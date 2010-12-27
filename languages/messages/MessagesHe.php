<?php
/** Hebrew (עברית)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Agbad
 * @author Drorsnir
 * @author Ijon
 * @author Rotem Dan (July 2003)
 * @author Rotem Liss (March 2006 on)
 * @author Rotemliss
 * @author YaronSh
 * @author ערן
 */

$rtl = true;
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
);

$linkTrail = '/^([a-zא-ת]+)(.*)$/sDu';
$fallback8bitEncoding = 'windows-1255';


$datePreferences = array(
	'default',
	'mdy',
	'dmy',
	'ymd',
	'hebrew',
	'ISO 8601',
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

	'hebrew time' => 'H:i',
	'hebrew date' => 'xhxjj xjx xhxjY',
	'hebrew both' => 'H:i, xhxjj xjx xhxjY',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$bookstoreList = array(
	'מיתוס'          => 'http://www.mitos.co.il/',
	'iBooks'         => 'http://www.ibooks.co.il/',
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'Amazon.com'     => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$magicWords = array(
	'redirect'              => array( '0', '#הפניה', '#REDIRECT' ),
	'notoc'                 => array( '0', '__ללא_תוכן_עניינים__', '__ללא_תוכן__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__ללא_גלריה__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__חייב_תוכן_עניינים__', '__חייב_תוכן__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__תוכן_עניינים__', '__תוכן__', '__TOC__' ),
	'noeditsection'         => array( '0', '__ללא_עריכה__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__ללא_כותרת__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'חודש נוכחי', 'חודש נוכחי 2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'חודש נוכחי 1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'שם חודש נוכחי', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'שם חודש נוכחי קניין', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'קיצור חודש נוכחי', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'יום נוכחי', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'יום נוכחי 2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'שם יום נוכחי', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'שנה נוכחית', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'שעה נוכחית', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'שעות נוכחיות', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'חודש מקומי', 'חודש מקומי 2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'חודש מקומי 1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'שם חודש מקומי', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'שם חודש מקומי קניין', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'קיצור חודש מקומי', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'יום מקומי', 'LOCALDAY' ),
	'localday2'             => array( '1', 'יום מקומי 2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'שם יום מקומי', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'שנה מקומית', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'שעה מקומית', 'LOCALTIME' ),
	'localhour'             => array( '1', 'שעות מקומיות', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'מספר דפים כולל', 'מספר דפים', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'מספר ערכים', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'מספר קבצים', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'מספר משתמשים', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'מספר משתמשים פעילים', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'מספר עריכות', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'מספר צפיות', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'שם הדף', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'שם הדף מקודד', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'מרחב השם', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'מרחב השם מקודד', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'מרחב השיחה', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'מרחב השיחה מקודד', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'מרחב הנושא', 'מרחב הערכים', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'מרחב הנושא מקודד', 'מרחב הערכים מקודד', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'שם הדף המלא', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'שם הדף המלא מקודד', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'שם דף המשנה', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'שם דף המשנה מקודד', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'שם דף הבסיס', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'שם דף הבסיס מקודד', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'שם דף השיחה', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'שם דף השיחה מקודד', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'שם דף הנושא', 'שם הערך', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'שם דף הנושא מקודד', 'שם הערך מקודד', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'הכללה:', 'MSG:' ),
	'subst'                 => array( '0', 'ס:', 'SUBST:' ),
	'safesubst'             => array( '0', 'ס בטוח:', 'SAFESUBST:' ),
	'msgnw'                 => array( '0', 'הכללת מקור', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'ממוזער', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'ממוזער=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'ימין', 'right' ),
	'img_left'              => array( '1', 'שמאל', 'left' ),
	'img_none'              => array( '1', 'ללא', 'none' ),
	'img_width'             => array( '1', '$1 פיקסלים', '$1px' ),
	'img_center'            => array( '1', 'מרכז', 'center', 'centre' ),
	'img_framed'            => array( '1', 'ממוסגר', 'מסגרת', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'לא ממוסגר', 'ללא מסגרת', 'frameless' ),
	'img_page'              => array( '1', 'דף=$1', 'דף $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'ימין למעלה', 'ימין למעלה=$1', 'ימין למעלה $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'גבולות', 'גבול', 'border' ),
	'img_baseline'          => array( '1', 'שורת הבסיס', 'baseline' ),
	'img_sub'               => array( '1', 'תחתי', 'sub' ),
	'img_super'             => array( '1', 'עילי', 'super', 'sup' ),
	'img_top'               => array( '1', 'למעלה', 'top' ),
	'img_text_top'          => array( '1', 'בראש הטקסט', 'text-top' ),
	'img_middle'            => array( '1', 'באמצע', 'middle' ),
	'img_bottom'            => array( '1', 'למטה', 'bottom' ),
	'img_text_bottom'       => array( '1', 'בתחתית הטקסט', 'text-bottom' ),
	'img_link'              => array( '1', 'קישור=$1', 'link=$1' ),
	'img_alt'               => array( '1', 'טקסט=$1', 'alt=$1' ),
	'int'                   => array( '0', 'הודעה:', 'INT:' ),
	'sitename'              => array( '1', 'שם האתר', 'SITENAME' ),
	'ns'                    => array( '0', 'מרחב שם:', 'NS:' ),
	'nse'                   => array( '0', 'מרחב שם מקודד:', 'NSE:' ),
	'localurl'              => array( '0', 'כתובת יחסית:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'כתובת יחסית מקודד:', 'LOCALURLE:' ),
	'articlepath'           => array( '0', 'נתיב הדפים', 'ARTICLEPATH' ),
	'server'                => array( '0', 'כתובת השרת', 'שרת', 'SERVER' ),
	'servername'            => array( '0', 'שם השרת', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'נתיב הקבצים', 'SCRIPTPATH' ),
	'stylepath'             => array( '0', 'נתיב הסגנון', 'STYLEPATH' ),
	'grammar'               => array( '0', 'דקדוק:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'מגדר:', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__ללא_המרת_כותרת__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__ללא_המרת_תוכן__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'שבוע נוכחי', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'מספר יום נוכחי', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'שבוע מקומי', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'מספר יום מקומי', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'מזהה גרסה', 'REVISIONID' ),
	'revisionday'           => array( '1', 'יום גרסה', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'יום גרסה 2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'חודש גרסה', 'REVISIONMONTH' ),
	'revisionmonth1'        => array( '1', 'חודש גרסה 1', 'REVISIONMONTH1' ),
	'revisionyear'          => array( '1', 'שנת גרסה', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'זמן גרסה', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'כותב גרסה', 'REVISIONUSER' ),
	'plural'                => array( '0', 'רבים:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'כתובת מלאה:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'כתובת מלאה מקודד:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'אות ראשונה קטנה:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'אות ראשונה גדולה:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'אותיות קטנות:', 'LC:' ),
	'uc'                    => array( '0', 'אותיות גדולות:', 'UC:' ),
	'raw'                   => array( '0', 'ללא עיבוד:', 'RAW:' ),
	'displaytitle'          => array( '1', 'כותרת תצוגה', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'ללא פסיק', 'R' ),
	'newsectionlink'        => array( '1', '__יצירת_הערה__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__ללא_יצירת_הערה__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'גרסה נוכחית', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'נתיב מקודד:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'עוגן מקודד:', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'זמן נוכחי', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'זמן מקומי', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'סימן כיווניות', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#שפה:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'שפת תוכן', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'דפים במרחב השם:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'מספר מפעילים', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'עיצוב מספר', 'FORMATNUM' ),
	'padleft'               => array( '0', 'ריפוד משמאל', 'PADLEFT' ),
	'padright'              => array( '0', 'ריפוד מימין', 'PADRIGHT' ),
	'special'               => array( '0', 'מיוחד', 'special' ),
	'defaultsort'           => array( '1', 'מיון רגיל:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'נתיב לקובץ:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'תגית', 'tag' ),
	'hiddencat'             => array( '1', '__קטגוריה_מוסתרת__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'דפים בקטגוריה', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'גודל דף', 'PAGESIZE' ),
	'index'                 => array( '1', '__לחיפוש__', '__INDEX__' ),
	'noindex'               => array( '1', '__לא_לחיפוש__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'מספר בקבוצה', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__הפניה_קבועה__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'רמת הגנה', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'עיצוב תאריך', 'formatdate', 'dateformat' ),
	'url_path'              => array( '0', 'נתיב', 'PATH' ),
	'url_wiki'              => array( '0', 'ויקי', 'WIKI' ),
	'url_query'             => array( '0', 'שאילתה', 'QUERY' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'הפניות_כפולות' ),
	'BrokenRedirects'           => array( 'הפניות_לא_תקינות', 'הפניות_שבורות' ),
	'Disambiguations'           => array( 'פירושונים', 'דפי_פירושונים' ),
	'Userlogin'                 => array( 'כניסה_לחשבון', 'כניסה', 'כניסה_/_הרשמה_לחשבון' ),
	'Userlogout'                => array( 'יציאה_מהחשבון', 'יציאה' ),
	'CreateAccount'             => array( 'הרשמה_לחשבון' ),
	'Preferences'               => array( 'העדפות', 'ההעדפות_שלי' ),
	'Watchlist'                 => array( 'רשימת_המעקב', 'רשימת_מעקב', 'רשימת_המעקב_שלי' ),
	'Recentchanges'             => array( 'שינויים_אחרונים' ),
	'Upload'                    => array( 'העלאה', 'העלאת_קובץ_לשרת' ),
	'UploadStash'               => array( 'מאגר_העלאות' ),
	'Listfiles'                 => array( 'רשימת_קבצים', 'רשימת_תמונות', 'קבצים', 'תמונות' ),
	'Newimages'                 => array( 'קבצים_חדשים', 'תמונות_חדשות', 'גלריית_קבצים_חדשים', 'גלריית_תמונות_חדשות' ),
	'Listusers'                 => array( 'רשימת_משתמשים', 'משתמשים' ),
	'Listgrouprights'           => array( 'רשימת_הרשאות_לקבוצה' ),
	'Statistics'                => array( 'סטטיסטיקות' ),
	'Randompage'                => array( 'אקראי', 'דף_אקראי' ),
	'Lonelypages'               => array( 'דפים_יתומים' ),
	'Uncategorizedpages'        => array( 'דפים_חסרי_קטגוריה' ),
	'Uncategorizedcategories'   => array( 'קטגוריות_חסרות_קטגוריה' ),
	'Uncategorizedimages'       => array( 'קבצים_חסרי_קטגוריה', 'תמונות_חסרות_קטגוריה' ),
	'Uncategorizedtemplates'    => array( 'תבניות_חסרות_קטגוריות' ),
	'Unusedcategories'          => array( 'קטגוריות_שאינן_בשימוש' ),
	'Unusedimages'              => array( 'קבצים_שאינם_בשימוש', 'תמונות_שאינן_בשימוש' ),
	'Wantedpages'               => array( 'דפים_מבוקשים' ),
	'Wantedcategories'          => array( 'קטגוריות_מבוקשות' ),
	'Wantedfiles'               => array( 'קבצים_מבוקשים' ),
	'Wantedtemplates'           => array( 'תבניות_מבוקשות' ),
	'Mostlinked'                => array( 'הדפים_המקושרים_ביותר', 'המקושרים_ביותר' ),
	'Mostlinkedcategories'      => array( 'הקטגוריות_המקושרות_ביותר' ),
	'Mostlinkedtemplates'       => array( 'התבניות_המקושרות_ביותר' ),
	'Mostimages'                => array( 'הקבצים_המקושרים_ביותר', 'התמונות_המקושרות_ביותר' ),
	'Mostcategories'            => array( 'הקטגוריות_הרבות_ביותר', 'הדפים_מרובי-הקטגוריות_ביותר' ),
	'Mostrevisions'             => array( 'הגרסאות_הרבות_ביותר', 'הדפים_בעלי_מספר_העריכות_הגבוה_ביותר' ),
	'Fewestrevisions'           => array( 'הגרסאות_המעטות_ביותר', 'הדפים_בעלי_מספר_העריכות_הנמוך_ביותר' ),
	'Shortpages'                => array( 'דפים_קצרים' ),
	'Longpages'                 => array( 'דפים_ארוכים' ),
	'Newpages'                  => array( 'דפים_חדשים' ),
	'Ancientpages'              => array( 'דפים_מוזנחים' ),
	'Deadendpages'              => array( 'דפים_ללא_קישורים' ),
	'Protectedpages'            => array( 'דפים_מוגנים' ),
	'Protectedtitles'           => array( 'כותרות_מוגנות' ),
	'Allpages'                  => array( 'כל_הדפים' ),
	'Prefixindex'               => array( 'דפים_המתחילים_ב' ),
	'Ipblocklist'               => array( 'רשימת_חסומים', 'רשימת_משתמשים_חסומים', 'משתמשים_חסומים' ),
	'Unblock'                   => array( 'שחרור_חסימה' ),
	'Specialpages'              => array( 'דפים_מיוחדים' ),
	'Contributions'             => array( 'תרומות', 'תרומות_המשתמש' ),
	'Emailuser'                 => array( 'שליחת_דואר_למשתמש' ),
	'Confirmemail'              => array( 'אימות_כתובת_דואר' ),
	'Whatlinkshere'             => array( 'דפים_המקושרים_לכאן' ),
	'Recentchangeslinked'       => array( 'שינויים_בדפים_המקושרים' ),
	'Movepage'                  => array( 'העברת_דף', 'העברה' ),
	'Blockme'                   => array( 'חסום_אותי' ),
	'Booksources'               => array( 'משאבי_ספרות', 'משאבי_ספרות_חיצוניים' ),
	'Categories'                => array( 'קטגוריות', 'רשימת_קטגוריות' ),
	'Export'                    => array( 'ייצוא', 'ייצוא_דפים' ),
	'Version'                   => array( 'גרסה', 'גרסת_התוכנה' ),
	'Allmessages'               => array( 'הודעות_המערכת' ),
	'Log'                       => array( 'יומנים' ),
	'Blockip'                   => array( 'חסימה', 'חסימת_כתובת', 'חסימת_משתמש' ),
	'Undelete'                  => array( 'צפייה_בדפים_מחוקים' ),
	'Import'                    => array( 'ייבוא', 'ייבוא_דפים' ),
	'Lockdb'                    => array( 'נעילת_בסיס_הנתונים' ),
	'Unlockdb'                  => array( 'שחרור_בסיס_הנתונים' ),
	'Userrights'                => array( 'ניהול_הרשאות_משתמש', 'הפיכת_משתמש_למפעיל_מערכת', 'הענקת_או_ביטול_הרשאת_בוט' ),
	'MIMEsearch'                => array( 'חיפוש_MIME' ),
	'FileDuplicateSearch'       => array( 'חיפוש_קבצים_כפולים' ),
	'Unwatchedpages'            => array( 'דפים_שאינם_במעקב' ),
	'Listredirects'             => array( 'רשימת_הפניות', 'הפניות' ),
	'Revisiondelete'            => array( 'מחיקת_ושחזור_גרסאות' ),
	'Unusedtemplates'           => array( 'תבניות_שאינן_בשימוש' ),
	'Randomredirect'            => array( 'הפניה_אקראית' ),
	'Mypage'                    => array( 'הדף_שלי', 'דף_המשתמש_שלי' ),
	'Mytalk'                    => array( 'השיחה_שלי', 'דף_השיחה_שלי' ),
	'Mycontributions'           => array( 'התרומות_שלי' ),
	'Myuploads'                 => array( 'ההעלאות_שלי' ),
	'Listadmins'                => array( 'רשימת_מפעילים' ),
	'Listbots'                  => array( 'רשימת_בוטים' ),
	'Popularpages'              => array( 'הדפים_הנצפים_ביותר', 'דפים_פופולריים' ),
	'Search'                    => array( 'חיפוש' ),
	'Resetpass'                 => array( 'שינוי_סיסמה', 'איפוס_סיסמה' ),
	'Withoutinterwiki'          => array( 'דפים_ללא_קישורי_שפה' ),
	'MergeHistory'              => array( 'מיזוג_גרסאות' ),
	'Filepath'                  => array( 'נתיב_לקובץ' ),
	'Invalidateemail'           => array( 'ביטול_דואר' ),
	'Blankpage'                 => array( 'דף_ריק' ),
	'LinkSearch'                => array( 'חיפוש_קישורים_חיצוניים' ),
	'DeletedContributions'      => array( 'תרומות_מחוקות' ),
	'Tags'                      => array( 'תגיות' ),
	'Activeusers'               => array( 'משתמשים_פעילים' ),
	'RevisionMove'              => array( 'העברת_גרסאות' ),
	'ComparePages'              => array( 'השוואת_דפים' ),
	'Badtitle'                  => array( 'כותרת_שגויה' ),
	'DisableAccount'            => array( 'ביטול_חשבון' ),
);

$namespaceNames = array(
	NS_MEDIA            => 'מדיה',
	NS_SPECIAL          => 'מיוחד',
	NS_MAIN             => '',
	NS_TALK             => 'שיחה',
	NS_USER             => 'משתמש',
	NS_USER_TALK        => 'שיחת_משתמש',
	NS_PROJECT_TALK     => 'שיחת_$1',
	NS_FILE             => 'קובץ',
	NS_FILE_TALK        => 'שיחת_קובץ',
	NS_MEDIAWIKI        => 'מדיה_ויקי',
	NS_MEDIAWIKI_TALK   => 'שיחת_מדיה_ויקי',
	NS_TEMPLATE         => 'תבנית',
	NS_TEMPLATE_TALK    => 'שיחת_תבנית',
	NS_HELP             => 'עזרה',
	NS_HELP_TALK        => 'שיחת_עזרה',
	NS_CATEGORY         => 'קטגוריה',
	NS_CATEGORY_TALK    => 'שיחת_קטגוריה',
);
$namespaceAliases = array(
	'תמונה'      => NS_FILE,
	'שיחת_תמונה' => NS_FILE_TALK,
);

$messages = array(
# User preference toggles
'tog-underline'               => 'סימון קישורים בקו תחתי:',
'tog-highlightbroken'         => 'סימון קישורים לדפים שלא נכתבו <a href="" class="new">כך</a> (או: כך<a href="" class="internal">?</a>)',
'tog-justify'                 => 'יישור פסקאות',
'tog-hideminor'               => 'הסתרת שינויים משניים ברשימת השינויים האחרונים',
'tog-hidepatrolled'           => 'הסתרת שינויים בדוקים ברשימת השינויים האחרונים',
'tog-newpageshidepatrolled'   => 'הסתרת דפים בדוקים ברשימת הדפים החדשים',
'tog-extendwatchlist'         => 'הרחבת רשימת המעקב כך שתציג את כל השינויים, לא רק את השינויים האחרונים בכל דף',
'tog-usenewrc'                => 'שימוש ברשימת שינויים אחרונים משופרת (דרוש JavaScript)',
'tog-numberheadings'          => 'מספור כותרות אוטומטי',
'tog-showtoolbar'             => 'הצגת סרגל העריכה (דרוש JavaScript)',
'tog-editondblclick'          => 'עריכת דפים בלחיצה כפולה (דרוש JavaScript)',
'tog-editsection'             => 'עריכת פסקאות באמצעות קישורים מהצורה [עריכה]',
'tog-editsectiononrightclick' => 'עריכת פסקאות על ידי לחיצה ימנית על כותרות הפסקאות (דרוש JavaScript)',
'tog-showtoc'                 => 'הצגת תוכן עניינים (עבור דפים עם יותר מ־3 כותרות)',
'tog-rememberpassword'        => 'זכירת הכניסה שלי בדפדפן זה (למשך עד {{PLURAL:$1|יום אחד|$1 ימים|יומיים}})',
'tog-watchcreations'          => 'מעקב אחרי דפים שיצרתי',
'tog-watchdefault'            => 'מעקב אחרי דפים שערכתי',
'tog-watchmoves'              => 'מעקב אחרי דפים שהעברתי',
'tog-watchdeletion'           => 'מעקב אחרי דפים שמחקתי',
'tog-minordefault'            => 'הגדרת כל פעולת עריכה כמשנית אם לא צוין אחרת',
'tog-previewontop'            => 'הצגת תצוגה מקדימה לפני תיבת העריכה (או: אחריה)',
'tog-previewonfirst'          => 'הצגת תצוגה מקדימה בעריכה ראשונה',
'tog-nocache'                 => 'מניעת אחסון הדפים בזכרון המטמון בדפדפן',
'tog-enotifwatchlistpages'    => 'שליחת דוא"ל אליך כאשר נעשה שינוי בדפים ברשימת המעקב שלך',
'tog-enotifusertalkpages'     => 'שליחת דוא"ל אליך כאשר נעשה שינוי בדף שיחת המשתמש שלך',
'tog-enotifminoredits'        => 'שליחת דוא"ל אליך גם על עריכות משניות של דפים',
'tog-enotifrevealaddr'        => 'חשיפת כתובת הדוא"ל שלך בהודעות דואר',
'tog-shownumberswatching'     => 'הצגת מספר המשתמשים העוקבים אחרי הדף',
'tog-oldsig'                  => 'תצוגה מקדימה של החתימה הקיימת:',
'tog-fancysig'                => 'טיפול בחתימה כקוד ויקי (ללא קישור אוטומטי)',
'tog-externaleditor'          => 'שימוש בעורך חיצוני כברירת מחדל (למשתמשים מומחים בלבד, דורש הגדרות מיוחדות במחשב)',
'tog-externaldiff'            => 'שימוש בתוכנת השוואת הגרסאות החיצונית כברירת מחדל (למשתמשים מומחים בלבד, דורש הגדרות מיוחדות במחשב)',
'tog-showjumplinks'           => 'הצגת קישורי נגישות מסוג "קפוץ אל"',
'tog-uselivepreview'          => 'שימוש בתצוגה מקדימה מהירה (דרוש JavaScript) (ניסיוני)',
'tog-forceeditsummary'        => 'הצגת אזהרה כשאני מכניס תקציר עריכה ריק',
'tog-watchlisthideown'        => 'הסתרת עריכות שלי ברשימת המעקב',
'tog-watchlisthidebots'       => 'הסתרת בוטים ברשימת המעקב',
'tog-watchlisthideminor'      => 'הסתרת עריכות משניות ברשימת המעקב',
'tog-watchlisthideliu'        => 'הסתרת עריכות של משתמשים רשומים ברשימת המעקב',
'tog-watchlisthideanons'      => 'הסתרת עריכות של משתמשים אנונימיים ברשימת המעקב',
'tog-watchlisthidepatrolled'  => 'הסתרת עריכות בדוקות ברשימת המעקב',
'tog-nolangconversion'        => 'ביטול המרת גרסאות שפה',
'tog-ccmeonemails'            => 'קבלת העתקים של הודעות דוא"ל הנשלחות ממני למשתמשים אחרים',
'tog-diffonly'                => 'ביטול הצגת תוכן הדף מתחת להשוואות הגרסאות',
'tog-showhiddencats'          => 'הצגת קטגוריות מוסתרות',
'tog-noconvertlink'           => 'ביטול המרת קישורים לכותרות',
'tog-norollbackdiff'          => 'השמטת ההבדלים בין הגרסאות לאחר ביצוע שחזור',

'underline-always'  => 'תמיד',
'underline-never'   => 'אף פעם',
'underline-default' => 'ברירת מחדל של הדפדפן',

# Font style option in Special:Preferences
'editfont-style'     => 'הגופן בתיבת העריכה:',
'editfont-default'   => 'ברירת מחדל של הדפדפן',
'editfont-monospace' => 'גופן ברוחב קבוע (monospace)',
'editfont-sansserif' => 'גופן ללא תגים (sans-serif)',
'editfont-serif'     => 'גופן עם תגים (serif)',

# Dates
'sunday'        => 'ראשון',
'monday'        => 'שני',
'tuesday'       => 'שלישי',
'wednesday'     => 'רביעי',
'thursday'      => 'חמישי',
'friday'        => 'שישי',
'saturday'      => 'שבת',
'sun'           => "ראש'",
'mon'           => 'שני',
'tue'           => "שלי'",
'wed'           => "רבי'",
'thu'           => "חמי'",
'fri'           => "שיש'",
'sat'           => 'שבת',
'january'       => 'ינואר',
'february'      => 'פברואר',
'march'         => 'מרץ',
'april'         => 'אפריל',
'may_long'      => 'מאי',
'june'          => 'יוני',
'july'          => 'יולי',
'august'        => 'אוגוסט',
'september'     => 'ספטמבר',
'october'       => 'אוקטובר',
'november'      => 'נובמבר',
'december'      => 'דצמבר',
'january-gen'   => 'בינואר',
'february-gen'  => 'בפברואר',
'march-gen'     => 'במרץ',
'april-gen'     => 'באפריל',
'may-gen'       => 'במאי',
'june-gen'      => 'ביוני',
'july-gen'      => 'ביולי',
'august-gen'    => 'באוגוסט',
'september-gen' => 'בספטמבר',
'october-gen'   => 'באוקטובר',
'november-gen'  => 'בנובמבר',
'december-gen'  => 'בדצמבר',
'jan'           => "ינו'",
'feb'           => "פבר'",
'mar'           => 'מרץ',
'apr'           => "אפר'",
'may'           => 'מאי',
'jun'           => 'יוני',
'jul'           => 'יולי',
'aug'           => "אוג'",
'sep'           => "ספט'",
'oct'           => "אוק'",
'nov'           => "נוב'",
'dec'           => "דצמ'",

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|קטגוריה|קטגוריות}}',
'category_header'                => 'דפים בקטגוריה "$1"',
'subcategories'                  => 'קטגוריות משנה',
'category-media-header'          => 'קובצי מדיה בקטגוריה "$1"',
'category-empty'                 => "'''קטגוריה זו אינה כוללת דפים או קובצי מדיה.'''",
'hidden-categories'              => '{{PLURAL:$1|קטגוריה מוסתרת|קטגוריות מוסתרות}}',
'hidden-category-category'       => 'קטגוריות מוסתרות',
'category-subcat-count'          => '{{PLURAL:$2|קטגוריה זו כוללת את קטגוריית המשנה הבאה בלבד|דף קטגוריה זה כולל את {{PLURAL:$1|קטגוריית המשנה הבאה|$1 קטגוריות המשנה הבאות}}, מתוך $2 בקטגוריה כולה}}.',
'category-subcat-count-limited'  => 'קטגוריה זו כוללת את {{PLURAL:$1|קטגוריית המשנה הבאה|$1 קטגוריות המשנה הבאות}}.',
'category-article-count'         => '{{PLURAL:$2|קטגוריה זו כוללת את הדף הבא בלבד|דף קטגוריה זה כולל את {{PLURAL:$1|הדף הבא|$1 הדפים הבאים}}, מתוך $2 בקטגוריה כולה}}.',
'category-article-count-limited' => 'קטגוריה זו כוללת את {{PLURAL:$1|הדף הבא|$1 הדפים הבאים}}.',
'category-file-count'            => '{{PLURAL:$2|קטגוריה זו כוללת את הקובץ הבא בלבד|דף קטגוריה זה כולל את {{PLURAL:$1|הקובץ הבא|$1 הקבצים הבאים}}, מתוך $2 בקטגוריה כולה}}.',
'category-file-count-limited'    => 'קטגוריה זו כוללת את {{PLURAL:$1|הקובץ הבא|$1 הקבצים הבאים}}.',
'listingcontinuesabbrev'         => '(המשך)',
'index-category'                 => 'דפים המופיעים במנועי חיפוש',
'noindex-category'               => 'דפים המוסתרים ממנועי חיפוש',

'mainpagetext'      => "'''תוכנת מדיה־ויקי הותקנה בהצלחה.'''",
'mainpagedocfooter' => 'היעזרו ב[http://meta.wikimedia.org/wiki/Help:Contents מדריך למשתמש] למידע על שימוש בתוכנת הוויקי.

== קישורים שימושיים ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings רשימת ההגדרות]
* [http://www.mediawiki.org/wiki/Manual:FAQ שאלות נפוצות]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce רשימת התפוצה על השקת גרסאות]',

'about'         => 'אודות',
'article'       => 'דף תוכן',
'newwindow'     => '(נפתח בחלון חדש)',
'cancel'        => 'ביטול / יציאה',
'moredotdotdot' => 'עוד…',
'mypage'        => 'הדף שלי',
'mytalk'        => 'דף השיחה שלי',
'anontalk'      => 'השיחה עבור IP זה',
'navigation'    => 'ניווט',
'and'           => '&#32;וגם',

# Cologne Blue skin
'qbfind'         => 'חיפוש',
'qbbrowse'       => 'דפדוף',
'qbedit'         => 'עריכה',
'qbpageoptions'  => 'אפשרויות דף',
'qbpageinfo'     => 'מידע על הדף',
'qbmyoptions'    => 'האפשרויות שלי',
'qbspecialpages' => 'דפים מיוחדים',
'faq'            => 'שאלות ותשובות',
'faqpage'        => 'Project:שאלות ותשובות',

# Vector skin
'vector-action-addsection'       => 'הוספת נושא',
'vector-action-delete'           => 'מחיקה',
'vector-action-move'             => 'העברה',
'vector-action-protect'          => 'הגנה',
'vector-action-undelete'         => 'ביטול מחיקה',
'vector-action-unprotect'        => 'הסרת הגנה',
'vector-simplesearch-preference' => 'הפעלת הצעות החיפוש המשופרות (בעיצוב וקטור בלבד)',
'vector-view-create'             => 'יצירה',
'vector-view-edit'               => 'עריכה',
'vector-view-history'            => 'הצגת היסטוריה',
'vector-view-view'               => 'קריאה',
'vector-view-viewsource'         => 'הצגת מקור',
'actions'                        => 'פעולות',
'namespaces'                     => 'מרחבי שם',
'variants'                       => 'גרסאות שפה',

'errorpagetitle'    => 'שגיאה',
'returnto'          => 'חזרה לדף $1.',
'tagline'           => 'מתוך {{SITENAME}}',
'help'              => 'עזרה',
'search'            => 'חיפוש',
'searchbutton'      => 'חיפוש',
'go'                => 'הצגה',
'searcharticle'     => 'לדף',
'history'           => 'היסטוריית הדף',
'history_short'     => 'היסטוריית הדף',
'updatedmarker'     => 'עודכן מאז ביקורך האחרון',
'info_short'        => 'מידע',
'printableversion'  => 'גרסת הדפסה',
'permalink'         => 'קישור קבוע',
'print'             => 'גרסה להדפסה',
'edit'              => 'עריכה',
'create'            => 'יצירה',
'editthispage'      => 'עריכת דף זה',
'create-this-page'  => 'יצירת דף זה',
'delete'            => 'מחיקה',
'deletethispage'    => 'מחיקת דף זה',
'undelete_short'    => 'שחזור {{PLURAL:$1|עריכה אחת|$1 עריכות}}',
'protect'           => 'הגנה',
'protect_change'    => 'שינוי',
'protectthispage'   => 'הפעלת הגנה על דף זה',
'unprotect'         => 'הסרת הגנה',
'unprotectthispage' => 'הסרת הגנה מדף זה',
'newpage'           => 'דף חדש',
'talkpage'          => 'שיחה על דף זה',
'talkpagelinktext'  => 'שיחה',
'specialpage'       => 'דף מיוחד',
'personaltools'     => 'כלים אישיים',
'postcomment'       => 'פסקה חדשה',
'articlepage'       => 'צפייה בדף התוכן',
'talk'              => 'שיחה',
'views'             => 'צפיות',
'toolbox'           => 'תיבת כלים',
'userpage'          => 'צפייה בדף המשתמש',
'projectpage'       => 'צפייה בדף המיזם',
'imagepage'         => 'צפייה בדף הקובץ',
'mediawikipage'     => 'צפייה בדף ההודעה',
'templatepage'      => 'צפייה בדף התבנית',
'viewhelppage'      => 'צפייה בדף העזרה',
'categorypage'      => 'צפייה בדף הקטגוריה',
'viewtalkpage'      => 'צפייה בדף השיחה',
'otherlanguages'    => 'דף זה בשפות אחרות',
'redirectedfrom'    => '(הופנה מהדף $1)',
'redirectpagesub'   => 'דף הפניה',
'lastmodifiedat'    => 'שונה לאחרונה ב־$2, $1.',
'viewcount'         => 'דף זה נצפה {{PLURAL:$1|פעם אחת|$1 פעמים|פעמיים}}.',
'protectedpage'     => 'דף מוגן',
'jumpto'            => 'קפיצה אל:',
'jumptonavigation'  => 'ניווט',
'jumptosearch'      => 'חיפוש',
'view-pool-error'   => 'מצטערים, השרתים עמוסים כרגע.
יותר מדי משתמשים מנסים לצפות בדף זה.
אנא המתינו זמן מה לפני שתנסו שוב לצפות בדף.

$1',
'pool-timeout'      => 'זמן ההמתנה לסיום הנעילה עבר',
'pool-queuefull'    => 'התור מלא',
'pool-errorunknown' => 'שגיאה בלתי ידועה',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'אודות {{SITENAME}}',
'aboutpage'            => 'Project:אודות',
'copyright'            => 'התוכן מוגש בכפוף ל־$1.<br /> בעלי זכויות היוצרים מפורטים בהיסטוריית השינויים של הדף.',
'copyrightpage'        => '{{ns:project}}:זכויות יוצרים',
'currentevents'        => 'אקטואליה',
'currentevents-url'    => 'Project:אקטואליה',
'disclaimers'          => 'הבהרה משפטית',
'disclaimerpage'       => 'Project:הבהרה משפטית',
'edithelp'             => 'עזרה לעריכה',
'edithelppage'         => 'Help:עריכת דף',
'helppage'             => 'Help:תפריט ראשי',
'mainpage'             => 'עמוד ראשי',
'mainpage-description' => 'עמוד ראשי',
'policy-url'           => 'Project:נהלים',
'portal'               => 'שער הקהילה',
'portal-url'           => 'Project:שער הקהילה',
'privacy'              => 'מדיניות הפרטיות',
'privacypage'          => 'Project:מדיניות הפרטיות',

'badaccess'        => 'שגיאה בהרשאות',
'badaccess-group0' => 'אינכם מורשים לבצע את הפעולה שביקשתם.',
'badaccess-groups' => 'הפעולה שביקשתם לבצע מוגבלת למשתמשים ב{{PLURAL:$2|קבוצה|אחת הקבוצות}} $1.',

'versionrequired'     => 'נדרשת גרסה $1 של מדיה־ויקי',
'versionrequiredtext' => 'גרסה $1 של מדיה־ויקי נדרשת לשימוש בדף זה. למידע נוסף, ראו את [[Special:Version|דף הגרסה]].',

'ok'                      => 'אישור',
'pagetitle'               => '$1 – {{SITENAME}}',
'retrievedfrom'           => '<br /><span style="font-size: smaller;">מקור: $1</span>',
'youhavenewmessages'      => 'יש לך $1 ($2).',
'newmessageslink'         => 'הודעות חדשות',
'newmessagesdifflink'     => 'השוואה לגרסה הקודמת',
'youhavenewmessagesmulti' => 'יש לך הודעות חדשות ב־$1',
'editsection'             => 'עריכה',
'editold'                 => 'עריכה',
'viewsourceold'           => 'הצגת מקור',
'editlink'                => 'עריכה',
'viewsourcelink'          => 'הצגת מקור',
'editsectionhint'         => 'עריכת פסקה: $1',
'toc'                     => 'תוכן עניינים',
'showtoc'                 => 'הצגה',
'hidetoc'                 => 'הסתרה',
'collapsible-collapse'    => 'כיווץ',
'collapsible-expand'      => 'הרחבה',
'thisisdeleted'           => 'שחזור או הצגת $1?',
'viewdeleted'             => 'הצגת $1?',
'restorelink'             => '{{PLURAL:$1|גרסה מחוקה אחת|$1 גרסאות מחוקות}}',
'feedlinks'               => 'הזנה:',
'feed-invalid'            => 'סוג הזנת המנוי שגוי.',
'feed-unavailable'        => 'הזנות אינן זמינות',
'site-rss-feed'           => 'RSS של $1',
'site-atom-feed'          => 'Atom של $1',
'page-rss-feed'           => 'RSS של $1',
'page-atom-feed'          => 'Atom של $1',
'red-link-title'          => '$1 (הדף אינו קיים)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'דף תוכן',
'nstab-user'      => 'דף משתמש',
'nstab-media'     => 'מדיה',
'nstab-special'   => 'דף מיוחד',
'nstab-project'   => 'דף מיזם',
'nstab-image'     => 'קובץ',
'nstab-mediawiki' => 'הודעה',
'nstab-template'  => 'תבנית',
'nstab-help'      => 'דף עזרה',
'nstab-category'  => 'קטגוריה',

# Main script and global functions
'nosuchaction'      => 'אין פעולה כזו',
'nosuchactiontext'  => 'הפעולה שצוינה בכתובת ה־URL אינה תקינה.
ייתכן שטעיתם בהקלדת ה־URL, או שהשתמשתם בקישור לא נכון.
ייתכן גם שהבעיה נוצרה כתוצאה מבאג בתוכנה המשמשת את {{SITENAME}}.',
'nosuchspecialpage' => 'אין דף מיוחד בשם זה',
'nospecialpagetext' => '<strong>ביקשתם דף מיוחד שאינו קיים.</strong>

ראו גם את [[Special:SpecialPages|רשימת הדפים המיוחדים התקינים]].',

# General errors
'error'                => 'שגיאה',
'databaseerror'        => 'שגיאת בסיס נתונים',
'dberrortext'          => 'ארעה שגיאת תחביר בשאילתה לבסיס הנתונים.
שגיאה זו עלולה להעיד על באג בתוכנה.
השאילתה האחרונה שבוצעה לבסיס הנתונים הייתה:
<blockquote><tt>$1</tt></blockquote>
מתוך הפונקציה "<tt>$2</tt>".
בסיס הנתונים החזיר את השגיאה "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'ארעה שגיאת תחביר בשאילתה לבסיס הנתונים.
השאילתה האחרונה שבוצעה לבסיס הנתונים הייתה:
"$1"
מתוך הפונקציה "$2".
בסיס הנתונים החזיר את השגיאה "$3: $4".',
'laggedslavemode'      => "'''אזהרה:''' הדף עשוי שלא להכיל עדכונים אחרונים.",
'readonly'             => 'בסיס הנתונים נעול',
'enterlockreason'      => 'הזינו סיבה לנעילת בסיס הנתונים, כולל הערכה לגבי מועד שחרור הנעילה.',
'readonlytext'         => 'בסיס נתונים זה של האתר נעול ברגע זה לצורך הזנת נתונים ושינויים. ככל הנראה מדובר בתחזוקה שוטפת, שלאחריה יחזור האתר לפעולתו הרגילה.

מנהל המערכת שנעל את בסיס הנתונים סיפק את ההסבר הבא: $1',
'missing-article'      => 'בסיס הנתונים לא מצא את הטקסט של הדף שהוא היה אמור למצוא, בשם "$1" $2.

הדבר נגרם בדרך כלל על ידי קישור ישן להשוואת גרסאות של דף שנמחק או לגרסה של דף כזה.

אם זה אינו המקרה, זהו כנראה באג בתוכנה.
אנא דווחו על כך ל[[Special:ListUsers/sysop|מפעיל מערכת]], תוך שמירת פרטי כתובת ה־URL.',
'missingarticle-rev'   => '(מספר גרסה: $1)',
'missingarticle-diff'  => '(השוואת הגרסאות: $1, $2)',
'readonly_lag'         => 'בסיס הנתונים ננעל אוטומטית כדי לאפשר לבסיסי הנתונים המשניים להתעדכן מהבסיס הראשי.',
'internalerror'        => 'שגיאה פנימית',
'internalerror_info'   => 'שגיאה פנימית: $1',
'fileappenderrorread'  => 'קריאת "$1" במהלך צירוף נכשלה.',
'fileappenderror'      => 'צירוף "$1" ל־"$2" נכשל.',
'filecopyerror'        => 'העתקת "$1" ל־"$2" נכשלה.',
'filerenameerror'      => 'שינוי השם של "$1" ל־"$2" נכשל.',
'filedeleteerror'      => 'מחיקת "$1" נכשלה.',
'directorycreateerror' => 'יצירת התיקייה "$1" נכשלה.',
'filenotfound'         => 'הקובץ "$1" לא נמצא.',
'fileexistserror'      => 'הכתיבה לקובץ "$1" נכשלה: הקובץ קיים',
'unexpected'           => 'ערך לא צפוי: "$1"="$2"',
'formerror'            => 'שגיאה: לא יכול לשלוח טופס.',
'badarticleerror'      => 'לא ניתן לבצע פעולה זו בדף זה.',
'cannotdelete'         => 'לא ניתן היה למחוק את הדף או הקובץ "$1".
ייתכן שהוא נמחק כבר על ידי מישהו אחר.',
'badtitle'             => 'כותרת שגויה',
'badtitletext'         => 'כותרת הדף המבוקש הייתה לא־חוקית, ריקה, קישור ויקי פנימי, או פנים שפה שגוי.',
'perfcached'           => 'המידע הבא הוא עותק שמור של המידע, ועשוי שלא להיות מעודכן.',
'perfcachedts'         => 'המידע הבא הוא עותק שמור של המידע, שעודכן לאחרונה ב־$1.',
'querypage-no-updates' => 'העדכונים לדף זה כרגע מופסקים, והמידע לא יעודכן באופן שוטף.',
'wrong_wfQuery_params' => 'הפרמטרים שהוזנו ל־wfQuery() אינם נכונים:<br />
פונקציה: $1<br />
שאילתה: $2',
'viewsource'           => 'הצגת מקור',
'viewsourcefor'        => 'לדף $1',
'actionthrottled'      => 'הפעולה הוגבלה',
'actionthrottledtext'  => 'כאמצעי נגד ספאם, אינכם מורשים לבצע פעולה זו פעמים רבות מדי בזמן קצר. אנא נסו שוב בעוד מספר דקות.',
'protectedpagetext'    => 'דף זה מוגן ולא ניתן לערוך אותו.',
'viewsourcetext'       => 'באפשרותכם לצפות בטקסט המקור של הדף ולהעתיקו:',
'protectedinterface'   => 'דף זה הוא אחד מסדרת דפים המספקים הודעות מערכת לתוכנה, ומוגן כדי למנוע השחתות.',
'editinginterface'     => "'''אזהרה:''' הדף שאתם עורכים הוא אחד הדפים המספקים הודעות מערכת לתוכנה.
שינויים בדף זה ישפיעו על תצוגת ממשק המשתמש של משתמשים אחרים.",
'sqlhidden'            => '(שאילתת ה־SQL מוסתרת)',
'cascadeprotected'     => 'דף זה מוגן מעריכה כיוון שהוא מוכלל {{PLURAL:$1|בדף הבא, שמופעלת אצלו|בדפים הבאים, שמופעלת אצלם}} הגנה מדורגת:
$2',
'namespaceprotected'   => "אינכם מורשים לערוך דפים במרחב השם '''$1'''.",
'customcssjsprotected' => 'אינכם מורשים לערוך דף זה, כיוון שהוא כולל את ההגדרות האישיות של משתמש אחר.',
'ns-specialprotected'  => 'לא ניתן לערוך דפים מיוחדים.',
'titleprotected'       => 'לא ניתן ליצור דף זה, כיוון שהמשתמש [[User:$1|$1]] הגן עליו מפני יצירה.
הסיבה שניתנה לחסימה היא "$2".',

# Virus scanner
'virus-badscanner'     => "הגדרות שגויות: סורק הווירוסים אינו ידוע: ''$1''",
'virus-scanfailed'     => 'הסריקה נכשלה (קוד: $1)',
'virus-unknownscanner' => 'אנטי־וירוס בלתי ידוע:',

# Login and logout pages
'logouttext'                 => 'יצאתם זה עתה מהחשבון. באפשרותכם להמשיך ולעשות שימוש ב{{grammar:תחילית|{{SITENAME}}}} באופן אנונימי, או [[Special:UserLogin|לשוב ולהיכנס לאתר]] עם שם משתמש זהה או אחר.',
'welcomecreation'            => '== ברוך הבא, $1! ==
חשבונך נפתח. אל תשכח להתאים את [[Special:Preferences|העדפות המשתמש]] שלך.',
'yourname'                   => 'שם משתמש:',
'yourpassword'               => 'סיסמה:',
'yourpasswordagain'          => 'הקש סיסמה שנית:',
'remembermypassword'         => 'זכירת הכניסה שלי בדפדפן זה (למשך עד {{PLURAL:$1|יום אחד|$1 ימים|יומיים}})',
'securelogin-stick-https'    => 'המשך שימוש ב־HTTPS אחרי הכניסה',
'yourdomainname'             => 'התחום שלך:',
'externaldberror'            => 'הייתה שגיאה בבסיס הנתונים של ההזדהות, או שאינכם רשאים לעדכן את חשבונכם החיצוני.',
'login'                      => 'כניסה לחשבון',
'nav-login-createaccount'    => 'כניסה לחשבון / הרשמה',
'loginprompt'                => 'לפני הכניסה לחשבון ב{{grammar:תחילית|{{SITENAME}}}}, עליכם לוודא כי ה"עוגיות" (Cookies) מופעלות.',
'userlogin'                  => 'כניסה לחשבון / הרשמה',
'userloginnocreate'          => 'כניסה לחשבון',
'logout'                     => 'יציאה מהחשבון',
'userlogout'                 => 'יציאה מהחשבון',
'notloggedin'                => 'לא בחשבון',
'nologin'                    => 'אין לכם חשבון? $1.',
'nologinlink'                => 'אתם מוזמנים להירשם',
'createaccount'              => 'יצירת משתמש חדש',
'gotaccount'                 => 'כבר נרשמתם? $1.',
'gotaccountlink'             => 'היכנסו לחשבון',
'createaccountmail'          => 'באמצעות דוא"ל',
'createaccountreason'        => 'סיבה:',
'badretype'                  => 'הסיסמאות שהזנתם אינן מתאימות.',
'userexists'                 => 'שם המשתמש שבחרתם נמצא בשימוש.
אנא בחרו שם אחר.',
'loginerror'                 => 'שגיאה בכניסה לאתר',
'createaccounterror'         => 'לא ניתן היה ליצור את החשבון: $1',
'nocookiesnew'               => 'חשבון המשתמש שלכם נוצר, אך לא נכנסתם כמשתמשים רשומים.
{{SITENAME}} משתמש בעוגיות כדי להכניס משתמשים למערכת.
בדפדפן שלכם העוגיות מבוטלות.
אנא הפעילו אותן מחדש, ולאחר מכן תוכלו להיכנס למערכת עם שם המשתמש והסיסמה החדשים שלכם.',
'nocookieslogin'             => '{{SITENAME}} משתמש בעוגיות כדי להכניס משתמשים למערכת.
בדפדפן שלכם העוגיות מבוטלות.
אנא הפעילו אותן מחדש ונסו שוב.',
'nocookiesfornew'            => 'חשבון המשתמש לא נוצר, כיוון שלא יכולנו לוודא את מקורו.
ודאו שהעוגיות מופעלות בדפדפן שלכם, העלו מחדש דף זה ונסו שוב.',
'noname'                     => 'לא הכנסתם שם משתמש חוקי',
'loginsuccesstitle'          => 'הכניסה הושלמה בהצלחה',
'loginsuccess'               => "'''נכנסת ל{{grammar:תחילית|{{SITENAME}}}} בשם \"\$1\".'''",
'nosuchuser'                 => 'אין משתמש בשם "$1".
אנא ודאו שהאיות נכון (כולל אותיות רישיות וקטנות), או [[Special:UserLogin/signup|צרו חשבון חדש]].',
'nosuchusershort'            => 'אין משתמש בשם "<nowiki>$1</nowiki>". אנא ודאו שהאיות נכון.',
'nouserspecified'            => 'עליכם לציין שם משתמש.',
'login-userblocked'          => 'משתמש זה חסום. אינכם מורשים להיכנס לחשבון.',
'wrongpassword'              => 'הסיסמה שהקלדתם שגויה, אנא נסו שוב.',
'wrongpasswordempty'         => 'הסיסמה שהקלדתם ריקה. אנא נסו שוב.',
'passwordtooshort'           => 'סיסמאות חייבות להיות באורך {{PLURAL:$1|תו אחד|$1 תווים}} לפחות.',
'password-name-match'        => 'סיסמתכם חייבת להיות שונה משם המשתמש שלכם.',
'mailmypassword'             => 'שלחו לי סיסמה חדשה',
'passwordremindertitle'      => 'סיסמה זמנית חדשה מ{{grammar:תחילית|{{SITENAME}}}}',
'passwordremindertext'       => 'מישהו (ככל הנראה אתם, מכתובת ה־IP מספר $1) ביקש סיסמה
חדשה לכניסה לחשבון ב{{grammar:תחילית|{{SITENAME}}}} ($4). נוצרה סיסמה זמנית למשתמש "$2",
וסיסמה זו היא "$3". אם זו הייתה כוונתכם, תוכלו כעת להיכנס לחשבון ולבחור סיסמה חדשה.
הסיסמה הזמנית שלכם תפקע תוך {{PLURAL:$5|יום אחד|$5 ימים|יומיים}}.

עליכם להיכנס לאתר ולשנות את סיסמתכם בהקדם האפשרי. אם מישהו אחר ביקש סיסמה חדשה זו או אם נזכרתם בסיסמתכם
ואינכם רוצים עוד לשנות אותה, באפשרותכם להתעלם מהודעה זו ולהמשיך להשתמש בסיסמתכם הישנה.',
'noemail'                    => 'לא רשומה כתובת דואר אלקטרוני עבור משתמש  "$1".',
'noemailcreate'              => 'עליכם לספק כתובת דואר אלקטרוני תקינה',
'passwordsent'               => 'סיסמה חדשה נשלחה לכתובת הדואר האלקטרוני הרשומה עבור "$1".
אנא היכנסו חזרה לאתר אחרי שתקבלו אותה.',
'blocked-mailpassword'       => 'כתובת ה־IP שלכם חסומה מעריכה, ולפיכך אינכם מורשים להשתמש באפשרות שחזור הסיסמה כדי למנוע ניצול לרעה של התכונה.',
'eauthentsent'               => 'דוא"ל אישור נשלח לכתובת הדוא"ל שקבעת. לפני שדברי דוא"ל אחרים נשלחים לחשבון הזה, תצטרך לפעול לפי ההוראות בדוא"ל כדי לוודא שהדוא"ל הוא אכן שלך.',
'throttled-mailpassword'     => 'כבר נעשה שימוש באפשרות שחזור הסיסמה ב{{PLURAL:$1|שעה האחרונה|־$1 השעות האחרונות}}. כדי למנוע ניצול לרעה, רק דואר אחד כזה יכול להישלח כל {{PLURAL:$1|שעה אחת|$1 שעות}}.',
'mailerror'                  => 'שגיאה בשליחת דואר: $1',
'acct_creation_throttle_hit' => 'מבקרים באתר זה דרך כתובת ה־IP שלכם כבר יצרו {{PLURAL:$1|חשבון אחד|$1 חשבונות}} ביום האחרון. זהו המקסימום המותר בתקופה זו.
לפיכך, מבקרים דרך כתובת ה־IP הזו לא יכולים ליצור חשבונות נוספים ברגע זה.',
'emailauthenticated'         => 'כתובת הדוא"ל שלך אושרה ב־$3, $2.',
'emailnotauthenticated'      => 'כתובת הדוא"ל שלכם <strong>עדיין לא אושרה</strong> - שירותי הדוא"ל הבאים אינם פעילים.',
'noemailprefs'               => 'אנא ציינו כתובת דוא"ל בהעדפות שלכם כדי שתכונות אלה יעבדו.',
'emailconfirmlink'           => 'אישור כתובת הדוא"ל שלך',
'invalidemailaddress'        => 'כתובת הדוא"ל אינה מתקבלת כיוון שנראה שהיא בפורמט לא נכון.
אנא הקלידו כתובת תקינה או השאירו את השדה ריק.',
'accountcreated'             => 'החשבון נוצר',
'accountcreatedtext'         => 'חשבון המשתמש $1 נוצר.',
'createaccount-title'        => 'יצירת חשבון ב{{grammar:תחילית|{{SITENAME}}}}',
'createaccount-text'         => 'מישהו יצר חשבון בשם $2 ב{{grammar:תחילית|{{SITENAME}}}} ($4), והסיסמה הזמנית של החשבון היא "$3". עליכם להיכנס ולשנות עכשיו את הסיסמה.

באפשרותכם להתעלם מהודעה זו, אם החשבון נוצר בטעות.',
'usernamehasherror'          => 'שם משתמש אינו יכול לכלול תווי סולמית',
'login-throttled'            => 'ביצעתם לאחרונה ניסיונות רבים מדי להיכנס לחשבון זה.
אנא המתינו לפני שתנסו שוב.',
'loginlanguagelabel'         => 'שפה: $1',
'suspicious-userlogout'      => 'בקשתכם לצאת מהחשבון נדחתה כיוון שנראה שהיא נשלחה על ידי דפדפן שבור או שרת פרוקסי עם זיכרון מטמון.',

# E-mail sending
'php-mail-error-unknown' => 'שגיאה לא ידועה בפונקציה mail() של PHP',

# JavaScript password checks
'password-strength'            => 'חוזק סיסמה מוערך: $1',
'password-strength-bad'        => 'רע',
'password-strength-mediocre'   => 'בינוני',
'password-strength-acceptable' => 'סביר',
'password-strength-good'       => 'טוב',
'password-retype'              => 'הקלידו מחדש את סיסמתכם כאן',
'password-retype-mismatch'     => 'הסיסמאות אינן מתאימות',

# Password reset dialog
'resetpass'                 => 'שינוי סיסמה',
'resetpass_announce'        => 'נכנסתם באמצעות סיסמה זמנית שנשלחה אליכם בדוא"ל.
כדי לסיים את הכניסה, עליכם לקבוע כאן סיסמה חדשה:',
'resetpass_text'            => '<!-- הוסיפו טקסט כאן -->',
'resetpass_header'          => 'שינוי סיסמת החשבון',
'oldpassword'               => 'סיסמה ישנה:',
'newpassword'               => 'סיסמה חדשה:',
'retypenew'                 => 'חזרה על הסיסמה חדשה:',
'resetpass_submit'          => 'הגדרת הסיסמה וכניסה לחשבון',
'resetpass_success'         => 'סיסמתכם שונתה בהצלחה! מכניס אתכם למערכת…',
'resetpass_forbidden'       => 'לא ניתן לשנות סיסמאות.',
'resetpass-no-info'         => 'עליכם להיכנס לחשבון כדי לגשת לדף זה באופן ישיר.',
'resetpass-submit-loggedin' => 'שינוי סיסמה',
'resetpass-submit-cancel'   => 'ביטול',
'resetpass-wrong-oldpass'   => 'הסיסמה הזמנית או הנוכחית אינה תקינה.
ייתכן שכבר שיניתם את סיסמתכם או שכבר ביקשתם סיסמה זמנית חדשה.',
'resetpass-temp-password'   => 'סיסמה זמנית:',

# Edit page toolbar
'bold_sample'     => 'טקסט מודגש',
'bold_tip'        => 'טקסט מודגש',
'italic_sample'   => 'טקסט נטוי',
'italic_tip'      => 'טקסט נטוי (לא מומלץ בעברית)',
'link_sample'     => 'קישור',
'link_tip'        => 'קישור פנימי',
'extlink_sample'  => 'http://www.example.com כותרת הקישור לתצוגה',
'extlink_tip'     => 'קישור חיצוני (כולל קידומת http מלאה)',
'headline_sample' => 'כותרת',
'headline_tip'    => 'כותרת – דרגה 2',
'math_sample'     => 'formula',
'math_tip'        => 'נוסחה מתמטית (LaTeX)',
'nowiki_sample'   => 'טקסט לא מעוצב',
'nowiki_tip'      => 'טקסט לא מעוצב (התעלם מסימני ויקי)',
'image_tip'       => 'קובץ המוצג בתוך הדף',
'media_tip'       => 'קישור לקובץ מדיה',
'sig_tip'         => 'חתימה + שעה',
'hr_tip'          => 'קו אופקי (השתדלו להימנע משימוש בקו)',

# Edit pages
'summary'                          => 'תקציר:',
'subject'                          => 'נושא/כותרת:',
'minoredit'                        => 'זהו שינוי משני',
'watchthis'                        => 'מעקב אחרי דף זה',
'savearticle'                      => 'שמירה',
'preview'                          => 'תצוגה מקדימה',
'showpreview'                      => 'תצוגה מקדימה',
'showlivepreview'                  => 'תצוגה מקדימה מהירה',
'showdiff'                         => 'הצגת שינויים',
'anoneditwarning'                  => "'''אזהרה:''' אינכם מחוברים לחשבון. כתובת ה־IP שלכם תירשם בהיסטוריית העריכות של הדף.",
'anonpreviewwarning'               => "''אינכם מחוברים לחשבון. שמירה תגרום לכתובת ה־IP שלכם להירשם בהיסטוריית העריכות של הדף.''",
'missingsummary'                   => "'''תזכורת:''' לא הזנתם תקציר עריכה.
אם תלחצו שוב על הכפתור \"{{int:savearticle}}\", עריכתכם תישמר בלעדיו.",
'missingcommenttext'               => 'אנא הקלידו את ההודעה למטה.',
'missingcommentheader'             => "'''תזכורת:''' לא הזנתם נושא/כותרת להודעה זו.
אם תלחצו שוב על הכפתור \"{{int:savearticle}}\", עריכתכם תישמר בלעדיו.",
'summary-preview'                  => 'תצוגה מקדימה של התקציר:',
'subject-preview'                  => 'תצוגה מקדימה של הנושא/הכותרת:',
'blockedtitle'                     => 'המשתמש חסום',
'blockedtext'                      => '\'\'\'שם המשתמש או כתובת ה־IP שלכם נחסמו.\'\'\'

החסימה בוצעה על ידי $1. הסיבה שניתנה לכך היא \'\'\'$2\'\'\'.

* תחילת החסימה: $8
* פקיעת החסימה: $6
* החסימה שבוצעה: $7

באפשרותכם ליצור קשר עם $1 או עם כל אחד מ[[{{MediaWiki:Grouppage-sysop}}|מפעילי המערכת]] האחרים כדי לדון על החסימה.
אינכם יכולים להשתמש בתכונת "שליחת דואר אלקטרוני למשתמש זה" אם לא ציינתם כתובת דוא"ל תקפה ב[[Special:Preferences|העדפות המשתמש שלכם]] או אם נחסמתם משליחת דוא"ל.
כתובת ה־IP שלכם היא $3, ומספר החסימה שלכם הוא #$5.
אנא ציינו את כל הפרטים הללו בכל פנייה למפעילי המערכת.',
'autoblockedtext'                  => 'כתובת ה־IP שלכם נחסמה באופן אוטומטי כיוון שמשתמש אחר, שנחסם על ידי $1, עשה בה שימוש.
הסיבה שניתנה לחסימה היא:

:\'\'\'$2\'\'\'

* תחילת החסימה: $8
* פקיעת החסימה: $6
* החסימה שבוצעה: $7

באפשרותכם ליצור קשר עם $1 או עם כל אחד מ[[{{MediaWiki:Grouppage-sysop}}|מפעילי המערכת]] האחרים כדי לדון על החסימה.
אינכם יכולים להשתמש בתכונת "שליחת דואר אלקטרוני למשתמש זה" אם לא ציינתם כתובת דוא"ל תקפה ב[[Special:Preferences|העדפות המשתמש שלכם]] או אם נחסמתם משליחת דוא"ל.
כתובת ה־IP שלכם היא $3, ומספר החסימה שלכם הוא #$5.
אנא ציינו את כל הפרטים הללו בכל פנייה למפעילי המערכת.',
'blockednoreason'                  => 'לא ניתנה סיבה',
'blockedoriginalsource'            => "טקסט המקור של '''$1''' מוצג למטה:",
'blockededitsource'                => "הטקסט של '''העריכות שלך''' לדף '''$1''' מוצג למטה:",
'whitelistedittitle'               => 'כניסה לחשבון נדרשת לעריכה',
'whitelistedittext'                => 'עליכם $1 כדי לערוך דפים.',
'confirmedittext'                  => 'עליכם לאמת את כתובת הדוא"ל שלכם לפני שתוכלו לערוך דפים. אנא הגדירו ואמתו את כתובת הדוא"ל שלכם באמצעות [[Special:Preferences|העדפות המשתמש]] שלכם.',
'nosuchsectiontitle'               => 'הפסקה לא נמצאה',
'nosuchsectiontext'                => 'ניסיתם לערוך פסקה שאינה קיימת. ייתכן שהיא הועברה או נמחקה בעת שצפיתם בדף.',
'loginreqtitle'                    => 'כניסה לחשבון נדרשת',
'loginreqlink'                     => 'להיכנס לחשבון',
'loginreqpagetext'                 => 'עליכם $1 כדי לצפות בדפים אחרים.',
'accmailtitle'                     => 'הסיסמה נשלחה',
'accmailtext'                      => 'סיסמה אקראית עבור [[User talk:$1|$1]] נשלחה אל $2.

ניתן לשנות את הסיסמה לחשבון החדש הזה בדף [[Special:ChangePassword|שינוי הסיסמה]] לאחר הכניסה.',
'newarticle'                       => '(חדש)',
'newarticletext'                   => "הגעתם לדף שעדיין איננו קיים.
כדי ליצור דף חדש, כתבו את התוכן שלכם בתיבת הטקסט למטה.
כדי ליצור את הדף, התחילו להקליד את התוכן בתיבת הטקסט למטה (ראו את [[{{MediaWiki:Helppage}}|דף העזרה]] למידע נוסף).
אם הגעתם לכאן בטעות, לחצו על מקש ה־'''Back''' בדפדפן שלכם.",
'anontalkpagetext'                 => "----
'''זהו דף שיחה של משתמש אנונימי שעדיין לא יצר חשבון במערכת, או שהוא לא משתמש בו. כיוון שכך, אנו צריכים להשתמש בכתובת ה־IP כדי לזהותו. ייתכן שכתובת IP זו תייצג מספר משתמשים. אם אתם משתמשים אנונימיים ומרגישים שקיבלתם הודעות בלתי רלוונטיות, אנא [[Special:UserLogin|היכנסו לחשבון]] או [[Special:UserLogin/signup|הירשמו לאתר]] כדי להימנע מבלבול עתידי עם משתמשים אנונימיים נוספים.'''
----",
'noarticletext'                    => 'אין כרגע טקסט בדף זה.
באפשרותכם [[Special:Search/{{PAGENAME}}|לחפש את כותרת הדף]] בדפים אחרים,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} לחפש ביומנים הרלוונטיים],
או [{{fullurl:{{FULLPAGENAME}}|action=edit}} לערוך דף זה]</span>.',
'noarticletext-nopermission'       => 'אין כרגע טקסט בדף זה.
באפשרותכם [[Special:Search/{{PAGENAME}}|לחפש את כותרת הדף]] בדפים אחרים,
או <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} לחפש ביומנים הרלוונטיים].</span>',
'userpage-userdoesnotexist'        => 'חשבון המשתמש "$1" אינו רשום.
אנא בדקו אם ברצונכם ליצור/לערוך דף זה.',
'userpage-userdoesnotexist-view'   => 'חשבון המשתמש "$1" אינו רשום.',
'blocked-notice-logextract'        => 'משתמש זה חסום כרגע.
פעולת יומן החסימות האחרונה מוצגת להלן:',
'clearyourcache'                   => "'''הערה:''' לאחר השמירה, עליכם לנקות את זיכרון המטמון (Cache) של הדפדפן כדי להבחין בשינויים.
* '''מוזילה / פיירפוקס / ספארי:''' לחצו על מקש ה־Shift בעת לחיצתכם על '''העלה מחדש''' (Reload), או הקישו על ''Ctrl+F5'' או על ''Ctrl+R'' (או על ''Command+R'' במקינטוש).
* '''Konqueror:''' לחצו על '''העלה מחדש''' (Reload), או הקישו על ''F5''.
* '''אופרה''': נקו את המטמון ב'''כלים''' (Tools) > '''העדפות''' (Preferences).
* '''אינטרנט אקספלורר:''' לחצו על מקש ה־Ctrl בעת לחיצתכם על '''רענן''' (Refresh), או הקישו על ''Ctrl+F5''.",
'usercssyoucanpreview'             => "'''עצה:''' השתמשו בלחצן \"{{int:showpreview}}\" כדי לבחון את גליון ה־CSS החדש שלכם לפני השמירה.",
'userjsyoucanpreview'              => "'''עצה:''' השתמשו בלחצן \"{{int:showpreview}}\" כדי לבחון את סקריפט ה־JavaScript החדש שלכם לפני השמירה.",
'usercsspreview'                   => "'''זכרו שזו רק תצוגה מקדימה של גליון ה־CSS שלכם.'''
'''הוא טרם נשמר!'''",
'userjspreview'                    => "'''זכרו שזו רק בדיקה/תצוגה מקדימה של סקריפט ה־JavaScript שלכם.'''
'''הוא טרם נשמר!'''",
'sitecsspreview'                   => "'''זכרו שזו רק תצוגה מקדימה של גליון ה־CSS הזה.'''
'''הוא טרם נשמר!'''",
'sitejspreview'                    => "'''זכרו שזו רק תצוגה מקדימה של קוד ה־JavaScript הזה.'''
'''הוא טרם נשמר!'''",
'userinvalidcssjstitle'            => "'''אזהרה''': העיצוב \"\$1\" אינו קיים.
דפי .css ו־.js מותאמים אישית משתמשים בכותרת עם אותיות קטנות – למשל, {{ns:user}}:דוגמה/vector.css ולא {{ns:user}}:דוגמה/Vector.css.",
'updated'                          => '(מעודכן)',
'note'                             => "'''הערה:'''",
'previewnote'                      => "'''זכרו שזו רק תצוגה מקדימה.'''
השינויים שלכם טרם נשמרו!",
'previewconflict'                  => 'תצוגה מקדימה זו מציגה כיצד ייראה הטקסט בחלון העריכה העליון, אם תבחרו לשמור אותו.',
'session_fail_preview'             => "'''לא ניתן לבצע את עריכתכם עקב אובדן מידע הכניסה.'''
אנא נסו שוב.
אם זה לא עוזר, נסו [[Special:UserLogout|לצאת מהחשבון]] ולהיכנס אליו שנית.",
'session_fail_preview_html'        => "'''לא ניתן לבצע את עריכתם עקב אובדן מידע הכניסה.'''

כיוון שבאתר זה אפשרות השימוש ב־HTML מאופשרת, התצוגה המקדימה מוסתרת כדי למנוע התקפות JavaScript.

'''אם זהו ניסיון עריכה לגיטימי, אנא נסו שוב.'''
אם זה לא עוזר, נסו [[Special:UserLogout|לצאת מהחשבון]] ולהיכנס אליו שנית.",
'token_suffix_mismatch'            => "'''עריכתכם נדחתה כיוון שהדפדפן שלכם מחק את תווי הניקוד באסימון העריכה.'''
העריכה נדחתה כדי למנוע בעיות כאלה בטקסט של הדף.
לעיתים קורה דבר כזה בגלל שירות פרוקסי אנונימי פגום.",
'editing'                          => 'עריכת $1',
'editingsection'                   => 'עריכת $1 (פסקה)',
'editingcomment'                   => 'עריכת $1 (פסקה חדשה)',
'editconflict'                     => 'התנגשות עריכה: $1',
'explainconflict'                  => "משתמש אחר שינה את הדף מאז שהתחלתם לערוך אותו.
חלון העריכה העליון מכיל את הטקסט בדף כפי שהוא עתה.
השינויים שלכם מוצגים בחלון העריכה התחתון.
עליכם למזג את השינויים שלכם לתוך הטקסט הקיים.
'''רק''' הטקסט בחלון העריכה העליון יישמר כשתלחצו על \"{{int:savearticle}}\".",
'yourtext'                         => 'הטקסט שלך',
'storedversion'                    => 'גרסה שמורה',
'nonunicodebrowser'                => "'''אזהרה: הדפדפן שלכם אינו תואם לתקן יוניקוד.'''
כדי למנוע בעיות הנוצרות כתוצאה מכך ולאפשר לכם לערוך דפים בבטחה, תווים שאינם ב־ASCII יוצגו בתיבת העריכה כקודים הקסדצימליים.",
'editingold'                       => "'''אזהרה: אתם עורכים גרסה לא עדכנית של דף זה.'''
אם תשמרו את הדף, כל השינויים שנעשו מאז גרסה זו יאבדו.",
'yourdiff'                         => 'הבדלים',
'copyrightwarning'                 => "'''שימו לב:''' תרומתכם ל{{grammar:תחילית|{{SITENAME}}}} תפורסם תחת תנאי הרישיון $2 (ראו $1 לפרטים נוספים). אם אינכם רוצים שעבודתכם תהיה זמינה לעריכה על ידי אחרים, שתופץ לעיני כל, ושאחרים יוכלו להעתיק ממנה בציון המקור – אל תפרסמו אותה פה. כמו־כן, אתם מבטיחים לנו כי כתבתם את הטקסט הזה בעצמכם, או העתקתם אותו ממקור שאינו מוגן על ידי זכויות יוצרים. '''אל תעשו שימוש בחומר המוגן בזכויות יוצרים ללא רשות!'''",
'copyrightwarning2'                => "'''שימו לב:''' תורמים אחרים עשויים לערוך או אף להסיר את תרומתכם ל{{grammar:תחילית|{{SITENAME}}}}. אם אינכם רוצים שעבודתכם תהיה זמינה לעריכה על ידי אחרים – אל תפרסמו אותה פה. כמו־כן, אתם מבטיחים לנו כי כתבתם את הטקסט הזה בעצמכם, או העתקתם אותו ממקור שאינו מוגן על ידי זכויות יוצרים (ראו $1 לפרטים נוספים). '''אל תעשו שימוש בחומר המוגן בזכויות יוצרים ללא רשות!'''",
'longpageerror'                    => "'''שגיאה: הטקסט ששלחתם הוא באורך $1 קילובייטים, אך אסור לו להיות ארוך יותר מהמקסימום של $2 קילובייטים.'''
לא ניתן לשומרו.",
'readonlywarning'                  => "'''אזהרה: בסיס הנתונים ננעל לצורך תחזוקה. בזמן זה אי אפשר לשמור את הטקסט הערוך.'''
באפשרותכם להעתיק ולהדביק את הטקסט לתוך קובץ טקסט ולשמור אותו עד שתיגמר הנעילה.

מנהל המערכת שנעל את בסיס הנתונים סיפק את ההסבר הבא: $1",
'protectedpagewarning'             => "'''אזהרה: דף זה מוגן כך שרק מפעילי מערכת יכולים לערוך אותו.'''
פעולת היומן האחרונה מוצגת להלן:",
'semiprotectedpagewarning'         => "'''הערה:''' דף זה מוגן כך שרק משתמשים רשומים יכולים לערוך אותו.
פעולת היומן האחרונה מוצגת להלן:",
'cascadeprotectedwarning'          => "'''אזהרה:''' דף זה מוגן כך שרק מפעילי מערכת יכולים לערוך אותו, כיוון שהוא מוכלל {{PLURAL:$1|בדף הבא, שמופעלת עליו|בדפים הבאים, שמופעלת עליהם}} הגנה מדורגת:",
'titleprotectedwarning'            => "'''אזהרה: דף זה מוגן כך שדרושות [[Special:ListGroupRights|הרשאות מסוימות]] כדי ליצור אותו.'''
פעולת היומן האחרונה מוצגת להלן:",
'templatesused'                    => '{{PLURAL:$1|תבנית המופיעה|תבניות המופיעות}} בדף זה:',
'templatesusedpreview'             => '{{PLURAL:$1|תבנית המופיעה|תבניות המופיעות}} בתצוגה המקדימה הזו:',
'templatesusedsection'             => '{{PLURAL:$1|תבנית המופיעה|תבניות המופיעות}} בפסקה זו:',
'template-protected'               => '(מוגנת)',
'template-semiprotected'           => '(מוגנת חלקית)',
'hiddencategories'                 => 'דף זה כלול ב{{PLURAL:$1|קטגוריה מוסתרת אחת|־$1 קטגוריות מוסתרות}}:',
'edittools'                        => '<!-- הטקסט הנכתב כאן יוצג מתחת לטפסי עריכת דפים והעלאת קבצים, ולפיכך ניתן לכתוב להציג בו תווים קשים לכתיבה, קטעים מוכנים של טקסט ועוד. -->',
'nocreatetitle'                    => 'יצירת הדפים הוגבלה',
'nocreatetext'                     => 'אתר זה מגביל את האפשרות ליצור דפים חדשים. באפשרותכם לחזור אחורה ולערוך דף קיים, או [[Special:UserLogin|להיכנס לחשבון]].',
'nocreate-loggedin'                => 'אינכם מורשים ליצור דפים חדשים.',
'sectioneditnotsupported-title'    => 'עריכת פסקאות אינה נתמכת',
'sectioneditnotsupported-text'     => 'עריכת פסקאות אינה נתמכת בדף זה.',
'permissionserrors'                => 'שגיאות הרשאה',
'permissionserrorstext'            => 'אינכם מורשים לבצע פעולה זו, {{PLURAL:$1|מהסיבה הבאה|מהסיבות הבאות}}:',
'permissionserrorstext-withaction' => 'אינכם מורשים $2, {{PLURAL:$1|מהסיבה הבאה|מהסיבות הבאות}}:',
'recreate-moveddeleted-warn'       => "'''אזהרה: הנכם יוצרים דף חדש שנמחק בעבר.'''

אנא שיקלו אם יהיה זה נכון להמשיך לערוך את הדף.
יומני המחיקות וההעברות של הדף מוצגים להלן:",
'moveddeleted-notice'              => 'דף זה נמחק.
יומני המחיקות וההעברות של הדף מוצגים להלן.',
'log-fulllog'                      => 'הצגת היומן המלא',
'edit-hook-aborted'                => 'העריכה בוטלה על־ידי Hook.
לא ניתן הסבר לביטול.',
'edit-gone-missing'                => 'לא ניתן לעדכן את הדף.
נראה שהוא נמחק.',
'edit-conflict'                    => 'התנגשות עריכה.',
'edit-no-change'                   => 'המערכת התעלמה מעריכתכם, כיוון שלא נעשה שינוי בטקסט.',
'edit-already-exists'              => 'לא ניתן ליצור דף חדש.
הוא כבר קיים.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''אזהרה:''' דף זה כולל יותר מדי קריאות למשתנים הגוזלים משאבים.

{{PLURAL:$2|צריכה להיות פחות מקריאה אחת כזאת|צריכות להיות פחות מ־$2 קריאות כאלה}}, אך כרגע יש {{PLURAL:$1|קריאה אחת|$1 קריאות}}.",
'expensive-parserfunction-category'       => 'דפים עם יותר מדי קריאות למשתנים הגוזלים משאבים',
'post-expand-template-inclusion-warning'  => "'''אזהרה:''' התבניות המוכללות בדף זה גדולות מדי.
חלק מהתבניות לא יוכללו.",
'post-expand-template-inclusion-category' => 'דפים שבהם ההכללה גדולה מדי',
'post-expand-template-argument-warning'   => "'''אזהרה:''' דף זה כולל לפחות תבנית אחת שבה פרמטרים גדולים מדי.
פרמטרים אלה הושמטו.",
'post-expand-template-argument-category'  => 'דפים שבהם הושמטו פרמטרים של תבניות',
'parser-template-loop-warning'            => 'נמצאה תבנית הקוראת לעצמה: [[$1]]',
'parser-template-recursion-depth-warning' => 'עומק התבניות המוכללות זו בזו עבר את המקסימום האפשרי ($1)',
'language-converter-depth-warning'        => 'עומק ממיר השפה עבר את המקסימום האפשרי ($1)',

# "Undo" feature
'undo-success' => 'ניתן לבטל את העריכה. אנא בידקו את השוואת הגרסאות למטה כדי לוודא שזה מה שאתם רוצים לעשות, ואז שמרו את השינויים למטה כדי לבצע את ביטול העריכה.',
'undo-failure' => 'לא ניתן היה לבטל את העריכה עקב התנגשות עם עריכות מאוחרות יותר.',
'undo-norev'   => 'לא ניתן היה לבטל את העריכה כיוון שהיא אינה קיימת או שהיא נמחקה.',
'undo-summary' => 'ביטול גרסה $1 של [[Special:Contributions/$2|$2]] ([[User talk:$2|שיחה]])',

# Account creation failure
'cantcreateaccounttitle' => 'לא ניתן ליצור את החשבון',
'cantcreateaccount-text' => 'אפשרות יצירת החשבונות מכתובת ה־IP הזו (<b>$1</b>) נחסמה על ידי [[User:$3|$3]]. הסיבה שניתנה על ידי $3 היא "$2".',

# History pages
'viewpagelogs'           => 'הצגת יומנים עבור דף זה',
'nohistory'              => 'אין היסטוריית שינויים עבור דף זה.',
'currentrev'             => 'גרסה אחרונה',
'currentrev-asof'        => 'גרסה אחרונה מתאריך $1',
'revisionasof'           => 'גרסה מתאריך $1',
'revision-info'          => 'גרסה מתאריך $1 מאת $2',
'previousrevision'       => '→ הגרסה הקודמת',
'nextrevision'           => 'הגרסה הבאה ←',
'currentrevisionlink'    => 'הגרסה האחרונה',
'cur'                    => 'נוכ',
'next'                   => 'הבא',
'last'                   => 'אחרון',
'page_first'             => 'ראשון',
'page_last'              => 'אחרון',
'histlegend'             => "השוואת גרסאות: סמנו את תיבות האפשרויות של הגרסאות המיועדות להשוואה, והקישו על Enter או על הכפתור שלמעלה או למטה.<br />
מקרא: '''({{int:cur}})''' = הבדלים עם הגרסה האחרונה, '''({{int:last}})''' = הבדלים עם הגרסה הקודמת, '''{{int:minoreditletter}}''' = שינוי משני.",
'history-fieldset-title' => 'חיפוש בהיסטוריית הדף',
'history-show-deleted'   => 'רק מחוקות',
'histfirst'              => 'ראשונות',
'histlast'               => 'אחרונות',
'historysize'            => '({{PLURAL:$1|בית אחד|$1 בתים}})',
'historyempty'           => '(ריק)',

# Revision feed
'history-feed-title'          => 'היסטוריית גרסאות',
'history-feed-description'    => 'היסטוריית הגרסאות של הדף הזה בוויקי',
'history-feed-item-nocomment' => '$1 ב־$2',
'history-feed-empty'          => 'הדף המבוקש לא נמצא.
ייתכן שהוא נמחק, או ששמו שונה.
נסו [[Special:Search|לחפש]] אחר דפים רלוונטיים חדשים.',

# Revision deletion
'rev-deleted-comment'         => '(תקציר העריכה הוסר)',
'rev-deleted-user'            => '(שם המשתמש הוסר)',
'rev-deleted-event'           => '(פעולת היומן הוסרה)',
'rev-deleted-user-contribs'   => '[שם המשתמש או כתובת ה־IP הוסרו - העריכה הוסתרה מדף התרומות]',
'rev-deleted-text-permission' => "גרסת הדף הזו '''נמחקה'''.
ניתן למצוא פרטים על כך ב[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} יומן המחיקות].",
'rev-deleted-text-unhide'     => "גרסת הדף הזו '''נמחקה'''.
ניתן למצוא פרטים על כך ב[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} יומן המחיקות].
כיוון שאתם בעלי הרשאות מפעיל מערכת, באפשרותכם [$1 לצפות בגרסה] אם ברצונכם להמשיך.",
'rev-suppressed-text-unhide'  => "גרסת הדף הזו '''הוסתרה'''.
ניתן למצוא פרטים על כך ב[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} יומן ההסתרות].
כיוון שאתם בעלי הרשאות מפעיל מערכת, באפשרותכם [$1 לצפות בגרסה] אם ברצונכם להמשיך.",
'rev-deleted-text-view'       => "גרסת הדף הזו '''נמחקה'''.
כיוון שאתם בעלי הרשאות מפעיל מערכת, באפשרותכם לצפות בגרסה; ניתן למצוא פרטים על כך ב[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} יומן המחיקות].",
'rev-suppressed-text-view'    => "גרסת הדף הזו '''הוסתרה'''.
כיוון שאתם בעלי הרשאות מפעיל מערכת, באפשרותכם לצפות בגרסה; ניתן למצוא פרטים על כך ב[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} יומן ההסתרות].",
'rev-deleted-no-diff'         => "אינכם יכולים לצפות בהבדלים בין הגרסאות שציינתם משום שאחת מהן '''נמחקה'''.
ניתן למצוא פרטים על כך ב[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} יומן המחיקות].",
'rev-suppressed-no-diff'      => "אינכם יכולים לצפות בהבדלים בין הגרסאות שציינתם משום שאחת מהן '''נמחקה'''.",
'rev-deleted-unhide-diff'     => "אחת מהגרסאות שציינתם '''נמחקה'''.
ניתן למצוא פרטים על כך ב[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} יומן המחיקות].
כיוון שאתם בעלי הרשאות מפעיל מערכת, באפשרותכם [$1 לצפות בהבדלים בין הגרסאות] אם ברצונכם להמשיך.",
'rev-suppressed-unhide-diff'  => "אחת מהגרסאות שציינתם '''הוסתרה'''.
ניתן למצוא פרטים על כך ב[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} יומן ההסתרות].
כיוון שאתם בעלי הרשאות מפעיל מערכת, באפשרותכם [$1 לצפות בהבדלים בין הגרסאות] אם ברצונכם להמשיך.",
'rev-deleted-diff-view'       => "אחת מהגרסאות שציינתם '''נמחקה'''.
כיוון שאתם בעלי הרשאות מפעיל מערכת, באפשרותכם לצפות בהבדלים בין הגרסאות; ניתן למצוא פרטים על כך ב[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} יומן המחיקות].",
'rev-suppressed-diff-view'    => "אחת מהגרסאות שציינתם '''הוסתרה'''.
כיוון שאתם בעלי הרשאות מפעיל מערכת, באפשרותכם לצפות בהבדלים בין הגרסאות; ניתן למצוא פרטים על כך ב[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} יומן ההסתרות].",
'rev-delundel'                => 'הצגה/הסתרה',
'rev-showdeleted'             => 'הצגה',
'revisiondelete'              => 'מחיקת ושחזור גרסאות',
'revdelete-nooldid-title'     => 'גרסת מטרה בלתי תקינה',
'revdelete-nooldid-text'      => 'הגרסה או הגרסאות עליהן תבוצע פעולה זו אינן תקינות. ייתכן שלא ציינתם אותן, ייתכן שהגרסה אינה קיימת, וייתכן שאתם מנסים להסתיר את הגרסה הנוכחית.',
'revdelete-nologtype-title'   => 'לא נבחר סוג יומן',
'revdelete-nologtype-text'    => 'לא ציינתם את סוג היומן שעליו תבוצע הפעולה.',
'revdelete-nologid-title'     => 'רישום יומן בלתי תקין',
'revdelete-nologid-text'      => 'או שלא ציינתם את האירוע או האירועים ביומן שעליהם תבוצע הפעולה, או שרישום היומן שציינתם אינו קיים.',
'revdelete-no-file'           => 'הקובץ שציינתם אינו קיים.',
'revdelete-show-file-confirm' => 'האם אתם בטוחים שברצונכם לצפות בגרסה המחוקה של הקובץ "<nowiki>$1</nowiki>" מתאריך $3, $2?',
'revdelete-show-file-submit'  => 'כן',
'revdelete-selected'          => "'''{{PLURAL:$2|הגרסה שנבחרה|הגרסאות שנבחרו}} של [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|פעולת היומנים שנבחרה|פעולות היומנים שנבחרו}}:'''",
'revdelete-text'              => "'''גרסאות ופעולות יומנים שנמחקו עדיין תופענה בהיסטוריית הדף ובדפי היומנים, אך חלקים מהתוכן שלהן לא יהיה זמין לציבור.'''
מפעילי מערכת אחרים באתר עדיין יוכלו לגשת לתוכן הנסתר ויוכלו לשחזר אותו שוב דרך הממשק הזה, אלא אם כן תוגדרנה הגבלות נוספות.",
'revdelete-confirm'           => 'אנא אשרו שזה אכן מה שאתם מתכוונים לעשות, שאתם מבינים את התוצאות של מעשה כזה, ושהמעשה מבוצע בהתאם ל[[{{MediaWiki:Policy-url}}|נהלי האתר]].',
'revdelete-suppress-text'     => "יש להשתמש בהסתרה מלאה '''אך ורק''' במקרים הבאים:
* מידע שעלול להיות לשון הרע
* חשיפת מידע אישי
*: '''כתובות בתים ומספרי טלפון, מספרי ביטוח לאומי, וכדומה'''",
'revdelete-legend'            => 'הגדרת הגבלות התצוגה',
'revdelete-hide-text'         => 'הסתרת תוכן הגרסה',
'revdelete-hide-image'        => 'הסתרת תוכן הקובץ',
'revdelete-hide-name'         => 'הסתרת הפעולה ודף היעד',
'revdelete-hide-comment'      => 'הסתרת תקציר העריכה',
'revdelete-hide-user'         => 'הסתרת שם המשתמש או כתובת ה־IP של העורך',
'revdelete-hide-restricted'   => 'הסתרת המידע גם ממפעילי המערכת',
'revdelete-radio-same'        => '(ללא שינוי)',
'revdelete-radio-set'         => 'כן',
'revdelete-radio-unset'       => 'לא',
'revdelete-suppress'          => 'הסתרת המידע גם ממפעילי המערכת',
'revdelete-unsuppress'        => 'הסרת הגבלות בגרסאות המשוחזרות',
'revdelete-log'               => 'סיבה:',
'revdelete-submit'            => 'ביצוע על {{PLURAL:$1|הגרסה שנבחרה|הגרסאות שנבחרו}}',
'revdelete-logentry'          => 'שינה את הסתרת הגרסה של "[[$1]]"',
'logdelete-logentry'          => 'שינה את הסתרת פעולת היומן של "[[$1]]"',
'revdelete-success'           => "'''מצב הסתרת הגרסה עודכן בהצלחה.'''",
'revdelete-failure'           => "'''לא ניתן היה לעדכן את מצב הסתרת הגרסה:'''
$1",
'logdelete-success'           => "'''הסתרת פעולת היומן הושלמה בהצלחה.'''",
'logdelete-failure'           => "'''לא ניתן היה לבצע את הסתרת פעולת היומן:'''
$1",
'revdel-restore'              => 'שינוי ההצגה',
'revdel-restore-deleted'      => 'גרסאות מחוקות',
'revdel-restore-visible'      => 'גרסאות גלויות',
'pagehist'                    => 'היסטוריית הדף',
'deletedhist'                 => 'הגרסאות המחוקות',
'revdelete-content'           => 'התוכן',
'revdelete-summary'           => 'תקציר העריכה',
'revdelete-uname'             => 'שם המשתמש',
'revdelete-restricted'        => 'נוספו הגבלות למפעילי מערכת',
'revdelete-unrestricted'      => 'הוסרו הגבלות ממפעילי מערכת',
'revdelete-hid'               => 'הסתיר את $1',
'revdelete-unhid'             => 'ביטל את הסתרת $1',
'revdelete-log-message'       => '$1 עבור {{PLURAL:$2|גרסה אחת|$2 גרסאות}}',
'logdelete-log-message'       => '$1 עבור {{PLURAL:$2|אירוע אחד|$2 אירועים}}',
'revdelete-hide-current'      => 'שגיאה בהסתרת הפריט מתאריך $2, $1: זו הגרסה הנוכחית.
לא ניתן להסתיר אותה.',
'revdelete-show-no-access'    => 'שגיאה בהצגת הפריט מתאריך $2, $1: פריט זה סומן כ"מוגבל".
אין לכם גישה אליו.',
'revdelete-modify-no-access'  => 'שגיאה בשינוי הפריט מתאריך $2, $1: פריט זה סומן כ"מוגבל".
אין לכם גישה אליו.',
'revdelete-modify-missing'    => 'שגיאה בשינוי פריט מספר $1: הוא אינו נמצא בבסיס הנתונים!',
'revdelete-no-change'         => "'''אזהרה:''' לפריט מתאריך $2, $1 כבר יש את הגדרות ההצגה הנדרשות.",
'revdelete-concurrent-change' => 'שגיאה בשינוי הפריט מתאריך $2, $1: נראה שמצבו שונה על ידי מישהו אחר בזמן שאתם ניסיתם לשנות אותו.
אנא בדקו ביומנים.',
'revdelete-only-restricted'   => 'שגיאה בהסתרת הפריט מתאריך $2, $1: אין באפשרותכם להסתיר פרטים מצפיית מפעילי מערכת בלי לבחור גם באחת מאפשרויות ההסתרה האחרות.',
'revdelete-reason-dropdown'   => '* סיבות מחיקה נפוצות
** הפרת זכויות יוצרים
** חשיפת מידע אישי
** מידע שעלול להיות לשון הרע',
'revdelete-otherreason'       => 'סיבה אחרת/נוספת:',
'revdelete-reasonotherlist'   => 'סיבה אחרת',
'revdelete-edit-reasonlist'   => 'עריכת סיבות המחיקה',
'revdelete-offender'          => 'מחבר הגרסה:',

# Suppression log
'suppressionlog'     => 'יומן הסתרות',
'suppressionlogtext' => 'להלן רשימת המחיקות והחסימות הכוללות תוכן המוסתר ממפעילי המערכת. ראו את  [[Special:IPBlockList|רשימת המשתמשים החסומים]] לרשימת החסימות הפעילות כעת.',

# Revision move
'moverevlogentry'              => 'העביר {{PLURAL:$3|גרסה אחת|$3 גרסאות}} מ$1 ל$2',
'revisionmove'                 => 'העברת גרסאות מהדף "$1"',
'revisionmove-backlink'        => '→ $1',
'revmove-explain'              => 'הגרסאות הבאות יועברו מהדף $1 לדף היעד שצוין. אם דף היעד לא קיים, הוא ייווצר. אחרת, גרסאות אלה ימוזגו לתוך היסטוריית הדף.',
'revmove-legend'               => 'הגדרת דף היעד והתקציר',
'revmove-submit'               => 'העברת הגרסאות לדף שנבחר',
'revisionmoveselectedversions' => 'העברת הגרסאות שנבחרו',
'revmove-reasonfield'          => 'סיבה:',
'revmove-titlefield'           => 'דף היעד:',
'revmove-badparam-title'       => 'פרמטרים שגויים',
'revmove-badparam'             => 'בקשתכם כוללת פרמטרים בלתי חוקיים או בלתי מספיקים.
אנא חזרו לדף הקודם ונסו שוב.',
'revmove-norevisions-title'    => 'גרסת יעד בלתי תקינה',
'revmove-norevisions'          => 'לא ציינתם גרסת יעד אחת או יותר שיש לבצע עליה פעולה זו, או שהגרסה שציינתם אינה קיימת.',
'revmove-nullmove-title'       => 'כותרת שגויה',
'revmove-nullmove'             => 'דף המקור אינו יכול להיות זהה לדף היעד.
אנא חזרו לדף הקודם והקלידו שם דף שונה מ"$1".',
'revmove-success-existing'     => '{{PLURAL:$1|גרסה אחת מ[[$2]] הועברה|$1 גרסאות מ[[$2]] הועברו}} לדף הקיים [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|גרסה אחת מ[[$2]] הועברה|$1 גרסאות מ[[$2]] הועברו}} לדף החדש [[$3]].',

# History merging
'mergehistory'                     => 'מיזוג גרסאות של דפים',
'mergehistory-header'              => "דף זה מאפשר לכם למזג גרסאות מהיסטוריית הדף של דף מקור לתוך דף חדש יותר.
אנא ודאו ששינוי זה לא יפגע בהמשכיות השינויים בדף הישן.

'''לפחות גרסה אחת של דף המקור חייבת להישאר בו.'''",
'mergehistory-box'                 => 'מיזוג גרסאות של שני דפים:',
'mergehistory-from'                => 'דף המקור:',
'mergehistory-into'                => 'דף היעד:',
'mergehistory-list'                => 'היסטוריית עריכות ברת מיזוג',
'mergehistory-merge'               => 'ניתן למזג את הגרסאות הבאות של [[:$1]] לתוך [[:$2]]. אנא השתמשו בלחצני האפשרות כדי לבחור זמן שרק גרסאות שנוצרו בו ולפניו ימוזגו. שימוש בקישורי הניווט יאפס עמודה זו.',
'mergehistory-go'                  => 'הצגת עריכות ברות מיזוג',
'mergehistory-submit'              => 'מיזוג',
'mergehistory-empty'               => 'אין גרסאות למיזוג.',
'mergehistory-success'             => '{{PLURAL:$3|גרסה אחת|$3 גרסאות}} של [[:$1]] מוזגו בהצלחה לתוך [[:$2]].',
'mergehistory-fail'                => 'לא ניתן לבצע את מיזוג הגרסאות, אנא בדקו שנית את הגדרות הדף והזמן.',
'mergehistory-no-source'           => 'דף המקור $1 אינו קיים.',
'mergehistory-no-destination'      => 'דף היעד $1 אינו קיים.',
'mergehistory-invalid-source'      => 'דף המקור חייב להיות בעל כותרת תקינה.',
'mergehistory-invalid-destination' => 'דף היעד חייב להיות בעל כותרת תקינה.',
'mergehistory-autocomment'         => 'מיזג את [[:$1]] לתוך [[:$2]]',
'mergehistory-comment'             => 'מיזג את [[:$1]] לתוך [[:$2]]: $3',
'mergehistory-same-destination'    => 'דפי המקור והיעד זהים',
'mergehistory-reason'              => 'סיבה:',

# Merge log
'mergelog'           => 'יומן מיזוגים',
'pagemerge-logentry' => 'מיזג את [[$1]] לתוך [[$2]] (גרסאות עד $3)',
'revertmerge'        => 'ביטול המיזוג',
'mergelogpagetext'   => 'זוהי רשימה של המיזוגים האחרונים של גרסאות מדף אחד לתוך דף שני.',

# Diffs
'history-title'            => 'היסטוריית הגרסאות של $1',
'difference'               => '(הבדלים בין גרסאות)',
'difference-multipage'     => '(הבדלים בין דפים)',
'lineno'                   => 'שורה $1:',
'compareselectedversions'  => 'השוואת הגרסאות שנבחרו',
'showhideselectedversions' => 'הצגת/הסתרת הגרסאות שנבחרו',
'editundo'                 => 'ביטול',
'diff-multi'               => '({{PLURAL:$1|גרסת ביניים אחת|$1 גרסאות ביניים}} של {{PLURAL:$2|משתמש אחד|$2 משתמשים}} {{PLURAL:$1|אינה מוצגת|אינן מוצגות}})',
'diff-multi-manyusers'     => '({{PLURAL:$1|גרסת ביניים אחת|$1 גרסאות ביניים}} של יותר מ{{PLURAL:$2|משתמש אחד|־$2 משתמשים}} אינן מוצגות)',

# Search results
'searchresults'                    => 'תוצאות החיפוש',
'searchresults-title'              => 'תוצאות החיפוש "$1"',
'searchresulttext'                 => 'למידע נוסף על חיפוש ב{{grammar:תחילית|{{SITENAME}}}}, עיינו ב[[Project:עזרה|דפי העזרה]].',
'searchsubtitle'                   => 'לחיפוש המונח \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|לכל הדפים המתחילים ב"$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|לכל הדפים המקשרים ל"$1"]])',
'searchsubtitleinvalid'            => "לחיפוש המונח '''$1'''",
'toomanymatches'                   => 'יותר מדי תוצאות נמצאו, אנא נסו מילות חיפוש אחרות',
'titlematches'                     => 'כותרות דפים תואמות',
'notitlematches'                   => 'אין כותרות דפים תואמות',
'textmatches'                      => 'דפים עם תוכן תואם',
'notextmatches'                    => 'אין דפים עם תוכן תואם',
'prevn'                            => '{{PLURAL:$1|הקודם|$1 הקודמים}}',
'nextn'                            => '{{PLURAL:$1|הבא|$1 הבאים}}',
'prevn-title'                      => '{{PLURAL:$1|התוצאה הקודמת|$1 התוצאות הקודמות}}',
'nextn-title'                      => '{{PLURAL:$1|התוצאה הבאה|$1 התוצאות הבאות}}',
'shown-title'                      => 'הצגת {{PLURAL:$1|תוצאה אחת|$1 תוצאות}} בדף',
'viewprevnext'                     => 'צפייה ב - ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'אפשרויות חיפוש',
'searchmenu-exists'                => "'''קיים דף בשם \"[[:\$1]]\" באתר זה.'''",
'searchmenu-new'                   => "'''יצירת הדף \"[[:\$1]]\" באתר זה.'''",
'searchhelp-url'                   => 'Help:תפריט ראשי',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|מציאת דפים עם קידומת זו]]',
'searchprofile-articles'           => 'דפי תוכן',
'searchprofile-project'            => 'עזרה ודפי המיזם',
'searchprofile-images'             => 'מולטימדיה',
'searchprofile-everything'         => 'הכול',
'searchprofile-advanced'           => 'מתקדם',
'searchprofile-articles-tooltip'   => 'חיפוש $1',
'searchprofile-project-tooltip'    => 'חיפוש $1',
'searchprofile-images-tooltip'     => 'חיפוש קבצים',
'searchprofile-everything-tooltip' => 'חיפוש בכל התוכן (למעט דפי השיחה)',
'searchprofile-advanced-tooltip'   => 'חיפוש במרחבי שם מותאמים אישית',
'search-result-size'               => '$1 ({{PLURAL:$2|מילה אחת|$2 מילים}})',
'search-result-category-size'      => '{{PLURAL:$1|חבר אחד|$1 חברים}} ({{PLURAL:$2|קטגוריית משנה אחת|$2 קטגוריות משנה}}, {{PLURAL:$3|קובץ אחד|$3 קבצים}})',
'search-result-score'              => 'רלוונטיות: $1%',
'search-redirect'                  => '(הפניה $1)',
'search-section'                   => '(פסקה $1)',
'search-suggest'                   => 'האם התכוונת ל: $1',
'search-interwiki-caption'         => 'מיזמי אחות',
'search-interwiki-default'         => '$1 תוצאות:',
'search-interwiki-more'            => '(עוד)',
'search-mwsuggest-enabled'         => 'עם הצעות',
'search-mwsuggest-disabled'        => 'ללא הצעות',
'search-relatedarticle'            => 'קשור',
'mwsuggest-disable'                => 'ביטול הצעות AJAX',
'searcheverything-enable'          => 'חיפוש בכל מרחבי השם',
'searchrelated'                    => 'קשור',
'searchall'                        => 'הכול',
'showingresults'                   => "הצגת עד {{PLURAL:$1|תוצאה '''אחת'''|'''$1''' תוצאות}} החל ממספר #'''$2''':",
'showingresultsnum'                => "הצגת {{PLURAL:$3|תוצאה '''אחת'''|'''$3''' תוצאות}} החל ממספר #'''$2''':",
'showingresultsheader'             => "{{PLURAL:$5|תוצאה '''$1''' מתוך '''$3'''|תוצאות '''$1 - $2''' מתוך '''$3'''}} עבור '''$4'''",
'nonefound'                        => "'''הערה''': כברירת מחדל, החיפוש מבוצע במספר מרחבי שם בלבד. באפשרותכם לכתוב '''all:''' לפני מונח החיפוש כדי לחפש בכל הדפים (כולל דפי שיחה, תבניות, ועוד), או לכתוב לפני מונח החיפוש את מרחב השם שאתם מעוניינים בו.",
'search-nonefound'                 => 'לא נמצאו תוצאות המתאימות לחיפוש.',
'powersearch'                      => 'חיפוש מתקדם',
'powersearch-legend'               => 'חיפוש מתקדם',
'powersearch-ns'                   => 'חיפוש במרחבי השם:',
'powersearch-redir'                => 'הצגת דפי הפניה',
'powersearch-field'                => 'חיפוש',
'powersearch-togglelabel'          => 'בחירה:',
'powersearch-toggleall'            => 'הכול',
'powersearch-togglenone'           => 'אף אחד',
'search-external'                  => 'חיפוש חיצוני',
'searchdisabled'                   => 'לצערנו, עקב עומס על המערכת, לא ניתן לחפש כעת בטקסט המלא של הדפים. באפשרותכם להשתמש בינתיים בגוגל, אך שימו לב שייתכן שהוא אינו מעודכן.',

# Quickbar
'qbsettings'               => 'הגדרות סרגל כלים',
'qbsettings-none'          => 'ללא',
'qbsettings-fixedleft'     => 'קבוע משמאל',
'qbsettings-fixedright'    => 'קבוע מימין',
'qbsettings-floatingleft'  => 'צף משמאל',
'qbsettings-floatingright' => 'צף מימין',

# Preferences page
'preferences'                   => 'העדפות',
'mypreferences'                 => 'ההעדפות שלי',
'prefs-edits'                   => 'מספר עריכות:',
'prefsnologin'                  => 'לא נרשמת באתר',
'prefsnologintext'              => 'עליכם <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} להיכנס לחשבון]</span> כדי לשנות העדפות משתמש.',
'changepassword'                => 'שינוי סיסמה',
'prefs-skin'                    => 'עיצוב',
'skin-preview'                  => 'תצוגה מקדימה',
'prefs-math'                    => 'נוסחאות מתמטיות',
'datedefault'                   => 'ברירת המחדל',
'prefs-datetime'                => 'תאריך ושעה',
'prefs-personal'                => 'פרטי המשתמש',
'prefs-rc'                      => 'שינויים אחרונים',
'prefs-watchlist'               => 'רשימת המעקב',
'prefs-watchlist-days'          => 'מספר הימים המירבי שיוצגו ברשימת המעקב:',
'prefs-watchlist-days-max'      => 'לכל היותר 7 ימים',
'prefs-watchlist-edits'         => 'מספר העריכות המירבי שיוצגו ברשימת המעקב המורחבת:',
'prefs-watchlist-edits-max'     => 'לכל היותר 1000',
'prefs-watchlist-token'         => 'אסימון לרשימת המעקב:',
'prefs-misc'                    => 'שונות',
'prefs-resetpass'               => 'שינוי סיסמה',
'prefs-email'                   => 'אפשרויות דוא"ל',
'prefs-rendering'               => 'מראה',
'saveprefs'                     => 'שמירת העדפות',
'resetprefs'                    => 'מחיקת שינויים שלא נשמרו',
'restoreprefs'                  => 'חזרה להגדרות ברירת המחדל',
'prefs-editing'                 => 'עריכה',
'prefs-edit-boxsize'            => 'גודל חלון העריכה.',
'rows'                          => 'שורות:',
'columns'                       => 'עמודות:',
'searchresultshead'             => 'חיפוש',
'resultsperpage'                => 'מספר תוצאות בעמוד:',
'contextlines'                  => 'שורות לכל תוצאה:',
'contextchars'                  => 'מספר תווי קונטקסט בשורה:',
'stub-threshold'                => 'סף לעיצוב <a href="#" class="stub">קישורים</a> לקצרמרים (בתים):',
'stub-threshold-disabled'       => 'מבוטל',
'recentchangesdays'             => 'מספר הימים שיוצגו בדף השינויים האחרונים:',
'recentchangesdays-max'         => 'לכל היותר {{PLURAL:$1|יום אחד|$1 ימים}}',
'recentchangescount'            => 'מספר העריכות שיוצגו כברירת מחדל:',
'prefs-help-recentchangescount' => 'כולל שינויים אחרונים, היסטוריית הדף ויומנים.',
'prefs-help-watchlist-token'    => 'מילוי השדה הזה במפתח סודי ייצור הזנת RSS עבור רשימת המעקב שלכם.
כל מי שיודע את המפתח שבשדה זה יוכל לקרוא את רשימת המעקב שלכם, לכן עליכם לבחור ערך בטוח.
תוכלו להשתמש בערך הבא, שנוצר באופן אקראי: $1',
'savedprefs'                    => 'העדפותיך נשמרו.',
'timezonelegend'                => 'אזור זמן:',
'localtime'                     => 'זמן מקומי:',
'timezoneuseserverdefault'      => 'ברירת המחדל של השרת',
'timezoneuseoffset'             => 'אחר (נא ציינו את ההפרש)',
'timezoneoffset'                => 'הפרש¹:',
'servertime'                    => 'השעה הנוכחית בשרת:',
'guesstimezone'                 => 'קבלה מהדפדפן',
'timezoneregion-africa'         => 'אפריקה',
'timezoneregion-america'        => 'אמריקה',
'timezoneregion-antarctica'     => 'אנטארקטיקה',
'timezoneregion-arctic'         => 'האזור הארקטי',
'timezoneregion-asia'           => 'אסיה',
'timezoneregion-atlantic'       => 'האוקיינוס האטלנטי',
'timezoneregion-australia'      => 'אוסטרליה',
'timezoneregion-europe'         => 'אירופה',
'timezoneregion-indian'         => 'האוקיינוס ההודי',
'timezoneregion-pacific'        => 'האוקיינוס השקט',
'allowemail'                    => 'קבלת דוא"ל ממשתמשים אחרים',
'prefs-searchoptions'           => 'אפשרויות חיפוש',
'prefs-namespaces'              => 'מרחבי שם',
'defaultns'                     => 'אחרת, החיפוש יתבצע במרחבי השם הבאים:',
'default'                       => 'ברירת מחדל',
'prefs-files'                   => 'קבצים',
'prefs-custom-css'              => 'קובץ CSS מותאם אישית',
'prefs-custom-js'               => 'קובץ JavaScript מותאם אישית',
'prefs-common-css-js'           => 'קובצי CSS/JavaScript משותפים לכל העיצובים:',
'prefs-reset-intro'             => 'באפשרותכם להשתמש בדף זה כדי להחזיר את ההעדפות שלכם להגדרות ברירת המחדל של האתר.
לא ניתן לבטל פעולה זו.',
'prefs-emailconfirm-label'      => 'אימות כתובת דוא"ל:',
'prefs-textboxsize'             => 'גודל חלון העריכה',
'youremail'                     => 'דואר אלקטרוני:',
'username'                      => 'שם משתמש:',
'uid'                           => 'מספר סידורי:',
'prefs-memberingroups'          => 'חבר ב{{PLURAL:$1|קבוצה|קבוצות}}:',
'prefs-registration'            => 'זמן ההרשמה:',
'yourrealname'                  => 'שם אמיתי:',
'yourlanguage'                  => 'שפת הממשק:',
'yourvariant'                   => 'גרסה:',
'yournick'                      => 'חתימה:',
'prefs-help-signature'          => 'על הודעות בדפי שיחה יש לחתום באמצעות הטקסט "<nowiki>~~~~</nowiki>", שיומר לחתימה שלכם ואחריה תאריך ושעה.',
'badsig'                        => 'חתימה מסוגננת שגויה.
אנא בדקו את תגיות ה־HTML.',
'badsiglength'                  => 'חתימתכם ארוכה מדי.
היא חייבת לא להיות ארוכה מ{{PLURAL:$1|תו אחד|־$1 תווים}}.',
'yourgender'                    => 'מין:',
'gender-unknown'                => 'לא צוין',
'gender-male'                   => 'זכר',
'gender-female'                 => 'נקבה',
'prefs-help-gender'             => 'אופציונאלי: ניתן לכתוב מידע זה כדי שהתוכנה תתייחס אליכם באופן נכון מבחינה מגדרית. מידע זה יהיה ציבורי.',
'email'                         => 'דוא"ל',
'prefs-help-realname'           => 'השם האמיתי הוא אופציונאלי.
אם תבחרו לספקו, הוא ישמש לייחוס עבודתכם אליכם.',
'prefs-help-email'              => 'כתובת דואר אלקטרוני היא אופציונאלית, אך היא דרושה לאיפוס הסיסמה במקרה שתשכחו את הסיסמה.
תוכלו גם לבחור לאפשר לאחרים לשלוח לכם מסר דרך דף המשתמש או דף השיחה שלכם ללא צורך לחשוף את כתובתכם.',
'prefs-help-email-required'     => 'כתובת דואר אלקטרוני נדרשת לכתיבה באתר.',
'prefs-info'                    => 'מידע בסיסי',
'prefs-i18n'                    => 'בינאום',
'prefs-signature'               => 'חתימה',
'prefs-dateformat'              => 'מבנה תאריך',
'prefs-timeoffset'              => 'הפרש זמנים',
'prefs-advancedediting'         => 'אפשרויות מתקדמות',
'prefs-advancedrc'              => 'אפשרויות מתקדמות',
'prefs-advancedrendering'       => 'אפשרויות מתקדמות',
'prefs-advancedsearchoptions'   => 'אפשרויות מתקדמות',
'prefs-advancedwatchlist'       => 'אפשרויות מתקדמות',
'prefs-displayrc'               => 'אפשרויות תצוגה',
'prefs-displaysearchoptions'    => 'אפשרויות תצוגה',
'prefs-displaywatchlist'        => 'אפשרויות תצוגה',
'prefs-diffs'                   => 'הבדלים בין גרסאות',

# User rights
'userrights'                   => 'ניהול הרשאות משתמש',
'userrights-lookup-user'       => 'ניהול קבוצות משתמש',
'userrights-user-editname'     => 'שם משתמש:',
'editusergroup'                => 'עריכת קבוצות משתמשים',
'editinguser'                  => "שינוי הרשאות המשתמש של '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'עריכת קבוצות משתמש',
'saveusergroups'               => 'שמירת קבוצות משתמש',
'userrights-groupsmember'      => 'חבר בקבוצות:',
'userrights-groupsmember-auto' => 'חבר אוטומטית בקבוצות:',
'userrights-groups-help'       => 'באפשרותכם לשנות את הקבוצות שמשתמש זה חבר בהן:
* תיבה מסומנת פירושה שהמשתמש חבר בקבוצה.
* תיבה בלתי מסומנת פירושה שהמשתמש אינו חבר בקבוצה.
* סימון * פירושו שלא תוכלו להסיר משתמש מהקבוצה מרגע שהוספתם אותו אליה, או להפך.',
'userrights-reason'            => 'סיבה:',
'userrights-no-interwiki'      => 'אין לכם הרשאה לערוך הרשאות משתמש באתרים אחרים.',
'userrights-nodatabase'        => 'בסיס הנתונים $1 אינו קיים או אינו מקומי.',
'userrights-nologin'           => 'עליכם [[Special:UserLogin|להיכנס לחשבון]] עם הרשאות מתאימות כדי לשנות הרשאות של משתמשים.',
'userrights-notallowed'        => 'לחשבון המשתמש שלכם אין הרשאה לשנות הרשאות של משתמשים.',
'userrights-changeable-col'    => 'קבוצות שבאפשרותכם לשנות',
'userrights-unchangeable-col'  => 'קבוצות שאין באפשרותכם לשנות',

# Groups
'group'               => 'קבוצה:',
'group-user'          => 'משתמשים',
'group-autoconfirmed' => 'משתמשים ותיקים',
'group-bot'           => 'בוטים',
'group-sysop'         => 'מפעילי מערכת',
'group-bureaucrat'    => 'ביורוקרטים',
'group-suppress'      => 'מסתירים',
'group-all'           => '(הכול)',

'group-user-member'          => 'משתמש',
'group-autoconfirmed-member' => 'משתמש ותיק',
'group-bot-member'           => 'בוט',
'group-sysop-member'         => 'מפעיל מערכת',
'group-bureaucrat-member'    => 'ביורוקרט',
'group-suppress-member'      => 'מסתיר',

'grouppage-user'          => '{{ns:project}}:משתמש רשום',
'grouppage-autoconfirmed' => '{{ns:project}}:משתמש ותיק',
'grouppage-bot'           => '{{ns:project}}:בוט',
'grouppage-sysop'         => '{{ns:project}}:מפעיל מערכת',
'grouppage-bureaucrat'    => '{{ns:project}}:ביורוקרט',
'grouppage-suppress'      => '{{ns:project}}:מסתיר',

# Rights
'right-read'                  => 'קריאת דפים',
'right-edit'                  => 'עריכת דפים',
'right-createpage'            => 'יצירת דפים שאינם דפי שיחה',
'right-createtalk'            => 'יצירת דפי שיחה',
'right-createaccount'         => 'יצירת חשבונות משתמש חדשים',
'right-minoredit'             => 'סימון עריכות כמשניות',
'right-move'                  => 'העברת דפים',
'right-move-subpages'         => 'העברת דפים עם דפי המשנה שלהם',
'right-move-rootuserpages'    => 'העברת דפי משתמש שאינם דפי משנה',
'right-movefile'              => 'העברת קבצים',
'right-suppressredirect'      => 'הימנעות מיצירת הפניות מדפי המקור בעת העברת דפים',
'right-upload'                => 'העלאת קבצים',
'right-reupload'              => 'דריסת קבצים קיימים',
'right-reupload-own'          => 'דריסת קבצים קיימים שהועלו על ידי אותו המשתמש',
'right-reupload-shared'       => 'דריסה מקומית של קבצים מאתר קובצי המדיה המשותף',
'right-upload_by_url'         => 'העלאת קובץ מכתובת אינטרנט',
'right-purge'                 => 'רענון זכרון המטמון של האתר לדף מסוים ללא דף אישור',
'right-autoconfirmed'         => 'עריכת דפים מוגנים חלקית',
'right-bot'                   => 'טיפול בעריכות כאוטומטיות',
'right-nominornewtalk'        => 'ביטול הודעת ההודעות החדשות בעת עריכה משנית בדפי שיחה',
'right-apihighlimits'         => 'שימוש ב־API עם פחות הגבלות',
'right-writeapi'              => 'שימוש ב־API לשינוי דפים',
'right-delete'                => 'מחיקת דפים',
'right-bigdelete'             => 'מחיקת דפים עם היסטוריית דף ארוכה',
'right-deleterevision'        => 'מחיקת גרסאות מסוימות של דפים',
'right-deletedhistory'        => 'צפייה בגרסאות מחוקות ללא הטקסט השייך להן',
'right-deletedtext'           => 'צפייה בטקסט מחוק ובהבדלים בין גרסאות מחוקות',
'right-browsearchive'         => 'חיפוש דפים מחוקים',
'right-undelete'              => 'שחזור דף מחוק',
'right-suppressrevision'      => 'בדיקה ושחזור של גרסאות המוסתרות ממפעילי המערכת',
'right-suppressionlog'        => 'צפייה ביומנים פרטיים',
'right-block'                 => 'חסימת משתמשים אחרים מעריכה',
'right-blockemail'            => 'חסימת משתמש משליחת דואר אלקטרוני',
'right-hideuser'              => 'חסימת שם משתמש תוך הסתרתו מהציבור',
'right-ipblock-exempt'        => 'עקיפת חסימות של כתובת IP, חסימות אוטומטיות וחסימות טווח',
'right-proxyunbannable'       => 'עקיפת חסימות אוטומטיות של שרתי פרוקסי',
'right-unblockself'           => 'שחרור חסימה של עצמם',
'right-protect'               => 'שינוי רמות הגנה ועריכת דפים מוגנים',
'right-editprotected'         => 'עריכת דפים מוגנים (ללא הגנה מדורגת)',
'right-editinterface'         => 'עריכת ממשק המשתמש',
'right-editusercssjs'         => 'עריכת דפי CSS ו־JavaScript של משתמשים אחרים',
'right-editusercss'           => 'עריכת דפי CSS של משתמשים אחרים',
'right-edituserjs'            => 'עריכת דפי JavaScript של משתמשים אחרים',
'right-rollback'              => 'שחזור מהיר של עריכות המשתמש האחרון שערך דף מסוים',
'right-markbotedits'          => 'סימון עריכות משוחזרות כעריכות של בוט',
'right-noratelimit'           => 'עקיפת הגבלת קצב העריכות',
'right-import'                => 'ייבוא דפים מאתרי ויקי אחרים',
'right-importupload'          => 'ייבוא דפים באמצעות העלאת קובץ',
'right-patrol'                => 'סימון עריכות של אחרים כבדוקות',
'right-autopatrol'            => 'סימון אוטומטי של עריכות של המשתמש כבדוקות',
'right-patrolmarks'           => 'צפייה בסימוני עריכות בדוקות בשינויים האחרונים',
'right-unwatchedpages'        => 'הצגת רשימה של דפים שאינם במעקב',
'right-trackback'             => 'שליחת טרקבק',
'right-mergehistory'          => 'מיזוג היסטוריות של דפים',
'right-userrights'            => 'עריכת כל הרשאות המשתמש',
'right-userrights-interwiki'  => 'עריכת הרשאות המשתמש של משתמשים באתרי ויקי אחרים',
'right-siteadmin'             => 'נעילת וביטול נעילת בסיס הנתונים',
'right-reset-passwords'       => 'איפוס סיסמאות של משתמשים אחרים',
'right-override-export-depth' => 'ייצוא דפים כולל דפים מקושרים עד עומק של חמישה',
'right-sendemail'             => 'שליחת דואר אלקטרוני למשתמשים אחרים',
'right-revisionmove'          => 'העברת גרסאות',
'right-disableaccount'        => 'ביטול חשבונות',

# User rights log
'rightslog'      => 'יומן תפקידים',
'rightslogtext'  => 'זהו יומן השינויים בתפקידי המשתמשים.',
'rightslogentry' => 'שינה את ההרשאות של $1 מ$2 ל$3',
'rightsnone'     => '(כלום)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'לקרוא דף זה',
'action-edit'                 => 'לערוך דף זה',
'action-createpage'           => 'ליצור דפים',
'action-createtalk'           => 'ליצור דפי שיחה',
'action-createaccount'        => 'ליצור את חשבון המשתמש הזה',
'action-minoredit'            => 'לסמן עריכה זו כמשנית',
'action-move'                 => 'להעביר דף זה',
'action-move-subpages'        => 'להעביר דף זה יחד עם דפי המשנה שלו',
'action-move-rootuserpages'   => 'להעביר דפי משתמש שאינם דפי משנה',
'action-movefile'             => 'להעביר קובץ זה',
'action-upload'               => 'להעלות קובץ זה',
'action-reupload'             => 'לדרוס את הקובץ הקיים הזה',
'action-reupload-shared'      => 'לדרוס את הקובץ הזה, הקיים כקובץ משותף',
'action-upload_by_url'        => 'להעלות קובץ זה מכתובת URL',
'action-writeapi'             => 'להשתמש ב־API לשינוי דפים',
'action-delete'               => 'למחוק דף זה',
'action-deleterevision'       => 'למחוק גרסה זו',
'action-deletedhistory'       => 'לצפות בהיסטוריה המחוקה של דף זה',
'action-browsearchive'        => 'לחפש דפים מחוקים',
'action-undelete'             => 'לשחזר דף זה',
'action-suppressrevision'     => 'לבדוק ולשחזר גרסה מוסתרת זו',
'action-suppressionlog'       => 'לצפות ביומן פרטי זה',
'action-block'                => 'לחסום משתמש זה מעריכה',
'action-protect'              => 'לשנות את רמת ההגנה על דף זה',
'action-import'               => 'לייבא דף זה מאתר ויקי אחר',
'action-importupload'         => 'לייבא דף זה באמצעות העלאת קובץ',
'action-patrol'               => 'לסמן עריכות של אחרים כבדוקות',
'action-autopatrol'           => 'לסמן את עריכותיך כבדוקות',
'action-unwatchedpages'       => 'לצפות ברשימת הדפים שאינם במעקב',
'action-trackback'            => 'לשלוח טרקבק',
'action-mergehistory'         => 'למזג את ההיסטוריה של דף זה',
'action-userrights'           => 'לערוך את כל הרשאות המשתמש',
'action-userrights-interwiki' => 'לערוך את ההרשאות של משתמשים באתרי ויקי אחרים',
'action-siteadmin'            => 'לנעול או לבטל את נעילת בסיס הנתונים',
'action-revisionmove'         => 'להעביר גרסאות',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|שינוי אחד|$1 שינויים}}',
'recentchanges'                     => 'שינויים אחרונים',
'recentchanges-legend'              => 'אפשרויות בשינויים האחרונים',
'recentchangestext'                 => 'ניתן לעקוב אחרי השינויים האחרונים באתר בדף זה.',
'recentchanges-feed-description'    => 'ניתן לעקוב אחרי השינויים האחרונים באתר בדף זה.',
'recentchanges-label-newpage'       => 'בעריכה זו נוצר דף חדש',
'recentchanges-label-minor'         => 'זוהי עריכה משנית',
'recentchanges-label-bot'           => 'עריכה זו בוצעה על ידי בוט',
'recentchanges-label-unpatrolled'   => 'עריכה זו טרם נבדקה',
'rcnote'                            => "להלן {{PLURAL:$1|השינוי האחרון|'''$1''' השינויים האחרונים}} {{PLURAL:$2|ביום האחרון|ב־$2 הימים האחרונים}}, עד $5, $4:",
'rcnotefrom'                        => 'להלן <b>$1</b> השינויים האחרונים שבוצעו החל מתאריך <b>$2</b>:',
'rclistfrom'                        => 'הצגת שינויים חדשים החל מ־$1',
'rcshowhideminor'                   => '$1 שינויים משניים',
'rcshowhidebots'                    => '$1 בוטים',
'rcshowhideliu'                     => '$1 משתמשים רשומים',
'rcshowhideanons'                   => '$1 משתמשים אנונימיים',
'rcshowhidepatr'                    => '$1 עריכות בדוקות',
'rcshowhidemine'                    => '$1 עריכות שלי',
'rclinks'                           => 'הצגת $1 שינויים אחרונים ב־$2 הימים האחרונים.<br /> $3',
'diff'                              => 'הבדל',
'hist'                              => 'היסטוריה',
'hide'                              => 'הסתרת',
'show'                              => 'הצגת',
'minoreditletter'                   => 'מ',
'newpageletter'                     => 'ח',
'boteditletter'                     => 'ב',
'sectionlink'                       => '←',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|משתמש אחד עוקב|$1 משתמשים עוקבים}} אחרי הדף]',
'rc_categories'                     => 'הגבלה לקטגוריות (יש להפריד עם "|")',
'rc_categories_any'                 => 'הכול',
'newsectionsummary'                 => '/* $1 */ פסקה חדשה',
'rc-enhanced-expand'                => 'הצגת הפרטים (נדרש JavaScript)',
'rc-enhanced-hide'                  => 'הסתרת הפרטים',

# Recent changes linked
'recentchangeslinked'          => 'שינויים בדפים המקושרים',
'recentchangeslinked-feed'     => 'שינויים בדפים המקושרים',
'recentchangeslinked-toolbox'  => 'שינויים בדפים המקושרים',
'recentchangeslinked-title'    => 'שינויים בדפים המקושרים לדף $1',
'recentchangeslinked-backlink' => '→ $1',
'recentchangeslinked-noresult' => 'לא היו שינויים בדפים המקושרים בתקופה זו.',
'recentchangeslinked-summary'  => "בדף מיוחד זה רשומים השינויים האחרונים בדפים המקושרים מתוך הדף (או בדפים הכלולים בקטגוריה).
דפים ב[[Special:Watchlist|רשימת המעקב שלכם]] מוצגים ב'''הדגשה'''.",
'recentchangeslinked-page'     => 'שם הדף:',
'recentchangeslinked-to'       => 'הצגת השינויים בדפים המקשרים לדף הנתון במקום זאת',

# Upload
'upload'                      => 'העלאת קובץ לשרת',
'uploadbtn'                   => 'העלאה',
'reuploaddesc'                => 'ביטול ההעלאה וחזרה לטופס העלאת קבצים לשרת',
'upload-tryagain'             => 'שליחת התיאור החדש של הקובץ',
'uploadnologin'               => 'לא נכנסתם לאתר',
'uploadnologintext'           => 'עליכם [[Special:UserLogin|להיכנס לחשבון]] כדי להעלות קבצים.',
'upload_directory_missing'    => 'שרת האינטרנט אינו יכול ליצור את תיקיית ההעלאות ($1) החסרה.',
'upload_directory_read_only'  => 'שרת האינטרנט אינו יכול לכתוב בתיקיית ההעלאות ($1).',
'uploaderror'                 => 'שגיאה בהעלאת הקובץ',
'upload-recreate-warning'     => "'''אזהרה: קובץ בשם זה נמחק או הועבר.'''

יומני המחיקות וההעברות של הדף מוצגים להלן:",
'uploadtext'                  => "השתמשו בטופס להלן כדי להעלות קבצים.
כדי לראות או לחפש קבצים שהועלו בעבר אנא פנו ל[[Special:FileList|רשימת הקבצים שהועלו]], וכמו כן, העלאות (כולל העלאות של גרסה חדשה) מוצגות ב[[Special:Log/upload|יומן ההעלאות]], ומחיקות ב[[Special:Log/delete|יומן המחיקות]].

כדי לכלול קובץ בדף, השתמשו בקישור באחת הצורות הבאות:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' לשימוש בגרסה המלאה של הקובץ
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|טקסט תיאור]]</nowiki></tt>''' לשימוש בגרסה מוקטנת ברוחב 200 פיקסלים בתיבה בצד שמאל של הדף, עם 'טקסט תיאור' כתיאור
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' לקישור ישיר לקובץ בלי להציגו",
'upload-permitted'            => 'סוגי קבצים מותרים: $1.',
'upload-preferred'            => 'סוגי קבצים מומלצים: $1.',
'upload-prohibited'           => 'סוגי קבצים אסורים: $1.',
'uploadlog'                   => 'יומן העלאות קבצים',
'uploadlogpage'               => 'יומן העלאות',
'uploadlogpagetext'           => 'להלן רשימה של העלאות הקבצים האחרונות שבוצעו.
ראו את [[Special:NewFiles|גלריית הקבצים החדשים]] להצגה ויזואלית שלהם.',
'filename'                    => 'שם הקובץ',
'filedesc'                    => 'תקציר',
'fileuploadsummary'           => 'תיאור:',
'filereuploadsummary'         => 'השינויים בקובץ:',
'filestatus'                  => 'מעמד זכויות יוצרים:',
'filesource'                  => 'מקור:',
'uploadedfiles'               => 'קבצים שהועלו',
'ignorewarning'               => 'התעלמות מהאזהרה ושמירת הקובץ בכל זאת',
'ignorewarnings'              => 'התעלמות מכל האזהרות',
'minlength1'                  => 'שמות קבצים צריכים להיות בני תו אחד לפחות.',
'illegalfilename'             => 'הקובץ "$1" מכיל תווים בלתי חוקיים. אנא שנו את שמו ונסו להעלותו שנית.',
'badfilename'                 => 'שם הקובץ שונה ל־"$1".',
'filetype-mime-mismatch'      => 'סיומת הקובץ אינה מתאימה לסוג ה־MIME.',
'filetype-badmime'            => 'לא ניתן להעלות קבצים עם סוג ה־MIME "$1".',
'filetype-bad-ie-mime'        => 'לא ניתן להעלות קובץ זה, כיוון שאינטרנט אקספלורר יזהה אותו כקובץ מסוג "$1", שהוא סוג קובץ אסור שעלול להיות מסוכן.',
'filetype-unwanted-type'      => "'''\".\$1\"''' הוא סוג קובץ בלתי מומלץ. {{PLURAL:\$3|סוג הקובץ המומלץ הוא|סוגי הקבצים המומלצים הם}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' הוא סוג קובץ אסור להעלאה. {{PLURAL:\$3|סוג הקובץ המותר הוא|סוגי הקבצים המותרים הם}} \$2.",
'filetype-missing'            => 'לקובץ אין סיומת (כדוגמת ".jpg").',
'empty-file'                  => 'הקובץ ששלחת היה ריק',
'file-too-large'              => 'הקובץ ששלחת היה גדול מדי',
'filename-tooshort'           => 'שם הקובץ קצר מדי',
'filetype-banned'             => 'סוג הקובץ הזה אסור להעלאה',
'verification-error'          => 'קובץ זה לא עבר את תהליך אימות הקבצים',
'hookaborted'                 => 'השינוי שניסית לבצע הופסק על ידי מבנה Hook של הרחבה',
'illegal-filename'            => 'שם הקובץ אינו מותר להעלאה',
'overwrite'                   => 'דריסת קובץ קיים אינה מותרת',
'unknown-error'               => 'אירעה שגיאה בלתי ידועה',
'tmp-create-error'            => 'לא ניתן ליצור קובץ זמני',
'tmp-write-error'             => 'שגיאה בכתיבה לקובץ הזמני',
'large-file'                  => 'מומלץ שהקבצים לא יהיו גדולים יותר מ־$1 (גודל הקובץ שהעליתם הוא $2).',
'largefileserver'             => 'גודל הקובץ שהעליתם חורג ממגבלת השרת.',
'emptyfile'                   => 'הקובץ שהעליתם ריק. ייתכן שהסיבה לכך היא שגיאת הקלדה בשם הקובץ. אנא ודאו שזהו הקובץ שברצונך להעלות.',
'fileexists'                  => "קובץ בשם זה כבר קיים, אנא בדקו את '''<tt>[[:$1]]</tt>''' אם אינכם בטוחים שברצונכם להחליף אותו.
[[$1|thumb]]",
'filepageexists'              => "דף תיאור הקובץ עבור קובץ זה כבר נוצר ב'''<tt>[[:$1]]</tt>''', אך לא קיים קובץ בשם זה.
תיאור הקובץ שתכתבו לא יופיע בדף תיאור הקובץ.
כדי לגרום לו להופיע שם, יהיה עליכם לערוך אותו ידנית. [[$1|thumb]]",
'fileexists-extension'        => "קובץ עם שם דומה כבר קיים: [[$2|thumb]]
* שם הקובץ המועלה: '''<tt>[[:$1]]</tt>'''
* שם הקובץ הקיים: '''<tt>[[:$2]]</tt>'''
אנא בחרו שם אחר.",
'fileexists-thumbnail-yes'    => "הקובץ הוא כנראה תמונה מוקטנת (ממוזערת). [[$1|thumb]]
אנא בדקו את הקובץ '''<tt>[[:$1]]</tt>'''.
אם הקובץ שבדקתם הוא אותה התמונה בגודל מקורי, אין זה הכרחי להעלות גם תמונה ממוזערת.",
'file-thumbnail-no'           => "שם הקובץ מתחיל עם '''<tt>$1</tt>'''. נראה שזוהי תמונה מוקטנת (ממוזערת).
אם התמונה בגודל מלא מצויה ברשותכם, אנא העלו אותה ולא את התמונה הממוזערת; אחרת, אנא שנו את שם הקובץ.",
'fileexists-forbidden'        => 'קובץ בשם זה כבר קיים, ואינכם יכולים להחליף אותו.
אם אתם עדיין מעוניינים להעלות קובץ זה, אנא חזרו לדף הקודם והעלו את הקובץ תחת שם חדש.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'קובץ בשם זה כבר קיים כקובץ משותף.
אם אתם עדיין מעוניינים להעלות קובץ זה, אנא חזרו לדף הקודם והעלו את הקובץ תחת שם חדש.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'קובץ זה כפול ל{{PLURAL:$1|קובץ הבא|קבצים הבאים}}:',
'file-deleted-duplicate'      => 'קובץ זהה לקובץ זה ([[$1]]) נמחק בעבר. אנא בדקו את היסטוריית המחיקה של הקובץ לפני שתעלו אותו מחדש.',
'uploadwarning'               => 'אזהרת העלאת קבצים',
'uploadwarning-text'          => 'אנא שנו את תיאור הקובץ שלמטה ונסו שוב.',
'savefile'                    => 'שמירת קובץ',
'uploadedimage'               => 'העלה את הקובץ [[$1]]',
'overwroteimage'              => 'העלה גרסה חדשה של הקובץ [[$1]]',
'uploaddisabled'              => 'העלאת קבצים מבוטלת',
'copyuploaddisabled'          => 'העלאת קבצים מכתובת URL מבוטלת',
'uploadfromurl-queued'        => 'העלאתכם הועברה לתור המשימות.',
'uploaddisabledtext'          => 'אפשרות העלאת הקבצים מבוטלת.',
'php-uploaddisabledtext'      => 'אפשרות העלאת הקבצים מבוטלת ברמת PHP. אנא בדקו את ההגדרה file_uploads.',
'uploadscripted'              => 'הקובץ כולל קוד סקריפט או HTML שעשוי להתפרש או להתבצע בטעות על ידי הדפדפן.',
'uploadvirus'                 => 'הקובץ מכיל וירוס! פרטים: <div style="direction: ltr;">$1</div>',
'upload-source'               => 'קובץ המקור',
'sourcefilename'              => 'שם הקובץ:',
'sourceurl'                   => 'כתובת URL של המקור:',
'destfilename'                => 'שמור קובץ בשם:',
'upload-maxfilesize'          => 'גודל הקובץ המקסימלי: $1',
'upload-description'          => 'תיאור הקובץ',
'upload-options'              => 'אפשרויות העלאה',
'watchthisupload'             => 'מעקב אחרי קובץ זה',
'filewasdeleted'              => 'קובץ בשם זה כבר הועלה בעבר, ולאחר מכן נמחק. אנא בדקו את הדף $1 לפני שתמשיכו להעלותו שנית.',
'upload-wasdeleted'           => "'''אזהרה: הנכם מעלים קובץ שנמחק בעבר.'''

אנא שיקלו האם יהיה זה נכון להמשיך בהעלאת הקובץ.
יומן המחיקות של הקובץ מוצג להלן:",
'filename-bad-prefix'         => "שם הקובץ שאתם מעלים מתחיל עם '''\"\$1\"''', שהוא שם שאינו מתאר את הקובץ ובדרך כלל מוכנס אוטומטית על ידי מצלמות דיגיטליות. אנא בחרו שם מתאים יותר לקובץ, שיתאר את תכניו.",
'filename-prefix-blacklist'   => ' #<!-- נא להשאיר שורה זו בדיוק כפי שהיא --> <pre>
# התחביר הוא כדלקמן:
#   * כל דבר מתו "#" לסוף השורה הוא הערה
#   * כל שורה לא ריקה היא קידומת לשמות קבצים טיפוסיים שמצלמות דיגיטליות נותנות אוטומטית
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # מספר טלפונים סלולריים
IMG # כללי
JD # Jenoptik
MGP # Pentax
PICT # שונות
 #</pre> <!-- נא להשאיר שורה זו בדיוק כפי שהיא -->',
'upload-success-subj'         => 'ההעלאה הושלמה בהצלחה',
'upload-success-msg'          => 'ההעלאה מהכתובת [$2] הושלמה בהצלחה. הקובץ שהעליתם זמין כאן: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'בעיה בהעלאה',
'upload-failure-msg'          => 'הייתה בעיה עם הקובץ שהעליתם מהכתובת [$2]:

$1',
'upload-warning-subj'         => 'אזהרה בהעלאה',
'upload-warning-msg'          => 'הייתה בעיה עם הקובץ שהעליתם מהכתובת [$2]. באפשרותכם לחזור ל[[Special:Upload/stash/$1|טופס ההעלאה]] כדי לתקן בעיה זו.',

'upload-proto-error'        => 'פרוטוקול שגוי',
'upload-proto-error-text'   => 'בהעלאה מרוחקת, יש להשתמש בכתובות URL המתחילות עם <code>http://</code> או <code>ftp://</code>.',
'upload-file-error'         => 'שגיאה פנימית',
'upload-file-error-text'    => 'שגיאה פנימית התרחשה בעת הניסיון ליצור קובץ זמני על השרת.
אנא צרו קשר עם [[Special:ListUsers/sysop|מפעיל מערכת]].',
'upload-misc-error'         => 'שגיאת העלאה בלתי ידועה',
'upload-misc-error-text'    => 'שגיאת העלאה בלתי ידועה התרחשה במהלך ההעלאה.
אנא ודאו שכתובת ה־URL תקינה וזמינה ונסו שוב.
אם הבעיה חוזרת על עצמה, אנא צרו קשר עם [[Special:ListUsers/sysop|מפעיל מערכת]].',
'upload-too-many-redirects' => 'ה־URL כוללת הפניות רבות מדי',
'upload-unknown-size'       => 'גודל בלתי ידוע',
'upload-http-error'         => 'התרחשה שגיאת HTTP: $1',

# Special:UploadStash
'uploadstash'          => 'מאגר העלאות',
'uploadstash-summary'  => 'דף זה מאפשר גישה לקבצים שהועלו (או שהם בתהליך העלאה) אך טרם פורסמו באתר הוויקי. קבצים אלה אינם גלויים לאף אחד מלבד המשתמש שהעלה אותם.',
'uploadstash-clear'    => 'מחיקת הקבצים במאגר',
'uploadstash-nofiles'  => 'אין לכם קבצים במאגר.',
'uploadstash-badtoken' => 'ביצוע הפעולה נכשל, אולי בגלל פקיעת תוקפו של אסימון העריכה שלכם. נסו שוב.',
'uploadstash-errclear' => 'מחיקת הקבצים נכשלה.',
'uploadstash-refresh'  => 'רענון רשימת הקבצים',

# img_auth script messages
'img-auth-accessdenied' => 'הגישה נדחתה',
'img-auth-nopathinfo'   => 'PATH_INFO חסר.
השרת אינו מוגדר להעברת מידע זה.
ייתכן שהוא מבוסס על CGI ולכן אינו יכול לתמוך ב־img_auth.
ראו http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'הנתיב המבוקש אינו בתיקיית ההעלאות שהוגדרה.',
'img-auth-badtitle'     => 'לא ניתן ליצור כותרת תקינה מתוך "$1".',
'img-auth-nologinnWL'   => 'אינכם מחוברים לחשבון והדף "$1" אינו ברשימה המותרת.',
'img-auth-nofile'       => 'הקובץ "$1" אינו קיים.',
'img-auth-isdir'        => 'אתם מנסים לגשת לתיקייה "$1".
רק גישה לקבצים מותרת.',
'img-auth-streaming'    => 'מבצע הזרמה של "$1".',
'img-auth-public'       => 'img_auth.php משמש להצגת קבצים מתוך אתר ויקי פרטי.
אתר ויקי זה מוגדר כציבורי.
כדי להשיג אבטחה מירבית, img_auth.php מבוטל.',
'img-auth-noread'       => 'למשתמש אין הרשאה לקרוא את "$1".',

# HTTP errors
'http-invalid-url'      => 'כתובת URL בלתי תקינה: $1',
'http-invalid-scheme'   => 'כתובות URL מהסוג "$1" אינן נתמכות.',
'http-request-error'    => 'בקשת ה־HTTP נכשלה עקב שגיאה בלתי ידועה.',
'http-read-error'       => 'שגיאת קריאה של HTTP.',
'http-timed-out'        => 'עבר זמן ההמתנה של בקשת ה־HTTP.',
'http-curl-error'       => 'שגיאה בקבלת כתובת ה־URL: $1',
'http-host-unreachable' => 'לא ניתן להגיע לכתובת ה־URL.',
'http-bad-status'       => 'הייתה בעיה בשליחת בקשת ה־HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'לא ניתן להגיע ל־URL',
'upload-curl-error6-text'  => 'לא ניתן לכתובת ה־URL שנכתבה. אנא בדקו אם כתובת זו נכונה ואם האתר זמין.',
'upload-curl-error28'      => 'הסתיים זמן ההמתנה להעלאה',
'upload-curl-error28-text' => 'לאתר לקח זמן רב מדי לענות. אנא בדקו שהאתר זמין, המתינו מעט ונסו שוב. ייתכן שתרצו לנסות בזמן פחות עמוס.',

'license'            => 'רישיון:',
'license-header'     => 'רישיון',
'nolicense'          => 'אין',
'license-nopreview'  => '(תצוגה מקדימה לא זמינה)',
'upload_source_url'  => ' (כתובת URL תקפה ונגישה)',
'upload_source_file' => ' (קובץ במחשב שלך)',

# Special:ListFiles
'listfiles-summary'     => 'דף זה מציג את כל הקבצים שהועלו. כברירת מחדל מוצגים הקבצים האחרונים שהועלו בראש הרשימה. לחיצה על כותרת של עמודה משנה את המיון.',
'listfiles_search_for'  => 'חיפוש קובץ מדיה בשם:',
'imgfile'               => 'קובץ',
'listfiles'             => 'רשימת קבצים',
'listfiles_thumb'       => 'תמונה ממוזערת',
'listfiles_date'        => 'תאריך',
'listfiles_name'        => 'שם',
'listfiles_user'        => 'משתמש',
'listfiles_size'        => 'גודל',
'listfiles_description' => 'תיאור',
'listfiles_count'       => 'גרסאות',

# File description page
'file-anchor-link'                  => 'קובץ',
'filehist'                          => 'היסטוריית הקובץ',
'filehist-help'                     => 'לחצו על תאריך/שעה כדי לראות את הקובץ כפי שנראה בעת זו.',
'filehist-deleteall'                => 'מחיקת כל הגרסאות',
'filehist-deleteone'                => 'מחיקה',
'filehist-revert'                   => 'שחזור',
'filehist-current'                  => 'נוכחית',
'filehist-datetime'                 => 'תאריך/שעה',
'filehist-thumb'                    => 'תמונה ממוזערת',
'filehist-thumbtext'                => 'תמונה ממוזערת לגרסה מתאריך $1',
'filehist-nothumb'                  => 'אין תמונה ממוזערת',
'filehist-user'                     => 'משתמש',
'filehist-dimensions'               => 'ממדים',
'filehist-filesize'                 => 'גודל הקובץ',
'filehist-comment'                  => 'הערה',
'filehist-missing'                  => 'הקובץ חסר',
'imagelinks'                        => 'קישורים לקובץ',
'linkstoimage'                      => '{{PLURAL:$1|הדף הבא משתמש|הדפים הבאים משתמשים}} בקובץ זה:',
'linkstoimage-more'                 => 'יותר מ{{PLURAL:$1|דף אחד מקשר|־$1 דפים מקשרים}} לקובץ זה.
הרשימה הבאה מראה רק את {{PLURAL:$1|הדף הראשון שמקשר|$1 הדפים הראשונים שמקשרים}} לקובץ זה.
ניתן לצפות ב[[Special:WhatLinksHere/$2|רשימה המלאה]].',
'nolinkstoimage'                    => 'אין דפים המשתמשים בקובץ זה.',
'morelinkstoimage'                  => 'ראו [[Special:WhatLinksHere/$1|דפים נוספים]] המשתמשים בקובץ זה.',
'redirectstofile'                   => '{{PLURAL:$1|הדף הבא הוא דף הפניה|הדפים הבאים הם דפי הפניה}} לקובץ זה:',
'duplicatesoffile'                  => '{{PLURAL:$1|הקובץ הבא זהה|הקבצים הבאים זהים}} לקובץ זה ([[Special:FileDuplicateSearch/$2|לפרטים נוספים]]):',
'sharedupload'                      => 'זהו קובץ מתוך $1 וניתן להשתמש בו גם במיזמים אחרים.',
'sharedupload-desc-there'           => 'זהו קובץ מתוך $1 וניתן להשתמש בו גם במיזמים אחרים.
למידע נוסף, ראו את [$2 דף תיאור הקובץ].',
'sharedupload-desc-here'            => 'זהו קובץ מתוך $1 וניתן להשתמש בו גם במיזמים אחרים.
תיאורו ב[$2 דף תיאור הקובץ] שלו מוצג למטה.',
'filepage-nofile'                   => 'לא קיים קובץ בשם זה.',
'filepage-nofile-link'              => 'לא קיים קובץ בשם זה, אך באפשרותכם [$1 להעלות אחד].',
'uploadnewversion-linktext'         => 'העלאת גרסה חדשה של קובץ זה',
'shared-repo-from'                  => 'מתוך $1',
'shared-repo'                       => 'מקום איחסון משותף',
'shared-repo-name-wikimediacommons' => 'ויקישיתוף',
'filepage.css'                      => '/* הסגנונות הנכתבים כאן יוכללו בדף תיאור הקובץ, כולל באתרי ויקי זרים */',

# File reversion
'filerevert'                => 'שחזור $1',
'filerevert-backlink'       => '→ $1',
'filerevert-legend'         => 'שחזור קובץ',
'filerevert-intro'          => "אתם עומדים לשחזר את הקובץ '''[[Media:$1|$1]]''' ל[$4 גרסה מ־$3, $2].",
'filerevert-comment'        => 'סיבה:',
'filerevert-defaultcomment' => 'שוחזר לגרסה מ־$2, $1',
'filerevert-submit'         => 'שחזור',
'filerevert-success'        => "'''[[Media:$1|$1]]''' שוחזרה ל[$4 גרסה מ־$3, $2].",
'filerevert-badversion'     => 'אין גרסה מקומית קודמת של הקובץ שהועלתה בתאריך המבוקש.',

# File deletion
'filedelete'                  => 'מחיקת $1',
'filedelete-backlink'         => '→ $1',
'filedelete-legend'           => 'מחיקת קובץ',
'filedelete-intro'            => "אתם עומדים למחוק את הקובץ '''[[Media:$1|$1]]''' יחד עם כל ההיסטוריה שלו.",
'filedelete-intro-old'        => "אתם מוחקים את הגרסה של '''[[Media:$1|$1]]''' מ־[$4 $3, $2].",
'filedelete-comment'          => 'סיבה:',
'filedelete-submit'           => 'מחיקה',
'filedelete-success'          => "'''$1''' נמחק.",
'filedelete-success-old'      => "הגרסה של '''[[Media:$1|$1]]''' מ־$3, $2 נמחקה.",
'filedelete-nofile'           => "'''$1''' אינו קיים.",
'filedelete-nofile-old'       => "אין גרסה ישנה של '''$1''' עם התכונות המבוקשות.",
'filedelete-otherreason'      => 'סיבה נוספת/אחרת:',
'filedelete-reason-otherlist' => 'סיבה אחרת',
'filedelete-reason-dropdown'  => '
* סיבות מחיקה נפוצות
** הפרת זכויות יוצרים
** קובץ כפול',
'filedelete-edit-reasonlist'  => 'עריכת סיבות המחיקה',
'filedelete-maintenance'      => 'אפשרות המחיקה והשחזור של קבצים מבוטלת זמנית עקב פעולת תחזוקה.',

# MIME search
'mimesearch'         => 'חיפוש MIME',
'mimesearch-summary' => 'דף זה מאפשר את סינון הקבצים לפי סוג ה־MIME שלהם.
סוג ה־MIME בנוי בצורה "סוג תוכן/סוג משני", לדוגמה <tt>image/jpeg</tt>.',
'mimetype'           => 'סוג MIME:',
'download'           => 'הורדה',

# Unwatched pages
'unwatchedpages' => 'דפים שאינם במעקב',

# List redirects
'listredirects' => 'רשימת הפניות',

# Unused templates
'unusedtemplates'     => 'תבניות שאינן בשימוש',
'unusedtemplatestext' => 'דף זה מכיל רשימה של כל הדפים במרחב השם {{ns:template}} שאינם נכללים בדף אחר. אנא זכרו לבדוק את הקישורים האחרים לתבניות לפני שתמחקו אותן.',
'unusedtemplateswlh'  => 'קישורים אחרים',

# Random page
'randompage'         => 'דף אקראי',
'randompage-nopages' => 'אין דפים ב{{PLURAL:$2|מרחב השם הבא|מרחבי השם הבאים}}: $1.',

# Random redirect
'randomredirect'         => 'הפניה אקראית',
'randomredirect-nopages' => 'אין הפניות במרחב השם "$1".',

# Statistics
'statistics'                   => 'סטטיסטיקות',
'statistics-header-pages'      => 'סטטיסטיקות דפים',
'statistics-header-edits'      => 'סטטיסטיקות עריכה',
'statistics-header-views'      => 'סטטיסטיקות צפייה',
'statistics-header-users'      => 'סטטיסטיקות משתמשים',
'statistics-header-hooks'      => 'סטטיסטיקות אחרות',
'statistics-articles'          => 'דפי תוכן',
'statistics-pages'             => 'דפים',
'statistics-pages-desc'        => 'כל הדפים באתר הוויקי, כולל דפי שיחה, הפניות, וכדומה',
'statistics-files'             => 'קבצים שהועלו',
'statistics-edits'             => 'העריכות מאז תחילת הפעולה של {{SITENAME}}',
'statistics-edits-average'     => 'מספר העריכות הממוצע לדף',
'statistics-views-total'       => 'מספר הצפיות הכולל',
'statistics-views-total-desc'  => 'צפיות בדפים שאינם קיימים ובדפים מיוחדים אינן כלולות',
'statistics-views-peredit'     => 'מספר הצפיות לעריכה',
'statistics-users'             => 'ה[[Special:ListUsers|משתמשים]] הרשומים',
'statistics-users-active'      => 'המשתמשים הפעילים',
'statistics-users-active-desc' => 'משתמשים שביצעו פעולה כלשהי ב{{PLURAL:$1|יום האחרון|־$1 הימים האחרונים}}',
'statistics-mostpopular'       => 'הדפים הנצפים ביותר',

'disambiguations'      => 'דפי פירושונים',
'disambiguationspage'  => 'Template:פירושונים',
'disambiguations-text' => "הדפים הבאים מקשרים ל'''דפי פירושונים'''.
עליהם לקשר לדף הנושא הרלוונטי במקום זאת.<br />
הדף נחשב לדף פירושונים אם הוא משתמש בתבנית המקושרת מהדף [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'הפניות כפולות',
'doubleredirectstext'        => 'ההפניות הבאות מפנות לדפי הפניה אחרים.
כל שורה מכילה קישור להפניות הראשונה והשנייה, וכן את היעד של ההפניה השנייה, שהיא לרוב היעד ה"אמיתי" של ההפניה, אליו אמורה ההפניה הראשונה להצביע.
ערכים <del>מחוקים</del> כבר תוקנו.',
'double-redirect-fixed-move' => '[[$1]] הועבר. כעת הוא הפניה לדף [[$2]].',
'double-redirect-fixer'      => 'מתקן הפניות',

'brokenredirects'        => 'הפניות לא תקינות',
'brokenredirectstext'    => 'ההפניות שלהלן מפנות לדפים שאינם קיימים:',
'brokenredirects-edit'   => 'עריכה',
'brokenredirects-delete' => 'מחיקה',

'withoutinterwiki'         => 'דפים ללא קישורי שפה',
'withoutinterwiki-summary' => 'הדפים הבאים אינם מקשרים לגרסאות שלהם בשפות אחרות:',
'withoutinterwiki-legend'  => 'הדפים המתחילים ב…',
'withoutinterwiki-submit'  => 'הצגה',

'fewestrevisions' => 'הדפים בעלי מספר העריכות הנמוך ביותר',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|בית אחד|$1 בתים}}',
'ncategories'             => '{{PLURAL:$1|קטגוריה אחת|$1 קטגוריות}}',
'nlinks'                  => '{{PLURAL:$1|קישור אחד|$1 קישורים}}',
'nmembers'                => '{{PLURAL:$1|דף אחד|$1 דפים}}',
'nrevisions'              => '{{PLURAL:$1|גרסה אחת|$1 גרסאות}}',
'nviews'                  => '{{PLURAL:$1|צפייה אחת|$1 צפיות}}',
'nimagelinks'             => 'בשימוש ב{{PLURAL:$1|דף אחד|־$1 דפים}}',
'ntransclusions'          => 'בשימוש ב{{PLURAL:$1|דף אחד|־$1 דפים}}',
'specialpage-empty'       => 'אין תוצאות.',
'lonelypages'             => 'דפים יתומים',
'lonelypagestext'         => 'הדפים הבאים אינם מקושרים מדפים אחרים באתר זה ואינם מוכללים בהם.',
'uncategorizedpages'      => 'דפים חסרי קטגוריה',
'uncategorizedcategories' => 'קטגוריות חסרות קטגוריה',
'uncategorizedimages'     => 'קבצים חסרי קטגוריה',
'uncategorizedtemplates'  => 'תבניות חסרות קטגוריה',
'unusedcategories'        => 'קטגוריות שאינן בשימוש',
'unusedimages'            => 'קבצים שאינם בשימוש',
'popularpages'            => 'הדפים הנצפים ביותר',
'wantedcategories'        => 'קטגוריות מבוקשות',
'wantedpages'             => 'דפים מבוקשים',
'wantedpages-badtitle'    => 'כותרת בלתי תקינה ברשימת התוצאות: $1',
'wantedfiles'             => 'קבצים מבוקשים',
'wantedtemplates'         => 'תבניות מבוקשות',
'mostlinked'              => 'הדפים המקושרים ביותר',
'mostlinkedcategories'    => 'הקטגוריות המקושרות ביותר',
'mostlinkedtemplates'     => 'התבניות המקושרות ביותר',
'mostcategories'          => 'הדפים מרובי־הקטגוריות ביותר',
'mostimages'              => 'הקבצים המקושרים ביותר',
'mostrevisions'           => 'הדפים בעלי מספר העריכות הגבוה ביותר',
'prefixindex'             => 'רשימת הדפים המתחילים ב…',
'shortpages'              => 'דפים קצרים',
'longpages'               => 'דפים ארוכים',
'deadendpages'            => 'דפים ללא קישורים',
'deadendpagestext'        => 'הדפים הבאים אינם מקשרים לדפים אחרים באתר.',
'protectedpages'          => 'דפים מוגנים',
'protectedpages-indef'    => 'הגנות לצמיתות בלבד',
'protectedpages-cascade'  => 'הגנות מדורגות בלבד',
'protectedpagestext'      => 'הדפים הבאים מוגנים מפני עריכה או העברה:',
'protectedpagesempty'     => 'אין כרגע דפים מוגנים עם הפרמטרים הללו.',
'protectedtitles'         => 'כותרות מוגנות',
'protectedtitlestext'     => 'הכותרות הבאות מוגנות מפני יצירה:',
'protectedtitlesempty'    => 'אין כרגע כותרות מוגנות עם הפרמטרים האלה.',
'listusers'               => 'רשימת משתמשים',
'listusers-editsonly'     => 'הצגת משתמשים עם עריכות בלבד',
'listusers-creationsort'  => 'סידור לפי תאריך היצירה',
'usereditcount'           => '{{PLURAL:$1|עריכה אחת|$1 עריכות}}',
'usercreated'             => 'נוצר ב־$2, $1',
'newpages'                => 'דפים חדשים',
'newpages-username'       => 'שם משתמש:',
'ancientpages'            => 'דפים מוזנחים',
'move'                    => 'העברה',
'movethispage'            => 'העברת דף זה',
'unusedimagestext'        => 'הקבצים הבאים קיימים אך אינם מוצגים באף דף.
שימו לב שאתרי אינטרנט אחרים עשויים לקשר לקובץ באמצעות כתובת URL ישירה, ולכן הוא עלול להופיע כאן למרות שהוא בשימוש פעיל.',
'unusedcategoriestext'    => 'למרות שהקטגוריות הבאות קיימות, אין שום דף בו נעשה בהן שימוש.',
'notargettitle'           => 'אין דף מטרה',
'notargettext'            => 'לא ציינתם דף מטרה או משתמש לגביו תבוצע פעולה זו.',
'nopagetitle'             => 'אין דף מטרה כזה',
'nopagetext'              => 'דף המטרה שציינתם אינו קיים.',
'pager-newer-n'           => '{{PLURAL:$1|הבאה|$1 הבאות}}',
'pager-older-n'           => '{{PLURAL:$1|הקודמת|$1 הקודמות}}',
'suppress'                => 'הסתרה',
'querypage-disabled'      => 'דף מיוחד זה מבוטל עקב בעיות ביצועים.',

# Book sources
'booksources'               => 'משאבי ספרות חיצוניים',
'booksources-search-legend' => 'חיפוש משאבי ספרות חיצוניים',
'booksources-isbn'          => 'מסת"ב:',
'booksources-go'            => 'הצגה',
'booksources-text'          => 'להלן רשימת קישורים לאתרים אחרים המוכרים ספרים חדשים ויד־שנייה, ושבהם עשוי להיות מידע נוסף לגבי ספרים שאתם מחפשים:',
'booksources-invalid-isbn'  => 'המסת"ב שניתן כנראה אינו תקין; אנא בדקו אם ביצעתם טעויות בהעתקה מהמידע המקורי.',

# Special:Log
'specialloguserlabel'  => 'משתמש:',
'speciallogtitlelabel' => 'כותרת:',
'log'                  => 'יומנים',
'all-logs-page'        => 'כל היומנים הציבוריים',
'alllogstext'          => 'תצוגה משולבת של כל סוגי היומנים הזמינים ב{{grammar:תחילית|{{SITENAME}}}}.
ניתן לצמצם את התצוגה על ידי בחירת סוג היומן, שם המשתמש (תלוי רישיות) או הדף המושפע (גם כן תלוי רישיות).',
'logempty'             => 'אין פריטים תואמים ביומן.',
'log-title-wildcard'   => 'חיפוש כותרות המתחילות באותיות אלה',

# Special:AllPages
'allpages'          => 'כל הדפים',
'alphaindexline'    => '$1 עד $2',
'nextpage'          => 'הדף הבא ($1)',
'prevpage'          => 'הדף הקודם ($1)',
'allpagesfrom'      => 'הצגת דפים החל מ:',
'allpagesto'        => 'הצגת דפים עד:',
'allarticles'       => 'כל הדפים',
'allinnamespace'    => 'כל הדפים (מרחב שם $1)',
'allnotinnamespace' => 'כל הדפים (שלא במרחב השם $1)',
'allpagesprev'      => 'הקודם',
'allpagesnext'      => 'הבא',
'allpagessubmit'    => 'הצגה',
'allpagesprefix'    => 'הדפים ששמם מתחיל ב…:',
'allpagesbadtitle'  => 'כותרת הדף המבוקש הייתה לא־חוקית, ריקה, קישור ויקי פנימי, או פנים שפה שגוי. ייתכן שהיא כוללת תו אחד או יותר האסורים לשימוש בכותרות.',
'allpages-bad-ns'   => 'אין מרחב שם בשם "$1".',

# Special:Categories
'categories'                    => 'קטגוריות',
'categoriespagetext'            => '{{PLURAL:$1|הקטגוריה הבאה כוללת|הקטגוריות הבאות כוללות}} דפים או קובצי מדיה.
[[Special:UnusedCategories|קטגוריות שאינן בשימוש]] אינן מוצגות כאן.
ראו גם את [[Special:WantedCategories|רשימת הקטגוריות המבוקשות]].',
'categoriesfrom'                => 'הצגת קטגוריות החל מ:',
'special-categories-sort-count' => 'סידור לפי מספר הדפים',
'special-categories-sort-abc'   => 'סידור לפי סדר האלף בית',

# Special:DeletedContributions
'deletedcontributions'             => 'תרומות משתמש מחוקות',
'deletedcontributions-title'       => 'תרומות משתמש מחוקות',
'sp-deletedcontributions-contribs' => 'תרומות',

# Special:LinkSearch
'linksearch'       => 'קישורים חיצוניים',
'linksearch-pat'   => 'תבנית קישור לחיפוש:',
'linksearch-ns'    => 'מרחב שם:',
'linksearch-ok'    => 'חיפוש',
'linksearch-text'  => 'ניתן להשתמש בתווים כללים, לדוגמה "*.wikipedia.org".<br />פרוטוקולים נתמכים: <tt>$1</tt>',
'linksearch-line'  => '$1 מקושר מהדף $2',
'linksearch-error' => 'תווים כלליים יכולים להופיע רק בתחילת שם השרת.',

# Special:ListUsers
'listusersfrom'      => 'הצגת משתמשים החל מ:',
'listusers-submit'   => 'הצגה',
'listusers-noresult' => 'לא נמצאו משתמשים.',
'listusers-blocked'  => '(חסום)',

# Special:ActiveUsers
'activeusers'            => 'רשימת משתמשים פעילים',
'activeusers-intro'      => 'זוהי רשימת המשתמשים שביצעו פעולה כלשהי ב{{PLURAL:$1|יום האחרון|־$1 הימים האחרונים}}.',
'activeusers-count'      => '{{PLURAL:$1|עריכה אחת|$1 עריכות}} ב{{PLURAL:$3|יום האחרון|־$3 הימים האחרונים|יומיים האחרונים}}',
'activeusers-from'       => 'הצגת משתמשים החל מ:',
'activeusers-hidebots'   => 'הסתרת בוטים',
'activeusers-hidesysops' => 'הסתרת מפעילי מערכת',
'activeusers-noresult'   => 'לא נמצאו משתמשים.',

# Special:Log/newusers
'newuserlogpage'              => 'יומן רישום משתמשים',
'newuserlogpagetext'          => 'זהו יומן המכיל הרשמות של משתמשים.',
'newuserlog-byemail'          => 'הסיסמה נשלחה בדוא"ל',
'newuserlog-create-entry'     => 'חשבון משתמש חדש',
'newuserlog-create2-entry'    => 'יצר חשבון חדש $1',
'newuserlog-autocreate-entry' => 'חשבון שנוצר אוטומטית',

# Special:ListGroupRights
'listgrouprights'                      => 'רשימת הרשאות לקבוצה',
'listgrouprights-summary'              => 'זוהי רשימה של קבוצות המשתמש המוגדרות באתר זה, עם ההרשאות של כל אחת.
מידע נוסף על ההרשאות ניתן למצוא [[{{MediaWiki:Listgrouprights-helppage}}|כאן]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">הרשאה שהוענקה</span>
* <span class="listgrouprights-revoked">הרשאה שהוסרה</span>',
'listgrouprights-group'                => 'קבוצה',
'listgrouprights-rights'               => 'הרשאות',
'listgrouprights-helppage'             => 'Help:הרשאות',
'listgrouprights-members'              => '(רשימת חברים)',
'listgrouprights-addgroup'             => 'הוספת {{PLURAL:$2|הקבוצה|הקבוצות}}: $1',
'listgrouprights-removegroup'          => 'הסרת {{PLURAL:$2|הקבוצה|הקבוצות}}: $1',
'listgrouprights-addgroup-all'         => 'הוספת כל הקבוצות',
'listgrouprights-removegroup-all'      => 'הסרת כל הקבוצות',
'listgrouprights-addgroup-self'        => 'הוספת {{PLURAL:$2|הקבוצה|הקבוצות}} לחשבון האישי: $1',
'listgrouprights-removegroup-self'     => 'הסרת {{PLURAL:$2|הקבוצה|הקבוצות}} מהחשבון האישי: $1',
'listgrouprights-addgroup-self-all'    => 'הוספת כל הקבוצות לחשבון האישי',
'listgrouprights-removegroup-self-all' => 'הסרת כל הקבוצות מהחשבון האישי',

# E-mail user
'mailnologin'          => 'אין כתובת לשליחה',
'mailnologintext'      => 'עליכם [[Special:UserLogin|להיכנס לחשבון]] ולהגדיר לעצמכם כתובת דואר אלקטרוני תקינה ב[[Special:Preferences|העדפות המשתמש]] שלכם כדי לשלוח דואר למשתמש אחר.',
'emailuser'            => 'שליחת דואר אלקטרוני למשתמש זה',
'emailpage'            => 'שליחת דואר למשתמש',
'emailpagetext'        => 'ניתן להשתמש בטופס כדי לשלוח הודעת דואר אלקטרוני למשתמש זה.
כתובת הדואר האלקטרוני שכתבתם ב[[Special:Preferences|העדפות המשתמש שלכם]] תופיע ככתובת ממנה נשלחה ההודעה, כדי לאפשר תגובה ישירה למכתב.',
'usermailererror'      => 'אוביקט הדואר החזיר שגיאה:',
'defemailsubject'      => 'דוא"ל {{SITENAME}}',
'usermaildisabled'     => 'שליחת דוא"ל למשתמשים מבוטלת',
'usermaildisabledtext' => 'אינכם רשאים לשלוח דואר אלקטרוני למשתמשים אחרים באתר זה',
'noemailtitle'         => 'אין כתובת דואר אלקטרוני',
'noemailtext'          => 'משתמש זה לא הזין כתובת דואר אלקטרוני חוקית.',
'nowikiemailtitle'     => 'שליחת דוא"ל אינה אפשרית',
'nowikiemailtext'      => 'משתמש זה בחר שלא לקבל דואר אלקטרוני ממשתמשים אחרים.',
'email-legend'         => 'שליחת דואר אלקטרוני למשתמש אחר של {{SITENAME}}',
'emailfrom'            => 'מאת:',
'emailto'              => 'אל:',
'emailsubject'         => 'נושא:',
'emailmessage'         => 'הודעה:',
'emailsend'            => 'שליחה',
'emailccme'            => 'קבלת העתק של הודעה זו בדואר האלקטרוני.',
'emailccsubject'       => 'העתק של הודעתך למשתמש $1: $2',
'emailsent'            => 'הדואר נשלח',
'emailsenttext'        => 'הודעת הדואר האלקטרוני שלך נשלחה.',
'emailuserfooter'      => 'דואר זה נשלח על ידי $1 למשתמש $2 באמצעות תכונת "שליחת דואר אלקטרוני למשתמש זה" ב{{grammar:תחילית|{{SITENAME}}}}.',

# User Messenger
'usermessage-summary' => 'השארת הודעת מערכת.',
'usermessage-editor'  => 'שולח הודעות המערכת',

# Watchlist
'watchlist'            => 'רשימת המעקב שלי',
'mywatchlist'          => 'רשימת המעקב שלי',
'watchlistfor2'        => 'עבור $1 $2',
'nowatchlist'          => 'אין דפים ברשימת המעקב.',
'watchlistanontext'    => 'עליכם $1 כדי לצפות או לערוך פריטים ברשימת המעקב.',
'watchnologin'         => 'לא נכנסתם לחשבון',
'watchnologintext'     => 'עליכם [[Special:UserLogin|להיכנס לחשבון]] כדי לערוך את רשימת המעקב.',
'addedwatch'           => 'הדף נוסף לרשימת המעקב',
'addedwatchtext'       => 'הדף [[:$1]] נוסף ל[[Special:Watchlist|רשימת המעקב]]. שינויים שייערכו בעתיד, בדף זה ובדף השיחה שלו, יוצגו ברשימת המעקב.

בנוסף, הדף יופיע בכתב מודגש ב[[Special:RecentChanges|רשימת השינויים האחרונים]], כדי להקל עליכם את המעקב אחריו.',
'removedwatch'         => 'הדף הוסר מרשימת המעקב',
'removedwatchtext'     => 'הדף [[:$1]] הוסר מ[[Special:Watchlist|רשימת המעקב]].',
'watch'                => 'מעקב',
'watchthispage'        => 'מעקב אחרי דף זה',
'unwatch'              => 'הפסקת מעקב',
'unwatchthispage'      => 'הפסקת המעקב אחרי דף זה',
'notanarticle'         => 'זהו אינו דף תוכן',
'notvisiblerev'        => 'הגרסה האחרונה שנוצרה על ידי משתמש אחר נמחקה',
'watchnochange'        => 'אף אחד מהדפים ברשימת המעקב לא עודכן בפרק הזמן המצוין למעלה.',
'watchlist-details'    => 'ברשימת המעקב יש {{PLURAL:$1|דף אחד|$1 דפים}} (לא כולל דפי שיחה).',
'wlheader-enotif'      => '* הודעות דוא"ל מאופשרות.',
'wlheader-showupdated' => "* דפים שהשתנו מאז ביקורכם האחרון בהם מוצגים ב'''הדגשה'''.",
'watchmethod-recent'   => 'בודק את הדפים שברשימת המעקב לשינויים אחרונים.',
'watchmethod-list'     => 'בודק את העריכות האחרונות בדפים שברשימת המעקב',
'watchlistcontains'    => 'רשימת המעקב כוללת {{PLURAL:$1|דף אחד|$1 דפים}}.',
'iteminvalidname'      => 'בעיה עם $1, שם שגוי…',
'wlnote'               => "להלן {{PLURAL:$1|השינוי האחרון|'''$1''' השינויים האחרונים}} {{PLURAL:$2|בשעה האחרונה|ב־'''$2''' השעות האחרונות}}.",
'wlshowlast'           => '(הצגת $1 שעות אחרונות | $2 ימים אחרונים | $3)',
'watchlist-options'    => 'אפשרויות ברשימת המעקב',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'בהוספה לרשימת המעקב…',
'unwatching' => 'בהסרה מרשימת המעקב…',

'enotif_mailer'                => 'הודעות {{SITENAME}}',
'enotif_reset'                 => 'סמן את כל הדפים כאילו נצפו',
'enotif_newpagetext'           => 'זהו דף חדש.',
'enotif_impersonal_salutation' => 'משתמש של {{SITENAME}}',
'changed'                      => 'שונה',
'created'                      => 'נוצר',
'enotif_subject'               => 'הדף $PAGETITLE ב{{grammar:תחילית|{{SITENAME}}}} $CHANGEDORCREATED על ידי $PAGEEDITOR',
'enotif_lastvisited'           => 'ראו $1 לכל השינויים מאז ביקורכם האחרון.',
'enotif_lastdiff'              => 'ראו $1 לשינוי זה.',
'enotif_anon_editor'           => 'משתמש אנונימי $1',
'enotif_body'                  => 'לכבוד $WATCHINGUSERNAME,

הדף $PAGETITLE ב{{grammar:תחילית|{{SITENAME}}}} $CHANGEDORCREATED ב־$PAGEEDITDATE על ידי $PAGEEDITOR, ראו $PAGETITLE_URL לגרסה הנוכחית.

$NEWPAGE

תקציר העריכה: $PAGESUMMARY $PAGEMINOREDIT

באפשרותכם ליצור קשר עם העורך:
בדואר האלקטרוני: $PAGEEDITOR_EMAIL
באתר: $PAGEEDITOR_WIKI

לא תהיינה הודעות על שינויים נוספים עד שתבקרו את הדף. באפשרותכם גם לאפס את דגלי ההודעות בכל הדפים שברשימת המעקב.

             מערכת ההודעות של {{SITENAME}}

--
כדי לשנות את הגדרות רשימת המעקב, בקרו בדף
{{fullurl:{{#special:Watchlist}}/edit}}

כדי למחוק את הדף מרשימת המעקב שלכם, בקרו בדף
$UNWATCHURL

למשוב ולעזרה נוספת:
{{fullurl::{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'מחיקה',
'confirm'                => 'אישור',
'excontent'              => 'תוכן היה: "$1"',
'excontentauthor'        => 'תוכן היה: "$1" (והתורם היחיד היה "[[Special:Contributions/$2|$2]]")',
'exbeforeblank'          => 'תוכן לפני שרוקן היה: "$1"',
'exblank'                => 'הדף היה ריק',
'delete-confirm'         => 'מחיקת $1',
'delete-backlink'        => '→ $1',
'delete-legend'          => 'מחיקה',
'historywarning'         => "'''אזהרה:''' לדף שאתם עומדים למחוק יש היסטוריית שינויים של בערך {{PLURAL:$1|גרסה אחת|$1 גרסאות}}:",
'confirmdeletetext'      => 'אתם עומדים למחוק דף יחד עם כל ההיסטוריה שלו.

אנא אשרו שזה אכן מה שאתם מתכוונים לעשות, שאתם מבינים את התוצאות של מעשה כזה, ושהמעשה מבוצע בהתאם ל[[{{MediaWiki:Policy-url}}|נהלי האתר]].',
'actioncomplete'         => 'הפעולה בוצעה',
'actionfailed'           => 'הפעולה נכשלה',
'deletedtext'            => '<strong><nowiki>$1</nowiki></strong> נמחק. ראו $2 לרשימת המחיקות האחרונות.',
'deletedarticle'         => 'מחק את [[$1]]',
'suppressedarticle'      => 'הסתיר את [[$1]]',
'dellogpage'             => 'יומן מחיקות',
'dellogpagetext'         => 'להלן רשימה של המחיקות האחרונות שבוצעו.',
'deletionlog'            => 'יומן מחיקות',
'reverted'               => 'שוחזר לגרסה קודמת',
'deletecomment'          => 'סיבה:',
'deleteotherreason'      => 'סיבה נוספת/אחרת:',
'deletereasonotherlist'  => 'סיבה אחרת',
'deletereason-dropdown'  => '* סיבות מחיקה נפוצות
** לבקשת הכותב
** הפרת זכויות יוצרים
** השחתה',
'delete-edit-reasonlist' => 'עריכת סיבות המחיקה',
'delete-toobig'          => 'דף זה כולל מעל {{PLURAL:$1|גרסה אחת|$1 גרסאות}} בהיסטוריית העריכות שלו. מחיקת דפים כאלה הוגבלה כדי למנוע פגיעה בביצועי האתר.',
'delete-warning-toobig'  => 'דף זה כולל מעל {{PLURAL:$1|גרסה אחת|$1 גרסאות}} בהיסטוריית העריכות שלו. מחיקה שלו עלולה להפריע לפעולות בבסיס הנתונים; אנא שיקלו שנית את המחיקה.',

# Rollback
'rollback'          => 'שחזור עריכות',
'rollback_short'    => 'שחזור',
'rollbacklink'      => 'שחזור',
'rollbackfailed'    => 'השחזור נכשל',
'cantrollback'      => 'לא ניתן לשחזר את העריכה – התורם האחרון הוא היחיד שכתב דף זה; עם זאת, ניתן למחוק את הדף.',
'alreadyrolled'     => 'לא ניתן לשחזר את עריכת הדף [[:$1]] על ידי [[User:$2|$2]] ([[User talk:$2|שיחה]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); מישהו אחר כבר ערך או שחזר דף זה.

העריכה האחרונה הייתה של [[User:$3|$3]] ([[User talk:$3|שיחה]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "תקציר העריכה היה: \"'''\$1'''\".",
'revertpage'        => 'שוחזר מעריכות של [[Special:Contributions/$2|$2]] ([[User talk:$2|שיחה]]) לעריכה האחרונה של [[User:$1|$1]]',
'revertpage-nouser' => 'שוחזר מעריכות של (שם המשתמש הוסר) לעריכה האחרונה של [[User:$1|$1]]',
'rollback-success'  => 'שוחזר מעריכה של $1 לעריכה האחרונה של $2',

# Edit tokens
'sessionfailure-title' => 'בעיה בחיבור',
'sessionfailure'       => 'נראה שיש בעיה בחיבורכם לאתר;
פעולתכם בוטלה כאמצעי זהירות נגד התחזות לתקשורת ממחשבכם.
אנא חזרו לדף הקודם, העלו אותו מחדש ונסו שוב.',

# Protect
'protectlogpage'              => 'יומן הגנות',
'protectlogtext'              => 'להלן רשימה של הפעלות וביטולי הגנות על דפים. ראו גם את [[Special:ProtectedPages|רשימת הדפים המוגנים]] הנוכחית.',
'protectedarticle'            => 'הפעיל הגנה על [[$1]]',
'modifiedarticleprotection'   => 'שינה את רמת ההגנה של [[$1]]',
'unprotectedarticle'          => 'ביטל את ההגנה על [[$1]]',
'movedarticleprotection'      => 'העביר את הגדרות ההגנה מ"[[$2]]" ל"[[$1]]"',
'protect-title'               => 'שינוי רמת ההגנה של "$1"',
'prot_1movedto2'              => '[[$1]] הועבר ל[[$2]]',
'protect-backlink'            => '→ $1',
'protect-legend'              => 'אישור הפעלת ההגנה',
'protectcomment'              => 'סיבה:',
'protectexpiry'               => 'פקיעת ההגנה:',
'protect_expiry_invalid'      => 'זמן פקיעת ההגנה בלתי חוקי.',
'protect_expiry_old'          => 'זמן פקיעת ההגנה כבר עבר.',
'protect-unchain-permissions' => 'שינוי אפשרויות הגנה נוספות',
'protect-text'                => "בדף זה תוכלו לראות ולשנות את רמת ההגנה של הדף '''<nowiki>$1</nowiki>'''. אנא ודאו שאתם פועלים בהתאם בהתאם לנהלי האתר.",
'protect-locked-blocked'      => "אינכם יכולים לשנות את רמת ההגנה של הדף בעודכם חסומים.
להלן ההגדרות הנוכחיות עבור הדף '''$1''':",
'protect-locked-dblock'       => "אינכם יכולים לשנות את רמת ההגנה על הדף שכן בסיס הנתונים חסום ברגע זה.
להלן ההגדרות הנוכחיות עבור הדף '''$1''':",
'protect-locked-access'       => "למשתמש שלכם אין הרשאה לשנות את רמת ההגנה של הדף.
להלן ההגדרות הנוכחיות עבור הדף '''$1''':",
'protect-cascadeon'           => 'דף זה מוגן כרגע כיוון שהוא מוכלל {{PLURAL:$1|בדף הבא, שמופעלת עליו|בדפים הבאים, שמופעלת עליהם}} הגנה מדורגת. באפשרותכם לשנות את רמת ההגנה על הדף, אך זה לא ישפיע על ההגנה המדורגת.',
'protect-default'             => 'כל המשתמשים מורשים',
'protect-fallback'            => 'משתמשים בעלי הרשאת "$1" בלבד',
'protect-level-autoconfirmed' => 'מניעת משתמשים חדשים ולא רשומים',
'protect-level-sysop'         => 'מפעילי מערכת בלבד',
'protect-summary-cascade'     => 'מדורג',
'protect-expiring'            => 'פוקעת $1 (UTC)',
'protect-expiry-indefinite'   => 'לצמיתות',
'protect-cascade'             => 'הגנה על כל הדפים המוכללים בדף זה (הגנה מדורגת)',
'protect-cantedit'            => 'אינכם יכולים לשנות את רמת ההגנה על דף זה, כיוון שאין לכם הרשאה לערוך אותו.',
'protect-othertime'           => 'זמן אחר:',
'protect-othertime-op'        => 'זמן אחר',
'protect-existing-expiry'     => 'זמן פקיעה נוכחי: $3, $2',
'protect-otherreason'         => 'סיבה אחרת/נוספת:',
'protect-otherreason-op'      => 'סיבה אחרת',
'protect-dropdown'            => '* סיבות הגנה נפוצות
** השחתה רבה
** ספאם רב
** מלחמת עריכה בלתי מועילה
** דף בשימוש רב',
'protect-edit-reasonlist'     => 'עריכת סיבות ההגנה',
'protect-expiry-options'      => 'שעה:1 hour,יום:1 day,שבוע:1 week,שבועיים:2 weeks,חודש:1 month,שלושה חודשים:3 months,שישה חודשים:6 months,שנה:1 year,לצמיתות:infinite',
'restriction-type'            => 'הרשאה:',
'restriction-level'           => 'רמת ההגבלה:',
'minimum-size'                => 'גודל מינימלי',
'maximum-size'                => 'גודל מקסימלי:',
'pagesize'                    => '(בבתים)',

# Restrictions (nouns)
'restriction-edit'   => 'עריכה',
'restriction-move'   => 'העברה',
'restriction-create' => 'יצירה',
'restriction-upload' => 'העלאה',

# Restriction levels
'restriction-level-sysop'         => 'הגנה מלאה',
'restriction-level-autoconfirmed' => 'הגנה חלקית',
'restriction-level-all'           => 'כל רמה',

# Undelete
'undelete'                     => 'צפייה בדפים מחוקים',
'undeletepage'                 => 'צפייה ושחזור דפים מחוקים',
'undeletepagetitle'            => "'''זוהי רשימת הגרסאות המחוקות של [[:$1]]'''.",
'viewdeletedpage'              => 'צפייה בדפים מחוקים',
'undeletepagetext'             => '{{PLURAL:$1|הדף שלהלן נמחק, אך הוא עדיין בארכיון וניתן לשחזר אותו|הדפים שלהלן נמחקו, אך הם עדיין בארכיון וניתן לשחזר אותם}}.
ניתן לנקות את הארכיון מעת לעת.',
'undelete-fieldset-title'      => 'שחזור גרסאות',
'undeleteextrahelp'            => "לשחזור היסטוריית הגרסאות המלאה של הדף, אל תסמנו אף תיבת סימון ולחצו על '''{{int:undeletebtn}}'''.
לשחזור של גרסאות מסוימות בלבד, סמנו את תיבות הסימון של הגרסאות הללו, ולחצו על '''{{int:undeletebtn}}'''.
לחיצה על '''{{int:undeletereset}}''' תנקה את התקציר, ואת כל תיבות הסימון.",
'undeleterevisions'            => '{{PLURAL:$1|גרסה אחת נשמרה|$1 גרסאות נשמרו}} בארכיון',
'undeletehistory'              => 'אם תשחזרו את הדף, כל הגרסאות תשוחזרנה להיסטוריית השינויים שלו.
אם יש כבר דף חדש באותו השם, הגרסאות והשינויים יופיעו רק בדף ההיסטוריה שלו.',
'undeleterevdel'               => 'השחזור לא יבוצע אם הגרסה הנוכחית של הדף מחוקה בחלקה. במקרה כזה, עליכם לבטל את ההסתרה של הגרסאות המחוקות החדשות ביותר.',
'undeletehistorynoadmin'       => 'דף זה נמחק. הסיבה למחיקה מוצגת בתקציר מטה, ביחד עם פרטים על המשתמשים שערכו את הדף לפני מחיקתו. הטקסט של גרסאות אלו זמין למפעילי מערכת בלבד.',
'undelete-revision'            => 'גרסה שנמחקה מהדף $1 (מ־$5, $4) מאת $3:',
'undeleterevision-missing'     => 'הגרסה שגויה או חסרה. ייתכן שמדובר בקישור שבור, או שהגרסה שוחזרה או הוסרה מהארכיון.',
'undelete-nodiff'              => 'לא נמצאה גרסה קודמת.',
'undeletebtn'                  => 'שחזור',
'undeletelink'                 => 'צפייה/שחזור',
'undeleteviewlink'             => 'צפייה',
'undeletereset'                => 'איפוס',
'undeleteinvert'               => 'הפיכת הבחירה',
'undeletecomment'              => 'סיבה:',
'undeletedarticle'             => 'שחזר את [[$1]]',
'undeletedrevisions'           => '{{PLURAL:$1|שוחזרה גרסה אחת|שוחזרו $1 גרסאות}}',
'undeletedrevisions-files'     => 'שחזר {{PLURAL:$1|גרסה אחת|$1 גרסאות}} ו{{PLURAL:$2|קובץ אחד|־$2 קבצים}}',
'undeletedfiles'               => 'שחזר {{PLURAL:$1|קובץ אחד|$1 קבצים}}',
'cannotundelete'               => 'השחזור נכשל; ייתכן שמישהו אחר כבר שחזר את הדף.',
'undeletedpage'                => "'''הדף $1 שוחזר בהצלחה.'''

ראו את [[Special:Log/delete|יומן המחיקות]] לרשימה של מחיקות ושחזורים אחרונים.",
'undelete-header'              => 'ראו את [[Special:Log/delete|יומן המחיקות]] לדפים שנמחקו לאחרונה.',
'undelete-search-box'          => 'חיפוש דפים שנמחקו',
'undelete-search-prefix'       => 'הצגת דפים החל מ:',
'undelete-search-submit'       => 'חיפוש',
'undelete-no-results'          => 'לא נמצאו דפים תואמים בארכיון המחיקות.',
'undelete-filename-mismatch'   => 'שחזור גרסת הקובץ מהתאריך $1 נכשל: שם קובץ לא תואם',
'undelete-bad-store-key'       => 'שחזור גרסת הקובץ מהתאריך $1 נכשל: הקובץ היה חסר לפני המחיקה.',
'undelete-cleanup-error'       => 'שגיאת בעת מחיקת קובץ הארכיון "$1" שאינו בשימוש.',
'undelete-missing-filearchive' => 'שחזור קובץ הארכיון שמספרו $1 נכשל כיוון שהוא אינו בבסיס הנתונים. ייתכן שהוא כבר שוחזר.',
'undelete-error-short'         => 'שגיאה בשחזור הקובץ: $1',
'undelete-error-long'          => 'שגיאות שאירעו בעת שחזור הקובץ:

$1',
'undelete-show-file-confirm'   => 'האם אתם בטוחים שברצונכם לצפות בגרסה המחוקה של הקובץ "<nowiki>$1</nowiki>" מ־$3, $2?',
'undelete-show-file-submit'    => 'כן',

# Namespace form on various pages
'namespace'      => 'מרחב שם:',
'invert'         => 'ללא מרחב זה',
'blanknamespace' => '(ראשי)',

# Contributions
'contributions'       => 'תרומות המשתמש',
'contributions-title' => 'תרומות של המשתמש $1',
'mycontris'           => 'התרומות שלי',
'contribsub2'         => 'עבור $1 ($2)',
'nocontribs'          => 'לא נמצאו שינויים המתאימים לקריטריונים אלו.',
'uctop'               => '(אחרון)',
'month'               => 'עד החודש:',
'year'                => 'עד השנה:',

'sp-contributions-newbies'             => 'הצגת תרומות של משתמשים חדשים בלבד',
'sp-contributions-newbies-sub'         => 'עבור משתמשים חדשים',
'sp-contributions-newbies-title'       => 'תרומות של משתמשים חדשים',
'sp-contributions-blocklog'            => 'יומן חסימות',
'sp-contributions-deleted'             => 'תרומות משתמש מחוקות',
'sp-contributions-uploads'             => 'העלאות',
'sp-contributions-logs'                => 'יומנים',
'sp-contributions-talk'                => 'שיחה',
'sp-contributions-userrights'          => 'ניהול הרשאות משתמש',
'sp-contributions-blocked-notice'      => 'משתמש זה חסום כרגע.
הפעולה האחרונה ביומן החסימות מוצגת להלן:',
'sp-contributions-blocked-notice-anon' => 'כתובת IP זו חסומה כרגע.
הפעולה האחרונה ביומן החסימות מוצגת להלן:',
'sp-contributions-search'              => 'חיפוש תרומות',
'sp-contributions-username'            => 'שם משתמש או כתובת IP:',
'sp-contributions-toponly'             => 'הצגת עריכות שהן הגרסאות האחרונות בלבד',
'sp-contributions-submit'              => 'חיפוש',

# What links here
'whatlinkshere'            => 'דפים המקושרים לכאן',
'whatlinkshere-title'      => 'דפים המקשרים לדף $1',
'whatlinkshere-page'       => 'דף:',
'whatlinkshere-backlink'   => '→ $1',
'linkshere'                => "הדפים שלהלן מקושרים לדף '''[[:$1]]''':",
'nolinkshere'              => "אין דפים המקושרים לדף '''[[:$1]]'''.",
'nolinkshere-ns'           => "אין דפים המקושרים לדף '''[[:$1]]''' במרחב השם שנבחר.",
'isredirect'               => 'דף הפניה',
'istemplate'               => 'הכללה',
'isimage'                  => 'קישור לקובץ',
'whatlinkshere-prev'       => '{{PLURAL:$1|הקודם|$1 הקודמים}}',
'whatlinkshere-next'       => '{{PLURAL:$1|הבא|$1 הבאים}}',
'whatlinkshere-links'      => '→ קישורים',
'whatlinkshere-hideredirs' => '$1 הפניות',
'whatlinkshere-hidetrans'  => '$1 הכללות',
'whatlinkshere-hidelinks'  => '$1 קישורים',
'whatlinkshere-hideimages' => '$1 קישורים לקובץ',
'whatlinkshere-filters'    => 'מסננים',

# Block/unblock
'blockip'                         => 'חסימת משתמש',
'blockip-title'                   => 'חסימת משתמש',
'blockip-legend'                  => 'חסימת משתמש',
'blockiptext'                     => 'השתמשו בטופס שלהלן כדי לחסום את הרשאות הכתיבה ממשתמש או כתובת IP ספציפיים.

חסימות כאלה צריכות להתבצע אך ורק כדי למנוע ונדליזם, ובהתאם ל[[{{MediaWiki:Policy-url}}|נהלים]].

אנא פרטו את הסיבה הספציפית לחסימה להלן (לדוגמה, ציון דפים ספציפיים אותם השחית המשתמש).',
'ipaddress'                       => 'כתובת IP:',
'ipadressorusername'              => 'כתובת IP או שם משתמש:',
'ipbexpiry'                       => 'פקיעה:',
'ipbreason'                       => 'סיבה:',
'ipbreasonotherlist'              => 'סיבה אחרת',
'ipbreason-dropdown'              => "* סיבות חסימה נפוצות
** הוספת מידע שגוי
** הסרת תוכן מדפים
** הצפת קישורים לאתרים חיצוניים
** הוספת שטויות/ג'יבריש לדפים
** התנהגות מאיימת/הטרדה
** שימוש לרעה בחשבונות מרובים
** שם משתמש בעייתי",
'ipbanononly'                     => 'חסימה של משתמשים אנונימיים בלבד',
'ipbcreateaccount'                => 'חסימה של יצירת חשבונות',
'ipbemailban'                     => 'חסימה של שליחת דואר אלקטרוני',
'ipbenableautoblock'              => 'חסימה גם של כתובת ה־IP שלו וכל כתובת IP אחרת שישתמש בה',
'ipbsubmit'                       => 'חסימה',
'ipbother'                        => 'זמן אחר:',
'ipboptions'                      => 'שעתיים:2 hours,יום:1 day,שלושה ימים:3 days,שבוע:1 week,שבועיים:2 weeks,חודש:1 month,שלושה חודשים:3 months,שישה חודשים:6 months,שנה:1 year,לצמיתות:infinite',
'ipbotheroption'                  => 'אחר',
'ipbotherreason'                  => 'סיבה אחרת/נוספת:',
'ipbhidename'                     => 'הסתרת שם המשתמש מהעריכות ומהרשימות',
'ipbwatchuser'                    => 'מעקב אחרי דפי המשתמש והשיחה של משתמש זה',
'ipballowusertalk'                => 'מתן אפשרות למשתמש לערוך את דף השיחה של עצמו בעת החסימה',
'ipb-change-block'                => 'חסימת המשתמש מחדש עם הגדרות אלה',
'badipaddress'                    => 'משתמש או כתובת IP שגויים.',
'blockipsuccesssub'               => 'החסימה הושלמה בהצלחה',
'blockipsuccesstext'              => 'המשתמש [[Special:Contributions/$1|$1]] נחסם.

ראו את [[Special:IPBlockList|רשימת המשתמשים החסומים]] כדי לצפות בחסימות.',
'ipb-edit-dropdown'               => 'עריכת סיבות החסימה',
'ipb-unblock-addr'                => 'הסרת חסימה של $1',
'ipb-unblock'                     => 'הסרת חסימה של שם משתמש או כתובת IP',
'ipb-blocklist'                   => 'הצגת החסימות הנוכחיות',
'ipb-blocklist-contribs'          => 'התרומות של $1',
'unblockip'                       => 'שחרור חסימה',
'unblockiptext'                   => 'השתמשו בטופס שלהלן כדי להחזיר את הרשאות הכתיבה למשתמש או כתובת IP חסומים.',
'ipusubmit'                       => 'שחרור חסימה',
'unblocked'                       => 'המשתמש [[User:$1|$1]] שוחרר מחסימתו.',
'unblocked-id'                    => 'חסימה מספר $1 שוחררה.',
'ipblocklist'                     => 'רשימת כתובות IP ומשתמשים חסומים',
'ipblocklist-legend'              => 'מציאת משתמש חסום',
'ipblocklist-username'            => 'שם משתמש או כתובת IP:',
'ipblocklist-sh-userblocks'       => '$1 חסימות של חשבונות',
'ipblocklist-sh-tempblocks'       => '$1 חסימות זמניות',
'ipblocklist-sh-addressblocks'    => '$1 חסימות של כתובות IP יחידות',
'ipblocklist-submit'              => 'חיפוש',
'ipblocklist-localblock'          => 'חסימה מקומית',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|חסימה אחרת|חסימות אחרות}}',
'blocklistline'                   => '$1 $2 חסם את $3 ($4)',
'infiniteblock'                   => 'לצמיתות',
'expiringblock'                   => 'פוקע ב־$2, $1',
'anononlyblock'                   => 'משתמשים אנונימיים בלבד',
'noautoblockblock'                => 'חסימה אוטומטית מבוטלת',
'createaccountblock'              => 'יצירת חשבונות נחסמה',
'emailblock'                      => 'שליחת דוא"ל נחסמה',
'blocklist-nousertalk'            => 'עריכת דף השיחה האישי נחסמה',
'ipblocklist-empty'               => 'רשימת המשתמשים החסומים ריקה.',
'ipblocklist-no-results'          => 'שם המשתמש או כתובת ה־IP המבוקשים אינם חסומים.',
'blocklink'                       => 'חסימה',
'unblocklink'                     => 'שחרור חסימה',
'change-blocklink'                => 'שינוי חסימה',
'contribslink'                    => 'תרומות',
'autoblocker'                     => 'נחסמתם באופן אוטומטי משום שאתם חולקים את כתובת ה־IP שלכם עם [[User:$1|$1]]. הנימוק לחסימה: "$2".',
'blocklogpage'                    => 'יומן חסימות',
'blocklog-showlog'                => 'משתמש זה נחסם בעבר. יומן החסימות מוצג למטה:',
'blocklog-showsuppresslog'        => 'משתמש זה נחסם והוסתר בעבר. יומן ההסתרות מוצג למטה:',
'blocklogentry'                   => 'חסם את [[$1]] למשך $2 $3',
'reblock-logentry'                => 'שינה את הגדרות החסימה של [[$1]] עם זמן פקיעה של $2 $3',
'blocklogtext'                    => 'זהו יומן פעולות החסימה והשחרור של משתמשים. כתובות IP הנחסמות באופן אוטומטי אינן מופיעות.

ראו גם את [[Special:IPBlockList|רשימת המשתמשים החסומים]] הנוכחית.',
'unblocklogentry'                 => 'שחרר את $1',
'block-log-flags-anononly'        => 'משתמשים אנונימיים בלבד',
'block-log-flags-nocreate'        => 'יצירת חשבונות נחסמה',
'block-log-flags-noautoblock'     => 'חסימה אוטומטית מבוטלת',
'block-log-flags-noemail'         => 'שליחת דוא"ל נחסמה',
'block-log-flags-nousertalk'      => 'עריכת דף השיחה האישי נחסמה',
'block-log-flags-angry-autoblock' => 'חסימה אוטומטית משוכללת מופעלת',
'block-log-flags-hiddenname'      => 'שם המשתמש הוסתר',
'range_block_disabled'            => 'האפשרות לחסום טווח כתובות אינה פעילה.',
'ipb_expiry_invalid'              => 'זמן פקיעת חסימה בלתי חוקי',
'ipb_expiry_temp'                 => 'חסימות הכוללות הסתרת שם משתמש חייבות להיות לצמיתות.',
'ipb_hide_invalid'                => 'לא ניתן להסתיר שם משתמש זה; ייתכן שבוצעו ממנו יותר מדי עריכות.',
'ipb_already_blocked'             => 'המשתמש "$1" כבר נחסם',
'ipb-needreblock'                 => '== כבר נחסם ==
$1 כבר נחסם. האם ברצונכם לשנות את הגדרות החסימה?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|חסימה אחרת|חסימות אחרות}}',
'ipb_cant_unblock'                => 'שגיאה: חסימה מספר $1 לא נמצאה. ייתכן שהיא כבר שוחררה.',
'ipb_blocked_as_range'            => 'שגיאה: כתובת ה־IP $1 אינה חסומה ישירות ולכן לא ניתן לשחרר את חסימתה. עם זאת, היא חסומה כחלק מהטווח $2, שניתן לשחרר את חסימתו.',
'ip_range_invalid'                => 'טווח IP שגוי.',
'ip_range_toolarge'               => 'לא ניתן לחסום טווחים גדולים מ־/$1.',
'blockme'                         => 'חסום אותי',
'proxyblocker'                    => 'חוסם פרוקסי',
'proxyblocker-disabled'           => 'אפשרות זו מבוטלת.',
'proxyblockreason'                => 'כתובת ה־IP שלכם נחסמה משום שהיא כתובת פרוקסי פתוחה. אנא צרו קשר עם ספק האינטרנט שלכם והודיעו לו על בעיית האבטחה החמורה הזו.',
'proxyblocksuccess'               => 'בוצע.',
'sorbsreason'                     => 'כתובת ה־IP שלכם רשומה ככתובת פרוקסי פתוחה ב־DNSBL שאתר זה משתמש בו.',
'sorbs_create_account_reason'     => 'כתובת ה־IP שלכם רשומה ככתובת פרוקסי פתוחה ב־DNSBL שאתר זה משתמש בו. אינכם יכולים ליצור חשבון.',
'cant-block-while-blocked'        => 'אינכם יכולים לחסום משתמשים אחרים כשאתם חסומים.',
'cant-see-hidden-user'            => 'המשתמש שאתם מנסים לחסום כבר נחסם והוסתר. כיוון שאין לכם את ההרשאה לחסימת משתמש והסתרתו, אינכם רשאים לצפות בחסימת המשתמש או לערוך אותה.',
'ipbblocked'                      => 'אינכם יכולים לחסום או לשחרר את חסימתם של משתמשים אחרים, כיוון שאתם עצמכם חסומים',
'ipbnounblockself'                => 'אינכם רשאים לשחרר את חסימתכם',

# Developer tools
'lockdb'              => 'נעילת בסיס נתונים',
'unlockdb'            => 'שחרור בסיס נתונים',
'lockdbtext'          => 'נעילת בסיס הנתונים תמנע ממשתמשים את האפשרות לערוך דפים, לשנות את העדפותיהם, לערוך את רשימות המעקב שלהם, ופעולות אחרות הדורשות ביצוע שינויים בבסיס הנתונים.

אנא אשרו שזה מה שאתם מתכוונים לעשות, ושתשחררו את בסיס הנתונים מנעילה כאשר פעולת התחזוקה תסתיים.',
'unlockdbtext'        => 'שחרור בסיס הנתונים מנעילה יחזיר למשתמשים את היכולת לערוך דפים, לשנות את העדפותיהם, לערוך את רשימות המעקב שלהם, ולבצע פעולות אחרות הדורשות ביצוע שינויים בבסיס הנתונים
אנא אשרו שזה מה שבכוונתכם לעשות.',
'lockconfirm'         => 'כן, ברצוני לנעול את בסיס הנתונים.',
'unlockconfirm'       => 'כן, ברצוני לשחרר את בסיס הנתונים מנעילה.',
'lockbtn'             => 'נעילת בסיס הנתונים',
'unlockbtn'           => 'שחרור בסיס הנתונים מנעילה',
'locknoconfirm'       => 'לא סימנתם את תיבת האישור.',
'lockdbsuccesssub'    => 'נעילת בסיס הנתונים הושלמה בהצלחה',
'unlockdbsuccesssub'  => 'שוחררה הנעילה מבסיס הנתונים',
'lockdbsuccesstext'   => 'בסיס הנתונים ננעל.

זכרו [[Special:UnlockDB|לשחרר את הנעילה]] לאחר שפעולת התחזוקה הסתיימה.',
'unlockdbsuccesstext' => 'שוחררה הנעילה של בסיס הנתונים',
'lockfilenotwritable' => 'קובץ נעילת בסיס הנתונים אינו ניתן לכתיבה. כדי שאפשר יהיה לנעול את בסיס הנתונים או לבטל את נעילתו, שרת האינטרנט צריך לקבל הרשאות לכתוב אליו.',
'databasenotlocked'   => 'בסיס הנתונים אינו נעול.',

# Move page
'move-page'                    => 'העברת $1',
'move-page-backlink'           => '→ $1',
'move-page-legend'             => 'העברת דף',
'movepagetext'                 => "שימוש בטופס שלהלן ישנה את שמו של דף, ויעביר את כל ההיסטוריה שלו לשם חדש.
השם הישן יהפוך לדף הפניה אל הדף עם השם החדש.
באפשרותכם לעדכן אוטומטית דפי הפניה לכותרת המקורית.
אם תבחרו לא לעשות זאת, אנא ודאו שאין [[Special:DoubleRedirects|הפניות כפולות]] או [[Special:BrokenRedirects|שבורות]].
אתם אחראים לוודא שכל הקישורים ימשיכו להצביע למקום שאליו הם אמורים להצביע.

שימו לב: הדף '''לא''' יועבר אם כבר יש דף תחת השם החדש, אלא אם הדף הזה ריק, או שהוא הפניה, ואין לו היסטוריית עריכות קודמות.
פירוש הדבר שאפשר לשנות חזרה את שמו של דף לשם המקורי אם נעשתה טעות, ושלא ניתן לדרוס דף קיים.

'''אזהרה!'''
שינוי זה עשוי להיות שינוי דרסטי ובלתי צפוי לדף פופולרי;
אנא ודאו שאתם מבינים את השלכות המעשה לפני שאתם ממשיכים.",
'movepagetext-noredirectfixer' => "שימוש בטופס שלהלן ישנה את שמו של דף, ויעביר את כל ההיסטוריה שלו לשם חדש.
השם הישן יהפוך לדף הפניה אל הדף עם השם החדש.
אנא ודאו שאין [[Special:DoubleRedirects|הפניות כפולות]] או [[Special:BrokenRedirects|שבורות]].
אתם אחראים לוודא שכל הקישורים ימשיכו להצביע למקום שאליו הם אמורים להצביע.

שימו לב: הדף '''לא''' יועבר אם כבר יש דף תחת השם החדש, אלא אם הדף הזה ריק, או שהוא הפניה, ואין לו היסטוריית עריכות קודמות.
פירוש הדבר שאפשר לשנות חזרה את שמו של דף לשם המקורי אם נעשתה טעות, ושלא ניתן לדרוס דף קיים.

'''אזהרה!'''
שינוי זה עשוי להיות שינוי דרסטי ובלתי צפוי לדף פופולרי;
אנא ודאו שאתם מבינים את השלכות המעשה לפני שאתם ממשיכים.",
'movepagetalktext'             => 'דף השיחה של דף זה יועבר אוטומטית, אלא אם:
* קיים דף שיחה שאינו ריק תחת השם החדש אליו מועבר הדף, או
* הסרתם את הסימון בתיבה שלהלן.

במקרים אלו, תצטרכו להעביר או לשלב את הדפים באופן ידני, אם תרצו.',
'movearticle'                  => 'העברת דף:',
'moveuserpage-warning'         => "'''אזהרה:''' אתם עומדים להעביר דף משתמש. שימו לב שרק הדף יועבר וששם המשתמש '''לא''' ישתנה.",
'movenologin'                  => 'לא נכנסתם לאתר',
'movenologintext'              => 'עליכם [[Special:UserLogin|להיכנס לחשבון]] כדי להעביר דפים.',
'movenotallowed'               => 'אינכם מורשים להעביר דפים.',
'movenotallowedfile'           => 'אינכם מורשים להעביר קבצים.',
'cant-move-user-page'          => 'אינכם מורשים להעביר דפי משתמש (למעט דפי משנה).',
'cant-move-to-user-page'       => 'אינכם מורשים להעביר דף לדף משתמש (למעט לדף משנה של דף משתמש).',
'newtitle'                     => 'לשם החדש:',
'move-watch'                   => 'מעקב אחרי דף המקור ואחרי דף היעד',
'movepagebtn'                  => 'העברה',
'pagemovedsub'                 => 'ההעברה הושלמה בהצלחה',
'movepage-moved'               => 'הדף "$1" הועבר לשם "$2".',
'movepage-moved-redirect'      => 'נוצרה הפניה.',
'movepage-moved-noredirect'    => 'יצירת ההפניה בוטלה.',
'articleexists'                => 'קיים כבר דף עם אותו שם, או שהשם שבחרתם אינו חוקי.
אנא בחרו שם אחר.',
'cantmove-titleprotected'      => 'אינכם יכולים להעביר את הדף לשם זה, כיוון שהשם החדש מוגן מפני יצירה',
'talkexists'                   => 'הדף עצמו הועבר בהצלחה, אבל דף השיחה לא הועבר כיוון שקיים כבר דף שיחה במיקום החדש. אנא מזגו אותם ידנית.',
'movedto'                      => 'הועבר ל',
'movetalk'                     => 'העברה גם של דף השיחה',
'move-subpages'                => 'העברת כל דפי המשנה (עד $1)',
'move-talk-subpages'           => 'העברת כל דפי המשנה של דף השיחה (עד $1)',
'movepage-page-exists'         => 'הדף $1 קיים כבר ולא ניתן לדרוס אותו אוטומטית.',
'movepage-page-moved'          => 'הדף $1 הועבר לשם $2.',
'movepage-page-unmoved'        => 'לא ניתן להעביר את הדף $1 לשם $2.',
'movepage-max-pages'           => 'המספר המקסימלי של {{PLURAL:$1|דף אחד|$1 דפים}} כבר הועבר ולא ניתן להעביר דפים נוספים אוטומטית.',
'1movedto2'                    => '[[$1]] הועבר ל[[$2]]',
'1movedto2_redir'              => '[[$1]] הועבר ל[[$2]] במקום הפניה',
'move-redirect-suppressed'     => 'לא נוצרה הפניה',
'movelogpage'                  => 'יומן העברות',
'movelogpagetext'              => 'להלן רשימה של כל הדפים שהועברו.',
'movesubpage'                  => '{{PLURAL:$1|דף משנה|דפי משנה}}',
'movesubpagetext'              => 'לדף זה יש {{PLURAL:$1|דף משנה אחד המוצג להלן|$1 דפי משנה המוצגים להלן}}.',
'movenosubpage'                => 'לדף זה אין דפי משנה.',
'movereason'                   => 'סיבה:',
'revertmove'                   => 'החזרה',
'delete_and_move'              => 'מחיקה והעברה',
'delete_and_move_text'         => '== בקשת מחיקה ==
דף היעד, [[:$1]], כבר קיים. האם ברצונכם למחוק אותו כדי לאפשר את ההעברה?',
'delete_and_move_confirm'      => 'אישור מחיקת הדף',
'delete_and_move_reason'       => 'מחיקה על מנת לאפשר העברה',
'selfmove'                     => 'כותרות המקור והיעד זהות; לא ניתן להעביר דף לעצמו.',
'immobile-source-namespace'    => 'לא ניתן להעביר דפים במרחב השם "$1"',
'immobile-target-namespace'    => 'לא ניתן להעביר דפים למרחב השם "$1"',
'immobile-target-namespace-iw' => 'קישור אינטרוויקי אינו יעד תקין להעברת דף.',
'immobile-source-page'         => 'דף זה אינו ניתן להעברה.',
'immobile-target-page'         => 'לא ניתן להעביר אל כותרת יעד זו.',
'imagenocrossnamespace'        => 'לא ניתן להעביר קובץ למרחב שם אחר',
'nonfile-cannot-move-to-file'  => 'לא ניתן להעביר דף שאינו קובץ למרחב קובץ',
'imagetypemismatch'            => 'סיומת הקובץ החדשה אינה מתאימה לסוג הקובץ',
'imageinvalidfilename'         => 'שם קובץ היעד אינו תקין',
'fix-double-redirects'         => 'עדכון הפניות לכותרת הדף המקורית',
'move-leave-redirect'          => 'השארת הפניה בדף המקורי',
'protectedpagemovewarning'     => "'''אזהרה:''' דף זה מוגן כך שרק מפעילי מערכת יכולים להעביר אותו.
פעולת היומן האחרונה מוצגת להלן:",
'semiprotectedpagemovewarning' => "'''הערה:''' דף זה מוגן כך שרק משתמשים רשומים יכולים להעביר אותו.
פעולת היומן האחרונה מוצגת להלן:",
'move-over-sharedrepo'         => '== הקובץ קיים ==
[[:$1]] כבר קיים כקובץ משותף. העברת הקובץ לכותרת זו תדרוס את הקובץ המשותף.',
'file-exists-sharedrepo'       => 'קובץ בשם שנבחר כבר קיים כקובץ משותף.
אנא בחרו שם אחר.',

# Export
'export'            => 'ייצוא דפים',
'exporttext'        => 'באפשרותכם לייצא את התוכן ואת היסטוריית העריכה של דף אחד או של מספר דפים, בתבנית של קובץ XML.
ניתן לייבא את הקובץ למיזם ויקי אחר המשתמש בתוכנת מדיה־ויקי באמצעות [[Special:Import|דף הייבוא]].

כדי לייצא דפים, הקישו את שמותיהם בתיבת הטקסט שלהלן, כל שם בשורה נפרדת, ובחרו האם לייצא גם את הגרסה הנוכחית וגם את היסטוריית השינויים של הדפים, או רק את הגרסה הנוכחית עם מידע על העריכה האחרונה.

בנוסף, ניתן להשתמש בקישור, כגון [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] לדף [[{{MediaWiki:Mainpage}}]] ללא היסטוריית השינויים שלו.',
'exportcuronly'     => 'כלול רק את הגרסה הנוכחית, ללא כל ההיסטוריה',
'exportnohistory'   => "----
'''הערה:''' ייצוא ההיסטוריה המלאה של דפים דרך טופס זה הופסקה עקב בעיות ביצועים.",
'export-submit'     => 'ייצוא',
'export-addcattext' => 'הוספת דפים מהקטגוריה:',
'export-addcat'     => 'הוספה',
'export-addnstext'  => 'הוספת דפים ממרחב השם:',
'export-addns'      => 'הוספה',
'export-download'   => 'שמירה כקובץ',
'export-templates'  => 'כלילת תבניות',
'export-pagelinks'  => 'כלילת דפים מקושרים עד לעומק של:',

# Namespace 8 related
'allmessages'                   => 'הודעות המערכת',
'allmessagesname'               => 'שם',
'allmessagesdefault'            => 'טקסט ברירת המחדל של ההודעה',
'allmessagescurrent'            => 'הטקסט הנוכחי של ההודעה',
'allmessagestext'               => 'זוהי רשימת כל הודעות המערכת שבמרחב השם {{ns:mediawiki}}, המשמשות את ממשק האתר.

מפעילי המערכת יכולים לערוך את ההודעות בלחיצה על שם ההודעה.',
'allmessagesnotsupportedDB'     => 'לא ניתן להשתמש בדף זה כיוון ש־wgUseDatabseMessages מבוטל.',
'allmessages-filter-legend'     => 'מסנן',
'allmessages-filter'            => 'סינון לפי מצב ההודעה:',
'allmessages-filter-unmodified' => 'הודעות שלא שונו',
'allmessages-filter-all'        => 'הכול',
'allmessages-filter-modified'   => 'הודעות ששונו',
'allmessages-prefix'            => 'סינון לפי קידומת:',
'allmessages-language'          => 'שפה:',
'allmessages-filter-submit'     => 'הצגה',

# Thumbnails
'thumbnail-more'           => 'הגדל',
'filemissing'              => 'קובץ חסר',
'thumbnail_error'          => 'שגיאה ביצירת תמונה ממוזערת: $1',
'djvu_page_error'          => 'דף ה־DjVu מחוץ לטווח',
'djvu_no_xml'              => 'לא ניתן היה לקבל את ה־XML עבור קובץ ה־DjVu',
'thumbnail_invalid_params' => 'פרמטרים שגויים לתמונה הממוזערת',
'thumbnail_dest_directory' => 'לא ניתן היה ליצור את תיקיית היעד',
'thumbnail_image-type'     => 'סוג התמונה אינו נתמך',
'thumbnail_gd-library'     => 'הגדרת הספריה GD אינה שלמה: חסרה הפונקציה $1',
'thumbnail_image-missing'  => 'נראה שהקובץ הבא חסר: $1',

# Special:Import
'import'                     => 'ייבוא דפים',
'importinterwiki'            => 'ייבוא בין־אתרי',
'import-interwiki-text'      => 'אנא בחרו אתר ויקי ואת כותרת הדף לייבוא.
תאריכי ועורכי הגרסאות יישמרו בעת הייבוא.
כל פעולות הייבוא הבין־אתרי נשמרות ב[[Special:Log/import|יומן הייבוא]].',
'import-interwiki-source'    => 'אתר/דף המקור:',
'import-interwiki-history'   => 'העתקת כל היסטוריית העריכות של דף זה',
'import-interwiki-templates' => 'ייבוא גם של כל התבניות המוכללות בדף',
'import-interwiki-submit'    => 'ייבוא',
'import-interwiki-namespace' => 'העתקה למרחב השם:',
'import-upload-filename'     => 'שם הקובץ:',
'import-comment'             => 'הערה:',
'importtext'                 => 'אנא ייצאו את הקובץ מאתר המקור תוך שימוש ב[[Special:Export|כלי הייצוא]], שמרו אותו לדיסק הקשיח שלכם והעלו אותו לכאן.',
'importstart'                => 'מייבא דפים…',
'import-revision-count'      => '{{PLURAL:$1|גרסה אחת|$1 גרסאות}}',
'importnopages'              => 'אין דפים לייבוא.',
'imported-log-entries'       => '{{PLURAL:$1|יובאה פעולת יומן אחת|יובאו $1 פעולות יומן}}.',
'importfailed'               => 'הייבוא נכשל: <nowiki>$1</nowiki>',
'importunknownsource'        => 'סוג ייבוא בלתי ידוע',
'importcantopen'             => 'פתיחת קובץ הייבוא נכשלה',
'importbadinterwiki'         => 'קישור בינוויקי שגוי',
'importnotext'               => 'ריק או חסר טקסט',
'importsuccess'              => 'הייבוא הושלם בהצלחה!',
'importhistoryconflict'      => 'ישנה התנגשות עם ההיסטוריה הקיימת של הדף (ייתכן שהדף יובא בעבר)',
'importnosources'            => 'אין מקורות לייבוא בין־אתרי, וייבוא ישיר של דף עם היסטוריה אינו מאופשר כעת.',
'importnofile'               => 'לא הועלה קובץ ייבוא.',
'importuploaderrorsize'      => 'העלאת קובץ הייבוא נכשלה. הקובץ היה גדול יותר מגודל ההעלאה המותר.',
'importuploaderrorpartial'   => 'העלאת קובץ הייבוא נכשלה. הקובץ הועלה באופן חלקי בלבד.',
'importuploaderrortemp'      => 'העלאת קובץ הייבוא נכשלה. חסרה תיקייה זמנית.',
'import-parse-failure'       => 'שגיאה בפענוח ה־XML',
'import-noarticle'           => 'אין דף לייבוא!',
'import-nonewrevisions'      => 'כל הגרסאות יובאו בעבר.',
'xml-error-string'           => '$1 בשורה $2, עמודה $3 (בייט מספר $4): $5',
'import-upload'              => 'העלאת קובץ XML',
'import-token-mismatch'      => 'מידע הכניסה אבד. אנא נסו שוב.',
'import-invalid-interwiki'   => 'לא ניתן לייבא מאתר הוויקי שציינתם.',

# Import log
'importlogpage'                    => 'יומן ייבוא',
'importlogpagetext'                => 'ייבוא מנהלי של דפים (כולל היסטוריית העריכות שלהם) מאתרי ויקי אחרים.',
'import-logentry-upload'           => 'ייבא את [[$1]] באמצעות העלאת קובץ',
'import-logentry-upload-detail'    => '{{PLURAL:$1|גרסה אחת|$1 גרסאות}}',
'import-logentry-interwiki'        => 'ייבא את $1 בייבוא בין־אתרי',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|גרסה אחת|$1 גרסאות}} של הדף $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'דף המשתמש שלכם',
'tooltip-pt-anonuserpage'         => 'דף המשתמש של משתמש אנונימי זה',
'tooltip-pt-mytalk'               => 'דף השיחה שלכם',
'tooltip-pt-anontalk'             => 'שיחה על תרומות המשתמש האנונימי',
'tooltip-pt-preferences'          => 'ההעדפות שלכם',
'tooltip-pt-watchlist'            => 'רשימת הדפים שאתם עוקבים אחרי השינויים בהם',
'tooltip-pt-mycontris'            => 'רשימת התרומות שלכם',
'tooltip-pt-login'                => 'מומלץ להירשם, אך אין חובה לעשות כן',
'tooltip-pt-anonlogin'            => 'מומלץ להירשם, אך אין חובה לעשות כן',
'tooltip-pt-logout'               => 'יציאה מהחשבון',
'tooltip-ca-talk'                 => 'שיחה על דף זה',
'tooltip-ca-edit'                 => 'באפשרותכם לערוך דף זה. אנא השתמשו בלחצן "תצוגה מקדימה" לפני השמירה',
'tooltip-ca-addsection'           => 'הוספת פסקה חדשה',
'tooltip-ca-viewsource'           => 'זהו דף מוגן, אך באפשרותכם לצפות במקורו',
'tooltip-ca-history'              => 'גרסאות קודמות של דף זה',
'tooltip-ca-protect'              => 'הגנה על דף זה',
'tooltip-ca-unprotect'            => 'הסרת ההגנה על דף זה',
'tooltip-ca-delete'               => 'מחיקת דף זה',
'tooltip-ca-undelete'             => 'שחזור עריכות שנעשו בדף זה לפני שנמחק',
'tooltip-ca-move'                 => 'העברת דף זה',
'tooltip-ca-watch'                => 'הוספת דף זה לרשימת המעקב',
'tooltip-ca-unwatch'              => 'הסרת דף זה מרשימת המעקב',
'tooltip-search'                  => 'חיפוש ב{{grammar:תחילית|{{SITENAME}}}}',
'tooltip-search-go'               => 'מעבר לדף בשם הזה בדיוק, אם הוא קיים',
'tooltip-search-fulltext'         => 'חיפוש טקסט זה בדפים',
'tooltip-p-logo'                  => 'ביקור בעמוד הראשי',
'tooltip-n-mainpage'              => 'ביקור בעמוד הראשי',
'tooltip-n-mainpage-description'  => 'ביקור בעמוד הראשי',
'tooltip-n-portal'                => 'אודות המיזם, איך תוכלו לעזור, איפה למצוא דברים',
'tooltip-n-currentevents'         => 'מציאת מידע רקע על האירועים האחרונים',
'tooltip-n-recentchanges'         => 'רשימת השינויים האחרונים באתר',
'tooltip-n-randompage'            => 'צפייה בדף תוכן אקראי',
'tooltip-n-help'                  => 'עזרה בשימוש באתר',
'tooltip-t-whatlinkshere'         => 'רשימת כל הדפים המקושרים לכאן',
'tooltip-t-recentchangeslinked'   => 'השינויים האחרונים שבוצעו בדפים המקושרים לכאן',
'tooltip-feed-rss'                => 'הוספת עדכון אוטומטי על ידי RSS',
'tooltip-feed-atom'               => 'הוספת עדכון אוטומטי על ידי Atom',
'tooltip-t-contributions'         => 'צפייה בתרומותיו של משתמש זה',
'tooltip-t-emailuser'             => 'שליחת דואר אלקטרוני למשתמש זה',
'tooltip-t-upload'                => 'העלאת קבצים',
'tooltip-t-specialpages'          => 'רשימת כל הדפים המיוחדים',
'tooltip-t-print'                 => 'גרסה להדפסה של דף זה',
'tooltip-t-permalink'             => 'קישור קבוע לגרסה זו של הדף',
'tooltip-ca-nstab-main'           => 'צפייה בדף התוכן',
'tooltip-ca-nstab-user'           => 'צפייה בדף המשתמש',
'tooltip-ca-nstab-media'          => 'צפייה בפריט המדיה',
'tooltip-ca-nstab-special'        => 'זהו דף מיוחד, אי אפשר לערוך אותו',
'tooltip-ca-nstab-project'        => 'צפייה בדף המיזם',
'tooltip-ca-nstab-image'          => 'צפייה בדף הקובץ',
'tooltip-ca-nstab-mediawiki'      => 'צפייה בהודעת המערכת',
'tooltip-ca-nstab-template'       => 'צפייה בתבנית',
'tooltip-ca-nstab-help'           => 'צפייה בדף העזרה',
'tooltip-ca-nstab-category'       => 'צפייה בדף הקטגוריה',
'tooltip-minoredit'               => 'סימון עריכה זו כמשנית',
'tooltip-save'                    => 'שמירת השינויים שביצעתם',
'tooltip-preview'                 => 'תצוגה מקדימה, אנא השתמשו באפשרות זו לפני השמירה!',
'tooltip-diff'                    => 'צפייה בשינויים שערכתם בטקסט',
'tooltip-compareselectedversions' => 'צפייה בהשוואת שתי גרסאות של דף זה',
'tooltip-watch'                   => 'הוספת דף זה לרשימת המעקב',
'tooltip-recreate'                => 'יצירת הדף מחדש למרות שהוא נמחק',
'tooltip-upload'                  => 'התחלת ההעלאה',
'tooltip-rollback'                => 'שחזור בלחיצה אחת של העריכה או העריכות של התורם האחרון לדף זה',
'tooltip-undo'                    => 'פתיחת חלון העריכה במצב תצוגה מקדימה כדי לשחזר את העריכה, תוך אפשרות להוספת סיבה בתקציר העריכה',
'tooltip-preferences-save'        => 'שמירת ההעדפות',
'tooltip-summary'                 => 'להכנסת תקציר קצר',

# Stylesheets
'common.css'      => '/* הסגנונות הנכתבים כאן ישפיעו על כל העיצובים */',
'standard.css'    => '/* הסגנונות הנכתבים כאן ישפיעו על העיצוב Standard בלבד */',
'nostalgia.css'   => '/* הסגנונות הנכתבים כאן ישפיעו על העיצוב Nostalgia בלבד */',
'cologneblue.css' => '/* הסגנונות הנכתבים כאן ישפיעו על העיצוב CologneBlue בלבד */',
'monobook.css'    => '/* הסגנונות הנכתבים כאן ישפיעו על העיצוב Monobook בלבד */',
'myskin.css'      => '/* הסגנונות הנכתבים כאן ישפיעו על העיצוב MySkin בלבד */',
'chick.css'       => '/* הסגנונות הנכתבים כאן ישפיעו על העיצוב Chick בלבד */',
'simple.css'      => '/* הסגנונות הנכתבים כאן ישפיעו על העיצוב Simple בלבד */',
'modern.css'      => '/* הסגנונות הנכתבים כאן ישפיעו על העיצוב Modern בלבד */',
'vector.css'      => '/* הסגנונות הנכתבים כאן ישפיעו על העיצוב Vector בלבד */',
'print.css'       => '/* הסגנונות הנכתבים כאן ישפיעו על הפלט בהדפסה בלבד */',
'handheld.css'    => '/* הסגנונות הנכתבים כאן ישפיעו על מכשירים ניידים המבוססים על העיצוב שבהגדרה $wgHandheldStyle בלבד */',

# Scripts
'common.js'      => '/* כל סקריפט JavaScript שנכתב כאן ירוץ עבור כל המשתמשים בכל טעינת עמוד */',
'standard.js'    => '/* כל סקריפט JavaScript שנכתב כאן ירוץ רק עבור המשתמשים בעיצוב Standard */',
'nostalgia.js'   => '/* כל סקריפט JavaScript שנכתב כאן ירוץ רק עבור המשתמשים בעיצוב Nostalgia */',
'cologneblue.js' => '/* כל סקריפט JavaScript שנכתב כאן ירוץ רק עבור המשתמשים בעיצוב CologneBlue */',
'monobook.js'    => '/* כל סקריפט JavaScript שנכתב כאן ירוץ רק עבור המשתמשים בעיצוב Monobook */',
'myskin.js'      => '/* כל סקריפט JavaScript שנכתב כאן ירוץ רק עבור המשתמשים בעיצוב MySkin */',
'chick.js'       => '/* כל סקריפט JavaScript שנכתב כאן ירוץ רק עבור המשתמשים בעיצוב Chick */',
'simple.js'      => '/* כל סקריפט JavaScript שנכתב כאן ירוץ רק עבור המשתמשים בעיצוב Simple */',
'modern.js'      => '/* כל סקריפט JavaScript שנכתב כאן ירוץ רק עבור המשתמשים בעיצוב Modern */',
'vector.js'      => '/* כל סקריפט JavaScript שנכתב כאן ירוץ רק עבור המשתמשים בעיצוב Vector */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadata מבוטל בשרת זה.',
'nocreativecommons' => 'Creative Commons RDF metadata מבוטל בשרת זה.',
'notacceptable'     => 'האתר לא יכול לספק מידע בפורמט שתוכנת הלקוח יכולה לקרוא.',

# Attribution
'anonymous'        => '{{PLURAL:$1|משתמש אנונימי|משתמשים אנונימיים}} של {{SITENAME}}',
'siteuser'         => 'משתמש {{SITENAME}} $1',
'anonuser'         => 'משתמש אנונימי של {{SITENAME}} $1',
'lastmodifiedatby' => 'דף זה שונה לאחרונה בתאריך $2, $1 על ידי $3.',
'othercontribs'    => 'מבוסס על העבודה של $1.',
'others'           => 'אחרים',
'siteusers'        => '{{PLURAL:$2|משתמש|משתמשי}} {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|משתמש אנונימי|משתמשים אנונימיים}} של {{SITENAME}} $1',
'creditspage'      => 'קרדיטים בדף',
'nocredits'        => 'אין קרדיטים זמינים בדף זה.',

# Spam protection
'spamprotectiontitle' => 'מנגנון מסנן הספאם',
'spamprotectiontext'  => 'הטקסט אותו רצית לשמור נחסם על ידי מסנן הספאם.
הסיבה לכך היא לרוב קישור לאתר חיצוני הנמצא ברשימה השחורה.',
'spamprotectionmatch' => 'הטקסט הבא הוא שגרם להפעלת סינון הספאם: $1',
'spambot_username'    => 'מנקה הספאם של מדיה ויקי',
'spam_reverting'      => 'שחזור לגרסה אחרונה שלא כוללת קישורים ל־$1',
'spam_blanking'       => 'כל הגרסאות כוללות קישורים ל־$1, מרוקן את הדף',

# Info page
'infosubtitle'   => 'מידע על הדף',
'numedits'       => 'מספר עריכות (דף תוכן): $1',
'numtalkedits'   => 'מספר עריכות (דף שיחה): $1',
'numwatchers'    => 'מספר העוקבים אחרי הדף: $1',
'numauthors'     => 'מספר כותבים נפרדים (דף תוכן): $1',
'numtalkauthors' => 'מספר כותבים נפרדים (דף שיחה): $1',

# Skin names
'skinname-standard'    => 'קלאסי',
'skinname-nostalgia'   => 'נוסטלגיה',
'skinname-cologneblue' => 'מים כחולים',
'skinname-monobook'    => 'מונובוק',
'skinname-myskin'      => 'העיצוב שלי',
'skinname-chick'       => "צ'יק",
'skinname-simple'      => 'פשוט',
'skinname-modern'      => 'מודרני',
'skinname-vector'      => 'וקטור',

# Math options
'mw_math_png'    => 'תמיד הצג כ־PNG',
'mw_math_simple' => 'HTML אם פשוט מאוד, אחרת PNG',
'mw_math_html'   => 'HTML אם אפשר, אחרת PNG',
'mw_math_source' => 'השארה כקוד TeX (לדפדפני טקסט)',
'mw_math_modern' => 'מומלץ לדפדפנים עדכניים',
'mw_math_mathml' => 'MathML אם אפשר (ניסיוני)',

# Math errors
'math_failure'          => 'עיבוד הנוסחה נכשל',
'math_unknown_error'    => 'שגיאה לא ידועה',
'math_unknown_function' => 'פונקציה לא מוכרת',
'math_lexing_error'     => 'שגיאת לקסינג',
'math_syntax_error'     => 'שגיאת תחביר',
'math_image_error'      => 'ההמרה ל־PNG נכשלה; אנא בדקו אם התקנתם נכון את latex, את dvips, את gs ואת convert.',
'math_bad_tmpdir'       => 'התוכנה לא הצליחה לכתוב או ליצור את הספרייה הזמנית של המתמטיקה',
'math_bad_output'       => 'התוכנה לא הצליחה לכתוב או ליצור את ספריית הפלט של המתמטיקה',
'math_notexvc'          => 'קובץ בר־ביצוע של texvc אינו זמין; אנא צפו בקובץ math/README למידע על ההגדרות.',

# Patrolling
'markaspatrolleddiff'                 => 'סימון השינוי כבדוק',
'markaspatrolledtext'                 => 'סימון דף זה כבדוק',
'markedaspatrolled'                   => 'השינוי סומן כבדוק',
'markedaspatrolledtext'               => 'השינוי שבחרתם בדף [[:$1]] סומן כבדוק.',
'rcpatroldisabled'                    => 'אפשרות סימון השינויים כבדוקים מבוטלת',
'rcpatroldisabledtext'                => 'התכונה של סימון שינוי כבדוק בשינויים האחרונים מבוטלת.',
'markedaspatrollederror'              => 'לא ניתן לסמן כבדוק',
'markedaspatrollederrortext'          => 'עליכם לציין גרסה שתציינו כבדוקה.',
'markedaspatrollederror-noautopatrol' => 'אינכם מורשים לסמן את השינויים של עצמכם כבדוקים.',

# Patrol log
'patrol-log-page'      => 'יומן שינויים בדוקים',
'patrol-log-header'    => 'יומן זה מציג גרסאות שנבדקו.',
'patrol-log-line'      => 'סימן את $1 בדף $2 כבדוקה $3',
'patrol-log-auto'      => '(אוטומטית)',
'patrol-log-diff'      => 'גרסה $1',
'log-show-hide-patrol' => '$1 יומן שינויים בדוקים',

# Image deletion
'deletedrevision'                 => 'מחק גרסה ישנה $1',
'filedeleteerror-short'           => 'שגיאה במחיקת הקובץ: $1',
'filedeleteerror-long'            => 'שגיאות שאירעו בעת מחיקת הקובץ:

$1',
'filedelete-missing'              => 'מחיקת הקובץ "$1" נכשלה, כיוון שהוא אינו קיים.',
'filedelete-old-unregistered'     => 'גרסת הקובץ "$1" אינה רשומה בבסיס הנתונים.',
'filedelete-current-unregistered' => 'הקובץ "$1" אינו רשום בבסיס הנתונים.',
'filedelete-archive-read-only'    => 'השרת אינו יכול לכתוב לתיקיית הארכיון "$1".',

# Browsing diffs
'previousdiff' => '→ מעבר להשוואת הגרסאות הקודמת',
'nextdiff'     => 'מעבר להשוואת הגרסאות הבאה ←',

# Media information
'mediawarning'         => "'''אזהרה:''' סוג קובץ זה עלול להכיל קוד זדוני.
הרצת הקוד עלולה לסכן את המערכת שלכם.",
'imagemaxsize'         => 'גודל תמונה מירבי:<br />(לדפי תיאור קובץ)',
'thumbsize'            => 'הקטנה לגודל של:',
'widthheightpage'      => '$1×$2, {{PLURAL:$3|דף אחד|$3 דפים}}',
'file-info'            => '(גודל הקובץ: $1, סוג MIME: $2)',
'file-info-size'       => '($1 × $2 פיקסלים, גודל הקובץ: $3, סוג MIME: $4)',
'file-nohires'         => '<small>אין גרסת רזולוציה גבוהה יותר.</small>',
'svg-long-desc'        => '(קובץ SVG, הגודל המקורי: $1 × $2 פיקסלים, גודל הקובץ: $3)',
'show-big-image'       => 'תמונה ברזולוציה גבוהה יותר',
'show-big-image-thumb' => '<small>גודל התצוגה הזו: $1 × $2 פיקסלים</small>',
'file-info-gif-looped' => 'בלולאה',
'file-info-gif-frames' => '{{PLURAL:$1|תמונה אחת|$1 תמונות}}',
'file-info-png-looped' => 'בלולאה',
'file-info-png-repeat' => 'הוצג {{PLURAL:$1|פעם אחת|$1 פעמים|פעמיים}}',
'file-info-png-frames' => '{{PLURAL:$1|תמונה אחת|$1 תמונות}}',

# Special:NewFiles
'newimages'             => 'גלריית קבצים חדשים',
'imagelisttext'         => 'להלן רשימה של {{PLURAL:$1|קובץ אחד|$1 קבצים}}, ממוינים $2:',
'newimages-summary'     => 'דף זה מציג את הקבצים האחרונים שהועלו',
'newimages-legend'      => 'מסנן',
'newimages-label'       => 'שם הקובץ (או חלק ממנו):',
'showhidebots'          => '($1 בוטים)',
'noimages'              => 'אין קבצים.',
'ilsubmit'              => 'חיפוש',
'bydate'                => 'לפי תאריך',
'sp-newimages-showfrom' => 'הצגת קבצים חדשים החל מ־$2, $1',

# Bad image list
'bad_image_list' => 'דרך הכתיבה בהודעה היא כמתואר להלן:

רק פריטי רשימה (שורות המתחילות עם *) נחשבים. הקישור הראשון בשורה חייב להיות קישור לקובץ שאין להציג.
כל הקישורים הבאים באותה השורה נחשבים לחריגים, כלומר לדפים שבהם ניתן להציג את הקובץ.',

# Metadata
'metadata'          => 'מידע נוסף על הקובץ',
'metadata-help'     => 'קובץ זה מכיל מידע נוסף, שכנראה הגיע ממצלמה דיגיטלית או מסורק שבהם הקובץ נוצר או עבר דיגיטציה.
אם הקובץ שונה ממצבו הראשוני, כמה מהנתונים להלן עלולים שלא לשקף באופן מלא את הקובץ הנוכחי.',
'metadata-expand'   => 'הצגת פרטים מורחבים',
'metadata-collapse' => 'הסתרת פרטים מורחבים',
'metadata-fields'   => 'שדות המידע הנוסף של EXIF האלה אינם פרטים מורחבים ויוצגו תמיד, לעומת השאר:
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'רוחב',
'exif-imagelength'                 => 'גובה',
'exif-bitspersample'               => 'ביטים לרכיב',
'exif-compression'                 => 'תבנית דחיסה',
'exif-photometricinterpretation'   => 'הרכב פיקסלים',
'exif-orientation'                 => 'כיווניות',
'exif-samplesperpixel'             => 'מספר רכיבים',
'exif-planarconfiguration'         => 'סידור מידע',
'exif-ycbcrsubsampling'            => 'הפחתת יחס Y ל־C',
'exif-ycbcrpositioning'            => 'מיקום Y ו־C',
'exif-xresolution'                 => 'רזולוציה אופקית',
'exif-yresolution'                 => 'רזולוציה אנכית',
'exif-resolutionunit'              => 'יחידות מידה של רזולוציות X ו־Y',
'exif-stripoffsets'                => 'מיקום מידע התמונה',
'exif-rowsperstrip'                => 'מספר השורות לרצועה',
'exif-stripbytecounts'             => 'בייטים לרצועה דחוסה',
'exif-jpeginterchangeformat'       => 'יחס ל־JPEG SOI',
'exif-jpeginterchangeformatlength' => 'בייטים של מידע JPEG',
'exif-transferfunction'            => 'פונקציית העברה',
'exif-whitepoint'                  => 'נקודה לבנה צבעונית',
'exif-primarychromaticities'       => 'צבעוניות ה־Primarity',
'exif-ycbcrcoefficients'           => 'מקדמי פעולת הטרנספורמציה של מרחב הצבע',
'exif-referenceblackwhite'         => 'זוג ערכי התייחסות לשחור ולבן',
'exif-datetime'                    => 'תאריך ושעת שינוי הקובץ',
'exif-imagedescription'            => 'כותרת התמונה',
'exif-make'                        => 'יצרן המצלמה',
'exif-model'                       => 'דגם המצלמה',
'exif-software'                    => 'תוכנה בשימוש',
'exif-artist'                      => 'מחבר',
'exif-copyright'                   => 'בעל זכויות היוצרים',
'exif-exifversion'                 => 'גרסת Exif',
'exif-flashpixversion'             => 'גרסת Flashpix נתמכת',
'exif-colorspace'                  => 'מרחב הצבע',
'exif-componentsconfiguration'     => 'משמעות כל רכיב',
'exif-compressedbitsperpixel'      => 'שיטת דחיסת התמונה',
'exif-pixelydimension'             => 'רוחב התמונה הנכון',
'exif-pixelxdimension'             => 'גובה התמונה הנכון',
'exif-makernote'                   => 'הערות היצרן',
'exif-usercomment'                 => 'הערות המשתמש',
'exif-relatedsoundfile'            => 'קובץ שמע מקושר',
'exif-datetimeoriginal'            => 'תאריך ושעת יצירת הקובץ',
'exif-datetimedigitized'           => 'תאריך ושעת הפיכת הקובץ לדיגיטלי',
'exif-subsectime'                  => 'תת־השניות של שינוי הקובץ',
'exif-subsectimeoriginal'          => 'תת־השניות של יצירת הקובץ',
'exif-subsectimedigitized'         => 'תת־השניות של הפיכת הקובץ לדיגיטלי',
'exif-exposuretime'                => 'זמן חשיפה',
'exif-exposuretime-format'         => '$1 שניות ($2)',
'exif-fnumber'                     => 'מספר F',
'exif-exposureprogram'             => 'תוכנת החשיפה',
'exif-spectralsensitivity'         => 'רגישות הספקטרום',
'exif-isospeedratings'             => 'דירוג מהירות ה־ISO',
'exif-oecf'                        => 'מקדם המרה אופטו־אלקטרוני',
'exif-shutterspeedvalue'           => 'מהירות צמצם',
'exif-aperturevalue'               => 'פתח',
'exif-brightnessvalue'             => 'בהירות',
'exif-exposurebiasvalue'           => 'נטיית החשיפה',
'exif-maxaperturevalue'            => 'גודל הפתח המקסימאלי',
'exif-subjectdistance'             => 'נושא המרחק',
'exif-meteringmode'                => 'שיטת מדידה',
'exif-lightsource'                 => 'מקור אור',
'exif-flash'                       => 'פלש',
'exif-focallength'                 => 'אורך מוקדי העדשות',
'exif-focallength-format'          => '$1 מ"מ',
'exif-subjectarea'                 => 'נושא האזור',
'exif-flashenergy'                 => 'אנרגיית הפלש',
'exif-spatialfrequencyresponse'    => 'תדירות התגובה המרחבית',
'exif-focalplanexresolution'       => 'משטח הפוקוס ברזולוציה האופקית',
'exif-focalplaneyresolution'       => 'משטח הפוקוס ברזולוציה האנכית',
'exif-focalplaneresolutionunit'    => 'יחידת המידה של משטח הפוקוס ברזולוציה',
'exif-subjectlocation'             => 'נושא המיקום',
'exif-exposureindex'               => 'מדד החשיפה',
'exif-sensingmethod'               => 'שיטת חישה',
'exif-filesource'                  => 'מקור הקובץ',
'exif-scenetype'                   => 'סוג הסצנה',
'exif-cfapattern'                  => 'תבנית CFA',
'exif-customrendered'              => 'עיבוד תמונה מותאם',
'exif-exposuremode'                => 'מצב החשיפה',
'exif-whitebalance'                => 'איזון צבע לבן',
'exif-digitalzoomratio'            => 'יחס הזום הדיגיטלי',
'exif-focallengthin35mmfilm'       => 'אורך מוקדי העדשות בסרט צילום של 35 מ"מ',
'exif-scenecapturetype'            => 'אופן צילום הסצנה',
'exif-gaincontrol'                 => 'בקרת הסצנה',
'exif-contrast'                    => 'ניגוד',
'exif-saturation'                  => 'רוויה',
'exif-sharpness'                   => 'חדות',
'exif-devicesettingdescription'    => 'תיאור הגדרות ההתקן',
'exif-subjectdistancerange'        => 'טווח נושא המרחק',
'exif-imageuniqueid'               => 'מזהה תמונה ייחודי',
'exif-gpsversionid'                => 'גרסת תגי GPS',
'exif-gpslatituderef'              => 'קו־רוחב צפוני או דרומי',
'exif-gpslatitude'                 => 'קו־רוחב',
'exif-gpslongituderef'             => 'קו־אורך מזרחי או מערבי',
'exif-gpslongitude'                => 'קו־אורך',
'exif-gpsaltituderef'              => 'התייחסות גובה',
'exif-gpsaltitude'                 => 'גובה',
'exif-gpstimestamp'                => 'זמן GPS (שעון אטומי)',
'exif-gpssatellites'               => 'לוויינים ששמשו למדידה',
'exif-gpsstatus'                   => 'מעמד המקלט',
'exif-gpsmeasuremode'              => 'מצב מדידה',
'exif-gpsdop'                      => 'דיוק מדידה',
'exif-gpsspeedref'                 => 'יחידת מהירות',
'exif-gpsspeed'                    => 'יחידת מהירות של מקלט GPS',
'exif-gpstrackref'                 => 'התייחסות למהירות התנועה',
'exif-gpstrack'                    => 'מהירות התנועה',
'exif-gpsimgdirectionref'          => 'התייחסות לכיוון התמונה',
'exif-gpsimgdirection'             => 'כיוון התמונה',
'exif-gpsmapdatum'                 => 'מידע סקר מדידת הארץ שנעשה בו שימוש',
'exif-gpsdestlatituderef'          => 'התייחסות לקו־הרוחב של היעד',
'exif-gpsdestlatitude'             => 'קו־הרוחב של היעד',
'exif-gpsdestlongituderef'         => 'התייחסות לקו־האורך של היעד',
'exif-gpsdestlongitude'            => 'קו־האורך של היעד',
'exif-gpsdestbearingref'           => 'התייחסות לכיוון היעד',
'exif-gpsdestbearing'              => 'כיוון היעד',
'exif-gpsdestdistanceref'          => 'התייחסות למרחק ליעד',
'exif-gpsdestdistance'             => 'מרחק ליעד',
'exif-gpsprocessingmethod'         => 'שם שיטת העיבוד של ה־GPS',
'exif-gpsareainformation'          => 'שם אזור ה־GPS',
'exif-gpsdatestamp'                => 'תאריך ה־GPS',
'exif-gpsdifferential'             => 'תיקון דיפרנציאלי של ה־GPS',

# EXIF attributes
'exif-compression-1' => 'לא דחוס',

'exif-unknowndate' => 'תאריך בלתי ידוע',

'exif-orientation-1' => 'רגילה',
'exif-orientation-2' => 'הפוך אופקית',
'exif-orientation-3' => 'מסובב 180°',
'exif-orientation-4' => 'הפוך אנכית',
'exif-orientation-5' => 'מסובב 90° נגד כיוון השעון והפוך אנכית',
'exif-orientation-6' => 'מסובב 90° עם כיוון השעון',
'exif-orientation-7' => 'מסובב 90° עם כיוון השעון והפוך אנכית',
'exif-orientation-8' => 'מסובב 90° נגד כיוון השעון',

'exif-planarconfiguration-1' => 'פורמט חסון',
'exif-planarconfiguration-2' => 'פורמט שטוח',

'exif-componentsconfiguration-0' => 'אינו קיים',

'exif-exposureprogram-0' => 'לא הוגדרה',
'exif-exposureprogram-1' => 'ידנית',
'exif-exposureprogram-2' => 'תוכנה רגילה',
'exif-exposureprogram-3' => 'עדיפות פתח',
'exif-exposureprogram-4' => 'עדיפות צמצם',
'exif-exposureprogram-5' => 'תוכנה יוצרת (מטה לכיוון עומק השדה)',
'exif-exposureprogram-6' => 'תוכנה פועלת (מטה לכיוון מהירות צמצם גבוהה)',
'exif-exposureprogram-7' => 'מצב דיוקן (לתמונות צילום מקרוב כשהרקע לא בפוקוס)',
'exif-exposureprogram-8' => 'מצב נוף (לתמונות נוף כשהרקע בפוקוס)',

'exif-subjectdistance-value' => '$1 מטרים',

'exif-meteringmode-0'   => 'לא ידוע',
'exif-meteringmode-1'   => 'ממוצע',
'exif-meteringmode-2'   => 'מרכז משקל ממוצע',
'exif-meteringmode-3'   => 'נקודה',
'exif-meteringmode-4'   => 'רב־נקודה',
'exif-meteringmode-5'   => 'תבנית',
'exif-meteringmode-6'   => 'חלקי',
'exif-meteringmode-255' => 'אחר',

'exif-lightsource-0'   => 'לא ידוע',
'exif-lightsource-1'   => 'אור יום',
'exif-lightsource-2'   => 'פלואורסצנטי',
'exif-lightsource-3'   => 'טונגסטן (אור מתלהט)',
'exif-lightsource-4'   => 'פלש',
'exif-lightsource-9'   => 'מזג אוויר טוב',
'exif-lightsource-10'  => 'מזג אוויר מעונן',
'exif-lightsource-11'  => 'צל',
'exif-lightsource-12'  => 'אור יום פלואורסצנטי (D 5700 – 7100K)',
'exif-lightsource-13'  => 'אור יום לבן פלואורסצנטי (N 4600 – 5400K)',
'exif-lightsource-14'  => 'אור יום קריר לבן פלואורסצנטי (W 3900 – 4500K)',
'exif-lightsource-15'  => 'פלואורסצנטי לבן (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'אור רגיל A',
'exif-lightsource-18'  => 'אור רגיל B',
'exif-lightsource-19'  => 'אור רגיל C',
'exif-lightsource-24'  => 'טונגסטן אולפן ISO',
'exif-lightsource-255' => 'מקור אור אחר',

# Flash modes
'exif-flash-fired-0'    => 'הפלאש לא הופעל',
'exif-flash-fired-1'    => 'הפלאש הופעל',
'exif-flash-return-0'   => 'ללא התכונה של גילוי חזרת סטרובוסקופ',
'exif-flash-return-2'   => 'לא התגלה אור חזרת סטרובוסקופ',
'exif-flash-return-3'   => 'התגלה אור חזרת סטרובוסקופ',
'exif-flash-mode-1'     => 'מצב פלאש מופעל תמיד',
'exif-flash-mode-2'     => 'מצב פלאש כבוי תמיד',
'exif-flash-mode-3'     => 'מצב פלאש אוטומטי',
'exif-flash-function-1' => 'ללא תכונת פלאש',
'exif-flash-redeye-1'   => 'מצב מניעת עיניים אדומות',

'exif-focalplaneresolutionunit-2' => "אינצ'ים",

'exif-sensingmethod-1' => 'לא מוגדרת',
'exif-sensingmethod-2' => 'חיישן אזור בצבע עם שבב אחד',
'exif-sensingmethod-3' => 'חיישן אזור בצבע עם שני שבבים',
'exif-sensingmethod-4' => 'חיישן אזור בצבע עם שלושה שבבים',
'exif-sensingmethod-5' => 'חיישן אזור עם צבע רציף',
'exif-sensingmethod-7' => 'חיישן טריליניארי',
'exif-sensingmethod-8' => 'חיישן עם צבע רציף ליניארי',

'exif-scenetype-1' => 'תמונה שצולמה ישירות',

'exif-customrendered-0' => 'תהליך רגיל',
'exif-customrendered-1' => 'תהליך מותאם',

'exif-exposuremode-0' => 'חשיפה אוטומטית',
'exif-exposuremode-1' => 'חשיפה ידנית',
'exif-exposuremode-2' => 'מסגרת אוטומטית',

'exif-whitebalance-0' => 'איזון צבע לבן אוטומטי',
'exif-whitebalance-1' => 'איזון צבע לבן ידני',

'exif-scenecapturetype-0' => 'רגיל',
'exif-scenecapturetype-1' => 'נוף',
'exif-scenecapturetype-2' => 'דיוקן',
'exif-scenecapturetype-3' => 'סצנה לילית',

'exif-gaincontrol-0' => 'ללא',
'exif-gaincontrol-1' => 'תוספת נמוכה למעלה',
'exif-gaincontrol-2' => 'תוספת גבוהה למעלה',
'exif-gaincontrol-3' => 'תוספת נמוכה למטה',
'exif-gaincontrol-4' => 'תוספת גבוהה למטה',

'exif-contrast-0' => 'רגיל',
'exif-contrast-1' => 'רך',
'exif-contrast-2' => 'קשה',

'exif-saturation-0' => 'רגילה',
'exif-saturation-1' => 'רוויה נמוכה',
'exif-saturation-2' => 'רוויה גבוהה',

'exif-sharpness-0' => 'רגילה',
'exif-sharpness-1' => 'רכה',
'exif-sharpness-2' => 'קשה',

'exif-subjectdistancerange-0' => 'לא ידוע',
'exif-subjectdistancerange-1' => 'מאקרו',
'exif-subjectdistancerange-2' => 'תצוגה קרובה',
'exif-subjectdistancerange-3' => 'תצוגה רחוקה',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'קו־רוחב צפוני',
'exif-gpslatitude-s' => 'קו־רוחב דרומי',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'קו־אורך מזרחי',
'exif-gpslongitude-w' => 'קו־אורך מערבי',

'exif-gpsstatus-a' => 'מדידה בתהליך',
'exif-gpsstatus-v' => 'מדידה בו־זמנית',

'exif-gpsmeasuremode-2' => 'מדידה בשני ממדים',
'exif-gpsmeasuremode-3' => 'מדידה בשלושה ממדים',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'קילומטרים בשעה',
'exif-gpsspeed-m' => 'מיילים בשעה',
'exif-gpsspeed-n' => 'מיילים ימיים בשעה',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'כיוון אמיתי',
'exif-gpsdirection-m' => 'כיוון מגנטי',

# External editor support
'edit-externally'      => 'ערכו קובץ זה באמצעות יישום חיצוני',
'edit-externally-help' => '(ראו את [http://www.mediawiki.org/wiki/Manual:External_editors הוראות ההתקנה] למידע נוסף)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'הכול',
'imagelistall'     => 'הכול',
'watchlistall2'    => 'הכול',
'namespacesall'    => 'הכול',
'monthsall'        => 'הכול',
'limitall'         => 'הכול',

# E-mail address confirmation
'confirmemail'              => 'אימות כתובת דוא"ל',
'confirmemail_noemail'      => 'אין לכם כתובת דוא"ל תקפה המוגדרת ב[[Special:Preferences|העדפות המשתמש]] שלכם.',
'confirmemail_text'         => 'אתר זה דורש שתאמתו את כתובת הדוא"ל שלכם לפני שתשתמשו בשירותי הדוא"ל. לחצו על הכפתור למטה כדי לשלוח דוא"ל עם קוד אישור לכתובת הדוא"ל שהזנתם. טענו את הקישור בדפדפן שלכם כדי לאשר שכתובת הדוא"ל תקפה.',
'confirmemail_pending'      => 'קוד אישור דוא"ל כבר נשלח אליכם; אם יצרתם את החשבון לאחרונה, ייתכן שתרצו לחכות מספר דקות עד שיגיע לפני שתנסו לבקש קוד חדש.',
'confirmemail_send'         => 'שלח קוד אישור',
'confirmemail_sent'         => 'הדוא"ל עם קוד האישור נשלח.',
'confirmemail_oncreate'     => 'קוד אישור דוא"ל נשלח לכתובת הדוא"ל שלכם. הקוד הזה אינו נדרש לכניסה, אך תצטרכו לספקו כדי להשתמש בכל תכונה מבוססת דוא"ל באתר זה.',
'confirmemail_sendfailed'   => '{{SITENAME}} לא הצליח לשלוח לכם הודעת דוא"ל עם קוד האישור.
אנא בדקו שאין תווים שגויים בכתובת הדוא"ל.

תוכנת שליחת הדוא"ל החזירה את ההודעה הבאה: $1',
'confirmemail_invalid'      => 'קוד האישור שגוי. ייתכן שפג תוקפו.',
'confirmemail_needlogin'    => 'עליכם לבצע $1 כדי לאמת את כתובת הדוא"ל שלכם.',
'confirmemail_success'      => 'כתובת הדוא"ל שלכם אושרה.
כעת באפשרותכם [[Special:UserLogin|להיכנס לחשבון שלכם]] וליהנות מהאתר.',
'confirmemail_loggedin'     => 'כתובת הדוא"ל שלכם אושרה כעת.',
'confirmemail_error'        => 'שגיאה בשמירת קוד האישור.',
'confirmemail_subject'      => 'קוד אישור דוא"ל מ{{grammar:תחילית|{{SITENAME}}}}',
'confirmemail_body'         => 'מישהו, כנראה אתם (מכתובת ה־IP הזו: $1), רשם את החשבון "$2" עם כתובת הדוא"ל הזו ב{{grammar:תחילית|{{SITENAME}}}}.

כדי לאשר שחשבון זה באמת שייך לכם ולהפעיל את שירותי הדוא"ל באתר, אנא פתחו את הכתובת הבאה בדפדפן שלכם:

$3

אם *לא* אתם רשמתם את החשבון, השתמשו בקישור הבא כדי לבטל את אימות כתובת הדוא"ל:

$5

קוד האישור יפקע ב־$4.',
'confirmemail_body_changed' => 'מישהו, כנראה אתם (מכתובת ה־IP הזו: $1), שינה את כתובת הדוא"ל של החשבון "$2" לכתובת הזו ב{{grammar:תחילית|{{SITENAME}}}}.

כדי לאשר שחשבון זה באמת שייך לכם ולהפעיל מחדש את שירותי הדוא"ל באתר, אנא פתחו את הכתובת הבאה בדפדפן שלכם:

$3

אם החשבון *אינו* שייך לכם, השתמשו בקישור הבא כדי לבטל את אימות כתובת הדוא"ל:

$5

קוד האישור יפקע ב־$4.',
'confirmemail_invalidated'  => 'אימות כתובת הדוא"ל בוטל',
'invalidateemail'           => 'ביטול האימות של כתובת הדוא"ל',

# Scary transclusion
'scarytranscludedisabled' => '[הכללת דפים בין אתרים מבוטלת]',
'scarytranscludefailed'   => '[הכללת התבנית נכשלה בגלל $1]',
'scarytranscludetoolong'  => '[כתובת ה־URL ארוכה מדי]',

# Trackbacks
'trackbackbox'      => 'טרקבקים לדף זה:<br />
$1',
'trackbackremove'   => '([$1 מחיקה])',
'trackbacklink'     => 'טרקבק',
'trackbackdeleteok' => 'הטרקבק נמחק בהצלחה.',

# Delete conflict
'deletedwhileediting' => "'''אזהרה''': דף זה נמחק לאחר שהתחלתם לערוך!",
'confirmrecreate'     => "הדף נמחק על ידי המשתמש [[User:$1|$1]] ([[User talk:$1|שיחה]]) לאחר שהתחלתם לערוך אותו, מסיבה זו:
:'''$2'''
אנא אשרו שאתם אכן רוצים ליצור מחדש את הדף.",
'recreate'            => 'יצירה מחדש',

# action=purge
'confirm_purge_button' => 'אישור',
'confirm-purge-top'    => 'לנקות את המטמון של דף זה?',
'confirm-purge-bottom' => 'ניקוי המטמון של דף גורם לגרסה החדשה ביותר להופיע.',

# Multipage image navigation
'imgmultipageprev' => '→ לדף הקודם',
'imgmultipagenext' => 'לדף הבא ←',
'imgmultigo'       => 'הצגה',
'imgmultigoto'     => 'מעבר לדף $1',

# Table pager
'ascending_abbrev'         => 'עולה',
'descending_abbrev'        => 'יורד',
'table_pager_next'         => 'הדף הבא',
'table_pager_prev'         => 'הדף הקודם',
'table_pager_first'        => 'הדף הראשון',
'table_pager_last'         => 'הדף האחרון',
'table_pager_limit'        => 'הצגת $1 פריטים בדף',
'table_pager_limit_label'  => 'מספר פריטים בדף:',
'table_pager_limit_submit' => 'הצגה',
'table_pager_empty'        => 'ללא תוצאות',

# Auto-summaries
'autosumm-blank'   => 'הסרת כל התוכן מדף זה',
'autosumm-replace' => 'החלפת הדף בתוכן "$1"',
'autoredircomment' => 'הפניה לדף [[$1]]',
'autosumm-new'     => 'יצירת דף עם התוכן "$1"',

# Size units
'size-bytes'     => '$1 בייט',
'size-kilobytes' => '$1 קילו־בייט',
'size-megabytes' => '$1 מגה־בייט',
'size-gigabytes' => "$1 ג'יגה־בייט",

# Live preview
'livepreview-loading' => 'בטעינה…',
'livepreview-ready'   => 'בטעינה… נטען!',
'livepreview-failed'  => 'התצוגה המקדימה המהירה נכשלה! נסו להשתמש בתצוגה מקדימה רגילה.',
'livepreview-error'   => 'ההתחברות נכשלה: $1 "$2". נסו להשתמש בתצוגה מקדימה רגילה.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'שינויים שבוצעו לפני פחות מ{{PLURAL:$1|שנייה אחת|־$1 שניות}} אינם מוצגים ברשימה זו.',
'lag-warn-high'   => 'בגלל עיכוב בעדכון בסיס הנתונים, שינויים שבוצעו לפני פחות מ{{PLURAL:$1|שנייה אחת|־$1 שניות}} אינם מוצגים ברשימה זו.',

# Watchlist editor
'watchlistedit-numitems'       => 'יש לכם {{PLURAL:$1|פריט אחד|$1 פריטים}} ברשימת המעקב, לא כולל דפי שיחה.',
'watchlistedit-noitems'        => 'רשימת המעקב ריקה.',
'watchlistedit-normal-title'   => 'עריכת רשימת המעקב',
'watchlistedit-normal-legend'  => 'הסרת דפים מרשימת המעקב',
'watchlistedit-normal-explain' => 'כל הדפים ברשימת המעקב מוצגים להלן.
כדי להסיר דף, יש לסמן את התיבה לידו, וללחוץ על "{{int:Watchlistedit-normal-submit}}".
באפשרותכם גם [[Special:Watchlist/raw|לערוך את הרשימה הגולמית]].',
'watchlistedit-normal-submit'  => 'הסרת הדפים',
'watchlistedit-normal-done'    => '{{PLURAL:$1|כותרת אחת הוסרה|$1 כותרות הוסרו}} מרשימת המעקב:',
'watchlistedit-raw-title'      => 'עריכת הרשימה הגולמית',
'watchlistedit-raw-legend'     => 'עריכת הרשימה הגולמית',
'watchlistedit-raw-explain'    => 'הדפים ברשימת המעקב מוצגים להלן, וניתן לערוך אותם באמצעות הוספה והסרה שלהם מהרשימה;
כל כותרת מופיעה בשורה נפרדת.
לאחר סיום העריכה, יש ללחוץ על "{{int:Watchlistedit-raw-submit}}".
באפשרותכם גם [[Special:Watchlist/edit|להשתמש בעורך הרגיל]].',
'watchlistedit-raw-titles'     => 'דפים:',
'watchlistedit-raw-submit'     => 'עדכון הרשימה',
'watchlistedit-raw-done'       => 'רשימת המעקב עודכנה.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|כותרת אחת נוספה|$1 כותרות נוספו}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|כותרת אחת הוסרה|$1 כותרות הוסרו}}:',

# Watchlist editing tools
'watchlisttools-view' => 'הצגת השינויים הרלוונטיים',
'watchlisttools-edit' => 'הצגה ועריכה של רשימת המעקב',
'watchlisttools-raw'  => 'עריכת הרשימה הגולמית',

# Iranian month names
'iranian-calendar-m1'  => 'פרברדין',
'iranian-calendar-m2'  => 'אורדיבהשט',
'iranian-calendar-m3'  => 'חורדאד',
'iranian-calendar-m4'  => 'תיר',
'iranian-calendar-m5'  => 'מורדאד',
'iranian-calendar-m6'  => 'שהריבר',
'iranian-calendar-m7'  => 'מהר',
'iranian-calendar-m8'  => 'אבן',
'iranian-calendar-m9'  => 'אזר',
'iranian-calendar-m10' => 'די',
'iranian-calendar-m11' => 'בהמן',
'iranian-calendar-m12' => 'אספנד',

# Hijri month names
'hijri-calendar-m1'  => 'מוחרם',
'hijri-calendar-m2'  => 'צפר',
'hijri-calendar-m3'  => 'רבּיע אל-אוול',
'hijri-calendar-m4'  => "רבּיע א-ת'אני",
'hijri-calendar-m5'  => "ג'ומאדא אל-אוּלא",
'hijri-calendar-m6'  => "ג'ומאדא א-ת'אניה",
'hijri-calendar-m7'  => "רג'בּ",
'hijri-calendar-m8'  => 'שעבּאן',
'hijri-calendar-m9'  => 'רמדאן',
'hijri-calendar-m10' => 'שוואל',
'hijri-calendar-m11' => "ד'ו אל-קעדה",
'hijri-calendar-m12' => "ד'ו אל-חיג'ה",

# Hebrew month names
'hebrew-calendar-m1'      => 'תשרי',
'hebrew-calendar-m2'      => 'חשוון',
'hebrew-calendar-m3'      => 'כסלו',
'hebrew-calendar-m4'      => 'טבת',
'hebrew-calendar-m5'      => 'שבט',
'hebrew-calendar-m6'      => 'אדר',
'hebrew-calendar-m6a'     => "אדר א'",
'hebrew-calendar-m6b'     => "אדר ב'",
'hebrew-calendar-m7'      => 'ניסן',
'hebrew-calendar-m8'      => 'אייר',
'hebrew-calendar-m9'      => 'סיוון',
'hebrew-calendar-m10'     => 'תמוז',
'hebrew-calendar-m11'     => 'אב',
'hebrew-calendar-m12'     => 'אלול',
'hebrew-calendar-m1-gen'  => 'בתשרי',
'hebrew-calendar-m2-gen'  => 'בחשוון',
'hebrew-calendar-m3-gen'  => 'בכסלו',
'hebrew-calendar-m4-gen'  => 'בטבת',
'hebrew-calendar-m5-gen'  => 'בשבט',
'hebrew-calendar-m6-gen'  => 'באדר',
'hebrew-calendar-m6a-gen' => "באדר א'",
'hebrew-calendar-m6b-gen' => "באדר ב'",
'hebrew-calendar-m7-gen'  => 'בניסן',
'hebrew-calendar-m8-gen'  => 'באייר',
'hebrew-calendar-m9-gen'  => 'בסיוון',
'hebrew-calendar-m10-gen' => 'בתמוז',
'hebrew-calendar-m11-gen' => 'באב',
'hebrew-calendar-m12-gen' => 'באלול',

# Core parser functions
'unknown_extension_tag' => 'תגית בלתי ידועה: "$1"',
'duplicate-defaultsort' => '\'\'\'אזהרה:\'\'\' המיון הרגיל "$2" דורס את המיון הרגיל המוקדם ממנו "$1".',

# Special:Version
'version'                          => 'גרסת התוכנה',
'version-extensions'               => 'הרחבות מותקנות',
'version-specialpages'             => 'דפים מיוחדים',
'version-parserhooks'              => 'הרחבות מפענח',
'version-variables'                => 'משתנים',
'version-antispam'                 => 'מניעת ספאם',
'version-skins'                    => 'עיצובים',
'version-other'                    => 'אחר',
'version-mediahandlers'            => 'מציגי מדיה',
'version-hooks'                    => 'מבני Hook',
'version-extension-functions'      => 'פונקציות של הרחבות',
'version-parser-extensiontags'     => 'תגיות של הרחבות מפענח',
'version-parser-function-hooks'    => 'משתנים',
'version-skin-extension-functions' => 'עיצובים',
'version-hook-name'                => 'שם ה־Hook',
'version-hook-subscribedby'        => 'הפונקציה הרושמת',
'version-version'                  => '(גרסה $1)',
'version-license'                  => 'רישיון',
'version-poweredby-credits'        => "אתר הוויקי הזה מופעל על ידי '''[http://www.mediawiki.org/ מדיה־ויקי]''', © 2001-$1 $2.",
'version-poweredby-others'         => 'אחרים',
'version-license-info'             => "מדיה־ויקי היא תוכנה חופשית; באפשרותכם להפיץ אותה מחדש ו/או לשנות אותה לפי תנאי הרישיון הציבורי הכללי של גנו המפורסם על ידי המוסד לתוכנה חופשית: גרסה 2 של רישיון זה, או (לפי בחירתכם) כל גרסה מאוחרת יותר.

מדיה־ויקי מופצת בתקווה שהיא תהיה שימושית, אך '''ללא כל הבטחה לאחריות'''; אפילו לא אחריות משתמעת של '''יכולת להיסחר''' או '''התאמה לרישיון מסוים'''. ראו את הרישיון הציבורי הכללי של גנו לפרטים נוספים.

הייתם צריכים לקבל [{{SERVER}}{{SCRIPTPATH}}/COPYING העתק של הרישיון הציבורי הכללי של גנו] יחד עם תוכנה זו; אם לא, כתבו למוסד לתוכנה חופשית: Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA או [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html קראו אותו ברשת].",
'version-software'                 => 'תוכנות מותקנות',
'version-software-product'         => 'תוכנה',
'version-software-version'         => 'גרסה',

# Special:FilePath
'filepath'         => 'נתיב לקובץ',
'filepath-page'    => 'הקובץ:',
'filepath-submit'  => 'הצגה',
'filepath-summary' => 'דף זה מציג את הנתיב המלא לקבצים שהועלו.
תמונות מוצגות ברזולוציה מלאה, וסוגי קבצים אחרים מוצגים ישירות באמצעות התוכנה שהוגדרה להצגתם.

יש להקליד את שם הקובץ ללא הקידומת "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'חיפוש קבצים כפולים',
'fileduplicatesearch-summary'  => 'חיפוש קבצים כפולים על בסיס ערכי ה־Hash שלהם.

הקלידו את שם הקובץ ללא הקידומת "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'חיפוש קבצים כפולים',
'fileduplicatesearch-filename' => 'קובץ:',
'fileduplicatesearch-submit'   => 'חיפוש',
'fileduplicatesearch-info'     => '$1 × $2 פיקסלים<br />גודל הקובץ: $3<br />סוג MIME: $4',
'fileduplicatesearch-result-1' => 'אין קובץ כפול לקובץ "$1".',
'fileduplicatesearch-result-n' => 'לקובץ "$1" יש {{PLURAL:$2|עותק כפול אחד|$2 עותקים כפולים}}.',

# Special:SpecialPages
'specialpages'                   => 'דפים מיוחדים',
'specialpages-note'              => '----
* דפים מיוחדים רגילים.
* <strong class="mw-specialpagerestricted">דפים מיוחדים מוגבלים.</strong>',
'specialpages-group-maintenance' => 'דיווחי תחזוקה',
'specialpages-group-other'       => 'דפים מיוחדים אחרים',
'specialpages-group-login'       => 'כניסה / הרשמה לחשבון',
'specialpages-group-changes'     => 'שינויים אחרונים ויומנים',
'specialpages-group-media'       => 'קובצי מדיה והעלאות',
'specialpages-group-users'       => 'משתמשים והרשאות',
'specialpages-group-highuse'     => 'דפים בשימוש רב',
'specialpages-group-pages'       => 'רשימות דפים',
'specialpages-group-pagetools'   => 'כלים לדפים',
'specialpages-group-wiki'        => 'מידע וכלים על האתר',
'specialpages-group-redirects'   => 'הפניות מדפים מיוחדים',
'specialpages-group-spam'        => 'כלי ספאם',

# Special:BlankPage
'blankpage'              => 'דף ריק',
'intentionallyblankpage' => 'דף זה נשאר ריק במכוון.',

# External image whitelist
'external_image_whitelist' => '#נא להשאיר שורה זו בדיוק כפי שהיא<pre>
#כתבו קטעים של ביטויים רגולריים (רק החלק שבין סימני //) למטה
#ביטויים אלה יושוו לכתובות ה־URL של תמונות חיצוניות (המוכללות באמצעות כתובת URL)
#התמונות שתואמות לאחד הביטויים הרגולריים יוצגו כתמונות, והאחרות יוצגו כקישורים בלבד
#שורות המתחילות בסימן # הן הערות
#רשימה זו אינה תלויה ברישיות

#נא לכתוב את כל הביטויים הרגולריים מעל שורה זו. נא להשאיר שורה זו בדיוק כפי שהיא</pre>',

# Special:Tags
'tags'                    => 'התגיות הקיימות לסימון שינויים',
'tag-filter'              => 'מסנן [[Special:Tags|תגיות]]:',
'tag-filter-submit'       => 'סינון',
'tags-title'              => 'תגיות',
'tags-intro'              => 'דף זה מכיל רשימה של תגיות שהתוכנה יכולה לסמן איתן עריכה, ומשמעויותיהן.',
'tags-tag'                => 'שם התגית',
'tags-display-header'     => 'הופעה ברשימות השינויים',
'tags-description-header' => 'תיאור מלא של המשמעות',
'tags-hitcount-header'    => 'שינויים עם תגיות',
'tags-edit'               => 'עריכה',
'tags-hitcount'           => '{{PLURAL:$1|שינוי אחד|$1 שינויים}}',

# Special:ComparePages
'comparepages'     => 'השוואת דפים',
'compare-selector' => 'השוואת גרסאות של דפים',
'compare-page1'    => 'דף 1',
'compare-page2'    => 'דף 2',
'compare-rev1'     => 'גרסה 1',
'compare-rev2'     => 'גרסה 2',
'compare-submit'   => 'השוואה',

# Database error messages
'dberr-header'      => 'בעיה בוויקי',
'dberr-problems'    => 'מצטערים! קיימת בעיה טכנית באתר זה.',
'dberr-again'       => 'נסו להמתין מספר שניות ולהעלות מחדש את הדף.',
'dberr-info'        => '(לא ניתן ליצור קשר עם שרת הנתונים: $1)',
'dberr-usegoogle'   => 'באפשרותכם לנסות לחפש דרך גוגל בינתיים.',
'dberr-outofdate'   => 'שימו לב שהתוכן שלנו ששמר בזיכרון המטמון עשוי שלא להיות מעודכן.',
'dberr-cachederror' => 'זהו עותק שמור של המידע, והוא עשוי שלא להיות מעודכן.',

# HTML forms
'htmlform-invalid-input'       => 'יש בעיות עם חלק מהקלט שהכנסתם',
'htmlform-select-badoption'    => 'הערך שציינתם אינו אפשרות תקינה.',
'htmlform-int-invalid'         => 'הערך שציינתם אינו מספר שלם.',
'htmlform-float-invalid'       => 'הערך שציינתם אינו מספר.',
'htmlform-int-toolow'          => 'הערך שציינתם הוא מתחת למינימום, $1',
'htmlform-int-toohigh'         => 'הערך שציינתם הוא מעל למקסימום, $1',
'htmlform-required'            => 'ערך זה דרוש',
'htmlform-submit'              => 'שליחה',
'htmlform-reset'               => 'ביטול השינויים',
'htmlform-selectorother-other' => 'אחר',

# SQLite database support
'sqlite-has-fts' => '$1 עם תמיכה בחיפוש בטקסט מלא',
'sqlite-no-fts'  => '$1 ללא תמיכה בחיפוש בטקסט מלא',

# Special:DisableAccount
'disableaccount'             => 'ביטול חשבון משתמש',
'disableaccount-user'        => 'שם משתמש:',
'disableaccount-reason'      => 'סיבה:',
'disableaccount-confirm'     => "ביטול חשבון משתמש זה.
המשתמש לא יוכל להיכנס לחשבון, לאפס את הסיסמה, או לקבל הודעות בדואר אלקטרוני.
אם המשתמש מחובר כעת לחשבון במקום כלשהו, הוא ייצא מהחשבון מיד.
'''שימו לב שביטול חשבון הוא פעולה בלתי הפיכה ללא התערבות של מנהל מערכת.'''",
'disableaccount-mustconfirm' => 'עליכם לאשר שאתם רוצים לבטל חשבון זה.',
'disableaccount-nosuchuser'  => 'חשבון המשתמש "$1" אינו קיים.',
'disableaccount-success'     => 'חשבון המשתמש "$1" בוטל.',
'disableaccount-logentry'    => 'ביטל את חשבון המשתמש  [[$1]]',

);
