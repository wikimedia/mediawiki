<?php
/**
 * Wrapper for Html::namespaceSelector to use in HTMLForm
 */
class HTMLSelectNamespace extends HTMLFormField {
	public function __construct() {
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
		$namespaceOptions = Html::namespaceSelectorOptions( array( 'all' => $this->mAllValue ) );

		$options = array();
		foreach( $namespaceOptions as $id => $name ) {
			$options[] = array(
				'data' => (string)$id,
				'label' => $name,
			);
		};

		return new OOUI\DropdownInputWidget( array(
			'options' => $options,
			'value' => $value,
			'name' => $this->mName,
			'id' => $this->mID,
		) );
	}
}
