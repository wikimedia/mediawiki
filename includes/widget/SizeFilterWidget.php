<?php
/**
 * MediaWiki Widgets – SizeFilterWidget class.
 *
 * @copyright 2011-2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

use \OOUI\RadioSelectInputWidget;
use \OOUI\TextInputWidget;

/**
 * Select and input widget.
 */
class SizeFilterWidget extends \OOUI\Widget {

	protected $radioselect = null;
	protected $textinput = null;

	/**
	 * RadioSelectInputWidget and a TextInputWidget to set minimum or maximum byte size
	 *
	 * @param array $config Configuration options
	 * @param bool $congif['selectMin'] Whether to select 'min', false would select 'max'
	 */
	public function __construct( array $config = [] ) {
		// Configuration initialization
		$config = array_merge( [ 'selectMin' => true ], $config );

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->radioselect = new RadioSelectInputWidget( [ 'options' => [
			[
				'data' => 'min',
				'label' => wfMessage( 'minimum-size' )->text()
			],
			[
				'data' => 'max',
				'label' => wfMessage( 'maximum-size' )->text()
			]
		] ] );
		$this->textinput = new TextInputWidget( [
			'placeholder' => wfMessage( 'pagesize' )->text()
		] );

		// Initialization
		$this->radioselect->setValue( $config[ 'selectMin' ] ? 'min' : 'max' );
		$this
			->addClasses( [ 'mw-widget-sizeFilterWidget' ] )
			->appendContent( $this->radioselect, $this->textinput );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.SizeFilterWidget';
	}

	public function getConfig( &$config ) {
		return parent::getConfig( $config );
	}
}
