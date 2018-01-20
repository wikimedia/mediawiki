<?php
/**
 * This class wraps around database result row.
 *
 * This is how I see the log system history:
 * - appending to plain wiki pages
 * - formatting log entries based on database fields
 * - user is now part of the action message
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
 * @author Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.19
 */

/**
 * This class wraps around database result row.
 *
 * @since 1.19
 */
class DatabaseLogEntry extends LogEntryBase {

	/**
	 * Returns array of information that is needed for querying
	 * log entries. Array contains the following keys:
	 * tables, fields, conds, options and join_conds
	 *
	 * @return array
	 */
	public static function getSelectQueryData() {
		$commentQuery = CommentStore::newKey( 'log_comment' )->getJoin();

		$tables = [ 'logging', 'user' ] + $commentQuery['tables'];
		$fields = [
			'log_id', 'log_type', 'log_action', 'log_timestamp',
			'log_user', 'log_user_text',
			'log_namespace', 'log_title', // unused log_page
			'log_params', 'log_deleted',
			'user_id', 'user_name', 'user_editcount',
		] + $commentQuery['fields'];

		$joins = [
			// IPs don't have an entry in user table
			'user' => [ 'LEFT JOIN', 'log_user=user_id' ],
		] + $commentQuery['joins'];

		return [
			'tables' => $tables,
			'fields' => $fields,
			'conds' => [],
			'options' => [],
			'join_conds' => $joins,
		];
	}

	/**
	 * Constructs new LogEntry from database result row.
	 * Supports rows from both logging and recentchanges table.
	 *
	 * @param stdClass|array $row
	 * @return DatabaseLogEntry
	 */
	public static function newFromRow( $row ) {
		$row = (object)$row;
		if ( isset( $row->rc_logid ) ) {
			return new RCDatabaseLogEntry( $row );
		} else {
			return new self( $row );
		}
	}

	/** @var stdClass Database result row. */
	protected $row;

	/** @var User */
	protected $performer;

	/** @var array Parameters for log entry */
	protected $params;

	/** @var int A rev id associated to the log entry */
	protected $revId = null;

	/** @var bool Whether the parameters for this log entry are stored in new or old format. */
	protected $legacy;

	protected function __construct( $row ) {
		$this->row = $row;
	}

	/**
	 * Returns the unique database id.
	 *
	 * @return int
	 */
	public function getId() {
		return (int)$this->row->log_id;
	}

	/**
	 * Returns whatever is stored in the database field.
	 *
	 * @return string
	 */
	protected function getRawParameters() {
		return $this->row->log_params;
	}

	public function isLegacy() {
		// This extracts the property
		$this->getParameters();
		return $this->legacy;
	}

	public function getType() {
		return $this->row->log_type;
	}

	public function getSubtype() {
		return $this->row->log_action;
	}

	public function getParameters() {
		if ( !isset( $this->params ) ) {
			$blob = $this->getRawParameters();
			MediaWiki\suppressWarnings();
			$params = LogEntryBase::extractParams( $blob );
			MediaWiki\restoreWarnings();
			if ( $params !== false ) {
				$this->params = $params;
				$this->legacy = false;
			} else {
				$this->params = LogPage::extractParams( $blob );
				$this->legacy = true;
			}

			if ( isset( $this->params['associated_rev_id'] ) ) {
				$this->revId = $this->params['associated_rev_id'];
				unset( $this->params['associated_rev_id'] );
			}
		}

		return $this->params;
	}

	public function getAssociatedRevId() {
		// This extracts the property
		$this->getParameters();
		return $this->revId;
	}

	public function getPerformer() {
		if ( !$this->performer ) {
			$userId = (int)$this->row->log_user;
			if ( $userId !== 0 ) {
				// logged-in users
				if ( isset( $this->row->user_name ) ) {
					$this->performer = User::newFromRow( $this->row );
				} else {
					$this->performer = User::newFromId( $userId );
				}
			} else {
				// IP users
				$userText = $this->row->log_user_text;
				$this->performer = User::newFromName( $userText, false );
			}
		}

		return $this->performer;
	}

	public function getTarget() {
		$namespace = $this->row->log_namespace;
		$page = $this->row->log_title;
		$title = Title::makeTitle( $namespace, $page );

		return $title;
	}

	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->row->log_timestamp );
	}

	public function getComment() {
		return CommentStore::newKey( 'log_comment' )->getComment( $this->row )->text;
	}

	public function getDeleted() {
		return $this->row->log_deleted;
	}
}
