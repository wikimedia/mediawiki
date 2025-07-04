<?php
/**
 * Copyright © 2004 Brooke Vibber, lcrocker, Tim Starling,
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
 */

namespace MediaWiki\Specials;

use MediaWiki\Block\HideUserUtils;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\Pager\UsersPager;
use MediaWiki\SpecialPage\IncludableSpecialPage;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentityLookup;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implements Special:Listusers
 *
 * @ingroup SpecialPage
 */
class SpecialListUsers extends IncludableSpecialPage {

	private LinkBatchFactory $linkBatchFactory;
	private IConnectionProvider $dbProvider;
	private UserGroupManager $userGroupManager;
	private UserIdentityLookup $userIdentityLookup;
	private HideUserUtils $hideUserUtils;
	private TempUserConfig $tempUserConfig;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		UserGroupManager $userGroupManager,
		UserIdentityLookup $userIdentityLookup,
		HideUserUtils $hideUserUtils,
		TempUserConfig $tempUserConfig
	) {
		parent::__construct( 'Listusers' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->dbProvider = $dbProvider;
		$this->userGroupManager = $userGroupManager;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->hideUserUtils = $hideUserUtils;
		$this->tempUserConfig = $tempUserConfig;
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
			$this->dbProvider,
			$this->userGroupManager,
			$this->userIdentityLookup,
			$this->hideUserUtils,
			$this->tempUserConfig,
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

	/** @inheritDoc */
	protected function getGroupName() {
		return 'users';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialListUsers::class, 'SpecialListUsers' );
