<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @var String[] the names of the assignments.
 */
$assignmentsNames = isset($assignmentsNames) ? $assignmentsNames : array();
/**
 * @var array[] where the keys are the names of students and the values are arrays of grades.
 */
$grades = isset($grades) ? $grades : array();
?>


<span>This is the component where the detailed grades for the students to show.</span>


<h4>Students' Grades</h4>

<table class="table">
    <thead>
    <tr>
        <th scope="col">
            Student Name
        </th>
        <?php foreach ($assignmentsNames as $assignmentArray): ?>
            <th scope="col" title="<?= $assignmentArray['name'] ?>">
                <?= $assignmentArray['alias'] ?>
            </th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach($grades as $studentName => $gradeArr): ?>
    <tr>
        <th scope="row">
            <?= $studentName ?>
        </th>
        <?php foreach($gradeArr as $grade):?>
            <td>
                <?= $grade ?>
            </td>
        <?php endforeach;?>
    </tr>
    <?php endforeach; ?>

    </tbody>


</table>