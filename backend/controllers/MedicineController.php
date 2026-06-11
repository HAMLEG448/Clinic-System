<?php

require_once __DIR__ . "/../models/Medicine.php";
require_once __DIR__ . "/../entities/MedicineEntity.php";

class MedicineController
{
    private Medicine $medicineModel;

    public function __construct()
    {
        $this->medicineModel = new Medicine();
    }

    public function index(): array
    {
        return $this->medicineModel->getAll();
    }

    public function store(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: ../../frontend/medicines.php");
            exit;
        }

        if (empty($_POST["medicine_name"])) {
            die("กรุณากรอกชื่อยา");
        }

        $medicine = new MedicineEntity($_POST);
        $this->medicineModel->create($medicine);

        header("Location: ../../frontend/medicines.php?success=1");
        exit;
    }

    public function update(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: ../../frontend/medicines.php");
            exit;
        }

        $medicine = new MedicineEntity($_POST);

        if (!$medicine->medicine_id) {
            die("ไม่พบรหัสยา");
        }

        $this->medicineModel->update($medicine);

        header("Location: ../../frontend/medicines.php?success=2");
        exit;
    }

    public function delete(): void
    {
        $id = (int) ($_GET["id"] ?? 0);

        if (!$id) {
            die("ไม่พบรหัสยา");
        }

        $this->medicineModel->delete($id);

        header("Location: ../../frontend/medicines.php?success=3");
        exit;
    }
}
