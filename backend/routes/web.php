<?php

require_once __DIR__ . "/../controllers/PatientController.php";
require_once __DIR__ . "/../controllers/VisitController.php";

$action = $_GET["action"] ?? "";

switch ($action) {
    case "store_patient":
        $controller = new PatientController();
        $controller->store();
        break;

    case "store_visit":
        $controller = new VisitController();
        $controller->store();
        break;

    default:
        echo "ไม่พบ route";
        break;
}