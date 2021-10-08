<?php
namespace classes;
require_once 'Connect.php';

class Pagination extends Connect
{
    public function upload_sess($args) {
        update_metadata( 'user', get_current_user_id(), 'index', 0 );
        update_metadata( 'user', get_current_user_id(), 'list', 1 );
        $this->dataPaginator($args);   
        return $this->viewPagination();
    }

    public function upload_post() {
        $args = get_user_meta( get_current_user_id(), 'count-view', true );;
        $this->dataPaginator($args);   
        return $this->viewPagination();       
    }
    
    public function dataPaginator($view) {
        global $wpdb;
//         if (!$result = $this->queryMySql("SELECT COUNT(*) AS volume FROM `wp_tel_spravochnik` WHERE 1")) {
// /*?*/          createPhonebook();
//             exit("Справочник не обнаружен");
//         }
        $result = $this->queryMySql("SELECT COUNT(*) AS volume FROM `".$wpdb -> prefix."_tel_spravochnik` WHERE 1");
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
        // $this->dataPaginator($_SESSION['view-'.session_id()]); // С этой приблудой прыгает пагинация
        $this->formPagination();
        return $this->arrayPaginotion( get_user_meta( get_current_user_id(), 'index', true ), get_user_meta( get_current_user_id(), 'count-view', true ) );
    }
    
    public function arrayPaginotion($index, $view) { // Список контактов (порция)
        global $wpdb;
        $queryNotes = $this->queryMySql("SELECT SQL_CALC_FOUND_ROWS * FROM `".$wpdb -> prefix."_tel_spravochnik` LIMIT ".$index.",".$view."");
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

    public function formPagination()
    {
        ?>
<form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
<table style="width: 45%;">
	<tr>
		<td style="width: 10%">
			<button type="submit" name="prev-skip" class="mybtn" 

                <?php get_user_meta( get_current_user_id(), 'list', true ) <= (int) 1 ? print("disabled") : '' ?>>

                <?php $_SESSION['list-'.session_id()] <= (int) 1 ? print("disabled") : '' ?>>

				<span class="dashicons dashicons-controls-skipback"></span>
			</button>
		</td>
		<td style="width: 5%">
			<button type="submit" name="prev-pagination" class="mybtn"
				<?php get_user_meta( get_current_user_id(), 'list', true ) <= (int) 1 ? print("disabled") : '' ?>>
				<span class="dashicons dashicons-controls-back"></span>
			</button>
		</td>
		<td style="width: 6%; text-align: center; font-weight: bold;">View:</td>
		<td style="width: 7%"><input type="number" min="1" name="count-view"
			value="<?= get_user_meta( get_current_user_id(), 'count-view', true ); ?>" />
        </td>
		<td style="width: 5%">
			<button type="submit" name="confirm-count-view" class="mybtn" 
                <?php get_user_meta( get_current_user_id(), 'total', true ) == 0 ? print("disabled") : '' ?>>
                <span class="dashicons dashicons-editor-break"></span>
			</button>
		</td>
		<td style="width: 8%; text-align: center; font-weight: bold;"><?= "List: " ?>
            <?= get_user_meta( get_current_user_id(), 'list', true ) ?><?= " of " ?>
            <?= (int) ceil(get_user_meta( get_current_user_id(), 'total', true ) / get_user_meta( get_current_user_id(), 'count-view', true )) ?>
        </td>
		<td style="width: 5%;">
			<button type="submit" name="next-pagination" class="mybtn"
				<?php ( (int) get_user_meta( get_current_user_id(), 'list', true ) === (int) ceil(get_user_meta( get_current_user_id(), 'total', true ) / get_user_meta( get_current_user_id(), 'count-view', true ))) ? print("disabled") : '' ?>>
				<span class="dashicons dashicons-controls-play"></span>
			</button>
		</td>
		<td style="width: 10%;">
			<button type="submit" name="next-skip" class="mybtn"

				<?php ( (int) get_user_meta( get_current_user_id(), 'list', true ) === (int) ceil(get_user_meta( get_current_user_id(), 'total', true ) / get_user_meta( get_current_user_id(), 'count-view', true ))) ? print("disabled") : '' ?>>

				<?php $_SESSION['list-'.session_id()] === (int) ceil($_SESSION['total-'.session_id()] / $_SESSION['view-'.session_id()]) ? print("disabled") : '' ?>>

				<span class="dashicons dashicons-controls-forward"></span>
			</button>
		</td>
	</tr>
</table>
</form>
<?php
    }

    function __destruct()
    {}
}

