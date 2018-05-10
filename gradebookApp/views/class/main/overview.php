<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @var \Objects\Student[] $students
 */
$students = (isset($students)) ? $students : array();
?>

<div class="container">
    <h2>Overview</h2>
    <p>Overall class overview displaying students first, last names and overall grade score:</p>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Student Name</th>
            <th>Overall Grade</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $student): ?>
            <tr>
                <th><?= "$student->name_last, $student->name_first" ?></th>
                <td><?= number_format($student->getGrade(), 2) ?>%</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

