<?php
/**
 *
 *
 * Created on Sep 7, 2007
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
 * API module that facilitates the unblocking of users. Requires API write mode
 * to be enabled.
 *
 * @ingroup API
 */
class ApiUnblock extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	/**
	 * Unblocks the specified user or provides the reason the unblock failed.
	 */
	public function execute() {
		global $wgUser;
		$params = $this->extractRequestParams();

		if ( $params['gettoken'] ) {
			$res['unblocktoken'] = $wgUser->editToken( '', $this->getMain()->getRequest() );
			$this->getResult()->addValue( null, $this->getModuleName(), $res );
			return;
		}

		if ( is_null( $params['id'] ) && is_null( $params['user'] ) ) {
			$this->dieUsageMsg( 'unblock-notarget' );
		}
		if ( !is_null( $params['id'] ) && !is_null( $params['user'] ) ) {
			$this->dieUsageMsg( 'unblock-idanduser' );
		}

		if ( !$wgUser->isAllowed( 'block' ) ) {
			$this->dieUsageMsg( 'cantunblock' );
		}
		# bug 15810: blocked admins should have limited access here
		if ( $wgUser->isBlocked() ) {
			$status = SpecialBlock::checkUnblockSelf( $params['user'] );
			if ( $status !== true ) {
				$this->dieUsageMsg( $status );
			}
		}

		$data = array(
			'Target' => is_null( $params['id'] ) ? $params['user'] : "#{$params['id']}",
			'Reason' => is_null( $params['reason'] ) ? '' : $params['reason']
		);
		$block = Block::newFromTarget( $data['Target'] );
		$retval = SpecialUnblock::processUnblock( $data );
		if ( $retval !== true ) {
			$this->dieUsageMsg( $retval[0] );
		}

		$res['id'] = $block->getId();
		$res['user'] = $block->getType() == Block::TYPE_AUTO ? '' : $block->getTarget();
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
		return array(
			'id' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'user' => null,
			'token' => null,
			'gettoken' => false,
			'reason' => null,
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'id' => "ID of the block you want to unblock (obtained through list=blocks). Cannot be used together with {$p}user",
			'user' => "Username, IP address or IP range you want to unblock. Cannot be used together with {$p}id",
			'token' => "An unblock token previously obtained through the gettoken parameter or {$p}prop=info",
			'gettoken' => 'If set, an unblock token will be returned, and no other action will be taken',
			'reason' => 'Reason for unblock (optional)',
		);
	}

	public function getDescription() {
		return 'Unblock a user';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'unblock-notarget' ),
			array( 'unblock-idanduser' ),
			array( 'cantunblock' ),
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
			'api.php?action=unblock&id=105',
			'api.php?action=unblock&user=Bob&reason=Sorry%20Bob'
		);
	}

	public function getHelpUrls() {
		return 'http://www.mediawiki.org/wiki/API:Block';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
