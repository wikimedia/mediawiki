<?php
/**
 * Base class for exporting
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

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

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

	public const FULL = 1;
	public const CURRENT = 2;
	public const STABLE = 4; // extension defined
	public const LOGS = 8;
	public const RANGE = 16;

	public const TEXT = XmlDumpWriter::WRITE_CONTENT;
	public const STUB = XmlDumpWriter::WRITE_STUB;

	protected const BATCH_SIZE = 50000;

	/** @var int */
	public $text;

	/** @var DumpOutput */
	public $sink;

	/** @var XmlDumpWriter */
	private $writer;

	/** @var IDatabase */
	protected $db;

	/** @var array|int */
	protected $history;

	/** @var array|null */
	protected $limitNamespaces;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * Returns the default export schema version, as defined by $wgXmlDumpSchemaVersion.
	 * @return string
	 */
	public static function schemaVersion() {
		global $wgXmlDumpSchemaVersion;
		return $wgXmlDumpSchemaVersion;
	}

	/**
	 * @param IDatabase $db
	 * @param int|array $history One of WikiExporter::FULL, WikiExporter::CURRENT,
	 *   WikiExporter::RANGE or WikiExporter::STABLE, or an associative array:
	 *   - offset: non-inclusive offset at which to start the query
	 *   - limit: maximum number of rows to return
	 *   - dir: "asc" or "desc" timestamp order
	 * @param int $text One of WikiExporter::TEXT or WikiExporter::STUB
	 * @param null|array $limitNamespaces Comma-separated list of namespace numbers
	 *   to limit results
	 */
	public function __construct(
		$db,
		$history = self::CURRENT,
		$text = self::TEXT,
		$limitNamespaces = null
	) {
		$this->db = $db;
		$this->history = $history;
		$this->writer = new XmlDumpWriter( $text, self::schemaVersion() );
		$this->sink = new DumpOutput();
		$this->text = $text;
		$this->limitNamespaces = $limitNamespaces;
		$services = MediaWikiServices::getInstance();
		$this->hookRunner = new HookRunner( $services->getHookContainer() );
		$this->revisionStore = $services->getRevisionStore();
	}

	/**
	 * @param string $schemaVersion which schema version the generated XML should comply to.
	 * One of the values from self::$supportedSchemas, using the XML_DUMP_SCHEMA_VERSION_XX
	 * constants.
	 */
	public function setSchemaVersion( $schemaVersion ) {
		$this->writer = new XmlDumpWriter( $this->text, $schemaVersion );
	}

	/**
	 * Set the DumpOutput or DumpFilter object which will receive
	 * various row objects and XML output for filtering. Filters
	 * can be chained or used as callbacks.
	 *
	 * @param DumpOutput|DumpFilter &$sink
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
	 * @param bool $orderRevs order revisions within pages in ascending order
	 */
	public function pagesByRange( $start, $end, $orderRevs ) {
		if ( $orderRevs ) {
			$condition = 'rev_page >= ' . intval( $start );
			if ( $end ) {
				$condition .= ' AND rev_page < ' . intval( $end );
			}
		} else {
			$condition = 'page_id >= ' . intval( $start );
			if ( $end ) {
				$condition .= ' AND page_id < ' . intval( $end );
			}
		}
		$this->dumpFrom( $condition, $orderRevs );
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
		if ( $title === null ) {
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
	 * @param string $cond
	 */
	protected function do_list_authors( $cond ) {
		$this->author_list = "<contributors>";
		// rev_deleted

		$revQuery = $this->revisionStore->getQueryInfo( [ 'page' ] );
		$res = $this->db->select(
			$revQuery['tables'],
			[
				'rev_user_text' => $revQuery['fields']['rev_user_text'],
				'rev_user' => $revQuery['fields']['rev_user'],
			],
			[
				$this->db->bitAnd( 'rev_deleted', RevisionRecord::DELETED_USER ) . ' = 0',
				$cond,
			],
			__METHOD__,
			[ 'DISTINCT' ],
			$revQuery['joins']
		);

		foreach ( $res as $row ) {
			$this->author_list .= "<contributor>" .
				"<username>" .
				htmlspecialchars( $row->rev_user_text ) .
				"</username>" .
				"<id>" .
				( (int)$row->rev_user ) .
				"</id>" .
				"</contributor>";
		}
		$this->author_list .= "</contributors>";
	}

	/**
	 * @param string $cond
	 * @param bool $orderRevs
	 * @throws MWException
	 * @throws Exception
	 */
	protected function dumpFrom( $cond = '', $orderRevs = false ) {
		if ( $this->history & self::LOGS ) {
			$this->dumpLogs( $cond );
		} else {
			$this->dumpPages( $cond, $orderRevs );
		}
	}

	/**
	 * @param string $cond
	 * @throws Exception
	 */
	protected function dumpLogs( $cond ) {
		$where = [];
		# Hide private logs
		$hideLogs = LogEventsList::getExcludeClause( $this->db );
		if ( $hideLogs ) {
			$where[] = $hideLogs;
		}
		# Add on any caller specified conditions
		if ( $cond ) {
			$where[] = $cond;
		}
		$result = null; // Assuring $result is not undefined, if exception occurs early

		$commentQuery = CommentStore::getStore()->getJoin( 'log_comment' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'log_user' );

		$tables = array_merge(
			[ 'logging' ], $commentQuery['tables'], $actorQuery['tables'], [ 'user' ]
		);
		$fields = [
			'log_id', 'log_type', 'log_action', 'log_timestamp', 'log_namespace',
			'log_title', 'log_params', 'log_deleted', 'user_name'
		] + $commentQuery['fields'] + $actorQuery['fields'];
		$options = [
			'ORDER BY' => 'log_id',
			'USE INDEX' => [ 'logging' => 'PRIMARY' ],
			'LIMIT' => self::BATCH_SIZE,
		];
		$joins = [
			'user' => [ 'JOIN', 'user_id = ' . $actorQuery['fields']['log_user'] ]
		] + $commentQuery['joins'] + $actorQuery['joins'];

		$lastLogId = 0;
		while ( true ) {
			$result = $this->db->select(
				$tables,
				$fields,
				array_merge( $where, [ 'log_id > ' . intval( $lastLogId ) ] ),
				__METHOD__,
				$options,
				$joins
			);

			if ( !$result->numRows() ) {
				break;
			}

			$lastLogId = $this->outputLogStream( $result );
		}
	}

	/**
	 * @param string $cond
	 * @param bool $orderRevs
	 * @throws MWException
	 * @throws Exception
	 */
	protected function dumpPages( $cond, $orderRevs ) {
		$revQuery = $this->revisionStore->getQueryInfo( [ 'page' ] );
		$slotQuery = $this->revisionStore->getSlotsQueryInfo( [ 'content' ] );

		// We want page primary rather than revision.
		// We also want to join in the slots and content tables.
		// NOTE: This means we may get multiple rows per revision, and more rows
		// than the batch size! Should be ok, since the max number of slots is
		// fixed and low (dozens at worst).
		$tables = array_merge( [ 'page' ], array_diff( $revQuery['tables'], [ 'page' ] ) );
		$tables = array_merge( $tables, array_diff( $slotQuery['tables'], $tables ) );
		$join = $revQuery['joins'] + [
				'revision' => $revQuery['joins']['page'],
				'slots' => [ 'JOIN', [ 'slot_revision_id = rev_id' ] ],
				'content' => [ 'JOIN', [ 'content_id = slot_content_id' ] ],
			];
		unset( $join['page'] );

		$fields = array_merge( $revQuery['fields'], $slotQuery['fields'] );
		$fields[] = 'page_restrictions';

		if ( $this->text != self::STUB ) {
			$fields['_load_content'] = '1';
		}

		$conds = [];
		if ( $cond !== '' ) {
			$conds[] = $cond;
		}
		$opts = [ 'ORDER BY' => [ 'rev_page ASC', 'rev_id ASC' ] ];
		$opts['USE INDEX'] = [];

		$op = '>';
		if ( is_array( $this->history ) ) {
			# Time offset/limit for all pages/history...
			# Set time order
			if ( $this->history['dir'] == 'asc' ) {
				$opts['ORDER BY'] = 'rev_timestamp ASC';
			} else {
				$op = '<';
				$opts['ORDER BY'] = 'rev_timestamp DESC';
			}
			# Set offset
			if ( !empty( $this->history['offset'] ) ) {
				$conds[] = "rev_timestamp $op " .
					$this->db->addQuotes( $this->db->timestamp( $this->history['offset'] ) );
			}
			# Set query limit
			if ( !empty( $this->history['limit'] ) ) {
				$maxRowCount = intval( $this->history['limit'] );
			}
		} elseif ( $this->history & self::FULL ) {
			# Full history dumps...
			# query optimization for history stub dumps
			if ( $this->text == self::STUB ) {
				$opts[] = 'STRAIGHT_JOIN';
				$opts['USE INDEX']['revision'] = 'rev_page_id';
				unset( $join['revision'] );
				$join['page'] = [ 'JOIN', 'rev_page=page_id' ];
			}
		} elseif ( $this->history & self::CURRENT ) {
			# Latest revision dumps...
			if ( $this->list_authors && $cond != '' ) { // List authors, if so desired
				$this->do_list_authors( $cond );
			}
			$join['revision'] = [ 'JOIN', 'page_id=rev_page AND page_latest=rev_id' ];
			$opts[ 'ORDER BY' ] = [ 'page_id ASC' ];
		} elseif ( $this->history & self::STABLE ) {
			# "Stable" revision dumps...
			# Default JOIN, to be overridden...
			$join['revision'] = [ 'JOIN', 'page_id=rev_page AND page_latest=rev_id' ];
			# One, and only one hook should set this, and return false
			if ( $this->hookRunner->onWikiExporter__dumpStableQuery( $tables, $opts, $join ) ) {
				throw new MWException( __METHOD__ . " given invalid history dump type." );
			}
		} elseif ( $this->history & self::RANGE ) {
			# Dump of revisions within a specified range.  Condition already set in revsByRange().
		} else {
			# Unknown history specification parameter?
			throw new MWException( __METHOD__ . " given invalid history dump type." );
		}

		$result = null; // Assuring $result is not undefined, if exception occurs early
		$done = false;
		$lastRow = null;
		$revPage = 0;
		$revId = 0;
		$rowCount = 0;

		$opts['LIMIT'] = self::BATCH_SIZE;

		$this->hookRunner->onModifyExportQuery(
			$this->db, $tables, $cond, $opts, $join, $conds );

		while ( !$done ) {
			// If necessary, impose the overall maximum and stop looping after this iteration.
			if ( !empty( $maxRowCount ) && $rowCount + self::BATCH_SIZE > $maxRowCount ) {
				$opts['LIMIT'] = $maxRowCount - $rowCount;
				$done = true;
			}

			$queryConds = $conds;
			$queryConds[] = 'rev_page>' . intval( $revPage ) . ' OR (rev_page=' .
				intval( $revPage ) . ' AND rev_id' . $op . intval( $revId ) . ')';

			# Do the query and process any results, remembering max ids for the next iteration.
			$result = $this->db->select(
				$tables,
				$fields,
				$queryConds,
				__METHOD__,
				$opts,
				$join
			);
			if ( $result->numRows() > 0 ) {
				$lastRow = $this->outputPageStreamBatch( $result, $lastRow );
				$rowCount += $result->numRows();
				$revPage = $lastRow->rev_page;
				$revId = $lastRow->rev_id;
			} else {
				$done = true;
			}

			// If we are finished, close off final page element (if any).
			if ( $done && $lastRow ) {
				$this->finishPageStreamOutput( $lastRow );
			}
		}
	}

	/**
	 * Runs through a query result set dumping page, revision, and slot records.
	 * The result set should join the page, revision, slots, and content tables,
	 * and be sorted/grouped by page and revision to avoid duplicate page records in the output.
	 *
	 * @param IResultWrapper $results
	 * @param object $lastRow the last row output from the previous call (or null if none)
	 * @return object the last row processed
	 */
	protected function outputPageStreamBatch( $results, $lastRow ) {
		$rowCarry = null;
		while ( true ) {
			$slotRows = $this->getSlotRowBatch( $results, $rowCarry );

			if ( !$slotRows ) {
				break;
			}

			// All revision info is present in all slot rows.
			// Use the first slot row as the revision row.
			$revRow = $slotRows[0];

			if ( $this->limitNamespaces &&
				!in_array( $revRow->page_namespace, $this->limitNamespaces ) ) {
				$lastRow = $revRow;
				continue;
			}

			if ( $lastRow === null ||
				$lastRow->page_namespace !== $revRow->page_namespace ||
				$lastRow->page_title !== $revRow->page_title ) {
				if ( $lastRow !== null ) {
					$output = '';
					if ( $this->dumpUploads ) {
						$output .= $this->writer->writeUploads( $lastRow, $this->dumpUploadFileContents );
					}
					$output .= $this->writer->closePage();
					$this->sink->writeClosePage( $output );
				}
				$output = $this->writer->openPage( $revRow );
				$this->sink->writeOpenPage( $revRow, $output );
			}
			$output = $this->writer->writeRevision( $revRow, $slotRows );
			$this->sink->writeRevision( $revRow, $output );
			$lastRow = $revRow;
		}

		if ( $rowCarry ) {
			throw new LogicException( 'Error while processing a stream of slot rows' );
		}

		return $lastRow;
	}

	/**
	 * Returns all slot rows for a revision.
	 * Takes and returns a carry row from the last batch;
	 *
	 * @param IResultWrapper|array $results
	 * @param null|object &$carry A row carried over from the last call to getSlotRowBatch()
	 *
	 * @return object[]
	 */
	protected function getSlotRowBatch( $results, &$carry = null ) {
		$slotRows = [];
		$prev = null;

		if ( $carry ) {
			$slotRows[] = $carry;
			$prev = $carry;
			$carry = null;
		}

		while ( $row = $results->fetchObject() ) {
			if ( $prev && $prev->rev_id !== $row->rev_id ) {
				$carry = $row;
				break;
			}
			$slotRows[] = $row;
			$prev = $row;
		}

		return $slotRows;
	}

	/**
	 * Final page stream output, after all batches are complete
	 *
	 * @param object $lastRow the last row output from the last batch (or null if none)
	 */
	protected function finishPageStreamOutput( $lastRow ) {
		$output = '';
		if ( $this->dumpUploads ) {
			$output .= $this->writer->writeUploads( $lastRow, $this->dumpUploadFileContents );
		}
		$output .= $this->author_list;
		$output .= $this->writer->closePage();
		$this->sink->writeClosePage( $output );
	}

	/**
	 * @param IResultWrapper $resultset
	 * @return int|null the log_id value of the last item output, or null if none
	 */
	protected function outputLogStream( $resultset ) {
		foreach ( $resultset as $row ) {
			$output = $this->writer->writeLogItem( $row );
			$this->sink->writeLogItem( $row, $output );
		}
		return $row->log_id ?? null;
	}
}
