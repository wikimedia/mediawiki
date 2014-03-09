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

	public function execute() {
		$params = $this->extractRequestParams();
		$res = array();

		$types = $this->getTokenTypes();
		foreach ( $params['type'] as $type ) {
			$val = call_user_func( $types[$type], null, null );

			if ( $val === false ) {
				$this->setWarning( "Action '$type' is not allowed for the current user" );
			} else {
				$res[$type . 'token'] = $val;
			}
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	private function getTokenTypes() {
		// If we're in JSON callback mode, no tokens can be obtained
		if ( !is_null( $this->getMain()->getRequest()->getVal( 'callback' ) ) ) {
			return array();
		}

		static $types = null;
		if ( $types ) {
			return $types;
		}
		wfProfileIn( __METHOD__ );
		$types = array( 'patrol' => array( 'ApiQueryRecentChanges', 'getPatrolToken' ) );
		$names = array( 'edit', 'delete', 'protect', 'move', 'block', 'unblock',
			'email', 'import', 'watch', 'options' );
		foreach ( $names as $name ) {
			$types[$name] = array( 'ApiQueryInfo', 'get' . ucfirst( $name ) . 'Token' );
		}
		wfRunHooks( 'ApiTokensGetTokenTypes', array( &$types ) );
		ksort( $types );
		wfProfileOut( __METHOD__ );

		return $types;
	}

	public function getAllowedParams() {
		return array(
			'type' => array(
				ApiBase::PARAM_DFLT => 'edit',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenTypes() ),
			),
		);
	}

	public function getResultProperties() {
		$props = array(
			'' => array(),
		);

		self::addTokenProperties( $props, $this->getTokenTypes() );

		return $props;
	}

	public function getParamDescription() {
		return array(
			'type' => 'Type of token(s) to request'
		);
	}

	public function getDescription() {
		return 'Gets tokens for data-modifying actions.';
	}

	protected function getExamples() {
		return array(
			'api.php?action=tokens' => 'Retrieve an edit token (the default)',
			'api.php?action=tokens&type=email|move' => 'Retrieve an email token and a move token'
		);
	}
}
