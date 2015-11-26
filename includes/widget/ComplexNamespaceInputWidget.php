<?php
/**
 * MediaWiki Widgets – ComplexNamespaceInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * Namespace input widget. Displays a dropdown box with the choice of available namespaces, plus two
 * checkboxes to include associated namespace or to invert selection.
 */
class ComplexNamespaceInputWidget extends \OOUI\Widget {

	protected $config;
	protected $namespace;
	protected $associated = null;
	protected $associatedLabel = null;
	protected $invert = null;
	protected $invertLabel = null;

	/**
	 * @param array $config Configuration options
	 * @param array $config['namespace'] Configuration for the NamespaceInputWidget dropdown with list
	 *     of namespaces
	 * @param string $config['namespace']['includeAllValue'] If specified, add a "all namespaces"
	 *     option to the dropdown, and use this as the input value for it
	 * @param array|null $config['invert'] Configuration for the "invert selection" CheckboxInputWidget. If
	 *     null, the checkbox will not be generated.
	 * @param array|null $config['associated'] Configuration for the "include associated namespace"
	 *     CheckboxInputWidget. If null, the checkbox will not be generated.
	 * @param array $config['invertLabel'] Configuration for the FieldLayout with label wrapping the
	 *     "invert selection" checkbox
	 * @param string $config['invertLabel']['label'] Label text for the label
	 * @param array $config['associatedLabel'] Configuration for the FieldLayout with label wrapping
	 *     the "include associated namespace" checkbox
	 * @param string $config['associatedLabel']['label'] Label text for the label
	 */
	public function __construct( array $config = array() ) {
		// Configuration initialization
		$config = array_merge(
			array(
				// Config options for nested widgets
				'namespace' => array(),
				'invert' => array(),
				'invertLabel' => array(),
				'associated' => array(),
				'associatedLabel' => array(),
			),
			$config
		);

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->config = $config;

		$this->namespace = new NamespaceInputWidget( $config['namespace'] );
		if ( $config['associated'] !== null ) {
			$this->associated = new \OOUI\CheckboxInputWidget( array_merge(
				array( 'value' => '1' ),
				$config['associated']
			) );
			// TODO Should use a LabelWidget? But they don't work like HTML <label>s yet
			$this->associatedLabel = new \OOUI\FieldLayout(
				$this->associated,
				array_merge(
					array( 'align' => 'inline' ),
					$config['associatedLabel']
				)
			);
		}
		if ( $config['invert'] !== null ) {
			$this->invert = new \OOUI\CheckboxInputWidget( array_merge(
				array( 'value' => '1' ),
				$config['invert']
			) );
			// TODO Should use a LabelWidget? But they don't work like HTML <label>s yet
			$this->invertLabel = new \OOUI\FieldLayout(
				$this->invert,
				array_merge(
					array( 'align' => 'inline' ),
					$config['invertLabel']
				)
			);
		}

		// Initialization
		$this
			->addClasses( array( 'mw-widget-complexNamespaceInputWidget' ) )
			->appendContent( $this->namespace, $this->associatedLabel, $this->invertLabel );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.ComplexNamespaceInputWidget';
	}

	public function getConfig( &$config ) {
		$config = array_merge(
			$config,
			array_intersect_key(
				$this->config,
				array_fill_keys( array( 'namespace', 'invert', 'invertLabel', 'associated', 'associatedLabel' ), true )
			)
		);
		return parent::getConfig( $config );
	}
}
