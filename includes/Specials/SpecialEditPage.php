<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\SpecialRedirectWithAction;
use SearchEngineFactory;

/**
 * Redirect from Special:Edit/$1 to index.php?title=$1&action=edit.
 *
 * @ingroup SpecialPage
 * @author DannyS712
 */
class SpecialEditPage extends SpecialRedirectWithAction {

	public function __construct(
		SearchEngineFactory $searchEngineFactory
	) {
		parent::__construct( 'EditPage', 'edit', 'editpage', $searchEngineFactory );
	}

	// Messages, for grep:
	// specialeditpage-page
	// specialeditpage-submit
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialEditPage::class, 'SpecialEditPage' );
