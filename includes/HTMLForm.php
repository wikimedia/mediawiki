<?php
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
		$checked = isset( $GLOBALS[$varname] ) && $GLOBALS[$varname] ;
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
		$value = isset( $GLOBALS[$varname] ) ? $GLOBALS[$varname] : $value;
		return "<div><label>". wfMsg( $this->mName.'-'.$varname ) .
			"<input type='text' name=\"wpOp{$varname}\" value=\"{$value}\" size=\"{$size}\" /></label></div>\n";
	}

	/* 
	 * @access private
	 * @param string $varname Name of the radiobox.
	 * @param array $fields Various fields.
	 */
	function radiobox( $varname, $fields ) {
		foreach ( $fields as $value => $checked ) {
			$s .= "<div><label><input type='radio' name=\"wpOp{$varname}\" value=\"{$value}\"" .
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
		$value = isset( $GLOBALS[$varname] ) ? $GLOBALS[$varname] : $value;
		return '<div><label>'.wfMsg( $this->mName.'-'.$varname ).
		       "<textarea name=\"wpOp{$varname}\" rows=\"5\" cols=\"{$size}\">$value</textarea>\n";
	}

	/* 
	 * @access private
	 * @param string $varname Name of the arraybox.
	 * @param integer $size Optional size of the textarea (default 20)
	 */
	function arraybox( $varname , $size=20 ) {
		$s = '';
		if ( isset( $GLOBALS[$varname] ) && is_array( $GLOBALS[$varname] ) ) {
			foreach ( $GLOBALS[$varname] as $index=>$element ) {
				$s .= $element."\n";
			}
		}
		return "<div><label>".wfMsg( $this->mName.'-'.$varname ).
			"<textarea name=\"wpOp{$varname}\" rows=\"5\" cols=\"{$size}\">{$s}</textarea>\n";
	}
}
?>
