<?php
class Tokenizer {
	/* private */ var $mText, 		# Text to be processed by the tokenizer
			  $mPos,		# current position of tokenizer in text
			  $mTextLength,		# Length of $mText
			  $mQueuedToken;	# Tokens that were already found, but not
			  			# returned yet.

	/* private */ function Tokenizer()
	{
		$this->mPos=0;
		$this->mTokenQueue=array();
	}

	# factory function
	function newFromString( $s )
	{
		$fname = "Tokenizer::newFromString";
		wfProfileIn( $fname );

		$t = new Tokenizer();
		$t->mText = $s;
		$t->mTextLength = strlen( $s );

		wfProfileOut( $fname );
		return $t;
	}


	// Return the next token, but do not increase the pointer. The next call
	// to previewToken or nextToken will return the same token again.
	// Actually, the pointer is increased, but the token is queued. The next
	// call to previewToken or nextToken will check the queue and return
	// the stored token.
	function previewToken()
	{
		$fname = "Tokenizer::previewToken";
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


	// get the next token
	// proceeds character by character through the text, looking for characters needing
	// special attention. Those are currently: I, R, ', [, ], newline
	//
	// TODO: prefixed links for Arabic wikipedia not implemented yet
	//       handling of French blanks not yet implemented
	function nextToken()
	{
		$fname = "Tokenizer::nextToken";
		wfProfileIn( $fname );

		if ( count( $this->mQueuedToken ) != 0 ) {
			// still one token from the last round around. Return that one first.
			$token = array_shift( $this->mQueuedToken );
		} else if ( $this->mPos > $this->mTextLength )
		{	// If no text is left, return "false".
			$token = false;
		} else {

			$token["text"]="";
			$token["type"]="text";

			while ( $this->mPos <= $this->mTextLength ) {
				switch ( @$ch = $this->mText[$this->mPos] ) {
					case 'R': // for "RFC "
						if ( $this->continues("FC ") ) {
						     	$queueToken["type"] = $queueToken["text"] = "RFC ";
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 3;
							break 2; // switch + while 
						}
						break;
					case 'I': // for "ISBN "
						if ( $this->continues("SBN ") ) {
						     	$queueToken["type"] = $queueToken["text"] = "ISBN ";
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 4;
							break 2; // switch + while
						}
						break;
					case "[": // for links "[["
						if ( $this->continues("[[") ) {
						     	$queueToken["type"] = "[[[";
							$queueToken["text"] = "";
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 3;
							break 2; // switch + while
						} else if ( $this->continues("[") ) {
						     	$queueToken["type"] = "[[";
							$queueToken["text"] = "";
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 2;
							break 2; // switch + while 
						}
						break;
					case "]": // for end of links "]]"
						if ( $this->continues("]") ) {
						     	$queueToken["type"] = "]]";
							$queueToken["text"] = "";
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 2;
							break 2; // switch + while 
						}
						break;
					case "'": // for all kind of em's and strong's
						if ( $this->continues("'") ) {
							$queueToken["type"] = "'";
							$queueToken["text"] = "";
							while(   ($this->mPos+1 < $this->mTextLength) 
							       && $this->mText[$this->mPos+1] == "'" )
							{
								$queueToken["type"] .= "'";
								$this->mPos ++;
							}
							
							$this->mQueuedToken[] = $queueToken;
							$this->mPos ++;
							break 2; // switch + while
						}
						break;
					case "\n": // for block levels, actually, only "----" is handled.
					case "\r":
						if ( $this->continues( "----" ) )
						{
						     	$queueToken["type"] = "----";
							$queueToken["text"] = "";
							$this->mQueuedToken[] = $queueToken;
							$this->mPos += 5;
							while (     $this->mPos<$this->mTextLength 
								and $this->mText[$this->mPos] == "-" )
							{
								$this->mPos ++;
							}
							break 2;
						}
				} /* switch */
				$token["text"].=$ch;
				$this->mPos ++;
				// echo $this->mPos . "<br>\n"; 
			} /* while */
		} /* if (nothing left in queue) */
	
		wfProfileOut( $fname );
		return $token;
	}

	// function continues
	// checks whether the mText continues with $cont from mPos+1
	function continues( $cont )
	{
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
		
}
		
