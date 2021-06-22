<?php
namespace classes;
require_once 'Connect.php';

class Pagination extends Connect
{
    public function upload_post_sess($args) {
        $this->dataPaginator($args);
        if ($_SESSION['index-'.session_id()] == null) {
            $_SESSION['index-'.session_id()] = (int) 0;
        }
        if ($_SESSION['list-'.session_id()] == null) {
            $_SESSION['list-'.session_id()] = (int) 1;
        }
        $this->formPagination();
        return $this->arrayPaginotion($_SESSION['index-'.session_id()], $_SESSION['view-'.session_id()]);
    }

    public function upload_post() {
        $this->formPagination();
        return $this->arrayPaginotion($_SESSION['index-'.session_id()], $_SESSION['view-'.session_id()]);
    }
    
    public function dataPaginator($view) {
//         if (!$result = $this->queryMySql("SELECT COUNT(*) AS volume FROM `wp_tel_spravochnik` WHERE 1")) {
// /*?*/          createPhonebook();
//             exit("Справочник не обнаружен");
//         }
        $result = $this->queryMySql("SELECT COUNT(*) AS volume FROM `wp_tel_spravochnik` WHERE 1");
        $volume = $result[0]['volume'];
        $_SESSION['total-'.session_id()] = $volume;
        $_SESSION['view-'.session_id()] = $view;
        if ($_SESSION['total-'.session_id()] == $_SESSION['index-'.session_id()]) {
            $_SESSION['index-'.session_id()] -= $view;
            $_SESSION['list-'.session_id()] -= 1;
        }
        // while (($_SESSION['view-'.session_id()] * $_SESSION['list-'.session_id()]) > $_SESSION['total-'.session_id()]) {
            # code...
        // }
        // if (($_SESSION['view-'.session_id()] * $_SESSION['list-'.session_id()]) > $_SESSION['total-'.session_id()]) {
            // $_SESSION['list-'.session_id()] -= 1;
        // }
        // $_SESSION['list-'.session_id()] += 1;
    }

    public function clear_search() { // Очистить поиск
        $this->formPagination();
        return $this->arrayPaginotion($_SESSION['index-'.session_id()], $_SESSION['view-'.session_id()]);
    }
    
    public function arrayPaginotion($index, $view) { // Список контактов (порция)
        $queryNotes = $this->queryMySql("SELECT SQL_CALC_FOUND_ROWS * FROM `wp_tel_spravochnik` LIMIT ".$index.",".$view."");
        return $queryNotes;
    }
    
    public function skipback() { // <<
        $_SESSION['index-'.session_id()] = (int) 0;
        $_SESSION['list-'.session_id()] = (int) 1;
        $this->formPagination();
        return $this->arrayPaginotion($_SESSION['index-'.session_id()], $_SESSION['view-'.session_id()]);
    }
    
    public function back() { // <
        $_SESSION['index-'.session_id()] -= $_SESSION['view-'.session_id()];
        $_SESSION['list-'.session_id()]--;
        $this->formPagination();
        return $this->arrayPaginotion($_SESSION['index-'.session_id()], $_SESSION['view-'.session_id()]);
    }
    
    public function confirm_view() { // Количество контактов на странице
        $_SESSION['view-'.session_id()] = $_POST['count-view'];
        $this->dataPaginator($_POST['count-view']);
        
        // $this->skipback();
        $_SESSION['list-'.session_id()] = (int) 1;
        $_SESSION['index-'.session_id()] = (int) 0;
        $this->formPagination();
        return $this->arrayPaginotion($_SESSION['index-'.session_id()], $_SESSION['view-'.session_id()]);

        // return $this->arrayPaginotion($_SESSION['index-'.session_id()], $_SESSION['view-'.session_id()]);
    }
    
    public function next() { // >
        $_SESSION['index-'.session_id()] += $_SESSION['view-'.session_id()];
        $_SESSION['list-'.session_id()]++;
        $this->formPagination();
        return $this->arrayPaginotion($_SESSION['index-'.session_id()], $_SESSION['view-'.session_id()]);
    }
    
    public function skipnext() { // >>
        $_SESSION['list-'.session_id()] = (int) ceil($_SESSION['total-'.session_id()] / $_SESSION['view-'.session_id()]);
        $_SESSION['index-'.session_id()] = ($_SESSION['list-'.session_id()] * $_SESSION['view-'.session_id()]) - $_SESSION['view-'.session_id()];
        $this->formPagination();
        return $this->arrayPaginotion($_SESSION['index-'.session_id()], $_SESSION['view-'.session_id()]);
    }

    public function formPagination()
    {
        ?>
<form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST"
	id="pagination"></form>
<table style="width: 45%;">
	<tr>
		<td style="width: 10%">
			<button type="submit" name="begin" form="pagination" class="mybtn"
				<?php $_SESSION['list-'.session_id()] == (int) 1 ? print("disabled") : '' ?>>
				<span class="dashicons dashicons-controls-skipback"></span>
			</button>
		</td>
		<td style="width: 5%">
			<button type="submit" name="prev-pagination" form="pagination" class="mybtn"
				<?php $_SESSION['list-'.session_id()] == (int) 1 ? print("disabled") : '' ?>>
				<span class="dashicons dashicons-controls-back"></span>
			</button>
		</td>
		<td style="width: 6%; text-align: center; font-weight: bold;">List:</td>
		<td style="width: 7%"><input type="number" name="count-view"
			form="pagination" value="<?= $_SESSION['view-'.session_id()] ?>" /></td>
		<td style="width: 5%">
			<button type="submit" name="confirm-count-view" form="pagination" class="mybtn">
				<span class="dashicons dashicons-editor-break"></span>
			</button>
		</td>
		<td style="width: 7%; text-align: center; font-weight: bold;">
                <?= $_SESSION['list-'.session_id()] ?><?= " is " ?><?= (int) ceil($_SESSION['total-'.session_id()] / $_SESSION['view-'.session_id()]) ?>
              </td>
		<td style="width: 5%;">
			<button type="submit" name="next-pagination" form="pagination"
				class="mybtn"
				<?php $_SESSION['list-'.session_id()] == (int) ceil($_SESSION['total-'.session_id()] / $_SESSION['view-'.session_id()]) ? print("disabled") : '' ?>>
				<span class="dashicons dashicons-controls-play"></span>
			</button>
		</td>
		<td style="width: 10%;">
			<button type="submit" name="end" form="pagination" class="mybtn"
				<?php $_SESSION['list-'.session_id()] == (int) ceil($_SESSION['total-'.session_id()] / $_SESSION['view-'.session_id()]) ? print("disabled") : '' ?>>
				<span class="dashicons dashicons-controls-forward"></span>
			</button>
		</td>
	</tr>
</table>
<?php
    }

    function __destruct()
    {}
}

