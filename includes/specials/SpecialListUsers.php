<?php
/**
 * Implements Special:Listusers
 *
 * Copyright © 2004 Brion Vibber, lcrocker, Tim Starling,
 * Domas Mituzas, Antoine Musso, Jens Frank, Zhengzhu,
 * 2006 Rob Church <robchur@gmail.com>
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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\User\UserGroupManager;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @ingroup SpecialPage
 */
class SpecialListUsers extends IncludableSpecialPage {

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var UserGroupManager */
	private $userGroupManager;

	/**
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param ILoadBalancer $loadBalancer
	 * @param UserGroupManager $userGroupManager
	 */
	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		ILoadBalancer $loadBalancer,
		UserGroupManager $userGroupManager
	) {
		parent::__construct( 'Listusers' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->loadBalancer = $loadBalancer;
		$this->userGroupManager = $userGroupManager;
	}

	/**
	 * @param string|null $par A group to list users from
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$up = new UsersPager(
			$this->getContext(),
			$this->getHookContainer(),
			$this->linkBatchFactory,
			$this->loadBalancer,
			$this->userGroupManager,
			$par,
			$this->including()
		);

		# getBody() first to check, if empty
		$usersbody = $up->getBody();

		$s = '';
		if ( !$this->including() ) {
			$s = $up->getPageHeader();
		}

		if ( $usersbody ) {
			$s .= $up->getNavigationBar();
			$s .= Html::rawElement( 'ul', [], $usersbody );
			$s .= $up->getNavigationBar();
		} else {
			$s .= $this->msg( 'listusers-noresult' )->parseAsBlock();
		}

		$out = $this->getOutput();
		$out->addHTML( $s );
		$out->addModuleStyles( 'mediawiki.interface.helpers.styles' );
	}

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @return string[] subpages
	 */
	public function getSubpagesForPrefixSearch() {
		return $this->userGroupManager->listAllGroups();
	}

	protected function getGroupName() {
		return 'users';
	}
}
