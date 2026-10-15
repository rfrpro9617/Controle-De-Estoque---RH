<?php

namespace App\Back\DTOs;

final class PurchaseOrderDTO
{
  public function __construct(
    public readonly ?string $orderNumber = null,
    public readonly ?string $situation = null,
    public readonly ?string $orderDate = null,
    public readonly ?string $purchaseOrder = null,
    public readonly ?string $category = null,
    public readonly ?string $additionalInfo = null,
    public readonly ?string $deliveryLocationRegisteredCompanyName = null,
    public readonly ?string $controlCode = null,
    public readonly ?string $deliveryLocation = null,
    public readonly ?string $deliveryLocationCnpjCpf = null,
    public readonly ?string $deliveryAddress = null,
    public readonly ?string $deliveryObservations = null,
    public readonly ?string $clientRegisteredCompanyName = null,
    public readonly ?string $clientCnpjCpf = null,
    public readonly ?string $clientAddress = null,
    public readonly ?string $requesterName = null,
    public readonly ?string $requesterEmail = null,
    public readonly ?string $carrier = null,
    public readonly ?string $deliveryForecast = null,
    public readonly ?string $shippingDate = null,
    public readonly ?string $deliveryDate = null,
    public readonly ?string $receiverName = null,
    public readonly ?string $orderNote = null,
    public readonly ?string $approverNote = null,
    public readonly ?string $invoiceNote = null,
    public readonly array $items = [],
    public readonly ?array $orderTotals = null,
    public readonly ?array $supplierTotals = null,
    public readonly string $sourceText = '',
  ) {}
}
