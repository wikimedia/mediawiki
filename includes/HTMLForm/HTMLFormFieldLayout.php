<?php

namespace MediaWiki\HTMLForm;

/**
 * @stable to extend
 */
class HTMLFormFieldLayout extends \OOUI\FieldLayout {
	use \MediaWiki\HTMLForm\HTMLFormElement;

	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $fieldWidget, array $config = [] ) {
		parent::__construct( $fieldWidget, $config );

		// Traits
		$this->initializeHTMLFormElement( $config );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.htmlform.FieldLayout';
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLFormFieldLayout::class, 'HTMLFormFieldLayout' );
