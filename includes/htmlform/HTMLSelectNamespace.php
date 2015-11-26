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
			'value' => $value,
			'name' => $this->mName,
			'id' => $this->mID,
			'includeAllValue' => $this->mAllValue,
		) );
	}
}
