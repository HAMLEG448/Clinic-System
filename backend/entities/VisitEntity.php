<?php

class VisitEntity
{
    public ?int $visit_id;
    public int $patient_id;
    public string $chief_complaint;
    public string $symptom_detail;
    public string $blood_pressure;
    public ?float $temperature;
    public ?float $weight;
    public string $status;

    public function __construct(array $data)
    {
        $this->visit_id = $data["visit_id"] ?? null;
        $this->patient_id = (int) ($data["patient_id"] ?? 0);
        $this->chief_complaint = $data["chief_complaint"] ?? "";
        $this->symptom_detail = $data["symptom_detail"] ?? "";
        $this->blood_pressure = $data["blood_pressure"] ?? "";
        $this->temperature = $data["temperature"] !== "" ? (float) $data["temperature"] : null;
        $this->weight = $data["weight"] !== "" ? (float) $data["weight"] : null;
        $this->status = $data["status"] ?? "waiting";
    }
}