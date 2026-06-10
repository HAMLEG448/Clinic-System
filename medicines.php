<?php
$pageTitle = "รายการยา";
include "includes/header.php";
include "includes/sidebar.php";

$medicines = [
    ["name" => "Paracetamol", "type" => "เม็ด", "stock" => 120, "price" => 2],
    ["name" => "Amoxicillin", "type" => "แคปซูล", "stock" => 80, "price" => 5],
    ["name" => "ยาทาแผล", "type" => "หลอด", "stock" => 25, "price" => 35],
];
?>

<h2 class="page-title">รายการยา</h2>

<div class="card card-box p-4">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ชื่อยา</th>
                <th>ประเภท</th>
                <th>คงเหลือ</th>
                <th>ราคา</th>
                <th>สถานะ</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($medicines as $medicine): ?>
                <tr>
                    <td><?= $medicine["name"] ?></td>
                    <td><?= $medicine["type"] ?></td>
                    <td><?= $medicine["stock"] ?></td>
                    <td><?= $medicine["price"] ?> บาท</td>
                    <td>
                        <?php if ($medicine["stock"] <= 30): ?>
                            <span class="badge bg-danger">ใกล้หมด</span>
                        <?php else: ?>
                            <span class="badge bg-success">ปกติ</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "includes/footer.php"; ?>