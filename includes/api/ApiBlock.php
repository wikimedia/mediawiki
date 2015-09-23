<?php
/**
 *
 *
 * Created on Sep 4, 2007
 *
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
 * API module that facilitates the blocking of users. Requires API write mode
 * to be enabled.
 *
 * @ingroup API
 */
class ApiBlock extends ApiBase {

	/**
	 * Blocks the user specified in the parameters for the given expiry, with the
	 * given reason, and with all other settings provided in the params. If the block
	 * succeeds, produces a result containing the details of the block and notice
	 * of success. If it fails, the result will specify the nature of the error.
	 */
	public function execute() {
		global $wgContLang;

		$user = $this->getUser();
		$params = $this->extractRequestParams();

		if ( !$user->isAllowed( 'block' ) ) {
			$this->dieUsageMsg( 'cantblock' );
		}

		# bug 15810: blocked admins should have limited access here
		if ( $user->isBlocked() ) {
			$status = SpecialBlock::checkUnblockSelf( $params['user'], $user );
			if ( $status !== true ) {
				$msg = $this->parseMsg( $status );
				$this->dieUsage(
					$msg['info'],
					$msg['code'],
					0,
					array( 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $user->getBlock() ) )
				);
			}
		}

		$target = User::newFromName( $params['user'] );
		// Bug 38633 - if the target is a user (not an IP address), but it
		// doesn't exist or is unusable, error.
		if ( $target instanceof User &&
			( $target->isAnon() /* doesn't exist */ || !User::isUsableName( $target->getName() ) )
		) {
			$this->dieUsageMsg( array( 'nosuchuser', $params['user'] ) );
		}

		if ( $params['hidename'] && !$user->isAllowed( 'hideuser' ) ) {
			$this->dieUsageMsg( 'canthide' );
		}
		if ( $params['noemail'] && !SpecialBlock::canBlockEmail( $user ) ) {
			$this->dieUsageMsg( 'cantblock-email' );
		}

		$data = array(
			'PreviousTarget' => $params['user'],
			'Target' => $params['user'],
			'Reason' => array(
				$params['reason'],
				'other',
				$params['reason']
			),
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
		);

		$retval = SpecialBlock::processForm( $data, $this->getContext() );
		if ( $retval !== true ) {
			// We don't care about multiple errors, just report one of them
			$this->dieUsageMsg( $retval );
		}

		list( $target, /*...*/ ) = SpecialBlock::getTargetAndType( $params['user'] );
		$res['user'] = $params['user'];
		$res['userID'] = $target instanceof User ? $target->getId() : 0;

		$block = Block::newFromTarget( $target, null, true );
		if ( $block instanceof Block ) {
			$res['expiry'] = $wgContLang->formatExpiry( $block->mExpiry, TS_ISO_8601, 'infinite' );
			$res['id'] = $block->getId();
		} else {
			# should be unreachable
			$res['expiry'] = '';
			$res['id'] = '';
		}

		$res['reason'] = $params['reason'];
		$res['anononly'] = $params['anononly'];
		$res['nocreate'] = $params['nocreate'];
		$res['autoblock'] = $params['autoblock'];
		$res['noemail'] = $params['noemail'];
		$res['hidename'] = $params['hidename'];
		$res['allowusertalk'] = $params['allowusertalk'];
		$res['watchuser'] = $params['watchuser'];

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'user' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
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
		);
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return array(
			'action=block&user=192.0.2.5&expiry=3%20days&reason=First%20strike&token=123ABC'
				=> 'apihelp-block-example-ip-simple',
			'action=block&user=Vandal&expiry=never&reason=Vandalism&nocreate=&autoblock=&noemail=&token=123ABC'
				=> 'apihelp-block-example-user-complex',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Block';
	}
}
