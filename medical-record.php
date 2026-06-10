<?php
$pageTitle = "บันทึกการตรวจรักษา";
include "includes/header.php";
include "includes/sidebar.php";
?>

<h2 class="page-title">บันทึกการตรวจรักษา</h2>

<div class="card card-box p-4">
    <form action="payment.php" method="post">

        <h5 class="mb-3">ข้อมูลการตรวจ</h5>

        <div class="mb-3">
            <label>วินิจฉัยโรค / อาการ</label>
            <input type="text" name="diagnosis" class="form-control" placeholder="เช่น ไข้หวัด, แผลถลอก, ปวดกล้ามเนื้อ">
        </div>

        <div class="mb-3">
            <label>ผลตรวจร่างกายเบื้องต้น</label>
            <textarea name="physical_exam" class="form-control" rows="3" placeholder="เช่น พบแผลถลอก ไม่มีหนอง ไม่มีเลือดออกมาก"></textarea>
        </div>

        <div class="mb-3">
            <label>แนวทางรักษา</label>
            <textarea name="treatment_plan" class="form-control" rows="3" placeholder="เช่น ล้างแผล ใส่ยา ให้ยาลดปวดกลับบ้าน"></textarea>
        </div>

        <hr>

        <h5 class="mb-3">บริการที่ทำ</h5>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="services[]" value="ตรวจทั่วไป">
            <label class="form-check-label">ตรวจทั่วไป</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="services[]" value="ล้างแผล">
            <label class="form-check-label">ล้างแผล</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="services[]" value="ทำแผล">
            <label class="form-check-label">ทำแผล</label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="services[]" value="ฉีดยา">
            <label class="form-check-label">ฉีดยา</label>
        </div>

        <hr>

        <h5 class="mb-3">รายการยา</h5>

        <div class="row mb-2">
            <div class="col-md-4">
                <select name="medicine_id[]" class="form-select">
                    <option value="1">Paracetamol</option>
                    <option value="2">Amoxicillin</option>
                    <option value="3">ยาทาแผล</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="number" name="quantity[]" class="form-control" placeholder="จำนวน">
            </div>

            <div class="col-md-3">
                <input type="text" name="dosage[]" class="form-control" placeholder="วิธีใช้ เช่น 1 เม็ด">
            </div>

            <div class="col-md-3">
                <input type="text" name="instruction[]" class="form-control" placeholder="หลังอาหาร / ก่อนนอน">
            </div>
        </div>

        <div class="mb-3">
            <label>หมายเหตุแพทย์</label>
            <textarea name="doctor_note" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>วันนัดติดตาม</label>
            <input type="date" name="follow_up_date" class="form-control">
        </div>

        <button class="btn btn-primary">บันทึกและไปหน้าชำระเงิน</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>