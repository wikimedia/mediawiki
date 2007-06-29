<?php
# Copyright (C) 2005-2007 Brion Vibber <brion@pobox.com>
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
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

$options = array( 'fix', 'suffix', 'help' );

/** */
require_once( 'commandLine.inc' );

if(isset( $options['help'] ) ) {
print <<<END
usage: namespaceDupes.php [--fix] [--suffix=<text>] [--help]
    --help          : this help message
    --fix           : attempt to automatically fix errors
    --suffix=<text> : dupes will be renamed with correct namespace with <text>
                      appended after the article name.
    --prefix=<text> : Do an explicit check for the given title prefix
                      in place of the standard namespace list.
    --verbose       : Display output for checked namespaces without conflicts

END;
die;
}

class NamespaceConflictChecker {
	function NamespaceConflictChecker( $db, $verbose=false ) {
		$this->db = $db;
		$this->verbose = $verbose;
	}

	function checkAll( $fix, $suffix = '' ) {
		global $wgContLang, $wgNamespaceAliases, $wgCanonicalNamespaceNames;
		global $wgCapitalLinks;
		
		$spaces = array();
		
		// List interwikis first, so they'll be overridden
		// by any conflicting local namespaces.
		foreach( $this->getInterwikiList() as $prefix ) {
			$name = $wgContLang->ucfirst( $prefix );
			$spaces[$name] = 0;
		}

		// Now pull in all canonical and alias namespaces...
		foreach( $wgCanonicalNamespaceNames as $ns => $name ) {
			// This includes $wgExtraNamespaces
			if( $name !== '' ) {
				$spaces[$name] = $ns;
			}
		}
		foreach( $wgContLang->getNamespaces() as $ns => $name ) {
			if( $name !== '' ) {
				$spaces[$name] = $ns;
			}
		}
		foreach( $wgNamespaceAliases as $name => $ns ) {
			$spaces[$name] = $ns;
		}
		foreach( $wgContLang->namespaceAliases as $name => $ns ) {
			$spaces[$name] = $ns;
		}
		
		if( !$wgCapitalLinks ) {
			// We'll need to check for lowercase keys as well,
			// since we're doing case-sensitive searches in the db.
			foreach( array_values( $spaces ) as $name => $ns ) {
				$lcname = $wgContLang->lcfirst( $name );
				$spaces[$lcname] = $ns;
			}
		}
		ksort( $spaces );
		asort( $spaces );
		
		$ok = true;
		foreach( $spaces as $name => $ns ) {
			$ok = $this->checkNamespace( $ns, $name, $fix, $suffix ) && $ok;
		}
		return $ok;
	}
	
	private function getInterwikiList() {
		$result = $this->db->select( 'interwiki', array( 'iw_prefix' ) );
		while( $row = $this->db->fetchObject( $result ) ) {
			$prefixes[] = $row->iw_prefix;
		}
		$this->db->freeResult( $result );
		return $prefixes;
	}

	function checkNamespace( $ns, $name, $fix, $suffix = '' ) {
		if( $ns == 0 ) {
			$header = "Checking interwiki prefix: \"$name\"\n";
		} else {
			$header = "Checking namespace $ns: \"$name\"\n";
		}

		$conflicts = $this->getConflicts( $ns, $name );
		$count = count( $conflicts );
		if( $count == 0 ) {
			if( $this->verbose ) {
				echo $header;
				echo "... no conflicts detected!\n";
			}
			return true;
		}

		echo $header;
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
	
	/**
	 * @fixme: do this for reals
	 */
	function checkPrefix( $key, $prefix, $fix, $suffix = '' ) {
		echo "Checking prefix \"$prefix\" vs namespace $key\n";
		return $this->checkNamespace( $key, $prefix, $fix, $suffix );
	}

	function getConflicts( $ns, $name ) {
		$page  = 'page';
		$table = $this->db->tableName( $page );

		$prefix     = $this->db->strencode( $name );
		$likeprefix = str_replace( '_', '\\_', $prefix);
		$encNamespace = $this->db->addQuotes( $ns );

		$titleSql = "TRIM(LEADING '$prefix:' FROM {$page}_title)";
		if( $ns == 0 ) {
			// An interwiki; try an alternate encoding with '-' for ':'
			$titleSql = "CONCAT('$prefix-',$titleSql)";
		}
                                     
		$sql = "SELECT {$page}_id    AS id,
		               {$page}_title AS oldtitle,
		               $encNamespace AS namespace,
		               $titleSql     AS title
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
		$newTitle = Title::makeTitleSafe( $row->namespace, $row->title );
		printf( "... %d (0,\"%s\") -> (%d,\"%s\") [[%s]]\n",
			$row->id,
			$row->oldtitle,
			$newTitle->getNamespace(),
			$newTitle->getDbKey(),
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
			$title = Title::makeTitleSafe( $row->namespace, $row->title );
			echo "...  *** using suffixed form [[" . $title->getPrefixedText() . "]] ***\n";
		}
		$tables = array( 'page' );
		foreach( $tables as $table ) {
			$this->resolveConflictOn( $row, $table );
		}
		return true;
	}

	function resolveConflictOn( $row, $table ) {
		$fname = 'NamespaceConflictChecker::resolveConflictOn';
		echo "... resolving on $table... ";
		$newTitle = Title::makeTitleSafe( $row->namespace, $row->title );
		$this->db->update( $table,
			array(
				"{$table}_namespace" => $newTitle->getNamespace(),
				"{$table}_title"     => $newTitle->getDbKey(),
			),
			array(
				"{$table}_namespace" => 0,
				"{$table}_title"     => $row->oldtitle,
			),
			$fname );
		echo "ok.\n";
		return true;
	}
}




$wgTitle = Title::newFromText( 'Namespace title conflict cleanup script' );

$verbose = isset( $options['verbose'] );
$fix = isset( $options['fix'] );
$suffix = isset( $options['suffix'] ) ? $options['suffix'] : '';
$prefix = isset( $options['prefix'] ) ? $options['prefix'] : '';
$key = isset( $options['key'] ) ? intval( $options['key'] ) : 0;

$dbw = wfGetDB( DB_MASTER );
$duper = new NamespaceConflictChecker( $dbw, $verbose );

if( $prefix ) {
	$retval = $duper->checkPrefix( $key, $prefix, $fix, $suffix );
} else {
	$retval = $duper->checkAll( $fix, $suffix );
}

if( $retval ) {
	echo "\nLooks good!\n";
	exit( 0 );
} else {
	echo "\nOh noeees\n";
	exit( -1 );
}


