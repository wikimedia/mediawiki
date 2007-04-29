<?php
/**
 * Kazakh (قازاقشا)
 *
 * @addtogroup Language
 *
 */

$fallback = 'kk-kz';
$rtl = true;

$separatorTransformTable = array(
	',' => "\xc2\xa0",
	'.' => ',',
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
	NS_MEDIAWIKI        => 'مەدياۋيكي',
	NS_MEDIAWIKI_TALK   => 'مەدياۋيكي_تالقىلاۋى',
	NS_TEMPLATE         => 'ٷلگٸ',
	NS_TEMPLATE_TALK    => 'ٷلگٸ_تالقىلاۋى',
	NS_HELP             => 'انىقتاما',
	NS_HELP_TALK        => 'انىقتاما_تالقىلاۋى',
	NS_CATEGORY         => 'سانات',
	NS_CATEGORY_TALK    => 'سانات_تالقىلاۋى'
);

$namespaceAliases = array(
	# Aliases to renamed kk-cn namespaces
	'ٴۇلگٴى'              => NS_TEMPLATE,
	'ٴۇلگٴى_تالقىلاۋى'    => NS_TEMPLATE_TALK,
	# Aliases to kk-kz namespaces
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
	# Aliases to kk-tr namespaces
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

);

$skinNames = array(
	'standard'    => 'داعدىلى',
	'nostalgia'   => 'اڭساۋ',
	'cologneblue' => 'كٶلن زەڭگٸرلٸگٸ',
	'davinci'     => 'دا ۆينچي',
	'mono'        => 'دارا',
	'monobook'    => 'دارا كٸتاپ',
	'myskin'      => 'ٶز مٵنەرٸم',
	'chick'       => 'بالاپان',
	'simple'      => 'كٵدٸمگٸ'
);

$datePreferences = array(
	'default',
	'mdy',
	'dmy',
	'ymd',
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
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j F, Y',
	'dmy both' => 'H:i, j F, Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y "ج." xg j',
	'ymd both' => 'H:i, Y "ج." xg j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

/**
 * Magic words
 * Customisable syntax for wikitext and elsewhere
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
	'redirect'               => array( 0,    '#ايداۋ', '#REDIRECT' ),
	'notoc'                  => array( 0,    '__مازمۇنسىز__', '__مسىز__', '__NOTOC__' ),
	'nogallery'              => array( 0,    '__قويماسىز__', '__قسىز__', '__NOGALLERY__' ),
	'forcetoc'               => array( 0,    '__مازمۇنداتقىزۋ__', '__مقىزۋ__', '__FORCETOC__' ),
	'toc'                    => array( 0,    '__مازمۇنى__', '__مزمن__', '__TOC__' ),
	'noeditsection'          => array( 0,    '__بٶلٸمٶندەتكٸزبەۋ__', '__NOEDITSECTION__' ),
	'start'                  => array( 0,    '__باستاۋ__', '__START__' ),
	'currentmonth'           => array( 1,    'اعىمداعىاي', 'CURRENTMONTH' ),
	'currentmonthname'       => array( 1,    'اعىمداعىاياتاۋى', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'    => array( 1,    'اعىمداعىايٸلٸكاتاۋى', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'     => array( 1,    'اعىمداعىايجيىر', 'اعىمداعىايقىسقا', 'CURRENTMONTHABBREV' ),
	'currentday'             => array( 1,    'اعىمداعىكٷن', 'CURRENTDAY' ),
	'currentday2'            => array( 1,    'اعىمداعىكٷن2', 'CURRENTDAY2' ),
	'currentdayname'         => array( 1,    'اعىمداعىكٷناتاۋى', 'CURRENTDAYNAME' ),
	'currentyear'            => array( 1,    'اعىمداعىجىل', 'CURRENTYEAR' ),
	'currenttime'            => array( 1,    'اعىمداعىۋاقىت', 'CURRENTTIME' ),
	'currenthour'            => array( 1,    'اعىمداعىساعات', 'CURRENTHOUR' ),
	'localmonth'             => array( 1,    'جەرگٸلٸكتٸاي', 'LOCALMONTH' ),
	'localmonthname'         => array( 1,    'جەرگٸلٸكتٸاياتاۋى', 'LOCALMONTHNAME' ),
	'localmonthnamegen'      => array( 1,    'جەرگٸلٸكتٸايٸلٸكاتاۋى', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'       => array( 1,    'جەرگٸلٸكتٸايجيىر', 'جەرگٸلٸكتٸايقىسقا', 'LOCALMONTHABBREV' ),
	'localday'               => array( 1,    'جەرگٸلٸكتٸكٷن', 'LOCALDAY' ),
	'localday2'              => array( 1,    'جەرگٸلٸكتٸكٷن2', 'LOCALDAY2'  ),
	'localdayname'           => array( 1,    'جەرگٸلٸكتٸكٷناتاۋى', 'LOCALDAYNAME' ),
	'localyear'              => array( 1,    'جەرگٸلٸكتٸجىل', 'LOCALYEAR' ),
	'localtime'              => array( 1,    'جەرگٸلٸكتٸۋاقىت', 'LOCALTIME' ),
	'localhour'              => array( 1,    'جەرگٸلٸكتٸساعات', 'LOCALHOUR' ),
	'numberofpages'          => array( 1,    'بەتسانى', 'NUMBEROFPAGES' ),
	'numberofarticles'       => array( 1,    'ماقالاسانى', 'NUMBEROFARTICLES' ),
	'numberoffiles'          => array( 1,    'فايلسانى', 'NUMBEROFFILES' ),
	'numberofusers'          => array( 1,    'قاتىسۋشىسانى', 'NUMBEROFUSERS' ),
	'numberofedits'          => array( 1,    'تٷزەتۋسانى', 'NUMBEROFEDITS' ),
	'pagename'               => array( 1,    'بەتاتاۋى', 'PAGENAME' ),
	'pagenamee'              => array( 1,    'بەتاتاۋى2', 'PAGENAMEE' ),
	'namespace'              => array( 1,    'ەسٸماياسى', 'NAMESPACE' ),
	'namespacee'             => array( 1,    'ەسٸماياسى2', 'NAMESPACEE' ),
	'talkspace'              => array( 1,    'تالقىلاۋاياسى', 'TALKSPACE' ),
	'talkspacee'             => array( 1,    'تالقىلاۋاياسى2', 'TALKSPACEE' ),
	'subjectspace'           => array( 1,    'تاقىرىپبەتٸ', 'ماقالابەتٸ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'          => array( 1,    'تاقىرىپبەتٸ2', 'ماقالابەتٸ2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'           => array( 1,    'تولىقبەتاتاۋى', 'FULLPAGENAME' ),
	'fullpagenamee'          => array( 1,    'تولىقبەتاتاۋى2', 'FULLPAGENAMEE' ),
	'subpagename'            => array( 1,    'استىڭعىبەتاتاۋى', 'SUBPAGENAME' ),
	'subpagenamee'           => array( 1,    'استىڭعىبەتاتاۋى2', 'SUBPAGENAMEE' ),
	'basepagename'           => array( 1,    'نەگٸزگٸبەتاتاۋى', 'BASEPAGENAME' ),
	'basepagenamee'          => array( 1,    'نەگٸزگٸبەتاتاۋى2', 'BASEPAGENAMEE' ),
	'talkpagename'           => array( 1,    'تالقىلاۋبەتاتاۋى', 'TALKPAGENAME' ),
	'talkpagenamee'          => array( 1,    'تالقىلاۋبەتاتاۋى2', 'TALKPAGENAMEE' ),
	'subjectpagename'        => array( 1,    'تاقىرىپبەتاتاۋى', 'ماقالابەتاتاۋى', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'       => array( 1,    'تاقىرىپبەتاتاۋى2', 'ماقالابەتاتاۋى2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                    => array( 0,    'حبر:', 'MSG:' ),
	'subst'                  => array( 0,    'بٵدەل:', 'SUBST:' ),
	'msgnw'                  => array( 0,    'ۋيكيسٸزحبر:', 'MSGNW:' ),
	'img_thumbnail'          => array( 1,    'نوباي', 'thumbnail', 'thumb' ),
	'img_manualthumb'        => array( 1,    'نوباي=$1', 'thumbnail=$1', 'thumb=$1'),
	'img_right'              => array( 1,    'وڭعا', 'وڭ', 'right' ),
	'img_left'               => array( 1,    'سولعا', 'سول', 'left' ),
	'img_none'               => array( 1,    'ەشقانداي', 'جوق', 'none' ),
	'img_width'              => array( 1,    '$1 px', '$1px' ),
	'img_center'             => array( 1,    'ورتاعا', 'ورتا', 'center', 'centre' ),
	'img_framed'             => array( 1,    'سٷرمەلٸ', 'framed', 'enframed', 'frame' ),
	'img_page'               => array( 1,    'بەت=$1', 'بەت $1', 'page=$1', 'page $1' ),
	'img_baseline'           => array( 1,    'نەگٸزجول', 'baseline' ),
	'img_sub'                => array( 1,    'استىلىعى', 'است', 'sub'),
	'img_super'              => array( 1,    'ٷستٸلٸگٸ', 'ٷست', 'sup', 'super', 'sup' ),
	'img_top'                => array( 1,    'ٷستٸنە', 'top' ),
	'img_text-top'           => array( 1,    'مٵتٸن-ٷستٸندە', 'text-top' ),
	'img_middle'             => array( 1,    'ارالىعىنا', 'middle' ),
	'img_bottom'             => array( 1,    'استىنا', 'bottom' ),
	'img_text-bottom'        => array( 1,    'مٵتٸن-استىندا', 'text-bottom' ),
	'int'                    => array( 0,    'ٸشكٸ:', 'INT:' ),
	'sitename'               => array( 1,    'توراپاتاۋى', 'SITENAME' ),
	'ns'                     => array( 0,    'ەا:', 'NS:' ),
	'localurl'               => array( 0,    'جەرگٸلٸكتٸجاي:', 'LOCALURL:' ),
	'localurle'              => array( 0,    'جەرگٸلٸكتٸجاي2:', 'LOCALURLE:' ),
	'server'                 => array( 0,    'سەرۆەر', 'SERVER' ),
	'servername'             => array( 0,    'سەرۆەراتاۋى', 'SERVERNAME' ),
	'scriptpath'             => array( 0,    'ٵمٸرجولى', 'SCRIPTPATH' ),
	'grammar'                => array( 0,    'سەپتٸك:', 'GRAMMAR:' ),
	'notitleconvert'         => array( 0,    '__اتاۋالماستىرعىزباۋ__', '__ااباۋ__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'       => array( 0,    '__ماعلۇماتالماستىرعىزباۋ__', '__ماباۋ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'            => array( 1,    'اعىمداعىاپتا', 'CURRENTWEEK' ),
	'currentdow'             => array( 1,    'اعىمداعىاپتاكٷنٸ', 'CURRENTDOW' ),
	'localweek'              => array( 1,    'جەرگٸلٸكتٸاپتا', 'LOCALWEEK' ),
	'localdow'               => array( 1,    'جەرگٸلٸكتٸاپتاكٷنٸ', 'LOCALDOW' ),
	'revisionid'             => array( 1,    'نۇسقانٶمٸرٸ', 'REVISIONID' ),
	'revisionday'            => array( 1,    'نۇسقاكٷنٸ' , 'REVISIONDAY' ),
	'revisionday2'           => array( 1,    'نۇسقاكٷنٸ2', 'REVISIONDAY2' ),
	'revisionmonth'          => array( 1,    'نۇسقاايى', 'REVISIONMONTH' ),
	'revisionyear'           => array( 1,    'نۇسقاجىلى', 'REVISIONYEAR' ),
	'revisiontimestamp'      => array( 1,    'نۇسقاۋاقىتتٷيٸندەمەسٸ', 'REVISIONTIMESTAMP' ),
	'plural'                 => array( 0,    'كٶپشە:', 'PLURAL:' ),
	'fullurl'                => array( 0,    'تولىقجاي:', 'FULLURL:' ),
	'fullurle'               => array( 0,    'تولىقجاي2:', 'FULLURLE:' ),
	'lcfirst'                => array( 0,    'كٵ1:', 'LCFIRST:' ),
	'ucfirst'                => array( 0,    'بٵ1:', 'UCFIRST:' ),
	'lc'                     => array( 0,    'كٵ:', 'LC:' ),
	'uc'                     => array( 0,    'بٵ:', 'UC:' ),
	'raw'                    => array( 0,    'قام:', 'RAW:' ),
	'displaytitle'           => array( 1,    'كٶرسەتٸلەتٸناتاۋ', 'DISPLAYTITLE' ),
	'rawsuffix'              => array( 1,    'ق', 'R' ),
	'newsectionlink'         => array( 1,    '__جاڭابٶلٸمسٸلتەمەسٸ__', '__NEWSECTIONLINK__' ),
	'currentversion'         => array( 1,    'باعدارلامانۇسقاسى', 'CURRENTVERSION' ),
	'urlencode'              => array( 0,    'جايدىمۇقامداۋ:', 'URLENCODE:' ),
	'anchorencode'           => array( 0,    'جٵكٸردٸمۇقامداۋ', 'ANCHORENCODE' ),
	'currenttimestamp'       => array( 1,    'اعىمداعىۋاقىتتٷيٸندەمەسٸ', 'اعىمداعىۋاقىتتٷيٸن', 'CURRENTTIMESTAMP' ),
	'localtimestamp'         => array( 1,    'جەرگٸلٸكتٸۋاقىتتٷيٸندەمەسٸ', 'جەرگٸلٸكتٸۋاقىتتٷيٸن', 'LOCALTIMESTAMP' ),
	'directionmark'          => array( 1,    'باعىتبەلگٸسٸ', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'               => array( 0,    '#تٸل:', '#LANGUAGE:' ),
	'contentlanguage'        => array( 1,    'ماعلۇماتتٸلٸ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'       => array( 1,    'ەسٸمايابەتسانى:', 'ەابەتسانى:', 'ايابەتسانى:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'         => array( 1,    'ٵكٸمشٸسانى', 'NUMBEROFADMINS' ),
	'formatnum'              => array( 0,    'سانپٸشٸمٸ', 'FORMATNUM' ),
	'padleft'                => array( 0,    'سولىعىس', 'PADLEFT' ),
	'padright'               => array( 0,    'وڭىعىس', 'PADRIGHT' ),
	'special'                => array( 0,    'ارنايى', 'special',  ),
	'defaultsort'            => array( 1,    'ٵدەپكٸسۇرىپتاۋ:', 'ٵدەپكٸسۇرىپ:', 'DEFAULTSORT:' ),
);

$specialPageAliases = array(
        'DoubleRedirects'           => array( 'شىنجىرلى_ايداتۋلار' ),
        'BrokenRedirects'           => array( 'جارامسىز_ايداتۋلار' ),
        'Disambiguations'           => array( 'ايرىقتى_بەتتەر' ),
        'Userlogin'                 => array( 'قاتىسۋشى_كٸرۋٸ' ),
        'Userlogout'                => array( 'قاتىسۋشى_شىعۋى' ),
        'Preferences'               => array( 'باپتاۋ' ),
        'Watchlist'                 => array( 'باقىلاۋ_تٸزٸمٸ' ),
        'Recentchanges'             => array( 'جۋىقتاعى_ٶزگەرٸستەر' ),
        'Upload'                    => array( 'قوتارۋ' ),
        'Imagelist'                 => array( 'سۋرەت_تٸزٸمٸ' ),
        'Newimages'                 => array( 'جاڭا_سۋرەتتەر' ),
        'Listusers'                 => array( 'قاتىسۋشىلار' ),
        'Statistics'                => array( 'ساناق' ),
        'Randompage'                => array( 'كەزدەيسوق_بەت', 'كەزدەيسوق' ),
        'Lonelypages'               => array( 'ساياق_بەتتەر' ),
        'Uncategorizedpages'        => array( 'ساناتسىز_بەتتەر' ),
        'Uncategorizedcategories'   => array( 'ساناتسىز_ساناتتار' ),
        'Uncategorizedimages'       => array( 'ساناتسىز_سۋرەتتەر' ),
        'Unusedcategories'          => array( 'پايدالانىلماعان_ساناتتار' ),
        'Unusedimages'              => array( 'پايدالانىلماعان_سۋرەتتەر' ),
        'Wantedpages'               => array( 'تولتىرىلماعان_بەتتەر' ),
        'Wantedcategories'          => array( 'تولتىرىلماعان_ساناتتار' ),
        'Mostlinked'                => array( 'ەڭ_كٶپ_سٸلتەنگەن_بەتتەر' ),
        'Mostlinkedcategories'      => array( 'ەڭ_كٶپ_سٸلتەنگەن_ساناتتار' ),
        'Mostcategories'            => array( 'ەڭ_كٶپ_ساناتتار_بارى' ),
        'Mostimages'                => array( 'ەڭ_كٶپ_سۋرەتتەر_بارى' ),
        'Mostrevisions'             => array( 'ەڭ_كٶپ_نۇسقالار_بارى' ),
        'Fewestrevisions'           => array( 'ەڭ_از_تٷزەتٸلگەن ' ),
        'Shortpages'                => array( 'قىسقا_بەتتەر' ),
        'Longpages'                 => array( 'ٷلكەن_بەتتەر' ),
        'Newpages'                  => array( 'جاڭا_بەتتەر' ),
        'Ancientpages'              => array( 'ەسكٸ_بەتتەر' ),
        'Deadendpages'              => array( 'تۇيىق_بەتتەر' ),
        'Protectedpages'            => array( 'قورعالعان_بەتتەر' ),
        'Allpages'                  => array( 'بارلىق_بەتتەر' ),
        'Prefixindex'               => array( 'باستاۋىش_تٸزٸمٸ' ) ,
        'Ipblocklist'               => array( 'بۇعاتتالعاندار' ),
        'Specialpages'              => array( 'ارنايى_بەتتەر' ),
        'Contributions'             => array( 'ٷلەسٸ' ),
        'Emailuser'                 => array( 'حات_جٸبەرۋ' ),
        'Whatlinkshere'             => array( 'مىندا_سٸلتەگەندەر' ),
        'Recentchangeslinked'       => array( 'سٸلتەنگەندەردٸڭ_ٶزگەرٸستەرٸ' ),
        'Movepage'                  => array( 'بەتتٸ_جىلجىتۋ' ),
        'Blockme'                   => array( 'ٶزدٸك_بۇعاتتاۋ' ),
        'Booksources'               => array( 'كٸتاپ_قاينارلارى' ),
        'Categories'                => array( 'ساناتتار' ),
        'Export'                    => array( 'سىرتقا_بەرۋ' ),
        'Version'                   => array( 'نۇسقاسى' ),
        'Allmessages'               => array( 'بارلىق_حابارلار' ),
        'Log'                       => array( 'جۋرنالدار', 'جۋرنال' ),
        'Blockip'                   => array( 'جايدى_بۇعاتتاۋ' ),
        'Undelete'                  => array( 'جويىلعاندى_قايتارۋ' ),
        'Import'                    => array( 'سىرتتان_الۋ' ),
        'Lockdb'                    => array( 'دەرەكقوردى_قۇلىپتاۋ' ),
        'Unlockdb'                  => array( 'دەرەكقوردى_قۇلىپتاماۋ' ),
        'Userrights'                => array( 'قاتىسۋشى_قۇقىقتارى' ),
        'MIMEsearch'                => array( 'MIME_تٷرٸمەن_ٸزدەۋ' ),
        'Unwatchedpages'            => array( 'باقىلانىلماعان_بەتتەر' ),
        'Listredirects'             => array( 'ايداتۋ_تٸزٸمٸ' ),
        'Revisiondelete'            => array( 'نۇسقانى_جويۋ' ),
        'Unusedtemplates'           => array( 'پايدالانىلماعان_ٷلگٸلەر' ),
        'Randomredirect'            => array( 'كەدەيسوق_ايداتۋ' ),
        'Mypage'                    => array( 'جەكە_بەتٸم' ),
        'Mytalk'                    => array( 'تالقىلاۋىم' ),
        'Mycontributions'           => array( 'ٷلەسٸم' ),
        'Listadmins'                => array( 'ٵكٸمشٸلەر'),
        'Popularpages'              => array( 'ٵيگٸلٸ_بەتتەر' ),
        'Search'                    => array( 'ٸزدەۋ' ),
        'Resetpass'                 => array( 'قۇپييا_سٶزدٸ_قايتارۋ' ),
        'Withoutinterwiki'          => array( 'ۋيكي-ارالىقسىزدار' ),
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------

$messages = array(
# User preference toggles
'tog-underline'               => 'سٸلتەمەنٸ استىنان سىز:',
'tog-highlightbroken'         => 'جوقتالعان سٸلتەمەلەردٸ <a href="" class="new">بىلاي</a> پٸشٸمدە (باسقاشا: بىلاي <a href="" class="internal">؟</a> سيياقتى).',
'tog-justify'                 => 'ەجەلەردٸ ەنٸ بويىنشا تۋرالاۋ',
'tog-hideminor'               => 'جۋىقتاعى ٶزگەرٸستەردە شاعىن تٷزەتۋدٸ جاسىر',
'tog-extendwatchlist'         => 'باقىلاۋ تٸزٸمدٸ ۇلعايت (بارلىق جارامدى ٶزگەرٸستەردٸ كٶرسەت)',
'tog-usenewrc'                => 'كەڭەيتٸلگەن جۋىقتاعى ٶزگەرٸستەر (JavaScript)',
'tog-numberheadings'          => 'بٶلٸم تاقىرىپتارىن ٶزدٸك تٷردە نومٸرلە',
'tog-showtoolbar'             => 'ٶڭدەۋ قۋرالدار جولاعىن كٶرسەت (JavaScript)',
'tog-editondblclick'          => 'قوس نۇقىمداپ ٶڭدەۋ (JavaScript)',
'tog-editsection'             => 'بٶلٸمدەردٸ [ٶڭدەۋ] سٸلتەمەسٸمەن ٶڭدەۋٸن ەندٸر',
'tog-editsectiononrightclick' => 'بٶلٸم اتاۋىن وڭ جاق نۇقۋمەن<br />ٶڭدەۋٸن ەندٸر (JavaScript)',
'tog-showtoc'                 => 'مازمۇنىن كٶرسەت (3-تەن ارتىق بٶلٸمٸ بارىلارعا)',
'tog-rememberpassword'        => 'كٸرگەنٸمدٸ بۇل كومپييۋتەردە ۇمىتپا',
'tog-editwidth'               => 'ٶڭدەۋ اۋماعى تولىق ەنٸمەن',
'tog-watchcreations'          => 'مەن باستاعان بەتتەردٸ باقىلاۋ تٸزٸمٸمە قوس',
'tog-watchdefault'            => 'مەن ٶڭدەگەن بەتتەردٸ باقىلاۋ تٸزٸمٸمە قوس',
'tog-watchmoves'              => 'مەن جىلجىتقان بەتتەردٸ باقىلاۋ تٸزٸمٸمە قوس',
'tog-watchdeletion'           => 'مەن جويعان بەتتەردٸ باقىلاۋ تٸزٸمٸمە قوس',
'tog-minordefault'            => 'ٵدەپكٸدەن بارلىق تٷزەتۋلەردٸ شاعىن دەپ بەلگٸلەۋ',
'tog-previewontop'            => 'قاراپ شىعۋ اۋماعى ٶڭدەۋ اۋماعى الدىندا',
'tog-previewonfirst'          => 'بٸرٸنشٸ ٶڭدەگەندە قاراپ شىعۋ',
'tog-nocache'                 => 'بەت قوسالقى قالتاسىن ٶشٸر',
'tog-enotifwatchlistpages'    => 'باقىلانعان بەت ٶزگەرگەندە ماعان حات جٸبەر',
'tog-enotifusertalkpages'     => 'تالقىلاۋىم ٶزگەرگەندە ماعان حات جٸبەر',
'tog-enotifminoredits'        => 'شاعىن تٷزەتۋ تۋرالى دا ماعان حات جٸبەر',
'tog-enotifrevealaddr'        => 'ە-پوشتا جايىمدى ەسكەرتۋ حاتتا اشىق كٶرسەت',
'tog-shownumberswatching'     => 'باقىلاپ تۇرعان قاتىسۋشىلاردىڭ سانىن كٶرسەت',
'tog-fancysig'                => 'قام قولتاڭبا (ٶزدٸك سٸلتەمەسٸز;)',
'tog-externaleditor'          => 'سىرتقى ٶڭدەۋٸشتٸ ٵدەپكٸدەن قولدان',
'tog-externaldiff'            => 'سىرتقى ايىرماعىشتى ٵدەپكٸدەن قولدان',
'tog-showjumplinks'           => '«ٶتٸپ كەتۋ» قاتىناۋ سٸلتەمەلەرٸن ەندٸر',
'tog-uselivepreview'          => 'تۋرا قاراپ شىعۋدى قولدانۋ (JavaScript) (سىناق تٷرٸندە)',
'tog-forceeditsummary'        => 'ٶڭدەۋ سيپاتتاماسى بوس قالعاندا ماعان ەسكەرت',
'tog-watchlisthideown'        => 'تٷزەتۋٸمدٸ باقىلاۋ تٸزٸمنەن جاسىر',
'tog-watchlisthidebots'       => 'بوت تٷزەتۋٸن باقىلاۋ تٸزٸمنەن جاسىر',
'tog-watchlisthideminor'      => 'شاعىن تٷزەتۋلەردٸ باقىلاۋ تٸزٸمٸندە كٶرسەتپەۋ',
'tog-nolangconversion'        => 'تٸل تٷرٸن اۋدارماۋ',
'tog-ccmeonemails'            => 'باسقا قاتىسۋشىعا جٸبەرگەن حاتىمنىڭ كٶشٸرمەسٸن ماعان دا جٸبەر',
'tog-diffonly'                => 'ايىرما استىندا بەت ماعلۇماتىن كٶرسەتپە',

'underline-always'  => 'ٵرقاشان',
'underline-never'   => 'ەشقاشان',
'underline-default' => 'شولعىش بويىنشا',

'skinpreview' => '(قاراپ شىعۋ)',

# Dates
'sunday'        => 'جەكسەنبٸ',
'monday'        => 'دٷيسەنبٸ',
'tuesday'       => 'سەيسەنبٸ',
'wednesday'     => 'سٵرسەنبٸ',
'thursday'      => 'بەيسەنبٸ',
'friday'        => 'جۇما',
'saturday'      => 'سەنبٸ',
'sun'           => 'جەك',
'mon'           => 'دٷي',
'tue'           => 'بەي',
'wed'           => 'سٵر',
'thu'           => 'بەي',
'fri'           => 'جۇم',
'sat'           => 'سەن',
'january'       => 'قاڭتار',
'february'      => 'اقپان',
'march'         => 'ناۋرىز',
'april'         => 'cٵۋٸر',
'may_long'      => 'مامىر',
'june'          => 'ماۋسىم',
'july'          => 'شٸلدە',
'august'        => 'تامىز',
'september'     => 'قىركٷيەك',
'october'       => 'قازان',
'november'      => 'قاراشا',
'december'      => 'جەلتوقسان',
'january-gen'   => 'قانتاردىڭ',
'february-gen'  => 'اقپاننىڭ',
'march-gen'     => 'ناۋرىزدىڭ',
'april-gen'     => 'سٵۋٸردٸڭ',
'may-gen'       => 'مامىردىڭ',
'june-gen'      => 'ماۋسىمنىڭ',
'july-gen'      => 'شٸلدەنٸڭ',
'august-gen'    => 'تامىزدىڭ',
'september-gen' => 'قىركٷيەكتٸڭ',
'october-gen'   => 'قازاننىڭ',
'november-gen'  => 'قاراشانىڭ',
'december-gen'  => 'جەلتوقساننىڭ',
'jan'           => 'قان',
'feb'           => 'اقپ',
'mar'           => 'ناۋ',
'apr'           => 'cٵۋ',
'may'           => 'مام',
'jun'           => 'ماۋ',
'jul'           => 'شٸل',
'aug'           => 'تام',
'sep'           => 'قىر',
'oct'           => 'قاز',
'nov'           => 'قار',
'dec'           => 'جەل',

# Bits of text used by many pages
'categories'            => 'بارلىق سانات تٸزٸمٸ',
'pagecategories'        => '{{PLURAL:$1|سانات|ساناتتار}}',
'category_header'       => '«$1» ساناتىنداعى بەتتەر',
'subcategories'         => 'تٶمەنگٸ ساناتتار',
'category-media-header' => '«$1» ساناتىنداعى تاسپالار',

'linkprefix'        => '/^(.*?)([a-zäçéğıïñöşüýа-яёәіңғүұқөһA-ZÄÇÉĞİÏÑÖŞÜÝА-ЯЁӘІҢҒҮҰҚӨҺʺʹ«„]+)$/sDu',
'mainpagetext'      => "<big>'''مەدياۋيكي باعدارلاماسى سٵتتٸ ورناتىلدى.'''</big>",
'mainpagedocfooter' => 'ۋيكي باعدارلاماسىن پايدالانۋ اقپاراتى ٷشٸن [http://meta.wikimedia.org/wiki/Help:Contents پايدالانۋشى نۇسقاۋلارىمەن] تانىسىڭىز.

== باستاۋ ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings باپتاۋ قالاۋلارى تٸزٸمٸ]
* [http://www.mediawiki.org/wiki/Help:FAQ مەدياۋيكي جقس]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce مەدياۋيكي حات تاراتۋ تٸزٸمٸ]',

'article'        => 'ماعلۇمات بەتٸ',
'newwindow'      => '(جاڭا تەرەزەدە اشىلادى)',
'cancel'         => 'بولدىرماۋ',
'qbfind'         => 'تابۋ',
'qbbrowse'       => 'شولۋ',
'qbedit'         => 'ٶڭدەۋ',
'qbpageoptions'  => 'وسى بەت',
'qbpageinfo'     => 'مٵتٸن ارالىعى',
'qbmyoptions'    => 'بەتتەرٸم',
'qbspecialpages' => 'ارنايى بەتتەر',
'moredotdotdot'  => 'كٶبٸرەك…',
'mypage'         => 'جەكە بەتٸم',
'mytalk'         => 'تالقىلاۋىم',
'anontalk'       => 'IP تالقىلاۋى',
'navigation'     => 'باعىتتاۋ',

# Metadata in edit box
'metadata_help' => 'مەتا-دەرەكتەر:',

'errorpagetitle'    => 'قاتە',
'returnto'          => '$1 دەگەنگە ورالۋ.',
'tagline'           => '{{GRAMMAR:ablative|{{SITENAME}}}}',
'search'            => 'ٸزدەۋ',
'searchbutton'      => 'ٸزدەۋ',
'go'                => 'ٶتۋ',
'searcharticle'     => 'ٶتۋ',
'history'           => 'بەت تاريحى',
'history_short'     => 'تاريحى',
'updatedmarker'     => 'سوڭعى كٸرٸستەن بەرٸ جاڭارتىلعان',
'info_short'        => 'اقپارات',
'printableversion'  => 'باسىپ شىعارۋعا',
'permalink'         => 'تۇراقتى سٸلتەمە',
'print'             => 'باسىپ شىعارۋ',
'edit'              => 'ٶڭدەۋ',
'editthispage'      => 'بەتتٸ ٶڭدەۋ',
'delete'            => 'جويۋ',
'deletethispage'    => 'بەتتٸ جويۋ',
'undelete_short'    => '{{PLURAL:$1|بٸر|$1}} تٷزەتۋدٸ قايتارۋ',
'protect'           => 'قورعاۋ',
'protect_change'    => 'قورعاۋدى ٶزگەرتۋ',
'protectthispage'   => 'بەتتٸ قورعاۋ',
'unprotect'         => 'قورعاماۋ',
'unprotectthispage' => 'بەتتٸ قورعاماۋ',
'newpage'           => 'جاڭا بەت',
'talkpage'          => 'بەتتٸ تالقىلاۋ',
'talkpagelinktext'  => 'تالقىلاۋى',
'specialpage'       => 'ارنايى بەت',
'personaltools'     => 'جەكە قۇرالدار',
'postcomment'       => 'مٵندەمە جٸبەرۋ',
'articlepage'       => 'ماعلۇمات بەتٸن قاراۋ',
'talk'              => 'تالقىلاۋ',
'views'             => 'كٶرٸنٸس',
'toolbox'           => 'قۇرالدار',
'userpage'          => 'قاتىسۋشىنىڭ بەتٸن قاراۋ',
'projectpage'       => 'جوبا بەتٸن قاراۋ',
'imagepage'         => 'سۋرەت بەتٸن قاراۋ',
'mediawikipage'     => 'حابار بەتٸن قاراۋ',
'templatepage'      => 'ٷلگٸ بەتٸن قاراۋ',
'viewhelppage'      => 'انىقتاما بەتٸن قاراۋ',
'categorypage'      => 'سانات بەتٸن قاراۋ',
'viewtalkpage'      => 'تالقىلاۋ بەتٸن قاراۋ',
'otherlanguages'    => 'باسقا تٸلدەردە',
'redirectedfrom'    => '($1 بەتٸنەن ايداتىلعان)',
'redirectpagesub'   => 'ايداتۋ بەتٸ',
'lastmodifiedat'    => 'بۇل بەتتٸڭ ٶزگەرتٸلگەن سوڭعى كەزٸ: $2, $1.', # $1 date, $2 time
'viewcount'         => 'بۇل بەت {{plural:$1|بٸر|$1}} رەت قارالعان.',
'protectedpage'     => 'قورعاۋلى بەت',
'jumpto'            => 'مىناعان ٶتٸپ كەتۋ:',
'jumptonavigation'  => 'باعىتتاۋ',
'jumptosearch'      => 'ٸزدەۋ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'about'             => 'بٸز تۋرالى',
'aboutsite'         => '{{SITENAME}} تۋرالى',
'aboutpage'         => '{{ns:project}}:بٸز_تۋرالى',
'bugreports'        => 'قاتە ەسەپتەمەلەرٸ',
'bugreportspage'    => '{{ns:project}}:قاتە_ەسەپتەمەلەرٸ',
'copyright'         => 'ماعلۇمات $1 قۇجاتى بويىنشا قاتىناۋلى.',
'copyrightpagename' => '{{SITENAME}} اۋتورلىق قۇقىقتارى',
'copyrightpage'     => '{{ns:project}}:اۋتورلىق قۇقىقتار',
'currentevents'     => 'اعىمداعى وقيعالار',
'currentevents-url' => 'اعىمداعى_وقيعالار',
'disclaimers'       => 'جاۋاپكەرشٸلٸكتەن باس تارتۋ',
'disclaimerpage'    => '{{ns:project}}:جاۋاپكەرشٸلٸكتەن_باس_تارتۋ',
'edithelp'          => 'ٶندەۋ انىقتاماسى',
'edithelppage'      => '{{ns:help}}:ٶڭدەۋ',
'faq'               => 'جقس',
'faqpage'           => '{{ns:project}}:جقس',
'help'              => 'انىقتاما',
'helppage'          => '{{ns:help}}:مازمۇنى',
'mainpage'          => 'باستى بەت',
'portal'            => 'قاۋىم پورتالى',
'portal-url'        => '{{ns:project}}:قاۋىم_پورتالى',
'privacy'           => 'جەكە قۇپيياسىن ساقتاۋ',
'privacypage'       => '{{ns:project}}:جەكە_قۇپيياسىن_ساقتاۋ',
'sitesupport'       => 'دەمەۋشٸلٸك',
'sitesupport-url'   => '{{ns:project}}:جٵردەم',

'badaccess'        => 'رۇقسات قاتەسٸ',
'badaccess-group0' => 'سۇرانىسقان ٵرەكەتٸڭٸزدٸ جەگۋٸڭٸزگە رۇقسات ەتٸلمەيدٸ.',
'badaccess-group1' => 'سۇرانىسقان ٵرەكەتٸڭٸز $1 توبىنىڭ قاتىسۋشىلارىنا شەكتەلەدٸ.',
'badaccess-group2' => 'سۇرانىسقان ٵرەكەتٸڭٸز $1 توپتارى بٸرٸنٸڭ قاتۋسىشىلارىنا شەكتەلەدٸ.',
'badaccess-groups' => 'سۇرانىسقان ٵرەكەتٸڭٸز $1 توپتارى بٸرٸنٸڭ قاتۋسىشىلارىنا شەكتەلەدٸ.',

'versionrequired'     => 'MediaWiki $1 نۇسقاسى قاجەت',
'versionrequiredtext' => 'وسى بەتتٸ قولدانۋ ٷشٸن MediaWiki $1 نۇسقاسى قاجەت. [[{{ns:special}}:Version|جٷيە نۇسقاسى بەتٸن]] قاراڭىز.',

'ok'                  => 'جارايدى',
'pagetitle'           => '$1 — {{SITENAME}}',
'retrievedfrom'       => '«$1» دەگەننەن الىنعان',
'youhavenewmessages'  => 'سٸزدە $1 بار ($2).',
'newmessageslink'     => 'جاڭا حابارلار',
'newmessagesdifflink' => 'سوڭعى ٶزگەرٸسٸنە',
'editsection'         => 'ٶڭدەۋ',
'editold'             => 'ٶڭدەۋ',
'editsectionhint'     => 'بٶلٸمدٸ ٶڭدەۋ: $1',
'toc'                 => 'مازمۇنى',
'showtoc'             => 'كٶرسەت',
'hidetoc'             => 'جاسىر',
'thisisdeleted'       => 'قارايمىز با, نە قايتارامىز با؟: $1',
'viewdeleted'         => 'قارايمىز با؟: $1',
'restorelink'         => 'جويىلعان {{PLURAL:$1|بٸر|$1}} تٷزەتۋ',
'feedlinks'           => 'ارنا:',
'feed-invalid'        => 'جارامسىز جازىلىم ارنا تٷرٸ.',
'feed-atom'           => 'Atom',
'feed-rss'            => 'RSS',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'ماعلۇمات',
'nstab-user'      => 'جەكە بەتٸ',
'nstab-media'     => 'تاسپا بەتٸ',
'nstab-special'   => 'ارنايى',
'nstab-project'   => 'جوبا بەتٸ',
'nstab-image'     => 'فايل',
'nstab-mediawiki' => 'جٷيە حابارى',
'nstab-template'  => 'ٷلگٸ',
'nstab-help'      => 'انىقتاما',
'nstab-category'  => 'سانات',

# Main script and global functions
'nosuchaction'      => 'مۇنداي ٵرەكەت جوق',
'nosuchactiontext'  => 'وسى URL جايىمەن ەنگٸزٸلگەن ٵرەكەتتٸ
وسى ۋيكي جورامالداپ بٸلمەدٸ.',
'nosuchspecialpage' => 'بۇل ارنايى بەت ەمەس',
'nospecialpagetext' => 'سٸز سۇرانىسقان ارنايى بەت جارامسىز. بارلىق جارامدى ارنايى بەتتەردٸ [[{{ns:special}}:Specialpages|ارنايى بەتتەر تٸزٸمٸندە]] تابا الاسىز.',

# General errors
'error'                => 'قاتە',
'databaseerror'        => 'دەرەكقوردىڭ قاتەسٸ',
'dberrortext'          => 'دەرەكقورعا سۇرانىس جاسالعاندا سينتاكسيس قاتەسٸ كەزدەستٸ.
بۇل باعدارلامانىڭ قاتەسٸن كٶرسەتۋ مٷمكٸن.
دەرەكقورعا سوڭعى بولعان سۇرانىس:
<blockquote><tt>$1</tt></blockquote>
مىنا فۋنكتسيياسىنان «<tt>$2</tt>».
MySQL قايتارعان قاتەسٸ «<tt>$3: $4</tt>».',
'dberrortextcl'        => 'دەرەكقورعا سۇرانىس جاسالعاندا سينتاكسيس قاتەسٸ كەزدەستٸ.
دەرەكقورعا سوڭعى بولعان سۇرانىس:
«$1»
مىنا فۋنكتسيياسىنان: «$2».
MySQL قايتارعان قاتەسٸ «$3: $4»',
'noconnect'            => 'عافۋ ەتٸڭٸز! بۇل ۋيكيدە كەيبٸر تەحنيكالىق قيىنشىلىقتار كەزدەستٸ, سوندىقتان دەرەكقور سەرۆەرٸنە قاتىناسۋ المايدى. <br />
$1',
'nodb'                 => '$1 دەرەكقورى تالعانبادى',
'cachederror'          => 'تٶمەندە سۇرانعان بەتتٸڭ قوسالقى قالتاداعى كٶشٸرمەسٸ, وسى بەت جاڭارتىلماعان بولۋى مٷمكٸن.',
'laggedslavemode'      => 'نازار سالىڭىز: بەتتە جۋىقتاعى جاڭالاۋلار بولماۋى مٷمكٸن.',
'readonly'             => 'دەرەكقورى قۇلىپتالعان',
'enterlockreason'      => 'قۇلىپتاۋ سەبەبٸن ەنگٸزٸڭٸز, قاي ۋاقىتقا دەيٸن
قۇلىپتالعانىن قوسا',
'readonlytext'         => 'اعىمدا دەرەكقور جاڭا جازبا جٵنە تاعى باسقا ٶزگەرٸستەر جاساۋدان قۇلىپتالىنعان. بۇل دەرەكقوردى جٶندەتۋ باعدارلامالارىن ورىنداۋ ٷشٸن بولۋى مٷمكٸن, بۇنى بٸتٸرگەننەن سوڭ قالٸپتٸ ٸسكە قايتارىلادى.

قۇلىپتاعان ٵكٸمشٸ بۇنى بىلاي تٷسٸندٸرەدٸ: $1',
'missingarticle'       => 'ٸزدەستٸرٸلگەن «$1» اتاۋلى بەت مٵتٸنٸ دەرەكقوردا تابىلمادى.

بۇل داعدىدا ەسكٸرگەن ايىرما سٸلتەمەسٸنە نەمەسە جويىلعان بەت تاريحىنىڭ سٸلتەمەسٸنە
ەرگەننەن بولۋى مٷمكٸن.

ەگەر بۇل بولجام دۇرىس سەبەپ بولماسا, باعدارلامامىزداعى قاتەگە تاپ بولۋىڭىز مٷمكٸن.
بۇل تۋرالى ناقتى URL جايىن كٶرسەتٸپ ٵكٸمشٸگە ەسەپتەمە جٸبەرٸڭٸز.',
'readonly_lag'         => 'جەتەك دەرەكقور سەرۆەرلەر باستاۋىشپەن قاداملانعاندا وسى دەرەكقور ٶزدٸك تٷرٸندە قۇلىپتالىنعان',
'internalerror'        => 'ٸشكٸ قاتە',
'filecopyerror'        => '«$1» فايلى «$2» فايلىنا كٶشٸرٸلمەدٸ.',
'filerenameerror'      => '«$1» فايل اتى «$2» اتىنا ٶزگەرتٸلمەدٸ.',
'filedeleteerror'      => '«$1» فايلى جويىلمايدى.',
'filenotfound'         => '«$1» فايلى تابىلمادى.',
'unexpected'           => 'كٷتٸلمەگەن ماعىنا: «$1» = «$2».',
'formerror'            => 'قاتە: جٸبەرۋ ٷلگٸتٸ ەمەس',
'badarticleerror'      => 'وسىنداي ٵرەكەت مىنا بەتتە اتقارىلمايدى.',
'cannotdelete'         => 'ايتىلمىش بەت نە سۋرەت جويىلمايدى. (بۇنى باسقا بٸرەۋ جويعان شىعار.)',
'badtitle'             => 'جارامسىز اتاۋ',
'badtitletext'         => 'سۇرانىسقان بەت اتاۋى جارامسىز, بوس, تٸلارا سٸلتەمەسٸ نە ۋيكي-ارا اتاۋى مٷلتٸك بولعان. اتاۋلاردا سٷەمەلدەمەگەن بٸرقاتار ٵرٸپتەر بولۋى مٷمكٸن.',
'perfdisabled'         => 'عافۋ ەتٸڭٸز! وسى قاسيەت, دەرەكقوردىڭ جىلدامىلىعىنا ٵسەر ەتٸپ, ەشكٸمگە ۋيكيدٸ پايدالانۋعا بەرمەگەسٸن, ۋاقىتشا ٶشٸرٸلگەن.',
'perfdisabledsub'      => 'مىندا $1 بەتٸنٸڭ ساقتالعان كٶشٸرمەسٸ:', # obsolete؟
'perfcached'           => 'كەلەسٸ دەرەك قوسالقى قالتاسىنان الىنعان, سوندىقتان تولىقتاي جاڭالانماعان بولۋى مٷمكٸن.',
'perfcachedts'         => 'كەلەسٸ دەرەك قوسالقى قالتاسىنان الىنعان, سوڭعى جاڭالانلعان كەزٸ: $1.',
'querypage-no-updates' => 'بۇل بەتتٸڭ جاڭارتىلۋى اعىمدا ٶشٸرٸلگەن. دەرەكتەرٸ قازٸر ٶزگەرتٸلمەيدٸ.',
'wrong_wfQuery_params' => 'wfQuery() فۋنكتسيياسىندا جارامسىز باپتار<br />
فۋنكتسييا: $1<br />
سۇرانىس: $2',
'viewsource'           => 'قاينارىن قاراۋ',
'viewsourcefor'        => '$1 دەگەن ٷشٸن',
'protectedpagetext'    => 'بۇل بەت ٶڭدەۋ بولدىرماۋ ٷشٸن قۇلىپتالىنعان.',
'viewsourcetext'       => 'بۇل بەتتٸڭ قاينارىن قاراۋىڭىزعا جٵنە كٶشٸرٸپ الۋڭىزعا بولادى:',
'protectedinterface'   => 'بۇل بەت باعدارلامانىڭ تٸلدەسۋ مٵتٸنٸن جەتٸستٸرەدٸ, سوندىقتان قييانات كەلتٸرمەۋ ٷشٸن ٶزگەرتۋٸ قۇلىپتالعان.',
'editinginterface'     => "'''نازار سالىڭىز:''' باعدارلاماعا تٸلدەسۋ مٵتٸنٸن جەتٸستٸرەتٸن MediaWiki بەتٸن ٶڭدەپ جاتىرسىز. بۇل بەتتٸڭ ٶزگەرتۋٸ بارلىق پايدالانۋشىلار تٸلدەسۋٸنە ٵسەر ەتەدٸ.",
'sqlhidden'            => '(SQL سۇرانىسى جاسىرىلدى)',
'cascadeprotected'     => 'بۇل بەت ٶڭدەۋدەن قورعالعان, سەبەبٸ: ول مىنا «باۋلى» قورعاۋى ەندٸرٸلگەن {{PLURAL:$1|بەتكە|بەتتەرگە}} كٸرٸكتٸرٸلگەن:',

# Login and logout pages
'logouttitle'                => 'قاتىسۋشى شىعۋى',
'logouttext'                 => '<strong>ەندٸ جٷيەدەن شىقتىڭىز.</strong><br />
بۇل كومپييۋتەردەن ٵلٸ دە جٷيەگە كٸرمەستەن {{SITENAME}} جوباسىن
شولۋىڭىز مٷمكٸن, نەمەسە باسقا پايدالانۋشىنىڭ جٷيەگە كٸرۋٸ مٷمكٸن.
كەيبٸر بەتتەردە ٵلٸ دە جٷيەگە كٸرگەنٸڭٸزدەي كٶرٸنۋٸ مٷمكٸندٸگٸن
ەسكەرتەمٸز; بۇل شولعىشتىڭ قوسالقى قالتاسىن بوساتۋ ارقىلى شەشٸلەدٸ.',
'welcomecreation'            => '== قوش كەلدٸڭٸز, $1! ==

تٸركەلگٸڭٸز جاسالدى. {{SITENAME}} باپتاۋىڭىزدى قالاۋىڭىزبەن ٶزگەرتۋدٸ ۇمىتپاڭىز.',
'loginpagetitle'             => 'قاتىسۋشى كٸرۋٸ',
'yourname'                   => 'قاتىسۋشى اتىڭىز',
'yourpassword'               => 'قۇپييا سٶزٸڭٸز',
'yourpasswordagain'          => 'قۇپييا سٶزدٸ قايتالاپ ەنگٸزٸڭٸز',
'remembermypassword'         => 'مەنٸڭ كٸرگەنٸمدٸ بۇل كومپييۋتەردە ۇمىتپا',
'yourdomainname'             => 'جەلٸ ٷيشٸگٸڭٸز',
'externaldberror'            => 'وسىندا سىرتقى تەڭدەستٸرۋ دەرەكقورىندا قاتە بولدى, نەمەسە سىرتقى تٸركەلگٸڭٸزدٸ جاڭالاۋعا رۇقسات جوق.',
'loginproblem'               => '<b>كٸرۋٸڭٸز كەزٸندە وسىندا قيىندىققا تاپ بولدىق.</b><br />تاعى دا قايتالاپ قاراڭىز.',
'alreadyloggedin'            => '<strong>$1 دەگەن قاتىسۋشى, كٸرٸپسٸز تٷگە!</strong><br />',

'login'                      => 'كٸرۋ',
'loginprompt'                => '{{SITENAME}} تورابىنا كٸرۋ ٷشٸن «cookies» قاسيەتٸن ەندٸرۋٸڭٸز قاجەت.',
'userlogin'                  => 'كٸرۋ / تٸركەلگٸ جاساۋ',
'logout'                     => 'شىعۋ',
'userlogout'                 => 'شىعۋ',
'notloggedin'                => 'كٸرمەگەنسٸز',
'nologin'                    => 'تٸركەلگٸڭٸز جوق پا؟ $1.',
'nologinlink'                => 'جاساڭىز',
'createaccount'              => 'تٸركەلگٸ جاسا',
'gotaccount'                 => 'تٸركەلگٸڭٸز بار ما؟  $1.',
'gotaccountlink'             => 'كٸرٸڭٸز',
'createaccountmail'          => 'ە-پوشتامەن',
'badretype'                  => 'ەنگٸزگەن قۇپييا سٶزدەرٸڭٸز بٸر بٸرٸنە سٵيكەس ەمەس.',
'userexists'                 => 'ەنگٸزگەن قاتىسۋشى اتىڭىزدى بٸرەۋ پايدالانىپ جاتىر. باسقا اتاۋ تانداڭىز.',
'youremail'                  => 'ە-پوشتا جايىڭىز *:',
'username'                   => 'قاتىسۋشى اتىڭىز:',
'uid'                        => 'قاتىسۋشى تەڭدەستٸرۋٸڭٸز:',
'yourrealname'               => 'شىن اتىڭىز *:',
'yourlanguage'               => 'تٸلٸڭٸز:',
'yourvariant'                => 'تٷرٸ',
'yournick'                   => 'لاقاپ اتىڭىز:',
'badsig'                     => 'قام قولتاڭباڭىز جارامسىز; HTML بەلگٸشەلەرٸن تەكسەرٸڭٸز.',
'email'                      => 'ە-پوشتاڭىز',
'prefs-help-email-enotif'    => 'ەگەر سونى باپتاساڭىز, وسى ە-پوشتا جايى سٸزگە ەسكەرتۋ حات جٸبەرۋگە قولدانىلادى.',
'prefs-help-realname'        => '* شىن اتىڭىز (مٸندەتتٸ ەمەس): ەنگٸزسەڭٸز, شىعارماڭىزدىڭ اۋتورلىعىن بەلگٸلەۋٸ ٷشٸن قولدانىلادى.',
'loginerror'                 => 'كٸرۋ قاتەسٸ',
'prefs-help-email'           => '* ە-پوشتاڭىز (مٸندەتتٸ ەمەس): «قاتىسۋشى» نەمەسە «قاتىسۋشى تالقىلاۋ» بەتٸڭٸزدەر ارقىلى باسقالارعا بايلانىسۋ مٷمكٸندٸك بەرەدٸ. ٶزٸڭٸزدٸڭ كٸم ەكەنٸڭٸزدٸ بٸلدٸرتپەيدٸ.',
'nocookiesnew'               => 'قاتىسۋشى تٸركەلگٸسٸ جاسالدى, تەك ٵلٸ كٸرمەگەنسٸز. {{SITENAME}} جوباسىنا قاتىسۋشى كٸرۋ ٷشٸن «cookies» قاسيەتٸ قاجەت. شولعىشىڭىزدا «cookies» قاسيەتٸ ٶشٸرٸلگەن. سونى ەندٸرٸڭٸز دە جاڭا قاتىسۋشى اتىڭىزدى جٵنە قۇپييا سٶزٸڭٸزدٸ ەنگٸزٸپ كٸرٸڭٸز.',
'nocookieslogin'             => 'قاتىسۋشى كٸرۋ ٷشٸن {{SITENAME}} جوباسى «cookies» قاسيەتٸن قولدانادى. شولعىشىڭىزدا «cookies» قاسيەتٸ ٶشٸرٸلگەن. سونى ەندٸرٸڭٸز دە قايتالاپ كٸرٸڭٸز.',
'noname'                     => 'قاتىسۋشى اتىن دۇرىس ەنگٸزبەدٸڭٸز.',
'loginsuccesstitle'          => 'كٸرۋٸڭٸز سٵتتٸ ٶتتٸ',
'loginsuccess'               => "'''سٸز ەندٸ {{SITENAME}} جوباسىنا «$1» رەتٸندە كٸرٸپ وتىرسىز.'''",
'nosuchuser'                 => 'مىندا «$1» اتاۋلى قاتىسۋشى جوق. ەملەڭٸزدٸ تەكسەرٸڭٸز, نەمەسە جاڭا تٸركەلگٸ جاساڭىز.',
'nosuchusershort'            => 'مىندا «$1» دەگەن قاتىسۋشى اتاۋى جوق. ەملەڭٸزدٸ تەكسەرٸڭٸز.',
'nouserspecified'            => 'قاتىسۋشى اتىن ەنگٸزٸۋٸڭٸز قاجەت.',
'wrongpassword'              => 'ەنگٸزگەن قۇپييا سٶز جارامسىز. قايتالاپ كٶرٸڭٸز.',
'wrongpasswordempty'         => 'قۇپييا سٶز بوستى بوپتى. قايتالاپ كٶرٸڭٸز.',
'mailmypassword'             => 'قۇپييا سٶزٸمدٸ حاتپەن جٸبەر',
'passwordremindertitle'      => 'قۇپييا سٶز تۋرالى {{SITENAME}} جوباسىنىڭ ەسكەرتۋٸ',
'passwordremindertext'       => 'كەيبٸرەۋ (IP جايى: $1, بٵلكٸم, ٶزٸڭٸز بولارسىز)
{{SITENAME}} ٷشٸن بٸزدەن جاڭا قۇپييا سٶزٸن جٸبەرۋٸن سۇرانىسقان ($4).
«$2» قاتىسۋشىنىڭ قۇپييا سٶزٸ «$3» بولدى ەندٸ.
قازٸر كٸرۋٸڭٸز جٵنە قۇپييا سٶزٸڭٸزدٸ اۋىسترۋىڭىز قاجەت.

ەگەر باسقا بٸرەۋ بۇل سۇرانىستى جاساسا, نەمەسە قۇپييا سٶزٸڭٸزدٸ ۇمىتساڭىز دا,
جٵنە بۇنى ٶزگەرتكٸڭٸز كەلمەسە دە, وسى حابارلاماعا اڭعارماۋىڭىزعا دا بولادى,
ەسكٸ قۇپييا سٶزٸڭٸزدٸ ٵرٸعاراي قولدانىپ.',
'noemail'                    => 'مىندا «$1» قاتىسۋشىنىڭ ە-پوشتاسى جوق.',
'passwordsent'               => 'جاڭا قۇپييا سٶز «$1» ٷشٸن
تٸركەلگەن ە-پوشتا جايىنا جٸبەرٸلدٸ.
قابىلداعاننان كەيٸن كٸرگەندە سونى ەنگٸزٸڭٸز.',
'blocked-mailpassword'       => 'IP جايىڭىزدان ٶڭدەۋ بۇعاتتالعان, سوندىقتان
قيياناتشىلىقتان ساقتانۋ ٷشٸن قۇپييا سٶز جٸبەرۋ قىزمەتٸنٸڭ ٵرەكەتٸ رۇقسات ەتٸلمەيدٸ.',
'eauthentsent'               => 'كۋٵلاندىرۋ حاتى اتالعان ە-پوشتا جايىنا جٸبەرٸلدٸ.
باسقا ە-پوشتا حاتىن جٸبەرۋدٸڭ الدىنان, تٸركەلگٸ شىنىنان سٸزدٸكٸ ەكەنٸن
كۋٵلاندىرۋ ٷشٸن حاتتاعى نۇسقاۋلارعا ەرٸڭٸز.',
'throttled-mailpassword'     => 'سوڭعى $1 ساعاتتا قۇپييا سٶز ەسكەرتۋ حاتى جٸبەرٸلدٸ تٷگە.
قيياناتشىلىققا كەدەرگٸ بولۋ ٷشٸن, $1 ساعات سايىن تەك بٸر عانا قۇپييا سٶز ەسكەرتۋ
حاتى جٸبەرٸلەدٸ.',
'mailerror'                  => 'حات جٸبەرۋ قاتەسٸ: $1',
'acct_creation_throttle_hit' => 'عافۋ ەتٸڭٸز, سٸز $1 تٸركەلگٸ جاساپسىز تٷگە. ونان ارتىق ٸستەي المايسىز.',
'emailauthenticated'         => 'ە-پوشتا جايىڭىز كۋٵلاندىرىلعان كەزٸ: $1.',
'emailnotauthenticated'      => 'ە-پوشتا جايىڭىز ٵلٸ كۋٵلاندىرعان جوق.
تٶمەندەگٸ قاسيەتتتەر ٷشٸن ەشقانداي حات جٸبەرٸلمەيدٸ.',
'noemailprefs'               => 'وسى قاسيەتتەر ٸستەۋٸ ٷشٸن ە-پوشتا جايىڭىزدى ەنگٸزٸڭٸز.',
'emailconfirmlink'           => 'ە-پوشتا جايىڭىزدى كۋٵلاندىرىڭىز',
'invalidemailaddress'        => 'وسى ە-پوشتا جايدا جارامسىز پٸشٸم بولعان, قابىل ەتٸلمەيدٸ.
دۇرىس پٸشٸمدەلگەن جايدى ەنگٸزٸڭٸز, نە اۋماقتى بوس قالدىرىڭىز.',
'accountcreated'             => 'تٸركەلگٸ جاسالدى',
'accountcreatedtext'         => '$1 ٷشٸن قاتىسۋشى تٸركەلگٸسٸ جاسالدى.',

# Password reset dialog
'resetpass'               => 'تٸركەلگٸنٸڭ قۇپييا سٶزٸن بۇرىنعى قالىپىنا كەلتٸرۋ',
'resetpass_announce'      => 'حاتپەن جٸبەرٸلگەن ۋاقىتشا بەلگٸلەمەمەن كٸرٸپسٸز. تٸركەلۋدٸ بٸتٸرۋ ٷشٸن جاڭا قۇپييا سٶزٸڭٸزدٸ مىندا ەنگٸزٸڭٸز:',
'resetpass_header'        => 'قۇپييا سٶزدٸ بۇرىنعى قالىپىنا كەلتٸرۋ',
'resetpass_submit'        => 'قۇپييا سٶزدٸ قالاڭىز دا كٸرٸڭٸز',
'resetpass_success'       => 'قۇپييا سٶزٸڭٸز سٵتتٸ ٶزگەرتٸلدٸ! ەندٸ كٸرٸڭٸز…',
'resetpass_bad_temporary' => 'ۋاقىتشا قۇپييا سٶز جارامسىز. مٷمكٸن قۇپييا سٶزٸڭٸزدٸ ٶزگەرتكەن بولارسىز نەمەسە جاڭا ۋاقىتشا قۇپييا سٶز سۇراعان بولارسىز.',
'resetpass_forbidden'     => 'بۇل ۋيكيدە قۇپييا سٶزدەر ٶزگەرتٸلمەيدٸ',
'resetpass_missing'       => 'ٷلگٸت دەرەكتەرٸ جوق.',

# Edit page toolbar
'bold_sample'     => 'جۋان مٵتٸن',
'bold_tip'        => 'جۋان مٵتٸن',
'italic_sample'   => 'قيعاش مٵتٸن',
'italic_tip'      => 'قيعاش مٵتٸن',
'link_sample'     => 'سٸلتەمە اتاۋى',
'link_tip'        => 'ٸشكٸ سٸلتەمە',
'extlink_sample'  => 'http://www.example.com سٸلتەمە اتاۋى',
'extlink_tip'     => 'سىرتقى سٸلتەمە (الدىنان http:// ەنگٸزۋٸن ۇمىتپاڭىز)',
'headline_sample' => 'تاقىرىپ مٵتٸنٸ',
'headline_tip'    => '1-شٸ دەڭگەيلٸ تاقىرىپ',
'math_sample'     => 'فورمۋلانى مىندا ەنگٸزٸڭٸز',
'math_tip'        => 'ماتەماتيكا فورمۋلاسى (LaTeX)',
'nowiki_sample'   => 'پٸشٸمدەلمەيتٸن مٵتٸندٸ وسىندا ەنگٸزٸڭٸز',
'nowiki_tip'      => 'ۋيكي پٸشٸمٸن ەلەمەۋ',
'image_sample'    => 'Example.jpg',
'image_tip'       => 'كٸرٸكتٸرٸلگەن سۋرەت',
'media_sample'    => 'Example.ogg',
'media_tip'       => 'تاسپا فايلىنىڭ سٸلتەمەسٸ',
'sig_tip'         => 'قولتاڭباڭىز جٵنە ۋاقىت بەلگٸسٸ',
'hr_tip'          => 'دەرەلەي سىزىق (ٷنەمدٸ قولدانىڭىز)',

# Edit pages
'summary'                   => 'سيپاتتاماسى',
'subject'                   => 'تاقىرىبى/باسى',
'minoredit'                 => 'بۇل شاعىن تٷزەتۋ',
'watchthis'                 => 'بەتتٸ باقىلاۋ',
'savearticle'               => 'بەتتٸ ساقتا!',
'preview'                   => 'قاراپ شىعۋ',
'showpreview'               => 'قاراپ شىعۋ',
'showlivepreview'           => 'تۋرا قاراپ شىعۋ',
'showdiff'                  => 'ٶزگەرٸستەردٸ كٶرسەت',
'anoneditwarning'           => "'''نازار سالىڭىز:''' سٸز جٷيەگە كٸرمەگەنسٸز. IP جايىڭىز بۇل بەتتٸڭ ٶڭدەۋ تاريحىندا جازىلىپ الىنادى.",
'missingsummary'            => "'''ەسكەرتۋ:''' تٷزەتۋ سيپاتتاماسىن ەنگٸزبەپسٸز. «ساقتاۋ» تٷيمەسٸن تاعى باسساڭىز, تٷزەتۋٸڭٸز مٵندەمەسٸز ساقتالادى.",
'missingcommenttext'        => 'تٶمەندە مٵندەمەڭٸزدٸ ەنگٸزٸڭٸز.',
'missingcommentheader'      => "'''ەسكەرتۋ:''' بۇل مٵندەمەگە تاقىرىپ/باسجول جەتٸستٸرمەپسٸز. ەگەر تاعى دا ساقتاۋ تٷيمەسٸن نۇقىساڭىز, تٷزەتۋٸڭٸز سولسىز ساقتالادى.",
'summary-preview'           => 'سيپاتتاماسىن قاراپ شىعۋ',
'subject-preview'           => 'تاقىرىبىن/باسىن قاراپ شىعۋ',
'blockedtitle'              => 'پايدالانۋشى بۇعاتتالعان',
'blockedtext'               => "<big>'''قاتىسۋشى اتىڭىز نە IP جايىڭىز بۇعاتتالعان.'''</big>

بۇعاتتاۋدى $1 ٸستەگەن. بەلگٸلەنگەن سەبەبٸ: ''$2''.

وسى بۇعاتتاۋدى تالقىلاۋ ٷشٸن $1 دەگەنمەن نە باسقا [[{{{{ns:mediawiki}}:grouppage-sysop}}|ٵكٸمشٸمەن]] قاتىناسۋىڭىزعا بولادى.
[[{{ns:special}}:Preferences|تٸركەلگٸ باپتاۋلارىن]] قولدانىپ جارامدى ە-پوشتا جايىن ەنگٸزگەنشە دەيٸن
«قاتىسۋشىعا حات جازۋ» قاسيەتٸن پايدالانىلمايسىز. اعىمدىق IP جايىڭىز: $3, جٵنە بۇعاتاۋى نٶمٸرٸ: $5. سونىڭ بٸرەۋٸن, نەمەسە ەكەۋٸن دە ٵربٸر سۇرانىسىڭىزعا قوسىڭىز.",
'blockedoriginalsource'     => "تٶمەندە '''$1''' دەگەننٸڭ قاينارى كٶرسەتٸلەدٸ:",
'blockededitsource'         => "تٶمەندە '''$1''' دەگەنگە جاسالعان '''تٷزەتۋڭٸزدٸڭ''' مٵتٸنٸ كٶرسەتٸلەدٸ:",
'whitelistedittitle'        => 'ٶڭدەۋ ٷشٸن كٸرۋٸڭٸز جٶن.',
'whitelistedittext'         => 'بەتتەردٸ ٶڭدەۋ ٷشٸن $1 جٶن.',
'whitelistreadtitle'        => 'وقۋ ٷشٸن كٸرۋٸڭٸز جٶن',
'whitelistreadtext'         => 'بەتتەردٸ وقۋ ٷشٸن [[{{ns:special}}:Userlogin|كٸرۋٸڭٸز]] جٶن.',
'whitelistacctitle'         => 'سٸزگە تٸركەلگٸ جاساۋعا رۇقسات بەرٸلمەگەن',
'whitelistacctext'          => 'وسى ۋيكيدە باسقالارعا تٸركەلگٸ جاساۋ ٷشٸن [[{{ns:special}}:Userlogin|كٸرۋٸڭٸز]] قاجەت جٵنە جاناسىمدى رۇقساتتارىن بيلەۋ قاجەت.',
'confirmedittitle'          => 'ە-پوشتا جايىن كۋٵلاندىرۋ حاتىن قايتا ٶڭدەۋ قاجەت',
'confirmedittext'           => 'بەتتەردٸ ٶڭدەۋ ٷشٸن الدىن الا ە-پوشتا جايىڭىزدى كۋٵلاندىرۋىڭىز قاجەت. جايىڭىزدى [[{{ns:Special}}:Preferences|قاتىسۋشى باپتاۋى]] ارقىلى ەنگٸزٸڭٸز جٵنە تەكسەرتكٸڭٸز.',
'nosuchsectiontitle'        => 'بۇل بٶلٸم ەمەس',
'nosuchsectiontext'         => "جوق بٶلٸمدٸ ٶڭدەۋدٸ تالاپ ەتٸپسٸز. مىندا \$1 دەگەن بٶلٸم جوق ەكەن, ٶڭدەۋلەرٸڭٸزدٸ ساقتاۋ ٷشٸن ورىن جوق.",
'loginreqtitle'             => 'كٸرۋٸڭٸز قاجەت',
'loginreqlink'              => 'كٸرۋ',
'loginreqpagetext'          => 'باسقا بەتتەردٸ كٶرۋ ٷشٸن سٸز $1 بولۋىڭىز قاجەت.',
'accmailtitle'              => 'قۇپييا سٶز جٸبەرٸلدٸ.',
'accmailtext'               => '$2 جايىنا «$1» قۇپييا سٶزٸ جٸبەرٸلدٸ.',
'newarticle'                => '(جاڭا)',
'newarticletext'            => 'سٸلتەمەگە ەرٸپ ٵلٸ باستالماعان بەتكە
كەلٸپسٸز. بەتتٸ باستاۋ ٷشٸن, تٶمەندەگٸ اۋماقتا مٵتٸنٸڭٸزدٸ
تەرٸڭٸز (كٶبٸرەك اقپارات ٷشٸن [[{{{{ns:mediawiki}}:helppage}}|انىقتاما بەتٸن]]
قاراڭىز).ەگەر جاڭىلعاننان وسىندا كەلگەن بولساڭىز, شولعىشىڭىز
«ارتقا» دەگەن تٷيمەسٸن نۇقىڭىز.',
'anontalkpagetext'          => "----''بۇل تٸركەلگٸسٸز (نەمەسە تٸركەلگٸسٸن قولدانباعان) پايدالانۋشىنىڭ تالقىلاۋ بەتٸ. وسى پايدالانۋشىنى بٸز تەك ساندىق IP جايىمەن تەڭدەستٸرەمٸز. وسىنداي IP جايلار بٸرنەشە پايدالانۋشىعا ورتاق بولۋى مٷمكٸن. ەگەر سٸز تٸركەلگٸسٸز پايدالانۋشى بولساڭىز جٵنە سٸزگە قاتىسسىز مٵندەمەلەر جٸبەرٸلگەنٸن سەزسەڭٸز, باسقا تٸركەلگٸسٸز پايدالانۋشىلارمەن ارالاستىرماۋى ٷشٸن [[{{ns:special}}:Userlogin|تٸركەلگٸ جاساڭىز نە كٸرٸڭٸز]].''",
'noarticletext'             => 'بۇل بەتتە اعىمدا ەش مٵتٸن جوق, باسقا بەتتەردەن وسى بەت اتاۋىن [[{{ns:special}}:Search/{{PAGENAME}}|ٸزدەپ كٶرۋٸڭٸزگە]] نەمەسە وسى بەتتٸ [{{fullurl:{{FULLPAGENAME}}|action=edit}} تٷزەتۋٸڭٸزگە] بولادى.',
'clearyourcache'            => "'''اڭعارتپا:''' ساقتاعاننان كەيٸن ٶزگەرٸستەردٸ كٶرۋ ٷشٸن شولعىش قوسالقى قالتاسىن بوساتۋ كەرەگٸ مٷمكٸن. '''Mozilla  / Safari:''' ''Shift'' پەرنەسٸن باسىپ تۇرىپ ''Reload'' (''قايتا جٷكتەۋ'') تٷيمەسٸن نۇقىڭىز (نە ''Ctrl-Shift-R'' باسىڭىز); ''IE:'' ''Ctrl-F5'' باسىڭىز; '''Opera / Konqueror''' ''F5'' پەرنەسٸن باسىڭىز.",
'usercssjsyoucanpreview'    => '<strong>باسالقى:</strong> ساقتاۋ الدىندا جاڭا CSS/JS فايلىن تەكسەرۋ ٷشٸن «قاراپ شىعۋ» تٷيمەسٸن قولدانىڭىز.',
'usercsspreview'            => "'''مىناۋ CSS مٵتٸنٸن تەك قاراپ شىعۋ ەكەنٸن ۇمىتپاڭىز, ول ٵلٸ ساقتالعان جوق!'''",
'userjspreview'             => "'''مىناۋ JavaScript قاتىسۋشى باعدارلاماسىن تەكسەرۋ/قاراپ شىعۋ ەكەنٸن ۇمىتپاڭىز, ول ٵلٸ ساقتالعان جوق!'''",
'userinvalidcssjstitle'     => "'''نازار سالىڭىز:''' بۇل «$1» دەگەن بەزەندٸرۋ مٵنەرٸ ەمەس. پايدالانۋشىنىڭ .css جٵنە .js فايل اتاۋى كٸشٸ ٵرٸپپپەن جازىلۋ تيٸستٸ ەكەنٸن ۇمىتپاڭىز, مىسالعا {{ns:user}}:Foo/monobook.css دەگەندٸ {{ns:user}}:Foo/Monobook.css دەگەنمەن سالىستىرىپ قاراڭىز.",
'updated'                   => '(جاڭارتىلعان)',
'note'                      => '<strong>اڭعارتپا:</strong>',
'previewnote'               => '<strong>مىناۋ تەك قاراپ شىعۋ ەكەنٸن ۇمىتپاڭىز; تٷزەتۋلەر ٵلٸ ساقتالعان جوق!</strong>',
'previewconflict'           => 'بۇل قاراپ شىعۋ جوعارىداعى ٶڭدەۋ اۋماعىنداعى مٵتٸنگە ساقتاعان كەزٸندەگٸ دەي ىقپال ەتەدٸ.',
'session_fail_preview'      => '<strong>عافۋ ەتٸڭٸز! سەسسييا دەرەكتەرٸ ىسىراپ قالعاندىقتان ٶڭدەۋٸڭٸزدٸ جٶندەي المايمىز.
مٵتٸنٸڭٸزدٸ ساقتاپ قايتالاپ كٶرٸڭٸز. ەگەر ٵلٸ ٸس ٶتپەيتٸن بولسا, شىعىپ جٵنە كەرٸ كٸرٸپ كٶرٸڭٸز.</strong>',
'session_fail_preview_html' => "<strong>عافۋ ەتٸڭٸز! سەسسييا دەرەكتەرٸ ىسىراپ قالعاندىقتان ٶڭدەۋٸڭٸزدٸ جٶندەي المايمىز.</strong>

''وسى ۋيكيدە قام HTML ەندٸرٸلگەن, JavaScript شابۋىلداردان قورعانۋ ٷشٸن الدىن الا قاراپ شىعۋ جاسىرىلعان.''

<strong>ەگەر بۇل ٶڭدەۋ ادال تالاپ بولسا, قايتارىپ كٶرٸڭٸز. ەگەر ٵلٸ دە ٸستەمەسە, شىعىپ, سوسىن كەرٸ كٸرٸپ كٶرٸڭٸز.</strong>",
'importing'                 => 'سىرتتان الۋدا: $1',
'editing'                   => 'ٶڭدەلۋدە: $1',
'editinguser'               => 'ٶڭدەلۋدە: <b>$1</b> دەگەن قاتىسۋشى',
'editingsection'            => 'ٶڭدەلۋدە: $1 (بٶلٸمٸ)',
'editingcomment'            => 'ٶڭدەلۋدە: $1 (مٵندەمەسٸ)',
'editconflict'              => 'ٶڭدەۋ قاقتىعىسى: $1',
'explainconflict'           => 'وسى بەتتٸ سٸز ٶڭدەي باستاعاندا باسقا كەيبٸرەۋ بەتتٸ ٶزگەرتكەن.
جوعارعى اۋماقتا بەتتٸڭ اعىمدىق مٵتٸنٸ بار.
تٶمەنگٸ اۋماقتا سٸز ٶزگەرتكەن مٵتٸنٸ كٶرسەتٸلەدٸ.
ٶزگەرتۋٸڭٸزدٸ اعىمدىق مٵتٸنگە ٷستەۋٸڭٸز جٶن.
"بەتتٸ ساقتا!" تٷيمەسٸنە باسقاندا
<b>تەك</b> جوعارعى اۋماقتاعى مٵتٸن ساقتالادى.<br />',
'yourtext'                  => 'مٵتٸنٸڭٸز',
'storedversion'             => 'ساقتالعان نۇسقاسى',
'nonunicodebrowser'         => '<strong>اڭعارتپا: شولعىشىڭىز Unicode بەلگٸلەۋٸنە ٷيلەسٸمدٸ ەمەس, سوندىقتان لاتىن ەمەس ٵرٸپتەرٸ بار بەتتەردٸ ٶڭدەۋ زٸل بولۋ مٷمكٸن. جۇمىس ٸستەۋگە ىقتيمالدىق بەرۋ ٷشٸن, تٶمەنگٸ ٶڭدەۋ اۋماعىندا ASCII ەمەس ٵرٸپتەر ونالتىلىق سانىمەن كٶرسەتٸلەدٸ</strong>.',
'editingold'                => '<strong>اڭعارتپا: وسى بەتتٸڭ ەرتەرەك نۇسقاسىن
ٶڭدەپ جاتىرسىز.
بۇنى ساقتاساڭىز, وسى نۋسقادان سوڭعى بارلىق تٷزەتۋلەر جويىلادى.</strong>',
'yourdiff'                  => 'ايىرمالار',
'copyrightwarning'          => '{{SITENAME}} جوباسىنا قوسىلعان بٷكٸل ٷلەس $2 (كٶبٸرەك اقپارات ٷشٸن: $1) قۇجاتىنا ساي جٸبەرٸلگەن بولىپ سانالادى. ەگەر جازۋىڭىزدىڭ ەركٸن كٶشٸرٸلٸپ تٷزەتٸلۋٸن قالاماساڭىز, مىندا ۇسىنباۋىڭىز جٶن.<br />
تاعى, قوسقان ٷلەسٸڭٸز - ٶزٸڭٸزدٸڭ جازعانىعىز, نە اشىق اقپارات كٶزدەرٸنەن الىنعان ماعلۇمات بولعانىن ۋٵدە ەتەسٸز.<br />
<strong>اۆتورلىق قۇقىقپەن قورعاۋلى اقپاراتتى رۇقساتسىز قوسپاڭىز!</strong>',
'copyrightwarning2'         => 'ەستە تۇرسىن: بارلىق {{SITENAME}} جوباسىنا بەرٸلگەن ٷلەستەر باسقا ۋلەس بەرۋشٸلەرمەن تٷزەتۋگە, ٶزگەرتۋگە, نە الاستانۋعا مٷمكٸن. العىسسىز تٷزەتۋگە ەنجارلان بولساڭىز, وندا شىعارماڭىزدى مىندا جارييالاماڭىز.<br />
تاعى, وسىنى ٶزٸڭٸز جازعانىڭىزدى, نە بارشا قازىناسىنان, نەمەسە سونداي-اق اقىسىز اشىق قاينارىنان كٶشٸرگەنٸڭٸزدٸ
دٵل وسىنداي بٸزگە مٸندەتتەمە بەرەسٸز (كٶبٸرەك اقپارات ٷشٸن $1 قۋجاتىن قاراڭىز).<br />
<strong>اۋتورلىق قۇقىقپەن قورعاۋلى اقپاراتتى رۇقساتسىز قوسپاڭىز!</strong>',
'longpagewarning'           => '<strong>نازار سالىڭىز: بۇل بەتتٸڭ مٶلشەرٸ — $1 KB; كەيبٸر
شولعىشتاردا بەت مٶلشەرٸ 32 KB جەتسە نە ونى اسسا ٶڭدەۋ كٷردەلٸ بولۋى مٷمكٸن.
بەتتٸ بٸرنەشە كٸشكٸن بٶلٸمدەرگە بٶلٸپ كٶرٸڭٸز.</strong>',
'longpageerror'             => '<strong>قاتە: جٸبەرەتٸن مٵتٸنٸڭٸزدٸن مٶلشەرٸ — $1 KB, ەڭ كٶبٸ $2 KB
رۇقسات ەتٸلگەن مٶلشەرٸنەن اسقان. بۇل ساقتاي الىنبايدى.</strong>',
'readonlywarning'           => '<strong>نازار سالىڭىز: دەرەكقور جٶندەتۋ ٷشٸن قۇلىپتالعان,
سوندىقتان دٵل قازٸر تٷزەتۋٸڭٸزدٸ ساقتاي المايسىز. سوسىن قولدانۋعا ٷشٸن مٵتٵنٸڭٸزدٸ كٶشٸرٸپ,
ٶز كومپٷتەرٸڭٸزدە فايلعا ساقتاڭىز.</strong>',
'protectedpagewarning'      => '<strong>نازار سالىڭىز: بۇل بەت قورعالعان. تەك ٵكٸمشٸ رۇقساتى بار قاتىسۋشىلار ٶڭدەۋ جاساي الادى.</strong>',
'semiprotectedpagewarning'  => "'''اڭعارتپا:''' بەت جارتىلاي قورعالعان, سوندىقتان وسىنى تەك رۇقساتى بار قاتىسۋشىلار ٶڭدەي الادى.",
'cascadeprotectedwarning'   => "'''نازار سالىڭىز''': بۇل بەت قۇلىپتالعان, ەندٸ تەك ٵكٸمشٸ قۇقىقتارى بار پايدالانۋشىلار بۇنى ٶڭدەي الادى.بۇنىڭ سەبەبٸ: بۇل بەت «باۋلى قورعاۋى» بار كەلەسٸ {{PLURAL:$1|بەتكە|بەتتەرگە}} كٸرٸكتٸرٸلگەن:",
'templatesused'             => 'بۇل بەتتە قولدانىلعان ٷلگٸلەر:',
'templatesusedpreview'      => 'بۇنى قاراپ شىعۋعا قولدانىلعان ٷلگٸلەر:',
'templatesusedsection'      => 'بۇل بٶلٸمدە قولدانىلعان ٷلگٸلەر:',
'template-protected'        => '(قورعالعان)',
'template-semiprotected'    => '(جارتىلاي قورعالعان)',
'edittools'                 => '<!-- مىنداعى ماعلۇمات ٶڭدەۋ جٵنە قوتارۋ ٷلگٸتترٸڭٸڭ استىندا كٶرسەتٸلەدٸ. -->',
'nocreatetitle'             => 'بەتتٸ باستاۋ شەكتەلگەن',
'nocreatetext'              => 'بۇل توراپتا جاڭا بەت باستاۋى شەكتەلگەن.
كەرٸ قايتىپ بار بەتتٸ ٶڭدەۋٸڭٸزگە بولادى, نەمەسە [[{{ns:special}}:Userlogin|كٸرۋٸڭٸزگە نە تٸركەلگٸ جاساۋعا]] بولادى.',

# "Undo" feature
'undo-success' => 'بۇل ٶڭدەۋدٸڭ بولدىرماۋى اتقارىلادى. تالابىڭىزدى بٸلٸپ تۇرىپ الدىن الا تٶمەندەگٸ سالىستىرۋدى تەكسەرٸپ شىعىڭىز دا, تٷزەتۋ بولدىرماۋىن بٸتٸرۋ ٷشٸن تٶمەندەگٸ ٶزگەرٸستەردٸ ساقتاڭىز.',
'undo-failure' => 'بۇل ٶڭدەۋدٸڭ بولدىرماۋى اتقارىلمايدى, سەبەبٸ: كەدەرگٸ جاساعان ارالاس تٷزەتۋلەر بار.',
'undo-summary' => '[[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|تالقىلاۋى]]) ٸستەگەن $1 نۇسقاسىن بولدىرماۋ',

# Account creation failure
'cantcreateaccounttitle' => 'تٸركەلگٸ جاسالمادى',
'cantcreateaccounttext'  => 'وسى IP جايدان (<b>$1</b>) تٸركەلگٸ جاساۋى بۇعاتتالعان.
بٵلكٸم سەبەبٸ, وقۋ ورنىڭىزدان, نەمەسە ينتەرنەت جەتكٸزۋشٸدەن
ٷزبەي بۇزاقىلىق بولعانى.',

# History pages
'revhistory'                  => 'نۇسقالار تاريحى',
'viewpagelogs'                => 'وسى بەتكە قاتىستى جۋرنالداردى قاراۋ',
'nohistory'                   => 'وسى بەتتٸنٸڭ نۇسقالار تاريحى جوق.',
'revnotfound'                 => 'نۇسقا تابىلمادى',
'revnotfoundtext'             => 'وسى سۇرانىسقان بەتتٸڭ ەسكٸ نۇسقاسى تابىلعان جوق.
وسى بەتتٸ اشۋعا پايدالانعان URL جايىن قايتا تەكسەرٸپ شىعىڭىز.',
'loadhist'                    => 'بەت تاريحىن جٷكتەۋٸ',
'currentrev'                  => 'اعىمدىق نۇسقاسى',
'revisionasof'                => '$1 كەزٸندەگٸ نۇسقاسى',
'revision-info'               => '$1 كەزٸندەگٸ $2 جاساعان نۇسقاسى',
'previousrevision'            => '← ەسكٸلەۋ نۇسقاسى',
'nextrevision'                => 'جاڭالاۋ نۇسقاسى →',
'currentrevisionlink'         => 'اعىمدىق نۇسقاسى',
'cur'                         => 'اعىم.',
'next'                        => 'كەل.',
'last'                        => 'سوڭ.',
'orig'                        => 'تٷپ.',
'page_first'                  => 'العاشقىسىنا',
'page_last'                   => 'سوڭعىسىنا',
'histlegend'                  => 'ايىرماسىن كٶرۋ: سالىستىرامىن دەگەن نۇسقالاردى تاڭداپ, نە <Enter> پەرنەسٸن, نە تٶمەندەگٸ تٷيمەنٸ باسىڭىز.<br />
شارتتى بەلگٸلەر: (اعىم.) = اعىمدىق نۇسقامەن ايىرماسى,
(سوڭ.) = الدىڭعى نۇسقامەن ايىرماسى, ش = شاعىن تٷزەتۋ',
'deletedrev'                  => '[جويىلعان]',
'histfirst'                   => 'ەڭ العاشقىسىنا',
'histlast'                    => 'ەڭ سوڭعىسىنا',
'historysize'                 => '($1 B)',
'historyempty'                => '(بوس)',

# Revision feed
'history-feed-title'          => 'نۇسقا تاريحى',
'history-feed-description'    => 'مىنا ۋيكيدەگٸ بۇل بەتتٸڭ نۇسقا تاريحى',
'history-feed-item-nocomment' => '$2 كەزٸندەگٸ $1 دەگەن', # user at time
'history-feed-empty'          => 'سۇرانىسقان بەت جوق بولدى.
ول مىنا ۋيكيدەن جويىلعان, نەمەسە اتاۋى اۋىستىرىلعان.
وسىعان قاتىستى جاڭا بەتتەردٸ [[{{ns:special}}:Search|بۇل ۋيكيدەن ٸزدەپ]] كٶرٸڭٸز.',

# Revision deletion
'rev-deleted-comment'         => '(مٵندەمە الاستاتىلدى)',
'rev-deleted-user'            => '(قاتىسۋشى اتى الاستاتىلدى)',
'rev-deleted-event'           => '(جازبا جويىلدى)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
وسى بەتتٸڭ نۇسقاسى جارييا مۇراعاتتارىنان الاستاتىلعان.
بۇل جايتقا [{{fullurl:{{ns:special}}:Log/delete|page={{FULLPAGENAMEE}}}} جويۋ جۋرنالىندا] ەگجەي-تەگجەي مٵلٸمەتتەرٸ بولۋى مٷمكٸن.
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
وسى بەتتٸڭ نۇسقاسى جارييا مۇراعاتتارىنان الاستاتىلعان.
سونى وسى توراپتىڭ ٵكٸمشٸسٸ بوپ كٶرۋٸڭٸز مٷمكٸن;
بۇل جايتقا [{{fullurl:{{ns:special}}:Log/delete|page={{FULLPAGENAMEE}}}} جويۋ جۋرنالىندا] ەگجەي-تەگجەي مٵلمەتتەرٸ بولۋى مٷمكٸن.
</div>',
'rev-delundel'                => 'كٶرسەت/جاسىر',
'revisiondelete'            => 'نۇسقالاردى جويۋ/قايتارۋ',
'revdelete-nooldid-title'   => 'نىسانا نۇسقاسى جوق',
'revdelete-nooldid-text'      => 'وسى ٵرەكەتتٸ ورىنداۋ ٷشٸن اقىرعى نۇسقاسىننە نۇسقالارىن ەنگٸزبەپسٸز.',
'revdelete-selected'          => "'''$1:''' دەگەننٸڭ {{PLURAL:$2|تالعانىلعان نۇسقاسى|تالعانىلعان نۇسقالارى}}:",
'logdelete-selected'          => "'''$1:''' دەگەننٸڭ {{PLURAL:$2|تالعانىلعان جۋرنال جازباسى|تالعانىلعان جۋرنال جازبالارى}}:",
'revdelete-text'              => 'جويىلعان نۇسقالار مەن جازبالاردى ٵلٸ دە بەت تاريحىندا جٵنە جۋرنالداردا تابۋعا بولادى,
بٸراق ولاردىڭ ماعلۇمات بٶلشەكتەرٸ بارشاعا قاتىنالمايدى.

وسى ۋيكيدٸڭ باسقا ٵكٸمشٸلەرٸ جاسىرىن ماعلۇماتقا قاتىناي الادى, جٵنە قوسىمشا شەكتەۋ
ەندٸرٸلگەنشە دەيٸن, وسى تٸلدەسۋ ارقىلى جويىلعان ماعلۇماتتى كەرٸ قايتارا الادى.',
'revdelete-legend'            => 'شەكتەۋلەردٸ ورناتۋ:',
'revdelete-hide-text'         => 'نۇسقا مٵتٸنٸن جاسىر',
'revdelete-hide-name'         => 'ٵرەكەت پەن ماقساتىن جاسىر',
'revdelete-hide-comment'      => 'تٷزەتۋ مٵندەمەسٸن جاسىر',
'revdelete-hide-user'         => 'ٶڭدەۋشٸ اتىن (IP جايىن) جاسىر',
'revdelete-hide-restricted'   => 'وسى شەكتەۋلەردٸ بارشاعا سيياقتى ٵكٸمشٸلەرگە دە قولدانۋ',
'revdelete-suppress'          => 'ٵكٸمشٸلەر جاساعان ماعلۇماتتى باسقالارشا پەردەلەۋ',
'revdelete-hide-image'      => 'فايل ماعلۇماتىن جاسىر',
'revdelete-unsuppress'        => 'قايتارىلعان نۇسقالاردان شەكتەۋلەردٸ الاستاتۋ',
'revdelete-log'               => 'جۋرنال مٵندەمەسٸ:',
'revdelete-submit'          => 'تالعانعان نۇسقاعا قولدانۋ',
'revdelete-logentry'          => '[[$1]] دەگەننٸڭ نۇسقا كٶرٸنٸسٸن ٶزگەرتتٸ',
'logdelete-logentry'          => '[[$1]] دەگەننٸڭ جازبا كٶرٸنٸسٸن ٶزگەرتتٸ',
'revdelete-logaction'         => '{{plural:$1|نۇسقانى|$1 نۇسقانى}} $2 كٷيٸنە قويدى',
'logdelete-logaction'         => '[[$3]] دەگەننٸڭ {{plural:$1|جازباسىن|$1 جازباسىن}} $2 كٷيٸنە قويدى',
'revdelete-success'           => 'نۇسقا كٶرٸنٸسٸ سٵتتٸ قويىلدى.',
'logdelete-success'           => 'جازبا كٶرٸنٸسٸ سٵتتٸ قويىلدى.',

# Oversight log
'oversightlog'              => 'تەكسەرۋشٸ جۋرنالى',
'overlogpagetext'           => 'تٶمەندە ٵكٸمشٸلەر جاسىرعان ماعلۇماتقا ىقپال ەتەتٸن جۋىقتاعى بولعان جويۋ جٵنە بۇعاتتاۋ
تٸزٸمٸ بەرٸلەدٸ. اعىمداعى امالدى بۇعاتتاۋ مەن تيىم ٷشٸن [[{{ns:special}}:Ipblocklist|IP بۇعاتتاۋ تٸزٸمٸن]] قاراڭىز.',

# Diffs
'difference'                => '(نۇسقالار اراسىنداعى ايىرماشىلىق)',
'loadingrev'                => 'ايىرما ٷشٸن نۇسقا جٷكتەۋ',
'lineno'                    => 'جول $1:',
'editcurrent'               => 'وسى بەتتٸڭ اعىمدىق نۇسقاسىن ٶڭدەۋ',
'selectnewerversionfordiff' => 'سالىستىرۋ ٷشٸن جاڭالاۋ نۇسقاسىن تالعاڭىز',
'selectolderversionfordiff' => 'سالىستىرۋ ٷشٸن ەسكٸلەۋ نۇسقاسىن تالعاڭىز',
'compareselectedversions'   => 'تاڭداعان نۇسقالاردى سالىستىرۋ',
'editundo'                  => 'بولدىرماۋ',
'diff-multi'                => '(اراداعى {{plural:$1|بٸر نۇسقا|$1 نۇسقا}} كٶرسەتٸلمەدٸ.)',

# Search results
'searchresults'         => 'ٸزدەستٸرۋ نٵتيجەلەرٸ',
'searchresulttext'      => '{{SITENAME}} جوباسىندا ٸزدەستٸرۋ تۋرالى كٶبٸرەك اقپارات ٷشٸن, [[{{{{ns:mediawiki}}:helppage}}|{{int:help}}]] قاراڭىز.',
'searchsubtitle'        => "ٸزدەستٸرۋ سۇرانىسىڭىز: '''[[:$1]]'''",
'searchsubtitleinvalid' => "ٸزدەستٸرۋ سۇرانىسىڭىز: '''$1'''",
'badquery'              => 'ٸزدەستٸرۋ سۇرانىس جارامسىز پٸشٸمدەلگەن',
'badquerytext'          => 'عافۋ ەتٸڭٸز, سۇرانىسىڭىزدى ورىنداي المادىق.
بۇل ٷش ٵرٸپتەن كەم سٶزدٸ ٸزدەستٸرۋگە تالاپتانعانىڭىزدان
بولۋعا مٷمكٸن, ول ٵلٸ دە سٷيەمەلدەنبەگەن.
تاعى دا بۇل سٶيلەمدٸ دۇرىس ەنگٸزبەگەندٸكتەن دە بولۋعا مٷمكٸن,
مىسالى, «بالىق جٵنە جٵنە قابىرشاق».
باسقا سۇرانىس جاساپ كٶرٸڭٸز',
'matchtotals'           => '«$1» ٸزدەستٸرۋ سۇرانىسى $2 بەتتٸڭ اتاۋىنا
جٵنە $3 بەتتٸڭ مٵتٸنٸنە سٵيكەس.',
'noexactmatch'          => "'''وسىندا «$1» اتاۋلى بەت جوق.''' بۇل بەتتٸ ٶزٸڭٸز '''[[:$1|باستاي  الاسىز]].'''",
'titlematches'          => 'بەت اتاۋى سٵيكەسٸ',
'notitlematches'        => 'ەش بەت اتاۋى سٵيكەس ەمەس',
'textmatches'           => 'بەت مٵتٸنٸڭ سٵيكەسٸ',
'notextmatches'         => 'ەش بەت مٵتٸنٸ سٵيكەس ەمەس',
'prevn'                 => 'الدىڭعى $1',
'nextn'                 => 'كەلەسٸ $1',
'viewprevnext'          => 'كٶرسەتٸلۋٸ: ($1) ($2) ($3) جازبا.',
'showingresults'        => "تٶمەندە نٶمٸر '''$2''' ورنىنان باستاپ, جەتكەنشە {{PLURAL:$1|'''1''' نٵتيجە|'''$1''' نٵتيجە}} كٶرسەتٸلگەن.",
'showingresultsnum'     => "تٶمەندە نٶمٸر '''$2''' ورنىنان باستاپ {{PLURAL:$3|'''1''' نٵتيجە|'''$3''' نٵتيجە}} كٶرسەتٸلگەن.",
'nonefound'             => "'''اڭعارتپا''': تابۋ سٵتسٸز بٸتۋٸ جيٸ «بولعان» جٵنە «دەگەن» سيياقتى
تٸزٸمدەلمەيتٸن جالپى سٶزدەرمەن ٸزدەستٸرۋدەن بولۋى مٷمكٸن,
نەمەسە بٸردەن ارتىق ٸزدەستٸرۋ شارت سٶزدەرٸن ەگٸزگەننەن (نٵتيجەلەردە تەك
بارلىق شارت سٶزدەر كەدەسسە كٶرسەتٸلەدٸ) بولۋى مٷمكٸن.",
'powersearch'           => 'ٸزدەۋ',
'powersearchtext'       => 'مىنا ەسٸم ايالاردا ٸزدەۋ:<br />$1<br />$2 ايداتۋلاردى تٸزٸمدەۋ<br />ٸزدەستٸرۋ سۇرانىسى: $3 $9',
'searchdisabled'        => '{{SITENAME}} جوباسىندا ٸشكٸ ٸزدەۋٸ ٶشٸرٸلگەن. ٵزٸرشە Google نەمەسە Yahoo! ارقىلى ٸزدەۋگە بولادى. اڭعارتپا: {{SITENAME}} ماعلۇمات تٸزٸمٸدەۋلەرٸ ولاردا ەسكٸرگەن بولۋعا مٷمكٸن.',
'blanknamespace'        => '(نەگٸزگٸ)',

# Preferences page
'preferences'              => 'باپتاۋلار',
'mypreferences'            => 'باپتاۋىم',
'prefsnologin'             => 'كٸرمەگەنسٸز',
'prefsnologintext'         => 'باپتاۋلاردى قالاۋ ٷشٸن الدىن الا [[{{ns:special}}:Userlogin|كٸرۋٸڭٸز]] قاجەت.',
'prefsreset'               => 'باپتاۋلار ارقاۋدان قايتا ورناتىلدى.',
'qbsettings'               => 'مٵزٸر ايماعى',
'qbsettings-none'          => 'ەشقانداي',
'qbsettings-fixedleft'     => 'سولعا بەكٸتٸلگەن',
'qbsettings-fixedright'    => 'وڭعا بەكٸتٸلگەن',
'qbsettings-floatingleft'  => 'سولعا قالقىعان',
'qbsettings-floatingright' => 'وڭعا قالقىعان',
'changepassword'           => 'قۇپييا سٶز ٶزگەرتۋ',
'skin'                     => 'بەزەندٸرۋ',
'math'                     => 'ماتەماتيكا',
'dateformat'               => 'كٷن-اي پٸشٸمٸ',
'datedefault'              => 'ەش قالاۋسىز',
'datetime'                 => 'ۋاقىت',
'math_failure'             => 'ٶڭدەتۋ سٵتسٸز بٸتتٸ',
'math_unknown_error'       => 'بەلگٸسٸز قاتە',
'math_unknown_function'    => 'بەلگٸسٸز فۋنكتسييا',
'math_lexing_error'        => 'لەكسيكا قاتەسٸ',
'math_syntax_error'        => 'سينتاكسيس قاتەسٸ',
'math_image_error'         => 'PNG اۋدارىسى سٵتسٸز بٸتتٸ; latex, dvips, gs جٵنە convert باعدارلامالارىنىڭ مٷلتٸكسٸز ورناتۋىن تەكسەرٸڭٸز',
'math_bad_tmpdir'          => 'ماتەماتيكانىڭ ۋاقىتشا قالتاسىنا جازىلمادى, نە قالتا جاسالمادى',
'math_bad_output'          => 'ماتەماتيكانىڭ بەرٸس قالتاسىنا جازىلمادى, نە قالتا جاسالمادى',
'math_notexvc'             => 'texvc باعدارلاماسى جوعالتىلعان; باپتاۋ ٷشٸن math/README قۇجاتىن قاراڭىز.',
'prefs-personal'           => 'جەكە دەرەكتەرٸ',
'prefs-rc'                 => 'جۋىقتاعى ٶزگەرٸستەر',
'prefs-watchlist'          => 'باقىلاۋ',
'prefs-watchlist-days'     => 'باقىلاۋ تٸزٸمٸندە كٶرسەتەرٸن كٷن سانى:',
'prefs-watchlist-edits'    => 'كەڭەيتٸلگەن باقىلاۋ تٸزٸمٸ تٷزەتۋ كٶرسەتەرٸن سانى:',
'prefs-misc'               => 'قوسىمشا',
'saveprefs'                => 'ساقتا',
'resetprefs'               => 'تاستا',
'oldpassword'              => 'اعىمدىق قۇپييا سٶز:',
'newpassword'              => 'جاڭا قۇپييا سٶز:',
'retypenew'                => 'جاڭا قۇپييا سٶزدٸ قايتالاڭىز:',
'textboxsize'              => 'ٶڭدەۋ',
'rows'                     => 'جولدار:',
'columns'                  => 'باعاندار:',
'searchresultshead'        => 'ٸزدەۋ',
'resultsperpage'           => 'بەت سايىن نٵتيجە سانى:',
'contextlines'             => 'نٵتيجە سايىن جول سانى:',
'contextchars'             => 'جول سايىن ٵرٸپ سانى:',
'stubthreshold'            => 'بٸتەمە كٶرستەتۋٸن انىقتاۋ تابالدىرىعى:',
'recentchangesdays'        => 'جٷىقتاعى ٶزگەرٸستەردەگٸ كٶرسەتٸلەتٸن كٷندەر:',
'recentchangescount'       => 'جۋىقتاعى ٶزگەرٸستەردەگٸ كٶرسەتٸلەتٸن تٷزەتۋلەر:',
'savedprefs'               => 'باپتاۋلارىڭىز ساقتالدى.',
'timezonelegend'           => 'ۋاقىت بەلدەۋٸ',
'timezonetext'             => 'جەرگٸلٸكتٸ ۋاقىتىڭىزبەن سەرۆەر ۋاقىتىنىڭ (UTC) اراسىنداعى ساعات سانى.',
'localtime'                => 'جەرگٸلٸكتٸ ۋاقىت',
'timezoneoffset'           => 'ىعىستىرۋ¹',
'servertime'               => 'سەرۆەر ۋاقىتى',
'guesstimezone'            => 'شولعىشتان الىپ تولتىرۋ',
'allowemail'               => 'باسقادان حات قابىلداۋىن ەندٸرۋ',
'defaultns'                => 'مىنا ەسٸم ايالاردا ٵدەپكٸدەن ٸزدەۋ:',
'default'                  => 'ٵدەپكٸ',
'files'                    => 'فايلدار',

# User rights
'userrights-lookup-user'     => 'قاتىسۋشى توپتارىن مەڭگەرۋ',
'userrights-user-editname'   => 'قاتىسۋشى اتىن ەنگٸزٸڭٸز:',
'editusergroup'              => 'قاتىسۋشى توپتارىن ٶڭدەۋ',
'userrights-editusergroup'   => 'قاتىسۋشى توپتارىن ٶڭدەۋ',
'saveusergroups'             => 'قاتىسۋشى توپتارىن ساقتاۋ',
'userrights-groupsmember'    => 'مٷشەلٸگٸ:',
'userrights-groupsavailable' => 'قاتىناۋلى توپتار:',
'userrights-groupshelp'      => 'قاتىسۋشىنى ٷستەيتٸن نە الاستاتىن توپتاردى تالعاڭىز.
تالعاۋى ٶشٸرٸلگەن توپتار ٶزگەرتٸلٸمەيدٸ. توپتاردىڭ تالعاۋىن CTRL + سول جاق نۇقۋمەن ٶشٸرۋٸڭٸزگە بولادى.',
'userrights-reason'          => 'ٶزگەرتۋ سەبەبٸ:',

# Groups
'group'            => 'توپ:',
'group-bot'        => 'بوتتار',
'group-sysop'      => 'ٵكٸمشٸلەر',
'group-bureaucrat' => 'تٶرەشٸلەر',
'group-all'        => '(بارلىعى)',

'group-bot-member'        => 'بوت',
'group-sysop-member'      => 'ٵكٸمشٸ',
'group-bureaucrat-member' => 'تٶرەشٸ',

'grouppage-bot'        => '{{ns:project}}:بوتتار',
'grouppage-sysop'      => '{{ns:project}}:ٵكٸمشٸلەر',
'grouppage-bureaucrat' => '{{ns:project}}:تٶرەشٸلەر',

# User rights log
'rightslog'      => 'قاتىسۋشى_قۇقىقتارى_جۋرنالى',
'rightslogtext'  => 'بۇل پايدالانۋشىلار قۇقىقتارىن ٶزگەرتۋ جۋرنالى.',
'rightslogentry' => ' $1 توپ مٷشەلگٸن $2 دەگەننەن $3 دەگەنگە ٶزگەرتتٸ',
'rightsnone'     => '(ەشقانداي)',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|بٸر تٷزەتۋ|$1 تٷزەتۋ}}',
'recentchanges'                     => 'جۋىقتاعى ٶزگەرٸستەر',
'recentchangestext'                 => 'بۇل بەتتە وسى ۋيكيدەگٸ بولعان جۋىقتاعى ٶزگەرٸستەر بايقالادى.',
'recentchanges-feed-description'    => 'بۇل ارنامەنەن ۋيكيدەگٸ ەڭ سوڭعى ٶزگەرٸستەر قاداعالانادى.',
'rcnote'                            => "$3 كەزٸنە دەيٸن — تٶمەندە سوڭعى {{PLURAL:$2|كٷندەگٸ|'''$2''' كٷندەگٸ}}, سوڭعى {{PLURAL:$1|'''1''' ٶزگەرٸس|'''$1''' ٶزگەرٸس}} كٶرسەتٸلگەن.",
'rcnotefrom'                        => '<b>$2</b> كەزٸنەن بەرٸ — تٶمەندە ٶزگەرٸستەر <b>$1</b> دەيٸن كٶرسەتٸلگەن.',
'rclistfrom'                        => '$1 كەزٸنەن بەرٸ — جاڭا ٶزگەرٸستەردٸ كٶرسەت.',
'rcshowhideminor'                   => 'شاعىن تٷزەتۋدٸ $1',
'rcshowhidebots'                    => 'بوتتاردى $1',
'rcshowhideliu'                     => 'تٸركەلگەندٸ $1',
'rcshowhideanons'                   => 'تٸركەلگٸسٸزدٸ $1',
'rcshowhidepatr'                    => 'كٷزەتتەگٸ تٷزەتۋلەردٸ $1',
'rcshowhidemine'                    => 'تٷزەتۋٸمدٸ $1',
'rclinks'                           => 'سوڭعى $2 كٷندە بولعان, سوڭعى $1 ٶزگەرٸستٸ كٶرسەت<br />$3',
'diff'                              => 'ايىرم.',
'hist'                              => 'تار.',
'hide'                              => 'جاسىر',
'show'                              => 'كٶرسەت',
'minoreditletter'                   => 'ش',
'newpageletter'                     => 'ج',
'boteditletter'                     => 'ب',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[باقىلاعان $1 قاتىسۋشى]',
'rc_categories'                     => 'ساناتتارعا شەكتەۋ ("|" بەلگٸسٸمەن بٶلٸكتەڭٸز)',
'rc_categories_any'                 => 'قايسىبٸر',

# Recent changes linked
'recentchangeslinked'          => 'قاتىستى تٷزەتۋلەر',
'recentchangeslinked-noresult' => 'سٸلتەگەن بەتتەردە ايتىلمىش مەرزٸمدە ەشقانداي ٶزگەرٸس بولماعان.',
'recentchangeslinked-summary'  => "بۇل ارنايى بەتتە سٸلتەگەن بەتتەردەگٸ جۋىقتاعى ٶزگەرٸستەر تٸزٸمٸ بەرٸلەدٸ. باقىلاۋ تٸزٸمٸڭٸزدەگٸ بەتتەر '''جۋان''' ٵرپٸمەن بەلگٸلەنەدٸ.",

# Upload
'upload'                      => 'فايل قوتارۋ',
'uploadbtn'                   => 'قوتار!',
'reupload'                    => 'قايتالاپ قوتارۋ',
'reuploaddesc'                => 'قوتارۋ ٷلگٸتٸنە ورالۋ.',
'uploadnologin'               => 'كٸرمەگەنسٸز',
'uploadnologintext'           => 'فايل قوتارۋ ٷشٸن
[[{{ns:special}}:Userlogin|كٸرۋٸڭٸز]] قاجەت.',
'upload_directory_read_only'  => 'قوتارۋ قالتاسىنا ($1) جازۋعا ۆەب-سەرۆەرگە رۇقسات بەرٸلمەگەن.',
'uploaderror'                 => 'قوتارۋ قاتەسٸ',
'uploadtext'                  => "تٶمەندەگٸ ٷلگٸت فايل قوتارۋعا قولدانىلادى, الدىنداعى سۋرەتتەردٸ قاراۋ ٷشٸن نە ٸزدەۋ ٷشٸن [[{{ns:special}}:Imagelist|قوتارىلعان فايلدار تٸزٸمٸنە]] بارىڭىز, قوتارۋ مەن جويۋ تاعى دا [[{{ns:special}}:Log/upload|قوتارۋ جۋرنالىنا]] جازىلىپ الىنادى.

سۋرەتتەردٸ بەتكە كٸرگٸزۋ ٷشٸن, فايلعا تۋرا بايلانىستراتىن
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.png|بالاما مٵتٸنٸ]]</nowiki>''' نەمەسە
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' سٸلتەمە پٸشٸمٸن قولدانىڭىز.",
'uploadlog'                   => 'قوتارۋ جۋرنالى',
'uploadlogpage'               => 'قوتارۋ جۋرنالى',
'uploadlogpagetext'           => 'تٶمەندە جۋىقتاعى قوتارىلعان فايل تٸزٸمٸ.',
'filename'                    => 'فايل اتى',
'filedesc'                    => 'سيپاتتاماسى',
'fileuploadsummary'           => 'سيپاتتاماسى:',
'filestatus'                  => 'اۋتورلىق قۇقىقتارى كٷيٸ',
'filesource'                  => 'فايل قاينارى',
'uploadedfiles'               => 'قوتارىلعان فايلدار',
'ignorewarning'               => 'نازار سالۋدى ەلەمەۋ جٵنە فايلدى ٵردەقاشان ساقتاۋ.',
'ignorewarnings'              => 'ٵرقايسى نازار سالۋلاردى ەلەمەۋ',
'minlength'                   => 'فايل اتىندا ەڭ كەمٸندە ٷش ٵرٸپ بولۋى كەرەك.',
'illegalfilename'             => '«$1» فايل اتاۋىندا بەت اتاۋلارىندا رۇقسات ەتٸلمەگەن نىشاندار بار. فايلدى قايتا اتاڭىز, سوسىن قايتا جۋكتەپ كٶرٸڭٸز.',
'badfilename'                 => 'فايلدىڭ اتى «$1» بوپ ٶزگەرتٸلدٸ.',
'filetype-badmime'            => '«$1» دەگەن MIME تٷرٸ بار فايلداردى قوتارۋعا رۇقسات ەتٸلمەيدٸ.',
'filetype-badtype'            => "'''«.$1»''' دەگەن كٷتٸلمەگەن فايل تٷرٸ
: رٷقسات ەتٸلگەن فايل تٷر تٸزٸمٸ: $2",
'filetype-missing'            => 'بۇل فايلدىڭ («.jpg» سيياقتى) كەڭەيتٸمٸ جوق.',
'large-file'                  => 'فايلدى $1 مٶلشەردەن اسپاۋىنا تىرىسىڭىز; بۇل فايل مٶلشەرٸ — $2.',
'largefileserver'             => 'وسى فايلدىڭ مٶلشەرٸ سەرۆەردٸڭ قالاۋىنان اسىپ كەتكەن.',
'emptyfile'                   => 'قوتارىلعان فايلىڭىز بوس سيياقتى. بۇل فايل اتاۋى جانساق ەنگٸزٸلگەنٸنەن بولۋى مٷمكٸن. قوتارعىڭىز كەلگەن فايل شىنىندا دا وسى فايل بولعانىن تەكسەرٸپ الىڭىز.',
'fileexists'                  => 'وسىنداي اتاۋلى فايل بار تٷگە. قايتا جازۋدىڭ الدىنان $1 تەكسەرٸپ شىعىڭىز.',
'fileexists-extension'        => 'بۇنداي اتاۋىمەن فايل بار تٷگە:<br />
قوتارىلاتىن فايل اتاۋى: <strong><tt>$1</tt></strong><br />
بار بولعان فايل اتاۋى: <strong><tt>$2</tt></strong><br />
ايىرماشلىعى تەك كەڭەيتٸمٸ باس/كٸشٸ ٵرپٸمەن جازىلۋىندا. فايلداردىڭ بٸردەيلٸگٸن سىناپ شىعىڭىز.',
'fileexists-thumb'            => "'''<center>بار بولعان سۋرەت</center>'''",
'fileexists-thumbnail-yes'    => "وسى فايل — مٶلشەرٸ كٸشٸرٸتٸلگەن سۋرەت <i>(نوباي)</i> سيياقتى. بۇل <strong><tt>$1</tt></strong> دەگەن فايلدى سىناپ شىعىڭىز.<br />
ەگەر سىنالعان فايل تٷپنۇسقالى مٶلشەرٸ بار دٵلمە-دٵل سۋرەت بولسا, قوسىسمشا نوبايدى قوتارۋ قاجەتٸ جوق.",
'file-thumbnail-no'           => "فايل اتاۋى <strong><tt>$1</tt></strong> دەگەنمەن باستالادى. بۇل — مٶلشەرٸ كٸشٸرٸتٸلگەن سۋرەت <i>(نوباي)</i> سيياقتى.
ەگەر تولىق اجىراتىلىمدىعى بار سۋرەتٸڭٸز بولسا, سونى قوتارىڭىز, ٵيتپەسە فايل اتاۋىن ٶزگەرتٸڭٸز.",
'fileexists-forbidden'        => 'وسىنداي اتاۋلى فايل بار تٷگە. كەرٸ قايتىڭىز دا, جٵنە وسى فايلدى باسقا اتىمەن قوتارىڭىز. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'وسىنداي اتاۋلى فايل ورتاق فايل ارقاۋىندا بار تٷگە. كەرٸ قايتىڭىز دا, وسى فايلدى جاڭا اتىمەن قوتارىڭىز. [[{{ns:image}}:$1|thumb|center|$1]]',
'successfulupload'            => 'قوتارۋ سٵتتٸ ٶتتٸ',
'fileuploaded'                => '«$1» فايلى سٵتتٸ قوتارىلدى!
وسى سٸلتەمەگە ەرٸپ — $2, سيپاتتاما بەتٸنە بارىڭىز دا, جٵنە وسى فايل تۋرالى
اقپارات تولتىرىڭىز: قايدان الىنعانىن, قاشان جاسالعانىن, كٸم جاساعانىن,
تاعى باسقا بٸلەتٸڭٸزدٸ. بۇل سۋرەت بولسا, مىناداي پٸشٸمٸمەن كٸرٸستٸرۋگە بولادى: <tt><nowiki>[[</nowiki>{{ns:image}}<nowiki>:$1|thumb|سيپاتتاماسى]]</nowiki></tt>',
'uploadwarning'               => 'قوتارۋ تۋرالى نازار سالۋ',
'savefile'                    => 'فايلدى ساقتاۋ',
'uploadedimage'               => '«[[$1]]» فايلىن قوتاردى',
'uploaddisabled'              => 'فايل قوتارۋى ٶشٸرٸلگەن',
'uploaddisabledtext'          => 'وسى ۋيكيدە فايل قوتارۋى ٶشٸرٸلگەن.',
'uploadscripted'              => 'وسى فايلدا, ۆەب شولعىشتى اعات تٷسٸندٸككە كەلتٸرەتٸڭ HTML بەلگٸلەۋ, نە سكريپت كودى بار.',
'uploadcorrupt'               => 'وسى فايل بٷلدٸرٸلگەن, نە ٵدەپسٸز كەڭەيتٸمٸ بار. فايلدى تەكسەرٸپ, قوتارۋىن قايتالاڭىز.',
'uploadvirus'                 => 'وسى فايلدا ۆيرۋس بولۋى مٷمكٸن! ەگجەي-تەگجەي اقپاراتى: $1',
'sourcefilename'              => 'قاينارداعى فايل اتى',
'destfilename'                => 'اقىرعى فايل اتى',
'watchthisupload'             => 'وسى بەتتٸ باقىلاۋ',
'filewasdeleted'              => 'وسى اتاۋى بار فايل بۇرىن قوتارىلعان, سوسىن جويىلدىرىلعان. قايتا قوتارۋ الدىنان $1 دەگەندٸ تەكسەرٸڭٸز.',

'upload-proto-error'      => 'جارامسىز حاتتامالىق',
'upload-proto-error-text' => 'سىرتتان قوتارۋ ٷشٸن URL جايلارى <code>http://</code> نەمەسە <code>ftp://</code> دەگەندەردەن باستالۋ قاجەت.',
'upload-file-error'       => 'ٸشكٸ قاتە',
'upload-file-error-text'  => 'سەرۆەردە ۋاقىتشا فايل جاساۋى ٸشكٸ قاتەگە ۇشىراستى. بۇل جٷيەنٸڭ ٵكٸمشٸمەن قاتىناسىڭىز.',
'upload-misc-error'       => 'بەلگٸسٸز قوتارۋ قاتەسٸ',
'upload-misc-error-text'  => 'قوتارۋ كەزٸندە بەلگٸسٸز قاتە ۇشىراستى. قايسى URL جايى جارامدى جٵنە قاتىناۋلى ەكەنٸن تەكسەرٸپ شىعىڭىز دا قايتالاپ كٶرٸڭٸز. ەگەر بۇل مٵسەلە ٵلدە دە قالسا, جٷيە ٵكٸمشٸمەن قاتىناسىڭىز.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL جايى جەتٸلمەدٸ',
'upload-curl-error6-text'  => 'بەرٸلگەن URL جايى جەتٸلمەدٸ. قايسى URL جايى دۇرىس ەكەنٸن جٵنە توراپ ٸستە ەكەنٸن قايتالاپ قاتاڭ تەكسەرٸڭٸز.',
'upload-curl-error28'      => 'قوتارۋعا بەرٸلگەن ۋاقىت بٸتتٸ',
'upload-curl-error28-text' => 'توراپتىڭ جاۋاپ بەرۋٸ تىم ۇزاق ۋاقىتقا سوزىلدى. بۇل توراپ ٸستە ەكەنٸن تەكسەرٸپ شىعىڭىز, از ۋاقىت كٸدٸرە تۇرىڭىز دا تاعى قايتالاپ كٶرٸڭٸز. تالابىڭىزدى جٷكتەلۋٸ ازداۋ كەزٸندە قايتالاۋعا بولمىس.',

'license'            => 'ليتسەنزيياسى',
'nolicense'          => 'ەشتەڭە تالعانباعان',
'upload_source_url'  => ' (جارامدى, بارشاعا قاتىناۋلى URL جاي)',
'upload_source_file' => ' (كومپييۋتەرٸڭٸزدەگٸ فايل)',

# Image list
'imagelist'                 => 'فايل تٸزٸمٸ',
'imagelisttext'             => "تٶمەندە ''$2'' سۇرىپتالعان '''$1''' فايل تٸزٸمٸ.",
'imagelistforuser'          => 'مىندا تەك $1 جٷكتەگەن سۋرەتتەر كٶرسەتٸلەدٸ.',
'getimagelist'              => 'فايل تٸزٸمدەۋٸ',
'ilsubmit'                  => 'ٸزدەۋ',
'showlast'                  => 'سوڭعى $1 فايل $2 سۇرىپتاپ كٶرسەت.',
'byname'                    => 'اتىمەن',
'bydate'                    => 'كٷن-ايمەن',
'bysize'                    => 'مٶلشەرٸمەن',
'imgdelete'                 => 'جويۋ',
'imgdesc'                   => 'سيپپ.',
'imgfile'                   => 'فايل',
'imglegend'                 => 'شارتتى بەلگٸلەر: (سيپپ.) — فايل سيپاتتاماسىن كٶرسەتۋ/ٶڭدەۋ.',
'imghistory'                => 'فايل تاريحى',
'revertimg'                 => 'قايت.',
'deleteimg'                 => 'جويۋ',
'deleteimgcompletely'       => 'وسى فايلدىڭ بارلىق نۇسقالارىن جوي',
'imghistlegend'             => 'شارتتى بەلگٸلەر: (اعىم.) = اعىمدىق فايل, (جويۋ) = ەسكٸ نۇسقاسىن
جويۋ, (قاي.) = ەسكٸ نۇسقاسىنا قايتارۋ.
<br /><i>قوتارىلعان فايلدى كٶرۋ ٷشٸن كٷن-ايىنا نۇقىڭىز</i>.',
'imagelinks'                => 'سٸلتەمەلەرٸ',
'linkstoimage'              => 'بۇل فايلعا كەلەسٸ بەتتەر سٸلتەيدٸ:',
'nolinkstoimage'            => 'بۇل فايلعا ەش بەت سٸلتەمەيدٸ.',
'sharedupload'              => 'بۇل فايل ورتاق ارقاۋىنا قوتارىلعان سوندىقتان باسقا جوبالاردا قولدانۋى مٷمكٸن.',
'shareduploadwiki'          => 'بىلايعى اقپارات ٷشٸن $1 دەگەندٸ قاراڭىز.',
'shareduploadwiki-linktext' => 'فايل سيپاتتاماسى بەتٸ',
'noimage'                   => 'مىناداي اتاۋلى فايل جوق, $1 مٷمكٸندٸگٸڭٸز بار.',
'noimage-linktext'          => 'وسىنى قوتارۋ',
'uploadnewversion-linktext' => 'بۇل فايلدىڭ جاڭا نۇسقاسىن قوتارۋ',
'imagelist_date'            => 'كٷن-ايى',
'imagelist_name'            => 'اتاۋى',
'imagelist_user'            => 'قاتىسۋشى',
'imagelist_size'            => 'مٶلشەرٸ (B)',
'imagelist_description'     => 'سيپاتتاماسى',
'imagelist_search_for'      => 'سۋرەتتٸ اتىمەن ٸزدەۋ:',

# MIME search
'mimesearch'         => 'فايلدى MIME تٷرٸمەن ٸزدەۋ',
'mimesearch-summary' => 'بۇل بەت فايلداردى MIME تٷرٸمەن سٷزگٸلەۋ مٷمكٸندٸگٸن بەرەدٸ. كٸرٸسٸ: «ماعلۇمات تٷرٸ»/«تاراۋ تٷرٸ», مىسالى <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME تٷرٸ:',
'download'           => 'جٷكتەۋ',

# Unwatched pages
'unwatchedpages'         => 'باقىلانباعان بەتتەر',

# List redirects
'listredirects'         => 'ايداتۋ بەت تٸزٸمٸ',

# Unused templates
'unusedtemplates'         => 'پايدالانىلماعان ٷلگٸلەر',
'unusedtemplatestext'     => 'بۇل بەت باسقا بەتكە كٸرٸcتٸرٸلمەگەن ٷلگٸ ەسٸم اياىسىنداعى بارلىق بەتتەردٸ تٸزٸمدەيدٸ. ٷلگٸلەردٸ جويۋ الدىنان بۇنىڭ باسقا سٸلتەمەلەرٸن تەكسەرٸپ شىعۋىن ۇمىتپاڭىز',
'unusedtemplateswlh'      => 'باسقا سٸلتەمەلەر',

# Random redirect
'randomredirect' => 'كەزدەيسوق ايداتۋ',
'randomredirect-nopages' => 'بۇل ەسٸم اياسىندا ەش ايداتۋ جوق.',
# Statistics
'statistics'             => 'جوبا ساناعى',
'sitestats'              => '{{SITENAME}} ساناعى',
'userstats'              => 'قاتىسۋشى ساناعى',
'sitestatstext'          => "دەرەكقوردا {{PLURAL:$1|'''1''' بەت|بۇلايشا '''$1''' بەت}} بار.
بۇنىڭ ٸشٸندە: «تالقىلاۋ» بەتتەرٸ, {{SITENAME}} جوباسى تۋرالى بەتتەر, ەڭ از «بٸتەمە»
بەتتەرٸ, ايداتۋلار, تاعى دا باسقا ماعلۇمات دەپ تانىلمايتىن بەتتەر بولۋى مٷمكٸن .
سولاردى ەسەپتەن شىعارعاندا, مىندا ماعلۇمات دەپ سانالاتىن
{{PLURAL:$2|'''1'''|'''$2'''}} بەت بار شىعار.

قوتارىلعان {{PLURAL:$8|'''1''' فايل|'''$8''' فايل}} ساقتالادى.

{{SITENAME}} جوباسى ورناتىلعاننان بەرٸ بەتتەر {{PLURAL:$3|'''1''' رەت|بۇلايشا '''$3''' رەت}} قارالعان,
جٵنە بەتتەر {{PLURAL:$4|'''1''' رەت|'''$4''' رەت}} تٷزەتٸلگەن.
بۇنىڭ نٵتيجەسٸندە ورتا ەسەپپەن ٵربٸر بەتكە '''$5''' رەت تٷزەتۋ كەلەدٸ, جٵنە ٵربٸر تٷزەتۋگە '''$6''' رەت قاراۋ كەلەدٸ.

اعىمدىق [http://meta.wikimedia.org/wiki/Help:Job_queue تاپسىرىم كەزەگٸ] ۇزىندىلىعى: '''$7'''.",
'userstatstext'          => "مىندا {{PLURAL:$1|'''1''' تٸركەلگەن قاتىسۋشى|'''$1''' تٸركەلگەن قاتىسۋشى}} بار, سونىڭ ٸشٸندە
 {{PLURAL:$2|'''1''' قاتىسۋشىدا|'''$2''' قاتىسۋشىدا}} (نەمەسە '''$4 ٪''') $5 قۇقىقتارى بار",
'statistics-mostpopular' => 'ەڭ كٶپ قارالعان بەتتەر',

'disambiguations'         => 'ايرىقتى بەتتەر',
'disambiguationspage'     => '{{ns:template}}:Disambig',
'disambiguations-text'    => "كەلەسٸ بەتتەر '''ايرىقتى بەتكە''' سٸلتەيدٸ. بۇنىڭ ورنىنا بەلگٸلٸ تاقىرىپقا سٸلتەۋٸ قاجەت.<br />ەگەر [[{{ns:mediawiki}}:disambiguationspage]] تٸزٸمٸندەگٸ ٷلگٸ قولدانىلسا, بەت ايرىقتى دەپ سانالادى.",

'doubleredirects'         => 'شىنجىرلى ايداتۋلار',
'doubleredirectstext'     => 'ٵربٸر جولداعى بٸرٸنشٸ مەن ەكٸنشٸ ايداتۋ سٸلتەمەلەرٸ بار, سونىمەن بٸرگە ەكٸنشٸ ايداتۋ مٵتٸننٸڭ بٸرٸنشٸ جولى بار. ٵدەتتە بٸرٸنشٸ سٸلتەمە ايدايتىن «شىن» اقىرعى بەتتٸڭ اتاۋى بولۋى قاجەت.',

'brokenredirects'         => 'ەش بەتكە كەلتٸرمەيتٸن ايداتۋلار',
'brokenredirectstext'     => 'كەلەسٸ ايداتۋلار جوق بەتتەرگە سٸلتەيدٸ:',
'brokenredirects-edit'    => '(ٶڭدەۋ)',
'brokenredirects-delete'  => '(جويۋ)',

'withoutinterwiki'        => 'ەش تٸلگە سٸلتeمەگەن بەتتەر',
'withoutinterwiki-header' => 'كەلەسٸ بەتتەر باسقا تٸلدەرگە سٸلتەمەيدٸ:',

'fewestrevisions'         => 'ەڭ از تٷزەتٸلگەن بەتتەر',
# Miscellaneous special pages
'nbytes'                          => '$1 B',
'ncategories'                     => '$1 سانات',
'nlinks'                          => '$1 سٸلتەمە',
'nmembers'                        => '$1 بۋىن',
'nrevisions'                      => '$1 نۇسقا',
'nviews'                          => '$1 رەت قارالعان',
'specialpage-empty'               => 'بۇل بەت بوس.',
'lonelypages'                     => 'ەش بەت سٸلتەمەگەن بەتتەر',
'lonelypagestext'                 => 'كەلەسٸ بەتتەرگە وسى جوباداعى باسقا بەتتەر سٸلتەمەيدٸ.',
'uncategorizedpages'              => 'ەش ساناتقا كٸرمەگەن بەتتەر',
'uncategorizedcategories'         => 'ەش ساناتقا كٸرمەگەن ساناتتار',
'uncategorizedimages'             => 'ەش ساناتقا كٸرمەگەن سۋرەتتەر',
'unusedcategories'                => 'پايدالانىلماعان ساناتتار',
'unusedimages'                    => 'پايدالانىلماعان فايلدار',
'popularpages'                    => 'ٵيگٸلٸ بەتتەر',
'wantedcategories'                => 'باستالماعان ساناتتار',
'wantedpages'                     => 'باستالماعان بەتتەر',
'mostlinked'                      => 'ەڭ كٶپ سٸلتەنگەن بەتتەر',
'mostlinkedcategories'            => 'ەڭ كٶپ سٸلتەنگەن ساناتتار',
'mostcategories'                  => 'ەڭ كٶپ ساناتتارعا كٸرگەن بەتتەر',
'mostimages'                      => 'ەڭ كٶپ سٸلتەنگەن سۋرەتتەر',
'mostrevisions'                   => 'ەڭ كٶپ تٷزەتٸلگەن بەتتەر',
'allpages'                        => 'بارلىق بەت تٸزٸمٸ',
'prefixindex'                     => 'بەت باستاۋ تٸزٸمٸ',
'randompage'                      => 'كەزدەيسوق بەت',
'randompage-nopages'              => 'بۇل ەسٸم اياسىندا بەتتەر جوق.',
'shortpages'                      => 'ەڭ قىسقا بەتتەر',
'longpages'                       => 'ەڭ ٷلكەن بەتتەر',

'deadendpages'                    => 'ەش بەتكە سٸلتەمەيتٸن بەتتەر',
'deadendpagestext'                => 'كەلەسٸ بەتتەر وسى جوباداعى باسقا بەتتەرگە سٸلتەمەيدٸ.',
'protectedpages'                  => 'قورعالعان بەتتەر',
'protectedpagestext'              => 'كەلەسٸ بەتتەر ٶڭدەۋدەن نەمەسە جىلجىتۋدان قورعالعان',
'protectedpagesempty'             => 'اعىمدا وسىنداي باپتاۋلارىمەن ەشبٸر بەت قورعالماعان',
'listusers'                       => 'بارلىق قاتىسۋشى تٸزٸمٸ',
'specialpages'                    => 'ارنايى بەتتەر',
'spheading'                       => 'بارشانىڭ ارنايى بەتتەرٸ',
'restrictedpheading'              => 'شەكتەۋلٸ ارنايى بەتتەر',
'rclsub'                          => '(«$1» بەتٸنەن سٸلتەنگەن بەتتەرگە)',
'newpages'                        => 'ەڭ جاڭا بەتتەر',
'newpages-username'               => 'قاتىسۋشى اتى:',
'ancientpages'                    => 'ەڭ ەسكٸ بەتتەر',
'intl'                            => 'تٸلارالىق سٸلتەمەلەر',
'move'                            => 'جىلجىتۋ',
'movethispage'                    => 'بەتتٸ جىلجىتۋ',
'unusedimagestext'                => '<p>ەسكەرتۋ: باسقا ۆەب توراپتار فايلدىڭ
URL جايىنا تٸكەلەي سٸلتەۋٸ مٷمكٸن. سوندىقتان, بەلسەندٸ پايدالانۋىنا اڭعارماي,
وسى تٸزٸمدە قالۋى مٷمكٸن.</p>',
'unusedcategoriestext'            => 'كەلەسٸ سانات بەتتەر بار بولىپ تۇر, بٸراق وعان ەشقانداي بەت, نە سانات كٸرمەيدٸ.',

# Book sources
'booksources'               => 'كٸتاپ قاينارلارى',
'booksources-search-legend' => 'كٸتاپ قاينارلارىن ٸزدەۋ',
'booksources-isbn'          => 'ISBN بەلگٸسٸ:',
'booksources-go'            => 'ٶتۋ',
'booksources-text'          => 'تٶمەندە جاڭا جٵنە قولدانعان كٸتاپتار ساتاتىنتوراپتارىنىڭ سٸلتەمەلەرٸ تٸزٸمدەلگەن.
بۇل توراپتاردا ٸزدەلگەن كٸتاپتار تۋرالى بىلايعى اقپارات بولۋعا مٷمكٸن.',

'categoriespagetext' => 'وسىندا ۋيكيدەگٸ بارلىق ساناتتارىنىڭ تٸزٸمٸ بەرٸلٸپ تۇر.',
'data'               => 'دەرەكتەر',
'userrights'         => 'قاتىسۋشىلار قۇقىقتارىن مەڭگەرۋ',
'groups'             => 'قاتىسۋشى توپتارى',
'isbn'               => 'ISBN',
'alphaindexline'     => '$1 — $2',
'version'            => 'جٷيە نۇسقاسى',

# Special:Logs
'specialloguserlabel'  => 'قاتىسۋشى:',
'speciallogtitlelabel' => 'اتاۋ:',
'log'                => 'جۋرنالدار',
'log-search-legend'    => 'جۋرنالداردان ٸزدەۋ',
'log-search-submit'    => 'ٶتۋ',
'alllogstext'          => '{{SITENAME}} جوباسىنىڭ بارلىق قاتىناۋلى جۋرنالدارىن بٸرٸكتٸرٸپ كٶرسەتۋٸ.
جۋرنال تٷرٸن, قاتىسۋشى اتىن, نە تيٸستٸ بەتٸن تالعاپ, تارىلتىپ قاراۋىڭىزعا بولادى.',
'logempty'             => 'جۋرنالدا سٵيكەس دانالار جوق.',
'log-title-wildcard'   => 'مىناداي مٵتٸننەڭ باستالىتىن اتاۋلاردان ٸزدەۋ',

# Special:Allpages
'nextpage'          => 'كەلەسٸ بەتكە ($1)',
'prevpage'          => 'الدىڭعى بەتكە ($1)',
'allpagesfrom'      => 'مىنا بەتتەن باستاپ كٶرسەتۋ:',
'allarticles'       => 'بارلىق بەت تٸزٸمٸ',
'allinnamespace'    => 'بارلىق بەت ($1 ەسٸم اياسى)',
'allnotinnamespace' => 'بارلىق بەت ($1 ەسٸم اياسىنان تىس)',
'allpagesprev'      => 'الدىڭعىعا',
'allpagesnext'      => 'كەلەسٸگە',
'allpagessubmit'    => 'ٶتۋ',
'allpagesprefix'    => 'مىنادان باستالعان بەتتەردٸ كٶرسەتۋ:',
'allpagesbadtitle'  => 'الىنعان بەت اتاۋى جارامسىز بولعان, نەمەسە تٸل-ارالىق نە ۋيكي-ارالىق باستاۋى بار بولدى. اتاۋدا قولدانۋعا بولمايتىن نىشاندار بولۋى مٷمكٸن.',

# Special:Listusers
'listusersfrom' => 'مىنا قاتىسۋشىدان باستاپ كٶرسەتۋ:',
'listusers-submit'   => 'كٶرسەت',
'listusers-noresult' => 'قاتىسۋشى تابىلعان جوق.',

# E-mail user
'mailnologin'     => 'ە-پوشتا جايى جٸبەرٸلگەن جوق',
'mailnologintext' => 'باسقا قاتىسۋشىعا حات جٸبەرۋ ٷشٸن
[[{{ns:special}}:Userlogin|كٸرۋٸڭٸز]] قاجەت, جٵنە [[{{ns:special}}:Preferences|باپتاۋىڭىزدا]]
كۋٵلاندىرىلعان ە-پوشتا جايى بولۋى جٶن.',
'emailuser'       => 'قاتىسۋشىعا حات جازۋ',
'emailpage'       => 'قاتىسۋشىعا حات جٸبەرۋ',
'emailpagetext'   => 'ەگەر بۇل قاتىسۋشى باپتاۋلارىندا كۋٵلاندىرعان ە-پوشتا
جايىن ەنگٸزسە, تٶمەندەگٸ ٷلگٸت ارقىلى بۇعان جالعىز ە-پوشتا حاتىن جٸبەرۋگە بولادى.
قاتىسۋشى باپتاۋىڭىزدا ەنگٸزگەن ە-پوشتا جايىڭىز
«كٸمنەن» دەگەن باس جولاعىندا كٶرٸنەدٸ, سوندىقتان
حات الۋشىسى تۋرا جاۋاپ بەرە الادى.',
'usermailererror' => 'Mail نىسانى قاتە قايتاردى:',
'defemailsubject' => '{{SITENAME}} ە-پوشتاسىنىڭ حاتى',
'noemailtitle'    => 'بۇل ە-پوشتا جايى ەمەس',
'noemailtext'     => 'وسى قاتىسۋشى جارامدى ە-پوشتا جايىن ەنگٸزبەگەن,
نەمەسە باسقالاردان حات قابىلداۋىن ٶشٸرگەن.',
'emailfrom'       => 'كٸمنەن',
'emailto'         => 'كٸمگە',
'emailsubject'    => 'تاقىرىبى',
'emailmessage'    => 'حات',
'emailsend'       => 'جٸبەرۋ',
'emailccme'       => 'حاتىمدىڭ كٶشٸرمەسٸن ماعان دا جٸبەر.',
'emailccsubject'  => '$1 دەگەنگە جٸبەرٸلگەن حاتىڭىزدىڭ كٶشٸرمەسٸ: $2',
'emailsent'       => 'حات جٸبەرٸلدٸ',
'emailsenttext'   => 'ە-پوشتا حاتىڭىز جٸبەرٸلدٸ.',

# Watchlist
'watchlist'            => 'باقىلاۋ تٸزٸمٸ',
'mywatchlist'          => 'باقىلاۋىم',
'watchlistfor'         => "('''$1''' باقىلاۋلارى)",
'nowatchlist'          => 'باقىلاۋ تٸزٸمٸڭٸزدە ەشبٸر دانا جوق',
'watchlistanontext'    => 'باقىلاۋ تٸزٸمٸڭٸزدەگٸ دانالاردى قاراۋ, نە ٶڭدەۋ ٷشٸن $1 قاجەت.',
'watchlistcount'       => "'''باقىلاۋ تٸزٸمٸڭٸزدە (تالقىلاۋ بەتتەردٸ قوسا) $1 دانا بار.'''",
'clearwatchlist'       => 'باقىلاۋ تٸزٸمٸن تازالاۋ',
'watchlistcleartext'   => 'سولاردى تولىق الاستاتۋعا باتىلسىز با؟',
'watchlistclearbutton' => 'باقىلاۋ تٸزٸمٸن تازالاۋ',
'watchlistcleardone'   => 'باقىلاۋ تٸزٸمٸڭٸز تازارتىلدى. $1 دانا الاستاتىلدى.',
'watchnologin'         => 'كٸرمەگەنسٸز',
'watchnologintext'     => 'باقىلاۋ تٸزٸمٸڭٸزدٸ ٶزگەرتۋ ٷشٸن [[{{ns:special}}:Userlogin|كٸرۋٸڭٸز]] جٶن.',
'addedwatch'           => 'باقىلاۋ تٸزٸمٸنە قوسىلدى',
'addedwatchtext'       => "«[[:$1]]» بەتٸ [[{{ns:special}}:Watchlist|باقىلاۋ تٸزٸمٸڭٸزگە]] قوسىلدى.
وسى بەتتٸڭ جٵنە سونىڭ تالقىلاۋ بەتٸنٸڭ كەلەشەكتەگٸ ٶزگەرٸستەرٸ مىندا تٸزٸمدەلەدٸ.
سوندا بەتتٸڭ اتاۋى تابۋعا جەڭٸلدەتٸپ [[{{ns:special}}:Recentchanges|جۋىقتاعى ٶزگەرٸستەر تٸزٸمٸندە]]
'''جۋان ٵرپٸمەن''' كٶرسەتٸلەدٸ.

وسى بەتتٸ سوڭىنان باقىلاۋ تٸزٸمنەن الاستاتىڭىز كەلسە «باقىلاماۋ» پاراعىن نۇقىڭىز.",
'removedwatch'         => 'باقىلاۋ تٸزٸمٸڭٸزدەن الاستاتىلدى',
'removedwatchtext'     => '«[[:$1]]» بەتٸ باقىلاۋ تٸزٸمٸڭٸزدەن الاستاتىلدى.',
'watch'                => 'باقىلاۋ',
'watchthispage'        => 'بەتتٸ باقىلاۋ',
'unwatch'              => 'باقىلاماۋ',
'unwatchthispage'      => 'باقىلاۋدى توقتاتۋ',
'notanarticle'         => 'ماعلۇمات بەتٸ ەمەس',
'watchnochange'        => 'كٶرسەتٸلگەن مەرزٸمدە ەشبٸر باقىلانعان دانا ٶڭدەلگەن جوق.',
'watchdetails'         => "* باقىلاۋ تٸزٸمٸندە (تالقىلاۋ بەتتەرٸسٸز) '''$1''' بەت بار.
* [[{{ns:special}}:Watchlist/edit|بٷكٸل تٸزٸمدٸ قاراۋ جٵنە ٶزگەرتۋ]].
* [[{{ns:special}}:Watchlist/clear|تٸزٸمدەگٸ بارلىق دانا الاستاتۋ]].",
'wlheader-enotif'      => '* ەسكەرتۋ حات جٸبەرۋٸ ەندٸرٸلگەن.',
'wlheader-showupdated' => "* سوڭعى كٸرگەنٸمنەن بەرٸ تٷزەتٸلگەن بەتتەردٸ '''جۋان''' مٵتٸنمەن كٶرسەت",
'watchmethod-recent'   => 'باقىلاۋلى بەتتەردٸڭ جۋىقتاعى ٶزگەرٸستەرٸن تەكسەرۋ',
'watchmethod-list'     => 'جۋىقتاعى ٶزگەرٸستەردە باقىلاۋلى بەتتەردٸ تەكسەرۋ',
'removechecked'        => 'بەلگٸلەنگەندٸ باقىلاۋ تٸزٸمٸنەن الاستاتۋ',
'watchlistcontains'    => 'باقىلاۋ تٸزٸمٸڭٸزدە {{PLURAL:$1|1 بەت|$1 بەت}} بار.',
'watcheditlist'        => "وسىندا ٵلٸپپەم سۇرىپتالعان باقىلانعان ماعلۇمات بەتتەرٸڭٸز تٸزٸمدەلٸنگەن.
بەتتەردٸ الاستاتۋ ٷشٸن ونىڭ قاسىنداعى قاباشاقتاردى بەلگٸلەپ, تٶمەندەگٸ ''بەلگٸلەنگەندٸ الاستات'' تٷيمەسٸن نۇقىڭىز
(ماعلۇمات بەتٸن جويعاندا تالقىلاۋ بەتٸ دە بٸرگە جويىلادى).",
'removingchecked'      => 'سۇرانعان دانالاردى باقىلاۋ تٸزٸمنەن الاستاۋى…',
'couldntremove'        => '«$1» دەگەن دانا الاستاتىلمادى…',
'iteminvalidname'      => '«$1» داناسىنىڭ جارامسىز اتاۋىنان شاتاق تۋدى…',
'wlnote'               => "تٶمەندە سوڭعى {{PLURAL:$2|ساعاتتا|'''$2''' ساعاتتا}} بولعان, {{PLURAL:$1|جۋىقتاعى ٶزگەرٸس|جۋىقتاعى '''$1''' ٶزگەرٸس}} كٶرسەتٸلگەن.",
'wlshowlast'           => 'سوڭعى $1 ساعاتتاعى, $2 كٷندەگٸ, $3 بولعان ٶزگەرٸستٸ كٶرسەتۋ',
'wlsaved'              => 'بۇل باقىلۋ تٸزٸمٸڭٸزدٸڭ ساقتالعان نۇسقاسى.',
'watchlist-show-bots'  => 'بوتتاردى كٶرسەت',
'watchlist-hide-bots'  => 'بوتتاردى جاسىر',
'watchlist-show-own'   => 'تٷزەتۋٸمدٸ كٶرسەت',
'watchlist-hide-own'   => 'تٷزەتۋٸمدٸ جاسىر',
'watchlist-show-minor' => 'شاعىن تٷزەتۋدٸ كٶرسەت',
'watchlist-hide-minor' => 'شاعىن تٷزەتۋدٸ جاسىر',
'wldone'               => 'ٸس بٸتتٸ.',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'باقىلاۋ…',
'unwatching' => 'باقىلاماۋ…',

'enotif_mailer'      => '{{SITENAME}} ەسكەرتۋ حات جٸبەرۋ قىزمەتٸ',
'enotif_reset'       => 'بارلىق بەت كارالدٸ دەپ بەلگٸلە',
'enotif_newpagetext' => 'مىناۋ جاڭا بەت.',
'changed'            => 'ٶزگەرتتٸ',
'created'            => 'جاسادى',
'enotif_subject'     => '{{SITENAME}} جوباسىندا $PAGEEDITOR $PAGETITLE اتاۋلى بەتتٸ $CHANGEDORCREATED',
'enotif_lastvisited' => 'سوڭعى كٸرۋٸڭٸزدەن بەرٸ بولعان ٶزگەرٸستەر ٷشٸن $1 دەگەندٸ قاراڭىز.',
'enotif_body'        => 'قۇرمەتتٸ $WATCHINGUSERNAME,

{{SITENAME}} جوباسىدا $PAGEEDITDATE كەزٸندە $PAGEEDITOR $PAGETITLE اتاۋلى بەتتٸ $CHANGEDORCREATED, اعىمدىق نۇسقاسىن $PAGETITLE_URL جايىنان قاراڭىز.

$NEWPAGE

ٶڭدەۋشٸ سيپاتتاماسى: $PAGESUMMARY $PAGEMINOREDIT

ٶڭدەۋشٸمەن قاتىناسۋ:
ە-پوشتا: $PAGEEDITOR_EMAIL
ۋيكي: $PAGEEDITOR_WIKI

بىلايعى ٶزگەرٸستەر بولعاندا دا سٸز وسى بەتكە بارعانشا دەيٸن ەشقانداي باسقا ەسكەرتۋ حاتتار جٸبەرٸلمەيدٸ. سونىمەن قاتار باقىلاۋ تٸزٸمٸڭٸزدەگٸ بەت ەسكەرتپەلٸك بەلگٸسٸن ٵدەپكە كٷيٸنە كەلتٸرٸڭٸز.

             سٸزدٸڭ دوستى {{SITENAME}} ەسكەرتۋ قىزمەتٸ

----
باقىلاۋ تٸزٸمٸڭٸزدٸ باپتاۋ ٷشٸن, مىندا بارىڭىز
{{fullurl:{{ns:special}}:Watchlist/edit}}

سىن-پٸكٸر بەرۋ جٵنە بىلايعى جٵردەم الۋ ٷشٸن:
{{fullurl:{{{{ns:mediawiki}}:helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'بەتتٸ جويۋ',
'confirm'                     => 'راستاۋ',
'excontent'                   => 'بولعان ماعلۇماتى: «$1»',
'excontentauthor'             => 'بولعان ماعلۇماتى: «$1» (تەك «[[Special:Contributions/$2|$2]]» ٷلەسٸ)',
'exbeforeblank'               => 'تازارتۋ الدىنداعى بولعان ماعلۇماتى: «$1»',
'exblank'                     => 'بەت بوستى بولدى',
'confirmdelete'               => 'جويۋدى راستاۋ',
'deletesub'                   => '(«$1» جويۋى)',
'historywarning'              => 'نازار سالىڭىز: جويۋعا ارنالعان بەتتە ٶز تاريحى بار:',
'confirmdeletetext'           => 'بەتتٸ نەمەسە سۋرەتتٸ بارلىق تاريحىمەن
بٸرگە دەرەكقوردان ٵردايىم جويىعىڭىز كەلەتٸن سيياقتى.
بۇنى جويۋدىڭ زاردابىن تٷسٸنٸپ شىن نيەتتەنگەنٸڭٸزدٸ, جٵنە
[[{{{{ns:mediawiki}}:policy-url}}]] دەگەنگە لايىقتى دەپ
سەنگەنٸڭٸزدٸ راستاڭىز.',
'policy-url'                 => '{{ns:project}}:ەرەجەلەر',
'actioncomplete'              => 'ٵرەكەت بٸتتٸ',
'deletedtext'                 => '«$1» جويىلدى.
جۋىقتاعى جويۋلار تۋرالى جازبالارىن $2 دەگەننەن قاراڭىز.',
'deletedarticle'              => '«[[$1]]» بەتٸن جويدى',
'dellogpage'                  => 'جويۋ_جۋرنالى',
'dellogpagetext'              => 'تٶمەندە جۋىقتاعى جويۋلاردىڭ تٸزٸمٸ بەرٸلگەن.',
'deletionlog'                 => 'جويۋ جۋرنالى',
'reverted'                    => 'ەرتەرەك نۇسقاسىنا قايتارىلعان',
'deletecomment'               => 'جويۋدىڭ سەبەبٸ',
'imagereverted'               => 'ەرتەرەك نۇسقاسىنا قايتارۋ سٵتتٸ ٶتتٸ.',
'rollback'                    => 'تٷزەتۋلەردٸ قايتارۋ',
'rollback_short'              => 'قايتارۋ',
'rollbacklink'                => 'قايتارۋ',
'rollbackfailed'              => 'قايتارۋ سٵتسٸز اياقتالدى',
'cantrollback'                => 'تٷزەتۋ قايتارىلمايدى. بۇل بەتتٸڭ سوڭعى ٷلەسكەرٸ تەك باستاۋىش اۋتورى.',
'alreadyrolled'               => '[[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|تالقىلاۋى]]) جاساعان [[:$1]]
دەگەننٸڭ سوڭعى ٶڭدەۋٸ قايتارىلمادى; كەيبٸرەۋ وسى قازٸر بەتتٸ ٶڭدەپ نە قايتارىپ جاتىر تٷگە.

سوڭعى ٶڭدەۋدٸ [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|تالقىلاۋى]]) دەگەندٸ جاساعان.',
'editcomment'                 => 'تٷزەتۋدٸڭ بولعان مٵندەمەسٸ: «<i>$1</i>».', # only shown if there is an edit comment
'revertpage'                  => '[[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|تالقىلاۋى]]) تٷزەتۋلەرٸن [[{{ns:user}}:$1|$1]] سوڭعى نۇسقاسىنا قايتاردى',
'sessionfailure'              => 'كٸرۋ سەسسيياسىندا شاتاق بولعان سيياقتى;
سەسسيياعا شابۋىلداۋداردان قورعانۋ ٷشٸن, وسى ٵرەكەت توقتاتىلدى.
«ارتقا» تٷيمەسٸن باسىڭىز, جٵنە بەتتٸ كەرٸ جٷكتەڭٸز, سوسىن قايتالاپ كٶرٸڭٸز.',
'protectlogpage'              => 'قورعاۋ_جۋرنالى',
'protectlogtext'              => 'تٶمەندە بەتتەردٸڭ قورعاۋ/قورعاماۋ تٸزٸمٸ بەرٸلگەن. اعىمداعى قورعاۋ ٵرەكتتەر بار بەتتەر ٷشٸن [[{{ns:special}}:Protectedpages|قورعالعان بەت تٸزٸمٸن]] قاراڭىز.',
'protectedarticle'            => '«$1» قورعالدى',
'unprotectedarticle'          => '«[[$1]]» قورعالمادى',
'protectsub'                  => '(«$1» قورعاۋدا)',
'confirmprotecttext'          => 'وسى بەتتٸ راسىندا دا قورعاۋ قاجەت پە؟',
'confirmprotect'              => 'قورعاۋدى راستاۋ',
'protectmoveonly'             => 'تەك جىلجىتۋدان قورعاۋ',
'protectcomment'              => 'قورعاۋ سەبەبٸ',
'protectexpiry'               => 'بٸتەتٸن مەرزٸمٸ',
'protect_expiry_invalid'      => 'بٸتەتٸن ۋاقىتى جارامسىز.',
'protect_expiry_old'          => 'بٸتەتٸن ۋاقىتى ٶتٸپ كەتكەن.',
'unprotectsub'                => '(«$1» قورعاماۋدا)',
'confirmunprotecttext'        => 'وسى بەتتٸ راستان قورعاماۋ قاجەت پە؟',
'confirmunprotect'            => 'قورعاماۋدى راستاۋ',
'unprotectcomment'            => 'قورعاماۋ سەبەبٸ',
'protect-unchain'             => 'جىلجىتۋعا رۇقسات بەرۋ',
'protect-text'                => '<strong>$1</strong> بەتٸنٸڭ قورعاۋ دەڭگەيٸن قاراي جٵنە ٶزگەرتە الاسىز.',
'protect-locked-blocked'      => 'بۇعاتتاۋىڭىز ٶشٸرٸلگەنشە دەيٸن قورعاۋ دەڭگەيٸن ٶزگەرتە المايسىز.
مىنا <strong>$1</strong> بەتتٸڭ اعىمدىق باپتاۋلارى:',
'protect-locked-dblock'       => 'دەرەكقوردىڭ قۇلىپتاۋى بەلسەندٸ بولعاندىقتان قورعاۋ دەڭگەيلەرٸ ٶزگەرتٸلمەيدٸ.
مىنا <strong>$1</strong> بەتتٸڭ اعىمدىق باپتاۋلارى:',
'protect-locked-access'       => 'تٸركەلگٸڭٸزگە بەت قورعاۋ دەنگەيلەرٸن ٶزگەرتۋٸنە رۇقسات جوق.
مىنا <strong>$1</strong> بەتتٸڭ اعىمدىق باپتاۋلارى:',
'protect-cascadeon'           => 'بۇل بەت اعىمدا قورعالعان, سەبەبٸ: وسى بەت باۋلى قورعاۋى بار كەلەسٸ {{PLURAL:$1|بەتكە|بەتتەرگە}} كٸرٸستٸرٸلگەن. بۇل بەتتٸڭ قورعاۋ دەڭگەيٸن ٶزگەرتە الاسىز, بٸراق بۇل باۋلى قورعاۋعا ىقپال ەتپەيدٸ.',
'protect-default'             => '(ٵدەپكٸ)',
'protect-level-autoconfirmed' => 'تٸركەلگٸسٸز پايدالانۋشىلارعا تيىم',
'protect-level-sysop'         => 'تەك ٵكٸمشٸلەرگە رۇقسات',
'protect-summary-cascade'     => 'باۋلى',
'protect-expiring'            => 'بٸتۋٸ: $1 (UTC)',
'protect-cascade'             => 'باۋلى قورعاۋ — بۇل بەتكە كٸرٸستٸرٸلگەن ٵرقايسى بەتتەردٸ قورعاۋ.',
'restriction-type'            => 'رۇقسات',
'restriction-level'           => 'رۇقسات دەڭگەيٸ',
'minimum-size'                => 'ەڭ از مٶلشەرٸ (بايت)',

# Restrictions (nouns)
'restriction-edit' => 'ٶڭدەۋ',
'restriction-move' => 'جىلجىتۋ',

# Restriction levels
'restriction-level-sysop'         => 'تولىق قورعالعان',
'restriction-level-autoconfirmed' => 'جارتىلاي قورعالعان',
'restriction-level-all'           => 'ٵرقايسى دەڭگەيدە',

# Undelete
'undelete'                 => 'جويىلعان بەتتەردٸ قاراۋ',
'undeletepage'             => 'جويىلعان بەتتەردٸ قاراۋ جٵنە قايتارۋ',
'viewdeletedpage'          => 'جويىلعان بەتتەردٸ قاراۋ',
'undeletepagetext'         => 'كەلەسٸ بەتتەر جويىلدى دەپ بەلگٸلەنگەن, بٸراق ماعلۇماتى مۇراعاتتا جاتقان,
سوندىقتان كەرٸ قايتارۋعا ٵزٸر. مۇراعات مەرزٸم بويىنشا تازالانىپ تۇرۋى مٷمكٸن.',
'undeleteextrahelp'        => "بٷكٸل بەتتٸ قايتارۋ ٷشٸن, بارلىق قاباشاقتاردى بوس قالدىرىپ
'''''قايتار!''''' تٷيمەسٸن نۇقىڭىز. بٶلەكشە قايتارۋ ورىنداۋ ٷشٸن, قايتارايىن دەگەن نۇسقالارىنا سٵيكەس
قاباشاقتارىن بەلگٸلەڭٸز دە, جٵنە '''''قايتار!''''' تٷيمەسٸن نۇقىڭىز. '''''تاستا''''' تٷيمەسٸن
نۇقىعاندا مٵندەمە اۋماعى مەن بارلىق قاباشاقتار تازالانادى.",
'undeleterevisions'        => '{{PLURAL:$1|بٸر نۇسقا|$1 نۇسقا}} مۇراعاتتالدى',
'undeletehistory'          => 'ەگەر بەت ماعلۇماتىن قايتارساڭىز,تاريحىندا بارلىق نۇسقالار دا
قايتارىلادى. ەگەر جويۋدان سوڭ دٵل سولاي اتاۋىمەن جاڭا بەت جاسالسا, قايتارىلعان نۇسقالار
تاريحتىڭ ەڭ ادىندا كٶرسەتٸلەدٸ, جٵنە كٶرسەتٸلٸپ تۇرعان بەتتٸڭ اعىمدىق نۇسقاسى
ٶزدٸكتٸ الماستىرىلمايدى. فايل نۇسقالارىنىڭ قايتارعاندا شەكتەۋلەرٸ جويىلاتىن ۇمىتپاڭىز.',
'undeleterevdel'           => 'ەگەر بەتتٸڭ ٷستٸڭگٸ نۇسقاسى جارىم-جارتىلاي جويىلعان بولسا جويىلعان قايتارۋى
 اتقارىلمايدى. وسىنداي جاعدايلاردا, ەڭ جاڭا جويىلعان نۇسقا بەلگٸلەۋٸن نەمەسە جاسىرۋىن الاستاتىڭىز.
كٶرۋٸڭٸزگە رۇقسات ەتٸلمەگەن فايل نۇسقالارى قايتارىلمايدى.',
'undeletehistorynoadmin'   => 'بۇل بەت جويىلعان. جويۋ سەبەبٸ الدىنداعى ٶڭدەگەن قاتىسۋشىلار
ەگجەي-تەگجەيلەرٸمەن بٸرگە تٶمەندەگٸ سيپاتتاماسىندا كٶرسەتٸلگەن.
وسى جويىلعان نۇسقالاردىڭ مٵتٸنٸ تەك ٵكٸمشٸلەرگە قاتىناۋلى.',
'undelete-revision'        => '$2 كەزٸندەگٸ $1 دەگەننٸڭ جويىلعان نۇسقاسى:',
'undeleterevision-missing' => 'جارامسىز نە جوعالعان نۇسقا. سٸلتەمەڭٸز جارامسىز بولۋى مٷمكٸن, نە
نۇسقا قايتارىلعان تٷگە نەمەسە مۇراعاتتان الاستاتىلعان.',
'undeletebtn'              => 'قايتار!',
'undeletereset'            => 'تاستا',
'undeletecomment'          => 'مٵندەمەسٸ:',
'undeletedarticle'         => '«[[$1]]» قايتاردى',
'undeletedrevisions'       => '{{plural:$1|نۇسقانى|$1 نۇسقانى}} قايتاردى',
'undeletedrevisions-files' => '{{plural:$1|نۇسقانى|$1 نۇسقانى}} جٵنە {plural:$2|فايلدى|$2 فايلدى}} قايتاردى',
'undeletedfiles'           => '{{plural:$1|1 فايل|$1 فايل}} قايتاردى',
'cannotundelete'           => 'قايتارۋ سٵتسٸز بٸتتٸ; تاعى بٸرەۋ سٸزدەن بۇرىن سول بەتتٸ قايتارعان بولار.',
'undeletedpage'            => "<big>'''$1 قايتارىلدى'''</big>

جۋىقتاعى جويۋ مەن قايتارۋ جٶنٸندە [[{{ns:special}}:Log/delete|جويۋ جۋرنالىن]] قاراڭىز.",
'undelete-header'          => 'جۋىقتاعى جويىلعان بەتتەر جٶنٸندە [[{{ns:special}}:Log/delete|جويۋ جۋرنالىن]] قاراڭىز.',
'undelete-search-box'      => 'جويىلعان بەتتەردٸ ٸزدەۋ',
'undelete-search-prefix'   => 'مىنادان باستالعان بەتتەردٸ كٶرسەت:',
'undelete-search-submit'   => 'ٸزدەۋ',
'undelete-no-results'      => 'جويۋ مۇراعاتىندا ەشقانداي سٵيكەس بەتتەر تابىلمادى.',

# Namespace form on various pages
'namespace' => 'ەسٸم اياسى:',
'invert'    => 'تالعاۋدى كەرٸلەۋ',

# Contributions
'contributions' => 'قاتىسۋشى ٷلەسٸ',
'mycontris'     => 'ٷلەسٸم',
'contribsub2'    => '$1 ($2) ٷلەسٸ',
'nocontribs'    => 'وسى ٸزدەۋ شارتىنا سٵيكەس ٶزگەرٸستەر تابىلعان جوق.',
'ucnote'        => 'تٶمەندە وسى قاتىسۋشىنىڭ سوڭعى <b>$2</b> كٷندەگٸ, سوڭعى <b>$1</b> ٶزگەرٸسٸ كٶرسەتلەدٸ.',
'uclinks'       => 'سوڭعى $2 كٷندەگٸ, سوڭعى $1 ٶزگەرٸسٸن قاراۋ.',
'uctop'         => ' (ٷستٸ)',

'sp-contributions-newest'      => 'ەڭ جاڭاسىنا',
'sp-contributions-oldest'      => 'ەڭ ەسكٸسٸنە',
'sp-contributions-newer'       => 'جاڭالاۋ $1',
'sp-contributions-older'       => 'ەسكٸلەۋ $1',
'sp-contributions-newbies'     => 'تەك جاڭا تٸركەلگٸدەن جاساعان ٷلەستەردٸ كٶرسەت',
'sp-contributions-newbies-sub' => 'جاڭادان تٸركەلگٸ جاساعاندار ٷشٸن',
'sp-contributions-blocklog'    => 'بۇعاتتاۋ جۋرنالى',
'sp-contributions-search'      => 'ٷلەس ٷشٸن ٸزدەۋ',
'sp-contributions-username'    => 'IP جاي نە قاتىسۋشى اتى:',
'sp-contributions-submit'      => 'ٸزدەۋ',

'sp-newimages-showfrom'        => '$1 كەزٸنەن بەرٸ — جاڭا سۋرەتتەردٸ كٶرسەت',

# What links here
'whatlinkshere'         => 'سٸلتەگەن بەتتەر',
'whatlinkshere-barrow'  => '&lt;',
'notargettitle'         => 'اقىرعى اتاۋ جوق',
'notargettext'          => 'وسى ٵرەكەت ورىندالاتىن نىسانا بەت,
نە قاتىسۋشى كٶرسەتٸلمەگەن.',
'linklistsub'           => '(سٸلتەمەلەر تٸزٸمٸ)',
'linkshere'             => "'''[[:$1]]''' دەگەنگە مىنا بەتتەر سٸلتەيدٸ:",
'nolinkshere'           => "'''[[:$1]]''' دەگەنگە ەش بەت سٸلتەمەيدٸ.",
'nolinkshere-ns'        => "تالعانعان ەسٸم اياسىندا '''[[:$1]]''' دەگەنگە ەشقانداي بەت سٸلتەمەيدٸ.",
'isredirect'            => 'ايداتۋ بەتٸ',
'istemplate'            => 'كٸرٸكتٸرۋ',
'whatlinkshere-prev'    => '{{PLURAL:$1|الدىڭعى|الدىڭعى $1}}',
'whatlinkshere-next'    => '{{PLURAL:$1|كەلەسٸ|كەلەسٸ $1}}',

# Block/unblock
'blockip'                     => 'پايدالانۋشىنى بۇعاتتاۋ',
'blockiptext'                 => 'تٶمەندەگٸ ٷلگٸت پايدالانۋشىنىڭ جازۋ 
رۇقساتىن بەلگٸلٸ IP جايىمەن نە اتاۋىمەن بۇعاتتاۋ ٷشٸن قولدانىلادى.
بۇنى تەك بۇزاقىلىققا كەدەرگٸ ٸستەۋ ٷشٸن جٵنە دە
[[{{{{ns:mediawiki}}:policy-url}}|ەرەجەلەر]] بويىنشا اتقارۋىڭىز جٶن.
تٶمەندە تيٸستٸ سەبەبٸن تولتىرىپ كٶرسەتٸڭٸز (مىسالى, دٵيەككە بۇزاقىلىقپەن
ٶزگەرتكەن بەتتەردٸ كەلتٸرٸپ).',
'ipaddress'                   => 'IP جاي',
'ipadressorusername'          => 'IP جاي نە اتى',
'ipbexpiry'                   => 'بٸتەتٸن مەرزٸمٸ',
'ipbreason'                   => 'سەبەبٸ',
'ipbreasonotherlist'          => 'باسقا سەبەپ',
// These are examples only. They can be translated but should be adjusted via
// [[MediaWiki:ipbreason-list]] by the local community
// defines a block reason not part of a group
// * defines a block reason group in the drow down menu
// ** defines a block reason
// To disable this drop down menu enter '-' in [[MediaWiki:ipbreason-dropdown]].
'ipbreason-dropdown'    => '
* بۇعاتتاۋدىڭ جالپى سەبەبتەرٸ 
** بۇزاقىلىق: جالعان مٵلٸمەت ەنگٸزۋ 
** بۇزاقىلىق: بەتتەردەگٸ ماعلۇماتتى جويۋ 
** بۇزاقىلىق: سىرتقى توراپتار سٸلتەمەلەرٸن جاۋدىرۋ 
** بۇزاقىلىق: بەتتەرگە بٶستەكٸلٸك/قيسىنسىزدىق كٸرٸسترٸرۋ 
** قوقانداۋ/قۋعىنداۋ مٸنەزقۇلىق 
** كٶپتەگەن تٸركەلگٸلەردٸ جاساپ قيياناتتاۋ 
** قولايسىز قاتىسۋشى اتاۋى',
'ipbanononly'                 => 'تەك تٸركەلگٸسٸز پايدالانۋشىلاردى بۇعاتتاۋ',
'ipbcreateaccount'            => 'تٸركەلگٸ جاساۋىن كەدەرگٸلەۋ',
'ipbenableautoblock'          => 'بۇل پايدالانۋشى سوڭعى قولدانعان IP جايى, جٵنە كەيٸن تٷزەتۋ ٸستەۋگە بايقاپ قارالعان ٵرقايسى IP جايلارى ٶزدٸكتٸ بۇعاتتالسىن',
'ipbsubmit'                   => 'پايدالانۋشىنى بۇعاتتاۋ',
'ipbother'                    => 'باسقا مەرزٸم',
'ipboptions'                  => '2 ساعات:2 hours,1 كٷن:1 day,3 كٷن:3 days,1 اپتا:1 week,2 اپتا:2 weeks,1 اي:1 month,3 اي:3 months,6 اي:6 months,1 جىل:1 year,مٵنگٸ:infinite',
'ipbotheroption'              => 'باسقا',
'ipbotherreason'              => 'باسقا/قوسىمشا سەبەپ',
'ipbhidename'                 => 'بۇعاتتاۋ جۋرنالىنداعى, بەلسەندٸ بۇعاتتاۋ تٸزٸمٸندەگٸ, قاتىسۋشى تٸزٸمٸننەگٸ اتى/IP جاسىرىلسىن',
'badipaddress'                => 'جارامسىز IP جاي',
'blockipsuccesssub'           => 'بۇعاتتاۋ سٵتتٸ ٶتتٸ',
'blockipsuccesstext'          => '[[{{ns:special}}:Contributions/$1|$1]] دەگەن بۇعاتتالعان.
<br />بۇعاتتاردى شولىپ شىعۋ ٷشٸن [[{{ns:special}}:Ipblocklist|IP بۇعاتتاۋ تٸزٸمٸن]] قاراڭىز.',
'ipb-edit-dropdown'           => 'بۇعاتتاۋ سەبەپتەرٸن ٶڭدەۋ',
'ipb-unblock-addr'            => '$1 دەگەندٸ بۇعاتتاماۋ',
'ipb-unblock'                 => 'قاتىسۋشى اتىن نەمەسە IP جايىن بۇعاتتاماۋ',
'ipb-blocklist-addr'          => '$1 ٷشٸن بار بۇعاتتاۋلاردى قاراۋ',
'ipb-blocklist'               => 'بار بۇعاتتاۋلاردى قاراۋ',
'unblockip'                   => 'پايدالانۋشىنى بۇعاتتاماۋ',
'unblockiptext'               => 'تٶمەندەگٸ ٷلگٸت بەلگٸلٸ IP جايىمەن نە اتاۋىمەن
بۇرىن بۇعاتتالعان پايدالانۋشىنىڭ جازۋ رۇقساتىن قايتارۋ ٷشٸن قولدانىلادى.',
'ipusubmit'                   => 'وسى جايدى بۇعاتتاماۋ',
'unblocked'                   => '[[{{ns:user}}:$1|$1]] بۇعاتتاۋى ٶشٸرٸلدٸ',
'ipblocklist'                 => 'بۇعاتتالعان پايدالانۋشى / IP- جاي تٸزٸمٸ',
'ipblocklist-submit'          => 'ٸزدەۋ',
'blocklistline'               => '$1, $2 «$3» دەگەندٸ بۇعاتتادى ($4)',
'infiniteblock'               => 'مٵنگٸ',
'expiringblock'               => 'بٸتۋٸ: $1',
'anononlyblock'               => 'تەك تٸركەلگٸسٸزدٸ',
'noautoblockblock'            => 'ٶزدٸك بۇعاتتاۋ ٶشٸرٸلەنگەن',
'createaccountblock'          => 'تٸركەلگٸ جاساۋى بۇعاتتالعان',
'ipblocklistempty'            => 'بۇعاتتاۋ تٸزٸمٸ بوس, نەمەسەرۇرانىسقان IP جاي/قاتىسۋشى اتى بۇعاتتالعان جوق.',
'blocklink'                   => 'بۇعاتتاۋ',
'unblocklink'                 => 'بۇعاتتاماۋ',
'contribslink'                => 'ٷلەسٸ',
'autoblocker'                 => "IP جايىڭىزدى جۋىقتا «[[{{ns:user}}:1|$1]]» پايدالانعان, سوندىقتان ٶزدٸك تٷردە بۇعاتتالعان. $1 بۇعاتتاۋ سەبەبٸ: «$2».",
'blocklogpage'                => 'بۇعاتتاۋ_جۋرنالى',
'blocklogentry'               => '«[[$1]]» دەگەندٸ $2 مەرزٸمگە بۇعاتتادى $3',
'blocklogtext'                => 'بۇل پايدالانۋشىلاردى بۇعاتتاۋ/بۇعاتتاماۋ ٵرەكەتتەرٸنٸڭ جۋرنالى. ٶزدٸك تٷردە
بۇعاتتالعان IP جايلار وسىندا تٸزٸمدەلگەمەگەن. اعىمداعى بەلسەندٸ بۇعاتتاۋلارىن
[[{{ns:special}}:Ipblocklist|IP بۇعاتتاۋ تٸزٸمٸنەن]] قاراۋعا بولادى.',
'unblocklogentry'             => '«$1» دەگەننٸڭ بۇعاتتاۋىن ٶشٸردٸ',
'block-log-flags-anononly'    => 'تەك تٸركەلمەگەندەر',
'block-log-flags-nocreate'    => 'تٸركەلگٸ جاساۋ ٶشٸرٸلگەن',
'block-log-flags-noautoblock'   => 'ٶزدٸكتٸ بۇعاتتاعىش ٶشٸرٸلگەن',
'range_block_disabled'        => 'اۋقىم بۇعاتتاۋىن جاساۋ ٵكٸمشٸلٸك مٷمكٸندٸگٸ ٶشٸرٸلگەن.',
'ipb_expiry_invalid'          => 'بٸتەتٸن ۋاقىتى جارامسىز.',
'ipb_already_blocked'         => '«$1» بۇعاتتالعان تٷگە',
'ip_range_invalid'            => 'IP جاي اۋقىمى جارامسىز.',
'proxyblocker'                => 'پروكسي سەرۆەرلەردٸ بۇعاتتاۋىش',
'ipb_cant_unblock'            => 'قاتە: IP $1 بۇعاتتاۋى تابىلمادى. ونىڭ بۇعاتتاۋى ٶشٸرلگەن سيياقتى.',
'proxyblockreason'            => 'IP جايىڭىز اشىق پروكسي سەرۆەرگە جاتاتىندىقتان بۇعاتتالعان. ينتەرنەت قىزمەتٸن جابدىقتاۋشىڭىزبەن, نە تەحنيكالىق مەدەۋ قىزمەتٸمەن قاتىناسىڭىز, جٵنە ولارعا وسى وتە كٷردەلٸ قاۋىپسٸزدٸك شاتاق تۋرالى اقپارات بەرٸڭٸز.',
'proxyblocksuccess'           => 'بٸتتٸ.',
'sorbs'                       => 'DNSBL قارا تٸزٸمٸ',
'sorbsreason'                 => 'سٸزدٸڭ IP جايىڭىز وسى توراپتا قولدانىلعان DNSBL قارا تٸزٸمٸندەگٸ اشىق پروكسي-سەرۆەر دەپ تابىلادى.',
'sorbs_create_account_reason' => 'سٸزدٸڭ IP جايىڭىز وسى توراپتا قولدانىلعان DNSBL قارا تٸزٸمٸندەگٸ اشىق پروكسي-سەرۆەر دەپ تابىلادى. تٸركەلگٸ جاساي المايسىز.',

# Developer tools
'lockdb'              => 'دەرەكقوردى قۇلىپتاۋ',
'unlockdb'            => 'دەرەكقوردى قۇلىپتاماۋ',
'lockdbtext'          => 'دەرەكقوردىن قۇلىپتالۋى بارلىق پايدالانۋشىنىڭ
بەت ٶڭدەۋ, باپتاۋىن قالاۋ, باقىلاۋ تٸزٸمٸن, تاعى باسقا
دەرەكقوردى ٶزگەرتەتٸن مٷمكٸندٸكتەرٸن توقتاتا تۇرادى.
وسى ماقساتىڭىزدى, جٵنە جٶندەۋٸڭٸز بٸتكەندە
دەرەكقوردى اشاتىڭىزدى راستاڭىز.',
'unlockdbtext'        => 'دەرەكقودىن اشىلۋى بارلىق پايدالانۋشىنىڭ بەت ٶڭدەۋ,
باپتاۋىن قالاۋ, باقىلاۋ تٸزٸمٸن, تاعى باسقا دەرەكقوردى ٶزگەرتەتٸن
مٷمكٸندٸكتەرٸن قايتا اشادى.
وسى ماقساتىڭىزدى راستاڭىز.',
'lockconfirm'         => 'يٵ, مەن دەرەكقوردى راستان قۇلىپتايمىن.',
'unlockconfirm'       => 'يٵ, مەن دەرەكقوردى راستان قۇلىپتامايمىن.',
'lockbtn'             => 'دەرەكقوردى قۇلىپتا',
'unlockbtn'           => 'دەرەكقوردى قۇلىپتاما',
'locknoconfirm'       => 'راستاۋ بەلگٸسٸن قويماپسىز.',
'lockdbsuccesssub'    => 'دەرەكقوردى قۇلىپتاۋ سٵتتٸ ٶتتٸ',
'unlockdbsuccesssub'  => 'قۇلىپتالعان دەرەكقور اشىلدى',
'lockdbsuccesstext'   => 'دەرەكقور قۇلىپتالدى.
<br />جٶندەۋٸڭٸز بٸتكەننەن كەيٸن [[{{ns:special}}:Unlockdb|قۇلىپتاۋىن ٶشٸرۋگە]] ۇمىتپاڭىز.',
'unlockdbsuccesstext' => 'قۇلىپتالعان دەرەكقور سٵتتٸ اشىلدى.',
'lockfilenotwritable' => 'دەرەكقور قۇلىپتاۋ فايلى جازىلمايدى. دەرەكقوردى قۇلىپتاۋ نە اشۋ ٷشٸن, ۆەب-سەرۆەر فايلعا جازۋ رۇقساتى بولۋ قاجەت.',
'databasenotlocked'   => 'دەرەكقور قۇلىپتالعان جوق.',

# Move page
'movepage'                => 'بەتتٸ جىلجىتۋ',
'movepagetext'            => "تٶمەندەگٸ ٷلگٸتتٸ قولدانىپ بەتتەردٸ قايتا اتايدى,
بارلىق تاريحىن جاڭا اتاۋعا جىلجىتادى.
بۇرىنعى بەت اتاۋى جاڭا اتاۋعا ايداتاتىن بەت بولادى.
ەسكٸ اتاۋىنا سٸلتەيتٸن  سٸلتەمەلەر ٶزگەرتٸلمەيدٸ; جىلجىتۋدان سوڭ
شىنجىرلى ايداتۋلار بار-جوعىن تەكسەرٸڭٸز.
سٸلتەمەلەر بۇرىنعى جولداۋىمەن بىلايعى ٶتۋٸن تەكسەرۋٸنە
سٸز مٸندەتتٸ بولاسىز.

ەسكەرٸڭٸز, ەگەر جىلجىتىلاتىن اتاۋدا بەت بولسا, سول ەسكٸ بەتكە ايداتۋ
بولعانشا جٵنە تاريحى بولسا, بەت '''جىلجىتىلمايدى'''.
وسىنىڭ ماعىناسى: ەگەر بەتتٸ قاتەلٸك پەن قايتا اتالسا,
بۇرىنعى اتاۋىنا قايتا اتاۋعا بولادى,
بٸراق بار بەتتٸڭ ٷستٸنە جازۋعا بولمايدى.

<b>نازار سالىڭىز!</b>
بۇل دٵرٸپتٸ بەتكە قاتاڭ جٵنە كەنەت ٶزگەرٸس جاساۋعا مٷمكٸن;
ٵرەكەتتٸڭ الدىنان وسىنىڭ زارداپتارىن تٷسٸنگەنٸڭٸزگە باتىل
بولىڭىز.",
'movepagetalktext'        => "كەلەسٸ سەبەپتەر '''بولعانشا''' دەيٸن, تالقىلاۋ بەتٸ ٶزدٸك تٷردە بٸرگە جىلجىتىلادى:
* بوس ەمەس تالقىلاۋ بەتٸ جاڭا اتاۋدا بولعاندا, نەمەسە
* تٶمەندەگٸ قابىشاقتا بەلگٸنٸ الاستاتقاندا.

وسى ورايدا, قالاۋىڭىز بولسا, بەتتٸ قولدان جىلجىتا نە قوسا الاسىز.",
'movearticle'             => 'بەتتٸ جىلجىتۋ',
'movenologin'             => 'جٷيەگە كٸرمەگەنسٸز',
'movenologintext'         => 'بەتتٸ جىلجىتۋ ٷشٸن تٸركەلگەن بولۋىڭىز جٵنە
 [[{{ns:special}}:Userlogin|كٸرۋٸڭٸز]] قاجەت.',
'newtitle'                => 'جاڭا اتاۋعا',
'move-watch'              => 'بۇل بەتتٸ باقىلاۋ',
'movepagebtn'             => 'بەتتٸ جىلجىت',
'pagemovedsub'            => 'جىلجىتۋ سٵتتٸ اياقتالدى',
'pagemovedtext'           => '«[[$1]]» بەتٸ «[[$2]]» بەتٸنە جىلجىتىلدى.',
'articleexists'           => 'بىلاي اتاۋلى بەت بار بولدى, نە تاڭداعان
اتاۋىڭىز جارامدى ەمەس.
باسقا اتاۋ تانداڭىز',
'talkexists'              => "'''بەتتٸڭ ٶزٸ سٵتتٸ جىلجىتىلدى, بٸراق تالقىلاۋ بەتٸ بٸرگە جىلجىتىلمادى, ونىڭ سەبەبٸ جاڭا اتاۋدىڭ تالقىلاۋ بەتٸ بار تٷگە. بۇنى قولمەن قوسىڭىز.'''",
'movedto'                 => 'مىناعان جىلجىتىلدى:',
'movetalk'                => 'قاتىستى تالقىلاۋ بەتٸمەن بٸرگە جىلجىتۋ',
'talkpagemoved'           => 'قاتىستى تالقىلاۋ بەتٸ دە جىلجىتىلدى.',
'talkpagenotmoved'        => 'قاتىستى تالقىلاۋ بەتٸ <strong>جىلجىتىلمادى</strong>.',
'1movedto2'               => '«[[$1]]» بەتٸندە ايداتۋ قالدىرىپ «[[$2]]» بەتٸنە جىلجىتتى',
'1movedto2_redir'         => '«[[$1]]» بەتٸن «[[$2]]» ايداتۋ بەتٸنٸڭ ٷستٸنە جىلجىتتى',
'movelogpage'             => 'جىلجىتۋ جۋرنالى',
'movelogpagetext'         => 'تٶمەندە جىلجىتىلعان بەتتەردٸڭ تٸزٸمٸ بەرٸلٸپ تۇر.',
'movereason'              => 'سەبەبٸ',
'revertmove'              => 'قايتارۋ',
'delete_and_move'         => 'جويۋ جٵنە جىلجىتۋ',
'delete_and_move_text'    => '==جويۋ قاجەت==

اقىرعى «[[$1]]» بەت اتاۋى بار تٷگە.
جىلجىتۋعا جول بەرۋ ٷشٸن جويامىز با؟',
'delete_and_move_confirm' => 'يٵ, وسى بەتتٸ جوي',
'delete_and_move_reason'  => 'جىلجىتۋعا جول بەرۋ ٷشٸن جويىلعان',
'selfmove'                => 'قاينار جٵنە اقىرعى اتاۋى بٸردەي; بەت ٶزٸنە جىلجىتىلمايدى.',
'immobile_namespace'      => 'قاينار نەمەسە اقىرعى اتاۋى ارنايى تٷرٸندە بولدى; وسىنداي ەسٸم اياسى جاعىنا جٵنە جاعىنان بەتتەر جىلجىتىلمايدى.',

# Export
'export'          => 'بەتتەردٸ سىرتقا بەرۋ',
'exporttext'      => 'XML پٸشٸمٸنە قاپتالعان بٶلەك بەت نە بەتتەر بۋماسى
مٵتٸنٸڭ جٵنە ٶڭدەۋ تاريحىن سىرتقا بەرە الاسىز. وسىنى, باسقا ۋيكيگە
جٷيەنٸڭ [[{{ns:special}}:Import|سىرتتان الۋ بەتٸن]] پايدالانىپ, الۋعا بولادى.

بەتتەردٸ سىرتقا بەرۋ ٷشٸن, اتاۋلارىن تٶمەندەگٸ مٵتٸن اۋماعىنا ەنگٸزٸڭٸز,
بٸر جولدا بٸر اتاۋ, جٵنە تانداڭىز: نە اعىمدىق نۇسقاسىن, بارلىق ەسكٸ نۇسقالارى مەن
جٵنە تاريحى جولدارى مەن بٸرگە, نە دٵل اعىمدىق نۇسقاسىن, سوڭعى ٶڭدەۋ تۋرالى اقپاراتى مەن بٸرگە.

سوڭعى جاعدايدا سٸلتەمەنٸ دە, مىسالى {{{{ns:mediawiki}}:mainpage}} بەتٸ ٷشٸن [[{{ns:special}}:Export/{{MediaWiki:mainpage}}]] قولدانۋعا بولادى.',
'exportcuronly'   => 'تولىق تاريحىن ەمەس, تەك اعىمدىق نۇسقاسىن كٸرٸستٸرٸڭٸز',
'exportnohistory' => "----
'''اڭعارتپا:''' ٶنٸمدٸلٸك ٵسەرٸ سەبەپتەرٸنەن, بەتتەر تولىق تاريحىن سىرتقا بەرۋٸ ٶشٸرٸلگەن.",
'export-submit'   => 'سىرتقا بەر',
'export-addcattext' => 'مىنا ساناتتاعى بەتتەردٸ ٷستەۋ:',
'export-addcat' => 'ٷستە',

# Namespace 8 related
'allmessages'               => 'جٷيە حابارلارى',
'allmessagesname'           => 'اتاۋى',
'allmessagesdefault'        => 'ٵدەپكٸ مٵتٸنٸ',
'allmessagescurrent'        => 'اعىمدىق مٵتٸنٸ',
'allmessagestext'           => 'مىندا «MediaWiki:» ەسٸم اياسىنداعى بارلىق قاتىناۋلى جٷيە حابار تٸزٸمٸ بەرٸلٸپ تۇر.',
'allmessagesnotsupportedUI' => 'Your current interface language <b>$1</b> is not supported by Special:Allmessages at this site.',
'allmessagesnotsupportedDB' => "'''wgUseDatabaseMessages''' بابى ٶشٸرٸلگەن سەبەبٸنەن '''{{ns:special}}:AllMessages''' سيپاتى سٷەمەلدەنبەيدٸ.",
'allmessagesfilter'         => 'حاباردى اتاۋى بويىنشا سٷزگٸلەۋ:',
'allmessagesmodified'       => 'تەك ٶزگەرتٸلگەندٸ كٶرسەت',

# Thumbnails
'thumbnail-more'           => 'ٷلكەيتۋ',
'missingimage'    => '<b>جوعالعان سۋرەت </b><br /><i>$1</i>',
'filemissing'     => 'جوعالعان فايل',
'thumbnail_error'          => 'نوباي قۇرۋ قاتەسٸ: $1',
'djvu_page_error'          => 'DjVu بەتٸ مٷمكٸندٸ اۋماقتىڭ سىرتىنددا',
'djvu_no_xml'              => 'DjVu فايلىنا XML كەلتٸرۋگە بولمايدى',
'thumbnail_invalid_params' => 'نوبايدىڭ باپتارى جارامسىز ',
'thumbnail_dest_directory' => 'اقىرعى قالتا جاسالمادى',
# Special:Import
'import'                     => 'بەتتەردٸ سىرتتان الۋ',
'importinterwiki'            => 'ۋيكي-تاسىمالداپ سىرتتان الۋ',
'import-interwiki-text'      => 'سىرتتان الاتىن ۋيكي جوباسىن جٵنە بەت اتاۋىن تانداڭىز.
نۇسقا كٷن-ايى جٵنە ٶڭدەۋشٸ اتتارى ساقتالادى.
بارلىق ۋيكي-تاسىمالداپ سىرتتان الۋ ٵرەكەتتەر [[{{ns:special}}:Log/import|سىرتتان الۋ جۋرنالىنا]] جازىلىپ الىنادى.',
'import-interwiki-history'   => 'وسى بەتتٸڭ بارلىق تاريحي نۇسقالارىن كٶشٸرۋ',
'import-interwiki-submit'    => 'سىرتتان الۋ',
'import-interwiki-namespace' => 'مىنا ەسٸم اياسىنا بەتتەردٸ تاسىمالداۋ:',
'importtext'                 => 'قاينار ۋيكيدەن «Special:Export» قۋرالىن قولدانىپ, فايلدى سىرتقا بەرٸڭٸز, ديسكٸڭٸزگە ساقتاڭىز, سوسىن مىندا قوتارىڭىز.',
'importstart'                => 'بەتتەردٸ سىرتتان الۋى…',
'import-revision-count'      => '$1 نۇسقا',
'importnopages'              => 'سىرتتان الىناتىن بەتتەر جوق.',
'importfailed'               => 'سىرتتان الۋ سٵتسٸز بٸتتٸ: $1',
'importunknownsource'        => 'Cىرتتان الۋ قاينار تٷرٸ تانىمالسىز',
'importcantopen'             => 'سىرتتان الۋ فايلى اشىلمايدى',
'importbadinterwiki'         => 'جارامسىز ۋيكي-ارالىق سٸلتەمە',
'importnotext'               => 'بوستى, نە مٵتٸنٸ جوق',
'importsuccess'              => 'سىرتتان الۋ سٵتتٸ اياقتالدى!',
'importhistoryconflict'      => 'تاريحىنىڭ ەگەس نۇسقالارى بار (بۇل بەتتٸ الدىندا سىرتتان الىنعان سيياقتى)',
'importnosources'            => 'ەشقانداي ۋيكي-تاسىمالداپ سىرتتان الۋ قاينارى بەلگٸلەنمەگەن, جٵنە تاريحىن تٸكەلەي قوتارۋى ٶشٸرٸلگەن.',
'importnofile'               => 'سىرتتان الىناتىن فايل قوتارىلعان جوق.',
'importuploaderror'          => 'سىرتتان الۋ فايلدىڭ قوتارۋى سٵتسٸز بٸتتٸ; وسى فايل مٶلشەرٸ رۇقسات ەتٸلگەن مٶلشەردەن اسۋى مٷمكٸن.',

# Import log
'importlogpage'                    => 'سىرتتان الۋ جۋرنالى',
'importlogpagetext'                => 'باسقا ۋيكيلەردەن ٶڭدەۋ تاريحىمەن بٸرگە بەتتەردٸ ٵكٸمشٸلٸك رەتٸندە سىرتتان الۋ.',
'import-logentry-upload'           => 'فايل قوتارۋىمەن سىرتتان «[[$1]]» بەتٸ الىندى',
'import-logentry-upload-detail'    => '$1 نۇسقا',
'import-logentry-interwiki'        => 'ۋيكي-تاسىمالدانعان $1',
'import-logentry-interwiki-detail' => '$2 دەگەننەن $1 نۇسقا',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'جەكە بەتٸم',
'tooltip-pt-anonuserpage'         => 'وسى IP جايدىڭ جەكە بەتٸ',
'tooltip-pt-mytalk'               => 'تالقىلاۋ بەتٸم',
'tooltip-pt-anontalk'             => 'وسى IP جاي تٷزەتۋلەرٸن تالقىلاۋ',
'tooltip-pt-preferences'          => 'باپتاۋىم',
'tooltip-pt-watchlist'            => 'ٶزگەرٸستەرٸن باقىلاپ تۇرعان بەتتەر تٸزٸمٸم.',
'tooltip-pt-mycontris'            => 'ٷلەستەرٸمدٸڭ تٸزٸمٸ',
'tooltip-pt-login'                => 'كٸرۋٸڭٸزدٸ ۇسىنامىز, ول مٸندەتتٸ ەمەس.',
'tooltip-pt-anonlogin'            => 'كٸرۋٸڭٸزدٸ ۇسىنامىز, بٸراق, ول مٸندەتتٸ ەمەس.',
'tooltip-pt-logout'               => 'شىعۋ',
'tooltip-ca-talk'                 => 'ماعلۇمات بەتتٸ تالقىلاۋ',
'tooltip-ca-edit'                 => 'بۇل بەتتٸ ٶڭدەي الاسىز. ساقتاۋدىڭ الدىندا «قاراپ شىعۋ» تٷيمەسٸن نۇقىڭىز.',
'tooltip-ca-addsection'           => 'بۇل تالقىلاۋ بەتٸندە جاڭا تاراۋ باستاۋ.',
'tooltip-ca-viewsource'           => 'بۇل بەت قورعالعان, بٸراق, قاينارىن قاراۋعا بولادى.',
'tooltip-ca-history'              => 'بۇل بەتتٸن جۋىقتاعى نۇسقالارى.',
'tooltip-ca-protect'              => 'بۇل بەتتٸ قورعاۋ',
'tooltip-ca-delete'               => 'بۇل بەتتٸ جويۋ',
'tooltip-ca-undelete'             => 'بۇل بەتتٸڭ جويۋدىڭ الدىنداعى بولعان تٷزەتۋلەرٸن قايتارۋ',
'tooltip-ca-move'                 => 'بۇل بەتتٸ جىلجىتۋ',
'tooltip-ca-watch'                => 'بۇل بەتتٸ باقىلاۋ تٸزٸمٸڭٸزگە ٷستەۋ',
'tooltip-ca-unwatch'              => 'بۇل بەتتٸ باقىلاۋ تٸزٸمٸڭٸزدەن الاستاتۋ',
'tooltip-search'                  => '{{SITENAME}} جوباسىنان ٸزدەستٸرۋ',
'tooltip-p-logo'                  => 'باستى بەتكە',
'tooltip-n-mainpage'              => 'باستى بەتكە بارىپ كەتٸڭٸز',
'tooltip-n-portal'                => 'جوبا تۋرالى, نە ٸستەۋٸڭٸزگە بولاتىن, قايدان تابۋعا بولاتىن تۋرالى',
'tooltip-n-currentevents'         => 'اعىمداعى وقيعالارعا قاتىستى اقپارات',
'tooltip-n-recentchanges'         => 'وسى ۋيكيدەگٸ جۋىقتاعى ٶزگەرٸستەر تٸزٸمٸ.',
'tooltip-n-randompage'            => 'كەزدەيسوق بەتتٸ جٷكتەۋ',
'tooltip-n-help'                  => 'انىقتاما تابۋ ورنى.',
'tooltip-n-sitesupport'           => 'بٸزگە جٵردەم ەتٸڭٸز',
'tooltip-t-whatlinkshere'         => 'مىندا سٸلتەگەن بارلىق بەتتەردٸڭ تٸزٸمٸ',
'tooltip-t-recentchangeslinked'   => 'مىننان سٸلتەنگەن بەتتەردٸڭ جۋىقتاعى ٶزگەرٸستەرٸ',
'tooltip-feed-rss'                => 'بۇل بەتتٸڭ RSS ارناسى',
'tooltip-feed-atom'               => 'بۇل بەتتٸڭ Atom ارناسى',
'tooltip-t-contributions'         => 'وسى قاتىسۋشىنىڭ ٷلەس تٸزٸمٸن قاراۋ',
'tooltip-t-emailuser'             => 'وسى قاتىسۋشىعا email جٸبەرۋ',
'tooltip-t-upload'                => 'سۋرەت نە مەديا فايلدارىن قوتارۋ',
'tooltip-t-specialpages'          => 'بارلىق ارنايى بەتتەر تٸزٸمٸ',
'tooltip-ca-nstab-main'           => 'ماعلۇمات بەتٸن قاراۋ',
'tooltip-ca-nstab-user'           => 'قاتىسۋشى بەتٸن قاراۋ',
'tooltip-ca-nstab-media'          => 'تاسپا بەتٸن قاراۋ',
'tooltip-ca-nstab-special'        => 'بۇل ارنايى بەت, بەتتٸڭ ٶزٸ ٶڭدەلٸنبەيدٸ.',
'tooltip-ca-nstab-project'        => 'جوبا بەتٸن قاراۋ',
'tooltip-ca-nstab-image'          => 'سۋرەت بەتٸن قاراۋ',
'tooltip-ca-nstab-mediawiki'      => 'جٷيە حابارىن قاراۋ',
'tooltip-ca-nstab-template'       => 'ٷلگٸنٸ قاراۋ',
'tooltip-ca-nstab-help'           => 'انىقتىما بەتٸن قاراۋ',
'tooltip-ca-nstab-category'       => 'سانات بەتٸن قاراۋ',
'tooltip-minoredit'               => 'وسىنى شاعىن تٷزەتۋ دەپ بەلگٸلەۋ',
'tooltip-save'                    => 'تٷزەتۋٸڭٸزدٸ ساقتاۋ',
'tooltip-preview'                 => 'ساقتاۋدىڭ الدىنان تٷزەتۋٸڭٸزدٸ قاراپ شىعىڭىز!',
'tooltip-diff'                    => 'مٵتٸنگە قانداي ٶزگەرٸستەردٸ جاساعانىڭىزدى قاراۋ.',
'tooltip-compareselectedversions' => 'بەتتٸڭ ەكٸ نۇسقاسىنىڭ ايىرماسىن قاراۋ.',
'tooltip-watch'                   => 'بۇل بەتتٸ باقىلاۋ تٸزٸمٸڭٸزگە ٷستەۋ',
'tooltip-recreate'                => 'بەت جويىلعانىنا قاراماستان قايتا جاساۋ',

# Stylesheets
'common.css'   => '/* مىنداعى CSS ٵمٸرلەرٸ بارلىق بەزەندٸرۋ مٵنەرٸندەردە قولدانىلادى */',
'monobook.css' => '/* مىنداعى CSS ٵمٸرلەرٸ «دارا كٸتاپ» بەزەندٸرۋ مٵنەرٸن پايدالانۋشىلارعا ٵسەر ەتەدٸ */',

# Scripts
'common.js'   => '/* Мындағы JavaScript әмірлері әрқайсы бет қаралғанда барлық пайдаланушыларға жүктеледі. */

/* Workaround for language variants */

// Set user-defined "lang" attributes for the document element (from zh)
var htmlE=document.documentElement;
if (wgUserLanguage == "kk"){ variant = "kk"; }
if (wgUserLanguage == "kk-kz"){ variant = "kk-Cyrl"; }
if (wgUserLanguage == "kk-tr"){ variant = "kk-Latn"; }
if (wgUserLanguage == "kk-cn"){ variant = "kk-Arab"; htmlE.setAttribute("dir","rtl"); }
htmlE.setAttribute("lang",variant);
htmlE.setAttribute("xml:lang",variant);

// Switch language variants of messages (from zh)
function wgULS(kz,tr,cn){
        //
        kk=kz||tr||cn;
        kz=kz;
        tr=tr;
        cn=cn;
        switch(wgUserLanguage){
                case "kk": return kk;
                case "kk-kz": return kz;
                case "kk-tr": return tr;
                case "kk-cn": return cn;
                default: return "";
        }
}

// workaround for RTL ([[bugzilla:6756]])  and for [[bugzilla:02020]] & [[bugzilla:04295]]
if (wgUserLanguage == "kk-cn") 
{
  document.direction="rtl";
  document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/common/common_rtl.css">\');
  document.write(\'<style type="text/css">html {direction:rtl;} body {direction:rtl; unicode-bidi:embed; lang:kk-Arab; font-family:"Arial Unicode MS",Arial,Tahoma; font-size: 75%; letter-spacing: 0.001em;} html > body div#content ol {clear: left;} ol {margin-left:2.4em; margin-right:2.4em;} ul {margin-left:1.5em; margin-right:1.5em;} h1.firstHeading {background-position: bottom right; background-repeat: no-repeat;} h3 {font-size:110%;} h4 {font-size:100%;} h5 {font-size:90%;} #catlinks {width:100%;} #userloginForm {float: right !important;}</style>\');

  if (skin == "monobook"){
     document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/monobook/rtl.css">\');
}',
'monobook.js' => '/* Бостекі болды; орнына мынаны [[MediaWiki:common.js]] пайдалаңыз */',

# Metadata
'nodublincore'      => 'وسى سەرۆەرگە «Dublin Core RDF» مەتا-دەرەكتەرٸ ٶشٸرٸلگەن.',
'nocreativecommons' => 'وسى سەرۆەرگە «Creative Commons RDF» مەتا-دەرەكتەرٸ ٶشٸرٸلگەن.',
'notacceptable'     => 'وسى ۋيكي سەرۆەرٸ سٸزدٸڭ «پايدالانۋشى ٵرەكەتكٸشٸ» وقي الاتىن پٸشٸمٸ بار دەرەكتەردٸ جٸبەرە المايدى.',

# Attribution
'anonymous'        => '{{SITENAME}} تٸركەلگٸسٸز پايدالانۋشى(لار)ى',
'siteuser'         => '{{SITENAME}} قاتىسۋشى $1',
'lastmodifiedatby' => 'بۇل بەتتٸ $3 قاتىسۋشى سوڭعى ٶزگەرتكەن كەزٸ: $2, $1.', # $1 date, $2 time, $3 user
'and'              => 'جٵنە',
'othercontribs'    => 'شىعارما نەگٸزٸن $1 جازعان.',
'others'           => 'باسقالار',
'siteusers'        => '{{SITENAME}} قاتىسۋشى(لار) $1',
'creditspage'      => 'بەتتٸ جازعاندار',
'nocredits'        => 'بۇل بەتتٸ جازعاندار تۋرالى اقپارات جوق.',

# Spam protection
'spamprotectiontitle'    => '«سپام»-نان قورعايتىن سٷزگٸ',
'spamprotectiontext'     => 'بۇل بەتتٸڭ ساقتاۋىن «سپام» سٷزگٸسٸ بۇعاتتادى. بۇنىڭ سەبەبٸ سىرتقى توراپ سٸلتەمەسٸنەن بولۋى مٷمكٸن.',
'spamprotectionmatch'    => 'كەلەسٸ «سپام» مٵتٸنٸ سٷزگٸلەنگەن: $1',
'subcategorycount'       => 'بۇل ساناتتا {{PLURAL:$1|بٸر|$1}} تٶمەنگٸ سانات بار.',
'categoryarticlecount'   => 'بۇل ساناتتا {{PLURAL:$1|بٸر|$1}} بەت بار.',
'category-media-count'   => 'بۇل ساناتتا {{PLURAL:$1|بٸر|$1}} فايل بار.',
'listingcontinuesabbrev' => ' (جالع.)',
'spambot_username'       => 'MediaWiki spam cleanup',
'spam_reverting'         => '$1 دەگەنگە سٸلتەمەسٸ جوق سوڭعى نۇسقاسىنا قايتارىلدى',
'spam_blanking'          => '$1 دەگەنگە سٸلتەمەسٸ بار بارلىق نۇسقالار تازارتىلدى',

# Info page
'infosubtitle'   => 'بەت تۋرالى اقپارات',
'numedits'       => 'تٷزەتۋ سانى (نەگٸزگٸ بەت): $1',
'numtalkedits'   => 'تٷزەتۋ سانى (تالقىلاۋ بەتٸ): $1',
'numwatchers'    => 'باقىلاۋشى سانى: $1',
'numauthors'     => 'ٵرتٷرلٸ اۋتورلار سانى (نەگٸزگٸ بەتٸ): $1',
'numtalkauthors' => 'ٵرتٷرلٸ اۋتور سانى (تالقىلاۋ بەتٸ): $1',

# Math options
'mw_math_png'    => 'ٵرقاشان PNG تٷرٸمەن كٶرسەت',
'mw_math_simple' => 'كٵدٸمگٸ بولسا HTML پٸشٸمٸمەن, باسقاشا PNG تٷرٸمەن',
'mw_math_html'   => 'ىقتيمال بولسا HTML پٸشٸمٸمەن, باسقاشا PNG تٷرٸمەن',
'mw_math_source' => 'TeX پٸشٸمٸندە قالدىرۋ (مٵتٸندٸك شولعىشتارىنا)',
'mw_math_modern' => 'وسى زاماننىڭ شولعىشتارىنا ۇسىنىلعان',
'mw_math_mathml' => 'ىقتيمال بولسا MathML پشٸمٸمەن (سىناق تٷرٸندە)',

# Patrolling
'markaspatrolleddiff'                 => 'كٷزەتتە دەپ بەلگٸلەۋ',
'markaspatrolledtext'                 => 'وسى بەتتٸ كٷزەتۋدە دەپ بەلگٸلەۋ',
'markedaspatrolled'                   => 'كٷزەتتە دەپ بەلگٸلەندٸ',
'markedaspatrolledtext'               => 'تالعانعان نۇسقا كٷزەتتە دەپ بەلگٸلەندٸ.',
'rcpatroldisabled'                    => 'جۋىقتاعى ٶزگەرٸستەر كٷزەتٸ ٶشٸرٸلگەن',
'rcpatroldisabledtext'                => 'جۋىقتاعى ٶزگەرٸستەر كٷزەتٸ قاسيەتٸ اعىمدا ٶشٸرٸلگەن.',
'markedaspatrollederror'              => 'كٷزەتتە دەپ بەلگٸلەنبەيدٸ',
'markedaspatrollederrortext'          => 'كٷزەتتە دەپ بەلگٸلەۋ ٷشٸن نۇسقاسىن ەنگٸزٸڭٸز.',
'markedaspatrollederror-noautopatrol' => 'ٶزٸڭٸزدٸڭ ٶزگەرٸستەرٸڭٸزدٸ كٷزەتكە قويا المايسىز.',

# Patrol log
'patrol-log-page' => 'كٷزەت جۋرنالى',
'patrol-log-line' => '$2 كەزٸندە $1 دەگەندٸ كٷزەتتە دەپ بەلگٸلەدٸ $3',
'patrol-log-auto' => '(ٶزدٸك)',
'patrol-log-diff' => 'r$1',

# Image deletion
'deletedrevision' => 'مىنا ەسكٸ نۇسقاسىن جويدى: $1.',

# Browsing diffs
'previousdiff' => '← الدىڭعىمەن ايىرماسى',
'nextdiff'     => 'كەلەسٸمەن ايىرماسى →',

# Media information
'mediawarning'          => "'''نازار سالىڭىز''': بۇل فايل تٷرٸندە قاسكٷنەمدٸ ٵمٸردٸڭ بار بولۋى ىقتيمال; فايلدى جەگٸپ جٷيەڭٸزگە زييان كەلتٸرۋٸڭٸز مٷمكٸن.<hr />",
'imagemaxsize'          => 'سۋرەت تٷيٸندەمە بەتٸندەگٸ سۋرەتتٸڭ مٶلشەرٸن شەكتەۋٸ:',
'thumbsize'             => 'نوباي مٶلشەرٸ:',
'widthheight'           => '$1 × $2',
'file-info'             => 'فايل مٶلشەرٸ: $1, MIME تٷرٸ: $2',
'file-info-size'        => '($1 × $2 پيكسەل, فايل مٶلشەرٸ: $3, MIME تٷرٸ: $4)',
'file-nohires'          => '<small>جوعارى اجىراتىلىمدىعى جەتٸمسٸز.</small>',
'file-svg'              => '<small>بۇل شىعىنسىز سوزىلعىش ۆەكتورلىق سۋرەتٸ. نەگٸزگٸ مٶلشەرٸ: $1 × $2 پيكسەل.</small>',
'show-big-image'        => 'جوعارى اجىراتىلىمدى',
'show-big-image-thumb'  => '<small>قاراپ شىعۋ مٶلشەرٸ: $1 × $2 پيكسەل</small>',

'newimages'         => 'ەڭ جاڭا فايلدار قويماسى',
'showhidebots'      => '(بوتتاردى $1)',
'noimages'          => 'كٶرەتٸن ەشتەڭە جوق.',

# Variants for Kazakh language
'variantname-kk-tr' => 'Latın',
'variantname-kk-kz' => 'Кирил',
'variantname-kk-cn' => 'توتە',
'variantname-kk'    => 'disable',

'passwordtooshort' => 'قۇپييا سٶزٸڭٸز جارامسىز نە تىم قىسقا. ەڭ كەمٸندە $1 ٵرٸپ جٵنە قاتىسۋشى اتىڭىزدان باسقا بولۋى قاجەت.',

# Metadata
'metadata'          => 'مەتا-دەرەكتەرٸ',
'metadata-help'     => 'وسى فايلدا قوسىمشا اقپارات بار. بٵلكٸم, وسى اقپارات فايلدى جاساپ شىعارۋ, نە ساندىلاۋ ٷشٸن پايدالانعان ساندىق كامەرا, نە مٵتٸنالعىردان الىنعان. ەگەر وسى فايل نەگٸزگٸ كٷيٸنەن ٶزگەرتٸلگەن بولسا, كەيبٸر ەجەلەلەرٸ ٶزگەرتٸلگەن فوتوسۋرەتكە لايىق بولماس.',
'metadata-expand'   => 'ەگجەي-تەگجەيٸن كٶرسەت',
'metadata-collapse' => 'ەگجەي-تەگجەيٸن جاسىر',
'metadata-fields'   => 'وسى حاباردا تٸزٸمدەلگەن EXIF مەتا-دەرەك اۋماقتارى,
سۋرەت بەتٸ كٶرسەتۋ كەزٸندە مەتا-دەرەك كەستە جاسىرىلىعاندا كٸرٸستٸرلەدٸ.
باسقاسى ٵدەپكٸدەن جاسىرىلادى.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'ەنٸ',
'exif-imagelength'                 => 'بيٸكتٸگٸ',
'exif-bitspersample'               => 'قۇراش سايىن بيت سانى',
'exif-compression'                 => 'قىسىم سۇلباسى',
'exif-photometricinterpretation'   => 'پيكسەل قيىسۋى',
'exif-orientation'                 => 'مەگزەۋٸ',
'exif-samplesperpixel'             => 'قۇراش سانى',
'exif-planarconfiguration'         => 'دەرەك رەتتەۋٸ',
'exif-ycbcrsubsampling'            => 'Y قۇراشىنىڭ C قۇراشىنا جارناقتاۋى',
'exif-ycbcrpositioning'            => 'Y قۇراشى جٵنە C قۇراشى مەكەندەۋٸ',
'exif-xresolution'                 => 'دەرەلەي اجىراتىلىمدىعى',
'exif-yresolution'                 => 'تٸرەلەي اجىراتىلىمدىعى',
'exif-resolutionunit'              => 'X جٵنە Y اجىراتىلىمدىقتارىعىنىڭ ٶلشەمٸ',
'exif-stripoffsets'                => 'سۋرەت دەرەرەكتەرٸنٸڭ جايعاسۋى',
'exif-rowsperstrip'                => 'بەلدٸك سايىن جول سانى',
'exif-stripbytecounts'             => 'قىسىمدالعان بەلدٸك سايىن بايت سانى',
'exif-jpeginterchangeformat'       => 'JPEG SOI دەگەننەن ىعىسۋى',
'exif-jpeginterchangeformatlength' => 'JPEG دەرەكتەرٸنٸڭ بايت سانى',
'exif-transferfunction'            => 'تاسىمالداۋ فۋنكتسيياسى',
'exif-whitepoint'                  => 'اق نٷكتە تٷستٸلٸگٸ',
'exif-primarychromaticities'       => 'العى شەپتەگٸ تٷستٸلٸكتەرٸ',
'exif-ycbcrcoefficients'           => 'تٷس اياسىن تاسىمالداۋ ماتريتسالىق ەسەلٸكتەرٸ',
'exif-referenceblackwhite'         => 'قارا جٵنە اق انىقتاۋىش قوس كولەمدەرٸ',
'exif-datetime'                    => 'فايلدىڭ ٶزگەرتٸلگەن كٷن-ايى',
'exif-imagedescription'            => 'سۋرەت اتاۋى',
'exif-make'                        => 'كامەرا ٶندٸرۋشٸسٸ',
'exif-model'                       => 'كامەرا ٷلگٸسٸ',
'exif-software'                    => 'قولدانىلعان باعدارلاما',
'exif-artist'                      => 'جىعارماشىسى',
'exif-copyright'                   => 'جىعارماشىلىق قۇقىقتار يەسٸ',
'exif-exifversion'                 => 'Exif نۇسقاسى',
'exif-flashpixversion'             => 'سٷيەمدەلٸنگەن Flashpix نۇسقاسى',
'exif-colorspace'                  => 'تٷس اياسى',
'exif-componentsconfiguration'     => 'ٵرقايسى قۇراش مٵنٸ',
'exif-compressedbitsperpixel'      => 'سۋرەت قىسىمداۋ تٵرتٸبٸ',
'exif-pixelydimension'             => 'سۋرەتتٸڭ جارامدى ەنٸ',
'exif-pixelxdimension'             => 'سۋرەتتٸڭ جارامدى بيٸكتٸگٸ',
'exif-makernote'                   => 'ٶندٸرۋشٸ ەسكەرتپەلەرٸ',
'exif-usercomment'                 => 'پايدالانۋشى مٵندەمەلەرٸ',
'exif-relatedsoundfile'            => 'قاتىستى دىبىس فايلى',
'exif-datetimeoriginal'            => 'جاسالعان كەزٸ',
'exif-datetimedigitized'           => 'ساندىقتاۋ كەزٸ',
'exif-subsectime'                  => 'جاسالعان كەزٸنٸڭ سەكۋند بٶلشەكتەرٸ',
'exif-subsectimeoriginal'          => 'تٷپنۇسقا كەزٸنٸڭ سەكۋند بٶلشەكتەرٸ',
'exif-subsectimedigitized'         => 'ساندىقتاۋ كەزٸنٸڭ سەكۋند بٶلشەكتەرٸ',
'exif-exposuretime'                => 'ۇستالىم ۋاقىتى',
'exif-exposuretime-format'         => '$1 س ($2)',
'exif-fnumber'                     => 'ساڭىلاۋ مٶلشەرٸ',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'ۇستالىم باعدارلاماسى',
'exif-spectralsensitivity'         => 'سپەكتر بويىنشا سەزگٸشتٸگٸ',
'exif-isospeedratings'             => 'ISO جىلدامدىق جارناقتاۋى (جارىق سەزگٸشتٸگٸ)',
'exif-oecf'                        => 'وپتوەلەكتروندى تٷرلەتۋ ىقپالى',
'exif-shutterspeedvalue'           => 'جاپقىش جىلدامدىلىعى',
'exif-aperturevalue'               => 'ساڭىلاۋلىق',
'exif-brightnessvalue'             => 'اشىقتىق',
'exif-exposurebiasvalue'           => 'ۇستالىم ٶتەمٸ',
'exif-maxaperturevalue'            => 'بارىنشا ساڭىلاۋ اشۋى',
'exif-subjectdistance'             => 'نىسانا قاشىقتىعى',
'exif-meteringmode'                => 'ٶلشەۋ تٵرتٸبٸ',
'exif-lightsource'                 => 'جارىق كٶزٸ',
'exif-flash'                       => 'جارقىلداعىش',
'exif-focallength'                 => 'شوعىرلاۋ الشاقتىعى',
'exif-focallength-format'          => '$1 mm',
'exif-subjectarea'                 => 'نىسانا اۋقىمى',
'exif-flashenergy'                 => 'جارقىلداعىش قارقىنى',
'exif-spatialfrequencyresponse'    => 'كەڭٸستٸك-جيٸلٸك ٵسەرشٸلٸگٸ',
'exif-focalplanexresolution'       => 'ح بويىنشا شوعىرلاۋ جايپاقتىقتىڭ اجىراتىلىمدىعى',
'exif-focalplaneyresolution'       => 'Y بويىنشا شوعىرلاۋ جايپاقتىقتىڭ اجىراتىلىمدىعى',
'exif-focalplaneresolutionunit'    => 'شوعىرلاۋ جايپاقتىقتىڭ اجىراتىلىمدىق ٶلشەمٸ',
'exif-subjectlocation'             => 'نىسانا مەكەندەۋٸ',
'exif-exposureindex'               => 'ۇستالىم ايقىنداۋى',
'exif-sensingmethod'               => 'سەنسوردٸڭ ٶلشەۋ ٵدٸسٸ',
'exif-filesource'                  => 'فايل قاينارى',
'exif-scenetype'                   => 'ساحنا تٷرٸ',
'exif-cfapattern'                  => 'CFA سٷزگٸ كەيٸپٸ',
'exif-customrendered'              => 'قوسىمشا سۋرەت ٶڭدەتۋٸ',
'exif-exposuremode'                => 'ۇستالىم تٵرتٸبٸ',
'exif-whitebalance'                => 'اق تٷسٸنٸڭ تەندەستٸگٸ',
'exif-digitalzoomratio'            => 'ساندىق اۋقىمداۋ جارناقتاۋى',
'exif-focallengthin35mmfilm'       => '35 mm تاسپاسىنىڭ شوعىرلاۋ الشاقتىعى',
'exif-scenecapturetype'            => 'تٷسٸرگەن ساحنا تٷرٸ',
'exif-gaincontrol'                 => 'ساحنانى مەڭگەرۋ',
'exif-contrast'                    => 'قاراما-قارسىلىق',
'exif-saturation'                  => 'قانىقتىق',
'exif-sharpness'                   => 'ايقىندىق',
'exif-devicesettingdescription'    => 'جابدىق باپتاۋ سيپاتتارى',
'exif-subjectdistancerange'        => 'ساحنا قاشىقتىعىنىڭ كٶلەمٸ',
'exif-imageuniqueid'               => 'سۋرەتتٸڭ بٸرەگەي نٶمٸرٸ (ID)',
'exif-gpsversionid'                => 'GPS بەلگٸشەسٸنٸڭ نۇسقاسى',
'exif-gpslatituderef'              => 'سولتٷستٸك نەمەسە وڭتٷستٸك بويلىعى',
'exif-gpslatitude'                 => 'بويلىعى',
'exif-gpslongituderef'             => 'شىعىس نەمەسە باتىس ەندٸگٸ',
'exif-gpslongitude'                => 'ەندٸگٸ',
'exif-gpsaltituderef'              => 'بيٸكتٸك كٶرسەتۋٸ',
'exif-gpsaltitude'                 => 'بيٸكتٸك',
'exif-gpstimestamp'                => 'GPS ۋاقىتى (اتوم ساعاتى)',
'exif-gpssatellites'               => 'ٶلشەۋگە پيدالانىلعان جەر سەرٸكتەرٸ',
'exif-gpsstatus'                   => 'قابىلداعىش كٷيٸ',
'exif-gpsmeasuremode'              => 'ٶلشەۋ تٵرتٸبٸ',
'exif-gpsdop'                      => 'ٶلشەۋ دٵلدٸگٸ',
'exif-gpsspeedref'                 => 'جىلدامدىلىق ٶلشەمٸ',
'exif-gpsspeed'                    => 'GPS قابىلداعىشتىڭ جىلدامدىلىعى',
'exif-gpstrackref'                 => 'قوزعالىس باعىتىن كٶرسەتۋٸ',
'exif-gpstrack'                    => 'قوزعالىس باعىتى',
'exif-gpsimgdirectionref'          => 'سۋرەت باعىتىن كٶرسەتۋٸ',
'exif-gpsimgdirection'             => 'سۋرەت باعىتى',
'exif-gpsmapdatum'                 => 'پايدالانىلعان گەودەزييالىق تٷسٸرمە دەرەكتەرٸ',
'exif-gpsdestlatituderef'          => 'نىسانا بويلىعىن كٶرسەتۋٸ',
'exif-gpsdestlatitude'             => 'نىسانا بويلىعى',
'exif-gpsdestlongituderef'         => 'نىسانا ەندٸگٸن كٶرسەتۋٸ',
'exif-gpsdestlongitude'            => 'نىسانا ەندٸگٸ',
'exif-gpsdestbearingref'           => 'نىسانا ازيمۋتىن كٶرسەتۋٸ',
'exif-gpsdestbearing'              => 'نىسانا ازيمۋتى',
'exif-gpsdestdistanceref'          => 'نىسانا قاشىقتىعىن كٶرسەتۋٸ',
'exif-gpsdestdistance'             => 'نىسانا قاشىقتىعى',
'exif-gpsprocessingmethod'         => 'GPS ٶڭدەتۋ ٵدٸسٸنٸڭ اتاۋى',
'exif-gpsareainformation'          => 'GPS اۋماعىنىڭ اتاۋى',
'exif-gpsdatestamp'                => 'GPS كٷن-ايى',
'exif-gpsdifferential'             => 'GPS سارالانعان تٷزەتۋ',

# EXIF attributes
'exif-compression-1' => 'ۇلعايتىلعان',
'exif-compression-6' => 'JPEG',

'exif-unknowndate' => 'بەلگٸسٸز كٷن-ايى',
'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',


'exif-orientation-1' => 'قالىپتى', # 0th row: top; 0th column: left
'exif-orientation-2' => 'دەرەلەي شاعىلىسقان', # 0th row: top; 0th column: right
'exif-orientation-3' => '180° بۇرىشقا اينالعان', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'تٸرەلەي شاعىلىسقان', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'ساعات تٸلشەسٸنە قارسى 90° بۇرىشقا اينالعان جٵنە تٸرەلەي شاعىلىسقان', # 0th row: left; 0th column: top
'exif-orientation-6' => 'ساعات تٸلشە بويىنشا 90° بۇرىشقا اينالعان', # 0th row: right; 0th column: top
'exif-orientation-7' => 'ساعات تٸلشە بويىنشا 90° بۇرىشقا اينالعان جٵنە تٸرەلەي شاعىلىسقان', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'ساعات تٸلشەسٸنە قارسى 90° بۇرىشقا اينالعان', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'تالپاق پٸشٸم',
'exif-planarconfiguration-2' => 'تايپاق پٸشٸم',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'بار بولمادى',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'انىقتالماعان',
'exif-exposureprogram-1' => 'قولمەن',
'exif-exposureprogram-2' => 'باعدارلامالى ٵدٸس (قالىپتى)',
'exif-exposureprogram-3' => 'ساڭىلاۋ باسىڭقىلىعى',
'exif-exposureprogram-4' => 'ىسىرما باسىڭقىلىعى',
'exif-exposureprogram-5' => 'ٶنەر باعدارلاماسى (انىقتىق تەرەندٸگٸنە ساناسقان)',
'exif-exposureprogram-6' => 'قيمىل باعدارلاماسى (جاپقىش شاپشاندىلىعىنا ساناسقان)',
'exif-exposureprogram-7' => 'تٸرەلەي ٵدٸسٸ (ارتى شوعىرلاۋسىز تاياۋ تٷسٸرمەلەر)',
'exif-exposureprogram-8' => 'دەرەلەي ٵدٸسٸ (ارتى شوعىرلانعان دەرەلەي تٷسٸرمەلەر)',

'exif-subjectdistance-value' => '$1 m',

'exif-meteringmode-0'   => 'بەلگٸسٸز',
'exif-meteringmode-1'   => 'بٸركەلكٸ',
'exif-meteringmode-2'   => 'بۇلدىر داق',
'exif-meteringmode-3'   => 'بٸرداقتى',
'exif-meteringmode-4'   => 'كٶپداقتى',
'exif-meteringmode-5'   => 'ٶرنەكتٸ',
'exif-meteringmode-6'   => 'جىرتىندى',
'exif-meteringmode-255' => 'باسقا',

'exif-lightsource-0'   => 'بەلگٸسٸز',
'exif-lightsource-1'   => 'كٷن جارىعى',
'exif-lightsource-2'   => 'كٷنجارىقتى شام',
'exif-lightsource-3'   => 'قىزدىرعىشتى شام',
'exif-lightsource-4'   => 'جارقىلداعىش',
'exif-lightsource-9'   => 'اشىق كٷن',
'exif-lightsource-10'  => 'بۇلىنعىر كٷن',
'exif-lightsource-11'  => 'كٶلەنكەلٸ',
'exif-lightsource-12'  => 'كٷنجارىقتى شام (D 5700–7100 K)',
'exif-lightsource-13'  => 'كٷنجارىقتى شام (N 4600–5400 K)',
'exif-lightsource-14'  => 'كٷنجارىقتى شام (W 3900–4500 K)',
'exif-lightsource-15'  => 'كٷنجارىقتى شام (WW 3200–3700 K)',
'exif-lightsource-17'  => 'قالىپتى جارىق قاينارى A',
'exif-lightsource-18'  => 'قالىپتى جارىق قاينارى B',
'exif-lightsource-19'  => 'قالىپتى جارىق قاينارى C',
'exif-lightsource-20'  => 'D55',
'exif-lightsource-21'  => 'D65',
'exif-lightsource-22'  => 'D75',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'ستۋدييالىق ISO كٷنجارىقتى شام',
'exif-lightsource-255' => 'باسقا جارىق قاينارى',

'exif-focalplaneresolutionunit-2' => 'ديۋيم',

'exif-sensingmethod-1' => 'انىقتالماعان',
'exif-sensingmethod-2' => '1-چيپتٸ اۋماقتى تٷسسەزگٸش',
'exif-sensingmethod-3' => '2-چيپتٸ اۋماقتى تٷسسەزگٸش',
'exif-sensingmethod-4' => '3-چيپتٸ اۋماقتى تٷسسەزگٸش',
'exif-sensingmethod-5' => 'كەزەكتٸ اۋماقتى تٷسسەزگٸش',
'exif-sensingmethod-7' => '3-سىزىقتى تٷسسەزگٸش',
'exif-sensingmethod-8' => 'كەزەكتٸ سىزىقتى تٷسسەزگٸش',

'exif-filesource-3' => 'DSC',

'exif-scenetype-1' => 'تٸكەلەي تٷسٸرٸلگەن فوتوسۋرەت',

'exif-customrendered-0' => 'قالىپتى ٶڭدەتۋ',
'exif-customrendered-1' => 'قوسىمشا ٶڭدەتۋ',

'exif-exposuremode-0' => 'ٶزدٸك ۇستالىمداۋ',
'exif-exposuremode-1' => 'قولمەن ۇستالىمداۋ',
'exif-exposuremode-2' => 'ٶزدٸك جارقىلداۋ',

'exif-whitebalance-0' => 'اق تٷسٸنٸڭ ٶزدٸك تەندەستٸرۋ',
'exif-whitebalance-1' => 'اق تٷسٸنٸڭ قولمەن تەندەستٸرۋ',

'exif-scenecapturetype-0' => 'قالىپتى',
'exif-scenecapturetype-1' => 'دەرەلەي',
'exif-scenecapturetype-2' => 'تٸرەلەي',
'exif-scenecapturetype-3' => 'تٷنگٸ ساحنا',

'exif-gaincontrol-0' => 'جوق',
'exif-gaincontrol-1' => 'تٶمەن زورايۋ',
'exif-gaincontrol-2' => 'جوعارى زورايۋ',
'exif-gaincontrol-3' => 'تٶمەن باياۋلاۋ',
'exif-gaincontrol-4' => 'جوعارى باياۋلاۋ',

'exif-contrast-0' => 'قالىپتى',
'exif-contrast-1' => 'ۇيان',
'exif-contrast-2' => 'تۇرپايى',

'exif-saturation-0' => 'قالىپتى',
'exif-saturation-1' => 'تٶمەن قانىقتى',
'exif-saturation-2' => 'جوعارى قانىقتى',

'exif-sharpness-0' => 'قالىپتى',
'exif-sharpness-1' => 'ۇيان',
'exif-sharpness-2' => 'تۇرپايى',

'exif-subjectdistancerange-0' => 'بەلگٸسٸز',
'exif-subjectdistancerange-1' => 'تاياۋ تٷسٸرٸلگەن',
'exif-subjectdistancerange-2' => 'جاقىن تٷسٸرٸلگەن',
'exif-subjectdistancerange-3' => 'الىس تٷسٸرٸلگەن',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'سولتٷستٸك بويلىعى',
'exif-gpslatitude-s' => 'وڭتٷستٸك بويلىعى',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'شىعىس ەندٸگٸ',
'exif-gpslongitude-w' => 'باتىس ەندٸگٸ',

'exif-gpsstatus-a' => 'ٶلشەۋ ۇلاسۋدا',
'exif-gpsstatus-v' => 'ٶلشەۋ ٶزارا ٵرەكەتتە',

'exif-gpsmeasuremode-2' => '2-باعىتتىق ٶلشەم',
'exif-gpsmeasuremode-3' => '3-باعىتتىق ٶلشەم',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mil/h',
'exif-gpsspeed-n' => 'ج. تٷيٸن',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'شىن باعىت',
'exif-gpsdirection-m' => 'ماگنيتتى باعىت',

# External editor support
'edit-externally'      => 'بۇل فايلدى سىرتقى قۇرال/باعدارلاما ارقىلى ٶڭدەۋ',
'edit-externally-help' => 'كٶبٸرەك اقپارات ٷشٸن [http://meta.wikimedia.org/wiki/Help:External_editors ورناتۋ نۇسقاۋلارىن] قاراڭىز.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'بارلىعىن',
'imagelistall'     => 'بارلىعى',
'watchlistall1'    => 'بارلىعى',
'watchlistall2'    => 'بارلىق',
'namespacesall'    => 'بارلىعى',

# E-mail address confirmation
'confirmemail'            => 'ە-پوشتا جايىن كۋٵلاندىرۋ',
'confirmemail_noemail'    => '[[{{ns:special}}:Preferences|قاتىسۋشى باپتاۋىڭىزدا]] جارامدى ە-پوشتا جايىن ەنگٸزبەپسٸز.',
'confirmemail_text'       => 'بۇل ۋيكيدە ە-پوشتا قاسيەتتەرٸن پايدالانۋدىڭ الدىنان ە-پوشتا جايىڭىزدى
كۋٵلاندىرۋ قاجەت. ٶزٸڭٸزدٸڭ جايىڭىزعا كۋٵلاندىرۋ حاتىن جٸبەرۋ ٷشٸن تٶمەندەگٸ تٷيمەنٸ نۇقىڭىز.
حاتتىڭ ٸشٸندە ارنايى كودى بار سٸلتەمە كٸرٸستٸرلەدٸ;	ە-پوشتا جايىڭىزدىڭ جارامدىعىن كۋٵلاندىرۋ ٷشٸن
سٸلتەمەنٸ شولعىشتىڭ مەكەن-جاي جولاعىنا ەنگٸزٸپ اشىڭىز.',
'confirmemail_pending'    => '<div class="error">
راستاۋ بەلگٸلەمەڭٸز حاتپەن جٸبەرٸلٸپتٸ تٷگە; ەگەر تٸركەلگٸڭٸزدٸ 
جۋىقتا ٸستەسەڭٸز, جاڭا بەلگٸلە سۇرانىسىن جٸبەرۋ الدىنان 
حات كەلۋٸن بٸرشاما مينٶت كٷتە تۇرىڭىز.
</div>',
'confirmemail_send'       => 'كۋٵلاندىرۋ كودىن جٸبەرۋ',
'confirmemail_sent'       => 'كۋٵلاندىرۋ حاتى جٸبەرٸلدٸ.',
'confirmemail_oncreate'   => 'راستاۋ بەلگٸلەمەسٸ ە-پوشتا ادرەسٸڭٸزگە جٸبەرٸلدٸ.
بۇل بەلگٸلەمە كٸرۋ ٷدٸرٸسٸنە كەرەگٸ جوق, بٸراق ول ە-پوشتا نەگٸزٸندەگٸ
ۋيكي قاسيەتتەردٸ ەندٸرۋ ٷشٸن جەتٸستٸرۋٸڭٸز قاجەت.',
'confirmemail_sendfailed' => 'كۋٵلاندىرۋ حاتى جٸبەرٸلمەدٸ. ەنگٸزٸلگەن جايدى جارامسىز ٵرٸتەرٸنە تەكسەرٸپ شىعىڭىز.

پوشتا جٸبەرگٸشتٸڭ قايتارعانى: $1',
'confirmemail_invalid'    => 'كۋٵلاندىرۋ كودى جارامسىز. كودتىڭ مەرزٸمٸ بٸتكەن شىعار.',
'confirmemail_needlogin'  => 'ە-پوشتا جايىڭىزدى كۋٵلاندىرۋ ٷشٸن $1 قاجەت.',
'confirmemail_success'    => 'ە-پوشتا جايىڭىز كۋٵلاندىرىلدى. ەندٸ ۋيكيگە كٸرٸپ جۇمىسقا كٸرٸسۋگە بولادى',
'confirmemail_loggedin'   => 'ە-پوشتا جايىڭىز كۋٵلاندىرىلدى.',
'confirmemail_error'      => 'كۋٵلاندىرۋىڭىزدى ساقتاعاندا بەلگٸسٸز قاتە بولدى.',
'confirmemail_subject'    => '{{SITENAME}} تورابىنان ە-پوشتا جايىڭىزدى كۋٵلاندىرۋ حاتى',
'confirmemail_body'       => "كەيبٸرەۋ, مىنا $1 IP جايىنان, ٶزٸڭٸز بولۋى مٷمكٸن,
{{SITENAME}} جوباسىنداعى ە-پوشتا جايىن قولدانىپ «$2» تٸركەلگٸ جاساپتى.

وسى تٸركەلگٸ راستان سٸزدٸكٸ ەكەنٸن كۋٵلاندىرۋ ٷشٸن, جٵنە {{SITENAME}} جوباسىنىڭ
ە-پوشتا قاسيەتتەرٸن بەلسەندٸرۋ ٷشٸن, مىنا سٸلتەمەنٸ شولعىشپەن اشىڭىز:

$3

بۇل سٸزدٸكٸ '''ەمەس''' بولسا, سٸلتەمەگە ەرمەڭٸز. كۋٵلاندىرۋ كودىنىڭ
مەرزٸمٸ $4 كەزٸندە بٸتەدٸ.",

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'دٵل سٵيكەسٸن سىناپ كٶرٸڭٸز',
'searchfulltext' => 'تولىق مٵتٸنٸمەن ٸزدەۋ',
'createarticle'  => 'بەتتٸ باستاۋ',

# Scary transclusion
'scarytranscludedisabled' => '[ۋيكي-ارا كٸرەگۋ ٶشٸرٸلگەن]',
'scarytranscludefailed'   => '[$1 بەتٸنە ٷلگٸ ٶڭدەتۋ سٵتسٸز بٸتتٸ; كەشٸرٸڭٸز]',
'scarytranscludetoolong'  => '[URL جايى تىم ۇزىن; كەشٸرٸڭٸز]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
بۇل بەتتٸڭ اڭىستاۋلارى:<br />
$1
</div>',
'trackbackremove'   => '([$1 جويىلدى])',
'trackbacklink'     => 'اڭىستاۋ',
'trackbackdeleteok' => 'اڭىستاۋ جويۋى سٵتتٸ ٶتتٸ.',

# Delete conflict
'deletedwhileediting' => 'نازار سالىڭىز:سٸز بۇل بەتتٸڭ ٶڭدەۋٸن باستاعاندا, وسى بەت جويىلدى!',
'confirmrecreate'     => "سٸز بۇل بەتتٸڭ ٶندەۋٸن باستاعاندا [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|تالقىلاۋى]]) وسى بەتتٸ جويدى, كٶرسەتكەن سەبەبٸ:
: ''$2''
وسى بەتتٸ شىنىنان قايتا جاساۋىن راستاڭىز.",
'recreate'            => 'قايتا جاساۋ',

'unit-pixel' => ' px',

# HTML dump
'redirectingto' => '[[$1]] بەتٸنە ايداتۋدا…',

# action=purge
'confirm_purge'        => 'قوسالقى قالتاداعى وسى بەتٸن تازالايمىز با؟<br /><br />$1',
'confirm_purge_button' => 'جارايدى',

'youhavenewmessagesmulti' => '$1 دەگەنگە جاڭا حابارلار تٷستٸ',

'searchcontaining' => "مىنا سٶزٸ بار بەت اراسىنان ٸزدەۋ: ''$1''.",
'searchnamed'      => "مىنا اتاۋلى بەت اراسىنان ٸزدەۋ: ''$1''.",
'articletitles'    => "اتاۋلارى مىنادان باستالعان بەتتەر: ''$1''",
'hideresults'      => 'نٵتيجەلەردٸ جاسىر',

# DISPLAYTITLE
'displaytitle' => '(بۇل بەتتٸڭ سٸلتەمەسٸ: [[$1]])',

'loginlanguagelabel' => 'تٸل: $1',

# Multipage image navigation
'imgmultipageprev'   => '← الدىڭعى بەتكە',
'imgmultipagenext'   => 'كەلەسٸ بەتكە →',
'imgmultigo'         => 'ٶتۋ!',
'imgmultigotopre'    => 'مىنا بەتكە ٶتۋ',
'imgmultiparseerror' => 'سۋرەت فايلى قيراعان نەمەسە دۇرىس ەمەس, سوندىقتان {{SITENAME}} بەت تٸزٸمٸن كٶرسەتە المايدى.',

# Table pager
'ascending_abbrev'         => 'ٶسۋ',
'descending_abbrev'        => 'كەمۋ',
'table_pager_next'         => 'كەلەسٸ بەتكە',
'table_pager_prev'         => 'الدىڭعى بەتكە',
'table_pager_first'        => 'العاشقى بەتكە',
'table_pager_last'         => 'سوڭعى بەتكە',
'table_pager_limit'        => 'بەت سايىن $1 دانا كٶرسەت',
'table_pager_limit_submit' => 'ٶتۋ',
'table_pager_empty'        => 'ەش نٵتيجە جوق',

# Auto-summaries
'autosumm-blank'   => 'بەتتٸڭ بارلىق ماعلۇماتىن الاستاتتى',
'autosumm-replace' => "بەتتٸ «$1» دەگەنمەن الماستىردى",
'autoredircomment' => '[[$1]] دەگەنگە ايدادى', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'جاڭا بەتتە: $1',

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'جٷكتەۋدە…',
'livepreview-ready'   => 'جٷكتەۋدە… دايىن!',
'livepreview-failed'  => "تۋرا قاراپ شىعۋ امالى بولمادى!\nكٵدٸمگٸ قاراپ شىعۋ ٵدٸسٸن بايقاپ كٶرٸڭٸز.",
'livepreview-error'   => "مىناعان قوسىلۋ امالى بولمادى: $1 «$2»\nكٵدٸمگٸ قاراپ شىعۋ ٵدٸسٸن بايقاپ كٶرٸڭٸز.",

);

?>
