<?php
/**
 * Classes for %MediaWiki extension translation.
 *
 * @file
 * @author Niklas Laxström
 * @copyright Copyright © 2008-2013, Niklas Laxström
 * @license GPL-2.0+
 */

/**
 * Class which handles special definition format for %MediaWiki extensions.
 */
class PremadeMediawikiExtensionGroups {
	/**
	 * @param string $def Absolute path to the definition file. See
	 *   tests/data/mediawiki-extensions.txt for example.
	 * @param string $path General prefix to the file locations without
	 *   the extension specific part. Should start with %GROUPROOT%/ or
	 *   otherwise export path will be wrong. The export path is
	 *   constructed by replacing %GROUPROOT%/ with target directory.
	 */
	public function __construct( $def, $path ) {
	}

	/**
	 * Whether to use the Configure extension to load extension home pages.
	 *
	 * @since 2012-03-22
	 * @param bool $value Whether Configure should be used.
	 */
	public function setUseConfigure( $value ) {
	}

	/**
	 * How to prefix message group ids.
	 *
	 * @since 2012-03-22
	 * @param string $value
	 */
	public function setGroupPrefix( $value ) {
	}

	/**
	 * Which namespace holds the messages.
	 *
	 * @since 2012-03-22
	 * @param int $value
	 */
	public function setNamespace( $value ) {
	}

    /**
     * Makes an group id from extension name
     * @param string $name
     * @return string
     */
	public static function foldId( $name ) {
	}

	/// Hook: TranslatePostInitGroups
	public function register( array &$list, array &$deps ) {
	}
}
