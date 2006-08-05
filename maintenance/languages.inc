<?php
/**
 * Library to grab data from languages files
 *
 * WORK IN PROGRESS. There is some bugs when including the same
 * file multiple time :(((
 */
require_once('commandLine.inc');

class languages {
	/** Contain the list of languages available */
	var $list = array();
	/** some messages for the current lang */
	var $messages = array();

	function __construct() {
		$this->clear();
		$this->loadList();
	}

	function clear() {
		$this->list = array();
		$this->messages = array();
	}

	function loadList() {
		global $IP;
		$this->list = array();

		// available language files
		$dir = opendir("$IP/languages");
		while ($file = readdir($dir)) {
			if (preg_match("/Messages([^.]*?)\.php$/", $file, $m)) {
				$this->list[] = $m[1];
			}
		}
		sort($this->list);
	}

	function getList() { return $this->list; }
}
?>
