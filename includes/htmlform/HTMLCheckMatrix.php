<?php

/**
 * A checkbox matrix
 * Operates similarly to HTMLMultiSelectField, but instead of using an array of
 * options, uses an array of rows and an array of columns to dynamically
 * construct a matrix of options. The tags used to identify a particular cell
 * are of the form "columnName-rowName"
 *
 * Options:
 *   - columns
 *     - Required list of columns in the matrix.
 *   - rows
 *     - Required list of rows in the matrix.
 *   - force-options-on
 *     - Accepts array of column-row tags to be displayed as enabled but unavailable to change
 *   - force-options-off
 *     - Accepts array of column-row tags to be displayed as disabled but unavailable to change.
 *   - tooltips
 *     - Optional array mapping row label to tooltip content
 *   - tooltip-class
 *     - Optional CSS class used on tooltip container span. Defaults to mw-icon-question.
 */
class HTMLCheckMatrix extends HTMLFormField implements HTMLNestedFilterable {
	static private $requiredParams = array(
		// Required by underlying HTMLFormField
		'fieldname',
		// Required by HTMLCheckMatrix
		'rows',
		'columns'
	);

	public function __construct( $params ) {
		$missing = array_diff( self::$requiredParams, array_keys( $params ) );
		if ( $missing ) {
			throw new HTMLFormFieldRequiredOptionsException( $this, $missing );
		}
		parent::__construct( $params );
	}

	function validate( $value, $alldata ) {
		$rows = $this->mParams['rows'];
		$columns = $this->mParams['columns'];

		// Make sure user-defined validation callback is run
		$p = parent::validate( $value, $alldata );
		if ( $p !== true ) {
			return $p;
		}

		// Make sure submitted value is an array
		if ( !is_array( $value ) ) {
			return false;
		}

		// If all options are valid, array_intersect of the valid options
		// and the provided options will return the provided options.
		$validOptions = array();
		foreach ( $rows as $rowTag ) {
			foreach ( $columns as $columnTag ) {
				$validOptions[] = $columnTag . '-' . $rowTag;
			}
		}
		$validValues = array_intersect( $value, $validOptions );
		if ( count( $validValues ) == count( $value ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' )->parse();
		}
	}

	/**
	 * Build a table containing a matrix of checkbox options.
	 * The value of each option is a combination of the row tag and column tag.
	 * mParams['rows'] is an array with row labels as keys and row tags as values.
	 * mParams['columns'] is an array with column labels as keys and column tags as values.
	 *
	 * @param array $value Array of the options that should be checked
	 *
	 * @return string
	 */
	function getInputHTML( $value ) {
		$html = '';
		$tableContents = '';
		$rows = $this->mParams['rows'];
		$columns = $this->mParams['columns'];

		$mappings = array();

		if ( $this->mParent instanceof OOUIHTMLForm ) {
			$mappings['tabindex'] = 'tabIndex';
		}

		$attribs = $this->getAttributes( array( 'disabled', 'tabindex' ), $mappings );

		// Build the column headers
		$headerContents = Html::rawElement( 'td', array(), '&#160;' );
		foreach ( $columns as $columnLabel => $columnTag ) {
			$headerContents .= Html::rawElement( 'td', array(), $columnLabel );
		}
		$tableContents .= Html::rawElement( 'tr', array(), "\n$headerContents\n" );

		$tooltipClass = 'mw-icon-question';
		if ( isset( $this->mParams['tooltip-class'] ) ) {
			$tooltipClass = $this->mParams['tooltip-class'];
		}

		// Build the options matrix
		foreach ( $rows as $rowLabel => $rowTag ) {
			// Append tooltip if configured
			if ( isset( $this->mParams['tooltips'][$rowLabel] ) ) {
				$tooltipAttribs = array(
					'class' => "mw-htmlform-tooltip $tooltipClass",
					'title' => $this->mParams['tooltips'][$rowLabel],
				);
				$rowLabel .= ' ' . Html::element( 'span', $tooltipAttribs, '' );
			}
			$rowContents = Html::rawElement( 'td', array(), $rowLabel );
			foreach ( $columns as $columnTag ) {
				$thisTag = "$columnTag-$rowTag";
				// Construct the checkbox
				$thisAttribs = array(
					'id' => "{$this->mID}-$thisTag",
					'value' => $thisTag,
				);
				$checked = in_array( $thisTag, (array)$value, true );
				if ( $this->isTagForcedOff( $thisTag ) ) {
					$checked = false;
					$thisAttribs['disabled'] = 1;
				} elseif ( $this->isTagForcedOn( $thisTag ) ) {
					$checked = true;
					$thisAttribs['disabled'] = 1;
				}

				$checkbox = $this->getOneCheckbox( $checked, $attribs + $thisAttribs );

				$rowContents .= Html::rawElement(
					'td',
					array(),
					$checkbox
				);
			}
			$tableContents .= Html::rawElement( 'tr', array(), "\n$rowContents\n" );
		}

		// Put it all in a table
		$html .= Html::rawElement( 'table',
				array( 'class' => 'mw-htmlform-matrix' ),
				Html::rawElement( 'tbody', array(), "\n$tableContents\n" ) ) . "\n";

		return $html;
	}

	protected function getOneCheckbox( $checked, $attribs ) {
		if ( $this->mParent instanceof OOUIHTMLForm ) {
			return new OOUI\CheckboxInputWidget( array(
				'name' => "{$this->mName}[]",
				'selected' => $checked,
				'value' => $attribs['value'],
			) + $attribs );
		} else {
			$checkbox = Xml::check( "{$this->mName}[]", $checked, $attribs );
			if ( $this->mParent->getConfig()->get( 'UseMediaWikiUIEverywhere' ) ) {
				$checkbox = Html::openElement( 'div', array( 'class' => 'mw-ui-checkbox' ) ) .
					$checkbox .
					Html::element( 'label', array( 'for' => $attribs['id'] ) ) .
					Html::closeElement( 'div' );
			}
			return $checkbox;
		}
	}

	protected function isTagForcedOff( $tag ) {
		return isset( $this->mParams['force-options-off'] )
			&& in_array( $tag, $this->mParams['force-options-off'] );
	}

	protected function isTagForcedOn( $tag ) {
		return isset( $this->mParams['force-options-on'] )
			&& in_array( $tag, $this->mParams['force-options-on'] );
	}

	/**
	 * Get the complete table row for the input, including help text,
	 * labels, and whatever.
	 * We override this function since the label should always be on a separate
	 * line above the options in the case of a checkbox matrix, i.e. it's always
	 * a "vertical-label".
	 *
	 * @param string $value The value to set the input to
	 *
	 * @return string Complete HTML table row
	 */
	function getTableRow( $value ) {
		list( $errors, $errorClass ) = $this->getErrorsAndErrorClass( $value );
		$inputHtml = $this->getInputHTML( $value );
		$fieldType = get_class( $this );
		$helptext = $this->getHelpTextHtmlTable( $this->getHelpText() );
		$cellAttributes = array( 'colspan' => 2 );

		$hideClass = '';
		$hideAttributes = array();
		if ( $this->mHideIf ) {
			$hideAttributes['data-hide-if'] = FormatJson::encode( $this->mHideIf );
			$hideClass = 'mw-htmlform-hide-if';
		}

		$label = $this->getLabelHtml( $cellAttributes );

		$field = Html::rawElement(
			'td',
			array( 'class' => 'mw-input' ) + $cellAttributes,
			$inputHtml . "\n$errors"
		);

		$html = Html::rawElement( 'tr',
			array( 'class' => "mw-htmlform-vertical-label $hideClass" ) + $hideAttributes,
			$label );
		$html .= Html::rawElement( 'tr',
			array( 'class' => "mw-htmlform-field-$fieldType {$this->mClass} $errorClass $hideClass" ) +
				$hideAttributes,
			$field );

		return $html . $helptext;
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return array
	 */
	function loadDataFromRequest( $request ) {
		if ( $this->mParent->getMethod() == 'post' ) {
			if ( $request->wasPosted() ) {
				// Checkboxes are not added to the request arrays if they're not checked,
				// so it's perfectly possible for there not to be an entry at all
				return $request->getArray( $this->mName, array() );
			} else {
				// That's ok, the user has not yet submitted the form, so show the defaults
				return $this->getDefault();
			}
		} else {
			// This is the impossible case: if we look at $_GET and see no data for our
			// field, is it because the user has not yet submitted the form, or that they
			// have submitted it with all the options unchecked. We will have to assume the
			// latter, which basically means that you can't specify 'positive' defaults
			// for GET forms.
			return $request->getArray( $this->mName, array() );
		}
	}

	function getDefault() {
		if ( isset( $this->mDefault ) ) {
			return $this->mDefault;
		} else {
			return array();
		}
	}

	function filterDataForSubmit( $data ) {
		$columns = HTMLFormField::flattenOptions( $this->mParams['columns'] );
		$rows = HTMLFormField::flattenOptions( $this->mParams['rows'] );
		$res = array();
		foreach ( $columns as $column ) {
			foreach ( $rows as $row ) {
				// Make sure option hasn't been forced
				$thisTag = "$column-$row";
				if ( $this->isTagForcedOff( $thisTag ) ) {
					$res[$thisTag] = false;
				} elseif ( $this->isTagForcedOn( $thisTag ) ) {
					$res[$thisTag] = true;
				} else {
					$res[$thisTag] = in_array( $thisTag, $data );
				}
			}
		}

		return $res;
	}
}
