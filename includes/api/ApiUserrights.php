<?php

/**
 * API userrights module
 *
 * Copyright © 2009 Roan Kattouw <roan.kattouw@gmail.com>
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

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Specials\SpecialUserRights;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @ingroup API
 */
class ApiUserrights extends ApiBase {

	use ApiWatchlistTrait;

	/** @var UserIdentity|null */
	private $mUser = null;

	private UserGroupManager $userGroupManager;
	private WatchedItemStoreInterface $watchedItemStore;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		UserGroupManager $userGroupManager,
		WatchedItemStoreInterface $watchedItemStore,
		WatchlistManager $watchlistManager,
		UserOptionsLookup $userOptionsLookup
	) {
		parent::__construct( $mainModule, $moduleName );
		$this->userGroupManager = $userGroupManager;
		$this->watchedItemStore = $watchedItemStore;

		// Variables needed in ApiWatchlistTrait trait
		$this->watchlistExpiryEnabled = $this->getConfig()->get( MainConfigNames::WatchlistExpiry );
		$this->watchlistMaxDuration =
			$this->getConfig()->get( MainConfigNames::WatchlistExpiryMaxDuration );
		$this->watchlistManager = $watchlistManager;
		$this->userOptionsLookup = $userOptionsLookup;
	}

	public function execute() {
		$pUser = $this->getUser();

		// Deny if the user is blocked and doesn't have the full 'userrights' permission.
		// This matches what Special:UserRights does for the web UI.
		if ( !$this->getAuthority()->isAllowed( 'userrights' ) ) {
			$block = $pUser->getBlock( IDBAccessObject::READ_LATEST );
			if ( $block && $block->isSitewide() ) {
				$this->dieBlocked( $block );
			}
		}

		$params = $this->extractRequestParams();

		// Figure out expiry times from the input
		$expiry = (array)$params['expiry'];
		$add = (array)$params['add'];
		if ( !$add ) {
			$expiry = [];
		} elseif ( count( $expiry ) !== count( $add ) ) {
			if ( count( $expiry ) === 1 ) {
				$expiry = array_fill( 0, count( $add ), $expiry[0] );
			} else {
				$this->dieWithError( [
					'apierror-toofewexpiries',
					count( $expiry ),
					count( $add )
				] );
			}
		}

		// Validate the expiries
		$groupExpiries = [];
		foreach ( $expiry as $index => $expiryValue ) {
			$group = $add[$index];
			$groupExpiries[$group] = SpecialUserRights::expiryToTimestamp( $expiryValue );

			if ( $groupExpiries[$group] === false ) {
				$this->dieWithError( [ 'apierror-invalidexpiry', wfEscapeWikiText( $expiryValue ) ] );
			}

			// not allowed to have things expiring in the past
			if ( $groupExpiries[$group] && $groupExpiries[$group] < wfTimestampNow() ) {
				$this->dieWithError( [ 'apierror-pastexpiry', wfEscapeWikiText( $expiryValue ) ] );
			}
		}

		$user = $this->getUrUser( $params );

		$tags = $params['tags'];

		// Check if user can add tags
		if ( $tags !== null ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $tags, $this->getAuthority() );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		$form = new SpecialUserRights();
		$form->setContext( $this->getContext() );
		$r = [];
		$r['user'] = $user->getName();
		$r['userid'] = $user->getId( $user->getWikiId() );
		[ $r['added'], $r['removed'] ] = $form->doSaveUserGroups(
			$user,
			$add,
			// Don't pass null to doSaveUserGroups() for array params, cast to empty array
			(array)$params['remove'],
			$params['reason'],
			(array)$tags,
			$groupExpiries
		);

		$userPage = Title::makeTitle( NS_USER, $user->getName() );
		$watchlistExpiry = $this->getExpiryFromParams( $params, $userPage, $this->getUser() );
		$watchuser = $params['watchuser'];
		if ( $watchuser && $user->getWikiId() === UserIdentity::LOCAL ) {
			$this->setWatch( 'watch', $userPage, $this->getUser(), null, $watchlistExpiry );
		} else {
			$watchuser = false;
			$watchlistExpiry = null;
		}
		$r['watchuser'] = $watchuser;
		if ( $watchlistExpiry !== null ) {
			$r['watchlistexpiry'] = $this->getWatchlistExpiry(
				$this->watchedItemStore,
				$userPage,
				$this->getUser()
			);
		}

		$result = $this->getResult();
		ApiResult::setIndexedTagName( $r['added'], 'group' );
		ApiResult::setIndexedTagName( $r['removed'], 'group' );
		$result->addValue( null, $this->getModuleName(), $r );
	}

	/**
	 * @param array $params
	 * @return UserIdentity
	 */
	private function getUrUser( array $params ) {
		if ( $this->mUser !== null ) {
			return $this->mUser;
		}

		$this->requireOnlyOneParameter( $params, 'user', 'userid' );

		$user = $params['user'] ?? '#' . $params['userid'];

		$form = new SpecialUserRights();
		$form->setContext( $this->getContext() );
		$status = $form->fetchUser( $user );
		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$this->mUser = $status->value;

		return $status->value;
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams( $flags = 0 ) {
		$allGroups = $this->userGroupManager->listAllGroups();

		if ( $flags & ApiBase::GET_VALUES_FOR_HELP ) {
			sort( $allGroups );
		}

		$params = [
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'id' ],
			],
			'userid' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'add' => [
				ParamValidator::PARAM_TYPE => $allGroups,
				ParamValidator::PARAM_ISMULTI => true
			],
			'expiry' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_ALLOW_DUPLICATES => true,
				ParamValidator::PARAM_DEFAULT => 'infinite',
			],
			'remove' => [
				ParamValidator::PARAM_TYPE => $allGroups,
				ParamValidator::PARAM_ISMULTI => true
			],
			'reason' => [
				ParamValidator::PARAM_DEFAULT => ''
			],
			'token' => [
				// Standard definition automatically inserted
				ApiBase::PARAM_HELP_MSG_APPEND => [ 'api-help-param-token-webui' ],
			],
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true
			],
			'watchuser' => false,
		];

		// Params appear in the docs in the order they are defined,
		// which is why this is here and not at the bottom.
		// @todo Find better way to support insertion at arbitrary position
		if ( $this->watchlistExpiryEnabled ) {
			$params += [
				'watchlistexpiry' => [
					ParamValidator::PARAM_TYPE => 'expiry',
					ExpiryDef::PARAM_MAX => $this->watchlistMaxDuration,
					ExpiryDef::PARAM_USE_MAX => true,
				]
			];
		}

		return $params;
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'userrights';
	}

	/** @inheritDoc */
	protected function getWebUITokenSalt( array $params ) {
		return $this->getUrUser( $params )->getName();
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=userrights&user=FooBot&add=bot&remove=sysop|bureaucrat&token=123ABC'
				=> 'apihelp-userrights-example-user',
			'action=userrights&userid=123&add=bot&remove=sysop|bureaucrat&token=123ABC'
				=> 'apihelp-userrights-example-userid',
			'action=userrights&user=SometimeSysop&add=sysop&expiry=1%20month&token=123ABC'
				=> 'apihelp-userrights-example-expiry',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:User_group_membership';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiUserrights::class, 'ApiUserrights' );
