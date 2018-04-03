<?php
//debugging purposes.
$loggedUser = isset($loggedUser) ? $loggedUser : "user is not set in the data passed to the view.";
?>

<h3>basic info od a class should be added here</h3>
<!--debugging purposes.-->
<p>the logged user is: <?= $loggedUser ?></p>

<!--In this part, the view should collect:-->
<!--classid, professorid, classname, section, classtitle, meetingtimes.-->

<div class="container">
    <form>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label>Class ID:</label>
                <input type="text" id="classid" name="classId" class="form-control" placeholder="eg. 32133" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Professor ID:</label>
                <input type="text" id="professorid" name="professorId" class="form-control" placeholder="eg. 3434" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Class Name:</label>
                <input type="text" id="classname" name="className" class="form-control" placeholder="eg. SE131" required>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label>Section:</label>
                <input type="text" id="section" name="section" class="form-control" placeholder="eg. Sec01" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Class Title:</label>
                <input type="text" id="classtitle" name="classTitle" class="form-control" placeholder="eg. Software Engineering I" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Meeting Times:</label>
                <input type="text" id="meetingtimes" name="meetingTimes" class="form-control" placeholder="eg. SE131" required>
            </div>
        </div>
    </form>
</div>