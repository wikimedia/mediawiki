<?php
# Copyright (C) 2003 Brion Vibber <brion@pobox.com>
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
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Constructor
 */
function wfSpecialImport( $page = '' ) {
	global $wgOut, $wgLang, $wgRequest, $wgTitle;
	global $wgImportSources;
	
	###
	$wgOut->addWikiText( "Special:Import is not ready for this beta release, sorry." );
	return;
	###
	
	if( $wgRequest->wasPosted() && $wgRequest->getVal( 'action' ) == 'submit') {
		$importer = new WikiImporter();
		
		switch( $wgRequest->getVal( "source" ) ) {
		case "upload":
			$ok = $importer->setupFromUpload( "xmlimport" );
			break;
		case "interwiki":
			$ok = $importer->setupFromInterwiki(
				$wgRequest->getVal( "interwiki" ),
				$wgRequest->getText( "frompage" ) );
			break;
		default:
			$ok = false;
		}
		
		if( $ok ) {
			$importer->setRevisionHandler( "wfImportOldRevision" );
			if( $importer->doImport() ) {
				# Success!
				$wgOut->addHTML( "<p>" . wfMsg( "importsuccess" ) . "</p>" );
			} else {
				$wgOut->addHTML( "<p>" . wfMsg( "importfailed",
					htmlspecialchars( $importer->getError() ) ) . "</p>" );
			}
		} else {
			$wgOut->addWikiText( htmlspecialchars( $importer->getError() ) );
		}
	}
	
	$wgOut->addWikiText( "<p>" . wfMsg( "importtext" ) . "</p>" );
	$action = $wgTitle->escapeLocalUrl();
	$wgOut->addHTML( "
<fieldset>
	<legend>Upload XML</legend>
	<form enctype='multipart/form-data' method='post' action=\"$action\">
		<input type='hidden' name='action' value='submit' />
		<input type='hidden' name='source' value='upload' />
		<input type='hidden' name='MAX_FILE_SIZE' value='200000' />
		<input type='file' name='xmlimport' value='' size='30' />
		<input type='submit' value='" . htmlspecialchars( wfMsg( "uploadbtn" ) ) . "'/>
	</form>
</fieldset>
" );

	if( !empty( $wgImportSources ) ) {
		$wgOut->addHTML( "
<fieldset>
	<legend>Interwiki import</legend>
	<form method='post' action=\"$action\">
		<input type='hidden' name='action' value='submit' />
		<input type='hidden' name='source' value='interwiki' />
		<select name='interwiki'>
" );
		foreach( $wgImportSources as $interwiki ) {
			$iw = htmlspecialchars( $interwiki );
			$wgOut->addHTML( "<option value=\"$iw\">$iw</option>\n" );
		}
		$wgOut->addHTML( "
		</select>
		<input name='frompage' />
		<input type='submit' />
	</form>
</fieldset>
" );
	}
}

function wfImportOldRevision( &$revision ) {
	$dbw =& wfGetDB( DB_MASTER );
	$dbw->deadlockLoop( array( &$revision, 'importOldRevision' ) );
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class WikiRevision {
	var $title = NULL;
	var $timestamp = "20010115000000";
	var $user = 0;
	var $user_text = "";
	var $text = "";
	var $comment = "";
	
	function setTitle( $text ) {
		$text = $this->fixEncoding( $text );
		$this->title = Title::newFromText( $text );
	}
	
	function setTimestamp( $ts ) {
		# 2003-08-05T18:30:02Z
		$this->timestamp = preg_replace( '/^(....)-(..)-(..)T(..):(..):(..)Z$/', '$1$2$3$4$5$6', $ts );
	}
	
	function setUsername( $user ) {
		$this->user_text = $this->fixEncoding( $user );
	}
	
	function setUserIP( $ip ) {
		$this->user_text = $this->fixEncoding( $ip );
	}
	
	function setText( $text ) {
		$this->text = $this->fixEncoding( $text );
	}
	
	function setComment( $text ) {
		$this->comment = $this->fixEncoding( $text );
	}
	
	function fixEncoding( $data ) {
		global $wgLang, $wgInputEncoding;
		
		if( strcasecmp( $wgInputEncoding, "utf-8" ) == 0 ) {
			return $data;
		} else {
			return $wgLang->iconv( "utf-8", $wgInputEncoding, $data );
		}
	}
	
	function getTitle() {
		return $this->title;
	}
	
	function getTimestamp() {
		return $this->timestamp;
	}
	
	function getUser() {
		return $this->user_text;
	}
	
	function getText() {
		return $this->text;
	}
	
	function getComment() {
		return $this->comment;
	}
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class WikiImporter {
	var $mSource = NULL;
	var $mError = "";
	var $mXmlError = XML_ERROR_NONE;
	var $mRevisionHandler = NULL;
	var $lastfield;
	
	function WikiImporter() {
		$this->setRevisionHandler( array( &$this, "defaultRevisionHandler" ) );
	}
	
	function setError( $err ) {
		$this->mError = $err;
		return false;
	}
	
	function getError() {
		if( $this->mXmlError == XML_ERROR_NONE ) {
			return $this->mError;
		} else {
			return xml_error_string( $this->mXmlError );
		}
	}
	
	function throwXmlError( $err ) {
		$this->debug( "FAILURE: $err" );
	}
	
	function setupFromFile( $filename ) {
		$this->mSource = file_get_contents( $filename );
		return true;
	}

	function setupFromUpload( $fieldname = "xmlimport" ) {
		global $wgOut;
		
		$upload =& $_FILES[$fieldname];
		
		if( !isset( $upload ) ) {
			return $this->setError( wfMsg( "importnofile" ) );
		}
		if( !empty( $upload['error'] ) ) {
			return $this->setError( wfMsg( "importuploaderror", $upload['error'] ) );
		}
		$fname = $upload['tmp_name'];
		if( is_uploaded_file( $fname ) ) {
			return $this->setupFromFile( $fname );
		} else {
			return $this->setError( wfMsg( "importnofile" ) );
		}
	}
	
	function setupFromURL( $url ) {
		# fopen-wrappers are normally turned off for security.
		ini_set( "allow_url_fopen", true );
		$ret = $this->setupFromFile( $url );
		ini_set( "allow_url_fopen", false );
		return $ret;
	}
	
	function setupFromInterwiki( $interwiki, $page ) {
		$base = Title::getInterwikiLink( $interwiki );
		if( empty( $base ) ) {
			return false;
		} else {
			$import = wfUrlencode( "Special:Export/$page" );
			$url = str_replace( "$1", $import, $base );
			$this->notice( "Importing from $url" );
			return $this->setupFromURL( $url );
		}
	}
	
	# --------------
	
	function doImport() {
		if( empty( $this->mSource ) ) {
			return $this->setError( wfMsg( "importnotext" ) );
		}
		
		$parser = xml_parser_create( "UTF-8" );
		
		# case folding violates XML standard, turn it off
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, false );
		
		xml_set_object( $parser, &$this );
		xml_set_element_handler( $parser, "in_start", "" );
		
		if( !xml_parse( $parser, $this->mSource, true ) ) {
			# return error message
			$this->mXmlError = xml_get_error_code( $parser );
			xml_parser_free( $parser );
			return false;
		}
		xml_parser_free( $parser );
		
		return true;
	}
	
	function debug( $data ) {
		global $wgOut;
		# $this->notice( "DEBUG: $data\n" );
	}
	
	function notice( $data ) {
		global $wgCommandLineMode;
		if( $wgCommandLineMode ) {
			print "$data\n";
		} else {
			global $wgOut;
			$wgOut->addHTML( "<li>$data</li>\n" );
		}
	}
	
	function setRevisionHandler( $functionref ) {
		$this->mRevisionHandler = $functionref;
	}
	
	function defaultRevisionHandler( &$revision ) {
		$this->debug( "Got revision:" );
		if( is_object( $revision->title ) ) {
			$this->debug( "-- Title: " . $revision->title->getPrefixedText() );
		} else {
			$this->debug( "-- Title: <invalid>" );
		}
		$this->debug( "-- User: " . $revision->user_text );
		$this->debug( "-- Timestamp: " . $revision->timestamp );
		$this->debug( "-- Comment: " . $revision->comment );
		$this->debug( "-- Text: " . $revision->text );
	}
	
	
	
	# XML parser callbacks from here out -- beware!
	function donothing( $parser, $x, $y="" ) {
		#$this->debug( "donothing" );
	}
	
	function in_start( $parser, $name, $attribs ) {
		$this->debug( "in_start $name" );
		if( $name != "mediawiki" ) {
			return $this->throwXMLerror( "Expected <mediawiki>, got <$name>" );
		}
		xml_set_element_handler( $parser, "in_mediawiki", "out_mediawiki" );
	}
	
	function in_mediawiki( $parser, $name, $attribs ) {
		$this->debug( "in_mediawiki $name" );
		if( $name != "page" ) {
			return $this->throwXMLerror( "Expected <page>, got <$name>" );
		}
		xml_set_element_handler( $parser, "in_page", "out_page" );
	}
	function out_mediawiki( $parser, $name ) {
		$this->debug( "out_mediawiki $name" );
		if( $name != "mediawiki" ) {
			return $this->throwXMLerror( "Expected </mediawiki>, got </$name>" );
		}
		xml_set_element_handler( $parser, "donothing", "donothing" );
	}

	function in_page( $parser, $name, $attribs ) {
		$this->debug( "in_page $name" );
		switch( $name ) {
		case "id":
		case "title":
		case "restrictions":
			$this->appendfield = $name;
			$this->appenddata = "";
			$this->parenttag = "page";
			xml_set_element_handler( $parser, "in_nothing", "out_append" );
			xml_set_character_data_handler( $parser, "char_append" );
			break;
		case "revision":
			$this->workRevision = new WikiRevision;
			$this->workRevision->setTitle( $this->workTitle );
			xml_set_element_handler( $parser, "in_revision", "out_revision" );
			break;
		default:
			return $this->throwXMLerror( "Element <$name> not allowed in a <page>." );
		}
	}
	
	function out_page( $parser, $name ) {
		$this->debug( "out_page $name" );
		if( $name != "page" ) {
			return $this->throwXMLerror( "Expected </page>, got </$name>" );
		}
		xml_set_element_handler( $parser, "in_mediawiki", "out_mediawiki" );
		
		$this->workTitle = NULL;
		$this->workRevision = NULL;
	}
	
	function in_nothing( $parser, $name, $attribs ) {
		$this->debug( "in_nothing $name" );
		return $this->throwXMLerror( "No child elements allowed here; got <$name>" );
	}
	function char_append( $parser, $data ) {
		$this->debug( "char_append '$data'" );
		$this->appenddata .= $data;
	}
	function out_append( $parser, $name ) {
		$this->debug( "out_append $name" );
		if( $name != $this->appendfield ) {
			return $this->throwXMLerror( "Expected </{$this->appendfield}>, got </$name>" );
		}
		xml_set_element_handler( $parser, "in_$this->parenttag", "out_$this->parenttag" );
		xml_set_character_data_handler( $parser, "donothing" );
		switch( $this->appendfield ) {
		case "title":
			$this->workTitle = $this->appenddata;
			break;
		case "text":
			$this->workRevision->setText( $this->appenddata );
			break;
		case "username":
			$this->workRevision->setUsername( $this->appenddata );
			break;
		case "ip":
			$this->workRevision->setUserIP( $this->appenddata );
			break;
		case "timestamp":
			$this->workRevision->setTimestamp( $this->appenddata );
			break;
		case "comment":
			$this->workRevision->setComment( $this->appenddata );
			break;
		default;
			$this->debug( "Bad append: {$this->appendfield}" );
		}
		$this->appendfield = "";
		$this->appenddata = "";
	}
	
	function in_revision( $parser, $name, $attribs ) {
		$this->debug( "in_revision $name" );
		switch( $name ) {
		case "id":
		case "timestamp":
		case "comment":
		case "text":
			$this->parenttag = "revision";
			$this->appendfield = $name;
			xml_set_element_handler( $parser, "in_nothing", "out_append" );
			xml_set_character_data_handler( $parser, "char_append" );
			break;
		case "contributor":
			xml_set_element_handler( $parser, "in_contributor", "out_contributor" );
			break;
		default:
			return $this->throwXMLerror( "Element <$name> not allowed in a <revision>." );
		}
	}
	
	function out_revision( $parser, $name ) {
		$this->debug( "out_revision $name" );
		if( $name != "revision" ) {
			return $this->throwXMLerror( "Expected </revision>, got </$name>" );
		}
		xml_set_element_handler( $parser, "in_page", "out_page" );
		
		$out = call_user_func( $this->mRevisionHandler, &$this->workRevision, &$this );
		if( !empty( $out ) ) {
			global $wgOut;
			$wgOut->addHTML( "<li>" . $out . "</li>\n" );
		}
	}
	
	function in_contributor( $parser, $name, $attribs ) {
		$this->debug( "in_contributor $name" );
		switch( $name ) {
		case "username":
		case "ip":
			$this->parenttag = "contributor";
			$this->appendfield = $name;
			xml_set_element_handler( $parser, "in_nothing", "out_append" );
			xml_set_character_data_handler( $parser, "char_append" );
			break;
		default:
			$this->throwXMLerror( "Invalid tag <$name> in <contributor>" );
		}
	}
	
	function out_contributor( $parser, $name ) {
		$this->debug( "out_contributor $name" );
		if( $name != "contributor" ) {
			return $this->throwXMLerror( "Expected </contributor>, got </$name>" );
		}
		xml_set_element_handler( $parser, "in_revision", "out_revision" );
	}

	function importOldRevision() {
		$fname = "WikiImporter::importOldRevision";
		$dbw =& wfGetDB( DB_MASTER );
		
		# Sneak a single revision into place
		$user = User::newFromName( $this->getUser() );

		$res = $dbw->select( 'old', 1, 
			$this->title->oldCond() + array( 'old_timestamp' => $this->timestamp ),
			$fname, 'FOR UPDATE'
		);
		
		$numrows = $dbw->numRows( $res );
		$dbw->freeResult( $res );
		if( $numrows > 0 ) {
			return wfMsg( "importhistoryconflict" );
		}
		
		# Insert the row
		$oldIgnore = $dbw->ignoreErrors( true );
		$success = $dbw->insert( 'old', 
			array( 
				'old_namespace' => intval( $this->title->getNamespace() ),
				'old_title' => $this->title->getDBkey(),
				'old_text' => $this->getText(),
				'old_comment' => $this->getComment(),
				'old_user' => intval( $user->getId() ),
				'old_user_text' => $user->getName(),
				'old_timestamp' => $this->timestamp,
				'inverse_timestamp' => wfInvertTimestamp( $this->timestamp ),
				'old_minor_edit' => 0,
				'old_flags' => ''
			), $fname
		);
		
		return wfMsg( "ok" );
	}
}


?>
