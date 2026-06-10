<?php
$pageTitle = "เพิ่มผู้ป่วย";
include "includes/header.php";
include "includes/sidebar.php";
?>

<h2 class="page-title">เพิ่มข้อมูลผู้ป่วย</h2>

<div class="card card-box p-4">
    <form action="#" method="post">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>เลขบัตรประชาชน</label>
                <input type="text" name="citizen_id" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>ชื่อ</label>
                <input type="text" name="first_name" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>นามสกุล</label>
                <input type="text" name="last_name" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>เพศ</label>
                <select name="gender" class="form-select">
                    <option value="">เลือกเพศ</option>
                    <option value="male">ชาย</option>
                    <option value="female">หญิง</option>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label>วันเกิด</label>
                <input type="date" name="birth_date" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>เบอร์โทร</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label>ที่อยู่</label>
                <textarea name="address" class="form-control"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>ประวัติแพ้ยา</label>
                <textarea name="allergy" class="form-control"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>โรคประจำตัว</label>
                <textarea name="underlying_disease" class="form-control"></textarea>
            </div>
        </div>

        <button class="btn btn-primary">บันทึกข้อมูลผู้ป่วย</button>
        <a href="patients.php" class="btn btn-secondary">ย้อนกลับ</a>
    </form>
</div>

<?php include "includes/footer.php"; ?>