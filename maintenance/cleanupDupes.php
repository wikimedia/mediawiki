<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
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

/**
 * If on the old non-unique indexes, check the cur table for duplicate
 * entries and remove them...
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

$options = array( 'fix' );

require_once( "commandLine.inc" );
$wgTitle = Title::newFromText( "Dupe cur entry cleanup script" );

checkDupes( isset( $options['fix'] ) );

function fixDupes( $fixthem = false) {
	$dbw =& wfGetDB( DB_MASTER );
	$cur = $dbw->tableName( 'cur' );
	$dbw->query( "LOCK TABLES $cur WRITE" );
	echo "Checking for duplicate cur table entries... (this may take a while on a large wiki)\n";
	$res = $dbw->query( <<<END
SELECT cur_namespace,cur_title,count(*) as c,min(cur_id) as id
  FROM $cur
 GROUP BY cur_namespace,cur_title
HAVING c > 1
END
	);
	$n = $dbw->numRows( $res );
	echo "Found $n titles with duplicate entries.\n";
	if( $n > 0 ) {
		if( $fixthem ) {
			echo "Correcting...\n";
		} else {
			echo "Just a demo...\n";
		}
		while( $row = $dbw->fetchObject( $res ) ) {
			$ns = IntVal( $row->cur_namespace );
			$title = $dbw->addQuotes( $row->cur_title );
			$id = IntVal( $row->id );
			echo "$ns:$row->cur_title (canonical ID $id)\n";
			if( $fixthem ) {
				$dbw->query( <<<END
DELETE
  FROM $cur
 WHERE cur_namespace=$ns
   AND cur_title=$title
   AND cur_id>$id
END
				);
			}
		}
	}
	$dbw->query( 'UNLOCK TABLES' );
	if( $fixthem ) {
		echo "Done.\n";
	} else {
		echo "Run again with --fix option to delete the duplicates.\n";
	}
}

function checkDupes( $fixthem = false ) {
	$dbw =& wfGetDB( DB_MASTER );
	if( $dbw->indexExists( 'cur', 'name_title' ) &&
	    $dbw->indexUnique( 'cur', 'name_title' ) ) {
		echo "Your cur table has the current unique index; no duplicate entries.\n";
	} else {
		echo "Your cur table has the old non-unique index and may have duplicate entries.\n";
		fixDupes( $fixthem );
	}
}

?>