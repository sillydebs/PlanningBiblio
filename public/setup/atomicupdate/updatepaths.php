<?php

$sql[]="UPDATE `{$dbprefix}acces` SET `page` = '/closingday' WHERE `page` ='joursFeries/index.php';";


$sql[]="UPDATE `{$dbprefix}menu` SET `page` = '/closingday' WHERE `url` ='joursFeries/index.php';";
?>