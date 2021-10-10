<?php
namespace classes;

class Pagination
{

    public function upload_post() {
        $args = get_user_meta( get_current_user_id(), 'count-view', true );;
        $this->dataPaginator($args);   
        return $this->viewPagination();       
    }
    
    public function dataPaginator($view) {
        global $wpdb;
        $result = $wpdb -> get_results("SELECT COUNT(*) AS volume FROM `".$wpdb -> prefix."tel_spravochnik` WHERE 1", ARRAY_A);
        $volume = $result[0]['volume'];
        update_metadata( 'user', get_current_user_id(), 'total', $volume );

        update_metadata( 'user', get_current_user_id(), 'count-view', $view );

        if (get_user_meta( get_current_user_id(), 'total', true ) == get_user_meta( get_current_user_id(), 'index', true )) {
            if (get_user_meta( get_current_user_id(), 'index', true ) != 0) {
                update_metadata( 'user', get_current_user_id(), 'index', ( $view - get_user_meta( get_current_user_id(), 'index', true ) ) );
                
            } else {
                get_user_meta( get_current_user_id(), 'index', true );
            }
            if (get_user_meta( get_current_user_id(), 'list', true ) >= 1) {
                update_metadata( 'user', get_current_user_id(), 'list', (
                    get_user_meta( get_current_user_id(), 'list', true ) - 1
                ) );
                
            }
        }
    }

    public function viewPagination() {
        // $this->formPagination();
        return $this->arrayPaginotion( get_user_meta( get_current_user_id(), 'index', true ), get_user_meta( get_current_user_id(), 'count-view', true ) );
    }
    
    public function arrayPaginotion($index, $view) { // Список контактов (порция)
        global $wpdb;
        $queryNotes = $wpdb -> get_results("SELECT SQL_CALC_FOUND_ROWS * FROM `".$wpdb -> prefix."tel_spravochnik` LIMIT ".$index.",".$view."", ARRAY_A);
        return $queryNotes;
    }
    
    public function skipback() { // <<

        update_metadata( 'user', get_current_user_id(), 'index', 0 );
        update_metadata( 'user', get_current_user_id(), 'list', 1 );

        return $this->viewPagination();
    }
    
    public function back() { // <
        update_metadata( 'user', get_current_user_id(), 'index', (
            get_user_meta( get_current_user_id(), 'index', true ) - get_user_meta( get_current_user_id(), 'count-view', true )
        ) );
        update_metadata( 'user', get_current_user_id(), 'list', (
            get_user_meta( get_current_user_id(), 'list', true ) - 1
        ) );

        return $this->viewPagination();
    }
    
    public function confirm_view() { // Количество контактов на странице

        $this->dataPaginator($_POST['count-view']);
 
        update_metadata( 'user', get_current_user_id(), 'index', 0 );

        update_metadata( 'user', get_current_user_id(), 'list', 1 );
        return $this->viewPagination();
    }
    
    public function next() { // >
        update_metadata( 'user', get_current_user_id(), 'index', (get_user_meta( get_current_user_id(), 'index', true ) + get_user_meta( get_current_user_id(), 'count-view', true )) );

        update_metadata( 'user', get_current_user_id(), 'list', (
                    get_user_meta( get_current_user_id(), 'list', true ) + 1
        ) );
        return $this->viewPagination();
    }


    public function skipnext() { // >>
        update_metadata( 'user', get_current_user_id(), 'list', (
            (int) ceil(get_user_meta( get_current_user_id(), 'total', true ) / get_user_meta( get_current_user_id(), 'count-view', true ))
        ));

        update_metadata( 'user', get_current_user_id(), 'index', (
         get_user_meta( get_current_user_id(), 'list', true ) * get_user_meta( get_current_user_id(), 'count-view', true ) - get_user_meta( get_current_user_id(), 'count-view', true )
        ));
        return $this->viewPagination();
    }

    function __destruct()
    {}
}

