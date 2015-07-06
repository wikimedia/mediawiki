<?php
/**
 * Base classes for dumps and export
 *
 * Copyright © 2003, 2005, 2006 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
	/** @var bool Return distinct author list (when not returning full history) */
	public $list_authors = false;

	/** @var bool */
	public $dumpUploads = false;

	/** @var bool */
	public $dumpUploadFileContents = false;

	/** @var string */
	public $author_list = "";

	const FULL = 1;
	const CURRENT = 2;
	const STABLE = 4; // extension defined
	const LOGS = 8;
	const RANGE = 16;

	const BUFFER = 0;
	const STREAM = 1;

	const TEXT = 0;
	const STUB = 1;

	/** @var int */
	public $buffer;

	/** @var int */
	public $text;

	/** @var DumpOutput */
	public $sink;

	/**
	 * Returns the export schema version.
	 * @return string
	 */
	public static function schemaVersion() {
		return "0.10";
	}

	/**
	 * If using WikiExporter::STREAM to stream a large amount of data,
	 * provide a database connection which is not managed by
	 * LoadBalancer to read from: some history blob types will
	 * make additional queries to pull source data while the
	 * main query is still running.
	 *
	 * @param DatabaseBase $db
	 * @param int|array $history One of WikiExporter::FULL, WikiExporter::CURRENT,
	 *   WikiExporter::RANGE or WikiExporter::STABLE, or an associative array:
	 *   - offset: non-inclusive offset at which to start the query
	 *   - limit: maximum number of rows to return
	 *   - dir: "asc" or "desc" timestamp order
	 * @param int $buffer One of WikiExporter::BUFFER or WikiExporter::STREAM
	 * @param int $text One of WikiExporter::TEXT or WikiExporter::STUB
	 */
	function __construct( $db, $history = WikiExporter::CURRENT,
			$buffer = WikiExporter::BUFFER, $text = WikiExporter::TEXT ) {
		$this->db = $db;
		$this->history = $history;
		$this->buffer = $buffer;
		$this->writer = new XmlDumpWriter();
		$this->sink = new DumpOutput();
		$this->text = $text;
	}

	/**
	 * Set the DumpOutput or DumpFilter object which will receive
	 * various row objects and XML output for filtering. Filters
	 * can be chained or used as callbacks.
	 *
	 * @param DumpOutput $sink
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
		$this->dumpFrom( '' );
	}

	/**
	 * Dumps a series of page and revision records for those pages
	 * in the database falling within the page_id range given.
	 * @param int $start Inclusive lower limit (this id is included)
	 * @param int $end Exclusive upper limit (this id is not included)
	 *   If 0, no upper limit.
	 */
	public function pagesByRange( $start, $end ) {
		$condition = 'page_id >= ' . intval( $start );
		if ( $end ) {
			$condition .= ' AND page_id < ' . intval( $end );
		}
		$this->dumpFrom( $condition );
	}

	/**
	 * Dumps a series of page and revision records for those pages
	 * in the database with revisions falling within the rev_id range given.
	 * @param int $start Inclusive lower limit (this id is included)
	 * @param int $end Exclusive upper limit (this id is not included)
	 *   If 0, no upper limit.
	 */
	public function revsByRange( $start, $end ) {
		$condition = 'rev_id >= ' . intval( $start );
		if ( $end ) {
			$condition .= ' AND rev_id < ' . intval( $end );
		}
		$this->dumpFrom( $condition );
	}

	/**
	 * @param Title $title
	 */
	public function pageByTitle( $title ) {
		$this->dumpFrom(
			'page_namespace=' . $title->getNamespace() .
			' AND page_title=' . $this->db->addQuotes( $title->getDBkey() ) );
	}

	/**
	 * @param string $name
	 * @throws MWException
	 */
	public function pageByName( $name ) {
		$title = Title::newFromText( $name );
		if ( is_null( $title ) ) {
			throw new MWException( "Can't export invalid title" );
		} else {
			$this->pageByTitle( $title );
		}
	}

	/**
	 * @param array $names
	 */
	public function pagesByName( $names ) {
		foreach ( $names as $name ) {
			$this->pageByName( $name );
		}
	}

	public function allLogs() {
		$this->dumpFrom( '' );
	}

	/**
	 * @param int $start
	 * @param int $end
	 */
	public function logsByRange( $start, $end ) {
		$condition = 'log_id >= ' . intval( $start );
		if ( $end ) {
			$condition .= ' AND log_id < ' . intval( $end );
		}
		$this->dumpFrom( $condition );
	}

	/**
	 * Generates the distinct list of authors of an article
	 * Not called by default (depends on $this->list_authors)
	 * Can be set by Special:Export when not exporting whole history
	 *
	 * @param array $cond
	 */
	protected function do_list_authors( $cond ) {
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
				htmlentities( $row->rev_user_text ) .
				"</username>" .
				"<id>" .
				$row->rev_user .
				"</id>" .
				"</contributor>";
		}
		$this->author_list .= "</contributors>";
	}

	/**
	 * @param string $cond
	 * @throws MWException
	 * @throws Exception
	 */
	protected function dumpFrom( $cond = '' ) {
		# For logging dumps...
		if ( $this->history & self::LOGS ) {
			$where = array( 'user_id = log_user' );
			# Hide private logs
			$hideLogs = LogEventsList::getExcludeClause( $this->db );
			if ( $hideLogs ) {
				$where[] = $hideLogs;
			}
			# Add on any caller specified conditions
			if ( $cond ) {
				$where[] = $cond;
			}
			# Get logging table name for logging.* clause
			$logging = $this->db->tableName( 'logging' );

			if ( $this->buffer == WikiExporter::STREAM ) {
				$prev = $this->db->bufferResults( false );
			}
			$result = null; // Assuring $result is not undefined, if exception occurs early
			try {
				$result = $this->db->select( array( 'logging', 'user' ),
					array( "{$logging}.*", 'user_name' ), // grab the user name
					$where,
					__METHOD__,
					array( 'ORDER BY' => 'log_id', 'USE INDEX' => array( 'logging' => 'PRIMARY' ) )
				);
				$this->outputLogStream( $result );
				if ( $this->buffer == WikiExporter::STREAM ) {
					$this->db->bufferResults( $prev );
				}
			} catch ( Exception $e ) {
				// Throwing the exception does not reliably free the resultset, and
				// would also leave the connection in unbuffered mode.

				// Freeing result
				try {
					if ( $result ) {
						$result->free();
					}
				} catch ( Exception $e2 ) {
					// Already in panic mode -> ignoring $e2 as $e has
					// higher priority
				}

				// Putting database back in previous buffer mode
				try {
					if ( $this->buffer == WikiExporter::STREAM ) {
						$this->db->bufferResults( $prev );
					}
				} catch ( Exception $e2 ) {
					// Already in panic mode -> ignoring $e2 as $e has
					// higher priority
				}

				// Inform caller about problem
				throw $e;
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
				if ( $this->list_authors && $cond != '' ) { // List authors, if so desired
					$this->do_list_authors( $cond );
				}
				$join['revision'] = array( 'INNER JOIN', 'page_id=rev_page AND page_latest=rev_id' );
			} elseif ( $this->history & WikiExporter::STABLE ) {
				# "Stable" revision dumps...
				# Default JOIN, to be overridden...
				$join['revision'] = array( 'INNER JOIN', 'page_id=rev_page AND page_latest=rev_id' );
				# One, and only one hook should set this, and return false
				if ( Hooks::run( 'WikiExporter::dumpStableQuery', array( &$tables, &$opts, &$join ) ) ) {
					throw new MWException( __METHOD__ . " given invalid history dump type." );
				}
			} elseif ( $this->history & WikiExporter::RANGE ) {
				# Dump of revisions within a specified range
				$join['revision'] = array( 'INNER JOIN', 'page_id=rev_page' );
				$opts['ORDER BY'] = array( 'rev_page ASC', 'rev_id ASC' );
			} else {
				# Unknown history specification parameter?
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

			$result = null; // Assuring $result is not undefined, if exception occurs early
			try {
				Hooks::run( 'ModifyExportQuery',
						array( $this->db, &$tables, &$cond, &$opts, &$join ) );

				# Do the query!
				$result = $this->db->select( $tables, '*', $cond, __METHOD__, $opts, $join );
				# Output dump results
				$this->outputPageStream( $result );

				if ( $this->buffer == WikiExporter::STREAM ) {
					$this->db->bufferResults( $prev );
				}
			} catch ( Exception $e ) {
				// Throwing the exception does not reliably free the resultset, and
				// would also leave the connection in unbuffered mode.

				// Freeing result
				try {
					if ( $result ) {
						$result->free();
					}
				} catch ( Exception $e2 ) {
					// Already in panic mode -> ignoring $e2 as $e has
					// higher priority
				}

				// Putting database back in previous buffer mode
				try {
					if ( $this->buffer == WikiExporter::STREAM ) {
						$this->db->bufferResults( $prev );
					}
				} catch ( Exception $e2 ) {
					// Already in panic mode -> ignoring $e2 as $e has
					// higher priority
				}

				// Inform caller about problem
				throw $e;
			}
		}
	}

	/**
	 * Runs through a query result set dumping page and revision records.
	 * The result set should be sorted/grouped by page to avoid duplicate
	 * page records in the output.
	 *
	 * Should be safe for
	 * streaming (non-buffered) queries, as long as it was made on a
	 * separate database connection not managed by LoadBalancer; some
	 * blob storage types will make queries to pull source data.
	 *
	 * @param ResultWrapper $resultset
	 */
	protected function outputPageStream( $resultset ) {
		$last = null;
		foreach ( $resultset as $row ) {
			if ( $last === null ||
				$last->page_namespace != $row->page_namespace ||
				$last->page_title != $row->page_title ) {
				if ( $last !== null ) {
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
		if ( $last !== null ) {
			$output = '';
			if ( $this->dumpUploads ) {
				$output .= $this->writer->writeUploads( $last, $this->dumpUploadFileContents );
			}
			$output .= $this->author_list;
			$output .= $this->writer->closePage();
			$this->sink->writeClosePage( $output );
		}
	}

	/**
	 * @param ResultWrapper $resultset
	 */
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
	 * Opens the XML output stream's root "<mediawiki>" element.
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
		$ver = WikiExporter::schemaVersion();
		return Xml::element( 'mediawiki', array(
			'xmlns'              => "http://www.mediawiki.org/xml/export-$ver/",
			'xmlns:xsi'          => "http://www.w3.org/2001/XMLSchema-instance",
			/*
			 * When a new version of the schema is created, it needs staging on mediawiki.org.
			 * This requires a change in the operations/mediawiki-config git repo.
			 *
			 * Create a changeset like https://gerrit.wikimedia.org/r/#/c/149643/ in which
			 * you copy in the new xsd file.
			 *
			 * After it is reviewed, merged and deployed (sync-docroot), the index.html needs purging.
			 * echo "http://www.mediawiki.org/xml/index.html" | mwscript purgeList.php --wiki=aawiki
			 */
			'xsi:schemaLocation' => "http://www.mediawiki.org/xml/export-$ver/ " .
				"http://www.mediawiki.org/xml/export-$ver.xsd",
			'version'            => $ver,
			'xml:lang'           => $wgLanguageCode ),
			null ) .
			"\n" .
			$this->siteInfo();
	}

	/**
	 * @return string
	 */
	function siteInfo() {
		$info = array(
			$this->sitename(),
			$this->dbname(),
			$this->homelink(),
			$this->generator(),
			$this->caseSetting(),
			$this->namespaces() );
		return "  <siteinfo>\n    " .
			implode( "\n    ", $info ) .
			"\n  </siteinfo>\n";
	}

	/**
	 * @return string
	 */
	function sitename() {
		global $wgSitename;
		return Xml::element( 'sitename', array(), $wgSitename );
	}

	/**
	 * @return string
	 */
	function dbname() {
		global $wgDBname;
		return Xml::element( 'dbname', array(), $wgDBname );
	}

	/**
	 * @return string
	 */
	function generator() {
		global $wgVersion;
		return Xml::element( 'generator', array(), "MediaWiki $wgVersion" );
	}

	/**
	 * @return string
	 */
	function homelink() {
		return Xml::element( 'base', array(), Title::newMainPage()->getCanonicalURL() );
	}

	/**
	 * @return string
	 */
	function caseSetting() {
		global $wgCapitalLinks;
		// "case-insensitive" option is reserved for future
		$sensitivity = $wgCapitalLinks ? 'first-letter' : 'case-sensitive';
		return Xml::element( 'case', array(), $sensitivity );
	}

	/**
	 * @return string
	 */
	function namespaces() {
		global $wgContLang;
		$spaces = "<namespaces>\n";
		foreach ( $wgContLang->getFormattedNamespaces() as $ns => $title ) {
			$spaces .= '      ' .
				Xml::element( 'namespace',
					array(
						'key' => $ns,
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
	 * Opens a "<page>" section on the output stream, with data
	 * from the given database row.
	 *
	 * @param object $row
	 * @return string
	 */
	public function openPage( $row ) {
		$out = "  <page>\n";
		$title = Title::makeTitle( $row->page_namespace, $row->page_title );
		$out .= '    ' . Xml::elementClean( 'title', array(), self::canonicalTitle( $title ) ) . "\n";
		$out .= '    ' . Xml::element( 'ns', array(), strval( $row->page_namespace ) ) . "\n";
		$out .= '    ' . Xml::element( 'id', array(), strval( $row->page_id ) ) . "\n";
		if ( $row->page_is_redirect ) {
			$page = WikiPage::factory( $title );
			$redirect = $page->getRedirectTarget();
			if ( $redirect instanceof Title && $redirect->isValidRedirectTarget() ) {
				$out .= '    ';
				$out .= Xml::element( 'redirect', array( 'title' => self::canonicalTitle( $redirect ) ) );
				$out .= "\n";
			}
		}

		if ( $row->page_restrictions != '' ) {
			$out .= '    ' . Xml::element( 'restrictions', array(),
				strval( $row->page_restrictions ) ) . "\n";
		}

		Hooks::run( 'XmlDumpWriterOpenPage', array( $this, &$out, $row, $title ) );

		return $out;
	}

	/**
	 * Closes a "<page>" section on the output stream.
	 *
	 * @access private
	 * @return string
	 */
	function closePage() {
		return "  </page>\n";
	}

	/**
	 * Dumps a "<revision>" section on the output stream, with
	 * data filled in from the given database row.
	 *
	 * @param object $row
	 * @return string
	 * @access private
	 */
	function writeRevision( $row ) {

		$out = "    <revision>\n";
		$out .= "      " . Xml::element( 'id', null, strval( $row->rev_id ) ) . "\n";
		if ( isset( $row->rev_parent_id ) && $row->rev_parent_id ) {
			$out .= "      " . Xml::element( 'parentid', null, strval( $row->rev_parent_id ) ) . "\n";
		}

		$out .= $this->writeTimestamp( $row->rev_timestamp );

		if ( isset( $row->rev_deleted ) && ( $row->rev_deleted & Revision::DELETED_USER ) ) {
			$out .= "      " . Xml::element( 'contributor', array( 'deleted' => 'deleted' ) ) . "\n";
		} else {
			$out .= $this->writeContributor( $row->rev_user, $row->rev_user_text );
		}

		if ( isset( $row->rev_minor_edit ) && $row->rev_minor_edit ) {
			$out .= "      <minor/>\n";
		}
		if ( isset( $row->rev_deleted ) && ( $row->rev_deleted & Revision::DELETED_COMMENT ) ) {
			$out .= "      " . Xml::element( 'comment', array( 'deleted' => 'deleted' ) ) . "\n";
		} elseif ( $row->rev_comment != '' ) {
			$out .= "      " . Xml::elementClean( 'comment', array(), strval( $row->rev_comment ) ) . "\n";
		}

		if ( isset( $row->rev_content_model ) && !is_null( $row->rev_content_model ) ) {
			$content_model = strval( $row->rev_content_model );
		} else {
			// probably using $wgContentHandlerUseDB = false;
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$content_model = ContentHandler::getDefaultModelFor( $title );
		}

		$content_handler = ContentHandler::getForModelID( $content_model );

		if ( isset( $row->rev_content_format ) && !is_null( $row->rev_content_format ) ) {
			$content_format = strval( $row->rev_content_format );
		} else {
			// probably using $wgContentHandlerUseDB = false;
			$content_format = $content_handler->getDefaultFormat();
		}

		$out .= "      " . Xml::element( 'model', null, strval( $content_model ) ) . "\n";
		$out .= "      " . Xml::element( 'format', null, strval( $content_format ) ) . "\n";

		$text = '';
		if ( isset( $row->rev_deleted ) && ( $row->rev_deleted & Revision::DELETED_TEXT ) ) {
			$out .= "      " . Xml::element( 'text', array( 'deleted' => 'deleted' ) ) . "\n";
		} elseif ( isset( $row->old_text ) ) {
			// Raw text from the database may have invalid chars
			$text = strval( Revision::getRevisionText( $row ) );
			$text = $content_handler->exportTransform( $text, $content_format );
			$out .= "      " . Xml::elementClean( 'text',
				array( 'xml:space' => 'preserve', 'bytes' => intval( $row->rev_len ) ),
				strval( $text ) ) . "\n";
		} else {
			// Stub output
			$out .= "      " . Xml::element( 'text',
				array( 'id' => $row->rev_text_id, 'bytes' => intval( $row->rev_len ) ),
				"" ) . "\n";
		}

		if ( isset( $row->rev_sha1 )
			&& $row->rev_sha1
			&& !( $row->rev_deleted & Revision::DELETED_TEXT )
		) {
			$out .= "      " . Xml::element( 'sha1', null, strval( $row->rev_sha1 ) ) . "\n";
		} else {
			$out .= "      <sha1/>\n";
		}

		Hooks::run( 'XmlDumpWriterWriteRevision', array( &$this, &$out, $row, $text ) );

		$out .= "    </revision>\n";

		return $out;
	}

	/**
	 * Dumps a "<logitem>" section on the output stream, with
	 * data filled in from the given database row.
	 *
	 * @param object $row
	 * @return string
	 * @access private
	 */
	function writeLogItem( $row ) {

		$out = "  <logitem>\n";
		$out .= "    " . Xml::element( 'id', null, strval( $row->log_id ) ) . "\n";

		$out .= $this->writeTimestamp( $row->log_timestamp, "    " );

		if ( $row->log_deleted & LogPage::DELETED_USER ) {
			$out .= "    " . Xml::element( 'contributor', array( 'deleted' => 'deleted' ) ) . "\n";
		} else {
			$out .= $this->writeContributor( $row->log_user, $row->user_name, "    " );
		}

		if ( $row->log_deleted & LogPage::DELETED_COMMENT ) {
			$out .= "    " . Xml::element( 'comment', array( 'deleted' => 'deleted' ) ) . "\n";
		} elseif ( $row->log_comment != '' ) {
			$out .= "    " . Xml::elementClean( 'comment', null, strval( $row->log_comment ) ) . "\n";
		}

		$out .= "    " . Xml::element( 'type', null, strval( $row->log_type ) ) . "\n";
		$out .= "    " . Xml::element( 'action', null, strval( $row->log_action ) ) . "\n";

		if ( $row->log_deleted & LogPage::DELETED_ACTION ) {
			$out .= "    " . Xml::element( 'text', array( 'deleted' => 'deleted' ) ) . "\n";
		} else {
			$title = Title::makeTitle( $row->log_namespace, $row->log_title );
			$out .= "    " . Xml::elementClean( 'logtitle', null, self::canonicalTitle( $title ) ) . "\n";
			$out .= "    " . Xml::elementClean( 'params',
				array( 'xml:space' => 'preserve' ),
				strval( $row->log_params ) ) . "\n";
		}

		$out .= "  </logitem>\n";

		return $out;
	}

	/**
	 * @param string $timestamp
	 * @param string $indent Default to six spaces
	 * @return string
	 */
	function writeTimestamp( $timestamp, $indent = "      " ) {
		$ts = wfTimestamp( TS_ISO_8601, $timestamp );
		return $indent . Xml::element( 'timestamp', null, $ts ) . "\n";
	}

	/**
	 * @param int $id
	 * @param string $text
	 * @param string $indent Default to six spaces
	 * @return string
	 */
	function writeContributor( $id, $text, $indent = "      " ) {
		$out = $indent . "<contributor>\n";
		if ( $id || !IP::isValid( $text ) ) {
			$out .= $indent . "  " . Xml::elementClean( 'username', null, strval( $text ) ) . "\n";
			$out .= $indent . "  " . Xml::element( 'id', null, strval( $id ) ) . "\n";
		} else {
			$out .= $indent . "  " . Xml::elementClean( 'ip', null, strval( $text ) ) . "\n";
		}
		$out .= $indent . "</contributor>\n";
		return $out;
	}

	/**
	 * Warning! This data is potentially inconsistent. :(
	 * @param object $row
	 * @param bool $dumpContents
	 * @return string
	 */
	function writeUploads( $row, $dumpContents = false ) {
		if ( $row->page_namespace == NS_FILE ) {
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
	 * @param File $file
	 * @param bool $dumpContents
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
			$be = $file->getRepo()->getBackend();
			# Dump file as base64
			# Uses only XML-safe characters, so does not need escaping
			# @todo Too bad this loads the contents into memory (script might swap)
			$contents = '      <contents encoding="base64">' .
				chunk_split( base64_encode(
					$be->getFileContents( array( 'src' => $file->getPath() ) ) ) ) .
				"      </contents>\n";
		} else {
			$contents = '';
		}
		if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
			$comment = Xml::element( 'comment', array( 'deleted' => 'deleted' ) );
		} else {
			$comment = Xml::elementClean( 'comment', null, $file->getDescription() );
		}
		return "    <upload>\n" .
			$this->writeTimestamp( $file->getTimestamp() ) .
			$this->writeContributor( $file->getUser( 'id' ), $file->getUser( 'text' ) ) .
			"      " . $comment . "\n" .
			"      " . Xml::element( 'filename', null, $file->getName() ) . "\n" .
			$archiveName .
			"      " . Xml::element( 'src', null, $file->getCanonicalURL() ) . "\n" .
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
	 * XML "<siteinfo>" data so are unsafe in export.
	 *
	 * @param Title $title
	 * @return string
	 * @since 1.18
	 */
	public static function canonicalTitle( Title $title ) {
		if ( $title->isExternal() ) {
			return $title->getPrefixedText();
		}

		global $wgContLang;
		$prefix = $wgContLang->getFormattedNsText( $title->getNamespace() );

		if ( $prefix !== '' ) {
			$prefix .= ':';
		}

		return $prefix . $title->getText();
	}
}

/**
 * Base class for output stream; prints to stdout or buffer or wherever.
 * @ingroup Dump
 */
class DumpOutput {

	/**
	 * @param string $string
	 */
	function writeOpenStream( $string ) {
		$this->write( $string );
	}

	/**
	 * @param string $string
	 */
	function writeCloseStream( $string ) {
		$this->write( $string );
	}

	/**
	 * @param object $page
	 * @param string $string
	 */
	function writeOpenPage( $page, $string ) {
		$this->write( $string );
	}

	/**
	 * @param string $string
	 */
	function writeClosePage( $string ) {
		$this->write( $string );
	}

	/**
	 * @param object $rev
	 * @param string $string
	 */
	function writeRevision( $rev, $string ) {
		$this->write( $string );
	}

	/**
	 * @param object $rev
	 * @param string $string
	 */
	function writeLogItem( $rev, $string ) {
		$this->write( $string );
	}

	/**
	 * Override to write to a different stream type.
	 * @param string $string
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
	 * @param string|array $newname File name. May be a string or an array with one element
	 */
	function closeRenameAndReopen( $newname ) {
	}

	/**
	 * Close the old file, and move it to a specified name.
	 * Use this for the last piece of a file written out
	 * at specified checkpoints (e.g. every n hours).
	 * @param string|array $newname File name. May be a string or an array with one element
	 * @param bool $open If true, a new file with the old filename will be opened
	 *   again for writing (default: false)
	 */
	function closeAndRename( $newname, $open = false ) {
	}

	/**
	 * Returns the name of the file or files which are
	 * being written to, if there are any.
	 * @return null
	 */
	function getFilenames() {
		return null;
	}
}

/**
 * Stream outputter to send data to a file.
 * @ingroup Dump
 */
class DumpFileOutput extends DumpOutput {
	protected $handle = false, $filename;

	/**
	 * @param string $file
	 */
	function __construct( $file ) {
		$this->handle = fopen( $file, "wt" );
		$this->filename = $file;
	}

	/**
	 * @param string $string
	 */
	function writeCloseStream( $string ) {
		parent::writeCloseStream( $string );
		if ( $this->handle ) {
			fclose( $this->handle );
			$this->handle = false;
		}
	}

	/**
	 * @param string $string
	 */
	function write( $string ) {
		fputs( $this->handle, $string );
	}

	/**
	 * @param string $newname
	 */
	function closeRenameAndReopen( $newname ) {
		$this->closeAndRename( $newname, true );
	}

	/**
	 * @param string $newname
	 * @throws MWException
	 */
	function renameOrException( $newname ) {
			if ( !rename( $this->filename, $newname ) ) {
				throw new MWException( __METHOD__ . ": rename of file {$this->filename} to $newname failed\n" );
			}
	}

	/**
	 * @param array $newname
	 * @return string
	 * @throws MWException
	 */
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

	/**
	 * @param string $newname
	 * @param bool $open
	 */
	function closeAndRename( $newname, $open = false ) {
		$newname = $this->checkRenameArgCount( $newname );
		if ( $newname ) {
			if ( $this->handle ) {
				fclose( $this->handle );
				$this->handle = false;
			}
			$this->renameOrException( $newname );
			if ( $open ) {
				$this->handle = fopen( $this->filename, "wt" );
			}
		}
	}

	/**
	 * @return string|null
	 */
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
	protected $procOpenResource = false;

	/**
	 * @param string $command
	 * @param string $file
	 */
	function __construct( $command, $file = null ) {
		if ( !is_null( $file ) ) {
			$command .= " > " . wfEscapeShellArg( $file );
		}

		$this->startCommand( $command );
		$this->command = $command;
		$this->filename = $file;
	}

	/**
	 * @param string $string
	 */
	function writeCloseStream( $string ) {
		parent::writeCloseStream( $string );
		if ( $this->procOpenResource ) {
			proc_close( $this->procOpenResource );
			$this->procOpenResource = false;
		}
	}

	/**
	 * @param string $command
	 */
	function startCommand( $command ) {
		$spec = array(
			0 => array( "pipe", "r" ),
		);
		$pipes = array();
		$this->procOpenResource = proc_open( $command, $spec, $pipes );
		$this->handle = $pipes[0];
	}

	/**
	 * @param string $newname
	 */
	function closeRenameAndReopen( $newname ) {
		$this->closeAndRename( $newname, true );
	}

	/**
	 * @param string $newname
	 * @param bool $open
	 */
	function closeAndRename( $newname, $open = false ) {
		$newname = $this->checkRenameArgCount( $newname );
		if ( $newname ) {
			if ( $this->handle ) {
				fclose( $this->handle );
				$this->handle = false;
			}
			if ( $this->procOpenResource ) {
				proc_close( $this->procOpenResource );
				$this->procOpenResource = false;
			}
			$this->renameOrException( $newname );
			if ( $open ) {
				$command = $this->command;
				$command .= " > " . wfEscapeShellArg( $this->filename );
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
	/**
	 * @param string $file
	 */
	function __construct( $file ) {
		parent::__construct( "gzip", $file );
	}
}

/**
 * Sends dump output via the bgzip2 compressor.
 * @ingroup Dump
 */
class DumpBZip2Output extends DumpPipeOutput {
	/**
	 * @param string $file
	 */
	function __construct( $file ) {
		parent::__construct( "bzip2", $file );
	}
}

/**
 * Sends dump output via the p7zip compressor.
 * @ingroup Dump
 */
class Dump7ZipOutput extends DumpPipeOutput {
	/**
	 * @param string $file
	 */
	function __construct( $file ) {
		$command = $this->setup7zCommand( $file );
		parent::__construct( $command );
		$this->filename = $file;
	}

	/**
	 * @param string $file
	 * @return string
	 */
	function setup7zCommand( $file ) {
		$command = "7za a -bd -si -mx=4 " . wfEscapeShellArg( $file );
		// Suppress annoying useless crap from p7zip
		// Unfortunately this could suppress real error messages too
		$command .= ' >' . wfGetNull() . ' 2>&1';
		return $command;
	}

	/**
	 * @param string $newname
	 * @param bool $open
	 */
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
	/**
	 * @var DumpOutput
	 * FIXME will need to be made protected whenever legacy code
	 * is updated.
	 */
	public $sink;

	/**
	 * @var bool
	 */
	protected $sendingThisPage;

	/**
	 * @param DumpOutput $sink
	 */
	function __construct( &$sink ) {
		$this->sink =& $sink;
	}

	/**
	 * @param string $string
	 */
	function writeOpenStream( $string ) {
		$this->sink->writeOpenStream( $string );
	}

	/**
	 * @param string $string
	 */
	function writeCloseStream( $string ) {
		$this->sink->writeCloseStream( $string );
	}

	/**
	 * @param object $page
	 * @param string $string
	 */
	function writeOpenPage( $page, $string ) {
		$this->sendingThisPage = $this->pass( $page, $string );
		if ( $this->sendingThisPage ) {
			$this->sink->writeOpenPage( $page, $string );
		}
	}

	/**
	 * @param string $string
	 */
	function writeClosePage( $string ) {
		if ( $this->sendingThisPage ) {
			$this->sink->writeClosePage( $string );
			$this->sendingThisPage = false;
		}
	}

	/**
	 * @param object $rev
	 * @param string $string
	 */
	function writeRevision( $rev, $string ) {
		if ( $this->sendingThisPage ) {
			$this->sink->writeRevision( $rev, $string );
		}
	}

	/**
	 * @param object $rev
	 * @param string $string
	 */
	function writeLogItem( $rev, $string ) {
		$this->sink->writeRevision( $rev, $string );
	}

	/**
	 * @param string $newname
	 */
	function closeRenameAndReopen( $newname ) {
		$this->sink->closeRenameAndReopen( $newname );
	}

	/**
	 * @param string $newname
	 * @param bool $open
	 */
	function closeAndRename( $newname, $open = false ) {
		$this->sink->closeAndRename( $newname, $open );
	}

	/**
	 * @return array
	 */
	function getFilenames() {
		return $this->sink->getFilenames();
	}

	/**
	 * Override for page-based filter types.
	 * @param object $page
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
	/**
	 * @param object $page
	 * @return bool
	 */
	function pass( $page ) {
		return !MWNamespace::isTalk( $page->page_namespace );
	}
}

/**
 * Dump output filter to include or exclude pages in a given set of namespaces.
 * @ingroup Dump
 */
class DumpNamespaceFilter extends DumpFilter {
	/** @var bool */
	public $invert = false;

	/** @var array */
	public $namespaces = array();

	/**
	 * @param DumpOutput $sink
	 * @param array $param
	 * @throws MWException
	 */
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
			"NS_IMAGE"          => NS_IMAGE, // NS_IMAGE is an alias for NS_FILE
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

	/**
	 * @param object $page
	 * @return bool
	 */
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
	public $page;

	public $pageString;

	public $rev;

	public $revString;

	/**
	 * @param object $page
	 * @param string $string
	 */
	function writeOpenPage( $page, $string ) {
		$this->page = $page;
		$this->pageString = $string;
	}

	/**
	 * @param string $string
	 */
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

	/**
	 * @param object $rev
	 * @param string $string
	 */
	function writeRevision( $rev, $string ) {
		if ( $rev->rev_id == $this->page->page_latest ) {
			$this->rev = $rev;
			$this->revString = $string;
		}
	}
}

/**
 * Base class for output stream; prints to stdout or buffer or wherever.
 * @ingroup Dump
 */
class DumpMultiWriter {

	/**
	 * @param array $sinks
	 */
	function __construct( $sinks ) {
		$this->sinks = $sinks;
		$this->count = count( $sinks );
	}

	/**
	 * @param string $string
	 */
	function writeOpenStream( $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeOpenStream( $string );
		}
	}

	/**
	 * @param string $string
	 */
	function writeCloseStream( $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeCloseStream( $string );
		}
	}

	/**
	 * @param object $page
	 * @param string $string
	 */
	function writeOpenPage( $page, $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeOpenPage( $page, $string );
		}
	}

	/**
	 * @param string $string
	 */
	function writeClosePage( $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeClosePage( $string );
		}
	}

	/**
	 * @param object $rev
	 * @param string $string
	 */
	function writeRevision( $rev, $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeRevision( $rev, $string );
		}
	}

	/**
	 * @param array $newnames
	 */
	function closeRenameAndReopen( $newnames ) {
		$this->closeAndRename( $newnames, true );
	}

	/**
	 * @param array $newnames
	 * @param bool $open
	 */
	function closeAndRename( $newnames, $open = false ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->closeAndRename( $newnames[$i], $open );
		}
	}

	/**
	 * @return array
	 */
	function getFilenames() {
		$filenames = array();
		for ( $i = 0; $i < $this->count; $i++ ) {
			$filenames[] = $this->sinks[$i]->getFilenames();
		}
		return $filenames;
	}
}
