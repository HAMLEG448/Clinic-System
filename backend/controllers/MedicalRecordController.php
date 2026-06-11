<?php

require_once __DIR__ . "/../models/MedicalRecord.php";
require_once __DIR__ . "/../models/Medicine.php";
require_once __DIR__ . "/../models/Visit.php";
require_once __DIR__ . "/../entities/MedicalRecordEntity.php";

class MedicalRecordController
{
    private MedicalRecord $medicalRecordModel;
    private Medicine $medicineModel;
    private Visit $visitModel;

    public function __construct()
    {
        $this->medicalRecordModel = new MedicalRecord();
        $this->medicineModel      = new Medicine();
        $this->visitModel         = new Visit();
    }

    public function show(int $visit_id): array
    {
        $visit     = $this->visitModel->findById($visit_id);
        $medicines = $this->medicineModel->getActive();
        $record    = $this->medicalRecordModel->findByVisitId($visit_id);

        return compact("visit", "medicines", "record");
    }

    public function store(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: ../../frontend/medical-record.php");
            exit;
        }

        $visit_id = (int) ($_POST["visit_id"] ?? 0);

        if (!$visit_id) {
            die("ไม่พบข้อมูลการเข้ารับบริการ");
        }

        // บันทึก medical record
        $record = new MedicalRecordEntity($_POST);
        $this->medicalRecordModel->create($record);

        // อัปเดตสถานะ visit เป็น completed
        $this->visitModel->updateStatus($visit_id, "completed");

        // ── เก็บข้อมูลบริการ+ยาลง session เพื่อส่งต่อไป payment.php ──
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION["payment_data"][$visit_id] = [
            "services"    => $_POST["services"]    ?? [],
            "medicine_id" => $_POST["medicine_id"] ?? [],
            "quantity"    => $_POST["quantity"]     ?? [],
        ];

        header("Location: ../../frontend/payment.php?visit_id=" . $visit_id);
        exit;
    }
}
