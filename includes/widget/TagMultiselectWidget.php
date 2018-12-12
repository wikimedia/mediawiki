<?php

namespace MediaWiki\Widget;

use OOUI\MultilineTextInputWidget;

/**
 * Abstract base class for widgets to select multiple users, titles,
 * namespaces, etc.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
abstract class TagMultiselectWidget extends \OOUI\Widget {

	protected $selectedArray = [];
	protected $inputName = null;
	protected $inputPlaceholder = null;
	protected $tagLimit = null;

	/**
	 * @param array $config Configuration options
	 *   - array $config['default'] Array of items to use as preset data
	 *   - array $config['name'] Name attribute (used in forms)
	 *   - array $config['placeholder'] Placeholder message for input
	 *   - array $config['input'] Config options for the input widget
	 *   - number $config['tagLimit'] Maximum number of selected items
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		// Properties
		if ( isset( $config['default'] ) ) {
			$this->selectedArray = $config['default'];
		}
		if ( isset( $config['name'] ) ) {
			$this->inputName = $config['name'];
		}
		if ( isset( $config['placeholder'] ) ) {
			$this->inputPlaceholder = $config['placeholder'];
		}
		if ( isset( $config['input'] ) ) {
			$this->input = $config['input'];
		} else {
			$this->input = [];
		}
		if ( isset( $config['tagLimit'] ) ) {
			$this->tagLimit = $config['tagLimit'];
		}

		$textarea = new MultilineTextInputWidget( array_merge( [
			'name' => $this->inputName,
			'value' => implode( "\n", $this->selectedArray ),
			'rows' => 10,
			'classes' => [
				'mw-widgets-tagMultiselectWidget-multilineTextInputWidget'
			],
		], $this->input ) );

		$pending = new PendingTextInputWidget();

		$this->appendContent( $textarea, $pending );
		$this->addClasses( [ 'mw-widgets-tagMultiselectWidget' ] );
	}

	public function getConfig( &$config ) {
		if ( $this->selectedArray !== null ) {
			$config['selected'] = $this->selectedArray;
		}
		if ( $this->inputName !== null ) {
			$config['name'] = $this->inputName;
		}
		if ( $this->inputPlaceholder !== null ) {
			$config['placeholder'] = $this->inputPlaceholder;
		}
		if ( $this->input !== null ) {
			$config['input'] = $this->input;
		}
		if ( $this->tagLimit !== null ) {
			$config['tagLimit'] = $this->tagLimit;
		}

		$config['$overlay'] = true;
		return parent::getConfig( $config );
	}

}
