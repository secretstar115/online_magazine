<?php

require_once 'mappers/article_mapper.php';
require_once 'models/model.php';
require_once 'models/mappers/db_connect.php';

class LatestModel extends Model {
	private $mapper;	
	function __construct($view) {
		parent::__construct($view);
		$this->mapper = new ArticleMapper(new DBConnect());		
	}
	
	/**
	 * Prepare all the latest articles for the view
	 */
	function prepareAll() {
		$this->set ( "LatestData", $this->mapper->fetchAllLatestArticles() );
	}
	/**
	 * Prepare all the latest column articles for the view.
	 * 
	 * @param unknown $column the column to prepare
	 */
	function prepareColumnData($column) {
		$column_articles = $this->mapper->fetchAllColumnArticles($column);
		$this->set ( "ColumnData",  $column_articles);
		$this->set ( "Column",  $column);
	}
	/**
	 * Prepare all the latest reviews for the view.
	 */
	function prepareReviewData() {
		$reviews = $this->mapper->fetchAllReviews();
		$this->set ( "ReviewsData",  $reviews);
	}
}