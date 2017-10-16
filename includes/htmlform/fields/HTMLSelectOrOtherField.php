<?php

/**
 * Select dropdown field, with an additional "other" textbox.
 *
 * HTMLComboboxField implements the same functionality using a single form field
 * and should be used instead.
 */
class HTMLSelectOrOtherField extends HTMLTextField {
	public function __construct( $params ) {
		parent::__construct( $params );
		$this->getOptions();
		if ( !in_array( 'other', $this->mOptions, true ) ) {
			$msg =
				isset( $params['other'] )
					? $params['other']
					: wfMessage( 'htmlform-selectorother-other' )->text();
			// Have 'other' always as first element
			$this->mOptions = [ $msg => 'other' ] + $this->mOptions;
		}
	}

	public function getInputHTML( $value ) {
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

		$tbAttribs = [ 'id' => $this->mID . '-other', 'size' => $this->getSize() ];

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

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.SelectWithInputWidget' ];
	}

	public function getInputOOUI( $value ) {
		$this->mParent->getOutput()->addModuleStyles( 'mediawiki.widgets.SelectWithInputWidget.styles' );

		$valInSelect = false;
		if ( $value !== false ) {
			$value = strval( $value );
			$valInSelect = in_array(
				$value, HTMLFormField::flattenOptions( $this->getOptions() ), true
			);
		}

		# DropdownInput
		$dropdownAttribs = [
			'name' => $this->mName,
			'options' => $this->getOptionsOOUI(),
			'value' => $valInSelect ? $value : 'other',
			'class' => [ 'mw-htmlform-select-or-other' ],
		];

		$allowedParams = [
			'disabled',
			'tabindex',
		];

		$dropdownAttribs += OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		# TextInput
		$textAttribs = [
			'name' => $this->mName . '-other',
			'size' => $this->getSize(),
			'value' => $valInSelect ? '' : $value,
		];

		$allowedParams = [
			'required',
			'autofocus',
			'multiple',
			'disabled',
			'tabindex',
			'maxlength',
		];

		$textAttribs += OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		if ( $this->mClass !== '' ) {
			$textAttribs['classes'] = [ $this->mClass ];
		}
		if ( $this->mPlaceholder !== '' ) {
			$textAttribs['placeholder'] = $this->mPlaceholder;
		}

		return $this->getInputWidget( [
			'id' => $this->mID,
			'textinput' => $textAttribs,
			'dropdowninput' => $dropdownAttribs,
			'or' => true,
		] );
	}

	public function getInputWidget( $params ) {
		return new MediaWiki\Widget\SelectWithInputWidget( $params );
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return string
	 */
	public function loadDataFromRequest( $request ) {
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
