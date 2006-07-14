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
		'redirect'              => array( 0,    '#הפניה',                                  '#REDIRECT'              ),
		'notoc'                 => array( 0,    '__ללא_תוכן_עניינים__', '__ללא_תוכן__',    '__NOTOC__'              ),
		'nogallery'             => array( 0,    '__ללא_גלריה__',                          '__NOGALLERY__'          ),
		'forcetoc'              => array( 0,    '__חייב_תוכן_עניינים__', '__חייב_תוכן__',   '__FORCETOC__'           ),
		'toc'                   => array( 0,    '__תוכן_עניינים__', '__תוכן__',             '__TOC__'                ),
		'noeditsection'         => array( 0,    '__ללא_עריכה__',                           '__NOEDITSECTION__'      ),
		'start'                 => array( 0,    '__התחלה__',                               '__START__'              ),
		'currentmonth'          => array( 1,    'חודש נוכחי',                               'CURRENTMONTH'           ),
		'currentmonthname'      => array( 1,    'שם חודש נוכחי',                            'CURRENTMONTHNAME'       ),
		'currentmonthnamegen'   => array( 1,    'שם חודש נוכחי קניין',                      'CURRENTMONTHNAMEGEN'    ),
		'currentmonthabbrev'    => array( 1,    'קיצור חודש נוכחי',                         'CURRENTMONTHABBREV'     ),
		'currentday'            => array( 1,    'יום נוכחי',                                'CURRENTDAY'             ),
		'currentday2'           => array( 1,    'יום נוכחי 2',                              'CURRENTDAY2'            ),
		'currentdayname'        => array( 1,    'שם יום נוכחי',                             'CURRENTDAYNAME'         ),
		'currentyear'           => array( 1,    'שנה נוכחית',                               'CURRENTYEAR'            ),
		'currenttime'           => array( 1,    'שעה נוכחית',                               'CURRENTTIME'            ),
		'numberofpages'         => array( 1,    'מספר דפים כולל', 'מספר דפים',             'NUMBEROFPAGES'          ),
		'numberofarticles'      => array( 1,    'מספר ערכים',                              'NUMBEROFARTICLES'       ),
		'numberoffiles'         => array( 1,    'מספר קבצים',                              'NUMBEROFFILES'          ),
		'numberofusers'         => array( 1,    'מספר משתמשים',                            'NUMBEROFUSERS'          ),
		'pagename'              => array( 1,    'שם הדף',                                  'PAGENAME'               ),
		'pagenamee'             => array( 1,    'שם הדף מקודד',                            'PAGENAMEE'              ),
		'namespace'             => array( 1,    'מרחב השם',                                'NAMESPACE'              ),
		'namespacee'            => array( 1,    'מרחב השם מקודד',                          'NAMESPACEE'             ),
		'talkspace'             => array( 1,    'מרחב השיחה',                              'TALKSPACE'              ),
		'talkspacee'            => array( 1,    'מרחב השיחה מקודד',                        'TALKSPACEE'              ),
		'subjectspace'          => array( 1,    'מרחב הנושא', 'מרחב הערכים',              'SUBJECTSPACE', 'ARTICLESPACE' ),
		'subjectspacee'         => array( 1,    'מרחב הנושא מקודד', 'מרחב הערכים מקודד',  'SUBJECTSPACEE', 'ARTICLESPACEE' ),
		'fullpagename'          => array( 1,    'שם הדף המלא',                            'FULLPAGENAME'           ),
		'fullpagenamee'         => array( 1,    'שם הדף המלא מקודד',                      'FULLPAGENAMEE'          ),
		'subpagename'           => array( 1,    'שם דף המשנה',                            'SUBPAGENAME'            ),
		'subpagenamee'          => array( 1,    'שם דף המשנה מקודד',                      'SUBPAGENAMEE'           ),
		'basepagename'          => array( 1,    'שם דף הבסיס',                            'BASEPAGENAME'           ),
		'basepagenamee'         => array( 1,    'שם דף הבסיס מקודד',                      'BASEPAGENAMEE'          ),
		'talkpagename'          => array( 1,    'שם דף השיחה',                            'TALKPAGENAME'           ),
		'talkpagenamee'         => array( 1,    'שם דף השיחה מקודד',                      'TALKPAGENAMEE'          ),
		'subjectpagename'       => array( 1,    'שם דף הנושא', 'שם הערך',                 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
		'subjectpagenamee'      => array( 1,    'שם דף הנושא מקודד', 'שם הערך מקודד',     'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
		'msg'                   => array( 0,    'הכללה:',                                'MSG:'                   ),
		'subst'                 => array( 0,    'ס:',                                    'SUBST:'                 ),
		'msgnw'                 => array( 0,    'הכללת מקור',                            'MSGNW:'                 ),
		'end'                   => array( 0,    '__סוף__',                               '__END__'                ),
		'img_thumbnail'         => array( 1,    'ממוזער',                                'thumbnail', 'thumb'     ),
		'img_manualthumb'       => array( 1,    'ממוזער=$1',                             'thumbnail=$1', 'thumb=$1'),
		'img_right'             => array( 1,    'ימין',                                  'right'                  ),
		'img_left'              => array( 1,    'שמאל',                                 'left'                   ),
		'img_none'              => array( 1,    'ללא',                                  'none'                   ),
		'img_width'             => array( 1,    '$1px',                                 '$1px'                   ),
		'img_center'            => array( 1,    'מרכז',                                 'center', 'centre'       ),
		'img_framed'            => array( 1,    'ממוסגר', 'מסגרת',                      'framed', 'enframed', 'frame' ),
		'int'                   => array( 0,    'הודעה:',                               'INT:'                   ),
		'sitename'              => array( 1,    'שם האתר',                              'SITENAME'               ),
		'ns'                    => array( 0,    'מרחב שם:',                             'NS:'                    ),
		'localurl'              => array( 0,    'כתובת יחסית:',                         'LOCALURL:'              ),
		'localurle'             => array( 0,    'כתובת יחסית מקודד:',                   'LOCALURLE:'             ),
		'server'                => array( 0,    'כתובת השרת', 'שרת',                    'SERVER'                 ),
		'servername'            => array( 0,    'שם השרת',                              'SERVERNAME'             ),
		'scriptpath'            => array( 0,    'נתיב הקבצים',                          'SCRIPTPATH'             ),
		'grammar'               => array( 0,    'דקדוק:',                               'GRAMMAR:'               ),
		'notitleconvert'        => array( 0,    '__ללא_המרת_כותרת__',                  '__NOTITLECONVERT__', '__NOTC__'),
		'nocontentconvert'      => array( 0,    '__ללא_המרת_תוכן__',                   '__NOCONTENTCONVERT__', '__NOCC__'),
		'currentweek'           => array( 1,    'שבוע נוכחי',                           'CURRENTWEEK'            ),
		'currentdow'            => array( 1,    'מספר יום נוכחי',                       'CURRENTDOW'             ),
		'revisionid'            => array( 1,    'מזהה גרסה',                            'REVISIONID'             ),
		'plural'                => array( 0,    'רבים:',                                'PLURAL:'                ),
		'fullurl'               => array( 0,    'כתובת מלאה:',                          'FULLURL:'               ),
		'fullurle'              => array( 0,    'כתובת מלאה מקודד:',                    'FULLURLE:'              ),
		'lcfirst'               => array( 0,    'אות ראשונה קטנה:',                     'LCFIRST:'               ),
		'ucfirst'               => array( 0,    'אות ראשונה גדולה:',                    'UCFIRST:'               ),
		'lc'                    => array( 0,    'אותיות קטנות:',                        'LC:'                    ),
		'uc'                    => array( 0,    'אותיות גדולות:',                       'UC:'                    ),
		'raw'                   => array( 0,    'ללא עיבוד:',                          'RAW:'                   ),
		'displaytitle'          => array( 1,    'כותרת תצוגה',                         'DISPLAYTITLE'           ),
		'rawsuffix'             => array( 1,    'ללא פסיק',                            'R'                      ),
		'newsectionlink'        => array( 1,    '__יצירת_הערה__',                      '__NEWSECTIONLINK__'     ),
		'currentversion'        => array( 1,    'גרסה נוכחית',                         'CURRENTVERSION'         ),
		'urlencode'             => array( 0,    'נתיב מקודד:',                         'URLENCODE:'             ),
		'currenttimestamp'      => array( 1,    'זמן נוכחי',                           'CURRENTTIMESTAMP'       ),
		'directionmark'         => array( 1,    'סימן כיווניות',                       'DIRECTIONMARK', 'DIRMARK' ),
		'language'              => array( 0,    '#שפה:',                              '#LANGUAGE:' ),
		'contentlanguage'       => array( 1,    'שפת תוכן',                           'CONTENTLANGUAGE', 'CONTENTLANG' ),
		'pagesinnamespace'      => array( 1,    'דפים במרחב השם:',                   'PAGESINNAMESPACE:', 'PAGESINNS:' ),
		'numberofadmins'        => array( 1,    'מספר מפעילים',                      'NUMBEROFADMINS' ),
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
	public function getDefaultUserOptions() {
		$opt = parent::getDefaultUserOptions();
		$opt["quickbar"] = 2;
		return $opt;
	}
	
	/**
	 * @return array of Hebrew bookstore list
	 */
	public function getBookstoreList() {
		return $this->mBookstoreListHe;
	}
	
	/**
	 * @return array of Hebrew namespace names
	 */
	public function getNamespaces() {
		return $this->mNamespaceNamesHe + parent::getNamespaces();
	}
	
	/**
	 * @return array of Hebrew skin names
	 */
	public function getSkinNames() {
		return $this->mSkinNamesHe + parent::getSkinNames();
	}
	
	/**
	 * @return array of Hebrew quickbar settings
	 */
	public function getQuickbarSettings() {
		return $this->mQuickbarSettingsHe;
	}
	
	/**
	 * The function returns a message, in Hebrew if exists, in English if not, only from the default translations here.
	 *
	 * @param string the message key
	 *
	 * @return string of the wanted message
	 */
	public function getMessage( $key ) {
		if( isset( $this->mMessagesHe[$key] ) ) {
			return $this->mMessagesHe[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
	
	/**
	 * @return array of all the Hebrew messages
	 */
	public function getAllMessages() {
		return $this->mMessagesHe;
	}
	
	/**
	 * @return array of all the magic words
	 */
	public function &getMagicWords() {
		return $this->mMagicWordsHe;
	}
	
	/**
	 * @return true, as Hebrew is RTL language
	 */
	public function isRTL() {
		return true;
	}
	
	/**
	 * @return regular expression which includes the word trails in the link
	 */
	public function linkTrail() {
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
	public function convertGrammar( $word, $case ) {
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
	public function convertPlural( $count, $w1, $w2, $w3) {
		if ( $count == '1' ) {
			return $w1;
		} elseif ( $count == '2' && $w3 ) {
			return $w3;
		} else {
			return $w2;
		}
	}
	
	/**
	 * @return string of the best 8-bit encoding for Hebrew, if UTF-8 cannot be used
	 */
	public function fallback8bitEncoding() {
		return "windows-1255";
	}
}

?>
