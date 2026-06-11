<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../entities/MedicalRecordEntity.php";

class MedicalRecord
{
    private PDO $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function create(MedicalRecordEntity $record): int
    {
        $sql = "INSERT INTO medical_records
                (visit_id, diagnosis, physical_exam, treatment_plan, doctor_note, follow_up_date)
                VALUES
                (:visit_id, :diagnosis, :physical_exam, :treatment_plan, :doctor_note, :follow_up_date)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":visit_id"       => $record->visit_id,
            ":diagnosis"      => $record->diagnosis,
            ":physical_exam"  => $record->physical_exam,
            ":treatment_plan" => $record->treatment_plan,
            ":doctor_note"    => $record->doctor_note,
            ":follow_up_date" => $record->follow_up_date,
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function findByVisitId(int $visit_id): array|false
    {
        $sql  = "SELECT * FROM medical_records WHERE visit_id = :visit_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":visit_id" => $visit_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        $sql = "SELECT mr.*, v.visit_date, p.first_name, p.last_name
                FROM medical_records mr
                INNER JOIN visits v ON mr.visit_id = v.visit_id
                INNER JOIN patients p ON v.patient_id = p.patient_id
                ORDER BY mr.created_at DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
