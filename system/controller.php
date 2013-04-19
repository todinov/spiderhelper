<?php

class Controller {
	protected $vars;
	// cache data
	protected $cache_vars;
	protected $cache_id;
	protected $cache_file;
	protected $expire;
	protected $cached;

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


	// cache methods
	public function insert($name, $value) 
	{
		$this->cache_vars[$name] = is_object($value) ? $value->fetch() : $value;
		$this->vars[$name] = '<?php echo $'.$name.';?>';
	}

	public function is_cached() 
	{
		if ($this->cached) return true;
		// passed a cache_id?
		if (!$this->cache_id) return false;
		// cache file exists?
		if (!file_exists($this->cache_file)) return false;
		// can't get the time of the file?
		if (!($mtime = filemtime($this->cache_file))) return false;
		// cache expired?
		if (($mtime + $this->expire) < time()) {
			@unlink($this->cache_file);
			return false;
		}
		else 
		{
			$this->cached = true;
			return true;
		}
	}

	public function fetch($target = null, $cache = false) 
	{
		// extract the vars to local namespace
		if ($cache)
		{
			if (is_array($this->cache_vars))
			{
				extract($this->cache_vars);
			}
		}
		else 
		{
			if (is_array($this->vars))
			{
				extract($this->vars);
			}
		}

		// start output buffering
		ob_start();
		if (file_exists($target))
		{
			include $target;
		}
		// get the contents of the buffer
		$contents = ob_get_contents();
		// end buffering and discard
		ob_end_clean();	
		return $contents;
	}

	// this function returns a cached copy of a view, if it exists, 
	// otherwise it parses it as normal and caches the content
	public function cview($view, $cache_id = null) 
	{
		$cache_id = $view.$cache_id;
		$viewname = array_pop(explode('/', $view));
		
		$this->cache_id = $cache_id ? CACHEPATH . md5($cache_id) : $cache_id;
		$this->cache_file = $this->cache_id.'.'.$viewname.'.php';

		if ($this->is_cached())
		{
			echo $this->fetch($this->cache_file, true);
		}
		else 
		{
			// create cache and fetch it
			$target = APPPATH.'views/'.$view.'.php';
			$contents = $this->fetch($target);
			// Write the cache
			$success = file_put_contents($this->cache_file, $contents);
			if ($success)
			{
				echo $this->fetch($this->cache_file, true);
			}
			else
			{
				die('System error');
			}
		}
	}
}