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
	private $titleText;
	private $errorMsg;
	private $errorMsgParameters;

	/**
	 * @param string $errorMsg Localisation message describing the error
	 * @param string $titleText The invalid title text
	 * @param string[] $errorMsgParameters Additional parameters for the error message
	 */
	public function __construct( $errorMsg = null, $titleText = null, $errorMsgParameters = array() ) {
		$this->errorMsg = $errorMsg;
		$this->titleText = $titleText;
		$this->errorMsgParameters = $errorMsgParameters;
	}

	/**
	 * Return l10n messages to be used by BadTitleError for this exception.
	 *
	 * @return array( error page title message, error text message, error text parameters )
	 */
	public function getErrorPageParams() {
		if ( !$this->errorMsg ) {
			return array(
				'badtitletext',
				array( $this->titleText )
			);
		}

		return array(
			$this->errorMsg,
			array_merge( $this->errorMsgParameters, array( $this->titleText ) )
		);
	}
}
