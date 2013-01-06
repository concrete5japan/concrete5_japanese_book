<?php    defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php
$autonav = BlockType::getByHandle('autonav');
$autonav->controller->orderBy = 'display_asc';
$autonav->controller->displayPages = 'top';
$autonav->controller->displaySubPages = 'none';
$autonav->render('templates/global_nav');
?>