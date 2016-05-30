<?php

/**
 * Multi-select field
 */
class HTMLMultiSelectField extends HTMLFormField implements HTMLNestedFilterable {
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( !is_array( $value ) ) {
			return false;
		}

		# If all options are valid, array_intersect of the valid options
		# and the provided options will return the provided options.
		$validOptions = HTMLFormField::flattenOptions( $this->getOptions() );

		$validValues = array_intersect( $value, $validOptions );
		if ( count( $validValues ) == count( $value ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' )->parse();
		}
	}

	function getInputHTML( $value ) {
		$value = HTMLFormField::forceToStringRecursive( $value );
		$html = $this->formatOptions( $this->getOptions(), $value );

		return $html;
	}

	function formatOptions( $options, $value ) {
		$html = '';

		$attribs = $this->getAttributes( [ 'disabled', 'tabindex' ] );

		foreach ( $options as $label => $info ) {
			if ( is_array( $info ) ) {
				$html .= Html::rawElement( 'h1', [], $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$thisAttribs = [
					'id' => "{$this->mID}-$info",
					'value' => $info,
				];
				$checked = in_array( $info, $value, true );

				$checkbox = $this->getOneCheckbox( $checked, $attribs + $thisAttribs, $label );

				$html .= ' ' . Html::rawElement(
					'div',
					[ 'class' => 'mw-htmlform-flatlist-item' ],
					$checkbox
				);
			}
		}

		return $html;
	}

	protected function getOneCheckbox( $checked, $attribs, $label ) {
		if ( $this->mParent instanceof OOUIHTMLForm ) {
			throw new MWException( 'HTMLMultiSelectField#getOneCheckbox() is not supported' );
		} else {
			$elementFunc = [ 'Html', $this->mOptionsLabelsNotFromMessage ? 'rawElement' : 'element' ];
			$checkbox =
				Xml::check( "{$this->mName}[]", $checked, $attribs ) .
				'&#160;' .
				call_user_func( $elementFunc,
					'label',
					[ 'for' => $attribs['id'] ],
					$label
				);
			if ( $this->mParent->getConfig()->get( 'UseMediaWikiUIEverywhere' ) ) {
				$checkbox = Html::openElement( 'div', [ 'class' => 'mw-ui-checkbox' ] ) .
					$checkbox .
					Html::closeElement( 'div' );
			}
			return $checkbox;
		}
	}

	/**
	 * Get the OOUI version of this field.
	 *
	 * @since 1.28
	 * @param string[] $value
	 * @return OOUI\CheckboxMultiselectInputWidget
	 */
	public function getInputOOUI( $value ) {
		$attr = $this->getTooltipAndAccessKey();
		$attr['id'] = $this->mID;
		$attr['name'] = "{$this->mName}[]";

		$attr['value'] = $value;
		$attr['options'] = $this->getOptionsOOUI();

		if ( $this->mOptionsLabelsNotFromMessage ) {
			foreach ( $attr['options'] as &$option ) {
				$option['label'] = new OOUI\HtmlSnippet( $option['label'] );
			}
		}

		$attr += OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( [ 'disabled', 'tabindex' ] )
		);

		if ( $this->mClass !== '' ) {
			$attr['classes'] = [ $this->mClass ];
		}

		return new OOUI\CheckboxMultiselectInputWidget( $attr );
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return string
	 */
	function loadDataFromRequest( $request ) {
		if ( $this->isSubmitAttempt( $request ) ) {
			// Checkboxes are just not added to the request arrays if they're not checked,
			// so it's perfectly possible for there not to be an entry at all
			return $request->getArray( $this->mName, [] );
		} else {
			// That's ok, the user has not yet submitted the form, so show the defaults
			return $this->getDefault();
		}
	}

	function getDefault() {
		if ( isset( $this->mDefault ) ) {
			return $this->mDefault;
		} else {
			return [];
		}
	}

	function filterDataForSubmit( $data ) {
		$data = HTMLFormField::forceToStringRecursive( $data );
		$options = HTMLFormField::flattenOptions( $this->getOptions() );

		$res = [];
		foreach ( $options as $opt ) {
			$res["$opt"] = in_array( $opt, $data, true );
		}

		return $res;
	}

	protected function needsLabel() {
		return false;
	}
}
