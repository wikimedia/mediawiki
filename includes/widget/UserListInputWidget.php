<?php
/**
 * MediaWiki Widgets – UserListInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * User input widget.
 */
class UserListInputWidget extends \OOUI\Widget {

	protected $config = array();
	protected $userinput = null;
	protected $userinputLabel = null;
	protected $groupinput = null;
	protected $groupinputLabel = null;
	protected $editsonlyCheck = null;
	protected $editsonlyCheckLabel = null;
	protected $creationsortCheck = null;
	protected $creationsortCheckLabel = null;
	protected $descsortCheck = null;
	protected $descsortCheckLabel = null;
	protected $sortWidget = null;

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = array() ) {
		// Configuration initialization
		$config = array_merge(
			array(
				// Config options for nested widgets
				'userinput' => array(),
				'userinputLabel' => array(),
				'groupinput' => array(),
				'groupinputLabel' => array(),
				'editsonlyCheck' => array(),
				'editsonlyCheckLabel' => array(),
				'creationsortCheck' => array(),
				'creationsortCheckLabel' => array(),
				'descsortCheck' => array(),
				'descsortCheckLabel' => array(),
			),
			$config
		);

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->config = $config;

		// there should always be a user input
		$this->userinput = new UserInputWidget( $config['userinput'] );
		// wrap it into a FieldLayout and add a label if provided
		$this->userinputLabel = new \OOUI\FieldLayout(
			$this->userinput,
			$config['userinputLabel']
		);

		// add a group dropdown field, if needed
		if ( $config['groupinput'] !== null ) {
			$this->groupinput = new \OOUI\DropdownInputWidget( $config['groupinput'] );

			$this->groupinputLabel = new \OOUI\FieldLayout(
				$this->groupinput,
				$config['groupinputLabel']
			);
		}

		// $checkboxItems hold all checkbox items that should be added under the input fields
		$checkboxItems = array();
		if ( $config['editsonlyCheck'] !== null ) {
			$this->editsonlyCheck = new \OOUI\CheckboxInputWidget( $config['editsonlyCheck'] );

			$this->editsonlyCheckLabel = $checkboxItems[] = new \OOUI\FieldLayout(
				$this->editsonlyCheck,
				array_merge(
					array( 'align' => 'inline' ),
					$config['editsonlyCheckLabel']
				)
			);
		}
		if ( $config['creationsortCheck'] !== null ) {
			$this->creationsortCheck = new \OOUI\CheckboxInputWidget( $config['creationsortCheck'] );

			$this->creationsortCheckLabel = $checkboxItems[] = new \OOUI\FieldLayout(
				$this->creationsortCheck,
				array_merge(
					array( 'align' => 'inline' ),
					$config['creationsortCheckLabel']
				)
			);
		}
		if ( $config['descsortCheck'] !== null ) {
			$this->descsortCheck = new \OOUI\CheckboxInputWidget( $config['descsortCheck'] );

			$this->creationsortCheckLabel = $checkboxItems[] = new \OOUI\FieldLayout(
				$this->descsortCheck,
				array_merge(
					array( 'align' => 'inline' ),
					$config['descsortCheckLabel']
				)
			);
		}

		// wrap the elements in $checkboxItems into it's own horizontal layout
		$this->sortWidget = new \OOUI\HorizontalLayout( array(
			'items' => $checkboxItems
		) );

		// add the content's to the widget
		$this
			// FIXME: This shouldn't have the complexNamespaceInputWidget
			->addClasses( array( 'mw-widget-complexNamespaceInputWidget' ) )
			->appendContent( $this->userinputLabel, $this->groupinputLabel, $this->sortWidget );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.UserListInputWidget';
	}
}
