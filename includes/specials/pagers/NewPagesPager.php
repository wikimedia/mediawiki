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
use MediaWiki\MediaWikiServices;

class NewPagesPager extends ReverseChronologicalPager {

	/**
	 * @var FormOptions
	 */
	protected $opts;

	/**
	 * @var SpecialNewpages
	 */
	protected $mForm;

	/**
	 * @param SpecialNewpages $form
	 * @param FormOptions $opts
	 */
	public function __construct( $form, FormOptions $opts ) {
		parent::__construct( $form->getContext() );
		$this->mForm = $form;
		$this->opts = $opts;
	}

	function getQueryInfo() {
		$rcQuery = RecentChange::getQueryInfo();

		$conds = [];
		$conds['rc_new'] = 1;

		$username = $this->opts->getValue( 'username' );
		$user = Title::makeTitleSafe( NS_USER, $username );

		$size = abs( intval( $this->opts->getValue( 'size' ) ) );
		if ( $size > 0 ) {
			if ( $this->opts->getValue( 'size-mode' ) === 'max' ) {
				$conds[] = 'page_len <= ' . $size;
			} else {
				$conds[] = 'page_len >= ' . $size;
			}
		}

		if ( $user ) {
			$conds[] = ActorMigration::newMigration()->getWhere(
				$this->mDb, 'rc_user', User::newFromName( $user->getText(), false ), false
			)['conds'];
		} elseif ( MediaWikiServices::getInstance()
					->getPermissionManager()
					->groupHasPermission( '*', 'createpage' ) &&
			$this->opts->getValue( 'hideliu' )
		) {
			# If anons cannot make new pages, don't "exclude logged in users"!
			$conds[] = ActorMigration::newMigration()->isAnon( $rcQuery['fields']['rc_user'] );
		}

		$conds = array_merge( $conds, $this->getNamespaceCond() );

		# If this user cannot see patrolled edits or they are off, don't do dumb queries!
		if ( $this->opts->getValue( 'hidepatrolled' ) && $this->getUser()->useNPPatrol() ) {
			$conds['rc_patrolled'] = RecentChange::PRC_UNPATROLLED;
		}

		if ( $this->opts->getValue( 'hidebots' ) ) {
			$conds['rc_bot'] = 0;
		}

		if ( $this->opts->getValue( 'hideredirs' ) ) {
			$conds['page_is_redirect'] = 0;
		}

		// Allow changes to the New Pages query
		$tables = array_merge( $rcQuery['tables'], [ 'page' ] );
		$fields = array_merge( $rcQuery['fields'], [
			'length' => 'page_len', 'rev_id' => 'page_latest', 'page_namespace', 'page_title'
		] );
		$join_conds = [ 'page' => [ 'JOIN', 'page_id=rc_cur_id' ] ] + $rcQuery['joins'];

		// Avoid PHP 7.1 warning from passing $this by reference
		$pager = $this;
		Hooks::run( 'SpecialNewpagesConditions',
			[ &$pager, $this->opts, &$conds, &$tables, &$fields, &$join_conds ] );

		$info = [
			'tables' => $tables,
			'fields' => $fields,
			'conds' => $conds,
			'options' => [],
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

	// Based on ContribsPager.php
	function getNamespaceCond() {
		$namespace = $this->opts->getValue( 'namespace' );
		if ( $namespace === 'all' || $namespace === '' ) {
			return [];
		}

		$namespace = intval( $namespace );
		$invert = $this->opts->getValue( 'invert' );
		$associated = $this->opts->getValue( 'associated' );

		$eq_op = $invert ? '!=' : '=';
		$bool_op = $invert ? 'AND' : 'OR';

		$selectedNS = $this->mDb->addQuotes( $namespace );
		if ( !$associated ) {
			return [ "rc_namespace $eq_op $selectedNS" ];
		}

		$associatedNS = $this->mDb->addQuotes(
			MediaWikiServices::getInstance()->getNamespaceInfo()->getAssociated( $namespace )
		);
		return [
			"rc_namespace $eq_op $selectedNS " .
			$bool_op .
			" rc_namespace $eq_op $associatedNS"
		];
	}

	function getIndexField() {
		return 'rc_timestamp';
	}

	function formatRow( $row ) {
		return $this->mForm->formatRow( $row );
	}

	protected function getStartBody() {
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

	protected function getEndBody() {
		return '</ul>';
	}
}
