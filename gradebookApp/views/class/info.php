<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!isset($highGrade)) {
    $highGrade = 0;
}
if (!isset($lowGrade)) {
    $lowGrade = 0;
}
if (!isset($meanGrade)) {
    $meanGrade = 0;
}
if (!isset($medianGrade)) {
    $medianGrade = 0;
}
if (!isset($varGrade)) {
    $varGrade = 0;
}
if (!isset($stdDevGrade)) {
    $stdDevGrade = 0;
}
?>

<div class="row">
    <div class="col">
        Mean:
    </div>
    <div class="col-sm text-right">
        <?= $meanGrade ?>
    </div>
    <div class="col-sm">
        Median:
    </div>
    <div class="col-sm text-right">
        <?= $medianGrade ?>
    </div>
</div>
<div class="row">
    <div class="col-sm">
        High:
    </div>
    <div class="col-sm text-right">
        <?= $highGrade ?>
    </div>
    <div class="col-sm">
        Low:
    </div>
    <div class="col-sm text-right">
        <?= $lowGrade ?>
    </div>
</div>
<div class="row">
    <div class="col-sm">
        StdDev:
    </div>
    <div class="col-sm text-right">
        <?= $stdDevGrade ?>
    </div>
    <div class="col-sm">
    </div>
    <div class="col-sm text-right">
    </div>
</div>