<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!isset($header)) {
    $header = "";
}
if (!isset($mainContent)) {
    $mainContent = "";
}

$partialViews = isset($partialViews) ? $partialViews : array();
?>

<!--this combines both view to creat the full view.
this is also the general template for all the pages-->

<?= $header ?>
<body>
    <div class="container">
        <?= $mainContent ?>
        <?php foreach($partialViews as $partialView): ?>
          <?= $partialView ?>
        <?php endforeach; ?>
    </div>
</body>