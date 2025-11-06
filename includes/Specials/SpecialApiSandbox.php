<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\SpecialPage;

/**
 * Implements Special:ApiSandbox
 *
 * @since 1.27
 * @ingroup SpecialPage
 */
class SpecialApiSandbox extends SpecialPage {

	public function __construct() {
		parent::__construct( 'ApiSandbox' );
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$out = $this->getOutput();
		$this->addHelpLink( 'Help:ApiSandbox' );

		$out->addJsConfigVars(
			'apihighlimits',
			$this->getAuthority()->isAllowed( 'apihighlimits' )
		);
		$out->addModuleStyles( [
			'mediawiki.special',
			'mediawiki.hlist',
		] );
		$out->addModules( [
			'mediawiki.special.apisandbox',
			'mediawiki.apipretty',
		] );
		$out->wrapWikiMsg(
			"<div id='mw-apisandbox'><div class='mw-apisandbox-nojs error'>\n$1\n</div></div>",
			'apisandbox-jsonly'
		);
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'wiki';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialApiSandbox::class, 'SpecialApiSandbox' );
