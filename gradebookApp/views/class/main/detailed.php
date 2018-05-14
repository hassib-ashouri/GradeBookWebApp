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
/**
 * @var string $classId
 */
$classId = isset($classId) ? $classId : '';
?>

<h4>Students' Grades</h4>
<form id="detailedForm" method="post"
      action="<?= base_url() . "Class_controller/updateStudentAssignments" ?>">
    <input type="hidden" name="classId" value="<?= $classId ?>">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">
                Student Name
            </th>
            <th></th>
            <?php foreach ($assignmentsNames as $assignmentArray): ?>
                <th scope="col" title="<?= $assignmentArray['name'] ?>">
                    <input type="checkbox" class="toggle_col"
                           style="height: 1em; width: 1em;">
                    <br>
                    <?= $assignmentArray['alias'] ?>
                </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php $row = 0;
        foreach ($grades as $studentName => $gradeArr): ?>
            <tr>
                <th scope="row">
                    <?= $studentName ?>
                </th>
                <td>
                    <input type="checkbox" class="toggle_row"
                           style="height: 1em; width: 1em;">
                </td>
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
        <tr>
            <th>Max Points</th>
            <td></td>
            <?php foreach ($assignmentsNames as $assignmentArray): ?>
                <th style="height: 1.5em; width: 5em">
                    <span class="d-inline-block"
                          style="height: inherit; width: inherit;">
                        <?= $assignmentArray['maxPoints'] ?>
                    </span>
                </th>
            <?php endforeach; ?>
        </tr>
        <tr>
            <th>Graded</th>
            <td></td>
            <?php $col = 0;
            foreach ($assignmentsNames as $assignmentArray): ?>
                <td style="height: 1.5em; width: 1.5em">
                    <input name="<?= "graded-$col" ?>" type="checkbox"
                           value="1"
                        <?= ($assignmentArray['graded']) ? 'checked' : '' ?>
                           style="height: inherit; width: inherit;">
                </td>
                <?php $col++;
            endforeach; ?>
        </tr>
        </tbody>
    </table>
</form>
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

        function bindNeighbors(row) {
            let $input = $('.row_' + row).find("input");
            $input.on({
                change: (e) => {
                    let $currentTarget = $(e.currentTarget);
                    let val = $currentTarget.val();
                    let $span = $currentTarget.siblings("span");
                    $span.html(val);
                }
            });
        }

        let $toggleRow = $('.toggle_row');
        let $toggleCol = $('.toggle_col');

        $toggleRow.each((index, ele) => {
            $(ele).on({change: toggleRow(index)});
        });
        $toggleCol.each((index, ele) => {
            $(ele).on({change: toggleCol(index)});
        });

        $toggleRow.each((index, ele) => {
            $(ele).on({change: bindNeighbors(index)});
        });

        let $detailedSubmit = $("#detailedSubmit");

        $detailedSubmit.on({
            click: (e) => {
                $("#detailedForm").submit();
            }
        });

        $("#details-tab").on({
            click: () => {
                $detailedSubmit.fadeIn(600);
            }
        });
        $("#overview-tab, #assignments-tab").on({
            click: () => {
                $detailedSubmit.fadeOut(600);
            }
        });
    });
</script>