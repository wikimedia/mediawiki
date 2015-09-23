<?php

namespace OOUI;

/**
 * Layout made of a field, button and optional label.
 */
class ActionFieldLayout extends FieldLayout {

	/**
	 * Button widget to be laid out.
	 *
	 * @var Widget
	 */
	protected $buttonWidget;

	/**
	 * @param Widget $fieldWidget Field widget
	 * @param ButtonWidget $buttonWidget Field widget
	 * @param array $config Configuration options
	 */
	public function __construct( $fieldWidget, $buttonWidget = false, array $config = array() ) {
		// Allow passing positional parameters inside the config array
		if ( is_array( $fieldWidget ) && isset( $fieldWidget['fieldWidget'] ) ) {
			$config = $fieldWidget;
			$fieldWidget = $config['fieldWidget'];
			$buttonWidget = $config['buttonWidget'];
		}

		// Parent constructor
		parent::__construct( $fieldWidget, $config );

		// Properties
		$this->buttonWidget = $buttonWidget;
		$this->button = new Tag( 'div' );
		$this->input = new Tag( 'div' );

		// Initialization
		$this->addClasses( array( 'oo-ui-actionFieldLayout' ) );
		$this->button
			->addClasses( array( 'oo-ui-actionFieldLayout-button' ) )
			->appendContent( $this->buttonWidget );
		$this->input
			->addClasses( array( 'oo-ui-actionFieldLayout-input' ) )
			->appendContent( $this->fieldWidget );
		$this->field
			->clearContent()
			->appendContent( $this->input, $this->button );
	}

	public function getConfig( &$config ) {
		$config['buttonWidget'] = $this->buttonWidget;
		return parent::getConfig( $config );
	}
}
