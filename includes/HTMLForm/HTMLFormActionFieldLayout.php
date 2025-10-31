<?php

namespace MediaWiki\HTMLForm;

/**
 * @stable to extend
 */
class HTMLFormActionFieldLayout extends \OOUI\ActionFieldLayout {
	use HTMLFormElement;

	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $fieldWidget, $buttonWidget = false, array $config = [] ) {
		parent::__construct( $fieldWidget, $buttonWidget, $config );

		// Traits
		$this->initializeHTMLFormElement( $config );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.htmlform.ActionFieldLayout';
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLFormActionFieldLayout::class, 'HTMLFormActionFieldLayout' );
