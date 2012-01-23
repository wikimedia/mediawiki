<?php
/**
 * Base classes for dumps and export
 *
 * Copyright © 2003, 2005, 2006 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * @defgroup Dump Dump
 */

/**
 * @ingroup SpecialPage Dump
 */
class WikiExporter {
	var $list_authors = false ; # Return distinct author list (when not returning full history)
	var $author_list = "" ;

	var $dumpUploads = false;
	var $dumpUploadFileContents = false;

	const FULL = 1;
	const CURRENT = 2;
	const STABLE = 4; // extension defined
	const LOGS = 8;
	const RANGE = 16;

	const BUFFER = 0;
	const STREAM = 1;

	const TEXT = 0;
	const STUB = 1;

	/**
	 * If using WikiExporter::STREAM to stream a large amount of data,
	 * provide a database connection which is not managed by
	 * LoadBalancer to read from: some history blob types will
	 * make additional queries to pull source data while the
	 * main query is still running.
	 *
	 * @param $db DatabaseBase
	 * @param $history Mixed: one of WikiExporter::FULL, WikiExporter::CURRENT,
	 *                 WikiExporter::RANGE or WikiExporter::STABLE,
	 *                 or an associative array:
	 *                   offset: non-inclusive offset at which to start the query
	 *                   limit: maximum number of rows to return
	 *                   dir: "asc" or "desc" timestamp order
	 * @param $buffer Int: one of WikiExporter::BUFFER or WikiExporter::STREAM
	 * @param $text Int: one of WikiExporter::TEXT or WikiExporter::STUB
	 */
	function __construct( &$db, $history = WikiExporter::CURRENT,
			$buffer = WikiExporter::BUFFER, $text = WikiExporter::TEXT ) {
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
	 * @param $sink mixed
	 */
	public function setOutputSink( &$sink ) {
		$this->sink =& $sink;
	}

	public function openStream() {
		$output = $this->writer->openStream();
		$this->sink->writeOpenStream( $output );
	}

	public function closeStream() {
		$output = $this->writer->closeStream();
		$this->sink->writeCloseStream( $output );
	}

	/**
	 * Dumps a series of page and revision records for all pages
	 * in the database, either including complete history or only
	 * the most recent version.
	 */
	public function allPages() {
		return $this->dumpFrom( '' );
	}

	/**
	 * Dumps a series of page and revision records for those pages
	 * in the database falling within the page_id range given.
	 * @param $start Int: inclusive lower limit (this id is included)
	 * @param $end   Int: Exclusive upper limit (this id is not included)
	 *                   If 0, no upper limit.
	 */
	public function pagesByRange( $start, $end ) {
		$condition = 'page_id >= ' . intval( $start );
		if ( $end ) {
			$condition .= ' AND page_id < ' . intval( $end );
		}
		return $this->dumpFrom( $condition );
	}

	/**
	 * Dumps a series of page and revision records for those pages
	 * in the database with revisions falling within the rev_id range given.
	 * @param $start Int: inclusive lower limit (this id is included)
	 * @param $end   Int: Exclusive upper limit (this id is not included)
	 *                   If 0, no upper limit.
	 */
	public function revsByRange( $start, $end ) {
		$condition = 'rev_id >= ' . intval( $start );
		if ( $end ) {
			$condition .= ' AND rev_id < ' . intval( $end );
		}
		return $this->dumpFrom( $condition );
	}

	/**
	 * @param $title Title
	 */
	public function pageByTitle( $title ) {
		return $this->dumpFrom(
			'page_namespace=' . $title->getNamespace() .
			' AND page_title=' . $this->db->addQuotes( $title->getDBkey() ) );
	}

	public function pageByName( $name ) {
		$title = Title::newFromText( $name );
		if ( is_null( $title ) ) {
			throw new MWException( "Can't export invalid title" );
		} else {
			return $this->pageByTitle( $title );
		}
	}

	public function pagesByName( $names ) {
		foreach ( $names as $name ) {
			$this->pageByName( $name );
		}
	}

	public function allLogs() {
		return $this->dumpFrom( '' );
	}

	public function logsByRange( $start, $end ) {
		$condition = 'log_id >= ' . intval( $start );
		if ( $end ) {
			$condition .= ' AND log_id < ' . intval( $end );
		}
		return $this->dumpFrom( $condition );
	}

	# Generates the distinct list of authors of an article
	# Not called by default (depends on $this->list_authors)
	# Can be set by Special:Export when not exporting whole history
	protected function do_list_authors( $cond ) {
		wfProfileIn( __METHOD__ );
		$this->author_list = "<contributors>";
		// rev_deleted

		$res = $this->db->select(
			array( 'page', 'revision' ),
			array( 'DISTINCT rev_user_text', 'rev_user' ),
			array(
				$this->db->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0',
				$cond,
				'page_id = rev_id',
			),
			__METHOD__
		);

		foreach ( $res as $row ) {
			$this->author_list .= "<contributor>" .
				"<username>" .
				htmlentities( $row->rev_user_text )  .
				"</username>" .
				"<id>" .
				$row->rev_user .
				"</id>" .
				"</contributor>";
		}
		$this->author_list .= "</contributors>";
		wfProfileOut( __METHOD__ );
	}

	protected function dumpFrom( $cond = '' ) {
		wfProfileIn( __METHOD__ );
		# For logging dumps...
		if ( $this->history & self::LOGS ) {
			if ( $this->buffer == WikiExporter::STREAM ) {
				$prev = $this->db->bufferResults( false );
			}
			$where = array( 'user_id = log_user' );
			# Hide private logs
			$hideLogs = LogEventsList::getExcludeClause( $this->db );
			if ( $hideLogs ) $where[] = $hideLogs;
			# Add on any caller specified conditions
			if ( $cond ) $where[] = $cond;
			# Get logging table name for logging.* clause
			$logging = $this->db->tableName( 'logging' );
			$result = $this->db->select( array( 'logging', 'user' ),
				array( "{$logging}.*", 'user_name' ), // grab the user name
				$where,
				__METHOD__,
				array( 'ORDER BY' => 'log_id', 'USE INDEX' => array( 'logging' => 'PRIMARY' ) )
			);
			$wrapper = $this->db->resultObject( $result );
			$this->outputLogStream( $wrapper );
			if ( $this->buffer == WikiExporter::STREAM ) {
				$this->db->bufferResults( $prev );
			}
		# For page dumps...
		} else {
			$tables = array( 'page', 'revision' );
			$opts = array( 'ORDER BY' => 'page_id ASC' );
			$opts['USE INDEX'] = array();
			$join = array();
			if ( is_array( $this->history ) ) {
				# Time offset/limit for all pages/history...
				$revJoin = 'page_id=rev_page';
				# Set time order
				if ( $this->history['dir'] == 'asc' ) {
					$op = '>';
					$opts['ORDER BY'] = 'rev_timestamp ASC';
				} else {
					$op = '<';
					$opts['ORDER BY'] = 'rev_timestamp DESC';
				}
				# Set offset
				if ( !empty( $this->history['offset'] ) ) {
					$revJoin .= " AND rev_timestamp $op " .
						$this->db->addQuotes( $this->db->timestamp( $this->history['offset'] ) );
				}
				$join['revision'] = array( 'INNER JOIN', $revJoin );
				# Set query limit
				if ( !empty( $this->history['limit'] ) ) {
					$opts['LIMIT'] = intval( $this->history['limit'] );
				}
			} elseif ( $this->history & WikiExporter::FULL ) {
				# Full history dumps...
				$join['revision'] = array( 'INNER JOIN', 'page_id=rev_page' );
			} elseif ( $this->history & WikiExporter::CURRENT ) {
				# Latest revision dumps...
				if ( $this->list_authors && $cond != '' )  { // List authors, if so desired
					$this->do_list_authors( $cond );
				}
				$join['revision'] = array( 'INNER JOIN', 'page_id=rev_page AND page_latest=rev_id' );
			} elseif ( $this->history & WikiExporter::STABLE ) {
				# "Stable" revision dumps...
				# Default JOIN, to be overridden...
				$join['revision'] = array( 'INNER JOIN', 'page_id=rev_page AND page_latest=rev_id' );
				# One, and only one hook should set this, and return false
				if ( wfRunHooks( 'WikiExporter::dumpStableQuery', array( &$tables, &$opts, &$join ) ) ) {
					wfProfileOut( __METHOD__ );
					throw new MWException( __METHOD__ . " given invalid history dump type." );
				}
			} elseif ( $this->history & WikiExporter::RANGE ) {
				# Dump of revisions within a specified range
				$join['revision'] = array( 'INNER JOIN', 'page_id=rev_page' );
				$opts['ORDER BY'] = 'rev_page ASC, rev_id ASC';
			} else {
				# Uknown history specification parameter?
				wfProfileOut( __METHOD__ );
				throw new MWException( __METHOD__ . " given invalid history dump type." );
			}
			# Query optimization hacks
			if ( $cond == '' ) {
				$opts[] = 'STRAIGHT_JOIN';
				$opts['USE INDEX']['page'] = 'PRIMARY';
			}
			# Build text join options
			if ( $this->text != WikiExporter::STUB ) { // 1-pass
				$tables[] = 'text';
				$join['text'] = array( 'INNER JOIN', 'rev_text_id=old_id' );
			}

			if ( $this->buffer == WikiExporter::STREAM ) {
				$prev = $this->db->bufferResults( false );
			}

			wfRunHooks( 'ModifyExportQuery',
						array( $this->db, &$tables, &$cond, &$opts, &$join ) );

			# Do the query!
			$result = $this->db->select( $tables, '*', $cond, __METHOD__, $opts, $join );
			$wrapper = $this->db->resultObject( $result );
			# Output dump results
			$this->outputPageStream( $wrapper );
			if ( $this->list_authors ) {
				$this->outputPageStream( $wrapper );
			}

			if ( $this->buffer == WikiExporter::STREAM ) {
				$this->db->bufferResults( $prev );
			}
		}
		wfProfileOut( __METHOD__ );
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
	 * @param $resultset ResultWrapper
	 */
	protected function outputPageStream( $resultset ) {
		$last = null;
		foreach ( $resultset as $row ) {
			if ( is_null( $last ) ||
				$last->page_namespace != $row->page_namespace ||
				$last->page_title     != $row->page_title ) {
				if ( isset( $last ) ) {
					$output = '';
					if ( $this->dumpUploads ) {
						$output .= $this->writer->writeUploads( $last, $this->dumpUploadFileContents );
					}
					$output .= $this->writer->closePage();
					$this->sink->writeClosePage( $output );
				}
				$output = $this->writer->openPage( $row );
				$this->sink->writeOpenPage( $row, $output );
				$last = $row;
			}
			$output = $this->writer->writeRevision( $row );
			$this->sink->writeRevision( $row, $output );
		}
		if ( isset( $last ) ) {
			$output = '';
			if ( $this->dumpUploads ) {
				$output .= $this->writer->writeUploads( $last, $this->dumpUploadFileContents );
			}
			$output .= $this->author_list;
			$output .= $this->writer->closePage();
			$this->sink->writeClosePage( $output );
		}
	}

	protected function outputLogStream( $resultset ) {
		foreach ( $resultset as $row ) {
			$output = $this->writer->writeLogItem( $row );
			$this->sink->writeLogItem( $row, $output );
		}
	}
}

/**
 * @ingroup Dump
 */
class XmlDumpWriter {
	/**
	 * Returns the export schema version.
	 * @return string
	 */
	function schemaVersion() {
		return "0.6";
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
		global $wgLanguageCode;
		$ver = $this->schemaVersion();
		return Xml::element( 'mediawiki', array(
			'xmlns'              => "http://www.mediawiki.org/xml/export-$ver/",
			'xmlns:xsi'          => "http://www.w3.org/2001/XMLSchema-instance",
			'xsi:schemaLocation' => "http://www.mediawiki.org/xml/export-$ver/ " .
			                        "http://www.mediawiki.org/xml/export-$ver.xsd",
			'version'            => $ver,
			'xml:lang'           => $wgLanguageCode ),
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
		return Xml::element( 'sitename', array(), $wgSitename );
	}

	function generator() {
		global $wgVersion;
		return Xml::element( 'generator', array(), "MediaWiki $wgVersion" );
	}

	function homelink() {
		return Xml::element( 'base', array(), Title::newMainPage()->getCanonicalUrl() );
	}

	function caseSetting() {
		global $wgCapitalLinks;
		// "case-insensitive" option is reserved for future
		$sensitivity = $wgCapitalLinks ? 'first-letter' : 'case-sensitive';
		return Xml::element( 'case', array(), $sensitivity );
	}

	function namespaces() {
		global $wgContLang;
		$spaces = "<namespaces>\n";
		foreach ( $wgContLang->getFormattedNamespaces() as $ns => $title ) {
			$spaces .= '      ' .
				Xml::element( 'namespace',
					array(	'key' => $ns,
							'case' => MWNamespace::isCapitalized( $ns ) ? 'first-letter' : 'case-sensitive',
					), $title ) . "\n";
		}
		$spaces .= "    </namespaces>";
		return $spaces;
	}

	/**
	 * Closes the output stream with the closing root element.
	 * Call when finished dumping things.
	 *
	 * @return string
	 */
	function closeStream() {
		return "</mediawiki>\n";
	}

	/**
	 * Opens a <page> section on the output stream, with data
	 * from the given database row.
	 *
	 * @param $row object
	 * @return string
	 * @access private
	 */
	function openPage( $row ) {
		$out = "  <page>\n";
		$title = Title::makeTitle( $row->page_namespace, $row->page_title );
		$out .= '    ' . Xml::elementClean( 'title', array(), self::canonicalTitle( $title ) ) . "\n";
		$out .= '    ' . Xml::element( 'ns', array(), strval( $row->page_namespace) ) . "\n";
		$out .= '    ' . Xml::element( 'id', array(), strval( $row->page_id ) ) . "\n";
		if ( $row->page_is_redirect ) {
			$page = WikiPage::factory( $title );
			$redirect = $page->getRedirectTarget();
			if ( $redirect instanceOf Title && $redirect->isValidRedirectTarget() ) {
				$out .= '    ' . Xml::element( 'redirect', array( 'title' => self::canonicalTitle( $redirect ) ) ) . "\n";
			}
		}
		
		if ( $row->rev_sha1 ) {
			$out .= "      " . Xml::element('sha1', null, strval($row->rev_sha1) ) . "\n";
		} else {
			$out .= "      <sha1/>\n";
		}
		
		if ( $row->page_restrictions != '' ) {
			$out .= '    ' . Xml::element( 'restrictions', array(),
				strval( $row->page_restrictions ) ) . "\n";
		}

		wfRunHooks( 'XmlDumpWriterOpenPage', array( $this, &$out, $row, $title ) );

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
	 * @param $row object
	 * @return string
	 * @access private
	 */
	function writeRevision( $row ) {
		wfProfileIn( __METHOD__ );

		$out  = "    <revision>\n";
		$out .= "      " . Xml::element( 'id', null, strval( $row->rev_id ) ) . "\n";

		$out .= $this->writeTimestamp( $row->rev_timestamp );

		if ( $row->rev_deleted & Revision::DELETED_USER ) {
			$out .= "      " . Xml::element( 'contributor', array( 'deleted' => 'deleted' ) ) . "\n";
		} else {
			$out .= $this->writeContributor( $row->rev_user, $row->rev_user_text );
		}

		if ( $row->rev_minor_edit ) {
			$out .=  "      <minor/>\n";
		}
		if ( $row->rev_deleted & Revision::DELETED_COMMENT ) {
			$out .= "      " . Xml::element( 'comment', array( 'deleted' => 'deleted' ) ) . "\n";
		} elseif ( $row->rev_comment != '' ) {
			$out .= "      " . Xml::elementClean( 'comment', null, strval( $row->rev_comment ) ) . "\n";
		}

		$text = '';
		if ( $row->rev_deleted & Revision::DELETED_TEXT ) {
			$out .= "      " . Xml::element( 'text', array( 'deleted' => 'deleted' ) ) . "\n";
		} elseif ( isset( $row->old_text ) ) {
			// Raw text from the database may have invalid chars
			$text = strval( Revision::getRevisionText( $row ) );
			$out .= "      " . Xml::elementClean( 'text',
				array( 'xml:space' => 'preserve', 'bytes' => intval( $row->rev_len ) ),
				strval( $text ) ) . "\n";
		} else {
			// Stub output
			$out .= "      " . Xml::element( 'text',
				array( 'id' => $row->rev_text_id, 'bytes' => intval( $row->rev_len ) ),
				"" ) . "\n";
		}

		wfRunHooks( 'XmlDumpWriterWriteRevision', array( &$this, &$out, $row, $text ) );

		$out .= "    </revision>\n";

		wfProfileOut( __METHOD__ );
		return $out;
	}

	/**
	 * Dumps a <logitem> section on the output stream, with
	 * data filled in from the given database row.
	 *
	 * @param $row object
	 * @return string
	 * @access private
	 */
	function writeLogItem( $row ) {
		wfProfileIn( __METHOD__ );

		$out  = "    <logitem>\n";
		$out .= "      " . Xml::element( 'id', null, strval( $row->log_id ) ) . "\n";

		$out .= $this->writeTimestamp( $row->log_timestamp );

		if ( $row->log_deleted & LogPage::DELETED_USER ) {
			$out .= "      " . Xml::element( 'contributor', array( 'deleted' => 'deleted' ) ) . "\n";
		} else {
			$out .= $this->writeContributor( $row->log_user, $row->user_name );
		}

		if ( $row->log_deleted & LogPage::DELETED_COMMENT ) {
			$out .= "      " . Xml::element( 'comment', array( 'deleted' => 'deleted' ) ) . "\n";
		} elseif ( $row->log_comment != '' ) {
			$out .= "      " . Xml::elementClean( 'comment', null, strval( $row->log_comment ) ) . "\n";
		}

		$out .= "      " . Xml::element( 'type', null, strval( $row->log_type ) ) . "\n";
		$out .= "      " . Xml::element( 'action', null, strval( $row->log_action ) ) . "\n";

		if ( $row->log_deleted & LogPage::DELETED_ACTION ) {
			$out .= "      " . Xml::element( 'text', array( 'deleted' => 'deleted' ) ) . "\n";
		} else {
			$title = Title::makeTitle( $row->log_namespace, $row->log_title );
			$out .= "      " . Xml::elementClean( 'logtitle', null, self::canonicalTitle( $title ) ) . "\n";
			$out .= "      " . Xml::elementClean( 'params',
				array( 'xml:space' => 'preserve' ),
				strval( $row->log_params ) ) . "\n";
		}

		$out .= "    </logitem>\n";

		wfProfileOut( __METHOD__ );
		return $out;
	}

	function writeTimestamp( $timestamp ) {
		$ts = wfTimestamp( TS_ISO_8601, $timestamp );
		return "      " . Xml::element( 'timestamp', null, $ts ) . "\n";
	}

	function writeContributor( $id, $text ) {
		$out = "      <contributor>\n";
		if ( $id || !IP::isValid( $text ) ) {
			$out .= "        " . Xml::elementClean( 'username', null, strval( $text ) ) . "\n";
			$out .= "        " . Xml::element( 'id', null, strval( $id ) ) . "\n";
		} else {
			$out .= "        " . Xml::elementClean( 'ip', null, strval( $text ) ) . "\n";
		}
		$out .= "      </contributor>\n";
		return $out;
	}

	/**
	 * Warning! This data is potentially inconsistent. :(
	 */
	function writeUploads( $row, $dumpContents = false ) {
		if ( $row->page_namespace == NS_IMAGE ) {
			$img = wfLocalFile( $row->page_title );
			if ( $img && $img->exists() ) {
				$out = '';
				foreach ( array_reverse( $img->getHistory() ) as $ver ) {
					$out .= $this->writeUpload( $ver, $dumpContents );
				}
				$out .= $this->writeUpload( $img, $dumpContents );
				return $out;
			}
		}
		return '';
	}

	/**
	 * @param $file File
	 * @param $dumpContents bool
	 * @return string
	 */
	function writeUpload( $file, $dumpContents = false ) {
		if ( $file->isOld() ) {
			$archiveName = "      " .
				Xml::element( 'archivename', null, $file->getArchiveName() ) . "\n";
		} else {
			$archiveName = '';
		}
		if ( $dumpContents ) {
			# Dump file as base64
			# Uses only XML-safe characters, so does not need escaping
			$contents = '      <contents encoding="base64">' .
				chunk_split( base64_encode( file_get_contents( $file->getPath() ) ) ) .
				"      </contents>\n";
		} else {
			$contents = '';
		}
		return "    <upload>\n" .
			$this->writeTimestamp( $file->getTimestamp() ) .
			$this->writeContributor( $file->getUser( 'id' ), $file->getUser( 'text' ) ) .
			"      " . Xml::elementClean( 'comment', null, $file->getDescription() ) . "\n" .
			"      " . Xml::element( 'filename', null, $file->getName() ) . "\n" .
			$archiveName .
			"      " . Xml::element( 'src', null, $file->getCanonicalUrl() ) . "\n" .
			"      " . Xml::element( 'size', null, $file->getSize() ) . "\n" .
			"      " . Xml::element( 'sha1base36', null, $file->getSha1() ) . "\n" .
			"      " . Xml::element( 'rel', null, $file->getRel() ) . "\n" .
			$contents .
			"    </upload>\n";
	}

	/**
	 * Return prefixed text form of title, but using the content language's
	 * canonical namespace. This skips any special-casing such as gendered
	 * user namespaces -- which while useful, are not yet listed in the
	 * XML <siteinfo> data so are unsafe in export.
	 *
	 * @param Title $title
	 * @return string
	 * @since 1.18
	 */
	public static function canonicalTitle( Title $title ) {
		if ( $title->getInterwiki() ) {
			return $title->getPrefixedText();
		}

		global $wgContLang;
		$prefix = str_replace( '_', ' ', $wgContLang->getNsText( $title->getNamespace() ) );

		if ( $prefix !== '' ) {
			$prefix .= ':';
		}

		return $prefix . $title->getText();
	}
}


/**
 * Base class for output stream; prints to stdout or buffer or whereever.
 * @ingroup Dump
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

	function writeLogItem( $rev, $string ) {
		$this->write( $string );
	}

	/**
	 * Override to write to a different stream type.
	 * @return bool
	 */
	function write( $string ) {
		print $string;
	}

	/**
	 * Close the old file, move it to a specified name,
	 * and reopen new file with the old name. Use this
	 * for writing out a file in multiple pieces
	 * at specified checkpoints (e.g. every n hours).
	 * @param $newname mixed File name. May be a string or an array with one element
	 */
	function closeRenameAndReopen( $newname ) {
		return;
	}

	/**
	 * Close the old file, and move it to a specified name.
	 * Use this for the last piece of a file written out
	 * at specified checkpoints (e.g. every n hours).
	 * @param $newname mixed File name. May be a string or an array with one element
	 * @param $open bool If true, a new file with the old filename will be opened again for writing (default: false)
	 */
	function closeAndRename( $newname, $open = false ) {
		return;
	}

	/**
	 * Returns the name of the file or files which are
	 * being written to, if there are any.
	 */
	function getFilenames() {
		return NULL;
	}
}

/**
 * Stream outputter to send data to a file.
 * @ingroup Dump
 */
class DumpFileOutput extends DumpOutput {
	protected $handle, $filename;

	function __construct( $file ) {
		$this->handle = fopen( $file, "wt" );
		$this->filename = $file;
	}

	function write( $string ) {
		fputs( $this->handle, $string );
	}

	function closeRenameAndReopen( $newname ) {
		$this->closeAndRename( $newname, true );
	}

	function renameOrException( $newname ) {
			if (! rename( $this->filename, $newname ) ) {
				throw new MWException( __METHOD__ . ": rename of file {$this->filename} to $newname failed\n" );
			}
	}

	function checkRenameArgCount( $newname ) {
		if ( is_array( $newname ) ) {
			if ( count( $newname ) > 1 ) {
				throw new MWException( __METHOD__ . ": passed multiple arguments for rename of single file\n" );
			} else {
				$newname = $newname[0];
			}
		}
		return $newname;
	}

	function closeAndRename( $newname, $open = false ) {
		$newname = $this->checkRenameArgCount( $newname );
		if ( $newname ) {
			fclose( $this->handle );
			$this->renameOrException( $newname );
			if ( $open ) {
				$this->handle = fopen( $this->filename, "wt" );
			}
		}
	}

	function getFilenames() {
		return $this->filename;
	}
}

/**
 * Stream outputter to send data to a file via some filter program.
 * Even if compression is available in a library, using a separate
 * program can allow us to make use of a multi-processor system.
 * @ingroup Dump
 */
class DumpPipeOutput extends DumpFileOutput {
	protected $command, $filename;

	function __construct( $command, $file = null ) {
		if ( !is_null( $file ) ) {
			$command .=  " > " . wfEscapeShellArg( $file );
		}

		$this->startCommand( $command );
		$this->command = $command;
		$this->filename = $file;
	}

	function startCommand( $command ) {
		$spec = array(
			0 => array( "pipe", "r" ),
		);
		$pipes = array();
		$this->procOpenResource = proc_open( $command, $spec, $pipes );
		$this->handle = $pipes[0];
	}

	function closeRenameAndReopen( $newname ) {
		$this->closeAndRename( $newname, true );
	}

	function closeAndRename( $newname, $open = false ) {
		$newname = $this->checkRenameArgCount( $newname );
		if ( $newname ) {
			fclose( $this->handle );
			proc_close( $this->procOpenResource );
			$this->renameOrException( $newname );
			if ( $open ) {
				$command = $this->command;
				$command .=  " > " . wfEscapeShellArg( $this->filename );
				$this->startCommand( $command );
			}
		}
	}

}

/**
 * Sends dump output via the gzip compressor.
 * @ingroup Dump
 */
class DumpGZipOutput extends DumpPipeOutput {
	function __construct( $file ) {
		parent::__construct( "gzip", $file );
	}
}

/**
 * Sends dump output via the bgzip2 compressor.
 * @ingroup Dump
 */
class DumpBZip2Output extends DumpPipeOutput {
	function __construct( $file ) {
		parent::__construct( "bzip2", $file );
	}
}

/**
 * Sends dump output via the p7zip compressor.
 * @ingroup Dump
 */
class Dump7ZipOutput extends DumpPipeOutput {
	function __construct( $file ) {
		$command = $this->setup7zCommand( $file );
		parent::__construct( $command );
		$this->filename = $file;
	}

	function setup7zCommand( $file ) {
		$command = "7za a -bd -si " . wfEscapeShellArg( $file );
		// Suppress annoying useless crap from p7zip
		// Unfortunately this could suppress real error messages too
		$command .= ' >' . wfGetNull() . ' 2>&1';
		return( $command );
	}

	function closeAndRename( $newname, $open = false ) {
		$newname = $this->checkRenameArgCount( $newname );
		if ( $newname ) {
			fclose( $this->handle );
			proc_close( $this->procOpenResource );
			$this->renameOrException( $newname );
			if ( $open ) {
				$command = $this->setup7zCommand( $this->filename );
				$this->startCommand( $command );
			}
		}
	}
}



/**
 * Dump output filter class.
 * This just does output filtering and streaming; XML formatting is done
 * higher up, so be careful in what you do.
 * @ingroup Dump
 */
class DumpFilter {
	function __construct( &$sink ) {
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
		if ( $this->sendingThisPage ) {
			$this->sink->writeOpenPage( $page, $string );
		}
	}

	function writeClosePage( $string ) {
		if ( $this->sendingThisPage ) {
			$this->sink->writeClosePage( $string );
			$this->sendingThisPage = false;
		}
	}

	function writeRevision( $rev, $string ) {
		if ( $this->sendingThisPage ) {
			$this->sink->writeRevision( $rev, $string );
		}
	}

	function writeLogItem( $rev, $string ) {
		$this->sink->writeRevision( $rev, $string );
	}

	function closeRenameAndReopen( $newname ) {
		$this->sink->closeRenameAndReopen( $newname );
	}

	function closeAndRename( $newname, $open = false ) {
		$this->sink->closeAndRename( $newname, $open );
	}

	function getFilenames() {
		return $this->sink->getFilenames();
	}

	/**
	 * Override for page-based filter types.
	 * @return bool
	 */
	function pass( $page ) {
		return true;
	}
}

/**
 * Simple dump output filter to exclude all talk pages.
 * @ingroup Dump
 */
class DumpNotalkFilter extends DumpFilter {
	function pass( $page ) {
		return !MWNamespace::isTalk( $page->page_namespace );
	}
}

/**
 * Dump output filter to include or exclude pages in a given set of namespaces.
 * @ingroup Dump
 */
class DumpNamespaceFilter extends DumpFilter {
	var $invert = false;
	var $namespaces = array();

	function __construct( &$sink, $param ) {
		parent::__construct( $sink );

		$constants = array(
			"NS_MAIN"           => NS_MAIN,
			"NS_TALK"           => NS_TALK,
			"NS_USER"           => NS_USER,
			"NS_USER_TALK"      => NS_USER_TALK,
			"NS_PROJECT"        => NS_PROJECT,
			"NS_PROJECT_TALK"   => NS_PROJECT_TALK,
			"NS_FILE"           => NS_FILE,
			"NS_FILE_TALK"      => NS_FILE_TALK,
			"NS_IMAGE"          => NS_IMAGE,  // NS_IMAGE is an alias for NS_FILE
			"NS_IMAGE_TALK"     => NS_IMAGE_TALK,
			"NS_MEDIAWIKI"      => NS_MEDIAWIKI,
			"NS_MEDIAWIKI_TALK" => NS_MEDIAWIKI_TALK,
			"NS_TEMPLATE"       => NS_TEMPLATE,
			"NS_TEMPLATE_TALK"  => NS_TEMPLATE_TALK,
			"NS_HELP"           => NS_HELP,
			"NS_HELP_TALK"      => NS_HELP_TALK,
			"NS_CATEGORY"       => NS_CATEGORY,
			"NS_CATEGORY_TALK"  => NS_CATEGORY_TALK );

		if ( $param { 0 } == '!' ) {
			$this->invert = true;
			$param = substr( $param, 1 );
		}

		foreach ( explode( ',', $param ) as $key ) {
			$key = trim( $key );
			if ( isset( $constants[$key] ) ) {
				$ns = $constants[$key];
				$this->namespaces[$ns] = true;
			} elseif ( is_numeric( $key ) ) {
				$ns = intval( $key );
				$this->namespaces[$ns] = true;
			} else {
				throw new MWException( "Unrecognized namespace key '$key'\n" );
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
 * @ingroup Dump
 */
class DumpLatestFilter extends DumpFilter {
	var $page, $pageString, $rev, $revString;

	function writeOpenPage( $page, $string ) {
		$this->page = $page;
		$this->pageString = $string;
	}

	function writeClosePage( $string ) {
		if ( $this->rev ) {
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
		if ( $rev->rev_id == $this->page->page_latest ) {
			$this->rev = $rev;
			$this->revString = $string;
		}
	}
}

/**
 * Base class for output stream; prints to stdout or buffer or whereever.
 * @ingroup Dump
 */
class DumpMultiWriter {
	function __construct( $sinks ) {
		$this->sinks = $sinks;
		$this->count = count( $sinks );
	}

	function writeOpenStream( $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeOpenStream( $string );
		}
	}

	function writeCloseStream( $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeCloseStream( $string );
		}
	}

	function writeOpenPage( $page, $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeOpenPage( $page, $string );
		}
	}

	function writeClosePage( $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeClosePage( $string );
		}
	}

	function writeRevision( $rev, $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeRevision( $rev, $string );
		}
	}

	function closeRenameAndReopen( $newnames ) {
		$this->closeAndRename( $newnames, true );
	}

	function closeAndRename( $newnames, $open = false ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->closeAndRename( $newnames[$i], $open );
		}
	}

	function getFilenames() {
		$filenames = array();
		for ( $i = 0; $i < $this->count; $i++ ) {
			$filenames[] =  $this->sinks[$i]->getFilenames();
		}
		return $filenames;
	}

}

function xmlsafe( $string ) {
	wfProfileIn( __FUNCTION__ );

	/**
	 * The page may contain old data which has not been properly normalized.
	 * Invalid UTF-8 sequences or forbidden control characters will make our
	 * XML output invalid, so be sure to strip them out.
	 */
	$string = UtfNormal::cleanUp( $string );

	$string = htmlspecialchars( $string );
	wfProfileOut( __FUNCTION__ );
	return $string;
}
