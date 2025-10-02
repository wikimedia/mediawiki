<?php

/**
 * Registry of flags used with ParserOutput::{get,append}OutputString()
 * within MediaWiki core.
 *
 * @license GPL-2.0-or-later
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
