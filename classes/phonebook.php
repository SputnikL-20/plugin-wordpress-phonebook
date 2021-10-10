<?php
namespace classes;

require_once wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/classes/dml.php';

/**
 * 
 */
class Phonebook extends Dml
{
    
    // function __construct(argument)
    // {
    //     // code...
    // }

    public function getUploadPost()
    {
        if (empty($_POST) && !empty(get_user_meta( get_current_user_id(), 'count-view', true ))) { 
            return $this -> upload_post(); 
        }

        if (isset($_POST['prev-skip'])) {
            return $this -> skipback();
        }

        if (isset($_POST['prev-pagination'])) { 
            return $this -> back();
        }

        if (isset($_POST['confirm-count-view'])) {
            update_metadata( 'user', get_current_user_id(), 'count-view', $_POST['count-view'] );
            return $this -> confirm_view();
        }

        if (isset($_POST['next-pagination'])) { 
            return $this -> next();
        }

        if (isset($_POST['next-skip'])) {
            return $this -> skipnext();
        }

        if (isset($_POST['ok']) && !empty($_POST['search'])) {
            $arr = (array) new Search();
            return array_values($arr['unique']);
        }

        if (isset($_POST['clear'])) { // button 'Очистить' (поиск)
            return $this -> viewPagination();
        }

        if (isset($_POST['create'])) { // Вызов формы для создания контакта
            return;
        }

        if (isset($_POST['insert'])) {
            $this -> press_insert();
            return $this -> skipnext();
        }

        if (isset($_POST['update'])) {
            $this -> press_update();
            return $this -> viewPagination();
        }

        if (isset($_POST['delete'])) {
            $this -> press_delete();
            return $this -> viewPagination();
        }
    }
}

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