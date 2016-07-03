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

$digitTransformTable = [
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
];

$separatorTransformTable = [
	'.' => '٫', # &#x066b;
	',' => '٬', # &#x066c;
];

$fallback8bitEncoding = 'windows-1256';

$namespaceNames = [
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
];

$namespaceAliases = [
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
];

$datePreferences = [
	'default',
	'mdy',
	'dmy',
	'ymd',
	'yyyy-mm-dd',
	'persian',
	'hebrew',
	'ISO 8601',
];

$defaultDateFormat = 'ymd';

$datePreferenceMigrationMap = [
	'default',
	'mdy',
	'dmy',
	'ymd'
];

$dateFormats = [
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
];

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
$magicWords = [
	'redirect'                  => [ '0', '#ايداۋ', '#АЙДАУ', '#REDIRECT' ],
	'notoc'                     => [ '0', '__مازمۇنسىز__', '__مسىز__', '__МАЗМҰНСЫЗ__', '__МСЫЗ__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__قويماسىز__', '__قسىز__', '__ҚОЙМАСЫЗ__', '__ҚСЫЗ__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__مازمۇنداتقىزۋ__', '__مقىزۋ__', '__МАЗМҰНДАТҚЫЗУ__', '__МҚЫЗУ__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__مازمۇنى__', '__مزمن__', '__МАЗМҰНЫ__', '__МЗМН__', '__TOC__' ],
	'noeditsection'             => [ '0', '__بولىدىموندەمەۋ__', '__بولىموندەتكىزبەۋ__', '__БӨЛІДІМӨНДЕМЕУ__', '__БӨЛІМӨНДЕТКІЗБЕУ__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'اعىمداعىاي', 'АҒЫМДАҒЫАЙ', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'اعىمداعىاياتاۋى', 'АҒЫМДАҒЫАЙАТАУЫ', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'اعىمداعىايىلىكاتاۋى', 'АҒЫМДАҒЫАЙІЛІКАТАУЫ', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'اعىمداعىايجىيىر', 'اعىمداعىايقىسقا', 'АҒЫМДАҒЫАЙЖИЫР', 'АҒЫМДАҒЫАЙҚЫСҚА', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'اعىمداعىكۇن', 'АҒЫМДАҒЫКҮН', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'اعىمداعىكۇن2', 'АҒЫМДАҒЫКҮН2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'اعىمداعىكۇناتاۋى', 'АҒЫМДАҒЫКҮНАТАУЫ', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'اعىمداعىجىل', 'АҒЫМДАҒЫЖЫЛ', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'اعىمداعىۋاقىت', 'АҒЫМДАҒЫУАҚЫТ', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'اعىمداعىساعات', 'АҒЫМДАҒЫСАҒАТ', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'جەرگىلىكتىاي', 'ЖЕРГІЛІКТІАЙ', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'جەرگىلىكتىاياتاۋى', 'ЖЕРГІЛІКТІАЙАТАУЫ', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'جەرگىلىكتىايىلىكاتاۋى', 'ЖЕРГІЛІКТІАЙІЛІКАТАУЫ', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'جەرگىلىكتىايجىيىر', 'جەرگىلىكتىايقىسقاشا', 'جەرگىلىكتىايقىسقا', 'ЖЕРГІЛІКТІАЙЖИЫР', 'ЖЕРГІЛІКТІАЙҚЫСҚАША', 'ЖЕРГІЛІКТІАЙҚЫСҚА', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'جەرگىلىكتىكۇن', 'ЖЕРГІЛІКТІКҮН', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'جەرگىلىكتىكۇن2', 'ЖЕРГІЛІКТІКҮН2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'جەرگىلىكتىكۇناتاۋى', 'ЖЕРГІЛІКТІКҮНАТАУЫ', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'جەرگىلىكتىجىل', 'ЖЕРГІЛІКТІЖЫЛ', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'جەرگىلىكتىۋاقىت', 'ЖЕРГІЛІКТІУАҚЫТ', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'جەرگىلىكتىساعات', 'ЖЕРГІЛІКТІСАҒАТ', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'بەتسانى', 'БЕТСАНЫ', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'ماقالاسانى', 'МАҚАЛАСАНЫ', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'فايلسانى', 'ФАЙЛСАНЫ', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'قاتىسۋشىسانى', 'ҚАТЫСУШЫСАНЫ', 'NUMBEROFUSERS' ],
	'numberofedits'             => [ '1', 'وڭدەمەسانى', 'تۇزەتۋسانى', 'ӨҢДЕМЕСАНЫ', 'ТҮЗЕТУСАНЫ', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'بەتاتاۋى', 'БЕТАТАУЫ', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'بەتاتاۋى2', 'БЕТАТАУЫ2', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'ەسىماياسى', 'ЕСІМАЯСЫ', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ەسىماياسى2', 'ЕСІМАЯСЫ2', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'تالقىلاۋاياسى', 'ТАЛҚЫЛАУАЯСЫ', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'تالقىلاۋاياسى2', 'ТАЛҚЫЛАУАЯСЫ2', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'تاقىرىپبەتى', 'ماقالابەتى', 'ТАҚЫРЫПБЕТІ', 'МАҚАЛАБЕТІ', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'تاقىرىپبەتى2', 'ماقالابەتى2', 'ТАҚЫРЫПБЕТІ2', 'МАҚАЛАБЕТІ2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'تولىقبەتاتاۋى', 'ТОЛЫҚБЕТАТАУЫ', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'تولىقبەتاتاۋى2', 'ТОЛЫҚБЕТАТАУЫ2', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'بەتشەاتاۋى', 'استىڭعىبەتاتاۋى', 'БЕТШЕАТАУЫ', 'АСТЫҢҒЫБЕТАТАУЫ', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'بەتشەاتاۋى2', 'استىڭعىبەتاتاۋى2', 'БЕТШЕАТАУЫ2', 'АСТЫҢҒЫБЕТАТАУЫ2', 'SUBPAGENAMEE' ],
	'basepagename'              => [ '1', 'نەگىزگىبەتاتاۋى', 'НЕГІЗГІБЕТАТАУЫ', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'نەگىزگىبەتاتاۋى2', 'НЕГІЗГІБЕТАТАУЫ2', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'تالقىلاۋبەتاتاۋى', 'ТАЛҚЫЛАУБЕТАТАУЫ', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'تالقىلاۋبەتاتاۋى2', 'ТАЛҚЫЛАУБЕТАТАУЫ2', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', 'تاقىرىپبەتاتاۋى', 'ماقالابەتاتاۋى', 'ТАҚЫРЫПБЕТАТАУЫ', 'МАҚАЛАБЕТАТАУЫ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'تاقىرىپبەتاتاۋى2', 'ماقالابەتاتاۋى2', 'ТАҚЫРЫПБЕТАТАУЫ2', 'МАҚАЛАБЕТАТАУЫ2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'msg'                       => [ '0', 'حبر:', 'ХБР:', 'MSG:' ],
	'subst'                     => [ '0', 'بادەل:', 'БӘДЕЛ:', 'SUBST:' ],
	'msgnw'                     => [ '0', 'ۋىيكىيسىزحبر:', 'УИКИСІЗХБР:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'نوباي', 'нобай', 'thumbnail', 'thumb' ],
	'img_manualthumb'           => [ '1', 'نوباي=$1', 'нобай=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'وڭعا', 'وڭ', 'оңға', 'оң', 'right' ],
	'img_left'                  => [ '1', 'سولعا', 'سول', 'солға', 'сол', 'left' ],
	'img_none'                  => [ '1', 'ەشقانداي', 'جوق', 'ешқандай', 'жоқ', 'none' ],
	'img_width'                 => [ '1', '$1 نۇكتە', '$1 нүкте', '$1px' ],
	'img_center'                => [ '1', 'ورتاعا', 'ورتا', 'ортаға', 'орта', 'center', 'centre' ],
	'img_framed'                => [ '1', 'سۇرمەلى', 'сүрмелі', 'framed', 'enframed', 'frame' ],
	'img_frameless'             => [ '1', 'سۇرمەسىز', 'сүрмесіз', 'frameless' ],
	'img_page'                  => [ '1', 'بەت=$1', 'بەت $1', 'бет=$1', 'бет $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'تىكتى', 'تىكتىك=$1', 'تىكتىك $1', 'тікті', 'тіктік=$1', 'тіктік $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'جىييەكتى', 'жиекті', 'border' ],
	'img_baseline'              => [ '1', 'تىرەكجول', 'тірекжол', 'baseline' ],
	'img_sub'                   => [ '1', 'استىلىعى', 'است', 'астылығы', 'аст', 'sub' ],
	'img_super'                 => [ '1', 'ۇستىلىگى', 'ۇست', 'үстілігі', 'үст', 'super', 'sup' ],
	'img_top'                   => [ '1', 'ۇستىنە', 'үстіне', 'top' ],
	'img_text_top'              => [ '1', 'ماتىن-ۇستىندە', 'мәтін-үстінде', 'text-top' ],
	'img_middle'                => [ '1', 'ارالىعىنا', 'аралығына', 'middle' ],
	'img_bottom'                => [ '1', 'استىنا', 'астына', 'bottom' ],
	'img_text_bottom'           => [ '1', 'ماتىن-استىندا', 'мәтін-астында', 'text-bottom' ],
	'int'                       => [ '0', 'ىشكى:', 'ІШКІ:', 'INT:' ],
	'sitename'                  => [ '1', 'توراپاتاۋى', 'ТОРАПАТАУЫ', 'SITENAME' ],
	'ns'                        => [ '0', 'ەا:', 'ەسىمايا:', 'ЕА:', 'ЕСІМАЯ:', 'NS:' ],
	'localurl'                  => [ '0', 'جەرگىلىكتىجاي:', 'ЖЕРГІЛІКТІЖАЙ:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'جەرگىلىكتىجاي2:', 'ЖЕРГІЛІКТІЖАЙ2:', 'LOCALURLE:' ],
	'server'                    => [ '0', 'سەرۆەر', 'СЕРВЕР', 'SERVER' ],
	'servername'                => [ '0', 'سەرۆەراتاۋى', 'СЕРВЕРАТАУЫ', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'امىرجولى', 'ӘМІРЖОЛЫ', 'SCRIPTPATH' ],
	'grammar'                   => [ '0', 'سەپتىگى:', 'سەپتىك:', 'СЕПТІГІ:', 'СЕПТІК:', 'GRAMMAR:' ],
	'notitleconvert'            => [ '0', '__تاقىرىپاتىنتۇرلەندىرگىزبەۋ__', '__تاتجوق__', '__اتاۋالماستىرعىزباۋ__', '__ااباۋ__', '__ТАҚЫРЫПАТЫНТҮРЛЕНДІРГІЗБЕУ__', '__ТАТЖОҚ__', '__АТАУАЛМАСТЫРҒЫЗБАУ__', '__ААБАУ__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__ماعلۇماتىنتۇرلەندىرگىزبەۋ__', '__ماتجوق__', '__ماعلۇماتالماستىرعىزباۋ__', '__ماباۋ__', '__МАҒЛҰМАТЫНТҮРЛЕНДІРГІЗБЕУ__', '__МАТЖОҚ__', '__МАҒЛҰМАТАЛМАСТЫРҒЫЗБАУ__', '__МАБАУ__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'اعىمداعىاپتاسى', 'اعىمداعىاپتا', 'АҒЫМДАҒЫАПТАСЫ', 'АҒЫМДАҒЫАПТА', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'اعىمداعىاپتاكۇنى', 'АҒЫМДАҒЫАПТАКҮНІ', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'جەرگىلىكتىاپتاسى', 'جەرگىلىكتىاپتا', 'ЖЕРГІЛІКТІАПТАСЫ', 'ЖЕРГІЛІКТІАПТА', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'جەرگىلىكتىاپتاكۇنى', 'ЖЕРГІЛІКТІАПТАКҮНІ', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'تۇزەتۋنومىرٴى', 'نۇسقانومىرٴى', 'ТҮЗЕТУНӨМІРІ', 'НҰСҚАНӨМІРІ', 'REVISIONID' ],
	'revisionday'               => [ '1', 'تۇزەتۋكۇنى', 'نۇسقاكۇنى', 'ТҮЗЕТУКҮНІ', 'НҰСҚАКҮНІ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'تۇزەتۋكۇنى2', 'نۇسقاكۇنى2', 'ТҮЗЕТУКҮНІ2', 'НҰСҚАКҮНІ2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'تۇزەتۋايى', 'نۇسقاايى', 'ТҮЗЕТУАЙЫ', 'НҰСҚААЙЫ', 'REVISIONMONTH' ],
	'revisionyear'              => [ '1', 'تۇزەتۋجىلى', 'نۇسقاجىلى', 'ТҮЗЕТУЖЫЛЫ', 'НҰСҚАЖЫЛЫ', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'تۇزەتۋۋاقىتىتاڭباسى', 'نۇسقاۋاقىتتۇيىندەمەسى', 'ТҮЗЕТУУАҚЫТЫТАҢБАСЫ', 'НҰСҚАУАҚЫТТҮЙІНДЕМЕСІ', 'REVISIONTIMESTAMP' ],
	'plural'                    => [ '0', 'كوپشەتۇرى:', 'كوپشە:', 'КӨПШЕТҮРІ:', 'КӨПШЕ:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'تولىقجايى:', 'تولىقجاي:', 'ТОЛЫҚЖАЙЫ:', 'ТОЛЫҚЖАЙ:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'تولىقجايى2:', 'تولىقجاي2:', 'ТОЛЫҚЖАЙЫ2:', 'ТОЛЫҚЖАЙ2:', 'FULLURLE:' ],
	'lcfirst'                   => [ '0', 'كا1:', 'كىشىارىپپەن1:', 'КӘ1:', 'КІШІӘРІППЕН1:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'با1:', 'باسارىپپەن1:', 'БӘ1:', 'БАСӘРІППЕН1:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'كا:', 'كىشىارىپپەن:', 'КӘ:', 'КІШІӘРІППЕН:', 'LC:' ],
	'uc'                        => [ '0', 'با:', 'باسارىپپەن:', 'БӘ:', 'БАСӘРІППЕН:', 'UC:' ],
	'raw'                       => [ '0', 'قام:', 'ҚАМ:', 'RAW:' ],
	'displaytitle'              => [ '1', 'كورسەتىلەتىناتاۋ', 'КӨРІНЕТІНТАҚЫРЫАПАТЫ', 'КӨРСЕТІЛЕТІНАТАУ', 'DISPLAYTITLE' ],
	'rawsuffix'                 => [ '1', 'ق', 'Қ', 'R' ],
	'newsectionlink'            => [ '1', '__جاڭابولىمسىلتەمەسى__', '__ЖАҢАБӨЛІМСІЛТЕМЕСІ__', '__NEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'باعدارلامانۇسقاسى', 'БАҒДАРЛАМАНҰСҚАСЫ', 'CURRENTVERSION' ],
	'urlencode'                 => [ '0', 'جايدىمۇقامداۋ:', 'ЖАЙДЫМҰҚАМДАУ:', 'URLENCODE:' ],
	'anchorencode'              => [ '0', 'جاكىردىمۇقامداۋ', 'ЖӘКІРДІМҰҚАМДАУ', 'ANCHORENCODE' ],
	'currenttimestamp'          => [ '1', 'اعىمداعىۋاقىتتۇيىندەمەسى', 'اعىمداعىۋاقىتتۇيىن', 'АҒЫМДАҒЫУАҚЫТТҮЙІНДЕМЕСІ', 'АҒЫМДАҒЫУАҚЫТТҮЙІН', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'جەرگىلىكتىۋاقىتتۇيىندەمەسى', 'جەرگىلىكتىۋاقىتتۇيىن', 'ЖЕРГІЛІКТІУАҚЫТТҮЙІНДЕМЕСІ', 'ЖЕРГІЛІКТІУАҚЫТТҮЙІН', 'LOCALTIMESTAMP' ],
	'directionmark'             => [ '1', 'باعىتبەلگىسى', 'БАҒЫТБЕЛГІСІ', 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                  => [ '0', '#تىل:', '#ТІЛ:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'ماعلۇماتتىلى', 'МАҒЛҰМАТТІЛІ', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'ەسىمايابەتسانى:', 'ەابەتسانى:', 'ايابەتسانى:', 'ЕСІМАЯБЕТСАНЫ:', 'ЕАБЕТСАНЫ:', 'АЯБЕТСАНЫ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'اكىمشىسانى', 'ӘКІМШІСАНЫ', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'سانپىشىمى', 'САНПІШІМІ', 'FORMATNUM' ],
	'padleft'                   => [ '0', 'سولعاىعىس', 'سولىعىس', 'СОЛҒАЫҒЫС', 'СОЛЫҒЫС', 'PADLEFT' ],
	'padright'                  => [ '0', 'وڭعاىعىس', 'وڭىعىس', 'ОҢҒАЫҒЫС', 'ОҢЫҒЫС', 'PADRIGHT' ],
	'special'                   => [ '0', 'ارنايى', 'арнайы', 'special' ],
	'defaultsort'               => [ '1', 'ادەپكىسۇرىپتاۋ:', 'ادەپكىساناتسۇرىپتاۋ:', 'ادەپكىسۇرىپتاۋكىلتى:', 'ادەپكىسۇرىپ:', 'ӘДЕПКІСҰРЫПТАУ:', 'ӘДЕПКІСАНАТСҰРЫПТАУ:', 'ӘДЕПКІСҰРЫПТАУКІЛТІ:', 'ӘДЕПКІСҰРЫП:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'فايلمەكەنى:', 'ФАЙЛМЕКЕНІ:', 'FILEPATH:' ],
	'tag'                       => [ '0', 'بەلگى', 'белгі', 'tag' ],
	'hiddencat'                 => [ '1', '__جاسىرىنسانات__', '__ЖАСЫРЫНСАНАТ__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'ساناتتاعىبەتتەر', 'САНАТТАҒЫБЕТТЕР', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'بەتمولشەرى', 'БЕТМӨЛШЕРІ', 'PAGESIZE' ],
];

$specialPageAliases = [
	'Allmessages'               => [ 'بارلىق_حابارلار' ],
	'Allpages'                  => [ 'بارلىق_بەتتەر' ],
	'Ancientpages'              => [ 'ەسكى_بەتتەر' ],
	'Block'                     => [ 'جايدى_بۇعاتتاۋ', 'IP_بۇعاتتاۋ' ],
	'Booksources'               => [ 'كىتاپ_قاينارلارى' ],
	'BrokenRedirects'           => [ 'جارامسىز_ايداعىشتار', 'جارامسىز_ايداتۋلار' ],
	'Categories'                => [ 'ساناتتار' ],
	'ChangePassword'            => [ 'قۇپىيا_سوزدى_قايتارۋ' ],
	'Confirmemail'              => [ 'قۇپتاۋ_حات' ],
	'Contributions'             => [ 'ۇلەسى' ],
	'CreateAccount'             => [ 'جاڭا_تىركەلگى', 'تىركەلگى_جاراتۋ' ],
	'Deadendpages'              => [ 'تۇيىق_بەتتەر' ],
	'DoubleRedirects'           => [ 'شىنجىرلى_ايداعىشتار', 'شىنجىرلى_ايداتۋلار' ],
	'Emailuser'                 => [ 'حات_جىبەرۋ' ],
	'Export'                    => [ 'سىرتقا_بەرۋ' ],
	'Fewestrevisions'           => [ 'ەڭ_از_تۇزەتىلگەن' ],
	'Import'                    => [ 'سىرتتان_الۋ' ],
	'Invalidateemail'           => [ 'قۇپتاماۋ_حاتى' ],
	'BlockList'                 => [ 'بۇعاتتالعاندار' ],
	'Listadmins'                => [ 'اكىمشىلەر', 'اكىمشى_تىزىمى' ],
	'Listbots'                  => [ 'بوتتار', 'ٴبوتتار_ٴتىزىمى' ],
	'Listfiles'                 => [ 'سۋرەت_تىزىمى' ],
	'Listgrouprights'           => [ 'توپ_قۇقىقتارى_تىزىمى' ],
	'Listredirects'             => [ 'ٴايداتۋ_ٴتىزىمى' ],
	'Listusers'                 => [ 'قاتىسۋشىلار', 'قاتىسۋشى_تىزىمى' ],
	'Lockdb'                    => [ 'دەرەكقوردى_قۇلىپتاۋ' ],
	'Log'                       => [ 'جۋرنال', 'جۋرنالدار' ],
	'Lonelypages'               => [ 'ساياق_بەتتەر' ],
	'Longpages'                 => [ 'ۇزىن_بەتتەر', 'ۇلكەن_بەتتەر' ],
	'MergeHistory'              => [ 'تارىيح_بىرىكتىرۋ' ],
	'MIMEsearch'                => [ 'MIME_تۇرىمەن_ىزدەۋ' ],
	'Mostcategories'            => [ 'ەڭ_كوپ_ساناتتار_بارى' ],
	'Mostimages'                => [ 'ەڭ_كوپ_پايدالانىلعان_سۋرەتتەر', 'ەڭ_كوپ_سۋرەتتەر_بارى' ],
	'Mostlinked'                => [ 'ەڭ_كوپ_سىلتەنگەن_بەتتەر' ],
	'Mostlinkedcategories'      => [ 'ەڭ_كوپ_پايدالانىلعان_ساناتتار', 'ەڭ_كوپ_سىلتەنگەن_ساناتتار' ],
	'Mostlinkedtemplates'       => [ 'ەڭ_كوپ_پايدالانىلعان_ۇلگىلەر', 'ەڭ_كوپ_سىلتەنگەن_ۇلگىلەر' ],
	'Mostrevisions'             => [ 'ەڭ_كوپ_تۇزەتىلگەن', 'ەڭ_كوپ_نۇسقالار_بارى' ],
	'Movepage'                  => [ 'بەتتى_جىلجىتۋ' ],
	'Mycontributions'           => [ 'ۇلەسىم' ],
	'Mypage'                    => [ 'جەكە_بەتىم' ],
	'Mytalk'                    => [ 'تالقىلاۋىم' ],
	'Newimages'                 => [ 'جاڭا_سۋرەتتەر' ],
	'Newpages'                  => [ 'جاڭا_بەتتەر' ],
	'Preferences'               => [ 'باپتالىمدار', 'باپتاۋ' ],
	'Prefixindex'               => [ 'ٴباستاۋىش_ٴتىزىمى' ],
	'Protectedpages'            => [ 'قورعالعان_بەتتەر' ],
	'Protectedtitles'           => [ 'قورعالعان_تاقىرىپتار', 'قورعالعان_اتاۋلار' ],
	'Randompage'                => [ 'كەزدەيسوق', 'كەزدەيسوق_بەت' ],
	'Randomredirect'            => [ 'Кедейсоқ_айдағыш', 'Кедейсоқ_айдату' ],
	'Recentchanges'             => [ 'جۋىقتاعى_وزگەرىستەر' ],
	'Recentchangeslinked'       => [ 'سىلتەنگەندەردىڭ_وزگەرىستەرى' ],
	'Revisiondelete'            => [ 'تۇزەتۋ_جويۋ', 'نۇسقانى_جويۋ' ],
	'Search'                    => [ 'ىزدەۋ' ],
	'Shortpages'                => [ 'قىسقا_بەتتەر' ],
	'Specialpages'              => [ 'ارنايى_بەتتەر' ],
	'Statistics'                => [ 'ساناق' ],
	'Uncategorizedcategories'   => [ 'ساناتسىز_ساناتتار' ],
	'Uncategorizedimages'       => [ 'ساناتسىز_سۋرەتتەر' ],
	'Uncategorizedpages'        => [ 'ساناتسىز_بەتتەر' ],
	'Uncategorizedtemplates'    => [ 'ساناتسىز_ۇلگىلەر' ],
	'Undelete'                  => [ 'جويۋدى_بولدىرماۋ', 'جويىلعاندى_قايتارۋ' ],
	'Unlockdb'                  => [ 'دەرەكقوردى_قۇلىپتاماۋ' ],
	'Unusedcategories'          => [ 'پايدالانىلماعان_ساناتتار' ],
	'Unusedimages'              => [ 'پايدالانىلماعان_سۋرەتتەر' ],
	'Unusedtemplates'           => [ 'پايدالانىلماعان_ۇلگىلەر' ],
	'Unwatchedpages'            => [ 'باقىلانىلماعان_بەتتەر' ],
	'Upload'                    => [ 'قوتارىپ_بەرۋ', 'قوتارۋ' ],
	'Userlogin'                 => [ 'قاتىسۋشى_كىرۋى' ],
	'Userlogout'                => [ 'قاتىسۋشى_شىعۋى' ],
	'Userrights'                => [ 'قاتىسۋشى_قۇقىقتارى' ],
	'Version'                   => [ 'نۇسقاسى' ],
	'Wantedcategories'          => [ 'تولتىرىلماعان_ساناتتار' ],
	'Wantedpages'               => [ 'تولتىرىلماعان_بەتتەر', 'جارامسىز_سىلتەمەلەر' ],
	'Watchlist'                 => [ 'باقىلاۋ_تىزىمى' ],
	'Whatlinkshere'             => [ 'مىندا_سىلتەگەندەر' ],
	'Withoutinterwiki'          => [ 'ۋىيكىي-ارالىقسىزدار' ],
];

# -------------------------------------------------------------------
# Default messages
# -------------------------------------------------------------------

