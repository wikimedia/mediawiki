<?php
/**
 *
 *
 * Created on Sep 4, 2007
 *
 * Copyright © 2007 Roan Kattouw <Firstname>.<Lastname>@gmail.com
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiBase.php" );
}

/**
* API module that facilitates the blocking of users. Requires API write mode
* to be enabled.
*
 * @ingroup API
 */
class ApiBlock extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	/**
	 * Blocks the user specified in the parameters for the given expiry, with the
	 * given reason, and with all other settings provided in the params. If the block
	 * succeeds, produces a result containing the details of the block and notice
	 * of success. If it fails, the result will specify the nature of the error.
	 */
	public function execute() {
		global $wgUser, $wgBlockAllowsUTEdit;
		$params = $this->extractRequestParams();

		if ( $params['gettoken'] ) {
			$res['blocktoken'] = $wgUser->editToken( '', $this->getMain()->getRequest() );
			$this->getResult()->addValue( null, $this->getModuleName(), $res );
			return;
		}

		if ( !$wgUser->isAllowed( 'block' ) ) {
			$this->dieUsageMsg( array( 'cantblock' ) );
		}
		# bug 15810: blocked admins should have limited access here
		if ( $wgUser->isBlocked() ) {
			$status = SpecialBlock::checkUnblockSelf( $params['user'] );
			if ( $status !== true ) {
				$this->dieUsageMsg( array( $status ) );
			}
		}
		if ( $params['hidename'] && !$wgUser->isAllowed( 'hideuser' ) ) {
			$this->dieUsageMsg( array( 'canthide' ) );
		}
		if ( $params['noemail'] && !SpecialBlock::canBlockEmail( $wgUser ) ) {
			$this->dieUsageMsg( array( 'cantblock-email' ) );
		}

		$data = array(
			'Target' => $params['user'],
			'Reason' => array(
				is_null( $params['reason'] ) ? '' : $params['reason'],
				'other',
				is_null( $params['reason'] ) ? '' : $params['reason']
			),
			'Expiry' => $params['expiry'] == 'never' ? 'infinite' : $params['expiry'],
			'HardBlock' => !$params['anononly'],
			'CreateAccount' => $params['nocreate'],
			'AutoBlock' => $params['autoblock'],
			'DisableEmail' => $params['noemail'],
			'HideUser' => $params['hidename'],
			'DisableUTEdit' => $params['allowusertalk'],
			'AlreadyBlocked' => $params['reblock'],
			'Watch' => $params['watchuser'],
		);

		$retval = SpecialBlock::processForm( $data );
		if ( $retval !== true ) {
			// We don't care about multiple errors, just report one of them
			$this->dieUsageMsg( $retval );
		}

		list( $target, /*...*/ ) = SpecialBlock::getTargetAndType( $params['user'] );
		$res['user'] = $params['user'];
		$res['userID'] = $target instanceof User ? $target->getId() : 0;

		$block = Block::newFromTarget( $target );
		if( $block instanceof Block ){
			$res['expiry'] = $block->mExpiry == wfGetDB( DB_SLAVE )->getInfinity()
				? 'infinite'
				: wfTimestamp( TS_ISO_8601, $block->mExpiry );
		} else {
			# should be unreachable
			$res['expiry'] = '';
		}

		$res['reason'] = $params['reason'];
		if ( $params['anononly'] ) {
			$res['anononly'] = '';
		}
		if ( $params['nocreate'] ) {
			$res['nocreate'] = '';
		}
		if ( $params['autoblock'] ) {
			$res['autoblock'] = '';
		}
		if ( $params['noemail'] ) {
			$res['noemail'] = '';
		}
		if ( $params['hidename'] ) {
			$res['hidename'] = '';
		}
		if ( $params['allowusertalk'] ) {
			$res['allowusertalk'] = '';
		}
		if ( $params['watchuser'] ) {
			$res['watchuser'] = '';
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
		return array(
			'user' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'token' => null,
			'gettoken' => false,
			'expiry' => 'never',
			'reason' => null,
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

	public function getParamDescription() {
		return array(
			'user' => 'Username, IP address or IP range you want to block',
			'token' => 'A block token previously obtained through the gettoken parameter or prop=info',
			'gettoken' => 'If set, a block token will be returned, and no other action will be taken',
			'expiry' => 'Relative expiry time, e.g. \'5 months\' or \'2 weeks\'. If set to \'infinite\', \'indefinite\' or \'never\', the block will never expire.',
			'reason' => 'Reason for block (optional)',
			'anononly' => 'Block anonymous users only (i.e. disable anonymous edits for this IP)',
			'nocreate' => 'Prevent account creation',
			'autoblock' => 'Automatically block the last used IP address, and any subsequent IP addresses they try to login from',
			'noemail' => 'Prevent user from sending e-mail through the wiki. (Requires the "blockemail" right.)',
			'hidename' => 'Hide the username from the block log. (Requires the "hideuser" right.)',
			'allowusertalk' => 'Allow the user to edit their own talk page (depends on $wgBlockAllowsUTEdit)',
			'reblock' => 'If the user is already blocked, overwrite the existing block',
			'watchuser' => 'Watch the user/IP\'s user and talk pages',
		);
	}

	public function getDescription() {
		return 'Block a user';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'cantblock' ),
			array( 'canthide' ),
			array( 'cantblock-email' ),
			array( 'ipbblocked' ),
			array( 'ipbnounblockself' ),
		) );
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	protected function getExamples() {
		return array(
			'api.php?action=block&user=123.5.5.12&expiry=3%20days&reason=First%20strike',
			'api.php?action=block&user=Vandal&expiry=never&reason=Vandalism&nocreate=&autoblock=&noemail='
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
