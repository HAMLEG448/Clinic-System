<?php
$pageTitle = "คิวตรวจ";
include "includes/header.php";
include "includes/sidebar.php";

require_once "../backend/controllers/VisitController.php";

$visitController = new VisitController();
$data = $visitController->queue();

$waitingVisits = $data["waiting"];
$examiningVisits = $data["examining"];
?>

<h2 class="page-title">คิวตรวจ</h2>

<div class="card card-box p-4 mb-4">
    <h4 class="mb-3">ผู้ป่วยรอตรวจ</h4>

    <?php if (empty($waitingVisits)): ?>
        <div class="alert alert-secondary mb-0">
            ไม่มีผู้ป่วยรอตรวจ
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>อายุ</th>
                        <th>อาการหลัก</th>
                        <th>แพ้ยา</th>
                        <th>เวลารับเข้า</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($waitingVisits as $index => $visit): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>

                            <td>
                                <?= htmlspecialchars($visit["first_name"] . " " . $visit["last_name"], ENT_QUOTES, "UTF-8") ?>
                            </td>

                            <td>
                                <?= $visit["age"] !== null ? $visit["age"] . " ปี" : "-" ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($visit["chief_complaint"], ENT_QUOTES, "UTF-8") ?>
                            </td>

                            <td>
                                <?= !empty($visit["allergy"])
                                    ? htmlspecialchars($visit["allergy"], ENT_QUOTES, "UTF-8")
                                    : "-" ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($visit["visit_date"], ENT_QUOTES, "UTF-8") ?>
                            </td>

                            <td>
                                <form action="../backend/routes/web.php?action=start_queue" method="post" class="d-inline">
                                    <input type="hidden" name="visit_id" value="<?= $visit["visit_id"] ?>">
                                    <button class="btn btn-primary btn-sm">
                                        เริ่มตรวจ
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="card card-box p-4">
    <h4 class="mb-3">ผู้ป่วยกำลังตรวจ</h4>

    <?php if (empty($examiningVisits)): ?>
        <div class="alert alert-secondary mb-0">
            ไม่มีผู้ป่วยที่กำลังตรวจ
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>อายุ</th>
                        <th>อาการหลัก</th>
                        <th>แพ้ยา</th>
                        <th>เวลารับเข้า</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($examiningVisits as $index => $visit): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>

                            <td>
                                <?= htmlspecialchars($visit["first_name"] . " " . $visit["last_name"], ENT_QUOTES, "UTF-8") ?>
                            </td>

                            <td>
                                <?= $visit["age"] !== null ? $visit["age"] . " ปี" : "-" ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($visit["chief_complaint"], ENT_QUOTES, "UTF-8") ?>
                            </td>

                            <td>
                                <?= !empty($visit["allergy"])
                                    ? htmlspecialchars($visit["allergy"], ENT_QUOTES, "UTF-8")
                                    : "-" ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($visit["visit_date"], ENT_QUOTES, "UTF-8") ?>
                            </td>

                            <td>
                                <a
                                    href="medical-record.php?visit_id=<?= $visit["visit_id"] ?>"
                                    class="btn btn-success btn-sm"
                                >
                                    บันทึกการตรวจ
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
