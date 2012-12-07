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
 *  - http://www.mediawiki.org/wiki/HTMLForm
 *  - http://www.mediawiki.org/wiki/HTMLForm/tutorial
 *
 * The constructor input is an associative array of $fieldname => $info,
 * where $info is an Associative Array with any of the following:
 *
 *	'class'               -- the subclass of HTMLFormField that will be used
 *	                         to create the object.  *NOT* the CSS class!
 *	'type'                -- roughly translates into the <select> type attribute.
 *	                         if 'class' is not specified, this is used as a map
 *	                         through HTMLForm::$typeMappings to get the class name.
 *	'default'             -- default value when the form is displayed
 *	'id'                  -- HTML id attribute
 *	'cssclass'            -- CSS class
 *	'options'             -- varies according to the specific object.
 *	'label-message'       -- message key for a message to use as the label.
 *	                         can be an array of msg key and then parameters to
 *	                         the message.
 *	'label'               -- alternatively, a raw text message. Overridden by
 *	                         label-message
 *	'help'                -- message text for a message to use as a help text.
 *	'help-message'        -- message key for a message to use as a help text.
 *	                         can be an array of msg key and then parameters to
 *	                         the message.
 *	                         Overwrites 'help-messages' and 'help'.
 *	'help-messages'       -- array of message key. As above, each item can
 *	                         be an array of msg key and then parameters.
 *	                         Overwrites 'help'.
 *	'required'            -- passed through to the object, indicating that it
 *	                         is a required field.
 *	'size'                -- the length of text fields
 *	'filter-callback      -- a function name to give you the chance to
 *	                         massage the inputted value before it's processed.
 *	                         @see HTMLForm::filter()
 *	'validation-callback' -- a function name to give you the chance
 *	                         to impose extra validation on the field input.
 *	                         @see HTMLForm::validate()
 *	'name'                -- By default, the 'name' attribute of the input field
 *	                         is "wp{$fieldname}".  If you want a different name
 *	                         (eg one without the "wp" prefix), specify it here and
 *	                         it will be used without modification.
 *
 * Since 1.20, you can chain mutators to ease the form generation:
 * @par Example:
 * @code
 * $form = new HTMLForm( $someFields );
 * $form->setMethod( 'get' )
 *      ->setWrapperLegendMsg( 'message-key' )
 *      ->suppressReset()
 *      ->prepareForm()
 *      ->displayForm();
 * @endcode
 * Note that you will have prepareForm and displayForm at the end. Other
 * methods call done after that would simply not be part of the form :(
 *
 * TODO: Document 'section' / 'subsection' stuff
 */
class HTMLForm extends ContextSource {

	// A mapping of 'type' inputs onto standard HTMLFormField subclasses
	static $typeMappings = array(
		'api' => 'HTMLApiField',
		'text' => 'HTMLTextField',
		'textarea' => 'HTMLTextAreaField',
		'select' => 'HTMLSelectField',
		'radio' => 'HTMLRadioField',
		'multiselect' => 'HTMLMultiSelectField',
		'check' => 'HTMLCheckField',
		'toggle' => 'HTMLCheckField',
		'int' => 'HTMLIntField',
		'float' => 'HTMLFloatField',
		'info' => 'HTMLInfoField',
		'selectorother' => 'HTMLSelectOrOtherField',
		'selectandother' => 'HTMLSelectAndOtherField',
		'submit' => 'HTMLSubmitField',
		'hidden' => 'HTMLHiddenField',
		'edittools' => 'HTMLEditTools',

		// HTMLTextField will output the correct type="" attribute automagically.
		// There are about four zillion other HTML5 input types, like url, but
		// we don't use those at the moment, so no point in adding all of them.
		'email' => 'HTMLTextField',
		'password' => 'HTMLTextField',
	);

	protected $mMessagePrefix;

	/** @var HTMLFormField[] */
	protected $mFlatFields;

	protected $mFieldTree;
	protected $mShowReset = false;
	public $mFieldData;

	protected $mSubmitCallback;
	protected $mValidationErrorMessage;

	protected $mPre = '';
	protected $mHeader = '';
	protected $mFooter = '';
	protected $mSectionHeaders = array();
	protected $mSectionFooters = array();
	protected $mPost = '';
	protected $mId;

	protected $mSubmitID;
	protected $mSubmitName;
	protected $mSubmitText;
	protected $mSubmitTooltip;

	protected $mTitle;
	protected $mMethod = 'post';

	/**
	 * Form action URL. false means we will use the URL to set Title
	 * @since 1.19
	 * @var bool|string
	 */
	protected $mAction = false;

	protected $mUseMultipart = false;
	protected $mHiddenFields = array();
	protected $mButtons = array();

	protected $mWrapperLegend = false;

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
	 * @var String
	 */
	protected $displayFormat = 'table';

	/**
	 * Available formats in which to display the form
	 * @var Array
	 */
	protected $availableDisplayFormats = array(
		'table',
		'div',
		'raw',
	);

	/**
	 * Build a new HTMLForm from an array of field attributes
	 * @param $descriptor Array of Field constructs, as described above
	 * @param $context IContextSource available since 1.18, will become compulsory in 1.18.
	 *     Obviates the need to call $form->setTitle()
	 * @param $messagePrefix String a prefix to go in front of default messages
	 */
	public function __construct( $descriptor, /*IContextSource*/ $context = null, $messagePrefix = '' ) {
		if ( $context instanceof IContextSource ) {
			$this->setContext( $context );
			$this->mTitle = false; // We don't need them to set a title
			$this->mMessagePrefix = $messagePrefix;
		} else {
			// B/C since 1.18
			if ( is_string( $context ) && $messagePrefix === '' ) {
				// it's actually $messagePrefix
				$this->mMessagePrefix = $context;
			}
		}

		// Expand out into a tree.
		$loadedDescriptor = array();
		$this->mFlatFields = array();

		foreach ( $descriptor as $fieldname => $info ) {
			$section = isset( $info['section'] )
				? $info['section']
				: '';

			if ( isset( $info['type'] ) && $info['type'] == 'file' ) {
				$this->mUseMultipart = true;
			}

			$field = self::loadInputFromParameters( $fieldname, $info );
			$field->mParent = $this;

			$setSection =& $loadedDescriptor;
			if ( $section ) {
				$sectionParts = explode( '/', $section );

				while ( count( $sectionParts ) ) {
					$newName = array_shift( $sectionParts );

					if ( !isset( $setSection[$newName] ) ) {
						$setSection[$newName] = array();
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
	 * Set format in which to display the form
	 * @param $format String the name of the format to use, must be one of
	 *        $this->availableDisplayFormats
	 * @since 1.20
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setDisplayFormat( $format ) {
		if ( !in_array( $format, $this->availableDisplayFormats ) ) {
			throw new MWException ( 'Display format must be one of ' . print_r( $this->availableDisplayFormats, true ) );
		}
		$this->displayFormat = $format;
		return $this;
	}

	/**
	 * Getter for displayFormat
	 * @since 1.20
	 * @return String
	 */
	public function getDisplayFormat() {
		return $this->displayFormat;
	}

	/**
	 * Add the HTMLForm-specific JavaScript, if it hasn't been
	 * done already.
	 * @deprecated since 1.18 load modules with ResourceLoader instead
	 */
	static function addJS() { wfDeprecated( __METHOD__, '1.18' ); }

	/**
	 * Initialise a new Object for the field
	 * @param $fieldname string
	 * @param $descriptor string input Descriptor, as described above
	 * @return HTMLFormField subclass
	 */
	static function loadInputFromParameters( $fieldname, $descriptor ) {
		if ( isset( $descriptor['class'] ) ) {
			$class = $descriptor['class'];
		} elseif ( isset( $descriptor['type'] ) ) {
			$class = self::$typeMappings[$descriptor['type']];
			$descriptor['class'] = $class;
		} else {
			$class = null;
		}

		if ( !$class ) {
			throw new MWException( "Descriptor with no class: " . print_r( $descriptor, true ) );
		}

		$descriptor['fieldname'] = $fieldname;

		# TODO
		# This will throw a fatal error whenever someone try to use
		# 'class' to feed a CSS class instead of 'cssclass'. Would be
		# great to avoid the fatal error and show a nice error.
		$obj = new $class( $descriptor );

		return $obj;
	}

	/**
	 * Prepare form for submission.
	 *
	 * @attention When doing method chaining, that should be the very last
	 * method call before displayForm().
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function prepareForm() {
		# Check if we have the info we need
		if ( !$this->mTitle instanceof Title && $this->mTitle !== false ) {
			throw new MWException( "You must call setTitle() on an HTMLForm" );
		}

		# Load data from the request.
		$this->loadData();
		return $this;
	}

	/**
	 * Try submitting, with edit token check first
	 * @return Status|boolean
	 */
	function tryAuthorizedSubmit() {
		$result = false;

		$submit = false;
		if ( $this->getMethod() != 'post' ) {
			$submit = true; // no session check needed
		} elseif ( $this->getRequest()->wasPosted() ) {
			$editToken = $this->getRequest()->getVal( 'wpEditToken' );
			if ( $this->getUser()->isLoggedIn() || $editToken != null ) {
				// Session tokens for logged-out users have no security value.
				// However, if the user gave one, check it in order to give a nice
				// "session expired" error instead of "permission denied" or such.
				$submit = $this->getUser()->matchEditToken( $editToken );
			} else {
				$submit = true;
			}
		}

		if ( $submit ) {
			$result = $this->trySubmit();
		}

		return $result;
	}

	/**
	 * The here's-one-I-made-earlier option: do the submission if
	 * posted, or display the form with or without funky validation
	 * errors
	 * @return Bool or Status whether submission was successful.
	 */
	function show() {
		$this->prepareForm();

		$result = $this->tryAuthorizedSubmit();
		if ( $result === true || ( $result instanceof Status && $result->isGood() ) ) {
			return $result;
		}

		$this->displayForm( $result );
		return false;
	}

	/**
	 * Validate all the fields, and call the submision callback
	 * function if everything is kosher.
	 * @return Mixed Bool true == Successful submission, Bool false
	 *	 == No submission attempted, anything else == Error to
	 *	 display.
	 */
	function trySubmit() {
		# Check for validation
		foreach ( $this->mFlatFields as $fieldname => $field ) {
			if ( !empty( $field->mParams['nodata'] ) ) {
				continue;
			}
			if ( $field->validate(
					$this->mFieldData[$fieldname],
					$this->mFieldData )
				!== true
			) {
				return isset( $this->mValidationErrorMessage )
					? $this->mValidationErrorMessage
					: array( 'htmlform-invalid-input' );
			}
		}

		$callback = $this->mSubmitCallback;
		if ( !is_callable( $callback ) ) {
			throw new MWException( 'HTMLForm: no submit callback provided. Use setSubmitCallback() to set one.' );
		}

		$data = $this->filterDataForSubmit( $this->mFieldData );

		$res = call_user_func( $callback, $data, $this );

		return $res;
	}

	/**
	 * Set a callback to a function to do something with the form
	 * once it's been successfully validated.
	 * @param $cb String function name.  The function will be passed
	 *	 the output from HTMLForm::filterDataForSubmit, and must
	 *	 return Bool true on success, Bool false if no submission
	 *	 was attempted, or String HTML output to display on error.
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setSubmitCallback( $cb ) {
		$this->mSubmitCallback = $cb;
		return $this;
	}

	/**
	 * Set a message to display on a validation error.
	 * @param $msg Mixed String or Array of valid inputs to wfMessage()
	 *	 (so each entry can be either a String or Array)
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setValidationErrorMessage( $msg ) {
		$this->mValidationErrorMessage = $msg;
		return $this;
	}

	/**
	 * Set the introductory message, overwriting any existing message.
	 * @param $msg String complete text of message to display
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setIntro( $msg ) {
		$this->setPreText( $msg );
		return $this;
	}

	/**
	 * Set the introductory message, overwriting any existing message.
	 * @since 1.19
	 * @param $msg String complete text of message to display
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setPreText( $msg ) {
		$this->mPre = $msg;
		return $this;
	}

	/**
	 * Add introductory text.
	 * @param $msg String complete text of message to display
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function addPreText( $msg ) {
		$this->mPre .= $msg;
		return $this;
	}

	/**
	 * Add header text, inside the form.
	 * @param $msg String complete text of message to display
	 * @param $section string The section to add the header to
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function addHeaderText( $msg, $section = null ) {
		if ( is_null( $section ) ) {
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
	 * @param $msg String complete text of message to display
	 * @param $section The section to add the header to
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setHeaderText( $msg, $section = null ) {
		if ( is_null( $section ) ) {
			$this->mHeader = $msg;
		} else {
			$this->mSectionHeaders[$section] = $msg;
		}
		return $this;
	}

	/**
	 * Add footer text, inside the form.
	 * @param $msg String complete text of message to display
	 * @param $section string The section to add the footer text to
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function addFooterText( $msg, $section = null ) {
		if ( is_null( $section ) ) {
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
	 * @param $msg String complete text of message to display
	 * @param $section string The section to add the footer text to
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setFooterText( $msg, $section = null ) {
		if ( is_null( $section ) ) {
			$this->mFooter = $msg;
		} else {
			$this->mSectionFooters[$section] = $msg;
		}
		return $this;
	}

	/**
	 * Add text to the end of the display.
	 * @param $msg String complete text of message to display
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function addPostText( $msg ) {
		$this->mPost .= $msg;
		return $this;
	}

	/**
	 * Set text at the end of the display.
	 * @param $msg String complete text of message to display
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setPostText( $msg ) {
		$this->mPost = $msg;
		return $this;
	}

	/**
	 * Add a hidden field to the output
	 * @param $name String field name.  This will be used exactly as entered
	 * @param $value String field value
	 * @param $attribs Array
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function addHiddenField( $name, $value, $attribs = array() ) {
		$attribs += array( 'name' => $name );
		$this->mHiddenFields[] = array( $value, $attribs );
		return $this;
	}

	/**
	 * Add a button to the form
	 * @param $name String field name.
	 * @param $value String field value
	 * @param $id String DOM id for the button (default: null)
	 * @param $attribs Array
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function addButton( $name, $value, $id = null, $attribs = null ) {
		$this->mButtons[] = compact( 'name', 'value', 'id', 'attribs' );
		return $this;
	}

	/**
	 * Display the form (sending to $wgOut), with an appropriate error
	 * message or stack of messages, and any validation errors, etc.
	 *
	 * @attention You should call prepareForm() before calling this function.
	 * Moreover, when doing method chaining this should be the very last method
	 * call just after prepareForm().
	 *
	 * @param $submitResult Mixed output from HTMLForm::trySubmit()
	 * @return Nothing, should be last call
	 */
	function displayForm( $submitResult ) {
		$this->getOutput()->addHTML( $this->getHTML( $submitResult ) );
	}

	/**
	 * Returns the raw HTML generated by the form
	 * @param $submitResult Mixed output from HTMLForm::trySubmit()
	 * @return string
	 */
	function getHTML( $submitResult ) {
		# For good measure (it is the default)
		$this->getOutput()->preventClickjacking();
		$this->getOutput()->addModules( 'mediawiki.htmlform' );

		$html = ''
			. $this->getErrors( $submitResult )
			. $this->mHeader
			. $this->getBody()
			. $this->getHiddenFields()
			. $this->getButtons()
			. $this->mFooter
		;

		$html = $this->wrapForm( $html );

		return '' . $this->mPre . $html . $this->mPost;
	}

	/**
	 * Wrap the form innards in an actual "<form>" element
	 * @param $html String HTML contents to wrap.
	 * @return String wrapped HTML.
	 */
	function wrapForm( $html ) {

		# Include a <fieldset> wrapper for style, if requested.
		if ( $this->mWrapperLegend !== false ) {
			$html = Xml::fieldset( $this->mWrapperLegend, $html );
		}
		# Use multipart/form-data
		$encType = $this->mUseMultipart
			? 'multipart/form-data'
			: 'application/x-www-form-urlencoded';
		# Attributes
		$attribs = array(
			'action'  => $this->mAction === false ? $this->getTitle()->getFullURL() : $this->mAction,
			'method'  => $this->mMethod,
			'class'   => 'visualClear',
			'enctype' => $encType,
		);
		if ( !empty( $this->mId ) ) {
			$attribs['id'] = $this->mId;
		}

		return Html::rawElement( 'form', $attribs, $html );
	}

	/**
	 * Get the hidden fields that should go inside the form.
	 * @return String HTML.
	 */
	function getHiddenFields() {
		global $wgArticlePath;

		$html = '';
		if ( $this->getMethod() == 'post' ) {
			$html .= Html::hidden( 'wpEditToken', $this->getUser()->getEditToken(), array( 'id' => 'wpEditToken' ) ) . "\n";
			$html .= Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) . "\n";
		}

		if ( strpos( $wgArticlePath, '?' ) !== false && $this->getMethod() == 'get' ) {
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
	 * @return String HTML.
	 */
	function getButtons() {
		$html = '';
		$attribs = array();

		if ( isset( $this->mSubmitID ) ) {
			$attribs['id'] = $this->mSubmitID;
		}

		if ( isset( $this->mSubmitName ) ) {
			$attribs['name'] = $this->mSubmitName;
		}

		if ( isset( $this->mSubmitTooltip ) ) {
			$attribs += Linker::tooltipAndAccesskeyAttribs( $this->mSubmitTooltip );
		}

		$attribs['class'] = 'mw-htmlform-submit';

		$html .= Xml::submitButton( $this->getSubmitText(), $attribs ) . "\n";

		if ( $this->mShowReset ) {
			$html .= Html::element(
				'input',
				array(
					'type' => 'reset',
					'value' => $this->msg( 'htmlform-reset' )->text()
				)
			) . "\n";
		}

		foreach ( $this->mButtons as $button ) {
			$attrs = array(
				'type'  => 'submit',
				'name'  => $button['name'],
				'value' => $button['value']
			);

			if ( $button['attribs'] ) {
				$attrs += $button['attribs'];
			}

			if ( isset( $button['id'] ) ) {
				$attrs['id'] = $button['id'];
			}

			$html .= Html::element( 'input', $attrs );
		}

		return $html;
	}

	/**
	 * Get the whole body of the form.
	 * @return String
	 */
	function getBody() {
		return $this->displaySection( $this->mFieldTree );
	}

	/**
	 * Format and display an error message stack.
	 * @param $errors String|Array|Status
	 * @return String
	 */
	function getErrors( $errors ) {
		if ( $errors instanceof Status ) {
			if ( $errors->isOK() ) {
				$errorstr = '';
			} else {
				$errorstr = $this->getOutput()->parse( $errors->getWikiText() );
			}
		} elseif ( is_array( $errors ) ) {
			$errorstr = $this->formatErrors( $errors );
		} else {
			$errorstr = $errors;
		}

		return $errorstr
			? Html::rawElement( 'div', array( 'class' => 'error' ), $errorstr )
			: '';
	}

	/**
	 * Format a stack of error messages into a single HTML string
	 * @param $errors Array of message keys/values
	 * @return String HTML, a "<ul>" list of errors
	 */
	public static function formatErrors( $errors ) {
		$errorstr = '';

		foreach ( $errors as $error ) {
			if ( is_array( $error ) ) {
				$msg = array_shift( $error );
			} else {
				$msg = $error;
				$error = array();
			}

			$errorstr .= Html::rawElement(
				'li',
				array(),
				wfMessage( $msg, $error )->parse()
			);
		}

		$errorstr = Html::rawElement( 'ul', array(), $errorstr );

		return $errorstr;
	}

	/**
	 * Set the text for the submit button
	 * @param $t String plaintext.
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setSubmitText( $t ) {
		$this->mSubmitText = $t;
		return $this;
	}

	/**
	 * Set the text for the submit button to a message
	 * @since 1.19
	 * @param $msg String message key
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setSubmitTextMsg( $msg ) {
		$this->setSubmitText( $this->msg( $msg )->text() );
		return $this;
	}

	/**
	 * Get the text for the submit button, either customised or a default.
	 * @return string
	 */
	function getSubmitText() {
		return $this->mSubmitText
			? $this->mSubmitText
			: $this->msg( 'htmlform-submit' )->text();
	}

	/**
	 * @param $name String Submit button name
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setSubmitName( $name ) {
		$this->mSubmitName = $name;
		return $this;
	}

	/**
	 * @param $name String Tooltip for the submit button
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setSubmitTooltip( $name ) {
		$this->mSubmitTooltip = $name;
		return $this;
	}

	/**
	 * Set the id for the submit button.
	 * @param $t String.
	 * @todo FIXME: Integrity of $t is *not* validated
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setSubmitID( $t ) {
		$this->mSubmitID = $t;
		return $this;
	}

	/**
	 * @param $id String DOM id for the form
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setId( $id ) {
		$this->mId = $id;
		return $this;
	}
	/**
	 * Prompt the whole form to be wrapped in a "<fieldset>", with
	 * this text as its "<legend>" element.
	 * @param $legend String HTML to go inside the "<legend>" element.
	 *	 Will be escaped
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
	 * @param $msg String message key
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setWrapperLegendMsg( $msg ) {
		$this->setWrapperLegend( $this->msg( $msg )->text() );
		return $this;
	}

	/**
	 * Set the prefix for various default messages
	 * @todo currently only used for the "<fieldset>" legend on forms
	 * with multiple sections; should be used elsewhre?
	 * @param $p String
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setMessagePrefix( $p ) {
		$this->mMessagePrefix = $p;
		return $this;
	}

	/**
	 * Set the title for form submission
	 * @param $t Title of page the form is on/should be posted to
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function setTitle( $t ) {
		$this->mTitle = $t;
		return $this;
	}

	/**
	 * Get the title
	 * @return Title
	 */
	function getTitle() {
		return $this->mTitle === false
			? $this->getContext()->getTitle()
			: $this->mTitle;
	}

	/**
	 * Set the method used to submit the form
	 * @param $method String
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setMethod( $method = 'post' ) {
		$this->mMethod = $method;
		return $this;
	}

	public function getMethod() {
		return $this->mMethod;
	}

	/**
	 * @todo Document
	 * @param $fields array[]|HTMLFormField[] array of fields (either arrays or objects)
	 * @param $sectionName string ID attribute of the "<table>" tag for this section, ignored if empty
	 * @param $fieldsetIDPrefix string ID prefix for the "<fieldset>" tag of each subsection, ignored if empty
	 * @return String
	 */
	public function displaySection( $fields, $sectionName = '', $fieldsetIDPrefix = '' ) {
		$displayFormat = $this->getDisplayFormat();

		$html = '';
		$subsectionHtml = '';
		$hasLabel = false;

		$getFieldHtmlMethod = ( $displayFormat == 'table' ) ? 'getTableRow' : 'get' . ucfirst( $displayFormat );

		foreach ( $fields as $key => $value ) {
			if ( $value instanceof HTMLFormField ) {
				$v = empty( $value->mParams['nodata'] )
					? $this->mFieldData[$key]
					: $value->getDefault();
				$html .= $value->$getFieldHtmlMethod( $v );

				$labelValue = trim( $value->getLabel() );
				if ( $labelValue != '&#160;' && $labelValue !== '' ) {
					$hasLabel = true;
				}
			} elseif ( is_array( $value ) ) {
				$section = $this->displaySection( $value, $key );
				$legend = $this->getLegend( $key );
				if ( isset( $this->mSectionHeaders[$key] ) ) {
					$section = $this->mSectionHeaders[$key] . $section;
				}
				if ( isset( $this->mSectionFooters[$key] ) ) {
					$section .= $this->mSectionFooters[$key];
				}
				$attributes = array();
				if ( $fieldsetIDPrefix ) {
					$attributes['id'] = Sanitizer::escapeId( "$fieldsetIDPrefix$key" );
				}
				$subsectionHtml .= Xml::fieldset( $legend, $section, $attributes ) . "\n";
			}
		}

		if ( $displayFormat !== 'raw' ) {
			$classes = array();

			if ( !$hasLabel ) { // Avoid strange spacing when no labels exist
				$classes[] = 'mw-htmlform-nolabel';
			}

			$attribs = array(
				'class' => implode( ' ', $classes ),
			);

			if ( $sectionName ) {
				$attribs['id'] = Sanitizer::escapeId( "mw-htmlform-$sectionName" );
			}

			if ( $displayFormat === 'table' ) {
				$html = Html::rawElement( 'table', $attribs,
					Html::rawElement( 'tbody', array(), "\n$html\n" ) ) . "\n";
			} elseif ( $displayFormat === 'div' ) {
				$html = Html::rawElement( 'div', $attribs, "\n$html\n" );
			}
		}

		if ( $this->mSubSectionBeforeFields ) {
			return $subsectionHtml . "\n" . $html;
		} else {
			return $html . "\n" . $subsectionHtml;
		}
	}

	/**
	 * Construct the form fields from the Descriptor array
	 */
	function loadData() {
		$fieldData = array();

		foreach ( $this->mFlatFields as $fieldname => $field ) {
			if ( !empty( $field->mParams['nodata'] ) ) {
				continue;
			} elseif ( !empty( $field->mParams['disabled'] ) ) {
				$fieldData[$fieldname] = $field->getDefault();
			} else {
				$fieldData[$fieldname] = $field->loadDataFromRequest( $this->getRequest() );
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
	 * @param $suppressReset Bool set to false to re-enable the
	 *	 button again
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	function suppressReset( $suppressReset = true ) {
		$this->mShowReset = !$suppressReset;
		return $this;
	}

	/**
	 * Overload this if you want to apply special filtration routines
	 * to the form as a whole, after it's submitted but before it's
	 * processed.
	 * @param $data
	 * @return
	 */
	function filterDataForSubmit( $data ) {
		return $data;
	}

	/**
	 * Get a string to go in the "<legend>" of a section fieldset.
	 * Override this if you want something more complicated.
	 * @param $key String
	 * @return String
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
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setAction( $action ) {
		$this->mAction = $action;
		return $this;
	}

}

/**
 * The parent class to generate form fields.  Any field type should
 * be a subclass of this.
 */
abstract class HTMLFormField {

	protected $mValidationCallback;
	protected $mFilterCallback;
	protected $mName;
	public $mParams;
	protected $mLabel;	# String label.  Set on construction
	protected $mID;
	protected $mClass = '';
	protected $mDefault;

	/**
	 * @var HTMLForm
	 */
	public $mParent;

	/**
	 * This function must be implemented to return the HTML to generate
	 * the input object itself.  It should not implement the surrounding
	 * table cells/rows, or labels/help messages.
	 * @param $value String the value to set the input to; eg a default
	 *	 text for a text input.
	 * @return String valid HTML.
	 */
	abstract function getInputHTML( $value );

	/**
	 * Get a translated interface message
	 *
	 * This is a wrapper arround $this->mParent->msg() if $this->mParent is set
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
	 * @param $value String the value the field was submitted with
	 * @param $alldata Array the data collected from the form
	 * @return Mixed Bool true on success, or String error to display.
	 */
	function validate( $value, $alldata ) {
		if ( isset( $this->mParams['required'] ) && $this->mParams['required'] !== false && $value === '' ) {
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
	 * Get the value that this input has been set to from a posted form,
	 * or the input's default value if it has not been set.
	 * @param $request WebRequest
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
	 * @param $params array Associative Array. See HTMLForm doc for syntax.
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
			$this->mLabel = $params['label'];
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
	}

	/**
	 * Get the complete table row for the input, including help text,
	 * labels, and whatever.
	 * @param $value String the value to set the input to.
	 * @return String complete HTML table row.
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
			$html = Html::rawElement( 'tr',
				array( 'class' => 'mw-htmlform-vertical-label' ), $label );
			$html .= Html::rawElement( 'tr',
				array( 'class' => "mw-htmlform-field-$fieldType {$this->mClass} $errorClass" ),
				$field );
		} else {
			$html = Html::rawElement( 'tr',
				array( 'class' => "mw-htmlform-field-$fieldType {$this->mClass} $errorClass" ),
				$label . $field );
		}

		return $html . $helptext;
	}

	/**
	 * Get the complete div for the input, including help text,
	 * labels, and whatever.
	 * @since 1.20
	 * @param $value String the value to set the input to.
	 * @return String complete HTML table row.
	 */
	public function getDiv( $value ) {
		list( $errors, $errorClass ) = $this->getErrorsAndErrorClass( $value );
		$inputHtml = $this->getInputHTML( $value );
		$fieldType = get_class( $this );
		$helptext = $this->getHelpTextHtmlDiv( $this->getHelpText() );
		$cellAttributes = array();
		$label = $this->getLabelHtml( $cellAttributes );

		$field = Html::rawElement(
			'div',
			array( 'class' => 'mw-input' ) + $cellAttributes,
			$inputHtml . "\n$errors"
		);
		$html = Html::rawElement( 'div',
			array( 'class' => "mw-htmlform-field-$fieldType {$this->mClass} $errorClass" ),
			$label . $field );
		$html .= $helptext;
		return $html;
	}

	/**
	 * Get the complete raw fields for the input, including help text,
	 * labels, and whatever.
	 * @since 1.20
	 * @param $value String the value to set the input to.
	 * @return String complete HTML table row.
	 */
	public function getRaw( $value ) {
		list( $errors, $errorClass ) = $this->getErrorsAndErrorClass( $value );
		$inputHtml = $this->getInputHTML( $value );
		$fieldType = get_class( $this );
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
	 * @param $helptext String|null
	 * @return String
	 */
	public function getHelpTextHtmlTable( $helptext ) {
		if ( is_null( $helptext ) ) {
			return '';
		}

		$row = Html::rawElement(
			'td',
			array( 'colspan' => 2, 'class' => 'htmlform-tip' ),
			$helptext
		);
		$row = Html::rawElement( 'tr', array(), $row );
		return $row;
	}

	/**
	 * Generate help text HTML in div format
	 * @since 1.20
	 * @param $helptext String|null
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
	 * @param $helptext String|null
	 * @return String
	 */
	public function getHelpTextHtmlRaw( $helptext ) {
		return $this->getHelpTextHtmlDiv( $helptext );
	}

	/**
	 * Determine the help text to display
	 * @since 1.20
	 * @return String
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
		}
		elseif ( isset( $this->mParams['help'] ) ) {
			$helptext = $this->mParams['help'];
		}
		return $helptext;
	}

	/**
	 * Determine form errors to display and their classes
	 * @since 1.20
	 * @param $value String the value of the input
	 * @return Array
	 */
	public function getErrorsAndErrorClass( $value ) {
		$errors = $this->validate( $value, $this->mParent->mFieldData );

		if ( $errors === true || ( !$this->mParent->getRequest()->wasPosted() && ( $this->mParent->getMethod() == 'post' ) ) ) {
			$errors = '';
			$errorClass = '';
		} else {
			$errors = self::formatErrors( $errors );
			$errorClass = 'mw-htmlform-invalid-input';
		}
		return array( $errors, $errorClass );
	}

	function getLabel() {
		return $this->mLabel;
	}

	function getLabelHtml( $cellAttributes = array() ) {
		# Don't output a for= attribute for labels with no associated input.
		# Kind of hacky here, possibly we don't want these to be <label>s at all.
		$for = array();

		if ( $this->needsLabel() ) {
			$for['for'] = $this->mID;
		}

		$displayFormat = $this->mParent->getDisplayFormat();
		$labelElement = Html::rawElement( 'label', $for, $this->getLabel() );

		if ( $displayFormat == 'table' ) {
			return Html::rawElement( 'td', array( 'class' => 'mw-label' ) + $cellAttributes,
				Html::rawElement( 'label', $for, $this->getLabel() )
			);
		} elseif ( $displayFormat == 'div' ) {
			return Html::rawElement( 'div', array( 'class' => 'mw-label' ) + $cellAttributes,
				Html::rawElement( 'label', $for, $this->getLabel() )
			);
		} else {
			return $labelElement;
		}
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
	 * flatten an array of options to a single array, for instance,
	 * a set of "<options>" inside "<optgroups>".
	 * @param $options array Associative Array with values either Strings
	 *	 or Arrays
	 * @return Array flattened input
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
	 * @param $errors String|Message|Array of strings or Message instances
	 * @return String html
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

class HTMLTextField extends HTMLFormField {
	function getSize() {
		return isset( $this->mParams['size'] )
			? $this->mParams['size']
			: 45;
	}

	function getInputHTML( $value ) {
		$attribs = array(
			'id' => $this->mID,
			'name' => $this->mName,
			'size' => $this->getSize(),
			'value' => $value,
		) + $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			$attribs['class'] = $this->mClass;
		}

		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		# TODO: Enforce pattern, step, required, readonly on the server side as
		# well
		$allowedParams = array( 'min', 'max', 'pattern', 'title', 'step',
			'placeholder', 'list', 'maxlength' );
		foreach ( $allowedParams as $param ) {
			if ( isset( $this->mParams[$param] ) ) {
				$attribs[$param] = $this->mParams[$param];
			}
		}

		foreach ( array( 'required', 'autofocus', 'multiple', 'readonly' ) as $param ) {
			if ( isset( $this->mParams[$param] ) ) {
				$attribs[$param] = '';
			}
		}

		# Implement tiny differences between some field variants
		# here, rather than creating a new class for each one which
		# is essentially just a clone of this one.
		if ( isset( $this->mParams['type'] ) ) {
			switch ( $this->mParams['type'] ) {
				case 'email':
					$attribs['type'] = 'email';
					break;
				case 'int':
					$attribs['type'] = 'number';
					break;
				case 'float':
					$attribs['type'] = 'number';
					$attribs['step'] = 'any';
					break;
				# Pass through
				case 'password':
				case 'file':
					$attribs['type'] = $this->mParams['type'];
					break;
			}
		}

		return Html::element( 'input', $attribs );
	}
}
class HTMLTextAreaField extends HTMLFormField {
	function getCols() {
		return isset( $this->mParams['cols'] )
			? $this->mParams['cols']
			: 80;
	}

	function getRows() {
		return isset( $this->mParams['rows'] )
			? $this->mParams['rows']
			: 25;
	}

	function getInputHTML( $value ) {
		$attribs = array(
			'id' => $this->mID,
			'name' => $this->mName,
			'cols' => $this->getCols(),
			'rows' => $this->getRows(),
		) + $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			$attribs['class'] = $this->mClass;
		}

		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		if ( !empty( $this->mParams['readonly'] ) ) {
			$attribs['readonly'] = 'readonly';
		}

		if ( isset( $this->mParams['placeholder'] ) ) {
			$attribs['placeholder'] = $this->mParams['placeholder'];
		}

		foreach ( array( 'required', 'autofocus' ) as $param ) {
			if ( isset( $this->mParams[$param] ) ) {
				$attribs[$param] = '';
			}
		}

		return Html::element( 'textarea', $attribs, $value );
	}
}

/**
 * A field that will contain a numeric value
 */
class HTMLFloatField extends HTMLTextField {
	function getSize() {
		return isset( $this->mParams['size'] )
			? $this->mParams['size']
			: 20;
	}

	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		$value = trim( $value );

		# http://dev.w3.org/html5/spec/common-microsyntaxes.html#real-numbers
		# with the addition that a leading '+' sign is ok.
		if ( !preg_match( '/^((\+|\-)?\d+(\.\d+)?(E(\+|\-)?\d+)?)?$/i', $value ) ) {
			return $this->msg( 'htmlform-float-invalid' )->parseAsBlock();
		}

		# The "int" part of these message names is rather confusing.
		# They make equal sense for all numbers.
		if ( isset( $this->mParams['min'] ) ) {
			$min = $this->mParams['min'];

			if ( $min > $value ) {
				return $this->msg( 'htmlform-int-toolow', $min )->parseAsBlock();
			}
		}

		if ( isset( $this->mParams['max'] ) ) {
			$max = $this->mParams['max'];

			if ( $max < $value ) {
				return $this->msg( 'htmlform-int-toohigh', $max )->parseAsBlock();
			}
		}

		return true;
	}
}

/**
 * A field that must contain a number
 */
class HTMLIntField extends HTMLFloatField {
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		# http://dev.w3.org/html5/spec/common-microsyntaxes.html#signed-integers
		# with the addition that a leading '+' sign is ok. Note that leading zeros
		# are fine, and will be left in the input, which is useful for things like
		# phone numbers when you know that they are integers (the HTML5 type=tel
		# input does not require its value to be numeric).  If you want a tidier
		# value to, eg, save in the DB, clean it up with intval().
		if ( !preg_match( '/^((\+|\-)?\d+)?$/', trim( $value ) )
		) {
			return $this->msg( 'htmlform-int-invalid' )->parseAsBlock();
		}

		return true;
	}
}

/**
 * A checkbox field
 */
class HTMLCheckField extends HTMLFormField {
	function getInputHTML( $value ) {
		if ( !empty( $this->mParams['invert'] ) ) {
			$value = !$value;
		}

		$attr = $this->getTooltipAndAccessKey();
		$attr['id'] = $this->mID;

		if ( !empty( $this->mParams['disabled'] ) ) {
			$attr['disabled'] = 'disabled';
		}

		if ( $this->mClass !== '' ) {
			$attr['class'] = $this->mClass;
		}

		return Xml::check( $this->mName, $value, $attr ) . '&#160;' .
			Html::rawElement( 'label', array( 'for' => $this->mID ), $this->mLabel );
	}

	/**
	 * For a checkbox, the label goes on the right hand side, and is
	 * added in getInputHTML(), rather than HTMLFormField::getRow()
	 * @return String
	 */
	function getLabel() {
		return '&#160;';
	}

	/**
	 * @param  $request WebRequest
	 * @return String
	 */
	function loadDataFromRequest( $request ) {
		$invert = false;
		if ( isset( $this->mParams['invert'] ) && $this->mParams['invert'] ) {
			$invert = true;
		}

		// GetCheck won't work like we want for checks.
		// Fetch the value in either one of the two following case:
		// - we have a valid token (form got posted or GET forged by the user)
		// - checkbox name has a value (false or true), ie is not null
		if ( $request->getCheck( 'wpEditToken' ) || $request->getVal( $this->mName ) !== null ) {
			// XOR has the following truth table, which is what we want
			// INVERT VALUE | OUTPUT
			// true   true  | false
			// false  true  | true
			// false  false | false
			// true   false | true
			return $request->getBool( $this->mName ) xor $invert;
		} else {
			return $this->getDefault();
		}
	}
}

/**
 * A select dropdown field.  Basically a wrapper for Xmlselect class
 */
class HTMLSelectField extends HTMLFormField {
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		$validOptions = HTMLFormField::flattenOptions( $this->mParams['options'] );

		if ( in_array( $value, $validOptions ) )
			return true;
		else
			return $this->msg( 'htmlform-select-badoption' )->parse();
	}

	function getInputHTML( $value ) {
		$select = new XmlSelect( $this->mName, $this->mID, strval( $value ) );

		# If one of the options' 'name' is int(0), it is automatically selected.
		# because PHP sucks and thinks int(0) == 'some string'.
		# Working around this by forcing all of them to strings.
		foreach ( $this->mParams['options'] as &$opt ) {
			if ( is_int( $opt ) ) {
				$opt = strval( $opt );
			}
		}
		unset( $opt ); # PHP keeps $opt around as a reference, which is a bit scary

		if ( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
		}

		if ( $this->mClass !== '' ) {
			$select->setAttribute( 'class', $this->mClass );
		}

		$select->addOptions( $this->mParams['options'] );

		return $select->getHTML();
	}
}

/**
 * Select dropdown field, with an additional "other" textbox.
 */
class HTMLSelectOrOtherField extends HTMLTextField {
	static $jsAdded = false;

	function __construct( $params ) {
		if ( !in_array( 'other', $params['options'], true ) ) {
			$msg = isset( $params['other'] ) ?
				$params['other'] :
				wfMessage( 'htmlform-selectorother-other' )->text();
			$params['options'][$msg] = 'other';
		}

		parent::__construct( $params );
	}

	static function forceToStringRecursive( $array ) {
		if ( is_array( $array ) ) {
			return array_map( array( __CLASS__, 'forceToStringRecursive' ), $array );
		} else {
			return strval( $array );
		}
	}

	function getInputHTML( $value ) {
		$valInSelect = false;

		if ( $value !== false ) {
			$valInSelect = in_array(
				$value,
				HTMLFormField::flattenOptions( $this->mParams['options'] )
			);
		}

		$selected = $valInSelect ? $value : 'other';

		$opts = self::forceToStringRecursive( $this->mParams['options'] );

		$select = new XmlSelect( $this->mName, $this->mID, $selected );
		$select->addOptions( $opts );

		$select->setAttribute( 'class', 'mw-htmlform-select-or-other' );

		$tbAttribs = array( 'id' => $this->mID . '-other', 'size' => $this->getSize() );

		if ( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
			$tbAttribs['disabled'] = 'disabled';
		}

		$select = $select->getHTML();

		if ( isset( $this->mParams['maxlength'] ) ) {
			$tbAttribs['maxlength'] = $this->mParams['maxlength'];
		}

		if ( $this->mClass !== '' ) {
			$tbAttribs['class'] = $this->mClass;
		}

		$textbox = Html::input(
			$this->mName . '-other',
			$valInSelect ? '' : $value,
			'text',
			$tbAttribs
		);

		return "$select<br />\n$textbox";
	}

	/**
	 * @param  $request WebRequest
	 * @return String
	 */
	function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {
			$val = $request->getText( $this->mName );

			if ( $val == 'other' ) {
				$val = $request->getText( $this->mName . '-other' );
			}

			return $val;
		} else {
			return $this->getDefault();
		}
	}
}

/**
 * Multi-select field
 */
class HTMLMultiSelectField extends HTMLFormField {

	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( !is_array( $value ) ) {
			return false;
		}

		# If all options are valid, array_intersect of the valid options
		# and the provided options will return the provided options.
		$validOptions = HTMLFormField::flattenOptions( $this->mParams['options'] );

		$validValues = array_intersect( $value, $validOptions );
		if ( count( $validValues ) == count( $value ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' )->parse();
		}
	}

	function getInputHTML( $value ) {
		$html = $this->formatOptions( $this->mParams['options'], $value );

		return $html;
	}

	function formatOptions( $options, $value ) {
		$html = '';

		$attribs = array();

		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		foreach ( $options as $label => $info ) {
			if ( is_array( $info ) ) {
				$html .= Html::rawElement( 'h1', array(), $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$thisAttribs = array( 'id' => "{$this->mID}-$info", 'value' => $info );

				$checkbox = Xml::check(
					$this->mName . '[]',
					in_array( $info, $value, true ),
					$attribs + $thisAttribs );
				$checkbox .= '&#160;' . Html::rawElement( 'label', array( 'for' => "{$this->mID}-$info" ), $label );

				$html .= ' ' . Html::rawElement( 'div', array( 'class' => 'mw-htmlform-flatlist-item' ), $checkbox );
			}
		}

		return $html;
	}

	/**
	 * @param  $request WebRequest
	 * @return String
	 */
	function loadDataFromRequest( $request ) {
		if ( $this->mParent->getMethod() == 'post' ) {
			if ( $request->wasPosted() ) {
				# Checkboxes are just not added to the request arrays if they're not checked,
				# so it's perfectly possible for there not to be an entry at all
				return $request->getArray( $this->mName, array() );
			} else {
				# That's ok, the user has not yet submitted the form, so show the defaults
				return $this->getDefault();
			}
		} else {
			# This is the impossible case: if we look at $_GET and see no data for our
			# field, is it because the user has not yet submitted the form, or that they
			# have submitted it with all the options unchecked? We will have to assume the
			# latter, which basically means that you can't specify 'positive' defaults
			# for GET forms.
			# @todo FIXME...
			return $request->getArray( $this->mName, array() );
		}
	}

	function getDefault() {
		if ( isset( $this->mDefault ) ) {
			return $this->mDefault;
		} else {
			return array();
		}
	}

	protected function needsLabel() {
		return false;
	}
}

/**
 * Double field with a dropdown list constructed from a system message in the format
 *     * Optgroup header
 *     ** <option value>
 *     * New Optgroup header
 * Plus a text field underneath for an additional reason.  The 'value' of the field is
 * "<select>: <extra reason>", or "<extra reason>" if nothing has been selected in the
 * select dropdown.
 * @todo FIXME: If made 'required', only the text field should be compulsory.
 */
class HTMLSelectAndOtherField extends HTMLSelectField {

	function __construct( $params ) {
		if ( array_key_exists( 'other', $params ) ) {
		} elseif ( array_key_exists( 'other-message', $params ) ) {
			$params['other'] = wfMessage( $params['other-message'] )->plain();
		} else {
			$params['other'] = null;
		}

		if ( array_key_exists( 'options', $params ) ) {
			# Options array already specified
		} elseif ( array_key_exists( 'options-message', $params ) ) {
			# Generate options array from a system message
			$params['options'] = self::parseMessage(
				wfMessage( $params['options-message'] )->inContentLanguage()->plain(),
				$params['other']
			);
		} else {
			# Sulk
			throw new MWException( 'HTMLSelectAndOtherField called without any options' );
		}
		$this->mFlatOptions = self::flattenOptions( $params['options'] );

		parent::__construct( $params );
	}

	/**
	 * Build a drop-down box from a textual list.
	 * @param $string String message text
	 * @param $otherName String name of "other reason" option
	 * @return Array
	 * TODO: this is copied from Xml::listDropDown(), deprecate/avoid duplication?
	 */
	public static function parseMessage( $string, $otherName = null ) {
		if ( $otherName === null ) {
			$otherName = wfMessage( 'htmlform-selectorother-other' )->plain();
		}

		$optgroup = false;
		$options = array( $otherName => 'other' );

		foreach ( explode( "\n", $string ) as $option ) {
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
					$options[$opt] = $opt;
				} else {
					$options[$optgroup][$opt] = $opt;
				}
			} else {
				# groupless reason list
				$optgroup = false;
				$options[$option] = $option;
			}
		}

		return $options;
	}

	function getInputHTML( $value ) {
		$select = parent::getInputHTML( $value[1] );

		$textAttribs = array(
			'id' => $this->mID . '-other',
			'size' => $this->getSize(),
		);

		if ( $this->mClass !== '' ) {
			$textAttribs['class'] = $this->mClass;
		}

		foreach ( array( 'required', 'autofocus', 'multiple', 'disabled' ) as $param ) {
			if ( isset( $this->mParams[$param] ) ) {
				$textAttribs[$param] = '';
			}
		}

		$textbox = Html::input(
			$this->mName . '-other',
			$value[2],
			'text',
			$textAttribs
		);

		return "$select<br />\n$textbox";
	}

	/**
	 * @param  $request WebRequest
	 * @return Array("<overall message>","<select value>","<text field value>")
	 */
	function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {

			$list = $request->getText( $this->mName );
			$text = $request->getText( $this->mName . '-other' );

			if ( $list == 'other' ) {
				$final = $text;
			} elseif ( !in_array( $list, $this->mFlatOptions ) ) {
				# User has spoofed the select form to give an option which wasn't
				# in the original offer.  Sulk...
				$final = $text;
			} elseif ( $text == '' ) {
				$final = $list;
			} else {
				$final = $list . $this->msg( 'colon-separator' )->inContentLanguage()->text() . $text;
			}

		} else {
			$final = $this->getDefault();

			$list = 'other';
			$text = $final;
			foreach ( $this->mFlatOptions as $option ) {
				$match = $option . $this->msg( 'colon-separator' )->inContentLanguage()->text();
				if ( strpos( $text, $match ) === 0 ) {
					$list = $option;
					$text = substr( $text, strlen( $match ) );
					break;
				}
			}
		}
		return array( $final, $list, $text );
	}

	function getSize() {
		return isset( $this->mParams['size'] )
			? $this->mParams['size']
			: 45;
	}

	function validate( $value, $alldata ) {
		# HTMLSelectField forces $value to be one of the options in the select
		# field, which is not useful here.  But we do want the validation further up
		# the chain
		$p = parent::validate( $value[1], $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( isset( $this->mParams['required'] ) && $this->mParams['required'] !== false && $value[1] === '' ) {
			return $this->msg( 'htmlform-required' )->parse();
		}

		return true;
	}
}

/**
 * Radio checkbox fields.
 */
class HTMLRadioField extends HTMLFormField {


	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( !is_string( $value ) && !is_int( $value ) ) {
			return false;
		}

		$validOptions = HTMLFormField::flattenOptions( $this->mParams['options'] );

		if ( in_array( $value, $validOptions ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' )->parse();
		}
	}

	/**
	 * This returns a block of all the radio options, in one cell.
	 * @see includes/HTMLFormField#getInputHTML()
	 * @param $value String
	 * @return String
	 */
	function getInputHTML( $value ) {
		$html = $this->formatOptions( $this->mParams['options'], $value );

		return $html;
	}

	function formatOptions( $options, $value ) {
		$html = '';

		$attribs = array();
		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		# TODO: should this produce an unordered list perhaps?
		foreach ( $options as $label => $info ) {
			if ( is_array( $info ) ) {
				$html .= Html::rawElement( 'h1', array(), $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$id = Sanitizer::escapeId( $this->mID . "-$info" );
				$radio = Xml::radio(
					$this->mName,
					$info,
					$info == $value,
					$attribs + array( 'id' => $id )
				);
				$radio .= '&#160;' .
						Html::rawElement( 'label', array( 'for' => $id ), $label );

				$html .= ' ' . Html::rawElement( 'div', array( 'class' => 'mw-htmlform-flatlist-item' ), $radio );
			}
		}

		return $html;
	}

	protected function needsLabel() {
		return false;
	}
}

/**
 * An information field (text blob), not a proper input.
 */
class HTMLInfoField extends HTMLFormField {
	public function __construct( $info ) {
		$info['nodata'] = true;

		parent::__construct( $info );
	}

	public function getInputHTML( $value ) {
		return !empty( $this->mParams['raw'] ) ? $value : htmlspecialchars( $value );
	}

	public function getTableRow( $value ) {
		if ( !empty( $this->mParams['rawrow'] ) ) {
			return $value;
		}

		return parent::getTableRow( $value );
	}

	/**
	 * @since 1.20
	 */
	public function getDiv( $value ) {
		if ( !empty( $this->mParams['rawrow'] ) ) {
			return $value;
		}

		return parent::getDiv( $value );
	}

	/**
	 * @since 1.20
	 */
	public function getRaw( $value ) {
		if ( !empty( $this->mParams['rawrow'] ) ) {
			return $value;
		}

		return parent::getRaw( $value );
	}

	protected function needsLabel() {
		return false;
	}
}

class HTMLHiddenField extends HTMLFormField {
	public function __construct( $params ) {
		parent::__construct( $params );

		# Per HTML5 spec, hidden fields cannot be 'required'
		# http://dev.w3.org/html5/spec/states-of-the-type-attribute.html#hidden-state
		unset( $this->mParams['required'] );
	}

	public function getTableRow( $value ) {
		$params = array();
		if ( $this->mID ) {
			$params['id'] = $this->mID;
		}

		$this->mParent->addHiddenField(
			$this->mName,
			$this->mDefault,
			$params
		);

		return '';
	}

	/**
	 * @since 1.20
	 */
	public function getDiv( $value ) {
		return $this->getTableRow( $value );
	}

	/**
	 * @since 1.20
	 */
	public function getRaw( $value ) {
		return $this->getTableRow( $value );
	}

	public function getInputHTML( $value ) { return ''; }
}

/**
 * Add a submit button inline in the form (as opposed to
 * HTMLForm::addButton(), which will add it at the end).
 */
class HTMLSubmitField extends HTMLFormField {

	public function __construct( $info ) {
		$info['nodata'] = true;
		parent::__construct( $info );
	}

	public function getInputHTML( $value ) {
		return Xml::submitButton(
			$value,
			array(
				'class' => 'mw-htmlform-submit ' . $this->mClass,
				'name' => $this->mName,
				'id' => $this->mID,
			)
		);
	}

	protected function needsLabel() {
		return false;
	}

	/**
	 * Button cannot be invalid
	 * @param $value String
	 * @param $alldata Array
	 * @return Bool
	 */
	public function validate( $value, $alldata ) {
		return true;
	}
}

class HTMLEditTools extends HTMLFormField {
	public function getInputHTML( $value ) {
		return '';
	}

	public function getTableRow( $value ) {
		$msg = $this->formatMsg();

		return '<tr><td></td><td class="mw-input">'
			. '<div class="mw-editTools">'
			. $msg->parseAsBlock()
			. "</div></td></tr>\n";
	}

	/**
	 * @since 1.20
	 */
	public function getDiv( $value ) {
		$msg = $this->formatMsg();
		return '<div class="mw-editTools">' . $msg->parseAsBlock() . '</div>';
	}

	/**
	 * @since 1.20
	 */
	public function getRaw( $value ) {
		return $this->getDiv( $value );
	}

	protected function formatMsg() {
		if ( empty( $this->mParams['message'] ) ) {
			$msg = $this->msg( 'edittools' );
		} else {
			$msg = $this->msg( $this->mParams['message'] );
			if ( $msg->isDisabled() ) {
				$msg = $this->msg( 'edittools' );
			}
		}
		$msg->inContentLanguage();
		return $msg;
	}
}

class HTMLApiField extends HTMLFormField {
	public function getTableRow( $value ) {
		return '';
	}

	public function getDiv( $value ) {
		return $this->getTableRow( $value );
	}

	public function getRaw( $value ) {
		return $this->getTableRow( $value );
	}

	public function getInputHTML( $value ) {
		return '';
	}
}
