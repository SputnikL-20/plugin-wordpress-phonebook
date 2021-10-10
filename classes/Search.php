<?php
namespace classes;

class Search
{
    public $unique;   

    public function __construct() {
        $this->unique = $this->arrayUniqueKey($this->showFindData(), 'id');  
    }

    public function showFindData()
    {
        global $wpdb;
        $temp = [];
        $c = 0;
        $field = ['fio', 'otdel', 'position'];
        foreach ($field as $value) {
            $array = $wpdb -> get_results("SELECT * FROM `".$wpdb -> prefix."tel_spravochnik`
                            WHERE `".$wpdb -> prefix."tel_spravochnik`.`" . $value . "` LIKE '%" . $_POST['search'] . "%'", ARRAY_A);
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
                if ($c > 2) {
                    exit('Извените по вашемо запросу ни чего не найдено, попробуйте повторить поиск.');
                }
            }
        }
        return $temp;
    }

    public function arrayUniqueKey($array, $key)
    {
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

