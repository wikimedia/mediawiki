<?php

namespace OOUI;

/**
 * Layout made of a field and optional label.
 *
 * Available label alignment modes include:
 *  - left: Label is before the field and aligned away from it, best for when the user will be
 *    scanning for a specific label in a form with many fields
 *  - right: Label is before the field and aligned toward it, best for forms the user is very
 *    familiar with and will tab through field checking quickly to verify which field they are in
 *  - top: Label is before the field and above it, best for when the user will need to fill out all
 *    fields from top to bottom in a form with few fields
 *  - inline: Label is after the field and aligned toward it, best for small boolean fields like
 *    checkboxes or radio buttons
 */
class FieldLayout extends Layout {

	/**
	 * Alignment.
	 *
	 * @var string
	 */
	protected $align;

	/**
	 * Field widget to be laid out.
	 *
	 * @var Widget
	 */
	protected $fieldWidget;

	/**
	 * Error messages.
	 *
	 * @var array
	 */
	protected $errors;

	/**
	 * Notice messages.
	 *
	 * @var array
	 */
	protected $notices;

	/**
	 * @var ButtonWidget|string
	 */
	protected $help;

	protected $field, $body, $messages;

	/**
	 * @param Widget $fieldWidget Field widget
	 * @param array $config Configuration options
	 * @param string $config['align'] Alignment mode, either 'left', 'right', 'top' or 'inline'
	 *   (default: 'left')
	 * @param array $config['errors'] Error messages about the widget, as strings or HtmlSnippet
	 *   instances.
	 * @param array $config['notices'] Notices about the widget, as strings or HtmlSnippet instances.
	 * @param string|HtmlSnippet $config['help'] Explanatory text shown as a '?' icon.
	 * @throws Exception An exception is thrown if no widget is specified
	 */
	public function __construct( $fieldWidget, array $config = array() ) {
		// Allow passing positional parameters inside the config array
		if ( is_array( $fieldWidget ) && isset( $fieldWidget['fieldWidget'] ) ) {
			$config = $fieldWidget;
			$fieldWidget = $config['fieldWidget'];
		}

		// Make sure we have required constructor arguments
		if ( $fieldWidget === null ) {
			throw new Exception( 'Widget not found' );
		}

		$hasInputWidget = $fieldWidget::$supportsSimpleLabel;

		// Config initialization
		$config = array_merge( array( 'align' => 'left' ), $config );

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->fieldWidget = $fieldWidget;
		$this->errors = isset( $config['errors'] ) ? $config['errors'] : array();
		$this->notices = isset( $config['notices'] ) ? $config['notices'] : array();
		$this->field = new Tag( 'div' );
		$this->messages = new Tag( 'ul' );
		$this->body = new Tag( $hasInputWidget ? 'label' : 'div' );
		if ( isset( $config['help'] ) ) {
			$this->help = new ButtonWidget( array(
				'classes' => array( 'oo-ui-fieldLayout-help' ),
				'framed' => false,
				'icon' => 'info',
				'title' => $config['help'],
			) );
		} else {
			$this->help = '';
		}

		// Mixins
		$this->mixin( new LabelElement( $this, $config ) );
		$this->mixin( new TitledElement( $this,
			array_merge( $config, array( 'titled' => $this->label ) ) ) );

		// Initialization
		$this
			->addClasses( array( 'oo-ui-fieldLayout' ) )
			->appendContent( $this->help, $this->body );
		if ( count( $this->errors ) || count( $this->notices ) ) {
			$this->appendContent( $this->messages );
		}
		$this->body->addClasses( array( 'oo-ui-fieldLayout-body' ) );
		$this->messages->addClasses( array( 'oo-ui-fieldLayout-messages' ) );
		$this->field
			->addClasses( array( 'oo-ui-fieldLayout-field' ) )
			->toggleClasses( array( 'oo-ui-fieldLayout-disable' ), $this->fieldWidget->isDisabled() )
			->appendContent( $this->fieldWidget );

		foreach ( $this->notices as $text ) {
			$this->messages->appendContent( $this->makeMessage( 'notice', $text ) );
		}
		foreach ( $this->errors as $text ) {
			$this->messages->appendContent( $this->makeMessage( 'error', $text ) );
		}

		$this->setAlignment( $config['align'] );
	}

	/**
	 * @param string $kind 'error' or 'notice'
	 * @param string|HtmlSnippet $text
	 * @return Tag
	 */
	private function makeMessage( $kind, $text ) {
		$listItem = new Tag( 'li' );
		if ( $kind === 'error' ) {
			$icon = new IconWidget( array( 'icon' => 'alert', 'flags' => array( 'warning' ) ) );
		} elseif ( $kind === 'notice' ) {
			$icon = new IconWidget( array( 'icon' => 'info' ) );
		} else {
			$icon = null;
		}
		$message = new LabelWidget( array( 'label' => $text ) );
		$listItem
			->appendContent( $icon, $message )
			->addClasses( array( "oo-ui-fieldLayout-messages-$kind" ) );
		return $listItem;
	}

	/**
	 * Get the field.
	 *
	 * @return Widget Field widget
	 */
	public function getField() {
		return $this->fieldWidget;
	}

	/**
	 * Set the field alignment mode.
	 *
	 * @param string $value Alignment mode, either 'left', 'right', 'top' or 'inline'
	 * @chainable
	 */
	protected function setAlignment( $value ) {
		if ( $value !== $this->align ) {
			// Default to 'left'
			if ( !in_array( $value, array( 'left', 'right', 'top', 'inline' ) ) ) {
				$value = 'left';
			}
			// Reorder elements
			$this->body->clearContent();
			if ( $value === 'inline' ) {
				$this->body->appendContent( $this->field, $this->label );
			} else {
				$this->body->appendContent( $this->label, $this->field );
			}
			// Set classes. The following classes can be used here:
			// * oo-ui-fieldLayout-align-left
			// * oo-ui-fieldLayout-align-right
			// * oo-ui-fieldLayout-align-top
			// * oo-ui-fieldLayout-align-inline
			if ( $this->align ) {
				$this->removeClasses( array( 'oo-ui-fieldLayout-align-' . $this->align ) );
			}
			$this->addClasses( array( 'oo-ui-fieldLayout-align-' . $value ) );
			$this->align = $value;
		}

		return $this;
	}

	public function getConfig( &$config ) {
		$config['fieldWidget'] = $this->fieldWidget;
		$config['align'] = $this->align;
		$config['errors'] = $this->errors;
		$config['notices'] = $this->notices;
		if ( $this->help !== '' ) {
			$config['help'] = $this->help->getTitle();
		}
		return parent::getConfig( $config );
	}
}
