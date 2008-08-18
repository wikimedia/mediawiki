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
	
	# Flags for Parser::setFunctionHook
	# Also available as global constants from Defines.php
	const SFH_NO_HASH = 1;
	const SFH_OBJECT_ARGS = 2;

	# Constants needed for external link processing
	# Everything except bracket, space, or control characters
	const EXT_LINK_URL_CLASS = '[^][<>"\\x00-\\x20\\x7F]';
	const EXT_IMAGE_REGEX = '/^(http:\/\/|https:\/\/)([^][<>"\\x00-\\x20\\x7F]+)
		\\/([A-Za-z0-9_.,~%\\-+&;#*?!=()@\\x80-\\xFF]+)\\.((?i)gif|png|jpg|jpeg)$/Sx';

	// State constants for the definition list colon extraction
	const COLON_STATE_TEXT = 0;
	const COLON_STATE_TAG = 1;
	const COLON_STATE_TAGSTART = 2;
	const COLON_STATE_CLOSETAG = 3;
	const COLON_STATE_TAGSLASH = 4;
	const COLON_STATE_COMMENT = 5;
	const COLON_STATE_COMMENTDASH = 6;
	const COLON_STATE_COMMENTDASHDASH = 7;

	// Flags for preprocessToDom
	const PTD_FOR_INCLUSION = 1;

	// Allowed values for $this->mOutputType
	// Parameter to startExternalParse().
	const OT_HTML = 1;
	const OT_WIKI = 2;
	const OT_PREPROCESS = 3;
	const OT_MSG = 3;

	// Marker Suffix needs to be accessible staticly.
	const MARKER_SUFFIX = "-QINU\x7f";
	
	/**
	 * Replace unusual URL escape codes with their equivalent characters
	 * @param string
	 * @return string
	 * @static
	 * @todo  This can merge genuinely required bits in the path or query string,
	 *        breaking legit URLs. A proper fix would treat the various parts of
	 *        the URL differently; as a workaround, just use the output for
	 *        statistical records, not for actual linking/output.
	 */
	static function replaceUnusualEscapes( $url ) {
		return preg_replace_callback( '/%[0-9A-Fa-f]{2}/',
			array( __CLASS__, 'replaceUnusualEscapesCallback' ), $url );
	}
	
	/**
	 * Callback function used in replaceUnusualEscapes().
	 * Replaces unusual URL escape codes with their equivalent character
	 * @static
	 * @private
	 */
	private static function replaceUnusualEscapesCallback( $matches ) {
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
	
	/*
	 * Return a three-element array: leading whitespace, string contents, trailing whitespace
	 */
	public static function splitWhitespace( $s ) {
		$ltrimmed = ltrim( $s );
		$w1 = substr( $s, 0, strlen( $s ) - strlen( $ltrimmed ) );
		$trimmed = rtrim( $ltrimmed );
		$diff = strlen( $ltrimmed ) - strlen( $trimmed );
		if ( $diff > 0 ) {
			$w2 = substr( $ltrimmed, -$diff );
		} else {
			$w2 = '';
		}
		return array( $w1, $trimmed, $w2 );
	}
	
	/// Clean up argument array - refactored in 1.9 so parserfunctions can use it, too.
	static function createAssocArgs( $args ) {
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

		return $assocArgs;
	}
	
	/**
	 * Static function to get a template
	 * Can be overridden via ParserOptions::setTemplateCallback().
	 */
	static function statelessFetchTemplate( $title, $parser=false ) {
		$text = $skip = false;
		$finalTitle = $title;
		$deps = array();

		// Loop to fetch the article, with up to 1 redirect
		for ( $i = 0; $i < 2 && is_object( $title ); $i++ ) {
			# Give extensions a chance to select the revision instead
			$id = false; // Assume current
			wfRunHooks( 'BeforeParserFetchTemplateAndtitle', array( $parser, &$title, &$skip, &$id ) );

			if( $skip ) {
				$text = false;
				$deps[] = array(
					'title' => $title,
					'page_id' => $title->getArticleID(),
					'rev_id' => null );
				break;
			}
			$rev = $id ? Revision::newFromId( $id ) : Revision::newFromTitle( $title );
			$rev_id = $rev ? $rev->getId() : 0;
			// If there is no current revision, there is no page
			if( $id === false && !$rev ) {
				$linkCache = LinkCache::singleton();
				$linkCache->addBadLinkObj( $title );
			}

			$deps[] = array(
				'title' => $title,
				'page_id' => $title->getArticleID(),
				'rev_id' => $rev_id );

			if( $rev ) {
				$text = $rev->getText();
			} elseif( $title->getNamespace() == NS_MEDIAWIKI ) {
				global $wgLang;
				$message = $wgLang->lcfirst( $title->getText() );
				$text = wfMsgForContentNoTrans( $message );
				if( wfEmptyMsg( $message, $text ) ) {
					$text = false;
					break;
				}
			} else {
				break;
			}
			if ( $text === false ) {
				break;
			}
			// Redirect?
			$finalTitle = $title;
			$title = Title::newFromRedirect( $text );
		}
		return array(
			'text' => $text,
			'finalTitle' => $finalTitle,
			'deps' => $deps );
	}
	
	/**
	 * Process [[ ]] wikilinks
	 * @return LinkHolderArray
	 *
	 * @private
	 */
	function replaceInternalLinks2( &$s ) {
		global $wgContLang;

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

		$sk = $this->mOptions->getSkin();
		$holders = new LinkHolderArray( $this );
		
		if( is_null( $this->mTitle ) ) {
			wfProfileOut( __METHOD__ );
			wfProfileOut( __METHOD__.'-setup' );
			throw new MWException( __METHOD__.": \$this->mTitle is null\n" );
		}
		$nottalk = !$this->mTitle->isTalkPage();
		
		if($wgContLang->hasVariants()) {
			$selflink = $wgContLang->convertLinkToAllVariants($this->mTitle->getPrefixedText());
		} else {
			$selflink = array($this->mTitle->getPrefixedText());
		}
		wfProfileOut( __METHOD__.'-setup' );
		
		$offset = 0;
		$offsetStack = array();
		$markerReplacer = new LinkMarkerReplacer( array( &$this, 'replaceInternalLinksCallback' ) );
		$markerReplacer->holders( $holders );
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
						$marker = $markerReplacer->addMarker($titleText, $paramText);
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
		$s = $markerReplacer->expand( $s );
		wfProfileOut( __METHOD__.'-expand' );
		
		wfProfileOut( __METHOD__ );
		return $holders;
	}
	
	function replaceInternalLinksCallback( $markerReplacer, $titleText, $paramText ) {
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
		
		$callback = array( 'CoreLinkFunctions', 'defaultLinkHook' );
		$args = array( $markerReplacer, $title, $titleText, &$paramText, &$leadingColon );
		$return = call_user_func_array( $callback, $args );
		if( $return === false ) {
			# False (no link) was returned, output plain wikitext
			# Build it again as the hook is allowed to modify $paramText
			return isset($paramText) ? "[[$titleText|$paramText]]" : "[[$titleText]]";
		} elseif( $return === true ) {
			# True (treat as plain link) was returned, call the defaultLinkHook
			$args = array( $markerReplacer, $title, $titleText, &$paramText, &$leadingColon );
			$return = call_user_func_array( array( &$this, 'defaultLinkHook' ), $args );
		}
		# Content was returned, return it
		return $return;
	}
	
}

class LinkMarkerReplacer {
	
	protected $markers, $nextId, $holders;
	
	function __construct( $callback ) {
		$this->nextId  = 0;
		$this->markers = array();
		$this->callback = $callback;
		$this->holders = null;
	}
	
	# Note: This is a bit of an ugly way to do this. It works for now, but before
	# this feature becomes usable we should come up with a better arg list.
	# $parser, $holders, and $linkMarkers appear to be 3 needed ones
	function holders( $holders = null ) { return wfSetVar( $this->holders, $holders ); }
	
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
		return call_user_func_array( $this->callback, $args );
	}
	
}
