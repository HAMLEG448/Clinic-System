<?php

require_once __DIR__ . "/../controllers/PatientController.php";
require_once __DIR__ . "/../controllers/VisitController.php";
require_once __DIR__ . "/../controllers/MedicalRecordController.php";
require_once __DIR__ . "/../controllers/MedicineController.php";
require_once __DIR__ . "/../controllers/PaymentController.php";

$action = $_GET["action"] ?? "";

switch ($action) {

    // ── ผู้ป่วย ──────────────────────────────
    case "store_patient":
        (new PatientController())->store();
        break;

    // ── การเข้ารับบริการ ─────────────────────
    case "store_visit":
        (new VisitController())->store();
        break;

    case "start_queue":
        (new VisitController())->startQueue();
        break;

    // ── บันทึกการตรวจรักษา ───────────────────
    case "store_medical_record":
        (new MedicalRecordController())->store();
        break;

    // ── ยา ───────────────────────────────────
    case "store_medicine":
        (new MedicineController())->store();
        break;

    case "update_medicine":
        (new MedicineController())->update();
        break;

    case "delete_medicine":
        (new MedicineController())->delete();
        break;

    // ── ชำระเงิน ─────────────────────────────
    case "store_payment":
        (new PaymentController())->store();
        break;

    // ── fallback ──────────────────────────────
    default:
        http_response_code(404);
        echo "404 — ไม่พบ route ที่ร้องขอ (action = " . htmlspecialchars($action) . ")";
        break;
}
