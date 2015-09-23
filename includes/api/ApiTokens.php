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
 * @deprecated since 1.24
 * @ingroup API
 */
class ApiTokens extends ApiBase {

	public function execute() {
		$this->setWarning(
			"action=tokens has been deprecated. Please use action=query&meta=tokens instead."
		);
		$this->logFeatureUsage( "action=tokens" );

		$params = $this->extractRequestParams();
		$res = array(
			ApiResult::META_TYPE => 'assoc',
		);

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
		// If we're in a mode that breaks the same-origin policy, no tokens can
		// be obtained
		if ( $this->lacksSameOriginSecurity() ) {
			return array();
		}

		static $types = null;
		if ( $types ) {
			return $types;
		}
		$types = array( 'patrol' => array( 'ApiQueryRecentChanges', 'getPatrolToken' ) );
		$names = array( 'edit', 'delete', 'protect', 'move', 'block', 'unblock',
			'email', 'import', 'watch', 'options' );
		foreach ( $names as $name ) {
			$types[$name] = array( 'ApiQueryInfo', 'get' . ucfirst( $name ) . 'Token' );
		}
		Hooks::run( 'ApiTokensGetTokenTypes', array( &$types ) );
		ksort( $types );

		return $types;
	}

	public function isDeprecated() {
		return true;
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

	protected function getExamplesMessages() {
		return array(
			'action=tokens'
				=> 'apihelp-tokens-example-edit',
			'action=tokens&type=email|move'
				=> 'apihelp-tokens-example-emailmove',
		);
	}
}
