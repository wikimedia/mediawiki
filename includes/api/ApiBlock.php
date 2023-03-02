<?php
/**
 * Copyright © 2007 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockPermissionCheckerFactory;
use MediaWiki\Block\BlockUserFactory;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\TitleDef;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;

/**
 * API module that facilitates the blocking of users. Requires API write mode
 * to be enabled.
 *
 * @ingroup API
 */
class ApiBlock extends ApiBase {

	use ApiBlockInfoTrait;
	use ApiWatchlistTrait;

	/** @var BlockPermissionCheckerFactory */
	private $blockPermissionCheckerFactory;

	/** @var BlockUserFactory */
	private $blockUserFactory;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var UserIdentityLookup */
	private $userIdentityLookup;

	/** @var WatchedItemStoreInterface */
	private $watchedItemStore;

	/** @var BlockUtils */
	private $blockUtils;

	/** @var BlockActionInfo */
	private $blockActionInfo;

	/**
	 * @param ApiMain $main
	 * @param string $action
	 * @param BlockPermissionCheckerFactory $blockPermissionCheckerFactory
	 * @param BlockUserFactory $blockUserFactory
	 * @param TitleFactory $titleFactory
	 * @param UserIdentityLookup $userIdentityLookup
	 * @param WatchedItemStoreInterface $watchedItemStore
	 * @param BlockUtils $blockUtils
	 * @param BlockActionInfo $blockActionInfo
	 * @param WatchlistManager $watchlistManager
	 * @param UserOptionsLookup $userOptionsLookup
	 */
	public function __construct(
		ApiMain $main,
		$action,
		BlockPermissionCheckerFactory $blockPermissionCheckerFactory,
		BlockUserFactory $blockUserFactory,
		TitleFactory $titleFactory,
		UserIdentityLookup $userIdentityLookup,
		WatchedItemStoreInterface $watchedItemStore,
		BlockUtils $blockUtils,
		BlockActionInfo $blockActionInfo,
		WatchlistManager $watchlistManager,
		UserOptionsLookup $userOptionsLookup
	) {
		parent::__construct( $main, $action );

		$this->blockPermissionCheckerFactory = $blockPermissionCheckerFactory;
		$this->blockUserFactory = $blockUserFactory;
		$this->titleFactory = $titleFactory;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->watchedItemStore = $watchedItemStore;
		$this->blockUtils = $blockUtils;
		$this->blockActionInfo = $blockActionInfo;

		// Variables needed in ApiWatchlistTrait trait
		$this->watchlistExpiryEnabled = $this->getConfig()->get( MainConfigNames::WatchlistExpiry );
		$this->watchlistMaxDuration =
			$this->getConfig()->get( MainConfigNames::WatchlistExpiryMaxDuration );
		$this->watchlistManager = $watchlistManager;
		$this->userOptionsLookup = $userOptionsLookup;
	}

	/**
	 * Blocks the user specified in the parameters for the given expiry, with the
	 * given reason, and with all other settings provided in the params. If the block
	 * succeeds, produces a result containing the details of the block and notice
	 * of success. If it fails, the result will specify the nature of the error.
	 */
	public function execute() {
		$this->checkUserRightsAny( 'block' );
		$params = $this->extractRequestParams();
		$this->requireOnlyOneParameter( $params, 'user', 'userid' );

		// Make sure $target contains a parsed target
		if ( $params['user'] !== null ) {
			$target = $params['user'];
		} else {
			$target = $this->userIdentityLookup->getUserIdentityByUserId( $params['userid'] );
			if ( !$target ) {
				$this->dieWithError( [ 'apierror-nosuchuserid', $params['userid'] ], 'nosuchuserid' );
			}
		}
		[ $target, $targetType ] = $this->blockUtils->parseBlockTarget( $target );

		if (
			$params['noemail'] &&
			!$this->blockPermissionCheckerFactory
				->newBlockPermissionChecker(
					$target,
					$this->getAuthority()
				)
				->checkEmailPermissions()
		) {
			$this->dieWithError( 'apierror-cantblock-email' );
		}

		$restrictions = [];
		if ( $params['partial'] ) {
			$pageRestrictions = array_map(
				[ PageRestriction::class, 'newFromTitle' ],
				(array)$params['pagerestrictions']
			);

			$namespaceRestrictions = array_map( static function ( $id ) {
				return new NamespaceRestriction( 0, $id );
			}, (array)$params['namespacerestrictions'] );
			$restrictions = array_merge( $pageRestrictions, $namespaceRestrictions );

			if ( $this->getConfig()->get( MainConfigNames::EnablePartialActionBlocks ) ) {
				$actionRestrictions = array_map( function ( $action ) {
					return new ActionRestriction( 0, $this->blockActionInfo->getIdFromAction( $action ) );
				}, (array)$params['actionrestrictions'] );
				$restrictions = array_merge( $restrictions, $actionRestrictions );
			}
		}

		$status = $this->blockUserFactory->newBlockUser(
			$target,
			$this->getAuthority(),
			$params['expiry'],
			$params['reason'],
			[
				'isCreateAccountBlocked' => $params['nocreate'],
				'isEmailBlocked' => $params['noemail'],
				'isHardBlock' => !$params['anononly'],
				'isAutoblocking' => $params['autoblock'],
				'isUserTalkEditBlocked' => !$params['allowusertalk'],
				'isHideUser' => $params['hidename'],
				'isPartial' => $params['partial'],
			],
			$restrictions,
			$params['tags']
		)->placeBlock( $params['reblock'] );

		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$block = $status->value;

		$watchlistExpiry = $this->getExpiryFromParams( $params );
		$userPage = Title::makeTitle( NS_USER, $block->getTargetName() );

		if ( $params['watchuser'] && $targetType !== AbstractBlock::TYPE_RANGE ) {
			$this->setWatch( 'watch', $userPage, $this->getUser(), null, $watchlistExpiry );
		}

		$res = [];

		$res['user'] = $block->getTargetName();
		$res['userID'] = $target instanceof UserIdentity ? $target->getId() : 0;

		if ( $block instanceof DatabaseBlock ) {
			$res['expiry'] = ApiResult::formatExpiry( $block->getExpiry(), 'infinite' );
			$res['id'] = $block->getId();
		} else {
			# should be unreachable
			$res['expiry'] = ''; // @codeCoverageIgnore
			$res['id'] = ''; // @codeCoverageIgnore
		}

		$res['reason'] = $params['reason'];
		$res['anononly'] = $params['anononly'];
		$res['nocreate'] = $params['nocreate'];
		$res['autoblock'] = $params['autoblock'];
		$res['noemail'] = $params['noemail'];
		$res['hidename'] = $params['hidename'];
		$res['allowusertalk'] = $params['allowusertalk'];
		$res['watchuser'] = $params['watchuser'];
		if ( $watchlistExpiry ) {
			$expiry = $this->getWatchlistExpiry(
				$this->watchedItemStore,
				$userPage,
				$this->getUser()
			);
			$res['watchlistexpiry'] = $expiry;
		}
		$res['partial'] = $params['partial'];
		$res['pagerestrictions'] = $params['pagerestrictions'];
		$res['namespacerestrictions'] = $params['namespacerestrictions'];
		if ( $this->getConfig()->get( MainConfigNames::EnablePartialActionBlocks ) ) {
			$res['actionrestrictions'] = $params['actionrestrictions'];
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		$params = [
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'cidr', 'id' ],
			],
			'userid' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'expiry' => 'never',
			'reason' => '',
			'anononly' => false,
			'nocreate' => false,
			'autoblock' => false,
			'noemail' => false,
			'hidename' => false,
			'allowusertalk' => false,
			'reblock' => false,
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

		$params += [
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
			'partial' => false,
			'pagerestrictions' => [
				ParamValidator::PARAM_TYPE => 'title',
				TitleDef::PARAM_MUST_EXIST => true,

				// TODO: TitleDef returns instances of TitleValue when PARAM_RETURN_OBJECT is
				// truthy. At the time of writing,
				// MediaWiki\Block\Restriction\PageRestriction::newFromTitle accepts either
				// string or instance of Title.
				//TitleDef::PARAM_RETURN_OBJECT => true,

				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_ISMULTI_LIMIT1 => 10,
				ParamValidator::PARAM_ISMULTI_LIMIT2 => 10,
			],
			'namespacerestrictions' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace',
			],
		];

		if ( $this->getConfig()->get( MainConfigNames::EnablePartialActionBlocks ) ) {
			$params += [
				'actionrestrictions' => [
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_TYPE => array_keys(
						$this->blockActionInfo->getAllBlockActions()
					),
				],
			];
		}

		return $params;
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		// phpcs:disable Generic.Files.LineLength
		return [
			'action=block&user=192.0.2.5&expiry=3%20days&reason=First%20strike&token=123ABC'
				=> 'apihelp-block-example-ip-simple',
			'action=block&user=Vandal&expiry=never&reason=Vandalism&nocreate=&autoblock=&noemail=&token=123ABC'
				=> 'apihelp-block-example-user-complex',
		];
		// phpcs:enable
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Block';
	}
}
