<?php
# See skin.doc

class SkinFramed extends Skin {

	function useBodyTag()
	{
		global $frame;
		return ( "set" != $frame );
	}

	function qbSetting() { return 0; }
	function beforeContent() { return ""; }
	function afterContent() { return ""; }
	function qbLogo() { return ""; }
	function isFramed() { return true; }

	function getBaseTag()
	{
		global $wgServer, $wgScript;

		$s = "<base href=\"{$wgServer}{$wgScript}\" target=\"_top\">\n";
		return $s;
	}

	function getTopFrame()
	{
		$t = $this->pageTitleLinks();
		$t = explode ( "<br>" , $t );
		while ( count ( $t ) < 4 ) { array_push ( $t, "" ); }
		$t = implode ( "<br>", $t );

		$s = "";
		$s .= "<table width='100%' border=0><tr height=152>\n";
		$s .= "<td class='top' valign=top>" . $this->logoText() . "</td>\n";
		$s .= "<td class='top'>&nbsp;&nbsp;</td>\n";
		$s .= "<td class='top' valign=top width='100%'>\n";
		$s .= $this->topLinks();
		$s .= $t;

		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle();

		$s .= "</td>\n<td class='top' valign=top align=right width=200 nowrap>";
		$s .= $this->nameAndLogin();
		$s .= "\n<br>" . $this->searchForm() . "</td>";
		$s .= "</tr></table>";
		return $s;
	}

	function transformContent( $text )
	{
		global $frame, $HTTP_SERVER_VARS;
		global $wgOut, $wgServer, $wgScript;

		$qs = $HTTP_SERVER_VARS["QUERY_STRING"];
		$qs = wfEscapeHTML( $qs );

		if ( "" == $qs ) { $qs = "?frame="; }
		else { $qs = "?{$qs}&amp;frame="; }
		$url = "{$wgServer}{$wgScript}{$qs}";

		if ( "set" == $frame ) {
			$s = "<frameset rows='152,*' border=0>\n" .
			  "<frame marginwidth=0 marginheight=0 frameborder=1 " .
			  "src=\"{$url}top\" noresize scrolling=no>\n" .
			  "<frameset cols='152,*' border=0>\n" .
			  "<frame marginwidth=0 marginheight=0 frameborder=1 " .
			  "src=\"{$url}side\">\n" .
			  "<frame marginwidth=0 marginheight=0 frameborder=1 " .
			  "src=\"{$url}body\">\n" .
			  "</frameset>\n</frameset>\n";
		} else if ( "top" == $frame ) {
			$s = $this->getTopFrame();
		} else if ( "side" == $frame ) {
			$s = $this->quickBar();
			# $s = spliti( "<hr>", $s, 2 );
			# $s = $s[1] ;
		} else if ( "body" == $frame ) {
			$s = $text;
			# Bottom links?
		}
		return $s;
	}
}

?>
