<?php

function output($val) {
	die(var_dump($val));
}

if (!function_exists('load_class')) {
	/**
	 * @param	string	the class name being requested 请求的类名
	 * @param	string	an optional argument to pass to the class constructor 实例化该类所需的参数
	 * @return	object
	 */
	function &load_class($class, $param = NULL) {
		// 设置静态变量——类的实例化的数组
		static $_classes = array();

		// Does the class exist? If so, we're done... 如果请求的类对象存在，则直接返回该对象。
		if (isset($_classes[$class])) {
			return $_classes[$class];
		}

		$name = $class;

		foreach (array(MODEL, LIBRARY, CONTROLLER) as $path) {
			if (file_exists($path . $class . ".php")) {
				require_once $path . $class . ".php";
			}
		}

		// 实例化请求的类 并返回该实例
		$_classes[$class] = isset($param)
		? new $name($param)
		: new $name();
		return $_classes[$class];
	}
}

if (!function_exists('is_loaded')) {
	/**
	将已经加载的类的名称记录在静态变量$_is_loaded数组中

	 * @param	string
	 * @return	array
	 */
	function &is_loaded($class = '') {
		static $_is_loaded = array();

		if ($class !== '') {
			$_is_loaded[strtolower($class)] = $class;
		}

		return $_is_loaded;
	}
}

if (!function_exists('load_view')) {
	function load_view($view, $data = array()) {
		if (file_exists(VIEW . $view . '.php')) {
			if (!empty($data)) {
				extract($data);
			}
			require_once VIEW . $view . '.php';
		}
	}
}