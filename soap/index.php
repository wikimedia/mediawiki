<?php
# SOAP remote API for MediaWiki
#
# Copyright (C) 2004 Jens Frank, jeluf@gmx.de
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

require_once('nusoap.php');

#
# Initialize the environment
#
unset( $wgEnableSOAP );
unset( $IP );
@ini_set( "allow_url_fopen", 0 ); # For security...

if(!file_exists("../LocalSettings.php")) {
        die( "You'll have to <a href='../config/index.php'>set the wiki up</a> first!" );
}

define( "MEDIAWIKI", true );

require_once( '../includes/Defines.php' );
require_once( '../LocalSettings.php' );

ini_set( "include_path", "$IP:" . ini_get("include_path") );

require_once( '../includes/Setup.php' );

#
# SOAP must be activated in the Configuration. Else, exit.
#
if ( ! $wgEnableSOAP ) {
	print "You have to enable SOAP by setting \$wgEnableSoap=true; in LocalSettings.php\n";
	exit;
}

#
# Set up server and register methods.
#

$s = new soap_server;

$s->register('echoString');		# For testing
$s->register('getArticle');		# For testing
$s->register('getArticleByVersion');
$s->register('getArticleRevisions');
$s->register('searchTitles');

# Convert decimal to ASCII-encoded
#
function decasc( $n ) {
	return strtoupper(base_convert( $n, 10, 36));
}
# Test function
# Return the string.
#
function echoString( $in ) {
	if ( is_string( $in ) ) {
		return $in;
	} else {
		return new soap_fault('Client', 'The parameter to this service must be a string');
	}
}

# Test function
# Return an array:
#   name   	IN  name of the page to get, e.g. Wikipedia talk:Village Pump
#   title  	OUT Title of the page, DB form, e.g. Village_Pump
#   namespace	OUT Namespace of the article, e.g. 3 for Wikipedia_talk.
#   text	OUT Wikitext of the requested page
#
function getArticle( $name ) {
	global $wgWhitelistEdit, $wgWhitelistRead;

	if ( $wgWhitelistEdit || $wgWhitelistRead ) {
		return new soap_fault('Client', 'SOAP not available in whitelist mode.');
	}

	if ( ! is_string( $name ) ) {
		return new soap_fault('Client', 'The parameter to this service must be a string');
	}
	require_once('Title.php');
	if( $name == '' ) {
		$name = wfMsg( "mainpage" );
	}
	$wgTitle = Title::newFromText( $name );
	$dbr =& wfGetDB( DB_SLAVE );
	$text = $dbr->selectField( 'cur', 'cur_text', 
		"cur_title='".$wgTitle->getDBkey()."' AND cur_namespace=".$wgTitle->getNamespace(),
		'SOAP::getArticle' );
	if ( false === $text ) {
		return new soap_fault('Client', 'Page does not exist.');
	}
	return array(
		'title' => $wgTitle->getDBkey(),
		'namespace' => $wgTitle->getNamespace(),
		'text' => $text
	);
}

# getArticleByVersion
# Returns the wikitext and metadata of an article, either current or old revision.
#   title	IN   title of the page, including namespace prefix
#   version	IN   version number of the page, or 0 for cur.
#
#   title	OUT  title of the page, without namespace prefix
#   namespace   OUT  namespace number of that article
#   id		OUT  ID number of this page
#   timestamp   OUT  timestamp of this page
#   text	OUT  wikitext of this page
#   
function getArticleByVersion( $title, $version ) {
	global $wgWhitelistEdit, $wgWhitelistRead;
	$fname = 'SOAP::getArticleByVersion';

	# Check parameters
	#
	if ( $wgWhitelistEdit || $wgWhitelistRead ) {
		return new soap_fault('Client', 'SOAP not available in whitelist mode.');
	}
	if ( ! is_string( $title ) ) {
		return new soap_fault('Client', 'The first parameter to this service must be a string');
	}
	if ( intval( $version ) != $version ) {
		return new soap_fault('Client', 'The second parameter to this service must be an integer');
	}

	# Instantiate a title object
	#
	require_once('Title.php');
	if( $title == '' ) {
		$title = wfMsg( "mainpage" );
	}
	$wgTitle = Title::newFromText( $title );

	$dbr =& wfGetDB( DB_SLAVE );

	# set up SQL query
	#
	if ( $version == 0 ) {
		# Get current revision
		#
		$sql = "SELECT cur_title AS title, cur_namespace AS namespace, cur_id AS id,
				cur_timestamp AS timestamp, cur_text as text FROM cur
			WHERE cur_title = '" . $wgTitle->getDBkey()."' AND cur_namespace=".$wgTitle->getNamespace();
	} else {
		$sql = "SELECT old_title AS title, old_namespace AS namespace, old_id AS id,
				old_timestamp AS timestamp, old_text AS text FROM old
			WHERE old_title = '" . $wgTitle->getDBkey()."' AND old_id=".intval( $version );
	}

	$res = $dbr->query( $sql, $fname, true );

	if ( ! $res ) {
		return new soap_fault( 'Server', $dbr->lastError() );
	}

	if ( ! ( $line = $dbr->fetchObject( $res ) ) ) {
		return new soap_fault( 'Client', 'That page or revision does not exist.' );
	}

	$answer['title'] = $line->title;
	$answer['namespace'] = $line->namespace;
	$answer['id'] = $line->id;
	$answer['timestamp'] = $line->timestamp;
	$answer['text'] = $line->text;

	return $answer;
}

# getArticleRevisions
# Return a list of all revisions of a page.
#   title	IN   title of the page, including namespace prefix
# 
#   title	OUT  title of the page, without namespace prefix
#   namespace	OUT  namespace number of that article
#   id		OUT  ID number of this page
#   revisions	OUT  list of revisions of this page. Array consisting of elements with
#		     the following fields:
#	id	   OUT	revision id of this revision
#	comment	   OUT	edit comment of this revision
#	user	   OUT	user name of the user adding this revision
#	timestamp  OUT	timestamp of this revision
#	minor	   OUT	is this change a minor change
#	unique	   OUT	unique ID of this revision. built from (curid,timestamp,hash(text))
#
function getArticleRevisions( $title ) {
	global $wgWhitelistEdit, $wgWhitelistRead;
	$fname = 'SOAP::getArticleRevisions';

	# Check parameters
	#
	if ( $wgWhitelistEdit || $wgWhitelistRead ) {
		return new soap_fault('Client', 'SOAP not available in whitelist mode.');
	}
	if ( ! is_string( $title ) ) {
		return new soap_fault('Client', 'The first parameter to this service must be a string');
	}

	# Instantiate a title object
	#
	require_once('Title.php');
	if( $title == '' ) {
		$title = wfMsg( "mainpage" );
	}
	$wgTitle = Title::newFromText( $title );

	$dbr =& wfGetDB( DB_SLAVE );

	# set up SQL query
	#
	$sql1 = "SELECT cur_id, cur_comment, cur_user_text, cur_timestamp, cur_minor_edit, cur_text
		FROM cur
		WHERE cur_title = '" . $wgTitle->getDBkey()."' AND cur_namespace=".$wgTitle->getNamespace();

	$sql2 = "SELECT old_id, old_comment, old_user_text, old_timestamp, old_minor_edit, old_text
		FROM old
		WHERE old_title = '" . $wgTitle->getDBkey()."' AND old_namespace=".$wgTitle->getNamespace();
	
	$res = $dbr->query( $sql1, $fname, true );

	if ( ! $res ) {
		return new soap_fault( 'Server', $dbr->lastError() );
	}

	if ( ! ( $line = $dbr->fetchObject( $res ) ) ) {
		return new soap_fault( 'Client', 'That page or revision does not exist.' );
	}

	$curid = $line->cur_id;

	$revisions[] = array( 
		'id' => 0,
		'comment' => $line->cur_comment,
		'user' => $line->cur_user_text,
		'timestamp' => $line->cur_timestamp,
		'minor' => $line->cur_minor_edit,
		'unique' => decasc($curid).'-'.
			decasc(wfTimestamp2Unix($line->cur_timestamp)).'-'.
			decasc(crc32($line->cur_text))
	);

	$answer = array(
		'title' => $wgTitle->getDBkey(),
		'namespace' => $wgTitle->getNamespace(),
		'id' => $curid
	);

	
	$res = $dbr->query( $sql2, $fname, true );
	$answer['count'] = 1;

	if ( $res ) {
		while ( $line = $dbr->fetchObject( $res ) ) {
			$revisions[] = array(
				'id' => $line->old_id,
				'comment' => $line->old_comment,
				'user' => $line->old_user_text,
				'timestamp' => $line->old_timestamp,
				'minor' => $line->old_minor_edit,
				'unique' => decasc($curid).'-'.
					decasc(wfTimestamp2Unix($line->old_timestamp)).'-'.
					decasc(crc32($line->old_text))
			);
			$answer['count'] ++;
		}
	}
	$answer['revisions'] = $revisions;

	return $answer;
}

# searchTitles
# Returns a list of article titles matching a search string
#   pattern	IN   search pattern
#   main_only	IN   if 1, search only the main namespace, if 0, search all namespaces
#
#   count	OUT  number of results
#   base	OUT  base URL of the hits. Concatenate with title_url to get full url.
#   hits	OUT  list of hits. Array consisting of elements with the following fields:
#	title	   OUT	title of the hit. "Display style". Does not include namespace.
#	title_ns   OUT	title of the hit. "Display style". Includes namespace.
#	title_url  OUT	title of the hit. URL escaped. Includes namespace prefix.
#	namespace  OUT  numerical namespace ID
#
function searchTitles( $pattern, $main_only ) {
	global $wgWhitelistEdit, $wgWhitelistRead, $wgLang, $wgServer, $wgArticlePath;
	$fname = 'SOAP::getArticleRevisions';

	# Check parameters
	#
	if ( $wgWhitelistEdit || $wgWhitelistRead ) {
		return new soap_fault('Client', 'SOAP not available in whitelist mode.');
	}
	if ( ! is_string( $pattern ) ) {
		return new soap_fault('Client', 'The first parameter to this service must be a string');
	}
	if ( $main_only != 0 && $main_only != 1 ) {
		return new soap_fault('Client', 'The second parameter to this service must be 0 or 1');
	}
	

	# Connect to the DB
	$dbr =& wfGetDB( DB_SLAVE );

	# Normalize the search pattern
	$pattern = $dbr->strencode( $wgLang->stripForSearch( $pattern ) );

	# Prepare the query

	$sql = 'SELECT cur_id, cur_namespace, cur_title FROM cur, searchindex WHERE '
		. ( $main_only ? 'cur_namespace=0 AND ' :'' )
		. "cur_id=si_page AND MATCH(si_title) AGAINST('{$pattern}' IN BOOLEAN MODE)";
	
	$res = $dbr->query( $sql, $fname, true );

	if ( ! $res ) {
		return new soap_fault( 'Server', $dbr->lastError() );
	}

	$answer = array(
		'count' =>	0,
		'base' =>	$wgServer.$wgArticlePath,
		'hits' =>	array(),
	);
	while ( ($line = $dbr->fetchObject( $res )) && $answer['count'] < 200 ) {
		$nt = Title::newFromDBkey( $wgLang->getNsText( $line->cur_namespace ) . ':' . $line->cur_title );
		$answer['hits'][] = array(
			'title' => $nt->getText(),
			'title_ns' => $nt->getPrefixedText(),
			'title_url' => $nt->getPartialURL(),
			'namespace' => $line->cur_namespace
		);
		$answer['count']++;
	}

	return $answer;
}

# SOAP uses POST, if POST data is available, process it. Else produce an error.
if ( isset( $HTTP_RAW_POST_DATA ) ) {
	$s->service( $HTTP_RAW_POST_DATA );
} else {
	print( "You need a SOAP client to access SOAP services." );
}

?>
