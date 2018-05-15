<?php
?>

<div class="container" style="border-color : black">
    <h3> Add Assignments: <button id="addgroupbtn" type="button" class="btn">Add Group</button></h3>
    <p class="text-muted fornt-weight-light">Note: the sum of all the weights should equal to 100.</p>

    <ul id="groupsList" class="list-group"></ul>
    <div class="container d-flex mt-2 mb-2">
        <?php $backLink = base_url() . 'Class_list_controller/classListView'; ?>
        <button type="button" class="btn mr-auto"
                onclick="location.href = '<?= $backLink ?>'">
            Back
        </button>
        <button id="addClass" type="button" class="btn">Add Class</button>
    </div>
</div>
