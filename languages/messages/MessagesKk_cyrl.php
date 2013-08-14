<?php
/** Kazakh (Cyrillic script) (қазақша (кирил)‎)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AlefZet
 * @author Alibek Kisybay
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
	'مەدياۋيكي_تالقىلاۋى'  => NS_MEDIAWIKI_TALK ,
	'ٷلگٸ'        => NS_TEMPLATE ,
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
	'Blockme'                   => array( 'Өздіктік_бұғаттау', 'Өздік_бұғаттау', 'Мені_бұғаттау' ),
	'Booksources'               => array( 'Кітап_қайнарлары' ),
	'BrokenRedirects'           => array( 'Жарамсыз_айдағыштар', 'Жарамсыз_айдатулар' ),
	'Categories'                => array( 'Санаттар' ),
	'ChangePassword'            => array( 'Құпия_сөзді_қайтару' ),
	'Confirmemail'              => array( 'Құптау_хат' ),
	'Contributions'             => array( 'Үлесі' ),
	'CreateAccount'             => array( 'Жаңа_тіркелгі', 'Тіркелгі_Жарату' ),
	'Deadendpages'              => array( 'Тұйық_беттер' ),
	'Disambiguations'           => array( 'Айрықты_беттер' ),
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
	'Popularpages'              => array( 'Ең_көп_қаралған_беттер', 'Әйгілі_беттер' ),
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

$messages = array(
# User preference toggles
'tog-underline' => 'Сілтеменің астын сызу:',
'tog-justify' => 'Ежелерді ені бойынша туралау',
'tog-hideminor' => 'Жуықтағы өзгерістерден шағын өңдемелерді жасыру',
'tog-hidepatrolled' => 'Тексерілген өңдеулерді соңғы өзгерістер тізімінде көрсетпеу',
'tog-newpageshidepatrolled' => 'Тексерілген беттерді жаңа беттер тізімінде көрсетпеу',
'tog-extendwatchlist' => 'Бақылау тізімді ұлғайту (барлық жарамды өзгерістерді көрсету)',
'tog-usenewrc' => 'Кеңейтілген жуықтағы өзгерістер (JavaScript)',
'tog-numberheadings' => 'Мазмұн тақырыптарын автоматты нөмірлеу',
'tog-showtoolbar' => 'Өңдеу құралдары орналасқан аспаптар жақтауын көрсету (JavaScript-ті қажет етеді)',
'tog-editondblclick' => 'Қос шерту арқылы бетті өңдеу (JavaScript)',
'tog-editsection' => 'Бөлімдерді [өңдеу] сілтемесімен өңдеуін қос',
'tog-editsectiononrightclick' => 'Бөлім тақырыбын оң нұқумен өңдеуін қос (JavaScript)',
'tog-showtoc' => 'Мазмұнын көрсету (3-тен астам бөлімі болған жағдайда ғана)',
'tog-rememberpassword' => 'Тіркелгімді осы браузерде ұмытпа (ең көбі $1 {{PLURAL:$1|күн|күн}})',
'tog-watchcreations' => 'Мен бастаған беттерді бақылау тізіміне қос',
'tog-watchdefault' => 'Мен өңдеген беттерді бақылау тізіміне қос',
'tog-watchmoves' => 'Мен жылжытқан беттерді бақылау тізіміне қос',
'tog-watchdeletion' => 'Мен жойған беттерді бақылау тізіміне қос',
'tog-minordefault' => 'Әдепкіден барлық өңдемелерді шағын деп белгіле',
'tog-previewontop' => 'Қарап шығу аумағын өңдеу терезесінің жоғарғы жағында көрсету',
'tog-previewonfirst' => 'Бірінші өңдегенде қарап шығу',
'tog-nocache' => 'Бет бүркемелеуін өшір',
'tog-enotifwatchlistpages' => 'Бақылауыңыздағы бет өзгергенде е-поштаға хабарлама жіберу',
'tog-enotifusertalkpages' => 'Талқылау бетім өзгергенде маған хат жібер',
'tog-enotifminoredits' => 'Шағын өңдеме туралы да маған хат жібер',
'tog-enotifrevealaddr' => 'Е-поштамның мекенжайын ескерту хаттарда аш',
'tog-shownumberswatching' => 'Бақылап тұрған қатысушылардың санын көрсет',
'tog-oldsig' => 'Ағымдағы қолтаңбаңыз:',
'tog-fancysig' => 'Қолтаңбаны уикимәтін ретінде қарастыру (автоматты сілтеме қойылмайды)',
'tog-uselivepreview' => 'Тура қарап шығуды қолдану (JavaScript) (Сынақтама)',
'tog-forceeditsummary' => 'Өңдеменің қысқаша мазмұндамасы бос қалғанда маған ескерт',
'tog-watchlisthideown' => 'Өңдемелерімді бақылау тізімінен жасыр',
'tog-watchlisthidebots' => 'Бот өңдемелерін бақылау тізімінен жасыр',
'tog-watchlisthideminor' => 'Шағын өңдемелерді бақылау тізімінде көрсетпеу',
'tog-watchlisthideliu' => 'Бақылау тізіміндегі қатысушылардың өңдеулерін көрсетпеу',
'tog-watchlisthideanons' => 'Бақылау тізіміндегі жасырын қатысушылардың өңдеулерін көрсетпеу',
'tog-watchlisthidepatrolled' => 'Бақылау тізімінде тексерілген өңдеулерді көрсетпеу',
'tog-ccmeonemails' => 'Басқа қатысушыға жіберген хатымның көшірмесін маған да жөнелт',
'tog-diffonly' => 'Айырма астында бет мағлұматын көрсетпе',
'tog-showhiddencats' => 'Жасырын санаттарды көрсету',
'tog-noconvertlink' => 'Сілтеме атауларын ауыстырма',
'tog-norollbackdiff' => 'Шегіндіруден кейін нұсқалардың айырмашылығын көрсетпеу',
'tog-useeditwarning' => 'Өңдемесі сақталмаған парақшадан шығар кезде ескерту',

'underline-always' => 'Әрқашан',
'underline-never' => 'Ешқашан',
'underline-default' => 'Шолғыш бойынша',

# Font style option in Special:Preferences
'editfont-style' => 'Өңдеу жолындағы қаріптің түрі',
'editfont-default' => 'Негізгі браузер',
'editfont-monospace' => 'Бірдей енді қаріп',
'editfont-sansserif' => 'Ноқатсыз қаріп',
'editfont-serif' => 'Ноқатты қаріп',

# Dates
'sunday' => 'Жексенбі',
'monday' => 'Дүйсенбі',
'tuesday' => 'Сейсенбі',
'wednesday' => 'Сәрсенбі',
'thursday' => 'Бейсенбі',
'friday' => 'Жұма',
'saturday' => 'Сенбі',
'sun' => 'Жек',
'mon' => 'Дүй',
'tue' => 'Бей',
'wed' => 'Сәр',
'thu' => 'Бей',
'fri' => 'Жұм',
'sat' => 'Сен',
'january' => 'қаңтар',
'february' => 'ақпан',
'march' => 'наурыз',
'april' => 'сәуір',
'may_long' => 'мамыр',
'june' => 'маусым',
'july' => 'шілде',
'august' => 'тамыз',
'september' => 'қыркүйек',
'october' => 'қазан',
'november' => 'қараша',
'december' => 'желтоқсан',
'january-gen' => 'қаңтардың',
'february-gen' => 'ақпанның',
'march-gen' => 'наурыздың',
'april-gen' => 'сәуірдің',
'may-gen' => 'мамырдың',
'june-gen' => 'маусымның',
'july-gen' => 'шілденің',
'august-gen' => 'тамыздың',
'september-gen' => 'қыркүйектің',
'october-gen' => 'қазанның',
'november-gen' => 'қарашаның',
'december-gen' => 'желтоқсанның',
'jan' => 'қаң',
'feb' => 'ақп',
'mar' => 'нау',
'apr' => 'cәу',
'may' => 'мам',
'jun' => 'мау',
'jul' => 'шіл',
'aug' => 'там',
'sep' => 'қыр',
'oct' => 'қаз',
'nov' => 'қар',
'dec' => 'жел',
'january-date' => 'Қаңтар $1',
'february-date' => 'Ақпан $1',
'march-date' => 'Наурыз $1',
'april-date' => 'Сәуір $1',
'may-date' => 'Мамыр $1',
'june-date' => 'Маусым $1',
'july-date' => 'Шілде $1',
'august-date' => 'Тамыз $1',
'september-date' => 'Қыркүйек $1',
'october-date' => 'Қазан $1',
'november-date' => 'Қараша $1',
'december-date' => 'Желтоқсан $1',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Санат|Санат}}',
'category_header' => '"$1" санатындағы беттер',
'subcategories' => 'Санатшалар',
'category-media-header' => '"$1" санатындағы медиа',
'category-empty' => "''Бұл санатта ағымда еш бет немесе медиа жоқ.''",
'hidden-categories' => '{{PLURAL:$1|Жасырын санат|Жасырын санаттар}}',
'hidden-category-category' => 'Жасырын санаттар',
'category-subcat-count' => '{{PLURAL:$2|Бұл санатта тек келесі санатша бар.|Бұл санатта келесі {{PLURAL:$1|санатша|$1 санатша}} бар (не барлығы $2).}}',
'category-subcat-count-limited' => 'Бұл санатта келесі $1 санатша бар.',
'category-article-count' => '{{PLURAL:$2|Бұл санатта тек келесі бет бар.|Бұл санатта келесі {{PLURAL:$1|бет|$1 бет}} бар, барлығы $2 сыртында.}}',
'category-article-count-limited' => 'Ағымдағы санатта келесі $1 бет бар.',
'category-file-count' => '{{PLURAL:$2|Бұл санатта тек келесі файл бар.|Бұл санатта келесі {{PLURAL:$1|файл|$1 файл}} бар, барлығы $2 сыртында.}}',
'category-file-count-limited' => 'Ағымдағы санатта келесі $1 файл бар.',
'listingcontinuesabbrev' => '(жалғ.)',
'index-category' => 'Индекстелген беттер',
'noindex-category' => 'Индекстелмейтін беттер',
'broken-file-category' => 'Ақаулы файлдық сілтемелері бар беттер',

'about' => 'Жоба туралы',
'article' => 'Мағлұмат беті',
'newwindow' => '(жаңа терезеде ашу)',
'cancel' => 'Болдырмау',
'moredotdotdot' => 'Көбірек…',
'morenotlisted' => 'Бұл тізім толық емес.',
'mypage' => 'Жеке бет',
'mytalk' => 'Талқылау',
'anontalk' => 'IP талқылауы',
'navigation' => 'Бағыттау',
'and' => '&#32;және',

# Cologne Blue skin
'qbfind' => 'Табу',
'qbbrowse' => 'Шолу',
'qbedit' => 'Өңдеу',
'qbpageoptions' => 'Бұл бет',
'qbmyoptions' => 'Беттерім',
'qbspecialpages' => 'Арнайы беттер',
'faq' => 'Жиі қойылатын сұрақтар',
'faqpage' => 'Project:Жиі қойылатын сұрақтар',

# Vector skin
'vector-action-addsection' => 'Тақырып қосу',
'vector-action-delete' => 'Жою',
'vector-action-move' => 'Жылжыту',
'vector-action-protect' => 'Қорғау',
'vector-action-undelete' => 'Жоймау',
'vector-action-unprotect' => 'Қорғанысты өзгерту',
'vector-simplesearch-preference' => 'Кеңейтілген іздеу құралын қосу (Векторлық безендіру үшін ғана)',
'vector-view-create' => 'Бастау',
'vector-view-edit' => 'Өңдеу',
'vector-view-history' => 'Тарихын қарау',
'vector-view-view' => 'Оқу',
'vector-view-viewsource' => 'Қайнарын қарау',
'actions' => 'Әрекеттер',
'namespaces' => 'Есім кеңістіктері',
'variants' => 'Нұсқалар',

'navigation-heading' => 'Бағыттау мәзірі',
'errorpagetitle' => 'Қате',
'returnto' => '$1 дегенге қайта келу.',
'tagline' => '{{SITENAME}} жобасынан алынған мәлімет',
'help' => 'Анықтама',
'search' => 'Іздеу',
'searchbutton' => 'Іздеу',
'go' => 'Өту',
'searcharticle' => 'Өту',
'history' => 'Бет тарихы',
'history_short' => 'Тарихы',
'updatedmarker' => 'соңғы қаралғаннан кейін жаңартылған',
'printableversion' => 'Басып шығару нұсқасы',
'permalink' => 'Тұрақты сілтеме',
'print' => 'Басып шығару',
'view' => 'Қарау',
'edit' => 'Өңдеу',
'create' => 'Бастау',
'editthispage' => 'Бұл бетті өңдеу',
'create-this-page' => 'Осы бетті бастау',
'delete' => 'Жою',
'deletethispage' => 'Бұл бетті жою',
'undeletethispage' => 'Жойылған бетті қайтару',
'undelete_short' => '{{PLURAL:$1|өңдеме|$1 өңдеме}} жоюын болдырмау',
'viewdeleted_short' => '{{PLURAL:$1|жойылған өңдеуді|$1 жойылған өңдеулерді }} көру',
'protect' => 'Қорғау',
'protect_change' => 'өзгерту',
'protectthispage' => 'Бұл бетті қорғау',
'unprotect' => 'Қорғалуын өзгерту',
'unprotectthispage' => 'Бұл беттің қорғауын өзгерту',
'newpage' => 'Жаңа бет',
'talkpage' => 'Бұл бетті талқылау',
'talkpagelinktext' => 'Талқылауы',
'specialpage' => 'Арнайы бет',
'personaltools' => 'Жеке құралдар',
'postcomment' => 'Жаңа бөлім',
'articlepage' => 'Мәлімет бетін қарау',
'talk' => 'Талқылау',
'views' => 'Көрініс',
'toolbox' => 'Құралдар',
'userpage' => 'Қатысушы бетін қарау',
'projectpage' => 'Жоба бетін қарау',
'imagepage' => 'Файл бетін қарау',
'mediawikipage' => 'Хабар бетін қарау',
'templatepage' => 'Үлгі бетін қарау',
'viewhelppage' => 'Анықтама бетін қарау',
'categorypage' => 'Санат бетін қарау',
'viewtalkpage' => 'Талқылау бетін қарау',
'otherlanguages' => 'Басқа тілдерде',
'redirectedfrom' => '($1 бетінен айдатылған)',
'redirectpagesub' => 'Айдату беті',
'lastmodifiedat' => 'Бұл беттің соңғы өзгертілген кезі: $2, $1.',
'viewcount' => 'Бұл бет {{PLURAL:$1|бір рет|$1 уақыт}} қатыналған.',
'protectedpage' => 'Қорғалған бет',
'jumpto' => 'Мұнда ауысу:',
'jumptonavigation' => 'шарлау',
'jumptosearch' => 'іздеу',
'view-pool-error' => 'Кешіріңіз, қазір серверлер шектен тыс жүктелуде.
Осы бетті қарауға өте көп сұраныс жасалды.
Өтініш, күте тұрыңыз және осы бетке кіруге қайта әрекет жасаңыз.

$1',
'pool-timeout' => 'Бұғатталу уақытын күту мерзімі өтті',
'pool-queuefull' => 'Сұранымдар жинақтауышысы толық',
'pool-errorunknown' => 'Белгісіз қате',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '{{SITENAME}} туралы',
'aboutpage' => 'Project:Жоба туралы',
'copyright' => 'Мәлімет $1 шартымен жетімді.',
'copyrightpage' => '{{ns:project}}:Авторлық құқықтар',
'currentevents' => 'Ағымдағы оқиғалар',
'currentevents-url' => 'Project:Ағымдағы оқиғалар',
'disclaimers' => 'Жауапкершіліктен бас тарту',
'disclaimerpage' => 'Project:Жауапкершіліктен бас тарту',
'edithelp' => 'Өндеу анықтамасы',
'helppage' => 'Help:Мазмұны',
'mainpage' => 'Басты бет',
'mainpage-description' => 'Басты бет',
'policy-url' => 'Project:Ережелер',
'portal' => 'Қауым порталы',
'portal-url' => 'Project:Қауым порталы',
'privacy' => 'Жеке құпиясын сақтау',
'privacypage' => 'Project:Жеке құпиясын сақтау',

'badaccess' => 'Рұқсат беру қатесі',
'badaccess-group0' => 'Сұратылған әрекетіңізді орындауға рұқсат етілмейді.',
'badaccess-groups' => 'Аталған әрекетті тек {{PLURAL:$2|топтардың|топтың}} $1 қатысушылары ғана атқара алады.',

'versionrequired' => 'MediaWiki $1 нұсқасы керек',
'versionrequiredtext' => 'Бұл бетті қолдану үшін MediaWiki $1 нұсқасы керек. [[Special:Version|Жүйе нұсқасы бетін]] қараңыз.',

'ok' => 'Жарайды',
'pagetitle' => '$1 — {{SITENAME}}',
'retrievedfrom' => '«$1» бетінен алынған',
'youhavenewmessages' => 'Сізде $1 бар ($2).',
'newmessageslink' => 'жаңа хабарламалар',
'newmessagesdifflink' => 'соңғы өзгерiсіне',
'youhavenewmessagesfromusers' => 'Сіз {{PLURAL:$3|басқа қатысушыдан|$3 қатысушыдан}} $1 алдыңыз ($2).',
'youhavenewmessagesmanyusers' => 'Сіз бірнеше қатысушыдан $1 алдыңыз ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|жаңа хабарлама|жаңа хабарламалар}}',
'newmessagesdifflinkplural' => 'соңғы {{PLURAL:$1|өзгеріс|өзгерістер}}',
'youhavenewmessagesmulti' => '$1 дегенде жаңа хабарламалар бар',
'editsection' => 'өңдеу',
'editold' => 'өңдеу',
'viewsourceold' => 'қайнарын қарау',
'editlink' => 'өңдеу',
'viewsourcelink' => 'қайнарын қарау',
'editsectionhint' => 'Бөлімді өңдеу: $1',
'toc' => 'Мазмұны',
'showtoc' => 'көрсету',
'hidetoc' => 'жасыру',
'collapsible-collapse' => 'Түру',
'collapsible-expand' => 'Жазу',
'thisisdeleted' => '$1 қарайсыз ба, немесе қалпына келтіресіз бе?',
'viewdeleted' => '$1 қарайсыз ба?',
'restorelink' => '{{PLURAL:$1|жойылған өңдеме|$1 жойылған өңдемелер}}',
'feedlinks' => 'Арна:',
'feed-invalid' => 'Жарамсыз жазылымды арна түрі.',
'feed-unavailable' => 'Синдикация таспалары қолжетімсіз',
'site-rss-feed' => '$1 RSS арнасы',
'site-atom-feed' => '$1 Atom арнасы',
'page-rss-feed' => '«$1» — RSS арнасы',
'page-atom-feed' => '«$1» — Atom арнасы',
'red-link-title' => '$1 (мұндай бет жоқ)',
'sort-descending' => 'Кему бойынша ретке келтіру',
'sort-ascending' => 'Өсу бойынша ретке келтіру',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Мақала',
'nstab-user' => 'Жеке бет',
'nstab-media' => 'Медиа беті',
'nstab-special' => 'Арнайы бет',
'nstab-project' => 'Жоба беті',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'Хабарлама',
'nstab-template' => 'Үлгі',
'nstab-help' => 'Анықтама',
'nstab-category' => 'Санат',

# Main script and global functions
'nosuchaction' => 'Мұндай әрекет жоқ',
'nosuchactiontext' => 'URL-дегі көрсетілген әрекет қате.
Бәлкім, Сіз URL теру барысында қате жібердіңіз немесе қате сілтеме бойынша өттіңіз.
Бұл сондай-ақ {{SITENAME}} жобасында қателікті көрсетуі мүмкін.',
'nosuchspecialpage' => 'Мұндай арнайы бет жоқ',
'nospecialpagetext' => '<strong>Сіздің сұраған арнайы бетіңіз жоқ.</strong>

Бар арнайы беттер тізімі: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error' => 'Қате',
'databaseerror' => 'Дерекқор қатесі',
'databaseerror-query' => 'Сұрау:$1',
'databaseerror-error' => 'Қате:$1',
'laggedslavemode' => "'''Ескерту:''' Бетте жуықтағы жаңартулар болмауы мүмкін.",
'readonly' => 'Дерекқоры құлыпталған',
'enterlockreason' => 'Құлыптау себебін, қай уақытқа дейін құлыпталғанын кірістіріп, енгізіңіз.',
'readonlytext' => 'Бұл дерекқор жаңадан жазу және басқа өзгерістер жасаудан ағымда құлыпталынған, мүмкін күнде-күн дерекқорды баптау үшін, бұны бітіргеннен соң қалыпты іске қайтарылады.

Құлыптаған әкімші бұны былай түсіндіреді: $1',
'missing-article' => 'Бар болуы жөн былай аталған бет мәтіні дерекқорда табылмады: «$1» $2.

Бұл ескірген айырма сілтемесіне немесе жойылған бет тарихы сілтемесіне ергеннен бола береді.

Егер бұл орынды болмаса, бағдарламалық жасақтамадағы қатеге тап болуыңыз мүмкін.
Бұл туралы нақты URL жайына аңғартпа жасап, [[Special:ListUsers/sysop|әкімшіге]] баяндаңыз.',
'missingarticle-rev' => '(түзету нұсқасы#: $1)',
'missingarticle-diff' => '(Айырым: $1, $2)',
'readonly_lag' => 'Жетек дерекқор серверлер басқасымен қадамланғанда осы дерекқор өздіктік құлыпталынған',
'internalerror' => 'Ішкі қате',
'internalerror_info' => 'Ішкі қатесі: $1',
'fileappenderrorread' => 'Толықтыру кезінде «$1» оқылмады.',
'fileappenderror' => '«$2» -ге  "$1" -ді қосу мүмкін болмады.',
'filecopyerror' => '«$1» файлы «$2» файлына көшірілмеді.',
'filerenameerror' => '«$1» файл атауы «$2» атауына өзгертілмеді.',
'filedeleteerror' => '«$1» файлы жойылмайды.',
'directorycreateerror' => '«$1» қалтасы құрылмады.',
'filenotfound' => '«$1» файлы табылмады.',
'fileexistserror' => '«$1» файлға жазу икемді емес: файл бар',
'unexpected' => 'Күтілмеген мағына: «$1» = «$2».',
'formerror' => 'Қателік: пішін жөнелтілмейді',
'badarticleerror' => 'Осындай әрекет мына бетте атқарылмайды.',
'cannotdelete' => '«$1» бетін немесе файлын жою мүмкін емес. 
Мұны әлдекім жойған болуы мүмкін.',
'cannotdelete-title' => '«$1» бетін жою мүмкін емес',
'delete-hook-aborted' => 'Түзету ілмек арқылы тоқтатылды.
Қосымша түсіндірмелер көрсетілмеген.',
'badtitle' => 'Жарамсыз тақырып аты',
'badtitletext' => 'Сұралған бет тақырыбының аты жарамсыз, бос, тіларалық сілтемесі не уики-аралық тақырып аты бұрыс енгізілген.
Мында тақырып атында қолдалмайтын бірқатар таңбалар болуы мүмкін.',
'perfcached' => 'Келесі дерек бүркемеленген, сондықтан толықтай жаңаланбаған болуы мүмкін. A maximum of {{PLURAL:$1|one result is|$1 results are}} available in the cache.',
'perfcachedts' => 'Келесі дерек бүркемеленген, соңғы жаңаланған кезі: $1. Кэште {{PLURAL:$4|жазбалардан}} артық сақталмайды..',
'querypage-no-updates' => 'Бұл беттің жаңартылуы ағымда өшірілген. Деректері қазір өзгертілмейді.',
'wrong_wfQuery_params' => 'wfQuery() функциясы үшін бұрыс бапталымдары бар<br />
Жете: $1<br />
Сұраным: $2',
'viewsource' => 'Қайнарын қарау',
'viewsource-title' => '$1 бетінің бастапқы мәтінін қарау',
'actionthrottled' => 'Әрекет бәсеңдетілді',
'actionthrottledtext' => 'Спамға қарсы күрес есебінде, осы әрекетті қысқа уақытта тым көп рет орындауыңыз шектелінді, және бұл шектеу шамасынан асып кеткенсіз.
Бірнеше минуттан қайта байқап көріңіз.',
'protectedpagetext' => 'Бұл бет өңдеу немесе басқа өзгерістер енгізілмес үшін қорғалған.',
'viewsourcetext' => 'Бұл беттің қайнарын қарауыңызға және көшіріп алуыңызға болады:',
'viewyourtext' => 'Осы бет арқылы "өзіңіз жасаған өңдеулердің" бастапқы мәтінін көруге және көшіруге мүмкіндігіңіз болады.',
'protectedinterface' => 'Бұл MediaWiki-дің [[Уикипедия:Интерфейсті аудару|жүйе хабарламасы]], оны тек жоба [[Уикипедия:Әкімшілер|әкімшілер]] ғана өзгерте алады. 
Кейбір хабарламалар [[translatewiki:{{FULLPAGENAME}}/qqq|құжаттамада]] [[mw:Manual:Interface/{{PAGENAME}}|бар]].',
'editinginterface' => "'''Ескерту:''' Бағдарламалық жасақтаманың тілдесу мәтінін жетістіретін бетін өңдеп жатырсыз.
Бұл беттің өзгертуі басқа қатысушыларға пайдаланушылық тілдесуі қалай көрінетіне әсер етеді.
Барлық уикилер үшін аудармаларды өзгерту немесе қосу үшін [//translatewiki.net/ translatewiki.net] МедиаУики жерлестіру жобасын пайдаланыңыз.",
'cascadeprotected' => 'Бұл бет өңдеуден қорғалған, себебі бұл келесі «баулы қорғауы» қосылған {{PLURAL:$1|беттің|беттердің}} кірікбеті:
$2',
'namespaceprotected' => "'''$1''' есім аясындағы беттерді өңдеу үшін рұқсатыңыз жоқ.",
'customcssprotected' => 'Сіздің бұл CSS-бетті өңдеуге рұқсатыңыз жоқ, себебі мұнда өзге қатысушының жеке баптаулары бар.',
'customjsprotected' => 'Сіздің бұл JavaScript бетін өңдеуге рұқсатыңыз жоқ, себебі мұнда өзге қатысушының жеке баптаулары бар.',
'mycustomcssprotected' => 'Сізде CSS бетін өңдеуге рұқсатыңыз жоқ.',
'mycustomjsprotected' => 'Сізде JavaScript бетін өңдеуге рұқсатыңыз жоқ.',
'myprivateinfoprotected' => 'Сізде жеке ақпараттарыңызды өңдеу рұқсатыңыз жоқ.',
'mypreferencesprotected' => 'Сізде баптауларыңызды өңдеуге рұқсатыңыз жоқ.',
'ns-specialprotected' => '{{ns:special}} есім аясындағы беттер өңдеуге келмейді.',
'titleprotected' => "Бұл тақырып аты бастаудан [[User:$1|$1]] қорғады.
Келтірілген себебі: ''$2''.",
'filereadonlyerror' => "«$2» сақтамасы «тек қана оқу» тәртіптемесінде тұрғасын, «$1» файлын өзгерту мүмкін емес.
Бұл тәртіптемені қондырған әкімші келесі түсіндірмені қалдырды: «''$3''»",
'invalidtitle-knownnamespace' => '"$2" есім кеңістік түрі және  "$3" мәтіні жарамсыз',
'invalidtitle-unknownnamespace' => 'Нөмері $1 белгісіз есім кеңістік түрі және "$2" мәтіні жарамсыз',
'exception-nologin' => 'Кірмегенсіз',
'exception-nologin-text' => 'Бұл бет немесе әрекет бұл уикиге кіріуіңізді міндеттейді.',

# Virus scanner
'virus-badscanner' => 'Дұрыс емес ішқұрылым. Белгісіз вирус сканері: $1',
'virus-scanfailed' => 'сканерлеу орындалмады (код $1)',
'virus-unknownscanner' => 'белгісіз антивирус:',

# Login and logout pages
'logouttext' => "'''Жүйеден шықтыңыз.'''

Жүйеге кірмесеңіз де {{SITENAME}} жобасын пайдалана аласыз, немесе баяғы не өзге қатысушы ретінде жүйеге <span class='plainlinks'>[$1 қайта кіруіңізге]</span> болады.
Аңғартпа: Кейбір беттер шолғышыңыздың кэшін тазартқанша әлі де жүйеге кіріп отырғаныңыздай көрінуі мүмкін.",
'welcomeuser' => 'Қош келдіңіз, $1!',
'welcomecreation-msg' => 'Сіздің тіркеліміңіз жасалынды.
[[Special:Preferences|{{SITENAME}} баптауларыңызды]] өзгертуді ұмытпаңыз.',
'yourname' => 'Қатысушы аты:',
'userlogin-yourname' => 'Қатысушы есіміңіз',
'userlogin-yourname-ph' => 'Қатысушы есіміңізді енгізіңіз',
'createacct-another-username-ph' => 'Қатысушы есіміңізді енгізіңіз',
'yourpassword' => 'Құпия сөз:',
'userlogin-yourpassword' => 'Құпия сөз',
'userlogin-yourpassword-ph' => 'Құпия сөздіңізді енгізіңіз',
'createacct-yourpassword-ph' => 'Құпия сөзді енгізу',
'yourpasswordagain' => 'Құпия сөзді қайталаңыз:',
'createacct-yourpasswordagain' => 'Құпия сөзді құптаңыз',
'createacct-yourpasswordagain-ph' => 'Құпия сөзіңізді қайтадан енгізіңіз',
'remembermypassword' => 'Тіркелгімді осы браузерде ұмытпа (ең көбі $1 {{PLURAL:$1|күн|күн}})',
'userlogin-remembermypassword' => 'Мені жүйеде сақтап қою',
'userlogin-signwithsecure' => 'Құпия байланысуды қолдану',
'yourdomainname' => 'Желі үйшігіңіз:',
'password-change-forbidden' => 'Сіз бұл уикиде құпия сөзіңізді өзгерте алмайсыз.',
'externaldberror' => 'Осы арада не шеттік растау дерекқорында қате болды, немесе шеттік тіркелгіңізді жаңалау рұқсаты жоқ.',
'login' => 'Кіру',
'nav-login-createaccount' => 'Кіру / Тіркелу',
'loginprompt' => '{{SITENAME}} торабына кіруіңіз үшін «cookies» қосылуы жөн.',
'userlogin' => 'Кіру / Тіркелу',
'userloginnocreate' => 'Кіру',
'logout' => 'Шығу',
'userlogout' => 'Шығу',
'notloggedin' => 'Кірмегенсіз',
'userlogin-noaccount' => 'Тіркелгіңіз жоқ па?',
'userlogin-joinproject' => '{{SITENAME}} жобасына тіркелу',
'nologin' => 'Тіркелгіңіз бар ма? $1.',
'nologinlink' => 'Тіркелгіңізді жасаңыз',
'createaccount' => 'Жаңа тіркелгі',
'gotaccount' => "Бұған дейін тіркеліп пе едіңіз? '''$1'''.",
'gotaccountlink' => 'Кіріңіз',
'userlogin-resetlink' => 'Қатысушы атын не құпия сөзді ұмыттыңыз ба?',
'userlogin-resetpassword-link' => 'Құпия сөздіңізді ысыру',
'helplogin-url' => 'Help:Тіркелу',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Тіркелуге көмек]]',
'createacct-join' => 'Төменге өзіңіз туралы ақпарат енгізіңіз.',
'createacct-another-join' => 'Төменге жаңа тіркелгі туралы ақпарат енгізіңіз.',
'createacct-emailrequired' => 'Е-пошта мекен-жайы:',
'createacct-emailoptional' => 'Е-поштаның мекен-жайы (міндетті емес)',
'createacct-email-ph' => 'Е-пошта мекен-жайыңызды енгізіңіз',
'createacct-another-email-ph' => 'Е-пошта мекен-жайын енгізіңіз',
'createaccountmail' => 'Уақытша берілген кілтсөзді пайдаланыңыз және оны көрсетілген электрондық поштаға жіберіңіз',
'createacct-realname' => 'Нақты атыңыз (ерікті)',
'createaccountreason' => 'Себебі:',
'createacct-reason' => 'Себебі:',
'createacct-reason-ph' => 'Неге басқа тіркегі жасамақшысыз',
'createacct-captcha' => 'Құпиялық тексеруі',
'createacct-imgcaptcha-ph' => 'Жоғарғыдағы көріп тұрған мәтінді енгізіңіз',
'createacct-submit' => 'Тіркелгіңізді жасаңыз',
'createacct-another-submit' => 'Басқа тіркелгі жасау',
'createacct-benefit-heading' => '{{SITENAME}} сіз сияқты қызығатын адамдар арқылы жасалады.',
'createacct-benefit-body1' => '{{PLURAL:$1|өңдеме|өңдеме}}',
'createacct-benefit-body2' => '{{PLURAL:$1|бет|бет}}',
'createacct-benefit-body3' => 'жуықтағы {{PLURAL:$1|қатысушы|қатысушы}}',
'badretype' => 'Енгізген құпия сөздеріңіз бір-біріне сәйкес емес.',
'userexists' => 'Енгізген қатысушы атыңыз әлдеқашан пайдалануда.
Өзге атауды таңдаңыз.',
'loginerror' => 'Кіру қатесі',
'createacct-error' => 'Тіркелгі жасауада қате кетті',
'createaccounterror' => 'Тіркелгіні жасау мүмкін емес: $1',
'nocookiesnew' => 'Жаңа қатысушы тіркелгісі жасалды, бірақ кірмегенсіз.
Қатысушы кіру үшін {{SITENAME}} торабында «cookie» файлдары қолданылады.
Сізде «cookies» өшірілген.
Соны қосыңыз да жаңа қатысушы атыңызды және құпия сөзіңізді енгізіп кіріңіз.',
'nocookieslogin' => 'Қатысушы кіру үшін {{SITENAME}} торабында «cookies» деген қолданылады.
Сізде «cookies» өшірілген.
Соны қосыңыз да кіруді қайта байқап көріңіз.',
'nocookiesfornew' => 'Оның қайнарын растай алмағандықтан қатысушының аккаунты тіркелмеді. «Cookies» қосылып тұрғанына көз жеткізіңіз, бетті қайта жаңартыңыз және тағы байқап көріңіз.',
'noname' => 'Жарамды қатысушы атын енгізбедіңіз.',
'loginsuccesstitle' => 'Кіруіңіз сәтті өтті',
'loginsuccess' => "'''Сіз енді {{SITENAME}} жобасына «$1» ретінде кірдіңіз.'''",
'nosuchuser' => '«$1» деген қатысушы тіркелмеген.
Қатысушы аттары кіші әріптерден тұру керек.
Емлеңізді тексеріңіз, немесе [[Special:UserLogin/signup|жаңа тіркелгі жасаңыз]].',
'nosuchusershort' => 'Мұнда «$1» деп аталған қатысушы жоқ.
Емлеңізді тексеріңіз.',
'nouserspecified' => 'Қатысушы атын келтіруіңіз жөн.',
'login-userblocked' => 'Бұл қатысушы бұғатталған. Жүйеге кiру рұқсат етiлмеген.',
'wrongpassword' => 'Бұрыс құпия сөз енгізілген. Қайта байқап көріңіз.',
'wrongpasswordempty' => 'Құпия сөз бос болған. Қайта байқап көріңіз.',
'passwordtooshort' => 'Құпия сөзіңіз жарамсыз немесе тым қысқа.
Бұнда ең кемінде $1 таңба болуы керек.',
'password-name-match' => 'Енгізген құпия сөзіңіз қатысушы атынан өзгеше болуы қажет.',
'password-login-forbidden' => 'Бұл қатысушы аты мен құпия сөзін пайдалануға тыйым салынған.',
'mailmypassword' => 'Жаңа құпия сөзді хатпен жіберу',
'passwordremindertitle' => '{{SITENAME}} үшін жаңа уақытша құпия сөз',
'passwordremindertext' => 'Біреу (IP мекенжайы: $1, бәлкім өзіңіз боларсыз) {{SITENAME}} үшін жаңа құпия сөз жөнелету сұранымын жасаған ($4).
Қатысушы «$2» үшін уақытша құпия сөз жасалды: «$3». Егер бұл Сіздің сұранымыңыз болса, жүйеге кіріп құпия сөзді өзгертуіңіз керек. Сіздің уақытша құпия сөзіңіз $5 дейін белсенді болады.

Егер бұл сұранымды Сіз жасамасыңыз, не құпия сөзді еске түсіріп енді өзгерткіңіз келмесе, ескі құпия сөзді қолдануды жалғастырып осы хатқа аңғармауыңызға да болады.',
'noemail' => 'Осы арада «$1» қатысушының е-пошта мекенжайы жоқ.',
'noemailcreate' => 'Сізге нақты жарамды электрондық пошта мекен-жайын көрсету керек.',
'passwordsent' => 'Жаңа құпия сөз «$1» үшін тіркелген е-пошта мекенжайына жөнелтілді.
Қабылдағаннан кейін кіргенде соны енгізіңіз.',
'blocked-mailpassword' => 'IP мекенжайыңыздан өңдеу бұғатталған, сондықтан қиянатты қақпайлау үшін құпия сөзді қалпына келтіру жетесін қолдануына рұқсат етілмейді.',
'eauthentsent' => 'Құптау хаты айтылмыш е-пошта мекенжайына жөнелтілді.
Басқа е-пошта хатын жөнелту алдынан, тіркелгі шынынан сіздікі екенін құптау үшін хаттағы нұсқамаларға еріуңіз жөн.',
'throttled-mailpassword' => 'Соңғы {{PLURAL:$1|сағатта|$1 сағатта}} құпия сөз ескерту хаты алдақашан жөнелтілді.
Қиянатты қақпайлау үшін, {{PLURAL:$1|сағат|$1 сағат}} сайын тек бір ғана құпия сөз ескерту хаты жөнелтіледі.',
'mailerror' => 'Хат жөнелту қатесі: $1',
'acct_creation_throttle_hit' => 'Ғафу етіңіз, сіз алдақашан $1 рет тіркелгі жасапсыз. Онан артық жасай алмайсыз.
Нәтижесінде, осы IP-мекенжаймен кірушілер дәл қазіргі уақытта бірнеше тіркелгі жасай алмайды.',
'emailauthenticated' => 'Е-пошта мекен-жайыңыз расталған кезі: $3, $2.',
'emailnotauthenticated' => 'Е-пошта мекен-жайыңыз әлі расталған жоқ.
Келесі әрбір мүмкіндіктер үшін еш хат жөнелтілмейді.',
'noemailprefs' => 'Осы мүмкіндіктер істеуі үшін е-пошта мекен-жайыңызды енгізіңіз.',
'emailconfirmlink' => 'Е-пошта мекен-жайыңызды құптаңыз',
'invalidemailaddress' => 'Бұл е-пошта есімі пішімге сәйкес келмегендіктен қабылданбайды.
Дұрыс пішімделген е-пошта есімін енгізіңіз, немесе аумақты бос қалдырыңыз.',
'cannotchangeemail' => 'Тіркелгінің е-поштасының мекен-жайы бұл уикиде өзгертілмейді.',
'emaildisabled' => 'Бұл сайт е-поштаның хабарламасын жібере алмайды.',
'accountcreated' => 'Тіркелгі жасалды',
'accountcreatedtext' => '[[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|талқылауы]]) үшін жаңа қатысушы тіркелгісі жасалды.',
'createaccount-title' => '{{SITENAME}} үшін тіркелу',
'createaccount-text' => 'Кейбіреу е-пошта мекенжайыңызды пайдаланып {{SITENAME}} жобасында ($4) «$2» атауымен, «$3» құпия сөзімен тіркелгі жасаған.
Жобаға кіріуіңіз және құпия сөзіңізді өзгертуіңіз тиісті.

Егер бұл тіркелгі қателікпен жасалса, осы хабарға елемеуіңіз мүмкін.',
'usernamehasherror' => 'Қатысушы есіміне тор белгі нышаны енгізілмейді.',
'login-throttled' => 'Сіз жүйеге кіру үшін тым көп талпыныс жасадыңыз. Өтінемін, қайта кірмес бұрын $1 күте тұрыңыз.',
'login-abort-generic' => 'Жүйеге кіру үшін сәтсіз талпыныс жасадыңыз.',
'loginlanguagelabel' => 'Тіл: $1',
'suspicious-userlogout' => 'Сіздің жүйеден шығу сұранымыңыз қабылданбады, өйткені, бұл жарамсыз браузер немесе кэштеуші проксидің сұранымына ұқсайды.',

# Email sending
'php-mail-error-unknown' => 'Mail() PHP-функциясындағы белгісіз қате.',
'user-mail-no-addy' => 'Е-пошта есімінсіз хабарлама жіберуге талпынды.',

# Change password dialog
'resetpass' => 'Тіркелгінің құпия сөзін өзгерту',
'resetpass_announce' => 'Хатпен жіберілген уақытша кодымен кіргенсіз.
Кіруіңізді бітіру үшін, жаңа құпия сөзіңізді мында енгізуіңіз жөн:',
'resetpass_header' => 'Құпия сөзді өзгерту',
'oldpassword' => 'Ескі құпия сөзіңіз:',
'newpassword' => 'Жаңа құпия сөзіңіз:',
'retypenew' => 'Жаңа құпия сөзіңізді қайталаңыз:',
'resetpass_submit' => 'Құпия сөзді қойыңыз да кіріңіз',
'changepassword-success' => 'Құпия сөзіңіз сәтті өзгертілді!',
'resetpass_forbidden' => 'Құпия сөз өзгертілмейді',
'resetpass-no-info' => 'Бұл бетке тікелей ену үшін, жүйеге кіруіңіз керек.',
'resetpass-submit-loggedin' => 'Құпия сөзді өзгерту',
'resetpass-submit-cancel' => 'Болдырмау',
'resetpass-wrong-oldpass' => 'Уақытша немесе ағымдағы құпия сөзіңіз дұрыс емес.
Мүмкін Сіз құпия сөзді сәтті өзгерткенсіз, немесе жаңа уақытша құпия сөзге сұраным жасағансыз.',
'resetpass-temp-password' => 'Уақытша құпия сөз:',
'resetpass-abort-generic' => 'Құпия сөзді өзгерту кеңейтпенің әсерінен аяқталмады.',

# Special:PasswordReset
'passwordreset' => 'Құпия сөзді қайтару',
'passwordreset-text-one' => 'Құпия сөзіңізді түзеу үшін бұл пішінді толтырыңыз.',
'passwordreset-text-many' => '{{PLURAL:$1|Құпия сөзді қайтару үшін жолақтарды толтырыңыз.}}',
'passwordreset-legend' => 'Құпия сөзді қайтару',
'passwordreset-disabled' => 'Бұл уикиде құпия сөзді қайтару ажыратылған.',
'passwordreset-emaildisabled' => 'E-mail мүмкіндігі бұл уикиде өшірілген.',
'passwordreset-username' => 'Қатысушы аты:',
'passwordreset-domain' => 'Домен:',
'passwordreset-capture' => 'Келген хатты қарау керек пе?',
'passwordreset-capture-help' => 'Егер Сіз берілген белгішені қондырсаңыз, қатысушыға жіберілетін уақытша құпия сөз жазылған хат көрсетіледі.',
'passwordreset-email' => 'Е-поштаның мекен-жайы:',
'passwordreset-emailtitle' => '{{SITENAME}} тіркелгісі туралы анықтама',
'passwordreset-emailelement' => 'Қатысушы есімі: $1
Уақытша құпия сөз: $2',
'passwordreset-emailsent' => 'Құпия сөзді өзгерту электронды пошта арқылы жөнелтілді.',
'passwordreset-emailsent-capture' => 'Құпия сөзді өзгерту электронды пошта арқылы жөнелтілді, ол төменде көрсетілген.',
'passwordreset-emailerror-capture' => 'Жазылған ескертпе-хат төменде көрсетілген, оның жөнелтілмеу себебі: $1',

# Special:ChangeEmail
'changeemail' => 'Е-пошта мекен-жайын өзгерту',
'changeemail-header' => 'Е-пошта мекен-жайының өзгертілуі',
'changeemail-text' => 'Е-поштаның мекен-жайын өзгерту үшін мына пішінді толтырыңыз. Өзгертулерді растау үшін Сізге құпия сөзді енгізу керек.',
'changeemail-no-info' => 'Бұл бетке тікелей ену үшін, жүйеге кіруіңіз керек.',
'changeemail-oldemail' => 'Е-поштаның ағымдағы мекен-жайы:',
'changeemail-newemail' => 'Е-поштаның жаңа мекен жайы:',
'changeemail-none' => '(ешкім)',
'changeemail-password' => 'Сіздің {{SITENAME}} жобасындағы құпия сөзіңіз:',
'changeemail-submit' => 'Е-поштаны өзгерту',
'changeemail-cancel' => 'Болдырмау',

# Edit page toolbar
'bold_sample' => 'Жуан мәтін',
'bold_tip' => 'Жуан мәтін',
'italic_sample' => 'Қиғаш мәтін',
'italic_tip' => 'Қиғаш мәтін',
'link_sample' => 'Сілтеме тақырыбының аты',
'link_tip' => 'Ішкі сілтеме',
'extlink_sample' => 'http://www.мысал.com сілтеме тақырыбының аты',
'extlink_tip' => 'Шеттік сілтеме (алдынан http:// енгізуін ұмытпаңыз)',
'headline_sample' => 'Бас жол мәтіні',
'headline_tip' => '2-ші деңгейлі бас жол',
'nowiki_sample' => 'Пішімделінбеген мәтінді мында енгізіңіз',
'nowiki_tip' => 'Уики пішімін елемеу',
'image_sample' => 'Мысал.jpg',
'image_tip' => 'Ендірілген файл',
'media_tip' => 'Файл сілтемесі',
'sig_tip' => 'Қолтаңбаңыз және уақыт белгісі',
'hr_tip' => 'Көлденең сызық (үнемді қолданыңыз)',

# Edit pages
'summary' => 'Түйіндемесі:',
'subject' => 'Тақырыбы/бас жолы:',
'minoredit' => 'Бұл шағын өңдеме',
'watchthis' => 'Бұл бетті бақылау',
'savearticle' => 'Бетті сақтау',
'preview' => 'Қарап шығу',
'showpreview' => 'Алдын ала қарау',
'showlivepreview' => 'Жылдам қарау',
'showdiff' => 'Өзгерістерді көрсет',
'anoneditwarning' => "'''Ескерту:''' Сіз жүйеге кірмегенсіз.
IP-мекенжайыңыз бұл беттің түзету тарихында жазылып алынады.",
'anonpreviewwarning' => '"Сіз жүйеге кірмегенсіз. IP-мекенжайыңыз бұл беттің өңдеу тарихында жазылып алынады."',
'missingsummary' => "'''Ескерту:''' Өңдеменің қысқаша түйіндемесін енгізбепсіз.
«Сақтау» түймесін қайта бассаңыз, өңденмеңіз түйіндемесіз сақталады.",
'missingcommenttext' => 'Мәндемеңізді төменде енгізіңіз.',
'missingcommentheader' => "'''Ескерту:''' Бұл мәндемеге тақырып/басжол жазбапсыз.
«{{int:savearticle}}» түймесін тағы бассаңыз, өңдемеңіз түйіндемесіз жазылады.",
'summary-preview' => 'Қысқаша түйіндемесін қарап шығу:',
'subject-preview' => 'Тақырыбын/бас жолын қарап шығу:',
'blockedtitle' => 'Қатысушы бұғатталған',
'blockedtext' => "'''Қатысушы атыңыз не IP мекенжайыңыз бұғатталған.'''

Осы бұғаттауды $1 істеген. Келтірілген себебі: ''$2''.

* Бұғаттаудың басталғаны: $8
* Бұғаттаудың бітетіні: $6
* Бұғаттау нысанасы: $7

Осы бұғаттауды талқылау үшін $1, не өзге [[{{MediaWiki:Grouppage-sysop}}|әкімшімен]] қатынасуыңызға болады.
[[Special:Preferences|Тіркелгі бапталымдары]]ңызда жарамды е-пошта мекенжайын ұсынып және де оны пайдаланудан бұғатталмаған жағдайыңызда ғана «Қатысушыға хат жазу» қызметін қолдана аласыз.
Ағымдық IP мекенжайыңыз: $3, және бұғатау нөмірі: $5.
Сұраным жасағанда осының екеуін де кірістіруіңізді сұраймыз.",
'autoblockedtext' => "'''Қатысушы атыңыз не IP-мекенжайыңыз бұғатталған.'''

Осы бұғаттауды $1 істеген. Келтірілген себебі: ''$2''.

* Бұғаттаудың басталғаны: $8
* Бұғаттаудың бітетіні: $6
* Бұғаттау нысанасы: $7

Осы бұғаттауды талқылау үшін $1, не өзге [[{{MediaWiki:Grouppage-sysop}}|әкімшімен]] қатынасуыңызға болады.
[[Special:Preferences|Тіркелгі бапталымдары]]ңызда жарамды е-пошта мекенжайын ұсынып және де оны пайдаланудан бұғатталмаған жағдайыңызда ғана «Қатысушыға хат жазу» қызметін қолдана аласыз.
Ағымдық IP мекенжайыңыз: $3, және бұғатау нөмірі: $5.
Сұраным жасағанда осының екеуін де кірістіруіңізді сұраймыз.",
'blockednoreason' => 'еш себебі келтірілмеген',
'whitelistedittext' => 'Беттерді өңдеу үшін сізде $1 болуы керек.',
'confirmedittext' => 'Беттерді өңдеу үшін алдын ала Е-пошта мекенжайыңызды құптауыңыз жөн.
Е-пошта мекенжайыңызды [Special:Preferences}}|қатысушы бапталымдарыңыз]] арқылы қойыңыз да жарамдылығын тексеріп шығыңыз.',
'nosuchsectiontitle' => 'Бұл бөлімді табу мүмкін емес',
'nosuchsectiontext' => 'Сіз бұрын болмаған бөлімді өзгертпекшісіз.
Мүмкін бұл бетті қарап жатқаныңызда ол бөлім жойылған немесе басқа орынға көшірілген.',
'loginreqtitle' => 'Кіруіңіз керек',
'loginreqlink' => 'кіру',
'loginreqpagetext' => 'Басқа беттерді көру үшін сіз $1 болуыңыз жөн.',
'accmailtitle' => 'Құпия сөз жөнелтілді.',
'accmailtext' => "$2 жайына [[User talk:$1|$1]] үшін құпия сөзі жөнелтілді.
Бұл жаңа қатысушы үшін құпия сөз ''[[Special:ChangePassword|құпия сөзді өзгерту]]'' бетінде кіру үстінде өзгертілген.",
'newarticle' => '(Жаңа)',
'newarticletext' => 'Сілтемеге еріп әлі басталмаған бетке келіпсіз.
Бетті бастау үшін, төменгі терезеде мәтініңізді теріңіз (көбірек ақпарат үшін [[{{MediaWiki:Helppage}}|анықтама бетін]] қараңыз).
Егер жаңылғаннан осында келген болсаңыз, браузердің «артқа» деген батырмасын басыңыз.',
'anontalkpagetext' => "----''Бұл тіркелгісіз (немесе тіркелгісін қолданбаған) қатысушы талқылау беті. Осы қатысушыны біз тек сандық IP мекенжайымен теңдестіреміз.
Осындай IP мекенжай бірнеше қатысушыға ортақтастырылған болуы мүмкін.
Егер сіз тіркелгісіз қатысушы болсаңыз және сізге қатыссыз мәндемелер жіберілгенін сезсеңіз, басқа тіркелгісіз қатысушылармен араластырмауы үшін [[{{#special:Userlogin}}|тіркеліңіз не кіріңіз]].''",
'noarticletext' => "Ағымда бұл бетте еш мәтін жоқ.
* Басқа беттерден [[Special:Search/{{PAGENAME}}|бұл бет атауын іздеу]],
* <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} Журналдардан бұл бетке қатысты сәйкес жазбаларды табу]</span>,
* <span class=\"plainlinks\">'''[{{fullurl:{{FULLPAGENAME}}|action=edit}} Бұл бетті жаңадан бастау]'''</span>.",
'noarticletext-nopermission' => 'Ағымда бұл бетте еш мәтін жоқ.
Сіз [[Special:Search/{{PAGENAME}}|бұл бет атауын]] басқа беттерден іздей аласыз, немесе <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} журналдардан бұл бетке қатысты сәйкес жазбаларды таба аласыз]</span>. Ал бұл бетті жаңадан бастауға сізде рұқсат жоқ.',
'userpage-userdoesnotexist' => '«<nowiki>$1</nowiki>» қатысушы тіркелгісі жазып алынбаған. Бұл бетті бастау/өңдеу талабыңызды тексеріп шығыңыз.',
'userpage-userdoesnotexist-view' => '«$1» қатысушы есімі тіркелмеген.',
'blocked-notice-logextract' => 'Бұл қатысушы қазіргі уақытта  бұғатталған.
Төменде бұғаттау журналындағы соңғы жазбалар көрсетілген.',
'clearyourcache' => "'''Ескерту:''' Сақтағаннан кейін, өзгерістерді көру үшін шолғыш бүркемесін орағыту ықтимал. 
*'''Firefox / Safari:''' ''Қайта жүктеу'' батырмасын нұқығанда ''Shift'' тұтыңыз, не ''Ctrl-Shift-R'' басыңыз (Apple Mac — ''Cmd-Shift-R''); 
* '''Google Chrome:'''  ''Ctrl-Shift-R'' басыңыз (Mac — ''⌘-Shift-R'')
*'''IE:''' ''Жаңарту'' батырмасын нұқығанда ''Ctrl'' тұтыңыз, не ''Ctrl-F5'' басыңыз;  не ''F5'' басыңыз; *'''Opera''' пайданушылары ''Құралдар→Бапталымдар'' дегенге барып бүркемесін толық тазарту жөн.",
'usercssyoucanpreview' => "'''Кеңес:''' Жаңа CSS файлын сақтау алдында «Қарап шығу» батырмасын қолданып сынақтаңыз.",
'userjsyoucanpreview' => "'''Ақыл-кеңес:''' Жаңа JS файлын сақтау алдында «Қарап шығу» батырмасын қолданып сынақтаңыз.",
'usercsspreview' => "'''Мынау CSS мәтінін тек қарап шығу екенін ұмытпаңыз, ол әлі сақталған жоқ!'''",
'userjspreview' => "'''Мынау JavaScript қатысушы бағдарламасын тексеру/қарап шығу екенін ұмытпаңыз, ол әлі сақталған жоқ!'''",
'sitecsspreview' => "'''Мынау CSS қатысушы бағдарламасын тексеру/қарап шығу екенін ұмытпаңыз, ол әлі сақталған жоқ!'''",
'sitejspreview' => "'''Мынау JavaScript кодын тексеру/қарап шығу екенін ұмытпаңыз, ол әлі сақталған жоқ!'''",
'userinvalidcssjstitle' => "'''Ескерту:''' Осы арада «$1» деген еш мәнер жоқ.
Қатысушының .css және .js файл атауы кіші әріпппен жазылу тиісті екенін ұмытпаңыз, мысалға {{ns:user}}:Foo/vector.css дегенді {{ns:user}}:Foo/Vector.css дегенмен салыстырып қараңыз.",
'updated' => '(Жаңартылған)',
'note' => "'''Аңғартпа:'''",
'previewnote' => "Бұл тек '''қарап шығу''' екенін ұмытпаңыз, сіздің өзгертулеріңіз әлі сақталған жоқ!",
'continue-editing' => 'Өңдеу аумағына өту',
'previewconflict' => 'Бұл қарап шығу беті жоғарғы кірістіру орнындағы мәтінді қамтиды да және сақталғандағы өңді көрсетпек.',
'session_fail_preview' => "'''Ғафу етіңіз! Сессия деректері жоғалуы салдарынан өңдемеңізді бітіре алмаймыз.
Қайта байқап көріңіз. Егер бұл әлі істелмесе, шығуды және қайта кіруді байқап көріңіз.'''",
'session_fail_preview_html' => "'''Ғафу етіңіз! Сессия деректері жоғалуы салдарынан өңдемеңізді бітіре алмаймыз.'''

''{{SITENAME}} жобасында қам HTML қосылған, JavaScript шабуылдардан қорғану үшін алдын ала қарап шығу жасырылған.''

'''Егер бұл өңдеме адал талап болса, қайта байқап көріңіз. Егер бұл әлі істемесе, шығуды және қайта кіруді байқап көріңіз.'''",
'token_suffix_mismatch' => "'''Өңдемеңіз тайдырылды, себебі тұтынғышыңыз өңдеме деректер бумасындағы тыныс белгілерін бүлдіртті.
Бет мәтіні бүлінбеу үшін өңдемеңіз тайдырылады.
Бұл кей уақытта қатесі толған веб-негізінде тіркелуі жоқ прокси-серверді пайдаланған болуы мүмкін.'''",
'editing' => 'Өңделуде: $1',
'creating' => 'Жаңадан бастау: $1',
'editingsection' => 'Өңделуде: $1 (бөлімі)',
'editingcomment' => 'Өңделуде: $1 (жаңа бөлім)',
'editconflict' => 'Өңдемелер қақтығысы: $1',
'explainconflict' => "Осы бетті сіз өңдей бастағанда басқа біреу бетті өзгерткен.
Жоғарғы кірістіру орнында беттің ағымдық мәтіні бар.
Төменгі кірістіру орнында сіз өзгерткен мәтіні көрсетіледі.
Өзгертуіңізді ағымдық мәтінге үстеуіңіз жөн.
\"{{int:savearticle}}\" батырмасын басқанда '''тек''' жоғарғы кірістіру орнындағы мәтін сақталады.",
'yourtext' => 'Мәтініңіз',
'storedversion' => 'Сақталған нұсқасы',
'nonunicodebrowser' => "'''ЕСКЕРТУ: Шолғышыңыз Unicode белгілеуіне үйлесімді емес, сондықтан латын емес әріптері бар беттерді өңдеу зіл болу мүмкін.
Жұмыс істеуге ықтималдық беру үшін, төмендегі кірістіру орнында ASCII емес таңбалар оналтылық кодымен көрсетіледі'''.",
'editingold' => "'''ЕСКЕТУ: Осы беттің ертерек түзетуін өңдеп жатырсыз.'''
Бұны сақтасаңыз, осы түзетуден кейінгі барлық өзгерістер жойылады.",
'yourdiff' => 'Айырмалар',
'copyrightwarning' => "Аңғартпа: {{SITENAME}} жобасына берілген барлық үлестер $2 (көбірек ақпарат үшін: $1) құжатына сай деп саналады.
Егер жазуыңыздың еркін өңделуін және ақысыз көпшілікке таратуын қаламасаңыз, мында жарияламауыңыз жөн.<br />
Тағы да, бұл мағлұмат өзіңіз жазғаныңызға, не қоғам қазынасынан немесе сондай ашық қорлардан көшірілгеніне бізге уәде бересіз.
'''АВТОРЛЫҚ ҚҰҚЫҚПЕН ҚОРҒАУҒАН МАҒЛҰМАТТЫ РҰҚСАТСЫЗ ЖАРИЯЛАМАҢЫЗ!'''",
'copyrightwarning2' => "Аңғартпа: {{SITENAME}} жобасына берілген барлық үлестерді басқа үлескерлер өңдеуге, өзгертуге, не аластауға мүмкін.
Егер жазуыңыздың еркін өңделуін қаламасаңыз, мында жарияламауыңыз жөн.<br />
Тағы да, бұл мағлұмат өзіңіз жазғаныңызға, не қоғам қазынасынан немесе сондай ашық қорлардан көшірілгеніне бізге уәде бересіз (көбірек ақпарат үшін $1 қужатын қараңыз).
'''АВТОРЛЫҚ ҚҰҚЫҚПЕН ҚОРҒАУҒАН МАҒЛҰМАТТЫ РҰҚСАТСЫЗ ЖАРИЯЛАМАҢЫЗ!'''",
'longpageerror' => "'''ҚАТЕЛІК: Сақтамақ мәтініңіздін мөлшері — {{PLURAL:$1|килобайт|$1 килобайт}}, ең көбі {{PLURAL:$2|килобайт|$2 килобайт}} KB рұқсат етілген мөлшерінен асқан.
Бұл сақталмайды.'''",
'readonlywarning' => "'''ЕСКЕТУ: Дерекқор баптау үшін құлыпталған, сондықтан дәл қазір өңдемеңізді сақтай алмайсыз.
Кейін қолдану үшін мәтінді қойып алып және қойып, мәтін файлына сақтауңызға болады.''' 
Әкімшінің құлыптау себебі келесідей: $1",
'protectedpagewarning' => "'''Ескерту: Бұл бет өңдеуден қорғалған. Тек әкімші құқықтары бар қатысушылар ғана өңдей алады.'''
Төменде бет журналының соңғы жазбасы көрсетілген:",
'semiprotectedpagewarning' => "'''Аңғартпа:''' Бет жартылай қорғалған, сондықтан осыны тек тіркелген қатысушылар өңдей алады.
Төменде бет журналының соңғы жазбасы көрсетілген:",
'cascadeprotectedwarning' => "'''Ескету''': Бұл бет құлыпталған, енді тек әкімші құқықтары бар қатысушылар ғана бұны өңдей алады.Бұның себебі: бұл бет «баулы қорғауы» бар келесі {{PLURAL:$1|беттің|беттердің}} кірікбеті:",
'titleprotectedwarning' => "'''Ескерту: Бұл бет атауы бастаудан қорғалған, сондықтан [[Special:ListGroupRights|арнайы құқықтары]] бар қатысушылар бұндай атаумен бетті бастай алады.'''
Төменде бет журналының соңғы жазбасы көрсетілген:",
'templatesused' => 'Бұл бетте қолданылған {{PLURAL:$1|үлгі|үлгілер}}:',
'templatesusedpreview' => 'Беттің қарап шығуында қолданылған {{PLURAL:$1|үлгі|үлгілер}}:',
'templatesusedsection' => 'Бұл бөлімде қолданылған {{PLURAL:$1|үлгі|үлгілер}}:',
'template-protected' => '(қорғалған)',
'template-semiprotected' => '(жартылай қорғалған)',
'hiddencategories' => 'Бұл бет $1 {{PLURAL:$1|1 жасырын санаттың|$1 жасырын санаттардың}}: мүшесі:',
'nocreatetext' => '{{SITENAME}} жобасында жаңа бет бастауы шектелген.
Кері қайтып бар бетті өңдеуіңізге болады, немесе [[Special:UserLogin|кіруіңізге не тіркелуіңізге]] болады.',
'nocreate-loggedin' => 'Жаңа бет бастауға рұқсатыңыз жоқ.',
'sectioneditnotsupported-title' => 'Бөлімдерді өңдеу қолдамайды',
'sectioneditnotsupported-text' => 'Бұл бетте бөлімдерді өңдеуді қолдамайды.',
'permissionserrors' => 'Рұқсат қатесі',
'permissionserrorstext' => 'Бұны істеуге рұқсатыңыз жоқ, келесі {{PLURAL:$1|себеп|себептер}} бойынша:',
'permissionserrorstext-withaction' => '$2 дегенге рұқсатыңыз жоқ, келесі {{PLURAL:$1|себеп|себептер}} бойынша:',
'recreate-moveddeleted-warn' => "'''Назар аудар: Алдында жойылған бетті қайта бастайын деп тұрсыз.'''

Бұл бетті жаңадан бастаудың орынды екеніне көз жеткізіңіз.
Төменде бұл бетке қатысты жою және жылжыту журналы көрсетілген:",
'moveddeleted-notice' => 'Бұл бет жойылған.
Төменде бұл бетке қатысты жою және жылжыту журналы көрсетілген:',
'log-fulllog' => 'Толық журналды қарау',
'edit-hook-aborted' => 'Түзету ілмек арқылы болдырмады.
Қосымша түсіндірмелер көрсетілмеген.',
'edit-gone-missing' => 'Бетті жаңарту мүмкін емес.
Мүмкін, бұл бет жойылған.',
'edit-conflict' => 'Өңдемелер қақтығысы.',
'postedit-confirmation' => 'Сіздің өңдемеңіз сақталды.',
'edit-already-exists' => 'Жаңа бет жасау мүмкін емес.
Ол әлдеқашан бар.',
'defaultmessagetext' => 'Әдепкі мәтіні',
'invalid-content-data' => 'Жарамсыз дерек мазмұны',
'editwarning-warning' => 'Басқа бетке өтсеңіз сіздің жазған соңғы өңдемелеріңіз жойылуы мүмкін. 
Егер сiз жүйеде тiркелсеңiз, онда сiз баптауларыңыздағы «{{int:prefs-editing}}» бөлігіне кіріп, бұл ескертуді өшіре аласыз.',

# Content models
'content-model-wikitext' => 'Уикимәтін',
'content-model-text' => 'қалыпты мәтін',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Ескерту:''' Бұл бетте тым көп шығыс алатын құрылым талдатқыш жетелерінің қоңырау шалулары бар.

Бұл $2  {{PLURAL:$2|call|calls}} шамасынан кем болуы жөн, осы арада {{PLURAL:$1|қазір $1 call|қазір $1 calls}}.",
'expensive-parserfunction-category' => 'Шығыс алатын құрылым талдатқыш жетелерінің тым көп шақырымы бар беттер',
'post-expand-template-inclusion-warning' => 'Ескерту: Үлгі кірістіру өлшемі тым үлкен.
Кейбір үлгілер кірістірілмейді.',
'post-expand-template-inclusion-category' => 'Үлгі кірістірілген беттер өлшемі асып кетті',
'post-expand-template-argument-warning' => 'Ескерту: Бұл бетте тым көп ұлғайтылған мөлшері болған ең кемінде бір үлгі дәлелі бар.
Бұның дәлелдерін қалдырып кеткен.',
'post-expand-template-argument-category' => 'Үлгі дәлелдерін қалдырып кеткен беттер',

# "Undo" feature
'undo-success' => 'Бұл өңдеме жоққа шығарылуы мүмкін. Талабыңызды құптап алдын ала төмендегі салыстыруды тексеріп шығыңыз да, өңдемені жоққа шығаруын бітіру үшін төмендегі өзгерістерді сақтаңыз.',
'undo-failure' => 'Бұл өңдеме жоққа шығарылмайды, себебі арада қақтығысты өңдемелер бар.',
'undo-norev' => 'Бұл өңдеме жоққа шығарылмайды, себебі бұл жоқ немесе жойылған.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|т]]) істеген нөмір $1 түзетуін [[Project:Жоққа шығару|жоққа шығарды]]',

# Account creation failure
'cantcreateaccounttitle' => 'Жаңа тіркелгі жасалмады',
'cantcreateaccount-text' => "Бұл IP мекенжайдан ('''$1''') жаңа тіркелгі жасауын [[{{ns:user}}:$3|$3]] бұғаттаған.

$3 келтірілген себебі: ''$2''",

# History pages
'viewpagelogs' => 'Бұл бет үшін журнал оқиғаларын қарау',
'nohistory' => 'Мында бұл беттінің түзету тарихы жоқ.',
'currentrev' => 'Ең соңғы түзету',
'currentrev-asof' => '$1 кезіндегі нұсқасы',
'revisionasof' => '$1 кезіндегі түзету',
'revision-info' => '$1 кезіндегі $2 істеген түзету',
'previousrevision' => '← Ескі түзетулер',
'nextrevision' => 'Жаңа түзетулер →',
'currentrevisionlink' => 'Ағымдағы түзетулер',
'cur' => 'ағым.',
'next' => 'кел.',
'last' => 'соң.',
'page_first' => 'алғашқысына',
'page_last' => 'соңғысына',
'histlegend' => "</span><br /><span style=\"white-space:nowrap;\">Сыртқы құралдар: [http://vs.aka-online.de/cgi-bin/wppagehiststat.pl?lang=kk.wikipedia&page={{FULLPAGENAMEE}} Өңдеу статистикасы] '''·'''</span> <span style=\"white-space:nowrap;\">[http://wikipedia.ramselehof.de/wikiblame.php?lang=kk&article={{FULLPAGENAMEE}} Өзгеріс тарихын іздеу] '''·'''</span> <span style=\"white-space:nowrap;\">[//toolserver.org/~daniel/WikiSense/Contributors.php?wikilang=kk&wikifam=.wikipedia.org&grouped=on&page={{FULLPAGENAMEE}} Үлестер статистикасы] '''·'''</span> <span style=\"white-space:nowrap;\">[http://toolserver.org/~snottywong/usersearch.html?page={{FULLPAGENAMEE}} Қатысушы өңдемелері] '''·'''</span> <span style=\"white-space:nowrap;\">[//toolserver.org/~mzmcbride/cgi-bin/watcher.py?db=kkwiki_p&titles={{FULLPAGENAMEE}} Көрушілер саны] '''·'''</span> <span style=\"white-space:nowrap;\">[http://stats.grok.se/kk/latest/{{FULLPAGENAMEE}} Бетің қаралу статистикасы] '''·'''</span> Тағы қараңыз: <span style=\"white-space:nowrap;\">[{{fullurl:{{FULLPAGENAMEE}}|action=info}} бет туралы мәліметтер]</span>
----
Айырмасын бөлектеу: салыстырмақ нұсқаларының қосу көздерін белгілеп <Enter> пернесін басыңыз, немесе төмендегі батырманы нұқыңыз.<br />
Шартты белгілер: <span style=\"white-space:nowrap;\">(ағым.) = ағымдық нұсқамен айырмасы, <span style=\"white-space:nowrap;\">(соң.) = алдыңғы нұсқамен айырмасы, </span> <span style=\"white-space:nowrap;\">&nbsp; '''ш''' = [[Уикипедия:Шағын өңдеме|шағын өңдеме]], → = [[Help:Бөлім#Бөлімін өңдеу|бөлімін өңдеу]]</span></div>",
'history-fieldset-title' => 'Тарихынан іздеу',
'history-show-deleted' => 'Жойылғанын ғана көрсету',
'histfirst' => 'Ең ескісіне',
'histlast' => 'Ең жаңасына',
'historysize' => '({{PLURAL:$1|1 байт|$1 байт}})',
'historyempty' => '(бос)',

# Revision feed
'history-feed-title' => 'Түзету тарихы',
'history-feed-description' => 'Мына уикидегі бұл беттің түзету тарихы',
'history-feed-item-nocomment' => '$2 кезіндегі $1 деген',
'history-feed-empty' => 'Сұратылған бет жоқ болды.
Ол мына уикиден жойылған, немесе атауы ауыстырылған.
Осыған қатысты жаңа беттерді [[Special:Search|бұл уикиден іздеуді]] байқап көріңіз.',

# Revision deletion
'rev-deleted-comment' => '(өңдеу түйіндемесі аласталды)',
'rev-deleted-user' => '(қатысушы аты аласталды)',
'rev-deleted-event' => '(әрекет журналы аласталды)',
'rev-deleted-user-contribs' => '[Қатысушы аты немесе IP-мекенжайы жойылған — өңдемелері қатысушы үлесі бетінен жасырылған]',
'rev-deleted-text-permission' => 'Бұл беттің түзетуі барша мұрағаттарынан аласталған.
Мында [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} жою журналында] егжей-тегжей мәліметтері болуы мүмкін.',
'rev-deleted-text-view' => 'Осы беттің түзетуі барша мұрағаттарынан аласталған.
{{SITENAME}} әкімшісі боп соны көре аласыз;
[{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} жою журналында] егжей-тегжей мәлметтері болуы мүмкін.',
'rev-delundel' => 'көрсет/жасыр',
'rev-showdeleted' => 'көрсету',
'revisiondelete' => 'Түзетулерді жою/жоюды болдырмау',
'revdelete-nooldid-title' => 'Нысана түзету жарамсыз',
'revdelete-nooldid-text' => 'Бұл жетені орындау үшін нысана түзетуін/түзетулерін келтірілмепсіз,
келтірілген түзету жоқ, не ағымдық түзетуді жасыру үшін әрекеттеніп көрдіңіз.',
'revdelete-nologtype-title' => 'Журнал түрі көрсетілмеген',
'revdelete-nologid-title' => 'Журналдағы қате жазба',
'revdelete-show-file-submit' => 'Иә',
'revdelete-selected' => "'''[[:$1]] дегеннің бөлектенген {{PLURAL:$2|түзетуі|түзетулері}}:'''",
'logdelete-selected' => "'''Бөлектенген {{PLURAL:$1|журнал оқиғасы|журнал оқиғалары}}:'''",
'revdelete-text' => "'''Жойылған түзетулер мен оқиғаларды әлі де бет тарихында және журналдарда табуға болады, бірақ олардың мағлұмат бөлшектері баршаға қатыналмайды.'''

{{SITENAME}} жобасының басқа әкімшілері жасырын мағлұматқа қатынай алады, және қосымша тиымдар қойылғанша дейін, осы тілдесу арқылы жоюды болдырмауы мүмкін.",
'revdelete-legend' => 'Көрініс тиымдарын қою:',
'revdelete-hide-text' => 'Түзету мәтінін жасыр',
'revdelete-hide-image' => 'Файл мағлұматын жасыр',
'revdelete-hide-name' => 'Әрекет пен нысанасын жасыр',
'revdelete-hide-comment' => 'Өңдеме түйіндемесін жасыр',
'revdelete-hide-user' => 'Өңдеуші атын (IP мекенжайын) жасыр',
'revdelete-hide-restricted' => 'Осы тиымдарды әкімшілерге қолдану және бұл тілдесуді құлыптау',
'revdelete-radio-same' => '(өзгертпе)',
'revdelete-radio-set' => 'Иә',
'revdelete-radio-unset' => 'Жоқ',
'revdelete-suppress' => 'Деректерді баршаға ұқсас әкімшілерден де шеттету',
'revdelete-unsuppress' => 'Қалпына келтірілген түзетулерден тиымдарды аластау',
'revdelete-log' => 'Себебі:',
'revdelete-submit' => 'Бөлектенген {{PLURAL:$1|түзетуге|түзетулерге}} қолдану',
'revdelete-success' => "'''Түзету көрінісі сәтті жаңартылды.'''",
'revdelete-failure' => "'''Түзету көрінісі жаңартылмады:'''
$1",
'logdelete-success' => "'''Журнал көрінісі сәтті қойылды.'''",
'revdel-restore' => 'Көрінісін өзгерту',
'revdel-restore-deleted' => 'жойылған нұсқалары',
'revdel-restore-visible' => 'көрінетін нұсқалары',
'pagehist' => 'Бет тарихы',
'deletedhist' => 'Жойылған тарихы',
'revdelete-otherreason' => 'Басқа/қосымша себеп:',
'revdelete-reasonotherlist' => 'Өзге себеп',
'revdelete-edit-reasonlist' => 'Жою себептерін өңдеу',
'revdelete-offender' => 'Нұсқа авторы:',

# Suppression log
'suppressionlog' => 'Шеттету журналы',
'suppressionlogtext' => 'Төмендегі тізімде әкімшілерден жасырылған мағлұматқа қатысты жоюлар мен бұғаттаулар беріледі.
Ағымда әрекеттегі тиым мен бұғаттау тізімі үшін [[Special:IPBlockList|IP бұғаттау тізімін]] қараңыз.',

# History merging
'mergehistory' => 'Беттер тарихын біріктіру',
'mergehistory-header' => 'Бұл бет түзетулер тарихын қайнар беттің біреуінен алып жаңа бетке біріктіргізеді.
Осы өзгеріс беттің тарихи жалғастырушылығын қоштайтынына көзіңіз жетсін.',
'mergehistory-box' => 'Екі беттің түзетулерін біріктіру:',
'mergehistory-from' => 'Қайнар беті:',
'mergehistory-into' => 'Нысана беті:',
'mergehistory-list' => 'Біріктірлетін түзету тарихы',
'mergehistory-merge' => '[[:$1]] дегеннің келесі түзетулері [[:$2]] дегенге біріктірілуі мүмкін.
Біріктіруге тек енгізілген уақытқа дейін жасалған түзетулерді айырып-қосқыш бағанды қолданыңыз.
Аңғартпа: бағыттау сілтемелерін қолданғанда бұл баған қайта қойылады.',
'mergehistory-go' => 'Біріктірлетін түзетулерді көрсет',
'mergehistory-submit' => 'Түзетулерді біріктіру',
'mergehistory-empty' => 'Түзетулер біріктірілмейді.',
'mergehistory-success' => '[[:$1]] дегеннің $3 түзетуі [[:$2]] дегенге сәтті біріктірілді.',
'mergehistory-fail' => 'Тарих біріктіруін орындау икемді емес, бет пен уақыт бапталымдарын қайта тексеріп шығыңыз.',
'mergehistory-no-source' => '$1 деген қайнар беті жоқ.',
'mergehistory-no-destination' => '$1 деген нысана беті жоқ.',
'mergehistory-invalid-source' => 'Қайнар бетінде жарамды тақырып аты болуы жөн.',
'mergehistory-invalid-destination' => 'Нысана бетінде жарамды тақырып аты болуы жөн.',
'mergehistory-autocomment' => '[[:$1]] деген [[:$2]] дегенге біріктірілді',
'mergehistory-comment' => '[[:$1]] деген [[:$2]] дегенге біріктірілді: $3',
'mergehistory-same-destination' => 'Бастапқы және мақсатты беттер бірдей болмауы керек',
'mergehistory-reason' => 'Себебі:',

# Merge log
'mergelog' => 'Біріктіру журналы',
'pagemerge-logentry' => '[[$1]] деген [[$2]] дегенге біріктірілді ($3 дейінгі түзетулері)',
'revertmerge' => 'Біріктіруді болдырмау',
'mergelogpagetext' => 'Төменде бір беттің тарихы өзге бетке біріктіру ең соңғы тізімі келтіріледі.',

# Diffs
'history-title' => '«$1» дегеннің өңдеу тарихы',
'difference-title' => 'Нұсқалар арасындағы айырмашылық: "$1"',
'difference-title-multipage' => '"$1" және "$2" беттерінің арасындағы айырмашылық',
'difference-multipage' => '(Беттер арасындағы айырмашылық)',
'lineno' => 'Жол нөмірі $1:',
'compareselectedversions' => 'Таңдалған нұсқаларды салыстыру',
'showhideselectedversions' => 'Бөлектенген нұсқаларды көрсет/жасыр',
'editundo' => 'жоққа шығару',
'diff-empty' => '(айырмашылығы жоқ)',
'diff-multi' => '($2 қатысушының арадағы $1 түзетуі көрсетілмеген)',

# Search results
'searchresults' => 'Іздеу нәтижелері',
'searchresults-title' => '"$1" сұранымына табылған нәтижелер',
'searchresulttext' => '{{SITENAME}} жобасында іздеу туралы көбірек ақпарат үшін, [[{{MediaWiki:Helppage}}|{{int:help}} бетін]] қараңыз.',
'searchsubtitle' => '\'\'\'[[:$1]]\'\'\' үшін іздегеніңіз  ([[Special:Prefixindex/$1| "$1" бетінен басталатын барлық беттер]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" бетіне сілтейтін барлық беттер]])',
'searchsubtitleinvalid' => "Іздегеніңіз: '''$1'''",
'toomanymatches' => 'Тым көп сәйкес қайтарылды, өзге сұранымды байқап көріңіз',
'titlematches' => 'Бет тақырыбын аты сәйкес келеді',
'notitlematches' => 'Еш бет тақырыбын аты сәйкес емес',
'textmatches' => 'Бет мәтіні сәйкес келеді',
'notextmatches' => 'Еш бет мәтіні сәйкес емес',
'prevn' => 'алдыңғы {{PLURAL:$1|$1}}',
'nextn' => 'келесі {{PLURAL:$1|$1}}',
'prevn-title' => 'Алдыңғы $1 {{PLURAL:$1|жазба|жазбалар}}',
'nextn-title' => 'Келесі $1 {{PLURAL:$1|жазба|жазбалар}}',
'shown-title' => 'Осы бетте {{PLURAL:$1|жазба}} көрсету.',
'viewprevnext' => 'Көрсетілуі: ($1 {{int:pipe-separator}} $2) ($3) жазба',
'searchmenu-legend' => 'Іздеу бапталымдары',
'searchmenu-exists' => "'''Бұл жобада «[[:$1]]» деген бет бар.'''",
'searchmenu-new' => "'''\"[[:\$1]]\" осындай атпен бетті бастау'''",
'searchprofile-articles' => 'Негізгі беттер',
'searchprofile-project' => 'Анықтама және жоба беттері',
'searchprofile-images' => 'Мультимедиа',
'searchprofile-everything' => 'Барлық жерде',
'searchprofile-advanced' => 'Кеңейтілген',
'searchprofile-articles-tooltip' => '$1 іздеу',
'searchprofile-project-tooltip' => '$1 іздеу',
'searchprofile-images-tooltip' => 'Файлдарды іздеу',
'searchprofile-everything-tooltip' => 'Барлық беттерден іздеу (талқылау беттерін қоса)',
'searchprofile-advanced-tooltip' => 'Белгіленген есім кеңістігінен іздеу',
'search-result-size' => '$1 ({{PLURAL:$2|1 сөз|$2 сөз}})',
'search-result-category-size' => '{{PLURAL:$1|1 мүше|$1 мүше}} ({{PLURAL:$2|1 санатша|$2 санатша}}, {{PLURAL:$3|1 файл|$3 файл}})',
'search-result-score' => 'Арақатынастылығы: $1 %',
'search-redirect' => '(айдағыш $1)',
'search-section' => '(бөлім $1)',
'search-suggest' => 'Мүмкін осы болар: $1',
'search-interwiki-caption' => 'Бауырлас жобалар',
'search-interwiki-default' => '$1 нәтиже:',
'search-interwiki-more' => '(көбірек)',
'search-relatedarticle' => 'Қатысты',
'mwsuggest-disable' => 'Іздеу ұсынымдарын өшір',
'searcheverything-enable' => 'Белгіленген есім кеңістігінен іздеу',
'searchrelated' => 'қатысты',
'searchall' => 'барлық',
'showingresults' => "Төменде нөмір '''$2''' орнынан бастап барынша '''$1''' нәтиже көрсетіледі.",
'showingresultsnum' => "Төменде нөмір '''$2''' орнынан бастап '''$3''' нәтиже көрсетіледі.",
'showingresultsheader' => "«'''$4'''» үшін {{PLURAL:$5|тек '''$1''' нәтиже табылды|табылған '''$3''' нәтиженің '''$1—$2''' аралығы көрсетілген}}",
'nonefound' => "'''Аңғартпа''': Әдепкіден тек кейбір есім аялардан ізделінеді. Барлық мағлұмат түрін (соның ішінде талқылау беттерді, үлгілерді т.б.) іздеу үшін сұранымыңызды ''барлық:'' деп бастаңыз, немесе қалаған есім аясын бастауыш есебінде қолданыңыз.",
'search-nonefound' => 'Сұрауға сәйкес нәтижелер табылмады.',
'powersearch' => 'Кеңейтілген іздеу',
'powersearch-legend' => 'Кеңейтілген іздеу',
'powersearch-ns' => 'Мына есім аяларда іздеу:',
'powersearch-redir' => 'Айдатуларды тізімдеу',
'powersearch-field' => 'Іздеу',
'powersearch-togglelabel' => 'Белгілеу:',
'powersearch-toggleall' => 'Барлығы',
'powersearch-togglenone' => 'Ешқандай',
'search-external' => 'Сыртқы іздеу',
'searchdisabled' => '{{SITENAME}} іздеу қызметі өшірілген.
Әзірше Google арқылы іздеуге болады.
Аңғартпа: {{SITENAME}} торабының мағлұмат тізбелері ескірген болуы мүмкін.',

# Preferences page
'preferences' => 'Баптаулар',
'mypreferences' => 'Баптаулар',
'prefs-edits' => 'Өңдеме саны:',
'prefsnologin' => 'Кірмегенсіз',
'prefsnologintext' => 'Қатысушы бапталымдарыңызды жөндеу үшін <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} кіруіңіз]</span> жөн.',
'changepassword' => 'Құпия сөзді өзгерту',
'prefs-skin' => 'Мәнерлер',
'skin-preview' => 'Қарап шығу',
'datedefault' => 'Еш қалаусыз',
'prefs-beta' => 'Beta мүмкіндік',
'prefs-datetime' => 'Уақыт',
'prefs-labs' => 'Тәжірибелік мүмкіндіктер',
'prefs-user-pages' => 'Қатысушы беттері',
'prefs-personal' => 'Жеке деректері',
'prefs-rc' => 'Жуықтағы өзгерістер',
'prefs-watchlist' => 'Бақылау тізімі',
'prefs-watchlist-days' => 'Бақылау тізіміндегі күндердің көрсетпек саны:',
'prefs-watchlist-days-max' => 'Ең көбі $1 {{PLURAL:$1|күн|күн}}',
'prefs-watchlist-edits' => 'Кеңейтілген бақылаулардағы өзгерістердің барынша көрсетпек саны:',
'prefs-watchlist-edits-max' => 'Ең көп саны: 1000',
'prefs-watchlist-token' => 'Бақылау тізімінің белгісі:',
'prefs-misc' => 'Әрқилы',
'prefs-resetpass' => 'Құпия сөзді өзгерту',
'prefs-changeemail' => 'E-mail мекен-жайын өзгерту',
'prefs-setemail' => 'E-mail мекен-жайын жөндеу',
'prefs-email' => 'Е-пошта баптаулары',
'prefs-rendering' => 'Сырт көрініс',
'saveprefs' => 'Сақтау',
'resetprefs' => 'Сақталмаған өзгерістерді тазарту',
'restoreprefs' => 'Барлығын бастапқы баптауларға қайтару',
'prefs-editing' => 'Өңдеу',
'rows' => 'Жолдар:',
'columns' => 'Бағандар:',
'searchresultshead' => 'Іздеу',
'resultsperpage' => 'Бет сайын нәтиже саны:',
'stub-threshold' => '<a href="#" class="stub">Бастама сілтемесін</a> пішімдеу табалдырығы (байт):',
'stub-threshold-disabled' => 'Ажыратылған',
'recentchangesdays' => 'Жуықтағы өзгерістерде көрсетілетін күн саны:',
'recentchangesdays-max' => 'Ең көбі $1 {{PLURAL:$1|күн|күн}}',
'recentchangescount' => 'Жуықтағы өзгерістердінде, тарих және журнал беттерінде көрсетпек өңдеме саны:',
'prefs-help-recentchangescount' => 'Жуықтағы өзгерістер, бет тарихтарығ және журналдар қамтылады.',
'savedprefs' => 'Бапталымдарыңыз сақталды.',
'timezonelegend' => 'Уақыт белдеуі:',
'localtime' => 'Жергілікті уақыт:',
'timezoneuseserverdefault' => 'Уикидің баптауларын қолдану ($1)',
'timezoneuseoffset' => 'Басқа (жылжытуды көрсетіңіз)',
'timezoneoffset' => 'Сағат ығысуы¹:',
'servertime' => 'Сервер уақыты:',
'guesstimezone' => 'Шолғыштан алып толтыру',
'timezoneregion-africa' => 'Африка',
'timezoneregion-america' => 'Америка',
'timezoneregion-antarctica' => 'Антарктика',
'timezoneregion-arctic' => 'Арктика',
'timezoneregion-asia' => 'Азия',
'timezoneregion-atlantic' => 'Атлант мұхиты',
'timezoneregion-australia' => 'Аустралия',
'timezoneregion-europe' => 'Еуропа',
'timezoneregion-indian' => 'Үнді мұхиты',
'timezoneregion-pacific' => 'Тынық мұхиты',
'allowemail' => 'Басқадан хат қабылдауын қосу',
'prefs-searchoptions' => 'Іздеу бапталымдары',
'prefs-namespaces' => 'Есім кеңістіктері',
'defaultns' => 'Мына есім кеңістіктерінде басқаша іздеу:',
'default' => 'әдепкі',
'prefs-files' => 'Файлдар',
'prefs-custom-css' => 'CSS өзгертпелі',
'prefs-custom-js' => 'JavaScript өзгертпелі',
'prefs-common-css-js' => 'Барлық skin-дер үшін CSS/JavaScript бөлісілді:',
'prefs-emailconfirm-label' => 'Е-поштаның расталуы:',
'youremail' => 'Е-поштаңыз:',
'username' => '{{GENDER:$1|Қатысушы аты}}:',
'uid' => '{{GENDER:$1|Қатысушы}} теңдестіргішіңіз (ID):',
'prefs-memberingroups' => '{{GENDER:$2|Мүше}}  {{PLURAL:$1|тобыңыз|топтарыңыз}}:',
'prefs-registration' => 'Тіркелу уақыты:',
'yourrealname' => 'Нақты атыңыз:',
'yourlanguage' => 'Тіліңіз:',
'yourvariant' => 'Жазба тілінің нұсқалары:',
'yournick' => 'Жаңа қолтаңбаңыз:',
'prefs-help-signature' => 'Талқылау беттерінде хабарыңыздан кейін "<nowiki>~~~~</nowiki>" белгісін қалдырсаңыз, бұл қолтаңбаңызбен сол кездегі датаға ауыстырылады.',
'badsig' => 'Қам қолтаңбаңыз жарамсыз; HTML белгішелерін тексеріңіз.',
'badsiglength' => 'Қолтаңбаңыз тым ұзын;
Бұл $1 {{PLURAL:$1|таңбадан|таңбадан}} аспауы керек.',
'yourgender' => 'Жынысыңыз:',
'gender-unknown' => 'Көрсетілмеген',
'gender-male' => 'Ер',
'gender-female' => 'Әйел',
'prefs-help-gender' => 'Міндетті емес: бағдарламалық жасақтама жынысыңызға сәйкес хабарларды көрсетуге қолданылады.
Бұл мағлұмат баршаға мәлім болады.',
'email' => 'Е-поштаңыз',
'prefs-help-realname' => 'Нақты атыңыз міндетті емес.
Егер бұны жетістіруді таңдасаңыз, бұл түзетуіңіздің ауторлығын анықтау үшін қолданылады.',
'prefs-help-email' => 'Электронды поштаңыздың мекенжайын көрсету міндетті емес, бірақ құпия сөзіңізді ұмытқан жағдайда керек болады.',
'prefs-help-email-required' => 'Е-пошта мекенжайы керек.',
'prefs-info' => 'Негізгі мәлімет',
'prefs-i18n' => 'Тіл туралы мәлімет',
'prefs-signature' => 'Қолтаңба',
'prefs-dateformat' => 'Уақыт пішіні',
'prefs-timeoffset' => 'Уақыт ығысуы',
'prefs-advancedediting' => 'Негізгі параметрлер',
'prefs-editor' => 'Өңдеуші',
'prefs-preview' => 'Қарап шығу',
'prefs-advancedrc' => 'Кеңейтілген баптаулар',
'prefs-advancedrendering' => 'Кеңейтілген баптаулар',
'prefs-advancedsearchoptions' => 'Кеңейтілген баптаулар',
'prefs-advancedwatchlist' => 'Кеңейтілген баптаулар',
'prefs-displayrc' => 'Көрсету бапталымдары',
'prefs-displaysearchoptions' => 'Көрсету бапталымдары',
'prefs-displaywatchlist' => 'Көрсету бапталымдары',
'prefs-diffs' => 'Айырмашылықтар',

# User preference: email validation using jQuery
'email-address-validity-invalid' => 'Жарамсыз электронды пошта мекен-жайын енгізіңіз',

# User rights
'userrights' => 'Қатысушы құқықтарын реттеу',
'userrights-lookup-user' => 'Қатысушы топтарын реттеу',
'userrights-user-editname' => 'Қатысушы атын енгізіңіз:',
'editusergroup' => 'Қатысушы топтарын өңдеу',
'editinguser' => "'''[[User:$1|$1]]''' $2 есімді қатысушының құқықтарын өзгерту",
'userrights-editusergroup' => 'Қатысушы топтарын өңдеу',
'saveusergroups' => 'Қатысушы топтарын сақтау',
'userrights-groupsmember' => 'Мүшелігі:',
'userrights-groups-help' => 'Бұл қатысушы кіретін топтарды реттей аласыз.
* Құсбелгі қойылған көзі қатысушы бұл топқа кіргенін көрсетеді;
* Құсбелгі алып тасталған көз қатысушы бұл топқа кірмегенін көрсетеді;
* Келтірілген * топты бір үстегенінен кейін аластай алмайтындығын, не қарама-қарсысын көрсетеді.',
'userrights-reason' => 'Себебі:',
'userrights-no-interwiki' => 'Басқа уикилердегі қатысушы құқықтарын өңдеуге рұқсатыңыз жоқ.',
'userrights-nodatabase' => '$1 дерекқоры жоқ не жергілікті емес.',
'userrights-nologin' => 'Қатысушы құқықтарын тағайындау үшін әкімші тіркелгісімен [[Special:UserLogin|кіруіңіз]] жөн.',
'userrights-notallowed' => 'Сізге қатысушы құқықтарын қосуға немесе алып тастауға рұқсат берілмеген.',
'userrights-changeable-col' => 'Өзгерте алатын топтар',
'userrights-unchangeable-col' => 'Өзгерте алмайтын топтар',
'userrights-conflict' => 'Қатысушы құқықтарының қақтығысы! Өзгертулеріңізді қайта қарап шығыңыз және құптаңыз.',
'userrights-removed-self' => 'Өзіңіздің құқықтарыңызды сәтті алып тастадыңыз.  As such, you are no longer able to access this page.',

# Groups
'group' => 'Топ:',
'group-user' => 'Қатысушылар',
'group-autoconfirmed' => 'Өзқұпталған қатысушылар',
'group-bot' => 'Боттар',
'group-sysop' => 'Әкімшілер',
'group-bureaucrat' => 'Бітікшілер',
'group-suppress' => 'Шеттетушілер',
'group-all' => '(барлық)',

'group-user-member' => '{{GENDER:$1|қатысушы}}',
'group-autoconfirmed-member' => '{{GENDER:$1|өзқұпталған қатысушы}}',
'group-bot-member' => '{{GENDER:$1|бот}}',
'group-sysop-member' => '{{GENDER:$1|әкімші}}',
'group-bureaucrat-member' => '{{GENDER:$1|бітікші}}',
'group-suppress-member' => '{{GENDER:$1|шеттетуші}}',

'grouppage-user' => '{{ns:project}}:Қатысушылар',
'grouppage-autoconfirmed' => '{{ns:project}}:Өзқұпталған қатысушылар',
'grouppage-bot' => '{{ns:project}}:Боттар',
'grouppage-sysop' => '{{ns:project}}:Әкімшілер',
'grouppage-bureaucrat' => '{{ns:project}}:Бітікшілер',
'grouppage-suppress' => '{{ns:project}}:Шеттетушілер',

# Rights
'right-read' => 'Беттерді оқу',
'right-edit' => 'Беттерді өңдеу',
'right-createpage' => 'Беттерді бастау (талқылау емес беттерді бастау)',
'right-createtalk' => 'Талқылау беттерін бастау',
'right-createaccount' => 'Жаңа қатысушы тіркелгісін жасау',
'right-minoredit' => 'Өңдемелерді шағын деп белгілеу',
'right-move' => 'Беттерді жылжыту',
'right-move-subpages' => 'Беттерді олардың бағынышты беттерін қоса жылжыту',
'right-move-rootuserpages' => 'Қатысушы беттерін түбірімен жылжыту',
'right-movefile' => 'Файлдарды жылжыту',
'right-suppressredirect' => 'Тиісті атауға бетті жылжытқанда айдағышты жасамау',
'right-upload' => 'Файлдарды жүктеу',
'right-reupload' => 'Бар файл үстіне жазу',
'right-reupload-own' => 'Өзі жүктеген файл үстіне жазу',
'right-reupload-shared' => 'Таспа ортақ қоймасындағы файлдарды жергіліктілермен асыру',
'right-upload_by_url' => 'Файлды URL мекенжайдан жүктеу',
'right-purge' => 'Бетті торап бүркемесінен құптаусыз тазарту',
'right-autoconfirmed' => 'Жартылай қорғалған беттерді өңдеу',
'right-bot' => 'Өздіктік үдеріс деп есептелу',
'right-nominornewtalk' => 'Талқылау беттердегі шағын өңдемелерді жаңа хабар деп есептемеу',
'right-apihighlimits' => 'API сұранымдарының жоғары шектелімдерін пайдалану',
'right-writeapi' => 'API жазуын пайдалану',
'right-delete' => 'Беттерді жою',
'right-bigdelete' => 'Ұзақ тарихы бар беттерді жою',
'right-deletelogentry' => 'Ерекше журнал енгізілімдерін жою және жоймау',
'right-deleterevision' => 'Беттердің өзіндік түзетулерін жою не жоюын болдырмау',
'right-deletedhistory' => 'Жойылған тарих даналарын (байланысты мәтінсіз) көру',
'right-deletedtext' => 'Жойылған мәтінді және жойылған нұсқалар арасындағы өзгерістерді қарау',
'right-browsearchive' => 'Жойылған беттерді іздеу',
'right-undelete' => 'Беттің жоюын болдырмау',
'right-suppressrevision' => 'Әкімшілерден жасырылған түзетулерді шолып шығу және қалпына келтіру',
'right-suppressionlog' => 'Жекелік журналдарды көру',
'right-block' => 'Басқа қатысушыларды өңдеуден бұғаттау',
'right-blockemail' => 'Қатысушының хат жөнелтуін бұғаттау',
'right-hideuser' => 'Баршадан жасырып, қатысушы атын бұғаттау',
'right-ipblock-exempt' => 'IP бұғаттауларды, өзбұғаттауларды және ауқым бұғаттауларды орағыту',
'right-proxyunbannable' => 'Прокси серверлердің өзбұғаттауларын орағыту',
'right-unblockself' => 'Бұғаттаудан шығару',
'right-protect' => 'Қорғау деңгейлерін өзгерту және баулы-қорғаулы беттерді өңдеу',
'right-editprotected' => 'Қорғалған беттерді өңдеу "{{int:protect-level-sysop}}"',
'right-editsemiprotected' => 'Қорғалған беттерді өңдеу "{{int:protect-level-autoconfirmed}}"',
'right-editinterface' => 'Қатысушы тілдесіуін өңдеу',
'right-editusercssjs' => 'Басқа қатысушылардың CSS және JS файлдарын өңдеу',
'right-editusercss' => 'Басқа қатысушылардың CSS файлдарын өңдеу',
'right-edituserjs' => 'Басқа қатысушылардың JavaScript файлдарын өңдеу',
'right-editmyusercss' => 'Өзіңіздің қатысушы CSS файлдарыңызды өңдеу',
'right-editmyuserjs' => 'Өзіңіздің қатысушы JavaScript файлдарыңызды өңдеу',
'right-viewmywatchlist' => 'Бақылау тізіміңізді қарау',
'right-editmywatchlist' => 'Өзіңіздің баптауларыңызды өңдеу. Note some actions will still add pages even without this right.',
'right-viewmyprivateinfo' => 'Өзіңіздің жеке деректеріңізді қарау (e.g. email мекен-жай, нақты есім)',
'right-editmyprivateinfo' => 'Өзіңіздің жеке деректеріңізді өңдеу (e.g. email мекен-жай, нақты есім)',
'right-editmyoptions' => 'Баптауларыңызды өңдеу',
'right-rollback' => 'Белгілі бетті өңдеген соңғы қатысушының өңдемелерінен жылдам шегіндіру',
'right-markbotedits' => 'Шегіндірлген өңдемелерді боттар өңдемелері деп белгілеу',
'right-noratelimit' => 'Еселік шектелімдері ықпал етпейді',
'right-import' => 'Басқа уикилерден беттерді сырттан алу',
'right-importupload' => 'Файлдарды жүктеу арқылы беттерді сырттан алу',
'right-patrol' => 'Басқарардың өңдемелерін зерттелді деп белгілеу',
'right-autopatrol' => 'Өз өңдемелерін зерттелді деп өздіктік белгілеу',
'right-patrolmarks' => 'Жуықтағы өзгерістердегі зерттеу белгілерін көру',
'right-unwatchedpages' => 'Бақыланылмаған бет тізімін көру',
'right-mergehistory' => 'Беттердің тарихын қосып беру',
'right-userrights' => 'Қатысушылардың барлық құқықтарын өңдеу',
'right-userrights-interwiki' => 'Басқа үикилердегі қатысушылардың құқықтарын өңдеу',
'right-siteadmin' => 'Дерекқорды құлыптау және құлыптауын өшіру',
'right-sendemail' => 'Басқа қатысушыларға е-пошта жіберу',
'right-passwordreset' => 'Өзгерген құпия сөз арқылы хабарламаларды шолу',

# Special:Log/newusers
'newuserlogpage' => 'Тіркелу журналы',
'newuserlogpagetext' => 'Бұл қатысушы тіркелгі жасау журналы',

# User rights log
'rightslog' => 'Қатысушы құқықтары журналы',
'rightslogtext' => 'Бұл қатысушы құқықтарын өзгерту журналы.',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'Осы бетті оқу',
'action-edit' => 'осы бетті өңдеу',
'action-createpage' => 'беттерді бастау',
'action-createtalk' => 'талқылау беттерін бастау',
'action-createaccount' => 'Бұл қатысушы тіркелгісін жасау',
'action-minoredit' => 'бұл өңдемені шағын деп белгілеу',
'action-move' => 'бұл бетті жылжыту',
'action-move-subpages' => 'бұл бетті және оның төменгі беттерін жылжыту',
'action-move-rootuserpages' => 'қатысушы беттерін түбірімен жылжыту',
'action-movefile' => 'Бұл файлды жылжыту',
'action-upload' => 'бұл файлды жүктеу',
'action-reupload' => 'бұл бар файлдың үстіне жазу',
'action-upload_by_url' => 'бұл файлды URL-дан жүктеу',
'action-writeapi' => 'API жазуын пайдалану',
'action-delete' => 'осы бетті жою',
'action-deleterevision' => 'бұл нұсқасын жою',
'action-deletedhistory' => 'бұл беттің жойылған тарихын қарау',
'action-browsearchive' => 'жойылған беттерді іздеу',
'action-undelete' => 'бұл бетті жоймау',
'action-suppressrevision' => 'бұл жасырылған нұсқаны қарау және қалпына келтіру',
'action-suppressionlog' => 'бұл ерекше журналды қарау',
'action-block' => 'бұл қатысушыны өңдеуден бұғаттау',
'action-protect' => 'бұл бет үшін қорғалу деңгейін өзгерту',
'action-rollback' => 'жекелік беттегі соңғы өңдеген қатысушының соңғы өңдемелерін жылдам шегіндіру',
'action-import' => 'бұл бетті басқа уикиден импортау',
'action-importupload' => 'бұл бетті файл жүктеуінен импорттау',
'action-patrol' => 'басқалардың өңдеулерін зерттелді деп белгілеу',
'action-autopatrol' => 'өзіңіздің өңдемеңізді зерттелді деп белгілеу',
'action-unwatchedpages' => 'бақыланылмаған беттер тізімін қарау',
'action-mergehistory' => 'Бұл беттің өзгеріс тарихын қосу',
'action-userrights' => 'Қатысушылардың барлық құқықтарын өзгерту',
'action-userrights-interwiki' => 'Басқа уикилердегі қатысушылардың құқықтарын өзгерту',
'action-siteadmin' => 'Дерекқорды бұғаттау немесе бұғаттан шығару',
'action-sendemail' => 'электронды хаттарды жіберу',
'action-editmywatchlist' => 'бақылауыңызды өңдеу',
'action-viewmywatchlist' => 'бақылау тізіміңізді қарау',
'action-viewmyprivateinfo' => 'жеке ақпараттарыңызды қарау',
'action-editmyprivateinfo' => 'жеке ақпараттарыңызды өңдеу',

# Recent changes
'nchanges' => '$1 өзгеріс',
'recentchanges' => 'Жуықтағы өзгерістер',
'recentchanges-legend' => 'Жуықтағы өзгерістер баптаулары',
'recentchanges-summary' => 'Бұл бетте осы уикидегі болған жуықтағы өзгерістер байқалады.',
'recentchanges-noresult' => 'Бұл талап бойынша көрсетілген уақыттан бері өзгерістер болған жоқ.',
'recentchanges-feed-description' => 'Бұл арнаменен уикидегі ең соңғы өзгерістер қадағаланады.',
'recentchanges-label-newpage' => 'Бұл өңдеме арқылы жаңа бет басталды',
'recentchanges-label-minor' => 'Бұл шағын өңдеме',
'recentchanges-label-bot' => 'Бұл өңдемені бот жасады.',
'recentchanges-label-unpatrolled' => 'Бұл өңдеме әлі тексеруден өтпеді.',
'rcnote' => "Төменде $5, $4 кезіне дейінгі соңғы {{PLURAL:$2|күндегі|'''$2''' күндегі}}, {{PLURAL:$1| '''1''' өзгеріс|соңғы '''$1''' өзгеріс}}  көрсетіледі.",
'rcnotefrom' => "Төменде '''$2''' кезінен бергі ('''$1''' жеткенше дейін) өзгерістер көрсетіледі.",
'rclistfrom' => '$1 кезінен бергі жаңа өзгерістерді көрсет.',
'rcshowhideminor' => 'Шағын өңдемелерді $1',
'rcshowhidebots' => 'Боттарды $1',
'rcshowhideliu' => 'Тіркелгендерді $1',
'rcshowhideanons' => 'Анонимді қатысушыларды $1',
'rcshowhidepatr' => 'Зерттелген өңдемелерді $1',
'rcshowhidemine' => 'Өңдемелерімді $1',
'rclinks' => 'Соңғы $2 күнде болған, соңғы $1 өзгерісті көрсет<br />$3',
'diff' => 'айырм.',
'hist' => 'тарихы',
'hide' => 'жасыру',
'show' => 'көрсету',
'minoreditletter' => 'ш',
'newpageletter' => 'Ж',
'boteditletter' => 'б',
'number_of_watching_users_pageview' => '[бақылаған $1 қатысушы]',
'rc_categories' => 'Санаттарға шектеу ("|" белгісімен бөліктеңіз)',
'rc_categories_any' => 'Кез келген',
'rc-change-size-new' => 'Өңдеуден кейінгі көлемі: $1{{PLURAL:$1|байт|байт}}',
'newsectionsummary' => '/* $1 */ жаңа бөлім',
'rc-enhanced-expand' => 'Толық ақпаратты көрсету',
'rc-enhanced-hide' => 'Толық ақпаратты жасыру',
'rc-old-title' => 'Бастапқы «$1» сияқты жасалған',

# Recent changes linked
'recentchangeslinked' => 'Қатысты өзгерістер',
'recentchangeslinked-feed' => 'Қатысты өзгерістер',
'recentchangeslinked-toolbox' => 'Қатысты өзгерістер',
'recentchangeslinked-title' => '«$1» дегенге қатысты өзгерістер',
'recentchangeslinked-summary' => "Бұл тізімде өзіндік бетке сілтеген беттердегі (не өзіндік санат мүшелеріндегі) істелген жуықтағы өзгерістер беріледі.
[[Special:Watchlist|Бақылау тізіміңіздегі]] беттер '''жуан''' болып белгіленеді.",
'recentchangeslinked-page' => 'Бет атауы:',
'recentchangeslinked-to' => 'Керісінше, келтірілген бетке сілтейтін беттердегі өзгерістерді көрсет',

# Upload
'upload' => 'Файл жүктеу',
'uploadbtn' => 'Файлды жүктеу',
'reuploaddesc' => 'Жүктеу пішініне қайта келу.',
'upload-tryagain' => 'Файл сипаттамасының өзгерістерін жөнелту',
'uploadnologin' => 'Кірмегенсіз',
'uploadnologintext' => 'Файлдарды $1 жүктеуіңіз керек.',
'upload_directory_missing' => 'Жүктеу қалтасы ($1) жетіспейді және веб-сервер жарата алмайды.',
'upload_directory_read_only' => 'Жүктеу қалтасына ($1) веб-сервер жаза алмайды.',
'uploaderror' => 'Жүктеу қатесі',
'upload-recreate-warning' => "'''Ескету: Бұл атаумен файл жойылған немесе жылжытылған'''

The deletion and move log for this page are provided here for convenience:",
'uploadtext' => "Төмендегі пішінді файлдарды жүктеу үшін қолданыңыз.
Алдында жүктелген файлдарды қарау не іздеу үшін [[Special:FileList|жүктелген файлдар тізіміне]] барыңыз. Сондай-ақ файлдардың жүктелуі [[Special:Log/upload|жүктелі журналына]], ал жойылған файлдар [[Special:Log/delete|жойылу журналына]] жазылады.

Суретті мақалаға қосу үшін келесі тәсілдерді қолданыңыз:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' файлдың толық нұсқасын орнату үшін;
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|сурет тақырыбы]]</nowiki></code>''' 200px кішірейтілген файлды тақырыбын қосып сол жаққа орналастыру;
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' тек файлға сілтеме жасау үшін.",
'upload-permitted' => 'Рұқсат етілген файл түрлері: $1.',
'upload-preferred' => 'Ұнамды файл түрлері $1.',
'upload-prohibited' => 'Рұқсат етілмеген файл түрлері: $1.',
'uploadlog' => 'жүктеу журналы',
'uploadlogpage' => 'Жүктеу журналы',
'uploadlogpagetext' => 'Төменде ең соңғы жүктелген файлдар тізімі.
Тағы көрнекі қарап шығу үшін [[Special:NewFiles|жаңа файлдар көрмесі]] дегенді қараңыз.',
'filename' => 'Файл атауы',
'filedesc' => 'Түйіндемесі',
'fileuploadsummary' => 'Файл сипаттамасы:',
'filereuploadsummary' => 'Файлдағы өзгерістер',
'filestatus' => 'Ауторлық құқықтар күйі:',
'filesource' => 'Қайнар көзі:',
'uploadedfiles' => 'Жүктелген файлдар',
'ignorewarning' => 'Ескетуді елеме де файлды қалайда сақта.',
'ignorewarnings' => 'Ескертулерді елемеу',
'minlength1' => 'Файл атауында ең кемінде бір әріп болуы жөн.',
'illegalfilename' => '«$1» файл атауында бет тақырыбы атында рұқсат берілмеген таңбалар бар.
Файлды қайта атаңыз да бұны қотарып беруді қайта байқап көріңіз.',
'filename-toolong' => 'Файл атауы 240 байттан жоғары болмауы керек',
'badfilename' => 'Файлдың атауы «$1» деп өзгертілді.',
'filetype-badmime' => '«$1» деген MIME түрі бар файлдарды қотарып беруге рұқсат етілмейді.',
'filetype-unwanted-type' => "'''«.$1»''' — күтілмеген файл түрі. Ұнамды файл түрлері: $2.",
'filetype-banned-type' => "'''«.$1»''' — {{PLURAL:$4|рұқсатталмаған файл түрі|рұқсатталмаған файл түрлері}}. Рұқсатталған {{PLURAL:$3|файл түрі|файл түрлері}}: $2.",
'filetype-missing' => 'Бұл файлдың («.jpg» сияқты) кеңейтімі жоқ.',
'empty-file' => 'Сіз жіберген файл бос.',
'filename-tooshort' => 'Файл атауы қысқа.',
'illegal-filename' => 'Файл атауы рұқсат етілген.',
'unknown-error' => 'Белгісіз қателік орын алды.',
'large-file' => 'Файлдың $1 мөлшерінен аспауына кепілдеме беріледі;
бұл файл мөлшері — $2.',
'largefileserver' => 'Осы файлдың мөлшері сервердің қалауынан асып кеткен.',
'emptyfile' => 'Қотарып берілген файлыңыз бос сияқты. Файл атауы қате жазылған мүмкін.
Бұл файлды қотарып беруі нақты талабыңыз екенін тексеріп шығыңыз.',
'windows-nonascii-filename' => 'Бұл уики файл атауларында арнайы таңбаларды қолдамайды.',
'fileexists' => 'Осылай аталған файл әлдеқашан бар, егер бұны өзгертуге сеніміңіз жоқ болса <strong>[[:$1]]</strong> дегенді тексеріп шығыңыз.
[[$1|thumb]]',
'filepageexists' => 'Бұл файлдың сипаттама беті алдақашан <strong>[[:$1]]</strong> дегенде жасалған, бірақ ағымда былай аталған еш файл жоқ.
Енгізген қысқаша мазмұндамаңыз сипаттамасы бетінде көрсетілмейді.
Қысқаша мазмұндамаңыз осы арада көрсетілу үшін, бұны қолмен өңдемек болыңыз.
[[$1|нобай]]',
'fileexists-extension' => 'Ұқсас атауы бар файл табылды: [[$2|thumb]]
* Қотарып берілетін файл атауы: <strong>[[:$1]]</strong>
* Бар болған файл атауы: <strong>[[:$2]]</strong>
Өзге атауды таңдаңыз.',
'fileexists-thumbnail-yes' => 'Осы файл — мөлшері кішірітілген көшірмесі (нобай) сияқты. [[$1|thumb]]
Өтініш, <strong>[[:$1]]</strong> деген файлды тексеріңіз.
Егер көрсетілген файл дәл сіз жүктейін деп жатқан файл болса, онда оның кішірейтілген көшірмесін қайта жүктеудің қажеті жоқ.',
'file-thumbnail-no' => "Файл атауы <strong>$1</strong> дегенмен басталады.
Бұл — мөлшері кішірітілген сурет ''(нобай)'' сияқты.
Егер бұл суреттің толық ажыратылымдығы болса, бұны қотарып беріңіз, әйтпесе файл атауын өзгертіңіз.",
'fileexists-forbidden' => 'Осылай аталған файл алдақашан бар;
кері қайтыңыз да, осы файлды жаңа атымен жүктеп беріңіз. [[File:$1|нобай|center|$1]]',
'fileexists-shared-forbidden' => 'Осылай аталған файл ортаққорда алдақашан бар;
кері қайтыңыз да, осы файлды жаңа атымен жүктеп беріңіз. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Бұл файл келесі {{PLURAL:$1|файлдың|файлдарының}} телнұсқасы:',
'uploadwarning' => 'Жүктеу жөнінде құлақтандыру',
'savefile' => 'Файлды сақтау',
'uploadedimage' => '«[[$1]]» файлын жүктеді',
'overwroteimage' => '«[[$1]]» деген файлдың жаңа нұсқасын жүктеді',
'uploaddisabled' => 'Жүктеу өшірілген',
'copyuploaddisabled' => 'URL арқылы жүктеу өшірілген.',
'uploadfromurl-queued' => 'Сіздің жүктеулеріңіз кезекте тұр.',
'uploaddisabledtext' => 'Файл жүктеу өшірілген.',
'uploadscripted' => 'Бұл файлда веб шолғышты қателікпен талдатқызатын HTML не әмір коды бар.',
'uploadvirus' => 'Бұл файлда вирус бар! Егжей-тегжейлері: $1',
'upload-source' => 'Қайнар файл',
'sourcefilename' => 'Қайнар файл атауы:',
'sourceurl' => 'Қайнардың URL-мекенжайы:',
'destfilename' => 'Файл атауы:',
'upload-maxfilesize' => 'Файлдың ең көп мүмкін мөлшері: $1',
'upload-description' => 'Файл сипаттамасы',
'upload-options' => 'Жүктеу баптаулары',
'watchthisupload' => 'Осы файлды бақылау',
'filewasdeleted' => 'Бұндай атаумен файл бұрын жүктелген болатын, бірақ кейін жойылды. Бұны қайта жүктеу алдында $1 дегенді тексеріп шығыңыз.',
'filename-bad-prefix' => "Қотарып бермек файлыңыздың атауы '''«$1» ''' деп басталады, мынадай сипаттаусыз атауды әдетте сандық камералар өздіктік береді.
Файлыңызға сипаттылау атауды таңдаңыз.",
'upload-success-subj' => 'Сәтті жүктелді',
'upload-success-msg' => '[$2] дегеннен сәтті жүктедіңіз. Оны мынадан ала аласыз [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Жүктеу мәселесі',
'upload-warning-subj' => 'Жүктеу кезіндегі ескерту',

'upload-proto-error' => 'Бұрыс хаттама',
'upload-proto-error-text' => 'Шеттен жүктеу үшін URL жайлары <code>http://</code> немесе <code>ftp://</code> дегендерден басталу жөн.',
'upload-file-error' => 'Ішкі қате',
'upload-file-error-text' => 'Серверде уақытша файл құрылуы ішкі қатесіне ұшырасты.
Бұл жүйенің әкімшімен қатынасыңыз.',
'upload-misc-error' => 'Жүктеу кезіндегі белгісіз қате',
'upload-misc-error-text' => 'Жүктеу кезінде белгісіз қатеге ұшырасты.
URL жарамды және қатынаулы екенін тексеріп шығыңыз да қайта байқап көріңіз.
Егер бұл мәселе әлде де қалса, жүйе әкімшімен қатынасыңыз.',
'upload-too-many-redirects' => 'URL шектен тыс жылжытуларға ие',
'upload-unknown-size' => 'Белгісіз өлшем',

# File backend
'backend-fail-delete' => '«$1» файлы жойылмайды.',
'backend-fail-describe' => '"$1" файлы үшін метадерегі өзгертілмейді.',
'backend-fail-alreadyexists' => '"$1" файлы бұрыннан бар.',
'backend-fail-copy' => '«$1» файлы «$2» файлына көшірілмеді.',
'backend-fail-move' => '«$1» файл атауы «$2» атауына өзгертілмеді.',
'backend-fail-opentemp' => 'Сырттан алынатын файл ашылмайды',
'backend-fail-read' => '«$1» файлы оқылмайды.',
'backend-fail-create' => '«$1» файлы жазылмайды.',
'backend-fail-maxsize' => '"$1" файлы жазылмайды, себебі {{PLURAL:$2|1 байттан|$2 байттан}} үлкенірек.',

# HTTP errors
'http-invalid-url' => 'Жарамсыз URL: $1',
'http-read-error' => 'HTTP оқудағы қате.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL жетілмеді',
'upload-curl-error6-text' => 'Келтірілген URL жетілмеді.
URL дұрыс екендігін және торап істеп тұрғанын қос тексеріңіз.',
'upload-curl-error28' => 'Жүктеу уақыты бітті',
'upload-curl-error28-text' => 'Тораптың жауап беруі тым ұзақ уақытқа созылды.
Бұл торап істе екенін тексеріп шығыңыз, азғана кідіре тұрыңыз да қайта байқап көріңіз.
Талабыңызды қол тиген кезінде қайта байқап көруіңіз мүмкін.',

'license' => 'Лицензияландыруы:',
'license-header' => 'Лицензияландыруы',
'nolicense' => 'Ештеңе таңдалмаған',
'license-nopreview' => '(Қарап шығу жетімді емес)',
'upload_source_url' => '(жарамды, баршаға қатынаулы URL)',
'upload_source_file' => '(компьютеріңіздегі файл)',

# Special:ListFiles
'listfiles-summary' => 'Бұл арнайы бетте барлық жүктелген файлдар көрсетіледі.
Соңғы жүктелген файлдар тізімде жоғарғы шетімен әдепкіден көрсетіледі.
Бағанның бас жолын нұқығанда сұрыптаудың реттеуі өзгертіледі.',
'listfiles_search_for' => 'Медиа атауын іздеу:',
'imgfile' => 'файл',
'listfiles' => 'Файл тізімі',
'listfiles_thumb' => 'Нобай',
'listfiles_date' => 'Күн-айы',
'listfiles_name' => 'Атауы',
'listfiles_user' => 'Қатысушы',
'listfiles_size' => 'Өлшемі',
'listfiles_description' => 'Сипаттамасы',
'listfiles_count' => 'Нұсқалары',

# File description page
'file-anchor-link' => 'Файл беті',
'filehist' => 'Файл тарихы',
'filehist-help' => 'Файлдың қай уақытта қалай көрінетін үшін Күн-ай/Уақыт дегенді нұқыңыз.',
'filehist-deleteall' => 'барлығын жой',
'filehist-deleteone' => 'жой',
'filehist-revert' => 'қайтар',
'filehist-current' => 'ағымдағы',
'filehist-datetime' => 'Күн-ай/Уақыт',
'filehist-thumb' => 'Нобай',
'filehist-thumbtext' => '$1 кезіндегі нұсқасы үшін нобай',
'filehist-nothumb' => 'Нобайсыз',
'filehist-user' => 'Қатысушы',
'filehist-dimensions' => 'Өлшемдері',
'filehist-filesize' => 'Файл өлшемі',
'filehist-comment' => 'Пікір',
'filehist-missing' => 'Жоғалған файл',
'imagelinks' => 'Файл қолданылуы',
'linkstoimage' => 'Бұл файлға келесі {{PLURAL:$1|беттер|$1 бет}} сілтейді:',
'nolinkstoimage' => 'Бұл файлға еш бет сілтемейді.',
'morelinkstoimage' => 'Бұл файлдың [[Special:WhatLinksHere/$1|көбірек сілтемелерін]] қарау.',
'linkstoimage-redirect' => '$1 (файл айдатылуы) $2',
'duplicatesoffile' => 'Келесі {{PLURAL:$1|файл бұл файлдың телнұсқасы|$1 файл бұл файлдың телнұсқалары}} ([[Special:FileDuplicateSearch/$2|толығырақ көру]]):',
'sharedupload' => 'Бұл файл $1 жобасынан сондықтан басқа жобаларда қолдануы мүмкін.',
'sharedupload-desc-there' => 'Бұл файл $1 жобасынан және сондықтан басқа жобаларда қолдануы мүмкін.
Қосымша мәліметтер үшін [$2 файл сипаттама бетін] қараңыз.',
'sharedupload-desc-here' => 'Бұл файл $1 жобасынан және сондықтан басқа жобаларда қолдануы мүмкін.
Бұның сипатамасы [$2 файл сипаттама беті] төменде көрсетілген.',
'sharedupload-desc-edit' => 'Бұл файл $1 жобасынан және сондықтан басқа жобаларда қолдануы мүмкін.
Сипаттамасын өңдегіңіз келсе мұнда [$2 файл сипаттама беті].',
'sharedupload-desc-create' => 'Бұл файл $1 жобасынан және сондықтан басқа жобаларда қолдануы мүмкін.
Сипаттамасын өңдегіңіз келсе мұнда [$2 файл сипаттама беті].',
'filepage-nofile' => 'Бұл атаумен файл жоқ.',
'filepage-nofile-link' => 'Бұл атаумен файл жоқ, бірақ сіз оны [$1 жүктей аласыз].',
'uploadnewversion-linktext' => 'Бұл файлдың жаңа нұсқасын жүктеу',
'shared-repo-from' => '$1 дегеннен',
'shared-repo' => 'қоймаға қосылған',
'upload-disallowed-here' => 'Сіз бұл файлдың үстінен жаза алмайсыз',

# File reversion
'filerevert' => '$1 дегенді қайтару',
'filerevert-legend' => 'Файлды қайтару',
'filerevert-intro' => "Сіз  '''[[Media:$1|$1]]''' дегенді [$3, $2 кезіндегі $4 нұсқасына] қайтарудасыз.",
'filerevert-comment' => 'Себебі:',
'filerevert-defaultcomment' => '$2, $1 кезіндегі нұсқасына қайтарылды',
'filerevert-submit' => 'Қайтару',
'filerevert-success' => "'''[[Media:$1|$1]]''' деген [$3, $2 кезіндегі $4 нұсқасына] қайтарылды.",
'filerevert-badversion' => 'Келтірілген уақыт белгісімен бұл файлдың алдыңғы жергілікті нұсқасы жоқ.',

# File deletion
'filedelete' => '$1 дегенді жою',
'filedelete-legend' => 'Файлды жою',
'filedelete-intro' => "Сіз  '''[[Media:$1|$1]]''' деген файлды барлық тарихымен бірге жоймақшысыз.",
'filedelete-intro-old' => "'''[[Media:$1|$1]]''' — [$4 $3, $2 кезіндегі нұсқасын] жоюдасыз.",
'filedelete-comment' => 'Себебі:',
'filedelete-submit' => 'Жой',
'filedelete-success' => "'''$1''' деген жойылды.",
'filedelete-success-old' => "'''[[Media:$1|$1]]''' дегеннің $3, $2 кезіндегі нұсқасы жойылды.",
'filedelete-nofile' => "'''$1''' деген жоқ.",
'filedelete-nofile-old' => "Келтірілген анықтауыштарымен '''$1''' дегеннің мұрағатталған нұсқасы мында жоқ.",
'filedelete-otherreason' => 'Басқа/қосымша себеп:',
'filedelete-reason-otherlist' => 'Басқа себеп',
'filedelete-reason-dropdown' => '* Жоюдың жалпы себептері
** Авторлық құқықтарын бұзу
** Файл телнұсқасы',
'filedelete-edit-reasonlist' => 'Жою себептерін өңдеу',
'filedelete-maintenance-title' => 'Файл жойылмады',

# MIME search
'mimesearch' => 'Файлды MIME түрімен іздеу',
'mimesearch-summary' => 'Бұл бетте файлдарды MIME түрімен сүзгілеуі қосылған.
Кірісі: мағлұмат_түрі/түр_тарауы, мысалы <code>image/jpeg</code>.',
'mimetype' => 'MIME түрі:',
'download' => 'Түсіріп алу',

# Unwatched pages
'unwatchedpages' => 'Бақыланылмаған беттер',

# List redirects
'listredirects' => 'Айдағыш бет тізімі',

# Unused templates
'unusedtemplates' => 'Пайдаланылмаған үлгілер',
'unusedtemplatestext' => 'Бұл бет басқа бетке кіріcтірілмеген {{ns:template}} есім кеңістігіндегі барлық беттерді тізімдейді.
Үлгілерді жоймас бұрын бұның өзге сілтемелерін тексеріп шығуын ұмытпаңыз.',
'unusedtemplateswlh' => 'басқа сілтемелер',

# Random page
'randompage' => 'Кездейсоқ бет',
'randompage-nopages' => 'Мұнда келесі {{PLURAL:$2|есім кеңістігі|есім кеңістігінде}}: $1 беттер жоқ.',

# Random page in category
'randomincategory' => 'Санаттағы кездейсоқ бет',
'randomincategory-invalidcategory' => '"$1" жарамды санат аты емес.',
'randomincategory-nopages' => 'Бұлар [[:Category:$1]] беттері мес.',
'randomincategory-selectcategory-submit' => 'Өту',

# Random redirect
'randomredirect' => 'Кездейсоқ айдағыш',
'randomredirect-nopages' => 'Бұл есім аясында еш айдағыш жоқ "$1".',

# Statistics
'statistics' => 'Санақ',
'statistics-header-pages' => 'Беттер статистикасы',
'statistics-header-edits' => 'Өңдеулер статистикасы',
'statistics-header-views' => 'Қаралу статистикасы',
'statistics-header-users' => 'Қатысушы статистикасы',
'statistics-header-hooks' => 'Басқа статистикалар',
'statistics-articles' => 'Мақалалар саны',
'statistics-pages' => 'Беттер',
'statistics-pages-desc' => 'Уикидегі барлық беттер, соның ішінде талқылау беттері, айдатқыштар және басқалары (санат, жоба, портал, файл, Уикипедия, қатысушы жеке беттері, үлгі).',
'statistics-files' => 'Жүктелген файлдар',
'statistics-edits' => '{{SITENAME}} құрылғанан бергі бет өңдеулері',
'statistics-edits-average' => 'Әр бетке шаққандағы өңдеулердің орташа саны',
'statistics-views-total' => 'Барлық қаралулар',
'statistics-views-total-desc' => 'Қаралуларға бар болмаған беттер және арнайы беттер кірмейді',
'statistics-views-peredit' => 'Өңдеуге шаққанда қаралулар',
'statistics-users' => 'Тіркелген қатысушылар [[Special:ListUsers|(тізім)]]',
'statistics-users-active' => 'Белсенді қатысушылар',
'statistics-users-active-desc' => 'Соңғы {{PLURAL:$1|күнде|$1 күнде}} қандай да бір іс-әрекет жасаған қатысушылар',
'statistics-mostpopular' => 'Ең көп қаралған беттер',

'pageswithprop' => 'Беттер бет сипатымен',
'pageswithprop-legend' => 'Беттер бет сипатымен',
'pageswithprop-prop' => 'Меншік атауы:',
'pageswithprop-submit' => 'Өту',

'doubleredirects' => 'Екі мәрте айдағыштар',
'doubleredirectstext' => 'Бұл бетте басқа айдату беттерге сілтейтін беттер тізімделінеді. Әрбір жолақта бірінші және екінші айдағышқа сілтемелер бар, сонымен бірге екінші айдағыш нысанасы бар, әдетте бұл бірінші айдағыш бағыттайтын «нақты» нысана бет атауы болуы керек.',
'double-redirect-fixed-move' => '[[$1]] жылжытылды.
оған қазір [[$2]] дегенге айдатылды.',

'brokenredirects' => 'Сынық айдағыштар',
'brokenredirectstext' => 'Келесі айдағыштар жоқ беттерге сілтейді:',
'brokenredirects-edit' => 'өңдеу',
'brokenredirects-delete' => 'жою',

'withoutinterwiki' => 'Еш тілге сілтeмеген беттер',
'withoutinterwiki-summary' => 'Келесі беттер басқа тілдерге сілтемейді',
'withoutinterwiki-legend' => 'Басталуы:',
'withoutinterwiki-submit' => 'Көрсет',

'fewestrevisions' => 'Ең аз түзетілген беттер',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|байт|байт}}',
'ncategories' => '$1 {{PLURAL:$1|Санат|Санаттар}}',
'ninterwikis' => '$1 {{PLURAL:$1|интеруики|интеруикилер}}',
'nlinks' => '$1 {{PLURAL:$1|сілтеме|сілтемелер}}',
'nmembers' => '$1 {{PLURAL:$1|мүше|мүше}}',
'nrevisions' => '$1 {{PLURAL:$1|түзету|түзету}}',
'nviews' => '$1 {{PLURAL:$1|қаралу|қаралу}}',
'nimagelinks' => '$1 {{PLURAL:$1|бетінде|беттерінде}} қолданылады',
'ntransclusions' => '$1 {{PLURAL:$1|бетінде|беттерінде}} қолданылады',
'specialpage-empty' => 'Бұл баянатқа еш нәтиже жоқ.',
'lonelypages' => 'Еш беттен сілтелмеген беттер',
'lonelypagestext' => 'Келесі беттерге {{SITENAME}} жобасындағы басқа беттерінің ішіндегі кірікбеттер сілтемейді.',
'uncategorizedpages' => 'Санатсыз беттер',
'uncategorizedcategories' => 'Санатсыз санаттар',
'uncategorizedimages' => 'Санатсыз файлдар',
'uncategorizedtemplates' => 'Санатсыз үлгілер',
'unusedcategories' => 'Пайдаланылмаған санаттар',
'unusedimages' => 'Пайдаланылмаған файлдар',
'popularpages' => 'Ең көп қаралған беттер',
'wantedcategories' => 'Басталмаған санаттар',
'wantedpages' => 'Басталмаған беттер',
'wantedfiles' => 'Басталмаған файлдар',
'wantedtemplates' => 'Басталмаған үлгілер',
'mostlinked' => 'Ең көп сілтенген беттер',
'mostlinkedcategories' => 'Ең көп сілтенген санаттар',
'mostlinkedtemplates' => 'Ең көп сілтенген үлгілер',
'mostcategories' => 'Ең көп санаты бар беттер',
'mostimages' => 'Ең көп сілтенген файлдар',
'mostinterwikis' => 'Ең көп интеруикилері бар беттер',
'mostrevisions' => 'Ең көп түзетілген беттер',
'prefixindex' => 'Атау бастауыш тізімі',
'prefixindex-namespace' => 'Атау бастауыш тізімі ($1 есім кеңістігі)',
'shortpages' => 'Ең қысқа беттер',
'longpages' => 'Ең ұзын беттер',
'deadendpages' => 'Еш бетке сілтемейтін беттер',
'deadendpagestext' => 'Келесі беттер {{SITENAME}} жобасындағы басқа беттерге сілтемейді.',
'protectedpages' => 'Қорғалған беттер',
'protectedpages-indef' => 'Тек белгісіз қорғаулар',
'protectedpages-cascade' => 'Тек баулы қорғаулар',
'protectedpagestext' => 'Келесі беттер өңдеуден немесе жылжытудан қорғалған',
'protectedpagesempty' => 'Ағымда мынадай параметрлермен ешбір бет қорғалмаған.',
'protectedtitles' => 'Қорғалған тақырып аттары',
'protectedtitlestext' => 'Келесі тақырып аттарын бастауға рұқсат берілмеген',
'protectedtitlesempty' => 'Бұл бапталымдармен ағымда еш тақырып аттары қорғалмаған.',
'listusers' => 'Қатысушы тізімі',
'listusers-editsonly' => 'Тек қатысушы өңдемелерін көрсету',
'listusers-creationsort' => 'Басталған уақытына қарай іріктеу',
'usereditcount' => '$1 {{PLURAL:$1|өңдеме|өңдемелер}}',
'usercreated' => '$1 $2-та {{GENDER:$3|басталған}}',
'newpages' => 'Ең жаңа беттер',
'newpages-username' => 'Қатысушы аты:',
'ancientpages' => 'Ең ескі беттер',
'move' => 'Жылжыту',
'movethispage' => 'Бұл бетті жылжыту',
'unusedimagestext' => '<p>Аңғартпа: Ғаламтордағы басқа тораптар файлға тура URL арқылы сілтеуі мүмкін. Сондықтан, белсенді пайдалануына аңғармай, осы тізімде қалуы мүмкін.</p>',
'unusedcategoriestext' => 'Келесі санат беттері бар боп тұр, бірақ оған еш бет не санат кірмейді.',
'notargettitle' => 'Нысана жоқ',
'notargettext' => 'Осы жете орындалатын нысана бетті, не қатысушыны енгізбепсіз.',
'nopagetitle' => 'Мынадай еш нысана бет жоқ',
'nopagetext' => 'Келтірілген нысана бетіңіз жоқ.',
'pager-newer-n' => '{{PLURAL:$1|жаңалау 1|жаңалау $1}}',
'pager-older-n' => '{{PLURAL:$1|ескілеу 1|ескілеу $1}}',
'suppress' => 'Шеттету',

# Book sources
'booksources' => 'Кітап қайнарлары',
'booksources-search-legend' => 'Кітап қайнарларын іздеу',
'booksources-go' => 'Өту',
'booksources-text' => 'Төменде жаңа және қолданған кітаптар сататын тораптарының сілтемелері тізімделген. Бұл тораптарда ізделген кітаптар туралы былайғы ақпарат болуға мүмкін.',

# Special:Log
'specialloguserlabel' => 'Орындаушы:',
'speciallogtitlelabel' => 'Нысана (тақырып аты немесе қатысушы):',
'log' => 'Журналдар',
'all-logs-page' => 'Барлық журналдар',
'alllogstext' => '{{SITENAME}} жобасының барлық қатынаулы журналдарын біріктіріп көрсетуі.
Журнал түрін, қатысушы атын (үлкен кішілігін ескеріп), не тиісті бетін бөлектеп, тарылтып қарай аласыз (кейде үлкен кішілігін ескеріп).',
'logempty' => 'Журналда сәйкес даналар жоқ.',
'log-title-wildcard' => 'Мына мәтіннен басталытын тақырып аттарын іздеу',
'showhideselectedlogentries' => 'Таңдалған журнал енгізілімдерін көрсету/жасыру',

# Special:AllPages
'allpages' => 'Барлық беттер',
'alphaindexline' => '$1 дегеннен $2',
'nextpage' => 'Келесі бетке ($1)',
'prevpage' => 'Алдыңғы бетке ($1)',
'allpagesfrom' => 'Мына беттерден бастап көрсету:',
'allpagesto' => 'Мына беттерден аяқталғанды көрсету:',
'allarticles' => 'Барлық беттер тізімі',
'allinnamespace' => 'Барлық беттер ($1 есім кеңістігі)',
'allnotinnamespace' => 'Барлық беттер ($1 есім кеңістігінен тыс)',
'allpagesprev' => 'Алдыңғыға',
'allpagesnext' => 'Келесіге',
'allpagessubmit' => 'Өту',
'allpagesprefix' => 'Мынадан басталған беттерді көрсету:',
'allpagesbadtitle' => 'Келтірілген бет тақырыбын аты жарамсыз болған, немесе тіл-аралық не уики-аралық бастауы бар болды.
Мында тақырып атында қолдалмайтын бірқатар таңбалар болуы мүмкін.',
'allpages-bad-ns' => '{{SITENAME}} жобасында «$1» есім кеңістігі жоқ.',
'allpages-hide-redirects' => 'Айдатқыштарды жасыру',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Сіз бұл беттің кештегі нұсқасын көріп тұрсыз, $1 дейінгі ескісі болуы мүмкін.',
'cachedspecial-refresh-now' => 'Ең соңғысын қарау',

# Special:Categories
'categories' => 'Санаттар',
'categoriespagetext' => 'Келесі {{PLURAL:$1|санат ішінде|санаттар ішінде}} беттер немесе медиа бар.
[[Special:UnusedCategories|Пайдаланылмаған санаттарды]] мынадан қарай аласыз.
Тағы қараңыз [[Special:WantedCategories|басталмаған санаттар]].',
'categoriesfrom' => 'Санаттарды мынадан бастап көрсету:',
'special-categories-sort-count' => 'санымен сұрыптау',
'special-categories-sort-abc' => 'әліпбимен сұрыптау',

# Special:DeletedContributions
'deletedcontributions' => 'Қатысушының жойылған үлесі',
'deletedcontributions-title' => 'Қатысушының жойылған үлесі',
'sp-deletedcontributions-contribs' => 'үлестер',

# Special:LinkSearch
'linksearch' => 'Сыртқы сілтемелерді іздеу',
'linksearch-pat' => 'Іздеу шарты:',
'linksearch-ns' => 'Есім кеңістігі:',
'linksearch-ok' => 'Іздеу',
'linksearch-text' => '«*.wikipedia.org» атауына ұқсасты бәдел нышандарды қолдануға болады.',
'linksearch-line' => '$2 дегеннен $1 сілтеген',
'linksearch-error' => 'Бәдел нышандар тек сервер жайы атауының бастауында болуы мүмкін.',

# Special:ListUsers
'listusersfrom' => 'Мына қатысушыдан бастап көрсету:',
'listusers-submit' => 'Көрсет',
'listusers-noresult' => 'Қатысушы табылған жоқ.',
'listusers-blocked' => '(бұғатталған)',

# Special:ActiveUsers
'activeusers' => 'Белсенді қатысушылар тізімі',
'activeusers-intro' => 'Бұл тізім соңғы $1 {{PLURAL:$1|күнде|күнде}} қандай да бір іс-әрекет жасаған қатысушылар тізімі.',
'activeusers-count' => 'соңғы {{PLURAL:$3|күнде|$3 күнде}} $1 {{PLURAL:$1|әрекет|әрекет}}',
'activeusers-from' => 'Мынадан басталатын қатысушыларды көрсет:',
'activeusers-hidebots' => 'Боттарды жасыру',
'activeusers-hidesysops' => 'Әкімшілерді жасыру',
'activeusers-noresult' => 'Қатысушылар табылған жоқ.',

# Special:ListGroupRights
'listgrouprights' => 'Қатысушы тобы құқықтары',
'listgrouprights-summary' => 'Келесі тізімде бұл уикиде тағайындалған қатысушы құқықтары (байланысты қатынау құқықтарымен бірге) көрсетіледі.
Жеке құқықтар туралы көбірек ақпаратты [[{{MediaWiki:Listgrouprights-helppage}}|мында]] таба аласыз.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Берілген құқығы</span>
* <span class="listgrouprights-revoked">Бұзылған құқығы</span>',
'listgrouprights-group' => 'Топ',
'listgrouprights-rights' => 'Құқықтары',
'listgrouprights-helppage' => '{{ns:4}}:Топ құқықтары',
'listgrouprights-members' => '(мүше тізімі)',
'listgrouprights-addgroup' => '{{PLURAL:$2|топты|топтарды}} қосу: $1',
'listgrouprights-removegroup' => '{{PLURAL:$2|топты|топтарды}} алып тастау: $1',
'listgrouprights-addgroup-all' => 'Барлық топтарды қосу',
'listgrouprights-removegroup-all' => 'Барлық топтарды алып тастау',
'listgrouprights-addgroup-self' => 'Өзіңіздің тіркелгіңізге {{PLURAL:$2|топты|топтарды}} қосу : $1',
'listgrouprights-removegroup-self' => 'Өз тіркелгіңізді {{PLURAL:$2|топтан|топтардан}} алып тастау: $1',
'listgrouprights-addgroup-self-all' => 'Өз тіркелгіңізге барлық топтарды қосу',
'listgrouprights-removegroup-self-all' => 'Өз тіркелгіңізден барлық топтарды алып тастау',

# Email user
'mailnologin' => 'Еш мекенжай жөнелтілген жоқ',
'mailnologintext' => 'Басқа қатысушыға хат жөнелту үшін [[Special:UserLogin|кіруіңіз]] жөн, және [[Special:Preferences|бапталымдарыңызда]] жарамды е-пошта мекенжайы болуы жөн.',
'emailuser' => 'Қатысушыға хат жазу',
'emailuser-title-target' => 'Бұл {{GENDER:$1|қатысушы}} email-ы',
'emailuser-title-notarget' => 'Қатысушы е-поштасы',
'emailpage' => 'Қатысушыға хат жазу',
'emailpagetext' => 'Егер бұл қатысушы баптауларында жарамды е-пошта мекенжайын енгізсе, төмендегі пішін арқылы бұған жалғыз е-пошта хатын жөнелтуге болады.
Қатысушы баптауыңызда енгізген е-пошта мекенжайыңыз «Кімнен» деген бас жолағында көрінеді, сондықтан хат алушысы тура жауап бере алады.',
'usermailererror' => 'Mail нысаны қате қайтарды:',
'defemailsubject' => '{{SITENAME}} е-поштасының хаты',
'usermaildisabled' => 'Қатысушының электронды поштасы қосылмаған',
'usermaildisabledtext' => 'Бұл уикиде басқа қатысушыларға хат жібере алмайсыз',
'noemailtitle' => 'Еш е-пошта мекенжайы жоқ',
'noemailtext' => 'Бұл қатысушы жарамды Е-пошта мекенжайын келтірмеген, не басқалардан хат қабылдауын өшірген.',
'emailusername' => 'Қатысушы аты:',
'emailusernamesubmit' => 'Жіберу',
'email-legend' => 'Басқа{{SITENAME}} қатысушысына хат жіберу',
'emailfrom' => 'Кімнен:',
'emailto' => 'Кімге:',
'emailsubject' => 'Тақырыбы:',
'emailmessage' => 'Хат:',
'emailsend' => 'Жіберу',
'emailccme' => 'Хатымдың көшірмесін маған да жібер.',
'emailccsubject' => '$1 дегенге хатыңыздың көшірмесі: $2',
'emailsent' => 'Хат жөнелтілді',
'emailsenttext' => 'Е-пошта хатыңыз жөнелтілді.',

# User Messenger
'usermessage-summary' => 'Жүйе хабарламасы қалдырылуда.',
'usermessage-editor' => 'Жүйе мессенжері',

# Watchlist
'watchlist' => 'Бақылау тізімі',
'mywatchlist' => 'Бақылау тізімі',
'watchlistfor2' => '$1 ($2) бақылау тізімі',
'nowatchlist' => 'Бақылау тізіміңізде еш дана жоқ',
'watchlistanontext' => 'Бақылау тізіміңіздегі даналарды қарау, не өңдеу үшін $1 керек.',
'watchnologin' => 'Кірмегенсіз',
'watchnologintext' => 'Бақылау тізіміңізді өзгерту үшін [[Special:UserLogin|кіріңіз]].',
'addwatch' => 'Бақылау тізіміңізге қосу',
'addedwatchtext' => "«[[:$1]]» беті [[Special:Watchlist|бақылау тізіміңізге]] қосылды.
Бұл беттің және байланысты талқылау бетінің келешектегі өзгерістері мында тізімделінеді де, және беттің атауы жеңіл табылу үшін [[{{#special:Recentchanges}}|жуықтағы өзгерістер тізімінде]] '''жуан әрпімен''' көрсетіледі.",
'removewatch' => 'Бақылау тізіміңізден аластату',
'removedwatchtext' => '«[[:$1]]» беті [[Special:Watchlist|бақылау тізіміңізден]] аласталды.',
'watch' => 'Бақылау',
'watchthispage' => 'Бұл бетті бақылау',
'unwatch' => 'Бақыламау',
'unwatchthispage' => 'Бақылауды тоқтату',
'notanarticle' => 'Мағлұмат беті емес',
'notvisiblerev' => 'Түзету жойылды',
'watchlist-details' => 'Тізіміңізде $1 бет бар (талқылау беттері саналмайды).',
'wlheader-enotif' => 'Ескерту хат жіберуі қосылған.',
'wlheader-showupdated' => "Соңғы келіп-кетуіңізден бері өзгертілген беттерді '''жуан''' қаріпімен көрсет",
'watchmethod-recent' => 'бақылаулы беттер үшін жуықтағы өзгерістерді тексеру',
'watchmethod-list' => 'жуықтағы өзгерістер үшін бақылаулы беттерді тексеру',
'watchlistcontains' => 'Бақылау тізіміңізде $1 бет бар.',
'iteminvalidname' => "'$1' данада ақау бар — жарамсыз атау…",
'wlnote' => "Төменде $3, $4 кезіне дейінгі соңғы {{PLURAL:$2|сағатта|'''$2''' сағатта}} болған, {{PLURAL:$1|жуықтағы өзгеріс|жуықтағы '''$1''' өзгеріс}} көрсетіледі.",
'wlshowlast' => 'Соңғы $1 сағаттағы, $2 күндегі, $3 болған өзгерісті көрсету',
'watchlist-options' => 'Бақылау тізімінің баптаулары',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Бақылауда…',
'unwatching' => 'Бақыламауда…',

'enotif_mailer' => '{{SITENAME}} ескерту хат жіберу қызметі',
'enotif_reset' => 'Барлық бет келіп-кетілді деп белгіле',
'enotif_impersonal_salutation' => '{{SITENAME}} қатысушысы',
'enotif_subject_deleted' => '{{SITENAME}} $1 бетін $2 қатысушысы {{GENDER:$2|жойған}}',
'enotif_subject_created' => '{{SITENAME}} $1 бетін $2 қатысушысы {{GENDER:$2|бастаған}}',
'enotif_subject_moved' => '{{SITENAME}} $1 бетін $2 қатысушысы {{GENDER:$2|жылжытқан}}',
'enotif_subject_restored' => '{{SITENAME}} $1 бетін $2 қатысушысы {{GENDER:$2|қалпына келтірген}}',
'enotif_subject_changed' => '{{SITENAME}} $1 бетін $2 қатысушысы {{GENDER:$2|өзгерткен}}',
'enotif_body_intro_deleted' => '{{SITENAME}} $1 бетін $2 қатысушысы $PAGEEDITDATE $2,  {{GENDER:$2|жойған}} қара $3',
'enotif_body_intro_created' => '{{SITENAME}} $1 бетін $2 қатысушысы $PAGEEDITDATE $2,  {{GENDER:$2|бастаған}}, соңғы нұсқасы үшін қара $3',
'enotif_body_intro_moved' => '{{SITENAME}} $1 бетін $2 қатысушысы $PAGEEDITDATE $2,  {{GENDER:$2|жылжытқан}}, соңғы нұсқасы үшін қара $3',
'enotif_body_intro_restored' => '{{SITENAME}} $1 бетін $2 қатысушысы $PAGEEDITDATE $2,  {{GENDER:$2|қалпына келтірген}}, соңғы нұсқасы үшін қара $3',
'enotif_body_intro_changed' => '{{SITENAME}} $1 бетін $2 қатысушысы $PAGEEDITDATE $2,  {{GENDER:$2|өзгерткен}}, соңғы нұсқасы үшін қара $3',
'enotif_lastvisited' => 'Соңғы келіп-кетуіңізден бері болған өзгерістер үшін $1 дегенді қараңыз.',
'enotif_lastdiff' => 'Осы өзгеріс үшін $1 дегенді қараңыз.',
'enotif_anon_editor' => 'тіркелгісіз қатысушы $1',
'enotif_body' => 'Қадірлі $WATCHINGUSERNAME,


{{SITENAME}} жобасының $PAGETITLE атаулы бетті $PAGEEDITDATE кезінде $PAGEEDITOR деген $CHANGEDORCREATED, ағымдық нұсқасы үшін $PAGETITLE_URL қараңыз.

$NEWPAGE

Өңдеуші келтірген қысқаша мазмұндамасы: $PAGESUMMARY $PAGEMINOREDIT

Өңдеушімен қатынасу:
е-пошта: $PAGEEDITOR_EMAIL
уики: $PAGEEDITOR_WIKI

Былайғы өзгерістер болғанда да осы бетке келіп-кетуіңізгенше дейін ешқандай басқа ескерту хаттар жіберілмейді.
Сонымен қатар бақылау тізіміңіздегі бет ескертпелік белгісін қайта қойыңыз.

             Сіздің достық {{SITENAME}} жобасының ескерту қызметі

----
Бақылау тізіміңіздің баптаулырын өзгерту үшін, мында келіп-кетіңіз:
{{canonicalurl:{{#special:EditWatchlist}}}}

Сын-пікір беру және былайғы жәрдем алу үшін:
{{canonicalurl:{{{{ns:mediawiki}}:Helppage}}}}',
'created' => 'бастады',
'changed' => 'өзгертті',

# Delete
'deletepage' => 'Бетті жою',
'confirm' => 'Құптау',
'excontent' => "болған мағлұматы: '$1'",
'excontentauthor' => 'болған мағлұматы (тек "[[Special:Contributions/$2|$2]]" үлесі): "$1"',
'exbeforeblank' => 'тазарту алдындағы болған мағлұматы: "$1"',
'exblank' => 'бет бос болды',
'delete-confirm' => '«$1» дегенді жою',
'delete-legend' => 'Жою',
'historywarning' => "'''Ескету:'' Жоюы көзделген бетте бет тарихында шамамен $1 {{PLURAL:$1|түзетілуі|түзетілулері}} бар:",
'confirmdeletetext' => '<div id="confirmdeletetext">
Бетті бүкіл тарихымен бірге дерекқордан жойғалы жатырсыз.
Бұл әрекетіңіз ниетпен жасалғанын, әрекет салдары есепке алынғанын және әрекетіңіз [[{{MediaWiki:Policy-url}}|ережелерге]] лайықты болғанын тағы бір рет тексеріп шығуыңызды сұраймыз. Сонымен бірге [[Special:Whatlinkshere/{{FULLPAGENAMEE}}|мұнда сілтенген беттерді]] тексеріңіз, мүмкін болса сілтемелерін дұрыстап шығыңыз.
</div>',
'actioncomplete' => 'Әрекет орындалды',
'actionfailed' => 'Әрекет орындалмады',
'deletedtext' => '"$1" жойылды.
Жуықтағы жоюлар туралы жазбаларын $2 дегеннен қараңыз.',
'dellogpage' => 'Жою журналы',
'dellogpagetext' => 'Төменде жуықтағы жойылған беттер тізімі берілген.',
'deletionlog' => 'жою журналы',
'reverted' => 'Ертерек түзетуіне қайтарылған',
'deletecomment' => 'Себебі:',
'deleteotherreason' => 'Басқа/қосымша себеп:',
'deletereasonotherlist' => 'Басқа себеп',
'deletereason-dropdown' => '* Жоюдың жалпы себептері
** Автордың сұранымы бойынша
** Авторлық құқықтарын бұзу
** Вандализм',
'delete-edit-reasonlist' => 'Жою себептерін өңдеу',
'delete-toobig' => 'Бұл бетте үлкен түзету тарихы бар, $1 {{PLURAL:$1|түзетуден|түзетуден}} астам.
Бұндай беттердің жоюы {{SITENAME}} торабын әлдеқалай үзіп тастауына бөгет салу үшін тиымдалған.',
'delete-warning-toobig' => 'Бұл бетте үлкен өңдеу тарихы бар, $1 {{PLURAL:$1|түзетуден|түзетуден}} астам.
Бұның жоюы {{SITENAME}} торабындағы дерекқор әрекеттерді үзіп тастауын мүмкін;
бұны абайлап өткізіңіз.',

# Rollback
'rollback' => 'Өңдемелерді шегіндіру',
'rollback_short' => 'Шегіндіру',
'rollbacklink' => 'шегіндіру',
'rollbacklinkcount' => '$1 {{PLURAL:$1|өңдемені|өңдемені}} шегіндіру',
'rollbacklinkcount-morethan' => '$1-нан аса {{PLURAL:$1|өңдемені|өңдемелерді}} шегіндіру',
'rollbackfailed' => 'Шегіндіру орындалмады',
'cantrollback' => 'Өңдеме қайтарылмады;
соңғы үлескері тек осы беттің бастаушысы болды.',
'alreadyrolled' => '[[User:$2|$2]] ([[User talk:$2|талқылауы]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); істеген [[:$1]] соңғы өңдемесі шегіндірілмеді;
басқа біреу бұл бетті алдақашан өңдеген немесе шегіндірген.

Бетті [[User:$3|$3]] ([[User talk:$3|talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) соңғы рет өңдеген.',
'editcomment' => "Болған өңдеме түйіндемесі: «''$1''».",
'revertpage' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|т]]) өңдемелерінен [[User:$1|$1]] соңғы нұсқасына қайтарды',
'revertpage-nouser' => 'Жасырылған қатысушы өңдемелерінен [[User:$1|$1]] соңғы нұсқасына қайтарды',
'rollback-success' => '$1 өңдемелерінен қайтарды;
$2 соңғы нұсқасына қайта өзгертті.',

# Edit tokens
'sessionfailure-title' => 'Сеанс сәтсіз болды',
'sessionfailure' => 'Кіру сессиясында шатақ болған сияқты;
сессияға шабуылдаудардан қорғану үшін, осы әрекет тоқтатылды.
«Артқа» дегенді басыңыз, және бетті қайта жүктеңіз де, қайта байқап көріңіз.',

# Protect
'protectlogpage' => 'Қорғау журналы',
'protectlogtext' => 'Төменде беттердің қорғау/қорғамау тізімі берілген.
Ағымдағы қорғау әректтер бар беттер үшін [[Special:ProtectedPages|қорғалған беттер тізімін]] қараңыз.',
'protectedarticle' => '"[[$1]]" бетін қорғады',
'modifiedarticleprotection' => '"[[$1]]" қорғалу деңгейін өзгертті',
'unprotectedarticle' => '"[[$1]]" дегеннен қорғалуын жылжытты',
'movedarticleprotection' => 'қорғалу баптауларын "[[$2]]" дегеннен "[[$1]]" дегенге жылжытты',
'protect-title' => '«$1» қорғау деңгейін өзгерту',
'protect-title-notallowed' => '«$1» қорғалу деңгейін қарау',
'prot_1movedto2' => '[[$1]] дегенді [[$2]] дегенге жылжытты',
'protect-badnamespace-title' => 'Қорғалмайтын есім кеңістігі',
'protect-badnamespace-text' => 'Бұл есім кеңістігіндегі беттер қорғалмайды.',
'protect-norestrictiontypes-title' => 'Қорғалмайтын бет',
'protect-legend' => 'Қорғауды құптау',
'protectcomment' => 'Себебі:',
'protectexpiry' => 'Мерзімі бітпек:',
'protect_expiry_invalid' => 'Бітетін уақыты жарамсыз.',
'protect_expiry_old' => 'Бітетін уақыты өтіп кеткен.',
'protect-unchain-permissions' => 'Басқа қорғау баптауларын құлыптамау',
'protect-text' => "'''$1''' бетінің қорғау деңгейін қарап және өзгертіп шыға аласыз.",
'protect-locked-blocked' => "Бұғаттауыңыз өшірілгенше дейін қорғау деңгейін өзгерте алмайсыз.
Мына '''$1''' беттің ағымдық баптаулары:",
'protect-locked-dblock' => "Дерекқордың құлыптауы белсенді болғандықтан қорғау деңгейлері өзгертілмейді.
Мына '''$1''' беттің ағымдық баптаулары:",
'protect-locked-access' => "Тіркелгіңізге бет қорғау денгейлерін өзгертуіне рұқсат жоқ.
Мына '''$1''' беттің ағымдық баптаулары:",
'protect-cascadeon' => 'Бұл бет ағымда қорғалған, себебі осы бет «баулы қорғауы» бар келесі {{PLURAL:$1|беттің|беттердің}} кірікбеті.
Бұл беттің қорғау деңгейін өзгерте аласыз, бірақ бұл баулы қорғауға ықпал етпейді.',
'protect-default' => 'Барлық қатысушыларға рұқсат ету',
'protect-fallback' => 'Тек «$1» қатысушыларға рұқсат ету',
'protect-level-autoconfirmed' => 'Тіркелгісіздерге тиым',
'protect-level-sysop' => 'Тек әкімшілерге рұқсат ету',
'protect-summary-cascade' => 'баулы',
'protect-expiring' => 'мерзімі бітпек: $1 (UTC)',
'protect-expiring-local' => 'мерзімі бітпек: $1',
'protect-expiry-indefinite' => 'мәңгі',
'protect-cascade' => 'Бұл беттің кірікбеттерін қорғау (баулы қорғау).',
'protect-cantedit' => 'Бұл беттің қорғау деңгейін өзгерте алмайсыз, себебі бұны өңдеуге рұқсатыңыз жоқ.',
'protect-othertime' => 'Басқа уақыт:',
'protect-othertime-op' => 'басқа уақыт',
'protect-existing-expiry' => 'Mерзімі бітпек: $3, $2',
'protect-otherreason' => 'Басқа/қосымша себеп:',
'protect-otherreason-op' => 'Басқа себеп',
'protect-dropdown' => '*Ортақ қорғау себеттер
** Артық бұзақылық
** Артық спамдау
** Counter-productive edit warring
** Жоғары байланысты бет',
'protect-edit-reasonlist' => 'Қорғалу себептерін өңдеу',
'protect-expiry-options' => '1 сағат:1 hour,1 күн:1 day,1 апта:1 week,2 апта:2 weeks,1 ай:1 month,3 ай:3 months,6 ай:6 months,1 жыл:1 year,мәңгі:infinite',
'restriction-type' => 'Рұқсаты:',
'restriction-level' => 'Тиымдық деңгейі:',
'minimum-size' => 'Ең аз өлшемі',
'maximum-size' => 'Ең көп өлшемі:',
'pagesize' => '(байт)',

# Restrictions (nouns)
'restriction-edit' => 'Өңдеуге',
'restriction-move' => 'Жылжытуға',
'restriction-create' => 'Бастауға',
'restriction-upload' => 'Жүктеуге',

# Restriction levels
'restriction-level-sysop' => 'толықтай қорғалған',
'restriction-level-autoconfirmed' => 'жартылай қорғалған',
'restriction-level-all' => 'әр деңгейде',

# Undelete
'undelete' => 'Жойылған беттерді қарау',
'undeletepage' => 'Жойылған беттерді қарау және қалпына келтіру',
'undeletepagetitle' => "'''Келесі тізім [[:$1|$1]] дегеннің жойылған түзетулерінен тұрады'''.",
'viewdeletedpage' => 'Жойылған беттерді қарау',
'undeletepagetext' => 'Келесі беттер жойылды деп белгіленген, бірақ мағлұматы мұрағатта бар
және қалпына келтіруге мүмкін. Мұрағат мерзім бойынша тазаланып тұруы мүмкін.',
'undelete-fieldset-title' => 'Нұсқаларды қалпына келтіру',
'undeleteextrahelp' => "Бүкіл бетті қалпына келтіру үшін, барлық құсбелгі көздерді босатып '''''{{int:Қалпына келтір!}}''''' батырмасын нұқыңыз.
Бөлектеумен қалпына келтіру орындау үшін, келтіремін деген түзетулеріне сәйкес көздерге құсбелгі салыңыз да, және '''''{{int:Қалпына келтір!}}''''' түймесін нұқыңыз. '''''Қайта қой''''' түймесін нұқығанда мәндеме аумағы тазартады және барлық құсбелгі көздерін босатады.",
'undeleterevisions' => '$1 түзету мұрағатталды',
'undeletehistory' => 'Егер бет мағлұматын қалпына келтірсеңіз, тарихында барлық түзетулер да
қайтарылады. Егер жоюдан соң дәл солай атауымен жаңа бет басталса, қалпына келтірілген түзетулер
тарихтың алдында көрсетіледі. Тағы да файл түзетулерін қалпына келтіргенде тиымдары жойылатын ескеріңіз.',
'undeleterevdel' => 'Егер бұл үстіңгі бетте аяқталса, не файл түзетуі жарым-жартылай жойылған болса, жою болдырмауы орындалмайды.
Осындай жағдайларда, ең жаңа жойылған түзетуін алып тастауыңыз не жасыруын болдырмауыңыз жөн.',
'undeletehistorynoadmin' => 'Бұл бет жойылған.
Жою себебі алдындағы өңдеген қатысушылар егжей-тегжейлерімен бірге төмендегі қысқаша мазмұндамасында көрсетілген.
Мына жойылған түзетулерін көкейкесті мәтіні тек әкімшілерге жетімді.',
'undelete-revision' => '$4,  $5  кезіндегі $3 жойған $1 дегеннің жойылған түзетуі:',
'undeleterevision-missing' => 'Жарамсыз не жоғалған түзету.
Сілтемеңіз жарамсыз, не түзету қалпына келтірілген, немесе мұрағаттан аласталған болуы мүмкін.',
'undelete-nodiff' => 'Еш алдыңғы түзету табылмады.',
'undeletebtn' => 'Қалпына келтір!',
'undeletelink' => 'қарау/қалпына келтіру',
'undeleteviewlink' => 'қарау',
'undeletereset' => 'Қайта қой',
'undeleteinvert' => 'Таңдалғанды алмастыру',
'undeletecomment' => 'Себебі:',
'undeletedrevisions' => '{{PLURAL:$1|1 түзету|$1 түзету}} қалпына келтірілді',
'undeletedrevisions-files' => '{{PLURAL:$1|1 түзету|$1 түзету}} және {{PLURAL:$2|1 файл|$2 файл}} қалпына келтірілді',
'undeletedfiles' => '{{PLURAL:$1|1 файл|$1 файл}} қалпына келтірілді',
'cannotundelete' => 'Жою болдырмауы сәтсіз бітті;
$1',
'undeletedpage' => "'''$1 дегенді қалпына келтірді'''

Жуықтағы жоюлар мен қалпына келтірулер жөнінде  [[Special:Log/delete|жою журналын]] қараңыз.",
'undelete-header' => 'Жуықтағы жойылған беттер жөнінде  [[Special:Log/delete|жою журналын]] қараңыз.',
'undelete-search-title' => 'Жойылған беттерді іздеу',
'undelete-search-box' => 'Жойылған беттерді іздеу',
'undelete-search-prefix' => 'Мынадан басталған беттерді көрсет:',
'undelete-search-submit' => 'Іздеу',
'undelete-no-results' => 'Жою мұрағатында ешқандай сәйкес беттер табылмады.',
'undelete-filename-mismatch' => '$1 кезіндегі файл түзетуінің жоюы болдырмады: файл атауы сәйкессіз',
'undelete-bad-store-key' => '$1 кезіндегі файл түзетуінің жоюы болдырмады: жоюдың алдынан файл жоқ болған.',
'undelete-cleanup-error' => '«$1» пайдаланылмаған мұрағатталған файл жою қатесі.',
'undelete-missing-filearchive' => 'Мұрағатталған файл (нөмірі $1) қалпына келтіруі икемді емес, себебі ол дерекқорда жоқ.
Бұның жоюын болдырмауы алдақашан болғаны мүмкін.',
'undelete-error-short' => 'Файл жоюын болдырмау қатесі: $1',
'undelete-error-long' => 'Файл жоюын болдырмау кезінде мына қателер кездесті:

$1',
'undelete-show-file-submit' => 'Иә',

# Namespace form on various pages
'namespace' => 'Есім кеңістігі:',
'invert' => 'Таңдалғанды жасыру',
'namespace_association' => 'Қатысты есім аясы',
'blanknamespace' => 'Негізгі беттерден',

# Contributions
'contributions' => '{{GENDER:$1|Қатысушы}} үлестері',
'contributions-title' => '$1 есімді қатысушының үлесі',
'mycontris' => 'Үлестер',
'contribsub2' => '$1 ($2) үлесі',
'nocontribs' => 'Осы іздеу шартына сәйкес өзгерістер табылған жоқ.',
'uctop' => '(ағымдағы)',
'month' => 'Мына айдан (және ертеректен):',
'year' => 'Мына жылдан (және ертеректен):',

'sp-contributions-newbies' => 'Тек жаңа тіркелгіден жасаған үлестерді көрсет',
'sp-contributions-newbies-sub' => 'Жаңа тіркелгендер үшін',
'sp-contributions-newbies-title' => 'Жаңа тіркелгендер үшін қатысушы үлестері',
'sp-contributions-blocklog' => 'Бұғаттау журналы',
'sp-contributions-deleted' => 'Қатысушының жойылған үлестері',
'sp-contributions-uploads' => 'жүктеулері',
'sp-contributions-logs' => 'журналдары',
'sp-contributions-talk' => 'талқылауы',
'sp-contributions-userrights' => 'Қатысушы құқықтарын реттеу',
'sp-contributions-blocked-notice' => 'Бұл қатысушы қазіргі уақытта  бұғатталған.
Төменде бұғаттау журналындағы соңғы жазбалар көрсетілген:',
'sp-contributions-blocked-notice-anon' => 'Бұл IP мекен-жайы қазіргі уақытта  бұғатталған.
Төменде бұғаттау журналындағы соңғы жазбалар көрсетілген.',
'sp-contributions-search' => 'Үлес үшін іздеу',
'sp-contributions-username' => 'IP-мекенжайы немесе қатысушы аты:',
'sp-contributions-toponly' => 'Өңдемелердің тек соңғы нұсқаларын көрсету',
'sp-contributions-submit' => 'Ізде',

# What links here
'whatlinkshere' => 'Мұнда сілтейтін беттер',
'whatlinkshere-title' => '$1 дегенге сілтейтін беттер',
'whatlinkshere-page' => 'Бет:',
'linkshere' => "'''[[:$1]]''' дегенге мына беттер сілтейді:",
'nolinkshere' => "'''[[:$1]]''' дегенге еш бет сілтемейді.",
'nolinkshere-ns' => "Таңдалған есім кеңістігінде '''[[:$1]]''' дегенге ешқандай бет сілтемейді.",
'isredirect' => 'айдату беті',
'istemplate' => 'кірікбет',
'isimage' => 'файл сілтемесі',
'whatlinkshere-prev' => '{{PLURAL:$1|алдыңғы|алдыңғы $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|келесі|келесі $1}}',
'whatlinkshere-links' => '← сілтемелер',
'whatlinkshere-hideredirs' => 'айдағыштарды $1',
'whatlinkshere-hidetrans' => 'кірікбеттерді $1',
'whatlinkshere-hidelinks' => 'сілтемелерді $1',
'whatlinkshere-hideimages' => 'файл сілтемелерін $1',
'whatlinkshere-filters' => 'Сүзгілер',

# Block/unblock
'autoblockid' => '#$1 өздікбұғаттауы',
'block' => 'Қатысушыны бұғаттау',
'unblock' => 'Қатысушыны бұғаттамау',
'blockip' => 'Қатысушыны бұғаттау',
'blockip-title' => 'Қатысушыны бұғаттау',
'blockip-legend' => 'Қатысушыны бұғаттау',
'blockiptext' => 'Төмендегі пішін қатысушының жазу рұқсатын белгілі IP мекенжайымен не атымен бұғаттау үшін қолданылады.
Бұны тек бұзақылықты қақпайлау үшін және де [[{{{{ns:mediawiki}}:Policy-url}}|ережелер]] бойынша атқаруыңыз жөн.
Төменде тиісті себебін толтырып көрсетіңіз (мысалы, дәйекке бұзақылықпен өзгерткен беттерді келтіріп).',
'ipadressorusername' => 'IP-мекенжайы немесе қатысушы аты:',
'ipbexpiry' => 'Мерзімі бітпек:',
'ipbreason' => 'Себебі:',
'ipbreasonotherlist' => 'Басқа себеп',
'ipbreason-dropdown' => '* Бұғаттаудың жалпы себебтері
** Жалған мәлімет енгізу
** Беттердегі мағлұматты аластау
** Сыртқы сайттар сілтемелерін жаудыру
** Беттерге мағынасыздық/балдырлау кірістіру
** Қоқандау/қуғындау мінезқұлық
** Бірнеше рет тіркеліп қиянаттау
** Өрескел қатысушы аты',
'ipb-hardblock' => 'Бұл IP мекен-жайы арқылы тіркелген қатысушылардың өңдеуіне кедергі жасау',
'ipbcreateaccount' => 'Тіркелуді қақпайлау',
'ipbemailban' => 'Қатысушы е-поштамен хат жөнелтуін қақпайлау',
'ipbenableautoblock' => 'Бұл қатысушы соңғы қолданған IP мекенжайын, және кейін өңдеуге байқап көрген әр IP мекенжайларын өзбұғаттауы',
'ipbsubmit' => 'Бұл қатысушыны бұғаттау',
'ipbother' => 'Басқа мерзімі:',
'ipboptions' => '2 сағат:2 hours,1 күн:1 day,3 күн:3 days,1 апта:1 week,2 апта:2 weeks,1 ай:1 month,3 ай:3 months,6 ай:6 months,1 жыл:1 year,мәңгі:infinite',
'ipbotheroption' => 'басқа',
'ipbotherreason' => 'Басқа/қосымша себеп:',
'ipbhidename' => 'Қатысушы атын өңдемелерден және тізімдерден жасыру',
'ipbwatchuser' => 'Бұл қатысушының жеке және талқылау беттерін бақылау',
'ipb-disableusertalk' => 'Бұл қатысушыны бұғатталған кезде өзінің талқылау бетінін өңдеуіне  кедергі жасау',
'ipb-confirm' => 'Бұғаттауды құптау',
'badipaddress' => 'IP мекенжайы жарамсыз.',
'blockipsuccesssub' => 'Бұғаттау сәтті өтті',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] деген бұғатталған.<br />
Бұғаттарды шолып шығу үшін [[Special:BlockList|бұғаттау тізімін]] қараңыз.',
'ipb-blockingself' => 'Сіз өзіңізді бұғаттамақшысыз. Бұны істегіңіз келгеніне сенімдісіз бе?',
'ipb-edit-dropdown' => 'Бұғаттау себептерін өңдеу',
'ipb-unblock-addr' => '$1 дегенді бұғаттамау',
'ipb-unblock' => 'Қатысушы атын немесе IP мекенжайын бұғаттамау',
'ipb-blocklist' => 'Бар бұғаттауларды қарау',
'ipb-blocklist-contribs' => '$1 есімді қатысушының үлесі',
'unblockip' => 'Қатысушыны бұғаттамау',
'unblockiptext' => 'Төмендегі пішінді алдындағы IP мекенжайымен не атымен бұғатталған қатысушыға жазу қатынауын қалпына келтіріуі үшін қолданыңыз.',
'ipusubmit' => 'Осы бұғаттауды алып тастау',
'unblocked' => '[[User:$1|$1]] бұғаттауы өшірілді',
'unblocked-range' => '$1  бұғаттауы өшірілді',
'unblocked-id' => '$1 бұғаттауы аласталды.',
'blocklist' => 'Бұғатталған қатысушылар',
'ipblocklist' => 'Бұғатталған қатысушылар',
'ipblocklist-legend' => 'Бұғатталған қатысушыны табу',
'blocklist-userblocks' => 'Тіркелгендер бұғаттауын жасыру',
'blocklist-tempblocks' => 'Уақытша бұғаттауларды жасыру',
'blocklist-addressblocks' => 'Жалғыз IP бұғаттауларын жасыру',
'blocklist-rangeblocks' => 'Аралық бұғаттауларды жасыру',
'blocklist-timestamp' => 'Уақыт белгісі',
'blocklist-target' => 'Нысана',
'blocklist-expiry' => 'Біту мерзімі',
'blocklist-by' => 'Әкімшіні бұғаттау',
'blocklist-params' => 'Бұғаттау бапталымдары',
'blocklist-reason' => 'Себебі',
'ipblocklist-submit' => 'Ізде',
'ipblocklist-localblock' => 'Жергілікті бұғаттауы',
'ipblocklist-otherblocks' => 'Басқа {{PLURAL:$1|бұғаттауы|бұғаттаулары}}',
'infiniteblock' => 'мәңгі',
'expiringblock' => 'мерзімі бітпек: $1 $2',
'anononlyblock' => 'тек тіркелгісіздерді',
'noautoblockblock' => 'өзбұғаттау өшірілген',
'createaccountblock' => 'тіркелу бұғатталған',
'emailblock' => 'е-пошта өшірілді',
'blocklist-nousertalk' => 'талқылау бетіңізді өңдемеңіз',
'ipblocklist-empty' => 'Бұғаттау тізімі бос.',
'ipblocklist-no-results' => 'Сұратылған IP мекенжай не қатысушы аты бұғатталған емес.',
'blocklink' => 'бұғаттау',
'unblocklink' => 'бұғаттамау',
'change-blocklink' => 'Бұғаттауын өзгерту',
'contribslink' => 'үлесі',
'emaillink' => 'хат жіберу',
'autoblocker' => 'IP мекенжайыңызды жуықта "[[User:$1|$1]]" пайдаланған, сондықтан өзбұғатталған.
$1 бұғаттауы үшін келтірілген себебі: «$2».',
'blocklogpage' => 'Бұғаттау журналы',
'blocklogentry' => '[[$1]] дегенді $2 мерзімге бұғаттады $3',
'blocklogtext' => 'Бұл қатысушыларды бұғаттау/бұғаттамау әрекеттерінің журналы.
Өздіктік бұғатталған IP мекенжайлар осында тізімделгемеген.
Ағымдағы белсенді тиымдар мен бұғаттауларды [[Special:BlockList|IP бұғаттау тізімінен]] қараңыз.',
'unblocklogentry' => '«$1» — бұғаттауын өшірді',
'block-log-flags-anononly' => 'тек тіркелгісіздер',
'block-log-flags-nocreate' => 'тіркелу өшірілген',
'block-log-flags-noautoblock' => 'өзбұғаттау өшірілген',
'block-log-flags-noemail' => 'е-пошта бұғатталған',
'block-log-flags-nousertalk' => 'талқылау бетін өңдемеңіз',
'block-log-flags-hiddenname' => 'қатысушы есімі жасырылды',
'range_block_disabled' => 'Ауқым бұғаттауларын жасау әкімшілік мүмкіндігі өшірілген.',
'ipb_expiry_invalid' => 'Бітетін уақыты жарамсыз.',
'ipb_expiry_temp' => 'Жасырылған қатысушы атын бұғаттауы мәңгі болуы жөн.',
'ipb_already_blocked' => '«$1» әлдеқашан бұғатталған',
'ipb-needreblock' => '$1 әлдеқашан бұғатталған. Бұғаттау параметрлерін өзгертесіз бе?',
'ipb-otherblocks-header' => 'Басқа {{PLURAL:$1|бұғаттау|бұғаттаулар}}',
'ipb_cant_unblock' => 'Қателік: IP $1 бұғаттауы табылмады. Оның бұғаттауы алдақашан өшірлген мүмкін.',
'ipb_blocked_as_range' => 'Қателік: IP $1 тікелей бұғатталмаған және бұғаттауы өшірілмейді.
Бірақ, бұл бұғаттауы өшірілуі мүмкін $2 ауқымы бөлігі боп бұғатталған.',
'ip_range_invalid' => 'IP мекенжай ауқымы жарамсыз.',
'proxyblocker' => 'Прокси серверлерді бұғаттауыш',
'proxyblockreason' => 'IP мекенжайыңыз ашық прокси серверге жататындықтан бұғатталған.
Интернет қызметін жабдықтаушыңызбен, не техникалық қолдау қызметімен қатынасыңыз, және оларға осы оте күрделі қауыпсіздік шатақ туралы ақпарат беріңіз.',
'sorbsreason' => 'IP мекенжайыңыз {{SITENAME}} торабында қолданылған DNSBL қара тізіміндегі ашық прокси-сервер деп табылады.',
'sorbs_create_account_reason' => 'IP мекенжайыңыз {{SITENAME}} торабында қолданылған DNSBL қара тізіміндегі ашық прокси-сервер деп табылады.
Жаңа тіркелгі жасай алмайсыз.',
'ipbnounblockself' => 'Өзіңіздің бұғаттауыңызды алып тастау рұқсат етілмеген',

# Developer tools
'lockdb' => 'Дерекқорды құлыптау',
'unlockdb' => 'Дерекқорды құлыптамау',
'lockdbtext' => 'Дерекқордын құлыпталуы барлық қатысушылардың бет өңдеу, баптауын қалау, бақылау тізімін, тағы басқа дерекқорды өзгертетін мүмкіндіктерін тоқтата тұрады.
Осы мақсатыңызды, және баптау біткенде дерекқорды ашатыңызды құптаңыз.',
'unlockdbtext' => 'Дерекқодын ашылуы барлық қатысушылардың бет өңдеу, баптауын қалау, бақылау тізімін, тағы басқа дерекқорды өзгертетін мүмкіндіктерін қалпына келтіреді.
Осы мақсатыңызды құптаңыз.',
'lockconfirm' => 'Иә, мен шыныменде дерекқордың құлыпталуын тілеймін.',
'unlockconfirm' => 'Иә, мен шыныменде дерекқордың құлыпталуын өшіргім келеді.',
'lockbtn' => 'Дерекқорды құлыпта',
'unlockbtn' => 'Дерекқорды құлыптама',
'locknoconfirm' => 'Құптау көзіне құсбелгі салмағансыз.',
'lockdbsuccesssub' => 'Дерекқор құлыптауы сәтті өтті',
'unlockdbsuccesssub' => 'Дерекқор құлыптауы аласталды',
'lockdbsuccesstext' => 'Дерекқор құлыпталды.<br />
Баптау толық өткізілгеннен кейін [[{{#special:Unlockdb}}|құлыптауын аластауға]] ұмытпаңыз.',
'unlockdbsuccesstext' => 'Құлыпталған дерекқор сәтті ашылды.',
'lockfilenotwritable' => 'Дерекқор құлыптау файлы жазылмайды.
Дерекқорды құлыптау не ашу үшін, веб-сервер файлға жазу рұқсаты болу керек.',
'databasenotlocked' => 'Дерекқор құлыпталған жоқ.',

# Move page
'move-page' => '«$1» дегенді жылжыту',
'move-page-legend' => 'Бетті жылжыту',
'movepagetext' => "Бетті бүкіл тарихымен қоса жаңа атауға жылжытқалы жатырсыз.
Беттің бұрыңғы атауы жаңа бетке айдағыш сілтеме ретінде қалады.
Қаласаңыз, бұрыңғы атауды мегзейтін сілтемелердің автоматты түрде жаңартылуын таңдай аласыз. Бұны таңдамаған жағдайда, [[Special:DoubleRedirects|екі мәрте айдағыш]] не [[Special:BrokenRedirects|сынық айдағыш]] сілтемелер қалып қоймауына көз жеткізіңіз.
Жылжытудан кейін әр сілтеме өзіне тиісті бетке мегзейтініне жауапты боласыз.

Егер жылжытайын деп жатқан жаңа атау басқа бетке әлдеқашан берілген болса, жылжыту '''орындалмайды'''. Бұл шара әлдеқашан бар беттің қайта жазылуынан сақтайды. Алайда, егер бет —   бос бет, не өткен тарихы жоқ [[Special:ListRedirects|айдағыш бет]] болса, жылжыту орындалады. Бұл жаңылыс жылжытылған бетті бұрыңғы атауына қайтаруды мүмкін ету үшін жасалған.


'''Ескерту!'''
Бұл көп қаралатын бетке тиісті өзгеріс болуы мүмкін;
ілгері басудан бұрын әрекетіңіздің салдарын есепке алуыңызды сұраймыз.",
'movepagetext-noredirectfixer' => "Бетті бүкіл тарихымен қоса жаңа атауға жылжытқалы жатырсыз.
Беттің бұрыңғы атауы жаңа бетке айдағыш сілтеме ретінде қалады.
[[Special:DoubleRedirects|Екі мәрте айдағыш]] не [[Special:BrokenRedirects|сынық айдағыш]] сілтемелер қалып қоймауына көз жеткізіңіз.
Жылжытудан кейін әр сілтеме өзіне тиісті бетке мегзейтініне жауапты боласыз.

Егер жылжытайын деп жатқан жаңа атау басқа бетке әлдеқашан берілген болса, жылжыту '''орындалмайды'''. Бұл шара әлдеқашан бар беттің қайта жазылуынан сақтайды. Алайда, егер бет —   бос бет, не өткен тарихы жоқ [[Special:ListRedirects|айдағыш бет]] болса, жылжыту орындалады. Бұл жаңылыс жылжытылған бетті бұрыңғы атауына қайтаруды мүмкін ету үшін жасалған.


'''Ескерту!'''
Бұл көп қаралатын бетке тиісті өзгеріс болуы мүмкін;
ілгері басудан бұрын әрекетіңіздің салдарын есепке алуыңызды сұраймыз.",
'movepagetalktext' => "Келесі жағдай орын алса, қатысты талқылау беті '''жылжытылмайды''':
*жаңа атаумен аталатын беттің талқылау беті әлдеқашан бар болған кезде (бұл жағдайда талқылауын қолмен көшіруге болады, бірақ түйіндемесіне қай беттен көшірілгендігін міндетті түрде жазыңыз)
*төмендегі қорапшадан құсбелгі алынып тасталғанда.

Ал мақаланың атауын өзгертем деп мағлұматын қолмен көшіруге болмайды, себебі беттің түзету тарихын өшіреді.",
'movearticle' => 'Ағымдағы бет атауы:',
'movenologin' => 'Сіз жүйеге кірмегенсіз',
'movenologintext' => 'Бетті жылжыту үшін тіркелген болуыңыз және [[{{#special:UserLogin}}|кіруіңіз]] жөн.',
'movenotallowed' => '{{SITENAME}} жобасында беттерді жылжытуға рұқсатыңыз жоқ.',
'newtitle' => 'Жаңа бет атауы:',
'move-watch' => 'Бұл бетті бақылау',
'movepagebtn' => 'Бетті жылжыту',
'pagemovedsub' => 'Жылжыту сәтті аяқталды',
'movepage-moved' => '\'\'\'"$1" беті "$2" бетіне жылжытылды\'\'\'',
'movepage-moved-redirect' => 'Айдатқыш жасалды.',
'articleexists' => 'Осылай аталған бет алдақашан бар, не таңдаған атауыңыз жарамды емес.
Өзге атауды таңдаңыз.',
'cantmove-titleprotected' => 'Бетті осы орынға жылжыта алмайсыз, себебі жаңа тақырып аты бастаудан қорғалған',
'talkexists' => "'''Беттің өзі сәтті жылжытылды, бірақ талқылау беті бірге жылжытылмады, оның себебі жаңа тақырып атында біреуі алдақашан бар.
Бұны қолмен қосыңыз.'''",
'movedto' => 'мынаған жылжытылды:',
'movetalk' => 'Қатысты талқылау бетін де жылжыту',
'move-subpages' => 'Барлық бетшелерін жылжыту ($1 дейін)',
'move-talk-subpages' => 'Талқылау бетінің барлық бетшелерін жылжыту ($1 дегенге)',
'movepage-page-exists' => '$1 деген бет алдақашан бар және үстіне өздіктік жазылмайды.',
'movepage-page-moved' => '$1 деген бет $2 дегенге жылжытылды.',
'movepage-page-unmoved' => '$1 деген бет $2 дегенге жылжытылмайды.',
'movepage-max-pages' => 'Барынша $1 бет жылжытылды да мыннан көбі өздіктік жылжылтылмайды.',
'movelogpage' => 'Жылжыту журналы',
'movelogpagetext' => 'Төменде жылжытылған беттердің тізімі беріліп тұр.',
'movesubpage' => '{{PLURAL:$1|төменгі беті|төменгі беттері}}',
'movesubpagetext' => 'Төменде бұл беттің $1 {{PLURAL:$1|төменгі беті|төменгі беттері}} көрсетілген.',
'movenosubpage' => 'Бұл бетте төменгі беттері жоқ.',
'movereason' => 'Жылжытудың себебі:',
'revertmove' => 'қайтару',
'delete_and_move' => 'Жою және жылжыту',
'delete_and_move_text' => '==Жою керек==
"[[:$1]]" деген нысана бет алдақашан бар.
Жылжытуға жол беру үшін бұны жоясыз ба?',
'delete_and_move_confirm' => 'Иә, бұл бетті жой',
'delete_and_move_reason' => '"[[$1]]" дегеннен жылжытуға жол беру үшін жойылған',
'selfmove' => 'Қайнар және нысана тақырып аттары бірдей;
бет өзінің үстіне жылжытылмайды.',
'immobile-source-namespace' => '"$1" есім кеңістігіндегі беттер жылжытылмайды',
'immobile-target-namespace' => '"$1" есім кеңістігіне беттерді жылдытылмайды',
'immobile-source-page' => 'Бұл бет жылжытылмайды.',
'imagenocrossnamespace' => 'Файл емес есім кеңістігіне файл жылжытылмайды',
'nonfile-cannot-move-to-file' => 'Файл емес есім кеңістігінен файл есім кеңістігіне жылжытылмайды',
'imagetypemismatch' => 'Файлдың жаңа кеңейтімі бұның түріне сәйкес емес',
'imageinvalidfilename' => 'Файл атауы жарамсыз',
'move-leave-redirect' => 'Ескі бетте айдату сілтемесін қалдыру',

# Export
'export' => 'Беттерді сыртқа беру',
'exporttext' => 'XML пішіміне қапталған бөлек бет не беттер бумасы мәтінің және өңдеу тарихын сыртқа бере аласыз.
MediaWiki жүйесінің [[{{#special:Import}}|сырттан алу бетін]] пайдаланып, бұны өзге уикиге алуға болады.

Беттерді сыртқа беру үшін, тақырып аттарын төмендегі мәтін жолағына енгізіңіз (жол сайын бір тақырып аты), және де бөлектеңіз: не ағымдық нұсқасын, барлық ескі нұсқалары мен және тарихы жолдары мен бірге, немесе дәл ағымдық нұсқасын, соңғы өңдемеу туралы ақпараты мен бірге.

Соңғы жағдайда сілтемені де, мысалы «{{{{ns:mediawiki}}:Mainpage}}» беті үшін [[{{#special:Export}}/{{MediaWiki:Mainpage}}]] қолдануға болады.',
'exportall' => 'Барлық беттерді сыртқа беру',
'exportcuronly' => 'Толық тарихын емес, тек ағымдық түзетуін кірістіріңіз',
'exportnohistory' => "----
'''Аңғартпа:''' Өнімділік әсері себептерінен, беттердің толық тарихын бұл пішінмен сыртқа беруі өшірілген.",
'export-submit' => 'Сыртқа бер',
'export-addcattext' => 'Мына санаттағы беттерді үстеу:',
'export-addcat' => 'Үсте',
'export-addnstext' => 'Келесі есім кеңістігінен беттерді қос:',
'export-addns' => 'Қосу',
'export-download' => 'Файл түрінде сақтау',
'export-templates' => 'Үлгілерді қоса алып',

# Namespace 8 related
'allmessages' => 'Жүйе хабарлары',
'allmessagesname' => 'Атауы',
'allmessagesdefault' => 'Әдепкі мәтіні',
'allmessagescurrent' => 'Ағымдық мәтіні',
'allmessagestext' => 'Мында {{ns:mediawiki}} есім аясында жетімді жүйе хабар тізімі беріледі.
Егер әмбебап MediaWiki жерсіндіруге үлес қосқыңыз келсе [//www.mediawiki.org/wiki/Localisation MediaWiki жерсіндіру бетіне] және [//translatewiki.net translatewiki.net жобасына] барып шығыңыз.',
'allmessagesnotsupportedDB' => "'''\$wgUseDatabaseMessages''' өшірілген себебінен '''{{#special:AllMessages}}''' беті қолданылмайды.",
'allmessages-filter-legend' => 'Сүзгі',
'allmessages-filter-unmodified' => 'Өзгертілмегендер',
'allmessages-filter-all' => 'Барлығы',
'allmessages-filter-modified' => 'Өзгертілгендер',
'allmessages-language' => 'Тілі:',
'allmessages-filter-submit' => 'Өту',

# Thumbnails
'thumbnail-more' => 'Үлкейту',
'filemissing' => 'Жоғалған файл',
'thumbnail_error' => 'Нобай құру қатесі: $1',
'thumbnail_error_remote' => '$1 дегеннен хабарлама қатесі:
$2',
'djvu_page_error' => 'DjVu беті аумақ сыртындда',
'djvu_no_xml' => 'DjVu файлы үшін XML келтіруі икемді емес',
'thumbnail_invalid_params' => 'Нобайдың бапталымдары жарамсыз',
'thumbnail_dest_directory' => 'Нысана қалтасы құруы икемді емес',

# Special:Import
'import' => 'Беттерді сырттан алу',
'importinterwiki' => 'Уики-апару үшін сырттан алу',
'import-interwiki-text' => 'Сырттан алынатын уикиді және беттің тақырып атын бөлектеңіз.
Түзету күн-айы және өңдеуші есімдері сақталады.
Уики-апару үшін сырттан алу барлық әрекеттер [[{{#special:Log}}/import|сырттан алу журналына]] жазылып алынады.',
'import-interwiki-source' => 'Қайнар уики/бет:',
'import-interwiki-history' => 'Бұл беттің барлық тарихи нұсқаларын көшіру',
'import-interwiki-templates' => 'Кірістірілген барлық үлгілер',
'import-interwiki-submit' => 'Сырттан алу',
'import-interwiki-namespace' => 'Беттерді мына есім кеңістігіне апару:',
'import-upload-filename' => 'Файл атауы:',
'import-comment' => 'Пікірі:',
'importtext' => 'Қайнар уикиден [[Special:Export|export utility]]  қолданып файлды сыртқа беріңіз.
Комьпютеріңізге сақтаңыз да мында жүктеп беріңіз.',
'importstart' => 'Беттерді сырттан алуда…',
'import-revision-count' => '$1 түзету',
'importnopages' => 'Сырттан алынатын беттер жоқ.',
'importfailed' => 'Сырттан алу сәтсіз бітті: <nowiki>$1</nowiki>',
'importunknownsource' => 'Cырттан алынатын қайнар түрі белгісіз',
'importcantopen' => 'Сырттан алынатын файл ашылмайды',
'importbadinterwiki' => 'Жарамсыз уики-аралық сілтеме',
'importnotext' => 'Бұл бос, немесе мәтіні жоқ',
'importsuccess' => 'Сырттан алу аяқталды!',
'importhistoryconflict' => 'Тарихында қақтығысты түзету бар (бұл бет алдында сырттан алынған сияқты)',
'importnosources' => 'Уики-апару үшін сырттан алынатын еш қайнар көзі анықталмаған, және тарихын тікелей қотарып беруі өшірілген.',
'importnofile' => 'Сырттан алынған файл жүктелген жоқ.',
'importuploaderrorsize' => 'Сырттан алынған файлдың жүктелуі сәтсіз өтті. Файл мөлшері рұқсат етілгеннен мөлшерден асады.',
'importuploaderrorpartial' => 'Сырттан алынған файлдың жүктелуі сәтсіз өтті. Осы файлдың тек бөліктері жүктелді.',
'importuploaderrortemp' => 'Сырттан алынған файлдың жүктелуі сәтсіз өтті. Уақытша қалта табылмады.',
'import-parse-failure' => 'Сырттан алынған XML файл құрылымын талдатқанда сәтсіздік болды',
'import-noarticle' => 'Сырттан алынатын еш бет жоқ!',
'import-nonewrevisions' => 'Барлық түзетулері алдында сырттан алынған.',
'xml-error-string' => '$1 нөмір $2 жолда, баған $3 (байт $4): $5',
'import-upload' => 'XML деректерін жүктеу',
'import-options-wrong' => 'Қате {{PLURAL:$2|параметр|параметр}}: <nowiki>$1</nowiki>',

# Import log
'importlogpage' => 'Сырттан алу журналы',
'importlogpagetext' => 'Беттерді түзету тарихымен бірге сыртқы уикилерден әкімші ретінде алу.',
'import-logentry-upload' => '«[[$1]]» дегенді файл жүктеу арқылы сырттан алды',
'import-logentry-upload-detail' => '$1 түзету',
'import-logentry-interwiki' => 'уики-апарылған $1',
'import-logentry-interwiki-detail' => '$2 дегеннен $1 түзету',

# JavaScriptTest
'javascripttest' => 'JavaScript сынақталуда',
'javascripttest-qunit-intro' => '[$1 сынақтау құжаттамасын]  mediawiki.org-тен қара.',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Жеке бетіңіз',
'tooltip-pt-anonuserpage' => 'Бұл IP мекенжайдың жеке беті',
'tooltip-pt-mytalk' => 'Талқылау бетіңіз',
'tooltip-pt-anontalk' => 'Бұл IP мекенжай өңдемелерін талқылау',
'tooltip-pt-preferences' => 'Бапталымдарым',
'tooltip-pt-watchlist' => 'Өзгерістерін бақылап тұрған беттер тізімім.',
'tooltip-pt-mycontris' => 'Өңдеулеріңіздің тізімі',
'tooltip-pt-login' => 'Кіруіңізді ұсынамыз, ол міндетті емес.',
'tooltip-pt-anonlogin' => 'Кіруіңізді ұсынамыз, бірақ, ол міндетті емес.',
'tooltip-pt-logout' => 'Шығу',
'tooltip-ca-talk' => 'Мағлұмат бетті талқылау',
'tooltip-ca-edit' => 'Бұл бетті өңдей аласыз. Сақтаудың алдында «Қарап шығу» батырмасын нұқыңыз.',
'tooltip-ca-addsection' => 'Жаңа бөлім бастау.',
'tooltip-ca-viewsource' => 'Бұл бет қорғалған. Қайнарын қарай аласыз.',
'tooltip-ca-history' => 'Бұл беттін соңғы нұсқалары.',
'tooltip-ca-protect' => 'Бұл бетті қорғау',
'tooltip-ca-unprotect' => 'Бұл беттің қорғалуын өзгерту',
'tooltip-ca-delete' => 'Бұл бетті жою',
'tooltip-ca-undelete' => 'Бұл беттің жоюдың алдындағы болған өңдемелерін қалпына келтіру',
'tooltip-ca-move' => 'Бұл бетті жылжыту',
'tooltip-ca-watch' => 'Бұл бетті бақылау тізіміңізге үстеу',
'tooltip-ca-unwatch' => 'Бұл бетті бақылау тізіміңізден аластау',
'tooltip-search' => '{{SITENAME}} жобасында іздеу',
'tooltip-search-go' => 'Егер дәл осы атауымен болса бетке өтіп кету',
'tooltip-search-fulltext' => 'Осы мәтіні бар бетті іздеу',
'tooltip-p-logo' => 'Басты бетке',
'tooltip-n-mainpage' => 'Басты бетке келіп-кетіңіз',
'tooltip-n-mainpage-description' => 'Басты бетке',
'tooltip-n-portal' => 'Жоба туралы, не істеуіңізге болатын, қайдан табуға болатын туралы',
'tooltip-n-currentevents' => 'Ағымдағы оқиғаларға қатысты өң ақпаратын табу',
'tooltip-n-recentchanges' => 'Осы уикидегі жуықтағы өзгерістер тізімі.',
'tooltip-n-randompage' => 'Кездейсоқ бетті жүктеу',
'tooltip-n-help' => 'Анықтама табу орны.',
'tooltip-t-whatlinkshere' => 'Мұнда сілтейтін барлық бет тізімі',
'tooltip-t-recentchangeslinked' => 'Мыннан сілтенген беттердің жуықтағы өзгерістері',
'tooltip-feed-rss' => 'Бұл беттің RSS арнасы',
'tooltip-feed-atom' => 'Бұл беттің Atom арнасы',
'tooltip-t-contributions' => 'Осы қатысушының үлестерінің тізімі',
'tooltip-t-emailuser' => 'Осы қатысушыға хат жөнелту',
'tooltip-t-upload' => 'Файлдарды жүктеу',
'tooltip-t-specialpages' => 'Барлық арнайы беттер тізімі',
'tooltip-t-print' => 'Бұл беттің басып шығарышқа арналған нұсқасы',
'tooltip-t-permalink' => 'Мына беттің осы нұсқасының тұрақты сілтемесі',
'tooltip-ca-nstab-main' => 'Мағлұмат бетін қарау',
'tooltip-ca-nstab-user' => 'Қатысушы бетін қарау',
'tooltip-ca-nstab-media' => 'Медиа бетін қарау',
'tooltip-ca-nstab-special' => 'Бұл арнайы бет, беттің өзі өңделінбейді.',
'tooltip-ca-nstab-project' => 'Жоба бетін қарау',
'tooltip-ca-nstab-image' => 'Файл бетін қарау',
'tooltip-ca-nstab-mediawiki' => 'Жүйе хабарын қарау',
'tooltip-ca-nstab-template' => 'Үлгіні қарау',
'tooltip-ca-nstab-help' => 'Анықтыма бетін қарау',
'tooltip-ca-nstab-category' => 'Санат бетін қарау',
'tooltip-minoredit' => 'Бұны шағын өңдеме деп белгілеу',
'tooltip-save' => 'Жасаған өзгерістеріңізді сақтау',
'tooltip-preview' => 'Сақтаудың алдынан жасаған өзгерістеріңізді қарап шығыңыз!',
'tooltip-diff' => 'Мәтінге қандай өзгерістерді жасағаныңызды қарау.',
'tooltip-compareselectedversions' => 'Беттің екі бөлектенген нұсқасы айырмасын қарау.',
'tooltip-watch' => 'Бұл бетті бақылау тізіміңізге үстеу',
'tooltip-watchlistedit-normal-submit' => 'Тақырып аттарын алып тастау',
'tooltip-watchlistedit-raw-submit' => 'Бақылау тізіміңізді жаңартыңыз',
'tooltip-recreate' => 'Бет жойылғанына қарамастан қайта бастау',
'tooltip-upload' => 'Жүктеуді бастау',
'tooltip-rollback' => '"Шегіндіру" сілтемесін бір рет басу арқылы соңға редактордың барлық қатар өңдемелерін өшіру',
'tooltip-preferences-save' => 'Бапталымдарыңызды сақтау',
'tooltip-summary' => 'Қысқаша сипаттамасын жазыңыз',

# Stylesheets
'common.css' => '/* Мында орналастырылған CSS барлық мәнерлерде қолданылады */',
'cologneblue.css' => '/* Мында орналастырылған CSS тек «Көлн зеңгірлігі» (cologneblue) мәнерін пайдаланушыларына ықпал етеді skin */',
'monobook.css' => '/* Мында орналастырылған CSS тек «Дара кітап» (monobook) мәнерін пайдаланушыларына ықпал етеді */',
'modern.css' => '/* Мында орналастырылған CSS тек «Заманауи» (modern) мәнерін пайдаланушыларына ықпал етеді */',

# Scripts
'common.js' => '/* Мындағы әртүрлі JavaScript кез келген бет қотарылғанда барлық пайдаланушылар үшін жегіледі. */',
'cologneblue.js' => '/* Мындағы JavaScript тек «Көлн зеңгірлігі» (cologneblue) мәнерін пайдаланушылар үшін жегіледі */',
'monobook.js' => '/* Мындағы JavaScript тек «Дара кітап» (monobook) мәнерін пайдаланушылар үшін жегіледі */',
'modern.js' => '/* Мындағы JavaScript тек «Заманауи» (modern) мәнерін пайдаланушылар үшін жегіледі */',

# Metadata
'notacceptable' => 'Тұтынғышыңыз оқи алатын пішімі бар деректерді бұл уики сервер жетістіре алмайды.',

# Attribution
'anonymous' => '{{SITENAME}} тіркелгісіз {{PLURAL:$1|қатысушысы|қатысушылары}}',
'siteuser' => '{{SITENAME}} қатысушы $1',
'anonuser' => '{{SITENAME}} анонимді қатысушы: $1',
'lastmodifiedatby' => 'Бұл бетті $3 қатысушы соңғы өзгерткен кезі: $2, $1.',
'othercontribs' => 'Шығарма негізін $1 жазған.',
'others' => 'басқалар',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|қатысушысы|қатысушылары}} $1',
'anonusers' => '{{SITENAME}} анонимді {{PLURAL:$2|қатысушысы|қатысушылары}} $1',
'creditspage' => 'Бетті жазғандар',
'nocredits' => 'Бұл бетті жазғандар туралы ақпарат жоқ.',

# Spam protection
'spamprotectiontitle' => '«Спам»-нан қорғайтын сүзгі',
'spamprotectiontext' => 'Бұл беттің сақтауын «спам» сүзгісі бұғаттады.
Бұның себебі шеттік торап сілтемесінен болуы мүмкін.',
'spamprotectionmatch' => 'Келесі «спам» мәтіні сүзгіленген: $1',
'spambot_username' => 'MediaWiki spam cleanup',
'spam_reverting' => '$1 дегенге сілтемелері жоқ соңғы нұсқасына қайтарылды',
'spam_blanking' => '$1 дегенге сілтемелері бар барлық түзетулер тазартылды',

# Info page
'pageinfo-title' => '"$1" үшін ақпараттар',
'pageinfo-header-basic' => 'Негізгі ақпарат',
'pageinfo-header-edits' => 'Өңдеу тарихы',
'pageinfo-header-restrictions' => 'Бет қорғалуы',
'pageinfo-header-properties' => 'Бет сипаттары',
'pageinfo-display-title' => 'Бейнебет атауы',
'pageinfo-length' => 'Бет ұзындығы (байтпен)',
'pageinfo-article-id' => 'Бет ID-і',
'pageinfo-language' => 'Бет мәлімет тілі',
'pageinfo-robot-policy' => 'Индекстеуді робот жүргізеді',
'pageinfo-robot-index' => 'Рұқсат берілген',
'pageinfo-robot-noindex' => 'Рұқсат етілмеген',
'pageinfo-views' => 'Қараушылар саны',
'pageinfo-watchers' => 'Бетті қараушылар саны',
'pageinfo-few-watchers' => '$1 азырақ {{PLURAL:$1|қараушы|қараушы}}',
'pageinfo-redirects-name' => 'Бұл бетке айдатылғандар саны',
'pageinfo-subpages-name' => 'Бұл беттің төменгі беттер саны',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|айдатқыш|айдатқыш}}; $3 {{PLURAL:$3|айдатқыш емес|айдатқыш емес}})',
'pageinfo-firstuser' => 'Бетті бастаушы',
'pageinfo-firsttime' => 'Беттің басталған уақыты',
'pageinfo-lastuser' => 'Соңғы өңдеуші',
'pageinfo-lasttime' => 'Соңғы өңделген уақыты',
'pageinfo-edits' => 'Барлық өңдеме саны',
'pageinfo-authors' => 'Барлық белгілі авторлар саны',
'pageinfo-recent-edits' => 'Жуықтағы өңдеме саны (соңғы $1де)',
'pageinfo-recent-authors' => 'Жуықтағы белгілі авторлар саны',
'pageinfo-magic-words' => 'Сиқырлы {{PLURAL:$1|сөз|сөздер}} ($1)',
'pageinfo-hidden-categories' => 'Жасырылған {{PLURAL:$1|санат|санаттар}} ($1)',
'pageinfo-templates' => 'Кіріктірілген {{PLURAL:$1|үлгі|үлгілер}} ($1)',
'pageinfo-transclusions' => 'Kіріктірілген {{PLURAL:$1|бет|беттер}} ($1)',
'pageinfo-toolboxlink' => 'Бет ақпараттары',
'pageinfo-redirectsto' => 'Айдатылғандар',
'pageinfo-redirectsto-info' => 'Информация',
'pageinfo-contentpage-yes' => 'Иә',
'pageinfo-protect-cascading' => 'Баулы қорғаулылар мұнда',
'pageinfo-protect-cascading-yes' => 'Иә',
'pageinfo-category-info' => 'Санат ақпараттары',
'pageinfo-category-pages' => 'Беттер саны',
'pageinfo-category-subcats' => 'Санатшалар саны',
'pageinfo-category-files' => 'Файлдар саны',

# Skin names
'skinname-cologneblue' => 'Көк зеңгірлігі (cologneblue)',
'skinname-monobook' => 'Дара кітап (monobook)',
'skinname-modern' => 'Заманауи (modern)',

# Patrolling
'markaspatrolleddiff' => 'Зерттелді деп белгілеу',
'markaspatrolledtext' => 'Бұл бетті зерттелді деп белгіле',
'markedaspatrolled' => 'Зерттелді деп белгіленді',
'markedaspatrolledtext' => 'Бөлектенген нұсқа [[:$1]]  зерттелді деп белгіленді.',
'rcpatroldisabled' => 'Жуықтағы өзгерістерді зерттеуі өшірілген',
'rcpatroldisabledtext' => 'Жуықтағы өзгерістерді зерттеу мүмкіндігі ағымда өшірілген.',
'markedaspatrollederror' => 'Зерттелді деп белгіленбейді',
'markedaspatrollederrortext' => 'Зерттелді деп белгілеу үшін түзетуді келтіріңіз.',
'markedaspatrollederror-noautopatrol' => 'Өз жасаған өзгерістеріңізді зерттелді деп белгілей алмайсыз.',

# Patrol log
'patrol-log-page' => 'Зерттеу журналы',
'log-show-hide-patrol' => '$1 зерттеу журналы',

# Image deletion
'deletedrevision' => 'Ескі түзетуін жойды: $1',
'filedeleteerror-short' => 'Файл жою қатесі: $1',
'filedeleteerror-long' => 'Файлды жойғанда қателер кездесті:

$1',
'filedelete-missing' => '«$1» файлы жойылмайды, себебі ол жоқ.',
'filedelete-old-unregistered' => '«$1» файлдың келтірілген түзетуі дерекқорда жоқ.',
'filedelete-current-unregistered' => '«$1» файлдың келтірілген атауы дерекқорда жоқ.',
'filedelete-archive-read-only' => '«$1» мұрағат қалтасына веб-сервер жаза алмайды.',

# Browsing diffs
'previousdiff' => '← Алдыңғы өңдеме',
'nextdiff' => 'Келесі өңдеме →',

# Media information
'mediawarning' => "'''Ескерту''': Бұл файл түрінде қаскүнемді коды бар болуы ықтимал; бұны жегіп жүйеңізге зиян келтіруіңіз мүмкін.",
'imagemaxsize' => "Суреттің өлшем шектеуі:<br />''(файл сипаттама беттері үшін)''",
'thumbsize' => 'Нобай мөлшері:',
'widthheight' => '$1 × $2',
'widthheightpage' => '$1 × $2, $3 бет',
'file-info' => 'Файл мөлшері: $1, MIME түрі: $2',
'file-info-size' => '$1 × $2 нүкте, файл мөлшері: $3, MIME түрі: $4',
'file-info-size-pages' => '$1 × $2 пиксель, Файл өлшемі: $3, MIME түрі: $4, $5 {{PLURAL:$5|бет|беттер}}',
'file-nohires' => 'Жоғары кеңейтілімдегі нұсқалары жоқ.',
'svg-long-desc' => 'SVG файлы, кесімді $1 × $2 нүкте, файл мөлшері: $3',
'svg-long-desc-animated' => 'SVG қозғалысты файлы, кесімді $1 × $2 нүкте, файл өлшемі: $3',
'svg-long-error' => 'жарамсыз SVG файлы: $1',
'show-big-image' => 'Жоғары ажыратылымды',
'show-big-image-preview' => 'Бұл қарап шығудағы өлшемі: $1.',
'show-big-image-other' => 'Басқа {{PLURAL:$2|ажыратылымдық|ажыратылымдық}}: $1.',
'show-big-image-size' => '$1 × $2 нүкте',
'file-info-gif-looped' => 'тұйықталған',
'file-info-gif-frames' => '$1 {{PLURAL:$1|жақтау терезе|жақтау терезелер}}',
'file-info-png-looped' => 'тұйықталған',
'file-info-png-repeat' => '$1 {{PLURAL:$1|уақыт|уақыт}} ойнатылды',
'file-info-png-frames' => '$1 {{PLURAL:$1|жақтау терезе|жақтау терезелер}}',

# Special:NewFiles
'newimages' => 'Жаңа файлдар көрмесі',
'imagelisttext' => "Төменде $2 сұрыпталған '''$1''' файл тізімі.",
'newimages-summary' => 'Бұл арнайы бетте соңғы жүктелген файлдар көрсетіледі',
'newimages-legend' => 'Сүзгі',
'showhidebots' => '(боттарды $1)',
'noimages' => 'Көретін ештеңе жоқ.',
'ilsubmit' => 'Ізде',
'bydate' => 'күн-айымен',
'sp-newimages-showfrom' => '$2, $1 кезінен бері — жаңа суреттерді көрсет',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1, $2 × $3',
'seconds-abbrev' => '$1с',
'minutes-abbrev' => '$1мин',
'hours-abbrev' => '$1сағ',
'seconds' => '{{PLURAL:$1|$1 секунт|$1 секунт}}',
'minutes' => '{{PLURAL:$1|$1 минут|$1 минут}}',
'hours' => '{{PLURAL:$1|$1 сағат|$1 сағат}}',
'days' => '{{PLURAL:$1|$1 күн|$1 күн}}',
'weeks' => '{{PLURAL:$1|$1 апта|$1 апта}}',
'months' => '{{PLURAL:$1|$1 ай|$1 ай}}',
'years' => '{{PLURAL:$1|$1 жыл|$1 жыл}}',
'ago' => '$1 бұрын',
'just-now' => 'Дәл қазір',

# Human-readable timestamps
'hours-ago' => '$1 {{PLURAL:$1|сағат|сағат}} бұрын',
'minutes-ago' => '$1 {{PLURAL:$1|минут|минут}} бұрын',
'seconds-ago' => '$1 {{PLURAL:$1|секунт|секунт}} бұрын',

# Bad image list
'bad_image_list' => 'Пішімі төмендегідей:

Тек тізім даналары (* нышанымен басталытын жолдар) есептеледі.
Жолдың бірінші сілтемесі жарамсыз суретке сілтеу жөн.
Сол жолдағы кейінгі әрбір сілтемелер ерен болып есептеледі, мысалы жол ішіндегі кездесетін суреті бар беттер.',

# Metadata
'metadata' => 'Қосымша мәліметтер',
'metadata-help' => 'Осы файлда қосымша мәліметтер бар. Бәлкім, осы мәліметтер файлды жасап шығару, не сандылау үшін пайдаланған сандық камера, не мәтіналғырдан алынған.
Егер осы файл негізгі күйінен өзгертілген болса, кейбір ежелелері өзгертілген фотосуретке лайық болмас.',
'metadata-expand' => 'Егжей-тегжейін көрсет',
'metadata-collapse' => 'Егжей-тегжейін жасыр',
'metadata-fields' => 'Осы хабарда тізімделген EXIF қосымша мәліметтер аумақтары, сурет беті көрсету кезінде қосымша мәліметтер кесте жасырылығанда кірістірледі.
Басқалары әдепкіден жасырылады.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# Exif tags
'exif-imagewidth' => 'Ені',
'exif-imagelength' => 'Биіктігі',
'exif-bitspersample' => 'Құраш сайын бит саны',
'exif-compression' => 'Қысым сұлбасы',
'exif-photometricinterpretation' => 'Нүкте қиысуы',
'exif-orientation' => 'Мегзеуі',
'exif-samplesperpixel' => 'Құраш саны',
'exif-planarconfiguration' => 'Дерек реттеуі',
'exif-ycbcrsubsampling' => 'Y құрашының C құрашына жарнақтауы',
'exif-ycbcrpositioning' => 'Y құрашы және C құрашы мекендеуі',
'exif-xresolution' => 'Дерелей ажыратылымдығы',
'exif-yresolution' => 'Тірелей ажыратылымдығы',
'exif-stripoffsets' => 'Сурет дереректерінің жайғасуы',
'exif-rowsperstrip' => 'Белдік сайын жол саны',
'exif-stripbytecounts' => 'Қысымдалған белдік сайын байт саны',
'exif-jpeginterchangeformat' => 'JPEG SOI дегенге ығысуы',
'exif-jpeginterchangeformatlength' => 'JPEG деректерінің байт саны',
'exif-whitepoint' => 'Ақ нүкте түстілігі',
'exif-primarychromaticities' => 'Алғы шептегі түстіліктері',
'exif-ycbcrcoefficients' => 'Түс аясын тасымалдау матрицалық еселіктері',
'exif-referenceblackwhite' => 'Қара және ақ анықтауыш қос көлемдері',
'exif-datetime' => 'Файлдың өзгертілген күн-айы',
'exif-imagedescription' => 'Сурет тақырыбының аты',
'exif-make' => 'Фотоаппарат өндірушісі',
'exif-model' => 'Фотоаппарат үлгісі',
'exif-software' => 'Қолданылған бағдарламалық жасақтама',
'exif-artist' => 'Авторы',
'exif-copyright' => 'Авторлық құқықтар иесі',
'exif-exifversion' => 'Exif нұсқасы',
'exif-flashpixversion' => 'Қолданған Flashpix нұсқасы',
'exif-colorspace' => 'Түс кеңістігі',
'exif-componentsconfiguration' => 'Әрқайсы құраш мәні',
'exif-compressedbitsperpixel' => 'Сурет қысымдау тәртібі',
'exif-pixelydimension' => 'Сурет ені',
'exif-pixelxdimension' => 'Сурет биіктігі',
'exif-usercomment' => 'Қатысушы пікірі',
'exif-relatedsoundfile' => 'Қатысты дыбыс файлы',
'exif-datetimeoriginal' => 'Жасалған кезі',
'exif-datetimedigitized' => 'Сандықтау кезі',
'exif-subsectime' => 'Жасалған кезінің секунд бөлшектері',
'exif-subsectimeoriginal' => 'Түпнұсқа кезінің секунд бөлшектері',
'exif-subsectimedigitized' => 'Сандықтау кезінің секунд бөлшектері',
'exif-exposuretime' => 'Ұсталым уақыты',
'exif-exposuretime-format' => '$1 сек ($2)',
'exif-fnumber' => 'Саңылау мөлшері',
'exif-exposureprogram' => 'Ұсталым бағдарламасы',
'exif-spectralsensitivity' => 'Спектр бойынша сезгіштігі',
'exif-isospeedratings' => 'ISO жылдамдық жарнақтауы (жарық сезгіштігі)',
'exif-shutterspeedvalue' => 'Жапқыш жылдамдылығы',
'exif-aperturevalue' => 'Саңылаулық',
'exif-brightnessvalue' => 'Жарықтылық',
'exif-exposurebiasvalue' => 'Ұсталым өтемі',
'exif-maxaperturevalue' => 'Барынша саңылау ашуы',
'exif-subjectdistance' => 'Нысана қашықтығы',
'exif-meteringmode' => 'Өлшеу әдісі',
'exif-lightsource' => 'Жарық көзі',
'exif-flash' => 'Жарқылдағыш',
'exif-focallength' => 'Шоғырлау алшақтығы',
'exif-subjectarea' => 'Нысана ауқымы',
'exif-flashenergy' => 'Жарқылдағыш қарқыны',
'exif-focalplanexresolution' => 'Х бойынша шоғырлау жайпақтықтың ажыратылымдығы',
'exif-focalplaneyresolution' => 'Y бойынша шоғырлау жайпақтықтың ажыратылымдығы',
'exif-focalplaneresolutionunit' => 'Шоғырлау жайпақтықтың ажыратылымдық өлшемі',
'exif-subjectlocation' => 'Нысана орналасуы',
'exif-exposureindex' => 'Ұсталым айқындауы',
'exif-sensingmethod' => 'Сенсордің өлшеу әдісі',
'exif-filesource' => 'Файл қайнары',
'exif-scenetype' => 'Сахна түрі',
'exif-customrendered' => 'Қосымша сурет өңдетуі',
'exif-exposuremode' => 'Ұсталым тәртібі',
'exif-whitebalance' => 'Ақ түсінің тендестігі',
'exif-digitalzoomratio' => 'Сандық ауқымдау жарнақтауы',
'exif-focallengthin35mmfilm' => '35 mm таспасының шоғырлау алшақтығы',
'exif-scenecapturetype' => 'Түсірген сахна түрі',
'exif-gaincontrol' => 'Сахнаны реттеу',
'exif-contrast' => 'Ашықтық',
'exif-saturation' => 'Қанықтық',
'exif-sharpness' => 'Айқындық',
'exif-devicesettingdescription' => 'Жабдық баптау сипаттамасы',
'exif-subjectdistancerange' => 'Сахна қашықтығының көлемі',
'exif-imageuniqueid' => 'Суреттің бірегей нөмірі (ID)',
'exif-gpsversionid' => 'GPS белгішесінің нұсқасы',
'exif-gpslatituderef' => 'Солтүстік немесе оңтүстік бойлығы',
'exif-gpslatitude' => 'Бойлығы',
'exif-gpslongituderef' => 'Шығыс немесе батыс ендігі',
'exif-gpslongitude' => 'Ендігі',
'exif-gpsaltituderef' => 'Биіктік көрсетуі',
'exif-gpsaltitude' => 'Биіктік',
'exif-gpstimestamp' => 'GPS уақыты (атом сағаты)',
'exif-gpssatellites' => 'Өлшеуге пйдаланылған Жер серіктері',
'exif-gpsstatus' => 'Қабылдағыш күйі',
'exif-gpsmeasuremode' => 'Өлшеу тәртібі',
'exif-gpsdop' => 'Өлшеу дәлдігі',
'exif-gpsspeedref' => 'Жылдамдылық өлшемі',
'exif-gpsspeed' => 'GPS қабылдағыштың жылдамдылығы',
'exif-gpstrackref' => 'Қозғалыс бағытын көрсетуі',
'exif-gpstrack' => 'Қозғалыс бағыты',
'exif-gpsimgdirectionref' => 'Сурет бағытын көрсетуі',
'exif-gpsimgdirection' => 'Сурет бағыты',
'exif-gpsmapdatum' => 'Пайдаланылған геодезиялық түсірме деректері',
'exif-gpsdestlatituderef' => 'Нысана бойлығын көрсетуі',
'exif-gpsdestlatitude' => 'Нысана бойлығы',
'exif-gpsdestlongituderef' => 'Нысана ендігін көрсетуі',
'exif-gpsdestlongitude' => 'Нысана ендігі',
'exif-gpsdestbearingref' => 'Нысана азимутын көрсетуі',
'exif-gpsdestbearing' => 'Нысана азимуты',
'exif-gpsdestdistanceref' => 'Нысана қашықтығын көрсетуі',
'exif-gpsdestdistance' => 'Нысана қашықтығы',
'exif-gpsprocessingmethod' => 'GPS өңдету әдісінің атауы',
'exif-gpsareainformation' => 'GPS аумағының атауы',
'exif-gpsdatestamp' => 'GPS күн-айы',
'exif-gpsdifferential' => 'GPS сараланған дұрыстау',
'exif-keywords' => 'Пернетақталар',
'exif-worldregioncreated' => 'Бұл суретте Әлем аймақтары түсірілген',
'exif-countrycreated' => 'Бұл суретте мемлекет түсірілген',
'exif-countrycodecreated' => 'Бұл суретте мемлекет коды түсірілген',
'exif-provinceorstatecreated' => 'Бұл суретте облыс немесе штат түсірілген',
'exif-citycreated' => 'Бұл суретте қала түсірілген',
'exif-sublocationcreated' => 'Бұл суретте қала ауданы түсірілген',
'exif-worldregiondest' => 'Әлем аймақтары көрсетілген',
'exif-countrydest' => 'Мемлекет көрсетілген',
'exif-countrycodedest' => 'Мемлекет коды көрсетілген',
'exif-provinceorstatedest' => 'облыс (провинция) немесе штат көрсетілген',
'exif-citydest' => 'Қала көрсетілген',
'exif-sublocationdest' => 'Қала ауданы көрсетілген',
'exif-objectname' => 'Қысқаша атауы',
'exif-specialinstructions' => 'Арнайы таныстырылымдар',
'exif-headline' => 'Тақырып',
'exif-source' => 'Қайнары',
'exif-contact' => 'Байланыс ақпараттары',
'exif-writer' => 'Жазушы',
'exif-languagecode' => 'Тіл',
'exif-iimversion' => 'IIM нұсқа',
'exif-iimcategory' => 'Санат',
'exif-iimsupplementalcategory' => 'Қосымша санаттар',
'exif-datetimeexpires' => 'Соңынан қолданба',
'exif-identifier' => 'Жалпылауыш',
'exif-lens' => 'Линза қолданылған',
'exif-serialnumber' => 'Фотоаппараттың сериал нөмері',
'exif-cameraownername' => 'Фотоаппараттың иесі',
'exif-label' => 'Деңгей',
'exif-copyrighted' => 'Авторлық құқық күйі:',
'exif-copyrightowner' => 'Авторлық құқықтар иесі',

# Exif attributes
'exif-compression-1' => 'Ұлғайтылған',

'exif-unknowndate' => 'Белгісіз күн-айы',

'exif-orientation-1' => 'Қалыпты',
'exif-orientation-2' => 'Дерелей шағылысқан',
'exif-orientation-3' => '180° бұрышқа айналған',
'exif-orientation-4' => 'Тірелей шағылысқан',
'exif-orientation-5' => 'Сағат тілшесіне қарсы 90° бұрышқа айналған және тірелей шағылысқан',
'exif-orientation-6' => 'Сағат тілше бойынша 90° бұрышқа айналған',
'exif-orientation-7' => 'Сағат тілше бойынша 90° бұрышқа айналған және тірелей шағылысқан',
'exif-orientation-8' => 'Сағат тілшесіне қарсы 90° бұрышқа айналған',

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

'exif-meteringmode-0' => 'Белгісіз',
'exif-meteringmode-1' => 'Біркелкі',
'exif-meteringmode-2' => 'Бұлдыр дақ',
'exif-meteringmode-3' => 'БірДақты',
'exif-meteringmode-4' => 'КөпДақты',
'exif-meteringmode-5' => 'Өрнекті',
'exif-meteringmode-6' => 'Жыртынды',
'exif-meteringmode-255' => 'Басқа',

'exif-lightsource-0' => 'Белгісіз',
'exif-lightsource-1' => 'Күн жарығы',
'exif-lightsource-2' => 'Күнжарықты шам',
'exif-lightsource-3' => 'Қыздырғышты шам',
'exif-lightsource-4' => 'Жарқылдағыш',
'exif-lightsource-9' => 'Ашық күн',
'exif-lightsource-10' => 'Бұлынғыр күн',
'exif-lightsource-11' => 'Көленкелі',
'exif-lightsource-12' => 'Күнжарықты шам (D 5700–7100 K)',
'exif-lightsource-13' => 'Күнжарықты шам (N 4600–5400 K)',
'exif-lightsource-14' => 'Күнжарықты шам (W 3900–4500 K)',
'exif-lightsource-15' => 'Күнжарықты шам (WW 3200–3700 K)',
'exif-lightsource-17' => 'Қалыпты жарық қайнары A',
'exif-lightsource-18' => 'Қалыпты жарық қайнары B',
'exif-lightsource-19' => 'Қалыпты жарық қайнары C',
'exif-lightsource-24' => 'Студиялық ISO күнжарықты шам',
'exif-lightsource-255' => 'Басқа жарық көзі',

'exif-focalplaneresolutionunit-2' => 'дүйм',

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

'exif-whitebalance-0' => 'Ақ түсі өздіктік тендестірілген',
'exif-whitebalance-1' => 'Ақ түсі қолмен тендестірілген',

'exif-scenecapturetype-0' => 'Қалыпталған',
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

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mil/h',
'exif-gpsspeed-n' => 'knot',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Километр',
'exif-gpsdestdistance-m' => 'Миль',
'exif-gpsdestdistance-n' => 'Табиғи мильдер',

'exif-gpsdop-excellent' => '($1) керемет',
'exif-gpsdop-good' => '($1) жақсы',
'exif-gpsdop-moderate' => '($1) орташа',
'exif-gpsdop-fair' => '($1) әділ',
'exif-gpsdop-poor' => '($1) жаман',

'exif-objectcycle-a' => 'Тек таңертең',
'exif-objectcycle-p' => 'Тек кешке',
'exif-objectcycle-b' => 'таңертең және кешке екеуі де',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Шын бағыт',
'exif-gpsdirection-m' => 'Магнитты бағыт',

'exif-ycbcrpositioning-1' => 'Орталықты',

'exif-dc-date' => 'Күн(дер)',
'exif-dc-publisher' => 'Жариялаушы',
'exif-dc-relation' => 'Қатысты медиа',
'exif-dc-rights' => 'Құқықтар',
'exif-dc-source' => 'Қайнар медиа',
'exif-dc-type' => 'Медиа түрі',

'exif-rating-rejected' => 'Өшірілген',

'exif-isospeedratings-overflow' => '65535-нан үлкенірек',

'exif-iimcategory-fin' => 'Экономика және бизнес',
'exif-iimcategory-edu' => 'Білім',
'exif-iimcategory-evn' => 'Қоршаған орта',
'exif-iimcategory-hth' => 'Денсаулық',
'exif-iimcategory-pol' => 'Саясат',
'exif-iimcategory-sci' => 'Ғылым және технология',
'exif-iimcategory-spo' => 'Спорт',
'exif-iimcategory-wea' => 'Ауа райы',

'exif-urgency-normal' => 'Қалыпты ($1)',
'exif-urgency-low' => 'Төмен ($1)',
'exif-urgency-high' => 'Жоғары ($1)',

# External editor support
'edit-externally' => 'Бұл файлды шеттік қондырма арқылы өңдеу',
'edit-externally-help' => '(көбірек ақпарат үшін [//www.mediawiki.org/wiki/Manual:External_editors орнату нұсқауларын] қараңыз)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'барлық',
'namespacesall' => 'барлығы',
'monthsall' => 'барлығы',
'limitall' => 'барлығы',

# Email address confirmation
'confirmemail' => 'Е-пошта мекенжайын құптау',
'confirmemail_noemail' => '[[Special:Preferences|Пайдаланушылық бапталымдарыңызда]] жарамды е-пошта мекенжайын қоймапсыз.',
'confirmemail_text' => '{{SITENAME}} е-пошта мүмкіндіктерін пайдалану үшін алдынан е-пошта мекенжайыңыздың жарамдылығын тексеріп шығуыңыз керек.
Өзіңіздің мекенжайыңызға құптау хатын жөнелту үшін төмендегі батырманы нұқыңыз.
Хаттың ішінде коды бар сілтеме кірістірмек;
е-пошта жайыңыздың жарамдылығын құптау үшін сілтемені шолғыштың мекенжай жолағына енгізіп ашыңыз.',
'confirmemail_pending' => 'Құптау коды алдақашан хатпен жіберіліген;
егер жуықта тіркелсеңіз, жаңа кодын сұрату алдынан хат келуін біршама минөт күте тұрыңыз.',
'confirmemail_send' => 'Құптау кодын жөнелту',
'confirmemail_sent' => 'Құптау хаты жөнелтілді.',
'confirmemail_oncreate' => 'Құптау коды е-пошта мекенжайыңызға жөнелтілді.
Бұл белгілеме кіру үдірісіне керегі жоқ, бірақ е-пошта негізіндегі уики мүмкіндіктерді қосу үшін бұны жетістіруіңіз керек.',
'confirmemail_sendfailed' => '{{SITENAME}} құптау хаты жөнелтілмеді.
Жарамсыз таңбалар үшін мекенжайды тексеріп шығыңыз.

Пошта жібергіштің қайтарған мәліметі: $1',
'confirmemail_invalid' => 'Құптау коды жарамсыз.
Код мерзімі біткен шығар.',
'confirmemail_needlogin' => 'Е-пошта мекенжайыңызды құптау үшін $1 керек.',
'confirmemail_success' => 'Е-пошта мекенжайыңыз құпталды.
Енді уикиге кіріп жұмысқа кірісуге болады',
'confirmemail_loggedin' => 'Е-пошта мекенжайыңыз енді құпталды.',
'confirmemail_error' => 'Құптауңызды сақтағанда белгісіз қате болды.',
'confirmemail_subject' => '{{SITENAME}} торабынан е-пошта мекенжайыңызды құптау хаты',
'confirmemail_body' => 'Кейбіреу, $1 деген IP мекенжайынан, өзіңіз болуы мүмкін,
{{SITENAME}} жобасында бұл Е-пошта мекенжайын қолданып «$2» деген тіркелгі жасапты.

Бұл тіркелгі нақты сізге тән екенін құптау үшін, және {{SITENAME}} жобасының
е-пошта мүмкіндіктерін белсендіру үшін, мына сілтемені шолғышыңызбен ашыңыз:

$3

Егер бұл тіркелгіні жасаған өзіңіз *емес* болса, мына сілтемеге еріп
е-пошта мекенжайы құптауын болдырмаңыз:

$5

Құптау коды мерзімі бітетін кезі: $4.',
'confirmemail_invalidated' => 'Е-пошта мекенжайын құптауы болдырылмады',
'invalidateemail' => 'Е-пошта мекенжайын құптауы болдырмау',

# Scary transclusion
'scarytranscludedisabled' => '[Уики-аралық кірікбеттер өшірілген]',
'scarytranscludefailed' => '[$1 үшін үлгі келтіруі сәтсіз бітті; ғафу етіңіз]',
'scarytranscludetoolong' => '[URL тым ұзын; ғафу етіңіз]',

# Delete conflict
'deletedwhileediting' => 'Ескету: Бұл бетті өңдеуіңізді бастағанда, осы бет жойылды!',
'confirmrecreate' => "Бұл бетті өңдеуіңізді бастағанда [[User:$1|$1]] ([[User talk:$1|талқылауы]]) осы бетті жойды, келтірген себебі:
: ''$2''
Осы бетті қайта бастауын нақты тілегеніңізді құптаңыз.",
'recreate' => 'Қайта бастау',

'unit-pixel' => ' нүкте',

# action=purge
'confirm_purge_button' => 'Жарайды',
'confirm-purge-top' => 'Бұл беттін бүркемесін тазартасыз ба?',

# action=watch/unwatch
'confirm-watch-button' => 'Жарайды',
'confirm-watch-top' => 'Бұл бетті бақылау тізіміңізге қосқыңыз келе ме?',
'confirm-unwatch-button' => 'Жарайды',
'confirm-unwatch-top' => 'Бұл бетті бақылау тізіміңізден аластағыңыз келе ме?',

# Separators for various lists, etc.
'semicolon-separator' => ';',
'colon-separator' => ':&#32;',

# Multipage image navigation
'imgmultipageprev' => '← алдыңғы бетке',
'imgmultipagenext' => 'келесі бетке →',
'imgmultigo' => 'Өт!',
'imgmultigoto' => '$1 бетіне өту',

# Table pager
'ascending_abbrev' => 'өсу',
'descending_abbrev' => 'кему',
'table_pager_next' => 'Келесі бетке',
'table_pager_prev' => 'Алдыңғы бетке',
'table_pager_first' => 'Алғашқы бетке',
'table_pager_last' => 'Соңғы бетке',
'table_pager_limit' => 'Бет сайын $1 дана көрсет',
'table_pager_limit_label' => 'Бет сайын дана:',
'table_pager_limit_submit' => 'Өту',
'table_pager_empty' => 'Еш нәтиже жоқ',

# Auto-summaries
'autosumm-blank' => 'Беттің барлық мағлұматын аластады',
'autosumm-replace' => 'Бетті "$1" дегенмен алмастырды',
'autoredircomment' => '[[$1]] дегенге айдады',
'autosumm-new' => 'Жаңа бетте: "$1"',

# Size units
'size-bytes' => '$1 байт',

# Live preview
'livepreview-loading' => 'Жүктеуде…',
'livepreview-ready' => 'Жүктеуде… Дайын!',
'livepreview-failed' => 'Тура қарап шығу сәтсіз! Кәдімгі қарап шығу әдісін байқап көріңіз.',
'livepreview-error' => 'Қосылу сәтсіз: $1 "$2". Кәдімгі қарап шығу әдісін байқап көріңіз.',

# Friendlier slave lag warnings
'lag-warn-normal' => '{{PLURAL:$1|секунтта|секунтта}} $1 жаңалау өзгерістер бұл тізімде көрсетілмеуі мүмкін.',
'lag-warn-high' => 'Дерекқор сервері көп кешігуі себебінен, $1 {{PLURAL:$1|секунтта|сеунтта}} жаңалау өзгерістер бұл тізімде көрсетілмеуі мүмкін.',

# Watchlist editor
'watchlistedit-numitems' => 'Бақылау тізіміңізде, талқылау беттерсіз, {{PLURAL:$1|1 тақырып аты|$1 тақырып аттары}} бар.',
'watchlistedit-noitems' => 'Бақылау тізіміңізде еш тақырып аты жоқ.',
'watchlistedit-normal-title' => 'Бақылау тізімді өңдеу',
'watchlistedit-normal-legend' => 'Бақылау тізімінен тақырып аттарын аластау',
'watchlistedit-normal-explain' => 'Бақылау тізіміңіздегі тақырып аттар төменде көрсетіледі.
Тақырып атын аластау үшін, бүйір көзге құсбелгі салыңыз, және "{{int:Watchlistedit-normal-submit}}" дегенді нұқыңыз.
Тағы да [[Special:EditWatchlist/raw|қам тізімді өңдей]] аласыз.',
'watchlistedit-normal-submit' => 'Тақырып аттарын аласта',
'watchlistedit-normal-done' => 'Бақылау тізіміңізден {{PLURAL:$1|1 тақырып аты|$1 тақырып аттары}} аласталды:',
'watchlistedit-raw-title' => 'Қам бақылау тізімді өңдеу',
'watchlistedit-raw-legend' => 'Қам бақылау тізімді өңдеу',
'watchlistedit-raw-explain' => 'Бақылау тізіміңіздегі тақырып аттары төменде көрсетіледі, және де тізмге үстеп және тізмден аластап өңделуі мүмкін;
жол сайын бір тақырып аты болу жөн.
Бітіргеннен соң "{{int:Watchlistedit-raw-submit}}" дегенді нұқыңыз.
Тағы да [[Special:EditWatchlist|қалыпалған өңдеуішті пайдалана]] аласыз.',
'watchlistedit-raw-titles' => 'Тақырып аттары:',
'watchlistedit-raw-submit' => 'Бақылау тізімді жаңарту',
'watchlistedit-raw-done' => 'Бақылау тізіміңіз жаңартылды.',
'watchlistedit-raw-added' => '$1 тақырып аты үстелді:',
'watchlistedit-raw-removed' => '$1 тақырып аты аласталды:',

# Watchlist editing tools
'watchlisttools-view' => 'Қатысты өзгерістерді қарау',
'watchlisttools-edit' => 'Бақылау тізімді қарау және өңдеу',
'watchlisttools-raw' => 'Бақылау тізімін өңдеу',

# Iranian month names
'iranian-calendar-m1' => 'пыруардин',
'iranian-calendar-m2' => 'әрдибешт',
'iranian-calendar-m3' => 'хырдад',
'iranian-calendar-m4' => 'тир',
'iranian-calendar-m5' => 'мырдад',
'iranian-calendar-m6' => 'шерияр',
'iranian-calendar-m7' => 'мер',
'iranian-calendar-m8' => 'абан',
'iranian-calendar-m9' => 'азар',
'iranian-calendar-m10' => 'ди',
'iranian-calendar-m11' => 'бемін',
'iranian-calendar-m12' => 'аспанд',

# Hebrew month names
'hebrew-calendar-m1' => 'тішри',
'hebrew-calendar-m2' => 'xышуан',
'hebrew-calendar-m3' => 'кіслу',
'hebrew-calendar-m4' => 'тот',
'hebrew-calendar-m5' => 'шыбат',
'hebrew-calendar-m6' => 'адар',
'hebrew-calendar-m6a' => 'адар',
'hebrew-calendar-m6b' => 'уадар',
'hebrew-calendar-m7' => 'нисан',
'hebrew-calendar-m8' => 'аяр',
'hebrew-calendar-m9' => 'сиуан',
'hebrew-calendar-m10' => 'тымоз',
'hebrew-calendar-m11' => 'аб',
'hebrew-calendar-m12' => 'айлол',
'hebrew-calendar-m1-gen' => 'тішридің',
'hebrew-calendar-m2-gen' => 'хышуандың',
'hebrew-calendar-m3-gen' => 'кіслудің',
'hebrew-calendar-m4-gen' => 'тоттың',
'hebrew-calendar-m5-gen' => 'шыбаттың',
'hebrew-calendar-m6-gen' => 'адардың',
'hebrew-calendar-m6a-gen' => 'адардың',
'hebrew-calendar-m6b-gen' => 'уадардың',
'hebrew-calendar-m7-gen' => 'нисанның',
'hebrew-calendar-m8-gen' => 'аярдың',
'hebrew-calendar-m9-gen' => 'сиуанның',
'hebrew-calendar-m10-gen' => 'тымоздың',
'hebrew-calendar-m11-gen' => 'абтың',
'hebrew-calendar-m12-gen' => 'айлолдың',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|талқ]])',

# Core parser functions
'unknown_extension_tag' => 'Белгісіз кеңейтпе белгісі "$1"',

# Special:Version
'version' => 'Нұсқа',
'version-extensions' => 'Орнатылған кеңейтімдер',
'version-specialpages' => 'Арнайы беттер',
'version-parserhooks' => 'Құрылымдық талдатқыштың тұзақтары',
'version-variables' => 'Айнымалылар',
'version-antispam' => 'Спамнан қорғау',
'version-skins' => 'Мәнерлер',
'version-other' => 'Тағы басқалар',
'version-mediahandlers' => 'Медиа өңдеткіштері',
'version-hooks' => 'Жете тұзақтары',
'version-parser-extensiontags' => 'Құрылымдық талдатқыш кеңейтімдерінің белгілемері',
'version-parser-function-hooks' => 'Құрылымдық талдатқыш жетелерінің тұзақтары',
'version-hook-name' => 'Тұзақ атауы',
'version-hook-subscribedby' => 'Тұзақ тартқыштары',
'version-version' => '(Нұсқасы: $1)',
'version-license' => 'Лицензиясы',
'version-poweredby-others' => 'басқалар',
'version-software' => 'Орнатылған бағдарламалық жасақтама',
'version-software-product' => 'Өнім',
'version-software-version' => 'Нұсқасы',
'version-entrypoints-header-url' => 'URL',

# Special:Redirect
'redirect-legend' => 'Файл немесе бетке айдатулар',
'redirect-submit' => 'Өту',
'redirect-lookup' => 'Іздеу:',
'redirect-value' => 'Мән:',
'redirect-user' => 'Қатысушы ID',
'redirect-revision' => 'Бет түзетуі',
'redirect-file' => 'Файл атауы',
'redirect-not-exists' => 'Мән табылмады',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Файл телнұсқаларын іздеу',
'fileduplicatesearch-summary' => 'Файл хеші мағынасы негізінде телнұсқаларын іздеу.',
'fileduplicatesearch-legend' => 'Телнұсқаны іздеу',
'fileduplicatesearch-filename' => 'Файл атауы:',
'fileduplicatesearch-submit' => 'Ізде',
'fileduplicatesearch-info' => '$1 × $2 пиксел<br />Файл мөлшері: $3<br />MIME түрі: $4',
'fileduplicatesearch-result-1' => '«$1» файлына тең телнұсқасы жоқ.',
'fileduplicatesearch-result-n' => '«$1» файлына тең $2 телнұсқасы бар.',
'fileduplicatesearch-noresults' => '"$1" атауымен файл табылмады.',

# Special:SpecialPages
'specialpages' => 'Арнайы беттер',
'specialpages-note' => '----
* Кәдімгі арнайы беттер.
* <span class=="mw-specialpagerestricted">Шектелген арнайы беттер.</span>',
'specialpages-group-maintenance' => 'Техникалық талқылау есептері',
'specialpages-group-other' => 'Тағы басқа арнайы беттер',
'specialpages-group-login' => 'Кіру / тіркелгі жасау',
'specialpages-group-changes' => 'Жуықтағы өзгерістер мен журналдар',
'specialpages-group-media' => 'Медиа баянаттары және жүктелгендер',
'specialpages-group-users' => 'Қатысушылар және олардың құқықтары',
'specialpages-group-highuse' => 'Өте көп қолданылған беттер',
'specialpages-group-pages' => 'Беттер тізімдері',
'specialpages-group-pagetools' => 'Бет құралдары',
'specialpages-group-wiki' => 'Деректер және құралдар',
'specialpages-group-redirects' => 'Айдайтын арнайы беттер',
'specialpages-group-spam' => 'Спам құралдары',

# Special:BlankPage
'blankpage' => 'Бос бет',
'intentionallyblankpage' => 'Бұл бет әдейі бос қалдырылған',

# Special:Tags
'tags' => 'Тектерді өзгерту жарамсыз',
'tag-filter' => '[[Special:Tags|Тег]] сүзгісі:',
'tag-filter-submit' => 'Сүзгі',
'tag-list-wrapper' => '([[Special:Tags|{{PLURAL:$1|Тег|Тег}}]]: $2)',
'tags-title' => 'Тегтер',
'tags-tag' => 'Тег атауы',
'tags-display-header' => 'Өзгеріс тізіміндегі көрінісі',
'tags-description-header' => 'Толық сипаттама мәні',
'tags-active-header' => 'Белсенді ме?',
'tags-hitcount-header' => 'Тегтелген өзгерістер',
'tags-active-yes' => 'Иә',
'tags-edit' => 'өңдеу',
'tags-hitcount' => '$1 {{PLURAL:$1|өзгеріс|өзгеріс}}',

# Special:ComparePages
'comparepages' => 'Беттерді салыстыру',
'compare-selector' => 'Бет түзетулерін салыстыру',
'compare-page1' => 'Бет 1',
'compare-page2' => 'Бет 2',
'compare-rev1' => 'Нұсқа 1',
'compare-rev2' => 'Нұсқа 2',
'compare-submit' => 'Салыстыру',

# Database error messages
'dberr-header' => 'Бұл уикиде мәселе бар',

# HTML forms
'htmlform-required' => 'Бұл мән міндетті',
'htmlform-submit' => 'Жіберу',
'htmlform-reset' => 'Өзгерістерді болдырмау',
'htmlform-selectorother-other' => 'Басқа',
'htmlform-no' => 'Жоқ',
'htmlform-yes' => 'Иә',

# New logging system
'logentry-delete-delete' => '$1 $3 деген бетті {{GENDER:$2|жойды}}',
'logentry-delete-restore' => '$1 $3 деген бетті {{GENDER:$2|қалпына келтірді}}',
'revdelete-content-hid' => 'мағұлымат жасырылған',
'revdelete-summary-hid' => 'өңдеу түйіндемесі жасырылған',
'revdelete-uname-hid' => 'қатысушы есімі жасырылған',
'revdelete-content-unhid' => 'мағлұматы жасырылмаған',
'revdelete-summary-unhid' => 'өңдеу түйіндемесі жасырылмаған',
'revdelete-uname-unhid' => 'қатысушы есімі жасырылмаған',
'revdelete-restricted' => 'әкімшілерге тиымдар қолдады',
'revdelete-unrestricted' => 'әкімшілерден тиымдарды аластады',
'logentry-move-move' => '$1 $3 бетін $4 бетіне {{GENDER:$2|жылжытты}}',
'logentry-move-move-noredirect' => '$1 $3 бетін $4 бетіне {{GENDER:$2|жылжытты}} (айдатқыш қалдырылмады)',
'logentry-move-move_redir' => '$1 $3 бетін $4 деген айдатқыш үстіне {{GENDER:$2|жылжытты}}',
'logentry-move-move_redir-noredirect' => '$1 $3 бетін $4 деген айдатқыш үстіне {{GENDER:$2|жылжытты}} (айдатқыш қалдырылмады)',
'logentry-newusers-newusers' => '$1 жаңадан қатысушы тіркелгісін {{GENDER:$2|жасады}}',
'logentry-newusers-create' => '$1 жаңадан аккаунт тіркеді',
'logentry-newusers-create2' => '$1 $3 деген аккаунт тіркеді',
'logentry-newusers-autocreate' => '$1 қатысушы аккаунтын автоматты түрде {{GENDER:$2|тіркеді}}',
'logentry-rights-rights' => '$1 $3 үшін топ мүшелігін $4 дегеннен $5 дегенге {{GENDER:$2|өзгерті}}',
'logentry-rights-rights-legacy' => '$1 $3 үшін топ мүшелігін {{GENDER:$2|өзгерті}}',
'rightsnone' => '(ешқандай)',

# Feedback
'feedback-subject' => 'Тақырып:',
'feedback-message' => 'Хабарлама:',
'feedback-cancel' => 'Болдырмау',
'feedback-submit' => 'Кері байланысты жіберу',
'feedback-adding' => 'Бетке кері байланыс қосуда...',
'feedback-error2' => 'Қате: Өңдеме сәтсіздікке ұшырады',
'feedback-thanks' => 'Рахмет! Сіздің кері байланысыңыз "[$2 $1]" бетіне қойылды.',
'feedback-close' => 'Жасалды',

# Search suggestions
'searchsuggest-search' => 'Іздеу',
'searchsuggest-containing' => 'қамтылуда...',

# API errors
'api-error-unclassified' => 'Белгісіз қателік орын алды.',
'api-error-unknown-code' => 'Белгісіз қате: "$1".',
'api-error-unknown-warning' => 'Белгісіз ескерту: "$1".',
'api-error-unknownerror' => 'Белгісіз қате: "$1".',
'api-error-uploaddisabled' => 'Бұл уикиде жүктеп беру өшірілген.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|секунт|секунт}}',
'duration-minutes' => '$1 {{PLURAL:$1|минут|минут}}',
'duration-hours' => '$1 {{PLURAL:$1|сағат|сағат}}',
'duration-days' => '$1 {{PLURAL:$1|күн|күн}}',
'duration-weeks' => '$1 {{PLURAL:$1|апта|апта}}',
'duration-years' => '$1 {{PLURAL:$1|жыл|жыл}}',
'duration-decades' => '$1 {{PLURAL:$1|он жылдық|он жылдық}}',
'duration-centuries' => '$1 {{PLURAL:$1|ғасыр|ғасыр}}',
'duration-millennia' => '$1 {{PLURAL:$1|мың жылдық|мың жылдық}}',

);
