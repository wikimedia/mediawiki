<?php

namespace MediaWiki\Content\Hook;

use WikiPage;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PlaceNewSectionHook {
	/**
	 * Use this hook to override placement of new sections.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikipage
	 * @param string $oldtext Text of the article before editing
	 * @param string $subject Subject of the new section
	 * @param string &$text Text of the new section
	 * @return bool|void True or no return value to continue, or false and put the
	 *   merged text into $text to override the default behavior
	 */
	public function onPlaceNewSection( $wikipage, $oldtext, $subject, &$text );
}
