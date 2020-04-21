<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PlaceNewSectionHook {
	/**
	 * Override placement of new sections. Return false and put the
	 * merged text into $text to override the default behavior.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikipage WikiPage object
	 * @param ?mixed $oldtext the text of the article before editing
	 * @param ?mixed $subject subject of the new section
	 * @param ?mixed &$text text of the new section
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPlaceNewSection( $wikipage, $oldtext, $subject, &$text );
}
