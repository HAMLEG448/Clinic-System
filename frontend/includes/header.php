<?php 
// วางโค้ดนี้ที่บรรทัดแรกสุดของ includes/header.php
// (ก่อน DOCTYPE หรือก่อน include อื่นใด)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    $pageTitle = $pageTitle ?? "Clinic System";
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">