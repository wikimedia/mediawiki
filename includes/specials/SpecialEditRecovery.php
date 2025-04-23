<?php

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\Options\UserOptionsLookup;

/**
 * @ingroup SpecialPage
 */
class SpecialEditRecovery extends SpecialPage {

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	public function __construct( UserOptionsLookup $userOptionsLookup ) {
		parent::__construct( 'EditRecovery' );
		$this->userOptionsLookup = $userOptionsLookup;
	}

	protected function getGroupName() {
		return 'changes';
	}

	/**
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		parent::execute( $subPage );
		// Always add the help link, even for the error pages.
		$this->addHelpLink( 'Help:Edit_Recovery' );

		// Check that the user preference is enabled (the user is not necessarily logged in).
		if ( !$this->userOptionsLookup->getOption( $this->getUser(), 'editrecovery' ) ) {
			if ( !$this->getUser()->isNamed() ) {
				// Pref is not enabled, and they aren't logged in.
				$this->getOutput()->showErrorPage( 'editrecovery', 'edit-recovery-special-user-unnamed' );
			} else {
				// Pref is not enabled, but they are logged in so can enable it themselves.
				$this->getOutput()->showErrorPage( 'editrecovery', 'edit-recovery-special-user-not-enabled' );
			}
			return;
		}

		$this->getOutput()->addModuleStyles( 'mediawiki.special.editrecovery.styles' );
		$this->getOutput()->addModules( 'mediawiki.special.editrecovery' );
		$this->getOutput()->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
		$noJs = Html::errorBox(
			$this->msg( 'edit-recovery-nojs-placeholder' )->parse(),
			'',
			'mw-EditRecovery-special-nojs-notice'
		);
		$placeholder = Html::rawElement( 'div', [ 'class' => 'mw-EditRecovery-special' ], $noJs );
		$this->getOutput()->addHTML( $placeholder );
	}
}
