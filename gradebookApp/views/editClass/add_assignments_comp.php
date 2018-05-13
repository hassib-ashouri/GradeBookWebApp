<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @var \Objects\AssignmentList $assignments
 */
$assignments = (isset($assignments)) ? $assignments : array();
?>

<h3>Add Assignments:
    <button id="addgroupbtn" type="button" class="btn">Add Group</button>
</h3>
<p class="text-muted fornt-weight-light">Note: the sum of all the weights should equal to 100.</p>

<ul id="groupsList" class="list-group"></ul>

<div class="container d-flex mt-2 mb-2">
    <button type="button" class="btn mr-auto">
        <a href="<?= base_url() . 'Class_list_controller/classListView' ?>">
            Back
        </a>
    </button>
    <button id="Submit" type="button" class="btn">Submit Changes</button>
</div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        var groups = <?= json_encode($assignments) ?>;
        addGroups(groups);
    });
</script>