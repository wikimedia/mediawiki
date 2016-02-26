<?php
/**
 * Created by PhpStorm.
 * User: Geoffrey
 * Date: 2/26/2016
 * Time: 5:17 PM
 */

namespace MediaWiki\Widget;

/**
 * Date input widget.
 */
class DateInputWidget extends \OOUI\TextInputWidget {


	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		// Parent constructor
		parent::__construct(
			array_merge( [ 'infusable' => true, 'type' => 'date' ], $config )
		);

		// Initialization
		$this->addClasses( [ 'mw-widget-dateInputWidget' ] );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.DateInputWidget';
	}

	public function getConfig( &$config ) {
		return parent::getConfig( $config );
	}
}