<?php
/**
 * @deprecated
 * @package MediaWiki
 * @subpackage MaintenanceArchive
 */

/** */
print "This script is obsolete!";
print "It is retained in the source here in case some of its
code might be useful for ad-hoc conversion tasks, but it is
not maintained and probably won't even work as is.";
exit();

# Database conversion (from May 2002 format).  Assumes that
# the old tables have been loaded into an empty database from
# dump files.

global $IP;
require_once( "../LocalSettings.php" );
require_once( "../AdminSettings.php" );
require_once( "$IP/Setup.php" );

$wgTitle = Title::newFromText( "Conversion script" );
require_once( "./rebuildLinks.inc" );
require_once( "./rebuildRecentchanges.inc" );
require_once( "./buildTables.inc" );
set_time_limit(0);

$wgDBuser			= "wikiadmin";
$wgDBpassword		= $wgDBadminpassword;
# $wgImageDirectory	= "/usr/local/apache/htdocs/wikiimages";
$wgImageDirectory	= "/usr/local/apache/htdocs/upload";
$wgMetaImageDirectory   = "/usr/local/apache/htdocs-meta/upload";

renameOldTables();
buildTables();
initializeTables();

# convertImageDirectories();
convertUserTable();
convertOldTable();
convertCurTable();

buildIndexes();

rebuildLinkTablesPass1();
rebuildLinkTablesPass2();

# This is kinda ugly, could be done cleaner
convertImageDirectories();
#rebuildLinkTablesPass1();
#rebuildLinkTablesPass2();
#

removeOldTables();

refillRandom();
rebuildRecentChangesTable();

print "Done.\n";
exit();

########## End of script, beginning of functions.

function convertUserTable()
{
	$count = 0;
	print "Converting USER table.\n";

	$sql = "LOCK TABLES old_user READ, user WRITE";
	$newres = wfQuery( $sql, DB_MASTER );

	$sql = "SELECT user_id,user_name,user_rights,user_password," .
	  "user_email,user_options,user_watch FROM old_user";
	$oldres = wfQuery( $sql, DB_SLAVE );

	$sql = "DELETE FROM user";
	$newres = wfQuery( $sql, DB_MASTER );

	$sql = "";
	while ( $row = mysql_fetch_object( $oldres ) ) {
		$name = addslashes( fixUserName( $row->user_name ) );
		if ( "" == $name ) continue; # Don't convert illegal names

		if ( 0 == ( $count % 10 ) ) {
			if ( 0 != $count ) { $newres = wfQuery( $sql, DB_MASTER ); }

			$sql = "INSERT INTO user (user_id,user_name,user_rights," .
			  "user_password,user_newpassword,user_email,user_options," .
			  "user_watch) VALUES ";
		} else {
			$sql .= ",";
		}
		$ops = addslashes( fixUserOptions( $row->user_options ) );
		$rights = addslashes( fixUserRights( $row->user_rights ) );
		$email = addslashes( $row->user_email );
		$pwd = addslashes( md5( $row->user_password ) );
		$watch = addslashes( $row->user_watch );

		$sql .= "({$row->user_id},'{$name}','{$rights}','{$pwd}',''," .
		  "'{$email}','{$ops}','{$watch}')";

		if ( ( ++$count % 1000 ) == 0 ) {
			print "$count user records processed.\n";
		}
	}
	if ( $sql ) { $newres = wfQuery( $sql, DB_MASTER ); }

	print "$count user records processed.\n";
	mysql_free_result( $oldres );

	$sql = "UNLOCK TABLES";
	$newres = wfQuery( $sql, DB_MASTER );
}

# Convert May 2002 version of database into new format.
#
function convertCurTable()
{
	$count = $countables = 0;
	print "Converting CUR table.\n";

	$sql = "LOCK TABLES old_cur READ, cur WRITE, site_stats WRITE";
	$newres = wfQuery( $sql, DB_MASTER );

	$sql = "SELECT cur_id,cur_title,cur_text,cur_comment,cur_user," .
	  "cur_timestamp,cur_minor_edit,cur_restrictions," .
	  "cur_counter,cur_ind_title,cur_user_text FROM old_cur";
	$oldres = wfQuery( $sql, DB_SLAVE );

	$sql = "DELETE FROM cur";
	wfQuery( $sql, DB_MASTER );

	$sql = "DELETE FROM site_stats";
	wfQuery( $sql, DB_MASTER );

	$sql = "";
	while ( $row = mysql_fetch_object( $oldres ) ) {
		$nt = Title::newFromDBkey( $row->cur_title );
		$title = addslashes( $nt->getDBkey() );
		$ns = $nt->getNamespace();
		$text = addslashes( convertMediaLinks( $row->cur_text ) );

		$ititle = addslashes( indexTitle( $nt->getText() ) );
		$itext = addslashes( indexText( $text, $ititle ) );

		$com = addslashes( $row->cur_comment );
		$cr = addslashes( fixUserRights( $row->cur_restrictions ) );
		$cut = addslashes( $row->cur_user_text );
		if ( "" == $cut ) { $cut = "Unknown"; }

		if ( 2 == $row->cur_minor_edit ) { $isnew = 1; }
		else { $isnew = 0; }
		if ( 0 != $row->cur_minor_edit ) { $isme = 1; }
		else { $isme = 0; }

		# $counter = $row->cur_counter;
		# if ( ! $counter ) { $counter = 0; }

		if ( preg_match( "/^#redirect/i", $text ) ) {
			$redir = 1;
			$text = fixRedirect( $text );
		} else { $redir = 0; }

		$sql = "INSERT INTO cur (cur_id,cur_namespace," .
		  "cur_title,cur_text,cur_comment,cur_user," .
		  "cur_timestamp,cur_minor_edit,cur_is_new," .
		  "cur_restrictions,cur_counter,cur_ind_title," .
		  "cur_ind_text,cur_is_redirect,cur_user_text) VALUES ";
		$sql .= "({$row->cur_id},{$ns},'{$title}','{$text}'," .
		  "'{$com}',{$row->cur_user},'{$row->cur_timestamp}'," .
		  "{$isme},{$isnew},'{$cr}',0,'{$ititle}','{$itext}'," .
		  "{$redir},'{$cut}')";
		wfQuery( $sql, DB_MASTER );

		if ( ( ++$count % 1000 ) == 0 ) {
			print "$count article records processed.\n";
		}
		if ( 0 != $ns ) { continue; }
		if ( 0 != $redir ) { continue; }
		if ( false === strstr( $text, "," ) ) { continue; }
		++$countables;
	}
	print "$count article records processed.\n";
	mysql_free_result( $oldres );

	$sql = "REPLACE INTO site_stats (ss_row_id,ss_total_views," .
	  "ss_total_edits,ss_good_articles) VALUES (1,0,0,{$countables})";
	wfQuery( $sql, DB_MASTER );

	$sql = "UNLOCK TABLES";
	$newres = wfQuery( $sql, DB_MASTER );
}

# Convert May 2002 version of database into new format.
#
function convertOldTable()
{
	$count = 0;
	print "Converting OLD table.\n";

	$sql = "LOCK TABLES old_old READ, old WRITE";
	$newres = wfQuery( $sql, DB_MASTER );

	$sql = "SELECT old_id,old_title,old_text,old_comment,old_user," .
	  "old_timestamp,old_minor_edit,old_user_text FROM old_old";
	$oldres = wfQuery( $sql, DB_SLAVE );

	$sql = "DELETE FROM old";
	$newres = wfQuery( $sql, DB_MASTER );

	while ( $row = mysql_fetch_object( $oldres ) ) {
		$nt = Title::newFromDBkey( $row->old_title );
		$title = addslashes( $nt->getDBkey() );
		$ns = $nt->getNamespace();
		#$text = addslashes( convertMediaLinks( $row->old_text ) );
		# DO NOT convert media links on old versions!!!!!
		# Old table should always be left intact
		$text = addslashes($row->old_text);

		$com = addslashes( $row->old_comment );
		$cut = addslashes( $row->old_user_text );
		if ( "" == $cut ) { $cut = "Unknown"; }

		if ( 0 != $row->old_minor_edit ) { $isme = 1; }
		else { $isme = 0; }

		if ( preg_match( "/^#redirect/i", $text ) ) {
			$redir = 1;
			$text = fixRedirect( $text );
		} else { $redir = 0; }

		$sql = "INSERT INTO old (old_id,old_namespace,old_title," .
		  "old_text,old_comment,old_user," .
		  "old_timestamp,old_minor_edit,old_user_text) VALUES ";
		$sql .= "({$row->old_id},{$ns},'{$title}','{$text}'," .
		  "'{$com}',{$row->old_user},'{$row->old_timestamp}'," .
		  "{$isme},'{$cut}')";
		wfQuery( $sql, DB_MASTER );

		if ( ( ++$count % 1000 ) == 0 ) {
			print "$count history records processed.\n";
		}
	}
	print "$count history records processed.\n";
	mysql_free_result( $oldres );

	$sql = "UNLOCK TABLES";
	$newres = wfQuery( $sql, DB_MASTER );
}

function convertImageDirectoriesX()
{
	global $wgImageDirectory, $wgMetaImageDirectory, $wgUploadDirectory;
	$count = 0;

	print "Moving image files.\n";
	$dir = opendir( $wgImageDirectory ) or die(
	  "Couldn't open directory \"{$wgImageDirectory}\".\n" );

	while ( false !== ( $oname = readdir( $dir ) ) ) {
		if ( "." == $oname{0} ) continue;

		$nt = Title::newFromText( $oname );
		$nname = $nt->getDBkey();

		$exts = array( "png", "gif", "jpg", "jpeg", "ogg" );
		$ext = strrchr( $nname, "." );
		if ( false === $ext ) { $ext = ""; }
		else { $ext = strtolower( substr( $ext, 1 ) ); }
		if ( ! in_array( $ext, $exts ) ) {
			print "Skipping \"{$oname}\"\n";
			continue;
		}
		$oldumask = umask(0);
		$hash = md5( $nname );
		$dest = $wgUploadDirectory . "/" . $hash{0};
		if ( ! is_dir( $dest ) ) {
			mkdir( $dest, 0777 ) or die( "Can't create \"{$dest}\".\n" );
		}
		$dest .= "/" . substr( $hash, 0, 2 );
		if ( ! is_dir( $dest ) ) {
			mkdir( $dest, 0777 ) or die( "Can't create \"{$dest}\".\n" );
		}
		umask( $oldumask );

		if ( copy( "{$wgImageDirectory}/{$oname}", "{$dest}/{$nname}" ) ) {
			++$count;

			$sql = "DELETE FROM image WHERE img_name='" .
			  addslashes( $nname ) . "'";
			$res = wfQuery( $sql, DB_MASTER );

			$sql = "INSERT INTO image (img_name,img_timestamp,img_user," .
			  "img_user_text,img_size,img_description) VALUES ('" .
			  addslashes( $nname ) . "','" .
			  date( "YmdHis" ) . "',0,'(Automated conversion)','" .
			  filesize( "{$dest}/{$nname}" ) . "','')";
			$res = wfQuery( $sql, DB_MASTER );
		} else {
			die( "Couldn't copy \"{$oname}\" to \"{$nname}\"\n" );
		}
	}
	print "{$count} images moved.\n";
}

function convertImageDirectories()
{
	global $wgImageDirectory, $wgMetaImageDirectory, $wgUploadDirectory;
	$count = 0;


	$sql = "SELECT DISTINCT il_to FROM imagelinks";
	$result = wfQuery ( $sql, DB_SLAVE ) ;

   while ( $row = mysql_fetch_object ( $result ) ) {
   	$oname = $row->il_to ;
   	$nname = ucfirst ( $oname ) ;
	
        $exts = array( "png", "gif", "jpg", "jpeg", "ogg" );
		$ext = strrchr( $nname, "." );
		if ( false === $ext ) { $ext = ""; }
		else { $ext = strtolower( substr( $ext, 1 ) ); }
		if ( ! in_array( $ext, $exts ) ) {
			print "Skipping \"{$oname}\"\n";
			continue;
		}
		$oldumask = umask(0);
		$hash = md5( $nname );
		$dest = $wgUploadDirectory . "/" . $hash{0};
		$wgImageDirectoryHash = $wgImageDirectory . "/" . $hash{0} . "/" . substr ( $hash , 0, 2);
		$wgMetaImageDirectoryHash = $wgMetaImageDirectory . "/" . $hash{0} . "/" . substr( $hash, 0, 2);
		if ( ! is_dir( $dest ) ) {
			mkdir( $dest, 0777 ) or die( "Can't create \"{$dest}\".\n" );
		}
		$dest .= "/" . substr( $hash, 0, 2 );
		if ( ! is_dir( $dest ) ) {
			mkdir( $dest, 0777 ) or die( "Can't create \"{$dest}\".\n" );
		}
		umask( $oldumask );

		#echo "Would be copying {$wgImageDirectoryHash}/{$oname} to {$dest}/{$nname}\n";
		#continue;
		
		if ( copy( "{$wgImageDirectoryHash}/{$nname}", "{$dest}/{$nname}" )
		  or copy( "{$wgImageDirectory}/{$oname}", "{$dest}/{$nname}" )
		  or copy( "{$wgImageDirectory}/".strtolower($oname), "{$dest}/{$nname}" )
          or copy( "{$wgMetaImageDirectoryHash}/{$oname}", "{$dest}/{$nname}" )
          or copy( "{$wgMetaImageDirectory}/{$oname}", "{$dest}/{$nname}" )
          or copy( "{$wgMetaImageDirectory}/".strtolower($oname), "{$dest}/{$nname}" ) ) {
			++$count;

			$sql = "DELETE FROM image WHERE img_name='" .
			  addslashes( $nname ) . "'";
			$res = wfQuery( $sql, DB_MASTER );

			$sql = "INSERT INTO image (img_name,img_timestamp,img_user," .
			  "img_user_text,img_size,img_description) VALUES ('" .
			  addslashes( $nname ) . "','" .
			  date( "YmdHis" ) . "',0,'(Automated conversion)','" .
			  filesize( "{$dest}/{$nname}" ) . "','')";
			$res = wfQuery( $sql, DB_MASTER );
		} else {
            echo( "Couldn't copy \"{$oname}\" to \"{$nname}\"\n" );
        }
	}
}

# Utility functions for the above.
#
function convertMediaLinks( $text )
{
	global $wgLang;
	$ins = $wgLang->getNsText( Namespace::getImage() );

	$q = $text;
	$text = preg_replace(
	  "/(^|[^[])http:\/\/(www.||meta.)wikipedia.(?:com|org)\/upload\/(?:[0-9a-f]\/[0-9a-f][0-9a-f]\/|)" .
	  "([a-zA-Z0-9_:.~\%\-]+)\.(png|PNG|jpg|JPG|jpeg|JPEG|gif|GIF)/",
	  "\\1[[{$ins}:\\3.\\4]]", $text );
	$text = preg_replace(
	  "/(^|[^[])http:\/\/(www.||meta.)wikipedia.(?:com|org)\/images\/uploads\/" .
	  "([a-zA-Z0-9_:.~\%\-]+)\.(png|PNG|jpg|JPG|jpeg|JPEG|gif|GIF)/",
	  "\\1[[{$ins}:\\3.\\4]]", $text );

	$text = preg_replace(
	  "/(^|[^[])http:\/\/(www.||meta.)wikipedia.(?:com|org)\/upload\/(?:[0-9a-f]\/[0-9a-f][0-9a-f]\/|)" .
	  "([a-zA-Z0-9_:.~\%\-]+)/", "\\1[[media:\\3]]", $text );
	$text = preg_replace(
	  "/(^|[^[])http:\/\/(www.||meta.)wikipedia.(?:com|org)\/images\/uploads\/" .
	  "([a-zA-Z0-9_:.~\%\-]+)/", "\\1[[media:\\3]]", $text );

	if ($q != $text) echo "BOOF!"; else echo ".";
	return $text;
}

function fixRedirect( $text )
{
	$tc = "[&;%\\-,.\\(\\)' _0-9A-Za-z\\/:\\xA0-\\xff]";
	$re = "#redirect";
	if ( preg_match( "/^{$re}\\s*\\[{$tc}+\\]/i", $text ) ) {
		$text = preg_replace( "/^({$re})\\s*\\[\\s*({$tc}+)\\]/i",
		  "\\1 [[\\2]]", $text, 1 );
	} else if ( preg_match( "/^{$re}\\s+{$tc}+/i", $text ) ) {
		$text = preg_replace( "/^({$re})\\s+({$tc}+)/i",
		  "\\1 [[\\2]]", $text, 1 );
	}
	return $text;
}

function fixUserOptions( $in )
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
	if ( $c < 20 || c > 200 ) { $nops["cols"] = 80; }
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

function fixUserRights( $in )
{
	$a = explode( ",", $in );
	$b = array();
	foreach ( $a as $r ) {
		if ( "is_developer" == strtolower( trim( $r ) ) ) {
			array_push( $b, "developer" );
		} else if ( "is_sysop" == strtolower( trim( $r ) ) ) {
			array_push( $b, "sysop" );
		}
	}
	$out = implode( ",", $b );
	return $out;
}

function fixUserName( $in )
{
	$lc = "-,.()' _0-9A-Za-z\\/:\\xA0-\\xFF";
	$out = preg_replace( "/[^{$lc}]/", "", $in );
	$out = ucfirst( trim( str_replace( "_", " ", $out ) ) );
	return $out;
}

function indexTitle( $in )
{
	$lc = "A-Za-z_'0-9&#;\\x90-\\xFF\\-";
	$t = preg_replace( "/[^{$lc}]+/", " ", $in );
#	$t = preg_replace( "/\\b[{$lc}][{$lc}]\\b/", " ", $t );
	$t = preg_replace( "/\\b[{$lc}]\\b/", " ", $t );
	$t = preg_replace( "/\\s+/", " ", $t );
	return $t;
}

function indexText( $text, $ititle )
{
	global $wgLang;
	$lc = SearchEngine::legalSearchChars() . "&#;";

	$text = preg_replace( "/<\\/?\\s*[A-Za-z][A-Za-z0-9]*\\s*([^>]*?)>/",
	  " ", strtolower( " " . $text . " " ) ); # Strip HTML markup
	$text = preg_replace( "/(^|\\n)\\s*==\\s+([^\\n]+)\\s+==\\s/sD",
	  "\\2 \\2 \\2 ", $text ); # Emphasize headings

	# Strip external URLs
	$uc = "A-Za-z0-9_\\/:.,~%\\-+&;#?!=()@\\xA0-\\xFF";
	$protos = "http|https|ftp|mailto|news|gopher";
	$pat = "/(^|[^\\[])({$protos}):[{$uc}]+([^{$uc}]|$)/";
	$text = preg_replace( $pat, "\\1 \\3", $text );

	$p1 = "/([^\\[])\\[({$protos}):[{$uc}]+]/";
	$p2 = "/([^\\[])\\[({$protos}):[{$uc}]+\\s+([^\\]]+)]/";
	$text = preg_replace( $p1, "\\1 ", $text );
	$text = preg_replace( $p2, "\\1 \\3 ", $text );

	# Internal image links
	$ins = $wgLang->getNsText( Namespace::getImage() );
	$pat2 = "/\\[\\[$ins:([{$uc}]+)\\.(gif|png|jpg|jpeg)([^{$uc}])/i";
	$text = preg_replace( $pat2, " \\1 \\3", $text );

	$text = preg_replace( "/([^{$lc}])([{$lc}]+)]]([a-z]+)/",
	  "\\1\\2 \\2\\3", $text ); # Handle [[game]]s

	# Strip all remaining non-search characters
	$text = preg_replace( "/[^{$lc}]+/", " ", $text );

	# Handle 's, s'
	$text = preg_replace( "/([{$lc}]+)'s /", "\\1 \\1's ", $text );
	$text = preg_replace( "/([{$lc}]+)s' /", "\\1s ", $text );

	# Strip wiki '' and '''
	$text = preg_replace( "/''[']*/", " ", $text );

	# Strip 1- and 2-letter words
#	$text = preg_replace( "/\\s[{$lc}][{$lc}]\\s/", " ", $text );
#	$text = preg_replace( "/\\s[{$lc}][{$lc}]\\s/", " ", $text );
	$text = preg_replace( "/\\s[{$lc}]\\s/", " ", $text );
	$text = preg_replace( "/\\s[{$lc}]\\s/", " ", $text );

	return $text;
}

function refillRandom()
{
	$sql = "INSERT INTO random(ra_current,ra_title) SELECT 0,cur_title " .
	  "FROM cur WHERE cur_namespace=0 AND cur_is_redirect=0 " .
	  "ORDER BY RAND() LIMIT 1000";
	wfQuery( $sql, DB_MASTER, $fname );

	$sql = "UPDATE random SET ra_current=(ra_current+1)";
	wfQuery( $sql, DB_MASTER, $fname );

	$sql = "DELETE FROM random WHERE ra_current>1";
	wfQuery( $sql, DB_MASTER, $fname );
}

function renameOldTables()
{
	$sql = "ALTER TABLE user RENAME TO old_user";
	wfQuery( $sql, DB_MASTER );
	$sql = "ALTER TABLE cur RENAME TO old_cur";
	wfQuery( $sql, DB_MASTER );
	$sql = "ALTER TABLE old RENAME TO old_old";
	wfQuery( $sql, DB_MASTER );
	$sql = "DROP TABLE IF EXISTS linked";
	wfQuery( $sql, DB_MASTER );
	$sql = "DROP TABLE IF EXISTS unlinked";
	wfQuery( $sql, DB_MASTER );
}

function removeOldTables()
{
	wfQuery( "DROP TABLE IF EXISTS old_user", DB_MASTER );
	wfQuery( "DROP TABLE IF EXISTS old_linked", DB_MASTER );
	wfQuery( "DROP TABLE IF EXISTS old_unlinked", DB_MASTER );
	wfQuery( "DROP TABLE IF EXISTS old_cur", DB_MASTER );
	wfQuery( "DROP TABLE IF EXISTS old_old", DB_MASTER );
}

?>
