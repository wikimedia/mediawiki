<?php
/**
 * Definitions of wfMsg and all it's incarnations. $key refers to the unique key
 * of the message. Most of the messages are defined in
 * $IP/languages/messages/MessagesEn.php with default values.
 *
 * Some function accept $language as parameter. It can be either be a language
 * code, one of the array keys returned by Language::getLanguageNames(); or bool
 * in which case true is shortcut for content language and false for interface
 * language.
 *
 * Most functions take parameters for the message in variable argument list
 * after defined parameters.
 *
 * Some functions do "transforming", which means {{..}} items are substituted.
 * These include magic words for plural and grammar function. It is important
 * to call the right function, so that these function will produce the correct
 * results.
 *
 * To produce correct results two things need to be taken care of. The language
 * information must be passed, so that correct language is called for the
 * substition. Another thing is that variables must be substited before doing
 * this process.
 * @file
 */


/**
 * Equivalent to: wfMsgExt( $key, 'parsemag' );
 * Use cases: Getting interface messages that are later passed to functions that
 * escape their input.
 *
 * Use wfMsgForContent() instead if the message should NOT
 * change depending on the user preferences.
 */
function wfMsg( $key ) {
	$args = func_get_args();
	array_shift( $args );

	$message = MessageGetter::get( $key );
	$message = MessageGetter::replaceArgs( $message, $args );
	$message = MessageGetter::transform( $message );
	return $message;
}

/**
 * Equivalent to: wfMsgExt( $key );
 * Use cases: Getting interface messages that are later passed to function that
 * parse their contents. Make sure that the function does know the correct
 * language.
 */
function wfMsgNoTrans( $key ) {
	$args = func_get_args();
	array_shift( $args );

	$message = MessageGetter::get( $key );
	$message = MessageGetter::replaceArgs( $message, $args );
	return $message;
}

/**
 * Use cases: Message that should NOT change dependent on the language set in
 * the user's preferences. This is the case for most text written into logs, as
 * well as link targets (such as the name of the copyright policy page) and else
 * that goes back into the database. Link titles, on the other hand, should be
 * shown in the UI language.
 */
function wfMsgForContent( $key ) {
	$args = func_get_args();
	array_shift( $args );

	$content = MessageGetter::forContentLanguage( $key );
	$message = MessageGetter::get( $key, /*Language*/ $content );
	$message = MessageGetter::replaceArgs( $message, $args );
	$message = MessageGetter::transform( $message, /*Langugage*/ $content );
	return $message;
}

/**
 * Use cases: Messages for content language that are later passed to a function
 * that parses it. Make sure the function uses the correct language for parsing.
 * Or just for getting the raw message without conversions.
 */
function wfMsgForContentNoTrans( $key ) {
	$args = func_get_args();
	array_shift( $args );

	$content = MessageGetter::forContentLanguage( $key );
	$message = MessageGetter::get( $key, /*Language*/ $content );
	$message = MessageGetter::replaceArgs( $message, $args );
	return $message;
}

/**
 * Use cases: Getting messages when the database is not available. Also used in
 * Special:Allmessages.
 */
function wfMsgNoDB( $key ) {
	$args = func_get_args();
	array_shift( $args );

	$message = MessageGetter::get( $key, MessageGetter::LANG_UI, /*DB*/false );
	$message = MessageGetter::replaceArgs( $message, $args );
	$message = MessageGetter::transform( $message );
	return $message;
}

/**
 * Use cases: Getting the unmodified message when database is not available,
 * perhaps for later parsing.
 */
function wfMsgNoDBForContent( $key ) {
	$args = func_get_args();
	array_shift( $args );

	$message = MessageGetter::get( $key, /*Language*/ $content, /*DB*/false );
	$message = MessageGetter::replaceArgs( $message, $args );
	return $message;
}


/**
 * Use cases: Getting messages in different languages.
 */
function wfMsgReal( $key, $args, $useDB = true, $language = MessageGetter::LANG_UI, $transform = true ) {
	$message = MessageGetter::get( $key, /*Language*/ $language, /*DB*/ $useDB );
	$message = MessageGetter::replaceArgs( $message, $args );
	if ( $transform )
		$message = MessageGetter::transform( $message, /*Langugage*/ $language );
	return $message;
}

/**
 * Use cases: Getting the message content or empty string if it doesn't exist
 * for showing as the default value when editing MediaWiki namespace.
 */
function wfMsgWeirdKey ( $key ) {
	$message = MessageGetter::get( $key, MessageGetter::LANG_UI, /*DB*/ false );
	return wfEmptyMsg( $key, $message ) ? '' : $message;
}


// Private marked
function wfMsgGetKey( $key, $useDB, $language = MessageGetter::LANG_UI, $transform = true ) {
	//wfDeprecated( __METHOD__ );
	$message = MessageGetter::get( $key, $language, $useDB );

	// Plural and grammar will go wrong here, no arguments replaced
	if ( $transform ) {
		wfDebug( __METHOD__ . " called with transform = true for key $key\n" );
		$message = MessageGetter::transform( $message, $language );
	}

	return $message;
}

function wfMsgReplaceArgs( $message, $args ) {
	//wfDeprecated( __METHOD__ );
	return MessageGetter::replaceArgs( $message, $args );
}

/**
 * Return an HTML-escaped version of a message.
 * Parameter replacements, if any, are done *after* the HTML-escaping,
 * so parameters may contain HTML (eg links or form controls). Be sure
 * to pre-escape them if you really do want plaintext, or just wrap
 * the whole thing in htmlspecialchars().
 */
function wfMsgHtml( $key ) {
	$args = func_get_args();
	array_shift( $args );

	$message = MessageGetter::get( $key );
	$message = MessageGetter::escapeHtml( $message, /* Entities */ false );
	$message = MessageGetter::replaceArgs( $message, $args );
	return $message;
}

/**
 * Return an HTML version of message
 * Parameter replacements, if any, are done *after* parsing the wiki-text message,
 * so parameters may contain HTML (eg links or form controls). Be sure
 * to pre-escape them if you really do want plaintext, or just wrap
 * the whole thing in htmlspecialchars().
 */
function wfMsgWikiHtml( $key ) {
	$args = func_get_args();
	array_shift( $args );

	$message = MessageGetter::get( $key );
	$message = MessageGetter::parse( $message );
	$message = MessageGetter::replaceArgs( $message, $args );
	return $message;
}

/**
 * Use cases: When the previous just aren't enough.
 * @param $key String: Key of the message
 * @param $options Array: Processing rules:
 * @param $... Arguments
 *  <i>parse</i>: parses wikitext to html
 *  <i>parseinline</i>: parses wikitext to html and removes the surrounding p's added by parser or tidy
 *  <i>escape</i>: filters message through htmlspecialchars
 *  <i>escapenoentities</i>: same, but allows entity references like &nbsp; through
 *  <i>replaceafter</i>: parameters are substituted after parsing or escaping
 *  <i>parsemag</i>: transform the message using magic phrases
 *  <i>content</i>: fetch message for content language instead of interface
 *  <i>language</i>: language code to fetch message for (overriden by <i>content</i>), its behaviour
 *                   with parse, parseinline and parsemag is undefined.
 * Behavior for conflicting options (e.g., parse+parseinline) is undefined.
 */
function wfMsgExt( $key, $options ) {
	$args = func_get_args();
	array_shift( $args );
	array_shift( $args );

	if( !is_array($options) ) {
		$options = array($options);
	}

	$language = MessageGetter::LANG_UI;

	if( in_array('content', $options) ) {
		$language = MessageGetter::LANG_CONTENT;
	} elseif( array_key_exists('language', $options) ) {
		$language = $options['language'];
		$validCodes = array_keys( Language::getLanguageNames() );
		if( !in_array($language, $validCodes) ) {
			# Fallback to en, instead of whatsever interface language we might have
			$language = 'en';
		}
	}

	$message = MessageGetter::get( $key, $language );

	if( !in_array('replaceafter', $options) ) {
		$message = MessageGetter::replaceArgs( $message, $args );
	}

	if( in_array('parse', $options) ) {
		$message = MessageGetter::parse( $message, $language );
	} elseif ( in_array('parseinline', $options) ) {
		$message = MessageGetter::parse( $message, $language, /*inline*/true );
	} elseif ( in_array('parsemag', $options) ) {
		$message = MessageGetter::transform( $message, $language );
	}

	if ( in_array('escape', $options) ) {
		$message = MessageGetter::escapeHtml( $message, /*allowEntities*/false );
	} elseif ( in_array( 'escapenoentities', $options ) ) {
		$message = MessageGetter::escapeHtml( $message );
	}

	if( in_array('replaceafter', $options) ) {
		$message = MessageGetter::replaceArgs( $message, $args );
	}

	return $message;
}

class MessageGetter {

	const LANG_UI = false;
	const LANG_CONTENT = true;

	public static function get( $key, $language = self::LANG_UI, $database = true ) {
		global $wgMessageCache;
		if( !is_object($wgMessageCache) ) {
			throw new MWException( "Message cache not initialised\n" );
		}

		wfRunHooks('NormalizeMessageKey', array(&$key, &$database, &$language));

		$message = $wgMessageCache->get( $key, $database, $language );
		# Fix windows line-endings
		# Some messages are split with explode("\n", $msg)
		$message = str_replace( "\r", '', $message );
		return $message;

	}

	public static function forContentLanguage( $key ) {
		global $wgForceUIMsgAsContentMsg;
		if( is_array( $wgForceUIMsgAsContentMsg ) &&
			in_array( $key, $wgForceUIMsgAsContentMsg ) ) {
			return self::LANG_UI;
		} else {
			return self::LANG_CONTENT;
		}
	}

	public static function replaceArgs( $message, $args ) {
		// Replace arguments
		if ( count( $args ) ) {
			if ( is_array( $args[0] ) ) {
				$args = array_values( $args[0] );
			}
			$replacementKeys = array();
			foreach( $args as $n => $param ) {
				$replacementKeys['$' . ($n + 1)] = $param;
			}
			$message = strtr( $message, $replacementKeys );
		}

		return $message;
	}

	/**
	 * @param $language LANG_UI or LANG_CONTENT.
	 */
	public static function transform( $message, $language = self::LANG_UI ) {
		global $wgMessageCache;
		// transform accepts only boolean values
		if ( !is_bool($language) )
			throw new MWException( __METHOD__ . ': only ui/content language supported' );
		return $wgMessageCache->transform( $message, !$language );
	}

	/**
	 * @param $language LANG_UI or LANG_CONTENT.
	 */
	public static function parse( $message, $language = self::LANG_UI, $inline = false ) {
		global $wgOut;
		// parse accepts only boolean values
		if ( !is_bool($language) )
			throw new MWException( __METHOD__ . ': only ui/content language supported' );
		$message = $wgOut->parse( $message, true, !$language );

		if ( $inline ) {
			$m = array();
			if( preg_match( '/^<p>(.*)\n?<\/p>\n?$/sU', $message, $m ) ) {
				$message = $m[1];
			}
		}

		return $message;
	}

	public static function escapeHtml( $message, $allowEntities = true ) {
		$message = htmlspecialchars( $message );
		if ( $allowEntities ) {
			$message = str_replace( '&amp;', '&', $message );
			$message = Sanitizer::normalizeCharReferences( $message );
		}

		return $message;
	}

}