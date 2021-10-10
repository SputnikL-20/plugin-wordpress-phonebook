<?php

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

if (empty($_POST) && !empty(get_user_meta( get_current_user_id(), 'count-view', true ))) { 
    adminPhonebook($obj->upload_post()); 
} 

// if (empty($_POST) && !empty(get_user_meta( get_current_user_id(), 'index', true ))) { 
//     adminPhonebook($obj->upload_sess(VIEW)); 
// } 

// if (empty($_POST) && empty($_SESSION)) {
//     adminPhonebook($obj->upload_sess(VIEW));
// }

if (isset($_POST['prev-skip'])) {
    adminPhonebook($obj->skipback());
}

if (isset($_POST['prev-pagination'])) { 
    adminPhonebook($obj->back());
}

if (isset($_POST['confirm-count-view'])) {
    // add_user_meta( get_current_user_id(), 'count-view', $_POST['count-view'], true );
    update_metadata( 'user', get_current_user_id(), 'count-view', $_POST['count-view'] );
    adminPhonebook($obj->confirm_view());
}

if (isset($_POST['next-pagination'])) { 
    adminPhonebook($obj->next());
}

if (isset($_POST['next-skip'])) {
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
    echo "Данные обновлены";
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
// SELECT `".$wpdb -> prefix."tel_spravochnik`.`otdel` FROM `".$wpdb -> prefix."tel_spravochnik` GROUP BY `".$wpdb -> prefix."tel_spravochnik`.`otdel` ORDER BY `".$wpdb -> prefix."tel_spravochnik`.`otdel` ASC 
// function createTable() {
//   global $wpdb;
//   $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb -> prefix."tel_spravochnik`(\n"
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
// echo "count-view - " . get_user_meta( get_current_user_id(), 'count-view', true ) . "<br>";
// echo "total - " . get_user_meta( get_current_user_id(), 'total', true ) . "<br>";
// echo "index - " . get_user_meta( get_current_user_id(), 'index', true ) . "<br>";
// echo "list - " . get_user_meta( get_current_user_id(), 'list', true ) . "<br>";
// print_r($_POST);
// print_r($_SESSION);
// echo "</pre>";

    // global $wpdb;
    // $result = $wpdb -> get_results("SELECT COUNT(*) AS volume FROM `".$wpdb -> prefix."tel_spravochnik` WHERE 1", ARRAY_A);
    // $volume = $result[0]['volume'];
    // print_r($volume);
//    echo "<pre>";
//    print_r($arr['unique']);
//    print_r(json_decode(json_encode(new Search()), TRUE));
//    print_r($_sobj->method());
//    echo "</pre>";

/**
 * Проверяет роль определенного пользователя.
 * Возвращает true при совпадении.
 *
 * @param строка $role Название роли.
 * @param логический $user_id (не обязательный) ID пользователя, роль которого нужно проверить.
 * @return bool
 */
// function is_user_role($role, $user_id = null) {
// $user = is_numeric($user_id) ? get_userdata($user_id) : wp_get_current_user();
// if (!$user)
//     return false;
//     return in_array($role, (array) $user->roles);
// }

// $user = wp_get_current_user();
// if (is_user_role('administrator', $user->ID)) {
//     echo "У вас есть доступ";
// } else {
//     echo "У вас нет доступа";
// }