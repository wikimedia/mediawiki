<?php

# Globals used: 
#    objects:   $wgUser, $wgTitle, $wgLang, $wgDateFormatter, $wgLinkCache, $wgCurOut, $wgArticle
#
#    query:     $wpPreview
#               
#    settings:  $wgUseTex, $wgUseCategoryMagic, $wgUseDynamicDates, $wgInterwikiMagic,
#               $wgNamespacesWithSubpages, $wgLanguageCode, $wgUseLinkPrefixCombination

class Parser
{
	var $mOutput, $mAutonumber, $mLastSection, $mDTopen;
	
	function Parser()
	{
		$this->clearState();
	}

	function clearState()
	{
		$this->mOutput = new ParserOutput;
		$this->mAutonumber = 0;
		$this->mLastSection = "";
		$this->mDTopen = false;
	}
	
	# First pass--just handle <nowiki> sections, pass the rest off
	# to doWikiPass2() which does all the real work.
	#
	# Returns a ParserOutput
	#
	function parse( $text, $linestart = true, $clearState = true )
	{
		global $wgUseTeX;
		$fname = "Parser::parse";
		wfProfileIn( $fname );
		$unique  = "3iyZiyA7iMwg5rhxP0Dcc9oTnj8qD1jm1Sfv4";
		$unique2 = "4LIQ9nXtiYFPCSfitVwDw7EYwQlL4GeeQ7qSO";
		$unique3 = "fPaA8gDfdLBqzj68Yjg9Hil3qEF8JGO0uszIp";
		$nwlist = array();
		$nwsecs = 0;
		$mathlist = array();
		$mathsecs = 0;
		$prelist = array ();
		$presecs = 0;
		$stripped = "";
		$stripped2 = "";
		$stripped3 = "";

		if ( $clearState ) {
			$this->clearState();
		}
				
		# Replace any instances of the placeholders
		$text = str_replace( $unique, wfHtmlEscapeFirst( $unique ), $text );
		$text = str_replace( $unique2, wfHtmlEscapeFirst( $unique2 ), $text );
		$text = str_replace( $unique3, wfHtmlEscapeFirst( $unique3 ), $text );
			
		while ( "" != $text ) {
			$p = preg_split( "/<\\s*nowiki\\s*>/i", $text, 2 );
			$stripped .= $p[0];
			if ( ( count( $p ) < 2 ) || ( "" == $p[1] ) ) { $text = ""; }
			else {
				$q = preg_split( "/<\\/\\s*nowiki\\s*>/i", $p[1], 2 );
				++$nwsecs;
				$nwlist[$nwsecs] = wfEscapeHTMLTagsOnly($q[0]);
				$stripped .= $unique . $nwsecs . "s";
				$text = $q[1];
			}
		}

		if( $wgUseTeX ) {
			while ( "" != $stripped ) {
				$p = preg_split( "/<\\s*math\\s*>/i", $stripped, 2 );
				$stripped2 .= $p[0];
				if ( ( count( $p ) < 2 ) || ( "" == $p[1] ) ) { $stripped = ""; }
				else {
					$q = preg_split( "/<\\/\\s*math\\s*>/i", $p[1], 2 );
					++$mathsecs;
					$mathlist[$mathsecs] = renderMath($q[0]);
					$stripped2 .= $unique2 . $mathsecs . "s";
					$stripped = $q[1];
				}
			}
		} else {
			$stripped2 = $stripped;
		}

		while ( "" != $stripped2 ) {
			$p = preg_split( "/<\\s*pre\\s*>/i", $stripped2, 2 );
			$stripped3 .= $p[0];
			if ( ( count( $p ) < 2 ) || ( "" == $p[1] ) ) { $stripped2 = ""; }
			else {
				$q = preg_split( "/<\\/\\s*pre\\s*>/i", $p[1], 2 );
				++$presecs;
				$prelist[$presecs] = "<pre>". wfEscapeHTMLTagsOnly($q[0]). "</pre>\n";
				$stripped3 .= $unique3 . $presecs . "s";
				$stripped2 = $q[1];
			}
		}

		$text = $this->doWikiPass2( $stripped3, $linestart );
		
		$specialChars = array("\\", "$");
		$escapedChars = array("\\\\", "\\$");

		# Go backwards so that {$unique1}1 doesn't overwrite {$unique1}10
		for ( $i = $presecs; $i >= 1; --$i ) {
			$text = preg_replace( "/{$unique3}{$i}s/", str_replace( $specialChars, 
				$escapedChars, $prelist[$i] ), $text );
		}

		for ( $i = $mathsecs; $i >= 1; --$i ) {
			$text = preg_replace( "/{$unique2}{$i}s/", str_replace( $specialChars, 
				$escapedChars, $mathlist[$i] ), $text );
		}

		for ( $i = $nwsecs; $i >= 1; --$i ) {
			$text = preg_replace( "/{$unique}{$i}s/", str_replace( $specialChars, 
				$escapedChars, $nwlist[$i] ), $text );
		}
		
		$this->mOutput->setText( $text );
		wfProfileOut( $fname );
		return $this->mOutput;
	}

	function categoryMagic ()
	{
		global $wgTitle , $wgUseCategoryMagic, $wgLang ;
		if ( !isset ( $wgUseCategoryMagic ) || !$wgUseCategoryMagic ) return ;
		$id = $wgTitle->getArticleID() ;
		$cat = ucfirst ( wfMsg ( "category" ) ) ;
		$ti = $wgTitle->getText() ;
		$ti = explode ( ":" , $ti , 2 ) ;
		if ( $cat != $ti[0] ) return "" ;
		$r = "<br break=all>\n" ;

		$articles = array() ;
		$parents = array () ;
		$children = array() ;


		global $wgUser ;
		$sk = $wgUser->getSkin() ;

		$doesexist = false ;
		if ( $doesexist ) {
			$sql = "SELECT l_from FROM links WHERE l_to={$id}" ;
		} else {
			$sql = "SELECT cur_title,cur_namespace FROM cur,brokenlinks WHERE bl_to={$id} AND bl_from=cur_id" ;
		}

		$res = wfQuery ( $sql, DB_READ ) ;
		while ( $x = wfFetchObject ( $res ) )
		{
		#  $t = new Title ; 
		#  $t->newFromDBkey ( $x->l_from ) ;
		#  $t = $t->getText() ;
			if ( $doesexist ) {
				$t = $x->l_from ;
			} else {
				$t = $wgLang->getNsText ( $x->cur_namespace ) ;
				if ( $t != "" ) $t .= ":" ;
				$t .= $x->cur_title ;
			}

			$y = explode ( ":" , $t , 2 ) ;
			if ( count ( $y ) == 2 && $y[0] == $cat ) {
				array_push ( $children , $sk->makeLink ( $t , $y[1] ) ) ;
			} else {
				array_push ( $articles , $sk->makeLink ( $t ) ) ;
			}
		}
		wfFreeResult ( $res ) ;

		# Children
		if ( count ( $children ) > 0 )
		{
			asort ( $children ) ;
			$r .= "<h2>".wfMsg("subcategories")."</h2>\n" ;
			$r .= implode ( ", " , $children ) ;
		}

		# Articles
		if ( count ( $articles ) > 0 )
		{
			asort ( $articles ) ;
			$h =  wfMsg( "category_header", $ti[1] );
			$r .= "<h2>{$h}</h2>\n" ;
			$r .= implode ( ", " , $articles ) ;
		}


		return $r ;
	}

function getHTMLattrs ()
{
		$htmlattrs = array( # Allowed attributes--no scripting, etc.
			"title", "align", "lang", "dir", "width", "height",
			"bgcolor", "clear", /* BR */ "noshade", /* HR */
			"cite", /* BLOCKQUOTE, Q */ "size", "face", "color",
			/* FONT */ "type", "start", "value", "compact",
			/* For various lists, mostly deprecated but safe */
			"summary", "width", "border", "frame", "rules",
			"cellspacing", "cellpadding", "valign", "char",
			"charoff", "colgroup", "col", "span", "abbr", "axis",
			"headers", "scope", "rowspan", "colspan", /* Tables */
			"id", "class", "name", "style" /* For CSS */
		);
return $htmlattrs ;
}

function fixTagAttributes ( $t )
{
	if ( trim ( $t ) == "" ) return "" ; # Saves runtime ;-)
	$htmlattrs = $this->getHTMLattrs() ;
  
	# Strip non-approved attributes from the tag
	$t = preg_replace(
		"/(\\w+)(\\s*=\\s*([^\\s\">]+|\"[^\">]*\"))?/e",
		"(in_array(strtolower(\"\$1\"),\$htmlattrs)?(\"\$1\".((\"x\$3\" != \"x\")?\"=\$3\":'')):'')",
		$t);
	# Strip javascript "expression" from stylesheets. Brute force approach:
	# If anythin offensive is found, all attributes of the HTML tag are dropped

	if( preg_match( 
		"/style\\s*=.*(expression|tps*:\/\/|url\\s*\().*/is",
		wfMungeToUtf8( $t ) ) )
	{
		$t="";
	}

	return trim ( $t ) ;
}

function doTableStuff ( $t )
{
	$t = explode ( "\n" , $t ) ;
	$td = array () ; # Is currently a td tag open?
		$ltd = array () ; # Was it TD or TH?
		$tr = array () ; # Is currently a tr tag open?
		$ltr = array () ; # tr attributes
		foreach ( $t AS $k => $x )
		{
			$x = rtrim ( $x ) ;
			$fc = substr ( $x , 0 , 1 ) ;
			if ( "{|" == substr ( $x , 0 , 2 ) )
			{
				$t[$k] = "<table " . $this->fixTagAttributes ( substr ( $x , 3 ) ) . ">" ;
				array_push ( $td , false ) ;
				array_push ( $ltd , "" ) ;
				array_push ( $tr , false ) ;
				array_push ( $ltr , "" ) ;
			}
			else if ( count ( $td ) == 0 ) { } # Don't do any of the following
			else if ( "|}" == substr ( $x , 0 , 2 ) )
			{
				$z = "</table>\n" ;
				$l = array_pop ( $ltd ) ;
				if ( array_pop ( $tr ) ) $z = "</tr>" . $z ;
				if ( array_pop ( $td ) ) $z = "</{$l}>" . $z ;
				array_pop ( $ltr ) ;
				$t[$k] = $z ;
			}
			/*      else if ( "|_" == substr ( $x , 0 , 2 ) ) # Caption
					{ 
					$z = trim ( substr ( $x , 2 ) ) ;
					$t[$k] = "<caption>{$z}</caption>\n" ;
					}*/
			else if ( "|-" == substr ( $x , 0 , 2 ) ) # Allows for |---------------
			{
				$x = substr ( $x , 1 ) ;
				while ( $x != "" && substr ( $x , 0 , 1 ) == '-' ) $x = substr ( $x , 1 ) ;
				$z = "" ;
				$l = array_pop ( $ltd ) ;
				if ( array_pop ( $tr ) ) $z = "</tr>" . $z ;
				if ( array_pop ( $td ) ) $z = "</{$l}>" . $z ;
				array_pop ( $ltr ) ;
				$t[$k] = $z ;
				array_push ( $tr , false ) ;
				array_push ( $td , false ) ;
				array_push ( $ltd , "" ) ;
				array_push ( $ltr , $this->fixTagAttributes ( $x ) ) ;
			}
			else if ( "|" == $fc || "!" == $fc || "|+" == substr ( $x , 0 , 2 ) ) # Caption
			{
				if ( "|+" == substr ( $x , 0 , 2 ) )
				{
					$fc = "+" ;
					$x = substr ( $x , 1 ) ;
				}
				$after = substr ( $x , 1 ) ;
				if ( $fc == "!" ) $after = str_replace ( "!!" , "||" , $after ) ;
				$after = explode ( "||" , $after ) ;
				$t[$k] = "" ;
				foreach ( $after AS $theline )
				{
					$z = "" ;
					if ( $fc != "+" )
					{  
						$tra = array_pop ( $ltr ) ;
						if ( !array_pop ( $tr ) ) $z = "<tr {$tra}>\n" ;
						array_push ( $tr , true ) ;
						array_push ( $ltr , "" ) ;
					}

					$l = array_pop ( $ltd ) ;
					if ( array_pop ( $td ) ) $z = "</{$l}>" . $z ;
					if ( $fc == "|" ) $l = "TD" ;
					else if ( $fc == "!" ) $l = "TH" ;
					else if ( $fc == "+" ) $l = "CAPTION" ;
					else $l = "" ;
					array_push ( $ltd , $l ) ;
					$y = explode ( "|" , $theline , 2 ) ;
					if ( count ( $y ) == 1 ) $y = "{$z}<{$l}>{$y[0]}" ;
					else $y = $y = "{$z}<{$l} ".$this->fixTagAttributes($y[0]).">{$y[1]}" ;
					$t[$k] .= $y ;
					array_push ( $td , true ) ;
				}
			}
		}

	# Closing open td, tr && table
	while ( count ( $td ) > 0 )
	{
		if ( array_pop ( $td ) ) $t[] = "</td>" ;
		if ( array_pop ( $tr ) ) $t[] = "</tr>" ;
		$t[] = "</table>" ;
	}

	$t = implode ( "\n" , $t ) ;
	#		$t = $this->removeHTMLtags( $t );
	return $t ;
}

	# Well, OK, it's actually about 14 passes.  But since all the
	# hard lifting is done inside PHP's regex code, it probably
	# wouldn't speed things up much to add a real parser.
	#
	function doWikiPass2( $text, $linestart )
	{
		global $wgUser, $wgLang, $wgUseDynamicDates;
		$fname = "OutputPage::doWikiPass2";
		wfProfileIn( $fname );
		
		$text = $this->removeHTMLtags( $text );
		$text = $this->replaceVariables( $text );

		$text = preg_replace( "/(^|\n)-----*/", "\\1<hr>", $text );
		$text = str_replace ( "<HR>", "<hr>", $text );

		$text = $this->doAllQuotes( $text );
		$text = $this->doHeadings( $text );
		$text = $this->doBlockLevels( $text, $linestart );
		
		if($wgUseDynamicDates) {
			global $wgDateFormatter;
			$text = $wgDateFormatter->reformat( $wgUser->getOption("date"), $text );
		}

		$text = $this->replaceExternalLinks( $text );
		$text = $this->replaceInternalLinks ( $text );
		$text = $this->doTableStuff ( $text ) ;

		$text = $this->magicISBN( $text );
		$text = $this->magicRFC( $text );
		$text = $this->formatHeadings( $text );

		$sk = $wgUser->getSkin();
		$text = $sk->transformContent( $text );
		$text .= $this->categoryMagic () ;

		wfProfileOut( $fname );
		return $text;
	}

	/* private */ function doAllQuotes( $text )
	{
		$outtext = "";
		$lines = explode( "\r\n", $text );
		foreach ( $lines as $line ) {
			$outtext .= $this->doQuotes ( "", $line, "" ) . "\r\n";
		}
		return $outtext;
	}

	/* private */ function doQuotes( $pre, $text, $mode )
	{
		if ( preg_match( "/^(.*)''(.*)$/sU", $text, $m ) ) {
			$m1_strong = ($m[1] == "") ? "" : "<strong>{$m[1]}</strong>";
			$m1_em = ($m[1] == "") ? "" : "<em>{$m[1]}</em>";
			if ( substr ($m[2], 0, 1) == "'" ) {
				$m[2] = substr ($m[2], 1);
				if ($mode == "em") {
					return $this->doQuotes ( $m[1], $m[2], ($m[1] == "") ? "both" : "emstrong" );
				} else if ($mode == "strong") {
					return $m1_strong . $this->doQuotes ( "", $m[2], "" );
				} else if (($mode == "emstrong") || ($mode == "both")) {
					return $this->doQuotes ( "", $pre.$m1_strong.$m[2], "em" );
				} else if ($mode == "strongem") {
					return "<strong>{$pre}{$m1_em}</strong>" . $this->doQuotes ( "", $m[2], "em" );
				} else {
					return $m[1] . $this->doQuotes ( "", $m[2], "strong" );
				}
			} else {
				if ($mode == "strong") {
					return $this->doQuotes ( $m[1], $m[2], ($m[1] == "") ? "both" : "strongem" );
				} else if ($mode == "em") {
					return $m1_em . $this->doQuotes ( "", $m[2], "" );
				} else if ($mode == "emstrong") {
					return "<em>{$pre}{$m1_strong}</em>" . $this->doQuotes ( "", $m[2], "strong" );
				} else if (($mode == "strongem") || ($mode == "both")) {
					return $this->doQuotes ( "", $pre.$m1_em.$m[2], "strong" );
				} else {
					return $m[1] . $this->doQuotes ( "", $m[2], "em" );
				}
			}
		} else {
			$text_strong = ($text == "") ? "" : "<strong>{$text}</strong>";
			$text_em = ($text == "") ? "" : "<em>{$text}</em>";
			if ($mode == "") {
				return $pre . $text;
			} else if ($mode == "em") {
				return $pre . $text_em;
			} else if ($mode == "strong") {
				return $pre . $text_strong;
			} else if ($mode == "strongem") {
				return (($pre == "") && ($text == "")) ? "" : "<strong>{$pre}{$text_em}</strong>";
			} else {
				return (($pre == "") && ($text == "")) ? "" : "<em>{$pre}{$text_strong}</em>";
			}
		}
	}

	/* private */ function doHeadings( $text )
	{
		for ( $i = 6; $i >= 1; --$i ) {
			$h = substr( "======", 0, $i );
			$text = preg_replace( "/^{$h}([^=]+){$h}(\\s|$)/m",
			  "<h{$i}>\\1</h{$i}>\\2", $text );
		}
		return $text;
	}

	# Note: we have to do external links before the internal ones,
	# and otherwise take great care in the order of things here, so
	# that we don't end up interpreting some URLs twice.

	/* private */ function replaceExternalLinks( $text )
	{
		$fname = "OutputPage::replaceExternalLinks";
		wfProfileIn( $fname );
		$text = $this->subReplaceExternalLinks( $text, "http", true );
		$text = $this->subReplaceExternalLinks( $text, "https", true );
		$text = $this->subReplaceExternalLinks( $text, "ftp", false );
		$text = $this->subReplaceExternalLinks( $text, "irc", false );
		$text = $this->subReplaceExternalLinks( $text, "gopher", false );
		$text = $this->subReplaceExternalLinks( $text, "news", false );
		$text = $this->subReplaceExternalLinks( $text, "mailto", false );
		wfProfileOut( $fname );
		return $text;
	}
	
	/* private */ function subReplaceExternalLinks( $s, $protocol, $autonumber )
	{
		global $wgUser, $printable;
		global $wgAllowExternalImages;


		$unique = "4jzAfzB8hNvf4sqyO9Edd8pSmk9rE2in0Tgw3";
		$uc = "A-Za-z0-9_\\/~%\\-+&*#?!=()@\\x80-\\xFF";
		
		# this is  the list of separators that should be ignored if they 
		# are the last character of an URL but that should be included
		# if they occur within the URL, e.g. "go to www.foo.com, where .."
		# in this case, the last comma should not become part of the URL,
		# but in "www.foo.com/123,2342,32.htm" it should.
		$sep = ",;\.:";   
		$fnc = "A-Za-z0-9_.,~%\\-+&;#*?!=()@\\x80-\\xFF";
		$images = "gif|png|jpg|jpeg";

		# PLEASE NOTE: The curly braces { } are not part of the regex,
		# they are interpreted as part of the string (used to tell PHP
		# that the content of the string should be inserted there).
		$e1 = "/(^|[^\\[])({$protocol}:)([{$uc}{$sep}]+)\\/([{$fnc}]+)\\." .
		  "((?i){$images})([^{$uc}]|$)/";
		  
		$e2 = "/(^|[^\\[])({$protocol}:)(([".$uc."]|[".$sep."][".$uc."])+)([^". $uc . $sep. "]|[".$sep."]|$)/";
		$sk = $wgUser->getSkin();

		if ( $autonumber and $wgAllowExternalImages) { # Use img tags only for HTTP urls
			$s = preg_replace( $e1, "\\1" . $sk->makeImage( "{$unique}:\\3" .
			  "/\\4.\\5", "\\4.\\5" ) . "\\6", $s );
		}
		$s = preg_replace( $e2, "\\1" . "<a href=\"{$unique}:\\3\"" .
		  $sk->getExternalLinkAttributes( "{$unique}:\\3", wfEscapeHTML(
		  "{$unique}:\\3" ) ) . ">" . wfEscapeHTML( "{$unique}:\\3" ) .
		  "</a>\\5", $s );
		$s = str_replace( $unique, $protocol, $s );

		$a = explode( "[{$protocol}:", " " . $s );
		$s = array_shift( $a );
		$s = substr( $s, 1 );

		$e1 = "/^([{$uc}"."{$sep}]+)](.*)\$/sD";
		$e2 = "/^([{$uc}"."{$sep}]+)\\s+([^\\]]+)](.*)\$/sD";

		foreach ( $a as $line ) {
			if ( preg_match( $e1, $line, $m ) ) {
				$link = "{$protocol}:{$m[1]}";
				$trail = $m[2];
				if ( $autonumber ) { $text = "[" . ++$this->mAutonumber . "]"; }
				else { $text = wfEscapeHTML( $link ); }
			} else if ( preg_match( $e2, $line, $m ) ) {
				$link = "{$protocol}:{$m[1]}";
				$text = $m[2];
				$trail = $m[3];			
			} else {
				$s .= "[{$protocol}:" . $line;
				continue;
			}
			if ( $printable == "yes") $paren = " (<i>" . htmlspecialchars ( $link ) . "</i>)";
			else $paren = "";
			$la = $sk->getExternalLinkAttributes( $link, $text );
			$s .= "<a href='{$link}'{$la}>{$text}</a>{$paren}{$trail}";

		}
		return $s;
	}

	/* private */ function replaceInternalLinks( $s )
	{
		global $wgTitle, $wgUser, $wgLang;
		global $wgLinkCache, $wgInterwikiMagic, $wgUseCategoryMagic;
		global $wgNamespacesWithSubpages, $wgLanguageCode;
		wfProfileIn( $fname = "OutputPage::replaceInternalLinks" );

		wfProfileIn( "$fname-setup" );
		$tc = Title::legalChars() . "#";
		$sk = $wgUser->getSkin();

		$a = explode( "[[", " " . $s );
		$s = array_shift( $a );
		$s = substr( $s, 1 );

		# Match a link having the form [[namespace:link|alternate]]trail
		$e1 = "/^([{$tc}]+)(?:\\|([^]]+))?]](.*)\$/sD";
		# Match the end of a line for a word that's not followed by whitespace,
		# e.g. in the case of 'The Arab al[[Razi]]', 'al' will be matched
		#$e2 = "/^(.*)\\b(\\w+)\$/suD";
		#$e2 = "/^(.*\\s)(\\S+)\$/suD";
		$e2 = '/^(.*\s)([a-zA-Z\x80-\xff]+)$/sD';
		

		# Special and Media are pseudo-namespaces; no pages actually exist in them
		$image = Namespace::getImage();
		$special = Namespace::getSpecial();
		$media = Namespace::getMedia();
		$category = wfMsg ( "category" ) ;
		$nottalk = !Namespace::isTalk( $wgTitle->getNamespace() );

		if ( $wgLang->linkPrefixExtension() && preg_match( $e2, $s, $m ) ) {
			$new_prefix = $m[2];
			$s = $m[1];
		} else {
			$new_prefix="";
		}

		wfProfileOut( "$fname-setup" );

		foreach ( $a as $line ) {
			$prefix = $new_prefix;
			if ( $wgLang->linkPrefixExtension() && preg_match( $e2, $line, $m ) ) {
				$new_prefix = $m[2];
				$line = $m[1];
			} else {
				$new_prefix = "";
			}
			if ( preg_match( $e1, $line, $m ) ) { # page with normal text or alt
				$text = $m[2];
				$trail = $m[3];				
			} else { # Invalid form; output directly
				$s .= $prefix . "[[" . $line ;
				continue;
			}
			
			/* Valid link forms:
			Foobar -- normal
			:Foobar -- override special treatment of prefix (images, language links)
			/Foobar -- convert to CurrentPage/Foobar
			/Foobar/ -- convert to CurrentPage/Foobar, strip the initial / from text
			*/
			$c = substr($m[1],0,1);
			$noforce = ($c != ":");
			if( $c == "/" ) { # subpage
				if(substr($m[1],-1,1)=="/") {                 # / at end means we don't want the slash to be shown
					$m[1]=substr($m[1],1,strlen($m[1])-2); 
					$noslash=$m[1];
				} else {
					$noslash=substr($m[1],1);
				}
				if($wgNamespacesWithSubpages[$wgTitle->getNamespace()]) { # subpages allowed here
					$link = $wgTitle->getPrefixedText(). "/" . trim($noslash);
					if( "" == $text ) {
						$text= $m[1]; 
					} # this might be changed for ugliness reasons
				} else {
					$link = $noslash; # no subpage allowed, use standard link
				}
			} elseif( $noforce ) { # no subpage
				$link = $m[1];
			} else {
				$link = substr( $m[1], 1 );
			}
			if( "" == $text )
				$text = $link;

			$nt = Title::newFromText( $link );
			if( !$nt ) {
				$s .= $prefix . "[[" . $line;
				continue;
			}
			$ns = $nt->getNamespace();
			$iw = $nt->getInterWiki();
			if( $noforce ) {
				if( $iw && $wgInterwikiMagic && $nottalk && $wgLang->getLanguageName( $iw ) ) {
					array_push( $this->mOutput->mLanguageLinks, $nt->getPrefixedText() );
					$s .= $prefix . $trail;
					continue;
				}
				if( $ns == $image ) {
					$s .= $prefix . $sk->makeImageLinkObj( $nt, $text ) . $trail;
					$wgLinkCache->addImageLinkObj( $nt );
					continue;
				}
			}
			if( ( $nt->getPrefixedText() == $wgTitle->getPrefixedText() ) &&
			    ( strpos( $link, "#" ) == FALSE ) ) {
				$s .= $prefix . "<strong>" . $text . "</strong>" . $trail;
				continue;
			}
 			if ( $ns == $category && $wgUseCategoryMagic ) {
			  $t = explode ( ":" , $nt->getText() ) ;
 				array_shift ( $t ) ;
 				$t = implode ( ":" , $t ) ;
 				$t = $wgLang->ucFirst ( $t ) ;
#				$t = $sk->makeKnownLink( $category.":".$t, $t, "", $trail , $prefix );
				$nnt = Title::newFromText ( $category.":".$t ) ;
				$t = $sk->makeLinkObj( $nnt, $t, "", $trail , $prefix );
 				$this->mCategoryLinks[] = $t ;
 				$s .= $prefix . $trail ;
 				continue ;	    
			}
			if( $ns == $media ) {
				$s .= $prefix . $sk->makeMediaLinkObj( $nt, $text ) . $trail;
				$wgLinkCache->addImageLinkObj( $nt );
				continue;
			} elseif( $ns == $special ) {
				$s .= $prefix . $sk->makeKnownLinkObj( $nt, $text, "", $trail );
				continue;
			}
			$s .= $sk->makeLinkObj( $nt, $text, "", $trail , $prefix );
		}
		wfProfileOut( $fname );
		return $s;
	}

	# Some functions here used by doBlockLevels()
	#
	/* private */ function closeParagraph()
	{
		$result = "";
		if ( 0 != strcmp( "p", $this->mLastSection ) &&
		  0 != strcmp( "", $this->mLastSection ) ) {
			$result = "</" . $this->mLastSection  . ">";
		}
		$this->mLastSection = "";
		return $result."\n";
	}
	# getCommon() returns the length of the longest common substring
	# of both arguments, starting at the beginning of both.
	#
	/* private */ function getCommon( $st1, $st2 )
	{
		$fl = strlen( $st1 );
		$shorter = strlen( $st2 );
		if ( $fl < $shorter ) { $shorter = $fl; }

		for ( $i = 0; $i < $shorter; ++$i ) {
			if ( $st1{$i} != $st2{$i} ) { break; }
		}
		return $i;
	}
	# These next three functions open, continue, and close the list
	# element appropriate to the prefix character passed into them.
	#
	/* private */ function openList( $char )
    {
		$result = $this->closeParagraph();

		if ( "*" == $char ) { $result .= "<ul><li>"; }
		else if ( "#" == $char ) { $result .= "<ol><li>"; }
		else if ( ":" == $char ) { $result .= "<dl><dd>"; }
		else if ( ";" == $char ) {
			$result .= "<dl><dt>";
			$this->mDTopen = true;
		}
		else { $result = "<!-- ERR 1 -->"; }

		return $result;
	}

	/* private */ function nextItem( $char )
	{
		if ( "*" == $char || "#" == $char ) { return "</li><li>"; }
		else if ( ":" == $char || ";" == $char ) {
			$close = "</dd>";
			if ( $this->mDTopen ) { $close = "</dt>"; }
			if ( ";" == $char ) {
				$this->mDTopen = true;
				return $close . "<dt>";
			} else {
				$this->mDTopen = false;
				return $close . "<dd>";
			}
		}
		return "<!-- ERR 2 -->";
	}

	/* private */function closeList( $char )
	{
		if ( "*" == $char ) { $text = "</li></ul>"; }
		else if ( "#" == $char ) { $text = "</li></ol>"; }
		else if ( ":" == $char ) {
			if ( $this->mDTopen ) {
				$this->mDTopen = false;
				$text = "</dt></dl>";
			} else {
				$text = "</dd></dl>";
			}
		}
		else {	return "<!-- ERR 3 -->"; }
		return $text."\n";
	}

	/* private */ function doBlockLevels( $text, $linestart )
	{
		$fname = "OutputPage::doBlockLevels";
		wfProfileIn( $fname );
		# Parsing through the text line by line.  The main thing
		# happening here is handling of block-level elements p, pre,
		# and making lists from lines starting with * # : etc.
		#
		$a = explode( "\n", $text );
		$text = $lastPref = "";
		$this->mDTopen = $inBlockElem = false;

		if ( ! $linestart ) { $text .= array_shift( $a ); }
		foreach ( $a as $t ) {
			if ( "" != $text ) { $text .= "\n"; }

			$oLine = $t;
			$opl = strlen( $lastPref );
			$npl = strspn( $t, "*#:;" );
			$pref = substr( $t, 0, $npl );
			$pref2 = str_replace( ";", ":", $pref );
			$t = substr( $t, $npl );

			if ( 0 != $npl && 0 == strcmp( $lastPref, $pref2 ) ) {
				$text .= $this->nextItem( substr( $pref, -1 ) );

				if ( ";" == substr( $pref, -1 ) ) {
					$cpos = strpos( $t, ":" );
					if ( ! ( false === $cpos ) ) {
						$term = substr( $t, 0, $cpos );
						$text .= $term . $this->nextItem( ":" );
						$t = substr( $t, $cpos + 1 );
					}
				}
			} else if (0 != $npl || 0 != $opl) {
				$cpl = $this->getCommon( $pref, $lastPref );

				while ( $cpl < $opl ) {
					$text .= $this->closeList( $lastPref{$opl-1} );
					--$opl;
				}
				if ( $npl <= $cpl && $cpl > 0 ) {
					$text .= $this->nextItem( $pref{$cpl-1} );
				}
				while ( $npl > $cpl ) {
					$char = substr( $pref, $cpl, 1 );
					$text .= $this->openList( $char );

					if ( ";" == $char ) {
						$cpos = strpos( $t, ":" );
						if ( ! ( false === $cpos ) ) {
							$term = substr( $t, 0, $cpos );
							$text .= $term . $this->nextItem( ":" );
							$t = substr( $t, $cpos + 1 );
						}
					}
					++$cpl;
				}
				$lastPref = $pref2;
			}
			if ( 0 == $npl ) { # No prefix--go to paragraph mode
				if ( preg_match(
				  "/(<table|<blockquote|<h1|<h2|<h3|<h4|<h5|<h6)/i", $t ) ) {
					$text .= $this->closeParagraph();
					$inBlockElem = true;
				}
				if ( ! $inBlockElem ) {
					if ( " " == $t{0} ) {
						$newSection = "pre";
						# $t = wfEscapeHTML( $t );
					}
					else { $newSection = "p"; }

					if ( 0 == strcmp( "", trim( $oLine ) ) ) {
						$text .= $this->closeParagraph();
						$text .= "<" . $newSection . ">";
					} else if ( 0 != strcmp( $this->mLastSection,
					  $newSection ) ) {
						$text .= $this->closeParagraph();
						if ( 0 != strcmp( "p", $newSection ) ) {
							$text .= "<" . $newSection . ">";
						}
					}
					$this->mLastSection = $newSection;
				}
				if ( $inBlockElem &&
				  preg_match( "/(<\\/table|<\\/blockquote|<\\/h1|<\\/h2|<\\/h3|<\\/h4|<\\/h5|<\\/h6)/i", $t ) ) {
					$inBlockElem = false;
				}
			}
			$text .= $t;
		}
		while ( $npl ) {
			$text .= $this->closeList( $pref2{$npl-1} );
			--$npl;
		}
		if ( "" != $this->mLastSection ) {
			if ( "p" != $this->mLastSection ) {
				$text .= "</" . $this->mLastSection . ">";
			}
			$this->mLastSection = "";
		}
		wfProfileOut( $fname );
		return $text;
	}

	/* private */ function replaceVariables( $text )
	{
		global $wgLang, $wgCurOut;
		$fname = "OutputPage::replaceVariables";
		wfProfileIn( $fname );

		$magic = array();

		# Basic variables
		# See Language.php for the definition of each magic word
		# As with sigs, this uses the server's local time -- ensure 
		# this is appropriate for your audience!

		$magic[MAG_CURRENTMONTH] = date( "m" );
		$magic[MAG_CURRENTMONTHNAME] = $wgLang->getMonthName( date("n") );
		$magic[MAG_CURRENTMONTHNAMEGEN] = $wgLang->getMonthNameGen( date("n") );
		$magic[MAG_CURRENTDAY] = date("j");
		$magic[MAG_CURRENTDAYNAME] = $wgLang->getWeekdayName( date("w")+1 );
		$magic[MAG_CURRENTYEAR] = date( "Y" );
		$magic[MAG_CURRENTTIME] = $wgLang->time( wfTimestampNow(), false );
		
		$this->mContainsOldMagic += MagicWord::replaceMultiple($magic, $text, $text);

		$mw =& MagicWord::get( MAG_NUMBEROFARTICLES );
		if ( $mw->match( $text ) ) {
			$v = wfNumberOfArticles();
			$text = $mw->replace( $v, $text );
			if( $mw->getWasModified() ) { $this->mContainsOldMagic++; }
		}

		# "Variables" with an additional parameter e.g. {{MSG:wikipedia}}
		# The callbacks are at the bottom of this file
		$wgCurOut = $this;
		$mw =& MagicWord::get( MAG_MSG );
		$text = $mw->substituteCallback( $text, "wfReplaceMsgVar" );
		if( $mw->getWasModified() ) { $this->mContainsNewMagic++; }

		$mw =& MagicWord::get( MAG_MSGNW );
		$text = $mw->substituteCallback( $text, "wfReplaceMsgnwVar" );
		if( $mw->getWasModified() ) { $this->mContainsNewMagic++; }

		wfProfileOut( $fname );
		return $text;
	}

	# Cleans up HTML, removes dangerous tags and attributes
	/* private */ function removeHTMLtags( $text )
	{
		$fname = "OutputPage::removeHTMLtags";
		wfProfileIn( $fname );
		$htmlpairs = array( # Tags that must be closed
			"b", "i", "u", "font", "big", "small", "sub", "sup", "h1",
			"h2", "h3", "h4", "h5", "h6", "cite", "code", "em", "s",
			"strike", "strong", "tt", "var", "div", "center",
			"blockquote", "ol", "ul", "dl", "table", "caption", "pre",
			"ruby", "rt" , "rb" , "rp"
		);
		$htmlsingle = array(
			"br", "p", "hr", "li", "dt", "dd"
		);
		$htmlnest = array( # Tags that can be nested--??
			"table", "tr", "td", "th", "div", "blockquote", "ol", "ul",
			"dl", "font", "big", "small", "sub", "sup"
		);
		$tabletags = array( # Can only appear inside table
			"td", "th", "tr"
		);

		$htmlsingle = array_merge( $tabletags, $htmlsingle );
		$htmlelements = array_merge( $htmlsingle, $htmlpairs );

                $htmlattrs = $this->getHTMLattrs () ;

		# Remove HTML comments
		$text = preg_replace( "/<!--.*-->/sU", "", $text );

		$bits = explode( "<", $text );
		$text = array_shift( $bits );
		$tagstack = array(); $tablestack = array();

		foreach ( $bits as $x ) {
			$prev = error_reporting( E_ALL & ~( E_NOTICE | E_WARNING ) );
			preg_match( "/^(\\/?)(\\w+)([^>]*)(\\/{0,1}>)([^<]*)$/",
			  $x, $regs );
			list( $qbar, $slash, $t, $params, $brace, $rest ) = $regs;
			error_reporting( $prev );

			$badtag = 0 ;
			if ( in_array( $t = strtolower( $t ), $htmlelements ) ) {
				# Check our stack
				if ( $slash ) {
					# Closing a tag...
					if ( ! in_array( $t, $htmlsingle ) &&
					  ( $ot = array_pop( $tagstack ) ) != $t ) {
						array_push( $tagstack, $ot );
						$badtag = 1;
					} else {
						if ( $t == "table" ) {
							$tagstack = array_pop( $tablestack );
						}
						$newparams = "";
					}
				} else {
					# Keep track for later
					if ( in_array( $t, $tabletags ) &&
					  ! in_array( "table", $tagstack ) ) {
						$badtag = 1;
					} else if ( in_array( $t, $tagstack ) &&
					  ! in_array ( $t , $htmlnest ) ) {
						$badtag = 1 ;
					} else if ( ! in_array( $t, $htmlsingle ) ) {
						if ( $t == "table" ) {
							array_push( $tablestack, $tagstack );
							$tagstack = array();
						}
						array_push( $tagstack, $t );
					}
					# Strip non-approved attributes from the tag
					$newparams = $this->fixTagAttributes($params);
						
				}
				if ( ! $badtag ) {
					$rest = str_replace( ">", "&gt;", $rest );
					$text .= "<$slash$t $newparams$brace$rest";
					continue;
				}
			}
			$text .= "&lt;" . str_replace( ">", "&gt;", $x);
		}
		# Close off any remaining tags
		while ( $t = array_pop( $tagstack ) ) {
			$text .= "</$t>\n";
			if ( $t == "table" ) { $tagstack = array_pop( $tablestack ); }
		}
		wfProfileOut( $fname );
		return $text;
	}

/* 
 * 
 * This function accomplishes several tasks:
 * 1) Auto-number headings if that option is enabled
 * 2) Add an [edit] link to sections for logged in users who have enabled the option
 * 3) Add a Table of contents on the top for users who have enabled the option
 * 4) Auto-anchor headings
 *
 * It loops through all headlines, collects the necessary data, then splits up the
 * string and re-inserts the newly formatted headlines.
 *
 * */
	/* private */ function formatHeadings( $text )
	{
		global $wgUser,$wgArticle,$wgTitle,$wpPreview;
		$nh=$wgUser->getOption( "numberheadings" );
		$st=$wgUser->getOption( "showtoc" );
		if(!$wgTitle->userCanEdit()) {
			$es=0;
			$esr=0;
		} else {
			$es=$wgUser->getID() && $wgUser->getOption( "editsection" );
			$esr=$wgUser->getID() && $wgUser->getOption( "editsectiononrightclick" );
		}

		# Inhibit editsection links if requested in the page
		$esw =& MagicWord::get( MAG_NOEDITSECTION );
		if ($esw->matchAndRemove( $text )) {
			$es=0;
		}
		# if the string __NOTOC__ (not case-sensitive) occurs in the HTML, 
		# do not add TOC
		$mw =& MagicWord::get( MAG_NOTOC );
		if ($mw->matchAndRemove( $text ))
		{
			$st = 0;
		}

		# never add the TOC to the Main Page. This is an entry page that should not
		# be more than 1-2 screens large anyway
		if($wgTitle->getPrefixedText()==wfMsg("mainpage")) {$st=0;}

		# We need this to perform operations on the HTML
		$sk=$wgUser->getSkin();

		# Get all headlines for numbering them and adding funky stuff like [edit]
		# links
		preg_match_all("/<H([1-6])(.*?>)(.*?)<\/H[1-6]>/i",$text,$matches);
		
		# headline counter
		$c=0;

		# Ugh .. the TOC should have neat indentation levels which can be
		# passed to the skin functions. These are determined here
		foreach($matches[3] as $headline) {
			if($level) { $prevlevel=$level;}
			$level=$matches[1][$c];
			if(($nh||$st) && $prevlevel && $level>$prevlevel) { 
							
				$h[$level]=0; // reset when we enter a new level				
				$toc.=$sk->tocIndent($level-$prevlevel);
				$toclevel+=$level-$prevlevel;
			
			} 
			if(($nh||$st) && $level<$prevlevel) {
				$h[$level+1]=0; // reset when we step back a level
				$toc.=$sk->tocUnindent($prevlevel-$level);
				$toclevel-=$prevlevel-$level;

			}
			$h[$level]++; // count number of headlines for each level
			
			if($nh||$st) {
				for($i=1;$i<=$level;$i++) {
					if($h[$i]) {
						if($dot) {$numbering.=".";}
						$numbering.=$h[$i];
						$dot=1;					
					}
				}
			}

			// The canonized header is a version of the header text safe to use for links
			
			$canonized_headline=preg_replace("/<.*?>/","",$headline); // strip out HTML
			$tocline = trim( $canonized_headline );
			$canonized_headline=str_replace('"',"",$canonized_headline);
			$canonized_headline=str_replace(" ","_",trim($canonized_headline));			
			$refer[$c]=$canonized_headline;
			$refers[$canonized_headline]++;  // count how many in assoc. array so we can track dupes in anchors
			$refcount[$c]=$refers[$canonized_headline];

            // Prepend the number to the heading text
			
			if($nh||$st) {
				$tocline=$numbering ." ". $tocline;
				
				// Don't number the heading if it is the only one (looks silly)
				if($nh && count($matches[3]) > 1) {
					$headline=$numbering . " " . $headline; // the two are different if the line contains a link
				}
			}
			
			// Create the anchor for linking from the TOC to the section
			
			$anchor=$canonized_headline;
			if($refcount[$c]>1) {$anchor.="_".$refcount[$c];}
			if($st) {
				$toc.=$sk->tocLine($anchor,$tocline,$toclevel);
			}
			if($es && !isset($wpPreview)) {
				$head[$c].=$sk->editSectionLink($c+1);
			}
			
			// Put it all together
			
			$head[$c].="<h".$level.$matches[2][$c]
			 ."<a name=\"".$anchor."\">"
			 .$headline
			 ."</a>"
			 ."</h".$level.">";
			
			// Add the edit section link
			
			if($esr && !isset($wpPreview)) {
				$head[$c]=$sk->editSectionScript($c+1,$head[$c]);	
			}
			
			$numbering="";
			$c++;
			$dot=0;
		}		

		if($st) {
			$toclines=$c;
			$toc.=$sk->tocUnindent($toclevel);
			$toc=$sk->tocTable($toc);
		}

		// split up and insert constructed headlines
		
		$blocks=preg_split("/<H[1-6].*?>.*?<\/H[1-6]>/i",$text);
		$i=0;

		foreach($blocks as $block) {
			if(($es) && !isset($wpPreview) && $c>0 && $i==0) {
			    # This is the [edit] link that appears for the top block of text when 
				# section editing is enabled
				$full.=$sk->editSectionLink(0);
			}
			$full.=$block;
			if($st && $toclines>3 && !$i) {
				# Let's add a top anchor just in case we want to link to the top of the page
				$full="<a name=\"top\"></a>".$full.$toc;
			}

			$full.=$head[$i];
			$i++;
		}
		
		return $full;
	}

	/* private */ function magicISBN( $text )
	{
		global $wgLang;

		$a = split( "ISBN ", " $text" );
		if ( count ( $a ) < 2 ) return $text;
		$text = substr( array_shift( $a ), 1);
		$valid = "0123456789-ABCDEFGHIJKLMNOPQRSTUVWXYZ";

		foreach ( $a as $x ) {
			$isbn = $blank = "" ;
			while ( " " == $x{0} ) {
				$blank .= " ";
				$x = substr( $x, 1 );
			}
            		while ( strstr( $valid, $x{0} ) != false ) {
				$isbn .= $x{0};
				$x = substr( $x, 1 );
			}
			$num = str_replace( "-", "", $isbn );
			$num = str_replace( " ", "", $num );

			if ( "" == $num ) {
				$text .= "ISBN $blank$x";
			} else {
				$text .= "<a href=\"" . wfLocalUrlE( $wgLang->specialPage(
				  "Booksources"), "isbn={$num}" ) . "\" class=\"internal\">ISBN $isbn</a>";
				$text .= $x;
			}
		}
		return $text;
	}

	/* private */ function magicRFC( $text )
	{
		return $text;
	}


}

class ParserOutput
{
	var $mText, $mLanguageLinks, $mCategoryLinks, $mContainsOldMagic;

	function ParserOutput( $text = "", $languageLinks = array(), $categoryLinks = array(),
		$containsOldMagic = false )
	{
		$this->mText = $text;
		$this->mLanguageLinks = $languageLinks;
		$this->mCategoryLinks = $categoryLinks;
		$this->mContainsOldMagic = $containsOldMagic;
	}

	function getText() { return $this->mText; }
	function getLanguageLinks() { return $this->mLanguageLinks; }
	function getCategoryLinks() { return $this->mCategoryLinks; }
	function containsOldMagic() { return $this->mContainsOldMagic; }
	function setText( $text ) { return wfSetVar( $this->mText, $text ); }
	function setLanguageLinks( $ll ) { return wfSetVar( $this->mLanguageLinks, $ll ); }
	function setCategoryLinks( $cl ) { return wfSetVar( $this->mCategoryLinks, $cl ); }
	function setContainsOldMagic( $com ) { return wfSetVar( $this->mContainsOldMagic, $com ); }
}

# Regex callbacks, used in OutputPage::replaceVariables

# Just get rid of the dangerous stuff
# Necessary because replaceVariables is called after removeHTMLtags, 
# and message text can come from any user
function wfReplaceMsgVar( $matches ) {
	global $wgCurOut, $wgLinkCache;
	$text = $wgCurOut->removeHTMLtags( wfMsg( $matches[1] ) );
	$wgLinkCache->suspend();
	$text = $wgCurOut->replaceInternalLinks( $text );
	$wgLinkCache->resume();
	$wgLinkCache->addLinkObj( Title::makeTitle( NS_MEDIAWIKI, $matches[1] ) );
	return $text;
}

# Effective <nowiki></nowiki>
# Not real <nowiki> because this is called after nowiki sections are processed
function wfReplaceMsgnwVar( $matches ) {
	global $wgCurOut, $wgLinkCache;
	$text = wfEscapeWikiText( wfMsg( $matches[1] ) );
	$wgLinkCache->addLinkObj( Title::makeTitle( NS_MEDIAWIKI, $matches[1] ) );
	return $text;
}



?>
