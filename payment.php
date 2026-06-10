<?php
$pageTitle = "ชำระเงิน";
include "includes/header.php";
include "includes/sidebar.php";

$serviceTotal = 150;
$medicineTotal = 80;
$totalAmount = $serviceTotal + $medicineTotal;
?>

<h2 class="page-title">ชำระเงิน</h2>

<div class="card card-box p-4">
    <h5>สรุปค่าใช้จ่าย</h5>

    <table class="table">
        <tr>
            <td>ค่าบริการ / ค่าตรวจ / ทำแผล</td>
            <td class="text-end"><?= $serviceTotal ?> บาท</td>
        </tr>

        <tr>
            <td>ค่ายา</td>
            <td class="text-end"><?= $medicineTotal ?> บาท</td>
        </tr>

        <tr>
            <th>รวมทั้งหมด</th>
            <th class="text-end"><?= $totalAmount ?> บาท</th>
        </tr>
    </table>

    <form action="#" method="post">
        <div class="mb-3">
            <label>ส่วนลด</label>
            <input type="number" name="discount" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label>วิธีชำระเงิน</label>
            <select name="payment_method" class="form-select">
                <option value="cash">เงินสด</option>
                <option value="transfer">โอนเงิน</option>
                <option value="card">บัตร</option>
            </select>
        </div>

        <button class="btn btn-success">บันทึกการชำระเงิน</button>
        <button type="button" class="btn btn-secondary">พิมพ์ใบเสร็จ</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>