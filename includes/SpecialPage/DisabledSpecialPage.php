<?php
/**
 * Special page for replacing manually disabled special pages
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\SpecialPage;

use Closure;
use MediaWiki\Html\Html;
use MediaWiki\Message\Message;

/**
 * This class is a drop-in replacement for other special pages that need to be manually
 * disabled. To use it, just put something like
 *
 *     $wgSpecialPages['Name'] = DisabledSpecialPage::getCallback( 'Name', 'message' );
 *
 * in the local configuration (where 'Name' is the canonical name of the special page
 * to be disabled, and 'message' is a message key for explaining the reason for disabling).
 *
 * @since 1.33
 */
class DisabledSpecialPage extends UnlistedSpecialPage {

	/** @var Message|string */
	protected $errorMessage;

	/**
	 * Create a callback suitable for use in $wgSpecialPages.
	 * @param string $name Canonical name of the special page that's being replaced.
	 * @param Message|string|null $errorMessage Error message to show when users try to use the page.
	 * @return Closure
	 */
	public static function getCallback( $name, $errorMessage = null ) {
		return static function () use ( $name, $errorMessage ) {
			return new DisabledSpecialPage( $name, $errorMessage );
		};
	}

	/**
	 * @param string $name Canonical name of the special page that's being replaced.
	 * @param Message|string|null $errorMessage Error message to show when users try to use the page.
	 */
	public function __construct( $name, $errorMessage = null ) {
		parent::__construct( $name );
		$this->errorMessage = $errorMessage ?: 'disabledspecialpage-disabled';
	}

	/** @inheritDoc */
	public function execute( $subPage ) {
		$this->setHeaders();
		$this->outputHeader();

		$this->getOutput()->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
		$this->getOutput()->addHTML( Html::errorBox(
			$this->msg( $this->errorMessage )->parse()
		) );
	}

}

/** @deprecated class alias since 1.41 */
class_alias( DisabledSpecialPage::class, 'DisabledSpecialPage' );
