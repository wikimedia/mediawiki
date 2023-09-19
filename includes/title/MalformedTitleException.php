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

namespace MediaWiki\Title;

use Exception;
use ILocalizedException;
use Message;

/**
 * MalformedTitleException is thrown when a TitleParser is unable to parse a title string.
 * @newable
 * @since 1.23
 */
class MalformedTitleException extends Exception implements ILocalizedException {

	/** @var string|null */
	private $titleText;
	/** @var string */
	private $errorMessage;
	/** @var array */
	private $errorMessageParameters;

	/**
	 * @stable to call
	 * @param string $errorMessage Localisation message describing the error (since MW 1.26)
	 * @param string|null $titleText The invalid title text (since MW 1.26)
	 * @param array $errorMessageParameters Additional parameters for the error message.
	 * $titleText will be appended if it's not null. (since MW 1.26)
	 */
	public function __construct(
		$errorMessage, $titleText = null, $errorMessageParameters = []
	) {
		$this->errorMessage = $errorMessage;
		$this->titleText = $titleText;
		if ( $titleText !== null ) {
			$errorMessageParameters[] = wfEscapeWikiText( $titleText );
		}
		$this->errorMessageParameters = $errorMessageParameters;

		// Supply something useful for Exception::getMessage() to return.
		$enMsg = wfMessage( $errorMessage, $errorMessageParameters );
		$enMsg->inLanguage( 'en' )->useDatabase( false );
		parent::__construct( $enMsg->text() );
	}

	/**
	 * @since 1.26
	 * @return string|null
	 */
	public function getTitleText() {
		return $this->titleText;
	}

	/**
	 * @since 1.26
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 * @since 1.26
	 * @return array
	 */
	public function getErrorMessageParameters() {
		return $this->errorMessageParameters;
	}

	/**
	 * @since 1.29
	 * @return Message
	 */
	public function getMessageObject() {
		return wfMessage( $this->getErrorMessage(), $this->getErrorMessageParameters() );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( MalformedTitleException::class, 'MalformedTitleException' );
