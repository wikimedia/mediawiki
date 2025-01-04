<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Html\Html;
use MediaWiki\Widget\OrderedMultiselectWidget;

/**
 * Implements a tag multiselect input field with a searchable dropdown containing valid tags.
 *
 * Besides the parameters recognized by HTMLTagMultiselectField, additional recognized
 * parameters are:
 *  options - array, the list of allowed values.
 *
 * The result is a newline-delimited string of selected tags.
 *
 * @note This widget is not likely to remain functional in non-OOUI forms.
 */
class HTMLOrderedMultiselectField extends HTMLTagMultiselectField {

	protected function getInputWidget( $params ) {
		$widget = new OrderedMultiselectWidget( $params + [
			'options' => $this->getOptionsOOUI(),
		] );
		$widget->setAttributes( [ 'data-mw-modules' => implode( ',', $this->getOOUIModules() ) ] );
		return $widget;
	}

	public function validate( $value, $alldata ) {
		$this->mParams['allowedValues'] = self::flattenOptions( $this->getOptions() );
		return parent::validate( $value, $alldata );
	}

	public function getOptionsOOUI() {
		$optionsOouiSections = [];
		$options = $this->getOptions();

		foreach ( $options as $label => $section ) {
			if ( is_array( $section ) ) {
				$optionsOouiSections[ $label ] = Html::listDropdownOptionsOoui( $section );
				unset( $options[$label] );
			}
		}

		// If anything remains in the array, they are sectionless options. Put them at the beginning.
		if ( $options ) {
			$optionsOouiSections = array_merge(
				[ '' => Html::listDropdownOptionsOoui( $options ) ],
				$optionsOouiSections
			);
		}

		return $optionsOouiSections;
	}

	public function getOOUIModules() {
		return [ 'mediawiki.widgets.OrderedMultiselectWidget' ];
	}
}
