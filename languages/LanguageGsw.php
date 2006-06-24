<?php
/** Alemannic (Alemannisch)
 *
 * @package MediaWiki
 * @subpackage Language
 */

/*
<Melancholie> for the moment it would be the best if LanguageAls.php would be
              the same like LanguageDe.php. That would help us a lot at als.
<Melancholie> at the moment all is in English
<TimStarling> ok
<Melancholie> great
<TimStarling> I'll make a stub language file that fetches everything from de
<Melancholie> cool
*/

include_once( "LanguageDe.php" );

if (!$wgCachedMessageArrays) {
	require_once('MessagesGsw.php');
}

class LanguageGsw extends LanguageDe {
	private $mMessagesGsw = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesGsw;
		$this->mMessagesGsw =& $wgAllMessagesGsw;

	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesGsw[$key] ) ) {
			return $this->mMessagesGsw[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesGsw;
	}

	function getFallbackLanguage() {
		return 'de';
	}

	function linkTrail() {
		return '/^([äöüßa-z]+)(.*)$/sDu';
	}

   # Convert from the nominative form of a noun to some other case
   # Invoked with result

   function convertGrammar( $word, $case ) {
       global $wgGrammarForms;
       if ( isset($wgGrammarForms['gsw'][$case][$word]) ) {
           return $wgGrammarForms['gsw'][$case][$word];
       }
       switch ( $case ) {
           case 'dativ':
               if ( $word == 'Wikipedia' ) {
                   $word = 'vo de Wikipedia';
               } elseif ( $word == 'Wikinorchrichte' ) {
                   $word = 'vo de Wikinochrichte';
               } elseif ( $word == 'Wiktionaire' ) {
                   $word = 'vom Wiktionaire';
               } elseif ( $word == 'Wikibuecher' ) {
                   $word = 'vo de Wikibuecher';
               } elseif ( $word == 'Wikisprüch' ) {
                   $word = 'vo de Wikisprüch';
               } elseif ( $word == 'Wikiquälle' ) {
                   $word = 'vo de Wikiquälle';
               }
               break;
           case 'akkusativ':
               if ( $word == 'Wikipedia' ) {
                   $word = 'd Wikipedia';
               } elseif ( $word == 'Wikinorchrichte' ) {
                   $word = 'd Wikinochrichte';
               } elseif ( $word == 'Wiktionaire' ) {
                   $word = 's Wiktionaire';
               } elseif ( $word == 'Wikibuecher' ) {
                   $word = 'd Wikibuecher';
               } elseif ( $word == 'Wikisprüch' ) {
                   $word = 'd Wikisprüch';
               } elseif ( $word == 'Wikiquälle' ) {
                   $word = 'd Wikiquälle';
               }
               break;
           case 'nominativ':
               if ( $word == 'Wikipedia' ) {
                   $word = 'd Wikipedia';
               } elseif ( $word == 'Wikinorchrichte' ) {
                   $word = 'd Wikinochrichte';
               } elseif ( $word == 'Wiktionaire' ) {
                   $word = 's Wiktionaire';
               } elseif ( $word == 'Wikibuecher' ) {
                   $word = 'd Wikibuecher';
               } elseif ( $word == 'Wikisprüch' ) {
                   $word = 'd Wikisprüch';
               } elseif ( $word == 'Wikiquälle' ) {
                   $word = 'd Wikiquälle';
               }
               break;
       } 
       return $word;
   }

}

?>
