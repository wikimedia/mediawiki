<?php

/**
 * Registry of flags used with ParserOutput::{get,append}OutputString()
 * within MediaWiki core.
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
 * Registry of string sets used with ParserOutput::{get,append}OutputString()
 * within MediaWiki core.
 *
 * All string sets used should be defined in this class.
 *
 * @package MediaWiki\Parser
 */
class ParserOutputStringSets {

	// These flags are currently stored as ParserOutput properties

	/**
	 * @var string ResourceLoader modules to load.
	 * @see \MediaWiki\Output\OutputPage::addModules
	 * @see ParserOutput::addModules
	 * @see ParserOutput::getModules
	 */
	public const MODULE = 'mw-Module';

	/**
	 * @var string Style-only ResourceLoader modules to load.
	 * @see \MediaWiki\Output\OutputPage::addModuleStyles
	 * @see ParserOutput::addModuleStyles
	 * @see ParserOutput::getModuleStyles
	 */
	public const MODULE_STYLE = 'mw-ModuleStyle';

	/**
	 * @var string Extra values for the Content-Security-Policy default-src
	 *  directive.
	 * @see ParserOutput::addExtraCSPDefaultSrc
	 * @see ParserOutput::getExtraCSPDefaultSrcs
	 */
	public const EXTRA_CSP_DEFAULT_SRC = 'mw-ExtraCSPDefaultSrc';

	/**
	 * @var string Extra values for the Content-Security-Policy script-src
	 *  directive.
	 * @see ParserOutput::addExtraCSPScriptSrc
	 * @see ParserOutput::getExtraCSPScriptSrcs
	 */
	public const EXTRA_CSP_SCRIPT_SRC = 'mw-ExtraCspScriptSrc';

	/**
	 * @var string Extra values for the Content-Security-Policy style-src
	 *  directive.
	 * @see ParserOutput::addExtraCSPStyleSrc
	 * @see ParserOutput::getExtraCSPStyleSrcs
	 */
	public const EXTRA_CSP_STYLE_SRC = 'mw-ExtraCspStyleSrc';

	public static function cases(): array {
		return [
			self::MODULE,
			self::MODULE_STYLE,
			self::EXTRA_CSP_DEFAULT_SRC,
			self::EXTRA_CSP_SCRIPT_SRC,
			self::EXTRA_CSP_STYLE_SRC,
		];
	}
}
