<?php

/**
 * The parent class to generate form fields.  Any field type should
 * be a subclass of this.
 */
abstract class HTMLFormField {
	public $mParams;

	protected $mValidationCallback;
	protected $mFilterCallback;
	protected $mName;
	protected $mDir;
	protected $mLabel; # String label, as HTML. Set on construction.
	protected $mID;
	protected $mClass = '';
	protected $mVFormClass = '';
	protected $mHelpClass = false;
	protected $mDefault;
	protected $mOptions = false;
	protected $mOptionsLabelsNotFromMessage = false;
	protected $mHideIf = null;

	/**
	 * @var bool If true will generate an empty div element with no label
	 * @since 1.22
	 */
	protected $mShowEmptyLabels = true;

	/**
	 * @var HTMLForm|null
	 */
	public $mParent;

	/**
	 * This function must be implemented to return the HTML to generate
	 * the input object itself.  It should not implement the surrounding
	 * table cells/rows, or labels/help messages.
	 *
	 * @param string $value The value to set the input to; eg a default
	 *     text for a text input.
	 *
	 * @return string Valid HTML.
	 */
	abstract function getInputHTML( $value );

	/**
	 * Same as getInputHTML, but returns an OOUI object.
	 * Defaults to false, which getOOUI will interpret as "use the HTML version"
	 *
	 * @param string $value
	 * @return OOUI\Widget|false
	 */
	function getInputOOUI( $value ) {
		return false;
	}

	/**
	 * True if this field type is able to display errors; false if validation errors need to be
	 * displayed in the main HTMLForm error area.
	 * @return bool
	 */
	public function canDisplayErrors() {
		return true;
	}

	/**
	 * Get a translated interface message
	 *
	 * This is a wrapper around $this->mParent->msg() if $this->mParent is set
	 * and wfMessage() otherwise.
	 *
	 * Parameters are the same as wfMessage().
	 *
	 * @return Message
	 */
	function msg() {
		$args = func_get_args();

		if ( $this->mParent ) {
			$callback = [ $this->mParent, 'msg' ];
		} else {
			$callback = 'wfMessage';
		}

		return call_user_func_array( $callback, $args );
	}

	/**
	 * If this field has a user-visible output or not. If not,
	 * it will not be rendered
	 *
	 * @return bool
	 */
	public function hasVisibleOutput() {
		return true;
	}

	/**
	 * Fetch a field value from $alldata for the closest field matching a given
	 * name.
	 *
	 * This is complex because it needs to handle array fields like the user
	 * would expect. The general algorithm is to look for $name as a sibling
	 * of $this, then a sibling of $this's parent, and so on. Keeping in mind
	 * that $name itself might be referencing an array.
	 *
	 * @param array $alldata
	 * @param string $name
	 * @return string
	 */
	protected function getNearestFieldByName( $alldata, $name ) {
		$tmp = $this->mName;
		$thisKeys = [];
		while ( preg_match( '/^(.+)\[([^\]]+)\]$/', $tmp, $m ) ) {
			array_unshift( $thisKeys, $m[2] );
			$tmp = $m[1];
		}
		if ( substr( $tmp, 0, 2 ) == 'wp' &&
			!array_key_exists( $tmp, $alldata ) &&
			array_key_exists( substr( $tmp, 2 ), $alldata )
		) {
			// Adjust for name mangling.
			$tmp = substr( $tmp, 2 );
		}
		array_unshift( $thisKeys, $tmp );

		$tmp = $name;
		$nameKeys = [];
		while ( preg_match( '/^(.+)\[([^\]]+)\]$/', $tmp, $m ) ) {
			array_unshift( $nameKeys, $m[2] );
			$tmp = $m[1];
		}
		array_unshift( $nameKeys, $tmp );

		$testValue = '';
		for ( $i = count( $thisKeys ) - 1; $i >= 0; $i-- ) {
			$keys = array_merge( array_slice( $thisKeys, 0, $i ), $nameKeys );
			$data = $alldata;
			while ( $keys ) {
				$key = array_shift( $keys );
				if ( !is_array( $data ) || !array_key_exists( $key, $data ) ) {
					continue 2;
				}
				$data = $data[$key];
			}
			$testValue = (string)$data;
			break;
		}

		return $testValue;
	}

	/**
	 * Helper function for isHidden to handle recursive data structures.
	 *
	 * @param array $alldata
	 * @param array $params
	 * @return bool
	 * @throws MWException
	 */
	protected function isHiddenRecurse( array $alldata, array $params ) {
		$origParams = $params;
		$op = array_shift( $params );

		try {
			switch ( $op ) {
				case 'AND':
					foreach ( $params as $i => $p ) {
						if ( !is_array( $p ) ) {
							throw new MWException(
								"Expected array, found " . gettype( $p ) . " at index $i"
							);
						}
						if ( !$this->isHiddenRecurse( $alldata, $p ) ) {
							return false;
						}
					}
					return true;

				case 'OR':
					foreach ( $params as $i => $p ) {
						if ( !is_array( $p ) ) {
							throw new MWException(
								"Expected array, found " . gettype( $p ) . " at index $i"
							);
						}
						if ( $this->isHiddenRecurse( $alldata, $p ) ) {
							return true;
						}
					}
					return false;

				case 'NAND':
					foreach ( $params as $i => $p ) {
						if ( !is_array( $p ) ) {
							throw new MWException(
								"Expected array, found " . gettype( $p ) . " at index $i"
							);
						}
						if ( !$this->isHiddenRecurse( $alldata, $p ) ) {
							return true;
						}
					}
					return false;

				case 'NOR':
					foreach ( $params as $i => $p ) {
						if ( !is_array( $p ) ) {
							throw new MWException(
								"Expected array, found " . gettype( $p ) . " at index $i"
							);
						}
						if ( $this->isHiddenRecurse( $alldata, $p ) ) {
							return false;
						}
					}
					return true;

				case 'NOT':
					if ( count( $params ) !== 1 ) {
						throw new MWException( "NOT takes exactly one parameter" );
					}
					$p = $params[0];
					if ( !is_array( $p ) ) {
						throw new MWException(
							"Expected array, found " . gettype( $p ) . " at index 0"
						);
					}
					return !$this->isHiddenRecurse( $alldata, $p );

				case '===':
				case '!==':
					if ( count( $params ) !== 2 ) {
						throw new MWException( "$op takes exactly two parameters" );
					}
					list( $field, $value ) = $params;
					if ( !is_string( $field ) || !is_string( $value ) ) {
						throw new MWException( "Parameters for $op must be strings" );
					}
					$testValue = $this->getNearestFieldByName( $alldata, $field );
					switch ( $op ) {
						case '===':
							return ( $value === $testValue );
						case '!==':
							return ( $value !== $testValue );
					}

				default:
					throw new MWException( "Unknown operation" );
			}
		} catch ( Exception $ex ) {
			throw new MWException(
				"Invalid hide-if specification for $this->mName: " .
				$ex->getMessage() . " in " . var_export( $origParams, true ),
				0, $ex
			);
		}
	}

	/**
	 * Test whether this field is supposed to be hidden, based on the values of
	 * the other form fields.
	 *
	 * @since 1.23
	 * @param array $alldata The data collected from the form
	 * @return bool
	 */
	function isHidden( $alldata ) {
		if ( !$this->mHideIf ) {
			return false;
		}

		return $this->isHiddenRecurse( $alldata, $this->mHideIf );
	}

	/**
	 * Override this function if the control can somehow trigger a form
	 * submission that shouldn't actually submit the HTMLForm.
	 *
	 * @since 1.23
	 * @param string|array $value The value the field was submitted with
	 * @param array $alldata The data collected from the form
	 *
	 * @return bool True to cancel the submission
	 */
	function cancelSubmit( $value, $alldata ) {
		return false;
	}

	/**
	 * Override this function to add specific validation checks on the
	 * field input.  Don't forget to call parent::validate() to ensure
	 * that the user-defined callback mValidationCallback is still run
	 *
	 * @param string|array $value The value the field was submitted with
	 * @param array $alldata The data collected from the form
	 *
	 * @return bool|string True on success, or String error to display, or
	 *   false to fail validation without displaying an error.
	 */
	function validate( $value, $alldata ) {
		if ( $this->isHidden( $alldata ) ) {
			return true;
		}

		if ( isset( $this->mParams['required'] )
			&& $this->mParams['required'] !== false
			&& $value === ''
		) {
			return $this->msg( 'htmlform-required' )->parse();
		}

		if ( isset( $this->mValidationCallback ) ) {
			return call_user_func( $this->mValidationCallback, $value, $alldata, $this->mParent );
		}

		return true;
	}

	function filter( $value, $alldata ) {
		if ( isset( $this->mFilterCallback ) ) {
			$value = call_user_func( $this->mFilterCallback, $value, $alldata, $this->mParent );
		}

		return $value;
	}

	/**
	 * Should this field have a label, or is there no input element with the
	 * appropriate id for the label to point to?
	 *
	 * @return bool True to output a label, false to suppress
	 */
	protected function needsLabel() {
		return true;
	}

	/**
	 * Tell the field whether to generate a separate label element if its label
	 * is blank.
	 *
	 * @since 1.22
	 *
	 * @param bool $show Set to false to not generate a label.
	 * @return void
	 */
	public function setShowEmptyLabel( $show ) {
		$this->mShowEmptyLabels = $show;
	}

	/**
	 * Get the value that this input has been set to from a posted form,
	 * or the input's default value if it has not been set.
	 *
	 * @param WebRequest $request
	 * @return string The value
	 */
	function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {
			return $request->getText( $this->mName );
		} else {
			return $this->getDefault();
		}
	}

	/**
	 * Initialise the object
	 *
	 * @param array $params Associative Array. See HTMLForm doc for syntax.
	 *
	 * @since 1.22 The 'label' attribute no longer accepts raw HTML, use 'label-raw' instead
	 * @throws MWException
	 */
	function __construct( $params ) {
		$this->mParams = $params;

		if ( isset( $params['parent'] ) && $params['parent'] instanceof HTMLForm ) {
			$this->mParent = $params['parent'];
		}

		# Generate the label from a message, if possible
		if ( isset( $params['label-message'] ) ) {
			$this->mLabel = $this->getMessage( $params['label-message'] )->parse();
		} elseif ( isset( $params['label'] ) ) {
			if ( $params['label'] === '&#160;' ) {
				// Apparently some things set &nbsp directly and in an odd format
				$this->mLabel = '&#160;';
			} else {
				$this->mLabel = htmlspecialchars( $params['label'] );
			}
		} elseif ( isset( $params['label-raw'] ) ) {
			$this->mLabel = $params['label-raw'];
		}

		$this->mName = "wp{$params['fieldname']}";
		if ( isset( $params['name'] ) ) {
			$this->mName = $params['name'];
		}

		if ( isset( $params['dir'] ) ) {
			$this->mDir = $params['dir'];
		}

		$validName = Sanitizer::escapeId( $this->mName );
		$validName = str_replace( [ '.5B', '.5D' ], [ '[', ']' ], $validName );
		if ( $this->mName != $validName && !isset( $params['nodata'] ) ) {
			throw new MWException( "Invalid name '{$this->mName}' passed to " . __METHOD__ );
		}

		$this->mID = "mw-input-{$this->mName}";

		if ( isset( $params['default'] ) ) {
			$this->mDefault = $params['default'];
		}

		if ( isset( $params['id'] ) ) {
			$id = $params['id'];
			$validId = Sanitizer::escapeId( $id );

			if ( $id != $validId ) {
				throw new MWException( "Invalid id '$id' passed to " . __METHOD__ );
			}

			$this->mID = $id;
		}

		if ( isset( $params['cssclass'] ) ) {
			$this->mClass = $params['cssclass'];
		}

		if ( isset( $params['csshelpclass'] ) ) {
			$this->mHelpClass = $params['csshelpclass'];
		}

		if ( isset( $params['validation-callback'] ) ) {
			$this->mValidationCallback = $params['validation-callback'];
		}

		if ( isset( $params['filter-callback'] ) ) {
			$this->mFilterCallback = $params['filter-callback'];
		}

		if ( isset( $params['flatlist'] ) ) {
			$this->mClass .= ' mw-htmlform-flatlist';
		}

		if ( isset( $params['hidelabel'] ) ) {
			$this->mShowEmptyLabels = false;
		}

		if ( isset( $params['hide-if'] ) ) {
			$this->mHideIf = $params['hide-if'];
		}
	}

	/**
	 * Get the complete table row for the input, including help text,
	 * labels, and whatever.
	 *
	 * @param string $value The value to set the input to.
	 *
	 * @return string Complete HTML table row.
	 */
	function getTableRow( $value ) {
		list( $errors, $errorClass ) = $this->getErrorsAndErrorClass( $value );
		$inputHtml = $this->getInputHTML( $value );
		$fieldType = get_class( $this );
		$helptext = $this->getHelpTextHtmlTable( $this->getHelpText() );
		$cellAttributes = [];
		$rowAttributes = [];
		$rowClasses = '';

		if ( !empty( $this->mParams['vertical-label'] ) ) {
			$cellAttributes['colspan'] = 2;
			$verticalLabel = true;
		} else {
			$verticalLabel = false;
		}

		$label = $this->getLabelHtml( $cellAttributes );

		$field = Html::rawElement(
			'td',
			[ 'class' => 'mw-input' ] + $cellAttributes,
			$inputHtml . "\n$errors"
		);

		if ( $this->mHideIf ) {
			$rowAttributes['data-hide-if'] = FormatJson::encode( $this->mHideIf );
			$rowClasses .= ' mw-htmlform-hide-if';
		}

		if ( $verticalLabel ) {
			$html = Html::rawElement( 'tr',
				$rowAttributes + [ 'class' => "mw-htmlform-vertical-label $rowClasses" ], $label );
			$html .= Html::rawElement( 'tr',
				$rowAttributes + [
					'class' => "mw-htmlform-field-$fieldType {$this->mClass} $errorClass $rowClasses"
				],
				$field );
		} else {
			$html =
				Html::rawElement( 'tr',
					$rowAttributes + [
						'class' => "mw-htmlform-field-$fieldType {$this->mClass} $errorClass $rowClasses"
					],
					$label . $field );
		}

		return $html . $helptext;
	}

	/**
	 * Get the complete div for the input, including help text,
	 * labels, and whatever.
	 * @since 1.20
	 *
	 * @param string $value The value to set the input to.
	 *
	 * @return string Complete HTML table row.
	 */
	public function getDiv( $value ) {
		list( $errors, $errorClass ) = $this->getErrorsAndErrorClass( $value );
		$inputHtml = $this->getInputHTML( $value );
		$fieldType = get_class( $this );
		$helptext = $this->getHelpTextHtmlDiv( $this->getHelpText() );
		$cellAttributes = [];
		$label = $this->getLabelHtml( $cellAttributes );

		$outerDivClass = [
			'mw-input',
			'mw-htmlform-nolabel' => ( $label === '' )
		];

		$horizontalLabel = isset( $this->mParams['horizontal-label'] )
			? $this->mParams['horizontal-label'] : false;

		if ( $horizontalLabel ) {
			$field = '&#160;' . $inputHtml . "\n$errors";
		} else {
			$field = Html::rawElement(
				'div',
				[ 'class' => $outerDivClass ] + $cellAttributes,
				$inputHtml . "\n$errors"
			);
		}
		$divCssClasses = [ "mw-htmlform-field-$fieldType",
			$this->mClass, $this->mVFormClass, $errorClass ];

		$wrapperAttributes = [
			'class' => $divCssClasses,
		];
		if ( $this->mHideIf ) {
			$wrapperAttributes['data-hide-if'] = FormatJson::encode( $this->mHideIf );
			$wrapperAttributes['class'][] = ' mw-htmlform-hide-if';
		}
		$html = Html::rawElement( 'div', $wrapperAttributes, $label . $field );
		$html .= $helptext;

		return $html;
	}

	/**
	 * Get the OOUI version of the div. Falls back to getDiv by default.
	 * @since 1.26
	 *
	 * @param string $value The value to set the input to.
	 *
	 * @return OOUI\FieldLayout|OOUI\ActionFieldLayout
	 */
	public function getOOUI( $value ) {
		$inputField = $this->getInputOOUI( $value );

		if ( !$inputField ) {
			// This field doesn't have an OOUI implementation yet at all. Fall back to getDiv() to
			// generate the whole field, label and errors and all, then wrap it in a Widget.
			// It might look weird, but it'll work OK.
			return $this->getFieldLayoutOOUI(
				new OOUI\Widget( [ 'content' => new OOUI\HtmlSnippet( $this->getDiv( $value ) ) ] ),
				[ 'infusable' => false, 'align' => 'top' ]
			);
		}

		$infusable = true;
		if ( is_string( $inputField ) ) {
			// We have an OOUI implementation, but it's not proper, and we got a load of HTML.
			// Cheat a little and wrap it in a widget. It won't be infusable, though, since client-side
			// JavaScript doesn't know how to rebuilt the contents.
			$inputField = new OOUI\Widget( [ 'content' => new OOUI\HtmlSnippet( $inputField ) ] );
			$infusable = false;
		}

		$fieldType = get_class( $this );
		$helpText = $this->getHelpText();
		$errors = $this->getErrorsRaw( $value );
		foreach ( $errors as &$error ) {
			$error = new OOUI\HtmlSnippet( $error );
		}

		$config = [
			'classes' => [ "mw-htmlform-field-$fieldType", $this->mClass ],
			'align' => $this->getLabelAlignOOUI(),
			'help' => $helpText !== null ? new OOUI\HtmlSnippet( $helpText ) : null,
			'errors' => $errors,
			'infusable' => $infusable,
		];

		// the element could specify, that the label doesn't need to be added
		$label = $this->getLabel();
		if ( $label ) {
			$config['label'] = new OOUI\HtmlSnippet( $label );
		}

		return $this->getFieldLayoutOOUI( $inputField, $config );
	}

	/**
	 * Get label alignment when generating field for OOUI.
	 * @return string 'left', 'right', 'top' or 'inline'
	 */
	protected function getLabelAlignOOUI() {
		return 'top';
	}

	/**
	 * Get a FieldLayout (or subclass thereof) to wrap this field in when using OOUI output.
	 * @return OOUI\FieldLayout|OOUI\ActionFieldLayout
	 */
	protected function getFieldLayoutOOUI( $inputField, $config ) {
		if ( isset( $this->mClassWithButton ) ) {
			$buttonWidget = $this->mClassWithButton->getInputOOUI( '' );
			return new OOUI\ActionFieldLayout( $inputField, $buttonWidget, $config );
		}
		return new OOUI\FieldLayout( $inputField, $config );
	}

	/**
	 * Get the complete raw fields for the input, including help text,
	 * labels, and whatever.
	 * @since 1.20
	 *
	 * @param string $value The value to set the input to.
	 *
	 * @return string Complete HTML table row.
	 */
	public function getRaw( $value ) {
		list( $errors, ) = $this->getErrorsAndErrorClass( $value );
		$inputHtml = $this->getInputHTML( $value );
		$helptext = $this->getHelpTextHtmlRaw( $this->getHelpText() );
		$cellAttributes = [];
		$label = $this->getLabelHtml( $cellAttributes );

		$html = "\n$errors";
		$html .= $label;
		$html .= $inputHtml;
		$html .= $helptext;

		return $html;
	}

	/**
	 * Get the complete field for the input, including help text,
	 * labels, and whatever. Fall back from 'vform' to 'div' when not overridden.
	 *
	 * @since 1.25
	 * @param string $value The value to set the input to.
	 * @return string Complete HTML field.
	 */
	public function getVForm( $value ) {
		// Ewwww
		$this->mVFormClass = ' mw-ui-vform-field';
		return $this->getDiv( $value );
	}

	/**
	 * Get the complete field as an inline element.
	 * @since 1.25
	 * @param string $value The value to set the input to.
	 * @return string Complete HTML inline element
	 */
	public function getInline( $value ) {
		list( $errors, $errorClass ) = $this->getErrorsAndErrorClass( $value );
		$inputHtml = $this->getInputHTML( $value );
		$helptext = $this->getHelpTextHtmlDiv( $this->getHelpText() );
		$cellAttributes = [];
		$label = $this->getLabelHtml( $cellAttributes );

		$html = "\n" . $errors .
			$label . '&#160;' .
			$inputHtml .
			$helptext;

		return $html;
	}

	/**
	 * Generate help text HTML in table format
	 * @since 1.20
	 *
	 * @param string|null $helptext
	 * @return string
	 */
	public function getHelpTextHtmlTable( $helptext ) {
		if ( is_null( $helptext ) ) {
			return '';
		}

		$rowAttributes = [];
		if ( $this->mHideIf ) {
			$rowAttributes['data-hide-if'] = FormatJson::encode( $this->mHideIf );
			$rowAttributes['class'] = 'mw-htmlform-hide-if';
		}

		$tdClasses = [ 'htmlform-tip' ];
		if ( $this->mHelpClass !== false ) {
			$tdClasses[] = $this->mHelpClass;
		}
		$row = Html::rawElement( 'td', [ 'colspan' => 2, 'class' => $tdClasses ], $helptext );
		$row = Html::rawElement( 'tr', $rowAttributes, $row );

		return $row;
	}

	/**
	 * Generate help text HTML in div format
	 * @since 1.20
	 *
	 * @param string|null $helptext
	 *
	 * @return string
	 */
	public function getHelpTextHtmlDiv( $helptext ) {
		if ( is_null( $helptext ) ) {
			return '';
		}

		$wrapperAttributes = [
			'class' => 'htmlform-tip',
		];
		if ( $this->mHelpClass !== false ) {
			$wrapperAttributes['class'] .= " {$this->mHelpClass}";
		}
		if ( $this->mHideIf ) {
			$wrapperAttributes['data-hide-if'] = FormatJson::encode( $this->mHideIf );
			$wrapperAttributes['class'] .= ' mw-htmlform-hide-if';
		}
		$div = Html::rawElement( 'div', $wrapperAttributes, $helptext );

		return $div;
	}

	/**
	 * Generate help text HTML formatted for raw output
	 * @since 1.20
	 *
	 * @param string|null $helptext
	 * @return string
	 */
	public function getHelpTextHtmlRaw( $helptext ) {
		return $this->getHelpTextHtmlDiv( $helptext );
	}

	/**
	 * Determine the help text to display
	 * @since 1.20
	 * @return string HTML
	 */
	public function getHelpText() {
		$helptext = null;

		if ( isset( $this->mParams['help-message'] ) ) {
			$this->mParams['help-messages'] = [ $this->mParams['help-message'] ];
		}

		if ( isset( $this->mParams['help-messages'] ) ) {
			foreach ( $this->mParams['help-messages'] as $msg ) {
				$msg = $this->getMessage( $msg );

				if ( $msg->exists() ) {
					if ( is_null( $helptext ) ) {
						$helptext = '';
					} else {
						$helptext .= $this->msg( 'word-separator' )->escaped(); // some space
					}
					$helptext .= $msg->parse(); // Append message
				}
			}
		} elseif ( isset( $this->mParams['help'] ) ) {
			$helptext = $this->mParams['help'];
		}

		return $helptext;
	}

	/**
	 * Determine form errors to display and their classes
	 * @since 1.20
	 *
	 * @param string $value The value of the input
	 * @return array array( $errors, $errorClass )
	 */
	public function getErrorsAndErrorClass( $value ) {
		$errors = $this->validate( $value, $this->mParent->mFieldData );

		if ( is_bool( $errors ) || !$this->mParent->wasSubmitted() ) {
			$errors = '';
			$errorClass = '';
		} else {
			$errors = self::formatErrors( $errors );
			$errorClass = 'mw-htmlform-invalid-input';
		}

		return [ $errors, $errorClass ];
	}

	/**
	 * Determine form errors to display, returning them in an array.
	 *
	 * @since 1.26
	 * @param string $value The value of the input
	 * @return string[] Array of error HTML strings
	 */
	public function getErrorsRaw( $value ) {
		$errors = $this->validate( $value, $this->mParent->mFieldData );

		if ( is_bool( $errors ) || !$this->mParent->wasSubmitted() ) {
			$errors = [];
		}

		if ( !is_array( $errors ) ) {
			$errors = [ $errors ];
		}
		foreach ( $errors as &$error ) {
			if ( $error instanceof Message ) {
				$error = $error->parse();
			}
		}

		return $errors;
	}

	/**
	 * @return string HTML
	 */
	function getLabel() {
		return is_null( $this->mLabel ) ? '' : $this->mLabel;
	}

	function getLabelHtml( $cellAttributes = [] ) {
		# Don't output a for= attribute for labels with no associated input.
		# Kind of hacky here, possibly we don't want these to be <label>s at all.
		$for = [];

		if ( $this->needsLabel() ) {
			$for['for'] = $this->mID;
		}

		$labelValue = trim( $this->getLabel() );
		$hasLabel = false;
		if ( $labelValue !== '&#160;' && $labelValue !== '' ) {
			$hasLabel = true;
		}

		$displayFormat = $this->mParent->getDisplayFormat();
		$html = '';
		$horizontalLabel = isset( $this->mParams['horizontal-label'] )
			? $this->mParams['horizontal-label'] : false;

		if ( $displayFormat === 'table' ) {
			$html =
				Html::rawElement( 'td',
					[ 'class' => 'mw-label' ] + $cellAttributes,
					Html::rawElement( 'label', $for, $labelValue ) );
		} elseif ( $hasLabel || $this->mShowEmptyLabels ) {
			if ( $displayFormat === 'div' && !$horizontalLabel ) {
				$html =
					Html::rawElement( 'div',
						[ 'class' => 'mw-label' ] + $cellAttributes,
						Html::rawElement( 'label', $for, $labelValue ) );
			} else {
				$html = Html::rawElement( 'label', $for, $labelValue );
			}
		}

		return $html;
	}

	function getDefault() {
		if ( isset( $this->mDefault ) ) {
			return $this->mDefault;
		} else {
			return null;
		}
	}

	/**
	 * Returns the attributes required for the tooltip and accesskey.
	 *
	 * @return array Attributes
	 */
	public function getTooltipAndAccessKey() {
		if ( empty( $this->mParams['tooltip'] ) ) {
			return [];
		}

		return Linker::tooltipAndAccesskeyAttribs( $this->mParams['tooltip'] );
	}

	/**
	 * Returns the given attributes from the parameters
	 *
	 * @param array $list List of attributes to get
	 * @return array Attributes
	 */
	public function getAttributes( array $list ) {
		static $boolAttribs = [ 'disabled', 'required', 'autofocus', 'multiple', 'readonly' ];

		$ret = [];
		foreach ( $list as $key ) {
			if ( in_array( $key, $boolAttribs ) ) {
				if ( !empty( $this->mParams[$key] ) ) {
					$ret[$key] = '';
				}
			} elseif ( isset( $this->mParams[$key] ) ) {
				$ret[$key] = $this->mParams[$key];
			}
		}

		return $ret;
	}

	/**
	 * Given an array of msg-key => value mappings, returns an array with keys
	 * being the message texts. It also forces values to strings.
	 *
	 * @param array $options
	 * @return array
	 */
	private function lookupOptionsKeys( $options ) {
		$ret = [];
		foreach ( $options as $key => $value ) {
			$key = $this->msg( $key )->plain();
			$ret[$key] = is_array( $value )
				? $this->lookupOptionsKeys( $value )
				: strval( $value );
		}
		return $ret;
	}

	/**
	 * Recursively forces values in an array to strings, because issues arise
	 * with integer 0 as a value.
	 *
	 * @param array $array
	 * @return array
	 */
	static function forceToStringRecursive( $array ) {
		if ( is_array( $array ) ) {
			return array_map( [ __CLASS__, 'forceToStringRecursive' ], $array );
		} else {
			return strval( $array );
		}
	}

	/**
	 * Fetch the array of options from the field's parameters. In order, this
	 * checks 'options-messages', 'options', then 'options-message'.
	 *
	 * @return array|null Options array
	 */
	public function getOptions() {
		if ( $this->mOptions === false ) {
			if ( array_key_exists( 'options-messages', $this->mParams ) ) {
				$this->mOptions = $this->lookupOptionsKeys( $this->mParams['options-messages'] );
			} elseif ( array_key_exists( 'options', $this->mParams ) ) {
				$this->mOptionsLabelsNotFromMessage = true;
				$this->mOptions = self::forceToStringRecursive( $this->mParams['options'] );
			} elseif ( array_key_exists( 'options-message', $this->mParams ) ) {
				/** @todo This is copied from Xml::listDropDown(), deprecate/avoid duplication? */
				$message = $this->getMessage( $this->mParams['options-message'] )->inContentLanguage()->plain();

				$optgroup = false;
				$this->mOptions = [];
				foreach ( explode( "\n", $message ) as $option ) {
					$value = trim( $option );
					if ( $value == '' ) {
						continue;
					} elseif ( substr( $value, 0, 1 ) == '*' && substr( $value, 1, 1 ) != '*' ) {
						# A new group is starting...
						$value = trim( substr( $value, 1 ) );
						$optgroup = $value;
					} elseif ( substr( $value, 0, 2 ) == '**' ) {
						# groupmember
						$opt = trim( substr( $value, 2 ) );
						if ( $optgroup === false ) {
							$this->mOptions[$opt] = $opt;
						} else {
							$this->mOptions[$optgroup][$opt] = $opt;
						}
					} else {
						# groupless reason list
						$optgroup = false;
						$this->mOptions[$option] = $option;
					}
				}
			} else {
				$this->mOptions = null;
			}
		}

		return $this->mOptions;
	}

	/**
	 * Get options and make them into arrays suitable for OOUI.
	 * @return array Options for inclusion in a select or whatever.
	 */
	public function getOptionsOOUI() {
		$oldoptions = $this->getOptions();

		if ( $oldoptions === null ) {
			return null;
		}

		$options = [];

		foreach ( $oldoptions as $text => $data ) {
			$options[] = [
				'data' => (string)$data,
				'label' => (string)$text,
			];
		}

		return $options;
	}

	/**
	 * flatten an array of options to a single array, for instance,
	 * a set of "<options>" inside "<optgroups>".
	 *
	 * @param array $options Associative Array with values either Strings or Arrays
	 * @return array Flattened input
	 */
	public static function flattenOptions( $options ) {
		$flatOpts = [];

		foreach ( $options as $value ) {
			if ( is_array( $value ) ) {
				$flatOpts = array_merge( $flatOpts, self::flattenOptions( $value ) );
			} else {
				$flatOpts[] = $value;
			}
		}

		return $flatOpts;
	}

	/**
	 * Formats one or more errors as accepted by field validation-callback.
	 *
	 * @param string|Message|array $errors Array of strings or Message instances
	 * @return string HTML
	 * @since 1.18
	 */
	protected static function formatErrors( $errors ) {
		if ( is_array( $errors ) && count( $errors ) === 1 ) {
			$errors = array_shift( $errors );
		}

		if ( is_array( $errors ) ) {
			$lines = [];
			foreach ( $errors as $error ) {
				if ( $error instanceof Message ) {
					$lines[] = Html::rawElement( 'li', [], $error->parse() );
				} else {
					$lines[] = Html::rawElement( 'li', [], $error );
				}
			}

			return Html::rawElement( 'ul', [ 'class' => 'error' ], implode( "\n", $lines ) );
		} else {
			if ( $errors instanceof Message ) {
				$errors = $errors->parse();
			}

			return Html::rawElement( 'span', [ 'class' => 'error' ], $errors );
		}
	}

	/**
	 * Turns a *-message parameter (which could be a MessageSpecifier, or a message name, or a
	 * name + parameters array) into a Message.
	 * @param mixed $value
	 * @return Message
	 */
	protected function getMessage( $value ) {
		$message = Message::newFromSpecifier( $value );

		if ( $this->mParent ) {
			$message->setContext( $this->mParent );
		}

		return $message;
	}

	/**
	 * Skip this field when collecting data.
	 * @param WebRequest $request
	 * @return bool
	 * @since 1.27
	 */
	public function skipLoadData( $request ) {
		return !empty( $this->mParams['nodata'] );
	}
}
