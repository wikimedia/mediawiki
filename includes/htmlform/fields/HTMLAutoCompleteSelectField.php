<?php

namespace MediaWiki\HTMLForm\Field;

use InvalidArgumentException;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\Json\FormatJson;
use MediaWiki\Xml\XmlSelect;

/**
 * Text field for selecting a value from a large list of possible values, with
 * auto-completion and optionally with a select dropdown for selecting common
 * options.
 *
 * HTMLComboboxField implements most of the same functionality and should be
 * used instead, if possible.
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
 *   autocomplete-data - Associative array mapping display text to values.
 *   autocomplete-data-messages - Like autocomplete, but keys are message names.
 *   require-match - Boolean, if true the value must be in the options or the
 *     autocomplete.
 *   other-message - Message to use instead of htmlform-selectorother-other for
 *      the 'other' message.
 *   other - Raw text to use for the 'other' message
 *
 * @stable to extend
 */
class HTMLAutoCompleteSelectField extends HTMLTextField {
	/** @var string[] */
	protected $autocompleteData = [];

	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $params ) {
		$params += [
			'require-match' => false,
		];

		parent::__construct( $params );

		if ( array_key_exists( 'autocomplete-data-messages', $this->mParams ) ) {
			foreach ( $this->mParams['autocomplete-data-messages'] as $key => $value ) {
				$key = $this->msg( $key )->plain();
				$this->autocompleteData[$key] = strval( $value );
			}
		} elseif ( array_key_exists( 'autocomplete-data', $this->mParams ) ) {
			foreach ( $this->mParams['autocomplete-data'] as $key => $value ) {
				$this->autocompleteData[$key] = strval( $value );
			}
		}
		if ( !is_array( $this->autocompleteData ) || !$this->autocompleteData ) {
			throw new InvalidArgumentException( 'HTMLAutoCompleteSelectField called without any autocompletions' );
		}

		$this->getOptions();
		if ( $this->mOptions && !in_array( 'other', $this->mOptions, true ) ) {
			if ( isset( $params['other-message'] ) ) {
				$msg = $this->getMessage( $params['other-message'] )->text();
			} elseif ( isset( $params['other'] ) ) {
				$msg = $params['other'];
			} else {
				$msg = wfMessage( 'htmlform-selectorother-other' )->text();
			}
			$this->mOptions[$msg] = 'other';
		}
	}

	/** @inheritDoc */
	public function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {
			$val = $request->getText( $this->mName . '-select', 'other' );

			if ( $val === 'other' ) {
				$val = $request->getText( $this->mName );
				if ( isset( $this->autocompleteData[$val] ) ) {
					$val = $this->autocompleteData[$val];
				}
			}

			return $val;
		} else {
			return $this->getDefault();
		}
	}

	/** @inheritDoc */
	public function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		$validOptions = HTMLFormField::flattenOptions( $this->getOptions() ?: [] );

		if (
			in_array( strval( $value ), $validOptions, true ) ||
			in_array( strval( $value ), $this->autocompleteData, true )
		) {
			return true;
		} elseif ( $this->mParams['require-match'] ) {
			return $this->msg( 'htmlform-select-badoption' );
		}

		return true;
	}

	// FIXME Ewww, this shouldn't be adding any attributes not requested in $list :(
	public function getAttributes( array $list ) {
		$attribs = [
			'type' => 'text',
			'data-autocomplete' => FormatJson::encode( array_keys( $this->autocompleteData ) ),
		] + parent::getAttributes( $list );

		if ( $this->getOptions() ) {
			$attribs['data-cond-state'] = FormatJson::encode( [
				'hide' => [ '!==', $this->mName . '-select', 'other' ],
			] );
		}

		return $attribs;
	}

	/** @inheritDoc */
	public function getInputHTML( $value ) {
		$oldClass = $this->mClass;
		$classes = (array)$this->mClass;

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

			if ( !empty( $this->mParams['disabled'] ) ) {
				$select->setAttribute( 'disabled', 'disabled' );
			}

			if ( isset( $this->mParams['tabindex'] ) ) {
				$select->setAttribute( 'tabindex', $this->mParams['tabindex'] );
			}

			$ret = $select->getHTML() . "<br />\n";

			$classes[] = 'mw-htmlform-hide-if';
		}

		if ( $valInSelect ) {
			$value = '';
		} else {
			$key = array_search( strval( $value ), $this->autocompleteData, true );
			if ( $key !== false ) {
				$value = $key;
			}
		}

		$classes[] = 'mw-htmlform-autocomplete';
		$this->mClass = implode( ' ', $classes );
		$ret .= parent::getInputHTML( $valInSelect ? '' : $value );
		$this->mClass = $oldClass;

		return $ret;
	}

	/**
	 * Get the OOUI version of this input.
	 * @param string $value
	 * @return false
	 */
	public function getInputOOUI( $value ) {
		// To be implemented, for now override the function from HTMLTextField
		return false;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLAutoCompleteSelectField::class, 'HTMLAutoCompleteSelectField' );
