<?php

namespace App\Back\Controllers;

use App\Back\Services\PdfTextExtractorService;
use App\Back\Utils\Navigation;
use App\Back\Validators\PdfUploadValidator;
use Core\Controller;

final class PdfTextExtractorController extends Controller
{
  public function __construct(
    private PdfTextExtractorService $pdfTextExtractorService = new PdfTextExtractorService(),
    private PdfUploadValidator $pdfUploadValidator = new PdfUploadValidator()
  ) {}

  public function index(string $section = 'dashboard'): void
  {
    $this->renderSection($section);
  }

  public function importPdf(): void
  {
    $this->renderSection('importar-pdf');
  }

  public function uploadPdf(): void
  {
    $validation = $this->pdfUploadValidator->validatePdfUpload($_FILES);
    if (!$validation['valid']) {
      $this->renderSection('importar-pdf', [
        'feedback' => $validation['message'],
        'feedbackType' => 'error',
      ]);
      return;
    }

    try {
      $extractedData = $this->pdfTextExtractorService->extractPurchaseOrder($validation['temporaryPath']);
    } catch (\Exception $exception) {
      $this->renderSection('importar-pdf', [
        'feedback' => 'Não foi possível processar este PDF ou cadastrar o fornecedor. Verifique se o arquivo não está corrompido ou protegido por senha.',
        'feedbackType' => 'error',
      ]);
      return;
    }

    $text = $extractedData->sourceText;
    $hasExtractedText = trim($text) !== '';
    $this->renderSection('importar-pdf', [
      'feedback' => $hasExtractedText
        ? 'PDF lido com sucesso.'
        : 'O PDF foi aberto, mas não contém texto extraível. Arquivos digitalizados podem exigir OCR.',
      'feedbackType' => $hasExtractedText ? 'success' : 'warning',
      'extractedText' => $hasExtractedText ? mb_substr($text, 0, 12000) : '',
      'extractedCharacterCount' => mb_strlen($text),
      'textPreviewTruncated' => mb_strlen($text) > 12000,
    ]);
  }

  private function renderSection(string $section, array $extraData = []): void
  {
    $navigationGroups = Navigation::groups(BASE_URL . '/estoque');
    $selectedSection = Navigation::findBySlug($navigationGroups, $section);

    if ($selectedSection === null) {
      $this->notFound();
      return;
    }

    $view = $section === 'importar-pdf' ? 'stock/pdf-import' : 'stock/index';
    $this->view(
      $view,
      array_merge([
        'title' => $selectedSection['label'] . ' | Sistema de Estoque',
        'activeSection' => $section,
        'navigationGroups' => $navigationGroups,
        'section' => $selectedSection,
      ], $extraData),
      'inventory'
    );
  }
}
