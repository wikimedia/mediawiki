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
		global $wgLang;

		# build up the regex, step by step.
		# Basic features: Quotes for <em>/<strong> and hyphens for <hr>
		$regex = "\'\'\'\'\'|\'\'\'|\'\'|\n-----*";
		# Append regex for linkPrefixExtension 
		if (  $wgLang->linkPrefixExtension() ) {
			$regex .= "|([a-zA-Z\x80-\xff]+)\[\[";
		} else {
			$regex .= "|\[\[";
		}
		# Closing link
		$regex .= "|\]\]";
		# Magic words that automatically generate links
		$regex .= "|ISBN |RFC ";
		# Language-specific additions
		$regex .= $wgLang->tokenizerRegex();
		# Finalize regex
		$regex = "/(" . $regex . ")/";

		# Apply the regex to the text
		$this->mCount = preg_match_all( $regex, $this->mText, $this->mMatch,
						PREG_PATTERN_ORDER|PREG_OFFSET_CAPTURE);
		$this->mMatchPos=0;
	}

	function nextToken()
	{
		$token = $this->previewToken();
		if ( $token ) {
			$this->mMatchPos = $token["mMatchPos"];
			$this->mPos = $token["mPos"];
		}
		return $token;
	}


	function previewToken()
	{
		if ( $this->mMatchPos < $this->mCount  ) {
			$token["pos"] = $this->mPos;
			if ( $this->mPos < $this->mMatch[0][$this->mMatchPos][1] ) {
				$token["type"] = "text";
				$token["text"] = substr( $this->mText, $this->mPos,
							 $this->mMatch[0][$this->mMatchPos][1] - $this->mPos );
				# What the pointers would change to if this would not just be a preview
				$token["mMatchPos"] = $this->mMatchPos; 
				$token["mPos"] = $this->mMatch[0][$this->mMatchPos][1];
			} else {
				# If linkPrefixExtension is set,  $this->mMatch[2][$this->mMatchPos][0]
				# contains the link prefix, or is null if no link prefix exist.
				if ( isset( $this->mMatch[2] ) && $this->mMatch[2][$this->mMatchPos][0] )
				{
					# prefixed link open tag, [0] is "prefix[["
					$token["type"] = "[[";
					$token["text"] = $this->mMatch[2][$this->mMatchPos][0]; # the prefix
				} else {
					$token["type"] = $this->mMatch[0][$this->mMatchPos][0];
					if ( substr($token["type"],1,4) == "----" )
					{
						# any number of hyphens bigger than four is a <HR>. 
						# strip down to four.
						$token["type"]="----";
					}
				}
				# What the pointers would change to if this would not just be a preview
				$token["mPos"] = $this->mPos + strlen( $this->mMatch[0][$this->mMatchPos][0] );
				$token["mMatchPos"] = $this->mMatchPos + 1;
			}
		} elseif ( $this->mPos < $this->mTextLength ) {
			$token["type"] = "text";
			$token["text"] = substr( $this->mText, $this->mPos );
			# What the pointers would change to if this would not just be a preview
			$token["mPos"] = $this->mTextLength;
			$token["mMatchPos"] = $this->mMatchPos;
		} else {
			$token = FALSE;
		}
		return $token;
	}

		
}
		
