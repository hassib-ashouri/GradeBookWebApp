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
$new_user_view = isset($new_user_view) ? $new_user_view : "";
?>


<div class="card mx-auto" style="width: 18rem;">

    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link" href="<?=$existing_user_view?>">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?=$new_user_view?>">First time</a>
            </li>
        </ul>
    </div>

    <div class="card-body">

        <form action="<?= $formAction ?>" method="post">
            <p><?= $userName ?>, as this is your first time here please create your password</p>
            <input type="hidden" name="username" value="<?= $userId ?>">

            <div class="form-group">
                <label>Type the new password</label>
                <input class="form-control" id="pass1" type="password" name="password" placeholder="Password" onkeyup="verifyy('pass1','pass2')">
            </div>

            <div class="form-group">
                <input class="form-control" id="pass2" type="password" name="passwordConfirm" placeholder="Type it Again" onkeyup="verifyy('pass1','pass2')">
<!--                if the passwords dont match, an error message will show.-->
                <?php if (strlen($errorMessage) != ""): ?>
                <small class="form-text text-muted"><?= $errorMessage ?></small>
                <?php endif; ?>
            </div>

            <div>
                <button id="btn" class="btn btn-primary" type="submit"><?= $buttonText ?></button>
            </div>
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