<?php
require_once 'mappers/article_mapper.php';
require_once 'models/model.php';
require_once 'models/mappers/db_connect.php';

/**
 * This is the model for the home page. 
 *
 */
class HomeModel extends Model {
	function __construct($view) {
		parent::__construct ( $view );
		// Set up the mapper
		$this->mapper = new ArticleMapper ( new DBConnect () );
		// Prepare the 5 popular articles for the home page view
		$this->set ( "PopularArticles", $this->mapper->fetchPopularArticles () );
		// Prepare the 5 featured articles for the home page view
		$this->set ( "FeaturedArticles", $this->mapper->fetchFeaturedArticles () );
		// prepare the 5 latest articles for the home page view
		$this->set ( "LatestArticles", $this->mapper->fetchLatestFiveArticles () );
		// prepare the latest 5 Column Articles for the home page view
		$this->set ( "LatestColumnArticles", $this->mapper->fetchLatestFiveColunArticles () );
		// prepare the latest 5 reviews for the home page view
		$this->set ( "LatestReviews", $this->mapper->fetchLatestFiveReviews () );
	}
}