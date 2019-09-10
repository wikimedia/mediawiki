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
	/** @var array */
	protected $selectedArray;
	/** @var string|null */
	protected $inputName;
	/** @var string|null */
	protected $inputPlaceholder;
	/** @var array */
	protected $input;
	/** @var int|null */
	protected $tagLimit;

	/**
	 * @param array $config Configuration options
	 *   - array $config['default'] Array of items to use as preset data
	 *   - string $config['name'] Name attribute (used in forms)
	 *   - string $config['placeholder'] Placeholder message for input
	 *   - array $config['input'] Config options for the input widget
	 *   - int $config['tagLimit'] Maximum number of selected items
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		// Properties
		$this->selectedArray = $config['default'] ?? [];
		$this->inputName = $config['name'] ?? null;
		$this->inputPlaceholder = $config['placeholder'] ?? null;
		$this->input = $config['input'] ?? [];
		$this->tagLimit = $config['tagLimit'] ?? null;

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
