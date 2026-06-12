<?php

require_once __DIR__ . "/../models/Payment.php";
require_once __DIR__ . "/../models/Medicine.php";
require_once __DIR__ . "/../models/MedicalRecord.php";
require_once __DIR__ . "/../models/Visit.php";
require_once __DIR__ . "/../entities/PaymentEntity.php";

class PaymentController
{
    private Payment       $paymentModel;
    private Medicine      $medicineModel;
    private MedicalRecord $medicalRecordModel;
    private Visit         $visitModel;

    // ราคาบริการ (สามารถย้ายไป DB ได้ในอนาคต)
    private const SERVICE_PRICES = [
        "ตรวจทั่วไป" => 100,
        "ล้างแผล"     => 50,
        "ทำแผล"       => 80,
        "ฉีดยา"       => 70,
        "เย็บแผล"    => 150,
    ];

    public function __construct()
    {
        $this->paymentModel       = new Payment();
        $this->medicineModel      = new Medicine();
        $this->medicalRecordModel = new MedicalRecord();
        $this->visitModel         = new Visit();
    }

    /**
     * เตรียมข้อมูลสำหรับหน้า payment.php
     * คำนวณราคาจาก services[] และ medicine_id[] ที่ส่งมาจาก medical-record
     */
    public function show(int $visit_id): array
    {
        $visit  = $this->visitModel->findById($visit_id);
        $record = $this->medicalRecordModel->findByVisitId($visit_id);

        // คำนวณค่าบริการ
        $services     = $_SESSION["services"]     ?? [];
        $medicineIds  = $_SESSION["medicine_ids"] ?? [];
        $quantities   = $_SESSION["quantities"]   ?? [];

        $serviceTotal  = 0;
        foreach ($services as $svc) {
            $serviceTotal += self::SERVICE_PRICES[$svc] ?? 0;
        }

        // คำนวณค่ายา
        $medicineTotal   = 0;
        $medicineDetails = [];
        foreach ($medicineIds as $idx => $mid) {
            $mid = (int) $mid;
            $qty = (int) ($quantities[$idx] ?? 1);
            if (!$mid || !$qty) continue;
            $med = $this->medicineModel->findById($mid);
            if ($med) {
                $subtotal          = $med["price"] * $qty;
                $medicineTotal    += $subtotal;
                $medicineDetails[] = [
                    "name"     => $med["medicine_name"],
                    "unit"     => $med["unit"],
                    "price"    => $med["price"],
                    "qty"      => $qty,
                    "subtotal" => $subtotal,
                ];
            }
        }

        $totalAmount = $serviceTotal + $medicineTotal;

        // ถ้าบันทึก payment ไปแล้ว ดึงข้อมูลที่มีอยู่
        $existingPayment = $this->paymentModel->findByVisitId($visit_id);

        return compact(
            "visit", "record", "services",
            "serviceTotal", "medicineDetails", "medicineTotal",
            "totalAmount", "existingPayment"
        );
    }

    /**
     * รับ POST จากฟอร์ม payment.php → บันทึก payment
     */
    public function store(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: ../../frontend/payment.php");
            exit;
        }

        $visit_id = (int) ($_POST["visit_id"] ?? 0);
        if (!$visit_id) die("ไม่พบข้อมูล visit");

        $serviceTotal  = (float) ($_POST["service_total"]  ?? 0);
        $medicineTotal = (float) ($_POST["medicine_total"] ?? 0);
        $totalAmount   = $serviceTotal + $medicineTotal;
        $discount      = (float) ($_POST["discount"] ?? 0);
        $netAmount     = max(0, $totalAmount - $discount);

        // ตัดสต็อกยา
        $medicineIds = $_POST["medicine_id"] ?? [];
        $quantities  = $_POST["quantity"]    ?? [];
        foreach ($medicineIds as $idx => $mid) {
            $mid = (int) $mid;
            $qty = (int) ($quantities[$idx] ?? 0);
            if ($mid && $qty) {
                $success = $this->medicineModel->reduceStock($mid, $qty);

                if (!$success) {
                    die("จำนวนยาไม่พอในสต็อก หรือยาถูกปิดใช้งานแล้ว");
                }
            }
        }

        $data = [
            "visit_id"       => $visit_id,
            "service_total"  => $serviceTotal,
            "medicine_total" => $medicineTotal,
            "total_amount"   => $totalAmount,
            "discount"       => $discount,
            "net_amount"     => $netAmount,
            "payment_method" => $_POST["payment_method"] ?? "cash",
            "payment_status" => "paid",
            "paid_at"        => date("Y-m-d H:i:s"),
        ];

        $payment    = new PaymentEntity($data);
        $payment_id = $this->paymentModel->create($payment);

        header("Location: ../../frontend/payment.php?visit_id={$visit_id}&payment_id={$payment_id}&done=1");
        exit;
    }
}
