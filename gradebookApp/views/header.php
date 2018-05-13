<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!isset($title)) {
    $title = "";
}
if (!isset($javascripts)) {
    $javascripts = array();
}
if (!isset($stylesheets)) {
    $stylesheets = array();
}
?>
<!DOCTYPE html>
<head>
    <link rel="icon" href="<?= images_url() ?>favicon.png" type="image/png"/>
    <title><?= $title ?></title>

    <!-- Hassib- added the bootstrap libraries -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            crossorigin="anonymous"></script>

    <!--<script type="text/javascript" src="-->
    <? //= javascripts_url() ?><!--main.js" defer></script>-->
    <?php foreach ($javascripts as $jsUrl): ?>
        <script type="text/javascript" src="<?= javascripts_url() . $jsUrl ?>" defer></script>
    <?php endforeach ?>

    <!--    <link rel="stylesheet" type="text/css" href="-->
    <? //= stylesheets_url() ?><!--styles.css">-->
    <?php foreach ($stylesheets as $cssUrl): ?>
        <link rel="stylesheet" type="text/css" href="<?= stylesheets_url() . $cssUrl ?>">
    <?php endforeach ?>
    <script>
        var IDGF = {};
        IDGF.baseURL = '<?= base_url(); ?>';
    </script>
</head>
<meta charset="UTF-8">

<div class="container-fluid bg-primary d-flex" style="">
    <div class="mr-auto">
        <div class="h1">IDGF GradeBook</div>
        <?php if (isset($name)): ?>
            <div class="h5"><?= "Welcome, $name!" ?></div>
        <?php endif; ?>
    </div>
    <div>
        <?php if (isset($name)): ?>
            <!-- only creates if there's a named user -->
            <form action="<?= base_url() . 'Login_controller/logout' ?>">
                <button type="submit" class="btn btn-light mt-2">
                    Logout
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>