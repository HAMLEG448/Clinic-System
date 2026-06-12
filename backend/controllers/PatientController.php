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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: ../../frontend/patients-create.php");
            exit;
        }

        $citizen_id = trim($_POST["citizen_id"] ?? "");
        $first_name = trim($_POST["first_name"] ?? "");
        $last_name = trim($_POST["last_name"] ?? "");
        $phone = trim($_POST["phone"] ?? "");

        $_POST["citizen_id"] = $citizen_id;
        $_POST["first_name"] = $first_name;
        $_POST["last_name"] = $last_name;
        $_POST["phone"] = $phone;

        $errors = [];

        if ($citizen_id === "") {
            $errors["citizen_id"] = "กรุณากรอกเลขบัตรประชาชน";
        } elseif ($this->patientModel->existsByCitizenId($citizen_id)) {
            $errors["citizen_id"] = "กรอกเลขบัตรประชาชนซ้ำไม่ได้";
        }

        if ($first_name === "") {
            $errors["first_name"] = "กรุณากรอกชื่อ";
        }

        if ($last_name === "") {
            $errors["last_name"] = "กรุณากรอกนามสกุล";
        }

        if ($first_name !== "" && $last_name !== "") {
            if ($this->patientModel->existsByFullName($first_name, $last_name)) {
                $errors["full_name"] = "ชื่อและนามสกุลนี้มีอยู่แล้ว";
            }
        }

        if ($phone === "") {
            $errors["phone"] = "กรุณากรอกเบอร์โทร";
        } elseif ($this->patientModel->existsByPhone($phone)) {
            $errors["phone"] = "กรอกเบอร์โทรซ้ำไม่ได้";
        }

        if (!empty($errors)) {
            $_SESSION["patient_errors"] = $errors;
            $_SESSION["patient_old"] = $_POST;

            header("Location: ../../frontend/patients-create.php");
            exit;
        }

        $patient = new PatientEntity($_POST);
        $this->patientModel->create($patient);

        unset($_SESSION["patient_errors"]);
        unset($_SESSION["patient_old"]);

        header("Location: ../../frontend/patients.php");
        exit;
    }
    public function availableForVisit(): array
    {
        return $this->patientModel->getAvailableForVisit();
    }
}