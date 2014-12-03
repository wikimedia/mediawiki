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
 * @since 1.25
 * @ingroup API
 */
interface IApiMessage extends MessageSpecifier {
	/**
	 * Returns a machine-readable code for use by the API
	 *
	 * The message key is often sufficient, but sometimes there are multiple
	 * messages used for what is really the same underlying condition (e.g.
	 * badaccess-groups and badaccess-group0)
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
	 * @param string|null $code If null, the message key should be returned by self::getApiCode()
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
 * Extension of Message implementing IApiMessage
 * @since 1.25
 * @ingroup API
 * @todo: Would be nice to use a Trait here to avoid code duplication
 */
class ApiMessage extends Message implements IApiMessage {
	protected $apiCode = null;
	protected $apiData = array();

	/**
	 * Create an IApiMessage for the message
	 *
	 * This returns $msg if it's an IApiMessage, calls 'new ApiRawMessage' if
	 * $msg is a RawMessage, or calls 'new ApiMessage' in all other cases.
	 *
	 * @param Message|RawMessage|array|string $msg
	 * @param string|null $code
	 * @param array|null $data
	 * @return ApiMessage
	 */
	public static function create( $msg, $code = null, array $data = null ) {
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
	 * @return ApiMessage
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
		$this->apiCode = $code;
		$this->apiData = (array)$data;
	}

	public function getApiCode() {
		return $this->apiCode === null ? $this->getKey() : $this->apiCode;
	}

	public function setApiCode( $code, array $data = null ) {
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
}

/**
 * Extension of RawMessage implementing IApiMessage
 * @since 1.25
 * @ingroup API
 * @todo: Would be nice to use a Trait here to avoid code duplication
 */
class ApiRawMessage extends RawMessage implements IApiMessage {
	protected $apiCode = null;
	protected $apiData = array();

	/**
	 * @param RawMessage|string|array $msg
	 *  - RawMessage: is cloned
	 *  - array: first element is $key, rest are $params to RawMessage::__construct
	 *  - string: passed to RawMessage::__construct
	 * @param string|null $code
	 * @param array|null $data
	 * @return ApiMessage
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
		$this->apiCode = $code;
		$this->apiData = (array)$data;
	}

	public function getApiCode() {
		return $this->apiCode === null ? $this->getKey() : $this->apiCode;
	}

	public function setApiCode( $code, array $data = null ) {
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
}
