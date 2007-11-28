<?php
/** Belarusian in Taraskievica orthography (Беларуская тарашкевіца)
  *
  * @addtogroup Language
  */

$skinNames = array(
	'standard'    => 'Клясычнае',
	'nostalgia'   => 'Настальгія',
	'cologneblue' => 'Кёльнскі смутак',
	'monobook'    => 'Монакніга',
	'myskin'      => 'MySkin',
	'chick'       => 'Цыпа'
);

$bookstoreList = array(
	'OZ.by' => 'http://oz.by/search.phtml?what=books&isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$datePreferences = array(
	'default',
	'dmy',
	'ISO 8601',
);

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',
);

$magicWords = array(
	'redirect'               => array( 0,    '#перанакіраваньне', '#redirect' ),
	'notoc'                  => array( 0,    '__NOTOC__', '__БЯЗЬ_ЗЬМЕСТУ__' ),
	'nogallery'              => array( 0,    '__NOGALLERY__', '__БЕЗ_ГАЛЕРЭІ__' ),
	'forcetoc'               => array( 0,    '__FORCETOC__', '__ЗЬМЕСТ_ПРЫМУСАМ__' ),
	'toc'                    => array( 0,    '__TOC__', '__ЗЬМЕСТ__' ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__БЕЗ_РЭДАГАВАНЬНЯ_СЭКЦЫІ__' ),
	'currentmonth'           => array( 1,    'CURRENTMONTH', 'БЯГУЧЫ_МЕСЯЦ' ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА' ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА_Ў_РОДНЫМ_СКЛОНЕ' ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV', 'СКАРОЧАНАЯ_НАЗВА_БЯГУЧАГА_МЕСЯЦА' ),
	'currentday'             => array( 1,    'CURRENTDAY', 'БЯГУЧЫ_ДЗЕНЬ' ),
	'currentday2'            => array( 1,    'CURRENTDAY2', 'БЯГУЧЫ_ДЗЕНЬ_2' ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME', 'НАЗВА_БЯГУЧАГА_ДНЯ' ),
	'currentyear'            => array( 1,    'CURRENTYEAR', 'БЯГУЧЫ_ГОД' ),
	'currenttime'            => array( 1,    'CURRENTTIME', 'БЯГУЧЫ_ЧАС' ),
	'currenthour'            => array( 1,    'CURRENTHOUR', 'БЯГУЧАЯ_ГАДЗІНА' ),
	'localmonth'             => array( 1,    'LOCALMONTH', 'ЛЯКАЛЬНЫ_МЕСЯЦ' ),
	'localmonthname'         => array( 1,    'LOCALMONTHNAME', 'НАЗВА_ЛЯКАЛЬНАГА_МЕСЯЦА' ),
	'localmonthnamegen'      => array( 1,    'LOCALMONTHNAMEGEN', 'НАЗВА_ЛЯКАЛЬНАГА_МЕСЯЦА_Ў_РОДНЫМ_СКЛОНЕ' ),
	'localmonthabbrev'       => array( 1,    'LOCALMONTHABBREV', 'СКАРОЧАНАЯ_НАЗВА_ЛЯКАЛЬНАГА_МЕСЯЦА' ),
	'localday'               => array( 1,    'LOCALDAY', 'ЛЯКАЛЬНЫ_ДЗЕНЬ' ),
	'localday2'              => array( 1,    'LOCALDAY2', 'ЛЯКАЛЬНЫ_ДЗЕНЬ_2' ),
	'localdayname'           => array( 1,    'LOCALDAYNAME', 'НАЗВА_ЛЯКАЛЬНАГА_ДНЯ' ),
	'localyear'              => array( 1,    'LOCALYEAR', 'ЛЯКАЛЬНЫ_ГОД' ),
	'localtime'              => array( 1,    'LOCALTIME', 'ЛЯКАЛЬНЫ_ЧАС' ),
	'localhour'              => array( 1,    'LOCALHOUR', 'ЛЯКАЛЬНАЯ_ГАДЗІНА' ),
	'numberofpages'          => array( 1,    'NUMBEROFPAGES', 'КОЛЬКАСЬЦЬ_СТАРОНАК' ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES', 'КОЛЬКАСЬЦЬ_АРТЫКУЛАЎ' ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES', 'КОЛЬКАСЬЦЬ_ФАЙЛАЎ' ),
	'numberofusers'          => array( 1,    'NUMBEROFUSERS', 'КОЛЬКАСЬЦЬ_УДЗЕЛЬНІКАЎ' ),
	'numberofedits'          => array( 1,    'NUMBEROFEDITS', 'КОЛЬКАСЬЦЬ_РЭДАГАВАНЬНЯЎ' ),
	'pagename'               => array( 1,    'PAGENAME', 'НАЗВА_СТАРОНКІ' ),
	'pagenamee'              => array( 1,    'PAGENAMEE', 'НАЗВА_СТАРОНКІ_2' ),
	'namespace'              => array( 1,    'NAMESPACE', 'ПРАСТОРА_НАЗВАЎ' ),
	'namespacee'             => array( 1,    'NAMESPACEE', 'ПРАСТОРА_НАЗВАЎ_2' ),
	'talkspace'              => array( 1,    'TALKSPACE', 'ПРАСТОРА_НАЗВАЎ_АБМЕРКАВАНЬНЯ' ),
	'talkspacee'             => array( 1,    'TALKSPACEE', 'ПРАСТОРА_НАЗВАЎ_АБМЕРКАВАНЬНЯ_2' ),
	'subjectspace'           => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE', 'ПРАСТОРА_НАЗВАЎ_ПРАДМЕТУ', 'ПРАСТОРА_НАЗВАЎ_АРТЫКУЛА' ),
	'subjectspacee'          => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE', 'ПРАСТОРА_НАЗВАЎ_ПРАДМЕТУ_2', 'ПРАСТОРА_НАЗВАЎ_АРТЫКУЛА_2' ),
	'fullpagename'           => array( 1,    'FULLPAGENAME', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ' ),
	'fullpagenamee'          => array( 1,    'FULLPAGENAMEE', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ_2' ),
	'subpagename'            => array( 1,    'SUBPAGENAME', 'НАЗВА_ПАДСТАРОНКІ' ),
	'subpagenamee'           => array( 1,    'SUBPAGENAMEE', 'НАЗВА_ПАДСТАРОНКІ_2' ),
	'basepagename'           => array( 1,    'BASEPAGENAME', 'НАЗВА_БАЗАВАЙ_СТАРОНКІ' ),
	'basepagenamee'          => array( 1,    'BASEPAGENAMEE', 'НАЗВА_БАЗАВАЙ_СТАРОНКІ_2' ),
	'talkpagename'           => array( 1,    'TALKPAGENAME', 'НАЗВА_СТАРОНКІ_АБМЕРКАВАНЬНЯ' ),
	'talkpagenamee'          => array( 1,    'TALKPAGENAMEE', 'НАЗВА_СТАРОНКІ_АБМЕРКАВАНЬНЯ_2' ),
	'subjectpagename'        => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME', 'НАЗВА_СТАРОНКІ_ПРАДМЕТУ', 'НАЗВА_СТАРОНКІ_АРТЫКУЛА' ),
	'subjectpagenamee'       => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE', 'НАЗВА_СТАРОНКІ_ПРАДМЕТУ_2', 'НАЗВА_СТАРОНКІ_АРТЫКУЛА_2' ),
	'msg'                    => array( 0,    'MSG:', 'ПАВЕДАМЛЕНЬНЕ:' ),
	'subst'                  => array( 0,    'SUBST:', 'ПАДСТАНОЎКА:' ),
	'msgnw'                  => array( 0,    'MSGNW:', 'ПАВЕДАМЛЕНЬНЕ_БЯЗЬ_ВІКІ:' ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb', 'значак', 'міні' ),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1', 'значак=$1', 'міні=$1' ),
	'img_right'              => array( 1,    'right', 'справа' ),
	'img_left'               => array( 1,    'left', 'зьлева' ),
	'img_none'               => array( 1,    'none', 'няма' ),
	'img_width'              => array( 1,    '$1px', '$1пкс' ),
	'img_center'             => array( 1,    'center', 'centre', 'цэнтар' ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame', 'рамка' ),
	'img_page'               => array( 1,    'page=$1', 'page $1', 'старонка=$1', 'старонка $1' ),
	'img_top'                => array( 1,    'top', 'зьверху' ),
	'img_middle'             => array( 1,    'middle', 'пасярэдзіне' ),
	'img_bottom'             => array( 1,    'bottom', 'зьнізу' ),
	'sitename'               => array( 1,    'SITENAME', 'НАЗВА_САЙТУ' ),
	'ns'                     => array( 0,    'NS:', 'ПН:' ),
	'localurl'               => array( 0,    'LOCALURL:', 'ЛЯКАЛЬНЫ_АДРАС:' ),
	'localurle'              => array( 0,    'LOCALURLE:', 'ЛЯКАЛЬНЫ_АДРАС_2:' ),
	'server'                 => array( 0,    'SERVER', 'СЭРВЭР' ),
	'servername'             => array( 0,    'SERVERNAME', 'НАЗВА_СЭРВЭРА' ),
	'scriptpath'             => array( 0,    'SCRIPTPATH', 'ШЛЯХ_ДА_СКРЫПТА' ),
	'grammar'                => array( 0,    'GRAMMAR:', 'ГРАМАТЫКА:' ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__БЕЗ_КАНВЭРТАЦЫІ_НАЗВЫ__' ),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__БЕЗ_КАНВЭРТАЦЫІ_ТЭКСТУ__' ),
	'currentweek'            => array( 1,    'CURRENTWEEK', 'БЯГУЧЫ_ТЫДЗЕНЬ' ),
	'currentdow'             => array( 1,    'CURRENTDOW', 'БЯГУЧЫ_ДЗЕНЬ_ТЫДНЯ' ),
	'localweek'              => array( 1,    'LOCALWEEK', 'ЛЯКАЛЬНЫ_ТЫДЗЕНЬ' ),
	'localdow'               => array( 1,    'LOCALDOW', 'ЛЯКАЛЬНЫ_ДЗЕНЬ_ТЫДНЯ' ),
	'revisionid'             => array( 1,    'REVISIONID', 'ID_ВЭРСІІ' ),
	'revisionday'            => array( 1,    'REVISIONDAY', 'ДЗЕНЬ_ВЭРСІІ' ),
	'revisionday2'           => array( 1,    'REVISIONDAY2', 'ДЗЕНЬ_ВЭРСІІ_2' ),
	'revisionmonth'          => array( 1,    'REVISIONMONTH', 'МЕСЯЦ_ВЭРСІІ' ),
	'revisionyear'           => array( 1,    'REVISIONYEAR', 'ГОД_ВЭРСІІ' ),
	'revisiontimestamp'      => array( 1,    'REVISIONTIMESTAMP', 'МОМАНТ_ЧАСУ_ВЭРСІІ' ),
	'plural'                 => array( 0,    'PLURAL:', 'МНОЖНЫ_ЛІК:'),
	'fullurl'                => array( 0,    'FULLURL:', 'ПОЎНЫ_АДРАС:' ),
	'fullurle'               => array( 0,    'FULLURLE:', 'ПОЎНЫ_АДРАС_2:' ),
	'lcfirst'                => array( 0,    'LCFIRST:', 'ПЕРШАЯ_ЛІТАРА_МАЛАЯ:' ),
	'ucfirst'                => array( 0,    'UCFIRST:', 'ПЕРШАЯ_ЛІТАРА_ВЯЛІКАЯ:' ),
	'lc'                     => array( 0,    'LC:', 'МАЛЫМІ_ЛІТАРАМІ:' ),
	'uc'                     => array( 0,    'UC:', 'ВЯЛІКІМІ_ЛІТАРАМІ:' ),
	'raw'                    => array( 0,    'RAW:', 'НЕАПРАЦАВАНЫ:' ),
	'displaytitle'           => array( 1,    'DISPLAYTITLE', 'ПАКАЗВАЦЬ_НАЗВУ' ),
	'rawsuffix'              => array( 1,    'R', 'Н' ),
	'newsectionlink'         => array( 1,    '__NEWSECTIONLINK__', '__СПАСЫЛКА_НА_НОВУЮ_СЭКЦЫЮ__' ),
	'currentversion'         => array( 1,    'CURRENTVERSION', 'БЯГУЧАЯ_ВЭРСІЯ' ),
	'currenttimestamp'       => array( 1,    'CURRENTTIMESTAMP', 'МОМАНТ_ЧАСУ' ),
	'localtimestamp'         => array( 1,    'LOCALTIMESTAMP', 'ЛЯКАЛЬНЫ_МОМАНТ_ЧАСУ' ),
	'directionmark'          => array( 1,    'DIRECTIONMARK', 'DIRMARK', 'СЫМБАЛЬ_НАПРАМКУ_ПІСЬМА' ),
	'language'               => array( 0,    '#LANGUAGE:', '#МОВА:' ),
	'contentlanguage'        => array( 1,    'CONTENTLANGUAGE', 'CONTENTLANG', 'МОВА_ЗЬМЕСТУ' ),
	'pagesinnamespace'       => array( 1,    'PAGESINNAMESPACE:', 'PAGESINNS:', 'КОЛЬКАСЬЦЬ_СТАРОНАК_У_ПРАСТОРЫ_НАЗВАЎ:' ),
	'numberofadmins'         => array( 1,    'NUMBEROFADMINS', 'КОЛЬКАСЬЦЬ_АДМІНІСТРАТАРАЎ' ),
	'formatnum'              => array( 0,    'FORMATNUM', 'ФАРМАТАВАЦЬ_ЛІК' ),
	'defaultsort'            => array( 1,    'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:',
	                                         'САРТЫРОЎКА_ПА_ЗМОЎЧВАНЬНІ:', 'КЛЮЧ_САРТЫРОЎКІ_ПА_ЗМОЎЧВАНЬНІ:',
	                                         'САРТЫРОЎКА_Ў_КАТЭГОРЫІ_ПА_ЗМОЎЧВАНЬНІ:' ),
);

$namespaceNames = array(
	NS_MEDIA          => 'Мэдыя',
	NS_SPECIAL        => 'Спэцыяльныя',
	NS_MAIN           => '',
	NS_TALK           => 'Абмеркаваньне',
	NS_USER           => 'Удзельнік',
	NS_USER_TALK      => 'Гутаркі_ўдзельніка',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Абмеркаваньне_$1',
	NS_IMAGE          => 'Выява',
	NS_IMAGE_TALK     => 'Абмеркаваньне_выявы',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Абмеркаваньне_MediaWiki',
	NS_TEMPLATE       => 'Шаблён',
	NS_TEMPLATE_TALK  => 'Абмеркаваньне_шаблёну',
	NS_HELP           => 'Дапамога',
	NS_HELP_TALK      => 'Абмеркаваньне_дапамогі',
	NS_CATEGORY       => 'Катэгорыя',
	NS_CATEGORY_TALK  => 'Абмеркаваньне_катэгорыі'
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Двайныя_перанакіраваньні' ),
	'BrokenRedirects'           => array( 'Некарэктныя_перанакіраваньні' ),
	'Disambiguations'           => array( 'Неадназначнасьці' ),
	'Watchlist'                 => array( 'Сьпіс_назіраньня' ),
	'Recentchanges'             => array( 'Апошнія_зьмены' ),
	'Upload'                    => array( 'Загрузка' ),
	'Imagelist'                 => array( 'Сьпіс_файлаў' ),
	'Newimages'                 => array( 'Новыя_файлы' ),
	'Listusers'                 => array( 'Сьпіс_удзельнікаў' ),
	'Statistics'                => array( 'Статыстыка' ),
	'Randompage'                => array( 'Выпадковая_старонка' ),
	'Lonelypages'               => array( 'Старонкі-сіраціны' ),
	'Uncategorizedpages'        => array( 'Некатэгарызаваныя_старонкі' ),
	'Uncategorizedcategories'   => array( 'Некатэгарызаваныя_катэгорыі' ),
	'Uncategorizedimages'       => array( 'Некатэгарызаваныя_файлы' ),
	'Uncategorizedtemplates'    => array( 'Некатэгарызаваныя_шаблёны' ),
	'Wantedpages'               => array( 'Запатрабаваныя_старонкі', 'Некарэктныя_спасылкі' ),
	'Wantedcategories'          => array( 'Запатрабаваныя_катэгорыі' ),
	'Shortpages'                => array( 'Кароткія_старонкі' ),
	'Longpages'                 => array( 'Доўгія_старонкі' ),
	'Newpages'                  => array( 'Новыя_старонкі' ),
	'Ancientpages'              => array( 'Найстарэйшыя_старонкі' ),
	'Deadendpages'              => array( 'Тупіковыя_старонкі' ),
	'Protectedpages'            => array( 'Абароненыя_старонкі' ),
	'Allpages'                  => array( 'Усе_старонкі' ),
	'Specialpages'              => array( 'Спэцыяльныя_старонкі' ),
	'Contributions'             => array( 'Унёсак' ),
	'Categories'                => array( 'Катэгорыі' ),
	'Export'                    => array( 'Экспарт' ),
	'Version'                   => array( 'Вэрсія' ),
	'Allmessages'               => array( 'Сыстэмныя_паведамленьні' ),
	'Log'                       => array( 'Журналы_падзей' ),
	'Blockip'                   => array( 'Блякаваньне' ),
	'Import'                    => array( 'Імпарт' ),
	'Listredirects'             => array( 'Сьпіс_перанакіраваньняў' ),
	'Randomredirect'            => array( 'Выпадковае_перанакіраваньне' ),
	'Mypage'                    => array( 'Мая_старонка' ),
	'Mytalk'                    => array( 'Мае_размовы' ),
	'Mycontributions'           => array( 'Мой_унёсак' ),
	'Listadmins'                => array( 'Сьпіс_адміністратараў' ),
	'Popularpages'              => array( 'Папулярныя_старонкі' ),
	'Search'                    => array( 'Пошук' ),
);

$separatorTransformTable = array(',' => '.', '.' => ',' );

$linkTrail = '/^([абвгґджзеёжзійклмнопрстуўфхцчшыьэюяćčłńśšŭźža-z]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'          => 'Падкрэсьліваць спасылкі:',
'tog-hideminor'          => 'Хаваць дробныя зьмены ў сьпісе апошніх зьменаў',
'tog-usenewrc'           => 'Удасканалены сьпіс апошніх зьменаў (патрабуе JavaScript)',
'tog-numberheadings'     => 'Аўтаматычная нумарацыя загалоўкаў',
'tog-showtoolbar'        => 'Паказваць панэль інструмэнтаў рэдагаваньня (патрабуе JavaScript)',
'tog-editsection'        => 'Дазволіць рэдагаваньне асобных сэкцыяў па спасылках [рэдагаваць]',
'tog-showtoc'            => 'Паказваць зьмест (для старонак з колькасьцю сэкцый болей за 3)',
'tog-rememberpassword'   => 'Запомніць мяне на гэтым кампутары',
'tog-editwidth'          => 'Поле рэдагаваньня ў поўную шырыню',
'tog-minordefault'       => 'Па змоўчаньні пазначаць усе зьмены дробнымі',
'tog-previewonfirst'     => 'Папярэдні прагляд пры першым рэдагаваньні',
'tog-nocache'            => 'Адключыць кэшаваньне старонак',
'tog-fancysig'           => 'Просты подпіс (без аўтаматычнай спасылкі)',
'tog-externaleditor'     => 'Па змоўчаньні выкарыстоўваць зьнешні рэдактар',
'tog-watchlisthideown'   => 'Хаваць мае праўкі ў сьпісе назіраньня',
'tog-watchlisthidebots'  => 'Хаваць праўкі робатаў у сьпісе назіраньня',
'tog-watchlisthideminor' => 'Хаваць дробныя праўкі ў сьпісе назіраньня',
'tog-ccmeonemails'       => 'Дасылаць мне копіі лістоў, якія я дасылаю іншым удзельнікам і ўдзельніцам',

'underline-always' => 'Заўсёды',
'underline-never'  => 'Ніколі',

'skinpreview' => '(Прагляд)',

# Dates
'sunday'        => 'нядзеля',
'monday'        => 'панядзелак',
'tuesday'       => 'аўторак',
'wednesday'     => 'серада',
'thursday'      => 'чацьвер',
'friday'        => 'пятніца',
'saturday'      => 'субота',
'january'       => 'студзень',
'february'      => 'люты',
'march'         => 'сакавік',
'april'         => 'красавік',
'may_long'      => 'травень',
'june'          => 'чэрвень',
'july'          => 'ліпень',
'august'        => 'жнівень',
'september'     => 'верасень',
'october'       => 'кастрычнік',
'november'      => 'лістапад',
'december'      => 'сьнежань',
'january-gen'   => 'студзеня',
'february-gen'  => 'лютага',
'march-gen'     => 'сакавіка',
'april-gen'     => 'красавіка',
'may-gen'       => 'траўня',
'june-gen'      => 'чэрвеня',
'july-gen'      => 'ліпеня',
'august-gen'    => 'жніўня',
'september-gen' => 'верасьня',
'october-gen'   => 'кастрычніка',
'november-gen'  => 'лістапада',
'december-gen'  => 'сьнежня',
'jan'           => '01',
'feb'           => '02',
'mar'           => '03',
'apr'           => '04',
'may'           => '05',
'jun'           => '06',
'jul'           => '07',
'aug'           => '08',
'sep'           => '09',
'oct'           => '10',
'nov'           => '11',
'dec'           => '12',

# Bits of text used by many pages
'categories'            => 'Катэгорыі',
'pagecategories'        => '{{PLURAL:$1|Катэгорыя|Катэгорыі|Катэгорыі}}',
'category_header'       => 'Артыкулы ў катэгорыі «$1»',
'subcategories'         => 'Падкатэгорыі',
'category-media-header' => 'Файлы ў катэгорыі «$1»',
'category-empty'        => "''Гэтая катэгорыя ня ўтрымлівае ні артыкулаў, ні файлаў.''",

'about'          => 'Пра',
'article'        => 'Артыкул',
'newwindow'      => '(адчыняецца ў новым акне)',
'cancel'         => 'Адмяніць',
'qbfind'         => 'Знайсьці',
'qbbrowse'       => 'Праглядзець',
'qbedit'         => 'Рэдагаваць',
'qbpageoptions'  => 'Гэтая старонка',
'qbspecialpages' => 'Спэцыяльныя старонкі',
'mypage'         => 'Мая старонка',
'mytalk'         => 'Мае размовы',
'anontalk'       => 'Гутаркі для гэтага IP-адраса',
'navigation'     => 'Навігацыя',

'errorpagetitle'   => 'Памылка',
'returnto'         => 'Вярнуцца да $1.',
'tagline'          => 'Зьвесткі зь {{GRAMMAR:родны|{{SITENAME}}}}',
'help'             => 'Дапамога',
'search'           => 'Пошук',
'searchbutton'     => 'Пошук',
'go'               => 'Старонка',
'searcharticle'    => 'Старонка',
'history'          => 'Гісторыя старонкі',
'history_short'    => 'Гісторыя',
'info_short'       => 'Інфармацыя',
'printableversion' => 'Вэрсія для друку',
'permalink'        => 'Сталая спасылка',
'edit'             => 'Рэдагаваць',
'editthispage'     => 'Рэдагаваць гэтую старонку',
'delete'           => 'Выдаліць',
'deletethispage'   => 'Выдаліць гэтую старонку',
'protect'          => 'Абараніць',
'protectthispage'  => 'Абараніць гэтую старонку',
'unprotect'        => 'зьняць абарону',
'newpage'          => 'Новая старонка',
'talkpage'         => 'Абмеркаваць гэтую старонку',
'talkpagelinktext' => 'гутаркі',
'specialpage'      => 'Спэцыяльная старонка',
'postcomment'      => 'Пракамэнтаваць',
'articlepage'      => 'Паказаць артыкул',
'talk'             => 'Гутаркі',
'toolbox'          => 'Інструмэнты',
'projectpage'      => 'Паказаць старонку праекту',
'imagepage'        => 'Паказаць старонку выявы',
'categorypage'     => 'Паказаць старонку катэгорыі',
'viewtalkpage'     => 'Паказаць абмеркаваньне',
'otherlanguages'   => 'На іншых мовах',
'redirectedfrom'   => '(Перанакіраваная з $1)',
'redirectpagesub'  => 'Старонка-перанакіраваньне',
'lastmodifiedat'   => 'Гэтая старонка апошні раз рэдагавалася $2, $1.', # $1 date, $2 time
'protectedpage'    => 'Абароненая старонка',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Пра {{GRAMMAR:вінавальны|{{SITENAME}}}}',
'aboutpage'         => 'Project:Пра {{GRAMMAR:вінавальны|{{SITENAME}}}}',
'bugreports'        => 'Паведамленьні пра памылкі',
'bugreportspage'    => 'Project:Паведамленьні пра памылкі',
'copyright'         => 'Зьмест старонкі падпадае пад ліцэнзію $1.',
'copyrightpagename' => 'Аўтарскія правы {{GRAMMAR:родны|{{SITENAME}}}}',
'copyrightpage'     => '{{ns:project}}:Аўтарскія правы',
'currentevents'     => 'Бягучыя падзеі',
'currentevents-url' => 'Бягучыя падзеі',
'disclaimers'       => 'Адмова ад адказнасьці',
'disclaimerpage'    => 'Project:Адмова ад адказнасьці',
'edithelp'          => 'Дапамога ў рэдагаваньні',
'edithelppage'      => 'Help:Рэдагаваньне',
'helppage'          => 'Help:Зьмест',
'mainpage'          => 'Галоўная старонка',
'policy-url'        => 'Project:Правілы',
'portal'            => 'Суполка',
'portal-url'        => 'Project:Суполка',
'privacy'           => 'Правілы адносна прыватнасьці',
'privacypage'       => 'Project:Правілы адносна прыватнасьці',
'sitesupport'       => 'Ахвяраваньні',

'badaccess'        => 'Памылка: не адпаведны ўзровень правоў',
'badaccess-group0' => 'Вам не дазволена выканаць запытанае Вамі дзеяньне.',
'badaccess-group1' => 'Запытанае Вамі дзеяньне дазволенае толькі ўдзельнікам з групы $1.',
'badaccess-group2' => 'Запытанае Вамі дзеяньне дазволенае толькі ўдзельнікам адной з групаў $1.',
'badaccess-groups' => 'Запытанае Вамі дзеяньне дазволенае толькі ўдзельнікам адной з групаў $1.',

'ok'                  => 'Добра',
'pagetitle'           => '$1 — {{SITENAME}}',
'retrievedfrom'       => 'Атрымана з «$1»',
'youhavenewmessages'  => 'Вы маеце $1 ($2).',
'newmessageslink'     => 'новыя паведамленьні',
'newmessagesdifflink' => 'апошняя зьмена',
'editsection'         => 'рэдагаваць',
'editold'             => 'рэдагаваць',
'editsectionhint'     => 'Рэдагаваць сэкцыю «$1»',
'toc'                 => 'Зьмест',
'showtoc'             => 'паказаць',
'hidetoc'             => 'схаваць',
'thisisdeleted'       => 'Праглядзець ці аднавіць $1?',
'viewdeleted'         => 'Паказаць $1?',
'restorelink'         => 'выдаленыя зьмены ($1)',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Артыкул',
'nstab-user'      => 'Старонка ўдзельніка/ўдзельніцы',
'nstab-media'     => 'Мэдыя',
'nstab-special'   => 'Спэцыяльная',
'nstab-project'   => 'Старонка праекту',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Паведамленьне',
'nstab-template'  => 'Шаблён',
'nstab-help'      => 'Старонка дапамогі',
'nstab-category'  => 'Катэгорыя',

# Main script and global functions
'nosuchaction'      => 'Няма такога дзеяньня',
'nosuchspecialpage' => 'Такой спэцыяльнай старонкі не існуе',

# General errors
'error'                => 'Памылка',
'databaseerror'        => 'Памылка базы зьвестак',
'nodb'                 => 'Немагчыма выбраць базу зьвестак $1',
'cachederror'          => 'Наступнае — кэшаваная копія запытанай старонкі; магчыма, яна ўжо не адпавядае рэчаіснасьці.',
'internalerror'        => 'Унутраная памылка',
'internalerror_info'   => 'Унутраная памылка: $1',
'filecopyerror'        => 'Немагчыма cкапіяваць файл «$1» у «$2».',
'filerenameerror'      => 'Немагчыма перайменаваць файл «$1» у «$2».',
'filedeleteerror'      => 'Немагчыма выдаліць файл «$1».',
'directorycreateerror' => 'Немагчыма стварыць дырэкторыю «$1».',
'filenotfound'         => 'Немагчыма знайсьці файл «$1».',
'badarticleerror'      => 'Гэтае дзеяньне немагчыма выканаць на гэтай старонцы.',
'cannotdelete'         => 'Немагчыма выдаліць указаную старонку альбо выяву. (Магчыма, яна ўжо выдаленая кімсьці іншым.)',
'badtitle'             => 'Некарэктная назва',
'badtitletext'         => 'Запытаная назва старонкі няслушная ці пустая, альбо няслушна ўказаная міжмоўная ці міжвікі назва. Яна можа ўтрымліваць сымбалі, якія нельга ўжываць у назвах.',
'perfcached'           => 'Наступныя зьвесткі кэшаваныя і могуць быць састарэлымі.',
'perfcachedts'         => 'Наступныя зьвесткі кэшаваныя і апошні раз былі абноўленыя $1.',
'viewsource'           => 'Паказаць крыніцу',
'viewsourcefor'        => 'для $1',
'namespaceprotected'   => "Вы ня маеце правоў на рэдагаваньне старонак у прасторы назваў '''$1'''.",

# Login and logout pages
'yourname'                   => 'Імя ўдзельніка/ўдзельніцы:',
'yourpassword'               => 'Пароль:',
'yourpasswordagain'          => 'Паўтарыце пароль:',
'remembermypassword'         => 'Запомніць мяне на гэтым кампутары',
'login'                      => 'Увайсьці',
'userlogin'                  => 'Стварэньне рахунку ці ўваход',
'logout'                     => 'Выйсьці',
'userlogout'                 => 'Выйсьці',
'nologin'                    => 'Ня маеце рахунку? $1.',
'nologinlink'                => 'Стварыце рахунак',
'createaccount'              => 'Стварыць новы рахунак',
'gotaccount'                 => 'Ужо маеце рахунак? $1.',
'gotaccountlink'             => 'Увайдзіце',
'createaccountmail'          => 'па электроннай пошце',
'badretype'                  => 'Уведзеныя Вамі паролі не супадаюць.',
'userexists'                 => 'Выбранае Вамі імя ўдзельніка/ўдзельніцы ўжо выкарыстоўваецца кімсьці іншым. Калі ласка, выберыце іншае імя.',
'youremail'                  => 'Адрас электроннай пошты:',
'username'                   => 'Імя ўдзельніка/ўдзельніцы:',
'uid'                        => 'ID удзельніка/удзельніцы:',
'yourrealname'               => 'Сапраўднае імя:',
'yourlanguage'               => 'Мова інтэрфэйсу:',
'yournick'                   => 'Мянушка:',
'badsig'                     => 'Няслушны неапрацаваны подпіс; праверце HTML-тэгі.',
'badsiglength'               => 'Мянушка занадта доўгая; мусіць быць не даўжэй за $1 сымбаляў.',
'email'                      => 'Электронная пошта',
'loginerror'                 => 'Памылка ўваходу',
'prefs-help-email'           => 'Адрас электроннай пошты — неабавязковы, але ён дазваляе іншым зьвязвацца з Вамі
праз старонкі ўдзельніка безь неабходнасьці раскрыцьця Вашых асабістых зьвестак.',
'nosuchuser'                 => 'Не існуе ўдзельніка ці ўдзельніцы «$1».
Праверце напісаньне, альбо выкарыстайце форму ніжэй, каб стварыць новы рахунак ўдзельніка ці ўдзельніцы.',
'nosuchusershort'            => 'Не існуе ўдзельніка ці ўдзельніцы «$1». Праверце напісаньне.',
'nouserspecified'            => 'Вы мусіце пазначыць імя ўдзельніка.',
'mailmypassword'             => 'Даслаць мне пароль',
'blocked-mailpassword'       => 'З Вашага IP-адраса забароненыя рэдагаваньні, а таму таксама не даступная функцыя аднаўленьня паролю дзеля прадухіленьня шкоды.',
'acct_creation_throttle_hit' => 'На жаль, Вы ўжо стварылі $1 рахункаў. Болей нельга.',
'emailauthenticated'         => 'Ваш адрас электроннай пошты быў пацьверджаны $1.',
'emailconfirmlink'           => 'Пацьвердзіць Ваш адрас электроннай пошты',
'accountcreated'             => 'Рахунак створаны',
'accountcreatedtext'         => 'Рахунак удзельніка для $1 быў створаны.',
'loginlanguagelabel'         => 'Мова: $1',

# Edit page toolbar
'bold_sample'   => 'Тлусты тэкст',
'bold_tip'      => 'Тлусты тэкст',
'link_tip'      => 'Унутраная спасылка',
'extlink_tip'   => 'Вонкавая спасылка (не забывайцеся пачынаць з http:// )',
'math_sample'   => 'Зьмясьціце тут формулу',
'math_tip'      => 'Матэматычная формула (LaTeX)',
'nowiki_sample' => 'Пішыце сюды нефарматаваны тэкст',
'nowiki_tip'    => 'Ігнараваць вікі-фарматаваньне',
'image_sample'  => 'Прыклад.jpg',
'media_sample'  => 'Прыклад.ogg',
'media_tip'     => 'Спасылка на мэдыя-файл',
'sig_tip'       => 'Ваш подпіс і момант часу',
'hr_tip'        => 'Гарызантальная лінія (не выкарыстоўвайце часта)',

# Edit pages
'summary'               => 'Кароткае апісаньне зьменаў',
'subject'               => 'Тэма/назва',
'minoredit'             => 'Гэта дробная праўка',
'watchthis'             => 'Назіраць за гэтай старонкай',
'savearticle'           => 'Захаваць старонку',
'preview'               => 'Прагляд',
'showpreview'           => 'Праглядзець',
'showdiff'              => 'Паказаць зьмены',
'anoneditwarning'       => "'''Папярэджаньне:''' Вы не ўвайшлі. Ваш IP-адрас будзе запісаны ў гісторыі гэтай старонкі.",
'blockedtitle'          => 'Удзельнік заблякаваны',
'blockedtext'           => "<big>'''Ваш рахунак ўдзельніка ці IP-адрас быў заблякаваны $1.'''</big>

Прычына гэтага: ''$2''.

Блякіроўка пачалася $8 і дзейнічае да $6 і выкарыстоўваецца супраць дзеяньняў $7.

Вы можаце скантактавацца з $1 ці адным зь іншых [[{{MediaWiki:Grouppage-sysop}}|адміністратараў]], каб абмеркаваць блякаваньне. Заўважце, што Вы ня зможаце ўжыць мажлівасьць «даслаць ліст па электроннай пошце», пакуль не пазначыце сапраўдны адрас электроннай пошты ў Вашых [[Special:Preferences|устаноўках]], што Вам не забаронена. Ваш IP-адрас — $3, ідэнтыфікатар блякіроўкі — #$5. Калі ласка, улучайце гэтую інфармацыю ва ўсе запыты, што Вы будзеце рабіць.",
'autoblockedtext'       => "Ваш IP-адрас быў аўтаматычна заблякаваны, таму што ён ужываўся іншым удзельнікам, які быў заблякаваны $1.

Прычына гэтага: ''$2''.

Блякіроўка пачалася $8 і дзейнічае да $6.

Вы можаце скантактавацца з $1 ці з адным зь іншых [[{{MediaWiki:Grouppage-sysop}}|адміністратараў]], каб абмеркаваць блякаваньне.

Заўважце, што вы ня зможаце ужываць мажлівасьць «даслаць ліст праз электронную пошту», пакуль ня будзе пазначаны дзейны адрас электроннай пошты ў вашых [[Special:Preferences|устаноўках удзельніка]], што Вам не забаронена.

ID вашага блякаваньня — #$5. Калі ласка, улучайце гэты ID ва ўсе запыты, якія вы робіце.",
'blockedoriginalsource' => "Крыніца '''$1''' паказана ніжэй:",
'blockededitsource'     => "Тэкст '''Вашых зьменаў''' у '''$1''' паказаны ніжэй:",
'whitelistacctitle'     => 'Вам не дазволена ствараць рахунак',
'nosuchsectiontitle'    => 'Такой сэкцыі не існуе',
'accmailtitle'          => 'Пароль адасланы.',
'accmailtext'           => 'Пароль для «$1» быў адасланы на адрас $2.',
'newarticle'            => '(Новы)',
'newarticletext'        => "Вы прыйшлі па спасылцы на старонку, якая яшчэ не існуе.
Каб стварыць яе, напішыце тэкст у полі ніжэй (глядзіце [[{{MediaWiki:Helppage}}|старонку дапамогі]] дзеля дадатковай інфармацыі).
Калі Вы трапілі сюды памылкова, націсьніце '''назад''' у вашым браўзэры.",
'anontalkpagetext'      => "----''Гэта старонка гутарак ананімнага ўдзельніка, які яшчэ не стварыў сабе рахунак ці не ўжывае яго. Таму мы вымушаныя ўжываць лічбавы IP-адрас дзеля ягонай ідэнтыфікацыі. Адзін IP-адрас можа выкарыстоўвацца некалькімі ўдзельнікамі. Калі Вы — ананімны ўдзельнік і лічыце, што атрымалі не прызначаныя Вам камэнтары, калі ласка, [[Special:Userlogin|зарэгіструйцеся ці ўвайдзіце ў сыстэму]], каб у будучыні пазьбегнуць магчымай блытаніны зь іншымі ананімнымі ўдзельнікамі.''

''This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical IP address to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.''",
'noarticletext'         => 'Зараз тэкст на гэтай старонцы адсутнічае. Вы можаце [[Special:Search/{{PAGENAME}}|пашукаць гэтую назву]] ў іншых старонках альбо [{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} рэдагаваць гэтую старонку].',
'note'                  => '<strong>Заўвага: </strong>',
'previewnote'           => '<strong>Гэта толькі папярэдні прагляд і зьмены яшчэ не былі захаваныя!</strong>',
'editing'               => 'Рэдагаваньне: $1',
'editingsection'        => 'Рэдагаваньне: $1 (сэкцыя)',
'editingcomment'        => 'Рэдагаваньне: $1 (камэнтар)',
'editconflict'          => 'Канфлікт рэдагаваньня: $1',
'yourtext'              => 'Ваш тэкст',
'storedversion'         => 'Захаваная вэрсія',
'copyrightwarning'      => '<strong>НЕЛЬГА БЕЗ [[{{MediaWiki:Copyrightpage}}|ДАЗВОЛУ]] ДАДАВАЦЬ МАТЭРЫЯЛЫ, АБАРОНЕНЫЯ АЎТАРСКІМ ПРАВАМ!</strong>',
'protectedpagewarning'  => '<strong>ПАПЯРЭДЖАНЬНЕ:  Гэтая старонка была абароненая, таму толькі адміністратары могуць рэдагаваць яе. Упэўніцеся, што Вы кіруецеся [[Special:Protectedpages|правіламі рэдагаваньня абароненых старонак]].</strong>',
'templatesused'         => 'На гэтай старонцы выкарыстаныя наступныя шаблёны:',
'templatesusedpreview'  => 'У гэтым праглядзе выкарыстаныя наступныя шаблёны:',
'templatesusedsection'  => 'У гэтай сэкцыі выкарыстаныя наступныя шаблёны:',
'template-protected'    => '(абаронены)',

# Account creation failure
'cantcreateaccounttitle' => 'Немагчыма стварыць рахунак',
'cantcreateaccounttext'  => 'Стварэньне рахунку з гэтага IP-адрасу (<b>$1</b>) было заблякаванае. Гэта магчыма праз сталы вандалізм з вашай навучальнай установы ці правайдэру інтэрнэт-паслугаў.',

# History pages
'revhistory'          => 'Гісторыя зьменаў',
'viewpagelogs'        => 'Паказаць журналы падзей для гэтай старонкі',
'nohistory'           => 'Гісторыя зьменаў для гэтай старонкі адсутнічае.',
'revnotfound'         => 'Вэрсія ня знойдзеная',
'loadhist'            => 'Загрузка гісторыі старонкі',
'currentrev'          => 'Бягучая вэрсія',
'revisionasof'        => 'Вэрсія ад $1',
'currentrevisionlink' => 'Бягучая вэрсія',
'cur'                 => 'бяг',
'orig'                => 'арыг',
'deletedrev'          => '[выдаленая]',

# Revision feed
'history-feed-title'       => 'Гісторыя зьменаў',
'history-feed-description' => 'Гісторыя зьменаў гэтай старонкі',

# Diffs
'difference'              => '(Адрозьненьні паміж вэрсіямі)',
'loadingrev'              => 'Загрузка вэрсіі для параўнаньня',
'lineno'                  => 'Радок $1:',
'editcurrent'             => 'Рэдагаваць бягучую вэрсію гэтай старонкі',
'compareselectedversions' => 'Параўнаць выбраныя вэрсіі',

# Search results
'searchresults'    => 'Вынікі пошуку',
'searchresulttext' => 'Для атрыманьня больш падрабязнай інфармацыі пра пошук у {{GRAMMAR:месны|{{SITENAME}}}}, глядзіце [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'noexactmatch'     => "'''Старонкі з гэткай назвай не існуе.''' Вы можаце '''[[:$1|стварыць гэтую старонку]]'''.",
'prevn'            => 'папярэднія $1',
'nextn'            => 'наступныя $1',
'viewprevnext'     => 'Паказаць ($1) ($2) ($3)',
'showingresults'   => 'Ніжэй паданыя да <b>$1</b> вынікаў, пачынаючы з #<b>$2</b>.',
'powersearch'      => 'Пошук',

# Preferences page
'preferences'             => 'Устаноўкі',
'mypreferences'           => 'Мае ўстаноўкі',
'prefs-edits'             => 'Колькасьць рэдагаваньняў:',
'qbsettings-none'         => 'Не паказваць',
'qbsettings-fixedleft'    => 'Замацаваная зьлева',
'qbsettings-fixedright'   => 'Замацаваная справа',
'qbsettings-floatingleft' => 'Рухомая зьлева',
'changepassword'          => 'Зьмяніць пароль',
'skin'                    => 'Афармленьне',
'dateformat'              => 'Фармат даты',
'datetime'                => 'Дата і час',
'math_unknown_error'      => 'невядомая памылка',
'math_unknown_function'   => 'невядомая функцыя',
'math_syntax_error'       => 'сынтаксычная памылка',
'prefs-rc'                => 'Апошнія зьмены',
'prefs-watchlist'         => 'Сьпіс назіраньня',
'prefs-misc'              => 'Рознае',
'saveprefs'               => 'Захаваць',
'resetprefs'              => 'Скінуць',
'oldpassword'             => 'Стары пароль:',
'newpassword'             => 'Новы пароль:',
'retypenew'               => 'Паўтарыце новы пароль:',
'textboxsize'             => 'Рэдагаваньне',
'rows'                    => 'Радкоў:',
'columns'                 => 'Слупкоў:',
'searchresultshead'       => 'Пошук',
'resultsperpage'          => 'Колькасьць вынікаў на старонцы',
'savedprefs'              => 'Вашыя ўстаноўкі былі захаваныя.',
'timezonelegend'          => 'Часавы пояс',
'localtime'               => 'Мясцовы час',
'servertime'              => 'Бягучы час на сэрвэры',
'guesstimezone'           => 'Запоўніць з браўзэра',
'allowemail'              => 'Дазволіць атрыманьне лістоў ад іншых удзельнікаў і ўдзельніц',
'defaultns'               => 'Па змоўчаньні, шукаць у наступных прасторах назваў:',
'default'                 => 'па змоўчаньні',
'files'                   => 'Файлы',

# User rights
'userrights-user-editname' => 'Увядзіце імя ўдзельніка:',
'editusergroup'            => 'Рэдагаваць групы ўдзельнікаў і ўдзельніц',
'saveusergroups'           => 'Захаваць групы ўдзельнікаў і ўдзельніц',
'userrights-reason'        => 'Прычына зьмены:',

# Groups
'group'            => 'Група:',
'group-bot'        => 'Робаты',
'group-sysop'      => 'Адміністрацыя',
'group-bureaucrat' => 'Бюракраты',
'group-all'        => '(усе)',

'group-bot-member'        => 'Робат',
'group-sysop-member'      => 'Адміністратар/Адміністратарка',
'group-bureaucrat-member' => 'Бюракрат',

'grouppage-bot'   => '{{ns:project}}:Робаты',
'grouppage-sysop' => '{{ns:project}}:Адміністрацыя',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|зьмена|зьмены|зьменаў}}',
'recentchanges'                     => 'Апошнія зьмены',
'recentchangestext'                 => 'Сачыце за апошнімі зьменамі ў {{GRAMMAR:месны|{{SITENAME}}}} на гэтай старонцы.',
'rcnote'                            => 'Ніжэй пададзеныя апошнія <strong>$1</strong> зьменаў у апошнія <strong>$2</strong> дзён, па стане на $3.',
'rcnotefrom'                        => 'Ніжэй знаходзяцца зьмены з <b>$2</b> (да <b>$1</b> на старонку).',
'rclistfrom'                        => 'Паказаць зьмены з $1',
'rcshowhideminor'                   => '$1 дробныя праўкі',
'rcshowhidebots'                    => '$1 робатаў',
'rcshowhideliu'                     => '$1 зарэгістраваных',
'rcshowhideanons'                   => '$1 ананімаў',
'rcshowhidemine'                    => '$1 мае праўкі',
'rclinks'                           => 'Паказаць апошнія $1 зьменаў за мінулыя $2 дзён<br />$3',
'diff'                              => 'розьн',
'hist'                              => 'гіст',
'hide'                              => 'схаваць',
'show'                              => 'паказаць',
'minoreditletter'                   => 'д',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'р',
'number_of_watching_users_pageview' => '[Колькасьць назіральнікаў: $1]',
'newsectionsummary'                 => '/* $1 */ новая сэкцыя',

# Recent changes linked
'recentchangeslinked' => 'Зьвязаныя праўкі',

# Upload
'upload'              => 'Загрузіць файл',
'uploadbtn'           => 'Загрузіць файл',
'reupload'            => 'Загрузіць зноў',
'reuploaddesc'        => 'Вярнуцца да формы загрузкі.',
'uploadtext'          => "'''Перад тым, як загрузіць файл:'''

* Азнаёмцеся з '''[[{{MediaWiki:Policy-url}}|правіламі выкарыстаньня файлаў]]'''.
* Праверце з дапамогай '''[[Special:Imagelist|сьпісу файлаў]]''', ці не загружаны гэты файл з іншай назвай.
* Выкарыстоўвайце наступныя '''фарматы''': JPG — для фотаздымкаў; GIF — для анімацыі; PNG — для іншых выяваў; OGG — для аўдыёфайлаў.
* Давайце файлам '''зразумелыя назвы''', якія адлюстроўваюць іх зьмест. Напрыклад: ''Janka Kupala, 1910.jpg'' замест ''JK1.jpg''. Назву файла '''немагчыма''' зьмяніць пасьля загрузкі.
* Пытайцеся '''дазволу''' на публікацыю фотаздымка ва ўсіх людзей, якія там прысутнічаюць.

'''Пасьля таго, як выява загружаная:'''

* '''Абавязкова''' дадайце:
** '''дэталёвае апісаньне зьместу''';
** '''крыніцу''': файл створаны Вамі; адсканаваны з кнігі ''X''; узяты з Інтэрнэт па адрасу ''Y'';
** для файлаў, якія зроблены '''ня''' Вамі, укажыце, ці атрымалі Вы '''дазвол''' на выкарыстаньне гэтага файла ў {{GRAMMAR:месны|{{SITENAME}}}};
** '''ліцэнзіі''', згодна ўмоваў якіх магчыма распаўсюджваць файл.
* '''Выкарыстоўвайце файл''' у артыкуле(ах). Напрыклад: <code><nowiki>[[</nowiki>{{ns:image}}:file.jpg<nowiki>]]</nowiki></code> ці <code><nowiki>[[</nowiki>{{ns:image}}:file.jpg<nowiki>|thumb|200px|Апісаньне]]</nowiki></code> — для выяваў; <code><nowiki>[[</nowiki>{{ns:media}}:file.ogg<nowiki>]]</nowiki></code> — для аўдыёфайлаў.",
'uploadlog'           => 'журнал загрузак',
'uploadlogpage'       => 'Журнал загрузак',
'uploadlogpagetext'   => 'Сьпіс апошніх загружаных файлаў.',
'filename'            => 'Назва файла',
'filedesc'            => 'Апісаньне',
'fileuploadsummary'   => 'Апісаньне:',
'filesource'          => 'Крыніца',
'uploadedfiles'       => 'Загружаныя файлы',
'ignorewarning'       => 'Праігнараваць папярэджаньне і захаваць файл.',
'illegalfilename'     => 'Назва файла «$1» зьмяшчае сымбалі, якія нельга выкарыстоўваць у назвах старонак. Калі ласка, зьмяніце назву файла і паспрабуйце загрузіць яго зноў.',
'badfilename'         => 'Назва файла была зьмененая на «$1».',
'savefile'            => 'Захаваць файл',
'uploadedimage'       => 'загружаная «[[$1]]»',
'overwroteimage'      => 'загружаная новая вэрсія «[[$1]]»',
'uploadvirus'         => 'Файл утрымлівае вірус! Падрабязнасьці: $1',
'watchthisupload'     => 'Назіраць за гэтай старонкай',
'filename-bad-prefix' => 'Назва файла, які Вы загружаеце, пачынаецца з <strong>«$1»</strong>. Падобныя бессэнсоўныя назвы звычайна ствараюцца аўтаматычна лічбавымі фотаапаратамі. Калі ласка, абярыце больш зразумелую назву для Вашага файла.',

'license'           => 'Ліцэнзія',
'nolicense'         => 'Не выбраная',
'license-nopreview' => '(Прагляд недаступны)',

# Image list
'imagelist'             => 'Сьпіс файлаў',
'imagelisttext'         => 'Сьпіс <strong>$1</strong> файлаў, адсартаваных <strong>$2</strong>.',
'getimagelist'          => 'атрыманьне сьпісу файлаў',
'ilsubmit'              => 'Шукаць',
'showlast'              => 'Паказаць $1 апошніх файлаў адсартаваных $2.',
'byname'                => 'па назьве',
'bydate'                => 'па даце',
'bysize'                => 'па памеры',
'imgdelete'             => 'выдаліць',
'imgdesc'               => 'апісаньне',
'imgfile'               => 'файл',
'filehist'              => 'Гісторыя файла',
'filehist-deleteall'    => 'выдаліць усе',
'filehist-deleteone'    => 'выдаліць гэтую',
'filehist-revert'       => 'вярнуць',
'filehist-current'      => 'бягучая',
'filehist-datetime'     => 'Дата і час',
'filehist-user'         => 'Удзельнік',
'filehist-dimensions'   => 'Памеры',
'filehist-filesize'     => 'Памер файла',
'filehist-comment'      => 'Камэнтар',
'imagelinks'            => 'Спасылкі',
'linkstoimage'          => 'Наступныя старонкі спасылаюцца на гэты файл:',
'nolinkstoimage'        => 'Ніводная старонка не спасылаецца на гэты файл.',
'sharedupload'          => 'Гэты файл зьяўляецца агульным і можа выкарыстоўвацца іншымі праектамі.',
'imagelist_date'        => 'Дата',
'imagelist_name'        => 'Назва',
'imagelist_user'        => 'Удзельнік',
'imagelist_size'        => 'Памер',
'imagelist_description' => 'Апісаньне',

# File reversion
'filerevert'                => 'Вярнуць $1',
'filerevert-comment'        => 'Камэнтар:',
'filerevert-defaultcomment' => 'Вернутая вэрсія ад $2, $1',
'filerevert-submit'         => 'Вярнуць',

# File deletion
'filedelete'         => 'Выдаліць $1',
'filedelete-legend'  => 'Выдаліць файл',
'filedelete-intro'   => "Вы выдаляеце '''[[Media:$1|$1]]'''.",
'filedelete-comment' => 'Камэнтар:',
'filedelete-submit'  => 'Выдаліць',
'filedelete-success' => "'''$1''' выдалены.",

# MIME search
'mimesearch' => 'Пошук па MIME',
'mimetype'   => 'Тып MIME:',

# Unwatched pages
'unwatchedpages' => 'Старонкі, за якімі ніхто не назірае',

# List redirects
'listredirects' => 'Сьпіс перанакіраваньняў',

# Unused templates
'unusedtemplates'    => 'Шаблёны, якія не выкарыстоўваюцца',
'unusedtemplateswlh' => 'іншыя спасылкі',

# Random redirect
'randomredirect' => 'Выпадковае перанакіраваньне',

# Statistics
'statistics'    => 'Статыстыка',
'sitestats'     => 'Статыстыка {{GRAMMAR:родны|{{SITENAME}}}}',
'userstats'     => 'Статыстыка ўдзелу',
'userstatstext' => "Колькасьць [[Special:Listusers|зарэгістраваных удзельнікаў і ўдзельніц]]: '''$1'''.

Колькасьць адміністратараў і адміністратарак: '''$2''' (ці '''$4%''').",

'disambiguations'     => 'Старонкі-неадназначнасьці',
'disambiguationspage' => 'Шаблён:Неадназначнасьць',

'doubleredirects' => 'Двайныя перанакіраваньні',

'brokenredirects'        => 'Некарэктныя перанакіраваньні',
'brokenredirectstext'    => 'Наступныя перанакіраваньні спасылаюцца на неіснуючыя старонкі:',
'brokenredirects-edit'   => '(рэдагаваць)',
'brokenredirects-delete' => '(выдаліць)',

'withoutinterwiki' => 'Старонкі без спасылак на іншыя моўныя вэрсіі',

'fewestrevisions' => 'Старонкі з найменшай колькасьцю рэдагаваньняў',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт|байта|байтаў}}',
'ncategories'             => '$1 {{PLURAL:$1|катэгорыя|катэгорыі|катэгорый}}',
'nlinks'                  => '$1 {{PLURAL:$1|спасылка|спасылкі|спасылак}}',
'nmembers'                => '$1 {{PLURAL:$1|элемэнт|элемэнты|элемэнтаў}}',
'nrevisions'              => '$1 {{PLURAL:$1|вэрсія|вэрсіі|вэрсій}}',
'nviews'                  => '$1 {{PLURAL:$1|прагляд|прагляды|праглядаў}}',
'specialpage-empty'       => 'Гэтая старонка — пустая.',
'lonelypages'             => 'Старонкі-сіраціны',
'uncategorizedpages'      => 'Некатэгарызаваныя старонкі',
'uncategorizedcategories' => 'Некатэгарызаваныя катэгорыі',
'uncategorizedimages'     => 'Некатэгарызаваныя файлы',
'uncategorizedtemplates'  => 'Некатэгарызаваныя шаблёны',
'unusedcategories'        => 'Катэгорыі, якія не выкарыстоўваюцца',
'unusedimages'            => 'Файлы, якія не выкарыстоўваюцца',
'popularpages'            => 'Папулярныя старонкі',
'wantedcategories'        => 'Запатрабаваныя катэгорыі',
'wantedpages'             => 'Запатрабаваныя старонкі',
'mostlinked'              => 'Старонкі, на якія найчасьцей спасылаюцца',
'mostlinkedcategories'    => 'Катэгорыі з найбольшай колькасьцю артыкулаў',
'mostlinkedtemplates'     => 'Шаблёны, якія найчасьцей выкарыстоўваюцца',
'mostcategories'          => 'Старонкі з найбольшай колькасьцю катэгорый',
'mostimages'              => 'Выявы, на якія найчасьцей спасылаюцца',
'mostrevisions'           => 'Старонкі з найбольшай колькасьцю рэдагаваньняў',
'allpages'                => 'Усе старонкі',
'prefixindex'             => 'Пошук старонак па пачатку назвы',
'randompage'              => 'Выпадковая старонка',
'shortpages'              => 'Кароткія старонкі',
'longpages'               => 'Доўгія старонкі',
'deadendpages'            => 'Тупіковыя старонкі',
'protectedpages'          => 'Абароненыя старонкі',
'listusers'               => 'Сьпіс удзельнікаў і ўдзельніц',
'specialpages'            => 'Спэцыяльныя старонкі',
'spheading'               => 'Спэцыяльныя старонкі для ўсіх удзельнікаў і ўдзельніц',
'restrictedpheading'      => 'Спэцыяльныя старонкі з абмежаваным доступам',
'rclsub'                  => '(да старонак, спасылкі на якія ёсьць на «$1»)',
'newpages'                => 'Новыя старонкі',
'newpages-username'       => 'Імя ўдзельніка/ўдзельніцы:',
'ancientpages'            => 'Найстарэйшыя старонкі',
'move'                    => 'Перанесьці',
'movethispage'            => 'Перанесьці гэтую старонку',

# Book sources
'booksources'               => 'Пошук кніг',
'booksources-search-legend' => 'Пошук кніг',
'booksources-go'            => 'Паказаць',
'booksources-text'          => 'Ніжэй знаходзіцца сьпіс спасылак на іншыя сайты, якія прадаюць новыя і патрыманыя кнігі, і могуць таксама мець інфармацыю пра кнігі, якія вы шукаеце:',

'categoriespagetext' => 'У {{GRAMMAR:месны|{{SITENAME}}}} існуюць наступныя катэгорыі:',
'data'               => 'Зьвесткі',
'alphaindexline'     => 'ад $1 да $2',
'version'            => 'Вэрсія',

# Special:Log
'specialloguserlabel'  => 'Удзельнік/удзельніца:',
'speciallogtitlelabel' => 'Назва:',
'log'                  => 'Журналы падзей',
'all-logs-page'        => 'Усе журналы падзей',
'alllogstext'          => 'Сумесны паказ журналаў загрузкі, выдаленьня, абароны, блякаваньня і адміністраваньня.
Вы можаце адфільтраваць вынікі па тыпе журналу, удзельніку ці старонцы.',

# Special:Allpages
'nextpage'          => 'Наступная старонка ($1)',
'prevpage'          => 'Папярэдняя старонка ($1)',
'allpagesfrom'      => 'Паказаць старонкі, пачынаючы з:',
'allarticles'       => 'Усе артыкулы',
'allinnamespace'    => 'Усе старонкі (прастора назваў: $1)',
'allnotinnamespace' => 'Усе старонкі (не ў прасторы назваў $1)',
'allpagesprev'      => 'Папярэднія',
'allpagesnext'      => 'Наступныя',
'allpagessubmit'    => 'Паказаць',
'allpagesprefix'    => 'Паказаць старонкі, назвы якіх пачынаюцца з:',
'allpagesbadtitle'  => 'Пададзеная назва старонкі была няслушная ці пачыналася зь міжмоўнай ці міжвікі спасылкі. Яна яшчэ можа ўтрымліваць сымбалі, якія ня могуць ужывацца ў назвах.',
'allpages-bad-ns'   => '{{SITENAME}} ня мае прасторы назваў «$1».',

# Special:Listusers
'listusers-submit' => 'Паказаць',

# E-mail user
'emailuser'       => 'Даслаць ліст па электроннай пошце',
'emailpage'       => 'Даслаць ліст ўдзельніку ці ўдзельніцы па электроннай пошце',
'defemailsubject' => 'Электронная пошта {{GRAMMAR:родны|{{SITENAME}}}}',
'noemailtitle'    => 'Адрас электроннай пошты адсутнічае',
'emailfrom'       => 'Ад',
'emailto'         => 'Каму',
'emailsubject'    => 'Тэма',
'emailmessage'    => 'Паведамленьне',
'emailsend'       => 'Даслаць',

# Watchlist
'watchlist'            => 'Мой сьпіс назіраньня',
'mywatchlist'          => 'Мой сьпіс назіраньня',
'watchlistfor'         => "(для '''$1''')",
'nowatchlist'          => 'Ваш сьпіс назіраньня — пусты.',
'addedwatch'           => 'Дададзеная ў сьпіс назіраньня',
'addedwatchtext'       => "Артыкул «$1» быў дададзены да Вашага [[Special:Watchlist|сьпісу назіраньня]].
Наступныя зьмены ў гэтым артыкуле і зьвязанай зь ім старонцы абмеркаваньняў будуць бачныя там, і старонка будзе выглядаць '''тлустай''' на [[Special:Recentchanges|старонцы са сьпісам апошніх зьменаў]], каб зьмены было лягчэй заўважыць.

Калі Вы захочаце выдаліць артыкул са сьпісу назіраньня, націсьніце «не назіраць» у спэцыяльным радку зьверху артыкула.",
'removedwatch'         => 'Выдаленая са сьпісу назіраньня',
'removedwatchtext'     => 'Старонка «[[:$1]]» была выдаленая з Вашага сьпісу назіраньня.',
'watch'                => 'Назіраць',
'watchthispage'        => 'Назіраць за гэтай старонкай',
'unwatch'              => 'Не назіраць',
'unwatchthispage'      => 'Перастаць назіраць',
'notanarticle'         => 'Не артыкул',
'watchlistcontains'    => 'Ваш сьпіс назіраньня зьмяшчае $1 {{PLURAL:$1|старонка|старонкі|старонак}}.',
'watchlist-show-bots'  => 'паказаць праўкі робатаў',
'watchlist-hide-bots'  => 'схаваць праўкі робатаў',
'watchlist-show-own'   => 'паказаць мае праўкі',
'watchlist-hide-own'   => 'схаваць мае праўкі',
'watchlist-show-minor' => 'паказаць дробныя праўкі',
'watchlist-hide-minor' => 'схаваць дробныя праўкі',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Дадаецца ў сьпіс назіраньня...',
'unwatching' => 'Выдаляецца са сьпісу назіраньня...',

'enotif_newpagetext' => 'Гэта новая старонка.',
'changed'            => 'зьмененая',
'created'            => 'створаная',

# Delete/protect/revert
'deletepage'       => 'Выдаліць старонку',
'confirm'          => 'Пацьверджаньне',
'excontent'        => 'колішні зьмест: «$1»',
'exblank'          => 'старонка была пустая',
'confirmdelete'    => 'Пацьверджаньне выдаленьня',
'deletesub'        => '(Выдаленьне «$1»)',
'historywarning'   => 'Папярэджаньне: у старонкі, якую Вы зьбіраецеся выдаліць, ёсьць гісторыя:',
'actioncomplete'   => 'Дзеяньне завершанае',
'deletedarticle'   => 'выдаленая «[[$1]]»',
'dellogpage'       => 'Журнал выдаленьняў',
'dellogpagetext'   => 'Сьпіс апошніх выдаленьняў.',
'deletionlog'      => 'журнал выдаленьняў',
'deletecomment'    => 'Прычына выдаленьня',
'rollback'         => 'Адмяніць рэдагаваньні',
'cantrollback'     => 'Немагчыма скасаваць зьмену; апошні рэдактар — адзіны аўтар гэтай старонкі.',
'alreadyrolled'    => 'Немагчыма скасаваць апошнюю зьмену [[:$1]], якую зрабіў [[User:$2|$2]] ([[User talk:$2|гутаркі]]); нехта іншы ўжо зьмяніў артыкул ці скасаваў зьмены.

Апошнія зьмены зробленыя [[User:$3|$3]] ([[User talk:$3|гутаркі]]).',
'protectlogpage'   => 'Журнал абаронаў',
'protectedarticle' => 'абароненая «[[$1]]»',
'protectsub'       => '(Абарона «$1»)',
'confirmprotect'   => 'Пацьверджаньне абароны',
'protectcomment'   => 'Камэнтар:',
'protect-default'  => '(па змоўчаньні)',
'minimum-size'     => 'Мінімальны памер',
'maximum-size'     => 'Максымальны памер',
'pagesize'         => '(у байтах)',

# Restrictions (nouns)
'restriction-edit' => 'Рэдагаваньне',
'restriction-move' => 'Перанос',

# Restriction levels
'restriction-level-sysop'         => 'поўнасьцю абароненая',
'restriction-level-autoconfirmed' => 'часткова абароненая',

# Undelete
'undelete'               => 'Прагляд выдаленых старонак',
'undeletepage'           => 'Прагляд і аднаўленьне выдаленых старонак',
'viewdeletedpage'        => 'Паказаць выдаленыя старонкі',
'undeletecomment'        => 'Камэнтар:',
'cannotundelete'         => 'Аднаўленьне не адбылося; нехта іншы мог пасьпець аднавіць старонку раней.',
'undelete-search-box'    => 'Пошук выдаленых старонак',
'undelete-search-prefix' => 'Паказаць старонкі, назвы якіх пачынаюцца з:',
'undelete-search-submit' => 'Паказаць',

# Namespace form on various pages
'namespace'      => 'Прастора назваў:',
'invert'         => 'Адваротны выбар',
'blanknamespace' => 'Артыкул',

# Contributions
'contributions' => 'Унёсак удзельніка/удзельніцы',
'mycontris'     => 'Мой унёсак',
'contribsub2'   => 'Для $1 ($2)',
'uctop'         => ' (апошняя)',

'sp-contributions-blocklog' => 'журнал блякаваньняў',
'sp-contributions-username' => 'IP-адрас альбо імя ўдзельніка/ўдзельніцы:',

# What links here
'whatlinkshere'       => 'Адкуль спасылаюцца на старонку',
'whatlinkshere-title' => 'Старонкі, якія спасылаюцца на $1',
'linklistsub'         => '(Сьпіс спасылак)',
'linkshere'           => "Наступныя старонкі спасылаюцца на '''[[:$1]]''':",
'nolinkshere'         => "Ніводная старонка не спасылаецца на '''[[:$1]]'''.",
'nolinkshere-ns'      => "Ніводная старонка не спасылаецца на '''[[:$1]]''' з выбранай прасторы назваў.",
'isredirect'          => 'старонка-перанакіраваньне',
'istemplate'          => 'уключэньне',
'whatlinkshere-links' => '← спасылкі',

# Block/unblock
'blockip'                     => 'Блякаваньне ўдзельніка ці ўдзельніцы',
'blockiptext'                 => 'Ужывайце форму ніжэй, каб заблякаваць доступ для запісу з пэўнага IP-адрасу ці імя ўдзельніка. Гэта трэба рабіць толькі прадухіленьня вандалізму і згодна з [[{{MediaWiki:Policy-url}}|правіламі]]. Запоўніце ніжэй пэўную прычыну (напрыклад, пералічыце асобныя старонкі, на якіх былі парушэньні).',
'ipaddress'                   => 'IP-адрас:',
'ipadressorusername'          => 'IP-адрас альбо імя ўдзельніка/ўдзельніцы:',
'ipbexpiry'                   => 'Тэрмін:',
'ipbreason'                   => 'Прычына:',
'ipbreasonotherlist'          => 'Іншая прычына',
'ipbanononly'                 => 'Блякаваць толькі ананімаў',
'ipbcreateaccount'            => 'Забараніць стварэньне рахункаў',
'ipbother'                    => 'Іншы тэрмін:',
'ipboptions'                  => '2 гадзіны:2 hours,1 дзень:1 day,3 дні:3 days,1 тыдзень:1 week,2 тыдні:2 weeks,1 месяц:1 month,3 месяцы:3 months,6 месяцаў:6 months,1 год:1 year,назаўсёды:infinite',
'ipbotheroption'              => 'іншы',
'badipaddress'                => 'Некарэктны IP-адрас',
'blockipsuccesssub'           => 'Блякаваньне пасьпяховае',
'blockipsuccesstext'          => '[[Special:Contributions/$1|$1]] быў заблякаваны/была заблякаваная.
<br />Глядзіце [[Special:Ipblocklist|сьпіс заблякаваных IP-адрасоў]] дзеля перагляду блякаваньняў.',
'ipb-edit-dropdown'           => 'Рэдагаваць прычыны блякіровак',
'ipb-unblock-addr'            => 'Разблякаваць $1',
'ipb-unblock'                 => 'Разблякаваць рахунак ўдзельніка ці IP-адрас',
'ipb-blocklist-addr'          => 'Паказаць існуючыя блякаваньні для $1',
'ipb-blocklist'               => 'Паказаць існуючыя блякаваньні',
'ipusubmit'                   => 'Разблякаваць гэты IP-адрас',
'ipblocklist'                 => 'Сьпіс заблякаваных IP-адрасоў і імёнаў удзельнікаў',
'blocklistline'               => '$1, $2 заблякаваў $3 ($4)',
'infiniteblock'               => 'назаўсёды',
'anononlyblock'               => 'толькі ананімаў',
'createaccountblock'          => 'стварэньне рахунку заблякаванае',
'blocklink'                   => 'заблякаваць',
'unblocklink'                 => 'разблякаваць',
'contribslink'                => 'унёсак',
'autoblocker'                 => "Вы аўтаматычна заблякаваныя, таму што Ваш IP-адрас нядаўна ўжываўся «[[User:$1|$1]]». Прычына блякаваньня $1 наступная: «'''$2'''»",
'blocklogpage'                => 'Журнал блякаваньняў',
'blocklogentry'               => 'заблякаваны [[$1]] на тэрмін: $2 $3',
'blocklogtext'                => 'Гэта журнал уліку блякаваньняў і разблякаваньняў удзельнікаў. Аўтаматычна заблякаваныя IP-адрасы не пазначаныя. Глядзіце [[Special:Ipblocklist|сьпіс заблякаваных IP-адрасоў]], каб пабачыць дзейныя ў гэты момант блякаваньні.',
'unblocklogentry'             => 'разблякаваны $1',
'block-log-flags-anononly'    => 'толькі ананімныя ўдзельнікі',
'block-log-flags-nocreate'    => 'стварэньне рахункаў забароненае',
'block-log-flags-noautoblock' => 'аўтаматычнае блякаваньне адключанае',
'block-log-flags-noemail'     => 'электронная пошта заблякаваная',
'ipb_already_blocked'         => '«$1» ужо заблякаваны',
'ip_range_invalid'            => 'Некарэктны дыяпазон IP-адрасоў.',

# Move page
'movepage'                => 'Перанесьці старонку',
'movearticle'             => 'Перанесьці старонку:',
'newtitle'                => 'Новая назва:',
'move-watch'              => 'Назіраць за гэтай старонкай',
'movepagebtn'             => 'Перанесьці старонку',
'movepage-moved'          => "<big>'''«$1» перанесеная ў «$2»'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Старонка з такой назвай ужо існуе, альбо абраная Вамі назва недапушчальная. Калі ласка, абярыце іншую назву.',
'movedto'                 => 'перанесеная ў',
'movetalk'                => 'Перанесьці таксама старонку абмеркаваньня.',
'talkpagemoved'           => 'Адпаведная старонка абмеркаваньня таксама перанесеная.',
'talkpagenotmoved'        => 'Адпаведная старонка абмеркаваньня <strong>не</strong> перанесеная.',
'1movedto2'               => '[[$1]] перанесеная ў [[$2]]',
'1movedto2_redir'         => '[[$1]] перанесеная ў [[$2]] з выдаленьнем перанакіраваньня',
'movereason'              => 'Прычына:',
'delete_and_move'         => 'Выдаліць і перанесьці',
'delete_and_move_confirm' => 'Так, выдаліць старонку',

# Export
'export'            => 'Экспартаваць старонкі',
'exportcuronly'     => 'Экспартаваць толькі бягучую вэрсію, бяз поўнай гісторыі',
'export-addcattext' => 'Дадаць старонкі з катэгорыі:',
'export-addcat'     => 'Дадаць',

# Namespace 8 related
'allmessages'               => 'Сыстэмныя паведамленьні',
'allmessagesname'           => 'Назва',
'allmessagesdefault'        => 'Тэкст па змоўчаньні',
'allmessagescurrent'        => 'Бягучы тэкст',
'allmessagestext'           => 'Сьпіс усіх сыстэмных паведамленьняў, якія існуюць у прасторы назваў MediaWiki.',
'allmessagesnotsupportedDB' => "'''{{ns:Special}}:AllMessages''' не падтрымліваецца, таму што адключанае '''\$wgUseDatabaseMessages'''.",
'allmessagesfilter'         => 'Фільтар рэгулярных выразаў:',
'allmessagesmodified'       => 'Паказаць толькі зьмененыя',

# Thumbnails
'thumbnail-more' => 'Павялічыць',
'missingimage'   => '<b>Выява адсутнічае</b><br /><i>$1</i>',

# Special:Import
'import'        => 'Імпартаваць старонкі',
'importfailed'  => 'Немагчыма імпартаваць: $1',
'importsuccess' => 'Імпартаваньне скончанае!',

# Tooltip help for the actions
'tooltip-ca-protect'   => 'Абараніць гэтую старонку',
'tooltip-ca-delete'    => 'Выдаліць гэтую старонку',
'tooltip-ca-watch'     => 'Дадаць гэтую старонку ў Ваш сьпіс назіраньня',
'tooltip-ca-unwatch'   => 'Выдаліць гэтую старонку з Вашага сьпісу назіраньня',
'tooltip-p-logo'       => 'Галоўная старонка',
'tooltip-n-mainpage'   => 'Наведаць галоўную старонку',
'tooltip-n-randompage' => 'Паказаць выпадковую старонку',
'tooltip-t-emailuser'  => 'Даслаць ліст гэтаму ўдзельніку/гэтай удзельніцы',
'tooltip-t-print'      => 'Вэрсія гэтай старонкі для друку',
'tooltip-t-permalink'  => 'Сталая спасылка на гэтую вэрсію старонкі',
'tooltip-minoredit'    => 'Пазначыць гэтую зьмену як дробную',
'tooltip-save'         => 'Захаваць Вашы зьмены',
'tooltip-preview'      => 'Праглядзець Вашы зьмены. Калі ласка, выкарыстоўвайце гэтую магчымасьць перад тым, як захаваць старонку!',
'tooltip-diff'         => 'Паказаць зробленыя Вамі зьмены ў тэксьце.',
'tooltip-watch'        => 'Дадаць гэтую старонку ў Ваш сьпіс назіраньня',

# Attribution
'anonymous'        => 'Ананімныя ўдзельнікі і ўдзельніцы {{GRAMMAR:родны|{{SITENAME}}}}',
'siteuser'         => 'Удзельнік/удзельніца {{GRAMMAR:родны|{{SITENAME}}}} $1',
'lastmodifiedatby' => 'Гэтую старонку апошні раз рэдагаваў $3 $2, $1.', # $1 date, $2 time, $3 user
'and'              => 'і',
'others'           => 'іншыя',
'creditspage'      => 'Падзякі',

# Spam protection
'spamprotectiontitle'    => 'Фільтар для абароны ад спаму',
'subcategorycount'       => 'У гэтай катэгорыі ёсьць $1 {{PLURAL:$1|падкатэгорыя|падкатэгорыі|падкатэгорый}}.',
'categoryarticlecount'   => 'У гэтай катэгорыі ёсьць $1 {{PLURAL:$1|артыкул|артыкулы|артыкулаў}}.',
'category-media-count'   => 'У гэтай катэгорыі ёсьць $1 {{PLURAL:$1|файл|файлы|файлаў}}.',
'listingcontinuesabbrev' => ' (працяг)',

# Info page
'infosubtitle'   => 'Інфармацыя пра старонку',
'numedits'       => 'Колькасьць зьменаў (артыкула): $1',
'numtalkedits'   => 'Колькасьць зьменаў (старонкі абмеркаваньня): $1',
'numwatchers'    => 'Колькасьць назіральнікаў і назіральніц: $1',
'numauthors'     => 'Колькасьць розных аўтараў і аўтарак (артыкула): $1',
'numtalkauthors' => 'Колькасьць розных аўтараў і аўтарак (старонкі абмеркаваньня): $1',

# Math options
'mw_math_png'    => 'Заўсёды паказваць як PNG',
'mw_math_simple' => 'HTML у простых выпадках, інакш PNG',
'mw_math_html'   => 'HTML калі магчыма, інакш PNG',
'mw_math_source' => 'Пакідаць у выглядзе TeX (для тэкставых браўзэраў)',
'mw_math_modern' => 'Рэкамэндуецца для сучасных браўзэраў',
'mw_math_mathml' => 'MathML калі магчыма (экспэрымэнтальна)',

# Image deletion
'deletedrevision' => 'Выдаленая старая вэрсія $1',

# Browsing diffs
'previousdiff' => '← Перайсьці да папярэдняй зьмены',
'nextdiff'     => 'Перайсьці да наступнай зьмены →',

# Special:Newimages
'newimages' => 'Галерэя новых файлаў',
'noimages'  => 'Выявы адсутнічаюць.',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => 'с',
'minutes-abbrev' => 'хв',
'hours-abbrev'   => 'г',

# EXIF tags
'exif-imagewidth'         => 'Шырыня',
'exif-imagelength'        => 'Вышыня',
'exif-imagedescription'   => 'Назва выявы',
'exif-make'               => 'Вытворца фотаапарата',
'exif-model'              => 'Мадэль фотаапарата',
'exif-exifversion'        => 'Вэрсія Exif',
'exif-lightsource'        => 'Крыніца сьвятла',
'exif-flash'              => 'Успышка',
'exif-focallength-format' => '$1 мм',
'exif-gpslatitude'        => 'Шырата',
'exif-gpslongitude'       => 'Даўгата',

'exif-lightsource-0' => 'Невядомая',
'exif-lightsource-4' => 'Успышка',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'усе',
'namespacesall'    => 'усе',

# E-mail address confirmation
'confirmemail'           => 'Пацьвердзіць адрас электроннай пошты',
'confirmemail_sent'      => 'Ліст для пацьверджаньня дасланы.',
'confirmemail_invalid'   => 'Няслушны код пацьверджаньня. Магчыма, скончыўся яго тэрмін дзеяньня.',
'confirmemail_needlogin' => 'Вам трэба $1, каб пацьвердзіць Ваш адрас электроннай пошты.',
'confirmemail_success'   => 'Ваш адрас электроннай пошты быў пацьверджаны. Зараз Вы можаце ўвайсьці.',
'confirmemail_loggedin'  => 'Ваш адрас электроннай пошты быў пацьверджаны.',
'confirmemail_subject'   => '{{SITENAME}}: Пацьверджаньне адрасу электроннай пошты',
'confirmemail_body'      => 'Нехта, магчыма Вы з IP-адрасу $1, стварыў рахунак «$2» з гэтым паштовым адрасам у праекце {{SITENAME}}.

Каб пацьвердзіць, што гэты рахунак належыць Вам актывізаваць магчымасьці электроннай пошты ў {{GRAMMAR:месны|{{SITENAME}}}}, адчыніце гэтую спасылку у Вашым браўзэры:

$3

Калі гэта ня Вы, не адчыняйце спасылку. Код дзейнічае да $4.',

'unit-pixel' => 'пкс',

# action=purge
'confirm_purge'        => 'Ачысьціць кэш гэтай старонкі?

$1',
'confirm_purge_button' => 'Так',

# AJAX search
'articletitles' => "Артыкулы, якія пачынаюцца з ''$1''",
'hideresults'   => 'Схаваць вынікі',

# Multipage image navigation
'imgmultipageprev' => '← папярэдняя старонка',
'imgmultipagenext' => 'наступная старонка →',

# Table pager
'table_pager_next'  => 'Наступная старонка',
'table_pager_prev'  => 'Папярэдняя старонка',
'table_pager_first' => 'Першая старонка',
'table_pager_last'  => 'Апошняя старонка',

# Auto-summaries
'autosumm-blank'   => 'Зьмест старонкі выдалены цалкам',
'autosumm-replace' => "Старонка замененая на '$1'",
'autoredircomment' => 'Перанакіроўвае на [[$1]]',
'autosumm-new'     => 'Новая старонка: $1',

# Live preview
'livepreview-loading' => 'Загрузка…',

# Watchlist editor
'watchlistedit-normal-title' => 'Рэдагаваць сьпіс назіраньня',
'watchlistedit-raw-titles'   => 'Назвы:',

# Watchlist editing tools

);
