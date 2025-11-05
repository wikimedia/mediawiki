<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Gan Chinese
 *
 * This handles both Traditional and Simplified Chinese.
 * Right now, we only distinguish `gan_hans` and `gan_hant`.
 *
 * @ingroup Languages
 */
class LanguageGan extends LanguageZh {
	/** @inheritDoc */
	protected function getSearchIndexVariant() {
		return 'gan-hans';
	}
}
