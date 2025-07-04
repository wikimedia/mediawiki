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

use MediaWiki\Language\Language;

/**
 * Dummy language that returns the message names.
 *
 * For all translated messages, this returns a special value handled in Message::format()
 * to display the message key (and fallback keys) and the parameters passed to the message.
 * This does not affect untranslated messages.
 *
 * NOTE: It returns a valid title, because there are some poorly written
 * extensions that assume the contents of some messages are valid.
 *
 * @ingroup Languages
 * @deprecated since 1.41. The overridden method is deprecated. The feature has been reimplemented
 *   in MessageCache. Callers doing "new LanguageQqx" should use
 *   $languageFactory->getLanguage( 'qqx' ) to get a Language object with its code set to qqx.
 */
class LanguageQqx extends Language {
	/** @inheritDoc */
	public function getMessage( $key ) {
		// Special value replaced in Message::format()
		return '($*)';
	}
}
