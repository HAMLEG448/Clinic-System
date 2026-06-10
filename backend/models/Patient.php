<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../entities/PatientEntity.php";

class Patient
{
    private PDO $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM patients ORDER BY patient_id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(PatientEntity $patient)
    {
        $sql = "INSERT INTO patients
                (citizen_id, first_name, last_name, gender, birth_date, phone, address, allergy, underlying_disease)
                VALUES
                (:citizen_id, :first_name, :last_name, :gender, :birth_date, :phone, :address, :allergy, :underlying_disease)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":citizen_id" => $patient->citizen_id,
            ":first_name" => $patient->first_name,
            ":last_name" => $patient->last_name,
            ":gender" => $patient->gender,
            ":birth_date" => $patient->birth_date ?: null,
            ":phone" => $patient->phone,
            ":address" => $patient->address,
            ":allergy" => $patient->allergy,
            ":underlying_disease" => $patient->underlying_disease
        ]);
    }
}