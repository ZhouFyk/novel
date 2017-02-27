<?php

/**
 *
 */
class Novel {
	private $novelname = '';
	private $title = '';
	private $content = '';
	private $novelid = '';
	private $db = '';

	function __construct() {
		$this->db = new Database();
	}

	public function select($novelid, $chapterid = '') {
		if ($chapterid) {

		}
	}

	public function update($novelid, $chapterid) {

	}

	public function delete($novelid, $chapterid) {

	}

	public function insert($novelid, $chapterid) {

	}
}