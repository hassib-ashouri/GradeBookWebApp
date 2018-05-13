(function () {
    var groups = 0;

    $(document).ready(function () {
        $("#addgroupbtn").click(addGroupClick);
        /**
         * add rows to the students table.
         */
        $("#add_row").click(() => addFillOneStudentRow(""));


        /**
         * this function should collect the information about inputed about the class.
         */
        $("#addClass").click(function () {
            var classData = {
                classId: document.getElementById("classid").value,
                professorId: document.getElementById("professorid").value,
                className: document.getElementById("classname").value,
                section: document.getElementById("section").value,
                classTitle: document.getElementById("classtitle").value,
                meetingTimes: document.getElementById("meetingtimes").value,
                students: [],
                assignmentGroups: []
            };

            //get the students
            $("tbody").find("input").each(function (index, input) {
                if (input.value != "") {
                    classData.students.push(input.value);
                }
            });


            //loops through every group of assignments
            $("#groupsList").children().each(function (index, group) {
                var assignmentGroup = {
                    groupName: "",
                    weight: 0,
                    assignmentsArr: []
                };
                //loop though the childres of every group line to get the values of the input
                $(group).children("input").each(function (index, child) {
                    if (index == 0) {
                        assignmentGroup.groupName = child.value;
                    } else if (index == 1) {
                        assignmentGroup.weight = child.value;
                    }
                });
                //loop through assignment row related to the group and get the assifnment information.
                $(group.getElementsByTagName("ul")).children("li")
                    .each(function (index, assignmentRow) {
                        var assignment = {
                            assignmentName: "",
                            assignmentGrade: 0
                        };
                        $(assignmentRow).children("input").each(function (index, input) {
                            //this is bad practice. could not think od another way.
                            if (index == 0) {
                                assignment.assignmentName = input.value;
                            } else if (index == 1) {
                                assignment.assignmentGrade = input.value;
                            }
                        });
                        assignmentGroup.assignmentsArr.push(assignment);
                    });

                classData.assignmentGroups.push(assignmentGroup);
            });
            //send the data as post request.
            $.ajax({
                type: "POST",
                url: IDGF.baseURL + "Add_class_controller/recieveClassInfo",
                data: classData,
                success: function (data) {
                    location.href = IDGF.baseURL + "Class_list_controller/classListView";
                }
            });

        });

        $("#Submit").click(function()
        {
            var classData = {
                classId: document.getElementById("classid").value,
                professorId: document.getElementById("professorid").value,
                className: document.getElementById("classname").value,
                section: document.getElementById("section").value,
                classTitle: document.getElementById("classtitle").value,
                meetingTimes: document.getElementById("meetingtimes").value,
                students: [],
                assignmentGroups: []
            };

            //get the students
            $("tbody").find("input").each(function (index, input) {
                if (input.value != "") {
                    classData.students.push(input.value);
                }
            });

            //loops through every group of assignments
            $("#groupsList").children().each(function (index, group) {
                var assignmentGroup = {
                    groupName: "",
                    weight: 0,
                    status: "",
                    assignmentsArr: []
                };

                // get the name of the group.
                assignmentGroup.groupName = group.querySelector("#name").value;
                //if the group does not have a name, skip this group.
                if(assignmentGroup.groupName == ""){return;}

                // get the group weight.
                assignmentGroup.weight = group.querySelector("#weight").value;
                // get the status of the group.
                assignmentGroup.status = group.dataset.status;
                // loop through assignment rows related to the group and get the assifnment information.
                $(group.getElementsByTagName("ul")).children()
                    .each(function (index, assignmentRow) {
                        var assignment = {
                            assignmentId : "",
                            assignmentName: "",
                            assignmentGrade: 0
                        };
                        // get the assignment id.
                        assignment.assignmentId = assignmentRow.dataset.assignmentId;
                        // get the assignment name.
                        assignment.assignmentName = assignmentRow.querySelector("#name").value;
                        // if the assignment is empty, skip it.
                        if(assignment.assignmentName == ""){return;}
                        // get the assignment grade.
                        assignment.assignmentGrade = assignmentRow.querySelector("#grade").value;
                        // add the assignment info to its group.
                        assignmentGroup.assignmentsArr.push(assignment);
                    });

                classData.assignmentGroups.push(assignmentGroup);
            });
            //send the data as post request.
            $.ajax({
                type: "POST",
                url: IDGF.baseURL + "Edit_class_controller/recieveClassInfo",
                data: classData,
                success: function (data) {
                    location.href = IDGF.baseURL + "Class_list_controller/classListView";
                }
            });
        });

    });




    function addGroupClick(event) {
        //TODO: add this to input elements
        // <div class="invalid-feedback">
        //     Please choose a username.
        // </div>
        // <div class="valid-feedback">
        //     Looks good!
        // </div>

        let $li = $("<li>")
            .addClass("list-group-item")
            .attr({"data-status" : "new"});
        let $nameSpan = $("<span>").html("Group Name: ");
        let $nameInput = $("<input>")
            .addClass("form-control")
            .attr({
                type: "text",
                id : "name",
                placeholder: "Group Name"
            });
        let $weightSpan = $("<span>").html("Weight: ");
        let $weightInput = $("<input>")
            .addClass("form-control")
            .attr({
                type: "text",
                id : "weight",
                placeholder: "Group Weight"
            });
        let $button = $("<button>")
            .addClass("btn btn-primary my-1")
            .html("Add Assignment")
            .attr({type:"button"})
            .on({click: addAssignment()});
        let $assignmentList = $("<ul>")
            .addClass("list-group my-2");

        $("#groupsList").append(
            $li.append($nameSpan)
                .append($nameInput)
                .append($weightSpan)
                .append($weightInput)
                .append($assignmentList)
                .append($button)
        );

        groups++;
    }

    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);


})();

/**
 * this function fills the rows of students when editing a class.
 * @param idsArr an array of student ids
 */
function addStudentRow(idsArr)
{
    for(var index = 0 ; index < idsArr.length; index++)
        addFillOneStudentRow(idsArr[index]);
}

/**
 * this function fills one specific row of the students table with the
 * passed id. Its used with empty string argument to simulate adding a new
 * empty row.
 * @param studentId
 */
function addFillOneStudentRow(studentId)
{
    //TODO: add this to input elements
    // <div class="invalid-feedback">
    //     Please choose a username.
    // </div>
    // <div class="valid-feedback">
    //     Looks good!
    // </div>
    $input = $("<input>")
        .addClass("form-control")
        .attr({
            type: "text",
            placeholder: "ID",
            value: studentId.toString()
        });
    $inputCell = $("<td>").append($input);
    $removeBtn = $("<button>")
        .addClass("btn btn-danger")
        .html("Remove")
        .attr({type:"button"})
        .on({
            click: function () {
                this.parentElement.parentElement.remove();
            }
        });
    $btnCell = $("<td>").append($removeBtn);
    $row = $("<tr>")
        .append($inputCell)
        .append($btnCell);
    $("tbody").append($row);
}



/**
 * this function should returns a funtion that will be attached to the group list.
 * the attached funtion will add new assignments in the assignment group.
 * @param string list the id of the group list of assignments. the <ul> element.
 * @returns {Function}
 */
function addAssignment()
{
    return function ()
    {
        //TODO: add this to input elements
        // <div class="invalid-feedback">
        //     Please choose a username.
        // </div>
        // <div class="valid-feedback">
        //     Looks good!
        // </div>
        $li = $("<li>")
            .addClass("list-group-item")
            .attr({"data-assignment-id":"new"});
        $AssignmentNameSpan = $("<span>").html("Assignment Name: ");
        $AssignmentNameInput = $("<input>")
            .addClass("form-control")
            .attr({
                type: "text",
                id : "name"
            });
        $AssignmentGradeSpan = $("<span>")
            .html("Grade: ");
        $AssignmentGradeInput = $("<input>")
            .addClass("form-control")
            .attr({
                type: "number",
                id : "grade"
            });
        $AssignmentRemoveBtn = $("<Button>")
            .addClass("btn btn-danger mt-2")
            .html("Remove")
            .attr({type:"button"})
            .on({
                click: function()
                {
                    this.parentElement.remove();
                }
            });

        $(this.parentElement).children("ul").append(
            $li.append($AssignmentNameSpan)
                .append($AssignmentNameInput)
                .append($AssignmentGradeSpan)
                .append($AssignmentGradeInput)
                .append($AssignmentRemoveBtn)
        );
    };
}



/**
 * this function fills the assignments in lists while editing
 * the class info in edit class view.
 * @param groups
 */
function addGroups(groups)
{
    function getWieght(group)
    {
        for(var assignmentid in group.assignments)
        {
            return group.assignments[assignmentid].weight;
        }
    }
    console.log(groups);


    for (var key in groups)
    {
        //TODO: add this to input elements
        // <div class="invalid-feedback">
        //     Please choose a username.
        // </div>
        // <div class="valid-feedback">
        //     Looks good!
        // </div>

        $Groupli = $("<li>")
            .addClass("list-group-item")
            .attr({"data-status" : "notnew"});
        $nameSpan = $("<span>")
            .html("Group Name: ");
        $nameInput = $("<input>")
            .addClass("form-control")
            .attr({
                type: "text",
                placeholder: "Group Name",
                id:"name",
                value : key,
            });
        $weightSpan = $("<span>")
            .html("Weight: ");
        $weightInput = $("<input>")
            .addClass("form-control")
            .attr({
                type: "text",
                placeholder: "Group Weight",
                id: "weight",
                value: getWieght(groups[key]),
            });
        $button = $("<button>")
            .addClass("btn btn-primary my-1")
            .html("Add Assignment")
            .attr({type:"button"})
            .on({click: addAssignment()});
        $assignmentList = $("<ul>")
            .addClass("list-group my-2");

        $("#groupsList").append(
            $Groupli.append($nameSpan)
                .append($nameInput)
                .append($weightSpan)
                .append($weightInput)
                .append($assignmentList)
                .append($button)
        );

        var group = groups[key]["assignments"];

        // add the assignments in this group to the list.
        addAssignments($Groupli.children("ul"), group);

    }
}

/**
 * adds the assignments to the given list.
 * @param listElement unorder list html element to add the assignments to.
 * @param assignments array of assignments to be added to the list.
 */
function addAssignments(listElement,assignments)
{
    for( var assignment in assignments)
    {
        //TODO: add this to input elements
        // <div class="invalid-feedback">
        //     Please choose a username.
        // </div>
        // <div class="valid-feedback">
        //     Looks good!
        // </div>
        $Assignmentli = $("<li>").addClass("list-group-item").attr({"data-assignment-id" : assignment});
        $AssignmentNameSpan = $("<span>")
            .html("Assignment Name: ");
        $AssignmentNameInput = $("<input>")
            .addClass("form-control")
            .attr({
                type : "text",
                value : assignments[assignment]["assignment_name"],
                id : "name"
            });
        $AssignmentMaxGradeSpan = $("<span>")
            .html("Max Grade: ");
        $AssignmentMaxGradeInput = $("<input>")
            .addClass("form-control")
            .attr({
                type:"text",
                value: assignments[assignment]["max_points"],
                id: "grade"
            });
        $AssignmentRemoveBtn = $("<Button>")
            .addClass("btn btn-danger mt-2")
            .attr({type:"button"})
            .html("Remove")
            .on({
                click: function()
                {
                    this.parentElement.remove();
                }
            });
        $Assignmentli
            .append($AssignmentNameSpan)
            .append($AssignmentNameInput)
            .append($AssignmentMaxGradeSpan)
            .append($AssignmentMaxGradeInput)
            .append($AssignmentRemoveBtn);

        $(listElement).append($Assignmentli);
    }
}