<?php

namespace MediaWiki\Widget;

use OOUI\Widget;

/**
 * Expiry widget.
 *
 * Allows the user to toggle between a precise time or enter a relative time,
 * regardless, the value comes in as a relative time.
 *
 * @copyright 2018 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class ExpiryInputWidget extends Widget {

	/**
	 * @var Widget
	 */
	protected $relativeInput;

	/**
	 * @var bool
	 */
	protected $noDatePicker;

	/**
	 * @var bool
	 */
	protected $required;

	/**
	 * @param Widget $relativeInput
	 * @param array $options Configuration options
	 */
	public function __construct( Widget $relativeInput, array $options = [] ) {
		$config = \RequestContext::getMain()->getConfig();

		$options['noDatePicker'] = $config->get( 'ExpiryWidgetNoDatePicker' );

		parent::__construct( $options );

		$this->noDatePicker = $options['noDatePicker'];
		$this->required = $options['required'] ?? false;

		// Properties
		$this->relativeInput = $relativeInput;
		$this->relativeInput->addClasses( [ 'mw-widget-ExpiryWidget-relative' ] );

		// Initialization
		$classes = [
			'mw-widget-ExpiryWidget',
		];
		if ( $options['noDatePicker'] === false ) {
			$classes[] = 'mw-widget-ExpiryWidget-hasDatePicker';
		}
		$this
			->addClasses( $classes )
			->appendContent( $this->relativeInput );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.ExpiryWidget';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getConfig( &$config ) {
		$config['noDatePicker'] = $this->noDatePicker;
		$config['required'] = $this->required;
		$config['relativeInput'] = [];
		$this->relativeInput->getConfig( $config['relativeInput'] );
		return parent::getConfig( $config );
	}
}
