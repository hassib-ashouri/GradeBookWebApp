<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$detailedGrades = isset($detailedGrades) ? $detailedGrades : "";
$assignments = isset($assignments) ? $assignments : "";
$gradesOverview = isset($gradesOverview) ? $gradesOverview : "";
?>

<div class="container mt-4">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="false">Overview</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="assignments-tab" data-toggle="tab" href="#assignments" role="tab" aria-controls="assignments" aria-selected="false">Assignments</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
          <?= $detailedGrades ?>
      </div>
      <div class="tab-pane fade" id="overview" role="tabpanel" aria-labelledby="overview-tab">
          <?= $gradesOverview ?>
      </div>
      <div class="tab-pane fade" id="assignments" role="tabpanel" aria-labelledby="assignments-tab">
          <?= $assignments ?>
      </div>
    </div>
</div>
<div class="container mt-2 mb-2">
    <?php $backLink = base_url() . 'Class_list_controller/classListView'; ?>
    <button type="button" class="btn"
            onclick="location.href = '<?= $backLink ?>'">
        Back
    </button>
</div>