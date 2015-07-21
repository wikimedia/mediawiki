<?php
/** Belarusian (Taraškievica orthography) (беларуская (тарашкевіца)‎)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'be';

$namespaceNames = array(
	NS_MEDIA            => 'Мэдыя',
	NS_SPECIAL          => 'Спэцыяльныя',
	NS_TALK             => 'Абмеркаваньне',
	NS_USER             => 'Удзельнік',
	NS_USER_TALK        => 'Гутаркі_ўдзельніка',
	NS_PROJECT_TALK     => 'Абмеркаваньне_{{GRAMMAR:родны|$1}}',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Абмеркаваньне_файла',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Абмеркаваньне_MediaWiki',
	NS_TEMPLATE         => 'Шаблён',
	NS_TEMPLATE_TALK    => 'Абмеркаваньне_шаблёну',
	NS_HELP             => 'Дапамога',
	NS_HELP_TALK        => 'Абмеркаваньне_дапамогі',
	NS_CATEGORY         => 'Катэгорыя',
	NS_CATEGORY_TALK    => 'Абмеркаваньне_катэгорыі',
);

$namespaceAliases = array(
	'Абмеркаваньне_$1' => NS_PROJECT_TALK, // legacy support for old non-inflected links
	'Выява' => NS_FILE,
	'Абмеркаваньне_выявы' => NS_FILE_TALK,
);

$namespaceGenderAliases = array(
	NS_USER      => array( 'male' => 'Удзельнік', 'female' => 'Удзельніца' ),
	NS_USER_TALK => array( 'male' => 'Гутаркі_ўдзельніка', 'female' => 'Гутаркі_ўдзельніцы' ),
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Сыстэмныя_паведамленьні' ),
	'Allpages'                  => array( 'Усе_старонкі' ),
	'Ancientpages'              => array( 'Найстарэйшыя_старонкі' ),
	'Block'                     => array( 'Блякаваньне' ),
	'BrokenRedirects'           => array( 'Некарэктныя_перанакіраваньні' ),
	'Categories'                => array( 'Катэгорыі' ),
	'ChangePassword'            => array( 'Зьмяніць_пароль', 'Ачысьціць_пароль' ),
	'Contributions'             => array( 'Унёсак' ),
	'CreateAccount'             => array( 'Стварыць_рахунак' ),
	'Deadendpages'              => array( 'Тупіковыя_старонкі' ),
	'DeletedContributions'      => array( 'Выдалены_ўнёсак' ),
	'DoubleRedirects'           => array( 'Двайныя_перанакіраваньні' ),
	'Emailuser'                 => array( 'Даслаць_ліст' ),
	'Export'                    => array( 'Экспарт' ),
	'Filepath'                  => array( 'Шлях_да_файла' ),
	'Import'                    => array( 'Імпарт' ),
	'LinkSearch'                => array( 'Пошук_вонкавых_спасылак' ),
	'Listadmins'                => array( 'Сьпіс_адміністратараў' ),
	'Listbots'                  => array( 'Сьпіс_робатаў' ),
	'Listfiles'                 => array( 'Сьпіс_файлаў' ),
	'Listredirects'             => array( 'Сьпіс_перанакіраваньняў' ),
	'Listusers'                 => array( 'Сьпіс_удзельнікаў' ),
	'Log'                       => array( 'Журналы_падзеяў' ),
	'Lonelypages'               => array( 'Старонкі-сіраціны' ),
	'Longpages'                 => array( 'Доўгія_старонкі' ),
	'MergeHistory'              => array( 'Гісторыя_аб\'яднаньняў' ),
	'Mycontributions'           => array( 'Мой_унёсак' ),
	'Mypage'                    => array( 'Мая_старонка' ),
	'Mytalk'                    => array( 'Мае_размовы' ),
	'Newimages'                 => array( 'Новыя_файлы' ),
	'Newpages'                  => array( 'Новыя_старонкі' ),

	'Protectedpages'            => array( 'Абароненыя_старонкі' ),
	'Protectedtitles'           => array( 'Забароненыя_старонкі' ),
	'Randompage'                => array( 'Выпадковая_старонка' ),
	'Randomredirect'            => array( 'Выпадковае_перанакіраваньне' ),
	'Recentchanges'             => array( 'Апошнія_зьмены' ),
	'Search'                    => array( 'Пошук' ),
	'Shortpages'                => array( 'Кароткія_старонкі' ),
	'Specialpages'              => array( 'Спэцыяльныя_старонкі' ),
	'Statistics'                => array( 'Статыстыка' ),
	'Uncategorizedcategories'   => array( 'Некатэгарызаваныя_катэгорыі' ),
	'Uncategorizedimages'       => array( 'Некатэгарызаваныя_файлы' ),
	'Uncategorizedpages'        => array( 'Некатэгарызаваныя_старонкі' ),
	'Uncategorizedtemplates'    => array( 'Некатэгарызаваныя_шаблёны' ),
	'Upload'                    => array( 'Загрузка' ),
	'Userlogin'                 => array( 'Уваход_у_сыстэму' ),
	'Version'                   => array( 'Вэрсія' ),
	'Wantedcategories'          => array( 'Запатрабаваныя_катэгорыі' ),
	'Wantedfiles'               => array( 'Запатрабаваныя_файлы' ),
	'Wantedpages'               => array( 'Запатрабаваныя_старонкі', 'Некарэктныя_спасылкі' ),
	'Wantedtemplates'           => array( 'Запатрабаваныя_шаблёны' ),
	'Watchlist'                 => array( 'Сьпіс_назіраньня' ),
	'Whatlinkshere'             => array( 'Спасылкі_на_старонку' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#перанакіраваньне', '#REDIRECT' ),
	'notoc'                     => array( '0', '__БЯЗЬ_ЗЬМЕСТУ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__БЕЗ_ГАЛЕРЭІ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ЗЬМЕСТ_ПРЫМУСАМ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ЗЬМЕСТ__', '__TOC__' ),
	'noeditsection'             => array( '0', '__БЕЗ_РЭДАГАВАНЬНЯ_СЭКЦЫІ__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'БЯГУЧЫ_МЕСЯЦ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА_Ў_РОДНЫМ_СКЛОНЕ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'СКАРОЧАНАЯ_НАЗВА_БЯГУЧАГА_МЕСЯЦА', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'БЯГУЧЫ_ДЗЕНЬ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'БЯГУЧЫ_ДЗЕНЬ_2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'НАЗВА_БЯГУЧАГА_ДНЯ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'БЯГУЧЫ_ГОД', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'БЯГУЧЫ_ЧАС', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'БЯГУЧАЯ_ГАДЗІНА', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'ЛЯКАЛЬНЫ_МЕСЯЦ', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'НАЗВА_ЛЯКАЛЬНАГА_МЕСЯЦА', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'НАЗВА_ЛЯКАЛЬНАГА_МЕСЯЦА_Ў_РОДНЫМ_СКЛОНЕ', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'СКАРОЧАНАЯ_НАЗВА_ЛЯКАЛЬНАГА_МЕСЯЦА', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'ЛЯКАЛЬНЫ_ДЗЕНЬ', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'ЛЯКАЛЬНЫ_ДЗЕНЬ_2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'НАЗВА_ЛЯКАЛЬНАГА_ДНЯ', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'ЛЯКАЛЬНЫ_ГОД', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'ЛЯКАЛЬНЫ_ЧАС', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'ЛЯКАЛЬНАЯ_ГАДЗІНА', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'КОЛЬКАСЬЦЬ_СТАРОНАК', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'КОЛЬКАСЬЦЬ_АРТЫКУЛАЎ', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'КОЛЬКАСЬЦЬ_ФАЙЛАЎ', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'КОЛЬКАСЬЦЬ_УДЗЕЛЬНІКАЎ', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'КОЛЬКАСЬЦЬ_АКТЫЎНЫХ_УДЗЕЛЬНІКАЎ', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'КОЛЬКАСЬЦЬ_РЭДАГАВАНЬНЯЎ', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'НАЗВА_СТАРОНКІ', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'НАЗВА_СТАРОНКІ_2', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ПРАСТОРА_НАЗВАЎ', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ПРАСТОРА_НАЗВАЎ_2', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'ПРАСТОРА_НАЗВАЎ_АБМЕРКАВАНЬНЯ', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'ПРАСТОРА_НАЗВАЎ_АБМЕРКАВАНЬНЯ_2', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ПРАСТОРА_НАЗВАЎ_ПРАДМЕТУ', 'ПРАСТОРА_НАЗВАЎ_АРТЫКУЛА', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ПРАСТОРА_НАЗВАЎ_ПРАДМЕТУ_2', 'ПРАСТОРА_НАЗВАЎ_АРТЫКУЛА_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ_2', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'НАЗВА_ПАДСТАРОНКІ', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'НАЗВА_ПАДСТАРОНКІ_2', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'НАЗВА_БАЗАВАЙ_СТАРОНКІ', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'НАЗВА_БАЗАВАЙ_СТАРОНКІ_2', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'НАЗВА_СТАРОНКІ_АБМЕРКАВАНЬНЯ', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'НАЗВА_СТАРОНКІ_АБМЕРКАВАНЬНЯ_2', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'НАЗВА_СТАРОНКІ_ПРАДМЕТУ', 'НАЗВА_СТАРОНКІ_АРТЫКУЛА', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'НАЗВА_СТАРОНКІ_ПРАДМЕТУ_2', 'НАЗВА_СТАРОНКІ_АРТЫКУЛА_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'ПАВЕДАМЛЕНЬНЕ:', 'MSG:' ),
	'subst'                     => array( '0', 'ПАДСТАНОЎКА:', 'SUBST:' ),
	'msgnw'                     => array( '0', 'ПАВЕДАМЛЕНЬНЕ_БЯЗЬ_ВІКІ:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'значак', 'міні', 'мініяцюра', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'значак=$1', 'міні=$1', 'мініяцюра=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'справа', 'right' ),
	'img_left'                  => array( '1', 'зьлева', 'злева', 'left' ),
	'img_none'                  => array( '1', 'няма', 'none' ),
	'img_width'                 => array( '1', '$1пкс', '$1px' ),
	'img_center'                => array( '1', 'цэнтар', 'цэнтр', 'center', 'centre' ),
	'img_framed'                => array( '1', 'рамка', 'безрамкі', 'framed', 'enframed', 'frame' ),
	'img_page'                  => array( '1', 'старонка=$1', 'старонка $1', 'page=$1', 'page $1' ),
	'img_top'                   => array( '1', 'зьверху', 'top' ),
	'img_middle'                => array( '1', 'пасярэдзіне', 'middle' ),
	'img_bottom'                => array( '1', 'зьнізу', 'bottom' ),
	'img_link'                  => array( '1', 'спасылка=$1', 'link=$1' ),
	'sitename'                  => array( '1', 'НАЗВА_САЙТУ', 'SITENAME' ),
	'ns'                        => array( '0', 'ПН:', 'NS:' ),
	'localurl'                  => array( '0', 'ЛЯКАЛЬНЫ_АДРАС:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'ЛЯКАЛЬНЫ_АДРАС_2:', 'LOCALURLE:' ),
	'server'                    => array( '0', 'СЭРВЭР', 'SERVER' ),
	'servername'                => array( '0', 'НАЗВА_СЭРВЭРА', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'ШЛЯХ_ДА_СКРЫПТА', 'SCRIPTPATH' ),
	'grammar'                   => array( '0', 'ГРАМАТЫКА:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'ПОЛ:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__НЕ_КАНВЭРТАВАЦЬ_НАЗВУ__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__НЕ_КАНВЭРТАВАЦЬ_ТЭКСТ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'БЯГУЧЫ_ТЫДЗЕНЬ', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'БЯГУЧЫ_ДЗЕНЬ_ТЫДНЯ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'ЛЯКАЛЬНЫ_ТЫДЗЕНЬ', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'ЛЯКАЛЬНЫ_ДЗЕНЬ_ТЫДНЯ', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'ID_ВЭРСІІ', 'REVISIONID' ),
	'revisionday'               => array( '1', 'ДЗЕНЬ_ВЭРСІІ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'ДЗЕНЬ_ВЭРСІІ_2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'МЕСЯЦ_ВЭРСІІ', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'ГОД_ВЭРСІІ', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'МОМАНТ_ЧАСУ_ВЭРСІІ', 'REVISIONTIMESTAMP' ),
	'plural'                    => array( '0', 'МНОЖНЫ_ЛІК:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'ПОЎНЫ_АДРАС:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'ПОЎНЫ_АДРАС_2:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'ПЕРШАЯ_ЛІТАРА_МАЛАЯ:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'ПЕРШАЯ_ЛІТАРА_ВЯЛІКАЯ:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'МАЛЫМІ_ЛІТАРАМІ:', 'LC:' ),
	'uc'                        => array( '0', 'ВЯЛІКІМІ_ЛІТАРАМІ:', 'UC:' ),
	'raw'                       => array( '0', 'НЕАПРАЦАВАНЫ:', 'RAW:' ),
	'displaytitle'              => array( '1', 'ПАКАЗВАЦЬ_НАЗВУ', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'Н', 'R' ),
	'newsectionlink'            => array( '1', '__СПАСЫЛКА_НА_НОВУЮ_СЭКЦЫЮ__', '__NEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'БЯГУЧАЯ_ВЭРСІЯ', 'CURRENTVERSION' ),
	'currenttimestamp'          => array( '1', 'МОМАНТ_ЧАСУ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'ЛЯКАЛЬНЫ_МОМАНТ_ЧАСУ', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'СЫМБАЛЬ_НАПРАМКУ_ПІСЬМА', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#МОВА:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'МОВА_ЗЬМЕСТУ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'КОЛЬКАСЬЦЬ_СТАРОНАК_У_ПРАСТОРЫ_НАЗВАЎ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'КОЛЬКАСЬЦЬ_АДМІНІСТРАТАРАЎ', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'ФАРМАТАВАЦЬ_ЛІК', 'FORMATNUM' ),
	'defaultsort'               => array( '1', 'САРТЫРОЎКА_ПА_ЗМОЎЧВАНЬНІ:', 'КЛЮЧ_САРТЫРОЎКІ_ПА_ЗМОЎЧВАНЬНІ:', 'САРТЫРОЎКА_Ў_КАТЭГОРЫІ_ПА_ЗМОЎЧВАНЬНІ:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'ШЛЯХ_ДА_ФАЙЛА:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'тэг', 'tag' ),
	'hiddencat'                 => array( '1', '__СХАВАЦЬ_КАТЭГОРЫЮ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'КОЛЬКАСЬЦЬ_СТАРОНАК_У_КАТЭГОРЫІ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'ПАМЕР_СТАРОНКІ', 'PAGESIZE' ),
	'staticredirect'            => array( '1', '__СТАТЫЧНАЕ_ПЕРАНАКІРАВАНЬНЕ__', '__STATICREDIRECT__' ),
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

$separatorTransformTable = array(
	',' => "\xc2\xa0", # nbsp
	'.' => ','
);

$linkTrail = '/^([абвгґджзеёжзійклмнопрстуўфхцчшыьэюяćčłńśšŭźža-z]+)(.*)$/sDu';

$imageFiles = array(
	'button-bold'     => 'be-tarask/button_bold.png',
	'button-italic'   => 'be-tarask/button_italic.png',
	'button-link'     => 'be-tarask/button_link.png',
);

