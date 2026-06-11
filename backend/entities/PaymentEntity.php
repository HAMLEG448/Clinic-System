<?php

class PaymentEntity
{
    public ?int   $payment_id;
    public int    $visit_id;
    public float  $service_total;
    public float  $medicine_total;
    public float  $total_amount;
    public float  $discount;
    public float  $net_amount;
    public string $payment_method;
    public string $payment_status;
    public ?string $paid_at;

    public function __construct(array $data)
    {
        $this->payment_id     = $data["payment_id"] ?? null;
        $this->visit_id       = (int)   ($data["visit_id"]       ?? 0);
        $this->service_total  = (float) ($data["service_total"]  ?? 0);
        $this->medicine_total = (float) ($data["medicine_total"] ?? 0);
        $this->total_amount   = (float) ($data["total_amount"]   ?? 0);
        $this->discount       = (float) ($data["discount"]       ?? 0);
        $this->net_amount     = (float) ($data["net_amount"]     ?? 0);
        $this->payment_method = $data["payment_method"] ?? "cash";
        $this->payment_status = $data["payment_status"] ?? "unpaid";
        $this->paid_at        = $data["paid_at"] ?? null;
    }
}
