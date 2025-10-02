<?php
/**
 * Class for generating clickable toggle links for a list of checkboxes.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Html;

use MediaWiki\Output\OutputPage;

/**
 * Class for generating clickable toggle links for a list of checkboxes.
 *
 * This is only supported on clients that have JavaScript enabled; it is hidden
 * for clients that have it disabled.
 *
 * @since 1.27
 */
class ListToggle {
	/** @var OutputPage */
	private $output;

	public function __construct( OutputPage $output ) {
		$this->output = $output;

		$output->addModules( 'mediawiki.checkboxtoggle' );
		$output->addModuleStyles( 'mediawiki.checkboxtoggle.styles' );
	}

	private function checkboxLink( string $checkboxType ): string {
		return Html::element(
			// CSS classes: mw-checkbox-all, mw-checkbox-none, mw-checkbox-invert
			'a', [ 'class' => 'mw-checkbox-' . $checkboxType, 'role' => 'button', 'tabindex' => 0 ],
			$this->output->msg( 'checkbox-' . $checkboxType )->text()
		);
	}

	/**
	 * @return string
	 */
	public function getHTML() {
		// Select: All, None, Invert
		$links = [
			$this->checkboxLink( 'all' ),
			$this->checkboxLink( 'none' ),
			$this->checkboxLink( 'invert' ),
		];

		return Html::rawElement(
			'div',
			[
				'class' => 'mw-checkbox-toggle-controls',
			],
			$this->output->msg( 'checkbox-select' )
				->rawParams( $this->output->getLanguage()->commaList( $links ) )->escaped()
		);
	}
}
