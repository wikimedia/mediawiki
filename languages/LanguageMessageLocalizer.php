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
 * @ingroup Language
 */

/**
 * Interface for exposing mediawiki messages
 *
 * @since 1.29
 * @ingroup Language
 */
class LanguageMessageLocalizer implements MessageLocalizer {

	private $language;

	/**
	 * @param Language $language
	 */
	public function __construct( Language $language ) {
		$this->language = $language;
	}

	/**
	 * @see MessageLocalizer::msg()
	 *
	 * @param string|string[]|MessageSpecifier $key Message key, or array of keys,
	 *   or a MessageSpecifier.
	 * @param mixed $params,... Normal message parameters
	 * @return Message
	 */
	public function msg( $key /*...*/ ) {
		$message = new Message( $key );

		$params = func_get_args();
		array_shift( $params );
		if ( $params ) {
			call_user_func_array( [ $message, 'params' ], $params );
		}

		return $message->inLanguage( $this->language );
	}

}

