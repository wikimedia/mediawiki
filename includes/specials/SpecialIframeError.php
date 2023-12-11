<?php

namespace MediaWiki\Specials;

use MediaWiki\Output\IframeSandbox;
use MediaWiki\SpecialPage\UnlistedSpecialPage;

/**
 * A special page showing an error message for browsers which do not
 * support the `srcdoc` option to `<iframe>`. Used by IframeSandbox.
 *
 * @ingroup SpecialPage
 * @see IframeSandbox
 */
class SpecialIframeError extends UnlistedSpecialPage {

	public const NAME = 'IframeError';

	public function __construct() {
		parent::__construct( self::NAME );
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$out = $this->getOutput();
		$out->setPreventClickjacking( false );
		$out->disallowUserJs();
		$out->setArticleBodyOnly( true );
		$out->setPageTitle( $this->msg( 'iframeerror-title' ) );
		$out->addWikiMsg( 'iframeerror-message' );
	}
}
