<?php


/*
 * Created on Sep 7, 2006
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <FirstnameLastname@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ('ApiBase.php');
}

abstract class ApiQueryBase extends ApiBase {

	private $mQueryModule, $tables, $where, $fields, $options;

	public function __construct($query, $moduleName, $paramPrefix = '') {
		parent :: __construct($query->getMain(), $moduleName, $paramPrefix);
		$this->mQueryModule = $query;
		$this->resetQueryParams();
	}
	
	protected function resetQueryParams() {
		$this->tables = array ();
		$this->where = array ();
		$this->fields = array();
		$this->options = array ();
	}

	protected function addTables($value) {
		if(is_array($value))
			$this->tables = array_merge($this->tables, $value);
		else
			$this->tables[] = $value;
	}
	
	protected function addFields($value) {	
		if(is_array($value))
			$this->fields = array_merge($this->fields, $value);
		else
			$this->fields[] = $value;
	}

	protected function addFieldsIf($value, $condition) {
		if ($condition) {
			$this->addFields($value);
			return true;
		}
		return false;
	}
	
	protected function addWhere($value) {
		if(is_array($value))
			$this->where = array_merge($this->where, $value);
		else
			$this->where[] = $value;
	}
	
	protected function addWhereIf($value, $condition) {
		if ($condition) {
			$this->addWhere($value);
			return true;
		}
		return false;
	}

	protected function addWhereFld($field, $value) {
		if(!is_null($value))
			$this->where[$field] = $value;
	}

	protected function addWhereRange($field, $dir, $start, $end) {
		$isDirNewer = ($dir === 'newer');
		$after = ($isDirNewer ? '<=' : '>=');
		$before = ($isDirNewer ? '>=' : '<=');
		$db = & $this->getDB();

		if (!is_null($start))
			$this->addWhere($field . $after . $db->addQuotes($start));

		if (!is_null($end))
			$this->addWhere($field . $before . $db->addQuotes($end));
			
		$this->addOption('ORDER BY', $field . ($isDirNewer ? '' : ' DESC'));
	}
	
	protected function addOption($name, $value) {
		$this->options[$name] = $value;
	}
	
	protected function select($method) {
		
		// getDB has its own profileDBIn/Out calls
		$db = & $this->getDB();		
		
		$this->profileDBIn();
		$res = $db->select($this->tables, $this->fields, $this->where, $method, $this->options);
		$this->profileDBOut();
		
		return $res;
	}


	protected function addRowInfo($prefix, $row) {

		$vals = array();
		
		// ID
		@$tmp = $row->{$prefix . '_id'};
		if(!is_null($tmp)) $vals[$prefix . 'id'] = intval($tmp);

		// Title
		$title = ApiQueryBase::addRowInfo_title($row, $prefix . '_namespace', $prefix . '_title');
		if ($title) {
			if (!$title->userCanRead())
				return false;
			$vals['ns'] = $title->getNamespace();
			$vals['title'] = $title->getPrefixedText();
		}	

		switch($prefix) {

			case 'page':
				// page_is_redirect
				@$tmp = $row->page_is_redirect;
				if($tmp) $vals['redirect'] = '';

				break;

			case 'rc':
				// PageId
				@$tmp = $row->rc_cur_id;
				if(!is_null($tmp)) $vals['pageid'] = intval($tmp);
	
				@$tmp = $row->rc_this_oldid;
				if(!is_null($tmp)) $vals['revid'] = intval($tmp);
	
				@$tmp = $row->rc_last_oldid;
				if(!is_null($tmp)) $vals['old_revid'] = intval($tmp);
	
				$title = ApiQueryBase::addRowInfo_title($row, 'rc_moved_to_ns', 'rc_moved_to_title');
				if ($title) {
					if (!$title->userCanRead())
						return false;
					$vals['new_ns'] = $title->getNamespace();
					$vals['new_title'] = $title->getPrefixedText();
				}	
	
				@$tmp = $row->rc_patrolled;
				if(!is_null($tmp)) $vals['patrolled'] = '';

				break;

			case 'log':
				// PageId
				@$tmp = $row->page_id;
				if(!is_null($tmp)) $vals['pageid'] = intval($tmp);
	
				if ($row->log_params !== '') {
					$params = explode("\n", $row->log_params);
					if ($row->log_type == 'move' && isset ($params[0])) {
						$newTitle = Title :: newFromText($params[0]);
						if ($newTitle) {
							$vals['new_ns'] = $newTitle->getNamespace();
							$vals['new_title'] = $newTitle->getPrefixedText();
							$params = null;
						}
					}
	
					if (!empty ($params)) {
						$this->getResult()->setIndexedTagName($params, 'param');
						$vals = array_merge($vals, $params);
					}
				}

				break;
		}

		// Type
		@$tmp = $row->{$prefix . '_type'};
		if(!is_null($tmp)) $vals['type'] = $tmp;

		// Action
		@$tmp = $row->{$prefix . '_action'};
		if(!is_null($tmp)) $vals['action'] = $tmp;
		
		// Old ID
		@$tmp = $row->{$prefix . '_text_id'};
		if(!is_null($tmp)) $vals['oldid'] = intval($tmp);

		// User Name / Anon IP
		@$tmp = $row->{$prefix . '_user_text'};
		if(is_null($tmp)) @$tmp = $row->user_name;
		if(!is_null($tmp)) {
			$vals['user'] = $tmp;
			@$tmp = !$row->{$prefix . '_user'};
			if(!is_null($tmp) && $tmp)
				$vals['anon'] = '';
		}
		
		// Bot Edit
		@$tmp = $row->{$prefix . '_bot'};
		if(!is_null($tmp) && $tmp) $vals['bot'] = '';
		
		// New Edit
		@$tmp = $row->{$prefix . '_new'};
		if(is_null($tmp)) @$tmp = $row->{$prefix . '_is_new'};
		if(!is_null($tmp) && $tmp) $vals['new'] = '';
		
		// Minor Edit
		@$tmp = $row->{$prefix . '_minor_edit'};
		if(is_null($tmp)) @$tmp = $row->{$prefix . '_minor'};
		if(!is_null($tmp) && $tmp) $vals['minor'] = '';
		
		// Timestamp
		@$tmp = $row->{$prefix . '_timestamp'};
		if(!is_null($tmp))
			$vals['timestamp'] = wfTimestamp(TS_ISO_8601, $tmp);

		// Comment
		@$tmp = $row->{$prefix . '_comment'};
		if(!empty($tmp))	// optimize bandwidth
			$vals['comment'] = $tmp;
			
		return $vals;
	}  

	private static function addRowInfo_title($row, $nsfld, $titlefld) {
		@$ns = $row->$nsfld;
		if(!is_null($ns)) {
			@$title = $row->$titlefld;
			if(!empty($title))
				return Title :: makeTitle($ns, $title);
		}
		return false;
	}

	/**
	 * Override this method to request extra fields from the pageSet
	 * using $this->getPageSet()->requestField('fieldName')
	 */
	public function requestExtraData() {
	}

	/**
	 * Get the main Query module
	 */
	public function getQuery() {
		return $this->mQueryModule;
	}

	protected function setContinueEnumParameter($paramName, $paramValue) {
		$msg = array (
			$this->encodeParamName($paramName
		) => $paramValue);
		$this->getResult()->addValue('query-continue', $this->getModuleName(), $msg);
	}

	/**
	 * Get the Query database connection (readonly)
	 */
	protected function getDB() {
		return $this->getQuery()->getDB();
	}

	/**
	 * Get the PageSet object to work on
	 * @return ApiPageSet data
	 */
	protected function getPageSet() {
		return $this->mQueryModule->getPageSet();
	}

	/**
	 * This is a very simplistic utility function
	 * to convert a non-namespaced title string to a db key.
	 * It will replace all ' ' with '_'
	 */
	public static function titleToKey($title) {
		return str_replace(' ', '_', $title);
	}

	public static function keyToTitle($key) {
		return str_replace('_', ' ', $key);
	}

	public static function getBaseVersion() {
		return __CLASS__ . ': $Id$';
	}
}

abstract class ApiQueryGeneratorBase extends ApiQueryBase {

	private $mIsGenerator;

	public function __construct($query, $moduleName, $paramPrefix = '') {
		parent :: __construct($query, $moduleName, $paramPrefix);
		$this->mIsGenerator = false;
	}

	public function setGeneratorMode() {
		$this->mIsGenerator = true;
	}

	/**
	 * Overrides base class to prepend 'g' to every generator parameter
	 */
	public function encodeParamName($paramName) {
		if ($this->mIsGenerator)
			return 'g' . parent :: encodeParamName($paramName);
		else
			return parent :: encodeParamName($paramName);
	}

	/**
	 * Execute this module as a generator
	 * @param $resultPageSet PageSet: All output should be appended to this object
	 */
	public abstract function executeGenerator($resultPageSet);
}
?>
