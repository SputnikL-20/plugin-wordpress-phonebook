<?php
namespace classes;
require_once 'Connect.php';

class Pagination extends Connect
{
    public function upload_sess($args) {
        $_SESSION['index-'.session_id()] = (int) 0; 
        $_SESSION['list-'.session_id()] = (int) 1; 
        $this->dataPaginator($args);   
        return $this->viewPagination();
    }

    public function upload_post() {
        $args = $_SESSION['view-'.session_id()];
        $this->dataPaginator($args);   
        return $this->viewPagination();       
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
            if ($_SESSION['index-'.session_id()] != 0) {
                $_SESSION['index-'.session_id()] -= $view;
            } else {
                $_SESSION['index-'.session_id()];
            }
            if ($_SESSION['list-'.session_id()] >= 1) {
                $_SESSION['list-'.session_id()] -= 1;
            }
        }
    }

    public function viewPagination() {
        // $this->dataPaginator($_SESSION['view-'.session_id()]); // С этой приблудой прыгает пагинация
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
        return $this->viewPagination();
    }
    
    public function back() { // <
        $_SESSION['index-'.session_id()] -= $_SESSION['view-'.session_id()];
        $_SESSION['list-'.session_id()]--;
        return $this->viewPagination();
    }
    
    public function confirm_view() { // Количество контактов на странице
        $_SESSION['view-'.session_id()] = $_POST['count-view'];
        $this->dataPaginator($_POST['count-view']);
        $_SESSION['index-'.session_id()] = (int) 0;
        $_SESSION['list-'.session_id()] = (int) 1;
        return $this->viewPagination();
    }
    
    public function next() { // >
        $_SESSION['index-'.session_id()] += $_SESSION['view-'.session_id()];
        $_SESSION['list-'.session_id()]++;
        return $this->viewPagination();
    }
    
    public function skipnext() { // >>
        $_SESSION['list-'.session_id()] = (int) ceil($_SESSION['total-'.session_id()] / $_SESSION['view-'.session_id()]);
        $_SESSION['index-'.session_id()] = ($_SESSION['list-'.session_id()] * $_SESSION['view-'.session_id()]) - $_SESSION['view-'.session_id()];
        return $this->viewPagination();
    }

    public function formPagination()
    {
        ?>
<form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
<table style="width: 45%;">
	<tr>
		<td style="width: 10%">
			<button type="submit" name="prev" class="mybtn" 
                <?php $_SESSION['list-'.session_id()] <= (int) 1 ? print("disabled") : '' ?>>
				<span class="dashicons dashicons-controls-skipback"></span>
			</button>
		</td>
		<td style="width: 5%">
			<button type="submit" name="prev-pagination" class="mybtn"
				<?php $_SESSION['list-'.session_id()] <= (int) 1 ? print("disabled") : '' ?>>
				<span class="dashicons dashicons-controls-back"></span>
			</button>
		</td>
		<td style="width: 6%; text-align: center; font-weight: bold;">View:</td>
		<td style="width: 7%"><input type="number" min="1" name="count-view"
			value="<?= $_SESSION['view-'.session_id()] ?>" />
        </td>
		<td style="width: 5%">
			<button type="submit" name="confirm-count-view" class="mybtn" 
                <?php $_SESSION['total-'.session_id()] == 0 ? print("disabled") : '' ?>>
                <span class="dashicons dashicons-editor-break"></span>
			</button>
		</td>
		<td style="width: 8%; text-align: center; font-weight: bold;"><?= "List: " ?>
            <?= $_SESSION['list-'.session_id()] ?><?= " of " ?>
            <?= (int) ceil($_SESSION['total-'.session_id()] / $_SESSION['view-'.session_id()]) ?>
        </td>
		<td style="width: 5%;">
			<button type="submit" name="next-pagination" class="mybtn"
				<?php $_SESSION['list-'.session_id()] === (int) ceil($_SESSION['total-'.session_id()] / $_SESSION['view-'.session_id()]) ? print("disabled") : '' ?>>
				<span class="dashicons dashicons-controls-play"></span>
			</button>
		</td>
		<td style="width: 10%;">
			<button type="submit" name="next" class="mybtn"
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

