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

/** */
require_once( 'Revision.php' );

/**
 *
 */
function wfSpecialExport( $page = '' ) {
	global $wgOut, $wgLang, $wgRequest;
	
	if( $wgRequest->getVal( 'action' ) == 'submit') {
		$page = $wgRequest->getText( 'pages' );
		$curonly = $wgRequest->getCheck( 'curonly' );
	} else {
		# Pre-check the 'current version only' box in the UI
		$curonly = true;
	}
	
	if( $page != '' ) {
		$wgOut->disable();
		header( "Content-type: application/xml; charset=utf-8" );
		$pages = explode( "\n", $page );
		
		$db =& wfGetDB( DB_SLAVE );
		$history = $curonly ? MW_EXPORT_CURRENT : MW_EXPORT_FULL;
		$exporter = new WikiExporter( $db, $history );
		$exporter->openStream();
		$exporter->pagesByName( $pages );
		$exporter->closeStream();
		return;
	}
	
	$wgOut->addWikiText( wfMsg( "exporttext" ) );
	$titleObj = Title::makeTitle( NS_SPECIAL, "Export" );
	$action = $titleObj->escapeLocalURL( 'action=submit' );
	$wgOut->addHTML( "
<form method='post' action=\"$action\">
<input type='hidden' name='action' value='submit' />
<textarea name='pages' cols='40' rows='10'></textarea><br />
<label><input type='checkbox' name='curonly' value='true' checked='checked' />
" . wfMsg( "exportcuronly" ) . "</label><br />
<input type='submit' />
</form>
" );
}

define( 'MW_EXPORT_FULL',     0 );
define( 'MW_EXPORT_CURRENT',  1 );

define( 'MW_EXPORT_BUFFER',   0 );
define( 'MW_EXPORT_STREAM',   1 );

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class WikiExporter {
	var $pageCallback = null;
	var $revCallback = null;
	
	/**
	 * If using MW_EXPORT_STREAM to stream a large amount of data,
	 * provide a database connection which is not managed by
	 * LoadBalancer to read from: some history blob types will
	 * make additional queries to pull source data while the
	 * main query is still running.
	 *
	 * @param Database $db
	 * @param int $history one of MW_EXPORT_FULL or MW_EXPORT_CURRENT
	 * @param int $buffer one of MW_EXPORT_BUFFER or MW_EXPORT_STREAM
	 */
	function WikiExporter( &$db, $history = MW_EXPORT_CURRENT,
			$buffer = MW_EXPORT_BUFFER ) {
		$this->db =& $db;
		$this->history = $history;
		$this->buffer  = $buffer;
	}
	
	/**
	 * Set a callback to be called after each page in the output
	 * stream is closed. The callback will be passed a database row
	 * object with the last revision output.
	 *
	 * A set callback can be removed by passing null here.
	 *
	 * @param mixed $callback
	 */
	function setPageCallback( $callback ) {
		$this->pageCallback = $callback;
	}
	
	/**
	 * Set a callback to be called after each revision in the output
	 * stream is closed. The callback will be passed a database row
	 * object with the revision data.
	 *
	 * A set callback can be removed by passing null here.
	 *
	 * @param mixed $callback
	 */
	function setRevisionCallback( $callback ) {
		$this->revCallback = $callback;
	}
	
	/**
	 * Returns the export schema version.
	 * @return string
	 */
	function schemaVersion() {
		return "0.3";
	}
	
	/**
	 * Opens the XML output stream's root <mediawiki> element.
	 * This does not include an xml directive, so is safe to include
	 * as a subelement in a larger XML stream. Namespace and XML Schema
	 * references are included.
	 *
	 * To capture the stream to a string, use PHP's output buffering
	 * functions. Output will be encoded in UTF-8.
	 */
	function openStream() {
		global $wgContLanguageCode;
		$ver = $this->schemaVersion();
		print wfElement( 'mediawiki', array(
			'xmlns'              => "http://www.mediawiki.org/xml/export-$ver/",
			'xmlns:xsi'          => "http://www.w3.org/2001/XMLSchema-instance",
			'xsi:schemaLocation' => "http://www.mediawiki.org/xml/export-$ver/ " .
			                        "http://www.mediawiki.org/xml/export-$ver.xsd",
			'version'            => $ver,
			'xml:lang'           => $wgContLanguageCode ),
			null ) . "\n";
		$this->siteInfo();
	}
	
	function siteInfo() {
		$info = array(
			$this->sitename(),
			$this->homelink(),
			$this->generator(),
			$this->caseSetting(),
			$this->namespaces() );
		print "<siteinfo>\n";
		foreach( $info as $item ) {
			print "  $item\n";
		}
		print "</siteinfo>\n";
	}
	
	function sitename() {
		global $wgSitename;
		return wfElement( 'sitename', array(), $wgSitename );
	}
	
	function generator() {
		global $wgVersion;
		return wfElement( 'generator', array(), "MediaWiki $wgVersion" );
	}
	
	function homelink() {
		$page = Title::newFromText( wfMsgForContent( 'mainpage' ) );
		return wfElement( 'base', array(), $page->getFullUrl() );
	}
	
	function caseSetting() {
		global $wgCapitalLinks;
		// "case-insensitive" option is reserved for future
		$sensitivity = $wgCapitalLinks ? 'first-letter' : 'case-sensitive';
		return wfElement( 'case', array(), $sensitivity );
	}
	
	function namespaces() {
		global $wgContLang;
		$spaces = "<namespaces>\n";
		foreach( $wgContLang->getNamespaces() as $ns => $title ) {
			$spaces .= '    ' . wfElement( 'namespace',
				array( 'key' => $ns ),
				str_replace( '_', ' ', $title ) ) . "\n";
		}
		$spaces .= "  </namespaces>";
		return $spaces;
	}
	
	/**
	 * Closes the output stream with the closing root element.
	 * Call when finished dumping things.
	 */
	function closeStream() {
		print "</mediawiki>\n";
	}
	
	/**
	 * Dumps a series of page and revision records for all pages
	 * in the database, either including complete history or only
	 * the most recent version.
	 *
	 *
	 * @param Database $db
	 */
	function allPages() {
		return $this->dumpFrom( '' );
	}
	
	/**
	 * @param Title $title
	 */
	function pageByTitle( $title ) {
		return $this->dumpFrom(
			'page_namespace=' . $title->getNamespace() .
			' AND page_title=' . $this->db->addQuotes( $title->getDbKey() ) );
	}
	
	function pageByName( $name ) {
		$title = Title::newFromText( $name );
		if( is_null( $title ) ) {
			return new WikiError( "Can't export invalid title" );
		} else {
			return $this->pageByTitle( $title );
		}
	}
	
	function pagesByName( $names ) {
		foreach( $names as $name ) {
			$this->pageByName( $name );
		}
	}

	
	// -------------------- private implementation below --------------------
	
	function dumpFrom( $cond = '' ) {
		$fname = 'WikiExporter::dumpFrom';
		wfProfileIn( $fname );
		
		$page     = $this->db->tableName( 'page' );
		$revision = $this->db->tableName( 'revision' );
		$text     = $this->db->tableName( 'text' );
		
		if( $this->history == MW_EXPORT_FULL ) {
			$join = 'page_id=rev_page';
		} elseif( $this->history == MW_EXPORT_CURRENT ) {
			$join = 'page_id=rev_page AND page_latest=rev_id';
		} else {
			wfProfileOut( $fname );
			return new WikiError( "$fname given invalid history dump type." );
		}
		$where = ( $cond == '' ) ? '' : "$cond AND";
		
		if( $this->buffer == MW_EXPORT_STREAM ) {
			$prev = $this->db->bufferResults( false );
		}
		if( $cond == '' ) {
			// Optimization hack for full-database dump
			$pageindex = 'FORCE INDEX (PRIMARY)';
			$revindex = 'FORCE INDEX(page_timestamp)';
		} else {
			$pageindex = '';
			$revindex = '';
		}
		$result = $this->db->query(
			"SELECT * FROM
				$page $pageindex,
				$revision $revindex,
				$text
				WHERE $where $join AND rev_text_id=old_id
				ORDER BY page_id", $fname );
		$wrapper = $this->db->resultObject( $result );
		$this->outputStream( $wrapper );
		
		if( $this->buffer == MW_EXPORT_STREAM ) {
			$this->db->bufferResults( $prev );
		}
		
		wfProfileOut( $fname );
	}
	
	/**
	 * Runs through a query result set dumping page and revision records.
	 * The result set should be sorted/grouped by page to avoid duplicate
	 * page records in the output.
	 *
	 * The result set will be freed once complete. Should be safe for
	 * streaming (non-buffered) queries, as long as it was made on a
	 * separate database connection not managed by LoadBalancer; some
	 * blob storage types will make queries to pull source data.
	 *
	 * @param ResultWrapper $resultset
	 * @access private
	 */
	function outputStream( $resultset ) {
		$last = null;
		while( $row = $resultset->fetchObject() ) {
			if( is_null( $last ) ||
				$last->page_namespace != $row->page_namespace ||
				$last->page_title     != $row->page_title ) {
				if( isset( $last ) ) {
					$this->closePage( $last );
				}
				$this->openPage( $row );
				$last = $row;
			}
			$this->dumpRev( $row );
		}
		if( isset( $last ) ) {
			$this->closePage( $last );
		}
		$resultset->free();
	}
	
	/**
	 * Opens a <page> section on the output stream, with data
	 * from the given database row.
	 *
	 * @param object $row
	 * @access private
	 */
	function openPage( $row ) {
		print "<page>\n";
		$title = Title::makeTitle( $row->page_namespace, $row->page_title );
		print '  ' . wfElementClean( 'title', array(), $title->getPrefixedText() ) . "\n";
		print '  ' . wfElement( 'id', array(), $row->page_id ) . "\n";
		if( '' != $row->page_restrictions ) {
			print '  ' . wfElement( 'restrictions', array(),
				$row->page_restrictions ) . "\n";
		}
	}
	
	/**
	 * Closes a <page> section on the output stream.
	 * If a per-page callback has been set, it will be called
	 * and passed the last database row used for this page.
	 *
	 * @param object $row
	 * @access private
	 */
	function closePage( $row ) {
		print "</page>\n";
		if( isset( $this->pageCallback ) ) {
			call_user_func( $this->pageCallback, $row );
		}
	}
	
	/**
	 * Dumps a <revision> section on the output stream, with
	 * data filled in from the given database row.
	 *
	 * @param object $row
	 * @access private
	 */
	function dumpRev( $row ) {
		$fname = 'WikiExporter::dumpRev';
		wfProfileIn( $fname );
		
		print "    <revision>\n";
		print "      " . wfElement( 'id', null, $row->rev_id ) . "\n";
		
		$ts = wfTimestamp2ISO8601( $row->rev_timestamp );
		print "      " . wfElement( 'timestamp', null, $ts ) . "\n";
		
		print "      <contributor>";
		if( $row->rev_user ) {
			print wfElementClean( 'username', null, $row->rev_user_text );
			print wfElement( 'id', null, $row->rev_user );
		} else {
			print wfElementClean( 'ip', null, $row->rev_user_text );
		}
		print "</contributor>\n";
		
		if( $row->rev_minor_edit ) {
			print  "      <minor/>\n";
		}
		if( $row->rev_comment != '' ) {
			print "      " . wfElementClean( 'comment', null, $row->rev_comment ) . "\n";
		}
	
		$text = Revision::getRevisionText( $row );
		print "      " . wfElementClean( 'text',
			array( 'xml:space' => 'preserve' ), $text ) . "\n";
		
		print "    </revision>\n";
		
		wfProfileOut( $fname );
		
		if( isset( $this->revCallback ) ) {
			call_user_func( $this->revCallback, $row );
		}
	}

}

function wfTimestamp2ISO8601( $ts ) {
	#2003-08-05T18:30:02Z
	return preg_replace( '/^(....)(..)(..)(..)(..)(..)$/', '$1-$2-$3T$4:$5:$6Z', $ts );
}

function xmlsafe( $string ) {
	$fname = 'xmlsafe';
	wfProfileIn( $fname );
	
	/**
	 * The page may contain old data which has not been properly normalized.
	 * Invalid UTF-8 sequences or forbidden control characters will make our
	 * XML output invalid, so be sure to strip them out.
	 */
	$string = UtfNormal::cleanUp( $string );
	
	$string = htmlspecialchars( $string );
	wfProfileOut( $fname );
	return $string;
}

?>
