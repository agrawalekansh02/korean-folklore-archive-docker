<?php
/*
Author: Yusuf Bhabhrawala
Date: 5/20/2011
Description: This CMS is based on Joomla CMS. It is a bare minimum boiler plate implementation of the template architecture of Joomla CMS.
*/


class MiniCMS {
	public $args = array();
	public $path = '';
	public $title = false;
	public $css = array();
	public $js = array();
	public $config = array();
	public $style = array();
	public $script = array();
	public $meta = array();

	function __construct() {
		include(dirname(__FILE__) . "/config.php");
		include(dirname(__FILE__) . "/lib.php");
		$this->config = get_defined_vars();

		$url = preg_replace('/\?.+$/','',$_SERVER['REQUEST_URI']);
		$this->path = trim(preg_replace('/\.[a-z]+$/','',$url),'/');
		$this->args = explode('/',$this->path);
		
		if ($this->config['folder_depth'] > 0) {
			$i = $this->config['folder_depth'];
			while($i--) array_shift($this->args); // since the site is in a folder
			$this->path = implode('/', $this->args);
		}
	}

	private function get_html($args, $data = false) {
		$cpath = "contents/" . implode('/', $args) . '.php';
		if (!file_exists($cpath)) return false;
		$cms = $this;
		ob_start();
		include($cpath);
		return ob_get_clean();
	}

	public function content($path = false) {
		$args = ($path) ? explode('/', $path) : $this->args;
		$data = array();
		do {
			if (count($args) > 0) {
				$html = $this->get_html($args, $data);
				if ($html) return $html;
			}

			array_push($args, 'index');
			$html = $this->get_html($args, $data);
			if ($html) return $html;
			array_pop($args);

			array_unshift($data, array_pop($args));
		} while(count($args));
		return $html;		
	}

	public function region($region) {
		$conf = json_decode(file_get_contents("contents/modules/$region.config"), true);
		$html = '';
		foreach ($conf as $mod => $patterns) {
			foreach ($patterns as $patt) {
				if (preg_match("|$patt|", $this->path)) {
					$html .= $this->content("modules/$mod");
				}
			}
		}
		return $html;
	}

	public function head() {
		$title = ($this->title) ? $this->title . ' - ' . $this->config['site_name'] : $this->config['site_name']; 
		$js = ''; foreach($this->js as $t) $js .= "<script src='$t'></script>\n";
		$css = ''; foreach($this->css as $t) $css .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"$t\" media=\"screen,projection\" />\n";
		$baseref = 'https://' . $_SERVER['HTTP_HOST'] . preg_replace('#/[^/]+$#','/',$_SERVER['SCRIPT_NAME']);
		$style = '';
		if (count($this->style) > 0) {
			$style = '<style type="text/css">' . "\n";
			foreach($this->style as $s) $style .= "$s\n";
			$style .= "</style>\n";
		}
		$script = '';
		if (count($this->script) > 0) {
			$script = '<script type="text/javascript">' . "\n";
			foreach($this->script as $s) $script .= "$s\n";
			$script .= "</script>\n";
		}
		$meta = '';
		foreach ($this->meta as $name => $content) {
			$meta .= "<meta name=\"$name\" content=\"$content\" />\n";
		}
echo <<<HEAD
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<base href="$baseref" /> 
	$meta
	$css
	$js
	<title>$title</title> 
	$style
	$script
HEAD;
	}

}

$cms = new MiniCMS();


