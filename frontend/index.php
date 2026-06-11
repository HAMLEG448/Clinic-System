<?php
$pageTitle = "Dashboard";
include "includes/header.php";
include "includes/sidebar.php";

require_once "../backend/config/database.php";

// ดึงสถิติจาก DB
$totalPatients = $conn->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$todayVisits   = $conn->query("SELECT COUNT(*) FROM visits WHERE DATE(visit_date) = CURDATE()")->fetchColumn();
$unpaidCount   = $conn->query("SELECT COUNT(*) FROM payments WHERE payment_status = 'unpaid'")->fetchColumn();
$lowStockCount = $conn->query("SELECT COUNT(*) FROM medicines WHERE status = 'active'")->fetchColumn();

// รายการรอตรวจวันนี้
$waitingVisits = $conn->query(
    "SELECT v.visit_id, v.chief_complaint, v.status, v.visit_date,
            p.first_name, p.last_name
     FROM visits v
     INNER JOIN patients p ON v.patient_id = p.patient_id
     WHERE DATE(v.visit_date) = CURDATE()
     ORDER BY v.visit_date DESC
     LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="page-title">Dashboard</h2>

<!-- สถิติ -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-box p-3 border-start border-primary border-4">
            <h6 class="text-muted">ผู้ป่วยทั้งหมด</h6>
            <h2 class="mb-0"><?= number_format($totalPatients) ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3 border-start border-info border-4">
            <h6 class="text-muted">เข้ารักษาวันนี้</h6>
            <h2 class="mb-0"><?= number_format($todayVisits) ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3 border-start border-warning border-4">
            <h6 class="text-muted">รอชำระเงิน</h6>
            <h2 class="mb-0"><?= number_format($unpaidCount) ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3 border-start border-danger border-4">
            <h6 class="text-muted">ยาใกล้หมด</h6>
            <h2 class="mb-0"><?= number_format($lowStockCount) ?></h2>
        </div>
    </div>
</div>

<!-- Flow การทำงาน -->
<div class="card card-box p-4 mb-4">
    <h5>Flow การทำงานของคลินิก</h5>
    <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
        <a href="patients.php" class="badge bg-primary text-decoration-none fs-6">1. ลงทะเบียนผู้ป่วย</a>
        <span>→</span>
        <a href="visit-create.php" class="badge bg-info text-decoration-none fs-6">2. รับเข้ารักษา</a>
        <span>→</span>
        <span class="badge bg-warning text-dark fs-6">3. หมอตรวจ</span>
        <span>→</span>
        <span class="badge bg-success fs-6">4. ให้ยา/ทำแผล</span>
        <span>→</span>
        <span class="badge bg-dark fs-6">5. ชำระเงิน</span>
    </div>
</div>

<!-- รายการผู้ป่วยวันนี้ -->
<div class="card card-box p-4">
    <h5 class="mb-3">รายการเข้ารับบริการวันนี้</h5>
    <?php if (empty($waitingVisits)): ?>
        <p class="text-muted">ยังไม่มีผู้ป่วยวันนี้</p>
    <?php else: ?>
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>อาการหลัก</th>
                    <th>เวลา</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($waitingVisits as $v): ?>
                    <tr>
                        <td><?= $v["visit_id"] ?></td>
                        <td><?= htmlspecialchars($v["first_name"] . " " . $v["last_name"]) ?></td>
                        <td><?= htmlspecialchars($v["chief_complaint"]) ?></td>
                        <td><?= date("H:i", strtotime($v["visit_date"])) ?></td>
                        <td>
                            <?php $statusMap = [
                                "waiting"   => ["warning", "รอตรวจ"],
                                "examining" => ["info",    "กำลังตรวจ"],
                                "completed" => ["success", "เสร็จสิ้น"],
                                "cancelled" => ["secondary","ยกเลิก"],
                            ]; [$color, $label] = $statusMap[$v["status"]] ?? ["secondary", $v["status"]]; ?>
                            <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                        </td>
                        <td>
                            <a href="medical-record.php?visit_id=<?= $v["visit_id"] ?>"
                               class="btn btn-sm btn-outline-primary">บันทึกตรวจ</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
