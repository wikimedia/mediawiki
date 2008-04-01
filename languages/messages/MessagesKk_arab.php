<?php
/** Kazakh (Arabic script) (‫قازاقشا (تٴوتە)‬)
 *
 * @addtogroup Language
 *
 * @author GaiJin
 * @author AlefZet
 */

$fallback = 'kk-cyrl';
$rtl = true;

$digitTransformTable = array(
	'0' => '۰', # &#x06f0;
	'1' => '۱', # &#x06f1;
	'2' => '۲', # &#x06f2;
	'3' => '۳', # &#x06f3;
	'4' => '۴', # &#x06f4;
	'5' => '۵', # &#x06f5;
	'6' => '۶', # &#x06f6;
	'7' => '۷', # &#x06f7;
	'8' => '۸', # &#x06f8;
	'9' => '۹', # &#x06f9;
);

$separatorTransformTable = array(
	'.' => '٫', # &#x066b;
	',' => '٬', # &#x066c;
);

$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
	# Underlines seriously harm legibility. Force off:
	'underline' => 0,
);

$extraUserToggles = array(
	'nolangconversion'
);

$fallback8bitEncoding = 'windows-1256';

$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'تاسپا',
	NS_SPECIAL          => 'ارنايى',
	NS_MAIN	            => '',
	NS_TALK	            => 'تالقىلاۋ',
	NS_USER             => 'قاتىسۋشى',
	NS_USER_TALK        => 'قاتىسۋشى_تالقىلاۋى',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_تالقىلاۋى',
	NS_IMAGE            => 'سۋرەت',
	NS_IMAGE_TALK       => 'سۋرەت_تالقىلاۋى',
	NS_MEDIAWIKI        => 'مەدىياۋىيكىي',
	NS_MEDIAWIKI_TALK   => 'مەدىياۋىيكىي_تالقىلاۋى',
	NS_TEMPLATE         => 'ۇلگى',
	NS_TEMPLATE_TALK    => 'ۇلگى_تالقىلاۋى',
	NS_HELP             => 'انىقتاما',
	NS_HELP_TALK        => 'انىقتاما_تالقىلاۋى',
	NS_CATEGORY         => 'سانات',
	NS_CATEGORY_TALK    => 'سانات_تالقىلاۋى'
);

$namespaceAliases = array(
	# Aliases to kk-cyrl namespaces
	'Таспа'               => NS_MEDIA,
	'Арнайы'              => NS_SPECIAL,
	'Талқылау'            => NS_TALK,
	'Қатысушы'            => NS_USER,
	'Қатысушы_талқылауы'  => NS_USER_TALK,
	'$1_талқылауы'        => NS_PROJECT_TALK,
	'Сурет'               => NS_IMAGE,
	'Сурет_талқылауы'     => NS_IMAGE_TALK,
	'МедиаУики'           => NS_MEDIAWIKI,
	'МедиаУики_талқылауы' => NS_MEDIAWIKI_TALK,
	'Үлгі'                => NS_TEMPLATE,
	'Үлгі_талқылауы'      => NS_TEMPLATE_TALK,
	'Анықтама'            => NS_HELP,
	'Анықтама_талқылауы'  => NS_HELP_TALK,
	'Санат'               => NS_CATEGORY,
	'Санат_талқылауы'     => NS_CATEGORY_TALK,

	# Aliases to kk-latn namespaces
	'Taspa'               => NS_MEDIA,
	'Arnaýı'              => NS_SPECIAL,
	'Talqılaw'            => NS_TALK,
	'Qatıswşı'            => NS_USER,
	'Qatıswşı_talqılawı'  => NS_USER_TALK,
	'$1_talqılawı'        => NS_PROJECT_TALK,
	'Swret'               => NS_IMAGE,
	'Swret_talqılawı'     => NS_IMAGE_TALK,
	'MedïaWïkï'           => NS_MEDIAWIKI,
	'MedïaWïkï_talqılawı' => NS_MEDIAWIKI_TALK,
	'Ülgi'                => NS_TEMPLATE,
	'Ülgi_talqılawı'      => NS_TEMPLATE_TALK,
	'Anıqtama'            => NS_HELP,
	'Anıqtama_talqılawı'  => NS_HELP_TALK,
	'Sanat'               => NS_CATEGORY,
	'Sanat_talqılawı'     => NS_CATEGORY_TALK,

	# Aliases to renamed kk-arab namespaces
	'مەدياۋيكي'        => NS_MEDIAWIKI,
	'مەدياۋيكي_تالقىلاۋى'  => NS_MEDIAWIKI_TALK ,
	'ٷلگٸ'        => NS_TEMPLATE ,
	'ٷلگٸ_تالقىلاۋى'    => NS_TEMPLATE_TALK,
	'ٴۇلگٴى'              => NS_TEMPLATE,
	'ٴۇلگٴى_تالقىلاۋى'    => NS_TEMPLATE_TALK,
);

$skinNames = array(
	'standard'    => 'داعدىلى (standard)',
	'nostalgia'   => 'اڭساۋ (nostalgia)',
	'cologneblue' => 'كولن زەڭگىرلىگى (cologneblue)',
	'monobook'    => 'دارا كىتاپ (monobook)',
	'myskin'      => 'ٴوز مانەرىم (myskin)',
	'chick'       => 'بالاپان (chick)',
	'simple'      => 'كادىمگى (simple)'
);

$datePreferences = array(
	'default',
	'mdy',
	'dmy',
	'ymd',
	'yyyy-mm-dd',
	'persian',
	'hebrew',
	'ISO 8601',
);

$defaultDateFormat = 'ymd';

$datePreferenceMigrationMap = array(
	'default',
	'mdy',
	'dmy',
	'ymd'
);

$dateFormats = array(
#   Please be cautious not to delete the invisible RLM from the beginning of the strings.
	'mdy time' => '‏H:i',
	'mdy date' => '‏xg j، Y "ج."',
	'mdy both' => '‏H:i، xg j، Y "ج."',

	'dmy time' => '‏H:i',
	'dmy date' => '‏j F، Y "ج."',
	'dmy both' => '‏H:i، j F، Y "ج."',

	'ymd time' => '‏H:i',
	'ymd date' => '‏Y "ج." xg j',
	'ymd both' => '‏H:i، Y "ج." xg j',

	'yyyy-mm-dd time' => 'xnH:xni:xns',
	'yyyy-mm-dd date' => 'xnY-xnm-xnd',
	'yyyy-mm-dd both' => 'xnH:xni:xns, xnY-xnm-xnd',

	'persian time' => '‏H:i',
	'persian date' => '‏xij xiF xiY', 
	'persian both' => '‏xij xiF xiY، H:i',
	
	'hebrew time' => '‏H:i',
	'hebrew date' => '‏xjj xjF xjY',
	'hebrew both' => '‏H:i، xjj xjF xjY',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

/**
 * Magic words
 * Customisable syntax for wikitext and elsewhere.
 *
 * IDs must be valid identifiers, they can't contain hyphens. 
 *
 * Note to translators:
 *   Please include the English words as synonyms.  This allows people
 *   from other wikis to contribute more easily.
 *   Please don't remove deprecated values, them should be keeped for backward compatibility.
 *
 * This array can be modified at runtime with the LanguageGetMagic hook
 */
$magicWords = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#REDIRECT', '#ايداۋ' ),
	'notoc'                  => array( 0,    '__مازمۇنسىز__', '__مسىز__', '__NOTOC__' ),
	'nogallery'              => array( 0,    '__قويماسىز__', '__قسىز__', '__NOGALLERY__' ),
	'forcetoc'               => array( 0,    '__مازمۇنداتقىزۋ__', '__مقىزۋ__', '__FORCETOC__' ),
	'toc'                    => array( 0,    '__مازمۇنى__', '__مزمن__', '__TOC__' ),
	'noeditsection'          => array( 0,    '__بولىموندەتكىزبەۋ__', '__NOEDITSECTION__' ),
	'currentmonth'           => array( 1,    'اعىمداعىاي', 'CURRENTMONTH' ),
	'currentmonthname'       => array( 1,    'اعىمداعىاياتاۋى', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'    => array( 1,    'اعىمداعىايىلىكاتاۋى', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'     => array( 1,    'اعىمداعىايجىيىر', 'اعىمداعىايقىسقا', 'CURRENTMONTHABBREV' ),
	'currentday'             => array( 1,    'اعىمداعىكۇن', 'CURRENTDAY' ),
	'currentday2'            => array( 1,    'اعىمداعىكۇن2', 'CURRENTDAY2' ),
	'currentdayname'         => array( 1,    'اعىمداعىكۇناتاۋى', 'CURRENTDAYNAME' ),
	'currentyear'            => array( 1,    'اعىمداعىجىل', 'CURRENTYEAR' ),
	'currenttime'            => array( 1,    'اعىمداعىۋاقىت', 'CURRENTTIME' ),
	'currenthour'            => array( 1,    'اعىمداعىساعات', 'CURRENTHOUR' ),
	'localmonth'             => array( 1,    'جەرگىلىكتىاي', 'LOCALMONTH' ),
	'localmonthname'         => array( 1,    'جەرگىلىكتىاياتاۋى', 'LOCALMONTHNAME' ),
	'localmonthnamegen'      => array( 1,    'جەرگىلىكتىايىلىكاتاۋى', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'       => array( 1,    'جەرگىلىكتىايجىيىر', 'جەرگىلىكتىايقىسقاشا', 'جەرگىلىكتىايقىسقا', 'LOCALMONTHABBREV' ),
	'localday'               => array( 1,    'جەرگىلىكتىكۇن', 'LOCALDAY' ),
	'localday2'              => array( 1,    'جەرگىلىكتىكۇن2', 'LOCALDAY2'  ),
	'localdayname'           => array( 1,    'جەرگىلىكتىكۇناتاۋى', 'LOCALDAYNAME' ),
	'localyear'              => array( 1,    'جەرگىلىكتىجىل', 'LOCALYEAR' ),
	'localtime'              => array( 1,    'جەرگىلىكتىۋاقىت', 'LOCALTIME' ),
	'localhour'              => array( 1,    'جەرگىلىكتىساعات', 'LOCALHOUR' ),
	'numberofpages'          => array( 1,    'بەتسانى', 'NUMBEROFPAGES' ),
	'numberofarticles'       => array( 1,    'ماقالاسانى', 'NUMBEROFARTICLES' ),
	'numberoffiles'          => array( 1,    'فايلسانى', 'NUMBEROFFILES' ),
	'numberofusers'          => array( 1,    'قاتىسۋشىسانى', 'NUMBEROFUSERS' ),
	'numberofedits'          => array( 1,    'تۇزەتۋسانى', 'NUMBEROFEDITS' ),
	'pagename'               => array( 1,    'بەتاتاۋى', 'PAGENAME' ),
	'pagenamee'              => array( 1,    'بەتاتاۋى2', 'PAGENAMEE' ),
	'namespace'              => array( 1,    'ەسىماياسى', 'NAMESPACE' ),
	'namespacee'             => array( 1,    'ەسىماياسى2', 'NAMESPACEE' ),
	'talkspace'              => array( 1,    'تالقىلاۋاياسى', 'TALKSPACE' ),
	'talkspacee'             => array( 1,    'تالقىلاۋاياسى2', 'TALKSPACEE' ),
	'subjectspace'           => array( 1,    'تاقىرىپبەتى', 'ماقالابەتى', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'          => array( 1,    'تاقىرىپبەتى2', 'ماقالابەتى2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'           => array( 1,    'تولىقبەتاتاۋى', 'FULLPAGENAME' ),
	'fullpagenamee'          => array( 1,    'تولىقبەتاتاۋى2', 'FULLPAGENAMEE' ),
	'subpagename'            => array( 1,    'بەتشەاتاۋى', 'استىڭعىبەتاتاۋى', 'SUBPAGENAME' ),
	'subpagenamee'           => array( 1,    'بەتشەاتاۋى2', 'استىڭعىبەتاتاۋى2', 'SUBPAGENAMEE' ),
	'basepagename'           => array( 1,    'نەگىزگىبەتاتاۋى', 'BASEPAGENAME' ),
	'basepagenamee'          => array( 1,    'نەگىزگىبەتاتاۋى2', 'BASEPAGENAMEE' ),
	'talkpagename'           => array( 1,    'تالقىلاۋبەتاتاۋى', 'TALKPAGENAME' ),
	'talkpagenamee'          => array( 1,    'تالقىلاۋبەتاتاۋى2', 'TALKPAGENAMEE' ),
	'subjectpagename'        => array( 1,    'تاقىرىپبەتاتاۋى', 'ماقالابەتاتاۋى', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'       => array( 1,    'تاقىرىپبەتاتاۋى2', 'ماقالابەتاتاۋى2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                    => array( 0,    'حبر:', 'MSG:' ),
	'subst'                  => array( 0,    'بادەل:', 'SUBST:' ),
	'msgnw'                  => array( 0,    'ۋىيكىيسىزحبر:', 'MSGNW:' ),
	'img_thumbnail'          => array( 1,    'نوباي', 'thumbnail', 'thumb' ),
	'img_manualthumb'        => array( 1,    'نوباي=$1', 'thumbnail=$1', 'thumb=$1'),
	'img_right'              => array( 1,    'وڭعا', 'وڭ', 'right' ),
	'img_left'               => array( 1,    'سولعا', 'سول', 'left' ),
	'img_none'               => array( 1,    'ەشقانداي', 'جوق', 'none' ),
	'img_width'              => array( 1,    '$1 px', '$1px' ),
	'img_center'             => array( 1,    'ورتاعا', 'ورتا', 'center', 'centre' ),
	'img_framed'             => array( 1,    'سۇرمەلى', 'framed', 'enframed', 'frame' ),
	'img_frameless'          => array( 1,    'سۇرمەسىز', 'frameless' ),
	'img_page'               => array( 1,    'بەت=$1', 'بەت $1', 'page=$1', 'page $1' ),
	'img_upright'            => array( 1,    'تىكتى', 'تىكتىك=$1', 'تىكتىك $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'             => array( 1,    'شەكتى', 'border' ),
	'img_baseline'           => array( 1,    'نەگىزجول', 'baseline' ),
	'img_sub'                => array( 1,    'استىلىعى', 'است', 'sub'),
	'img_super'              => array( 1,    'ۇستىلىگى', 'ۇست', 'sup', 'super', 'sup' ),
	'img_top'                => array( 1,    'ۇستىنە', 'top' ),
	'img_text_top'           => array( 1,    'ماتىن-ۇستىندە', 'text-top' ),
	'img_middle'             => array( 1,    'ارالىعىنا', 'middle' ),
	'img_bottom'             => array( 1,    'استىنا', 'bottom' ),
	'img_text_bottom'        => array( 1,    'ماتىن-استىندا', 'text-bottom' ),
	'int'                    => array( 0,    'ىشكى:', 'INT:' ),
	'sitename'               => array( 1,    'توراپاتاۋى', 'SITENAME' ),
	'ns'                     => array( 0,    'ەا:', 'ەسىمايا:', 'NS:' ),
	'localurl'               => array( 0,    'جەرگىلىكتىجاي:', 'LOCALURL:' ),
	'localurle'              => array( 0,    'جەرگىلىكتىجاي2:', 'LOCALURLE:' ),
	'server'                 => array( 0,    'سەرۆەر', 'SERVER' ),
	'servername'             => array( 0,    'سەرۆەراتاۋى', 'SERVERNAME' ),
	'scriptpath'             => array( 0,    'امىرجولى', 'SCRIPTPATH' ),
	'grammar'                => array( 0,    'سەپتىگى:', 'سەپتىك:', 'GRAMMAR:' ),
	'notitleconvert'         => array( 0,    '__اتاۋالماستىرعىزباۋ__', '__ااباۋ__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'       => array( 0,    '__ماعلۇماتالماستىرعىزباۋ__', '__ماباۋ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'            => array( 1,    'اعىمداعىاپتاسى', 'اعىمداعىاپتا', 'CURRENTWEEK' ),
	'currentdow'             => array( 1,    'اعىمداعىاپتاكۇنى', 'CURRENTDOW' ),
	'localweek'              => array( 1,    'جەرگىلىكتىاپتاسى', 'جەرگىلىكتىاپتا', 'LOCALWEEK' ),
	'localdow'               => array( 1,    'جەرگىلىكتىاپتاكۇنى', 'LOCALDOW' ),
	'revisionid'             => array( 1,    'نۇسقانومىرى', 'REVISIONID' ),
	'revisionday'            => array( 1,    'نۇسقاكۇنى' , 'REVISIONDAY' ),
	'revisionday2'           => array( 1,    'نۇسقاكۇنى2', 'REVISIONDAY2' ),
	'revisionmonth'          => array( 1,    'نۇسقاايى', 'REVISIONMONTH' ),
	'revisionyear'           => array( 1,    'نۇسقاجىلى', 'REVISIONYEAR' ),
	'revisiontimestamp'      => array( 1,    'نۇسقاۋاقىتتۇيىندەمەسى', 'REVISIONTIMESTAMP' ),
	'plural'                 => array( 0,    'كوپشەتۇرى:','كوپشە:', 'PLURAL:' ),
	'fullurl'                => array( 0,    'تولىقجايى:', 'تولىقجاي:', 'FULLURL:' ),
	'fullurle'               => array( 0,    'تولىقجايى2:', 'تولىقجاي2:', 'FULLURLE:' ),
	'lcfirst'                => array( 0,    'كا1:', 'كىشىارىپپەن1:', 'LCFIRST:' ),
	'ucfirst'                => array( 0,    'با1:', 'باسارىپپەن1:', 'UCFIRST:' ),
	'lc'                     => array( 0,    'كا:', 'كىشىارىپپەن:', 'LC:' ),
	'uc'                     => array( 0,    'با:', 'باسارىپپەن:', 'UC:' ),
	'raw'                    => array( 0,    'قام:', 'RAW:' ),
	'displaytitle'           => array( 1,    'كورسەتىلەتىناتاۋ', 'DISPLAYTITLE' ),
	'rawsuffix'              => array( 1,    'ق', 'R' ),
	'newsectionlink'         => array( 1,    '__جاڭابولىمسىلتەمەسى__', '__NEWSECTIONLINK__' ),
	'currentversion'         => array( 1,    'باعدارلامانۇسقاسى', 'CURRENTVERSION' ),
	'urlencode'              => array( 0,    'جايدىمۇقامداۋ:', 'URLENCODE:' ),
	'anchorencode'           => array( 0,    'جاكىردىمۇقامداۋ', 'ANCHORENCODE' ),
	'currenttimestamp'       => array( 1,    'اعىمداعىۋاقىتتۇيىندەمەسى', 'اعىمداعىۋاقىتتۇيىن', 'CURRENTTIMESTAMP' ),
	'localtimestamp'         => array( 1,    'جەرگىلىكتىۋاقىتتۇيىندەمەسى', 'جەرگىلىكتىۋاقىتتۇيىن', 'LOCALTIMESTAMP' ),
	'directionmark'          => array( 1,    'باعىتبەلگىسى', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'               => array( 0,    '#تىل:', '#LANGUAGE:' ),
	'contentlanguage'        => array( 1,    'ماعلۇماتتىلى', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'       => array( 1,    'ەسىمايابەتسانى:', 'ەابەتسانى:', 'ايابەتسانى:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'         => array( 1,    'اكىمشىسانى', 'NUMBEROFADMINS' ),
	'formatnum'              => array( 0,    'سانپىشىمى', 'FORMATNUM' ),
	'padleft'                => array( 0,    'سولعاىعىس', 'سولىعىس', 'PADLEFT' ),
	'padright'               => array( 0,    'وڭعاىعىس', 'وڭىعىس', 'PADRIGHT' ),
	'special'                => array( 0,    'ارنايى', 'special' ),
	'defaultsort'            => array( 1,    'ادەپكىسۇرىپتاۋ:', 'ادەپكىساناتسۇرىپتاۋ:', 'ادەپكىسۇرىپتاۋكىلتى:', 'ادەپكىسۇرىپ:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'               => array( 0,    'FILEPATH:', 'فايلمەكەنى' ),
	'tag'                    => array( 0,    'بەلگى', 'tag' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'شىنجىرلى_ايداتۋلار' ),
	'BrokenRedirects'           => array( 'جارامسىز_ايداتۋلار' ),
	'Disambiguations'           => array( 'ايرىقتى_بەتتەر' ),
	'Userlogin'                 => array( 'قاتىسۋشى_كىرۋى' ),
	'Userlogout'                => array( 'قاتىسۋشى_شىعۋى' ),
	'CreateAccount'             => array( 'تىركەلگى_جاراتۋ' ),
	'Preferences'               => array( 'باپتاۋ' ),
	'Watchlist'                 => array( 'باقىلاۋ_تىزىمى' ),
	'Recentchanges'             => array( 'جۋىقتاعى_وزگەرىستەر' ),
	'Upload'                    => array( 'قوتارۋ' ),
	'Imagelist'                 => array( 'سۋرەت_تىزىمى' ),
	'Newimages'                 => array( 'جاڭا_سۋرەتتەر' ),
	'Listusers'                 => array( 'قاتىسۋشىلار', 'قاتىسۋشى_تىزىمى' ),
	'Statistics'                => array( 'ساناق' ),
	'Randompage'                => array( 'كەزدەيسوق', 'كەزدەيسوق_بەت' ),
	'Lonelypages'               => array( 'ساياق_بەتتەر' ),
	'Uncategorizedpages'        => array( 'ساناتسىز_بەتتەر' ),
	'Uncategorizedcategories'   => array( 'ساناتسىز_ساناتتار' ),
	'Uncategorizedimages'       => array( 'ساناتسىز_سۋرەتتەر' ),
	'Uncategorizedtemplates'    => array( 'ساناتسىز_ۇلگىلەر' ),
	'Unusedcategories'          => array( 'پايدالانىلماعان_ساناتتار' ),
	'Unusedimages'              => array( 'پايدالانىلماعان_سۋرەتتەر' ),
	'Wantedpages'               => array( 'تولتىرىلماعان_بەتتەر', 'جارامسىز_سىلتەمەلەر' ),
	'Wantedcategories'          => array( 'تولتىرىلماعان_ساناتتار' ),
	'Mostlinked'                => array( 'ەڭ_كوپ_سىلتەنگەن_بەتتەر' ),
	'Mostlinkedcategories'      => array( 'ەڭ_كوپ_پايدالانىلعان_ساناتتار', 'ەڭ_كوپ_سىلتەنگەن_ساناتتار' ),
	'Mostlinkedtemplates'       => array( 'ەڭ_كوپ_پايدالانىلعان_ۇلگىلەر', 'ەڭ_كوپ_سىلتەنگەن_ۇلگىلەر' ),
	'Mostcategories'            => array( 'ەڭ_كوپ_ساناتتار_بارى' ),
	'Mostimages'                => array( 'ەڭ_كوپ_پايدالانىلعان_سۋرەتتەر', 'ەڭ_كوپ_سۋرەتتەر_بارى' ),
	'Mostrevisions'             => array( 'ەڭ_كوپ_نۇسقالار_بارى' ),
	'Fewestrevisions'           => array( 'ەڭ_از_تۇزەتىلگەن ' ),
	'Shortpages'                => array( 'قىسقا_بەتتەر' ),
	'Longpages'                 => array( 'ۇزىن_بەتتەر', 'ۇلكەن_بەتتەر' ),
	'Newpages'                  => array( 'جاڭا_بەتتەر' ),
	'Ancientpages'              => array( 'ەسكى_بەتتەر' ),
	'Deadendpages'              => array( 'تۇيىق_بەتتەر' ),
	'Protectedpages'            => array( 'قورعالعان_بەتتەر' ),
	'Protectedtitles'           => array( 'قورعالعان_اتاۋلار' ),
	'Allpages'                  => array( 'بارلىق_بەتتەر' ),
	'Prefixindex'               => array( 'ٴباستاۋىش_ٴتىزىمى' ) ,
	'Ipblocklist'               => array( 'بۇعاتتالعاندار' ),
	'Specialpages'              => array( 'ارنايى_بەتتەر' ),
	'Contributions'             => array( 'ۇلەسى' ),
	'Emailuser'                 => array( 'حات_جىبەرۋ' ),
	'Confirmemail'              => array( 'قۇپتاۋ_حات' ),
	'Whatlinkshere'             => array( 'مىندا_سىلتەگەندەر' ),
	'Recentchangeslinked'       => array( 'سىلتەنگەندەردىڭ_وزگەرىستەرى' ),
	'Movepage'                  => array( 'بەتتى_جىلجىتۋ' ),
	'Blockme'                   => array( 'وزدىكتىك_بۇعاتتاۋ', 'وزدىك_بۇعاتتاۋ', 'مەنى_بۇعاتتاۋ',),
	'Booksources'               => array( 'كىتاپ_قاينارلارى' ),
	'Categories'                => array( 'ساناتتار' ),
	'Export'                    => array( 'سىرتقا_بەرۋ' ),
	'Version'                   => array( 'نۇسقاسى' ),
	'Allmessages'               => array( 'بارلىق_حابارلار' ),
	'Log'                       => array( 'جۋرنال', 'جۋرنالدار' ),
	'Blockip'                   => array( 'جايدى_بۇعاتتاۋ', 'IP_بۇعاتتاۋ'),
	'Undelete'                  => array( 'جويۋدى_بولدىرماۋ', 'جويىلعاندى_قايتارۋ' ),
	'Import'                    => array( 'سىرتتان_الۋ' ),
	'Lockdb'                    => array( 'دەرەكقوردى_قۇلىپتاۋ' ),
	'Unlockdb'                  => array( 'دەرەكقوردى_قۇلىپتاماۋ' ),
	'Userrights'                => array( 'قاتىسۋشى_قۇقىقتارى' ),
	'MIMEsearch'                => array( 'MIME_تۇرىمەن_ىزدەۋ' ),
	'Unwatchedpages'            => array( 'باقىلانىلماعان_بەتتەر' ),
	'Listredirects'             => array( 'ٴايداتۋ_ٴتىزىمى' ),
	'Revisiondelete'            => array( 'نۇسقانى_جويۋ' ),
	'Unusedtemplates'           => array( 'پايدالانىلماعان_ۇلگىلەر' ),
	'Randomredirect'            => array( 'كەدەيسوق_ايداتۋ' ),
	'Mypage'                    => array( 'جەكە_بەتىم' ),
	'Mytalk'                    => array( 'تالقىلاۋىم' ),
	'Mycontributions'           => array( 'ۇلەسىم' ),
	'Listadmins'                => array( 'اكىمشىلەر', 'اكىمشى_تىزىمى'),
	'Listbots'                  => array( 'بوتتار', 'ٴبوتتار_ٴتىزىمى' ),
	'Popularpages'              => array( 'ەڭ_كوپ_قارالعان_بەتتەر', 'ايگىلى_بەتتەر' ),
	'Search'                    => array( 'ىزدەۋ' ),
	'Resetpass'                 => array( 'قۇپىيا_سوزدى_قايتارۋ' ),
	'Withoutinterwiki'          => array( 'ۋىيكىي-ارالىقسىزدار' ),
	'MergeHistory'              => array( 'تارىيح_بىرىكتىرۋ' ),
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------

$messages = array(
# User preference toggles
'tog-underline'               => 'سىلتەمەنىڭ استىن سىز:',
'tog-highlightbroken'         => 'جارامسىز سىلتەمەلەردى <a href="" class="new">بىلاي سىيياقتى</a> پىشىمدە (بالاماسى: بىلاي سىيياقتى<a href="" class="internal">?</a>).',
'tog-justify'                 => 'ەجەلەردى ەنى بويىنشا تۋرالاۋ',
'tog-hideminor'               => 'جۋىقتاعى وزگەرىستەردەن شاعىندارىن جاسىر',
'tog-extendwatchlist'         => 'باقىلاۋ ٴتىزىمدى ۇلعايت (بارلىق جارامدى وزگەرىستەردى كورسەت)',
'tog-usenewrc'                => 'كەڭەيتىلگەن جۋىقتاعى وزگەرىستەر (JavaScript)',
'tog-numberheadings'          => 'باس جولداردى وزدىكتىك نومىرلە',
'tog-showtoolbar'             => 'وڭدەۋ قۋرالدار جولاعىن كورسەت (JavaScript)',
'tog-editondblclick'          => 'قوس نۇقىمداپ وڭدەۋ (JavaScript)',
'tog-editsection'             => 'بولىمدەردى [وڭدەۋ] سىلتەمەسىمەن وڭدەۋىن قوس',
'tog-editsectiononrightclick' => 'ٴبولىم اتاۋىن وڭ جاق نۇقۋمەن وڭدەۋىن قوس (JavaScript)',
'tog-showtoc'                 => 'مازمۇنىن كورسەت (3-تەن ارتا ٴبولىمى بارىلارعا)',
'tog-rememberpassword'        => 'كىرگەنىمدى وسى كومپيۋتەردە ۇمىتپا',
'tog-editwidth'               => 'وڭدەۋ اۋماعى تولىق ەنىمەن',
'tog-watchcreations'          => 'مەن باستاعان بەتتەردى باقىلاۋ تىزىمىمە ۇستە',
'tog-watchdefault'            => 'مەن وڭدەگەن بەتتەردى باقىلاۋ تىزىمىمە ۇستە',
'tog-watchmoves'              => 'مەن جىلجىتقان بەتتەردى باقىلاۋ تىزىمىمە ۇستە',
'tog-watchdeletion'           => 'مەن جويعان بەتتەردى باقىلاۋ تىزىمىمە ۇستە',
'tog-minordefault'            => 'ادەپكىدەن بارلىق تۇزەتۋلەردى شاعىن دەپ بەلگىلە',
'tog-previewontop'            => 'قاراپ شىعۋ اۋماعى وڭدەۋ اۋماعى الدىندا',
'tog-previewonfirst'          => 'ٴبىرىنشى وڭدەگەندە قاراپ شىعۋ',
'tog-nocache'                 => 'بەتتى قوسالقى قالتادا ساقتاۋدى ٴوشىر',
'tog-enotifwatchlistpages'    => 'باقىلانعان بەت وزگەرگەندە ماعان حات جىبەر',
'tog-enotifusertalkpages'     => 'تالقىلاۋىم وزگەرگەندە ماعان حات جىبەر',
'tog-enotifminoredits'        => 'شاعىن تۇزەتۋ تۋرالى دا ماعان حات جىبەر',
'tog-enotifrevealaddr'        => 'ە-پوشتا جايىمدى ەسكەرتۋ حاتتا اشىق كورسەت',
'tog-shownumberswatching'     => 'باقىلاپ تۇرعان قاتىسۋشىلاردىڭ سانىن كورسەت',
'tog-fancysig'                => 'قام قولتاڭبا (وزدىكتىك سىلتەمەسىز;)',
'tog-externaleditor'          => 'سىرتقى وڭدەۋىشتى ادەپكىدەن قولدان',
'tog-externaldiff'            => 'سىرتقى ايىرماعىشتى ادەپكىدەن قولدان',
'tog-showjumplinks'           => '«ٴوتىپ كەتۋ» قاتىناۋ سىلتەمەلەرىن قوس',
'tog-uselivepreview'          => 'تۋرا قاراپ شىعۋدى قولدانۋ (JavaScript) (سىناقتاما)',
'tog-forceeditsummary'        => 'وڭدەۋ سىيپاتتاماسى بوس قالعاندا ماعان ەسكەرت',
'tog-watchlisthideown'        => 'تۇزەتۋىمدى باقىلاۋ تىزىمنەن جاسىر',
'tog-watchlisthidebots'       => 'بوت تۇزەتۋىن باقىلاۋ تىزىمنەن جاسىر',
'tog-watchlisthideminor'      => 'شاعىن تۇزەتۋلەردى باقىلاۋ تىزىمىندە كورسەتپە',
'tog-nolangconversion'        => 'ٴتىل ٴتۇرى اۋدارىسىن ٴوشىر',
'tog-ccmeonemails'            => 'باسقا قاتىسۋشىعا جىبەرگەن حاتىمنىڭ كوشىرمەسىن ماعان دا جىبەر',
'tog-diffonly'                => 'ايىرما استىندا بەت ماعلۇماتىن كورسەتپە',

'underline-always'  => 'ارقاشان',
'underline-never'   => 'ەشقاشان',
'underline-default' => 'شولعىش بويىنشا',

'skinpreview' => '(قاراپ شىعۋ)',

# Dates
'sunday'        => 'جەكسەنبى',
'monday'        => 'دۇيسەنبى',
'tuesday'       => 'سەيسەنبى',
'wednesday'     => 'سارسەنبى',
'thursday'      => 'بەيسەنبى',
'friday'        => 'جۇما',
'saturday'      => 'سەنبى',
'sun'           => 'جەك',
'mon'           => 'ٴدۇي',
'tue'           => 'بەي',
'wed'           => 'ٴسار',
'thu'           => 'بەي',
'fri'           => 'جۇم',
'sat'           => 'سەن',
'january'       => 'قاڭتار',
'february'      => 'اقپان',
'march'         => 'ناۋرىز',
'april'         => 'cٴاۋىر',
'may_long'      => 'مامىر',
'june'          => 'ماۋسىم',
'july'          => 'شىلدە',
'august'        => 'تامىز',
'september'     => 'قىركۇيەك',
'october'       => 'قازان',
'november'      => 'قاراشا',
'december'      => 'جەلتوقسان',
'january-gen'   => 'قاڭتاردىڭ',
'february-gen'  => 'اقپاننىڭ',
'march-gen'     => 'ناۋرىزدىڭ',
'april-gen'     => 'ٴساۋىردىڭ',
'may-gen'       => 'مامىردىڭ',
'june-gen'      => 'ماۋسىمنىڭ',
'july-gen'      => 'شىلدەنىڭ',
'august-gen'    => 'تامىزدىڭ',
'september-gen' => 'قىركۇيەكتىڭ',
'october-gen'   => 'قازاننىڭ',
'november-gen'  => 'قاراشانىڭ',
'december-gen'  => 'جەلتوقساننىڭ',
'jan'           => 'قاڭ',
'feb'           => 'اقپ',
'mar'           => 'ناۋ',
'apr'           => 'cٴاۋ',
'may'           => 'مام',
'jun'           => 'ماۋ',
'jul'           => 'ٴشىل',
'aug'           => 'تام',
'sep'           => 'قىر',
'oct'           => 'قاز',
'nov'           => 'قار',
'dec'           => 'جەل',

# Categories related messages
'categories'             => 'ساناتتار',
'categoriespagetext'     => 'وسىندا ۋىيكىيدەگى بارلىق ساناتتارىنىڭ ٴتىزىمى بەرىلىپ تۇر.',
'pagecategories'         => '{{PLURAL:$1|سانات|ساناتتار}}',
'category_header'        => '«$1» ساناتىنداعى بەتتەر',
'subcategories'          => 'ساناتشالار',
'category-media-header'  => '«$1» ساناتىنداعى تاسپالار',
'category-empty'         => "''بۇل ساناتتا اعىمدا ەش بەت نە تاسپا جوق.''",
'listingcontinuesabbrev' => '(جالع.)',

'mainpagetext'      => "<big>'''مەدىياۋىيكىي باعدارلاماسى ٴساتتى ورناتىلدى.'''</big>",
'mainpagedocfooter' => 'ۋىيكىي باعدارلاماسىن پايدالانۋ اقپاراتى ٴۇشىن [http://meta.wikimedia.org/wiki/Help:Contents پايدالانۋشى نۇسقاۋلارىمەن] تانىسىڭىز.

== باستاۋ ٴۇشىن ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings باپتاۋ قالاۋلارىنىڭ ٴتىزىمى]
* [http://www.mediawiki.org/wiki/Manual:FAQ مەدىياۋىيكىيدىڭ جىيى قويىلعان ساۋالدارى]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce مەدىياۋىيكىي شىعۋ تۋرالى حات تاراتۋ ٴتىزىمى]',

'about'          => 'جوبا تۋرالى',
'article'        => 'ماعلۇمات بەتى',
'newwindow'      => '(جاڭا تەرەزەدە)',
'cancel'         => 'بولدىرماۋ',
'qbfind'         => 'تابۋ',
'qbbrowse'       => 'شولۋ',
'qbedit'         => 'وڭدەۋ',
'qbpageoptions'  => 'بۇل بەت',
'qbpageinfo'     => 'ٴماتىن ارالىعى',
'qbmyoptions'    => 'بەتتەرىم',
'qbspecialpages' => 'ارنايى بەتتەر',
'moredotdotdot'  => 'كوبىرەك…',
'mypage'         => 'جەكە بەتىم',
'mytalk'         => 'تالقىلاۋىم',
'anontalk'       => 'IP تالقىلاۋى',
'navigation'     => 'باعىتتاۋ',
'and'            => 'جانە',

# Metadata in edit box
'metadata_help' => 'قوسىمشا دەرەكتەر:',

'errorpagetitle'    => 'قاتەلىك',
'returnto'          => '$1 دەگەنگە قايتا ورالۋ.',
'tagline'           => '{{GRAMMAR:ablative|{{SITENAME}}}}',
'help'              => 'انىقتاما',
'search'            => 'ىزدەۋ',
'searchbutton'      => 'ىزدە',
'go'                => 'ٴوتۋ',
'searcharticle'     => 'ٴوت!',
'history'           => 'بەت تارىيحى',
'history_short'     => 'تارىيحى',
'updatedmarker'     => 'سوڭعى كەلىپ-كەتۋىمنەن بەرى جاڭالانعان',
'info_short'        => 'مالىمەت',
'printableversion'  => 'باسىپ شىعارۋ ٴۇشىن',
'permalink'         => 'تۇراقتى سىلتەمە',
'print'             => 'باسىپ شىعارۋ',
'edit'              => 'وڭدەۋ',
'editthispage'      => 'بەتتى وڭدەۋ',
'delete'            => 'جويۋ',
'deletethispage'    => 'بەتتى جويۋ',
'undelete_short'    => '{{PLURAL:$1|ٴبىر|$1}} تۇزەتۋ جويۋىن بولدىرماۋ',
'protect'           => 'قورعاۋ',
'protect_change'    => 'قورعاۋدى وزگەرتۋ',
'protectthispage'   => 'بەتتى قورعاۋ',
'unprotect'         => 'قورعاماۋ',
'unprotectthispage' => 'بەتتى قورعاماۋ',
'newpage'           => 'جاڭا بەت',
'talkpage'          => 'بەتتى تالقىلاۋ',
'talkpagelinktext'  => 'تالقىلاۋى',
'specialpage'       => 'ارنايى بەت',
'personaltools'     => 'جەكە قۇرالدار',
'postcomment'       => 'ماندەمە جىبەرۋ',
'articlepage'       => 'ماعلۇمات بەتىن قاراۋ',
'talk'              => 'تالقىلاۋ',
'views'             => 'كورىنىس',
'toolbox'           => 'قۇرالدار',
'userpage'          => 'قاتىسۋشى بەتىن قاراۋ',
'projectpage'       => 'جوبا بەتىن قاراۋ',
'imagepage'         => 'سۋرەت بەتىن قاراۋ',
'mediawikipage'     => 'حابار بەتىن قاراۋ',
'templatepage'      => 'ۇلگى بەتىن قاراۋ',
'viewhelppage'      => 'انىقتاما بەتىن قاراۋ',
'categorypage'      => 'سانات بەتىن قاراۋ',
'viewtalkpage'      => 'تالقىلاۋ بەتىن قاراۋ',
'otherlanguages'    => 'باسقا تىلدەردە',
'redirectedfrom'    => '($1 بەتىنەن ايداتىلعان)',
'redirectpagesub'   => 'ايداتۋ بەتى',
'lastmodifiedat'    => 'بۇل بەتتىڭ وزگەرتىلگەن سوڭعى كەزى: $2, $1.', # $1 date, $2 time
'viewcount'         => 'بۇل بەت {{PLURAL:$1|ٴبىر|$1}} رەت قاتىنالعان.',
'protectedpage'     => 'قورعالعان بەت',
'jumpto'            => 'مىندا ٴوتۋ:',
'jumptonavigation'  => 'باعىتتاۋ',
'jumptosearch'      => 'ىزدەۋ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} تۋرالى',
'aboutpage'         => 'Project:جوبا تۋرالى',
'bugreports'        => 'قاتەلىك ەسەپتەمەلەرى',
'bugreportspage'    => 'Project:قاتەلىك ەسەپتەمەلەرى',
'copyright'         => 'ماعلۇمات $1 بويىنشا قاتىنالادى.',
'copyrightpagename' => '{{SITENAME}} اۋتورلىق قۇقىقتارى',
'copyrightpage'     => '{{ns:project}}:اۋتورلىق قۇقىقتار',
'currentevents'     => 'اعىمداعى وقىيعالار',
'currentevents-url' => 'Project:اعىمداعى وقىيعالار',
'disclaimers'       => 'جاۋاپكەرشىلىكتەن باس تارتۋ',
'disclaimerpage'    => 'Project:جاۋاپكەرشىلىكتەن باس تارتۋ',
'edithelp'          => 'وندەۋ انىقتاماسى',
'edithelppage'      => 'Help:وڭدەۋ',
'faq'               => 'ٴجىيى قويىلعان ساۋالدار',
'faqpage'           => 'Project:ٴجىيى قويىلعان ساۋالدار',
'helppage'          => 'Help:مازمۇنى',
'mainpage'          => 'باستى بەت',
'policy-url'        => 'Project:ەرەجەلەر',
'portal'            => 'قاۋىم پورتالى',
'portal-url'        => 'Project:قاۋىم پورتالى',
'privacy'           => 'جەكە قۇپىياسىن ساقتاۋ',
'privacypage'       => 'Project:جەكە قۇپىياسىن ساقتاۋ',
'sitesupport'       => 'دەمەۋشىلىك',
'sitesupport-url'   => 'Project:دەمەۋشىلىك',

'badaccess'        => 'رۇقسات قاتەسى',
'badaccess-group0' => 'سۇراتىلعان ارەكەتىڭىزدى جەگۋىڭىزگە رۇقسات ەتىلمەيدى.',
'badaccess-group1' => 'سۇراتىلعان ارەكەتىڭىز $1 توبىنىڭ قاتىسۋشىلارىنا شەكتەلەدى.',
'badaccess-group2' => 'سۇراتىلعان ارەكەتىڭىز $1 توپتارى ٴبىرىنىڭ قاتۋسىشىلارىنا شەكتەلەدى.',
'badaccess-groups' => 'سۇراتىلعان ارەكەتىڭىز $1 توپتارى ٴبىرىنىڭ قاتۋسىشىلارىنا شەكتەلەدى.',

'versionrequired'     => 'MediaWiki $1 نۇسقاسى كەرەك',
'versionrequiredtext' => 'وسى بەتتى قولدانۋ ٴۇشىن MediaWiki $1 نۇسقاسى كەرەك. [[Special:Version|جۇيە نۇسقاسى بەتىن]] قاراڭىز.',

'ok'                      => 'جارايدى',
'pagetitle'               => '$1 — {{SITENAME}}',
'retrievedfrom'           => '«$1» بەتىنەن الىنعان',
'youhavenewmessages'      => 'سىزگە $1 بار ($2).',
'newmessageslink'         => 'جاڭا حابارلار',
'newmessagesdifflink'     => 'سوڭعى وزگەرىسىنە',
'youhavenewmessagesmulti' => '$1 دەگەندە جاڭا حابارلار بار',
'editsection'             => 'وڭدەۋ',
'editold'                 => 'وڭدەۋ',
'editsectionhint'         => 'ٴبولىمدى وڭدەۋ: $1',
'toc'                     => 'مازمۇنى',
'showtoc'                 => 'كورسەت',
'hidetoc'                 => 'جاسىر',
'thisisdeleted'           => 'قاراۋ, نە قالپىنا كەلتىرۋ مە?: $1',
'viewdeleted'             => 'قاراۋ ما?: $1',
'restorelink'             => 'جويىلعان {{PLURAL:$1|ٴبىر|$1}} تۇزەتۋ',
'feedlinks'               => 'ارنا:',
'feed-invalid'            => 'جارامسىز جازىلىمدى ارنا ٴتۇرى.',
'site-rss-feed'           => '$1 RSS ارناسى',
'site-atom-feed'          => '$1 Atom ارناسى',
'page-rss-feed'           => '«$1» دەگەننىڭ RSS ارناسى',
'page-atom-feed'          => '«$1» دەگەننىڭ Atom ارناسى',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'بەت',
'nstab-user'      => 'جەكە بەت',
'nstab-media'     => 'تاسپا بەتى',
'nstab-special'   => 'ارنايى',
'nstab-project'   => 'جوبا بەتى',
'nstab-image'     => 'فايل بەتى',
'nstab-mediawiki' => 'حابار',
'nstab-template'  => 'ۇلگى',
'nstab-help'      => 'انىقتاما',
'nstab-category'  => 'سانات',

# Main script and global functions
'nosuchaction'      => 'بۇنداي ارەكەت جوق',
'nosuchactiontext'  => 'وسى URL جايىمەن ەنگىزىلگەن ارەكەتتى
وسى ۋىيكىي جورامالداپ بىلمەدى.',
'nosuchspecialpage' => 'بۇنداي ارنايى بەت جوق',
'nospecialpagetext' => "<big>'''سۇراتىلعان ارنايى بەتىڭىز جارامسىز.'''</big>

جارامدى ارنايى بەت ٴتىزىمىن [[{{ns:special}}:Specialpages]] دەگەننەن تابا الاسىز.",

# General errors
'error'                => 'قاتە',
'databaseerror'        => 'دەرەكقور قاتەسى',
'dberrortext'          => 'دەرەكقور سۇرانىمىندا سىينتاكسىيس قاتەسى بولدى.
بۇل باعدارلاما قاتەسىن بەلگىلەۋى مۇمكىن.
سوڭعى بولعان دەرەكقور سۇرانىمى:
<blockquote><tt>$1</tt></blockquote>
مىنا فۋنكتسىياسىنان «<tt>$2</tt>».
MySQL قايتارعان قاتەسى «<tt>$3: $4</tt>».',
'dberrortextcl'        => 'دەرەكقور سۇرانىمىندا سىينتاكسىيس قاتەسى بولدى.
سوڭعى بولعان دەرەكقور سۇرانىمى:
«$1»
مىنا فۋنكتسىياسىنان: «$2».
MySQL قايتارعان قاتەسى «$3: $4»',
'noconnect'            => 'عافۋ ەتىڭىز! بۇل ۋىيكىيدە كەيبىر تەحنىيكالىق قىيىنشىلىقتار كەزدەستى, جانە دە دەرەكقور سەرۆەرىنە بايلانىسا المايدى.<br />
$1',
'nodb'                 => '$1 دەرەكقورى بولەكتەنبەدى',
'cachederror'          => 'تومەندە سۇراتىلعان بەتتىڭ قوسالقى قالتاداعى كوشىرمەسى, وسى بەت جاڭارتىلماعان بولۋى مۇمكىن.',
'laggedslavemode'      => 'قۇلاقتاندىرۋ: بەتتە جۋىقتاعى جاڭالاۋلار بولماۋى مۇمكىن.',
'readonly'             => 'دەرەكقورى قۇلىپتالعان',
'enterlockreason'      => 'قۇلىپتاۋ سەبەبىن ەنگىزىڭىز, قاي ۋاقىتقا دەيىن
قۇلىپتالعانىن كىرىستىرىپ',
'readonlytext'         => 'اعىمدا دەرەكقور جاڭا جازبا جانە تاعى باسقا وزگەرىستەر جاساۋدان قۇلىپتالىنعان. بۇل دەرەكقوردى جوندەتۋ باعدارلامالارىن ورىنداۋ ٴۇشىن بولۋى مۇمكىن, بۇنى بىتىرگەننەن سوڭ قالىپتى ىسكە قايتارىلادى.

قۇلىپتاعان اكىمشى بۇنى بىلاي تۇسىندىرەدى: $1',
'missingarticle'       => 'ىزدەستىرىلگەن «$1» اتاۋلى بەت ٴماتىنى دەرەكقوردا تابىلمادى.

بۇل داعدىدا ەسكىرگەن ايىرما سىلتەمەسىنە نەمەسە جويىلعان بەت تارىيحىنىڭ سىلتەمەسىنە
ەرگەننەن بولۋى مۇمكىن.

ەگەر بۇل بولجام دۇرىس سەبەپ بولماسا, باعدارلامامىزداعى قاتەگە تاپ بولۋىڭىز مۇمكىن.
بۇل تۋرالى ناقتى URL جايىنا اڭعارتىپ, اكىمشىگە ەسەپتەمە جىبەرىڭىز.',
'readonly_lag'         => 'جەتەك دەرەكقور سەرۆەرلەر باستاۋىشپەن قاداملانعاندا وسى دەرەكقور وزدىكتىك قۇلىپتالىنعان',
'internalerror'        => 'ىشكى قاتە',
'internalerror_info'   => 'ىشكى قاتەسى: $1',
'filecopyerror'        => '«$1» فايلى «$2» فايلىنا كوشىرىلمەدى.',
'filerenameerror'      => '«$1» فايل اتى «$2» اتىنا وزگەرتىلمەدى.',
'filedeleteerror'      => '«$1» فايلى جويىلمايدى.',
'directorycreateerror' => '«$1» قالتاسى جاراتىلمادى.',
'filenotfound'         => '«$1» فايلى تابىلمادى.',
'fileexistserror'      => '«$1» فايلعا جازۋعا بولمايدى: وسىنداي فايل بار تۇگە',
'unexpected'           => 'كۇتىلمەگەن ماعىنا: «$1» = «$2».',
'formerror'            => 'قاتەلىك: ٴپىشىن جىبەرىلمەيدى',
'badarticleerror'      => 'وسىنداي ارەكەت مىنا بەتتە اتقارىلمايدى.',
'cannotdelete'         => 'ايتىلمىش بەت نە سۋرەت جويىلمايدى. (بۇنى باسقا بىرەۋ الداقاشان جويعان مۇمكىن.)',
'badtitle'             => 'جارامسىز اتاۋ',
'badtitletext'         => 'سۇراتىلعان بەت اتاۋى جارامسىز, بوس, ٴتىلارا سىلتەمەسى نە ۋىيكىي-ارا اتاۋى بۇرىس ەنگىزىلگەن. اتاۋلاردا سۇيەمەلدەمەگەن بىرقاتار ارىپتەر بولۋى مۇمكىن.',
'perfdisabled'         => 'عافۋ ەتىڭىز! بۇل مۇمكىندىك, دەرەكقوردىڭ جىلدامىلىعىنا اسەر ەتىپ, ەشكىمگە ۋىيكىيدى پايدالانۋعا بەرمەگەسىن, ۋاقىتشا وشىرىلگەن.',
'perfcached'           => 'كەلەسى دەرەك قوسالقى قالتاسىنان الىنعان, سوندىقتان تولىقتاي جاڭالانماعان بولۋى مۇمكىن.',
'perfcachedts'         => 'كەلەسى دەرەك قوسالقى قالتاسىنان الىنعان, سوڭعى جاڭالانلعان كەزى: $1.',
'querypage-no-updates' => 'بۇل بەتتىڭ جاڭارتىلۋى اعىمدا وشىرىلگەن. دەرەكتەرى قازىر وزگەرتىلمەيدى.',
'wrong_wfQuery_params' => 'wfQuery() فۋنكتسىياسى ٴۇشىن بۇرىس باپتالىمدارى بار<br />
فۋنكتسىيا: $1<br />
سۇرانىم: $2',
'viewsource'           => 'قاينارىن قاراۋ',
'viewsourcefor'        => '$1 دەگەن ٴۇشىن',
'actionthrottled'      => 'ارەكەت باسەڭدەتىلدى',
'actionthrottledtext'  => 'سپامعا قارسى كۇرەس ەسەبىندە, وسى ارەكەتتى قىسقا ۋاقىتتا تىم كوپ رەت ورىنداۋىڭىز شەكتەلىندى, جانە بۇل شەكتەۋ شاماسىنان اسىپ كەتكەنسىز. بىرنەشە ٴمىينوتتان قايتا بايقاپ كورىڭىز.',
'protectedpagetext'    => 'وڭدەۋدى قاقپايلاۋ ٴۇشىن بۇل بەت قۇلىپتالىنعان.',
'viewsourcetext'       => 'بۇل بەتتىڭ قاينارىن قاراۋىڭىزعا جانە كوشىرىپ الۋڭىزعا بولادى:',
'protectedinterface'   => 'بۇل بەت باعدارلامانىڭ تىلدەسۋ ٴماتىنىن جەتىستىرەدى, سوندىقتان قىياناتتى قاقپايلاۋ ٴۇشىن وزگەرتۋى قۇلىپتالعان.',
'editinginterface'     => "'''قۇلاقتاندىرۋ:''' باعدارلاماعا تىلدەسۋ ٴماتىنىن جەتىستىرەتىن بەتىن وڭدەپ جاتىرسىز. بۇل بەتتىڭ وزگەرتۋى باسقا قاتىسۋشىلارعا پايدالانۋشىلىق تىلدەسۋى قالاي كورىنەتىنە اسەر ەتەدى. اۋدارۋ ٴۇشىن, MediaWiki باعدارلاماسىن جەرسىندىرۋ [http://translatewiki.net/wiki/Translating:Intro Betawiki] جوباسى پايدالانۋى جان-جاعىن قاراڭىز.",
'sqlhidden'            => '(SQL سۇرانىمى جاسىرىلعان)',
'cascadeprotected'     => 'بۇل بەت وڭدەۋدەن قورعالعان, سەبەبى كەلەسى «باۋلى قورعاۋى» قوسىلعان {{PLURAL:$1|بەتكە|بەتتەرگە}} كىرىستىرىلگەن:

$2',
'namespaceprotected'   => "'''$1''' ەسىم اياسىنداعى بەتتەردى وڭدەۋ ٴۇشىن رۇقساتىڭىز جوق.",
'customcssjsprotected' => 'بۇل بەتتى وڭدەۋگە رۇقساتىڭىز جوق, سەبەبى مىندا باسقا قاتىسۋشىنىڭ جەكە باپتاۋلارى بار.',
'ns-specialprotected'  => '{{ns:special}} ەسىم اياسىنداعى بەتتەر وڭدەلىنبەيدى',
'titleprotected'       => 'بۇل اتاۋدىڭ جاراتۋىن [[{{ns:user}}:$1|$1]] قورعادى. كەلتىرىلگەن سەبەبى: <i>$2</i>.',

# Login and logout pages
'logouttitle'                => 'قاتىسۋشى شىعۋى',
'logouttext'                 => '<strong>ەندى جۇيەدەن شىقتىڭىز.</strong><br />
جۇيەگە كىرمەستەن {{SITENAME}} جوباسىن پايدالانۋىن جالعاستىرا الاسىز,
نەمەسە ٴدال سول نە باسقا قاتىسۋشى بوپ قايتا كرۋىڭىز مۇمكىن.
اڭعارتپا: كەيبىر بەتتەر شولعىشتىڭ قوسالقى قالتاسىن بوساتقانشا دەيىن
ٴالى دە جۇيەگە كىرگەنىڭىزدەي كورىنۋى مۇمكىن.',
'welcomecreation'            => '== قوش كەلدىڭىز, $1! ==

تىركەلگىڭىز جاراتىلدى. {{SITENAME}} باپتاۋىڭىزدى قالاۋىڭىزبەن وزگەرتۋدى ۇمىتپاڭىز.',
'loginpagetitle'             => 'قاتىسۋشى كىرۋى',
'yourname'                   => 'قاتىسۋشى اتىڭىز:',
'yourpassword'               => 'قۇپىيا ٴسوزىڭىز:',
'yourpasswordagain'          => 'قۇپىيا ٴسوزدى قايتالاڭىز:',
'remembermypassword'         => 'مەنىڭ كىرگەنىمدى بۇل كومپيۋتەردە ۇمىتپا',
'yourdomainname'             => 'جەلى ۇيشىگىڭىز:',
'externaldberror'            => 'وسىندا نە سىرتقى تەڭدەستىرۋ دەرەكقورىندا قاتە بولدى, نەمەسە سىرتقى تىركەلگىڭىزدى جاڭالاۋ رۇقساتى جوق.',
'loginproblem'               => '<b>كىرۋىڭىز كەزىندە وسىندا قىيىندىققا تاپ بولدىق.</b><br />قايتا بايقاپ كورىڭىز.',
'login'                      => 'كىرۋ',
'loginprompt'                => '{{SITENAME}} تورابىنا كىرۋىڭىز ٴۇشىن «cookies» قوسىلۋى ٴتىيىستى.',
'userlogin'                  => 'كىرۋ / تىركەلۋ',
'logout'                     => 'شىعۋ',
'userlogout'                 => 'شىعۋ',
'notloggedin'                => 'كىرمەگەنسىز',
'nologin'                    => 'كىرمەگەنسىز بە? $1.',
'nologinlink'                => 'تىركەلىڭىز',
'createaccount'              => 'تىركەلۋ',
'gotaccount'                 => 'الداقاشان تىركەلدىڭىز بە? $1.',
'gotaccountlink'             => 'كىرىڭىز',
'createaccountmail'          => 'ە-پوشتامەن',
'badretype'                  => 'ەنگىزگەن قۇپىيا سوزدەرىڭىز ٴبىر بىرىنە سايكەس ەمەس.',
'userexists'                 => 'ەنگىزگەن قاتىسۋشى اتىڭىز الداقاشان پايدالانۋدا. باسقا اتاۋ تانداڭىز.',
'youremail'                  => 'ە-پوشتا جايىڭىز:',
'username'                   => 'قاتىسۋشى اتىڭىز:',
'uid'                        => 'قاتىسۋشى تەڭدەستىرۋىڭىز:',
'yourrealname'               => 'شىن اتىڭىز:',
'yourlanguage'               => 'ٴتىلىڭىز:',
'yourvariant'                => 'نۇسقاڭىز:',
'yournick'                   => 'لاقاپ اتىڭىز:',
'badsig'                     => 'قام قولتاڭباڭىز جارامسىز; HTML بەلگىشەلەرىن تەكسەرىڭىز.',
'badsiglength'               => 'لاقاپ اتىڭىز تىم ۇزىن; $1 نىشاننان اسپاۋى كەرەك.',
'email'                      => 'ە-پوشتاڭىز',
'prefs-help-realname'        => 'مىندەتتى ەمەس: ەنگىزسەڭىز, شىعارماڭىزدىڭ اۋتورلىعىن بەلگىلەۋى ٴۇشىن قولدانىلادى.',
'loginerror'                 => 'كىرۋ قاتەسى',
'prefs-help-email'           => 'مىندەتتى ەمەس: «قاتىسۋشى» نەمەسە «قاتىسۋشى_تالقىلاۋى» دەگەن بەتتەرىڭىز ارقىلى باسقالارعا بايلانىسۋ قوسىلادى. ٴوزىڭىزدىڭ كىم ەكەنىڭىزدى بىلدىرتپەيدى.',
'prefs-help-email-required'  => 'ە-پوشتا جايى كەرەك.',
'nocookiesnew'               => 'قاتىسۋشى تىركەلگىسى جاراتىلدى, بىراق كىرمەگەنسىز. قاتىسۋشى كىرۋ ٴۇشىن {{SITENAME}} تورابى «cookies» دەگەندى قولدانادى. سىزدە «cookies» وشىرىلگەن. سونى قوسىڭىز دا جاڭا قاتىسۋشى اتىڭىزدى جانە قۇپىيا ٴسوزىڭىزدى ەنگىزىپ كىرىڭىز.',
'nocookieslogin'             => 'قاتىسۋشى كىرۋ ٴۇشىن {{SITENAME}} تورابى «cookies» دەگەندى قولدانادى. سىزدە «cookies» وشىرىلگەن. سونى قوسىڭىز دا كىرۋدى قايتا بايقاپ كورىڭىز.',
'noname'                     => 'جارامدى قاتىسۋشى اتىن ەنگىزبەدىڭىز.',
'loginsuccesstitle'          => 'كىرۋىڭىز ٴساتتى ٴوتتى',
'loginsuccess'               => "'''ٴسىز ەندى {{SITENAME}} جوباسىنا «$1» رەتىندە كىرىپ وتىرسىز.'''",
'nosuchuser'                 => 'مىندا «$1» اتاۋلى قاتىسۋشى جوق. ەملەڭىزدى تەكسەرىڭىز, نەمەسە جاڭادان تىركەلىڭىز.',
'nosuchusershort'            => 'مىندا «<nowiki>$1</nowiki>» اتاۋلى قاتىسۋشى جوق. ەملەڭىزدى تەكسەرىڭىز.',
'nouserspecified'            => 'قاتىسۋشى اتىن ەنگىزىۋىڭىز كەرەك.',
'wrongpassword'              => 'بۇرىس قۇپىيا ٴسوز ەنگىزىلگەن. قايتا بايقاپ كورىڭىز.',
'wrongpasswordempty'         => 'قۇپىيا ٴسوز بوس بولعان. قايتا بايقاپ كورىڭىز.',
'passwordtooshort'           => 'قۇپىيا ٴسوزىڭىز جارامسىز نە تىم قىسقا. ەڭ كەمىندە $1 ٴارىپ جانە قاتىسۋشى اتىڭىزدان باسقا بولۋى كەرەك.',
'mailmypassword'             => 'قۇپىيا ٴسوزىمدى حاتپەن جىبەر',
'passwordremindertitle'      => '{{SITENAME}} ٴۇشىن جاڭا ۋاقىتشا قۇپىيا ٴسوز',
'passwordremindertext'       => 'كەيبىرەۋ (IP جايى: $1, بالكىم ٴوزىڭىز بولارسىز)
{{SITENAME}} ٴۇشىن بىزدەن جاڭا قۇپىيا ٴسوزىن جىبەرۋىن سۇراتىلعان ($4).
«$2» قاتىسۋشىنىڭ قۇپىيا ٴسوزى «$3» بولدى ەندى.
قازىر كىرۋىڭىز جانە قۇپىيا ٴسوزىڭىزدى اۋىسترۋىڭىز كەرەك.

ەگەر باسقا بىرەۋ بۇنى سۇراتىلعان بولسا, نەمەسە قۇپىيا ٴسوزىڭىزدى ۇمىتساڭىز دا,
جانە بۇنى وزگەرتكىڭىز كەلمەسە دە, وسى حابارلاماعا اڭعارماۋىڭىزعا دا بولادى,
ەسكى قۇپىيا ٴسوزىڭىزدى ارىعاراي قولدانىپ.',
'noemail'                    => 'مىندا «$1» قاتىسۋشىنىڭ ە-پوشتاسى جوق.',
'passwordsent'               => 'جاڭا قۇپىيا ٴسوز «$1» ٴۇشىن تىركەلگەن ە-پوشتا
جايىنا جىبەرىلدى.
قابىلداعاننان كەيىن كىرگەندە سونى ەنگىزىڭىز.',
'blocked-mailpassword'       => 'IP جايىڭىزدان وڭدەۋ بۇعاتتالعان, سوندىقتان
قىياناتتى قاقپايلاۋ ٴۇشىن قۇپىيا ٴسوز جىبەرۋ قىزمەتىنىڭ ارەكەتى رۇقسات ەتىلمەيدى.',
'eauthentsent'               => 'قۇپتاۋ حاتى اتالمىش ە-پوشتا جايىنا جىبەرىلدى.
باسقا ە-پوشتا حاتىن جىبەرۋدىڭ الدىنان, تىركەلگى شىنىنان سىزدىكى ەكەنىن
قۇپتاۋ ٴۇشىن حاتتاعى نۇسقاۋلارعا ەرىڭىز.',
'throttled-mailpassword'     => 'سوڭعى $1 ساعاتتا قۇپىيا ٴسوز ەسكەرتۋ حاتى الداقاشان جىبەرىلدى.
قىياناتتى قاقپايلاۋ ٴۇشىن, $1 ساعات سايىن تەك ٴبىر عانا قۇپىيا ٴسوز ەسكەرتۋ
حاتى جىبەرىلەدى.',
'mailerror'                  => 'حات جىبەرۋ قاتەسى: $1',
'acct_creation_throttle_hit' => 'عافۋ ەتىڭىز, ٴسىز الداقاشان $1 رەت تىركەىلىپسىز. ونان ارتىق ىستەي المايسىز.',
'emailauthenticated'         => 'ە-پوشتا جايىڭىز قۇپتالعان كەزى: $1.',
'emailnotauthenticated'      => 'ە-پوشتا جايىڭىز ٴالى قۇپتالعان جوق.
تومەندەگى مۇمكىندىكتەر ٴۇشىن ەشقانداي حات جىبەرىلمەيدى.',
'noemailprefs'               => 'وسى مۇمكىندىكتەر ىستەۋى ٴۇشىن ە-پوشتا جايىڭىزدى ەنگىزىڭىز.',
'emailconfirmlink'           => 'ە-پوشتا جايىڭىزدى قۇپتاڭىز',
'invalidemailaddress'        => 'وسى ە-پوشتا جايدا جارامسىز ٴپىشىم بولعان, قابىل ەتىلمەيدى.
دۇرىس پىشىمدەلگەن جايدى ەنگىزىڭىز, نە اۋماقتى بوس قالدىرىڭىز.',
'accountcreated'             => 'تىركەلگى جاراتىلدى',
'accountcreatedtext'         => '$1 ٴۇشىن قاتىسۋشى تىركەلگىسى جاراتىلدى.',
'createaccount-title'        => '{{SITENAME}} ٴۇشىن تىركەلۋ',
'createaccount-text'         => 'كەيبىرەۋ ($1) {{SITENAME}} جوباسىندا $2 ٴۇشىن تىركەلگەن ($4).
«$2» دەگەننىڭ قۇپىيا ٴسوزى — «$3». قازىر كىرىڭىز دە قۇپىيا ٴسوزىڭىزدى
وزگەرتىڭىز

ەگەر وسى تىركەلگى قاتەلىكپەن جاراتىلسا, بۇل حابارعا ەلەمەۋىڭىز مۇمكىن.',
'loginlanguagelabel'         => 'ٴتىل: $1',

# Password reset dialog
'resetpass'               => 'تىركەلگىنىڭ قۇپىيا ٴسوزىن وزگەرتۋ',
'resetpass_announce'      => 'حاتپەن جىبەرىلگەن ۋاقىتشا بەلگىلەمەمەن كىرىپسىز. تىركەلۋدى ٴبىتىرۋ ٴۇشىن جاڭا قۇپىيا ٴسوزىڭىزدى مىندا ەنگىزىڭىز:',
'resetpass_header'        => 'قۇپىيا ٴسوزدى وزگەرتۋ',
'resetpass_submit'        => 'قۇپىيا ٴسوزدى قويىڭىز دا كىرىڭىز',
'resetpass_success'       => 'قۇپىيا ٴسوزىڭىز ٴساتتى وزگەرتىلدى! ەندى كىرىڭىز…',
'resetpass_bad_temporary' => 'ۋاقىتشا قۇپىيا ٴسوز جارامسىز. مۇمكىن قۇپىيا ٴسوزىڭىزدى الداقاشان ٴساتتى وزگەرتكەن بولارسىز نەمەسە جاڭا ۋاقىتشا قۇپىيا ٴسوزىن سۇراتىلعانسىز.',
'resetpass_forbidden'     => '{{SITENAME}} جوباسىندا قۇپىيا سوزدەر وزگەرتىلمەيدى',
'resetpass_missing'       => 'ەش ٴپىشىن دەرەكتەرى جوق.',

# Edit page toolbar
'bold_sample'     => 'جۋان ٴماتىن',
'bold_tip'        => 'جۋان ٴماتىن',
'italic_sample'   => 'قىيعاش ٴماتىن',
'italic_tip'      => 'قىيعاش ٴماتىن',
'link_sample'     => 'سىلتەمە اتاۋى',
'link_tip'        => 'ىشكى سىلتەمە',
'extlink_sample'  => 'http://www.example.com سىلتەمە اتاۋى',
'extlink_tip'     => 'سىرتقى سىلتەمە (الدىنان http:// ەنگىزۋىن ۇمىتپاڭىز)',
'headline_sample' => 'باس جول ٴماتىنى',
'headline_tip'    => '2-ٴشى دەڭگەيلى باس جول',
'math_sample'     => 'فورمۋلانى مىندا ەنگىزىڭىز',
'math_tip'        => 'ماتەماتىيكا فورمۋلاسى (LaTeX)',
'nowiki_sample'   => 'پىشىمدەلمەيتىن ٴماتىندى وسىندا ەنگىزىڭىز',
'nowiki_tip'      => 'ۋىيكىي ٴپىشىمىن ەلەمەۋ',
'image_tip'       => 'ەندىرىلگەن سۋرەت',
'media_tip'       => 'تاسپا فايلىنىڭ سىلتەمەسى',
'sig_tip'         => 'قولتاڭباڭىز جانە ۋاقىت بەلگىسى',
'hr_tip'          => 'دەرەلەي سىزىق (ۇنەمدى قولدانىڭىز)',

# Edit pages
'summary'                   => 'سىيپاتتاماسى',
'subject'                   => 'تاقىرىبى/باس جولى',
'minoredit'                 => 'بۇل شاعىن تۇزەتۋ',
'watchthis'                 => 'بەتتى باقىلاۋ',
'savearticle'               => 'بەتتى ساقتا!',
'preview'                   => 'قاراپ شىعۋ',
'showpreview'               => 'قاراپ شىق',
'showlivepreview'           => 'تۋرا قاراپ شىق',
'showdiff'                  => 'وزگەرىستەردى كورسەت',
'anoneditwarning'           => "'''قۇلاقتاندىرۋ:''' ٴسىز جۇيەگە كىرمەگەنسىز. IP جايىڭىز بۇل بەتتىڭ وڭدەۋ تارىيحىندا جازىلىپ الىنادى.",
'missingsummary'            => "'''ەسكەرتپە:''' تۇزەتۋ سىيپاتتاماسىن ەنگىزبەپسىز. «ساقتاۋ» تۇيمەسىن تاعى باسساڭىز, تۇزەتۋىڭىز ماندەمەسىز ساقتالادى.",
'missingcommenttext'        => 'تومەندە ماندەمەڭىزدى ەنگىزىڭىز.',
'missingcommentheader'      => "'''ەسكەرتپە:''' بۇل ماندەمەگە تاقىرىپ/باسجول جەتىستىرمەپسىز. ەگەر تاعى دا ساقتاۋ تۇيمەسىن نۇقىساڭىز, تۇزەتۋىڭىز سولسىز ساقتالادى.",
'summary-preview'           => 'سىيپاتتاماسىن قاراپ شىعۋ',
'subject-preview'           => 'تاقىرىبىن/باس جولىن قاراپ شىعۋ',
'blockedtitle'              => 'قاتىسۋشى بۇعاتتالعان',
'blockedtext'               => "<big>'''قاتىسۋشى اتىڭىز نە IP جايىڭىز بۇعاتتالعان.'''</big>

وسى بۇعاتتاۋدى $1 ىستەگەن. كەلتىرىلگەن سەبەبى: ''$2''.

* بۇعاتتاۋ باستالعانى: $8
* بۇعاتتاۋ بىتەتىنى: $6
* بۇعاتتاۋ ماقساتى: $7

وسى بۇعاتتاۋدى تالقىلاۋ ٴۇشىن $1 دەگەنمەن, نە باسقا [[{{{{ns:mediawiki}}:grouppage-sysop}}|اكىمشىمەن]] قاتىناسۋىڭىزعا بولادى.
[[{{ns:special}}:Preferences|تىركەلگى باپتاۋلارىن]] قولدانىپ جارامدى ە-پوشتا جايىن ەنگىزگەنشە دەيىن جانە بۇنى پايدالانۋى 
بۇعاتتالماعانشا دەيىن «قاتىسۋشىعا حات جازۋ» مۇمكىندىگىن قولدانا المايسىز.
اعىمدىق IP جايىڭىز: $3, جانە بۇعاتاۋ ٴنومىرى: $5. سونىڭ بىرەۋىن, نەمەسە ەكەۋىن دە ٴاربىر سۇرانىمىڭىزعا كىرىستىرىڭىز.",
'autoblockedtext'           => "$1 دەگەن بۇرىن باسقا قاتىسۋشى پايدالانعان بولعاسىن وسى IP جايىڭىز وزدىكتىك بۇعاتتالعان.
كەلتىرىلگەن سەبەبى:

:''$2''

* بۇعاتتاۋ باستالعانى: $8
* بۇعاتتاۋ بىتەتىنى: $6

وسى بۇعاتتاۋدى تالقىلاۋ ٴۇشىن $1 دەگەنمەن,
نە باسقا [[{{{{ns:mediawiki}}:grouppage-sysop}}|اكىمشىمەن]] قاتىناسۋىڭىزعا بولادى.

اڭعارتپا: [[{{ns:special}}:Preferences|تىركەلگى باپتاۋلارىن]] قولدانىپ جارامدى ە-پوشتا جايىن ەنگىزگەنشە 
دەيىن جانە بۇنى پايدالانۋى بۇعاتتالماعانشا دەيىن «قاتىسۋشىعا حات جازۋ» مۇمكىندىگىن قولدانا المايسىز. 

بۇعاتاۋ ٴنومىرىڭىز: $5. بۇل ٴنومىردى ٴاربىر سۇرانىمىڭىزعا كىرىستىرىڭىز.",
'blockednoreason'           => 'ەش سەبەبى كەلتىرىلمەگەن',
'blockedoriginalsource'     => "'''$1''' دەگەننىڭ قاينارى 
تومەندە كورسەتىلەدى:",
'blockededitsource'         => "'''$1''' دەگەنگە جاسالعان '''تۇزەتۋلەرىڭىزدىڭ''' ٴماتىنى تومەندە كورسەتىلەدى:",
'whitelistedittitle'        => 'وڭدەۋ ٴۇشىن كىرۋىڭىز ٴجون.',
'whitelistedittext'         => 'بەتتەردى وڭدەۋ ٴۇشىن $1 ٴجون.',
'whitelistreadtitle'        => 'وقۋ ٴۇشىن كىرۋىڭىز ٴجون',
'whitelistreadtext'         => 'بەتتەردى وقۋ ٴۇشىن [[Special:Userlogin|كىرۋىڭىز]] ٴجون.',
'whitelistacctitle'         => 'تىركەلۋىڭىزگە رۇقسات بەرىلمەگەن',
'whitelistacctext'          => '{{SITENAME}} جوباسىنا تىركەلۋگە ٴۇشىن [[Special:Userlogin|كىرۋىڭىز]] كەرەك جانە جاناسىمدى رۇقساتتارىڭىز بولۋ كەرەك.',
'confirmedittitle'          => 'ە-پوشتا جايىن قۇپتاۋ حاتى قايتا وڭدەلۋى كەرەك',
'confirmedittext'           => 'بەتتەردى وڭدەۋ ٴۇشىن الدىن الا ە-پوشتا جايىڭىزدى قۇپتاۋىڭىز كەرەك. جايىڭىزدى [[Special:Preferences|قاتىسۋشى باپتاۋى]] ارقىلى قويىڭىز دا جارامدىلىعىن تەكسەرىپ شىعىڭىز.',
'nosuchsectiontitle'        => 'بۇنداي ٴبولىم جوق',
'nosuchsectiontext'         => 'جوق ٴبولىمدى وڭدەۋدى تالاپ ەتىپسىز. مىندا $1 دەگەن ٴبولىم جوق ەكەن, وڭدەۋلەرىڭىزدى ساقتاۋ ٴۇشىن ورىن جوق.',
'loginreqtitle'             => 'كىرۋىڭىز كەرەك',
'loginreqlink'              => 'كىرۋ',
'loginreqpagetext'          => 'باسقا بەتتەردى كورۋ ٴۇشىن ٴسىز $1 بولۋىڭىز كەرەك.',
'accmailtitle'              => 'قۇپىيا ٴسوز جىبەرىلدى.',
'accmailtext'               => '$2 جايىنا «$1» قۇپىيا ٴسوزى جىبەرىلدى.',
'newarticle'                => '(جاڭا)',
'newarticletext'            => 'سىلتەمەگە ەرىپ ٴالى باستالماعان بەتكە كەلىپسىز.
بەتتى باستاۋ ٴۇشىن, تومەندەگى اۋماقتا ٴماتىنىڭىزدى تەرىڭىز
(كوبىرەك اقپارات ٴۇشىن [[{{{{ns:mediawiki}}:helppage}}|انىقتاما بەتىن]] قاراڭىز).
ەگەر جاڭىلعاننان وسىندا كەلگەن بولساڭىز, شولعىشىڭىز «ارتقا» دەگەن باتىرماسىن نۇقىڭىز.',
'anontalkpagetext'          => "----''بۇل تىركەلگىسىز (نەمەسە تىركەلگىسىن قولدانباعان) قاتىسۋشى تالقىلاۋ بەتى. وسى قاتىسۋشىنى ٴبىز تەك ساندىق IP جايىمەن تەڭدەستىرەمىز. وسىنداي IP جايلار بىرنەشە قاتىسۋشىعا ورتاق بولۋى مۇمكىن. ەگەر ٴسىز تىركەلگىسىز قاتىسۋشى بولساڭىز جانە سىزگە قاتىسسىز ماندەمەلەر جىبەرىلگەنىن سەزسەڭىز, باسقا تىركەلگىسىز قاتىسۋشىلارمەن ارالاستىرماۋى ٴۇشىن [[{{ns:special}}:Userlogin|تىركەلىڭىز نە كىرىڭىز]].''",
'noarticletext'             => 'بۇل بەتتە اعىمدا ەش ٴماتىن جوق, باسقا بەتتەردەن وسى بەت اتاۋىن [[Special:Search/{{PAGENAME}}|ىزدەپ كورۋىڭىزگە]] نەمەسە وسى بەتتى [{{fullurl:{{FULLPAGENAME}}|action=edit}} تۇزەتۋىڭىزگە] بولادى.',
'userpage-userdoesnotexist' => '«$1» قاتىسۋشى تىركەلگىسى جازىپ الىنباعان. بۇل بەتتى باستاۋ/وڭدەۋ تالابىڭىزدى تەكسەرىپ شىعىڭىز.',
'clearyourcache'            => "'''اڭعارتپا:''' ساقتاعاننان كەيىن وزگەرىستەردى كورۋ ٴۇشىن شولعىش قوسالقى قالتاسىن بوساتۋ كەرەگى مۇمكىن. '''Mozilla  / Safari:''' ''Shift'' پەرنەسىن باسىپ تۇرىپ ''Reload'' (''قايتا جۇكتەۋ'') باتىرماسىن نۇقىڭىز (نە ''Ctrl-Shift-R'' باسىڭىز); ''IE:'' ''Ctrl-F5'' باسىڭىز; '''Opera / Konqueror''' ''F5'' پەرنەسىن باسىڭىز.",
'usercssjsyoucanpreview'    => '<strong>باسالقى:</strong> ساقتاۋ الدىندا جاڭا CSS/JS فايلىن تەكسەرۋ ٴۇشىن «قاراپ شىعۋ» باتىرماسىن قولدانىڭىز.',
'usercsspreview'            => "'''مىناۋ CSS ٴماتىنىن تەك قاراپ شىعۋ ەكەنىن ۇمىتپاڭىز, ول ٴالى ساقتالعان جوق!'''",
'userjspreview'             => "'''مىناۋ JavaScript قاتىسۋشى باعدارلاماسىن تەكسەرۋ/قاراپ شىعۋ ەكەنىن ۇمىتپاڭىز, ول ٴالى ساقتالعان جوق!'''",
'userinvalidcssjstitle'     => "'''قۇلاقتاندىرۋ:''' بۇل «$1» دەگەن بەزەندىرۋ مانەرى ەمەس. قاتىسۋشىنىڭ .css جانە .js فايل اتاۋى كىشى ارىپپپەن جازىلۋ ٴتىيىستى ەكەنىن ۇمىتپاڭىز, مىسالعا {{ns:user}}:Foo/monobook.css دەگەندى {{ns:user}}:Foo/Monobook.css دەگەنمەن سالىستىرىپ قاراڭىز.",
'updated'                   => '(جاڭارتىلعان)',
'note'                      => '<strong>اڭعارتپا:</strong>',
'previewnote'               => '<strong>مىناۋ تەك قاراپ شىعۋ ەكەنىن ۇمىتپاڭىز; تۇزەتۋلەر ٴالى ساقتالعان جوق!</strong>',
'previewconflict'           => 'بۇل قاراپ شىعۋ جوعارىداعى وڭدەۋ اۋماعىنداعى ماتىنگە ساقتاعان كەزىندەگى دەي ىقپال ەتەدى.',
'session_fail_preview'      => '<strong>عافۋ ەتىڭىز! سەسسىيا دەرەكتەرى ىسىراپ قالعاندىقتان وڭدەۋىڭىزدى جوندەي المايمىز.
قايتا بايقاپ كورىڭىز. ەگەر بۇل ٴالى ىستەمەسە, شىعۋدى جانە قايتا كىرۋدى بايقاپ كورىڭىز.</strong>',
'session_fail_preview_html' => "<strong>عافۋ ەتىڭىز! سەسسىيا دەرەكتەرى ىسىراپ قالعاندىقتان وڭدەۋىڭىزدى جوندەي المايمىز.</strong>

''{{SITENAME}} جوباسىندا قام HTML قوسىلعان, JavaScript شابۋىلداردان قورعانۋ ٴۇشىن الدىن الا قاراپ شىعۋ جاسىرىلعان.''

<strong>ەگەر بۇل وڭدەۋ ادال تالاپ بولسا, قايتا بايقاپ كورىڭىز. ەگەر بۇل ٴالى ىستەمەسە, شىعۋدى جانە قايتا كىرۋدى بايقاپ كورىڭىز.</strong>",
'token_suffix_mismatch'     => '<strong>تۇزەتۋىڭىز تايدىرىلدى, سەبەبى تۇتىنعىشىڭىز وڭدەۋ نىشانىندا 
ەملە تاڭبالارىن كەسكىلەپ تاستادى. بەت ٴماتىنى بۇلىنبەۋ ٴۇشىن تۇزەتۋىڭىز تايدىرىلادى.
بۇل عالامتورعا نەگىزدەلىنگەن قاتە تولعان تىركەلۋى جوق پروكسىي-سەرۆەردى پايدالانعان بولۋى مۇمكىن.</strong>',
'editing'                   => 'وڭدەلۋدە: $1',
'editingsection'            => 'وڭدەلۋدە: $1 (ٴبولىمى)',
'editingcomment'            => 'وڭدەلۋدە: $1 (ماندەمەسى)',
'editconflict'              => 'وڭدەۋ قاقتىعىسى: $1',
'explainconflict'           => 'وسى بەتتى ٴسىز وڭدەي باستاعاندا باسقا بىرەۋ بەتتى وزگەرتكەن.
جوعارعى اۋماقتا بەتتىڭ اعىمدىق ٴماتىنى بار.
تومەنگى اۋماقتا ٴسىز وزگەرتكەن ٴماتىنى كورسەتىلەدى.
وزگەرتۋىڭىزدى اعىمدىق ماتىنگە ۇستەۋىڭىز ٴجون.
"بەتتى ساقتا!" تۇيمەسىنە باسقاندا
<b>تەك</b> جوعارعى اۋماقتاعى ٴماتىن ساقتالادى.<br />',
'yourtext'                  => 'ٴماتىنىڭىز',
'storedversion'             => 'ساقتالعان نۇسقاسى',
'nonunicodebrowser'         => '<strong>قۇلاقتاندىرۋ: شولعىشىڭىز Unicode بەلگىلەۋىنە ۇيلەسىمدى ەمەس, سوندىقتان لاتىن ەمەس ارىپتەرى بار بەتتەردى وڭدەۋ ٴزىل بولۋ مۇمكىن. جۇمىس ىستەۋگە ىقتىيمالدىق بەرۋ ٴۇشىن, تومەنگى وڭدەۋ اۋماعىندا ASCII ەمەس ارىپتەر ونالتىلىق سانىمەن كورسەتىلەدى</strong>.',
'editingold'                => '<strong>قۇلاقتاندىرۋ: وسى بەتتىڭ ەرتەرەك نۇسقاسىن
وڭدەپ جاتىرسىز.
بۇنى ساقتاساڭىز, وسى نۋسقادان سوڭعى بارلىق وزگەرىستەر جويىلادى.</strong>',
'yourdiff'                  => 'ايىرمالار',
'copyrightwarning'          => 'اڭعارتپا: {{SITENAME}} جوباسىنا بەرىلگەن بارلىق ۇلەستەر $2 (كوبىرەك اقپارات ٴۇشىن: $1) قۇجاتىنا ساي دەپ سانالادى. ەگەر جازۋىڭىزدىڭ ەركىن تۇزەتىلۋىن جانە اقىسىز كوپشىلىككە تاراتۋىن قالاماساڭىز, مىندا جارىيالاماۋىڭىز ٴجون.<br />
تاعى دا, بۇل ماعلۇمات ٴوزىڭىز جازعانىڭىزعا, نە قوعام قازىناسىنان نەمەسە سونداي اشىق قاينارلاردان كوشىرىلگەنىنە بىزگە ۋادە بەرەسىز.
<strong>اۋتورلىق قۇقىقپەن قورعاۋلى ماعلۇماتتى رۇقساتسىز جارىيالاماڭىز!</strong>',
'copyrightwarning2'         => 'اڭعارتپا: {{SITENAME}} جوباسىنا بەرىلگەن بارلىق ۇلەستەردى باسقا ۇلەسكەرلەر تۇزەتۋگە, وزگەرتۋگە, نە الاستاۋعا مۇمكىن. ەگەر جازۋىڭىزدىڭ ەركىن تۇزەتىلۋىن قالاماساڭىز, مىندا جارىيالاماۋىڭىز ٴجون.<br />
تاعى دا, بۇل ماعلۇمات ٴوزىڭىز جازعانىڭىزعا, نە قوعام قازىناسىنان نەمەسە سونداي اشىق قاينارلاردان كوشىرىلگەنىنە بىزگە ۋادە بەرەسىز
(كوبىرەك اقپارات ٴۇشىن $1 قۋجاتىن قاراڭىز).
<strong>اۋتورلىق قۇقىقپەن قورعاۋلى ماعلۇماتتى رۇقساتسىز جارىيالاماڭىز!</strong>',
'longpagewarning'           => '<strong>قۇلاقتاندىرۋ: بۇل بەتتىڭ مولشەرى — $1 KB; كەيبىر
شولعىشتاردا بەت مولشەرى 32 KB جەتسە نە ونى اسسا وڭدەۋ كۇردەلى بولۋى مۇمكىن.
بەتتى بىرنەشە كىشكىن بولىمدەرگە ٴبولىپ كورىڭىز.</strong>',
'longpageerror'             => '<strong>قاتەلىك: جىبەرەتىن ٴماتىنىڭىزدىن مولشەرى — $1 KB, ەڭ كوبى $2 KB
رۇقسات ەتىلگەن مولشەرىنەن اسقان. بۇل ساقتاي الىنبايدى.</strong>',
'readonlywarning'           => '<strong>قۇلاقتاندىرۋ: دەرەكقور جوندەتۋ ٴۇشىن قۇلىپتالعان,
سوندىقتان ٴدال قازىر تۇزەتۋىڭىزدى ساقتاي المايسىز. سوسىن قولدانۋعا ٴۇشىن ٴماتانىڭىزدى كوشىرىپ,
ٴوز كومپۇتەرىڭىزدە فايلعا ساقتاڭىز.</strong>',
'protectedpagewarning'      => '<strong>قۇلاقتاندىرۋ: بۇل بەت قورعالعان. تەك اكىمشى رۇقساتى بار قاتىسۋشىلار وڭدەۋ جاساي الادى.</strong>',
'semiprotectedpagewarning'  => "'''اڭعارتپا:''' بەت جارتىلاي قورعالعان, سوندىقتان وسىنى تەك تىركەلگەن قاتىسۋشىلار وڭدەي الادى.",
'cascadeprotectedwarning'   => "'''قۇلاقتاندىرۋ''': بۇل بەت قۇلىپتالعان, ەندى تەك اكىمشى قۇقىقتارى بار قاتىسۋشىلار بۇنى وڭدەي الادى.بۇنىڭ سەبەبى: بۇل بەت «باۋلى قورعاۋى» بار كەلەسى {{PLURAL:$1|بەتكە|بەتتەرگە}} كىرىستىرىلگەن:",
'titleprotectedwarning'     => '<strong>قۇلاقتاندىرۋ:  بۇل بەت قۇلىپتالعان, سوندىقتان تەك بىرقاتار قاتىسۋشىلار بۇنى جاراتا الادى.</strong>',
'templatesused'             => 'بۇل بەتتە قولدانىلعان ۇلگىلەر:',
'templatesusedpreview'      => 'بۇنى قاراپ شىعۋعا قولدانىلعان ۇلگىلەر:',
'templatesusedsection'      => 'بۇل بولىمدە قولدانىلعان ۇلگىلەر:',
'template-protected'        => '(قورعالعان)',
'template-semiprotected'    => '(جارتىلاي قورعالعان)',
'nocreatetitle'             => 'بەتتى باستاۋ شەكتەلگەن',
'nocreatetext'              => '{{SITENAME}} جوباسىندا جاڭا بەت باستاۋى شەكتەلگەن.
كەرى قايتىپ بار بەتتى وڭدەۋىڭىزگە بولادى, نەمەسە [[{{ns:special}}:Userlogin|كىرۋىڭىزگە نە تىركەلۋىڭىزگە]] بولادى.',
'nocreate-loggedin'         => '{{SITENAME}} جوباسىندا جاڭا بەت باستاۋ رۇقساتىڭىز جوق.',
'permissionserrors'         => 'رۇقساتتار قاتەلەرى',
'permissionserrorstext'     => 'بۇنى ىستەۋگە رۇقساتىڭىز جوق, كەلەسى {{PLURAL:$1|سەبەپ|سەبەپتەر}} بويىنشا:',
'recreate-deleted-warn'     => "'''قۇلاقتاندىرۋ: الدىندا جويىلعان بەتتى قايتا باستايىن دەپ تۇرسىز.'''

مىنا بەت وڭدەۋىن جالعاستىرۋ ٴۇشىن جاراستىعىن تەكسەرىپ شىعۋىڭىز ٴجون.
قولايلى بولۋى ٴۇشىن بۇل بەتتىڭ جويۋ جۋرنالى كەلتىرىلگەن:",

# "Undo" feature
'undo-success' => 'بۇل تۇزەتۋ جوققا شىعارىلۋى مۇمكىن. تالابىڭىزدى ٴبىلىپ تۇرىپ الدىن الا تومەندەگى سالىستىرۋدى تەكسەرىپ شىعىڭىز دا, تۇزەتۋدىڭ جوققا شىعارۋىن ٴبىتىرۋ ٴۇشىن تومەندەگى وزگەرىستەردى ساقتاڭىز.',
'undo-failure' => 'بۇل تۇزەتۋ جوققا شىعارىلمايدى, سەبەبى ارادا قاقتىعىس جاسايتىن تۇزەتۋلەر بار.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User_talk:$2|تالقىلاۋى]]) ىستەگەن ٴنومىر $1 نۇسقاسىن جوققا شىعاردى',

# Account creation failure
'cantcreateaccounttitle' => 'تىركەلگى جاراتىلمادى',
'cantcreateaccount-text' => "بۇل IP جايدان (<b>$1</b>) تىركەلۋىن [[User:$3|$3]] بۇعاتتاعان.

$3 كەلتىرىلگەن سەبەبى: ''$2''",

# History pages
'viewpagelogs'        => 'وسى بەتكە قاتىستى جۋرنالداردى قاراۋ',
'nohistory'           => 'وسى بەتتىنىڭ نۇسقالار تارىيحى جوق.',
'revnotfound'         => 'نۇسقا تابىلمادى',
'revnotfoundtext'     => 'وسى سۇرانىسقان بەتتىڭ ەسكى نۇسقاسى تابىلعان جوق. وسى بەتتى اشۋعا پايدالانعان URL جايىن قايتا تەكسەرىپ شىعىڭىز.',
'currentrev'          => 'اعىمدىق نۇسقاسى',
'revisionasof'        => '$1 كەزىندەگى نۇسقاسى',
'revision-info'       => '$1 كەزىندەگى $2 جاساعان نۇسقاسى',
'previousrevision'    => '← ەسكىلەۋ نۇسقاسى',
'nextrevision'        => 'جاڭالاۋ نۇسقاسى →',
'currentrevisionlink' => 'اعىمدىق نۇسقاسى',
'cur'                 => 'اعىم.',
'next'                => 'كەل.',
'last'                => 'سوڭ.',
'page_first'          => 'العاشقىسىنا',
'page_last'           => 'سوڭعىسىنا',
'histlegend'          => 'ايىرماسىن بولەكتەۋ: سالىستىرامىن دەگەن نۇسقالاردى ايىرىپ-قوسقىشپەن بەلگىلەپ جانە دە <Enter> پەرنەسىن باسىڭىز, نەمەسە استىنداعى باتىرمانى نۇقىڭىز.<br />
شارتتى بەلگىلەر: (اعىم.) = اعىمدىق نۇسقامەن ايىرماسى,
(سوڭ.) = الدىڭعى نۇسقامەن ايىرماسى, ش = شاعىن تۇزەتۋ',
'deletedrev'          => '[جويىلعان]',
'histfirst'           => 'ەڭ العاشقىسىنا',
'histlast'            => 'ەڭ سوڭعىسىنا',
'historysize'         => '({{PLURAL:$1|1|$1}} بايت)',
'historyempty'        => '(بوس)',

# Revision feed
'history-feed-title'          => 'نۇسقا تارىيحى',
'history-feed-description'    => 'مىنا ۋىيكىيدەگى بۇل بەتتىڭ نۇسقا تارىيحى',
'history-feed-item-nocomment' => '$2 كەزىندەگى $1 دەگەن', # user at time
'history-feed-empty'          => 'سۇراتىلعان بەت جوق بولدى.
ول مىنا ۋىيكىيدەن جويىلعان, نەمەسە اتاۋى اۋىستىرىلعان.
وسىعان قاتىستى جاڭا بەتتەردى [[{{ns:special}}:Search|بۇل ۋىيكىيدەن ىزدەۋدى]] بايقاپ كورىڭىز.',

# Revision deletion
'rev-deleted-comment'         => '(ماندەمە الاستالدى)',
'rev-deleted-user'            => '(قاتىسۋشى اتى الاستالدى)',
'rev-deleted-event'           => '(جازبا الاستالدى)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
وسى بەتتىڭ نۇسقاسى جارىيا مۇراعاتتارىنان الاستالعان.
بۇل جايتقا [{{fullurl:{{ns:special}}:Log/delete|page={{FULLPAGENAMEE}}}} جويۋ جۋرنالىندا] ەگجەي-تەگجەي مالىمەتتەرى بولۋى مۇمكىن.
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
وسى بەتتىڭ نۇسقاسى جارىيا مۇراعاتتارىنان الاستالعان.
{{SITENAME}} اكىمشىسى بوپ سونى كورە الاسىز;
[{{fullurl:{{ns:special}}:Log/delete|page={{FULLPAGENAMEE}}}} جويۋ جۋرنالىندا] ەگجەي-تەگجەي مالمەتتەرى بولۋى مۇمكىن.
</div>',
'rev-delundel'                => 'كورسەت/جاسىر',
'revisiondelete'              => 'نۇسقالاردى جويۋ/جويۋدى بولدىرماۋ',
'revdelete-nooldid-title'     => 'نىسانا نۇسقاسى جوق',
'revdelete-nooldid-text'      => 'وسى ارەكەتتى ورىنداۋ ٴۇشىن اقىرعى نۇسقاسىننە نۇسقالارىن ەنگىزبەپسىز.',
'revdelete-selected'          => "'''$1:''' دەگەننىڭ بولەكتەنگەن {{PLURAL:$2|نۇسقاسى|نۇسقالارى}}:",
'logdelete-selected'          => "'''$1:''' دەگەننىڭ بولەكتەنگەن جۋرنال {{PLURAL:$2|وقىيعاسى|وقىيعالارى}}:",
'revdelete-text'              => 'جويىلعان نۇسقالار مەن جازبالاردى ٴالى دە بەت تارىيحىندا جانە جۋرنالداردا تابۋعا بولادى,
بىراق ولاردىڭ ماعلۇمات بولشەكتەرى بارشاعا قاتىنالمايدى.

{{SITENAME}} جوباسىنىڭ باسقا اكىمشىلەرى جاسىرىن ماعلۇماتقا قاتىناي الادى, جانە قوسىمشا تىيىمدار
قويىلعانشا دەيىن, وسى تىلدەسۋ ارقىلى جويۋدى بولدىرماۋى مۇمكىن.',
'revdelete-legend'            => 'تىيىمداردى قويۋ:',
'revdelete-hide-text'         => 'نۇسقا ٴماتىنىن جاسىر',
'revdelete-hide-name'         => 'ارەكەت پەن ماقساتىن جاسىر',
'revdelete-hide-comment'      => 'تۇزەتۋ ماندەمەسىن جاسىر',
'revdelete-hide-user'         => 'وڭدەۋشى اتىن (IP جايىن) جاسىر',
'revdelete-hide-restricted'   => 'وسى تىيىمداردى بارشاعا سىياقتى اكىمشىلەرگە دە قولدانۋ',
'revdelete-suppress'          => 'اكىمشىلەر جاساعان ماعلۇماتتى باسقالارشا پەردەلەۋ',
'revdelete-hide-image'        => 'فايل ماعلۇماتىن جاسىر',
'revdelete-unsuppress'        => 'قالپىنا كەلتىرىلگەن نۇسقالاردان تىيىمداردى الاستاۋ',
'revdelete-log'               => 'جۋرنال ماندەمەسى:',
'revdelete-submit'            => 'بولەكتەنگەن نۇسقاعا قولدانۋ',
'revdelete-logentry'          => '[[$1]] دەگەننىڭ نۇسقا كورىنىسىن وزگەرتتى',
'logdelete-logentry'          => '[[$1]] دەگەننىڭ جازبا كورىنىسىن وزگەرتتى',
'revdelete-logaction'         => '{{PLURAL:$1|1|$1}} نۇسقانى $2 كۇيىنە قويدى',
'logdelete-logaction'         => '[[$3]] دەگەنگە {{PLURAL:$1|1|$1}} وقىيعانى $2 كۇيىنە قويدى',
'revdelete-success'           => 'نۇسقا كورىنىسى ٴساتتى قويىلدى.',
'logdelete-success'           => 'جازبا كورىنىسى ٴساتتى قويىلدى.',

# History merging
'mergehistory'                     => 'بەتتەر تارىيحىن بىرىكتىرۋ',
'mergehistory-header'              => "بۇل بەت ارقىلى ٴبىر قاينار بەتتىڭ نۇسقالار تارىيحىن جاڭا بەتكە بىرىكتىرۋگە مۇمكىندىك بەرەدى.
وسى وزگەرىس بەتتىڭ تارىيحىي جالعاستىرۋشىلىعىن قوشتايتىنىنا كوزىڭىز جەتسىن.

'''ەڭ كەمىندە قاينار بەتىنىڭ اعىمدىق نۇسقاسى قالۋ كەرەك.'''",
'mergehistory-box'                 => 'ەكى بەتتىڭ نۇسقالارىن بىرىكتىرۋ:',
'mergehistory-from'                => 'قاينار بەتى:',
'mergehistory-into'                => 'نىسانا بەتى:',
'mergehistory-list'                => 'بىرىكتىرلەتىن تۇزەتۋ تارىيحى',
'mergehistory-merge'               => '[[:$1]] دەگەننىڭ كەلەسى نۇسقالارى [[:$2]] دەگەنگە بىرىكتىرىلۋىنە مۇمكىن. بىرىكتىرۋگە تەك ەنگىزىلگەن ۋاقىتقا دەيىن جاسالعان نۇسقالاردى ايىرىپ-قوسقىش باعاندى قولدانىڭىز. اڭعارتپا: باعىتتاۋ سىلتەمەلەرىن قولدانعاندا بۇل باعان قايتا قويىلادى.',
'mergehistory-go'                  => 'بىرىكتىرلەتىن تۇزەتۋلەردى كورسەت',
'mergehistory-submit'              => 'نۇسقالاردى بىرىكتىرۋ',
'mergehistory-empty'               => 'ەش نۇسقالار بىرىكتىرىلمەيدى',
'mergehistory-success'             => '[[:$1]] دەگەننىڭ $3 نۇسقاسى [[:$2]] دەگەنگە ٴساتتى بىرىكتىرىلدى.',
'mergehistory-fail'                => 'تارىيح بىرىكتىرۋى ورىندالمادى, بەت پەن ۋاقىت باپتالىمدارىن قايتا تەكسەرىپ شىعىڭىز.',
'mergehistory-no-source'           => '$1 دەگەن قاينار بەتى جوق.',
'mergehistory-no-destination'      => '$1 دەگەن نىسانا بەتى جوق.',
'mergehistory-invalid-source'      => 'قاينار بەتىندە جارامدى اتاۋ بولۋى كەرەك.',
'mergehistory-invalid-destination' => 'نىسانا بەتىندە جارامدى اتاۋ بولۋى كەرەك.',

# Merge log
'mergelog'           => 'بىرىكتىرۋ جۋرنالى',
'pagemerge-logentry' => '[[$1]] دەگەن [[$2]] دەگەنگە بىرىكتىرىلدى ($3 دەيىنگى نۇسقالارى)',
'revertmerge'        => 'بىرىكتىرۋدى بولدىرماۋ',
'mergelogpagetext'   => 'تومەندە ٴبىر بەتتىڭ تارىيحى باسقا بەتكە ەڭ سوڭعى بىرىكتىرۋ ٴتىزىمى كەلتىرىلەدى.',

# Diffs
'history-title'           => '«$1» نۇسقا تارىيحى',
'difference'              => '(نۇسقالار اراسىنداعى ايىرماشىلىق)',
'lineno'                  => 'جول ٴنومىرى $1:',
'compareselectedversions' => 'بولەكتەنگەن نۇسقالاردى سالىستىرۋ',
'editundo'                => 'جوققا شىعارۋ',
'diff-multi'              => '(اراداعى {{PLURAL:$1|ٴبىر|$1}} نۇسقا كورسەتىلمەگەن.)',

# Search results
'searchresults'         => 'ىزدەستىرۋ ناتىيجەلەرى',
'searchresulttext'      => '{{SITENAME}} جوباسىندا ىزدەستىرۋ تۋرالى كوبىرەك اقپارات ٴۇشىن, [[{{{{ns:mediawiki}}:helppage}}|{{int:help}}]] قاراڭىز.',
'searchsubtitle'        => "ىزدەستىرۋ سۇرانىسىڭىز: '''[[:$1]]'''",
'searchsubtitleinvalid' => "ىزدەستىرۋ سۇرانىسىڭىز: '''$1'''",
'noexactmatch'          => "'''وسىندا «$1» اتاۋلى بەت جوق.''' بۇل بەتتى ٴوزىڭىز [[:$1|باستاي الاسىز]].",
'noexactmatch-nocreate' => "'''وسىندا «$1» اتاۋلى بەت جوق.'''",
'titlematches'          => 'بەت اتاۋى سايكەسى',
'notitlematches'        => 'ەش بەت اتاۋى سايكەس ەمەس',
'textmatches'           => 'بەت ٴماتىنىڭ سايكەسى',
'notextmatches'         => 'ەش بەت ٴماتىنى سايكەس ەمەس',
'prevn'                 => 'الدىڭعى $1',
'nextn'                 => 'كەلەسى $1',
'viewprevnext'          => 'كورسەتىلۋى: ($1) ($2) ($3) جازبا',
'showingresults'        => "تومەندە ٴنومىر '''$2''' ورنىنان باستاپ, جەتكەنشە {{PLURAL:$1|'''1'''|'''$1'''}} ناتىيجە كورسەتىلگەن.",
'showingresultsnum'     => "تومەندە ٴنومىر '''$2''' ورنىنان باستاپ {{PLURAL:$3|'''1'''|'''$3'''}} ناتىيجە كورسەتىلگەن.",
'nonefound'             => "'''اڭعارتپا''': تابۋ ٴساتسىز ٴبىتۋى ٴجىيى «بولعان» جانە «دەگەن» سىياقتى
تىزىمدەلمەيتىن جالپى سوزدەرمەن ىزدەستىرۋدەن بولۋى مۇمكىن,
نەمەسە بىردەن ارتىق ىزدەستىرۋ شارت سوزدەرىن ەگىزگەننەن (ناتىيجەلەردە تەك
بارلىق شارت سوزدەر كەدەسسە كورسەتىلەدى) بولۋى مۇمكىن.",
'powersearch'           => 'ىزدەۋ',
'powersearchtext'       => 'مىنا ەسىم ايالاردا ىزدەۋ:<br />$1<br />$2 ايداتۋلاردى تىزىمدەۋ<br />ىزدەستىرۋ سۇرانىمى: $3 $9',
'searchdisabled'        => '{{SITENAME}} ىزدەۋ قىزمەتى وشىرىلگەن. ازىرشە Google ارقىلى ىزدەۋگە بولادى. اڭعارتپا: {{SITENAME}} ماعلۇماتىن تىزىمىدەۋلەرى ەسكىرگەن بولۋعا مۇمكىن.',

# Preferences page
'preferences'              => 'باپتاۋ',
'mypreferences'            => 'باپتاۋىم',
'prefs-edits'              => 'تۇزەتۋ سانى:',
'prefsnologin'             => 'كىرمەگەنسىز',
'prefsnologintext'         => 'باپتاۋىڭىزدى قويۋ ٴۇشىن [[Special:Userlogin|كىرۋىڭىز]] ٴتىيىستى.',
'prefsreset'               => 'باپتاۋ ارقاۋدان قايتا قويىلدى.',
'qbsettings'               => 'ٴمازىر',
'qbsettings-none'          => 'ەشقانداي',
'qbsettings-fixedleft'     => 'سولعا بەكىتىلگەن',
'qbsettings-fixedright'    => 'وڭعا بەكىتىلگەن',
'qbsettings-floatingleft'  => 'سولعا قالقىعان',
'qbsettings-floatingright' => 'وڭعا قالقىعان',
'changepassword'           => 'قۇپىيا ٴسوزدى وزگەرتۋ',
'skin'                     => 'بەزەندىرۋ',
'math'                     => 'ماتەماتىيكا',
'dateformat'               => 'كۇن-اي ٴپىشىمى',
'datedefault'              => 'ەش قالاۋسىز',
'datetime'                 => 'ۋاقىت',
'math_failure'             => 'وڭدەتۋ ٴساتسىز ٴبىتتى',
'math_unknown_error'       => 'بەلگىسىز قاتە',
'math_unknown_function'    => 'بەلگىسىز فۋنكتسىيا',
'math_lexing_error'        => 'لەكسىيكا قاتەسى',
'math_syntax_error'        => 'سىينتاكسىيس قاتەسى',
'math_image_error'         => 'PNG اۋدارىسى ٴساتسىز ٴبىتتى; latex, dvips, gs جانە convert باعدارلامالارىنىڭ دۇرىس ورناتۋىن تەكسەرىپ شىعىڭىز',
'math_bad_tmpdir'          => 'ماتەماتىيكانىڭ ۋاقىتشا قالتاسىنا جازىلمادى, نە قالتا جاراتىلمادى',
'math_bad_output'          => 'ماتەماتىيكانىڭ بەرىس قالتاسىنا جازىلمادى, نە قالتا جاراتىلمادى',
'math_notexvc'             => 'texvc باعدارلاماسى جوعالتىلعان; باپتاۋ ٴۇشىن math/README قۇجاتىن قاراڭىز.',
'prefs-personal'           => 'جەكە دەرەكتەرى',
'prefs-rc'                 => 'جۋىقتاعى وزگەرىستەر',
'prefs-watchlist'          => 'باقىلاۋ',
'prefs-watchlist-days'     => 'باقىلاۋ تىزىمىندە كورسەتەرىن ەڭ كوپ كۇندەرى:',
'prefs-watchlist-edits'    => 'كەڭەيتىلگەن باقىلاۋ تىزىمىندە كورسەتەرىن ەڭ كوپ تۇزەتۋلەرى:',
'prefs-misc'               => 'قوسىمشا',
'saveprefs'                => 'ساقتا',
'resetprefs'               => 'قايتا قوي',
'oldpassword'              => 'اعىمدىق قۇپىيا ٴسوز:',
'newpassword'              => 'جاڭا قۇپىيا ٴسوز:',
'retypenew'                => 'جاڭا قۇپىيا ٴسوزدى قايتالاڭىز:',
'textboxsize'              => 'وڭدەۋ',
'rows'                     => 'جولدار:',
'columns'                  => 'باعاندار:',
'searchresultshead'        => 'ىزدەۋ',
'resultsperpage'           => 'بەت سايىن ناتىيجە سانى:',
'contextlines'             => 'ناتىيجە سايىن جول سانى:',
'contextchars'             => 'جول سايىن ٴارىپ سانى:',
'stub-threshold'           => '<a href="#" class="stub">بىتەمە سىلتەمەسىن</a> پىشىمدەۋ تابالدىرىعى (بايت):',
'recentchangesdays'        => 'جۇىقتاعى وزگەرىستەردەگى كورسەتىلەتىن كۇندەر:',
'recentchangescount'       => 'جۋىقتاعى وزگەرىستەردەگى كورسەتىلەتىن تۇزەتۋلەر:',
'savedprefs'               => 'باپتاۋىڭىز ساقتالدى.',
'timezonelegend'           => 'ۋاقىت بەلدەۋى',
'timezonetext'             => 'جەرگىلىكتى ۋاقىتىڭىز بەن سەرۆەر ۋاقىتىنىڭ (UTC) اراسىنداعى ساعات سانى.',
'localtime'                => 'جەرگىلىكتى ۋاقىت',
'timezoneoffset'           => 'ىعىستىرۋ¹',
'servertime'               => 'سەرۆەر ۋاقىتى',
'guesstimezone'            => 'شولعىشتان الىپ تولتىرۋ',
'allowemail'               => 'باسقادان حات قابىلداۋىن قوس',
'defaultns'                => 'مىنا ەسىم ايالاردا ادەپكىدەن ىزدەۋ:',
'default'                  => 'ادەپكى',
'files'                    => 'فايلدار',

# User rights
'userrights'                       => 'قاتىسۋشىلار قۇقىقتارىن رەتتەۋ', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'           => 'قاتىسۋشى توپتارىن رەتتەۋ',
'userrights-user-editname'         => 'قاتىسۋشى اتىن ەنگىزىڭىز:',
'editusergroup'                    => 'قاتىسۋشى توپتارىن وڭدەۋ',
'editinguser'                      => 'وڭدەلۋدە: <b>$1</b> دەگەن قاتىسۋشى',
'userrights-editusergroup'         => 'قاتىسۋشى توپتارىن وڭدەۋ',
'saveusergroups'                   => 'قاتىسۋشى توپتارىن ساقتاۋ',
'userrights-groupsmember'          => 'مۇشەلىگى:',
'userrights-groupsremovable'       => 'الاستالاتىن توپتار:',
'userrights-groupsavailable'       => 'جەتىمدى توپتار:',
'userrights-groupshelp'            => 'قاتىسۋشىنى توپقا ۇستەيمىن نە توپتان الاستايمىن دەگەن توپتاردى بولەكتەڭىز. بولەكتەنبەگەن توپتار وزگەرتىلىمەيدى. توپتاردىڭ بولەكتەۋىن CTRL + سول جاق نۇقۋمەن وشىرۋىڭىزگە بولادى.',
'userrights-reason'                => 'وزگەرتۋ سەبەبى:',
'userrights-available-none'        => 'توپ مۇشەلىگىن وزگەرتە المايسىز.',
'userrights-available-add'         => 'قاتىسۋشىلاردى مىنا {{PLURAL:$2|توپقا|توپتارعا}} ۇستەي الاسىز: $1.',
'userrights-available-remove'      => 'قاتىسۋشىلاردى مىنا {{PLURAL:$2|توپتان|توپتاردان}} الاستاي الاسىز: $1.',
'userrights-available-add-self'    => 'ٴوزىڭىزدى مىنا {{PLURAL:$2|توپقا|توپتارعا}} ۇستەي الاسىز: $1.',
'userrights-available-remove-self' => 'ٴوزىڭىزدى مىنا {{PLURAL:$2|توپتان|توپتاردان}} الاستاي الاسىز: $1.',
'userrights-no-interwiki'          => 'باسقا ۋىيكىيلەردەگى پايدالانۋشى قۇقىقتارىن وڭدەۋگە رۇقساتىڭىز جوق.',
'userrights-nodatabase'            => '$1 دەرەكقورى جوق نە جەرگىلىكتى ەمەس.',
'userrights-nologin'               => 'قاتىسۋشى قۇقىقتارىن تاعايىنداۋ ٴۇشىن اكىمشى تىركەلگىسىمەن [[Special:Userlogin|كىرۋىڭىز]] ٴجون.',
'userrights-notallowed'            => 'قاتىسۋشى قۇقىقتارىن تاعايىنداۋ ٴۇشىن تىركەلگىڭىزدە رۇقسات جوق.',

# Groups
'group'               => 'توپ:',
'group-autoconfirmed' => 'ٴوزى قۇپتالعاندار',
'group-bot'           => 'بوتتار',
'group-sysop'         => 'اكىمشىلەر',
'group-bureaucrat'    => 'بىتىكشىلەر',
'group-all'           => '(بارلىعى)',

'group-autoconfirmed-member' => 'ٴوزى قۇپتالعان',
'group-bot-member'           => 'بوت',
'group-sysop-member'         => 'اكىمشى',
'group-bureaucrat-member'    => 'بىتىكشى',

'grouppage-autoconfirmed' => '{{ns:project}}:ٴوزى قۇپتالعاندار',
'grouppage-bot'           => '{{ns:project}}:بوتتار',
'grouppage-sysop'         => '{{ns:project}}:اكىمشىلەر',
'grouppage-bureaucrat'    => '{{ns:project}}:بىتىكشىلەر',

# User rights log
'rightslog'      => 'قاتىسۋشى قۇقىقتارى جۋرنالى',
'rightslogtext'  => 'بۇل قاتىسۋشى قۇقىقتارىن وزگەرتۋ جۋرنالى.',
'rightslogentry' => '$1 توپ مۇشەلگىن $2 دەگەننەن $3 دەگەنگە وزگەرتتى',
'rightsnone'     => '(ەشقانداي)',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|1|$1}} وزگەرىس',
'recentchanges'                     => 'جۋىقتاعى وزگەرىستەر',
'recentchangestext'                 => 'بۇل بەتتە وسى ۋىيكىيدەگى بولعان جۋىقتاعى وزگەرىستەر بايقالادى.',
'recentchanges-feed-description'    => 'بۇل ارنامەنەن ۋىيكىيدەگى ەڭ سوڭعى وزگەرىستەر قاداعالانادى.',
'rcnote'                            => "$3 كەزىنە دەيىن — تومەندە سوڭعى {{PLURAL:$2|كۇندەگى|'''$2''' كۇندەگى}}, سوڭعى {{PLURAL:$1|'''1'''|'''$1'''}} وزگەرىس كورسەتىلگەن.",
'rcnotefrom'                        => '<b>$2</b> كەزىنەن بەرى — تومەندە وزگەرىستەر <b>$1</b> دەيىن كورسەتىلگەن.',
'rclistfrom'                        => '$1 كەزىنەن بەرى — جاڭا وزگەرىستەردى كورسەت.',
'rcshowhideminor'                   => 'شاعىن تۇزەتۋدى $1',
'rcshowhidebots'                    => 'بوتتاردى $1',
'rcshowhideliu'                     => 'تىركەلگەندى $1',
'rcshowhideanons'                   => 'تىركەلگىسىزدى $1',
'rcshowhidepatr'                    => 'كۇزەتتەگى تۇزەتۋلەردى $1',
'rcshowhidemine'                    => 'تۇزەتۋىمدى $1',
'rclinks'                           => 'سوڭعى $2 كۇندە بولعان, سوڭعى $1 وزگەرىستى كورسەت<br />$3',
'diff'                              => 'ايىرم.',
'hist'                              => 'تار.',
'hide'                              => 'جاسىر',
'show'                              => 'كورسەت',
'minoreditletter'                   => 'ش',
'newpageletter'                     => 'ج',
'boteditletter'                     => 'ب',
'number_of_watching_users_pageview' => '[باقىلاعان {{PLURAL:$1|1|$1}} قاتىسۋشى]',
'rc_categories'                     => 'ساناتتارعا شەكتەۋ ("|" بەلگىسىمەن بولىكتەڭىز)',
'rc_categories_any'                 => 'قايسىبىر',
'newsectionsummary'                 => '/* $1 */ جاڭا ٴبولىم',

# Recent changes linked
'recentchangeslinked'          => 'قاتىستى وزگەرىستەر',
'recentchangeslinked-title'    => '$1 دەگەنگە قاتىستى وزگەرىستەر',
'recentchangeslinked-noresult' => 'سىلتەلگەن بەتتەردە كەلتىرىلگەن مەرزىمدە ەشقانداي وزگەرىس بولماعان.',
'recentchangeslinked-summary'  => "بۇل ارنايى بەتتە سىلتەلگەن بەتتەردەگى جۋىقتاعى وزگەرىستەر ٴتىزىمى بەرىلەدى. باقىلاۋ تىزىمىڭىزدەگى بەتتەر '''جۋان''' اربىمەن بەلگىلەنەدى.",

# Upload
'upload'                      => 'فايل قوتارۋ',
'uploadbtn'                   => 'قوتار!',
'reupload'                    => 'قايتالاپ قوتارۋ',
'reuploaddesc'                => 'قوتارۋ پىشىنىنە ورالۋ.',
'uploadnologin'               => 'كىرمەگەنسىز',
'uploadnologintext'           => 'فايل قوتارۋ ٴۇشىن [[Special:Userlogin|كىرۋىڭىز]] كەرەك.',
'upload_directory_read_only'  => 'قوتارۋ قالتاسىنا ($1) جازۋعا ۆەب-سەرۆەرگە رۇقسات بەرىلمەگەن.',
'uploaderror'                 => 'قوتارۋ قاتەسى',
'uploadtext'                  => "تومەندەگى ٴپىشىن فايل قوتارۋعا قولدانىلادى, الدىنداعى سۋرەتتەردى قاراۋ ٴۇشىن نە ىزدەۋ ٴۇشىن [[{{ns:special}}:Imagelist|قوتارىلعان فايلدار تىزىمىنە]] بارىڭىز, قوتارۋ مەن جويۋ تاعى دا [[{{ns:special}}:Log/upload|قوتارۋ جۋرنالىنا]] جازىلىپ الىنادى.

سۋرەتتى بەتكە كىرىستىرۋگە, فايلعا تۋرا سىلتەۋ ٴۇشىن مىنا پىشىندەگى سىلتەمەنى قولدانىڭىز:
'''<nowiki>[[{{ns:image}}:File.jpg]]''',
'''[[{{ns:image}}:File.png|بالاما ٴماتىن]]''' نە
'''[[{{ns:media}}:File.ogg]]'''.",
'upload-permitted'            => 'رۇقسات بەرىگەن فايل تۇرلەرى: $1.',
'upload-preferred'            => 'ۇنامدى فايل تۇرلەرى $1.',
'upload-prohibited'           => 'ۇقسات بەرىلمەگەن فايل تۇرلەرى: $1.',
'uploadlog'                   => 'قوتارۋ جۋرنالى',
'uploadlogpage'               => 'قوتارۋ جۋرنالى',
'uploadlogpagetext'           => 'تومەندە جۋىقتاعى قوتارىلعان فايل ٴتىزىمى.',
'filename'                    => 'فايل اتى',
'filedesc'                    => 'سىيپاتتاماسى',
'fileuploadsummary'           => 'سىيپاتتاماسى:',
'filestatus'                  => 'اۋتورلىق قۇقىقتارى كۇيى:',
'filesource'                  => 'فايل قاينارى:',
'uploadedfiles'               => 'قوتارىلعان فايلدار',
'ignorewarning'               => 'قۇلاقتاندىرۋعا ەلەمە دە فايلدى ارقايسى جولىمەن ساقتا.',
'ignorewarnings'              => 'ارقايسى قۇلاقتاندىرۋلارعا ەلەمە',
'minlength1'                  => 'فايل اتاۋىندا ەڭ كەمىندە ٴبىر ٴارىپ بولۋى كەرەك.',
'illegalfilename'             => '«$1» فايل اتاۋىندا بەت اتاۋلارىندا رۇقسات ەتىلمەگەن نىشاندار بار. فايلدى قايتا اتاڭىز دا بۇنى جۋكتەدى قايتا بايقاپ كورىڭىز.',
'badfilename'                 => 'فايلدىڭ اتى «$1» بوپ وزگەرتىلدى.',
'filetype-badmime'            => '«$1» دەگەن MIME ٴتۇرى بار فايلداردى قوتارۋعا رۇقسات ەتىلمەيدى.',
'filetype-unwanted-type'      => "'''«.$1»''' — كۇتىلمەگەن فايل ٴتۇرى. ۇنامدى فايل تۇرلەرى: $2.",
'filetype-banned-type'        => "'''«.$1»''' — رۇقساتتالماعان فايل ٴتۇرى. رۇقساتتالعان فايل تۇرلەرى: $2.",
'filetype-missing'            => 'بۇل فايلدىڭ («.jpg» سىياقتى) كەڭەيتىمى جوق.',
'large-file'                  => 'فايلدى $1 مولشەردەن اسپاۋىنا تىرىسىڭىز; بۇل فايل مولشەرى — $2.',
'largefileserver'             => 'وسى فايلدىڭ مولشەرى سەرۆەردىڭ قالاۋىنان اسىپ كەتكەن.',
'emptyfile'                   => 'قوتارىلعان فايلىڭىز بوس سىياقتى. بۇل فايل اتاۋىندا قاتە بولۋى مۇمكىن. وسى فايلدى شىنايى قوتارعىڭىز كەلەتىن تەكسەرىپ شىعىڭىز.',
'fileexists'                  => 'وسىلاي اتالعان فايل الداقاشان بار, ەگەر بۇنى وزگەرتۋگە سەنىمىڭىز جوق بولسا <strong><tt>$1</tt></strong> دەگەندى تەكسەرىپ شىعىڭىز.',
'filepageexists'              => 'بىلاي اتالعان بەت (سۋرەت ەمەس) الداقاشان بار, بۇنى وزگەرتۋگە سەنىمىڭىز بولماسا <strong><tt>$1</tt></strong> دەگەندى تەكسەرىپ شىعىڭىز.',
'fileexists-extension'        => 'ۇقساستى فايل اتاۋى بار بولدى:<br />
قوتارىلاتىن فايل اتاۋى: <strong><tt>$1</tt></strong><br />
بار بولعان فايل اتاۋى: <strong><tt>$2</tt></strong><br />
باسقا اتاۋ تاڭداڭىز.',
'fileexists-thumb'            => "<center>'''بار بولعان سۋرەت'''</center>",
'fileexists-thumbnail-yes'    => 'وسى فايل — مولشەرى كىشىرىتىلگەن سۋرەت <i>(نوباي)</i> سىياقتى. بۇل <strong><tt>$1</tt></strong> دەگەن فايلدى سىناپ شىعىڭىز.<br />
ەگەر سىنالعان فايل تۇپنۇسقالى مولشەرى بار دالمە-ٴدال سۋرەت بولسا, قوسىسمشا نوبايدى قوتارۋ كەرەكى جوق.',
'file-thumbnail-no'           => 'فايل اتاۋى <strong><tt>$1</tt></strong> دەگەنمەن باستالادى. بۇل — مولشەرى كىشىرىتىلگەن سۋرەت <i>(نوباي)</i> سىياقتى.
ەگەر تولىق اجىراتىلىمدىعى بار سۋرەتىڭىز بولسا, سونى قوتارىڭىز, ايتپەسە فايل اتاۋىن وزگەرتىڭىز.',
'fileexists-forbidden'        => 'وسىلاي اتالعان فايل الداقاشان بار. كەرى قايتىڭىز دا, جانە وسى فايلدى باسقا اتىمەن قوتارىڭىز. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'وسىلاي اتالعان فايل ورتاق فايل ارقاۋىندا الداقاشان بار. كەرى قايتىڭىز دا, وسى فايلدى جاڭا اتىمەن قوتارىڭىز. [[{{ns:image}}:$1|thumb|center|$1]]',
'successfulupload'            => 'قوتارۋ ٴساتتى ٴوتتى',
'uploadwarning'               => 'قوتارۋ تۋرالى قۇلاقتاندىرۋى',
'savefile'                    => 'فايلدى ساقتاۋ',
'uploadedimage'               => '«[[$1]]» فايلىن قوتاردى',
'overwroteimage'              => '«[[$1]]» فايلىن جاڭا نۇسقاسىن قوتاردى',
'uploaddisabled'              => 'فايل قوتارۋى وشىرىلگەن',
'uploaddisabledtext'          => '{{SITENAME}} جوباسىندا فايل قوتارۋى وشىرىلگەن.',
'uploadscripted'              => 'وسى فايلدا, ۆەب شولعىشتى اعات تۇسىندىككە كەلتىرەتىڭ HTML بەلگىلەۋ, نە سكرىيپت كودى بار.',
'uploadcorrupt'               => 'وسى فايل بۇلدىرىلگەن, نە بۇرىس كەڭەيتىمى بار. فايلدى تەكسەرىپ, قوتارۋىن قايتالاڭىز.',
'uploadvirus'                 => 'وسى فايلدا ۆىيرۋس بولۋى مۇمكىن! ەگجەي-تەگجەي اقپاراتى: $1',
'sourcefilename'              => 'قاينارداعى فايل اتى:',
'destfilename'                => 'نىسانا فايل اتى:',
'watchthisupload'             => 'وسى بەتتى باقىلاۋ',
'filewasdeleted'              => 'وسى اتاۋى بار فايل بۇرىن قوتارىلعان, سوسىن جويىلدىرىلعان. قايتا قوتارۋ الدىنان $1 دەگەندى تەكسەرىڭىز.',
'upload-wasdeleted'           => "'''قۇلاقتاندىرۋ: الدىندا جويىلعان فايلدى قوتارايىن دەپ تۇرسىز.'''

مىنا فايل قوتارۋىن جالعاستىرۋ ٴۇشىن جاراستىعىن تەكسەرىپ شىعۋىڭىز ٴجون.
قولايلى بولۋى ٴۇشىن بۇل فايلدىڭ جويۋ جۋرنالى كەلتىرىلگەن:",
'filename-bad-prefix'         => 'قوتارايىن دەگەن فايلىڭىزدىڭ اتاۋى <strong>«$1» </strong> دەپ باستالادى, مىناداي سىيپاتتاۋسىز اتاۋدى ادەتتە ساندىق كامەرالار وزدىكتىك بەرەدى. فايلىڭىزعا سىيپاتتىلاۋ اتاۋ تانداپ بەرىڭىز.',

'upload-proto-error'      => 'بۇرىس حاتتامالىق',
'upload-proto-error-text' => 'سىرتتان قوتارۋ ٴۇشىن URL جايلارى <code>http://</code> نەمەسە <code>ftp://</code> دەگەندەردەن باستالۋ كەرەك.',
'upload-file-error'       => 'ىشكى قاتە',
'upload-file-error-text'  => 'سەرۆەردە ۋاقىتشا فايل جاراتىلۋى ىشكى قاتەسىنە ۇشىراستى. بۇل جۇيەنىڭ اكىمشىمەن قاتىناسىڭىز.',
'upload-misc-error'       => 'بەلگىسىز قوتارۋ قاتەسى',
'upload-misc-error-text'  => 'قوتارۋ كەزىندە بەلگىسىز قاتەگە ۇشىراستى. URL جايى جارامدى جانە قاتىناۋلى ەكەنىن تەكسەرىپ شىعىڭىز دا قايتا بايقاپ كورىڭىز. ەگەر بۇل ماسەلە الدە دە قالسا, جۇيە اكىمشىمەن قاتىناسىڭىز.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL جايى جەتىلمەدى',
'upload-curl-error6-text'  => 'بەرىلگەن URL جايى جەتىلمەدى. قايسى URL جايى دۇرىس ەكەنىن جانە توراپ ىستە ەكەنىن قايتالاپ قاتاڭ تەكسەرىڭىز.',
'upload-curl-error28'      => 'قوتارۋعا بەرىلگەن ۋاقىت ٴبىتتى',
'upload-curl-error28-text' => 'توراپتىڭ جاۋاپ بەرۋى تىم ۇزاق ۋاقىتقا سوزىلدى. بۇل توراپ ىستە ەكەنىن تەكسەرىپ شىعىڭىز, ازعانا كىدىرە تۇرىڭىز دا قايتا بايقاپ كورىڭىز. تالابىڭىزدى قول تىيگەن كەزىندە قايتا بايقاپ كورۋىڭىز مۇمكىن.',

'license'            => 'لىيتسەنزىياسى:',
'nolicense'          => 'ەشتەڭە بولەكتەنبەگەن',
'license-nopreview'  => '(قاراپ شىعۋ قاتىنالمايدى)',
'upload_source_url'  => ' (جارامدى, بارشاعا قاتىناۋلى URL جاي)',
'upload_source_file' => ' (كومپيۋتەرىڭىزدەگى فايل)',

# Special:Imagelist
'imagelist_search_for'  => 'سۋرەتتى اتاۋىمەن ىزدەۋ:',
'imgdesc'               => 'سىيپپ.',
'imgfile'               => 'فايل',
'imagelist'             => 'فايل ٴتىزىمى',
'imagelist_date'        => 'كۇن-ايى',
'imagelist_name'        => 'اتاۋى',
'imagelist_user'        => 'قاتىسۋشى',
'imagelist_size'        => 'مولشەرى',
'imagelist_description' => 'سىيپاتتاماسى',

# Image description page
'filehist'                  => 'فايل تارىيحى',
'filehist-help'             => 'فايلدىڭ قاي ۋاقىتتا قالاي كورىنەتىن ٴۇشىن كۇن-اي/ۋاقىت دەگەندى نۇقىڭىز.',
'filehist-deleteall'        => 'بارلىعىن جوي',
'filehist-deleteone'        => 'بۇنى جوي',
'filehist-revert'           => 'قايتار',
'filehist-current'          => 'اعىمداعى',
'filehist-datetime'         => 'كۇن-اي/ۋاقىت',
'filehist-user'             => 'قاتىسۋشى',
'filehist-dimensions'       => 'ولشەمدەرى',
'filehist-filesize'         => 'فايل مولشەرى',
'filehist-comment'          => 'ماندەمەسى',
'imagelinks'                => 'سىلتەمەلەر',
'linkstoimage'              => 'بۇل فايلعا كەلەسى بەتتەر سىلتەيدى:',
'nolinkstoimage'            => 'بۇل فايلعا ەش بەت سىلتەمەيدى.',
'sharedupload'              => 'بۇل فايل ورتاق ارقاۋىنا قوتارىلعان سوندىقتان باسقا جوبالاردا قولدانۋى مۇمكىن.',
'shareduploadwiki'          => 'بىلايعى اقپارات ٴۇشىن $1 دەگەندى قاراڭىز.',
'shareduploadwiki-linktext' => 'فايل سىيپاتتاماسى بەتى',
'noimage'                   => 'مىناداي اتاۋلى فايل جوق, $1 مۇمكىندىگىڭىز بار.',
'noimage-linktext'          => 'بۇنى قوتارۋ',
'uploadnewversion-linktext' => 'بۇل فايلدىڭ جاڭا نۇسقاسىن قوتارۋ',

# File reversion
'filerevert'                => '$1 دەگەندى قايتارۋ',
'filerevert-legend'         => 'فايلدى قايتارۋ',
'filerevert-intro'          => '<span class="plainlinks">\'\'\'[[{{ns:media}}:$1|$1]]\'\'\' دەگەندى [$4 $3, $2 كەزىندەگى نۇسقاسىنا] قايتارۋداسىز.</span>',
'filerevert-comment'        => 'ماندەمەسى:',
'filerevert-defaultcomment' => '$2, $1 كەزىندەگى نۇسقاسىنا قايتارىلدى',
'filerevert-submit'         => 'قايتار',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[{{ns:media}}:$1|$1]]\'\'\' دەگەن [$4 $3, $2 كەزىندەگى نۇسقاسىنا] قايتارىلدى.</span>',
'filerevert-badversion'     => 'كەلتىرىلگەن ۋاقىت بەلگىسىمەن بۇل فايلدىڭ الدىڭعى جەرگىلىكتى نۇسقاسى جوق.',

# File deletion
'filedelete'                  => '$1 دەگەندى جويۋ',
'filedelete-legend'           => 'فايلدى جويۋ',
'filedelete-intro'            => "'''[[{{ns:media}}:$1|$1]]''' دەگەندى جويۋداسىز.",
'filedelete-intro-old'        => '<span class="plainlinks">\'\'\'[[{{ns:media}}:$1|$1]]\'\'\' دەگەننىڭ [$4 $3, $2 كەزىندەگى نۇسقاسىن] جويۋداسىز.</span>',
'filedelete-comment'          => 'جويۋ سەبەبى:',
'filedelete-submit'           => 'جوي',
'filedelete-success'          => "'''$1''' دەگەن جويىلدى.",
'filedelete-success-old'      => '<span class="plainlinks">\'\'\'[[{{ns:media}}:$1|$1]]\'\'\' دەگەننىڭ $3, $2 كەزىندەگى نۇسقاسى جويىلدى.</span>',
'filedelete-nofile'           => "'''$1''' دەگەن {{SITENAME}} جوباسىندا جوق.",
'filedelete-nofile-old'       => "كەلتىرىلگەن انىقتاۋىشتارىمەن '''$1''' دەگەننىڭ مۇراعاتتالعان نۇسقاسى مىندا جوق.",
'filedelete-iscurrent'        => 'بۇل فايلدىڭ ەڭ سوڭعى نۇسقاسىن جويۋ تالاپ ەتكەنسىز. الدىنان ەسكى نۇسقاسىنا قايتارىڭىز.',
'filedelete-otherreason'      => 'باسقا/قوسىمشا سەبەپ:',
'filedelete-reason-otherlist' => 'باسقا سەبەپ',
'filedelete-reason-dropdown'  => '
* جويۋدىڭ جالپى سەبەپتەرى
** اۋتورلىق قۇقىقتارىن بۇزۋ
** قوسارىلانعان فايل',

# MIME search
'mimesearch'         => 'فايلدى MIME تۇرىمەن ىزدەۋ',
'mimesearch-summary' => 'بۇل بەتتە فايلداردى MIME تۇرىمەن سۇزگىلەۋى قوسىلعان. كىرىسى: «ماعلۇمات ٴتۇرى»/«ٴتۇر تاراۋى», مىسالى <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME ٴتۇرى:',
'download'           => 'جۇكتەۋ',

# Unwatched pages
'unwatchedpages' => 'باقىلانىلماعان بەتتەر',

# List redirects
'listredirects' => 'ايداتۋ بەت ٴتىزىمى',

# Unused templates
'unusedtemplates'     => 'پايدالانىلماعان ۇلگىلەر',
'unusedtemplatestext' => 'بۇل بەت باسقا بەتكە كىرىcتىرىلمەگەن ۇلگى ەسىم اياىسىنداعى بارلىق بەتتەردى تىزىمدەيدى. ۇلگىلەردى جويۋ الدىنان بۇنىڭ باسقا سىلتەمەلەرىن تەكسەرىپ شىعۋىن ۇمىتپاڭىز',
'unusedtemplateswlh'  => 'باسقا سىلتەمەلەر',

# Random page
'randompage'         => 'كەزدەيسوق بەت',
'randompage-nopages' => 'بۇل ەسىم اياسىندا بەتتەر جوق.',

# Random redirect
'randomredirect'         => 'كەزدەيسوق ايداتۋ',
'randomredirect-nopages' => 'بۇل ەسىم اياسىندا ەش ايداتۋ جوق.',

# Statistics
'statistics'             => 'ساناق',
'sitestats'              => '{{SITENAME}} ساناعى',
'userstats'              => 'قاتىسۋشى ساناعى',
'sitestatstext'          => "دەرەكقوردا {{PLURAL:$1|'''1'''|بۇلايشا '''$1'''}} بەت بار.
بۇعان «تالقىلاۋ» بەتتەرى, {{SITENAME}} جوباسى تۋرالى بەتتەر, ەڭ از «بىتەمە»
بەتتەرى, ايداتۋلار, تاعى دا باسقا ماعلۇمات دەپ تانىلمايتىن بەتتەر كىرىستىرلەدى.
سولاردى ەسەپتەن شىعارعاندا, مىندا ماعلۇمات {{PLURAL:$2|بەتى|بەتتەرى}} دەپ سانالاتىن
{{PLURAL:$2|'''1'''|'''$2'''}} بەت بار شىعار.

قوتارىلعان {{PLURAL:$8|'''1'''|'''$8'''}} فايل ساقتالادى.

{{SITENAME}} ورناتىلعاننان بەرى بەتتەر {{PLURAL:$3|'''1'''|بۇلايشا '''$3'''}} رەت قارالعان,
جانە بەتتەر {{PLURAL:$4|'''1'''|'''$4'''}} رەت تۇزەتىلگەن.
بۇنىڭ ناتىيجەسىندە ورتاشا ەسەپپەن ٴاربىر بەتكە '''$5''' رەت تۇزەتۋ كەلەدى, جانە ٴاربىر تۇزەتۋگە '''$6''' رەت قاراۋ كەلەدى.

اعىمدىق [http://meta.wikimedia.org/wiki/Help:Job_queue تاپسىرىم كەزەگى] ۇزىندىلىعى: '''$7'''.",
'userstatstext'          => "مىندا {{PLURAL:$1|'''1'''|'''$1'''}} [[{{ns:special}}:Listusers|تىركەلگەن قاتىسۋشى]] بار, سونىڭ ىشىندە
{{PLURAL:$2|'''1'''|'''$2'''}} (نە '''$4 %''') قاتىسۋشىسىندا $5 قۇقىقتارى بار",
'statistics-mostpopular' => 'ەڭ كوپ قارالعان بەتتەر',

'disambiguations'      => 'ايرىقتى بەتتەر',
'disambiguations-text' => "كەلەسى بەتتەر '''ايرىقتى بەتكە''' سىلتەيدى. بۇنىڭ ورنىنا بەلگىلى تاقىرىپقا سىلتەۋى كەرەك.<br />ەگەر [[{{ns:mediawiki}}:Disambiguationspage]] تىزىمىندەگى ۇلگى قولدانىلسا, بەت ايرىقتى دەپ سانالادى.",

'doubleredirects'     => 'شىنجىرلى ايداتۋلار',
'doubleredirectstext' => 'بۇل بەتتە باسقا ايداتۋ بەتتەرگە سىلتەيتىن بەتتەر ٴتىزىمى بەرىلەدى. ٴاربىر جولاقتا ٴبىرىنشى جانە ەكىنشى ايداتۋعا سىلتەمەلەر بار, سونىمەن بىرگە ەكىنشى ايداتۋ نىساناسى بار, ادەتتە بۇل ٴبىرىنشى ايداتۋ باعىتتايتىن «شىن» نىسانا بەت اتاۋى بولۋى كەرەك.',

'brokenredirects'        => 'ەش بەتكە كەلتىرمەيتىن ايداتۋلار',
'brokenredirectstext'    => 'كەلەسى ايداتۋلار جوق بەتتەرگە سىلتەيدى:',
'brokenredirects-edit'   => '(وڭدەۋ)',
'brokenredirects-delete' => '(جويۋ)',

'withoutinterwiki'        => 'ەش تىلگە سىلتeمەگەن بەتتەر',
'withoutinterwiki-header' => 'كەلەسى بەتتەر باسقا تىلدەرگە سىلتەمەيدى:',
'withoutinterwiki-submit' => 'كورسەت',

'fewestrevisions' => 'ەڭ از تۇزەتىلگەن بەتتەر',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|1|$1}} بايت',
'ncategories'             => '{{PLURAL:$1|1|$1}} سانات',
'nlinks'                  => '{{PLURAL:$1|1|$1}} سىلتەمە',
'nmembers'                => '{{PLURAL:$1|1|$1}} بۋىن',
'nrevisions'              => '{{PLURAL:$1|1|$1}} نۇسقا',
'nviews'                  => '{{PLURAL:$1|1|$1}} رەت قارالعان',
'specialpage-empty'       => 'بۇل باياناتقا ەش ناتىيجە جوق.',
'lonelypages'             => 'ەش بەتتەن سىلتەلمەگەن بەتتەر',
'lonelypagestext'         => 'كەلەسى بەتتەرگە {{SITENAME}} جوباسىنداعى باسقا بەتتەر سىلتەمەيدى.',
'uncategorizedpages'      => 'ساناتسىز بەتتەر',
'uncategorizedcategories' => 'ساناتسىز ساناتتار',
'uncategorizedimages'     => 'ساناتسىز سۋرەتتەر',
'uncategorizedtemplates'  => 'ساناتسىز ۇلگىلەر',
'unusedcategories'        => 'پايدالانىلماعان ساناتتار',
'unusedimages'            => 'پايدالانىلماعان فايلدار',
'popularpages'            => 'ەڭ كوپ كورىلگەن بەتتەر',
'wantedcategories'        => 'باستالماعان ساناتتار',
'wantedpages'             => 'باستالماعان بەتتەر',
'mostlinked'              => 'ەڭ كوپ سىلتەلگەن بەتتەر',
'mostlinkedcategories'    => 'ەڭ كوپ پايدالانىلعان ساناتتار',
'mostlinkedtemplates'     => 'ەڭ كوپ پايدالانىلعان ۇلگىلەر',
'mostcategories'          => 'ەڭ كوپ ساناتى بار بەتتەر',
'mostimages'              => 'ەڭ كوپ پايدالانىلعان سۋرەتتەر',
'mostrevisions'           => 'ەڭ كوپ تۇزەتىلگەن بەتتەر',
'prefixindex'             => 'اتاۋ باستاۋى ٴتىزىمى',
'shortpages'              => 'ەڭ قىسقا بەتتەر',
'longpages'               => 'ەڭ ۇزىن بەتتەر',
'deadendpages'            => 'ەش بەتكە سىلتەمەيتىن بەتتەر',
'deadendpagestext'        => 'كەلەسى بەتتەر {{SITENAME}} جوباسىنداعى باسقا بەتتەرگە سىلتەمەيدى.',
'protectedpages'          => 'قورعالعان بەتتەر',
'protectedpagestext'      => 'كەلەسى بەتتەر وڭدەۋدەن نەمەسە جىلجىتۋدان قورعالعان',
'protectedpagesempty'     => 'اعىمدا مىناداي باپتالىمدارىمەن ەشبىر بەت قورعالماعان',
'protectedtitles'         => 'قورعالعان اتاۋلار',
'protectedtitlestext'     => 'كەلەسى اتاۋلاردىڭ جاراتۋىنا رۇقسات بەرىلمەگەن',
'protectedtitlesempty'    => 'بۇل باپتالىمدارمەن اعىمدا ەش اتاۋلار قورعالماعان.',
'listusers'               => 'قاتىسۋشى ٴتىزىمى',
'specialpages'            => 'ارنايى بەتتەر',
'spheading'               => 'بارشانىڭ ارنايى بەتتەرى',
'restrictedpheading'      => 'تىيىمدى ارنايى بەتتەر',
'newpages'                => 'ەڭ جاڭا بەتتەر',
'newpages-username'       => 'قاتىسۋشى اتى:',
'ancientpages'            => 'ەڭ ەسكى بەتتەر',
'move'                    => 'جىلجىتۋ',
'movethispage'            => 'بەتتى جىلجىتۋ',
'unusedimagestext'        => '<p>اڭعارتپا: عالامتورداعى باسقا توراپتار فايلدىڭ
URL جايىنا تىكەلەي سىلتەۋى مۇمكىن. سوندىقتان, بەلسەندى پايدالانۋىنا اڭعارماي,
وسى تىزىمدە قالۋى مۇمكىن.</p>',
'unusedcategoriestext'    => 'كەلەسى سانات بەتتەرى بار بولىپ تۇر, بىراق وعان ەشقانداي بەت, نە سانات كىرمەيدى.',
'notargettitle'           => 'نىسانا جوق',
'notargettext'            => 'بۇل ارەكەت ورىندالاتىن نىسانا بەتتى, نە قاتىسۋشىنى ەنگىزبەپسىز.',
'pager-newer-n'           => '{{PLURAL:$1|جاڭالاۋ 1|جاڭالاۋ $1}}',
'pager-older-n'           => '{{PLURAL:$1|ەسكىلەۋ 1|ەسكىلەۋ $1}}',

# Book sources
'booksources'               => 'كىتاپ قاينارلارى',
'booksources-search-legend' => 'كىتاپ قاينارلارىن ىزدەۋ',
'booksources-go'            => 'ٴوتۋ',
'booksources-text'          => 'تومەندە جاڭا جانە قولدانعان كىتاپتار ساتاتىنتوراپتارىنىڭ سىلتەمەلەرى تىزىمدەلگەن.
بۇل توراپتاردا ىزدەلگەن كىتاپتار تۋرالى بىلايعى اقپارات بولۋعا مۇمكىن.',

# Special:Log
'specialloguserlabel'  => 'قاتىسۋشى:',
'speciallogtitlelabel' => 'اتاۋ:',
'log'                  => 'جۋرنالدار',
'all-logs-page'        => 'بارلىق جۋرنالدار',
'log-search-legend'    => 'جۋرنالداردان ىزدەۋ',
'log-search-submit'    => 'ٴوت',
'alllogstext'          => '{{SITENAME}} جوباسىنىڭ بارلىق قاتىناۋلى جۋرنالدارىن بىرىكتىرىپ كورسەتۋى.
جۋرنال ٴتۇرىن, قاتىسۋشى اتىن, نە ٴتىيىستى بەتىن بولەكتەپ, تارىلتىپ قاراۋىڭىزعا بولادى.',
'logempty'             => 'جۋرنالدا سايكەس دانالار جوق.',
'log-title-wildcard'   => 'مىناداي ماتىننەڭ باستالىتىن اتاۋلاردان ىزدەۋ',

# Special:Allpages
'allpages'          => 'بارلىق بەتتەر',
'alphaindexline'    => '$1 — $2',
'nextpage'          => 'كەلەسى بەتكە ($1)',
'prevpage'          => 'الدىڭعى بەتكە ($1)',
'allpagesfrom'      => 'مىنا بەتتەن باستاپ كورسەتۋ:',
'allarticles'       => 'بارلىق بەت ٴتىزىمى',
'allinnamespace'    => 'بارلىق بەت ($1 ەسىم اياسى)',
'allnotinnamespace' => 'بارلىق بەت ($1 ەسىم اياسىنان تىس)',
'allpagesprev'      => 'الدىڭعىعا',
'allpagesnext'      => 'كەلەسىگە',
'allpagessubmit'    => 'ٴوتۋ',
'allpagesprefix'    => 'مىنادان باستالعان بەتتەردى كورسەتۋ:',
'allpagesbadtitle'  => 'كەلتىرىلگەن بەت اتاۋى جارامسىز بولعان, نەمەسە ٴتىل-ارالىق نە ۋىيكىي-ارالىق باستاۋى بار بولدى. اتاۋدا قولدانۋعا بولمايتىن نىشاندار بولۋى مۇمكىن.',
'allpages-bad-ns'   => '{{SITENAME}} جوباسىندا «$1» ەسىم اياسى جوق.',

# Special:Listusers
'listusersfrom'      => 'مىنا قاتىسۋشىدان باستاپ كورسەتۋ:',
'listusers-submit'   => 'كورسەت',
'listusers-noresult' => 'قاتىسۋشى تابىلعان جوق.',

# E-mail user
'mailnologin'     => 'ەش جىبەرىلەتىن جاي جوق',
'mailnologintext' => 'باسقا قاتىسۋشىعا حات جىبەرۋ ٴۇشىن
[[{{ns:special}}:Userlogin|كىرۋىڭىز]] كەرەك, جانە [[{{ns:special}}:Preferences|باپتاۋىڭىزدا]]
جارامدى ە-پوشتا جايى بولۋى ٴجون.',
'emailuser'       => 'قاتىسۋشىعا حات جازۋ',
'emailpage'       => 'قاتىسۋشىعا حات جىبەرۋ',
'emailpagetext'   => 'ەگەر بۇل قاتىسۋشى باپتاۋلارىندا جارامدى ە-پوشتا
جايىن ەنگىزسە, تومەندەگى ٴپىشىن ارقىلى بۇعان جالعىز ە-پوشتا حاتىن جىبەرۋگە بولادى.
قاتىسۋشى باپتاۋىڭىزدا ەنگىزگەن ە-پوشتا جايىڭىز
«كىمنەن» دەگەن باس جولاعىندا كورىنەدى, سوندىقتان
حات الۋشىسى تۋرا جاۋاپ بەرە الادى.',
'usermailererror' => 'Mail نىسانى قاتە قايتاردى:',
'defemailsubject' => '{{SITENAME}} ە-پوشتاسىنىڭ حاتى',
'noemailtitle'    => 'ەش ە-پوشتا جايى جوق',
'noemailtext'     => 'بۇل قاتىسۋشى جارامدى ە-پوشتا جايىن ەنگىزبەگەن,
نە باسقالاردان حات قابىلداۋىن وشىرگەن.',
'emailfrom'       => 'كىمنەن',
'emailto'         => 'كىمگە',
'emailsubject'    => 'تاقىرىبى',
'emailmessage'    => 'حات',
'emailsend'       => 'جىبەرۋ',
'emailccme'       => 'حاتىمدىڭ كوشىرمەسىن ماعان دا جىبەر.',
'emailccsubject'  => '$1 دەگەنگە جىبەرىلگەن حاتىڭىزدىڭ كوشىرمەسى: $2',
'emailsent'       => 'حات جىبەرىلدى',
'emailsenttext'   => 'ە-پوشتا حاتىڭىز جىبەرىلدى.',

# Watchlist
'watchlist'            => 'باقىلاۋ ٴتىزىمى',
'mywatchlist'          => 'باقىلاۋىم',
'watchlistfor'         => "('''$1''' باقىلاۋلارى)",
'nowatchlist'          => 'باقىلاۋ تىزىمىڭىزدە ەشبىر دانا جوق',
'watchlistanontext'    => 'باقىلاۋ تىزىمىڭىزدەگى دانالاردى قاراۋ, نە وڭدەۋ ٴۇشىن $1 كەرەك.',
'watchnologin'         => 'كىرمەگەنسىز',
'watchnologintext'     => 'باقىلاۋ ٴتىزىمىڭىزدى وزگەرتۋ ٴۇشىن [[{{ns:special}}:Userlogin|كىرۋىڭىز]] ٴجون.',
'addedwatch'           => 'باقىلاۋ تىزىمىنە ۇستەلدى',
'addedwatchtext'       => "«[[:$1]]» بەتى [[{{ns:special}}:Watchlist|باقىلاۋ تىزىمىڭىزگە]] ۇستەلدى.
وسى بەتتىڭ جانە سونىڭ تالقىلاۋ بەتىنىڭ كەلەشەكتەگى وزگەرىستەرى مىندا تىزىمدەلەدى.
سوندا بەتتىڭ اتاۋى تابۋعا جەڭىلدەتىپ [[{{ns:special}}:Recentchanges|جۋىقتاعى وزگەرىستەر تىزىمىندە]]
'''جۋان ارپىمەن''' كورسەتىلەدى.

وسى بەتتى سوڭىنان باقىلاۋ تىزىمنەن الاستاعىڭىز كەلسە «باقىلاماۋ» پاراعىن نۇقىڭىز.",
'removedwatch'         => 'باقىلاۋ تىزىمىڭىزدەن الاستالدى',
'removedwatchtext'     => '«[[:$1]]» بەتى باقىلاۋ تىزىمىڭىزدەن الاستالدى.',
'watch'                => 'باقىلاۋ',
'watchthispage'        => 'بەتتى باقىلاۋ',
'unwatch'              => 'باقىلاماۋ',
'unwatchthispage'      => 'باقىلاۋدى توقتاتۋ',
'notanarticle'         => 'ماعلۇمات بەتى ەمەس',
'watchnochange'        => 'كورسەتىلگەن مەرزىمدە ەش باقىلانعان دانا وڭدەلگەن جوق.',
'watchlist-details'    => 'تالقىلاۋ بەتتەرىن ساناماعاندا {{PLURAL:$1|1|$1}} بەت باقلانعان.',
'wlheader-enotif'      => '* ەسكەرتۋ حات جىبەرۋى قوسىلعان.',
'wlheader-showupdated' => "* سوڭعى كەلىپ-كەتۋىڭىزدەن بەرى وزگەرتىلگەن بەتتەردى '''جۋان''' قارىپىمەن كورسەت",
'watchmethod-recent'   => 'باقىلاۋلى بەتتەردىڭ جۋىقتاعى وزگەرىستەرىن تەكسەرۋ',
'watchmethod-list'     => 'جۋىقتاعى وزگەرىستەردە باقىلاۋلى بەتتەردى تەكسەرۋ',
'watchlistcontains'    => 'باقىلاۋ تىزىمىڭىزدە {{PLURAL:$1|1|$1}} بەت بار.',
'iteminvalidname'      => "'$1' داناسىنىڭ جارامسىز اتاۋىنان شاتاق تۋدى…",
'wlnote'               => "تومەندە سوڭعى {{PLURAL:$2|ساعاتتا|'''$2''' ساعاتتا}} بولعان, {{PLURAL:$1|جۋىقتاعى وزگەرىس|جۋىقتاعى '''$1''' وزگەرىس}} كورسەتىلگەن.",
'wlshowlast'           => 'سوڭعى $1 ساعاتتاعى, $2 كۇندەگى, $3 بولعان وزگەرىستى كورسەتۋ',
'watchlist-show-bots'  => 'بوتتاردى كورسەت',
'watchlist-hide-bots'  => 'بوتتاردى جاسىر',
'watchlist-show-own'   => 'تۇزەتۋىمدى كورسەت',
'watchlist-hide-own'   => 'تۇزەتۋىمدى جاسىر',
'watchlist-show-minor' => 'شاعىن تۇزەتۋدى كورسەت',
'watchlist-hide-minor' => 'شاعىن تۇزەتۋدى جاسىر',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'باقىلاۋ…',
'unwatching' => 'باقىلاماۋ…',

'enotif_mailer'                => '{{SITENAME}} ەسكەرتۋ حات جىبەرۋ قىزمەتى',
'enotif_reset'                 => 'بارلىق بەت كەلىپ-كەتىلدى دەپ بەلگىلە',
'enotif_newpagetext'           => 'مىناۋ جاڭا بەت.',
'enotif_impersonal_salutation' => '{{SITENAME}} قاتىسۋشىسى',
'changed'                      => 'وزگەرتتى',
'created'                      => 'جاراتتى',
'enotif_subject'               => '{{SITENAME}} جوباسىندا $PAGEEDITOR $PAGETITLE اتاۋلى بەتتى $CHANGEDORCREATED',
'enotif_lastvisited'           => 'سوڭعى كەلىپ-كەتۋىڭىزدەن بەرى بولعان وزگەرىستەر ٴۇشىن $1 دەگەندى قاراڭىز.',
'enotif_lastdiff'              => 'وسى وزگەرىس ٴۇشىن $1 دەگەندى قاراڭىز.',
'enotif_anon_editor'           => 'تىركەلگىسىز قاتىسۋشى $1',
'enotif_body'                  => 'قۇرمەتتى $WATCHINGUSERNAME,

{{SITENAME}} جوباسىنىڭ $PAGETITLE اتاۋلى بەتتى $PAGEEDITDATE كەزىندە $PAGEEDITOR دەگەن $CHANGEDORCREATED, اعىمدىق نۇسقاسىن $PAGETITLE_URL جايىنان قاراڭىز.

$NEWPAGE

وڭدەۋشى سىيپاتتاماسى: $PAGESUMMARY $PAGEMINOREDIT

وڭدەۋشىمەن قاتىناسۋ:
ە-پوشتا: $PAGEEDITOR_EMAIL
ۋىيكىي: $PAGEEDITOR_WIKI

بىلايعى وزگەرىستەر بولعاندا دا وسى بەتكە كەلىپ-كەتۋىڭىزگەنشە دەيىن ەشقانداي باسقا ەسكەرتۋ حاتتار جىبەرىلمەيدى. سونىمەن قاتار باقىلاۋ تىزىمىڭىزدەگى بەت ەسكەرتپەلىك بەلگىسىن قايتا قويىڭىز.

             ٴسىزدىڭ دوستى {{SITENAME}} ەسكەرتۋ قىزمەتى

----
باقىلاۋ ٴتىزىمىڭىزدىڭ باپتاۋلىرىن وزگەرتۋ ٴۇشىن, مىندا كەلىپ-كەتىڭىز:
{{fullurl:{{ns:special}}:Watchlist/edit}}

سىن-پىكىر بەرۋ جانە بىلايعى جاردەم الۋ ٴۇشىن:
{{fullurl:{{{{ns:mediawiki}}:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'بەتتى جويۋ',
'confirm'                     => 'قۇپتاۋ',
'excontent'                   => "بولعان ماعلۇماتى: '$1'",
'excontentauthor'             => "بولعان ماعلۇماتى (تەك '[[{{ns:special}}:Contributions/$2|$2]]' ۇلەسى): '$1'",
'exbeforeblank'               => "تازارتۋ الدىنداعى بولعان ماعلۇماتى: '$1'",
'exblank'                     => 'بەت بوس بولدى',
'delete-confirm'              => '«$1» دەگەندى جويۋ',
'delete-legend'               => 'جويۋ',
'historywarning'              => 'قۇلاقتاندىرۋ: جويۋعا ارنالعان بەتتە ٴوز تارىيحى بار:',
'confirmdeletetext'           => 'بەتتى نەمەسە سۋرەتتى بارلىق تارىيحىمەن بىرگە دەرەكقوردان ٴاردايىم جويىعىڭىز كەلەتىن سىياقتى.
بۇنى جويۋدىڭ زاردابىن ٴتۇسىنىپ شىن نىيەتتەنگەنىڭىزدى, جانە [[{{{{ns:mediawiki}}:Policy-url}}]] دەگەنگە لايىقتى دەپ سەنگەنىڭىزدى قۇپتاڭىز.',
'actioncomplete'              => 'ارەكەت ٴبىتتى',
'deletedtext'                 => '«<nowiki>$1</nowiki>» جويىلدى.جۋىقتاعى جويۋلار تۋرالى جازبالارىن $2 دەگەننەن قاراڭىز.',
'deletedarticle'              => '«[[$1]]» بەتىن جويدى',
'dellogpage'                  => 'جويۋ_جۋرنالى',
'dellogpagetext'              => 'تومەندە جۋىقتاعى جويۋلاردىڭ ٴتىزىمى بەرىلگەن.',
'deletionlog'                 => 'جويۋ جۋرنالى',
'reverted'                    => 'ەرتەرەك نۇسقاسىنا قايتارىلعان',
'deletecomment'               => 'جويۋدىڭ سەبەبى:',
'deleteotherreason'           => 'باسقا/قوسىمشا سەبەپ:',
'deletereasonotherlist'       => 'باسقا سەبەپ',
'deletereason-dropdown'       => '
* جويۋدىڭ جالپى سەبەپتەرى
** اۋتوردىڭ سۇرانىمى بويىنشا
** اۋتورلىق قۇقىقتارىن بۇزۋ
** بۇزاقىلىق',
'delete-toobig'               => 'بۇل بەتتە بايتاق وڭدەۋ تارىيحى بار, $1 نۇسقادان استام. بۇنداي بەتتەردىڭ جويۋى {{SITENAME}} تورابىن الدەقالاي ٴۇزىپ تاستاۋىنا بوگەت سالۋ ٴۇشىن تىيىمدالعان.',
'delete-warning-toobig'       => 'بۇل بەتتە بايتاق وڭدەۋ تارىيحى بار, $1 نۇسقادان استام. بۇنىڭ جويۋى {{SITENAME}} تورابىنداعى دەرەكقور ارەكەتتەردى ٴۇزىپ تاستاۋىن مۇمكىن; بۇنى ابايلاپ وتكىزىڭىز.',
'rollback'                    => 'تۇزەتۋلەردى كەرى قايتارۋ',
'rollback_short'              => 'كەرى قايتارۋ',
'rollbacklink'                => 'كەرى قايتارۋ',
'rollbackfailed'              => 'كەرى قايتارۋ ٴساتسىز ٴبىتتى',
'cantrollback'                => 'تۇزەتۋ قايتارىلمايدى; بۇل بەتتىڭ اۋتورى تەك سوڭعى ۇلەسكەر بولعان.',
'alreadyrolled'               => '[[:$1]] بەتىنىڭ [[User:$2|$2]] ([[User_talk:$2|تالقىلاۋى]])
سوڭعى تۇزەتۋى كەرى قايتارىلمادى; باسقا بىرەۋ بەتتى الداقاشان وڭدەگەن نە قايتارعان.

سوڭعى وڭدەۋدى [[User:$3|$3]] ([[User_talk:$3|تالقىلاۋى]]) ىستەگەن.',
'editcomment'                 => 'بولعان تۇزەتۋ ماندەمەسى: «<i>$1</i>».', # only shown if there is an edit comment
'revertpage'                  => '[[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|تالقىلاۋى]]) تۇزەتۋلەرىنەن قايتارعان; [[{{ns:user}}:$1|$1]] سوڭعى نۇسقاسىنا وزگەرتتى.', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success'            => '$1 تۇزەتۋلەرىنەن قايتارعان; $2 سوڭعى نۇسقاسىنا وزگەرتتى.',
'sessionfailure'              => 'كىرۋ سەسسىياسىندا شاتاق بولعان سىياقتى; سەسسىياعا شابۋىلداۋداردان قورعانۋ ٴۇشىن, وسى ارەكەت توقتاتىلدى. «ارتقا» تۇيمەسىن باسىڭىز, جانە بەتتى كەرى جۇكتەڭىز, سوسىن قايتا بايقاپ كورىڭىز.',
'protectlogpage'              => 'قورعاۋ_جۋرنالى',
'protectlogtext'              => 'تومەندە بەتتەردىڭ قورعاۋ/قورعاماۋ ٴتىزىمى بەرىلگەن. اعىمداعى قورعاۋ ارەكتتەر بار بەتتەر ٴۇشىن [[{{ns:special}}:Protectedpages|قورعالعان بەت ٴتىزىمىن]] قاراڭىز.',
'protectedarticle'            => '«[[$1]]» قورعالدى',
'modifiedarticleprotection'   => '«[[$1]]» دەگەننىڭ قورعالۋ دەڭگەيى وزگەردى',
'unprotectedarticle'          => '«[[$1]]» قورعالمادى',
'protect-title'               => '«$1» قورعاۋ دەڭگەيىن قويۋ',
'protect-legend'              => 'قورعاۋدى قۇپتاۋ',
'protectcomment'              => 'ماندەمەسى:',
'protectexpiry'               => 'بىتەتىن مەرزىمى:',
'protect_expiry_invalid'      => 'بىتەتىن ۋاقىتى جارامسىز.',
'protect_expiry_old'          => 'بىتەتىن ۋاقىتى ٴوتىپ كەتكەن.',
'protect-unchain'             => 'جىلجىتۋ رۇقساتتارىن بەرۋ',
'protect-text'                => '<strong><nowiki>$1</nowiki></strong> بەتىنىڭ قورعاۋ دەڭگەيىن قاراپ جانە وزگەرتىپ شىعا الاسىز.',
'protect-locked-blocked'      => 'بۇعاتتاۋىڭىز وشىرىلگەنشە دەيىن قورعاۋ دەڭگەيىن وزگەرتە المايسىز.
مىنا <strong>$1</strong> بەتتىڭ اعىمدىق باپتاۋلارى:',
'protect-locked-dblock'       => 'دەرەكقوردىڭ قۇلىپتاۋى بەلسەندى بولعاندىقتان قورعاۋ دەڭگەيلەرى وزگەرتىلمەيدى.
مىنا <strong>$1</strong> بەتتىڭ اعىمدىق باپتاۋلارى:',
'protect-locked-access'       => 'تىركەلگىڭىزگە بەت قورعاۋ دەنگەيلەرىن وزگەرتۋىنە رۇقسات جوق.
مىنا <strong>$1</strong> بەتتىڭ اعىمدىق باپتاۋلارى:',
'protect-cascadeon'           => 'بۇل بەت اعىمدا قورعالعان, سەبەبى: وسى بەت باۋلى قورعاۋى بار كەلەسى {{PLURAL:$1|بەتكە|بەتتەرگە}} كىرىستىرىلگەن. بۇل بەتتىڭ قورعاۋ دەڭگەيىن وزگەرتە الاسىز, بىراق بۇل باۋلى قورعاۋعا ىقپال ەتپەيدى.',
'protect-default'             => '(ادەپكى)',
'protect-fallback'            => '«$1» رۇقساتى كەرەك بولدى',
'protect-level-autoconfirmed' => 'تىركەلگىسىزدەرگە تىيىم',
'protect-level-sysop'         => 'تەك اكىمشىلەرگە رۇقسات',
'protect-summary-cascade'     => 'باۋلى',
'protect-expiring'            => 'ٴبىتۋى: $1 (UTC)',
'protect-cascade'             => 'بۇل بەتكە كىرىستىرىلگەن بەتتەردى قورعاۋ (باۋلى قورعاۋ).',
'protect-cantedit'            => 'بۇل بەتتىڭ قورعاۋ دەڭگەيىن وزگەرتە المايسىز, سەبەبى بۇنى وڭدەۋگە رۇقستاڭىز جوق.',
'restriction-type'            => 'رۇقساتى:',
'restriction-level'           => 'تىيىم دەڭگەيى:',
'minimum-size'                => 'ەڭ از مولشەرى',
'maximum-size'                => 'ەڭ كوپ مولشەرى',
'pagesize'                    => '(بايت)',

# Restrictions (nouns)
'restriction-edit'   => 'وڭدەۋگە',
'restriction-move'   => 'جىلجىتۋعا',
'restriction-create' => 'جاراتۋ',

# Restriction levels
'restriction-level-sysop'         => 'تولىق قورعالعان',
'restriction-level-autoconfirmed' => 'جارتىلاي قورعالعان',
'restriction-level-all'           => 'ارقايسى دەڭگەيدە',

# Undelete
'undelete'                     => 'جويىلعان بەتتەردى قاراۋ',
'undeletepage'                 => 'جويىلعان بەتتەردى قاراۋ جانە قالپىنا كەلتىرۋ',
'viewdeletedpage'              => 'جويىلعان بەتتەردى قاراۋ',
'undeletepagetext'             => 'كەلەسى بەتتەر جويىلدى دەپ بەلگىلەنگەن, بىراق ماعلۇماتى مۇراعاتتا بار
جانە قالپىنا كەلتىرۋگە مۇمكىن. مۇراعات مەرزىم بويىنشا تازالانىپ تۇرۋى مۇمكىن.',
'undeleteextrahelp'            => "بۇكىل بەتتى قالپىنا كەلتىرۋ ٴۇشىن, بارلىق شارشىلاردى قۇسبەلگىلەردەن بوساتىپ
'''''قالپىنا كەلتىر!''''' باتىرماسىن نۇقىڭىز. بولەكتەۋمەن قالپىنا كەلتىرۋ ورىنداۋ ٴۇشىن, كەلتىرەمىن دەگەن نۇسقالارىنا سايكەس
قاباشاقتارىن بەلگىلەڭىز دە, جانە '''''قالپىنا كەلتىر!''''' تۇيمەسىن نۇقىڭىز. '''''قايتا قوي''''' تۇيمەسىن
نۇقىعاندا ماندەمە اۋماعى تازارتادى جانە بارلىق شارشىلاردى قۇسبەلگىلەردەن بوساتادى.",
'undeleterevisions'            => '{{PLURAL:$1|1|$1}} نۇسقا مۇراعاتتالدى',
'undeletehistory'              => 'ەگەر بەت ماعلۇماتىن قالپىنا كەلتىرسەڭىز, تارىيحىندا بارلىق نۇسقالار دا
قايتارىلادى. ەگەر جويۋدان سوڭ ٴدال سولاي اتاۋىمەن جاڭا بەت باستالسا, قالپىنا كەلتىرىلگەن نۇسقالار
تارىيحتىڭ الدىندا كورسەتىلەدى. تاعى دا فايل نۇسقالارىنىڭ قالپىنا كەلتىرگەندە تىيىمدارى جويىلاتىن ۇمىتپاڭىز.',
'undeleterevdel'               => 'ەگەر بەتتىڭ ۇستىڭگى نۇسقاسى جارىم-جارتىلاي جويىلعان بولسا جويۋ بولدىرماۋى
اتقارىلمايدى. وسىنداي جاعدايلاردا, ەڭ جاڭا جويىلعان نۇسقا بەلگىلەۋىن نەمەسە جاسىرۋىن بولدىرماڭىز.
كورۋىڭىزگە رۇقسات ەتىلمەگەن فايل نۇسقالارى قالپىنا كەلتىرىلمەيدى.',
'undeletehistorynoadmin'       => 'بۇل بەت جويىلعان. جويۋ سەبەبى الدىنداعى وڭدەگەن قاتىسۋشىلار
ەگجەي-تەگجەيلەرىمەن بىرگە تومەندەگى سىيپاتتاماسىندا كورسەتىلگەن.
وسى جويىلعان نۇسقالاردىڭ ٴماتىنى تەك اكىمشىلەرگە قاتىناۋلى.',
'undelete-revision'            => '$2 كەزىندەگى $1 دەگەننىڭ نۇسقاسى ($3 جويعان):',
'undeleterevision-missing'     => 'جارامسىز نە جوعالعان نۇسقا. سىلتەمەڭىز جارامسىز بولۋى مۇمكىن, نە
نۇسقا الداقاشان قالپىنا كەلتىرىلگەن نەمەسە مۇراعاتتان الاستالعان.',
'undelete-nodiff'              => 'ەش الدىڭعى نۇسقا تابىلمادى.',
'undeletebtn'                  => 'قالپىنا كەلتىر!',
'undeletereset'                => 'قايتا قوي',
'undeletecomment'              => 'ماندەمەسى:',
'undeletedarticle'             => '«[[$1]]» قالپىنا كەلتىرىلدى',
'undeletedrevisions'           => '{{PLURAL:$1|1|$1}} نۇسقا قالپىنا كەلتىرىلدى',
'undeletedrevisions-files'     => '{{PLURAL:$1|1|$1}} نۇسقا جانە {{PLURAL:$2|1|$2}} فايل قالپىنا كەلتىرىلدى',
'undeletedfiles'               => '{{PLURAL:$1|1|$1}} فايل قالپىنا كەلتىرىلدى',
'cannotundelete'               => 'جويۋ بولدىرماۋى ٴساتسىز ٴبىتتى; باسقا بىرەۋ العاشىندا بەتتىڭ جويۋدىڭ بولدىرماۋى مۇمكىن.',
'undeletedpage'                => "<big>'''$1 قالپىنا كەلتىرىلدى'''</big>

جۋىقتاعى جويۋلار مەن قالپىنا كەلتىرۋلەر جونىندە [[{{ns:special}}:Log/delete|جويۋ جۋرنالىن]] قاراڭىز.",
'undelete-header'              => 'جۋىقتاعى جويىلعان بەتتەر جونىندە [[{{ns:special}}:Log/delete|جويۋ جۋرنالىن]] قاراڭىز.',
'undelete-search-box'          => 'جويىلعان بەتتەردى ىزدەۋ',
'undelete-search-prefix'       => 'مىنادان باستالعان بەتتەردى كورسەت:',
'undelete-search-submit'       => 'ىزدەۋ',
'undelete-no-results'          => 'جويۋ مۇراعاتىندا ەشقانداي سايكەس بەتتەر تابىلمادى.',
'undelete-filename-mismatch'   => '$1 ۋاقىت بەلگىسىمەن فايل نۇسقاسى جويۋدى بولدىرماۋ اتقارىلمادى: فايل اتى سايكەسسىز',
'undelete-bad-store-key'       => '$1 ۋاقىت بەلگىسىمەن فايل نۇسقاسى جويۋدى بولدىرماۋ اتقارىلمادى: جويۋدىڭ الدىنان فايل جوق بولعان.',
'undelete-cleanup-error'       => '«$1» پايدالانىلماعان مۇراعاتتالعان فايل جويۋ قاتەسى.',
'undelete-missing-filearchive' => 'مۇراعاتتالعان فايل (ٴنومىرى $1) قالپىنا كەلتىرىلمەدى, سەبەبى ول دەرەكقوردا جوق. بۇنىڭ جويۋىن بولدىرماۋى الداقاشان بولعانى مۇمكىن.',
'undelete-error-short'         => 'فايل جويۋىن بولدىرماۋ قاتەسى: $1',
'undelete-error-long'          => 'فايل جويۋىن بولدىرماۋ كەزىندە مىنا قاتەلەر كەزدەستى:

$1',

# Namespace form on various pages
'namespace'      => 'ەسىم اياسى:',
'invert'         => 'بولەكتەۋدى كەرىلەۋ',
'blanknamespace' => '(نەگىزگى)',

# Contributions
'contributions' => 'قاتىسۋشى ۇلەسى',
'mycontris'     => 'ۇلەسىم',
'contribsub2'   => '$1 ($2) ۇلەسى',
'nocontribs'    => 'وسى ىزدەۋ شارتىنا سايكەس وزگەرىستەر تابىلعان جوق.',
'uctop'         => ' (ٴۇستى)',
'month'         => 'ايداعى (جانە ەرتەرەكتەن):',
'year'          => 'جىلداعى (جانە ەرتەرەكتەن):',

'sp-contributions-newbies'     => 'تەك جاڭا تىركەلگىدەن جاساعان ۇلەستەردى كورسەت',
'sp-contributions-newbies-sub' => 'جاڭادان تىركەلگى جاساعاندار ٴۇشىن',
'sp-contributions-blocklog'    => 'بۇعاتتاۋ جۋرنالى',
'sp-contributions-search'      => 'ۇلەس ٴۇشىن ىزدەۋ',
'sp-contributions-username'    => 'IP جاي نە قاتىسۋشى اتى:',
'sp-contributions-submit'      => 'ىزدە',

# What links here
'whatlinkshere'       => 'سىلتەلگەن بەتتەر',
'whatlinkshere-title' => '$1 دەگەنگە سىلتەيتىن بەتتەر',
'whatlinkshere-page'  => 'بەت:',
'linklistsub'         => '(سىلتەمەلەر ٴتىزىمى)',
'linkshere'           => "'''[[:$1]]''' دەگەنگە مىنا بەتتەر سىلتەيدى:",
'nolinkshere'         => "'''[[:$1]]''' دەگەنگە ەش بەت سىلتەمەيدى.",
'nolinkshere-ns'      => "تالعانعان ەسىم اياسىندا '''[[:$1]]''' دەگەنگە ەشقانداي بەت سىلتەمەيدى.",
'isredirect'          => 'ايداتۋ بەتى',
'istemplate'          => 'كىرىكتىرۋ',
'whatlinkshere-prev'  => '{{PLURAL:$1|الدىڭعى|الدىڭعى $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|كەلەسى|كەلەسى $1}}',
'whatlinkshere-links' => '← سىلتەمەلەر',

# Block/unblock
'blockip'                     => 'قاتىسۋشىنى بۇعاتتاۋ',
'blockiptext'                 => 'تومەندەگى ٴپىشىن قاتىسۋشىنىڭ جازۋ 
رۇقساتىن بەلگىلى IP جايىمەن نە اتاۋىمەن بۇعاتتاۋ ٴۇشىن قولدانىلادى.
بۇنى تەك بۇزاقىلىقتى قاقپايلاۋ ٴۇشىن جانە دە
[[{{{{ns:mediawiki}}:Policy-url}}|ەرەجەلەر]] بويىنشا اتقارۋىڭىز ٴجون.
تومەندە ٴتىيىستى سەبەبىن تولتىرىپ كورسەتىڭىز (مىسالى, دايەككە بۇزاقىلىقپەن
وزگەرتكەن بەتتەردى كەلتىرىپ).',
'ipaddress'                   => 'IP جايى:',
'ipadressorusername'          => 'IP جايى نە اتى:',
'ipbexpiry'                   => 'بىتەتىن مەرزىمى:',
'ipbreason'                   => 'سەبەبى:',
'ipbreasonotherlist'          => 'باسقا سەبەپ',
'ipbreason-dropdown'          => '
* بۇعاتتاۋدىڭ جالپى سەبەبتەرى 
** جالعان مالىمەت ەنگىزۋ 
** بەتتەردەگى ماعلۇماتتى الاستاۋ 
** سىرتقى توراپتار سىلتەمەلەرىن جاۋدىرۋ 
** بەتتەرگە ماعىناسىزدىق/بالدىرلاۋ كىرىستىرۋ 
** قوقانداۋ/قۋعىنداۋ مىنەزقۇلىق 
** بىرنەشە رەت تىركەلىپ قىياناتتاۋ 
** قولايسىز قاتىسۋشى اتاۋى',
'ipbanononly'                 => 'تەك تىركەلگىسىز قاتىسۋشىلاردى بۇعاتتاۋ',
'ipbcreateaccount'            => 'تىركەلۋدى قاقپايلاۋ',
'ipbemailban'                 => 'قاتىسۋشى ە-پوشتامەن حات جىبەرۋىن قاقپايلاۋ',
'ipbenableautoblock'          => 'بۇل قاتىسۋشى سوڭعى قولدانعان IP جايى, جانە كەيىن وڭدەۋگە بايقاپ كورگەن ارقايسى IP جايلارى وزدىكتىك بۇعاتتالسىن',
'ipbsubmit'                   => 'قاتىسۋشىنى بۇعاتتا',
'ipbother'                    => 'باسقا مەرزىمى:',
'ipboptions'                  => '2 ساعات:2 hours,1 كۇن:1 day,3 كۇن:3 days,1 اپتا:1 week,2 اپتا:2 weeks,1 اي:1 month,3 اي:3 months,6 اي:6 months,1 جىل:1 year,مانگى:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'باسقا',
'ipbotherreason'              => 'باسقا/قوسىمشا سەبەپ:',
'ipbhidename'                 => 'بۇعاتتاۋ جۋرنالىنداعى, بەلسەندى بۇعاتتاۋ تىزىمىندەگى, قاتىسۋشى تىزىمىننەگى اتى/IP جاسىرىلسىن',
'badipaddress'                => 'جارامسىز IP جاي',
'blockipsuccesssub'           => 'بۇعاتتاۋ ٴساتتى ٴوتتى',
'blockipsuccesstext'          => '[[{{ns:special}}:Contributions/$1|$1]] دەگەن بۇعاتتالعان.
<br />بۇعاتتاردى شولىپ شىعۋ ٴۇشىن [[{{ns:special}}:Ipblocklist|IP بۇعاتتاۋ ٴتىزىمىن]] قاراڭىز.',
'ipb-edit-dropdown'           => 'بۇعاتتاۋ سەبەپتەرىن وڭدەۋ',
'ipb-unblock-addr'            => '$1 دەگەندى بۇعاتتاماۋ',
'ipb-unblock'                 => 'قاتىسۋشى اتىن نەمەسە IP جايىن بۇعاتتاماۋ',
'ipb-blocklist-addr'          => '$1 ٴۇشىن بار بۇعاتتاۋلاردى قاراۋ',
'ipb-blocklist'               => 'بار بۇعاتتاۋلاردى قاراۋ',
'unblockip'                   => 'قاتىسۋشىنى بۇعاتتاماۋ',
'unblockiptext'               => 'تومەندەگى ٴپىشىندى الدىنداعى IP جايىمەن نە اتاۋىمەن بۇعاتتالعان قاتىسۋشىعا جازۋ قاتىناۋىن قالپىنا كەلتىرىۋى ٴۇشىن قولدانىڭىز.',
'ipusubmit'                   => 'وسى جايدى بۇعاتتاماۋ',
'unblocked'                   => '[[User:$1|$1]] بۇعاتتاۋى ٴوشىرىلدى',
'unblocked-id'                => '$1 دەگەن بۇعاتتاۋ الاستالدى',
'ipblocklist'                 => 'بۇعاتتالعان قاتىسۋشى / IP جاي ٴتىزىمى',
'ipblocklist-legend'          => 'بۇعاتتالعان قاتىسۋشىنى تابۋ',
'ipblocklist-username'        => 'قاتىسۋشى اتى / IP جاي:',
'ipblocklist-submit'          => 'ىزدە',
'blocklistline'               => '$1, $2 $3 دەگەندى بۇعاتتادى ($4)',
'infiniteblock'               => 'مانگى',
'expiringblock'               => 'ٴبىتۋى: $1',
'anononlyblock'               => 'تەك تىركەلگىسىزدەر',
'noautoblockblock'            => 'وزدىكتىك بۇعاتتاۋ وشىرىلگەن',
'createaccountblock'          => 'تىركەلۋ بۇعاتتالعان',
'emailblock'                  => 'ە-پوشتا بۇعاتتالعان',
'ipblocklist-empty'           => 'بۇعاتتاۋ ٴتىزىمى بوس.',
'ipblocklist-no-results'      => 'سۇراتىلعان IP جاي نە قاتىسۋشى اتى بۇعاتتالعان ەمەس.',
'blocklink'                   => 'بۇعاتتاۋ',
'unblocklink'                 => 'بۇعاتتاماۋ',
'contribslink'                => 'ۇلەسى',
'autoblocker'                 => 'IP جايىڭىزدى جۋىقتا «[[User:1|$1]]» پايدالانعان, سوندىقتان وزدىكتىك بۇعاتتالعان. $1 بۇعاتتاۋى ٴۇشىن كەلتىرىلگەن سەبەبى: «$2».',
'blocklogpage'                => 'بۇعاتتاۋ_جۋرنالى',
'blocklogentry'               => '[[$1]] دەگەندى $2 مەرزىمگە بۇعاتتادى $3',
'blocklogtext'                => 'بۇل قاتىسۋشىلاردى بۇعاتتاۋ/بۇعاتتاماۋ ارەكەتتەرىنىڭ جۋرنالى. وزدىكتىك
بۇعاتتالعان IP جايلار وسىندا تىزىمدەلگەمەگەن. اعىمداعى بەلسەندى بۇعاتتاۋلارىن
[[{{ns:special}}:Ipblocklist|IP بۇعاتتاۋ تىزىمىنەن]] قاراۋعا بولادى.',
'unblocklogentry'             => '«$1» دەگەننىڭ بۇعاتتاۋىن ٴوشىردى',
'block-log-flags-anononly'    => 'تەك تىركەلگىسىزدەر',
'block-log-flags-nocreate'    => 'تىركەلۋ وشىرىلگەن',
'block-log-flags-noautoblock' => 'وزدىكتىك بۇعاتتاۋ وشىرىلگەن',
'block-log-flags-noemail'     => 'ە-پوشتا بۇعاتتالعان',
'range_block_disabled'        => 'اۋقىم بۇعاتتاۋلارىن جاساۋ اكىمشىلىك مۇمكىندىگى وشىرىلگەن.',
'ipb_expiry_invalid'          => 'بىتەتىن ۋاقىتى جارامسىز.',
'ipb_already_blocked'         => '«$1» الداقاشان بۇعاتتالعان',
'ipb_cant_unblock'            => 'قاتەلىك: IP $1 بۇعاتتاۋى تابىلمادى. ونىڭ بۇعاتتاۋى الداقاشان وشىرلگەن مۇمكىن.',
'ipb_blocked_as_range'        => 'قاتەلىك: IP $1 تىكەلەي بۇعاتتالماعان جانە بۇعاتتاۋى وشىرىلمەيدى. بىراق, بۇل بۇعاتتاۋى ٴوشىرىلۋى مۇمكىن $2 اۋقىمى بولىگى بوپ بۇعاتتالعان.',
'ip_range_invalid'            => 'IP جاي اۋقىمى جارامسىز.',
'blockme'                     => 'وزدىكتىك_بۇعاتتاۋ',
'proxyblocker'                => 'پروكسىي سەرۆەرلەردى بۇعاتتاۋىش',
'proxyblocker-disabled'       => 'بۇل فۋنكتسىيا وشىرىلگەن.',
'proxyblockreason'            => 'IP جايىڭىز اشىق پروكسىي سەرۆەرگە جاتاتىندىقتان بۇعاتتالعان. ىينتەرنەت قىزمەتىن جابدىقتاۋشىڭىزبەن, نە تەحنىيكالىق مەدەۋ قىزمەتىمەن قاتىناسىڭىز, جانە ولارعا وسى وتە كۇردەلى قاۋىپسىزدىك شاتاق تۋرالى اقپارات بەرىڭىز.',
'proxyblocksuccess'           => 'ٴبىتتى.',
'sorbsreason'                 => 'ٴسىزدىڭ IP جايىڭىز {{SITENAME}} تورابىندا قولدانىلعان DNSBL قارا تىزىمىندەگى اشىق پروكسىي-سەرۆەر دەپ تابىلادى.',
'sorbs_create_account_reason' => 'ٴسىزدىڭ IP جايىڭىز {{SITENAME}} تورابىندا قولدانىلعان DNSBL قارا تىزىمىندەگى اشىق پروكسىي-سەرۆەر دەپ تابىلادى. تىركەلگىنى جاراتا المايسىز.',

# Developer tools
'lockdb'              => 'دەرەكقوردى قۇلىپتاۋ',
'unlockdb'            => 'دەرەكقوردى قۇلىپتاماۋ',
'lockdbtext'          => 'دەرەكقوردىن قۇلىپتالۋى بارلىق قاتىسۋشىلاردىڭ
بەت وڭدەۋ, باپتاۋىن قالاۋ, باقىلاۋ ٴتىزىمىن, تاعى باسقا
دەرەكقوردى وزگەرتەتىن مۇمكىندىكتەرىن توقتاتا تۇرادى.
وسى ماقساتىڭىزدى, جانە جوندەۋىڭىز بىتكەندە
دەرەكقوردى اشاتىڭىزدى قۇپتاڭىز.',
'unlockdbtext'        => 'دەرەكقودىن اشىلۋى بارلىق قاتىسۋشىلاردىڭ بەت وڭدەۋ,
باپتاۋىن قالاۋ, باقىلاۋ ٴتىزىمىن, تاعى باسقا دەرەكقوردى وزگەرتەتىن
مۇمكىندىكتەرىن قالپىنا كەلتىرەدى.
وسى ماقساتىڭىزدى قۇپتاڭىز.',
'lockconfirm'         => 'ٴىيا, مەن دەرەكقوردى راستان قۇلىپتايمىن.',
'unlockconfirm'       => 'ٴىيا, مەن دەرەكقوردى راستان قۇلىپتامايمىن.',
'lockbtn'             => 'دەرەكقوردى قۇلىپتا',
'unlockbtn'           => 'دەرەكقوردى قۇلىپتاما',
'locknoconfirm'       => 'قۇپتاۋ بەلگىسىن قويماپسىز.',
'lockdbsuccesssub'    => 'دەرەكقور قۇلىپتاۋى ٴساتتى ٴوتتى',
'unlockdbsuccesssub'  => 'دەرەكقور قۇلىپتاۋى الاستالدى',
'lockdbsuccesstext'   => 'دەرەكقور قۇلىپتالدى.
<br />جوندەۋىڭىز بىتكەننەن كەيىن [[{{ns:special}}:Unlockdb|قۇلىپتاۋىن الاستاۋعا]] ۇمىتپاڭىز.',
'unlockdbsuccesstext' => 'قۇلىپتالعان دەرەكقور ٴساتتى اشىلدى.',
'lockfilenotwritable' => 'دەرەكقور قۇلىپتاۋ فايلى جازىلمايدى. دەرەكقوردى قۇلىپتاۋ نە اشۋ ٴۇشىن, ۆەب-سەرۆەر فايلعا جازۋ رۇقساتى بولۋ كەرەك.',
'databasenotlocked'   => 'دەرەكقور قۇلىپتالعان جوق.',

# Move page
'move-page-legend'        => 'بەتتى جىلجىتۋ',
'movepagetext'            => "تومەندەگى ٴپىشىندى قولدانىپ بەتتەردى قايتا اتايدى,
بارلىق تارىيحىن جاڭا اتاۋعا جىلجىتادى.
بۇرىنعى بەت اتاۋى جاڭا اتاۋعا ايداتاتىن بەت بولادى.
ەسكى اتاۋىنا سىلتەيتىن سىلتەمەلەر وزگەرتىلمەيدى; جىلجىتۋدان سوڭ
شىنجىرلى نە جارامسىز ايداتۋلار بار-جوعىن تەكسەرىپ شىعىڭىز.
سىلتەمەلەر بۇرىنعى جولداۋىمەن بىلايعى ٴوتۋىن تەكسەرۋىنە
ٴسىز مىندەتتى بولاسىز.

اڭعارتپا: ەگەر مىندا الداقاشان جاڭا اتاۋى بار بەت بولسا, جانە سوڭعى تۇزەتۋ تارىيحسىز
بوس بەت نە ايداتۋ بولعانشا دەيىن, بەت '''جىلجىتىلمايدى'''.
وسىنىڭ ماعىناسى: ەگەر بەتتى قاتەلىكپەن قايتا اتاساڭىز,
بۇرىنعى اتاۋىنا قايتا اتاۋعا بولادى, جانە بار بەتتىڭ ۇستىنە
جازۋىڭىزعا بولمايدى.

<b>قۇلاقتاندىرۋ!</b>
بۇل كوپ قارالاتىن بەتكە قاتاڭ جانە كەنەت وزگەرىس جاساۋعا مۇمكىن;
ارەكەتتىڭ الدىنان وسىنىڭ زارداپتارىن تۇسىنگەنىڭىزگە باتىل
بولىڭىز.",
'movepagetalktext'        => "كەلەسى سەبەپتەر '''بولعانشا''' دەيىن, تالقىلاۋ بەتى بۇنىمەن بىرگە وزدىكتىك جىلجىتىلادى:
* بوس ەمەس تالقىلاۋ بەتى جاڭا اتاۋدا الداقاشان بولعاندا, نە
* تومەندەگى قابىشاقتا بەلگىنى بولدىرماعاندا.

وسى ورايدا, قالاۋىڭىز بولسا, بەتتى قولدان جىلجىتا نە قوسا الاسىز.",
'movearticle'             => 'بەتتى جىلجىتۋ:',
'movenologin'             => 'جۇيەگە كىرمەگەنسىز',
'movenologintext'         => 'بەتتى جىلجىتۋ ٴۇشىن تىركەلگەن بولۋىڭىز جانە [[{{ns:special}}:Userlogin|كىرۋىڭىز]] كەرەك.',
'movenotallowed'          => '{{SITENAME}} جوباسىندا بەتتەردى جىلجىتۋ رۋقساتىڭىز جوق.',
'newtitle'                => 'جاڭا اتاۋعا:',
'move-watch'              => 'بۇل بەتتى باقىلاۋ',
'movepagebtn'             => 'بەتتى جىلجىت',
'pagemovedsub'            => 'جىلجىتۋ ٴساتتى اياقتالدى',
'movepage-moved'          => "<big>'''«$1» دەگەن «$2» دەگەنگە جىلجىتىلدى'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'وسىلاي اتالعان بەت الداقاشان بار, نە
تاڭداعان اتاۋىڭىز جارامدى ەمەس.
باسقا اتاۋ تانداڭىز',
'cantmove-titleprotected' => 'بەتتى مىنا ورىنعا جىلجىتا المايسىز, سەبەبى وسى جاڭا اتاۋ جاراتىلۋى قورعالعان',
'talkexists'              => "'''بەتتىڭ ٴوزى ٴساتتى جىلجىتىلدى, بىراق تالقىلاۋ بەتى بىرگە جىلجىتىلمادى, ونىڭ سەبەبى جاڭا اتاۋدىڭ تالقىلاۋ بەتى الداقاشان بار. بۇنى قولمەن قوسىڭىز.'''",
'movedto'                 => 'مىناعان جىلجىتىلدى:',
'movetalk'                => 'قاتىستى تالقىلاۋ بەتىمەن بىرگە جىلجىتۋ',
'talkpagemoved'           => 'قاتىستى تالقىلاۋ بەتى دە جىلجىتىلدى.',
'talkpagenotmoved'        => 'قاتىستى تالقىلاۋ بەتى <strong>جىلجىتىلمادى</strong>.',
'1movedto2'               => '«[[$1]]» بەتىندە ايداتۋ قالدىرىپ «[[$2]]» بەتىنە جىلجىتتى',
'1movedto2_redir'         => '«[[$1]]» بەتىن «[[$2]]» ايداتۋ بەتىنىڭ ۇستىنە جىلجىتتى',
'movelogpage'             => 'جىلجىتۋ جۋرنالى',
'movelogpagetext'         => 'تومەندە جىلجىتىلعان بەتتەردىڭ ٴتىزىمى بەرىلىپ تۇر.',
'movereason'              => 'سەبەبى:',
'revertmove'              => 'قايتارۋ',
'delete_and_move'         => 'جويۋ جانە جىلجىتۋ',
'delete_and_move_text'    => '==جويۋ كەرەك==

نىسانا بەت «[[$1]]» الداقاشان بار. جىلجىتۋعا جول بەرۋ ٴۇشىن جويامىز با?',
'delete_and_move_confirm' => 'ٴىيا, بۇل بەتتى جوي',
'delete_and_move_reason'  => 'جىلجىتۋعا جول بەرۋ ٴۇشىن جويىلعان',
'selfmove'                => 'قاينار جانە نىسانا اتاۋى بىردەي; بەت وزىنە جىلجىتىلمايدى.',
'immobile_namespace'      => 'قاينار نە نىسانا اتاۋى ارنايى تۇرىنە جاتادى; وسىنداي ەسىم اياسىنا جانە ەسىم اياسىنان بەتتەر جىلجىتىلمايدى.',

# Export
'export'            => 'بەتتەردى سىرتقا بەرۋ',
'exporttext'        => 'XML پىشىمىنە قاپتالعان بولەك بەت نە بەتتەر بۋماسى
ٴماتىنىڭ جانە وڭدەۋ تارىيحىن سىرتقا بەرە الاسىز. وسىنى, باسقا ۋىيكىيگە
جۇيەنىڭ [[{{ns:special}}:Import|سىرتتان الۋ بەتىن]] پايدالانىپ, الۋعا بولادى.

بەتتەردى سىرتقا بەرۋ ٴۇشىن, اتاۋلارىن تومەندەگى ٴماتىن اۋماعىنا ەنگىزىڭىز
(ٴبىر جولدا ٴبىر اتاۋ), جانە دە بولەكتەڭىز: نە اعىمدىق نۇسقاسىن, بارلىق ەسكى نۇسقالارى مەن
جانە تارىيحى جولدارى مەن بىرگە, نەمەسە ٴدال اعىمدىق نۇسقاسىن, سوڭعى وڭدەۋ تۋرالى اقپاراتى مەن بىرگە.

سوڭعى جاعدايدا سىلتەمەنى دە, مىسالى «{{{{ns:mediawiki}}:Mainpage}}» بەتى ٴۇشىن [[{{ns:special}}:Export/{{MediaWiki:Mainpage}}]] قولدانۋعا بولادى.',
'exportcuronly'     => 'تولىق تارىيحىن ەمەس, تەك اعىمدىق نۇسقاسىن كىرىستىرىڭىز',
'exportnohistory'   => "----
'''اڭعارتپا:''' ونىمدىلىك اسەرى سەبەپتەرىنەن, بەتتەردىڭ تولىق تارىيحىن بۇل پىشىنمەن سىرتقا بەرۋى وشىرىلگەن.",
'export-submit'     => 'سىرتقا بەر',
'export-addcattext' => 'مىنا ساناتتاعى بەتتەردى ۇستەۋ:',
'export-addcat'     => 'ۇستە',
'export-download'   => 'فايل تۇرىندە ساقتاۋ',
'export-templates'  => 'ۇلگىلەردى قوسا الىپ',

# Namespace 8 related
'allmessages'               => 'جۇيە حابارلارى',
'allmessagesname'           => 'اتاۋى',
'allmessagesdefault'        => 'ادەپكى ٴماتىنى',
'allmessagescurrent'        => 'اعىمدىق ٴماتىنى',
'allmessagestext'           => 'بۇل {{ns:mediawiki}} ەسىم اياسىندا قاتىناۋلى جۇيە حابار ٴتىزىمى.',
'allmessagesnotsupportedDB' => "'''\$wgUseDatabaseMessages''' وشىرىلگەن سەبەبىنەن '''{{ns:special}}:AllMessages''' بەتى قولدانىلمايدى.",
'allmessagesfilter'         => 'حابار اتاۋىمەن سۇزگىلەۋ:',
'allmessagesmodified'       => 'تەك وزگەرتىلگەندى كورسەت',

# Thumbnails
'thumbnail-more'           => 'ۇلكەيتۋ',
'filemissing'              => 'جوعالعان فايل',
'thumbnail_error'          => 'نوباي جاراتىلۋ قاتەسى: $1',
'djvu_page_error'          => 'DjVu بەتى مۇمكىندى اۋماقتىڭ سىرتىنددا',
'djvu_no_xml'              => 'DjVu فايلىنا XML كەلتىرۋگە بولمايدى',
'thumbnail_invalid_params' => 'نوبايدىڭ باپتالىمدارى جارامسىز',
'thumbnail_dest_directory' => 'نىسانا قالتا جاراتىلمادى',

# Special:Import
'import'                     => 'بەتتەردى سىرتتان الۋ',
'importinterwiki'            => 'ۋىيكىي-تاسىمالداپ سىرتتان الۋ',
'import-interwiki-text'      => 'سىرتتان الاتىن ۋىيكىي جوباسىن جانە بەت اتاۋىن بولەكتەڭىز.
نۇسقا كۇن-ايى جانە وڭدەۋشى اتتارى ساقتالادى.
بارلىق ۋىيكىي-تاسىمالداپ سىرتتان الۋ ارەكەتتەر [[{{ns:special}}:Log/import|سىرتتان الۋ جۋرنالىنا]] جازىلىپ الىنادى.',
'import-interwiki-history'   => 'وسى بەتتىڭ بارلىق تارىيحىي نۇسقالارىن كوشىرۋ',
'import-interwiki-submit'    => 'سىرتتان الۋ',
'import-interwiki-namespace' => 'مىنا ەسىم اياسىنا بەتتەردى تاسىمالداۋ:',
'importtext'                 => 'قاينار ۋىيكىيدەن «Special:Export» قۋرالىن قولدانىپ, فايلدى سىرتقا بەرىڭىز, دىيسكىڭىزگە ساقتاڭىز, سوسىن مىندا قوتارىڭىز.',
'importstart'                => 'بەتتەردى سىرتتان الۋى…',
'import-revision-count'      => '{{PLURAL:$1|1|$1}} نۇسقا',
'importnopages'              => 'سىرتتان الىناتىن بەتتەر جوق.',
'importfailed'               => 'سىرتتان الۋ ٴساتسىز ٴبىتتى: $1',
'importunknownsource'        => 'Cىرتتان الۋ قاينار ٴتۇرى تانىمالسىز',
'importcantopen'             => 'سىرتتان الۋ فايلى اشىلمايدى',
'importbadinterwiki'         => 'جارامسىز ۋىيكىي-ارالىق سىلتەمە',
'importnotext'               => 'بوستى, نە ٴماتىنى جوق',
'importsuccess'              => 'سىرتتان الۋى اياقتالدى!',
'importhistoryconflict'      => 'تارىيحىنىڭ ەگەس نۇسقالارى بار (بۇل بەتتى الدىندا سىرتتان الىنعان سىياقتى)',
'importnosources'            => 'ەشقانداي ۋىيكىي-تاسىمالداپ سىرتتان الۋ قاينارى بەلگىلەنمەگەن, جانە تارىيحىن تىكەلەي قوتارۋى وشىرىلگەن.',
'importnofile'               => 'سىرتتان الىناتىن فايل قوتارىلعان جوق.',
'importuploaderrorsize'      => 'سىرتتان الىناتىن فايلدىڭ قوتارۋى ٴساتسىز ٴوتتى. فايل مولشەرى قوتارۋعا رۋقسات ەتىلگەننەن ارتىق.',
'importuploaderrorpartial'   => 'سىرتتان الىناتىن فايلدىڭ قوتارۋى ٴساتسىز ٴوتتى. وسى فايلدىڭ تەك بولىكتەرى قوتارىلدى.',
'importuploaderrortemp'      => 'سىرتتان الىناتىن فايلدىڭ قوتارۋى ٴساتسىز ٴوتتى. ۋاقىتشا قالتا تابىلمادى.',
'import-parse-failure'       => 'سىرتتان العاندا XML وندەتۋى بۇزىلدى',
'import-noarticle'           => 'سىرتتان الاتىن ەش بەت جوق!',
'import-nonewrevisions'      => 'بارلىق نۇسقالارى الدىندا سىرتتان الىنعان.',
'xml-error-string'           => '$1 ٴنومىر $2 جولدا, باعان $3 (بايت $4): $5',

# Import log
'importlogpage'                    => 'سىرتتان الۋ جۋرنالى',
'importlogpagetext'                => 'باسقا ۋىيكىيلەردەن وڭدەۋ تارىيحىمەن بىرگە بەتتەردى اكىمشىلىك رەتىندە سىرتتان الۋ.',
'import-logentry-upload'           => 'فايل قوتارۋىمەن سىرتتان «[[$1]]» بەتى الىندى',
'import-logentry-upload-detail'    => '{{PLURAL:$1|1|$1}} نۇسقا',
'import-logentry-interwiki'        => 'ۋىيكىي اراسىنان تاسىمالدانعان $1',
'import-logentry-interwiki-detail' => '$2 دەگەننەن {{PLURAL:$1|1|$1}} نۇسقا',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'جەكە بەتىم',
'tooltip-pt-anonuserpage'         => 'وسى IP جايدىڭ جەكە بەتى',
'tooltip-pt-mytalk'               => 'تالقىلاۋ بەتىم',
'tooltip-pt-anontalk'             => 'وسى IP جاي تۇزەتۋلەرىن تالقىلاۋ',
'tooltip-pt-preferences'          => 'باپتاۋىم',
'tooltip-pt-watchlist'            => 'وزگەرىستەرىن باقىلاپ تۇرعان بەتتەر ٴتىزىمىم.',
'tooltip-pt-mycontris'            => 'ۇلەستەرىمدىڭ ٴتىزىمى',
'tooltip-pt-login'                => 'كىرۋىڭىزدى ۇسىنامىز, ول مىندەتتى ەمەس.',
'tooltip-pt-anonlogin'            => 'كىرۋىڭىزدى ۇسىنامىز, بىراق, ول مىندەتتى ەمەس.',
'tooltip-pt-logout'               => 'شىعۋ',
'tooltip-ca-talk'                 => 'ماعلۇمات بەتتى تالقىلاۋ',
'tooltip-ca-edit'                 => 'بۇل بەتتى وڭدەي الاسىز. ساقتاۋدىڭ الدىندا «قاراپ شىعۋ» باتىرماسىن نۇقىڭىز.',
'tooltip-ca-addsection'           => 'بۇل تالقىلاۋ بەتىندە جاڭا تاراۋ باستاۋ.',
'tooltip-ca-viewsource'           => 'بۇل بەت قورعالعان, بىراق, قاينارىن قاراۋعا بولادى.',
'tooltip-ca-history'              => 'بۇل بەتتىن جۋىقتاعى نۇسقالارى.',
'tooltip-ca-protect'              => 'بۇل بەتتى قورعاۋ',
'tooltip-ca-delete'               => 'بۇل بەتتى جويۋ',
'tooltip-ca-undelete'             => 'بۇل بەتتىڭ جويۋدىڭ الدىنداعى بولعان تۇزەتۋلەرىن قالپىنا كەلتىرۋ',
'tooltip-ca-move'                 => 'بۇل بەتتى جىلجىتۋ',
'tooltip-ca-watch'                => 'بۇل بەتتى باقىلاۋ تىزىمىڭىزگە ۇستەۋ',
'tooltip-ca-unwatch'              => 'بۇل بەتتى باقىلاۋ تىزىمىڭىزدەن الاستاۋ',
'tooltip-search'                  => '{{SITENAME}} جوباسىنان ىزدەستىرۋ',
'tooltip-search-go'               => 'ەگەر ٴدال وسى اتاۋىمەن بولسا بەتكە ٴوتىپ كەتۋ',
'tooltip-search-fulltext'         => 'وسى ٴماتىنى بار بەتتى ىزدەۋ',
'tooltip-p-logo'                  => 'باستى بەتكە',
'tooltip-n-mainpage'              => 'باستى بەتكە كەلىپ-كەتىڭىز',
'tooltip-n-portal'                => 'جوبا تۋرالى, نە ىستەۋىڭىزگە بولاتىن, قايدان تابۋعا بولاتىن تۋرالى',
'tooltip-n-currentevents'         => 'اعىمداعى وقىيعالارعا قاتىستى اقپارات',
'tooltip-n-recentchanges'         => 'وسى ۋىيكىيدەگى جۋىقتاعى وزگەرىستەر ٴتىزىمى.',
'tooltip-n-randompage'            => 'كەزدەيسوق بەتتى جۇكتەۋ',
'tooltip-n-help'                  => 'انىقتاما تابۋ ورنى.',
'tooltip-n-sitesupport'           => 'بىزگە جاردەم ەتىڭىز',
'tooltip-t-whatlinkshere'         => 'مىندا سىلتەگەن بارلىق بەتتەردىڭ ٴتىزىمى',
'tooltip-t-recentchangeslinked'   => 'مىننان سىلتەنگەن بەتتەردىڭ جۋىقتاعى وزگەرىستەرى',
'tooltip-feed-rss'                => 'بۇل بەتتىڭ RSS ارناسى',
'tooltip-feed-atom'               => 'بۇل بەتتىڭ Atom ارناسى',
'tooltip-t-contributions'         => 'وسى قاتىسۋشىنىڭ ۇلەس ٴتىزىمىن قاراۋ',
'tooltip-t-emailuser'             => 'وسى قاتىسۋشىعا email جىبەرۋ',
'tooltip-t-upload'                => 'سۋرەت نە تاسپا فايلدارىن قوتارۋ',
'tooltip-t-specialpages'          => 'بارلىق ارنايى بەتتەر ٴتىزىمى',
'tooltip-t-print'                 => 'بۇل بەتتىڭ باسىپ شىعارىشقا ارنالعان نۇسقاسى',
'tooltip-t-permalink'             => 'مىنا بەتتىڭ وسى نۇسقاسىنىڭ تۇراقتى سىلتەمەسى',
'tooltip-ca-nstab-main'           => 'ماعلۇمات بەتىن قاراۋ',
'tooltip-ca-nstab-user'           => 'قاتىسۋشى بەتىن قاراۋ',
'tooltip-ca-nstab-media'          => 'تاسپا بەتىن قاراۋ',
'tooltip-ca-nstab-special'        => 'بۇل ارنايى بەت, بەتتىڭ ٴوزى وڭدەلىنبەيدى.',
'tooltip-ca-nstab-project'        => 'جوبا بەتىن قاراۋ',
'tooltip-ca-nstab-image'          => 'سۋرەت بەتىن قاراۋ',
'tooltip-ca-nstab-mediawiki'      => 'جۇيە حابارىن قاراۋ',
'tooltip-ca-nstab-template'       => 'ۇلگىنى قاراۋ',
'tooltip-ca-nstab-help'           => 'انىقتىما بەتىن قاراۋ',
'tooltip-ca-nstab-category'       => 'سانات بەتىن قاراۋ',
'tooltip-minoredit'               => 'وسىنى شاعىن تۇزەتۋ دەپ بەلگىلەۋ',
'tooltip-save'                    => 'جاساعان وزگەرىستەرىڭىزدى ساقتاۋ',
'tooltip-preview'                 => 'ساقتاۋدىڭ الدىنان جاساعان وزگەرىستەرىڭىزدى قاراپ شىعىڭىز!',
'tooltip-diff'                    => 'ماتىنگە قانداي وزگەرىستەردى جاساعانىڭىزدى قاراۋ.',
'tooltip-compareselectedversions' => 'بەتتىڭ ەكى بولەكتەنگەن نۇسقاسى ايىرماسىن قاراۋ.',
'tooltip-watch'                   => 'بۇل بەتتى باقىلاۋ تىزىمىڭىزگە ۇستەۋ',
'tooltip-recreate'                => 'بەت جويىلعانىنا قاراماستان قايتا باستاۋ',
'tooltip-upload'                  => 'قوتارۋدى باستاۋ',

# Metadata
'nodublincore'      => 'بۇل سەرۆەردە «Dublin Core RDF» ٴتۇرى قوسىمشا دەرەكتەرى وشىرىلگەن.',
'nocreativecommons' => 'بۇل سەرۆەردە «Creative Commons RDF» ٴتۇرى قوسىمشا دەرەكتەرى وشىرىلگەن.',
'notacceptable'     => 'تۇتىنعىشىڭىز وقىپ الۋ ٴۇشىن پىشىمدەلىنگەن دەرەكتەردى وسى ۋىيكىي سەرۆەر جەتىستىرە المايدى.',

# Attribution
'anonymous'        => '{{SITENAME}} تىركەلگىسىز قاتىسۋشى(لارى)',
'siteuser'         => '{{SITENAME}} قاتىسۋشى $1',
'lastmodifiedatby' => 'بۇل بەتتى $3 قاتىسۋشى سوڭعى وزگەرتكەن كەزى: $2, $1.', # $1 date, $2 time, $3 user
'othercontribs'    => 'شىعارما نەگىزىن $1 جازعان.',
'others'           => 'باسقالار',
'siteusers'        => '{{SITENAME}} قاتىسۋشى(لار) $1',
'creditspage'      => 'بەتتى جازعاندار',
'nocredits'        => 'بۇل بەتتى جازعاندار تۋرالى اقپارات جوق.',

# Spam protection
'spamprotectiontitle' => '«سپام»-نان قورعايتىن سۇزگى',
'spamprotectiontext'  => 'بۇل بەتتىڭ ساقتاۋىن «سپام» سۇزگىسى بۇعاتتادى. بۇنىڭ سەبەبى سىرتقى توراپ سىلتەمەسىنەن بولۋى مۇمكىن.',
'spamprotectionmatch' => 'كەلەسى «سپام» ٴماتىنى سۇزگىلەنگەن: $1',
'spam_reverting'      => '$1 دەگەنگە سىلتەمەسى جوق سوڭعى نۇسقاسىنا قايتارىلدى',
'spam_blanking'       => '$1 دەگەنگە سىلتەمەسى بار بارلىق نۇسقالار تازارتىلدى',

# Info page
'infosubtitle'   => 'بەت تۋرالى مالىمەت',
'numedits'       => 'تۇزەتۋ سانى (بەت): $1',
'numtalkedits'   => 'تۇزەتۋ سانى (تالقىلاۋ بەتى): $1',
'numwatchers'    => 'باقىلاۋشى سانى: $1',
'numauthors'     => 'ٴارتۇرلى اۋتورلار سانى (بەت): $1',
'numtalkauthors' => 'ٴارتۇرلى اۋتور سانى (تالقىلاۋ بەتى): $1',

# Math options
'mw_math_png'    => 'ارقاشان PNG كورسەتكىز',
'mw_math_simple' => 'ەگەر وتە قاراپايىم بولسا — HTML, ايتپەسە PNG',
'mw_math_html'   => 'ەگەر ىقتىيمال بولسا — HTML, ايتپەسە PNG',
'mw_math_source' => 'بۇنى TeX پىشىمىندە قالدىر (ماتىندىك شولعىشتارعا)',
'mw_math_modern' => 'وسى زامانعى شولعىشتارىنا ۇسىنىلادى',
'mw_math_mathml' => 'ەگەر ىقتىيمال بولسا — MathML (سىناقتاما)',

# Patrolling
'markaspatrolleddiff'                 => 'كۇزەتتە دەپ بەلگىلەۋ',
'markaspatrolledtext'                 => 'وسى بەتتى كۇزەتۋدە دەپ بەلگىلەۋ',
'markedaspatrolled'                   => 'كۇزەتتە دەپ بەلگىلەندى',
'markedaspatrolledtext'               => 'تالعانعان نۇسقا كۇزەتتە دەپ بەلگىلەندى.',
'rcpatroldisabled'                    => 'جۋىقتاعى وزگەرىستەر كۇزەتى وشىرىلگەن',
'rcpatroldisabledtext'                => 'جۋىقتاعى وزگەرىستەر كۇزەتى مۇمكىندىگى اعىمدا وشىرىلگەن.',
'markedaspatrollederror'              => 'كۇزەتتە دەپ بەلگىلەنبەيدى',
'markedaspatrollederrortext'          => 'كۇزەتتە دەپ بەلگىلەۋ ٴۇشىن نۇسقاسىن ەنگىزىڭىز.',
'markedaspatrollederror-noautopatrol' => 'ٴوزىڭىز جاساعان وزگەرىستەرىڭىزدى كۇزەتكە قويا المايسىز.',

# Patrol log
'patrol-log-page' => 'كۇزەت جۋرنالى',
'patrol-log-line' => 'كۇزەتتەگى $2 دەگەننىڭ $1 نۇسقاسىن بەلگىلەدى $3',
'patrol-log-auto' => '(وزدىكتىك)',
'patrol-log-diff' => 'ٴنومىر $1',

# Image deletion
'deletedrevision'                 => 'مىنا ەسكى نۇسقاسىن جويدى: $1',
'filedeleteerror-short'           => 'فايل جويۋ قاتەسى: $1',
'filedeleteerror-long'            => 'مىنا فايلدى جويعاندا قاتەلەر كەزدەستى:

$1',
'filedelete-missing'              => '«$1» فايلى جويىلمايدى, سەبەبى ول جوق.',
'filedelete-old-unregistered'     => 'فايلدىن كەلتىرىلگەن «$1» نۇسقاسى دەرەكقوردا جوق.',
'filedelete-current-unregistered' => 'كەلتىرىلگەن «$1» فايل دەرەكقوردا جوق.',
'filedelete-archive-read-only'    => '«$1» دەگەن مۇراعات قالتاسىنا ۆەبسەرۆەر جازا المايدى.',

# Browsing diffs
'previousdiff' => '← الدىڭعى ايىرم.',
'nextdiff'     => 'كەلەسى ايىرم. →',

# Media information
'mediawarning'         => "'''قۇلاقتاندىرۋ''': بۇل فايل تۇرىندە قاسكۇنەمدى امىرلەردىڭ بار بولۋى ىقتىيمال; بۇنى جەگىپ جۇيەڭىزگە زىيان كەلتىرۋىڭىز مۇمكىن.<hr />",
'imagemaxsize'         => 'سىيپاتتاماسى بەتىندەگى سۋرەتتىڭ مولشەرىن شەكتەۋى:',
'thumbsize'            => 'نوباي مولشەرى:',
'widthheight'          => '$1 × $2',
'widthheightpage'      => '$1 × $2, $3 بەت',
'file-info'            => 'فايل مولشەرى: $1, MIME ٴتۇرى: $2',
'file-info-size'       => '($1 × $2 پىيكسەل, فايل مولشەرى: $3, MIME ٴتۇرى: $4)',
'file-nohires'         => '<small>جوعارى اجىراتىلىمدىعى جەتىمسىز.</small>',
'svg-long-desc'        => '(SVG فايلى, كەسىمدى $1 × $2 پىيكسەل, فايل مولشەرى: $3)',
'show-big-image'       => 'جوعارى اجىراتىلىمدى',
'show-big-image-thumb' => '<small>قاراپ شىعۋ مولشەرى: $1 × $2 پىيكسەل</small>',

# Special:Newimages
'newimages'             => 'ەڭ جاڭا فايلدار قويماسى',
'imagelisttext'         => "تومەندە $2 سۇرىپتالعان {{PLURAL:$1|'''1'''|'''$1'''}} فايل ٴتىزىمى.",
'showhidebots'          => '(بوتتاردى $1)',
'noimages'              => 'كورەتىن ەشتەڭە جوق.',
'ilsubmit'              => 'ىزدە',
'bydate'                => 'كۇن-ايىمەن',
'sp-newimages-showfrom' => '$1 كەزىنەن بەرى — جاڭا سۋرەتتەردى كورسەت',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2 × $3',
'seconds-abbrev' => 'س',
'minutes-abbrev' => 'مىين',
'hours-abbrev'   => 'ساع',

# Bad image list
'bad_image_list' => 'ٴپىشىمى تومەندەگىدەي:

تەك ٴتىزىم دانالارى (* نىشانىمەن باستالىتىن جولدار) ەسەپتەلەدى. جولدىڭ ٴبىرىنشى سىلتەمەسى جارامسىز سۋرەتكە سىلتەۋ كەرەك.
سول جولداعى كەيىنگى ٴاربىر سىلتەمەلەر ەرەن بولىپ ەسەپتەلەدى, مىسالى جول ىشىندەگى كەزدەسەتىن سۋرەتى بار بەتتەر.',

# Metadata
'metadata'          => 'قوسىمشا مالىمەتتەر',
'metadata-help'     => 'وسى فايلدا قوسىمشا مالىمەتتەر بار. بالكىم, وسى مالىمەتتەر فايلدى جاساپ شىعارۋ, نە ساندىلاۋ ٴۇشىن پايدالانعان ساندىق كامەرا, نە ماتىنالعىردان الىنعان. ەگەر وسى فايل نەگىزگى كۇيىنەن وزگەرتىلگەن بولسا, كەيبىر ەجەلەلەرى وزگەرتىلگەن فوتوسۋرەتكە لايىق بولماس.',
'metadata-expand'   => 'ەگجەي-تەگجەيىن كورسەت',
'metadata-collapse' => 'ەگجەي-تەگجەيىن جاسىر',
'metadata-fields'   => 'وسى حاباردا تىزىمدەلگەن EXIF قوسىمشا مالىمەتتەر اۋماقتارى,
سۋرەت بەتى كورسەتۋ كەزىندە قوسىمشا مالىمەتتەر كەستە جاسىرىلىعاندا كىرىستىرلەدى.
باسقالارى ادەپكىدەن جاسىرىلادى.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'ەنى',
'exif-imagelength'                 => 'بىيىكتىگى',
'exif-bitspersample'               => 'قۇراش سايىن بىيت سانى',
'exif-compression'                 => 'قىسىم سۇلباسى',
'exif-photometricinterpretation'   => 'پىيكسەل قىيىسۋى',
'exif-orientation'                 => 'مەگزەۋى',
'exif-samplesperpixel'             => 'قۇراش سانى',
'exif-planarconfiguration'         => 'دەرەك رەتتەۋى',
'exif-ycbcrsubsampling'            => 'Y قۇراشىنىڭ C قۇراشىنا جارناقتاۋى',
'exif-ycbcrpositioning'            => 'Y قۇراشى جانە C قۇراشى مەكەندەۋى',
'exif-xresolution'                 => 'دەرەلەي اجىراتىلىمدىعى',
'exif-yresolution'                 => 'تىرەلەي اجىراتىلىمدىعى',
'exif-resolutionunit'              => 'X جانە Y اجىراتىلىمدىقتارىعىنىڭ ولشەمى',
'exif-stripoffsets'                => 'سۋرەت دەرەرەكتەرىنىڭ جايعاسۋى',
'exif-rowsperstrip'                => 'بەلدىك سايىن جول سانى',
'exif-stripbytecounts'             => 'قىسىمدالعان بەلدىك سايىن بايت سانى',
'exif-jpeginterchangeformat'       => 'JPEG SOI دەگەنگە ىعىسۋى',
'exif-jpeginterchangeformatlength' => 'JPEG دەرەكتەرىنىڭ بايت سانى',
'exif-transferfunction'            => 'تاسىمالداۋ فۋنكتسىياسى',
'exif-whitepoint'                  => 'اق نۇكتە تۇستىلىگى',
'exif-primarychromaticities'       => 'العى شەپتەگى تۇستىلىكتەرى',
'exif-ycbcrcoefficients'           => 'ٴتۇس اياسىن تاسىمالداۋ ماترىيتسالىق ەسەلىكتەرى',
'exif-referenceblackwhite'         => 'قارا جانە اق انىقتاۋىش قوس كولەمدەرى',
'exif-datetime'                    => 'فايلدىڭ وزگەرتىلگەن كۇن-ايى',
'exif-imagedescription'            => 'سۋرەت اتاۋى',
'exif-make'                        => 'كامەرا ٴوندىرۋشىسى',
'exif-model'                       => 'كامەرا ۇلگىسى',
'exif-software'                    => 'قولدانىلعان باعدارلاما',
'exif-artist'                      => 'جىعارماشىسى',
'exif-copyright'                   => 'جىعارماشىلىق قۇقىقتار ىيەسى',
'exif-exifversion'                 => 'Exif نۇسقاسى',
'exif-flashpixversion'             => 'سۇيەمدەلىنگەن Flashpix نۇسقاسى',
'exif-colorspace'                  => 'ٴتۇس اياسى',
'exif-componentsconfiguration'     => 'ارقايسى قۇراش ٴمانى',
'exif-compressedbitsperpixel'      => 'سۋرەت قىسىمداۋ ٴتارتىبى',
'exif-pixelydimension'             => 'سۋرەتتىڭ جارامدى ەنى',
'exif-pixelxdimension'             => 'سۋرەتتىڭ جارامدى بىيىكتىگى',
'exif-makernote'                   => 'ٴوندىرۋشىنىڭ اڭعارتپالارى',
'exif-usercomment'                 => 'قاتىسۋشىنىڭ ماندەمەلەرى',
'exif-relatedsoundfile'            => 'قاتىستى دىبىس فايلى',
'exif-datetimeoriginal'            => 'جاسالعان كەزى',
'exif-datetimedigitized'           => 'ساندىقتاۋ كەزى',
'exif-subsectime'                  => 'جاسالعان كەزىنىڭ سەكۋند بولشەكتەرى',
'exif-subsectimeoriginal'          => 'تۇپنۇسقا كەزىنىڭ سەكۋند بولشەكتەرى',
'exif-subsectimedigitized'         => 'ساندىقتاۋ كەزىنىڭ سەكۋند بولشەكتەرى',
'exif-exposuretime'                => 'ۇستالىم ۋاقىتى',
'exif-exposuretime-format'         => '$1 س ($2)',
'exif-fnumber'                     => 'ساڭىلاۋ مولشەرى',
'exif-exposureprogram'             => 'ۇستالىم باعدارلاماسى',
'exif-spectralsensitivity'         => 'سپەكتر بويىنشا سەزگىشتىگى',
'exif-isospeedratings'             => 'ISO جىلدامدىق جارناقتاۋى (جارىق سەزگىشتىگى)',
'exif-oecf'                        => 'وپتويەلەكتروندى تۇرلەتۋ ىقپالى',
'exif-shutterspeedvalue'           => 'جاپقىش جىلدامدىلىعى',
'exif-aperturevalue'               => 'ساڭىلاۋلىق',
'exif-brightnessvalue'             => 'اشىقتىق',
'exif-exposurebiasvalue'           => 'ۇستالىم وتەمى',
'exif-maxaperturevalue'            => 'بارىنشا ساڭىلاۋ اشۋى',
'exif-subjectdistance'             => 'نىسانا قاشىقتىعى',
'exif-meteringmode'                => 'ولشەۋ ٴتارتىبى',
'exif-lightsource'                 => 'جارىق كوزى',
'exif-flash'                       => 'جارقىلداعىش',
'exif-focallength'                 => 'شوعىرلاۋ الشاقتىعى',
'exif-subjectarea'                 => 'نىسانا اۋقىمى',
'exif-flashenergy'                 => 'جارقىلداعىش قارقىنى',
'exif-spatialfrequencyresponse'    => 'كەڭىستىك-جىيىلىك اسەرشىلىگى',
'exif-focalplanexresolution'       => 'ح بويىنشا شوعىرلاۋ جايپاقتىقتىڭ اجىراتىلىمدىعى',
'exif-focalplaneyresolution'       => 'Y بويىنشا شوعىرلاۋ جايپاقتىقتىڭ اجىراتىلىمدىعى',
'exif-focalplaneresolutionunit'    => 'شوعىرلاۋ جايپاقتىقتىڭ اجىراتىلىمدىق ولشەمى',
'exif-subjectlocation'             => 'نىسانا مەكەندەۋى',
'exif-exposureindex'               => 'ۇستالىم ايقىنداۋى',
'exif-sensingmethod'               => 'سەنسوردىڭ ولشەۋ ٴادىسى',
'exif-filesource'                  => 'فايل قاينارى',
'exif-scenetype'                   => 'ساحنا ٴتۇرى',
'exif-cfapattern'                  => 'CFA سۇزگى كەيىپى',
'exif-customrendered'              => 'قوسىمشا سۋرەت وڭدەتۋى',
'exif-exposuremode'                => 'ۇستالىم ٴتارتىبى',
'exif-whitebalance'                => 'اق ٴتۇسىنىڭ تەندەستىگى',
'exif-digitalzoomratio'            => 'ساندىق اۋقىمداۋ جارناقتاۋى',
'exif-focallengthin35mmfilm'       => '35 mm تاسپاسىنىڭ شوعىرلاۋ الشاقتىعى',
'exif-scenecapturetype'            => 'تۇسىرگەن ساحنا ٴتۇرى',
'exif-gaincontrol'                 => 'ساحنانى مەڭگەرۋ',
'exif-contrast'                    => 'قاراما-قارسىلىق',
'exif-saturation'                  => 'قانىقتىق',
'exif-sharpness'                   => 'ايقىندىق',
'exif-devicesettingdescription'    => 'جابدىق باپتاۋ سىيپاتتاماسى',
'exif-subjectdistancerange'        => 'ساحنا قاشىقتىعىنىڭ كولەمى',
'exif-imageuniqueid'               => 'سۋرەتتىڭ بىرەگەي ٴنومىرى (ID)',
'exif-gpsversionid'                => 'GPS بەلگىشەسىنىڭ نۇسقاسى',
'exif-gpslatituderef'              => 'سولتۇستىك نەمەسە وڭتۇستىك بويلىعى',
'exif-gpslatitude'                 => 'بويلىعى',
'exif-gpslongituderef'             => 'شىعىس نەمەسە باتىس ەندىگى',
'exif-gpslongitude'                => 'ەندىگى',
'exif-gpsaltituderef'              => 'بىيىكتىك كورسەتۋى',
'exif-gpsaltitude'                 => 'بىيىكتىك',
'exif-gpstimestamp'                => 'GPS ۋاقىتى (اتوم ساعاتى)',
'exif-gpssatellites'               => 'ولشەۋگە پيدالانىلعان جەر سەرىكتەرى',
'exif-gpsstatus'                   => 'قابىلداعىش كۇيى',
'exif-gpsmeasuremode'              => 'ولشەۋ ٴتارتىبى',
'exif-gpsdop'                      => 'ولشەۋ دالدىگى',
'exif-gpsspeedref'                 => 'جىلدامدىلىق ولشەمى',
'exif-gpsspeed'                    => 'GPS قابىلداعىشتىڭ جىلدامدىلىعى',
'exif-gpstrackref'                 => 'قوزعالىس باعىتىن كورسەتۋى',
'exif-gpstrack'                    => 'قوزعالىس باعىتى',
'exif-gpsimgdirectionref'          => 'سۋرەت باعىتىن كورسەتۋى',
'exif-gpsimgdirection'             => 'سۋرەت باعىتى',
'exif-gpsmapdatum'                 => 'پايدالانىلعان گەودەزىيالىق تۇسىرمە دەرەكتەرى',
'exif-gpsdestlatituderef'          => 'نىسانا بويلىعىن كورسەتۋى',
'exif-gpsdestlatitude'             => 'نىسانا بويلىعى',
'exif-gpsdestlongituderef'         => 'نىسانا ەندىگىن كورسەتۋى',
'exif-gpsdestlongitude'            => 'نىسانا ەندىگى',
'exif-gpsdestbearingref'           => 'نىسانا ازىيمۋتىن كورسەتۋى',
'exif-gpsdestbearing'              => 'نىسانا ازىيمۋتى',
'exif-gpsdestdistanceref'          => 'نىسانا قاشىقتىعىن كورسەتۋى',
'exif-gpsdestdistance'             => 'نىسانا قاشىقتىعى',
'exif-gpsprocessingmethod'         => 'GPS وڭدەتۋ ٴادىسىنىڭ اتاۋى',
'exif-gpsareainformation'          => 'GPS اۋماعىنىڭ اتاۋى',
'exif-gpsdatestamp'                => 'GPS كۇن-ايى',
'exif-gpsdifferential'             => 'GPS سارالانعان دۇرىستاۋ',

# EXIF attributes
'exif-compression-1' => 'ۇلعايتىلعان',

'exif-unknowndate' => 'بەلگىسىز كۇن-ايى',

'exif-orientation-1' => 'قالىپتى', # 0th row: top; 0th column: left
'exif-orientation-2' => 'دەرەلەي شاعىلىسقان', # 0th row: top; 0th column: right
'exif-orientation-3' => '180° بۇرىشقا اينالعان', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'تىرەلەي شاعىلىسقان', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'ساعات تىلشەسىنە قارسى 90° بۇرىشقا اينالعان جانە تىرەلەي شاعىلىسقان', # 0th row: left; 0th column: top
'exif-orientation-6' => 'ساعات تىلشە بويىنشا 90° بۇرىشقا اينالعان', # 0th row: right; 0th column: top
'exif-orientation-7' => 'ساعات تىلشە بويىنشا 90° بۇرىشقا اينالعان جانە تىرەلەي شاعىلىسقان', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'ساعات تىلشەسىنە قارسى 90° بۇرىشقا اينالعان', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'تالپاق ٴپىشىم',
'exif-planarconfiguration-2' => 'تايپاق ٴپىشىم',

'exif-componentsconfiguration-0' => 'بار بولمادى',

'exif-exposureprogram-0' => 'انىقتالماعان',
'exif-exposureprogram-1' => 'قولمەن',
'exif-exposureprogram-2' => 'باعدارلامالى ٴادىس (قالىپتى)',
'exif-exposureprogram-3' => 'ساڭىلاۋ باسىڭقىلىعى',
'exif-exposureprogram-4' => 'ىسىرما باسىڭقىلىعى',
'exif-exposureprogram-5' => 'ونەر باعدارلاماسى (انىقتىق تەرەندىگىنە ساناسقان)',
'exif-exposureprogram-6' => 'قىيمىل باعدارلاماسى (جاپقىش شاپشاندىلىعىنا ساناسقان)',
'exif-exposureprogram-7' => 'تىرەلەي ٴادىسى (ارتى شوعىرلاۋسىز تاياۋ تۇسىرمەلەر)',
'exif-exposureprogram-8' => 'دەرەلەي ٴادىسى (ارتى شوعىرلانعان دەرەلەي تۇسىرمەلەر)',

'exif-subjectdistance-value' => '$1 m',

'exif-meteringmode-0'   => 'بەلگىسىز',
'exif-meteringmode-1'   => 'بىركەلكى',
'exif-meteringmode-2'   => 'بۇلدىر داق',
'exif-meteringmode-3'   => 'بىرداقتى',
'exif-meteringmode-4'   => 'كوپداقتى',
'exif-meteringmode-5'   => 'ورنەكتى',
'exif-meteringmode-6'   => 'جىرتىندى',
'exif-meteringmode-255' => 'باسقا',

'exif-lightsource-0'   => 'بەلگىسىز',
'exif-lightsource-1'   => 'كۇن جارىعى',
'exif-lightsource-2'   => 'كۇنجارىقتى شام',
'exif-lightsource-3'   => 'قىزدىرعىشتى شام',
'exif-lightsource-4'   => 'جارقىلداعىش',
'exif-lightsource-9'   => 'اشىق كۇن',
'exif-lightsource-10'  => 'بۇلىنعىر كۇن',
'exif-lightsource-11'  => 'كولەنكەلى',
'exif-lightsource-12'  => 'كۇنجارىقتى شام (D 5700–7100 K)',
'exif-lightsource-13'  => 'كۇنجارىقتى شام (N 4600–5400 K)',
'exif-lightsource-14'  => 'كۇنجارىقتى شام (W 3900–4500 K)',
'exif-lightsource-15'  => 'كۇنجارىقتى شام (WW 3200–3700 K)',
'exif-lightsource-17'  => 'قالىپتى جارىق قاينارى A',
'exif-lightsource-18'  => 'قالىپتى جارىق قاينارى B',
'exif-lightsource-19'  => 'قالىپتى جارىق قاينارى C',
'exif-lightsource-24'  => 'ستۋدىيالىق ISO كۇنجارىقتى شام',
'exif-lightsource-255' => 'باسقا جارىق قاينارى',

'exif-focalplaneresolutionunit-2' => 'ديۋيم',

'exif-sensingmethod-1' => 'انىقتالماعان',
'exif-sensingmethod-2' => '1-ٴتشىيپتى اۋماقتى تۇسسەزگىش',
'exif-sensingmethod-3' => '2-ٴتشىيپتى اۋماقتى تۇسسەزگىش',
'exif-sensingmethod-4' => '3-ٴتشىيپتى اۋماقتى تۇسسەزگىش',
'exif-sensingmethod-5' => 'كەزەكتى اۋماقتى تۇسسەزگىش',
'exif-sensingmethod-7' => '3-سىزىقتى تۇسسەزگىش',
'exif-sensingmethod-8' => 'كەزەكتى سىزىقتى تۇسسەزگىش',

'exif-scenetype-1' => 'تىكەلەي تۇسىرىلگەن فوتوسۋرەت',

'exif-customrendered-0' => 'قالىپتى وڭدەتۋ',
'exif-customrendered-1' => 'قوسىمشا وڭدەتۋ',

'exif-exposuremode-0' => 'وزدىكتىك ۇستالىمداۋ',
'exif-exposuremode-1' => 'قولمەن ۇستالىمداۋ',
'exif-exposuremode-2' => 'وزدىكتىك جارقىلداۋ',

'exif-whitebalance-0' => 'اق ٴتۇسى وزدىكتىك تەندەستىرىلگەن',
'exif-whitebalance-1' => 'اق ٴتۇسى قولمەن تەندەستىرىلگەن',

'exif-scenecapturetype-0' => 'قالىپتى',
'exif-scenecapturetype-1' => 'دەرەلەي',
'exif-scenecapturetype-2' => 'تىرەلەي',
'exif-scenecapturetype-3' => 'تۇنگى ساحنا',

'exif-gaincontrol-0' => 'جوق',
'exif-gaincontrol-1' => 'تومەن زورايۋ',
'exif-gaincontrol-2' => 'جوعارى زورايۋ',
'exif-gaincontrol-3' => 'تومەن باياۋلاۋ',
'exif-gaincontrol-4' => 'جوعارى باياۋلاۋ',

'exif-contrast-0' => 'قالىپتى',
'exif-contrast-1' => 'ۇيان',
'exif-contrast-2' => 'تۇرپايى',

'exif-saturation-0' => 'قالىپتى',
'exif-saturation-1' => 'تومەن قانىقتى',
'exif-saturation-2' => 'جوعارى قانىقتى',

'exif-sharpness-0' => 'قالىپتى',
'exif-sharpness-1' => 'ۇيان',
'exif-sharpness-2' => 'تۇرپايى',

'exif-subjectdistancerange-0' => 'بەلگىسىز',
'exif-subjectdistancerange-1' => 'تاياۋ تۇسىرىلگەن',
'exif-subjectdistancerange-2' => 'جاقىن تۇسىرىلگەن',
'exif-subjectdistancerange-3' => 'الىس تۇسىرىلگەن',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'سولتۇستىك بويلىعى',
'exif-gpslatitude-s' => 'وڭتۇستىك بويلىعى',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'شىعىس ەندىگى',
'exif-gpslongitude-w' => 'باتىس ەندىگى',

'exif-gpsstatus-a' => 'ولشەۋ ۇلاسۋدا',
'exif-gpsstatus-v' => 'ولشەۋ ٴوزارا ارەكەتتە',

'exif-gpsmeasuremode-2' => '2-باعىتتىق ولشەم',
'exif-gpsmeasuremode-3' => '3-باعىتتىق ولشەم',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mil/h',
'exif-gpsspeed-n' => 'knot',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'شىن باعىت',
'exif-gpsdirection-m' => 'ماگنىيتتى باعىت',

# External editor support
'edit-externally'      => 'بۇل فايلدى سىرتقى قۇرال/باعدارلاما ارقىلى وڭدەۋ',
'edit-externally-help' => 'كوبىرەك اقپارات ٴۇشىن [http://meta.wikimedia.org/wiki/Help:External_editors ورناتۋ نۇسقاۋلارىن] قاراڭىز.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'بارلىعىن',
'imagelistall'     => 'بارلىعى',
'watchlistall2'    => 'بارلىق',
'namespacesall'    => 'بارلىعى',
'monthsall'        => 'بارلىعى',

# E-mail address confirmation
'confirmemail'            => 'ە-پوشتا جايىن قۇپتاۋ',
'confirmemail_noemail'    => '[[{{ns:special}}:Preferences|قاتىسۋشى باپتاۋىڭىزدا]] جارامدى ە-پوشتا جايىن قويماپسىز.',
'confirmemail_text'       => '{{SITENAME}} ە-پوشتا مۇمكىندىكتەرىن پايدالانۋ ٴۇشىن الدىنان ە-پوشتا جايىڭىزدىڭ
جارامدىلىعىن تەكسەرىپ شىعۋىڭىز كەرەك. ٴوزىڭىزدىڭ جايىڭىزعا قۇپتاۋ حاتىن جىبەرۋ ٴۇشىن تومەندەگى باتىرمانى نۇقىڭىز.
حاتتىڭ ىشىندە ارنايى كودى بار سىلتەمە كىرىستىرلەدى; ە-پوشتا جايىڭىزدىڭ جارامدىلىعىن قۇپتاۋ ٴۇشىن
سىلتەمەنى شولعىشتىڭ مەكەن-جاي جولاعىنا ەنگىزىپ اشىڭىز.',
'confirmemail_pending'    => '<div class="error">
قۇپتاۋ بەلگىلەمەڭىز الداقاشان حاتپەن جىبەرىلىپتى; ەگەر جۋىقتا
تىركەلسەڭىز, جاڭا بەلگىلەمەنى سۇراتۋ الدىنان 
حات كەلۋىن ٴبىرشاما ٴمىينوت كۇتە تۇرىڭىز.
</div>',
'confirmemail_send'       => 'قۇپتاۋ بەلگىلەمەسىن جىبەرۋ',
'confirmemail_sent'       => 'قۇپتاۋ حاتى جىبەرىلدى.',
'confirmemail_oncreate'   => 'قۇپتاۋ بەلگىلەمەسى ە-پوشتا ادرەسىڭىزگە جىبەرىلدى.
بۇل بەلگىلەمە كىرۋ ۇدىرىسىنە كەرەگى جوق, بىراق ە-پوشتا نەگىزىندەگى
ۋىيكىي مۇمكىندىكتەردى قوسۋ ٴۇشىن بۇنى جەتىستىرۋىڭىز كەرەك.',
'confirmemail_sendfailed' => 'قۇپتاۋ حاتى جىبەرىلمەدى. جايدى جارامسىز ارىپتەرىنە تەكسەرىپ شىعىڭىز.

پوشتا جىبەرگىشتىڭ قايتارعانى: $1',
'confirmemail_invalid'    => 'قۇپتاۋ بەلگىلەمەسى جارامسىز. بەلگىلەمەنىڭ مەرزىمى بىتكەن شىعار.',
'confirmemail_needlogin'  => 'ە-پوشتا جايىڭىزدى قۇپتاۋ ٴۇشىن $1 كەرەك.',
'confirmemail_success'    => 'ە-پوشتا جايىڭىز قۇپتالدى. ەندى ۋىيكىيگە كىرىپ جۇمىسقا كىرىسۋگە بولادى',
'confirmemail_loggedin'   => 'ە-پوشتا جايىڭىز ەندى قۇپتالدى.',
'confirmemail_error'      => 'قۇپتاۋڭىزدى ساقتاعاندا بەلگىسىز قاتە بولدى.',
'confirmemail_subject'    => '{{SITENAME}} تورابىنان ە-پوشتا جايىڭىزدى قۇپتاۋ حاتى',
'confirmemail_body'       => "كەيبىرەۋ, $1 دەگەن IP جايىنان, ٴوزىڭىز بولۋى مۇمكىن,
{{SITENAME}} جوباسىندا بۇل ە-پوشتا جايىن قولدانىپ «$2» دەگەن تىركەلگى جاساپتى.

وسى تىركەلگى شىنىنان سىزدىكى ەكەنىن قۇپتاۋ ٴۇشىن, جانە {{SITENAME}} جوباسىنىڭ
ە-پوشتا مۇمكىندىكتەرىن بەلسەندىرۋ ٴۇشىن, مىنا سىلتەمەنى شولعىشپەن اشىڭىز:

$3

بۇل سىزدىكى '''ەمەس''' بولسا, سىلتەمەگە ەرمەڭىز. قۇپتاۋ بەلگىلەمەسىنىڭ
مەرزىمى $4 كەزىندە بىتەدى.",

# Scary transclusion
'scarytranscludedisabled' => '[ۋىيكىي-ارا كىرەگۋى وشىرىلگەن]',
'scarytranscludefailed'   => '[$1 ٴۇشىن ۇلگى كەلتىرۋى ٴساتسىز ٴبىتتى; عافۋ ەتىڭىز]',
'scarytranscludetoolong'  => '[URL جايى تىم ۇزىن; عافۋ ەتىڭىز]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
بۇل بەتتىڭ اڭىستاۋلارى:<br />
$1
</div>',
'trackbackremove'   => '([$1 جويۋ])',
'trackbacklink'     => 'اڭىستاۋ',
'trackbackdeleteok' => 'اڭىستاۋ ٴساتتى جويىلدى.',

# Delete conflict
'deletedwhileediting' => 'قۇلاقتاندىرۋ: بۇل بەتتى وڭدەۋىڭىزدى باستاعاندا, وسى بەت جويىلدى!',
'confirmrecreate'     => "بۇل بەتتى وڭدەۋىڭىزدى باستاعاندا [[User:$1|$1]] ([[User_talk:$1|تالقىلاۋى]]) وسى بەتتى جويدى, كەلتىرگەن سەبەبى:
: ''$2''
وسى بەتتى شىنىنان قايتا باستاۋىن قۇپتاڭىز.",
'recreate'            => 'قايتا باستاۋ',

'unit-pixel' => ' px',

# HTML dump
'redirectingto' => '[[$1]] بەتىنە ايداتۋدا…',

# action=purge
'confirm_purge'        => 'قوسالقى قالتاداعى وسى بەتىن تازالايمىز با?

$1',
'confirm_purge_button' => 'جارايدى',

# AJAX search
'searchcontaining' => "''$1'' ماعلۇماتى بار بەتتەردەن ىزدەۋ.",
'searchnamed'      => "''$1'' اتاۋى بار بەتتەردەن ىزدەۋ.",
'articletitles'    => "''$1'' دەپ باستالعان بەتتەردى",
'hideresults'      => 'ناتىيجەلەردى جاسىر',
'useajaxsearch'    => 'AJAX قولدانىپ ىزدەۋ',

# Separators for various lists, etc.
'semicolon-separator' => '؛',
'comma-separator'     => '،&#32;',

# Multipage image navigation
'imgmultipageprev' => '← الدىڭعى بەتكە',
'imgmultipagenext' => 'كەلەسى بەتكە →',
'imgmultigo'       => 'ٴوت!',
'imgmultigotopre'  => 'مىنا بەتكە ٴوتۋ',

# Table pager
'ascending_abbrev'         => 'ٴوسۋ',
'descending_abbrev'        => 'كەمۋ',
'table_pager_next'         => 'كەلەسى بەتكە',
'table_pager_prev'         => 'الدىڭعى بەتكە',
'table_pager_first'        => 'العاشقى بەتكە',
'table_pager_last'         => 'سوڭعى بەتكە',
'table_pager_limit'        => 'بەت سايىن $1 دانا كورسەت',
'table_pager_limit_submit' => 'ٴوتۋ',
'table_pager_empty'        => 'ەش ناتىيجە جوق',

# Auto-summaries
'autosumm-blank'   => 'بەتتىڭ بارلىق ماعلۇماتىن الاستاتتى',
'autosumm-replace' => "بەتتى '$1' دەگەنمەن الماستىردى",
'autoredircomment' => '[[$1]] دەگەنگە ايدادى',
'autosumm-new'     => 'جاڭا بەتتە: $1',

# Size units
'size-bytes' => '$1 بايت',

# Live preview
'livepreview-loading' => 'جۇكتەۋدە…',
'livepreview-ready'   => 'جۇكتەۋدە… دايىن!',
'livepreview-failed'  => 'تۋرا قاراپ شىعۋ ٴساتسىز! كادىمگى قاراپ شىعۋ ٴادىسىن بايقاپ كورىڭىز.',
'livepreview-error'   => 'قوسىلۋ ٴساتسىز: $1 «$2». كادىمگى قاراپ شىعۋ ٴادىسىن بايقاپ كورىڭىز.',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 سەكۋندتان جاڭالاۋ وزگەرىستەر بۇل تىزىمدە كورسەتىلمەۋى مۇمكىن.',
'lag-warn-high'   => 'دەرەكقور سەرۆەرى زور كەشىگۋى سەبەبىنەن, $1 سەكۋندتان جاڭالاۋ وزگەرىستەر بۇل تىزىمدە كورسەتىلمەۋى مۇمكىن.',

# Watchlist editor
'watchlistedit-numitems'       => 'باقىلاۋ تىزىمىڭىزدە, تالقىلاۋ بەتتەرسىز, {{PLURAL:$1|1|$1}} اتاۋ بار.',
'watchlistedit-noitems'        => 'باقىلاۋ تىزىمىڭىزدە ەش اتاۋ جوق.',
'watchlistedit-normal-title'   => 'باقىلاۋ ٴتىزىمدى وڭدەۋ',
'watchlistedit-normal-legend'  => 'باقىلاۋ تىزىمدەگى اتاۋلاردى الاستاۋ',
'watchlistedit-normal-explain' => 'باقىلاۋ تىزىمىڭىزدەگى اتاۋلار تومەندە كورسەتىلەدى. اتاۋدى الاستاۋ ٴۇشىن, قاسىنداعى قاباشاقتى
بەلگىلەڭىز, جانە اتاۋلاردى الاستاۋ دەگەندى نۇقىڭىز. تاعى دا [[{{ns:special}}:Watchlist/raw|قام ٴتىزىمدى وڭدەي]] الاسىز.',
'watchlistedit-normal-submit'  => 'اتاۋلاردى الاستاۋ',
'watchlistedit-normal-done'    => 'باقىلاۋ تىزىمىڭىزدەن {{PLURAL:$1|1|$1}} اتاۋ الاستالدى:',
'watchlistedit-raw-title'      => 'قام باقىلاۋ ٴتىزىمدى وڭدەۋ',
'watchlistedit-raw-legend'     => 'قام باقىلاۋ ٴتىزىمدى وڭدەۋ',
'watchlistedit-raw-explain'    => 'باقىلاۋ تىزىمىڭىزدەگى اتاۋلار تومەندە كورسەتىلەدى, جانە دە تىزمگە ۇستەپ جانە
تىزمدەن الاستاپ وڭدەۋگە بولادى; ٴبىر جولدا ٴبىر اتاۋ كەلەدى. بىتىرگەننەن سوڭ باقىلاۋ ٴتىزىمدى جاڭارتۋ دەگەندى نۇقىڭىز.
تاعى دا [[Special:Watchlist/edit|قالىپتى وڭدەۋىشتى پايدالانا]] الاسىز.',
'watchlistedit-raw-titles'     => 'اتاۋلار:',
'watchlistedit-raw-submit'     => 'باقىلاۋ ٴتىزىمدى جاڭارتۋ',
'watchlistedit-raw-done'       => 'باقىلاۋ ٴتىزىمىڭىز جاڭارتىلدى.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1|$1}} اتاۋ ۇستەلدى:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1|$1}} اتاۋ الاستالدى:',

# Watchlist editing tools
'watchlisttools-view' => 'قاتىستى وزگەرىستەردى قاراۋ',
'watchlisttools-edit' => 'باقىلاۋ ٴتىزىمدى قاراۋ جانە وڭدەۋ',
'watchlisttools-raw'  => 'قام باقىلاۋ ٴتىزىمدى وڭدەۋ',

# Iranian month names
'iranian-calendar-m1'  => 'پىرۋاردىين',
'iranian-calendar-m2'  => 'اردىيبەشت',
'iranian-calendar-m3'  => 'حىرداد',
'iranian-calendar-m4'  => 'تىير',
'iranian-calendar-m5'  => 'مىرداد',
'iranian-calendar-m6'  => 'شەرىيار',
'iranian-calendar-m7'  => 'مەر',
'iranian-calendar-m8'  => 'ابان',
'iranian-calendar-m9'  => 'ازار',
'iranian-calendar-m10' => 'دىي',
'iranian-calendar-m11' => 'بەمىن',
'iranian-calendar-m12' => 'اسپاند',

# Hebrew month names
'hebrew-calendar-m1'      => 'ٴتىشرىي',
'hebrew-calendar-m2'      => 'xىشۋان',
'hebrew-calendar-m3'      => 'كىسلۋ',
'hebrew-calendar-m4'      => 'توت',
'hebrew-calendar-m5'      => 'شىبات',
'hebrew-calendar-m6'      => 'ادار',
'hebrew-calendar-m6a'     => 'ادار',
'hebrew-calendar-m6b'     => 'ۋادار',
'hebrew-calendar-m7'      => 'نىيسان',
'hebrew-calendar-m8'      => 'ايار',
'hebrew-calendar-m9'      => 'سىيۋان',
'hebrew-calendar-m10'     => 'تىموز',
'hebrew-calendar-m11'     => 'اب',
'hebrew-calendar-m12'     => 'ايلول',
'hebrew-calendar-m1-gen'  => 'ٴتىشرىيدىڭ',
'hebrew-calendar-m2-gen'  => 'حىشۋاندىڭ',
'hebrew-calendar-m3-gen'  => 'كىسلۋدىڭ',
'hebrew-calendar-m4-gen'  => 'توتتىڭ',
'hebrew-calendar-m5-gen'  => 'شىباتتىڭ',
'hebrew-calendar-m6-gen'  => 'اداردىڭ',
'hebrew-calendar-m6a-gen' => 'اداردىڭ',
'hebrew-calendar-m6b-gen' => 'ۋاداردىڭ',
'hebrew-calendar-m7-gen'  => 'نىيساننىڭ',
'hebrew-calendar-m8-gen'  => 'اياردىڭ',
'hebrew-calendar-m9-gen'  => 'سىيۋاننىڭ',
'hebrew-calendar-m10-gen' => 'تىموزدىڭ',
'hebrew-calendar-m11-gen' => 'ابتىڭ',
'hebrew-calendar-m12-gen' => 'ايلولدىڭ',

# Core parser functions
'unknown_extension_tag' => 'تانىلماعان كەڭەيتپە بەلگىسى «$1»',

# Special:Version
'version' => 'جۇيە نۇسقاسى', # Not used as normal message but as header for the special page itself

# Special:Filepath
'filepath'         => 'فايل ورنالاسۋى',
'filepath-page'    => 'فايل اتى:',
'filepath-submit'  => 'ورنالاسۋىن تابۋ',
'filepath-summary' => 'بۇل ارنايى بەت فايل ورنالاسۋى تولىق جولىن قايتارادى. سۋرەتتەر تولىق اجىراتىلىمدىعىمەن كٶرسەتٸلەدٸ, باسقا فايل تٷرلەرٸنە قاتىستى باعدارلاماسى تۋرا جەگٸلەدٸ.

فايل اتاۋىن «{{ns:image}}:» دەگەن باستاۋىشسىز ەڭگٸزٸڭٸز.',

);
