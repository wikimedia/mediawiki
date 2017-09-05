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
class NewPagesPager extends ReverseChronologicalPager {

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
		$conds['rc_new'] = 1;

		$namespace = $this->opts->getValue( 'namespace' );
		$namespace = ( $namespace === 'all' ) ? false : intval( $namespace );

		$username = $this->opts->getValue( 'username' );
		$user = Title::makeTitleSafe( NS_USER, $username );

		$rcIndexes = [];

		if ( $namespace !== false ) {
			if ( $this->opts->getValue( 'invert' ) ) {
				$conds[] = 'rc_namespace != ' . $this->mDb->addQuotes( $namespace );
			} else {
				$conds['rc_namespace'] = $namespace;
			}
		}

		if ( $user ) {
			$conds['rc_user_text'] = $user->getText();
			$rcIndexes = 'rc_user_text';
		} elseif ( User::groupHasPermission( '*', 'createpage' ) &&
			$this->opts->getValue( 'hideliu' )
		) {
			# If anons cannot make new pages, don't "exclude logged in users"!
			$conds['rc_user'] = 0;
		}

		# If this user cannot see patrolled edits or they are off, don't do dumb queries!
		if ( $this->opts->getValue( 'hidepatrolled' ) && $this->getUser()->useNPPatrol() ) {
			$conds['rc_patrolled'] = 0;
		}

		if ( $this->opts->getValue( 'hidebots' ) ) {
			$conds['rc_bot'] = 0;
		}

		if ( $this->opts->getValue( 'hideredirs' ) ) {
			$conds['page_is_redirect'] = 0;
		}

		// Allow changes to the New Pages query
		$tables = [ 'recentchanges', 'page' ];
		$fields = [
			'rc_namespace', 'rc_title', 'rc_cur_id', 'rc_user', 'rc_user_text',
			'rc_comment', 'rc_timestamp', 'rc_patrolled', 'rc_id', 'rc_deleted',
			'length' => 'page_len', 'rev_id' => 'page_latest', 'rc_this_oldid',
			'page_namespace', 'page_title'
		];
		$join_conds = [ 'page' => [ 'INNER JOIN', 'page_id=rc_cur_id' ] ];

		// Avoid PHP 7.1 warning from passing $this by reference
		$pager = $this;
		Hooks::run( 'SpecialNewpagesConditions',
			[ &$pager, $this->opts, &$conds, &$tables, &$fields, &$join_conds ] );

		$options = [];

		if ( $rcIndexes ) {
			$options = [ 'USE INDEX' => [ 'recentchanges' => $rcIndexes ] ];
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
		return 'rc_timestamp';
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
		}
		$linkBatch->execute();

		return '<ul>';
	}

	function getEndBody() {
		return '</ul>';
	}
}
