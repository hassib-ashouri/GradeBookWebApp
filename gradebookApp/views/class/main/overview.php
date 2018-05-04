<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @var \Objects\Student[] $students
 */
$students = (isset($students)) ? $students : array();
?>

<div class="container">
    <h2>Hover Rows</h2>
    <p>The .table-hover class enables a hover state on table rows:</p>
    <table class="table table-hover">
        <thead>
        <tr>
            <th> First Name </th>
            <th> Last Name </th>
            <th> Overall Grade </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $student): ?>
        <tr>
            <th> <?= $student->name_first ?> </th>
            <th> <?= $student->name_last ?> </th>
            <th> <?= $student->getGrade() ?> </th>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td>Mary</td>
            <td>Moe</td>
            <td>mary@example.com</td>
        </tr>
        <tr>
            <td>July</td>
            <td>Dooley</td>
            <td>july@example.com</td>
        </tr>
        </tbody>
    </table>
</div>

