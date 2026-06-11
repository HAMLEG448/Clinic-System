<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../entities/PaymentEntity.php";

class Payment
{
    private PDO $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function create(PaymentEntity $payment): int
    {
        $sql = "INSERT INTO payments
                    (visit_id, service_total, medicine_total, total_amount,
                     discount, net_amount, payment_method, payment_status, paid_at)
                VALUES
                    (:visit_id, :service_total, :medicine_total, :total_amount,
                     :discount, :net_amount, :payment_method, :payment_status, :paid_at)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":visit_id"       => $payment->visit_id,
            ":service_total"  => $payment->service_total,
            ":medicine_total" => $payment->medicine_total,
            ":total_amount"   => $payment->total_amount,
            ":discount"       => $payment->discount,
            ":net_amount"     => $payment->net_amount,
            ":payment_method" => $payment->payment_method,
            ":payment_status" => $payment->payment_status,
            ":paid_at"        => $payment->paid_at,
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function findByVisitId(int $visit_id): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM payments WHERE visit_id = :visit_id ORDER BY payment_id DESC LIMIT 1"
        );
        $stmt->execute([":visit_id" => $visit_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->conn->prepare("SELECT * FROM payments WHERE payment_id = :id");
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUnpaid(): array
    {
        $sql = "SELECT p.*, v.visit_date, pt.first_name, pt.last_name
                FROM payments p
                INNER JOIN visits v   ON p.visit_id   = v.visit_id
                INNER JOIN patients pt ON v.patient_id = pt.patient_id
                WHERE p.payment_status = 'unpaid'
                ORDER BY p.payment_id DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markPaid(int $payment_id): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE payments SET payment_status = 'paid', paid_at = NOW() WHERE payment_id = :id"
        );
        return $stmt->execute([":id" => $payment_id]);
    }
}
