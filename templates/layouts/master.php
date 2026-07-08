<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? config('name');
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

<link
rel="stylesheet"
href="<?= asset('assets/css/bootstrap.min.css') ?>">

<link
rel="stylesheet"
href="<?= asset('assets/icons/bootstrap-icons/font/bootstrap-icons.css') ?>">

<link
rel="stylesheet"
href="<?= asset('assets/css/variables.css') ?>">

<link
rel="stylesheet"
href="<?= asset('assets/css/app.css') ?>">

<link
rel="stylesheet"
href="<?= asset('assets/css/home.css'); ?>">

</head>

<body>

<?php require $contentFile; ?>

<script
src="<?= asset('assets/js/vendor/bootstrap.bundle.min.js') ?>"></script>

<script
src="<?= asset('assets/js/app.js') ?>"></script>

</body>

</html>
