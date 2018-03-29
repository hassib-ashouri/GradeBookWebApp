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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


         <!--<script type="text/javascript" src="--><?//= javascripts_url() ?><!--main.js" defer></script>-->
        <?php foreach ($javascripts as $jsUrl): ?>
            <script type="text/javascript" src="<?= javascripts_url() . $jsUrl ?>" defer></script>
        <?php endforeach ?>

        <!--    <link rel="stylesheet" type="text/css" href="--><?//= stylesheets_url() ?><!--styles.css">-->
        <?php foreach ($stylesheets as $cssUrl): ?>
            <link rel="stylesheet" type="text/css" href="<?= stylesheets_url() . $cssUrl ?>">
        <?php endforeach ?>
    </head>




    <div class="container-fluid bg-primary" style="">

        <div class="h1">IDGF</div>
        <div class="h5">the user name could go here</div>

    </div>


<meta charset="UTF-8">