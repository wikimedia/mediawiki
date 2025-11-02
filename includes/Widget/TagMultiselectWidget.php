<?php

namespace MediaWiki\Widget;

use OOUI\MultilineTextInputWidget;
use OOUI\Tag;
use OOUI\Widget;

/**
 * Base class for widgets to select multiple users, titles,
 * namespaces, etc.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class TagMultiselectWidget extends Widget {
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
	/** @var bool */
	protected $allowArbitrary;
	/** @var bool */
	protected $allowReordering;
	/** @var string[]|null */
	protected $allowedValues;

	/**
	 * @param array $config Configuration options
	 *   - array $config['default'] Array of items to use as preset data
	 *   - string $config['name'] Name attribute (used in forms)
	 *   - string $config['placeholder'] Placeholder message for input
	 *   - array $config['input'] Config options for the input widget
	 *   - int $config['tagLimit'] Maximum number of selected items
	 *   - bool $config['allowArbitrary'] Allow data items not present in the menu.
	 *   - bool $config['allowReordering'] Allow reordering of the items
	 *   - array $config['allowedValues'] Allowed items
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		// Properties
		$this->selectedArray = $config['default'] ?? [];
		$this->inputName = $config['name'] ?? null;
		$this->inputPlaceholder = $config['placeholder'] ?? null;
		$this->input = $config['input'] ?? [];
		$this->tagLimit = $config['tagLimit'] ?? null;
		$this->allowArbitrary = $config['allowArbitrary'] ?? false;
		$this->allowReordering = $config['allowReordering'] ?? true;
		$this->allowedValues = $config['allowedValues'] ?? null;

		$noJsFallback = ( new Tag( 'div' ) )
			->addClasses( [ 'mw-widgets-tagMultiselectWidget-nojs' ] )
			->appendContent( $this->getNoJavaScriptFallback() );

		$pending = new PendingTextInputWidget();

		$this->appendContent( $noJsFallback, $pending );
		$this->addClasses( [ 'mw-widgets-tagMultiselectWidget' ] );
	}

	/** @inheritDoc */
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
		if ( $this->allowArbitrary !== null ) {
			$config['allowArbitrary'] = $this->allowArbitrary;
		}
		if ( $this->allowReordering !== null ) {
			$config['allowReordering'] = $this->allowReordering;
		}
		if ( $this->allowedValues !== null ) {
			$config['allowedValues'] = $this->allowedValues;
		}

		$config['$overlay'] = true;
		return parent::getConfig( $config );
	}

	/**
	 * Provide the implementation for clients with JavaScript disabled.
	 *
	 * @stable to override
	 * @since 1.44
	 * @return Widget[]
	 */
	protected function getNoJavaScriptFallback() {
		$widget = new MultilineTextInputWidget( array_merge( [
			'name' => $this->inputName,
			'value' => implode( "\n", $this->selectedArray ),
			'rows' => min( $this->tagLimit, 10 ) ?? 10,
		], $this->input ) );

		return [ $widget ];
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.TagMultiselectWidget';
	}
}
