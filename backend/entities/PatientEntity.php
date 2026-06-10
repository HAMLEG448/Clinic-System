<?php

class PatientEntity
{
    public ?int $patient_id;
    public string $citizen_id;
    public string $first_name;
    public string $last_name;
    public ?string $gender;
    public ?string $birth_date;
    public string $phone;
    public string $address;
    public string $allergy;
    public string $underlying_disease;

    public function __construct(array $data)
    {
        $this->patient_id = $data["patient_id"] ?? null;
        $this->citizen_id = $data["citizen_id"] ?? "";
        $this->first_name = $data["first_name"] ?? "";
        $this->last_name = $data["last_name"] ?? "";
        $this->gender = $data["gender"] ?? null;
        $this->birth_date = $data["birth_date"] ?? null;
        $this->phone = $data["phone"] ?? "";
        $this->address = $data["address"] ?? "";
        $this->allergy = $data["allergy"] ?? "";
        $this->underlying_disease = $data["underlying_disease"] ?? "";
    }
}