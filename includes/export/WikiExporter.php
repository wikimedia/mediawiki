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

use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\IDatabase;

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
	 * @param IDatabase $db
	 * @param int|array $history One of WikiExporter::FULL, WikiExporter::CURRENT,
	 *   WikiExporter::RANGE or WikiExporter::STABLE, or an associative array:
	 *   - offset: non-inclusive offset at which to start the query
	 *   - limit: maximum number of rows to return
	 *   - dir: "asc" or "desc" timestamp order
	 * @param int $buffer One of WikiExporter::BUFFER or WikiExporter::STREAM
	 * @param int $text One of WikiExporter::TEXT or WikiExporter::STUB
	 */
	function __construct( $db, $history = self::CURRENT,
			$buffer = self::BUFFER, $text = self::TEXT ) {
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
	 * @param DumpOutput &$sink
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

		$revQuery = Revision::getQueryInfo( [ 'page' ] );
		$res = $this->db->select(
			$revQuery['tables'],
			[
				'rev_user_text' => $revQuery['fields']['rev_user_text'],
				'rev_user' => $revQuery['fields']['rev_user'],
			],
			[
				$this->db->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0',
				$cond,
			],
			__METHOD__,
			[ 'DISTINCT' ],
			$revQuery['joins']
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
	 * @param bool $orderRevs
	 * @throws MWException
	 * @throws Exception
	 */
	protected function dumpFrom( $cond = '', $orderRevs = false ) {
		# For logging dumps...
		if ( $this->history & self::LOGS ) {
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
			# Get logging table name for logging.* clause
			$logging = $this->db->tableName( 'logging' );

			if ( $this->buffer == self::STREAM ) {
				$prev = $this->db->bufferResults( false );
			}
			$result = null; // Assuring $result is not undefined, if exception occurs early

			$commentQuery = CommentStore::getStore()->getJoin( 'log_comment' );
			$actorQuery = ActorMigration::newMigration()->getJoin( 'log_user' );

			try {
				$result = $this->db->select(
					array_merge( [ 'logging' ], $commentQuery['tables'], $actorQuery['tables'], [ 'user' ] ),
					[ "{$logging}.*", 'user_name' ] + $commentQuery['fields'] + $actorQuery['fields'],
					$where,
					__METHOD__,
					[ 'ORDER BY' => 'log_id', 'USE INDEX' => [ 'logging' => 'PRIMARY' ] ],
					[
						'user' => [ 'JOIN', 'user_id = ' . $actorQuery['fields']['log_user'] ]
					] + $commentQuery['joins'] + $actorQuery['joins']
				);
				$this->outputLogStream( $result );
				if ( $this->buffer == self::STREAM ) {
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
					if ( $this->buffer == self::STREAM ) {
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
			$revOpts = [ 'page' ];
			if ( $this->text != self::STUB ) {
				$revOpts[] = 'text';
			}
			$revQuery = Revision::getQueryInfo( $revOpts );

			// We want page primary rather than revision
			$tables = array_merge( [ 'page' ], array_diff( $revQuery['tables'], [ 'page' ] ) );
			$join = $revQuery['joins'] + [
				'revision' => $revQuery['joins']['page']
			];
			unset( $join['page'] );

			$fields = array_merge( $revQuery['fields'], [ 'page_restrictions' ] );

			$conds = [];
			if ( $cond !== '' ) {
				$conds[] = $cond;
			}
			$opts = [ 'ORDER BY' => 'page_id ASC' ];
			$opts['USE INDEX'] = [];
			if ( is_array( $this->history ) ) {
				# Time offset/limit for all pages/history...
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
					$conds[] = "rev_timestamp $op " .
						$this->db->addQuotes( $this->db->timestamp( $this->history['offset'] ) );
				}
				# Set query limit
				if ( !empty( $this->history['limit'] ) ) {
					$opts['LIMIT'] = intval( $this->history['limit'] );
				}
			} elseif ( $this->history & self::FULL ) {
				# Full history dumps...
				# query optimization for history stub dumps
				if ( $this->text == self::STUB && $orderRevs ) {
					$tables = $revQuery['tables'];
					$opts['ORDER BY'] = [ 'rev_page ASC', 'rev_id ASC' ];
					$opts['USE INDEX']['revision'] = 'rev_page_id';
					unset( $join['revision'] );
					$join['page'] = [ 'INNER JOIN', 'rev_page=page_id' ];
				}
			} elseif ( $this->history & self::CURRENT ) {
				# Latest revision dumps...
				if ( $this->list_authors && $cond != '' ) { // List authors, if so desired
					$this->do_list_authors( $cond );
				}
				$join['revision'] = [ 'INNER JOIN', 'page_id=rev_page AND page_latest=rev_id' ];
			} elseif ( $this->history & self::STABLE ) {
				# "Stable" revision dumps...
				# Default JOIN, to be overridden...
				$join['revision'] = [ 'INNER JOIN', 'page_id=rev_page AND page_latest=rev_id' ];
				# One, and only one hook should set this, and return false
				if ( Hooks::run( 'WikiExporter::dumpStableQuery', [ &$tables, &$opts, &$join ] ) ) {
					throw new MWException( __METHOD__ . " given invalid history dump type." );
				}
			} elseif ( $this->history & self::RANGE ) {
				# Dump of revisions within a specified range
				$opts['ORDER BY'] = [ 'rev_page ASC', 'rev_id ASC' ];
			} else {
				# Unknown history specification parameter?
				throw new MWException( __METHOD__ . " given invalid history dump type." );
			}

			if ( $this->buffer == self::STREAM ) {
				$prev = $this->db->bufferResults( false );
			}
			$result = null; // Assuring $result is not undefined, if exception occurs early
			try {
				Hooks::run( 'ModifyExportQuery',
						[ $this->db, &$tables, &$cond, &$opts, &$join ] );

				# Do the query!
				$result = $this->db->select(
					$tables,
					$fields,
					$conds,
					__METHOD__,
					$opts,
					$join
				);
				# Output dump results
				$this->outputPageStream( $result );

				if ( $this->buffer == self::STREAM ) {
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
					if ( $this->buffer == self::STREAM ) {
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
