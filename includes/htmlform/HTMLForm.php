<?php

/**
 * HTML form generation and submission handling.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Object handling generic submission, CSRF protection, layout and
 * other logic for UI forms. in a reusable manner.
 *
 * In order to generate the form, the HTMLForm object takes an array
 * structure detailing the form fields available. Each element of the
 * array is a basic property-list, including the type of field, the
 * label it is to be given in the form, callbacks for validation and
 * 'filtering', and other pertinent information.
 *
 * Field types are implemented as subclasses of the generic HTMLFormField
 * object, and typically implement at least getInputHTML, which generates
 * the HTML for the input field to be placed in the table.
 *
 * You can find extensive documentation on the www.mediawiki.org wiki:
 *  - https://www.mediawiki.org/wiki/HTMLForm
 *  - https://www.mediawiki.org/wiki/HTMLForm/tutorial
 *
 * The constructor input is an associative array of $fieldname => $info,
 * where $info is an Associative Array with any of the following:
 *
 *    'class'               -- the subclass of HTMLFormField that will be used
 *                             to create the object.  *NOT* the CSS class!
 *    'type'                -- roughly translates into the <select> type attribute.
 *                             if 'class' is not specified, this is used as a map
 *                             through HTMLForm::$typeMappings to get the class name.
 *    'default'             -- default value when the form is displayed
 *    'id'                  -- HTML id attribute
 *    'cssclass'            -- CSS class
 *    'csshelpclass'        -- CSS class used to style help text
 *    'dir'                 -- Direction of the element.
 *    'options'             -- associative array mapping labels to values.
 *                             Some field types support multi-level arrays.
 *    'options-messages'    -- associative array mapping message keys to values.
 *                             Some field types support multi-level arrays.
 *    'options-message'     -- message key or object to be parsed to extract the list of
 *                             options (like 'ipbreason-dropdown').
 *    'label-message'       -- message key or object for a message to use as the label.
 *                             can be an array of msg key and then parameters to
 *                             the message.
 *    'label'               -- alternatively, a raw text message. Overridden by
 *                             label-message
 *    'help'                -- message text for a message to use as a help text.
 *    'help-message'        -- message key or object for a message to use as a help text.
 *                             can be an array of msg key and then parameters to
 *                             the message.
 *                             Overwrites 'help-messages' and 'help'.
 *    'help-messages'       -- array of message keys/objects. As above, each item can
 *                             be an array of msg key and then parameters.
 *                             Overwrites 'help'.
 *    'notice'              -- message text for a message to use as a notice in the field.
 *                             Currently used by OOUI form fields only.
 *    'notice-messages'     -- array of message keys/objects to use for notice.
 *                             Overrides 'notice'.
 *    'notice-message'      -- message key or object to use as a notice.
 *    'required'            -- passed through to the object, indicating that it
 *                             is a required field.
 *    'size'                -- the length of text fields
 *    'filter-callback'     -- a function name to give you the chance to
 *                             massage the inputted value before it's processed.
 *                             @see HTMLFormField::filter()
 *    'validation-callback' -- a function name to give you the chance
 *                             to impose extra validation on the field input.
 *                             @see HTMLFormField::validate()
 *    'name'                -- By default, the 'name' attribute of the input field
 *                             is "wp{$fieldname}".  If you want a different name
 *                             (eg one without the "wp" prefix), specify it here and
 *                             it will be used without modification.
 *    'hide-if'             -- expression given as an array stating when the field
 *                             should be hidden. The first array value has to be the
 *                             expression's logic operator. Supported expressions:
 *                               'NOT'
 *                                 [ 'NOT', array $expression ]
 *                                 To hide a field if a given expression is not true.
 *                               '==='
 *                                 [ '===', string $fieldName, string $value ]
 *                                 To hide a field if another field identified by
 *                                 $field has the value $value.
 *                               '!=='
 *                                 [ '!==', string $fieldName, string $value ]
 *                                 Same as [ 'NOT', [ '===', $fieldName, $value ]
 *                               'OR', 'AND', 'NOR', 'NAND'
 *                                 [ 'XXX', array $expression1, ..., array $expressionN ]
 *                                 To hide a field if one or more (OR), all (AND),
 *                                 neither (NOR) or not all (NAND) given expressions
 *                                 are evaluated as true.
 *                             The expressions will be given to a JavaScript frontend
 *                             module which will continually update the field's
 *                             visibility.
 *
 * Since 1.20, you can chain mutators to ease the form generation:
 * @par Example:
 * @code
 * $form = new HTMLForm( $someFields );
 * $form->setMethod( 'get' )
 *      ->setWrapperLegendMsg( 'message-key' )
 *      ->prepareForm()
 *      ->displayForm( '' );
 * @endcode
 * Note that you will have prepareForm and displayForm at the end. Other
 * methods call done after that would simply not be part of the form :(
 *
 * @todo Document 'section' / 'subsection' stuff
 */
class HTMLForm extends ContextSource {
	// A mapping of 'type' inputs onto standard HTMLFormField subclasses
	public static $typeMappings = [
		'api' => 'HTMLApiField',
		'text' => 'HTMLTextField',
		'textwithbutton' => 'HTMLTextFieldWithButton',
		'textarea' => 'HTMLTextAreaField',
		'select' => 'HTMLSelectField',
		'combobox' => 'HTMLComboboxField',
		'radio' => 'HTMLRadioField',
		'multiselect' => 'HTMLMultiSelectField',
		'limitselect' => 'HTMLSelectLimitField',
		'check' => 'HTMLCheckField',
		'toggle' => 'HTMLCheckField',
		'int' => 'HTMLIntField',
		'float' => 'HTMLFloatField',
		'info' => 'HTMLInfoField',
		'selectorother' => 'HTMLSelectOrOtherField',
		'selectandother' => 'HTMLSelectAndOtherField',
		'namespaceselect' => 'HTMLSelectNamespace',
		'namespaceselectwithbutton' => 'HTMLSelectNamespaceWithButton',
		'tagfilter' => 'HTMLTagFilter',
		'sizefilter' => 'HTMLSizeFilterField',
		'submit' => 'HTMLSubmitField',
		'hidden' => 'HTMLHiddenField',
		'edittools' => 'HTMLEditTools',
		'checkmatrix' => 'HTMLCheckMatrix',
		'cloner' => 'HTMLFormFieldCloner',
		'autocompleteselect' => 'HTMLAutoCompleteSelectField',
		'date' => 'HTMLDateTimeField',
		'time' => 'HTMLDateTimeField',
		'datetime' => 'HTMLDateTimeField',
		// HTMLTextField will output the correct type="" attribute automagically.
		// There are about four zillion other HTML5 input types, like range, but
		// we don't use those at the moment, so no point in adding all of them.
		'email' => 'HTMLTextField',
		'password' => 'HTMLTextField',
		'url' => 'HTMLTextField',
		'title' => 'HTMLTitleTextField',
		'user' => 'HTMLUserTextField',
		'usersmultiselect' => 'HTMLUsersMultiselectField',
	];

	public $mFieldData;

	protected $mMessagePrefix;

	/** @var HTMLFormField[] */
	protected $mFlatFields;

	protected $mFieldTree;
	protected $mShowReset = false;
	protected $mShowSubmit = true;
	protected $mSubmitFlags = [ 'primary', 'progressive' ];
	protected $mShowCancel = false;
	protected $mCancelTarget;

	protected $mSubmitCallback;
	protected $mValidationErrorMessage;

	protected $mPre = '';
	protected $mHeader = '';
	protected $mFooter = '';
	protected $mSectionHeaders = [];
	protected $mSectionFooters = [];
	protected $mPost = '';
	protected $mId;
	protected $mName;
	protected $mTableId = '';

	protected $mSubmitID;
	protected $mSubmitName;
	protected $mSubmitText;
	protected $mSubmitTooltip;

	protected $mFormIdentifier;
	protected $mTitle;
	protected $mMethod = 'post';
	protected $mWasSubmitted = false;

	/**
	 * Form action URL. false means we will use the URL to set Title
	 * @since 1.19
	 * @var bool|string
	 */
	protected $mAction = false;

	/**
	 * Form attribute autocomplete. false does not set the attribute
	 * @since 1.27
	 * @var bool|string
	 */
	protected $mAutocomplete = false;

	protected $mUseMultipart = false;
	protected $mHiddenFields = [];
	protected $mButtons = [];

	protected $mWrapperLegend = false;

	/**
	 * Salt for the edit token.
	 * @var string|array
	 */
	protected $mTokenSalt = '';

	/**
	 * If true, sections that contain both fields and subsections will
	 * render their subsections before their fields.
	 *
	 * Subclasses may set this to false to render subsections after fields
	 * instead.
	 */
	protected $mSubSectionBeforeFields = true;

	/**
	 * Format in which to display form. For viable options,
	 * @see $availableDisplayFormats
	 * @var string
	 */
	protected $displayFormat = 'table';

	/**
	 * Available formats in which to display the form
	 * @var array
	 */
	protected $availableDisplayFormats = [
		'table',
		'div',
		'raw',
		'inline',
	];

	/**
	 * Available formats in which to display the form
	 * @var array
	 */
	protected $availableSubclassDisplayFormats = [
		'vform',
		'ooui',
	];

	/**
	 * Construct a HTMLForm object for given display type. May return a HTMLForm subclass.
	 *
	 * @param string $displayFormat
	 * @param mixed $arguments,... Additional arguments to pass to the constructor.
	 * @return HTMLForm
	 */
	public static function factory( $displayFormat/*, $arguments...*/ ) {
		$arguments = func_get_args();
		array_shift( $arguments );

		switch ( $displayFormat ) {
			case 'vform':
				return ObjectFactory::constructClassInstance( VFormHTMLForm::class, $arguments );
			case 'ooui':
				return ObjectFactory::constructClassInstance( OOUIHTMLForm::class, $arguments );
			default:
				/** @var HTMLForm $form */
				$form = ObjectFactory::constructClassInstance( self::class, $arguments );
				$form->setDisplayFormat( $displayFormat );
				return $form;
		}
	}

	/**
	 * Build a new HTMLForm from an array of field attributes
	 *
	 * @param array $descriptor Array of Field constructs, as described above
	 * @param IContextSource $context Available since 1.18, will become compulsory in 1.18.
	 *     Obviates the need to call $form->setTitle()
	 * @param string $messagePrefix A prefix to go in front of default messages
	 */
	public function __construct( $descriptor, /*IContextSource*/ $context = null,
		$messagePrefix = ''
	) {
		if ( $context instanceof IContextSource ) {
			$this->setContext( $context );
			$this->mTitle = false; // We don't need them to set a title
			$this->mMessagePrefix = $messagePrefix;
		} elseif ( $context === null && $messagePrefix !== '' ) {
			$this->mMessagePrefix = $messagePrefix;
		} elseif ( is_string( $context ) && $messagePrefix === '' ) {
			// B/C since 1.18
			// it's actually $messagePrefix
			$this->mMessagePrefix = $context;
		}

		// Evil hack for mobile :(
		if (
			!$this->getConfig()->get( 'HTMLFormAllowTableFormat' )
			&& $this->displayFormat === 'table'
		) {
			$this->displayFormat = 'div';
		}

		// Expand out into a tree.
		$loadedDescriptor = [];
		$this->mFlatFields = [];

		foreach ( $descriptor as $fieldname => $info ) {
			$section = isset( $info['section'] )
				? $info['section']
				: '';

			if ( isset( $info['type'] ) && $info['type'] === 'file' ) {
				$this->mUseMultipart = true;
			}

			$field = static::loadInputFromParameters( $fieldname, $info, $this );

			$setSection =& $loadedDescriptor;
			if ( $section ) {
				$sectionParts = explode( '/', $section );

				while ( count( $sectionParts ) ) {
					$newName = array_shift( $sectionParts );

					if ( !isset( $setSection[$newName] ) ) {
						$setSection[$newName] = [];
					}

					$setSection =& $setSection[$newName];
				}
			}

			$setSection[$fieldname] = $field;
			$this->mFlatFields[$fieldname] = $field;
		}

		$this->mFieldTree = $loadedDescriptor;
	}

	/**
	 * @param string $fieldname
	 * @return bool
	 */
	public function hasField( $fieldname ) {
		return isset( $this->mFlatFields[$fieldname] );
	}

	/**
	 * @param string $fieldname
	 * @return HTMLFormField
	 * @throws DomainException on invalid field name
	 */
	public function getField( $fieldname ) {
		if ( !$this->hasField( $fieldname ) ) {
			throw new DomainException( __METHOD__ . ': no field named ' . $fieldname );
		}
		return $this->mFlatFields[$fieldname];
	}

	/**
	 * Set format in which to display the form
	 *
	 * @param string $format The name of the format to use, must be one of
	 *   $this->availableDisplayFormats
	 *
	 * @throws MWException
	 * @since 1.20
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setDisplayFormat( $format ) {
		if (
			in_array( $format, $this->availableSubclassDisplayFormats, true ) ||
			in_array( $this->displayFormat, $this->availableSubclassDisplayFormats, true )
		) {
			throw new MWException( 'Cannot change display format after creation, ' .
				'use HTMLForm::factory() instead' );
		}

		if ( !in_array( $format, $this->availableDisplayFormats, true ) ) {
			throw new MWException( 'Display format must be one of ' .
				print_r(
					array_merge(
						$this->availableDisplayFormats,
						$this->availableSubclassDisplayFormats
					),
					true
				) );
		}

		// Evil hack for mobile :(
		if ( !$this->getConfig()->get( 'HTMLFormAllowTableFormat' ) && $format === 'table' ) {
			$format = 'div';
		}

		$this->displayFormat = $format;

		return $this;
	}

	/**
	 * Getter for displayFormat
	 * @since 1.20
	 * @return string
	 */
	public function getDisplayFormat() {
		return $this->displayFormat;
	}

	/**
	 * Test if displayFormat is 'vform'
	 * @since 1.22
	 * @deprecated since 1.25
	 * @return bool
	 */
	public function isVForm() {
		wfDeprecated( __METHOD__, '1.25' );
		return false;
	}

	/**
	 * Get the HTMLFormField subclass for this descriptor.
	 *
	 * The descriptor can be passed either 'class' which is the name of
	 * a HTMLFormField subclass, or a shorter 'type' which is an alias.
	 * This makes sure the 'class' is always set, and also is returned by
	 * this function for ease.
	 *
	 * @since 1.23
	 *
	 * @param string $fieldname Name of the field
	 * @param array &$descriptor Input Descriptor, as described above
	 *
	 * @throws MWException
	 * @return string Name of a HTMLFormField subclass
	 */
	public static function getClassFromDescriptor( $fieldname, &$descriptor ) {
		if ( isset( $descriptor['class'] ) ) {
			$class = $descriptor['class'];
		} elseif ( isset( $descriptor['type'] ) ) {
			$class = static::$typeMappings[$descriptor['type']];
			$descriptor['class'] = $class;
		} else {
			$class = null;
		}

		if ( !$class ) {
			throw new MWException( "Descriptor with no class for $fieldname: "
				. print_r( $descriptor, true ) );
		}

		return $class;
	}

	/**
	 * Initialise a new Object for the field
	 *
	 * @param string $fieldname Name of the field
	 * @param array $descriptor Input Descriptor, as described above
	 * @param HTMLForm|null $parent Parent instance of HTMLForm
	 *
	 * @throws MWException
	 * @return HTMLFormField Instance of a subclass of HTMLFormField
	 */
	public static function loadInputFromParameters( $fieldname, $descriptor,
		HTMLForm $parent = null
	) {
		$class = static::getClassFromDescriptor( $fieldname, $descriptor );

		$descriptor['fieldname'] = $fieldname;
		if ( $parent ) {
			$descriptor['parent'] = $parent;
		}

		# @todo This will throw a fatal error whenever someone try to use
		# 'class' to feed a CSS class instead of 'cssclass'. Would be
		# great to avoid the fatal error and show a nice error.
		return new $class( $descriptor );
	}

	/**
	 * Prepare form for submission.
	 *
	 * @attention When doing method chaining, that should be the very last
	 * method call before displayForm().
	 *
	 * @throws MWException
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function prepareForm() {
		# Check if we have the info we need
		if ( !$this->mTitle instanceof Title && $this->mTitle !== false ) {
			throw new MWException( 'You must call setTitle() on an HTMLForm' );
		}

		# Load data from the request.
		if (
			$this->mFormIdentifier === null ||
			$this->getRequest()->getVal( 'wpFormIdentifier' ) === $this->mFormIdentifier
		) {
			$this->loadData();
		} else {
			$this->mFieldData = [];
		}

		return $this;
	}

	/**
	 * Try submitting, with edit token check first
	 * @return Status|bool
	 */
	public function tryAuthorizedSubmit() {
		$result = false;

		$identOkay = false;
		if ( $this->mFormIdentifier === null ) {
			$identOkay = true;
		} else {
			$identOkay = $this->getRequest()->getVal( 'wpFormIdentifier' ) === $this->mFormIdentifier;
		}

		$tokenOkay = false;
		if ( $this->getMethod() !== 'post' ) {
			$tokenOkay = true; // no session check needed
		} elseif ( $this->getRequest()->wasPosted() ) {
			$editToken = $this->getRequest()->getVal( 'wpEditToken' );
			if ( $this->getUser()->isLoggedIn() || $editToken !== null ) {
				// Session tokens for logged-out users have no security value.
				// However, if the user gave one, check it in order to give a nice
				// "session expired" error instead of "permission denied" or such.
				$tokenOkay = $this->getUser()->matchEditToken( $editToken, $this->mTokenSalt );
			} else {
				$tokenOkay = true;
			}
		}

		if ( $tokenOkay && $identOkay ) {
			$this->mWasSubmitted = true;
			$result = $this->trySubmit();
		}

		return $result;
	}

	/**
	 * The here's-one-I-made-earlier option: do the submission if
	 * posted, or display the form with or without funky validation
	 * errors
	 * @return bool|Status Whether submission was successful.
	 */
	public function show() {
		$this->prepareForm();

		$result = $this->tryAuthorizedSubmit();
		if ( $result === true || ( $result instanceof Status && $result->isGood() ) ) {
			return $result;
		}

		$this->displayForm( $result );

		return false;
	}

	/**
	 * Same as self::show with the difference, that the form will be
	 * added to the output, no matter, if the validation was good or not.
	 * @return bool|Status Whether submission was successful.
	 */
	public function showAlways() {
		$this->prepareForm();

		$result = $this->tryAuthorizedSubmit();

		$this->displayForm( $result );

		return $result;
	}

	/**
	 * Validate all the fields, and call the submission callback
	 * function if everything is kosher.
	 * @throws MWException
	 * @return bool|string|array|Status
	 *     - Bool true or a good Status object indicates success,
	 *     - Bool false indicates no submission was attempted,
	 *     - Anything else indicates failure. The value may be a fatal Status
	 *       object, an HTML string, or an array of arrays (message keys and
	 *       params) or strings (message keys)
	 */
	public function trySubmit() {
		$valid = true;
		$hoistedErrors = Status::newGood();
		if ( $this->mValidationErrorMessage ) {
			foreach ( (array)$this->mValidationErrorMessage as $error ) {
				call_user_func_array( [ $hoistedErrors, 'fatal' ], $error );
			}
		} else {
			$hoistedErrors->fatal( 'htmlform-invalid-input' );
		}

		$this->mWasSubmitted = true;

		# Check for cancelled submission
		foreach ( $this->mFlatFields as $fieldname => $field ) {
			if ( !array_key_exists( $fieldname, $this->mFieldData ) ) {
				continue;
			}
			if ( $field->cancelSubmit( $this->mFieldData[$fieldname], $this->mFieldData ) ) {
				$this->mWasSubmitted = false;
				return false;
			}
		}

		# Check for validation
		foreach ( $this->mFlatFields as $fieldname => $field ) {
			if ( !array_key_exists( $fieldname, $this->mFieldData ) ) {
				continue;
			}
			if ( $field->isHidden( $this->mFieldData ) ) {
				continue;
			}
			$res = $field->validate( $this->mFieldData[$fieldname], $this->mFieldData );
			if ( $res !== true ) {
				$valid = false;
				if ( $res !== false && !$field->canDisplayErrors() ) {
					if ( is_string( $res ) ) {
						$hoistedErrors->fatal( 'rawmessage', $res );
					} else {
						$hoistedErrors->fatal( $res );
					}
				}
			}
		}

		if ( !$valid ) {
			return $hoistedErrors;
		}

		$callback = $this->mSubmitCallback;
		if ( !is_callable( $callback ) ) {
			throw new MWException( 'HTMLForm: no submit callback provided. Use ' .
				'setSubmitCallback() to set one.' );
		}

		$data = $this->filterDataForSubmit( $this->mFieldData );

		$res = call_user_func( $callback, $data, $this );
		if ( $res === false ) {
			$this->mWasSubmitted = false;
		}

		return $res;
	}

	/**
	 * Test whether the form was considered to have been submitted or not, i.e.
	 * whether the last call to tryAuthorizedSubmit or trySubmit returned
	 * non-false.
	 *
	 * This will return false until HTMLForm::tryAuthorizedSubmit or
	 * HTMLForm::trySubmit is called.
	 *
	 * @since 1.23
	 * @return bool
	 */
	public function wasSubmitted() {
		return $this->mWasSubmitted;
	}

	/**
	 * Set a callback to a function to do something with the form
	 * once it's been successfully validated.
	 *
	 * @param callable $cb The function will be passed the output from
	 *   HTMLForm::filterDataForSubmit and this HTMLForm object, and must
	 *   return as documented for HTMLForm::trySubmit
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setSubmitCallback( $cb ) {
		$this->mSubmitCallback = $cb;

		return $this;
	}

	/**
	 * Set a message to display on a validation error.
	 *
	 * @param string|array $msg String or Array of valid inputs to wfMessage()
	 *     (so each entry can be either a String or Array)
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setValidationErrorMessage( $msg ) {
		$this->mValidationErrorMessage = $msg;

		return $this;
	}

	/**
	 * Set the introductory message, overwriting any existing message.
	 *
	 * @param string $msg Complete text of message to display
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setIntro( $msg ) {
		$this->setPreText( $msg );

		return $this;
	}

	/**
	 * Set the introductory message HTML, overwriting any existing message.
	 * @since 1.19
	 *
	 * @param string $msg Complete HTML of message to display
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setPreText( $msg ) {
		$this->mPre = $msg;

		return $this;
	}

	/**
	 * Add HTML to introductory message.
	 *
	 * @param string $msg Complete HTML of message to display
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function addPreText( $msg ) {
		$this->mPre .= $msg;

		return $this;
	}

	/**
	 * Add HTML to the header, inside the form.
	 *
	 * @param string $msg Additional HTML to display in header
	 * @param string|null $section The section to add the header to
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function addHeaderText( $msg, $section = null ) {
		if ( $section === null ) {
			$this->mHeader .= $msg;
		} else {
			if ( !isset( $this->mSectionHeaders[$section] ) ) {
				$this->mSectionHeaders[$section] = '';
			}
			$this->mSectionHeaders[$section] .= $msg;
		}

		return $this;
	}

	/**
	 * Set header text, inside the form.
	 * @since 1.19
	 *
	 * @param string $msg Complete HTML of header to display
	 * @param string|null $section The section to add the header to
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setHeaderText( $msg, $section = null ) {
		if ( $section === null ) {
			$this->mHeader = $msg;
		} else {
			$this->mSectionHeaders[$section] = $msg;
		}

		return $this;
	}

	/**
	 * Get header text.
	 *
	 * @param string|null $section The section to get the header text for
	 * @since 1.26
	 * @return string HTML
	 */
	public function getHeaderText( $section = null ) {
		if ( $section === null ) {
			return $this->mHeader;
		} else {
			return isset( $this->mSectionHeaders[$section] ) ? $this->mSectionHeaders[$section] : '';
		}
	}

	/**
	 * Add footer text, inside the form.
	 *
	 * @param string $msg Complete text of message to display
	 * @param string|null $section The section to add the footer text to
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function addFooterText( $msg, $section = null ) {
		if ( $section === null ) {
			$this->mFooter .= $msg;
		} else {
			if ( !isset( $this->mSectionFooters[$section] ) ) {
				$this->mSectionFooters[$section] = '';
			}
			$this->mSectionFooters[$section] .= $msg;
		}

		return $this;
	}

	/**
	 * Set footer text, inside the form.
	 * @since 1.19
	 *
	 * @param string $msg Complete text of message to display
	 * @param string|null $section The section to add the footer text to
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setFooterText( $msg, $section = null ) {
		if ( $section === null ) {
			$this->mFooter = $msg;
		} else {
			$this->mSectionFooters[$section] = $msg;
		}

		return $this;
	}

	/**
	 * Get footer text.
	 *
	 * @param string|null $section The section to get the footer text for
	 * @since 1.26
	 * @return string
	 */
	public function getFooterText( $section = null ) {
		if ( $section === null ) {
			return $this->mFooter;
		} else {
			return isset( $this->mSectionFooters[$section] ) ? $this->mSectionFooters[$section] : '';
		}
	}

	/**
	 * Add text to the end of the display.
	 *
	 * @param string $msg Complete text of message to display
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function addPostText( $msg ) {
		$this->mPost .= $msg;

		return $this;
	}

	/**
	 * Set text at the end of the display.
	 *
	 * @param string $msg Complete text of message to display
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setPostText( $msg ) {
		$this->mPost = $msg;

		return $this;
	}

	/**
	 * Add a hidden field to the output
	 *
	 * @param string $name Field name.  This will be used exactly as entered
	 * @param string $value Field value
	 * @param array $attribs
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function addHiddenField( $name, $value, array $attribs = [] ) {
		$attribs += [ 'name' => $name ];
		$this->mHiddenFields[] = [ $value, $attribs ];

		return $this;
	}

	/**
	 * Add an array of hidden fields to the output
	 *
	 * @since 1.22
	 *
	 * @param array $fields Associative array of fields to add;
	 *        mapping names to their values
	 *
	 * @return HTMLForm $this for chaining calls
	 */
	public function addHiddenFields( array $fields ) {
		foreach ( $fields as $name => $value ) {
			$this->mHiddenFields[] = [ $value, [ 'name' => $name ] ];
		}

		return $this;
	}

	/**
	 * Add a button to the form
	 *
	 * @since 1.27 takes an array as shown. Earlier versions accepted
	 *  'name', 'value', 'id', and 'attribs' as separate parameters in that
	 *  order.
	 * @note Custom labels ('label', 'label-message', 'label-raw') are not
	 *  supported for IE6 and IE7 due to bugs in those browsers. If detected,
	 *  they will be served buttons using 'value' as the button label.
	 * @param array $data Data to define the button:
	 *  - name: (string) Button name.
	 *  - value: (string) Button value.
	 *  - label-message: (string, optional) Button label message key to use
	 *    instead of 'value'. Overrides 'label' and 'label-raw'.
	 *  - label: (string, optional) Button label text to use instead of
	 *    'value'. Overrides 'label-raw'.
	 *  - label-raw: (string, optional) Button label HTML to use instead of
	 *    'value'.
	 *  - id: (string, optional) DOM id for the button.
	 *  - attribs: (array, optional) Additional HTML attributes.
	 *  - flags: (string|string[], optional) OOUI flags.
	 *  - framed: (boolean=true, optional) OOUI framed attribute.
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function addButton( $data ) {
		if ( !is_array( $data ) ) {
			$args = func_get_args();
			if ( count( $args ) < 2 || count( $args ) > 4 ) {
				throw new InvalidArgumentException(
					'Incorrect number of arguments for deprecated calling style'
				);
			}
			$data = [
				'name' => $args[0],
				'value' => $args[1],
				'id' => isset( $args[2] ) ? $args[2] : null,
				'attribs' => isset( $args[3] ) ? $args[3] : null,
			];
		} else {
			if ( !isset( $data['name'] ) ) {
				throw new InvalidArgumentException( 'A name is required' );
			}
			if ( !isset( $data['value'] ) ) {
				throw new InvalidArgumentException( 'A value is required' );
			}
		}
		$this->mButtons[] = $data + [
			'id' => null,
			'attribs' => null,
			'flags' => null,
			'framed' => true,
		];

		return $this;
	}

	/**
	 * Set the salt for the edit token.
	 *
	 * Only useful when the method is "post".
	 *
	 * @since 1.24
	 * @param string|array $salt Salt to use
	 * @return HTMLForm $this For chaining calls
	 */
	public function setTokenSalt( $salt ) {
		$this->mTokenSalt = $salt;

		return $this;
	}

	/**
	 * Display the form (sending to the context's OutputPage object), with an
	 * appropriate error message or stack of messages, and any validation errors, etc.
	 *
	 * @attention You should call prepareForm() before calling this function.
	 * Moreover, when doing method chaining this should be the very last method
	 * call just after prepareForm().
	 *
	 * @param bool|string|array|Status $submitResult Output from HTMLForm::trySubmit()
	 *
	 * @return void Nothing, should be last call
	 */
	public function displayForm( $submitResult ) {
		$this->getOutput()->addHTML( $this->getHTML( $submitResult ) );
	}

	/**
	 * Returns the raw HTML generated by the form
	 *
	 * @param bool|string|array|Status $submitResult Output from HTMLForm::trySubmit()
	 *
	 * @return string HTML
	 */
	public function getHTML( $submitResult ) {
		# For good measure (it is the default)
		$this->getOutput()->preventClickjacking();
		$this->getOutput()->addModules( 'mediawiki.htmlform' );
		$this->getOutput()->addModuleStyles( 'mediawiki.htmlform.styles' );

		$html = ''
			. $this->getErrorsOrWarnings( $submitResult, 'error' )
			. $this->getErrorsOrWarnings( $submitResult, 'warning' )
			. $this->getHeaderText()
			. $this->getBody()
			. $this->getHiddenFields()
			. $this->getButtons()
			. $this->getFooterText();

		$html = $this->wrapForm( $html );

		return '' . $this->mPre . $html . $this->mPost;
	}

	/**
	 * Get HTML attributes for the `<form>` tag.
	 * @return array
	 */
	protected function getFormAttributes() {
		# Use multipart/form-data
		$encType = $this->mUseMultipart
			? 'multipart/form-data'
			: 'application/x-www-form-urlencoded';
		# Attributes
		$attribs = [
			'class' => 'mw-htmlform',
			'action' => $this->getAction(),
			'method' => $this->getMethod(),
			'enctype' => $encType,
		];
		if ( $this->mId ) {
			$attribs['id'] = $this->mId;
		}
		if ( $this->mAutocomplete ) {
			$attribs['autocomplete'] = $this->mAutocomplete;
		}
		if ( $this->mName ) {
			$attribs['name'] = $this->mName;
		}
		if ( $this->needsJSForHtml5FormValidation() ) {
			$attribs['novalidate'] = true;
		}
		return $attribs;
	}

	/**
	 * Wrap the form innards in an actual "<form>" element
	 *
	 * @param string $html HTML contents to wrap.
	 *
	 * @return string Wrapped HTML.
	 */
	public function wrapForm( $html ) {
		# Include a <fieldset> wrapper for style, if requested.
		if ( $this->mWrapperLegend !== false ) {
			$legend = is_string( $this->mWrapperLegend ) ? $this->mWrapperLegend : false;
			$html = Xml::fieldset( $legend, $html );
		}

		return Html::rawElement(
			'form',
			$this->getFormAttributes(),
			$html
		);
	}

	/**
	 * Get the hidden fields that should go inside the form.
	 * @return string HTML.
	 */
	public function getHiddenFields() {
		$html = '';
		if ( $this->mFormIdentifier !== null ) {
			$html .= Html::hidden(
				'wpFormIdentifier',
				$this->mFormIdentifier
			) . "\n";
		}
		if ( $this->getMethod() === 'post' ) {
			$html .= Html::hidden(
				'wpEditToken',
				$this->getUser()->getEditToken( $this->mTokenSalt ),
				[ 'id' => 'wpEditToken' ]
			) . "\n";
			$html .= Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) . "\n";
		}

		$articlePath = $this->getConfig()->get( 'ArticlePath' );
		if ( strpos( $articlePath, '?' ) !== false && $this->getMethod() === 'get' ) {
			$html .= Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) . "\n";
		}

		foreach ( $this->mHiddenFields as $data ) {
			list( $value, $attribs ) = $data;
			$html .= Html::hidden( $attribs['name'], $value, $attribs ) . "\n";
		}

		return $html;
	}

	/**
	 * Get the submit and (potentially) reset buttons.
	 * @return string HTML.
	 */
	public function getButtons() {
		$buttons = '';
		$useMediaWikiUIEverywhere = $this->getConfig()->get( 'UseMediaWikiUIEverywhere' );

		if ( $this->mShowSubmit ) {
			$attribs = [];

			if ( isset( $this->mSubmitID ) ) {
				$attribs['id'] = $this->mSubmitID;
			}

			if ( isset( $this->mSubmitName ) ) {
				$attribs['name'] = $this->mSubmitName;
			}

			if ( isset( $this->mSubmitTooltip ) ) {
				$attribs += Linker::tooltipAndAccesskeyAttribs( $this->mSubmitTooltip );
			}

			$attribs['class'] = [ 'mw-htmlform-submit' ];

			if ( $useMediaWikiUIEverywhere ) {
				foreach ( $this->mSubmitFlags as $flag ) {
					$attribs['class'][] = 'mw-ui-' . $flag;
				}
				$attribs['class'][] = 'mw-ui-button';
			}

			$buttons .= Xml::submitButton( $this->getSubmitText(), $attribs ) . "\n";
		}

		if ( $this->mShowReset ) {
			$buttons .= Html::element(
				'input',
				[
					'type' => 'reset',
					'value' => $this->msg( 'htmlform-reset' )->text(),
					'class' => $useMediaWikiUIEverywhere ? 'mw-ui-button' : null,
				]
			) . "\n";
		}

		if ( $this->mShowCancel ) {
			$target = $this->mCancelTarget ?: Title::newMainPage();
			if ( $target instanceof Title ) {
				$target = $target->getLocalURL();
			}
			$buttons .= Html::element(
					'a',
					[
						'class' => $useMediaWikiUIEverywhere ? 'mw-ui-button' : null,
						'href' => $target,
					],
					$this->msg( 'cancel' )->text()
				) . "\n";
		}

		// IE<8 has bugs with <button>, so we'll need to avoid them.
		$isBadIE = preg_match( '/MSIE [1-7]\./i', $this->getRequest()->getHeader( 'User-Agent' ) );

		foreach ( $this->mButtons as $button ) {
			$attrs = [
				'type' => 'submit',
				'name' => $button['name'],
				'value' => $button['value']
			];

			if ( isset( $button['label-message'] ) ) {
				$label = $this->getMessage( $button['label-message'] )->parse();
			} elseif ( isset( $button['label'] ) ) {
				$label = htmlspecialchars( $button['label'] );
			} elseif ( isset( $button['label-raw'] ) ) {
				$label = $button['label-raw'];
			} else {
				$label = htmlspecialchars( $button['value'] );
			}

			if ( $button['attribs'] ) {
				$attrs += $button['attribs'];
			}

			if ( isset( $button['id'] ) ) {
				$attrs['id'] = $button['id'];
			}

			if ( $useMediaWikiUIEverywhere ) {
				$attrs['class'] = isset( $attrs['class'] ) ? (array)$attrs['class'] : [];
				$attrs['class'][] = 'mw-ui-button';
			}

			if ( $isBadIE ) {
				$buttons .= Html::element( 'input', $attrs ) . "\n";
			} else {
				$buttons .= Html::rawElement( 'button', $attrs, $label ) . "\n";
			}
		}

		if ( !$buttons ) {
			return '';
		}

		return Html::rawElement( 'span',
			[ 'class' => 'mw-htmlform-submit-buttons' ], "\n$buttons" ) . "\n";
	}

	/**
	 * Get the whole body of the form.
	 * @return string
	 */
	public function getBody() {
		return $this->displaySection( $this->mFieldTree, $this->mTableId );
	}

	/**
	 * Format and display an error message stack.
	 *
	 * @param string|array|Status $errors
	 *
	 * @deprecated since 1.28, use getErrorsOrWarnings() instead
	 *
	 * @return string
	 */
	public function getErrors( $errors ) {
		wfDeprecated( __METHOD__ );
		return $this->getErrorsOrWarnings( $errors, 'error' );
	}

	/**
	 * Returns a formatted list of errors or warnings from the given elements.
	 *
	 * @param string|array|Status $elements The set of errors/warnings to process.
	 * @param string $elementsType Should warnings or errors be returned.  This is meant
	 *     for Status objects, all other valid types are always considered as errors.
	 * @return string
	 */
	public function getErrorsOrWarnings( $elements, $elementsType ) {
		if ( !in_array( $elementsType, [ 'error', 'warning' ], true ) ) {
			throw new DomainException( $elementsType . ' is not a valid type.' );
		}
		$elementstr = false;
		if ( $elements instanceof Status ) {
			list( $errorStatus, $warningStatus ) = $elements->splitByErrorType();
			$status = $elementsType === 'error' ? $errorStatus : $warningStatus;
			if ( $status->isGood() ) {
				$elementstr = '';
			} else {
				$elementstr = $this->getOutput()->parse(
					$status->getWikiText()
				);
			}
		} elseif ( is_array( $elements ) && $elementsType === 'error' ) {
			$elementstr = $this->formatErrors( $elements );
		} elseif ( $elementsType === 'error' ) {
			$elementstr = $elements;
		}

		return $elementstr
			? Html::rawElement( 'div', [ 'class' => $elementsType ], $elementstr )
			: '';
	}

	/**
	 * Format a stack of error messages into a single HTML string
	 *
	 * @param array $errors Array of message keys/values
	 *
	 * @return string HTML, a "<ul>" list of errors
	 */
	public function formatErrors( $errors ) {
		$errorstr = '';

		foreach ( $errors as $error ) {
			$errorstr .= Html::rawElement(
				'li',
				[],
				$this->getMessage( $error )->parse()
			);
		}

		$errorstr = Html::rawElement( 'ul', [], $errorstr );

		return $errorstr;
	}

	/**
	 * Set the text for the submit button
	 *
	 * @param string $t Plaintext
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setSubmitText( $t ) {
		$this->mSubmitText = $t;

		return $this;
	}

	/**
	 * Identify that the submit button in the form has a destructive action
	 * @since 1.24
	 *
	 * @return HTMLForm $this for chaining calls (since 1.28)
	 */
	public function setSubmitDestructive() {
		$this->mSubmitFlags = [ 'destructive', 'primary' ];

		return $this;
	}

	/**
	 * Identify that the submit button in the form has a progressive action
	 * @since 1.25
	 *
	 * @return HTMLForm $this for chaining calls (since 1.28)
	 */
	public function setSubmitProgressive() {
		$this->mSubmitFlags = [ 'progressive', 'primary' ];

		return $this;
	}

	/**
	 * Set the text for the submit button to a message
	 * @since 1.19
	 *
	 * @param string|Message $msg Message key or Message object
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setSubmitTextMsg( $msg ) {
		if ( !$msg instanceof Message ) {
			$msg = $this->msg( $msg );
		}
		$this->setSubmitText( $msg->text() );

		return $this;
	}

	/**
	 * Get the text for the submit button, either customised or a default.
	 * @return string
	 */
	public function getSubmitText() {
		return $this->mSubmitText ?: $this->msg( 'htmlform-submit' )->text();
	}

	/**
	 * @param string $name Submit button name
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setSubmitName( $name ) {
		$this->mSubmitName = $name;

		return $this;
	}

	/**
	 * @param string $name Tooltip for the submit button
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setSubmitTooltip( $name ) {
		$this->mSubmitTooltip = $name;

		return $this;
	}

	/**
	 * Set the id for the submit button.
	 *
	 * @param string $t
	 *
	 * @todo FIXME: Integrity of $t is *not* validated
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setSubmitID( $t ) {
		$this->mSubmitID = $t;

		return $this;
	}

	/**
	 * Set an internal identifier for this form. It will be submitted as a hidden form field, allowing
	 * HTMLForm to determine whether the form was submitted (or merely viewed). Setting this serves
	 * two purposes:
	 *
	 * - If you use two or more forms on one page, it allows HTMLForm to identify which of the forms
	 *   was submitted, and not attempt to validate the other ones.
	 * - If you use checkbox or multiselect fields inside a form using the GET method, it allows
	 *   HTMLForm to distinguish between the initial page view and a form submission with all
	 *   checkboxes or select options unchecked.
	 *
	 * @since 1.28
	 * @param string $ident
	 * @return $this
	 */
	public function setFormIdentifier( $ident ) {
		$this->mFormIdentifier = $ident;

		return $this;
	}

	/**
	 * Stop a default submit button being shown for this form. This implies that an
	 * alternate submit method must be provided manually.
	 *
	 * @since 1.22
	 *
	 * @param bool $suppressSubmit Set to false to re-enable the button again
	 *
	 * @return HTMLForm $this for chaining calls
	 */
	public function suppressDefaultSubmit( $suppressSubmit = true ) {
		$this->mShowSubmit = !$suppressSubmit;

		return $this;
	}

	/**
	 * Show a cancel button (or prevent it). The button is not shown by default.
	 * @param bool $show
	 * @return HTMLForm $this for chaining calls
	 * @since 1.27
	 */
	public function showCancel( $show = true ) {
		$this->mShowCancel = $show;
		return $this;
	}

	/**
	 * Sets the target where the user is redirected to after clicking cancel.
	 * @param Title|string $target Target as a Title object or an URL
	 * @return HTMLForm $this for chaining calls
	 * @since 1.27
	 */
	public function setCancelTarget( $target ) {
		$this->mCancelTarget = $target;
		return $this;
	}

	/**
	 * Set the id of the \<table\> or outermost \<div\> element.
	 *
	 * @since 1.22
	 *
	 * @param string $id New value of the id attribute, or "" to remove
	 *
	 * @return HTMLForm $this for chaining calls
	 */
	public function setTableId( $id ) {
		$this->mTableId = $id;

		return $this;
	}

	/**
	 * @param string $id DOM id for the form
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setId( $id ) {
		$this->mId = $id;

		return $this;
	}

	/**
	 * @param string $name 'name' attribute for the form
	 * @return HTMLForm $this for chaining calls
	 */
	public function setName( $name ) {
		$this->mName = $name;

		return $this;
	}

	/**
	 * Prompt the whole form to be wrapped in a "<fieldset>", with
	 * this text as its "<legend>" element.
	 *
	 * @param string|bool $legend If false, no wrapper or legend will be displayed.
	 *     If true, a wrapper will be displayed, but no legend.
	 *     If a string, a wrapper will be displayed with that string as a legend.
	 *     The string will be escaped before being output (this doesn't support HTML).
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setWrapperLegend( $legend ) {
		$this->mWrapperLegend = $legend;

		return $this;
	}

	/**
	 * Prompt the whole form to be wrapped in a "<fieldset>", with
	 * this message as its "<legend>" element.
	 * @since 1.19
	 *
	 * @param string|Message $msg Message key or Message object
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setWrapperLegendMsg( $msg ) {
		if ( !$msg instanceof Message ) {
			$msg = $this->msg( $msg );
		}
		$this->setWrapperLegend( $msg->text() );

		return $this;
	}

	/**
	 * Set the prefix for various default messages
	 * @todo Currently only used for the "<fieldset>" legend on forms
	 * with multiple sections; should be used elsewhere?
	 *
	 * @param string $p
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setMessagePrefix( $p ) {
		$this->mMessagePrefix = $p;

		return $this;
	}

	/**
	 * Set the title for form submission
	 *
	 * @param Title $t Title of page the form is on/should be posted to
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setTitle( $t ) {
		$this->mTitle = $t;

		return $this;
	}

	/**
	 * Get the title
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle === false
			? $this->getContext()->getTitle()
			: $this->mTitle;
	}

	/**
	 * Set the method used to submit the form
	 *
	 * @param string $method
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setMethod( $method = 'post' ) {
		$this->mMethod = strtolower( $method );

		return $this;
	}

	/**
	 * @return string Always lowercase
	 */
	public function getMethod() {
		return $this->mMethod;
	}

	/**
	 * Wraps the given $section into an user-visible fieldset.
	 *
	 * @param string $legend Legend text for the fieldset
	 * @param string $section The section content in plain Html
	 * @param array $attributes Additional attributes for the fieldset
	 * @return string The fieldset's Html
	 */
	protected function wrapFieldSetSection( $legend, $section, $attributes ) {
		return Xml::fieldset( $legend, $section, $attributes ) . "\n";
	}

	/**
	 * @todo Document
	 *
	 * @param array[]|HTMLFormField[] $fields Array of fields (either arrays or
	 *   objects).
	 * @param string $sectionName ID attribute of the "<table>" tag for this
	 *   section, ignored if empty.
	 * @param string $fieldsetIDPrefix ID prefix for the "<fieldset>" tag of
	 *   each subsection, ignored if empty.
	 * @param bool &$hasUserVisibleFields Whether the section had user-visible fields.
	 * @throws LogicException When called on uninitialized field data, e.g. When
	 *  HTMLForm::displayForm was called without calling HTMLForm::prepareForm
	 *  first.
	 *
	 * @return string
	 */
	public function displaySection( $fields,
		$sectionName = '',
		$fieldsetIDPrefix = '',
		&$hasUserVisibleFields = false
	) {
		if ( $this->mFieldData === null ) {
			throw new LogicException( 'HTMLForm::displaySection() called on uninitialized field data. '
				. 'You probably called displayForm() without calling prepareForm() first.' );
		}

		$displayFormat = $this->getDisplayFormat();

		$html = [];
		$subsectionHtml = '';
		$hasLabel = false;

		// Conveniently, PHP method names are case-insensitive.
		// For grep: this can call getDiv, getRaw, getInline, getVForm, getOOUI
		$getFieldHtmlMethod = $displayFormat === 'table' ? 'getTableRow' : ( 'get' . $displayFormat );

		foreach ( $fields as $key => $value ) {
			if ( $value instanceof HTMLFormField ) {
				$v = array_key_exists( $key, $this->mFieldData )
					? $this->mFieldData[$key]
					: $value->getDefault();

				$retval = $value->$getFieldHtmlMethod( $v );

				// check, if the form field should be added to
				// the output.
				if ( $value->hasVisibleOutput() ) {
					$html[] = $retval;

					$labelValue = trim( $value->getLabel() );
					if ( $labelValue !== '&#160;' && $labelValue !== '' ) {
						$hasLabel = true;
					}

					$hasUserVisibleFields = true;
				}
			} elseif ( is_array( $value ) ) {
				$subsectionHasVisibleFields = false;
				$section =
					$this->displaySection( $value,
						"mw-htmlform-$key",
						"$fieldsetIDPrefix$key-",
						$subsectionHasVisibleFields );
				$legend = null;

				if ( $subsectionHasVisibleFields === true ) {
					// Display the section with various niceties.
					$hasUserVisibleFields = true;

					$legend = $this->getLegend( $key );

					$section = $this->getHeaderText( $key ) .
						$section .
						$this->getFooterText( $key );

					$attributes = [];
					if ( $fieldsetIDPrefix ) {
						$attributes['id'] = Sanitizer::escapeIdForAttribute( "$fieldsetIDPrefix$key" );
					}
					$subsectionHtml .= $this->wrapFieldSetSection( $legend, $section, $attributes );
				} else {
					// Just return the inputs, nothing fancy.
					$subsectionHtml .= $section;
				}
			}
		}

		$html = $this->formatSection( $html, $sectionName, $hasLabel );

		if ( $subsectionHtml ) {
			if ( $this->mSubSectionBeforeFields ) {
				return $subsectionHtml . "\n" . $html;
			} else {
				return $html . "\n" . $subsectionHtml;
			}
		} else {
			return $html;
		}
	}

	/**
	 * Put a form section together from the individual fields' HTML, merging it and wrapping.
	 * @param array $fieldsHtml
	 * @param string $sectionName
	 * @param bool $anyFieldHasLabel
	 * @return string HTML
	 */
	protected function formatSection( array $fieldsHtml, $sectionName, $anyFieldHasLabel ) {
		$displayFormat = $this->getDisplayFormat();
		$html = implode( '', $fieldsHtml );

		if ( $displayFormat === 'raw' ) {
			return $html;
		}

		$classes = [];

		if ( !$anyFieldHasLabel ) { // Avoid strange spacing when no labels exist
			$classes[] = 'mw-htmlform-nolabel';
		}

		$attribs = [
			'class' => implode( ' ', $classes ),
		];

		if ( $sectionName ) {
			$attribs['id'] = Sanitizer::escapeIdForAttribute( $sectionName );
		}

		if ( $displayFormat === 'table' ) {
			return Html::rawElement( 'table',
					$attribs,
					Html::rawElement( 'tbody', [], "\n$html\n" ) ) . "\n";
		} elseif ( $displayFormat === 'inline' ) {
			return Html::rawElement( 'span', $attribs, "\n$html\n" );
		} else {
			return Html::rawElement( 'div', $attribs, "\n$html\n" );
		}
	}

	/**
	 * Construct the form fields from the Descriptor array
	 */
	public function loadData() {
		$fieldData = [];

		foreach ( $this->mFlatFields as $fieldname => $field ) {
			$request = $this->getRequest();
			if ( $field->skipLoadData( $request ) ) {
				continue;
			} elseif ( !empty( $field->mParams['disabled'] ) ) {
				$fieldData[$fieldname] = $field->getDefault();
			} else {
				$fieldData[$fieldname] = $field->loadDataFromRequest( $request );
			}
		}

		# Filter data.
		foreach ( $fieldData as $name => &$value ) {
			$field = $this->mFlatFields[$name];
			$value = $field->filter( $value, $this->mFlatFields );
		}

		$this->mFieldData = $fieldData;
	}

	/**
	 * Stop a reset button being shown for this form
	 *
	 * @param bool $suppressReset Set to false to re-enable the button again
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function suppressReset( $suppressReset = true ) {
		$this->mShowReset = !$suppressReset;

		return $this;
	}

	/**
	 * Overload this if you want to apply special filtration routines
	 * to the form as a whole, after it's submitted but before it's
	 * processed.
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function filterDataForSubmit( $data ) {
		return $data;
	}

	/**
	 * Get a string to go in the "<legend>" of a section fieldset.
	 * Override this if you want something more complicated.
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function getLegend( $key ) {
		return $this->msg( "{$this->mMessagePrefix}-$key" )->text();
	}

	/**
	 * Set the value for the action attribute of the form.
	 * When set to false (which is the default state), the set title is used.
	 *
	 * @since 1.19
	 *
	 * @param string|bool $action
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setAction( $action ) {
		$this->mAction = $action;

		return $this;
	}

	/**
	 * Get the value for the action attribute of the form.
	 *
	 * @since 1.22
	 *
	 * @return string
	 */
	public function getAction() {
		// If an action is alredy provided, return it
		if ( $this->mAction !== false ) {
			return $this->mAction;
		}

		$articlePath = $this->getConfig()->get( 'ArticlePath' );
		// Check whether we are in GET mode and the ArticlePath contains a "?"
		// meaning that getLocalURL() would return something like "index.php?title=...".
		// As browser remove the query string before submitting GET forms,
		// it means that the title would be lost. In such case use wfScript() instead
		// and put title in an hidden field (see getHiddenFields()).
		if ( strpos( $articlePath, '?' ) !== false && $this->getMethod() === 'get' ) {
			return wfScript();
		}

		return $this->getTitle()->getLocalURL();
	}

	/**
	 * Set the value for the autocomplete attribute of the form.
	 * When set to false (which is the default state), the attribute get not set.
	 *
	 * @since 1.27
	 *
	 * @param string|bool $autocomplete
	 *
	 * @return HTMLForm $this for chaining calls
	 */
	public function setAutocomplete( $autocomplete ) {
		$this->mAutocomplete = $autocomplete;

		return $this;
	}

	/**
	 * Turns a *-message parameter (which could be a MessageSpecifier, or a message name, or a
	 * name + parameters array) into a Message.
	 * @param mixed $value
	 * @return Message
	 */
	protected function getMessage( $value ) {
		return Message::newFromSpecifier( $value )->setContext( $this );
	}

	/**
	 * Whether this form, with its current fields, requires the user agent to have JavaScript enabled
	 * for the client-side HTML5 form validation to work correctly. If this function returns true, a
	 * 'novalidate' attribute will be added on the `<form>` element. It will be removed if the user
	 * agent has JavaScript support, in htmlform.js.
	 *
	 * @return bool
	 * @since 1.29
	 */
	public function needsJSForHtml5FormValidation() {
		foreach ( $this->mFlatFields as $fieldname => $field ) {
			if ( $field->needsJSForHtml5FormValidation() ) {
				return true;
			}
		}
		return false;
	}
}
