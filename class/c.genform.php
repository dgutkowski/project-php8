<?php

class genForm
{
	private $form;
	private $action;
	private $method;
	private $title;
	private $hSize;
	
	private $state;
	private $msg;
	
	public function __construct($title, $hSize = 3, $action = "", $method = "POST")
	{
		$this->title = $title;
		$this->hSize = $hSize;
		$this->form = "";
		$this->action = $action;
		if($method == "GET" || $method == "POST")
		{	$this->method = $method;	}
		else $this->method = "POST";
		return true;
	}
	public function addInput($title, $name, $value = null, $type = null, $placeholder = null)
	{
		if($type == null) $type = "text";
		$this->form.= "		<div class=\"col-md-12\"><label class=\"form-label\" for=\"".$name."\">".$title."</label><input class=\"form-control form-control-sm\" name=\"".$name."\" type=\"".$type."\" value=\"".$value."\" placeholder=\"".$placeholder."\"></div>\n";
		return true;
	}
	public function addCheckBox()
	{
	}
	public function addRadio()
	{
	}
	public function addSelect($title, $name, $options, $values, $selected = false, $multiples = false)
	{
		$this->form.= "		<div class=\"col-md-12\"><label class=\"form-label\" for=\"".$name."\">".$title."</label>\n";
		$this->form.= "		<select class=\"form-select form-select-sm\" name=\"".$name."\" aria-label=\".form-select-sm example\">\n";
		for($i = 0; !empty($options[$i]); $i++)
		{
			$this->form.= "			<option value=\"".$values[$i]."\"";
			if($values[$i] == $selected) $this->form.= " selected";
			$this->form.= ">".$options[$i]."</option>\n";
		}
		$this->form.="		</select>\n";
	}
	public function addText()
	{
	}
	public function setState($state)
	{
		if(is_int($state)) $this->state = $state;
		else return false;	
	}
	public function setMsg($string, $clear = true)
	{
		if(!empty($string))
		{
			if($clear)
			{
				$this->msg = array();
				$this->msg[] = $string;
			}
			else $this->msg[] = $string;
		}
		else return false;
	}	
	public function addNote($string)
	{
		if(empty($string))
		{
			$this->form.= "		<!-- Pusta notka -->\n";
			return false;
		}
		$this->form.= "		<div class=\"col-md-12 small\">".$string."</div>\n";
		return true;
	}
	public function addSubmit($value)
	{
		$this->form.= "		<div class=\"col-md-12\"><center><input class=\"btn btn-sm btn-light btn-outline-primary w-25 mt-2\" type=\"submit\" value=\"".$value."\"></center></div>\n";
		return true;
	}
	public function printForm()
	{
		if(is_int($this->state) && !empty($this->msg))
		{
			switch($this->state){
			case 0:
				$sDivClass = "alert alert-success";
				break;
			case 1:
				$sDivClass = "alert alert-info";
				break;
			case 2:
				$sDivClass = "alert alert-danger";
				break;
			}
			if(!empty($this->msg))
			{
				echo "	<div class=\"".$sDivClass."\">";
				foreach($this->msg as $msg)
				{
					echo "".$msg."<br>";
				}
				echo "</div>\n";
			}
		}
		echo "	<h".$this->hSize.">".$this->title."</h".$this->hSize.">\n";
		echo "	<form class=\"row g-3\"action=\"\" method=\"post\">\n";
		echo $this->form;
		echo "	</form>\n";
	}
}

?>