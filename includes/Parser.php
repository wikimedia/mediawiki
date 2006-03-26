<?php
/**
 * File for Parser and related classes
 *
 * @package MediaWiki
 * @subpackage Parser
 */

/** */
require_once( 'Sanitizer.php' );
require_once( 'HttpFunctions.php' );

/**
 * Update this version number when the ParserOutput format
 * changes in an incompatible way, so the parser cache
 * can automatically discard old data.
 */
define( 'MW_PARSER_VERSION', '1.5.0' );

/**
 * Variable substitution O(N^2) attack
 *
 * Without countermeasures, it would be possible to attack the parser by saving
 * a page filled with a large number of inclusions of large pages. The size of
 * the generated page would be proportional to the square of the input size.
 * Hence, we limit the number of inclusions of any given page, thus bringing any
 * attack back to O(N).
 */

define( 'MAX_INCLUDE_REPEAT', 100 );
define( 'MAX_INCLUDE_SIZE', 1000000 ); // 1 Million

define( 'RLH_FOR_UPDATE', 1 );

# Allowed values for $mOutputType
define( 'OT_HTML', 1 );
define( 'OT_WIKI', 2 );
define( 'OT_MSG' , 3 );

# string parameter for extractTags which will cause it
# to strip HTML comments in addition to regular
# <XML>-style tags. This should not be anything we
# may want to use in wikisyntax
define( 'STRIP_COMMENTS', 'HTMLCommentStrip' );

# Constants needed for external link processing
define( 'HTTP_PROTOCOLS', 'http:\/\/|https:\/\/' );
# Everything except bracket, space, or control characters
define( 'EXT_LINK_URL_CLASS', '[^]<>"\\x00-\\x20\\x7F]' );
# Including space
define( 'EXT_LINK_TEXT_CLASS', '[^\]\\x00-\\x1F\\x7F]' );
define( 'EXT_IMAGE_FNAME_CLASS', '[A-Za-z0-9_.,~%\\-+&;#*?!=()@\\x80-\\xFF]' );
define( 'EXT_IMAGE_EXTENSIONS', 'gif|png|jpg|jpeg' );
define( 'EXT_LINK_BRACKETED',  '/\[(\b('.$wgUrlProtocols.')'.EXT_LINK_URL_CLASS.'+) *('.EXT_LINK_TEXT_CLASS.'*?)\]/S' );
define( 'EXT_IMAGE_REGEX',
	'/^('.HTTP_PROTOCOLS.')'.  # Protocol
	'('.EXT_LINK_URL_CLASS.'+)\\/'.  # Hostname and path
	'('.EXT_IMAGE_FNAME_CLASS.'+)\\.((?i)'.EXT_IMAGE_EXTENSIONS.')$/S' # Filename
);

/**
 * PHP Parser
 *
 * Processes wiki markup
 *
 * <pre>
 * There are three main entry points into the Parser class:
 * parse()
 *   produces HTML output
 * preSaveTransform().
 *   produces altered wiki markup.
 * transformMsg()
 *   performs brace substitution on MediaWiki messages
 *
 * Globals used:
 *    objects:   $wgLang, $wgLinkCache
 *
 * NOT $wgArticle, $wgUser or $wgTitle. Keep them away!
 *
 * settings:
 *  $wgUseTex*, $wgUseDynamicDates*, $wgInterwikiMagic*,
 *  $wgNamespacesWithSubpages, $wgAllowExternalImages*,
 *  $wgLocaltimezone, $wgAllowSpecialInclusion*
 *
 *  * only within ParserOptions
 * </pre>
 *
 * @package MediaWiki
 */
class Parser
{
	/**#@+
	 * @access private
	 */
	# Persistent:
	var $mTagHooks;

	# Cleared with clearState():
	var $mOutput, $mAutonumber, $mDTopen, $mStripState = array();
	var $mVariables, $mIncludeCount, $mArgStack, $mLastSection, $mInPre;
	var $mInterwikiLinkHolders, $mLinkHolders, $mUniqPrefix;

	# Temporary:
	var $mOptions, $mTitle, $mOutputType,
	    $mTemplates,	// cache of already loaded templates, avoids
		                // multiple SQL queries for the same string
	    $mTemplatePath;	// stores an unsorted hash of all the templates already loaded
		                // in this path. Used for loop detection.

	var $mIWTransData = array();

	/**#@-*/

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function Parser() {
		global $wgContLang;
 		$this->mTemplates = array();
 		$this->mTemplatePath = array();
		$this->mTagHooks = array();
		$this->clearState();
	}

	/**
	 * Clear Parser state
	 *
	 * @access private
	 */
	function clearState() {
		$this->mOutput = new ParserOutput;
		$this->mAutonumber = 0;
		$this->mLastSection = '';
		$this->mDTopen = false;
		$this->mVariables = false;
		$this->mIncludeCount = array();
		$this->mStripState = array();
		$this->mArgStack = array();
		$this->mInPre = false;
		$this->mInterwikiLinkHolders = array(
			'texts' => array(),
			'titles' => array()
		);
		$this->mLinkHolders = array(
			'namespaces' => array(),
			'dbkeys' => array(),
			'queries' => array(),
			'texts' => array(),
			'titles' => array()
		);
		$this->mUniqPrefix = 'UNIQ' . Parser::getRandomString();
	}

	/**
	 * Accessor for mUniqPrefix.
	 *
	 * @access public
	 */
	function UniqPrefix() {
		return $this->mUniqPrefix;
	}

	/**
	 * First pass--just handle <nowiki> sections, pass the rest off
	 * to internalParse() which does all the real work.
	 *
	 * @access private
	 * @param string $text Text we want to parse
	 * @param Title &$title A title object
	 * @param array $options
	 * @param boolean $linestart
	 * @param boolean $clearState
	 * @return ParserOutput a ParserOutput
	 */
	function parse( $text, &$title, $options, $linestart = true, $clearState = true ) {
		global $wgUseTidy, $wgContLang;
		$fname = 'Parser::parse';
		wfProfileIn( $fname );

		if ( $clearState ) {
			$this->clearState();
		}

		$this->mOptions = $options;
		$this->mTitle =& $title;
		$this->mOutputType = OT_HTML;

		$this->mStripState = NULL;

		//$text = $this->strip( $text, $this->mStripState );
		// VOODOO MAGIC FIX! Sometimes the above segfaults in PHP5.
		$x =& $this->mStripState;

		wfRunHooks( 'ParserBeforeStrip', array( &$this, &$text, &$x ) );
		$text = $this->strip( $text, $x );
		wfRunHooks( 'ParserAfterStrip', array( &$this, &$text, &$x ) );

		$text = $this->internalParse( $text );

		$text = $this->unstrip( $text, $this->mStripState );

		# Clean up special characters, only run once, next-to-last before doBlockLevels
		$fixtags = array(
			# french spaces, last one Guillemet-left
			# only if there is something before the space
			'/(.) (?=\\?|:|;|!|\\302\\273)/' => '\\1&nbsp;\\2',
			# french spaces, Guillemet-right
			'/(\\302\\253) /' => '\\1&nbsp;',
			'/<center *>(.*)<\\/center *>/i' => '<div class="center">\\1</div>',
		);
		$text = preg_replace( array_keys($fixtags), array_values($fixtags), $text );

		# only once and last
		$text = $this->doBlockLevels( $text, $linestart );

		$this->replaceLinkHolders( $text );

		# the position of the convert() call should not be changed. it
		# assumes that the links are all replaces and the only thing left
		# is the <nowiki> mark.
		$text = $wgContLang->convert($text);
		$this->mOutput->setTitleText($wgContLang->getParsedTitle());

		$text = $this->unstripNoWiki( $text, $this->mStripState );

		wfRunHooks( 'ParserBeforeTidy', array( &$this, &$text ) );

		$text = Sanitizer::normalizeCharReferences( $text );
		global $wgUseTidy;
		if ($wgUseTidy) {
			$text = Parser::tidy($text);
		}

		wfRunHooks( 'ParserAfterTidy', array( &$this, &$text ) );

		$this->mOutput->setText( $text );
		wfProfileOut( $fname );
		return $this->mOutput;
	}

	/**
	 * Get a random string
	 *
	 * @access private
	 * @static
	 */
	function getRandomString() {
		return dechex(mt_rand(0, 0x7fffffff)) . dechex(mt_rand(0, 0x7fffffff));
	}

	/**
	 * Replaces all occurrences of <$tag>content</$tag> in the text
	 * with a random marker and returns the new text. the output parameter
	 * $content will be an associative array filled with data on the form
	 * $unique_marker => content.
	 *
	 * If $content is already set, the additional entries will be appended
	 * If $tag is set to STRIP_COMMENTS, the function will extract
	 * <!-- HTML comments -->
	 *
	 * @access private
	 * @static
	 */
	function extractTagsAndParams($tag, $text, &$content, &$tags, &$params, $uniq_prefix = ''){
		$rnd = $uniq_prefix . '-' . $tag . Parser::getRandomString();
		if ( !$content ) {
			$content = array( );
		}
		$n = 1;
		$stripped = '';

		if ( !$tags ) {
			$tags = array( );
		}

		if ( !$params ) {
			$params = array( );
		}

		if( $tag == STRIP_COMMENTS ) {
			$start = '/<!--()/';
			$end   = '/-->/';
		} else {
			$start = "/<$tag(\\s+[^>]*|\\s*)>/i";
			$end   = "/<\\/$tag\\s*>/i";
		}

		while ( '' != $text ) {
			$p = preg_split( $start, $text, 2, PREG_SPLIT_DELIM_CAPTURE );
			$stripped .= $p[0];
			if( count( $p ) < 3 ) {
				break;
			}
			$attributes = $p[1];
			$inside     = $p[2];

			$marker = $rnd . sprintf('%08X', $n++);
			$stripped .= $marker;

			$tags[$marker] = "<$tag$attributes>";
			$params[$marker] = Sanitizer::decodeTagAttributes( $attributes );

			$q = preg_split( $end, $inside, 2 );
			$content[$marker] = $q[0];
			if( count( $q ) < 2 ) {
				# No end tag -- let it run out to the end of the text.
				break;
			} else {
				$text = $q[1];
			}
		}
		return $stripped;
	}

	/**
	 * Wrapper function for extractTagsAndParams
	 * for cases where $tags and $params isn't needed
	 * i.e. where tags will never have params, like <nowiki>
	 *
	 * @access private
	 * @static
	 */
	function extractTags( $tag, $text, &$content, $uniq_prefix = '' ) {
		$dummy_tags = array();
		$dummy_params = array();

		return Parser::extractTagsAndParams( $tag, $text, $content,
			$dummy_tags, $dummy_params, $uniq_prefix );
	}

	/**
	 * Strips and renders nowiki, pre, math, hiero
	 * If $render is set, performs necessary rendering operations on plugins
	 * Returns the text, and fills an array with data needed in unstrip()
	 * If the $state is already a valid strip state, it adds to the state
	 *
	 * @param bool $stripcomments when set, HTML comments <!-- like this -->
	 *  will be stripped in addition to other tags. This is important
	 *  for section editing, where these comments cause confusion when
	 *  counting the sections in the wikisource
	 *
	 * @access private
	 */
	function strip( $text, &$state, $stripcomments = false ) {
		$render = ($this->mOutputType == OT_HTML);
		$html_content = array();
		$nowiki_content = array();
		$math_content = array();
		$pre_content = array();
		$comment_content = array();
		$ext_content = array();
		$ext_tags = array();
		$ext_params = array();
		$gallery_content = array();

		# Replace any instances of the placeholders
		$uniq_prefix = $this->mUniqPrefix;
		#$text = str_replace( $uniq_prefix, wfHtmlEscapeFirst( $uniq_prefix ), $text );

		# html
		global $wgRawHtml;
		if( $wgRawHtml ) {
			$text = Parser::extractTags('html', $text, $html_content, $uniq_prefix);
			foreach( $html_content as $marker => $content ) {
				if ($render ) {
					# Raw and unchecked for validity.
					$html_content[$marker] = $content;
				} else {
					$html_content[$marker] = '<html>'.$content.'</html>';
				}
			}
		}

		# nowiki
		$text = Parser::extractTags('nowiki', $text, $nowiki_content, $uniq_prefix);
		foreach( $nowiki_content as $marker => $content ) {
			if( $render ){
				$nowiki_content[$marker] = wfEscapeHTMLTagsOnly( $content );
			} else {
				$nowiki_content[$marker] = '<nowiki>'.$content.'</nowiki>';
			}
		}

		# math
		if( $this->mOptions->getUseTeX() ) {
			$text = Parser::extractTags('math', $text, $math_content, $uniq_prefix);
			foreach( $math_content as $marker => $content ){
				if( $render ) {
					$math_content[$marker] = renderMath( $content );
				} else {
					$math_content[$marker] = '<math>'.$content.'</math>';
				}
			}
		}

		# pre
		$text = Parser::extractTags('pre', $text, $pre_content, $uniq_prefix);
		foreach( $pre_content as $marker => $content ){
			if( $render ){
				$pre_content[$marker] = '<pre>' . wfEscapeHTMLTagsOnly( $content ) . '</pre>';
			} else {
				$pre_content[$marker] = '<pre>'.$content.'</pre>';
			}
		}

		# gallery
		$text = Parser::extractTags('gallery', $text, $gallery_content, $uniq_prefix);
		foreach( $gallery_content as $marker => $content ) {
			require_once( 'ImageGallery.php' );
			if ( $render ) {
				$gallery_content[$marker] = Parser::renderImageGallery( $content );
			} else {
				$gallery_content[$marker] = '<gallery>'.$content.'</gallery>';
			}
		}

		# Comments
		if($stripcomments) {
			$text = Parser::extractTags(STRIP_COMMENTS, $text, $comment_content, $uniq_prefix);
			foreach( $comment_content as $marker => $content ){
				$comment_content[$marker] = '<!--'.$content.'-->';
			}
		}

		# Extensions
		foreach ( $this->mTagHooks as $tag => $callback ) {
			$ext_content[$tag] = array();
			$text = Parser::extractTagsAndParams( $tag, $text, $ext_content[$tag],
				$ext_tags[$tag], $ext_params[$tag], $uniq_prefix );
			foreach( $ext_content[$tag] as $marker => $content ) {
				$full_tag = $ext_tags[$tag][$marker];
				$params = $ext_params[$tag][$marker];
				if ( $render ) {
					$ext_content[$tag][$marker] = call_user_func_array( $callback, array( $content, $params, &$this ) );;
				} else {
					$ext_content[$tag][$marker] = "$full_tag$content</$tag>";
				}
			}
		}

		# Merge state with the pre-existing state, if there is one
		if ( $state ) {
			$state['html'] = $state['html'] + $html_content;
			$state['nowiki'] = $state['nowiki'] + $nowiki_content;
			$state['math'] = $state['math'] + $math_content;
			$state['pre'] = $state['pre'] + $pre_content;
			$state['comment'] = $state['comment'] + $comment_content;
			$state['gallery'] = $state['gallery'] + $gallery_content;

			foreach( $ext_content as $tag => $array ) {
				if ( array_key_exists( $tag, $state ) ) {
					$state[$tag] = $state[$tag] + $array;
				}
			}
		} else {
			$state = array(
			  'html' => $html_content,
			  'nowiki' => $nowiki_content,
			  'math' => $math_content,
			  'pre' => $pre_content,
			  'comment' => $comment_content,
			  'gallery' => $gallery_content,
			) + $ext_content;
		}
		return $text;
	}

	/**
	 * restores pre, math, and hiero removed by strip()
	 *
	 * always call unstripNoWiki() after this one
	 * @access private
	 */
	function unstrip( $text, &$state ) {
		# Must expand in reverse order, otherwise nested tags will be corrupted
		foreach( array_reverse( $state, true ) as $tag => $contentDict ) {
			if( $tag != 'nowiki' && $tag != 'html' ) {
				foreach( array_reverse( $contentDict, true ) as $uniq => $content ) {
					$text = str_replace( $uniq, $content, $text );
				}
			}
		}

		return $text;
	}

	/**
	 * always call this after unstrip() to preserve the order
	 *
	 * @access private
	 */
	function unstripNoWiki( $text, &$state ) {
		# Must expand in reverse order, otherwise nested tags will be corrupted
		for ( $content = end($state['nowiki']); $content !== false; $content = prev( $state['nowiki'] ) ) {
			$text = str_replace( key( $state['nowiki'] ), $content, $text );
		}

		global $wgRawHtml;
		if ($wgRawHtml) {
			for ( $content = end($state['html']); $content !== false; $content = prev( $state['html'] ) ) {
				$text = str_replace( key( $state['html'] ), $content, $text );
			}
		}

		return $text;
	}

	/**
	 * Add an item to the strip state
	 * Returns the unique tag which must be inserted into the stripped text
	 * The tag will be replaced with the original text in unstrip()
	 *
	 * @access private
	 */
	function insertStripItem( $text, &$state ) {
		$rnd = $this->mUniqPrefix . '-item' . Parser::getRandomString();
		if ( !$state ) {
			$state = array(
			  'html' => array(),
			  'nowiki' => array(),
			  'math' => array(),
			  'pre' => array(),
			  'comment' => array(),
			  'gallery' => array(),
			);
		}
		$state['item'][$rnd] = $text;
		return $rnd;
	}

	/**
	 * Interface with html tidy, used if $wgUseTidy = true.
	 * If tidy isn't able to correct the markup, the original will be
	 * returned in all its glory with a warning comment appended.
	 *
	 * Either the external tidy program or the in-process tidy extension
	 * will be used depending on availability. Override the default
	 * $wgTidyInternal setting to disable the internal if it's not working.
	 *
	 * @param string $text Hideous HTML input
	 * @return string Corrected HTML output
	 * @access public
	 * @static
	 */
	function tidy( $text ) {
		global $wgTidyInternal;
		$wrappedtext = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'.
' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html>'.
'<head><title>test</title></head><body>'.$text.'</body></html>';
		if( $wgTidyInternal ) {
			$correctedtext = Parser::internalTidy( $wrappedtext );
		} else {
			$correctedtext = Parser::externalTidy( $wrappedtext );
		}
		if( is_null( $correctedtext ) ) {
			wfDebug( "Tidy error detected!\n" );
			return $text . "\n<!-- Tidy found serious XHTML errors -->\n";
		}
		return $correctedtext;
	}

	/**
	 * Spawn an external HTML tidy process and get corrected markup back from it.
	 *
	 * @access private
	 * @static
	 */
	function externalTidy( $text ) {
		global $wgTidyConf, $wgTidyBin, $wgTidyOpts;
		$fname = 'Parser::externalTidy';
		wfProfileIn( $fname );

		$cleansource = '';
		$opts = ' -utf8';

		$descriptorspec = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
			2 => array('file', '/dev/null', 'a')
		);
		$pipes = array();
		$process = proc_open("$wgTidyBin -config $wgTidyConf $wgTidyOpts$opts", $descriptorspec, $pipes);
		if (is_resource($process)) {
			fwrite($pipes[0], $text);
			fclose($pipes[0]);
			while (!feof($pipes[1])) {
				$cleansource .= fgets($pipes[1], 1024);
			}
			fclose($pipes[1]);
			proc_close($process);
		}

		wfProfileOut( $fname );

		if( $cleansource == '' && $text != '') {
			// Some kind of error happened, so we couldn't get the corrected text.
			// Just give up; we'll use the source text and append a warning.
			return null;
		} else {
			return $cleansource;
		}
	}

	/**
	 * Use the HTML tidy PECL extension to use the tidy library in-process,
	 * saving the overhead of spawning a new process. Currently written to
	 * the PHP 4.3.x version of the extension, may not work on PHP 5.
	 *
	 * 'pear install tidy' should be able to compile the extension module.
	 *
	 * @access private
	 * @static
	 */
	function internalTidy( $text ) {
		global $wgTidyConf;
		$fname = 'Parser::internalTidy';
		wfProfileIn( $fname );

		tidy_load_config( $wgTidyConf );
		tidy_set_encoding( 'utf8' );
		tidy_parse_string( $text );
		tidy_clean_repair();
		if( tidy_get_status() == 2 ) {
			// 2 is magic number for fatal error
			// http://www.php.net/manual/en/function.tidy-get-status.php
			$cleansource = null;
		} else {
			$cleansource = tidy_get_output();
		}
		wfProfileOut( $fname );
		return $cleansource;
	}

	/**
	 * parse the wiki syntax used to render tables
	 *
	 * @access private
	 */
	function doTableStuff ( $t ) {
		$fname = 'Parser::doTableStuff';
		wfProfileIn( $fname );

		$t = explode ( "\n" , $t ) ;
		$td = array () ; # Is currently a td tag open?
		$ltd = array () ; # Was it TD or TH?
		$tr = array () ; # Is currently a tr tag open?
		$ltr = array () ; # tr attributes
		$indent_level = 0; # indent level of the table
		foreach ( $t AS $k => $x )
		{
			$x = trim ( $x ) ;
			$fc = substr ( $x , 0 , 1 ) ;
			if ( preg_match( '/^(:*)\{\|(.*)$/', $x, $matches ) ) {
				$indent_level = strlen( $matches[1] );
				
				$attributes = $this->unstripForHTML( $matches[2] );

				$t[$k] = str_repeat( '<dl><dd>', $indent_level ) .
					'<table' . Sanitizer::fixTagAttributes ( $attributes, 'table' ) . '>' ;
				array_push ( $td , false ) ;
				array_push ( $ltd , '' ) ;
				array_push ( $tr , false ) ;
				array_push ( $ltr , '' ) ;
			}
			else if ( count ( $td ) == 0 ) { } # Don't do any of the following
			else if ( '|}' == substr ( $x , 0 , 2 ) ) {
				$z = "</table>" . substr ( $x , 2);
				$l = array_pop ( $ltd ) ;
				if ( array_pop ( $tr ) ) $z = '</tr>' . $z ;
				if ( array_pop ( $td ) ) $z = '</'.$l.'>' . $z ;
				array_pop ( $ltr ) ;
				$t[$k] = $z . str_repeat( '</dd></dl>', $indent_level );
			}
			else if ( '|-' == substr ( $x , 0 , 2 ) ) { # Allows for |---------------
				$x = substr ( $x , 1 ) ;
				while ( $x != '' && substr ( $x , 0 , 1 ) == '-' ) $x = substr ( $x , 1 ) ;
				$z = '' ;
				$l = array_pop ( $ltd ) ;
				if ( array_pop ( $tr ) ) $z = '</tr>' . $z ;
				if ( array_pop ( $td ) ) $z = '</'.$l.'>' . $z ;
				array_pop ( $ltr ) ;
				$t[$k] = $z ;
				array_push ( $tr , false ) ;
				array_push ( $td , false ) ;
				array_push ( $ltd , '' ) ;
				$attributes = $this->unstripForHTML( $x );
				array_push ( $ltr , Sanitizer::fixTagAttributes ( $attributes, 'tr' ) ) ;
			}
			else if ( '|' == $fc || '!' == $fc || '|+' == substr ( $x , 0 , 2 ) ) { # Caption
				# $x is a table row
				if ( '|+' == substr ( $x , 0 , 2 ) ) {
					$fc = '+' ;
					$x = substr ( $x , 1 ) ;
				}
				$after = substr ( $x , 1 ) ;
				if ( $fc == '!' ) $after = str_replace ( '!!' , '||' , $after ) ;
				$after = explode ( '||' , $after ) ;
				$t[$k] = '' ;

				# Loop through each table cell
				foreach ( $after AS $theline )
				{
					$z = '' ;
					if ( $fc != '+' )
					{
						$tra = array_pop ( $ltr ) ;
						if ( !array_pop ( $tr ) ) $z = '<tr'.$tra.">\n" ;
						array_push ( $tr , true ) ;
						array_push ( $ltr , '' ) ;
					}

					$l = array_pop ( $ltd ) ;
					if ( array_pop ( $td ) ) $z = '</'.$l.'>' . $z ;
					if ( $fc == '|' ) $l = 'td' ;
					else if ( $fc == '!' ) $l = 'th' ;
					else if ( $fc == '+' ) $l = 'caption' ;
					else $l = '' ;
					array_push ( $ltd , $l ) ;

					# Cell parameters
					$y = explode ( '|' , $theline , 2 ) ;
					# Note that a '|' inside an invalid link should not
					# be mistaken as delimiting cell parameters
					if ( strpos( $y[0], '[[' ) !== false ) {
						$y = array ($theline);
					}
					if ( count ( $y ) == 1 )
						$y = "{$z}<{$l}>{$y[0]}" ;
					else {
						$attributes = $this->unstripForHTML( $y[0] );
						$y = "{$z}<{$l}".Sanitizer::fixTagAttributes($attributes, $l).">{$y[1]}" ;
					}
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
		wfProfileOut( $fname );
		return $t ;
	}

	/**
	 * Helper function for parse() that transforms wiki markup into
	 * HTML. Only called for $mOutputType == OT_HTML.
	 *
	 * @access private
	 */
	function internalParse( $text ) {
		global $wgContLang;
		$args = array();
		$isMain = true;
		$fname = 'Parser::internalParse';
		wfProfileIn( $fname );

		# Remove <noinclude> tags and <includeonly> sections
		$text = strtr( $text, array( '<noinclude>' => '', '</noinclude>' => '') );
		$text = preg_replace( '/<includeonly>.*?<\/includeonly>/s', '', $text );

		$text = Sanitizer::removeHTMLtags( $text, array( &$this, 'attributeStripCallback' ) );
		$text = $this->replaceVariables( $text, $args );

		$text = preg_replace( '/(^|\n)-----*/', '\\1<hr />', $text );

		$text = $this->doHeadings( $text );
		if($this->mOptions->getUseDynamicDates()) {
			$df =& DateFormatter::getInstance();
			$text = $df->reformat( $this->mOptions->getDateFormat(), $text );
		}
		$text = $this->doAllQuotes( $text );
		$text = $this->replaceInternalLinks( $text );
		$text = $this->replaceExternalLinks( $text );

		# replaceInternalLinks may sometimes leave behind
		# absolute URLs, which have to be masked to hide them from replaceExternalLinks
		$text = str_replace($this->mUniqPrefix."NOPARSE", "", $text);

		$text = $this->doMagicLinks( $text );
		$text = $this->doTableStuff( $text );
		$text = $this->formatHeadings( $text, $isMain );

		$regex = '/<!--IW_TRANSCLUDE (\d+)-->/';
		$text = preg_replace_callback($regex, array(&$this, 'scarySubstitution'), $text);

		wfProfileOut( $fname );
		return $text;
	}

	function scarySubstitution($matches) {
#		return "[[".$matches[0]."]]";
		return $this->mIWTransData[(int)$matches[0]];
	}

	/**
	 * Replace special strings like "ISBN xxx" and "RFC xxx" with
	 * magic external links.
	 *
	 * @access private
	 */
	function &doMagicLinks( &$text ) {
		$text = $this->magicISBN( $text );
		$text = $this->magicRFC( $text, 'RFC ', 'rfcurl' );
		$text = $this->magicRFC( $text, 'PMID ', 'pubmedurl' );
		return $text;
	}

	/**
	 * Parse ^^ tokens and return html
	 *
	 * @access private
	 */
	function doExponent( $text ) {
		$fname = 'Parser::doExponent';
		wfProfileIn( $fname );
		$text = preg_replace('/\^\^(.*)\^\^/','<small><sup>\\1</sup></small>', $text);
		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * Parse headers and return html
	 *
	 * @access private
	 */
	function doHeadings( $text ) {
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

	/**
	 * Replace single quotes with HTML markup
	 * @access private
	 * @return string the altered text
	 */
	function doAllQuotes( $text ) {
		$fname = 'Parser::doAllQuotes';
		wfProfileIn( $fname );
		$outtext = '';
		$lines = explode( "\n", $text );
		foreach ( $lines as $line ) {
			$outtext .= $this->doQuotes ( $line ) . "\n";
		}
		$outtext = substr($outtext, 0,-1);
		wfProfileOut( $fname );
		return $outtext;
	}

	/**
	 * Helper function for doAllQuotes()
	 * @access private
	 */
	function doQuotes( $text ) {
		$arr = preg_split( "/(''+)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE );
		if ( count( $arr ) == 1 )
			return $text;
		else
		{
			# First, do some preliminary work. This may shift some apostrophes from
			# being mark-up to being text. It also counts the number of occurrences
			# of bold and italics mark-ups.
			$i = 0;
			$numbold = 0;
			$numitalics = 0;
			foreach ( $arr as $r )
			{
				if ( ( $i % 2 ) == 1 )
				{
					# If there are ever four apostrophes, assume the first is supposed to
					# be text, and the remaining three constitute mark-up for bold text.
					if ( strlen( $arr[$i] ) == 4 )
					{
						$arr[$i-1] .= "'";
						$arr[$i] = "'''";
					}
					# If there are more than 5 apostrophes in a row, assume they're all
					# text except for the last 5.
					else if ( strlen( $arr[$i] ) > 5 )
					{
						$arr[$i-1] .= str_repeat( "'", strlen( $arr[$i] ) - 5 );
						$arr[$i] = "'''''";
					}
					# Count the number of occurrences of bold and italics mark-ups.
					# We are not counting sequences of five apostrophes.
					if ( strlen( $arr[$i] ) == 2 ) $numitalics++;  else
					if ( strlen( $arr[$i] ) == 3 ) $numbold++;     else
					if ( strlen( $arr[$i] ) == 5 ) { $numitalics++; $numbold++; }
				}
				$i++;
			}

			# If there is an odd number of both bold and italics, it is likely
			# that one of the bold ones was meant to be an apostrophe followed
			# by italics. Which one we cannot know for certain, but it is more
			# likely to be one that has a single-letter word before it.
			if ( ( $numbold % 2 == 1 ) && ( $numitalics % 2 == 1 ) )
			{
				$i = 0;
				$firstsingleletterword = -1;
				$firstmultiletterword = -1;
				$firstspace = -1;
				foreach ( $arr as $r )
				{
					if ( ( $i % 2 == 1 ) and ( strlen( $r ) == 3 ) )
					{
						$x1 = substr ($arr[$i-1], -1);
						$x2 = substr ($arr[$i-1], -2, 1);
						if ($x1 == ' ') {
							if ($firstspace == -1) $firstspace = $i;
						} else if ($x2 == ' ') {
							if ($firstsingleletterword == -1) $firstsingleletterword = $i;
						} else {
							if ($firstmultiletterword == -1) $firstmultiletterword = $i;
						}
					}
					$i++;
				}

				# If there is a single-letter word, use it!
				if ($firstsingleletterword > -1)
				{
					$arr [ $firstsingleletterword ] = "''";
					$arr [ $firstsingleletterword-1 ] .= "'";
				}
				# If not, but there's a multi-letter word, use that one.
				else if ($firstmultiletterword > -1)
				{
					$arr [ $firstmultiletterword ] = "''";
					$arr [ $firstmultiletterword-1 ] .= "'";
				}
				# ... otherwise use the first one that has neither.
				# (notice that it is possible for all three to be -1 if, for example,
				# there is only one pentuple-apostrophe in the line)
				else if ($firstspace > -1)
				{
					$arr [ $firstspace ] = "''";
					$arr [ $firstspace-1 ] .= "'";
				}
			}

			# Now let's actually convert our apostrophic mush to HTML!
			$output = '';
			$buffer = '';
			$state = '';
			$i = 0;
			foreach ($arr as $r)
			{
				if (($i % 2) == 0)
				{
					if ($state == 'both')
						$buffer .= $r;
					else
						$output .= $r;
				}
				else
				{
					if (strlen ($r) == 2)
					{
						if ($state == 'i')
						{ $output .= '</i>'; $state = ''; }
						else if ($state == 'bi')
						{ $output .= '</i>'; $state = 'b'; }
						else if ($state == 'ib')
						{ $output .= '</b></i><b>'; $state = 'b'; }
						else if ($state == 'both')
						{ $output .= '<b><i>'.$buffer.'</i>'; $state = 'b'; }
						else # $state can be 'b' or ''
						{ $output .= '<i>'; $state .= 'i'; }
					}
					else if (strlen ($r) == 3)
					{
						if ($state == 'b')
						{ $output .= '</b>'; $state = ''; }
						else if ($state == 'bi')
						{ $output .= '</i></b><i>'; $state = 'i'; }
						else if ($state == 'ib')
						{ $output .= '</b>'; $state = 'i'; }
						else if ($state == 'both')
						{ $output .= '<i><b>'.$buffer.'</b>'; $state = 'i'; }
						else # $state can be 'i' or ''
						{ $output .= '<b>'; $state .= 'b'; }
					}
					else if (strlen ($r) == 5)
					{
						if ($state == 'b')
						{ $output .= '</b><i>'; $state = 'i'; }
						else if ($state == 'i')
						{ $output .= '</i><b>'; $state = 'b'; }
						else if ($state == 'bi')
						{ $output .= '</i></b>'; $state = ''; }
						else if ($state == 'ib')
						{ $output .= '</b></i>'; $state = ''; }
						else if ($state == 'both')
						{ $output .= '<i><b>'.$buffer.'</b></i>'; $state = ''; }
						else # ($state == '')
						{ $buffer = ''; $state = 'both'; }
					}
				}
				$i++;
			}
			# Now close all remaining tags.  Notice that the order is important.
			if ($state == 'b' || $state == 'ib')
				$output .= '</b>';
			if ($state == 'i' || $state == 'bi' || $state == 'ib')
				$output .= '</i>';
			if ($state == 'bi')
				$output .= '</b>';
			if ($state == 'both')
				$output .= '<b><i>'.$buffer.'</i></b>';
			return $output;
		}
	}

	/**
	 * Replace external links
	 *
 	 * Note: this is all very hackish and the order of execution matters a lot.
	 * Make sure to run maintenance/parserTests.php if you change this code.
	 *
	 * @access private
	 */
	function replaceExternalLinks( $text ) {
		global $wgContLang;
		$fname = 'Parser::replaceExternalLinks';
		wfProfileIn( $fname );

		$sk =& $this->mOptions->getSkin();

		$bits = preg_split( EXT_LINK_BRACKETED, $text, -1, PREG_SPLIT_DELIM_CAPTURE );

		$s = $this->replaceFreeExternalLinks( array_shift( $bits ) );

		$i = 0;
		while ( $i<count( $bits ) ) {
			$url = $bits[$i++];
			$protocol = $bits[$i++];
			$text = $bits[$i++];
			$trail = $bits[$i++];

			# The characters '<' and '>' (which were escaped by
			# removeHTMLtags()) should not be included in
			# URLs, per RFC 2396.
			if (preg_match('/&(lt|gt);/', $url, $m2, PREG_OFFSET_CAPTURE)) {
				$text = substr($url, $m2[0][1]) . ' ' . $text;
				$url = substr($url, 0, $m2[0][1]);
			}

			# If the link text is an image URL, replace it with an <img> tag
			# This happened by accident in the original parser, but some people used it extensively
			$img = $this->maybeMakeExternalImage( $text );
			if ( $img !== false ) {
				$text = $img;
			}

			$dtrail = '';

			# Set linktype for CSS - if URL==text, link is essentially free
			$linktype = ($text == $url) ? 'free' : 'text';

			# No link text, e.g. [http://domain.tld/some.link]
			if ( $text == '' ) {
				# Autonumber if allowed
				if ( strpos( HTTP_PROTOCOLS, str_replace('/','\/', $protocol) ) !== false ) {
					$text = '[' . ++$this->mAutonumber . ']';
					$linktype = 'autonumber';
				} else {
					# Otherwise just use the URL
					$text = htmlspecialchars( $url );
					$linktype = 'free';
				}
			} else {
				# Have link text, e.g. [http://domain.tld/some.link text]s
				# Check for trail
				list( $dtrail, $trail ) = Linker::splitTrail( $trail );
			}

			$text = $wgContLang->markNoConversion($text);

			# Replace &amp; from obsolete syntax with &.
			# All HTML entities will be escaped by makeExternalLink()
			# or maybeMakeExternalImage()
			$url = str_replace( '&amp;', '&', $url );

			# Process the trail (i.e. everything after this link up until start of the next link),
			# replacing any non-bracketed links
			$trail = $this->replaceFreeExternalLinks( $trail );


			# Use the encoded URL
			# This means that users can paste URLs directly into the text
			# Funny characters like &ouml; aren't valid in URLs anyway
			# This was changed in August 2004
			$s .= $sk->makeExternalLink( $url, $text, false, $linktype ) . $dtrail . $trail;
		}

		wfProfileOut( $fname );
		return $s;
	}

	/**
	 * Replace anything that looks like a URL with a link
	 * @access private
	 */
	function replaceFreeExternalLinks( $text ) {
		global $wgUrlProtocols;
		global $wgContLang;
		$fname = 'Parser::replaceFreeExternalLinks';
		wfProfileIn( $fname );

		$bits = preg_split( '/(\b(?:'.$wgUrlProtocols.'))/S', $text, -1, PREG_SPLIT_DELIM_CAPTURE );
		$s = array_shift( $bits );
		$i = 0;

		$sk =& $this->mOptions->getSkin();

		while ( $i < count( $bits ) ){
			$protocol = $bits[$i++];
			$remainder = $bits[$i++];

			if ( preg_match( '/^('.EXT_LINK_URL_CLASS.'+)(.*)$/s', $remainder, $m ) ) {
				# Found some characters after the protocol that look promising
				$url = $protocol . $m[1];
				$trail = $m[2];

				# The characters '<' and '>' (which were escaped by
				# removeHTMLtags()) should not be included in
				# URLs, per RFC 2396.
				if (preg_match('/&(lt|gt);/', $url, $m2, PREG_OFFSET_CAPTURE)) {
					$trail = substr($url, $m2[0][1]) . $trail;
					$url = substr($url, 0, $m2[0][1]);
				}

				# Move trailing punctuation to $trail
				$sep = ',;\.:!?';
				# If there is no left bracket, then consider right brackets fair game too
				if ( strpos( $url, '(' ) === false ) {
					$sep .= ')';
				}

				$numSepChars = strspn( strrev( $url ), $sep );
				if ( $numSepChars ) {
					$trail = substr( $url, -$numSepChars ) . $trail;
					$url = substr( $url, 0, -$numSepChars );
				}

				# Replace &amp; from obsolete syntax with &.
				# All HTML entities will be escaped by makeExternalLink()
				# or maybeMakeExternalImage()
				$url = str_replace( '&amp;', '&', $url );

				# Is this an external image?
				$text = $this->maybeMakeExternalImage( $url );
				if ( $text === false ) {
					# Not an image, make a link
					$text = $sk->makeExternalLink( $url, $wgContLang->markNoConversion($url), true, 'free' );
				}
				$s .= $text . $trail;
			} else {
				$s .= $protocol . $remainder;
			}
		}
		wfProfileOut( $fname );
		return $s;
	}

	/**
	 * make an image if it's allowed
	 * @access private
	 */
	function maybeMakeExternalImage( $url ) {
		$sk =& $this->mOptions->getSkin();
		$text = false;
		if ( $this->mOptions->getAllowExternalImages() ) {
			if ( preg_match( EXT_IMAGE_REGEX, $url ) ) {
				# Image found
				$text = $sk->makeExternalImage( htmlspecialchars( $url ) );
			}
		}
		return $text;
	}

	/**
	 * Process [[ ]] wikilinks
	 *
	 * @access private
	 */
	function replaceInternalLinks( $s ) {
		global $wgContLang, $wgLinkCache, $wgUrlProtocols;
		static $fname = 'Parser::replaceInternalLinks' ;

		wfProfileIn( $fname );

		wfProfileIn( $fname.'-setup' );
		static $tc = FALSE;
		# the % is needed to support urlencoded titles as well
		if ( !$tc ) { $tc = Title::legalChars() . '#%'; }

		$sk =& $this->mOptions->getSkin();

		#split the entire text string on occurences of [[
		$a = explode( '[[', ' ' . $s );
		#get the first element (all text up to first [[), and remove the space we added
		$s = array_shift( $a );
		$s = substr( $s, 1 );

		# Match a link having the form [[namespace:link|alternate]]trail
		static $e1 = FALSE;
		if ( !$e1 ) { $e1 = "/^([{$tc}]+)(?:\\|(.+?))?]](.*)\$/sD"; }
		# Match cases where there is no "]]", which might still be images
		static $e1_img = FALSE;
		if ( !$e1_img ) { $e1_img = "/^([{$tc}]+)\\|(.*)\$/sD"; }
		# Match the end of a line for a word that's not followed by whitespace,
		# e.g. in the case of 'The Arab al[[Razi]]', 'al' will be matched
		$e2 = wfMsgForContent( 'linkprefix' );

		$useLinkPrefixExtension = $wgContLang->linkPrefixExtension();

		if( is_null( $this->mTitle ) ) {
			wfDebugDieBacktrace( 'nooo' );
		}
		$nottalk = !$this->mTitle->isTalkPage();

		if ( $useLinkPrefixExtension ) {
			if ( preg_match( $e2, $s, $m ) ) {
				$first_prefix = $m[2];
			} else {
				$first_prefix = false;
			}
		} else {
			$prefix = '';
		}

		$selflink = $this->mTitle->getPrefixedText();
		wfProfileOut( $fname.'-setup' );

		$checkVariantLink = sizeof($wgContLang->getVariants())>1;
		$useSubpages = $this->areSubpagesAllowed();

		# Loop for each link
		for ($k = 0; isset( $a[$k] ); $k++) {
			$line = $a[$k];
			if ( $useLinkPrefixExtension ) {
				wfProfileIn( $fname.'-prefixhandling' );
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
				wfProfileOut( $fname.'-prefixhandling' );
			}

			$might_be_img = false;

			if ( preg_match( $e1, $line, $m ) ) { # page with normal text or alt
				$text = $m[2];
				# If we get a ] at the beginning of $m[3] that means we have a link that's something like:
				# [[Image:Foo.jpg|[http://example.com desc]]] <- having three ] in a row fucks up,
				# the real problem is with the $e1 regex
				# See bug 1300.
				#
				# Still some problems for cases where the ] is meant to be outside punctuation,
				# and no image is in sight. See bug 2095.
				#
				if( $text !== '' && preg_match( "/^\](.*)/s", $m[3], $n ) ) {
					$text .= ']'; # so that replaceExternalLinks($text) works later
					$m[3] = $n[1];
				}
				# fix up urlencoded title texts
				//if(preg_match('/%/', $m[1] )) $m[1] = urldecode($m[1]);
				if(preg_match('/%/', $m[1] )) 
					# Should anchors '#' also be rejected?
					$m[1] = str_replace( array('<', '>'), array('&lt;', '&gt;'), urldecode($m[1]) );
				$trail = $m[3];
			} elseif( preg_match($e1_img, $line, $m) ) { # Invalid, but might be an image with a link in its caption
				$might_be_img = true;
				$text = $m[2];
				if(preg_match('/%/', $m[1] )) 
					# Should anchors '#' also be rejected?
					$m[1] = str_replace( array('<', '>'), array('&lt;', '&gt;'), urldecode($m[1]) );
				$trail = "";
			} else { # Invalid form; output directly
				$s .= $prefix . '[[' . $line ;
				continue;
			}

			# Don't allow internal links to pages containing
			# PROTO: where PROTO is a valid URL protocol; these
			# should be external links.
			if (preg_match('/^(\b(?:'.$wgUrlProtocols.'))/', $m[1])) {
				$s .= $prefix . '[[' . $line ;
				continue;
			}

			# Make subpage if necessary
			if( $useSubpages ) {
				$link = $this->maybeDoSubpageLink( $m[1], $text );
			} else {
				$link = $m[1];
			}

			$noforce = (substr($m[1], 0, 1) != ':');
			if (!$noforce) {
				# Strip off leading ':'
				$link = substr($link, 1);
			}

			$nt = Title::newFromText( $this->unstripNoWiki($link, $this->mStripState) );
			if( !$nt ) {
				$s .= $prefix . '[[' . $line;
				continue;
			}

			#check other language variants of the link
			#if the article does not exist
			if( $checkVariantLink
			    && $nt->getArticleID() == 0 ) {
				$wgContLang->findVariantLink($link, $nt);
			}

			$ns = $nt->getNamespace();
			$iw = $nt->getInterWiki();

			if ($might_be_img) { # if this is actually an invalid link
				if ($ns == NS_IMAGE && $noforce) { #but might be an image
					$found = false;
					while (isset ($a[$k+1]) ) {
						#look at the next 'line' to see if we can close it there
						$spliced = array_splice( $a, $k + 1, 1 );
						$next_line = array_shift( $spliced );
						if( preg_match("/^(.*?]].*?)]](.*)$/sD", $next_line, $m) ) {
						# the first ]] closes the inner link, the second the image
							$found = true;
							$text .= '[[' . $m[1];
							$trail = $m[2];
							break;
						} elseif( preg_match("/^.*?]].*$/sD", $next_line, $m) ) {
							#if there's exactly one ]] that's fine, we'll keep looking
							$text .= '[[' . $m[0];
						} else {
							#if $next_line is invalid too, we need look no further
							$text .= '[[' . $next_line;
							break;
						}
					}
					if ( !$found ) {
						# we couldn't find the end of this imageLink, so output it raw
						#but don't ignore what might be perfectly normal links in the text we've examined
						$text = $this->replaceInternalLinks($text);
						$s .= $prefix . '[[' . $link . '|' . $text;
						# note: no $trail, because without an end, there *is* no trail
						continue;
					}
				} else { #it's not an image, so output it raw
					$s .= $prefix . '[[' . $link . '|' . $text;
					# note: no $trail, because without an end, there *is* no trail
					continue;
				}
			}

			$wasblank = ( '' == $text );
			if( $wasblank ) $text = $link;


			# Link not escaped by : , create the various objects
			if( $noforce ) {

				# Interwikis
				if( $iw && $this->mOptions->getInterwikiMagic() && $nottalk && $wgContLang->getLanguageName( $iw ) ) {
					array_push( $this->mOutput->mLanguageLinks, $nt->getFullText() );
					$s = rtrim($s . "\n");
					$s .= trim($prefix . $trail, "\n") == '' ? '': $prefix . $trail;
					continue;
				}

				if ( $ns == NS_IMAGE ) {
					wfProfileIn( "$fname-image" );
					if ( !wfIsBadImage( $nt->getDBkey() ) ) {
						# recursively parse links inside the image caption
						# actually, this will parse them in any other parameters, too,
						# but it might be hard to fix that, and it doesn't matter ATM
						$text = $this->replaceExternalLinks($text);
						$text = $this->replaceInternalLinks($text);

						# cloak any absolute URLs inside the image markup, so replaceExternalLinks() won't touch them
						$s .= $prefix . preg_replace( "/\b($wgUrlProtocols)/", "{$this->mUniqPrefix}NOPARSE$1", $this->makeImage( $nt, $text) ) . $trail;
						$wgLinkCache->addImageLinkObj( $nt );

						wfProfileOut( "$fname-image" );
						continue;
					}
					wfProfileOut( "$fname-image" );

				}

				if ( $ns == NS_CATEGORY ) {
					wfProfileIn( "$fname-category" );
					$t = $wgContLang->convertHtml( $nt->getText() );
					$s = rtrim($s . "\n"); # bug 87

					$wgLinkCache->suspend(); # Don't save in links/brokenlinks
					$t = $sk->makeLinkObj( $nt, $t, '', '' , $prefix );
					$wgLinkCache->resume();

					if ( $wasblank ) {
						if ( $this->mTitle->getNamespace() == NS_CATEGORY ) {
							$sortkey = $this->mTitle->getText();
						} else {
							$sortkey = $this->mTitle->getPrefixedText();
						}
					} else {
						$sortkey = $text;
					}
					$sortkey = $wgContLang->convertCategoryKey( $sortkey );
					$wgLinkCache->addCategoryLinkObj( $nt, $sortkey );
					$this->mOutput->addCategoryLink( $t );

					/**
					 * Strip the whitespace Category links produce, see bug 87
					 * @todo We might want to use trim($tmp, "\n") here.
					 */
					$s .= trim($prefix . $trail, "\n") == '' ? '': $prefix . $trail;

					wfProfileOut( "$fname-category" );
					continue;
				}
			}

			if( ( $nt->getPrefixedText() === $selflink ) &&
			    ( $nt->getFragment() === '' ) ) {
				# Self-links are handled specially; generally de-link and change to bold.
				$s .= $prefix . $sk->makeSelfLinkObj( $nt, $text, '', $trail );
				continue;
			}

			# Special and Media are pseudo-namespaces; no pages actually exist in them
			if( $ns == NS_MEDIA ) {
				$link = $sk->makeMediaLinkObj( $nt, $text );
				# Cloak with NOPARSE to avoid replacement in replaceExternalLinks
				$s .= $prefix . str_replace( 'http://', "http{$this->mUniqPrefix}NOPARSE://", $link ) . $trail;
				$wgLinkCache->addImageLinkObj( $nt );
				continue;
			} elseif( $ns == NS_SPECIAL ) {
				$s .= $prefix . $sk->makeKnownLinkObj( $nt, $text, '', $trail );
				continue;
			}
			if( !$nt->isExternal() && $nt->isAlwaysKnown() ) {
				/**
				 * Skip lookups for special pages and self-links.
				 * External interwiki links are not included here because
				 * the HTTP urls would break output in the next parse step;
				 * they will have placeholders kept.
				 */
				$s .= $sk->makeKnownLinkObj( $nt, $text, '', $trail, $prefix );
			} else {
				/**
				 * Add a link placeholder
				 * Later, this will be replaced by a real link, after the existence or
				 * non-existence of all the links is known
				 */
				$s .= $this->makeLinkHolder( $nt, $text, '', $trail, $prefix );
			}
		}
		wfProfileOut( $fname );
		return $s;
	}

	/**
	 * Make a link placeholder. The text returned can be later resolved to a real link with
	 * replaceLinkHolders(). This is done for two reasons: firstly to avoid further
	 * parsing of interwiki links, and secondly to allow all extistence checks and
	 * article length checks (for stub links) to be bundled into a single query.
	 *
	 */
	function makeLinkHolder( &$nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		if ( ! is_object($nt) ) {
			# Fail gracefully
			$retVal = "<!-- ERROR -->{$prefix}{$text}{$trail}";
		} else {
			# Separate the link trail from the rest of the link
			list( $inside, $trail ) = Linker::splitTrail( $trail );

			if ( $nt->isExternal() ) {
				$nr = array_push( $this->mInterwikiLinkHolders['texts'], $prefix.$text.$inside );
				$this->mInterwikiLinkHolders['titles'][] = $nt;
				$retVal = '<!--IWLINK '. ($nr-1) ."-->{$trail}";
			} else {
				$nr = array_push( $this->mLinkHolders['namespaces'], $nt->getNamespace() );
				$this->mLinkHolders['dbkeys'][] = $nt->getDBkey();
				$this->mLinkHolders['queries'][] = $query;
				$this->mLinkHolders['texts'][] = $prefix.$text.$inside;
				$this->mLinkHolders['titles'][] = $nt;

				$retVal = '<!--LINK '. ($nr-1) ."-->{$trail}";
			}
		}
		return $retVal;
	}

	/**
	 * Return true if subpage links should be expanded on this page.
	 * @return bool
	 */
	function areSubpagesAllowed() {
		# Some namespaces don't allow subpages
		global $wgNamespacesWithSubpages;
		return !empty($wgNamespacesWithSubpages[$this->mTitle->getNamespace()]);
	}

	/**
	 * Handle link to subpage if necessary
	 * @param string $target the source of the link
	 * @param string &$text the link text, modified as necessary
	 * @return string the full name of the link
	 * @access private
	 */
	function maybeDoSubpageLink($target, &$text) {
		# Valid link forms:
		# Foobar -- normal
		# :Foobar -- override special treatment of prefix (images, language links)
		# /Foobar -- convert to CurrentPage/Foobar
		# /Foobar/ -- convert to CurrentPage/Foobar, strip the initial / from text
		# ../ -- convert to CurrentPage, from CurrentPage/CurrentSubPage
		# ../Foobar -- convert to CurrentPage/Foobar, from CurrentPage/CurrentSubPage

		$fname = 'Parser::maybeDoSubpageLink';
		wfProfileIn( $fname );
		$ret = $target; # default return value is no change

		# Some namespaces don't allow subpages,
		# so only perform processing if subpages are allowed
		if( $this->areSubpagesAllowed() ) {
			# Look at the first character
			if( $target != '' && $target{0} == '/' ) {
				# / at end means we don't want the slash to be shown
				if( substr( $target, -1, 1 ) == '/' ) {
					$target = substr( $target, 1, -1 );
					$noslash = $target;
				} else {
					$noslash = substr( $target, 1 );
				}

				$ret = $this->mTitle->getPrefixedText(). '/' . trim($noslash);
				if( '' === $text ) {
					$text = $target;
				} # this might be changed for ugliness reasons
			} else {
				# check for .. subpage backlinks
				$dotdotcount = 0;
				$nodotdot = $target;
				while( strncmp( $nodotdot, "../", 3 ) == 0 ) {
					++$dotdotcount;
					$nodotdot = substr( $nodotdot, 3 );
				}
				if($dotdotcount > 0) {
					$exploded = explode( '/', $this->mTitle->GetPrefixedText() );
					if( count( $exploded ) > $dotdotcount ) { # not allowed to go below top level page
						$ret = implode( '/', array_slice( $exploded, 0, -$dotdotcount ) );
						# / at the end means don't show full path
						if( substr( $nodotdot, -1, 1 ) == '/' ) {
							$nodotdot = substr( $nodotdot, 0, -1 );
							if( '' === $text ) {
								$text = $nodotdot;
							}
						}
						$nodotdot = trim( $nodotdot );
						if( $nodotdot != '' ) {
							$ret .= '/' . $nodotdot;
						}
					}
				}
			}
		}

		wfProfileOut( $fname );
		return $ret;
	}

	/**#@+
	 * Used by doBlockLevels()
	 * @access private
	 */
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
	/* private */ function openList( $char ) {
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
			$close = '</dd>';
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

	/* private */ function closeList( $char ) {
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
	/**#@-*/

	/**
	 * Make lists from lines starting with ':', '*', '#', etc.
	 *
	 * @access private
	 * @return string the lists rendered as HTML
	 */
	function doBlockLevels( $text, $linestart ) {
		$fname = 'Parser::doBlockLevels';
		wfProfileIn( $fname );

		# Parsing through the text line by line.  The main thing
		# happening here is handling of block-level elements p, pre,
		# and making lists from lines starting with * # : etc.
		#
		$textLines = explode( "\n", $text );

		$lastPrefix = $output = '';
		$this->mDTopen = $inBlockElem = false;
		$prefixLength = 0;
		$paragraphStack = false;

		if ( !$linestart ) {
			$output .= array_shift( $textLines );
		}
		foreach ( $textLines as $oLine ) {
			$lastPrefixLength = strlen( $lastPrefix );
			$preCloseMatch = preg_match('/<\\/pre/i', $oLine );
			$preOpenMatch = preg_match('/<pre/i', $oLine );
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

				if ( substr( $pref, -1 ) == ';') {
					# The one nasty exception: definition lists work like this:
					# ; title : definition text
					# So we check for : in the remainder text to split up the
					# title and definition, without b0rking links.
					$term = $t2 = '';
					if ($this->findColonNoLinks($t, $term, $t2) !== false) {
						$t = $t2;
						$output .= $term . $this->nextItem( ':' );
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
						if ($this->findColonNoLinks($t, $term, $t2) !== false) {
							$t = $t2;
							$output .= $term . $this->nextItem( ':' );
						}
					}
					++$commonPrefixLength;
				}
				$lastPrefix = $pref2;
			}
			if( 0 == $prefixLength ) {
				wfProfileIn( "$fname-paragraph" );
				# No prefix (not in list)--go to paragraph mode
				// XXX: use a stack for nestable elements like span, table and div
				$openmatch = preg_match('/(<table|<blockquote|<h1|<h2|<h3|<h4|<h5|<h6|<pre|<tr|<p|<ul|<li|<\\/tr|<\\/td|<\\/th)/iS', $t );
				$closematch = preg_match(
					'/(<\\/table|<\\/blockquote|<\\/h1|<\\/h2|<\\/h3|<\\/h4|<\\/h5|<\\/h6|'.
					'<td|<th|<div|<\\/div|<hr|<\\/pre|<\\/p|'.$this->mUniqPrefix.'-pre|<\\/li|<\\/ul)/iS', $t );
				if ( $openmatch or $closematch ) {
					$paragraphStack = false;
					$output .= $this->closeParagraph();
					if ( $preOpenMatch and !$preCloseMatch ) {
						$this->mInPre = true;
					}
					if ( $closematch ) {
						$inBlockElem = false;
					} else {
						$inBlockElem = true;
					}
				} else if ( !$inBlockElem && !$this->mInPre ) {
					if ( ' ' == $t{0} and ( $this->mLastSection == 'pre' or trim($t) != '' ) ) {
						// pre
						if ($this->mLastSection != 'pre') {
							$paragraphStack = false;
							$output .= $this->closeParagraph().'<pre>';
							$this->mLastSection = 'pre';
						}
						$t = substr( $t, 1 );
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
				wfProfileOut( "$fname-paragraph" );
			}
			// somewhere above we forget to get out of pre block (bug 785)
			if($preCloseMatch && $this->mInPre) {
				$this->mInPre = false;
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

	/**
	 * Split up a string on ':', ignoring any occurences inside
	 * <a>..</a> or <span>...</span>
	 * @param string $str the string to split
	 * @param string &$before set to everything before the ':'
	 * @param string &$after set to everything after the ':'
	 * return string the position of the ':', or false if none found
	 */
	function findColonNoLinks($str, &$before, &$after) {
		# I wonder if we should make this count all tags, not just <a>
		# and <span>. That would prevent us from matching a ':' that
		# comes in the middle of italics other such formatting....
		# -- Wil
		$fname = 'Parser::findColonNoLinks';
		wfProfileIn( $fname );
		$pos = 0;
		do {
			$colon = strpos($str, ':', $pos);

			if ($colon !== false) {
				$before = substr($str, 0, $colon);
				$after = substr($str, $colon + 1);

				# Skip any ':' within <a> or <span> pairs
				$a = substr_count($before, '<a');
				$s = substr_count($before, '<span');
				$ca = substr_count($before, '</a>');
				$cs = substr_count($before, '</span>');

				if ($a <= $ca and $s <= $cs) {
					# Tags are balanced before ':'; ok
					break;
				}
				$pos = $colon + 1;
			}
		} while ($colon !== false);
		wfProfileOut( $fname );
		return $colon;
	}

	/**
	 * Return value of a magic variable (like PAGENAME)
	 *
	 * @access private
	 */
	function getVariableValue( $index ) {
		global $wgContLang, $wgSitename, $wgServer, $wgServerName, $wgArticle, $wgScriptPath;

		/**
		 * Some of these require message or data lookups and can be
		 * expensive to check many times.
		 */
		static $varCache = array();
		if( isset( $varCache[$index] ) ) return $varCache[$index];

		switch ( $index ) {
			case MAG_CURRENTMONTH:
				return $varCache[$index] = $wgContLang->formatNum( date( 'm' ) );
			case MAG_CURRENTMONTHNAME:
				return $varCache[$index] = $wgContLang->getMonthName( date('n') );
			case MAG_CURRENTMONTHNAMEGEN:
				return $varCache[$index] = $wgContLang->getMonthNameGen( date('n') );
			case MAG_CURRENTMONTHABBREV:
				return $varCache[$index] = $wgContLang->getMonthAbbreviation( date('n') );
			case MAG_CURRENTDAY:
				return $varCache[$index] = $wgContLang->formatNum( date('j') );
			case MAG_PAGENAME:
				return $this->mTitle->getText();
			case MAG_PAGENAMEE:
				return $this->mTitle->getPartialURL();
			case MAG_REVISIONID:
				return $wgArticle->getRevIdFetched();
			case MAG_NAMESPACE:
				# return Namespace::getCanonicalName($this->mTitle->getNamespace());
				return $wgContLang->getNsText($this->mTitle->getNamespace()); # Patch by Dori
			case MAG_CURRENTDAYNAME:
				return $varCache[$index] = $wgContLang->getWeekdayName( date('w')+1 );
			case MAG_CURRENTYEAR:
				return $varCache[$index] = $wgContLang->formatNum( date( 'Y' ), true );
			case MAG_CURRENTTIME:
				return $varCache[$index] = $wgContLang->time( wfTimestampNow(), false );
			case MAG_CURRENTWEEK:
				return $varCache[$index] = $wgContLang->formatNum( intval( date('W') ) );
			case MAG_CURRENTDOW:
				return $varCache[$index] = $wgContLang->formatNum( date('w') );
			case MAG_NUMBEROFARTICLES:
				return $varCache[$index] = $wgContLang->formatNum( wfNumberOfArticles() );
			case MAG_NUMBEROFFILES:
				return $varCache[$index] = $wgContLang->formatNum( wfNumberOfFiles() );
			case MAG_SITENAME:
				return $wgSitename;
			case MAG_SERVER:
				return $wgServer;
			case MAG_SERVERNAME:
				return $wgServerName;
			case MAG_SCRIPTPATH:
				return $wgScriptPath;
			default:
				return NULL;
		}
	}

	/**
	 * initialise the magic variables (like CURRENTMONTHNAME)
	 *
	 * @access private
	 */
	function initialiseVariables() {
		$fname = 'Parser::initialiseVariables';
		wfProfileIn( $fname );
		global $wgVariableIDs;
		$this->mVariables = array();
		foreach ( $wgVariableIDs as $id ) {
			$mw =& MagicWord::get( $id );
			$mw->addToArray( $this->mVariables, $id );
		}
		wfProfileOut( $fname );
	}

	/**
	 * Replace magic variables, templates, and template arguments
	 * with the appropriate text. Templates are substituted recursively,
	 * taking care to avoid infinite loops.
	 *
	 * Note that the substitution depends on value of $mOutputType:
	 *  OT_WIKI: only {{subst:}} templates
	 *  OT_MSG: only magic variables
	 *  OT_HTML: all templates and magic variables
	 *
	 * @param string $tex The text to transform
	 * @param array $args Key-value pairs representing template parameters to substitute
	 * @access private
	 */
	function replaceVariables( $text, $args = array() ) {

		# Prevent too big inclusions
		if( strlen( $text ) > MAX_INCLUDE_SIZE ) {
			return $text;
		}

		$fname = 'Parser::replaceVariables';
		wfProfileIn( $fname );

		$titleChars = Title::legalChars();

		# This function is called recursively. To keep track of arguments we need a stack:
		array_push( $this->mArgStack, $args );

		# Variable substitution
		$text = preg_replace_callback( "/{{([$titleChars]*?)}}/", array( &$this, 'variableSubstitution' ), $text );

		if ( $this->mOutputType == OT_HTML || $this->mOutputType == OT_WIKI ) {
			# Argument substitution
			$text = preg_replace_callback( "/{{{([$titleChars]*?)}}}/", array( &$this, 'argSubstitution' ), $text );
		}
		# Template substitution
		$regex = '/(\\n|{)?{{(['.$titleChars.']*)(\\|.*?|)}}/s';
		$text = preg_replace_callback( $regex, array( &$this, 'braceSubstitution' ), $text );

		array_pop( $this->mArgStack );

		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * Replace magic variables
	 * @access private
	 */
	function variableSubstitution( $matches ) {
		$fname = 'parser::variableSubstitution';
		$varname = $matches[1];
		wfProfileIn( $fname );
		if ( !$this->mVariables ) {
			$this->initialiseVariables();
		}
		$skip = false;
		if ( $this->mOutputType == OT_WIKI ) {
			# Do only magic variables prefixed by SUBST
			$mwSubst =& MagicWord::get( MAG_SUBST );
			if (!$mwSubst->matchStartAndRemove( $varname ))
				$skip = true;
			# Note that if we don't substitute the variable below,
			# we don't remove the {{subst:}} magic word, in case
			# it is a template rather than a magic variable.
		}
		if ( !$skip && array_key_exists( $varname, $this->mVariables ) ) {
			$id = $this->mVariables[$varname];
			$text = $this->getVariableValue( $id );
			$this->mOutput->mContainsOldMagic = true;
		} else {
			$text = $matches[0];
		}
		wfProfileOut( $fname );
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

		for ( $i = 0; $i < $argc-1; $i++ ) {
			if ( substr_count ( $args[$i], '[[' ) != substr_count ( $args[$i], ']]' ) ) {
				$args[$i] .= '|'.$args[$i+1];
				array_splice($args, $i+1, 1);
				$i--;
				$argc--;
			}
		}

		return $args;
	}

	/**
	 * Return the text of a template, after recursively
	 * replacing any variables or templates within the template.
	 *
	 * @param array $matches The parts of the template
	 *  $matches[1]: the title, i.e. the part before the |
	 *  $matches[2]: the parameters (including a leading |), if  any
	 * @return string the text of the template
	 * @access private
	 */
	function braceSubstitution( $matches ) {
		global $wgLinkCache, $wgContLang;
		$fname = 'Parser::braceSubstitution';
		wfProfileIn( $fname );

		$found = false;
		$nowiki = false;
		$noparse = false;

		$title = NULL;

		# Need to know if the template comes at the start of a line,
		# to treat the beginning of the template like the beginning
		# of a line for tables and block-level elements.
		$linestart = $matches[1];

		# $part1 is the bit before the first |, and must contain only title characters
		# $args is a list of arguments, starting from index 0, not including $part1

		$part1 = $matches[2];
		# If the third subpattern matched anything, it will start with |

		$args = $this->getTemplateArgs($matches[3]);
		$argc = count( $args );

		# Don't parse {{{}}} because that's only for template arguments
		if ( $linestart === '{' ) {
			$text = $matches[0];
			$found = true;
			$noparse = true;
		}

		# SUBST
		if ( !$found ) {
			$mwSubst =& MagicWord::get( MAG_SUBST );
			if ( $mwSubst->matchStartAndRemove( $part1 ) xor ($this->mOutputType == OT_WIKI) ) {
				# One of two possibilities is true:
				# 1) Found SUBST but not in the PST phase
				# 2) Didn't find SUBST and in the PST phase
				# In either case, return without further processing
				$text = $matches[0];
				$found = true;
				$noparse = true;
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
					$text = $linestart . wfMsgReal( $part1, $args, true );
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
					$text = $linestart . $wgContLang->getNsText( intval( $part1 ) );
					$found = true;
				} else {
					$index = Namespace::getCanonicalIndex( strtolower( $part1 ) );
					if ( !is_null( $index ) ) {
						$text = $linestart . $wgContLang->getNsText( $index );
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
						$text = $linestart . $title->$func( $args[0] );
					} else {
						$text = $linestart . $title->$func();
					}
					$found = true;
				}
			}
		}

		# GRAMMAR
		if ( !$found && $argc == 1 ) {
			$mwGrammar =& MagicWord::get( MAG_GRAMMAR );
			if ( $mwGrammar->matchStartAndRemove( $part1 ) ) {
				$text = $linestart . $wgContLang->convertGrammar( $args[0], $part1 );
				$found = true;
			}
		}

		# Template table test

		# Did we encounter this template already? If yes, it is in the cache
		# and we need to check for loops.
		if ( !$found && isset( $this->mTemplates[$part1] ) ) {
			$found = true;

			# Infinite loop test
			if ( isset( $this->mTemplatePath[$part1] ) ) {
				$noparse = true;
				$found = true;
				$text = $linestart .
					"\{\{$part1}}" .
					'<!-- WARNING: template loop detected -->';
				wfDebug( "$fname: template loop broken at '$part1'\n" );
			} else {
				# set $text to cached message.
				$text = $linestart . $this->mTemplates[$part1];
			}
		}

		# Load from database
		$replaceHeadings = false;
		$isHTML = false;
		$lastPathLevel = $this->mTemplatePath;
		if ( !$found ) {
			$ns = NS_TEMPLATE;
			$part1 = $this->maybeDoSubpageLink( $part1, $subpage='' );
			if ($subpage !== '') {
				$ns = $this->mTitle->getNamespace();
			}
			$title = Title::newFromText( $part1, $ns );

                        if ($title) {
                            $interwiki = Title::getInterwikiLink($title->getInterwiki());
                            if ($interwiki != '' && $title->isTrans()) {
                                    return $this->scarytransclude($title, $interwiki);
                            }
                        }

			if ( !is_null( $title ) && !$title->isExternal() ) {
				# Check for excessive inclusion
				$dbk = $title->getPrefixedDBkey();
				if ( $this->incrementIncludeCount( $dbk ) ) {
					if ( $title->getNamespace() == NS_SPECIAL && $this->mOptions->getAllowSpecialInclusion() ) {
						# Capture special page output
						$text = SpecialPage::capturePath( $title );
						if ( is_string( $text ) ) {
							$found = true;
							$noparse = true;
							$isHTML = true;
							$this->mOutput->setCacheTime( -1 );
						}
					} else {
						$article = new Article( $title );
						$articleContent = $article->getContentWithoutUsingSoManyDamnGlobals();
						if ( $articleContent !== false ) {
							$found = true;
							$text = $articleContent;
							$replaceHeadings = true;
						}
					}
				}

				# If the title is valid but undisplayable, make a link to it
				if ( $this->mOutputType == OT_HTML && !$found ) {
					$text = '[['.$title->getPrefixedText().']]';
					$found = true;
				}

				# Template cache array insertion
				if( $found ) {
					$this->mTemplates[$part1] = $text;
					$text = $linestart . $text;
				}
			}
		}

		# Recursive parsing, escaping and link table handling
		# Only for HTML output
		if ( $nowiki && $found && $this->mOutputType == OT_HTML ) {
			$text = wfEscapeWikiText( $text );
		} elseif ( ($this->mOutputType == OT_HTML || $this->mOutputType == OT_WIKI) && $found && !$noparse) {
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

			# Add a new element to the templace recursion path
			$this->mTemplatePath[$part1] = 1;

			if( $this->mOutputType == OT_HTML ) {
				# Remove <noinclude> sections and <includeonly> tags
				$text = preg_replace( '/<noinclude>.*?<\/noinclude>/s', '', $text );
				$text = strtr( $text, array( '<includeonly>' => '' , '</includeonly>' => '' ) );
				# Strip <nowiki>, <pre>, etc.
				$text = $this->strip( $text, $this->mStripState );
				$text = Sanitizer::removeHTMLtags( $text, array( &$this, 'replaceVariables' ), $assocArgs );
			}
			$text = $this->replaceVariables( $text, $assocArgs );

			# Resume the link cache and register the inclusion as a link
			if ( $this->mOutputType == OT_HTML && !is_null( $title ) ) {
				$wgLinkCache->addLinkObj( $title );
			}

			# If the template begins with a table or block-level
			# element, it should be treated as beginning a new line.
			if ($linestart !== '\n' && preg_match('/^({\\||:|;|#|\*)/', $text)) {
				$text = "\n" . $text;
			}
		}
		# Prune lower levels off the recursion check path
		$this->mTemplatePath = $lastPathLevel;

		if ( !$found ) {
			wfProfileOut( $fname );
			return $matches[0];
		} else {
			if ( $isHTML ) {
				# Replace raw HTML by a placeholder
				# Add a blank line preceding, to prevent it from mucking up
				# immediately preceding headings
				$text = "\n\n" . $this->insertStripItem( $text, $this->mStripState );
			} else {
				# replace ==section headers==
				# XXX this needs to go away once we have a better parser.
				if ( $this->mOutputType != OT_WIKI && $replaceHeadings ) {
					if( !is_null( $title ) )
						$encodedname = base64_encode($title->getPrefixedDBkey());
					else
						$encodedname = base64_encode("");
					$m = preg_split('/(^={1,6}.*?={1,6}\s*?$)/m', $text, -1,
						PREG_SPLIT_DELIM_CAPTURE);
					$text = '';
					$nsec = 0;
					for( $i = 0; $i < count($m); $i += 2 ) {
						$text .= $m[$i];
						if (!isset($m[$i + 1]) || $m[$i + 1] == "") continue;
						$hl = $m[$i + 1];
						if( strstr($hl, "<!--MWTEMPLATESECTION") ) {
							$text .= $hl;
							continue;
						}
						preg_match('/^(={1,6})(.*?)(={1,6})\s*?$/m', $hl, $m2);
						$text .= $m2[1] . $m2[2] . "<!--MWTEMPLATESECTION="
							. $encodedname . "&" . base64_encode("$nsec") . "-->" . $m2[3];

						$nsec++;
					}
				}
			}
		}

		# Prune lower levels off the recursion check path
		$this->mTemplatePath = $lastPathLevel;

		if ( !$found ) {
			wfProfileOut( $fname );
			return $matches[0];
		} else {
			wfProfileOut( $fname );
			return $text;
		}
	}

	/**
	 * Translude an interwiki link.
	 */
	function scarytransclude($title, $interwiki) {
		global $wgEnableScaryTranscluding;

		if (!$wgEnableScaryTranscluding)
			return wfMsg('scarytranscludedisabled');

		$articlename = "Template:" . $title->getDBkey();
		$url = str_replace('$1', urlencode($articlename), $interwiki);
		if (strlen($url) > 255)
			return wfMsg('scarytranscludetoolong');
		$text = $this->fetchScaryTemplateMaybeFromCache($url);
		$this->mIWTransData[] = $text;
		return "<!--IW_TRANSCLUDE ".(count($this->mIWTransData) - 1)."-->";
	}

	function fetchScaryTemplateMaybeFromCache($url) {
		$dbr = wfGetDB(DB_SLAVE);
		$obj = $dbr->selectRow('transcache', array('tc_time', 'tc_contents'),
				array('tc_url' => $url));
		if ($obj) {
			$time = $obj->tc_time;
			$text = $obj->tc_contents;
			if ($time && $time < (time() + (60*60))) {
				return $text;
			}
		}

		$text = wfGetHTTP($url . '?action=render');
		if (!$text)
			return wfMsg('scarytranscludefailed', $url);

		$dbw = wfGetDB(DB_MASTER);
		$dbw->replace('transcache', array(), array(
			'tc_url' => $url,
			'tc_time' => time(),
			'tc_contents' => $text));
		return $text;
	}


	/**
	 * Triple brace replacement -- used for template arguments
	 * @access private
	 */
	function argSubstitution( $matches ) {
		$arg = trim( $matches[1] );
		$text = $matches[0];
		$inputArgs = end( $this->mArgStack );

		if ( array_key_exists( $arg, $inputArgs ) ) {
			$text = $inputArgs[$arg];
		}

		return $text;
	}

	/**
	 * Returns true if the function is allowed to include this entity
	 * @access private
	 */
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

	/**
	 * This function accomplishes several tasks:
	 * 1) Auto-number headings if that option is enabled
	 * 2) Add an [edit] link to sections for logged in users who have enabled the option
	 * 3) Add a Table of contents on the top for users who have enabled the option
	 * 4) Auto-anchor headings
	 *
	 * It loops through all headlines, collects the necessary data, then splits up the
	 * string and re-inserts the newly formatted headlines.
	 *
	 * @param string $text
	 * @param boolean $isMain
	 * @access private
	 */
	function formatHeadings( $text, $isMain=true ) {
		global $wgMaxTocLevel, $wgContLang, $wgLinkHolders, $wgInterwikiLinkHolders;

		$doNumberHeadings = $this->mOptions->getNumberHeadings();
		$doShowToc = true;
		$forceTocHere = false;
		if( !$this->mTitle->userCanEdit() ) {
			$showEditLink = 0;
		} else {
			$showEditLink = $this->mOptions->getEditSection();
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
			$doShowToc = false;
		}

		# Get all headlines for numbering them and adding funky stuff like [edit]
		# links - this is for later, but we need the number of headlines right now
		$numMatches = preg_match_all( '/<H([1-6])(.*?'.'>)(.*?)<\/H[1-6] *>/i', $text, $matches );

		# if there are fewer than 4 headlines in the article, do not show TOC
		if( $numMatches < 4 ) {
			$doShowToc = false;
		}

		# if the string __TOC__ (not case-sensitive) occurs in the HTML,
		# override above conditions and always show TOC at that place

		$mw =& MagicWord::get( MAG_TOC );
		if($mw->match( $text ) ) {
			$doShowToc = true;
			$forceTocHere = true;
		} else {
			# if the string __FORCETOC__ (not case-sensitive) occurs in the HTML,
			# override above conditions and always show TOC above first header
			$mw =& MagicWord::get( MAG_FORCETOC );
			if ($mw->matchAndRemove( $text ) ) {
				$doShowToc = true;
			}
		}

		# Never ever show TOC if no headers
		if( $numMatches < 1 ) {
			$doShowToc = false;
		}

		# We need this to perform operations on the HTML
		$sk =& $this->mOptions->getSkin();

		# headline counter
		$headlineCount = 0;
		$sectionCount = 0; # headlineCount excluding template sections

		# Ugh .. the TOC should have neat indentation levels which can be
		# passed to the skin functions. These are determined here
		$toc = '';
		$full = '';
		$head = array();
		$sublevelCount = array();
		$levelCount = array();
		$toclevel = 0;
		$level = 0;
		$prevlevel = 0;
		$toclevel = 0;
		$prevtoclevel = 0;

		foreach( $matches[3] as $headline ) {
			$istemplate = 0;
			$templatetitle = '';
			$templatesection = 0;
			$numbering = '';

			if (preg_match("/<!--MWTEMPLATESECTION=([^&]+)&([^_]+)-->/", $headline, $mat)) {
				$istemplate = 1;
				$templatetitle = base64_decode($mat[1]);
				$templatesection = 1 + (int)base64_decode($mat[2]);
				$headline = preg_replace("/<!--MWTEMPLATESECTION=([^&]+)&([^_]+)-->/", "", $headline);
			}

			if( $toclevel ) {
				$prevlevel = $level;
				$prevtoclevel = $toclevel;
			}
			$level = $matches[1][$headlineCount];

			if( $doNumberHeadings || $doShowToc ) {

				if ( $level > $prevlevel ) {
					# Increase TOC level
					$toclevel++;
					$sublevelCount[$toclevel] = 0;
					$toc .= $sk->tocIndent();
				}
				elseif ( $level < $prevlevel && $toclevel > 1 ) {
					# Decrease TOC level, find level to jump to

					if ( $toclevel == 2 && $level <= $levelCount[1] ) {
						# Can only go down to level 1
						$toclevel = 1;
					} else {
						for ($i = $toclevel; $i > 0; $i--) {
							if ( $levelCount[$i] == $level ) {
								# Found last matching level
								$toclevel = $i;
								break;
							}
							elseif ( $levelCount[$i] < $level ) {
								# Found first matching level below current level
								$toclevel = $i + 1;
								break;
							}
						}
					}

					$toc .= $sk->tocUnindent( $prevtoclevel - $toclevel );
				}
				else {
					# No change in level, end TOC line
					$toc .= $sk->tocLineEnd();
				}

				$levelCount[$toclevel] = $level;

				# count number of headlines for each level
				@$sublevelCount[$toclevel]++;
				$dot = 0;
				for( $i = 1; $i <= $toclevel; $i++ ) {
					if( !empty( $sublevelCount[$i] ) ) {
						if( $dot ) {
							$numbering .= '.';
						}
						$numbering .= $wgContLang->formatNum( $sublevelCount[$i] );
						$dot = 1;
					}
				}
			}

			# The canonized header is a version of the header text safe to use for links
			# Avoid insertion of weird stuff like <math> by expanding the relevant sections
			$canonized_headline = $this->unstrip( $headline, $this->mStripState );
			$canonized_headline = $this->unstripNoWiki( $canonized_headline, $this->mStripState );

			# Remove link placeholders by the link text.
			#     <!--LINK number-->
			# turns into
			#     link text with suffix
			$canonized_headline = preg_replace( '/<!--LINK ([0-9]*)-->/e',
							    "\$this->mLinkHolders['texts'][\$1]",
							    $canonized_headline );
			$canonized_headline = preg_replace( '/<!--IWLINK ([0-9]*)-->/e',
							    "\$this->mInterwikiLinkHolders['texts'][\$1]",
							    $canonized_headline );

			# strip out HTML
			$canonized_headline = preg_replace( '/<.*?' . '>/','',$canonized_headline );
			$tocline = trim( $canonized_headline );
			$canonized_headline = urlencode( Sanitizer::decodeCharReferences( str_replace(' ', '_', $tocline) ) );
			$replacearray = array(
				'%3A' => ':',
				'%' => '.'
			);
			$canonized_headline = str_replace(array_keys($replacearray),array_values($replacearray),$canonized_headline);
			$refers[$headlineCount] = $canonized_headline;

			# count how many in assoc. array so we can track dupes in anchors
			@$refers[$canonized_headline]++;
			$refcount[$headlineCount]=$refers[$canonized_headline];

			# Don't number the heading if it is the only one (looks silly)
			if( $doNumberHeadings && count( $matches[3] ) > 1) {
				# the two are different if the line contains a link
				$headline=$numbering . ' ' . $headline;
			}

			# Create the anchor for linking from the TOC to the section
			$anchor = $canonized_headline;
			if($refcount[$headlineCount] > 1 ) {
				$anchor .= '_' . $refcount[$headlineCount];
			}
			if( $doShowToc && ( !isset($wgMaxTocLevel) || $toclevel<$wgMaxTocLevel ) ) {
				$toc .= $sk->tocLine($anchor, $tocline, $numbering, $toclevel);
			}
			if( $showEditLink && ( !$istemplate || $templatetitle !== "" ) ) {
				if ( empty( $head[$headlineCount] ) ) {
					$head[$headlineCount] = '';
				}
				if( $istemplate )
					$head[$headlineCount] .= $sk->editSectionLinkForOther($templatetitle, $templatesection);
				else
					$head[$headlineCount] .= $sk->editSectionLink($this->mTitle, $sectionCount+1);
			}

			# give headline the correct <h#> tag
			@$head[$headlineCount] .= "<a name=\"$anchor\"></a><h".$level.$matches[2][$headlineCount] .$headline.'</h'.$level.'>';

			$headlineCount++;
			if( !$istemplate )
				$sectionCount++;
		}

		if( $doShowToc ) {
			$toc .= $sk->tocUnindent( $toclevel - 1 );
			$toc = $sk->tocList( $toc );
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

	/**
	 * Return an HTML link for the "ISBN 123456" text
	 * @access private
	 */
	function magicISBN( $text ) {
		$fname = 'Parser::magicISBN';
		wfProfileIn( $fname );

		$a = split( 'ISBN ', ' '.$text );
		if ( count ( $a ) < 2 ) {
			wfProfileOut( $fname );
			return $text;
		}
		$text = substr( array_shift( $a ), 1);
		$valid = '0123456789-Xx';

		foreach ( $a as $x ) {
			$isbn = $blank = '' ;
			while ( ' ' == $x{0} ) {
				$blank .= ' ';
				$x = substr( $x, 1 );
			}
			if ( $x == '' ) { # blank isbn
				$text .= "ISBN $blank";
				continue;
			}
			while ( strstr( $valid, $x{0} ) != false ) {
				$isbn .= $x{0};
				$x = substr( $x, 1 );
			}
			$num = str_replace( '-', '', $isbn );
			$num = str_replace( ' ', '', $num );
			$num = str_replace( 'x', 'X', $num );

			if ( '' == $num ) {
				$text .= "ISBN $blank$x";
			} else {
				$titleObj = Title::makeTitle( NS_SPECIAL, 'Booksources' );
				$text .= '<a href="' .
				$titleObj->escapeLocalUrl( 'isbn='.$num ) .
					"\" class=\"internal\">ISBN $isbn</a>";
				$text .= $x;
			}
		}
		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * Return an HTML link for the "RFC 1234" text
	 *
	 * @access private
	 * @param string $text     Text to be processed
	 * @param string $keyword  Magic keyword to use (default RFC)
	 * @param string $urlmsg   Interface message to use (default rfcurl)
	 * @return string
	 */
	function magicRFC( $text, $keyword='RFC ', $urlmsg='rfcurl'  ) {

		$valid = '0123456789';
		$internal = false;

		$a = split( $keyword, ' '.$text );
		if ( count ( $a ) < 2 ) {
			return $text;
		}
		$text = substr( array_shift( $a ), 1);

		/* Check if keyword is preceed by [[.
		 * This test is made here cause of the array_shift above
		 * that prevent the test to be done in the foreach.
		 */
		if ( substr( $text, -2 ) == '[[' ) {
			$internal = true;
		}

		foreach ( $a as $x ) {
			/* token might be empty if we have RFC RFC 1234 */
			if ( $x=='' ) {
				$text.=$keyword;
				continue;
				}

			$id = $blank = '' ;

			/** remove and save whitespaces in $blank */
			while ( $x{0} == ' ' ) {
				$blank .= ' ';
				$x = substr( $x, 1 );
			}

			/** remove and save the rfc number in $id */
			while ( strstr( $valid, $x{0} ) != false ) {
				$id .= $x{0};
				$x = substr( $x, 1 );
			}

			if ( $id == '' ) {
				/* call back stripped spaces*/
				$text .= $keyword.$blank.$x;
			} elseif( $internal ) {
				/* normal link */
				$text .= $keyword.$id.$x;
			} else {
				/* build the external link*/
				$url = wfMsg( $urlmsg, $id);
				$sk =& $this->mOptions->getSkin();
				$la = $sk->getExternalLinkAttributes( $url, $keyword.$id );
				$text .= "<a href='{$url}'{$la}>{$keyword}{$id}</a>{$x}";
			}

			/* Check if the next RFC keyword is preceed by [[ */
			$internal = ( substr($x,-2) == '[[' );
		}
		return $text;
	}

	/**
	 * Transform wiki markup when saving a page by doing \r\n -> \n
	 * conversion, substitting signatures, {{subst:}} templates, etc.
	 *
	 * @param string $text the text to transform
	 * @param Title &$title the Title object for the current article
	 * @param User &$user the User object describing the current user
	 * @param ParserOptions $options parsing options
	 * @param bool $clearState whether to clear the parser state first
	 * @return string the altered wiki markup
	 * @access public
	 */
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
		$text = str_replace( array_keys( $pairs ), array_values( $pairs ), $text );
		$text = $this->strip( $text, $stripState, false );
		$text = $this->pstPass2( $text, $user );
		$text = $this->unstrip( $text, $stripState );
		$text = $this->unstripNoWiki( $text, $stripState );
		return $text;
	}

	/**
	 * Pre-save transform helper function
	 * @access private
	 */
	function pstPass2( $text, &$user ) {
		global $wgContLang, $wgLocaltimezone;

		# Variable replacement
		# Because mOutputType is OT_WIKI, this will only process {{subst:xxx}} type tags
		$text = $this->replaceVariables( $text );

		# Signatures
		#
		$n = $user->getName();
		$k = $user->getOption( 'nickname' );
		if ( '' == $k ) { $k = $n; }
		if ( isset( $wgLocaltimezone ) ) {
			$oldtz = getenv( 'TZ' );
			putenv( 'TZ='.$wgLocaltimezone );
		}

		/* Note: This is the timestamp saved as hardcoded wikitext to
		 * the database, we use $wgContLang here in order to give
		 * everyone the same signiture and use the default one rather
		 * than the one selected in each users preferences.
		 */
		$d = $wgContLang->timeanddate( date( 'YmdHis' ), false, false) .
		  ' (' . date( 'T' ) . ')';
		if ( isset( $wgLocaltimezone ) ) {
			putenv( 'TZ='.$oldtz );
		}

		if( $user->getOption( 'fancysig' ) ) {
			$sigText = $k;
		} else {
			$sigText = '[[' . $wgContLang->getNsText( NS_USER ) . ":$n|$k]]";
		}
		$text = preg_replace( '/~~~~~/', $d, $text );
		$text = preg_replace( '/~~~~/', "$sigText $d", $text );
		$text = preg_replace( '/~~~/', $sigText, $text );

		# Context links: [[|name]] and [[name (context)|]]
		#
		$tc = "[&;%\\-,.\\(\\)' _0-9A-Za-z\\/:\\x80-\\xff]";
		$np = "[&;%\\-,.' _0-9A-Za-z\\/:\\x80-\\xff]"; # No parens
		$namespacechar = '[ _0-9A-Za-z\x80-\xff]'; # Namespaces can use non-ascii!
		$conpat = "/^({$np}+) \\(({$tc}+)\\)$/";

		$p1 = "/\[\[({$np}+) \\(({$np}+)\\)\\|]]/";		# [[page (context)|]]
		$p2 = "/\[\[\\|({$tc}+)]]/";					# [[|page]]
		$p3 = "/\[\[(:*$namespacechar+):({$np}+)\\|]]/";		# [[namespace:page|]] and [[:namespace:page|]]
		$p4 = "/\[\[(:*$namespacechar+):({$np}+) \\(({$np}+)\\)\\|]]/"; # [[ns:page (cont)|]] and [[:ns:page (cont)|]]
		$context = '';
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

		# Trim trailing whitespace
		# MAG_END (__END__) tag allows for trailing
		# whitespace to be deliberately included
		$text = rtrim( $text );
		$mw =& MagicWord::get( MAG_END );
		$mw->matchAndRemove( $text );

		return $text;
	}

	/**
	 * Set up some variables which are usually set up in parse()
	 * so that an external function can call some class members with confidence
	 * @access public
	 */
	function startExternalParse( &$title, $options, $outputType, $clearState = true ) {
		$this->mTitle =& $title;
		$this->mOptions = $options;
		$this->mOutputType = $outputType;
		if ( $clearState ) {
			$this->clearState();
		}
	}

	/**
	 * Transform a MediaWiki message by replacing magic variables.
	 *
	 * @param string $text the text to transform
	 * @param ParserOptions $options  options
	 * @return string the text with variables substituted
	 * @access public
	 */
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

	/**
	 * Create an HTML-style tag, e.g. <yourtag>special text</yourtag>
	 * Callback will be called with the text within
	 * Transform and return the text within
	 * @access public
	 */
	function setHook( $tag, $callback ) {
		$oldVal = @$this->mTagHooks[$tag];
		$this->mTagHooks[$tag] = $callback;
		return $oldVal;
	}

	/**
	 * Replace <!--LINK--> link placeholders with actual links, in the buffer
	 * Placeholders created in Skin::makeLinkObj()
	 * Returns an array of links found, indexed by PDBK:
	 *  0 - broken
	 *  1 - normal link
	 *  2 - stub
	 * $options is a bit field, RLH_FOR_UPDATE to select for update
	 */
	function replaceLinkHolders( &$text, $options = 0 ) {
		global $wgUser, $wgLinkCache;
		global $wgOutputReplace;

		$fname = 'Parser::replaceLinkHolders';
		wfProfileIn( $fname );

		$pdbks = array();
		$colours = array();
		$sk = $this->mOptions->getSkin();

		if ( !empty( $this->mLinkHolders['namespaces'] ) ) {
			wfProfileIn( $fname.'-check' );
			$dbr =& wfGetDB( DB_SLAVE );
			$page = $dbr->tableName( 'page' );
			$threshold = $wgUser->getOption('stubthreshold');

			# Sort by namespace
			asort( $this->mLinkHolders['namespaces'] );

			# Generate query
			$query = false;
			foreach ( $this->mLinkHolders['namespaces'] as $key => $val ) {
				# Make title object
				$title = $this->mLinkHolders['titles'][$key];

				# Skip invalid entries.
				# Result will be ugly, but prevents crash.
				if ( is_null( $title ) ) {
					continue;
				}
				$pdbk = $pdbks[$key] = $title->getPrefixedDBkey();

				# Check if it's in the link cache already
				if ( $wgLinkCache->getGoodLinkID( $pdbk ) ) {
					$colours[$pdbk] = 1;
				} elseif ( $wgLinkCache->isBadLink( $pdbk ) ) {
					$colours[$pdbk] = 0;
				} else {
					# Not in the link cache, add it to the query
					if ( !isset( $current ) ) {
						$current = $val;
						$query =  "SELECT page_id, page_namespace, page_title";
						if ( $threshold > 0 ) {
							$query .= ', page_len, page_is_redirect';
						}
						$query .= " FROM $page WHERE (page_namespace=$val AND page_title IN(";
					} elseif ( $current != $val ) {
						$current = $val;
						$query .= ")) OR (page_namespace=$val AND page_title IN(";
					} else {
						$query .= ', ';
					}

					$query .= $dbr->addQuotes( $this->mLinkHolders['dbkeys'][$key] );
				}
			}
			if ( $query ) {
				$query .= '))';
				if ( $options & RLH_FOR_UPDATE ) {
					$query .= ' FOR UPDATE';
				}

				$res = $dbr->query( $query, $fname );

				# Fetch data and form into an associative array
				# non-existent = broken
				# 1 = known
				# 2 = stub
				while ( $s = $dbr->fetchObject($res) ) {
					$title = Title::makeTitle( $s->page_namespace, $s->page_title );
					$pdbk = $title->getPrefixedDBkey();
					$wgLinkCache->addGoodLinkObj( $s->page_id, $title );

					if ( $threshold >  0 ) {
						$size = $s->page_len;
						if ( $s->page_is_redirect || $s->page_namespace != 0 || $size >= $threshold ) {
							$colours[$pdbk] = 1;
						} else {
							$colours[$pdbk] = 2;
						}
					} else {
						$colours[$pdbk] = 1;
					}
				}
			}
			wfProfileOut( $fname.'-check' );

			# Construct search and replace arrays
			wfProfileIn( $fname.'-construct' );
			$wgOutputReplace = array();
			foreach ( $this->mLinkHolders['namespaces'] as $key => $ns ) {
				$pdbk = $pdbks[$key];
				$searchkey = "<!--LINK $key-->";
				$title = $this->mLinkHolders['titles'][$key];
				if ( empty( $colours[$pdbk] ) ) {
					$wgLinkCache->addBadLinkObj( $title );
					$colours[$pdbk] = 0;
					$wgOutputReplace[$searchkey] = $sk->makeBrokenLinkObj( $title,
									$this->mLinkHolders['texts'][$key],
									$this->mLinkHolders['queries'][$key] );
				} elseif ( $colours[$pdbk] == 1 ) {
					$wgOutputReplace[$searchkey] = $sk->makeKnownLinkObj( $title,
									$this->mLinkHolders['texts'][$key],
									$this->mLinkHolders['queries'][$key] );
				} elseif ( $colours[$pdbk] == 2 ) {
					$wgOutputReplace[$searchkey] = $sk->makeStubLinkObj( $title,
									$this->mLinkHolders['texts'][$key],
									$this->mLinkHolders['queries'][$key] );
				}
			}
			wfProfileOut( $fname.'-construct' );

			# Do the thing
			wfProfileIn( $fname.'-replace' );

			$text = preg_replace_callback(
				'/(<!--LINK .*?-->)/',
				"wfOutputReplaceMatches",
				$text);

			wfProfileOut( $fname.'-replace' );
		}

		# Now process interwiki link holders
		# This is quite a bit simpler than internal links
		if ( !empty( $this->mInterwikiLinkHolders['texts'] ) ) {
			wfProfileIn( $fname.'-interwiki' );
			# Make interwiki link HTML
			$wgOutputReplace = array();
			foreach( $this->mInterwikiLinkHolders['texts'] as $key => $link ) {
				$title = $this->mInterwikiLinkHolders['titles'][$key];
				$wgOutputReplace[$key] = $sk->makeLinkObj( $title, $link );
			}

			$text = preg_replace_callback(
				'/<!--IWLINK (.*?)-->/',
				"wfOutputReplaceMatches",
				$text );
			wfProfileOut( $fname.'-interwiki' );
		}

		wfProfileOut( $fname );
		return $colours;
	}

	/**
	 * Replace <!--LINK--> link placeholders with plain text of links
	 * (not HTML-formatted).
	 * @param string $text
	 * @return string
	 */
	function replaceLinkHoldersText( $text ) {
		global $wgUser, $wgLinkCache;
		global $wgOutputReplace;

		$fname = 'Parser::replaceLinkHoldersText';
		wfProfileIn( $fname );

		$text = preg_replace_callback(
			'/<!--(LINK|IWLINK) (.*?)-->/',
			array( &$this, 'replaceLinkHoldersTextCallback' ),
			$text );

		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * @param array $matches
	 * @return string
	 * @access private
	 */
	function replaceLinkHoldersTextCallback( $matches ) {
		$type = $matches[1];
		$key  = $matches[2];
		if( $type == 'LINK' ) {
			if( isset( $this->mLinkHolders['texts'][$key] ) ) {
				return $this->mLinkHolders['texts'][$key];
			}
		} elseif( $type == 'IWLINK' ) {
			if( isset( $this->mInterwikiLinkHolders['texts'][$key] ) ) {
				return $this->mInterwikiLinkHolders['texts'][$key];
			}
		}
		return $matches[0];
	}

	/**
	 * Renders an image gallery from a text with one line per image.
	 * text labels may be given by using |-style alternative text. E.g.
	 *   Image:one.jpg|The number "1"
	 *   Image:tree.jpg|A tree
	 * given as text will return the HTML of a gallery with two images,
	 * labeled 'The number "1"' and
	 * 'A tree'.
	 *
	 * @static
	 */
	function renderImageGallery( $text ) {
		# Setup the parser
		global $wgUser, $wgTitle;
		$parserOptions = ParserOptions::newFromUser( $wgUser );
		$localParser = new Parser();

		global $wgLinkCache;
		$ig = new ImageGallery();
		$ig->setShowBytes( false );
		$ig->setShowFilename( false );
		$lines = explode( "\n", $text );

		foreach ( $lines as $line ) {
			# match lines like these:
			# Image:someimage.jpg|This is some image
			preg_match( "/^([^|]+)(\\|(.*))?$/", $line, $matches );
			# Skip empty lines
			if ( count( $matches ) == 0 ) {
				continue;
			}
			$nt = Title::newFromURL( $matches[1] );
			if( is_null( $nt ) ) {
				# Bogus title. Ignore these so we don't bomb out later.
				continue;
			}
			if ( isset( $matches[3] ) ) {
				$label = $matches[3];
			} else {
				$label = '';
			}

			$html = $localParser->parse( $label , $wgTitle, $parserOptions );
			$html = $html->mText;

			$ig->add( new Image( $nt ), $html );
			$wgLinkCache->addImageLinkObj( $nt );
		}
		return $ig->toHTML();
	}

	/**
	 * Parse image options text and use it to make an image
	 */
	function makeImage( &$nt, $options ) {
		global $wgContLang, $wgUseImageResize;
		global $wgUser, $wgThumbLimits;

		$align = '';

		# Check if the options text is of the form "options|alt text"
		# Options are:
		#  * thumbnail       	make a thumbnail with enlarge-icon and caption, alignment depends on lang
		#  * left		no resizing, just left align. label is used for alt= only
		#  * right		same, but right aligned
		#  * none		same, but not aligned
		#  * ___px		scale to ___ pixels width, no aligning. e.g. use in taxobox
		#  * center		center the image
		#  * framed		Keep original image size, no magnify-button.

		$part = explode( '|', $options);

		$mwThumb  =& MagicWord::get( MAG_IMG_THUMBNAIL );
		$mwLeft   =& MagicWord::get( MAG_IMG_LEFT );
		$mwRight  =& MagicWord::get( MAG_IMG_RIGHT );
		$mwNone   =& MagicWord::get( MAG_IMG_NONE );
		$mwWidth  =& MagicWord::get( MAG_IMG_WIDTH );
		$mwCenter =& MagicWord::get( MAG_IMG_CENTER );
		$mwFramed =& MagicWord::get( MAG_IMG_FRAMED );
		$caption = '';

		$width = $height = $framed = $thumb = false;
		$manual_thumb = '' ;

		foreach( $part as $key => $val ) {
			$val_parts = explode ( '=' , $val , 2 ) ;
			$left_part = array_shift ( $val_parts ) ;
			if ( $wgUseImageResize && ! is_null( $mwThumb->matchVariableStartToEnd($val) ) ) {
				$thumb=true;
			} elseif ( $wgUseImageResize && count ( $val_parts ) == 1 && ! is_null( $mwThumb->matchVariableStartToEnd($left_part) ) ) {
				# use manually specified thumbnail
				$thumb=true;
				$manual_thumb = array_shift ( $val_parts ) ;
			} elseif ( ! is_null( $mwRight->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'right';
			} elseif ( ! is_null( $mwLeft->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'left';
			} elseif ( ! is_null( $mwCenter->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'center';
			} elseif ( ! is_null( $mwNone->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'none';
			} elseif ( $wgUseImageResize && ! is_null( $match = $mwWidth->matchVariableStartToEnd($val) ) ) {
				wfDebug( "MAG_IMG_WIDTH match: $match\n" );
				# $match is the image width in pixels
				if ( preg_match( '/^([0-9]*)x([0-9]*)$/', $match, $m ) ) {
					$width = intval( $m[1] );
					$height = intval( $m[2] );
				} else {
					$width = intval($match);
				}
			} elseif ( ! is_null( $mwFramed->matchVariableStartToEnd($val) ) ) {
				$framed=true;
			} else {
				$caption = $val;
			}
		}
		# Strip bad stuff out of the alt text
		$alt = $this->replaceLinkHoldersText( $caption );
		$alt = Sanitizer::stripAllTags( $alt );

		# Linker does the rest
		$sk =& $this->mOptions->getSkin();
		return $sk->makeImageLinkObj( $nt, $caption, $alt, $align, $width, $height, $framed, $thumb, $manual_thumb );
	}

	/**
	 * Set a flag in the output object indicating that the content is dynamic and 
	 * shouldn't be cached.
	 */
	function disableCache() {
		$this->mOutput->mCacheTime = -1;
	}
	
	/**
	 * Callback from the Sanitizer for expanding items found in HTML attribute
	 * values, so they can be safely tested and escaped.
	 * @param string $text
	 * @param array $args
	 * @return string
	 * @access private
	 */
	function attributeStripCallback( &$text, $args ) {
		$text = $this->replaceVariables( $text, $args );
		$text = $this->unstripForHTML( $text );
		return $text;
	}
	
	function unstripForHTML( $text ) {
		$text = $this->unstrip( $text, $this->mStripState );
		$text = $this->unstripNoWiki( $text, $this->mStripState );
		return $text;
	}
}

/**
 * @todo document
 * @package MediaWiki
 */
class ParserOutput
{
	var $mText, $mLanguageLinks, $mCategoryLinks, $mContainsOldMagic;
	var $mCacheTime; # Timestamp on this article, or -1 for uncacheable. Used in ParserCache.
	var $mVersion;   # Compatibility check
	var $mTitleText; # title text of the chosen language variant

	function ParserOutput( $text = '', $languageLinks = array(), $categoryLinks = array(),
		$containsOldMagic = false, $titletext = '' )
	{
		$this->mText = $text;
		$this->mLanguageLinks = $languageLinks;
		$this->mCategoryLinks = $categoryLinks;
		$this->mContainsOldMagic = $containsOldMagic;
		$this->mCacheTime = '';
		$this->mVersion = MW_PARSER_VERSION;
		$this->mTitleText = $titletext;
	}

	function getText()                   { return $this->mText; }
	function getLanguageLinks()          { return $this->mLanguageLinks; }
	function getCategoryLinks()          { return array_keys( $this->mCategoryLinks ); }
	function getCacheTime()              { return $this->mCacheTime; }
	function getTitleText()              { return $this->mTitleText; }
	function containsOldMagic()          { return $this->mContainsOldMagic; }
	function setText( $text )            { return wfSetVar( $this->mText, $text ); }
	function setLanguageLinks( $ll )     { return wfSetVar( $this->mLanguageLinks, $ll ); }
	function setCategoryLinks( $cl )     { return wfSetVar( $this->mCategoryLinks, $cl ); }
	function setContainsOldMagic( $com ) { return wfSetVar( $this->mContainsOldMagic, $com ); }
	function setCacheTime( $t )          { return wfSetVar( $this->mCacheTime, $t ); }
	function setTitleText( $t )          { return wfSetVar ($this->mTitleText, $t); }

	function addCategoryLink( $c )       { $this->mCategoryLinks[$c] = 1; }

	function merge( $other ) {
		$this->mLanguageLinks = array_merge( $this->mLanguageLinks, $other->mLanguageLinks );
		$this->mCategoryLinks = array_merge( $this->mCategoryLinks, $this->mLanguageLinks );
		$this->mContainsOldMagic = $this->mContainsOldMagic || $other->mContainsOldMagic;
	}

	/**
	 * Return true if this cached output object predates the global or
	 * per-article cache invalidation timestamps, or if it comes from
	 * an incompatible older version.
	 *
	 * @param string $touched the affected article's last touched timestamp
	 * @return bool
	 * @access public
	 */
	function expired( $touched ) {
		global $wgCacheEpoch;
		return $this->getCacheTime() == -1 || // parser says it's uncacheable
		       $this->getCacheTime() <= $touched ||
		       $this->getCacheTime() <= $wgCacheEpoch ||
		       !isset( $this->mVersion ) ||
		       version_compare( $this->mVersion, MW_PARSER_VERSION, "lt" );
	}
}

/**
 * Set options of the Parser
 * @todo document
 * @package MediaWiki
 */
class ParserOptions
{
	# All variables are private
	var $mUseTeX;                    # Use texvc to expand <math> tags
	var $mUseDynamicDates;           # Use DateFormatter to format dates
	var $mInterwikiMagic;            # Interlanguage links are removed and returned in an array
	var $mAllowExternalImages;       # Allow external images inline
	var $mSkin;                      # Reference to the preferred skin
	var $mDateFormat;                # Date format index
	var $mEditSection;               # Create "edit section" links
	var $mNumberHeadings;            # Automatically number headings
	var $mAllowSpecialInclusion;     # Allow inclusion of special pages

	function getUseTeX()                        { return $this->mUseTeX; }
	function getUseDynamicDates()               { return $this->mUseDynamicDates; }
	function getInterwikiMagic()                { return $this->mInterwikiMagic; }
	function getAllowExternalImages()           { return $this->mAllowExternalImages; }
	function &getSkin()                         { return $this->mSkin; }
	function getDateFormat()                    { return $this->mDateFormat; }
	function getEditSection()                   { return $this->mEditSection; }
	function getNumberHeadings()                { return $this->mNumberHeadings; }
	function getAllowSpecialInclusion()         { return $this->mAllowSpecialInclusion; }


	function setUseTeX( $x )                    { return wfSetVar( $this->mUseTeX, $x ); }
	function setUseDynamicDates( $x )           { return wfSetVar( $this->mUseDynamicDates, $x ); }
	function setInterwikiMagic( $x )            { return wfSetVar( $this->mInterwikiMagic, $x ); }
	function setAllowExternalImages( $x )       { return wfSetVar( $this->mAllowExternalImages, $x ); }
	function setDateFormat( $x )                { return wfSetVar( $this->mDateFormat, $x ); }
	function setEditSection( $x )               { return wfSetVar( $this->mEditSection, $x ); }
	function setNumberHeadings( $x )            { return wfSetVar( $this->mNumberHeadings, $x ); }
	function setAllowSpecialInclusion( $x )     { return wfSetVar( $this->mAllowSpecialInclusion, $x ); }

	function setSkin( &$x ) { $this->mSkin =& $x; }

	function ParserOptions() {
		global $wgUser;
		$this->initialiseFromUser( $wgUser );
	}

	/**
	 * Get parser options
	 * @static
	 */
	function newFromUser( &$user ) {
		$popts = new ParserOptions;
		$popts->initialiseFromUser( $user );
		return $popts;
	}

	/** Get user options */
	function initialiseFromUser( &$userInput ) {
		global $wgUseTeX, $wgUseDynamicDates, $wgInterwikiMagic, $wgAllowExternalImages,
		       $wgAllowSpecialInclusion;
		$fname = 'ParserOptions::initialiseFromUser';
		wfProfileIn( $fname );
		if ( !$userInput ) {
			$user = new User;
			$user->setLoaded( true );
		} else {
			$user =& $userInput;
		}

		$this->mUseTeX = $wgUseTeX;
		$this->mUseDynamicDates = $wgUseDynamicDates;
		$this->mInterwikiMagic = $wgInterwikiMagic;
		$this->mAllowExternalImages = $wgAllowExternalImages;
		wfProfileIn( $fname.'-skin' );
		$this->mSkin =& $user->getSkin();
		wfProfileOut( $fname.'-skin' );
		$this->mDateFormat = $user->getOption( 'date' );
		$this->mEditSection = true;
		$this->mNumberHeadings = $user->getOption( 'numberheadings' );
		$this->mAllowSpecialInclusion = $wgAllowSpecialInclusion;
		wfProfileOut( $fname );
	}
}

/**
 * Callback function used by Parser::replaceLinkHolders()
 * to substitute link placeholders.
 */
function &wfOutputReplaceMatches( $matches ) {
	global $wgOutputReplace;
	return $wgOutputReplace[$matches[1]];
}

/**
 * Return the total number of articles
 */
function wfNumberOfArticles() {
	global $wgNumberOfArticles;

	wfLoadSiteStats();
	return $wgNumberOfArticles;
}

/**
 * Return the number of files
 */
function wfNumberOfFiles() {
	$fname = 'Parser::wfNumberOfFiles';

	wfProfileIn( $fname );
	$dbr =& wfGetDB( DB_SLAVE );
	$res = $dbr->selectField('image', 'COUNT(*)', array(), $fname );
	wfProfileOut( $fname );

	return $res;
}

/**
 * Get various statistics from the database
 * @private
 */
function wfLoadSiteStats() {
	global $wgNumberOfArticles, $wgTotalViews, $wgTotalEdits;
	$fname = 'wfLoadSiteStats';

	if ( -1 != $wgNumberOfArticles ) return;
	$dbr =& wfGetDB( DB_SLAVE );
	$s = $dbr->selectRow( 'site_stats',
		array( 'ss_total_views', 'ss_total_edits', 'ss_good_articles' ),
		array( 'ss_row_id' => 1 ), $fname
	);

	if ( $s === false ) {
		return;
	} else {
		$wgTotalViews = $s->ss_total_views;
		$wgTotalEdits = $s->ss_total_edits;
		$wgNumberOfArticles = $s->ss_good_articles;
	}
}

/**
 * Escape html tags
 * Basicly replacing " > and < with HTML entities ( &quot;, &gt;, &lt;)
 *
 * @param string $in Text that might contain HTML tags
 * @return string Escaped string
 */
function wfEscapeHTMLTagsOnly( $in ) {
	return str_replace(
		array( '"', '>', '<' ),
		array( '&quot;', '&gt;', '&lt;' ),
		$in );
}

?>
