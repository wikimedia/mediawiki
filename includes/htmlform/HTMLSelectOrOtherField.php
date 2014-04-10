<?php

/**
 * Select dropdown field, with an additional "other" textbox.
 */
class HTMLSelectOrOtherField extends HTMLTextField {
	function __construct( $params ) {
		parent::__construct( $params );
		$this->getOptions();
		if ( !in_array( 'other', $this->mOptions, true ) ) {
			$msg =
				isset( $params['other'] )
					? $params['other']
					: wfMessage( 'htmlform-selectorother-other' )->text();
			// Have 'other' always as first element
			$this->mOptions = array( $msg => 'other' ) + $this->mOptions;
		}

	}

	function getInputHTML( $value ) {
		$valInSelect = false;

		if ( $value !== false ) {
			$value = strval( $value );
			$valInSelect = in_array(
				$value, HTMLFormField::flattenOptions( $this->getOptions() ), true
			);
		}

		$selected = $valInSelect ? $value : 'other';

		$select = new XmlSelect( $this->mName, $this->mID, $selected );
		$select->addOptions( $this->getOptions() );

		$select->setAttribute( 'class', 'mw-htmlform-select-or-other' );

		$tbAttribs = array( 'id' => $this->mID . '-other', 'size' => $this->getSize() );

		if ( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
			$tbAttribs['disabled'] = 'disabled';
		}

		if ( isset( $this->mParams['tabindex'] ) ) {
			$select->setAttribute( 'tabindex', $this->mParams['tabindex'] );
			$tbAttribs['tabindex'] = $this->mParams['tabindex'];
		}

		$select = $select->getHTML();

		if ( isset( $this->mParams['maxlength'] ) ) {
			$tbAttribs['maxlength'] = $this->mParams['maxlength'];
		}

		if ( $this->mClass !== '' ) {
			$tbAttribs['class'] = $this->mClass;
		}

		$textbox = Html::input( $this->mName . '-other', $valInSelect ? '' : $value, 'text', $tbAttribs );

		return "$select<br />\n$textbox";
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return string
	 */
	function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {
			$val = $request->getText( $this->mName );

			if ( $val === 'other' ) {
				$val = $request->getText( $this->mName . '-other' );
			}

			return $val;
		} else {
			return $this->getDefault();
		}
	}
}
