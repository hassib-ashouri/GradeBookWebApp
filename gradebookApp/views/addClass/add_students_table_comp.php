<?php

?>

<!--dynamic table. thanks to-->
<!--https://bootsnipp.com/snippets/featured/dynamic-table-row-creation-and-deletion-->

<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <table class="table table-bordered table-hover" id="tab_logic">
                <thead>
                <tr >
                    <th class="text-center">
                        #
                    </th>
                    <th class="text-center">
                        ID
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr id='addr0'>
                    <td>
                        1
                    </td>
                    <td>
                        <input type="text" name='id0'  placeholder='ID' class="form-control"/>
                    </td>
                </tr>
                <tr id='addr1'></tr>
                </tbody>
            </table>
        </div>
    </div>
    <button id="add_row" class="btn mx-1">Add Row</button>
    <button id='delete_row' class="btn mx-1">Delete Row</button>
</div>

<script>
    $(document).ready(function()
    {
        var i=1;
        $("#add_row").click(function()
        {
            $('#addr'+i).html("<td>"+ (i+1) +"</td><td><input name='id"+i+"' type='text' placeholder='ID' class='form-control input-md'/> </td>");

            $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
            i++;
        });

        $("#delete_row").click(function()
        {
            if(i>1)
            {
                $("#addr"+(i-1)).html('');
                i--;
            }
        });

    });
</script>