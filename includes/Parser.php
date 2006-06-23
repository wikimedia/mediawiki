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
require_once( 'ImageGallery.php' );

/**
 * Update this version number when the ParserOutput format
 * changes in an incompatible way, so the parser cache
 * can automatically discard old data.
 */
define( 'MW_PARSER_VERSION', '1.6.0' );

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
define( 'EXT_LINK_URL_CLASS', '[^][<>"\\x00-\\x20\\x7F]' );
# Including space
define( 'EXT_LINK_TEXT_CLASS', '[^\]\\x00-\\x1F\\x7F]' );
define( 'EXT_IMAGE_FNAME_CLASS', '[A-Za-z0-9_.,~%\\-+&;#*?!=()@\\x80-\\xFF]' );
define( 'EXT_IMAGE_EXTENSIONS', 'gif|png|jpg|jpeg' );
define( 'EXT_LINK_BRACKETED',  '/\[(\b(' . wfUrlProtocols() . ')'.EXT_LINK_URL_CLASS.'+) *('.EXT_LINK_TEXT_CLASS.'*?)\]/S' );
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
 *    objects:   $wgLang, $wgContLang
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
	var $mTagHooks, $mFunctionHooks;

	# Cleared with clearState():
	var $mOutput, $mAutonumber, $mDTopen, $mStripState = array();
	var $mVariables, $mIncludeCount, $mArgStack, $mLastSection, $mInPre;
	var $mInterwikiLinkHolders, $mLinkHolders, $mUniqPrefix;
	var $mTemplates,	// cache of already loaded templates, avoids
		                // multiple SQL queries for the same string
	    $mTemplatePath;	// stores an unsorted hash of all the templates already loaded
		                // in this path. Used for loop detection.

	# Temporary
	# These are variables reset at least once per parse regardless of $clearState
	var $mOptions,      // ParserOptions object
		$mTitle,        // Title context, used for self-link rendering and similar things
		$mOutputType,   // Output type, one of the OT_xxx constants
		$mRevisionId;   // ID to display in {{REVISIONID}} tags

	/**#@-*/

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function Parser() {
		$this->mTagHooks = array();
		$this->mFunctionHooks = array();
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
		$this->mRevisionId = null;
		
		/**
		 * Prefix for temporary replacement strings for the multipass parser.
		 * \x07 should never appear in input as it's disallowed in XML.
		 * Using it at the front also gives us a little extra robustness
		 * since it shouldn't match when butted up against identifier-like
		 * string constructs.
		 */
		$this->mUniqPrefix = "\x07UNIQ" . Parser::getRandomString();

		# Clear these on every parse, bug 4549
 		$this->mTemplates = array();
 		$this->mTemplatePath = array();

		$this->mShowToc = true;
		$this->mForceTocPosition = false;
		
		wfRunHooks( 'ParserClearState', array( &$this ) );
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
	 * Convert wikitext to HTML
	 * Do not call this function recursively.
	 *
	 * @access private
	 * @param string $text Text we want to parse
	 * @param Title &$title A title object
	 * @param array $options
	 * @param boolean $linestart
	 * @param boolean $clearState
	 * @param int $revid number to pass in {{REVISIONID}}
	 * @return ParserOutput a ParserOutput
	 */
	function parse( $text, &$title, $options, $linestart = true, $clearState = true, $revid = null ) {
		/**
		 * First pass--just handle <nowiki> sections, pass the rest off
		 * to internalParse() which does all the real work.
		 */

		global $wgUseTidy, $wgAlwaysUseTidy, $wgContLang;
		$fname = 'Parser::parse';
		wfProfileIn( $fname );

		if ( $clearState ) {
			$this->clearState();
		}

		$this->mOptions = $options;
		$this->mTitle =& $title;
		$this->mRevisionId = $revid;
		$this->mOutputType = OT_HTML;

		//$text = $this->strip( $text, $this->mStripState );
		// VOODOO MAGIC FIX! Sometimes the above segfaults in PHP5.
		$x =& $this->mStripState;

		wfRunHooks( 'ParserBeforeStrip', array( &$this, &$text, &$x ) );
		$text = $this->strip( $text, $x );
		wfRunHooks( 'ParserAfterStrip', array( &$this, &$text, &$x ) );

		# Hook to suspend the parser in this state
		if ( !wfRunHooks( 'ParserBeforeInternalParse', array( &$this, &$text, &$x ) ) ) {
			wfProfileOut( $fname );
			return $text ;
		}

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

		# the position of the parserConvert() call should not be changed. it
		# assumes that the links are all replaced and the only thing left
		# is the <nowiki> mark.
		# Side-effects: this calls $this->mOutput->setTitleText()
		$text = $wgContLang->parserConvert( $text, $this );

		$text = $this->unstripNoWiki( $text, $this->mStripState );

		wfRunHooks( 'ParserBeforeTidy', array( &$this, &$text ) );

		$text = Sanitizer::normalizeCharReferences( $text );

		if (($wgUseTidy and $this->mOptions->mTidy) or $wgAlwaysUseTidy) {
			$text = Parser::tidy($text);
		} else {
			# attempt to sanitize at least some nesting problems
			# (bug #2702 and quite a few others)
			$tidyregs = array(	
				# ''Something [http://www.cool.com cool''] --> 
				# <i>Something</i><a href="http://www.cool.com"..><i>cool></i></a>
				'/(<([bi])>)(<([bi])>)?([^<]*)(<\/?a[^<]*>)([^<]*)(<\/\\4>)?(<\/\\2>)/' =>
				'\\1\\3\\5\\8\\9\\6\\1\\3\\7\\8\\9',
				# fix up an anchor inside another anchor, only
				# at least for a single single nested link (bug 3695)
				'/(<a[^>]+>)([^<]*)(<a[^>]+>[^<]*)<\/a>(.*)<\/a>/' =>
				'\\1\\2</a>\\3</a>\\1\\4</a>',
				# fix div inside inline elements- doBlockLevels won't wrap a line which
				# contains a div, so fix it up here; replace
				# div with escaped text
				'/(<([aib]) [^>]+>)([^<]*)(<div([^>]*)>)(.*)(<\/div>)([^<]*)(<\/\\2>)/' =>
				'\\1\\3&lt;div\\5&gt;\\6&lt;/div&gt;\\8\\9',
				# remove empty italic or bold tag pairs, some
				# introduced by rules above
				'/<([bi])><\/\\1>/' => '' 
			);

			$text = preg_replace( 
				array_keys( $tidyregs ),
				array_values( $tidyregs ),
				$text );
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

	function &getTitle() { return $this->mTitle; }
	function getOptions() { return $this->mOptions; }

	/**
	 * Replaces all occurrences of HTML-style comments and the given tags
	 * in the text with a random marker and returns teh next text. The output
	 * parameter $matches will be an associative array filled with data in
	 * the form:
	 *   'UNIQ-xxxxx' => array(
	 *     'element',
	 *     'tag content',
	 *     array( 'param' => 'x' ),
	 *     '<element param="x">tag content</element>' ) )
	 *
	 * @param $elements list of element names. Comments are always extracted.
	 * @param $text Source text string.
	 * @param $uniq_prefix
	 *
	 * @private
	 * @static
	 */
	function extractTagsAndParams($elements, $text, &$matches, $uniq_prefix = ''){
		$rand = Parser::getRandomString();
		$n = 1;
		$stripped = '';
		$matches = array();

		$taglist = implode( '|', $elements );
		$start = "/<($taglist)(\\s+[^>]*?|\\s*?)(\/?>)|<(!--)/i";

		while ( '' != $text ) {
			$p = preg_split( $start, $text, 2, PREG_SPLIT_DELIM_CAPTURE );
			$stripped .= $p[0];
			if( count( $p ) < 5 ) {
				break;
			}
			if( count( $p ) > 5 ) {
				// comment
				$element    = $p[4];
				$attributes = '';
				$close      = '';
				$inside     = $p[5];
			} else {
				// tag
				$element    = $p[1];
				$attributes = $p[2];
				$close      = $p[3];
				$inside     = $p[4];
			}

			$marker = "$uniq_prefix-$element-$rand" . sprintf('%08X', $n++) . '-QINU';
			$stripped .= $marker;

			if ( $close === '/>' ) {
				// Empty element tag, <tag />
				$content = null;
				$text = $inside;
				$tail = null;
			} else {
				if( $element == '!--' ) {
					$end = '/(-->)/';
				} else {
					$end = "/(<\\/$element\\s*>)/i";
				}
				$q = preg_split( $end, $inside, 2, PREG_SPLIT_DELIM_CAPTURE );
				$content = $q[0];
				if( count( $q ) < 3 ) {
					# No end tag -- let it run out to the end of the text.
					$tail = '';
					$text = '';
				} else {
					$tail = $q[1];
					$text = $q[2];
				}
			}
			
			$matches[$marker] = array( $element,
				$content,
				Sanitizer::decodeTagAttributes( $attributes ),
				"<$element$attributes$close$content$tail" );
		}
		return $stripped;
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
	 * @private
	 */
	function strip( $text, &$state, $stripcomments = false ) {
		$render = ($this->mOutputType == OT_HTML);

		# Replace any instances of the placeholders
		$uniq_prefix = $this->mUniqPrefix;
		#$text = str_replace( $uniq_prefix, wfHtmlEscapeFirst( $uniq_prefix ), $text );
		$commentState = array();
		
		$elements = array_merge(
			array( 'nowiki', 'pre', 'gallery' ),
			array_keys( $this->mTagHooks ) );
		global $wgRawHtml;
		if( $wgRawHtml ) {
			$elements[] = 'html';
		}
		if( $this->mOptions->getUseTeX() ) {
			$elements[] = 'math';
		}
		

		$matches = array();
		$text = Parser::extractTagsAndParams( $elements, $text, $matches, $uniq_prefix );
		
		foreach( $matches as $marker => $data ) {
			list( $element, $content, $params, $tag ) = $data;
			if( $render ) {
				$tagName = strtolower( $element );
				switch( $tagName ) {
				case '!--':
					// Comment
					if( substr( $tag, -3 ) == '-->' ) {
						$output = $tag;
					} else {
						// Unclosed comment in input.
						// Close it so later stripping can remove it
						$output = "$tag-->";
					}
					break;
				case 'html':
					if( $wgRawHtml ) {
						$output = $content;
						break;
					}
					// Shouldn't happen otherwise. :)
				case 'nowiki':
					$output = wfEscapeHTMLTagsOnly( $content );
					break;
				case 'math':
					$output = renderMath( $content );
					break;
				case 'pre':
					// Backwards-compatibility hack
					$content = preg_replace( '!<nowiki>(.*?)</nowiki>!is', '\\1', $content );
					$output = '<pre>' . wfEscapeHTMLTagsOnly( $content ) . '</pre>';
					break;
				case 'gallery':
					$output = $this->renderImageGallery( $content );
					break;
				default:
					if( isset( $this->mTagHooks[$tagName] ) ) {
						$output = call_user_func_array( $this->mTagHooks[$tagName],
							array( $content, $params, &$this ) );
					} else {
						wfDebugDieBacktrace( "Invalid call hook $element" );
					}
				}
			} else {
				// Just stripping tags; keep the source
				$output = $tag;
			}
			if( !$stripcomments && $element == '!--' ) {
				$commentState[$marker] = $output;
			} else {
				$state[$element][$marker] = $output;
			}
		}

		# Unstrip comments unless explicitly told otherwise.
		# (The comments are always stripped prior to this point, so as to
		# not invoke any extension tags / parser hooks contained within
		# a comment.)
		if ( !$stripcomments ) {
			// Put them all back and forget them
			$text = strtr( $text, $commentState );
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
		if ( !is_array( $state ) ) {
			return $text;
		}

		$replacements = array();
		foreach( $state as $tag => $contentDict ) {
			if( $tag != 'nowiki' && $tag != 'html' ) {
				foreach( $contentDict as $uniq => $content ) {
					$replacements[$uniq] = $content;
				}
			}
		}
		$text = strtr( $text, $replacements );

		return $text;
	}

	/**
	 * Always call this after unstrip() to preserve the order
	 *
	 * @access private
	 */
	function unstripNoWiki( $text, &$state ) {
		if ( !is_array( $state ) ) {
			return $text;
		}

		$replacements = array();
		foreach( $state as $tag => $contentDict ) {
			if( $tag == 'nowiki' || $tag == 'html' ) {
				foreach( $contentDict as $uniq => $content ) {
					$replacements[$uniq] = $content;
				}
			}
		}
		$text = strtr( $text, $replacements );

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
			$state = array();
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
			// Theoretically, this style of communication could cause a deadlock
			// here. If the stdout buffer fills up, then writes to stdin could
			// block. This doesn't appear to happen with tidy, because tidy only
			// writes to stdout after it's finished reading from stdin. Search
			// for tidyParseStdin and tidySaveStdout in console/tidy.c
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
		$has_opened_tr = array(); # Did this table open a <tr> element?
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
				array_push ( $has_opened_tr, false );
			}
			else if ( count ( $td ) == 0 ) { } # Don't do any of the following
			else if ( '|}' == substr ( $x , 0 , 2 ) ) {
				$z = "</table>" . substr ( $x , 2);
				$l = array_pop ( $ltd ) ;
				if ( !array_pop ( $has_opened_tr ) ) $z = "<tr><td></td></tr>" . $z ;
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
				array_pop ( $has_opened_tr );
				array_push ( $has_opened_tr , true ) ;
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
				
				// Split up multiple cells on the same line.
				// FIXME: This can result in improper nesting of tags processed
				// by earlier parser steps, but should avoid splitting up eg
				// attribute values containing literal "||".
				$after = wfExplodeMarkup( '||', $after );
				
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
						array_pop ( $has_opened_tr );
						array_push ( $has_opened_tr , true ) ;
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
			$l = array_pop ( $ltd ) ;
			if ( array_pop ( $td ) ) $t[] = '</td>' ;
			if ( array_pop ( $tr ) ) $t[] = '</tr>' ;
			if ( !array_pop ( $has_opened_tr ) ) $t[] = "<tr><td></td></tr>" ;
			$t[] = '</table>' ;
		}

		$t = implode ( "\n" , $t ) ;
		# special case: don't return empty table
		if($t == "<table>\n<tr><td></td></tr>\n</table>")
			$t = '';
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
		$args = array();
		$isMain = true;
		$fname = 'Parser::internalParse';
		wfProfileIn( $fname );

		# Remove <noinclude> tags and <includeonly> sections
		$text = strtr( $text, array( '<onlyinclude>' => '' , '</onlyinclude>' => '' ) );
		$text = strtr( $text, array( '<noinclude>' => '', '</noinclude>' => '') );
		$text = preg_replace( '/<includeonly>.*?<\/includeonly>/s', '', $text );
		
		$text = Sanitizer::removeHTMLtags( $text, array( &$this, 'attributeStripCallback' ) );

		$text = $this->replaceVariables( $text, $args );

		// Tables need to come after variable replacement for things to work
		// properly; putting them before other transformations should keep
		// exciting things like link expansions from showing up in surprising
		// places.
		$text = $this->doTableStuff( $text );

		$text = preg_replace( '/(^|\n)-----*/', '\\1<hr />', $text );

		$text = $this->stripToc( $text );
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
		$text = $this->formatHeadings( $text, $isMain );

		wfProfileOut( $fname );
		return $text;
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
	 * Parse headers and return html
	 *
	 * @access private
	 */
	function doHeadings( $text ) {
		$fname = 'Parser::doHeadings';
		wfProfileIn( $fname );
		for ( $i = 6; $i >= 1; --$i ) {
			$h = str_repeat( '=', $i );
			$text = preg_replace( "/^{$h}(.+){$h}\\s*$/m",
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
			$url = str_replace( '&amp;', '&', $url );

			# Process the trail (i.e. everything after this link up until start of the next link),
			# replacing any non-bracketed links
			$trail = $this->replaceFreeExternalLinks( $trail );

			# Use the encoded URL
			# This means that users can paste URLs directly into the text
			# Funny characters like &ouml; aren't valid in URLs anyway
			# This was changed in August 2004
			$s .= $sk->makeExternalLink( $url, $text, false, $linktype ) . $dtrail . $trail;

			# Register link in the output object.
			# Replace unnecessary URL escape codes with the referenced character
			# This prevents spammers from hiding links from the filters
			$pasteurized = Parser::replaceUnusualEscapes( $url );
			$this->mOutput->addExternalLink( $pasteurized );
		}

		wfProfileOut( $fname );
		return $s;
	}

	/**
	 * Replace anything that looks like a URL with a link
	 * @access private
	 */
	function replaceFreeExternalLinks( $text ) {
		global $wgContLang;
		$fname = 'Parser::replaceFreeExternalLinks';
		wfProfileIn( $fname );

		$bits = preg_split( '/(\b(?:' . wfUrlProtocols() . '))/S', $text, -1, PREG_SPLIT_DELIM_CAPTURE );
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

				# special case: handle urls as url args:
				# http://www.example.com/foo?=http://www.example.com/bar
				if(strlen($trail) == 0 && 
					isset($bits[$i]) &&
					preg_match('/^'. wfUrlProtocols() . '$/S', $bits[$i]) &&
					preg_match( '/^('.EXT_LINK_URL_CLASS.'+)(.*)$/s', $bits[$i + 1], $m )) 
				{
					# add protocol, arg
					$url .= $bits[$i] . $m[1]; # protocol, url as arg to previous link
					$i += 2;
					$trail = $m[2];
				}

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
					# Register it in the output object...
					# Replace unnecessary URL escape codes with their equivalent characters
					$pasteurized = Parser::replaceUnusualEscapes( $url );
					$this->mOutput->addExternalLink( $pasteurized );
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
	 * Replace unusual URL escape codes with their equivalent characters
	 * @param string
	 * @return string
	 * @static
	 * @fixme This can merge genuinely required bits in the path or query string,
	 *        breaking legit URLs. A proper fix would treat the various parts of
	 *        the URL differently; as a workaround, just use the output for
	 *        statistical records, not for actual linking/output.
	 */
	function replaceUnusualEscapes( $url ) {
		return preg_replace_callback( '/%[0-9A-Fa-f]{2}/',
			array( 'Parser', 'replaceUnusualEscapesCallback' ), $url );
	}

	/**
	 * Callback function used in replaceUnusualEscapes().
	 * Replaces unusual URL escape codes with their equivalent character
	 * @static
	 * @access private
	 */
	function replaceUnusualEscapesCallback( $matches ) {
		$char = urldecode( $matches[0] );
		$ord = ord( $char );
		// Is it an unsafe or HTTP reserved character according to RFC 1738?
		if ( $ord > 32 && $ord < 127 && strpos( '<>"#{}|\^~[]`;/?', $char ) === false ) {
			// No, shouldn't be escaped
			return $char;
		} else {
			// Yes, leave it escaped
			return $matches[0];
		}
	}

	/**
	 * make an image if it's allowed, either through the global
	 * option or through the exception
	 * @access private
	 */
	function maybeMakeExternalImage( $url ) {
		$sk =& $this->mOptions->getSkin();
		$imagesfrom = $this->mOptions->getAllowExternalImagesFrom();
		$imagesexception = !empty($imagesfrom);
		$text = false;
		if ( $this->mOptions->getAllowExternalImages()
		     || ( $imagesexception && strpos( $url, $imagesfrom ) === 0 ) ) {
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
		global $wgContLang;
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
				if( $text !== '' && 
					preg_match( "/^\](.*)/s", $m[3], $n ) && 
					strpos($text, '[') !== false 
				) 
				{
					$text .= ']'; # so that replaceExternalLinks($text) works later
					$m[3] = $n[1];
				}
				# fix up urlencoded title texts
				if(preg_match('/%/', $m[1] )) 
					# Should anchors '#' also be rejected?
					$m[1] = str_replace( array('<', '>'), array('&lt;', '&gt;'), urldecode($m[1]) );
				$trail = $m[3];
			} elseif( preg_match($e1_img, $line, $m) ) { # Invalid, but might be an image with a link in its caption
				$might_be_img = true;
				$text = $m[2];
				if(preg_match('/%/', $m[1] )) $m[1] = urldecode($m[1]);
				$trail = "";
			} else { # Invalid form; output directly
				$s .= $prefix . '[[' . $line ;
				continue;
			}

			# Don't allow internal links to pages containing
			# PROTO: where PROTO is a valid URL protocol; these
			# should be external links.
			if (preg_match('/^(\b(?:' . wfUrlProtocols() . '))/', $m[1])) {
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
					$this->mOutput->addLanguageLink( $nt->getFullText() );
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
						$s .= $prefix . $this->armorLinks( $this->makeImage( $nt, $text ) ) . $trail;
						$this->mOutput->addImage( $nt->getDBkey() );

						wfProfileOut( "$fname-image" );
						continue;
					}
					wfProfileOut( "$fname-image" );

				}

				if ( $ns == NS_CATEGORY ) {
					wfProfileIn( "$fname-category" );
					$s = rtrim($s . "\n"); # bug 87

					if ( $wasblank ) {
						if ( $this->mTitle->getNamespace() == NS_CATEGORY ) {
							$sortkey = $this->mTitle->getText();
						} else {
							$sortkey = $this->mTitle->getPrefixedText();
						}
					} else {
						$sortkey = $text;
					}
					$sortkey = Sanitizer::decodeCharReferences( $sortkey );
					$sortkey = str_replace( "\n", '', $sortkey );					
					$sortkey = $wgContLang->convertCategoryKey( $sortkey );
					$this->mOutput->addCategory( $nt->getDBkey(), $sortkey );

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
				$s .= $prefix . $this->armorLinks( $link ) . $trail;
				$this->mOutput->addImage( $nt->getDBkey() );
				continue;
			} elseif( $ns == NS_SPECIAL ) {
				$s .= $this->makeKnownLinkHolder( $nt, $text, '', $trail, $prefix );
				continue;
			} elseif( $ns == NS_IMAGE ) {
				$img = Image::newFromTitle( $nt );
				if( $img->exists() ) {
					// Force a blue link if the file exists; may be a remote
					// upload on the shared repository, and we want to see its
					// auto-generated page.
					$s .= $this->makeKnownLinkHolder( $nt, $text, '', $trail, $prefix );
					continue;
				}
			}
			$s .= $this->makeLinkHolder( $nt, $text, '', $trail, $prefix );
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
	 * Render a forced-blue link inline; protect against double expansion of
	 * URLs if we're in a mode that prepends full URL prefixes to internal links.
	 * Since this little disaster has to split off the trail text to avoid
	 * breaking URLs in the following text without breaking trails on the
	 * wiki links, it's been made into a horrible function.
	 *
	 * @param Title $nt
	 * @param string $text
	 * @param string $query
	 * @param string $trail
	 * @param string $prefix
	 * @return string HTML-wikitext mix oh yuck
	 */
	function makeKnownLinkHolder( $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		list( $inside, $trail ) = Linker::splitTrail( $trail );
		$sk =& $this->mOptions->getSkin();
		$link = $sk->makeKnownLinkObj( $nt, $text, $query, $inside, $prefix );
		return $this->armorLinks( $link ) . $trail;
	}

	/**
	 * Insert a NOPARSE hacky thing into any inline links in a chunk that's
	 * going to go through further parsing steps before inline URL expansion.
	 *
	 * In particular this is important when using action=render, which causes
	 * full URLs to be included.
	 *
	 * Oh man I hate our multi-layer parser!
	 *
	 * @param string more-or-less HTML
	 * @return string less-or-more HTML with NOPARSE bits
	 */
	function armorLinks( $text ) {
		return preg_replace( "/\b(" . wfUrlProtocols() . ')/',
			"{$this->mUniqPrefix}NOPARSE$1", $text );
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
		global $wgContLang, $wgSitename, $wgServer, $wgServerName, $wgScriptPath;

		/**
		 * Some of these require message or data lookups and can be
		 * expensive to check many times.
		 */
		static $varCache = array();
		if ( wfRunHooks( 'ParserGetVariableValueVarCache', array( &$this, &$varCache ) ) )
			if ( isset( $varCache[$index] ) )
				return $varCache[$index];

		$ts = time();
		wfRunHooks( 'ParserGetVariableValueTs', array( &$this, &$ts ) );

		switch ( $index ) {
			case MAG_CURRENTMONTH:
				return $varCache[$index] = $wgContLang->formatNum( date( 'm', $ts ) );
			case MAG_CURRENTMONTHNAME:
				return $varCache[$index] = $wgContLang->getMonthName( date( 'n', $ts ) );
			case MAG_CURRENTMONTHNAMEGEN:
				return $varCache[$index] = $wgContLang->getMonthNameGen( date( 'n', $ts ) );
			case MAG_CURRENTMONTHABBREV:
				return $varCache[$index] = $wgContLang->getMonthAbbreviation( date( 'n', $ts ) );
			case MAG_CURRENTDAY:
				return $varCache[$index] = $wgContLang->formatNum( date( 'j', $ts ) );
			case MAG_CURRENTDAY2:
				return $varCache[$index] = $wgContLang->formatNum( date( 'd', $ts ) );
			case MAG_PAGENAME:
				return $this->mTitle->getText();
			case MAG_PAGENAMEE:
				return $this->mTitle->getPartialURL();
			case MAG_FULLPAGENAME:
				return $this->mTitle->getPrefixedText();
			case MAG_FULLPAGENAMEE:
				return $this->mTitle->getPrefixedURL();
			case MAG_SUBPAGENAME:
				return $this->mTitle->getSubpageText();
			case MAG_SUBPAGENAMEE:
				return $this->mTitle->getSubpageUrlForm();
			case MAG_REVISIONID:
				return $this->mRevisionId;
			case MAG_NAMESPACE:
				return $wgContLang->getNsText( $this->mTitle->getNamespace() );
			case MAG_NAMESPACEE:
				return wfUrlencode( $wgContLang->getNsText( $this->mTitle->getNamespace() ) );
			case MAG_CURRENTDAYNAME:
				return $varCache[$index] = $wgContLang->getWeekdayName( date( 'w', $ts ) + 1 );
			case MAG_CURRENTYEAR:
				return $varCache[$index] = $wgContLang->formatNum( date( 'Y', $ts ), true );
			case MAG_CURRENTTIME:
				return $varCache[$index] = $wgContLang->time( wfTimestamp( TS_MW, $ts ), false, false );
			case MAG_CURRENTWEEK:
				// @bug 4594 PHP5 has it zero padded, PHP4 does not, cast to
				// int to remove the padding
				return $varCache[$index] = $wgContLang->formatNum( (int)date( 'W', $ts ) );
			case MAG_CURRENTDOW:
				return $varCache[$index] = $wgContLang->formatNum( date( 'w', $ts ) );
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
				$ret = null;
				if ( wfRunHooks( 'ParserGetVariableValueSwitch', array( &$this, &$varCache, &$index, &$ret ) ) )
					return $ret;
				else
					return null;
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
	 * parse any parentheses in format ((title|part|part))
	 * and call callbacks to get a replacement text for any found piece
	 *
	 * @param string $text The text to parse
	 * @param array $callbacks rules in form:
	 *     '{' => array(				# opening parentheses
	 *					'end' => '}',   # closing parentheses
	 *					'cb' => array(2 => callback,	# replacement callback to call if {{..}} is found
	 *								  4 => callback 	# replacement callback to call if {{{{..}}}} is found
	 *								  )
	 *					)
	 * @access private
	 */
	function replace_callback ($text, $callbacks) {
		$openingBraceStack = array();	# this array will hold a stack of parentheses which are not closed yet
		$lastOpeningBrace = -1;			# last not closed parentheses

		for ($i = 0; $i < strlen($text); $i++) {
			# check for any opening brace
			$rule = null;
			$nextPos = -1;
			foreach ($callbacks as $key => $value) {
				$pos = strpos ($text, $key, $i);
				if (false !== $pos && (-1 == $nextPos || $pos < $nextPos)) {
					$rule = $value;
					$nextPos = $pos;
				}
			}

			if ($lastOpeningBrace >= 0) {
				$pos = strpos ($text, $openingBraceStack[$lastOpeningBrace]['braceEnd'], $i);

				if (false !== $pos && (-1 == $nextPos || $pos < $nextPos)){
					$rule = null;
					$nextPos = $pos;
				}

				$pos = strpos ($text, '|', $i);

				if (false !== $pos && (-1 == $nextPos || $pos < $nextPos)){
					$rule = null;
					$nextPos = $pos;
				}
			}

			if ($nextPos == -1)
				break;

			$i = $nextPos;

			# found openning brace, lets add it to parentheses stack
			if (null != $rule) {
				$piece = array('brace' => $text[$i],
							   'braceEnd' => $rule['end'],
							   'count' => 1,
							   'title' => '',
							   'parts' => null);

				# count openning brace characters
				while ($i+1 < strlen($text) && $text[$i+1] == $piece['brace']) {
					$piece['count']++;
					$i++;
				}

				$piece['startAt'] = $i+1;
				$piece['partStart'] = $i+1;

				# we need to add to stack only if openning brace count is enough for any given rule
				foreach ($rule['cb'] as $cnt => $fn) {
					if ($piece['count'] >= $cnt) {
						$lastOpeningBrace ++;
						$openingBraceStack[$lastOpeningBrace] = $piece;
						break;
					}
				}

				continue;
			}
			else if ($lastOpeningBrace >= 0) {
				# first check if it is a closing brace
				if ($openingBraceStack[$lastOpeningBrace]['braceEnd'] == $text[$i]) {
					# lets check if it is enough characters for closing brace
					$count = 1;
					while ($i+$count < strlen($text) && $text[$i+$count] == $text[$i])
						$count++;

					# if there are more closing parentheses than opening ones, we parse less
					if ($openingBraceStack[$lastOpeningBrace]['count'] < $count)
						$count = $openingBraceStack[$lastOpeningBrace]['count'];

					# check for maximum matching characters (if there are 5 closing characters, we will probably need only 3 - depending on the rules)
					$matchingCount = 0;
					$matchingCallback = null;
					foreach ($callbacks[$openingBraceStack[$lastOpeningBrace]['brace']]['cb'] as $cnt => $fn) {
						if ($count >= $cnt && $matchingCount < $cnt) {
							$matchingCount = $cnt;
							$matchingCallback = $fn;
						}
					}

					if ($matchingCount == 0) {
						$i += $count - 1;
						continue;
					}

					# lets set a title or last part (if '|' was found)
					if (null === $openingBraceStack[$lastOpeningBrace]['parts'])
						$openingBraceStack[$lastOpeningBrace]['title'] = substr($text, $openingBraceStack[$lastOpeningBrace]['partStart'], $i - $openingBraceStack[$lastOpeningBrace]['partStart']);
					else
						$openingBraceStack[$lastOpeningBrace]['parts'][] = substr($text, $openingBraceStack[$lastOpeningBrace]['partStart'], $i - $openingBraceStack[$lastOpeningBrace]['partStart']);

					$pieceStart = $openingBraceStack[$lastOpeningBrace]['startAt'] - $matchingCount;
					$pieceEnd = $i + $matchingCount;

					if( is_callable( $matchingCallback ) ) {
						$cbArgs = array (
										 'text' => substr($text, $pieceStart, $pieceEnd - $pieceStart),
										 'title' => trim($openingBraceStack[$lastOpeningBrace]['title']),
										 'parts' => $openingBraceStack[$lastOpeningBrace]['parts'],
										 'lineStart' => (($pieceStart > 0) && ($text[$pieceStart-1] == "\n")),
										 );
						# finally we can call a user callback and replace piece of text
						$replaceWith = call_user_func( $matchingCallback, $cbArgs );
						$text = substr($text, 0, $pieceStart) . $replaceWith . substr($text, $pieceEnd);
						$i = $pieceStart + strlen($replaceWith) - 1;
					}
					else {
						# null value for callback means that parentheses should be parsed, but not replaced
						$i += $matchingCount - 1;
					}

					# reset last openning parentheses, but keep it in case there are unused characters
					$piece = array('brace' => $openingBraceStack[$lastOpeningBrace]['brace'],
								   'braceEnd' => $openingBraceStack[$lastOpeningBrace]['braceEnd'],
								   'count' => $openingBraceStack[$lastOpeningBrace]['count'],
								   'title' => '',
								   'parts' => null,
								   'startAt' => $openingBraceStack[$lastOpeningBrace]['startAt']);
					$openingBraceStack[$lastOpeningBrace--] = null;

					if ($matchingCount < $piece['count']) {
						$piece['count'] -= $matchingCount;
						$piece['startAt'] -= $matchingCount;
						$piece['partStart'] = $piece['startAt'];
						# do we still qualify for any callback with remaining count?
						foreach ($callbacks[$piece['brace']]['cb'] as $cnt => $fn) {
							if ($piece['count'] >= $cnt) {
								$lastOpeningBrace ++;
								$openingBraceStack[$lastOpeningBrace] = $piece;
								break;
							}
						}
					}
					continue;
				}

				# lets set a title if it is a first separator, or next part otherwise
				if ($text[$i] == '|') {
					if (null === $openingBraceStack[$lastOpeningBrace]['parts']) {
						$openingBraceStack[$lastOpeningBrace]['title'] = substr($text, $openingBraceStack[$lastOpeningBrace]['partStart'], $i - $openingBraceStack[$lastOpeningBrace]['partStart']);
						$openingBraceStack[$lastOpeningBrace]['parts'] = array();
					}
					else
						$openingBraceStack[$lastOpeningBrace]['parts'][] = substr($text, $openingBraceStack[$lastOpeningBrace]['partStart'], $i - $openingBraceStack[$lastOpeningBrace]['partStart']);

					$openingBraceStack[$lastOpeningBrace]['partStart'] = $i + 1;
				}
			}
		}

		return $text;
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
	 * @param bool $argsOnly Only do argument (triple-brace) expansion, not double-brace expansion
	 * @access private
	 */
	function replaceVariables( $text, $args = array(), $argsOnly = false ) {
		# Prevent too big inclusions
		if( strlen( $text ) > MAX_INCLUDE_SIZE ) {
			return $text;
		}

		$fname = 'Parser::replaceVariables';
		wfProfileIn( $fname );

		# This function is called recursively. To keep track of arguments we need a stack:
		array_push( $this->mArgStack, $args );

		$braceCallbacks = array();
		if ( !$argsOnly ) {
			$braceCallbacks[2] = array( &$this, 'braceSubstitution' );
		}
		if ( $this->mOutputType == OT_HTML || $this->mOutputType == OT_WIKI ) {
			$braceCallbacks[3] = array( &$this, 'argSubstitution' );
		}
		$callbacks = array();
		$callbacks['{'] = array('end' => '}', 'cb' => $braceCallbacks);
		$callbacks['['] = array('end' => ']', 'cb' => array(2=>null));
		$text = $this->replace_callback ($text, $callbacks);

		array_pop( $this->mArgStack );

		wfProfileOut( $fname );
		return $text;
	}
	
	/**
	 * Replace magic variables
	 * @access private
	 */
	function variableSubstitution( $matches ) {
		$fname = 'Parser::variableSubstitution';
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
	 * @param array $piece The parts of the template
	 *  $piece['text']: matched text
	 *  $piece['title']: the title, i.e. the part before the |
	 *  $piece['parts']: the parameter array
	 * @return string the text of the template
	 * @access private
	 */
	function braceSubstitution( $piece ) {
		global $wgContLang;
		$fname = 'Parser::braceSubstitution';
		wfProfileIn( $fname );

		# Flags
		$found = false;             # $text has been filled
		$nowiki = false;            # wiki markup in $text should be escaped
		$noparse = false;           # Unsafe HTML tags should not be stripped, etc.
		$noargs = false;            # Don't replace triple-brace arguments in $text
		$replaceHeadings = false;   # Make the edit section links go to the template not the article
		$isHTML = false;            # $text is HTML, armour it against wikitext transformation
		$forceRawInterwiki = false; # Force interwiki transclusion to be done in raw mode not rendered

		# Title object, where $text came from
		$title = NULL;

		$linestart = '';

		# $part1 is the bit before the first |, and must contain only title characters
		# $args is a list of arguments, starting from index 0, not including $part1

		$part1 = $piece['title'];
		# If the third subpattern matched anything, it will start with |

		if (null == $piece['parts']) {
			$replaceWith = $this->variableSubstitution (array ($piece['text'], $piece['title']));
			if ($replaceWith != $piece['text']) {
				$text = $replaceWith;
				$found = true;
				$noparse = true;
				$noargs = true;
			}
		}

		$args = (null == $piece['parts']) ? array() : $piece['parts'];
		$argc = count( $args );

		# SUBST
		if ( !$found ) {
			$mwSubst =& MagicWord::get( MAG_SUBST );
			if ( $mwSubst->matchStartAndRemove( $part1 ) xor ($this->mOutputType == OT_WIKI) ) {
				# One of two possibilities is true:
				# 1) Found SUBST but not in the PST phase
				# 2) Didn't find SUBST and in the PST phase
				# In either case, return without further processing
				$text = $piece['text'];
				$found = true;
				$noparse = true;
				$noargs = true;
			}
		}

		# MSG, MSGNW, INT and RAW
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
			
			# Check for RAW:
			$mwRaw =& MagicWord::get( MAG_RAW );
			if ( $mwRaw->matchStartAndRemove( $part1 ) ) {
				$forceRawInterwiki = true;
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
				if ( intval( $part1 ) || $part1 == "0" ) {
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

		# LCFIRST, UCFIRST, LC and UC
		if ( !$found ) {
			$lcfirst =& MagicWord::get( MAG_LCFIRST );
			$ucfirst =& MagicWord::get( MAG_UCFIRST );
			$lc =& MagicWord::get( MAG_LC );
			$uc =& MagicWord::get( MAG_UC );
			if ( $lcfirst->matchStartAndRemove( $part1 ) ) {
				$text = $linestart . $wgContLang->lcfirst( $part1 );
				$found = true;
			} else if ( $ucfirst->matchStartAndRemove( $part1 ) ) {
				$text = $linestart . $wgContLang->ucfirst( $part1 );
				$found = true;
			} else if ( $lc->matchStartAndRemove( $part1 ) ) {
				$text = $linestart . $wgContLang->lc( $part1 );
				$found = true;
			} else if ( $uc->matchStartAndRemove( $part1 ) ) {
				 $text = $linestart . $wgContLang->uc( $part1 );
				 $found = true;
			}
		}

		# LOCALURL and FULLURL
		if ( !$found ) {
			$mwLocal =& MagicWord::get( MAG_LOCALURL );
			$mwLocalE =& MagicWord::get( MAG_LOCALURLE );
			$mwFull =& MagicWord::get( MAG_FULLURL );
			$mwFullE =& MagicWord::get( MAG_FULLURLE );


			if ( $mwLocal->matchStartAndRemove( $part1 ) ) {
				$func = 'getLocalURL';
			} elseif ( $mwLocalE->matchStartAndRemove( $part1 ) ) {
				$func = 'escapeLocalURL';
			} elseif ( $mwFull->matchStartAndRemove( $part1 ) ) {
				$func = 'getFullURL';
			} elseif ( $mwFullE->matchStartAndRemove( $part1 ) ) {
				$func = 'escapeFullURL';
			} else {
				$func = false;
			}

			if ( $func !== false ) {
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

		# PLURAL
		if ( !$found && $argc >= 2 ) {
			$mwPluralForm =& MagicWord::get( MAG_PLURAL );
			if ( $mwPluralForm->matchStartAndRemove( $part1 ) ) {
				if ($argc==2) {$args[2]=$args[1];}
				$text = $linestart . $wgContLang->convertPlural( $part1, $args[0], $args[1], $args[2]);
				$found = true;
			}
		}

		# Extensions
		if ( !$found ) {
			$colonPos = strpos( $part1, ':' );
			if ( $colonPos !== false ) {
				$function = strtolower( substr( $part1, 0, $colonPos ) );
				if ( isset( $this->mFunctionHooks[$function] ) ) {
					$funcArgs = array_merge( array( &$this, substr( $part1, $colonPos + 1 ) ), $args );
					$result = call_user_func_array( $this->mFunctionHooks[$function], $funcArgs );
					$found = true;
					if ( is_array( $result ) ) {
						$text = $linestart . $result[0];
						unset( $result[0] );

						// Extract flags into the local scope
						// This allows callers to set flags such as nowiki, noparse, found, etc.
						extract( $result );
					} else {
						$text = $linestart . $result;
					}
				}
			}
		}

		# Template table test

		# Did we encounter this template already? If yes, it is in the cache
		# and we need to check for loops.
		if ( !$found && isset( $this->mTemplates[$piece['title']] ) ) {
			$found = true;

			# Infinite loop test
			if ( isset( $this->mTemplatePath[$part1] ) ) {
				$noparse = true;
				$noargs = true;
				$found = true;
				$text = $linestart .
					'{{' . $part1 . '}}' .
					'<!-- WARNING: template loop detected -->';
				wfDebug( "$fname: template loop broken at '$part1'\n" );
			} else {
				# set $text to cached message.
				$text = $linestart . $this->mTemplates[$piece['title']];
			}
		}

		# Load from database
		$lastPathLevel = $this->mTemplatePath;
		if ( !$found ) {
			$ns = NS_TEMPLATE;
			# declaring $subpage directly in the function call
			# does not work correctly with references and breaks
			# {{/subpage}}-style inclusions
			$subpage = '';
			$part1 = $this->maybeDoSubpageLink( $part1, $subpage );
			if ($subpage !== '') {
				$ns = $this->mTitle->getNamespace();
			}
			$title = Title::newFromText( $part1, $ns );

			if ( !is_null( $title ) ) {
				if ( !$title->isExternal() ) {
					# Check for excessive inclusion
					$dbk = $title->getPrefixedDBkey();
					if ( $this->incrementIncludeCount( $dbk ) ) {
						if ( $title->getNamespace() == NS_SPECIAL && $this->mOptions->getAllowSpecialInclusion() ) {
							# Capture special page output
							$text = SpecialPage::capturePath( $title );
							if ( is_string( $text ) ) {
								$found = true;
								$noparse = true;
								$noargs = true;
								$isHTML = true;
								$this->disableCache();
							}
						} else {
							$articleContent = $this->fetchTemplate( $title );
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
				} elseif ( $title->isTrans() ) {
					// Interwiki transclusion
					if ( $this->mOutputType == OT_HTML && !$forceRawInterwiki ) {
						$text = $this->interwikiTransclude( $title, 'render' );
						$isHTML = true;
						$noparse = true;
					} else {
						$text = $this->interwikiTransclude( $title, 'raw' );
						$replaceHeadings = true;
					}
					$found = true;
				}
				
				# Template cache array insertion
				# Use the original $piece['title'] not the mangled $part1, so that
				# modifiers such as RAW: produce separate cache entries
				if( $found ) {
					$this->mTemplates[$piece['title']] = $text;
					$text = $linestart . $text;
				}
			}
		}

		# Recursive parsing, escaping and link table handling
		# Only for HTML output
		if ( $nowiki && $found && $this->mOutputType == OT_HTML ) {
			$text = wfEscapeWikiText( $text );
		} elseif ( ($this->mOutputType == OT_HTML || $this->mOutputType == OT_WIKI) && $found ) {
			if ( !$noargs ) {
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
			}

			if ( !$noparse ) {
				# If there are any <onlyinclude> tags, only include them
				if ( in_string( '<onlyinclude>', $text ) && in_string( '</onlyinclude>', $text ) ) {
					preg_match_all( '/<onlyinclude>(.*?)\n?<\/onlyinclude>/s', $text, $m );
					$text = '';
					foreach ($m[1] as $piece)
						$text .= $piece;
				}
				# Remove <noinclude> sections and <includeonly> tags
				$text = preg_replace( '/<noinclude>.*?<\/noinclude>/s', '', $text );
				$text = strtr( $text, array( '<includeonly>' => '' , '</includeonly>' => '' ) );

				if( $this->mOutputType == OT_HTML ) {
					# Strip <nowiki>, <pre>, etc.
					$text = $this->strip( $text, $this->mStripState );
					$text = Sanitizer::removeHTMLtags( $text, array( &$this, 'replaceVariables' ), $assocArgs );
				}
				$text = $this->replaceVariables( $text, $assocArgs );

				# If the template begins with a table or block-level
				# element, it should be treated as beginning a new line.
				if (!$piece['lineStart'] && preg_match('/^({\\||:|;|#|\*)/', $text)) {
					$text = "\n" . $text;
				}
			} elseif ( !$noargs ) {
				# $noparse and !$noargs
				# Just replace the arguments, not any double-brace items
				# This is used for rendered interwiki transclusion
				$text = $this->replaceVariables( $text, $assocArgs, true );
			}
		}
		# Prune lower levels off the recursion check path
		$this->mTemplatePath = $lastPathLevel;

		if ( !$found ) {
			wfProfileOut( $fname );
			return $piece['text'];
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
			return $piece['text'];
		} else {
			wfProfileOut( $fname );
			return $text;
		}
	}

	/**
	 * Fetch the unparsed text of a template and register a reference to it.
	 */
	function fetchTemplate( $title ) {
		$text = false;
		// Loop to fetch the article, with up to 1 redirect
		for ( $i = 0; $i < 2 && is_object( $title ); $i++ ) {
			$rev = Revision::newFromTitle( $title );
			$this->mOutput->addTemplate( $title, $title->getArticleID() );
			if ( !$rev ) {
				break;
			}
			$text = $rev->getText();
			if ( $text === false ) {
				break;
			}
			// Redirect?
			$title = Title::newFromRedirect( $text );
		}
		return $text;
	}

	/**
	 * Transclude an interwiki link.
	 */
	function interwikiTransclude( $title, $action ) {
		global $wgEnableScaryTranscluding, $wgCanonicalNamespaceNames;

		if (!$wgEnableScaryTranscluding)
			return wfMsg('scarytranscludedisabled');

		// The namespace will actually only be 0 or 10, depending on whether there was a leading :
		// But we'll handle it generally anyway
		if ( $title->getNamespace() ) {
			// Use the canonical namespace, which should work anywhere
			$articleName = $wgCanonicalNamespaceNames[$title->getNamespace()] . ':' . $title->getDBkey();
		} else {
			$articleName = $title->getDBkey();
		}

		$url = str_replace('$1', urlencode($articleName), Title::getInterwikiLink($title->getInterwiki()));
		$url .= "?action=$action";
		if (strlen($url) > 255)
			return wfMsg('scarytranscludetoolong');
		return $this->fetchScaryTemplateMaybeFromCache($url);
	}

	function fetchScaryTemplateMaybeFromCache($url) {
		global $wgTranscludeCacheExpiry;
		$dbr =& wfGetDB(DB_SLAVE);
		$obj = $dbr->selectRow('transcache', array('tc_time', 'tc_contents'),
				array('tc_url' => $url));
		if ($obj) {
			$time = $obj->tc_time;
			$text = $obj->tc_contents;
			if ($time && time() < $time + $wgTranscludeCacheExpiry ) {
				return $text;
			}
		}

		$text = wfGetHTTP($url);
		if (!$text)
			return wfMsg('scarytranscludefailed', $url);

		$dbw =& wfGetDB(DB_MASTER);
		$dbw->replace('transcache', array('tc_url'), array(
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
		$arg = trim( $matches['title'] );
		$text = $matches['text'];
		$inputArgs = end( $this->mArgStack );

		if ( array_key_exists( $arg, $inputArgs ) ) {
			$text = $inputArgs[$arg];
		} else if ($this->mOutputType == OT_HTML && null != $matches['parts'] && count($matches['parts']) > 0) {
			$text = $matches['parts'][0];
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
	 * Detect __TOC__ magic word and set a placeholder
	 */
	function stripToc( $text ) {
		# if the string __NOTOC__ (not case-sensitive) occurs in the HTML,
		# do not add TOC
		$mw = MagicWord::get( MAG_NOTOC );
		if( $mw->matchAndRemove( $text ) ) {
			$this->mShowToc = false;
		}
		
		$mw = MagicWord::get( MAG_TOC );
		if( $mw->match( $text ) ) {
			$this->mShowToc = true;
			$this->mForceTocPosition = true;
			
			// Set a placeholder. At the end we'll fill it in with the TOC.
			$text = $mw->replace( '<!--MWTOC-->', $text, 1 );
			
			// Only keep the first one.
			$text = $mw->replace( '', $text );
		}
		return $text;
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
		global $wgMaxTocLevel, $wgContLang;

		$doNumberHeadings = $this->mOptions->getNumberHeadings();
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

		# Get all headlines for numbering them and adding funky stuff like [edit]
		# links - this is for later, but we need the number of headlines right now
		$numMatches = preg_match_all( '/<H([1-6])(.*?'.'>)(.*?)<\/H[1-6] *>/i', $text, $matches );

		# if there are fewer than 4 headlines in the article, do not show TOC
		# unless it's been explicitly enabled.
		$enoughToc = $this->mShowToc &&
			(($numMatches >= 4) || $this->mForceTocPosition);

		# if the string __FORCETOC__ (not case-sensitive) occurs in the HTML,
		# override above conditions and always show TOC above first header
		$mw =& MagicWord::get( MAG_FORCETOC );
		if ($mw->matchAndRemove( $text ) ) {
			$this->mShowToc = true;
			$enoughToc = true;
		}

		# Never ever show TOC if no headers
		if( $numMatches < 1 ) {
			$enoughToc = false;
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

			if( $doNumberHeadings || $enoughToc ) {

				if ( $level > $prevlevel ) {
					# Increase TOC level
					$toclevel++;
					$sublevelCount[$toclevel] = 0;
					if( $toclevel<$wgMaxTocLevel ) {
						$toc .= $sk->tocIndent();
					}
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
					if( $toclevel<$wgMaxTocLevel ) {
						$toc .= $sk->tocUnindent( $prevtoclevel - $toclevel );
					}
				}
				else {
					# No change in level, end TOC line
					if( $toclevel<$wgMaxTocLevel ) {
						$toc .= $sk->tocLineEnd();
					}
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
			# Save headline for section edit hint before it's escaped
			$headline_hint = trim( $canonized_headline );
			$canonized_headline = Sanitizer::escapeId( $tocline );
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
			if( $enoughToc && ( !isset($wgMaxTocLevel) || $toclevel<$wgMaxTocLevel ) ) {
				$toc .= $sk->tocLine($anchor, $tocline, $numbering, $toclevel);
			}
			if( $showEditLink && ( !$istemplate || $templatetitle !== "" ) ) {
				if ( empty( $head[$headlineCount] ) ) {
					$head[$headlineCount] = '';
				}
				if( $istemplate )
					$head[$headlineCount] .= $sk->editSectionLinkForOther($templatetitle, $templatesection);
				else
					$head[$headlineCount] .= $sk->editSectionLink($this->mTitle, $sectionCount+1, $headline_hint);
			}

			# give headline the correct <h#> tag
			@$head[$headlineCount] .= "<a name=\"$anchor\"></a><h".$level.$matches[2][$headlineCount] .$headline.'</h'.$level.'>';

			$headlineCount++;
			if( !$istemplate )
				$sectionCount++;
		}

		if( $enoughToc ) {
			if( $toclevel<$wgMaxTocLevel ) {
				$toc .= $sk->tocUnindent( $toclevel - 1 );
			}
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
			if( $enoughToc && !$i && $isMain && !$this->mForceTocPosition ) {
				# Top anchor now in skin
				$full = $full.$toc;
			}

			if( !empty( $head[$i] ) ) {
				$full .= $head[$i];
			}
			$i++;
		}
		if( $this->mForceTocPosition ) {
			return str_replace( '<!--MWTOC-->', $toc, $full );
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
			# hack: don't replace inside thumbnail title/alt
			# attributes
			if(preg_match('/<[^>]+(alt|title)="[^">]*$/', $text)) {
				$text .= "ISBN $x";
				continue;
			}

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

			# hack: don't replace inside thumbnail title/alt
			# attributes
			if(preg_match('/<[^>]+(alt|title)="[^">]*$/', $text)) {
				$text .= $keyword . $x;
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
		$text = $this->strip( $text, $stripState, true );
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

		/* Note: This is the timestamp saved as hardcoded wikitext to
		 * the database, we use $wgContLang here in order to give
		 * everyone the same signiture and use the default one rather
		 * than the one selected in each users preferences.
		 */
		if ( isset( $wgLocaltimezone ) ) {
			$oldtz = getenv( 'TZ' );
			putenv( 'TZ='.$wgLocaltimezone );
		}
		$d = $wgContLang->timeanddate( date( 'YmdHis' ), false, false) .
		  ' (' . date( 'T' ) . ')';
		if ( isset( $wgLocaltimezone ) ) {
			putenv( 'TZ='.$oldtz );
		}

		# Variable replacement
		# Because mOutputType is OT_WIKI, this will only process {{subst:xxx}} type tags
		$text = $this->replaceVariables( $text );
		
		# Signatures
		$sigText = $this->getUserSig( $user );
		$text = strtr( $text, array(
			'~~~~~' => $d,
			'~~~~' => "$sigText $d",
			'~~~' => $sigText
		) );

		# Context links: [[|name]] and [[name (context)|]]
		#
		global $wgLegalTitleChars;
		$tc = "[$wgLegalTitleChars]";
		$np = str_replace( array( '(', ')' ), array( '', '' ), $tc ); # No parens

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
	 * Fetch the user's signature text, if any, and normalize to
	 * validated, ready-to-insert wikitext.
	 *
	 * @param User $user
	 * @return string
	 * @access private
	 */
	function getUserSig( &$user ) {
		$username = $user->getName();
		$nickname = $user->getOption( 'nickname' );
		$nickname = $nickname === '' ? $username : $nickname;
	
		if( $user->getBoolOption( 'fancysig' ) !== false ) {
			# Sig. might contain markup; validate this
			if( $this->validateSig( $nickname ) !== false ) {
				# Validated; clean up (if needed) and return it
				return( $this->cleanSig( $nickname ) );
			} else {
				# Failed to validate; fall back to the default
				$nickname = $username;
				wfDebug( "Parser::getUserSig: $username has bad XML tags in signature.\n" );
			}
		}

		# If we're still here, make it a link to the user page
		$userpage = $user->getUserPage();
		return( '[[' . $userpage->getPrefixedText() . '|' . wfEscapeWikiText( $nickname ) . ']]' );
	}

	/**
	 * Check that the user's signature contains no bad XML
	 *
	 * @param string $text
	 * @return mixed An expanded string, or false if invalid.
	 */
	function validateSig( $text ) {
		return( wfIsWellFormedXmlFragment( $text ) ? $text : false );
	}
	
	/**
	 * Clean up signature text
	 *
	 * 1) Strip ~~~, ~~~~ and ~~~~~ out of signatures
	 * 2) Substitute all transclusions
	 *
	 * @param string $text
	 * @return string Signature text
	 */
	function cleanSig( $text ) {
		$substWord = MagicWord::get( MAG_SUBST );
		$substRegex = '/\{\{(?!(?:' . $substWord->getBaseRegex() . '))/x' . $substWord->getRegexCase();
		$substText = '{{' . $substWord->getSynonym( 0 );

		$text = preg_replace( $substRegex, $substText, $text );
		$text = preg_replace( '/~{3,5}/', '', $text );
		$text = $this->replaceVariables( $text );
	
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

		$fname = "Parser::transformMsg";

		# Guard against infinite recursion
		if ( $executing ) {
			return $text;
		}
		$executing = true;

		wfProfileIn($fname);

		$this->mTitle = $wgTitle;
		$this->mOptions = $options;
		$this->mOutputType = OT_MSG;
		$this->clearState();
		$text = $this->replaceVariables( $text );

		$executing = false;
		wfProfileOut($fname);
		return $text;
	}

	/**
	 * Create an HTML-style tag, e.g. <yourtag>special text</yourtag>
	 * The callback should have the following form:
	 *    function myParserHook( $text, $params, &$parser ) { ... }
	 *
	 * Transform and return $text. Use $parser for any required context, e.g. use
	 * $parser->getTitle() and $parser->getOptions() not $wgTitle or $wgOut->mParserOptions
	 *
	 * @access public
	 *
	 * @param mixed $tag The tag to use, e.g. 'hook' for <hook>
	 * @param mixed $callback The callback function (and object) to use for the tag
	 *
	 * @return The old value of the mTagHooks array associated with the hook
	 */
	function setHook( $tag, $callback ) {
		$tag = strtolower( $tag );
		$oldVal = @$this->mTagHooks[$tag];
		$this->mTagHooks[$tag] = $callback;

		return $oldVal;
	}

	/**
	 * Create a function, e.g. {{sum:1|2|3}}
	 * The callback function should have the form:
	 *    function myParserFunction( &$parser, $arg1, $arg2, $arg3 ) { ... }
	 *
	 * The callback may either return the text result of the function, or an array with the text 
	 * in element 0, and a number of flags in the other elements. The names of the flags are 
	 * specified in the keys. Valid flags are:
	 *   found                     The text returned is valid, stop processing the template. This 
	 *                             is on by default.
	 *   nowiki                    Wiki markup in the return value should be escaped
	 *   noparse                   Unsafe HTML tags should not be stripped, etc.
	 *   noargs                    Don't replace triple-brace arguments in the return value
	 *   isHTML                    The returned text is HTML, armour it against wikitext transformation
	 *
	 * @access public
	 *
	 * @param string $name The function name. Function names are case-insensitive.
	 * @param mixed $callback The callback function (and object) to use
	 *
	 * @return The old callback function for this name, if any
	 */
	function setFunctionHook( $name, $callback ) {
		$name = strtolower( $name );
		$oldVal = @$this->mFunctionHooks[$name];
		$this->mFunctionHooks[$name] = $callback;
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
		global $wgUser;
		global $wgOutputReplace;

		$fname = 'Parser::replaceLinkHolders';
		wfProfileIn( $fname );

		$pdbks = array();
		$colours = array();
		$sk =& $this->mOptions->getSkin();
		$linkCache =& LinkCache::singleton();

		if ( !empty( $this->mLinkHolders['namespaces'] ) ) {
			wfProfileIn( $fname.'-check' );
			$dbr =& wfGetDB( DB_SLAVE );
			$page = $dbr->tableName( 'page' );
			$threshold = $wgUser->getOption('stubthreshold');

			# Sort by namespace
			asort( $this->mLinkHolders['namespaces'] );

			# Generate query
			$query = false;
			foreach ( $this->mLinkHolders['namespaces'] as $key => $ns ) {
				# Make title object
				$title = $this->mLinkHolders['titles'][$key];

				# Skip invalid entries.
				# Result will be ugly, but prevents crash.
				if ( is_null( $title ) ) {
					continue;
				}
				$pdbk = $pdbks[$key] = $title->getPrefixedDBkey();

				# Check if it's a static known link, e.g. interwiki
				if ( $title->isAlwaysKnown() ) {
					$colours[$pdbk] = 1;
				} elseif ( ( $id = $linkCache->getGoodLinkID( $pdbk ) ) != 0 ) {
					$colours[$pdbk] = 1;
					$this->mOutput->addLink( $title, $id );
				} elseif ( $linkCache->isBadLink( $pdbk ) ) {
					$colours[$pdbk] = 0;
				} else {
					# Not in the link cache, add it to the query
					if ( !isset( $current ) ) {
						$current = $ns;
						$query =  "SELECT page_id, page_namespace, page_title";
						if ( $threshold > 0 ) {
							$query .= ', page_len, page_is_redirect';
						}
						$query .= " FROM $page WHERE (page_namespace=$ns AND page_title IN(";
					} elseif ( $current != $ns ) {
						$current = $ns;
						$query .= ")) OR (page_namespace=$ns AND page_title IN(";
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
					$linkCache->addGoodLinkObj( $s->page_id, $title );
					$this->mOutput->addLink( $title, $s->page_id );

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
					$linkCache->addBadLinkObj( $title );
					$colours[$pdbk] = 0;
					$this->mOutput->addLink( $title, 0 );
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
	 */
	function renderImageGallery( $text ) {
		# Setup the parser
		$parserOptions = new ParserOptions;
		$localParser = new Parser();

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
			$nt =& Title::newFromText( $matches[1] );
			if( is_null( $nt ) ) {
				# Bogus title. Ignore these so we don't bomb out later.
				continue;
			}
			if ( isset( $matches[3] ) ) {
				$label = $matches[3];
			} else {
				$label = '';
			}

			$pout = $localParser->parse( $label , $this->mTitle, $parserOptions );
			$html = $pout->getText();

			$ig->add( new Image( $nt ), $html );

			# Only add real images (bug #5586)
			if ( $nt->getNamespace() == NS_IMAGE ) {
				$this->mOutput->addImage( $nt->getDBkey() );
			}
		}
		return $ig->toHTML();
	}

	/**
	 * Parse image options text and use it to make an image
	 */
	function makeImage( &$nt, $options ) {
		global $wgUseImageResize;

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
		$mwManualThumb =& MagicWord::get( MAG_IMG_MANUALTHUMB );
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
			if ( $wgUseImageResize && ! is_null( $mwThumb->matchVariableStartToEnd($val) ) ) {
				$thumb=true;
			} elseif ( ! is_null( $match = $mwManualThumb->matchVariableStartToEnd($val) ) ) {
				# use manually specified thumbnail
				$thumb=true;
				$manual_thumb = $match;
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

		# make sure there are no placeholders in thumbnail attributes
		# that are later expanded to html- so expand them now and
		# remove the tags
		$alt = $this->unstrip($alt, $this->mStripState); 
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

	/**#@+
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
	/**#@-*/

	/**#@+
	 * Accessor/mutator
	 */
	function Title( $x = NULL ) { return wfSetVar( $this->mTitle, $x ); }
	function Options( $x = NULL ) { return wfSetVar( $this->mOptions, $x ); }
	function OutputType( $x = NULL ) { return wfSetVar( $this->mOutputType, $x ); }
	/**#@-*/

	/**#@+
	 * Accessor
	 */
	function getTags() { return array_keys( $this->mTagHooks ); }
	/**#@-*/
}

/**
 * @todo document
 * @package MediaWiki
 */
class ParserOutput
{
	var $mText,             # The output text
		$mLanguageLinks,    # List of the full text of language links, in the order they appear
		$mCategories,       # Map of category names to sort keys
		$mContainsOldMagic, # Boolean variable indicating if the input contained variables like {{CURRENTDAY}}
		$mCacheTime,        # Time when this object was generated, or -1 for uncacheable. Used in ParserCache.
		$mVersion,          # Compatibility check
		$mTitleText,        # title text of the chosen language variant
		$mLinks,            # 2-D map of NS/DBK to ID for the links in the document. ID=zero for broken.
		$mTemplates,        # 2-D map of NS/DBK to ID for the template references. ID=zero for broken.
		$mImages,           # DB keys of the images used, in the array key only
		$mExternalLinks;    # External link URLs, in the key only

	function ParserOutput( $text = '', $languageLinks = array(), $categoryLinks = array(),
		$containsOldMagic = false, $titletext = '' )
	{
		$this->mText = $text;
		$this->mLanguageLinks = $languageLinks;
		$this->mCategories = $categoryLinks;
		$this->mContainsOldMagic = $containsOldMagic;
		$this->mCacheTime = '';
		$this->mVersion = MW_PARSER_VERSION;
		$this->mTitleText = $titletext;
		$this->mLinks = array();
		$this->mTemplates = array();
		$this->mImages = array();
		$this->mExternalLinks = array();
	}

	function getText()                   { return $this->mText; }
	function getLanguageLinks()          { return $this->mLanguageLinks; }
	function getCategoryLinks()          { return array_keys( $this->mCategories ); }
	function &getCategories()            { return $this->mCategories; }
	function getCacheTime()              { return $this->mCacheTime; }
	function getTitleText()              { return $this->mTitleText; }
	function &getLinks()                 { return $this->mLinks; }
	function &getTemplates()             { return $this->mTemplates; }
	function &getImages()                { return $this->mImages; }
	function &getExternalLinks()         { return $this->mExternalLinks; }

	function containsOldMagic()          { return $this->mContainsOldMagic; }
	function setText( $text )            { return wfSetVar( $this->mText, $text ); }
	function setLanguageLinks( $ll )     { return wfSetVar( $this->mLanguageLinks, $ll ); }
	function setCategoryLinks( $cl )     { return wfSetVar( $this->mCategories, $cl ); }
	function setContainsOldMagic( $com ) { return wfSetVar( $this->mContainsOldMagic, $com ); }
	function setCacheTime( $t )          { return wfSetVar( $this->mCacheTime, $t ); }
	function setTitleText( $t )          { return wfSetVar ($this->mTitleText, $t); }

	function addCategory( $c, $sort )    { $this->mCategories[$c] = $sort; }
	function addImage( $name )           { $this->mImages[$name] = 1; }
	function addLanguageLink( $t )       { $this->mLanguageLinks[] = $t; }
	function addExternalLink( $url )     { $this->mExternalLinks[$url] = 1; }

	function addLink( $title, $id ) {
		$ns = $title->getNamespace();
		$dbk = $title->getDBkey();
		if ( !isset( $this->mLinks[$ns] ) ) {
			$this->mLinks[$ns] = array();
		}
		$this->mLinks[$ns][$dbk] = $id;
	}

	function addTemplate( $title, $id ) {
		$ns = $title->getNamespace();
		$dbk = $title->getDBkey();
		if ( !isset( $this->mTemplates[$ns] ) ) {
			$this->mTemplates[$ns] = array();
		}
		$this->mTemplates[$ns][$dbk] = $id;
	}

	/**
	 * @deprecated
	 */
	/*
	function merge( $other ) {
		$this->mLanguageLinks = array_merge( $this->mLanguageLinks, $other->mLanguageLinks );
		$this->mCategories = array_merge( $this->mCategories, $this->mLanguageLinks );
		$this->mContainsOldMagic = $this->mContainsOldMagic || $other->mContainsOldMagic;
	}*/

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
		       $this->getCacheTime() < $touched ||
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
	var $mAllowExternalImagesFrom;   # If not, any exception?
	var $mSkin;                      # Reference to the preferred skin
	var $mDateFormat;                # Date format index
	var $mEditSection;               # Create "edit section" links
	var $mNumberHeadings;            # Automatically number headings
	var $mAllowSpecialInclusion;     # Allow inclusion of special pages
	var $mTidy;	        	 # Ask for tidy cleanup

	function getUseTeX()                        { return $this->mUseTeX; }
	function getUseDynamicDates()               { return $this->mUseDynamicDates; }
	function getInterwikiMagic()                { return $this->mInterwikiMagic; }
	function getAllowExternalImages()           { return $this->mAllowExternalImages; }
	function getAllowExternalImagesFrom()       { return $this->mAllowExternalImagesFrom; }
	function &getSkin()                         { return $this->mSkin; }
	function getDateFormat()                    { return $this->mDateFormat; }
	function getEditSection()                   { return $this->mEditSection; }
	function getNumberHeadings()                { return $this->mNumberHeadings; }
	function getAllowSpecialInclusion()         { return $this->mAllowSpecialInclusion; }
	function getTidy()		            { return $this->mTidy; }

	function setUseTeX( $x )                    { return wfSetVar( $this->mUseTeX, $x ); }
	function setUseDynamicDates( $x )           { return wfSetVar( $this->mUseDynamicDates, $x ); }
	function setInterwikiMagic( $x )            { return wfSetVar( $this->mInterwikiMagic, $x ); }
	function setAllowExternalImages( $x )       { return wfSetVar( $this->mAllowExternalImages, $x ); }
	function setAllowExternalImagesFrom( $x )   { return wfSetVar( $this->mAllowExternalImagesFrom, $x ); }
	function setDateFormat( $x )                { return wfSetVar( $this->mDateFormat, $x ); }
	function setEditSection( $x )               { return wfSetVar( $this->mEditSection, $x ); }
	function setNumberHeadings( $x )            { return wfSetVar( $this->mNumberHeadings, $x ); }
	function setAllowSpecialInclusion( $x )     { return wfSetVar( $this->mAllowSpecialInclusion, $x ); }
	function setTidy( $x )			    { return wfSetVar( $this->mTidy, $x); }
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
		global $wgUseTeX, $wgUseDynamicDates, $wgInterwikiMagic, $wgAllowExternalImages;
		global $wgAllowExternalImagesFrom, $wgAllowSpecialInclusion;
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
		$this->mAllowExternalImagesFrom = $wgAllowExternalImagesFrom;
		wfProfileIn( $fname.'-skin' );
		$this->mSkin =& $user->getSkin();
		wfProfileOut( $fname.'-skin' );
		$this->mDateFormat = $user->getOption( 'date' );
		$this->mEditSection = true;
		$this->mNumberHeadings = $user->getOption( 'numberheadings' );
		$this->mAllowSpecialInclusion = $wgAllowSpecialInclusion;
		$this->mTidy = false;
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
	$fname = 'wfNumberOfFiles';

	wfProfileIn( $fname );
	$dbr =& wfGetDB( DB_SLAVE );
	$numImages = $dbr->selectField('site_stats', 'ss_images', array(), $fname );
	wfProfileOut( $fname );

	return $numImages;
}

/**
 * Get various statistics from the database
 * @access private
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
 * Basically replacing " > and < with HTML entities ( &quot;, &gt;, &lt;)
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
