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
		$t = new Tokenizer();
		$t->mText = $s;
		$t->mTextLength = strlen( $s );
		// echo "New tokenizer generated. <pre>{$s}</pre>\n"; 
		return $t;
	}


	// Return the next token, but do not increase the pointer. The next call
	// to previewToken or nextToken will return the same token again.
	// Actually, the pointer is increased, but the token is queued. The next
	// call to previewToken or nextToken will check the queue and return
	// the stored token.
	function previewToken()
	{
		if ( count( $this->mQueuedToken ) != 0 ) {
			// still one token from the last round around. Return that one first.
			$token = $this->mQueuedToken[0];
		} else {
			$token = $this->nextToken();
			array_unshift( $this->mQueuedToken, $token );
		}
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
		if ( count( $this->mQueuedToken ) != 0 ) {
			// still one token from the last round around. Return that one first.
			$token = array_shift( $this->mQueuedToken );
		} else {

			$token["text"]="";
			$token["type"]="text";

			// If no text is left, return "false".
			if ( $this->mPos > $this->mTextLength )
				return false;

			while ( $this->mPos <= $this->mTextLength ) {
				switch ( @$ch = $this->mText[$this->mPos] ) {
					case 'R': // for "RFC "
						if ( isset($this->mText[$this->mPos+1]) && $this->mText[$this->mPos+1] == 'F' &&
                                                isset($this->mText[$this->mPos+2]) && $this->mText[$this->mPos+2] == 'C' &&
                                                isset($this->mText[$this->mPos+4]) && $this->mText[$this->mPos+4] == ' ' ) {
						     	$queueToken["type"] = $queueToken["text"] = "RFC ";
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 3;
							break 2; // switch + while 
						}
						break;
					case 'I': // for "ISBN "
						if ( $this->mText[$this->mPos+1] == 'S' &&
					     	$this->mText[$this->mPos+2] == 'B' &&
					     	$this->mText[$this->mPos+3] == 'N' &&
					     	$this->mText[$this->mPos+4] == ' ' ) {
						     	$queueToken["type"] = $queueToken["text"] = "ISBN ";
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 4;
							break 2; // switch + while
						}
						break;
					case "[": // for links "[["
						if ( $this->mText[$this->mPos+1] == "[" &&
					     	     $this->mText[$this->mPos+2] == "[" ) {
						     	$queueToken["type"] = "[[[";
							$queueToken["text"] = "";
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 3;
							break 2; // switch + while
						} else if ( $this->mText[$this->mPos+1] == "[" ) {
						     	$queueToken["type"] = "[[";
							$queueToken["text"] = "";
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 2;
							break 2; // switch + while 
						}
						break;
					case "]": // for end of links "]]"
						if ( $this->mText[$this->mPos+1] == "]" ) {
						     	$queueToken["type"] = "]]";
							$queueToken["text"] = "";
							$this->mQueuedToken[] = $queueToken;
					     		$this->mPos += 2;
							break 2; // switch + while 
						}
						break;
					case "'": // for all kind of em's and strong's
						if ( $this->mText[$this->mPos+1] == "'" ) {
							$queueToken["type"] = "'";
							$queueToken["text"] = "";
							while(isset($this->mText[$this->mPos+1]) && $this->mText[$this->mPos+1] == "'" ) {
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
						if ( isset($this->mText[$this->mPos+4]) &&
						     $this->mText[$this->mPos+1] == "-" &&
						     $this->mText[$this->mPos+2] == "-" &&
						     $this->mText[$this->mPos+3] == "-" &&
						     $this->mText[$this->mPos+4] == "-" ) {
						     	$queueToken["type"] = "----";
							$queueToken["text"] = "";
							$this->mQueuedToken[] = $queueToken;
							$this->mPos += 5;
							while (isset($this->mText[$this->mPos]) and $this->mText[$this->mPos] == "-" ) {
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
		return $token;
	}

		
}
		
