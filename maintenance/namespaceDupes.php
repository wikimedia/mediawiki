<?php
# Copyright (C) 2005 Brion Vibber <brion@pobox.com>
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

$options = array( 'fix', 'suffix' );

/** */
require_once( 'commandLine.inc' );
#require_once( 'maintenance/userDupes.inc' );

class NamespaceConflictChecker {
	function NamespaceConflictChecker( &$db ) {
		$this->db =& $db;
	}
	
	function checkAll( $fix, $suffix = '' ) {
		global $wgContLang;
		$spaces = $wgContLang->getNamespaces();
		$ok = true;
		foreach( $spaces as $ns => $name ) {
			$ok = $this->checkNamespace( $ns, $name, $fix, $suffix ) && $ok;
		}
		return $ok;
	}
	
	function checkNamespace( $ns, $name, $fix, $suffix = '' ) {
		echo "Checking namespace $ns: \"$name\"\n";
		if( $name == '' ) {
			echo "... skipping article namespace\n";
			return true;
		}
		
		$conflicts = $this->getConflicts( $ns, $name );
		$count = count( $conflicts );
		if( $count == 0 ) {
			echo "... no conflicts detected!\n";
			return true;
		}
		
		echo "... $count conflicts detected:\n";
		$ok = true;
		foreach( $conflicts as $row ) {
			$resolvable = $this->reportConflict( $row, $suffix );
			$ok = $ok && $resolvable;
			if( $fix && ( $resolvable || $suffix != '' ) ) {
				$ok = $this->resolveConflict( $row, $resolvable, $suffix ) && $ok;
			}
		}
		return $ok;
	}
	
	function getConflicts( $ns, $name ) {
		$page  = $this->newSchema() ? 'page' : 'cur';
		$table = $this->db->tableName( $page );
		
		$prefix     = $this->db->strencode( $name );
		$likeprefix = str_replace( '_', '\\_', $prefix);
		
		$sql = "SELECT {$page}_id                                  AS id,
		               {$page}_title                               AS oldtitle,
		               $ns                                         AS namespace,
		               TRIM(LEADING '$prefix:' FROM {$page}_title) AS title
		          FROM {$table}
		         WHERE {$page}_namespace=0
		           AND {$page}_title LIKE '$likeprefix:%'";
		
		$result = $this->db->query( $sql, 'NamespaceConflictChecker::getConflicts' );
		
		$set = array();
		while( $row = $this->db->fetchObject( $result ) ) {
			$set[] = $row;
		}
		$this->db->freeResult( $result );
		
		return $set;
	}
	
	function reportConflict( $row, $suffix ) {
		$newTitle = Title::makeTitle( $row->namespace, $row->title );
		printf( "... %d (0,\"%s\") -> (%d,\"%s\") [[%s]]\n",
			$row->id,
			$row->oldtitle,
			$row->namespace,
			$row->title,
			$newTitle->getPrefixedText() );
		
		$id = $newTitle->getArticleId();
		if( $id ) {
			echo "...  *** cannot resolve automatically; page exists with ID $id ***\n";
			return false;
		} else {
			return true;
		}
	}
	
	function resolveConflict( $row, $resolvable, $suffix ) {
		if( !$resolvable ) {
			$row->title .= $suffix;
			$title = Title::makeTitle( $row->namespace, $row->title );
			echo "...  *** using suffixed form [[" . $title->getPrefixedText() . "]] ***\n";
		}
		$tables = $this->newSchema() 
			? array( 'page' )
			: array( 'cur', 'old' );
		foreach( $tables as $table ) {
			$this->resolveConflictOn( $row, $table );
		}
		return true;
	}
	
	function resolveConflictOn( $row, $table ) {
		$fname = 'NamespaceConflictChecker::resolveConflictOn';
		echo "... resolving on $table... ";
		$this->db->update( $table,
			array(
				"{$table}_namespace" => $row->namespace,
				"{$table}_title"     => $row->title,
			),
			array(
				"{$table}_namespace" => 0,
				"{$table}_title"     => $row->oldtitle,
			),
			$fname );
		echo "ok.\n";
		return true;
	}
	
	function newSchema() {
		return class_exists( 'Revision' );
	}
}




$wgTitle = Title::newFromText( 'Namespace title conflict cleanup script' );

$fix = isset( $options['fix'] );
$suffix = isset( $options['suffix'] ) ? $options['suffix'] : '';
$dbw =& wfGetDB( DB_MASTER );
$duper = new NamespaceConflictChecker( $dbw );
$retval = $duper->checkAll( $fix, $suffix );

if( $retval ) {
	echo "\nLooks good!\n";
	exit( 0 );
} else {
	echo "\nOh noeees\n";
	exit( -1 );
}

?>