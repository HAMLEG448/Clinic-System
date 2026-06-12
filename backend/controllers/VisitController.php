<?php

require_once __DIR__ . "/../models/Visit.php";
require_once __DIR__ . "/../entities/VisitEntity.php";

class VisitController
{
    private Visit $visitModel;

    public function __construct()
    {
        $this->visitModel = new Visit();
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: ../../frontend/visit-create.php");
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $patient_id = (int) ($_POST["patient_id"] ?? 0);

        if (!$patient_id) {
            $_SESSION["visit_error"] = "กรุณาเลือกผู้ป่วย";
            header("Location: ../../frontend/visit-create.php");
            exit;
        }

        if ($this->visitModel->hasActiveVisitByPatientId($patient_id)) {
            $_SESSION["visit_error"] = "ผู้ป่วยคนนี้มีรายการรอตรวจหรือกำลังตรวจอยู่แล้ว";
            header("Location: ../../frontend/visit-create.php");
            exit;
        }

        $_POST["status"] = "waiting";

        $visit = new VisitEntity($_POST);
        $this->visitModel->create($visit);

        header("Location: ../../frontend/queue.php");
        exit;
    }

    public function show($visit_id)
    {
        return $this->visitModel->findById($visit_id);
    }

    public function queue(): array
    {
        return [
            "waiting" => $this->visitModel->getByStatus("waiting"),
            "examining" => $this->visitModel->getByStatus("examining")
        ];
    }

    public function startQueue(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: ../../frontend/queue.php");
            exit;
        }

        $visit_id = (int) ($_POST["visit_id"] ?? 0);

        if (!$visit_id) {
            header("Location: ../../frontend/queue.php");
            exit;
        }

        $this->visitModel->updateStatus($visit_id, "examining");

        header("Location: ../../frontend/medical-record.php?visit_id=" . $visit_id);
        exit;
    }
}
