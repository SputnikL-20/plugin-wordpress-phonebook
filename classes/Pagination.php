<?php
namespace classes;

class Pagination
{
    public $index;
    public $total;
    public $view;
    public $list;

    private $ID;

    public function __construct () {
        $this -> ID = get_current_user_id();
    }

    public function upload_post() {
        $args = get_user_meta( $this -> ID, 'count-view', true );
        $this->dataPaginator($args);   
        return $this->viewPagination();       
    }
    
    public function dataPaginator($view) {
        global $wpdb;
        $result = $wpdb -> get_results("SELECT COUNT(*) AS volume FROM `".$wpdb -> prefix."tel_spravochnik` WHERE 1", ARRAY_A);
        $total = $result[0]['volume'];

        update_metadata( 'user', $this -> ID, 'total', $total );
        update_metadata( 'user', $this -> ID, 'count-view', $view );

        if (get_user_meta( $this -> ID, 'total', true ) === get_user_meta( $this -> ID, 'index', true )) 
        {
            if (get_user_meta( $this -> ID, 'index', true ) != 0) 
            {
                update_metadata('user', $this -> ID, 'index', ($view - get_user_meta($this -> ID, 'index', true)));
            } 
            if (get_user_meta( $this -> ID, 'list', true ) > 1) 
            {
                update_metadata('user', $this -> ID, 'list', (get_user_meta( $this -> ID, 'list', true ) - 1));
            }
        }
    }

    public function viewPagination() {
        $this -> index = get_user_meta( $this -> ID, 'index', true );
        $this -> total = get_user_meta( $this -> ID, 'total', true );
        $this -> view  = get_user_meta( $this -> ID, 'count-view', true );
        $this -> list  = get_user_meta( $this -> ID, 'list', true );
        return $this -> arrayPaginotion( $this -> index, $this -> view );
    }
    
    public function arrayPaginotion($index, $view) { // Список контактов (порция)
        global $wpdb;
        $queryNotes = $wpdb -> get_results("SELECT SQL_CALC_FOUND_ROWS * FROM `".$wpdb -> prefix."tel_spravochnik` LIMIT " . $index . "," . $view . "", ARRAY_A);
        return $queryNotes;
    }
    
    public function skipback() { // <<
        update_metadata( 'user', $this -> ID, 'index', 0 );
        update_metadata( 'user', $this -> ID, 'list', 1 );

        return $this->viewPagination();
    }
    
    public function back() { // <
        update_metadata('user', $this -> ID, 'index', (get_user_meta( $this -> ID, 'index', true ) - get_user_meta( $this -> ID, 'count-view', true )));
        update_metadata('user', $this -> ID, 'list', (get_user_meta( $this -> ID, 'list', true ) - 1));

        return $this->viewPagination();
    }
    
    public function confirm_view() { // Количество контактов на странице
        $this->dataPaginator($_POST['count-view']);
        update_metadata( 'user', $this -> ID, 'index', 0 );
        update_metadata( 'user', $this -> ID, 'list', 1 );
        return $this->viewPagination();
    }
    
    public function next() { // >
        update_metadata('user', $this -> ID, 'index', (get_user_meta($this -> ID, 'index', true) + get_user_meta($this -> ID, 'count-view', true)));
        update_metadata( 'user', $this -> ID, 'list', (get_user_meta( $this -> ID, 'list', true ) + 1));
        return $this->viewPagination();
    }


    public function skipnext() { // >>
        update_metadata( 'user', $this -> ID, 'list', (
            (int) ceil(get_user_meta($this -> ID, 'total', true) / get_user_meta($this -> ID, 'count-view', true))
        ));

        update_metadata( 'user', $this -> ID, 'index', (
         get_user_meta($this -> ID, 'list', true) * get_user_meta($this -> ID, 'count-view', true) - get_user_meta($this -> ID, 'count-view', true)
        ));
        return $this->viewPagination();
    }

    // function __destruct()
    // {}
}

