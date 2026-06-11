<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = "ชำระเงิน";
include "includes/header.php";
include "includes/sidebar.php";

$visit_id   = (int) ($_GET["visit_id"]   ?? 0);
$payment_id = (int) ($_GET["payment_id"] ?? 0);

if (!$visit_id) {
    echo '<div class="alert alert-danger m-4">ไม่พบข้อมูล visit กรุณาเข้าผ่านหน้าบันทึกการตรวจ</div>';
    include "includes/footer.php";
    exit;
}

require_once "../backend/models/Visit.php";
require_once "../backend/models/MedicalRecord.php";
require_once "../backend/models/Medicine.php";
require_once "../backend/models/Payment.php";

$visitModel   = new Visit();
$recordModel  = new MedicalRecord();
$medModel     = new Medicine();
$paymentModel = new Payment();

$visit  = $visitModel->findById($visit_id);
$record = $recordModel->findByVisitId($visit_id);

// ถ้าชำระแล้วดึงจาก DB
$existingPayment = $payment_id
    ? $paymentModel->findById($payment_id)
    : $paymentModel->findByVisitId($visit_id);

// ── ดึงข้อมูลจาก session ที่ MedicalRecordController บันทึกไว้ ──
$sessionData  = $_SESSION["payment_data"][$visit_id] ?? [];
$services     = $sessionData["services"]    ?? [];
$medicineIds  = $sessionData["medicine_id"] ?? [];
$quantities   = $sessionData["quantity"]    ?? [];

// ราคาบริการ (ต้องตรงกับใน medical-record.php)
$servicePrices = [
    "ตรวจทั่วไป" => 100,
    "ล้างแผล"    => 50,
    "ทำแผล"      => 80,
    "ฉีดยา"      => 70,
    "เย็บแผล"   => 150,
];

// คำนวณค่าบริการ
$serviceTotal = 0;
$serviceList  = [];
foreach ($services as $svc) {
    if (!$svc) continue;
    $price         = $servicePrices[$svc] ?? 0;
    $serviceTotal += $price;
    $serviceList[] = ["name" => $svc, "price" => $price];
}

// คำนวณค่ายา
$medicineTotal   = 0;
$medicineDetails = [];
foreach ($medicineIds as $idx => $mid) {
    $mid = (int) $mid;
    $qty = (int) ($quantities[$idx] ?? 1);
    if (!$mid || !$qty) continue;
    $med = $medModel->findById($mid);
    if ($med) {
        $subtotal          = $med["price"] * $qty;
        $medicineTotal    += $subtotal;
        $medicineDetails[] = [
            "id"       => $mid,
            "name"     => $med["medicine_name"],
            "unit"     => $med["unit"],
            "price"    => $med["price"],
            "qty"      => $qty,
            "subtotal" => $subtotal,
        ];
    }
}

// ถ้าชำระแล้ว ใช้ตัวเลขจาก DB แทน
if ($existingPayment) {
    $serviceTotal  = $existingPayment["service_total"];
    $medicineTotal = $existingPayment["medicine_total"];
    $totalAmount   = $existingPayment["total_amount"];
    $discount      = $existingPayment["discount"];
    $netAmount     = $existingPayment["net_amount"];
} else {
    $totalAmount = $serviceTotal + $medicineTotal;
    $discount    = 0;
    $netAmount   = $totalAmount;
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-title">ชำระเงิน</h2>
    <?php if ($existingPayment): ?>
        <span class="badge bg-success fs-6">✓ ชำระแล้ว</span>
    <?php endif; ?>
</div>

<?php if (!$visit): ?>
    <div class="alert alert-danger">ไม่พบข้อมูลการเข้ารับบริการ</div>
<?php else: ?>

<div class="card card-box p-4 mb-4" id="receipt-area">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h5 class="mb-1">ใบเสร็จรับเงิน</h5>
            <small class="text-muted">
                <?= htmlspecialchars($visit["first_name"] . " " . $visit["last_name"]) ?>
                &nbsp;|&nbsp; วันที่ <?= date("d/m/Y H:i", strtotime($visit["visit_date"])) ?>
                &nbsp;|&nbsp; Visit #<?= $visit_id ?>
            </small>
        </div>
        <?php if ($existingPayment && $existingPayment["paid_at"]): ?>
            <small class="text-success">ชำระเมื่อ <?= date("d/m/Y H:i", strtotime($existingPayment["paid_at"])) ?></small>
        <?php endif; ?>
    </div>

    <!-- ค่าบริการ -->
    <?php if (!empty($serviceList)): ?>
    <h6 class="mt-2">บริการ</h6>
    <table class="table table-sm mb-3">
        <?php foreach ($serviceList as $svc): ?>
            <tr>
                <td><?= htmlspecialchars($svc["name"]) ?></td>
                <td class="text-end"><?= number_format($svc["price"], 2) ?> บาท</td>
            </tr>
        <?php endforeach; ?>
        <tr class="table-light">
            <td><strong>รวมค่าบริการ</strong></td>
            <td class="text-end"><strong><?= number_format($serviceTotal, 2) ?> บาท</strong></td>
        </tr>
    </table>
    <?php endif; ?>

    <!-- ค่ายา -->
    <?php if (!empty($medicineDetails)): ?>
    <h6>รายการยา</h6>
    <table class="table table-sm mb-3">
        <thead class="table-light">
            <tr>
                <th>ยา</th>
                <th class="text-center">จำนวน</th>
                <th class="text-end">ราคา/หน่วย</th>
                <th class="text-end">รวม</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($medicineDetails as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m["name"]) ?> (<?= htmlspecialchars($m["unit"]) ?>)</td>
                <td class="text-center"><?= $m["qty"] ?></td>
                <td class="text-end"><?= number_format($m["price"], 2) ?></td>
                <td class="text-end"><?= number_format($m["subtotal"], 2) ?> บาท</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr class="table-light">
            <td colspan="3"><strong>รวมค่ายา</strong></td>
            <td class="text-end"><strong><?= number_format($medicineTotal, 2) ?> บาท</strong></td>
        </tr>
        </tfoot>
    </table>
    <?php endif; ?>

    <?php if (empty($serviceList) && empty($medicineDetails) && !$existingPayment): ?>
        <div class="alert alert-warning">ไม่พบข้อมูลบริการหรือยา — กรุณากลับไปบันทึกการตรวจใหม่</div>
    <?php endif; ?>

    <!-- สรุปยอด -->
    <table class="table table-bordered mt-2">
        <tr>
            <td>รวมค่าบริการ + ค่ายา</td>
            <td class="text-end"><?= number_format($totalAmount, 2) ?> บาท</td>
        </tr>
        <tr>
            <td>ส่วนลด</td>
            <td class="text-end text-danger">- <?= number_format($existingPayment ? $discount : 0, 2) ?> บาท</td>
        </tr>
        <tr class="table-success">
            <th>ยอดสุทธิ</th>
            <th class="text-end fs-5"><?= number_format($existingPayment ? $netAmount : $totalAmount, 2) ?> บาท</th>
        </tr>
    </table>

    <?php if ($existingPayment): ?>
        <div class="alert alert-success mt-3">
            ✓ ชำระโดย: <strong>
            <?= match($existingPayment["payment_method"]) {
                "cash"     => "เงินสด",
                "transfer" => "โอนเงิน",
                "card"     => "บัตร",
                default    => $existingPayment["payment_method"]
            } ?>
            </strong>
        </div>
        <button onclick="window.print()" class="btn btn-outline-secondary">🖨️ พิมพ์ใบเสร็จ</button>
        <a href="patients.php" class="btn btn-primary ms-2">กลับหน้าผู้ป่วย</a>

    <?php else: ?>
        <form action="../backend/routes/web.php?action=store_payment" method="post" class="mt-3">
            <input type="hidden" name="visit_id"       value="<?= $visit_id ?>">
            <input type="hidden" name="service_total"  value="<?= $serviceTotal ?>">
            <input type="hidden" name="medicine_total" value="<?= $medicineTotal ?>">

            <?php foreach ($medicineDetails as $m): ?>
                <input type="hidden" name="medicine_id[]" value="<?= $m["id"] ?>">
                <input type="hidden" name="quantity[]"    value="<?= $m["qty"] ?>">
            <?php endforeach; ?>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">ส่วนลด (บาท)</label>
                    <input type="number" name="discount" id="discount"
                        class="form-control" value="0" min="0"
                        oninput="calcNet()">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">วิธีชำระเงิน</label>
                    <select name="payment_method" class="form-select">
                        <option value="cash">💵 เงินสด</option>
                        <option value="transfer">📲 โอนเงิน</option>
                        <option value="card">💳 บัตร</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">ยอดสุทธิหลังส่วนลด</label>
                    <div class="form-control bg-light fw-bold text-success" id="net-display">
                        <?= number_format($totalAmount, 2) ?> บาท
                    </div>
                </div>
            </div>

            <button class="btn btn-success btn-lg">✓ บันทึกการชำระเงิน</button>
            <a href="medical-record.php?visit_id=<?= $visit_id ?>" class="btn btn-secondary ms-2">← ย้อนกลับ</a>
        </form>
    <?php endif; ?>
</div>

<?php endif; ?>

<script>
const total = <?= $totalAmount ?>;
function calcNet() {
    const discount = parseFloat(document.getElementById("discount").value) || 0;
    const net = Math.max(0, total - discount);
    document.getElementById("net-display").textContent = net.toFixed(2) + " บาท";
}
</script>

<style>
@media print {
    .sidebar, nav, .btn, form, .page-title { display: none !important; }
    .card-box { box-shadow: none !important; border: 1px solid #ccc !important; }
    #receipt-area { display: block !important; }
}
</style>

<?php include "includes/footer.php"; ?>
