<?php
namespace inc;
// session_start();

?>

<style type="text/css">
body {
	margin-right: 1.5%;
}
table {
  font-size:8pt;
  width:100%;
  border-collapse: collapse;
}
input[type=text], [type=search] {
	/*padding: 2pt 2pt 2pt 2pt;*/
	margin: 0 auto;
    width:100%;
}
input[type=number] {
	/*padding: 2pt 2pt 2pt 2pt;*/
  /*border: none;*/
	margin: 0 auto;
    width:100%;
}
button[type=submit] {
	width:100%;

	padding:2pt;
	margin: 0 auto;
	cursor:pointer;
}
/*.mybtn{
  text-align: center; 
  padding: 8px 10px; 
  border: solid 1px #777777; 
  color: #7777777; 
  background:#ffffff;
  tansition: all .3s linear;
  -webkit-transition: all .3s linear;
  -moz-transition: all .3s linear;  
  text-transform: uppercase;
}*/
.mybtn:hover {
  color: #fff;
  background: #0074a2;
  tansition: all 0s linear;
  -webkit-transition: all 0s linear;
  -moz-transition: all 0s linear; 
}
.btnedit:hover {
  color: #fff;
  background: #FF8C00;
  tansition: all .3s linear;
  -webkit-transition: all .3s linear;
  -moz-transition: all .3s linear; 
}
.btndel:hover {
  color: #fff;
  background: red;
  tansition: all .3s linear;
  -webkit-transition: all .3s linear;
  -moz-transition: all .3s linear; 
}
.btnadd:hover {
  color: #fff;
  background: green;
  tansition: all .3s linear;
  -webkit-transition: all .3s linear;
  -moz-transition: all .3s linear; 
}

</style>

<?php

function createPhonebook() {
?>
	<form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST" id="f_table">
		<button style="width: 100%" type="submit" name="create-table" form="f_table">Создать справочник</button>
	</form>
<?php	
}

function searchForm() {
    
?>
	<form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST" id="f_search"></form>
	<form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST" id="f_create"></form>
	<table>
		<tr>
			<td>
				<input type="search" name="search" form="f_search" placeholder="Поиск по справочнику" />
			</td>
			<td style="width: 10%">
				<button type="submit" name="ok" form="f_search"><span class="dashicons dashicons-search"></span></button>
			</td>
			<td style="width: 10%">
				<button type="submit" name="clear" form="f_search"><span class="dashicons dashicons-editor-justify"></span></button>
			</td>
			<td style="width: 10%">
				<button type="submit" name="create" form="f_create"><span class="dashicons dashicons-plus-alt"></span></button>
			</td>
		</tr>
	</table>

<?php
}



function adminPhonebook($array = null) {

  if (!empty($array)) {
  ?>
  	<table>
		<tr>
			<th>№</th>
			<th>ФИО</th>
			<th style="width: 10%">Отдел</th>
			<th>Должность</th>
			<th style="width: 9%">Длинный номер</th>
			<th style="width: 7%">Короткий номер</th>
			<th style="width: 7%">Кабинет</th>
			<th>Адрес</th>
			<th colspan="2" style="width: 5%">Обновить / Удалить</th>
		</tr>	
	<?php
	$j = 1; 
    for ($i = 0; $i < count($array); $i++) {
  	?>
  	
  	<tr>
  		<td style="text-align:center;font-weight:bold;">
  			<?= $j++ ?>
  		</td>
  		<td>
  		<form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST" id="<?= $i ?>"></form>
  			<input type="hidden" name="id" form="<?= $i ?>" value="<?= $array[$i]['id'] ?>" />
  			<input type="text" name="fio" form="<?= $i ?>" 
  					placeholder="ФИО" title="<?= $array[$i]['fio'] ?>" value="<?= $array[$i]['fio'] ?>" />
  		</td>
  		<td>
  			<input type="text" name="otdel" form="<?= $i ?>" 
  					placeholder="Отдел" value="<?= $array[$i]['otdel'] ?>" />
  		</td>
  		<td>
  			<input type="text" name="position" form="<?= $i ?>" 
  					placeholder="Должность" value="<?= $array[$i]['position'] ?>" />
  		</td>
  		<td>
  			<input type="tel" name="number" form="<?= $i ?>" 
  					placeholder="Длинный номер" maxlength=11 value="<?= $array[$i]['number'] ?>" />
  		</td>
  		<td>
  			<input type="text" name="small_number" form="<?= $i ?>" 
  					placeholder="Короткий номер" maxlength=7 value="<?= $array[$i]['small_number'] ?>" />
  		</td>
  		<td>
  			<input type="text" name="room" form="<?= $i ?>" 
  					placeholder="Кабинет" maxlength=15 value="<?= $array[$i]['room'] ?>" />
  		</td>
  		<td>
  			<input type="text" name="address" form="<?= $i ?>" 
  					placeholder="Адрес" maxlength=100 title="<?= $array[$i]['address'] ?>" value="<?= $array[$i]['address'] ?>" />
  		</td>
  		<td>
  			<button type="submit" name="update" form="<?= $i ?>" class="btnedit"><span class="dashicons dashicons-update"></span></button>
  		</td>
  		<td>
  			<button type="submit" name="delete" form="<?= $i ?>" class="btndel"><span class="dashicons dashicons-trash"></span></button>
  		</td>
  	</tr>
  <?php } ?>
  	</table>
  	<?php 
  } else {
  ?>
  <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST" id="f_add"></form>
	<table>
		<tr>
			<th>ФИО</th>
			<th style="width: 10%">Отдел</th>
			<th>Должность</th>
			<th style="width: 9%">Длинный номер</th>
			<th style="width: 7%">Короткий номер</th>
			<th style="width: 7%">Кабинет</th>
			<th>Адрес</th>
			<th colspan="2" style="width: 5%">Execute</th>
		</tr>	
		<tr>
			<td>
			  <input type="text" name="fio" form="f_add" placeholder="ФИО" />
			</td>
			<td>
			  <input type="text" name="otdel" form="f_add" placeholder="Отдел" maxlength=15 />
			</td>
			<td>
			  <input type="text" name="position" form="f_add" placeholder="Должность" />
			</td>
			<td>
			  <input type="tel" name="number" form="f_add" placeholder="Длинный номер" maxlength=11 />
			</td>
			<td>
			  <input type="text" name="small_number" form="f_add" placeholder="Короткий номер" maxlength=7 />
			</td>
			<td>
			  <input type="text" name="room" form="f_add" placeholder="Кабинет" maxlength=15 />
			</td>
			<td>
			  <input type="text" name="address" form="f_add" placeholder="Адрес" maxlength=100 />
			</td>
			<td colspan="2">
			  <button type="submit" name="insert" form="f_add" class="btnadd"><span class="dashicons dashicons-yes"></span></button>
			</td>
		</tr>  
    </table>  
    <?php
  }
}