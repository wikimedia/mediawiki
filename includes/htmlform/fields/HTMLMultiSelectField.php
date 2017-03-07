<?php

/**
 * Multi-select field
 */
class HTMLMultiSelectField extends HTMLFormField implements HTMLNestedFilterable {
	/**
	 * @param array $params
	 *   In adition to the usual HTMLFormField parameters, this can take the following fields:
	 *   - dropdown: If given, the options will be displayed inside a dropdown with a text field that
	 *     can be used to filter them. This is desirable mostly for very long lists of options.
	 *     This only works for users with JavaScript support and falls back to the list of checkboxes.
	 *   - flatlist: If given, the options will be displayed on a single line (wrapping to following
	 *     lines if necessary), rather than each one on a line of its own. This is desirable mostly
	 *     for very short lists of concisely labelled options.
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		// If the disabled-options parameter is not provided, use an empty array
		if ( isset( $this->mParams['disabled-options'] ) === false ) {
			$this->mParams['disabled-options'] = [];
		}

		// For backwards compatibility, also handle the old way with 'cssclass' => 'mw-chosen'
		if ( isset( $params['dropdown'] ) || strpos( $this->mClass, 'mw-chosen' ) !== false ) {
			$this->mClass .= ' mw-htmlform-dropdown';
		}

		if ( isset( $params['flatlist'] ) ) {
			$this->mClass .= ' mw-htmlform-flatlist';
		}
	}

	public function validate( $value, $alldata ) {
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
			return $this->msg( 'htmlform-select-badoption' );
		}
	}

	public function getInputHTML( $value ) {
		if ( isset( $this->mParams['dropdown'] ) ) {
			$this->mParent->getOutput()->addModules( 'jquery.chosen' );
		}

		$value = HTMLFormField::forceToStringRecursive( $value );
		$html = $this->formatOptions( $this->getOptions(), $value );

		return $html;
	}

	public function formatOptions( $options, $value ) {
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
				if ( in_array( $info, $this->mParams['disabled-options'], true ) ) {
					$thisAttribs['disabled'] = 'disabled';
				}
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
	 * Get options and make them into arrays suitable for OOUI.
	 * @return array Options for inclusion in a select or whatever.
	 */
	public function getOptionsOOUI() {
		$options = parent::getOptionsOOUI();
		foreach ( $options as &$option ) {
			$option['disabled'] = in_array( $option['data'], $this->mParams['disabled-options'], true );
		}
		return $options;
	}

	/**
	 * Get the OOUI version of this field.
	 *
	 * @since 1.28
	 * @param string[] $value
	 * @return OOUI\CheckboxMultiselectInputWidget
	 */
	public function getInputOOUI( $value ) {
		$this->mParent->getOutput()->addModules( 'oojs-ui-widgets' );

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
	 * @return string|array
	 */
	public function loadDataFromRequest( $request ) {
		if ( $this->isSubmitAttempt( $request ) ) {
			// Checkboxes are just not added to the request arrays if they're not checked,
			// so it's perfectly possible for there not to be an entry at all
			return $request->getArray( $this->mName, [] );
		} else {
			// That's ok, the user has not yet submitted the form, so show the defaults
			return $this->getDefault();
		}
	}

	public function getDefault() {
		if ( isset( $this->mDefault ) ) {
			return $this->mDefault;
		} else {
			return [];
		}
	}

	public function filterDataForSubmit( $data ) {
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
