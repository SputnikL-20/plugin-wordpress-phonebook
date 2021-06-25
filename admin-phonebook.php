<?php
session_start();

use function classes\Dml\createTable;
use function classes\Dml\deleteData;
use function classes\Dml\insertData;
use function classes\Dml\updateData;
use function classes\Pagination\arrayPaginotion;
use function classes\Pagination\dataPaginator;
use function classes\Search\arrayUniqueKey;
use function classes\Search\showFindData;
use function inc\adminPhonebook;
use function inc\formPagination;
use function inc\searchForm;
use classes\Pagination;
use classes\Search;
use classes\Dml;

require_once 'inc/constant.php';
require_once 'classes/Pagination.php';
require_once 'inc/admin-template.php';
require_once 'classes/Search.php';
require_once 'classes/Dml.php';

print ADMIN_HEADER;
searchForm();

$obj = new Dml();

if (empty($_POST) && !empty($_SESSION)) { 
    adminPhonebook($obj->upload_post()); 
} 

if (empty($_POST) && empty($_SESSION)) {
    adminPhonebook($obj->upload_sess(VIEW));
}

if (isset($_POST['prev'])) {
    adminPhonebook($obj->skipback());
}

if (isset($_POST['prev-pagination'])) { 
    adminPhonebook($obj->back());
}

if (isset($_POST['confirm-count-view'])) {
    adminPhonebook($obj->confirm_view());
}

if (isset($_POST['next-pagination'])) { 
    adminPhonebook($obj->next());
}

if (isset($_POST['next'])) {
    adminPhonebook($obj->skipnext());
}

if (isset($_POST['ok']) && !empty($_POST['search'])) {
    $arr = (array) new Search();
    adminPhonebook(array_values($arr['unique']));
}

if (isset($_POST['clear'])) { // button 'Очистить' (поиск)
    adminPhonebook($obj->viewPagination());
}

if (isset($_POST['create'])) { // Вызов формы для создания контакта
  adminPhonebook();
}

if (isset($_POST['insert'])) {
    $obj->press_insert();
    adminPhonebook($obj->skipnext());
}

if (isset($_POST['update'])) {
    $obj->press_update();
    adminPhonebook($obj->viewPagination());
}

if (isset($_POST['delete'])) {
    $obj->press_delete();
    adminPhonebook($obj->viewPagination());
    // adminPhonebook($obj->arrayPaginotion($_SESSION['index-'.session_id()], $_SESSION['view-'.session_id()]));
}

if (isset($_POST['create-table'])) {
  createTable();
  adminPhonebook();
}

// Functions //
// SELECT `wp_tel_spravochnik`.`otdel` FROM `wp_tel_spravochnik` GROUP BY `wp_tel_spravochnik`.`otdel` ORDER BY `wp_tel_spravochnik`.`otdel` ASC 
// function createTable() {
//   global $wpdb;
//   $sql = "CREATE TABLE IF NOT EXISTS `wp_tel_spravochnik`(\n"
//       . "    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,\n"
//       . "    `fio` VARCHAR(150) NOT NULL,\n"
//       . "    `otdel` VARCHAR(15) NOT NULL,\n"
//       . "    `position` VARCHAR(55) NOT NULL,\n"
//       . "    `number` VARCHAR(11) DEFAULT NULL,\n"
//       . "    `small_number` VARCHAR(7) DEFAULT NULL,\n"
//       . "    `room` VARCHAR(15) NOT NULL,\n"
//       . "    `address` VARCHAR(100) NOT NULL\n"
//       . ") ENGINE = InnoDB DEFAULT CHARSET = utf8";
//   $wpdb->query($sql);
// }

// function queryMySql($query = null) {
//   global $wpdb;
//   return $wpdb->get_results($query, ARRAY_A);
// }


// echo "<pre>";
// print_r($_POST);
// print_r($_SESSION);
// echo "</pre>";


//    echo "<pre>";
//    print_r($arr['unique']);
//    print_r(json_decode(json_encode(new Search()), TRUE));
//    print_r($_sobj->method());
//    echo "</pre>";
