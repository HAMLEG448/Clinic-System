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
    // ถ้าแก้จำนวนยาให้เหลือ 0 หรือน้อยกว่า 0
    // ให้เปลี่ยนสถานะยาเป็น inactive อัตโนมัติ
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
    // ถ้าจำนวนยาที่จะตัดไม่ถูกต้อง ไม่ต้องทำงานต่อ
    if ($qty <= 0) {
        return false;
    }

    // ลดจำนวนยาใน stock
    // ถ้าหลังจากลดแล้วเหลือ 0 ให้ปิดการใช้งานยาอัตโนมัติ
    $sql = "UPDATE medicines
            SET 
                stock_qty = stock_qty - :qty,
                status = CASE
                    WHEN stock_qty - :qty <= 0 THEN 'inactive'
                    ELSE status
                END
            WHERE medicine_id = :id
            AND stock_qty >= :qty
            AND status = 'active'";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        ":qty" => $qty,
        ":id"  => $medicine_id
    ]);

    return $stmt->rowCount() > 0;
    }
}
