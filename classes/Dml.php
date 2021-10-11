<?php
namespace classes;

require_once wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/classes/pagination.php';

class Dml extends Pagination
{

    // public function __construct()
    // {}
    
    public function press_update()
    {
        $this->updateData();
    }

    public function press_insert() 
    {
        $this->insertData();
        $this->dataPaginator(get_user_meta( get_current_user_id(), 'count-view', true ));
    }
    
    public function press_delete()
    {
        $this->deleteData();
        $this->dataPaginator(get_user_meta( get_current_user_id(), 'count-view', true ));
    }

    public function updateData()
    {
        global $wpdb;
        $wpdb->update($wpdb -> prefix.'tel_spravochnik', array(
            'fio'           => $_POST['fio'],
            'otdel'         => $_POST['otdel'],
            'position'      => $_POST['position'],
            'number'        => $_POST['number'],
            'small_number'  => $_POST['small_number'],
            'room'          => $_POST['room'],
            'address'       => $_POST['address']
        ), array(
            'id'            => $_POST['id']
        ), array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        ), array(
            '%d'
        ) // массив форматов данных полей в массиве «$where»
        );
    }

    public function insertData()
    {
        if (!empty($_POST['fio'])) {
        global $wpdb;
            $wpdb->insert($wpdb -> prefix.'tel_spravochnik', array( // Вторым параметром у нас идет массив ключей с содержимым:
                'fio'           => $_POST['fio'],
                'otdel'         => $_POST['otdel'],
                'position'      => $_POST['position'],
                'number'        => $_POST['number'],
                'small_number'  => $_POST['small_number'],
                'room'          => $_POST['room'],
                'address'       => $_POST['address']
            ), array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            ) // Последним параметром идет фильтр значений: где: %s — строка, %d — число
            );
        } else {
            exit("Поля не должны быть пустыми...");
        }
    }

    public function deleteData()
    {
        global $wpdb;
        $wpdb->delete($wpdb -> prefix.'tel_spravochnik', array(
            'id'            => $_POST['id']
        ), array(
            '%d'
        ));
    }

    public function downloadData()
    {
        global $wpdb;
        $result = $wpdb -> get_results("SELECT * FROM `".$wpdb -> prefix."tel_spravochnik` ORDER BY `fio` ASC", ARRAY_A);
        return $result;
    }

    // function __destruct()
    // {}
}

