<?php
/**
 * Thrown when incompatible types are compared.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup DifferenceEngine
 */

namespace MediaWiki\Diff;

use MediaWiki\Content\ContentHandler;
use MediaWiki\Exception\LocalizedException;

/**
 * Exception thrown when trying to render a diff between two content types
 * which cannot be compared (this is typically the case for all content types
 * unless both of them are some variant of TextContent). SlotDiffRenderer and
 * DifferenceEngine classes should generally throw this exception when handed
 * a content object they don't know how to diff against.
 *
 * @since 1.41
 */
class IncompatibleDiffTypesException extends LocalizedException {

	/**
	 * @param string $oldModel Content model of the "old" side of the diff
	 * @param string $newModel Content model of the "new" side of the diff
	 */
	public function __construct( $oldModel, $newModel ) {
		$oldName = ContentHandler::getLocalizedName( $oldModel );
		$newName = ContentHandler::getLocalizedName( $newModel );
		parent::__construct( wfMessage( 'diff-incompatible', $oldName, $newName ) );
	}

}

/** @deprecated class alias since 1.46 */
class_alias( IncompatibleDiffTypesException::class, 'IncompatibleDiffTypesException' );
