<?php

namespace MediaWiki\Widget;

use OOUI\CheckboxInputWidget;
use OOUI\Exception;
use OOUI\FieldLayout;
use OOUI\Widget;

/**
 * Namespace input widget. Displays a dropdown box with the choice of available namespaces, plus two
 * checkboxes to include associated namespace or to invert selection.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class ComplexNamespaceInputWidget extends Widget {

	/** @var array */
	protected $config;
	/** @var NamespaceInputWidget */
	protected $namespace;
	/** @var CheckboxInputWidget|null */
	protected $associated = null;
	/** @var FieldLayout|null */
	protected $associatedLabel = null;
	/** @var CheckboxInputWidget|null */
	protected $invert = null;
	/** @var FieldLayout|null */
	protected $invertLabel = null;

	/**
	 * @param array $config Configuration options
	 *   - array $config['namespace'] Configuration for the NamespaceInputWidget
	 *     dropdown with list of namespaces
	 *   - string $config['namespace']['includeAllValue'] If specified,
	 *     add an "all namespaces" option to the dropdown, and use this as the input value for it
	 *   - array|null $config['invert'] Configuration for the "invert selection"
	 *     CheckboxInputWidget. If null, the checkbox will not be generated.
	 *   - array|null $config['associated'] Configuration for the "include associated namespace"
	 *     CheckboxInputWidget. If null, the checkbox will not be generated.
	 *   - array $config['invertLabel'] Configuration for the FieldLayout with label
	 *     wrapping the "invert selection" checkbox
	 *   - string $config['invertLabel']['label'] Label text for the label
	 *   - array $config['associatedLabel'] Configuration for the FieldLayout with label
	 *     wrapping the "include associated namespace" checkbox
	 *   - string $config['associatedLabel']['label'] Label text for the label
	 *
	 * @throws Exception
	 */
	public function __construct( array $config = [] ) {
		// Configuration initialization
		$config = array_merge(
			[
				// Config options for nested widgets
				'namespace' => [],
				'invert' => [],
				'invertLabel' => [],
				'associated' => [],
				'associatedLabel' => [],
			],
			$config
		);

		parent::__construct( $config );

		// Properties
		$this->config = $config;

		$this->namespace = new NamespaceInputWidget( $config['namespace'] );
		if ( $config['associated'] !== null ) {
			$this->associated = new CheckboxInputWidget( array_merge(
				[ 'value' => '1' ],
				$config['associated']
			) );
			// TODO Should use a LabelWidget? But they don't work like HTML <label>s yet
			$this->associatedLabel = new FieldLayout(
				$this->associated,
				array_merge(
					[ 'align' => 'inline' ],
					$config['associatedLabel']
				)
			);
		}
		if ( $config['invert'] !== null ) {
			$this->invert = new CheckboxInputWidget( array_merge(
				[ 'value' => '1' ],
				$config['invert']
			) );
			// TODO Should use a LabelWidget? But they don't work like HTML <label>s yet
			$this->invertLabel = new FieldLayout(
				$this->invert,
				array_merge(
					[ 'align' => 'inline' ],
					$config['invertLabel']
				)
			);
		}

		// Initialization
		$this
			->addClasses( [ 'mw-widget-complexNamespaceInputWidget' ] )
			->appendContent( $this->namespace, $this->associatedLabel, $this->invertLabel );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.ComplexNamespaceInputWidget';
	}

	/** @inheritDoc */
	public function getConfig( &$config ) {
		$config = array_merge(
			$config,
			array_intersect_key(
				$this->config,
				array_fill_keys(
					[
						'namespace',
						'invert',
						'invertLabel',
						'associated',
						'associatedLabel'
					],
					true
				)
			)
		);
		$config['namespace']['dropdown']['$overlay'] = true;
		return parent::getConfig( $config );
	}
}
