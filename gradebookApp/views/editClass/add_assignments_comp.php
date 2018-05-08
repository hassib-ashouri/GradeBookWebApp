<?php
/**
 * @var \Objects\AssignmentList
 */
$Assignments;
?>


    <h3>Add Assignments:  <button id="addgroupbtn" type="button" class="btn">Add Group</button></h3>
    <p class="text-muted fornt-weight-light">Note: the sum of all the weights should equal to 100.</p>



    <ul id="groupsList" class="list-group">

    </ul>

    <div class="container mt-2">
        <div class="d-flex flex-row-reverse mt-4">
            <button id="Submit" type="button" class="btn">Submit Changes</button>
        </div>
    </div>
</form>
<script type="text/javascript">

    $(document).ready(function(){
        var groups = <?php echo json_encode($Assignments) ?>;
        addGroups(groups);
    });
</script>