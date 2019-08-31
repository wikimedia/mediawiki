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

		if ( isset( $params['dropdown'] ) ) {
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
			$elementFunc = [ Html::class, $this->mOptionsLabelsNotFromMessage ? 'rawElement' : 'element' ];
			$checkbox =
				Xml::check( "{$this->mName}[]", $checked, $attribs ) .
				"\u{00A0}" .
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
	 * @throws MWException
	 */
	public function getOptionsOOUI() {
		// Sections make this difficult. See getInputOOUI().
		throw new MWException( 'HTMLMultiSelectField#getOptionsOOUI() is not supported' );
	}

	/**
	 * Get the OOUI version of this field.
	 *
	 * Returns OOUI\CheckboxMultiselectInputWidget for fields that only have one section,
	 * string otherwise.
	 *
	 * @since 1.28
	 * @param string[] $value
	 * @return string|OOUI\CheckboxMultiselectInputWidget
	 * @suppress PhanParamSignatureMismatch
	 */
	public function getInputOOUI( $value ) {
		$this->mParent->getOutput()->addModules( 'oojs-ui-widgets' );

		$hasSections = false;
		$optionsOouiSections = [];
		$options = $this->getOptions();
		// If the options are supposed to be split into sections, each section becomes a separate
		// CheckboxMultiselectInputWidget.
		foreach ( $options as $label => $section ) {
			if ( is_array( $section ) ) {
				$optionsOouiSections[ $label ] = Xml::listDropDownOptionsOoui( $section );
				unset( $options[$label] );
				$hasSections = true;
			}
		}
		// If anything remains in the array, they are sectionless options. Put them in a separate widget
		// at the beginning.
		if ( $options ) {
			$optionsOouiSections = array_merge(
				[ '' => Xml::listDropDownOptionsOoui( $options ) ],
				$optionsOouiSections
			);
		}

		$out = [];
		foreach ( $optionsOouiSections as $sectionLabel => $optionsOoui ) {
			$attr = [];
			$attr['name'] = "{$this->mName}[]";

			$attr['value'] = $value;
			$attr['options'] = $optionsOoui;

			foreach ( $attr['options'] as &$option ) {
				$option['disabled'] = in_array( $option['data'], $this->mParams['disabled-options'], true );
			}
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

			$widget = new OOUI\CheckboxMultiselectInputWidget( $attr );
			if ( $sectionLabel ) {
				$out[] = new OOUI\FieldsetLayout( [
					'items' => [ $widget ],
					'label' => new OOUI\HtmlSnippet( $sectionLabel ),
				] );
			} else {
				$out[] = $widget;
			}
		}

		if ( !$hasSections ) {
			// Directly return the only OOUI\CheckboxMultiselectInputWidget.
			// This allows it to be made infusable and later tweaked by JS code.
			return $out[ 0 ];
		}

		return implode( '', $out );
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return string|array
	 */
	public function loadDataFromRequest( $request ) {
		$fromRequest = $request->getArray( $this->mName, [] );
		// Fetch the value in either one of the two following case:
		// - we have a valid submit attempt (form was just submitted)
		// - we have a value (an URL manually built by the user, or GET form with no wpFormIdentifier)
		if ( $this->isSubmitAttempt( $request ) || $fromRequest ) {
			// Checkboxes are just not added to the request arrays if they're not checked,
			// so it's perfectly possible for there not to be an entry at all
			return $fromRequest;
		} else {
			// That's ok, the user has not yet submitted the form, so show the defaults
			return $this->getDefault();
		}
	}

	public function getDefault() {
		return $this->mDefault ?? [];
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
