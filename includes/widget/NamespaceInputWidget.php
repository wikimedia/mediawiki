<?php
/**
 * MediaWiki Widgets – NamespaceInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * Namespace input widget. Displays a dropdown box with the choice of available namespaces, plus two
 * checkboxes to include associated namespace or to invert selection.
 */
class NamespaceInputWidget extends \OOUI\Widget {

	protected $namespace = null;
	protected $associated = null;
	protected $invert = null;
	protected $allValue = null;

	/**
	 * @param array $config Configuration options
	 * @param string $config['nameNamespace'] HTML input name for the namespace dropdown box (default:
	 *     'namespace')
	 * @param string $config['nameInvert'] HTML input name for the "invert selection" checkbox. If
	 *     null, the checkbox will not be generated. (default: 'invert')
	 * @param string $config['nameAssociated'] HTML input name for the "include associated namespace"
	 *     checkbox. If null, the checkbox will not be generated. (default: 'associated')
	 * @param string $config['includeAllValue'] If specified, add a "all namespaces" option to the
	 *     namespace dropdown, and use this as the input value for it
	 * @param int|string $config['valueNamespace'] Input value of the namespace dropdown box. May be a
	 *     string only if 'includeAllValue' is set.
	 * @param boolean $config['valueInvert'] Input value of the "invert selection" checkbox (default:
	 *     false)
	 * @param boolean $config['valueAssociated'] Input value of the "include associated namespace"
	 *     checkbox (default: false)
	 * @param string $config['labelInvert'] Text of label to use for "invert selection" checkbox
	 * @param string $config['labelAssociated'] Text of label to use for "include associated
	 *     namespace" checkbox
	 */
	public function __construct( array $config = array() ) {
		// Configuration initialization

		$config = array_merge(
			array(
				'nameNamespace' => 'namespace',
				'nameInvert' => 'invert',
				'nameAssociated' => 'associated',
				// Choose first available: either main namespace or the "all namespaces" option
				'valueNamespace' => null,
				'valueInvert' => false,
				'valueAssociated' => false,
			),
			$config
		);

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->allValue = isset( $config['includeAllValue'] ) ? $config['includeAllValue'] : null;
		$this->namespace = new \OOUI\DropdownInputWidget( array(
			'name' => $config['nameNamespace'],
			'value' => $config['valueNamespace'],
			'options' => $this->getNamespaceDropdownOptions( $config ),
		) );
		if ( $config['nameAssociated'] !== null ) {
			// FIXME Should use a LabelWidget? But they don't work like HTML <label>s yet
			$this->associated = new \OOUI\FieldLayout(
				new \OOUI\CheckboxInputWidget( array(
					'name' => $config['nameAssociated'],
					'selected' => $config['valueAssociated'],
					'value' => '1',
				) ),
				array(
					'align' => 'inline',
					'label' => $config['labelAssociated'],
				)
			);
		}
		if ( $config['nameInvert'] !== null ) {
			$this->invert = new \OOUI\FieldLayout(
				new \OOUI\CheckboxInputWidget( array(
					'name' => $config['nameInvert'],
					'selected' => $config['valueInvert'],
					'value' => '1',
				) ),
				array(
					'align' => 'inline',
					'label' => $config['labelInvert'],
				)
			);
		}

		// Initialization
		$this
			->addClasses( array( 'mw-widget-namespaceInputWidget' ) )
			->appendContent( $this->namespace, $this->associated, $this->invert );
	}

	protected function getNamespaceDropdownOptions( array $config ) {
		$namespaceOptionsParams = isset( $config['includeAllValue'] ) ?
			array( 'all' => $config['includeAllValue'] ) : array();
		$namespaceOptions = \Html::namespaceSelectorOptions( $namespaceOptionsParams );

		$options = array();
		foreach( $namespaceOptions as $id => $name ) {
			$options[] = array(
				'data' => (string)$id,
				'label' => $name,
			);
		}

		return $options;
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.NamespaceInputWidget';
	}

	public function getConfig( &$config ) {
		$config['namespace'] = $this->namespace;
		$config['associated'] = $this->associated;
		$config['invert'] = $this->invert;
		$config['allValue'] = $this->allValue;
		return parent::getConfig( $config );
	}
}
