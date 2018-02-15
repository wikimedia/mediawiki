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

/**
 * API module that facilitates the unblocking of users. Requires API write mode
 * to be enabled.
 *
 * @ingroup API
 */
class ApiUnblock extends ApiBase {

	/**
	 * Unblocks the specified user or provides the reason the unblock failed.
	 */
	public function execute() {
		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$this->requireOnlyOneParameter( $params, 'id', 'user', 'userid' );

		if ( !$user->isAllowed( 'block' ) ) {
			$this->dieWithError( 'apierror-permissiondenied-unblock', 'permissiondenied' );
		}
		# T17810: blocked admins should have limited access here
		if ( $user->isBlocked() ) {
			$status = SpecialBlock::checkUnblockSelf( $params['user'], $user );
			if ( $status !== true ) {
				$this->dieWithError(
					$status,
					null,
					[ 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $user->getBlock() ) ]
				);
			}
		}

		// Check if user can add tags
		if ( !is_null( $params['tags'] ) ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $user );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		if ( $params['userid'] !== null ) {
			$username = User::whoIs( $params['userid'] );

			if ( $username === false ) {
				$this->dieWithError( [ 'apierror-nosuchuserid', $params['userid'] ], 'nosuchuserid' );
			} else {
				$params['user'] = $username;
			}
		}

		$data = [
			'Target' => is_null( $params['id'] ) ? $params['user'] : "#{$params['id']}",
			'Reason' => $params['reason'],
			'Tags' => $params['tags']
		];
		$block = Block::newFromTarget( $data['Target'] );
		$retval = SpecialUnblock::processUnblock( $data, $this->getContext() );
		if ( $retval !== true ) {
			$this->dieStatus( $this->errorArrayToStatus( $retval ) );
		}

		$res['id'] = $block->getId();
		$target = $block->getType() == Block::TYPE_AUTO ? '' : $block->getTarget();
		$res['user'] = $target instanceof User ? $target->getName() : $target;
		$res['userid'] = $target instanceof User ? $target->getId() : 0;
		$res['reason'] = $params['reason'];
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
			'id' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
			'user' => null,
			'userid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'reason' => '',
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=unblock&id=105'
				=> 'apihelp-unblock-example-id',
			'action=unblock&user=Bob&reason=Sorry%20Bob'
				=> 'apihelp-unblock-example-user',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Block';
	}
}
