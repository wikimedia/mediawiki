<?php
/**
 * Library to grab data from languages files
 *
 * WORK IN PROGRESS. There is some bugs when including the same
 * file multiple time :(((
 */
require_once('commandLine.inc');
require_once('languages/Language.php');
class languages {
	/** Contain the list of languages available */
	var $list = array();
	/** some messages for the current lang */
	var $messages = array();

	function languages() {
		$this->clear;
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
			if (preg_match("/Language(.*?)\.php$/", $file, $m)) {
				$this->list[] = $m[1];
			}
		}
		sort($this->list);

		// Cleanup file list
		foreach($this->list as $key => $lang) {
			if ($lang == 'Utf8' || $lang == '' || $lang == 'Converter')
				unset($this->list[$key]);
		}
	}

	function getList() { return $this->list; }	
}

function getMessages($langcode) {
	global $wgAllMessagesEn, $wgSkinNamesEn, $wgNamespaceNamesEn, $wgBookstoreListEn,$wgMagicWordsEn,$wgUserTogglesEn;

	$arr = 'wgAllMessages'.$langcode;
	global $$arr;

	if(!isset($$arr)) {
		require_once( 'languages/Language'.$langcode.'.php' );
	}
	return $$arr;
}

?>
