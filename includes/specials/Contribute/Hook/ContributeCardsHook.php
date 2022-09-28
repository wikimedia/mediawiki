<?php

namespace MediaWiki\Specials\Contribute\Hook;

interface ContributeCardsHook {
	/**
	 * This hook is called before processing the list of cards
	 * to display on the contribute page
	 *
	 * @since 1.40
	 *
	 * @param array &$cards List of contribute cards data
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContributeCards( array &$cards );
}
