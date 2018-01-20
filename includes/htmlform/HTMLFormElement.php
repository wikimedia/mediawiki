<?php

/**
 * Allows custom data specific to HTMLFormField to be set for OOjs UI forms. A matching JS widget
 * (defined in htmlform.Element.js) picks up the extra config when constructed using OO.ui.infuse().
 *
 * Currently only supports passing 'hide-if' data.
 */
trait HTMLFormElement {

	protected $hideIf = null;
	protected $modules = null;

	public function initializeHTMLFormElement( array $config = [] ) {
		// Properties
		$this->hideIf = isset( $config['hideIf'] ) ? $config['hideIf'] : null;
		$this->modules = isset( $config['modules'] ) ? $config['modules'] : [];

		// Initialization
		if ( $this->hideIf ) {
			$this->addClasses( [ 'mw-htmlform-hide-if' ] );
		}
		if ( $this->modules ) {
			// JS code must be able to read this before infusing (before OOjs UI is even loaded),
			// so we put this in a separate attribute (not with the rest of the config).
			// And it's not needed anymore after infusing, so we don't put it in JS config at all.
			$this->setAttributes( [ 'data-mw-modules' => implode( ',', $this->modules ) ] );
		}
		$this->registerConfigCallback( function ( &$config ) {
			if ( $this->hideIf !== null ) {
				$config['hideIf'] = $this->hideIf;
			}
		} );
	}
}
