<?php
/**
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\SpecialPage;

/**
 * @ingroup SpecialPage
 */
class SpecialEditRecovery extends SpecialPage {

	public function __construct() {
		parent::__construct( 'EditRecovery' );
	}

	protected function getGroupName() {
		return 'changes';
	}

	/**
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		parent::execute( $subPage );
		$this->addHelpLink( 'Help:Edit_Recovery' );
		$this->getOutput()->addModuleStyles( 'mediawiki.special.editrecovery.styles' );
		$this->getOutput()->addModules( 'mediawiki.special.editrecovery' );
		$noJs = Html::element(
			'span',
			[ 'class' => 'error mw-EditRecovery-special-nojs-notice' ],
			$this->msg( 'edit-recovery-nojs-placeholder' )
		);
		$placeholder = Html::rawElement( 'div', [ 'class' => 'mw-EditRecovery-special' ], $noJs );
		$this->getOutput()->addHTML( $placeholder );
	}
}
