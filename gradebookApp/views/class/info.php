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
</div>
<div class="row">
    <div class="col-sm">
        Schedule:
    </div>
    <div class="col-sm text-right">
        <?= $schedule ?>
    </div>
</div>

