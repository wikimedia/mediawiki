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
}
