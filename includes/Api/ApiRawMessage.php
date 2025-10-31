<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Language\RawMessage;

/**
 * Extension of RawMessage implementing IApiMessage
 * @newable
 * @since 1.25
 * @ingroup API
 */
class ApiRawMessage extends RawMessage implements IApiMessage {
	use ApiMessageTrait;

	/**
	 * @stable to call
	 * @param RawMessage|string|array $msg
	 *  - RawMessage: is cloned
	 *  - array: first element is $key, rest are $params to RawMessage::__construct
	 *  - string: passed to RawMessage::__construct
	 * @param string|null $code
	 * @param array|null $data
	 */
	public function __construct( $msg, $code = null, ?array $data = null ) {
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

	/** @inheritDoc */
	public function getApiCode() {
		if ( $this->apiCode === null ) {
			// Copied from ApiMessageTrait to avoid changing the error codes. This causes T350248,
			// but there's nothing better we can do when a RawMessage is used.
			$this->apiCode = preg_replace( '/[^a-zA-Z0-9_-]/', '_', $this->getTextOfRawMessage() );
		}
		return $this->apiCode;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiRawMessage::class, 'ApiRawMessage' );
