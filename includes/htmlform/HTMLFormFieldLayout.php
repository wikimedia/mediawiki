<?php

class HTMLFormFieldLayout extends OOUI\FieldLayout {
	use HTMLFormElement;

	public function __construct( $fieldWidget, array $config = [] ) {
		parent::__construct( $fieldWidget, $config );

		// Traits
		$this->initializeHTMLFormElement( $config );
	}

	protected function getJavaScriptClassName() {
		return 'mw.htmlform.FieldLayout';
	}
}
