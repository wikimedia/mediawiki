<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\MediaWikiServices;
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
 * @stable to extend
 * @note This widget is not likely to remain functional in non-OOUI forms.
 */
class HTMLNamespacesMultiselectField extends HTMLSelectNamespace {
	/** @inheritDoc */
	public function loadDataFromRequest( $request ) {
		$value = $request->getText( $this->mName, $this->getDefault() ?? '' );

		$namespaces = explode( "\n", $value );
		// Remove empty lines
		$namespaces = array_values( array_filter( $namespaces, static function ( $namespace ) {
			return trim( $namespace ) !== '';
		} ) );
		// This function is expected to return a string
		return implode( "\n", $namespaces );
	}

	/** @inheritDoc */
	public function validate( $value, $alldata ) {
		if ( !$this->mParams['exists'] || $value === '' ) {
			return true;
		}

		if ( $value === null ) {
			return false;
		}

		// $value is a string, because HTMLForm fields store their values as strings
		$namespaces = explode( "\n", $value );

		if ( isset( $this->mParams['max'] ) && ( count( $namespaces ) > $this->mParams['max'] ) ) {
			return $this->msg( 'htmlform-multiselect-toomany', $this->mParams['max'] );
		}

		foreach ( $namespaces as $namespace ) {
			if (
				$namespace < 0 ||
				!MediaWikiServices::getInstance()->getNamespaceInfo()->exists( (int)$namespace )
			) {
				return $this->msg( 'htmlform-select-badoption' );
			}

			$result = parent::validate( $namespace, $alldata );
			if ( $result !== true ) {
				return $result;
			}
		}

		return true;
	}

	/** @inheritDoc */
	public function getInputHTML( $value ) {
		$this->mParent->getOutput()->enableOOUI();
		return $this->getInputOOUI( $value );
	}

	/** @inheritDoc */
	public function getInputOOUI( $value ) {
		$this->mParent->getOutput()->addModuleStyles( 'mediawiki.widgets.TagMultiselectWidget.styles' );

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

		$params['placeholder'] = $this->mParams['placeholder'] ??
			$this->msg( 'mw-widgets-titlesmultiselect-placeholder' )->plain();

		if ( isset( $this->mParams['max'] ) ) {
			$params['tagLimit'] = $this->mParams['max'];
		}

		if ( isset( $this->mParams['input'] ) ) {
			$params['input'] = $this->mParams['input'];
		}

		if ( isset( $this->mParams['allowEditTags'] ) ) {
			$params['allowEditTags'] = $this->mParams['allowEditTags'];
		}

		if ( $value !== null ) {
			// $value is a string, but the widget expects an array
			$params['default'] = $value === '' ? [] : explode( "\n", $value );
		}

		// Make the field auto-infusable when it's used inside a legacy HTMLForm rather than OOUIHTMLForm
		$params['infusable'] = true;
		$params['classes'] = [ 'mw-htmlform-autoinfuse' ];
		$widget = new NamespacesMultiselectWidget( $params );
		$widget->setAttributes( [ 'data-mw-modules' => implode( ',', $this->getOOUIModules() ) ] );

		return $widget;
	}

	/** @inheritDoc */
	protected function shouldInfuseOOUI() {
		return true;
	}

	/** @inheritDoc */
	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.NamespacesMultiselectWidget' ];
	}

	/** @inheritDoc */
	public function getInputCodex( $value, $hasErrors ) {
		// HTMLTextAreaField defaults to 'rows' => 25, which is too big for this field
		// Use 10 instead (but allow $this->mParams to override that value)
		$textAreaField = new HTMLTextAreaField( $this->mParams + [ 'rows' => 10 ] );
		return $textAreaField->getInputCodex( $value, $hasErrors );
	}

}

/** @deprecated class alias since 1.42 */
class_alias( HTMLNamespacesMultiselectField::class, 'HTMLNamespacesMultiselectField' );
