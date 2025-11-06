<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Title;

/**
 * A parser that translates page titles into ForeignTitle objects.
 */
interface ForeignTitleFactory {
	/**
	 * Create a ForeignTitle object.
	 *
	 * Based on the page title and optionally the namespace ID, of a page on a foreign wiki.
	 * These values could be, for example, the `<title>` and `<ns>` attributes found in an
	 * XML dump.
	 *
	 * @param string $title The page title
	 * @param int|null $ns The namespace ID, or null if this data is not available
	 * @return ForeignTitle
	 */
	public function createForeignTitle( $title, $ns = null );
}

/** @deprecated class alias since 1.41 */
class_alias( ForeignTitleFactory::class, 'ForeignTitleFactory' );
