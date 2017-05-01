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
$separatorTransformTable = [
	',' => "\xc2\xa0",
	'.' => ',',
];

$fallback8bitEncoding = 'windows-1251';

$linkTrail = '/^([a-zäçéğıïñöşüýʺʹа-яёәғіқңөұүһٴابپتجحدرزسشعفقكلمنڭەوۇۋۆىيچھ“»]+)(.*)$/sDu';

$namespaceNames = [
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
];

$namespaceAliases = [
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
];

$magicWords = [
	'redirect'                  => [ '0', '#АЙДАУ', '#REDIRECT' ],
	'notoc'                     => [ '0', '__МАЗМҰНСЫЗ__', '__МСЫЗ__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__ҚОЙМАСЫЗ__', '__ҚСЫЗ__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__МАЗМҰНДАТҚЫЗУ__', '__МҚЫЗУ__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__МАЗМҰНЫ__', '__МЗМН__', '__TOC__' ],
	'noeditsection'             => [ '0', '__БӨЛІДІМӨНДЕМЕУ__', '__БӨЛІМӨНДЕТКІЗБЕУ__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'АҒЫМДАҒЫАЙ', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'АҒЫМДАҒЫАЙАТАУЫ', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'АҒЫМДАҒЫАЙІЛІКАТАУЫ', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'АҒЫМДАҒЫАЙЖИЫР', 'АҒЫМДАҒЫАЙҚЫСҚА', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'АҒЫМДАҒЫКҮН', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'АҒЫМДАҒЫКҮН2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'АҒЫМДАҒЫКҮНАТАУЫ', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'АҒЫМДАҒЫЖЫЛ', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'АҒЫМДАҒЫУАҚЫТ', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'АҒЫМДАҒЫСАҒАТ', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'ЖЕРГІЛІКТІАЙ', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'ЖЕРГІЛІКТІАЙАТАУЫ', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'ЖЕРГІЛІКТІАЙІЛІКАТАУЫ', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'ЖЕРГІЛІКТІАЙЖИЫР', 'ЖЕРГІЛІКТІАЙҚЫСҚАША', 'ЖЕРГІЛІКТІАЙҚЫСҚА', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'ЖЕРГІЛІКТІКҮН', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'ЖЕРГІЛІКТІКҮН2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'ЖЕРГІЛІКТІКҮНАТАУЫ', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'ЖЕРГІЛІКТІЖЫЛ', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'ЖЕРГІЛІКТІУАҚЫТ', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'ЖЕРГІЛІКТІСАҒАТ', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'БЕТСАНЫ', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'МАҚАЛАСАНЫ', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'ФАЙЛСАНЫ', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'ҚАТЫСУШЫСАНЫ', 'NUMBEROFUSERS' ],
	'numberofedits'             => [ '1', 'ӨҢДЕМЕСАНЫ', 'ТҮЗЕТУСАНЫ', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'БЕТАТАУЫ', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'БЕТАТАУЫ2', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'ЕСІМАЯСЫ', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ЕСІМАЯСЫ2', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'ТАЛҚЫЛАУАЯСЫ', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'ТАЛҚЫЛАУАЯСЫ2', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'ТАҚЫРЫПБЕТІ', 'МАҚАЛАБЕТІ', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'ТАҚЫРЫПБЕТІ2', 'МАҚАЛАБЕТІ2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'ТОЛЫҚБЕТАТАУЫ', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ТОЛЫҚБЕТАТАУЫ2', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'БЕТШЕАТАУЫ', 'АСТЫҢҒЫБЕТАТАУЫ', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'БЕТШЕАТАУЫ2', 'АСТЫҢҒЫБЕТАТАУЫ2', 'SUBPAGENAMEE' ],
	'basepagename'              => [ '1', 'НЕГІЗГІБЕТАТАУЫ', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'НЕГІЗГІБЕТАТАУЫ2', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'ТАЛҚЫЛАУБЕТАТАУЫ', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'ТАЛҚЫЛАУБЕТАТАУЫ2', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', 'ТАҚЫРЫПБЕТАТАУЫ', 'МАҚАЛАБЕТАТАУЫ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'ТАҚЫРЫПБЕТАТАУЫ2', 'МАҚАЛАБЕТАТАУЫ2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'msg'                       => [ '0', 'ХБР:', 'MSG:' ],
	'subst'                     => [ '0', 'БӘДЕЛ:', 'SUBST:' ],
	'msgnw'                     => [ '0', 'УИКИСІЗХБР:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'нобай', 'thumbnail', 'thumb' ],
	'img_manualthumb'           => [ '1', 'нобай=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'оңға', 'оң', 'right' ],
	'img_left'                  => [ '1', 'солға', 'сол', 'left' ],
	'img_none'                  => [ '1', 'ешқандай', 'жоқ', 'none' ],
	'img_width'                 => [ '1', '$1 нүкте', '$1px' ],
	'img_center'                => [ '1', 'ортаға', 'орта', 'center', 'centre' ],
	'img_framed'                => [ '1', 'сүрмелі', 'framed', 'enframed', 'frame' ],
	'img_frameless'             => [ '1', 'сүрмесіз', 'frameless' ],
	'img_page'                  => [ '1', 'бет=$1', 'бет $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'тікті', 'тіктік=$1', 'тіктік $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'жиекті', 'border' ],
	'img_baseline'              => [ '1', 'тірекжол', 'baseline' ],
	'img_sub'                   => [ '1', 'астылығы', 'аст', 'sub' ],
	'img_super'                 => [ '1', 'үстілігі', 'үст', 'super', 'sup' ],
	'img_top'                   => [ '1', 'үстіне', 'top' ],
	'img_text_top'              => [ '1', 'мәтін-үстінде', 'text-top' ],
	'img_middle'                => [ '1', 'аралығына', 'middle' ],
	'img_bottom'                => [ '1', 'астына', 'bottom' ],
	'img_text_bottom'           => [ '1', 'мәтін-астында', 'text-bottom' ],
	'int'                       => [ '0', 'ІШКІ:', 'INT:' ],
	'sitename'                  => [ '1', 'ТОРАПАТАУЫ', 'SITENAME' ],
	'ns'                        => [ '0', 'ЕА:', 'ЕСІМАЯ:', 'NS:' ],
	'localurl'                  => [ '0', 'ЖЕРГІЛІКТІЖАЙ:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'ЖЕРГІЛІКТІЖАЙ2:', 'LOCALURLE:' ],
	'server'                    => [ '0', 'СЕРВЕР', 'SERVER' ],
	'servername'                => [ '0', 'СЕРВЕРАТАУЫ', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'ӘМІРЖОЛЫ', 'SCRIPTPATH' ],
	'grammar'                   => [ '0', 'СЕПТІГІ:', 'СЕПТІК:', 'GRAMMAR:' ],
	'notitleconvert'            => [ '0', '__ТАҚЫРЫПАТЫНТҮРЛЕНДІРГІЗБЕУ__', '__ТАТЖОҚ__', '__АТАУАЛМАСТЫРҒЫЗБАУ__', '__ААБАУ__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__МАҒЛҰМАТЫНТҮРЛЕНДІРГІЗБЕУ__', '__МАТЖОҚ__', '__МАҒЛҰМАТАЛМАСТЫРҒЫЗБАУ__', '__МАБАУ__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'АҒЫМДАҒЫАПТАСЫ', 'АҒЫМДАҒЫАПТА', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'АҒЫМДАҒЫАПТАКҮНІ', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'ЖЕРГІЛІКТІАПТАСЫ', 'ЖЕРГІЛІКТІАПТА', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'ЖЕРГІЛІКТІАПТАКҮНІ', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'ТҮЗЕТУНӨМІРІ', 'НҰСҚАНӨМІРІ', 'REVISIONID' ],
	'revisionday'               => [ '1', 'ТҮЗЕТУКҮНІ', 'НҰСҚАКҮНІ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'ТҮЗЕТУКҮНІ2', 'НҰСҚАКҮНІ2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'ТҮЗЕТУАЙЫ', 'НҰСҚААЙЫ', 'REVISIONMONTH' ],
	'revisionyear'              => [ '1', 'ТҮЗЕТУЖЫЛЫ', 'НҰСҚАЖЫЛЫ', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'ТҮЗЕТУУАҚЫТЫТАҢБАСЫ', 'НҰСҚАУАҚЫТТҮЙІНДЕМЕСІ', 'REVISIONTIMESTAMP' ],
	'plural'                    => [ '0', 'КӨПШЕТҮРІ:', 'КӨПШЕ:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'ТОЛЫҚЖАЙЫ:', 'ТОЛЫҚЖАЙ:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'ТОЛЫҚЖАЙЫ2:', 'ТОЛЫҚЖАЙ2:', 'FULLURLE:' ],
	'lcfirst'                   => [ '0', 'КӘ1:', 'КІШІӘРІППЕН1:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'БӘ1:', 'БАСӘРІППЕН1:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'КӘ:', 'КІШІӘРІППЕН:', 'LC:' ],
	'uc'                        => [ '0', 'БӘ:', 'БАСӘРІППЕН:', 'UC:' ],
	'raw'                       => [ '0', 'ҚАМ:', 'RAW:' ],
	'displaytitle'              => [ '1', 'КӨРІНЕТІНТАҚЫРЫАПАТЫ', 'КӨРСЕТІЛЕТІНАТАУ', 'DISPLAYTITLE' ],
	'rawsuffix'                 => [ '1', 'Қ', 'R' ],
	'newsectionlink'            => [ '1', '__ЖАҢАБӨЛІМСІЛТЕМЕСІ__', '__NEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'БАҒДАРЛАМАНҰСҚАСЫ', 'CURRENTVERSION' ],
	'urlencode'                 => [ '0', 'ЖАЙДЫМҰҚАМДАУ:', 'URLENCODE:' ],
	'anchorencode'              => [ '0', 'ЖӘКІРДІМҰҚАМДАУ', 'ANCHORENCODE' ],
	'currenttimestamp'          => [ '1', 'АҒЫМДАҒЫУАҚЫТТҮЙІНДЕМЕСІ', 'АҒЫМДАҒЫУАҚЫТТҮЙІН', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'ЖЕРГІЛІКТІУАҚЫТТҮЙІНДЕМЕСІ', 'ЖЕРГІЛІКТІУАҚЫТТҮЙІН', 'LOCALTIMESTAMP' ],
	'directionmark'             => [ '1', 'БАҒЫТБЕЛГІСІ', 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                  => [ '0', '#ТІЛ:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'МАҒЛҰМАТТІЛІ', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'ЕСІМАЯБЕТСАНЫ:', 'ЕАБЕТСАНЫ:', 'АЯБЕТСАНЫ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'ӘКІМШІСАНЫ', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'САНПІШІМІ', 'FORMATNUM' ],
	'padleft'                   => [ '0', 'СОЛҒАЫҒЫС', 'СОЛЫҒЫС', 'PADLEFT' ],
	'padright'                  => [ '0', 'ОҢҒАЫҒЫС', 'ОҢЫҒЫС', 'PADRIGHT' ],
	'special'                   => [ '0', 'арнайы', 'special' ],
	'defaultsort'               => [ '1', 'ӘДЕПКІСҰРЫПТАУ:', 'ӘДЕПКІСАНАТСҰРЫПТАУ:', 'ӘДЕПКІСҰРЫПТАУКІЛТІ:', 'ӘДЕПКІСҰРЫП:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'ФАЙЛМЕКЕНІ:', 'FILEPATH:' ],
	'tag'                       => [ '0', 'белгі', 'tag' ],
	'hiddencat'                 => [ '1', '__ЖАСЫРЫНСАНАТ__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'САНАТТАҒЫБЕТТЕР', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'БЕТМӨЛШЕРІ', 'PAGESIZE' ],
];

$specialPageAliases = [
	'Allmessages'               => [ 'Барлық_хабарлар' ],
	'Allpages'                  => [ 'Барлық_беттер' ],
	'Ancientpages'              => [ 'Ескі_беттер' ],
	'Block'                     => [ 'Жайды_бұғаттау', 'IP_бұғаттау' ],
	'Booksources'               => [ 'Кітап_қайнарлары' ],
	'BrokenRedirects'           => [ 'Жарамсыз_айдағыштар', 'Жарамсыз_айдатулар' ],
	'Categories'                => [ 'Санаттар' ],
	'ChangePassword'            => [ 'Құпия_сөзді_қайтару' ],
	'Confirmemail'              => [ 'Құптау_хат' ],
	'Contributions'             => [ 'Үлесі' ],
	'CreateAccount'             => [ 'Жаңа_тіркелгі', 'Тіркелгі_Жарату' ],
	'Deadendpages'              => [ 'Тұйық_беттер' ],
	'DoubleRedirects'           => [ 'Шынжырлы_айдағыштар', 'Шынжырлы_айдатулар' ],
	'Emailuser'                 => [ 'Хат_жіберу' ],
	'Export'                    => [ 'Сыртқа_беру' ],
	'Fewestrevisions'           => [ 'Ең_аз_түзетілген' ],
	'FileDuplicateSearch'       => [ 'Файл_телнұсқасын_іздеу', 'Қайталанған_файлдарды_іздеу' ],
	'Filepath'                  => [ 'Файл_мекені' ],
	'Import'                    => [ 'Сырттан_алу' ],
	'Invalidateemail'           => [ 'Құптамау_хаты' ],
	'BlockList'                 => [ 'Бұғатталғандар' ],
	'Listadmins'                => [ 'Әкімшілер', 'Әкімші_тізімі' ],
	'Listbots'                  => [ 'Боттар', 'Боттар_тізімі' ],
	'Listfiles'                 => [ 'Сурет_тізімі' ],
	'Listgrouprights'           => [ 'Топ_құқықтары_тізімі' ],
	'Listredirects'             => [ 'Айдату_тізімі' ],
	'Listusers'                 => [ 'Қатысушылар', 'Қатысушы_тізімі' ],
	'Lockdb'                    => [ 'Дерекқорды_құлыптау' ],
	'Log'                       => [ 'Журнал', 'Журналдар' ],
	'Lonelypages'               => [ 'Саяқ_беттер' ],
	'Longpages'                 => [ 'Ұзын_беттер', 'Үлкен_беттер' ],
	'MergeHistory'              => [ 'Тарих_біріктіру' ],
	'MIMEsearch'                => [ 'MIME_түрімен_іздеу' ],
	'Mostcategories'            => [ 'Ең_көп_санаттар_бары' ],
	'Mostimages'                => [ 'Ең_көп_пайдаланылған_суреттер', 'Ең_көп_суреттер_бары' ],
	'Mostlinked'                => [ 'Ең_көп_сілтенген_беттер' ],
	'Mostlinkedcategories'      => [ 'Ең_көп_пайдаланылған_санаттар', 'Ең_көп_сілтенген_санаттар' ],
	'Mostlinkedtemplates'       => [ 'Ең_көп_пайдаланылған_үлгілер', 'Ең_көп_сілтенген_үлгілер' ],
	'Mostrevisions'             => [ 'Ең_көп_түзетілген', 'Ең_көп_нұсқалар_бары' ],
	'Movepage'                  => [ 'Бетті_жылжыту' ],
	'Mycontributions'           => [ 'Үлесім' ],
	'Mypage'                    => [ 'Жеке_бетім' ],
	'Mytalk'                    => [ 'Талқылауым' ],
	'Newimages'                 => [ 'Жаңа_суреттер' ],
	'Newpages'                  => [ 'Жаңа_беттер' ],
	'Preferences'               => [ 'Бапталымдар', 'Баптау' ],
	'Prefixindex'               => [ 'Бастауыш_тізімі' ],
	'Protectedpages'            => [ 'Қорғалған_беттер' ],
	'Protectedtitles'           => [ 'Қорғалған_тақырыптар', 'Қорғалған_атаулар' ],
	'Randompage'                => [ 'Кездейсоқ', 'Кездейсоқ_бет' ],
	'Randomredirect'            => [ 'Кедейсоқ_айдағыш', 'Кедейсоқ_айдату' ],
	'Recentchanges'             => [ 'Жуықтағы_өзгерістер' ],
	'Recentchangeslinked'       => [ 'Сілтенгендердің_өзгерістері', 'Қатысты_өзгерістер' ],
	'Revisiondelete'            => [ 'Түзету_жою', 'Нұсқаны_жою' ],
	'Search'                    => [ 'Іздеу' ],
	'Shortpages'                => [ 'Қысқа_беттер' ],
	'Specialpages'              => [ 'Арнайы_беттер' ],
	'Statistics'                => [ 'Санақ' ],
	'Uncategorizedcategories'   => [ 'Санатсыз_санаттар' ],
	'Uncategorizedimages'       => [ 'Санатсыз_суреттер' ],
	'Uncategorizedpages'        => [ 'Санатсыз_беттер' ],
	'Uncategorizedtemplates'    => [ 'Санатсыз_үлгілер' ],
	'Undelete'                  => [ 'Жоюды_болдырмау', 'Жойылғанды_қайтару' ],
	'Unlockdb'                  => [ 'Дерекқорды_құлыптамау' ],
	'Unusedcategories'          => [ 'Пайдаланылмаған_санаттар' ],
	'Unusedimages'              => [ 'Пайдаланылмаған_суреттер' ],
	'Unusedtemplates'           => [ 'Пайдаланылмаған_үлгілер' ],
	'Unwatchedpages'            => [ 'Бақыланылмаған_беттер' ],
	'Upload'                    => [ 'Қотарып_беру', 'Қотару' ],
	'Userlogin'                 => [ 'Қатысушы_кіруі' ],
	'Userlogout'                => [ 'Қатысушы_шығуы' ],
	'Userrights'                => [ 'Қатысушы_құқықтары' ],
	'Version'                   => [ 'Нұсқасы' ],
	'Wantedcategories'          => [ 'Толтырылмаған_санаттар' ],
	'Wantedpages'               => [ 'Толтырылмаған_беттер', 'Жарамсыз_сілтемелер' ],
	'Watchlist'                 => [ 'Бақылау_тізімі' ],
	'Whatlinkshere'             => [ 'Мында_сілтегендер' ],
	'Withoutinterwiki'          => [ 'Уики-аралықсыздар' ],
];

