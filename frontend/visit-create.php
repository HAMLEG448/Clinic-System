<?php
$pageTitle = "รับคนไข้เข้ารักษา";
include "includes/header.php";
include "includes/sidebar.php";

require_once "../backend/controllers/PatientController.php";

$patientController = new PatientController();
$patients          = $patientController->index();

$selected_patient_id = (int) ($_GET["patient_id"] ?? 0);
?>

<h2 class="page-title">รับคนไข้เข้ารักษา</h2>

<div class="card card-box p-4">
    <form action="../backend/routes/web.php?action=store_visit" method="post">

        <div class="mb-3">
            <label class="form-label">เลือกผู้ป่วย <span class="text-danger">*</span></label>
            <select name="patient_id" class="form-select" required>
                <option value="">-- เลือกผู้ป่วย --</option>
                <?php foreach ($patients as $patient): ?>
                    <option value="<?= $patient["patient_id"] ?>"
                        <?= $patient["patient_id"] == $selected_patient_id ? "selected" : "" ?>>
                        <?= htmlspecialchars($patient["first_name"] . " " . $patient["last_name"]) ?>
                        <?php if ($patient["allergy"]): ?>
                            (⚠️ แพ้: <?= htmlspecialchars($patient["allergy"]) ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">อาการหลักที่มา <span class="text-danger">*</span></label>
            <input type="text" name="chief_complaint" class="form-control"
                placeholder="เช่น ไข้ ไอ ปวดหัว แผลถลอก" required>
        </div>

        <div class="mb-3">
            <label class="form-label">รายละเอียดอาการ / คนไข้เล่าว่าไปทำอะไรมา</label>
            <textarea name="symptom_detail" class="form-control" rows="4"
                placeholder="เช่น ล้มรถเมื่อวาน มีแผลถลอกที่หัวเข่า"></textarea>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">อุณหภูมิ (°C)</label>
                <input type="number" name="temperature" class="form-control"
                    placeholder="36.8" step="0.1" min="35" max="42">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">ความดัน</label>
                <input type="text" name="blood_pressure" class="form-control"
                    placeholder="120/80">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">น้ำหนัก (kg)</label>
                <input type="number" name="weight" class="form-control"
                    placeholder="60.0" step="0.1" min="1">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">สถานะเริ่มต้น</label>
                <select name="status" class="form-select">
                    <option value="waiting">รอตรวจ</option>
                    <option value="examining">กำลังตรวจ</option>
                </select>
            </div>
        </div>

        <button class="btn btn-success">ส่งเข้าห้องตรวจ →</button>
        <a href="patients.php" class="btn btn-secondary ms-2">ย้อนกลับ</a>
    </form>
</div>

<?php include "includes/footer.php"; ?>
