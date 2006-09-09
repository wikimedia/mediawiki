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

$rtl = true;
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
);
$linkTrail = '/^([a-zא-ת]+)(.*)$/sDu';
$fallback8bitEncoding = "windows-1255";

$skinNames = array(
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

$quickbarSettings = array(
	"ללא", "קבוע משמאל", "קבוע מימין", "צף משמאל", "צף מימין"
);

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i, Y xg j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$bookstoreList = array(
	"מיתוס"          => "http://www.mitos.co.il/",
	"iBooks"         => "http://www.ibooks.co.il/",
	"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=\$1",
	"Amazon.com"     => "http://www.amazon.com/exec/obidos/ISBN=\$1"
);

$magicWords = array(
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
	'currenthour'           => array( 1,    'שעות נוכחיות',                             'CURRENTHOUR'            ),
	'localmonth'            => array( 1,    'חודש מקומי',                               'LOCALMONTH'             ),
	'localmonthname'        => array( 1,    'שם חודש מקומי',                            'LOCALMONTHNAME'         ),
	'localmonthnamegen'     => array( 1,    'שם חודש מקומי קניין',                      'LOCALMONTHNAMEGEN'      ),
	'localmonthabbrev'      => array( 1,    'קיצור חודש מקומי',                         'LOCALMONTHABBREV'       ),
	'localday'              => array( 1,    'יום מקומי',                                'LOCALDAY'               ),
	'localday2'             => array( 1,    'יום מקומי 2',                              'LOCALDAY2'              ),
	'localdayname'          => array( 1,    'שם יום מקומי',                             'LOCALDAYNAME'           ),
	'localyear'             => array( 1,    'שנה מקומית',                               'LOCALYEAR'              ),
	'localtime'             => array( 1,    'שעה מקומית',                               'LOCALTIME'              ),
	'localhour'             => array( 1,    'שעות מקומיות',                             'LOCALHOUR'              ),
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
	'img_page'              => array( 1,    'דף=$1', 'דף $1',                       'page=$1', 'page $1'     ),
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
	'localweek'             => array( 1,    'שבוע מקומי',                           'LOCALWEEK'              ),
	'localdow'              => array( 1,    'מספר יום מקומי',                       'LOCALDOW'               ),
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
	'anchorencode'          => array( 0,    'עוגן מקודד:',                         'ANCHORENCODE'           ),
	'currenttimestamp'      => array( 1,    'זמן נוכחי',                           'CURRENTTIMESTAMP'       ),
	'localtimestamp'        => array( 1,    'זמן מקומי',                           'LOCALTIMESTAMP'         ),
	'directionmark'         => array( 1,    'סימן כיווניות',                       'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( 0,    '#שפה:',                              '#LANGUAGE:'             ),
	'contentlanguage'       => array( 1,    'שפת תוכן',                           'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( 1,    'דפים במרחב השם:',                   'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( 1,    'מספר מפעילים',                      'NUMBEROFADMINS'         ),
	'formatnum'             => array( 0,    'עיצוב מספר',                        'FORMATNUM'              ),
	'padleft'               => array( 0,    'ריפוד משמאל',                       'PADLEFT'                ),
	'padright'              => array( 0,    'ריפוד מימין',                       'PADRIGHT'               ),
);

$namespaceNames = array(
	NS_MEDIA          => "מדיה",
	NS_SPECIAL        => "מיוחד",
	NS_MAIN           => "",
	NS_TALK           => "שיחה",
	NS_USER           => "משתמש",
	NS_USER_TALK      => "שיחת_משתמש",
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => "שיחת_$1",
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


$messages = array(

# User preference toggles
"tog-underline"               => "סמן קישורים בקו תחתי",
"tog-highlightbroken"         => 'סמן קישורים לדפים שלא נכתבו <a href="" class="new">כך</a> (או: כך<a href="" class="new">?</a>))',
"tog-justify"                 => "ישר פסקאות",
"tog-hideminor"               => "הסתר שינויים משניים ברשימת השינויים האחרונים",
"tog-extendwatchlist"         => "הרחב את רשימת המעקב כך שתציג את כל השינויים המתאימים (אחרת: את השינוי האחרון בכל דף בלבד)",
"tog-usenewrc"                => "רשימת שינויים אחרונים משופרת (JavaScript)",
"tog-numberheadings"          => "מספר כותרות אוטומטית",
"tog-showtoolbar"             => "הצג את סרגל העריכה",
"tog-editondblclick"          => "ערוך דפים בלחיצה כפולה (JavaScript)",
"tog-editsection"             => "הפעל עריכת פסקאות באמצעות קישורים מהצורה [עריכה]",
"tog-editsectiononrightclick" => "הפעל עריכת פסקאות על־ידי לחיצה ימנית<br />על כותרות הפסקאות (JavaScript)",
"tog-showtoc"                 => "הצג תוכן עניינים<br />(עבור דפים עם יותר מ־3 כותרות)",
"tog-rememberpassword"        => "זכור את הסיסמה שלי בפעמים הבאות",
"tog-editwidth"               => "תיבת העריכה ברוחב מלא",
"tog-watchcreations"          => "עקוב אחרי דפים שיצרתי",
"tog-watchdefault"            => "עקוב אחרי דפים שערכתי",
"tog-minordefault"            => "הגדר כל פעולת עריכה כמשנית אם לא צויין אחרת",
"tog-previewontop"            => "הצג תצוגה מקדימה לפני תיבת העריכה (או: אחריה)",
"tog-previewonfirst"          => "הצג תצוגה מקדימה בעריכה ראשונה",
"tog-nocache"                 => "נטרל משיכת דפים מזכרון המטמון שבשרת",
"tog-enotifwatchlistpages"    => 'שלח לי דוא"ל כאשר נעשה שינוי בדפים הנצפים על־ידי',
"tog-enotifusertalkpages"     => 'שלח לי דוא"ל כאשר נעשה שינוי בדף שיחת המשתמש שלי',
"tog-enotifminoredits"        => 'שלח לי דוא"ל גם על עריכות משניות של דפים',
"tog-enotifrevealaddr"        => 'חשוף את כתובת הדוא"ל שלי בהודעות דואר',
"tog-shownumberswatching"     => "הצג את מספר המשתמשים הצופים בדף",
"tog-fancysig"                => "הצג חתימה מסוגננת",
"tog-externaleditor"          => "השתמש בעורך חיצוני כברירת מחדל",
"tog-externaldiff"            => "השתמש בתוכנת השוואת הגרסאות החיצונית כברירת מחדל",
"tog-showjumplinks"           => 'אפשר קישורי נגישות מסוג "קפוץ אל"',
"tog-uselivepreview"          => "השתמש בתצוגה מקדימה חיה (JavaScript) (ניסיוני)",
"tog-autopatrol"              => "סמן את העריכות שלי כבדוקות",
"tog-forceeditsummary"        => "הזהר אותי כשאני מכניס תקציר עריכה ריק",
"tog-watchlisthideown"        => "הסתר עריכות שלי ברשימת המעקב",
"tog-watchlisthidebots"       => "הסתר בוטים ברשימת המעקב",

"underline-always"  => "תמיד",
"underline-never"   => "אף פעם",
"underline-default" => "ברירת מחדל של הדפדפן",

"skinpreview" => "(תצוגה מקדימה)",

# Dates
"sunday"        => "ראשון",
"monday"        => "שני",
"tuesday"       => "שלישי",
"wednesday"     => "רביעי",
"thursday"      => "חמישי",
"friday"        => "שישי",
"saturday"      => "שבת",
"sun"           => "ראש'",
"mon"           => "שני",
"tue"           => "שלי'",
"wed"           => "רבי'",
"thu"           => "חמי'",
"fri"           => "שיש'",
"sat"           => "שבת",
"january"       => "ינואר",
"february"      => "פברואר",
"march"         => "מרץ",
"april"         => "אפריל",
"may_long"      => "מאי",
"june"          => "יוני",
"july"          => "יולי",
"august"        => "אוגוסט",
"september"     => "ספטמבר",
"october"       => "אוקטובר",
"november"      => "נובמבר",
"december"      => "דצמבר",
"january-gen"   => "בינואר",
"february-gen"  => "בפברואר",
"march-gen"     => "במרץ",
"april-gen"     => "באפריל",
"may-gen"       => "במאי",
"june-gen"      => "ביוני",
"july-gen"      => "ביולי",
"august-gen"    => "באוגוסט",
"september-gen" => "בספטמבר",
"october-gen"   => "באוקטובר",
"november-gen"  => "בנובמבר",
"december-gen"  => "בדצמבר",
"jan"           => "ינו'",
"feb"           => "פבר'",
"mar"           => "מרץ",
"apr"           => "אפר'",
"may"           => "מאי",
"jun"           => "יוני",
"jul"           => "יולי",
"aug"           => "אוג'",
"sep"           => "ספט'",
"oct"           => "אוק'",
"nov"           => "נוב'",
"dec"           => "דצמ'",

# Bits of text used by many pages
"categories"      => "{{plural:$1|קטגוריה|קטגוריות}}",
"category_header" => 'דפים בקטגוריה "$1"',
"subcategories"   => "קטגוריות משנה",

"mainpage"          => "עמוד ראשי",
"mainpagetext"      => "'''תוכנת מדיה־ויקי הותקנה בהצלחה.'''",
"mainpagedocfooter" => "היעזרו ב[http://meta.wikimedia.org/wiki/Help:Contents מדריך למשתמש] למידע על שימוש בתוכנת הוויקי.

== קישורים שימושיים ==
* [http://www.mediawiki.org/wiki/Help:Configuration_settings רשימת ההגדרות]
* [http://www.mediawiki.org/wiki/Help:FAQ שאלות נפוצות]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce רשימת התפוצה על השקת גרסאות]",

"portal"          => "שער הקהילה",
"portal-url"      => "{{ns:project}}:שער הקהילה",
"about"           => "אודות",
"aboutsite"       => "אודות {{SITENAME}}",
"aboutpage"       => "{{ns:project}}:אודות",
"article"         => "דף תוכן",
"help"            => "עזרה",
"helppage"        => "{{ns:project}}:עזרה",
"bugreports"      => "דיווח על באגים",
"bugreportspage"  => "{{ns:project}}:דיווח על באגים",
"sitesupport"     => "תרומות",
"sitesupport-url" => "{{ns:project}}:תרומות",
"faq"             => "שאלות ותשובות",
"faqpage"         => "{{ns:project}}:שאלות ותשובות",
"edithelp"        => "עזרה לעריכה",
"newwindow"       => "(נפתח בחלון חדש)",
"edithelppage"    => "{{ns:project}}:איך לערוך דף",
"cancel"          => "בטל / צא",
"qbfind"          => "חיפוש",
"qbbrowse"        => "דפדוף",
"qbedit"          => "עריכה",
"qbpageoptions"   => "אפשרויות דף",
"qbpageinfo"      => "מידע על הדף",
"qbmyoptions"     => "האפשרויות שלי",
"qbspecialpages"  => "דפים מיוחדים",
"moredotdotdot"   => "עוד…",
"mypage"          => "הדף שלי",
"mytalk"          => "דף השיחה שלי",
"anontalk"        => "השיחה עבור IP זה",
"navigation"      => "ניווט",

# Metadata in edit box
"metadata_help" => "Metadata (ראו [[{{ns:project}}:Metadata]] למידע נוסף):",

"currentevents"     => "אקטואליה",
"currentevents-url" => "אקטואליה",

"disclaimers"       => "הבהרה משפטית",
"disclaimerpage"    => "{{ns:project}}:הבהרה משפטית",
"privacy"           => "מדיניות הפרטיות",
"privacypage"       => "{{ns:project}}:מדיניות הפרטיות",
"errorpagetitle"    => "שגיאה",
"returnto"          => "חזרו לדף $1.",
"tagline"           => "מתוך {{SITENAME}}",
"help"              => "עזרה",
"search"            => "חיפוש",
"searchbutton"      => "חיפוש",
"go"                => "עבור",
"history"           => "היסטוריית הדף",
"history_short"     => "היסטוריה",
"updatedmarker"     => "עודכן מאז ביקורך האחרון",
"info_short"        => "מידע",
"printableversion"  => "גרסת הדפסה",
"permalink"         => "קישור קבוע",
"print"             => "גרסה להדפסה",
"edit"              => "עריכה",
"editthispage"      => "ערכו דף זה",
"delete"            => "מחק",
"deletethispage"    => "מחקו דף זה",
"undelete_short"    => "שחזר {{plural:$1|עריכה אחת|$1 עריכות}}",
"protect"           => "הגן",
"protectthispage"   => "הגנו על דף זה",
"unprotect"         => "הסר הגנה",
"unprotectthispage" => "הסירו הגנה מדף זה",
"newpage"           => "דף חדש",
"talkpage"          => "שוחחו על דף זה",
"specialpage"       => "דף מיוחד",
"personaltools"     => "כלים אישיים",
"postcomment"       => "הוסף הערה לדף השיחה",
"articlepage"       => "צפו בדף התוכן",
"talk"              => "שיחה",
"views"             => "צפיות",
"toolbox"           => "תיבת כלים",
"userpage"          => "צפו בדף המשתמש",
"projectpage"       => "צפו בדף המיזם",
"imagepage"         => "צפו בדף התמונה",
"mediawikipage"     => "צפו בדף ההודעה",
"templatepage"      => "צפו בדף התבנית",
"viewhelppage"      => "צפו בדף העזרה",
"categorypage"      => "צפו בדף הקטגוריה",
"viewtalkpage"      => "צפו בדף השיחה",
"otherlanguages"    => "שפות אחרות",
"redirectedfrom"    => "(הופנה מהדף $1)",
"autoredircomment"  => "הפניה לדף [[$1]]",
"redirectpagesub"   => "דף הפניה",
"lastmodified"      => "שונה לאחרונה ב־$1.",
"viewcount"         => "דף זה נצפה {{plural:$1|פעם אחת|$1 פעמים|פעמיים}}.",
"copyright"         => "התוכן מוגש בכפוף ל־$1.<br /> בעלי זכויות היוצרים מפורטים בהיסטוריית השינויים של הדף.",
"protectedpage"     => "דף מוגן",
"jumpto"            => "קפיצה אל:",
"jumptonavigation"  => "ניווט",
"jumptosearch"      => "חיפוש",

"badaccess"        => "שגיאה בהרשאות",
"badaccess-group0" => "אינכם מורשים לבצע את הפעולה שביקשתם.",
"badaccess-group1" => "הפעולה שביקשתם לבצע מוגבלת למשתמשים בקבוצה $1.",
"badaccess-group2" => "הפעולה שביקשתם לבצע מוגבלת למשתמשים באחת הקבוצות $1.",
"badaccess-groups" => "הפעולה שביקשתם לבצע מוגבלת למשתמשים באחת הקבוצות $1.",

"versionrequired"     => "נדרשת גרסה $1 של מדיה־ויקי",
"versionrequiredtext" => 'גרסה $1 של מדיה־ויקי נדרשת לשימוש בדף זה.

למידע נוסף, ראו [[{{ns:special}}:Version]].',

"ok"                  => "אישור",
"pagetitle"           => "$1 – {{SITENAME}}",
"retrievedfrom"       => '<br /><span style="font-size: smaller;">מקור: $1</span>',
"youhavenewmessages"  => "יש לכם $1 ($2).",
"newmessageslink"     => "הודעות חדשות",
"newmessagesdifflink" => "השוואה לגרסה הקודמת",
"editsection"         => "עריכה",
"editold"             => "עריכה",
"editsectionhint"     => "עריכת פסקה: $1",
"toc"                 => "תוכן עניינים",
"showtoc"             => "הראה",
"hidetoc"             => "הסתר",
"thisisdeleted"       => "הציגו או שחזרו $1?",
"viewdeleted"         => "הציגו $1?",
"restorelink"         => "{{plural:$1|גרסה מחוקה אחת|$1 גרסאות מחוקות}}",
"feedlinks"           => "הזנה:",
"feed-invalid"        => "סוג הזנת המנוי שגוי.",

# Short words for each namespace, by default used in the 'article' tab in monobook
"nstab-main"      => "דף תוכן",
"nstab-user"      => "דף משתמש",
"nstab-media"     => "מדיה",
"nstab-special"   => "מיוחד",
"nstab-project"   => "דף מיזם",
"nstab-image"     => "תמונה",
"nstab-mediawiki" => "הודעה",
"nstab-template"  => "תבנית",
"nstab-help"      => "עזרה",
"nstab-category"  => "קטגוריה",

# Main script and global functions
"nosuchaction"      => "אין פעולה כזו",
"nosuchactiontext"  => "מערכת מדיה־ויקי אינה מכירה את הפעולה המצויינת בכתובת ה־URL של הדף.",
"nosuchspecialpage" => "אין דף מיוחד בשם זה",
"nospecialpagetext" => "ביקשת דף מיוחד שאינו מוכר למערכת מדיה־ויקי.",

# General errors
"error"                => "שגיאה",
"databaseerror"        => "שגיאת בסיס־נתונים",
"dberrortext"          => '<p><b>ארעה שגיאת תחביר בשאילתה לבסיס הנתונים</b>.</p>
<p>שגיאה זו יכולה להיות תוצאה של שאילתת חיפוש בלתי חוקית, או שהיא עלולה להעיד על באג במערכת מדיה־ויקי.</p>
<table class="toccolours"
<tr>
<th colspan="2" style="background-color: #F8F8F8; text-align: center;">מידע על השגיאה</th>
</tr>
<tr>
<td>השאילתה האחרונה שבוצעה לבסיס הנתונים היתה:</td>
<td style="direction: ltr;">$1</td>
</tr>
<tr>
<td>הפונקציה הקוראת היתה:</td>
<td style="direction: ltr;">$2</td>
</tr>
<tr>
<td>הודעת השגיאה שהוחזרה על־ידי בסיס הנתונים:</td>
<td style="direction: ltr;">$3: $4</td>
</tr>
</table>',
"dberrortextcl"          => '<p><b>ארעה שגיאת תחביר בשאילתה לבסיס הנתונים</b>.</p>
<table class="toccolours"
<tr>
<th colspan="2" style="background-color: #F8F8F8; text-align: center;">מידע על השגיאה</th>
</tr>
<tr>
<td>השאילתה האחרונה שבוצעה לבסיס הנתונים היתה:</td>
<td style="direction: ltr;">$1</td>
</tr>
<tr>
<td>הפונקציה הקוראת היתה:</td>
<td style="direction: ltr;">$2</td>
</tr>
<tr>
<td>הודעת השגיאה שהוחזרה על־ידי בסיס הנתונים:</td>
<td style="direction: ltr;">$3: $4</td>
</tr>
</table>',
"noconnect"            => "ניסיון ההתחברות לבסיס הנתונים על $1 לא הצליח",
"nodb"                 => "לא ניתן לבחור את בסיס הנתונים $1",
"cachederror"          => "להלן מוצג עותק גיבוי (Cache), שכנראה איננו עדכני, של הדף המבוקש.",
"laggedslavemode"      => "אזהרה: הדף עשוי שלא להכיל עדכונים אחרונים.",
"readonly"             => "בסיס הנתונים נעול",
"enterlockreason"      => "הזינו סיבה לנעילת בסיס הנתונים, כולל הערכה לגבי מועד שחרור הנעילה.",
"readonlytext"         => "בסיס נתונים זה של האתר נעול ברגע זה לצורך הזנת נתונים ושינויים. ככל הנראה מדובר בתחזוקה שוטפת, שלאחריה יחזור האתר לפעולתו הרגילה.

המפתח שנעל את בסיס הנתונים סיפק את ההסבר הבא: $1",
"missingarticle"       => 'בסיס הנתונים לא מצא את הטקסט של הדף שהוא היה אמור למצוא, בשם "$1".

הדבר נגרם בדרך כלל באמצעות קישור ישן להשוואת גרסאות או גרסה קודמת של דף שנמחק.

אם זה אינו המקרה, כנראה שמצאת באג בתוכנה.

אנא דווח על כך למפתח תוך שמירת פרטי כתובת ה־URL.',
"readonly_lag"         => "בסיס הנתונים ננעל אוטומטית כדי לאפשר לבסיסי הנתונים המשניים להתעדכן מהבסיס הראשי.",
"internalerror"        => "שגיאה פנימית",
"filecopyerror"        => 'העתקת "$1" ל־"$2" לא הצליחה.',
"filerenameerror"      => 'שינוי השם של "$1" ל-"$2" לא הצליח.',
"filedeleteerror"      => 'מחיקת "$1" לא הצליחה.',
"filenotfound"         => 'הקובץ "$1" לא נמצא.',
"unexpected"           => 'ערך לא צפוי: "$1"="$2"',
"formerror"            => "שגיאה: לא יכול לשלוח טופס.",
"badarticleerror"      => "לא ניתן לבצע פעולה זו בדף זה.",
"cannotdelete"         => "מחיקת הדף או התמונה לא הצליחה. (יתכן שהוא נמחק כבר על־ידי מישהו אחר.)",
"badtitle"             => "כותרת שגויה",
"badtitletext"         => "כותרת הדף המבוקש הייתה לא־חוקית, ריקה, קישור ויקי פנימי, או פנים שפה שגוי.",
"perfdisabled"         => "שירות זה הופסק זמנית בכדי לא לפגוע בביצועי המערכת. עמכם הסליחה!",
"perfdisabledsub"      => "מוצג להלן עותק שמור של דף מ־$1:",
"perfcached"           => "המידע הבא הוא עותק שמור של המידע, ועשוי שלא להיות מעודכן.",
"perfcachedts"         => "המידע הבא הוא עותק שמור של המידע, שעודכן לאחרונה ב־$1.",
"wrong_wfQuery_params" => "הפרמטרים שהוזנו ל־wfQuery() אינם נכונים:<br />
פונקציה: $1<br />
שאילתה: $2",
"viewsource"           => "הצג מקור",
"viewsourcefor"        => "לדף $1",
"protectedtext"        => "דף זה הינו '''דף מוגן''' ולא ניתן לערוך אותו. ישנן מספר סיבות אפשריות להגנה כזו.

באפשרותכם לצפות בטקסט המקור של הדף, ואף להעתיקו:",
"protectedinterface"   => "דף זה הוא אחד מסדרת דפים המספקים הודעות מערכת לתוכנה, ונעול לעריכה למפעילי מערכת בלבד כדי למנוע השחתות של ההודעות.",
"editinginterface"     => "'''אזהרה:''' דף זה הוא אחד מסדרת דפים המספקים הודעות מערכת לתוכנה. שינויים בדף זה ישנו את הודעת המערכת לכל המשתמשים האחרים.",
"sqlhidden"            => "(שאילתת ה־SQL מוסתרת)",

# Login and logout pages
"logouttitle"        => "יציאה מהחשבון",
"logouttext"         => "יצאתם זה עתה מהחשבון. באפשרותכם להמשיך ולעשות שימוש ב{{grammar:תחילית|{{SITENAME}}}} באופן אנונימי, או לשוב ולהיכנס לאתר עם שם משתמש זהה או אחר.",
"welcomecreation"    => "== ברוך הבא, $1! ==
חשבונך נפתח. אל תשכח להתאים את הגדרות המשתמש שלך.",
"loginpagetitle"     => "כניסת משתמש",
"yourname"           => "שם משתמש",
"yourpassword"       => "סיסמה",
"yourpasswordagain"  => "הקש סיסמה שנית",
"remembermypassword" => "זכור את הסיסמה",
"yourdomainname"     => "התחום שלך",
"externaldberror"    => 'הייתה שגיאת הזדהות חיצונית לבסיס הנתונים, או שאינך רשאי לעדכן את חשבונך החיצוני.',
"loginproblem"       => "'''אירעה שגיאה בכניסה לאתר.'''<br />נסה שנית!",
"alreadyloggedin"    => "'''$1, כבר ביצעת כניסה לאתר!'''<br />",

"login"                      => "כניסה לחשבון",
"loginprompt"                => 'לפני הכניסה לחשבון ב{{grammar:תחילית|{{SITENAME}}}}, עליכם לוודא כי ה"עוגיות" (Cookies) מופעלות.',
"userlogin"                  => "כניסה / הרשמה לחשבון",
"logout"                     => "יציאה מהחשבון",
"userlogout"                 => "יציאה מהחשבון",
"notloggedin"                => "לא בחשבון",
"nologin"                    => "אין לכם חשבון? $1.",
"nologinlink"                => "אתם מוזמנים להרשם",
"createaccount"              => "צור משתמש חדש",
"gotaccount"                 => "כבר נרשמתם? $1.",
"gotaccountlink"             => "הכנסו לחשבון",
"createaccountmail"          => 'באמצעות דוא"ל',
"badretype"                  => "הסיסמאות שהזנת אינן מתאימות.",
"userexists"                 => "שם המשתמש שבחרתם נמצא בשימוש. אנא בחרו שם אחר.",
"youremail"                  => "דואר אלקטרוני *",
"username"                   => "שם משתמש:",
"uid"                        => "מספר סידורי:",
"yourrealname"               => "שם אמיתי *",
"yourlanguage"               => "שפת הממשק:",
"yourvariant"                => "שינוי",
"yournick"                   => "כינוי (לחתימות):",
"badsig"                     => "חתימה מסוגננת שגויה; אנא בדקו את תגיות ה־HTML.",
"email"                      => 'דוא"ל',
"prefs-help-email-enotif"    => 'כתובת זו משמשת גם למשלוח עדכונים דרך הדוא"ל (אם אפשרתם זאת).',
"prefs-help-realname"        => "* שם אמיתי (אופציונאלי): אם תבחרו לספק שם זה, הוא ישמש לייחוס עבודתכם אליכם.",
"loginerror"                 => "שגיאה בכניסה לאתר",
"prefs-help-email"           => '* דואר אלקטרוני (אופציונאלי): אפשרו לאחרים לשלוח לכם מסר דרך דף המשתמש שלכם ללא צורך לחשוף את כתובתכם.',
"nocookiesnew"               => "נוצר חשבון המשתמש שלכם, אך לא נכנסתם כמשתמשים רשומים למערכת כיוון שניטרלתם את העוגיות, ש{{grammar:תחילית|{{SITENAME}}}} משתמש בהן לצורך כניסה למערכת. אנא הפעילו אותן מחדש, ולאחר מכן תוכלו להיכנס למערכת עם שם המשתמש והסיסמה החדשים שלכם.",
"nocookieslogin"             => "לא הצלחתם להיכנס למערכת כמשתמשים רשומים כיוון שניטרלתם את העוגיות, ש{{grammar:תחילית|{{SITENAME}}}} משתמש בהן לצורך כניסה למערכת. אנא הפעילו אותן מחדש, ולאחר מכן תוכלו להיכנס למערכת עם שם המשתמש והסיסמה שלכם.",
"noname"                     => "לא הזנתם שם משתמש חוקי",
"loginsuccesstitle"          => "הכניסה הושלמה בהצלחה",
"loginsuccess"               => '\'\'\'נכנסתם ל{{grammar:תחילית|{{SITENAME}}}} בשם "$1".\'\'\'',
"nosuchuser"                 => 'אין משתמש בשם "$1".

אנא ודאו שהאיות נכון, או השתמשו בטופס שלהלן ליצירת חשבון משתמש חדש.',
"nosuchusershort"            => 'אין משתמש בשם "$1". אנא ודאו שהאיות נכון.',
"nouserspecified"            => "עליכם לציין שם משתמש.",
"wrongpassword"              => "הסיסמה שהקלדתם שגויה, אנא נסו שנית.",
"wrongpasswordempty"         => "הסיסמה שהקלדתם ריקה. אנא נסו שנית.",
"mailmypassword"             => "שלחו לי סיסמה חדשה",
"passwordremindertitle"      => "תזכורת סיסמה מ{{grammar:תחילית|{{SITENAME}}}}",
"passwordremindertext"       => 'מישהו (ככל הנראה אתם, מכתובת ה־IP מספר $1) ביקש שנשלח לכם סיסמה חדשה לכניסה לחשבון ב{{grammar:תחילית|{{SITENAME}}}} ($4). הסיסמה עבור המשתמש "$2" היא עתה "$3". עליכם להיכנס לאתר ולשנות את סיסמתכם בהקדם האפשרי. אם מישהו אחר ביקש סיסמה חדשה זו או אם נזכרתם בסיסמתכם ואינכם רוצים עוד לשנות אותה, באפשרותכם להתעלם מהודעה זו ולהמשיך להשתמש בסיסמתכם הישנה.',
"noemail"                    => 'לא רשומה כתובת דואר אלקטרוני עבור משתמש  "$1".',
"passwordsent"               => 'סיסמה חדשה נשלחה לכתובת הדואר האלקטרוני הרשומה עבור "$1".
אנא הכנסו חזרה לאתר אחרי שתקבלו אותה.',
"eauthentsent"               => 'דוא"ל אישור נשלח לכתובת הדוא"ל שקבעת.

לפני שדברי דוא"ל אחרים נשלחים לחשבון הזה, תצטרך לפעול לפי ההוראות בדוא"ל כדי לוודא שהדוא"ל הוא אכן שלך.',
"mailerror"                  => "שגיאה בשליחת דואר: $1",
"acct_creation_throttle_hit" => "מצטערים, יצרת כבר $1 חשבונות. אינך יכול ליצור חשבונות נוספים.",
"emailauthenticated"         => 'כתובת הדוא"ל שלך אושרה על־ידי $1.',
"emailnotauthenticated"      => 'כתובת הדוא"ל שלך <strong>עדיין לא אושרה</strong>. אף דוא"ל לא יישלח מאף אחת מהתכונות הבאות.',
"noemailprefs"               => 'אנא ציינו כתובת דוא"ל כדי שתכונות אלה יעבדו.',
"emailconfirmlink"           => 'אשר את כתובת הדוא"ל שלך',
"invalidemailaddress"        => 'כתובת הדוא"ל אינה מתקבלת כיוון שנראה שהיא בפורמט לא נכון. אנא הכנס כתובת נכונה או ותר על השדה הזה.',
"accountcreated"             => "החשבון נוצר",
"accountcreatedtext"         => "חשבון המשתמש $1 נוצר.",

# Edit page buttons
"bold_sample"     => "טקסט מודגש",
"bold_tip"        => "טקסט מודגש",
"italic_sample"   => "טקסט נטוי",
"italic_tip"      => "טקסט נטוי (לא מומלץ בעברית",
"link_sample"     => "קישור",
"link_tip"        => "קישור פנימי",
"extlink_sample"  => "http://www.example.com כותרת הקישור לתצוגה",
"extlink_tip"     => "קישור חיצוני (כולל קידומת http מלאה",
"headline_sample" => "כותרת",
"headline_tip"    => "כותרת – דרגה 2",
"math_sample"     => "formula",
"math_tip"        => "נוסחה מתמטית (LaTeX)",
"nowiki_sample"   => "טקסט לא מעוצב",
"nowiki_tip"      => "טקסט לא מעוצב (התעלם מסימני ויקי)",
"image_sample"    => "PictureFileName.jpg|left|thumb|250px|כיתוב תמונה",
"image_tip"       => "תמונה (שכבר הועלתה לשרת)",
"media_sample"    => "Example.ogg",
"media_tip"       => "קישור לקובץ מדיה",
"sig_tip"         => "חתימה + שעה",
"hr_tip"          => "קו אופקי (השתדלו להמנע משימוש בקו)",

# Edit pages
"summary"                   => "תקציר",
"subject"                   => "נושא/כותרת",
"minoredit"                 => "זהו שינוי משני",
"watchthis"                 => "עקוב אחרי דף זה",
"savearticle"               => "שמור דף",
"preview"                   => "תצוגה מקדימה",
"showpreview"               => "הראה תצוגה מקדימה",
"showlivepreview"           => "תצוגה מקדימה חיה",
"showdiff"                  => "הצג שינויים",
"anoneditwarning"           => "'''אזהרה:''' אינכם מחוברים לחשבון. כתובת ה־IP שלכם תירשם בהיסטוריית העריכות של הדף. אם לדעתכם זוהי פגיעה בפרטיותכם, עליכם [[{{ns:special}}:Userlogin|להיכנס לחשבון]].",
"missingsummary"            => '\'\'\'תזכורת:\'\'\' לא הזנתם תקציר עריכה. אם תלחצו שוב על "שמור דף", עריכתכם תישמר בלעדיו.',
"missingcommenttext"        => "אנא הקלידו את ההערה למטה.",
"blockedtitle"              => "המשתמש חסום",
"blockedtext"               => '<big>\'\'\'שם המשתמש או כתובת ה־IP שלכם נחסמו.\'\'\'</big>

החסימה בוצעה על־ידי $1. הסיבה שניתנה לכך היא \'\'\'$2\'\'\'.

באפשרותכם ליצור קשר עם $1 או עם כל אחד מ[[{{ns:project}}:מפעיל מערכת|מפעילי המערכת]] האחרים כדי לדון על החסימה.
אינכם יכולים להשתמש בתכונת "שלחו דואר אלקטרוני למשתמש זה" אם לא ציינתם כתובת דוא"ל תקפה ב[[{{ns:special}}:Preferences|העדפות המשתמש שלכם]].
כתובת ה־IP שלכם היא $3. אנא ציינו עובדה זו בכל פנייה למפעילי המערכת.',
"blockedoriginalsource"     => "טקסט המקור של '''$1''' מוצג למטה:",
"blockededitsource"         => "הטקסט של '''העריכות שלך''' לדף '''$1''' מוצג למטה:",
"whitelistedittitle"        => "כניסה לחשבון נדרשת לעריכה",
"whitelistedittext"         => "עליכם $1 כדי לערוך דפים.",
"whitelistreadtitle"        => "כניסה לחשבון נדרשת לקריאה",
"whitelistreadtext"         => "עליכם [[{{ns:special}}:Userlogin|להיכנס לחשבון]] כדי לקרוא דפים.",
"whitelistacctitle"         => "אינכם מורשים ליצור חשבון",
"whitelistacctext"          => "עליכם [[{{ns:special}}:Userlogin|להיכנס לחשבון]] שיש לו את ההרשאה ליצור חשבונות כדי ליצור חשבון.",
"confirmedittitle"          => 'הנכם חייבים לאמת את כתובת הדוא"ל שלכם כדי לערוך',
"confirmedittext"           => 'עליכם לאמת את כתובת הדוא"ל שלכם לפני שתוכלו לערוך דפים. אנא הגדירו ואמתו את כתובת הדוא"ל שלכם באמצעות [[{{ns:special}}:Preferences|העדפות המשתמש]] לשכם.',
"loginreqtitle"             => "כניסה לחשבון נדרשת",
"loginreqlink"              => "להיכנס לחשבון",
"loginreqpagetext"          => "עליכם $1 כדי לצפות בדפים אחרים.",
"accmailtitle"              => "הסיסמה נשלחה",
"accmailtext"               => 'הסיסמה עבור "$1" נשלחה אל $2.',
"newarticle"                => "(חדש)",
"newarticletext"            => 'הגעתם לדף שעדיין איננו קיים. כדי ליצור דף חדש, כתבו את התוכן שלכם בתיבת הטקסט למטה.

אם הגעתם לכאן בטעות, פשוט לחצו על מקש ה־\'\'\'Back\'\'\' בדפדפן שלכם.',
"anontalkpagetext"          => "----
'''זהו דף שיחה של משתמש אנונימי שעדיין לא יצר חשבון במערכת, או שהוא לא משתמש בו. כיוון שכך, אנו צריכים להשתמש בכתובת ה־IP כדי לזהותו. ייתכן שכתובת IP זו תייצג מספר משתמשים. אם אתם משתמשים אנונימיים ומרגישים שקיבלתם הודעות בלתי רלוונטיות, אנא [[{{ns:special}}:Userlogin|צרו חשבון או הכנסו]] כדי להימנע מבלבול עתידי עם משתמשים אנונימיים נוספים.'''
----",
"noarticletext"             => "אין עדיין טקסט בדף זה. באפשרותכם [[{{ns:special}}:Search/{{PAGENAME}}|לחפש את {{PAGENAME}} באתר]], או [{{fullurl:{{FULLPAGENAME}}|action=edit}} ליצור דף זה].",
"clearyourcache"            => "'''הערה:''' לאחר השמירה, עליכם לנקות את זכרון המטמון (Cache) של הדפדפן על־מנת להבחין בשינויים.
* ב'''מוזילה''', '''פיירפוקס''' או '''ספארי''', לחצו על מקש ה־Shift בעת לחיצתכם על '''העלה מחדש''' (Reload), או הקישו Ctrl+Shift+R (או Cmd+Shift+R במקינטוש של אפל).
* ב'''אינטרנט אקספלורר''', לחצו על מקש ה־Ctrl בעת לחיצתכם על '''רענן''' (Refresh), או הקישו על Ctrl+F5.
* ב־'''Konqueror''', פשוט לחצו על '''העלה מחדש''' (Reload), או הקישו על F5.
* ב'''אופרה''', ייתכן שתצטרכו להשתמש ב'''כלים''' (Tools) > '''העדפות''' (Preferences) כדי לנקות לחלוטין את זכרון המטמון.",
"usercssjsyoucanpreview"    => '\'\'\'עצה:\'\'\' השתמשו בלחצן "הראה תצוגה מקדימה" כדי לבחון את גליון ה־CSS או את סקריפט ה־JavaScript החדש שלכם לפני השמירה.',
"usercsspreview"            => "'''זכרו שזו רק תצוגה מקדימה של גליון ה־CSS שלכם, ושהוא טרם נשמר!'''",
"userjspreview"             => "'''זכרו שזו רק תצוגה מקדימה של סקריפט ה־JavaScript שלכם, ושהוא טרם נשמר!'''",
"userinvalidcssjstitle"     => '\'\'\'אזהרה\'\'\': הרקע "$1" אינו קיים. זכרו שדפי CSS ו־JavaScript מותאמים אישית משתמשים בכותרת עם אותיות קטנות – למשל, {{ns:user}}:דוגמה/monobook.css ולא {{ns:user}}:דוגמה/Monobook.css. כמו כן, יש להקפיד על שימוש ב־/ ולא ב־\.',
"updated"                   => "(מעודכן)",
"note"                      => "'''הערה:'''",
"previewnote"               => "זכרו שזו רק תצוגה מקדימה, והדף עדיין לא נשמר!",
"session_fail_preview"      => "'''מצטערים! לא ניתן לבצע את עריכתכם עקב אובדן קשר עם השרת. אנא נסו שנית. אם זה לא עוזר, אנא צאו מהחשבון ונסו שנית.",
"previewconflict"           => "תצוגה מקדימה זו מציגה כיצד ייראה הטקסט בחלון העריכה העליון, אם תבחרו לשמור אותו.",
"session_fail_preview_html" => "'''מצטערים! לא ניתן לבצע את עריכתם עקב אובדן קשר עם השרת.'''

כיוון שבאתר זה אפשרות השימוש ב־HTML מאופשרת, התצוגה המקדימה מוסתרת כדי למנוע התקפות JavaScript.

'''אם זהו ניסיון עריכה לגיטימי, אנא נסו שנית. אם זה לא עוזר, נסו לצאת מהחשבון ולהיכנס אליו שנית.'''",
"importing"                 => "מייבא את $1",
"editing"                   => "עורך את $1",
"editingsection"            => "עורך את $1 (פסקה)",
"editingcomment"            => "עורך את $1 (הערה)",
"editconflict"              => "התנגשות עריכה: $1",
"explainconflict"           => "משתמש אחר שינה את הדף מאז שהתחלתם לערוך אותו.

חלון העריכה העליון מכיל את הטקסט בדף כפי שהוא עתה.

השינויים שלכם מוצגים בחלון העריכה התחתון.

עליכם למזג את השינויים שלכם לתוך הטקסט הקיים.

'''רק''' הטקסט בחלון העריכה העליון ישמר כשתשמרו את הדף.",
"yourtext"                  => "הטקסט שלך",
"storedversion"             => "גרסה שמורה",
"nonunicodebrowser"         => "'''אזהרה: הדפדפן שלך אינו תואם לתקן יוניקוד. בשל כך הוכנס לפעולה מעקף של הבאג, כדי לאפשר לך לערוך דפים בבטחה: תווים שאינם ב־ASCII יוצגו בתיבת העריכה כקודים הקסדצימליים.",
"editingold"                => "'''זהירות: אתם עורכים גרסה לא עדכנית של דף זה.

אם תשמרו את הדף, כל השינויים שנעשו מאז גרסה זו יאבדו.'''",
"yourdiff"                  => "הבדלים",
"copyrightwarning"          => '<div id="editing-warn">\'\'\'שימו לב:\'\'\' תרומתכם ל{{grammar:תחילית|{{SITENAME}}}} תפורסם תחת תנאי הרישיון $2 (ראו $1 לפרטים נוספים). אם אינכם רוצים שעבודתכם תהיה זמינה לעריכה על־ידי אחרים, שתופץ לעיני כל, ושאחרים יוכלו להעתיק ממנה בציון המקור – אל תפרסמו אותה פה. כמו־כן, אתם מבטיחים לנו כי כתבתם את הטקסט הזה בעצמכם, או העתקתם אותו ממקור שאינו מוגן על־ידי זכויות יוצרים. \'\'\'אל תעשו שימוש בחומר המוגן בזכויות יוצרים ללא רשות!\'\'\'</div>',
"copyrightwarning2"         => '<div id="editing-warn">\'\'\'שימו לב:\'\'\' תורמים אחרים עשויים לערוך או אף להסיר את תרומתכם ל{{grammar:תחילית|{{SITENAME}}}}. אם אינכם רוצים שעבודתכם תהיה זמינה לעריכה על־ידי אחרים – אל תפרסמו אותה פה. כמו־כן, אתם מבטיחים לנו כי כתבתם את הטקסט הזה בעצמכם, או העתקתם אותו ממקור שאינו מוגן על־ידי זכויות יוצרים (ראו $1 לפרטים נוספים). \'\'\'אל תעשו שימוש בחומר המוגן בזכויות יוצרים ללא רשות!\'\'\'</div>',
"longpagewarning"           => "'''אזהרה: גודל דף זה הוא $1 קילובייטים. בדפדפנים מסוימים יהיו בעיות בעריכת דף הגדול מ־32 קילובייטים. אנא שיקלו לחלק דף זה לדפים קטנים יותר. אם זהו דף שיחה, שיקלו לארכב אותו.'''",
"longpageerror"             => "'''שגיאה: הטקסט ששלחתם הוא באורך $1 קילובייטים, אך אסור לו להיות ארוך יותר מהמקסימום של $2 קילובייטים. לא ניתן לשומרו.'''",
"readonlywarning"           => "'''אזהרה: בסיס הנתונים ננעל לצורך תחזוקה. בזמן זה אי אפשר לשמור את הטקסט הערוך. בינתיים, עד סיום התחזוקה, אתם יכולים להשתמש בעורך חיצוני. אנו מתנצלים על התקלה.'''",
"protectedpagewarning"      => "'''אזהרה: דף זה הוא דף מוגן וניתן לעריכה על־ידי מפעילי מערכת בלבד. אנא ודאו שאתם פועלים על־פי העקרונות לעריכת דפים אלו.'''",
"semiprotectedpagewarning"  => "'''הערה:''' דף זה ננעל כך שרק משתמשים רשומים יכולים לערוך אותו.",
"templatesused"             => "תבניות המופיעות בדף זה:",
"edittools"                 => "<!-- הטקסט הנכתב כאן יוצג מתחת לטפסי עריכת דפים והעלאת קבצים, ולפיכך ניתן לכתוב להציג בו תווים קשים לכתיבה, קטעים מוכנים של טקסט ועוד. -->",
"nocreatetitle"             => "יצירת הדפים הוגבלה",
"nocreatetext"              => "אתר זה מגביל את האפשרות ליצור דפים חדשים. באפשרותכם לחזור אחורה ולערוך דף קיים, או [[{{ns:special}}:Userlogin|להיכנס לחשבון]].",
"cantcreateaccounttitle"    => "לא ניתן ליצור את החשבון",
"cantcreateaccounttext"     => "אפשרות יצירת החשבונות מכתובת ה־IP הזו (<b>$1</b>) נחסמה, כנראה עקב השחתות מתמשכות מבית־הספר או ספק האינטרנט שלך.",

# History pages
"revhistory"                  => "היסטוריית שינויים",
"viewpagelogs"                => "הצג יומנים עבור דף זה",
"nohistory"                   => "אין היסטוריית שינויים עבור דף זה.",
"revnotfound"                 => "גרסה זו לא נמצאה",
"revnotfoundtext"             => "הגרסה הישנה של דף זה לא נמצאה. אנא בדקו את כתובת הקישור שהוביל אתכם הנה.",
"loadhist"                    => "טוען את היסטוריית השינויים של הדף",
"currentrev"                  => "גרסה נוכחית",
"revisionasof"                => "גרסה מתאריך $1",
"old-revision-navigation"     => "גרסה מתאריך $1; $5<br />($6) $3 | $2 | $4 ($7)",
"previousrevision"            => "→ הגרסה הקודמת",
"nextrevision"                => "הגרסה הבאה ←",
"currentrevisionlink"         => "הגרסה הנוכחית",
"cur"                         => "נוכ",
"next"                        => "הבא",
"last"                        => "אחרון",
"orig"                        => "מקור",
"histlegend"                  => "השוואת גרסאות: סמנו את תיבות האפשרויות של הגרסאות המיועדות להשוואה, והקישו על Enter או על הכפתור שלמעלה או למטה.<br />
מקרא: (נוכ) = הבדלים עם הגרסה הנוכחית, (אחרון) = הבדלים עם הגרסה הקודמת, מ = שינוי משני",
"deletedrev"                  => "[נמחק]",
"histfirst"                   => "ראשונות",
"histlast"                    => "אחרונות",
"rev-deleted-comment"         => "(תקציר העריכה הוסתר)",
"rev-deleted-user"            => "(שם המשתמש הוסתר)",
"rev-deleted-text-permission" => '<div class="mw-warning plainlinks">
גרסת הדף הזו הוסרה מהארכיונים הציבוריים. ייתכן שישנם פרטים נוספים על כך ב[{{fullurl:{{ns:special}}:Log/delete|page={{FULLPAGENAMEE}}}} יומן המחיקות].
</div>',
"rev-deleted-text-view"       => '<div class="mw-warning plainlinks">
גרסת הדף הזו הוסרה מהארכיונים הציבוריים. כמפעיל מערכת, באפשרותך לצפות בגרסה; ייתכן שישנם פרטים נוספים על כך ב[{{fullurl:{{ns:special}}:Log/delete|page={{FULLPAGENAMEE}}}} יומן המחיקות].
</div>',
# "rev-delundel"              => "מחק/שחזר",
"rev-delundel"                => "הצג/הסתר",

"history-feed-title"          => "היסטוריית גרסאות",
"history-feed-description"    => "היסטוריית הגרסאות של הדף הזה בוויקי",
"history-feed-item-nocomment" => "$1 ב־$2", # user at time
"history-feed-empty"          => "הדף המבוקש לא נמצא.
ייתכן שהוא נמחק מהוויקי, או ששמו שונה.
נסו [[{{ns:special}}:Search|לחפש בוויקי]] אחר דפים רלוונטיים חדשים.",

# Revision deletion
"revisiondelete"            => "מחיקת ושחזור גרסאות",
"revdelete-nooldid-title"   => "אין גרסת מטרה",
"revdelete-nooldid-text"    => "לא ציינתם גרסת או גרסאות מטרה עליהן תבוצע פעולה זו.",
"revdelete-selected"        => "הגרסאות שנבחרו של [[:$1]]:",
"revdelete-text"            => "גרסאות מחוקות עדיין יופיעו בהיסטוריית הדף, אך התוכן שלהן לא יהיה זמין לציבור.

מפעילי מערכת אחרים באתר עדיין יוכלו לגשת לתוכן הנסתר ויוכלו לשחזר אותו שוב דרך הממשק הזה, אלא אם כן הגבלה נוספת הוטלה על־ידי מנהלי האתר.",
"revdelete-legend"          => "הגדרת הגבלות הגרסה:",
"revdelete-hide-text"       => "הסתר את תוכן הגרסה",
"revdelete-hide-comment"    => "הסתר את תקציר העריכה",
"revdelete-hide-user"       => "הסתר את שם המשתמש או כתובת ה־IP של העורך",
"revdelete-hide-restricted" => "החל הגבלות אלו גם על מפעילי מערכת",
"revdelete-log"             => "הערה ביומן:",
"revdelete-submit"          => "החל לגרסה הנוכחית",
"revdelete-logentry"        => "שינה הצגת גרסה לדף [[$1]]",

# Diffs
"difference"                => "(הבדלים בין גרסאות)",
"loadingrev"                => "טוען את הגרסה להשוואה",
"lineno"                    => "שורה $1:",
"editcurrent"               => "ערוך גרסה נוכחית של הדף",
"selectnewerversionfordiff" => "בחר גרסה חדשה יותר להשוואה",
"selectolderversionfordiff" => "בחר גרסה ישנה יותר להשוואה",
"compareselectedversions"   => "השווה את הגרסאות שנבחרו",

# Search results
"searchresults"     => "תוצאות החיפוש",
"searchresulttext"  => "ראו גם [[{{ns:project}}:חיפוש|מידע נוסף על חיפוש ב{{grammar:תחילית|{{SITENAME}}}}]].",
"searchsubtitle"    => "לחיפוש המונח '''[[:$1]]'''",
"searchsubtitleinvalid"    => "לחיפוש המונח '''$1'''",
"badquery"          => "שגיאה בניסוח השאילתה.",
"badquerytext"      => 'לא הצלחנו לבצע את השאילתה, ככל הנראה כיוון שניסיתם לחפש מילה בעלת פחות משלוש אותיות. חיפוש כזה עדיין אינו נתמך במערכת. ייתכן גם ששגיתם בהקלדת השאליתה – לדוגמה, כתבתם "דג וגם וגם משקל".

ניתן לנסות שאילתה אחרת.',
"matchtotals"       => 'לחיפוש "$1" נמצאו $2 דפים עם כותרות תואמות ו־$3 דפים עם תוכן תואם',
"noexactmatch"         => 'אין דף שכותרתו "$1". באפשרותכם [[:$1|ליצור את הדף]].',
"titlematches"      => "כותרות דפים תואמות",
"notitlematches"    => "אין כותרות דפים תואמות",
"textmatches"       => "דפים עם תוכן תואם",
"notextmatches"     => "אין דפים עם תוכן תואם",
"prevn"             => "$1 הקודמים",
"nextn"             => "$1 הבאים",
"viewprevnext"      => "צפו ב - ($1) ($2) ($3).",
"showingresults"    => "מציג עד <b>$1</b> תוצאות החל ממספר #<b>$2</b>:",
"showingresultsnum" => "מציג <b>$3</b> תוצאות החל ממספר #<b>$2</b>:",
"nonefound"         => "לא נמצאו דפים עם תוכן תואם. אנא ודאו שהקלדתם את שאילתת החיפוש נכון. אם אכן הקלדתם אותה נכון, נסו לחפש נושא כללי יותר.

חיפושים כושלים מסוג זה נגרמים בדרך כלל בגלל ציון יותר ממילת חיפוש אחת, שכן במקרה זה מופיעים רק דפים הכוללים את כל המילים.",
"powersearch"       => "חפש",
"powersearchtext"   => "חפש במרחבי שם:<br />$1<br />$2 הצג גם דפי הפנייה<br />חפש $3 $9",
"searchdisabled"    => "לצערנו, עקב עומס על המערכת, לא ניתן לחפש כעת בטקסט המלא של הדפים. באפשרותכם להשתמש בינתיים בגוגל, אך שימו לב שייתכן שהוא אינו מעודכן.",
"blanknamespace"    => "(ראשי)",

# Preferences page
"preferences"           => "העדפות",
"mypreferences"         => "ההעדפות שלי",
"prefsnologin"          => "לא נרשמת באתר",
"prefsnologintext"      => "עליכם [[{{ns:special}}:Userlogin|להיכנס לחשבון]] כדי לשנות העדפות משתמש.",
"prefsreset"            => "העדפותיך שוחזרו לברירת המחדל.",
"qbsettings"            => "הגדרות סרגל כלים",
"changepassword"        => "שנה סיסמה",
"skin"                  => "רקע",
"math"                  => "נוסחאות מתמטיות",
"dateformat"            => "מבנה תאריך",
"datedefault"           => "ברירת המחדל",
"datetime"              => "תאריך ושעה",
"math_failure"          => "עיבוד הנוסחה נכשל",
"math_unknown_error"    => "שגיאה לא ידועה",
"math_unknown_function" => "פונקציה לא מוכרת",
"math_lexing_error"     => "שגיאת לקסינג",
"math_syntax_error"     => "שגיאת תחביר",
"math_image_error"      => "ההמרה ל־PNG נכשלה; אנא בדקו אם התקנתם נכון את latex, את dvips, את gs ואת convert.",
"math_bad_tmpdir"       => "התוכנה לא הצליחה לכתוב או ליצור את הספרייה הזמנית של המתמטיקה",
"math_bad_output"       => "התוכנה לא הצליחה לכתוב או ליצור את ספריית הפלט של המתמטיקה",
"math_notexvc"          => "קובץ בר־ביצוע של texvc אינו זמין; אנא ראו את קובץ ה־README למידע על ההגדרות.",
"prefs-personal"        => "פרטי המשתמש",
"prefs-rc"              => "שינויים אחרונים",
"prefs-watchlist"       => "רשימת המעקב",
"prefs-watchlist-days"  => "מספר הימים לתצוגה ברשימת המעקב:",
"prefs-watchlist-edits" => "מספר העריכות לתצוגה ברשימת המעקב המורחבת:",
"prefs-misc"            => "שונות",
"saveprefs"             => "שמור העדפות",
"resetprefs"            => "שחזר ברירת מחדל",
"oldpassword"           => "סיסמה ישנה",
"newpassword"           => "סיסמה חדשה",
"retypenew"             => "הקלד סיסמה חדשה שנית",
"textboxsize"           => "עריכה",
"rows"                  => "שורות",
"columns"               => "עמודות",
"searchresultshead"     => "חיפוש",
"resultsperpage"        => "מספר תוצאות בעמוד",
"contextlines"          => "שורות לכל תוצאה",
"contextchars"          => "מספר תווי קונטקסט בשורה",
"stubthreshold"         => "סף להצגת דפים קצרים (קצרמרים)",
"recentchangescount"    => "מספר שינויים שיוצגו בדף שינויים אחרונים",
"savedprefs"            => "העדפותיך נשמרו.",
"timezonelegend"        => "אזור זמן",
"timezonetext"          => "הפרש השעות בינך לבין השרת (UTC).",
"localtime"             => "זמן מקומי",
"timezoneoffset"        => "הפרש",
"servertime"            => "השעה הנוכחית בשרת היא",
"guesstimezone"         => "קבל מהדפדפן",
"allowemail"            => 'אפשר קבלת דוא"ל ממשתמשים אחרים',
"defaultns"             => "כברירת מחדל, חפש במרחבי השם אלו:",
"default"               => "ברירת מחדל",
"files"                 => "קבצים",

# User rights
"userrights-lookup-user"     => "נהלו קבוצות משתמש",
"userrights-user-editname"   => "הכניסו שם משתמש:",
"editusergroup"              => "ערכו קבוצות משתמשים",
"userrights-editusergroup"   => "ערכו קבוצות משתמש",
"saveusergroups"             => "שמור קבוצות משתמש",
"userrights-groupsmember"    => "חבר בקבוצות:",
"userrights-groupsavailable" => "קבוצות זמינות:",
"userrights-groupshelp"      => "אנא בחרו קבוצות שברצונכם שהמשתמש יתווסף אליהן או יוסר מהן.
קבוצות שלא נבחרו לא ישתנו. באפשרותכם לבטל בחירה של קבוצה באמצעות לחיצה על הכפתור השמאלי של העכבר ועל Ctrl מעליה.",

# Groups
"group"            => "קבוצה:",
"group-bot"        => "בוטים",
"group-sysop"      => "מפעילי מערכת",
"group-bureaucrat" => "ביורוקרטים",
"group-all"        => "(הכול)",

"group-bot-member"        => "בוט",
"group-sysop-member"      => "מפעיל מערכת",
"group-bureaucrat-member" => "ביורוקרט",

"grouppage-bot"        => "{{ns:project}}:בוט",
"grouppage-sysop"      => "{{ns:project}}:מפעיל מערכת",
"grouppage-bureaucrat" => "{{ns:project}}:ביורוקרט",

# Recent changes
"changes"                           => "שינויים",
"recentchanges"                     => "שינויים אחרונים",
"recentchangestext"                 => "עקבו אחרי השינויים האחרונים באתר בדף זה.",
"rcnote"                            => "להלן <b>$1</b> השינויים האחרונים שבוצעו ב־$2 הימים האחרונים, עד התאריך <b>$3</b>:",
"rcnotefrom"                        => "להלן <b>$1</b> השינויים האחרונים שבוצעו החל מתאריך <b>$2</b>:",
"rclistfrom"                        => "הצג שינויים חדשים החל מ־$1",
"rcshowhideminor"                   => "$1 שינויים משניים",
"rcshowhidebots"                    => "$1 בוטים",
"rcshowhideliu"                     => "$1 משתמשים רשומים",
"rcshowhideanons"                   => "$1 משתמשים אנונימיים",
"rcshowhidepatr"                    => "$1 עריכות בדוקות",
"rcshowhidemine"                    => "$1 עריכות שלי",
"rclinks"                           => "הצג $1 שינויים אחרונים ב-$2 הימים האחרונים.<br /> $3",
"diff"                              => "הבדל",
"hist"                              => "היסטוריה",
"hide"                              => "הסתר",
"show"                              => "הצג",
"minoreditletter"                   => "מ",
"newpageletter"                     => "ח",
"boteditletter"                     => "ב",
"sectionlink"                       => "←",
"number_of_watching_users_pageview" => "[$1 משתמש/ים צופים]",
"rc_categories"                     => 'הגבל לקטגוריות (הפרד עם "|")',
"rc_categories_any"                 => "הכול",

# Upload
"upload"                      => "העלאת קובץ לשרת",
"uploadbtn"                   => "העלה קובץ",
"reupload"                    => "העלה שנית",
"reuploaddesc"                => "חזרו לטופס העלאת קבצים לשרת.",
"uploadnologin"               => "לא נכנסתם לאתר",
"uploadnologintext"           => "עליכם [[{{ns:special}}:Userlogin|להיכנס לחשבון]] כדי להעלות קבצים.",
"upload_directory_read_only"  => "תיקיית ההעלאות ($1) אינה ניתנת לכתיבה על־ידי שרת האינטרנט, ולפיכך הוא אינו יכול להעלות את התמונה.",
"uploaderror"                 => "שגיאה בהעלאת הקובץ",
"uploadtext"                  => "השתמשו בטופס להלן כדי להעלות תמונות. כדי לראות או לחפש תמונות שהועלו בעבר אנא פנו ל[[{{ns:special}}:Imagelist|רשימת הקבצים המועלים]], וכמו כן, העלאות מוצגות ב[[{{ns:special}}:Log/upload|יומן ההעלאות]], ומחיקות ב[[{{ns:special}}:Log/delete|יומן המחיקות]].

כדי לכלול תמונה בדף, השתמשו בקישור בצורות '''<nowiki>[[{{ns:image}}:file.jpg]]</nowiki>''' לתמונות בפורמט JPG (המיועד לתצלומים), '''<nowiki>[[{{ns:image}}:file.png]]</nowiki>''' לתמונות בפורמט PNG (לאיורים, שרטוטים וסמלים). כדי לקשר ישירות לקובץ קול, השתמשו בקישור בצורה '''<nowiki>[[{{ns:media}}:file.jpg]]</nowiki>''' לקבצי קול בפורמט OGG.",
"uploadlog"                   => "יומן העלאות קבצים",
"uploadlogpage"               => "יומן העלאות",
"uploadlogpagetext"           => "להלן רשימה של העלאות הקבצים האחרונות שבוצעו.",
"filename"                    => "שם הקובץ",
"filedesc"                    => "תקציר",
"fileuploadsummary"           => "תיאור:",
"filestatus"                  => "מעמד זכויות יוצרים",
"filesource"                  => "מקור",
"copyrightpage"               => "{{ns:project}}:זכויות יוצרים",
"copyrightpagename"           => "זכויות היוצרים של {{SITENAME}}",
"uploadedfiles"               => "קבצים שהועלו",
"ignorewarning"               => "התעלם מהאזהרה ושמור את הקובץ בכל זאת.",
"ignorewarnings"              => "התעלם מכל האזהרות",
"minlength"                   => "שמות של קבצי תמונה צריכים להיות בני שלושה תווים לפחות.",
"illegalfilename"             => 'הקובץ "$1" מכיל תוים בלתי חוקיים. אנא שנו את שמו ונסו להעלותו שנית.',
"badfilename"                 => 'שם התמונה שונה ל־"$1".',
"badfiletype"                 => '"$1" אינו פורמט מומלץ לשמירת תמונות.',
"largefile"                   => "מומלץ שגודל הקובץ לא יחרוג מ־$1 בייטים (גודל הקובץ שהעליתם הוא $2 בייטים).",
"largefileserver"             => "גודל הקובץ שהעליתם חורג ממגבלת השרת.",
"emptyfile"                   => "הקובץ שהעליתם ריק. ייתכן שהסיבה לכך היא שגיאת הקלדה בשם הקובץ. אנא ודאו שזהו הקובץ שברצונך להעלות.",
"fileexists"                  => 'קובץ בשם זה כבר קיים, אנא בדקו את $1 אם אינכם בטוחים שברצונכם להחליף אותו.',
"fileexists-forbidden"        => "קובץ בשם זה כבר קיים; אנא חזרו לדף הקודם והעלו את הקובץ תחת שם חדש.
[[{{ns:image}}:$1|thumb|center|$1]]",
"fileexists-shared-forbidden" => "קובץ בשם זה כבר קיים כקובץ משותף; אנא חזרו לדף הקודם והעלו את הקובץ תחת שם חדש.
[[{{ns:image}}:$1|thumb|center|$1]]",
"successfulupload"            => "העלאת הקובץ הושלמה בהצלחה",
"fileuploaded"                => "הקובץ $1 הועלה לשרת בהצלחה.
אנא השתמשו בקישור $2 כדי לעבור לדף תיאור הקובץ ולמלא את כל המידע אודות הקובץ, כגון מאין הגיע, מתי נוצר ועל־ידי מי, וכל פרט אחר שאתם יודעים עליו. אם זו תמונה, באפשרותכם להכלילה בדפים כך: '''<nowiki>[[{{ns:image}}:$1|thumb|Description]]</nowiki>'''",
"uploadwarning"               => "אזהרת העלאת קבצים",
"savefile"                    => "שמור קובץ",
"uploadedimage"               => 'העלה את הקובץ "[[$1]]"',
"uploaddisabled"              => "העלאת קבצים מנוטרלת",
"uploaddisabledtext"          => "אפשרות העלאת הקבצים מנוטרלת באתר זה.",
"uploadscripted"              => "הקובץ כולל קוד סקריפט או HTML שעשוי להתפרש או להתבצע בטעות על־ידי הדפדפן.",
"uploadcorrupt"               => "קובץ זה אינו תקין או שהסיומת שלו איננה מתאימה. אנא בדקו את הקובץ והעלו אותו שוב.",
"uploadvirus"                 => 'הקובץ מכיל וירוס! פרטים: <div style="direction: ltr;">$1</div>',
"sourcefilename"              => "שם הקובץ",
"destfilename"                => "שמור קובץ בשם",
"watchthisupload"             => "עקוב אחרי דף זה",
"filewasdeleted"              => "קובץ בשם זה כבר הועלה בעבר, ולאחר מכן נמחק. אנא בדקו את הדף $1 לפני שתמשיכו להעלותו שנית.",

"license"            => "רישיון",
"nolicense"          => "אין",
"upload_source_url"  => " (כתובת URL תקפה ונגישה)",
"upload_source_file" => " (קובץ במחשב שלך)",

# Image list
"imagelist"                 => "רשימת תמונות",
"imagelisttext"             => "להלן רשימה של {{plural:$1|תמונה אחת|$1 תמונות}}, ממוינות $2:",
"imagelistforuser"          => "מוצגות רק התמונות שהועלו על־ידי $1.",
"getimagelist"              => "מושך את רשימת התמונות",
"ilsubmit"                  => "חיפוש",
"showlast"                  => "הצג $1 תמונות אחרונות ממוינות $2",
"byname"                    => "לפי שם",
"bydate"                    => "לפי תאריך",
"bysize"                    => "לפי גודל",
"imgdelete"                 => "מחק",
"imgdesc"                   => "תיאור",
"imgfile"                   => "קובץ",
"imglegend"                 => "מקרא: (תיאור) הצג/ערוך תיאור התמונה.",
"imghistory"                => "היסטורית קובץ תמונה",
"revertimg"                 => "חזור",
"deleteimg"                 => "מחק",
"deleteimgcompletely"       => "מחק את כל גרסאות התמונה",
"imghistlegend"             => "מקרא (נוכ) = זו התמונה הנוכחית, (מחק) = מחק גרסה ישנה זו, (חזור) חזור לגרסה ישנה זו.<br />
'''לחצו על תאריך לראות את התמונה שהועלתה בתאריך זה'''.",
"imagelinks"                => "קישורי תמונות",
"linkstoimage"              => "הדפים הבאים משתמשים בתמונה זו:",
"nolinkstoimage"            => "אין דפים המשתמשים בתמונה זו.",
"sharedupload"              => "קובץ זה הוא קובץ משותף וניתן להשתמש בו גם באתרים אחרים.",
"shareduploadwiki"          => "למידע נוסף, ראו את $1.",
"shareduploadwiki-linktext" => "דף תיאור הקובץ",
"noimage"                   => "לא נמצא קובץ בשם זה, אך יש באפשרותכם $1 חלופי.",
"noimage-linktext"          => "להעלות קובץ",
"uploadnewversion-linktext" => "העלו גרסה חדשה של קובץ זה",
"imagelist_date"            => "תאריך",
"imagelist_name"            => "שם",
"imagelist_user"            => "משתמש",
"imagelist_size"            => "גודל (בתים)",
"imagelist_description"     => "תיאור",
"imagelist_search_for"      => "חיפוש תמונה בשם:",

# MIME search
"mimesearch" => "חיפוש MIME",
"mimetype"   => "סוג MIME:",
"download"   => "הורדה",

# Unwatched pages
"unwatchedpages" => "דפים שאינם נצפים",

# List redirects
"listredirects" => "רשימת הפניות",

# Unused templates
"unusedtemplates"     => "תבניות שאינן בשימוש",
"unusedtemplatestext" => "דף זה מכיל רשימה של כל הדפים במרחב השם של התבניות שאינם נכללים בדף אחר. אנא זכרו לבדוק את הקישורים האחרים לתבניות לפני שתמחקו אותן.",
"unusedtemplateswlh"  => "קישורים אחרים",

# Random redirect
"randomredirect" => "הפניה אקראית",

# Statistics
"statistics"             => "סטטיסטיקות",
"sitestats"              => "סטטיסטיקות {{SITENAME}}",
"userstats"              => "סטטיסטיקות משתמשים",
"sitestatstext"          => 'בבסיס הנתונים יש בסך הכול \'\'\'$1\'\'\' דפים. מספר זה כולל דפים שאינם דפי תוכן, כגון דפי שיחה, דפים אודות {{SITENAME}}, קצרמרים, דפי תוכן ללא קישורים פנימיים, הפניות, וכיוצא בזה. אם לא סופרים את הדפים שאינם דפי תוכן, נשארים \'\'\'$2\'\'\' דפים שהם ככל הנראה דפי תוכן לכל דבר.

מאז תחילת פעולתו של האתר, היו באתר \'\'\'$3\'\'\' צפיות בדפים, ובוצעו \'\'\'$4\'\'\' פעולות עריכה.

בסך הכול בוצעו בממוצע \'\'\'$5\'\'\' עריכות לדף, והיו \'\'\'$6\'\'\' צפיות לכל עריכה.

אורך [http://meta.wikimedia.org/wiki/Help:Job_queue תור המשימות] הוא \'\'\'$7\'\'\'.

\'\'\'$8\'\'\' קבצים הועלו לאתר עד כה.',
"userstatstext"          => "ישנם '''$1''' [[{{ns:special}}:Listusers|משתמשים רשומים]] באתר, '''$2''' (או $4%) מתוכם הם $5.",
"statistics-mostpopular" => "הדפים הנצפים ביותר",

# Disambiguations Page
"disambiguations"            => "דפי פירושונים",
"disambiguationspage"        => "{{ns:template}}:פירושונים",
"disambiguationstext"        => "<p>הדפים שלהלן מקשרים אל <b>דפי פירושונים</b>. תפקיד דפים אלה הוא להפנות לדף הנושא הרלוונטי.</p>

<p>אנו מתייחסים לדף כדף פירושונים אם הוא מקושר לדף $1.</p>

<p>קישורים המגיעים לדף ממרחבי שם אחרים <b>אינם</b> מוצגים כאן.</p>",

# Double redirects page
"doubleredirects"            => "הפניות כפולות",
"doubleredirectstext"        => "<p><b>שימו לב</b>: רשימה זו עלולה לכלול דפים שנמצאו בטעות – כלומר, דפים שיש בהם טקסט נוסף הכולל קישורים מתחת ל־#REDIRECT הראשון.</p>

<p>כל שורה מכילה קישור להפניות הראשונה והשנייה, וכן את שורת הטקסט הראשונה של ההפניה השנייה, שלרוב נמצא בה היעד האמיתי של ההפניה, אליו אמורה ההפניה הראשונה להצביע.</p>",

# Broken redirects page
"brokenredirects"            => "הפניות לא תקינות",
"brokenredirectstext"        => "ההפניות שלהלן מפנות לדפים שאינם קיימים:",

# Miscellaneous special pages
"nbytes"      => "{{plural:$1|בית אחד|$1 בתים}}",
"ncategories" => "{{plural:$1|קטגוריה אחת|$1 קטגוריות}}",
"nlinks"      => "{{plural:$1|קישור אחד|$1 קישורים}}",
"nmembers"    => "{{plural:$1|דף אחד|$1 דפים}}",
"nrevisions"  => "{{plural:$1|גרסה אחת|$1 גרסאות}}",
"nviews"      => "{{plural:$1|צפיה אחת|$1 צפיות}}",

"lonelypages"             => "דפים יתומים",
"uncategorizedpages"      => "דפים חסרי קטגוריה",
"uncategorizedcategories" => "קטגוריות חסרות קטגוריה",
"uncategorizedimages"     => "תמונות חסרות קטגוריה",
"unusedcategories"        => "קטגוריות שאינן בשימוש",
"unusedimages"            => "תמונות שאינן בשימוש",
"popularpages"            => "דפים פופולריים",
"wantedcategories"        => "קטגוריות מבוקשות",
"wantedpages"             => "דפים מבוקשים",
"mostlinked"              => "הדפים המקושרים ביותר",
"mostlinkedcategories"    => "הקטגוריות המקושרות ביותר",
"mostcategories"          => "הדפים מרובי־הקטגוריות ביותר",
"mostimages"              => "התמונות המקושרות ביותר",
"mostrevisions"           => "הדפים בעלי מספר העריכות הגבוה ביותר",
"allpages"                => "כל הדפים",
"prefixindex"             => "רשימת הדפים המתחילים ב…",
"randompage"              => "דף אקראי",
"shortpages"              => "דפים קצרים",
"longpages"               => "דפים ארוכים",
"deadendpages"            => "דפים ללא קישורים",
"listusers"               => "רשימת משתמשים",
"specialpages"            => "דפים מיוחדים",
"spheading"               => "דפים מיוחדים",
"restrictedpheading"      => "דפים מיוחדים מוגבלים",
"recentchangeslinked"     => "שינויים בדפים המקושרים",
"rclsub"                  => '(לדפים המקושרים מהדף "$1")',
"newpages"                => "דפים חדשים",
"newpages-username"       => "שם משתמש:",
"ancientpages"            => "דפים מוזנחים",
"intl"                    => "קישורים בינלשוניים",
"move"                    => "העבר",
"movethispage"            => "העבר דף זה",
"unusedimagestext"        => "רשימת הקבצים שאינם בשימוש באתר. יש למצוא מקום עבור הקבצים או לסמן אותם למחיקה.",
"unusedcategoriestext"    => "למרות שהקטגוריות הבאות קיימות, אין שום דף בו נעשה בהן שימוש.",

"booksources"        => "משאבי ספרות חיצוניים",
"categoriespagetext" => "אלו הקטגוריות הקיימות באתר.",
"data"               => "נתונים",
"userrights"         => "ניהול הרשאות משתמש",
"groups"             => "קבוצות משתמשים",

"booksourcetext" => "להלן רשימת קישורים לאתרים אחרים המוכרים ספרים חדשים ויד־שנייה, ושבהם עשוי להיות מידע נוסף לגבי ספרים שאתם מחפשים. לאתר אין קשר לעקסים אלו, ואין לראות ברשימה זו המלצה, פרסום או עידוד לעשות שימוש באתרים אלו ספציפית.",
"isbn"           => "ISBN",
"alphaindexline" => "$1 עד $2",
"version"        => "גרסת התוכנה",
"log"            => "יומנים",
"alllogstext"    => "תצוגה משולבת של יומני העלאת קבצים, מחיקות והגנות על דפים, חסימת משתמשים ומינוי מפעילי מערכת.

ניתן לצמצם את התצוגה על־ידי בחירת סוג היומן, שם המשתמש או הדפים המושפעים.",
"logempty"       => "אין פריטים תואמים ביומן.",

# Special:Allpages
"nextpage"          => "הדף הבא ($1)",
"allpagesfrom"      => "הצג דפים החל מ:",
"allarticles"       => "כל הדפים",
"allinnamespace"    => "כל הדפים (מרחב שם $1)",
"allnotinnamespace" => "כל הדפים (שלא במרחב השם $1)",
"allpagesprev"      => "הקודם",
"allpagesnext"      => "הבא",
"allpagessubmit"    => "עבור",
"allpagesprefix"    => "הדפים ששמם מתחיל ב…:",
"allpagesbadtitle"  => "כותרת הדף המבוקש הייתה לא־חוקית, ריקה, קישור ויקי פנימי, או פנים שפה שגוי. ייתכן שהיא כוללת תו אחד או יותר האסורים לשימוש בכותרות.",

# Special:Listusers
"listusersfrom" => "הצג משתמשים החל מ:",

# Email this user
"mailnologin"     => "אין כתובת לשליחה",
"mailnologintext" => "עליכם [[{{ns:special}}:Userlogin|להיכנס לחשבון]] ולהגדיר לעצמכם כתובת דואר אלקטרוני תקינה ב[[{{ns:special}}:Preferences|העדפות המשתמש]] שלכם כדי לשלוח דואר למשתמש אחר.",
"emailuser"       => "שלחו דואר אלקטרוני למשתמש זה",
"emailpage"       => "שלחו דואר למשתמש",
"emailpagetext"   => "אם המשתמש הזין כתובת דואר אלקטרוני חוקית בהעדפותיו האישיות, הטופס שלהלן ישלח אליו הודעת דואר אחת. כתובת הדואר האלקטרוני שהזנתם בהעדפותיכם האישיות תופיע ככתובת ממנה נשלחה ההודעה כדי שהמשתמש יוכל לענות.",
"usermailererror" => "אוביקט הדואר החזיר שגיאה:",
"defemailsubject" => 'דוא"ל {{SITENAME}}',
"noemailtitle"    => "אין כתובת דואר אלקטרוני",
"noemailtext"     => "משתמש זה לא הזין כתובת דואר אלקטרוני חוקית או בחר שלא לקבל דואר אלקטרוני ממשתמשים אחרים.",
"emailfrom"       => "מאת",
"emailto"         => "אל",
"emailsubject"    => "נושא",
"emailmessage"    => "הודעה",
"emailsend"       => "שלח",
"emailsent"       => "הדואר נשלח",
"emailsenttext"   => "הודעת הדואר האלקטרוני שלך נשלחה.",

# Watchlist
"watchlist"            => "רשימת המעקב שלי",
"watchlistfor"         => "(עבור '''$1''')",
"nowatchlist"          => "אין לכם דפים ברשימת המעקב.",
"watchlistanontext"    => "עליכם $1 כדי לצפות או לערוך פריטים ברשימת המעקב שלכם.",
"watchlistcount"       => "'''יש לכם $1 פריטים ברשימת המעקב, כולל דפי שיחה.'''",
"clearwatchlist"       => "ניקוי רשימת המעקב",
"watchlistcleartext"   => "האם אתם בטוחים שברצונכם להסירם?",
"watchlistclearbutton" => "נקה את רשימת המעקב",
"watchlistcleardone"   => "רשימת המעקב שלכם רוקנה. $1 פריטים הוסרו ממנה.",
"watchnologin"         => "לא נכנסתם לאתר",
"watchnologintext"     => "עליכם [[{{ns:special}}:Userlogin|להיכנס לחשבון]] כדי לערוך את רשימת המעקב.",
"addedwatch"           => "הדף נוסף לרשימת המעקב",
"addedwatchtext"       => 'הדף "[[:$1]]" נוסף ל[[{{ns:special}}:Watchlist|רשימת המעקב שלכם]]. שינויים שייערכו בעתיד, בדף זה ובדף השיחה שלו, יוצגו ברשימת המעקב.

בנוסף, הדף יופיע בכתב מודגש ב[[{{ns:special}}:Recentchanges|רשימת השינויים האחרונים]], כדי להקל עליכם את המעקב אחריו.

אם תרצו להסיר את הדף מרשימת המעקב שלכם, לחצו על הלשונית "הפסק לעקוב" שלמעלה.',
"removedwatch"         => "הדף הוסר מרשימת המעקב",
"removedwatchtext"     => 'הדף "[[:$1]]" הוסר מ[[{{ns:special}}:Watchlist|רשימת המעקב שלכם]].',
"watch"                => "עקוב",
"watchthispage"        => "עקבו אחר דף זה",
"unwatch"              => "הפסק לעקוב",
"unwatchthispage"      => "הפסיקו לעקוב אחר דף זה",
"notanarticle"         => "זהו אינו דף תוכן",
"watchnochange"        => "אף אחד מהדפים ברשימת המעקב לא עודכן בפרק הזמן המצוין למעלה.",
"watchdetails"         => "* יש לכם $1 דפים (לא כולל דפי שיחה) ברשימת המעקב.
* [[{{ns:special}}:Watchlist/edit|הציגו וערכו את רשימת המעקב במלואה]].
* [[{{ns:special}}:Watchlist/clear|הסירו את כל הדפים]].",
"wlheader-enotif"      => '* הודעות דוא"ל מאופשרות.',
"wlheader-showupdated" => "* דפים שהשתנו מאז ביקורכם האחרון בהם מוצגים ב'''הדגשה'''.",
"watchmethod-recent"   => "בודק את הדפים שברשימת המעקב לשינויים אחרונים.",
"watchmethod-list"     => "בודק את העריכות האחרונות בדפים שברשימת המעקב",
"removechecked"        => "הסר דפים מסומנים מרשימת המעקב",
"watchlistcontains"    => "רשימת המעקב שלכם כוללת $1 דפים.",
"watcheditlist"        => 'להלן רשימה מסודרת של הדפים ברשימת המעקב שלכם. בחרו את הדפים שאתם רוצים להסיר מהרשימה ולחצו על "הסר דפים מסומנים" בתחתית הדף (הסרת דף גם מסירה את דף השיחה שלו, וכיוצא בזה).',
"removingchecked"      => "מסיר את הדפים המסומנים מרשימת המעקב…",
"couldntremove"        => "לא ניתן להסיר את $1…",
"iteminvalidname"      => "בעיה עם $1, שם שגוי…",
"wlnote"               => "להלן $1 השינויים האחרונים ב־'''$2''' השעות האחרונות.",
"wlshowlast"           => "(הצג $1 שעות אחרונות | $2 ימים אחרונים | $3)",
"wlsaved"              => "זוהי גרסה שמורה של רשימת המעקב שלכם.",
"wlhideshowown"        => "$1 עריכות שלי",
"wlhideshowbots"       => "$1 בוטים",
"wldone"               => "בוצע.",

"enotif_mailer"      => "הודעות {{SITENAME}}",
"enotif_reset"       => "סמן את כל הדפים כאילו נצפו",
"enotif_newpagetext" => "זהו דף חדש.",
"changed"            => "שונה",
"created"            => "נוצר",
"enotif_subject"     => 'הדף $PAGETITLE ב{{grammar:תחילית|{{SITENAME}}}} $CHANGEDORCREATED על־ידי $PAGEEDITOR',
"enotif_lastvisited" => "ראו $1 לכל השינויים מאז ביקורכם האחרון.",
"enotif_body"        => 'לכבוד $WATCHINGUSERNAME,

הדף $PAGETITLE ב{{grammar:תחילית|{{SITENAME}}}} $CHANGEDORCREATED ב־$PAGEEDITDATE על־ידי $PAGEEDITOR, ראו $PAGETITLE_URL לגרסה הנוכחית.

$NEWPAGE

תקציר העריכה: $PAGESUMMARY $PAGEMINOREDIT

באפשרותכם ליצור קשר עם העורך:
בדואר האלקטרוני: $PAGEEDITOR_EMAIL
באתר: $PAGEEDITOR_WIKI

לא תהיינה הודעות על שינויים נוספים עד שתבקרו את הדף. באפשרותכם גם לאפס את דגלי ההודעות בכל הדפים שברשימת המעקב שלכם.

             מערכת ההודעות של {{SITENAME}}

--
כדי לשנות את הגדרות רשימת המעקב שלכם, בקרו בדף
{{fullurl:{{ns:special}}:Watchlist/edit}}

משוב ועזרה נוספת:
{{fullurl:{{ns:project}}:עזרה}}',

# Delete/protect/revert
"deletepage"                  => "מחיקת דף",
"confirm"                     => "אישור",
"excontent"                   => 'תוכן היה: "$1"',
"excontentauthor"             => "תוכן היה: '$1' והתורם היחיד היה [[{{ns:special}}:Contributions/$2|$2]]",
"exbeforeblank"               => 'תוכן לפני שהורק היה: "$1"',
"exblank"                     => "הדף היה ריק",
"confirmdelete"               => "אישור מחיקת הדף",
"deletesub"                   => '(מוחק את "$1")',
"historywarning"              => "אזהרה – לדף שאתם עומדים למחוק יש היסטוריית שינויים:",
"confirmdeletetext"           => "אתם עומדים למחוק דף או תמונה, יחד עם כל ההיסטוריה שלהם, מבסיס הנתונים.

אנא אשרו שזה אכן מה שאתם מתכוונים לעשות, שאתם מבינים את התוצאות של מעשה כזה, ושהמעשה מבוצע בהתאם לנהלי האתר.",
"actioncomplete"              => "הפעולה בוצעה",
"deletedtext"                 => '"[[:$1]]" נמחק. ראו $2 לרשימת המחיקות האחרונות.',
"deletedarticle"              => 'מחק את "[[$1]]"',
"dellogpage"                  => "יומן מחיקות",
"dellogpagetext"              => "להלן רשימה של המחיקות האחרונות שבוצעו.",
"deletionlog"                 => "יומן מחיקות",
"reverted"                    => "שוחזר לגרסה קודמת",
"deletecomment"               => "סיבת המחיקה",
"imagereverted"               => "השיחזור לגרסה הקודמת הושלם בהצלחה.",
"rollback"                    => "שיחזור עריכות",
"rollback_short"              => "שיחזור",
"rollbacklink"                => "שיחזור",
"rollbackfailed"              => "השיחזור נכשל",
"cantrollback"                => "לא ניתן לשחזר את העריכה – התורם האחרון הוא היחיד שכתב דף זה; עם זאת, ניתן למחוק את הדף.",
"alreadyrolled"               => "לא ניתן לשחזר את עריכת הדף [[$1]] על־ידי [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|שיחה]]); מישהו אחר כבר ערך או שיחזר דף זה.

העריכה האחרונה היתה של [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|שיחה]]).",
"editcomment"                 => 'תקציר העריכה היה: "\'\'\'$1\'\'\'".', # only shown if there is an edit comment
"revertpage"                  => "שוחזר מעריכה של [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|שיחה]]) לעריכה האחרונה של [[{{ns:user}}:$1|$1]]",
"sessionfailure"              => "נראה שיש בעיה בחיבורכם לאתר. פעולתכם בוטלה כאמצעי זהירות כנגד התחזות לתקשורת ממחשבכם. אנא חיזרו לדף הקודם ונסו שנית.",
"protectlogpage"              => "יומן הגנות",
"protectlogtext"              => "להלן רשימה של הגנות וביטולי הגנות על דפים.",
"protectedarticle"            => "הגן על [[$1]]",
"unprotectedarticle"          => "ביטל את ההגנה על [[$1]]",
"protectsub"                  => '(מגן על "$1")',
"confirmprotecttext"          => "האם אתם בטוחים שברצונכם להגן על דף זה?",
"confirmprotect"              => "מאשר את ההגנה",
"protectmoveonly"             => "הגן מפני העברת הדף בלבד",
"protectcomment"              => "הסיבה להגנה",
"unprotectsub"                => '(מבטל את ההגנה על "$1")',
"confirmunprotecttext"        => "האם אתם בטוחים שברצונכם לבטל את ההגנה על דף זה?",
"confirmunprotect"            => "מאשר את ביטול ההגנה",
"unprotectcomment"            => "הסיבה להסרת ההגנה",
"protect-unchain"             => "אפשר שינוי הרשאות העברה",
"protect-text"                => "באפשרותכם לראות ולשנות כאן את רמת ההגנה של הדף [[:$1]]. אנא ודאו שאתם פועלים בהתאם בהתאם לנהלי האתר.",
"protect-viewtext"            => "לחשבון שלך אין הרשאה לשנות את רמת ההגנה של הדף. להלן ההגדרות הנוכחיות עבור הדף [[:$1]]:",
"protect-default"             => "(ברירת מחדל)",
"protect-level-autoconfirmed" => "משתמשים רשומים בלבד",
"protect-level-sysop"         => "מפעילי מערכת בלבד",

# Restrictions (nouns)
"restriction-edit" => "עריכה",
"restriction-move" => "העברה",

# Undelete
"undelete"                 => "צפו בדפים מחוקים",
"undeletepage"             => "צפו ושחזרו דפים מחוקים",
"viewdeletedpage"          => "צפו בדפים מחוקים",
"undeletepagetext"         => "הדפים שלהלן נמחקו, אך הם עדיין בארכיון וניתן לשחזר אותם. הארכיון מנוקה מעת לעת.",
"undeleteextrahelp"        => 'לשיחזור הדף כולו, אל תסמנו אף תיבת סימון ולחצו על "שיחזור". לשיחזור של גרסאות מסוימות בלבד, סמנו את תיבות הסימון של הגרסאות הללו, ולחצו על "שיחזור". לחיצה על "איפוס" תנקה את התקציר, ואת כל תיבות הסימון.',
"undeletearticle"          => "שחזרו דף מחוק",
"undeleterevisions"        => "$1 גרסאות נשמרו בארכיון",
"undeletehistory"          => "אם תשחזרו את הדף, כל הגרסאות תשוחזרנה להיסטוריית השינויים שלו.

אם כבר יש דף חדש באותו השם, הגרסאות והשינויים יופיעו רק בדף ההיסטוריה שלו, והגרסה הנוכחית של הדף לא תוחלף אוטומטית.",
"undeletehistorynoadmin"   => "דף זה נמחק. הסיבה למחיקה מוצגת בתקציר מטה, ביחד עם פרטים על המשתמשים שערכו את הדף לפני מחיקתו. הטקסט של גרסאות אלו זמין רק למפעילי מערכת.",
"undeleterevision"         => "נמחקו גרסאות החל מ־$1.",
"undeletebtn"              => "שיחזור",
"undeletereset"            => "איפוס",
"undeletecomment"          => "תקציר:",
"undeletedarticle"         => "שיחזר את [[:$1]]",
"undeletedrevisions"       => "שיחזר $1 גרסאות",
"undeletedrevisions-files" => "שיחזר $1 גרסאות ו־$2 קבצים",
"undeletedfiles"           => "שיחזר $1 קבצים",
"cannotundelete"           => "השיחזור נכשל; ייתכן שמישהו אחר כבר שיחזר את הדף.",
"undeletedpage"            => "'''הדף $1 שוחזר בהצלחה.'''

ראו את [[{{ns:special}}:Log/delete|יומן המחיקות]] לרשימה של מחיקות ושיחזורים אחרונים.",

# Namespace form on various pages
"namespace" => "מרחב שם:",
"invert"    => "ללא מרחב זה",

# Contributions
"contributions"          => "תרומות המשתמש",
"mycontris"              => "התרומות שלי",
"contribsub"             => "עבור $1",
"nocontribs"             => "לא נמצאו שינויים המתאימים לקריטריונים אלו.",
"ucnote"                 => "להלן '''$1''' השינויים האחרונים שביצע משתמש זה ב־'''$2''' הימים האחרונים:",
"uclinks"                => "צפה ב־$1 השינויים האחרונים; צפה ב־$2 הימים האחרונים",
"uctop"                  => "(אחרון)" ,
"newbies"                => "משתמשים חדשים",

"sp-newimages-showfrom" => "הצג תמונות חדשות החל מ־$1",

"sp-contributions-newest"      => "חדשות ביותר",
"sp-contributions-oldest"      => "ישנות ביותר",
"sp-contributions-newer"       => "$1 החדשות",
"sp-contributions-older"       => "$1 הישנות",
"sp-contributions-newbies-sub" => "עבור משתמשים חדשים",

# What links here
"whatlinkshere" => "דפים המקושרים לכאן",
"notargettitle" => "אין דף מטרה",
"notargettext"  => "לא ציינתם דף מטרה או משתמש לגביו תבוצע פעולה זו.",
"linklistsub"   => "(רשימת קישורים)",
"linkshere"     => "הדפים שלהלן מקושרים לדף '''[[:$1]]''':",
"nolinkshere"   => "אין דפים המקושרים לדף '''[[:$1]]'''.",
"isredirect"    => "דף הפניה",
"istemplate"    => "הכללה",

# Block/unblock IP
"blockip"                     => "חסימת משתמש",
"blockiptext"                 => "השתמשו בטופס שלהלן כדי לחסום את הרשאות הכתיבה ממשתמש או כתובת IP ספציפיים.

חסימות כאלה צריכות להתבצע אך ורק כדי למנוע ונדליזם, ובהתאם לנהלי האתר.

אנא פרטו את הסיבה הספציפית לחסימה להלן (לדוגמה, ציון דפים ספציפיים אותם השחית המשתמש).",
"ipaddress"                   => "כתובת IP",
"ipadressorusername"          => "כתובת IP או שם משתמש",
"ipbexpiry"                   => "פקיעה",
"ipbreason"                   => "סיבה",
"ipbanononly"                 => "חסום משתמשים אנונימיים בלבד",
"ipbcreateaccount"            => "חסום יצירת חשבונות",
"ipbsubmit"                   => "חסום משתמש זה",
"ipbother"                    => "זמן אחר",
"ipboptions"                  => "שעתיים:2 hours,יום:1 day,שלושה ימים:3 days,שבוע:1 week,שבועיים:2 weeks,חודש:1 month,שלושה חודשים:3 months,חצי שנה:6 months,שנה:1 year,לצמיתות:infinite",
"ipbotheroption"              => "אחר",
"badipaddress"                => "משתמש או כתובת IP שגויים.",
"blockipsuccesssub"           => "החסימה הושלמה בהצלחה",
"blockipsuccesstext"          => "המשתמש [[{{ns:special}}:Contributions/$1|$1]] נחסם.

ראו את [[{{ns:special}}:Ipblocklist|רשימת המשתמשים החסומים]] כדי לצפות בחסימות.",
"unblockip"                   => "שחרר משתמש",
"unblockiptext"               => "השתמשו בטופס שלהלן כדי להחזיר את הרשאות הכתיבה למשתמש או כתובת IP חסומים.",
"ipusubmit"                   => "שחרר משתמש זה",
"unblocked"                   => 'המשתמש "[[משתמש:$1|$1]]" שוחרר מחסימתו.',
"ipblocklist"                 => "רשימת משתמשים חסומים",
"blocklistline"               => '$1 $2 חסם את $3 ($4)',
"infiniteblock"               => "לצמיתות",
"expiringblock"               => "פוקע $1",
"anononlyblock"               => "משתמשים אנונימיים בלבד",
"createaccountblock"          => "יצירת חשבונות נחסמה",
"ipblocklistempty"            => "רשימת המשתמשים החסומים ריקה.",
"blocklink"                   => "חסום",
"unblocklink"                 => "שחרר חסימה",
"contribslink"                => "תרומות",
"autoblocker"                 => 'נחסמת באופן אוטומטי משום שאתה חולק את כתובת ה־IP שלך עם [[{{ns:user}}:$1|$1]]. הנימוק שניתן לחסימת [[{{ns:user}}:$1|$1]] הוא: "$2".',
"blocklogpage"                => "יומן חסימות",
"blocklogentry"               => "חסם את [[$1]] למשך $2",
"blocklogtext"                => 'זהו יומן פעולות החסימה והשחרור של משתמשים. כתובות IP הנחסמות באופן אוטומטי אינן מופיעות.

ראו גם את [[{{ns:special}}:Ipblocklist|רשימת המשתמשים החסומים]] הנוכחית.',
"unblocklogentry"             => "שיחרר את [[$1]]",
"range_block_disabled"        => "היכולת לחסום טווח כתובות איננה פעילה.",
"ipb_expiry_invalid"          => "זמן פקיעת חסימה בלתי חוקי",
"ipb_already_blocked"         => 'המשתמש "$1" כבר נחסם',
"ip_range_invalid"            => "טווח IP שגוי.",
"ipb_cant_unblock"            => "שגיאה: חסימה מספר $1 לא נמצאה. ייתכן שהיא כבר שוחררה.",
"proxyblocker"                => "חוסם פרוקסי",
"proxyblockreason"            => "כתובת ה־IP שלכם נחסמה משום שהיא כתובת פרוקסי פתוחה. אנא צרו קשר עם ספק האינטרנט שלכם והודיעו לו על בעיית האבטחה החמורה הזו.",
"proxyblocksuccess"           => "בוצע.",
"sorbs"                       => "SORBS DNSBL",
"sorbsreason"                 => "כתובת ה־IP שלכם רשומה ככתובת פרוקסי פתוחה ב[http://www.sorbs.net SORBS] DNSBL.",
"sorbs_create_account_reason" => "כתובת ה־IP שלכם רשומה ככתובת פרוקסי פתוחה ב[http://www.sorbs.net SORBS] DNSBL. אינכם יכולים ליצור חשבון.",

# Developer tools
"lockdb"              => "נעל בסיס־נתונים",
"unlockdb"            => "שחרר בסיס־נתונים מנעילה",
"lockdbtext"          => "נעילת בסיס הנתונים תמנע ממשתמשים את האפשרות לערוך דפים, לשנות את העדפותיהם, לערוך את רשימות המעקב שלהם, ופעולות אחרות הדורשות ביצוע שינויים בבסיס הנתונים.

אנא אשרו שזה מה שאתם מתכוונים לעשות, ושתשחררו את בסיס הנתונים מנעילה כאשר פעולת התחזוקה תסתיים.",
"unlockdbtext"        => "שחרור בסיס הנתונים מנעילה יחזיר למשתמשים את היכולת לערוך דפים, לשנות את העדפותיהם, לערוך את רשימות המעקב שלהם, ולבצע פעולות אחרות הדורשות ביצוע שינויים בבסיס הנתונים
אנא אשרו שזה מה שבכוונתכם לעשות.",
"lockconfirm"         => "כן, אני באמת רוצה לנעול את בסיס הנתונים.",
"unlockconfirm"       => "כן, אני באמת רוצה לשחרר את בסיס הנתונים מנעילה.",
"lockbtn"             => "נעל את בסיס הנתונים",
"unlockbtn"           => "שחרר את בסיס הנתונים מנעילה",
"locknoconfirm"       => "לא סימנתם את תיבת האישור.",
"lockdbsuccesssub"    => "נעילת בסיס הנתונים הושלמה בהצלחה",
"unlockdbsuccesssub"  => "שוחררה הנעילה מבסיס הנתונים",
"lockdbsuccesstext"   => "בסיס הנתונים ננעל.

זכרו [[{{ns:special}}:Unlockdb|לשחרר את הנעילה]] לאחר שפעולת התחזוקה הסתיימה.",
"unlockdbsuccesstext" => "שוחררה הנעילה של בסיס הנתונים",
"lockfilenotwritable" => "קובץ נעילת מסד הנתונים אינו ניתן לכתיבה. כדי שאפשר יהיה לנעול את מסד הנתונים או לבטל את נעילתו, שרת האינטרנט צריך לקבל הרשאות לכתוב אליו.",
"databasenotlocked"   => "מסד הנתונים אינו נעול.",

# Make sysop
"makesysoptitle"     => "הפוך משתמש למפעיל מערכת",
"makesysoptext"      => "דף זה משמש ביורוקרטים להפיכת משתמש רגיל למפעיל מערכת. אנא הקישו את שם המשתמש בתיבת הטקסט ולחצו על הכפתור על מנת להפוך את המשתמש למפעיל מערכת.",
"makesysopname"      => "שם המשתמש:",
"makesysopsubmit"    => "הפוך משתמש זה למפעיל מערכת",
"makesysopok"        => '\'\'\'המשתמש "$1" הוא עכשיו מפעיל מערכת.\'\'\'',
"makesysopfail"      => '\'\'\'לא ניתן היה למנות את המשתמש "$1" למפעיל מערכת.\'\'\' (האם הקלדתם נכונה את שם המשתמש?)',
"setbureaucratflag"  => "הפוך משתמש זה לביורוקרט.",
"rightslog"          => "יומן תפקידים",
"rightslogtext"      => "זהו יומן השינויים בתפקידי המשתמשים.",
"rightslogentry"     => 'שינה את ההרשאות של "$1" מההרשאות $2 להרשאות $3',
"rights"             => "הרשאות:",
"set_user_rights"    => "קבע הרשאות משתמש",
"user_rights_set"    => '\'\'\'הרשאות המשתמש "$1" עודכנו.\'\'\'',
"set_rights_fail"    => '\'\'\'שינוי הרשאות המשתמש "$1" נכשל.\'\'\' (האם הקלדתם נכונה את שם המשתמש?)',
"makesysop"          => "הפוך משתמש למפעיל מערכת",
"already_sysop"      => "משתמש זה הוא כבר מפעיל מערכת",
"already_bureaucrat" => "משתמש זה הוא כבר ביורוקרט",
"rightsnone"         => "(ללא הרשאות)",

# Move page
"movepage"                => "העברת דף",
"movepagetext"            => "שימוש בטופס שלהלן ישנה את שמו של דף, ויעביר את כל ההיסטוריה שלו לשם חדש.

השם הישן יהפוך לדף הפניה אל הדף עם השם החדש.

קישורים לשם הישן לא ישתנו, ולכן אנא ודאו שאין [[{{ns:special}}:DoubleRedirects|הפניות כפולות]] או [[{{ns:special}}:BrokenRedirects|שבורות]].

אתם אחראים לוודא שכל הקישורים ממשיכים להצביע למקום שאליו הם אמורים להצביע.

שימו לב: הדף '''לא''' יועבר אם כבר יש דף תחת השם החדש, אלא אם הדף הזה ריק, או שהוא הפניה, ואין לו היסטוריה של שינויים. משמעות הדבר, שאפשר לשנות חזרה את שמו של דף לשם המקורי, אם נעשתה טעות, ולא יימחק דף קיים במערכת.

'''אזהרה:''' שינוי זה עשוי להיות שינוי דרסטי ובלתי צפוי לדף פופלארי; אנא ודאו שאתם מבינים את השלכות המעשה לפני שאתם ממשיכים.",
"movepagetalktext"        => "דף השיחה של דף זה יועבר אוטומטית, אלא אם:
* קיים דף שיחה שאינו ריק תחת השם החדש אליו מועבר הדף.
* הורדתם את הסימון בתיבה שלהלן.

במקרים אלו, תצטרכו להעביר או לשלב את הדפים באופן ידני, אם תרצו.",
"movearticle"             => "העבר דף",
"movenologin"             => "לא נכנסתם לאתר",
"movenologintext"         => "עליכם [[{{ns:special}}:Userlogin|להיכנס לחשבון]] כדי להעביר דפים.",
"newtitle"                => "לשם החדש",
"movepagebtn"             => "העבר דף",
"pagemovedsub"            => "ההעברה הושלמה בהצלחה",
"pagemovedtext"           => 'הדף "[[$1]]" הועבר לשם "[[$2]]".',
"articleexists"           => "קיים כבר דף עם אותו שם, או שהשם שבחרתם אינו חוקי.
אנא בחרו שם אחר.",
"talkexists"              => "הדף עצמו הועבר בהצלחה, אבל דף השיחה לא הועבר כיוון שקיים כבר דף שיחה במיקום החדש. אנא מזגו אותם ידנית.",
"movedto"                 => "הועבר ל",
"movetalk"                => "העבר גם את דף השיחה.",
"talkpagemoved"           => "דף השיחה המשוייך הועבר גם כן.",
"talkpagenotmoved"        => "דף השיחה המשוייך '''לא''' הועבר.",
"1movedto2"               => "[[$1]] הועבר לשם [[$2]]",
"1movedto2_redir"         => "[[$1]] הועבר לשם [[$2]] במקום הפניה",
"movelogpage"             => "יומן העברות",
"movelogpagetext"         => "להלן רשימה של העברות דפים.",
"movereason"              => "סיבה",
"revertmove"              => "החזר",
"delete_and_move"         => "מחק והעבר",
"delete_and_move_text"    => '== בקשת מחיקה ==
דף היעד "[[$1]]" כבר קיים. האם ברצונכם למחוק אותו כדי לאפשר את ההעברה?',
"delete_and_move_confirm" => "כן, מחק את הדף",
"delete_and_move_reason"  => "מחיקה על מנת לאפשר העברה",
"selfmove"                => "כותרות המקור והיעד זהות; לא ניתן להעביר דף לעצמו.",
"immobile_namespace"      => "כותרת היעד היא סוג מיוחד של דף; אי אפשר להעביר דפים לתוך מרחב שם זה.",

# Export
"export"          => "ייצוא דפים",
"exporttext"      => "באפשרותכם לייצא את התוכן ואת היסטוריית העריכה של דף אחד או של מספר דפים, בתבנית של קובץ XML, שניתן לייבא אותו למיזם ויקי אחר המשתמש בתוכנת מדיה־ויקי באמצעות הדף [[{{ns:special}}:Import]].

כדי לייצא דפים, הקישו את שמותיהם בתיבת הטקסט שלהלן, כל שם בשורה נפרדת, ובחרו האם לייצא גם את הגרסה הנוכחית וגם את היסטוריית השינויים של הדפים, או רק את הגרסה הנוכחית עם מידע על העריכה האחרונה.

בנוסף, ניתן להשתמש בקישור, כגון [[{{ns:special}}:Export/{{int:mainpage}}]] לדף {{int:mainpage}} ללא היסטוריית השינויים שלו.",
"exportcuronly"   => "כלול רק את הגרסה הנוכחית, ללא כל ההיסטוריה",
"exportnohistory" => "----
'''הערה:''' ייצוא ההיסטוריה המלאה של דפים דרך טופס זה הופסקה עקב בעיות ביצוע.",
"export-submit"   => "ייצוא",

# Namespace 8 related
"allmessages"               => "הודעות המערכת",
"allmessagesname"           => "שם",
"allmessagesdefault"        => "טקסט ברירת מחדל",
"allmessagescurrent"        => "טקסט נוכחי",
"allmessagestext"           => "זוהי רשימת כל הודעות המערכת שבמרחב השם {{ns:mediawiki}}, המשמשים את ממשק האתר.

מפעילי המערכת יכולים לערוך את ההודעות בלחיצה על שם ההודעה.",
"allmessagesnotsupportedUI" => "שפת הממשק הנוכחית שלכם, '''$1''', אינה נתמכת על־ידי הדף באתר זה.",
"allmessagesnotsupportedDB" => "לא ניתן להשתמש בדף זה כיוון ש־wgUseDatabseMessages מבוטל.",
"allmessagesfilter"         => "מסנן שמות ההודעות:",
"allmessagesmodified"       => "רק הודעות ששונו",

# Thumbnails
"thumbnail-more"  => "הגדל",
"missingimage"    => "'''תמונה חסרה'''

'''$1''",
"filemissing"     => "קובץ חסר",
"thumbnail_error" => "שגיאה ביצירת תמונה ממוזערת: $1",

# Special:Import
"import"                     => "ייבוא דפים",
"importinterwiki"            => "ייבוא בין־אתרי",
"import-interwiki-text"      => "אנא בחרו אתר ויקי ואת כותרת הדף לייבוא.
תאריכי ועורכי הגרסאות יישמרו בעת הייבוא.
כל פעולות הייבוא הבין־אתרי נשמרות ביומן הייבוא.",
"import-interwiki-history"   => "העתק את כל היסטוריית העריכות של דף זה",
"import-interwiki-submit"    => "ייבוא",
"import-interwiki-namespace" => "העבר את הדפים לתוך מרחב השם:",
"importtext"                 => "אנא ייצאו את הקובץ מאתר המקור תוך שימוש בעזר הייצוא, שמרו אותו לדיסק הקשיח שלכם והעלו אותו לכאן.",
"importstart"                => "מייבא דפים…",
"import-revision-count"      => "{{plural:$1|גרסה אחת|$1 גרסאות}}",
"importnopages"              => "אין דפים לייבוא.",
"importfailed"               => "הייבוא נכשל: $1",
"importunknownsource"        => "סוג ייבוא בלתי ידוע",
"importcantopen"             => "פתיחת קובץ הייבוא נכשלה",
"importbadinterwiki"         => "קישור אינטרוויקי שגוי",
"importnotext"               => "ריק או חסר טקסט",
"importsuccess"              => "הייבוא הושלם בהצלחה!",
"importhistoryconflict"      => "ישנה התנגשות עם ההיסטוריה הקיימת של הדף (ייתכן שהדף יובא בעבר)",
"importnosources"            => "אין מקורות לייבוא בין־אתרי, וייבוא ישיר של דף עם היסטוריה אינו מאופשר כעת.",
"importnofile"               => "לא הועלה קובץ ייבוא.",
"importuploaderror"          => "העלאת קובץ ייבוא נכשלה; ייתכן שהקובץ גדול מגודל ההעלאה המותר.",

# Import log
"importlogpage"                    => "יומן ייבוא",
"importlogpagetext"                => "ייבוא מנהלי של דפים כולל היסטוריית העריכות שלהם מאתרי ויקי אחרים.",
"import-logentry-upload"           => "ייבא את $1 על־ידי העלאת קובץ",
"import-logentry-upload-detail"    => "$1 גרסאות",
"import-logentry-interwiki"        => "ייבא את $1 בייבוא בין־אתרי",
"import-logentry-interwiki-detail" => "$1 גרסאות מהאתר $2",

# Keyboard access keys for power users
"accesskey-search"                  => "f",
"accesskey-minoredit"               => "i",
"accesskey-save"                    => "s",
"accesskey-preview"                 => "p",
"accesskey-diff"                    => "v",
"accesskey-compareselectedversions" => "v",
"accesskey-watch"                   => "w",

# Tooltip help for some actions, most are in Monobook.js
"tooltip-search"                  => "חיפוש ב{{grammar:תחילית|{{SITENAME}}}} [Alt+F]",
"tooltip-minoredit"               => "סימון עריכה זו כמשנית [Alt+I]",
"tooltip-save"                    => "שמירת את השינויים שביצעתם [Alt+S]",
"tooltip-preview"                 => "צפו בתצוגה מקדימה, אנא השתמשו באפשרות זו לפני השמירה! [Alt+P]",
"tooltip-diff"                    => "צפו בשינויים שערכתם בטקסט [Alt+V]",
"tooltip-compareselectedversions" => "צפו בהשוואה של שתי גרסאות של דף זה [Alt+V]",
"tooltip-watch"                   => "הוסיפו דף זה לרשימת המעקב שלכם [Alt+W]",

# Stylesheets
"Common.css"   => '/* הסגנונות הנכתבים כאן ישפיעו על כל הרקעים */',
"Monobook.css" => '/* הסגנונות הנכתבים כאן ישפיעו על הרקע Monobook בלבד */',

# Metadata
"nodublincore"      => "Dublin Core RDF metadata מנוטרל בשרת זה.",
"nocreativecommons" => "Creative Commons RDF metadata מנוטרל בשרת זה.",
"notacceptable"     => "האתר לא יכול לספק מידע בפורמט שתוכנת הלקוח יכולה לקרוא.",

# Attribution
"anonymous"      => "משתמש(ים) אנונימי(ים) של {{SITENAME}}",
"siteuser"       => "משתמש {{SITENAME}} $1",
"lastmodifiedby" => "דף זה שונה לאחרונה בתאריך $1 על־ידי $2.",
"and"            => "וגם",
"othercontribs"  => "מבוסס על העבודה של $1.",
"others"         => "אחרים",
"siteusers"      => "משתמש(י) {{SITENAME}} $1",
"creditspage"    => "קרדיטים בדף",
"nocredits"      => "אין קרדיטים זמינים בדף זה.",

# Spam protection
"spamprotectiontitle"    => "מנגנון מסנן הספאם",
"spamprotectiontext"     => "הדף אותו רצית לשמור נחסם על־ידי מסנן הספאם. הסיבה לכך היא לרוב קישור לאתר חיצוני.",
"spamprotectionmatch"    => "הטקסט הבא הוא שגרם להפעלת סינון הספאם: $1",
"subcategorycount"       => "{{plural:$1|ישנה קטגוריית משנה אחת|ישנן $1 קטגוריות משנה}} בקטגוריה זו.",
"categoryarticlecount"   => "{{plural:$1|ישנו דף אחד|ישנם $1 דפים}} בקטגוריה זו.",
"listingcontinuesabbrev" => " (המשך)",
"spambot_username"       => "MediaWiki spam cleanup",
"spam_reverting"         => "שיחזור לגרסה אחרונה שלא כוללת קישורים ל־$1",
"spam_blanking"          => "כל הגרסאות כוללות קישורים ל־$1, מרוקן את הדף",

# Info page
"infosubtitle"   => "מידע על הדף",
"numedits"       => "מספר עריכות (דף תוכן): $1",
"numtalkedits"   => "מספר עריכות (דף שיחה): $1",
"numwatchers"    => "מספר צופים בדף: $1",
"numauthors"     => "מספר כותבים נפרדים (דף תוכן): $1",
"numtalkauthors" => "מספר כותבים נפרדים (דף שיחה): $1",

# Math options
"mw_math_png"    => "תמיד הצג כ־PNG",
"mw_math_simple" => "HTML אם פשוט מאוד, אחרת PNG",
"mw_math_html"   => "HTML אם אפשר, אחרת PNG",
"mw_math_source" => "השאר כקוד TeX (לדפדפני טקסט)",
"mw_math_modern" => "מומלץ לדפדפנים עדכניים",
"mw_math_mathml" => "MathML אם אפשר (ניסיוני)",

# Patrolling
"markaspatrolleddiff"        => "סמן שינוי כבדוק",
"markaspatrolledtext"        => "סמן דף זה כבדוק",
"markedaspatrolled"          => "השינוי נבדק",
"markedaspatrolledtext"      => "השינוי שנבחר נבדק.",
"rcpatroldisabled"           => "בדיקת השינויים האחרונים מבוטלת",
"rcpatroldisabledtext"       => "תכונת סימון שינוי כבדוק בשינויים האחרונים היא כרגע מנוטרלת.",
"markedaspatrollederror"     => "לא יכול לסמן כבדוק",
"markedaspatrollederrortext" => "עליכם לציין גרסה שתציינו כבדוקה.",

# Monobook.js: tooltips and access keys for monobook
"Monobook.js" => '/* ערכו קובץ זה כדי להריץ סקריפטים ב־JavaScript ברקע Monobook באתר כולו */
/* חלונות קופצים ומקשים חמים */
var ta = new Object();
ta["pt-userpage"]           = [".", "דף המשתמש שלי"];
ta["pt-anonuserpage"]       = [".", "דף המשתמש של משתמש אנונימי זה"];
ta["pt-mytalk"]             = ["n", "דף השיחה שלי"];
ta["pt-anontalk"]           = ["n", "שיחה על תרומות המשתמש האנונימי"];
ta["pt-preferences"]        = ["",  "ההעדפות שלי"];
ta["pt-watchlist"]          = ["l", "רשימת הדפים שאתה עוקב אחרי השינויים בהם"];
ta["pt-mycontris"]          = ["y", "רשימת התרומות שלי"];
ta["pt-login"]              = ["o", "מומלץ להירשם, אך אין חובה לעשות כן"];
ta["pt-anonlogin"]          = ["o", "מומלץ להירשם, אך אין חובה לעשות כן"];
ta["pt-logout"]             = ["o", "יציאה מהחשבון"];
ta["ca-talk"]               = ["t", "שוחחו על דף זה"];
ta["ca-edit"]               = ["e", "באפשרותכם לערוך דף זה. אנא השתמשו בלחצן \\"הראה תצוגה מקדימה\\" לפני השמירה."];
ta["ca-addsection"]         = ["+", "הוספת הערה לשיחה זו"];
ta["ca-viewsource"]         = ["e", "זהו דף מוגן, אך באפשרותך לצפות במקורו"];
ta["ca-history"]            = ["h", "גרסאות קודמות של דף זה."];
ta["ca-protect"]            = ["=", "הגנו על דף זה"];
ta["ca-delete"]             = ["d", "מחקו דף זה"];
ta["ca-undelete"]           = ["d", "שחזרו עריכות שנעשו בדף זה לפני שנמחק"];
ta["ca-move"]               = ["m", "העבירו דף זה"];
ta["ca-watch"]              = ["w", "הוסיפו דף זה לרשימת המעקב שלכם"];
ta["ca-unwatch"]            = ["w", "הסירו דף זה מרשימת המעקב שלכם"];
ta["search"]                = ["f", "חפשו באתר"];
ta["p-logo"]                = ["",  "עמוד ראשי"];
ta["n-mainpage"]            = ["z", "בקרו בעמוד הראשי"];
ta["n-portal"]              = ["",  "אודות המיזם, איך תוכלו לעזור, איפה למצוא דברים"];
ta["n-currentevents"]       = ["",  "מצאו מידע רקע על האירועים האחרונים"];
ta["n-recentchanges"]       = ["r", "רשימת השינויים האחרונים באתר"];
ta["n-randompage"]          = ["x", "צפייה בדף תוכן אקראי"];
ta["n-help"]                = ["",  "עזרה בשימוש באתר"];
ta["n-sitesupport"]         = ["",  "תרומה"];
ta["t-whatlinkshere"]       = ["j", "רשימת כל הדפים המקושרים לכאן"];
ta["t-recentchangeslinked"] = ["k", "השינויים האחרונים שבוצעו בדפים המקושרים לכאן"];
ta["feed-rss"]              = ["",  "הוסיפו עדכון אוטומטי על־ידי RSS"];
ta["feed-atom"]             = ["",  "הוסיפו עדכון אוטומטי על־ידי Atom"];
ta["t-contributions"]       = ["",  "צפו בתרומותיו של משתמש זה"];
ta["t-emailuser"]           = ["",  "שלחו דואר אלקטרוני למשתמש זה"];
ta["t-upload"]              = ["u", "העלו תמונות או קבצי מדיה"];
ta["t-specialpages"]        = ["q", "רשימת כל הדפים המיוחדים"];
ta["ca-nstab-main"]         = ["c", "צפו בדף התוכן"];
ta["ca-nstab-user"]         = ["c", "צפו בדף המשתמש"];
ta["ca-nstab-media"]        = ["c", "צפו בפריט המדיה"];
ta["ca-nstab-special"]      = ["",  "זהו דף מיוחד, אי אפשר לערוך אותו"];
ta["ca-nstab-project"]      = ["a", "צפו בדף המיזם"];
ta["ca-nstab-image"]        = ["c", "צפו בדף תיאור התמונה"];
ta["ca-nstab-mediawiki"]    = ["c", "צפו בהודעת המערכת"];
ta["ca-nstab-template"]     = ["c", "צפו בתבנית"];
ta["ca-nstab-help"]         = ["c", "צפו בדף העזרה"];
ta["ca-nstab-category"]     = ["c", "צפו בדף הקטגוריה"];',

# Image deletion
"deletedrevision" => "מחק גרסה ישנה $1.",

# Browsing diffs
"previousdiff" => "→ עבור להשוואת הגרסאות הקודמת",
"nextdiff"     => "עבור להשוואת הגרסאות הבאה ←",

"imagemaxsize" => "הגבל תמונות בדפי תיאור תמונה ל:",
"thumbsize"    => "הקטן לגודל של:",
"showbigimage" => "הורד גרסת רזולוציה גבוהה ($1x$2, גודל: $3 קילובייטים)",

"newimages"    => "גלריית תמונות חדשות",
"showhidebots" => "($1 בוטים)",
"noimages"     => "אין תמונות.",

# Short names for language variants used for language conversion links.
# To disable showing a particular link, set it to 'disable', e.g.
# "variantname-zh-sg" => "disable",
"variantname-zh-cn" => "cn",
"variantname-zh-tw" => "tw",
"variantname-zh-hk" => "hk",
"variantname-zh-sg" => "sg",
"variantname-zh"    => "zh",
# Variants for Serbian language
"variantname-sr-ec" => "sr-ec",
"variantname-sr-el" => "sr-el",
"variantname-sr-jc" => "sr-jc",
"variantname-sr-jl" => "sr-jl",
"variantname-sr"    => "sr",

# labels for User: and Title: on Special:Log pages
"specialloguserlabel"  => "משתמש:",
"speciallogtitlelabel" => "כותרת:",

"passwordtooshort" => "סיסמתכם קצרה מדי. עליה להיות מורכבת מ־$1 תווים לפחות.",

# Media Warning
"mediawarning" => "'''אזהרה:''' קובץ זה עלול להכיל קוד זדוני, שהרצתו עלולה לסכן את המערכת שלכם.<hr />",

"fileinfo" => "$1 קילובייטים, סוג MIME: <code>$2</code>",

# Metadata
"metadata"          => "מידע נוסף על התמונה",
"metadata-help"     => "קובץ זה מכיל מידע נוסף, שיש להניח שהגיע ממצלמה דיגיטלית או מסורק בו התמונה נוצרה או עברה דיגיטציה. אם הקובץ שונה ממצבו הראשוני, כמה מהנתונים להלן עלולים שלא לשקף באופן מלא את מצב התמונה החדש.",
"metadata-expand"   => "הצג פרטים מורחבים",
"metadata-collapse" => "הסתר פרטים מורחבים",
"metadata-fields"   => "שדות המידע הנוסף של EXIF האלה אינם פרטים מורחבים ויוצגו תמיד, לעומת השאר:
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength",

# Exif tags
"exif-imagewidth"                  => "רוחב",
"exif-imagelength"                 => "גובה",
"exif-bitspersample"               => "ביטים לרכיב",
"exif-compression"                 => "תבנית דחיסה",
"exif-photometricinterpretation"   => "הרכב פיקסלים",
"exif-orientation"                 => "כיווניות",
"exif-samplesperpixel"             => "מספר רכיבים",
"exif-planarconfiguration"         => "סידור מידע",
"exif-ycbcrsubsampling"            => "הפחתת יחס Y ל־C",
"exif-ycbcrpositioning"            => "מיקום Y ו־C",
"exif-xresolution"                 => "רזולוציה אופקית",
"exif-yresolution"                 => "רזולוציה אנכית",
"exif-resolutionunit"              => "יחידות מידה של רזולוציות X ו־Y",
"exif-stripoffsets"                => "מיקום מידע התמונה",
"exif-rowsperstrip"                => "מספר השורות לרצועה",
"exif-stripbytecounts"             => "בייטים לרצועה דחוסה",
"exif-jpeginterchangeformat"       => "יחס ל־JPEG SOI",
"exif-jpeginterchangeformatlength" => "בייטים של מידע JPEG",
"exif-transferfunction"            => "פונקציית העברה",
"exif-whitepoint"                  => "נקודה לבנה צבעונית",
"exif-primarychromaticities"       => "צבעוניות ה־Primarity",
"exif-ycbcrcoefficients"           => "מקדמי פעולת הטרנספורמציה של מרחב הצבע",
"exif-referenceblackwhite"         => "זוג ערכי התייחסות לשחור ולבן",
"exif-datetime"                    => "תאריך ושעת שינוי הקובץ",
"exif-imagedescription"            => "כותרת התמונה",
"exif-make"                        => "יצרן המצלמה",
"exif-model"                       => "דגם המצלמה",
"exif-software"                    => "תוכנה בשימוש",
"exif-artist"                      => "מחבר",
"exif-copyright"                   => "בעל זכויות היוצרים",
"exif-exifversion"                 => "גרסת Exif",
"exif-flashpixversion"             => "גרסת Flashpix נתמכת",
"exif-colorspace"                  => "מרחב הצבע",
"exif-componentsconfiguration"     => "משמעות כל רכיב",
"exif-compressedbitsperpixel"      => "שיטת דחיסת התמונה",
"exif-pixelydimension"             => "רוחב התמונה הנכון",
"exif-pixelxdimension"             => "גובה התמונה הנכון",
"exif-makernote"                   => "הערות היצרן",
"exif-usercomment"                 => "הערות המשתמש",
"exif-relatedsoundfile"            => "קובץ שמע מקושר",
"exif-datetimeoriginal"            => "תאריך ושעת יצירת הקובץ",
"exif-datetimedigitized"           => "תאריך ושעת הפיכת הקובץ לדיגיטלי",
"exif-subsectime"                  => "תת־השניות של שינוי הקובץ",
"exif-subsectimeoriginal"          => "תת־השניות של יצירת הקובץ",
"exif-subsectimedigitized"         => "תת־השניות של הפיכת הקובץ לדיגיטלי",
"exif-exposuretime"                => "זמן חשיפה",
"exif-exposuretime-format"         => "$1 שניות ($2)",
"exif-fnumber"                     => "מספר F",
"exif-fnumber-format"              => "f/$1",
"exif-exposureprogram"             => "תוכנת החשיפה",
"exif-spectralsensitivity"         => "רגישות הספקטרום",
"exif-isospeedratings"             => "דירוג מהירות ה־ISO",
"exif-oecf"                        => "מקדם המרה אופטו־אלקטרוני",
"exif-shutterspeedvalue"           => "מהירות צמצם",
"exif-aperturevalue"               => "פתח",
"exif-brightnessvalue"             => "בהירות",
"exif-exposurebiasvalue"           => "נטיית החשיפה",
"exif-maxaperturevalue"            => "גודל הפתח המקסימאלי",
"exif-subjectdistance"             => "נושא המרחק",
"exif-meteringmode"                => "שיטת מדידה",
"exif-lightsource"                 => "מקור אור",
"exif-flash"                       => "פלש",
"exif-focallength"                 => "אורך מוקדי העדשות",
"exif-focallength-format"          => '$1 מ"מ',
"exif-subjectarea"                 => "נושא האזור",
"exif-flashenergy"                 => "אנרגיית הפלש",
"exif-spatialfrequencyresponse"    => "תדירות התגובה המרחבית",
"exif-focalplanexresolution"       => "משטח הפוקוס ברזולוציה האופקית",
"exif-focalplaneyresolution"       => "משטח הפוקוס ברזולוציה האנכית",
"exif-focalplaneresolutionunit"    => "יחידת המידה של משטח הפוקוס ברזולוציה",
"exif-subjectlocation"             => "נושא המיקום",
"exif-exposureindex"               => "מדד החשיפה",
"exif-sensingmethod"               => "שיטת חישה",
"exif-filesource"                  => "מקור הקובץ",
"exif-scenetype"                   => "סוג הסצנה",
"exif-cfapattern"                  => "תבנית CFA",
"exif-customrendered"              => "עיבוד תמונה מותאם",
"exif-exposuremode"                => "מצב החשיפה",
"exif-whitebalance"                => "איזון צבע לבן",
"exif-digitalzoomratio"            => "יחס הזום הדיגיטלי",
"exif-focallengthin35mmfilm"       => 'אורך מוקדי העדשות בסרט צילום של 35 מ"מ',
"exif-scenecapturetype"            => "אופן צילום הסצנה",
"exif-gaincontrol"                 => "בקרת הסצנה",
"exif-contrast"                    => "ניגוד",
"exif-saturation"                  => "רוויה",
"exif-sharpness"                   => "חדות",
"exif-devicesettingdescription"    => "תיאור הגדרות ההתקן",
"exif-subjectdistancerange"        => "טווח נושא המרחק",
"exif-imageuniqueid"               => "מזהה תמונה ייחודי",
"exif-gpsversionid"                => "גרסת תגי GPS",
"exif-gpslatituderef"              => "קו־רוחב צפוני או דרומי",
"exif-gpslatitude"                 => "קו־רוחב",
"exif-gpslongituderef"             => "קו־אורך מזרחי או מערבי",
"exif-gpslongitude"                => "קו־אורך",
"exif-gpsaltituderef"              => "התייחסות גובה",
"exif-gpsaltitude"                 => "גובה",
"exif-gpstimestamp"                => "זמן GPS (שעון אטומי)",
"exif-gpssatellites"               => "לוויינים ששמשו למדידה",
"exif-gpsstatus"                   => "מעמד המקלט",
"exif-gpsmeasuremode"              => "מצב מדידה",
"exif-gpsdop"                      => "דיוק מדידה",
"exif-gpsspeedref"                 => "יחידת מהירות",
"exif-gpsspeed"                    => "יחידת מהירות של מקלט GPS",
"exif-gpstrackref"                 => "התייחסות מהירות התנועה",
"exif-gpstrack"                    => "מהירות התנועה",
"exif-gpsimgdirectionref"          => "התייחסות כיוון התמונה",
"exif-gpsimgdirection"             => "כיוון התמונה",
"exif-gpsmapdatum"                 => "מידע סקר מדידת הארץ שנעשה בו שימוש",
"exif-gpsdestlatituderef"          => "התייחסות קו־הרוחב של היעד",
"exif-gpsdestlatitude"             => "קו־הרוחב של היעד",
"exif-gpsdestlongituderef"         => "התייחסות קו־האורך של היעד",
"exif-gpsdestlongitude"            => "קו־האורך של היעד",
"exif-gpsdestbearingref"           => "התייחסות כיוון היעד",
"exif-gpsdestbearing"              => "כיוון היעד",
"exif-gpsdestdistanceref"          => "התייחסות מרחק ליעד",
"exif-gpsdestdistance"             => "מרחק ליעד",
"exif-gpsprocessingmethod"         => "שם שיטת העיבוד של ה־GPS",
"exif-gpsareainformation"          => "שם אזור ה־GPS",
"exif-gpsdatestamp"                => "תאריך ה־GPS",
"exif-gpsdifferential"             => "תיקון דיפרנציאלי של ה־GPS",

# Exif attributes

"exif-compression-1" => "לא דחוס",
"exif-compression-6" => "JPEG",

"exif-photometricinterpretation-2" => "RGB",
"exif-photometricinterpretation-6" => "YCbCr",

"exif-orientation-1" => "רגילה", # 0th row: top; 0th column: left
"exif-orientation-2" => "הפוך אופקית", # 0th row: top; 0th column: right
"exif-orientation-3" => "מסובב 180°", # 0th row: bottom; 0th column: right
"exif-orientation-4" => "הפוך אנכית", # 0th row: bottom; 0th column: left
"exif-orientation-5" => "מסובב 90° נגד כיוון השעון והפוך אנכית", # 0th row: left; 0th column: top
"exif-orientation-6" => "מסובב 90° עם כיוון השעון", # 0th row: right; 0th column: top
"exif-orientation-7" => "מסובב 90° עם כיוון השעון והפוך אנכית", # 0th row: right; 0th column: bottom
"exif-orientation-8" => "מסובב 90° נגד כיוון השעון", # 0th row: left; 0th column: bottom

"exif-planarconfiguration-1" => "פורמט חסון",
"exif-planarconfiguration-2" => "פורמט שטוח",

"exif-xyresolution-i" => "$1 dpi",
"exif-xyresolution-c" => "$1 dpc",

"exif-colorspace-1"      => "sRGB",
"exif-colorspace-ffff.h" => "FFFF.H",

"exif-componentsconfiguration-0" => "אינו קיים",
"exif-componentsconfiguration-1" => "Y",
"exif-componentsconfiguration-2" => "Cb",
"exif-componentsconfiguration-3" => "Cr",
"exif-componentsconfiguration-4" => "R",
"exif-componentsconfiguration-5" => "G",
"exif-componentsconfiguration-6" => "B",

"exif-exposureprogram-0" => "לא הוגדרה",
"exif-exposureprogram-1" => "ידנית",
"exif-exposureprogram-2" => "תוכנה רגילה",
"exif-exposureprogram-3" => "עדיפות פתח",
"exif-exposureprogram-4" => "עדיפות צמצם",
"exif-exposureprogram-5" => "תוכנה יוצרת (מטה לכיוון עומק השדה)",
"exif-exposureprogram-6" => "תוכנה פועלת (מטה לכיוון מהירות צמצם גבוהה)",
"exif-exposureprogram-7" => "מצב דיוקן (לתמונות צילום מקרוב כשהרקע לא בפוקוס)",
"exif-exposureprogram-8" => "מצב נוף (לתמונות נוף כשהרקע בפוקוס)",

"exif-subjectdistance-value" => "$1 מטרים",

"exif-meteringmode-0"   => "לא ידוע",
"exif-meteringmode-1"   => "ממוצע",
"exif-meteringmode-2"   => "מרכז משקל ממוצע",
"exif-meteringmode-3"   => "נקודה",
"exif-meteringmode-4"   => "רב־נקודה",
"exif-meteringmode-5"   => "תבנית",
"exif-meteringmode-6"   => "חלקי",
"exif-meteringmode-255" => "אחר",

"exif-lightsource-0"   => "לא ידוע",
"exif-lightsource-1"   => "אור יום",
"exif-lightsource-2"   => "פלואורסצנטי",
"exif-lightsource-3"   => "טונגסטן (אור מתלהט)",
"exif-lightsource-4"   => "פלש",
"exif-lightsource-9"   => "מזג אוויר טוב",
"exif-lightsource-10"  => "מזג אוויר מעונן",
"exif-lightsource-11"  => "צל",
"exif-lightsource-12"  => "אור יום פלואורסצנטי (D 5700 – 7100K)",
"exif-lightsource-13"  => "אור יום לבן פלואורסצנטי (N 4600 – 5400K)",
"exif-lightsource-14"  => "אור יום קריר לבן פלואורסצנטי (W 3900 – 4500K)",
"exif-lightsource-15"  => "פלואורסצנטי לבן (WW 3200 – 3700K)",
"exif-lightsource-17"  => "אור רגיל A",
"exif-lightsource-18"  => "אור רגיל B",
"exif-lightsource-19"  => "אור רגיל C",
"exif-lightsource-20"  => "D55",
"exif-lightsource-21"  => "D65",
"exif-lightsource-22"  => "D75",
"exif-lightsource-23"  => "D50",
"exif-lightsource-24"  => "טונגסטן אולפן ISO",
"exif-lightsource-255" => "מקור אור אחר",

"exif-focalplaneresolutionunit-2" => "אינצ'ים",

"exif-sensingmethod-1" => "לא מוגדרת",
"exif-sensingmethod-2" => "חיישן אזור בצבע עם שבב אחד",
"exif-sensingmethod-3" => "חיישן אזור בצבע עם שני שבבים",
"exif-sensingmethod-4" => "חיישן אזור בצבע עם שלושה שבבים",
"exif-sensingmethod-5" => "חיישן אזור עם צבע רציף",
"exif-sensingmethod-7" => "חיישן טריליניארי",
"exif-sensingmethod-8" => "חיישן עם צבע רציף ליניארי",

"exif-filesource-3" => "DSC",

"exif-scenetype-1" => "תמונה שצולמה ישירות",

"exif-customrendered-0" => "תהליך רגיל",
"exif-customrendered-1" => "תהליך מותאם",

"exif-exposuremode-0" => "חשיפה אוטומטית",
"exif-exposuremode-1" => "חשיפה ידנית",
"exif-exposuremode-2" => "מסגרת אוטומטית",

"exif-whitebalance-0" => "איזון צבע לבן אוטומטי",
"exif-whitebalance-1" => "איזון צבע לבן ידני",

"exif-scenecapturetype-0" => "רגיל",
"exif-scenecapturetype-1" => "נוף",
"exif-scenecapturetype-2" => "דיוקן",
"exif-scenecapturetype-3" => "סצנה לילית",

"exif-gaincontrol-0" => "ללא",
"exif-gaincontrol-1" => "תוספת נמוכה למעלה",
"exif-gaincontrol-2" => "תוספת גבוהה למעלה",
"exif-gaincontrol-3" => "תוספת נמוכה למטה",
"exif-gaincontrol-4" => "תוספת גבוהה למטה",

"exif-contrast-0" => "רגיל",
"exif-contrast-1" => "רך",
"exif-contrast-2" => "קשה",

"exif-saturation-0" => "רגילה",
"exif-saturation-1" => "רוויה נמוכה",
"exif-saturation-2" => "רוויה גבוהה",

"exif-sharpness-0" => "רגילה",
"exif-sharpness-1" => "רכה",
"exif-sharpness-2" => "קשה",

"exif-subjectdistancerange-0" => "לא ידוע",
"exif-subjectdistancerange-1" => "מאקרו",
"exif-subjectdistancerange-2" => "תצוגה קרובה",
"exif-subjectdistancerange-3" => "תצוגה רחוקה",

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
"exif-gpslatitude-n" => "קו־רוחב צפוני",
"exif-gpslatitude-s" => "קו־רוחב דרומי",

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
"exif-gpslongitude-e" => "קו־אורך מזרחי",
"exif-gpslongitude-w" => "קו־אורך מערבי",

"exif-gpsstatus-a" => "מדידה בתהליך",
"exif-gpsstatus-v" => "מדידה בו־זמנית",

"exif-gpsmeasuremode-2" => "מדידה בשני ממדים",
"exif-gpsmeasuremode-3" => "מדידה בשלושה ממדים",

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
"exif-gpsspeed-k" => "קילומטרים בשעה",
"exif-gpsspeed-m" => "מיילים בשעה",
"exif-gpsspeed-n" => "מיילים ימיים בשעה",

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
"exif-gpsdirection-t" => "כיוון אמיתי",
"exif-gpsdirection-m" => "כיוון מגנטי",

# External editor support
"edit-externally"      => "ערכו קובץ זה באמצעות יישום חיצוני",
"edit-externally-help" => "ראו את [http://meta.wikimedia.org/wiki/Help:External_editors הוראות ההתקנה] למידע נוסף.",

# "All" in various places, this might be different for inflected languages
"recentchangesall" => "הכול",
"imagelistall"     => "הכול",
"watchlistall1"    => "הכול",
"watchlistall2"    => "הכול",
"namespacesall"    => "הכול",

# E-mail address confirmation
"confirmemail"            => 'אמתו כתובת דוא"ל',
"confirmemail_noemail"    => 'אין לכם כתובת דוא"ל תקפה המוגדרת ב[[{{ns:special}}:Preferences|העדפות המשתמש]] שלכם.',
"confirmemail_text"       => 'אתר זה דורש שתאמתו את כתובת הדוא"ל שלכם לפני שתשתמשו בשירותי הדוא"ל. לחצו על הכפתור למטה כדי לשלוח דוא"ל עם קוד אישור לכתובת הדוא"ל שהזנתם. טענו את הקישור בדפדפן שלכם כדי לאשר שכתובת הדוא"ל תקפה.',
"confirmemail_send"       => "שלח קוד אישור",
"confirmemail_sent"       => 'הדוא"ל עם קוד האישור נשלח.',
"confirmemail_sendfailed" => 'שליחת הדוא"ל עם קוד האישור לא הצליחה. בדקו שאין תווים שגויים בכתובת.',
"confirmemail_invalid"    => "קוד האישור שגוי. ייתכן שפג תוקפו.",
"confirmemail_needlogin"  => 'עליכם לבצע $1 כדי לאמת את כתובת הדוא"ל שלכם.',
"confirmemail_success"    => 'כתובת הדוא"ל שלכם אושרה. כעת באפשרותכם להיכנס לחשבון שלכם וליהנות מהאתר.',
"confirmemail_loggedin"   => 'כתובת הדוא"ל שלכם אושרה כעת.',
"confirmemail_error"      => "שגיאה בשמירת קוד האישור.",
"confirmemail_subject"    => 'קוד אישור דוא"ל מ{{grammar:תחילית|{{SITENAME}}}}',
"confirmemail_body"       => 'מישהו, כנראה אתם (מכתובת ה־IP הזו: $1), רשם את החשבון "$2" עם כתובת הדוא"ל הזו ב{{grammar:תחילית|{{SITENAME}}}}.

כדי לוודא שחשבון זה באמת שייך לכם ולהפעיל את שירותי הדוא"ל באתר, אנא פתחו את הכתובת הבאה בדפדפן שלכם:

$3

אם *לא* אתם ביקשתם קוד אישור זה, אל תפתחו את הקישור. קוד האישור יפקע ב־$4.',

# Inputbox extension, may be useful in other contexts as well
"tryexact"       => "מצאו התאמה מדויקת",
"searchfulltext" => "חפשו בכל הדף",
"createarticle"  => "צרו דף",

# Scary transclusion
"scarytranscludedisabled" => "[הכללת תבניות בין אתרים מנוטרלת]",
"scarytranscludefailed"   => "[מצטערים, קבלת התבנית נכשלה בגלל $1]",
"scarytranscludetoolong"  => "[מצטערים, כתובת ה־URL ארוכה מדי]",

# Trackbacks
"trackbackbox"      => '<div id="mw_trackbacks">
טרקבקים לדף זה:<br />
$1
</div>',
"trackbackremove"   => " ([$1 מחיקה])",
"trackbacklink"     => "טרקבק",
"trackbackdeleteok" => "הטרקבק נמחק בהצלחה.",

# Delete conflict
"deletedwhileediting" => "אזהרה: דף זה נמחק לאחר שהתחלתם לערוך!",
"confirmrecreate"     => "המשתמש [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|שיחה]]) מחק את הדף לאחר שהתחלת לערוך אותו, מסיבה זו:
:'''$2'''
אנא אשרו שאתם אכן רוצים ליצור מחדש את הדף.",
"recreate"            => "צור מחדש",
"tooltip-recreate"    => "צור מחדש את הדף למרות שהוא נמחק",

"unit-pixel" => "px",

# HTML dump
"redirectingto" => "מפנה ל־[[$1]]…",

# action=purge
"confirm_purge"        => "לנקות את המטמון של דף זה?

$1",
"confirm_purge_button" => "אישור",

"youhavenewmessagesmulti" => "יש לך הודעות חדשות ב־$1",
"newtalkseperator"        => ",_",
"searchcontaining"        => "חפש דפים המכילים את הטקסט '''$1'''.",
"searchnamed"             => "חפש דפים בשם '''$1'''.",
"articletitles"           => "חפש דפים המתחילים עם '''$1'''",
"hideresults"             => "הסתר תוצאות",

# DISPLAYTITLE
"displaytitle" => "(קשרו לדף זה בשם [[$1]])",

"loginlanguagelabel" => "שפה: $1",

# Multipage image navigation
"imgmultipageprev" => "&rarr; לדף הקודם",
"imgmultipagenext" => "לדף הבא &larr;",
"imgmultigo"       => "עבור!",
"imgmultigotopre"  => "עבור לדף",

# Table pager
"ascending_abbrev"         => "עולה",
"descending_abbrev"        => "יורד",
"table_pager_next"         => "הדף הבא",
"table_pager_prev"         => "הדף הקודם",
"table_pager_first"        => "הדף הראשון",
"table_pager_last"         => "הדף האחרון",
"table_pager_limit"        => "הצג $1 פריטים בדף",
"table_pager_limit_submit" => "עבור",
"table_pager_empty"        => "ללא תוצאות",
);

?>
