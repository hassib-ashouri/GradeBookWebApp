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
?>
<?php if (strlen($errorMessage) > 0): ?>
    <div><?= $errorMessage ?></div>
<?php endif; ?>
<form action="<?= $formAction ?>" method="post">
    <h2>Welcome</h2>
    <div><?= $userName ?></div>
    <div>As this is your first time here please create your password</div>
    <input type="hidden" name="username" value="<?= $userId ?>">
    <div>
        <input type="password" name="password" placeholder="Type Your Password">
    </div>
    <div>
        <input type="password" name="passwordConfirm" placeholder="Type it Again">
    </div>
    <div>
        <button type="submit"><?= $buttonText ?></button>
    </div>
</form>