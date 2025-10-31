<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use Wikimedia\Message\MessageSpecifier;

/**
 * Interface for messages with machine-readable data for use by the API
 *
 * The idea is that it's a Message that has some extra data for the API to use when interpreting it
 * as an error (or, in the future, as a warning). Internals of MediaWiki often use messages (or
 * message keys, or Status objects containing messages) to pass information about errors to the user
 * (see e.g. PermissionManager::getPermissionErrors()) and the API has to make do with that.
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
	public function setApiCode( $code, ?array $data = null );

	/**
	 * Sets additional machine-readable data about the error condition
	 */
	public function setApiData( array $data );
}

/** @deprecated class alias since 1.43 */
class_alias( IApiMessage::class, 'IApiMessage' );
