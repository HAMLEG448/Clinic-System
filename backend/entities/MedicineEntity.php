<?php

class MedicineEntity
{
    public ?int $medicine_id;
    public string $medicine_name;
    public string $medicine_type;
    public string $unit;
    public int $stock_qty;
    public float $price;
    public ?string $expiry_date;
    public string $status;

    public function __construct(array $data)
    {
        $this->medicine_id   = $data["medicine_id"] ?? null;
        $this->medicine_name = $data["medicine_name"] ?? "";
        $this->medicine_type = $data["medicine_type"] ?? "";
        $this->unit          = $data["unit"] ?? "";
        $this->stock_qty     = (int) ($data["stock_qty"] ?? 0);
        $this->price         = (float) ($data["price"] ?? 0);
        $this->expiry_date   = ($data["expiry_date"] ?? "") !== "" ? $data["expiry_date"] : null;
        $this->status        = $data["status"] ?? "active";
    }
}
