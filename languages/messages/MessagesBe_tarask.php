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

$namespaceNames = [
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
];

$namespaceAliases = [
	'Абмеркаваньне_$1' => NS_PROJECT_TALK, // legacy support for old non-inflected links
	'Выява' => NS_FILE,
	'Абмеркаваньне_выявы' => NS_FILE_TALK,
];

$namespaceGenderAliases = [
	NS_USER      => [ 'male' => 'Удзельнік', 'female' => 'Удзельніца' ],
	NS_USER_TALK => [ 'male' => 'Гутаркі_ўдзельніка', 'female' => 'Гутаркі_ўдзельніцы' ],
];

$specialPageAliases = [
	'Allmessages'               => [ 'Сыстэмныя_паведамленьні' ],
	'Allpages'                  => [ 'Усе_старонкі' ],
	'Ancientpages'              => [ 'Найстарэйшыя_старонкі' ],
	'Block'                     => [ 'Блякаваньне' ],
	'BrokenRedirects'           => [ 'Некарэктныя_перанакіраваньні' ],
	'Categories'                => [ 'Катэгорыі' ],
	'ChangePassword'            => [ 'Зьмяніць_пароль', 'Ачысьціць_пароль' ],
	'Contributions'             => [ 'Унёсак' ],
	'CreateAccount'             => [ 'Стварыць_рахунак' ],
	'Deadendpages'              => [ 'Тупіковыя_старонкі' ],
	'DeletedContributions'      => [ 'Выдалены_ўнёсак' ],
	'DoubleRedirects'           => [ 'Двайныя_перанакіраваньні' ],
	'Emailuser'                 => [ 'Даслаць_ліст' ],
	'Export'                    => [ 'Экспарт' ],
	'Filepath'                  => [ 'Шлях_да_файла' ],
	'Import'                    => [ 'Імпарт' ],
	'LinkSearch'                => [ 'Пошук_вонкавых_спасылак' ],
	'Listadmins'                => [ 'Сьпіс_адміністратараў' ],
	'Listbots'                  => [ 'Сьпіс_робатаў' ],
	'Listfiles'                 => [ 'Сьпіс_файлаў' ],
	'Listredirects'             => [ 'Сьпіс_перанакіраваньняў' ],
	'Listusers'                 => [ 'Сьпіс_удзельнікаў' ],
	'Log'                       => [ 'Журналы_падзеяў' ],
	'Lonelypages'               => [ 'Старонкі-сіраціны' ],
	'Longpages'                 => [ 'Доўгія_старонкі' ],
	'MergeHistory'              => [ 'Гісторыя_аб\'яднаньняў' ],
	'Mycontributions'           => [ 'Мой_унёсак' ],
	'Mypage'                    => [ 'Мая_старонка' ],
	'Mytalk'                    => [ 'Мае_размовы' ],
	'Newimages'                 => [ 'Новыя_файлы' ],
	'Newpages'                  => [ 'Новыя_старонкі' ],
	'Protectedpages'            => [ 'Абароненыя_старонкі' ],
	'Protectedtitles'           => [ 'Забароненыя_старонкі' ],
	'Randompage'                => [ 'Выпадковая_старонка' ],
	'Randomredirect'            => [ 'Выпадковае_перанакіраваньне' ],
	'Recentchanges'             => [ 'Апошнія_зьмены' ],
	'Search'                    => [ 'Пошук' ],
	'Shortpages'                => [ 'Кароткія_старонкі' ],
	'Specialpages'              => [ 'Спэцыяльныя_старонкі' ],
	'Statistics'                => [ 'Статыстыка' ],
	'Uncategorizedcategories'   => [ 'Некатэгарызаваныя_катэгорыі' ],
	'Uncategorizedimages'       => [ 'Некатэгарызаваныя_файлы' ],
	'Uncategorizedpages'        => [ 'Некатэгарызаваныя_старонкі' ],
	'Uncategorizedtemplates'    => [ 'Некатэгарызаваныя_шаблёны' ],
	'Upload'                    => [ 'Загрузка' ],
	'Userlogin'                 => [ 'Уваход_у_сыстэму' ],
	'Version'                   => [ 'Вэрсія' ],
	'Wantedcategories'          => [ 'Запатрабаваныя_катэгорыі' ],
	'Wantedfiles'               => [ 'Запатрабаваныя_файлы' ],
	'Wantedpages'               => [ 'Запатрабаваныя_старонкі', 'Некарэктныя_спасылкі' ],
	'Wantedtemplates'           => [ 'Запатрабаваныя_шаблёны' ],
	'Watchlist'                 => [ 'Сьпіс_назіраньня' ],
	'Whatlinkshere'             => [ 'Спасылкі_на_старонку' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#перанакіраваньне', '#REDIRECT' ],
	'notoc'                     => [ '0', '__БЯЗЬ_ЗЬМЕСТУ__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__БЕЗ_ГАЛЕРЭІ__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__ЗЬМЕСТ_ПРЫМУСАМ__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__ЗЬМЕСТ__', '__TOC__' ],
	'noeditsection'             => [ '0', '__БЕЗ_РЭДАГАВАНЬНЯ_СЭКЦЫІ__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'БЯГУЧЫ_МЕСЯЦ', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА_Ў_РОДНЫМ_СКЛОНЕ', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'СКАРОЧАНАЯ_НАЗВА_БЯГУЧАГА_МЕСЯЦА', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'БЯГУЧЫ_ДЗЕНЬ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'БЯГУЧЫ_ДЗЕНЬ_2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'НАЗВА_БЯГУЧАГА_ДНЯ', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'БЯГУЧЫ_ГОД', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'БЯГУЧЫ_ЧАС', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'БЯГУЧАЯ_ГАДЗІНА', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'ЛЯКАЛЬНЫ_МЕСЯЦ', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'НАЗВА_ЛЯКАЛЬНАГА_МЕСЯЦА', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'НАЗВА_ЛЯКАЛЬНАГА_МЕСЯЦА_Ў_РОДНЫМ_СКЛОНЕ', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'СКАРОЧАНАЯ_НАЗВА_ЛЯКАЛЬНАГА_МЕСЯЦА', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'ЛЯКАЛЬНЫ_ДЗЕНЬ', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'ЛЯКАЛЬНЫ_ДЗЕНЬ_2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'НАЗВА_ЛЯКАЛЬНАГА_ДНЯ', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'ЛЯКАЛЬНЫ_ГОД', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'ЛЯКАЛЬНЫ_ЧАС', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'ЛЯКАЛЬНАЯ_ГАДЗІНА', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'КОЛЬКАСЬЦЬ_СТАРОНАК', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'КОЛЬКАСЬЦЬ_АРТЫКУЛАЎ', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'КОЛЬКАСЬЦЬ_ФАЙЛАЎ', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'КОЛЬКАСЬЦЬ_УДЗЕЛЬНІКАЎ', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'КОЛЬКАСЬЦЬ_АКТЫЎНЫХ_УДЗЕЛЬНІКАЎ', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'КОЛЬКАСЬЦЬ_РЭДАГАВАНЬНЯЎ', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'НАЗВА_СТАРОНКІ', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'НАЗВА_СТАРОНКІ_2', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'ПРАСТОРА_НАЗВАЎ', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ПРАСТОРА_НАЗВАЎ_2', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'ПРАСТОРА_НАЗВАЎ_АБМЕРКАВАНЬНЯ', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'ПРАСТОРА_НАЗВАЎ_АБМЕРКАВАНЬНЯ_2', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'ПРАСТОРА_НАЗВАЎ_ПРАДМЕТУ', 'ПРАСТОРА_НАЗВАЎ_АРТЫКУЛА', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'ПРАСТОРА_НАЗВАЎ_ПРАДМЕТУ_2', 'ПРАСТОРА_НАЗВАЎ_АРТЫКУЛА_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ_2', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'НАЗВА_ПАДСТАРОНКІ', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'НАЗВА_ПАДСТАРОНКІ_2', 'SUBPAGENAMEE' ],
	'basepagename'              => [ '1', 'НАЗВА_БАЗАВАЙ_СТАРОНКІ', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'НАЗВА_БАЗАВАЙ_СТАРОНКІ_2', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'НАЗВА_СТАРОНКІ_АБМЕРКАВАНЬНЯ', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'НАЗВА_СТАРОНКІ_АБМЕРКАВАНЬНЯ_2', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', 'НАЗВА_СТАРОНКІ_ПРАДМЕТУ', 'НАЗВА_СТАРОНКІ_АРТЫКУЛА', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'НАЗВА_СТАРОНКІ_ПРАДМЕТУ_2', 'НАЗВА_СТАРОНКІ_АРТЫКУЛА_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'msg'                       => [ '0', 'ПАВЕДАМЛЕНЬНЕ:', 'MSG:' ],
	'subst'                     => [ '0', 'ПАДСТАНОЎКА:', 'SUBST:' ],
	'msgnw'                     => [ '0', 'ПАВЕДАМЛЕНЬНЕ_БЯЗЬ_ВІКІ:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'значак', 'міні', 'мініяцюра', 'thumbnail', 'thumb' ],
	'img_manualthumb'           => [ '1', 'значак=$1', 'міні=$1', 'мініяцюра=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'справа', 'right' ],
	'img_left'                  => [ '1', 'зьлева', 'злева', 'left' ],
	'img_none'                  => [ '1', 'няма', 'none' ],
	'img_width'                 => [ '1', '$1пкс', '$1px' ],
	'img_center'                => [ '1', 'цэнтар', 'цэнтр', 'center', 'centre' ],
	'img_framed'                => [ '1', 'рамка', 'безрамкі', 'framed', 'enframed', 'frame' ],
	'img_page'                  => [ '1', 'старонка=$1', 'старонка $1', 'page=$1', 'page $1' ],
	'img_top'                   => [ '1', 'зьверху', 'top' ],
	'img_middle'                => [ '1', 'пасярэдзіне', 'middle' ],
	'img_bottom'                => [ '1', 'зьнізу', 'bottom' ],
	'img_link'                  => [ '1', 'спасылка=$1', 'link=$1' ],
	'sitename'                  => [ '1', 'НАЗВА_САЙТУ', 'SITENAME' ],
	'ns'                        => [ '0', 'ПН:', 'NS:' ],
	'localurl'                  => [ '0', 'ЛЯКАЛЬНЫ_АДРАС:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'ЛЯКАЛЬНЫ_АДРАС_2:', 'LOCALURLE:' ],
	'server'                    => [ '0', 'СЭРВЭР', 'SERVER' ],
	'servername'                => [ '0', 'НАЗВА_СЭРВЭРА', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'ШЛЯХ_ДА_СКРЫПТА', 'SCRIPTPATH' ],
	'grammar'                   => [ '0', 'ГРАМАТЫКА:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'ПОЛ:', 'GENDER:' ],
	'notitleconvert'            => [ '0', '__НЕ_КАНВЭРТАВАЦЬ_НАЗВУ__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__НЕ_КАНВЭРТАВАЦЬ_ТЭКСТ__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'БЯГУЧЫ_ТЫДЗЕНЬ', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'БЯГУЧЫ_ДЗЕНЬ_ТЫДНЯ', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'ЛЯКАЛЬНЫ_ТЫДЗЕНЬ', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'ЛЯКАЛЬНЫ_ДЗЕНЬ_ТЫДНЯ', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'ID_ВЭРСІІ', 'REVISIONID' ],
	'revisionday'               => [ '1', 'ДЗЕНЬ_ВЭРСІІ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'ДЗЕНЬ_ВЭРСІІ_2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'МЕСЯЦ_ВЭРСІІ', 'REVISIONMONTH' ],
	'revisionyear'              => [ '1', 'ГОД_ВЭРСІІ', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'МОМАНТ_ЧАСУ_ВЭРСІІ', 'REVISIONTIMESTAMP' ],
	'plural'                    => [ '0', 'МНОЖНЫ_ЛІК:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'ПОЎНЫ_АДРАС:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'ПОЎНЫ_АДРАС_2:', 'FULLURLE:' ],
	'lcfirst'                   => [ '0', 'ПЕРШАЯ_ЛІТАРА_МАЛАЯ:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'ПЕРШАЯ_ЛІТАРА_ВЯЛІКАЯ:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'МАЛЫМІ_ЛІТАРАМІ:', 'LC:' ],
	'uc'                        => [ '0', 'ВЯЛІКІМІ_ЛІТАРАМІ:', 'UC:' ],
	'raw'                       => [ '0', 'НЕАПРАЦАВАНЫ:', 'RAW:' ],
	'displaytitle'              => [ '1', 'ПАКАЗВАЦЬ_НАЗВУ', 'DISPLAYTITLE' ],
	'rawsuffix'                 => [ '1', 'Н', 'R' ],
	'newsectionlink'            => [ '1', '__СПАСЫЛКА_НА_НОВУЮ_СЭКЦЫЮ__', '__NEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'БЯГУЧАЯ_ВЭРСІЯ', 'CURRENTVERSION' ],
	'currenttimestamp'          => [ '1', 'МОМАНТ_ЧАСУ', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'ЛЯКАЛЬНЫ_МОМАНТ_ЧАСУ', 'LOCALTIMESTAMP' ],
	'directionmark'             => [ '1', 'СЫМБАЛЬ_НАПРАМКУ_ПІСЬМА', 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                  => [ '0', '#МОВА:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'МОВА_ЗЬМЕСТУ', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'КОЛЬКАСЬЦЬ_СТАРОНАК_У_ПРАСТОРЫ_НАЗВАЎ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'КОЛЬКАСЬЦЬ_АДМІНІСТРАТАРАЎ', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'ФАРМАТАВАЦЬ_ЛІК', 'FORMATNUM' ],
	'defaultsort'               => [ '1', 'САРТЫРОЎКА_ПА_ЗМОЎЧВАНЬНІ:', 'КЛЮЧ_САРТЫРОЎКІ_ПА_ЗМОЎЧВАНЬНІ:', 'САРТЫРОЎКА_Ў_КАТЭГОРЫІ_ПА_ЗМОЎЧВАНЬНІ:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'ШЛЯХ_ДА_ФАЙЛА:', 'FILEPATH:' ],
	'tag'                       => [ '0', 'тэг', 'tag' ],
	'hiddencat'                 => [ '1', '__СХАВАЦЬ_КАТЭГОРЫЮ__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'КОЛЬКАСЬЦЬ_СТАРОНАК_У_КАТЭГОРЫІ', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'ПАМЕР_СТАРОНКІ', 'PAGESIZE' ],
	'staticredirect'            => [ '1', '__СТАТЫЧНАЕ_ПЕРАНАКІРАВАНЬНЕ__', '__STATICREDIRECT__' ],
];

$bookstoreList = [
	'OZ.by' => 'http://oz.by/search.phtml?what=books&isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
];

$datePreferences = [
	'default',
	'dmy',
	'ISO 8601',
];

$defaultDateFormat = 'dmy';

$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',
];

$separatorTransformTable = [
	',' => "\xc2\xa0", # nbsp
	'.' => ','
];

$linkTrail = '/^([абвгґджзеёжзійклмнопрстуўфхцчшыьэюяćčłńśšŭźža-z]+)(.*)$/sDu';

$imageFiles = [
	'button-bold'     => 'be-tarask/button_bold.png',
	'button-italic'   => 'be-tarask/button_italic.png',
	'button-link'     => 'be-tarask/button_link.png',
];

