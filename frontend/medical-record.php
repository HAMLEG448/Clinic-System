<?php
$pageTitle = "บันทึกการตรวจรักษา";
include "includes/header.php";
include "includes/sidebar.php";

require_once "../backend/controllers/MedicalRecordController.php";

$visit_id = (int) ($_GET["visit_id"] ?? $_POST["visit_id"] ?? 0);

if (!$visit_id) {
    echo '<div class="alert alert-danger m-4">ไม่พบข้อมูล visit กรุณาเข้าผ่านหน้ารับคนไข้</div>';
    include "includes/footer.php";
    exit;
}

$controller = new MedicalRecordController();
$data       = $controller->show($visit_id);
$visit      = $data["visit"];
$medicines  = $data["medicines"];
$record     = $data["record"]; // null ถ้ายังไม่เคยบันทึก

if (!$visit) {
    echo '<div class="alert alert-danger m-4">ไม่พบข้อมูลการเข้ารับบริการ</div>';
    include "includes/footer.php";
    exit;
}
?>

<h2 class="page-title">บันทึกการตรวจรักษา</h2>

<!-- ข้อมูลผู้ป่วย (แสดงอย่างเดียว) -->
<div class="card card-box p-4 mb-4">
    <h5 class="mb-3">ข้อมูลผู้ป่วย</h5>
    <div class="row">
        <div class="col-md-4">
            <small class="text-muted">ชื่อ-นามสกุล</small>
            <p class="fw-bold"><?= htmlspecialchars($visit["first_name"] . " " . $visit["last_name"]) ?></p>
        </div>
        <div class="col-md-4">
            <small class="text-muted">อาการหลักที่มา</small>
            <p><?= htmlspecialchars($visit["chief_complaint"]) ?></p>
        </div>
        <div class="col-md-4">
            <small class="text-muted">ความดัน / อุณหภูมิ / น้ำหนัก</small>
            <p>
                <?= htmlspecialchars($visit["blood_pressure"] ?: "-") ?> /
                <?= $visit["temperature"] ? $visit["temperature"] . " °C" : "-" ?> /
                <?= $visit["weight"] ? $visit["weight"] . " kg" : "-" ?>
            </p>
        </div>
    </div>
</div>

<?php if ($record): ?>
    <!-- บันทึกไว้แล้ว แสดง read-only -->
    <div class="alert alert-success">บันทึกการตรวจนี้ถูกบันทึกแล้ว</div>
    <div class="card card-box p-4">
        <h5 class="mb-3">ผลการตรวจ</h5>
        <p><strong>วินิจฉัย:</strong> <?= htmlspecialchars($record["diagnosis"]) ?></p>
        <p><strong>ผลตรวจร่างกาย:</strong> <?= nl2br(htmlspecialchars($record["physical_exam"])) ?></p>
        <p><strong>แนวทางรักษา:</strong> <?= nl2br(htmlspecialchars($record["treatment_plan"])) ?></p>
        <p><strong>หมายเหตุแพทย์:</strong> <?= nl2br(htmlspecialchars($record["doctor_note"])) ?></p>
        <?php if ($record["follow_up_date"]): ?>
            <p><strong>วันนัดติดตาม:</strong> <?= $record["follow_up_date"] ?></p>
        <?php endif; ?>
        <a href="payment.php?visit_id=<?= $visit_id ?>" class="btn btn-success mt-2">ไปหน้าชำระเงิน →</a>
    </div>

<?php else: ?>
    <!-- ฟอร์มบันทึกใหม่ -->
    <div class="card card-box p-4">
        <form action="../backend/routes/web.php?action=store_medical_record" method="post">
            <input type="hidden" name="visit_id" value="<?= $visit_id ?>">

            <h5 class="mb-3">ข้อมูลการตรวจ</h5>

            <div class="mb-3">
                <label class="form-label">วินิจฉัยโรค / อาการ</label>
                <input type="text" name="diagnosis" class="form-control"
                    placeholder="เช่น ไข้หวัด, แผลถลอก, ปวดกล้ามเนื้อ" required>
            </div>

            <div class="mb-3">
                <label class="form-label">ผลตรวจร่างกายเบื้องต้น</label>
                <textarea name="physical_exam" class="form-control" rows="3"
                    placeholder="เช่น พบแผลถลอก ไม่มีหนอง ไม่มีเลือดออกมาก"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">แนวทางรักษา</label>
                <textarea name="treatment_plan" class="form-control" rows="3"
                    placeholder="เช่น ล้างแผล ใส่ยา ให้ยาลดปวดกลับบ้าน"></textarea>
            </div>

            <hr>

            <h5 class="mb-3">บริการที่ทำ</h5>

            <?php
            $serviceList = [
                "ตรวจทั่วไป"  => 100,
                "ล้างแผล"     => 50,
                "ทำแผล"       => 80,
                "ฉีดยา"       => 70,
                "เย็บแผล"    => 150,
            ];
            foreach ($serviceList as $name => $price): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                        name="services[]" value="<?= $name ?>" id="svc_<?= $name ?>">
                    <label class="form-check-label" for="svc_<?= $name ?>">
                        <?= $name ?> <span class="text-muted">(<?= $price ?> บาท)</span>
                    </label>
                </div>
            <?php endforeach; ?>

            <hr>

            <h5 class="mb-3">รายการยา</h5>

            <div id="medicine-rows">
                <div class="row mb-2 medicine-row">
                    <div class="col-md-4">
                        <select name="medicine_id[]" class="form-select">
                            <option value="">-- เลือกยา --</option>
                            <?php foreach ($medicines as $med): ?>
                                <option value="<?= $med["medicine_id"] ?>" data-stock="<?= $med["stock_qty"] ?>">
                                    <?= htmlspecialchars($med["medicine_name"]) ?>
                                    (คงเหลือ <?= $med["stock_qty"] ?> <?= htmlspecialchars($med["unit"]) ?> | <?= $med["price"] ?> บาท)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input 
                            type="number" 
                            name="quantity[]" 
                            class="form-control medicine-qty"
                            placeholder="จำนวน" 
                            min="1"
                        >
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="dosage[]" class="form-control"
                            placeholder="วิธีใช้ เช่น 1 เม็ด">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="instruction[]" class="form-control"
                            placeholder="เช่น หลังอาหาร / ก่อนนอน">
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-secondary btn-sm mb-3" onclick="addMedicineRow()">
                + เพิ่มรายการยา
            </button>

            <hr>

            <div class="mb-3">
                <label class="form-label">หมายเหตุแพทย์</label>
                <textarea name="doctor_note" class="form-control" rows="2"
                    placeholder="บันทึกเพิ่มเติมสำหรับแพทย์"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">วันนัดติดตาม</label>
                <input type="date" name="follow_up_date" class="form-control">
            </div>

            <button class="btn btn-primary">บันทึกและไปหน้าชำระเงิน →</button>
            <a href="visit-create.php" class="btn btn-secondary ms-2">ย้อนกลับ</a>
        </form>
    </div>
<?php endif; ?>

<script>
function updateQuantityLimit(row) {
    const select = row.querySelector("select[name='medicine_id[]']");
    const qtyInput = row.querySelector("input[name='quantity[]']");
    const selectedOption = select.options[select.selectedIndex];

    const stock = parseInt(selectedOption.dataset.stock || "0");

    if (stock > 0) {
        qtyInput.max = stock;
        qtyInput.placeholder = "สูงสุด " + stock;

        if (parseInt(qtyInput.value || "0") > stock) {
            qtyInput.value = stock;
        }
    } else {
        qtyInput.removeAttribute("max");
        qtyInput.placeholder = "จำนวน";
        qtyInput.value = "";
    }
}

function bindMedicineRow(row) {
    const select = row.querySelector("select[name='medicine_id[]']");
    const qtyInput = row.querySelector("input[name='quantity[]']");

    select.addEventListener("change", function () {
        updateQuantityLimit(row);
    });

    qtyInput.addEventListener("input", function () {
        const max = parseInt(qtyInput.max || "0");
        const value = parseInt(qtyInput.value || "0");

        if (max > 0 && value > max) {
            qtyInput.value = max;
            alert("จำนวนยาที่เลือกห้ามเกินจำนวนคงเหลือในสต็อก");
        }

        if (value < 1 && qtyInput.value !== "") {
            qtyInput.value = 1;
        }
    });
}

function addMedicineRow() {
    const container = document.getElementById("medicine-rows");
    const first = container.querySelector(".medicine-row");
    const clone = first.cloneNode(true);

    clone.querySelectorAll("input").forEach(i => {
        i.value = "";
        i.removeAttribute("max");
    });

    clone.querySelectorAll("select").forEach(s => {
        s.selectedIndex = 0;
    });

    container.appendChild(clone);
    bindMedicineRow(clone);
}

document.querySelectorAll(".medicine-row").forEach(row => {
    bindMedicineRow(row);
});
</script>

<?php include "includes/footer.php"; ?>
