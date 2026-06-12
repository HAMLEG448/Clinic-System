<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$visit_error = $_SESSION["visit_error"] ?? "";
unset($_SESSION["visit_error"]);

$pageTitle = "รับคนไข้เข้ารักษา";
include "includes/header.php";
include "includes/sidebar.php";

require_once "../backend/controllers/PatientController.php";

$patientController = new PatientController();
$patients = $patientController->availableForVisit();

$selected_patient_id = (int) ($_GET["patient_id"] ?? 0);
?>

<h2 class="page-title">รับคนไข้เข้ารักษา</h2>

<?php if ($visit_error): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($visit_error, ENT_QUOTES, "UTF-8") ?>
    </div>
<?php endif; ?>

<?php if (empty($patients)): ?>
    <div class="alert alert-warning">
        ตอนนี้ไม่มีผู้ป่วยที่สามารถรับเข้ารักษาได้ เพราะผู้ป่วยทั้งหมดกำลังรอตรวจหรือกำลังตรวจอยู่
    </div>
<?php endif; ?>

<div class="card card-box p-4">
    <form action="../backend/routes/web.php?action=store_visit" method="post">

        <div class="mb-3">
            <label class="form-label">
                เลือกผู้ป่วย <span class="text-danger">*</span>
            </label>

            <select name="patient_id" id="patient_id" class="form-select" required>
                <option value="" data-birth-date="">-- เลือกผู้ป่วย --</option>

                <?php foreach ($patients as $patient): ?>
                    <option
                        value="<?= $patient["patient_id"] ?>"
                        data-birth-date="<?= htmlspecialchars($patient["birth_date"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        <?= $patient["patient_id"] == $selected_patient_id ? "selected" : "" ?>
                    >
                        <?= htmlspecialchars($patient["first_name"] . " " . $patient["last_name"], ENT_QUOTES, "UTF-8") ?>

                        <?php if (!empty($patient["allergy"])): ?>
                            (⚠️ แพ้: <?= htmlspecialchars($patient["allergy"], ENT_QUOTES, "UTF-8") ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">
                อาการหลักที่มา <span class="text-danger">*</span>
            </label>

            <input
                type="text"
                name="chief_complaint"
                class="form-control"
                placeholder="เช่น ไข้ ไอ ปวดหัว แผลถลอก"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">รายละเอียดอาการ / คนไข้เล่าว่าไปทำอะไรมา</label>

            <textarea
                name="symptom_detail"
                class="form-control"
                rows="4"
                placeholder="เช่น ล้มรถเมื่อวาน มีแผลถลอกที่หัวเข่า"
            ></textarea>
        </div>

        <div class="row">
            <div class="col-md-2 mb-3">
                <label class="form-label">อายุผู้ป่วย</label>

                <input
                    type="text"
                    id="patient_age"
                    class="form-control"
                    placeholder="เลือกผู้ป่วย"
                    readonly
                >
            </div>

            <div class="col-md-2 mb-3">
                <label class="form-label">อุณหภูมิ (°C)</label>

                <input
                    type="number"
                    name="temperature"
                    class="form-control"
                    placeholder="36.8"
                    step="0.1"
                    min="35"
                    max="42"
                >
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">ความดัน</label>

                <input
                    type="text"
                    name="blood_pressure"
                    class="form-control"
                    placeholder="120/80"
                >
            </div>

            <div class="col-md-2 mb-3">
                <label class="form-label">น้ำหนัก (kg)</label>

                <input
                    type="number"
                    name="weight"
                    class="form-control"
                    placeholder="60.0"
                    step="0.1"
                    min="1"
                >
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">สถานะเริ่มต้น</label>

                <select name="status" class="form-select">
                    <option value="waiting">รอตรวจ</option>
                    <option value="examining">กำลังตรวจ</option>
                </select>
            </div>
        </div>

        <button class="btn btn-success" <?= empty($patients) ? "disabled" : "" ?>>
            ส่งเข้าห้องตรวจ →
        </button>

        <a href="patients.php" class="btn btn-secondary ms-2">ย้อนกลับ</a>
    </form>
</div>

<script>
function calculateAge(birthDate) {
    if (!birthDate) {
        return "";
    }

    const birth = new Date(birthDate);
    const today = new Date();

    if (isNaN(birth.getTime())) {
        return "";
    }

    let age = today.getFullYear() - birth.getFullYear();

    const monthDiff = today.getMonth() - birth.getMonth();
    const dayDiff = today.getDate() - birth.getDate();

    if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
        age--;
    }

    return age;
}

function updatePatientAge() {
    const patientSelect = document.getElementById("patient_id");
    const ageInput = document.getElementById("patient_age");

    if (!patientSelect || !ageInput) {
        return;
    }

    const selectedOption = patientSelect.options[patientSelect.selectedIndex];

    if (!selectedOption) {
        ageInput.value = "";
        ageInput.placeholder = "เลือกผู้ป่วย";
        return;
    }

    const birthDate = selectedOption.dataset.birthDate || "";
    const age = calculateAge(birthDate);

    if (age === "") {
        ageInput.value = "";
        ageInput.placeholder = "ไม่พบวันเกิด";
    } else {
        ageInput.value = age + " ปี";
    }
}

const patientSelect = document.getElementById("patient_id");

if (patientSelect) {
    patientSelect.addEventListener("change", updatePatientAge);
    updatePatientAge();
}
</script>

<?php include "includes/footer.php"; ?>