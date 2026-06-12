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

    public function existsByCitizenId(string $citizen_id): bool
    {
        $sql = "SELECT patient_id FROM patients WHERE citizen_id = :citizen_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":citizen_id" => $citizen_id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public function existsByPhone(string $phone): bool
    {
        $sql = "SELECT patient_id FROM patients WHERE phone = :phone LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":phone" => $phone
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
    public function existsByFirstName(string $first_name): bool
    {
        $sql = "SELECT patient_id FROM patients WHERE first_name = :first_name LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":first_name" => $first_name
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
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
    public function getAvailableForVisit(): array
    {
        $sql = "SELECT p.*
                FROM patients p
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM visits v
                    WHERE v.patient_id = p.patient_id
                    AND v.status IN ('waiting', 'examining')
                )
                ORDER BY p.first_name ASC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}