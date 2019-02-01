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
 * Basic localized exception.
 *
 * @since 1.29
 * @ingroup Exception
 * @note Don't use this in a situation where MessageCache is not functional.
 */
class LocalizedException extends Exception implements ILocalizedException {
	/** @var string|array|MessageSpecifier */
	protected $messageSpec;

	/**
	 * @param string|array|MessageSpecifier $messageSpec See Message::newFromSpecifier
	 * @param int $code
	 * @param Exception|Throwable|null $previous The previous exception used for the exception
	 *  chaining.
	 */
	public function __construct( $messageSpec, $code = 0, $previous = null ) {
		$this->messageSpec = $messageSpec;

		// Exception->getMessage() should be in plain English, not localized.
		// So fetch the English version of the message, without local
		// customizations, and make a basic attempt to turn markup into text.
		$msg = $this->getMessageObject()->inLanguage( 'en' )->useDatabase( false )->text();
		$msg = preg_replace( '!</?(var|kbd|samp|code)>!', '"', $msg );
		$msg = Sanitizer::stripAllTags( $msg );
		parent::__construct( $msg, $code, $previous );
	}

	public function getMessageObject() {
		return Message::newFromSpecifier( $this->messageSpec );
	}
}
