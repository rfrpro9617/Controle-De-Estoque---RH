<?php

namespace App\Back\Services;

use App\Back\DTOs\PurchaseOrderDTO;
use App\Back\Models\User;
use App\Back\Repositories\PdfTextExtractorRepository;
use App\Back\Utils\MoneyParser;
use App\Back\Utils\RegexCapture;
use Core\Auth;
use Core\Database;
use Smalot\PdfParser\Parser;
use DateTimeImmutable;
use LogicException;
use Throwable;
use UnexpectedValueException;

final class PdfTextExtractorService
{
  public function __construct(
    private PdfTextExtractorRepository $pdfTextExtractorRepository = new PdfTextExtractorRepository()
  ) {}

  public function extractPurchaseOrder(string $filePath): PurchaseOrderDTO
  {
    $document = (new Parser())->parseFile($filePath);

    $text = $document->getText();

    // Seção do pdf - Header

    // Número do Pedido
    preg_match('/Número do Pedido\s*(\d+)/u', $text, $orderMatch);
    // Situação do Pedido
    preg_match('/Situação\s+(.*?)\s+Detalhes do Pedido/us', $text, $situation);

    // Seção do pdf - Detalhes do Pedido

    // Dados do Pedido
    preg_match('/Data do Pedido\s+(.*?)\s+Ordem de Compra/us', $text, $data);
    // Ordem de Compra
    preg_match('/Ordem de Compra\s+(.*?)\s+Categoria/us', $text, $purchaseOrder);
    // Categoria
    preg_match('/Categoria\s+(.*?)\s+Info. Adicional/us', $text, $category);
    // Info. Adicional
    preg_match('/Info. Adicional\s+(.*?)\s+Local de Entrega/us', $text, $additionalInfo);

    // Seção do pdf - Local de Entrega

    // Nome / Razão Social
    preg_match('/Nome \/ Razão Social\s+(.*?)\s+Cód. Controle/us', $text, $deliveryLocationRegisteredCompanyName);
    // Cód. Controle
    preg_match('/Cód. Controle\s+(.*?)\s+Local de Entrega/us', $text, $controlCode);
    // Local de Entrega
    preg_match('/Cód\. Controle.*?\s+Local de Entrega\s+(.*?)\s+CNPJ \/ CPF/us', $text, $deliveryLocation);
    // CNPJ / CPF
    preg_match('/CNPJ \/ CPF\s+(.*?)\s+Endereço/us', $text, $deliveryLocationCnpjCpf);
    // Endereço
    preg_match('/Endereço\s+(.*?)\s+Observação/us', $text, $address);
    // Observações
    preg_match('/Observação\s+(.*?)\s+Cliente/us', $text, $observations);

    // Seção do pdf - Cliente

    // Nome / Razão Social
    preg_match('#Cliente.*?Nome / Razão Social\s+(.*?)\s+CNPJ / CPF#us', $text, $clientRegisteredCompanyName);
    // CNPJ / CPF
    preg_match('#Cliente.*?CNPJ / CPF\s+(.*?)\s+Endereço#us', $text, $clientCnpjCpf);
    // Endereço
    preg_match('#Cliente.*?Endereço\s+(.*?)\s+Solicitante#us', $text, $clientAddress);

    // Seção do pdf - Solicitante

    // Solicitante
    preg_match('#Solicitante.*?Solicitante\s+(.*?)\s+E-mail do Solicitante#us', $text, $requesterName);
    // E-mail do Solicitante
    preg_match('#E-mail do Solicitante\s+(.*?)\s+(?:Registro de Entrega|Transportadora|$)#us', $text, $requesterEmail);

    // Seção do pdf - Registro de Entrega

    // 1. Transportadora
    preg_match('#Registro de Entrega.*?Transportadora\s+(.*?)\s+Previsão Entrega#us', $text, $carrier);
    // 2. Previsão de Entrega
    preg_match('#Registro de Entrega.*?Previsão Entrega\s+(.*?)\s+Data de Embarque#us', $text, $deliveryForecast);
    // 3. Data de Embarque
    preg_match('#Registro de Entrega.*?Data de Embarque\s+(.*?)\s+Data da Entrega#us', $text, $shippingDate);
    // 4. Data da Entrega
    preg_match('#Registro de Entrega.*?Data da Entrega\s+(.*?)\s+Nome do Recebedor#us', $text, $deliveryDate);
    // 5. Nome do Recebedor
    preg_match('#Registro de Entrega.*?Nome do Recebedor\s+(.*?)\s+(?:Observações|Itens do Pedido|$)#us', $text, $receiverName);

    // Seção do pdf - Observações

    // 1. Observação do Pedido
    preg_match('#Observações.*?Observação do Pedido\s+(.*?)\s+Observação do Aprovador#us', $text, $orderNote);
    // 2. Observação do Aprovador
    preg_match('#Observações.*?Observação do Aprovador\s+(.*?)\s+Observação na Nota Fiscal#us', $text, $approverNote);
    // 3. Observação na Nota Fiscal
    preg_match('#Observações.*?Observação na Nota Fiscal\s+(.*?)\s+(?:Itens do Pedido|$)#us', $text, $invoiceNote);

    // Seção do pdf - Itens do Pedido
    preg_match('#Itens do Pedido(.*?)(?:Totais do Pedido|$)#us', $text, $itemsBlock);

    $extractedItems = [];
    if (!empty($itemsBlock[1])) {
      // Padrão para varrer cada item dentro do bloco extraído
      // A categoria começa na linha com "|" e pode continuar em outras até "Cód:".
      $pattern = '/(.+?)\n' . // Descrição do produto, que também pode ocupar várias linhas
        '((?=[^\n]*\|)[^\n]+(?:\n(?!\s*Cód:)[^\n]+)*?)\s*Cód:\s*([^\s\|]+).*?' . // Categoria / Caminho até o código do produto
        'Quant\s*\n\s*' .
        '(\d+)\s*\n\s*' . // Quantidade
        'R\$\s*([\d\.,]+)\s*\n\s*' . // Valor Unitário
        'R\$\s*([\d\.,]+)/us'; // Valor Total

      if (preg_match_all($pattern, $itemsBlock[1], $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
          $extractedItems[] = [
            'descricao' => trim(preg_replace('/\s+/u', ' ', $match[1]) ?? $match[1]),
            'categoria' => trim(preg_replace('/\s+/u', ' ', $match[2]) ?? $match[2]),
            'codigo' => trim($match[3]),
            'quantidade' => (int)$match[4],
            'valor_unitario' => MoneyParser::parseMoney($match[5]),
            'valor_total' => MoneyParser::parseMoney($match[6]),
          ];
        }
      }
    }

    // // Seção do pdf - Totais do Pedido

    $pattern = '/Totais do Pedido\s+' .
      'SubTotal:\s*R\$\s*([\d\.,]+)\s+' .
      'Taxa de Entrega:\s*R\$\s*([\d\.,]+)\s+' .
      'Taxa de Serviço:\s*R\$\s*([\d\.,]+)\s+' .
      'Valor Total:\s*R\$\s*([\d\.,]+)/us';

    $orderTotals = [];
    if (preg_match($pattern, $text, $matches)) {
      $orderTotals = [
        'subtotal' => MoneyParser::parseMoney($matches[1]),
        'deliverFee' => MoneyParser::parseMoney($matches[2]),
        'serviceFee' => MoneyParser::parseMoney($matches[3]),
        'totalAmount' => MoneyParser::parseMoney($matches[4]),
      ];
    }

    // // Seção do pdf - Subtotais por Fornecedor

    $pattern = '/Subtotais por Fornecedor\s+' .
      'FORNECEDOR\s+TAXA DE ENTREGA\s+VALOR DOS PRODUTOS\s+VALOR TOTAL\s+' .
      '([^\n]+?)\s+R\$\s*([\d\.,]+)\s+R\$\s*([\d\.,]+)\s+R\$\s*([\d\.,]+)/us';

    $supplierTotals = [];
    if (preg_match($pattern, $text, $matches)) {
      $supplierTotals = [
        'supplier' => trim($matches[1]),
        'deliverFee' => MoneyParser::parseMoney($matches[2]),
        'productsValue' => MoneyParser::parseMoney($matches[3]),
        'totalValue' => MoneyParser::parseMoney($matches[4]),
      ];
    }

    $supplier = $supplierTotals['supplier'] ?? null;
    $orderNumber = RegexCapture::firstGroup($orderMatch);
    $orderDate = RegexCapture::firstGroup($data);

    if ($orderNumber === null || $orderDate === null || !is_string($supplier) || trim($supplier) === '') {
      throw new UnexpectedValueException('O PDF não contém número, data ou fornecedor suficientes para cadastrar o pedido.');
    }

    $parsedDate = DateTimeImmutable::createFromFormat('!d/m/Y H:i:s', $orderDate);
    $dateErrors = DateTimeImmutable::getLastErrors();
    if (
      $parsedDate === false
      || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
    ) {
      throw new UnexpectedValueException('A data do pedido no PDF está em um formato inválido.');
    }

    $connection = Database::connection();
    try {
      $connection->beginTransaction();
      $requester = Auth::user();
      if (!$requester instanceof User) {
        throw new LogicException('Não foi possível identificar o usuário solicitante do pedido.');
      }

      $supplierId = $this->pdfTextExtractorRepository->insertSupplier($supplier);
      $orderId = $this->pdfTextExtractorRepository->insertPurchaseOrder(
        $orderNumber,
        $parsedDate->format('Y-m-d'),
        $supplierId,
        $requester->id
      );

      foreach ($extractedItems as $item) {
        $itemCategory = trim(explode('|', $item['categoria'], 2)[0]);
        if ($itemCategory === '') {
          continue;
        }

        $this->pdfTextExtractorRepository->insertMaterial(
          $itemCategory,
          $item['descricao'],
          $item['codigo']
        );
      }
      $connection->commit();
    } catch (Throwable $exception) {
      if ($connection->inTransaction()) {
        $connection->rollBack();
      }
      throw $exception;
    }

    return new PurchaseOrderDTO(
      orderNumber: $orderNumber,
      situation: RegexCapture::firstGroup($situation),
      orderDate: $orderDate,
      purchaseOrder: RegexCapture::firstGroup($purchaseOrder),
      category: RegexCapture::firstGroup($category),
      additionalInfo: RegexCapture::firstGroup($additionalInfo),
      deliveryLocationRegisteredCompanyName: RegexCapture::firstGroup($deliveryLocationRegisteredCompanyName),
      controlCode: RegexCapture::firstGroup($controlCode),
      deliveryLocation: RegexCapture::firstGroup($deliveryLocation),
      deliveryLocationCnpjCpf: RegexCapture::firstGroup($deliveryLocationCnpjCpf),
      deliveryAddress: RegexCapture::firstGroup($address),
      deliveryObservations: RegexCapture::firstGroup($observations),
      clientRegisteredCompanyName: RegexCapture::firstGroup($clientRegisteredCompanyName),
      clientCnpjCpf: RegexCapture::firstGroup($clientCnpjCpf),
      clientAddress: RegexCapture::firstGroup($clientAddress),
      requesterName: RegexCapture::firstGroup($requesterName),
      requesterEmail: RegexCapture::firstGroup($requesterEmail),
      carrier: RegexCapture::firstGroup($carrier),
      deliveryForecast: RegexCapture::firstGroup($deliveryForecast),
      shippingDate: RegexCapture::firstGroup($shippingDate),
      deliveryDate: RegexCapture::firstGroup($deliveryDate),
      receiverName: RegexCapture::firstGroup($receiverName),
      orderNote: RegexCapture::firstGroup($orderNote),
      approverNote: RegexCapture::firstGroup($approverNote),
      invoiceNote: RegexCapture::firstGroup($invoiceNote),
      items: $extractedItems,
      orderTotals: $orderTotals ?: null,
      supplierTotals: $supplierTotals ?: null,
      sourceText: $text,
      orderId: $orderId,
    );
  }
}
