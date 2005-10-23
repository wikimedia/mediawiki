<?php

$wgNoDBParam = true;	
	
require_once( "commandLine.inc" );
require_once( "rebuildInterwiki.inc" );
require_once( "languages/Names.php" );
if ( count( $args ) != 3 ) {
	die( "Usage: php addwiki.php <language> <site> <dbname>\n" );
}

addWiki( $args[0], $args[1], $args[2] ); 

# -----------------------------------------------------------------

function addWiki( $lang, $site, $dbName )
{
	global $IP, $wgLanguageNames;

	$name = $wgLanguageNames[$lang];

	$dbw =& wfGetDB( DB_WRITE );
	$common = "/home/wikipedia/common";
	$maintenance = "$IP/maintenance";

	print "Creating database $dbName for $lang.$site\n";
	# Set up the database
	$dbw->query( "SET table_type=Innodb" );
	$dbw->query( "CREATE DATABASE $dbName" );
	$dbw->selectDB( $dbName );
	
	print "Initialising tables\n";
	dbsource( "$maintenance/tables.sql", $dbw );
	dbsource( "$IP/extensions/OAI/update_table.sql", $dbw );
	$dbw->query( "INSERT INTO site_stats() VALUES ()" );

	$wgTitle = Title::newMainPage();
	$wgArticle = new Article( $wgTitle );
	$ucsite = ucfirst( $site );

	$wgArticle->insertNewArticle( "
==This subdomain is reserved for the creation of a $ucsite in '''[[:en:{$name}|{$name}]]''' language==

If you can write in this language and want to collaborate in the creation of this encyclopedia then '''you''' can make it.

Go ahead. Translate this page and start working on your encyclopedia.

For help, see '''[[m:Help:How to start a new Wikipedia|how to start a new Wikipedia]]'''.

==Sister projects==
[http://meta.wikipedia.org Meta-Wikipedia] | [http://www.wiktionary.org Wikitonary] | [http://www.wikibooks.org Wikibooks] | [http://www.wikinews.org Wikinews] | [http://www.wikiquote.org Wikiquote] | [http://www.wikisource.org Wikisource]

See the [http://www.wikipedia.org Wikipedia portal] for other language Wikipedias. 

[[aa:]]
[[af:]]
[[als:]]
[[ar:]]
[[de:]]
[[en:]]
[[as:]]
[[ast:]]
[[ay:]]
[[az:]]
[[be:]]
[[bg:]]
[[bn:]]
[[bo:]]
[[bs:]]
[[cs:]]
[[co:]]
[[cs:]]
[[cy:]]
[[da:]]
[[el:]]
[[eo:]]
[[es:]]
[[et:]]
[[eu:]]
[[fa:]]
[[fi:]]
[[fr:]]
[[fy:]]
[[ga:]]
[[gl:]]
[[gn:]]
[[gu:]]
[[he:]]
[[hi:]]
[[hr:]]
[[hy:]]
[[ia:]]
[[id:]]
[[is:]]
[[it:]]
[[ja:]]
[[ka:]]
[[kk:]]
[[km:]]
[[kn:]]
[[ko:]]
[[ks:]]
[[ku:]]
[[ky:]]
[[la:]]
[[ln:]]
[[lo:]]
[[lt:]]
[[lv:]]
[[hu:]]
[[mi:]]
[[mk:]]
[[ml:]]
[[mn:]]
[[mr:]]
[[ms:]]
[[mt:]]
[[my:]]
[[na:]]
[[nah:]]
[[nds:]]
[[ne:]]
[[nl:]]
[[no:]]
[[oc:]]
[[om:]]
[[pa:]]
[[pl:]]
[[ps:]]
[[pt:]]
[[qu:]]
[[ro:]]
[[ru:]]
[[sa:]]
[[si:]]
[[sk:]]
[[sl:]]
[[sq:]]
[[sr:]]
[[sv:]]
[[sw:]]
[[ta:]]
[[te:]]
[[tg:]]
[[th:]]
[[tk:]]
[[tl:]]
[[tr:]]
[[tt:]]
[[ug:]]
[[uk:]]
[[ur:]]
[[uz:]]
[[vi:]]
[[vo:]]
[[xh:]]
[[yo:]]
[[za:]]
[[zh:]]
[[zu:]]
", '', false, false );
	
	print "Adding to dblists\n";
	# Add to dblists
	# Site dblist
	$file = fopen( "$common/$site.dblist", "a" );
	fwrite( $file, "$dbName\n" );
	fclose( $file );

	# All dblist
	$file = fopen( "$common/all.dblist", "a" );
	fwrite( $file, "$dbName\n" );
	fclose( $file );

	print "Constructing interwiki SQL\n";
	# Rebuild interwiki tables
	$sql = getRebuildInterwikiSQL();
	$tempname = tempnam( '/tmp', 'addwiki' );
	$file = fopen( $tempname, 'w' );
	if ( !$file ) {
		die( "Error, unable to open temporary file $tempname\n" );
	}
	fwrite( $file, $sql );
	fclose( $file );
	print "Sourcing interwiki SQL\n";
	dbsource( $tempname, $dbw );
	unlink( $tempname );

	print "Script ended. You now want to run sync-common-all to publish *dblist files (check them for duplicates first)\n";
}
