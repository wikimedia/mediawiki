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
	protected $mLabel; # String label.  Set on construction
	protected $mID;
	protected $mClass = '';
	protected $mDefault;
	protected $mOptions = false;
	protected $mOptionsLabelsNotFromMessage = false;

	/**
	 * @var bool If true will generate an empty div element with no label
	 * @since 1.22
	 */
	protected $mShowEmptyLabels = true;

	/**
	 * @var HTMLForm
	 */
	public $mParent;

	/**
	 * This function must be implemented to return the HTML to generate
	 * the input object itself.  It should not implement the surrounding
	 * table cells/rows, or labels/help messages.
	 *
	 * @param string $value the value to set the input to; eg a default
	 *     text for a text input.
	 *
	 * @return string Valid HTML.
	 */
	abstract function getInputHTML( $value );

	/**
	 * Get a translated interface message
	 *
	 * This is a wrapper around $this->mParent->msg() if $this->mParent is set
	 * and wfMessage() otherwise.
	 *
	 * Parameters are the same as wfMessage().
	 *
	 * @return Message object
	 */
	function msg() {
		$args = func_get_args();

		if ( $this->mParent ) {
			$callback = array( $this->mParent, 'msg' );
		} else {
			$callback = 'wfMessage';
		}

		return call_user_func_array( $callback, $args );
	}

	/**
	 * Override this function to add specific validation checks on the
	 * field input.  Don't forget to call parent::validate() to ensure
	 * that the user-defined callback mValidationCallback is still run
	 *
	 * @param string $value The value the field was submitted with
	 * @param array $alldata The data collected from the form
	 *
	 * @return Mixed Bool true on success, or String error to display.
	 */
	function validate( $value, $alldata ) {
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
	 * @return String the value
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

		# Generate the label from a message, if possible
		if ( isset( $params['label-message'] ) ) {
			$msgInfo = $params['label-message'];

			if ( is_array( $msgInfo ) ) {
				$msg = array_shift( $msgInfo );
			} else {
				$msg = $msgInfo;
				$msgInfo = array();
			}

			$this->mLabel = wfMessage( $msg, $msgInfo )->parse();
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

		$validName = Sanitizer::escapeId( $this->mName );
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
		$cellAttributes = array();

		if ( !empty( $this->mParams['vertical-label'] ) ) {
			$cellAttributes['colspan'] = 2;
			$verticalLabel = true;
		} else {
			$verticalLabel = false;
		}

		$label = $this->getLabelHtml( $cellAttributes );

		$field = Html::rawElement(
			'td',
			array( 'class' => 'mw-input' ) + $cellAttributes,
			$inputHtml . "\n$errors"
		);

		if ( $verticalLabel ) {
			$html = Html::rawElement( 'tr', array( 'class' => 'mw-htmlform-vertical-label' ), $label );
			$html .= Html::rawElement( 'tr',
				array( 'class' => "mw-htmlform-field-$fieldType {$this->mClass} $errorClass" ),
				$field );
		} else {
			$html =
				Html::rawElement( 'tr',
					array( 'class' => "mw-htmlform-field-$fieldType {$this->mClass} $errorClass" ),
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
		$cellAttributes = array();
		$label = $this->getLabelHtml( $cellAttributes );

		$outerDivClass = array(
			'mw-input',
			'mw-htmlform-nolabel' => ( $label === '' )
		);

		$field = Html::rawElement(
			'div',
			array( 'class' => $outerDivClass ) + $cellAttributes,
			$inputHtml . "\n$errors"
		);
		$divCssClasses = array( "mw-htmlform-field-$fieldType", $this->mClass, $errorClass );
		if ( $this->mParent->isVForm() ) {
			$divCssClasses[] = 'mw-ui-vform-div';
		}
		$html = Html::rawElement( 'div', array( 'class' => $divCssClasses ), $label . $field );
		$html .= $helptext;

		return $html;
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
		$cellAttributes = array();
		$label = $this->getLabelHtml( $cellAttributes );

		$html = "\n$errors";
		$html .= $label;
		$html .= $inputHtml;
		$html .= $helptext;

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

		$row = Html::rawElement( 'td', array( 'colspan' => 2, 'class' => 'htmlform-tip' ), $helptext );
		$row = Html::rawElement( 'tr', array(), $row );

		return $row;
	}

	/**
	 * Generate help text HTML in div format
	 * @since 1.20
	 *
	 * @param string|null $helptext
	 *
	 * @return String
	 */
	public function getHelpTextHtmlDiv( $helptext ) {
		if ( is_null( $helptext ) ) {
			return '';
		}

		$div = Html::rawElement( 'div', array( 'class' => 'htmlform-tip' ), $helptext );

		return $div;
	}

	/**
	 * Generate help text HTML formatted for raw output
	 * @since 1.20
	 *
	 * @param string|null $helptext
	 * @return String
	 */
	public function getHelpTextHtmlRaw( $helptext ) {
		return $this->getHelpTextHtmlDiv( $helptext );
	}

	/**
	 * Determine the help text to display
	 * @since 1.20
	 * @return string
	 */
	public function getHelpText() {
		$helptext = null;

		if ( isset( $this->mParams['help-message'] ) ) {
			$this->mParams['help-messages'] = array( $this->mParams['help-message'] );
		}

		if ( isset( $this->mParams['help-messages'] ) ) {
			foreach ( $this->mParams['help-messages'] as $name ) {
				$helpMessage = (array)$name;
				$msg = $this->msg( array_shift( $helpMessage ), $helpMessage );

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
	 * @return array
	 */
	public function getErrorsAndErrorClass( $value ) {
		$errors = $this->validate( $value, $this->mParent->mFieldData );

		if ( $errors === true ||
			( !$this->mParent->getRequest()->wasPosted() && $this->mParent->getMethod() === 'post' )
		) {
			$errors = '';
			$errorClass = '';
		} else {
			$errors = self::formatErrors( $errors );
			$errorClass = 'mw-htmlform-invalid-input';
		}

		return array( $errors, $errorClass );
	}

	function getLabel() {
		return is_null( $this->mLabel ) ? '' : $this->mLabel;
	}

	function getLabelHtml( $cellAttributes = array() ) {
		# Don't output a for= attribute for labels with no associated input.
		# Kind of hacky here, possibly we don't want these to be <label>s at all.
		$for = array();

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

		if ( $displayFormat === 'table' ) {
			$html =
				Html::rawElement( 'td',
					array( 'class' => 'mw-label' ) + $cellAttributes,
					Html::rawElement( 'label', $for, $labelValue ) );
		} elseif ( $hasLabel || $this->mShowEmptyLabels ) {
			if ( $displayFormat === 'div' ) {
				$html =
					Html::rawElement( 'div',
						array( 'class' => 'mw-label' ) + $cellAttributes,
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
			return array();
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
		static $boolAttribs = array( 'disabled', 'required', 'autofocus', 'multiple', 'readonly' );

		$ret = array();

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
		$ret = array();
		foreach ( $options as $key => $value ) {
			$key = $this->msg( $key )->plain();
			$ret[$key] = is_array( $value )
				? $this->lookupOptionsKeys($field, $value)
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
			return array_map( array( __CLASS__, 'forceToStringRecursive' ), $array );
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
				$message = $this->msg( $this->mParams['options-message'] )->plain();

				$optgroup = false;
				$this->mOptions = array();
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
	 * flatten an array of options to a single array, for instance,
	 * a set of "<options>" inside "<optgroups>".
	 *
	 * @param array $options Associative Array with values either Strings
	 *     or Arrays
	 * @return array Flattened input
	 */
	public static function flattenOptions( $options ) {
		$flatOpts = array();

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
	 * @param string|Message|array $errors String|Message|Array of strings or Message instances
	 * @return string HTML
	 * @since 1.18
	 */
	protected static function formatErrors( $errors ) {
		if ( is_array( $errors ) && count( $errors ) === 1 ) {
			$errors = array_shift( $errors );
		}

		if ( is_array( $errors ) ) {
			$lines = array();
			foreach ( $errors as $error ) {
				if ( $error instanceof Message ) {
					$lines[] = Html::rawElement( 'li', array(), $error->parse() );
				} else {
					$lines[] = Html::rawElement( 'li', array(), $error );
				}
			}

			return Html::rawElement( 'ul', array( 'class' => 'error' ), implode( "\n", $lines ) );
		} else {
			if ( $errors instanceof Message ) {
				$errors = $errors->parse();
			}

			return Html::rawElement( 'span', array( 'class' => 'error' ), $errors );
		}
	}
}
