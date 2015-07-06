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

	public function getInputOOUI( $value, $dataOnly = false ) {
		global $wgContLang;

		# the label is set directly in OOUI for this element, overwrite it here to avoid multiple labels
		$label = $this->getLabel();
		$this->mLabel = '';

		# get Namesapce options
		$allValue = ( isset( $this->mParams['all'] ) ? $this->mParams['all'] : '' );
		$nsoptions = Html::getNSOptions( array( 'all' => $allValue ) );
		$options = array();
		foreach( $nsoptions as $id => $name ) {
			# FIXME: Code duplication with Html::namespaceSelector
			if ( $id < NS_MAIN ) {
				continue;
			}
			if ( $id === NS_MAIN ) {
				# For other namespaces use the namespace prefix as label, but for
				# main we don't use "" but the user message describing it (e.g. "(Main)" or "(Article)")
				$name = wfMessage( 'blanknamespace' )->text();
			} elseif ( is_int( $id ) ) {
				$name = $wgContLang->convertNamespace( $id );
			}
			$options[] = array(
				'data' => (string)$id,
				'label' => $name,
			);
		};

		$field = new OOUI\FieldLayout(
			new OOUI\DropdownInputWidget( array(
				'options' => $options,
				'value' => $value,
				'name' => $this->mName,
				'id' => $this->mID,
				'classes' => array( 'namespaceselector' ),
			) ),
			array(
				'label' => $label,
			)
		);
		if ( $dataOnly ) {
			return $field;
		}
		return $field->toString();
	}

	/**
	 * Get label alignment when generating field for OOUI.
	 * @return string 'left', 'right', 'top' or 'inline'
	 */
	protected function getLabelAlignOOUI() {
		return 'inline';
	}
}
