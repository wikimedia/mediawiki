<?php
/**
 * Creates a Html::namespaceSelector input field with a button assigned to the input field.
 */
class HTMLAdvancedSelectNamespace extends HTMLSelectNamespace {
	function getInputHTML( $value ) {
		$allValue = ( isset( $this->mParams['all'] ) ? $this->mParams['all'] : '' );

		return parent::getInputHTML( $value ) . '&#160;' .
			Xml::checkLabel(
				$this->msg( 'invert' )->text(),
				$this->mParams['invertname'],
				$this->mParams['invertid'],
				$this->mParams['invertdefault'],
				array( 'title' => $this->msg( 'tooltip-invert' )->text() )
			) . '&#160;' .
			Xml::checkLabel(
				$this->msg( 'namespace_association' )->text(),
				$this->mParams['associatedname'],
				$this->mParams['associatedid'],
				$this->mParams['associateddefault'],
				array( 'title' => $this->msg( 'tooltip-namespace_association' )->text() )
			);
	}

	/**
	 * Get the OOUI version of this field.
	 * @since 1.26
	 * @param string $value
	 * @return OOUI\FieldsetLayout A layout with all widget.
	 */
	public function getInputOOUI( $value ) {
		# There are more fields in this Widget as only one, so there are more values instead of only once.
		# Filter the data from the request before creating the form to set the correct values to the
		# Widget elements.
		# Get WebRequest only, if there is an instance of HTMLForm, use default data instead
		$invertdefault = false;
		$associateddefault = false;
		if ( $this->mParent instanceof HTMLForm ) {
			$request = $this->mParent->getRequest();
			if ( $request->getCheck( $this->mParams['associatedname'] ) ) {
				$associateddefault = true;
			}
			if ( $request->getCheck( $this->mParams['invertname'] ) ) {
				$invertdefault = true;
			}
		}

		// Unsupported: invertid, associatedid
		return new MediaWiki\Widget\NamespaceInputWidget( array(
			'nameNamespace' => $this->mName,
			'valueNamespace' => $value,
			'includeAllValue' => $this->mAllValue,
			'nameInvert' => $this->mParams['invertname'],
			'labelInvert' => $this->msg( 'invert' )->text(),
			'valueInvert' => $invertdefault,
			'nameAssociated' => $this->mParams['associatedname'],
			'valueAssociated' => $associateddefault,
			'labelAssociated' => $this->msg( 'namespace_association' )->text(),
		) );
	}
}
