<?php
/*
 * Plugin Name: Phonebook
 * Description: Телефонный справочник в виде плагина WORDPRESS
 * Author:      Голиков Сергей Сергеевич
 * Version:     Версия плагина 1.2
 *
 */


// фильтр передает переменную $template - путь до файла шаблона.
// Изменяя этот путь мы изменяем файл шаблона.
add_filter( 'template_include', 'phonebook_site_template' );
function phonebook_site_template( $template = null ) 
{
	# шаблон для записи по ID
	// файл шаблона расположен в папке плагина /plugin-wordpress-phonebook/view/page-template.php
	if( is_page('spravochnik') ){
		return wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/view/page-template.php';
	}
	if( is_page('inc-template') ){
		return wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/view/inc-template.php';
	}
	return $template;
}

// add_action('init', 'do_output_buffer');
// function do_output_buffer() {
//         ob_start();
// }

/**
 * Добавляем новое меню в Админ Консоль
 */

// Хук событие 'admin_menu', запуск функции 'fun_reg_admin_menu_phonebook()'
add_action( 'admin_menu', 'fun_reg_admin_menu_phonebook' );
// Добавляем новую ссылку в меню Админ Консоли
function fun_reg_admin_menu_phonebook()
{
	add_menu_page('Edit Phonebook', // Название страниц (Title)
				  'Phonebook', // Текст ссылки в меню
 				  'manage_options', // права пользователя, необходимые для доступа к странице
 				  'admin-template', // 'slug' - файл отобразится по нажатию на ссылку
 				  'page_admin_template', // функция, которая выводит содержимое страницы
 				  'dashicons-phone' // иконка, в данном случае из Dashicons
 				 );
}

function page_admin_template() {
	include wp_normalize_path( WP_PLUGIN_DIR ) . '/plugin-wordpress-phonebook/view/admin-template.php';
}

/**
 * Создание таблицы при активации плагина
 */ 
register_activation_hook( __FILE__, 'create_table_tel_spravochnik' );
function create_table_tel_spravochnik() {
	global $wpdb;
	$sql = "CREATE TABLE IF NOT EXISTS `".$wpdb -> prefix."tel_spravochnik`(\n"
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

/**
 * Добавление метаданных пользователю для навигации по справочнику
 */ 
register_activation_hook( __FILE__, 'addDataUsermeta' );
function addDataUsermeta()
{
    global $wpdb;
    $result = $wpdb -> get_results("SELECT COUNT(*) AS volume FROM `".$wpdb -> prefix."tel_spravochnik` WHERE 1", ARRAY_A);
    $volume = $result[0]['volume'];
    update_metadata( 'user', get_current_user_id(), 'index', 0 );
    update_metadata( 'user', get_current_user_id(), 'list', 1 );
    update_metadata( 'user', get_current_user_id(), 'total', $volume );
    update_metadata( 'user', get_current_user_id(), 'count-view', 10 );
}


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