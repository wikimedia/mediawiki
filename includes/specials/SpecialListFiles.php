<?php
/**
 * Implements Special:Listfiles
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
 * @ingroup SpecialPage
 */

use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use Wikimedia\Rdbms\ILoadBalancer;

class SpecialListFiles extends IncludableSpecialPage {

	/** @var RepoGroup */
	private $repoGroup;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var CommentStore */
	private $commentStore;

	/** @var ActorMigration */
	private $actorMigration;

	/** @var UserNameUtils */
	private $userNameUtils;

	/** @var UserNamePrefixSearch */
	private $userNamePrefixSearch;

	/**
	 * @param RepoGroup $repoGroup
	 * @param PermissionManager $permissionManager
	 * @param ILoadBalancer $loadBalancer
	 * @param CommentStore $commentStore
	 * @param ActorMigration $actorMigration
	 * @param UserNameUtils $userNameUtils
	 * @param UserNamePrefixSearch $userNamePrefixSearch
	 */
	public function __construct(
		RepoGroup $repoGroup,
		PermissionManager $permissionManager,
		ILoadBalancer $loadBalancer,
		CommentStore $commentStore,
		ActorMigration $actorMigration,
		UserNameUtils $userNameUtils,
		UserNamePrefixSearch $userNamePrefixSearch
	) {
		parent::__construct( 'Listfiles' );
		$this->repoGroup = $repoGroup;
		$this->permissionManager = $permissionManager;
		$this->loadBalancer = $loadBalancer;
		$this->commentStore = $commentStore;
		$this->actorMigration = $actorMigration;
		$this->userNameUtils = $userNameUtils;
		$this->userNamePrefixSearch = $userNamePrefixSearch;
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Managing_files' );

		if ( $this->including() ) {
			$userName = $par;
			$search = '';
			$showAll = false;
		} else {
			$userName = $this->getRequest()->getText( 'user', $par );
			$search = $this->getRequest()->getText( 'ilsearch', '' );
			$showAll = $this->getRequest()->getBool( 'ilshowall', false );
		}
		// Sanitize usernames to avoid symbols in the title of page.
		$sanitizedUserName = $this->userNameUtils->getCanonical( $userName, UserNameUtils::RIGOR_NONE );
		if ( $sanitizedUserName ) {
			$userName = $sanitizedUserName;
		}

		if ( $userName ) {
			$pageTitle = $this->msg( 'listfiles_subpage', $userName );
		} else {
			$pageTitle = $this->msg( 'listfiles' );
		}

		$pager = new ImageListPager(
			$this->getContext(),
			$userName,
			$search,
			$this->including(),
			$showAll,
			$this->getLinkRenderer(),
			$this->repoGroup,
			$this->permissionManager,
			$this->loadBalancer,
			$this->commentStore,
			$this->actorMigration,
			UserCache::singleton()
		);

		$out = $this->getOutput();
		$out->setPageTitle( $pageTitle );
		$out->addModuleStyles( 'mediawiki.special' );
		if ( $this->including() ) {
			$out->addParserOutputContent( $pager->getBodyOutput() );
		} else {
			$user = $pager->getRelevantUser();
			$this->getSkin()->setRelevantUser( $user );
			$pager->getForm();
			$out->addParserOutputContent( $pager->getFullOutput() );
		}
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$search = $this->userNameUtils->getCanonical( $search );
		if ( !$search ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return $this->userNamePrefixSearch
			->search( UserNamePrefixSearch::AUDIENCE_PUBLIC, $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'media';
	}
}
