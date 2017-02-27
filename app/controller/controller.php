<?php

/**
 *
 */
class Controller {

	// function __construct(argument)
	// {
	// 	# code...
	// }
	private $model = '';

	private function clean($str) {
		if (is_array($str)) {
			foreach ($str as $key => &$value) {
				trim(htmlentities($value));
			}
			return $str;
		}
		return trim(htmlentities($str));
	}

	public function search($novel) {
		$novel = $this->clean($novel);
		$handlenovel = new HandleNovel($novel);
		$links = $handlenovel->returnlPage();
		$data['links'] = $links;
		$view = load_view('header');
		$view .= load_view('list', $data);
		$view .= load_view('footer');
	}

	public function getChapter($url) {
		$url = $this->clean($url);
		$handlenovel = new HandleNovel();
		$data = $handlenovel->returncPage($url);
		$view = load_view('header') . load_view('chapter', $data) . load_view('footer');
	}

	public function getList($url) {
		$url = $this->clean($url);
		$handlenovel = new HandleNovel();
		$data = $handlenovel->returnlPage($url);
		$data['links'] = $data;
		$view = load_view('header') . load_view('list', $data) . load_view('footer');
	}
}