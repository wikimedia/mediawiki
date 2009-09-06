<?php

class HTMLForm {
	static $jsAdded = false;

	/* The descriptor is an array of arrays.
		i.e. array(
			'fieldname' => array( 'section' => 'section/subsection',
								properties... ),
			...
		)
	 */

	static $typeMappings = array(
		'text' => 'HTMLTextField',
		'select' => 'HTMLSelectField',
		'radio' => 'HTMLRadioField',
		'multiselect' => 'HTMLMultiSelectField',
		'check' => 'HTMLCheckField',
		'toggle' => 'HTMLCheckField',
		'int' => 'HTMLIntField',
		'float' => 'HTMLFloatField',
		'info' => 'HTMLInfoField',
		'selectorother' => 'HTMLSelectOrOtherField',
		# HTMLTextField will output the correct type="" attribute automagically.
		# There are about four zillion other HTML 5 input types, like url, but
		# we don't use those at the moment, so no point in adding all of them.
		'email' => 'HTMLTextField',
		'password' => 'HTMLTextField',
	);

	function __construct( $descriptor, $messagePrefix ) {
		$this->mMessagePrefix = $messagePrefix;

		// Expand out into a tree.
		$loadedDescriptor = array();
		$this->mFlatFields = array();

		foreach( $descriptor as $fieldname => $info ) {
			$section = '';
			if ( isset( $info['section'] ) )
				$section = $info['section'];

			$info['name'] = $fieldname;

			$field = $this->loadInputFromParameters( $info );
			$field->mParent = $this;

			$setSection =& $loadedDescriptor;
			if( $section ) {
				$sectionParts = explode( '/', $section );

				while( count( $sectionParts ) ) {
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

		$this->mShowReset = true;
	}

	static function addJS() {
		if( self::$jsAdded ) return;

		global $wgOut;

		$wgOut->addScriptClass( 'htmlform' );
	}

	static function loadInputFromParameters( $descriptor ) {
		if ( isset( $descriptor['class'] ) ) {
			$class = $descriptor['class'];
		} elseif ( isset( $descriptor['type'] ) ) {
			$class = self::$typeMappings[$descriptor['type']];
			$descriptor['class'] = $class;
		}

		if( !$class ) {
			throw new MWException( "Descriptor with no class: " . print_r( $descriptor, true ) );
		}

		$obj = new $class( $descriptor );

		return $obj;
	}

	function show() {
		$html = '';

		self::addJS();

		// Load data from the request.
		$this->loadData();

		// Try a submission
		global $wgUser, $wgRequest;
		$editToken = $wgRequest->getVal( 'wpEditToken' );

		$result = false;
		if ( $wgUser->matchEditToken( $editToken ) )
			$result = $this->trySubmit();

		if( $result === true )
			return $result;

		// Display form.
		$this->displayForm( $result );
	}

	/** Return values:
	  * TRUE == Successful submission
	  * FALSE == No submission attempted
	  * Anything else == Error to display.
	  */
	function trySubmit() {
		// Check for validation
		foreach( $this->mFlatFields as $fieldname => $field ) {
			if ( !empty( $field->mParams['nodata'] ) ) continue;
			if ( $field->validate( $this->mFieldData[$fieldname],
					$this->mFieldData ) !== true ) {
				return isset( $this->mValidationErrorMessage ) ?
						$this->mValidationErrorMessage : array( 'htmlform-invalid-input' );
			}
		}

		$callback = $this->mSubmitCallback;

		$data = $this->filterDataForSubmit( $this->mFieldData );

		$res = call_user_func( $callback, $data );

		return $res;
	}

	function setSubmitCallback( $cb ) {
		$this->mSubmitCallback = $cb;
	}

	function setValidationErrorMessage( $msg ) {
		$this->mValidationErrorMessage = $msg;
	}

	function setIntro( $msg ) {
		$this->mIntro = $msg;
	}

	function displayForm( $submitResult ) {
		global $wgOut;

		if ( $submitResult !== false ) {
			$this->displayErrors( $submitResult );
		}

		if ( isset( $this->mIntro ) ) {
			$wgOut->addHTML( $this->mIntro );
		}

		$html = $this->getBody();

		// Hidden fields
		$html .= $this->getHiddenFields();

		// Buttons
		$html .= $this->getButtons();

		$html = $this->wrapForm( $html );

		$wgOut->addHTML( $html );
	}

	function wrapForm( $html ) {
		return Xml::tags(
			'form',
			array(
				'action' => $this->getTitle()->getFullURL(),
				'method' => 'post',
			),
			$html
		);
	}

	function getHiddenFields() {
		global $wgUser;
		$html = '';

		$html .= Xml::hidden( 'wpEditToken', $wgUser->editToken() ) . "\n";
		$html .= Xml::hidden( 'title', $this->getTitle() ) . "\n";

		return $html;
	}

	function getButtons() {
		$html = '';

		$attribs = array();

		if ( isset( $this->mSubmitID ) )
			$attribs['id'] = $this->mSubmitID;

		$attribs['class'] = 'mw-htmlform-submit';

		$html .= Xml::submitButton( $this->getSubmitText(), $attribs ) . "\n";

		if( $this->mShowReset ) {
			$html .= Xml::element(
				'input',
				array(
					'type' => 'reset',
					'value' => wfMsg( 'htmlform-reset' )
				)
			) . "\n";
		}
		
		return $html;
	}

	function getBody() {
		return $this->displaySection( $this->mFieldTree );
	}

	function displayErrors( $errors ) {
		if ( is_array( $errors ) ) {
			$errorstr = $this->formatErrors( $errors );
		} else {
			$errorstr = $errors;
		}
		
		$errorstr = Xml::tags( 'div', array( 'class' => 'error' ), $errorstr );

		global $wgOut;
		$wgOut->addHTML( $errorstr );
	}

	static function formatErrors( $errors ) {
		$errorstr = '';
		foreach ( $errors as $error ) {
			if( is_array( $error ) ) {
				$msg = array_shift( $error );
			} else {
				$msg = $error;
				$error = array();
			}
			$errorstr .= Xml::tags(
				'li',
				null,
				wfMsgExt( $msg, array( 'parseinline' ), $error )
			);
		}

		$errorstr = Xml::tags( 'ul', null, $errorstr );

		return $errorstr;
	}

	function setSubmitText( $t ) {
		$this->mSubmitText = $t;
	}

	function getSubmitText() {
		return isset( $this->mSubmitText ) ? $this->mSubmitText : wfMsg( 'htmlform-submit' );
	}

	function setSubmitID( $t ) {
		$this->mSubmitID = $t;
	}

	function setMessagePrefix( $p ) {
		$this->mMessagePrefix = $p;
	}

	function setTitle( $t ) {
		$this->mTitle = $t;
	}

	function getTitle() {
		return $this->mTitle;
	}

	function displaySection( $fields ) {
		$tableHtml = '';
		$subsectionHtml = '';
		$hasLeftColumn = false;

		foreach( $fields as $key => $value ) {
			if ( is_object( $value ) ) {
				$v = empty( $value->mParams['nodata'] )
							? $this->mFieldData[$key]
							: $value->getDefault();
				$tableHtml .= $value->getTableRow( $v );

				if( $value->getLabel() != '&nbsp;' )
					$hasLeftColumn = true;
			} elseif ( is_array( $value ) ) {
				$section = $this->displaySection( $value );
				$legend = wfMsg( "{$this->mMessagePrefix}-$key" );
				$subsectionHtml .= Xml::fieldset( $legend, $section ) . "\n";
			}
		}

		$classes = array();
		if( !$hasLeftColumn ) // Avoid strange spacing when no labels exist
			$classes[] = 'mw-htmlform-nolabel';
		$classes = implode( ' ', $classes );

		$tableHtml = "<table class='$classes'><tbody>\n$tableHtml\n</tbody></table>\n";

		return $subsectionHtml . "\n" . $tableHtml;
	}

	function loadData() {
		global $wgRequest;

		$fieldData = array();

		foreach( $this->mFlatFields as $fieldname => $field ) {
			if ( !empty( $field->mParams['nodata'] ) ) continue;
			if ( !empty( $field->mParams['disabled'] ) ) {
				$fieldData[$fieldname] = $field->getDefault();
			} else {
				$fieldData[$fieldname] = $field->loadDataFromRequest( $wgRequest );
			}
		}

		// Filter data.
		foreach( $fieldData as $name => &$value ) {
			$field = $this->mFlatFields[$name];
			$value = $field->filter( $value, $this->mFlatFields );
		}

		$this->mFieldData = $fieldData;
	}

	function importData( $fieldData ) {
		// Filter data.
		foreach( $fieldData as $name => &$value ) {
			$field = $this->mFlatFields[$name];
			$value = $field->filter( $value, $this->mFlatFields );
		}

		foreach( $this->mFlatFields as $fieldname => $field ) {
			if ( !isset( $fieldData[$fieldname] ) )
				$fieldData[$fieldname] = $field->getDefault();
		}

		$this->mFieldData = $fieldData;
	}

	function suppressReset( $suppressReset = true ) {
		$this->mShowReset = !$suppressReset;
	}

	function filterDataForSubmit( $data ) {
		return $data;
	}
}

abstract class HTMLFormField {
	abstract function getInputHTML( $value );

	function validate( $value, $alldata ) {
		if ( isset( $this->mValidationCallback ) ) {
			return call_user_func( $this->mValidationCallback, $value, $alldata );
		}

		return true;
	}

	function filter( $value, $alldata ) {
		if( isset( $this->mFilterCallback ) ) {
			$value = call_user_func( $this->mFilterCallback, $value, $alldata );
		}

		return $value;
	}

	function loadDataFromRequest( $request ) {
		if( $request->getCheck( $this->mName ) ) {
			return $request->getText( $this->mName );
		} else {
			return $this->getDefault();
		}
	}

	function __construct( $params ) {
		$this->mParams = $params;

		if( isset( $params['label-message'] ) ) {
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

		if ( isset( $params['name'] ) ) {
			$name = $params['name'];
			$validName = Sanitizer::escapeId( $name );
			if( $name != $validName ) {
				throw new MWException("Invalid name '$name' passed to " . __METHOD__ );
			}
			$this->mName = 'wp'.$name;
			$this->mID = 'mw-input-'.$name;
		}

		if ( isset( $params['default'] ) ) {
			$this->mDefault = $params['default'];
		}

		if ( isset( $params['id'] ) ) {
			$id = $params['id'];
			$validId = Sanitizer::escapeId( $id );
			if( $id != $validId ) {
				throw new MWException("Invalid id '$id' passed to " . __METHOD__ );
			}
			$this->mID = $id;
		}

		if ( isset( $params['validation-callback'] ) ) {
			$this->mValidationCallback = $params['validation-callback'];
		}

		if ( isset( $params['filter-callback'] ) ) {
			$this->mFilterCallback = $params['filter-callback'];
		}
	}

	function getTableRow( $value ) {
		// Check for invalid data.
		global $wgRequest;

		$errors = $this->validate( $value, $this->mParent->mFieldData );
		if ( $errors === true || !$wgRequest->wasPosted() ) {
			$errors = '';
		} else {
			$errors = Xml::tags( 'span', array( 'class' => 'error' ), $errors );
		}

		$html = '';

		$html .= Xml::tags( 'td', array( 'class' => 'mw-label' ),
					Xml::tags( 'label', array( 'for' => $this->mID ), $this->getLabel() )
				);
		$html .= Xml::tags( 'td', array( 'class' => 'mw-input' ),
							$this->getInputHTML( $value ) ."\n$errors" );

		$fieldType = get_class( $this );

		$html = Xml::tags( 'tr', array( 'class' => "mw-htmlform-field-$fieldType" ),
							$html ) . "\n";

		$helptext = null;
		if ( isset( $this->mParams['help-message'] ) ) {
			$msg = $this->mParams['help-message'];
			$helptext = wfMsgExt( $msg, 'parseinline' );
			if ( wfEmptyMsg( $msg, $helptext ) ) {
				# Never mind
				$helptext = null;
			}
		} elseif ( isset( $this->mParams['help'] ) ) {
			$helptext = $this->mParams['help'];
		}

		if ( !is_null( $helptext ) ) {
			$row = Xml::tags( 'td', array( 'colspan' => 2, 'class' => 'htmlform-tip' ),
				$helptext );
			$row = Xml::tags( 'tr', null, $row );
			$html .= "$row\n";
		}

		return $html;
	}

	function getLabel() {
		return $this->mLabel;
	}

	function getDefault() {
		if ( isset( $this->mDefault ) ) {
			return $this->mDefault;
		} else {
			return null;
		}
	}

	static function flattenOptions( $options ) {
		$flatOpts = array();

		foreach( $options as $key => $value ) {
			if ( is_array( $value ) ) {
				$flatOpts = array_merge( $flatOpts, self::flattenOptions( $value ) );
			} else {
				$flatOpts[] = $value;
			}
		}

		return $flatOpts;
	}
}

class HTMLTextField extends HTMLFormField {
	# Override in derived classes to use other Xml::... functions
	protected $mFunction = 'input';
	
	function getSize() {
		return isset( $this->mParams['size'] ) ? $this->mParams['size'] : 45;
	}

	function getInputHTML( $value ) {
		global $wgHtml5;
		$attribs = array(
			'id' => $this->mID,
			'name' => $this->mName,
			'size' => $this->getSize(),
			'value' => $value,
		);

		if ( isset( $this->mParams['maxlength'] ) ) {
			$attribs['maxlength'] = $this->mParams['maxlength'];
		}
		
		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		if ( $wgHtml5 ) {
			# TODO: Enforce pattern, step, required, readonly on the server
			# side as well
			foreach ( array( 'min', 'max', 'pattern', 'title', 'step',
			'placeholder' ) as $param ) {
				if ( isset( $this->mParams[$param] ) ) {
					$attribs[$param] = $this->mParams[$param];
				}
			}
			foreach ( array( 'required', 'autofocus', 'multiple', 'readonly' )
			as $param ) {
				if ( isset( $this->mParams[$param] ) ) {
					$attribs[$param] = '';
				}
			}
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
				case 'password':
					$attribs['type'] = 'password';
					break;
				}
			}
		}

		return Html::element( 'input', $attribs );
	}
}

class HTMLFloatField extends HTMLTextField {
	function getSize() {
		return isset( $this->mParams['size'] ) ? $this->mParams['size'] : 20;
	}

	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) return $p;

		if ( floatval( $value ) != $value ) {
			return wfMsgExt( 'htmlform-float-invalid', 'parse' );
		}

		$in_range = true;

		# The "int" part of these message names is rather confusing.  They make
		# equal sense for all numbers.
		if ( isset( $this->mParams['min'] ) ) {
			$min = $this->mParams['min'];
			if ( $min > $value )
				return wfMsgExt( 'htmlform-int-toolow', 'parse', array( $min ) );
		}

		if ( isset( $this->mParams['max'] ) ) {
			$max = $this->mParams['max'];
			if( $max < $value )
				return wfMsgExt( 'htmlform-int-toohigh', 'parse', array( $max ) );
		}

		return true;
	}
}

class HTMLIntField extends HTMLFloatField {
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) return $p;

		if ( intval( $value ) != $value ) {
			return wfMsgExt( 'htmlform-int-invalid', 'parse' );
		}

		return true;
	}
}

class HTMLCheckField extends HTMLFormField {
	function getInputHTML( $value ) {
		if ( !empty( $this->mParams['invert'] ) )
			$value = !$value;

		$attr = array( 'id' => $this->mID );
		if( !empty( $this->mParams['disabled'] ) ) {
			$attr['disabled'] = 'disabled';
		}

		return Xml::check( $this->mName, $value, $attr ) . '&nbsp;' .
				Xml::tags( 'label', array( 'for' => $this->mID ), $this->mLabel );
	}

	function getLabel() {
		return '&nbsp;'; // In the right-hand column.
	}

	function loadDataFromRequest( $request ) {
		$invert = false;
		if ( isset( $this->mParams['invert'] ) && $this->mParams['invert'] ) {
			$invert = true;
		}

		// GetCheck won't work like we want for checks.
		if( $request->getCheck( 'wpEditToken' ) ) {
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

class HTMLSelectField extends HTMLFormField {

	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );
		if( $p !== true ) return $p;

		$validOptions = HTMLFormField::flattenOptions( $this->mParams['options'] );
		if ( in_array( $value, $validOptions ) )
			return true;
		else
			return wfMsgExt( 'htmlform-select-badoption', 'parseinline' );
	}

	function getInputHTML( $value ) {
		$select = new XmlSelect( $this->mName, $this->mID, strval( $value ) );

		// If one of the options' 'name' is int(0), it is automatically selected.
		//  because PHP sucks and things int(0) == 'some string'.
		//  Working around this by forcing all of them to strings.
		$options = array_map( 'strval', $this->mParams['options'] );

		if( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
		}

		$select->addOptions( $options );

		return $select->getHTML();
	}
}

class HTMLSelectOrOtherField extends HTMLTextField {
	static $jsAdded = false;

	function __construct( $params ) {
		if( !in_array( 'other', $params['options'] ) ) {
			$params['options'][wfMsg( 'htmlform-selectorother-other' )] = 'other';
		}

		parent::__construct( $params );
	}
	
	static function forceToStringRecursive( $array ) {
		if ( is_array($array) ) {
			return array_map( array( __CLASS__, 'forceToStringRecursive' ), $array);
		} else {
			return strval($array);
		}
	}

	function getInputHTML( $value ) {
		$valInSelect = false;

		if( $value !== false )
			$valInSelect = in_array( $value,
							HTMLFormField::flattenOptions( $this->mParams['options'] ) );

		$selected = $valInSelect ? $value : 'other';
		
		$opts = self::forceToStringRecursive( $this->mParams['options'] );

		$select = new XmlSelect( $this->mName, $this->mID, $selected );
		$select->addOptions( $opts );

		$select->setAttribute( 'class', 'mw-htmlform-select-or-other' );

		$tbAttribs = array( 'id' => $this->mID . '-other' );
		if( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
			$tbAttribs['disabled'] = 'disabled';
		}

		$select = $select->getHTML();

		if ( isset( $this->mParams['maxlength'] ) ) {
			$tbAttribs['maxlength'] = $this->mParams['maxlength'];
		}

		$textbox = Xml::input( $this->mName . '-other',
							$this->getSize(),
							$valInSelect ? '' : $value,
							$tbAttribs );

		return "$select<br/>\n$textbox";
	}

	function loadDataFromRequest( $request ) {
		if( $request->getCheck( $this->mName ) ) {
			$val = $request->getText( $this->mName );

			if( $val == 'other' ) {
				$val = $request->getText( $this->mName . '-other' );
			}

			return $val;
		} else {
			return $this->getDefault();
		}
	}
}

class HTMLMultiSelectField extends HTMLFormField {
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );
		if( $p !== true ) return $p;

		if( !is_array( $value ) ) return false;

		// If all options are valid, array_intersect of the valid options and the provided
		//  options will return the provided options.
		$validOptions = HTMLFormField::flattenOptions( $this->mParams['options'] );

		$validValues = array_intersect( $value, $validOptions );
		if ( count( $validValues ) == count( $value ) )
			return true;
		else
			return wfMsgExt( 'htmlform-select-badoption', 'parseinline' );
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

		foreach( $options as $label => $info ) {
			if( is_array( $info ) ) {
				$html .= Xml::tags( 'h1', null, $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$thisAttribs = array( 'id' => $this->mID . "-$info", 'value' => $info );
				
				$checkbox = Xml::check( $this->mName . '[]', in_array( $info, $value ),
								$attribs + $thisAttribs );
				$checkbox .= '&nbsp;' . Xml::tags( 'label', array( 'for' => $this->mID . "-$info" ), $label );

				$html .= $checkbox . '<br />';
			}
		}

		return $html;
	}

	function loadDataFromRequest( $request ) {
		// won't work with getCheck
		if( $request->getCheck( 'wpEditToken' ) ) {
			$arr = $request->getArray( $this->mName );

			if( !$arr )
				$arr = array();

			return $arr;
		} else {
			return $this->getDefault();
		}
	}

	function getDefault() {
		if ( isset( $this->mDefault ) ) {
			return $this->mDefault;
		} else {
			return array();
		}
	}
}

class HTMLRadioField extends HTMLFormField {
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );
		if( $p !== true ) return $p;

		if( !is_string( $value ) && !is_int( $value ) )
			return false;

		$validOptions = HTMLFormField::flattenOptions( $this->mParams['options'] );

		if ( in_array( $value, $validOptions ) )
			return true;
		else
			return wfMsgExt( 'htmlform-select-badoption', 'parseinline' );
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

		foreach( $options as $label => $info ) {
			if( is_array( $info ) ) {
				$html .= Xml::tags( 'h1', null, $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$id = Sanitizer::escapeId( $this->mID . "-$info" );
				$html .= Xml::radio( $this->mName, $info, $info == $value,
										$attribs + array( 'id' => $id ) );
				$html .= '&nbsp;' .
						Xml::tags( 'label', array( 'for' => $id ), $label );

				$html .= "<br/>\n";
			}
		}

		return $html;
	}
}

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
}
