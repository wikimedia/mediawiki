<?php
/**
 * This file contain a class to easily build HTML forms as well as custom
 * functions used by SpecialUserlevels.php and SpecialGrouplevels.php
 */

/**
 * Class to build various forms
 *
 * @package MediaWiki
 * @author jeluf, hashar
 */
class HTMLForm {
	/** name of our form. Used as prefix for labels */
	var $mName;

	/**
	 * @access private
	 * @param string $name Name of the fieldset.
	 * @param string $content HTML content to put in.
	 * @return string HTML fieldset
	 */
	function fieldset( $name, $content ) {
		return "<fieldset><legend>".wfMsg($this->mName.'-'.$name)."</legend>\n" .
			$content . "\n</fieldset>\n";
	}

	/*
	 * @access private
	 * @param string $varname Name of the checkbox.
	 * @param boolean $checked Set true to check the box (default False).
	 */
	function checkbox( $varname, $checked=false ) {
		$checked = isset( $_POST[$varname] ) && $_POST[$varname] ;
		return "<div><input type='checkbox' value=\"1\" id=\"{$varname}\" name=\"wpOp{$varname}\"" .
			( $checked ? ' checked="checked"' : '' ) .
			" /><label for=\"{$varname}\">". wfMsg( $this->mName.'-'.$varname ) .
			"</label></div>\n";
	}

	/* 
	 * @access private
	 * @param string $varname Name of the textbox.
	 * @param string $value Optional value (default empty)
	 * @param integer $size Optional size of the textbox (default 20)
	 */
	function textbox( $varname, $value='', $size=20 ) {
		$value = isset( $_POST[$varname] ) ? $_POST[$varname] : $value;
		return "<div><label>". wfMsg( $this->mName.'-'.$varname ) .
			"<input type='text' name=\"{$varname}\" value=\"{$value}\" size=\"{$size}\" /></label></div>\n";
	}

	/* 
	 * @access private
	 * @param string $varname Name of the radiobox.
	 * @param array $fields Various fields.
	 */
	function radiobox( $varname, $fields ) {
		foreach ( $fields as $value => $checked ) {
			$s .= "<div><label><input type='radio' name=\"{$varname}\" value=\"{$value}\"" .
				( $checked ? ' checked="checked"' : '' ) . " />" . wfMsg( $this->mName.'-'.$varname.'-'.$value ) .
				"</label></div>\n";
		}
		return $this->fieldset( $this->mName.'-'.$varname, $s );
	}
	
	/* 
	 * @access private
	 * @param string $varname Name of the textareabox.
	 * @param string $value Optional value (default empty)
	 * @param integer $size Optional size of the textarea (default 20)
	 */
	function textareabox ( $varname, $value='', $size=20 ) {
		$value = isset( $_POST[$varname] ) ? $_POST[$varname] : $value;
		return '<div><label>'.wfMsg( $this->mName.'-'.$varname ).
		       "<textarea name=\"{$varname}\" rows=\"5\" cols=\"{$size}\">$value</textarea></label></div>\n";
	}

	/* 
	 * @access private
	 * @param string $varname Name of the arraybox.
	 * @param integer $size Optional size of the textarea (default 20)
	 */
	function arraybox( $varname , $size=20 ) {
		$s = '';
		if ( isset( $_POST[$varname] ) && is_array( $_POST[$varname] ) ) {
			foreach ( $_POST[$varname] as $index=>$element ) {
				$s .= $element."\n";
			}
		}
		return "<div><label>".wfMsg( $this->mName.'-'.$varname ).
			"<textarea name=\"{$varname}\" rows=\"5\" cols=\"{$size}\">{$s}</textarea>\n";
	}
} // end class


// functions used by SpecialUserlevels & SpecialGrouplevels

/** Build a select with all existent groups
 * @param string $selectname Name of this element. Name of form is automaticly prefixed.
 * @param array $selected Array of element selected when posted. Multiples will only show them.
 * @param boolean $multiple A multiple elements select.
 * @param integer $size Number of element to be shown ignored for non multiple (default 6).
 * @param boolean $reverse If true, multiple select will hide selected elements (default false).
*/
function HTMLSelectGroups($selectname, $selected=array(), $multiple=false, $size=6, $reverse=false) {
	$dbr =& wfGetDB( DB_SLAVE );
	$group = $dbr->tableName( 'group' );
	$sql = "SELECT group_id, group_name FROM $group";
	$res = $dbr->query($sql,'wfSpecialAdmin');
	
	$out = wfMsg($selectname);
	$out .= '<select name="'.$selectname;
	if($multiple) {	$out.='[]" multiple="multiple" size="'.$size; }
	$out.= "\">\n";
	
	while($g = $dbr->fetchObject( $res ) ) {
		if($multiple) {
			// for multiple will only show the things we want
			if(in_array($g->group_id, $selected) xor $reverse) { 
				$out .= '<option value="'.$g->group_id.'">'.$g->group_name."</option>\n";
			}
		} else {
			$out .= '<option ';
			if(in_array($g->group_id, $selected)) { $out .= 'selected="selected" '; }
			$out .= 'value="'.$g->group_id.'">'.$g->group_name."</option>\n";
		}
	}
	$out .= "</select>\n";
	return $out;
}

/** Build a select with all existent rights
 * @param array $selected Names(?) of user rights that should be selected.
 * @return string HTML select.
 */
function HTMLSelectRights($selected='') {
	global $wgAvailableRights;
	$out = '<select name="editgroup-getrights[]" multiple="multiple">';
	$groupRights = explode(',',$selected);
	
	foreach($wgAvailableRights as $right) {
	
		// check box when right exist
		if(in_array($right, $groupRights)) { $selected = 'selected="selected" '; }
		else { $selected = ''; }
					
		$out .= '<option value="'.$right.'" '.$selected.'>'.$right."</option>\n";
	}
	$out .= "</select>\n";
	return $out;
}
?>
