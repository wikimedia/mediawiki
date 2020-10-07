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

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\ParamValidator\TypeDef\UserDef;

/**
 * API module that facilitates the blocking of users. Requires API write mode
 * to be enabled.
 *
 * @ingroup API
 */
class ApiBlock extends ApiBase {

	use ApiBlockInfoTrait;

	/**
	 * Blocks the user specified in the parameters for the given expiry, with the
	 * given reason, and with all other settings provided in the params. If the block
	 * succeeds, produces a result containing the details of the block and notice
	 * of success. If it fails, the result will specify the nature of the error.
	 */
	public function execute() {
		$this->checkUserRightsAny( 'block' );

		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$this->requireOnlyOneParameter( $params, 'user', 'userid' );

		# T17810: blocked admins should have limited access here
		$block = $user->getBlock();
		if ( $block ) {
			$status = SpecialBlock::checkUnblockSelf( $params['user'], $user );
			if ( $status !== true ) {
				$this->dieWithError(
					$status,
					null,
					[ 'blockinfo' => $this->getBlockDetails( $block ) ]
				);
			}
		}

		$editingRestriction = $params['partial'] ? 'partial' : 'sitewide';
		$pageRestrictions = implode( "\n", (array)$params['pagerestrictions'] );
		$namespaceRestrictions = implode( "\n", (array)$params['namespacerestrictions'] );

		if ( $params['userid'] !== null ) {
			$username = User::whoIs( $params['userid'] );

			if ( $username === false ) {
				$this->dieWithError( [ 'apierror-nosuchuserid', $params['userid'] ], 'nosuchuserid' );
			} else {
				$params['user'] = $username;
			}
		} else {
			list( $target, $type ) = SpecialBlock::getTargetAndType( $params['user'] );

			// T40633 - if the target is a user (not an IP address), but it
			// doesn't exist or is unusable, error.
			if ( $type === DatabaseBlock::TYPE_USER &&
				( $target->isAnon() /* doesn't exist */ || !User::isUsableName( $params['user'] ) )
			) {
				$this->dieWithError( [ 'nosuchusershort', $params['user'] ], 'nosuchuser' );
			}
		}

		if ( $params['tags'] ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $user );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		if ( $params['hidename'] &&
			 !$this->getPermissionManager()->userHasRight( $user, 'hideuser' ) ) {
			$this->dieWithError( 'apierror-canthide' );
		}
		if ( $params['noemail'] && !SpecialBlock::canBlockEmail( $user ) ) {
			$this->dieWithError( 'apierror-cantblock-email' );
		}

		$data = [
			'PreviousTarget' => $params['user'],
			'Target' => $params['user'],
			'Reason' => [
				$params['reason'],
				'other',
				$params['reason']
			],
			'Expiry' => $params['expiry'],
			'HardBlock' => !$params['anononly'],
			'CreateAccount' => $params['nocreate'],
			'AutoBlock' => $params['autoblock'],
			'DisableEmail' => $params['noemail'],
			'HideUser' => $params['hidename'],
			'DisableUTEdit' => !$params['allowusertalk'],
			'Reblock' => $params['reblock'],
			'Watch' => $params['watchuser'],
			'Confirm' => true,
			'Tags' => $params['tags'],
			'EditingRestriction' => $editingRestriction,
			'PageRestrictions' => $pageRestrictions,
			'NamespaceRestrictions' => $namespaceRestrictions,
		];

		$status = SpecialBlock::validateTarget( $params['user'], $user );
		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$retval = SpecialBlock::processForm( $data, $this->getContext() );
		if ( $retval !== true ) {
			$this->dieStatus( $this->errorArrayToStatus( $retval ) );
		}

		$res = [];

		$res['user'] = $params['user'];
		list( $target, /*...*/ ) = SpecialBlock::getTargetAndType( $params['user'] );
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
