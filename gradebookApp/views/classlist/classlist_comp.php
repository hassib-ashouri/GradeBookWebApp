<?php
/**
 * Created by IntelliJ IDEA.
 * User: soulstaker
 * Date: 3/16/18
 * Time: 2:39 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$loggedUser = isset($loggedUser) ? $loggedUser : "its not in the session data."

//$editClassLink = isset($editClassLink) ? $editClassLink : "";

?>

<h3>professor's list of classes (<?= $loggedUser?>)</h3>
<div class="container">
    <ul class="list-group">
        <?php
        foreach ($classes as $tableName):?>
        <li class="list-group-item">
            <a href="<?= base_url() . 'Class_controller/displayClassInfo/' . $tableName ?>">
                <?= $tableName?>
            </a>
            <button type="button" class="btn btn-danger deleteClassButton" data-html="true"
                    data-container="body" data-toggle="popover" data-trigger="focus"
                    data-placement="top" title="Are you sure you want to delete the class?"
                    data-content="<div style='text-align: center'><button class='btn' type='button' style='margin-right: 1em'><a href='<?= base_url() . 'Class_list_controller/deleteClass/' . $tableName ?>'>Yes</a></button><button class='btn' type='button'><a href='#'>No</a></button></div>">
                Delete
            </button>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<button class="btn" onclick="location.href = '<?= $addClassLink ?>';">add class</button>

<button class="btn" onclick="location.href = '<?= $editClassLink ?>';">edit class</button>