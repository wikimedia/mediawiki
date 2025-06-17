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
enum ParserOutputStringSets: string {

	// These flags are currently stored as ParserOutput properties

	/**
	 * ResourceLoader modules to load.
	 * @see \MediaWiki\Output\OutputPage::addModules
	 * @see ParserOutput::addModules
	 * @see ParserOutput::getModules
	 */
	case MODULE = 'mw-Module';

	/**
	 * Style-only ResourceLoader modules to load.
	 * @see \MediaWiki\Output\OutputPage::addModuleStyles
	 * @see ParserOutput::addModuleStyles
	 * @see ParserOutput::getModuleStyles
	 */
	case MODULE_STYLE = 'mw-ModuleStyle';

	/**
	 * Extra values for the Content-Security-Policy default-src
	 * directive.
	 * @see ParserOutput::addExtraCSPDefaultSrc
	 * @see ParserOutput::getExtraCSPDefaultSrcs
	 */
	case EXTRA_CSP_DEFAULT_SRC = 'mw-ExtraCSPDefaultSrc';

	/**
	 * Extra values for the Content-Security-Policy script-src
	 * directive.
	 * @see ParserOutput::addExtraCSPScriptSrc
	 * @see ParserOutput::getExtraCSPScriptSrcs
	 */
	case EXTRA_CSP_SCRIPT_SRC = 'mw-ExtraCspScriptSrc';

	/**
	 * Extra values for the Content-Security-Policy style-src
	 * directive.
	 * @see ParserOutput::addExtraCSPStyleSrc
	 * @see ParserOutput::getExtraCSPStyleSrcs
	 */
	case EXTRA_CSP_STYLE_SRC = 'mw-ExtraCspStyleSrc';

	/**
	 * Return the ParserOutputStringSets, as an array of string flag values.
	 * @return list<string>
	 */
	public static function values(): array {
		return array_column( self::cases(), 'value' );
	}
}
