<?php
namespace view;

use classes\Dml;
require_once wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/classes/dml.php';

if ($_SERVER['HTTP_REFERER'] === $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/wp-admin/admin.php?page=admin-template') {

    header("Content-type: text/csv"); 
    header("Content-Disposition: attachment; filename=Phonebook.csv"); 
    header("Pragma: no-cache"); 
    header("Expires: 0"); 

    $obj = new Dml;
    $unloading = $obj -> downloadData();

    $buffer = fopen('php://output', 'w'); 
    fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
    for ($i = 0; $i < count($unloading); $i++) { 
        fputcsv($buffer, $unloading[$i], ','); 
    }
    fclose($buffer); 
    exit();
}
?>


<div class="wrap">
  <br>
  <form method="POST" id="f_search"></form>
        <select name="field" form="f_search">
           <option selected value="otdel">Отдел</option>
           <option value="position">Должность</option>
           <option value="fio">ФИО</option>
        </select>
      <input type="text" name="search" form="f_search" placeholder="Поиск по справочнику" />
    <button type="submit" name="ok" form="f_search">Найти</button>
  <button type="submit" name="clear" form="f_search">Весь список</button>

<?php
  if (isset($_POST['ok']) && !empty($_POST['search'])) {
      $array = $wpdb->get_results("SELECT * FROM `".$wpdb -> prefix."tel_spravochnik` 
                                   WHERE `".$_POST['field']."` LIKE '%".$_POST['search']."%'", ARRAY_A);
      if (!empty($array)) {
         foreach ($array as $value) {
            $arr[] =  $value['otdel'];
         }
       $arr = array_unique($arr);
       sort($arr);
         if (count($arr) > 1) {
            foreach ($arr as $value) {
               for ($i = 0; $i < count($array); $i++) {
                  if ($value == $array[$i]['otdel']) {
                     $temp[] = $array[$i];
                  }
               }
               getPhonebook($temp);
               unset($temp);
            }
         } else {
            getPhonebook($array);
         }
      } else {
         print("Извените по вашемо запросу ни чего не найдено, попробуйте повторить поиск.");
      }
  } else {
      $array = $wpdb->get_results("SELECT * FROM `".$wpdb -> prefix."tel_spravochnik` WHERE 1", ARRAY_A);
      foreach ($array as $value) {
         $arr[] =  $value['otdel'];
      }
      $arr = array_unique($arr);
      sort($arr); 
      foreach ($arr as $value) {
        $array = $wpdb->get_results("SELECT * FROM `".$wpdb -> prefix."tel_spravochnik` WHERE otdel = '".$value."'", ARRAY_A);
        getPhonebook($array);
      }
  } 
 

function getPhonebook($array)
{
	?>     
    <details>
      <summary>
        <?= $array[0]['otdel']." ".$array[0]['address'] ?>
      </summary>
      <ul>
       	<?php 
       		for ($i = 0; $i < count($array); $i++) {
            if (is_user_logged_in()) {
               print ("<li><b>Должность:</b> ".$array[$i]['position'].
                         " <b>ФИО:</b> ".$array[$i]['fio'].
                         " <b>Короткий номер:</b> ".$array[$i]['small_number'].
                         " <b>Длинный номер:</b> ".$array[$i]['number'].
                         " <b>Кабинет:</b> ".$array[$i]['room'].
                     "</li>");
            } else {
               print ("<li><b>Должность:</b> ".$array[$i]['position'].
                         " <b>ФИО:</b> ".$array[$i]['fio'].
                         " <b>Длинный номер:</b> ".$array[$i]['number'].
                         " <b>Кабинет:</b> ".$array[$i]['room'].
                     "</li>");
            }
       		}
       	?>
      </ul>           
    </details>
	<?php
}
?>
</div>
<?php
  get_footer();
// echo "<pre>";
// print_r($_POST);
// print_r($_SERVER);
// echo "</pre>";

?>


