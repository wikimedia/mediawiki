<?php
/**
 * Defines an interface for messages with additional machine-readable data for
 * use by the API, and provides concrete implementations of that interface.
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
 * Interface for messages with machine-readable data for use by the API
 *
 * The idea is that it's a Message that has some extra data for the API to use when interpreting it
 * as an error (or, in the future, as a warning). Internals of MediaWiki often use messages (or
 * message keys, or Status objects containing messages) to pass information about errors to the user
 * (see e.g. Title::getUserPermissionsErrors()) and the API has to make do with that.
 *
 * @since 1.25
 * @note This interface exists to work around PHP's inheritance, so ApiMessage
 *  can extend Message and ApiRawMessage can extend RawMessage while still
 *  allowing an instanceof check for a Message object including this
 *  functionality. If for some reason you feel the need to implement this
 *  interface on some other class, that class must also implement all the
 *  public methods the Message class provides (not just those from
 *  MessageSpecifier, which as written is fairly useless).
 * @ingroup API
 */
interface IApiMessage extends MessageSpecifier {
	/**
	 * Returns a machine-readable code for use by the API
	 *
	 * If no code was specifically set, the message key is used as the code
	 * after removing "apiwarn-" or "apierror-" prefixes and applying
	 * backwards-compatibility mappings.
	 *
	 * @return string
	 */
	public function getApiCode();

	/**
	 * Returns additional machine-readable data about the error condition
	 * @return array
	 */
	public function getApiData();

	/**
	 * Sets the machine-readable code for use by the API
	 * @param string|null $code If null, uses the default (see self::getApiCode())
	 * @param array|null $data If non-null, passed to self::setApiData()
	 */
	public function setApiCode( $code, array $data = null );

	/**
	 * Sets additional machine-readable data about the error condition
	 * @param array $data
	 */
	public function setApiData( array $data );
}

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

/**
 * Extension of Message implementing IApiMessage
 * @since 1.25
 * @ingroup API
 */
class ApiMessage extends Message implements IApiMessage {
	use ApiMessageTrait;

	/**
	 * Create an IApiMessage for the message
	 *
	 * This returns $msg if it's an IApiMessage, calls 'new ApiRawMessage' if
	 * $msg is a RawMessage, or calls 'new ApiMessage' in all other cases.
	 *
	 * @param Message|RawMessage|array|string $msg
	 * @param string|null $code
	 * @param array|null $data
	 * @return IApiMessage
	 */
	public static function create( $msg, $code = null, array $data = null ) {
		if ( is_array( $msg ) ) {
			// From StatusValue
			if ( isset( $msg['message'] ) ) {
				if ( isset( $msg['params'] ) ) {
					$msg = array_merge( [ $msg['message'] ], $msg['params'] );
				} else {
					$msg = [ $msg['message'] ];
				}
			}

			// Weirdness that comes in sometimes, including the above
			if ( $msg[0] instanceof MessageSpecifier ) {
				$msg = $msg[0];
			}
		}

		if ( $msg instanceof IApiMessage ) {
			return $msg;
		} elseif ( $msg instanceof RawMessage ) {
			return new ApiRawMessage( $msg, $code, $data );
		} else {
			return new ApiMessage( $msg, $code, $data );
		}
	}

	/**
	 * @param Message|string|array $msg
	 *  - Message: is cloned
	 *  - array: first element is $key, rest are $params to Message::__construct
	 *  - string: passed to Message::__construct
	 * @param string|null $code
	 * @param array|null $data
	 */
	public function __construct( $msg, $code = null, array $data = null ) {
		if ( $msg instanceof Message ) {
			foreach ( get_class_vars( get_class( $this ) ) as $key => $value ) {
				if ( isset( $msg->$key ) ) {
					$this->$key = $msg->$key;
				}
			}
		} elseif ( is_array( $msg ) ) {
			$key = array_shift( $msg );
			parent::__construct( $key, $msg );
		} else {
			parent::__construct( $msg );
		}
		$this->setApiCode( $code, $data );
	}
}

/**
 * Extension of RawMessage implementing IApiMessage
 * @since 1.25
 * @ingroup API
 */
class ApiRawMessage extends RawMessage implements IApiMessage {
	use ApiMessageTrait;

	/**
	 * @param RawMessage|string|array $msg
	 *  - RawMessage: is cloned
	 *  - array: first element is $key, rest are $params to RawMessage::__construct
	 *  - string: passed to RawMessage::__construct
	 * @param string|null $code
	 * @param array|null $data
	 */
	public function __construct( $msg, $code = null, array $data = null ) {
		if ( $msg instanceof RawMessage ) {
			foreach ( get_class_vars( get_class( $this ) ) as $key => $value ) {
				if ( isset( $msg->$key ) ) {
					$this->$key = $msg->$key;
				}
			}
		} elseif ( is_array( $msg ) ) {
			$key = array_shift( $msg );
			parent::__construct( $key, $msg );
		} else {
			parent::__construct( $msg );
		}
		$this->setApiCode( $code, $data );
	}
}
