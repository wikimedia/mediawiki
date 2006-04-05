<?php
# Copyright (C) 2003, 2005, 2006 Brion Vibber <brion@pobox.com>
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
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** */
require_once( 'Revision.php' );

define( 'MW_EXPORT_FULL',     0 );
define( 'MW_EXPORT_CURRENT',  1 );

define( 'MW_EXPORT_BUFFER',   0 );
define( 'MW_EXPORT_STREAM',   1 );

define( 'MW_EXPORT_TEXT',     0 );
define( 'MW_EXPORT_STUB',     1 );


/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class WikiExporter {
	
	var $list_authors = false ; # Return distinct author list (when not returning full history)
	var $author_list = "" ;
	
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
			$buffer = MW_EXPORT_BUFFER, $text = MW_EXPORT_TEXT ) {
		$this->db =& $db;
		$this->history = $history;
		$this->buffer  = $buffer;
		$this->writer  = new XmlDumpWriter();
		$this->sink    = new DumpOutput();
		$this->text    = $text;
	}

	/**
	 * Set the DumpOutput or DumpFilter object which will receive
	 * various row objects and XML output for filtering. Filters
	 * can be chained or used as callbacks.
	 *
	 * @param mixed $callback
	 */
	function setOutputSink( &$sink ) {
		$this->sink =& $sink;
	}

	function openStream() {
		$output = $this->writer->openStream();
		$this->sink->writeOpenStream( $output );
	}

	function closeStream() {
		$output = $this->writer->closeStream();
		$this->sink->writeCloseStream( $output );
	}

	/**
	 * Dumps a series of page and revision records for all pages
	 * in the database, either including complete history or only
	 * the most recent version.
	 */
	function allPages() {
		return $this->dumpFrom( '' );
	}

	/**
	 * Dumps a series of page and revision records for those pages
	 * in the database falling within the page_id range given.
	 * @param int $start Inclusive lower limit (this id is included)
	 * @param int $end   Exclusive upper limit (this id is not included)
	 *                   If 0, no upper limit.
	 */
	function pagesByRange( $start, $end ) {
		$condition = 'page_id >= ' . intval( $start );
		if( $end ) {
			$condition .= ' AND page_id < ' . intval( $end );
		}
		return $this->dumpFrom( $condition );
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

	# Generates the distinct list of authors of an article
	# Not called by default (depends on $this->list_authors)
	# Can be set by Special:Export when not exporting whole history
	function do_list_authors ( $page , $revision , $cond ) {
		$fname = "do_list_authors" ;
		wfProfileIn( $fname );
		$this->author_list = "<contributors>";
		$sql = "SELECT DISTINCT rev_user_text,rev_user FROM {$page},{$revision} WHERE page_id=rev_page AND " . $cond ;
		$result = $this->db->query( $sql, $fname );
		$resultset = $this->db->resultObject( $result );
		while( $row = $resultset->fetchObject() ) {
			$this->author_list .= "<contributor>" . 
				"<username>" . 
				htmlentities( $row->rev_user_text )  . 
				"</username>" . 
				"<id>" . 
				$row->rev_user .
				"</id>" . 
				"</contributor>";
		}
		wfProfileOut( $fname );
		$this->author_list .= "</contributors>";
	}

	function dumpFrom( $cond = '' ) {
		$fname = 'WikiExporter::dumpFrom';
		wfProfileIn( $fname );

		$page     = $this->db->tableName( 'page' );
		$revision = $this->db->tableName( 'revision' );
		$text     = $this->db->tableName( 'text' );
		
		if( $this->history == MW_EXPORT_FULL ) {
			$join = 'page_id=rev_page';
		} elseif( $this->history == MW_EXPORT_CURRENT ) {
			if ( $this->list_authors && $cond != '' )  { // List authors, if so desired
				$this->do_list_authors ( $page , $revision , $cond );
			}
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
			$revindex = 'FORCE INDEX (PRIMARY)';
			$straight = ' /*! STRAIGHT_JOIN */ ';
		} else {
			$pageindex = '';
			$revindex = '';
			$straight = '';
		}
		if( $this->text == MW_EXPORT_STUB ) {
			$sql = "SELECT $straight * FROM
					$page $pageindex,
					$revision $revindex
					WHERE $where $join
					ORDER BY page_id";
		} else {
			$sql = "SELECT $straight * FROM
					$page $pageindex,
					$revision $revindex,
					$text
					WHERE $where $join AND rev_text_id=old_id
					ORDER BY page_id";
		}
		$result = $this->db->query( $sql, $fname );
		$wrapper = $this->db->resultObject( $result );
		$this->outputStream( $wrapper );
		
		if ( $this->list_authors ) {
			$this->outputStream( $wrapper );
		}

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
					$output = $this->writer->closePage();
					$this->sink->writeClosePage( $output );
				}
				$output = $this->writer->openPage( $row );
				$this->sink->writeOpenPage( $row, $output );
				$last = $row;
			}
			$output = $this->writer->writeRevision( $row );
			$this->sink->writeRevision( $row, $output );
		}
		if( isset( $last ) ) {
			$output = $this->author_list . $this->writer->closePage();
			$this->sink->writeClosePage( $output );
		}
		$resultset->free();
	}
}

class XmlDumpWriter {

	/**
	 * Returns the export schema version.
	 * @return string
	 */
	function schemaVersion() {
		return "0.3"; // FIXME: upgrade to 0.4 when updated XSD is ready, for the revision deletion bits
	}

	/**
	 * Opens the XML output stream's root <mediawiki> element.
	 * This does not include an xml directive, so is safe to include
	 * as a subelement in a larger XML stream. Namespace and XML Schema
	 * references are included.
	 *
	 * Output will be encoded in UTF-8.
	 *
	 * @return string
	 */
	function openStream() {
		global $wgContLanguageCode;
		$ver = $this->schemaVersion();
		return wfElement( 'mediawiki', array(
			'xmlns'              => "http://www.mediawiki.org/xml/export-$ver/",
			'xmlns:xsi'          => "http://www.w3.org/2001/XMLSchema-instance",
			'xsi:schemaLocation' => "http://www.mediawiki.org/xml/export-$ver/ " .
			                        "http://www.mediawiki.org/xml/export-$ver.xsd",
			'version'            => $ver,
			'xml:lang'           => $wgContLanguageCode ),
			null ) .
			"\n" .
			$this->siteInfo();
	}

	function siteInfo() {
		$info = array(
			$this->sitename(),
			$this->homelink(),
			$this->generator(),
			$this->caseSetting(),
			$this->namespaces() );
		return "  <siteinfo>\n    " .
			implode( "\n    ", $info ) .
			"\n  </siteinfo>\n";
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
		$spaces = "  <namespaces>\n";
		foreach( $wgContLang->getFormattedNamespaces() as $ns => $title ) {
			$spaces .= '      ' . wfElement( 'namespace', array( 'key' => $ns ), $title ) . "\n";
		}
		$spaces .= "    </namespaces>";
		return $spaces;
	}

	/**
	 * Closes the output stream with the closing root element.
	 * Call when finished dumping things.
	 */
	function closeStream() {
		return "</mediawiki>\n";
	}


	/**
	 * Opens a <page> section on the output stream, with data
	 * from the given database row.
	 *
	 * @param object $row
	 * @return string
	 * @access private
	 */
	function openPage( $row ) {
		$out = "  <page>\n";
		$title = Title::makeTitle( $row->page_namespace, $row->page_title );
		$out .= '    ' . wfElementClean( 'title', array(), $title->getPrefixedText() ) . "\n";
		$out .= '    ' . wfElement( 'id', array(), strval( $row->page_id ) ) . "\n";
		if( '' != $row->page_restrictions ) {
			$out .= '    ' . wfElement( 'restrictions', array(),
				strval( $row->page_restrictions ) ) . "\n";
		}
		return $out;
	}

	/**
	 * Closes a <page> section on the output stream.
	 *
	 * @access private
	 */
	function closePage() {
		return "  </page>\n";
	}

	/**
	 * Dumps a <revision> section on the output stream, with
	 * data filled in from the given database row.
	 *
	 * @param object $row
	 * @return string
	 * @access private
	 */
	function writeRevision( $row ) {
		$fname = 'WikiExporter::dumpRev';
		wfProfileIn( $fname );

		$out  = "    <revision>\n";
		$out .= "      " . wfElement( 'id', null, strval( $row->rev_id ) ) . "\n";

		$ts = wfTimestamp( TS_ISO_8601, $row->rev_timestamp );
		$out .= "      " . wfElement( 'timestamp', null, $ts ) . "\n";

		if( $row->rev_deleted & MW_REV_DELETED_USER ) {
			$out .= "      " . wfElement( 'contributor', array( 'deleted' => 'deleted' ) ) . "\n";
		} else {
			$out .= "      <contributor>\n";
			if( $row->rev_user ) {
				$out .= "        " . wfElementClean( 'username', null, strval( $row->rev_user_text ) ) . "\n";
				$out .= "        " . wfElement( 'id', null, strval( $row->rev_user ) ) . "\n";
			} else {
				$out .= "        " . wfElementClean( 'ip', null, strval( $row->rev_user_text ) ) . "\n";
			}
			$out .= "      </contributor>\n";
		}

		if( $row->rev_minor_edit ) {
			$out .=  "      <minor/>\n";
		}
		if( $row->rev_deleted & MW_REV_DELETED_COMMENT ) {
			$out .= "      " . wfElement( 'comment', array( 'deleted' => 'deleted' ) ) . "\n";
		} elseif( $row->rev_comment != '' ) {
			$out .= "      " . wfElementClean( 'comment', null, strval( $row->rev_comment ) ) . "\n";
		}

		if( $row->rev_deleted & MW_REV_DELETED_TEXT ) {
			$out .= "      " . wfElement( 'text', array( 'deleted' => 'deleted' ) ) . "\n";
		} elseif( isset( $row->old_text ) ) {
			// Raw text from the database may have invalid chars
			$text = strval( Revision::getRevisionText( $row ) );
			$out .= "      " . wfElementClean( 'text',
				array( 'xml:space' => 'preserve' ),
				strval( $text ) ) . "\n";
		} else {
			// Stub output
			$out .= "      " . wfElement( 'text',
				array( 'id' => $row->rev_text_id ),
				"" ) . "\n";
		}

		$out .= "    </revision>\n";

		wfProfileOut( $fname );
		return $out;
	}

}


/**
 * Base class for output stream; prints to stdout or buffer or whereever.
 */
class DumpOutput {
	function writeOpenStream( $string ) {
		$this->write( $string );
	}

	function writeCloseStream( $string ) {
		$this->write( $string );
	}

	function writeOpenPage( $page, $string ) {
		$this->write( $string );
	}

	function writeClosePage( $string ) {
		$this->write( $string );
	}

	function writeRevision( $rev, $string ) {
		$this->write( $string );
	}

	/**
	 * Override to write to a different stream type.
	 * @return bool
	 */
	function write( $string ) {
		print $string;
	}
}

/**
 * Stream outputter to send data to a file.
 */
class DumpFileOutput extends DumpOutput {
	var $handle;

	function DumpFileOutput( $file ) {
		$this->handle = fopen( $file, "wt" );
	}

	function write( $string ) {
		fputs( $this->handle, $string );
	}
}

/**
 * Stream outputter to send data to a file via some filter program.
 * Even if compression is available in a library, using a separate
 * program can allow us to make use of a multi-processor system.
 */
class DumpPipeOutput extends DumpFileOutput {
	function DumpPipeOutput( $command, $file = null ) {
		if( !is_null( $file ) ) {
			$command .=  " > " . wfEscapeShellArg( $file );
		}
		$this->handle = popen( $command, "w" );
	}
}

/**
 * Sends dump output via the gzip compressor.
 */
class DumpGZipOutput extends DumpPipeOutput {
	function DumpGZipOutput( $file ) {
		parent::DumpPipeOutput( "gzip", $file );
	}
}

/**
 * Sends dump output via the bgzip2 compressor.
 */
class DumpBZip2Output extends DumpPipeOutput {
	function DumpBZip2Output( $file ) {
		parent::DumpPipeOutput( "bzip2", $file );
	}
}

/**
 * Sends dump output via the p7zip compressor.
 */
class Dump7ZipOutput extends DumpPipeOutput {
	function Dump7ZipOutput( $file ) {
		$command = "7za a -bd -si " . wfEscapeShellArg( $file );
		parent::DumpPipeOutput( $command );
	}
}



/**
 * Dump output filter class.
 * This just does output filtering and streaming; XML formatting is done
 * higher up, so be careful in what you do.
 */
class DumpFilter {
	function DumpFilter( &$sink ) {
		$this->sink =& $sink;
	}

	function writeOpenStream( $string ) {
		$this->sink->writeOpenStream( $string );
	}

	function writeCloseStream( $string ) {
		$this->sink->writeCloseStream( $string );
	}

	function writeOpenPage( $page, $string ) {
		$this->sendingThisPage = $this->pass( $page, $string );
		if( $this->sendingThisPage ) {
			$this->sink->writeOpenPage( $page, $string );
		}
	}

	function writeClosePage( $string ) {
		if( $this->sendingThisPage ) {
			$this->sink->writeClosePage( $string );
			$this->sendingThisPage = false;
		}
	}

	function writeRevision( $rev, $string ) {
		if( $this->sendingThisPage ) {
			$this->sink->writeRevision( $rev, $string );
		}
	}

	/**
	 * Override for page-based filter types.
	 * @return bool
	 */
	function pass( $page, $string ) {
		return true;
	}
}

/**
 * Simple dump output filter to exclude all talk pages.
 */
class DumpNotalkFilter extends DumpFilter {
	function pass( $page ) {
		return !Namespace::isTalk( $page->page_namespace );
	}
}

/**
 * Dump output filter to include or exclude pages in a given set of namespaces.
 */
class DumpNamespaceFilter extends DumpFilter {
	var $invert = false;
	var $namespaces = array();

	function DumpNamespaceFilter( &$sink, $param ) {
		parent::DumpFilter( $sink );

		$constants = array(
			"NS_MAIN"           => NS_MAIN,
			"NS_TALK"           => NS_TALK,
			"NS_USER"           => NS_USER,
			"NS_USER_TALK"      => NS_USER_TALK,
			"NS_PROJECT"        => NS_PROJECT,
			"NS_PROJECT_TALK"   => NS_PROJECT_TALK,
			"NS_IMAGE"          => NS_IMAGE,
			"NS_IMAGE_TALK"     => NS_IMAGE_TALK,
			"NS_MEDIAWIKI"      => NS_MEDIAWIKI,
			"NS_MEDIAWIKI_TALK" => NS_MEDIAWIKI_TALK,
			"NS_TEMPLATE"       => NS_TEMPLATE,
			"NS_TEMPLATE_TALK"  => NS_TEMPLATE_TALK,
			"NS_HELP"           => NS_HELP,
			"NS_HELP_TALK"      => NS_HELP_TALK,
			"NS_CATEGORY"       => NS_CATEGORY,
			"NS_CATEGORY_TALK"  => NS_CATEGORY_TALK );

		if( $param{0} == '!' ) {
			$this->invert = true;
			$param = substr( $param, 1 );
		}

		foreach( explode( ',', $param ) as $key ) {
			$key = trim( $key );
			if( isset( $constants[$key] ) ) {
				$ns = $constants[$key];
				$this->namespaces[$ns] = true;
			} elseif( is_numeric( $key ) ) {
				$ns = intval( $key );
				$this->namespaces[$ns] = true;
			} else {
				wfDie( "Unrecognized namespace key '$key'\n" );
			}
		}
	}

	function pass( $page ) {
		$match = isset( $this->namespaces[$page->page_namespace] );
		return $this->invert xor $match;
	}
}


/**
 * Dump output filter to include only the last revision in each page sequence.
 */
class DumpLatestFilter extends DumpFilter {
	var $page, $pageString, $rev, $revString;

	function writeOpenPage( $page, $string ) {
		$this->page = $page;
		$this->pageString = $string;
	}

	function writeClosePage( $string ) {
		if( $this->rev ) {
			$this->sink->writeOpenPage( $this->page, $this->pageString );
			$this->sink->writeRevision( $this->rev, $this->revString );
			$this->sink->writeClosePage( $string );
		}
		$this->rev = null;
		$this->revString = null;
		$this->page = null;
		$this->pageString = null;
	}

	function writeRevision( $rev, $string ) {
		if( $rev->rev_id == $this->page->page_latest ) {
			$this->rev = $rev;
			$this->revString = $string;
		}
	}
}

/**
 * Base class for output stream; prints to stdout or buffer or whereever.
 */
class DumpMultiWriter {
	function DumpMultiWriter( $sinks ) {
		$this->sinks = $sinks;
		$this->count = count( $sinks );
	}

	function writeOpenStream( $string ) {
		for( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeOpenStream( $string );
		}
	}

	function writeCloseStream( $string ) {
		for( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeCloseStream( $string );
		}
	}

	function writeOpenPage( $page, $string ) {
		for( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeOpenPage( $page, $string );
		}
	}

	function writeClosePage( $string ) {
		for( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeClosePage( $string );
		}
	}

	function writeRevision( $rev, $string ) {
		for( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeRevision( $rev, $string );
		}
	}
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
