<?php
require_once 'control/controller.php';
/**
 * The Latest Controller provides access for viewing a list of the latest articles of the magazine. 
 * It also provides means for viewing the latest Reviews and Column Articles. 
 *
 */
class LatestController extends Controller {
	/**
	 * Show all the latest articles. 
	 */
	function all() {
		$this->_model->prepareAll();
	}
	/**
	 * Show all articles from a column, odrered by date. 
	 * 
	 * @param unknown $column the target column. 
	 */
	function column($column) {
		if (! isValidColumn ( $column )) {
			header ( 'Location: ' . ROOT . "/control/pagenotfound.php" );
		}
		$this->_model->prepareColumnData ( $column );
		$this->setTemplate ( "templates/column_template.php" );
	}
	/**
	 * Show all reviews ordered by date.
	 */
	function reviews() {
		$this->_model->prepareReviewData ( );
		$this->setTemplate ( "templates/reviews_template.php" );
	}
}