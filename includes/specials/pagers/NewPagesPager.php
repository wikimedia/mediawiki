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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Permissions\GroupPermissionsLookup;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @ingroup Pager
 */
class NewPagesPager extends ReverseChronologicalPager {

	/**
	 * @var FormOptions
	 */
	protected $opts;

	/**
	 * @var SpecialNewpages
	 */
	protected $mForm;

	/** @var GroupPermissionsLookup */
	private $groupPermissionsLookup;

	/** @var HookRunner */
	private $hookRunner;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/**
	 * @param SpecialNewpages $form
	 * @param GroupPermissionsLookup $groupPermissionsLookup
	 * @param HookContainer $hookContainer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param ILoadBalancer $loadBalancer
	 * @param NamespaceInfo $namespaceInfo
	 * @param FormOptions $opts
	 */
	public function __construct(
		SpecialNewpages $form,
		GroupPermissionsLookup $groupPermissionsLookup,
		HookContainer $hookContainer,
		LinkBatchFactory $linkBatchFactory,
		ILoadBalancer $loadBalancer,
		NamespaceInfo $namespaceInfo,
		FormOptions $opts
	) {
		// Set database before parent constructor to avoid setting it there with wfGetDB
		$this->mDb = $loadBalancer->getConnectionRef( ILoadBalancer::DB_REPLICA );
		parent::__construct( $form->getContext() );
		$this->groupPermissionsLookup = $groupPermissionsLookup;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->mForm = $form;
		$this->opts = $opts;
	}

	public function getQueryInfo() {
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
			$conds['actor_name'] = $user->getText();
		} elseif ( $this->canAnonymousUsersCreatePages() && $this->opts->getValue( 'hideliu' ) ) {
			# If anons cannot make new pages, don't "exclude logged in users"!
			$conds['actor_user'] = null;
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
			'length' => 'page_len', 'rev_id' => 'page_latest', 'page_namespace', 'page_title',
			'page_content_model',
		] );
		$join_conds = [ 'page' => [ 'JOIN', 'page_id=rc_cur_id' ] ] + $rcQuery['joins'];

		$this->hookRunner->onSpecialNewpagesConditions(
			$this, $this->opts, $conds, $tables, $fields, $join_conds );

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

	private function canAnonymousUsersCreatePages() {
		return $this->groupPermissionsLookup->groupHasPermission( '*', 'createpage' ) ||
			$this->groupPermissionsLookup->groupHasPermission( '*', 'createtalk' );
	}

	// Based on ContribsPager.php
	private function getNamespaceCond() {
		$namespace = $this->opts->getValue( 'namespace' );
		if ( $namespace === 'all' || $namespace === '' ) {
			return [];
		}

		$namespace = intval( $namespace );
		if ( $namespace < NS_MAIN ) {
			// Negative namespaces are invalid
			return [];
		}

		$invert = $this->opts->getValue( 'invert' );
		$associated = $this->opts->getValue( 'associated' );

		$eq_op = $invert ? '!=' : '=';
		$bool_op = $invert ? 'AND' : 'OR';

		$dbr = $this->getDatabase();
		$selectedNS = $dbr->addQuotes( $namespace );
		if ( !$associated ) {
			return [ "rc_namespace $eq_op $selectedNS" ];
		}

		$associatedNS = $dbr->addQuotes(
			$this->namespaceInfo->getAssociated( $namespace )
		);
		return [
			"rc_namespace $eq_op $selectedNS " .
			$bool_op .
			" rc_namespace $eq_op $associatedNS"
		];
	}

	public function getIndexField() {
		return 'rc_timestamp';
	}

	public function formatRow( $row ) {
		return $this->mForm->formatRow( $row );
	}

	protected function getStartBody() {
		# Do a batch existence check on pages
		$linkBatch = $this->linkBatchFactory->newLinkBatch();
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
