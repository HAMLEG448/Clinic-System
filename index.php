<?php
$pageTitle = "Dashboard";
include "includes/header.php";
include "includes/sidebar.php";
?>

<h2 class="page-title">Dashboard</h2>

<div class="row">
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>ผู้ป่วยทั้งหมด</h6>
            <h2>120</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>เข้ารักษาวันนี้</h6>
            <h2>8</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>รอชำระเงิน</h6>
            <h2>3</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>ยาคงเหลือน้อย</h6>
            <h2>5</h2>
        </div>
    </div>
</div>

<div class="card card-box mt-4 p-4">
    <h5>Flow การทำงานของคลินิก</h5>

    <div class="mt-3">
        <span class="badge bg-primary">1. ลงทะเบียนผู้ป่วย</span>
        →
        <span class="badge bg-info">2. รับเข้ารักษา</span>
        →
        <span class="badge bg-warning text-dark">3. หมอตรวจ</span>
        →
        <span class="badge bg-success">4. ให้ยา/ทำแผล</span>
        →
        <span class="badge bg-dark">5. ชำระเงิน</span>
    </div>
</div>

<?php include "includes/footer.php"; ?>