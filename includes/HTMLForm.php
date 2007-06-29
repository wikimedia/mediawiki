<?php
/**
 * This file contain a class to easily build HTML forms as well as custom
 * functions used by SpecialUserrights.php
 */

/**
 * Class to build various forms
 *
 * @author jeluf, hashar
 */
class HTMLForm {
	/** name of our form. Used as prefix for labels */
	var $mName, $mRequest;

	function HTMLForm( &$request ) {
		$this->mRequest = $request;
	}

	/**
	 * @private
	 * @param $name String: name of the fieldset.
	 * @param $content String: HTML content to put in.
	 * @return string HTML fieldset
	 */
	function fieldset( $name, $content ) {
		return "<fieldset><legend>".wfMsg($this->mName.'-'.$name)."</legend>\n" .
			$content . "\n</fieldset>\n";
	}

	/**
	 * @private
	 * @param $varname String: name of the checkbox.
	 * @param $checked Boolean: set true to check the box (default False).
	 */
	function checkbox( $varname, $checked=false ) {
		if ( $this->mRequest->wasPosted() && !is_null( $this->mRequest->getVal( $varname ) ) ) {
			$checked = $this->mRequest->getCheck( $varname );
		}
		return "<div><input type='checkbox' value=\"1\" id=\"{$varname}\" name=\"wpOp{$varname}\"" .
			( $checked ? ' checked="checked"' : '' ) .
			" /><label for=\"{$varname}\">". wfMsg( $this->mName.'-'.$varname ) .
			"</label></div>\n";
	}

	/**
	 * @private
	 * @param $varname String: name of the textbox.
	 * @param $value String: optional value (default empty)
	 * @param $size Integer: optional size of the textbox (default 20)
	 */
	function textbox( $varname, $value='', $size=20 ) {
		if ( $this->mRequest->wasPosted() ) {
			$value = $this->mRequest->getText( $varname, $value );
		}
		$value = htmlspecialchars( $value );
		return "<div><label>". wfMsg( $this->mName.'-'.$varname ) .
			"<input type='text' name=\"{$varname}\" value=\"{$value}\" size=\"{$size}\" /></label></div>\n";
	}

	/**
	 * @private
	 * @param $varname String: name of the radiobox.
	 * @param $fields Array: Various fields.
	 */
	function radiobox( $varname, $fields ) {
		foreach ( $fields as $value => $checked ) {
			$s .= "<div><label><input type='radio' name=\"{$varname}\" value=\"{$value}\"" .
				( $checked ? ' checked="checked"' : '' ) . " />" . wfMsg( $this->mName.'-'.$varname.'-'.$value ) .
				"</label></div>\n";
		}
		return $this->fieldset( $varname, $s );
	}

	/**
	 * @private
	 * @param $varname String: name of the textareabox.
	 * @param $value String: optional value (default empty)
	 * @param $size Integer: optional size of the textarea (default 20)
	 */
	function textareabox ( $varname, $value='', $size=20 ) {
		if ( $this->mRequest->wasPosted() ) {
			$value = $this->mRequest->getText( $varname, $value );
		}
		$value = htmlspecialchars( $value );
		return '<div><label>'.wfMsg( $this->mName.'-'.$varname ).
		       "<textarea name=\"{$varname}\" rows=\"5\" cols=\"{$size}\">$value</textarea></label></div>\n";
	}

	/**
	 * @private
	 * @param $varname String: name of the arraybox.
	 * @param $size Integer: Optional size of the textarea (default 20)
	 */
	function arraybox( $varname , $size=20 ) {
		$s = '';
		if ( $this->mRequest->wasPosted() ) {
			$arr = $this->mRequest->getArray( $varname );
			if ( is_array( $arr ) ) {
				foreach ( $_POST[$varname] as $element ) {
					$s .= htmlspecialchars( $element )."\n";
				}
			}
		}
		return "<div><label>".wfMsg( $this->mName.'-'.$varname ).
			"<textarea name=\"{$varname}\" rows=\"5\" cols=\"{$size}\">{$s}</textarea>\n";
	}
} // end class

/** Build a select with all defined groups
 *
 * used by SpecialUserrights.php
 * @todo move it to there, and don't forget to copy it for SpecialMakesysop.php
 *
 * @param $selectname String: name of this element. Name of form is automaticly prefixed.
 * @param $selectmsg String: FIXME
 * @param $selected Array: array of element selected when posted. Only multiples will show them.
 * @param $multiple Boolean: A multiple elements select.
 * @param $size Integer: number of elements to be shown ignored for non-multiple (default 6).
 * @param $reverse Boolean: if true, multiple select will hide selected elements (default false).
 * @todo Document $selectmsg
*/
function HTMLSelectGroups($selectname, $selectmsg, $selected=array(), $multiple=false, $size=6, $reverse=false) {
	$groups = User::getAllGroups();
	$out = htmlspecialchars( wfMsg( $selectmsg ) );
	$out .= "<br />";

	if( $multiple ) {
		$attribs = array(
			'name'    => $selectname . '[]',
			'multiple'=> 'multiple',
			'size'    => $size );
	} else {
		$attribs = array( 'name' => $selectname );
	}
	$attribs['style'] = 'width: 100%';
	$out .= wfOpenElement( 'select', $attribs );

	foreach( $groups as $group ) {
		$attribs = array( 'value' => $group );
		if( $multiple ) {
			// for multiple will only show the things we want
			if( !in_array( $group, $selected ) xor $reverse ) {
				continue;
			}
		} else {
			if( in_array( $group, $selected ) ) {
				$attribs['selected'] = 'selected';
			}
		}
		$out .= wfElement( 'option', $attribs, User::getGroupName( $group ) ) . "\n";
	}

	$out .= "</select>\n";
	return $out;
}


