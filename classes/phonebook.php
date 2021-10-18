<?php
namespace classes;

require_once wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/classes/dml.php';

/**
 * Контроллер событий
 */
class Phonebook extends Dml
{
    
    // function __construct(argument)
    // {
    //     // code...
    // }

    public function getActionPost()
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
            if (!empty(array_values($arr['unique']))) {
                return array_values($arr['unique']);
            }
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

        if (isset($_POST['upload'])) {
            if (file_exists($_FILES['file-csv']['tmp_name'])) {
                if (($handle = fopen($_FILES['file-csv']['tmp_name'], "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        for ($c = 1; $c < count($data); $c++) {
                            $_POST['fio']          = $data[$c];
                            $_POST['otdel']        = $data[++$c];
                            $_POST['position']     = $data[++$c];
                            $_POST['number']       = $data[++$c];
                            $_POST['small_number'] = $data[++$c];
                            $_POST['room']         = $data[++$c];
                            $_POST['address']      = $data[++$c];
                        }
                        $this -> insertData();
                    }
                    fclose($handle);
                }
                return $this -> upload_post(); 
            }   
            echo 'Невозможно открыть CSV-файл!';            
        }

        if (isset($_POST['download'])) {
            return $this -> upload_post(); 
        //     echo "Данные выгружены!";
            // return $this -> skipnext();
            // echo "<pre>";
            // print_r($this -> downloadData());
            // echo "</pre>";
            // $prods = $this -> downloadData();
            // $_POST['var'] = $this -> downloadData();

// header("Content-type: text/csv"); 
// header("Content-Disposition: attachment; filename=file.csv"); 
// header("Pragma: no-cache"); 
// header("Expires: 0"); 
//             // $buffer = fopen('php://output', 'w'); 
//             $buffer = fopen( wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/unloading/Phonebook.csv', 'r'); 
//             // $buffer = fopen( __DIR__ . '/Phonebook.csv', 'w'); 
//             // fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
//             for ($i = 0; $i < count($prods); $i++) { 
//                 fputcsv($buffer, $prods[$i], ','); 
//             }
//             fclose($buffer); 

// header('Location: '. include wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/view/inc-template.php');
// header('Location: http://test.wordpress.loc/spravochnik/');

// Имя скачиваемого файла
// $file = __DIR__ . '/Phonebook.csv';

// // // Контент-тип означающий скачивание
// // Контент-тип означающий скачивание
// header("Content-Type: application/octet-stream");

// // // Размер в байтах
// header("Accept-Ranges: bytes");

// // // Размер файла
// header("Content-Length: ".filesize($file));

// // // Расположение скачиваемого файла
// header("Content-Disposition: attachment; filename=" . $file);  

// // // Прочитать файл
// readfile($file);

            // include wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/unloading/Phonebook.csv';
// header("Content-type: text/csv"); 
// header("Content-Disposition: attachment; filename=file.csv"); 
// header("Pragma: no-cache"); 
// header("Expires: 0"); 

// $buffer = fopen('php://output', 'w'); 
// fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
// foreach($prods as $val) { 
//     fputcsv($buffer, $val, ';'); 

// } 
// fclose($buffer); 

// exit();
            // exit();
            // require wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/classes/download.php';
            // echo "<script> window.location='spravochnik'; </script> ";
            // return $this -> upload_post(); 
            // $_POST['var'] = $this -> downloadData();
            // $prods = $this -> downloadData();
            // require wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/classes/download.php';
            // header("Content-type: text/csv"); 
            // header("Content-Disposition: attachment; filename=file.csv"); 
            // header("Pragma: no-cache"); 
            // header("Expires: 0"); 

            // $buffer = fopen('php://output', 'w'); 
            // // fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
            // for ($i = 0; $i < count($prods); $i++) { 
            //     // foreach($prods[$i] as $val) { 
            //         fputcsv($buffer, $prods[$i], ','); 
            //     // } 
            // }
            // fclose($buffer); 
            // exit();

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