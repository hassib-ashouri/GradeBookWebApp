<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!isset($header)) {
    $header = "";
}
if (!isset($mainContent)) {
    $mainContent = "";
}
?>
<?= $header ?>
<body>
<?= $mainContent ?>
</body>