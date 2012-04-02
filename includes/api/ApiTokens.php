<?php
/**
 *
 *
 * Created on Jul 29, 2011
 *
 * Copyright © 2011 John Du Hart john@johnduhart.me
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
 * @ingroup API
 */
class ApiTokens extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$res = array();

		foreach ( $params['type'] as $type ) {
			$type = strtolower( $type );
			$func = 'get' .
					ucfirst( $type ) .
					'Token';
			if ( $type === 'patrol' ) {
				$val = call_user_func( array( 'ApiQueryRecentChanges', $func ), null, null );
			} else {
				$val = call_user_func( array( 'ApiQueryInfo', $func ), null, null );
			}
			if ( $val === false ) {
				$this->setWarning( "Action '$type' is not allowed for the current user" );
			} else {
				$res[$type . 'token'] = $val;
			}
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function getAllowedParams() {
		return array(
			'type' => array(
				ApiBase::PARAM_DFLT => 'edit',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'edit', 'delete', 'protect', 'move', 'block', 'unblock',
					'email', 'import', 'watch', 'patrol'
				)
			)
		);
	}

	public function getParamDescription() {
		return array(
			'type' => 'Type of token(s) to request'
		);
	}

	public function getDescription() {
		return 'Gets tokens for data-modifying actions';
	}

	protected function getExamples() {
		return array(
			'api.php?action=tokens' => 'Retrieve an edit token (the default)',
			'api.php?action=tokens&type=email|move' => 'Retrieve an email token and a move token'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
