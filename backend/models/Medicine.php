<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../entities/MedicineEntity.php";

class Medicine
{
    private PDO $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM medicines ORDER BY medicine_name ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive(): array
    {
        $sql  = "SELECT * FROM medicines WHERE status = 'active' AND stock_qty > 0 ORDER BY medicine_name ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->conn->prepare("SELECT * FROM medicines WHERE medicine_id = :id");
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(MedicineEntity $medicine): bool
    {
        $sql = "INSERT INTO medicines
                (medicine_name, medicine_type, unit, stock_qty, price, expiry_date, status)
                VALUES
                (:medicine_name, :medicine_type, :unit, :stock_qty, :price, :expiry_date, :status)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":medicine_name" => $medicine->medicine_name,
            ":medicine_type" => $medicine->medicine_type,
            ":unit"          => $medicine->unit,
            ":stock_qty"     => $medicine->stock_qty,
            ":price"         => $medicine->price,
            ":expiry_date"   => $medicine->expiry_date,
            ":status"        => $medicine->status,
        ]);
    }

    public function update(MedicineEntity $medicine): bool
    {
        if ($medicine->stock_qty <= 0) {
            $medicine->stock_qty = 0;
            $medicine->status = "inactive";
        }

        $sql = "UPDATE medicines SET
                    medicine_name = :medicine_name,
                    medicine_type = :medicine_type,
                    unit          = :unit,
                    stock_qty     = :stock_qty,
                    price         = :price,
                    expiry_date   = :expiry_date,
                    status        = :status
                WHERE medicine_id = :medicine_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":medicine_name" => $medicine->medicine_name,
            ":medicine_type" => $medicine->medicine_type,
            ":unit"          => $medicine->unit,
            ":stock_qty"     => $medicine->stock_qty,
            ":price"         => $medicine->price,
            ":expiry_date"   => $medicine->expiry_date,
            ":status"        => $medicine->status,
            ":medicine_id"   => $medicine->medicine_id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("UPDATE medicines SET status = 'inactive' WHERE medicine_id = :id");
        return $stmt->execute([":id" => $id]);
    }

    public function reduceStock(int $medicine_id, int $qty): bool
    {
        if ($qty <= 0) {
            return false;
        }

        try {
            $this->conn->beginTransaction();

            // ลดจำนวนยาอย่างเดียวก่อน ห้ามเปลี่ยน status ตรงนี้
            $sql = "UPDATE medicines
                    SET stock_qty = stock_qty - :qty
                    WHERE medicine_id = :id
                    AND stock_qty >= :qty
                    AND status = 'active'";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":qty" => $qty,
                ":id"  => $medicine_id
            ]);

            if ($stmt->rowCount() === 0) {
                $this->conn->rollBack();
                return false;
            }

            // ปิดใช้งานเฉพาะตอน stock เหลือ 0 จริง ๆ เท่านั้น
            $closeStmt = $this->conn->prepare(
                "UPDATE medicines
                 SET status = 'inactive'
                 WHERE medicine_id = :id
                 AND stock_qty = 0
                 AND status = 'active'"
            );
            $closeStmt->execute([":id" => $medicine_id]);

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            return false;
        }
    }
}
