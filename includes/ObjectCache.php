<?php
# $Id$
#
# Copyright (C) 2003-2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

# Simple generic object store
# interface is intended to be more or less compatible with
# the PHP memcached client.
#
# backends for local hash array and SQL table included:
#  $bag = new HashBagOStuff();
#  $bag = new MysqlBagOStuff($tablename); # connect to db first

class /* abstract */ BagOStuff {
	var $debugmode;
	
	function BagOStuff() {
		$this->set_debug( false );
	}
	
	function set_debug($bool) {
		$this->debugmode = $bool;
	}
	
	/* *** THE GUTS OF THE OPERATION *** */
	/* Override these with functional things in subclasses */
	
	function get($key) {
		/* stub */
		return false;
	}

	function set($key, $value, $exptime=0) {
		/* stub */
		return false;
	}
	
	function delete($key, $time=0) {
		/* stub */
		return false;
	}
	
	function lock($key, $timeout = 0) {
		/* stub */
		return true;
	}

	function unlock($key) {
		/* stub */
		return true;
	}
	
	/* *** Emulated functions *** */
	/* Better performance can likely be got with custom written versions */
	function get_multi($keys) {
		$out = array();
		foreach($keys as $key)
			$out[$key] = $this->get($key);
		return $out;
	}
	
	function set_multi($hash, $exptime=0) {
		foreach($hash as $key => $value)
			$this->set($key, $value, $exptime);
	}
	
	function add($key, $value, $exptime=0) {
		if( $this->get($key) == false ) {
			$this->set($key, $value, $exptime);
			return true;
		}
	}
	
	function add_multi($hash, $exptime=0) {
		foreach($hash as $key => $value)
			$this->add($key, $value, $exptime);
	}

	function delete_multi($keys, $time=0) {
		foreach($keys as $key)
			$this->delete($key, $time);
	}
	
	function replace($key, $value, $exptime=0) {
		if( $this->get($key) !== false )
			$this->set($key, $value, $exptime);
	}
	
	function incr($key, $value=1) {
		if ( !$this->lock($key) ) {
			return false;
		}
		$value = intval($value);
		if($value < 0) $value = 0;

		$n = false;
		if( ($n = $this->get($key)) !== false ) {
			$n += $value;
			$this->set($key, $n); // exptime?
		}
		$this->unlock($key);
		return $n;
	}
	
	function decr($key, $value=1) {
		if ( !$this->lock($key) ) {
			return false;
		}
		$value = intval($value);
		if($value < 0) $value = 0;

		$m = false;
		if( ($n = $this->get($key)) !== false ) {
			$m = $n - $value;
			if($m < 0) $m = 0;
			$this->set($key, $m); // exptime?
		}
		$this->unlock($key);
		return $m;
	}
	
	function _debug($text) {
		if($this->debugmode)
			wfDebug("BagOStuff debug: $text\n");
	}
}


/* Functional versions! */
class HashBagOStuff extends BagOStuff {
	/*
	   This is a test of the interface, mainly. It stores
	   things in an associative array, which is not going to
	   persist between program runs.
	*/
	var $bag;
	
	function HashBagOStuff() {
		$this->bag = array();
	}
	
	function _expire($key) {
		$et = $this->bag[$key][1];
		if(($et == 0) || ($et > time()))
			return false;
		$this->delete($key);
		return true;
	}
	
	function get($key) {
		if(!$this->bag[$key])
			return false;
		if($this->_expire($key))
			return false;
		return $this->bag[$key][0];
	}
	
	function set($key,$value,$exptime=0) {
		if(($exptime != 0) && ($exptime < 3600*24*30))
			$exptime = time() + $exptime;
		$this->bag[$key] = array( $value, $exptime );
	}
	
	function delete($key,$time=0) {
		if(!$this->bag[$key])
			return false;
		unset($this->bag[$key]);
		return true;
	}
}

/*
CREATE TABLE objectcache (
  keyname char(255) binary not null default '',
  value mediumblob,
  exptime datetime,
  unique key (keyname),
  key (exptime)
);
*/
class /* abstract */ SqlBagOStuff extends BagOStuff {
	var $table;

	function SqlBagOStuff($tablename = 'objectcache') {
		$this->table = $tablename;
	}
	
	function get($key) {
		/* expire old entries if any */
		$this->expireall();
		
		$res = $this->_query(
			"SELECT value,exptime FROM $0 WHERE keyname='$1'", $key);
		if(!$res) {
			$this->_debug("get: ** error: " . $this->_dberror($res) . " **");
			return false;
		}
		if($row=$this->_fetchobject($res)) {
			$this->_debug("get: retrieved data; exp time is " . $row->exptime);
			return unserialize($row->value);
		} else {
			$this->_debug('get: no matching rows');
		}
		return false;
	}
	
	function set($key,$value,$exptime=0) {
		$exptime = intval($exptime);
		if($exptime < 0) $exptime = 0;
		if($exptime == 0) {
			$exp = $this->_maxdatetime();
		} else {
			if($exptime < 3600*24*30)
				$exptime += time();
			$exp = $this->_fromunixtime($exptime);
		}
		$this->delete( $key );
		$this->_query(
			"INSERT INTO $0 (keyname,value,exptime) VALUES('$1','$2','$exp')",
			$key, serialize($value));
		return true; /* ? */
	}
	
	function delete($key,$time=0) {
		$this->_query(
			"DELETE FROM $0 WHERE keyname='$1'", $key );
		return true; /* ? */
	}
	
	function getTableName() {
		return $this->table;
	}
	
	function _query($sql) {
		$reps = func_get_args();
		$reps[0] = $this->getTableName();
		// ewwww
		for($i=0;$i<count($reps);$i++) {
			$sql = str_replace(
				'$' . $i,
				$this->_strencode($reps[$i]),
				$sql);
		}
		$res = $this->_doquery($sql);
		if($res == false) {
			$this->_debug('query failed: ' . $this->_dberror($res));
		}
		return $res;
	}
	
	function _strencode($str) {
		/* Protect strings in SQL */
		return str_replace( "'", "''", $str );
	}
	
	function _doquery($sql) {
		die( 'abstract function SqlBagOStuff::_doquery() must be defined' );
	}
	
	function _fetchrow($res) {
		die( 'abstract function SqlBagOStuff::_fetchrow() must be defined' );
	}
	
	function _freeresult($result) {
		/* stub */
		return false;
	}
	
	function _dberror($result) {
		/* stub */
		return 'unknown error';
	}
	
	function _maxdatetime() {
		die( 'abstract function SqlBagOStuff::_maxdatetime() must be defined' );
	}
	
	function _fromunixtime() {
		die( 'abstract function SqlBagOStuff::_fromunixtime() must be defined' );
	}
	
	function expireall() {
		/* Remove any items that have expired */
		$now=$this->_fromunixtime(time());
		$this->_query( "DELETE FROM $0 WHERE exptime<'$now'" );
	}
	
	function deleteall() {
		/* Clear *all* items from cache table */
		$this->_query( "DELETE FROM $0" );
	}
}

class MediaWikiBagOStuff extends SqlBagOStuff {
	var $tableInitialised = false;

	function _doquery($sql) {
		$dbw =& wfGetDB( DB_MASTER );
		return $dbw->query($sql, 'MediaWikiBagOStuff:_doquery');
	}
	function _fetchobject($result) {
		$dbw =& wfGetDB( DB_MASTER );
		return $dbw->fetchObject($result);
	}
	function _freeresult($result) {
		$dbw =& wfGetDB( DB_MASTER );
		return $dbw->freeResult($result);
	}
	function _dberror($result) {
		$dbw =& wfGetDB( DB_MASTER );
		return $dbw->lastError();
	}
	function _maxdatetime() {
		return '9999-12-31 12:59:59';
	}
	function _fromunixtime($ts) {
		return gmdate( 'Y-m-d H:i:s', $ts );
	}
	function _strencode($s) {
		$dbw =& wfGetDB( DB_MASTER );
		return $dbw->strencode($s);
	}
	function getTableName() {
		if ( !$this->tableInitialised ) {
			$dbw =& wfGetDB( DB_MASTER );
			$this->table = $dbw->tableName( $this->table );
			$this->tableInitialised = true;
		}
		return $this->table;
	}
}

class TurckBagOStuff extends BagOStuff {
	function get($key) {
		return mmcache_get( $key );
	}

	function set($key, $value, $exptime=0) {
		mmcache_put( $key, $value, $exptime );
		return true;
	}
	
	function delete($key, $time=0) {
		mmcache_rm( $key );
		return true;
	}

	function lock($key, $waitTimeout = 0 ) {
		mmcache_lock( $key );
		return true;
	}

	function unlock($key) {
		mmcache_unlock( $key );
		return true;
	}
}	
?>
