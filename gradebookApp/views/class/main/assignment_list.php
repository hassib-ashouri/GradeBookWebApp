<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @var \Objects\AssignmentList[] $assignmentGroups
 */
$assignmentGroups = (isset($assignmentGroups)) ? $assignmentGroups : array();
?>

<ul class="list-group">
    <?php foreach ($assignmentGroups as $assignmentGroup): ?>
        <li class="list-group-item">
            <span><?= $assignmentGroup->getGroupName() ?></span>
            <span>Weight: <?= $assignmentGroup->getGroupWeight() ?>%</span>
            <ul class="list-group my-2">
                <?php $assignments = $assignmentGroup->getAssignments();
                foreach ($assignments as $assignment): ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div class="p-2">
                                <span><?= $assignment->assignment_name ?></span>
                            </div>
                            <div class="p-2">
                                <span>Max Points: </span>
                                <span><?= number_format($assignment->max_points, 2) ?></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="p-2">
                                <span>Average Grade: </span>
                                <span><?= number_format($assignment->getMeanGrade(), 2) ?></span>
                            </div>
                            <div class="p-2">
                                <span>High Grade: </span>
                                <span><?= number_format($assignment->getHighGrade(), 2) ?></span>
                            </div>
                            <div class="p-2">
                                <span>Low Grade: </span>
                                <span><?= number_format($assignment->getLowGrade(), 2) ?></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="p-2">
                                <span>Median Grade: </span>
                                <span><?= number_format($assignment->getMedianGrade(), 2) ?></span>
                            </div>
                            <div class="p-2">
                                <span>Standard Deviation: </span>
                                <span><?= number_format($assignment->getStdDevGrade(), 2) ?></span>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>
