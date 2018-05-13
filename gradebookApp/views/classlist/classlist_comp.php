<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @var \Objects\ClassObj[] $classObjects
 */
$classObjects = (isset($classObjects)) ? $classObjects : array();
/**
 * @var string $addClassLink
 */
$addClassLink = (isset($addClassLink)) ? $addClassLink : '';
?>

<h3>Class List</h3>
<div class="container">
    <ul class="list-group">
        <?php
        foreach ($classObjects as $classObj):?>
            <li class="list-group-item d-flex flex-row">
                <div class="align-self-center mr-auto">
                    <a href="<?= base_url() . 'Class_controller/displayClassInfo/' . $classObj->table_name ?>">
                        <?= "$classObj->class_name-$classObj->section: $classObj->class_title" ?>
                    </a>
                </div>
                <div class="mr-1">
                    <?php $editClassLink = base_url() . 'Edit_class_controller/editClassView/' . $classObj->class_id; ?>
                    <button type="button" class="btn"
                            onclick="location.href = '<?= $editClassLink ?>'">
                        Edit
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-danger deleteClassButton" data-html="true"
                            data-container="body" data-toggle="popover" data-trigger="focus"
                            data-placement="top" title="Are you sure you want to delete the class?"
                            data-content="<div style='text-align: center'><button class='btn' type='button' style='margin-right: 1em'><a href='<?= base_url() . 'Class_list_controller/deleteClass/' . $classObj->table_name ?>'>Yes</a></button><button class='btn' type='button'><a href='#'>No</a></button></div>">
                        Delete
                    </button>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<button class="btn mt-2 mb-2"
        onclick="location.href = '<?= $addClassLink ?>';">
    Add Class
</button>