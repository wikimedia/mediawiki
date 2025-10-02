<?php
/**
 * HTML validation and correction
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

use MediaWiki\MediaWikiServices;

/**
 * Class to interact with and configure Remex tidy
 *
 * @ingroup Parser
 */
class MWTidy {
	/**
	 * Interface with Remex tidy.
	 * If tidy isn't able to correct the markup, the original will be
	 * returned in all its glory with a warning comment appended.
	 *
	 * @param string $text HTML input fragment. This should not contain a
	 *                     <body> or <html> tag.
	 * @return string Corrected HTML output
	 * @deprecated since 1.36; use MediaWikiServices::getTidy()->tidy() instead
	 */
	public static function tidy( $text ) {
		return MediaWikiServices::getInstance()->getTidy()->tidy( $text );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( MWTidy::class, 'MWTidy' );
