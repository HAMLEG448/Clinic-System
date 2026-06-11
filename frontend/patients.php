<?php
$pageTitle = "ผู้ป่วย";
include "includes/header.php";
include "includes/sidebar.php";

require_once "../backend/controllers/PatientController.php";

$controller = new PatientController();
$patients = $controller->index();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-title">รายชื่อผู้ป่วย</h2>
    <a href="patients-create.php" class="btn btn-primary">+ เพิ่มผู้ป่วย</a>
</div>

<div class="card card-box p-4">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>รหัส</th>
                <th>ชื่อ-นามสกุล</th>
                <th>เพศ</th>
                <th>เบอร์โทร</th>
                <th>ประวัติแพ้ยา</th>
                <th>จัดการ</th>
            </tr>
        </thead>

        <tbody>
                        <?php foreach ($patients as $patient): ?>
                <tr>
                    <td><?= $patient["patient_id"] ?></td>
                    <td><?= htmlspecialchars($patient["first_name"] . " " . $patient["last_name"]) ?></td>
                    <td><?= htmlspecialchars($patient["gender"] ?? "-") ?></td>
                    <td><?= htmlspecialchars($patient["phone"] ?? "-") ?></td>
                    <td><?= htmlspecialchars($patient["allergy"] ?? "-") ?></td>
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