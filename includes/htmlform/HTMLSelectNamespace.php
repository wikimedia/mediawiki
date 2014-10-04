<?php
/**
 * Wrapper for Html::namespaceSelector to use in HTMLForm
 */
class HTMLSelectNamespace extends HTMLFormField {
	function getInputHTML( $value ) {
		return Html::namespaceSelector(
			array(
				'selected' => $value,
				'all' => 'all'
			), array(
				'name' => $this->mName,
				'id' => $this->mID,
				'class' => 'namespaceselector',
			)
		);
	}
}
