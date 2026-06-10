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

        if (empty($_POST["patient_id"])) {
            die("ไม่พบข้อมูลผู้ป่วย");
        }

        $visit = new VisitEntity($_POST);
        $visit_id = $this->visitModel->create($visit);

        header("Location: ../../frontend/medical-record.php?visit_id=" . $visit_id);
        exit;
    }

    public function show($visit_id)
    {
        return $this->visitModel->findById($visit_id);
    }
}