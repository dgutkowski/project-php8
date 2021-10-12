<?php

class News
{
	private $id;
	private $title;
	private $content;
	private $author;
	private $date;
	private $allow_rating;
	private $allow_comment;
	
	public function __construct($id, $title, $content, $author, $date, $a_r, $a_c)
	{
		// weryfikacja zmiennych startowych
		
		// inicjalizacja

		$this->id = $id;			$this->title = $title;			$this->content = $content;
		$this->author = $author;	$this->date = $date;	
		$this->allow_rating = $a_r;	$this->allow_comment = $a_c;
	}
	
	public function print($tabs = null)
	{
		$tab = "";
		while($tabs>0) {	$tab.= "	";	$tabs--;	}
		echo $tab."<h1>".$this->title."</h1>\n";
		echo $tab."<p>\n".$tab."	".$this->content."\n".$tab."</p>\n";
	}
}
?>