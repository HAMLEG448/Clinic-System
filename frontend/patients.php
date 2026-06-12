<?php
$pageTitle = "ทะเบียน";
include "includes/header.php";
include "includes/sidebar.php";

require_once "../backend/controllers/PatientController.php";

$controller = new PatientController();
$patients = $controller->index();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-title">รายชื่อ/ประวัติผู้ป่วยที่ลงทะเบียน</h2>
    <a href="patients-create.php" class="btn btn-primary">+ เพิ่มผู้ป่วย</a>
</div>

<div class="card card-box p-4">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>ชื่อ-นามสกุล</th>
                <th>เลขบัตรประชาชน</th>
                <th>เบอร์โทร</th>
                <th>แพ้ยา</th>
                <th>วันที่ลงทะเบียน</th>
                <th>จัดการ</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($patients as $index => $patient): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($patient["first_name"] . " " . $patient["last_name"]) ?></td>
                    <td><?= htmlspecialchars($patient["citizen_id"]) ?></td>
                    <td><?= htmlspecialchars($patient["phone"]) ?></td>
                    <td><?= $patient["allergy"] ? htmlspecialchars($patient["allergy"]) : "-" ?></td>
                    <td><?= htmlspecialchars($patient["created_at"]) ?></td>
                    <td>
                        <a href="visit-create.php?patient_id=<?= $patient["patient_id"] ?>" class="btn btn-sm btn-success">
                            รับเข้ารักษา
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "includes/footer.php"; ?>
