<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

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
