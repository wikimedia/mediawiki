<?php
/**
 * Parser with LinkHooks experiment
 * @ingroup Parser
 */
class Parser_LinkHooks extends Parser
{
	/**
	 * Update this version number when the ParserOutput format
	 * changes in an incompatible way, so the parser cache
	 * can automatically discard old data.
	 */
	const VERSION = '1.6.4';
	
	# Flags for Parser::setLinkHook
	# Also available as global constants from Defines.php
	const SLH_PATTERN = 1;

	# Constants needed for external link processing
	# Everything except bracket, space, or control characters
	const EXT_LINK_URL_CLASS = '[^][<>"\\x00-\\x20\\x7F]';
	const EXT_IMAGE_REGEX = '/^(http:\/\/|https:\/\/)([^][<>"\\x00-\\x20\\x7F]+)
		\\/([A-Za-z0-9_.,~%\\-+&;#*?!=()@\\x80-\\xFF]+)\\.((?i)gif|png|jpg|jpeg)$/Sx';

	/**#@+
	 * @private
	 */
	# Persistent:
	var $mLinkHooks;

	/**#@-*/

	/**
	 * Constructor
	 *
	 * @public
	 */
	function __construct( $conf = array() ) {
		parent::__construct( $conf );
		$this->mLinkHooks = array();
	}

	/**
	 * Do various kinds of initialisation on the first call of the parser
	 */
	function firstCallInit() {
		parent::__construct();
		if ( !$this->mFirstCall ) {
			return;
		}
		$this->mFirstCall = false;

		wfProfileIn( __METHOD__ );

		$this->setHook( 'pre', array( $this, 'renderPreTag' ) );
		CoreParserFunctions::register( $this );
		CoreLinkFunctions::register( $this );
		$this->initialiseVariables();

		wfRunHooks( 'ParserFirstCallInit', array( &$this ) );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Create a link hook, e.g. [[Namepsace:...|display}}
	 * The callback function should have the form:
	 *    function myLinkCallback( $parser, $holders, $markers,
	 *    	Title $title, $titleText, &$sortText = null, &$leadingColon = false ) { ... }
	 *
	 * Or with SLH_PATTERN:
	 *    function myLinkCallback( $parser, $holders, $markers, )
	 *    	&$titleText, &$sortText = null, &$leadingColon = false ) { ... }
	 *
	 * The callback may either return a number of different possible values:
	 * String) Text result of the link
	 * True) (Treat as link) Parse the link according to normal link rules
	 * False) (Bad link) Just output the raw wikitext (You may modify the text first)
	 *
	 * @public
	 *
	 * @param $ns Integer or String: the Namespace ID or regex pattern if SLH_PATTERN is set
	 * @param $callback Mixed: the callback function (and object) to use
	 * @param $flags Integer: a combination of the following flags:
	 *     SLH_PATTERN   Use a regex link pattern rather than a namespace
	 *
	 * @return The old callback function for this name, if any
	 */
	function setLinkHook( $ns, $callback, $flags = 0 ) {
		if( $flags & SLH_PATTERN && !is_string($ns) )
			throw new MWException( __METHOD__.'() expecting a regex string pattern.' );
		elseif( $flags | ~SLH_PATTERN && !is_int($ns) )
			throw new MWException( __METHOD__.'() expecting a namespace index.' );
		$oldVal = isset( $this->mLinkHooks[$ns] ) ? $this->mLinkHooks[$ns][0] : null;
		$this->mLinkHooks[$ns] = array( $callback, $flags );
		return $oldVal;
	}
	
	/**
	 * Get all registered link hook identifiers
	 *
	 * @return array
	 */
	function getLinkHooks() {
		return array_keys( $this->mLinkHooks );
	}
	
	/**
	 * Process [[ ]] wikilinks
	 * @return LinkHolderArray
	 *
	 * @private
	 */
	function replaceInternalLinks2( &$s ) {
		wfProfileIn( __METHOD__ );

		wfProfileIn( __METHOD__.'-setup' );
		static $tc = FALSE, $titleRegex;//$e1, $e1_img;
		if( !$tc ) {
			# the % is needed to support urlencoded titles as well
			$tc = Title::legalChars() . '#%';
			# Match a link having the form [[namespace:link|alternate]]trail
			//$e1 = "/^([{$tc}]+)(?:\\|(.+?))?]](.*)\$/sD";
			# Match cases where there is no "]]", which might still be images
			//$e1_img = "/^([{$tc}]+)\\|(.*)\$/sD";
			# Match a valid plain title
			$titleRegex = "/^([{$tc}]+)$/sD";
		}

		$holders = new LinkHolderArray( $this );
		
		if( is_null( $this->mTitle ) ) {
			wfProfileOut( __METHOD__ );
			wfProfileOut( __METHOD__.'-setup' );
			throw new MWException( __METHOD__.": \$this->mTitle is null\n" );
		}

		wfProfileOut( __METHOD__.'-setup' );
		
		$offset = 0;
		$offsetStack = array();
		$markers = new LinkMarkerReplacer( $this, $holders, array( &$this, 'replaceInternalLinksCallback' ) );
		while( true ) {
			$startBracketOffset = strpos( $s, '[[', $offset );
			$endBracketOffset   = strpos( $s, ']]', $offset );
			# Finish when there are no more brackets
			if( $startBracketOffset === false && $endBracketOffset === false ) break;
			# Determine if the bracket is a starting or ending bracket
			# When we find both, use the first one
			elseif( $startBracketOffset !== false && $endBracketOffset !== false )
			     $isStart = $startBracketOffset <= $endBracketOffset;
			# When we only found one, check which it is
			else $isStart = $startBracketOffset !== false;
			$bracketOffset = $isStart ? $startBracketOffset : $endBracketOffset;
			if( $isStart ) {
				/** Opening bracket **/
				# Just push our current offset in the string onto the stack
				$offsetStack[] = $startBracketOffset;
			} else {
				/** Closing bracket **/
				# Pop the start pos for our current link zone off the stack
				$startBracketOffset = array_pop($offsetStack);
				# Just to clean up the code, lets place offsets on the outer ends
				$endBracketOffset += 2;
				
				# Only do logic if we actually have a opening bracket for this
				if( isset($startBracketOffset) ) {
					# Extract text inside the link
					@list( $titleText, $paramText ) = explode('|',
						substr($s, $startBracketOffset+2, $endBracketOffset-$startBracketOffset-4), 2);
					# Create markers only for valid links
					if( preg_match( $titleRegex, $titleText ) ) {
						# Store the text for the marker
						$marker = $markers->addMarker($titleText, $paramText);
						# Replace the current link with the marker
						$s = substr($s,0,$startBracketOffset).
							$marker.
							substr($s, $endBracketOffset);
						# We have modified $s, because of this we need to set the
						# offset manually since the end position is different now
						$offset = $startBracketOffset+strlen($marker);
						continue;
					}
					# ToDo: Some LinkHooks may allow recursive links inside of
					# the link text, create a regex that also matches our
					# <!-- LINKMARKER ### --> sequence in titles
					# ToDO: Some LinkHooks use patterns rather than namespaces
					# these need to be tested at this point here
				}
				
			}
			# Bump our offset to after our current bracket
			$offset = $bracketOffset+2;
		}
		
		
		# Now expand our tree
		wfProfileIn( __METHOD__.'-expand' );
		$s = $markers->expand( $s );
		wfProfileOut( __METHOD__.'-expand' );
		
		wfProfileOut( __METHOD__ );
		return $holders;
	}
	
	function replaceInternalLinksCallback( $parser, $holders, $markers, $titleText, $paramText ) {
		wfProfileIn( __METHOD__ );
		$wt = isset($paramText) ? "[[$titleText|$paramText]]" : "[[$titleText]]";
		wfProfileIn( __METHOD__."-misc" );
		# Don't allow internal links to pages containing
		# PROTO: where PROTO is a valid URL protocol; these
		# should be external links.
		if( preg_match('/^\b(?:' . wfUrlProtocols() . ')/', $titleText) ) {
			wfProfileOut( __METHOD__ );
			return $wt;
		}
		
		# Make subpage if necessary
		if( $this->areSubpagesAllowed() ) {
			$titleText = $this->maybeDoSubpageLink( $titleText, $paramText );
		}
		
		# Check for a leading colon and strip it if it is there
		$leadingColon = $titleText[0] == ':';
		if( $leadingColon ) $titleText = substr( $titleText, 1 );
		
		wfProfileOut( __METHOD__."-misc" );
		# Make title object
		wfProfileIn( __METHOD__."-title" );
		$title = Title::newFromText( $this->mStripState->unstripNoWiki($titleText) );
		if( !$title ) {
			wfProfileOut( __METHOD__."-title" );
			wfProfileOut( __METHOD__ );
			return $wt;
		}
		$ns = $title->getNamespace();
		wfProfileOut( __METHOD__."-title" );
		
		# Default for Namespaces is a default link
		# ToDo: Default for patterns is plain wikitext
		$return = true;
		if( isset($this->mLinkHooks[$ns]) ) {
			list( $callback, $flags ) = $this->mLinkHooks[$ns];
			if( $flags & SLH_PATTERN ) {
				$args = array( $parser, $holders, $markers, $titleText, &$paramText, &$leadingColon );
			} else {
				$args = array( $parser, $holders, $markers, $title, $titleText, &$paramText, &$leadingColon );
			}
			# Workaround for PHP bug 35229 and similar
			if ( !is_callable( $callback ) ) {
				throw new MWException( "Tag hook for $name is not callable\n" );
			}
			$return = call_user_func_array( $callback, $args );
		}
		if( $return === true ) {
			# True (treat as plain link) was returned, call the defaultLinkHook
			$args = array( $parser, $holders, $markers, $title, $titleText, &$paramText, &$leadingColon );
			$return = call_user_func_array( array( 'CoreLinkFunctions', 'defaultLinkHook' ), $args );
		}
		if( $return === false ) {
			# False (no link) was returned, output plain wikitext
			# Build it again as the hook is allowed to modify $paramText
			return isset($paramText) ? "[[$titleText|$paramText]]" : "[[$titleText]]";
		}
		# Content was returned, return it
		return $return;
	}
	
}

class LinkMarkerReplacer {
	
	protected $markers, $nextId, $parser, $holders, $callback;
	
	function __construct( $parser, $holders, $callback ) {
		$this->nextId   = 0;
		$this->markers  = array();
		$this->parser   = $parser;
		$this->holders  = $holders;
		$this->callback = $callback;
	}
	
	function addMarker($titleText, $paramText) {
		$id = $this->nextId++;
		$this->markers[$id] = array( $titleText, $paramText );
		return "<!-- LINKMARKER $id -->";
	}
	
	function findMarker( $string ) {
		return (bool) preg_match('/<!-- LINKMARKER [0-9]+ -->/', $string );
	}
	
	function expand( $string ) {
		return StringUtils::delimiterReplaceCallback( "<!-- LINKMARKER ", " -->", array( &$this, 'callback' ), $string );
	}
	
	function callback( $m ) {
		$id = intval($m[1]);
		if( !array_key_exists($id, $this->markers) ) return $m[0];
		$args = $this->markers[$id];
		array_unshift( $args, $this );
		array_unshift( $args, $this->holders );
		array_unshift( $args, $this->parser );
		return call_user_func_array( $this->callback, $args );
	}
	
}
