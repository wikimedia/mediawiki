<?php
# MediaWiki 'phase 2' to current format import script
# (import format current as of 1.2.0, March 2004)
#
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# Portions by Lee Daniel Crocker, 2002
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

/**
 * @todo document
 * @deprecated
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
die( "This import script is not currently maintained.
If you need it you'll have to modify it as necessary.\n");

if ( ! is_readable( "../LocalSettings.php" ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "must exist in the source directory.\n";
	exit();
}

$wgCommandLineMode = true;
ini_set("implicit_flush", 1);

$DP = "../includes";
require_once( "../LocalSettings.php" );
require_once( "../AdminSettings.php" );

$wgDBuser = $wgDBadminuser;
$wgDBpassword = $wgDBadminpassword;

$sep = ( DIRECTORY_SEPARATOR == "\\" ) ? ";" : ":";
ini_set( "include_path", "$IP$sep$include_path" );

require_once( "Setup.php" );

require_once( "../install-utils.inc" );
require_once( "InitialiseMessages.inc" );
require_once( "rebuildlinks.inc" );
require_once( "rebuildrecentchanges.inc" );
require_once( "rebuildtextindex.inc" );

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */
class Phase2Importer {
	var $olddb, $titleCache;

	function Phase2Importer( $database ) {
		$this->olddb = $database;
		$this->titleCache = new TitleCache;
	}

	function importAll() {
		$this->importCurData();
		$this->fixCurTitles();

		$this->importOldData();
		$this->fixOldTitles();

		$this->importUserData();
		$this->fixUserOptions();

		$this->importWatchlists();

		$this->importLinkData();

		/*
		# For some reason this is broken. RecentChanges will just start anew...
		rebuildRecentChangesTablePass1();
		rebuildRecentChangesTablePass2();
		*/

		print "Rebuilding search index:\n";
		dropTextIndex();
		rebuildTextIndex();
		createTextIndex();

		initialiseMessages();
	}

	# Simple import functions; for the most part these are pretty straightforward.
	# MySQL copies everything over to the new database and tweaks a few things.
	function importCurData() {
		print "Clearing pages from default install, if any...\n";
		wfQuery( "DELETE FROM cur", DB_MASTER );

		print "Importing current revision data...\n";
		wfQuery( "INSERT INTO cur (cur_id,cur_namespace,cur_title,cur_text,cur_comment,
			cur_user,cur_user_text,cur_timestamp,cur_restrictions,cur_counter,
			cur_is_redirect,cur_minor_edit,cur_is_new,cur_random,cur_touched)
			SELECT cur_id,0,cur_title,cur_text,cur_comment,
				cur_user,cur_user_text,cur_timestamp,REPLACE(cur_restrictions,'is_',''),cur_counter,
				cur_text like '#redirect%',cur_minor_edit,0,RAND(),NOW()+0,
			FROM {$this->olddb}.cur", DB_MASTER );
		$n = mysql_affected_rows();
		print "$n rows imported.\n";
	}

	function importOldData() {
		print "Clearing old revision data from default install, if any...\n";
		wfQuery( "DELETE FROM old", DB_MASTER );

		print "Importing old revision data...\n";
		wfQuery( "INSERT INTO old (old_id,old_namespace,old_title,old_text,old_comment,
			old_user,old_user_text,old_timestamp,old_minor_edit,old_flags)
			SELECT old_id,0,old_title,old_text,old_comment,
				old_user,old_user_text,old_timestamp,old_minor_edit,''
			FROM {$this->olddb}.old", DB_MASTER );
		$n = mysql_affected_rows();
		print "$n rows imported.\n";
	}

	function importUserData() {
		print "Clearing users from default install, if any...\n";
		wfQuery( "DELETE FROM user", DB_MASTER );

		print "Importing user data...\n";
		wfQuery( "INSERT INTO $newdb.user (user_id,user_name,user_rights,
			user_password,user_newpassword,user_email,user_options,user_touched)
			SELECT user_id,user_name,REPLACE(user_rights,'is_',''),
				MD5(CONCAT(user_id,'-',MD5(user_password))),'',user_email,user_options,NOW()+0
			FROM {$this->olddb}.user", DB_MASTER );
		$n = mysql_affected_rows();
		print "$n rows imported.\n";
	}

	# A little less clean...
	function importWatchlists() {
		print "Clearing watchlists from default install, if any...\n";
		wfQuery( "DELETE FROM watchlist", DB_MASTER );

		print "Importing watchlists...";
		$res = wfQuery( "SELECT user_id,user_watch FROM {$this->olddb}.user WHERE user_watch != ''", DB_MASTER );
		$total = wfNumRows( $res );
		$n = 0;
		print " ($total total)\n";

		while( $row = wfFetchObject( $res ) ) {
			$id = intval( $row->user_id );
			$list = explode( "\n", $row->user_watch );
			foreach( $list as $page ) {
				$title = $this->titleCache->fetch( $page );
				if( is_null( $title ) ) {
					print "Caught bad title '{$row->title}'\n";
				} else {
					$ns = $title->getNamespace();
					$t = wfStrencode( $title->getDBkey() );
					wfQuery( "INSERT INTO watchlist(wl_user,wl_namespace,wl_title) VALUES ($id,$ns,'$t')", DB_MASTER );
				}
			}
			if( ++$n % 50 == 0 ) {
				print "$n\n";
			}
		}
		wfFreeResult( $res );
	}

	function importLinkData() {
		# MUST BE CALLED BEFORE! fixCurTitles()
		print "Clearing links from default install, if any...\n";
		wfQuery( "DELETE FROM links", DB_MASTER );
		wfQuery( "DELETE FROM brokenlinks", DB_MASTER );

		print "Importing live links...";
		wfQuery( "INSERT INTO links (l_from, l_to)
					SELECT DISTINCT linked_from,cur_id
					FROM {$this->olddb}.linked,{$this->olddb}.cur
					WHERE linked_to=cur_title", DB_MASTER );
		$n = mysql_affected_rows();
		print "$n rows imported.\n";

		print "Importing broken links...";
		wfQuery( "INSERT INTO brokenlinks (bl_from, bl_to)
					SELECT DISTINCT cur_id,unlinked_to
					FROM {$this->olddb}.unlinked,{$this->olddb}.cur
					WHERE unlinked_from=cur_title", DB_MASTER );
		$n = mysql_affected_rows();
		print "$n rows imported.\n";
	}

	# Fixup functions: munge data that's already been brought into tables
	function fixCurTitles() {
		$this->fixTitles( "cur" );
	}

	function fixOldTitles() {
		$this->fixTitles( "old" );
	}

	function fixTitles( $table ) {
		print "Fixing titles in $table...";
		$res = wfQuery( "SELECT DISTINCT {$table}_title AS title FROM $table", DB_MASTER );
		$total = wfNumRows( $res );
		$n = 0;
		print " ($total total)\n";

		while( $row = wfFetchObject( $res ) ) {
			$xt = wfStrencode( $row->title );
			$title = $this->titleCache->fetch( $row->title );
			if( is_null( $title ) ) {
				print "Caught bad title '{$row->title}'\n";
			} else {
				$ns = $title->getNamespace();
				$t = wfStrencode( $title->getDBkey() );
				wfQuery( "UPDATE $table SET {$table}_namespace=$ns,{$table}_title='$t'
								WHERE {$table}_namespace=0 AND {$table}_title='$xt'", DB_MASTER );
			}
			if( ++$n % 50 == 0 ) {
				print "$n\n";
			}
		}
		wfFreeResult( $res );
	}

	function rewriteUserOptions( $in )
	{
		$s = urldecode( $in );
		$a = explode( "\n", $s );

		foreach ( $a as $l ) {
			if ( preg_match( "/^([A-Za-z0-9_]+)=(.*)/", $l, $m ) ) {
				$ops[$m[1]] = $m[2];
			}
		}
		$nops = array();

		$q = strtolower( $ops["quickBar"] );
		if ( $q == "none" ) { $q = 0; }
		else { $q = 1; } # Default to left
		$nops["quickbar"] = $q;

		if ( $ops["markupNewTopics"] == "inverse" ) {
			$nops["highlightbroken"] = 1;
		}
		$sk = substr( strtolower( $ops["skin"] ), 0, 4 );
		if ( "star" == $sk ) { $sk = 0; }
		else if ( "nost" == $sk ) { $sk = 1; }
		else if ( "colo" == $sk ) { $sk = 2; }
		else { $sk = 0; }
		$nops["skin"] = $sk;

		$u = strtolower( $ops["underlineLinks"] );
		if ( "yes" == $u || "on" == $u ) { $nops["underline"] = 1; }
		else { $nops["underline"] = 0; }

		$t = ( (int) ($ops["hourDiff"]) );
		if ( $t < -23 || $t > 23 ) { $t = 0; }
		if ( 0 != $t ) { $nops["timecorrection"] = $t; }

		$j = strtolower( $ops["justify"] );
		if ( "yes" == $j || "on" == $j ) { $nops["justify"] = 1; }
		$n = strtolower( $ops["numberHeadings"] );
		if ( "yes" == $n || "on" == $n ) { $nops["numberheadings"] = 1; }
		$h = strtolower( $ops["hideMinor"] );
		if ( "yes" == $h || "on" == $h ) { $nops["hideminor"] = 1; }
		$r = strtolower( $ops["rememberPassword"] );
		if ( "yes" == $r || "on" == $r ) { $nops["rememberpassword"] = 1; }
		$s = strtolower( $ops["showHover"] );
		if ( "yes" == $s || "on" == $s ) { $nops["hover"] = 1; }

		$c = $ops["cols"];
		if ( $c < 20 || $c > 200 ) { $nops["cols"] = 80; }
		else { $nops["cols"] = $c; }
		$r = $ops["rows"];
		if ( $r < 5 || $r > 100 ) { $nops["rows"] = 20; }
		else { $nops["rows"] = $r; }
		$r = $ops["resultsPerPage"];
		if ( $r < 3 || $r > 500 ) { $nops["searchlimit"] = 20; }
		else { $nops["searchlimit"] = $r; }
		$r = $ops["viewRecentChanges"];
		if ( $r < 10 || $r > 1000 ) { $nops["rclimit"] = 50; }
		else { $nops["rclimit"] = $r; }
		$nops["rcdays"] = 3;

		$a = array();
		foreach ( $nops as $oname => $oval ) {
			array_push( $a, "$oname=$oval" );
		}
		$s = implode( "\n", $a );
		return $s;
	}

	function fixUserOptions() {
		print "Fixing user options...";
		$res = wfQuery( "SELECT user_id,user_options FROM user", DB_MASTER );
		$total = wfNumRows( $res );
		$n = 0;
		print " ($total total)\n";

		while( $row = wfFetchObject( $res ) ) {
			$id = intval( $row->user_id );
			$option = wfStrencode( $this->rewriteUserOptions( $row->user_options ) );
			wfQuery( "UPDATE user SET user_options='$option' WHERE user_id=$id LIMIT 1", DB_MASTER );
			if( ++$n % 50 == 0 ) {
				print "$n\n";
			}
		}
		wfFreeResult( $res );
	}

}

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */
class TitleCache {
	var $hash = array();

	function &fetch( $dbkey ) {
		if( !isset( $hash[$dbkey] ) ) {
			$hash[$dbkey] = Title::newFromDBkey( $dbkey );
		}
		return $hash[$dbkey];
	}

}

#
print "You should have already run the installer to create a fresh, blank database.\n";
print "Data will be inserted into '$wgDBname'. THIS SHOULD BE EMPTY AND ANY DATA IN IN WILL BE ERASED!\n";
print "\nIf that's not what you want, ABORT NOW!\n\n";

print "Please enter the name of the old 'phase 2'-format database that will be used as a source:\n";
print "Old database name [enciclopedia]: ";
$olddb = readconsole();
if( empty( $olddb ) ) $olddb = "enciclopedia";

if( $olddb == $wgDBname ) {
	die( "Can't upgrade in-place! You must create a new database and copy data into it.\n" );
}

print "\nSource database: '$olddb'\n";
print "  Dest database: '$wgDBname'\n";
print "Is this correct? Anything in '$wgDBname' WILL BE DESTROYED. [y/N] ";
$response = readconsole();
if( strtolower( $response{0} ) != 'y' ) {
	die( "\nAborted by user.\n" );
}

print "Starting import....\n";

$wgTitle = Title::newFromText( "Conversion script" );
$importer = new Phase2Importer( $olddb );
$importer->importAll();

?>
