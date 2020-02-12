<?php
/**
 * This file contains the ApiErrorFormatter definition, plus implementations of
 * specific formatters.
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
 * Formats errors and warnings for the API, and add them to the associated
 * ApiResult.
 * @since 1.25
 * @ingroup API
 * @phan-file-suppress PhanUndeclaredMethod Undeclared methods in IApiMessage
 */
class ApiErrorFormatter {
	/** @var Title Dummy title to silence warnings from MessageCache::parse() */
	private static $dummyTitle = null;

	/** @var ApiResult */
	protected $result;

	/** @var Language */
	protected $lang;
	protected $useDB = false;
	protected $format = 'none';

	/**
	 * @param ApiResult $result Into which data will be added
	 * @param Language $lang Used for i18n
	 * @param string $format
	 *  - plaintext: Error message as something vaguely like plaintext
	 *    (it's basically wikitext with HTML tags stripped and entities decoded)
	 *  - wikitext: Error message as wikitext
	 *  - html: Error message as HTML
	 *  - raw: Raw message key and parameters, no human-readable text
	 *  - none: Code and data only, no human-readable text
	 * @param bool $useDB Whether to use local translations for errors and warnings.
	 */
	public function __construct( ApiResult $result, Language $lang, $format, $useDB = false ) {
		$this->result = $result;
		$this->lang = $lang;
		$this->useDB = $useDB;
		$this->format = $format;
	}

	/**
	 * Test whether a code is a valid API error code
	 *
	 * A valid code contains only ASCII letters, numbers, underscore, and
	 * hyphen and is not the empty string.
	 *
	 * For backwards compatibility, any code beginning 'internal_api_error_' is
	 * also allowed.
	 *
	 * @param string $code
	 * @return bool
	 */
	public static function isValidApiCode( $code ) {
		return is_string( $code ) && (
			preg_match( '/^[a-zA-Z0-9_-]+$/', $code ) ||
			// TODO: Deprecate this
			preg_match( '/^internal_api_error_[^\0\r\n]+$/', $code )
		);
	}

	/**
	 * Return a formatter like this one but with a different format
	 *
	 * @since 1.32
	 * @param string $format New format.
	 * @return ApiErrorFormatter
	 */
	public function newWithFormat( $format ) {
		return new self( $this->result, $this->lang, $format, $this->useDB );
	}

	/**
	 * Fetch the format for this formatter
	 * @since 1.32
	 * @return string
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * Fetch the Language for this formatter
	 * @since 1.29
	 * @return Language
	 */
	public function getLanguage() {
		return $this->lang;
	}

	/**
	 * Fetch a dummy title to set on Messages
	 * @return Title
	 */
	protected function getDummyTitle() {
		if ( self::$dummyTitle === null ) {
			self::$dummyTitle = Title::makeTitle( NS_SPECIAL, 'Badtitle/' . __METHOD__ );
		}
		return self::$dummyTitle;
	}

	/**
	 * Add a warning to the result
	 * @param string|null $modulePath
	 * @param Message|array|string $msg Warning message. See ApiMessage::create().
	 * @param string|null $code See ApiMessage::create().
	 * @param array|null $data See ApiMessage::create().
	 */
	public function addWarning( $modulePath, $msg, $code = null, $data = null ) {
		$msg = ApiMessage::create( $msg, $code, $data )
			->inLanguage( $this->lang )
			->title( $this->getDummyTitle() )
			->useDatabase( $this->useDB );
		$this->addWarningOrError( 'warning', $modulePath, $msg );
	}

	/**
	 * Add an error to the result
	 * @param string|null $modulePath
	 * @param Message|array|string $msg Warning message. See ApiMessage::create().
	 * @param string|null $code See ApiMessage::create().
	 * @param array|null $data See ApiMessage::create().
	 */
	public function addError( $modulePath, $msg, $code = null, $data = null ) {
		$msg = ApiMessage::create( $msg, $code, $data )
			->inLanguage( $this->lang )
			->title( $this->getDummyTitle() )
			->useDatabase( $this->useDB );
		$this->addWarningOrError( 'error', $modulePath, $msg );
	}

	/**
	 * Add warnings and errors from a StatusValue object to the result
	 * @param string|null $modulePath
	 * @param StatusValue $status
	 * @param string[]|string $types 'warning' and/or 'error'
	 * @param string[] $filter Messages to filter out (since 1.33)
	 */
	public function addMessagesFromStatus(
		$modulePath, StatusValue $status, $types = [ 'warning', 'error' ], array $filter = []
	) {
		if ( $status->isGood() || !$status->getErrors() ) {
			return;
		}

		$types = (array)$types;
		foreach ( $status->getErrors() as $error ) {
			if ( !in_array( $error['type'], $types, true ) ) {
				continue;
			}

			if ( $error['type'] === 'error' ) {
				$tag = 'error';
			} else {
				// Assume any unknown type is a warning
				$tag = 'warning';
			}

			$msg = ApiMessage::create( $error )
				->inLanguage( $this->lang )
				->title( $this->getDummyTitle() )
				->useDatabase( $this->useDB );
			if ( !in_array( $msg->getKey(), $filter, true ) ) {
				$this->addWarningOrError( $tag, $modulePath, $msg );
			}
		}
	}

	/**
	 * Get an ApiMessage from a throwable
	 * @since 1.29
	 * @param Throwable $exception
	 * @param array $options
	 *  - wrap: (string|array|MessageSpecifier) Used to wrap the throwable's
	 *    message if it's not an ILocalizedException. The throwable's message
	 *    will be added as the final parameter.
	 *  - code: (string) Default code
	 *  - data: (array) Default extra data
	 * @return IApiMessage
	 */
	public function getMessageFromException( Throwable $exception, array $options = [] ) {
		$options += [ 'code' => null, 'data' => [] ];

		if ( $exception instanceof ILocalizedException ) {
			$msg = $exception->getMessageObject();
			$params = [];
		} elseif ( $exception instanceof MessageSpecifier ) {
			$msg = Message::newFromSpecifier( $exception );
			$params = [];
		} else {
			if ( isset( $options['wrap'] ) ) {
				$msg = $options['wrap'];
			} else {
				$msg = new RawMessage( '$1' );
				if ( !isset( $options['code'] ) ) {
					$class = preg_replace( '#^Wikimedia\\\Rdbms\\\#', '', get_class( $exception ) );
					$options['code'] = 'internal_api_error_' . $class;
					$options['data']['errorclass'] = get_class( $exception );
				}
			}
			$params = [ wfEscapeWikiText( $exception->getMessage() ) ];
		}
		return ApiMessage::create( $msg, $options['code'], $options['data'] )
			->params( $params )
			->inLanguage( $this->lang )
			->title( $this->getDummyTitle() )
			->useDatabase( $this->useDB );
	}

	/**
	 * Format a throwable as an array
	 * @since 1.29
	 * @param Throwable $exception
	 * @param array $options See self::getMessageFromException(), plus
	 *  - format: (string) Format override
	 * @return array
	 */
	public function formatException( Throwable $exception, array $options = [] ) {
		return $this->formatMessage(
			// @phan-suppress-next-line PhanTypeMismatchArgument
			$this->getMessageFromException( $exception, $options ),
			$options['format'] ?? null
		);
	}

	/**
	 * Format a message as an array
	 * @param Message|array|string $msg Message. See ApiMessage::create().
	 * @param string|null $format
	 * @return array
	 */
	public function formatMessage( $msg, $format = null ) {
		$msg = ApiMessage::create( $msg )
			->inLanguage( $this->lang )
			->title( $this->getDummyTitle() )
			->useDatabase( $this->useDB );
		return $this->formatMessageInternal( $msg, $format ?: $this->format );
	}

	/**
	 * Format messages from a StatusValue as an array
	 * @param StatusValue $status
	 * @param string $type 'warning' or 'error'
	 * @param string|null $format
	 * @return array
	 */
	public function arrayFromStatus( StatusValue $status, $type = 'error', $format = null ) {
		if ( $status->isGood() || !$status->getErrors() ) {
			return [];
		}

		$result = new ApiResult( 1e6 );
		$formatter = new ApiErrorFormatter(
			$result, $this->lang, $format ?: $this->format, $this->useDB
		);
		$formatter->addMessagesFromStatus( null, $status, [ $type ] );
		switch ( $type ) {
			case 'error':
				return (array)$result->getResultData( [ 'errors' ] );
			case 'warning':
				return (array)$result->getResultData( [ 'warnings' ] );
		}
	}

	/**
	 * Turn wikitext into something resembling plaintext
	 * @since 1.29
	 * @param string $text
	 * @return string
	 */
	public static function stripMarkup( $text ) {
		// Turn semantic quoting tags to quotes
		$ret = preg_replace( '!</?(var|kbd|samp|code)>!', '"', $text );

		// Strip tags and decode.
		$ret = Sanitizer::stripAllTags( $ret );

		return $ret;
	}

	/**
	 * Format a Message object for raw format
	 * @param MessageSpecifier $msg
	 * @return array
	 */
	private function formatRawMessage( MessageSpecifier $msg ) {
		$ret = [
			'key' => $msg->getKey(),
			'params' => $msg->getParams(),
		];
		ApiResult::setIndexedTagName( $ret['params'], 'param' );

		// Transform Messages as parameters in the style of Message::fooParam().
		foreach ( $ret['params'] as $i => $param ) {
			if ( $param instanceof MessageSpecifier ) {
				$ret['params'][$i] = [ 'message' => $this->formatRawMessage( $param ) ];
			}
		}
		return $ret;
	}

	/**
	 * Format a message as an array
	 * @since 1.29
	 * @param ApiMessage|ApiRawMessage $msg
	 * @param string|null $format
	 * @return array
	 */
	protected function formatMessageInternal( $msg, $format ) {
		$value = [ 'code' => $msg->getApiCode() ];
		switch ( $format ) {
			case 'plaintext':
				$value += [
					'text' => self::stripMarkup( $msg->text() ),
					ApiResult::META_CONTENT => 'text',
				];
				break;

			case 'wikitext':
				$value += [
					'text' => $msg->text(),
					ApiResult::META_CONTENT => 'text',
				];
				break;

			case 'html':
				$value += [
					'html' => $msg->parse(),
					ApiResult::META_CONTENT => 'html',
				];
				break;

			case 'raw':
				$value += $this->formatRawMessage( $msg );
				break;

			case 'none':
				break;
		}
		$data = $msg->getApiData();
		if ( $data ) {
			$value['data'] = $msg->getApiData() + [
				ApiResult::META_TYPE => 'assoc',
			];
		}
		return $value;
	}

	/**
	 * Actually add the warning or error to the result
	 * @param string $tag 'warning' or 'error'
	 * @param string|null $modulePath
	 * @param ApiMessage|ApiRawMessage $msg
	 */
	protected function addWarningOrError( $tag, $modulePath, $msg ) {
		$value = $this->formatMessageInternal( $msg, $this->format );
		if ( $modulePath !== null ) {
			$value += [ 'module' => $modulePath ];
		}

		$path = [ $tag . 's' ];
		$existing = $this->result->getResultData( $path );
		if ( $existing === null || !in_array( $value, $existing ) ) {
			$flags = ApiResult::NO_SIZE_CHECK;
			if ( $existing === null ) {
				$flags |= ApiResult::ADD_ON_TOP;
			}
			$this->result->addValue( $path, null, $value, $flags );
			$this->result->addIndexedTagName( $path, $tag );
		}
	}
}
