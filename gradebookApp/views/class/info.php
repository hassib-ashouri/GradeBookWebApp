<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$className = (isset($className)) ? $className : '';
$section = (isset($section)) ? $section : '';
$schedule = (isset($schedule)) ? $schedule : '';
?>

<div class="row">
    <div class="col">
        Class Name:
    </div>
    <div class="col-sm text-right">
        <?= $className ?>
    </div>
</div>
<div class="row">
    <div class="col-sm">
        Section:
    </div>
    <div class="col-sm text-right">
        <?= $section ?>
    </div>
</div>
<div class="row">
    <div class="col-sm">
        Schedule:
    </div>
    <div class="col-sm text-right">
        <?= $schedule ?>
    </div>
</div>