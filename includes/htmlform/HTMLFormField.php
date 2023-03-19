<?php

namespace MediaWiki\HTMLForm;

use InvalidArgumentException;
use MediaWiki\Context\RequestContext;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLCheckField;
use MediaWiki\HTMLForm\Field\HTMLFormFieldCloner;
use MediaWiki\Json\FormatJson;
use MediaWiki\Linker\Linker;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Message\Message;
use MediaWiki\Request\WebRequest;
use MediaWiki\Status\Status;
use StatusValue;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

/**
 * The parent class to generate form fields.  Any field type should
 * be a subclass of this.
 *
 * @stable to extend
 */
abstract class HTMLFormField {
	/** @var array|array[] */
	public $mParams;

	/** @var callable(mixed,array,HTMLForm):(StatusValue|string|bool|Message)|null */
	protected $mValidationCallback;
	/** @var callable(mixed,array,HTMLForm):(StatusValue|string|bool|Message)|null */
	protected $mFilterCallback;
	/** @var string */
	protected $mName;
	/** @var string */
	protected $mDir;
	/** @var string String label, as HTML. Set on construction. */
	protected $mLabel;
	/** @var string */
	protected $mID;
	/** @var string */
	protected $mClass = '';
	/** @var string */
	protected $mVFormClass = '';
	/** @var string|false */
	protected $mHelpClass = false;
	/** @var mixed */
	protected $mDefault;
	/** @var array */
	private $mNotices;

	/**
	 * @var array|null|false
	 */
	protected $mOptions = false;
	/** @var bool */
	protected $mOptionsLabelsNotFromMessage = false;
	/**
	 * @var array Array to hold params for 'hide-if' or 'disable-if' statements
	 */
	protected $mCondState = [];
	/** @var array */
	protected $mCondStateClass = [];

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
	 * @param mixed $value The value to set the input to; eg a default
	 *     text for a text input.
	 *
	 * @return string Valid HTML.
	 */
	abstract public function getInputHTML( $value );

	/**
	 * Same as getInputHTML, but returns an OOUI object.
	 * Defaults to false, which getOOUI will interpret as "use the HTML version"
	 * @stable to override
	 *
	 * @param string $value
	 * @return \OOUI\Widget|string|false
	 */
	public function getInputOOUI( $value ) {
		return false;
	}

	/**
	 * Same as getInputHTML, but for Codex. This is called by CodexHTMLForm.
	 *
	 * If not overridden, falls back to getInputHTML.
	 *
	 * @param string $value The value to set the input to
	 * @param bool $hasErrors Whether there are validation errors. If set to true, this method
	 *   should apply a CSS class for the error status (e.g. cdx-text-input--status-error)
	 *   if the component used supports that.
	 * @return string HTML
	 */
	public function getInputCodex( $value, $hasErrors ) {
		// If not overridden, fall back to getInputHTML()
		return $this->getInputHTML( $value );
	}

	/**
	 * True if this field type is able to display errors; false if validation errors need to be
	 * displayed in the main HTMLForm error area.
	 * @stable to override
	 * @return bool
	 */
	public function canDisplayErrors() {
		return $this->hasVisibleOutput();
	}

	/**
	 * Get a translated interface message
	 *
	 * This is a wrapper around $this->mParent->msg() if $this->mParent is set
	 * and wfMessage() otherwise.
	 *
	 * Parameters are the same as wfMessage().
	 *
	 * @param string|string[]|MessageSpecifier $key
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return Message
	 */
	public function msg( $key, ...$params ) {
		if ( $this->mParent ) {
			return $this->mParent->msg( $key, ...$params );
		}
		return wfMessage( $key, ...$params );
	}

	/**
	 * If this field has a user-visible output or not. If not,
	 * it will not be rendered
	 * @stable to override
	 *
	 * @return bool
	 */
	public function hasVisibleOutput() {
		return true;
	}

	/**
	 * Get the field name that will be used for submission.
	 *
	 * @since 1.38
	 * @return string
	 */
	public function getName() {
		return $this->mName;
	}

	/**
	 * Get the closest field matching a given name.
	 *
	 * It can handle array fields like the user would expect. The general
	 * algorithm is to look for $name as a sibling of $this, then a sibling
	 * of $this's parent, and so on.
	 *
	 * @param string $name
	 * @param bool $backCompat Whether to try striping the 'wp' prefix.
	 * @return HTMLFormField
	 */
	protected function getNearestField( $name, $backCompat = false ) {
		// When the field is belong to a HTMLFormFieldCloner
		$cloner = $this->mParams['cloner'] ?? null;
		if ( $cloner instanceof HTMLFormFieldCloner ) {
			$field = $cloner->findNearestField( $this, $name );
			if ( $field ) {
				return $field;
			}
		}

		if ( $backCompat && str_starts_with( $name, 'wp' ) &&
			!$this->mParent->hasField( $name )
		) {
			// Don't break the existed use cases.
			return $this->mParent->getField( substr( $name, 2 ) );
		}
		return $this->mParent->getField( $name );
	}

	/**
	 * Fetch a field value from $alldata for the closest field matching a given
	 * name.
	 *
	 * @param array $alldata
	 * @param string $name
	 * @param bool $asDisplay Whether the reverting logic of HTMLCheckField
	 *     should be ignored.
	 * @param bool $backCompat Whether to try striping the 'wp' prefix.
	 * @return mixed
	 */
	protected function getNearestFieldValue( $alldata, $name, $asDisplay = false, $backCompat = false ) {
		$field = $this->getNearestField( $name, $backCompat );
		// When the field belongs to a HTMLFormFieldCloner
		$cloner = $field->mParams['cloner'] ?? null;
		if ( $cloner instanceof HTMLFormFieldCloner ) {
			$value = $cloner->extractFieldData( $field, $alldata );
		} else {
			// Note $alldata is an empty array when first rendering a form with a formIdentifier.
			// In that case, $alldata[$field->mParams['fieldname']] is unset and we use the
			// field's default value
			$value = $alldata[$field->mParams['fieldname']] ?? $field->getDefault();
		}

		// Check invert state for HTMLCheckField
		if ( $asDisplay && $field instanceof HTMLCheckField && ( $field->mParams['invert'] ?? false ) ) {
			$value = !$value;
		}

		return $value;
	}

	/**
	 * Fetch a field value from $alldata for the closest field matching a given
	 * name.
	 *
	 * @deprecated since 1.38 Use getNearestFieldValue() instead.
	 * @param array $alldata
	 * @param string $name
	 * @param bool $asDisplay
	 * @return string
	 */
	protected function getNearestFieldByName( $alldata, $name, $asDisplay = false ) {
		return (string)$this->getNearestFieldValue( $alldata, $name, $asDisplay );
	}

	/**
	 * Validate the cond-state params, the existence check of fields should
	 * be done later.
	 *
	 * @param array $params
	 */
	protected function validateCondState( $params ) {
		$origParams = $params;
		$op = array_shift( $params );

		$makeException = function ( string $details ) use ( $origParams ): InvalidArgumentException {
			return new InvalidArgumentException(
				"Invalid hide-if or disable-if specification for $this->mName: " .
				$details . " in " . var_export( $origParams, true )
			);
		};

		switch ( $op ) {
			case 'NOT':
				if ( count( $params ) !== 1 ) {
					throw $makeException( "NOT takes exactly one parameter" );
				}
				// Fall-through intentionally

			case 'AND':
			case 'OR':
			case 'NAND':
			case 'NOR':
				foreach ( $params as $i => $p ) {
					if ( !is_array( $p ) ) {
						$type = get_debug_type( $p );
						throw $makeException( "Expected array, found $type at index $i" );
					}
					$this->validateCondState( $p );
				}
				break;

			case '===':
			case '!==':
				if ( count( $params ) !== 2 ) {
					throw $makeException( "$op takes exactly two parameters" );
				}
				[ $name, $value ] = $params;
				if ( !is_string( $name ) || !is_string( $value ) ) {
					throw $makeException( "Parameters for $op must be strings" );
				}
				break;

			default:
				throw $makeException( "Unknown operation" );
		}
	}

	/**
	 * Helper function for isHidden and isDisabled to handle recursive data structures.
	 *
	 * @param array $alldata
	 * @param array $params
	 * @return bool
	 */
	protected function checkStateRecurse( array $alldata, array $params ) {
		$op = array_shift( $params );
		$valueChk = [ 'AND' => false, 'OR' => true, 'NAND' => false, 'NOR' => true ];
		$valueRet = [ 'AND' => true, 'OR' => false, 'NAND' => false, 'NOR' => true ];

		switch ( $op ) {
			case 'AND':
			case 'OR':
			case 'NAND':
			case 'NOR':
				foreach ( $params as $p ) {
					if ( $valueChk[$op] === $this->checkStateRecurse( $alldata, $p ) ) {
						return !$valueRet[$op];
					}
				}
				return $valueRet[$op];

			case 'NOT':
				return !$this->checkStateRecurse( $alldata, $params[0] );

			case '===':
			case '!==':
				[ $field, $value ] = $params;
				$testValue = (string)$this->getNearestFieldValue( $alldata, $field, true, true );
				switch ( $op ) {
					case '===':
						return ( $value === $testValue );
					case '!==':
						return ( $value !== $testValue );
				}
		}
	}

	/**
	 * Parse the cond-state array to use the field name for submission, since
	 * the key in the form descriptor is never known in HTML. Also check for
	 * field existence here.
	 *
	 * @param array $params
	 * @return mixed[]
	 */
	protected function parseCondState( $params ) {
		$op = array_shift( $params );

		switch ( $op ) {
			case 'AND':
			case 'OR':
			case 'NAND':
			case 'NOR':
				$ret = [ $op ];
				foreach ( $params as $p ) {
					$ret[] = $this->parseCondState( $p );
				}
				return $ret;

			case 'NOT':
				return [ 'NOT', $this->parseCondState( $params[0] ) ];

			case '===':
			case '!==':
				[ $name, $value ] = $params;
				$field = $this->getNearestField( $name, true );
				return [ $op, $field->getName(), $value ];
		}
	}

	/**
	 * Parse the cond-state array for client-side.
	 *
	 * @return array[]
	 */
	protected function parseCondStateForClient() {
		$parsed = [];
		foreach ( $this->mCondState as $type => $params ) {
			$parsed[$type] = $this->parseCondState( $params );
		}
		return $parsed;
	}

	/**
	 * Test whether this field is supposed to be hidden, based on the values of
	 * the other form fields.
	 *
	 * @since 1.23
	 * @param array $alldata The data collected from the form
	 * @return bool
	 */
	public function isHidden( $alldata ) {
		return isset( $this->mCondState['hide'] ) &&
			$this->checkStateRecurse( $alldata, $this->mCondState['hide'] );
	}

	/**
	 * Test whether this field is supposed to be disabled, based on the values of
	 * the other form fields.
	 *
	 * @since 1.38
	 * @param array $alldata The data collected from the form
	 * @return bool
	 */
	public function isDisabled( $alldata ) {
		return ( $this->mParams['disabled'] ?? false ) ||
			$this->isHidden( $alldata ) ||
			( isset( $this->mCondState['disable'] )
				&& $this->checkStateRecurse( $alldata, $this->mCondState['disable'] ) );
	}

	/**
	 * Override this function if the control can somehow trigger a form
	 * submission that shouldn't actually submit the HTMLForm.
	 *
	 * @stable to override
	 * @since 1.23
	 * @param string|array $value The value the field was submitted with
	 * @param array $alldata The data collected from the form
	 *
	 * @return bool True to cancel the submission
	 */
	public function cancelSubmit( $value, $alldata ) {
		return false;
	}

	/**
	 * Override this function to add specific validation checks on the
	 * field input.  Don't forget to call parent::validate() to ensure
	 * that the user-defined callback mValidationCallback is still run
	 * @stable to override
	 *
	 * @param mixed $value The value the field was submitted with
	 * @param array $alldata The data collected from the form
	 *
	 * @return bool|string|Message True on success, or String/Message error to display, or
	 *   false to fail validation without displaying an error.
	 */
	public function validate( $value, $alldata ) {
		if ( $this->isHidden( $alldata ) ) {
			return true;
		}

		if ( isset( $this->mParams['required'] )
			&& $this->mParams['required'] !== false
			&& ( $value === '' || $value === false || $value === null )
		) {
			return $this->msg( 'htmlform-required' );
		}

		if ( $this->mValidationCallback === null ) {
			return true;
		}

		$p = ( $this->mValidationCallback )( $value, $alldata, $this->mParent );

		if ( $p instanceof StatusValue ) {
			$language = $this->mParent ? $this->mParent->getLanguage() : RequestContext::getMain()->getLanguage();

			return $p->isGood() ? true : Status::wrap( $p )->getHTML( false, false, $language );
		}

		return $p;
	}

	/**
	 * @stable to override
	 *
	 * @param mixed $value
	 * @param mixed[] $alldata
	 *
	 * @return mixed
	 */
	public function filter( $value, $alldata ) {
		if ( $this->mFilterCallback !== null ) {
			$value = ( $this->mFilterCallback )( $value, $alldata, $this->mParent );
		}

		return $value;
	}

	/**
	 * Should this field have a label, or is there no input element with the
	 * appropriate id for the label to point to?
	 * @stable to override
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
	 * Can we assume that the request is an attempt to submit a HTMLForm, as opposed to an attempt to
	 * just view it? This can't normally be distinguished for e.g. checkboxes.
	 *
	 * Returns true if the request was posted and has a field for a CSRF token (wpEditToken), or
	 * has a form identifier (wpFormIdentifier).
	 *
	 * @todo Consider moving this to HTMLForm?
	 * @param WebRequest $request
	 * @return bool
	 */
	protected function isSubmitAttempt( WebRequest $request ) {
		// HTMLForm would add a hidden field of edit token for forms that require to be posted.
		return ( $request->wasPosted() && $request->getCheck( 'wpEditToken' ) )
			// The identifier matching or not has been checked in HTMLForm::prepareForm()
			|| $request->getCheck( 'wpFormIdentifier' );
	}

	/**
	 * Get the value that this input has been set to from a posted form,
	 * or the input's default value if it has not been set.
	 * @stable to override
	 *
	 * @param WebRequest $request
	 * @return mixed The value
	 */
	public function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {
			return $request->getText( $this->mName );
		} else {
			return $this->getDefault();
		}
	}

	/**
	 * Initialise the object
	 *
	 * @stable to call
	 * @param array $params Associative Array. See HTMLForm doc for syntax.
	 *
	 * @since 1.22 The 'label' attribute no longer accepts raw HTML, use 'label-raw' instead
	 */
	public function __construct( $params ) {
		$this->mParams = $params;

		if ( isset( $params['parent'] ) && $params['parent'] instanceof HTMLForm ) {
			$this->mParent = $params['parent'];
		} else {
			// Normally parent is added automatically by HTMLForm::factory.
			// Several field types already assume unconditionally this is always set,
			// so deprecate manually creating an HTMLFormField without a parent form set.
			wfDeprecatedMsg(
				__METHOD__ . ": Constructing an HTMLFormField without a 'parent' parameter",
				"1.40"
			);
		}

		# Generate the label from a message, if possible
		if ( isset( $params['label-message'] ) ) {
			$this->mLabel = $this->getMessage( $params['label-message'] )->parse();
		} elseif ( isset( $params['label'] ) ) {
			if ( $params['label'] === '&#160;' || $params['label'] === "\u{00A0}" ) {
				// Apparently some things set &nbsp directly and in an odd format
				$this->mLabel = "\u{00A0}";
			} else {
				$this->mLabel = htmlspecialchars( $params['label'] );
			}
		} elseif ( isset( $params['label-raw'] ) ) {
			$this->mLabel = $params['label-raw'];
		}

		$this->mName = $params['name'] ?? 'wp' . $params['fieldname'];

		if ( isset( $params['dir'] ) ) {
			$this->mDir = $params['dir'];
		}

		$this->mID = "mw-input-{$this->mName}";

		if ( isset( $params['default'] ) ) {
			$this->mDefault = $params['default'];
		}

		if ( isset( $params['id'] ) ) {
			$this->mID = $params['id'];
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

		if ( isset( $params['hidelabel'] ) ) {
			$this->mShowEmptyLabels = false;
		}
		if ( isset( $params['notices'] ) ) {
			$this->mNotices = $params['notices'];
		}

		if ( isset( $params['hide-if'] ) && $params['hide-if'] ) {
			$this->validateCondState( $params['hide-if'] );
			$this->mCondState['hide'] = $params['hide-if'];
			$this->mCondStateClass[] = 'mw-htmlform-hide-if';
		}
		if ( !( isset( $params['disabled'] ) && $params['disabled'] ) &&
			isset( $params['disable-if'] ) && $params['disable-if']
		) {
			$this->validateCondState( $params['disable-if'] );
			$this->mCondState['disable'] = $params['disable-if'];
			$this->mCondStateClass[] = 'mw-htmlform-disable-if';
		}
	}

	/**
	 * Get the complete table row for the input, including help text,
	 * labels, and whatever.
	 * @stable to override
	 *
	 * @param string $value The value to set the input to.
	 *
	 * @return string Complete HTML table row.
	 */
	public function getTableRow( $value ) {
		[ $errors, $errorClass ] = $this->getErrorsAndErrorClass( $value );
		$inputHtml = $this->getInputHTML( $value );
		$fieldType = $this->getClassName();
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

		if ( $this->mCondState ) {
			$rowAttributes['data-cond-state'] = FormatJson::encode( $this->parseCondStateForClient() );
			$rowClasses .= implode( ' ', $this->mCondStateClass );
			if ( $this->isHidden( $this->mParent->mFieldData ) ) {
				$rowClasses .= ' mw-htmlform-hide-if-hidden';
			}
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
			$html = Html::rawElement( 'tr',
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
	 * @stable to override
	 * @since 1.20
	 *
	 * @param string $value The value to set the input to.
	 *
	 * @return string Complete HTML table row.
	 */
	public function getDiv( $value ) {
		[ $errors, $errorClass ] = $this->getErrorsAndErrorClass( $value );
		$inputHtml = $this->getInputHTML( $value );
		$fieldType = $this->getClassName();
		$helptext = $this->getHelpTextHtmlDiv( $this->getHelpText() );
		$cellAttributes = [];
		$label = $this->getLabelHtml( $cellAttributes );

		$outerDivClass = [
			'mw-input',
			'mw-htmlform-nolabel' => ( $label === '' )
		];

		$horizontalLabel = $this->mParams['horizontal-label'] ?? false;

		if ( $horizontalLabel ) {
			$field = "\u{00A0}" . $inputHtml . "\n$errors";
		} else {
			$field = Html::rawElement(
				'div',
				// @phan-suppress-next-line PhanUselessBinaryAddRight
				[ 'class' => $outerDivClass ] + $cellAttributes,
				$inputHtml . "\n$errors"
			);
		}

		$wrapperAttributes = [ 'class' => [
			"mw-htmlform-field-$fieldType",
			$this->mClass,
			$this->mVFormClass,
			$errorClass,
		] ];
		if ( $this->mCondState ) {
			$wrapperAttributes['data-cond-state'] = FormatJson::encode( $this->parseCondStateForClient() );
			$wrapperAttributes['class'] = array_merge( $wrapperAttributes['class'], $this->mCondStateClass );
			if ( $this->isHidden( $this->mParent->mFieldData ) ) {
				$wrapperAttributes['class'][] = 'mw-htmlform-hide-if-hidden';
			}
		}
		return Html::rawElement( 'div', $wrapperAttributes, $label . $field ) .
			$helptext;
	}

	/**
	 * Get the OOUI version of the div. Falls back to getDiv by default.
	 * @stable to override
	 * @since 1.26
	 *
	 * @param string $value The value to set the input to.
	 *
	 * @return \OOUI\FieldLayout
	 */
	public function getOOUI( $value ) {
		$inputField = $this->getInputOOUI( $value );

		if ( !$inputField ) {
			// This field doesn't have an OOUI implementation yet at all. Fall back to getDiv() to
			// generate the whole field, label and errors and all, then wrap it in a Widget.
			// It might look weird, but it'll work OK.
			return $this->getFieldLayoutOOUI(
				new \OOUI\Widget( [ 'content' => new \OOUI\HtmlSnippet( $this->getDiv( $value ) ) ] ),
				[ 'align' => 'top' ]
			);
		}

		$infusable = true;
		if ( is_string( $inputField ) ) {
			// We have an OOUI implementation, but it's not proper, and we got a load of HTML.
			// Cheat a little and wrap it in a widget. It won't be infusable, though, since client-side
			// JavaScript doesn't know how to rebuilt the contents.
			$inputField = new \OOUI\Widget( [ 'content' => new \OOUI\HtmlSnippet( $inputField ) ] );
			$infusable = false;
		}

		$fieldType = $this->getClassName();
		$help = $this->getHelpText();
		$errors = $this->getErrorsRaw( $value );
		foreach ( $errors as &$error ) {
			$error = new \OOUI\HtmlSnippet( $error );
		}

		$config = [
			'classes' => [ "mw-htmlform-field-$fieldType" ],
			'align' => $this->getLabelAlignOOUI(),
			'help' => ( $help !== null && $help !== '' ) ? new \OOUI\HtmlSnippet( $help ) : null,
			'errors' => $errors,
			'infusable' => $infusable,
			'helpInline' => $this->isHelpInline(),
			'notices' => $this->mNotices ?: [],
		];
		if ( $this->mClass !== '' ) {
			$config['classes'][] = $this->mClass;
		}

		$preloadModules = false;

		if ( $infusable && $this->shouldInfuseOOUI() ) {
			$preloadModules = true;
			$config['classes'][] = 'mw-htmlform-autoinfuse';
		}
		if ( $this->mCondState ) {
			$config['classes'] = array_merge( $config['classes'], $this->mCondStateClass );
			if ( $this->isHidden( $this->mParent->mFieldData ) ) {
				$config['classes'][] = 'mw-htmlform-hide-if-hidden';
			}
		}

		// the element could specify, that the label doesn't need to be added
		$label = $this->getLabel();
		if ( $label && $label !== "\u{00A0}" && $label !== '&#160;' ) {
			$config['label'] = new \OOUI\HtmlSnippet( $label );
		}

		if ( $this->mCondState ) {
			$preloadModules = true;
			$config['condState'] = $this->parseCondStateForClient();
		}

		$config['modules'] = $this->getOOUIModules();

		if ( $preloadModules ) {
			$this->mParent->getOutput()->addModules( 'mediawiki.htmlform.ooui' );
			$this->mParent->getOutput()->addModules( $this->getOOUIModules() );
		}

		return $this->getFieldLayoutOOUI( $inputField, $config );
	}

	/**
	 * Get the Codex version of the div.
	 * @since 1.42
	 *
	 * @param string $value The value to set the input to.
	 * @return string HTML
	 */
	public function getCodex( $value ) {
		$isDisabled = ( $this->mParams['disabled'] ?? false );

		// Label
		$labelDiv = '';
		$labelValue = trim( $this->getLabel() );
		// For weird historical reasons, a non-breaking space is treated as an empty label
		// Check for both a literal nbsp ("\u{00A0}") and the HTML-encoded version
		if ( $labelValue !== '' && $labelValue !== "\u{00A0}" && $labelValue !== '&#160;' ) {
			$labelFor = $this->needsLabel() ? [ 'for' => $this->mID ] : [];
			$labelClasses = [ 'cdx-label' ];
			if ( $isDisabled ) {
				$labelClasses[] = 'cdx-label--disabled';
			}
			// <div class="cdx-label">
			$labelDiv = Html::rawElement( 'div', [ 'class' => $labelClasses ],
				// <label class="cdx-label__label" for="ID">
				Html::rawElement( 'label', [ 'class' => 'cdx-label__label' ] + $labelFor,
					// <span class="cdx-label__label__text">
					Html::rawElement( 'span', [ 'class' => 'cdx-label__label__text' ],
						$labelValue
					)
				)
			);
		}

		// Help text
		// <div class="cdx-field__help-text">
		$helptext = $this->getHelpTextHtmlDiv( $this->getHelpText(), [ 'cdx-field__help-text' ] );

		// Validation message
		// <div class="cdx-field__validation-message">
		// $errors is a <div class="cdx-message">
		// FIXME right now this generates a block message (cdx-message--block), we want an inline message instead
		$validationMessage = '';
		[ $errors, $errorClass ] = $this->getErrorsAndErrorClass( $value );
		if ( $errors !== '' ) {
			$validationMessage = Html::rawElement( 'div', [ 'class' => 'cdx-field__validation-message' ],
				$errors
			);
		}

		// Control
		$inputHtml = $this->getInputCodex( $value, $errors !== '' );
		// <div class="cdx-field__control cdx-field__control--has-help-text">
		$controlClasses = [ 'cdx-field__control' ];
		if ( $helptext ) {
			$controlClasses[] = 'cdx-field__control--has-help-text';
		}
		$control = Html::rawElement( 'div', [ 'class' => $controlClasses ], $inputHtml );

		// <div class="cdx-field">
		$fieldClasses = [
			"mw-htmlform-field-{$this->getClassName()}",
			$this->mClass,
			$errorClass,
			'cdx-field'
		];
		if ( $isDisabled ) {
			$fieldClasses[] = 'cdx-field--disabled';
		}
		$fieldAttributes = [];
		// Set data attribute and CSS class for client side handling of hide-if / disable-if
		if ( $this->mCondState ) {
			$fieldAttributes['data-cond-state'] = FormatJson::encode( $this->parseCondStateForClient() );
			$fieldClasses = array_merge( $fieldClasses, $this->mCondStateClass );
			if ( $this->isHidden( $this->mParent->mFieldData ) ) {
				$fieldClasses[] = 'mw-htmlform-hide-if-hidden';
			}
		}

		return Html::rawElement( 'div', [ 'class' => $fieldClasses ] + $fieldAttributes,
			$labelDiv . $control . $helptext . $validationMessage
		);
	}

	/**
	 * Gets the non namespaced class name
	 *
	 * @since 1.36
	 *
	 * @return string
	 */
	protected function getClassName() {
		$name = explode( '\\', static::class );
		return end( $name );
	}

	/**
	 * Get label alignment when generating field for OOUI.
	 * @stable to override
	 * @return string 'left', 'right', 'top' or 'inline'
	 */
	protected function getLabelAlignOOUI() {
		return 'top';
	}

	/**
	 * Get a FieldLayout (or subclass thereof) to wrap this field in when using OOUI output.
	 * @param \OOUI\Widget $inputField
	 * @param array $config
	 * @return \OOUI\FieldLayout
	 */
	protected function getFieldLayoutOOUI( $inputField, $config ) {
		return new HTMLFormFieldLayout( $inputField, $config );
	}

	/**
	 * Whether the field should be automatically infused. Note that all OOUI HTMLForm fields are
	 * infusable (you can call OO.ui.infuse() on them), but not all are infused by default, since
	 * there is no benefit in doing it e.g. for buttons and it's a small performance hit on page load.
	 * @stable to override
	 *
	 * @return bool
	 */
	protected function shouldInfuseOOUI() {
		// Always infuse fields with popup help text, since the interface for it is nicer with JS
		return !$this->isHelpInline() && $this->getHelpMessages();
	}

	/**
	 * Get the list of extra ResourceLoader modules which must be loaded client-side before it's
	 * possible to infuse this field's OOUI widget.
	 * @stable to override
	 *
	 * @return string[]
	 */
	protected function getOOUIModules() {
		return [];
	}

	/**
	 * Get the complete raw fields for the input, including help text,
	 * labels, and whatever.
	 * @stable to override
	 * @since 1.20
	 *
	 * @param string $value The value to set the input to.
	 *
	 * @return string Complete HTML table row.
	 */
	public function getRaw( $value ) {
		[ $errors, ] = $this->getErrorsAndErrorClass( $value );
		return "\n" . $errors .
			$this->getLabelHtml() .
			$this->getInputHTML( $value ) .
			$this->getHelpTextHtmlRaw( $this->getHelpText() );
	}

	/**
	 * Get the complete field for the input, including help text,
	 * labels, and whatever. Fall back from 'vform' to 'div' when not overridden.
	 *
	 * @stable to override
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
	 * @stable to override
	 * @since 1.25
	 * @param string $value The value to set the input to.
	 * @return string Complete HTML inline element
	 */
	public function getInline( $value ) {
		[ $errors, ] = $this->getErrorsAndErrorClass( $value );
		return "\n" . $errors .
			$this->getLabelHtml() .
			"\u{00A0}" .
			$this->getInputHTML( $value ) .
			$this->getHelpTextHtmlDiv( $this->getHelpText() );
	}

	/**
	 * Generate help text HTML in table format
	 * @since 1.20
	 *
	 * @param string|null $helptext
	 * @return string
	 */
	public function getHelpTextHtmlTable( $helptext ) {
		if ( $helptext === null ) {
			return '';
		}

		$rowAttributes = [];
		if ( $this->mCondState ) {
			$rowAttributes['data-cond-state'] = FormatJson::encode( $this->parseCondStateForClient() );
			$rowAttributes['class'] = $this->mCondStateClass;
		}

		$tdClasses = [ 'htmlform-tip' ];
		if ( $this->mHelpClass !== false ) {
			$tdClasses[] = $this->mHelpClass;
		}
		return Html::rawElement( 'tr', $rowAttributes,
			Html::rawElement( 'td', [ 'colspan' => 2, 'class' => $tdClasses ], $helptext )
		);
	}

	/**
	 * Generate help text HTML in div format
	 * @since 1.20
	 *
	 * @param string|null $helptext
	 * @param string[] $cssClasses
	 *
	 * @return string
	 */
	public function getHelpTextHtmlDiv( $helptext, $cssClasses = [] ) {
		if ( $helptext === null ) {
			return '';
		}

		$wrapperAttributes = [
			'class' => array_merge( $cssClasses, [ 'htmlform-tip' ] ),
		];
		if ( $this->mHelpClass !== false ) {
			$wrapperAttributes['class'][] = $this->mHelpClass;
		}
		if ( $this->mCondState ) {
			$wrapperAttributes['data-cond-state'] = FormatJson::encode( $this->parseCondStateForClient() );
			$wrapperAttributes['class'] = array_merge( $wrapperAttributes['class'], $this->mCondStateClass );
		}
		return Html::rawElement( 'div', $wrapperAttributes, $helptext );
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

	private function getHelpMessages(): array {
		if ( isset( $this->mParams['help-message'] ) ) {
			return [ $this->mParams['help-message'] ];
		} elseif ( isset( $this->mParams['help-messages'] ) ) {
			return $this->mParams['help-messages'];
		} elseif ( isset( $this->mParams['help-raw'] ) ) {
			return [ new HtmlArmor( $this->mParams['help-raw'] ) ];
		} elseif ( isset( $this->mParams['help'] ) ) {
			// @deprecated since 1.43, use 'help-raw' key instead
			return [ new HtmlArmor( $this->mParams['help'] ) ];
		}

		return [];
	}

	/**
	 * Determine the help text to display
	 * @stable to override
	 * @since 1.20
	 * @return string|null HTML
	 */
	public function getHelpText() {
		$html = [];

		foreach ( $this->getHelpMessages() as $msg ) {
			if ( $msg instanceof HtmlArmor ) {
				$html[] = HtmlArmor::getHtml( $msg );
			} else {
				$msg = $this->getMessage( $msg );
				if ( $msg->exists() ) {
					$html[] = $msg->parse();
				}
			}
		}

		return $html ? implode( $this->msg( 'word-separator' )->escaped(), $html ) : null;
	}

	/**
	 * Determine if the help text should be displayed inline.
	 *
	 * Only applies to OOUI forms.
	 *
	 * @since 1.31
	 * @return bool
	 */
	public function isHelpInline() {
		return $this->mParams['help-inline'] ?? true;
	}

	/**
	 * Determine form errors to display and their classes
	 * @since 1.20
	 *
	 * phan-taint-check gets confused with returning both classes
	 * and errors and thinks double escaping is happening, so specify
	 * that return value has no taint.
	 *
	 * @param string $value The value of the input
	 * @return array [ $errors, $errorClass ]
	 * @return-taint none
	 */
	public function getErrorsAndErrorClass( $value ) {
		$errors = $this->validate( $value, $this->mParent->mFieldData );

		if ( is_bool( $errors ) || !$this->mParent->wasSubmitted() ) {
			return [ '', '' ];
		}

		return [ self::formatErrors( $errors ), 'mw-htmlform-invalid-input' ];
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
			return [];
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
	 * @stable to override
	 * @return string HTML
	 */
	public function getLabel() {
		return $this->mLabel ?? '';
	}

	/**
	 * @stable to override
	 * @param array $cellAttributes
	 *
	 * @return string
	 */
	public function getLabelHtml( $cellAttributes = [] ) {
		# Don't output a for= attribute for labels with no associated input.
		# Kind of hacky here, possibly we don't want these to be <label>s at all.
		$for = $this->needsLabel() ? [ 'for' => $this->mID ] : [];

		$labelValue = trim( $this->getLabel() );
		$hasLabel = $labelValue !== '' && $labelValue !== "\u{00A0}" && $labelValue !== '&#160;';

		$displayFormat = $this->mParent->getDisplayFormat();
		$horizontalLabel = $this->mParams['horizontal-label'] ?? false;

		if ( $displayFormat === 'table' ) {
			return Html::rawElement( 'td',
					[ 'class' => 'mw-label' ] + $cellAttributes,
					Html::rawElement( 'label', $for, $labelValue ) );
		} elseif ( $hasLabel || $this->mShowEmptyLabels ) {
			if ( $displayFormat === 'div' && !$horizontalLabel ) {
				return Html::rawElement( 'div',
						[ 'class' => 'mw-label' ] + $cellAttributes,
						Html::rawElement( 'label', $for, $labelValue ) );
			} else {
				return Html::rawElement( 'label', $for, $labelValue );
			}
		}

		return '';
	}

	/**
	 * @stable to override
	 * @return mixed
	 */
	public function getDefault() {
		return $this->mDefault ?? null;
	}

	/**
	 * Returns the attributes required for the tooltip and accesskey, for Html::element() etc.
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
	 * Returns the attributes required for the tooltip and accesskey, for OOUI widgets' config.
	 *
	 * @return array Attributes
	 */
	public function getTooltipAndAccessKeyOOUI() {
		if ( empty( $this->mParams['tooltip'] ) ) {
			return [];
		}

		return [
			'title' => Linker::titleAttrib( $this->mParams['tooltip'] ),
			'accessKey' => Linker::accesskey( $this->mParams['tooltip'] ),
		];
	}

	/**
	 * Returns the given attributes from the parameters
	 * @stable to override
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
	 * @param bool $needsParse
	 * @return array
	 * @return-taint tainted
	 */
	private function lookupOptionsKeys( $options, $needsParse ) {
		$ret = [];
		foreach ( $options as $key => $value ) {
			$msg = $this->msg( $key );
			$msgAsText = $needsParse ? $msg->parse() : $msg->plain();
			if ( array_key_exists( $msgAsText, $ret ) ) {
				LoggerFactory::getInstance( 'translation-problem' )->error(
					'The option that uses the message key {msg_key_one} has the same translation as ' .
					'another option in {lang}. This means that {msg_key_one} will not be used as an option.',
					[
						'msg_key_one' => $key,
						'lang' => $this->mParent ?
							$this->mParent->getLanguageCode()->toBcp47Code() :
							RequestContext::getMain()->getLanguageCode()->toBcp47Code(),
					]
				);
				continue;
			}
			$ret[$msgAsText] = is_array( $value )
				? $this->lookupOptionsKeys( $value, $needsParse )
				: strval( $value );
		}
		return $ret;
	}

	/**
	 * Recursively forces values in an array to strings, because issues arise
	 * with integer 0 as a value.
	 *
	 * @param array|string $array
	 * @return array|string
	 */
	public static function forceToStringRecursive( $array ) {
		if ( is_array( $array ) ) {
			return array_map( [ self::class, 'forceToStringRecursive' ], $array );
		} else {
			return strval( $array );
		}
	}

	/**
	 * Fetch the array of options from the field's parameters. In order, this
	 * checks 'options-messages', 'options', then 'options-message'.
	 *
	 * @return array|null
	 */
	public function getOptions() {
		if ( $this->mOptions === false ) {
			if ( array_key_exists( 'options-messages', $this->mParams ) ) {
				$needsParse = $this->mParams['options-messages-parse'] ?? false;
				if ( $needsParse ) {
					$this->mOptionsLabelsNotFromMessage = true;
				}
				$this->mOptions = $this->lookupOptionsKeys( $this->mParams['options-messages'], $needsParse );
			} elseif ( array_key_exists( 'options', $this->mParams ) ) {
				$this->mOptionsLabelsNotFromMessage = true;
				$this->mOptions = self::forceToStringRecursive( $this->mParams['options'] );
			} elseif ( array_key_exists( 'options-message', $this->mParams ) ) {
				$message = $this->getMessage( $this->mParams['options-message'] )->inContentLanguage()->plain();
				$this->mOptions = Html::listDropdownOptions( $message );
			} else {
				$this->mOptions = null;
			}
		}

		return $this->mOptions;
	}

	/**
	 * Get options and make them into arrays suitable for OOUI.
	 * @stable to override
	 * @return array|null Options for inclusion in a select or whatever.
	 */
	public function getOptionsOOUI() {
		$oldoptions = $this->getOptions();

		if ( $oldoptions === null ) {
			return null;
		}

		return Html::listDropdownOptionsOoui( $oldoptions );
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
	 * To work around limitations in phan-taint-check the calling
	 * class has taintedness disabled. So instead we pretend that
	 * this method outputs html, since the result is eventually
	 * outputted anyways without escaping and this allows us to verify
	 * stuff is safe even though the caller has taintedness cleared.
	 * @param-taint $errors exec_html
	 * @return string HTML
	 * @since 1.18
	 */
	protected static function formatErrors( $errors ) {
		if ( is_array( $errors ) && count( $errors ) === 1 ) {
			$errors = array_shift( $errors );
		}

		if ( is_array( $errors ) ) {
			foreach ( $errors as &$error ) {
				$error = Html::rawElement( 'li', [],
					$error instanceof Message ? $error->parse() : $error
				);
			}
			$errors = Html::rawElement( 'ul', [], implode( "\n", $errors ) );
		} elseif ( $errors instanceof Message ) {
			$errors = $errors->parse();
		}

		return Html::errorBox( $errors );
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
	 * @stable to override
	 * @param WebRequest $request
	 * @return bool
	 * @since 1.27
	 */
	public function skipLoadData( $request ) {
		return !empty( $this->mParams['nodata'] );
	}

	/**
	 * Whether this field requires the user agent to have JavaScript enabled for the client-side HTML5
	 * form validation to work correctly.
	 *
	 * @return bool
	 * @since 1.29
	 */
	public function needsJSForHtml5FormValidation() {
		// This is probably more restrictive than it needs to be, but better safe than sorry
		return (bool)$this->mCondState;
	}

	/**
	 * The keys in the array returned by getOptions() can be either HTML or
	 * plain text depending on $this->mOptionsLabelsNotFromMessage. Convert such
	 * a key to HTML.
	 *
	 * FIXME: a dangerous and untidy convention.
	 *
	 * @param string $label
	 * @param-taint $label escapes_html
	 * @return string
	 */
	protected function escapeLabel( $label ) {
		return $this->mOptionsLabelsNotFromMessage
			? $label : htmlspecialchars( $label, ENT_NOQUOTES );
	}

	/**
	 * The keys in the array returned by getOptions() can be either HTML or
	 * plain text depending on $this->mOptionsLabelsNotFromMessage. Convert
	 * such a key to either plain text or an HtmlSnippet as appropriate.
	 *
	 * FIXME: a dangerous and untidy convention.
	 *
	 * @param string $label
	 * @param-taint $label escapes_html
	 * @return string|\OOUI\HtmlSnippet
	 */
	protected function makeLabelSnippet( $label ) {
		return $this->mOptionsLabelsNotFromMessage
			? new \OOUI\HtmlSnippet( $label ) : $label;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLFormField::class, 'HTMLFormField' );
