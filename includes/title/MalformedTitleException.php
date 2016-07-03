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

/**
 * MalformedTitleException is thrown when a TitleParser is unable to parse a title string.
 * @since 1.23
 */
class MalformedTitleException extends Exception {
	private $titleText = null;
	private $errorMessage = null;
	private $errorMessageParameters = [];

	/**
	 * @param string $errorMessage Localisation message describing the error (since MW 1.26)
	 * @param string $titleText The invalid title text (since MW 1.26)
	 * @param string[] $errorMessageParameters Additional parameters for the error message.
	 * $titleText will be appended if it's not null. (since MW 1.26)
	 */
	public function __construct(
		$errorMessage = null, $titleText = null, $errorMessageParameters = []
	) {
		$this->errorMessage = $errorMessage;
		$this->titleText = $titleText;
		if ( $titleText !== null ) {
			$errorMessageParameters[] = $titleText;
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
	 * @return string|null
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 * @since 1.26
	 * @return string[]
	 */
	public function getErrorMessageParameters() {
		return $this->errorMessageParameters;
	}
}
