<?php

/**
 * Allows custom data specific to HTMLFormField to be set for OOjs UI forms. A matching JS widget
 * (defined in htmlform.Element.js) picks up the extra config when constructed using OO.ui.infuse().
 *
 * Currently only supports passing 'hide-if' data.
 */
trait HTMLFormElement {

	protected $hideIf = null;

	public function initializeHTMLFormElement( array $config = [] ) {
		// Properties
		$this->hideIf = isset( $config['hideIf'] ) ? $config['hideIf'] : null;

		// Initialization
		if ( $this->hideIf ) {
			$this->addClasses( [ 'mw-htmlform-hide-if' ] );
		}
		$this->registerConfigCallback( function( &$config ) {
			if ( $this->hideIf !== null ) {
				$config['hideIf'] = $this->hideIf;
			}
		} );
	}
}

class HTMLFormFieldLayout extends OOUI\FieldLayout {
	use HTMLFormElement;

	public function __construct( $fieldWidget, array $config = [] ) {
		// Parent constructor
		parent::__construct( $fieldWidget, $config );
		// Traits
		$this->initializeHTMLFormElement( $config );
	}

	protected function getJavaScriptClassName() {
		return 'mw.htmlform.FieldLayout';
	}
}

class HTMLFormActionFieldLayout extends OOUI\ActionFieldLayout {
	use HTMLFormElement;

	public function __construct( $fieldWidget, $buttonWidget = false, array $config = [] ) {
		// Parent constructor
		parent::__construct( $fieldWidget, $buttonWidget, $config );
		// Traits
		$this->initializeHTMLFormElement( $config );
	}

	protected function getJavaScriptClassName() {
		return 'mw.htmlform.ActionFieldLayout';
	}
}
