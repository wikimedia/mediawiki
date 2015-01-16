<?php
/**
 * Module to fetch tokens via action=query&meta=tokens
 *
 * Created on August 8, 2014
 *
 * Copyright © 2014 Brad Jorsch bjorsch@wikimedia.org
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
 * @since 1.24
 */

/**
 * Module to fetch tokens via action=query&meta=tokens
 *
 * @ingroup API
 * @since 1.24
 */
class ApiQueryTokens extends ApiQueryBase {

	public function execute() {
		$params = $this->extractRequestParams();
		$res = array(
			ApiResult::META_TYPE => 'assoc',
		);

		if ( $this->lacksSameOriginSecurity() ) {
			$this->setWarning( 'Tokens may not be obtained when the same-origin policy is not applied' );
			return;
		}

		$salts = self::getTokenTypeSalts();
		foreach ( $params['type'] as $type ) {
			$salt = $salts[$type];
			$val = $this->getUser()->getEditToken( $salt, $this->getRequest() );
			$res[$type . 'token'] = $val;
		}

		$this->getResult()->addValue( 'query', $this->getModuleName(), $res );
	}

	public static function getTokenTypeSalts() {
		static $salts = null;
		if ( !$salts ) {
			$salts = array(
				'csrf' => '',
				'watch' => 'watch',
				'patrol' => 'patrol',
				'rollback' => 'rollback',
				'userrights' => 'userrights',
			);
			Hooks::run( 'ApiQueryTokensRegisterTypes', array( &$salts ) );
			ksort( $salts );
		}

		return $salts;
	}

	public function getAllowedParams() {
		return array(
			'type' => array(
				ApiBase::PARAM_DFLT => 'csrf',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_keys( self::getTokenTypeSalts() ),
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&meta=tokens'
				=> 'apihelp-query+tokens-example-simple',
			'action=query&meta=tokens&type=watch|patrol'
				=> 'apihelp-query+tokens-example-types',
		);
	}

	public function getCacheMode( $params ) {
		return 'private';
	}
}
