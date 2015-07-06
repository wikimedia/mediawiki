<?php
/**
 * Creates a Html::namespaceSelector input field with a button assigned to the input field.
 */
class HTMLWatchlistSelectNamespace extends HTMLFormField {
	function getInputHTML( $value ) {
		$allValue = ( isset( $this->mParams['all'] ) ? $this->mParams['all'] : '' );

		return Html::namespaceSelector(
				array(
					'selected' => $value,
					'all' => $allValue,
					'label' => $this->msg( 'namespace' )->text()
				), array(
					'name' => $this->mName,
					'id' => $this->mID,
					'class' => 'namespaceselector',
				)
			) . '&#160;' .
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
			) . '&#160;' .
			Xml::submitButton( $this->msg( 'allpagessubmit' )->text() );
	}

	/**
	 * Get the OOUI version of this field.
	 * @since 1.26
	 * @param string $value
	 * @return OOUI\FieldsetLayout A layout with all widget.
	 */
	public function getInputOOUI( $value ) {
		global $wgContLang;

		$horizontalAlignmentWidget = new OOUI\Widget( array(
			'classes' => array( 'oo-ui-watchlist-widget' ),
		) );
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
				// For other namespaces use the namespace prefix as label, but for
				// main we don't use "" but the user message describing it (e.g. "(Main)" or "(Article)")
				$name = wfMessage( 'blanknamespace' )->text();
			} elseif ( is_int( $id ) ) {
				$name = $wgContLang->convertNamespace( $id );
			}
			$options[] = array(
				'data' => $id,
				'label' => $name,
			);
		};
		# Adding content after the fact does not play well with
		# infusability.  We should be using a proper Layout here.
		$horizontalAlignmentWidget->appendContent(
			new OOUI\FieldLayout(
				new OOUI\DropdownInputWidget( array(
					'options' => $options,
					'value' => $value,
					'name' => $this->mName,
					'id' => $this->mID,
					'classes' => array( 'namespaceselector' ),
				) ),
				array(
					'label' => $this->msg( 'namespace' )->text(),
				)
			),
			new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( array(
					'name' => $this->mParams['invertname'],
					'id' => $this->mParams['invertid'],
					'selected' => $invertdefault,
					'value' => 1,
				) ),
				array(
					'align' => 'inline',
					'label' => $this->msg( 'invert' )->text(),
				)
			),
			new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( array(
					'name' => $this->mParams['associatedname'],
					'id' => $this->mParams['associatedid'],
					'selected' => $associateddefault,
					'value' => 1,
				) ),
				array(
					'align' => 'inline',
					'label' => $this->msg( 'namespace_association' )->text(),
				)
			),
			new OOUI\ButtonInputWidget( array(
				'label' => $this->msg( 'allpagessubmit' )->text(),
				'type' => 'submit',
				'flags' => array( 'primary', 'progressive' ),
				'icon' => 'check',
			) )
		);

		# $horizontalAlignmentWidget is not infusable because
		# it manually added content after creation. If we embed it
		# in an infusable FieldLayout (through HTMLFormField), it will (recursively)
		# be made infusable. So protect the widget by wrapping it in a
		# FieldLayout which isn't infusable (by returning a string, instead of the field itself,
		# which will instruct HTMLFormField to wrap it into a HtmlSnippet Widget and a not
		# not infusable FieldLayout.
		return $horizontalAlignmentWidget->toString();
	}
}
