<?php

/**
 * Class to build various forms
 */
class HTMLForm {
	/* private */ function fieldset( $name, $content ) {
		return "<fieldset><legend>".wfMsg($name)."</legend>\n" .
			$content . "\n</fieldset>\n";
	}

	/* private */ function checkbox( $varname, $checked=false ) {
		$checked = isset( $GLOBALS[$varname] ) && $GLOBALS[$varname] ;
		return "<div><input type='checkbox' value=\"1\" id=\"{$varname}\" name=\"wpOp{$varname}\"" .
			( $checked ? ' checked="checked"' : '' ) .
			" /><label for=\"{$varname}\">". wfMsg( "sitesettings-".$varname ) .
			"</label></div>\n";
	}

	/* private */ function textbox( $varname, $value='', $size=20 ) {
		$value = isset( $GLOBALS[$varname] ) ? $GLOBALS[$varname] : '';
		return "<div><label>". wfMsg( "sitesettings-".$varname ) .
			"<input type='text' name=\"wpOp{$varname}\" value=\"{$value}\" size=\"{$size}\" /></label></div>\n";
	}
	/* private */ function radiobox( $varname, $fields ) {
		foreach ( $fields as $value => $checked ) {
			$s .= "<div><label><input type='radio' name=\"wpOp{$varname}\" value=\"{$value}\"" .
				( $checked ? ' checked="checked"' : '' ) . " />" . wfMsg( 'sitesettings-'.$varname.'-'.$value ) .
				"</label></div>\n";
		}
		return $this->fieldset( 'sitesettings-'.$varname, $s );
	}

	/* private */ function arraybox( $varname , $size=20 ) {
		$s = '';
		if ( isset( $GLOBALS[$varname] ) && is_array( $GLOBALS[$varname] ) ) {
			foreach ( $GLOBALS[$varname] as $index=>$element ) {
				$s .= $element."\n";
			}
		}
		return "<div><label>".wfMsg( 'sitesettings-'.$varname ).
			"<textarea name=\"wpOp{$varname}\" rows=\"5\" cols=\"{$size}\">{$s}</textarea>\n";
	}
}
?>
