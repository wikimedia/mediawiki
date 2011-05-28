<?php
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
 *	'help-message'        -- message key for a message to use as a help text.
 *	                         can be an array of msg key and then parameters to
 *	                         the message.
 *                           Overwrites 'help-messages'.
 *  'help-messages'       -- array of message key. As above, each item can
 *                           be an array of msg key and then parameters.
 *                           Overwrites 'help-message'.
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
 * TODO: Document 'section' / 'subsection' stuff
 */
class HTMLForm {

	# A mapping of 'type' inputs onto standard HTMLFormField subclasses
	static $typeMappings = array(
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

		# HTMLTextField will output the correct type="" attribute automagically.
		# There are about four zillion other HTML5 input types, like url, but
		# we don't use those at the moment, so no point in adding all of them.
		'email' => 'HTMLTextField',
		'password' => 'HTMLTextField',
	);

	protected $mMessagePrefix;
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

	protected $mContext; // <! RequestContext
	protected $mTitle;
	protected $mMethod = 'post';

	protected $mUseMultipart = false;
	protected $mHiddenFields = array();
	protected $mButtons = array();

	protected $mWrapperLegend = false;

	/**
	 * Build a new HTMLForm from an array of field attributes
	 * @param $descriptor Array of Field constructs, as described above
	 * @param $context RequestContext available since 1.18, will become compulsory in 1.19.
	 *     Obviates the need to call $form->setTitle()
	 * @param $messagePrefix String a prefix to go in front of default messages
	 */
	public function __construct( $descriptor, /*RequestContext*/ $context = null, $messagePrefix = '' ) {
		if( $context instanceof RequestContext ){
			$this->mContext = $context;
			$this->mTitle = false; // We don't need them to set a title
			$this->mMessagePrefix = $messagePrefix;
		} else {
			// B/C since 1.18
			if( is_string( $context ) && $messagePrefix === '' ){
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
	 * Add the HTMLForm-specific JavaScript, if it hasn't been
	 * done already.
	 * @deprecated since 1.18 load modules with ResourceLoader instead
	 */
	static function addJS() { }

	/**
	 * Initialise a new Object for the field
	 * @param $descriptor input Descriptor, as described above
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

		$obj = new $class( $descriptor );

		return $obj;
	}

	/**
	 * Prepare form for submission
	 */
	function prepareForm() {
		# Check if we have the info we need
		if ( !$this->mTitle instanceof Title && $this->mTitle !== false ) {
			throw new MWException( "You must call setTitle() on an HTMLForm" );
		}

		# Load data from the request.
		$this->loadData();
	}

	/**
	 * Try submitting, with edit token check first
	 * @return Status|boolean
	 */
	function tryAuthorizedSubmit() {
		$editToken = $this->getRequest()->getVal( 'wpEditToken' );

		$result = false;
		if ( $this->getMethod() != 'post' || $this->getUser()->matchEditToken( $editToken ) ) {
			$result = $this->trySubmit();
		}
		return $result;
	}

	/**
	 * The here's-one-I-made-earlier option: do the submission if
	 * posted, or display the form with or without funky valiation
	 * errors
	 * @return Bool or Status whether submission was successful.
	 */
	function show() {
		$this->prepareForm();

		$result = $this->tryAuthorizedSubmit();
		if ( $result === true || ( $result instanceof Status && $result->isGood() ) ){
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

		$data = $this->filterDataForSubmit( $this->mFieldData );

		$res = call_user_func( $callback, $data );

		return $res;
	}

	/**
	 * Set a callback to a function to do something with the form
	 * once it's been successfully validated.
	 * @param $cb String function name.  The function will be passed
	 *	 the output from HTMLForm::filterDataForSubmit, and must
	 *	 return Bool true on success, Bool false if no submission
	 *	 was attempted, or String HTML output to display on error.
	 */
	function setSubmitCallback( $cb ) {
		$this->mSubmitCallback = $cb;
	}

	/**
	 * Set a message to display on a validation error.
	 * @param $msg Mixed String or Array of valid inputs to wfMsgExt()
	 *	 (so each entry can be either a String or Array)
	 */
	function setValidationErrorMessage( $msg ) {
		$this->mValidationErrorMessage = $msg;
	}

	/**
	 * Set the introductory message, overwriting any existing message.
	 * @param $msg String complete text of message to display
	 */
	function setIntro( $msg ) { $this->mPre = $msg; }

	/**
	 * Add introductory text.
	 * @param $msg String complete text of message to display
	 */
	function addPreText( $msg ) { $this->mPre .= $msg; }

	/**
	 * Add header text, inside the form.
	 * @param $msg String complete text of message to display
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
	}

	/**
	 * Add footer text, inside the form.
	 * @param $msg String complete text of message to display
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
	}

	/**
	 * Add text to the end of the display.
	 * @param $msg String complete text of message to display
	 */
	function addPostText( $msg ) { $this->mPost .= $msg; }

	/**
	 * Add a hidden field to the output
	 * @param $name String field name.  This will be used exactly as entered
	 * @param $value String field value
	 * @param $attribs Array
	 */
	public function addHiddenField( $name, $value, $attribs = array() ) {
		$attribs += array( 'name' => $name );
		$this->mHiddenFields[] = array( $value, $attribs );
	}

	public function addButton( $name, $value, $id = null, $attribs = null ) {
		$this->mButtons[] = compact( 'name', 'value', 'id', 'attribs' );
	}

	/**
	 * Display the form (sending to wgOut), with an appropriate error
	 * message or stack of messages, and any validation errors, etc.
	 * @param $submitResult Mixed output from HTMLForm::trySubmit()
	 */
	function displayForm( $submitResult ) {
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

		$this->getOutput()->addHTML( ''
			. $this->mPre
			. $html
			. $this->mPost
		);
	}

	/**
	 * Wrap the form innards in an actual <form> element
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
			'action'  => $this->getTitle()->getFullURL(),
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
		$html = '';
		if( $this->getMethod() == 'post' ){
			$html .= Html::hidden( 'wpEditToken', $this->getUser()->editToken(), array( 'id' => 'wpEditToken' ) ) . "\n";
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
			$attribs += Linker::tooltipAndAccessKeyAttribs( $this->mSubmitTooltip );
		}

		$attribs['class'] = 'mw-htmlform-submit';

		$html .= Xml::submitButton( $this->getSubmitText(), $attribs ) . "\n";

		if ( $this->mShowReset ) {
			$html .= Html::element(
				'input',
				array(
					'type' => 'reset',
					'value' => wfMsg( 'htmlform-reset' )
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
	 * @return String HTML, a <ul> list of errors
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
				wfMsgExt( $msg, array( 'parseinline' ), $error )
			);
		}

		$errorstr = Html::rawElement( 'ul', array(), $errorstr );

		return $errorstr;
	}

	/**
	 * Set the text for the submit button
	 * @param $t String plaintext.
	 */
	function setSubmitText( $t ) {
		$this->mSubmitText = $t;
	}

	/**
	 * Get the text for the submit button, either customised or a default.
	 * @return unknown_type
	 */
	function getSubmitText() {
		return $this->mSubmitText
			? $this->mSubmitText
			: wfMsg( 'htmlform-submit' );
	}

	public function setSubmitName( $name ) {
		$this->mSubmitName = $name;
	}

	public function setSubmitTooltip( $name ) {
		$this->mSubmitTooltip = $name;
	}

	/**
	 * Set the id for the submit button.
	 * @param $t String.
	 * @todo FIXME: Integrity of $t is *not* validated
	 */
	function setSubmitID( $t ) {
		$this->mSubmitID = $t;
	}

	public function setId( $id ) {
		$this->mId = $id;
	}
	/**
	 * Prompt the whole form to be wrapped in a <fieldset>, with
	 * this text as its <legend> element.
	 * @param $legend String HTML to go inside the <legend> element.
	 *	 Will be escaped
	 */
	public function setWrapperLegend( $legend ) { $this->mWrapperLegend = $legend; }

	/**
	 * Set the prefix for various default messages
	 * TODO: currently only used for the <fieldset> legend on forms
	 * with multiple sections; should be used elsewhre?
	 * @param $p String
	 */
	function setMessagePrefix( $p ) {
		$this->mMessagePrefix = $p;
	}

	/**
	 * Set the title for form submission
	 * @param $t Title of page the form is on/should be posted to
	 */
	function setTitle( $t ) {
		$this->mTitle = $t;
	}

	/**
	 * Get the title
	 * @return Title
	 */
	function getTitle() {
		return $this->mTitle === false
			? $this->getContext()->title
			: $this->mTitle;
	}

	/**
	 * @return RequestContext
	 */
	public function getContext(){
		return $this->mContext instanceof RequestContext
			? $this->mContext
			: RequestContext::getMain();
	}

	/**
	 * @return OutputPage
	 */
	public function getOutput(){
		return $this->getContext()->output;
	}

	/**
	 * @return WebRequest
	 */
	public function getRequest(){
		return $this->getContext()->request;
	}

	/**
	 * @return User
	 */
	public function getUser(){
		return $this->getContext()->user;
	}

	/**
	 * Set the method used to submit the form
	 * @param $method String
	 */
	public function setMethod( $method='post' ){
		$this->mMethod = $method;
	}

	public function getMethod(){
		return $this->mMethod;
	}

	/**
	 * TODO: Document
	 * @param $fields
	 */
	function displaySection( $fields, $sectionName = '' ) {
		$tableHtml = '';
		$subsectionHtml = '';
		$hasLeftColumn = false;

		foreach ( $fields as $key => $value ) {
			if ( is_object( $value ) ) {
				$v = empty( $value->mParams['nodata'] )
					? $this->mFieldData[$key]
					: $value->getDefault();
				$tableHtml .= $value->getTableRow( $v );

				if ( $value->getLabel() != '&#160;' )
					$hasLeftColumn = true;
			} elseif ( is_array( $value ) ) {
				$section = $this->displaySection( $value, $key );
				$legend = $this->getLegend( $key );
				if ( isset( $this->mSectionHeaders[$key] ) ) {
					$section = $this->mSectionHeaders[$key] . $section;
				}
				if ( isset( $this->mSectionFooters[$key] ) ) {
					$section .= $this->mSectionFooters[$key];
				}
				$subsectionHtml .= Xml::fieldset( $legend, $section ) . "\n";
			}
		}

		$classes = array();

		if ( !$hasLeftColumn ) { // Avoid strange spacing when no labels exist
			$classes[] = 'mw-htmlform-nolabel';
		}

		$attribs = array(
			'class' => implode( ' ', $classes ),
		);

		if ( $sectionName ) {
			$attribs['id'] = Sanitizer::escapeId( "mw-htmlform-$sectionName" );
		}

		$tableHtml = Html::rawElement( 'table', $attribs,
			Html::rawElement( 'tbody', array(), "\n$tableHtml\n" ) ) . "\n";

		return $subsectionHtml . "\n" . $tableHtml;
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
	 */
	function suppressReset( $suppressReset = true ) {
		$this->mShowReset = !$suppressReset;
	}

	/**
	 * Overload this if you want to apply special filtration routines
	 * to the form as a whole, after it's submitted but before it's
	 * processed.
	 * @param $data
	 * @return unknown_type
	 */
	function filterDataForSubmit( $data ) {
		return $data;
	}

	/**
	 * Get a string to go in the <legend> of a section fieldset.  Override this if you
	 * want something more complicated
	 * @param $key String
	 * @return String
	 */
	public function getLegend( $key ) {
		return wfMsg( "{$this->mMessagePrefix}-$key" );
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
	 * Override this function to add specific validation checks on the
	 * field input.  Don't forget to call parent::validate() to ensure
	 * that the user-defined callback mValidationCallback is still run
	 * @param $value String the value the field was submitted with
	 * @param $alldata Array the data collected from the form
	 * @return Mixed Bool true on success, or String error to display.
	 */
	function validate( $value, $alldata ) {
		if ( isset( $this->mValidationCallback ) ) {
			return call_user_func( $this->mValidationCallback, $value, $alldata );
		}

		if ( isset( $this->mParams['required'] ) && $value === '' ) {
			return wfMsgExt( 'htmlform-required', 'parseinline' );
		}

		return true;
	}

	function filter( $value, $alldata ) {
		if ( isset( $this->mFilterCallback ) ) {
			$value = call_user_func( $this->mFilterCallback, $value, $alldata );
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

			$this->mLabel = wfMsgExt( $msg, 'parseinline', $msgInfo );
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
	}

	/**
	 * Get the complete table row for the input, including help text,
	 * labels, and whatever.
	 * @param $value String the value to set the input to.
	 * @return String complete HTML table row.
	 */
	function getTableRow( $value ) {
		# Check for invalid data.

		$errors = $this->validate( $value, $this->mParent->mFieldData );

		$cellAttributes = array();
		$verticalLabel = false;

		if ( !empty($this->mParams['vertical-label']) ) {
			$cellAttributes['colspan'] = 2;
			$verticalLabel = true;
		}

		if ( $errors === true || ( !$this->mParent->getRequest()->wasPosted() && ( $this->mParent->getMethod() == 'post' ) ) ) {
			$errors = '';
			$errorClass = '';
		} else {
			$errors = self::formatErrors( $errors );
			$errorClass = 'mw-htmlform-invalid-input';
		}

		$label = $this->getLabelHtml( $cellAttributes );
		$field = Html::rawElement(
			'td',
			array( 'class' => 'mw-input' ) + $cellAttributes,
			$this->getInputHTML( $value ) . "\n$errors"
		);

		$fieldType = get_class( $this );

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

		$helptext = null;

		if ( isset( $this->mParams['help-message'] ) ) {
			$msg = wfMessage( $this->mParams['help-message'] );
			if ( $msg->exists() ) {
				$helptext = $msg->parse();
			}
		} elseif ( isset( $this->mParams['help-messages'] ) ) {
			# help-message can be passed a message key (string) or an array containing
			# a message key and additional parameters. This makes it impossible to pass
			# an array of message key
			foreach( $this->mParams['help-messages'] as $name ) {
				$msg = wfMessage( $name );
				if( $msg->exists() ) {
					$helptext .= $msg->parse(); // append message
				}
			}
		} elseif ( isset( $this->mParams['help'] ) ) {
			$helptext = $this->mParams['help'];
		}

		if ( !is_null( $helptext ) ) {
			$row = Html::rawElement( 'td', array( 'colspan' => 2, 'class' => 'htmlform-tip' ),
				$helptext );
			$row = Html::rawElement( 'tr', array(), $row );
			$html .= "$row\n";
		}

		return $html;
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

		return Html::rawElement( 'td', array( 'class' => 'mw-label' ) + $cellAttributes,
			Html::rawElement( 'label', $for, $this->getLabel() )
		);
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
		return Linker::tooltipAndAccessKeyAttribs( $this->mParams['tooltip'] );
	}

	/**
	 * flatten an array of options to a single array, for instance,
	 * a set of <options> inside <optgroups>.
	 * @param $options Associative Array with values either Strings
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

		if ( isset( $this->mParams['maxlength'] ) ) {
			$attribs['maxlength'] = $this->mParams['maxlength'];
		}

		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		# TODO: Enforce pattern, step, required, readonly on the server side as
		# well
		foreach ( array( 'min', 'max', 'pattern', 'title', 'step',
		'placeholder' ) as $param ) {
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


		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		if ( !empty( $this->mParams['readonly'] ) ) {
			$attribs['readonly'] = 'readonly';
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
			return wfMsgExt( 'htmlform-float-invalid', 'parse' );
		}

		# The "int" part of these message names is rather confusing.
		# They make equal sense for all numbers.
		if ( isset( $this->mParams['min'] ) ) {
			$min = $this->mParams['min'];

			if ( $min > $value ) {
				return wfMsgExt( 'htmlform-int-toolow', 'parse', array( $min ) );
			}
		}

		if ( isset( $this->mParams['max'] ) ) {
			$max = $this->mParams['max'];

			if ( $max < $value ) {
				return wfMsgExt( 'htmlform-int-toohigh', 'parse', array( $max ) );
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
			return wfMsgExt( 'htmlform-int-invalid', 'parse' );
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

		return Xml::check( $this->mName, $value, $attr ) . '&#160;' .
			Html::rawElement( 'label', array( 'for' => $this->mID ), $this->mLabel );
	}

	/**
	 * For a checkbox, the label goes on the right hand side, and is
	 * added in getInputHTML(), rather than HTMLFormField::getRow()
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
		if ( $request->getCheck( 'wpEditToken' ) || $this->mParent->getMethod() != 'post' ) {
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
			return wfMsgExt( 'htmlform-select-badoption', 'parseinline' );
	}

	function getInputHTML( $value ) {
		$select = new XmlSelect( $this->mName, $this->mID, strval( $value ) );

		# If one of the options' 'name' is int(0), it is automatically selected.
		# because PHP sucks and thinks int(0) == 'some string'.
		# Working around this by forcing all of them to strings.
		foreach( $this->mParams['options'] as &$opt ){
			if( is_int( $opt ) ){
				$opt = strval( $opt );
			}
		}
		unset( $opt ); # PHP keeps $opt around as a reference, which is a bit scary

		if ( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
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
			$params['options'][wfMsg( 'htmlform-selectorother-other' )] = 'other';
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

	public function __construct( $params ){
		parent::__construct( $params );
		if( isset( $params['flatlist'] ) ){
			$this->mClass .= ' mw-htmlform-multiselect-flatlist';
		}
	}

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
			return wfMsgExt( 'htmlform-select-badoption', 'parseinline' );
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

				$html .= ' ' . Html::rawElement( 'div', array( 'class' => 'mw-htmlform-multiselect-item' ), $checkbox );
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
			if( $request->wasPosted() ){
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
 *     ** <option value>|<option name>
 *     ** <option value == option name>
 *     * New Optgroup header
 * Plus a text field underneath for an additional reason.  The 'value' of the field is
 * ""<select>: <extra reason>"", or "<extra reason>" if nothing has been selected in the
 * select dropdown.
 * @todo FIXME: If made 'required', only the text field should be compulsory.
 */
class HTMLSelectAndOtherField extends HTMLSelectField {

	function __construct( $params ) {
		if ( array_key_exists( 'other', $params ) ) {
		} elseif( array_key_exists( 'other-message', $params ) ){
			$params['other'] = wfMsg( $params['other-message'] );
		} else {
			$params['other'] = wfMsg( 'htmlform-selectorother-other' );
		}

		if ( array_key_exists( 'options', $params ) ) {
			# Options array already specified
		} elseif( array_key_exists( 'options-message', $params ) ){
			# Generate options array from a system message
			$params['options'] = self::parseMessage( wfMsg( $params['options-message'], $params['other'] ) );
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
	public static function parseMessage( $string, $otherName=null ) {
		if( $otherName === null ){
			$otherName = wfMsg( 'htmlform-selectorother-other' );
		}

		$optgroup = false;
		$options = array( $otherName => 'other' );

		foreach ( explode( "\n", $string ) as $option ) {
			$value = trim( $option );
			if ( $value == '' ) {
				continue;
			} elseif ( substr( $value, 0, 1) == '*' && substr( $value, 1, 1) != '*' ) {
				# A new group is starting...
				$value = trim( substr( $value, 1 ) );
				$optgroup = $value;
			} elseif ( substr( $value, 0, 2) == '**' ) {
				# groupmember
				$opt = trim( substr( $value, 2 ) );
				$parts = array_map( 'trim', explode( '|', $opt, 2 ) );
				if( count( $parts ) === 1 ){
					$parts[1] = $parts[0];
				}
				if( $optgroup === false ){
					$options[$parts[1]] = $parts[0];
				} else {
					$options[$optgroup][$parts[1]] = $parts[0];
				}
			} else {
				# groupless reason list
				$optgroup = false;
				$parts = array_map( 'trim', explode( '|', $option, 2 ) );
				if( count( $parts ) === 1 ){
					$parts[1] = $parts[0];
				}
				$options[$parts[1]] = $parts[0];
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
	 * @return Array( <overall message>, <select value>, <text field value> )
	 */
	function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {

			$list = $request->getText( $this->mName );
			$text = $request->getText( $this->mName . '-other' );

			if ( $list == 'other' ) {
				$final = $text;
			} elseif( !in_array( $list, $this->mFlatOptions ) ){
				# User has spoofed the select form to give an option which wasn't
				# in the original offer.  Sulk...
				$final = $text;
			} elseif( $text == '' ) {
				$final = $list;
			} else {
				$final = $list . wfMsgForContent( 'colon-separator' ) . $text;
			}

		} else {
			$final = $this->getDefault();
			$list = $text = '';
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

		if( isset( $this->mParams['required'] ) && $value[1] === '' ){
			return wfMsgExt( 'htmlform-required', 'parseinline' );
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
			return wfMsgExt( 'htmlform-select-badoption', 'parseinline' );
		}
	}

	/**
	 * This returns a block of all the radio options, in one cell.
	 * @see includes/HTMLFormField#getInputHTML()
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
				$html .= Xml::radio(
					$this->mName,
					$info,
					$info == $value,
					$attribs + array( 'id' => $id )
				);
				$html .= '&#160;' .
						Html::rawElement( 'label', array( 'for' => $id ), $label );

				$html .= "<br />\n";
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
	function __construct( $info ) {
		$info['nodata'] = true;

		parent::__construct( $info );
	}

	function getInputHTML( $value ) {
		return !empty( $this->mParams['raw'] ) ? $value : htmlspecialchars( $value );
	}

	function getTableRow( $value ) {
		if ( !empty( $this->mParams['rawrow'] ) ) {
			return $value;
		}

		return parent::getTableRow( $value );
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

	public function getInputHTML( $value ) { return ''; }
}

/**
 * Add a submit button inline in the form (as opposed to
 * HTMLForm::addButton(), which will add it at the end).
 */
class HTMLSubmitField extends HTMLFormField {

	function __construct( $info ) {
		$info['nodata'] = true;
		parent::__construct( $info );
	}

	function getInputHTML( $value ) {
		return Xml::submitButton(
			$value,
			array(
				'class' => 'mw-htmlform-submit',
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
	 */
	public function validate( $value, $alldata ){
		return true;
	}
}

class HTMLEditTools extends HTMLFormField {
	public function getInputHTML( $value ) {
		return '';
	}

	public function getTableRow( $value ) {
		if ( empty( $this->mParams['message'] ) ) {
			$msg = wfMessage( 'edittools' );
		} else {
			$msg = wfMessage( $this->mParams['message'] );
			if ( $msg->isDisabled() ) {
				$msg = wfMessage( 'edittools' );
			}
		}
		$msg->inContentLanguage();


		return '<tr><td></td><td class="mw-input">'
			. '<div class="mw-editTools">'
			. $msg->parseAsBlock()
			. "</div></td></tr>\n";
	}
}
