<?php

$sql[]="DELETE FROM `{$dbprefix}acces` WHERE `page`='personnel/index.php';";

$sql[]="UPDATE `{$dbprefix}menu` SET `url` = '/agent' WHERE `url`='personnel/index.php';";

?>