<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\SpecialRedirectWithAction;
use SearchEngineFactory;

/**
 * Redirect from Special:Info/$1 to index.php?title=$1&action=info.
 *
 * @ingroup SpecialPage
 * @author DannyS712
 */
class SpecialPageInfo extends SpecialRedirectWithAction {

	public function __construct(
		SearchEngineFactory $searchEngineFactory
	) {
		parent::__construct( 'PageInfo', 'info', 'pageinfo', $searchEngineFactory );
	}

	// Messages, for grep:
	// specialpageinfo-page
	// specialpageinfo-submit
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialPageInfo::class, 'SpecialPageInfo' );
