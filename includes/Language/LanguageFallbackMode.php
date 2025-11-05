<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language;

/**
 * @since 1.46
 * @ingroup Language
 */
enum LanguageFallbackMode: int {

	/**
	 * Return a fallback chain for messages in getAll
	 */
	case MESSAGES = 0;

	/**
	 * Return a strict fallback chain in getAll
	 */
	case STRICT = 1;

}
