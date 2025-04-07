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

namespace MediaWiki\Api;

use InvalidArgumentException;

/**
 * Trait to implement the IApiMessage interface for Message subclasses
 * @since 1.27
 * @ingroup API
 * @phan-file-suppress PhanTraitParentReference
 * @phan-file-suppress PhanUndeclaredMethod
 */
trait ApiMessageTrait {

	/**
	 * Compatibility code mappings for various MW messages.
	 * @todo Ideally anything relying on this should be changed to use ApiMessage.
	 * @var string[]
	 * @phpcs-require-sorted-array
	 */
	protected static $messageMap = [
		'actionthrottledtext' => 'ratelimited',
		'autoblockedtext' => 'autoblocked',
		'autoblockedtext-tempuser' => 'autoblocked',
		'badaccess-group0' => 'permissiondenied',
		'badaccess-groups' => 'permissiondenied',
		'badipaddress' => 'invalidip',
		'blankpage' => 'emptypage',
		'blockedtext' => 'blocked',
		'blockedtext-composite' => 'blocked',
		'blockedtext-partial' => 'blocked',
		'blockedtext-tempuser' => 'blocked',
		'cannotdelete' => 'cantdelete',
		'cannotundelete' => 'cantundelete',
		'cantmove-titleprotected' => 'protectedtitle',
		'cantrollback' => 'onlyauthor',
		'confirmedittext' => 'confirmemail',
		'content-not-allowed-here' => 'contentnotallowedhere',
		'delete-toobig' => 'bigdelete',
		'deleteprotected' => 'cantedit',
		'edit-conflict' => 'editconflict',
		'imagenocrossnamespace' => 'nonfilenamespace',
		'imagetypemismatch' => 'filetypemismatch',
		'import-noarticle' => 'badinterwiki',
		'importbadinterwiki' => 'badinterwiki',
		'importcantopen' => 'cantopenfile',
		'importnofile' => 'nofile',
		'importuploaderrorpartial' => 'partialupload',
		'importuploaderrorsize' => 'filetoobig',
		'importuploaderrortemp' => 'notempdir',
		'ipb-block-not-found' => 'alreadyblocked',
		'ipb_already_blocked' => 'alreadyblocked',
		'ipb_blocked_as_range' => 'blockedasrange',
		'ipb_cant_unblock' => 'cantunblock',
		'ipb_expiry_invalid' => 'invalidexpiry',
		'ip_range_invalid' => 'invalidrange',
		'longpageerror' => 'contenttoobig',
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
		'userrights-no-interwiki' => 'nointerwikiuserrights',
		'userrights-nodatabase' => 'nosuchdatabase',
	];

	/** @var string|null */
	protected $apiCode = null;
	/** @var array */
	protected $apiData = [];

	public function getApiCode() {
		if ( $this->apiCode === null ) {
			$key = $this->getKey();
			if ( isset( self::$messageMap[$key] ) ) {
				$this->apiCode = self::$messageMap[$key];
			} elseif ( $key === 'apierror-missingparam' ) {
				// @todo: Kill this case along with ApiBase::$messageMap
				$this->apiCode = 'no' . $this->getParams()[0];
			} elseif ( str_starts_with( $key, 'apiwarn-' ) ) {
				$this->apiCode = substr( $key, 8 );
			} elseif ( str_starts_with( $key, 'apierror-' ) ) {
				$this->apiCode = substr( $key, 9 );
			} else {
				$this->apiCode = $key;
			}

			// Ensure the code is actually valid
			$this->apiCode = preg_replace( '/[^a-zA-Z0-9_-]/', '_', $this->apiCode );
		}
		return $this->apiCode;
	}

	public function setApiCode( $code, ?array $data = null ) {
		if ( $code !== null && !ApiErrorFormatter::isValidApiCode( $code ) ) {
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

	public function __serialize() {
		return [
			'parent' => parent::__serialize(),
			'apiCode' => $this->apiCode,
			'apiData' => $this->apiData,
		];
	}

	public function __unserialize( $data ) {
		parent::__unserialize( $data['parent'] );
		$this->apiCode = $data['apiCode'];
		$this->apiData = $data['apiData'];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiMessageTrait::class, 'ApiMessageTrait' );
