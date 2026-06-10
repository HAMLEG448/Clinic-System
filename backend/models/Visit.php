<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../entities/VisitEntity.php";

class Visit
{
    private PDO $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function create(VisitEntity $visit)
    {
        $sql = "INSERT INTO visits
                (patient_id, chief_complaint, symptom_detail, blood_pressure, temperature, weight, status)
                VALUES
                (:patient_id, :chief_complaint, :symptom_detail, :blood_pressure, :temperature, :weight, :status)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":patient_id" => $visit->patient_id,
            ":chief_complaint" => $visit->chief_complaint,
            ":symptom_detail" => $visit->symptom_detail,
            ":blood_pressure" => $visit->blood_pressure,
            ":temperature" => $visit->temperature,
            ":weight" => $visit->weight,
            ":status" => $visit->status
        ]);

        return $this->conn->lastInsertId();
    }

    public function findById($id)
    {
        $sql = "SELECT visits.*, patients.first_name, patients.last_name
                FROM visits
                INNER JOIN patients ON visits.patient_id = patients.patient_id
                WHERE visits.visit_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($visit_id, $status)
    {
        $sql = "UPDATE visits SET status = :status WHERE visit_id = :visit_id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":visit_id" => $visit_id,
            ":status" => $status
        ]);
    }
}