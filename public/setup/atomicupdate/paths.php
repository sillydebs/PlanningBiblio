<?php

$sql[]="INSERT INTO `{$dbprefix}acces` (`nom`,`groupe_id`,`groupe`,`page`,`categorie`,`ordre`) VALUES ('Planning Hebdo - Index','1101','Gestion des heures de présences, validation niveau 1','/attendance','Heures de présence','80');";

$sql[]="INSERT INTO `{$dbprefix}menu` (`niveau1`,`niveau2`,`titre`,`url`,`condition`) VALUES   ('50','75','Heures de présence','/attendance','config=PlanningHebdo');";

$sql[]="UPDATE `{$dbprefix}acces` SET `nom` = 'Planning Hebdo - Ajout' `page`='/attendance/add' WHERE `page`='planningHebdo/modif.php' ;";

$sql[]="DELETE FROM `{$dbprefix}acces` WHERE `page`='planningHebdo/valid.php' ;";
