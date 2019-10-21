<?php

class HTMLFormActionFieldLayout extends OOUI\ActionFieldLayout {
	use HTMLFormElement;

	public function __construct( $fieldWidget, $buttonWidget = false, array $config = [] ) {
		parent::__construct( $fieldWidget, $buttonWidget, $config );

		// Traits
		$this->initializeHTMLFormElement( $config );
	}

	protected function getJavaScriptClassName() {
		return 'mw.htmlform.ActionFieldLayout';
	}
}
