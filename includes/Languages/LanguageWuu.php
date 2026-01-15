<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Languages;

/**
 * Wu
 *
 * This handles both Traditional Han script and Simplified Han script.
 * Right now, we only distinguish `wuu-hans` and `wuu-hant`.
 *
 * @ingroup Languages
 */
class LanguageWuu extends LanguageZh {
	/** @inheritDoc */
	protected function getSearchIndexVariant() {
		return 'wuu-hans';
	}
}

/** @deprecated class alias since 1.46 */
class_alias( LanguageWuu::class, 'LanguageWuu' );
