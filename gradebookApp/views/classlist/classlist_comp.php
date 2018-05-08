<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$loggedUser = isset($loggedUser) ? $loggedUser : "its not in the session data.";
/**
 * @var \Objects\ClassObj[] $classObjects
 */
$classObjects = (isset($classObjects)) ? $classObjects : array();
?>

<h3>professor's list of classes (<?= $loggedUser ?>)</h3>
<div class="container">
    <ul class="list-group">
        <?php
        foreach ($classObjects as $classObj):?>
            <li class="list-group-item">
                <a href="<?= base_url() . 'Class_controller/displayClassInfo/' . $classObj->table_name ?>">
                    <?= "$classObj->class_name-$classObj->section: $classObj->class_title" ?>
                </a>
                <button type="button" class="btn">
                    <a href="<?= base_url() . 'Edit_class_controller/editClassView/' . $classObj->class_id ?>">
                        Edit
                    </a>
                </button>
                <button type="button" class="btn btn-danger deleteClassButton" data-html="true"
                        data-container="body" data-toggle="popover" data-trigger="focus"
                        data-placement="top" title="Are you sure you want to delete the class?"
                        data-content="<div style='text-align: center'><button class='btn' type='button' style='margin-right: 1em'><a href='<?= base_url() . 'Class_list_controller/deleteClass/' . $classObj->table_name ?>'>Yes</a></button><button class='btn' type='button'><a href='#'>No</a></button></div>">
                    Delete
                </button>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<button class="btn" onclick="location.href = '<?= $addClassLink ?>';">add class</button>