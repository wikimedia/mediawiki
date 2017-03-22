<?php
/**
 * MediaWiki Widgets – SelectWithInputWidget class.
 *
 * @copyright 2011-2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

use \OOUI\TextInputWidget;
use \OOUI\DropdownInputWidget;

/**
 * Select and input widget.
 */
class SelectWithInputWidget extends \OOUI\Widget {

	protected $textinput = null;
	protected $dropdowninput = null;

	/**
	 * A version of the SelectWithInputWidget, with `or` set to true.
	 *
	 * @param array $config Configuration options
	 * @param array $config['textinput'] Configuration for the TextInputWidget
	 * @param array $config['dropdowninput'] Configuration for the DropdownInputWidget
	 * @param boolean $config['or'] Configuration for whether the widget is dropdown AND input
	 *                              or dropdown OR input
	 */
	public function __construct( array $config = [] ) {
		// Configuration initialization
		$config = array_merge(
			[
				'textinput' => [],
				'dropdowninput' => [],
				'or' => false
			],
			$config
		);

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->config = $config;
		$this->textinput = new TextInputWidget( $config['textinput'] );
		$this->dropdowninput = new DropdownInputWidget( $config['dropdowninput'] );

		// Initialization
		$this
			->addClasses( [ 'mw-widget-selectWithInputWidget' ] )
			->appendContent( $this->dropdowninput, $this->textinput );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.SelectWithInputWidget';
	}

	public function getConfig( &$config ) {
		$config['textinput'] = $this->config['textinput'];
		$config['dropdowninput'] = $this->config['dropdowninput'];
		$config['or'] = $this->config['or'];
		return parent::getConfig( $config );
	}
}
