<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials\Contribute\Hook;

interface ContributeCardsHook {
	/**
	 * This hook is called before processing the list of cards
	 * to display on the contribute page
	 *
	 * @since 1.40
	 *
	 * @param array &$cards List of contribute cards data
	 * @return void
	 */
	public function onContributeCards( array &$cards ): void;
}
