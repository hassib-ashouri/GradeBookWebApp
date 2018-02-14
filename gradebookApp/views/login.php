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
    <?php if (strlen($userName) > 0 && strlen($userId) > 0): ?>
        <h2>Welcome</h2>
        <div><?= $userName ?></div>
        <input type="hidden" name="username" value="<?= $userId ?>">
        <div>
            <input type="password" name="password" placeholder="Password">
        </div>
    <?php else: ?>
        <div>
            <input type="text" name="username" placeholder="ID">
        </div>
    <?php endif; ?>
    <div>
        <button type="submit"><?= $buttonText ?></button>
    </div>
</form>