<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container mt-4">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="details" aria-selected="true">Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="overview" aria-selected="false">Overview</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="assignments" aria-selected="false">Assignments</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Edit</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="details-tab">
          studets and their detainled grades.
      </div>
      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="overview-tab">
          students and their overall grades
      </div>
      <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="assignments-tab">
          Assignments overview
      </div>
    </div>
</div>