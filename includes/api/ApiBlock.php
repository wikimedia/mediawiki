<?php
/**
 * Copyright © 2007 Roan Kattouw <roan.kattouw@gmail.com>
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

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockPermissionCheckerFactory;
use MediaWiki\Block\BlockTarget;
use MediaWiki\Block\BlockTargetFactory;
use MediaWiki\Block\BlockUser;
use MediaWiki\Block\BlockUserFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\Restriction;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\TitleDef;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use RuntimeException;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;

/**
 * API module that facilitates the blocking of users. Requires API write mode
 * to be enabled.
 *
 * @ingroup API
 */
class ApiBlock extends ApiBase {

	use ApiWatchlistTrait;

	private BlockPermissionCheckerFactory $blockPermissionCheckerFactory;
	private BlockUserFactory $blockUserFactory;
	private TitleFactory $titleFactory;
	private UserIdentityLookup $userIdentityLookup;
	private WatchedItemStoreInterface $watchedItemStore;
	private BlockActionInfo $blockActionInfo;
	private DatabaseBlockStore $blockStore;
	private BlockTargetFactory $blockTargetFactory;

	public function __construct(
		ApiMain $main,
		string $action,
		BlockPermissionCheckerFactory $blockPermissionCheckerFactory,
		BlockUserFactory $blockUserFactory,
		TitleFactory $titleFactory,
		UserIdentityLookup $userIdentityLookup,
		WatchedItemStoreInterface $watchedItemStore,
		BlockTargetFactory $blockTargetFactory,
		BlockActionInfo $blockActionInfo,
		DatabaseBlockStore $blockStore,
		WatchlistManager $watchlistManager,
		UserOptionsLookup $userOptionsLookup
	) {
		parent::__construct( $main, $action );

		$this->blockPermissionCheckerFactory = $blockPermissionCheckerFactory;
		$this->blockUserFactory = $blockUserFactory;
		$this->titleFactory = $titleFactory;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->watchedItemStore = $watchedItemStore;
		$this->blockTargetFactory = $blockTargetFactory;
		$this->blockActionInfo = $blockActionInfo;
		$this->blockStore = $blockStore;

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
		$this->requireOnlyOneParameter( $params, 'id', 'user', 'userid' );
		$this->requireNoConflictingParameters( $params,
			'id', [ 'newblock', 'reblock' ] );

		if ( $params['id'] !== null ) {
			$block = $this->blockStore->newFromID( $params['id'], true );
			if ( !$block ) {
				$this->dieWithError(
					[ 'apierror-nosuchblockid', $params['id'] ],
					'nosuchblockid' );
			}
			if ( $block->getType() === AbstractBlock::TYPE_AUTO ) {
				$this->dieWithError( 'apierror-modify-autoblock' );
			}
			$status = $this->updateBlock( $block, $params );
		} else {
			if ( $params['user'] !== null ) {
				$target = $this->blockTargetFactory->newFromUser( $params['user'] );
			} else {
				$targetUser = $this->userIdentityLookup->getUserIdentityByUserId( $params['userid'] );
				if ( !$targetUser ) {
					$this->dieWithError( [ 'apierror-nosuchuserid', $params['userid'] ], 'nosuchuserid' );
				}
				$target = $this->blockTargetFactory->newUserBlockTarget( $targetUser );
			}
			if ( $params['newblock'] ) {
				$status = $this->insertBlock( $target, $params );
			} else {
				$blocks = $this->blockStore->newListFromTarget(
					$target, null, false, DatabaseBlockStore::AUTO_NONE );
				if ( count( $blocks ) === 0 ) {
					$status = $this->insertBlock( $target, $params );
				} elseif ( count( $blocks ) === 1 ) {
					if ( $params['reblock'] ) {
						$status = $this->updateBlock( $blocks[0], $params );
					} else {
						$status = Status::newFatal( 'ipb_already_blocked', $blocks[0]->getTargetName() );
					}
				} else {
					$this->dieWithError( 'apierror-ambiguous-block', 'ambiguous-block' );
				}
			}
		}

		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$block = $status->value;
		if ( !( $block instanceof DatabaseBlock ) ) {
			throw new RuntimeException( "Unexpected block class" );
		}

		$userPage = Title::makeTitle( NS_USER, $block->getTargetName() );
		$watchlistExpiry = $this->getExpiryFromParams( $params, $userPage, $this->getUser() );

		if ( $params['watchuser'] && $block->getType() !== AbstractBlock::TYPE_RANGE ) {
			$this->setWatch( 'watch', $userPage, $this->getUser(), null, $watchlistExpiry );
		}

		$res = [];

		$res['user'] = $block->getTargetName();

		$blockedUser = $block->getTargetUserIdentity();
		$res['userID'] = $blockedUser ? $blockedUser->getId() : 0;

		$res['expiry'] = ApiResult::formatExpiry( $block->getExpiry(), 'infinite' );
		$res['id'] = $block->getId();

		$res['reason'] = $params['reason'];
		$res['anononly'] = $params['anononly'];
		$res['nocreate'] = $params['nocreate'];
		$res['autoblock'] = $params['autoblock'];
		$res['noemail'] = $params['noemail'];
		$res['hidename'] = $block->getHideName();
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
		$res['actionrestrictions'] = $params['actionrestrictions'];

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	/**
	 * Get the block options to be used for an insert or update
	 *
	 * @param array $params
	 * @return array
	 */
	private function getBlockOptions( $params ) {
		return [
			'isCreateAccountBlocked' => $params['nocreate'],
			'isEmailBlocked' => $params['noemail'],
			'isHardBlock' => !$params['anononly'],
			'isAutoblocking' => $params['autoblock'],
			'isUserTalkEditBlocked' => !$params['allowusertalk'],
			'isHideUser' => $params['hidename'],
			'isPartial' => $params['partial'],
		];
	}

	/**
	 * Get the new block restrictions
	 * @param array $params
	 * @return Restriction[]
	 */
	private function getRestrictions( $params ) {
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

			$actionRestrictions = array_map( function ( $action ) {
				return new ActionRestriction( 0, $this->blockActionInfo->getIdFromAction( $action ) );
			}, (array)$params['actionrestrictions'] );
			$restrictions = array_merge( $restrictions, $actionRestrictions );
		}
		return $restrictions;
	}

	/**
	 * Exit with an error if the user wants to block user-to-user email but is not allowed.
	 *
	 * @param array $params
	 */
	private function checkEmailPermissions( $params ) {
		if (
			$params['noemail'] &&
			!$this->blockPermissionCheckerFactory
				->newChecker( $this->getAuthority() )
				->checkEmailPermissions()
		) {
			$this->dieWithError( 'apierror-cantblock-email' );
		}
	}

	/**
	 * Update a block
	 *
	 * @param DatabaseBlock $block
	 * @param array $params
	 * @return Status
	 */
	private function updateBlock( DatabaseBlock $block, $params ) {
		$this->checkEmailPermissions( $params );
		return $this->blockUserFactory->newUpdateBlock(
			$block,
			$this->getAuthority(),
			$params['expiry'],
			$params['reason'],
			$this->getBlockOptions( $params ),
			$this->getRestrictions( $params ),
			$params['tags']
		)->placeBlock();
	}

	/**
	 * Insert a block
	 *
	 * @param BlockTarget $target
	 * @param array $params
	 * @return Status
	 */
	private function insertBlock( $target, $params ) {
		$this->checkEmailPermissions( $params );
		return $this->blockUserFactory->newBlockUser(
			$target,
			$this->getAuthority(),
			$params['expiry'],
			$params['reason'],
			$this->getBlockOptions( $params ),
			$this->getRestrictions( $params ),
			$params['tags']
		)->placeBlock( $params['newblock'] ? BlockUser::CONFLICT_NEW : BlockUser::CONFLICT_FAIL );
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
	public function getAllowedParams() {
		$params = [
			'id' => [ ParamValidator::PARAM_TYPE => 'integer' ],
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'cidr', 'id' ],
				UserDef::PARAM_RETURN_OBJECT => true,
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
			'newblock' => false,
			'watchuser' => false,
		];

		// Params appear in the docs in the order they are defined,
		// which is why this is here and not at the bottom.
		if ( $this->watchlistExpiryEnabled ) {
			$params += [
				'watchlistexpiry' => [
					ParamValidator::PARAM_TYPE => 'expiry',
					ExpiryDef::PARAM_MAX => $this->watchlistMaxDuration,
					ExpiryDef::PARAM_USE_MAX => true,
				]
			];
		}

		$pageLimit = $this->getConfig()->get( MainConfigNames::EnableMultiBlocks ) ? 50 : 10;

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
				ParamValidator::PARAM_ISMULTI_LIMIT1 => $pageLimit,
				ParamValidator::PARAM_ISMULTI_LIMIT2 => $pageLimit,
			],
			'namespacerestrictions' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace',
			],
			'actionrestrictions' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => array_keys(
					$this->blockActionInfo->getAllBlockActions()
				),
			],
		];

		return $params;
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Block';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiBlock::class, 'ApiBlock' );
