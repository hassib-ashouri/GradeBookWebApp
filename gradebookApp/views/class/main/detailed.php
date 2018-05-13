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
    <?php $rowCount = count($grades);
    $row = 0;
    foreach ($grades as $studentName => $gradeArr): ?>
        <tr>
            <th scope="row">
                <?= $studentName ?>
            </th>
            <?php $colCount = count($gradeArr['grades']); ?>
            <?php foreach ($gradeArr['grades'] as $col => $grade):
                $studentId = $gradeArr['studentId'];
                $assignId = $assignmentsNames[$col]['assignId']; ?>
                <td class="<?= "col_$col row_$row" ?>">
                    <span class="d-inline"><?= $grade ?></span>
                    <input name="<?= "a-$studentId-$assignId" ?>"
                           class="d-none" value="<?= $grade ?>">
                </td>
            <?php endforeach; ?>
        </tr>
        <?php $row++;
    endforeach; ?>
    </tbody>
</table>