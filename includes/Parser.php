<?php

// require_once('Tokenizer.php');

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

# Allowed values for $mOutputType
define( "OT_HTML", 1 );
define( "OT_WIKI", 2 );
define( "OT_MSG", 3 );

# string parameter for extractTags which will cause it
# to strip HTML comments in addition to regular
# <XML>-style tags. This should not be anything we
# may want to use in wikisyntax
define( "STRIP_COMMENTS", "HTMLCommentStrip" );

# prefix for escaping, used in two functions at least
define( "UNIQ_PREFIX", "NaodW29");

class Parser
{
	# Persistent:
	var $mTagHooks;
	
	# Cleared with clearState():
	var $mOutput, $mAutonumber, $mDTopen, $mStripState = array();
	var $mVariables, $mIncludeCount, $mArgStack, $mLastSection, $mInPre;

	# Temporary:
	var $mOptions, $mTitle, $mOutputType;

	function Parser() {
		$this->mTagHooks = array();
		$this->clearState();
	}

	function clearState() {
		$this->mOutput = new ParserOutput;
		$this->mAutonumber = 0;
		$this->mLastSection = "";
		$this->mDTopen = false;
		$this->mVariables = false;
		$this->mIncludeCount = array();
		$this->mStripState = array();
		$this->mArgStack = array();
		$this->mInPre = false;
	}

	# First pass--just handle <nowiki> sections, pass the rest off
	# to internalParse() which does all the real work.
	#
	# Returns a ParserOutput
	#
	function parse( $text, &$title, $options, $linestart = true, $clearState = true ) {
		global $wgUseTidy;
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
		$text = $this->internalParse( $text, $linestart );
		$text = $this->unstrip( $text, $this->mStripState );
		# Clean up special characters, only run once, next-to-last before doBlockLevels
		if(!$wgUseTidy) {
			$fixtags = array(
				# french spaces, last one Guillemet-left
				# only if there is something before the space
				'/(.) (\\?|:|;|!|\\302\\273)/i' => '\\1&nbsp;\\2', 
				# french spaces, Guillemet-right
				"/(\\302\\253) /i"=>"\\1&nbsp;", 
				'/<hr *>/i' => '<hr />',
				'/<br *>/i' => '<br />',
				'/<center *>/i' => '<div class="center">',
				'/<\\/center *>/i' => '</div>',
				# Clean up spare ampersands; note that we probably ought to be
				# more careful about named entities.
				'/&(?!:amp;|#[Xx][0-9A-fa-f]+;|#[0-9]+;|[a-zA-Z0-9]+;)/' => '&amp;'
			);
			$text = preg_replace( array_keys($fixtags), array_values($fixtags), $text );
		} else {
			$fixtags = array(
				# french spaces, last one Guillemet-left
				'/ (\\?|:|!|\\302\\273)/i' => '&nbsp;\\1', 
				# french spaces, Guillemet-right
				'/(\\302\\253) /i' => '\\1&nbsp;', 
				'/([^> ]+(&#x30(1|3|9);)[^< ]*)/i' => '<span class="diacrit">\\1</span>', 
				'/<center *>/i' => '<div class="center">',
				'/<\\/center *>/i' => '</div>'
			);
			$text = preg_replace( array_keys($fixtags), array_values($fixtags), $text );
		}
		# only once and last
		$text = $this->doBlockLevels( $text, $linestart );
		$text = $this->unstripNoWiki( $text, $this->mStripState );
		if($wgUseTidy) {
			$text = $this->tidy($text);
		}
		$this->mOutput->setText( $text );
		wfProfileOut( $fname );
		return $this->mOutput;
	}

	/* static */ function getRandomString() {
		return dechex(mt_rand(0, 0x7fffffff)) . dechex(mt_rand(0, 0x7fffffff));
	}

	# Replaces all occurrences of <$tag>content</$tag> in the text
	# with a random marker and returns the new text. the output parameter
	# $content will be an associative array filled with data on the form
	# $unique_marker => content.

	# If $content is already set, the additional entries will be appended

	# If $tag is set to STRIP_COMMENTS, the function will extract
	# <!-- HTML comments -->

	/* static */ function extractTags($tag, $text, &$content, $uniq_prefix = ""){
		$rnd = $uniq_prefix . '-' . $tag . Parser::getRandomString();
		if ( !$content ) {
			$content = array( );
		}
		$n = 1;
		$stripped = '';

		while ( '' != $text ) {
			if($tag==STRIP_COMMENTS) {
				$p = preg_split( '/<!--/i', $text, 2 );
			} else {
				$p = preg_split( "/<\\s*$tag\\s*>/i", $text, 2 );
			}
			$stripped .= $p[0];
			if ( ( count( $p ) < 2 ) || ( '' == $p[1] ) ) {
				$text = '';
			} else {
				if($tag==STRIP_COMMENTS) {
					$q = preg_split( '/-->/i', $p[1], 2 );
				} else {
					$q = preg_split( "/<\\/\\s*$tag\\s*>/i", $p[1], 2 );
				}
				$marker = $rnd . sprintf('%08X', $n++);
				$content[$marker] = $q[0];
				$stripped .= $marker;
				$text = $q[1];
			}
		}
		return $stripped;
	}

	# Strips and renders <nowiki>, <pre>, <math>, <hiero>
	# If $render is set, performs necessary rendering operations on plugins
	# Returns the text, and fills an array with data needed in unstrip()
	# If the $state is already a valid strip state, it adds to the state

	# When $stripcomments is set, HTML comments <!-- like this -->
	# will be stripped in addition to other tags. This is important
	# for section editing, where these comments cause confusion when
	# counting the sections in the wikisource
	function strip( $text, &$state, $stripcomments = false ) {
		$render = ($this->mOutputType == OT_HTML);
		$nowiki_content = array();
		$math_content = array();
		$pre_content = array();
		$comment_content = array();
		$ext_content = array();
		
		# Replace any instances of the placeholders
		$uniq_prefix = UNIQ_PREFIX;
		#$text = str_replace( $uniq_prefix, wfHtmlEscapeFirst( $uniq_prefix ), $text );


		# nowiki
		$text = Parser::extractTags('nowiki', $text, $nowiki_content, $uniq_prefix);
		foreach( $nowiki_content as $marker => $content ){
			if( $render ){
				$nowiki_content[$marker] = wfEscapeHTMLTagsOnly( $content );
			} else {
				$nowiki_content[$marker] = "<nowiki>$content</nowiki>";
			}
		}

		# math
		$text = Parser::extractTags('math', $text, $math_content, $uniq_prefix);
		foreach( $math_content as $marker => $content ){
			if( $render ) {
				if( $this->mOptions->getUseTeX() ) {
					$math_content[$marker] = renderMath( $content );
				} else {
					$math_content[$marker] = "&lt;math&gt;$content&lt;math&gt;";
				}
			} else {
				$math_content[$marker] = "<math>$content</math>";
			}
		}

		# pre
		$text = Parser::extractTags('pre', $text, $pre_content, $uniq_prefix);
		foreach( $pre_content as $marker => $content ){
			if( $render ){
				$pre_content[$marker] = '<pre>' . wfEscapeHTMLTagsOnly( $content ) . '</pre>';
			} else {
				$pre_content[$marker] = "<pre>$content</pre>";
			}
		}

		# Comments
		if($stripcomments) {
			$text = Parser::extractTags(STRIP_COMMENTS, $text, $comment_content, $uniq_prefix);
			foreach( $comment_content as $marker => $content ){
				$comment_content[$marker] = "<!--$content-->";
			}
		}

		# Extensions
		foreach ( $this->mTagHooks as $tag => $callback ) {
			$ext_contents[$tag] = array();
			$text = Parser::extractTags( $tag, $text, $ext_content[$tag], $uniq_prefix );
			foreach( $ext_content[$tag] as $marker => $content ) {
				if ( $render ) {
					$ext_content[$tag][$marker] = $callback( $content );
				} else {
					$ext_content[$tag][$marker] = "<$tag>$content</$tag>";
				}
			}
		}

		# Merge state with the pre-existing state, if there is one
		if ( $state ) {
			$state['nowiki'] = $state['nowiki'] + $nowiki_content;
			$state['math'] = $state['math'] + $math_content;
			$state['pre'] = $state['pre'] + $pre_content;
			$state['comment'] = $state['comment'] + $comment_content;
			
			foreach( $ext_content as $tag => $array ) {
				if ( array_key_exists( $tag, $state ) ) {
					$state[$tag] = $state[$tag] + $array;
				}
			}
		} else {
			$state = array(
			  'nowiki' => $nowiki_content,
			  'math' => $math_content,
			  'pre' => $pre_content,
			  'comment' => $comment_content,
			) + $ext_content;
		}
		return $text;
	}

	# always call unstripNoWiki() after this one
	function unstrip( $text, &$state ) {
		# Must expand in reverse order, otherwise nested tags will be corrupted
		$contentDict = end( $state );
		for ( $contentDict = end( $state ); $contentDict !== false; $contentDict = prev( $state ) ) {
			if( key($state) != 'nowiki') {
				for ( $content = end( $contentDict ); $content !== false; $content = prev( $contentDict ) ) {
					$text = str_replace( key( $contentDict ), $content, $text );
				}
			}
		}

		return $text;
	}
	# always call this after unstrip() to preserve the order 
	function unstripNoWiki( $text, &$state ) {
		# Must expand in reverse order, otherwise nested tags will be corrupted
		for ( $content = end($state['nowiki']); $content !== false; $content = prev( $state['nowiki'] ) ) {
			$text = str_replace( key( $state['nowiki'] ), $content, $text );
		}

		return $text;
	}

	# Add an item to the strip state
	# Returns the unique tag which must be inserted into the stripped text
	# The tag will be replaced with the original text in unstrip()

	function insertStripItem( $text, &$state ) {
		$rnd = UNIQ_PREFIX . '-item' . Parser::getRandomString();
		if ( !$state ) {
			$state = array(
			  'nowiki' => array(),
			  'math' => array(),
			  'pre' => array()
			);
		}
		$state['item'][$rnd] = $text;
		return $rnd;
	}

	# categoryMagic
	# generate a list of subcategories and pages for a category
	# depending on wfMsg("usenewcategorypage") it either calls the new
	# or the old code. The new code will not work properly for some
	# languages due to sorting issues, so they might want to turn it
	# off.
	function categoryMagic() {
		global $wgLinkCache;
		$wgLinkCache->suspend();
		$msg = wfMsg('usenewcategorypage');
		if ( '0' == @$msg[0] )
		{
			return $this->oldCategoryMagic();
		} else {
			return $this->newCategoryMagic();
		}
		$wgLinkCache->resume();
	}

	# This method generates the list of subcategories and pages for a category
	function oldCategoryMagic () {
		global $wgLang , $wgUser ;
		if ( !$this->mOptions->getUseCategoryMagic() ) return ; # Doesn't use categories at all

		$cns = Namespace::getCategory() ;
		if ( $this->mTitle->getNamespace() != $cns ) return "" ; # This ain't a category page

		$r = "<br style=\"clear:both;\"/>\n";


		$sk =& $wgUser->getSkin() ;

		$articles = array() ;
		$children = array() ;
		$data = array () ;
		$id = $this->mTitle->getArticleID() ;

		# FIXME: add limits
		$t = wfStrencode( $this->mTitle->getDBKey() );
		$sql = "SELECT DISTINCT cur_title,cur_namespace FROM cur,categorylinks WHERE cl_to='$t' AND cl_from=cur_id ORDER BY cl_sortkey" ;
		$res = wfQuery ( $sql, DB_READ ) ;
		while ( $x = wfFetchObject ( $res ) ) $data[] = $x ;

		# For all pages that link to this category
		foreach ( $data AS $x )
		{
			$t = $wgLang->getNsText ( $x->cur_namespace ) ;
			if ( $t != "" ) $t .= ":" ;
			$t .= $x->cur_title ;

			if ( $x->cur_namespace == $cns ) {
				array_push ( $children , $sk->makeLink ( $t ) ) ; # Subcategory
			} else {
				array_push ( $articles , $sk->makeLink ( $t ) ) ; # Page in this category
			}
		}
		wfFreeResult ( $res ) ;

		# Showing subcategories
		if ( count ( $children ) > 0 ) {
			$r .= '<h2>'.wfMsg('subcategories')."</h2>\n" ;
			$r .= implode ( ', ' , $children ) ;
		}

		# Showing pages in this category
		if ( count ( $articles ) > 0 ) {
			$ti = $this->mTitle->getText() ;
			$h =  wfMsg( 'category_header', $ti );
			$r .= "<h2>{$h}</h2>\n" ;
			$r .= implode ( ', ' , $articles ) ;
		}


		return $r ;
	}



	function newCategoryMagic () {
		global $wgLang , $wgUser ;
		if ( !$this->mOptions->getUseCategoryMagic() ) return ; # Doesn't use categories at all

		$cns = Namespace::getCategory() ;
		if ( $this->mTitle->getNamespace() != $cns ) return '' ; # This ain't a category page

		$r = "<br style=\"clear:both;\"/>\n";


		$sk =& $wgUser->getSkin() ;

		$articles = array() ;
		$articles_start_char = array();
		$children = array() ;
		$children_start_char = array();
		$data = array () ;
		$id = $this->mTitle->getArticleID() ;

		# FIXME: add limits
		$t = wfStrencode( $this->mTitle->getDBKey() );
		$sql = "SELECT DISTINCT cur_title,cur_namespace,cl_sortkey FROM 
cur,categorylinks WHERE cl_to='$t' AND cl_from=cur_id ORDER BY 
cl_sortkey" ;
		$res = wfQuery ( $sql, DB_READ ) ;
		while ( $x = wfFetchObject ( $res ) )
		{
			$t = $ns = $wgLang->getNsText ( $x->cur_namespace ) ;
			if ( $t != '' ) $t .= ':' ;
			$t .= $x->cur_title ;
			
			if ( $x->cur_namespace == $cns ) {
				$ctitle = str_replace( '_',' ',$x->cur_title );
				array_push ( $children, $sk->makeKnownLink ( $t, $ctitle ) ) ; # Subcategory

				// If there's a link from Category:A to Category:B, the sortkey of the resulting
				// entry in the categorylinks table is Category:A, not A, which it SHOULD be.
				// Workaround: If sortkey == "Category:".$title, than use $title for sorting,
				// else use sortkey...
				if ( ($ns.":".$ctitle) ==  $x->cl_sortkey ) {
					array_push ( $children_start_char, $wgLang->firstChar( $x->cur_title ) );
				} else {
					array_push ( $children_start_char, $wgLang->firstChar( $x->cl_sortkey ) ) ;
				}
			} else {
				array_push ( $articles , $sk->makeLink ( $t ) ) ; # Page in this category
				array_push ( $articles_start_char, $wgLang->firstChar( $x->cl_sortkey ) ) ;
			}
		}
		wfFreeResult ( $res ) ;

		$ti = $this->mTitle->getText() ;

		# Don't show subcategories section if there are none.
		if ( count ( $children ) > 0 )
		{
			# Showing subcategories
			$r .= '<h2>' . wfMsg( 'subcategories' ) . "</h2>\n"
				. wfMsg( 'subcategorycount', count( $children ) );
			if ( count ( $children ) > 6 ) {
			
				// divide list into three equal chunks
				$chunk = (int) (count ( $children ) / 3);

				// get and display header
				$r .= '<table width="100%"><tr valign="top">';
			
				$startChunk = 0;
				$endChunk = $chunk;
			
				// loop through the chunks
				for($startChunk = 0, $endChunk = $chunk, $chunkIndex = 0; 
					$chunkIndex < 3;
					$chunkIndex++, $startChunk = $endChunk, $endChunk += $chunk + 1)
				{
				
					$r .= '<td><ul>';
					// output all subcategories to category
					for ($index = $startChunk ;
						$index < $endChunk && $index < count($children);
						$index++ )
					{
						// check for change of starting letter or begging of chunk
						if ( ($children_start_char[$index] != $children_start_char[$index - 1])
							|| ($index == $startChunk) )
						{
							$r .= "</ul><h3>{$children_start_char[$index]}</h3>\n<ul>";
						}
				
						$r .= "<li>{$children[$index]}</li>";
					}
					$r .= '</ul></td>';
				
							
				}
				$r .= '</tr></table>';
			} else {
				// for short lists of subcategories to category.
	
				$r .= "<h3>{$children_start_char[0]}</h3>\n";
				$r .= '<ul><li>'.$children[0].'</li>';
				for ($index = 1; $index < count($children); $index++ )
				{
					if ($children_start_char[$index] != $children_start_char[$index - 1])
					{
						$r .= "</ul><h3>{$children_start_char[$index]}</h3>\n<ul>";
					}
				
					$r .= "<li>{$children[$index]}</li>";
				}
				$r .= '</ul>';
			}
		} # END of if ( count($children) > 0 )

		$r .= '<h2>' . wfMsg( 'category_header', $ti ) . "</h2>\n" .
			wfMsg( 'categoryarticlecount', count( $articles ) );

		# Showing articles in this category
		if ( count ( $articles ) > 6) {
			$ti = $this->mTitle->getText() ;
			
			// divide list into three equal chunks
			$chunk = (int) (count ( $articles ) / 3);
			
			// get and display header
			$r .= '<table width="100%"><tr valign="top">';
			
			// loop through the chunks
			for($startChunk = 0, $endChunk = $chunk, $chunkIndex = 0; 
				$chunkIndex < 3;
				$chunkIndex++, $startChunk = $endChunk, $endChunk += $chunk + 1)
			{
				
				$r .= '<td><ul>';
				
				// output all articles in category
				for ($index = $startChunk ;
					$index < $endChunk && $index < count($articles);
					$index++ )
				{
					// check for change of starting letter or begging of chunk
					if ( ($articles_start_char[$index] != $articles_start_char[$index - 1])
						|| ($index == $startChunk) )
					{
						$r .= "</ul><h3>{$articles_start_char[$index]}</h3>\n<ul>";
					}
				
					$r .= "<li>{$articles[$index]}</li>";
				}
				$r .= '</ul></td>';
				
	
			}
			$r .= '</tr></table>';
		} elseif ( count ( $articles )  > 0) {
			// for short lists of articles in categories.
			$ti = $this->mTitle->getText() ;
			
			$r .= '<h3>'.$articles_start_char[0]."</h3>\n";
			$r .= '<ul><li>'.$articles[0].'</li>';
			for ($index = 1; $index < count($articles); $index++ )
			{
				if ($articles_start_char[$index] != $articles_start_char[$index - 1])
				{
					$r .= "</ul><h3>{$articles_start_char[$index]}</h3>\n<ul>";
				}
				
				$r .= "<li>{$articles[$index]}</li>";
			}
			$r .= '</ul>';
		}


		return $r ;
	}

	# Return allowed HTML attributes
	function getHTMLattrs () {
		$htmlattrs = array( # Allowed attributes--no scripting, etc.
				'title', 'align', 'lang', 'dir', 'width', 'height',
				'bgcolor', 'clear', /* BR */ 'noshade', /* HR */
				'cite', /* BLOCKQUOTE, Q */ 'size', 'face', 'color',
				/* FONT */ 'type', 'start', 'value', 'compact',
				/* For various lists, mostly deprecated but safe */
				'summary', 'width', 'border', 'frame', 'rules',
				'cellspacing', 'cellpadding', 'valign', 'char',
				'charoff', 'colgroup', 'col', 'span', 'abbr', 'axis',
				'headers', 'scope', 'rowspan', 'colspan', /* Tables */
				'id', 'class', 'name', 'style' /* For CSS */
				);
		return $htmlattrs ;
	}

	# Remove non approved attributes and javascript in css
	function fixTagAttributes ( $t ) {
		if ( trim ( $t ) == '' ) return '' ; # Saves runtime ;-)
		$htmlattrs = $this->getHTMLattrs() ;

		# Strip non-approved attributes from the tag
		$t = preg_replace(
			'/(\\w+)(\\s*=\\s*([^\\s\">]+|\"[^\">]*\"))?/e',
			"(in_array(strtolower(\"\$1\"),\$htmlattrs)?(\"\$1\".((\"x\$3\" != \"x\")?\"=\$3\":'')):'')",
			$t);
		# Strip javascript "expression" from stylesheets. Brute force approach:
		# If anythin offensive is found, all attributes of the HTML tag are dropped

		if( preg_match(
			'/style\\s*=.*(expression|tps*:\/\/|url\\s*\().*/is',
			wfMungeToUtf8( $t ) ) )
		{
			$t='';
		}

		return trim ( $t ) ;
	}

	# interface with html tidy, used if $wgUseTidy = true
	function tidy ( $text ) {
		global $wgTidyConf, $wgTidyBin, $wgTidyOpts;
		global $wgInputEncoding, $wgOutputEncoding;
		$fname = 'Parser::tidy';
		wfProfileIn( $fname );
		
		$cleansource = '';
		switch(strtoupper($wgOutputEncoding)) {
			case 'ISO-8859-1':
				$wgTidyOpts .= ($wgInputEncoding == $wgOutputEncoding)? ' -latin1':' -raw';
				break;
			case 'UTF-8':
				$wgTidyOpts .= ($wgInputEncoding == $wgOutputEncoding)? ' -utf8':' -raw';
				break;
			default:
				$wgTidyOpts .= ' -raw';
			}

		$wrappedtext = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'.
' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html>'.
'<head><title>test</title></head><body>'.$text.'</body></html>';
		$descriptorspec = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
			2 => array('file', '/dev/null', 'a')
		);
		$process = proc_open("$wgTidyBin -config $wgTidyConf $wgTidyOpts", $descriptorspec, $pipes);
		if (is_resource($process)) {
			fwrite($pipes[0], $wrappedtext);
			fclose($pipes[0]);
			while (!feof($pipes[1])) {
				$cleansource .= fgets($pipes[1], 1024);
			}
			fclose($pipes[1]);
			$return_value = proc_close($process);
		}

		wfProfileOut( $fname );
		
		if( $cleansource == '' && $text != '') {
			wfDebug( "Tidy error detected!\n" );
			return $text . "\n<!-- Tidy found serious XHTML errors -->\n";
		} else {
			return $cleansource;
		}
	}

	# parse the wiki syntax used to render tables
	function doTableStuff ( $t ) {
		$t = explode ( "\n" , $t ) ;
		$td = array () ; # Is currently a td tag open?
			$ltd = array () ; # Was it TD or TH?
			$tr = array () ; # Is currently a tr tag open?
			$ltr = array () ; # tr attributes
			foreach ( $t AS $k => $x )
			{
				$x = trim ( $x ) ;
				$fc = substr ( $x , 0 , 1 ) ;
				if ( '{|' == substr ( $x , 0 , 2 ) )
				{
					$t[$k] = "\n<table " . $this->fixTagAttributes ( substr ( $x , 3 ) ) . '>' ;
					array_push ( $td , false ) ;
					array_push ( $ltd , '' ) ;
					array_push ( $tr , false ) ;
					array_push ( $ltr , '' ) ;
				}
				else if ( count ( $td ) == 0 ) { } # Don't do any of the following
				else if ( '|}' == substr ( $x , 0 , 2 ) )
				{
					$z = "</table>\n" ;
					$l = array_pop ( $ltd ) ;
					if ( array_pop ( $tr ) ) $z = '</tr>' . $z ;
					if ( array_pop ( $td ) ) $z = "</{$l}>" . $z ;
					array_pop ( $ltr ) ;
					$t[$k] = $z ;
				}
				/*      else if ( "|_" == substr ( $x , 0 , 2 ) ) # Caption
						{
						$z = trim ( substr ( $x , 2 ) ) ;
						$t[$k] = "<caption>{$z}</caption>\n" ;
						}*/
				else if ( '|-' == substr ( $x , 0 , 2 ) ) # Allows for |---------------
				{
					$x = substr ( $x , 1 ) ;
					while ( $x != '' && substr ( $x , 0 , 1 ) == '-' ) $x = substr ( $x , 1 ) ;
					$z = '' ;
					$l = array_pop ( $ltd ) ;
					if ( array_pop ( $tr ) ) $z = '</tr>' . $z ;
					if ( array_pop ( $td ) ) $z = "</{$l}>" . $z ;
					array_pop ( $ltr ) ;
					$t[$k] = $z ;
					array_push ( $tr , false ) ;
					array_push ( $td , false ) ;
					array_push ( $ltd , '' ) ;
					array_push ( $ltr , $this->fixTagAttributes ( $x ) ) ;
				}
				else if ( '|' == $fc || '!' == $fc || '|+' == substr ( $x , 0 , 2 ) ) # Caption
				{
					if ( '|+' == substr ( $x , 0 , 2 ) )
					{
						$fc = '+' ;
						$x = substr ( $x , 1 ) ;
					}
					$after = substr ( $x , 1 ) ;
					if ( $fc == '!' ) $after = str_replace ( '!!' , '||' , $after ) ;
					$after = explode ( '||' , $after ) ;
					$t[$k] = '' ;
					foreach ( $after AS $theline )
					{
						$z = '' ;
						if ( $fc != '+' )
						{
							$tra = array_pop ( $ltr ) ;
							if ( !array_pop ( $tr ) ) $z = "<tr {$tra}>\n" ;
							array_push ( $tr , true ) ;
							array_push ( $ltr , '' ) ;
						}

						$l = array_pop ( $ltd ) ;
						if ( array_pop ( $td ) ) $z = "</{$l}>" . $z ;
						if ( $fc == '|' ) $l = 'td' ;
						else if ( $fc == '!' ) $l = 'th' ;
						else if ( $fc == '+' ) $l = 'caption' ;
						else $l = '' ;
						array_push ( $ltd , $l ) ;
						$y = explode ( '|' , $theline , 2 ) ;
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
			if ( array_pop ( $td ) ) $t[] = '</td>' ;
			if ( array_pop ( $tr ) ) $t[] = '</tr>' ;
			$t[] = '</table>' ;
		}

		$t = implode ( "\n" , $t ) ;
		#		$t = $this->removeHTMLtags( $t );
		return $t ;
	}

	# Parses the text and adds the result to the strip state
	# Returns the strip tag
	function stripParse( $text, $newline, $args ) 
	{
		$text = $this->strip( $text, $this->mStripState );
		$text = $this->internalParse( $text, (bool)$newline, $args, false );
		return $newline.$this->insertStripItem( $text, $this->mStripState );
	}
	
	function internalParse( $text, $linestart, $args = array(), $isMain=true ) {
		$fname = 'Parser::internalParse';
		wfProfileIn( $fname );

		$text = $this->removeHTMLtags( $text );
		$text = $this->replaceVariables( $text, $args );

		$text = preg_replace( '/(^|\n)-----*/', '\\1<hr />', $text );

		$text = $this->doHeadings( $text );
		if($this->mOptions->getUseDynamicDates()) {
			global $wgDateFormatter;
			$text = $wgDateFormatter->reformat( $this->mOptions->getDateFormat(), $text );
		}
		$text = $this->doAllQuotes( $text );
		// $text = $this->doExponent( $text );
		$text = $this->replaceExternalLinks( $text );
		$text = $this->doMagicLinks( $text );
		$text = $this->replaceInternalLinks ( $text );
		$text = $this->replaceInternalLinks ( $text );
		//$text = $this->doTokenizedParser ( $text );
		$text = $this->doTableStuff ( $text );
		$text = $this->formatHeadings( $text, $isMain );
		$sk =& $this->mOptions->getSkin();
		$text = $sk->transformContent( $text );

		if ( $isMain && !isset ( $this->categoryMagicDone ) ) {
			$text .= $this->categoryMagic () ;
			$this->categoryMagicDone = true ;
		}

		wfProfileOut( $fname );
		return $text;
	}
	
	# Parse ^^ tokens and return html
	/* private */ function doExponent ( $text )
	{
		$fname = 'Parser::doExponent';
		wfProfileIn( $fname);
		$text = preg_replace('/\^\^(.*)\^\^/','<small><sup>\\1</sup></small>', $text);
		wfProfileOut( $fname);
		return $text;
	}

    # Parse headers and return html
	/* private */ function doHeadings( $text ) {
		$fname = 'Parser::doHeadings';
		wfProfileIn( $fname );
		for ( $i = 6; $i >= 1; --$i ) {
			$h = substr( '======', 0, $i );
			$text = preg_replace( "/^{$h}(.+){$h}(\\s|$)/m",
			  "<h{$i}>\\1</h{$i}>\\2", $text );
		}
		wfProfileOut( $fname );
		return $text;
	}

	/* private */ function doAllQuotes( $text ) {
		$fname = 'Parser::doAllQuotes';
		wfProfileIn( $fname );
		$outtext = '';
		$lines = explode( "\n", $text );
		foreach ( $lines as $line ) {
			$outtext .= $this->doQuotes ( '', $line, '' ) . "\n";
		}
		$outtext = substr($outtext, 0,-1);
		wfProfileOut( $fname );
		return $outtext;
	}
	
	/* private */ function &doMagicLinks( &$text ) {
		$text = $this->magicISBN( $text );
		$text = $this->magicRFC( $text );
		return $text;
	}

	/* private */ function doQuotes( $pre, $text, $mode ) {
		if ( preg_match( "/^(.*)''(.*)$/sU", $text, $m ) ) {
			$m1_strong = ($m[1] == "") ? "" : "<strong>{$m[1]}</strong>";
			$m1_em = ($m[1] == "") ? "" : "<em>{$m[1]}</em>";
			if ( substr ($m[2], 0, 1) == '\'' ) {
				$m[2] = substr ($m[2], 1);
				if ($mode == 'em') {
					return $this->doQuotes ( $m[1], $m[2], ($m[1] == '') ? 'both' : 'emstrong' );
				} else if ($mode == 'strong') {
					return $m1_strong . $this->doQuotes ( '', $m[2], '' );
				} else if (($mode == 'emstrong') || ($mode == 'both')) {
					return $this->doQuotes ( '', $pre.$m1_strong.$m[2], 'em' );
				} else if ($mode == 'strongem') {
					return "<strong>{$pre}{$m1_em}</strong>" . $this->doQuotes ( '', $m[2], 'em' );
				} else {
					return $m[1] . $this->doQuotes ( '', $m[2], 'strong' );
				}
			} else {
				if ($mode == 'strong') {
					return $this->doQuotes ( $m[1], $m[2], ($m[1] == '') ? 'both' : 'strongem' );
				} else if ($mode == 'em') {
					return $m1_em . $this->doQuotes ( '', $m[2], '' );
				} else if ($mode == 'emstrong') {
					return "<em>{$pre}{$m1_strong}</em>" . $this->doQuotes ( '', $m[2], 'strong' );
				} else if (($mode == 'strongem') || ($mode == 'both')) {
					return $this->doQuotes ( '', $pre.$m1_em.$m[2], 'strong' );
				} else {
					return $m[1] . $this->doQuotes ( '', $m[2], 'em' );
				}
			}
		} else {
			$text_strong = ($text == '') ? '' : "<strong>{$text}</strong>";
			$text_em = ($text == '') ? '' : "<em>{$text}</em>";
			if ($mode == '') {
				return $pre . $text;
			} else if ($mode == 'em') {
				return $pre . $text_em;
			} else if ($mode == 'strong') {
				return $pre . $text_strong;
			} else if ($mode == 'strongem') {
				return (($pre == '') && ($text == '')) ? '' : "<strong>{$pre}{$text_em}</strong>";
			} else {
				return (($pre == '') && ($text == '')) ? '' : "<em>{$pre}{$text_strong}</em>";
			}
		}
	}

	# Note: we have to do external links before the internal ones,
	# and otherwise take great care in the order of things here, so
	# that we don't end up interpreting some URLs twice.

	/* private */ function replaceExternalLinks( $text ) {
		$fname = 'Parser::replaceExternalLinks';
		wfProfileIn( $fname );
		$text = $this->subReplaceExternalLinks( $text, 'http', true );
		$text = $this->subReplaceExternalLinks( $text, 'https', true );
		$text = $this->subReplaceExternalLinks( $text, 'ftp', false );
		$text = $this->subReplaceExternalLinks( $text, 'irc', false );
		$text = $this->subReplaceExternalLinks( $text, 'gopher', false );
		$text = $this->subReplaceExternalLinks( $text, 'news', false );
		$text = $this->subReplaceExternalLinks( $text, 'mailto', false );
		wfProfileOut( $fname );
		return $text;
	}

	/* private */ function subReplaceExternalLinks( $s, $protocol, $autonumber ) {
		$unique = '4jzAfzB8hNvf4sqyO9Edd8pSmk9rE2in0Tgw3';
		$uc = "A-Za-z0-9_\\/~%\\-+&*#?!=()@\\x80-\\xFF";

		# this is  the list of separators that should be ignored if they
		# are the last character of an URL but that should be included
		# if they occur within the URL, e.g. "go to www.foo.com, where .."
		# in this case, the last comma should not become part of the URL,
		# but in "www.foo.com/123,2342,32.htm" it should.
		$sep = ",;\.:";
		$fnc = 'A-Za-z0-9_.,~%\\-+&;#*?!=()@\\x80-\\xFF';
		$images = 'gif|png|jpg|jpeg';

		# PLEASE NOTE: The curly braces { } are not part of the regex,
		# they are interpreted as part of the string (used to tell PHP
		# that the content of the string should be inserted there).
		$e1 = "/(^|[^\\[])({$protocol}:)([{$uc}{$sep}]+)\\/([{$fnc}]+)\\." .
		  "((?i){$images})([^{$uc}]|$)/";

		$e2 = "/(^|[^\\[])({$protocol}:)(([".$uc."]|[".$sep."][".$uc."])+)([^". $uc . $sep. "]|[".$sep."]|$)/";
		$sk =& $this->mOptions->getSkin();

		if ( $autonumber and $this->mOptions->getAllowExternalImages() ) { # Use img tags only for HTTP urls
			$s = preg_replace( $e1, '\\1' . $sk->makeImage( "{$unique}:\\3" .
			  '/\\4.\\5', '\\4.\\5' ) . '\\6', $s );
		}
		$s = preg_replace( $e2, '\\1' . "<a href=\"{$unique}:\\3\"" .
		  $sk->getExternalLinkAttributes( "{$unique}:\\3", wfEscapeHTML(
		  "{$unique}:\\3" ) ) . ">" . wfEscapeHTML( "{$unique}:\\3" ) .
		  '</a>\\5', $s );
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
				$paren = '';
			} else {
				# Expand the URL for printable version
				$paren = "<span class='urlexpansion'> (<i>" . htmlspecialchars ( $link ) . "</i>)</span>";
			}
			$la = $sk->getExternalLinkAttributes( $link, $text );
			$s .= "<a href='{$link}'{$la}>{$text}</a>{$paren}{$trail}";

		}
		return $s;
	}


	/* private */ function replaceInternalLinks( $s ) {
		global $wgLang, $wgLinkCache;
		global $wgNamespacesWithSubpages, $wgLanguageCode;
		static $fname = 'Parser::replaceInternalLinks' ;
		wfProfileIn( $fname );

		wfProfileIn( $fname.'-setup' );
		static $tc = FALSE;
		# the % is needed to support urlencoded titles as well
		if ( !$tc ) { $tc = Title::legalChars() . '#%'; }
		$sk =& $this->mOptions->getSkin();

		$a = explode( '[[', ' ' . $s );
		$s = array_shift( $a );
		$s = substr( $s, 1 );

		# Match a link having the form [[namespace:link|alternate]]trail
		static $e1 = FALSE;
		if ( !$e1 ) { $e1 = "/^([{$tc}]+)(?:\\|([^]]+))?]](.*)\$/sD"; }
		# Match the end of a line for a word that's not followed by whitespace,
		# e.g. in the case of 'The Arab al[[Razi]]', 'al' will be matched
		static $e2 = '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD';

		$useLinkPrefixExtension = $wgLang->linkPrefixExtension();
		# Special and Media are pseudo-namespaces; no pages actually exist in them
		static $image = FALSE;
		static $special = FALSE;
		static $media = FALSE;
		static $category = FALSE;
		if ( !$image ) { $image = Namespace::getImage(); }
		if ( !$special ) { $special = Namespace::getSpecial(); }
		if ( !$media ) { $media = Namespace::getMedia(); }
		if ( !$category ) { $category = Namespace::getCategory(); }

		$nottalk = !Namespace::isTalk( $this->mTitle->getNamespace() );

		if ( $useLinkPrefixExtension ) {
			if ( preg_match( $e2, $s, $m ) ) {
				$first_prefix = $m[2];
				$s = $m[1];
			} else {
				$first_prefix = false;
			}
		} else {
			$prefix = '';
		}

		wfProfileOut( $fname.'-setup' );

		foreach ( $a as $line ) {
			wfProfileIn( $fname.'-prefixhandling' );
			if ( $useLinkPrefixExtension ) {
				if ( preg_match( $e2, $s, $m ) ) {
					$prefix = $m[2];
					$s = $m[1];
				} else {
					$prefix='';
				}
				# first link
				if($first_prefix) {
					$prefix = $first_prefix;
					$first_prefix = false;
				}
			}
			wfProfileOut( $fname.'-prefixhandling' );
			
			if ( preg_match( $e1, $line, $m ) ) { # page with normal text or alt
				$text = $m[2];
				# fix up urlencoded title texts
				if(preg_match('/%/', $m[1] )) $m[1] = urldecode($m[1]);
				$trail = $m[3];
			} else { # Invalid form; output directly
				$s .= $prefix . '[[' . $line ;
				continue;
			}

			/* Valid link forms:
			Foobar -- normal
			:Foobar -- override special treatment of prefix (images, language links)
			/Foobar -- convert to CurrentPage/Foobar
			/Foobar/ -- convert to CurrentPage/Foobar, strip the initial / from text
			*/
			$c = substr($m[1],0,1);
			$noforce = ($c != ':');
			if( $c == '/' ) { # subpage
				if(substr($m[1],-1,1)=='/') {                 # / at end means we don't want the slash to be shown
					$m[1]=substr($m[1],1,strlen($m[1])-2);
					$noslash=$m[1];
				} else {
					$noslash=substr($m[1],1);
				}
				if(!empty($wgNamespacesWithSubpages[$this->mTitle->getNamespace()])) { # subpages allowed here
					$link = $this->mTitle->getPrefixedText(). '/' . trim($noslash);
					if( '' == $text ) {
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
			$wasblank = ( '' == $text );
			if( $wasblank )
			$text = $link;

			$nt = Title::newFromText( $link );
			if( !$nt ) {
				$s .= $prefix . '[[' . $line;
				continue;
			}
			$ns = $nt->getNamespace();
			$iw = $nt->getInterWiki();
			if( $noforce ) {
				if( $iw && $this->mOptions->getInterwikiMagic() && $nottalk && $wgLang->getLanguageName( $iw ) ) {
					array_push( $this->mOutput->mLanguageLinks, $nt->getFullText() );
					$tmp = $prefix . $trail ;
					$s .= (trim($tmp) == '')? '': $tmp;
					continue;
				}
				if ( $ns == $image ) {
					$s .= $prefix . $sk->makeImageLinkObj( $nt, $text ) . $trail;
					$wgLinkCache->addImageLinkObj( $nt );
					continue;
				}
				if ( $ns == $category ) {
					$t = $nt->getText() ;
					$nnt = Title::newFromText ( Namespace::getCanonicalName($category).":".$t ) ;

					$wgLinkCache->suspend(); # Don't save in links/brokenlinks
					$t = $sk->makeLinkObj( $nnt, $t, '', '' , $prefix );
					$wgLinkCache->resume();

					$sortkey = $wasblank ? $this->mTitle->getPrefixedText() : $text;
					$wgLinkCache->addCategoryLinkObj( $nt, $sortkey );
					$this->mOutput->mCategoryLinks[] = $t ;
					$s .= $prefix . $trail ;
					continue;
				}
			}
			if( ( $nt->getPrefixedText() == $this->mTitle->getPrefixedText() ) &&
			( strpos( $link, '#' ) == FALSE ) ) {
				# Self-links are handled specially; generally de-link and change to bold.
				$s .= $prefix . $sk->makeSelfLinkObj( $nt, $text, '', $trail );
				continue;
			}

			if( $ns == $media ) {
				$s .= $prefix . $sk->makeMediaLinkObj( $nt, $text ) . $trail;
				$wgLinkCache->addImageLinkObj( $nt );
				continue;
			} elseif( $ns == $special ) {
				$s .= $prefix . $sk->makeKnownLinkObj( $nt, $text, '', $trail );
				continue;
			}
			$s .= $sk->makeLinkObj( $nt, $text, '', $trail, $prefix );
		}
		wfProfileOut( $fname );
		return $s;
	}

	# Some functions here used by doBlockLevels()
	#
	/* private */ function closeParagraph() {
		$result = '';
		if ( '' != $this->mLastSection ) {
			$result = '</' . $this->mLastSection  . ">\n";
		}
		$this->mInPre = false;
		$this->mLastSection = '';
		return $result;
	}
	# getCommon() returns the length of the longest common substring
	# of both arguments, starting at the beginning of both.
	#
	/* private */ function getCommon( $st1, $st2 ) {
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

		if ( '*' == $char ) { $result .= '<ul><li>'; }
		else if ( '#' == $char ) { $result .= '<ol><li>'; }
		else if ( ':' == $char ) { $result .= '<dl><dd>'; }
		else if ( ';' == $char ) {
			$result .= '<dl><dt>';
			$this->mDTopen = true;
		}
		else { $result = '<!-- ERR 1 -->'; }

		return $result;
	}

	/* private */ function nextItem( $char ) {
		if ( '*' == $char || '#' == $char ) { return '</li><li>'; }
		else if ( ':' == $char || ';' == $char ) {
			$close = "</dd>";
			if ( $this->mDTopen ) { $close = '</dt>'; }
			if ( ';' == $char ) {
				$this->mDTopen = true;
				return $close . '<dt>';
			} else {
				$this->mDTopen = false;
				return $close . '<dd>';
			}
		}
		return '<!-- ERR 2 -->';
	}

	/* private */function closeList( $char ) {
		if ( '*' == $char ) { $text = '</li></ul>'; }
		else if ( '#' == $char ) { $text = '</li></ol>'; }
		else if ( ':' == $char ) {
			if ( $this->mDTopen ) {
				$this->mDTopen = false;
				$text = '</dt></dl>';
			} else {
				$text = '</dd></dl>';
			}
		}
		else {	return '<!-- ERR 3 -->'; }
		return $text."\n";
	}

	/* private */ function doBlockLevels( $text, $linestart ) {
		$fname = 'Parser::doBlockLevels';
		wfProfileIn( $fname );

		# Parsing through the text line by line.  The main thing
		# happening here is handling of block-level elements p, pre,
		# and making lists from lines starting with * # : etc.
		#
		$textLines = explode( "\n", $text );

		$lastPrefix = $output = $lastLine = '';
		$this->mDTopen = $inBlockElem = false;
		$prefixLength = 0;
		$paragraphStack = false;

		if ( !$linestart ) {
			$output .= array_shift( $textLines );
		}
		foreach ( $textLines as $oLine ) {
			$lastPrefixLength = strlen( $lastPrefix );
			$preCloseMatch = preg_match("/<\\/pre/i", $oLine );
			$preOpenMatch = preg_match("/<pre/i", $oLine );
			if ( !$this->mInPre ) {
				# Multiple prefixes may abut each other for nested lists.
				$prefixLength = strspn( $oLine, '*#:;' );
				$pref = substr( $oLine, 0, $prefixLength );

				# eh?
				$pref2 = str_replace( ';', ':', $pref );
				$t = substr( $oLine, $prefixLength );
				$this->mInPre = !empty($preOpenMatch);
			} else {
				# Don't interpret any other prefixes in preformatted text
				$prefixLength = 0;
				$pref = $pref2 = '';
				$t = $oLine;
			}

			# List generation
			if( $prefixLength && 0 == strcmp( $lastPrefix, $pref2 ) ) {
				# Same as the last item, so no need to deal with nesting or opening stuff
				$output .= $this->nextItem( substr( $pref, -1 ) );
				$paragraphStack = false;

				if ( ";" == substr( $pref, -1 ) ) {
					# The one nasty exception: definition lists work like this:
					# ; title : definition text
					# So we check for : in the remainder text to split up the
					# title and definition, without b0rking links.
					# FIXME: This is not foolproof. Something better in Tokenizer might help.
					if( preg_match( '/^(.*?(?:\s|&nbsp;)):(.*)$/', $t, $match ) ) {
						$term = $match[1];
						$output .= $term . $this->nextItem( ':' );
						$t = $match[2];
					}
				}
			} elseif( $prefixLength || $lastPrefixLength ) {
				# Either open or close a level...
				$commonPrefixLength = $this->getCommon( $pref, $lastPrefix );
				$paragraphStack = false;

				while( $commonPrefixLength < $lastPrefixLength ) {
					$output .= $this->closeList( $lastPrefix{$lastPrefixLength-1} );
					--$lastPrefixLength;
				}
				if ( $prefixLength <= $commonPrefixLength && $commonPrefixLength > 0 ) {
					$output .= $this->nextItem( $pref{$commonPrefixLength-1} );
				}
				while ( $prefixLength > $commonPrefixLength ) {
					$char = substr( $pref, $commonPrefixLength, 1 );
					$output .= $this->openList( $char );

					if ( ';' == $char ) {
						# FIXME: This is dupe of code above
						if( preg_match( '/^(.*?(?:\s|&nbsp;)):(.*)$/', $t, $match ) ) {
							$term = $match[1];
							$output .= $term . $this->nextItem( ":" );
							$t = $match[2];
						}
					}
					++$commonPrefixLength;
				}
				$lastPrefix = $pref2;
			}
			if( 0 == $prefixLength ) {
				# No prefix (not in list)--go to paragraph mode
				$uniq_prefix = UNIQ_PREFIX;
				// XXX: use a stack for nestable elements like span, table and div
				$openmatch = preg_match('/(<table|<blockquote|<h1|<h2|<h3|<h4|<h5|<h6|<pre|<tr|<p|<ul|<li|<\\/tr|<\\/td|<\\/th)/i', $t );
				$closematch = preg_match(
					'/(<\\/table|<\\/blockquote|<\\/h1|<\\/h2|<\\/h3|<\\/h4|<\\/h5|<\\/h6|'.
					'<td|<th|<div|<\\/div|<hr|<\\/pre|<\\/p|'.$uniq_prefix.'-pre|<\\/li|<\\/ul)/i', $t );
				if ( $openmatch or $closematch ) {
					$paragraphStack = false;
					$output .= $this->closeParagraph();
					if($preOpenMatch and !$preCloseMatch) {
						$this->mInPre = true;	
					}
					if ( $closematch  ) {
						$inBlockElem = false;
					} else {
						$inBlockElem = true;
					}
				} else if ( !$inBlockElem && !$this->mInPre ) {
					if ( " " == $t{0} and ( $this->mLastSection == 'pre' or trim($t) != '' ) ) {
						// pre
						if ($this->mLastSection != 'pre') {
							$paragraphStack = false;
							$output .= $this->closeParagraph().'<pre>';
							$this->mLastSection = 'pre';
						}
					} else {
						// paragraph
						if ( '' == trim($t) ) {
							if ( $paragraphStack ) {
								$output .= $paragraphStack.'<br />';
								$paragraphStack = false;
								$this->mLastSection = 'p';
							} else {
								if ($this->mLastSection != 'p' ) {
									$output .= $this->closeParagraph();
									$this->mLastSection = '';
									$paragraphStack = '<p>';
								} else {
									$paragraphStack = '</p><p>';
								}
							}
						} else {
							if ( $paragraphStack ) {
								$output .= $paragraphStack;
								$paragraphStack = false;
								$this->mLastSection = 'p';
							} else if ($this->mLastSection != 'p') {
								$output .= $this->closeParagraph().'<p>';
								$this->mLastSection = 'p';
							}
						}
					}
				}
			}
			if ($paragraphStack === false) {
				$output .= $t."\n";
			}
		}
		while ( $prefixLength ) {
			$output .= $this->closeList( $pref2{$prefixLength-1} );
			--$prefixLength;
		}
		if ( '' != $this->mLastSection ) {
			$output .= '</' . $this->mLastSection . '>';
			$this->mLastSection = '';
		}

		wfProfileOut( $fname );
		return $output;
	}

	# Return value of a magic variable (like PAGENAME)
	function getVariableValue( $index ) {
		global $wgLang, $wgSitename, $wgServer;

		switch ( $index ) {
			case MAG_CURRENTMONTH:
				return $wgLang->formatNum( date( 'm' ) );
			case MAG_CURRENTMONTHNAME:
				return $wgLang->getMonthName( date('n') );
			case MAG_CURRENTMONTHNAMEGEN:
				return $wgLang->getMonthNameGen( date('n') );
			case MAG_CURRENTDAY:
				return $wgLang->formatNum( date('j') );
			case MAG_PAGENAME:
				return $this->mTitle->getText();
			case MAG_NAMESPACE:
				# return Namespace::getCanonicalName($this->mTitle->getNamespace());
				return $wgLang->getNsText($this->mTitle->getNamespace()); // Patch  by Dori
			case MAG_CURRENTDAYNAME:
				return $wgLang->getWeekdayName( date('w')+1 );
			case MAG_CURRENTYEAR:
				return $wgLang->formatNum( date( 'Y' ) );
			case MAG_CURRENTTIME:
				return $wgLang->time( wfTimestampNow(), false );
			case MAG_NUMBEROFARTICLES:
				return $wgLang->formatNum( wfNumberOfArticles() );
			case MAG_SITENAME:
				return $wgSitename;
			case MAG_SERVER:
				return $wgServer;
			default:
				return NULL;
		}
	}

	# initialise the magic variables (like CURRENTMONTHNAME)
	function initialiseVariables() {
		global $wgVariableIDs;
		$this->mVariables = array();
		foreach ( $wgVariableIDs as $id ) {
			$mw =& MagicWord::get( $id );
			$mw->addToArray( $this->mVariables, $this->getVariableValue( $id ) );
		}
	}

	/* private */ function replaceVariables( $text, $args = array() ) {
		global $wgLang, $wgScript, $wgArticlePath;

		$fname = 'Parser::replaceVariables';
		wfProfileIn( $fname );

		$bail = false;
		if ( !$this->mVariables ) {
			$this->initialiseVariables();
		}
		$titleChars = Title::legalChars();
		$nonBraceChars = str_replace( array( '{', '}' ), array( '', '' ), $titleChars );

		# This function is called recursively. To keep track of arguments we need a stack:
		array_push( $this->mArgStack, $args );

		# PHP global rebinding syntax is a bit weird, need to use the GLOBALS array
		$GLOBALS['wgCurParser'] =& $this;
		

		if ( $this->mOutputType == OT_HTML ) {
			# Variable substitution
			$text = preg_replace_callback( "/{{([$nonBraceChars]*?)}}/", 'wfVariableSubstitution', $text );
			
			# Argument substitution
			$text = preg_replace_callback( "/(\\n?){{{([$titleChars]*?)}}}/", 'wfArgSubstitution', $text );
		}
		# Template substitution
		$regex = '/(\\n?){{(['.$nonBraceChars.']*)(\\|.*?|)}}/s';
		$text = preg_replace_callback( $regex, 'wfBraceSubstitution', $text );

		array_pop( $this->mArgStack );

		wfProfileOut( $fname );
		return $text;
	}

	function variableSubstitution( $matches ) {
		if ( array_key_exists( $matches[1], $this->mVariables ) ) {
			$text = $this->mVariables[$matches[1]];
			$this->mOutput->mContainsOldMagic = true;
		} else {
			$text = $matches[0];
		}
		return $text;
	}

	# Split template arguments
	function getTemplateArgs( $argsString ) {
		if ( $argsString === '' ) {
			return array();
		}

		$args = explode( '|', substr( $argsString, 1 ) );

		# If any of the arguments contains a '[[' but no ']]', it needs to be
		# merged with the next arg because the '|' character between belongs
		# to the link syntax and not the template parameter syntax.
		$argc = count($args);
		$i = 0;
		for ( $i = 0; $i < $argc-1; $i++ ) {
			if ( substr_count ( $args[$i], "[[" ) != substr_count ( $args[$i], "]]" ) ) {
				$args[$i] .= "|".$args[$i+1];
				array_splice($args, $i+1, 1);
				$i--;
				$argc--;
			}
		}

		return $args;
	}

	function braceSubstitution( $matches ) {
		global $wgLinkCache, $wgLang;
		$fname = 'Parser::braceSubstitution';
		$found = false;
		$nowiki = false;
		$noparse = false;
		
		$title = NULL;

		# $newline is an optional newline character before the braces
		# $part1 is the bit before the first |, and must contain only title characters
		# $args is a list of arguments, starting from index 0, not including $part1

		$newline = $matches[1];
		$part1 = $matches[2];
		# If the third subpattern matched anything, it will start with |

		$args = $this->getTemplateArgs($matches[3]);
		$argc = count( $args );
	
		# {{{}}}
		if ( strpos( $matches[0], '{{{' ) !== false ) {
			$text = $matches[0];
			$found = true;
			$noparse = true;
		}

		# SUBST
		if ( !$found ) {
			$mwSubst =& MagicWord::get( MAG_SUBST );
			if ( $mwSubst->matchStartAndRemove( $part1 ) ) {
				if ( $this->mOutputType != OT_WIKI ) {
					# Invalid SUBST not replaced at PST time
					# Return without further processing
					$text = $matches[0];
					$found = true;
					$noparse= true;
				}
			} elseif ( $this->mOutputType == OT_WIKI ) {
				# SUBST not found in PST pass, do nothing
				$text = $matches[0];
				$found = true;
			}
		}

		# MSG, MSGNW and INT
		if ( !$found ) {
			# Check for MSGNW:
			$mwMsgnw =& MagicWord::get( MAG_MSGNW );
			if ( $mwMsgnw->matchStartAndRemove( $part1 ) ) {
				$nowiki = true;
			} else {
				# Remove obsolete MSG:
				$mwMsg =& MagicWord::get( MAG_MSG );
				$mwMsg->matchStartAndRemove( $part1 );
			}

			# Check if it is an internal message
			$mwInt =& MagicWord::get( MAG_INT );
			if ( $mwInt->matchStartAndRemove( $part1 ) ) {
				if ( $this->incrementIncludeCount( 'int:'.$part1 ) ) {
					$text = wfMsgReal( $part1, $args, true );
					$found = true;
				}
			}
		}

		# NS
		if ( !$found ) {
			# Check for NS: (namespace expansion)
			$mwNs = MagicWord::get( MAG_NS );
			if ( $mwNs->matchStartAndRemove( $part1 ) ) {
				if ( intval( $part1 ) ) {
					$text = $wgLang->getNsText( intval( $part1 ) );
					$found = true;
				} else {
					$index = Namespace::getCanonicalIndex( strtolower( $part1 ) );
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

			if ( $mwLocal->matchStartAndRemove( $part1 ) ) {
				$func = 'getLocalURL';
			} elseif ( $mwLocalE->matchStartAndRemove( $part1 ) ) {
				$func = 'escapeLocalURL';
			} else {
				$func = '';
			}

			if ( $func !== '' ) {
				$title = Title::newFromText( $part1 );
				if ( !is_null( $title ) ) {
					if ( $argc > 0 ) {
						$text = $title->$func( $args[0] );
					} else {
						$text = $title->$func();
					}
					$found = true;
				}
			}
		}

		# Internal variables
		if ( !$found && array_key_exists( $part1, $this->mVariables ) ) {
			$text = $this->mVariables[$part1];
			$found = true;
			$this->mOutput->mContainsOldMagic = true;
		}
/*
		# Arguments input from the caller
		$inputArgs = end( $this->mArgStack );
		if ( !$found && array_key_exists( $part1, $inputArgs ) ) {
			$text = $inputArgs[$part1];
			$found = true;
		}
*/
		# Load from database
		if ( !$found ) {
			$title = Title::newFromText( $part1, NS_TEMPLATE );
			if ( !is_null( $title ) && !$title->isExternal() ) {
				# Check for excessive inclusion
				$dbk = $title->getPrefixedDBkey();
				if ( $this->incrementIncludeCount( $dbk ) ) {
					$article = new Article( $title );
					$articleContent = $article->getContentWithoutUsingSoManyDamnGlobals();
					if ( $articleContent !== false ) {
						$found = true;
						$text = $articleContent;

					}
				}

				# If the title is valid but undisplayable, make a link to it
				if ( $this->mOutputType == OT_HTML && !$found ) {
					$text = '[[' . $title->getPrefixedText() . ']]';
					$found = true;
				}
			}
		}

		# Recursive parsing, escaping and link table handling
		# Only for HTML output
		if ( $nowiki && $found && $this->mOutputType == OT_HTML ) {
			$text = wfEscapeWikiText( $text );
		} elseif ( $this->mOutputType == OT_HTML && $found && !$noparse) {
			# Clean up argument array
			$assocArgs = array();
			$index = 1;
			foreach( $args as $arg ) {
				$eqpos = strpos( $arg, '=' );
				if ( $eqpos === false ) {
					$assocArgs[$index++] = $arg;
				} else {
					$name = trim( substr( $arg, 0, $eqpos ) );
					$value = trim( substr( $arg, $eqpos+1 ) );
					if ( $value === false ) {
						$value = '';
					}
					if ( $name !== false ) {
						$assocArgs[$name] = $value;
					}
				}
			}

			# Do not enter included links in link table
			if ( !is_null( $title ) ) {
				$wgLinkCache->suspend();
			}

			# Run full parser on the included text
			$text = $this->stripParse( $text, $newline, $assocArgs );

			# Resume the link cache and register the inclusion as a link
			if ( !is_null( $title ) ) {
				$wgLinkCache->resume();
				$wgLinkCache->addLinkObj( $title );
			}
		}

		if ( !$found ) {
			return $matches[0];
		} else {
			return $text;
		}
	}

	# Triple brace replacement -- used for template arguments
	function argSubstitution( $matches ) {
		$newline = $matches[1];
		$arg = trim( $matches[2] );
		$text = $matches[0];
		$inputArgs = end( $this->mArgStack );

		if ( array_key_exists( $arg, $inputArgs ) ) {
			$text = $this->stripParse( $inputArgs[$arg], $newline, array() );
		}
		
		return $text;
	}

	# Returns true if the function is allowed to include this entity
	function incrementIncludeCount( $dbk ) {
		if ( !array_key_exists( $dbk, $this->mIncludeCount ) ) {
			$this->mIncludeCount[$dbk] = 0;
		}
		if ( ++$this->mIncludeCount[$dbk] <= MAX_INCLUDE_REPEAT ) {
			return true;
		} else {
			return false;
		}
	}


	# Cleans up HTML, removes dangerous tags and attributes
	/* private */ function removeHTMLtags( $text ) {
		global $wgUseTidy, $wgUserHtml;
		$fname = 'Parser::removeHTMLtags';
		wfProfileIn( $fname );
		
		if( $wgUserHtml ) {
			$htmlpairs = array( # Tags that must be closed
				'b', 'del', 'i', 'ins', 'u', 'font', 'big', 'small', 'sub', 'sup', 'h1',
				'h2', 'h3', 'h4', 'h5', 'h6', 'cite', 'code', 'em', 's',
				'strike', 'strong', 'tt', 'var', 'div', 'center',
				'blockquote', 'ol', 'ul', 'dl', 'table', 'caption', 'pre',
				'ruby', 'rt' , 'rb' , 'rp', 'p'
			);
			$htmlsingle = array(
				'br', 'hr', 'li', 'dt', 'dd'
			);
			$htmlnest = array( # Tags that can be nested--??
				'table', 'tr', 'td', 'th', 'div', 'blockquote', 'ol', 'ul',
				'dl', 'font', 'big', 'small', 'sub', 'sup'
			);
			$tabletags = array( # Can only appear inside table
				'td', 'th', 'tr'
			);
		} else {
			$htmlpairs = array();
			$htmlsingle = array();
			$htmlnest = array();
			$tabletags = array();
		}

		$htmlsingle = array_merge( $tabletags, $htmlsingle );
		$htmlelements = array_merge( $htmlsingle, $htmlpairs );

		$htmlattrs = $this->getHTMLattrs () ;

		# Remove HTML comments
		$text = preg_replace( '/(\\n *<!--.*--> *(?=\\n)|<!--.*-->)/sU', '$2', $text );

		$bits = explode( '<', $text );
		$text = array_shift( $bits );
		if(!$wgUseTidy) {
			$tagstack = array(); $tablestack = array();
			foreach ( $bits as $x ) {
				$prev = error_reporting( E_ALL & ~( E_NOTICE | E_WARNING ) );
				preg_match( '/^(\\/?)(\\w+)([^>]*)(\\/{0,1}>)([^<]*)$/',
				$x, $regs );
				list( $qbar, $slash, $t, $params, $brace, $rest ) = $regs;
				error_reporting( $prev );

				$badtag = 0 ;
				if ( in_array( $t = strtolower( $t ), $htmlelements ) ) {
					# Check our stack
					if ( $slash ) {
						# Closing a tag...
						if ( ! in_array( $t, $htmlsingle ) &&
						( $ot = @array_pop( $tagstack ) ) != $t ) {
							@array_push( $tagstack, $ot );
							$badtag = 1;
						} else {
							if ( $t == 'table' ) {
								$tagstack = array_pop( $tablestack );
							}
							$newparams = '';
						}
					} else {
						# Keep track for later
						if ( in_array( $t, $tabletags ) &&
						! in_array( 'table', $tagstack ) ) {
							$badtag = 1;
						} else if ( in_array( $t, $tagstack ) &&
						! in_array ( $t , $htmlnest ) ) {
							$badtag = 1 ;
						} else if ( ! in_array( $t, $htmlsingle ) ) {
							if ( $t == 'table' ) {
								array_push( $tablestack, $tagstack );
								$tagstack = array();
							}
							array_push( $tagstack, $t );
						}
						# Strip non-approved attributes from the tag
						$newparams = $this->fixTagAttributes($params);

					}
					if ( ! $badtag ) {
						$rest = str_replace( '>', '&gt;', $rest );
						$text .= "<$slash$t $newparams$brace$rest";
						continue;
					}
				}
				$text .= '&lt;' . str_replace( '>', '&gt;', $x);
			}
			# Close off any remaining tags
			while ( is_array( $tagstack ) && ($t = array_pop( $tagstack )) ) {
				$text .= "</$t>\n";
				if ( $t == 'table' ) { $tagstack = array_pop( $tablestack ); }
			}
		} else {
			# this might be possible using tidy itself
			foreach ( $bits as $x ) {
				preg_match( '/^(\\/?)(\\w+)([^>]*)(\\/{0,1}>)([^<]*)$/',
				$x, $regs );
				@list( $qbar, $slash, $t, $params, $brace, $rest ) = $regs;
				if ( in_array( $t = strtolower( $t ), $htmlelements ) ) {
					$newparams = $this->fixTagAttributes($params);
					$rest = str_replace( '>', '&gt;', $rest );
					$text .= "<$slash$t $newparams$brace$rest";
				} else {
					$text .= '&lt;' . str_replace( '>', '&gt;', $x);
				}
			}	
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

	/* private */ function formatHeadings( $text, $isMain=true ) {
		global $wgInputEncoding, $wgMaxTocLevel;
		
		$doNumberHeadings = $this->mOptions->getNumberHeadings();
		$doShowToc = $this->mOptions->getShowToc();
		$forceTocHere = false;
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
		if( $this->mTitle->getPrefixedText() == wfMsg('mainpage') ) {
			$doShowToc = 0;
		}

		# Get all headlines for numbering them and adding funky stuff like [edit]
		# links - this is for later, but we need the number of headlines right now
		$numMatches = preg_match_all( '/<H([1-6])(.*?' . '>)(.*?)<\/H[1-6]>/i', $text, $matches );

		# if there are fewer than 4 headlines in the article, do not show TOC
		if( $numMatches < 4 ) {
			$doShowToc = 0;
		}

		# if the string __TOC__ (not case-sensitive) occurs in the HTML,
		# override above conditions and always show TOC at that place
		$mw =& MagicWord::get( MAG_TOC );
		if ($mw->match( $text ) ) {
			$doShowToc = 1;
			$forceTocHere = true;
		} else {
			# if the string __FORCETOC__ (not case-sensitive) occurs in the HTML,
			# override above conditions and always show TOC above first header
			$mw =& MagicWord::get( MAG_FORCETOC );
			if ($mw->matchAndRemove( $text ) ) {
				$doShowToc = 1;
			}
		}
		


		# We need this to perform operations on the HTML
		$sk =& $this->mOptions->getSkin();

		# headline counter
		$headlineCount = 0;

		# Ugh .. the TOC should have neat indentation levels which can be
		# passed to the skin functions. These are determined here
		$toclevel = 0;
		$toc = '';
		$full = '';
		$head = array();
		$sublevelCount = array();
		$level = 0;
		$prevlevel = 0;
		foreach( $matches[3] as $headline ) {
			$numbering = '';
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
							$numbering .= '.';
						}
						$numbering .= $sublevelCount[$i];
						$dot = 1;
					}
				}
			}

			# The canonized header is a version of the header text safe to use for links
			# Avoid insertion of weird stuff like <math> by expanding the relevant sections
			$canonized_headline = $this->unstrip( $headline, $this->mStripState );
			$canonized_headline = $this->unstripNoWiki( $headline, $this->mStripState );

			# strip out HTML
			$canonized_headline = preg_replace( '/<.*?' . '>/','',$canonized_headline );
			$tocline = trim( $canonized_headline );
			$canonized_headline = urlencode( do_html_entity_decode( str_replace(' ', '_', $tocline), ENT_COMPAT, $wgInputEncoding ) );
			$replacearray = array(
				'%3A' => ':',
				'%' => '.'
			);
			$canonized_headline = str_replace(array_keys($replacearray),array_values($replacearray),$canonized_headline);
			$refer[$headlineCount] = $canonized_headline;

			# count how many in assoc. array so we can track dupes in anchors
			@$refers[$canonized_headline]++;
			$refcount[$headlineCount]=$refers[$canonized_headline];

			# Prepend the number to the heading text

			if( $doNumberHeadings || $doShowToc ) {
				$tocline = $numbering . ' ' . $tocline;

				# Don't number the heading if it is the only one (looks silly)
				if( $doNumberHeadings && count( $matches[3] ) > 1) {
					# the two are different if the line contains a link
					$headline=$numbering . ' ' . $headline;
				}
			}

			# Create the anchor for linking from the TOC to the section
			$anchor = $canonized_headline;
			if($refcount[$headlineCount] > 1 ) {
				$anchor .= '_' . $refcount[$headlineCount];
			}
			if( $doShowToc && ( !isset($wgMaxTocLevel) || $toclevel<$wgMaxTocLevel ) ) {
				$toc .= $sk->tocLine($anchor,$tocline,$toclevel);
			}
			if( $showEditLink ) {
				if ( empty( $head[$headlineCount] ) ) {
					$head[$headlineCount] = '';
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

		$blocks = preg_split( '/<H[1-6].*?' . '>.*?<\/H[1-6]>/i', $text );
		$i = 0;

		foreach( $blocks as $block ) {
			if( $showEditLink && $headlineCount > 0 && $i == 0 && $block != "\n" ) {
			    # This is the [edit] link that appears for the top block of text when
				# section editing is enabled

				# Disabled because it broke block formatting
				# For example, a bullet point in the top line
				# $full .= $sk->editSectionLink(0);
			}
			$full .= $block;
			if( $doShowToc && !$i && $isMain && !$forceTocHere) {
			# Top anchor now in skin
				$full = $full.$toc;
			}

			if( !empty( $head[$i] ) ) {
				$full .= $head[$i];
			}
			$i++;
		}
		if($forceTocHere) {
			$mw =& MagicWord::get( MAG_TOC );
			return $mw->replace( $toc, $full );
		} else {
			return $full;
		}
	}

	# Return an HTML link for the "ISBN 123456" text
	/* private */ function magicISBN( $text ) {
		global $wgLang;

		$a = split( 'ISBN ', " $text" );
		if ( count ( $a ) < 2 ) return $text;
		$text = substr( array_shift( $a ), 1);
		$valid = '0123456789-ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		foreach ( $a as $x ) {
			$isbn = $blank = '' ;
			while ( ' ' == $x{0} ) {
				$blank .= ' ';
				$x = substr( $x, 1 );
			}
			while ( strstr( $valid, $x{0} ) != false ) {
				$isbn .= $x{0};
				$x = substr( $x, 1 );
			}
			$num = str_replace( '-', '', $isbn );
			$num = str_replace( ' ', '', $num );

			if ( '' == $num ) {
				$text .= "ISBN $blank$x";
			} else {
				$titleObj = Title::makeTitle( NS_SPECIAL, 'Booksources' );
				$text .= '<a href="' .
				$titleObj->escapeLocalUrl( "isbn={$num}" ) .
					"\" class=\"internal\">ISBN $isbn</a>";
				$text .= $x;
			}
		}
		return $text;
	}
	
	# Return an HTML link for the "RFC 1234" text
	/* private */ function magicRFC( $text ) {
		global $wgLang;

		$a = split( 'RFC ', ' '.$text );
		if ( count ( $a ) < 2 ) return $text;
		$text = substr( array_shift( $a ), 1);
		$valid = '0123456789';

		foreach ( $a as $x ) {
			$rfc = $blank = '' ;
			while ( ' ' == $x{0} ) {
				$blank .= ' ';
				$x = substr( $x, 1 );
			}
			while ( strstr( $valid, $x{0} ) != false ) {
				$rfc .= $x{0};
				$x = substr( $x, 1 );
			}

			if ( '' == $rfc ) {
				$text .= "RFC $blank$x";
			} else {
				$url = wfmsg( 'rfcurl' );
				$url = str_replace( '$1', $rfc, $url);
				$sk =& $this->mOptions->getSkin();
				$la = $sk->getExternalLinkAttributes( $url, "RFC {$rfc}" );
                            	$text .= "<a href='{$url}'{$la}>RFC {$rfc}</a>{$x}";
			}
		}
		return $text;
	}

	function preSaveTransform( $text, &$title, &$user, $options, $clearState = true ) {
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
		/*
		$pairs = array(
			"/<br.+(clear|break)=[\"']?(all|both)[\"']?\\/?>/i" => '<br style="clear:both;"/>',
			"/<br *?>/i" => "<br />",
		);
		$text = preg_replace(array_keys($pairs), array_values($pairs), $text);
		*/
		$text = $this->strip( $text, $stripState, false );
		$text = $this->pstPass2( $text, $user );
		$text = $this->unstrip( $text, $stripState );
		$text = $this->unstripNoWiki( $text, $stripState );
		return $text;
	}

	/* private */ function pstPass2( $text, &$user ) {
		global $wgLang, $wgLocaltimezone, $wgCurParser;

		# Variable replacement
		# Because mOutputType is OT_WIKI, this will only process {{subst:xxx}} type tags
		$text = $this->replaceVariables( $text );

		# Signatures
		#
		$n = $user->getName();
		$k = $user->getOption( 'nickname' );
		if ( '' == $k ) { $k = $n; }
		if(isset($wgLocaltimezone)) {
			$oldtz = getenv('TZ'); putenv('TZ='.$wgLocaltimezone);
		}
		/* Note: this is an ugly timezone hack for the European wikis */
		$d = $wgLang->timeanddate( date( 'YmdHis' ), false ) .
		  ' (' . date( 'T' ) . ')';
		if(isset($wgLocaltimezone)) putenv('TZ='.$oldtzs);

		$text = preg_replace( '/~~~~~/', $d, $text );
		$text = preg_replace( '/~~~~/', '[[' . $wgLang->getNsText(
		  Namespace::getUser() ) . ":$n|$k]] $d", $text );
		$text = preg_replace( '/~~~/', '[[' . $wgLang->getNsText(
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
		$text = preg_replace( $p4, '[[\\1:\\2 (\\3)|\\2]]', $text );
		$text = preg_replace( $p1, '[[\\1 (\\2)|\\1]]', $text );
		$text = preg_replace( $p3, '[[\\1:\\2|\\2]]', $text );

		if ( '' == $context ) {
			$text = preg_replace( $p2, '[[\\1]]', $text );
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
	function startExternalParse( &$title, $options, $outputType, $clearState = true ) {
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

	# Create an HTML-style tag, e.g. <yourtag>special text</yourtag>
	# Callback will be called with the text within
	# Transform and return the text within
	function setHook( $tag, $callback ) {
		$oldVal = @$this->mTagHooks[$tag];
		$this->mTagHooks[$tag] = $callback;
		return $oldVal;
	}
}

class ParserOutput
{
	var $mText, $mLanguageLinks, $mCategoryLinks, $mContainsOldMagic;
	var $mCacheTime; # Used in ParserCache

	function ParserOutput( $text = "", $languageLinks = array(), $categoryLinks = array(),
		$containsOldMagic = false )
	{
		$this->mText = $text;
		$this->mLanguageLinks = $languageLinks;
		$this->mCategoryLinks = $categoryLinks;
		$this->mContainsOldMagic = $containsOldMagic;
		$this->mCacheTime = "";
	}

	function getText() { return $this->mText; }
	function getLanguageLinks() { return $this->mLanguageLinks; }
	function getCategoryLinks() { return $this->mCategoryLinks; }
	function getCacheTime() { return $this->mCacheTime; }
	function containsOldMagic() { return $this->mContainsOldMagic; }
	function setText( $text ) { return wfSetVar( $this->mText, $text ); }
	function setLanguageLinks( $ll ) { return wfSetVar( $this->mLanguageLinks, $ll ); }
	function setCategoryLinks( $cl ) { return wfSetVar( $this->mCategoryLinks, $cl ); }
	function setContainsOldMagic( $com ) { return wfSetVar( $this->mContainsOldMagic, $com ); }
	function setCacheTime( $t ) { return wfSetVar( $this->mCacheTime, $t ); }

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

	function getUseTeX()                        { return $this->mUseTeX; }
	function getUseCategoryMagic()              { return $this->mUseCategoryMagic; }
	function getUseDynamicDates()               { return $this->mUseDynamicDates; }
	function getInterwikiMagic()                { return $this->mInterwikiMagic; }
	function getAllowExternalImages()           { return $this->mAllowExternalImages; }
	function getSkin()                          { return $this->mSkin; }
	function getDateFormat()                    { return $this->mDateFormat; }
	function getEditSection()                   { return $this->mEditSection; }
	function getEditSectionOnRightClick()       { return $this->mEditSectionOnRightClick; }
	function getNumberHeadings()                { return $this->mNumberHeadings; }
	function getShowToc()                       { return $this->mShowToc; }

	function setUseTeX( $x )                    { return wfSetVar( $this->mUseTeX, $x ); }
	function setUseCategoryMagic( $x )          { return wfSetVar( $this->mUseCategoryMagic, $x ); }
	function setUseDynamicDates( $x )           { return wfSetVar( $this->mUseDynamicDates, $x ); }
	function setInterwikiMagic( $x )            { return wfSetVar( $this->mInterwikiMagic, $x ); }
	function setAllowExternalImages( $x )       { return wfSetVar( $this->mAllowExternalImages, $x ); }
	function setDateFormat( $x )                { return wfSetVar( $this->mDateFormat, $x ); }
	function setEditSection( $x )               { return wfSetVar( $this->mEditSection, $x ); }
	function setEditSectionOnRightClick( $x )   { return wfSetVar( $this->mEditSectionOnRightClick, $x ); }
	function setNumberHeadings( $x )            { return wfSetVar( $this->mNumberHeadings, $x ); }
	function setShowToc( $x )                   { return wfSetVar( $this->mShowToc, $x ); }

    function setSkin( &$x ) { $this->mSkin =& $x; }

	/* static */ function newFromUser( &$user ) {
		$popts = new ParserOptions;
		$popts->initialiseFromUser( $user );
		return $popts;
	}

	function initialiseFromUser( &$userInput ) {
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
		$this->mDateFormat = $user->getOption( 'date' );
		$this->mEditSection = $user->getOption( 'editsection' );
		$this->mEditSectionOnRightClick = $user->getOption( 'editsectiononrightclick' );
		$this->mNumberHeadings = $user->getOption( 'numberheadings' );
		$this->mShowToc = $user->getOption( 'showtoc' );
	}


}

# Regex callbacks, used in Parser::replaceVariables
function wfBraceSubstitution( $matches )
{
	global $wgCurParser;
	return $wgCurParser->braceSubstitution( $matches );
}

function wfArgSubstitution( $matches )
{
	global $wgCurParser;
	return $wgCurParser->argSubstitution( $matches );
}

function wfVariableSubstitution( $matches )
{
	global $wgCurParser;
	return $wgCurParser->variableSubstitution( $matches );
}

?>
