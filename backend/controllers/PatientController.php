<?php

require_once __DIR__ . "/../models/Patient.php";
require_once __DIR__ . "/../entities/PatientEntity.php";

class PatientController
{
    private Patient $patientModel;

    public function __construct()
    {
        $this->patientModel = new Patient();
    }

    public function index()
    {
        return $this->patientModel->getAll();
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: ../../frontend/patients-create.php");
            exit;
        }

        if (empty($_POST["first_name"]) || empty($_POST["last_name"])) {
            die("กรุณากรอกชื่อและนามสกุล");
        }

        $patient = new PatientEntity($_POST);
        $this->patientModel->create($patient);

        header("Location: ../../frontend/patients.php");
        exit;
    }
}