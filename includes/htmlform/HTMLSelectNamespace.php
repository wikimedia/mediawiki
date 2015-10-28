<?php
/**
 * Wrapper for Html::namespaceSelector to use in HTMLForm
 */
class HTMLSelectNamespace extends HTMLFormField {
	public function __construct( $params ) {
		parent::__construct( $params );

		if ( isset( $params['all'] ) ) {
			$this->mAllValue = $params['all'];
		} elseif ( is_null( $params['all'] ) ) {
			$this->mAllValue = null;
		} else {
			$this->mAllValue = 'all';
		}

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
