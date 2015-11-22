<?php
/**
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
 * @ingroup Auth
 */

namespace MediaWiki\Auth;

class ConfirmLinkAuthenticationRequest extends AuthenticationRequest {
	/** @var AuthenticationRequest[] */
	protected $linkRequests;

	/** @var string[] List of unique IDs of the confirmed accounts. */
	public $confirmedLinkIDs = [];

	/**
	 * @param AuthenticationRequest[] $linkRequests A list of autolink requests
	 *  which need to be confirmed.
	 */
	public function __construct( array $linkRequests ) {
		if ( !$linkRequests ) {
			throw new \InvalidArgumentException( '$linkRequests must not be empty' );
		}
		$this->linkRequests = $linkRequests;
	}

	public function getFieldInfo() {
		$options = [];
		foreach ( $this->linkRequests as $req ) {
			$description = $req->describeCredentials();
			$options[$req->getUniqueId()] = wfMessage(
				'authprovider-confirmlink-option',
				$description['provider']->text(), $description['account']->text()
			);
		}
		return [
			'confirmedLinkIDs' => [
				'type' => 'multiselect',
				'options' => $options,
				'label' => wfMessage( 'authprovider-confirmlink-request-label' ),
				'help' => wfMessage( 'authprovider-confirmlink-request-help' ),
				'optional' => true,
			]
		];
	}

	public function getUniqueId() {
		return parent::getUniqueId() . ':' . implode( '|', array_map( function ( $req ) {
			return $req->getUniqueId();
		}, $this->linkRequests ) );
	}

	/**
	 * Implementing this mainly for use from the unit tests.
	 * @param array $data
	 * @return AuthenticationRequest
	 */
	public static function __set_state( $data ) {
		$ret = new static( $data['linkRequests'] );
		foreach ( $data as $k => $v ) {
			$ret->$k = $v;
		}
		return $ret;
	}
}
