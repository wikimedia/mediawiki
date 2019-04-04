<?php

use MediaWiki\Widget\NamespacesMultiselectWidget;

/**
 * Implements a tag multiselect input field for namespaces.
 *
 * The result is the array of namespaces
 *
 * TODO: This widget duplicates a lot from HTMLTitlesMultiselectField,
 * which itself duplicates HTMLUsersMultiselectField. These classes
 * should be refactored.
 *
 * @note This widget is not likely to remain functional in non-OOUI forms.
 */
class HTMLNamespacesMultiselectField extends HTMLSelectNamespace {
	public function loadDataFromRequest( $request ) {
		$value = $request->getText( $this->mName, $this->getDefault() );

		$namespaces = explode( "\n", $value );
		// Remove empty lines
		$namespaces = array_values( array_filter( $namespaces, function ( $namespace ) {
			return trim( $namespace ) !== '';
		} ) );
		// This function is expected to return a string
		return implode( "\n", $namespaces );
	}

	public function validate( $value, $alldata ) {
		if ( !$this->mParams['exists'] ) {
			return true;
		}

		if ( is_null( $value ) ) {
			return false;
		}

		// $value is a string, because HTMLForm fields store their values as strings
		$namespaces = explode( "\n", $value );

		if ( isset( $this->mParams['max'] ) && ( count( $namespaces ) > $this->mParams['max'] ) ) {
			return $this->msg( 'htmlform-int-toohigh', $this->mParams['max'] );
		}

		foreach ( $namespaces as $namespace ) {
			if ( $namespace < 0 ) {
				return $this->msg( 'htmlform-select-badoption' );
			}

			$result = parent::validate( $namespace, $alldata );
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
		$widget = new NamespacesMultiselectWidget( $params );
		$widget->setAttributes( [ 'data-mw-modules' => implode( ',', $this->getOOUIModules() ) ] );

		return $widget;
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.NamespacesMultiselectWidget' ];
	}

}
