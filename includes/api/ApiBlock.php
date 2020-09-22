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
use MediaWiki\Block\BlockPermissionCheckerFactory;
use MediaWiki\Block\BlockUserFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;

/**
 * API module that facilitates the blocking of users. Requires API write mode
 * to be enabled.
 *
 * @ingroup API
 */
class ApiBlock extends ApiBase {

	use ApiBlockInfoTrait;

	/** @var BlockPermissionCheckerFactory */
	private $blockPermissionCheckerFactory;

	/** @var BlockUserFactory */
	private $blockUserFactory;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var UserFactory */
	private $userFactory;

	/**
	 * @param ApiMain $main
	 * @param string $action
	 * @param BlockPermissionCheckerFactory $blockPermissionCheckerFactory
	 * @param BlockUserFactory $blockUserFactory
	 * @param TitleFactory $titleFactory
	 * @param UserFactory $userFactory
	 */
	public function __construct(
		ApiMain $main,
		$action,
		BlockPermissionCheckerFactory $blockPermissionCheckerFactory,
		BlockUserFactory $blockUserFactory,
		TitleFactory $titleFactory,
		UserFactory $userFactory
	) {
		parent::__construct( $main, $action );

		$this->blockPermissionCheckerFactory = $blockPermissionCheckerFactory;
		$this->blockUserFactory = $blockUserFactory;
		$this->titleFactory = $titleFactory;
		$this->userFactory = $userFactory;
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
			if ( User::whoIs( $params['userid'] ) === false ) {
				$this->dieWithError( [ 'apierror-nosuchuserid', $params['userid'] ], 'nosuchuserid' );
			}

			$target = $this->userFactory->newFromId( $params['userid'] );
		}
		list( $target, $targetType ) = AbstractBlock::parseTarget( $target );

		if (
			$params['noemail'] &&
			!$this->blockPermissionCheckerFactory
				->newBlockPermissionChecker(
					$target,
					$this->getUser()
				)
				->checkEmailPermissions()
		) {
			$this->dieWithError( 'apierror-cantblock-email' );
		}

		$editingRestriction = $params['partial'] ? 'partial' : 'sitewide';
		$pageRestrictions = array_map( function ( $text ) {
			$title = $this->titleFactory->newFromText( $text );
			$restriction = new PageRestriction( 0, $title->getArticleID() );
			$restriction->setTitle( $title );
			return $restriction;
		}, (array)$params['pagerestrictions'] );
		$namespaceRestrictions = array_map( function ( $id ) {
			return new NamespaceRestriction( 0, $id );
		}, (array)$params['namespacerestrictions'] );
		$restrictions = array_merge( $pageRestrictions, $namespaceRestrictions );
		if ( $restrictions === null ) {
			$restrictions = [];
		}

		$status = $this->blockUserFactory->newBlockUser(
			$target,
			$this->getUser(),
			$params['expiry'],
			$params['reason'],
			[
				'isCreateAccountBlocked' => $params['nocreate'],
				'isEmailBlocked' => $params['noemail'],
				'isHardBlock' => !$params['anononly'],
				'isAutoblocking' => $params['autoblock'],
				'isUserTalkEditBlocked' => !$params['allowusertalk'],
				'isHideUser' => $params['hidename']
			],
			$restrictions,
			$params['tags']
		)->placeBlock( $params['reblock'] );

		if ( $params['watchuser'] && $targetType !== AbstractBlock::TYPE_RANGE ) {
			WatchAction::doWatch(
				Title::makeTitle( NS_USER, $target ),
				$this->getUser(),
				User::IGNORE_USER_RIGHTS
			);
		}

		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$res = [];

		if ( $target instanceof UserIdentity ) {
			$res['user'] = $target->getName();
		} else {
			$res['user'] = $target;
		}
		$res['userID'] = $target instanceof User ? $target->getId() : 0;

		$block = DatabaseBlock::newFromTarget( $target, null, true );
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
		$res['partial'] = $params['partial'];
		$res['pagerestrictions'] = $params['pagerestrictions'];
		$res['namespacerestrictions'] = $params['namespacerestrictions'];

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'cidr', 'id' ],
			],
			'userid' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DEPRECATED => true,
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
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
			'partial' => false,
			'pagerestrictions' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_ISMULTI_LIMIT1 => 10,
				ApiBase::PARAM_ISMULTI_LIMIT2 => 10,
			],
			'namespacerestrictions' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace',
			],
		];
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
