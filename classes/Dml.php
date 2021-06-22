<?php
namespace classes;

class Dml extends Pagination
{

    public function __construct()
    {}
    
    public function press_insert() 
    {
        $this->insertData();
        $this->dataPaginator($_SESSION['view-'.session_id()]);
    }
    
    public function press_delete()
    {
        $this->deleteData();
        $this->dataPaginator($_SESSION['view-'.session_id()]);
        $this->formPagination();
    }
    
    public function press_update()
    {
        $this->updateData();
    }

    public function updateData()
    {
        global $wpdb;
        $wpdb->update('wp_tel_spravochnik', array(
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
        global $wpdb;
        $wpdb->insert('wp_tel_spravochnik', array( // Вторым параметром у нас идет массив ключей с содержимым:
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
    }

    public function deleteData()
    {
        global $wpdb;
        $wpdb->delete('wp_tel_spravochnik', array(
            'id'            => $_POST['id']
        ), array(
            '%d'
        ));
    }

    public function createTable()
    {
        global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS `wp_tel_spravochnik`(\n" 
            . "    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,\n" 
            . "    `fio` VARCHAR(150) NOT NULL,\n" 
            . "    `otdel` VARCHAR(15) NOT NULL,\n"
            . "    `position` VARCHAR(55) NOT NULL,\n" 
            . "    `number` VARCHAR(11) DEFAULT NULL,\n" 
            . "    `small_number` VARCHAR(7) DEFAULT NULL,\n" 
            . "    `room` VARCHAR(15) NOT NULL,\n" 
            . "    `address` VARCHAR(100) NOT NULL\n" 
            . ") ENGINE = InnoDB DEFAULT CHARSET = utf8";
        $wpdb->query($sql);
    }

    function __destruct()
    {}
}

