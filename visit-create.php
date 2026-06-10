<?php
$pageTitle = "รับคนไข้เข้ารักษา";
include "includes/header.php";
include "includes/sidebar.php";
?>

<h2 class="page-title">รับคนไข้เข้ารักษา</h2>

<div class="card card-box p-4">
    <form action="medical-record.php" method="post">

        <div class="mb-3">
            <label>เลือกผู้ป่วย</label>
            <select name="patient_id" class="form-select">
                <option value="1">นายสมชาย ใจดี</option>
                <option value="2">นางสาวมาลี สดใส</option>
            </select>
        </div>

        <div class="mb-3">
            <label>อาการหลักที่มา</label>
            <input type="text" name="chief_complaint" class="form-control" placeholder="เช่น ไข้ ไอ ปวดหัว แผลถลอก">
        </div>

        <div class="mb-3">
            <label>รายละเอียดอาการ / คนไข้เล่าว่าไปทำอะไรมา</label>
            <textarea name="symptom_detail" class="form-control" rows="4" placeholder="เช่น ล้มรถเมื่อวาน มีแผลถลอกที่หัวเข่า"></textarea>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label>อุณหภูมิ</label>
                <input type="text" name="temperature" class="form-control" placeholder="36.8">
            </div>

            <div class="col-md-3 mb-3">
                <label>ความดัน</label>
                <input type="text" name="blood_pressure" class="form-control" placeholder="120/80">
            </div>

            <div class="col-md-3 mb-3">
                <label>น้ำหนัก</label>
                <input type="text" name="weight" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>สถานะ</label>
                <select name="status" class="form-select">
                    <option value="waiting">รอตรวจ</option>
                    <option value="examining">กำลังตรวจ</option>
                    <option value="completed">เสร็จสิ้น</option>
                </select>
            </div>
        </div>

        <button class="btn btn-success">ส่งเข้าห้องตรวจ</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>