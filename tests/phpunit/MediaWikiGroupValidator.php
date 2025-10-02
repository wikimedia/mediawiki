<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Testing
 */

/**
 * Trait that provides methods to check if group annotations are valid.
 */
trait MediaWikiGroupValidator {

	/**
	 * @return bool
	 * @since 1.34
	 */
	private static function isTestInDatabaseGroup() {
		// If the test class says it belongs to the Database group, it needs the database.
		// NOTE: This ONLY checks for the group in the class level doc comment.
		$rc = new ReflectionClass( static::class );
		return (bool)preg_match( '/@group +Database\b/m', $rc->getDocComment() );
	}
}
