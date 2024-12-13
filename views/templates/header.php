<?php require_once __DIR__ . '/../../config/init.php'; ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&family=Open+Sans:wght@300..800&display=swap" rel="stylesheet">
    <title><?php echo $title ?? 'RD-System'; ?></title>
</head>

<body>

<?php require_once __DIR__ . '/navbar.php'; ?>
<?php require_once __DIR__ . '/sidebar.php'; ?>
<?php require_once __DIR__ . '/../auth/modals/user_modal.php'; ?>


