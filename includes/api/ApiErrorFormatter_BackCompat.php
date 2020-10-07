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

use MediaWiki\MediaWikiServices;

/**
 * Format errors and warnings in the old style, for backwards compatibility.
 * @since 1.25
 * @deprecated Only for backwards compatibility, do not use
 * @ingroup API
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class ApiErrorFormatter_BackCompat extends ApiErrorFormatter {

	/**
	 * @param ApiResult $result Into which data will be added
	 */
	public function __construct( ApiResult $result ) {
		parent::__construct(
			$result,
			MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' ),
			'none',
			false
		);
	}

	public function getFormat() {
		return 'bc';
	}

	public function arrayFromStatus( StatusValue $status, $type = 'error', $format = null ) {
		if ( $status->isGood() || !$status->getErrors() ) {
			return [];
		}

		$result = [];
		foreach ( $status->getErrorsByType( $type ) as $error ) {
			$msg = ApiMessage::create( $error );
			$error = [
				'message' => $msg->getKey(),
				'params' => $msg->getParams(),
				'code' => $msg->getApiCode(),
			] + $error;
			ApiResult::setIndexedTagName( $error['params'], 'param' );
			$result[] = $error;
		}
		ApiResult::setIndexedTagName( $result, $type );

		return $result;
	}

	protected function formatMessageInternal( $msg, $format ) {
		return [
			'code' => $msg->getApiCode(),
			'info' => $msg->text(),
		] + $msg->getApiData();
	}

	/**
	 * Format a throwable as an array
	 * @since 1.29
	 * @param Throwable $exception
	 * @param array $options See parent::formatException(), plus
	 *  - bc: (bool) Return only the string, not an array
	 * @return array|string
	 */
	public function formatException( Throwable $exception, array $options = [] ) {
		$ret = parent::formatException( $exception, $options );
		return empty( $options['bc'] ) ? $ret : $ret['info'];
	}

	protected function addWarningOrError( $tag, $modulePath, $msg ) {
		$value = self::stripMarkup( $msg->text() );

		if ( $tag === 'error' ) {
			// In BC mode, only one error
			$existingError = $this->result->getResultData( [ 'error' ] );
			if ( !is_array( $existingError ) ||
				!isset( $existingError['code'] ) || !isset( $existingError['info'] )
			) {
				$value = [
					'code' => $msg->getApiCode(),
					'info' => $value,
				] + $msg->getApiData();
				$this->result->addValue( null, 'error', $value,
					ApiResult::OVERRIDE | ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
			}
		} else {
			if ( $modulePath === null ) {
				$moduleName = 'unknown';
			} else {
				$i = strrpos( $modulePath, '+' );
				$moduleName = $i === false ? $modulePath : substr( $modulePath, $i + 1 );
			}

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
