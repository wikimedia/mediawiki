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
 */

/**
 * Trait to implement the IApiMessage interface for Message subclasses
 * @since 1.27
 * @ingroup API
 */
trait ApiMessageTrait {

	/**
	 * Compatibility code mappings for various MW messages.
	 * @todo Ideally anything relying on this should be changed to use ApiMessage.
	 */
	protected static $messageMap = [
		'actionthrottledtext' => 'ratelimited',
		'autoblockedtext' => 'autoblocked',
		'badaccess-group0' => 'permissiondenied',
		'badaccess-groups' => 'permissiondenied',
		'badipaddress' => 'invalidip',
		'blankpage' => 'emptypage',
		'blockedtext' => 'blocked',
		'cannotdelete' => 'cantdelete',
		'cannotundelete' => 'cantundelete',
		'cantmove-titleprotected' => 'protectedtitle',
		'cantrollback' => 'onlyauthor',
		'confirmedittext' => 'confirmemail',
		'content-not-allowed-here' => 'contentnotallowedhere',
		'deleteprotected' => 'cantedit',
		'delete-toobig' => 'bigdelete',
		'edit-conflict' => 'editconflict',
		'imagenocrossnamespace' => 'nonfilenamespace',
		'imagetypemismatch' => 'filetypemismatch',
		'importbadinterwiki' => 'badinterwiki',
		'importcantopen' => 'cantopenfile',
		'import-noarticle' => 'badinterwiki',
		'importnofile' => 'nofile',
		'importuploaderrorpartial' => 'partialupload',
		'importuploaderrorsize' => 'filetoobig',
		'importuploaderrortemp' => 'notempdir',
		'ipb_already_blocked' => 'alreadyblocked',
		'ipb_blocked_as_range' => 'blockedasrange',
		'ipb_cant_unblock' => 'cantunblock',
		'ipb_expiry_invalid' => 'invalidexpiry',
		'ip_range_invalid' => 'invalidrange',
		'mailnologin' => 'cantsend',
		'markedaspatrollederror-noautopatrol' => 'noautopatrol',
		'movenologintext' => 'cantmove-anon',
		'movenotallowed' => 'cantmove',
		'movenotallowedfile' => 'cantmovefile',
		'namespaceprotected' => 'protectednamespace',
		'nocreate-loggedin' => 'cantcreate',
		'nocreatetext' => 'cantcreate-anon',
		'noname' => 'invaliduser',
		'nosuchusershort' => 'nosuchuser',
		'notanarticle' => 'missingtitle',
		'nouserspecified' => 'invaliduser',
		'ns-specialprotected' => 'unsupportednamespace',
		'protect-cantedit' => 'cantedit',
		'protectedinterface' => 'protectednamespace-interface',
		'protectedpagetext' => 'protectedpage',
		'range_block_disabled' => 'rangedisabled',
		'rcpatroldisabled' => 'patroldisabled',
		'readonlytext' => 'readonly',
		'sessionfailure' => 'badtoken',
		'systemblockedtext' => 'blocked',
		'titleprotected' => 'protectedtitle',
		'undo-failure' => 'undofailure',
		'userrights-nodatabase' => 'nosuchdatabase',
		'userrights-no-interwiki' => 'nointerwikiuserrights',
	];

	protected $apiCode = null;
	protected $apiData = [];

	public function getApiCode() {
		if ( $this->apiCode === null ) {
			$key = $this->getKey();
			if ( isset( self::$messageMap[$key] ) ) {
				$this->apiCode = self::$messageMap[$key];
			} elseif ( $key === 'apierror-missingparam' ) {
				/// @todo: Kill this case along with ApiBase::$messageMap
				$this->apiCode = 'no' . $this->getParams()[0];
			} elseif ( substr( $key, 0, 8 ) === 'apiwarn-' ) {
				$this->apiCode = substr( $key, 8 );
			} elseif ( substr( $key, 0, 9 ) === 'apierror-' ) {
				$this->apiCode = substr( $key, 9 );
			} else {
				$this->apiCode = $key;
			}
		}
		return $this->apiCode;
	}

	public function setApiCode( $code, array $data = null ) {
		if ( $code !== null && !( is_string( $code ) && $code !== '' ) ) {
			throw new InvalidArgumentException( "Invalid code \"$code\"" );
		}

		$this->apiCode = $code;
		if ( $data !== null ) {
			$this->setApiData( $data );
		}
	}

	public function getApiData() {
		return $this->apiData;
	}

	public function setApiData( array $data ) {
		$this->apiData = $data;
	}

	public function serialize() {
		return serialize( [
			'parent' => parent::serialize(),
			'apiCode' => $this->apiCode,
			'apiData' => $this->apiData,
		] );
	}

	public function unserialize( $serialized ) {
		$data = unserialize( $serialized );
		parent::unserialize( $data['parent'] );
		$this->apiCode = $data['apiCode'];
		$this->apiData = $data['apiData'];
	}
}
