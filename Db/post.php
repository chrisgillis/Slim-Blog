<?php

// Load Markdown 
require 'markdown.php';

class Post {
	function __construct($title, $body) {
		$this->bean = R::dispense('post');
		$this->bean->title = $title;
		$this->bean->body  = Markdown($body);
		$this->bean->created_at = time();
		if($id = R::store($this->bean)) {
			return $id;
		} else {
			return false;
		}
	}

	public static function update($id,$title,$body) {
		$bean = R::load('post',$id);
		$bean->title = $title;
		$bean->body = $body;
		if(R::store($bean)) {
			return true;
		} else {
			return false;
		}
	}
}