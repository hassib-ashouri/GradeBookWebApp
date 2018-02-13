<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php if (isset($errorMessage)): ?>
    <div><?= $errorMessage ?></div>
<?php endif; ?>
<div>display login screen</div>
<div>and allow for some sort of error message</div>
<form action="<?= base_url() . "IndexController/login" ?>" method="post">
    <div>
        <input type="text" name="username" placeholder="ID">
    </div>
    <div>
        <input type="password" name="password" placeholder="Password">
    </div>
    <div>
        <button type="submit">Log in</button>
    </div>
</form>