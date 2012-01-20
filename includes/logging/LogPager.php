<?php
/**
 * Contain classes to list log entries
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>, 2008 Aaron Schulz
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
 * @ingroup Pager
 */
class LogPager extends ReverseChronologicalPager {
	private $types = array(), $performer = '', $title = '', $pattern = '';
	private $typeCGI = '';
	public $mLogEventsList;

	/**
	 * Constructor
	 *
	 * @param $list LogEventsList
	 * @param $types String or Array: log types to show
	 * @param $performer String: the user who made the log entries
	 * @param $title String|Title: the page title the log entries are for
	 * @param $pattern String: do a prefix search rather than an exact title match
	 * @param $conds Array: extra conditions for the query
	 * @param $year Integer: the year to start from
	 * @param $month Integer: the month to start from
	 * @param $tagFilter String: tag
	 */
	public function __construct( $list, $types = array(), $performer = '', $title = '', $pattern = '',
		$conds = array(), $year = false, $month = false, $tagFilter = '' ) {
		parent::__construct( $list->getContext() );
		$this->mConds = $conds;

		$this->mLogEventsList = $list;

		$this->limitType( $types ); // also excludes hidden types
		$this->limitPerformer( $performer );
		$this->limitTitle( $title, $pattern );
		$this->getDateCond( $year, $month );
		$this->mTagFilter = $tagFilter;
	}

	public function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['type'] = $this->typeCGI; // arrays won't work here
		$query['user'] = $this->performer;
		$query['month'] = $this->mMonth;
		$query['year'] = $this->mYear;
		return $query;
	}

	// Call ONLY after calling $this->limitType() already!
	public function getFilterParams() {
		global $wgFilterLogTypes;
		$filters = array();
		if( count($this->types) ) {
			return $filters;
		}
		foreach( $wgFilterLogTypes as $type => $default ) {
			// Avoid silly filtering
			if( $type !== 'patrol' || $this->getUser()->useNPPatrol() ) {
				$hide = $this->getRequest()->getInt( "hide_{$type}_log", $default );
				$filters[$type] = $hide;
				if( $hide )
					$this->mConds[] = 'log_type != ' . $this->mDb->addQuotes( $type );
			}
		}
		return $filters;
	}

	/**
	 * Set the log reader to return only entries of the given type.
	 * Type restrictions enforced here
	 *
	 * @param $types String or array: Log types ('upload', 'delete', etc);
	 *   empty string means no restriction
	 */
	private function limitType( $types ) {
		global $wgLogRestrictions;
		// If $types is not an array, make it an array
		$types = ($types === '') ? array() : (array)$types;
		// Don't even show header for private logs; don't recognize it...
		$needReindex = false;
		foreach ( $types as $type ) {
			if( isset( $wgLogRestrictions[$type] )
				&& !$this->getUser()->isAllowed($wgLogRestrictions[$type])
			) {
				$needReindex = true;
				$types = array_diff( $types, array( $type ) );
			}
		}
		if ( $needReindex ) {
			// Lots of this code makes assumptions that
			// the first entry in the array is $types[0].
			$types = array_values( $types );
		}
		$this->types = $types;
		// Don't show private logs to unprivileged users.
		// Also, only show them upon specific request to avoid suprises.
		$audience = $types ? 'user' : 'public';
		$hideLogs = LogEventsList::getExcludeClause( $this->mDb, $audience );
		if( $hideLogs !== false ) {
			$this->mConds[] = $hideLogs;
		}
		if( count($types) ) {
			$this->mConds['log_type'] = $types;
			// Set typeCGI; used in url param for paging
			if( count($types) == 1 ) $this->typeCGI = $types[0];
		}
	}

	/**
	 * Set the log reader to return only entries by the given user.
	 *
	 * @param $name String: (In)valid user name
	 */
	private function limitPerformer( $name ) {
		if( $name == '' ) {
			return false;
		}
		$usertitle = Title::makeTitleSafe( NS_USER, $name );
		if( is_null($usertitle) ) {
			return false;
		}
		/* Fetch userid at first, if known, provides awesome query plan afterwards */
		$userid = User::idFromName( $name );
		if( !$userid ) {
			/* It should be nicer to abort query at all,
			   but for now it won't pass anywhere behind the optimizer */
			$this->mConds[] = "NULL";
		} else {
			$this->mConds['log_user'] = $userid;
			// Paranoia: avoid brute force searches (bug 17342)
			$user = $this->getUser();
			if( !$user->isAllowed( 'deletedhistory' ) ) {
				$this->mConds[] = $this->mDb->bitAnd('log_deleted', LogPage::DELETED_USER) . ' = 0';
			} elseif( !$user->isAllowed( 'suppressrevision' ) ) {
				$this->mConds[] = $this->mDb->bitAnd('log_deleted', LogPage::SUPPRESSED_USER) .
					' != ' . LogPage::SUPPRESSED_USER;
			}
			$this->performer = $usertitle->getText();
		}
	}

	/**
	 * Set the log reader to return only entries affecting the given page.
	 * (For the block and rights logs, this is a user page.)
	 *
	 * @param $page String or Title object: Title name
	 * @param $pattern String
	 */
	private function limitTitle( $page, $pattern ) {
		global $wgMiserMode;

		if ( $page instanceof Title ) {
			$title = $page;
		} else {
			$title = Title::newFromText( $page );
			if( strlen( $page ) == 0 || !$title instanceof Title ) {
				return false;
			}
		}

		$this->title = $title->getPrefixedText();
		$ns = $title->getNamespace();
		$db = $this->mDb;

		# Using the (log_namespace, log_title, log_timestamp) index with a
		# range scan (LIKE) on the first two parts, instead of simple equality,
		# makes it unusable for sorting.  Sorted retrieval using another index
		# would be possible, but then we might have to scan arbitrarily many
		# nodes of that index. Therefore, we need to avoid this if $wgMiserMode
		# is on.
		#
		# This is not a problem with simple title matches, because then we can
		# use the page_time index.  That should have no more than a few hundred
		# log entries for even the busiest pages, so it can be safely scanned
		# in full to satisfy an impossible condition on user or similar.
		if( $pattern && !$wgMiserMode ) {
			$this->mConds['log_namespace'] = $ns;
			$this->mConds[] = 'log_title ' . $db->buildLike( $title->getDBkey(), $db->anyString() );
			$this->pattern = $pattern;
		} else {
			$this->mConds['log_namespace'] = $ns;
			$this->mConds['log_title'] = $title->getDBkey();
		}
		// Paranoia: avoid brute force searches (bug 17342)
		$user = $this->getUser();
		if( !$user->isAllowed( 'deletedhistory' ) ) {
			$this->mConds[] = $db->bitAnd('log_deleted', LogPage::DELETED_ACTION) . ' = 0';
		} elseif( !$user->isAllowed( 'suppressrevision' ) ) {
			$this->mConds[] = $db->bitAnd('log_deleted', LogPage::SUPPRESSED_ACTION) .
				' != ' . LogPage::SUPPRESSED_ACTION;
		}
	}

	/**
	 * Constructs the most part of the query. Extra conditions are sprinkled in
	 * all over this class.
	 * @return array
	 */
	public function getQueryInfo() {
		$basic = DatabaseLogEntry::getSelectQueryData();

		$tables = $basic['tables'];
		$fields = $basic['fields'];
		$conds = $basic['conds'];
		$options = $basic['options'];
		$joins = $basic['join_conds'];

		$index = array();
		# Add log_search table if there are conditions on it.
		# This filters the results to only include log rows that have
		# log_search records with the specified ls_field and ls_value values.
		if( array_key_exists( 'ls_field', $this->mConds ) ) {
			$tables[] = 'log_search';
			$index['log_search'] = 'ls_field_val';
			$index['logging'] = 'PRIMARY';
			if ( !$this->hasEqualsClause( 'ls_field' )
				|| !$this->hasEqualsClause( 'ls_value' ) )
			{
				# Since (ls_field,ls_value,ls_logid) is unique, if the condition is
				# to match a specific (ls_field,ls_value) tuple, then there will be
				# no duplicate log rows. Otherwise, we need to remove the duplicates.
				$options[] = 'DISTINCT';
			}
		# Avoid usage of the wrong index by limiting
		# the choices of available indexes. This mainly
		# avoids site-breaking filesorts.
		} elseif( $this->title || $this->pattern || $this->performer ) {
			$index['logging'] = array( 'page_time', 'user_time' );
			if( count($this->types) == 1 ) {
				$index['logging'][] = 'log_user_type_time';
			}
		} elseif( count($this->types) == 1 ) {
			$index['logging'] = 'type_time';
		} else {
			$index['logging'] = 'times';
		}
		$options['USE INDEX'] = $index;
		# Don't show duplicate rows when using log_search
		$joins['log_search'] = array( 'INNER JOIN', 'ls_log_id=log_id' );

		$info = array(
			'tables'     => $tables,
			'fields'     => $fields,
			'conds'      => array_merge( $conds, $this->mConds ),
			'options'    => $options,
			'join_conds' => $joins,
		);
		# Add ChangeTags filter query
		ChangeTags::modifyDisplayQuery( $info['tables'], $info['fields'], $info['conds'],
			$info['join_conds'], $info['options'], $this->mTagFilter );
		return $info;
	}

	/**
	 * Checks if $this->mConds has $field matched to a *single* value
	 * @param $field
	 * @return bool
	 */
	protected function hasEqualsClause( $field ) {
		return (
			array_key_exists( $field, $this->mConds ) &&
			( !is_array( $this->mConds[$field] ) || count( $this->mConds[$field] ) == 1 )
		);
	}

	function getIndexField() {
		return 'log_timestamp';
	}

	public function getStartBody() {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		if( $this->getNumRows() > 0 ) {
			$lb = new LinkBatch;
			foreach ( $this->mResult as $row ) {
				$lb->add( $row->log_namespace, $row->log_title );
				$lb->addObj( Title::makeTitleSafe( NS_USER, $row->user_name ) );
				$lb->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->user_name ) );
				$formatter = LogFormatter::newFromRow( $row );
				foreach ( $formatter->getPreloadTitles() as $title ) {
					$lb->addObj( $title );
				}
			}
			$lb->execute();
			$this->mResult->seek( 0 );
		}
		wfProfileOut( __METHOD__ );
		return '';
	}

	public function formatRow( $row ) {
		return $this->mLogEventsList->logLine( $row );
	}

	public function getType() {
		return $this->types;
	}

	/**
	 * @return string
	 */
	public function getPerformer() {
		return $this->performer;
	}

	/**
	 * @return string
	 */
	public function getPage() {
		return $this->title;
	}

	public function getPattern() {
		return $this->pattern;
	}

	public function getYear() {
		return $this->mYear;
	}

	public function getMonth() {
		return $this->mMonth;
	}

	public function getTagFilter() {
		return $this->mTagFilter;
	}

	public function doQuery() {
		// Workaround MySQL optimizer bug
		$this->mDb->setBigSelects();
		parent::doQuery();
		$this->mDb->setBigSelects( 'default' );
	}
}
