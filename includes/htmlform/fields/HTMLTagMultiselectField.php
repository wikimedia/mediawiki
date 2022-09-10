<?php

use MediaWiki\Widget\TagMultiselectWidget;

/**
 * Implements a tag multiselect input field for arbitrary values.
 *
 * Besides the parameters recognized by HTMLTextField, additional recognized
 * parameters are:
 *  allowArbitrary - (optional) Bool to allow arbitrary inputs
 *  allowedValues - (optional) Array of allowed values
 *
 * The result is the array of tags
 *
 * @stable to extend
 * @note This widget is not likely to remain functional in non-OOUI forms.
 */
class HTMLTagMultiselectField extends HTMLTextField {
	public function loadDataFromRequest( $request ) {
		$value = $request->getText( $this->mName, $this->getDefault() );

		$tagsArray = explode( "\n", $value );
		// Remove empty lines
		$tagsArray = array_values( array_filter( $tagsArray, static function ( $tag ) {
			return trim( $tag ) !== '';
		} ) );
		// Remove any duplicate tags
		$uniqueTags = array_unique( $tagsArray );

		// This function is expected to return a string
		return implode( "\n", $uniqueTags );
	}

	public function validate( $value, $alldata ) {
		if ( $value === null ) {
			return false;
		}

		// $value is a string, because HTMLForm fields store their values as strings
		$tagsArray = explode( "\n", $value );

		if ( isset( $this->mParams['max'] ) && ( count( $tagsArray ) > $this->mParams['max'] ) ) {
			return $this->msg( 'htmlform-multiselect-toomany', $this->mParams['max'] );
		}

		foreach ( $tagsArray as $tag ) {
			$result = parent::validate( $tag, $alldata );
			if ( $result !== true ) {
				return $result;
			}

			if ( empty( $this->mParams['allowArbitrary'] ) && $tag ) {
				$allowedValues = $this->mParams['allowedValues'] ?? [];
				if ( !in_array( $tag, $allowedValues ) ) {
					return $this->msg( 'htmlform-tag-not-allowed', $tag )->escaped();
				}
			}
		}

		return true;
	}

	public function getInputHTML( $value ) {
		$this->mParent->getOutput()->enableOOUI();
		return $this->getInputOOUI( $value );
	}

	public function getInputOOUI( $value ) {
		$params = [ 'name' => $this->mName ];

		if ( isset( $this->mParams['id'] ) ) {
			$params['id'] = $this->mParams['id'];
		}

		if ( isset( $this->mParams['disabled'] ) ) {
			$params['disabled'] = $this->mParams['disabled'];
		}

		if ( isset( $this->mParams['default'] ) ) {
			$params['default'] = $this->mParams['default'];
		}

		$params['placeholder'] = $this->mParams['placeholder'] ??
			$this->msg( 'mw-widgets-tagmultiselect-placeholder' )->plain();

		if ( isset( $this->mParams['max'] ) ) {
			$params['tagLimit'] = $this->mParams['max'];
		}

		if ( isset( $this->mParams['allowArbitrary'] ) ) {
			$params['allowArbitrary'] = $this->mParams['allowArbitrary'];
		}

		if ( isset( $this->mParams['allowedValues'] ) ) {
			$params['allowedValues'] = $this->mParams['allowedValues'];
		}

		if ( isset( $this->mParams['input'] ) ) {
			$params['input'] = $this->mParams['input'];
		}

		if ( $value !== null ) {
			// $value is a string, but the widget expects an array
			$params['default'] = $value === '' ? [] : explode( "\n", $value );
		}

		// Make the field auto-infusable when it's used inside a legacy HTMLForm rather than OOUIHTMLForm
		$params['infusable'] = true;
		$params['classes'] = [ 'mw-htmlform-autoinfuse' ];
		$widget = new TagMultiselectWidget( $params );
		$widget->setAttributes( [ 'data-mw-modules' => implode( ',', $this->getOOUIModules() ) ] );

		return $widget;
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.TagMultiselectWidget' ];
	}

}
