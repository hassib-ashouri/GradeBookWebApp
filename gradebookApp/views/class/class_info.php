<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!isset($statsComponent)) {
    $statsComponent = "";
}
?>


<div class="container">
    <div class="row">
        <div class="col-lg">
            <div class="container">
                Class Info
            </div>
        </div>
        <div class="col-lg">
            <div class="container-fluid">
                Stats
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg">
            <div class="container">
<!--                //Code goes here-->
            </div>
        </div>
        <div class="col-lg">
            <div class="container">
                <?= $statsComponent ?>
            </div>
        </div>
    </div>
</div>
