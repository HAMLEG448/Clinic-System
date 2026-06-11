<?php

class MedicalRecordEntity
{
    public ?int $medical_record_id;
    public int $visit_id;
    public string $diagnosis;
    public string $physical_exam;
    public string $treatment_plan;
    public string $doctor_note;
    public ?string $follow_up_date;

    public function __construct(array $data)
    {
        $this->medical_record_id = $data["medical_record_id"] ?? null;
        $this->visit_id          = (int) ($data["visit_id"] ?? 0);
        $this->diagnosis         = $data["diagnosis"] ?? "";
        $this->physical_exam     = $data["physical_exam"] ?? "";
        $this->treatment_plan    = $data["treatment_plan"] ?? "";
        $this->doctor_note       = $data["doctor_note"] ?? "";
        $this->follow_up_date    = $data["follow_up_date"] !== "" ? ($data["follow_up_date"] ?? null) : null;
    }
}
