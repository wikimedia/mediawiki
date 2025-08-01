<?php
/**
 * Formatter for changelang log entries.
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
 * @author Kunal Grover
 * @license GPL-2.0-or-later
 * @since 1.24
 */

namespace MediaWiki\Logging;

use MediaWiki\Languages\LanguageNameUtils;

/**
 * This class formats language change log entries.
 *
 * @since 1.24
 */
class PageLangLogFormatter extends LogFormatter {
	private LanguageNameUtils $languageNameUtils;

	public function __construct(
		LogEntry $entry,
		LanguageNameUtils $languageNameUtils
	) {
		parent::__construct( $entry );
		$this->languageNameUtils = $languageNameUtils;
	}

	/** @inheritDoc */
	protected function getMessageParameters() {
		// Get the user language for displaying language names
		$userLang = $this->context->getLanguage()->getCode();
		$params = parent::getMessageParameters();

		// Get the language codes from log
		$oldLang = $params[3];
		$kOld = strrpos( $oldLang, '[' );
		if ( $kOld ) {
			$oldLang = substr( $oldLang, 0, $kOld );
		}

		$newLang = $params[4];
		$kNew = strrpos( $newLang, '[' );
		if ( $kNew ) {
			$newLang = substr( $newLang, 0, $kNew );
		}

		// Convert language codes to names in user language
		$logOld = $this->languageNameUtils->getLanguageName( $oldLang, $userLang )
			. ' (' . $oldLang . ')';
		$logNew = $this->languageNameUtils->getLanguageName( $newLang, $userLang )
			. ' (' . $newLang . ')';

		// Add the default message to languages if required
		$params[3] = !$kOld ? $logOld : $logOld . ' [' . $this->msg( 'default' ) . ']';
		$params[4] = !$kNew ? $logNew : $logNew . ' [' . $this->msg( 'default' ) . ']';
		return $params;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( PageLangLogFormatter::class, 'PageLangLogFormatter' );
