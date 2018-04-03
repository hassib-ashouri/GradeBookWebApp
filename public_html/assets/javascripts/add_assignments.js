(function () {
    var groups = 0;

    $(document).ready(function () {
        $("#addgroupbtn").click(addGroupClick);

        /**
         * this function should collect the information about inputed ahout the class.
         */
        $("#getInfo").click(function () {
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

            $.ajax({
                type: "POST",
                url: IDGF.baseURL + "Add_class_controller/recieveClassInfo",
                data: classData,
                success: function (data) {
                    console.log(data);
                }
            });

        });
    });

    function addAssignment(list) {
        return function () {
            var targetList = "#" + list;
            $(targetList).append(
                "<li class='list-group-item'>Assignment Name: <input class='form-control' type='text'> Grade: <input class='form-control' type='number'></li>"
            );
        };
    }

    function addGroupClick(event) {
        var groupTxt = "group" + (groups + 1),
            $li = $("<li>").addClass("list-group-item"),
            $nameSpan = $("<span>").html("Group Name: "),
            $nameInput = $("<input>")
                .addClass("form-control")
                .attr({
                    type: "text",
                    name: groupTxt + "Name",
                    placeholder: "Group Name"
                }),
            $weightSpan = $("<span>").html("Weight: "),
            $weightInput = $("<input>")
                .addClass("form-control")
                .attr({
                    type: "text",
                    name: groupTxt + "Weight",
                    placeholder: "Group Weight"
                }),
            $button = $("<button>")
                .addClass("btn btn-primary my-1")
                .html("Add Assignments")
                .on({click: addAssignment(groupTxt + "AssignmentList")}),
            $assignmentList = $("<ul>")
                .addClass("list-group")
                .attr({id: groupTxt + "AssignmentList"});

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
})();