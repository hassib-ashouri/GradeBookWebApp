<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//initialize vars to empty strings if they are not set
$errorMessage = isset($errorMessage) ? $errorMessage : "";
$formAction = isset($formAction) ? $formAction : "";
$new_user_view = isset($new_user_view) ? $new_user_view : "";
?>

<div class="card mx-auto" style="width: 18rem;">

    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $new_user_view?>">First time</a>
            </li>
        </ul>
    </div>

    <div class="card-body">

        <h5 class="card-title">Welcome</h5>

        <form action="<?= $formAction ?>" method="post">


            <div class="form-group">
                <input id="id-input" type="text" class="form-control" name="username" placeholder="ID" onkeyup="verifyy('id-input','pass-input')">
            </div>

            <div class="form-group">
                <input id="pass-input" type="password" class="form-control" name="password" placeholder="Password" onkeyup="verifyy('pass-input','id-input')">

                <!--how error message if id deos not exist in the DB-->
                <?php if ($errorMessage != ""): ?>
                    <small class="form-text text-muted"><?= $errorMessage ?></small>
                <?php endif; ?>
            </div>

            <button id="btn" class="btn btn-primary" type="submit" disabled="true">Login</button>

        </form>

    </div>
</div>

<script type="text/javascript">

    // disable button when input in empty
    function verifyy(field1 , field2)
    {
        var text1 = document.getElementById(field1).value;
        var text2 = document.getElementById(field2).value;
        var btn = document.getElementById("btn");
        //check for spaces
        if(text1.trim().length == 0 || text2.trim().length == 0)
        {
            btn.disabled = true;
        }
        else
        {
            btn.disabled = false;
        }
    }
</script>
