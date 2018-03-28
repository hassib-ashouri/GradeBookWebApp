<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!isset($header)) {
    $header = "";
}
if (!isset($mainContent)) {
    $mainContent = "";
}
?>

<!--this combines both view to creat the full view.
this is also the general template for all the pages-->



<?= $header ?>
<body>

<?= $mainContent ?>
</body>