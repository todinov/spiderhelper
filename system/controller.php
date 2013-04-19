<?php

class Controller {
	protected $vars;
	protected $expire;

	function __construct()
	{
		$this->vars = array();
		$this->expire = 3600;
	}

	public function assign($name, $value) 
	{
		$this->vars[$name] = is_object($value) ? $value->fetch() : $value;
	}

	// include a view
	public function view($view)
	{
		if (is_array($this->vars)) 
		{
			extract($this->vars); // pass variables to the view
		}
		$target = APPPATH.'views/'.$view.'.php';
		if (file_exists($target))
		{
			include $target;
		}
	}

	// include a model
	public function model($model)
	{
		$target = APPPATH.'models/'.$model.'.php';
		if (file_exists($target))
		{
			include $target;
		}
		$class = ucfirst($model);
		return new $class;
	}

	// include a helper
	public function helper($helper)
	{
		$target = SYSPATH.'helpers/'.$helper.'.php';
		if (file_exists($target))
		{
			include $target;
		}
	}
}