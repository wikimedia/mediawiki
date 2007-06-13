<?php
/**
 * Kazakh (Қазақша)
 *
 * @addtogroup Language
 *
 */


$separatorTransformTable = array(
	',' => "\xc2\xa0",
	'.' => ',',
);

$extraUserToggles = array(
	'nolangconversion'
);

$fallback8bitEncoding = 'windows-1251';

$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Таспа',
	NS_SPECIAL          => 'Арнайы',
	NS_MAIN	            => '',
	NS_TALK	            => 'Талқылау',
	NS_USER             => 'Қатысушы',
	NS_USER_TALK        => 'Қатысушы_талқылауы',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_талқылауы',
	NS_IMAGE            => 'Сурет',
	NS_IMAGE_TALK       => 'Сурет_талқылауы',
	NS_MEDIAWIKI        => 'МедиаУики',
	NS_MEDIAWIKI_TALK   => 'МедиаУики_талқылауы',
	NS_TEMPLATE         => 'Үлгі',
	NS_TEMPLATE_TALK    => 'Үлгі_талқылауы',
	NS_HELP             => 'Анықтама',
	NS_HELP_TALK        => 'Анықтама_талқылауы',
	NS_CATEGORY         => 'Санат',
	NS_CATEGORY_TALK    => 'Санат_талқылауы'
);

$namespaceAliases = array(
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

	# Aliases to renamed kk-cn namespaces
	'ٴۇلگٴى'              => NS_TEMPLATE,
	'ٴۇلگٴى_تالقىلاۋى'    => NS_TEMPLATE_TALK,

	# Aliases to kk-cn namespaces
	'تاسپا'              => NS_MEDIA,
	'ارنايى'              => NS_SPECIAL,
	'تالقىلاۋ'            => NS_TALK,
	'قاتىسۋشى'          => NS_USER,
	'قاتىسۋشى_تالقىلاۋى'=> NS_USER_TALK,
	'$1_تالقىلاۋى'        => NS_PROJECT_TALK,
	'سۋرەت'              => NS_IMAGE,
	'سۋرەت_تالقىلاۋى'    => NS_IMAGE_TALK,
	'مەدياۋيكي'           => NS_MEDIAWIKI,
	'مەدياۋيكي_تالقىلاۋى' => NS_MEDIAWIKI_TALK,
	'ٷلگٸ'              => NS_TEMPLATE,
	'ٷلگٸ_تالقىلاۋى'    => NS_TEMPLATE_TALK,
	'انىقتاما'            => NS_HELP,
	'انىقتاما_تالقىلاۋى'  => NS_HELP_TALK,
	'سانات'              => NS_CATEGORY,
	'سانات_تالقىلاۋى'    => NS_CATEGORY_TALK,
);

$skinNames = array(
	'standard'    => 'Дағдылы',
	'nostalgia'   => 'Аңсау',
	'cologneblue' => 'Көлн зеңгірлігі',
	'monobook'    => 'Дара кітап',
	'myskin'      => 'Өз мәнерім',
	'chick'       => 'Балапан',
	'simple'      => 'Кәдімгі'
);

$datePreferences = array(
	'default',
	'mdy',
	'dmy',
	'ymd',
	'yyyy-mm-dd',
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
	'mdy date' => 'xg j, Y "ж."',
	'mdy both' => 'H:i, xg j, Y "ж."',

	'dmy time' => 'H:i',
	'dmy date' => 'j F, Y "ж."',
	'dmy both' => 'H:i, j F, Y "ж."',

	'ymd time' => 'H:i',
	'ymd date' => 'Y "ж." xg j',
	'ymd both' => 'H:i, Y "ж." xg j',

	'yyyy-mm-dd time' => 'xnH:xni:xns',
	'yyyy-mm-dd date' => 'xnY-xnm-xnd',
	'yyyy-mm-dd both' => 'xnH:xni:xns, xnY-xnm-xnd',

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
	'redirect'               => array( 0,    '#REDIRECT', '#АЙДАУ' ),
	'notoc'                  => array( 0,    '__МАЗМҰНСЫЗ__', '__МСЫЗ__', '__NOTOC__' ),
	'nogallery'              => array( 0,    '__ҚОЙМАСЫЗ__', '__ҚСЫЗ__', '__NOGALLERY__' ),
	'forcetoc'               => array( 0,    '__МАЗМҰНДАТҚЫЗУ__', '__МҚЫЗУ__', '__FORCETOC__' ),
	'toc'                    => array( 0,    '__МАЗМҰНЫ__', '__МЗМН__', '__TOC__' ),
	'noeditsection'          => array( 0,    '__БӨЛІМӨНДЕТКІЗБЕУ__', '__NOEDITSECTION__' ),
	'start'                  => array( 0,    '__БАСТАУ__', '__START__' ),
	'currentmonth'           => array( 1,    'АҒЫМДАҒЫАЙ', 'CURRENTMONTH' ),
	'currentmonthname'       => array( 1,    'АҒЫМДАҒЫАЙАТАУЫ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'    => array( 1,    'АҒЫМДАҒЫАЙІЛІКАТАУЫ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'     => array( 1,    'АҒЫМДАҒЫАЙЖИЫР', 'АҒЫМДАҒЫАЙҚЫСҚА', 'CURRENTMONTHABBREV' ),
	'currentday'             => array( 1,    'АҒЫМДАҒЫКҮН', 'CURRENTDAY' ),
	'currentday2'            => array( 1,    'АҒЫМДАҒЫКҮН2', 'CURRENTDAY2' ),
	'currentdayname'         => array( 1,    'АҒЫМДАҒЫКҮНАТАУЫ', 'CURRENTDAYNAME' ),
	'currentyear'            => array( 1,    'АҒЫМДАҒЫЖЫЛ', 'CURRENTYEAR' ),
	'currenttime'            => array( 1,    'АҒЫМДАҒЫУАҚЫТ', 'CURRENTTIME' ),
	'currenthour'            => array( 1,    'АҒЫМДАҒЫСАҒАТ', 'CURRENTHOUR' ),
	'localmonth'             => array( 1,    'ЖЕРГІЛІКТІАЙ', 'LOCALMONTH' ),
	'localmonthname'         => array( 1,    'ЖЕРГІЛІКТІАЙАТАУЫ', 'LOCALMONTHNAME' ),
	'localmonthnamegen'      => array( 1,    'ЖЕРГІЛІКТІАЙІЛІКАТАУЫ', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'       => array( 1,    'ЖЕРГІЛІКТІАЙЖИЫР', 'ЖЕРГІЛІКТІАЙҚЫСҚАША', 'ЖЕРГІЛІКТІАЙҚЫСҚА', 'LOCALMONTHABBREV' ),
	'localday'               => array( 1,    'ЖЕРГІЛІКТІКҮН', 'LOCALDAY' ),
	'localday2'              => array( 1,    'ЖЕРГІЛІКТІКҮН2', 'LOCALDAY2'  ),
	'localdayname'           => array( 1,    'ЖЕРГІЛІКТІКҮНАТАУЫ', 'LOCALDAYNAME' ),
	'localyear'              => array( 1,    'ЖЕРГІЛІКТІЖЫЛ', 'LOCALYEAR' ),
	'localtime'              => array( 1,    'ЖЕРГІЛІКТІУАҚЫТ', 'LOCALTIME' ),
	'localhour'              => array( 1,    'ЖЕРГІЛІКТІСАҒАТ', 'LOCALHOUR' ),
	'numberofpages'          => array( 1,    'БЕТСАНЫ', 'NUMBEROFPAGES' ),
	'numberofarticles'       => array( 1,    'МАҚАЛАСАНЫ', 'NUMBEROFARTICLES' ),
	'numberoffiles'          => array( 1,    'ФАЙЛСАНЫ', 'NUMBEROFFILES' ),
	'numberofusers'          => array( 1,    'ҚАТЫСУШЫСАНЫ', 'NUMBEROFUSERS' ),
	'numberofedits'          => array( 1,    'ТҮЗЕТУСАНЫ', 'NUMBEROFEDITS' ),
	'pagename'               => array( 1,    'БЕТАТАУЫ', 'PAGENAME' ),
	'pagenamee'              => array( 1,    'БЕТАТАУЫ2', 'PAGENAMEE' ),
	'namespace'              => array( 1,    'ЕСІМАЯСЫ', 'NAMESPACE' ),
	'namespacee'             => array( 1,    'ЕСІМАЯСЫ2', 'NAMESPACEE' ),
	'talkspace'              => array( 1,    'ТАЛҚЫЛАУАЯСЫ', 'TALKSPACE' ),
	'talkspacee'             => array( 1,    'ТАЛҚЫЛАУАЯСЫ2', 'TALKSPACEE' ),
	'subjectspace'           => array( 1,    'ТАҚЫРЫПБЕТІ', 'МАҚАЛАБЕТІ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'          => array( 1,    'ТАҚЫРЫПБЕТІ2', 'МАҚАЛАБЕТІ2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'           => array( 1,    'ТОЛЫҚБЕТАТАУЫ', 'FULLPAGENAME' ),
	'fullpagenamee'          => array( 1,    'ТОЛЫҚБЕТАТАУЫ2', 'FULLPAGENAMEE' ),
	'subpagename'            => array( 1,    'БЕТШЕАТАУЫ', 'АСТЫҢҒЫБЕТАТАУЫ', 'SUBPAGENAME' ),
	'subpagenamee'           => array( 1,    'БЕТШЕАТАУЫ2', 'АСТЫҢҒЫБЕТАТАУЫ2', 'SUBPAGENAMEE' ),
	'basepagename'           => array( 1,    'НЕГІЗГІБЕТАТАУЫ', 'BASEPAGENAME' ),
	'basepagenamee'          => array( 1,    'НЕГІЗГІБЕТАТАУЫ2', 'BASEPAGENAMEE' ),
	'talkpagename'           => array( 1,    'ТАЛҚЫЛАУБЕТАТАУЫ', 'TALKPAGENAME' ),
	'talkpagenamee'          => array( 1,    'ТАЛҚЫЛАУБЕТАТАУЫ2', 'TALKPAGENAMEE' ),
	'subjectpagename'        => array( 1,    'ТАҚЫРЫПБЕТАТАУЫ', 'МАҚАЛАБЕТАТАУЫ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'       => array( 1,    'ТАҚЫРЫПБЕТАТАУЫ2', 'МАҚАЛАБЕТАТАУЫ2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                    => array( 0,    'ХБР:', 'MSG:' ),
	'subst'                  => array( 0,    'БӘДЕЛ:', 'SUBST:' ),
	'msgnw'                  => array( 0,    'УИКИСІЗХБР:', 'MSGNW:' ),
	'img_thumbnail'          => array( 1,    'нобай', 'thumbnail', 'thumb' ),
	'img_manualthumb'        => array( 1,    'нобай=$1', 'thumbnail=$1', 'thumb=$1'),
	'img_right'              => array( 1,    'оңға', 'оң', 'right' ),
	'img_left'               => array( 1,    'солға', 'сол', 'left' ),
	'img_none'               => array( 1,    'ешқандай', 'жоқ', 'none' ),
	'img_width'              => array( 1,    '$1 px', '$1px' ),
	'img_center'             => array( 1,    'ортаға', 'орта', 'center', 'centre' ),
	'img_framed'             => array( 1,    'сүрмелі', 'framed', 'enframed', 'frame' ),
	'img_frameless'          => array( 1,    'сүрмесіз', 'frameless' ),
	'img_page'               => array( 1,    'бет=$1', 'бет $1', 'page=$1', 'page $1' ),
	'img_upright'            => array( 1,    'тікті', 'тіктік=$1', 'тіктік $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'             => array( 1,    'шекті', 'border' ),
	'img_baseline'           => array( 1,    'негізжол', 'baseline' ),
	'img_sub'                => array( 1,    'астылығы', 'аст', 'sub'),
	'img_super'              => array( 1,    'үстілігі', 'үст', 'sup', 'super', 'sup' ),
	'img_top'                => array( 1,    'үстіне', 'top' ),
	'img_text-top'           => array( 1,    'мәтін-үстінде', 'text-top' ),
	'img_middle'             => array( 1,    'аралығына', 'middle' ),
	'img_bottom'             => array( 1,    'астына', 'bottom' ),
	'img_text-bottom'        => array( 1,    'мәтін-астында', 'text-bottom' ),
	'int'                    => array( 0,    'ІШКІ:', 'INT:' ),
	'sitename'               => array( 1,    'ТОРАПАТАУЫ', 'SITENAME' ),
	'ns'                     => array( 0,    'ЕА:', 'ЕСІМАЯ:', 'NS:' ),
	'localurl'               => array( 0,    'ЖЕРГІЛІКТІЖАЙ:', 'LOCALURL:' ),
	'localurle'              => array( 0,    'ЖЕРГІЛІКТІЖАЙ2:', 'LOCALURLE:' ),
	'server'                 => array( 0,    'СЕРВЕР', 'SERVER' ),
	'servername'             => array( 0,    'СЕРВЕРАТАУЫ', 'SERVERNAME' ),
	'scriptpath'             => array( 0,    'ӘМІРЖОЛЫ', 'SCRIPTPATH' ),
	'grammar'                => array( 0,    'СЕПТІГІ:', 'СЕПТІК:', 'GRAMMAR:' ),
	'notitleconvert'         => array( 0,    '__АТАУАЛМАСТЫРҒЫЗБАУ__', '__ААБАУ__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'       => array( 0,    '__МАҒЛҰМАТАЛМАСТЫРҒЫЗБАУ__', '__МАБАУ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'            => array( 1,    'АҒЫМДАҒЫАПТАСЫ', 'АҒЫМДАҒЫАПТА', 'CURRENTWEEK' ),
	'currentdow'             => array( 1,    'АҒЫМДАҒЫАПТАКҮНІ', 'CURRENTDOW' ),
	'localweek'              => array( 1,    'ЖЕРГІЛІКТІАПТАСЫ', 'ЖЕРГІЛІКТІАПТА', 'LOCALWEEK' ),
	'localdow'               => array( 1,    'ЖЕРГІЛІКТІАПТАКҮНІ', 'LOCALDOW' ),
	'revisionid'             => array( 1,    'НҰСҚАНӨМІРІ', 'REVISIONID' ),
	'revisionday'            => array( 1,    'НҰСҚАКҮНІ' , 'REVISIONDAY' ),
	'revisionday2'           => array( 1,    'НҰСҚАКҮНІ2', 'REVISIONDAY2' ),
	'revisionmonth'          => array( 1,    'НҰСҚААЙЫ', 'REVISIONMONTH' ),
	'revisionyear'           => array( 1,    'НҰСҚАЖЫЛЫ', 'REVISIONYEAR' ),
	'revisiontimestamp'      => array( 1,    'НҰСҚАУАҚЫТТҮЙІНДЕМЕСІ', 'REVISIONTIMESTAMP' ),
	'plural'                 => array( 0,    'КӨПШЕТҮРІ:','КӨПШЕ:', 'PLURAL:' ),
	'fullurl'                => array( 0,    'ТОЛЫҚЖАЙЫ:', 'ТОЛЫҚЖАЙ:', 'FULLURL:' ),
	'fullurle'               => array( 0,    'ТОЛЫҚЖАЙЫ2:', 'ТОЛЫҚЖАЙ2:', 'FULLURLE:' ),
	'lcfirst'                => array( 0,    'КӘ1:', 'КІШІӘРІППЕН1:', 'LCFIRST:' ),
	'ucfirst'                => array( 0,    'БӘ1:', 'БАСӘРІППЕН1:', 'UCFIRST:' ),
	'lc'                     => array( 0,    'КӘ:', 'КІШІӘРІППЕН:', 'LC:' ),
	'uc'                     => array( 0,    'БӘ:', 'БАСӘРІППЕН:', 'UC:' ),
	'raw'                    => array( 0,    'ҚАМ:', 'RAW:' ),
	'displaytitle'           => array( 1,    'КӨРСЕТІЛЕТІНАТАУ', 'DISPLAYTITLE' ),
	'rawsuffix'              => array( 1,    'Қ', 'R' ),
	'newsectionlink'         => array( 1,    '__ЖАҢАБӨЛІМСІЛТЕМЕСІ__', '__NEWSECTIONLINK__' ),
	'currentversion'         => array( 1,    'БАҒДАРЛАМАНҰСҚАСЫ', 'CURRENTVERSION' ),
	'urlencode'              => array( 0,    'ЖАЙДЫМҰҚАМДАУ:', 'URLENCODE:' ),
	'anchorencode'           => array( 0,    'ЖӘКІРДІМҰҚАМДАУ', 'ANCHORENCODE' ),
	'currenttimestamp'       => array( 1,    'АҒЫМДАҒЫУАҚЫТТҮЙІНДЕМЕСІ', 'АҒЫМДАҒЫУАҚЫТТҮЙІН', 'CURRENTTIMESTAMP' ),
	'localtimestamp'         => array( 1,    'ЖЕРГІЛІКТІУАҚЫТТҮЙІНДЕМЕСІ', 'ЖЕРГІЛІКТІУАҚЫТТҮЙІН', 'LOCALTIMESTAMP' ),
	'directionmark'          => array( 1,    'БАҒЫТБЕЛГІСІ', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'               => array( 0,    '#ТІЛ:', '#LANGUAGE:' ),
	'contentlanguage'        => array( 1,    'МАҒЛҰМАТТІЛІ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'       => array( 1,    'ЕСІМАЯБЕТСАНЫ:', 'ЕАБЕТСАНЫ:', 'АЯБЕТСАНЫ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'         => array( 1,    'ӘКІМШІСАНЫ', 'NUMBEROFADMINS' ),
	'formatnum'              => array( 0,    'САНПІШІМІ', 'FORMATNUM' ),
	'padleft'                => array( 0,    'СОЛҒАЫҒЫС', 'СОЛЫҒЫС', 'PADLEFT' ),
	'padright'               => array( 0,    'ОҢҒАЫҒЫС', 'ОҢЫҒЫС', 'PADRIGHT' ),
	'special'                => array( 0,    'арнайы', 'special', ),
	'defaultsort'            => array( 1,    'ӘДЕПКІСҰРЫПТАУ:', 'ӘДЕПКІСАНАТСҰРЫПТАУ:', 'ӘДЕПКІСҰРЫПТАУКІЛТІ:', 'ӘДЕПКІСҰРЫП:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
);

$specialPageAliases = array(
        'DoubleRedirects'           => array( 'Шынжырлы_айдатулар' ),
        'BrokenRedirects'           => array( 'Жарамсыз_айдатулар' ),
        'Disambiguations'           => array( 'Айрықты_беттер' ),
        'Userlogin'                 => array( 'Қатысушы_кіруі' ),
        'Userlogout'                => array( 'Қатысушы_шығуы' ),
        'Preferences'               => array( 'Баптау' ),
        'Watchlist'                 => array( 'Бақылау_тізімі' ),
        'Recentchanges'             => array( 'Жуықтағы_өзгерістер' ),
        'Upload'                    => array( 'Қотару' ),
        'Imagelist'                 => array( 'Сурет_тізімі' ),
        'Newimages'                 => array( 'Жаңа_суреттер' ),
        'Listusers'                 => array( 'Қатысушылар' ),
        'Statistics'                => array( 'Санақ' ),
        'Randompage'                => array( 'Кездейсоқ_бет', 'Кездейсоқ' ),
        'Lonelypages'               => array( 'Саяқ_беттер' ),
        'Uncategorizedpages'        => array( 'Санатсыз_беттер' ),
        'Uncategorizedcategories'   => array( 'Санатсыз_санаттар' ),
        'Uncategorizedimages'       => array( 'Санатсыз_суреттер' ),
        'Uncategorizedtemplates'    => array( 'Санатсыз_үлгілер' ),
        'Unusedcategories'          => array( 'Пайдаланылмаған_санаттар' ),
        'Unusedimages'              => array( 'Пайдаланылмаған_суреттер' ),
        'Wantedpages'               => array( 'Толтырылмаған_беттер', 'Жарамсыз_сілтемелер' ),
        'Wantedcategories'          => array( 'Толтырылмаған_санаттар' ),
        'Mostlinked'                => array( 'Ең_көп_сілтенген_беттер' ),
        'Mostlinkedcategories'      => array( 'Ең_көп_сілтенген_санаттар' ),
        'Mostlinkedtemplates'       => array( 'Ең_көп_сілтенген_үлгілер' ),
        'Mostcategories'            => array( 'Ең_көп_санаттар_бары' ),
        'Mostimages'                => array( 'Ең_көп_суреттер_бары' ),
        'Mostrevisions'             => array( 'Ең_көп_нұсқалар_бары' ),
        'Fewestrevisions'           => array( 'Ең_аз_түзетілген ' ),
        'Shortpages'                => array( 'Қысқа_беттер' ),
        'Longpages'                 => array( 'Үлкен_беттер' ),
        'Newpages'                  => array( 'Жаңа_беттер' ),
        'Ancientpages'              => array( 'Ескі_беттер' ),
        'Deadendpages'              => array( 'Тұйық_беттер' ),
        'Protectedpages'            => array( 'Қорғалған_беттер' ),
        'Allpages'                  => array( 'Барлық_беттер' ),
        'Prefixindex'               => array( 'Бастауыш_тізімі' ) ,
        'Ipblocklist'               => array( 'Бұғатталғандар' ),
        'Specialpages'              => array( 'Арнайы_беттер' ),
        'Contributions'             => array( 'Үлесі' ),
        'Emailuser'                 => array( 'Хат_жіберу' ),
        'Whatlinkshere'             => array( 'Мында_сілтегендер' ),
        'Recentchangeslinked'       => array( 'Сілтенгендердің_өзгерістері' ),
        'Movepage'                  => array( 'Бетті_жылжыту' ),
        'Blockme'                   => array( 'Өздіктік_бұғаттау', 'Өздік_бұғаттау' ),
        'Booksources'               => array( 'Кітап_қайнарлары' ),
        'Categories'                => array( 'Санаттар' ),
        'Export'                    => array( 'Сыртқа_беру' ),
        'Version'                   => array( 'Нұсқасы' ),
        'Allmessages'               => array( 'Барлық_хабарлар' ),
        'Log'                       => array( 'Журналдар', 'Журнал' ),
        'Blockip'                   => array( 'Жайды_бұғаттау' ),
        'Undelete'                  => array( 'Жойылғанды_қайтару' ),
        'Import'                    => array( 'Сырттан_алу' ),
        'Lockdb'                    => array( 'Дерекқорды_құлыптау' ),
        'Unlockdb'                  => array( 'Дерекқорды_құлыптамау' ),
        'Userrights'                => array( 'Қатысушы_құқықтары' ),
        'MIMEsearch'                => array( 'MIME_түрімен_іздеу' ),
        'Unwatchedpages'            => array( 'Бақыланылмаған_беттер' ),
        'Listredirects'             => array( 'Айдату_тізімі' ),
        'Revisiondelete'            => array( 'Нұсқаны_жою' ),
        'Unusedtemplates'           => array( 'Пайдаланылмаған_үлгілер' ),
        'Randomredirect'            => array( 'Кедейсоқ_айдату' ),
        'Mypage'                    => array( 'Жеке_бетім' ),
        'Mytalk'                    => array( 'Талқылауым' ),
        'Mycontributions'           => array( 'Үлесім' ),
        'Listadmins'                => array( 'Әкімшілер'),
        'Popularpages'              => array( 'Әйгілі_беттер' ),
        'Search'                    => array( 'Іздеу' ),
        'Resetpass'                 => array( 'Құпия_сөзді_қайтару' ),
        'Withoutinterwiki'          => array( 'Уики-аралықсыздар' ),
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------

$messages = array(
# User preference toggles
'tog-underline'               => 'Сілтемені астынан сыз:',
'tog-highlightbroken'         => 'Жарамсыз сілтемелерді <a href="" class="new">былай</a> пішімде (баламасы: былай <a href="" class="internal">?</a> сияқты).',
'tog-justify'                 => 'Ежелерді ені бойынша туралау',
'tog-hideminor'               => 'Жуықтағы өзгерістерде шағын түзетуді жасыр',
'tog-extendwatchlist'         => 'Бақылау тізімді ұлғайт (барлық жарамды өзгерістерді көрсет)',
'tog-usenewrc'                => 'Кеңейтілген Жуықтағы өзгерістер (JavaScript)',
'tog-numberheadings'          => 'Бөлім тақырыптарын өздіктік түрде номірле',
'tog-showtoolbar'             => 'Өңдеу қуралдар жолағын көрсет (JavaScript)',
'tog-editondblclick'          => 'Қос нұқымдап өңдеу (JavaScript)',
'tog-editsection'             => 'Бөлімдерді [өңдеу] сілтемесімен өңдеуін ендір',
'tog-editsectiononrightclick' => 'Бөлім атауын оң жақ нұқумен<br />өңдеуін ендір (JavaScript)',
'tog-showtoc'                 => 'Мазмұнын көрсет (3-тен артық бөлімі барыларға)',
'tog-rememberpassword'        => 'Кіргенімді бұл компьютерде ұмытпа',
'tog-editwidth'               => 'Өңдеу аумағы толық енімен',
'tog-watchcreations'          => 'Мен бастаған беттерді бақылау тізіміме қос',
'tog-watchdefault'            => 'Мен өңдеген беттерді бақылау тізіміме қос',
'tog-watchmoves'              => 'Мен жылжытқан беттерді бақылау тізіміме қос',
'tog-watchdeletion'           => 'Мен жойған беттерді бақылау тізіміме қос',
'tog-minordefault'            => 'Әдепкіден барлық түзетулерді шағын деп белгілеу',
'tog-previewontop'            => 'Қарап шығу аумағы өңдеу аумағы алдында',
'tog-previewonfirst'          => 'Бірінші өңдегенде қарап шығу',
'tog-nocache'                 => 'Бет қосалқы қалтасын өшір',
'tog-enotifwatchlistpages'    => 'Бақыланған бет өзгергенде маған хат жібер',
'tog-enotifusertalkpages'     => 'Талқылауым өзгергенде маған хат жібер',
'tog-enotifminoredits'        => 'Шағын түзету туралы да маған хат жібер',
'tog-enotifrevealaddr'        => 'Е-пошта жайымды ескерту хатта ашық көрсет',
'tog-shownumberswatching'     => 'Бақылап тұрған қатысушылардың санын көрсет',
'tog-fancysig'                => 'Қам қолтаңба (өздіктік сілтемесіз;)',
'tog-externaleditor'          => 'Сыртқы өңдеуішті әдепкіден қолдан',
'tog-externaldiff'            => 'Сыртқы айырмағышты әдепкіден қолдан',
'tog-showjumplinks'           => '«Өтіп кету» қатынау сілтемелерін ендір',
'tog-uselivepreview'          => 'Тура қарап шығуды қолдану (JavaScript) (Сынақ түрінде)',
'tog-forceeditsummary'        => 'Өңдеу сипаттамасы бос қалғанда маған ескерт',
'tog-watchlisthideown'        => 'Түзетуімді бақылау тізімнен жасыр',
'tog-watchlisthidebots'       => 'Бот түзетуін бақылау тізімнен жасыр',
'tog-watchlisthideminor'      => 'Шағын түзетулерді бақылау тізімінде көрсетпеу',
'tog-nolangconversion'        => 'Тіл түрін аудармау',
'tog-ccmeonemails'            => 'Басқа қатысушыға жіберген хатымның көшірмесін маған да жібер',
'tog-diffonly'                => 'Айырма астында бет мағлұматын көрсетпе',

'underline-always'  => 'Әрқашан',
'underline-never'   => 'Ешқашан',
'underline-default' => 'Шолғыш бойынша',

'skinpreview' => '(Қарап шығу)',

# Dates
'sunday'        => 'Жексенбі',
'monday'        => 'Дүйсенбі',
'tuesday'       => 'Сейсенбі',
'wednesday'     => 'Сәрсенбі',
'thursday'      => 'Бейсенбі',
'friday'        => 'Жұма',
'saturday'      => 'Сенбі',
'sun'           => 'Жек',
'mon'           => 'Дүй',
'tue'           => 'Бей',
'wed'           => 'Сәр',
'thu'           => 'Бей',
'fri'           => 'Жұм',
'sat'           => 'Сен',
'january'       => 'қаңтар',
'february'      => 'ақпан',
'march'         => 'наурыз',
'april'         => 'cәуір',
'may_long'      => 'мамыр',
'june'          => 'маусым',
'july'          => 'шілде',
'august'        => 'тамыз',
'september'     => 'қыркүйек',
'october'       => 'қазан',
'november'      => 'қараша',
'december'      => 'желтоқсан',
'january-gen'   => 'қантардың',
'february-gen'  => 'ақпанның',
'march-gen'     => 'наурыздың',
'april-gen'     => 'сәуірдің',
'may-gen'       => 'мамырдың',
'june-gen'      => 'маусымның',
'july-gen'      => 'шілденің',
'august-gen'    => 'тамыздың',
'september-gen' => 'қыркүйектің',
'october-gen'   => 'қазанның',
'november-gen'  => 'қарашаның',
'december-gen'  => 'желтоқсанның',
'jan'           => 'қан',
'feb'           => 'ақп',
'mar'           => 'нау',
'apr'           => 'cәу',
'may'           => 'мам',
'jun'           => 'мау',
'jul'           => 'шіл',
'aug'           => 'там',
'sep'           => 'қыр',
'oct'           => 'қаз',
'nov'           => 'қар',
'dec'           => 'жел',

# Bits of text used by many pages
'categories'            => 'Барлық санат тізімі',
'pagecategories'        => '{{PLURAL:$1|Санат|Санаттар}}',
'category_header'       => '«$1» санатындағы беттер',
'subcategories'         => 'Санатшалар',
'category-media-header' => '«$1» санатындағы таспалар',

'linkprefix'        => '/^(.*?)([a-zäçéğıïñöşüýа-яёәіңғүұқөһA-ZÄÇÉĞİÏÑÖŞÜÝА-ЯЁӘІҢҒҮҰҚӨҺʺʹ«„]+)$/sDu',
'mainpagetext'      => "<big>'''МедиаУики бағдарламасы сәтті орнатылды.'''</big>",
'mainpagedocfooter' => 'Уики бағдарламасын пайдалану ақпараты үшін [http://meta.wikimedia.org/wiki/Help:Contents Пайдаланушы нұсқауларымен] танысыңыз.

== Бастау ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Баптау қалаулары тізімі]
* [http://www.mediawiki.org/wiki/Help:FAQ МедиаУики ЖҚС]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce МедиаУики хат тарату тізімі]',

'about'          => 'Біз туралы',
'article'        => 'Мағлұмат беті',
'newwindow'      => '(жаңа терезеде ашылады)',
'cancel'         => 'Болдырмау',
'qbfind'         => 'Табу',
'qbbrowse'       => 'Шолу',
'qbedit'         => 'Өңдеу',
'qbpageoptions'  => 'Осы бет',
'qbpageinfo'     => 'Мәтін аралығы',
'qbmyoptions'    => 'Беттерім',
'qbspecialpages' => 'Арнайы беттер',
'moredotdotdot'  => 'Көбірек…',
'mypage'         => 'Жеке бетім',
'mytalk'         => 'Талқылауым',
'anontalk'       => 'IP талқылауы',
'navigation'     => 'Бағыттау',

# Metadata in edit box
'metadata_help' => 'Мета-деректер:',

'errorpagetitle'    => 'Қате',
'returnto'          => '$1 дегенге оралу.',
'tagline'           => '{{GRAMMAR:ablative|{{SITENAME}}}}',
'help'              => 'Анықтама',
'search'            => 'Іздеу',
'searchbutton'      => 'Іздеу',
'go'                => 'Өту',
'searcharticle'     => 'Өту',
'history'           => 'Бет тарихы',
'history_short'     => 'Тарихы',
'updatedmarker'     => 'соңғы кірістен бері жаңартылған',
'info_short'        => 'Ақпарат',
'printableversion'  => 'Басып шығаруға',
'permalink'         => 'Тұрақты сілтеме',
'print'             => 'Басып шығару',
'edit'              => 'Өңдеу',
'editthispage'      => 'Бетті өңдеу',
'delete'            => 'Жою',
'deletethispage'    => 'Бетті жою',
'undelete_short'    => '{{PLURAL:$1|Бір|$1}} түзетуді қайтару',
'protect'           => 'Қорғау',
'protect_change'    => 'қорғауды өзгерту',
'protectthispage'   => 'Бетті қорғау',
'unprotect'         => 'Қорғамау',
'unprotectthispage' => 'Бетті қорғамау',
'newpage'           => 'Жаңа бет',
'talkpage'          => 'Бетті талқылау',
'talkpagelinktext'  => 'Талқылауы',
'specialpage'       => 'Арнайы бет',
'personaltools'     => 'Жеке құралдар',
'postcomment'       => 'Мәндеме жіберу',
'articlepage'       => 'Мағлұмат бетін қарау',
'talk'              => 'Талқылау',
'views'             => 'Көрініс',
'toolbox'           => 'Құралдар',
'userpage'          => 'Қатысушының бетін қарау',
'projectpage'       => 'Жоба бетін қарау',
'imagepage'         => 'Сурет бетін қарау',
'mediawikipage'     => 'Хабар бетін қарау',
'templatepage'      => 'Үлгі бетін қарау',
'viewhelppage'      => 'Анықтама бетін қарау',
'categorypage'      => 'Санат бетін қарау',
'viewtalkpage'      => 'Талқылау бетін қарау',
'otherlanguages'    => 'Басқа тілдерде',
'redirectedfrom'    => '($1 бетінен айдатылған)',
'redirectpagesub'   => 'Айдату беті',
'lastmodifiedat'    => 'Бұл беттің өзгертілген соңғы кезі: $2, $1.', # $1 date, $2 time
'viewcount'         => 'Бұл бет {{PLURAL:$1|бір|$1}} рет қаралған.',
'protectedpage'     => 'Қорғаулы бет',
'jumpto'            => 'Мынаған өтіп кету:',
'jumptonavigation'  => 'бағыттау',
'jumptosearch'      => 'іздеу',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} туралы',
'aboutpage'         => '{{ns:project}}:Біз_туралы',
'bugreports'        => 'Қате есептемелері',
'bugreportspage'    => '{{ns:project}}:Қате_есептемелері',
'copyright'         => 'Мағлұмат $1 құжаты бойынша қатынаулы.',
'copyrightpagename' => '{{SITENAME}} ауторлық құқықтары',
'copyrightpage'     => '{{ns:project}}:Ауторлық құқықтар',
'currentevents'     => 'Ағымдағы оқиғалар',
'currentevents-url' => 'Ағымдағы_оқиғалар',
'disclaimers'       => 'Жауапкершіліктен бас тарту',
'disclaimerpage'    => '{{ns:project}}:Жауапкершіліктен_бас_тарту',
'edithelp'          => 'Өндеу анықтамасы',
'edithelppage'      => '{{ns:help}}:Өңдеу',
'faq'               => 'ЖҚС',
'faqpage'           => '{{ns:project}}:ЖҚС',
'helppage'          => '{{ns:help}}:Мазмұны',
'mainpage'          => 'Басты бет',
'policy-url'        => '{{ns:project}}:Ережелер',
'portal'            => 'Қауым порталы',
'portal-url'        => '{{ns:project}}:Қауым_порталы',
'privacy'           => 'Жеке құпиясын сақтау',
'privacypage'       => '{{ns:project}}:Жеке_құпиясын_сақтау',
'sitesupport'       => 'Демеушілік',
'sitesupport-url'   => '{{ns:project}}:Жәрдем',

'badaccess'        => 'Рұқсат қатесі',
'badaccess-group0' => 'Сұранысқан әрекетіңізді жегуіңізге рұқсат етілмейді.',
'badaccess-group1' => 'Сұранысқан әрекетіңіз $1 тобының қатысушыларына шектеледі.',
'badaccess-group2' => 'Сұранысқан әрекетіңіз $1 топтары бірінің қатусышыларына шектеледі.',
'badaccess-groups' => 'Сұранысқан әрекетіңіз $1 топтары бірінің қатусышыларына шектеледі.',

'versionrequired'     => 'MediaWiki $1 нұсқасы қажет',
'versionrequiredtext' => 'Осы бетті қолдану үшін MediaWiki $1 нұсқасы қажет. [[{{ns:special}}:Version|Жүйе нұсқасы бетін]] қараңыз.',

'ok'                  => 'Жарайды',
'pagetitle'           => '$1 — {{SITENAME}}',
'retrievedfrom'       => '«$1» дегеннен алынған',
'youhavenewmessages'  => 'Сізде $1 бар ($2).',
'newmessageslink'     => 'жаңа хабарлар',
'newmessagesdifflink' => 'соңғы өзгерісіне',
'editsection'         => 'өңдеу',
'editold'             => 'өңдеу',
'editsectionhint'     => 'Бөлімді өңдеу: $1',
'toc'                 => 'Мазмұны',
'showtoc'             => 'көрсет',
'hidetoc'             => 'жасыр',
'thisisdeleted'       => 'Қараймыз ба, не қайтарамыз ба?: $1',
'viewdeleted'         => 'Қараймыз ба?: $1',
'restorelink'         => 'жойылған {{PLURAL:$1|бір|$1}} түзету',
'feedlinks'           => 'Арна:',
'feed-invalid'        => 'Жарамсыз жазылым арна түрі.',
'feed-atom'           => 'Atom',
'feed-rss'            => 'RSS',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Мағлұмат',
'nstab-user'      => 'Жеке беті',
'nstab-media'     => 'Таспа беті',
'nstab-special'   => 'Арнайы',
'nstab-project'   => 'Жоба беті',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Жүйе хабары',
'nstab-template'  => 'Үлгі',
'nstab-help'      => 'Анықтама',
'nstab-category'  => 'Санат',

# Main script and global functions
'nosuchaction'      => 'Мұндай әрекет жоқ',
'nosuchactiontext'  => 'Осы URL жайымен енгізілген әрекетті
осы уики жорамалдап білмеді.',
'nosuchspecialpage' => 'Бұл арнайы бет емес',
'nospecialpagetext' => 'Сіз сұранысқан арнайы бет жарамсыз. Барлық жарамды арнайы беттерді [[{{ns:special}}:Specialpages|арнайы беттер тізімінде]] таба аласыз.',

# General errors
'error'                => 'Қате',
'databaseerror'        => 'Дерекқордың қатесі',
'dberrortext'          => 'Дерекқорға сұраныс жасалғанда синтаксис қатесі кездесті.
Бұл бағдарламаның қатесін көрсету мүмкін.
Дерекқорға соңғы болған сұраныс:
<blockquote><tt>$1</tt></blockquote>
мына функциясынан «<tt>$2</tt>».
MySQL қайтарған қатесі «<tt>$3: $4</tt>».',
'dberrortextcl'        => 'Дерекқорға сұраныс жасалғанда синтаксис қатесі кездесті.
Дерекқорға соңғы болған сұраныс:
«$1»
мына функциясынан: «$2».
MySQL қайтарған қатесі «$3: $4»',
'noconnect'            => 'Ғафу етіңіз! Бұл уикиде кейбір техникалық қиыншылықтар кездесті, сондықтан дерекқор серверіне қатынасу алмайды. <br />
$1',
'nodb'                 => '$1 дерекқоры талғанбады',
'cachederror'          => 'Төменде сұранған беттің қосалқы қалтадағы көшірмесі, осы бет жаңартылмаған болуы мүмкін.',
'laggedslavemode'      => 'Назар салыңыз: Бетте жуықтағы жаңалаулар болмауы мүмкін.',
'readonly'             => 'Дерекқоры құлыпталған',
'enterlockreason'      => 'Құлыптау себебін енгізіңіз, қай уақытқа дейін
құлыпталғанын қоса',
'readonlytext'         => 'Ағымда дерекқор жаңа жазба және тағы басқа өзгерістер жасаудан құлыпталынған. Бұл дерекқорды жөндету бағдарламаларын орындау үшін болуы мүмкін, бұны бітіргеннен соң қаліпті іске қайтарылады.

Құлыптаған әкімші бұны былай түсіндіреді: $1',
'missingarticle'       => 'Іздестірілген «$1» атаулы бет мәтіні дерекқорда табылмады.

Бұл дағдыда ескірген айырма сілтемесіне немесе жойылған бет тарихының сілтемесіне
ергеннен болуы мүмкін.

Егер бұл болжам дұрыс себеп болмаса, бағдарламамыздағы қатеге тап болуыңыз мүмкін.
Бұл туралы нақты URL жайын көрсетіп әкімшіге есептеме жіберіңіз.',
'readonly_lag'         => 'Жетек дерекқор серверлер бастауышпен қадамланғанда осы дерекқор өздіктік құлыпталынған',
'internalerror'        => 'Ішкі қате',
'filecopyerror'        => '«$1» файлы «$2» файлына көшірілмеді.',
'filerenameerror'      => '«$1» файл аты «$2» атына өзгертілмеді.',
'filedeleteerror'      => '«$1» файлы жойылмайды.',
'filenotfound'         => '«$1» файлы табылмады.',
'unexpected'           => 'Күтілмеген мағына: «$1» = «$2».',
'formerror'            => 'Қате: жіберу үлгіті емес',
'badarticleerror'      => 'Осындай әрекет мына бетте атқарылмайды.',
'cannotdelete'         => 'Айтылмыш бет не сурет жойылмайды. (Бұны басқа біреу жойған шығар.)',
'badtitle'             => 'Жарамсыз атау',
'badtitletext'         => 'Сұранысқан бет атауы жарамсыз, бос, тілара сілтемесі не уики-ара атауы мүлтік болған. Атауларда сүемелдемеген бірқатар әріптер болуы мүмкін.',
'perfdisabled'         => 'Ғафу етіңіз! Осы қасиет, дерекқордың жылдамылығына әсер етіп, ешкімге уикиді пайдалануға бермегесін, уақытша өшірілген.',
'perfcached'           => 'Келесі дерек қосалқы қалтасынан алынған, сондықтан толықтай жаңаланмаған болуы мүмкін.',
'perfcachedts'         => 'Келесі дерек қосалқы қалтасынан алынған, соңғы жаңаланлған кезі: $1.',
'querypage-no-updates' => 'Бұл беттің жаңартылуы ағымда өшірілген. Деректері қазір өзгертілмейді.',
'wrong_wfQuery_params' => 'wfQuery() функциясында жарамсыз баптар<br />
Функция: $1<br />
Сұраныс: $2',
'viewsource'           => 'Қайнарын қарау',
'viewsourcefor'        => '$1 деген үшін',
'protectedpagetext'    => 'Өңдеуді қақпайлау үшін бұл бет құлыпталынған.',
'viewsourcetext'       => 'Бұл беттің қайнарын қарауыңызға және көшіріп алуңызға болады:',
'protectedinterface'   => 'Бұл бет бағдарламаның тілдесу мәтінін жетістіреді, сондықтан қиянатты қақпайлау үшін өзгертуі құлыпталған.',
'editinginterface'     => "'''Назар салыңыз:''' Бағдарламаға тілдесу мәтінін жетістіретін MediaWiki бетін өңдеп жатырсыз. Бұл беттің өзгертуі барлық пайдаланушылар тілдесуіне әсер етеді.",
'sqlhidden'            => '(SQL сұранысы жасырылды)',
'cascadeprotected'     => 'Бұл бет өңдеуден қорғалған, себебі: ол мына «баулы» қорғауы ендірілген {{PLURAL:$1|бетке|беттерге}} кіріктірілген:',

# Login and logout pages
'logouttitle'                => 'Қатысушы шығуы',
'logouttext'                 => '<strong>Енді жүйеден шықтыңыз.</strong><br />
Бұл компьютерден әлі де жүйеге кірместен {{SITENAME}} жобасын
шолуыңыз мүмкін, немесе басқа пайдаланушының жүйеге кіруі мүмкін.
Кейбір беттерде әлі де жүйеге кіргеніңіздей көрінуі мүмкіндігін
ескертеміз; бұл шолғыштың қосалқы қалтасын босату арқылы шешіледі.',
'welcomecreation'            => '== Қош келдіңіз, $1! ==

Тіркелгіңіз жасалды. {{SITENAME}} баптауыңызды қалауыңызбен өзгертуді ұмытпаңыз.',
'loginpagetitle'             => 'Қатысушы кіруі',
'yourname'                   => 'Қатысушы атыңыз:',
'yourpassword'               => 'Құпия сөзіңіз:',
'yourpasswordagain'          => 'Құпия сөзді қайталаңыз:',
'remembermypassword'         => 'Менің кіргенімді бұл компьютерде ұмытпа',
'yourdomainname'             => 'Желі үйшігіңіз:',
'externaldberror'            => 'Осында сыртқы теңдестіру дерекқорында қате болды, немесе сыртқы тіркелгіңізді жаңалауға рұқсат жоқ.',
'loginproblem'               => '<b>Кіруіңіз кезінде осында қиындыққа тап болдық.</b><br />Тағы да қайталап қараңыз.',
'alreadyloggedin'            => '<strong>$1 деген қатысушы, кіріпсіз түге!</strong><br />',
'login'                      => 'Кіру',
'loginprompt'                => '{{SITENAME}} торабына кіру үшін «cookies» қасиетін ендіруіңіз қажет.',
'userlogin'                  => 'Кіру / Тіркелгі жасау',
'logout'                     => 'Шығу',
'userlogout'                 => 'Шығу',
'notloggedin'                => 'Кірмегенсіз',
'nologin'                    => 'Тіркелгіңіз жоқ па? $1.',
'nologinlink'                => 'Жасаңыз',
'createaccount'              => 'Тіркелгі жаса',
'gotaccount'                 => 'Тіркелгіңіз бар ма?  $1.',
'gotaccountlink'             => 'Кіріңіз',
'createaccountmail'          => 'е-поштамен',
'badretype'                  => 'Енгізген құпия сөздеріңіз бір біріне сәйкес емес.',
'userexists'                 => 'Енгізген қатысушы атыңызды біреу пайдаланып жатыр. Басқа атау тандаңыз.',
'youremail'                  => 'Е-пошта жайыңыз:',
'username'                   => 'Қатысушы атыңыз:',
'uid'                        => 'Қатысушы теңдестіруіңіз:',
'yourrealname'               => 'Шын атыңыз:',
'yourlanguage'               => 'Тіліңіз:',
'yourvariant'                => 'Түрі',
'yournick'                   => 'Лақап атыңыз:',
'badsig'                     => 'Қам қолтаңбаңыз жарамсыз; HTML белгішелерін тексеріңіз.',
'badsiglength'               => 'Лақап атыңыз тым ұзын; $1 нышаннан аспауы қажет.',
'email'                      => 'Е-поштаңыз',
'prefs-help-realname'        => 'Міндетті емес: Енгізсеңіз, шығармаңыздың ауторлығын белгілеуі үшін қолданылады.',
'loginerror'                 => 'Кіру қатесі',
'prefs-help-email'           => 'Міндетті емес: «Қатысушы» немесе «Қатысушы_талқылауы» деген беттеріңіз арқылы басқаларға байланысу мүмкіндік береді. Өзіңіздің кім екеніңізді білдіртпейді.',
'nocookiesnew'               => 'Қатысушы тіркелгісі жасалды, тек әлі кірмегенсіз. {{SITENAME}} жобасына қатысушы кіру үшін «cookies» қасиеті қажет. Шолғышыңызда «cookies» қасиеті өшірілген. Соны ендіріңіз де жаңа қатысушы атыңызды және құпия сөзіңізді енгізіп кіріңіз.',
'nocookieslogin'             => 'Қатысушы кіру үшін {{SITENAME}} жобасы «cookies» қасиетін қолданады. Шолғышыңызда «cookies» қасиеті өшірілген. Соны ендіріңіз де қайталап кіріңіз.',
'noname'                     => 'Қатысушы атын дұрыс енгізбедіңіз.',
'loginsuccesstitle'          => 'Кіруіңіз сәтті өтті',
'loginsuccess'               => "'''Сіз енді {{SITENAME}} жобасына «$1» ретінде кіріп отырсыз.'''",
'nosuchuser'                 => 'Мында «$1» атаулы қатысушы жоқ. Емлеңізді тексеріңіз, немесе жаңа тіркелгі жасаңыз.',
'nosuchusershort'            => 'Мында «$1» деген қатысушы атауы жоқ. Емлеңізді тексеріңіз.',
'nouserspecified'            => 'Қатысушы атын енгізіуіңіз қажет.',
'wrongpassword'              => 'Енгізген құпия сөз жарамсыз. Қайталап көріңіз.',
'wrongpasswordempty'         => 'Құпия сөз босты бопты. Қайталап көріңіз.',
'mailmypassword'             => 'Құпия сөзімді хатпен жібер',
'passwordremindertitle'      => 'Құпия сөз туралы {{SITENAME}} жобасының ескертуі',
'passwordremindertext'       => 'Кейбіреу (IP жайы: $1, бәлкім, өзіңіз боларсыз)
{{SITENAME}} үшін бізден жаңа құпия сөзін жіберуін сұранысқан ($4).
«$2» қатысушының құпия сөзі «$3» болды енді.
Қазір кіруіңіз және құпия сөзіңізді ауыструыңыз қажет.

Егер басқа біреу бұл сұранысты жасаса, немесе құпия сөзіңізді ұмытсаңыз да,
және бұны өзгерткіңіз келмесе де, осы хабарламаға аңғармауыңызға да болады,
ескі құпия сөзіңізді әріғарай қолданып.',
'noemail'                    => 'Мында «$1» қатысушының е-поштасы жоқ.',
'passwordsent'               => 'Жаңа құпия сөз «$1» үшін
тіркелген е-пошта жайына жіберілді.
Қабылдағаннан кейін кіргенде соны енгізіңіз.',
'blocked-mailpassword'       => 'IP жайыңыздан өңдеу бұғатталған, сондықтан
қиянатты қақпайлау үшін құпия сөз жіберу қызметінің әрекеті рұқсат етілмейді.',
'eauthentsent'               => 'Куәландыру хаты аталған е-пошта жайына жіберілді.
Басқа е-пошта хатын жіберудің алдынан, тіркелгі шынынан сіздікі екенін
куәландыру үшін хаттағы нұсқауларға еріңіз.',
'throttled-mailpassword'     => 'Соңғы $1 сағатта құпия сөз ескерту хаты жіберілді түге.
Қиянатты қақпайлау үшін, $1 сағат сайын тек бір ғана құпия сөз ескерту
хаты жіберіледі.',
'mailerror'                  => 'Хат жіберу қатесі: $1',
'acct_creation_throttle_hit' => 'Ғафу етіңіз, сіз $1 тіркелгі жасапсыз түге. Онан артық істей алмайсыз.',
'emailauthenticated'         => 'Е-пошта жайыңыз куәландырылған кезі: $1.',
'emailnotauthenticated'      => 'Е-пошта жайыңыз әлі куәландырған жоқ.
Төмендегі қасиетттер үшін ешқандай хат жіберілмейді.',
'noemailprefs'               => 'Осы қасиеттер істеуі үшін е-пошта жайыңызды енгізіңіз.',
'emailconfirmlink'           => 'Е-пошта жайыңызды куәландырыңыз',
'invalidemailaddress'        => 'Осы е-пошта жайда жарамсыз пішім болған, қабыл етілмейді.
Дұрыс пішімделген жайды енгізіңіз, не аумақты бос қалдырыңыз.',
'accountcreated'             => 'Тіркелгі жасалды',
'accountcreatedtext'         => '$1 үшін қатысушы тіркелгісі жасалды.',

# Password reset dialog
'resetpass'               => 'Тіркелгінің құпия сөзін бұрынғы қалыпына келтіру',
'resetpass_announce'      => 'Хатпен жіберілген уақытша белгілемемен кіріпсіз. Тіркелуді бітіру үшін жаңа құпия сөзіңізді мында енгізіңіз:',
'resetpass_header'        => 'Құпия сөзді бұрынғы қалыпына келтіру',
'resetpass_submit'        => 'Құпия сөзді қалаңыз да кіріңіз',
'resetpass_success'       => 'Құпия сөзіңіз сәтті өзгертілді! Енді кіріңіз…',
'resetpass_bad_temporary' => 'Уақытша құпия сөз жарамсыз. Мүмкін құпия сөзіңізді өзгерткен боларсыз немесе жаңа уақытша құпия сөз сұраған боларсыз.',
'resetpass_forbidden'     => 'Бұл уикиде құпия сөздер өзгертілмейді',
'resetpass_missing'       => 'Үлгіт деректері жоқ.',

# Edit page toolbar
'bold_sample'     => 'Жуан мәтін',
'bold_tip'        => 'Жуан мәтін',
'italic_sample'   => 'Қиғаш мәтін',
'italic_tip'      => 'Қиғаш мәтін',
'link_sample'     => 'Сілтеме атауы',
'link_tip'        => 'Ішкі сілтеме',
'extlink_sample'  => 'http://www.example.com сілтеме атауы',
'extlink_tip'     => 'Сыртқы сілтеме (алдынан http:// енгізуін ұмытпаңыз)',
'headline_sample' => 'Тақырып мәтіні',
'headline_tip'    => '1-ші деңгейлі тақырып',
'math_sample'     => 'Формуланы мында енгізіңіз',
'math_tip'        => 'Математика формуласы (LaTeX)',
'nowiki_sample'   => 'Пішімделмейтін мәтінді осында енгізіңіз',
'nowiki_tip'      => 'Уики пішімін елемеу',
'image_sample'    => 'Example.jpg',
'image_tip'       => 'Кіріктірілген сурет',
'media_sample'    => 'Example.ogg',
'media_tip'       => 'Таспа файлының сілтемесі',
'sig_tip'         => 'Қолтаңбаңыз және уақыт белгісі',
'hr_tip'          => 'Дерелей сызық (үнемді қолданыңыз)',

# Edit pages
'summary'                   => 'Сипаттамасы',
'subject'                   => 'Тақырыбы/басы',
'minoredit'                 => 'Бұл шағын түзету',
'watchthis'                 => 'Бетті бақылау',
'savearticle'               => 'Бетті сақта!',
'preview'                   => 'Қарап шығу',
'showpreview'               => 'Қарап шығу',
'showlivepreview'           => 'Тура қарап шығу',
'showdiff'                  => 'Өзгерістерді көрсет',
'anoneditwarning'           => "'''Назар салыңыз:''' Сіз жүйеге кірмегенсіз. IP жайыңыз бұл беттің өңдеу тарихында жазылып алынады.",
'missingsummary'            => "'''Ескерту:''' Түзету сипаттамасын енгізбепсіз. «Сақтау» түймесін тағы бассаңыз, түзетуіңіз мәндемесіз сақталады.",
'missingcommenttext'        => 'Төменде мәндемеңізді енгізіңіз.',
'missingcommentheader'      => "'''Ескерту:''' Бұл мәндемеге тақырып/басжол жетістірмепсіз. Егер тағы да Сақтау түймесін нұқысаңыз, түзетуіңіз солсыз сақталады.",
'summary-preview'           => 'Сипаттамасын қарап шығу',
'subject-preview'           => 'Тақырыбын/басын қарап шығу',
'blockedtitle'              => 'Пайдаланушы бұғатталған',
'blockedtext'               => "<big>'''Қатысушы атыңыз не IP жайыңыз бұғатталған.'''</big>

Бұғаттауды $1 істеген. Келтірілген себебі: ''$2''.

Бұғаттау бітетін мезгілі: $6<br />
Мақсатталған мерзімі: $7

Осы бұғаттауды талқылау үшін $1 дегенмен, не басқа [[{{{{ns:mediawiki}}:grouppage-sysop}}|әкімшімен]] қатынасуыңызға болады.
[[{{ns:special}}:Preferences|Тіркелгі баптауларын]] қолданып жарамды е-пошта жайын енгізгенше дейін және бұны пайдалануы 
бұғатталмаған болса «Қатысушыға хат жазу» қасиетін қолданбайсыз.
Ағымдық IP жайыңыз: $3, және бұғатау нөмірі: $5. Соның біреуін, немесе екеуін де әрбір сұранысыңызға қосыңыз.",
'autoblockedtext'           => "$1 деген бұрын басқа қатысушы пайдаланған болғасын осы IP жайыңыз өздіктік бұғатталған.
Белгіленген себебі:

:''$2''

Бұғаттау бітетін мезгілі: $6

Осы бұғаттауды талқылау үшін $1 дегенмен,
не басқа [[{{{{ns:mediawiki}}:grouppage-sysop}}|әкімшімен]] қатынасуыңызға болады.

[[{{ns:special}}:Preferences|Тіркелгі баптауларын]] қолданып жарамды е-пошта жайын енгізгенше 
дейін және бұны пайдалануы бұғатталмаған болса «Қатысушыға хат жазу» қасиетін қолданбайсыз. 

Бұғатау нөміріңіз: $5. Бұл нөмірді әрбір сұранысыңызға қосыңыз.",
'blockedoriginalsource'     => "Төменде '''$1''' дегеннің қайнары көрсетіледі:",
'blockededitsource'         => "Төменде '''$1''' дегенге жасалған '''түзетуңіздің''' мәтіні көрсетіледі:",
'whitelistedittitle'        => 'Өңдеу үшін кіруіңіз жөн.',
'whitelistedittext'         => 'Беттерді өңдеу үшін $1 жөн.',
'whitelistreadtitle'        => 'Оқу үшін кіруіңіз жөн',
'whitelistreadtext'         => 'Беттерді оқу үшін [[{{ns:special}}:Userlogin|кіруіңіз]] жөн.',
'whitelistacctitle'         => 'Сізге тіркелгі жасауға рұқсат берілмеген',
'whitelistacctext'          => 'Осы уикиде басқаларға тіркелгі жасау үшін [[{{ns:special}}:Userlogin|кіруіңіз]] қажет және жанасымды рұқсаттарын билеу қажет.',
'confirmedittitle'          => 'Е-пошта жайын куәландыру хатын қайта өңдеу қажет',
'confirmedittext'           => 'Беттерді өңдеу үшін алдын ала Е-пошта жайыңызды куәландыруыңыз қажет. Жайыңызды [[{{ns:Special}}:Preferences|қатысушы баптауы]] арқылы енгізіңіз және тексерткіңіз.',
'nosuchsectiontitle'        => 'Бұл бөлім емес',
'nosuchsectiontext'         => 'Жоқ бөлімді өңдеуді талап етіпсіз. Мында $1 деген бөлім жоқ екен, өңдеулеріңізді сақтау үшін орын жоқ.',
'loginreqtitle'             => 'Кіруіңіз қажет',
'loginreqlink'              => 'кіру',
'loginreqpagetext'          => 'Басқа беттерді көру үшін сіз $1 болуыңыз қажет.',
'accmailtitle'              => 'Құпия сөз жіберілді.',
'accmailtext'               => '$2 жайына «$1» құпия сөзі жіберілді.',
'newarticle'                => '(Жаңа)',
'newarticletext'            => 'Сілтемеге еріп әлі басталмаған бетке
келіпсіз. Бетті бастау үшін, төмендегі аумақта мәтініңізді
теріңіз (көбірек ақпарат үшін [[{{{{ns:mediawiki}}:helppage}}|анықтама бетін]]
қараңыз).Егер жаңылғаннан осында келген болсаңыз, шолғышыңыз
«Артқа» деген түймесін нұқыңыз.',
'anontalkpagetext'          => "----''Бұл тіркелгісіз (немесе тіркелгісін қолданбаған) пайдаланушының талқылау беті. Осы пайдаланушыны біз тек сандық IP жайымен теңдестіреміз. Осындай IP жайлар бірнеше пайдаланушыға ортақ болуы мүмкін. Егер сіз тіркелгісіз пайдаланушы болсаңыз және сізге қатыссыз мәндемелер жіберілгенін сезсеңіз, басқа тіркелгісіз пайдаланушылармен араластырмауы үшін [[{{ns:special}}:Userlogin|тіркелгі жасаңыз не кіріңіз]].''",
'noarticletext'             => 'Бұл бетте ағымда еш мәтін жоқ, басқа беттерден осы бет атауын [[{{ns:special}}:Search/{{PAGENAME}}|іздеп көруіңізге]] немесе осы бетті [{{fullurl:{{FULLPAGENAME}}|action=edit}} түзетуіңізге] болады.',
'clearyourcache'            => "'''Аңғартпа:''' Сақтағаннан кейін өзгерістерді көру үшін шолғыш қосалқы қалтасын босату керегі мүмкін. '''Mozilla  / Safari:''' ''Shift'' пернесін басып тұрып ''Reload'' (''Қайта жүктеу'') түймесін нұқыңыз (не ''Ctrl-Shift-R'' басыңыз); ''IE:'' ''Ctrl-F5'' басыңыз; '''Opera / Konqueror''' ''F5'' пернесін басыңыз.",
'usercssjsyoucanpreview'    => '<strong>Басалқы:</strong> Сақтау алдында жаңа CSS/JS файлын тексеру үшін «Қарап шығу» түймесін қолданыңыз.',
'usercsspreview'            => "'''Мынау CSS мәтінін тек қарап шығу екенін ұмытпаңыз, ол әлі сақталған жоқ!'''",
'userjspreview'             => "'''Мынау JavaScript қатысушы бағдарламасын тексеру/қарап шығу екенін ұмытпаңыз, ол әлі сақталған жоқ!'''",
'userinvalidcssjstitle'     => "'''Назар салыңыз:''' Бұл «$1» деген безендіру мәнері емес. Пайдаланушының .css және .js файл атауы кіші әріпппен жазылу тиісті екенін ұмытпаңыз, мысалға {{ns:user}}:Foo/monobook.css дегенді {{ns:user}}:Foo/Monobook.css дегенмен салыстырып қараңыз.",
'updated'                   => '(Жаңартылған)',
'note'                      => '<strong>Аңғартпа:</strong>',
'previewnote'               => '<strong>Мынау тек қарап шығу екенін ұмытпаңыз; түзетулер әлі сақталған жоқ!</strong>',
'previewconflict'           => 'Бұл қарап шығу жоғарыдағы өңдеу аумағындағы мәтінге сақтаған кезіндегі дей ықпал етеді.',
'session_fail_preview'      => '<strong>Ғафу етіңіз! Сессия деректері ысырап қалғандықтан өңдеуіңізді жөндей алмаймыз.
Мәтініңізді сақтап қайталап көріңіз. Егер әлі іс өтпейтін болса, шығып және кері кіріп көріңіз.</strong>',
'session_fail_preview_html' => "<strong>Ғафу етіңіз! Сессия деректері ысырап қалғандықтан өңдеуіңізді жөндей алмаймыз.</strong>

''Осы уикиде қам HTML ендірілген, JavaScript шабуылдардан қорғану үшін алдын ала қарап шығу жасырылған.''

<strong>Егер бұл өңдеу адал талап болса, қайтарып көріңіз. Егер әлі де істемесе, шығып, сосын кері кіріп көріңіз.</strong>",
'importing'                 => 'Сырттан алуда: $1',
'editing'                   => 'Өңделуде: $1',
'editinguser'               => 'Өңделуде: <b>$1</b> деген қатысушы',
'editingsection'            => 'Өңделуде: $1 (бөлімі)',
'editingcomment'            => 'Өңделуде: $1 (мәндемесі)',
'editconflict'              => 'Өңдеу қақтығысы: $1',
'explainconflict'           => 'Осы бетті сіз өңдей бастағанда басқа кейбіреу бетті өзгерткен.
Жоғарғы аумақта беттің ағымдық мәтіні бар.
Төменгі аумақта сіз өзгерткен мәтіні көрсетіледі.
Өзгертуіңізді ағымдық мәтінге үстеуіңіз жөн.
"Бетті сақта!" түймесіне басқанда
<b>тек</b> жоғарғы аумақтағы мәтін сақталады.<br />',
'yourtext'                  => 'Мәтініңіз',
'storedversion'             => 'Сақталған нұсқасы',
'nonunicodebrowser'         => '<strong>АҢҒАРТПА: Шолғышыңыз Unicode белгілеуіне үйлесімді емес, сондықтан латын емес әріптері бар беттерді өңдеу зіл болу мүмкін. Жұмыс істеуге ықтималдық беру үшін, төменгі өңдеу аумағында ASCII емес әріптер оналтылық санымен көрсетіледі</strong>.',
'editingold'                => '<strong>АҢҒАРТПА: Осы беттің ертерек нұсқасын
өңдеп жатырсыз.
Бұны сақтасаңыз, осы нусқадан соңғы барлық өзгерістер жойылады.</strong>',
'yourdiff'                  => 'Айырмалар',
'copyrightwarning'          => '{{SITENAME}} жобасына қосылған бүкіл үлес $2 (көбірек ақпарат үшін: $1) құжатына сай жіберілген болып саналады. Егер жазуыңыздың еркін көшіріліп түзетілуін қаламасаңыз, мында ұсынбауыңыз жөн.<br />
Тағы, қосқан үлесіңіз - өзіңіздің жазғанығыз, не ашық ақпарат көздерінен алынған мағлұмат болғанын уәде етесіз.<br />
<strong>АВТОРЛЫҚ ҚҰҚЫҚПЕН ҚОРҒАУЛЫ АҚПАРАТТЫ РҰҚСАТСЫЗ ҚОСПАҢЫЗ!</strong>',
'copyrightwarning2'         => 'Есте тұрсын: барлық {{SITENAME}} жобасына берілген үлестер басқа улес берушілермен түзетуге, өзгертуге, не аластануға мүмкін. Алғыссыз түзетуге енжарлан болсаңыз, онда шығармаңызды мында жарияламаңыз.<br />
Тағы, осыны өзіңіз жазғаныңызды, не барша қазынасынан, немесе сондай-ақ ақысыз ашық қайнарынан көшіргеніңізді
дәл осындай бізге міндеттеме бересіз (көбірек ақпарат үшін $1 қужатын қараңыз).<br />
<strong>АУТОРЛЫҚ ҚҰҚЫҚПЕН ҚОРҒАУЛЫ АҚПАРАТТЫ РҰҚСАТСЫЗ ҚОСПАҢЫЗ!</strong>',
'longpagewarning'           => '<strong>НАЗАР САЛЫҢЫЗ: Бұл беттің мөлшері — $1 KB; кейбір
шолғыштарда бет мөлшері 32 KB жетсе не оны асса өңдеу күрделі болуы мүмкін.
Бетті бірнеше кішкін бөлімдерге бөліп көріңіз.</strong>',
'longpageerror'             => '<strong>ҚАТЕ: Жіберетін мәтініңіздін мөлшері — $1 KB, ең көбі $2 KB
рұқсат етілген мөлшерінен асқан. Бұл сақтай алынбайды.</strong>',
'readonlywarning'           => '<strong>НАЗАР САЛЫҢЫЗ: Дерекқор жөндету үшін құлыпталған,
сондықтан дәл қазір түзетуіңізді сақтай алмайсыз. Сосын қолдануға үшін мәтәніңізді көшіріп,
өз компүтеріңізде файлға сақтаңыз.</strong>',
'protectedpagewarning'      => '<strong>НАЗАР САЛЫҢЫЗ: Бұл бет қорғалған. Тек әкімші рұқсаты бар қатысушылар өңдеу жасай алады.</strong>',
'semiprotectedpagewarning'  => "'''Аңғартпа:''' Бет жартылай қорғалған, сондықтан осыны тек рұқсаты бар қатысушылар өңдей алады.",
'cascadeprotectedwarning'   => "'''Назар салыңыз''': Бұл бет құлыпталған, енді тек әкімші құқықтары бар пайдаланушылар бұны өңдей алады.Бұның себебі: бұл бет «баулы қорғауы» бар келесі {{PLURAL:$1|бетке|беттерге}} кіріктірілген:",
'templatesused'             => 'Бұл бетте қолданылған үлгілер:',
'templatesusedpreview'      => 'Бұны қарап шығуға қолданылған үлгілер:',
'templatesusedsection'      => 'Бұл бөлімде қолданылған үлгілер:',
'template-protected'        => '(қорғалған)',
'template-semiprotected'    => '(жартылай қорғалған)',
'edittools'                 => '<!-- Мындағы мағлұмат өңдеу және қотару үлгіттріңің астында көрсетіледі. -->',
'nocreatetitle'             => 'Бетті бастау шектелген',
'nocreatetext'              => 'Бұл торапта жаңа бет бастауы шектелген.
Кері қайтып бар бетті өңдеуіңізге болады, немесе [[{{ns:special}}:Userlogin|кіруіңізге не тіркелгі жасауға]] болады.',
'recreate-deleted-warn'     => "'''Аңғартпа: Бұрын жойылған бетті қайта бастайын деп тұрыңыз.'''

Бетті одан әрі өңдейін десеңіз тиісті мәліметтерің қарап шығуыңызға жөн.
Қолайлы болуы үшін бұл беттің жою журналы келтіріледі:",

# "Undo" feature
'undo-success' => 'Бұл өңдеудің болдырмауы атқарылады. Талабыңызды біліп тұрып алдын ала төмендегі салыстыруды тексеріп шығыңыз да, түзету болдырмауын бітіру үшін төмендегі өзгерістерді сақтаңыз.',
'undo-failure' => 'Бұл өңдеудің болдырмауы атқарылмайды, себебі: кедергі жасаған аралас түзетулер бар.',
'undo-summary' => '[[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|талқылауы]]) істеген $1 нұсқасын болдырмау',

# Account creation failure
'cantcreateaccounttitle' => 'Тіркелгі жасалмады',
'cantcreateaccounttext'  => 'Осы IP жайдан (<b>$1</b>) тіркелгі жасауы бұғатталған.
Бәлкім себебі, оқу орныңыздан, немесе Интернет жеткізушіден
үзбей бұзақылық болғаны.',

# History pages
'revhistory'          => 'Нұсқалар тарихы',
'viewpagelogs'        => 'Осы бетке қатысты журналдарды қарау',
'nohistory'           => 'Осы беттінің нұсқалар тарихы жоқ.',
'revnotfound'         => 'Нұсқа табылмады',
'revnotfoundtext'     => 'Осы сұранысқан беттің ескі нұсқасы табылған жоқ.
Осы бетті ашуға пайдаланған URL жайын қайта тексеріп шығыңыз.',
'loadhist'            => 'Бет тарихын жүктеуі',
'currentrev'          => 'Ағымдық нұсқасы',
'revisionasof'        => '$1 кезіндегі нұсқасы',
'revision-info'       => '$1 кезіндегі $2 жасаған нұсқасы',
'previousrevision'    => '← Ескілеу нұсқасы',
'nextrevision'        => 'Жаңалау нұсқасы →',
'currentrevisionlink' => 'Ағымдық нұсқасы',
'cur'                 => 'ағым.',
'next'                => 'кел.',
'last'                => 'соң.',
'orig'                => 'түп.',
'page_first'          => 'алғашқысына',
'page_last'           => 'соңғысына',
'histlegend'          => 'Айырмасын көру: салыстырамын деген нұсқаларды таңдап, не <Enter> пернесін, не төмендегі түймені басыңыз.<br />
Шартты белгілер: (ағым.) = ағымдық нұсқамен айырмасы,
(соң.) = алдыңғы нұсқамен айырмасы, ш = шағын түзету',
'deletedrev'          => '[жойылған]',
'histfirst'           => 'Ең алғашқысына',
'histlast'            => 'Ең соңғысына',
'historysize'         => '($1 байт)',
'historyempty'        => '(бос)',

# Revision feed
'history-feed-title'          => 'Нұсқа тарихы',
'history-feed-description'    => 'Мына уикидегі бұл беттің нұсқа тарихы',
'history-feed-item-nocomment' => '$2 кезіндегі $1 деген', # user at time
'history-feed-empty'          => 'Сұранысқан бет жоқ болды.
Ол мына уикиден жойылған, немесе атауы ауыстырылған.
Осыған қатысты жаңа беттерді [[{{ns:special}}:Search|бұл уикиден іздеп]] көріңіз.',

# Revision deletion
'rev-deleted-comment'         => '(мәндеме аластатылды)',
'rev-deleted-user'            => '(қатысушы аты аластатылды)',
'rev-deleted-event'           => '(жазба жойылды)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Осы беттің нұсқасы жария мұрағаттарынан аластатылған.
Бұл жайтқа [{{fullurl:{{ns:special}}:Log/delete|page={{FULLPAGENAMEE}}}} жою журналында] егжей-тегжей мәліметтері болуы мүмкін.
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Осы беттің нұсқасы жария мұрағаттарынан аластатылған.
Соны осы тораптың әкімшісі боп көруіңіз мүмкін;
бұл жайтқа [{{fullurl:{{ns:special}}:Log/delete|page={{FULLPAGENAMEE}}}} жою журналында] егжей-тегжей мәлметтері болуы мүмкін.
</div>',
'rev-delundel'                => 'көрсет/жасыр',
'revisiondelete'              => 'Нұсқаларды жою/қайтару',
'revdelete-nooldid-title'     => 'Нысана нұсқасы жоқ',
'revdelete-nooldid-text'      => 'Осы әрекетті орындау үшін ақырғы нұсқасынне нұсқаларын енгізбепсіз.',
'revdelete-selected'          => "'''$1:''' дегеннің {{PLURAL:$2|талғанылған нұсқасы|талғанылған нұсқалары}}:",
'logdelete-selected'          => "'''$1:''' дегеннің {{PLURAL:$2|талғанылған журнал жазбасы|талғанылған журнал жазбалары}}:",
'revdelete-text'              => 'Жойылған нұсқалар мен жазбаларды әлі де бет тарихында және журналдарда табуға болады,
бірақ олардың мағлұмат бөлшектері баршаға қатыналмайды.

Осы уикидің басқа әкімшілері жасырын мағлұматқа қатынай алады, және қосымша шектеу
ендірілгенше дейін, осы тілдесу арқылы жойылған мағлұматты кері қайтара алады.',
'revdelete-legend'            => 'Шектеулерді орнату:',
'revdelete-hide-text'         => 'Нұсқа мәтінін жасыр',
'revdelete-hide-name'         => 'Әрекет пен мақсатын жасыр',
'revdelete-hide-comment'      => 'Түзету мәндемесін жасыр',
'revdelete-hide-user'         => 'Өңдеуші атын (IP жайын) жасыр',
'revdelete-hide-restricted'   => 'Осы шектеулерді баршаға сияқты әкімшілерге де қолдану',
'revdelete-suppress'          => 'Әкімшілер жасаған мағлұматты басқаларша перделеу',
'revdelete-hide-image'        => 'Файл мағлұматын жасыр',
'revdelete-unsuppress'        => 'Қайтарылған нұсқалардан шектеулерді аластату',
'revdelete-log'               => 'Журнал мәндемесі:',
'revdelete-submit'            => 'Талғанған нұсқаға қолдану',
'revdelete-logentry'          => '[[$1]] дегеннің нұсқа көрінісін өзгертті',
'logdelete-logentry'          => '[[$1]] дегеннің жазба көрінісін өзгертті',
'revdelete-logaction'         => '{{PLURAL:$1|Нұсқаны|$1 нұсқаны}} $2 күйіне қойды',
'logdelete-logaction'         => '[[$3]] дегеннің {{PLURAL:$1|жазбасын|$1 жазбасын}} $2 күйіне қойды',
'revdelete-success'           => 'Нұсқа көрінісі сәтті қойылды.',
'logdelete-success'           => 'Жазба көрінісі сәтті қойылды.',

# Oversight log
'oversightlog'    => 'Нұсқа жасыру журналы',
'overlogpagetext' => 'Төменде әкімшілер жасырған мағлұматқа ықпал ететін жуықтағы болған жою және бұғаттау
тізімі беріледі. Ағымдағы амалды бұғаттау мен тиым үшін [[{{ns:special}}:Ipblocklist|IP бұғаттау тізімін]] қараңыз.',

# Diffs
'difference'                => '(Нұсқалар арасындағы айырмашылық)',
'loadingrev'                => 'айырма үшін нұсқа жүктеу',
'lineno'                    => 'Жол $1:',
'editcurrent'               => 'Осы беттің ағымдық нұсқасын өңдеу',
'selectnewerversionfordiff' => 'Салыстыру үшін жаңалау нұсқасын талғаңыз',
'selectolderversionfordiff' => 'Салыстыру үшін ескілеу нұсқасын талғаңыз',
'compareselectedversions'   => 'Таңдаған нұсқаларды салыстыру',
'editundo'                  => 'болдырмау',
'diff-multi'                => '(Арадағы {{PLURAL:$1|бір нұсқа|$1 нұсқа}} көрсетілмеді.)',

# Search results
'searchresults'         => 'Іздестіру нәтижелері',
'searchresulttext'      => '{{SITENAME}} жобасында іздестіру туралы көбірек ақпарат үшін, [[{{{{ns:mediawiki}}:helppage}}|{{int:help}}]] қараңыз.',
'searchsubtitle'        => "Іздестіру сұранысыңыз: '''[[:$1]]'''",
'searchsubtitleinvalid' => "Іздестіру сұранысыңыз: '''$1'''",
'badquery'              => 'Іздестіру сұраныс жарамсыз пішімделген',
'badquerytext'          => 'Ғафу етіңіз, сұранысыңызды орындай алмадық.
Бұл үш әріптен кем сөзді іздестіруге талаптанғаныңыздан
болуға мүмкін, ол әлі де сүйемелденбеген.
Тағы да бұл сөйлемді дұрыс енгізбегендіктен де болуға мүмкін,
мысалы, «балық және және қабыршақ».
Басқа сұраныс жасап көріңіз',
'matchtotals'           => '«$1» іздестіру сұранысы $2 беттің атауына
және $3 беттің мәтініне сәйкес.',
'noexactmatch'          => "'''Осында «$1» атаулы бет жоқ.''' Бұл бетті өзіңіз '''[[:$1|бастай  аласыз]].'''",
'titlematches'          => 'Бет атауы сәйкесі',
'notitlematches'        => 'Еш бет атауы сәйкес емес',
'textmatches'           => 'Бет мәтінің сәйкесі',
'notextmatches'         => 'Еш бет мәтіні сәйкес емес',
'prevn'                 => 'алдыңғы $1',
'nextn'                 => 'келесі $1',
'viewprevnext'          => 'Көрсетілуі: ($1) ($2) ($3) жазба.',
'showingresults'        => "Төменде нөмір '''$2''' орнынан бастап, жеткенше {{PLURAL:$1|'''1''' нәтиже|'''$1''' нәтиже}} көрсетілген.",
'showingresultsnum'     => "Төменде нөмір '''$2''' орнынан бастап {{PLURAL:$3|'''1''' нәтиже|'''$3''' нәтиже}} көрсетілген.",
'nonefound'             => "'''Аңғартпа''': Табу сәтсіз бітуі жиі «болған» және «деген» сияқты
тізімделмейтін жалпы сөздермен іздестіруден болуы мүмкін,
немесе бірден артық іздестіру шарт сөздерін егізгеннен (нәтижелерде тек
барлық шарт сөздер кедессе көрсетіледі) болуы мүмкін.",
'powersearch'           => 'Іздеу',
'powersearchtext'       => 'Мына есім аяларда іздеу:<br />$1<br />$2 Айдатуларды тізімдеу<br />Іздестіру сұранысы: $3 $9',
'searchdisabled'        => '{{SITENAME}} жобасында ішкі іздеуі өшірілген. Әзірше Google немесе Yahoo! арқылы іздеуге болады. Аңғартпа: {{SITENAME}} мағлұмат тізімідеулері оларда ескірген болуға мүмкін.',
'blanknamespace'        => '(Негізгі)',

# Preferences page
'preferences'              => 'Баптаулар',
'mypreferences'            => 'Баптауым',
'prefsnologin'             => 'Кірмегенсіз',
'prefsnologintext'         => 'Баптауларды қалау үшін алдын ала [[{{ns:special}}:Userlogin|кіруіңіз]] қажет.',
'prefsreset'               => 'Баптаулар арқаудан қайта орнатылды.',
'qbsettings'               => 'Мәзір аймағы',
'qbsettings-none'          => 'Ешқандай',
'qbsettings-fixedleft'     => 'Солға бекітілген',
'qbsettings-fixedright'    => 'Оңға бекітілген',
'qbsettings-floatingleft'  => 'Солға қалқыған',
'qbsettings-floatingright' => 'Оңға қалқыған',
'changepassword'           => 'Құпия сөзді ауыстыру',
'skin'                     => 'Безендіру',
'math'                     => 'Математика',
'dateformat'               => 'Күн-ай пішімі',
'datedefault'              => 'Еш қалаусыз',
'datetime'                 => 'Уақыт',
'math_failure'             => 'Өңдету сәтсіз бітті',
'math_unknown_error'       => 'белгісіз қате',
'math_unknown_function'    => 'белгісіз функция',
'math_lexing_error'        => 'лексика қатесі',
'math_syntax_error'        => 'синтаксис қатесі',
'math_image_error'         => 'PNG аударысы сәтсіз бітті; latex, dvips, gs және convert бағдарламаларының мүлтіксіз орнатуын тексеріңіз',
'math_bad_tmpdir'          => 'Математиканың уақытша қалтасына жазылмады, не қалта жасалмады',
'math_bad_output'          => 'Математиканың беріс қалтасына жазылмады, не қалта жасалмады',
'math_notexvc'             => 'texvc бағдарламасы жоғалтылған; баптау үшін math/README құжатын қараңыз.',
'prefs-personal'           => 'Жеке деректері',
'prefs-rc'                 => 'Жуықтағы өзгерістер',
'prefs-watchlist'          => 'Бақылау',
'prefs-watchlist-days'     => 'Бақылау тізімінде көрсетерін күн саны:',
'prefs-watchlist-edits'    => 'Кеңейтілген бақылау тізімі түзету көрсетерін саны:',
'prefs-misc'               => 'Қосымша',
'saveprefs'                => 'Сақта',
'resetprefs'               => 'Таста',
'oldpassword'              => 'Ағымдық құпия сөз:',
'newpassword'              => 'Жаңа құпия сөз:',
'retypenew'                => 'Жаңа құпия сөзді қайталаңыз:',
'textboxsize'              => 'Өңдеу',
'rows'                     => 'Жолдар:',
'columns'                  => 'Бағандар:',
'searchresultshead'        => 'Іздеу',
'resultsperpage'           => 'Бет сайын нәтиже саны:',
'contextlines'             => 'Нәтиже сайын жол саны:',
'contextchars'             => 'Жол сайын әріп саны:',
'stub-threshold'           => '<a href="#" class="stub">Бітеме сілтемесін</a> пішімдеу табалдырығы:',
'recentchangesdays'        => 'Жүықтағы өзгерістердегі көрсетілетін күндер:',
'recentchangescount'       => 'Жуықтағы өзгерістердегі көрсетілетін түзетулер:',
'savedprefs'               => 'Баптауларыңыз сақталды.',
'timezonelegend'           => 'Уақыт белдеуі',
'timezonetext'             => 'Жергілікті уақытыңызбен сервер уақытының (UTC) арасындағы сағат саны.',
'localtime'                => 'Жергілікті уақыт',
'timezoneoffset'           => 'Ығыстыру¹',
'servertime'               => 'Сервер уақыты',
'guesstimezone'            => 'Шолғыштан алып толтыру',
'allowemail'               => 'Басқадан хат қабылдауын ендіру',
'defaultns'                => 'Мына есім аяларда әдепкіден іздеу:',
'default'                  => 'әдепкі',
'files'                    => 'Файлдар',

# User rights
'userrights-lookup-user'     => 'Қатысушы топтарын меңгеру',
'userrights-user-editname'   => 'Қатысушы атын енгізіңіз:',
'editusergroup'              => 'Қатысушы топтарын өңдеу',
'userrights-editusergroup'   => 'Қатысушы топтарын өңдеу',
'saveusergroups'             => 'Қатысушы топтарын сақтау',
'userrights-groupsmember'    => 'Мүшелігі:',
'userrights-groupsavailable' => 'Қатынаулы топтар:',
'userrights-groupshelp'      => 'Қатысушыны үстейтін не аластатын топтарды талғаңыз.
Талғауы өшірілген топтар өзгертілімейді. Топтардың талғауын CTRL + Сол жақ нұқумен өшіруіңізге болады.',
'userrights-reason'          => 'Өзгерту себебі:',

# Groups
'group'            => 'Топ:',
'group-bot'        => 'Боттар',
'group-sysop'      => 'Әкімшілер',
'group-bureaucrat' => 'Төрешілер',
'group-all'        => '(барлығы)',

'group-bot-member'        => 'бот',
'group-sysop-member'      => 'әкімші',
'group-bureaucrat-member' => 'төреші',

'grouppage-bot'        => '{{ns:project}}:Боттар',
'grouppage-sysop'      => '{{ns:project}}:Әкімшілер',
'grouppage-bureaucrat' => '{{ns:project}}:Төрешілер',

# User rights log
'rightslog'      => 'Қатысушы_құқықтары_журналы',
'rightslogtext'  => 'Бұл пайдаланушылар құқықтарын өзгерту журналы.',
'rightslogentry' => ' $1 топ мүшелгін $2 дегеннен $3 дегенге өзгертті',
'rightsnone'     => '(ешқандай)',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|бір өзгеріс|$1 өзгеріс}}',
'recentchanges'                     => 'Жуықтағы өзгерістер',
'recentchangestext'                 => 'Бұл бетте осы уикидегі болған жуықтағы өзгерістер байқалады.',
'recentchanges-feed-description'    => 'Бұл арнаменен уикидегі ең соңғы өзгерістер қадағаланады.',
'rcnote'                            => "$3 кезіне дейін — төменде соңғы {{PLURAL:$2|күндегі|'''$2''' күндегі}}, соңғы {{PLURAL:$1|'''1''' өзгеріс|'''$1''' өзгеріс}} көрсетілген.",
'rcnotefrom'                        => '<b>$2</b> кезінен бері — төменде өзгерістер <b>$1</b> дейін көрсетілген.',
'rclistfrom'                        => '$1 кезінен бері — жаңа өзгерістерді көрсет.',
'rcshowhideminor'                   => 'Шағын түзетуді $1',
'rcshowhidebots'                    => 'Боттарды $1',
'rcshowhideliu'                     => 'Тіркелгенді $1',
'rcshowhideanons'                   => 'Тіркелгісізді $1',
'rcshowhidepatr'                    => 'Күзеттегі түзетулерді $1',
'rcshowhidemine'                    => 'Түзетуімді $1',
'rclinks'                           => 'Соңғы $2 күнде болған, соңғы $1 өзгерісті көрсет<br />$3',
'diff'                              => 'айырм.',
'hist'                              => 'тар.',
'hide'                              => 'жасыр',
'show'                              => 'көрсет',
'minoreditletter'                   => 'ш',
'newpageletter'                     => 'Ж',
'boteditletter'                     => 'б',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[бақылаған $1 қатысушы]',
'rc_categories'                     => 'Санаттарға шектеу ("|" белгісімен бөліктеңіз)',
'rc_categories_any'                 => 'Қайсыбір',

# Recent changes linked
'recentchangeslinked'          => 'Қатысты өзгерістер',
'recentchangeslinked-noresult' => 'Сілтеген беттерде айтылмыш мерзімде ешқандай өзгеріс болмаған.',
'recentchangeslinked-summary'  => "Бұл арнайы бетте сілтеген беттердегі жуықтағы өзгерістер тізімі беріледі. Бақылау тізіміңіздегі беттер '''жуан''' әрбімен белгіленеді.",

# Upload
'upload'                      => 'Файл қотару',
'uploadbtn'                   => 'Қотар!',
'reupload'                    => 'Қайталап қотару',
'reuploaddesc'                => 'Қотару үлгітіне оралу.',
'uploadnologin'               => 'Кірмегенсіз',
'uploadnologintext'           => 'Файл қотару үшін
[[{{ns:special}}:Userlogin|кіруіңіз]] қажет.',
'upload_directory_read_only'  => 'Қотару қалтасына ($1) жазуға веб-серверге рұқсат берілмеген.',
'uploaderror'                 => 'Қотару қатесі',
'uploadtext'                  => "Төмендегі үлгіт файл қотаруға қолданылады, алдындағы суреттерді қарау үшін не іздеу үшін [[{{ns:special}}:Imagelist|қотарылған файлдар тізіміне]] барыңыз, қотару мен жою тағы да [[{{ns:special}}:Log/upload|қотару журналына]] жазылып алынады.

Суреттерді бетке кіргізу үшін, файлға тура байланыстратын
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.png|балама мәтіні]]</nowiki>''' немесе
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' сілтеме пішімін қолданыңыз.",
'uploadlog'                   => 'қотару журналы',
'uploadlogpage'               => 'Қотару журналы',
'uploadlogpagetext'           => 'Төменде жуықтағы қотарылған файл тізімі.',
'filename'                    => 'Файл аты',
'filedesc'                    => 'Сипаттамасы',
'fileuploadsummary'           => 'Сипаттамасы:',
'filestatus'                  => 'Ауторлық құқықтары күйі',
'filesource'                  => 'Файл қайнары',
'uploadedfiles'               => 'Қотарылған файлдар',
'ignorewarning'               => 'Назар салуды елемеу және файлды әрдеқашан сақтау.',
'ignorewarnings'              => 'Әрқайсы назар салуларды елемеу',
'minlength'                   => 'Файл атында ең кемінде үш әріп болуы керек.',
'illegalfilename'             => '«$1» файл атауында бет атауларында рұқсат етілмеген нышандар бар. Файлды қайта атаңыз, сосын қайта жуктеп көріңіз.',
'badfilename'                 => 'Файлдың аты «$1» боп өзгертілді.',
'filetype-badmime'            => '«$1» деген MIME түрі бар файлдарды қотаруға рұқсат етілмейді.',
'filetype-badtype'            => "'''«.$1»''' деген күтілмеген файл түрі
: Рүқсат етілген файл түр тізімі: $2",
'filetype-missing'            => 'Бұл файлдың («.jpg» сияқты) кеңейтімі жоқ.',
'large-file'                  => 'Файлды $1 мөлшерден аспауына тырысыңыз; бұл файл мөлшері — $2.',
'largefileserver'             => 'Осы файлдың мөлшері сервердің қалауынан асып кеткен.',
'emptyfile'                   => 'Қотарылған файлыңыз бос сияқты. Бұл файл атауында қате болуы мүмкін. Осы файлды шынайы қотарғыңыз келетін тексеріп шығыңыз.',
'fileexists'                  => 'Осындай атаулы файл бар түге, егер бұны өзгертуге сеніміңіз жоқ болса <strong><tt>$1</tt></strong> дегенді тексеріп шығыңыз.',
'fileexists-extension'        => 'Ұқсасты файл атауы бар түге:<br />
Қотарылатын файл атауы: <strong><tt>$1</tt></strong><br />
Бар болған файл атауы: <strong><tt>$2</tt></strong><br />
Басқа атау таңдаңыз.',
'fileexists-thumb'            => "'''<center>Бар болған сурет</center>'''",
'fileexists-thumbnail-yes'    => 'Осы файл — мөлшері кішірітілген сурет <i>(нобай)</i> сияқты. Бұл <strong><tt>$1</tt></strong> деген файлды сынап шығыңыз.<br />
Егер сыналған файл түпнұсқалы мөлшері бар дәлме-дәл сурет болса, қосысмша нобайды қотару қажеті жоқ.',
'file-thumbnail-no'           => 'Файл атауы <strong><tt>$1</tt></strong> дегенмен басталады. Бұл — мөлшері кішірітілген сурет <i>(нобай)</i> сияқты.
Егер толық ажыратылымдығы бар суретіңіз болса, соны қотарыңыз, әйтпесе файл атауын өзгертіңіз.',
'fileexists-forbidden'        => 'Осындай атаулы файл бар түге. Кері қайтыңыз да, және осы файлды басқа атымен қотарыңыз. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Осындай атаулы файл ортақ файл арқауында бар түге. Кері қайтыңыз да, осы файлды жаңа атымен қотарыңыз. [[{{ns:image}}:$1|thumb|center|$1]]',
'successfulupload'            => 'Қотару сәтті өтті',
'fileuploaded'                => '«$1» файлы сәтті қотарылды!
Осы сілтемеге еріп — $2, сипаттама бетіне барыңыз да, және осы файл туралы
ақпарат толтырыңыз: қайдан алынғанын, қашан жасалғанын, кім жасағанын,
тағы басқа білетіңізді. Бұл сурет болса, мынадай пішімімен кірістіруге болады: <tt><nowiki>[[</nowiki>{{ns:image}}<nowiki>:$1|thumb|Сипаттамасы]]</nowiki></tt>',
'uploadwarning'               => 'Қотару туралы назар салу',
'savefile'                    => 'Файлды сақтау',
'uploadedimage'               => '«[[$1]]» файлын қотарды',
'uploaddisabled'              => 'Файл қотаруы өшірілген',
'uploaddisabledtext'          => 'Осы уикиде файл қотаруы өшірілген.',
'uploadscripted'              => 'Осы файлда, веб шолғышты ағат түсіндікке келтіретің HTML белгілеу, не скрипт коды бар.',
'uploadcorrupt'               => 'Осы файл бүлдірілген, не әдепсіз кеңейтімі бар. Файлды тексеріп, қотаруын қайталаңыз.',
'uploadvirus'                 => 'Осы файлда вирус болуы мүмкін! Егжей-тегжей ақпараты: $1',
'sourcefilename'              => 'Қайнардағы файл аты',
'destfilename'                => 'Ақырғы файл аты',
'watchthisupload'             => 'Осы бетті бақылау',
'filewasdeleted'              => 'Осы атауы бар файл бұрын қотарылған, сосын жойылдырылған. Қайта қотару алдынан $1 дегенді тексеріңіз.',

'upload-proto-error'      => 'Жарамсыз хаттамалық',
'upload-proto-error-text' => 'Сырттан қотару үшін URL жайлары <code>http://</code> немесе <code>ftp://</code> дегендерден басталу қажет.',
'upload-file-error'       => 'Ішкі қате',
'upload-file-error-text'  => 'Серверде уақытша файл жасауы ішкі қатеге ұшырасты. Бұл жүйенің әкімшімен қатынасыңыз.',
'upload-misc-error'       => 'Белгісіз қотару қатесі',
'upload-misc-error-text'  => 'Қотару кезінде белгісіз қате ұшырасты. Қайсы URL жайы жарамды және қатынаулы екенін тексеріп шығыңыз да қайталап көріңіз. Егер бұл мәселе әлде де қалса, жүйе әкімшімен қатынасыңыз.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL жайы жетілмеді',
'upload-curl-error6-text'  => 'Берілген URL жайы жетілмеді. Қайсы URL жайы дұрыс екенін және торап істе екенін қайталап қатаң тексеріңіз.',
'upload-curl-error28'      => 'Қотаруға берілген уақыт бітті',
'upload-curl-error28-text' => 'Тораптың жауап беруі тым ұзақ уақытқа созылды. Бұл торап істе екенін тексеріп шығыңыз, аз уақыт кідіре тұрыңыз да тағы қайталап көріңіз. Талабыңызды жүктелуі аздау кезінде қайталауға болмыс.',

'license'            => 'Лицензиясы',
'nolicense'          => 'Ештеңе талғанбаған',
'upload_source_url'  => ' (жарамды, баршаға қатынаулы URL жай)',
'upload_source_file' => ' (компьютеріңіздегі файл)',

# Image list
'imagelist'                 => 'Файл тізімі',
'imagelisttext'             => "Төменде ''$2'' сұрыпталған '''$1''' файл тізімі.",
'imagelistforuser'          => 'Мында тек $1 жүктеген суреттер көрсетіледі.',
'getimagelist'              => 'файл тізімдеуі',
'ilsubmit'                  => 'Іздеу',
'showlast'                  => 'Соңғы $1 файл $2 сұрыптап көрсет.',
'byname'                    => 'атымен',
'bydate'                    => 'күн-аймен',
'bysize'                    => 'мөлшерімен',
'imgdelete'                 => 'жою',
'imgdesc'                   => 'сипп.',
'imgfile'                   => 'файл',
'imglegend'                 => 'Шартты белгілер: (сипп.) — файл сипаттамасын көрсету/өңдеу.',
'imghistory'                => 'Файл тарихы',
'revertimg'                 => 'қайт.',
'deleteimg'                 => 'жою',
'deleteimgcompletely'       => 'Осы файлдың барлық нұсқаларын жой',
'imghistlegend'             => 'Шартты белгілер: (ағым.) = ағымдық файл, (жою) = ескі нұсқасын
жою, (қай.) = ескі нұсқасына қайтару.
<br /><i>Қотарылған файлды көру үшін күн-айына нұқыңыз</i>.',
'imagelinks'                => 'Сілтемелері',
'linkstoimage'              => 'Бұл файлға келесі беттер сілтейді:',
'nolinkstoimage'            => 'Бұл файлға еш бет сілтемейді.',
'sharedupload'              => 'Бұл файл ортақ арқауына қотарылған сондықтан басқа жобаларда қолдануы мүмкін.',
'shareduploadwiki'          => 'Былайғы ақпарат үшін $1 дегенді қараңыз.',
'shareduploadwiki-linktext' => 'файл сипаттамасы беті',
'noimage'                   => 'Мынадай атаулы файл жоқ, $1 мүмкіндігіңіз бар.',
'noimage-linktext'          => 'осыны қотару',
'uploadnewversion-linktext' => 'Бұл файлдың жаңа нұсқасын қотару',
'imagelist_date'            => 'Күн-айы',
'imagelist_name'            => 'Атауы',
'imagelist_user'            => 'Қатысушы',
'imagelist_size'            => 'Мөлшері',
'imagelist_description'     => 'Сипаттамасы',
'imagelist_search_for'      => 'Суретті атымен іздеу:',

# MIME search
'mimesearch'         => 'Файлды MIME түрімен іздеу',
'mimesearch-summary' => 'Бұл бет файлдарды MIME түрімен сүзгілеу мүмкіндігін береді. Кірісі: «мағлұмат түрі»/«түр тарауы», мысалы <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME түрі:',
'download'           => 'жүктеу',

# Unwatched pages
'unwatchedpages' => 'Бақыланылмаған беттер',

# List redirects
'listredirects' => 'Айдату бет тізімі',

# Unused templates
'unusedtemplates'     => 'Пайдаланылмаған үлгілер',
'unusedtemplatestext' => 'Бұл бет басқа бетке кіріcтірілмеген үлгі есім аяысындағы барлық беттерді тізімдейді. Үлгілерді жою алдынан бұның басқа сілтемелерін тексеріп шығуын ұмытпаңыз',
'unusedtemplateswlh'  => 'басқа сілтемелер',

# Random redirect
'randomredirect'         => 'Кездейсоқ айдату',
'randomredirect-nopages' => 'Бұл есім аясында еш айдату жоқ.',

# Statistics
'statistics'             => 'Жоба санағы',
'sitestats'              => '{{SITENAME}} санағы',
'userstats'              => 'Қатысушы санағы',
'sitestatstext'          => "Дерекқорда {{PLURAL:$1|'''1''' бет|бұлайша '''$1''' бет}} бар.
Бұның ішінде: «талқылау» беттері, {{SITENAME}} жобасы туралы беттер, ең аз «бітеме»
беттері, айдатулар, тағы да басқа мағлұмат деп танылмайтын беттер болуы мүмкін .
Соларды есептен шығарғанда, мында мағлұмат деп саналатын
{{PLURAL:$2|'''1'''|'''$2'''}} бет бар шығар.

Қотарылған {{PLURAL:$8|'''1''' файл|'''$8''' файл}} сақталады.

{{SITENAME}} жобасы орнатылғаннан бері беттер {{PLURAL:$3|'''1''' рет|бұлайша '''$3''' рет}} қаралған,
және беттер {{PLURAL:$4|'''1''' рет|'''$4''' рет}} түзетілген.
Бұның нәтижесінде орта есеппен әрбір бетке '''$5''' рет түзету келеді, және әрбір түзетуге '''$6''' рет қарау келеді.

Ағымдық [http://meta.wikimedia.org/wiki/Help:Job_queue тапсырым кезегі] ұзындылығы: '''$7'''.",
'userstatstext'          => "Мында {{PLURAL:$1|'''1''' тіркелген қатысушы|'''$1''' тіркелген қатысушы}} бар, соның ішінде
{{PLURAL:$2|'''1''' қатысушыда|'''$2''' қатысушыда}} (немесе '''$4 %''') $5 құқықтары бар",
'statistics-mostpopular' => 'Ең көп қаралған беттер',

'disambiguations'      => 'Айрықты беттер',
'disambiguationspage'  => '{{ns:template}}:Disambig',
'disambiguations-text' => "Келесі беттер '''айрықты бетке''' сілтейді. Бұның орнына белгілі тақырыпқа сілтеуі қажет.<br />Егер [[{{ns:mediawiki}}:disambiguationspage]] тізіміндегі үлгі қолданылса, бет айрықты деп саналады.",

'doubleredirects'     => 'Шынжырлы айдатулар',
'doubleredirectstext' => 'Әрбір жолдағы бірінші мен екінші айдату сілтемелері бар, сонымен бірге екінші айдату мәтіннің бірінші жолы бар. Әдетте бірінші сілтеме айдайтын «шын» ақырғы беттің атауы болуы қажет.',

'brokenredirects'        => 'Еш бетке келтірмейтін айдатулар',
'brokenredirectstext'    => 'Келесі айдатулар жоқ беттерге сілтейді:',
'brokenredirects-edit'   => '(өңдеу)',
'brokenredirects-delete' => '(жою)',

'withoutinterwiki'        => 'Еш тілге сілтeмеген беттер',
'withoutinterwiki-header' => 'Келесі беттер басқа тілдерге сілтемейді:',

'fewestrevisions' => 'Ең аз түзетілген беттер',

# Miscellaneous special pages
'nbytes'                  => '$1 байт',
'ncategories'             => '$1 санат',
'nlinks'                  => '$1 сілтеме',
'nmembers'                => '$1 буын',
'nrevisions'              => '$1 нұсқа',
'nviews'                  => '$1 рет қаралған',
'specialpage-empty'       => 'Бұл баянатқа еш нәтиже жоқ.',
'lonelypages'             => 'Еш бет сілтемеген беттер',
'lonelypagestext'         => 'Келесі беттерге осы жобадағы басқа беттер сілтемейді.',
'uncategorizedpages'      => 'Еш санатқа кірмеген беттер',
'uncategorizedcategories' => 'Еш санатқа кірмеген санаттар',
'uncategorizedimages'     => 'Еш санатқа кірмеген суреттер',
'uncategorizedtemplates'  => 'Еш санатқа кірмеген үлгілер',
'unusedcategories'        => 'Пайдаланылмаған санаттар',
'unusedimages'            => 'Пайдаланылмаған файлдар',
'popularpages'            => 'Әйгілі беттер',
'wantedcategories'        => 'Басталмаған санаттар',
'wantedpages'             => 'Басталмаған беттер',
'mostlinked'              => 'Ең көп сілтенген беттер',
'mostlinkedcategories'    => 'Ең көп сілтенген санаттар',
'mostlinkedtemplates'     => 'Ең көп сілтенген үлгілер',
'mostcategories'          => 'Ең көп санаттарға кірген беттер',
'mostimages'              => 'Ең көп сілтенген суреттер',
'mostrevisions'           => 'Ең көп түзетілген беттер',
'allpages'                => 'Барлық бет тізімі',
'prefixindex'             => 'Бет бастау тізімі',
'randompage'              => 'Кездейсоқ бет',
'randompage-nopages'      => 'Бұл есім аясында беттер жоқ.',
'shortpages'              => 'Ең қысқа беттер',
'longpages'               => 'Ең үлкен беттер',
'deadendpages'            => 'Еш бетке сілтемейтін беттер',
'deadendpagestext'        => 'Келесі беттер осы жобадағы басқа беттерге сілтемейді.',
'protectedpages'          => 'Қорғалған беттер',
'protectedpagestext'      => 'Келесі беттер өңдеуден немесе жылжытудан қорғалған',
'protectedpagesempty'     => 'Ағымда осындай баптауларымен ешбір бет қорғалмаған',
'listusers'               => 'Барлық қатысушы тізімі',
'specialpages'            => 'Арнайы беттер',
'spheading'               => 'Баршаның арнайы беттері',
'restrictedpheading'      => 'Шектеулі арнайы беттер',
'rclsub'                  => '(«$1» бетінен сілтенген беттерге)',
'newpages'                => 'Ең жаңа беттер',
'newpages-username'       => 'Қатысушы аты:',
'ancientpages'            => 'Ең ескі беттер',
'intl'                    => 'Тіларалық сілтемелер',
'move'                    => 'Жылжыту',
'movethispage'            => 'Бетті жылжыту',
'unusedimagestext'        => '<p>Ескерту: Басқа веб тораптар файлдың
URL жайына тікелей сілтеуі мүмкін. Сондықтан, белсенді пайдалануына аңғармай,
осы тізімде қалуы мүмкін.</p>',
'unusedcategoriestext'    => 'Келесі санат беттер бар болып тұр, бірақ оған ешқандай бет, не санат кірмейді.',

# Book sources
'booksources'               => 'Кітап қайнарлары',
'booksources-search-legend' => 'Кітап қайнарларын іздеу',
'booksources-isbn'          => 'ISBN белгісі:',
'booksources-go'            => 'Өту',
'booksources-text'          => 'Төменде жаңа және қолданған кітаптар сататынтораптарының сілтемелері тізімделген.
Бұл тораптарда ізделген кітаптар туралы былайғы ақпарат болуға мүмкін.',

'categoriespagetext' => 'Осында уикидегі барлық санаттарының тізімі беріліп тұр.',
'data'               => 'Деректер',
'userrights'         => 'Қатысушылар құқықтарын меңгеру',
'groups'             => 'Қатысушы топтары',
'isbn'               => 'ISBN',
'alphaindexline'     => '$1 — $2',
'version'            => 'Жүйе нұсқасы',

# Special:Log
'specialloguserlabel'  => 'Қатысушы:',
'speciallogtitlelabel' => 'Атау:',
'log'                  => 'Журналдар',
'log-search-legend'    => 'Журналдардан іздеу',
'log-search-submit'    => 'Өту',
'alllogstext'          => '{{SITENAME}} жобасының барлық қатынаулы журналдарын біріктіріп көрсетуі.
Журнал түрін, қатысушы атын, не тиісті бетін талғап, тарылтып қарауыңызға болады.',
'logempty'             => 'Журналда сәйкес даналар жоқ.',
'log-title-wildcard'   => 'Мынадай мәтіннең басталытын атаулардан іздеу',

# Special:Allpages
'nextpage'          => 'Келесі бетке ($1)',
'prevpage'          => 'Алдыңғы бетке ($1)',
'allpagesfrom'      => 'Мына беттен бастап көрсету:',
'allarticles'       => 'Барлық бет тізімі',
'allinnamespace'    => 'Барлық бет ($1 есім аясы)',
'allnotinnamespace' => 'Барлық бет ($1 есім аясынан тыс)',
'allpagesprev'      => 'Алдыңғыға',
'allpagesnext'      => 'Келесіге',
'allpagessubmit'    => 'Өту',
'allpagesprefix'    => 'Мынадан басталған беттерді көрсету:',
'allpagesbadtitle'  => 'Алынған бет атауы жарамсыз болған, немесе тіл-аралық не уики-аралық бастауы бар болды. Атауда қолдануға болмайтын нышандар болуы мүмкін.',

# Special:Listusers
'listusersfrom'      => 'Мына қатысушыдан бастап көрсету:',
'listusers-submit'   => 'Көрсет',
'listusers-noresult' => 'Қатысушы табылған жоқ.',

# E-mail user
'mailnologin'     => 'Е-пошта жайы жіберілген жоқ',
'mailnologintext' => 'Басқа қатысушыға хат жіберу үшін
[[{{ns:special}}:Userlogin|кіруіңіз]] қажет, және [[{{ns:special}}:Preferences|баптауыңызда]]
куәландырылған е-пошта жайы болуы жөн.',
'emailuser'       => 'Қатысушыға хат жазу',
'emailpage'       => 'Қатысушыға хат жіберу',
'emailpagetext'   => 'Егер бұл қатысушы баптауларында куәландырған е-пошта
жайын енгізсе, төмендегі үлгіт арқылы бұған жалғыз е-пошта хатын жіберуге болады.
Қатысушы баптауыңызда енгізген е-пошта жайыңыз
«Кімнен» деген бас жолағында көрінеді, сондықтан
хат алушысы тура жауап бере алады.',
'usermailererror' => 'Mail нысаны қате қайтарды:',
'defemailsubject' => '{{SITENAME}} е-поштасының хаты',
'noemailtitle'    => 'Бұл е-пошта жайы емес',
'noemailtext'     => 'Осы қатысушы жарамды Е-пошта жайын енгізбеген,
немесе басқалардан хат қабылдауын өшірген.',
'emailfrom'       => 'Кімнен',
'emailto'         => 'Кімге',
'emailsubject'    => 'Тақырыбы',
'emailmessage'    => 'Хат',
'emailsend'       => 'Жіберу',
'emailccme'       => 'Хатымдың көшірмесін маған да жібер.',
'emailccsubject'  => '$1 дегенге жіберілген хатыңыздың көшірмесі: $2',
'emailsent'       => 'Хат жіберілді',
'emailsenttext'   => 'Е-пошта хатыңыз жіберілді.',

# Watchlist
'watchlist'            => 'Бақылау тізімі',
'mywatchlist'          => 'Бақылауым',
'watchlistfor'         => "('''$1''' бақылаулары)",
'nowatchlist'          => 'Бақылау тізіміңізде ешбір дана жоқ',
'watchlistanontext'    => 'Бақылау тізіміңіздегі даналарды қарау, не өңдеу үшін $1 қажет.',
'watchlistcount'       => "'''Бақылау тізіміңізде (талқылау беттерді қоса) $1 дана бар.'''",
'clearwatchlist'       => 'Бақылау тізімін тазалау',
'watchlistcleartext'   => 'Соларды толық аластатуға батылсыз ба?',
'watchlistclearbutton' => 'Бақылау тізімін тазалау',
'watchlistcleardone'   => 'Бақылау тізіміңіз тазартылды. $1 дана аластатылды.',
'watchnologin'         => 'Кірмегенсіз',
'watchnologintext'     => 'Бақылау тізіміңізді өзгерту үшін [[{{ns:special}}:Userlogin|кіруіңіз]] жөн.',
'addedwatch'           => 'Бақылау тізіміне қосылды',
'addedwatchtext'       => "«[[:$1]]» беті [[{{ns:special}}:Watchlist|бақылау тізіміңізге]] қосылды.
Осы беттің және соның талқылау бетінің келешектегі өзгерістері мында тізімделеді.
Сонда беттің атауы табуға жеңілдетіп [[{{ns:special}}:Recentchanges|жуықтағы өзгерістер тізімінде]]
'''жуан әрпімен''' көрсетіледі.

Осы бетті соңынан бақылау тізімнен аластатыңыз келсе «Бақыламау» парағын нұқыңыз.",
'removedwatch'         => 'Бақылау тізіміңізден аластатылды',
'removedwatchtext'     => '«[[:$1]]» беті бақылау тізіміңізден аластатылды.',
'watch'                => 'Бақылау',
'watchthispage'        => 'Бетті бақылау',
'unwatch'              => 'Бақыламау',
'unwatchthispage'      => 'Бақылауды тоқтату',
'notanarticle'         => 'Мағлұмат беті емес',
'watchnochange'        => 'Көрсетілген мерзімде ешбір бақыланған дана өңделген жоқ.',
'watchdetails'         => "* Бақылау тізімінде (талқылау беттерісіз) '''$1''' бет бар.
* [[{{ns:special}}:Watchlist/edit|Бүкіл тізімді қарау және өзгерту]].
* [[{{ns:special}}:Watchlist/clear|Тізімдегі барлық дана аластату]].",
'wlheader-enotif'      => '* Ескерту хат жіберуі ендірілген.',
'wlheader-showupdated' => "* Соңғы кіргенімнен бері өзгертілген беттерді '''жуан''' әрбімен көрсет",
'watchmethod-recent'   => 'бақылаулы беттердің жуықтағы өзгерістерін тексеру',
'watchmethod-list'     => 'жуықтағы өзгерістерде бақылаулы беттерді тексеру',
'removechecked'        => 'Белгіленгенді бақылау тізімінен аластату',
'watchlistcontains'    => 'Бақылау тізіміңізде {{PLURAL:$1|1 бет|$1 бет}} бар.',
'watcheditlist'        => "Осында әліппем сұрыпталған бақыланған мағлұмат беттеріңіз тізімделінген.
Беттерді аластату үшін оның қасындағы қабашақтарды белгілеп, төмендегі ''Белгіленгенді аластат'' түймесін нұқыңыз
(мағлұмат бетін жойғанда талқылау беті де бірге жойылады).",
'removingchecked'      => 'Сұранған даналарды бақылау тізімнен аластауы…',
'couldntremove'        => '«$1» деген дана аластатылмады…',
'iteminvalidname'      => '«$1» данасының жарамсыз атауынан шатақ туды…',
'wlnote'               => "Төменде соңғы {{PLURAL:$2|сағатта|'''$2''' сағатта}} болған, {{PLURAL:$1|жуықтағы өзгеріс|жуықтағы '''$1''' өзгеріс}} көрсетілген.",
'wlshowlast'           => 'Соңғы $1 сағаттағы, $2 күндегі, $3 болған өзгерісті көрсету',
'wlsaved'              => 'Бұл бақылу тізіміңіздің сақталған нұсқасы.',
'watchlist-show-bots'  => 'Боттарды көрсет',
'watchlist-hide-bots'  => 'Боттарды жасыр',
'watchlist-show-own'   => 'Түзетуімді көрсет',
'watchlist-hide-own'   => 'Түзетуімді жасыр',
'watchlist-show-minor' => 'Шағын түзетуді көрсет',
'watchlist-hide-minor' => 'Шағын түзетуді жасыр',
'wldone'               => 'Іс бітті.',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Бақылау…',
'unwatching' => 'Бақыламау…',

'enotif_mailer'                => '{{SITENAME}} ескерту хат жіберу қызметі',
'enotif_reset'                 => 'Барлық бет каралді деп белгіле',
'enotif_newpagetext'           => 'Мынау жаңа бет.',
'enotif_impersonal_salutation' => '{{SITENAME}} пайдаланушысы',
'changed'                      => 'өзгертті',
'created'                      => 'жасады',
'enotif_subject'               => '{{SITENAME}} жобасында $PAGEEDITOR $PAGETITLE атаулы бетті $CHANGEDORCREATED',
'enotif_lastvisited'           => 'Соңғы кіруіңізден бері болған өзгерістер үшін $1 дегенді қараңыз.',
'enotif_lastdiff'              => 'Осы өзгеріс үшін $1 дегенді қараңыз.',
'enotif_anon_editor'           => 'тіркелгісіз пайдаланушы $1',
'enotif_body'                  => 'Құрметті $WATCHINGUSERNAME,

{{SITENAME}} жобасының $PAGETITLE атаулы бетті $PAGEEDITDATE кезінде $PAGEEDITOR деген $CHANGEDORCREATED, ағымдық нұсқасын $PAGETITLE_URL жайынан қараңыз.

$NEWPAGE

Өңдеуші сипаттамасы: $PAGESUMMARY $PAGEMINOREDIT

Өңдеушімен қатынасу:
е-пошта: $PAGEEDITOR_EMAIL
уики: $PAGEEDITOR_WIKI

Былайғы өзгерістер болғанда да сіз осы бетке барғанша дейін ешқандай басқа ескерту хаттар жіберілмейді. Сонымен қатар бақылау тізіміңіздегі бет ескертпелік белгісін әдепке күйіне келтіріңіз.

             Сіздің досты {{SITENAME}} ескерту қызметі

----
Бақылау тізіміңізді баптау үшін, мында барыңыз
{{fullurl:{{ns:special}}:Watchlist/edit}}

Сын-пікір беру және былайғы жәрдем алу үшін:
{{fullurl:{{{{ns:mediawiki}}:helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Бетті жою',
'confirm'                     => 'Растау',
'excontent'                   => 'болған мағлұматы: «$1»',
'excontentauthor'             => 'болған мағлұматы (тек «[[{{ns:special}}:Contributions/$2|$2]]» үлесі): «$1»',
'exbeforeblank'               => 'тазарту алдындағы болған мағлұматы: «$1»',
'exblank'                     => 'бет босты болды',
'confirmdelete'               => 'Жоюды растау',
'deletesub'                   => '(«$1» жоюы)',
'historywarning'              => 'Назар салыңыз: Жоюға арналған бетте өз тарихы бар:',
'confirmdeletetext'           => 'Бетті немесе суретті барлық тарихымен
бірге дерекқордан әрдайым жойығыңыз келетін сияқты.
Бұны жоюдың зардабын түсініп шын ниеттенгеніңізді, және
[[{{{{ns:mediawiki}}:policy-url}}]] дегенге лайықты деп
сенгеніңізді растаңыз.',
'actioncomplete'              => 'Әрекет бітті',
'deletedtext'                 => '«$1» жойылды.
Жуықтағы жоюлар туралы жазбаларын $2 дегеннен қараңыз.',
'deletedarticle'              => '«[[$1]]» бетін жойды',
'dellogpage'                  => 'Жою_журналы',
'dellogpagetext'              => 'Төменде жуықтағы жоюлардың тізімі берілген.',
'deletionlog'                 => 'жою журналы',
'reverted'                    => 'Ертерек нұсқасына қайтарылған',
'deletecomment'               => 'Жоюдың себебі',
'imagereverted'               => 'Ертерек нұсқасына қайтару сәтті өтті.',
'rollback'                    => 'Түзетулерді қайтару',
'rollback_short'              => 'Қайтару',
'rollbacklink'                => 'қайтару',
'rollbackfailed'              => 'Қайтару сәтсіз аяқталды',
'cantrollback'                => 'Түзету қайтарылмайды. Бұл беттің соңғы үлескері тек бастауыш ауторы.',
'alreadyrolled'               => '[[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|талқылауы]]) жасаған [[:$1]]
дегеннің соңғы өңдеуі қайтарылмады; кейбіреу осы қазір бетті өңдеп не қайтарып жатыр түге.

Соңғы өңдеуді [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|талқылауы]]) дегенді жасаған.',
'editcomment'                 => 'Түзетудің болған мәндемесі: «<i>$1</i>».', # only shown if there is an edit comment
'revertpage'                  => '[[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|талқылауы]]) түзетулерін [[{{ns:user}}:$1|$1]] соңғы нұсқасына қайтарды',
'sessionfailure'              => 'Кіру сессиясында шатақ болған сияқты;
сессияға шабуылдаудардан қорғану үшін, осы әрекет тоқтатылды.
«Артқа» түймесін басыңыз, және бетті кері жүктеңіз, сосын қайталап көріңіз.',
'protectlogpage'              => 'Қорғау_журналы',
'protectlogtext'              => 'Төменде беттердің қорғау/қорғамау тізімі берілген. Ағымдағы қорғау әректтер бар беттер үшін [[{{ns:special}}:Protectedpages|қорғалған бет тізімін]] қараңыз.',
'protectedarticle'            => '«[[$1]]» қорғалды',
'modifiedarticleprotection'   => '«[[$1]]» дегеннің қорғалу деңгейі өзгерді',
'unprotectedarticle'          => '«[[$1]]» қорғалмады',
'protectsub'                  => '(«$1» қорғауда)',
'confirmprotect'              => 'Қорғауды растау',
'protectcomment'              => 'Мәндемесі:',
'protectexpiry'               => 'Бітетін мерзімі:',
'protect_expiry_invalid'      => 'Бітетін уақыты жарамсыз.',
'protect_expiry_old'          => 'Бітетін уақыты өтіп кеткен.',
'unprotectsub'                => '(«$1» қорғамауда)',
'protect-unchain'             => 'Жылжытуға рұқсат беру',
'protect-text'                => '<strong>$1</strong> бетінің қорғау деңгейін қарай және өзгерте аласыз.',
'protect-locked-blocked'      => 'Бұғаттауыңыз өшірілгенше дейін қорғау деңгейін өзгерте алмайсыз.
Мына <strong>$1</strong> беттің ағымдық баптаулары:',
'protect-locked-dblock'       => 'Дерекқордың құлыптауы белсенді болғандықтан қорғау деңгейлері өзгертілмейді.
Мына <strong>$1</strong> беттің ағымдық баптаулары:',
'protect-locked-access'       => 'Тіркелгіңізге бет қорғау денгейлерін өзгертуіне рұқсат жоқ.
Мына <strong>$1</strong> беттің ағымдық баптаулары:',
'protect-cascadeon'           => 'Бұл бет ағымда қорғалған, себебі: осы бет баулы қорғауы бар келесі {{PLURAL:$1|бетке|беттерге}} кірістірілген. Бұл беттің қорғау деңгейін өзгерте аласыз, бірақ бұл баулы қорғауға ықпал етпейді.',
'protect-default'             => '(әдепкі)',
'protect-level-autoconfirmed' => 'Тіркелгісіз пайдаланушыларға тиым',
'protect-level-sysop'         => 'Тек әкімшілерге рұқсат',
'protect-summary-cascade'     => 'баулы',
'protect-expiring'            => 'бітуі: $1 (UTC)',
'protect-cascade'             => 'Бұл бетке кіріктірілген беттерді қорғау (баулы қорғау).',
'restriction-type'            => 'Рұқсаты:',
'restriction-level'           => 'Рұқсат шектеу деңгейі:',
'minimum-size'                => 'Ең аз мөлшері',
'maximum-size'                => 'Ең көп мөлшері',
'pagesize'                    => '(байт)',

# Restrictions (nouns)
'restriction-edit' => 'Өңдеу',
'restriction-move' => 'Жылжыту',

# Restriction levels
'restriction-level-sysop'         => 'толық қорғалған',
'restriction-level-autoconfirmed' => 'жартылай қорғалған',
'restriction-level-all'           => 'әрқайсы деңгейде',

# Undelete
'undelete'                 => 'Жойылған беттерді қарау',
'undeletepage'             => 'Жойылған беттерді қарау және қайтару',
'viewdeletedpage'          => 'Жойылған беттерді қарау',
'undeletepagetext'         => 'Келесі беттер жойылды деп белгіленген, бірақ мағлұматы мұрағатта жатқан,
сондықтан кері қайтаруға әзір. Мұрағат мерзім бойынша тазаланып тұруы мүмкін.',
'undeleteextrahelp'        => "Бүкіл бетті қайтару үшін, барлық қабашақтарды бос қалдырып
'''''Қайтар!''''' түймесін нұқыңыз. Бөлекше қайтару орындау үшін, қайтарайын деген нұсқаларына сәйкес
қабашақтарын белгілеңіз де, және '''''Қайтар!''''' түймесін нұқыңыз. '''''Таста''''' түймесін
нұқығанда мәндеме аумағы мен барлық қабашақтар тазаланады.",
'undeleterevisions'        => '{{PLURAL:$1|Бір нұсқа|$1 нұсқа}} мұрағатталды',
'undeletehistory'          => 'Егер бет мағлұматын қайтарсаңыз,тарихында барлық нұсқалар да
қайтарылады. Егер жоюдан соң дәл солай атауымен жаңа бет жасалса, қайтарылған нұсқалар
тарихтың ең адында көрсетіледі, және көрсетіліп тұрған беттің ағымдық нұсқасы
өздіктік алмастырылмайды. Файл нұсқаларының қайтарғанда шектеулері жойылатын ұмытпаңыз.',
'undeleterevdel'           => 'Егер беттің үстіңгі нұсқасы жарым-жартылай жойылған болса жойылған қайтаруы
 атқарылмайды. Осындай жағдайларда, ең жаңа жойылған нұсқа белгілеуін немесе жасыруын аластатыңыз.
Көруіңізге рұқсат етілмеген файл нұсқалары қайтарылмайды.',
'undeletehistorynoadmin'   => 'Бұл бет жойылған. Жою себебі алдындағы өңдеген қатысушылар
егжей-тегжейлерімен бірге төмендегі сипаттамасында көрсетілген.
Осы жойылған нұсқалардың мәтіні тек әкімшілерге қатынаулы.',
'undelete-revision'        => '$2 кезіндегі $1 дегеннің жойылған нұсқасы:',
'undeleterevision-missing' => 'Жарамсыз не жоғалған нұсқа. Сілтемеңіз жарамсыз болуы мүмкін, не
нұсқа қайтарылған түге немесе мұрағаттан аластатылған.',
'undeletebtn'              => 'Қайтар!',
'undeletereset'            => 'Таста',
'undeletecomment'          => 'Мәндемесі:',
'undeletedarticle'         => '«[[$1]]» қайтарды',
'undeletedrevisions'       => '{{PLURAL:$1|Нұсқаны|$1 нұсқаны}} қайтарды',
'undeletedrevisions-files' => '{{PLURAL:$1|Нұсқаны|$1 нұсқаны}} және {{PLURAL:$2|файлды|$2 файлды}} қайтарды',
'undeletedfiles'           => '{{PLURAL:$1|1 файлды|$1 файлды}} қайтарды',
'cannotundelete'           => 'Қайтару сәтсіз бітті; тағы біреу сізден бұрын сол бетті қайтарған болар.',
'undeletedpage'            => "<big>'''$1 қайтарылды'''</big>

Жуықтағы жою мен қайтару жөнінде [[{{ns:special}}:Log/delete|жою журналын]] қараңыз.",
'undelete-header'          => 'Жуықтағы жойылған беттер жөнінде [[{{ns:special}}:Log/delete|жою журналын]] қараңыз.',
'undelete-search-box'      => 'Жойылған беттерді іздеу',
'undelete-search-prefix'   => 'Мынадан басталған беттерді көрсет:',
'undelete-search-submit'   => 'Іздеу',
'undelete-no-results'      => 'Жою мұрағатында ешқандай сәйкес беттер табылмады.',

# Namespace form on various pages
'namespace' => 'Есім аясы:',
'invert'    => 'Талғауды керілеу',

# Contributions
'contributions' => 'Қатысушы үлесі',
'mycontris'     => 'Үлесім',
'contribsub2'   => '$1 ($2) үлесі',
'nocontribs'    => 'Осы іздеу шартына сәйкес өзгерістер табылған жоқ.',
'ucnote'        => 'Төменде осы қатысушы жасаған соңғы <b>$2</b> күндегі, соңғы <b>$1</b> өзгерісі көрсетледі.',
'uclinks'       => 'Соңғы $2 күндегі, соңғы жасалған $1 өзгерісін қарау.',
'uctop'         => ' (үсті)',

'sp-contributions-newest'      => 'Ең жаңасына',
'sp-contributions-oldest'      => 'Ең ескісіне',
'sp-contributions-newer'       => 'Жаңалау $1',
'sp-contributions-older'       => 'Ескілеу $1',
'sp-contributions-newbies'     => 'Тек жаңа тіркелгіден жасаған үлестерді көрсет',
'sp-contributions-newbies-sub' => 'Жаңадан тіркелгі жасағандар үшін',
'sp-contributions-blocklog'    => 'Бұғаттау журналы',
'sp-contributions-search'      => 'Үлес үшін іздеу',
'sp-contributions-username'    => 'IP жай не қатысушы аты:',
'sp-contributions-submit'      => 'Іздеу',

'sp-newimages-showfrom' => '$1 кезінен бері — жаңа суреттерді көрсет',

# What links here
'whatlinkshere'        => 'Сілтеген беттер',
'whatlinkshere-barrow' => '&lt;',
'notargettitle'        => 'Ақырғы атау жоқ',
'notargettext'         => 'Осы әрекет орындалатын нысана бет,
не қатысушы көрсетілмеген.',
'linklistsub'          => '(Сілтемелер тізімі)',
'linkshere'            => "'''[[:$1]]''' дегенге мына беттер сілтейді:",
'nolinkshere'          => "'''[[:$1]]''' дегенге еш бет сілтемейді.",
'nolinkshere-ns'       => "Талғанған есім аясында '''[[:$1]]''' дегенге ешқандай бет сілтемейді.",
'isredirect'           => 'айдату беті',
'istemplate'           => 'кіріктіру',
'whatlinkshere-prev'   => '{{PLURAL:$1|алдыңғы|алдыңғы $1}}',
'whatlinkshere-next'   => '{{PLURAL:$1|келесі|келесі $1}}',
'whatlinkshere-links'  => '← сілтемелер',

# Block/unblock
'blockip'                     => 'Пайдаланушыны бұғаттау',
'blockiptext'                 => 'Төмендегі үлгіт пайдаланушының жазу 
рұқсатын белгілі IP жайымен не атауымен бұғаттау үшін қолданылады.
Бұны тек бұзақылықты қақпайлау үшін және де
[[{{{{ns:mediawiki}}:policy-url}}|ережелер]] бойынша атқаруыңыз жөн.
Төменде тиісті себебін толтырып көрсетіңіз (мысалы, дәйекке бұзақылықпен
өзгерткен беттерді келтіріп).',
'ipaddress'                   => 'IP жайы:',
'ipadressorusername'          => 'IP жайы не аты:',
'ipbexpiry'                   => 'Бітетін мерзімі:',
'ipbreason'                   => 'Себебі:',
'ipbreasonotherlist'          => 'Басқа себеп',
'ipbreason-dropdown'          => '
* Бұғаттаудың жалпы себебтері 
** Бұзақылық: жалған мәлімет енгізу 
** Бұзақылық: беттердегі мағлұматты жою 
** Бұзақылық: сыртқы тораптар сілтемелерін жаудыру 
** Бұзақылық: беттерге бөстекілік/қисынсыздық кірістріру 
** Қоқандау/қуғындау мінезқұлық 
** Көптеген тіркелгілерді жасап қиянаттау 
** Қолайсыз қатысушы атауы',
'ipbanononly'                 => 'Тек тіркелгісіз пайдаланушыларды бұғаттау',
'ipbcreateaccount'            => 'Тіркелгі жасауын қақпайлау',
'ipbemailban'                 => 'Пайдаланушы е-поштамен хат жіберуін қақпайлау',
'ipbenableautoblock'          => 'Бұл пайдаланушы соңғы қолданған IP жайы, және кейін түзету істеуге байқап қаралған әрқайсы IP жайлары өздіктік бұғатталсын',
'ipbsubmit'                   => 'Пайдаланушыны бұғаттау',
'ipbother'                    => 'Басқа мерзімі:',
'ipboptions'                  => '2 сағат:2 hours,1 күн:1 day,3 күн:3 days,1 апта:1 week,2 апта:2 weeks,1 ай:1 month,3 ай:3 months,6 ай:6 months,1 жыл:1 year,мәнгі:infinite',
'ipbotheroption'              => 'басқа',
'ipbotherreason'              => 'Басқа/қосымша себеп:',
'ipbhidename'                 => 'Бұғаттау журналындағы, белсенді бұғаттау тізіміндегі, қатысушы тізіміннегі аты/IP жасырылсын',
'badipaddress'                => 'Жарамсыз IP жай',
'blockipsuccesssub'           => 'Бұғаттау сәтті өтті',
'blockipsuccesstext'          => '[[{{ns:special}}:Contributions/$1|$1]] деген бұғатталған.
<br />Бұғаттарды шолып шығу үшін [[{{ns:special}}:Ipblocklist|IP бұғаттау тізімін]] қараңыз.',
'ipb-edit-dropdown'           => 'Бұғаттау себептерін өңдеу',
'ipb-unblock-addr'            => '$1 дегенді бұғаттамау',
'ipb-unblock'                 => 'Қатысушы атын немесе IP жайын бұғаттамау',
'ipb-blocklist-addr'          => '$1 үшін бар бұғаттауларды қарау',
'ipb-blocklist'               => 'Бар бұғаттауларды қарау',
'unblockip'                   => 'Пайдаланушыны бұғаттамау',
'unblockiptext'               => 'Төмендегі үлгіт белгілі IP жайымен не атауымен
бұрын бұғатталған пайдаланушының жазу рұқсатын қайтару үшін қолданылады.',
'ipusubmit'                   => 'Осы жайды бұғаттамау',
'unblocked'                   => '[[{{ns:user}}:$1|$1]] бұғаттауы өшірілді',
'unblocked-id'                => '$1 деген бұғаттау аластатылды',
'ipblocklist'                 => 'Бұғатталған пайдаланушы / IP- жай тізімі',
'ipblocklist-submit'          => 'Іздеу',
'blocklistline'               => '$1, $2 «$3» дегенді бұғаттады ($4)',
'infiniteblock'               => 'мәнгі',
'expiringblock'               => 'бітуі: $1',
'anononlyblock'               => 'тек тіркелгісіздерді',
'noautoblockblock'            => 'өздіктік бұғаттау өшірілген',
'createaccountblock'          => 'тіркелгі жасауы бұғатталған',
'emailblock'                  => 'е-пошта бұғатталған',
'ipblocklist-empty'           => 'Бұғаттау тізімі бос.',
'ipblocklist-no-results'      => 'Сұранысқан IP жай не қатысушы аты бұғатталған емес.',
'blocklink'                   => 'бұғаттау',
'unblocklink'                 => 'бұғаттамау',
'contribslink'                => 'үлесі',
'autoblocker'                 => 'IP жайыңызды жуықта «[[{{ns:user}}:1|$1]]» пайдаланған, сондықтан өздіктік бұғатталған. $1 бұғаттау себебі: «$2».',
'blocklogpage'                => 'Бұғаттау_журналы',
'blocklogentry'               => '«[[$1]]» дегенді $2 мерзімге бұғаттады $3',
'blocklogtext'                => 'Бұл пайдаланушыларды бұғаттау/бұғаттамау әрекеттерінің журналы. Өздіктік
бұғатталған IP жайлар осында тізімделгемеген. Ағымдағы белсенді бұғаттауларын
[[{{ns:special}}:Ipblocklist|IP бұғаттау тізімінен]] қарауға болады.',
'unblocklogentry'             => '«$1» дегеннің бұғаттауын өшірді',
'block-log-flags-anononly'    => 'тек тіркелмегендер',
'block-log-flags-nocreate'    => 'тіркелгі жасау өшірілген',
'block-log-flags-noautoblock' => 'өздіктік бұғаттағыш өшірілген',
'block-log-flags-noemail'     => 'е-пошта бұғатталған',
'range_block_disabled'        => 'Ауқым бұғаттауын жасау әкімшілік мүмкіндігі өшірілген.',
'ipb_expiry_invalid'          => 'Бітетін уақыты жарамсыз.',
'ipb_already_blocked'         => '«$1» бұғатталған түге',
'ip_range_invalid'            => 'IP жай ауқымы жарамсыз.',
'proxyblocker'                => 'Прокси серверлерді бұғаттауыш',
'ipb_cant_unblock'            => 'Қате: IP $1 бұғаттауы табылмады. Оның бұғаттауы өшірлген сияқты.',
'proxyblockreason'            => 'IP жайыңыз ашық прокси серверге жататындықтан бұғатталған. Интернет қызметін жабдықтаушыңызбен, не техникалық медеу қызметімен қатынасыңыз, және оларға осы оте күрделі қауыпсіздік шатақ туралы ақпарат беріңіз.',
'proxyblocksuccess'           => 'Бітті.',
'sorbs'                       => 'DNSBL қара тізімі',
'sorbsreason'                 => 'Сіздің IP жайыңыз осы торапта қолданылған DNSBL қара тізіміндегі ашық прокси-сервер деп табылады.',
'sorbs_create_account_reason' => 'Сіздің IP жайыңыз осы торапта қолданылған DNSBL қара тізіміндегі ашық прокси-сервер деп табылады. Тіркелгі жасай алмайсыз.',

# Developer tools
'lockdb'              => 'Дерекқорды құлыптау',
'unlockdb'            => 'Дерекқорды құлыптамау',
'lockdbtext'          => 'Дерекқордын құлыпталуы барлық пайдаланушының
бет өңдеу, баптауын қалау, бақылау тізімін, тағы басқа
дерекқорды өзгертетін мүмкіндіктерін тоқтата тұрады.
Осы мақсатыңызды, және жөндеуіңіз біткенде
дерекқорды ашатыңызды растаңыз.',
'unlockdbtext'        => 'Дерекқодын ашылуы барлық пайдаланушының бет өңдеу,
баптауын қалау, бақылау тізімін, тағы басқа дерекқорды өзгертетін
мүмкіндіктерін қайта ашады.
Осы мақсатыңызды растаңыз.',
'lockconfirm'         => 'Иә, мен дерекқорды растан құлыптаймын.',
'unlockconfirm'       => 'Иә, мен дерекқорды растан құлыптамаймын.',
'lockbtn'             => 'Дерекқорды құлыпта',
'unlockbtn'           => 'Дерекқорды құлыптама',
'locknoconfirm'       => 'Растау белгісін қоймапсыз.',
'lockdbsuccesssub'    => 'Дерекқорды құлыптау сәтті өтті',
'unlockdbsuccesssub'  => 'Құлыпталған дерекқор ашылды',
'lockdbsuccesstext'   => 'Дерекқор құлыпталды.
<br />Жөндеуіңіз біткеннен кейін [[{{ns:special}}:Unlockdb|құлыптауын өшіруге]] ұмытпаңыз.',
'unlockdbsuccesstext' => 'Құлыпталған дерекқор сәтті ашылды.',
'lockfilenotwritable' => 'Дерекқор құлыптау файлы жазылмайды. Дерекқорды құлыптау не ашу үшін, веб-сервер файлға жазу рұқсаты болу қажет.',
'databasenotlocked'   => 'Дерекқор құлыпталған жоқ.',

# Move page
'movepage'                => 'Бетті жылжыту',
'movepagetext'            => "Төмендегі үлгітті қолданып беттерді қайта атайды,
барлық тарихын жаңа атауға жылжытады.
Бұрынғы бет атауы жаңа атауға айдататын бет болады.
Ескі атауына сілтейтін сілтемелер өзгертілмейді; жылжытудан соң
шынжырлы не жарамсыз айдатулар бар-жоғын тексеріп шығыңыз.
Сілтемелер бұрынғы жолдауымен былайғы өтуін тексеруіне
сіз міндетті боласыз.

Ескеріңіз, егер жылжытылатын атауда бет болса, сол ескі бетке айдату
болғанша және тарихы болса, бет '''жылжытылмайды'''.
Осының мағынасы: егер бетті қателік пен қайта аталса,
бұрынғы атауына қайта атауға болады,
бірақ бар беттің үстіне жазуға болмайды.

<b>НАЗАР САЛЫҢЫЗ!</b>
Бұл әйгілі бетке қатаң және кенет өзгеріс жасауға мүмкін;
әрекеттің алдынан осының зардаптарын түсінгеніңізге батыл
болыңыз.",
'movepagetalktext'        => "Келесі себептер '''болғанша''' дейін, талқылау беті өздіктік бірге жылжытылады:
* Бос емес талқылау беті жаңа атауда болғанда, немесе
* Төмендегі қабышақта белгіні аластатқанда.

Осы орайда, қалауыңыз болса, бетті қолдан жылжыта не қоса аласыз.",
'movearticle'             => 'Бетті жылжыту',
'movenologin'             => 'Жүйеге кірмегенсіз',
'movenologintext'         => 'Бетті жылжыту үшін тіркелген болуыңыз және
 [[{{ns:special}}:Userlogin|кіруіңіз]] қажет.',
'newtitle'                => 'Жаңа атауға',
'move-watch'              => 'Бұл бетті бақылау',
'movepagebtn'             => 'Бетті жылжыт',
'pagemovedsub'            => 'Жылжыту сәтті аяқталды',
'pagemovedtext'           => '«[[$1]]» беті «[[$2]]» бетіне жылжытылды.',
'articleexists'           => 'Былай атаулы бет бар болды, не таңдаған
атауыңыз жарамды емес.
Басқа атау тандаңыз',
'talkexists'              => "'''Беттің өзі сәтті жылжытылды, бірақ талқылау беті бірге жылжытылмады, оның себебі жаңа атаудың талқылау беті бар түге. Бұны қолмен қосыңыз.'''",
'movedto'                 => 'мынаған жылжытылды:',
'movetalk'                => 'Қатысты талқылау бетімен бірге жылжыту',
'talkpagemoved'           => 'Қатысты талқылау беті де жылжытылды.',
'talkpagenotmoved'        => 'Қатысты талқылау беті <strong>жылжытылмады</strong>.',
'1movedto2'               => '«[[$1]]» бетінде айдату қалдырып «[[$2]]» бетіне жылжытты',
'1movedto2_redir'         => '«[[$1]]» бетін «[[$2]]» айдату бетінің үстіне жылжытты',
'movelogpage'             => 'Жылжыту журналы',
'movelogpagetext'         => 'Төменде жылжытылған беттердің тізімі беріліп тұр.',
'movereason'              => 'Себебі',
'revertmove'              => 'қайтару',
'delete_and_move'         => 'Жою және жылжыту',
'delete_and_move_text'    => '==Жою қажет==

Ақырғы «[[$1]]» бет атауы бар түге.
Жылжытуға жол беру үшін жоямыз ба?',
'delete_and_move_confirm' => 'Иә, осы бетті жой',
'delete_and_move_reason'  => 'Жылжытуға жол беру үшін жойылған',
'selfmove'                => 'Қайнар және ақырғы атауы бірдей; бет өзіне жылжытылмайды.',
'immobile_namespace'      => 'Қайнар немесе ақырғы атауы арнайы түрінде болды; осындай есім аясы жағына және жағынан беттер жылжытылмайды.',

# Export
'export'            => 'Беттерді сыртқа беру',
'exporttext'        => 'XML пішіміне қапталған бөлек бет не беттер бумасы
мәтінің және өңдеу тарихын сыртқа бере аласыз. Осыны, басқа уикиге
жүйенің [[{{ns:special}}:Import|сырттан алу бетін]] пайдаланып, алуға болады.

Беттерді сыртқа беру үшін, атауларын төмендегі мәтін аумағына енгізіңіз,
бір жолда бір атау, және тандаңыз: не ағымдық нұсқасын, барлық ескі нұсқалары мен
және тарихы жолдары мен бірге, не дәл ағымдық нұсқасын, соңғы өңдеу туралы ақпараты мен бірге.

Соңғы жағдайда сілтемені де, мысалы {{{{ns:mediawiki}}:mainpage}} беті үшін [[{{ns:special}}:Export/{{MediaWiki:mainpage}}]] қолдануға болады.',
'exportcuronly'     => 'Толық тарихын емес, тек ағымдық нұсқасын кірістіріңіз',
'exportnohistory'   => "----
'''Аңғартпа:''' Өнімділік әсері себептерінен, беттер толық тарихын сыртқа беруі өшірілген.",
'export-submit'     => 'Сыртқа бер',
'export-addcattext' => 'Мына санаттағы беттерді үстеу:',
'export-addcat'     => 'Үсте',

# Namespace 8 related
'allmessages'               => 'Жүйе хабарлары',
'allmessagesname'           => 'Атауы',
'allmessagesdefault'        => 'Әдепкі мәтіні',
'allmessagescurrent'        => 'Ағымдық мәтіні',
'allmessagestext'           => 'Мында «MediaWiki:» есім аясындағы барлық қатынаулы жүйе хабар тізімі беріліп тұр.',
'allmessagesnotsupportedUI' => 'Your current interface language <b>$1</b> is not supported by Special:Allmessages at this site.',
'allmessagesnotsupportedDB' => "'''wgUseDatabaseMessages''' бабы өшірілген себебінен '''{{ns:special}}:AllMessages''' сипаты сүемелденбейді.",
'allmessagesfilter'         => 'Хабарды атауы бойынша сүзгілеу:',
'allmessagesmodified'       => 'Тек өзгертілгенді көрсет',

# Thumbnails
'thumbnail-more'           => 'Үлкейту',
'missingimage'             => '<b>Жоғалған сурет </b><br /><i>$1</i>',
'filemissing'              => 'Жоғалған файл',
'thumbnail_error'          => 'Нобай құру қатесі: $1',
'djvu_page_error'          => 'DjVu беті мүмкінді аумақтың сыртындда',
'djvu_no_xml'              => 'DjVu файлына XML келтіруге болмайды',
'thumbnail_invalid_params' => 'Нобайдың баптары жарамсыз',
'thumbnail_dest_directory' => 'Ақырғы қалта жасалмады',

# Special:Import
'import'                     => 'Беттерді сырттан алу',
'importinterwiki'            => 'Уики-тасымалдап сырттан алу',
'import-interwiki-text'      => 'Сырттан алатын уики жобасын және бет атауын тандаңыз.
Нұсқа күн-айы және өңдеуші аттары сақталады.
Барлық уики-тасымалдап сырттан алу әрекеттер [[{{ns:special}}:Log/import|сырттан алу журналына]] жазылып алынады.',
'import-interwiki-history'   => 'Осы беттің барлық тарихи нұсқаларын көшіру',
'import-interwiki-submit'    => 'Сырттан алу',
'import-interwiki-namespace' => 'Мына есім аясына беттерді тасымалдау:',
'importtext'                 => 'Қайнар уикиден «Special:Export» қуралын қолданып, файлды сыртқа беріңіз, дискіңізге сақтаңыз, сосын мында қотарыңыз.',
'importstart'                => 'Беттерді сырттан алуы…',
'import-revision-count'      => '$1 нұсқа',
'importnopages'              => 'Сырттан алынатын беттер жоқ.',
'importfailed'               => 'Сырттан алу сәтсіз бітті: $1',
'importunknownsource'        => 'Cырттан алу қайнар түрі танымалсыз',
'importcantopen'             => 'Сырттан алу файлы ашылмайды',
'importbadinterwiki'         => 'Жарамсыз уики-аралық сілтеме',
'importnotext'               => 'Босты, не мәтіні жоқ',
'importsuccess'              => 'Сырттан алу сәтті аяқталды!',
'importhistoryconflict'      => 'Тарихының егес нұсқалары бар (бұл бетті алдында сырттан алынған сияқты)',
'importnosources'            => 'Ешқандай уики-тасымалдап сырттан алу қайнары белгіленмеген, және тарихын тікелей қотаруы өшірілген.',
'importnofile'               => 'Сырттан алынатын файл қотарылған жоқ.',
'importuploaderror'          => 'Сырттан алу файлдың қотаруы сәтсіз бітті; осы файл мөлшері рұқсат етілген мөлшерден асуы мүмкін.',

# Import log
'importlogpage'                    => 'Сырттан алу журналы',
'importlogpagetext'                => 'Басқа уикилерден өңдеу тарихымен бірге беттерді әкімшілік ретінде сырттан алу.',
'import-logentry-upload'           => 'файл қотаруымен сырттан «[[$1]]» беті алынды',
'import-logentry-upload-detail'    => '$1 нұсқа',
'import-logentry-interwiki'        => 'уики-тасымалданған $1',
'import-logentry-interwiki-detail' => '$2 дегеннен $1 нұсқа',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Жеке бетім',
'tooltip-pt-anonuserpage'         => 'Осы IP жайдың жеке беті',
'tooltip-pt-mytalk'               => 'Талқылау бетім',
'tooltip-pt-anontalk'             => 'Осы IP жай түзетулерін талқылау',
'tooltip-pt-preferences'          => 'Баптауым',
'tooltip-pt-watchlist'            => 'Өзгерістерін бақылап тұрған беттер тізімім.',
'tooltip-pt-mycontris'            => 'Үлестерімдің тізімі',
'tooltip-pt-login'                => 'Кіруіңізді ұсынамыз, ол міндетті емес.',
'tooltip-pt-anonlogin'            => 'Кіруіңізді ұсынамыз, бірақ, ол міндетті емес.',
'tooltip-pt-logout'               => 'Шығу',
'tooltip-ca-talk'                 => 'Мағлұмат бетті талқылау',
'tooltip-ca-edit'                 => 'Бұл бетті өңдей аласыз. Сақтаудың алдында «Қарап шығу» түймесін нұқыңыз.',
'tooltip-ca-addsection'           => 'Бұл талқылау бетінде жаңа тарау бастау.',
'tooltip-ca-viewsource'           => 'Бұл бет қорғалған, бірақ, қайнарын қарауға болады.',
'tooltip-ca-history'              => 'Бұл беттін жуықтағы нұсқалары.',
'tooltip-ca-protect'              => 'Бұл бетті қорғау',
'tooltip-ca-delete'               => 'Бұл бетті жою',
'tooltip-ca-undelete'             => 'Бұл беттің жоюдың алдындағы болған түзетулерін қайтару',
'tooltip-ca-move'                 => 'Бұл бетті жылжыту',
'tooltip-ca-watch'                => 'Бұл бетті бақылау тізіміңізге үстеу',
'tooltip-ca-unwatch'              => 'Бұл бетті бақылау тізіміңізден аластату',
'tooltip-search'                  => '{{SITENAME}} жобасынан іздестіру',
'tooltip-p-logo'                  => 'Басты бетке',
'tooltip-n-mainpage'              => 'Басты бетке барып кетіңіз',
'tooltip-n-portal'                => 'Жоба туралы, не істеуіңізге болатын, қайдан табуға болатын туралы',
'tooltip-n-currentevents'         => 'Ағымдағы оқиғаларға қатысты ақпарат',
'tooltip-n-recentchanges'         => 'Осы уикидегі жуықтағы өзгерістер тізімі.',
'tooltip-n-randompage'            => 'Кездейсоқ бетті жүктеу',
'tooltip-n-help'                  => 'Анықтама табу орны.',
'tooltip-n-sitesupport'           => 'Бізге жәрдем етіңіз',
'tooltip-t-whatlinkshere'         => 'Мында сілтеген барлық беттердің тізімі',
'tooltip-t-recentchangeslinked'   => 'Мыннан сілтенген беттердің жуықтағы өзгерістері',
'tooltip-feed-rss'                => 'Бұл беттің RSS арнасы',
'tooltip-feed-atom'               => 'Бұл беттің Atom арнасы',
'tooltip-t-contributions'         => 'Осы қатысушының үлес тізімін қарау',
'tooltip-t-emailuser'             => 'Осы қатысушыға email жіберу',
'tooltip-t-upload'                => 'Сурет не медиа файлдарын қотару',
'tooltip-t-specialpages'          => 'Барлық арнайы беттер тізімі',
'tooltip-t-print'                 => 'Бұл беттің басып шығарышқа арналған нұсқасы',
'tooltip-t-permalink'             => 'Мына беттің осы нұсқасының тұрақты сілтемесі',
'tooltip-ca-nstab-main'           => 'Мағлұмат бетін қарау',
'tooltip-ca-nstab-user'           => 'Қатысушы бетін қарау',
'tooltip-ca-nstab-media'          => 'Таспа бетін қарау',
'tooltip-ca-nstab-special'        => 'Бұл арнайы бет, беттің өзі өңделінбейді.',
'tooltip-ca-nstab-project'        => 'Жоба бетін қарау',
'tooltip-ca-nstab-image'          => 'Сурет бетін қарау',
'tooltip-ca-nstab-mediawiki'      => 'Жүйе хабарын қарау',
'tooltip-ca-nstab-template'       => 'Үлгіні қарау',
'tooltip-ca-nstab-help'           => 'Анықтыма бетін қарау',
'tooltip-ca-nstab-category'       => 'Санат бетін қарау',
'tooltip-minoredit'               => 'Осыны шағын түзету деп белгілеу',
'tooltip-save'                    => 'Жасаған өзгерістеріңізді сақтау',
'tooltip-preview'                 => 'Сақтаудың алдынан жасаған өзгерістеріңізді қарап шығыңыз!',
'tooltip-diff'                    => 'Мәтінге қандай өзгерістерді жасағаныңызды қарау.',
'tooltip-compareselectedversions' => 'Беттің екі нұсқасының айырмасын қарау.',
'tooltip-watch'                   => 'Бұл бетті бақылау тізіміңізге үстеу',
'tooltip-recreate'                => 'Бет жойылғанына қарамастан қайта жасау',

# Stylesheets
'common.css'   => '/* Мындағы CSS әмірлері барлық безендіру мәнеріндерде қолданылады */',
'monobook.css' => '/* Мындағы CSS әмірлері «Дара кітап» безендіру мәнерін пайдаланушыларға әсер етеді */',

# Scripts
'common.js'   => '/* Мындағы JavaScript әмірлері әрқайсы бет қаралғанда барлық пайдаланушыларға жүктеледі. */
/* Workaround for language variants */
var languagevariant;
var direction; 
switch(wgUserLanguage){
    case "kk": 
         languagevariant = "kk";
         direction = "ltr";
         break;
    case "kk-kz": 
         languagevariant = "kk-Cyrl";
         direction = "ltr";
         break;
    case "kk-tr": 
         languagevariant = "kk-Latn";
         direction = "ltr";
         break;
    case "kk-cn": 
         // workaround for RTL ([[bugzilla:6756]])  and for [[bugzilla:02020]] & [[bugzilla:04295]]
         languagevariant = "kk-Arab";
         direction = "rtl";

         document.getElementsByTagName("body").className = "rtl";
         document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/common/common_rtl.css">\');
         document.write(\'<style type="text/css">div#shared-image-desc {direction: ltr;} input#wpUploadFile, input#wpDestFile, input#wpLicense {float: right;} .editsection {float: left !important;} .infobox {float: left !important; clear:left; } div.floatleft, table.floatleft {float:right !important; margin-left:0.5em !important; margin-right:0 !important; } div.floatright, table.floatright {clear:left; float:left !important; margin-left:0 !important; margin-right:0.5em !important;}</style>\');

         if (skin == "monobook"){
             document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/common/quickbar-right.css">\');
             document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/monobook/rtl.css">\');
             document.write(\'<style type="text/css">body{font-size: 75%; letter-spacing: 0.001em;} h3{font-size:110%;} h4 {font-size:100%;} h5{font-size:90%;} html > body div#content ol{clear: left;} ol{margin-left:2.4em; margin-right:2.4em;} ul{margin-left:1.5em; margin-right:1.5em;} .editsection{margin-right:5px; margin-left:0;}  #column-one{padding-top:0; margin-top:0;} #p-navigation{padding-top:0; margin-top:160px;} #catlinks{width:100%;} #userloginForm{float: right !important;} .pBody{-moz-border-radius-topleft: 0.5em; -moz-border-radius-topright: 0em !important;} .portlet h5{clear:right;}</style>\');
         }
         break;
     default: 
         languagevariant = "kk";
         direction = "ltr";
}

// Set user language attributes for the whole document
var htmlE=document.documentElement;
htmlE.setAttribute("lang",languagevariant);
htmlE.setAttribute("xml:lang",languagevariant);
htmlE.setAttribute("dir",direction); 

// Switch language variants of messages
function wgULS(kz,tr,cn,en){
    if (!en) { en = ""; }

    kk=kz||tr||cn;
    kz=kz;
    tr=tr;
    cn=cn;
    switch(wgUserLanguage){
        case "kk": return kk;
        case "kk-kz": return kz;
        case "kk-tr": return tr;
        case "kk-cn": return cn;
        default: return en;
    }
}',
'monobook.js' => '/* Бостекі болды; орнына мынаны [[MediaWiki:common.js]] пайдалаңыз */',

# Metadata
'nodublincore'      => 'Осы серверге «Dublin Core RDF» мета-деректері өшірілген.',
'nocreativecommons' => 'Осы серверге «Creative Commons RDF» мета-деректері өшірілген.',
'notacceptable'     => 'Осы уики сервері сіздің «пайдаланушы әрекеткіші» оқи алатын пішімі бар деректерді жібере алмайды.',

# Attribution
'anonymous'        => '{{SITENAME}} тіркелгісіз пайдаланушы(лар)ы',
'siteuser'         => '{{SITENAME}} қатысушы $1',
'lastmodifiedatby' => 'Бұл бетті $3 қатысушы соңғы өзгерткен кезі: $2, $1.', # $1 date, $2 time, $3 user
'and'              => 'және',
'othercontribs'    => 'Шығарма негізін $1 жазған.',
'others'           => 'басқалар',
'siteusers'        => '{{SITENAME}} қатысушы(лар) $1',
'creditspage'      => 'Бетті жазғандар',
'nocredits'        => 'Бұл бетті жазғандар туралы ақпарат жоқ.',

# Spam protection
'spamprotectiontitle'    => '«Спам»-нан қорғайтын сүзгі',
'spamprotectiontext'     => 'Бұл беттің сақтауын «спам» сүзгісі бұғаттады. Бұның себебі сыртқы торап сілтемесінен болуы мүмкін.',
'spamprotectionmatch'    => 'Келесі «спам» мәтіні сүзгіленген: $1',
'subcategorycount'       => 'Бұл санатта {{PLURAL:$1|бір|$1}} санатша бар.',
'categoryarticlecount'   => 'Бұл санатта {{PLURAL:$1|бір|$1}} бет бар.',
'category-media-count'   => 'Бұл санатта {{PLURAL:$1|бір|$1}} файл бар.',
'listingcontinuesabbrev' => ' (жалғ.)',
'spambot_username'       => 'MediaWiki spam cleanup',
'spam_reverting'         => '$1 дегенге сілтемесі жоқ соңғы нұсқасына қайтарылды',
'spam_blanking'          => '$1 дегенге сілтемесі бар барлық нұсқалар тазартылды',

# Info page
'infosubtitle'   => 'Бет туралы ақпарат',
'numedits'       => 'Түзету саны (негізгі бет): $1',
'numtalkedits'   => 'Түзету саны (талқылау беті): $1',
'numwatchers'    => 'Бақылаушы саны: $1',
'numauthors'     => 'Әртүрлі ауторлар саны (негізгі беті): $1',
'numtalkauthors' => 'Әртүрлі аутор саны (талқылау беті): $1',

# Math options
'mw_math_png'    => 'Әрқашан PNG түрімен көрсет',
'mw_math_simple' => 'Кәдімгі болса HTML пішімімен, басқаша PNG түрімен',
'mw_math_html'   => 'Ықтимал болса HTML пішімімен, басқаша PNG түрімен',
'mw_math_source' => 'TeX пішімінде қалдыру (мәтіндік шолғыштарына)',
'mw_math_modern' => 'Осы заманның шолғыштарына ұсынылған',
'mw_math_mathml' => 'Ықтимал болса MathML пшімімен (сынақ түрінде)',

# Patrolling
'markaspatrolleddiff'                 => 'Күзетте деп белгілеу',
'markaspatrolledtext'                 => 'Осы бетті күзетуде деп белгілеу',
'markedaspatrolled'                   => 'Күзетте деп белгіленді',
'markedaspatrolledtext'               => 'Талғанған нұсқа күзетте деп белгіленді.',
'rcpatroldisabled'                    => 'Жуықтағы өзгерістер Күзеті өшірілген',
'rcpatroldisabledtext'                => 'Жуықтағы өзгерістер Күзеті қасиеті ағымда өшірілген.',
'markedaspatrollederror'              => 'Күзетте деп белгіленбейді',
'markedaspatrollederrortext'          => 'Күзетте деп белгілеу үшін нұсқасын енгізіңіз.',
'markedaspatrollederror-noautopatrol' => 'Өзіңіз жасаған өзгерістеріңізді күзетке қоя алмайсыз.',

# Patrol log
'patrol-log-page' => 'Күзет журналы',
'patrol-log-line' => '$2 кезінде $1 дегенді күзетте деп белгіледі $3',
'patrol-log-auto' => '(өздіктік)',
'patrol-log-diff' => 'r$1',

# Image deletion
'deletedrevision' => 'Мына ескі нұсқасын жойды: $1.',

# Browsing diffs
'previousdiff' => '← Алдыңғымен айырмасы',
'nextdiff'     => 'Келесімен айырмасы →',

# Media information
'mediawarning'         => "'''Назар салыңыз''': Бұл файл түрінде қаскүнемді әмірдің бар болуы ықтимал; файлды жегіп жүйеңізге зиян келтіруіңіз мүмкін.<hr />",
'imagemaxsize'         => 'Сипаттамасы бетіндегі суреттің мөлшерін шектеуі:',
'thumbsize'            => 'Нобай мөлшері:',
'widthheight'          => '$1 × $2',
'file-info'            => 'Файл мөлшері: $1, MIME түрі: $2',
'file-info-size'       => '($1 × $2 пиксел, файл мөлшері: $3, MIME түрі: $4)',
'file-nohires'         => '<small>Жоғары ажыратылымдығы жетімсіз.</small>',
'file-svg'             => '<small>Бұл шығынсыз созылғыш векторлық суреті. Негізгі мөлшері: $1 × $2 пиксел.</small>',
'show-big-image'       => 'Жоғары ажыратылымды',
'show-big-image-thumb' => '<small>Қарап шығу мөлшері: $1 × $2 пиксел</small>',

'newimages'    => 'Ең жаңа файлдар қоймасы',
'showhidebots' => '(боттарды $1)',
'noimages'     => 'Көретін ештеңе жоқ.',

# Variants for Kazakh language
'variantname-kk-tr' => 'Latın',
'variantname-kk-kz' => 'Кирил',
'variantname-kk-cn' => 'توتە',
'variantname-kk'    => 'disable',

'passwordtooshort' => 'Құпия сөзіңіз жарамсыз не тым қысқа. Ең кемінде $1 әріп және қатысушы атыңыздан басқа болуы қажет.',

# Metadata
'metadata'          => 'Мета-деректері',
'metadata-help'     => 'Осы файлда қосымша ақпарат бар. Бәлкім, осы ақпарат файлды жасап шығару, не сандылау үшін пайдаланған сандық камера, не мәтіналғырдан алынған. Егер осы файл негізгі күйінен өзгертілген болса, кейбір ежелелері өзгертілген фотосуретке лайық болмас.',
'metadata-expand'   => 'Егжей-тегжейін көрсет',
'metadata-collapse' => 'Егжей-тегжейін жасыр',
'metadata-fields'   => 'Осы хабарда тізімделген EXIF мета-дерек аумақтары,
сурет беті көрсету кезінде мета-дерек кесте жасырылығанда кірістірледі.
Басқасы әдепкіден жасырылады.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Ені',
'exif-imagelength'                 => 'Биіктігі',
'exif-bitspersample'               => 'Құраш сайын бит саны',
'exif-compression'                 => 'Қысым сұлбасы',
'exif-photometricinterpretation'   => 'Пиксел қиысуы',
'exif-orientation'                 => 'Мегзеуі',
'exif-samplesperpixel'             => 'Құраш саны',
'exif-planarconfiguration'         => 'Дерек реттеуі',
'exif-ycbcrsubsampling'            => 'Y құрашының C құрашына жарнақтауы',
'exif-ycbcrpositioning'            => 'Y құрашы және C құрашы мекендеуі',
'exif-xresolution'                 => 'Дерелей ажыратылымдығы',
'exif-yresolution'                 => 'Тірелей ажыратылымдығы',
'exif-resolutionunit'              => 'X және Y ажыратылымдықтарығының өлшемі',
'exif-stripoffsets'                => 'Сурет дереректерінің жайғасуы',
'exif-rowsperstrip'                => 'Белдік сайын жол саны',
'exif-stripbytecounts'             => 'Қысымдалған белдік сайын байт саны',
'exif-jpeginterchangeformat'       => 'JPEG SOI дегеннен ығысуы',
'exif-jpeginterchangeformatlength' => 'JPEG деректерінің байт саны',
'exif-transferfunction'            => 'Тасымалдау функциясы',
'exif-whitepoint'                  => 'Ақ нүкте түстілігі',
'exif-primarychromaticities'       => 'Алғы шептегі түстіліктері',
'exif-ycbcrcoefficients'           => 'Түс аясын тасымалдау матрицалық еселіктері',
'exif-referenceblackwhite'         => 'Қара және ақ анықтауыш қос колемдері',
'exif-datetime'                    => 'Файлдың өзгертілген күн-айы',
'exif-imagedescription'            => 'Сурет атауы',
'exif-make'                        => 'Камера өндірушісі',
'exif-model'                       => 'Камера үлгісі',
'exif-software'                    => 'Қолданылған бағдарлама',
'exif-artist'                      => 'Жығармашысы',
'exif-copyright'                   => 'Жығармашылық құқықтар иесі',
'exif-exifversion'                 => 'Exif нұсқасы',
'exif-flashpixversion'             => 'Сүйемделінген Flashpix нұсқасы',
'exif-colorspace'                  => 'Түс аясы',
'exif-componentsconfiguration'     => 'Әрқайсы құраш мәні',
'exif-compressedbitsperpixel'      => 'Сурет қысымдау тәртібі',
'exif-pixelydimension'             => 'Суреттің жарамды ені',
'exif-pixelxdimension'             => 'Суреттің жарамды биіктігі',
'exif-makernote'                   => 'Өндіруші ескертпелері',
'exif-usercomment'                 => 'Пайдаланушы мәндемелері',
'exif-relatedsoundfile'            => 'Қатысты дыбыс файлы',
'exif-datetimeoriginal'            => 'Жасалған кезі',
'exif-datetimedigitized'           => 'Сандықтау кезі',
'exif-subsectime'                  => 'Жасалған кезінің секунд бөлшектері',
'exif-subsectimeoriginal'          => 'Түпнұсқа кезінің секунд бөлшектері',
'exif-subsectimedigitized'         => 'Сандықтау кезінің секунд бөлшектері',
'exif-exposuretime'                => 'Ұсталым уақыты',
'exif-exposuretime-format'         => '$1 с ($2)',
'exif-fnumber'                     => 'Саңылау мөлшері',
'exif-exposureprogram'             => 'Ұсталым бағдарламасы',
'exif-spectralsensitivity'         => 'Спектр бойынша сезгіштігі',
'exif-isospeedratings'             => 'ISO жылдамдық жарнақтауы (жарық сезгіштігі)',
'exif-oecf'                        => 'Оптоелектронды түрлету ықпалы',
'exif-shutterspeedvalue'           => 'Жапқыш жылдамдылығы',
'exif-aperturevalue'               => 'Саңылаулық',
'exif-brightnessvalue'             => 'Ашықтық',
'exif-exposurebiasvalue'           => 'Ұсталым өтемі',
'exif-maxaperturevalue'            => 'Барынша саңылау ашуы',
'exif-subjectdistance'             => 'Нысана қашықтығы',
'exif-meteringmode'                => 'Өлшеу тәртібі',
'exif-lightsource'                 => 'Жарық көзі',
'exif-flash'                       => 'Жарқылдағыш',
'exif-focallength'                 => 'Шоғырлау алшақтығы',
'exif-subjectarea'                 => 'Нысана ауқымы',
'exif-flashenergy'                 => 'Жарқылдағыш қарқыны',
'exif-spatialfrequencyresponse'    => 'Кеңістік-жиілік әсершілігі',
'exif-focalplanexresolution'       => 'Х бойынша шоғырлау жайпақтықтың ажыратылымдығы',
'exif-focalplaneyresolution'       => 'Y бойынша шоғырлау жайпақтықтың ажыратылымдығы',
'exif-focalplaneresolutionunit'    => 'Шоғырлау жайпақтықтың ажыратылымдық өлшемі',
'exif-subjectlocation'             => 'Нысана мекендеуі',
'exif-exposureindex'               => 'Ұсталым айқындауы',
'exif-sensingmethod'               => 'Сенсордің өлшеу әдісі',
'exif-filesource'                  => 'Файл қайнары',
'exif-scenetype'                   => 'Сахна түрі',
'exif-cfapattern'                  => 'CFA сүзгі кейіпі',
'exif-customrendered'              => 'Қосымша сурет өңдетуі',
'exif-exposuremode'                => 'Ұсталым тәртібі',
'exif-whitebalance'                => 'Ақ түсінің тендестігі',
'exif-digitalzoomratio'            => 'Сандық ауқымдау жарнақтауы',
'exif-focallengthin35mmfilm'       => '35 mm таспасының шоғырлау алшақтығы',
'exif-scenecapturetype'            => 'Түсірген сахна түрі',
'exif-gaincontrol'                 => 'Сахнаны меңгеру',
'exif-contrast'                    => 'Қарама-қарсылық',
'exif-saturation'                  => 'Қанықтық',
'exif-sharpness'                   => 'Айқындық',
'exif-devicesettingdescription'    => 'Жабдық баптау сипаттары',
'exif-subjectdistancerange'        => 'Сахна қашықтығының көлемі',
'exif-imageuniqueid'               => 'Суреттің бірегей нөмірі (ID)',
'exif-gpsversionid'                => 'GPS белгішесінің нұсқасы',
'exif-gpslatituderef'              => 'Солтүстік немесе Оңтүстік бойлығы',
'exif-gpslatitude'                 => 'Бойлығы',
'exif-gpslongituderef'             => 'Шығыс немесе Батыс ендігі',
'exif-gpslongitude'                => 'Ендігі',
'exif-gpsaltituderef'              => 'Биіктік көрсетуі',
'exif-gpsaltitude'                 => 'Биіктік',
'exif-gpstimestamp'                => 'GPS уақыты (атом сағаты)',
'exif-gpssatellites'               => 'Өлшеуге пйдаланылған Жер серіктері',
'exif-gpsstatus'                   => 'Қабылдағыш күйі',
'exif-gpsmeasuremode'              => 'Өлшеу тәртібі',
'exif-gpsdop'                      => 'Өлшеу дәлдігі',
'exif-gpsspeedref'                 => 'Жылдамдылық өлшемі',
'exif-gpsspeed'                    => 'GPS қабылдағыштың жылдамдылығы',
'exif-gpstrackref'                 => 'Қозғалыс бағытын көрсетуі',
'exif-gpstrack'                    => 'Қозғалыс бағыты',
'exif-gpsimgdirectionref'          => 'Сурет бағытын көрсетуі',
'exif-gpsimgdirection'             => 'Сурет бағыты',
'exif-gpsmapdatum'                 => 'Пайдаланылған геодезиялық түсірме деректері',
'exif-gpsdestlatituderef'          => 'Нысана бойлығын көрсетуі',
'exif-gpsdestlatitude'             => 'Нысана бойлығы',
'exif-gpsdestlongituderef'         => 'Нысана ендігін көрсетуі',
'exif-gpsdestlongitude'            => 'Нысана ендігі',
'exif-gpsdestbearingref'           => 'Нысана азимутын көрсетуі',
'exif-gpsdestbearing'              => 'Нысана азимуты',
'exif-gpsdestdistanceref'          => 'Нысана қашықтығын көрсетуі',
'exif-gpsdestdistance'             => 'Нысана қашықтығы',
'exif-gpsprocessingmethod'         => 'GPS өңдету әдісінің атауы',
'exif-gpsareainformation'          => 'GPS аумағының атауы',
'exif-gpsdatestamp'                => 'GPS күн-айы',
'exif-gpsdifferential'             => 'GPS сараланған түзету',

# EXIF attributes
'exif-compression-1' => 'Ұлғайтылған',

'exif-unknowndate' => 'Белгісіз күн-айы',

'exif-orientation-1' => 'Қалыпты', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Дерелей шағылысқан', # 0th row: top; 0th column: right
'exif-orientation-3' => '180° бұрышқа айналған', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Тірелей шағылысқан', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Сағат тілшесіне қарсы 90° бұрышқа айналған және тірелей шағылысқан', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Сағат тілше бойынша 90° бұрышқа айналған', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Сағат тілше бойынша 90° бұрышқа айналған және тірелей шағылысқан', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Сағат тілшесіне қарсы 90° бұрышқа айналған', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'талпақ пішім',
'exif-planarconfiguration-2' => 'тайпақ пішім',

'exif-componentsconfiguration-0' => 'бар болмады',

'exif-exposureprogram-0' => 'Анықталмаған',
'exif-exposureprogram-1' => 'Қолмен',
'exif-exposureprogram-2' => 'Бағдарламалы әдіс (қалыпты)',
'exif-exposureprogram-3' => 'Саңылау басыңқылығы',
'exif-exposureprogram-4' => 'Ысырма басыңқылығы',
'exif-exposureprogram-5' => 'Өнер бағдарламасы (анықтық терендігіне санасқан)',
'exif-exposureprogram-6' => 'Қимыл бағдарламасы (жапқыш шапшандылығына санасқан)',
'exif-exposureprogram-7' => 'Тірелей әдісі (арты шоғырлаусыз таяу түсірмелер)',
'exif-exposureprogram-8' => 'Дерелей әдісі (арты шоғырланған дерелей түсірмелер)',

'exif-subjectdistance-value' => '$1 m',

'exif-meteringmode-0'   => 'Белгісіз',
'exif-meteringmode-1'   => 'Біркелкі',
'exif-meteringmode-2'   => 'Бұлдыр дақ',
'exif-meteringmode-3'   => 'БірДақты',
'exif-meteringmode-4'   => 'КөпДақты',
'exif-meteringmode-5'   => 'Өрнекті',
'exif-meteringmode-6'   => 'Жыртынды',
'exif-meteringmode-255' => 'Басқа',

'exif-lightsource-0'   => 'Белгісіз',
'exif-lightsource-1'   => 'Күн жарығы',
'exif-lightsource-2'   => 'Күнжарықты шам',
'exif-lightsource-3'   => 'Қыздырғышты шам',
'exif-lightsource-4'   => 'Жарқылдағыш',
'exif-lightsource-9'   => 'Ашық күн',
'exif-lightsource-10'  => 'Бұлынғыр күн',
'exif-lightsource-11'  => 'Көленкелі',
'exif-lightsource-12'  => 'Күнжарықты шам (D 5700–7100 K)',
'exif-lightsource-13'  => 'Күнжарықты шам (N 4600–5400 K)',
'exif-lightsource-14'  => 'Күнжарықты шам (W 3900–4500 K)',
'exif-lightsource-15'  => 'Күнжарықты шам (WW 3200–3700 K)',
'exif-lightsource-17'  => 'Қалыпты жарық қайнары A',
'exif-lightsource-18'  => 'Қалыпты жарық қайнары B',
'exif-lightsource-19'  => 'Қалыпты жарық қайнары C',
'exif-lightsource-24'  => 'Студиялық ISO күнжарықты шам',
'exif-lightsource-255' => 'Басқа жарық қайнары',

'exif-focalplaneresolutionunit-2' => 'дюйм',

'exif-sensingmethod-1' => 'Анықталмаған',
'exif-sensingmethod-2' => '1-чипті аумақты түссезгіш',
'exif-sensingmethod-3' => '2-чипті аумақты түссезгіш',
'exif-sensingmethod-4' => '3-чипті аумақты түссезгіш',
'exif-sensingmethod-5' => 'Кезекті аумақты түссезгіш',
'exif-sensingmethod-7' => '3-сызықты түссезгіш',
'exif-sensingmethod-8' => 'Кезекті сызықты түссезгіш',

'exif-scenetype-1' => 'Тікелей түсірілген фотосурет',

'exif-customrendered-0' => 'Қалыпты өңдету',
'exif-customrendered-1' => 'Қосымша өңдету',

'exif-exposuremode-0' => 'Өздіктік ұсталымдау',
'exif-exposuremode-1' => 'Қолмен ұсталымдау',
'exif-exposuremode-2' => 'Өздіктік жарқылдау',

'exif-whitebalance-0' => 'Ақ түсінің өздіктік тендестіру',
'exif-whitebalance-1' => 'Ақ түсінің қолмен тендестіру',

'exif-scenecapturetype-0' => 'Қалыпты',
'exif-scenecapturetype-1' => 'Дерелей',
'exif-scenecapturetype-2' => 'Тірелей',
'exif-scenecapturetype-3' => 'Түнгі сахна',

'exif-gaincontrol-0' => 'Жоқ',
'exif-gaincontrol-1' => 'Төмен зораю',
'exif-gaincontrol-2' => 'Жоғары зораю',
'exif-gaincontrol-3' => 'Төмен баяулау',
'exif-gaincontrol-4' => 'Жоғары баяулау',

'exif-contrast-0' => 'Қалыпты',
'exif-contrast-1' => 'Ұян',
'exif-contrast-2' => 'Тұрпайы',

'exif-saturation-0' => 'Қалыпты',
'exif-saturation-1' => 'Төмен қанықты',
'exif-saturation-2' => 'Жоғары қанықты',

'exif-sharpness-0' => 'Қалыпты',
'exif-sharpness-1' => 'Ұян',
'exif-sharpness-2' => 'Тұрпайы',

'exif-subjectdistancerange-0' => 'Белгісіз',
'exif-subjectdistancerange-1' => 'Таяу түсірілген',
'exif-subjectdistancerange-2' => 'Жақын түсірілген',
'exif-subjectdistancerange-3' => 'Алыс түсірілген',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Солтүстік бойлығы',
'exif-gpslatitude-s' => 'Оңтүстік бойлығы',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Шығыс ендігі',
'exif-gpslongitude-w' => 'Батыс ендігі',

'exif-gpsstatus-a' => 'Өлшеу ұласуда',
'exif-gpsstatus-v' => 'Өлшеу өзара әрекетте',

'exif-gpsmeasuremode-2' => '2-бағыттық өлшем',
'exif-gpsmeasuremode-3' => '3-бағыттық өлшем',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mil/h',
'exif-gpsspeed-n' => 'knot',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Шын бағыт',
'exif-gpsdirection-m' => 'Магнитты бағыт',

# External editor support
'edit-externally'      => 'Бұл файлды сыртқы құрал/бағдарлама арқылы өңдеу',
'edit-externally-help' => 'Көбірек ақпарат үшін [http://meta.wikimedia.org/wiki/Help:External_editors орнату нұсқауларын] қараңыз.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'барлығын',
'imagelistall'     => 'барлығы',
'watchlistall1'    => 'барлығы',
'watchlistall2'    => 'барлық',
'namespacesall'    => 'барлығы',

# E-mail address confirmation
'confirmemail'            => 'Е-пошта жайын куәландыру',
'confirmemail_noemail'    => '[[{{ns:special}}:Preferences|Қатысушы баптауыңызда]] жарамды е-пошта жайын енгізбепсіз.',
'confirmemail_text'       => 'Бұл уикиде е-пошта қасиеттерін пайдаланудың алдынан е-пошта жайыңызды
куәландыру қажет. Өзіңіздің жайыңызға куәландыру хатын жіберу үшін төмендегі түймені нұқыңыз.
Хаттың ішінде арнайы коды бар сілтеме кірістірледі;	е-пошта жайыңыздың жарамдығын куәландыру үшін
сілтемені шолғыштың мекен-жай жолағына енгізіп ашыңыз.',
'confirmemail_pending'    => '<div class="error">
Растау белгілемеңіз хатпен жіберіліпті түге; егер тіркелгіңізді 
жуықта істесеңіз, жаңа белгіле сұранысын жіберу алдынан 
хат келуін біршама минөт күте тұрыңыз.
</div>',
'confirmemail_send'       => 'Куәландыру кодын жіберу',
'confirmemail_sent'       => 'Куәландыру хаты жіберілді.',
'confirmemail_oncreate'   => 'Растау белгілемесі е-пошта адресіңізге жіберілді.
Бұл белгілеме кіру үдірісіне керегі жоқ, бірақ ол е-пошта негізіндегі
уики қасиеттерді ендіру үшін жетістіруіңіз қажет.',
'confirmemail_sendfailed' => 'Куәландыру хаты жіберілмеді. Енгізілген жайды жарамсыз әрітеріне тексеріп шығыңыз.

Пошта жібергіштің қайтарғаны: $1',
'confirmemail_invalid'    => 'Куәландыру коды жарамсыз. Кодтың мерзімі біткен шығар.',
'confirmemail_needlogin'  => 'Е-пошта жайыңызды куәландыру үшін $1 қажет.',
'confirmemail_success'    => 'Е-пошта жайыңыз куәландырылды. Енді Уикиге кіріп жұмысқа кірісуге болады',
'confirmemail_loggedin'   => 'Е-пошта жайыңыз куәландырылды.',
'confirmemail_error'      => 'Куәландыруыңызды сақтағанда белгісіз қате болды.',
'confirmemail_subject'    => '{{SITENAME}} торабынан е-пошта жайыңызды куәландыру хаты',
'confirmemail_body'       => "Кейбіреу, мына $1 IP жайынан, өзіңіз болуы мүмкін,
{{SITENAME}} жобасындағы Е-пошта жайын қолданып «$2» тіркелгі жасапты.

Осы тіркелгі растан сіздікі екенін куәландыру үшін, және {{SITENAME}} жобасының
е-пошта қасиеттерін белсендіру үшін, мына сілтемені шолғышпен ашыңыз:

$3

Бұл сіздікі '''емес''' болса, сілтемеге ермеңіз. Куәландыру кодының
мерзімі $4 кезінде бітеді.",

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'Дәл сәйкесін сынап көріңіз',
'searchfulltext' => 'Толық мәтінімен іздеу',
'createarticle'  => 'Бетті бастау',

# Scary transclusion
'scarytranscludedisabled' => '[Уики-ара кірегу өшірілген]',
'scarytranscludefailed'   => '[$1 бетіне үлгі өңдету сәтсіз бітті; кешіріңіз]',
'scarytranscludetoolong'  => '[URL жайы тым ұзын; кешіріңіз]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Бұл беттің аңыстаулары:<br />
$1
</div>',
'trackbackremove'   => '([$1 Жойылды])',
'trackbacklink'     => 'Аңыстау',
'trackbackdeleteok' => 'Аңыстау жоюы сәтті өтті.',

# Delete conflict
'deletedwhileediting' => 'Назар салыңыз:Сіз бұл беттің өңдеуін бастағанда, осы бет жойылды!',
'confirmrecreate'     => "Сіз бұл беттің өндеуін бастағанда [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|талқылауы]]) осы бетті жойды, көрсеткен себебі:
: ''$2''
Осы бетті шынынан қайта жасауын растаңыз.",
'recreate'            => 'Қайта жасау',

'unit-pixel' => ' px',

# HTML dump
'redirectingto' => '[[$1]] бетіне айдатуда…',

# action=purge
'confirm_purge'        => 'Қосалқы қалтадағы осы бетін тазалаймыз ба?<br /><br />$1',
'confirm_purge_button' => 'Жарайды',

'youhavenewmessagesmulti' => '$1 дегенге жаңа хабарлар түсті',

'searchcontaining' => "Мына сөзі бар бет арасынан іздеу: ''$1''.",
'searchnamed'      => "Мына атаулы бет арасынан іздеу: ''$1''.",
'articletitles'    => "Атаулары мынадан басталған беттер: ''$1''",
'hideresults'      => 'Нәтижелерді жасыр',

# DISPLAYTITLE
'displaytitle' => '(Бұл беттің сілтемесі: [[$1]])',

'loginlanguagelabel' => 'Тіл: $1',

# Multipage image navigation
'imgmultipageprev'   => '← алдыңғы бетке',
'imgmultipagenext'   => 'келесі бетке →',
'imgmultigo'         => 'Өту!',
'imgmultigotopre'    => 'Мына бетке өту',
'imgmultiparseerror' => 'Сурет файлы қираған немесе дұрыс емес, сондықтан {{SITENAME}} бет тізімін көрсете алмайды.',

# Table pager
'ascending_abbrev'         => 'өсу',
'descending_abbrev'        => 'кему',
'table_pager_next'         => 'Келесі бетке',
'table_pager_prev'         => 'Алдыңғы бетке',
'table_pager_first'        => 'Алғашқы бетке',
'table_pager_last'         => 'Соңғы бетке',
'table_pager_limit'        => 'Бет сайын $1 дана көрсет',
'table_pager_limit_submit' => 'Өту',
'table_pager_empty'        => 'Еш нәтиже жоқ',

# Auto-summaries
'autosumm-blank'   => 'Беттің барлық мағлұматын аластатты',
'autosumm-replace' => 'Бетті «$1» дегенмен алмастырды',
'autoredircomment' => '[[$1]] дегенге айдады', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Жаңа бетте: $1',

# Size units
'size-bytes'     => '$1 байт',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Жүктеуде…',
'livepreview-ready'   => 'Жүктеуде… Дайын!',
'livepreview-failed'  => 'Тура қарап шығу амалы болмады!<br />Кәдімгі қарап шығу әдісін байқап көріңіз.',
'livepreview-error'   => 'Мынаған қосылу амалы болмады: $1 «$2»<br />Кәдімгі қарап шығу әдісін байқап көріңіз.',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 секундтан жаңалау өзгерістер бұл тізімде көрсетілмеуі мүмкін.',
'lag-warn-high'   => 'Дерекқор сервері зор кешігуі себебінен, $1 секундтан жаңалау өзгерістер
бұл тізімде көрсетілмеуі мүмкін.',

);

?>
