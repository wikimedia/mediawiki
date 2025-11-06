<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Title;

/**
 * Represents an object that can convert page titles on a foreign wiki
 * (ForeignTitle objects) into page titles on the local wiki (Title objects).
 */
interface ImportTitleFactory {
	/**
	 * Determines which local title best corresponds to the given foreign title.
	 * If such a title can't be found or would be locally invalid, null is
	 * returned.
	 *
	 * @param ForeignTitle $foreignTitle The ForeignTitle to convert
	 * @return Title|null
	 */
	public function createTitleFromForeignTitle( ForeignTitle $foreignTitle );
}

/** @deprecated class alias since 1.41 */
class_alias( ImportTitleFactory::class, 'ImportTitleFactory' );
