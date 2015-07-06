<?php
/**
 * Wrapper for Html::namespaceSelector to use in HTMLForm
 */
class HTMLSelectNamespace extends HTMLFormField {
	public function __construct( $params ) {
		parent::__construct( $params );
		$this->mAllValue = isset( $this->mParams['all'] ) ? $this->mParams['all'] : 'all';
	}

	function getInputHTML( $value ) {
		return Html::namespaceSelector(
			array(
				'selected' => $value,
				'all' => $this->mAllValue
			), array(
				'name' => $this->mName,
				'id' => $this->mID,
				'class' => 'namespaceselector',
			)
		);
	}

	public function getInputOOUI( $value ) {
		return new MediaWiki\Widget\NamespaceInputWidget( array(
			'id' => $this->mID,
			'nameNamespace' => $this->mName,
			'valueNamespace' => $value,
			'includeAllValue' => $this->mAllValue,
			// Disable additional checkboxes
			'nameInvert' => null,
			'nameAssociated' => null,
		) );
	}
}
