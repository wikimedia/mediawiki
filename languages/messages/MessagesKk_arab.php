<?php
/** Kazakh (Arabic script) (قازاقشا (تٴوتە)‏)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AlefZet
 * @author Amire80
 * @author GaiJin
 * @author Lokal Profil
 * @author Urhixidur
 */

/**
 * بۇل قازاقشا تىلدەسۋىنىڭ جەرسىندىرۋ فايلى
 *
 * شەتكى پايدالانۋشىلار: بۇل فايلدى تىكەلەي وڭدەمەڭىز!
 *
 * بۇل فايلداعى وزگەرىستەر باعدارلامالىق جاساقتاما كەزەكتى جاڭارتىلعاندا جوعالتىلادى.
 * ۋىيكىيدە ٴوز باپتالىمدارىڭىزدى ىستەي الاسىز.
 * اكىمشى بوپ كىرگەنىڭىزدە, [[ارنايى:بارلىق حابارلار]] دەگەن بەتكە ٴوتىڭىز دە
 * مىندا تىزىمدەلىنگەن مەدىياۋىيكىي:* سىيپاتى بار بەتتەردى وڭدەڭىز.
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

$fallback8bitEncoding = 'windows-1256';

$namespaceNames = array(
	NS_MEDIA            => 'تاسپا',
	NS_SPECIAL          => 'ارنايى',
	NS_TALK             => 'تالقىلاۋ',
	NS_USER             => 'قاتىسۋشى',
	NS_USER_TALK        => 'قاتىسۋشى_تالقىلاۋى',
	NS_PROJECT_TALK     => '$1_تالقىلاۋى',
	NS_FILE             => 'سۋرەت',
	NS_FILE_TALK        => 'سۋرەت_تالقىلاۋى',
	NS_MEDIAWIKI        => 'مەدىياۋىيكىي',
	NS_MEDIAWIKI_TALK   => 'مەدىياۋىيكىي_تالقىلاۋى',
	NS_TEMPLATE         => 'ۇلگى',
	NS_TEMPLATE_TALK    => 'ۇلگى_تالقىلاۋى',
	NS_HELP             => 'انىقتاما',
	NS_HELP_TALK        => 'انىقتاما_تالقىلاۋى',
	NS_CATEGORY         => 'سانات',
	NS_CATEGORY_TALK    => 'سانات_تالقىلاۋى',
);

$namespaceAliases = array(
	# Aliases to kk-cyrl namespaces
	'Таспа'               => NS_MEDIA,
	'Арнайы'              => NS_SPECIAL,
	'Талқылау'            => NS_TALK,
	'Қатысушы'            => NS_USER,
	'Қатысушы_талқылауы'  => NS_USER_TALK,
	'$1_талқылауы'        => NS_PROJECT_TALK,
	'Сурет'               => NS_FILE,
	'Сурет_талқылауы'     => NS_FILE_TALK,
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
	'Swret'               => NS_FILE,
	'Swret_talqılawı'     => NS_FILE_TALK,
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
	'مەدياۋيكي_تالقىلاۋى'  => NS_MEDIAWIKI_TALK,
	'ٷلگٸ'        => NS_TEMPLATE,
	'ٷلگٸ_تالقىلاۋى'    => NS_TEMPLATE_TALK,
	'ٴۇلگٴى'              => NS_TEMPLATE,
	'ٴۇلگٴى_تالقىلاۋى'    => NS_TEMPLATE_TALK,
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
	'redirect'                  => array( '0', '#ايداۋ', '#АЙДАУ', '#REDIRECT' ),
	'notoc'                     => array( '0', '__مازمۇنسىز__', '__مسىز__', '__МАЗМҰНСЫЗ__', '__МСЫЗ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__قويماسىز__', '__قسىز__', '__ҚОЙМАСЫЗ__', '__ҚСЫЗ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__مازمۇنداتقىزۋ__', '__مقىزۋ__', '__МАЗМҰНДАТҚЫЗУ__', '__МҚЫЗУ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__مازمۇنى__', '__مزمن__', '__МАЗМҰНЫ__', '__МЗМН__', '__TOC__' ),
	'noeditsection'             => array( '0', '__بولىدىموندەمەۋ__', '__بولىموندەتكىزبەۋ__', '__БӨЛІДІМӨНДЕМЕУ__', '__БӨЛІМӨНДЕТКІЗБЕУ__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'اعىمداعىاي', 'АҒЫМДАҒЫАЙ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'اعىمداعىاياتاۋى', 'АҒЫМДАҒЫАЙАТАУЫ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'اعىمداعىايىلىكاتاۋى', 'АҒЫМДАҒЫАЙІЛІКАТАУЫ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'اعىمداعىايجىيىر', 'اعىمداعىايقىسقا', 'АҒЫМДАҒЫАЙЖИЫР', 'АҒЫМДАҒЫАЙҚЫСҚА', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'اعىمداعىكۇن', 'АҒЫМДАҒЫКҮН', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'اعىمداعىكۇن2', 'АҒЫМДАҒЫКҮН2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'اعىمداعىكۇناتاۋى', 'АҒЫМДАҒЫКҮНАТАУЫ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'اعىمداعىجىل', 'АҒЫМДАҒЫЖЫЛ', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'اعىمداعىۋاقىت', 'АҒЫМДАҒЫУАҚЫТ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'اعىمداعىساعات', 'АҒЫМДАҒЫСАҒАТ', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'جەرگىلىكتىاي', 'ЖЕРГІЛІКТІАЙ', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'جەرگىلىكتىاياتاۋى', 'ЖЕРГІЛІКТІАЙАТАУЫ', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'جەرگىلىكتىايىلىكاتاۋى', 'ЖЕРГІЛІКТІАЙІЛІКАТАУЫ', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'جەرگىلىكتىايجىيىر', 'جەرگىلىكتىايقىسقاشا', 'جەرگىلىكتىايقىسقا', 'ЖЕРГІЛІКТІАЙЖИЫР', 'ЖЕРГІЛІКТІАЙҚЫСҚАША', 'ЖЕРГІЛІКТІАЙҚЫСҚА', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'جەرگىلىكتىكۇن', 'ЖЕРГІЛІКТІКҮН', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'جەرگىلىكتىكۇن2', 'ЖЕРГІЛІКТІКҮН2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'جەرگىلىكتىكۇناتاۋى', 'ЖЕРГІЛІКТІКҮНАТАУЫ', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'جەرگىلىكتىجىل', 'ЖЕРГІЛІКТІЖЫЛ', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'جەرگىلىكتىۋاقىت', 'ЖЕРГІЛІКТІУАҚЫТ', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'جەرگىلىكتىساعات', 'ЖЕРГІЛІКТІСАҒАТ', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'بەتسانى', 'БЕТСАНЫ', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ماقالاسانى', 'МАҚАЛАСАНЫ', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'فايلسانى', 'ФАЙЛСАНЫ', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'قاتىسۋشىسانى', 'ҚАТЫСУШЫСАНЫ', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'وڭدەمەسانى', 'تۇزەتۋسانى', 'ӨҢДЕМЕСАНЫ', 'ТҮЗЕТУСАНЫ', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'بەتاتاۋى', 'БЕТАТАУЫ', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'بەتاتاۋى2', 'БЕТАТАУЫ2', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ەسىماياسى', 'ЕСІМАЯСЫ', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ەسىماياسى2', 'ЕСІМАЯСЫ2', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'تالقىلاۋاياسى', 'ТАЛҚЫЛАУАЯСЫ', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'تالقىلاۋاياسى2', 'ТАЛҚЫЛАУАЯСЫ2', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'تاقىرىپبەتى', 'ماقالابەتى', 'ТАҚЫРЫПБЕТІ', 'МАҚАЛАБЕТІ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'تاقىرىپبەتى2', 'ماقالابەتى2', 'ТАҚЫРЫПБЕТІ2', 'МАҚАЛАБЕТІ2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'تولىقبەتاتاۋى', 'ТОЛЫҚБЕТАТАУЫ', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'تولىقبەتاتاۋى2', 'ТОЛЫҚБЕТАТАУЫ2', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'بەتشەاتاۋى', 'استىڭعىبەتاتاۋى', 'БЕТШЕАТАУЫ', 'АСТЫҢҒЫБЕТАТАУЫ', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'بەتشەاتاۋى2', 'استىڭعىبەتاتاۋى2', 'БЕТШЕАТАУЫ2', 'АСТЫҢҒЫБЕТАТАУЫ2', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'نەگىزگىبەتاتاۋى', 'НЕГІЗГІБЕТАТАУЫ', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'نەگىزگىبەتاتاۋى2', 'НЕГІЗГІБЕТАТАУЫ2', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'تالقىلاۋبەتاتاۋى', 'ТАЛҚЫЛАУБЕТАТАУЫ', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'تالقىلاۋبەتاتاۋى2', 'ТАЛҚЫЛАУБЕТАТАУЫ2', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'تاقىرىپبەتاتاۋى', 'ماقالابەتاتاۋى', 'ТАҚЫРЫПБЕТАТАУЫ', 'МАҚАЛАБЕТАТАУЫ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'تاقىرىپبەتاتاۋى2', 'ماقالابەتاتاۋى2', 'ТАҚЫРЫПБЕТАТАУЫ2', 'МАҚАЛАБЕТАТАУЫ2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'حبر:', 'ХБР:', 'MSG:' ),
	'subst'                     => array( '0', 'بادەل:', 'БӘДЕЛ:', 'SUBST:' ),
	'msgnw'                     => array( '0', 'ۋىيكىيسىزحبر:', 'УИКИСІЗХБР:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'نوباي', 'нобай', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'نوباي=$1', 'нобай=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'وڭعا', 'وڭ', 'оңға', 'оң', 'right' ),
	'img_left'                  => array( '1', 'سولعا', 'سول', 'солға', 'сол', 'left' ),
	'img_none'                  => array( '1', 'ەشقانداي', 'جوق', 'ешқандай', 'жоқ', 'none' ),
	'img_width'                 => array( '1', '$1 نۇكتە', '$1 нүкте', '$1px' ),
	'img_center'                => array( '1', 'ورتاعا', 'ورتا', 'ортаға', 'орта', 'center', 'centre' ),
	'img_framed'                => array( '1', 'سۇرمەلى', 'сүрмелі', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'سۇرمەسىز', 'сүрмесіз', 'frameless' ),
	'img_page'                  => array( '1', 'بەت=$1', 'بەت $1', 'бет=$1', 'бет $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'تىكتى', 'تىكتىك=$1', 'تىكتىك $1', 'тікті', 'тіктік=$1', 'тіктік $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'جىييەكتى', 'жиекті', 'border' ),
	'img_baseline'              => array( '1', 'تىرەكجول', 'тірекжол', 'baseline' ),
	'img_sub'                   => array( '1', 'استىلىعى', 'است', 'астылығы', 'аст', 'sub' ),
	'img_super'                 => array( '1', 'ۇستىلىگى', 'ۇست', 'үстілігі', 'үст', 'super', 'sup' ),
	'img_top'                   => array( '1', 'ۇستىنە', 'үстіне', 'top' ),
	'img_text_top'              => array( '1', 'ماتىن-ۇستىندە', 'мәтін-үстінде', 'text-top' ),
	'img_middle'                => array( '1', 'ارالىعىنا', 'аралығына', 'middle' ),
	'img_bottom'                => array( '1', 'استىنا', 'астына', 'bottom' ),
	'img_text_bottom'           => array( '1', 'ماتىن-استىندا', 'мәтін-астында', 'text-bottom' ),
	'int'                       => array( '0', 'ىشكى:', 'ІШКІ:', 'INT:' ),
	'sitename'                  => array( '1', 'توراپاتاۋى', 'ТОРАПАТАУЫ', 'SITENAME' ),
	'ns'                        => array( '0', 'ەا:', 'ەسىمايا:', 'ЕА:', 'ЕСІМАЯ:', 'NS:' ),
	'localurl'                  => array( '0', 'جەرگىلىكتىجاي:', 'ЖЕРГІЛІКТІЖАЙ:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'جەرگىلىكتىجاي2:', 'ЖЕРГІЛІКТІЖАЙ2:', 'LOCALURLE:' ),
	'server'                    => array( '0', 'سەرۆەر', 'СЕРВЕР', 'SERVER' ),
	'servername'                => array( '0', 'سەرۆەراتاۋى', 'СЕРВЕРАТАУЫ', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'امىرجولى', 'ӘМІРЖОЛЫ', 'SCRIPTPATH' ),
	'grammar'                   => array( '0', 'سەپتىگى:', 'سەپتىك:', 'СЕПТІГІ:', 'СЕПТІК:', 'GRAMMAR:' ),
	'notitleconvert'            => array( '0', '__تاقىرىپاتىنتۇرلەندىرگىزبەۋ__', '__تاتجوق__', '__اتاۋالماستىرعىزباۋ__', '__ااباۋ__', '__ТАҚЫРЫПАТЫНТҮРЛЕНДІРГІЗБЕУ__', '__ТАТЖОҚ__', '__АТАУАЛМАСТЫРҒЫЗБАУ__', '__ААБАУ__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__ماعلۇماتىنتۇرلەندىرگىزبەۋ__', '__ماتجوق__', '__ماعلۇماتالماستىرعىزباۋ__', '__ماباۋ__', '__МАҒЛҰМАТЫНТҮРЛЕНДІРГІЗБЕУ__', '__МАТЖОҚ__', '__МАҒЛҰМАТАЛМАСТЫРҒЫЗБАУ__', '__МАБАУ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'اعىمداعىاپتاسى', 'اعىمداعىاپتا', 'АҒЫМДАҒЫАПТАСЫ', 'АҒЫМДАҒЫАПТА', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'اعىمداعىاپتاكۇنى', 'АҒЫМДАҒЫАПТАКҮНІ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'جەرگىلىكتىاپتاسى', 'جەرگىلىكتىاپتا', 'ЖЕРГІЛІКТІАПТАСЫ', 'ЖЕРГІЛІКТІАПТА', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'جەرگىلىكتىاپتاكۇنى', 'ЖЕРГІЛІКТІАПТАКҮНІ', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'تۇزەتۋنومىرٴى', 'نۇسقانومىرٴى', 'ТҮЗЕТУНӨМІРІ', 'НҰСҚАНӨМІРІ', 'REVISIONID' ),
	'revisionday'               => array( '1', 'تۇزەتۋكۇنى', 'نۇسقاكۇنى', 'ТҮЗЕТУКҮНІ', 'НҰСҚАКҮНІ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'تۇزەتۋكۇنى2', 'نۇسقاكۇنى2', 'ТҮЗЕТУКҮНІ2', 'НҰСҚАКҮНІ2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'تۇزەتۋايى', 'نۇسقاايى', 'ТҮЗЕТУАЙЫ', 'НҰСҚААЙЫ', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'تۇزەتۋجىلى', 'نۇسقاجىلى', 'ТҮЗЕТУЖЫЛЫ', 'НҰСҚАЖЫЛЫ', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'تۇزەتۋۋاقىتىتاڭباسى', 'نۇسقاۋاقىتتۇيىندەمەسى', 'ТҮЗЕТУУАҚЫТЫТАҢБАСЫ', 'НҰСҚАУАҚЫТТҮЙІНДЕМЕСІ', 'REVISIONTIMESTAMP' ),
	'plural'                    => array( '0', 'كوپشەتۇرى:', 'كوپشە:', 'КӨПШЕТҮРІ:', 'КӨПШЕ:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'تولىقجايى:', 'تولىقجاي:', 'ТОЛЫҚЖАЙЫ:', 'ТОЛЫҚЖАЙ:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'تولىقجايى2:', 'تولىقجاي2:', 'ТОЛЫҚЖАЙЫ2:', 'ТОЛЫҚЖАЙ2:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'كا1:', 'كىشىارىپپەن1:', 'КӘ1:', 'КІШІӘРІППЕН1:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'با1:', 'باسارىپپەن1:', 'БӘ1:', 'БАСӘРІППЕН1:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'كا:', 'كىشىارىپپەن:', 'КӘ:', 'КІШІӘРІППЕН:', 'LC:' ),
	'uc'                        => array( '0', 'با:', 'باسارىپپەن:', 'БӘ:', 'БАСӘРІППЕН:', 'UC:' ),
	'raw'                       => array( '0', 'قام:', 'ҚАМ:', 'RAW:' ),
	'displaytitle'              => array( '1', 'كورسەتىلەتىناتاۋ', 'КӨРІНЕТІНТАҚЫРЫАПАТЫ', 'КӨРСЕТІЛЕТІНАТАУ', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'ق', 'Қ', 'R' ),
	'newsectionlink'            => array( '1', '__جاڭابولىمسىلتەمەسى__', '__ЖАҢАБӨЛІМСІЛТЕМЕСІ__', '__NEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'باعدارلامانۇسقاسى', 'БАҒДАРЛАМАНҰСҚАСЫ', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'جايدىمۇقامداۋ:', 'ЖАЙДЫМҰҚАМДАУ:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'جاكىردىمۇقامداۋ', 'ЖӘКІРДІМҰҚАМДАУ', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'اعىمداعىۋاقىتتۇيىندەمەسى', 'اعىمداعىۋاقىتتۇيىن', 'АҒЫМДАҒЫУАҚЫТТҮЙІНДЕМЕСІ', 'АҒЫМДАҒЫУАҚЫТТҮЙІН', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'جەرگىلىكتىۋاقىتتۇيىندەمەسى', 'جەرگىلىكتىۋاقىتتۇيىن', 'ЖЕРГІЛІКТІУАҚЫТТҮЙІНДЕМЕСІ', 'ЖЕРГІЛІКТІУАҚЫТТҮЙІН', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'باعىتبەلگىسى', 'БАҒЫТБЕЛГІСІ', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#تىل:', '#ТІЛ:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'ماعلۇماتتىلى', 'МАҒЛҰМАТТІЛІ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'ەسىمايابەتسانى:', 'ەابەتسانى:', 'ايابەتسانى:', 'ЕСІМАЯБЕТСАНЫ:', 'ЕАБЕТСАНЫ:', 'АЯБЕТСАНЫ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'اكىمشىسانى', 'ӘКІМШІСАНЫ', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'سانپىشىمى', 'САНПІШІМІ', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'سولعاىعىس', 'سولىعىس', 'СОЛҒАЫҒЫС', 'СОЛЫҒЫС', 'PADLEFT' ),
	'padright'                  => array( '0', 'وڭعاىعىس', 'وڭىعىس', 'ОҢҒАЫҒЫС', 'ОҢЫҒЫС', 'PADRIGHT' ),
	'special'                   => array( '0', 'ارنايى', 'арнайы', 'special' ),
	'defaultsort'               => array( '1', 'ادەپكىسۇرىپتاۋ:', 'ادەپكىساناتسۇرىپتاۋ:', 'ادەپكىسۇرىپتاۋكىلتى:', 'ادەپكىسۇرىپ:', 'ӘДЕПКІСҰРЫПТАУ:', 'ӘДЕПКІСАНАТСҰРЫПТАУ:', 'ӘДЕПКІСҰРЫПТАУКІЛТІ:', 'ӘДЕПКІСҰРЫП:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'فايلمەكەنى:', 'ФАЙЛМЕКЕНІ:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'بەلگى', 'белгі', 'tag' ),
	'hiddencat'                 => array( '1', '__جاسىرىنسانات__', '__ЖАСЫРЫНСАНАТ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'ساناتتاعىبەتتەر', 'САНАТТАҒЫБЕТТЕР', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'بەتمولشەرى', 'БЕТМӨЛШЕРІ', 'PAGESIZE' ),
);

$specialPageAliases = array(
	'Allmessages'               => array( 'بارلىق_حابارلار' ),
	'Allpages'                  => array( 'بارلىق_بەتتەر' ),
	'Ancientpages'              => array( 'ەسكى_بەتتەر' ),
	'Block'                     => array( 'جايدى_بۇعاتتاۋ', 'IP_بۇعاتتاۋ' ),
	'Booksources'               => array( 'كىتاپ_قاينارلارى' ),
	'BrokenRedirects'           => array( 'جارامسىز_ايداعىشتار', 'جارامسىز_ايداتۋلار' ),
	'Categories'                => array( 'ساناتتار' ),
	'ChangePassword'            => array( 'قۇپىيا_سوزدى_قايتارۋ' ),
	'Confirmemail'              => array( 'قۇپتاۋ_حات' ),
	'Contributions'             => array( 'ۇلەسى' ),
	'CreateAccount'             => array( 'جاڭا_تىركەلگى', 'تىركەلگى_جاراتۋ' ),
	'Deadendpages'              => array( 'تۇيىق_بەتتەر' ),
	'DoubleRedirects'           => array( 'شىنجىرلى_ايداعىشتار', 'شىنجىرلى_ايداتۋلار' ),
	'Emailuser'                 => array( 'حات_جىبەرۋ' ),
	'Export'                    => array( 'سىرتقا_بەرۋ' ),
	'Fewestrevisions'           => array( 'ەڭ_از_تۇزەتىلگەن' ),
	'Import'                    => array( 'سىرتتان_الۋ' ),
	'Invalidateemail'           => array( 'قۇپتاماۋ_حاتى' ),
	'BlockList'                 => array( 'بۇعاتتالعاندار' ),
	'Listadmins'                => array( 'اكىمشىلەر', 'اكىمشى_تىزىمى' ),
	'Listbots'                  => array( 'بوتتار', 'ٴبوتتار_ٴتىزىمى' ),
	'Listfiles'                 => array( 'سۋرەت_تىزىمى' ),
	'Listgrouprights'           => array( 'توپ_قۇقىقتارى_تىزىمى' ),
	'Listredirects'             => array( 'ٴايداتۋ_ٴتىزىمى' ),
	'Listusers'                 => array( 'قاتىسۋشىلار', 'قاتىسۋشى_تىزىمى' ),
	'Lockdb'                    => array( 'دەرەكقوردى_قۇلىپتاۋ' ),
	'Log'                       => array( 'جۋرنال', 'جۋرنالدار' ),
	'Lonelypages'               => array( 'ساياق_بەتتەر' ),
	'Longpages'                 => array( 'ۇزىن_بەتتەر', 'ۇلكەن_بەتتەر' ),
	'MergeHistory'              => array( 'تارىيح_بىرىكتىرۋ' ),
	'MIMEsearch'                => array( 'MIME_تۇرىمەن_ىزدەۋ' ),
	'Mostcategories'            => array( 'ەڭ_كوپ_ساناتتار_بارى' ),
	'Mostimages'                => array( 'ەڭ_كوپ_پايدالانىلعان_سۋرەتتەر', 'ەڭ_كوپ_سۋرەتتەر_بارى' ),
	'Mostlinked'                => array( 'ەڭ_كوپ_سىلتەنگەن_بەتتەر' ),
	'Mostlinkedcategories'      => array( 'ەڭ_كوپ_پايدالانىلعان_ساناتتار', 'ەڭ_كوپ_سىلتەنگەن_ساناتتار' ),
	'Mostlinkedtemplates'       => array( 'ەڭ_كوپ_پايدالانىلعان_ۇلگىلەر', 'ەڭ_كوپ_سىلتەنگەن_ۇلگىلەر' ),
	'Mostrevisions'             => array( 'ەڭ_كوپ_تۇزەتىلگەن', 'ەڭ_كوپ_نۇسقالار_بارى' ),
	'Movepage'                  => array( 'بەتتى_جىلجىتۋ' ),
	'Mycontributions'           => array( 'ۇلەسىم' ),
	'Mypage'                    => array( 'جەكە_بەتىم' ),
	'Mytalk'                    => array( 'تالقىلاۋىم' ),
	'Newimages'                 => array( 'جاڭا_سۋرەتتەر' ),
	'Newpages'                  => array( 'جاڭا_بەتتەر' ),
	'Preferences'               => array( 'باپتالىمدار', 'باپتاۋ' ),
	'Prefixindex'               => array( 'ٴباستاۋىش_ٴتىزىمى' ),
	'Protectedpages'            => array( 'قورعالعان_بەتتەر' ),
	'Protectedtitles'           => array( 'قورعالعان_تاقىرىپتار', 'قورعالعان_اتاۋلار' ),
	'Randompage'                => array( 'كەزدەيسوق', 'كەزدەيسوق_بەت' ),
	'Randomredirect'            => array( 'Кедейсоқ_айдағыш', 'Кедейсоқ_айдату' ),
	'Recentchanges'             => array( 'جۋىقتاعى_وزگەرىستەر' ),
	'Recentchangeslinked'       => array( 'سىلتەنگەندەردىڭ_وزگەرىستەرى' ),
	'Revisiondelete'            => array( 'تۇزەتۋ_جويۋ', 'نۇسقانى_جويۋ' ),
	'Search'                    => array( 'ىزدەۋ' ),
	'Shortpages'                => array( 'قىسقا_بەتتەر' ),
	'Specialpages'              => array( 'ارنايى_بەتتەر' ),
	'Statistics'                => array( 'ساناق' ),
	'Uncategorizedcategories'   => array( 'ساناتسىز_ساناتتار' ),
	'Uncategorizedimages'       => array( 'ساناتسىز_سۋرەتتەر' ),
	'Uncategorizedpages'        => array( 'ساناتسىز_بەتتەر' ),
	'Uncategorizedtemplates'    => array( 'ساناتسىز_ۇلگىلەر' ),
	'Undelete'                  => array( 'جويۋدى_بولدىرماۋ', 'جويىلعاندى_قايتارۋ' ),
	'Unlockdb'                  => array( 'دەرەكقوردى_قۇلىپتاماۋ' ),
	'Unusedcategories'          => array( 'پايدالانىلماعان_ساناتتار' ),
	'Unusedimages'              => array( 'پايدالانىلماعان_سۋرەتتەر' ),
	'Unusedtemplates'           => array( 'پايدالانىلماعان_ۇلگىلەر' ),
	'Unwatchedpages'            => array( 'باقىلانىلماعان_بەتتەر' ),
	'Upload'                    => array( 'قوتارىپ_بەرۋ', 'قوتارۋ' ),
	'Userlogin'                 => array( 'قاتىسۋشى_كىرۋى' ),
	'Userlogout'                => array( 'قاتىسۋشى_شىعۋى' ),
	'Userrights'                => array( 'قاتىسۋشى_قۇقىقتارى' ),
	'Version'                   => array( 'نۇسقاسى' ),
	'Wantedcategories'          => array( 'تولتىرىلماعان_ساناتتار' ),
	'Wantedpages'               => array( 'تولتىرىلماعان_بەتتەر', 'جارامسىز_سىلتەمەلەر' ),
	'Watchlist'                 => array( 'باقىلاۋ_تىزىمى' ),
	'Whatlinkshere'             => array( 'مىندا_سىلتەگەندەر' ),
	'Withoutinterwiki'          => array( 'ۋىيكىي-ارالىقسىزدار' ),
);

# -------------------------------------------------------------------
# Default messages
# -------------------------------------------------------------------

