<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\Request\WebRequest;
use MediaWiki\Xml\XmlSelect;

/**
 * Select dropdown field, with an additional "other" textbox.
 *
 * HTMLComboboxField implements the same functionality using a single form field
 * and should be used instead.
 *
 * @stable to extend
 */
class HTMLSelectOrOtherField extends HTMLTextField {
	private const FIELD_CLASS = 'mw-htmlform-select-or-other';

	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $params ) {
		parent::__construct( $params );
		$this->getOptions();
		if ( !in_array( 'other', $this->mOptions, true ) ) {
			$msg =
				$params['other'] ?? wfMessage( 'htmlform-selectorother-other' )->text();
			// Have 'other' always as first element
			$this->mOptions = [ $msg => 'other' ] + $this->mOptions;
		}
	}

	/** @inheritDoc */
	public function getInputHTML( $value ) {
		$valInSelect = false;

		if ( $value !== false ) {
			$value = strval( $value );
			$valInSelect = in_array(
				$value, HTMLFormField::flattenOptions( $this->getOptions() ), true
			);
		}

		$selected = $valInSelect ? $value : 'other';

		$select = new XmlSelect( $this->mName, false, $selected );
		$select->addOptions( $this->getOptions() );

		$tbAttribs = [ 'size' => $this->getSize() ];

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

		if ( isset( $this->mParams['minlength'] ) ) {
			$tbAttribs['minlength'] = $this->mParams['minlength'];
		}

		$textbox = Html::input( $this->mName . '-other', $valInSelect ? '' : $value, 'text', $tbAttribs );

		$wrapperAttribs = [
			'id' => $this->mID,
			'class' => $this->getFieldClasses()
		];
		if ( $this->mClass !== '' ) {
			$wrapperAttribs['class'][] = $this->mClass;
		}
		return Html::rawElement(
			'div',
			$wrapperAttribs,
			"$select<br />\n$textbox"
		);
	}

	/** @inheritDoc */
	protected function shouldInfuseOOUI() {
		return true;
	}

	/** @inheritDoc */
	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.SelectWithInputWidget' ];
	}

	/** @inheritDoc */
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
		];

		$allowedParams = [
			'disabled',
			'tabindex',
		];

		$dropdownAttribs += \OOUI\Element::configFromHtmlAttributes(
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
			'minlength',
		];

		$textAttribs += \OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		if ( $this->mPlaceholder !== '' ) {
			$textAttribs['placeholder'] = $this->mPlaceholder;
		}

		$disabled = false;
		if ( isset( $this->mParams[ 'disabled' ] ) && $this->mParams[ 'disabled' ] ) {
			$disabled = true;
		}

		$inputClasses = $this->getFieldClasses();
		if ( $this->mClass !== '' ) {
			$inputClasses = array_merge( $inputClasses, explode( ' ', $this->mClass ) );
		}
		return $this->getInputWidget( [
			'id' => $this->mID,
			'classes' => $inputClasses,
			'disabled' => $disabled,
			'textinput' => $textAttribs,
			'dropdowninput' => $dropdownAttribs,
			'required' => $this->mParams[ 'required' ] ?? false,
			'or' => true,
		] );
	}

	/** @inheritDoc */
	public function getInputWidget( $params ) {
		return new \MediaWiki\Widget\SelectWithInputWidget( $params );
	}

	/** @inheritDoc */
	public function getInputCodex( $value, $hasErrors ) {
		// Figure out the value of the select.
		$valInSelect = false;
		if ( $value !== false ) {
			$value = strval( $value );
			$valInSelect = in_array(
				$value, HTMLFormField::flattenOptions( $this->getOptions() ), true
			);
		}
		$selected = $valInSelect ? $value : 'other';

		// Create the <select> element.
		$select = new XmlSelect( $this->mName, false, $selected );
		// TODO: Add support for error class once it's implemented in the Codex CSS-only Select.
		$select->setAttribute( 'class', 'cdx-select' );
		$select->addOptions( $this->getOptions() );

		// Set up attributes for the select and the text input.
		$textInputAttribs = [ 'size' => $this->getSize() ];
		$textInputAttribs['name'] = $this->mName . '-other';

		if ( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
			$textInputAttribs['disabled'] = 'disabled';
		}

		if ( isset( $this->mParams['tabindex'] ) ) {
			$select->setAttribute( 'tabindex', $this->mParams['tabindex'] );
			$textInputAttribs['tabindex'] = $this->mParams['tabindex'];
		}

		if ( isset( $this->mParams['maxlength'] ) ) {
			$textInputAttribs['maxlength'] = $this->mParams['maxlength'];
		}

		if ( isset( $this->mParams['minlength'] ) ) {
			$textInputAttribs['minlength'] = $this->mParams['minlength'];
		}

		// Get HTML of the select and text input.
		$select = $select->getHTML();
		$textInput = parent::buildCodexComponent(
			$valInSelect ? '' : $value,
			$hasErrors,
			'text',
			$this->mName . '-other',
			$textInputAttribs
		);

		// Set up the wrapper element and return the entire component.
		$wrapperAttribs = [
			'id' => $this->mID,
			'class' => $this->getFieldClasses()
		];
		if ( $this->mClass !== '' ) {
			$wrapperAttribs['class'][] = $this->mClass;
		}
		return Html::rawElement(
			'div',
			$wrapperAttribs,
			"$select\n$textInput"
		);
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

	/**
	 * Returns a list of classes that should be applied to the widget itself. Unfortunately, we can't use
	 * $this->mClass or the 'cssclass' config option, because they're also added to the outer field wrapper
	 * (which includes the label). This method exists a temporary workaround until HTMLFormField will have
	 * a stable way for subclasses to specify additional classes for the widget itself.
	 * @internal Should only be used in HTMLTimezoneField
	 * @return string[]
	 */
	protected function getFieldClasses(): array {
		return [ self::FIELD_CLASS ];
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLSelectOrOtherField::class, 'HTMLSelectOrOtherField' );
