<?php

/**
 * PHP version of the callback template processor
 * This is currently used as a test rig and is likely to be used for 
 * compatibility purposes later, where the C++ extension is not available.
 *
 * Template syntax
 * ---------------
 *
 * There are two modes: text mode and function mode. The brace characters "{" 
 * and "}" are the only reserved characters. Either one of them will switch from
 * text mode to function mode wherever they appear, no exceptions. 
 *
 * In text mode, all characters are passed through to the output. In function
 * mode, text is split into tokens, delimited either by whitespace or by 
 * matching pairs of braces. The first token is taken to be a function name. The
 * other tokens are first processed in function mode themselves, then they are 
 * passed to the named function as parameters. The return value of the function
 * is passed through to the output.
 *
 * Example:
 *    {escape {"hello"}}
 *
 * First brace switches to function mode. The function name is escape, the first
 * and only parameter is {"hello"}. This parameter is executed. The braces around
 * the parameter cause the parser to switch to text mode, thus the string "hello",
 * including the quotes, is passed back and used as an argument to the escape 
 * function. 
 *
 * Example:
 *    {if title {<h1>{title}</h1>}}
 *
 * The function name is "if". The first parameter is the result of calling the 
 * function "title". The second parameter is a level 1 HTML heading containing
 * the result of the function "title". "if" is a built-in function which will 
 * return the second parameter only if the first is non-blank, so the effect of
 * this is to return a heading element only if a title exists.
 *
 * As a shortcut for generation of HTML attributes, if a function mode segment is
 * surrounded by double quotes, quote characters in the return value will be 
 * escaped. This only applies if the quote character immediately precedes the 
 * opening brace, and immediately follows the closing brace, with no whitespace.
 *
 * User callback functions are defined by passing a function object to the 
 * template processor. Function names appearing in the text are first checked
 * against built-in function names, then against the method names in the function
 * object. The function object forms a sandbox for execution of the template, so 
 * security-conscious users may wish to avoid including functions that allow
 * arbitrary filesystem access or code execution.
 *
 * The callback function will receive its parameters as strings. If the 
 * result of the function depends only on the arguments, and certain things 
 * understood to be "static", such as the source code, then the callback function
 * should return a string. If the result depends on other things, then the function
 * should return an object:
 *
 *    return new TplValue( $text, $deps );
 *
 * where $deps is an array of string tokens, each one naming a dependency. As a 
 * shortcut, if there is only one dependency, $deps may be a string.
 * 
 */


define( 'TP_WHITE', " \t\r\n" );
define( 'TP_BRACE', '{}' );
define( 'TP_DELIM', TP_WHITE . TP_BRACE );
define( 'TP_DEBUG', 0 );

/**
 * Attempting to be a MediaWiki-independent module
 */
if ( !function_exists( 'wfProfileIn' ) ) {
	function wfProfileIn() {}
}
if ( !function_exists( 'wfProfileOut' ) ) {
	function wfProfileOut() {}
}

/**
 * Escape text for inclusion in template
 */
function templateEscape( $text ) {
	return strtr( $text, array( '{' => '{[}', '}' => '{]}' ) );
}

/**
 * A dependency-tracking value class
 * Callback functions should return one of these, unless they have 
 * no dependencies in which case they can return a string.
 */ 
class TplValue {
	var $mText, $mDeps, $mIsTemplate;

	/**
	 * Create a new value
	 * @param string $text
	 * @param array $deps What this value depends on
	 * @param bool $isTemplate whether the result needs compilation/execution
	 */
	function TplValue( $text = '', $deps = array(), $isTemplate = false ) {
		$this->mText = $text;
		if ( !is_array( $deps ) ) {
			$this->mDeps = array( $deps ) ;
		} else {
			$this->mDeps = $deps;
		}
		$this->mIsTemplate = $isTemplate;
	}

	/** Concatenate two values, merging their dependencies */
	function cat( $val ) {
		if ( is_object( $val ) ) {
			$this->addDeps( $val );
			$this->mText .= $val->mText;
		} else {
			$this->mText .= $val;
		}
	}

	/** Add the dependencies of another value to this one */
	function addDeps( $values ) {
		if ( !is_array( $values ) ) {
			$this->mDeps = array_merge( $this->mDeps, $values->mDeps );
		} else {
			foreach ( $values as $val ) {
				if ( !is_object( $val ) ) {
					var_dump( debug_backtrace() );
					exit;
				}
				$this->mDeps = array_merge( $this->mDeps, $val->mDeps );
			}
		}
	}

	/** Remove a list of dependencies */
	function removeDeps( $deps ) {
		$this->mDeps = array_diff( $this->mDeps, $deps );
	}

	function setText( $text ) {
		$this->mText = $text;
	}

	function getText() {
		return $this->mText;
	}

	function getDeps() {
		return $this->mDeps;
	}

	/** If the value is a template, execute it */
	function execute( &$processor ) {
		if ( $this->mIsTemplate ) {
			$myProcessor = new CBTProcessor( $this->mText,  $processor->mFunctionObj, $processor->mIgnorableDeps );
			$myProcessor->mCompiling = $processor->mCompiling;
			$val = $myProcessor->doText( 0, strlen( $this->mText ) );
			$this->mText = $val->mText;
			$this->addDeps( $val );
			if ( !$processor->mCompiling ) {
				$this->mIsTemplate = false;
			}
		}
	}

	/** If the value is plain text, escape it for inclusion in a template */
	function templateEscape() {
		if ( !$this->mIsTemplate ) {
			$this->mText = templateEscape( $this->mText );
		}
	}

	/** Return true if the value has no dependencies */
	function isStatic() {
		return count( $this->mDeps ) == 0;
	}
}

/**
 * Template processor, for compilation and execution
 */
class CBTProcessor {
	var $mText,                     # The text being processed. This is immutable.
		$mFunctionObj,              # The object containing callback functions
		$mCompiling = false,        # True if compiling to a template, false if executing to text
		$mIgnorableDeps = array(),  # Dependency names which should be treated as static
		$mFunctionCache = array(),  # A cache of function results keyed by argument hash

		/** Built-in functions */
		$mBuiltins = array(
		'if'       => 'bi_if',
		'true'     => 'bi_true',
		'['        => 'bi_lbrace',
		'lbrace'   => 'bi_lbrace',
		']'        => 'bi_rbrace',
		'rbrace'   => 'bi_rbrace',
		'escape'   => 'bi_escape',
		'~'        => 'bi_escape',
	);

	/**
	 * Create a template processor for a given text, callback object and static dependency list
	 */
	function CBTProcessor( $text, $functionObj, $ignorableDeps = array() ) {
		$this->mText = $text;
		$this->mFunctionObj = $functionObj;
		$this->mIgnorableDeps = $ignorableDeps;
	}

	/**
	 * Execute the template.
	 * If $compile is true, produces an optimised template where functions with static 
	 * dependencies have been replaced by their return values.
	 */
	function execute( $compile = false ) {
		$fname = 'CBTProcessor::execute';
		wfProfileIn( $fname );
		$this->mCompiling = $compile;
		$this->mLastError = false;
		$val = $this->doText( 0, strlen( $this->mText ) );
		$text = $val->getText();
		if ( $this->mLastError !== false ) {
			$pos = $this->mErrorPos;

			// Find the line number at which the error occurred
			$startLine = 0;
			$endLine = 0;
			$line = 0;
			do {
				if ( $endLine ) {
					$startLine = $endLine + 1;
				}
				$endLine = strpos( $this->mText, "\n", $startLine );
				++$line;
			} while ( $endLine !== false && $endLine < $pos );

			$text = "Template error at line $line: $this->mLastError\n<pre>\n";

			$context = rtrim( str_replace( "\t", " ", substr( $this->mText, $startLine, $endLine - $startLine ) ) );
			$text .= htmlspecialchars( $context ) . "\n" . str_repeat( ' ', $pos - $startLine ) . "^\n</pre>\n";
		} 
		wfProfileOut( $fname );
		return $text;
	}

	/** Shortcut for execute(true) */
	function compile() {
		$fname = 'CBTProcessor::compile';
		wfProfileIn( $fname );
		$s = $this->execute( true );
		wfProfileOut( $fname );
		return $s;
	}

	/** Shortcut for doOpenText( $start, $end, false */
	function doText( $start, $end ) {
		return $this->doOpenText( $start, $end, false );
	}

	/** 
	 * Escape text for a template if we are producing a template. Do nothing
	 * if we are producing plain text.
	 */
	 function templateEscape( $text ) {
		if ( $this->mCompiling ) {
			return templateEscape( $text );
		} else {
			return $text;
		}
	}

	/**
	 * Recursive workhorse for text mode.
	 * 
	 * Processes text mode starting from offset $p, until either $end is 
	 * reached or a closing brace is found. If $needClosing is false, a 
	 * closing brace will flag an error, if $needClosing is true, the lack
	 * of a closing brace will flag an error. 
	 *
	 * The parameter $p is advanced to the position after the closing brace, 
	 * or after the end. A TplValue is returned.
	 *
	 * @private
	 */
	function doOpenText( &$p, $end, $needClosing = true ) {
		$in =& $this->mText;
		$start = $p;
		$ret = new TplValue( '', array(), $this->mCompiling );
		
		$foundClosing = false;
		while ( $p < $end ) {
			$matchLength = strcspn( $in, TP_BRACE, $p, $end - $p );
			$pToken = $p + $matchLength;

			if ( $pToken >= $end ) {
				// No more braces, output remainder
				$ret->cat( substr( $in, $p ) );
				$p = $end;
				break;
			}

			// Output the text before the brace
			$ret->cat( substr( $in, $p, $matchLength ) );

			// Advance the pointer 
			$p = $pToken + 1;
			
			// Check for closing brace
			if ( $in[$pToken] == '}' ) {
				$foundClosing = true;
				break;
			}
			
			// Handle the "{fn}" special case
			if ( $pToken > 0 && $in[$pToken-1] == '"' ) {
				$val = $this->doOpenFunction( $p, $end );
				if ( $p < $end && $in[$p] == '"' ) {
					$val->setText( htmlspecialchars( $val->getText() ) );
				}
				$ret->cat( $val );
			} else {
				// Process the function mode component
				$ret->cat( $this->doOpenFunction( $p, $end ) );
			}
		}
		if ( $foundClosing && !$needClosing ) {
			$this->error( 'Errant closing brace', $p );
		} elseif ( !$foundClosing && $needClosing ) {
			$this->error( 'Unclosed text section', $start );
		}
		return $ret;
	}

	/**
	 * Recursive workhorse for function mode.
	 *
	 * Processes function mode starting from offset $p, until either $end is 
	 * reached or a closing brace is found. If $needClosing is false, a 
	 * closing brace will flag an error, if $needClosing is true, the lack
	 * of a closing brace will flag an error. 
	 *
	 * The parameter $p is advanced to the position after the closing brace, 
	 * or after the end. A TplValue is returned.
	 *
	 * @private
	 */
	function doOpenFunction( &$p, $end, $needClosing = true ) {
		$in =& $this->mText;
		$start = $p;
		$tokens = array();
		$unexecutedTokens = array();

		$foundClosing = false;
		while ( $p < $end ) {
			$char = $in[$p];
			if ( $char == '{' ) {
				// Switch to text mode
				++$p;
				$tokenStart = $p;
				$token = $this->doOpenText( $p, $end );
				$tokens[] = $token;
				$unexecutedTokens[] = '{' . substr( $in, $tokenStart, $p - $tokenStart - 1 ) . '}';
			} elseif ( $char == '}' ) {
				// Block end
				++$p;
				$foundClosing = true;
				break;
			} elseif ( false !== strpos( TP_WHITE, $char ) ) {
				// Whitespace
				// Consume the rest of the whitespace
				$p += strspn( $in, TP_WHITE, $p, $end - $p );
			} else {
				// Token, find the end of it
				$tokenLength = strcspn( $in, TP_DELIM, $p, $end - $p );
				$token = new TplValue( substr( $in, $p, $tokenLength ) );
				// Execute the token as a function if it's not the function name
				if ( count( $tokens ) ) {
					$tokens[] = $this->doFunction( array( $token ), $p );
				} else {
					$tokens[] = $token;
				}
				$unexecutedTokens[] = $token->getText();

				$p += $tokenLength;
			}
		}
		if ( !$foundClosing && $needClosing ) {
			$this->error( 'Unclosed function', $start );
			return '';
		}

		$val = $this->doFunction( $tokens, $start );
		if ( $this->mCompiling && !$val->isStatic() ) {
			$compiled = '';
			$first = true;
			foreach( $tokens as $i => $token ) {
				if ( $first ) {
					$first = false;
				} else {
					$compiled .= ' ';
				}
				if ( $token->isStatic() ) {
					if ( $i !== 0 ) {
						$compiled .= '{' . $token->getText() . '}';
					} else {
						$compiled .= $token->getText();
					}
				} else {
					$compiled .= $unexecutedTokens[$i];
				}
			}

			// The dynamic parts of the string are still represented as functions, and 
			// function invocations have no dependencies. Thus the compiled result has 
			// no dependencies.
			$val = new TplValue( "{{$compiled}}", array(), true );
		}
		return $val;
	}

	/**
	 * Execute a function, caching and returning the result value.
	 * $tokens is an array of TplValue objects. $tokens[0] is the function 
	 * name, the others are arguments. $p is the string position, and is used
	 * for error messages only.
	 */
	function doFunction( $tokens, $p ) {
		if ( count( $tokens ) == 0 ) {
			return new TplValue;
		}
		$fname = 'CBTProcessor::doFunction';
		wfProfileIn( $fname );
		
		$ret = new TplValue;
		
		// All functions implicitly depend on their arguments, and the function name
		// While this is not strictly necessary for all functions, it's true almost 
		// all the time and so convenient to do automatically. 
		$ret->addDeps( $tokens );

		$this->mCurrentPos = $p;
		$func = array_shift( $tokens );
		$func = $func->getText();

		// Extract the text component from all the tokens
		// And convert any templates to plain text
		$textArgs = array();
		foreach ( $tokens as $token ) {
			$token->execute( $this );
			$textArgs[] = $token->getText();
		}

		// Try the local cache
		$cacheKey = $func . "\n" . implode( "\n", $textArgs );
		if ( isset( $this->mFunctionCache[$cacheKey] ) ) {
			$val = $this->mFunctionCache[$cacheKey];
		} elseif ( isset( $this->mBuiltins[$func] ) ) {
			$func = $this->mBuiltins[$func];
			$val = call_user_func_array( array( &$this, $func ), $tokens );
			$this->mFunctionCache[$cacheKey] = $val;
		} elseif ( method_exists( $this->mFunctionObj, $func ) ) {
			$profName = get_class( $this->mFunctionObj ) . '::' . $func;
			wfProfileIn( "$fname-callback" );
			wfProfileIn( $profName );
			$val = call_user_func_array( array( &$this->mFunctionObj, $func ), $textArgs );
			wfProfileOut( $profName );
			wfProfileOut( "$fname-callback" );
			$this->mFunctionCache[$cacheKey] = $val;
		} else {
			$this->error( "Call of undefined function \"$func\"", $p );
			$val = new TplValue;
		}
		if ( !is_object( $val ) ) {
			$val = new TplValue((string)$val);
		}

		if ( TP_DEBUG ) {
			$unexpanded = $val;
		}

		// If the output was a template, execute it
		$val->execute( $this );
		
		if ( $this->mCompiling ) {
			// Escape any braces so that the output will be a valid template
			$val->templateEscape();
		} 
		$val->removeDeps( $this->mIgnorableDeps );
		$ret->addDeps( $val );
		$ret->setText( $val->getText() );

		if ( TP_DEBUG ) {
			print "doFunction $func args = ";
			var_dump( $tokens );
			print "unexpanded return = ";
			var_dump( $unexpanded );
			print "expanded return = ";
			var_dump( $ret );
		}
		
		wfProfileOut( $fname );
		return $ret;
	}

	/**
	 * Set a flag indicating that an error has been found.
	 */
	function error( $text, $pos = false ) {
		$this->mLastError = $text;
		if ( $pos === false ) {
			$this->mErrorPos = $this->mCurrentPos;
		} else {
			$this->mErrorPos = $pos;
		}
	}

	function getLastError() {
		return $this->mLastError;
	}

	/** 'if' built-in function */
	function bi_if( $condition, $trueBlock, $falseBlock = null ) {
		if ( is_null( $condition ) ) {
			$this->error( "Missing condition in if" );
			return '';
		}

		if ( $condition->getText() != '' ) {
			return new TplValue( $trueBlock->getText(), 
				array_merge( $condition->getDeps(), $trueBlock->getDeps() ),
				$trueBlock->mIsTemplate );
		} else {
			if ( !is_null( $falseBlock ) ) {
				return new TplValue( $falseBlock->getText(), 
					array_merge( $condition->getDeps(), $falseBlock->getDeps() ),
		   			$falseBlock->mIsTemplate );
			} else {
				return new TplValue( '', $condition->getDeps() );
			}
		}
	}

	/** 'true' built-in function */
	function bi_true() {
		return "true";
	}

	/** left brace built-in */
	function bi_lbrace() {
		return '{';
	}

	/** right brace built-in */
	function bi_rbrace() {
		return '}';
	}

	/** 
	 * escape built-in.
	 * Escape text for inclusion in an HTML attribute 
	 */
	function bi_escape( $val ) {
		return new TplValue( htmlspecialchars( $val->getText() ), $val->getDeps() );
	}
}
?>
