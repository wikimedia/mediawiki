<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\SpecialRedirectWithAction;
use SearchEngineFactory;

/**
 * Redirect from Special:DeletePage/$1 to index.php?title=$1&action=delete.
 *
 * @since 1.38
 * @ingroup SpecialPage
 * @author Zabe
 */
class SpecialDeletePage extends SpecialRedirectWithAction {

	public function __construct(
		SearchEngineFactory $searchEngineFactory
	) {
		parent::__construct( 'DeletePage', 'delete', 'deletepage', $searchEngineFactory );
	}

	// Messages, for grep:
	// specialdeletepage-page
	// specialdeletepage-submit
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialDeletePage::class, 'SpecialDeletePage' );
