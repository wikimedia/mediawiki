<?php
class Tokenizer {
	/* private */ var $mText, $mPos, $mTextLength;
	/* private */ var $mCount, $mM, $mMPos;

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
						$this->mText, $this->mM,
						PREG_PATTERN_ORDER|PREG_OFFSET_CAPTURE);
		$this->mMPos=0;
	}

	function nextToken()
	{
		$token = $this->previewToken();
		if ( $token ) {
			if ( $token["type"] == "text" ) {
				$this->mPos = $token["mPos"];
			} else {
				$this->mMPos = $token["mMPos"];
				$this->mPos = $token["mPos"];
			}
		}
		return $token;
	}


	function previewToken()
	{
		if ( $this->mMPos <= $this->mCount  ) {
			$token["pos"] = $this->mPos;
			if ( $this->mPos < $this->mM[0][$this->mMPos][1] ) {
				$token["type"] = "text";
				$token["text"] = substr( $this->mText, $this->mPos,
							 $this->mM[0][$this->mMPos][1] - $this->mPos );
				$token["mPos"] = $this->mM[0][$this->mMPos][1];
			} else {
				$token["type"] = $this->mM[0][$this->mMPos][0];
				$token["mPos"] = $this->mPos + strlen($token["type"]);
				$token["mMPos"] = $this->mMPos + 1;
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
		
