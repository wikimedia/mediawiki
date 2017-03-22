<?php
/**
 * MediaWiki Widgets – SelectAndInputWidget class.
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
class SelectAndInputWidget extends \OOUI\Widget {

	protected $textinput = null;
	protected $dropdowninput = null;

	/**
	 * A version of the SelectWithInputWidget, with `or` set to false, as is
	 * the default.
	 *
	 * @param array $config Configuration options
	 * @param array $config['textinput'] Configuration for the TextInputWidget
	 * @param array $config['dropdowinput'] Configuration for the DropdownInputWidget
	 */
	public function __construct( array $config = [] ) {
		// Configuration initialization
		$config = array_merge(
			[
				'textinput' => [],
				'dropdowninput' => [],
			],
			$config
		);

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->config = $config;
		$this->textinput = new TextInputWidget( $config['textinput'] );
		$this->dropdowninput = new DropdownInputWidget( $config['dropdown'] );

		// Initialization
		$this
			->addClasses( [ 'mw-widget-SelectWithInputWidget' ] )
			->appendContent( $this->namespace, $this->title );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.SelectWithInputWidget';
	}

	public function getConfig( &$config ) {
		$config['textinput'] = $this->config['textinput'];
		$config['dropdowninput'] = $this->config['dropdowninput'];
		return parent::getConfig( $config );
	}
}
