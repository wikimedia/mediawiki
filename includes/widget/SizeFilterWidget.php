<?php
/**
 * MediaWiki Widgets – SizeFilterWidget class.
 *
 * @copyright 2011-2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

use \OOUI\ButtonSelectWidget;
use \OOUI\TextInputWidget;

/**
 * Select and input widget.
 */
class SizeFilterWidget extends \OOUI\Widget {

	protected $textinput = null;
	protected $dropdowninput = null;

	/**
	 * ButtonSelectWidget and a TextInputWidget to set minimum or maximum byte size
	 *
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->buttonselect = new ButtonSelectWidget(); #TODO this doesn't exist?!
		$this->textinput = new TextInputWidget( $config['textinput'] );

		// Initialization
		$this
			->addClasses( [ 'mw-widget-sizeFilterWidget' ] )
			->appendContent( $this->buttonselect, $this->textinput );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.SizeFilterWidget';
	}

	public function getConfig( &$config ) {
		return parent::getConfig( $config );
	}
}
