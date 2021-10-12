<?php

class Comment
{
	protected $id;
	protected $content;
	protected $author;
	protected $date;
	protected $ip;
	
	public function __construct ($id, $content, $author, $date, $ip = null)
	{
		$this->id = $id;
		$this->content = $content;
		$this->author = $author;
		$this->date = $date;
		$this->ip = $ip;
	}
	
	public function print()
	{
		
	}
}

?>