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

namespace MediaWiki\HTMLForm;

use DomainException;
use InvalidArgumentException;
use LogicException;
use MediaWiki\Context\ContextSource;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLApiField;
use MediaWiki\HTMLForm\Field\HTMLAutoCompleteSelectField;
use MediaWiki\HTMLForm\Field\HTMLCheckField;
use MediaWiki\HTMLForm\Field\HTMLCheckMatrix;
use MediaWiki\HTMLForm\Field\HTMLComboboxField;
use MediaWiki\HTMLForm\Field\HTMLDateTimeField;
use MediaWiki\HTMLForm\Field\HTMLEditTools;
use MediaWiki\HTMLForm\Field\HTMLExpiryField;
use MediaWiki\HTMLForm\Field\HTMLFileField;
use MediaWiki\HTMLForm\Field\HTMLFloatField;
use MediaWiki\HTMLForm\Field\HTMLFormFieldCloner;
use MediaWiki\HTMLForm\Field\HTMLHiddenField;
use MediaWiki\HTMLForm\Field\HTMLInfoField;
use MediaWiki\HTMLForm\Field\HTMLIntField;
use MediaWiki\HTMLForm\Field\HTMLMultiSelectField;
use MediaWiki\HTMLForm\Field\HTMLNamespacesMultiselectField;
use MediaWiki\HTMLForm\Field\HTMLOrderedMultiselectField;
use MediaWiki\HTMLForm\Field\HTMLRadioField;
use MediaWiki\HTMLForm\Field\HTMLSelectAndOtherField;
use MediaWiki\HTMLForm\Field\HTMLSelectField;
use MediaWiki\HTMLForm\Field\HTMLSelectLanguageField;
use MediaWiki\HTMLForm\Field\HTMLSelectLimitField;
use MediaWiki\HTMLForm\Field\HTMLSelectNamespace;
use MediaWiki\HTMLForm\Field\HTMLSelectNamespaceWithButton;
use MediaWiki\HTMLForm\Field\HTMLSelectOrOtherField;
use MediaWiki\HTMLForm\Field\HTMLSizeFilterField;
use MediaWiki\HTMLForm\Field\HTMLSubmitField;
use MediaWiki\HTMLForm\Field\HTMLTagFilter;
use MediaWiki\HTMLForm\Field\HTMLTagMultiselectField;
use MediaWiki\HTMLForm\Field\HTMLTextAreaField;
use MediaWiki\HTMLForm\Field\HTMLTextField;
use MediaWiki\HTMLForm\Field\HTMLTextFieldWithButton;
use MediaWiki\HTMLForm\Field\HTMLTimezoneField;
use MediaWiki\HTMLForm\Field\HTMLTitlesMultiselectField;
use MediaWiki\HTMLForm\Field\HTMLTitleTextField;
use MediaWiki\HTMLForm\Field\HTMLUsersMultiselectField;
use MediaWiki\HTMLForm\Field\HTMLUserTextField;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Session\CsrfTokenSet;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\Xml\Xml;
use StatusValue;
use Stringable;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

/**
 * Object handling generic submission, CSRF protection, layout and
 * other logic for UI forms in a reusable manner.
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
 *    'nodata'              -- if set (to any value, which casts to true), the data
 *                             for this field will not be loaded from the actual request. Instead,
 *                             always the default data is set as the value of this field.
 *    'id'                  -- HTML id attribute
 *    'cssclass'            -- CSS class
 *    'csshelpclass'        -- CSS class used to style help text
 *    'dir'                 -- Direction of the element.
 *    'options'             -- associative array mapping raw HTML labels to values.
 *                             Some field types support multi-level arrays.
 *                             Overwrites 'options-message'.
 *    'options-messages'    -- associative array mapping message keys to values.
 *                             Some field types support multi-level arrays.
 *                             Overwrites 'options' and 'options-message'.
 *    'options-messages-parse' -- Flag to parse the messages in 'options-messages'.
 *    'options-message'     -- message key or object to be parsed to extract the list of
 *                             options (like 'ipbreason-dropdown').
 *    'label-message'       -- message key or object for a message to use as the label.
 *                             can be an array of msg key and then parameters to
 *                             the message.
 *    'label'               -- alternatively, a raw text message. Overridden by
 *                             label-message
 *    'help-raw'            -- message text for a message to use as a help text.
 *    'help-message'        -- message key or object for a message to use as a help text.
 *                             can be an array of msg key and then parameters to
 *                             the message.
 *                             Overwrites 'help-messages' and 'help-raw'.
 *    'help-messages'       -- array of message keys/objects. As above, each item can
 *                             be an array of msg key and then parameters.
 *                             Overwrites 'help-raw'.
 *    'help-inline'         -- Whether help text (defined using options above) will be shown
 *                             inline after the input field, rather than in a popup.
 *                             Defaults to true. Only used by OOUI form fields.
 *    'notices'             -- Array of plain text notices to display below the input field.
 *                             Only used by OOUI form fields.
 *    'required'            -- passed through to the object, indicating that it
 *                             is a required field.
 *    'size'                -- the length of text fields
 *    'filter-callback'     -- a function name to give you the chance to
 *                             massage the inputted value before it's processed.
 *                             @see HTMLFormField::filter()
 *    'validation-callback' -- a function name to give you the chance
 *                             to impose extra validation on the field input. The signature should be
 *                             as documented in {@see HTMLFormField::$mValidationCallback}.
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
 *    'disable-if'          -- expression given as an array stating when the field
 *                             should be disabled. See 'hide-if' for supported expressions.
 *                             The 'hide-if' logic would also disable fields, you don't need
 *                             to set this attribute with the same condition manually.
 *                             You can pass both 'disabled' and this attribute to omit extra
 *                             check, but this would function only for not 'disabled' fields.
 *    'section'             -- A string name for the section of the form to which the field
 *                             belongs. Subsections may be added using the separator '/', e.g.:
 *                               'section' => 'section1/subsection1'
 *                             More levels may be added, e.g.:
 *                               'section' => 'section1/subsection2/subsubsection1'
 *                             The message key for a section or subsection header is built from
 *                             its name and the form's message prefix (if present).
 *
 * Since 1.20, you can chain mutators to ease the form generation:
 * @par Example:
 * @code
 * $form = new HTMLForm( $someFields, $this->getContext() );
 * $form->setMethod( 'get' )
 *      ->setWrapperLegendMsg( 'message-key' )
 *      ->prepareForm()
 *      ->displayForm( '' );
 * @endcode
 * Note that you will have prepareForm and displayForm at the end. Other
 * method calls done after that would simply not be part of the form :(
 *
 * @stable to extend
 */
class HTMLForm extends ContextSource {
	use ProtectedHookAccessorTrait;

	/** @var string[] A mapping of 'type' inputs onto standard HTMLFormField subclasses */
	public static $typeMappings = [
		'api' => HTMLApiField::class,
		'text' => HTMLTextField::class,
		'textwithbutton' => HTMLTextFieldWithButton::class,
		'textarea' => HTMLTextAreaField::class,
		'select' => HTMLSelectField::class,
		'combobox' => HTMLComboboxField::class,
		'radio' => HTMLRadioField::class,
		'multiselect' => HTMLMultiSelectField::class,
		'limitselect' => HTMLSelectLimitField::class,
		'check' => HTMLCheckField::class,
		'toggle' => HTMLCheckField::class,
		'int' => HTMLIntField::class,
		'file' => HTMLFileField::class,
		'float' => HTMLFloatField::class,
		'info' => HTMLInfoField::class,
		'selectorother' => HTMLSelectOrOtherField::class,
		'selectandother' => HTMLSelectAndOtherField::class,
		'namespaceselect' => HTMLSelectNamespace::class,
		'namespaceselectwithbutton' => HTMLSelectNamespaceWithButton::class,
		'tagfilter' => HTMLTagFilter::class,
		'sizefilter' => HTMLSizeFilterField::class,
		'submit' => HTMLSubmitField::class,
		'hidden' => HTMLHiddenField::class,
		'edittools' => HTMLEditTools::class,
		'checkmatrix' => HTMLCheckMatrix::class,
		'cloner' => HTMLFormFieldCloner::class,
		'autocompleteselect' => HTMLAutoCompleteSelectField::class,
		'language' => HTMLSelectLanguageField::class,
		'date' => HTMLDateTimeField::class,
		'time' => HTMLDateTimeField::class,
		'datetime' => HTMLDateTimeField::class,
		'expiry' => HTMLExpiryField::class,
		'timezone' => HTMLTimezoneField::class,
		// HTMLTextField will output the correct type="" attribute automagically.
		// There are about four zillion other HTML5 input types, like range, but
		// we don't use those at the moment, so no point in adding all of them.
		'email' => HTMLTextField::class,
		'password' => HTMLTextField::class,
		'url' => HTMLTextField::class,
		'title' => HTMLTitleTextField::class,
		'user' => HTMLUserTextField::class,
		'tagmultiselect' => HTMLTagMultiselectField::class,
		'orderedmultiselect' => HTMLOrderedMultiselectField::class,
		'usersmultiselect' => HTMLUsersMultiselectField::class,
		'titlesmultiselect' => HTMLTitlesMultiselectField::class,
		'namespacesmultiselect' => HTMLNamespacesMultiselectField::class,
	];

	/** @var array */
	public $mFieldData;

	/** @var string */
	protected $mMessagePrefix;

	/** @var HTMLFormField[] */
	protected $mFlatFields = [];
	/** @var array */
	protected $mFieldTree = [];
	/** @var bool */
	protected $mShowSubmit = true;
	/** @var string[] */
	protected $mSubmitFlags = [ 'primary', 'progressive' ];
	/** @var bool */
	protected $mShowCancel = false;
	/** @var LinkTarget|string|null */
	protected $mCancelTarget;

	/** @var callable|null */
	protected $mSubmitCallback;
	/**
	 * @var array[]
	 * @phan-var non-empty-array[]
	 */
	protected $mValidationErrorMessage;

	/** @var string */
	protected $mPre = '';
	/** @var string */
	protected $mHeader = '';
	/** @var string */
	protected $mFooter = '';
	/** @var string[] */
	protected $mSectionHeaders = [];
	/** @var string[] */
	protected $mSectionFooters = [];
	/** @var string */
	protected $mPost = '';
	/** @var string|null */
	protected $mId;
	/** @var string|null */
	protected $mName;
	/** @var string */
	protected $mTableId = '';

	/** @var string|null */
	protected $mSubmitID;
	/** @var string|null */
	protected $mSubmitName;
	/** @var string|null */
	protected $mSubmitText;
	/** @var string|null */
	protected $mSubmitTooltip;

	/** @var string|null */
	protected $mFormIdentifier;
	/** @var bool */
	protected $mSingleForm = false;

	/** @var Title|null */
	protected $mTitle;
	/** @var string */
	protected $mMethod = 'post';
	/** @var bool */
	protected $mWasSubmitted = false;

	/**
	 * Form action URL. false means we will use the URL to set Title
	 * @since 1.19
	 * @var string|false
	 */
	protected $mAction = false;

	/**
	 * Whether the form can be collapsed
	 * @since 1.34
	 * @var bool
	 */
	protected $mCollapsible = false;

	/**
	 * Whether the form is collapsed by default
	 * @since 1.34
	 * @var bool
	 */
	protected $mCollapsed = false;

	/**
	 * Form attribute autocomplete. A typical value is "off". null does not set the attribute
	 * @since 1.27
	 * @var string|null
	 */
	protected $mAutocomplete = null;

	/** @var bool */
	protected $mUseMultipart = false;
	/**
	 * @var array[]
	 * @phan-var array<int,array{0:string,1:array}>
	 */
	protected $mHiddenFields = [];
	/**
	 * @var array[]
	 * @phan-var array<array{name:string,value:string,label-message?:string|array<string|MessageParam>|MessageSpecifier,label?:string,label-raw?:string,id?:string,attribs?:array,flags?:string|string[],framed?:bool}>
	 */
	protected $mButtons = [];

	/** @var string|false */
	protected $mWrapperLegend = false;
	/** @var array */
	protected $mWrapperAttributes = [];

	/**
	 * Salt for the edit token.
	 * @var string|array
	 */
	protected $mTokenSalt = '';

	/**
	 * Additional information about form sections. Only supported by CodexHTMLForm.
	 *
	 * Array is keyed on section name. Options per section include:
	 * 'description'               -- Description text placed below the section label.
	 * 'description-message'       -- The same, but a message key.
	 * 'description-message-parse' -- Whether to parse the 'description-message'
	 * 'optional'                  -- Whether the section should be marked as optional.
	 *
	 * @since 1.42
	 * @var array[]
	 */
	protected $mSections = [];

	/**
	 * If true, sections that contain both fields and subsections will
	 * render their subsections before their fields.
	 *
	 * Subclasses may set this to false to render subsections after fields
	 * instead.
	 * @var bool
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
		'codex',
		'ooui',
	];

	/**
	 * Whether a hidden title field has been added to the form
	 * @var bool
	 */
	private $hiddenTitleAddedToForm = false;

	/**
	 * Construct a HTMLForm object for given display type. May return a HTMLForm subclass.
	 *
	 * @stable to call
	 *
	 * @param string $displayFormat
	 * @param array $descriptor Array of Field constructs, as described
	 *     in the class documentation
	 * @param IContextSource $context Context used to fetch submitted form fields and
	 *     generate localisation messages
	 * @param string $messagePrefix A prefix to go in front of default messages
	 * @return HTMLForm
	 */
	public static function factory(
		$displayFormat, $descriptor, IContextSource $context, $messagePrefix = ''
	) {
		switch ( $displayFormat ) {
			case 'codex':
				return new CodexHTMLForm( $descriptor, $context, $messagePrefix );
			case 'vform':
				return new VFormHTMLForm( $descriptor, $context, $messagePrefix );
			case 'ooui':
				return new OOUIHTMLForm( $descriptor, $context, $messagePrefix );
			default:
				$form = new self( $descriptor, $context, $messagePrefix );
				$form->setDisplayFormat( $displayFormat );
				return $form;
		}
	}

	/**
	 * Build a new HTMLForm from an array of field attributes
	 *
	 * @stable to call
	 *
	 * @param array $descriptor Array of Field constructs, as described
	 *     in the class documentation
	 * @param IContextSource $context Context used to fetch submitted form fields and
	 *     generate localisation messages
	 * @param string $messagePrefix A prefix to go in front of default messages
	 */
	public function __construct(
		$descriptor, IContextSource $context, $messagePrefix = ''
	) {
		$this->setContext( $context );
		$this->mMessagePrefix = $messagePrefix;
		$this->addFields( $descriptor );
	}

	/**
	 * Add fields to the form
	 *
	 * @since 1.34
	 *
	 * @param array $descriptor Array of Field constructs, as described
	 * 	in the class documentation
	 * @return HTMLForm
	 */
	public function addFields( $descriptor ) {
		$loadedDescriptor = [];

		foreach ( $descriptor as $fieldname => $info ) {
			$section = $info['section'] ?? '';

			if ( isset( $info['type'] ) && $info['type'] === 'file' ) {
				$this->mUseMultipart = true;
			}

			$field = static::loadInputFromParameters( $fieldname, $info, $this );

			$setSection =& $loadedDescriptor;
			if ( $section ) {
				foreach ( explode( '/', $section ) as $newName ) {
					$setSection[$newName] ??= [];
					$setSection =& $setSection[$newName];
				}
			}

			$setSection[$fieldname] = $field;
			$this->mFlatFields[$fieldname] = $field;
		}

		$this->mFieldTree = array_merge_recursive( $this->mFieldTree, $loadedDescriptor );

		return $this;
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
	 * @since 1.20
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setDisplayFormat( $format ) {
		if (
			in_array( $format, $this->availableSubclassDisplayFormats, true ) ||
			in_array( $this->displayFormat, $this->availableSubclassDisplayFormats, true )
		) {
			throw new LogicException( 'Cannot change display format after creation, ' .
				'use HTMLForm::factory() instead' );
		}

		if ( !in_array( $format, $this->availableDisplayFormats, true ) ) {
			throw new InvalidArgumentException( 'Display format must be one of ' .
				print_r(
					array_merge(
						$this->availableDisplayFormats,
						$this->availableSubclassDisplayFormats
					),
					true
				) );
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
	 * @param array &$descriptor Input Descriptor, as described
	 * 	in the class documentation
	 *
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
			throw new InvalidArgumentException( "Descriptor with no class for $fieldname: "
				. print_r( $descriptor, true ) );
		}

		return $class;
	}

	/**
	 * Initialise a new Object for the field
	 * @stable to override
	 *
	 * @param string $fieldname Name of the field
	 * @param array $descriptor Input Descriptor, as described
	 * 	in the class documentation
	 * @param HTMLForm|null $parent Parent instance of HTMLForm
	 *
	 * @warning Not passing (or passing null) for $parent is deprecated as of 1.40
	 * @return HTMLFormField Instance of a subclass of HTMLFormField
	 */
	public static function loadInputFromParameters( $fieldname, $descriptor,
		?HTMLForm $parent = null
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
	 * @warning When doing method chaining, that should be the very last
	 * method call before displayForm().
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function prepareForm() {
		# Load data from the request.
		if (
			$this->mFormIdentifier === null ||
			$this->getRequest()->getVal( 'wpFormIdentifier' ) === $this->mFormIdentifier ||
			( $this->mSingleForm && $this->getMethod() === 'get' )
		) {
			$this->loadFieldData();
		} else {
			$this->mFieldData = [];
		}

		return $this;
	}

	/**
	 * Try submitting, with edit token check first
	 * @return bool|string|array|Status As documented for HTMLForm::trySubmit
	 */
	public function tryAuthorizedSubmit() {
		$result = false;

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
			if ( $this->getUser()->isRegistered() || $editToken !== null ) {
				// Session tokens for logged-out users have no security value.
				// However, if the user gave one, check it in order to give a nice
				// "session expired" error instead of "permission denied" or such.
				$tokenOkay = $this->getCsrfTokenSet()->matchTokenField(
					CsrfTokenSet::DEFAULT_FIELD_NAME, $this->mTokenSalt
				);
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
	 * @stable to override
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
	 * @stable to override
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
			foreach ( $this->mValidationErrorMessage as $error ) {
				$hoistedErrors->fatal( ...$error );
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
		$hasNonDefault = false;
		foreach ( $this->mFlatFields as $fieldname => $field ) {
			if ( !array_key_exists( $fieldname, $this->mFieldData ) ) {
				continue;
			}
			$hasNonDefault = $hasNonDefault || $this->mFieldData[$fieldname] !== $field->getDefault();
			if ( $field->isDisabled( $this->mFieldData ) ) {
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
			// Treat as not submitted if got nothing from the user on GET forms.
			if ( !$hasNonDefault && $this->getMethod() === 'get' &&
				( $this->mFormIdentifier === null ||
					$this->getRequest()->getCheck( 'wpFormIdentifier' ) )
			) {
				$this->mWasSubmitted = false;
				return false;
			}
			return $hoistedErrors;
		}

		$callback = $this->mSubmitCallback;
		if ( !is_callable( $callback ) ) {
			throw new LogicException( 'HTMLForm: no submit callback provided. Use ' .
				'setSubmitCallback() to set one.' );
		}

		$data = $this->filterDataForSubmit( $this->mFieldData );

		$res = $callback( $data, $this );
		if ( $res === false ) {
			$this->mWasSubmitted = false;
		} elseif ( $res instanceof StatusValue ) {
			// DWIM - callbacks are not supposed to return a StatusValue but it's easy to mix up.
			$res = Status::wrap( $res );
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
	 * @param array[] $msg Array of valid inputs to wfMessage()
	 *     (so each entry must itself be an array of arguments)
	 * @phan-param non-empty-array[] $msg
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setValidationErrorMessage( $msg ) {
		$this->mValidationErrorMessage = $msg;

		return $this;
	}

	/**
	 * Set the introductory message HTML, overwriting any existing message.
	 *
	 * @param string $html Complete HTML of message to display
	 *
	 * @since 1.38
	 * @return $this for chaining calls
	 */
	public function setPreHtml( $html ) {
		$this->mPre = $html;

		return $this;
	}

	/**
	 * Add HTML to introductory message.
	 *
	 * @param string $html Complete HTML of message to display
	 *
	 * @since 1.38
	 * @return $this for chaining calls
	 */
	public function addPreHtml( $html ) {
		$this->mPre .= $html;

		return $this;
	}

	/**
	 * Get the introductory message HTML.
	 *
	 * @since 1.38
	 * @return string
	 */
	public function getPreHtml() {
		return $this->mPre;
	}

	/**
	 * Add HTML to the header, inside the form.
	 *
	 * @param string $html Additional HTML to display in header
	 * @param string|null $section The section to add the header to
	 *
	 * @since 1.38
	 * @return $this for chaining calls
	 */
	public function addHeaderHtml( $html, $section = null ) {
		if ( $section === null ) {
			$this->mHeader .= $html;
		} else {
			$this->mSectionHeaders[$section] ??= '';
			$this->mSectionHeaders[$section] .= $html;
		}

		return $this;
	}

	/**
	 * Set header HTML, inside the form.
	 *
	 * @param string $html Complete HTML of header to display
	 * @param string|null $section The section to add the header to
	 *
	 * @since 1.38
	 * @return $this for chaining calls
	 */
	public function setHeaderHtml( $html, $section = null ) {
		if ( $section === null ) {
			$this->mHeader = $html;
		} else {
			$this->mSectionHeaders[$section] = $html;
		}

		return $this;
	}

	/**
	 * Get header HTML.
	 * @stable to override
	 *
	 * @param string|null $section The section to get the header text for
	 * @since 1.38
	 * @return string HTML
	 */
	public function getHeaderHtml( $section = null ) {
		return $section ? $this->mSectionHeaders[$section] ?? '' : $this->mHeader;
	}

	/**
	 * Add footer HTML, inside the form.
	 *
	 * @param string $html Complete text of message to display
	 * @param string|null $section The section to add the footer text to
	 *
	 * @since 1.38
	 * @return $this for chaining calls
	 */
	public function addFooterHtml( $html, $section = null ) {
		if ( $section === null ) {
			$this->mFooter .= $html;
		} else {
			$this->mSectionFooters[$section] ??= '';
			$this->mSectionFooters[$section] .= $html;
		}

		return $this;
	}

	/**
	 * Set footer HTML, inside the form.
	 *
	 * @param string $html Complete text of message to display
	 * @param string|null $section The section to add the footer text to
	 *
	 * @since 1.38
	 * @return $this for chaining calls
	 */
	public function setFooterHtml( $html, $section = null ) {
		if ( $section === null ) {
			$this->mFooter = $html;
		} else {
			$this->mSectionFooters[$section] = $html;
		}

		return $this;
	}

	/**
	 * Get footer HTML.
	 *
	 * @param string|null $section The section to get the footer text for
	 * @since 1.38
	 * @return string
	 */
	public function getFooterHtml( $section = null ) {
		return $section ? $this->mSectionFooters[$section] ?? '' : $this->mFooter;
	}

	/**
	 * Add HTML to the end of the display.
	 *
	 * @param string $html Complete text of message to display
	 *
	 * @since 1.38
	 * @return $this for chaining calls
	 */
	public function addPostHtml( $html ) {
		$this->mPost .= $html;

		return $this;
	}

	/**
	 * Set HTML at the end of the display.
	 *
	 * @param string $html Complete text of message to display
	 *
	 * @since 1.38
	 * @return $this for chaining calls
	 */
	public function setPostHtml( $html ) {
		$this->mPost = $html;

		return $this;
	}

	/**
	 * Get HTML at the end of the display.
	 *
	 * @since 1.38
	 * @return string HTML
	 */
	public function getPostHtml() {
		return $this->mPost;
	}

	/**
	 * Set an array of information about sections.
	 *
	 * @since 1.42
	 *
	 * @param array[] $sections Array of section information, keyed on section name.
	 *
	 * @return HTMLForm $this for chaining calls
	 */
	public function setSections( $sections ) {
		if ( $this->getDisplayFormat() !== 'codex' ) {
			throw new \InvalidArgumentException(
				"Non-Codex HTMLForms do not support additional section information."
			);
		}

		$this->mSections = $sections;

		return $this;
	}

	/**
	 * Add a hidden field to the output
	 * Array values are discarded for security reasons (per WebRequest::getVal)
	 *
	 * @param string $name Field name.  This will be used exactly as entered
	 * @param mixed $value Field value
	 * @param array $attribs
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function addHiddenField( $name, $value, array $attribs = [] ) {
		if ( !is_array( $value ) ) {
			// Per WebRequest::getVal: Array values are discarded for security reasons.
			$attribs += [ 'name' => $name ];
			$this->mHiddenFields[] = [ $value, $attribs ];
		}

		return $this;
	}

	/**
	 * Add an array of hidden fields to the output
	 * Array values are discarded for security reasons (per WebRequest::getVal)
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
			if ( is_array( $value ) ) {
				// Per WebRequest::getVal: Array values are discarded for security reasons.
				continue;
			}
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
	 * @param array $data Data to define the button:
	 *  - name: (string) Button name.
	 *  - value: (string) Button value.
	 *  - label-message: (string|array<string|array>|MessageSpecifier, optional) Button label
	 *    message key to use instead of 'value'. Overrides 'label' and 'label-raw'.
	 *  - label: (string, optional) Button label text to use instead of
	 *    'value'. Overrides 'label-raw'.
	 *  - label-raw: (string, optional) Button label HTML to use instead of
	 *    'value'.
	 *  - id: (string, optional) DOM id for the button.
	 *  - attribs: (array, optional) Additional HTML attributes.
	 *  - flags: (string|string[], optional) OOUI flags.
	 *  - framed: (boolean=true, optional) OOUI framed attribute.
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array{name:string,value:string,label-message?:string|array<string|MessageParam>|MessageSpecifier,label?:string,label-raw?:string,id?:string,attribs?:array,flags?:string|string[],framed?:bool} $data
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
				'id' => $args[2] ?? null,
				'attribs' => $args[3] ?? null,
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
	 * @warning You should call prepareForm() before calling this function.
	 * Moreover, when doing method chaining this should be the very last method
	 * call just after prepareForm().
	 *
	 * @stable to override
	 *
	 * @param bool|string|array|Status $submitResult Output from HTMLForm::trySubmit()
	 *
	 * @return void Nothing, should be last call
	 */
	public function displayForm( $submitResult ) {
		$this->getOutput()->addHTML( $this->getHTML( $submitResult ) );
	}

	/**
	 * Get a hidden field for the title of the page if necessary (empty string otherwise)
	 */
	private function getHiddenTitle(): string {
		if ( $this->hiddenTitleAddedToForm ) {
			return '';
		}

		$html = '';
		if ( $this->getMethod() === 'post' ||
			$this->getAction() === $this->getConfig()->get( MainConfigNames::Script )
		) {
			$html .= Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) . "\n";
		}
		$this->hiddenTitleAddedToForm = true;
		return $html;
	}

	/**
	 * Returns the raw HTML generated by the form
	 *
	 * @stable to override
	 *
	 * @param bool|string|array|Status $submitResult Output from HTMLForm::trySubmit()
	 *
	 * @return string HTML
	 * @return-taint escaped
	 */
	public function getHTML( $submitResult ) {
		# For good measure (it is the default)
		$this->getOutput()->getMetadata()->setPreventClickjacking( true );
		$this->getOutput()->addModules( 'mediawiki.htmlform' );
		$this->getOutput()->addModuleStyles( [
			'mediawiki.htmlform.styles',
			// Html::errorBox and Html::warningBox used by HtmlFormField and HtmlForm::getErrorsOrWarnings
			'mediawiki.codex.messagebox.styles'
		] );

		if ( $this->mCollapsible ) {
			// Preload jquery.makeCollapsible for mediawiki.htmlform
			$this->getOutput()->addModules( 'jquery.makeCollapsible' );
		}

		$headerHtml = $this->getHeaderHtml();
		$footerHtml = $this->getFooterHtml();
		$html = $this->getErrorsOrWarnings( $submitResult, 'error' )
			. $this->getErrorsOrWarnings( $submitResult, 'warning' )
			. $headerHtml
			. $this->getHiddenTitle()
			. $this->getBody()
			. $this->getHiddenFields()
			. $this->getButtons()
			. $footerHtml;

		return $this->mPre . $this->wrapForm( $html ) . $this->mPost;
	}

	/**
	 * Enable collapsible mode, and set whether the form is collapsed by default.
	 *
	 * @since 1.34
	 * @param bool $collapsedByDefault Whether the form is collapsed by default (optional).
	 * @return HTMLForm $this for chaining calls
	 */
	public function setCollapsibleOptions( $collapsedByDefault = false ) {
		$this->mCollapsible = true;
		$this->mCollapsed = $collapsedByDefault;
		return $this;
	}

	/**
	 * Get HTML attributes for the `<form>` tag.
	 * @stable to override
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
		if ( is_string( $this->mAutocomplete ) ) {
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
	 * @stable to override
	 * @param string $html HTML contents to wrap.
	 * @return string|\OOUI\Tag Wrapped HTML.
	 */
	public function wrapForm( $html ) {
		# Include a <fieldset> wrapper for style, if requested.
		if ( $this->mWrapperLegend !== false ) {
			$legend = is_string( $this->mWrapperLegend ) ? $this->mWrapperLegend : false;
			$html = Xml::fieldset( $legend, $html, $this->mWrapperAttributes );
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

		// add the title as a hidden file if it hasn't been added yet and if it is necessary
		// added for backward compatibility with the previous version of this public method
		$html .= $this->getHiddenTitle();

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
		}

		foreach ( $this->mHiddenFields as [ $value, $attribs ] ) {
			$html .= Html::hidden( $attribs['name'], $value, $attribs ) . "\n";
		}

		return $html;
	}

	/**
	 * Get the submit and (potentially) reset buttons.
	 * @stable to override
	 * @return string HTML.
	 */
	public function getButtons() {
		$buttons = '';

		if ( $this->mShowSubmit ) {
			$attribs = [];

			if ( $this->mSubmitID !== null ) {
				$attribs['id'] = $this->mSubmitID;
			}

			if ( $this->mSubmitName !== null ) {
				$attribs['name'] = $this->mSubmitName;
			}

			if ( $this->mSubmitTooltip !== null ) {
				$attribs += Linker::tooltipAndAccesskeyAttribs( $this->mSubmitTooltip );
			}

			$attribs['class'] = [ 'mw-htmlform-submit' ];

			$buttons .= Html::submitButton( $this->getSubmitText(), $attribs ) . "\n";
		}

		if ( $this->mShowCancel ) {
			$target = $this->getCancelTargetURL();
			$buttons .= Html::element(
					'a',
					[
						'href' => $target,
					],
					$this->msg( 'cancel' )->text()
				) . "\n";
		}

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

			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset Always set in self::addButton
			if ( $button['attribs'] ) {
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset Always set in self::addButton
				$attrs += $button['attribs'];
			}

			if ( isset( $button['id'] ) ) {
				$attrs['id'] = $button['id'];
			}

			$buttons .= Html::rawElement( 'button', $attrs, $label ) . "\n";
		}

		if ( !$buttons ) {
			return '';
		}

		return Html::rawElement( 'span',
			[ 'class' => 'mw-htmlform-submit-buttons' ], "\n$buttons" ) . "\n";
	}

	/**
	 * Get the whole body of the form.
	 * @stable to override
	 * @return string
	 */
	public function getBody() {
		return $this->displaySection( $this->mFieldTree, $this->mTableId );
	}

	/**
	 * Returns a formatted list of errors or warnings from the given elements.
	 * @stable to override
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
			[ $errorStatus, $warningStatus ] = $elements->splitByErrorType();
			$status = $elementsType === 'error' ? $errorStatus : $warningStatus;
			if ( $status->isGood() ) {
				$elementstr = '';
			} else {
				$elementstr = $status
					->getMessage()
					->setContext( $this )
					->setInterfaceMessageFlag( true )
					->parse();
			}
		} elseif ( $elementsType === 'error' ) {
			if ( is_array( $elements ) ) {
				$elementstr = $this->formatErrors( $elements );
			} elseif ( $elements && $elements !== true ) {
				$elementstr = (string)$elements;
			}
		}

		if ( !$elementstr ) {
			return '';
		} elseif ( $elementsType === 'error' ) {
			return Html::errorBox( $elementstr );
		} else { // $elementsType can only be 'warning'
			return Html::warningBox( $elementstr );
		}
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

		return Html::rawElement( 'ul', [], $errorstr );
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
	 * - If you use two or more forms on one page with the same submit target, it allows HTMLForm
	 *   to identify which of the forms was submitted, and not attempt to validate the other ones.
	 * - If you use checkbox or multiselect fields inside a form using the GET method, it allows
	 *   HTMLForm to distinguish between the initial page view and a form submission with all
	 *   checkboxes or select options unchecked. Set the second parameter to true if you are sure
	 *   this is the only form on the page, which allows form fields to be prefilled with query
	 *   params.
	 *
	 * @since 1.28
	 * @param string $ident
	 * @param bool $single Only work with GET form, see above. (since 1.41)
	 * @return $this
	 */
	public function setFormIdentifier( string $ident, bool $single = false ) {
		$this->mFormIdentifier = $ident;
		$this->mSingleForm = $single;

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
	 * @param LinkTarget|PageReference|string $target Target as an object or an URL
	 * @return HTMLForm $this for chaining calls
	 * @since 1.27
	 */
	public function setCancelTarget( $target ) {
		if ( $target instanceof PageReference ) {
			$target = TitleValue::castPageToLinkTarget( $target );
		}

		$this->mCancelTarget = $target;
		return $this;
	}

	/**
	 * @since 1.37
	 * @return string
	 */
	protected function getCancelTargetURL() {
		if ( is_string( $this->mCancelTarget ) ) {
			return $this->mCancelTarget;
		} else {
			// TODO: use a service to get the local URL for a LinkTarget, see T282283
			$target = Title::castFromLinkTarget( $this->mCancelTarget ) ?: Title::newMainPage();
			return $target->getLocalURL();
		}
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
	 * For internal use only. Use is discouraged, and should only be used where
	 * support for gadgets/user scripts is warranted.
	 * @param array $attributes
	 * @internal
	 * @return HTMLForm $this for chaining calls
	 */
	public function setWrapperAttributes( $attributes ) {
		$this->mWrapperAttributes = $attributes;

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
	 * @param PageReference $t The page the form is on/should be posted to
	 *
	 * @return HTMLForm $this for chaining calls (since 1.20)
	 */
	public function setTitle( $t ) {
		// TODO: make mTitle a PageReference when we have a better way to get URLs, see T282283.
		$this->mTitle = Title::castFromPageReference( $t );

		return $this;
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle ?: $this->getContext()->getTitle();
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
	 * Wraps the given $section into a user-visible fieldset.
	 * @stable to override
	 *
	 * @param string $legend Legend text for the fieldset
	 * @param string $section The section content in plain Html
	 * @param array $attributes Additional attributes for the fieldset
	 * @param bool $isRoot Section is at the root of the tree
	 * @return string The fieldset's Html
	 */
	protected function wrapFieldSetSection( $legend, $section, $attributes, $isRoot ) {
		return Xml::fieldset( $legend, $section, $attributes ) . "\n";
	}

	/**
	 * @todo Document
	 * @stable to override
	 *
	 * Throws an exception when called on uninitialized field data, e.g. when
	 * HTMLForm::displayForm was called without calling HTMLForm::prepareForm
	 * first.
	 *
	 * @param array[]|HTMLFormField[] $fields Array of fields (either arrays or
	 *   objects).
	 * @param string $sectionName ID attribute of the "<table>" tag for this
	 *   section, ignored if empty.
	 * @param string $fieldsetIDPrefix ID prefix for the "<fieldset>" tag of
	 *   each subsection, ignored if empty.
	 * @param bool &$hasUserVisibleFields Whether the section had user-visible fields.
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

		$html = [];
		$subsectionHtml = '';
		$hasLabel = false;

		foreach ( $fields as $key => $value ) {
			if ( $value instanceof HTMLFormField ) {
				$v = array_key_exists( $key, $this->mFieldData )
					? $this->mFieldData[$key]
					: $value->getDefault();

				$retval = $this->formatField( $value, $v ?? '' );

				// check, if the form field should be added to
				// the output.
				if ( $value->hasVisibleOutput() ) {
					$html[] = $retval;

					$labelValue = trim( $value->getLabel() );
					if ( $labelValue !== "\u{00A0}" && $labelValue !== '&#160;' && $labelValue !== '' ) {
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

				if ( $subsectionHasVisibleFields === true ) {
					// Display the section with various niceties.
					$hasUserVisibleFields = true;

					$legend = $this->getLegend( $key );

					$headerHtml = $this->getHeaderHtml( $key );
					$footerHtml = $this->getFooterHtml( $key );
					$section = $headerHtml .
						$section .
						$footerHtml;

					$attributes = [];
					if ( $fieldsetIDPrefix ) {
						$attributes['id'] = Sanitizer::escapeIdForAttribute( "$fieldsetIDPrefix$key" );
					}
					$subsectionHtml .= $this->wrapFieldSetSection(
						$legend, $section, $attributes, $fields === $this->mFieldTree
					);
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
	 * Generate the HTML for an individual field in the current display format.
	 * @since 1.41
	 * @stable to override
	 * @param HTMLFormField $field
	 * @param mixed $value
	 * @return string|Stringable HTML
	 */
	protected function formatField( HTMLFormField $field, $value ) {
		$displayFormat = $this->getDisplayFormat();
		switch ( $displayFormat ) {
			case 'table':
				return $field->getTableRow( $value );
			case 'div':
				return $field->getDiv( $value );
			case 'raw':
				return $field->getRaw( $value );
			case 'inline':
				return $field->getInline( $value );
			default:
				throw new LogicException( 'Not implemented' );
		}
	}

	/**
	 * Put a form section together from the individual fields' HTML, merging it and wrapping.
	 * @stable to override
	 * @param array $fieldsHtml Array of outputs from formatField()
	 * @param string $sectionName
	 * @param bool $anyFieldHasLabel
	 * @return string HTML
	 */
	protected function formatSection( array $fieldsHtml, $sectionName, $anyFieldHasLabel ) {
		if ( !$fieldsHtml ) {
			// Do not generate any wrappers for empty sections. Sections may be empty if they only have
			// subsections, but no fields. A legend will still be added in wrapFieldSetSection().
			return '';
		}

		$displayFormat = $this->getDisplayFormat();
		$html = implode( '', $fieldsHtml );

		if ( $displayFormat === 'raw' ) {
			return $html;
		}

		// Avoid strange spacing when no labels exist
		$attribs = $anyFieldHasLabel ? [] : [ 'class' => 'mw-htmlform-nolabel' ];

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
	 * @deprecated since 1.39, Use prepareForm() instead.
	 */
	public function loadData() {
		$this->prepareForm();
	}

	/**
	 * Load data of form fields from the request
	 */
	protected function loadFieldData() {
		$fieldData = [];
		$request = $this->getRequest();

		foreach ( $this->mFlatFields as $fieldname => $field ) {
			if ( $field->skipLoadData( $request ) ) {
				continue;
			}
			if ( $field->mParams['disabled'] ?? false ) {
				$fieldData[$fieldname] = $field->getDefault();
			} else {
				$fieldData[$fieldname] = $field->loadDataFromRequest( $request );
			}
		}

		// Reset to default for fields that are supposed to be disabled.
		// FIXME: Handle dependency chains, fields that a field checks on may need a reset too.
		foreach ( $fieldData as $name => &$value ) {
			$field = $this->mFlatFields[$name];
			if ( $field->isDisabled( $fieldData ) ) {
				$value = $field->getDefault();
			}
		}

		# Filter data.
		foreach ( $fieldData as $name => &$value ) {
			$field = $this->mFlatFields[$name];
			$value = $field->filter( $value, $fieldData );
		}

		$this->mFieldData = $fieldData;
	}

	/**
	 * Overload this if you want to apply special filtration routines
	 * to the form as a whole, after it's submitted but before it's
	 * processed.
	 * @stable to override
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
	 * @stable to override
	 *
	 * @param string $key
	 *
	 * @return string Plain text (not HTML-escaped)
	 */
	public function getLegend( $key ) {
		return $this->msg( $this->mMessagePrefix ? "{$this->mMessagePrefix}-$key" : $key )->text();
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
		// If an action is already provided, return it
		if ( $this->mAction !== false ) {
			return $this->mAction;
		}

		$articlePath = $this->getConfig()->get( MainConfigNames::ArticlePath );
		// Check whether we are in GET mode and the ArticlePath contains a "?"
		// meaning that getLocalURL() would return something like "index.php?title=...".
		// As browser remove the query string before submitting GET forms,
		// it means that the title would be lost. In such case use script path instead
		// and put title in a hidden field (see getHiddenFields()).
		if ( str_contains( $articlePath, '?' ) && $this->getMethod() === 'get' ) {
			return $this->getConfig()->get( MainConfigNames::Script );
		}

		return $this->getTitle()->getLocalURL();
	}

	/**
	 * Set the value for the autocomplete attribute of the form. A typical value is "off".
	 * When set to null (which is the default state), the attribute get not set.
	 *
	 * @since 1.27
	 *
	 * @param string|null $autocomplete
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
		foreach ( $this->mFlatFields as $field ) {
			if ( $field->needsJSForHtml5FormValidation() ) {
				return true;
			}
		}
		return false;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLForm::class, 'HTMLForm' );
