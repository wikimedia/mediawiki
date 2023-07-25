<?php

/**
 * Registry of flags used with ParserOutput::appendOutputString() within
 * MediaWiki core.
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
 * @since 1.41
 *
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

/**
 * Registry of string sets used with ParserOutput::{get,append}OutputString() within
 * MediaWiki core.
 *
 * All string sets used should be defined in this class.
 *
 * @package MediaWiki\Parser
 */
class ParserOutputStrings {

	public const EXTRA_CSP_DEFAULT_SRC = 'mw-ExtraCSPDefaultSrc';
	public const EXTRA_CSP_SCRIPT_SRC = 'mw-ExtraCspScriptSrc';
	public const EXTRA_CSP_STYLE_SRC = 'mw-ExtraCspStyleSrc';

	public static function cases(): array {
		return [
			self::EXTRA_CSP_DEFAULT_SRC,
			self::EXTRA_CSP_SCRIPT_SRC,
			self::EXTRA_CSP_STYLE_SRC,
		];
	}
}
