<?php
$pageTitle = "ผู้ป่วย";
include "includes/header.php";
include "includes/sidebar.php";

$patients = [
    [
        "id" => 1,
        "name" => "นายสมชาย ใจดี",
        "phone" => "0812345678",
        "gender" => "ชาย",
        "allergy" => "แพ้ยา Penicillin"
    ],
    [
        "id" => 2,
        "name" => "นางสาวมาลี สดใส",
        "phone" => "0899999999",
        "gender" => "หญิง",
        "allergy" => "ไม่มี"
    ],
];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-title">รายชื่อผู้ป่วย</h2>
    <a href="patient-create.php" class="btn btn-primary">+ เพิ่มผู้ป่วย</a>
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
                    <td><?= $patient["id"] ?></td>
                    <td><?= $patient["name"] ?></td>
                    <td><?= $patient["gender"] ?></td>
                    <td><?= $patient["phone"] ?></td>
                    <td><?= $patient["allergy"] ?></td>
                    <td>
                        <a href="visit-create.php?patient_id=<?= $patient["id"] ?>" class="btn btn-sm btn-success">
                            รับเข้ารักษา
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "includes/footer.php"; ?>