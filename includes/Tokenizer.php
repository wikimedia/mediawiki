<?php
class Tokenizer {
	/* private */ var $mText, 		# Text to be processed by the tokenizer
			  $mPos,		# current position of tokenizer in text
			  $mTextLength,		# Length of $mText
			  $mCount,		# token count, computed in preParse
			  $mMatch,		# matches of tokenizer regex, computed in preParse
			  $mMatchPos;		# current token position of tokenizer. Each match can
			  			# be up to two tokens: A matched token and the text after it.

	/* private */ function Tokenizer()
	{
		$this->mPos=0;
	}

	# factory function
	function newFromString( $s )
	{
		$t = new Tokenizer();
		$t->mText = $s;
		$t->preParse();
		$t->mTextLength = strlen( $s );
		return $t;
	}

	function preParse()
	{
		$this->mCount = preg_match_all( "/(\[\[|\]\]|\'\'\'\'\'|\'\'\'|\'\')/",
						$this->mText, $this->mMatch,
						PREG_PATTERN_ORDER|PREG_OFFSET_CAPTURE);
		$this->mMatchPos=0;
	}

	function nextToken()
	{
		$token = $this->previewToken();
		if ( $token ) {
			if ( $token["type"] == "text" ) {
				$this->mPos = $token["mPos"];
			} else {
				$this->mMatchPos = $token["mMatchPos"];
				$this->mPos = $token["mPos"];
			}
		}
		return $token;
	}


	function previewToken()
	{
		if ( $this->mMatchPos <= $this->mCount  ) {
			$token["pos"] = $this->mPos;
			if ( $this->mPos < $this->mMatch[0][$this->mMatchPos][1] ) {
				$token["type"] = "text";
				$token["text"] = substr( $this->mText, $this->mPos,
							 $this->mMatch[0][$this->mMatchPos][1] - $this->mPos );
				$token["mPos"] = $this->mMatch[0][$this->mMatchPos][1];
			} else {
				$token["type"] = $this->mMatch[0][$this->mMatchPos][0];
				$token["mPos"] = $this->mPos + strlen($token["type"]);
				$token["mMatchPos"] = $this->mMatchPos + 1;
			}
		} elseif ( $this->mPos < $this->mTextLength ) {
			$token["type"] = "text";
			$token["text"] = substr( $this->mText, $this->mPos );
			$token["mPos"] = $this->mTextLength;
		} else {
			$token = FALSE;
		}
		return $token;
	}

		
}
		
