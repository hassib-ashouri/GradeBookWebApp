<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($errorMessage)) {
    $errorMessage = "";
}
if (!isset($userName)) {
    $userName = "";
}
if (!isset($userId)) {
    $userId = "";
}
if (!isset($buttonText)) {
    $buttonText = "";
}
if (!isset($formAction)) {
    $formAction = "";
}

$existing_user_view = isset($existing_user_view) ? $existing_user_view : "";
?>


<div class="card mx-auto" style="width: 18rem;">

    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link" href="<?=$existing_user_view?>">Login</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="#">First Timer</a>
            </li>
        </ul>
    </div>

    <div class="card-body">

        <h5 class="card-title">Welcome</h5>

        <form action="<?= $formAction ?>" method="post">
            <?php if (strlen($userName) > 0 && strlen($userId) > 0): ?>

                <div class="form-group">

                    <label>User: <?= $userName ?></label>
                    <input name="username" value="<?= $userId ?>" hidden>

                    <input id="pass-input" type="password" class="form-control" name="password" placeholder="Password" onkeyup="verifyy('pass-input')">

                    <!--error message if id deos not exist in the DB-->
                    <?php if ($errorMessage != ""): ?>
                        <small class="form-text text-muted"><?= $errorMessage ?></small>
                    <?php endif; ?>
                </div>

                <button id="btn" class="btn btn-primary" type="submit" disabled="true"><?= $buttonText ?></button>


            <?php else: ?>


                <div class="form-group">
                    <label>For first time users.</label>
                    <input id="id-input" type="text" class="form-control" name="username" placeholder="ID" onkeyup="verifyy('id-input')">

                    <!--error message if id deos not exist in the DB-->
                    <?php if ($errorMessage != ""): ?>
                    <small class="form-text text-muted"><?= $errorMessage ?></small>
                    <?php endif; ?>

                </div>

                <button id="btn" class="btn btn-primary" type="submit" disabled="true"><?= $buttonText ?></button>


            <?php endif; ?>
        </form>


    </div>
</div>

<script type="text/javascript">

    /**
     *  enables the submit button if there is data in
     *  the input fields.
     * @param field the id of an input field.
     */
    function verifyy(field)
    {
        var text = document.getElementById(field).value;
        var btn = document.getElementById("btn");
        //check for spaces
        if(text.trim().length != 0)
        {
            btn.disabled = false;
        }
        else
        {
            btn.disabled = true;
        }
    }
</script>