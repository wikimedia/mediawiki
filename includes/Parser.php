<?php

include_once('Tokenizer.php');

if( $GLOBALS['wgUseWikiHiero'] ){
	include_once('wikihiero.php');
}

# PHP Parser 
# 
# Processes wiki markup
#
# There are two main entry points into the Parser class: parse() and preSaveTransform(). 
# The parse() function produces HTML output, preSaveTransform() produces altered wiki markup.
#
# Globals used: 
#    objects:   $wgLang, $wgDateFormatter, $wgLinkCache, $wgCurParser
#
# NOT $wgArticle, $wgUser or $wgTitle. Keep them away!
#
#    settings:  $wgUseTex*, $wgUseCategoryMagic*, $wgUseDynamicDates*, $wgInterwikiMagic*,
#               $wgNamespacesWithSubpages, $wgLanguageCode, $wgAllowExternalImages*, 
#               $wgLocaltimezone
#
#      * only within ParserOptions
#
#
#----------------------------------------
#    Variable substitution O(N^2) attack
#-----------------------------------------
# Without countermeasures, it would be possible to attack the parser by saving a page
# filled with a large number of inclusions of large pages. The size of the generated 
# page would be proportional to the square of the input size. Hence, we limit the number 
# of inclusions of any given page, thus bringing any attack back to O(N).
#
define( "MAX_INCLUDE_REPEAT", 5 );

# Recursion depth of variable/inclusion evaluation
define( "MAX_INCLUDE_PASSES", 3 );

# Allowed values for $mOutputType
define( "OT_HTML", 1 );
define( "OT_WIKI", 2 );
define( "OT_MSG", 3 );

# prefix for escaping, used in two functions at least
define( "UNIQ_PREFIX", "NaodW29");

class Parser
{
	# Cleared with clearState():
	var $mOutput, $mAutonumber, $mLastSection, $mDTopen, $mStripState = array();
	var $mVariables, $mIncludeCount;

	# Temporary:
	var $mOptions, $mTitle, $mOutputType;

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
		$this->mVariables = false;
		$this->mIncludeCount = array();
		$this->mStripState = array();
	}
	
	# First pass--just handle <nowiki> sections, pass the rest off
	# to doWikiPass2() which does all the real work.
	#
	# Returns a ParserOutput
	#
	function parse( $text, &$title, $options, $linestart = true, $clearState = true )
	{
		$fname = "Parser::parse";
		wfProfileIn( $fname );

		if ( $clearState ) {
			$this->clearState();
		}
		
		$this->mOptions = $options;
		$this->mTitle =& $title;
		$this->mOutputType = OT_HTML;
		
		$stripState = NULL;
		$text = $this->strip( $text, $this->mStripState );
		$text = $this->doWikiPass2( $text, $linestart );
		$text = $this->unstrip( $text, $this->mStripState );
		
		$this->mOutput->setText( $text );
		wfProfileOut( $fname );
		return $this->mOutput;
	}

	/* static */ function getRandomString()
	{
		return dechex(mt_rand(0, 0x7fffffff)) . dechex(mt_rand(0, 0x7fffffff));
	}

	# Replaces all occurences of <$tag>content</$tag> in the text
	# with a random marker and returns the new text. the output parameter
	# $content will be an associative array filled with data on the form
	# $unique_marker => content.

	# If $content is already set, the additional entries will be appended

	/* static */ function extractTags($tag, $text, &$content, $uniq_prefix = ""){
		$rnd = $uniq_prefix . '-' . $tag . Parser::getRandomString();
		if ( !$content ) {
			$content = array( );
		}
		$n = 1;
		$stripped = "";

		while ( "" != $text ) {
			$p = preg_split( "/<\\s*$tag\\s*>/i", $text, 2 );
			$stripped .= $p[0];
			if ( ( count( $p ) < 2 ) || ( "" == $p[1] ) ) { 
				$text = ""; 
			} else {
				$q = preg_split( "/<\\/\\s*$tag\\s*>/i", $p[1], 2 );
				$marker = $rnd . sprintf("%08X", $n++);
				$content[$marker] = $q[0];
				$stripped .= $marker;
				$text = $q[1];
			}
		}
		return $stripped;
	}	

	# Strips <nowiki>, <pre> and <math>
	# Returns the text, and fills an array with data needed in unstrip()
	# If the $state is already a valid strip state, it adds to the state
	#
	function strip( $text, &$state )
	{
		$render = ($this->mOutputType == OT_HTML);
		if ( $state ) {
			$nowiki_content = $state['nowiki']; 
			$hiero_content = $state['hiero'];
			$math_content = $state['math'];
			$pre_content = $state['pre'];
			$item_content = $state['item'];
		} else {
			$nowiki_content = array(); 
			$hiero_content = array();
			$math_content = array();
			$pre_content = array();
			$item_content = array();
		}

		# Replace any instances of the placeholders
		$uniq_prefix = UNIQ_PREFIX;
		$text = str_replace( $uniq_prefix, wfHtmlEscapeFirst( $uniq_prefix ), $text );

		$text = Parser::extractTags("nowiki", $text, $nowiki_content, $uniq_prefix);
		foreach( $nowiki_content as $marker => $content ){
			if( $render ){
				$nowiki_content[$marker] = wfEscapeHTMLTagsOnly( $content );
			} else {
				$nowiki_content[$marker] = "<nowiki>$content</nowiki>";
			}
		}

		if( $GLOBALS['wgUseWikiHiero'] ){
			$text = Parser::extractTags("hiero", $text, $hiero_content, $uniq_prefix);
			foreach( $hiero_content as $marker => $content ){
				if( $render ){
					$hiero_content[$marker] = WikiHiero( $content, WH_MODE_HTML);
				} else {
					$hiero_content[$marker] = "<hiero>$content</hiero>";
				}
			}
		}

		if( $this->mOptions->getUseTeX() ){
			$text = Parser::extractTags("math", $text, $math_content, $uniq_prefix);
			foreach( $math_content as $marker => $content ){
				if( $render ){
					$math_content[$marker] = renderMath( $content );
				} else {
					$math_content[$marker] = "<math>$content</math>";
				}
			}
		}

		$text = Parser::extractTags("pre", $text, $pre_content, $uniq_prefix);
		foreach( $pre_content as $marker => $content ){
			if( $render ){
				$pre_content[$marker] = "<pre>" . wfEscapeHTMLTagsOnly( $content ) . "</pre>";
			} else {
				$pre_content[$marker] = "<pre>$content</pre>";
			}
		}
		
		$state = array( 
		  'nowiki' => $nowiki_content,
		  'hiero' => $hiero_content,
		  'math' => $math_content, 
		  'pre' => $pre_content, 
		  'item' => $item_content
		);
		return $text;
	}

	function unstrip( $text, &$state )
	{
		# Must expand in reverse order, otherwise nested tags will be corrupted
		/*
		$dicts = array( 'item', 'pre', 'math', 'hiero', 'nowiki' );
		foreach ( $dicts as $dictName ) {
			$content_dict = $state[$dictName];
			foreach( $content_dict as $marker => $content ){
				$text = str_replace( $marker, $content, $text );
			}
		}*/

		$contentDict = end( $state );
		for ( $contentDict = end( $state ); $contentDict !== false; $contentDict = prev( $state ) ) { 
			for ( $content = end( $contentDict ); $content !== false; $content = prev( $contentDict ) ) {
				$text = str_replace( key( $contentDict ), $content, $text );
			}
		}

		return $text;
	}
	
	# Add an item to the strip state
	# Returns the unique tag which must be inserted into the stripped text
	# The tag will be replaced with the original text in unstrip()

	function insertStripItem( $text, &$state )
	{
		$rnd = UNIQ_PREFIX . '-item' . Parser::getRandomString();
		if ( !$state ) {
			$state = array( 
			  'nowiki' => array(),
			  'hiero' => array(),
			  'math' => array(),
			  'pre' => array(),
			  'item' => array()
			);
		}
		$state['item'][$rnd] = $text;
		return $rnd;
	}
		
	function categoryMagic ()
	{
		global $wgLang , $wgUser ;
		if ( !$this->mOptions->getUseCategoryMagic() ) return ;
		$id = $this->mTitle->getArticleID() ;
		$cat = $wgLang->ucfirst ( wfMsg ( "category" ) ) ;
		$ti = $this->mTitle->getText() ;
		$ti = explode ( ":" , $ti , 2 ) ;
		if ( $cat != $ti[0] ) return "" ;
		$r = '<br style="clear:both;"/>\n';

		$articles = array() ;
		$parents = array () ;
		$children = array() ;


#		$sk =& $this->mGetSkin();
		$sk =& $wgUser->getSkin() ;

		$data = array () ;
		$sql1 = "SELECT DISTINCT cur_title,cur_namespace FROM cur,links WHERE l_to={$id} AND l_from=cur_id";
		$sql2 = "SELECT DISTINCT cur_title,cur_namespace FROM cur,brokenlinks WHERE bl_to={$id} AND bl_from=cur_id" ;

		$res = wfQuery ( $sql1, DB_READ ) ;
		while ( $x = wfFetchObject ( $res ) ) $data[] = $x ;

		$res = wfQuery ( $sql2, DB_READ ) ;
		while ( $x = wfFetchObject ( $res ) ) $data[] = $x ;


		foreach ( $data AS $x )
		{
			$t = $wgLang->getNsText ( $x->cur_namespace ) ;
			if ( $t != "" ) $t .= ":" ;
			$t .= $x->cur_title ;

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
					$t[$k] = "\n<table " . $this->fixTagAttributes ( substr ( $x , 3 ) ) . ">" ;
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
						if ( $fc == "|" ) $l = "td" ;
						else if ( $fc == "!" ) $l = "th" ;
						else if ( $fc == "+" ) $l = "caption" ;
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
		$fname = "Parser::doWikiPass2";
		wfProfileIn( $fname );
		
		$text = $this->removeHTMLtags( $text );
		$text = $this->replaceVariables( $text );

		# $text = preg_replace( "/(^|\n)-----*/", "\\1<hr>", $text );

		$text = $this->doHeadings( $text );
		
		if($this->mOptions->getUseDynamicDates()) {
			global $wgDateFormatter;
			$text = $wgDateFormatter->reformat( $this->mOptions->getDateFormat(), $text );
		}

		$text = $this->replaceExternalLinks( $text );
		$text = $this->doTokenizedParser ( $text );

		$text = $this->doTableStuff ( $text ) ;

		$text = $this->formatHeadings( $text );

		$sk =& $this->mOptions->getSkin();
		$text = $sk->transformContent( $text );
		$fixtags = array(
			"/<hr *>/i" => '<hr/>',
			"/<br *>/i" => '<br/>', 
			"/<center *>/i"=>'<span style="text-align:center;">',
			"/<\\/center *>/i" => '</span>'
		);
		$text = preg_replace( array_keys($fixtags), array_values($fixtags), $text );
		// another round, but without regex
		$fixtags = array(
			'& ' => '&amp;',
			'&<' => '&amp;<',
		);
		$text = str_replace( array_keys($fixtags), array_values($fixtags), $text );

		$text .= $this->categoryMagic () ;
		
		# needs to be called last
		$text = $this->doBlockLevels( $text, $linestart );		

		wfProfileOut( $fname );
		return $text;
	}


	/* private */ function doHeadings( $text )
	{
		for ( $i = 6; $i >= 1; --$i ) {
			$h = substr( "======", 0, $i );
			$text = preg_replace( "/^{$h}(.+){$h}(\\s|$)/m",
			  "<h{$i}>\\1</h{$i}>\\2", $text );
		}
		return $text;
	}

	# Note: we have to do external links before the internal ones,
	# and otherwise take great care in the order of things here, so
	# that we don't end up interpreting some URLs twice.

	/* private */ function replaceExternalLinks( $text )
	{
		$fname = "Parser::replaceExternalLinks";
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
		$sk =& $this->mOptions->getSkin();

		if ( $autonumber and $this->mOptions->getAllowExternalImages() ) { # Use img tags only for HTTP urls
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
			if( $link == $text || preg_match( "!$protocol://" . preg_quote( $text, "/" ) . "/?$!", $link ) ) {
				$paren = "";
			} else {
				# Expand the URL for printable version
				$paren = "<span class='urlexpansion'> (<i>" . htmlspecialchars ( $link ) . "</i>)</span>";
			}
			$la = $sk->getExternalLinkAttributes( $link, $text );
			$s .= "<a href='{$link}'{$la}>{$text}</a>{$paren}{$trail}";

		}
		return $s;
	}

	/* private */ function handle3Quotes( &$state, $token )
	{
		if ( $state["strong"] !== false ) {
			if ( $state["em"] !== false && $state["em"] > $state["strong"] )
			{
				# ''' lala ''lala '''
				$s = "</em></strong><em>";
			} else {
				$s = "</strong>";
			}
			$state["strong"] = FALSE;
		} else {
			$s = "<strong>";
			$state["strong"] = $token["pos"];
		}
		return $s;
	}

	/* private */ function handle2Quotes( &$state, $token )
	{
		if ( $state["em"] !== false ) {
			if ( $state["strong"] !== false && $state["strong"] > $state["em"] )
			{
				# ''lala'''lala'' ....'''
				$s = "</strong></em><strong>";
			} else {
				$s = "</em>";
			}
			$state["em"] = FALSE;
		} else {
			$s = "<em>";
			$state["em"] = $token["pos"];
		}
		return $s;
	}
	
	/* private */ function handle5Quotes( &$state, $token )
	{
		$s = "";
		if ( $state["em"] !== false && $state["strong"] !== false ) {
			if ( $state["em"] < $state["strong"] ) {
				$s .= "</strong></em>";
			} else {
				$s .= "</em></strong>";
			}
			$state["strong"] = $state["em"] = FALSE;
		} elseif ( $state["em"] !== false ) {
			$s .= "</em><strong>";
			$state["em"] = FALSE;
			$state["strong"] = $token["pos"];
		} elseif ( $state["strong"] !== false ) {
			$s .= "</strong><em>";
			$state["strong"] = FALSE;
			$state["em"] = $token["pos"];
		} else { # not $em and not $strong
			$s .= "<strong><em>";
			$state["strong"] = $state["em"] = $token["pos"];
		}
		return $s;
	}

	/* private */ function doTokenizedParser( $str )
	{
		global $wgLang;	# for language specific parser hook

		$tokenizer=Tokenizer::newFromString( $str );
		$tokenStack = array();
		
		$s="";
		$state["em"]      = FALSE;
		$state["strong"]  = FALSE;
		$tagIsOpen = FALSE;
		$threeopen = false;
		
		# The tokenizer splits the text into tokens and returns them one by one.
		# Every call to the tokenizer returns a new token.
		while ( $token = $tokenizer->nextToken() )
		{
			switch ( $token["type"] )
			{
				case "text":
					# simple text with no further markup
					$txt = $token["text"];
					break;
				case "[[[":
					# remember the tag opened with 3 [
					$threeopen = true;
				case "[[":
					# link opening tag.
					# FIXME : Treat orphaned open tags (stack not empty when text is over)
					$tagIsOpen = TRUE;
					array_push( $tokenStack, $token );
					$txt="";
					break;
					
				case "]]]":
				case "]]":
					# link close tag.
					# get text from stack, glue it together, and call the code to handle a
					# link
					
					if ( count( $tokenStack ) == 0 )
					{
						# stack empty. Found a ]] without an opening [[
						$txt = "]]";
					} else {
						$linkText = "";
						$lastToken = array_pop( $tokenStack );
						while ( !(($lastToken["type"] == "[[[") or ($lastToken["type"] == "[[")) )
						{
							if( !empty( $lastToken["text"] ) ) {
								$linkText = $lastToken["text"] . $linkText;
							}
							$lastToken = array_pop( $tokenStack );
						}
						
						$txt = $linkText ."]]";
						
						if( isset( $lastToken["text"] ) ) {
							$prefix = $lastToken["text"];
						} else {
							$prefix = "";
						}
						$nextToken = $tokenizer->previewToken();
						if ( $nextToken["type"] == "text" ) 
						{
							# Preview just looks at it. Now we have to fetch it.
							$nextToken = $tokenizer->nextToken();
							$txt .= $nextToken["text"];
						}
						$fakestate = $this->mStripState;
						$txt = $this->handleInternalLink( $this->unstrip($txt,$fakestate), $prefix );

						# did the tag start with 3 [ ?						
						if($threeopen) {
							# show the first as text
							$txt = "[".$txt;
							$threeopen=false;
						}
				
					}
					$tagIsOpen = (count( $tokenStack ) != 0);
					break;
				case "----":
					$txt = "\n<hr />\n";
					break;
				case "'''":
					# This and the three next ones handle quotes
					$txt = $this->handle3Quotes( $state, $token );
					break;
				case "''":
					$txt = $this->handle2Quotes( $state, $token );
					break;
				case "'''''":
					$txt = $this->handle5Quotes( $state, $token );
					break;
				case "":
					# empty token
					$txt="";
					break;
				case "RFC ":
					if ( $tagIsOpen ) {
						$txt = "RFC ";
					} else {
						$txt = $this->doMagicRFC( $tokenizer );
					}
					break;
				case "ISBN ":
					if ( $tagIsOpen ) {
						$txt = "ISBN ";
					} else {
						$txt = $this->doMagicISBN( $tokenizer );
					}
					break;
				default:
					# Call language specific Hook.
					$txt = $wgLang->processToken( $token, $tokenStack );
					if ( NULL == $txt ) {
						# An unkown token. Highlight.
						$txt = "<font color=\"#FF0000\"><b>".$token["type"]."</b></font>";
						$txt .= "<font color=\"#FFFF00\"><b>".$token["text"]."</b></font>";
					}
					break;
			}
			# If we're parsing the interior of a link, don't append the interior to $s,
			# but push it to the stack so it can be processed when a ]] token is found.
			if ( $tagIsOpen  && $txt != "" ) {
				$token["type"] = "text";
				$token["text"] = $txt;
				array_push( $tokenStack, $token );
			} else {
				$s .= $txt;
			}
		} #end while
		if ( count( $tokenStack ) != 0 )
		{
			# still objects on stack. opened [[ tag without closing ]] tag.
			$txt = "";
			while ( $lastToken = array_pop( $tokenStack ) )
			{
				if ( $lastToken["type"] == "text" )
				{
					$txt = $lastToken["text"] . $txt;
				} else {
					$txt = $lastToken["type"] . $txt;
				}	
			}
			$s .= $txt;
		}
		return $s;
	}

	/* private */ function handleInternalLink( $line, $prefix )
	{
		global $wgLang, $wgLinkCache;
		global $wgNamespacesWithSubpages, $wgLanguageCode;
		static $fname = "Parser::handleInternalLink" ;
		wfProfileIn( $fname );

		wfProfileIn( "$fname-setup" );
		static $tc = FALSE;
		if ( !$tc ) { $tc = Title::legalChars() . "#"; }
		$sk =& $this->mOptions->getSkin();

		# Match a link having the form [[namespace:link|alternate]]trail
		static $e1 = FALSE;
		if ( !$e1 ) { $e1 = "/^([{$tc}]+)(?:\\|([^]]+))?]](.*)\$/sD"; }
		# Match the end of a line for a word that's not followed by whitespace,
		# e.g. in the case of 'The Arab al[[Razi]]', 'al' will be matched
		#$e2 = "/^(.*)\\b(\\w+)\$/suD";
		#$e2 = "/^(.*\\s)(\\S+)\$/suD";
		static $e2 = '/^(.*\s)([a-zA-Z\x80-\xff]+)$/sD';
		

		# Special and Media are pseudo-namespaces; no pages actually exist in them
		static $image = FALSE;
		static $special = FALSE;
		static $media = FALSE;
		static $category = FALSE;
		if ( !$image ) { $image = Namespace::getImage(); }
		if ( !$special ) { $special = Namespace::getSpecial(); }
		if ( !$media ) { $media = Namespace::getMedia(); }
		if ( !$category ) { $category = wfMsg ( "category" ) ; }
		
		$nottalk = !Namespace::isTalk( $this->mTitle->getNamespace() );

		wfProfileOut( "$fname-setup" );
		$s = "";
		
		if ( preg_match( $e1, $line, $m ) ) { # page with normal text or alt
			$text = $m[2];
			$trail = $m[3];				
		} else { # Invalid form; output directly
			$s .= $prefix . "[[" . $line ;
			return $s;
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
			if($wgNamespacesWithSubpages[$this->mTitle->getNamespace()]) { # subpages allowed here
				$link = $this->mTitle->getPrefixedText(). "/" . trim($noslash);
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
			return $s;
		}
		$ns = $nt->getNamespace();
		$iw = $nt->getInterWiki();
		if( $noforce ) {
			if( $iw && $this->mOptions->getInterwikiMagic() && $nottalk && $wgLang->getLanguageName( $iw ) ) {
				array_push( $this->mOutput->mLanguageLinks, $nt->getPrefixedText() );
				return (trim($s) == '')? '': $s;
			}
			if( $ns == $image ) {
				$s .= $prefix . $sk->makeImageLinkObj( $nt, $text ) . $trail;
				$wgLinkCache->addImageLinkObj( $nt );
				return $s;
			}
		}
		if( ( $nt->getPrefixedText() == $this->mTitle->getPrefixedText() ) &&
		    ( strpos( $link, "#" ) == FALSE ) ) {
			$s .= $prefix . "<strong>" . $text . "</strong>" . $trail;
			return $s;
		}

		# Category feature
		$catns = strtoupper ( $nt->getDBkey () ) ;
		$catns = explode ( ":" , $catns ) ;
		if ( count ( $catns ) > 1 ) $catns = array_shift ( $catns ) ;
		else $catns = "" ;
 		if ( $catns == strtoupper($category) && $this->mOptions->getUseCategoryMagic() ) {
		  	$t = explode ( ":" , $nt->getText() ) ;
 			array_shift ( $t ) ;
 			$t = implode ( ":" , $t ) ;
 			$t = $wgLang->ucFirst ( $t ) ;
			$nnt = Title::newFromText ( $category.":".$t ) ;
			$t = $sk->makeLinkObj( $nnt, $t, "", $trail , $prefix );
 			$this->mOutput->mCategoryLinks[] = $t ;
 			$s .= $prefix . $trail ;
			return $s ;
		}

		if( $ns == $media ) {
			$s .= $prefix . $sk->makeMediaLinkObj( $nt, $text ) . $trail;
			$wgLinkCache->addImageLinkObj( $nt );
			return $s;
		} elseif( $ns == $special ) {
			$s .= $prefix . $sk->makeKnownLinkObj( $nt, $text, "", $trail );
			return $s;
		}
		$s .= $sk->makeLinkObj( $nt, $text, "", $trail , $prefix );

		wfProfileOut( $fname );
		return $s;
	}

	# Some functions here used by doBlockLevels()
	#
	/* private */ function closeParagraph()
	{
		$result = "";
		if ( '' != $this->mLastSection ) {
			$result = "</" . $this->mLastSection  . ">\n";
		}
		$this->mLastSection = "";
		return $result;
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
		$fname = "Parser::doBlockLevels";
		wfProfileIn( $fname );
		# Parsing through the text line by line.  The main thing
		# happening here is handling of block-level elements p, pre,
		# and making lists from lines starting with * # : etc.
		#
		$a = explode( "\n", $text );
		$lastPref = $text = $lastLine = '';
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
				$uniq_prefix = UNIQ_PREFIX;
				// XXX: use a stack for nestable elements like span, table and div
				$openmatch = preg_match("/(<table|<blockquote|<h1|<h2|<h3|<h4|<h5|<h6|<div|<pre|<tr|<td|<p)/i", $t );
				$closematch = preg_match( 
					"/(<\\/table|<\\/blockquote|<\\/h1|<\\/h2|<\\/h3|<\\/h4|<\\/h5|<\\/h6|".
					"<\\/div|<hr|<\\/td|<\\/pre|<\\/p|".$uniq_prefix."-pre)/i", $t );
				if ( $openmatch or $closematch ) {
					$text .= $this->closeParagraph();
					if ( $closematch  ) {
						$inBlockElem = false;
					} else {
						$inBlockElem = true;
					}
				} else if ( !$inBlockElem ) {
					if ( " " == $t{0} ) {
						$newSection = "pre";
						$text .= $this->closeParagraph();
						$text .= "<" . $newSection . ">";
						$this->mLastSection = $newSection;
					} else { 
						$newSection = "p";
						if ( '' == trim($t) ) {
							if ( '' == trim($lastLine) ) {
								$text .= $this->closeParagraph();
								$text .= "<" . $newSection . "><br/>";
								$this->mLastSection = $newSection;
							} else {
								$t = '';
							}
						} else if ($this->mLastSection != $newSection or $newSection != 'p') {
							$text .= $this->closeParagraph();
							$text .= "<" . $newSection . ">";
							$this->mLastSection = $newSection;
						}			
					}

				} 
			}
			$lastLine = $t;
			$text .= $t;
		}
		while ( $npl ) {
			$text .= $this->closeList( $pref2{$npl-1} );
			--$npl;
		}
		if ( "" != $this->mLastSection ) {
			$text .= "</" . $this->mLastSection . ">";
			$this->mLastSection = "";
		}
		wfProfileOut( $fname );
		return $text;
	}

	function getVariableValue( $index ) {
		global $wgLang, $wgSitename, $wgServer;

		switch ( $index ) {
			case MAG_CURRENTMONTH:
				return date( "m" );
			case MAG_CURRENTMONTHNAME:
				return $wgLang->getMonthName( date("n") );
			case MAG_CURRENTMONTHNAMEGEN:
				return $wgLang->getMonthNameGen( date("n") );
			case MAG_CURRENTDAY:
				return date("j");
			case MAG_CURRENTDAYNAME:
				return $wgLang->getWeekdayName( date("w")+1 );
			case MAG_CURRENTYEAR:
				return date( "Y" );
			case MAG_CURRENTTIME:
				return $wgLang->time( wfTimestampNow(), false );
			case MAG_NUMBEROFARTICLES:
				return wfNumberOfArticles();
			case MAG_SITENAME:
				return $wgSitename;
			case MAG_SERVER:
				return $wgServer;
			default:
				return NULL;
		}
	}

	function initialiseVariables()
	{
		global $wgVariableIDs;
		$this->mVariables = array();
		foreach ( $wgVariableIDs as $id ) {
			$mw =& MagicWord::get( $id );
			$mw->addToArray( $this->mVariables, $this->getVariableValue( $id ) );
		}
	}

	/* private */ function replaceVariables( $text )
	{
		global $wgLang, $wgCurParser;
		global $wgScript, $wgArticlePath;

		$fname = "Parser::replaceVariables";
		wfProfileIn( $fname );
		
		$bail = false;
		if ( !$this->mVariables ) {
			$this->initialiseVariables();
		}
		$titleChars = Title::legalChars();
		$regex = "/{{([$titleChars\\|]*?)}}/s";

		# "Recursive" variable expansion: run it through a couple of passes
		for ( $i=0; $i<MAX_INCLUDE_REPEAT && !$bail; $i++ ) {
			$oldText = $text;
			
			# It's impossible to rebind a global in PHP
			# Instead, we run the substitution on a copy, then merge the changed fields back in
			$wgCurParser = $this->fork();

			$text = preg_replace_callback( $regex, "wfBraceSubstitution", $text );
			if ( $oldText == $text ) {
				$bail = true;
			}
			$this->merge( $wgCurParser );
		}

		return $text;
	}

	# Returns a copy of this object except with various variables cleared
	# This copy can be re-merged with the parent after operations on the copy
	function fork()
	{
		$copy = $this;
		$copy->mOutput = new ParserOutput;
		return $copy;
	}

	# Merges a copy split off with fork()
	function merge( &$copy )
	{
		# Output objects
		$this->mOutput->merge( $copy->mOutput );
		
		# Include throttling arrays
		foreach( $copy->mIncludeCount as $dbk => $count ) {
			if ( array_key_exists( $dbk, $this->mIncludeCount ) ) {
				$this->mIncludeCount[$dbk] += $count;
			} else {
				$this->mIncludeCount[$dbk] = $count;
			}
		}

		# Strip states
		foreach( $copy->mStripState as $dictName => $contentDict ) {
			$this->mStripState[$dictName] += $contentDict;
		}
	}

	function braceSubstitution( $matches )
	{
		global $wgLinkCache, $wgLang;
		$fname = "Parser::braceSubstitution";
		$found = false;
		$nowiki = false;
	
		$text = $matches[1];

		# SUBST
		$mwSubst =& MagicWord::get( MAG_SUBST );
		if ( $mwSubst->matchStartAndRemove( $text ) ) {
			if ( $this->mOutputType != OT_WIKI ) {
				# Invalid SUBST not replaced at PST time
				# Return without further processing
				$text = $matches[0];
				$found = true;
			}
		} elseif ( $this->mOutputType == OT_WIKI ) {
			# SUBST not found in PST pass, do nothing
			$text = $matches[0];
			$found = true;
		}
		
		# MSG, MSGNW and INT
		if ( !$found ) {
			# Check for MSGNW:
			$mwMsgnw =& MagicWord::get( MAG_MSGNW );
			if ( $mwMsgnw->matchStartAndRemove( $text ) ) {
				$nowiki = true;
			} else {
				# Remove obsolete MSG:
				$mwMsg =& MagicWord::get( MAG_MSG );
				$mwMsg->matchStartAndRemove( $text );
			}
			
			# Check if it is an internal message
			$mwInt =& MagicWord::get( MAG_INT );
			if ( $mwInt->matchStartAndRemove( $text ) ) {
				$text = wfMsg( $text );
				$found = true;
			}
		}
	
		# NS
		if ( !$found ) {
			# Check for NS: (namespace expansion)
			$mwNs = MagicWord::get( MAG_NS );
			if ( $mwNs->matchStartAndRemove( $text ) ) {
				if ( intval( $text ) ) {
					$text = $wgLang->getNsText( intval( $text ) );
					$found = true;
				} else {
					$index = Namespace::getCanonicalIndex( strtolower( $text ) );
					if ( !is_null( $index ) ) {
						$text = $wgLang->getNsText( $index );
						$found = true;
					}
				}
			}
		}
		
		# LOCALURL and LOCALURLE
		if ( !$found ) {
			$mwLocal = MagicWord::get( MAG_LOCALURL );
			$mwLocalE = MagicWord::get( MAG_LOCALURLE );

			if ( $mwLocal->matchStartAndRemove( $text ) ) {
				$func = 'getLocalURL';
			} elseif ( $mwLocalE->matchStartAndRemove( $text ) ) {
				$func = 'escapeLocalURL';
			} else {
				$func = '';
			}
			
			if ( $func !== '' ) {
				$args = explode( "|", $text );
				$n = count( $args );
				if ( $n > 0 ) {
					$title = Title::newFromText( $args[0] );
					if ( !is_null( $title ) ) {
						if ( $n > 1 ) {
							$text = $title->$func( $args[1] );
						} else {
							$text = $title->$func();
						}
						$found = true;
					}
				}
			}	
		}
		
		# Check for a match against internal variables
		if ( !$found && array_key_exists( $text, $this->mVariables ) ) {
			$text = $this->mVariables[$text];
			$found = true;
			$this->mOutput->mContainsOldMagic = true;
		} 
		
		# Load from database
		if ( !$found ) {
			$title = Title::newFromText( $text, NS_TEMPLATE );
			if ( is_object( $title ) && !$title->isExternal() ) {
				# Check for excessive inclusion
				$dbk = $title->getPrefixedDBkey();
				if ( !array_key_exists( $dbk, $this->mIncludeCount ) ) {
					$this->mIncludeCount[$dbk] = 0;
				}
				if ( ++$this->mIncludeCount[$dbk] <= MAX_INCLUDE_REPEAT ) {
					$article = new Article( $title );
					$articleContent = $article->getContentWithoutUsingSoManyDamnGlobals();
					if ( $articleContent !== false ) {
						$found = true;
						$text = $articleContent;
						
						# Escaping and link table handling
						# Not required for preSaveTransform()
						if ( $this->mOutputType == OT_HTML ) {
							if ( $nowiki ) {
								$text = wfEscapeWikiText( $text );
							} else {
								$text = $this->removeHTMLtags( $text );
							}
							# Do not enter included links in link table
							$wgLinkCache->suspend();

							# Run full parser on the included text
							$text = $this->strip( $text, $this->mStripState );
							$text = $this->doWikiPass2( $text, true );
							
							# Add the result to the strip state for re-inclusion after 
							# the rest of the processing
							$text = $this->insertStripItem( $text, $this->mStripState );
							
							# Resume the link cache and register the inclusion as a link
							$wgLinkCache->resume();
							$wgLinkCache->addLinkObj( $title );

						}
					} 
				} 

				# If the title is valid but undisplayable, make a link to it
				if ( $this->mOutputType == OT_HTML && !$found ) {
					$text = "[[" . $title->getPrefixedText() . "]]";
					$found = true;
				}
			}
		}

		if ( !$found ) {
			return $matches[0];
		} else {
			return $text;
		}
	}

	# Cleans up HTML, removes dangerous tags and attributes
	/* private */ function removeHTMLtags( $text )
	{
		$fname = "Parser::removeHTMLtags";
		wfProfileIn( $fname );
		$htmlpairs = array( # Tags that must be closed
			"b", "i", "u", "font", "big", "small", "sub", "sup", "h1",
			"h2", "h3", "h4", "h5", "h6", "cite", "code", "em", "s",
			"strike", "strong", "tt", "var", "div", "center",
			"blockquote", "ol", "ul", "dl", "table", "caption", "pre",
			"ruby", "rt" , "rb" , "rp", "p"
		);
		$htmlsingle = array(
			"br", "hr", "li", "dt", "dd"
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
 */

	/* private */ function formatHeadings( $text )
	{
		$doNumberHeadings = $this->mOptions->getNumberHeadings();
		$doShowToc = $this->mOptions->getShowToc();
		if( !$this->mTitle->userCanEdit() ) {
			$showEditLink = 0;
			$rightClickHack = 0;
		} else {
			$showEditLink = $this->mOptions->getEditSection();
			$rightClickHack = $this->mOptions->getEditSectionOnRightClick();
		}

		# Inhibit editsection links if requested in the page
		$esw =& MagicWord::get( MAG_NOEDITSECTION );
		if( $esw->matchAndRemove( $text ) ) {
			$showEditLink = 0;
		}
		# if the string __NOTOC__ (not case-sensitive) occurs in the HTML, 
		# do not add TOC
		$mw =& MagicWord::get( MAG_NOTOC );
		if( $mw->matchAndRemove( $text ) ) {
			$doShowToc = 0;
		}

		# never add the TOC to the Main Page. This is an entry page that should not
		# be more than 1-2 screens large anyway
		if( $this->mTitle->getPrefixedText() == wfMsg("mainpage") ) {
			$doShowToc = 0;
		}

		# Get all headlines for numbering them and adding funky stuff like [edit]
		# links - this is for later, but we need the number of headlines right now
		$numMatches = preg_match_all( "/<H([1-6])(.*?" . ">)(.*?)<\/H[1-6]>/i", $text, $matches );

		# if there are fewer than 4 headlines in the article, do not show TOC
		if( $numMatches < 4 ) {
			$doShowToc = 0;
		}

		# if the string __FORCETOC__ (not case-sensitive) occurs in the HTML,
		# override above conditions and always show TOC
		$mw =& MagicWord::get( MAG_FORCETOC );
		if ($mw->matchAndRemove( $text ) ) {
			$doShowToc = 1;
		}


		# We need this to perform operations on the HTML
		$sk =& $this->mOptions->getSkin();

		# headline counter
		$headlineCount = 0;

		# Ugh .. the TOC should have neat indentation levels which can be
		# passed to the skin functions. These are determined here
		$toclevel = 0;
		$toc = "";
		$full = "";
		$head = array();
		$sublevelCount = array();
		$level = 0;
		$prevlevel = 0;
		foreach( $matches[3] as $headline ) {
			$numbering = "";
			if( $level ) {
				$prevlevel = $level;
			}
			$level = $matches[1][$headlineCount];
			if( ( $doNumberHeadings || $doShowToc ) && $prevlevel && $level > $prevlevel ) { 
				# reset when we enter a new level
				$sublevelCount[$level] = 0;
				$toc .= $sk->tocIndent( $level - $prevlevel );
				$toclevel += $level - $prevlevel;
			} 
			if( ( $doNumberHeadings || $doShowToc ) && $level < $prevlevel ) {
				# reset when we step back a level
				$sublevelCount[$level+1]=0;
				$toc .= $sk->tocUnindent( $prevlevel - $level );
				$toclevel -= $prevlevel - $level;
			}
			# count number of headlines for each level
			@$sublevelCount[$level]++;
			if( $doNumberHeadings || $doShowToc ) {
				$dot = 0;
				for( $i = 1; $i <= $level; $i++ ) {
					if( !empty( $sublevelCount[$i] ) ) {
						if( $dot ) {
							$numbering .= ".";
						}
						$numbering .= $sublevelCount[$i];
						$dot = 1;					
					}
				}
			}

			# The canonized header is a version of the header text safe to use for links
			# Avoid insertion of weird stuff like <math> by expanding the relevant sections
			$canonized_headline = Parser::unstrip( $headline, $this->mStripState );
			
			# strip out HTML
			$canonized_headline = preg_replace( "/<.*?" . ">/","",$canonized_headline );
			$tocline = trim( $canonized_headline );	
			$canonized_headline = preg_replace("/[ &\\/<>\\(\\)\\[\\]=,+']+/", '_', html_entity_decode( $tocline));
			$refer[$headlineCount] = $canonized_headline;
			
			# count how many in assoc. array so we can track dupes in anchors
			@$refers[$canonized_headline]++;
			$refcount[$headlineCount]=$refers[$canonized_headline];

			# Prepend the number to the heading text
			
			if( $doNumberHeadings || $doShowToc ) {
				$tocline = $numbering . " " . $tocline;
				
				# Don't number the heading if it is the only one (looks silly)
				if( $doNumberHeadings && count( $matches[3] ) > 1) {
					# the two are different if the line contains a link
					$headline=$numbering . " " . $headline;
				}
			}
			
			# Create the anchor for linking from the TOC to the section
			$anchor = $canonized_headline;
			if($refcount[$headlineCount] > 1 ) {
				$anchor .= "_" . $refcount[$headlineCount];
			}
			if( $doShowToc ) {
				$toc .= $sk->tocLine($anchor,$tocline,$toclevel);
			}
			if( $showEditLink ) {
				if ( empty( $head[$headlineCount] ) ) {
					$head[$headlineCount] = "";
				}
				$head[$headlineCount] .= $sk->editSectionLink($headlineCount+1);
			}
				
			# Add the edit section span
			if( $rightClickHack ) {
				$headline = $sk->editSectionScript($headlineCount+1,$headline);	
			}

			# give headline the correct <h#> tag
			@$head[$headlineCount] .= "<a name=\"$anchor\"></a><h".$level.$matches[2][$headlineCount] .$headline."</h".$level.">";
			
			$headlineCount++;
		}		

		if( $doShowToc ) {
			$toclines = $headlineCount;
			$toc .= $sk->tocUnindent( $toclevel );
			$toc = $sk->tocTable( $toc );
		}

		# split up and insert constructed headlines
		
		$blocks = preg_split( "/<H[1-6].*?" . ">.*?<\/H[1-6]>/i", $text );
		$i = 0;

		foreach( $blocks as $block ) {
			if( $showEditLink && $headlineCount > 0 && $i == 0 && $block != "\n" ) {
			    # This is the [edit] link that appears for the top block of text when 
				# section editing is enabled
				$full .= $sk->editSectionLink(0);
			}
			$full .= $block;
			if( $doShowToc && !$i) {
			# Top anchor now in skin
				$full = $full.$toc;
			}

			if( !empty( $head[$i] ) ) {
				$full .= $head[$i];
			}
			$i++;
		}
		
		return $full;
	}

	/* private */ function doMagicISBN( &$tokenizer )
	{
		global $wgLang;

		# Check whether next token is a text token
		# If yes, fetch it and convert the text into a
		# Special::BookSources link
		$token = $tokenizer->previewToken();
		while ( $token["type"] == "" )
		{
			$tokenizer->nextToken();
			$token = $tokenizer->previewToken();
		}
		if ( $token["type"] == "text" )
		{
			$token = $tokenizer->nextToken();
			$x = $token["text"];
			$valid = "0123456789-ABCDEFGHIJKLMNOPQRSTUVWXYZ";

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
				$text = "ISBN $blank$x";
			} else {
				$titleObj = Title::makeTitle( NS_SPECIAL, "Booksources" );
				$text = "<a href=\"" .
				$titleObj->escapeLocalUrl( "isbn={$num}" ) .
					"\" class=\"internal\">ISBN $isbn</a>";
				$text .= $x;
			}
		} else {
			$text = "ISBN ";
		}
		return $text;
	}
	/* private */ function doMagicRFC( &$tokenizer )
	{
		global $wgLang;

		# Check whether next token is a text token
		# If yes, fetch it and convert the text into a
		# link to an RFC source
		$token = $tokenizer->previewToken();
		while ( $token["type"] == "" )
		{
			$tokenizer->nextToken();
			$token = $tokenizer->previewToken();
		}
		if ( $token["type"] == "text" )
		{
			$token = $tokenizer->nextToken();
			$x = $token["text"];
			$valid = "0123456789";

			$rfc = $blank = "" ;
			while ( " " == $x{0} ) {
				$blank .= " ";
				$x = substr( $x, 1 );
			}
			while ( strstr( $valid, $x{0} ) != false ) {
				$rfc .= $x{0};
				$x = substr( $x, 1 );
			}
		
			if ( "" == $rfc ) {
				$text .= "RFC $blank$x";
			} else {
				$url = wfmsg( "rfcurl" );
				$url = str_replace( "$1", $rfc, $url);
				$sk =& $this->mOptions->getSkin();
				$la = $sk->getExternalLinkAttributes( $url, "RFC {$rfc}" );
                            	$text = "<a href='{$url}'{$la}>RFC {$rfc}</a>{$x}";
			}
		} else {
			$text = "RFC ";
		}
		return $text;
	}

	function preSaveTransform( $text, &$title, &$user, $options, $clearState = true )
	{
		$this->mOptions = $options;
		$this->mTitle =& $title;
		$this->mOutputType = OT_WIKI;
		
		if ( $clearState ) {
			$this->clearState();
		}
		
		$stripState = false;
		$pairs = array(
			"\r\n" => "\n",
			);
		$text = str_replace(array_keys($pairs), array_values($pairs), $text);
		// now with regexes
		$pairs = array(
			"/<br.+(clear|break)=[\"']?(all|both)[\"']?\\/?>/i" => '<br style="clear:both;"/>',
			"/<br *?>/i" => "<br/>",
		);
		$text = preg_replace(array_keys($pairs), array_values($pairs), $text);
		$text = $this->strip( $text, $stripState, false );
		$text = $this->pstPass2( $text, $user );
		$text = $this->unstrip( $text, $stripState );
		return $text;
	}

	/* private */ function pstPass2( $text, &$user )
	{
		global $wgLang, $wgLocaltimezone, $wgCurParser;

		# Variable replacement
		# Because mOutputType is OT_WIKI, this will only process {{subst:xxx}} type tags
		$text = $this->replaceVariables( $text );

		# Signatures
		#
		$n = $user->getName();
		$k = $user->getOption( "nickname" );
		if ( "" == $k ) { $k = $n; }
		if(isset($wgLocaltimezone)) {
			$oldtz = getenv("TZ"); putenv("TZ=$wgLocaltimezone");
		}
		/* Note: this is an ugly timezone hack for the European wikis */
		$d = $wgLang->timeanddate( date( "YmdHis" ), false ) .
		  " (" . date( "T" ) . ")";
		if(isset($wgLocaltimezone)) putenv("TZ=$oldtz");

		$text = preg_replace( "/~~~~~/", $d, $text );
		$text = preg_replace( "/~~~~/", "[[" . $wgLang->getNsText(
		  Namespace::getUser() ) . ":$n|$k]] $d", $text );
		$text = preg_replace( "/~~~/", "[[" . $wgLang->getNsText(
		  Namespace::getUser() ) . ":$n|$k]]", $text );

		# Context links: [[|name]] and [[name (context)|]]
		#
		$tc = "[&;%\\-,.\\(\\)' _0-9A-Za-z\\/:\\x80-\\xff]";
		$np = "[&;%\\-,.' _0-9A-Za-z\\/:\\x80-\\xff]"; # No parens
		$namespacechar = '[ _0-9A-Za-z\x80-\xff]'; # Namespaces can use non-ascii!
		$conpat = "/^({$np}+) \\(({$tc}+)\\)$/";

		$p1 = "/\[\[({$np}+) \\(({$np}+)\\)\\|]]/";		# [[page (context)|]]
		$p2 = "/\[\[\\|({$tc}+)]]/";					# [[|page]]
		$p3 = "/\[\[($namespacechar+):({$np}+)\\|]]/";		# [[namespace:page|]]
		$p4 = "/\[\[($namespacechar+):({$np}+) \\(({$np}+)\\)\\|]]/";
														# [[ns:page (cont)|]]
		$context = "";
		$t = $this->mTitle->getText();
		if ( preg_match( $conpat, $t, $m ) ) {
			$context = $m[2];
		}
		$text = preg_replace( $p4, "[[\\1:\\2 (\\3)|\\2]]", $text );
		$text = preg_replace( $p1, "[[\\1 (\\2)|\\1]]", $text );
		$text = preg_replace( $p3, "[[\\1:\\2|\\2]]", $text );

		if ( "" == $context ) {
			$text = preg_replace( $p2, "[[\\1]]", $text );
		} else {
			$text = preg_replace( $p2, "[[\\1 ({$context})|\\1]]", $text );
		}
		
		/*
		$mw =& MagicWord::get( MAG_SUBST );
		$wgCurParser = $this->fork();
		$text = $mw->substituteCallback( $text, "wfBraceSubstitution" );
		$this->merge( $wgCurParser );
		*/
		
		# Trim trailing whitespace
		# MAG_END (__END__) tag allows for trailing 
		# whitespace to be deliberately included
		$text = rtrim( $text );
		$mw =& MagicWord::get( MAG_END );
		$mw->matchAndRemove( $text );

		return $text;
	}

	# Set up some variables which are usually set up in parse()
	# so that an external function can call some class members with confidence
	function startExternalParse( &$title, $options, $outputType, $clearState = true ) 
	{
		$this->mTitle =& $title;
		$this->mOptions = $options;
		$this->mOutputType = $outputType;
		if ( $clearState ) {
			$this->clearState();
		}
	}

	function transformMsg( $text, $options ) {
		global $wgTitle;
		static $executing = false;
		
		# Guard against infinite recursion
		if ( $executing ) {
			return $text;
		}
		$executing = true;

		$this->mTitle = $wgTitle;
		$this->mOptions = $options;
		$this->mOutputType = OT_MSG;
		$this->clearState();
		$text = $this->replaceVariables( $text );
		
		$executing = false;
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

	function merge( $other ) {
		$this->mLanguageLinks = array_merge( $this->mLanguageLinks, $other->mLanguageLinks );
		$this->mCategoryLinks = array_merge( $this->mCategoryLinks, $this->mLanguageLinks );
		$this->mContainsOldMagic = $this->mContainsOldMagic || $other->mContainsOldMagic;
	}

}

class ParserOptions
{
	# All variables are private
	var $mUseTeX;                    # Use texvc to expand <math> tags
	var $mUseCategoryMagic;          # Treat [[Category:xxxx]] tags specially
	var $mUseDynamicDates;           # Use $wgDateFormatter to format dates
	var $mInterwikiMagic;            # Interlanguage links are removed and returned in an array
	var $mAllowExternalImages;       # Allow external images inline
	var $mSkin;                      # Reference to the preferred skin
	var $mDateFormat;                # Date format index
	var $mEditSection;               # Create "edit section" links
	var $mEditSectionOnRightClick;   # Generate JavaScript to edit section on right click
	var $mNumberHeadings;            # Automatically number headings
	var $mShowToc;                   # Show table of contents

	function getUseTeX() { return $this->mUseTeX; }
	function getUseCategoryMagic() { return $this->mUseCategoryMagic; }
	function getUseDynamicDates() { return $this->mUseDynamicDates; }
	function getInterwikiMagic() { return $this->mInterwikiMagic; }
	function getAllowExternalImages() { return $this->mAllowExternalImages; }
	function getSkin() { return $this->mSkin; }
	function getDateFormat() { return $this->mDateFormat; }
	function getEditSection() { return $this->mEditSection; }
	function getEditSectionOnRightClick() { return $this->mEditSectionOnRightClick; }
	function getNumberHeadings() { return $this->mNumberHeadings; }
	function getShowToc() { return $this->mShowToc; }

	function setUseTeX( $x ) { return wfSetVar( $this->mUseTeX, $x ); }
	function setUseCategoryMagic( $x ) { return wfSetVar( $this->mUseCategoryMagic, $x ); }
	function setUseDynamicDates( $x ) { return wfSetVar( $this->mUseDynamicDates, $x ); }
	function setInterwikiMagic( $x ) { return wfSetVar( $this->mInterwikiMagic, $x ); }
	function setAllowExternalImages( $x ) { return wfSetVar( $this->mAllowExternalImages, $x ); }
	function setSkin( $x ) { return wfSetRef( $this->mSkin, $x ); }
	function setDateFormat( $x ) { return wfSetVar( $this->mDateFormat, $x ); }
	function setEditSection( $x ) { return wfSetVar( $this->mEditSection, $x ); }
	function setEditSectionOnRightClick( $x ) { return wfSetVar( $this->mEditSectionOnRightClick, $x ); }
	function setNumberHeadings( $x ) { return wfSetVar( $this->mNumberHeadings, $x ); }
	function setShowToc( $x ) { return wfSetVar( $this->mShowToc, $x ); }

	/* static */ function newFromUser( &$user ) 
	{
		$popts = new ParserOptions;
		$popts->initialiseFromUser( &$user );
		return $popts;
	}

	function initialiseFromUser( &$userInput ) 
	{
		global $wgUseTeX, $wgUseCategoryMagic, $wgUseDynamicDates, $wgInterwikiMagic, $wgAllowExternalImages;
		
		if ( !$userInput ) {
			$user = new User;
			$user->setLoaded( true );
		} else {
			$user =& $userInput;
		}

		$this->mUseTeX = $wgUseTeX;
		$this->mUseCategoryMagic = $wgUseCategoryMagic;
		$this->mUseDynamicDates = $wgUseDynamicDates;
		$this->mInterwikiMagic = $wgInterwikiMagic;
		$this->mAllowExternalImages = $wgAllowExternalImages;
		$this->mSkin =& $user->getSkin();
		$this->mDateFormat = $user->getOption( "date" );
		$this->mEditSection = $user->getOption( "editsection" );
		$this->mEditSectionOnRightClick = $user->getOption( "editsectiononrightclick" );
		$this->mNumberHeadings = $user->getOption( "numberheadings" );
		$this->mShowToc = $user->getOption( "showtoc" );
	}


}

# Regex callbacks, used in Parser::replaceVariables
function wfBraceSubstitution( $matches )
{
	global $wgCurParser;
	return $wgCurParser->braceSubstitution( $matches );
}

?>
