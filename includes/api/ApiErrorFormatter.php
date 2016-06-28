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
	 *  - text: Error message as wikitext
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
	 * @param string $moduleName
	 * @param MessageSpecifier|array|string $msg i18n message for the warning
	 * @param string $code Machine-readable code for the warning. Defaults as
	 *   for IApiMessage::getApiCode().
	 * @param array $data Machine-readable data for the warning, if any.
	 *   Uses IApiMessage::getApiData() if $msg implements that interface.
	 */
	public function addWarning( $moduleName, $msg, $code = null, $data = null ) {
		$msg = ApiMessage::create( $msg, $code, $data )
			->inLanguage( $this->lang )
			->title( $this->getDummyTitle() )
			->useDatabase( $this->useDB );
		$this->addWarningOrError( 'warning', $moduleName, $msg );
	}

	/**
	 * Add an error to the result
	 * @param string $moduleName
	 * @param MessageSpecifier|array|string $msg i18n message for the error
	 * @param string $code Machine-readable code for the warning. Defaults as
	 *   for IApiMessage::getApiCode().
	 * @param array $data Machine-readable data for the warning, if any.
	 *   Uses IApiMessage::getApiData() if $msg implements that interface.
	 */
	public function addError( $moduleName, $msg, $code = null, $data = null ) {
		$msg = ApiMessage::create( $msg, $code, $data )
			->inLanguage( $this->lang )
			->title( $this->getDummyTitle() )
			->useDatabase( $this->useDB );
		$this->addWarningOrError( 'error', $moduleName, $msg );
	}

	/**
	 * Add warnings and errors from a Status object to the result
	 * @param string $moduleName
	 * @param Status $status
	 * @param string[] $types 'warning' and/or 'error'
	 */
	public function addMessagesFromStatus(
		$moduleName, Status $status, $types = [ 'warning', 'error' ]
	) {
		if ( $status->isGood() || !$status->errors ) {
			return;
		}

		$types = (array)$types;
		foreach ( $status->errors as $error ) {
			if ( !in_array( $error['type'], $types, true ) ) {
				continue;
			}

			if ( $error['type'] === 'error' ) {
				$tag = 'error';
			} else {
				// Assume any unknown type is a warning
				$tag = 'warning';
			}

			if ( is_array( $error ) && isset( $error['message'] ) ) {
				// Normal case
				if ( $error['message'] instanceof Message ) {
					$msg = ApiMessage::create( $error['message'], null, [] );
				} else {
					$args = isset( $error['params'] ) ? $error['params'] : [];
					array_unshift( $args, $error['message'] );
					$error += [ 'params' => [] ];
					$msg = ApiMessage::create( $args, null, [] );
				}
			} elseif ( is_array( $error ) ) {
				// Weird case handled by Message::getErrorMessage
				$msg = ApiMessage::create( $error, null, [] );
			} else {
				// Another weird case handled by Message::getErrorMessage
				$msg = ApiMessage::create( $error, null, [] );
			}

			$msg->inLanguage( $this->lang )
				->title( $this->getDummyTitle() )
				->useDatabase( $this->useDB );
			$this->addWarningOrError( $tag, $moduleName, $msg );
		}
	}

	/**
	 * Format messages from a Status as an array
	 * @param Status $status
	 * @param string $type 'warning' or 'error'
	 * @param string|null $format
	 * @return array
	 */
	public function arrayFromStatus( Status $status, $type = 'error', $format = null ) {
		if ( $status->isGood() || !$status->errors ) {
			return [];
		}

		$result = new ApiResult( 1e6 );
		$formatter = new ApiErrorFormatter(
			$result, $this->lang, $format ?: $this->format, $this->useDB
		);
		$formatter->addMessagesFromStatus( 'dummy', $status, [ $type ] );
		switch ( $type ) {
			case 'error':
				return (array)$result->getResultData( [ 'errors', 'dummy' ] );
			case 'warning':
				return (array)$result->getResultData( [ 'warnings', 'dummy' ] );
		}
	}

	/**
	 * Actually add the warning or error to the result
	 * @param string $tag 'warning' or 'error'
	 * @param string $moduleName
	 * @param ApiMessage|ApiRawMessage $msg
	 */
	protected function addWarningOrError( $tag, $moduleName, $msg ) {
		$value = [ 'code' => $msg->getApiCode() ];
		switch ( $this->format ) {
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
				$value += [
					'key' => $msg->getKey(),
					'params' => $msg->getParams(),
				];
				ApiResult::setIndexedTagName( $value['params'], 'param' );
				break;

			case 'none':
				break;
		}
		$value += $msg->getApiData();

		$path = [ $tag . 's', $moduleName ];
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

/**
 * Format errors and warnings in the old style, for backwards compatibility.
 * @since 1.25
 * @deprecated Only for backwards compatibility, do not use
 * @ingroup API
 */
// @codingStandardsIgnoreStart Squiz.Classes.ValidClassName.NotCamelCaps
class ApiErrorFormatter_BackCompat extends ApiErrorFormatter {
	// @codingStandardsIgnoreEnd

	/**
	 * @param ApiResult $result Into which data will be added
	 */
	public function __construct( ApiResult $result ) {
		parent::__construct( $result, Language::factory( 'en' ), 'none', false );
	}

	public function arrayFromStatus( Status $status, $type = 'error', $format = null ) {
		if ( $status->isGood() || !$status->errors ) {
			return [];
		}

		$result = [];
		foreach ( $status->getErrorsByType( $type ) as $error ) {
			if ( $error['message'] instanceof Message ) {
				$error = [
					'message' => $error['message']->getKey(),
					'params' => $error['message']->getParams(),
				] + $error;
			}
			ApiResult::setIndexedTagName( $error['params'], 'param' );
			$result[] = $error;
		}
		ApiResult::setIndexedTagName( $result, $type );

		return $result;
	}

	protected function addWarningOrError( $tag, $moduleName, $msg ) {
		$value = $msg->plain();

		if ( $tag === 'error' ) {
			// In BC mode, only one error
			$code = $msg->getApiCode();
			if ( isset( ApiBase::$messageMap[$code] ) ) {
				// Backwards compatibility
				$code = ApiBase::$messageMap[$code]['code'];
			}

			$value = [
				'code' => $code,
				'info' => $value,
			] + $msg->getApiData();
			$this->result->addValue( null, 'error', $value,
				ApiResult::OVERRIDE | ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
		} else {
			// Don't add duplicate warnings
			$tag .= 's';
			$path = [ $tag, $moduleName ];
			$oldWarning = $this->result->getResultData( [ $tag, $moduleName, $tag ] );
			if ( $oldWarning !== null ) {
				$warnPos = strpos( $oldWarning, $value );
				// If $value was found in $oldWarning, check if it starts at 0 or after "\n"
				if ( $warnPos !== false && ( $warnPos === 0 || $oldWarning[$warnPos - 1] === "\n" ) ) {
					// Check if $value is followed by "\n" or the end of the $oldWarning
					$warnPos += strlen( $value );
					if ( strlen( $oldWarning ) <= $warnPos || $oldWarning[$warnPos] === "\n" ) {
						return;
					}
				}
				// If there is a warning already, append it to the existing one
				$value = "$oldWarning\n$value";
			}
			$this->result->addContentValue( $path, $tag, $value,
				ApiResult::OVERRIDE | ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
		}
	}
}
