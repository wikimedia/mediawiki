<?php

/**
 * Text field for selecting a value from a large list of possible values, with
 * auto-completion and optionally with a select dropdown for selecting common
 * options.
 *
 * If one of 'options-messages', 'options', or 'options-message' is provided
 * and non-empty, the select dropdown will be shown. An 'other' key will be
 * appended using message 'htmlform-selectorother-other' if not already
 * present.
 *
 * Besides the parameters recognized by HTMLTextField, the following are
 * recognized:
 *   options-messages - As for HTMLSelectField
 *   options - As for HTMLSelectField
 *   options-message - As for HTMLSelectField
 *   autocomplete - Associative array mapping display text to values.
 *   autocomplete-messages - Like autocomplete, but keys are message names.
 *   require-match - Boolean, if true the value must be in the options or the
 *     autocomplete.
 *   other-message - Message to use instead of htmlform-selectorother-other for
 *      the 'other' message.
 *   other - Raw text to use for the 'other' message
 *
 */
class HTMLAutoCompleteSelectField extends HTMLTextField {
	protected $autocomplete = array();

	function __construct( $params ) {
		$params += array(
			'require-match' => false,
		);

		parent::__construct( $params );

		if ( array_key_exists( 'autocomplete-messages', $this->mParams ) ) {
			foreach ( $this->mParams['autocomplete-messages'] as $key => $value ) {
				$key = $this->msg( $key )->plain();
				$this->autocomplete[$key] = strval( $value );
			}
		} elseif ( array_key_exists( 'autocomplete', $this->mParams ) ) {
			foreach ( $this->mParams['autocomplete'] as $key => $value ) {
				$this->autocomplete[$key] = strval( $value );
			}
		}
		if ( !is_array( $this->autocomplete ) || !$this->autocomplete ) {
			throw new MWException( 'HTMLAutoCompleteSelectField called without any autocompletions' );
		}

		$this->getOptions();
		if ( $this->mOptions && !in_array( 'other', $this->mOptions, true ) ) {
			if ( isset( $params['other-message'] ) ) {
				$msg = wfMessage( $params['other-message'] )->text();
			} elseif ( isset( $params['other'] ) ) {
				$msg = $params['other'];
			} else {
				$msg = wfMessage( 'htmlform-selectorother-other' )->text();
			}
			$this->mOptions[$msg] = 'other';
		}
	}

	function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {
			$val = $request->getText( $this->mName . '-select', 'other' );

			if ( $val === 'other' ) {
				$val = $request->getText( $this->mName );
				if ( isset( $this->autocomplete[$val] ) ) {
					$val = $this->autocomplete[$val];
				}
			}

			return $val;
		} else {
			return $this->getDefault();
		}
	}

	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		$validOptions = HTMLFormField::flattenOptions( $this->getOptions() );

		if ( in_array( strval( $value ), $validOptions, true ) ) {
			return true;
		} elseif ( in_array( strval( $value ), $this->autocomplete, true ) ) {
			return true;
		} elseif ( $this->mParams['require-match'] ) {
			return $this->msg( 'htmlform-select-badoption' )->parse();
		}

		return true;
	}

	// FIXME Ewww, this shouldn't be adding any attributes not requested in $list :(
	public function getAttributes( array $list, array $mappings = null ) {
		$attribs = array(
			'type' => 'text',
			'data-autocomplete' => FormatJson::encode( array_keys( $this->autocomplete ) ),
		) + parent::getAttributes( $list, $mappings );

		if ( $this->getOptions() ) {
			$attribs['data-hide-if'] = FormatJson::encode(
				array( '!==', $this->mName . '-select', 'other' )
			);
		}

		return $attribs;
	}

	function getInputHTML( $value ) {
		$oldClass = $this->mClass;
		$this->mClass = (array)$this->mClass;

		$valInSelect = false;
		$ret = '';

		if ( $this->getOptions() ) {
			if ( $value !== false ) {
				$value = strval( $value );
				$valInSelect = in_array(
					$value, HTMLFormField::flattenOptions( $this->getOptions() ), true
				);
			}

			$selected = $valInSelect ? $value : 'other';
			$select = new XmlSelect( $this->mName . '-select', $this->mID . '-select', $selected );
			$select->addOptions( $this->getOptions() );
			$select->setAttribute( 'class', 'mw-htmlform-select-or-other' );

			if ( !empty( $this->mParams['disabled'] ) ) {
				$select->setAttribute( 'disabled', 'disabled' );
			}

			if ( isset( $this->mParams['tabindex'] ) ) {
				$select->setAttribute( 'tabindex', $this->mParams['tabindex'] );
			}

			$ret = $select->getHTML() . "<br />\n";

			$this->mClass[] = 'mw-htmlform-hide-if';
		}

		if ( $valInSelect ) {
			$value = '';
		} else {
			$key = array_search( strval( $value ), $this->autocomplete, true );
			if ( $key !== false ) {
				$value = $key;
			}
		}

		$this->mClass[] = 'mw-htmlform-autocomplete';
		$ret .= parent::getInputHTML( $valInSelect ? '' : $value );
		$this->mClass = $oldClass;

		return $ret;
	}

	/**
	 * Get the OOUI version of this input.
	 * @param string $value
	 * @return false
	 */
	function getInputOOUI( $value ) {
		// To be implemented, for now override the function from HTMLTextField
		return false;
	}
}
