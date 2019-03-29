<?php

use MediaWiki\Widget\TitlesMultiselectWidget;

/**
 * Implements a tag multiselect input field for titles.
 *
 * Besides the parameters recognized by HTMLTitleTextField, additional recognized
 * parameters are:
 *  default - (optional) Array of titles to use as preset data
 *  placeholder - (optional) Custom placeholder message for input
 *
 * The result is the array of titles
 *
 * This widget is a duplication of HTMLUsersMultiselectField, except for:
 * - The configuration variable changed to 'titles' (from 'users')
 * - OOUI modules were adjusted for the TitlesMultiselectWidget
 * - The PHP version instantiates a MediaWiki\Widget\TitlesMultiselectWidget
 *
 * @note This widget is not likely to remain functional in non-OOUI forms.
 */
class HTMLTitlesMultiselectField extends HTMLTitleTextField {
	public function __construct( $params ) {
		$params += [
			// This overrides the default from HTMLTitleTextField
			'required' => false,
		];

		parent::__construct( $params );
	}

	public function loadDataFromRequest( $request ) {
		$value = $request->getText( $this->mName, $this->getDefault() );

		$titlesArray = explode( "\n", $value );
		// Remove empty lines
		$titlesArray = array_values( array_filter( $titlesArray, function ( $title ) {
			return trim( $title ) !== '';
		} ) );
		// This function is expected to return a string
		return implode( "\n", $titlesArray );
	}

	public function validate( $value, $alldata ) {
		if ( !$this->mParams['exists'] ) {
			return true;
		}

		if ( is_null( $value ) ) {
			return false;
		}

		// $value is a string, because HTMLForm fields store their values as strings
		$titlesArray = explode( "\n", $value );

		if ( isset( $this->mParams['max'] ) && ( count( $titlesArray ) > $this->mParams['max'] ) ) {
			return $this->msg( 'htmlform-int-toohigh', $this->mParams['max'] );
		}

		foreach ( $titlesArray as $title ) {
			$result = parent::validate( $title, $alldata );
			if ( $result !== true ) {
				return $result;
			}
		}

		return true;
	}

	public function getInputHTML( $value ) {
		$this->mParent->getOutput()->enableOOUI();
		return $this->getInputOOUI( $value );
	}

	public function getInputOOUI( $value ) {
		$params = [
			'id' => $this->mID,
			'name' => $this->mName,
			'dir' => $this->mDir,
		];

		if ( isset( $this->mParams['disabled'] ) ) {
			$params['disabled'] = $this->mParams['disabled'];
		}

		if ( isset( $this->mParams['default'] ) ) {
			$params['default'] = $this->mParams['default'];
		}

		if ( isset( $this->mParams['placeholder'] ) ) {
			$params['placeholder'] = $this->mParams['placeholder'];
		} else {
			$params['placeholder'] = $this->msg( 'mw-widgets-titlesmultiselect-placeholder' )->plain();
		}

		if ( isset( $this->mParams['max'] ) ) {
			$params['tagLimit'] = $this->mParams['max'];
		}

		if ( isset( $this->mParams['showMissing'] ) ) {
			$params['showMissing'] = $this->mParams['showMissing'];
		}
		if ( isset( $this->mParams['excludeDynamicNamespaces'] ) ) {
			$params['excludeDynamicNamespaces'] = $this->mParams['excludeDynamicNamespaces'];
		}

		if ( isset( $this->mParams['input'] ) ) {
			$params['input'] = $this->mParams['input'];
		}

		if ( !is_null( $value ) ) {
			// $value is a string, but the widget expects an array
			$params['default'] = $value === '' ? [] : explode( "\n", $value );
		}

		// Make the field auto-infusable when it's used inside a legacy HTMLForm rather than OOUIHTMLForm
		$params['infusable'] = true;
		$params['classes'] = [ 'mw-htmlform-field-autoinfuse' ];
		$widget = new TitlesMultiselectWidget( $params );
		$widget->setAttributes( [ 'data-mw-modules' => implode( ',', $this->getOOUIModules() ) ] );

		return $widget;
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.TitlesMultiselectWidget' ];
	}

}
