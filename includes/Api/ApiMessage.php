<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use Wikimedia\Message\MessageSpecifier;

/**
 * Extension of Message implementing IApiMessage
 * @newable
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
	 * @stable to call
	 * @param MessageSpecifier|array|string $msg
	 * @param string|null $code
	 * @param array|null $data
	 * @return IApiMessage
	 * @param-taint $msg tainted
	 */
	public static function create( $msg, $code = null, ?array $data = null ) {
		if ( is_array( $msg ) ) {
			// From StatusValue
			if ( isset( $msg['message'] ) ) {
				$msg = [ $msg['message'], ...$msg['params'] ?? [] ];
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
	 * @param MessageSpecifier|string|array $msg
	 *  - Message: is cloned
	 *  - array: first element is $key, rest are $params to Message::__construct
	 *  - string, any other MessageSpecifier: passed to Message::__construct
	 * @param string|null $code
	 * @param array|null $data
	 */
	public function __construct( $msg, $code = null, ?array $data = null ) {
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

/** @deprecated class alias since 1.43 */
class_alias( ApiMessage::class, 'ApiMessage' );
