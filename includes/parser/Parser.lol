 DO NOT WANT
  NOT WANT @defgroup Parser Parser
  NOT WANT 
  NOT WANT @file
  NOT WANT @ingroup Parser
  NOT WANT File for Parser and related classes
  WANT


 DO NOT WANT
  NOT WANT PHP Parser - Processes wiki markup (which uses a more user-friendly
  NOT WANT syntax, such as "[[link]]" for making links), and provides a one-way
  NOT WANT transformation of that wiki markup it into XHTML output / markup
  NOT WANT (which in turn the browser understands, and can display).
  NOT WANT 
  NOT WANT <pre>
  NOT WANT There are five main entry points into the Parser class:
  NOT WANT parse()
  NOT WANT     produces HTML output
  NOT WANT preSaveTransform().
  NOT WANT     produces altered wiki markup.
  NOT WANT preprocess()
  NOT WANT     removes HTML comments and expands templates
  NOT WANT cleanSig()
  NOT WANT     Cleans a signature before saving it to preferences
  NOT WANT extractSections()
  NOT WANT     Extracts sections from an article for section editing
  NOT WANT getPreloadText()
  NOT WANT     Removes <noinclude> sections, and <includeonly> tags.
  NOT WANT 
  NOT WANT Globals used:
  NOT WANT    objects:   $wgLang, $wgContLang
  NOT WANT 
  NOT WANT NOT $wgArticle, $wgUser or $wgTitle. Keep them away!
  NOT WANT 
  NOT WANT settings:
  NOT WANT  $wgUseTex*, $wgUseDynamicDates*, $wgInterwikiMagic*,
  NOT WANT  $wgNamespacesWithSubpages, $wgAllowExternalImages*,
  NOT WANT  $wgLocaltimezone, $wgAllowSpecialInclusion*,
  NOT WANT  $wgMaxArticleSize*
  NOT WANT 
  NOT WANT  * only within ParserOptions
  NOT WANT </pre>
  NOT WANT 
  NOT WANT @ingroup Parser
  WANT
 IM IN UR SPECIAL Parser
	 DO NOT WANT
	  NOT WANT Update this version number when the ParserOutput format
	  NOT WANT changes in an incompatible way, so the parser cache
	  NOT WANT can automatically discard old data.
	  WANT
	 I ALWAYZ HAS VERSION IZ '1.6.4';

	 BTW Flags for Parser::setFunctionHook
	 BTW Also available as global constants from Defines.php
	 I ALWAYZ HAS SFH_NO_HASH IZ 1;
	 I ALWAYZ HAS SFH_OBJECT_ARGS IZ 2;

	 BTW Constants needed for external link processing
	 BTW Everything except bracket, space, or control characters
	 I ALWAYZ HAS EXT_LINK_URL_CLASS IZ '[^][<>"\\x00-\\x20\\x7F]';
	 I ALWAYZ HAS EXT_IMAGE_REGEX IZ '/^(http:\/\/|https:\/\/)([^][<>"\\x00-\\x20\\x7F]+)
		\\/([A-Za-z0-9_.,~%\\-+&;#*?!=()@\\x80-\\xFF]+)\\.((?i)gif|png|jpg|jpeg)$/Sx';

	 BTW State constants for the definition list colon extraction
	 I ALWAYZ HAS COLON_STATE_TEXT IZ 0;
	 I ALWAYZ HAS COLON_STATE_TAG IZ 1;
	 I ALWAYZ HAS COLON_STATE_TAGSTART IZ 2;
	 I ALWAYZ HAS COLON_STATE_CLOSETAG IZ 3;
	 I ALWAYZ HAS COLON_STATE_TAGSLASH IZ 4;
	 I ALWAYZ HAS COLON_STATE_COMMENT IZ 5;
	 I ALWAYZ HAS COLON_STATE_COMMENTDASH IZ 6;
	 I ALWAYZ HAS COLON_STATE_COMMENTDASHDASH IZ 7;

	 BTW Flags for preprocessToDom
	 I ALWAYZ HAS PTD_FOR_INCLUSION IZ 1;

	 BTW Allowed values for $this->mOutputType
	 BTW Parameter to startExternalParse().
	 I ALWAYZ HAS OT_HTML IZ 1; # like parse()
	 I ALWAYZ HAS OT_WIKI IZ 2; # like preSaveTransform()
	 I ALWAYZ HAS OT_PREPROCESS IZ 3; # like preprocess()
	 I ALWAYZ HAS OT_MSG IZ 3;
	 I ALWAYZ HAS OT_PLAIN IZ 4; # like extractSections() - portions of the original are returned unchanged.

	 BTW Marker Suffix needs to be accessible staticly.
	 I ALWAYZ HAS MARKER_SUFFIX IZ "-QINU\x7f";

	 BTW Persistent:
	 I HAS UR $mTagHooks, $mTransparentTagHooks, $mFunctionHooks, $mFunctionSynonyms, $mVariables
	 I HAS UR $mSubstWords, $mImageParams, $mImageParamsMagicArray, $mStripList, $mMarkerIndex
	 I HAS UR $mPreprocessor, $mExtLinkBracketedRegex, $mUrlProtocols, $mDefaultStripList
	 I HAS UR $mVarCache, $mConf, $mFunctionTagHooks


	 BTW Cleared with clearState():
	 I HAS UR $mOutput, $mAutonumber, $mDTopen, $mStripState
	 I HAS UR $mIncludeCount, $mArgStack, $mLastSection, $mInPre
	 I HAS UR $mLinkHolders, $mLinkID
	 I HAS UR $mIncludeSizes, $mPPNodeCount, $mDefaultSort
	 I HAS UR $mTplExpandCache # empty-frame expansion cache
	 I HAS UR $mTplRedirCache, $mTplDomCache, $mHeadings, $mDoubleUnderscores
	 I HAS UR $mExpensiveFunctionCount # number of expensive parser function calls

	 BTW Temporary
	 BTW These are variables reset at least once per parse regardless of $clearState
	 I HAS UR $mOptions      # ParserOptions object
	 I HAS UR $mTitle        # Title context, used for self-link rendering and similar things
	 I HAS UR $mOutputType   # Output type, one of the OT_xxx constants
	 I HAS UR $ot            # Shortcut alias, see setOutputType()
	 I HAS UR $mRevisionId   # ID to display in {{REVISIONID}} tags
	 I HAS UR $mRevisionTimestamp # The timestamp of the specified revision ID
	 I HAS UR $mRevIdForTs   # The revision ID which was used to fetch the timestamp

	 DO NOT WANT
	  NOT WANT Constructor
	  NOT WANT 
	  NOT WANT @public
	  WANT
	 SO IM LIKE __construct WITH UR $conf = array()
		 UR SPECIAL mConf IZ $conf;
		 UR SPECIAL mTagHooks IZ EMPTY;
		 UR SPECIAL mTransparentTagHooks IZ EMPTY;
		 UR SPECIAL mFunctionHooks IZ EMPTY;
		 UR SPECIAL mFunctionTagHooks IZ EMPTY;
		 UR SPECIAL mFunctionSynonyms IZ BUCKET 0 => array(), 1 => array() );
		 UR SPECIAL mDefaultStripList IZ $this->mStripList = array();
		 UR SPECIAL mUrlProtocols IZ wfUrlProtocols();
		 UR SPECIAL mExtLinkBracketedRegex IZ '/\[(\b(' . wfUrlProtocols() . ')'.
			'[^][<>"\\x00-\\x20\\x7F]+) *([^\]\\x0a\\x0d]*?)\]/S';
		 UR SPECIAL mVarCache IZ EMPTY;
		 IZ isset( $conf['preprocessorClass'] )
			 UR SPECIAL mPreprocessorClass IZ $conf['preprocessorClass'];
		 ORLY extension_loaded( 'domxml' )
			 BTW PECL extension that conflicts with the core DOM extension (bug 13770)
			 IM ON UR wfDebug DOING "Warning: you have the obsolete domxml extension for PHP. Please remove it!\n"
			 UR SPECIAL mPreprocessorClass IZ 'Preprocessor_Hash';
		 ORLY extension_loaded( 'dom' )
			 UR SPECIAL mPreprocessorClass IZ 'Preprocessor_DOM';
		 NOWAI
			 UR SPECIAL mPreprocessorClass IZ 'Preprocessor_Hash';
		 KTHX
		 UR SPECIAL mMarkerIndex IZ 0;
		 UR SPECIAL mFirstCall IZ true;
	 KTHX

	 DO NOT WANT
	  NOT WANT Reduce memory usage to reduce the impact of circular references
	  WANT
	 SO IM LIKE __destruct
		 IZ isset( $this->mLinkHolders )
			 IM ON UR SPECIAL mLinkHolders->__destruct
		 KTHX
		 IM IN UR this ITZA name => $value
			 IM ON UR unset DOING $this->$name
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Do various kinds of initialisation on the first call of the parser
	  WANT
	 SO IM LIKE firstCallInit
		 IZ !$this->mFirstCall
			return;
		 KTHX
		 UR SPECIAL mFirstCall IZ false;

		 IM ON UR wfProfileIn DOING __METHOD__

		CoreParserFunctions::register( $this );
		CoreTagHooks::register( $this );
		 IM ON UR SPECIAL initialiseVariables

		 IM ON UR wfRunHooks DOING 'ParserFirstCallInit', array( &$this )
		 IM ON UR wfProfileOut DOING __METHOD__
	 KTHX

	 DO NOT WANT
	  NOT WANT Clear Parser state
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE clearState
		 IM ON UR wfProfileIn DOING __METHOD__
		 IZ $this->mFirstCall
			 IM ON UR SPECIAL firstCallInit
		 KTHX
		 UR SPECIAL mOutput IZ new ParserOutput;
		 UR SPECIAL mAutonumber IZ 0;
		 UR SPECIAL mLastSection IZ '';
		 UR SPECIAL mDTopen IZ false;
		 UR SPECIAL mIncludeCount IZ EMPTY;
		 UR SPECIAL mStripState IZ new StripState;
		 UR SPECIAL mArgStack IZ false;
		 UR SPECIAL mInPre IZ false;
		 UR SPECIAL mLinkHolders IZ new LinkHolderArray( $this );
		 UR SPECIAL mLinkID IZ 0;
		 UR SPECIAL mRevisionTimestamp IZ $this->mRevisionId = null;
		 UR SPECIAL mVarCache IZ EMPTY;

		 DO NOT WANT
		  NOT WANT Prefix for temporary replacement strings for the multipass parser.
		  NOT WANT \x07 should never appear in input as it's disallowed in XML.
		  NOT WANT Using it at the front also gives us a little extra robustness
		  NOT WANT since it shouldn't match when butted up against identifier-like
		  NOT WANT string constructs.
		  NOT WANT 
		  NOT WANT Must not consist of all title characters, or else it will change
		  NOT WANT the behaviour of <nowiki> in a link.
		  WANT
		 BTW $this->mUniqPrefix = "\x07UNIQ" . Parser::getRandomString();
		 BTW Changed to \x7f to allow XML double-parsing -- TS
		 UR SPECIAL mUniqPrefix IZ "\x7fUNIQ" . self::getRandomString();


		 BTW Clear these on every parse, bug 4549
		 UR SPECIAL mTplExpandCache IZ $this->mTplRedirCache = $this->mTplDomCache = array();

		 UR SPECIAL mShowToc IZ true;
		 UR SPECIAL mForceTocPosition IZ false;
		 UR SPECIAL mIncludeSizes IZ BUCKET
			'post-expand' => 0,
			'arg' => 0,
		 BUCKET
		 UR SPECIAL mPPNodeCount IZ 0;
		 UR SPECIAL mDefaultSort IZ false;
		 UR SPECIAL mHeadings IZ EMPTY;
		 UR SPECIAL mDoubleUnderscores IZ EMPTY;
		 UR SPECIAL mExpensiveFunctionCount IZ 0;

		 BTW Fix cloning
		 IZ isset( $this->mPreprocessor ) && $this->mPreprocessor->parser !== $this
			 UR SPECIAL mPreprocessor IZ null;
		 KTHX

		 IM ON UR wfRunHooks DOING 'ParserClearState', array( &$this )
		 IM ON UR wfProfileOut DOING __METHOD__
	 KTHX

	 SO IM LIKE setOutputType WITH UR $ot
		 UR SPECIAL mOutputType IZ $ot;
		 BTW Shortcut alias
		 UR SPECIAL ot IZ BUCKET
			'html' => $ot == self::OT_HTML,
			'wiki' => $ot == self::OT_WIKI,
			'pre' => $ot == self::OT_PREPROCESS,
			'plain' => $ot == self::OT_PLAIN,
		 BUCKET
	 KTHX

	 DO NOT WANT
	  NOT WANT Set the context title
	  WANT
	 SO IM LIKE setTitle WITH UR $t
 		 IZ !$t || $t instanceof FakeTitle
 			 I HAS t IZ Title::newFromText( 'NO TITLE' )
 		 KTHX

		 IZ strval( $t->getFragment() ) !== ''
			 BTW Strip the fragment to avoid various odd effects
			 UR SPECIAL mTitle IZ clone $t;
			 IM ON UR SPECIAL mTitle->setFragment DOING ''
		 NOWAI
			 UR SPECIAL mTitle IZ $t;
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Accessor for mUniqPrefix.
	  NOT WANT 
	  NOT WANT @public
	  WANT
	 SO IM LIKE uniqPrefix
		 IZ !isset( $this->mUniqPrefix )
			 BTW @todo Fixme: this is probably *horribly wrong*
			 BTW LanguageConverter seems to want $wgParser's uniqPrefix, however
			 BTW if this is called for a parser cache hit, the parser may not
			 BTW have ever been initialized in the first place.
			 BTW Not really sure what the heck is supposed to be going on here.
			 I FOUND MAH ''
			 BTW throw new MWException( "Accessing uninitialized mUniqPrefix" );
		 KTHX
		 I FOUND MAH $this->mUniqPrefix
	 KTHX

	 DO NOT WANT
	  NOT WANT Convert wikitext to HTML
	  NOT WANT Do not call this function recursively.
	  NOT WANT 
	  NOT WANT @param $text String: text we want to parse
	  NOT WANT @param $title A title object
	  NOT WANT @param $options ParserOptions
	  NOT WANT @param $linestart boolean
	  NOT WANT @param $clearState boolean
	  NOT WANT @param $revid Int: number to pass in {{REVISIONID}}
	  NOT WANT @return ParserOutput a ParserOutput
	  WANT
	 SO IM LIKE parse WITH UR $text, Title $title, ParserOptions $options, $linestart = true, $clearState = true, $revid = null
		 DO NOT WANT
		  NOT WANT First pass--just handle <nowiki> sections, pass the rest off
		  NOT WANT to internalParse() which does all the real work.
		  WANT

		 I HAS UR $wgUseTidy, $wgAlwaysUseTidy, $wgContLang, $wgDisableLangConversion ON UR INTERNETS
		 I HAS fname IZ __METHOD__.'-' . wfGetCaller()
		 IM ON UR wfProfileIn DOING __METHOD__
		 IM ON UR wfProfileIn DOING $fname

		 IZ $clearState
			 IM ON UR SPECIAL clearState
		 KTHX

		 UR SPECIAL mOptions IZ $options;
		 IM ON UR SPECIAL setTitle DOING $title # Page title has to be set for the pre-processor

		 I HAS oldRevisionId IZ $this->mRevisionId
		 I HAS oldRevisionTimestamp IZ $this->mRevisionTimestamp
		 IZ $revid !== null
			 UR SPECIAL mRevisionId IZ $revid;
			 UR SPECIAL mRevisionTimestamp IZ null;
		 KTHX
		 IM ON UR SPECIAL setOutputType DOING self::OT_HTML
		 IM ON UR wfRunHooks DOING 'ParserBeforeStrip', array( &$this, &$text, &$this->mStripState )
		 BTW No more strip!
		 IM ON UR wfRunHooks DOING 'ParserAfterStrip', array( &$this, &$text, &$this->mStripState )
		 I HAS text IZ $this->internalParse( $text )

		 I HAS text IZ $this->mStripState->unstripGeneral( $text )

		 BTW Clean up special characters, only run once, next-to-last before doBlockLevels
		 I HAS fixtags IZ BUCKET
			 BTW french spaces, last one Guillemet-left
			 BTW only if there is something before the space
			'/(.) (?=\\?|:|;|!|%|\\302\\273)/' => '\\1&nbsp;\\2',
			 BTW french spaces, Guillemet-right
			'/(\\302\\253) /' => '\\1&nbsp;',
			'/&nbsp;(!\s*important)/' => ' \\1', # Beware of CSS magic word !important, bug #11874.
		 BUCKET
		 I HAS text IZ preg_replace( array_keys( $fixtags ), array_values( $fixtags ), $text )

		 I HAS text IZ $this->doBlockLevels( $text, $linestart )

		 IM ON UR SPECIAL replaceLinkHolders DOING $text

		 BTW The position of the convert() call should not be changed. it
		 BTW assumes that the links are all replaced and the only thing left
		 BTW is the <nowiki> mark.
		if ( !( $wgDisableLangConversion
				|| isset( $this->mDoubleUnderscores['nocontentconvert'] )
				|| $this->mTitle->isTalkPage()
				|| $this->mTitle->isConversionTable() ) ) {
			 I HAS text IZ $wgContLang->convert( $text )
		 KTHX

		 BTW A title may have been set in a conversion rule.
		 BTW Note that if a user tries to set a title in a conversion
		 BTW rule but content conversion was not done, then the parser
		 BTW won't pick it up.  This is probably expected behavior.
		 IZ $wgContLang->getConvRuleTitle()
			 IM ON UR SPECIAL mOutput->setTitleText DOING $wgContLang->getConvRuleTitle()
		 KTHX

		 I HAS text IZ $this->mStripState->unstripNoWiki( $text )

		 IM ON UR wfRunHooks DOING 'ParserBeforeTidy', array( &$this, &$text )

//!JF Move to its own function

		 I HAS uniq_prefix IZ $this->mUniqPrefix
		 I HAS matches IZ EMPTY
		 I HAS elements IZ array_keys( $this->mTransparentTagHooks )
		 I HAS text IZ self::extractTagsAndParams( $elements, $text, $matches, $uniq_prefix )

		 IM IN UR matches ITZA marker => $data
			list( $element, $content, $params, $tag ) = $data;
			 I HAS tagName IZ strtolower( $element )
			 IZ isset( $this->mTransparentTagHooks[$tagName] )
				 I HAS output IZ call_user_func_array( $this->mTransparentTagHooks[$tagName], array( $content, $params, $this ) )
			 NOWAI
				 I HAS output IZ $tag
			 KTHX
			 IM ON UR SPECIAL mStripState->general->setPair DOING $marker, $output
		 KTHX
		 I HAS text IZ $this->mStripState->unstripGeneral( $text )

		 I HAS text IZ Sanitizer::normalizeCharReferences( $text )

		 IZ ( $wgUseTidy && $this->mOptions->mTidy ) || $wgAlwaysUseTidy
			 I HAS text IZ MWTidy::tidy( $text )
		 NOWAI
			 BTW attempt to sanitize at least some nesting problems
			 BTW (bug #2702 and quite a few others)
			 I HAS tidyregs IZ BUCKET
				 BTW ''Something [http://www.cool.com cool''] -->
				 BTW <i>Something</i><a href="http://www.cool.com"..><i>cool></i></a>
				'/(<([bi])>)(<([bi])>)?([^<]*)(<\/?a[^<]*>)([^<]*)(<\/\\4>)?(<\/\\2>)/' =>
				'\\1\\3\\5\\8\\9\\6\\1\\3\\7\\8\\9',
				 BTW fix up an anchor inside another anchor, only
				 BTW at least for a single single nested link (bug 3695)
				'/(<a[^>]+>)([^<]*)(<a[^>]+>[^<]*)<\/a>(.*)<\/a>/' =>
				'\\1\\2</a>\\3</a>\\1\\4</a>',
				 BTW fix div inside inline elements- doBlockLevels won't wrap a line which
				 BTW contains a div, so fix it up here; replace
				 BTW div with escaped text
				'/(<([aib]) [^>]+>)([^<]*)(<div([^>]*)>)(.*)(<\/div>)([^<]*)(<\/\\2>)/' =>
				'\\1\\3&lt;div\\5&gt;\\6&lt;/div&gt;\\8\\9',
				 BTW remove empty italic or bold tag pairs, some
				 BTW introduced by rules above
				'/<([bi])><\/\\1>/' => '',
			 BUCKET

			 text IZ preg_replace(
				array_keys( $tidyregs ),
				array_values( $tidyregs ),
				$text );
		 KTHX
		 I HAS UR $wgExpensiveParserFunctionLimit ON UR INTERNETS
		 IZ $this->mExpensiveFunctionCount > $wgExpensiveParserFunctionLimit
			 IM ON UR SPECIAL limitationWarn DOING 'expensive-parserfunction', $this->mExpensiveFunctionCount, $wgExpensiveParserFunctionLimit
		 KTHX

		 IM ON UR wfRunHooks DOING 'ParserAfterTidy', array( &$this, &$text )

		 BTW Information on include size limits, for the benefit of users who try to skirt them
		 IZ $this->mOptions->getEnableLimitReport()
			 I HAS max IZ $this->mOptions->getMaxIncludeSize()
			 I HAS PFreport IZ "Expensive parser function count: {$this->mExpensiveFunctionCount}/$wgExpensiveParserFunctionLimit\n"
			$limitReport =
				"NewPP limit report\n" .
				"Preprocessor node count: {$this->mPPNodeCount}/{$this->mOptions->mMaxPPNodeCount}\n" .
				"Post-expand include size: {$this->mIncludeSizes['post-expand']}/$max bytes\n" .
				"Template argument size: {$this->mIncludeSizes['arg']}/$max bytes\n".
				 I HAS PFreport
			 IM ON UR wfRunHooks DOING 'ParserLimitReport', array( $this, &$limitReport )
			 text HAS MOAR "\n<!-- \n$limitReport-->\n";
		 KTHX
		 IM ON UR SPECIAL mOutput->setText DOING $text

		 UR SPECIAL mRevisionId IZ $oldRevisionId;
		 UR SPECIAL mRevisionTimestamp IZ $oldRevisionTimestamp;
		 IM ON UR wfProfileOut DOING $fname
		 IM ON UR wfProfileOut DOING __METHOD__

		 I FOUND MAH $this->mOutput
	 KTHX

	 DO NOT WANT
	  NOT WANT Recursive parser entry point that can be called from an extension tag
	  NOT WANT hook.
	  NOT WANT 
	  NOT WANT If $frame is not provided, then template variables (e.g., {{{1}}}) within $text are not expanded
	  NOT WANT 
	  NOT WANT @param $text String: text extension wants to have parsed
	  NOT WANT @param PPFrame $frame: The frame to use for expanding any template variables
	  WANT
	 SO IM LIKE recursiveTagParse WITH UR $text, $frame=false
		 IM ON UR wfProfileIn DOING __METHOD__
		 IM ON UR wfRunHooks DOING 'ParserBeforeStrip', array( &$this, &$text, &$this->mStripState )
		 IM ON UR wfRunHooks DOING 'ParserAfterStrip', array( &$this, &$text, &$this->mStripState )
		 I HAS text IZ $this->internalParse( $text, false, $frame )
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Expand templates and variables in the text, producing valid, static wikitext.
	  NOT WANT Also removes comments.
	  WANT
	 SO IM LIKE preprocess WITH UR $text, $title, $options, $revid = null
		 IM ON UR wfProfileIn DOING __METHOD__
		 IM ON UR SPECIAL clearState
		 IM ON UR SPECIAL setOutputType DOING self::OT_PREPROCESS
		 UR SPECIAL mOptions IZ $options;
		 IM ON UR SPECIAL setTitle DOING $title
		 IZ $revid !== null
			 UR SPECIAL mRevisionId IZ $revid;
		 KTHX
		 IM ON UR wfRunHooks DOING 'ParserBeforeStrip', array( &$this, &$text, &$this->mStripState )
		 IM ON UR wfRunHooks DOING 'ParserAfterStrip', array( &$this, &$text, &$this->mStripState )
		 I HAS text IZ $this->replaceVariables( $text )
		 I HAS text IZ $this->mStripState->unstripBoth( $text )
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Process the wikitext for the ?preload= feature. (bug 5210)
	  NOT WANT 
	  NOT WANT <noinclude>, <includeonly> etc. are parsed as for template transclusion, 
	  NOT WANT comments, templates, arguments, tags hooks and parser functions are untouched.
	  WANT
	 SO IM LIKE getPreloadText WITH UR $text, $title, $options
		 BTW Parser (re)initialisation
		 IM ON UR SPECIAL clearState
		 IM ON UR SPECIAL setOutputType DOING self::OT_PLAIN
		 UR SPECIAL mOptions IZ $options;
		 IM ON UR SPECIAL setTitle DOING $title 

		 I HAS flags IZ PPFrame::NO_ARGS | PPFrame::NO_TEMPLATES
		 I HAS dom IZ $this->preprocessToDom( $text, self::PTD_FOR_INCLUSION )
		 I FOUND MAH $this->getPreprocessor()->newFrame()->expand( $dom, $flags )
	 KTHX

	 DO NOT WANT
	  NOT WANT Get a random string
	  NOT WANT 
	  NOT WANT @private
	  NOT WANT @static
	  WANT
	 SO IM LIKE getRandomString
		 I FOUND MAH dechex( mt_rand( 0, 0x7fffffff ) ) . dechex( mt_rand( 0, 0x7fffffff ) )
	 KTHX

	function &getTitle() { return $this->mTitle; }
	 SO IM LIKE getOptions TESTING UR return $this->mOptions;
	 SO IM LIKE getRevisionId TESTING UR return $this->mRevisionId;
	 SO IM LIKE getOutput TESTING UR return $this->mOutput;
	 SO IM LIKE nextLinkID TESTING UR return $this->mLinkID++;

	 SO IM LIKE getFunctionLang
		 I HAS UR $wgLang, $wgContLang ON UR INTERNETS

		 I HAS target IZ $this->mOptions->getTargetLanguage()
		 IZ $target !== null
			 I FOUND MAH $target
		 NOWAI
			 I FOUND MAH $this->mOptions->getInterfaceMessage() ? $wgLang : $wgContLang
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Get a preprocessor object
	  WANT
	 SO IM LIKE getPreprocessor
		 IZ !isset( $this->mPreprocessor )
			 I HAS class IZ $this->mPreprocessorClass
			 UR SPECIAL mPreprocessor IZ new $class( $this );
		 KTHX
		 I FOUND MAH $this->mPreprocessor
	 KTHX

	 DO NOT WANT
	  NOT WANT Replaces all occurrences of HTML-style comments and the given tags
	  NOT WANT in the text with a random marker and returns the next text. The output
	  NOT WANT parameter $matches will be an associative array filled with data in
	  NOT WANT the form:
	  NOT WANT   'UNIQ-xxxxx' => array(
	  NOT WANT     'element',
	  NOT WANT     'tag content',
	  NOT WANT     array( 'param' => 'x' ),
	  NOT WANT     '<element param="x">tag content</element>' ) )
	  NOT WANT 
	  NOT WANT @param $elements list of element names. Comments are always extracted.
	  NOT WANT @param $text Source text string.
	  NOT WANT @param $uniq_prefix
	  NOT WANT 
	  NOT WANT @public
	  NOT WANT @static
	  WANT
	 SO IM LIKE extractTagsAndParams WITH UR $elements, $text, &$matches, $uniq_prefix = ''
		static $n = 1;
		 I HAS stripped IZ ''
		 I HAS matches IZ EMPTY

		 I HAS taglist IZ implode( '|', $elements )
		 I HAS start IZ "/<($taglist)(\\s+[^>]*?|\\s*?)(\/?" . ">)|<(!--)/i"

		 STEALIN UR $text != ''
			 I HAS p IZ preg_split( $start, $text, 2, PREG_SPLIT_DELIM_CAPTURE )
			 stripped HAS MOAR $p[0];
			 IZ count( $p ) < 5
				break;
			 KTHX
			 IZ count( $p ) > 5
				 BTW comment
				 I HAS element IZ $p[4]
				 I HAS attributes IZ ''
				 I HAS close IZ ''
				 I HAS inside IZ $p[5]
			 NOWAI
				 BTW tag
				 I HAS element IZ $p[1]
				 I HAS attributes IZ $p[2]
				 I HAS close IZ $p[3]
				 I HAS inside IZ $p[4]
			 KTHX

			 I HAS marker IZ "$uniq_prefix-$element-" . sprintf( '%08X', $n++ ) . self::MARKER_SUFFIX
			 stripped HAS MOAR $marker;

			 IZ $close === '/>'
				 BTW Empty element tag, <tag />
				 I HAS content
				 I HAS text IZ $inside
				 I HAS tail
			 NOWAI
				 IZ $element === '!--'
					 I HAS end IZ '/(-->)/'
				 NOWAI
					 I HAS end IZ "/(<\\/$element\\s*>)/i"
				 KTHX
				 I HAS q IZ preg_split( $end, $inside, 2, PREG_SPLIT_DELIM_CAPTURE )
				 I HAS content IZ $q[0]
				 IZ count( $q ) < 3
					 BTW No end tag -- let it run out to the end of the text.
					 I HAS tail IZ ''
					 I HAS text IZ ''
				 NOWAI
					 I HAS tail IZ $q[1]
					 I HAS text IZ $q[2]
				 KTHX
			 KTHX

			$matches[$marker] = array( $element,
				$content,
				Sanitizer::decodeTagAttributes( $attributes ),
				"<$element$attributes$close$content$tail" );
		 KTHX
		 I FOUND MAH $stripped
	 KTHX

	 DO NOT WANT
	  NOT WANT Get a list of strippable XML-like elements
	  WANT
	 SO IM LIKE getStripList
		 I FOUND MAH $this->mStripList
	 KTHX

	 DO NOT WANT
	  NOT WANT @deprecated use replaceVariables
	  WANT
	 SO IM LIKE strip WITH UR $text, $state, $stripcomments = false , $dontstrip = array()
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Restores pre, math, and other extensions removed by strip()
	  NOT WANT 
	  NOT WANT always call unstripNoWiki() after this one
	  NOT WANT @private
	  NOT WANT @deprecated use $this->mStripState->unstrip()
	  WANT
	 SO IM LIKE unstrip WITH UR $text, $state
		 I FOUND MAH $state->unstripGeneral( $text )
	 KTHX

	 DO NOT WANT
	  NOT WANT Always call this after unstrip() to preserve the order
	  NOT WANT 
	  NOT WANT @private
	  NOT WANT @deprecated use $this->mStripState->unstrip()
	  WANT
	 SO IM LIKE unstripNoWiki WITH UR $text, $state
		 I FOUND MAH $state->unstripNoWiki( $text )
	 KTHX

	 DO NOT WANT
	  NOT WANT @deprecated use $this->mStripState->unstripBoth()
	  WANT
	 SO IM LIKE unstripForHTML WITH UR $text
		 I FOUND MAH $this->mStripState->unstripBoth( $text )
	 KTHX

	 DO NOT WANT
	  NOT WANT Add an item to the strip state
	  NOT WANT Returns the unique tag which must be inserted into the stripped text
	  NOT WANT The tag will be replaced with the original text in unstrip()
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE insertStripItem WITH UR $text
		 I HAS rnd IZ "{$this->mUniqPrefix}-item-{$this->mMarkerIndex}-" . self::MARKER_SUFFIX
		$this->mMarkerIndex++;
		 IM ON UR SPECIAL mStripState->general->setPair DOING $rnd, $text
		 I FOUND MAH $rnd
	 KTHX

	 DO NOT WANT
	  NOT WANT Interface with html tidy
	  NOT WANT @deprecated Use MWTidy::tidy()
	  WANT
	 SO IM ALWAYS LIKE tidy WITH UR $text
		 IM ON UR wfDeprecated DOING __METHOD__
		 I FOUND MAH MWTidy::tidy( $text )
	 KTHX

	 DO NOT WANT
	  NOT WANT parse the wiki syntax used to render tables
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE doTableStuff WITH UR $text
		 IM ON UR wfProfileIn DOING __METHOD__

		 I HAS lines IZ StringUtils::explode( "\n", $text )
		 I HAS out IZ ''
		 I HAS td_history IZ EMPTY # Is currently a td tag open?
		 I HAS last_tag_history IZ EMPTY # Save history of last lag activated (td, th or caption)
		 I HAS tr_history IZ EMPTY # Is currently a tr tag open?
		 I HAS tr_attributes IZ EMPTY # history of tr attributes
		 I HAS has_opened_tr IZ EMPTY # Did this table open a <tr> element?
		 I HAS indent_level IZ 0 # indent level of the table

		 IM IN UR lines ITZA outLine
			 I HAS line IZ trim( $outLine )

			 IZ $line == '' # empty line, go to next line
				 out HAS MOAR $outLine."\n";
				continue;
			 KTHX
			 I HAS first_character IZ $line[0]
			 I HAS matches IZ EMPTY

			 IZ preg_match( '/^(:*)\{\|(.*)$/', $line , $matches )
				 BTW First check if we are starting a new table
				 I HAS indent_level IZ strlen( $matches[1] )

				 I HAS attributes IZ $this->mStripState->unstripBoth( $matches[2] )
				 I HAS attributes IZ Sanitizer::fixTagAttributes( $attributes , 'table' )

				 I HAS outLine IZ str_repeat( '<dl><dd>' , $indent_level ) . "<table{$attributes}>"
				 IM ON UR array_push DOING $td_history , false
				 IM ON UR array_push DOING $last_tag_history , ''
				 IM ON UR array_push DOING $tr_history , false
				 IM ON UR array_push DOING $tr_attributes , ''
				 IM ON UR array_push DOING $has_opened_tr , false
			 ORLY count( $td_history ) == 0
				 BTW Don't do any of the following
				 out HAS MOAR $outLine."\n";
				continue;
			 ORLY substr( $line , 0 , 2 ) === '|}'
				 BTW We are ending a table
				 I HAS line IZ '</table>' . substr( $line , 2 )
				 I HAS last_tag IZ array_pop( $last_tag_history )

				 IZ !array_pop( $has_opened_tr )
					 I HAS line IZ "<tr><td></td></tr>{$line}"
				 KTHX

				 IZ array_pop( $tr_history )
					 I HAS line IZ "</tr>{$line}"
				 KTHX

				 IZ array_pop( $td_history )
					 I HAS line IZ "</{$last_tag}>{$line}"
				 KTHX
				 IM ON UR array_pop DOING $tr_attributes
				 I HAS outLine IZ $line . str_repeat( '</dd></dl>' , $indent_level )
			 ORLY substr( $line , 0 , 2 ) === '|-'
				 BTW Now we have a table row
				 I HAS line IZ preg_replace( '#^\|-+#', '', $line )

				 BTW Whats after the tag is now only attributes
				 I HAS attributes IZ $this->mStripState->unstripBoth( $line )
				 I HAS attributes IZ Sanitizer::fixTagAttributes( $attributes, 'tr' )
				 IM ON UR array_pop DOING $tr_attributes
				 IM ON UR array_push DOING $tr_attributes, $attributes

				 I HAS line IZ ''
				 I HAS last_tag IZ array_pop( $last_tag_history )
				 IM ON UR array_pop DOING $has_opened_tr
				 IM ON UR array_push DOING $has_opened_tr , true

				 IZ array_pop( $tr_history )
					 I HAS line IZ '</tr>'
				 KTHX

				 IZ array_pop( $td_history )
					 I HAS line IZ "</{$last_tag}>{$line}"
				 KTHX

				 I HAS outLine IZ $line
				 IM ON UR array_push DOING $tr_history , false
				 IM ON UR array_push DOING $td_history , false
				 IM ON UR array_push DOING $last_tag_history , ''
			 ORLY $first_character === '|' || $first_character === '!' || substr( $line , 0 , 2 )  === '|+'
				 BTW This might be cell elements, td, th or captions
				 IZ substr( $line , 0 , 2 ) === '|+'
					 I HAS first_character IZ '+'
					 I HAS line IZ substr( $line , 1 )
				 KTHX

				 I HAS line IZ substr( $line , 1 )

				 IZ $first_character === '!'
					 I HAS line IZ str_replace( '!!' , '||' , $line )
				 KTHX

				 BTW Split up multiple cells on the same line.
				 BTW FIXME : This can result in improper nesting of tags processed
				 BTW by earlier parser steps, but should avoid splitting up eg
				 BTW attribute values containing literal "||".
				 I HAS cells IZ StringUtils::explodeMarkup( '||' , $line )

				 I HAS outLine IZ ''

				 BTW Loop through each table cell
				 IM IN UR cells ITZA cell
					 I HAS previous IZ ''
					 IZ $first_character !== '+'
						 I HAS tr_after IZ array_pop( $tr_attributes )
						 IZ !array_pop( $tr_history )
							 I HAS previous IZ "<tr{$tr_after}>\n"
						 KTHX
						 IM ON UR array_push DOING $tr_history , true
						 IM ON UR array_push DOING $tr_attributes , ''
						 IM ON UR array_pop DOING $has_opened_tr
						 IM ON UR array_push DOING $has_opened_tr , true
					 KTHX

					 I HAS last_tag IZ array_pop( $last_tag_history )

					 IZ array_pop( $td_history )
						 I HAS previous IZ "</{$last_tag}>{$previous}"
					 KTHX

					 IZ $first_character === '|'
						 I HAS last_tag IZ 'td'
					 ORLY $first_character === '!'
						 I HAS last_tag IZ 'th'
					 ORLY $first_character === '+'
						 I HAS last_tag IZ 'caption'
					 NOWAI
						 I HAS last_tag IZ ''
					 KTHX

					 IM ON UR array_push DOING $last_tag_history , $last_tag

					 BTW A cell could contain both parameters and data
					 I HAS cell_data IZ explode( '|' , $cell , 2 )

					 BTW Bug 553: Note that a '|' inside an invalid link should not
					 BTW be mistaken as delimiting cell parameters
					 IZ strpos( $cell_data[0], '[[' ) !== false
						 I HAS cell IZ "{$previous}<{$last_tag}>{$cell}"
					 ORLY count( $cell_data ) == 1
						 I HAS cell IZ "{$previous}<{$last_tag}>{$cell_data[0]}"
					 NOWAI
						 I HAS attributes IZ $this->mStripState->unstripBoth( $cell_data[0] )
						 I HAS attributes IZ Sanitizer::fixTagAttributes( $attributes , $last_tag )
						 I HAS cell IZ "{$previous}<{$last_tag}{$attributes}>{$cell_data[1]}"
					 KTHX

					 outLine HAS MOAR $cell;
					 IM ON UR array_push DOING $td_history , true
				 KTHX
			 KTHX
			 out HAS MOAR $outLine . "\n";
		 KTHX

		 BTW Closing open td, tr && table
		 STEALIN UR count( $td_history ) > 0
			 IZ array_pop( $td_history )
				 out HAS MOAR "</td>\n";
			 KTHX
			 IZ array_pop( $tr_history )
				 out HAS MOAR "</tr>\n";
			 KTHX
			 IZ !array_pop( $has_opened_tr )
				 out HAS MOAR "<tr><td></td></tr>\n" ;
			 KTHX

			 out HAS MOAR "</table>\n";
		 KTHX

		 BTW Remove trailing line-ending (b/c)
		 IZ substr( $out, -1 ) === "\n"
			 I HAS out IZ substr( $out, 0, -1 )
		 KTHX

		 BTW special case: don't return empty table
		 IZ $out === "<table>\n<tr><td></td></tr>\n</table>"
			 I HAS out IZ ''
		 KTHX

		 IM ON UR wfProfileOut DOING __METHOD__

		 I FOUND MAH $out
	 KTHX

	 DO NOT WANT
	  NOT WANT Helper function for parse() that transforms wiki markup into
	  NOT WANT HTML. Only called for $mOutputType == self::OT_HTML.
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE internalParse WITH UR $text, $isMain = true, $frame=false
		 IM ON UR wfProfileIn DOING __METHOD__

		 I HAS origText IZ $text

		 BTW Hook to suspend the parser in this state
		 IZ !wfRunHooks( 'ParserBeforeInternalParse', array( &$this, &$text, &$this->mStripState ) )
			 IM ON UR wfProfileOut DOING __METHOD__
			 I FOUND MAH $text 
		 KTHX

		 BTW if $frame is provided, then use $frame for replacing any variables
		 IZ $frame
			 BTW use frame depth to infer how include/noinclude tags should be handled
			 BTW depth=0 means this is the top-level document; otherwise it's an included document
			 IZ !$frame->depth
				 I HAS flag IZ 0
			 NOWAI
				 I HAS flag IZ Parser::PTD_FOR_INCLUSION
			 I HAS dom IZ $this->preprocessToDom( $text, $flag )
			 I HAS text IZ $frame->expand( $dom )
			 KTHX
		 NOWAI
			 BTW if $frame is not provided, then use old-style replaceVariables
			 I HAS text IZ $this->replaceVariables( $text )
		 KTHX

		 I HAS text IZ Sanitizer::removeHTMLtags( $text, array( &$this, 'attributeStripCallback' ), false, array_keys( $this->mTransparentTagHooks ) )
		 IM ON UR wfRunHooks DOING 'InternalParseBeforeLinks', array( &$this, &$text, &$this->mStripState )

		 BTW Tables need to come after variable replacement for things to work
		 BTW properly; putting them before other transformations should keep
		 BTW exciting things like link expansions from showing up in surprising
		 BTW places.
		 I HAS text IZ $this->doTableStuff( $text )

		 I HAS text IZ preg_replace( '/(^|\n)-----*/', '\\1<hr />', $text )

		 I HAS text IZ $this->doDoubleUnderscore( $text )

		 I HAS text IZ $this->doHeadings( $text )
		 IZ $this->mOptions->getUseDynamicDates()
			 I HAS df IZ DateFormatter::getInstance()
			 I HAS text IZ $df->reformat( $this->mOptions->getDateFormat(), $text )
		 KTHX
		 I HAS text IZ $this->doAllQuotes( $text )
		 I HAS text IZ $this->replaceInternalLinks( $text )
		 I HAS text IZ $this->replaceExternalLinks( $text )

		 BTW replaceInternalLinks may sometimes leave behind
		 BTW absolute URLs, which have to be masked to hide them from replaceExternalLinks
		 I HAS text IZ str_replace( $this->mUniqPrefix.'NOPARSE', '', $text )

		 I HAS text IZ $this->doMagicLinks( $text )
		 I HAS text IZ $this->formatHeadings( $text, $origText, $isMain )

		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Replace special strings like "ISBN xxx" and "RFC xxx" with
	  NOT WANT magic external links.
	  NOT WANT 
	  NOT WANT DML
	  NOT WANT @private
	  WANT
	 SO IM LIKE doMagicLinks WITH UR $text
		 IM ON UR wfProfileIn DOING __METHOD__
		 I HAS prots IZ $this->mUrlProtocols
		 I HAS urlChar IZ self::EXT_LINK_URL_CLASS
		 text IZ preg_replace_callback(
			'!(?:                           # Start cases
				(<a[ \t\r\n>].*?</a>) |     # m[1]: Skip link text
				(<.*?>) |                   # m[2]: Skip stuff inside HTML elements' . "
				(\\b(?:$prots)$urlChar+) |  # m[3]: Free external links" . '
				(?:RFC|PMID)\s+([0-9]+) |   # m[4]: RFC or PMID, capture number
				ISBN\s+(\b                  # m[5]: ISBN, capture number
				    (?: 97[89] [\ \-]? )?   # optional 13-digit ISBN prefix
				    (?: [0-9]  [\ \-]? ){9} # 9 digits with opt. delimiters
				    [0-9Xx]                 # check digit
				    \b)
			)!x', array( &$this, 'magicLinkCallback' ), $text );
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX

	 SO IM LIKE magicLinkCallback WITH UR $m
		 IZ isset( $m[1] ) && $m[1] !== ''
			 BTW Skip anchor
			 I FOUND MAH $m[0]
		 ORLY isset( $m[2] ) && $m[2] !== ''
			 BTW Skip HTML element
			 I FOUND MAH $m[0]
		 ORLY isset( $m[3] ) && $m[3] !== ''
			 BTW Free external link
			 I FOUND MAH $this->makeFreeExternalLink( $m[0] )
		 ORLY isset( $m[4] ) && $m[4] !== ''
			 BTW RFC or PMID
			 I HAS CssClass IZ ''
			 IZ substr( $m[0], 0, 3 ) === 'RFC'
				 I HAS keyword IZ 'RFC'
				 I HAS urlmsg IZ 'rfcurl'
				 I HAS CssClass IZ 'mw-magiclink-rfc'
				 I HAS id IZ $m[4]
			 ORLY substr( $m[0], 0, 4 ) === 'PMID'
				 I HAS keyword IZ 'PMID'
				 I HAS urlmsg IZ 'pubmedurl'
				 I HAS CssClass IZ 'mw-magiclink-pmid'
				 I HAS id IZ $m[4]
			 NOWAI
				throw new MWException( __METHOD__.': unrecognised match type "' .
					 IM ON UR substr DOING $m[0], 0, 20 ) . '"'
			 KTHX
			 I HAS url IZ wfMsg( $urlmsg, $id)
			 I HAS sk IZ $this->mOptions->getSkin()
			 I HAS la IZ $sk->getExternalLinkAttributes( "external $CssClass" )
			 I FOUND MAH "<a href=\"{$url}\"{$la}>{$keyword} {$id}</a>"
		 ORLY isset( $m[5] ) && $m[5] !== ''
			 BTW ISBN
			 I HAS isbn IZ $m[5]
			 num IZ strtr( $isbn, array(
				'-' => '',
				' ' => '',
				'x' => 'X',
			));
			 I HAS titleObj IZ SpecialPage::getTitleFor( 'Booksources', $num )
			return'<a href="' .
				$titleObj->escapeLocalUrl() .
				"\" class=\"internal mw-magiclink-isbn\">ISBN $isbn</a>";
		 NOWAI
			 I FOUND MAH $m[0]
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Make a free external link, given a user-supplied URL
	  NOT WANT @return HTML
	  NOT WANT @private
	  WANT
	 SO IM LIKE makeFreeExternalLink WITH UR $url
		 I HAS UR $wgContLang ON UR INTERNETS
		 IM ON UR wfProfileIn DOING __METHOD__

		 I HAS sk IZ $this->mOptions->getSkin()
		 I HAS trail IZ ''

		 BTW The characters '<' and '>' (which were escaped by
		 BTW removeHTMLtags()) should not be included in
		 BTW URLs, per RFC 2396.
		 I HAS m2 IZ EMPTY
		 IZ preg_match( '/&(lt|gt);/', $url, $m2, PREG_OFFSET_CAPTURE )
			 I HAS trail IZ substr( $url, $m2[0][1] ) . $trail
			 I HAS url IZ substr( $url, 0, $m2[0][1] )
		 KTHX

		 BTW Move trailing punctuation to $trail
		 I HAS sep IZ ',;\.:!?'
		 BTW If there is no left bracket, then consider right brackets fair game too
		 IZ strpos( $url, '(' ) === false
			 sep HAS MOAR ')';
		 KTHX

		 I HAS numSepChars IZ strspn( strrev( $url ), $sep )
		 IZ $numSepChars
			 I HAS trail IZ substr( $url, -$numSepChars ) . $trail
			 I HAS url IZ substr( $url, 0, -$numSepChars )
		 KTHX

		 I HAS url IZ Sanitizer::cleanUrl( $url )

		 BTW Is this an external image?
		 I HAS text IZ $this->maybeMakeExternalImage( $url )
		 IZ $text === false
			 BTW Not an image, make a link
			 text IZ $sk->makeExternalLink( $url, $wgContLang->markNoConversion($url), true, 'free',
				 IM ON UR SPECIAL getExternalLinkAttribs DOING $url )
			 BTW Register it in the output object...
			 BTW Replace unnecessary URL escape codes with their equivalent characters
			 I HAS pasteurized IZ self::replaceUnusualEscapes( $url )
			 IM ON UR SPECIAL mOutput->addExternalLink DOING $pasteurized
		 KTHX
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text . $trail
	 KTHX


	 DO NOT WANT
	  NOT WANT Parse headers and return html
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE doHeadings WITH UR $text
		 IM ON UR wfProfileIn DOING __METHOD__
		for ( $i = 6; $i >= 1; --$i ) {
			 I HAS h IZ str_repeat( '=', $i )
			 text IZ preg_replace( "/^$h(.+)$h\\s*$/m",
			  "<h$i>\\1</h$i>", $text );
		 KTHX
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Replace single quotes with HTML markup
	  NOT WANT @private
	  NOT WANT @return string the altered text
	  WANT
	 SO IM LIKE doAllQuotes WITH UR $text
		 IM ON UR wfProfileIn DOING __METHOD__
		 I HAS outtext IZ ''
		 I HAS lines IZ StringUtils::explode( "\n", $text )
		 IM IN UR lines ITZA line
			 outtext HAS MOAR $this->doQuotes( $line ) . "\n";
		 KTHX
		 I HAS outtext IZ substr( $outtext, 0,-1 )
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $outtext
	 KTHX

	 DO NOT WANT
	  NOT WANT Helper function for doAllQuotes()
	  WANT
	 SO IM LIKE doQuotes WITH UR $text
		 I HAS arr IZ preg_split( "/(''+)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE )
		 IZ count( $arr ) == 1
			 I FOUND MAH $text
		 NOWAI
			 BTW First, do some preliminary work. This may shift some apostrophes from
			 BTW being mark-up to being text. It also counts the number of occurrences
			 BTW of bold and italics mark-ups.
			 I HAS i IZ 0
			 I HAS numbold IZ 0
			 I HAS numitalics IZ 0
			 IM IN UR arr ITZA r
				 IZ ( $i % 2 ) == 1
					 BTW If there are ever four apostrophes, assume the first is supposed to
					 BTW be text, and the remaining three constitute mark-up for bold text.
					 IZ strlen( $arr[$i] ) == 4
						$arr[$i-1] .= "'";
						$arr[$i] = "'''";
					 ORLY strlen( $arr[$i] ) > 5
						 BTW If there are more than 5 apostrophes in a row, assume they're all
						 BTW text except for the last 5.
						$arr[$i-1] .= str_repeat( "'", strlen( $arr[$i] ) - 5 );
						$arr[$i] = "'''''";
					 KTHX
					 BTW Count the number of occurrences of bold and italics mark-ups.
					 BTW We are not counting sequences of five apostrophes.
					 IZ strlen( $arr[$i] ) == 2 
						$numitalics++;
					 ORLY strlen( $arr[$i] ) == 3 
						$numbold++;
					 ORLY strlen( $arr[$i] ) == 5 
						$numitalics++; 
						$numbold++;
					 KTHX
				 KTHX
				$i++;
			 KTHX

			 BTW If there is an odd number of both bold and italics, it is likely
			 BTW that one of the bold ones was meant to be an apostrophe followed
			 BTW by italics. Which one we cannot know for certain, but it is more
			 BTW likely to be one that has a single-letter word before it.
			 IZ ( $numbold % 2 == 1 ) && ( $numitalics % 2 == 1 )
				 I HAS i IZ 0
				 I HAS firstsingleletterword IZ -1
				 I HAS firstmultiletterword IZ -1
				 I HAS firstspace IZ -1
				 IM IN UR arr ITZA r
					 IZ ( $i % 2 == 1 ) and ( strlen( $r ) == 3 )
						 I HAS x1 IZ substr( $arr[$i-1], -1 )
						 I HAS x2 IZ substr( $arr[$i-1], -2, 1 )
						 IZ $x1 === ' '
							 IZ $firstspace == -1
								 I HAS firstspace IZ $i
							 KTHX
						 KTHX elseif ( $x2 === ' ') {
							 IZ $firstsingleletterword == -1 
								 I HAS firstsingleletterword IZ $i
							 KTHX
						 NOWAI
							 IZ $firstmultiletterword == -1
								 I HAS firstmultiletterword IZ $i
							 KTHX
						 KTHX
					 KTHX
					$i++;
				 KTHX

				 BTW If there is a single-letter word, use it!
				 IZ $firstsingleletterword > -1
					$arr[$firstsingleletterword] = "''";
					$arr[$firstsingleletterword-1] .= "'";
				 ORLY $firstmultiletterword > -1
					 BTW If not, but there's a multi-letter word, use that one.
					$arr[$firstmultiletterword] = "''";
					$arr[$firstmultiletterword-1] .= "'";
				 ORLY $firstspace > -1
					 BTW ... otherwise use the first one that has neither.
					 BTW (notice that it is possible for all three to be -1 if, for example,
					 BTW there is only one pentuple-apostrophe in the line)
					$arr[$firstspace] = "''";
					$arr[$firstspace-1] .= "'";
				 KTHX
			 KTHX

			 BTW Now let's actually convert our apostrophic mush to HTML!
			 I HAS output IZ ''
			 I HAS buffer IZ ''
			 I HAS state IZ ''
			 I HAS i IZ 0
			 IM IN UR arr ITZA r
				 IZ ( $i % 2 ) == 0
					 IZ $state === 'both'
						 buffer HAS MOAR $r;
					 NOWAI
						 output HAS MOAR $r;
					 KTHX
				 NOWAI
					 IZ strlen( $r ) == 2
						 IZ $state === 'i'
							 output HAS MOAR '</i>'; $state = '';
						 ORLY $state === 'bi'
							 output HAS MOAR '</i>'; $state = 'b';
						 ORLY $state === 'ib'
							 output HAS MOAR '</b></i><b>'; $state = 'b';
						 ORLY $state === 'both'
							 output HAS MOAR '<b><i>'.$buffer.'</i>'; $state = 'b';
						 NOWAI # $state can be 'b' or ''
							 output HAS MOAR '<i>'; $state .= 'i';
						 KTHX
					 ORLY strlen( $r ) == 3
						 IZ $state === 'b'
							 output HAS MOAR '</b>'; $state = '';
						 ORLY $state === 'bi'
							 output HAS MOAR '</i></b><i>'; $state = 'i';
						 ORLY $state === 'ib'
							 output HAS MOAR '</b>'; $state = 'i';
						 ORLY $state === 'both'
							 output HAS MOAR '<i><b>'.$buffer.'</b>'; $state = 'i';
						 NOWAI # $state can be 'i' or ''
							 output HAS MOAR '<b>'; $state .= 'b';
						 KTHX
					 ORLY strlen( $r ) == 5
						 IZ $state === 'b'
							 output HAS MOAR '</b><i>'; $state = 'i';
						 ORLY $state === 'i'
							 output HAS MOAR '</i><b>'; $state = 'b';
						 ORLY $state === 'bi'
							 output HAS MOAR '</i></b>'; $state = '';
						 ORLY $state === 'ib'
							 output HAS MOAR '</b></i>'; $state = '';
						 ORLY $state === 'both'
							 output HAS MOAR '<i><b>'.$buffer.'</b></i>'; $state = '';
						 NOWAI # ($state == '')
							 I HAS buffer IZ ''; $state = 'both'
						 KTHX
					 KTHX
				 KTHX
				$i++;
			 KTHX
			 BTW Now close all remaining tags.  Notice that the order is important.
			 IZ $state === 'b' || $state === 'ib'
				 output HAS MOAR '</b>';
			 KTHX
			 IZ $state === 'i' || $state === 'bi' || $state === 'ib'
				 output HAS MOAR '</i>';
			 KTHX
			 IZ $state === 'bi'
				 output HAS MOAR '</b>';
			 KTHX
			 BTW There might be lonely ''''', so make sure we have a buffer
			 IZ $state === 'both' && $buffer
				 output HAS MOAR '<b><i>'.$buffer.'</i></b>';
			 KTHX
			 I FOUND MAH $output
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Replace external links (REL)
	  NOT WANT 
 	  NOT WANT Note: this is all very hackish and the order of execution matters a lot.
	  NOT WANT Make sure to run maintenance/parserTests.php if you change this code.
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE replaceExternalLinks WITH UR $text
		 I HAS UR $wgContLang ON UR INTERNETS
		 IM ON UR wfProfileIn DOING __METHOD__

		 I HAS sk IZ $this->mOptions->getSkin()

		 I HAS bits IZ preg_split( $this->mExtLinkBracketedRegex, $text, -1, PREG_SPLIT_DELIM_CAPTURE )
		 I HAS s IZ array_shift( $bits )

		 I HAS i IZ 0
		 STEALIN UR $i<count( $bits )
			 I HAS url IZ $bits[$i++]
			 I HAS protocol IZ $bits[$i++]
			 I HAS text IZ $bits[$i++]
			 I HAS trail IZ $bits[$i++]

			 BTW The characters '<' and '>' (which were escaped by
			 BTW removeHTMLtags()) should not be included in
			 BTW URLs, per RFC 2396.
			 I HAS m2 IZ EMPTY
			 IZ preg_match( '/&(lt|gt);/', $url, $m2, PREG_OFFSET_CAPTURE )
				 I HAS text IZ substr( $url, $m2[0][1] ) . ' ' . $text
				 I HAS url IZ substr( $url, 0, $m2[0][1] )
			 KTHX

			 BTW If the link text is an image URL, replace it with an <img> tag
			 BTW This happened by accident in the original parser, but some people used it extensively
			 I HAS img IZ $this->maybeMakeExternalImage( $text )
			 IZ $img !== false
				 I HAS text IZ $img
			 KTHX

			 I HAS dtrail IZ ''

			 BTW Set linktype for CSS - if URL==text, link is essentially free
			 I HAS linktype IZ ( $text === $url ) ? 'free' : 'text'

			 BTW No link text, e.g. [http://domain.tld/some.link]
			 IZ $text == ''
				 BTW Autonumber if allowed. See bug #5918
				 IZ strpos( wfUrlProtocols(), substr( $protocol, 0, strpos( $protocol, ':' ) ) ) !== false
					 I HAS langObj IZ $this->getFunctionLang()
					 I HAS text IZ '[' . $langObj->formatNum( ++$this->mAutonumber ) . ']'
					 I HAS linktype IZ 'autonumber'
				 NOWAI
					 BTW Otherwise just use the URL
					 I HAS text IZ htmlspecialchars( $url )
					 I HAS linktype IZ 'free'
				 KTHX
			 NOWAI
				 BTW Have link text, e.g. [http://domain.tld/some.link text]s
				 BTW Check for trail
				 IM ON UR list DOING $dtrail, $trail ) = Linker::splitTrail( $trail
			 KTHX

			 I HAS text IZ $wgContLang->markNoConversion( $text )

			 I HAS url IZ Sanitizer::cleanUrl( $url )

			 BTW Use the encoded URL
			 BTW This means that users can paste URLs directly into the text
			 BTW Funny characters like &ouml; aren't valid in URLs anyway
			 BTW This was changed in August 2004
			 s HAS MOAR $sk->makeExternalLink( $url, $text, false, $linktype,
				$this->getExternalLinkAttribs( $url ) ) . $dtrail . $trail;

			 BTW Register link in the output object.
			 BTW Replace unnecessary URL escape codes with the referenced character
			 BTW This prevents spammers from hiding links from the filters
			 I HAS pasteurized IZ self::replaceUnusualEscapes( $url )
			 IM ON UR SPECIAL mOutput->addExternalLink DOING $pasteurized
		 KTHX

		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $s
	 KTHX

	 DO NOT WANT
	  NOT WANT Get an associative array of additional HTML attributes appropriate for a
	  NOT WANT particular external link.  This currently may include rel => nofollow
	  NOT WANT (depending on configuration, namespace, and the URL's domain) and/or a
	  NOT WANT target attribute (depending on configuration).
	  NOT WANT 
	  NOT WANT @param string $url Optional URL, to extract the domain from for rel =>
	  NOT WANT   nofollow if appropriate
	  NOT WANT @return array Associative array of HTML attributes
	  WANT
	 SO IM LIKE getExternalLinkAttribs WITH UR $url = false
		 I HAS attribs IZ EMPTY
		 I HAS UR $wgNoFollowLinks, $wgNoFollowNsExceptions ON UR INTERNETS
		 I HAS ns IZ $this->mTitle->getNamespace()
		 IZ $wgNoFollowLinks && !in_array( $ns, $wgNoFollowNsExceptions )
			$attribs['rel'] = 'nofollow';

			 I HAS UR $wgNoFollowDomainExceptions ON UR INTERNETS
			 IZ $wgNoFollowDomainExceptions
				 I HAS bits IZ wfParseUrl( $url )
				 IZ is_array( $bits ) && isset( $bits['host'] )
					 IM IN UR wgNoFollowDomainExceptions ITZA domain
						 IZ substr( $bits['host'], -strlen( $domain ) ) == $domain
							 IM ON UR unset DOING $attribs['rel']
							break;
						 KTHX
					 KTHX
				 KTHX
			 KTHX
		 KTHX
		 IZ $this->mOptions->getExternalLinkTarget()
			$attribs['target'] = $this->mOptions->getExternalLinkTarget();
		 KTHX
		 I FOUND MAH $attribs
	 KTHX


	 DO NOT WANT
	  NOT WANT Replace unusual URL escape codes with their equivalent characters
	  NOT WANT @param string
	  NOT WANT @return string
	  NOT WANT @static
	  NOT WANT @todo  This can merge genuinely required bits in the path or query string,
	  NOT WANT        breaking legit URLs. A proper fix would treat the various parts of
	  NOT WANT        the URL differently; as a workaround, just use the output for
	  NOT WANT        statistical records, not for actual linking/output.
	  WANT
	 SO IM ALWAYS LIKE replaceUnusualEscapes WITH UR $url
		return preg_replace_callback( '/%[0-9A-Fa-f]{2}/',
			 IM ON UR array DOING __CLASS__, 'replaceUnusualEscapesCallback' ), $url
	 KTHX

	 DO NOT WANT
	  NOT WANT Callback function used in replaceUnusualEscapes().
	  NOT WANT Replaces unusual URL escape codes with their equivalent character
	  NOT WANT @static
	  NOT WANT @private
	  WANT
	 SO IM ALWAYS LIKE replaceUnusualEscapesCallback WITH UR $matches
		 I HAS char IZ urldecode( $matches[0] )
		 I HAS ord IZ ord( $char )
		 BTW Is it an unsafe or HTTP reserved character according to RFC 1738?
		 IZ $ord > 32 && $ord < 127 && strpos( '<>"#{}|\^~[]`;/?', $char ) === false
			 BTW No, shouldn't be escaped
			 I FOUND MAH $char
		 NOWAI
			 BTW Yes, leave it escaped
			 I FOUND MAH $matches[0]
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT make an image if it's allowed, either through the global
	  NOT WANT option, through the exception, or through the on-wiki whitelist
	  NOT WANT @private
	  WANT
	 SO IM LIKE maybeMakeExternalImage WITH UR $url
		 I HAS sk IZ $this->mOptions->getSkin()
		 I HAS imagesfrom IZ $this->mOptions->getAllowExternalImagesFrom()
		 I HAS imagesexception IZ !empty( $imagesfrom )
		 I HAS text IZ false
		 BTW $imagesfrom could be either a single string or an array of strings, parse out the latter
		 IZ $imagesexception && is_array( $imagesfrom )
			 I HAS imagematch IZ false
			 IM IN UR imagesfrom ITZA match
				 IZ strpos( $url, $match ) === 0
					 I HAS imagematch IZ true
					break;
				 KTHX
			 KTHX
		 ORLY $imagesexception
			 I HAS imagematch IZ ( strpos( $url, $imagesfrom ) === 0 )
		 NOWAI
			 I HAS imagematch IZ false
		 KTHX
		if ( $this->mOptions->getAllowExternalImages()
		     || ( $imagesexception && $imagematch ) ) {
			 IZ preg_match( self::EXT_IMAGE_REGEX, $url )
				 BTW Image found
				 I HAS text IZ $sk->makeExternalImage( $url )
			 KTHX
		 KTHX
		if ( !$text && $this->mOptions->getEnableImageWhitelist()
			 && preg_match( self::EXT_IMAGE_REGEX, $url ) ) {
			 I HAS whitelist IZ explode( "\n", wfMsgForContent( 'external_image_whitelist' ) )
			 IM IN UR whitelist ITZA entry
				 BTW Sanitize the regex fragment, make it case-insensitive, ignore blank entries/comments
				 IZ strpos( $entry, '#' ) === 0 || $entry === ''
					continue;
				 KTHX
				 IZ preg_match( '/' . str_replace( '/', '\\/', $entry ) . '/i', $url )
					 BTW Image matches a whitelist entry
					 I HAS text IZ $sk->makeExternalImage( $url )
					break;
				 KTHX
			 KTHX
		 KTHX
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Process [[ ]] wikilinks
	  NOT WANT @return processed text
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE replaceInternalLinks WITH UR $s
		 IM ON UR SPECIAL mLinkHolders->merge DOING $this->replaceInternalLinks2( $s )
		 I FOUND MAH $s
	 KTHX

	 DO NOT WANT
	  NOT WANT Process [[ ]] wikilinks (RIL)
	  NOT WANT @return LinkHolderArray
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE replaceInternalLinks2 WITH UR &$s
		 I HAS UR $wgContLang ON UR INTERNETS

		 IM ON UR wfProfileIn DOING __METHOD__

		 IM ON UR wfProfileIn DOING __METHOD__.'-setup'
		static $tc = FALSE, $e1, $e1_img;
		 BTW the % is needed to support urlencoded titles as well
		 IZ !$tc
			 I HAS tc IZ Title::legalChars() . '#%'
			 BTW Match a link having the form [[namespace:link|alternate]]trail
			 I HAS e1 IZ "/^([{$tc}]+)(?:\\|(.+?))?]](.*)\$/sD"
			 BTW Match cases where there is no "]]", which might still be images
			 I HAS e1_img IZ "/^([{$tc}]+)\\|(.*)\$/sD"
		 KTHX

		 I HAS sk IZ $this->mOptions->getSkin()
		 I HAS holders IZ new LinkHolderArray( $this )

	 	 BTW split the entire text string on occurences of [[
		 I HAS a IZ StringUtils::explode( '[[', ' ' . $s )
		 BTW get the first element (all text up to first [[), and remove the space we added
		 I HAS s IZ $a->current()
		$a->next();
		 I HAS line IZ $a->current() # Workaround for broken ArrayIterator::next() that returns "void"
		 I HAS s IZ substr( $s, 1 )

		 I HAS useLinkPrefixExtension IZ $wgContLang->linkPrefixExtension()
		 I HAS e2
		 IZ $useLinkPrefixExtension
			 BTW Match the end of a line for a word that's not followed by whitespace,
			 BTW e.g. in the case of 'The Arab al[[Razi]]', 'al' will be matched
			 I HAS e2 IZ wfMsgForContent( 'linkprefix' )
		 KTHX

		 IZ is_null( $this->mTitle )
			 IM ON UR wfProfileOut DOING __METHOD__.'-setup'
			 IM ON UR wfProfileOut DOING __METHOD__
			throw new MWException( __METHOD__.": \$this->mTitle is null\n" );
		 KTHX
		 I HAS nottalk IZ !$this->mTitle->isTalkPage()

		 IZ $useLinkPrefixExtension
			 I HAS m IZ EMPTY
			 IZ preg_match( $e2, $s, $m )
				 I HAS first_prefix IZ $m[2]
			 NOWAI
				 I HAS first_prefix IZ false
			 KTHX
		 NOWAI
			 I HAS prefix IZ ''
		 KTHX

		 IZ $wgContLang->hasVariants()
			 I HAS selflink IZ $wgContLang->convertLinkToAllVariants( $this->mTitle->getPrefixedText() )
		 NOWAI
			 I HAS selflink IZ BUCKET $this->mTitle->getPrefixedText() );
		 KTHX
		 I HAS useSubpages IZ $this->areSubpagesAllowed()
		 IM ON UR wfProfileOut DOING __METHOD__.'-setup'

		 BTW Loop for each link
		for ( ; $line !== false && $line !== null ; $a->next(), $line = $a->current() ) {
			 BTW Check for excessive memory usage
			 IZ $holders->isBig()
				 BTW Too big
				 BTW Do the existence check, replace the link holders and clear the array
				$holders->replace( $s );
				$holders->clear();
			 KTHX

			 IZ $useLinkPrefixExtension
				 IM ON UR wfProfileIn DOING __METHOD__.'-prefixhandling'
				 IZ preg_match( $e2, $s, $m )
					 I HAS prefix IZ $m[2]
					 I HAS s IZ $m[1]
				 NOWAI
					$prefix='';
				 KTHX
				 BTW first link
				 IZ $first_prefix
					 I HAS prefix IZ $first_prefix
					 I HAS first_prefix IZ false
				 KTHX
				 IM ON UR wfProfileOut DOING __METHOD__.'-prefixhandling'
			 KTHX

			 I HAS might_be_img IZ false

			 IM ON UR wfProfileIn DOING __METHOD__."-e1"
			 IZ preg_match( $e1, $line, $m ) # page with normal text or alt
				 I HAS text IZ $m[2]
				 BTW If we get a ] at the beginning of $m[3] that means we have a link that's something like:
				 BTW [[Image:Foo.jpg|[http://example.com desc]]] <- having three ] in a row fucks up,
				 BTW the real problem is with the $e1 regex
				 BTW See bug 1300.
				 BTW 
				 BTW Still some problems for cases where the ] is meant to be outside punctuation,
				 BTW and no image is in sight. See bug 2095.
				 BTW 
				if ( $text !== '' &&
					substr( $m[3], 0, 1 ) === ']' &&
					strpos( $text, '[' ) !== false
				)
				{
					 text HAS MOAR ']'; # so that replaceExternalLinks($text) works later
					$m[3] = substr( $m[3], 1 );
				 KTHX
				 BTW fix up urlencoded title texts
				 IZ strpos( $m[1], '%' ) !== false
					 BTW Should anchors '#' also be rejected?
					$m[1] = str_replace( array('<', '>'), array('&lt;', '&gt;'), urldecode( $m[1] ) );
				 KTHX
				 I HAS trail IZ $m[3]
			 ORLY preg_match( $e1_img, $line, $m ) # Invalid, but might be an image with a link in its caption
				 I HAS might_be_img IZ true
				 I HAS text IZ $m[2]
				 IZ strpos( $m[1], '%' ) !== false
					$m[1] = urldecode( $m[1] );
				 KTHX
				 I HAS trail IZ ""
			 NOWAI # Invalid form; output directly
				 s HAS MOAR $prefix . '[[' . $line ;
				 IM ON UR wfProfileOut DOING __METHOD__."-e1"
				continue;
			 KTHX
			 IM ON UR wfProfileOut DOING __METHOD__."-e1"
			 IM ON UR wfProfileIn DOING __METHOD__."-misc"

			 BTW Don't allow internal links to pages containing
			 BTW PROTO: where PROTO is a valid URL protocol; these
			 BTW should be external links.
			 IZ preg_match( '/^\b(?:' . wfUrlProtocols() . ')/', $m[1] )
				 s HAS MOAR $prefix . '[[' . $line ;
				 IM ON UR wfProfileOut DOING __METHOD__."-misc"
				continue;
			 KTHX

			 BTW Make subpage if necessary
			 IZ $useSubpages
				 I HAS link IZ $this->maybeDoSubpageLink( $m[1], $text )
			 NOWAI
				 I HAS link IZ $m[1]
			 KTHX

			 I HAS noforce IZ ( substr( $m[1], 0, 1 ) !== ':' )
			 IZ !$noforce
				 BTW Strip off leading ':'
				 I HAS link IZ substr( $link, 1 )
			 KTHX

			 IM ON UR wfProfileOut DOING __METHOD__."-misc"
			 IM ON UR wfProfileIn DOING __METHOD__."-title"
			 I HAS nt IZ Title::newFromText( $this->mStripState->unstripNoWiki( $link ) )
			 IZ $nt === null
				 s HAS MOAR $prefix . '[[' . $line;
				 IM ON UR wfProfileOut DOING __METHOD__."-title"
				continue;
			 KTHX

			 I HAS ns IZ $nt->getNamespace()
			 I HAS iw IZ $nt->getInterWiki()
			 IM ON UR wfProfileOut DOING __METHOD__."-title"

			 IZ $might_be_img # if this is actually an invalid link
				 IM ON UR wfProfileIn DOING __METHOD__."-might_be_img"
				 IZ $ns == NS_FILE && $noforce # but might be an image
					 I HAS found IZ false
					 STEALIN UR true
						 BTW look at the next 'line' to see if we can close it there
						$a->next();
						 I HAS next_line IZ $a->current()
						 IZ $next_line === false || $next_line === null
							break;
						 KTHX
						 I HAS m IZ explode( ']]', $next_line, 3 )
						 IZ count( $m ) == 3
							 BTW the first ]] closes the inner link, the second the image
							 I HAS found IZ true
							 text HAS MOAR "[[{$m[0]}]]{$m[1]}";
							 I HAS trail IZ $m[2]
							break;
						 ORLY count( $m ) == 2
							 BTW if there's exactly one ]] that's fine, we'll keep looking
							 text HAS MOAR "[[{$m[0]}]]{$m[1]}";
						 NOWAI
							 BTW if $next_line is invalid too, we need look no further
							 text HAS MOAR '[[' . $next_line;
							break;
						 KTHX
					 KTHX
					 IZ !$found
						 BTW we couldn't find the end of this imageLink, so output it raw
						 BTW but don't ignore what might be perfectly normal links in the text we've examined
						$holders->merge( $this->replaceInternalLinks2( $text ) );
						 s HAS MOAR "{$prefix}[[$link|$text";
						 BTW note: no $trail, because without an end, there *is* no trail
						 IM ON UR wfProfileOut DOING __METHOD__."-might_be_img"
						continue;
					 KTHX
				 NOWAI # it's not an image, so output it raw
					 s HAS MOAR "{$prefix}[[$link|$text";
					 BTW note: no $trail, because without an end, there *is* no trail
					 IM ON UR wfProfileOut DOING __METHOD__."-might_be_img"
					continue;
				 KTHX
				 IM ON UR wfProfileOut DOING __METHOD__."-might_be_img"
			 KTHX

			 I HAS wasblank IZ ( $text  == '' )
			 IZ $wasblank
				 I HAS text IZ $link
			 KTHX

			 BTW Link not escaped by : , create the various objects
			 IZ $noforce

				 BTW Interwikis
				 IM ON UR wfProfileIn DOING __METHOD__."-interwiki"
				 IZ $iw && $this->mOptions->getInterwikiMagic() && $nottalk && $wgContLang->getLanguageName( $iw )
					 IM ON UR SPECIAL mOutput->addLanguageLink DOING $nt->getFullText()
					 I HAS s IZ rtrim( $s . $prefix )
					 s HAS MOAR trim( $trail, "\n" ) == '' ? '': $prefix . $trail;
					 IM ON UR wfProfileOut DOING __METHOD__."-interwiki"
					continue;
				 KTHX
				 IM ON UR wfProfileOut DOING __METHOD__."-interwiki"

				 IZ $ns == NS_FILE
					 IM ON UR wfProfileIn DOING __METHOD__."-image"
					 IZ !wfIsBadImage( $nt->getDBkey(), $this->mTitle )
						 IZ $wasblank
							 BTW if no parameters were passed, $text
							 BTW becomes something like "File:Foo.png",
							 BTW which we don't want to pass on to the
							 BTW image generator
							 I HAS text IZ ''
						 NOWAI
							 BTW recursively parse links inside the image caption
							 BTW actually, this will parse them in any other parameters, too,
							 BTW but it might be hard to fix that, and it doesn't matter ATM
							 I HAS text IZ $this->replaceExternalLinks( $text )
							$holders->merge( $this->replaceInternalLinks2( $text ) );
						 KTHX
						 BTW cloak any absolute URLs inside the image markup, so replaceExternalLinks() won't touch them
						 s HAS MOAR $prefix . $this->armorLinks( $this->makeImage( $nt, $text, $holders ) ) . $trail;
					 NOWAI
						 s HAS MOAR $prefix . $trail;
					 KTHX
					 IM ON UR SPECIAL mOutput->addImage DOING $nt->getDBkey()
					 IM ON UR wfProfileOut DOING __METHOD__."-image"
					continue;

				 KTHX

				 IZ $ns == NS_CATEGORY
					 IM ON UR wfProfileIn DOING __METHOD__."-category"
					 I HAS s IZ rtrim( $s . "\n" ) # bug 87

					 IZ $wasblank
						 I HAS sortkey IZ $this->getDefaultSort()
					 NOWAI
						 I HAS sortkey IZ $text
					 KTHX
					 I HAS sortkey IZ Sanitizer::decodeCharReferences( $sortkey )
					 I HAS sortkey IZ str_replace( "\n", '', $sortkey )
					 I HAS sortkey IZ $wgContLang->convertCategoryKey( $sortkey )
					 IM ON UR SPECIAL mOutput->addCategory DOING $nt->getDBkey(), $sortkey

					 DO NOT WANT
					  NOT WANT Strip the whitespace Category links produce, see bug 87
					  NOT WANT @todo We might want to use trim($tmp, "\n") here.
					  WANT
					 s HAS MOAR trim( $prefix . $trail, "\n" ) == '' ? '': $prefix . $trail;

					 IM ON UR wfProfileOut DOING __METHOD__."-category"
					continue;
				 KTHX
			 KTHX

			 BTW Self-link checking
			 IZ $nt->getFragment() === '' && $ns != NS_SPECIAL
				 IZ in_array( $nt->getPrefixedText(), $selflink, true )
					 s HAS MOAR $prefix . $sk->makeSelfLinkObj( $nt, $text, '', $trail );
					continue;
				 KTHX
			 KTHX

			 BTW NS_MEDIA is a pseudo-namespace for linking directly to a file
			 BTW FIXME: Should do batch file existence checks, see comment below
			 IZ $ns == NS_MEDIA
				 IM ON UR wfProfileIn DOING __METHOD__."-media"
				 BTW Give extensions a chance to select the file revision for us
				 I HAS skip IZ $time = false
				 IM ON UR wfRunHooks DOING 'BeforeParserMakeImageLinkObj', array( &$this, &$nt, &$skip, &$time )
				 IZ $skip
					 I HAS link IZ $sk->link( $nt )
				 NOWAI
					 I HAS link IZ $sk->makeMediaLinkObj( $nt, $text, $time )
				 KTHX
				 BTW Cloak with NOPARSE to avoid replacement in replaceExternalLinks
				 s HAS MOAR $prefix . $this->armorLinks( $link ) . $trail;
				 IM ON UR SPECIAL mOutput->addImage DOING $nt->getDBkey()
				 IM ON UR wfProfileOut DOING __METHOD__."-media"
				continue;
			 KTHX

			 IM ON UR wfProfileIn DOING __METHOD__."-always_known"
			 BTW Some titles, such as valid special pages or files in foreign repos, should
			 BTW be shown as bluelinks even though they're not included in the page table
			 BTW 
			 BTW FIXME: isAlwaysKnown() can be expensive for file links; we should really do
			 BTW batch file existence checks for NS_FILE and NS_MEDIA
			 IZ $iw == '' && $nt->isAlwaysKnown()
				 IM ON UR SPECIAL mOutput->addLink DOING $nt
				 s HAS MOAR $this->makeKnownLinkHolder( $nt, $text, '', $trail, $prefix );
			 NOWAI
				 BTW Links will be added to the output link list after checking
				 s HAS MOAR $holders->makeHolder( $nt, $text, '', $trail, $prefix );
			 KTHX
			 IM ON UR wfProfileOut DOING __METHOD__."-always_known"
		 KTHX
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $holders
	 KTHX

	 DO NOT WANT
	  NOT WANT Make a link placeholder. The text returned can be later resolved to a real link with
	  NOT WANT replaceLinkHolders(). This is done for two reasons: firstly to avoid further
	  NOT WANT parsing of interwiki links, and secondly to allow all existence checks and
	  NOT WANT article length checks (for stub links) to be bundled into a single query.
	  NOT WANT 
	  NOT WANT @deprecated
	  WANT
	 SO IM LIKE makeLinkHolder WITH UR &$nt, $text = '', $query = '', $trail = '', $prefix = ''
		 I FOUND MAH $this->mLinkHolders->makeHolder( $nt, $text, $query, $trail, $prefix )
	 KTHX

	 DO NOT WANT
	  NOT WANT Render a forced-blue link inline; protect against double expansion of
	  NOT WANT URLs if we're in a mode that prepends full URL prefixes to internal links.
	  NOT WANT Since this little disaster has to split off the trail text to avoid
	  NOT WANT breaking URLs in the following text without breaking trails on the
	  NOT WANT wiki links, it's been made into a horrible function.
	  NOT WANT 
	  NOT WANT @param Title $nt
	  NOT WANT @param string $text
	  NOT WANT @param string $query
	  NOT WANT @param string $trail
	  NOT WANT @param string $prefix
	  NOT WANT @return string HTML-wikitext mix oh yuck
	  WANT
	 SO IM LIKE makeKnownLinkHolder WITH UR $nt, $text = '', $query = '', $trail = '', $prefix = ''
		 IM ON UR list DOING $inside, $trail ) = Linker::splitTrail( $trail
		 I HAS sk IZ $this->mOptions->getSkin()
		 BTW FIXME: use link() instead of deprecated makeKnownLinkObj()
		 I HAS link IZ $sk->makeKnownLinkObj( $nt, $text, $query, $inside, $prefix )
		 I FOUND MAH $this->armorLinks( $link ) . $trail
	 KTHX

	 DO NOT WANT
	  NOT WANT Insert a NOPARSE hacky thing into any inline links in a chunk that's
	  NOT WANT going to go through further parsing steps before inline URL expansion.
	  NOT WANT 
	  NOT WANT Not needed quite as much as it used to be since free links are a bit
	  NOT WANT more sensible these days. But bracketed links are still an issue.
	  NOT WANT 
	  NOT WANT @param string more-or-less HTML
	  NOT WANT @return string less-or-more HTML with NOPARSE bits
	  WANT
	 SO IM LIKE armorLinks WITH UR $text
		return preg_replace( '/\b(' . wfUrlProtocols() . ')/',
			"{$this->mUniqPrefix}NOPARSE$1", $text );
	 KTHX

	 DO NOT WANT
	  NOT WANT Return true if subpage links should be expanded on this page.
	  NOT WANT @return bool
	  WANT
	 SO IM LIKE areSubpagesAllowed
		 BTW Some namespaces don't allow subpages
		 I FOUND MAH MWNamespace::hasSubpages( $this->mTitle->getNamespace() )
	 KTHX

	 DO NOT WANT
	  NOT WANT Handle link to subpage if necessary
	  NOT WANT @param string $target the source of the link
	  NOT WANT @param string &$text the link text, modified as necessary
	  NOT WANT @return string the full name of the link
	  NOT WANT @private
	  WANT
	 SO IM LIKE maybeDoSubpageLink WITH UR $target, &$text
		 I FOUND MAH Linker::normalizeSubpageLink( $this->mTitle, $target, $text )
	 KTHX

	 DO NOT WANT#@+
	  NOT WANT Used by doBlockLevels()
	  NOT WANT @private
	  WANT
	 SO IM LIKE closeParagraph
		 I HAS result IZ ''
		 IZ $this->mLastSection != ''
			 I HAS result IZ '</' . $this->mLastSection  . ">\n"
		 KTHX
		 UR SPECIAL mInPre IZ false;
		 UR SPECIAL mLastSection IZ '';
		 I FOUND MAH $result
	 KTHX
	 DO NOT WANT
	  NOT WANT getCommon() returns the length of the longest common substring
	  NOT WANT of both arguments, starting at the beginning of both.
	  NOT WANT @private 
	  WANT 
	 SO IM LIKE getCommon WITH UR $st1, $st2
		 I HAS fl IZ strlen( $st1 )
		 I HAS shorter IZ strlen( $st2 )
		 IZ $fl < $shorter 
			 I HAS shorter IZ $fl 
		 KTHX

		for ( $i = 0; $i < $shorter; ++$i ) {
			 IZ $st1{$i} != $st2{$i} 
				break; 
			 KTHX
		 KTHX
		 I FOUND MAH $i
	 KTHX
	 DO NOT WANT
	  NOT WANT These next three functions open, continue, and close the list
	  NOT WANT element appropriate to the prefix character passed into them.
	  NOT WANT @private 
	  WANT
	 SO IM LIKE openList WITH UR $char
		 I HAS result IZ $this->closeParagraph()

		 IZ '*' === $char 
			 result HAS MOAR '<ul><li>'; 
		 ORLY '#' === $char 
			 result HAS MOAR '<ol><li>'; 
		 ORLY ':' === $char 
			 result HAS MOAR '<dl><dd>'; 
		 ORLY ';' === $char
			 result HAS MOAR '<dl><dt>';
			 UR SPECIAL mDTopen IZ true;
		 NOWAI 
			 I HAS result IZ '<!-- ERR 1 -->' 
		 KTHX

		 I FOUND MAH $result
	 KTHX

	 DO NOT WANT
	  NOT WANT TODO: document
	  NOT WANT @param $char String
	  NOT WANT @private
	  WANT
	 SO IM LIKE nextItem WITH UR $char
		 IZ '*' === $char || '#' === $char 
			 I FOUND MAH '</li><li>' 
		 ORLY ':' === $char || ';' === $char
			 I HAS close IZ '</dd>'
			 IZ $this->mDTopen 
				 I HAS close IZ '</dt>' 
			 KTHX
			 IZ ';' === $char
				 UR SPECIAL mDTopen IZ true;
				 I FOUND MAH $close . '<dt>'
			 NOWAI
				 UR SPECIAL mDTopen IZ false;
				 I FOUND MAH $close . '<dd>'
			 KTHX
		 KTHX
		 I FOUND MAH '<!-- ERR 2 -->'
	 KTHX

	 DO NOT WANT
	  NOT WANT TODO: document
	  NOT WANT @param $char String
	  NOT WANT @private
	  WANT
	 SO IM LIKE closeList WITH UR $char
		 IZ '*' === $char 
			 I HAS text IZ '</li></ul>' 
		 ORLY '#' === $char 
			 I HAS text IZ '</li></ol>' 
		 ORLY ':' === $char
			 IZ $this->mDTopen
				 UR SPECIAL mDTopen IZ false;
				 I HAS text IZ '</dt></dl>'
			 NOWAI
				 I HAS text IZ '</dd></dl>'
			 KTHX
		 NOWAI	
			 I FOUND MAH '<!-- ERR 3 -->' 
		 KTHX
		 I FOUND MAH $text."\n"
	 KTHX
	 DO NOT WANT#@-*/

	 DO NOT WANT
	  NOT WANT Make lists from lines starting with ':', '*', '#', etc. (DBL)
	  NOT WANT 
	  NOT WANT @param $linestart bool whether or not this is at the start of a line.
	  NOT WANT @private
	  NOT WANT @return string the lists rendered as HTML
	  WANT
	 SO IM LIKE doBlockLevels WITH UR $text, $linestart
		 IM ON UR wfProfileIn DOING __METHOD__

		 BTW Parsing through the text line by line.  The main thing
		 BTW happening here is handling of block-level elements p, pre,
		 BTW and making lists from lines starting with * # : etc.
		 BTW 
		 I HAS textLines IZ StringUtils::explode( "\n", $text )

		 I HAS lastPrefix IZ $output = ''
		 UR SPECIAL mDTopen IZ $inBlockElem = false;
		 I HAS prefixLength IZ 0
		 I HAS paragraphStack IZ false

		 IM IN UR textLines ITZA oLine
			 BTW Fix up $linestart
			 IZ !$linestart
				 output HAS MOAR $oLine;
				 I HAS linestart IZ true
				continue;
			 KTHX
			 BTW * = ul
			 BTW # = ol
			 BTW ; = dt
			 BTW : = dd

			 I HAS lastPrefixLength IZ strlen( $lastPrefix )
			 I HAS preCloseMatch IZ preg_match( '/<\\/pre/i', $oLine )
			 I HAS preOpenMatch IZ preg_match( '/<pre/i', $oLine )
			 BTW If not in a <pre> element, scan for and figure out what prefixes are there.
			 IZ !$this->mInPre
				 BTW Multiple prefixes may abut each other for nested lists.
				 I HAS prefixLength IZ strspn( $oLine, '*#:;' )
				 I HAS prefix IZ substr( $oLine, 0, $prefixLength )

				 BTW eh?
				 BTW ; and : are both from definition-lists, so they're equivalent
				 BTW  for the purposes of determining whether or not we need to open/close
				 BTW  elements.
				 I HAS prefix2 IZ str_replace( ';', ':', $prefix )
				 I HAS t IZ substr( $oLine, $prefixLength )
				 UR SPECIAL mInPre IZ (bool)$preOpenMatch;
			 NOWAI
				 BTW Don't interpret any other prefixes in preformatted text
				 I HAS prefixLength IZ 0
				 I HAS prefix IZ $prefix2 = ''
				 I HAS t IZ $oLine
			 KTHX

			 BTW List generation
			 IZ $prefixLength && $lastPrefix === $prefix2
				 BTW Same as the last item, so no need to deal with nesting or opening stuff
				 output HAS MOAR $this->nextItem( substr( $prefix, -1 ) );
				 I HAS paragraphStack IZ false

				if ( substr( $prefix, -1 ) === ';') {
					 BTW The one nasty exception: definition lists work like this:
					 BTW ; title : definition text
					 BTW So we check for : in the remainder text to split up the
					 BTW title and definition, without b0rking links.
					 I HAS term IZ $t2 = ''
					 IZ $this->findColonNoLinks( $t, $term, $t2 ) !== false
						 I HAS t IZ $t2
						 output HAS MOAR $term . $this->nextItem( ':' );
					 KTHX
				 KTHX
			 ORLY $prefixLength || $lastPrefixLength
				 BTW We need to open or close prefixes, or both.

				 BTW Either open or close a level...
				 I HAS commonPrefixLength IZ $this->getCommon( $prefix, $lastPrefix )
				 I HAS paragraphStack IZ false

				 BTW Close all the prefixes which aren't shared.
				 STEALIN UR $commonPrefixLength < $lastPrefixLength
					 output HAS MOAR $this->closeList( $lastPrefix[$lastPrefixLength-1] );
					--$lastPrefixLength;
				 KTHX

				 BTW Continue the current prefix if appropriate.
				 IZ $prefixLength <= $commonPrefixLength && $commonPrefixLength > 0
					 output HAS MOAR $this->nextItem( $prefix[$commonPrefixLength-1] );
				 KTHX

				 BTW Open prefixes where appropriate.
				 STEALIN UR $prefixLength > $commonPrefixLength
					 I HAS char IZ substr( $prefix, $commonPrefixLength, 1 )
					 output HAS MOAR $this->openList( $char );

					 IZ ';' === $char
						 BTW FIXME: This is dupe of code above
						 IZ $this->findColonNoLinks( $t, $term, $t2 ) !== false
							 I HAS t IZ $t2
							 output HAS MOAR $term . $this->nextItem( ':' );
						 KTHX
					 KTHX
					++$commonPrefixLength;
				 KTHX
				 I HAS lastPrefix IZ $prefix2
			 KTHX

			 BTW If we have no prefixes, go to paragraph mode.
			 IZ 0 == $prefixLength
				 IM ON UR wfProfileIn DOING __METHOD__."-paragraph"
				 BTW No prefix (not in list)--go to paragraph mode
				 BTW XXX: use a stack for nestable elements like span, table and div
				 I HAS openmatch IZ preg_match('/(?:<table|<blockquote|<h1|<h2|<h3|<h4|<h5|<h6|<pre|<tr|<p|<ul|<ol|<li|<\\/tr|<\\/td|<\\/th)/iS', $t )
				 closematch IZ preg_match(
					'/(?:<\\/table|<\\/blockquote|<\\/h1|<\\/h2|<\\/h3|<\\/h4|<\\/h5|<\\/h6|'.
					'<td|<th|<\\/?div|<hr|<\\/pre|<\\/p|'.$this->mUniqPrefix.'-pre|<\\/li|<\\/ul|<\\/ol|<\\/?center)/iS', $t );
				 IZ $openmatch or $closematch
					 I HAS paragraphStack IZ false
					# TODO bug 5718: paragraph closed
					 output HAS MOAR $this->closeParagraph();
					 IZ $preOpenMatch and !$preCloseMatch
						 UR SPECIAL mInPre IZ true;
					 KTHX
					 IZ $closematch
						 I HAS inBlockElem IZ false
					 NOWAI
						 I HAS inBlockElem IZ true
					 KTHX
				 ORLY !$inBlockElem && !$this->mInPre
					 IZ ' ' == substr( $t, 0, 1 ) and ( $this->mLastSection === 'pre' || trim( $t ) != '' )
						 BTW pre
						 IZ $this->mLastSection !== 'pre'
							 I HAS paragraphStack IZ false
							 output HAS MOAR $this->closeParagraph().'<pre>';
							 UR SPECIAL mLastSection IZ 'pre';
						 KTHX
						 I HAS t IZ substr( $t, 1 )
					 NOWAI
						 BTW paragraph
						 IZ trim( $t ) == ''
							 IZ $paragraphStack
								 output HAS MOAR $paragraphStack.'<br />';
								 I HAS paragraphStack IZ false
								 UR SPECIAL mLastSection IZ 'p';
							 NOWAI
								 IZ $this->mLastSection !== 'p'
									 output HAS MOAR $this->closeParagraph();
									 UR SPECIAL mLastSection IZ '';
									 I HAS paragraphStack IZ '<p>'
								 NOWAI
									 I HAS paragraphStack IZ '</p><p>'
								 KTHX
							 KTHX
						 NOWAI
							 IZ $paragraphStack
								 output HAS MOAR $paragraphStack;
								 I HAS paragraphStack IZ false
								 UR SPECIAL mLastSection IZ 'p';
							 ORLY $this->mLastSection !== 'p'
								 output HAS MOAR $this->closeParagraph().'<p>';
								 UR SPECIAL mLastSection IZ 'p';
							 KTHX
						 KTHX
					 KTHX
				 KTHX
				 IM ON UR wfProfileOut DOING __METHOD__."-paragraph"
			 KTHX
			 BTW somewhere above we forget to get out of pre block (bug 785)
			 IZ $preCloseMatch && $this->mInPre
				 UR SPECIAL mInPre IZ false;
			 KTHX
			 IZ $paragraphStack === false
				 output HAS MOAR $t."\n";
			 KTHX
		 KTHX
		 STEALIN UR $prefixLength
			 output HAS MOAR $this->closeList( $prefix2[$prefixLength-1] );
			--$prefixLength;
		 KTHX
		 IZ $this->mLastSection != ''
			 output HAS MOAR '</' . $this->mLastSection . '>';
			 UR SPECIAL mLastSection IZ '';
		 KTHX

		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $output
	 KTHX

	 DO NOT WANT
	  NOT WANT Split up a string on ':', ignoring any occurences inside tags
	  NOT WANT to prevent illegal overlapping.
	  NOT WANT @param string $str the string to split
	  NOT WANT @param string &$before set to everything before the ':'
	  NOT WANT @param string &$after set to everything after the ':'
	  NOT WANT return string the position of the ':', or false if none found
	  WANT
	 SO IM LIKE findColonNoLinks WITH UR $str, &$before, &$after
		 IM ON UR wfProfileIn DOING __METHOD__

		 I HAS pos IZ strpos( $str, ':' )
		 IZ $pos === false
			 BTW Nothing to find!
			 IM ON UR wfProfileOut DOING __METHOD__
			 I FOUND MAH false
		 KTHX

		 I HAS lt IZ strpos( $str, '<' )
		 IZ $lt === false || $lt > $pos
			 BTW Easy; no tag nesting to worry about
			 I HAS before IZ substr( $str, 0, $pos )
			 I HAS after IZ substr( $str, $pos+1 )
			 IM ON UR wfProfileOut DOING __METHOD__
			 I FOUND MAH $pos
		 KTHX

		 BTW Ugly state machine to walk through avoiding tags.
		 I HAS state IZ self::COLON_STATE_TEXT
		 I HAS stack IZ 0
		 I HAS len IZ strlen( $str )
		for( $i = 0; $i < $len; $i++ ) {
			 I HAS c IZ $str{$i}

			switch( $state ) {
			 BTW (Using the number is a performance hack for common cases)
			case 0: # self::COLON_STATE_TEXT:
				switch( $c ) {
				case "<":
					 BTW Could be either a <start> tag or an </end> tag
					 I HAS state IZ self::COLON_STATE_TAGSTART
					break;
				case ":":
					 IZ $stack == 0
						 BTW We found it!
						 I HAS before IZ substr( $str, 0, $i )
						 I HAS after IZ substr( $str, $i + 1 )
						 IM ON UR wfProfileOut DOING __METHOD__
						 I FOUND MAH $i
					 KTHX
					 BTW Embedded in a tag; don't break it.
					break;
				default:
					 BTW Skip ahead looking for something interesting
					 I HAS colon IZ strpos( $str, ':', $i )
					 IZ $colon === false
						 BTW Nothing else interesting
						 IM ON UR wfProfileOut DOING __METHOD__
						 I FOUND MAH false
					 KTHX
					 I HAS lt IZ strpos( $str, '<', $i )
					 IZ $stack === 0
						 IZ $lt === false || $colon < $lt
							 BTW We found it!
							 I HAS before IZ substr( $str, 0, $colon )
							 I HAS after IZ substr( $str, $colon + 1 )
							 IM ON UR wfProfileOut DOING __METHOD__
							 I FOUND MAH $i
						 KTHX
					 KTHX
					 IZ $lt === false
						 BTW Nothing else interesting to find; abort!
						 BTW We're nested, but there's no close tags left. Abort!
						break 2;
					 KTHX
					 BTW Skip ahead to next tag start
					 I HAS i IZ $lt
					 I HAS state IZ self::COLON_STATE_TAGSTART
				 KTHX
				break;
			case 1: # self::COLON_STATE_TAG:
				 BTW In a <tag>
				switch( $c ) {
				case ">":
					$stack++;
					 I HAS state IZ self::COLON_STATE_TEXT
					break;
				case "/":
					 BTW Slash may be followed by >?
					 I HAS state IZ self::COLON_STATE_TAGSLASH
					break;
				default:
					 BTW ignore
				 KTHX
				break;
			case 2: # self::COLON_STATE_TAGSTART:
				switch( $c ) {
				case "/":
					 I HAS state IZ self::COLON_STATE_CLOSETAG
					break;
				case "!":
					 I HAS state IZ self::COLON_STATE_COMMENT
					break;
				case ">":
					 BTW Illegal early close? This shouldn't happen D:
					 I HAS state IZ self::COLON_STATE_TEXT
					break;
				default:
					 I HAS state IZ self::COLON_STATE_TAG
				 KTHX
				break;
			case 3: # self::COLON_STATE_CLOSETAG:
				 BTW In a </tag>
				 IZ $c === ">"
					 I HAS stack--
					 IZ $stack < 0
						 IM ON UR wfDebug DOING __METHOD__.": Invalid input; too many close tags\n"
						 IM ON UR wfProfileOut DOING __METHOD__
						 I FOUND MAH false
					 KTHX
					 I HAS state IZ self::COLON_STATE_TEXT
				 KTHX
				break;
			case self::COLON_STATE_TAGSLASH:
				 IZ $c === ">"
					 BTW Yes, a self-closed tag <blah/>
					 I HAS state IZ self::COLON_STATE_TEXT
				 NOWAI
					 BTW Probably we're jumping the gun, and this is an attribute
					 I HAS state IZ self::COLON_STATE_TAG
				 KTHX
				break;
			case 5: # self::COLON_STATE_COMMENT:
				 IZ $c === "-"
					 I HAS state IZ self::COLON_STATE_COMMENTDASH
				 KTHX
				break;
			case self::COLON_STATE_COMMENTDASH:
				 IZ $c === "-"
					 I HAS state IZ self::COLON_STATE_COMMENTDASHDASH
				 NOWAI
					 I HAS state IZ self::COLON_STATE_COMMENT
				 KTHX
				break;
			case self::COLON_STATE_COMMENTDASHDASH:
				 IZ $c === ">"
					 I HAS state IZ self::COLON_STATE_TEXT
				 NOWAI
					 I HAS state IZ self::COLON_STATE_COMMENT
				 KTHX
				break;
			default:
				throw new MWException( "State machine error in " . __METHOD__ );
			 KTHX
		 KTHX
		 IZ $stack > 0
			 IM ON UR wfDebug DOING __METHOD__.": Invalid input; not enough close tags (stack $stack, state $state)\n"
			 I FOUND MAH false
		 KTHX
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH false
	 KTHX

	 DO NOT WANT
	  NOT WANT Return value of a magic variable (like PAGENAME)
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE getVariableValue WITH UR $index, $frame=false
		 I HAS UR $wgContLang, $wgSitename, $wgServer, $wgServerName ON UR INTERNETS
		 I HAS UR $wgScriptPath, $wgStylePath ON UR INTERNETS

		 DO NOT WANT
		  NOT WANT Some of these require message or data lookups and can be
		  NOT WANT expensive to check many times.
		  WANT
		 IZ wfRunHooks( 'ParserGetVariableValueVarCache', array( &$this, &$this->mVarCache ) )
			 IZ isset( $this->mVarCache[$index] )
				 I FOUND MAH $this->mVarCache[$index]
			 KTHX
		 KTHX

		 I HAS ts IZ wfTimestamp( TS_UNIX, $this->mOptions->getTimestamp() )
		 IM ON UR wfRunHooks DOING 'ParserGetVariableValueTs', array( &$this, &$ts )

		 BTW Use the time zone
		 I HAS UR $wgLocaltimezone ON UR INTERNETS
		 IZ isset( $wgLocaltimezone )
			 I HAS oldtz IZ date_default_timezone_get()
			 IM ON UR date_default_timezone_set DOING $wgLocaltimezone
		 KTHX

		 I HAS localTimestamp IZ date( 'YmdHis', $ts )
		 I HAS localMonth IZ date( 'm', $ts )
		 I HAS localMonth1 IZ date( 'n', $ts )
		 I HAS localMonthName IZ date( 'n', $ts )
		 I HAS localDay IZ date( 'j', $ts )
		 I HAS localDay2 IZ date( 'd', $ts )
		 I HAS localDayOfWeek IZ date( 'w', $ts )
		 I HAS localWeek IZ date( 'W', $ts )
		 I HAS localYear IZ date( 'Y', $ts )
		 I HAS localHour IZ date( 'H', $ts )
		 IZ isset( $wgLocaltimezone )
			 IM ON UR date_default_timezone_set DOING $oldtz
		 KTHX

		switch ( $index ) {
			case 'currentmonth':
				 I HAS value IZ $wgContLang->formatNum( gmdate( 'm', $ts ) )
				break;
			case 'currentmonth1':
				 I HAS value IZ $wgContLang->formatNum( gmdate( 'n', $ts ) )
				break;
			case 'currentmonthname':
				 I HAS value IZ $wgContLang->getMonthName( gmdate( 'n', $ts ) )
				break;
			case 'currentmonthnamegen':
				 I HAS value IZ $wgContLang->getMonthNameGen( gmdate( 'n', $ts ) )
				break;
			case 'currentmonthabbrev':
				 I HAS value IZ $wgContLang->getMonthAbbreviation( gmdate( 'n', $ts ) )
				break;
			case 'currentday':
				 I HAS value IZ $wgContLang->formatNum( gmdate( 'j', $ts ) )
				break;
			case 'currentday2':
				 I HAS value IZ $wgContLang->formatNum( gmdate( 'd', $ts ) )
				break;
			case 'localmonth':
				 I HAS value IZ $wgContLang->formatNum( $localMonth )
				break;
			case 'localmonth1':
				 I HAS value IZ $wgContLang->formatNum( $localMonth1 )
				break;
			case 'localmonthname':
				 I HAS value IZ $wgContLang->getMonthName( $localMonthName )
				break;
			case 'localmonthnamegen':
				 I HAS value IZ $wgContLang->getMonthNameGen( $localMonthName )
				break;
			case 'localmonthabbrev':
				 I HAS value IZ $wgContLang->getMonthAbbreviation( $localMonthName )
				break;
			case 'localday':
				 I HAS value IZ $wgContLang->formatNum( $localDay )
				break;
			case 'localday2':
				 I HAS value IZ $wgContLang->formatNum( $localDay2 )
				break;
			case 'pagename':
				 I HAS value IZ wfEscapeWikiText( $this->mTitle->getText() )
				break;
			case 'pagenamee':
				 I HAS value IZ $this->mTitle->getPartialURL()
				break;
			case 'fullpagename':
				 I HAS value IZ wfEscapeWikiText( $this->mTitle->getPrefixedText() )
				break;
			case 'fullpagenamee':
				 I HAS value IZ $this->mTitle->getPrefixedURL()
				break;
			case 'subpagename':
				 I HAS value IZ wfEscapeWikiText( $this->mTitle->getSubpageText() )
				break;
			case 'subpagenamee':
				 I HAS value IZ $this->mTitle->getSubpageUrlForm()
				break;
			case 'basepagename':
				 I HAS value IZ wfEscapeWikiText( $this->mTitle->getBaseText() )
				break;
			case 'basepagenamee':
				 I HAS value IZ wfUrlEncode( str_replace( ' ', '_', $this->mTitle->getBaseText() ) )
				break;
			case 'talkpagename':
				 IZ $this->mTitle->canTalk()
					 I HAS talkPage IZ $this->mTitle->getTalkPage()
					 I HAS value IZ wfEscapeWikiText( $talkPage->getPrefixedText() )
				 NOWAI
					 I HAS value IZ ''
				 KTHX
				break;
			case 'talkpagenamee':
				 IZ $this->mTitle->canTalk()
					 I HAS talkPage IZ $this->mTitle->getTalkPage()
					 I HAS value IZ $talkPage->getPrefixedUrl()
				 NOWAI
					 I HAS value IZ ''
				 KTHX
				break;
			case 'subjectpagename':
				 I HAS subjPage IZ $this->mTitle->getSubjectPage()
				 I HAS value IZ wfEscapeWikiText( $subjPage->getPrefixedText() )
				break;
			case 'subjectpagenamee':
				 I HAS subjPage IZ $this->mTitle->getSubjectPage()
				 I HAS value IZ $subjPage->getPrefixedUrl()
				break;
			case 'revisionid':
				 BTW Let the edit saving system know we should parse the page
				 BTW *after* a revision ID has been assigned.
				 IM ON UR SPECIAL mOutput->setFlag DOING 'vary-revision'
				 IM ON UR wfDebug DOING __METHOD__ . ": {{REVISIONID}} used, setting vary-revision...\n"
				 I HAS value IZ $this->mRevisionId
				break;
			case 'revisionday':
				 BTW Let the edit saving system know we should parse the page
				 BTW *after* a revision ID has been assigned. This is for null edits.
				 IM ON UR SPECIAL mOutput->setFlag DOING 'vary-revision'
				 IM ON UR wfDebug DOING __METHOD__ . ": {{REVISIONDAY}} used, setting vary-revision...\n"
				 I HAS value IZ intval( substr( $this->getRevisionTimestamp(), 6, 2 ) )
				break;
			case 'revisionday2':
				 BTW Let the edit saving system know we should parse the page
				 BTW *after* a revision ID has been assigned. This is for null edits.
				 IM ON UR SPECIAL mOutput->setFlag DOING 'vary-revision'
				 IM ON UR wfDebug DOING __METHOD__ . ": {{REVISIONDAY2}} used, setting vary-revision...\n"
				 I HAS value IZ substr( $this->getRevisionTimestamp(), 6, 2 )
				break;
			case 'revisionmonth':
				 BTW Let the edit saving system know we should parse the page
				 BTW *after* a revision ID has been assigned. This is for null edits.
				 IM ON UR SPECIAL mOutput->setFlag DOING 'vary-revision'
				 IM ON UR wfDebug DOING __METHOD__ . ": {{REVISIONMONTH}} used, setting vary-revision...\n"
				 I HAS value IZ intval( substr( $this->getRevisionTimestamp(), 4, 2 ) )
				break;
			case 'revisionyear':
				 BTW Let the edit saving system know we should parse the page
				 BTW *after* a revision ID has been assigned. This is for null edits.
				 IM ON UR SPECIAL mOutput->setFlag DOING 'vary-revision'
				 IM ON UR wfDebug DOING __METHOD__ . ": {{REVISIONYEAR}} used, setting vary-revision...\n"
				 I HAS value IZ substr( $this->getRevisionTimestamp(), 0, 4 )
				break;
			case 'revisiontimestamp':
				 BTW Let the edit saving system know we should parse the page
				 BTW *after* a revision ID has been assigned. This is for null edits.
				 IM ON UR SPECIAL mOutput->setFlag DOING 'vary-revision'
				 IM ON UR wfDebug DOING __METHOD__ . ": {{REVISIONTIMESTAMP}} used, setting vary-revision...\n"
				 I HAS value IZ $this->getRevisionTimestamp()
				break;
			case 'revisionuser':
				 BTW Let the edit saving system know we should parse the page
				 BTW *after* a revision ID has been assigned. This is for null edits.
				 IM ON UR SPECIAL mOutput->setFlag DOING 'vary-revision'
				 IM ON UR wfDebug DOING __METHOD__ . ": {{REVISIONUSER}} used, setting vary-revision...\n"
				 I HAS value IZ $this->getRevisionUser()
				break;
			case 'namespace':
				 I HAS value IZ str_replace( '_',' ',$wgContLang->getNsText( $this->mTitle->getNamespace() ) )
				break;
			case 'namespacee':
				 I HAS value IZ wfUrlencode( $wgContLang->getNsText( $this->mTitle->getNamespace() ) )
				break;
			case 'talkspace':
				 I HAS value IZ $this->mTitle->canTalk() ? str_replace( '_',' ',$this->mTitle->getTalkNsText() ) : ''
				break;
			case 'talkspacee':
				 I HAS value IZ $this->mTitle->canTalk() ? wfUrlencode( $this->mTitle->getTalkNsText() ) : ''
				break;
			case 'subjectspace':
				 I HAS value IZ $this->mTitle->getSubjectNsText()
				break;
			case 'subjectspacee':
				 I HAS value IZ ( wfUrlencode( $this->mTitle->getSubjectNsText() ) )
				break;
			case 'currentdayname':
				 I HAS value IZ $wgContLang->getWeekdayName( gmdate( 'w', $ts ) + 1 )
				break;
			case 'currentyear':
				 I HAS value IZ $wgContLang->formatNum( gmdate( 'Y', $ts ), true )
				break;
			case 'currenttime':
				 I HAS value IZ $wgContLang->time( wfTimestamp( TS_MW, $ts ), false, false )
				break;
			case 'currenthour':
				 I HAS value IZ $wgContLang->formatNum( gmdate( 'H', $ts ), true )
				break;
			case 'currentweek':
				 BTW @bug 4594 PHP5 has it zero padded, PHP4 does not, cast to
				 BTW int to remove the padding
				 I HAS value IZ $wgContLang->formatNum( (int)gmdate( 'W', $ts ) )
				break;
			case 'currentdow':
				 I HAS value IZ $wgContLang->formatNum( gmdate( 'w', $ts ) )
				break;
			case 'localdayname':
				 I HAS value IZ $wgContLang->getWeekdayName( $localDayOfWeek + 1 )
				break;
			case 'localyear':
				 I HAS value IZ $wgContLang->formatNum( $localYear, true )
				break;
			case 'localtime':
				 I HAS value IZ $wgContLang->time( $localTimestamp, false, false )
				break;
			case 'localhour':
				 I HAS value IZ $wgContLang->formatNum( $localHour, true )
				break;
			case 'localweek':
				 BTW @bug 4594 PHP5 has it zero padded, PHP4 does not, cast to
				 BTW int to remove the padding
				 I HAS value IZ $wgContLang->formatNum( (int)$localWeek )
				break;
			case 'localdow':
				 I HAS value IZ $wgContLang->formatNum( $localDayOfWeek )
				break;
			case 'numberofarticles':
				 I HAS value IZ $wgContLang->formatNum( SiteStats::articles() )
				break;
			case 'numberoffiles':
				 I HAS value IZ $wgContLang->formatNum( SiteStats::images() )
				break;
			case 'numberofusers':
				 I HAS value IZ $wgContLang->formatNum( SiteStats::users() )
				break;
			case 'numberofactiveusers':
				 I HAS value IZ $wgContLang->formatNum( SiteStats::activeUsers() )
				break;
			case 'numberofpages':
				 I HAS value IZ $wgContLang->formatNum( SiteStats::pages() )
				break;
			case 'numberofadmins':
				 I HAS value IZ $wgContLang->formatNum( SiteStats::numberingroup( 'sysop' ) )
				break;
			case 'numberofedits':
				 I HAS value IZ $wgContLang->formatNum( SiteStats::edits() )
				break;
			case 'numberofviews':
				 I HAS value IZ $wgContLang->formatNum( SiteStats::views() )
				break;
			case 'currenttimestamp':
				 I HAS value IZ wfTimestamp( TS_MW, $ts )
				break;
			case 'localtimestamp':
				 I HAS value IZ $localTimestamp
				break;
			case 'currentversion':
				 I HAS value IZ SpecialVersion::getVersion()
				break;
			case 'sitename':
				 I FOUND MAH $wgSitename
			case 'server':
				 I FOUND MAH $wgServer
			case 'servername':
				 I FOUND MAH $wgServerName
			case 'scriptpath':
				 I FOUND MAH $wgScriptPath
			case 'stylepath':
				 I FOUND MAH $wgStylePath
			case 'directionmark':
				 I FOUND MAH $wgContLang->getDirMark()
			case 'contentlanguage':
				 I HAS UR $wgContLanguageCode ON UR INTERNETS
				 I FOUND MAH $wgContLanguageCode
			default:
				 I HAS ret
				 IZ wfRunHooks( 'ParserGetVariableValueSwitch', array( &$this, &$this->mVarCache, &$index, &$ret, &$frame ) )
					 I FOUND MAH $ret
				 NOWAI
					 I FOUND MAH null
				 KTHX
		 KTHX

		if ( $index )
			$this->mVarCache[$index] = $value;

		 I FOUND MAH $value
	 KTHX

	 DO NOT WANT
	  NOT WANT initialise the magic variables (like CURRENTMONTHNAME) and substitution modifiers 
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE initialiseVariables
		 IM ON UR wfProfileIn DOING __METHOD__
		 I HAS variableIDs IZ MagicWord::getVariableIDs()
		 I HAS substIDs IZ MagicWord::getSubstIDs()

		 UR SPECIAL mVariables IZ new MagicWordArray( $variableIDs );
		 UR SPECIAL mSubstWords IZ new MagicWordArray( $substIDs );
		 IM ON UR wfProfileOut DOING __METHOD__
	 KTHX

	 DO NOT WANT
	  NOT WANT Preprocess some wikitext and return the document tree.
	  NOT WANT This is the ghost of replace_variables().
	  NOT WANT 
	  NOT WANT @param string $text The text to parse
	  NOT WANT @param integer flags Bitwise combination of:
	  NOT WANT          self::PTD_FOR_INCLUSION    Handle <noinclude>/<includeonly> as if the text is being
	  NOT WANT                                     included. Default is to assume a direct page view.
	  NOT WANT 
	  NOT WANT The generated DOM tree must depend only on the input text and the flags.
	  NOT WANT The DOM tree must be the same in OT_HTML and OT_WIKI mode, to avoid a regression of bug 4899.
	  NOT WANT 
	  NOT WANT Any flag added to the $flags parameter here, or any other parameter liable to cause a
	  NOT WANT change in the DOM tree for a given text, must be passed through the section identifier
	  NOT WANT in the section edit link and thus back to extractSections().
	  NOT WANT 
	  NOT WANT The output of this function is currently only cached in process memory, but a persistent
	  NOT WANT cache may be implemented at a later date which takes further advantage of these strict
	  NOT WANT dependency requirements.
	  NOT WANT 
	  NOT WANT @private
	  WANT
	 SO IM LIKE preprocessToDom WITH UR $text, $flags = 0
		 I HAS dom IZ $this->getPreprocessor()->preprocessToObj( $text, $flags )
		 I FOUND MAH $dom
	 KTHX

	 DO NOT WANT
	  NOT WANT Return a three-element array: leading whitespace, string contents, trailing whitespace
	  WANT
	 SO IM ALWAYS LIKE splitWhitespace WITH UR $s
		 I HAS ltrimmed IZ ltrim( $s )
		 I HAS w1 IZ substr( $s, 0, strlen( $s ) - strlen( $ltrimmed ) )
		 I HAS trimmed IZ rtrim( $ltrimmed )
		 I HAS diff IZ strlen( $ltrimmed ) - strlen( $trimmed )
		 IZ $diff > 0
			 I HAS w2 IZ substr( $ltrimmed, -$diff )
		 NOWAI
			 I HAS w2 IZ ''
		 KTHX
		 I FOUND MAH array( $w1, $trimmed, $w2 )
	 KTHX

	 DO NOT WANT
	  NOT WANT Replace magic variables, templates, and template arguments
	  NOT WANT with the appropriate text. Templates are substituted recursively,
	  NOT WANT taking care to avoid infinite loops.
	  NOT WANT 
	  NOT WANT Note that the substitution depends on value of $mOutputType:
	  NOT WANT  self::OT_WIKI: only {{subst:}} templates
	  NOT WANT  self::OT_PREPROCESS: templates but not extension tags
	  NOT WANT  self::OT_HTML: all templates and extension tags
	  NOT WANT 
	  NOT WANT @param string $tex The text to transform
	  NOT WANT @param PPFrame $frame Object describing the arguments passed to the template.
	  NOT WANT        Arguments may also be provided as an associative array, as was the usual case before MW1.12.
	  NOT WANT        Providing arguments this way may be useful for extensions wishing to perform variable replacement explicitly.
	  NOT WANT @param bool $argsOnly Only do argument (triple-brace) expansion, not double-brace expansion
	  NOT WANT @private
	  WANT
	 SO IM LIKE replaceVariables WITH UR $text, $frame = false, $argsOnly = false
		 BTW Is there any text? Also, Prevent too big inclusions!
		 IZ strlen( $text ) < 1 || strlen( $text ) > $this->mOptions->getMaxIncludeSize()
			 I FOUND MAH $text
		 KTHX
		 IM ON UR wfProfileIn DOING __METHOD__

		 IZ $frame === false
			 I HAS frame IZ $this->getPreprocessor()->newFrame()
		 ORLY !( $frame instanceof PPFrame )
			 IM ON UR wfDebug DOING __METHOD__." called using plain parameters instead of a PPFrame instance. Creating custom frame.\n"
			 I HAS frame IZ $this->getPreprocessor()->newCustomFrame( $frame )
		 KTHX

		 I HAS dom IZ $this->preprocessToDom( $text )
		 I HAS flags IZ $argsOnly ? PPFrame::NO_TEMPLATES : 0
		 I HAS text IZ $frame->expand( $dom, $flags )

		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX

	 BTW Clean up argument array - refactored in 1.9 so parserfunctions can use it, too.
	 SO IM ALWAYS LIKE createAssocArgs WITH UR $args
		 I HAS assocArgs IZ EMPTY
		 I HAS index IZ 1
		 IM IN UR args ITZA arg
			 I HAS eqpos IZ strpos( $arg, '=' )
			 IZ $eqpos === false
				$assocArgs[$index++] = $arg;
			 NOWAI
				 I HAS name IZ trim( substr( $arg, 0, $eqpos ) )
				 I HAS value IZ trim( substr( $arg, $eqpos+1 ) )
				 IZ $value === false
					 I HAS value IZ ''
				 KTHX
				 IZ $name !== false
					$assocArgs[$name] = $value;
				 KTHX
			 KTHX
		 KTHX

		 I FOUND MAH $assocArgs
	 KTHX

	 DO NOT WANT
	  NOT WANT Warn the user when a parser limitation is reached
	  NOT WANT Will warn at most once the user per limitation type
	  NOT WANT 
	  NOT WANT @param string $limitationType, should be one of:
	  NOT WANT   'expensive-parserfunction' (corresponding messages: 
	  NOT WANT       'expensive-parserfunction-warning', 
	  NOT WANT       'expensive-parserfunction-category')
	  NOT WANT   'post-expand-template-argument' (corresponding messages: 
	  NOT WANT       'post-expand-template-argument-warning', 
	  NOT WANT       'post-expand-template-argument-category')
	  NOT WANT   'post-expand-template-inclusion' (corresponding messages: 
	  NOT WANT       'post-expand-template-inclusion-warning', 
	  NOT WANT       'post-expand-template-inclusion-category')
	  NOT WANT @params int $current, $max When an explicit limit has been
	  NOT WANT 	 exceeded, provide the values (optional)
	  WANT
	function limitationWarn( $limitationType, $current=null, $max=null) {
		 BTW does no harm if $current and $max are present but are unnecessary for the message
		 I HAS warning IZ wfMsgExt( "$limitationType-warning", array( 'parsemag', 'escape' ), $current, $max )
		 IM ON UR SPECIAL mOutput->addWarning DOING $warning
		 IM ON UR SPECIAL addTrackingCategory DOING "$limitationType-category"
	 KTHX

	 DO NOT WANT
	  NOT WANT Return the text of a template, after recursively
	  NOT WANT replacing any variables or templates within the template.
	  NOT WANT 
	  NOT WANT @param array $piece The parts of the template
	  NOT WANT  $piece['title']: the title, i.e. the part before the |
	  NOT WANT  $piece['parts']: the parameter array
	  NOT WANT  $piece['lineStart']: whether the brace was at the start of a line
	  NOT WANT @param PPFrame The current frame, contains template arguments
	  NOT WANT @return string the text of the template
	  NOT WANT @private
	  WANT
	 SO IM LIKE braceSubstitution WITH UR $piece, $frame
		 I HAS UR $wgContLang, $wgNonincludableNamespaces ON UR INTERNETS
		 IM ON UR wfProfileIn DOING __METHOD__
		 IM ON UR wfProfileIn DOING __METHOD__.'-setup'

		 BTW Flags
		 I HAS found IZ false             # $text has been filled
		 I HAS nowiki IZ false            # wiki markup in $text should be escaped
		 I HAS isHTML IZ false            # $text is HTML, armour it against wikitext transformation
		 I HAS forceRawInterwiki IZ false # Force interwiki transclusion to be done in raw mode not rendered
		 I HAS isChildObj IZ false        # $text is a DOM node needing expansion in a child frame
		 I HAS isLocalObj IZ false        # $text is a DOM node needing expansion in the current frame

		 BTW Title object, where $text came from
		 I HAS title

		 BTW $part1 is the bit before the first |, and must contain only title characters.
		 BTW Various prefixes will be stripped from it later.
		 I HAS titleWithSpaces IZ $frame->expand( $piece['title'] )
		 I HAS part1 IZ trim( $titleWithSpaces )
		 I HAS titleText IZ false

		 BTW Original title text preserved for various purposes
		 I HAS originalTitle IZ $part1

		 BTW $args is a list of argument nodes, starting from index 0, not including $part1
		 I HAS args IZ ( null == $piece['parts'] ) ? array() : $piece['parts']
		 IM ON UR wfProfileOut DOING __METHOD__.'-setup'

		 BTW SUBST
		 IM ON UR wfProfileIn DOING __METHOD__.'-modifiers'
		 IZ !$found

			 I HAS substMatch IZ $this->mSubstWords->matchStartAndRemove( $part1 )

			 BTW Possibilities for substMatch: "subst", "safesubst" or FALSE
			 BTW Decide whether to expand template or keep wikitext as-is.
			 IZ $this->ot['wiki']
				 IZ $substMatch === false
					 I HAS literal IZ true  # literal when in PST with no prefix
				 NOWAI
					 I HAS literal IZ false # expand when in PST with subst: or safesubst:
				 KTHX
			 NOWAI
				 IZ $substMatch == 'subst'
					 I HAS literal IZ true  # literal when not in PST with plain subst:
				 NOWAI
					 I HAS literal IZ false # expand when not in PST with safesubst: or no prefix
				 KTHX
			 KTHX
			 IZ $literal
				 I HAS text IZ $frame->virtualBracketedImplode( '{{', '|', '}}', $titleWithSpaces, $args )
				 I HAS isLocalObj IZ true
				 I HAS found IZ true
			 KTHX
		 KTHX

		 BTW Variables
		 IZ !$found && $args->getLength() == 0
			 I HAS id IZ $this->mVariables->matchStartToEnd( $part1 )
			 IZ $id !== false
				 I HAS text IZ $this->getVariableValue( $id, $frame )
				 IZ MagicWord::getCacheTTL( $id ) > -1
					 UR SPECIAL mOutput->mContainsOldMagic IZ true;
				 KTHX
				 I HAS found IZ true
			 KTHX
		 KTHX

		 BTW MSG, MSGNW and RAW
		 IZ !$found
			 BTW Check for MSGNW:
			 I HAS mwMsgnw IZ MagicWord::get( 'msgnw' )
			 IZ $mwMsgnw->matchStartAndRemove( $part1 )
				 I HAS nowiki IZ true
			 NOWAI
				 BTW Remove obsolete MSG:
				 I HAS mwMsg IZ MagicWord::get( 'msg' )
				$mwMsg->matchStartAndRemove( $part1 );
			 KTHX

			 BTW Check for RAW:
			 I HAS mwRaw IZ MagicWord::get( 'raw' )
			 IZ $mwRaw->matchStartAndRemove( $part1 )
				 I HAS forceRawInterwiki IZ true
			 KTHX
		 KTHX
		 IM ON UR wfProfileOut DOING __METHOD__.'-modifiers'

		 BTW Parser functions
		 IZ !$found
			 IM ON UR wfProfileIn DOING __METHOD__ . '-pfunc'

			 I HAS colonPos IZ strpos( $part1, ':' )
			 IZ $colonPos !== false
				 BTW Case sensitive functions
				 I HAS function IZ substr( $part1, 0, $colonPos )
				 IZ isset( $this->mFunctionSynonyms[1][$function] )
					 I HAS function IZ $this->mFunctionSynonyms[1][$function]
				 NOWAI
					 BTW Case insensitive functions
					 I HAS function IZ $wgContLang->lc( $function )
					 IZ isset( $this->mFunctionSynonyms[0][$function] )
						 I HAS function IZ $this->mFunctionSynonyms[0][$function]
					 NOWAI
						 I HAS function IZ false
					 KTHX
				 KTHX
				 IZ $function
					list( $callback, $flags ) = $this->mFunctionHooks[$function];
					 I HAS initialArgs IZ BUCKET &$this );
					 I HAS funcArgs IZ BUCKET trim( substr( $part1, $colonPos + 1 ) ) );
					 IZ $flags & SFH_OBJECT_ARGS
						 BTW Add a frame parameter, and pass the arguments as an array
						 I HAS allArgs IZ $initialArgs
						$allArgs[] = $frame;
						for ( $i = 0; $i < $args->getLength(); $i++ ) {
							$funcArgs[] = $args->item( $i );
						 KTHX
						$allArgs[] = $funcArgs;
					 NOWAI
						 BTW Convert arguments to plain text
						for ( $i = 0; $i < $args->getLength(); $i++ ) {
							$funcArgs[] = trim( $frame->expand( $args->item( $i ) ) );
						 KTHX
						 I HAS allArgs IZ array_merge( $initialArgs, $funcArgs )
					 KTHX

					 BTW Workaround for PHP bug 35229 and similar
					 IZ !is_callable( $callback )
						 IM ON UR wfProfileOut DOING __METHOD__ . '-pfunc'
						 IM ON UR wfProfileOut DOING __METHOD__
						throw new MWException( "Tag hook for $function is not callable\n" );
					 KTHX
					 I HAS result IZ call_user_func_array( $callback, $allArgs )
					 I HAS found IZ true
					 I HAS noparse IZ true
					 I HAS preprocessFlags IZ 0

					 IZ is_array( $result )
						 IZ isset( $result[0] )
							 I HAS text IZ $result[0]
							 IM ON UR unset DOING $result[0]
						 KTHX

						 BTW Extract flags into the local scope
						 BTW This allows callers to set flags such as nowiki, found, etc.
						 IM ON UR extract DOING $result
					 NOWAI
						 I HAS text IZ $result
					 KTHX
					 IZ !$noparse
						 I HAS text IZ $this->preprocessToDom( $text, $preprocessFlags )
						 I HAS isChildObj IZ true
					 KTHX
				 KTHX
			 KTHX
			 IM ON UR wfProfileOut DOING __METHOD__ . '-pfunc'
		 KTHX

		 BTW Finish mangling title and then check for loops.
		 BTW Set $title to a Title object and $titleText to the PDBK
		 IZ !$found
			 I HAS ns IZ NS_TEMPLATE
			 BTW Split the title into page and subpage
			 I HAS subpage IZ ''
			 I HAS part1 IZ $this->maybeDoSubpageLink( $part1, $subpage )
			 IZ $subpage !== ''
				 I HAS ns IZ $this->mTitle->getNamespace()
			 KTHX
			 I HAS title IZ Title::newFromText( $part1, $ns )
			 IZ $title
				 I HAS titleText IZ $title->getPrefixedText()
				 BTW Check for language variants if the template is not found
				 IZ $wgContLang->hasVariants() && $title->getArticleID() == 0
					$wgContLang->findVariantLink( $part1, $title, true );
				 KTHX
				 BTW Do recursion depth check
				 I HAS limit IZ $this->mOptions->getMaxTemplateDepth()
				 IZ $frame->depth >= $limit
					 I HAS found IZ true
					 text IZ '<span class="error">' 
						. wfMsgForContent( 'parser-template-recursion-depth-warning', $limit ) 
						. '</span>';
				 KTHX
			 KTHX
		 KTHX

		 BTW Load from database
		 IZ !$found && $title
			 IM ON UR wfProfileIn DOING __METHOD__ . '-loadtpl'
			 IZ !$title->isExternal()
				if ( $title->getNamespace() == NS_SPECIAL 
					&& $this->mOptions->getAllowSpecialInclusion() 
					&& $this->ot['html'] ) 
				{
					 I HAS text IZ SpecialPage::capturePath( $title )
					 IZ is_string( $text )
						 I HAS found IZ true
						 I HAS isHTML IZ true
						 IM ON UR SPECIAL disableCache
					 KTHX
				 ORLY $wgNonincludableNamespaces && in_array( $title->getNamespace(), $wgNonincludableNamespaces )
					 I HAS found IZ false # access denied
					 IM ON UR wfDebug DOING __METHOD__.": template inclusion denied for " . $title->getPrefixedDBkey()
				 NOWAI
					 IM ON UR list DOING $text, $title ) = $this->getTemplateDom( $title
					 IZ $text !== false
						 I HAS found IZ true
						 I HAS isChildObj IZ true
					 KTHX
				 KTHX

				 BTW If the title is valid but undisplayable, make a link to it
				 IZ !$found && ( $this->ot['html'] || $this->ot['pre'] )
					 I HAS text IZ "[[:$titleText]]"
					 I HAS found IZ true
				 KTHX
			 ORLY $title->isTrans()
				 BTW Interwiki transclusion
				 IZ $this->ot['html'] && !$forceRawInterwiki
					 I HAS text IZ $this->interwikiTransclude( $title, 'render' )
					 I HAS isHTML IZ true
				 NOWAI
					 I HAS text IZ $this->interwikiTransclude( $title, 'raw' )
					 BTW Preprocess it like a template
					 I HAS text IZ $this->preprocessToDom( $text, self::PTD_FOR_INCLUSION )
					 I HAS isChildObj IZ true
				 KTHX
				 I HAS found IZ true
			 KTHX

			 BTW Do infinite loop check
			 BTW This has to be done after redirect resolution to avoid infinite loops via redirects
			 IZ !$frame->loopCheck( $title )
				 I HAS found IZ true
				 I HAS text IZ '<span class="error">' . wfMsgForContent( 'parser-template-loop-warning', $titleText ) . '</span>'
				 IM ON UR wfDebug DOING __METHOD__.": template loop broken at '$titleText'\n"
			 KTHX
			 IM ON UR wfProfileOut DOING __METHOD__ . '-loadtpl'
		 KTHX

		 BTW If we haven't found text to substitute by now, we're done
		 BTW Recover the source wikitext and return it
		 IZ !$found
			 I HAS text IZ $frame->virtualBracketedImplode( '{{', '|', '}}', $titleWithSpaces, $args )
			 IM ON UR wfProfileOut DOING __METHOD__
			 I FOUND MAH array( 'object' => $text )
		 KTHX

		 BTW Expand DOM-style return values in a child frame
		 IZ $isChildObj
			 BTW Clean up argument array
			 I HAS newFrame IZ $frame->newChild( $args, $title )

			 IZ $nowiki
				 I HAS text IZ $newFrame->expand( $text, PPFrame::RECOVER_ORIG )
			 ORLY $titleText !== false && $newFrame->isEmpty()
				 BTW Expansion is eligible for the empty-frame cache
				 IZ isset( $this->mTplExpandCache[$titleText] )
					 I HAS text IZ $this->mTplExpandCache[$titleText]
				 NOWAI
					 I HAS text IZ $newFrame->expand( $text )
					$this->mTplExpandCache[$titleText] = $text;
				 KTHX
			 NOWAI
				 BTW Uncached expansion
				 I HAS text IZ $newFrame->expand( $text )
			 KTHX
		 KTHX
		 IZ $isLocalObj && $nowiki
			 I HAS text IZ $frame->expand( $text, PPFrame::RECOVER_ORIG )
			 I HAS isLocalObj IZ false
		 KTHX

		 BTW Replace raw HTML by a placeholder
		 BTW Add a blank line preceding, to prevent it from mucking up
		 BTW immediately preceding headings
		 IZ $isHTML
			 I HAS text IZ "\n\n" . $this->insertStripItem( $text )
		 ORLY $nowiki && ( $this->ot['html'] || $this->ot['pre'] )
			 BTW Escape nowiki-style return values
			 I HAS text IZ wfEscapeWikiText( $text )
		 KTHX elseif ( is_string( $text )
			&& !$piece['lineStart'] 
			&& preg_match( '/^(?:{\\||:|;|#|\*)/', $text ) )
		{
			 BTW Bug 529: if the template begins with a table or block-level
			 BTW element, it should be treated as beginning a new line.
			 BTW This behaviour is somewhat controversial.
			 I HAS text IZ "\n" . $text
		 KTHX

		 IZ is_string( $text ) && !$this->incrementIncludeSize( 'post-expand', strlen( $text ) )
			 BTW Error, oversize inclusion
			 text IZ "[[$originalTitle]]" .
				 IM ON UR SPECIAL insertStripItem DOING '<!-- WARNING: template omitted, post-expand include size too large -->'
			 IM ON UR SPECIAL limitationWarn DOING 'post-expand-template-inclusion'
		 KTHX

		 IZ $isLocalObj
			 I HAS ret IZ BUCKET 'object' => $text );
		 NOWAI
			 I HAS ret IZ BUCKET 'text' => $text );
		 KTHX

		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $ret
	 KTHX

	 DO NOT WANT
	  NOT WANT Get the semi-parsed DOM representation of a template with a given title,
	  NOT WANT and its redirect destination title. Cached.
	  WANT
	 SO IM LIKE getTemplateDom WITH UR $title
		 I HAS cacheTitle IZ $title
		 I HAS titleText IZ $title->getPrefixedDBkey()

		 IZ isset( $this->mTplRedirCache[$titleText] )
			list( $ns, $dbk ) = $this->mTplRedirCache[$titleText];
			 I HAS title IZ Title::makeTitle( $ns, $dbk )
			 I HAS titleText IZ $title->getPrefixedDBkey()
		 KTHX
		 IZ isset( $this->mTplDomCache[$titleText] )
			 I FOUND MAH array( $this->mTplDomCache[$titleText], $title )
		 KTHX

		 BTW Cache miss, go to the database
		 IM ON UR list DOING $text, $title ) = $this->fetchTemplateAndTitle( $title

		 IZ $text === false
			$this->mTplDomCache[$titleText] = false;
			 I FOUND MAH array( false, $title )
		 KTHX

		 I HAS dom IZ $this->preprocessToDom( $text, self::PTD_FOR_INCLUSION )
		$this->mTplDomCache[ $titleText ] = $dom;

		 IZ !$title->equals( $cacheTitle )
			$this->mTplRedirCache[$cacheTitle->getPrefixedDBkey()] =
				 IM ON UR array DOING $title->getNamespace(),$cdb = $title->getDBkey()
		 KTHX

		 I FOUND MAH array( $dom, $title )
	 KTHX

	 DO NOT WANT
	  NOT WANT Fetch the unparsed text of a template and register a reference to it.
	  WANT
	 SO IM LIKE fetchTemplateAndTitle WITH UR $title
		 I HAS templateCb IZ $this->mOptions->getTemplateCallback()
		 I HAS stuff IZ call_user_func( $templateCb, $title, $this )
		 I HAS text IZ $stuff['text']
		 I HAS finalTitle IZ isset( $stuff['finalTitle'] ) ? $stuff['finalTitle'] : $title
		 IZ isset( $stuff['deps'] )
			foreach ( $stuff['deps'] as $dep ) {
				 IM ON UR SPECIAL mOutput->addTemplate DOING $dep['title'], $dep['page_id'], $dep['rev_id']
			 KTHX
		 KTHX
		 I FOUND MAH array( $text, $finalTitle )
	 KTHX

	 SO IM LIKE fetchTemplate WITH UR $title
		 I HAS rv IZ $this->fetchTemplateAndTitle( $title )
		 I FOUND MAH $rv[0]
	 KTHX

	 DO NOT WANT
	  NOT WANT Static function to get a template
	  NOT WANT Can be overridden via ParserOptions::setTemplateCallback().
	  WANT
	 SO IM ALWAYS LIKE statelessFetchTemplate WITH UR $title, $parser=false
		 I HAS text IZ $skip = false
		 I HAS finalTitle IZ $title
		 I HAS deps IZ EMPTY

		 BTW Loop to fetch the article, with up to 1 redirect
		for ( $i = 0; $i < 2 && is_object( $title ); $i++ ) {
			 BTW Give extensions a chance to select the revision instead
			 I HAS id IZ false # Assume current
			 IM ON UR wfRunHooks DOING 'BeforeParserFetchTemplateAndtitle', array( $parser, &$title, &$skip, &$id )

			 IZ $skip
				 I HAS text IZ false
				$deps[] = array(
					'title' => $title,
					'page_id' => $title->getArticleID(),
					'rev_id' => null );
				break;
			 KTHX
			 I HAS rev IZ $id ? Revision::newFromId( $id ) : Revision::newFromTitle( $title )
			 I HAS rev_id IZ $rev ? $rev->getId() : 0
			 BTW If there is no current revision, there is no page
			 IZ $id === false && !$rev
				 I HAS linkCache IZ LinkCache::singleton()
				$linkCache->addBadLinkObj( $title );
			 KTHX

			$deps[] = array(
				'title' => $title,
				'page_id' => $title->getArticleID(),
				'rev_id' => $rev_id );

			 IZ $rev
				 I HAS text IZ $rev->getText()
			 ORLY $title->getNamespace() == NS_MEDIAWIKI
				 I HAS UR $wgContLang ON UR INTERNETS
				 I HAS message IZ $wgContLang->lcfirst( $title->getText() )
				 I HAS text IZ wfMsgForContentNoTrans( $message )
				 IZ wfEmptyMsg( $message, $text )
					 I HAS text IZ false
					break;
				 KTHX
			 NOWAI
				break;
			 KTHX
			 IZ $text === false
				break;
			 KTHX
			 BTW Redirect?
			 I HAS finalTitle IZ $title
			 I HAS title IZ Title::newFromRedirect( $text )
		 KTHX
		return array(
			'text' => $text,
			'finalTitle' => $finalTitle,
			'deps' => $deps );
	 KTHX

	 DO NOT WANT
	  NOT WANT Transclude an interwiki link.
	  WANT
	 SO IM LIKE interwikiTransclude WITH UR $title, $action
		 I HAS UR $wgEnableScaryTranscluding ON UR INTERNETS

		 IZ !$wgEnableScaryTranscluding
			 I FOUND MAH wfMsg('scarytranscludedisabled')
		 KTHX

		 I HAS url IZ $title->getFullUrl( "action=$action" )

		 IZ strlen( $url ) > 255
			 I FOUND MAH wfMsg( 'scarytranscludetoolong' )
		 KTHX
		 I FOUND MAH $this->fetchScaryTemplateMaybeFromCache( $url )
	 KTHX

	 SO IM LIKE fetchScaryTemplateMaybeFromCache WITH UR $url
		 I HAS UR $wgTranscludeCacheExpiry ON UR INTERNETS
		 I HAS dbr IZ wfGetDB( DB_SLAVE )
		 I HAS tsCond IZ $dbr->timestamp( time() - $wgTranscludeCacheExpiry )
		 obj IZ $dbr->selectRow( 'transcache', array('tc_time', 'tc_contents' ),
				 IM ON UR array DOING 'tc_url' => $url, "tc_time >= " . $dbr->addQuotes( $tsCond ) )
		 IZ $obj
			 I FOUND MAH $obj->tc_contents
		 KTHX

		 I HAS text IZ Http::get( $url )
		 IZ !$text
			 I FOUND MAH wfMsg( 'scarytranscludefailed', $url )
		 KTHX

		 I HAS dbw IZ wfGetDB( DB_MASTER )
		$dbw->replace( 'transcache', array('tc_url'), array(
			'tc_url' => $url,
			'tc_time' => $dbw->timestamp( time() ),
			'tc_contents' => $text)
		 BUCKET
		 I FOUND MAH $text
	 KTHX


	 DO NOT WANT
	  NOT WANT Triple brace replacement -- used for template arguments
	  NOT WANT @private
	  WANT
	 SO IM LIKE argSubstitution WITH UR $piece, $frame
		 IM ON UR wfProfileIn DOING __METHOD__

		 I HAS error IZ false
		 I HAS parts IZ $piece['parts']
		 I HAS nameWithSpaces IZ $frame->expand( $piece['title'] )
		 I HAS argName IZ trim( $nameWithSpaces )
		 I HAS object IZ false
		 I HAS text IZ $frame->getArgument( $argName )
		if (  $text === false && $parts->getLength() > 0
		  && (
		    $this->ot['html']
		    || $this->ot['pre']
		    || ( $this->ot['wiki'] && $frame->isTemplate() )
		  )
		) {
			 BTW No match in frame, use the supplied default
			 I HAS object IZ $parts->item( 0 )->getChildren()
		 KTHX
		 IZ !$this->incrementIncludeSize( 'arg', strlen( $text ) )
			 I HAS error IZ '<!-- WARNING: argument omitted, expansion size too large -->'
			 IM ON UR SPECIAL limitationWarn DOING 'post-expand-template-argument'
		 KTHX

		 IZ $text === false && $object === false
			 BTW No match anywhere
			 I HAS object IZ $frame->virtualBracketedImplode( '{{{', '|', '}}}', $nameWithSpaces, $parts )
		 KTHX
		 IZ $error !== false
			 text HAS MOAR $error;
		 KTHX
		 IZ $object !== false
			 I HAS ret IZ BUCKET 'object' => $object );
		 NOWAI
			 I HAS ret IZ BUCKET 'text' => $text );
		 KTHX

		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $ret
	 KTHX

	 DO NOT WANT
	  NOT WANT Return the text to be used for a given extension tag.
	  NOT WANT This is the ghost of strip().
	  NOT WANT 
	  NOT WANT @param array $params Associative array of parameters:
	  NOT WANT     name       PPNode for the tag name
	  NOT WANT     attr       PPNode for unparsed text where tag attributes are thought to be
	  NOT WANT     attributes Optional associative array of parsed attributes
	  NOT WANT     inner      Contents of extension element
	  NOT WANT     noClose    Original text did not have a close tag
	  NOT WANT @param PPFrame $frame
	  WANT
	 SO IM LIKE extensionSubstitution WITH UR $params, $frame
		 I HAS UR $wgRawHtml, $wgContLang ON UR INTERNETS

		 I HAS name IZ $frame->expand( $params['name'] )
		 I HAS attrText IZ !isset( $params['attr'] ) ? null : $frame->expand( $params['attr'] )
		 I HAS content IZ !isset( $params['inner'] ) ? null : $frame->expand( $params['inner'] )
		 I HAS marker IZ "{$this->mUniqPrefix}-$name-" . sprintf( '%08X', $this->mMarkerIndex++ ) . self::MARKER_SUFFIX

		 isFunctionTag IZ isset( $this->mFunctionTagHooks[strtolower($name)] ) &&
			( $this->ot['html'] || $this->ot['pre'] );
		 IZ $isFunctionTag
			 I HAS markerType IZ 'none'
		 NOWAI
			 I HAS markerType IZ 'general'
		 KTHX
		 IZ $this->ot['html'] || $isFunctionTag
			 I HAS name IZ strtolower( $name )
			 I HAS attributes IZ Sanitizer::decodeTagAttributes( $attrText )
			 IZ isset( $params['attributes'] )
				 I HAS attributes IZ $attributes + $params['attributes']
			 KTHX

			 IZ isset( $this->mTagHooks[$name] )
				 BTW Workaround for PHP bug 35229 and similar
				 IZ !is_callable( $this->mTagHooks[$name] )
					throw new MWException( "Tag hook for $name is not callable\n" );
				 KTHX
				 output IZ call_user_func_array( $this->mTagHooks[$name],
					 IM ON UR array DOING $content, $attributes, $this, $frame )
			 ORLY isset( $this->mFunctionTagHooks[$name] )
				list( $callback, $flags ) = $this->mFunctionTagHooks[$name];
				 IZ !is_callable( $callback )
					throw new MWException( "Tag hook for $name is not callable\n" );
				 KTHX

				 I HAS output IZ call_user_func_array( $callback, array( &$this, $frame, $content, $attributes ) )
			 NOWAI
				 output IZ '<span class="error">Invalid tag extension name: ' .
					htmlspecialchars( $name ) . '</span>';
			 KTHX

			 IZ is_array( $output )
				 BTW Extract flags to local scope (to override $markerType)
				 I HAS flags IZ $output
				 I HAS output IZ $flags[0]
				 IM ON UR unset DOING $flags[0]
				 IM ON UR extract DOING $flags
			 KTHX
		 NOWAI
			 IZ is_null( $attrText )
				 I HAS attrText IZ ''
			 KTHX
			 IZ isset( $params['attributes'] )
				foreach ( $params['attributes'] as $attrName => $attrValue ) {
					 attrText HAS MOAR ' ' . htmlspecialchars( $attrName ) . '="' .
						htmlspecialchars( $attrValue ) . '"';
				 KTHX
			 KTHX
			 IZ $content === null
				 I HAS output IZ "<$name$attrText/>"
			 NOWAI
				 I HAS close IZ is_null( $params['close'] ) ? '' : $frame->expand( $params['close'] )
				 I HAS output IZ "<$name$attrText>$content$close"
			 KTHX
		 KTHX

		 IZ $markerType === 'none'
			 I FOUND MAH $output
		 ORLY $markerType === 'nowiki'
			 IM ON UR SPECIAL mStripState->nowiki->setPair DOING $marker, $output
		 ORLY $markerType === 'general'
			 IM ON UR SPECIAL mStripState->general->setPair DOING $marker, $output
		 NOWAI
			throw new MWException( __METHOD__.': invalid marker type' );
		 KTHX
		 I FOUND MAH $marker
	 KTHX

	 DO NOT WANT
	  NOT WANT Increment an include size counter
	  NOT WANT 
	  NOT WANT @param string $type The type of expansion
	  NOT WANT @param integer $size The size of the text
	  NOT WANT @return boolean False if this inclusion would take it over the maximum, true otherwise
	  WANT
	 SO IM LIKE incrementIncludeSize WITH UR $type, $size
		 IZ $this->mIncludeSizes[$type] + $size > $this->mOptions->getMaxIncludeSize( $type )
			 I FOUND MAH false
		 NOWAI
			$this->mIncludeSizes[$type] += $size;
			 I FOUND MAH true
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Increment the expensive function count
	  NOT WANT 
	  NOT WANT @return boolean False if the limit has been exceeded
	  WANT
	 SO IM LIKE incrementExpensiveFunctionCount
		 I HAS UR $wgExpensiveParserFunctionLimit ON UR INTERNETS
		$this->mExpensiveFunctionCount++;
		 IZ $this->mExpensiveFunctionCount <= $wgExpensiveParserFunctionLimit
			 I FOUND MAH true
		 KTHX
		 I FOUND MAH false
	 KTHX

	 DO NOT WANT
	  NOT WANT Strip double-underscore items like __NOGALLERY__ and __NOTOC__
	  NOT WANT Fills $this->mDoubleUnderscores, returns the modified text
	  WANT
	 SO IM LIKE doDoubleUnderscore WITH UR $text
		 IM ON UR wfProfileIn DOING __METHOD__

		 BTW The position of __TOC__ needs to be recorded
		 I HAS mw IZ MagicWord::get( 'toc' )
		 IZ $mw->match( $text )
			 UR SPECIAL mShowToc IZ true;
			 UR SPECIAL mForceTocPosition IZ true;

			 BTW Set a placeholder. At the end we'll fill it in with the TOC.
			 I HAS text IZ $mw->replace( '<!--MWTOC-->', $text, 1 )

			 BTW Only keep the first one.
			 I HAS text IZ $mw->replace( '', $text )
		 KTHX

		 BTW Now match and remove the rest of them
		 I HAS mwa IZ MagicWord::getDoubleUnderscoreArray()
		 UR SPECIAL mDoubleUnderscores IZ $mwa->matchAndRemove( $text );

		 IZ isset( $this->mDoubleUnderscores['nogallery'] )
			 UR SPECIAL mOutput->mNoGallery IZ true;
		 KTHX
		 IZ isset( $this->mDoubleUnderscores['notoc'] ) && !$this->mForceTocPosition
			 UR SPECIAL mShowToc IZ false;
		 KTHX
		 IZ isset( $this->mDoubleUnderscores['hiddencat'] ) && $this->mTitle->getNamespace() == NS_CATEGORY
			 IM ON UR SPECIAL mOutput->setProperty DOING 'hiddencat', 'y'
			 IM ON UR SPECIAL addTrackingCategory DOING 'hidden-category-category'
		 KTHX
		 BTW (bug 8068) Allow control over whether robots index a page.
		 BTW 
		 BTW FIXME (bug 14899): __INDEX__ always overrides __NOINDEX__ here!  This
		 BTW is not desirable, the last one on the page should win.
		 IZ isset( $this->mDoubleUnderscores['noindex'] ) && $this->mTitle->canUseNoindex()
			 IM ON UR SPECIAL mOutput->setIndexPolicy DOING 'noindex'
			 IM ON UR SPECIAL addTrackingCategory DOING 'noindex-category'
		 KTHX
		 IZ isset( $this->mDoubleUnderscores['index'] ) && $this->mTitle->canUseNoindex()
			 IM ON UR SPECIAL mOutput->setIndexPolicy DOING 'index'
			 IM ON UR SPECIAL addTrackingCategory DOING 'index-category'
		 KTHX

		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Add a tracking category, getting the title from a system message,
	  NOT WANT or print a debug message if the title is invalid.
	  NOT WANT @param $msg String message key
	  NOT WANT @return Bool whether the addition was successful
	  WANT
	 SO IM LIKE addTrackingCategory WITH UR $msg
		 I HAS cat IZ wfMsgForContent( $msg )

		 BTW Allow tracking categories to be disabled by setting them to "-"
		 IZ $cat === '-'
			 I FOUND MAH false
		 KTHX

		 I HAS containerCategory IZ Title::makeTitleSafe( NS_CATEGORY, $cat )
		 IZ $containerCategory
			 IM ON UR SPECIAL mOutput->addCategory DOING $containerCategory->getDBkey(), $this->getDefaultSort()
			 I FOUND MAH true
		 NOWAI
			 IM ON UR wfDebug DOING __METHOD__.": [[MediaWiki:$msg]] is not a valid title!\n"
			 I FOUND MAH false
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT This function accomplishes several tasks:
	  NOT WANT 1) Auto-number headings if that option is enabled
	  NOT WANT 2) Add an [edit] link to sections for users who have enabled the option and can edit the page
	  NOT WANT 3) Add a Table of contents on the top for users who have enabled the option
	  NOT WANT 4) Auto-anchor headings
	  NOT WANT 
	  NOT WANT It loops through all headlines, collects the necessary data, then splits up the
	  NOT WANT string and re-inserts the newly formatted headlines.
	  NOT WANT 
	  NOT WANT @param string $text
	  NOT WANT @param string $origText Original, untouched wikitext
	  NOT WANT @param boolean $isMain
	  NOT WANT @private
	  WANT
	 SO IM LIKE formatHeadings WITH UR $text, $origText, $isMain=true
		 I HAS UR $wgMaxTocLevel, $wgContLang, $wgHtml5, $wgExperimentalHtmlIds ON UR INTERNETS

		 I HAS doNumberHeadings IZ $this->mOptions->getNumberHeadings()
		 I HAS showEditLink IZ $this->mOptions->getEditSection()

		 BTW Do not call quickUserCan unless necessary
		 IZ $showEditLink && !$this->mTitle->quickUserCan( 'edit' )
			 I HAS showEditLink IZ 0
		 KTHX

		 BTW Inhibit editsection links if requested in the page
		 IZ isset( $this->mDoubleUnderscores['noeditsection'] )  || $this->mOptions->getIsPrintable()
			 I HAS showEditLink IZ 0
		 KTHX

		 BTW Get all headlines for numbering them and adding funky stuff like [edit]
		 BTW links - this is for later, but we need the number of headlines right now
		 I HAS matches IZ EMPTY
		 I HAS numMatches IZ preg_match_all( '/<H(?P<level>[1-6])(?P<attrib>.*?'.'>)(?P<header>.*?)<\/H[1-6] *>/i', $text, $matches )

		 BTW if there are fewer than 4 headlines in the article, do not show TOC
		 BTW unless it's been explicitly enabled.
		 enoughToc IZ $this->mShowToc &&
			( ( $numMatches >= 4 ) || $this->mForceTocPosition );

		 BTW Allow user to stipulate that a page should have a "new section"
		 BTW link added via __NEWSECTIONLINK__
		 IZ isset( $this->mDoubleUnderscores['newsectionlink'] )
			 IM ON UR SPECIAL mOutput->setNewSection DOING true
		 KTHX

		 BTW Allow user to remove the "new section"
		 BTW link via __NONEWSECTIONLINK__
		 IZ isset( $this->mDoubleUnderscores['nonewsectionlink'] )
			 IM ON UR SPECIAL mOutput->hideNewSection DOING true
		 KTHX

		 BTW if the string __FORCETOC__ (not case-sensitive) occurs in the HTML,
		 BTW override above conditions and always show TOC above first header
		 IZ isset( $this->mDoubleUnderscores['forcetoc'] )
			 UR SPECIAL mShowToc IZ true;
			 I HAS enoughToc IZ true
		 KTHX

		 BTW We need this to perform operations on the HTML
		 I HAS sk IZ $this->mOptions->getSkin()

		 BTW headline counter
		 I HAS headlineCount IZ 0
		 I HAS numVisible IZ 0

		 BTW Ugh .. the TOC should have neat indentation levels which can be
		 BTW passed to the skin functions. These are determined here
		 I HAS toc IZ ''
		 I HAS full IZ ''
		 I HAS head IZ EMPTY
		 I HAS sublevelCount IZ EMPTY
		 I HAS levelCount IZ EMPTY
		 I HAS toclevel IZ 0
		 I HAS level IZ 0
		 I HAS prevlevel IZ 0
		 I HAS toclevel IZ 0
		 I HAS prevtoclevel IZ 0
		 I HAS markerRegex IZ "{$this->mUniqPrefix}-h-(\d+)-" . self::MARKER_SUFFIX
		 I HAS baseTitleText IZ $this->mTitle->getPrefixedDBkey()
		 I HAS oldType IZ $this->mOutputType
		 IM ON UR SPECIAL setOutputType DOING self::OT_WIKI
		 I HAS frame IZ $this->getPreprocessor()->newFrame()
		 I HAS root IZ $this->preprocessToDom( $origText )
		 I HAS node IZ $root->getFirstChild()
		 I HAS byteOffset IZ 0
		 I HAS tocraw IZ EMPTY

		foreach ( $matches[3] as $headline ) {
			 I HAS isTemplate IZ false
			 I HAS titleText IZ false
			 I HAS sectionIndex IZ false
			 I HAS numbering IZ ''
			 I HAS markerMatches IZ EMPTY
			 IZ preg_match("/^$markerRegex/", $headline, $markerMatches )
				 I HAS serial IZ $markerMatches[1]
				list( $titleText, $sectionIndex ) = $this->mHeadings[$serial];
				 I HAS isTemplate IZ ( $titleText != $baseTitleText )
				 I HAS headline IZ preg_replace( "/^$markerRegex/", "", $headline )
			 KTHX

			 IZ $toclevel
				 I HAS prevlevel IZ $level
				 I HAS prevtoclevel IZ $toclevel
			 KTHX
			 I HAS level IZ $matches[1][$headlineCount]

			 IZ $level > $prevlevel
				 BTW Increase TOC level
				$toclevel++;
				$sublevelCount[$toclevel] = 0;
				 IZ $toclevel<$wgMaxTocLevel
					 I HAS prevtoclevel IZ $toclevel
					 toc HAS MOAR $sk->tocIndent();
					$numVisible++;
				 KTHX
			 ORLY $level < $prevlevel && $toclevel > 1
				 BTW Decrease TOC level, find level to jump to

				for ( $i = $toclevel; $i > 0; $i-- ) {
					 IZ $levelCount[$i] == $level
						 BTW Found last matching level
						 I HAS toclevel IZ $i
						break;
					 ORLY $levelCount[$i] < $level
						 BTW Found first matching level below current level
						 I HAS toclevel IZ $i + 1
						break;
					 KTHX
				 KTHX
				 IZ $i == 0
					 I HAS toclevel IZ 1
				 KTHX
				 IZ $toclevel<$wgMaxTocLevel
					 IZ $prevtoclevel < $wgMaxTocLevel
						 BTW Unindent only if the previous toc level was shown :p
						 toc HAS MOAR $sk->tocUnindent( $prevtoclevel - $toclevel );
						 I HAS prevtoclevel IZ $toclevel
					 NOWAI
						 toc HAS MOAR $sk->tocLineEnd();
					 KTHX
				 KTHX
			 NOWAI
				 BTW No change in level, end TOC line
				 IZ $toclevel<$wgMaxTocLevel
					 toc HAS MOAR $sk->tocLineEnd();
				 KTHX
			 KTHX

			$levelCount[$toclevel] = $level;

			 BTW count number of headlines for each level
			@$sublevelCount[$toclevel]++;
			 I HAS dot IZ 0
			for( $i = 1; $i <= $toclevel; $i++ ) {
				 IZ !empty( $sublevelCount[$i] )
					 IZ $dot
						 numbering HAS MOAR '.';
					 KTHX
					 numbering HAS MOAR $wgContLang->formatNum( $sublevelCount[$i] );
					 I HAS dot IZ 1
				 KTHX
			 KTHX

			 BTW The safe header is a version of the header text safe to use for links
			 BTW Avoid insertion of weird stuff like <math> by expanding the relevant sections
			 I HAS safeHeadline IZ $this->mStripState->unstripBoth( $headline )

			 BTW Remove link placeholders by the link text.
			 BTW     <!--LINK number-->
			 BTW turns into
			 BTW     link text with suffix
			 I HAS safeHeadline IZ $this->replaceLinkHoldersText( $safeHeadline )

			 BTW Strip out HTML (other than plain <sup> and <sub>: bug 8393)
			 tocline IZ preg_replace(
				array( '#<(?!/?(sup|sub)).*?'.'>#', '#<(/?(sup|sub)).*?'.'>#' ),
				array( '',                          '<$1>' ),
				$safeHeadline
			 BUCKET
			 I HAS tocline IZ trim( $tocline )

			 BTW For the anchor, strip out HTML-y stuff period
			 I HAS safeHeadline IZ preg_replace( '/<.*?'.'>/', '', $safeHeadline )
			 I HAS safeHeadline IZ preg_replace( '/[ _]+/', ' ', $safeHeadline )
			 I HAS safeHeadline IZ trim( $safeHeadline )

			 BTW Save headline for section edit hint before it's escaped
			 I HAS headlineHint IZ $safeHeadline

			 IZ $wgHtml5 && $wgExperimentalHtmlIds
				 BTW For reverse compatibility, provide an id that's
				 BTW HTML4-compatible, like we used to.
				 BTW 
				 BTW It may be worth noting, academically, that it's possible for
				 BTW the legacy anchor to conflict with a non-legacy headline
				 BTW anchor on the page.  In this case likely the "correct" thing
				 BTW would be to either drop the legacy anchors or make sure
				 BTW they're numbered first.  However, this would require people
				 BTW to type in section names like "abc_.D7.93.D7.90.D7.A4"
				 BTW manually, so let's not bother worrying about it.
				 legacyHeadline IZ Sanitizer::escapeId( $safeHeadline,
					 IM ON UR array DOING 'noninitial', 'legacy' )
				 I HAS safeHeadline IZ Sanitizer::escapeId( $safeHeadline )

				 IZ $legacyHeadline == $safeHeadline
					 BTW No reason to have both (in fact, we can't)
					 I HAS legacyHeadline IZ false
				 KTHX
			 NOWAI
				 I HAS legacyHeadline IZ false
				 safeHeadline IZ Sanitizer::escapeId( $safeHeadline,
					'noninitial' );
			 KTHX

			 BTW HTML names must be case-insensitively unique (bug 10721).  FIXME:
			 BTW Does this apply to Unicode characters?  Because we aren't
			 BTW handling those here.
			 I HAS arrayKey IZ strtolower( $safeHeadline )
			 IZ $legacyHeadline === false
				 I HAS legacyArrayKey IZ false
			 NOWAI
				 I HAS legacyArrayKey IZ strtolower( $legacyHeadline )
			 KTHX

			 BTW count how many in assoc. array so we can track dupes in anchors
			 IZ isset( $refers[$arrayKey] )
				$refers[$arrayKey]++;
			 NOWAI
				$refers[$arrayKey] = 1;
			 KTHX
			 IZ isset( $refers[$legacyArrayKey] )
				$refers[$legacyArrayKey]++;
			 NOWAI
				$refers[$legacyArrayKey] = 1;
			 KTHX

			 BTW Don't number the heading if it is the only one (looks silly)
			if ( $doNumberHeadings && count( $matches[3] ) > 1) {
				 BTW the two are different if the line contains a link
				 I HAS headline IZ $numbering . ' ' . $headline
			 KTHX

			 BTW Create the anchor for linking from the TOC to the section
			 I HAS anchor IZ $safeHeadline
			 I HAS legacyAnchor IZ $legacyHeadline
			 IZ $refers[$arrayKey] > 1
				 anchor HAS MOAR '_' . $refers[$arrayKey];
			 KTHX
			 IZ $legacyHeadline !== false && $refers[$legacyArrayKey] > 1
				 legacyAnchor HAS MOAR '_' . $refers[$legacyArrayKey];
			 KTHX
			 IZ $enoughToc && ( !isset( $wgMaxTocLevel ) || $toclevel < $wgMaxTocLevel )
				 toc HAS MOAR $sk->tocLine( $anchor, $tocline,
					$numbering, $toclevel, ( $isTemplate ? false : $sectionIndex ) );
			 KTHX

			 BTW Add the section to the section tree
			 BTW Find the DOM node for this header
			 STEALIN UR $node && !$isTemplate
				 IZ $node->getName() === 'h'
					 I HAS bits IZ $node->splitHeading()
					if ( $bits['i'] == $sectionIndex )
						break;
				 KTHX
				$byteOffset += mb_strlen( $this->mStripState->unstripBoth(
					$frame->expand( $node, PPFrame::RECOVER_ORIG ) ) );
				 I HAS node IZ $node->getNextSibling()
			 KTHX
			$tocraw[] = array(
				'toclevel' => $toclevel,
				'level' => $level,
				'line' => $tocline,
				'number' => $numbering,
				'index' => ( $isTemplate ? 'T-' : '' ) . $sectionIndex,
				'fromtitle' => $titleText,
				'byteoffset' => ( $isTemplate ? null : $byteOffset ),
				'anchor' => $anchor,
			 BUCKET

			 BTW give headline the correct <h#> tag
			 IZ $showEditLink && $sectionIndex !== false
				 IZ $isTemplate
					 BTW Put a T flag in the section identifier, to indicate to extractSections()
					 BTW that sections inside <includeonly> should be counted.
					 I HAS editlink IZ $sk->doEditSectionLink( Title::newFromText( $titleText ), "T-$sectionIndex" )
				 NOWAI
					 I HAS editlink IZ $sk->doEditSectionLink( $this->mTitle, $sectionIndex, $headlineHint )
				 KTHX
			 NOWAI
				 I HAS editlink IZ ''
			 KTHX
			$head[$headlineCount] = $sk->makeHeadline( $level,
				$matches['attrib'][$headlineCount], $anchor, $headline,
				$editlink, $legacyAnchor );

			$headlineCount++;
		 KTHX

		 IM ON UR SPECIAL setOutputType DOING $oldType

		 BTW Never ever show TOC if no headers
		 IZ $numVisible < 1
			 I HAS enoughToc IZ false
		 KTHX

		 IZ $enoughToc
			 IZ $prevtoclevel > 0 && $prevtoclevel < $wgMaxTocLevel
				 toc HAS MOAR $sk->tocUnindent( $prevtoclevel - 1 );
			 KTHX
			 I HAS toc IZ $sk->tocList( $toc )
			 IM ON UR SPECIAL mOutput->setTOCHTML DOING $toc
		 KTHX

		 IZ $isMain
			 IM ON UR SPECIAL mOutput->setSections DOING $tocraw
		 KTHX

		 BTW split up and insert constructed headlines

		 I HAS blocks IZ preg_split( '/<H[1-6].*?' . '>.*?<\/H[1-6]>/i', $text )
		 I HAS i IZ 0

		 IM IN UR blocks ITZA block
			 IZ $showEditLink && $headlineCount > 0 && $i == 0 && $block !== "\n"
				 BTW This is the [edit] link that appears for the top block of text when
				 BTW section editing is enabled

				 BTW Disabled because it broke block formatting
				 BTW For example, a bullet point in the top line
				 BTW $full .= $sk->editSectionLink(0);
			 KTHX
			 full HAS MOAR $block;
			 IZ $enoughToc && !$i && $isMain && !$this->mForceTocPosition
				 BTW Top anchor now in skin
				 I HAS full IZ $full.$toc
			 KTHX

			 IZ !empty( $head[$i] )
				 full HAS MOAR $head[$i];
			 KTHX
			$i++;
		 KTHX
		 IZ $this->mForceTocPosition
			 I FOUND MAH str_replace( '<!--MWTOC-->', $toc, $full )
		 NOWAI
			 I FOUND MAH $full
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Merge $tree2 into $tree1 by replacing the section with index
	  NOT WANT $section in $tree1 and its descendants with the sections in $tree2.
	  NOT WANT Note that in the returned section tree, only the 'index' and
	  NOT WANT 'byteoffset' fields are guaranteed to be correct.
	  NOT WANT @param $tree1 array Section tree from ParserOutput::getSectons()
	 NOT WANT  @param $tree2 array Section tree
	  NOT WANT @param $section int Section index
	  NOT WANT @param $title Title Title both section trees come from
	  NOT WANT @param $len2 int Length of the original wikitext for $tree2
	  NOT WANT @return array Merged section tree
	  WANT
	 SO IM ALWAYS LIKE mergeSectionTrees WITH UR $tree1, $tree2, $section, $title, $len2
		 I HAS UR $wgContLang ON UR INTERNETS
		 I HAS newTree IZ EMPTY
		 I HAS targetLevel IZ false
		 I HAS merged IZ false
		 I HAS lastLevel IZ 1
		 I HAS nextIndex IZ 1
		 I HAS numbering IZ BUCKET 0 );
		 I HAS titletext IZ $title->getPrefixedDBkey()
		 IM IN UR tree1 ITZA s
			 IZ $targetLevel !== false
				 IZ $s['level'] <= $targetLevel
					 BTW We've skipped enough
					 I HAS targetLevel IZ false
				 NOWAI
					continue;
				 KTHX
			 KTHX
			if ( $s['index'] != $section ||
					$s['fromtitle'] != $titletext ) {
				self::incrementNumbering( $numbering,
					$s['toclevel'], $lastLevel );

				 BTW Rewrite index, byteoffset and number
				 IZ $s['fromtitle'] == $titletext
					$s['index'] = $nextIndex++;
					 IZ $merged
						$s['byteoffset'] += $len2;
					 KTHX
				 KTHX
				$s['number']  = implode( '.', array_map(
					array( $wgContLang, 'formatnum' ),
					$numbering ) );
				 I HAS lastLevel IZ $s['toclevel']
				$newTree[] = $s;
			 NOWAI
				 BTW We're at $section
				 BTW Insert sections from $tree2 here
				 IM IN UR tree2 ITZA s2
					 BTW Rewrite the fields in $s2
					 BTW before inserting it
					$s2['toclevel'] += $s['toclevel'] - 1;
					$s2['level'] += $s['level'] - 1;
					$s2['index'] = $nextIndex++;
					$s2['byteoffset'] += $s['byteoffset'];

					self::incrementNumbering( $numbering,
						$s2['toclevel'], $lastLevel );
					$s2['number']  = implode( '.', array_map(
						array( $wgContLang, 'formatnum' ),
						$numbering ) );
					 I HAS lastLevel IZ $s2['toclevel']
					$newTree[] = $s2;
				 KTHX
				 BTW Skip all descendants of $section in $tree1
				 I HAS targetLevel IZ $s['level']
				 I HAS merged IZ true
			 KTHX
		 KTHX
		 I FOUND MAH $newTree
	 KTHX

	 DO NOT WANT
	  NOT WANT Increment a section number. Helper function for mergeSectionTrees()
	  NOT WANT @param $number array Array representing a section number
	  NOT WANT @param $level int Current TOC level (depth)
	  NOT WANT @param $lastLevel int Level of previous TOC entry
	  WANT
	 SO IM ALWAYS LIKE incrementNumbering WITH UR &$number, $level, $lastLevel
		 IZ $level > $lastLevel
			$number[$level - 1] = 1;
		 ORLY $level < $lastLevel
			foreach ( $number as $key => $unused )
				 IZ $key >= $level
					 IM ON UR unset DOING $number[$key]
				 KTHX
			$number[$level - 1]++;
		 NOWAI
			$number[$level - 1]++;
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Transform wiki markup when saving a page by doing \r\n -> \n
	  NOT WANT conversion, substitting signatures, {{subst:}} templates, etc.
	  NOT WANT 
	  NOT WANT @param string $text the text to transform
	  NOT WANT @param Title &$title the Title object for the current article
	  NOT WANT @param User $user the User object describing the current user
	  NOT WANT @param ParserOptions $options parsing options
	  NOT WANT @param bool $clearState whether to clear the parser state first
	  NOT WANT @return string the altered wiki markup
	  NOT WANT @public
	  WANT
	 SO IM LIKE preSaveTransform WITH UR $text, Title $title, $user, $options, $clearState = true
		 UR SPECIAL mOptions IZ $options;
		 IM ON UR SPECIAL setTitle DOING $title
		 IM ON UR SPECIAL setOutputType DOING self::OT_WIKI

		 IZ $clearState
			 IM ON UR SPECIAL clearState
		 KTHX

		 I HAS pairs IZ BUCKET
			"\r\n" => "\n",
		 BUCKET
		 I HAS text IZ str_replace( array_keys( $pairs ), array_values( $pairs ), $text )
		 I HAS text IZ $this->pstPass2( $text, $user )
		 I HAS text IZ $this->mStripState->unstripBoth( $text )
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Pre-save transform helper function
	  NOT WANT @private
	  WANT
	 SO IM LIKE pstPass2 WITH UR $text, $user
		 I HAS UR $wgContLang, $wgLocaltimezone ON UR INTERNETS

		 BTW Note: This is the timestamp saved as hardcoded wikitext to
		 BTW the database, we use $wgContLang here in order to give
		 BTW everyone the same signature and use the default one rather
		 BTW than the one selected in each user's preferences.
		 BTW (see also bug 12815)
		 I HAS ts IZ $this->mOptions->getTimestamp()
		 IZ isset( $wgLocaltimezone )
			 I HAS tz IZ $wgLocaltimezone
		 NOWAI
			 I HAS tz IZ date_default_timezone_get()
		 KTHX

		 I HAS unixts IZ wfTimestamp( TS_UNIX, $ts )
		 I HAS oldtz IZ date_default_timezone_get()
		 IM ON UR date_default_timezone_set DOING $tz
		 I HAS ts IZ date( 'YmdHis', $unixts )
		 I HAS tzMsg IZ date( 'T', $unixts )  # might vary on DST changeover!

		 BTW Allow translation of timezones trough wiki. date() can return
		 BTW whatever crap the system uses, localised or not, so we cannot
		 BTW ship premade translations.
		 I HAS key IZ 'timezone-' . strtolower( trim( $tzMsg ) )
		 I HAS value IZ wfMsgForContent( $key )
		 IZ !wfEmptyMsg( $key, $value )
			 I HAS tzMsg IZ $value
		 KTHX

		 IM ON UR date_default_timezone_set DOING $oldtz

		 I HAS d IZ $wgContLang->timeanddate( $ts, false, false ) . " ($tzMsg)"

		 BTW Variable replacement
		 BTW Because mOutputType is OT_WIKI, this will only process {{subst:xxx}} type tags
		 I HAS text IZ $this->replaceVariables( $text )

		 BTW Signatures
		 I HAS sigText IZ $this->getUserSig( $user )
		 text IZ strtr( $text, array(
			'~~~~~' => $d,
			'~~~~' => "$sigText $d",
			'~~~' => $sigText
		) );

		 BTW Context links: [[|name]] and [[name (context)|]]
		 I HAS UR $wgLegalTitleChars ON UR INTERNETS
		 I HAS tc IZ "[$wgLegalTitleChars]"
		 I HAS nc IZ '[ _0-9A-Za-z\x80-\xff-]' # Namespaces can use non-ascii!

		 I HAS p1 IZ "/\[\[(:?$nc+:|:|)($tc+?)( \\($tc+\\))\\|]]/"		# [[ns:page (context)|]]
		 I HAS p4 IZ "/\[\[(:?$nc+:|:|)($tc+?)(（$tc+）)\\|]]/"		# [[ns:page（context）|]]
		 I HAS p3 IZ "/\[\[(:?$nc+:|:|)($tc+?)( \\($tc+\\)|)(, $tc+|)\\|]]/"	# [[ns:page (context), context|]]
		 I HAS p2 IZ "/\[\[\\|($tc+)]]/"					# [[|page]]

		 BTW try $p1 first, to turn "[[A, B (C)|]]" into "[[A, B (C)|A, B]]"
		 I HAS text IZ preg_replace( $p1, '[[\\1\\2\\3|\\2]]', $text )
		 I HAS text IZ preg_replace( $p4, '[[\\1\\2\\3|\\2]]', $text )
		 I HAS text IZ preg_replace( $p3, '[[\\1\\2\\3\\4|\\2]]', $text )

		 I HAS t IZ $this->mTitle->getText()
		 I HAS m IZ EMPTY
		 IZ preg_match( "/^($nc+:|)$tc+?( \\($tc+\\))$/", $t, $m )
			 I HAS text IZ preg_replace( $p2, "[[$m[1]\\1$m[2]|\\1]]", $text )
		 ORLY preg_match( "/^($nc+:|)$tc+?(, $tc+|)$/", $t, $m ) && "$m[1]$m[2]" != ''
			 I HAS text IZ preg_replace( $p2, "[[$m[1]\\1$m[2]|\\1]]", $text )
		 NOWAI
			 BTW if there's no context, don't bother duplicating the title
			 I HAS text IZ preg_replace( $p2, '[[\\1]]', $text )
		 KTHX

		 BTW Trim trailing whitespace
		 I HAS text IZ rtrim( $text )

		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Fetch the user's signature text, if any, and normalize to
	  NOT WANT validated, ready-to-insert wikitext.
	  NOT WANT If you have pre-fetched the nickname or the fancySig option, you can
	  NOT WANT specify them here to save a database query.
	  NOT WANT 
	  NOT WANT @param User $user
	  NOT WANT @return string
	  WANT
	 SO IM LIKE getUserSig WITH UR &$user, $nickname = false, $fancySig = null
		 I HAS UR $wgMaxSigChars ON UR INTERNETS

		 I HAS username IZ $user->getName()

		 BTW If not given, retrieve from the user object.
		if ( $nickname === false )
			 I HAS nickname IZ $user->getOption( 'nickname' )

		 IZ is_null( $fancySig )
			 I HAS fancySig IZ $user->getBoolOption( 'fancysig' )
		 KTHX

		 I HAS nickname IZ $nickname == null ? $username : $nickname

		 IZ mb_strlen( $nickname ) > $wgMaxSigChars
			 I HAS nickname IZ $username
			 IM ON UR wfDebug DOING __METHOD__ . ": $username has overlong signature.\n"
		 ORLY $fancySig !== false
			 BTW Sig. might contain markup; validate this
			 IZ $this->validateSig( $nickname ) !== false
				 BTW Validated; clean up (if needed) and return it
				 I FOUND MAH $this->cleanSig( $nickname, true )
			 NOWAI
				 BTW Failed to validate; fall back to the default
				 I HAS nickname IZ $username
				 IM ON UR wfDebug DOING __METHOD__.": $username has bad XML tags in signature.\n"
			 KTHX
		 KTHX

		 BTW Make sure nickname doesnt get a sig in a sig
		 I HAS nickname IZ $this->cleanSigInSig( $nickname )

		 BTW If we're still here, make it a link to the user page
		 I HAS userText IZ wfEscapeWikiText( $username )
		 I HAS nickText IZ wfEscapeWikiText( $nickname )
		if ( $user->isAnon() )  {
			 I FOUND MAH wfMsgExt( 'signature-anon', array( 'content', 'parsemag' ), $userText, $nickText )
		 NOWAI
			 I FOUND MAH wfMsgExt( 'signature', array( 'content', 'parsemag' ), $userText, $nickText )
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Check that the user's signature contains no bad XML
	  NOT WANT 
	  NOT WANT @param string $text
	  NOT WANT @return mixed An expanded string, or false if invalid.
	  WANT
	 SO IM LIKE validateSig WITH UR $text
		 IM ON UR return DOING Xml::isWellFormedXmlFragment( $text ) ? $text : false
	 KTHX

	 DO NOT WANT
	  NOT WANT Clean up signature text
	  NOT WANT 
	  NOT WANT 1) Strip ~~~, ~~~~ and ~~~~~ out of signatures @see cleanSigInSig
	  NOT WANT 2) Substitute all transclusions
	  NOT WANT 
	  NOT WANT @param string $text
	  NOT WANT @param $parsing Whether we're cleaning (preferences save) or parsing
	  NOT WANT @return string Signature text
	  WANT
	 SO IM LIKE cleanSig WITH UR $text, $parsing = false
		 IZ !$parsing
			 I HAS UR $wgTitle ON UR INTERNETS
			 IM ON UR SPECIAL clearState
			 IM ON UR SPECIAL setTitle DOING $wgTitle
			 UR SPECIAL mOptions IZ new ParserOptions;
			 UR SPECIAL setOutputType IZ self::OT_PREPROCESS;
		 KTHX

		 BTW Option to disable this feature
		 IZ !$this->mOptions->getCleanSignatures()
			 I FOUND MAH $text
		 KTHX

		 BTW FIXME: regex doesn't respect extension tags or nowiki
		 BTW  => Move this logic to braceSubstitution()
		 I HAS substWord IZ MagicWord::get( 'subst' )
		 I HAS substRegex IZ '/\{\{(?!(?:' . $substWord->getBaseRegex() . '))/x' . $substWord->getRegexCase()
		 I HAS substText IZ '{{' . $substWord->getSynonym( 0 )

		 I HAS text IZ preg_replace( $substRegex, $substText, $text )
		 I HAS text IZ $this->cleanSigInSig( $text )
		 I HAS dom IZ $this->preprocessToDom( $text )
		 I HAS frame IZ $this->getPreprocessor()->newFrame()
		 I HAS text IZ $frame->expand( $dom )

		 IZ !$parsing
			 I HAS text IZ $this->mStripState->unstripBoth( $text )
		 KTHX

		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Strip ~~~, ~~~~ and ~~~~~ out of signatures
	  NOT WANT @param string $text
	  NOT WANT @return string Signature text with /~{3,5}/ removed
	  WANT
	 SO IM LIKE cleanSigInSig WITH UR $text
		 I HAS text IZ preg_replace( '/~{3,5}/', '', $text )
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Set up some variables which are usually set up in parse()
	  NOT WANT so that an external function can call some class members with confidence
	  NOT WANT @public
	  WANT
	 SO IM LIKE startExternalParse WITH UR &$title, $options, $outputType, $clearState = true
		 IM ON UR SPECIAL setTitle DOING $title
		 UR SPECIAL mOptions IZ $options;
		 IM ON UR SPECIAL setOutputType DOING $outputType
		 IZ $clearState
			 IM ON UR SPECIAL clearState
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Wrapper for preprocess()
	  NOT WANT 
	  NOT WANT @param string $text the text to preprocess
	  NOT WANT @param ParserOptions $options  options
	  NOT WANT @return string
	  NOT WANT @public
	  WANT
	 SO IM LIKE transformMsg WITH UR $text, $options
		 I HAS UR $wgTitle ON UR INTERNETS
		static $executing = false;

		 BTW Guard against infinite recursion
		 IZ $executing
			 I FOUND MAH $text
		 KTHX
		 I HAS executing IZ true

		 IM ON UR wfProfileIn DOING __METHOD__
		 I HAS text IZ $this->preprocess( $text, $wgTitle, $options )

		 I HAS executing IZ false
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT
	  NOT WANT Create an HTML-style tag, e.g. <yourtag>special text</yourtag>
	  NOT WANT The callback should have the following form:
	  NOT WANT    function myParserHook( $text, $params, &$parser ) { ... }
	  NOT WANT 
	  NOT WANT Transform and return $text. Use $parser for any required context, e.g. use
	  NOT WANT $parser->getTitle() and $parser->getOptions() not $wgTitle or $wgOut->mParserOptions
	  NOT WANT 
	  NOT WANT @public
	  NOT WANT 
	  NOT WANT @param mixed $tag The tag to use, e.g. 'hook' for <hook>
	  NOT WANT @param mixed $callback The callback function (and object) to use for the tag
	  NOT WANT 
	  NOT WANT @return The old value of the mTagHooks array associated with the hook
	  WANT
	 SO IM LIKE setHook WITH UR $tag, $callback
		 I HAS tag IZ strtolower( $tag )
		 I HAS oldVal IZ isset( $this->mTagHooks[$tag] ) ? $this->mTagHooks[$tag] : null
		$this->mTagHooks[$tag] = $callback;
		 IZ !in_array( $tag, $this->mStripList )
			$this->mStripList[] = $tag;
		 KTHX

		 I FOUND MAH $oldVal
	 KTHX

	 SO IM LIKE setTransparentTagHook WITH UR $tag, $callback
		 I HAS tag IZ strtolower( $tag )
		 I HAS oldVal IZ isset( $this->mTransparentTagHooks[$tag] ) ? $this->mTransparentTagHooks[$tag] : null
		$this->mTransparentTagHooks[$tag] = $callback;

		 I FOUND MAH $oldVal
	 KTHX

	 DO NOT WANT
	  NOT WANT Remove all tag hooks
	  WANT
	 SO IM LIKE clearTagHooks
		 UR SPECIAL mTagHooks IZ EMPTY;
		 UR SPECIAL mStripList IZ $this->mDefaultStripList;
	 KTHX

	 DO NOT WANT
	  NOT WANT Create a function, e.g. {{sum:1|2|3}}
	  NOT WANT The callback function should have the form:
	  NOT WANT    function myParserFunction( &$parser, $arg1, $arg2, $arg3 ) { ... }
	  NOT WANT 
	  NOT WANT Or with SFH_OBJECT_ARGS:
	  NOT WANT    function myParserFunction( $parser, $frame, $args ) { ... }
	  NOT WANT 
	  NOT WANT The callback may either return the text result of the function, or an array with the text
	  NOT WANT in element 0, and a number of flags in the other elements. The names of the flags are
	  NOT WANT specified in the keys. Valid flags are:
	  NOT WANT   found                     The text returned is valid, stop processing the template. This
	  NOT WANT                             is on by default.
	  NOT WANT   nowiki                    Wiki markup in the return value should be escaped
	  NOT WANT   isHTML                    The returned text is HTML, armour it against wikitext transformation
	  NOT WANT 
	  NOT WANT @public
	  NOT WANT 
	  NOT WANT @param string $id The magic word ID
	  NOT WANT @param mixed $callback The callback function (and object) to use
	  NOT WANT @param integer $flags a combination of the following flags:
	  NOT WANT     SFH_NO_HASH   No leading hash, i.e. {{plural:...}} instead of {{#if:...}}
	  NOT WANT 
	  NOT WANT     SFH_OBJECT_ARGS   Pass the template arguments as PPNode objects instead of text. This
	  NOT WANT     allows for conditional expansion of the parse tree, allowing you to eliminate dead
	  NOT WANT     branches and thus speed up parsing. It is also possible to analyse the parse tree of
	  NOT WANT     the arguments, and to control the way they are expanded.
	  NOT WANT 
	  NOT WANT     The $frame parameter is a PPFrame. This can be used to produce expanded text from the
	  NOT WANT     arguments, for instance:
	  NOT WANT         $text = isset( $args[0] ) ? $frame->expand( $args[0] ) : '';
	  NOT WANT 
	  NOT WANT     For technical reasons, $args[0] is pre-expanded and will be a string. This may change in
	  NOT WANT     future versions. Please call $frame->expand() on it anyway so that your code keeps
	  NOT WANT     working if/when this is changed.
	  NOT WANT 
	  NOT WANT     If you want whitespace to be trimmed from $args, you need to do it yourself, post-
	  NOT WANT     expansion.
	  NOT WANT 
	  NOT WANT     Please read the documentation in includes/parser/Preprocessor.php for more information
	  NOT WANT     about the methods available in PPFrame and PPNode.
	  NOT WANT 
	  NOT WANT @return The old callback function for this name, if any
	  WANT
	 SO IM LIKE setFunctionHook WITH UR $id, $callback, $flags = 0
		 I HAS UR $wgContLang ON UR INTERNETS

		 I HAS oldVal IZ isset( $this->mFunctionHooks[$id] ) ? $this->mFunctionHooks[$id][0] : null
		$this->mFunctionHooks[$id] = array( $callback, $flags );

		 BTW Add to function cache
		 I HAS mw IZ MagicWord::get( $id )
		if ( !$mw )
			throw new MWException( __METHOD__.'() expecting a magic word identifier.' );

		 I HAS synonyms IZ $mw->getSynonyms()
		 I HAS sensitive IZ intval( $mw->isCaseSensitive() )

		 IM IN UR synonyms ITZA syn
			 BTW Case
			 IZ !$sensitive
				 I HAS syn IZ $wgContLang->lc( $syn )
			 KTHX
			 BTW Add leading hash
			 IZ !( $flags & SFH_NO_HASH )
				 I HAS syn IZ '#' . $syn
			 KTHX
			 BTW Remove trailing colon
			 IZ substr( $syn, -1, 1 ) === ':'
				 I HAS syn IZ substr( $syn, 0, -1 )
			 KTHX
			$this->mFunctionSynonyms[$sensitive][$syn] = $id;
		 KTHX
		 I FOUND MAH $oldVal
	 KTHX

	 DO NOT WANT
	  NOT WANT Get all registered function hook identifiers
	  NOT WANT 
	  NOT WANT @return array
	  WANT
	 SO IM LIKE getFunctionHooks
		 I FOUND MAH array_keys( $this->mFunctionHooks )
	 KTHX

	 DO NOT WANT
	  NOT WANT Create a tag function, e.g. <test>some stuff</test>.
	  NOT WANT Unlike tag hooks, tag functions are parsed at preprocessor level.
	  NOT WANT Unlike parser functions, their content is not preprocessed.
	  WANT
	 SO IM LIKE setFunctionTagHook WITH UR $tag, $callback, $flags
		 I HAS tag IZ strtolower( $tag )
		 old IZ isset( $this->mFunctionTagHooks[$tag] ) ?
			$this->mFunctionTagHooks[$tag] : null;
		$this->mFunctionTagHooks[$tag] = array( $callback, $flags );

		 IZ !in_array( $tag, $this->mStripList )
			$this->mStripList[] = $tag;
		 KTHX

		 I FOUND MAH $old
	 KTHX

	 DO NOT WANT
	  NOT WANT FIXME: update documentation. makeLinkObj() is deprecated.
	  NOT WANT Replace <!--LINK--> link placeholders with actual links, in the buffer
	  NOT WANT Placeholders created in Skin::makeLinkObj()
	  NOT WANT Returns an array of link CSS classes, indexed by PDBK.
	  WANT
	 SO IM LIKE replaceLinkHolders WITH UR &$text, $options = 0
		 I FOUND MAH $this->mLinkHolders->replace( $text )
	 KTHX

	 DO NOT WANT
	  NOT WANT Replace <!--LINK--> link placeholders with plain text of links
	  NOT WANT (not HTML-formatted).
	  NOT WANT @param string $text
	  NOT WANT @return string
	  WANT
	 SO IM LIKE replaceLinkHoldersText WITH UR $text
		 I FOUND MAH $this->mLinkHolders->replaceText( $text )
	 KTHX

	 DO NOT WANT
	  NOT WANT Renders an image gallery from a text with one line per image.
	  NOT WANT text labels may be given by using |-style alternative text. E.g.
	  NOT WANT   Image:one.jpg|The number "1"
	  NOT WANT   Image:tree.jpg|A tree
	  NOT WANT given as text will return the HTML of a gallery with two images,
	  NOT WANT labeled 'The number "1"' and
	  NOT WANT 'A tree'.
	  WANT
	 SO IM LIKE renderImageGallery WITH UR $text, $params
		 I HAS ig IZ new ImageGallery()
		$ig->setContextTitle( $this->mTitle );
		$ig->setShowBytes( false );
		$ig->setShowFilename( false );
		$ig->setParser( $this );
		$ig->setHideBadImages();
		$ig->setAttributes( Sanitizer::validateTagAttributes( $params, 'table' ) );
		$ig->useSkin( $this->mOptions->getSkin() );
		$ig->mRevisionId = $this->mRevisionId;

		 IZ isset( $params['showfilename'] )
			$ig->setShowFilename( true );
		 NOWAI
			$ig->setShowFilename( false );
		 KTHX
		 IZ isset( $params['caption'] )
			 I HAS caption IZ $params['caption']
			 I HAS caption IZ htmlspecialchars( $caption )
			 I HAS caption IZ $this->replaceInternalLinks( $caption )
			$ig->setCaptionHtml( $caption );
		 KTHX
		 IZ isset( $params['perrow'] )
			$ig->setPerRow( $params['perrow'] );
		 KTHX
		 IZ isset( $params['widths'] )
			$ig->setWidths( $params['widths'] );
		 KTHX
		 IZ isset( $params['heights'] )
			$ig->setHeights( $params['heights'] );
		 KTHX

		 IM ON UR wfRunHooks DOING 'BeforeParserrenderImageGallery', array( &$this, &$ig )

		 I HAS lines IZ StringUtils::explode( "\n", $text )
		 IM IN UR lines ITZA line
			 BTW match lines like these:
			 BTW Image:someimage.jpg|This is some image
			 I HAS matches IZ EMPTY
			 IM ON UR preg_match DOING "/^([^|]+)(\\|(.*))?$/", $line, $matches
			 BTW Skip empty lines
			 IZ count( $matches ) == 0
				continue;
			 KTHX

			 IZ strpos( $matches[0], '%' ) !== false
				$matches[1] = urldecode( $matches[1] );
			 KTHX
			 I HAS tp IZ Title::newFromText( $matches[1] )
			$nt =& $tp;
			 IZ is_null( $nt )
				 BTW Bogus title. Ignore these so we don't bomb out later.
				continue;
			 KTHX
			 IZ isset( $matches[3] )
				 I HAS label IZ $matches[3]
			 NOWAI
				 I HAS label IZ ''
			 KTHX

			 I HAS html IZ $this->recursiveTagParse( trim( $label ) )

			$ig->add( $nt, $html );

			 BTW Only add real images (bug #5586)
			 IZ $nt->getNamespace() == NS_FILE
				 IM ON UR SPECIAL mOutput->addImage DOING $nt->getDBkey()
			 KTHX
		 KTHX
		 I FOUND MAH $ig->toHTML()
	 KTHX

	 SO IM LIKE getImageParams WITH UR $handler
		 IZ $handler
			 I HAS handlerClass IZ get_class( $handler )
		 NOWAI
			 I HAS handlerClass IZ ''
		 KTHX
		 IZ !isset( $this->mImageParams[$handlerClass]  )
			 BTW Initialise static lists
			static $internalParamNames = array(
				'horizAlign' => array( 'left', 'right', 'center', 'none' ),
				'vertAlign' => array( 'baseline', 'sub', 'super', 'top', 'text-top', 'middle',
					'bottom', 'text-bottom' ),
				'frame' => array( 'thumbnail', 'manualthumb', 'framed', 'frameless',
					'upright', 'border', 'link', 'alt' ),
			 BUCKET
			static $internalParamMap;
			 IZ !$internalParamMap
				 I HAS internalParamMap IZ EMPTY
				 IM IN UR internalParamNames ITZA type => $names
					 IM IN UR names ITZA name
						 I HAS magicName IZ str_replace( '-', '_', "img_$name" )
						$internalParamMap[$magicName] = array( $type, $name );
					 KTHX
				 KTHX
			 KTHX

			 BTW Add handler params
			 I HAS paramMap IZ $internalParamMap
			 IZ $handler
				 I HAS handlerParamMap IZ $handler->getParamMap()
				 IM IN UR handlerParamMap ITZA magic => $paramName
					$paramMap[$magic] = array( 'handler', $paramName );
				 KTHX
			 KTHX
			$this->mImageParams[$handlerClass] = $paramMap;
			$this->mImageParamsMagicArray[$handlerClass] = new MagicWordArray( array_keys( $paramMap ) );
		 KTHX
		 I FOUND MAH array( $this->mImageParams[$handlerClass], $this->mImageParamsMagicArray[$handlerClass] )
	 KTHX

	 DO NOT WANT
	  NOT WANT Parse image options text and use it to make an image
	  NOT WANT @param Title $title
	  NOT WANT @param string $options
	  NOT WANT @param LinkHolderArray $holders
	  WANT
	 SO IM LIKE makeImage WITH UR $title, $options, $holders = false
		 BTW Check if the options text is of the form "options|alt text"
		 BTW Options are:
		 BTW  * thumbnail  make a thumbnail with enlarge-icon and caption, alignment depends on lang
		 BTW  * left       no resizing, just left align. label is used for alt= only
		 BTW  * right      same, but right aligned
		 BTW  * none       same, but not aligned
		 BTW  * ___px      scale to ___ pixels width, no aligning. e.g. use in taxobox
		 BTW  * center     center the image
		 BTW  * frame      Keep original image size, no magnify-button.
		 BTW  * framed     Same as "frame"
		 BTW  * frameless  like 'thumb' but without a frame. Keeps user preferences for width
		 BTW  * upright    reduce width for upright images, rounded to full __0 px
		 BTW  * border     draw a 1px border around the image
		 BTW  * alt        Text for HTML alt attribute (defaults to empty)
		 BTW  * link       Set the target of the image link. Can be external, interwiki, or local
		 BTW vertical-align values (no % or length right now):
		 BTW  * baseline
		 BTW  * sub
		 BTW  * super
		 BTW  * top
		 BTW  * text-top
		 BTW  * middle
		 BTW  * bottom
		 BTW  * text-bottom

		 I HAS parts IZ StringUtils::explode( "|", $options )
		 I HAS sk IZ $this->mOptions->getSkin()

		 BTW Give extensions a chance to select the file revision for us
		 I HAS skip IZ $time = $descQuery = false
		 IM ON UR wfRunHooks DOING 'BeforeParserMakeImageLinkObj', array( &$this, &$title, &$skip, &$time, &$descQuery )

		 IZ $skip
			 I FOUND MAH $sk->link( $title )
		 KTHX

		 BTW Get the file
		 I HAS imagename IZ $title->getDBkey()
		 I HAS file IZ wfFindFile( $title, array( 'time' => $time ) )
		 BTW Get parameter map
		 I HAS handler IZ $file ? $file->getHandler() : false

		 IM ON UR list DOING $paramMap, $mwArray ) = $this->getImageParams( $handler

		 BTW Process the input parameters
		 I HAS caption IZ ''
		 I HAS params IZ BUCKET 'frame' => array(), 'handler' => array(),
			'horizAlign' => array(), 'vertAlign' => array() );
		 IM IN UR parts ITZA part
			 I HAS part IZ trim( $part )
			 IM ON UR list DOING $magicName, $value ) = $mwArray->matchVariableStartToEnd( $part
			 I HAS validated IZ false
			 IZ isset( $paramMap[$magicName] )
				list( $type, $paramName ) = $paramMap[$magicName];

				 BTW Special case; width and height come in one variable together
				 IZ $type === 'handler' && $paramName === 'width'
					 I HAS m IZ EMPTY
					 BTW (bug 13500) In both cases (width/height and width only),
					 BTW permit trailing "px" for backward compatibility.
					 IZ preg_match( '/^([0-9]*)x([0-9]*)\s*(?:px)?\s*$/', $value, $m )
						 I HAS width IZ intval( $m[1] )
						 I HAS height IZ intval( $m[2] )
						 IZ $handler->validateParam( 'width', $width )
							$params[$type]['width'] = $width;
							 I HAS validated IZ true
						 KTHX
						 IZ $handler->validateParam( 'height', $height )
							$params[$type]['height'] = $height;
							 I HAS validated IZ true
						 KTHX
					 ORLY preg_match( '/^[0-9]*\s*(?:px)?\s*$/', $value )
						 I HAS width IZ intval( $value )
						 IZ $handler->validateParam( 'width', $width )
							$params[$type]['width'] = $width;
							 I HAS validated IZ true
						 KTHX
					 KTHX # else no validation -- bug 13436
				 NOWAI
					 IZ $type === 'handler'
						 BTW Validate handler parameter
						 I HAS validated IZ $handler->validateParam( $paramName, $value )
					 NOWAI
						 BTW Validate internal parameters
						switch( $paramName ) {
						case 'manualthumb':
						case 'alt':
							 BTW @todo Fixme: possibly check validity here for
							 BTW manualthumb? downstream behavior seems odd with
							 BTW missing manual thumbs.
							 I HAS validated IZ true
							 I HAS value IZ $this->stripAltText( $value, $holders )
							break;
						case 'link':
							 I HAS chars IZ self::EXT_LINK_URL_CLASS
							 I HAS prots IZ $this->mUrlProtocols
							 IZ $value === ''
								 I HAS paramName IZ 'no-link'
								 I HAS value IZ true
								 I HAS validated IZ true
							 ORLY preg_match( "/^$prots/", $value )
								 IZ preg_match( "/^($prots)$chars+$/", $value, $m )
									 I HAS paramName IZ 'link-url'
									 IM ON UR SPECIAL mOutput->addExternalLink DOING $value
									 I HAS validated IZ true
								 KTHX
							 NOWAI
								 I HAS linkTitle IZ Title::newFromText( $value )
								 IZ $linkTitle
									 I HAS paramName IZ 'link-title'
									 I HAS value IZ $linkTitle
									 IM ON UR SPECIAL mOutput->addLink DOING $linkTitle
									 I HAS validated IZ true
								 KTHX
							 KTHX
							break;
						default:
							 BTW Most other things appear to be empty or numeric...
							 I HAS validated IZ ( $value === false || is_numeric( trim( $value ) ) )
						 KTHX
					 KTHX

					 IZ $validated
						$params[$type][$paramName] = $value;
					 KTHX
				 KTHX
			 KTHX
			 IZ !$validated
				 I HAS caption IZ $part
			 KTHX
		 KTHX

		 BTW Process alignment parameters
		 IZ $params['horizAlign']
			$params['frame']['align'] = key( $params['horizAlign'] );
		 KTHX
		 IZ $params['vertAlign']
			$params['frame']['valign'] = key( $params['vertAlign'] );
		 KTHX

		$params['frame']['caption'] = $caption;

		 BTW Will the image be presented in a frame, with the caption below?
		 imageIsFramed IZ isset( $params['frame']['frame'] ) ||
		                 isset( $params['frame']['framed'] ) ||
		                 isset( $params['frame']['thumbnail'] ) ||
		                  IM ON UR isset DOING $params['frame']['manualthumb']

		 BTW In the old days, [[Image:Foo|text...]] would set alt text.  Later it
		 BTW came to also set the caption, ordinary text after the image -- which
		 BTW makes no sense, because that just repeats the text multiple times in
		 BTW screen readers.  It *also* came to set the title attribute.
		 BTW 
		 BTW Now that we have an alt attribute, we should not set the alt text to
		 BTW equal the caption: that's worse than useless, it just repeats the
		 BTW text.  This is the framed/thumbnail case.  If there's no caption, we
		 BTW use the unnamed parameter for alt text as well, just for the time be-
		 BTW ing, if the unnamed param is set and the alt param is not.
		 BTW 
		 BTW For the future, we need to figure out if we want to tweak this more,
		 BTW e.g., introducing a title= parameter for the title; ignoring the un-
		 BTW named parameter entirely for images without a caption; adding an ex-
		 BTW plicit caption= parameter and preserving the old magic unnamed para-
		 BTW meter for BC; ...
		 IZ $imageIsFramed # Framed image
			 IZ $caption === '' && !isset( $params['frame']['alt'] )
				 BTW No caption or alt text, add the filename as the alt text so
				 BTW that screen readers at least get some description of the image
				$params['frame']['alt'] = $title->getText();
			 KTHX
			 BTW Do not set $params['frame']['title'] because tooltips don't make sense
			 BTW for framed images
		 NOWAI # Inline image
			 IZ !isset( $params['frame']['alt'] )
				 BTW No alt text, use the "caption" for the alt text
				if ( $caption !== '') {
					$params['frame']['alt'] = $this->stripAltText( $caption, $holders );
				 NOWAI
					 BTW No caption, fall back to using the filename for the
					 BTW alt text
					$params['frame']['alt'] = $title->getText();
				 KTHX
			 KTHX
			 BTW Use the "caption" for the tooltip text
			$params['frame']['title'] = $this->stripAltText( $caption, $holders );
		 KTHX

		 IM ON UR wfRunHooks DOING 'ParserMakeImageParams', array( $title, $file, &$params )

		 BTW Linker does the rest
		 I HAS ret IZ $sk->makeImageLink2( $title, $file, $params['frame'], $params['handler'], $time, $descQuery )

		 BTW Give the handler a chance to modify the parser object
		 IZ $handler
			$handler->parserTransformHook( $this, $file );
		 KTHX

		 I FOUND MAH $ret
	 KTHX

	 SO IM LIKE stripAltText WITH UR $caption, $holders
		 BTW Strip bad stuff out of the title (tooltip).  We can't just use
		 BTW replaceLinkHoldersText() here, because if this function is called
		 BTW from replaceInternalLinks2(), mLinkHolders won't be up-to-date.
		 IZ $holders
			 I HAS tooltip IZ $holders->replaceText( $caption )
		 NOWAI
			 I HAS tooltip IZ $this->replaceLinkHoldersText( $caption )
		 KTHX

		 BTW make sure there are no placeholders in thumbnail attributes
		 BTW that are later expanded to html- so expand them now and
		 BTW remove the tags
		 I HAS tooltip IZ $this->mStripState->unstripBoth( $tooltip )
		 I HAS tooltip IZ Sanitizer::stripAllTags( $tooltip )

		 I FOUND MAH $tooltip
	 KTHX

	 DO NOT WANT
	  NOT WANT Set a flag in the output object indicating that the content is dynamic and
	  NOT WANT shouldn't be cached.
	  WANT
	 SO IM LIKE disableCache
		 IM ON UR wfDebug DOING "Parser output marked as uncacheable.\n"
		 UR SPECIAL mOutput->mCacheTime IZ -1;
	 KTHX

	 DO NOT WANT#@+
	  NOT WANT Callback from the Sanitizer for expanding items found in HTML attribute
	  NOT WANT values, so they can be safely tested and escaped.
	  NOT WANT @param string $text
	  NOT WANT @param PPFrame $frame
	  NOT WANT @return string
	  NOT WANT @private
	  WANT
	 SO IM LIKE attributeStripCallback WITH UR &$text, $frame = false
		 I HAS text IZ $this->replaceVariables( $text, $frame )
		 I HAS text IZ $this->mStripState->unstripBoth( $text )
		 I FOUND MAH $text
	 KTHX

	 DO NOT WANT#@-*/

	 DO NOT WANT#@+
	  NOT WANT Accessor/mutator
	  WANT
	 SO IM LIKE Title WITH UR $x = null TESTING UR return wfSetVar( $this->mTitle, $x );
	 SO IM LIKE Options WITH UR $x = null TESTING UR return wfSetVar( $this->mOptions, $x );
	 SO IM LIKE OutputType WITH UR $x = null TESTING UR return wfSetVar( $this->mOutputType, $x );
	 DO NOT WANT#@-*/

	 DO NOT WANT#@+
	  NOT WANT Accessor
	  WANT
	 SO IM LIKE getTags 
		 I FOUND MAH array_merge( array_keys( $this->mTransparentTagHooks ), array_keys( $this->mTagHooks ) ) 
	 KTHX
	 DO NOT WANT#@-*/


	 DO NOT WANT
	  NOT WANT Break wikitext input into sections, and either pull or replace
	  NOT WANT some particular section's text.
	  NOT WANT 
	  NOT WANT External callers should use the getSection and replaceSection methods.
	  NOT WANT 
	  NOT WANT @param string $text Page wikitext
	  NOT WANT @param string $section A section identifier string of the form:
	  NOT WANT   <flag1> - <flag2> - ... - <section number>
	  NOT WANT 
	  NOT WANT Currently the only recognised flag is "T", which means the target section number
	  NOT WANT was derived during a template inclusion parse, in other words this is a template
	  NOT WANT section edit link. If no flags are given, it was an ordinary section edit link.
	  NOT WANT This flag is required to avoid a section numbering mismatch when a section is
	  NOT WANT enclosed by <includeonly> (bug 6563).
	  NOT WANT 
	  NOT WANT The section number 0 pulls the text before the first heading; other numbers will
	  NOT WANT pull the given section along with its lower-level subsections. If the section is
	  NOT WANT not found, $mode=get will return $newtext, and $mode=replace will return $text.
	  NOT WANT 
	  NOT WANT @param string $mode One of "get" or "replace"
	  NOT WANT @param string $newText Replacement text for section data.
	  NOT WANT @return string for "get", the extracted section text.
	  NOT WANT                for "replace", the whole page with the section replaced.
	  WANT
	 SO IM LIKE extractSections WITH UR $text, $section, $mode, $newText=''
		 I HAS UR $wgTitle ON UR INTERNETS
		 IM ON UR SPECIAL clearState
		 IM ON UR SPECIAL setTitle DOING $wgTitle # not generally used but removes an ugly failure mode
		 UR SPECIAL mOptions IZ new ParserOptions;
		 IM ON UR SPECIAL setOutputType DOING self::OT_PLAIN
		 I HAS outText IZ ''
		 I HAS frame IZ $this->getPreprocessor()->newFrame()

		 BTW Process section extraction flags
		 I HAS flags IZ 0
		 I HAS sectionParts IZ explode( '-', $section )
		 I HAS sectionIndex IZ array_pop( $sectionParts )
		 IM IN UR sectionParts ITZA part
			 IZ $part === 'T'
				$flags |= self::PTD_FOR_INCLUSION;
			 KTHX
		 KTHX
		 BTW Preprocess the text
		 I HAS root IZ $this->preprocessToDom( $text, $flags )

		 BTW <h> nodes indicate section breaks
		 BTW They can only occur at the top level, so we can find them by iterating the root's children
		 I HAS node IZ $root->getFirstChild()

		 BTW Find the target section
		 IZ $sectionIndex == 0
			 BTW Section zero doesn't nest, level=big
			 I HAS targetLevel IZ 1000
		 NOWAI
             STEALIN UR $node
                 IZ $node->getName() === 'h'
                     I HAS bits IZ $node->splitHeading()
					 IZ $bits['i'] == $sectionIndex
        				 I HAS targetLevel IZ $bits['level']
						break;
					 KTHX
				 KTHX
				 IZ $mode === 'replace'
					 outText HAS MOAR $frame->expand( $node, PPFrame::RECOVER_ORIG );
				 KTHX
				 I HAS node IZ $node->getNextSibling()
			 KTHX
		 KTHX

		 IZ !$node
			 BTW Not found
			 IZ $mode === 'get'
				 I FOUND MAH $newText
			 NOWAI
				 I FOUND MAH $text
			 KTHX
		 KTHX

		 BTW Find the end of the section, including nested sections
		do {
			 IZ $node->getName() === 'h'
				 I HAS bits IZ $node->splitHeading()
				 I HAS curLevel IZ $bits['level']
				 IZ $bits['i'] != $sectionIndex && $curLevel <= $targetLevel
					break;
				 KTHX
			 KTHX
			 IZ $mode === 'get'
				 outText HAS MOAR $frame->expand( $node, PPFrame::RECOVER_ORIG );
			 KTHX
			 I HAS node IZ $node->getNextSibling()
		 KTHX while ( $node );

		 BTW Write out the remainder (in replace mode only)
		 IZ $mode === 'replace'
			 BTW Output the replacement text
			 BTW Add two newlines on -- trailing whitespace in $newText is conventionally
			 BTW stripped by the editor, so we need both newlines to restore the paragraph gap
			 BTW Only add trailing whitespace if there is newText
			 IZ $newText != ""
				 outText HAS MOAR $newText . "\n\n";
			 KTHX

			 STEALIN UR $node
				 outText HAS MOAR $frame->expand( $node, PPFrame::RECOVER_ORIG );
				 I HAS node IZ $node->getNextSibling()
			 KTHX
		 KTHX

		 IZ is_string( $outText )
			 BTW Re-insert stripped tags
			 I HAS outText IZ rtrim( $this->mStripState->unstripBoth( $outText ) )
		 KTHX

		 I FOUND MAH $outText
	 KTHX

	 DO NOT WANT
	  NOT WANT This function returns the text of a section, specified by a number ($section).
	  NOT WANT A section is text under a heading like == Heading == or \<h1\>Heading\</h1\>, or
	  NOT WANT the first section before any such heading (section 0).
	  NOT WANT 
	  NOT WANT If a section contains subsections, these are also returned.
	  NOT WANT 
	  NOT WANT @param string $text text to look in
	  NOT WANT @param string $section section identifier
	  NOT WANT @param string $deftext default to return if section is not found
	  NOT WANT @return string text of the requested section
	  WANT
	 SO IM LIKE getSection WITH UR $text, $section, $deftext=''
		 I FOUND MAH $this->extractSections( $text, $section, "get", $deftext )
	 KTHX

	 SO IM LIKE replaceSection WITH UR $oldtext, $section, $text
		 I FOUND MAH $this->extractSections( $oldtext, $section, "replace", $text )
	 KTHX

	 DO NOT WANT
	  NOT WANT Get the timestamp associated with the current revision, adjusted for
	  NOT WANT the default server-local timestamp
	  WANT
	 SO IM LIKE getRevisionTimestamp
		 IZ is_null( $this->mRevisionTimestamp )
			 IM ON UR wfProfileIn DOING __METHOD__
			 I HAS UR $wgContLang ON UR INTERNETS
			 I HAS dbr IZ wfGetDB( DB_SLAVE )
			 timestamp IZ $dbr->selectField( 'revision', 'rev_timestamp',
					 IM ON UR array DOING 'rev_id' => $this->mRevisionId ), __METHOD__

			 BTW Normalize timestamp to internal MW format for timezone processing.
			 BTW This has the added side-effect of replacing a null value with
			 BTW the current time, which gives us more sensible behavior for
			 BTW previews.
			 I HAS timestamp IZ wfTimestamp( TS_MW, $timestamp )

			 BTW The cryptic '' timezone parameter tells to use the site-default
			 BTW timezone offset instead of the user settings.
			 BTW 
			 BTW Since this value will be saved into the parser cache, served
			 BTW to other users, and potentially even used inside links and such,
			 BTW it needs to be consistent for all visitors.
			 UR SPECIAL mRevisionTimestamp IZ $wgContLang->userAdjust( $timestamp, '' );

			 IM ON UR wfProfileOut DOING __METHOD__
		 KTHX
		 I FOUND MAH $this->mRevisionTimestamp
	 KTHX

	 DO NOT WANT
	  NOT WANT Get the name of the user that edited the last revision
	  WANT
	 SO IM LIKE getRevisionUser
		 BTW if this template is subst: the revision id will be blank,
		 BTW so just use the current user's name
		 IZ $this->mRevisionId
			 I HAS revision IZ Revision::newFromId( $this->mRevisionId )
			 I HAS revuser IZ $revision->getUserText()
		 NOWAI
			 I HAS UR $wgUser ON UR INTERNETS
			 I HAS revuser IZ $wgUser->getName()
		 KTHX
		 I FOUND MAH $revuser
	 KTHX

	 DO NOT WANT
	  NOT WANT Mutator for $mDefaultSort
	  NOT WANT 
	  NOT WANT @param $sort New value
	  WANT
	 SO IM LIKE setDefaultSort WITH UR $sort
		 UR SPECIAL mDefaultSort IZ $sort;
	 KTHX

	 DO NOT WANT
	  NOT WANT Accessor for $mDefaultSort
	  NOT WANT Will use the title/prefixed title if none is set
	  NOT WANT 
	  NOT WANT @return string
	  WANT
	 SO IM LIKE getDefaultSort
		 I HAS UR $wgCategoryPrefixedDefaultSortkey ON UR INTERNETS
		 IZ $this->mDefaultSort !== false
			 I FOUND MAH $this->mDefaultSort
		 KTHX elseif ( $this->mTitle->getNamespace() == NS_CATEGORY ||
			!$wgCategoryPrefixedDefaultSortkey ) 
		{
			 I FOUND MAH $this->mTitle->getText()
		 NOWAI
			 I FOUND MAH $this->mTitle->getPrefixedText()
		 KTHX
	 KTHX

	 DO NOT WANT
	  NOT WANT Accessor for $mDefaultSort
	  NOT WANT Unlike getDefaultSort(), will return false if none is set
	  NOT WANT 
	  NOT WANT @return string or false
	  WANT
	 SO IM LIKE getCustomDefaultSort
		 I FOUND MAH $this->mDefaultSort
	 KTHX

	 DO NOT WANT
	  NOT WANT Try to guess the section anchor name based on a wikitext fragment
	  NOT WANT presumably extracted from a heading, for example "Header" from
	  NOT WANT "== Header ==".
	  WANT
	 SO IM LIKE guessSectionNameFromWikiText WITH UR $text
		 BTW Strip out wikitext links(they break the anchor)
		 I HAS text IZ $this->stripSectionName( $text )
		 I HAS headline IZ Sanitizer::decodeCharReferences( $text )
		 BTW strip out HTML
		 I HAS headline IZ StringUtils::delimiterReplace( '<', '>', '', $headline )
		 I HAS headline IZ trim( $headline )
		 I HAS sectionanchor IZ '#' . urlencode( str_replace( ' ', '_', $headline ) )
		 I HAS replacearray IZ BUCKET
			'%3A' => ':',
			'%' => '.'
		 BUCKET
		return str_replace(
			array_keys( $replacearray ),
			array_values( $replacearray ),
			$sectionanchor );
	 KTHX

	 DO NOT WANT
	  NOT WANT Strips a text string of wikitext for use in a section anchor
	  NOT WANT 
	  NOT WANT Accepts a text string and then removes all wikitext from the
	  NOT WANT string and leaves only the resultant text (i.e. the result of
	  NOT WANT [[User:WikiSysop|Sysop]] would be "Sysop" and the result of
	  NOT WANT [[User:WikiSysop]] would be "User:WikiSysop") - this is intended
	  NOT WANT to create valid section anchors by mimicing the output of the
	  NOT WANT parser when headings are parsed.
	  NOT WANT 
	  NOT WANT @param $text string Text string to be stripped of wikitext
	  NOT WANT for use in a Section anchor
	  NOT WANT @return Filtered text string
	  WANT
	 SO IM LIKE stripSectionName WITH UR $text
		 BTW Strip internal link markup
		 I HAS text IZ preg_replace( '/\[\[:?([^[|]+)\|([^[]+)\]\]/', '$2', $text )
		 I HAS text IZ preg_replace( '/\[\[:?([^[]+)\|?\]\]/', '$1', $text )

		 BTW Strip external link markup (FIXME: Not Tolerant to blank link text
		 BTW I.E. [http://www.mediawiki.org] will render as [1] or something depending
		 BTW on how many empty links there are on the page - need to figure that out.
		 I HAS text IZ preg_replace( '/\[(?:' . wfUrlProtocols() . ')([^ ]+?) ([^[]+)\]/', '$2', $text )

		 BTW Parse wikitext quotes (italics & bold)
		 I HAS text IZ $this->doQuotes( $text )

		 BTW Strip HTML tags
		 I HAS text IZ StringUtils::delimiterReplace( '<', '>', '', $text )
		 I FOUND MAH $text
	 KTHX

	 SO IM LIKE srvus WITH UR $text
		 I FOUND MAH $this->testSrvus( $text, $this->mOutputType )
	 KTHX

	 DO NOT WANT
	  NOT WANT strip/replaceVariables/unstrip for preprocessor regression testing
	  WANT
	 SO IM LIKE testSrvus WITH UR $text, $title, $options, $outputType = self::OT_HTML
		 IM ON UR SPECIAL clearState
		 IZ !$title instanceof Title
			 I HAS title IZ Title::newFromText( $title )
		 KTHX
		 UR SPECIAL mTitle IZ $title;
		 UR SPECIAL mOptions IZ $options;
		 IM ON UR SPECIAL setOutputType DOING $outputType
		 I HAS text IZ $this->replaceVariables( $text )
		 I HAS text IZ $this->mStripState->unstripBoth( $text )
		 I HAS text IZ Sanitizer::removeHTMLtags( $text )
		 I FOUND MAH $text
	 KTHX

	 SO IM LIKE testPst WITH UR $text, $title, $options
		 I HAS UR $wgUser ON UR INTERNETS
		 IZ !$title instanceof Title
			 I HAS title IZ Title::newFromText( $title )
		 KTHX
		 I FOUND MAH $this->preSaveTransform( $text, $title, $wgUser, $options )
	 KTHX

	 SO IM LIKE testPreprocess WITH UR $text, $title, $options
		 IZ !$title instanceof Title
			 I HAS title IZ Title::newFromText( $title )
		 KTHX
		 I FOUND MAH $this->testSrvus( $text, $title, $options, self::OT_PREPROCESS )
	 KTHX

	 SO IM LIKE markerSkipCallback WITH UR $s, $callback
		 I HAS i IZ 0
		 I HAS out IZ ''
		 STEALIN UR $i < strlen( $s )
			 I HAS markerStart IZ strpos( $s, $this->mUniqPrefix, $i )
			 IZ $markerStart === false
				 out HAS MOAR call_user_func( $callback, substr( $s, $i ) );
				break;
			 NOWAI
				 out HAS MOAR call_user_func( $callback, substr( $s, $i, $markerStart - $i ) );
				 I HAS markerEnd IZ strpos( $s, self::MARKER_SUFFIX, $markerStart )
				 IZ $markerEnd === false
					 out HAS MOAR substr( $s, $markerStart );
					break;
				 NOWAI
					$markerEnd += strlen( self::MARKER_SUFFIX );
					 out HAS MOAR substr( $s, $markerStart, $markerEnd - $markerStart );
					 I HAS i IZ $markerEnd
				 KTHX
			 KTHX
		 KTHX
		 I FOUND MAH $out
	 KTHX

	 SO IM LIKE serialiseHalfParsedText WITH UR $text
		 I HAS data IZ EMPTY
		$data['text'] = $text;

		 BTW First, find all strip markers, and store their
		 BTW  data in an array.
		 I HAS stripState IZ new StripState
		 I HAS pos IZ 0
		while ( ( $start_pos = strpos( $text, $this->mUniqPrefix, $pos ) ) 
			&& ( $end_pos = strpos( $text, self::MARKER_SUFFIX, $pos ) ) ) 
		{
			$end_pos += strlen( self::MARKER_SUFFIX );
			 I HAS marker IZ substr( $text, $start_pos, $end_pos-$start_pos )

			 IZ !empty( $this->mStripState->general->data[$marker] )
				 I HAS replaceArray IZ $stripState->general
				 I HAS stripText IZ $this->mStripState->general->data[$marker]
			 ORLY !empty( $this->mStripState->nowiki->data[$marker] )
				 I HAS replaceArray IZ $stripState->nowiki
				 I HAS stripText IZ $this->mStripState->nowiki->data[$marker]
			 NOWAI
				throw new MWException( "Hanging strip marker: '$marker'." );
			 KTHX

			$replaceArray->setPair( $marker, $stripText );
			 I HAS pos IZ $end_pos
		 KTHX
		$data['stripstate'] = $stripState;

		 BTW Now, find all of our links, and store THEIR
		 BTW  data in an array! :)
		 I HAS links IZ BUCKET 'internal' => array(), 'interwiki' => array() );
		 I HAS pos IZ 0

		 BTW Internal links
		 STEALIN UR ( $start_pos = strpos( $text, '<!--LINK ', $pos ) )
			 IM ON UR list DOING $ns, $trail ) = explode( ':', substr( $text, $start_pos + strlen( '<!--LINK ' ) ), 2

			 I HAS ns IZ trim( $ns )
			 IZ empty( $links['internal'][$ns] )
				$links['internal'][$ns] = array();
			 KTHX

			 I HAS key IZ trim( substr( $trail, 0, strpos( $trail, '-->' ) ) )
			$links['internal'][$ns][] = $this->mLinkHolders->internals[$ns][$key];
			 I HAS pos IZ $start_pos + strlen( "<!--LINK $ns:$key-->" )
		 KTHX

		 I HAS pos IZ 0

		 BTW Interwiki links
		 STEALIN UR ( $start_pos = strpos( $text, '<!--IWLINK ', $pos ) )
			 I HAS data IZ substr( $text, $start_pos )
			 I HAS key IZ trim( substr( $data, 0, strpos( $data, '-->' ) ) )
			$links['interwiki'][] = $this->mLinkHolders->interwiki[$key];
			 I HAS pos IZ $start_pos + strlen( "<!--IWLINK $key-->" )
		 KTHX

		$data['linkholder'] = $links;

		 I FOUND MAH $data
	 KTHX

	 DO NOT WANT
	  NOT WANT TODO: document
	  NOT WANT @param $data Array
	  NOT WANT @param $intPrefix String unique identifying prefix
	  NOT WANT @return String
	  WANT
	 SO IM LIKE unserialiseHalfParsedText WITH UR $data, $intPrefix = null
		 IZ !$intPrefix
			 I HAS intPrefix IZ $this->getRandomString()
		 KTHX

		 BTW First, extract the strip state.
		 I HAS stripState IZ $data['stripstate']
		 IM ON UR SPECIAL mStripState->general->merge DOING $stripState->general
		 IM ON UR SPECIAL mStripState->nowiki->merge DOING $stripState->nowiki

		 BTW Now, extract the text, and renumber links
		 I HAS text IZ $data['text']
		 I HAS links IZ $data['linkholder']

		 BTW Internal...
		foreach ( $links['internal'] as $ns => $nsLinks ) {
			 IM IN UR nsLinks ITZA key => $entry
				 I HAS newKey IZ $intPrefix . '-' . $key
				$this->mLinkHolders->internals[$ns][$newKey] = $entry;

				 I HAS text IZ str_replace( "<!--LINK $ns:$key-->", "<!--LINK $ns:$newKey-->", $text )
			 KTHX
		 KTHX

		 BTW Interwiki...
		foreach ( $links['interwiki'] as $key => $entry ) {
			 I HAS newKey IZ "$intPrefix-$key"
			$this->mLinkHolders->interwikis[$newKey] = $entry;

			 I HAS text IZ str_replace( "<!--IWLINK $key-->", "<!--IWLINK $newKey-->", $text )
		 KTHX

		 BTW Should be good to go.
		 I FOUND MAH $text
	 KTHX
 KTHX

 DO NOT WANT
  NOT WANT @todo document, briefly.
  NOT WANT @ingroup Parser
  WANT
 IM IN UR SPECIAL StripState
	 I HAS UR $general, $nowiki

	 SO IM LIKE __construct
		 UR SPECIAL general IZ new ReplacementArray;
		 UR SPECIAL nowiki IZ new ReplacementArray;
	 KTHX

	 SO IM LIKE unstripGeneral WITH UR $text
		 IM ON UR wfProfileIn DOING __METHOD__
		do {
			 I HAS oldText IZ $text
			 I HAS text IZ $this->general->replace( $text )
		 KTHX while ( $text !== $oldText );
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX

	 SO IM LIKE unstripNoWiki WITH UR $text
		 IM ON UR wfProfileIn DOING __METHOD__
		do {
			 I HAS oldText IZ $text
			 I HAS text IZ $this->nowiki->replace( $text )
		 KTHX while ( $text !== $oldText );
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX

	 SO IM LIKE unstripBoth WITH UR $text
		 IM ON UR wfProfileIn DOING __METHOD__
		do {
			 I HAS oldText IZ $text
			 I HAS text IZ $this->general->replace( $text )
			 I HAS text IZ $this->nowiki->replace( $text )
		 KTHX while ( $text !== $oldText );
		 IM ON UR wfProfileOut DOING __METHOD__
		 I FOUND MAH $text
	 KTHX
 KTHX

 DO NOT WANT
  NOT WANT @todo document, briefly.
  NOT WANT @ingroup Parser
  WANT
 IM IN UR SPECIAL OnlyIncludeReplacer
	var $output = '';

	 SO IM LIKE replace WITH UR $matches
		 IZ substr( $matches[1], -1 ) === "\n"
			$this->output .= substr( $matches[1], 0, -1 );
		 NOWAI
			$this->output .= $matches[1];
		 KTHX
	 KTHX
 KTHX