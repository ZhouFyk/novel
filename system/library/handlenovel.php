<?php

/**
 * 从网上获取小说的一系列操作
 */
class HandleNovel {
	private $baseUrl = ''; // 小说网站url

	//小说网站内的搜索接口
	private $baseSearchUrl =
		'http://zhannei.baidu.com/cse/search?s=2041213923836881982&q=%s&isNeedCheckDomain=1&jump=1';
	private $pageUrl = ''; // 要获取的页面url
	private $errorInfo = '';
	public $novelInfo = [
		'cover' => '',
		'novelname' => '',
		'author' => '',
		'type' => '',
	];

	private $patterns = [
		'spage' => [
			'fimg' =>
			'/<div\sclass=\"result-game-item-pic\">([\w\W]*?)<\/div>/',
			'finfo' =>
			'/<div\sclass=\"result-game-item-detail\">([\w\W]*?)<\/div>/',
			//获取小说封面url
			'cover' =>
			'/<img\ssrc=\"([\w\W]*?)\"[\w\W]*?>/',
			//获取小说列表url以及小说名
			'novelname' =>
			'/<a[\s\S]*?\shref=\"([\w\W]*?)\"\stitle=\"([\w\W]*?)\"[\s\S]*?>/',
			//获取小说作者名
			'author' =>
			'/<span>([\w\W]*?)<\/span>/',
			//获取小说类型
			'type' =>
			'/<span\sclass=\"result-game-item-info-tag-title\">([\w\W]*?)<\/span>/',
		],
		'lpage' => [
			'list' => '/<div\ id=\"list\">([\w\W]*?)<\/div>/',
			'href' => '/<a\shref=\"(\d+?)\.html\">([\w\W]*?)<\/a>/',
		],
		'cpage' => [
			'prev' => '/<a\shref=\"(\d+?).html\">上一章<\/a>/',
			// 'list' => '/<a\shref=\"([\w\W]+?)\">章节列表<\/a>/',
			'next' => '/<a\shref=\"(\d+?).html\">下一章<\/a>/',
			'title' => '/<h1>([\w\W]*?)<\/h1>/',
			'content' => '/<div\ id=\"content\">([\w\W]*?)<\/div>/',
			'url1' => '/三七中文[\s]+?www.37zw.com/',
			'url2' => '/百度搜索“37zw”访问/',
		],
	];

	public function __construct($novelname = '') {
		$this->novelInfo['novelname'] = $novelname;
	}

	private function loader($config) {
		if (file_exists('../../app/config/' . $config)) {
			if (isset($config)) {
				require_once '../../app/config';
			}
		}
	}

	/*
		返回小说列表页面
		@return array
	*/
	public function returnlPage($url = '') {
		if (empty($url)) {
			$url = $this->getSearchPage();
		}

		$links = $this->getNovelList($url);
		$linkurl = [];
		foreach ($links as $key => &$value) {
			$value[1] =
				'<a href="novel/index.php?page=chapter&url[0]=' . $url . '&url[1]=' . $value[1] . '" class="list-group-item">' . $value[2] . '</a>';
			$linkurl[] = $value[1];
		}

		return $linkurl;

	}

	/*
		返回小说章节页面
		@return array
	*/
	public function returncPage($url) {
		// $this->pageUrl为指定url 从该Url中获取页面内容
		// $this->pageUrl = $this->baseUrl . $url;
		$allurl = $url[0] . $url[1] . '.html';
		$cpage = $this->loadPage($allurl);
		$pattern = $this->patterns['cpage'];

		//从页面中获取内容
		preg_match($pattern['title'], $cpage, $title); //章节标题
		preg_match_all($pattern['content'], $cpage, $content, PREG_SET_ORDER); //章节内容
		preg_match($pattern['prev'], $cpage, $prev);
		// preg_match($pattern['list'], $cpage, $list);
		preg_match($pattern['next'], $cpage, $next);

		$chapter['list'] = 'novel/index.php?page=list&url[0]=' . $url[0];

		if (empty($prev)) {
			$chapter['prev'] = 'novel/index.php?page=list&url[0]=' . $url[0];
		} else {
			$chapter['prev'] = 'novel/index.php?page=chapter&url[0]=' . $url[0] . '&url[1]=' . $prev[1];
		}
		if (empty($next)) {
			$chapter['next'] = 'novel/index.php?page=list&url[0]=' . $url[0];
		} else {
			$chapter['next'] = 'novel/index.php?page=chapter&url[0]=' . $url[0] . '&url[1]=' . $next[1];
		}

		$chapter['title'] = $title[1];

		$content = preg_replace($pattern['url1'], '', trim($content[0][1]));
		$chapter['content'] = preg_replace($pattern['url2'], '', $content);

		return $chapter;
	}

	/*
		通过小说网站搜索页面来搜索需要的小说，获取小说的详细Url
		@return string
	*/
	private function getSearchPage() {
		$url = $this->buildSearchUrl(); //建立搜索页面的Url
		$spage = $this->loadPage($url); //获得搜索页面
		$pattern = $this->patterns['spage'];

		// 是否有小说存在
		$isnovel = (preg_match($pattern['fimg'], $spage, $img)
			&& preg_match($pattern['finfo'], $spage, $info));
		if (!$isnovel) {
			$this->errorInfo = '搜不到该小说';
			return false;
		}

		unset($pattern['fimg'], $pattern['finfo']);
		preg_match($pattern['cover'], $img[1], $cover); //获取封面
		unset($pattern['cover']);

		// 获取其他信息
		foreach ($pattern as $key => $value) {
			preg_match($value, $info[1], $$key);
		}

		if ($this->novelInfo['novelname'] != $novelname[2]) {
			$this->errorInfo = '搜不到该小说';
			return false;
		}
		//将小说基本信息存储在数组中
		foreach ($this->novelInfo as $key => $value) {
			$k = $$key;
			if ($key == 'novelname') {
				$this->novelInfo[$key] = trim($k[2]);
				continue;
			}
			$this->novelInfo[$key] = trim($k[1]);
		}

		//返回小说的章节列表页url
		$this->baseUrl = $novellist = $novelname[1];

		return $novellist;
	}

	/*
		从网站中获取小说的列表 返回章节url数组
		@return array
	*/
	private function getNovelList($novellist) {
		$lpage = $this->loadPage($novellist);
		$pattern = $this->patterns['lpage'];
		//从网页中获取所有章节链接
		preg_match($pattern['list'], $lpage, $list);
		preg_match_all($pattern['href'], $list[0], $links, PREG_SET_ORDER);

		return $links;
	}

	/*
		根据传入的小说章节url
		获取小说g该章节内容
		返回该章节标题与内容
		@param  	array
		@return 	array
	*/
	private function getNovelContent($link) {
		$chapter = array();
		// $this->pageUrl为指定url 从该Url中获取页面内容
		$this->pageUrl = $this->baseUrl . $link;
		$cpage = $this->loadPage($this->pageUrl);
		$pattern = $this->patterns['cpage'];

		//从页面中获取内容
		preg_match($pattern['title'], $cpage, $title); //章节标题
		preg_match_all($pattern['content'], $cpage, $content, PREG_SET_ORDER); //章节内容
		$chapter['title'] = $title[1];
		$chapter['content'] = trim($content[0][1]);
		return $chapter;
	}

	/*
		*	使用url获取指定页面,返回页面的内容
		*	@param    string
		*	@return   string
	*/
	private function loadPage($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url); //指定的url
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //将执行结果以字符串返回而不是直接输出
		//CURLOPT_HEADER:启用时会将头文件的信息作为数据流输出。
		curl_setopt($curl, CURLOPT_HEADER, 0);
		//CURLOPT_FOLLOWLOCATION:TRUE 时将会根据服务器返回 HTTP 头中的 "Location: " 重定向
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		//CURLOPT_HTTPHEADER: 设置 HTTP 头字段的数组。格式： array('Content-type: text/plain', 'Content-length: 100')
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded;charset=utf-8", 'Accept-Encoding: gzip'));
		//CURLOPT_ENCODING: HTTP请求头中"Accept-Encoding: "的值。 这使得能够解码响应的内容。 支持的编码有"identity"，"deflate"和"gzip"。如果为空字符串""，会发送所有支持的编码类型。
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
		$cpage = curl_exec($curl);
		$cpage = mb_convert_encoding($cpage, 'utf-8', 'GBK,UTF-8,ASCII'); //编码格式调整为utf-8
		curl_close($curl);

		return $cpage;
	}

	private function outputValue($s) {
		echo "<pre>";
		die(var_dump($s));
	}

	/*
		建立小说站内搜索的Url
		@param  	string
		@return 	string
	*/
	private function buildSearchUrl() {
		$novelname = $this->novelInfo['novelname'];
		$url = sprintf($this->baseSearchUrl, $novelname);

		return $url;
	}

	private function showErrors() {
		echo "$this->errorInfo";
	}
}
