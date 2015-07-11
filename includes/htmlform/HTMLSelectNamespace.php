<?php
/**
 * Wrapper for Html::namespaceSelector to use in HTMLForm
 */
class HTMLSelectNamespace extends HTMLFormField {
	function getInputHTML( $value ) {
		$allValue = ( isset( $this->mParams['all'] ) ? $this->mParams['all'] : 'all' );

		return Html::namespaceSelector(
			array(
				'selected' => $value,
				'all' => $allValue
			), array(
				'name' => $this->mName,
				'id' => $this->mID,
				'class' => 'namespaceselector',
			)
		);
	}

	public function getInputOOUI( $value ) {
		$allValue = ( isset( $this->mParams['all'] ) ? $this->mParams['all'] : 'all' );
		$namespaceOptions = Html::namespaceSelectorOptions( array( 'all' => $allValue ) );

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
