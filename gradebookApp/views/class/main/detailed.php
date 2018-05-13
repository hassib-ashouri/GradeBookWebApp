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
        <th></th>
        <?php foreach ($assignmentsNames as $assignmentArray): ?>
            <th scope="col" title="<?= $assignmentArray['name'] ?>">
                <input type="checkbox" class="toggle_col">
                <br>
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
            <td>
                <input type="checkbox" class="toggle_row">
            </td>
            <?php $colCount = count($gradeArr['grades']); ?>
            <?php foreach ($gradeArr['grades'] as $col => $grade):
                $studentId = $gradeArr['studentId'];
                $assignId = $assignmentsNames[$col]['assignId']; ?>
                <td class="<?= "col_$col row_$row" ?>" style="height: 1.5em; width: 5em">
                    <span class="d-inline-block"
                          style="height: inherit; width: inherit;">
                        <?= $grade ?>
                    </span>
                    <input name="<?= "a-$studentId-$assignId" ?>"
                           class="d-none" value="<?= $grade ?>"
                           style="height: inherit; width: inherit;">
                </td>
            <?php endforeach; ?>
        </tr>
        <?php $row++;
    endforeach; ?>
    </tbody>
</table>

<script>
    $(document).ready(() => {
        function toggleRow(row) {
            return (e) => {
                let isChecked = $(e.currentTarget).is(":checked");
                let $td = $('.row_' + row);

                $td.data("row", isChecked);

                if (isChecked) {
                    $td.find("span").removeClass("d-inline-block").addClass("d-none");
                    $td.find("input").removeClass("d-none").addClass("d-inline-block");
                } else {
                    $td.find("input").removeClass("d-inline-block").addClass("d-none");
                    $td.find("span").removeClass("d-none").addClass("d-inline-block");
                }

                $td.each((index, ele) => {
                    let $ele = $(ele);
                    let colChecked = $ele.data("col");
                    if (colChecked) {
                        $ele.find("span").removeClass("d-inline-block").addClass("d-none");
                        $ele.find("input").removeClass("d-none").addClass("d-inline-block");
                    }
                });
            };
        }

        function toggleCol(col) {
            return (e) => {
                let isChecked = $(e.currentTarget).is(":checked");
                let $td = $('.col_' + col);

                $td.data("col", isChecked);

                if (isChecked) {
                    $td.find("span").removeClass("d-inline-block").addClass("d-none");
                    $td.find("input").removeClass("d-none").addClass("d-inline-block");
                } else {
                    $td.find("input").removeClass("d-inline-block").addClass("d-none");
                    $td.find("span").removeClass("d-none").addClass("d-inline-block");
                }

                $td.each((index, ele) => {
                    let $ele = $(ele);
                    let rowChecked = $ele.data("row");
                    if (rowChecked) {
                        $ele.find("span").removeClass("d-inline-block").addClass("d-none");
                        $ele.find("input").removeClass("d-none").addClass("d-inline-block");
                    }
                });
            };
        }

        $('.toggle_row').each((index, ele) => {
            $(ele).on({change: toggleRow(index)});
        });

        $('.toggle_col').each((index, ele) => {
            $(ele).on({change: toggleCol(index)});
        });
    });
</script>