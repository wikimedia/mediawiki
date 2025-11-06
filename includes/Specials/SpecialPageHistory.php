<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\SpecialRedirectWithAction;
use SearchEngineFactory;

/**
 * Redirect from Special:History/$1 to index.php?title=$1&action=history.
 *
 * @ingroup SpecialPage
 * @author DannyS712
 */
class SpecialPageHistory extends SpecialRedirectWithAction {

	public function __construct(
		SearchEngineFactory $searchEngineFactory
	) {
		parent::__construct( 'PageHistory', 'history', 'pagehistory', $searchEngineFactory );
	}

	// Messages, for grep:
	// specialpagehistory-page
	// specialpagehistory-submit
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialPageHistory::class, 'SpecialPageHistory' );
