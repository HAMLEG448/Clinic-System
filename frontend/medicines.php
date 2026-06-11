<?php
$pageTitle = "จัดการยา";
include "includes/header.php";
include "includes/sidebar.php";

require_once "../backend/controllers/MedicineController.php";

$controller = new MedicineController();
$medicines  = $controller->index();

$success = $_GET["success"] ?? "";
$successMsg = match ($success) {
    "1" => "เพิ่มยาสำเร็จ",
    "2" => "แก้ไขยาสำเร็จ",
    "3" => "ปิดการใช้งานยาสำเร็จ",
    default => ""
};

// จำนวนยาใกล้หมด (stock <= 30)
$lowStock = array_filter($medicines, fn($m) => $m["stock_qty"] <= 30 && $m["status"] === "active");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-title">จัดการยา</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
        + เพิ่มยาใหม่
    </button>
</div>

<?php if ($successMsg): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $successMsg ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (count($lowStock) > 0): ?>
    <div class="alert alert-warning">
        ⚠️ มียา <?= count($lowStock) ?> รายการที่ใกล้หมดสต็อก (เหลือ ≤ 30 หน่วย)
    </div>
<?php endif; ?>

<!-- ตารางรายการยา -->
<div class="card card-box p-4">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>รหัส</th>
                <th>ชื่อยา</th>
                <th>ประเภท</th>
                <th>หน่วย</th>
                <th>คงเหลือ</th>
                <th>ราคา/หน่วย</th>
                <th>วันหมดอายุ</th>
                <th>สถานะ</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($medicines as $med): ?>
                <tr>
                    <td><?= $med["medicine_id"] ?></td>
                    <td><?= htmlspecialchars($med["medicine_name"]) ?></td>
                    <td><?= htmlspecialchars($med["medicine_type"] ?: "-") ?></td>
                    <td><?= htmlspecialchars($med["unit"] ?: "-") ?></td>
                    <td>
                        <?php if ($med["stock_qty"] <= 30 && $med["status"] === "active"): ?>
                            <span class="badge bg-danger"><?= $med["stock_qty"] ?></span>
                        <?php else: ?>
                            <span class="badge bg-success"><?= $med["stock_qty"] ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= number_format($med["price"], 2) ?> บาท</td>
                    <td><?= $med["expiry_date"] ?: "-" ?></td>
                    <td>
                        <?php if ($med["status"] === "active"): ?>
                            <span class="badge bg-success">ใช้งาน</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">ปิดใช้งาน</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary"
                            onclick="openEditModal(<?= htmlspecialchars(json_encode($med), ENT_QUOTES) ?>)">
                            แก้ไข
                        </button>
                        <?php if ($med["status"] === "active"): ?>
                            <a href="../backend/routes/web.php?action=delete_medicine&id=<?= $med["medicine_id"] ?>"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('ยืนยันการปิดใช้งานยา: <?= addslashes($med["medicine_name"]) ?>?')">
                                ปิดใช้
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal เพิ่มยา -->
<div class="modal fade" id="addMedicineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มยาใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../backend/routes/web.php?action=store_medicine" method="post">
                <div class="modal-body">
                    <?php include "includes/medicine-form-fields.php"; ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal แก้ไขยา -->
<div class="modal fade" id="editMedicineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">แก้ไขยา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../backend/routes/web.php?action=update_medicine" method="post">
                <div class="modal-body">
                    <input type="hidden" name="medicine_id" id="edit_medicine_id">
                    <?php include "includes/medicine-form-fields.php"; ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(med) {
    const modal = document.getElementById("editMedicineModal");
    modal.querySelector("#edit_medicine_id").value        = med.medicine_id;
    modal.querySelector("[name='medicine_name']").value   = med.medicine_name;
    modal.querySelector("[name='medicine_type']").value   = med.medicine_type;
    modal.querySelector("[name='unit']").value            = med.unit;
    modal.querySelector("[name='stock_qty']").value       = med.stock_qty;
    modal.querySelector("[name='price']").value           = med.price;
    modal.querySelector("[name='expiry_date']").value     = med.expiry_date ?? "";
    modal.querySelector("[name='status']").value          = med.status;
    new bootstrap.Modal(modal).show();
}
</script>

<?php include "includes/footer.php"; ?>
