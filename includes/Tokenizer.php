<?php
/**
 *
 * @package MediaWiki
 */

/**
 *
 * @package MediaWiki
 */
class Tokenizer {
	/* private */ var $mText, 		# Text to be processed by the tokenizer
			  $mPos,		# current position of tokenizer in text
			  $mTextLength,		# Length of $mText
			  $mQueuedToken;	# Tokens that were already found, but not
			  			# returned yet.

	/**
	 * Constructor
	 * @access private
	 */
	function Tokenizer() {
		global $wgContLang;

		$this->mPos=0;
		$this->mTokenQueue=array();
		$this->linkPrefixExtension = $wgContLang->linkPrefixExtension();
	}

	/**
	 * factory function
	 */
	function newFromString( $s ) {
		$fname = 'Tokenizer::newFromString';
		wfProfileIn( $fname );

		$t = new Tokenizer();
		$t->mText = $s;
		$t->mTextLength = strlen( $s );

		wfProfileOut( $fname );
		return $t;
	}


	/**
	 * Return the next token, but do not increase the pointer. The next call
	 * to previewToken or nextToken will return the same token again.
	 * Actually, the pointer is increased, but the token is queued. The next
	 * call to previewToken or nextToken will check the queue and return
	 * the stored token.
	 */
	function previewToken() {
		$fname = 'Tokenizer::previewToken';
		wfProfileIn( $fname );

		if ( count( $this->mQueuedToken ) != 0 ) {
			// still one token from the last round around. Return that one first.
			$token = $this->mQueuedToken[0];
		} else {
			$token = $this->nextToken();
			array_unshift( $this->mQueuedToken, $token );
		}

		wfProfileOut( $fname );
		return $token;
	}


	/**
	 * Get the next token.
	 *
	 * proceeds character by character through the text, looking for characters needing
	 * special attention. Those are currently: I, R, ', [, ], newline
	 *
	 * @todo handling of French blanks not yet implemented
	 */
	function nextToken() {
		$fname = 'Tokenizer::nextToken';
		wfProfileIn( $fname );

		if ( count( $this->mQueuedToken ) != 0 ) {
			// still one token from the last round around. Return that one first.
			$token = array_shift( $this->mQueuedToken );
		} else if ( $this->mPos > $this->mTextLength ) {
		 	// If no text is left, return 'false'.
			$token = false;
		} else {

			$token['text']='';
			$token['type']='text';

			while ( $this->mPos <= $this->mTextLength ) {
				switch ( @$ch = $this->mText[$this->mPos] ) {
					case 'R': // for "RFC "
						if ( $this->continues('FC ') ) {
						     	$queueToken['type'] = $queueToken['text'] = 'RFC ';
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 3;
							break 2; // switch + while 
						}
						break;
					case 'I': // for "ISBN "
						if ( $this->continues('SBN ') ) {
						     	$queueToken['type'] = $queueToken['text'] = 'ISBN ';
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 4;
							break 2; // switch + while
						}
						break;
					case '[': // for links "[["
						if ( $this->continues('[[') ) {
						     	$queueToken['type'] = '[[[';
							$queueToken['text'] = '';
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 3;
							break 2; // switch + while
						} else if ( $this->continues('[') ) {
							$queueToken['type'] = '[[';
							$queueToken['text'] = '';
							// Check for a "prefixed link", e.g. Al[[Khazar]]
							// Mostly for arabic wikipedia
							if ( $this->linkPrefixExtension ) {
								while (    $this->linkPrefixExtension
									&& ($len = strlen( $token['text'] ) ) > 0 
									&& !ctype_space( $token['text'][$len-1] ) )
								{
									//prepend the character to the link's open tag
									$queueToken['text'] = $token['text'][$len-1] . $queueToken['text'];
									//remove character from the end of the text token
									$token['text'] = substr( $token['text'], 0, -1);
								}
							}
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 2;
							break 2; // switch + while 
						}
						break;
					case ']': // for end of links "]]"
						if ( $this->continues(']') ) {
						     	$queueToken['type'] = ']]';
							$queueToken['text'] = '';
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 2;
							break 2; // switch + while 
						}
						break;
					case "'": // for all kind of em's and strong's
						if ( $this->continues("'") ) {
							$queueToken['type'] = "'";
							$queueToken['text'] = '';
							while(   ($this->mPos+1 < $this->mTextLength) 
							       && $this->mText[$this->mPos+1] == "'" )
							{
								$queueToken['type'] .= "'";
								$queueToken['pos'] = $this->mPos;
								$this->mPos ++;
							}
							
							$this->mQueuedToken[] = $queueToken;
							$this->mPos ++;
							break 2; // switch + while
						}
						break;
					case "\n": // for block levels, actually, only "----" is handled.
					case "\r": // headings are detected to close any unbalanced em or strong tags in a section
						if ( $this->continues( '----' ) )
						{
						     	$queueToken['type'] = '----';
							$queueToken['text'] = '';
							$this->mQueuedToken[] = $queueToken;
							$this->mPos += 5;
							while (     $this->mPos<$this->mTextLength 
								and $this->mText[$this->mPos] == '-' )
							{
								$this->mPos ++;
							}
							break 2;
						} else if ( 
							$this->continues( '<h' ) and (
								$this->continues( '<h1' ) or
								$this->continues( '<h2' ) or 
								$this->continues( '<h3' ) or 
								$this->continues( '<h4' ) or 
								$this->continues( '<h5' ) or
								$this->continues( '<h6' ) 
							)
						) { // heading
							$queueToken['type'] = 'h';
							$queueToken['text'] = '';
							$this->mQueuedToken[] = $queueToken;
							$this->mPos ++;
							break 2; // switch + while
						}
						break;
					case '!': // French spacing rules have a space before exclamation
					case '?': // and question marks. Those have to become &nbsp;
					case ':': // And colons, Hashar says ...
						if ( $this->preceeded( ' ' ) )
						{
							// strip blank from Token
							$token['text'] = substr( $token['text'], 0, -1 );
							$queueToken['type'] = 'blank';
							$queueToken['text'] = ' '.$ch;
							$this->mQueuedToken[] = $queueToken;
							$this->mPos ++;
							break 2; // switch + while
						}
						break;
					case '0': // A space between two numbers is used to ease reading
					case '1': // of big numbers, e.g. 1 000 000. Those spaces need
					case '2': // to be unbreakable
					case '3':
					case '4':
					case '5':
					case '6':
					case '7':
					case '8':
					case '9':
						if (    ($this->mTextLength >= $this->mPos +2)
						     && ($this->mText[$this->mPos+1] == ' ')
						     && ctype_digit( $this->mText[$this->mPos+2] ) )
						{
							$queueToken['type'] = 'blank';
							$queueToken['text'] = $ch . ' ';
							$this->mQueuedToken[] = $queueToken;
							$this->mPos += 2;
							break 2; // switch + while
						}
						break;
					case "\302": // first byte of UTF-8 Character Guillemet-left
						if ( $this->continues( "\253 ") ) // second byte and a blank
						{
							$queueToken['type'] = 'blank';
							$queueToken['text'] = "\302\253 ";
							$this->mQueuedToken[] = $queueToken;
							$this->mPos += 3;
							break 2; // switch + while
						}
						break;
					case "\273": //last byte of UTF-8 Character Guillemet-right
						if ( $this->preceeded( " \302" ) )
						{
							$queueToken['type'] = 'blank';
							$queueToken['text'] = " \302\273";
							$token['text'] = substr( $token['text'], 0, -2 );
							$this->mQueuedToken[] = $queueToken;
							$this->mPos ++;
							break 2; // switch + while
						}
						break;
					case '&': //extensions like <timeline>, since HTML stripping has already been done, 
					 	  //those look like &lt;timeline&gt;
						if ( $this->continues( 'lt;timeline&gt;' ) )
						{
							$queueToken['type'] = '<timeline>';
							$queueToken['text'] = '&lt;timeline&gt;';
							$this->mQueuedToken[] = $queueToken;
							$this->mPos += 16;
							break 2; // switch + while
						}
						break;

				} /* switch */
				$token['text'].=$ch;
				$this->mPos ++;
				// echo $this->mPos . "<br>\n"; 
			} /* while */
		} /* if (nothing left in queue) */
	
		wfProfileOut( $fname );
		return $token;
	}

	/**
	 * function continues
	 *
	 * checks whether the mText continues with $cont from mPos+1
	 *
	 * @access private
	 */
	function continues( $cont ) {
		// If string is not long enough to contain $cont, return false
		if ( $this->mTextLength < $this->mPos + strlen( $cont ) )
			return false;
		for ( $i=0; $i < strlen( $cont ); $i++ )
		{
			if ( $this->mText[$this->mPos+1+$i] != $cont[$i] )
				return false;
		}
		return true;
	}

	/**
	 * function preceeded
	 *
	 * checks whether the mText is preceeded by $prec at position mPos
	 *
	 * @access private
	 */
	function preceeded( $prec ) {
		$len = strlen( $prec );
		// if $prec is longer than the text up to mPos, return false
		if ( $this->mPos < $len )
			return false;
		return ( 0 == strcmp( $prec, substr($this->mText, $this->mPos-$len, $len) ) );
	}

	/**
	 *
	 */
	function readAllUntil( $border ) {
		$n = strpos( $this->mText, $border, $this->mPos );
		if ( $n === false )
			return '';
		$ret = substr( $this->mText, $this->mPos, $n - $this->mPos );
		$this->mPos = $n + strlen( $border ) + 1;
		return $ret;
	}

}
