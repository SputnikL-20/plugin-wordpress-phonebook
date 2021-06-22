<?php
namespace classes;
require_once 'Connect.php';

class Search extends Connect
{
    public $unique;   

    public function __construct() {
        $this->unique = $this->arrayUniqueKey($this->showFindData(), 'id');  
    }

    public function showFindData()
    {
        // $c = (int) 0;
        $temp = [];
        $field = ['fio', 'otdel', 'position'];
        foreach ($field as $value) {
            $array = $this->queryMySql("SELECT * FROM `wp_tel_spravochnik`
                            WHERE `wp_tel_spravochnik`.`" . $value . "` LIKE '%" . $_POST['search'] . "%'");
            if (! empty($array)) {
                if (count($array) > 1) {
                    for ($i = 0; $i < count($array); $i ++) {
                        $temp[] = $array[$i];
                    }
                } else {
                    $temp = $array;
                }
            } 
            else {
                $c ++;
                // echo $c;
                if ($c > 2) {
                    exit(NOT_FOUND);
                }
            }
        }
        return $temp;
    }

    public function arrayUniqueKey($array, $key)
    {
        // print "Проверка связи";
        $tmp = $key_array = array();
        $i = 0;
        foreach ($array as $val) {
            if (! in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $tmp[$i] = $val;
            }
            $i ++;
        }
        return $tmp;
    }

    function __destruct()
    {}
}

