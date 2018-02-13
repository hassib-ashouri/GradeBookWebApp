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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" defer></script>

<!--    <script type="text/javascript" src="--><?//= javascripts_url() ?><!--main.js" defer></script>-->
    <?php foreach ($javascripts as $jsUrl): ?>
        <script type="text/javascript" src="<?= javascripts_url() . $jsUrl ?>" defer></script>
    <?php endforeach ?>

    <!--    <link rel="stylesheet" type="text/css" href="--><?//= stylesheets_url() ?><!--styles.css">-->
    <?php foreach ($stylesheets as $cssUrl): ?>
        <link rel="stylesheet" type="text/css" href="<?= stylesheets_url() . $cssUrl ?>">
    <?php endforeach ?>
</head>
<meta charset="UTF-8">