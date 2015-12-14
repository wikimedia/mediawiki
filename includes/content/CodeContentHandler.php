<?php
/**
 * Content handler for the pages with code, such as CSS, JavaScript, JSON.
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
 * @ingroup Content
 */

/**
 * Content handler for code content such as CSS, JavaScript, JSON, etc
 * @since 1.24
 * @ingroup Content
 */
abstract class CodeContentHandler extends TextContentHandler {

	/**
	 * Returns the English language, because code is English, and should be handled as such.
	 *
	 * @param Title $title
	 * @param Content $content
	 *
	 * @return Language
	 *
	 * @see ContentHandler::getPageLanguage()
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		return Language::factory( 'en' );
	}

	/**
	 * Returns the English language, because code is English, and should be handled as such.
	 *
	 * @param Title $title
	 * @param Content $content
	 *
	 * @return Language
	 *
	 * @see ContentHandler::getPageViewLanguage()
	 */
	public function getPageViewLanguage( Title $title, Content $content = null ) {
		return Language::factory( 'en' );
	}

	/**
	 * @return string
	 * @throws MWException
	 */
	protected function getContentClass() {
		throw new MWException( 'Subclass must override' );
	}

}
