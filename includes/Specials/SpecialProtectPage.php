<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\SpecialRedirectWithAction;
use SearchEngineFactory;

/**
 * Redirect from Special:ProtectPage/$1 to index.php?title=$1&action=protect.
 *
 * @since 1.38
 * @ingroup SpecialPage
 * @author Zabe
 */
class SpecialProtectPage extends SpecialRedirectWithAction {

	public function __construct(
		SearchEngineFactory $searchEngineFactory
	) {
		parent::__construct( 'ProtectPage', 'protect', 'protectpage', $searchEngineFactory );
	}

	// Messages, for grep:
	// specialprotectpage-page
	// specialprotectpage-submit
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialProtectPage::class, 'SpecialProtectPage' );
