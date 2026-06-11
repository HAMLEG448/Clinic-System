<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = $_SESSION["patient_errors"] ?? [];
$old = $_SESSION["patient_old"] ?? [];

unset($_SESSION["patient_errors"]);
unset($_SESSION["patient_old"]);

$pageTitle = "เพิ่มผู้ป่วย";
include "includes/header.php";
include "includes/sidebar.php";
?>

<h2 class="page-title">เพิ่มข้อมูลผู้ป่วย</h2>

<div class="card card-box p-4">
    <form action="../backend/routes/web.php?action=store_patient" method="post">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>เลขบัตรประชาชน</label>
                <input
                    type="text"
                    name="citizen_id"
                    maxlength="13"
                    class="form-control <?= isset($errors["citizen_id"]) ? "is-invalid" : "" ?>"
                    value="<?= htmlspecialchars($old["citizen_id"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                    required
                >

                <?php if (isset($errors["citizen_id"])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors["citizen_id"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-3 mb-3">
                <label>ชื่อ</label>
                <input
                    type="text"
                    name="first_name"
                    class="form-control <?= isset($errors["first_name"]) || isset($errors["full_name"]) ? "is-invalid" : "" ?>"
                    value="<?= htmlspecialchars($old["first_name"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                    required
                >

                <?php if (isset($errors["first_name"])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors["first_name"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                <?php elseif (isset($errors["full_name"])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors["full_name"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-3 mb-3">
                <label>นามสกุล</label>
                <input
                    type="text"
                    name="last_name"
                    class="form-control <?= isset($errors["last_name"]) || isset($errors["full_name"]) ? "is-invalid" : "" ?>"
                    value="<?= htmlspecialchars($old["last_name"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                    required
                >

                <?php if (isset($errors["last_name"])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors["last_name"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                <?php elseif (isset($errors["full_name"])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors["full_name"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-3 mb-3">
                <label>เพศ</label>
                <select name="gender" class="form-select">
                    <option value="">เลือกเพศ</option>
                    <option value="male" <?= ($old["gender"] ?? "") === "male" ? "selected" : "" ?>>ชาย</option>
                    <option value="female" <?= ($old["gender"] ?? "") === "female" ? "selected" : "" ?>>หญิง</option>
                    <option value="other" <?= ($old["gender"] ?? "") === "other" ? "selected" : "" ?>>อื่น ๆ</option>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label>วันเกิด</label>
                <input
                    type="date"
                    name="birth_date"
                    class="form-control"
                    value="<?= htmlspecialchars($old["birth_date"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                >
            </div>

            <div class="col-md-6 mb-3">
                <label>เบอร์โทร</label>
                <input
                    type="text"
                    name="phone"
                    maxlength="20"
                    class="form-control <?= isset($errors["phone"]) ? "is-invalid" : "" ?>"
                    value="<?= htmlspecialchars($old["phone"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                    required
                >

                <?php if (isset($errors["phone"])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors["phone"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-12 mb-3">
                <label>ที่อยู่</label>
                <textarea name="address" class="form-control"><?= htmlspecialchars($old["address"] ?? "", ENT_QUOTES, "UTF-8") ?></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>ประวัติแพ้ยา</label>
                <textarea name="allergy" class="form-control"><?= htmlspecialchars($old["allergy"] ?? "", ENT_QUOTES, "UTF-8") ?></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>โรคประจำตัว</label>
                <textarea name="underlying_disease" class="form-control"><?= htmlspecialchars($old["underlying_disease"] ?? "", ENT_QUOTES, "UTF-8") ?></textarea>
            </div>
        </div>

        <button class="btn btn-primary">บันทึกข้อมูลผู้ป่วย</button>
        <a href="patients.php" class="btn btn-secondary">ย้อนกลับ</a>
    </form>
</div>

<?php include "includes/footer.php"; ?>