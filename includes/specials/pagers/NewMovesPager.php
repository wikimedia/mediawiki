<?php
/**
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
 * @ingroup Pager
 */

/**
 * @ingroup Pager
 */
class NewMovesPager extends ReverseChronologicalPager {

	// Stored opts
	protected $opts;

	/**
	 * @var HtmlForm
	 */
	protected $mForm;

	function __construct( $form, FormOptions $opts ) {
		parent::__construct( $form->getContext() );
		$this->mForm = $form;
		$this->opts = $opts;
	}

	function getQueryInfo() {
		$conds = [];
		$conds['rc.rc_type'] = RC_LOG;
		$conds['rc.rc_log_type'] = 'move';

		$namespace = $this->opts->getValue( 'namespace' );
		$namespace = ( $namespace === 'all' ) ? false : intval( $namespace );

		$username = $this->opts->getValue( 'username' );
		$user = Title::makeTitleSafe( NS_USER, $username );

		$rcIndexes = [];

		if ( $namespace !== false ) {
			if ( $this->opts->getValue( 'invert' ) ) {
				$conds[] = 'page_namespace != ' . $this->mDb->addQuotes( $namespace );
			} else {
				$conds['page_namespace'] = $namespace;
			}
		}

		if ( $user ) {
			$conds['rc.rc_user_text'] = $user->getText();
			$rcIndexes = 'rc_user_text';
		} elseif ( User::groupHasPermission( '*', 'move' ) &&
			$this->opts->getValue( 'hideliu' )
		) {
			# If anons cannot move pages, don't "exclude logged in users"!
			$conds['rc.rc_user'] = 0;
		}

		# If this user cannot see patrolled edits or they are off, don't do dumb queries!
		if ( $this->opts->getValue( 'hidepatrolled' ) && $this->getUser()->useMovePatrol() ) {
			$conds['rc.rc_patrolled'] = 0;
		}

		if ( $this->opts->getValue( 'hidebots' ) ) {
			$conds['rc.rc_bot'] = 0;
		}

		$tables = [ 'rc' => 'recentchanges', 'rcjoin' => 'recentchanges', 'page' ];
		$fields = [
			'rc.rc_namespace', 'rc.rc_title', 'rc.rc_cur_id', 'rc.rc_user', 'rc.rc_user_text',
			'rc.rc_comment', 'rc.rc_timestamp', 'rc.rc_patrolled', 'rc.rc_id', 'rc.rc_deleted',
			'rc.rc_this_oldid', 'rc.rc_log_type', 'rc.rc_log_action', 'rc.rc_logid', 'rc.rc_params',
			'page_namespace', 'page_title'
		];
		$join_conds = [
			'rcjoin' => [ 'LEFT OUTER JOIN',
				[ 'rc.rc_cur_id=rcjoin.rc_cur_id', 'rc.rc_timestamp<rcjoin.rc_timestamp' ]
			],
			'page' => [ 'INNER JOIN',
				[ 'rc.rc_cur_id=page_id' ]
			],
		];
		$conds[] = 'rcjoin.rc_id IS NULL';

		$options = [];

		if ( $rcIndexes ) {
			$options = [ 'USE INDEX' => [ 'rc' => $rcIndexes ] ];
		}

		$info = [
			'tables' => $tables,
			'fields' => $fields,
			'conds' => $conds,
			'options' => $options,
			'join_conds' => $join_conds
		];

		// Modify query for tags
		ChangeTags::modifyDisplayQuery(
			$info['tables'],
			$info['fields'],
			$info['conds'],
			$info['join_conds'],
			$info['options'],
			$this->opts['tagfilter']
		);

		return $info;
	}

	function getIndexField() {
		return 'rc.rc_timestamp';
	}

	function formatRow( $row ) {
		return $this->mForm->formatRow( $row );
	}

	function getStartBody() {
		# Do a batch existence check on pages
		$linkBatch = new LinkBatch();
		foreach ( $this->mResult as $row ) {
			$linkBatch->add( NS_USER, $row->rc_user_text );
			$linkBatch->add( NS_USER_TALK, $row->rc_user_text );
			$linkBatch->add( $row->page_namespace, $row->page_title );
			$linkBatch->add( $row->rc_namespace, $row->rc_title );
		}
		$linkBatch->execute();

		return '<ul>';
	}

	function getEndBody() {
		return '</ul>';
	}
}
