<?php
/**
 * Hebrew (עברית)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Rotem Dan (July 2003)
 * @author Rotem Liss (March 2006 on)
 */

require_once("LanguageUtf8.php");

if (!$wgCachedMessageArrays) {
	require_once('MessagesHe.php');
}

class LanguageHe extends LanguageUtf8 {
	private $mMessagesHe, $mNamespaceNamesHe = null;
	
	private $mSkinNamesHe = array(
		"standard"    => "רגיל",
		"nostalgia"   => "נוסטלגי",
		"cologneblue" => "מים כחולים",
		"davinci"     => "דה־וינצ'י",
		"simple"      => "פשוט",
		"mono"        => "מונו",
		"monobook"    => "מונובוק",
		"myskin"      => "הרקע שלי",
		"chick"       => "צ'יק"
	);
	
	private $mQuickbarSettingsHe = array(
		"ללא", "קבוע משמאל", "קבוע מימין", "צף משמאל", "צף מימין"
	);
	
	private $mBookstoreListHe = array(
		"מיתוס"          => "http://www.mitos.co.il/",
		"iBooks"         => "http://www.ibooks.co.il/",
		"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=\$1",
		"Amazon.com"     => "http://www.amazon.com/exec/obidos/ISBN=\$1"
	);
	
	private $mMagicWordsHe = array(
		MAG_REDIRECT            => array( 0,    '#הפניה',                                  '#REDIRECT'              ),
		MAG_NOTOC               => array( 0,    '__ללא_תוכן_עניינים__', '__ללא_תוכן__',    '__NOTOC__'              ),
		MAG_NOGALLERY           => array( 0,    '__ללא_גלריה__',                          '__NOGALLERY__'          ),
		MAG_FORCETOC            => array( 0,    '__חייב_תוכן_עניינים__', '__חייב_תוכן__',   '__FORCETOC__'           ),
		MAG_TOC                 => array( 0,    '__תוכן_עניינים__', '__תוכן__',             '__TOC__'                ),
		MAG_NOEDITSECTION       => array( 0,    '__ללא_עריכה__',                           '__NOEDITSECTION__'      ),
		MAG_START               => array( 0,    '__התחלה__',                               '__START__'              ),
		MAG_CURRENTMONTH        => array( 1,    'חודש נוכחי',                               'CURRENTMONTH'           ),
		MAG_CURRENTMONTHNAME    => array( 1,    'שם חודש נוכחי',                            'CURRENTMONTHNAME'       ),
		MAG_CURRENTMONTHNAMEGEN => array( 1,    'שם חודש נוכחי קניין',                      'CURRENTMONTHNAMEGEN'    ),
		MAG_CURRENTMONTHABBREV  => array( 1,    'קיצור חודש נוכחי',                         'CURRENTMONTHABBREV'     ),
		MAG_CURRENTDAY          => array( 1,    'יום נוכחי',                                'CURRENTDAY'             ),
		MAG_CURRENTDAY2         => array( 1,    'יום נוכחי 2',                              'CURRENTDAY2'            ),
		MAG_CURRENTDAYNAME      => array( 1,    'שם יום נוכחי',                             'CURRENTDAYNAME'         ),
		MAG_CURRENTYEAR         => array( 1,    'שנה נוכחית',                               'CURRENTYEAR'            ),
		MAG_CURRENTTIME         => array( 1,    'שעה נוכחית',                               'CURRENTTIME'            ),
		MAG_NUMBEROFPAGES       => array( 1,    'מספר דפים כולל', 'מספר דפים',             'NUMBEROFPAGES'          ),
		MAG_NUMBEROFARTICLES    => array( 1,    'מספר ערכים',                              'NUMBEROFARTICLES'       ),
		MAG_NUMBEROFFILES       => array( 1,    'מספר קבצים',                              'NUMBEROFFILES'          ),
		MAG_NUMBEROFUSERS       => array( 1,    'מספר משתמשים',                            'NUMBEROFUSERS'          ),
		MAG_PAGENAME            => array( 1,    'שם הדף',                                  'PAGENAME'               ),
		MAG_PAGENAMEE           => array( 1,    'שם הדף מקודד',                            'PAGENAMEE'              ),
		MAG_NAMESPACE           => array( 1,    'מרחב השם',                                'NAMESPACE'              ),
		MAG_NAMESPACEE          => array( 1,    'מרחב השם מקודד',                          'NAMESPACEE'             ),
		MAG_TALKSPACE           => array( 1,    'מרחב השיחה',                              'TALKSPACE'              ),
		MAG_TALKSPACEE          => array( 1,    'מרחב השיחה מקודד',                        'TALKSPACEE'              ),
		MAG_SUBJECTSPACE        => array( 1,    'מרחב הנושא', 'מרחב הערכים',              'SUBJECTSPACE', 'ARTICLESPACE' ),
		MAG_SUBJECTSPACEE       => array( 1,    'מרחב הנושא מקודד', 'מרחב הערכים מקודד',  'SUBJECTSPACEE', 'ARTICLESPACEE' ),
		MAG_FULLPAGENAME        => array( 1,    'שם הדף המלא',                            'FULLPAGENAME'           ),
		MAG_FULLPAGENAMEE       => array( 1,    'שם הדף המלא מקודד',                      'FULLPAGENAMEE'          ),
		MAG_SUBPAGENAME         => array( 1,    'שם דף המשנה',                            'SUBPAGENAME'            ),
		MAG_SUBPAGENAMEE        => array( 1,    'שם דף המשנה מקודד',                      'SUBPAGENAMEE'           ),
		MAG_BASEPAGENAME        => array( 1,    'שם דף הבסיס',                            'BASEPAGENAME'           ),
		MAG_BASEPAGENAMEE       => array( 1,    'שם דף הבסיס מקודד',                      'BASEPAGENAMEE'          ),
		MAG_TALKPAGENAME        => array( 1,    'שם דף השיחה',                            'TALKPAGENAME'           ),
		MAG_TALKPAGENAMEE       => array( 1,    'שם דף השיחה מקודד',                      'TALKPAGENAMEE'          ),
		MAG_SUBJECTPAGENAME     => array( 1,    'שם דף הנושא', 'שם הערך',                 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
		MAG_SUBJECTPAGENAMEE    => array( 1,    'שם דף הנושא מקודד', 'שם הערך מקודד',     'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
		MAG_MSG                 => array( 0,    'הכללה:',                                'MSG:'                   ),
		MAG_SUBST               => array( 0,    'ס:',                                    'SUBST:'                 ),
		MAG_MSGNW               => array( 0,    'הכללת מקור',                            'MSGNW:'                 ),
		MAG_END                 => array( 0,    '__סוף__',                               '__END__'                ),
		MAG_IMG_THUMBNAIL       => array( 1,    'ממוזער',                                'thumbnail', 'thumb'     ),
		MAG_IMG_MANUALTHUMB     => array( 1,    'ממוזער=$1',                             'thumbnail=$1', 'thumb=$1'),
		MAG_IMG_RIGHT           => array( 1,    'ימין',                                  'right'                  ),
		MAG_IMG_LEFT            => array( 1,    'שמאל',                                 'left'                   ),
		MAG_IMG_NONE            => array( 1,    'ללא',                                  'none'                   ),
		MAG_IMG_WIDTH           => array( 1,    '$1px',                                 '$1px'                   ),
		MAG_IMG_CENTER          => array( 1,    'מרכז',                                 'center', 'centre'       ),
		MAG_IMG_FRAMED          => array( 1,    'ממוסגר', 'מסגרת',                      'framed', 'enframed', 'frame' ),
		MAG_INT                 => array( 0,    'הודעה:',                               'INT:'                   ),
		MAG_SITENAME            => array( 1,    'שם האתר',                              'SITENAME'               ),
		MAG_NS                  => array( 0,    'מרחב שם:',                             'NS:'                    ),
		MAG_LOCALURL            => array( 0,    'כתובת יחסית:',                         'LOCALURL:'              ),
		MAG_LOCALURLE           => array( 0,    'כתובת יחסית מקודד:',                   'LOCALURLE:'             ),
		MAG_SERVER              => array( 0,    'כתובת השרת', 'שרת',                    'SERVER'                 ),
		MAG_SERVERNAME          => array( 0,    'שם השרת',                              'SERVERNAME'             ),
		MAG_SCRIPTPATH          => array( 0,    'נתיב הקבצים',                          'SCRIPTPATH'             ),
		MAG_GRAMMAR             => array( 0,    'דקדוק:',                               'GRAMMAR:'               ),
		MAG_NOTITLECONVERT      => array( 0,    '__ללא_המרת_כותרת__',                  '__NOTITLECONVERT__', '__NOTC__'),
		MAG_NOCONTENTCONVERT    => array( 0,    '__ללא_המרת_תוכן__',                   '__NOCONTENTCONVERT__', '__NOCC__'),
		MAG_CURRENTWEEK         => array( 1,    'שבוע נוכחי',                           'CURRENTWEEK'            ),
		MAG_CURRENTDOW          => array( 1,    'מספר יום נוכחי',                       'CURRENTDOW'             ),
		MAG_REVISIONID          => array( 1,    'מזהה גרסה',                            'REVISIONID'             ),
		MAG_PLURAL              => array( 0,    'רבים:',                                'PLURAL:'                ),
		MAG_FULLURL             => array( 0,    'כתובת מלאה:',                          'FULLURL:'               ),
		MAG_FULLURLE            => array( 0,    'כתובת מלאה מקודד:',                    'FULLURLE:'              ),
		MAG_LCFIRST             => array( 0,    'אות ראשונה קטנה:',                     'LCFIRST:'               ),
		MAG_UCFIRST             => array( 0,    'אות ראשונה גדולה:',                    'UCFIRST:'               ),
		MAG_LC                  => array( 0,    'אותיות קטנות:',                        'LC:'                    ),
		MAG_UC                  => array( 0,    'אותיות גדולות:',                       'UC:'                    ),
		MAG_RAW                 => array( 0,    'ללא עיבוד:',                          'RAW:'                   ),
		MAG_DISPLAYTITLE        => array( 1,    'כותרת תצוגה',                         'DISPLAYTITLE'           ),
		MAG_RAWSUFFIX           => array( 1,    'ללא פסיק',                            'R'                      ),
		MAG_NEWSECTIONLINK      => array( 1,    '__יצירת_הערה__',                      '__NEWSECTIONLINK__'     ),
		MAG_CURRENTVERSION      => array( 1,    'גרסה נוכחית',                         'CURRENTVERSION'         ),
		MAG_URLENCODE           => array( 0,    'נתיב מקודד:',                         'URLENCODE:'             ),
		MAG_CURRENTTIMESTAMP    => array( 1,    'זמן נוכחי',                           'CURRENTTIMESTAMP'       ),
		MAG_DIRECTIONMARK       => array( 1,    'סימן כיווניות',                       'DIRECTIONMARK', 'DIRMARK' ),
		MAG_LANGUAGE            => array( 0,    '#שפה:',                              '#LANGUAGE:' ),
		MAG_CONTENTLANGUAGE     => array( 1,    'שפת תוכן',                           'CONTENTLANGUAGE', 'CONTENTLANG' ),
		MAG_PAGESINNAMESPACE    => array( 1,    'דפים במרחב השם:',                   'PAGESINNAMESPACE:', 'PAGESINNS:' ),
		MAG_NUMBEROFADMINS      => array( 1,    'מספר מפעילים',                      'NUMBEROFADMINS' ),
	);
	
	/**
	 * Constructor, setting the namespaces
	 */
	function __construct() {
		parent::__construct();
		
		global $wgAllMessagesHe;
		$this->mMessagesHe = &$wgAllMessagesHe;
		
		global $wgMetaNamespace;
		$this->mNamespaceNamesHe = array(
			NS_MEDIA          => "מדיה",
			NS_SPECIAL        => "מיוחד",
			NS_MAIN           => "",
			NS_TALK           => "שיחה",
			NS_USER           => "משתמש",
			NS_USER_TALK      => "שיחת_משתמש",
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => "שיחת_" . $wgMetaNamespace,
			NS_IMAGE          => "תמונה",
			NS_IMAGE_TALK     => "שיחת_תמונה",
			NS_MEDIAWIKI      => "מדיה_ויקי",
			NS_MEDIAWIKI_TALK => "שיחת_מדיה_ויקי",
			NS_TEMPLATE       => "תבנית",
			NS_TEMPLATE_TALK  => "שיחת_תבנית",
			NS_HELP           => "עזרה",
			NS_HELP_TALK      => "שיחת_עזרה",
			NS_CATEGORY       => "קטגוריה",
			NS_CATEGORY_TALK  => "שיחת_קטגוריה",
		);
	}
	
	/**
	 * Changing the default quickbar setting to "2" - right instead of left, as we use RTL interface
	 *
	 * @return array of the default user options
	 */
	function getDefaultUserOptions() {
		$opt = parent::getDefaultUserOptions();
		$opt["quickbar"] = 2;
		return $opt;
	}
	
	/**
	 * @return array of Hebrew bookstore list
	 */
	function getBookstoreList() {
		return $this->mBookstoreListHe;
	}
	
	/**
	 * @return array of Hebrew namespace names
	 */
	function getNamespaces() {
		return $this->mNamespaceNamesHe + parent::getNamespaces();
	}
	
	/**
	 * @return array of Hebrew skin names
	 */
	function getSkinNames() {
		return $this->mSkinNamesHe + parent::getSkinNames();
	}
	
	/**
	 * @return array of Hebrew quickbar settings
	 */
	function getQuickbarSettings() {
		return $this->mQuickbarSettingsHe;
	}
	
	/**
	 * The function returns a message, in Hebrew if exists, in English if not, only from the default translations here.
	 *
	 * @param string the message key
	 *
	 * @return string of the wanted message
	 */
	function getMessage( $key ) {
		if( isset( $this->mMessagesHe[$key] ) ) {
			return $this->mMessagesHe[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
	
	/**
	 * @return array of all the Hebrew messages
	 */
	function getAllMessages() {
		return $this->mMessagesHe;
	}
	
	/**
	 * @return array of all the magic words
	 */
	function &getMagicWords() {
		return $this->mMagicWordsHe;
	}
	
	/**
	 * @return true, as Hebrew is RTL language
	 */
	function isRTL() {
		return true;
	}
	
	/**
	 * @return regular expression which includes the word trails in the link
	 */
	function linkTrail() {
		return '/^([a-zא-ת]+)(.*)$/sDu';
	}
	
	/**
	 * Convert grammar forms of words.
	 *
	 * Available cases:
	 * "prefixed" (or "תחילית") - when the word has a prefix
	 *
	 * @param string the word to convert
	 * @param string the case
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['he'][$case][$word]) ) {
			return $wgGrammarForms['he'][$case][$word];
		}
		
		switch ( $case ) {
			case 'prefixed':
			case 'תחילית':
				# Duplicate the "Waw" if prefixed
				if ( substr( $word, 0, 2 ) == "ו" && substr( $word, 0, 4 ) != "וו" ) {
					$word = "ו".$word;
				}
				
				# Remove the "He" if prefixed
				if ( substr( $word, 0, 2 ) == "ה" ) {
					$word = substr( $word, 2 );
				}
				
				# Add a hyphen if non-Hebrew letters
				if ( substr( $word, 0, 2 ) < "א" || substr( $word, 0, 2 ) > "ת" ) {
					$word = "־".$word;
				}
		}
		
		return $word;
	}
	
	/**
	 * Gets a number and uses the suited form of the word.
	 *
	 * @param integer the number of items
	 * @param string the first form (singular)
	 * @param string the second form (plural)
	 * @param string the third form (2 items, plural is used if not applicable and not specified)
	 *
	 * @return string of the suited form of word
	 */
	function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
		if ( $count == '1' ) {
			return $wordform1;
		} elseif ( $count == '2' && $wordform3 ) {
			return $wordform3;
		} else {
			return $wordform2;
		}
	}
	
	/**
	 * @return string of the best 8-bit encoding for Hebrew, if UTF-8 cannot be used
	 */
	function fallback8bitEncoding() {
		return "windows-1255";
	}
}

?>
