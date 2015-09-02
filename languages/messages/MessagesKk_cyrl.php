<?php
/** Kazakh (Cyrillic script) (қазақша (кирил)‎)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AlefZet
 * @author Alibek Kisybay
 * @author Amangeldy
 * @author Arystanbek
 * @author Bakytgul Salykhova
 * @author Daniyar
 * @author GaiJin
 * @author Kaztrans
 * @author Nemo bis
 * @author Urhixidur
 */

/**
 * Бұл қазақша тілдесуінің жерсіндіру файлы
 *
 * ШЕТКІ ПАЙДАЛАНУШЫЛАР: БҰЛ ФАЙЛДЫ ТІКЕЛЕЙ ӨҢДЕМЕҢІЗ!
 *
 * Бұл файлдағы өзгерістер бағдарламалық жасақтама кезекті жаңартылғанда жоғалтылады.
 * Уикиде өз бапталымдарыңызды істей аласыз.
 * Әкімші боп кіргеніңізде, [[Арнайы:Барлық хабарлар]] деген бетке өтіңіз де
 * мында тізімделінген МедиаУики:* сипаты бар беттерді өңдеңіз.
 */
$separatorTransformTable = array(
	',' => "\xc2\xa0",
	'.' => ',',
);

$fallback8bitEncoding = 'windows-1251';

$linkTrail = '/^([a-zäçéğıïñöşüýʺʹа-яёәғіқңөұүһٴابپتجحدرزسشعفقكلمنڭەوۇۋۆىيچھ“»]+)(.*)$/sDu';

$namespaceNames = array(
	NS_MEDIA            => 'Таспа',
	NS_SPECIAL          => 'Арнайы',
	NS_TALK             => 'Талқылау',
	NS_USER             => 'Қатысушы',
	NS_USER_TALK        => 'Қатысушы_талқылауы',
	NS_PROJECT_TALK     => '$1_талқылауы',
	NS_FILE             => 'Сурет',
	NS_FILE_TALK        => 'Сурет_талқылауы',
	NS_MEDIAWIKI        => 'МедиаУики',
	NS_MEDIAWIKI_TALK   => 'МедиаУики_талқылауы',
	NS_TEMPLATE         => 'Үлгі',
	NS_TEMPLATE_TALK    => 'Үлгі_талқылауы',
	NS_HELP             => 'Анықтама',
	NS_HELP_TALK        => 'Анықтама_талқылауы',
	NS_CATEGORY         => 'Санат',
	NS_CATEGORY_TALK    => 'Санат_талқылауы',
);

$namespaceAliases = array(
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

	# Aliases to kk-arab namespaces
	'تاسپا'              => NS_MEDIA,
	'ارنايى'              => NS_SPECIAL,
	'تالقىلاۋ'            => NS_TALK,
	'قاتىسۋشى'          => NS_USER,
	'قاتىسۋشى_تالقىلاۋى' => NS_USER_TALK,
	'$1_تالقىلاۋى'        => NS_PROJECT_TALK,
	'سۋرەت'              => NS_FILE,
	'سۋرەت_تالقىلاۋى'    => NS_FILE_TALK,
	'انىقتاما'            => NS_HELP,
	'انىقتاما_تالقىلاۋى'  => NS_HELP_TALK,
	'سانات'              => NS_CATEGORY,
	'سانات_تالقىلاۋى'    => NS_CATEGORY_TALK,
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

	'persian time' => 'H:i',
	'persian date' => 'xij xiF xiY',
	'persian both' => 'xij xiF xiY, H:i',

	'hebrew time' => 'H:i',
	'hebrew date' => 'xjj xjF xjY',
	'hebrew both' => 'H:i, xjj xjF xjY',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$magicWords = array(
	'redirect'                  => array( '0', '#АЙДАУ', '#REDIRECT' ),
	'notoc'                     => array( '0', '__МАЗМҰНСЫЗ__', '__МСЫЗ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__ҚОЙМАСЫЗ__', '__ҚСЫЗ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__МАЗМҰНДАТҚЫЗУ__', '__МҚЫЗУ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__МАЗМҰНЫ__', '__МЗМН__', '__TOC__' ),
	'noeditsection'             => array( '0', '__БӨЛІДІМӨНДЕМЕУ__', '__БӨЛІМӨНДЕТКІЗБЕУ__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'АҒЫМДАҒЫАЙ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'АҒЫМДАҒЫАЙАТАУЫ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'АҒЫМДАҒЫАЙІЛІКАТАУЫ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'АҒЫМДАҒЫАЙЖИЫР', 'АҒЫМДАҒЫАЙҚЫСҚА', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'АҒЫМДАҒЫКҮН', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'АҒЫМДАҒЫКҮН2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'АҒЫМДАҒЫКҮНАТАУЫ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'АҒЫМДАҒЫЖЫЛ', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'АҒЫМДАҒЫУАҚЫТ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'АҒЫМДАҒЫСАҒАТ', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'ЖЕРГІЛІКТІАЙ', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'ЖЕРГІЛІКТІАЙАТАУЫ', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'ЖЕРГІЛІКТІАЙІЛІКАТАУЫ', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'ЖЕРГІЛІКТІАЙЖИЫР', 'ЖЕРГІЛІКТІАЙҚЫСҚАША', 'ЖЕРГІЛІКТІАЙҚЫСҚА', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'ЖЕРГІЛІКТІКҮН', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'ЖЕРГІЛІКТІКҮН2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'ЖЕРГІЛІКТІКҮНАТАУЫ', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'ЖЕРГІЛІКТІЖЫЛ', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'ЖЕРГІЛІКТІУАҚЫТ', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'ЖЕРГІЛІКТІСАҒАТ', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'БЕТСАНЫ', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'МАҚАЛАСАНЫ', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ФАЙЛСАНЫ', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'ҚАТЫСУШЫСАНЫ', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'ӨҢДЕМЕСАНЫ', 'ТҮЗЕТУСАНЫ', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'БЕТАТАУЫ', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'БЕТАТАУЫ2', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ЕСІМАЯСЫ', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ЕСІМАЯСЫ2', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'ТАЛҚЫЛАУАЯСЫ', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'ТАЛҚЫЛАУАЯСЫ2', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ТАҚЫРЫПБЕТІ', 'МАҚАЛАБЕТІ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ТАҚЫРЫПБЕТІ2', 'МАҚАЛАБЕТІ2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'ТОЛЫҚБЕТАТАУЫ', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'ТОЛЫҚБЕТАТАУЫ2', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'БЕТШЕАТАУЫ', 'АСТЫҢҒЫБЕТАТАУЫ', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'БЕТШЕАТАУЫ2', 'АСТЫҢҒЫБЕТАТАУЫ2', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'НЕГІЗГІБЕТАТАУЫ', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'НЕГІЗГІБЕТАТАУЫ2', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'ТАЛҚЫЛАУБЕТАТАУЫ', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'ТАЛҚЫЛАУБЕТАТАУЫ2', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'ТАҚЫРЫПБЕТАТАУЫ', 'МАҚАЛАБЕТАТАУЫ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'ТАҚЫРЫПБЕТАТАУЫ2', 'МАҚАЛАБЕТАТАУЫ2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'ХБР:', 'MSG:' ),
	'subst'                     => array( '0', 'БӘДЕЛ:', 'SUBST:' ),
	'msgnw'                     => array( '0', 'УИКИСІЗХБР:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'нобай', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'нобай=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'оңға', 'оң', 'right' ),
	'img_left'                  => array( '1', 'солға', 'сол', 'left' ),
	'img_none'                  => array( '1', 'ешқандай', 'жоқ', 'none' ),
	'img_width'                 => array( '1', '$1 нүкте', '$1px' ),
	'img_center'                => array( '1', 'ортаға', 'орта', 'center', 'centre' ),
	'img_framed'                => array( '1', 'сүрмелі', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'сүрмесіз', 'frameless' ),
	'img_page'                  => array( '1', 'бет=$1', 'бет $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'тікті', 'тіктік=$1', 'тіктік $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'жиекті', 'border' ),
	'img_baseline'              => array( '1', 'тірекжол', 'baseline' ),
	'img_sub'                   => array( '1', 'астылығы', 'аст', 'sub' ),
	'img_super'                 => array( '1', 'үстілігі', 'үст', 'super', 'sup' ),
	'img_top'                   => array( '1', 'үстіне', 'top' ),
	'img_text_top'              => array( '1', 'мәтін-үстінде', 'text-top' ),
	'img_middle'                => array( '1', 'аралығына', 'middle' ),
	'img_bottom'                => array( '1', 'астына', 'bottom' ),
	'img_text_bottom'           => array( '1', 'мәтін-астында', 'text-bottom' ),
	'int'                       => array( '0', 'ІШКІ:', 'INT:' ),
	'sitename'                  => array( '1', 'ТОРАПАТАУЫ', 'SITENAME' ),
	'ns'                        => array( '0', 'ЕА:', 'ЕСІМАЯ:', 'NS:' ),
	'localurl'                  => array( '0', 'ЖЕРГІЛІКТІЖАЙ:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'ЖЕРГІЛІКТІЖАЙ2:', 'LOCALURLE:' ),
	'server'                    => array( '0', 'СЕРВЕР', 'SERVER' ),
	'servername'                => array( '0', 'СЕРВЕРАТАУЫ', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'ӘМІРЖОЛЫ', 'SCRIPTPATH' ),
	'grammar'                   => array( '0', 'СЕПТІГІ:', 'СЕПТІК:', 'GRAMMAR:' ),
	'notitleconvert'            => array( '0', '__ТАҚЫРЫПАТЫНТҮРЛЕНДІРГІЗБЕУ__', '__ТАТЖОҚ__', '__АТАУАЛМАСТЫРҒЫЗБАУ__', '__ААБАУ__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__МАҒЛҰМАТЫНТҮРЛЕНДІРГІЗБЕУ__', '__МАТЖОҚ__', '__МАҒЛҰМАТАЛМАСТЫРҒЫЗБАУ__', '__МАБАУ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'АҒЫМДАҒЫАПТАСЫ', 'АҒЫМДАҒЫАПТА', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'АҒЫМДАҒЫАПТАКҮНІ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'ЖЕРГІЛІКТІАПТАСЫ', 'ЖЕРГІЛІКТІАПТА', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'ЖЕРГІЛІКТІАПТАКҮНІ', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'ТҮЗЕТУНӨМІРІ', 'НҰСҚАНӨМІРІ', 'REVISIONID' ),
	'revisionday'               => array( '1', 'ТҮЗЕТУКҮНІ', 'НҰСҚАКҮНІ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'ТҮЗЕТУКҮНІ2', 'НҰСҚАКҮНІ2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'ТҮЗЕТУАЙЫ', 'НҰСҚААЙЫ', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'ТҮЗЕТУЖЫЛЫ', 'НҰСҚАЖЫЛЫ', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'ТҮЗЕТУУАҚЫТЫТАҢБАСЫ', 'НҰСҚАУАҚЫТТҮЙІНДЕМЕСІ', 'REVISIONTIMESTAMP' ),
	'plural'                    => array( '0', 'КӨПШЕТҮРІ:', 'КӨПШЕ:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'ТОЛЫҚЖАЙЫ:', 'ТОЛЫҚЖАЙ:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'ТОЛЫҚЖАЙЫ2:', 'ТОЛЫҚЖАЙ2:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'КӘ1:', 'КІШІӘРІППЕН1:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'БӘ1:', 'БАСӘРІППЕН1:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'КӘ:', 'КІШІӘРІППЕН:', 'LC:' ),
	'uc'                        => array( '0', 'БӘ:', 'БАСӘРІППЕН:', 'UC:' ),
	'raw'                       => array( '0', 'ҚАМ:', 'RAW:' ),
	'displaytitle'              => array( '1', 'КӨРІНЕТІНТАҚЫРЫАПАТЫ', 'КӨРСЕТІЛЕТІНАТАУ', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'Қ', 'R' ),
	'newsectionlink'            => array( '1', '__ЖАҢАБӨЛІМСІЛТЕМЕСІ__', '__NEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'БАҒДАРЛАМАНҰСҚАСЫ', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'ЖАЙДЫМҰҚАМДАУ:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'ЖӘКІРДІМҰҚАМДАУ', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'АҒЫМДАҒЫУАҚЫТТҮЙІНДЕМЕСІ', 'АҒЫМДАҒЫУАҚЫТТҮЙІН', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'ЖЕРГІЛІКТІУАҚЫТТҮЙІНДЕМЕСІ', 'ЖЕРГІЛІКТІУАҚЫТТҮЙІН', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'БАҒЫТБЕЛГІСІ', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#ТІЛ:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'МАҒЛҰМАТТІЛІ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'ЕСІМАЯБЕТСАНЫ:', 'ЕАБЕТСАНЫ:', 'АЯБЕТСАНЫ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'ӘКІМШІСАНЫ', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'САНПІШІМІ', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'СОЛҒАЫҒЫС', 'СОЛЫҒЫС', 'PADLEFT' ),
	'padright'                  => array( '0', 'ОҢҒАЫҒЫС', 'ОҢЫҒЫС', 'PADRIGHT' ),
	'special'                   => array( '0', 'арнайы', 'special' ),
	'defaultsort'               => array( '1', 'ӘДЕПКІСҰРЫПТАУ:', 'ӘДЕПКІСАНАТСҰРЫПТАУ:', 'ӘДЕПКІСҰРЫПТАУКІЛТІ:', 'ӘДЕПКІСҰРЫП:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'ФАЙЛМЕКЕНІ:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'белгі', 'tag' ),
	'hiddencat'                 => array( '1', '__ЖАСЫРЫНСАНАТ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'САНАТТАҒЫБЕТТЕР', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'БЕТМӨЛШЕРІ', 'PAGESIZE' ),
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Барлық_хабарлар' ),
	'Allpages'                  => array( 'Барлық_беттер' ),
	'Ancientpages'              => array( 'Ескі_беттер' ),
	'Block'                     => array( 'Жайды_бұғаттау', 'IP_бұғаттау' ),
	'Booksources'               => array( 'Кітап_қайнарлары' ),
	'BrokenRedirects'           => array( 'Жарамсыз_айдағыштар', 'Жарамсыз_айдатулар' ),
	'Categories'                => array( 'Санаттар' ),
	'ChangePassword'            => array( 'Құпия_сөзді_қайтару' ),
	'Confirmemail'              => array( 'Құптау_хат' ),
	'Contributions'             => array( 'Үлесі' ),
	'CreateAccount'             => array( 'Жаңа_тіркелгі', 'Тіркелгі_Жарату' ),
	'Deadendpages'              => array( 'Тұйық_беттер' ),
	'DoubleRedirects'           => array( 'Шынжырлы_айдағыштар', 'Шынжырлы_айдатулар' ),
	'Emailuser'                 => array( 'Хат_жіберу' ),
	'Export'                    => array( 'Сыртқа_беру' ),
	'Fewestrevisions'           => array( 'Ең_аз_түзетілген' ),
	'FileDuplicateSearch'       => array( 'Файл_телнұсқасын_іздеу', 'Қайталанған_файлдарды_іздеу' ),
	'Filepath'                  => array( 'Файл_мекені' ),
	'Import'                    => array( 'Сырттан_алу' ),
	'Invalidateemail'           => array( 'Құптамау_хаты' ),
	'BlockList'                 => array( 'Бұғатталғандар' ),
	'Listadmins'                => array( 'Әкімшілер', 'Әкімші_тізімі' ),
	'Listbots'                  => array( 'Боттар', 'Боттар_тізімі' ),
	'Listfiles'                 => array( 'Сурет_тізімі' ),
	'Listgrouprights'           => array( 'Топ_құқықтары_тізімі' ),
	'Listredirects'             => array( 'Айдату_тізімі' ),
	'Listusers'                 => array( 'Қатысушылар', 'Қатысушы_тізімі' ),
	'Lockdb'                    => array( 'Дерекқорды_құлыптау' ),
	'Log'                       => array( 'Журнал', 'Журналдар' ),
	'Lonelypages'               => array( 'Саяқ_беттер' ),
	'Longpages'                 => array( 'Ұзын_беттер', 'Үлкен_беттер' ),
	'MergeHistory'              => array( 'Тарих_біріктіру' ),
	'MIMEsearch'                => array( 'MIME_түрімен_іздеу' ),
	'Mostcategories'            => array( 'Ең_көп_санаттар_бары' ),
	'Mostimages'                => array( 'Ең_көп_пайдаланылған_суреттер', 'Ең_көп_суреттер_бары' ),
	'Mostlinked'                => array( 'Ең_көп_сілтенген_беттер' ),
	'Mostlinkedcategories'      => array( 'Ең_көп_пайдаланылған_санаттар', 'Ең_көп_сілтенген_санаттар' ),
	'Mostlinkedtemplates'       => array( 'Ең_көп_пайдаланылған_үлгілер', 'Ең_көп_сілтенген_үлгілер' ),
	'Mostrevisions'             => array( 'Ең_көп_түзетілген', 'Ең_көп_нұсқалар_бары' ),
	'Movepage'                  => array( 'Бетті_жылжыту' ),
	'Mycontributions'           => array( 'Үлесім' ),
	'Mypage'                    => array( 'Жеке_бетім' ),
	'Mytalk'                    => array( 'Талқылауым' ),
	'Newimages'                 => array( 'Жаңа_суреттер' ),
	'Newpages'                  => array( 'Жаңа_беттер' ),
	'Preferences'               => array( 'Бапталымдар', 'Баптау' ),
	'Prefixindex'               => array( 'Бастауыш_тізімі' ),
	'Protectedpages'            => array( 'Қорғалған_беттер' ),
	'Protectedtitles'           => array( 'Қорғалған_тақырыптар', 'Қорғалған_атаулар' ),
	'Randompage'                => array( 'Кездейсоқ', 'Кездейсоқ_бет' ),
	'Randomredirect'            => array( 'Кедейсоқ_айдағыш', 'Кедейсоқ_айдату' ),
	'Recentchanges'             => array( 'Жуықтағы_өзгерістер' ),
	'Recentchangeslinked'       => array( 'Сілтенгендердің_өзгерістері', 'Қатысты_өзгерістер' ),
	'Revisiondelete'            => array( 'Түзету_жою', 'Нұсқаны_жою' ),
	'Search'                    => array( 'Іздеу' ),
	'Shortpages'                => array( 'Қысқа_беттер' ),
	'Specialpages'              => array( 'Арнайы_беттер' ),
	'Statistics'                => array( 'Санақ' ),
	'Uncategorizedcategories'   => array( 'Санатсыз_санаттар' ),
	'Uncategorizedimages'       => array( 'Санатсыз_суреттер' ),
	'Uncategorizedpages'        => array( 'Санатсыз_беттер' ),
	'Uncategorizedtemplates'    => array( 'Санатсыз_үлгілер' ),
	'Undelete'                  => array( 'Жоюды_болдырмау', 'Жойылғанды_қайтару' ),
	'Unlockdb'                  => array( 'Дерекқорды_құлыптамау' ),
	'Unusedcategories'          => array( 'Пайдаланылмаған_санаттар' ),
	'Unusedimages'              => array( 'Пайдаланылмаған_суреттер' ),
	'Unusedtemplates'           => array( 'Пайдаланылмаған_үлгілер' ),
	'Unwatchedpages'            => array( 'Бақыланылмаған_беттер' ),
	'Upload'                    => array( 'Қотарып_беру', 'Қотару' ),
	'Userlogin'                 => array( 'Қатысушы_кіруі' ),
	'Userlogout'                => array( 'Қатысушы_шығуы' ),
	'Userrights'                => array( 'Қатысушы_құқықтары' ),
	'Version'                   => array( 'Нұсқасы' ),
	'Wantedcategories'          => array( 'Толтырылмаған_санаттар' ),
	'Wantedpages'               => array( 'Толтырылмаған_беттер', 'Жарамсыз_сілтемелер' ),
	'Watchlist'                 => array( 'Бақылау_тізімі' ),
	'Whatlinkshere'             => array( 'Мында_сілтегендер' ),
	'Withoutinterwiki'          => array( 'Уики-аралықсыздар' ),
);

