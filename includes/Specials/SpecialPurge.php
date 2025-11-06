<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\SpecialRedirectWithAction;
use SearchEngineFactory;

/**
 * Redirect from Special:Purge/$1 to index.php?title=$1&action=purge.
 *
 * @ingroup SpecialPage
 * @author DannyS712
 */
class SpecialPurge extends SpecialRedirectWithAction {

	public function __construct(
		SearchEngineFactory $searchEngineFactory
	) {
		parent::__construct( 'Purge', 'purge', 'purge', $searchEngineFactory );
	}

	// Messages, for grep:
	// specialpurge-page
	// specialpurge-submit
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialPurge::class, 'SpecialPurge' );
