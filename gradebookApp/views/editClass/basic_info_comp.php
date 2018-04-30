<?php
//debugging purposes.
$loggedUser = isset($loggedUser) ? $loggedUser : "user is not set in the data passed to the view.";
/**
 * @var \Objects\ClassObj $classObj
 */
$classObj = isset($classObj) ? $classObj : "info did not reach the view";
?>
<!--debugging purposes.-->
<p>the logged user is(for debugging): <?= $loggedUser ?></p>

<!--In this part, the view should collect:-->
<!--classid, professorid, classname, section, classtitle, meetingtimes.-->

<h3>Basic Class Info:</h3>
<form class="needs-validation" novalidate>
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label for="validationCustom01">Class ID:</label>
            <input type="text" id="classid" name="classId" class="form-control" placeholder="eg. 32133" value="<?= $classObj->class_id?>" disabled required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Professor ID:</label>
            <input type="text" id="professorid" name="professorId" class="form-control" placeholder="eg. 3434"  value="<?= $loggedUser?>" disabled required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Class Name:</label>
            <input type="text" id="classname" name="className" class="form-control" placeholder="eg. SE131" value="<?= $classObj->class_name?>" required>
            <div class="invalid-feedback">
                Please choose a username.
            </div>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label>Section:</label>
            <input type="text" id="section" name="section" class="form-control" placeholder="eg. Sec01" value="<?= $classObj->section?>" required>
            <div class="invalid-feedback">
                Please choose a username.
            </div>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Class Title:</label>
            <input type="text" id="classtitle" name="classTitle" class="form-control" placeholder="eg. Software Engineering I" value="<?= $classObj->class_title?>" required>
            <div class="invalid-feedback">
                Please choose a username.
            </div>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Meeting Times:</label>
            <input type="text" id="meetingtimes" name="meetingTimes" class="form-control" placeholder="eg. SE131" value="<?= $classObj->meeting_times?>" required>
            <div class="invalid-feedback">
                Please choose a username.
            </div>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>
    </div>

