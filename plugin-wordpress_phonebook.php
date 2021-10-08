<?php
/*
 * Plugin Name: Phonebook
 * Description: OOP
 * Author:      Голиков Сергей Сергеевич
 * Version:     Версия плагина 1.1
 *
 */


//добавление использования сессий в нашем шаблоне
// add_action( 'init', 'do_session_start' ); 
// function do_session_start() { 
//     if ( !session_id() ) session_start(); 
// }

// фильтр передает переменную $template - путь до файла шаблона.
// Изменяя этот путь мы изменяем файл шаблона.
add_filter( 'template_include', 'phonebook_template' );
function phonebook_template( $template = null ) 
{
	# шаблон для записи по ID
	// файл шаблона расположен в папке плагина /my-plugin/site-template.php
	// global $post;
	if( is_page('spravochnik') ){
		return wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress_phonebook/inc/page-template.php';
	}
	return $template;
}


/*
 * Добавляем новое меню в Админ Консоль
 */
 
// Хук событие 'admin_menu', запуск функции 'mfp_Add_My_Admin_Link()'
add_action( 'admin_menu', 'mfp_Add_My_Admin_Link' );
// Добавляем новую ссылку в меню Админ Консоли
function mfp_Add_My_Admin_Link()
{
	add_menu_page('Edit Phonebook', // Название страниц (Title)
				  'Phonebook', // Текст ссылки в меню
 				  'manage_options', // Требование к возможности видеть ссылку
 				  'plugin-wordpress_phonebook/admin-phonebook.php' // 'slug' - файл отобразится по нажатию на ссылку
 				 );
}

## Создание таблицы при активации плагина
register_activation_hook( __FILE__, 'create_table_tel_spravochnik' );
function create_table_tel_spravochnik() {
	global $wpdb;
	$sql = "CREATE TABLE IF NOT EXISTS `".$wpdb -> prefix."_tel_spravochnik`(\n"
	  . "    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,\n"
	  . "    `fio` VARCHAR(150) NOT NULL,\n"
	  . "    `otdel` VARCHAR(15) NOT NULL,\n"
	  . "    `position` VARCHAR(55) NOT NULL,\n"
	  . "    `number` VARCHAR(11) DEFAULT NULL,\n"
	  . "    `small_number` VARCHAR(7) DEFAULT NULL,\n"
	  . "    `room` VARCHAR(15) NOT NULL,\n"
	  . "    `address` VARCHAR(100) NOT NULL\n"
	  . ") {$wpdb -> get_charset_collate()};";
	$wpdb->query($sql);
}

## Какой шаблон используется в текущий момент
// add_filter( 'template_include', 'echo_cur_tplfile', 99 );
// function echo_cur_tplfile( $template ){

// 	echo '<span style="color:red">'. wp_basename( $template ) .'</span>';

// 	return $template; 
// }




// register_activation_hook(__FILE__, 'plg_table_activation');
// function plg_table_activation()
// {
// 	global $wpdb;
	
// 	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
// 	dbDelta("CREATE TABLE IF NOT EXISTS `".$wpdb -> prefix."plg_table_demo` (
// 		`id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
// 		`field_one` VARCHAR(255) NOT NULL,
// 		`field_two` VARCHAR(255) NOT NULL,
// 		`date_create` INT(10) UNSIGNED NOT NULL
// 	) {$wpdb -> get_charset_collate()};");
	
// 	return true;
// };

// register_uninstall_hook(__FILE__, 'plg_table_uninstall');
// function plg_table_uninstall()
// {
// 	global $wpdb;
	
// 	$wpdb -> query("DROP TABLE IF EXISTS `" . $wpdb -> prefix . "plg_table_demo`");

// 	return true;
// }