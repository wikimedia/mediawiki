<?php
/** Russian (русский)
 *
 * @file
 * @ingroup Languages
 *
 * @author AVRS
 * @author Agilight
 * @author Ahonc
 * @author Aleksandrit
 * @author Alessandro
 * @author AlexSm
 * @author Alexander Sigachov (alexander.sigachov@gmail.com)
 * @author Alexandr Efremov
 * @author Altai uul
 * @author Am81
 * @author Amikeco
 * @author Amire80
 * @author Anonim.one
 * @author Askarmuk
 * @author Assele
 * @author BellaIlabekyan
 * @author Biathlon
 * @author Bouron
 * @author Chilin
 * @author Cinemantique
 * @author Claymore
 * @author Comp1089
 * @author Cryptocoryne
 * @author DCamer
 * @author Daniyar
 * @author Dim Grits
 * @author Don Alessandro
 * @author Ekulikovdo
 * @author Eleferen
 * @author Erdemaslancan
 * @author EugeneZelenko
 * @author Eugrus
 * @author Express2000
 * @author Ferrer
 * @author Flrn
 * @author G0rn
 * @author Gazeb
 * @author Geitost
 * @author Grigol
 * @author Haffman
 * @author HalanTul
 * @author Huuchin
 * @author Ignatus
 * @author Illusion
 * @author Iltever
 * @author Iluvatar
 * @author Incnis Mrsi
 * @author Iniquity
 * @author Innv
 * @author Ivan Shmakov
 * @author Jackie
 * @author JenVan
 * @author Jl
 * @author KPu3uC B Poccuu
 * @author Kaganer
 * @author Kalan
 * @author KorneySan
 * @author Kv75
 * @author Lockal
 * @author Lord Dimetr
 * @author Lunacy1911
 * @author Matma Rex
 * @author MaxBioHazard
 * @author MaxSem
 * @author Milicevic01
 * @author NBS
 * @author Nemo bis
 * @author Okras
 * @author Ola
 * @author Ole Yves
 * @author Putnik
 * @author Rave
 * @author Rubin
 * @author Rubin16
 * @author Sagan
 * @author Shirayuki
 * @author Sk
 * @author Soul Train
 * @author Spider
 * @author Sunpriat
 * @author TarzanASG
 * @author Temuri rajavi
 * @author Vago
 * @author VasilievVV
 * @author Volkov
 * @author Ytsukeng Fyvaprol
 * @author Александр Сигачёв
 * @author Гусейн
 * @author ОйЛ
 * @author Сай
 * @author Умар
 * @author Чаховіч Уладзіслаў
 * @author לערי ריינהארט
 * @author გიორგიმელა
 */

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Служебная',
	NS_TALK             => 'Обсуждение',
	NS_USER             => 'Участник',
	NS_USER_TALK        => 'Обсуждение_участника',
	NS_PROJECT_TALK     => 'Обсуждение_{{GRAMMAR:genitive|$1}}',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Обсуждение_файла',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Обсуждение_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Обсуждение_шаблона',
	NS_HELP             => 'Справка',
	NS_HELP_TALK        => 'Обсуждение_справки',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Обсуждение_категории',
];

$namespaceAliases = [
	'Изображение' => NS_FILE,
	'Обсуждение_изображения' => NS_FILE_TALK,
];

$namespaceGenderAliases = [
	NS_USER      => [ 'male' => 'Участник', 'female' => 'Участница' ],
	NS_USER_TALK => [ 'male' => 'Обсуждение_участника', 'female' => 'Обсуждение_участницы' ],
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Активные_участники' ],
	'Allmessages'               => [ 'Системные_сообщения' ],
	'AllMyUploads'              => [ 'Все_мои_файлы' ],
	'Allpages'                  => [ 'Все_страницы' ],
	'Badtitle'                  => [ 'Недопустимое_название' ],
	'Blankpage'                 => [ 'Пустая_страница' ],
	'Block'                     => [ 'Заблокировать' ],
	'BlockList'                 => [ 'Список_блокировок', 'Блокировки' ],
	'Booksources'               => [ 'Источники_книг' ],
	'BrokenRedirects'           => [ 'Разорванные_перенаправления' ],
	'Categories'                => [ 'Категории' ],
	'ChangeEmail'               => [ 'Сменить_e-mail', 'Сменить_почту' ],
	'ChangePassword'            => [ 'Сменить_пароль' ],
	'ComparePages'              => [ 'Сравнение_страниц' ],
	'Confirmemail'              => [ 'Подтвердить_e-mail', 'Подтвердить_почту' ],
	'Contributions'             => [ 'Вклад' ],
	'CreateAccount'             => [ 'Создать_учётную_запись', 'Создать_пользователя', 'Зарегистрироваться' ],
	'Deadendpages'              => [ 'Тупиковые_страницы' ],
	'DeletedContributions'      => [ 'Удалённый_вклад' ],
	'Diff'                      => [ 'Изменения' ],
	'DoubleRedirects'           => [ 'Двойные_перенаправления' ],
	'EditWatchlist'             => [ 'Править_список_наблюдения' ],
	'Emailuser'                 => [ 'Письмо_участнику', 'Отправить_письмо' ],
	'ExpandTemplates'           => [ 'Развёртка_шаблонов' ],
	'Export'                    => [ 'Экспорт', 'Выгрузка' ],
	'Fewestrevisions'           => [ 'Редко_редактируемые' ],
	'FileDuplicateSearch'       => [ 'Поиск_дубликатов_файлов' ],
	'Filepath'                  => [ 'Путь_к_файлу' ],
	'Import'                    => [ 'Импорт' ],
	'Invalidateemail'           => [ 'Отменить_подтверждение_адреса' ],
	'JavaScriptTest'            => [ 'Тестирование_JavaScript' ],
	'LinkSearch'                => [ 'Поиск_ссылок' ],
	'Listadmins'                => [ 'Список_администраторов' ],
	'Listbots'                  => [ 'Список_ботов' ],
	'ListDuplicatedFiles'       => [ 'Список_файлов-дубликатов' ],
	'Listfiles'                 => [ 'Список_файлов', 'Список_изображений' ],
	'Listgrouprights'           => [ 'Права_групп_участников', 'Список_прав_групп' ],
	'Listredirects'             => [ 'Список_перенаправлений' ],
	'Listusers'                 => [ 'Список_участников' ],
	'Lockdb'                    => [ 'Заблокировать_БД', 'Заблокировать_базу_данных' ],
	'Log'                       => [ 'Журналы', 'Журнал' ],
	'Lonelypages'               => [ 'Изолированные_страницы' ],
	'Longpages'                 => [ 'Длинные_страницы' ],
	'MergeHistory'              => [ 'Объединение_историй' ],
	'MIMEsearch'                => [ 'Поиск_по_MIME' ],
	'Mostcategories'            => [ 'Самые_категоризованные' ],
	'Mostimages'                => [ 'Самые_используемые_файлы' ],
	'Mostinterwikis'            => [ 'Наибольшее_количество_интервики-ссылок' ],
	'Mostlinked'                => [ 'Самые_используемые_страницы' ],
	'Mostlinkedcategories'      => [ 'Самые_используемые_категории' ],
	'Mostlinkedtemplates'       => [ 'Самые_используемые_шаблоны' ],
	'Mostrevisions'             => [ 'Наибольшее_количество_версий' ],
	'Movepage'                  => [ 'Переименовать_страницу', 'Переименование', 'Переименовать' ],
	'Mycontributions'           => [ 'Мой_вклад' ],
	'MyLanguage'                => [ 'Мой_язык' ],
	'Mypage'                    => [ 'Моя_страница' ],
	'Mytalk'                    => [ 'Моё_обсуждение' ],
	'Myuploads'                 => [ 'Мои_загрузки' ],
	'Newimages'                 => [ 'Новые_файлы' ],
	'Newpages'                  => [ 'Новые_страницы' ],
	'PasswordReset'             => [ 'Сброс_пароля' ],
	'PermanentLink'             => [ 'Постоянная_ссылка' ],
	'Preferences'               => [ 'Настройки' ],
	'Prefixindex'               => [ 'Указатель_по_началу_названия' ],
	'Protectedpages'            => [ 'Защищённые_страницы' ],
	'Protectedtitles'           => [ 'Защищённые_названия' ],
	'Randompage'                => [ 'Случайная_страница', 'Случайная' ],
	'Randomredirect'            => [ 'Случайное_перенаправление' ],
	'Recentchanges'             => [ 'Свежие_правки' ],
	'Recentchangeslinked'       => [ 'Связанные_правки' ],
	'Renameuser'                => [ 'Переименовать_участника' ],
	'Revisiondelete'            => [ 'Удаление_правки' ],
	'Search'                    => [ 'Поиск' ],
	'Shortpages'                => [ 'Короткие_страницы' ],
	'Specialpages'              => [ 'Спецстраницы' ],
	'Statistics'                => [ 'Статистика' ],
	'Tags'                      => [ 'Метки' ],
	'Unblock'                   => [ 'Разблокировка' ],
	'Uncategorizedcategories'   => [ 'Некатегоризованные_категории' ],
	'Uncategorizedimages'       => [ 'Некатегоризованные_файлы' ],
	'Uncategorizedpages'        => [ 'Некатегоризованные_страницы' ],
	'Uncategorizedtemplates'    => [ 'Некатегоризованные_шаблоны' ],
	'Undelete'                  => [ 'Восстановить', 'Восстановление' ],
	'Unlockdb'                  => [ 'Разблокировка_БД' ],
	'Unusedcategories'          => [ 'Неиспользуемые_категории' ],
	'Unusedimages'              => [ 'Неиспользуемые_файлы' ],
	'Unusedtemplates'           => [ 'Неиспользуемые_шаблоны' ],
	'Upload'                    => [ 'Загрузка' ],
	'UploadStash'               => [ 'Скрытная_загрузка' ],
	'Userlogin'                 => [ 'Вход' ],
	'Userlogout'                => [ 'Завершение_сеанса', 'Выход' ],
	'Userrights'                => [ 'Управление_правами' ],
	'Version'                   => [ 'Версия' ],
	'Wantedcategories'          => [ 'Требуемые_категории' ],
	'Wantedfiles'               => [ 'Требуемые_файлы' ],
	'Wantedpages'               => [ 'Требуемые_страницы' ],
	'Wantedtemplates'           => [ 'Требуемые_шаблоны' ],
	'Watchlist'                 => [ 'Список_наблюдения' ],
	'Whatlinkshere'             => [ 'Ссылки_сюда' ],
	'Withoutinterwiki'          => [ 'Без_интервики' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'anchorencode'              => [ '0', 'КОДИРОВАТЬ_МЕТКУ', 'ANCHORENCODE' ],
	'articlepath'               => [ '0', 'ПУТЬ_К_СТАТЬЕ', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ_2', 'BASEPAGENAMEE' ],
	'contentlanguage'           => [ '1', 'ЯЗЫК_СОДЕРЖАНИЯ', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'ТЕКУЩИЙ_ДЕНЬ_НЕДЕЛИ', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'ТЕКУЩИЙ_ЧАС', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'ТЕКУЩИЙ_МЕСЯЦ_1', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_АБР', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'ОТМЕТКА_ТЕКУЩЕГО_ВРЕМЕНИ', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'ТЕКУЩАЯ_ВЕРСИЯ', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'ТЕКУЩАЯ_НЕДЕЛЯ', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'СОРТИРОВКА_ПО_УМОЛЧАНИЮ', 'КЛЮЧ_СОРТИРОВКИ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'directionmark'             => [ '1', 'НАПРАВЛЕНИЕ_ПИСЬМА', 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'              => [ '1', 'ПОКАЗАТЬ_ЗАГОЛОВОК', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'ПУТЬ_К_ФАЙЛУ:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__ОБЯЗ_ОГЛ__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'форматдаты', 'formatdate', 'dateformat' ],
	'formatnum'                 => [ '0', 'ФОРМАТИРОВАТЬ_ЧИСЛО', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ_2', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'ПОЛНЫЙ_АДРЕС:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'ПОЛНЫЙ_АДРЕС_2:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'ПОЛ:', 'GENDER:' ],
	'grammar'                   => [ '0', 'ПАДЕЖ:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__СКРЫТАЯ_КАТЕГОРИЯ__', '__HIDDENCAT__' ],
	'img_alt'                   => [ '1', 'альт=$1', 'alt=$1' ],
	'img_baseline'              => [ '1', 'основание', 'baseline' ],
	'img_border'                => [ '1', 'граница', 'border' ],
	'img_bottom'                => [ '1', 'снизу', 'bottom' ],
	'img_center'                => [ '1', 'центр', 'center', 'centre' ],
	'img_framed'                => [ '1', 'обрамить', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'безрамки', 'frameless' ],
	'img_left'                  => [ '1', 'слева', 'left' ],
	'img_link'                  => [ '1', 'ссылка=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'мини=$1', 'миниатюра=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'посередине', 'middle' ],
	'img_none'                  => [ '1', 'без', 'none' ],
	'img_page'                  => [ '1', 'страница=$1', 'страница $1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'справа', 'right' ],
	'img_sub'                   => [ '1', 'под', 'sub' ],
	'img_super'                 => [ '1', 'над', 'super', 'sup' ],
	'img_text_bottom'           => [ '1', 'текст-снизу', 'text-bottom' ],
	'img_text_top'              => [ '1', 'текст-сверху', 'text-top' ],
	'img_thumbnail'             => [ '1', 'мини', 'миниатюра', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'сверху', 'top' ],
	'img_upright'               => [ '1', 'сверхусправа', 'сверхусправа=$1', 'сверхусправа $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_width'                 => [ '1', '$1пкс', '$1px' ],
	'index'                     => [ '1', '__ИНДЕКС__', '__INDEX__' ],
	'int'                       => [ '0', 'ВНУТР:', 'INT:' ],
	'language'                  => [ '0', '#ЯЗЫК', '#LANGUAGE' ],
	'lc'                        => [ '0', 'МАЛЕНЬКИМИ_БУКВАМИ:', 'LC:' ],
	'lcfirst'                   => [ '0', 'ПЕРВАЯ_БУКВА_МАЛЕНЬКАЯ:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'МЕСТНЫЙ_ДЕНЬ', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'МЕСТНЫЙ_ДЕНЬ_2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'НАЗВАНИЕ_МЕСТНОГО_ДНЯ', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'МЕСТНЫЙ_ДЕНЬ_НЕДЕЛИ', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'МЕСТНЫЙ_ЧАС', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'МЕСТНЫЙ_МЕСЯЦ', 'МЕСТНЫЙ_МЕСЯЦ_2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'МЕСТНЫЙ_МЕСЯЦ_1', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_АБР', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_РОД', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'МЕСТНОЕ_ВРЕМЯ', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'ОТМЕТКА_МЕСТНОГО_ВРЕМЕНИ', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'ЛОКАЛЬНЫЙ_АДРЕС:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'ЛОКАЛЬНЫЙ_АДРЕС_2:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'МЕСТНАЯ_НЕДЕЛЯ', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'МЕСТНЫЙ_ГОД', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'СООБЩЕНИЕ:', 'СООБЩ:', 'MSG:' ],
	'msgnw'                     => [ '0', 'СООБЩ_БЕЗ_ВИКИ:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ПРОСТРАНСТВО_ИМЁН_2', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'НОМЕР_ПРОСТРАНСТВА_ИМЁН', 'NAMESPACENUMBER' ],
	'newsectionlink'            => [ '1', '__ССЫЛКА_НА_НОВЫЙ_РАЗДЕЛ__', '__NEWSECTIONLINK__' ],
	'nocontentconvert'          => [ '0', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ТЕКСТА__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__БЕЗ_ИНДЕКСА__', '__NOINDEX__' ],
	'nonewsectionlink'          => [ '1', '__БЕЗ_ССЫЛКИ_НА_НОВЫЙ_РАЗДЕЛ__', '__NONEWSECTIONLINK__' ],
	'notitleconvert'            => [ '0', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ЗАГОЛОВКА__', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ],
	'ns'                        => [ '0', 'ПИ:', 'NS:' ],
	'nse'                       => [ '0', 'ПИК:', 'NSE:' ],
	'numberingroup'             => [ '1', 'ЧИСЛО_В_ГРУППЕ', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', 'КОЛИЧЕСТВО_АКТИВНЫХ_УЧАСТНИКОВ', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'КОЛИЧЕСТВО_АДМИНИСТРАТОРОВ', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'КОЛИЧЕСТВО_ПРАВОК', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'КОЛИЧЕСТВО_ФАЙЛОВ', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'КОЛИЧЕСТВО_СТРАНИЦ', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'КОЛИЧЕСТВО_УЧАСТНИКОВ', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'ЗАПОЛНИТЬ_СЛЕВА', 'PADLEFT' ],
	'padright'                  => [ '0', 'ЗАПОЛНИТЬ_СПРАВА', 'PADRIGHT' ],
	'pageid'                    => [ '0', 'ИДЕНТИФИКАТОР_СТРАНИЦЫ', 'PAGEID' ],
	'pagename'                  => [ '1', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'НАЗВАНИЕ_СТРАНИЦЫ_2', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'СТРАНИЦ_В_КАТЕГОРИИ', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_all'       => [ '0', 'все', 'all' ],
	'pagesincategory_files'     => [ '0', 'файлы', 'files' ],
	'pagesincategory_pages'     => [ '0', 'страницы', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'подкатегории', 'subcats' ],
	'pagesinnamespace'          => [ '1', 'СТРАНИЦ_В_ПРОСТРАНСТВЕ_ИМЁН:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'РАЗМЕР_СТРАНИЦЫ', 'PAGESIZE' ],
	'plural'                    => [ '0', 'МНОЖЕСТВЕННОЕ_ЧИСЛО:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'УРОВЕНЬ_ЗАЩИТЫ', 'PROTECTIONLEVEL' ],
	'raw'                       => [ '0', 'НЕОБРАБ:', 'RAW:' ],
	'rawsuffix'                 => [ '1', 'Н', 'R' ],
	'redirect'                  => [ '0', '#перенаправление', '#перенапр', '#REDIRECT' ],
	'revisionday'               => [ '1', 'ДЕНЬ_ВЕРСИИ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'ДЕНЬ_ВЕРСИИ_2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'ИД_ВЕРСИИ', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'МЕСЯЦ_ВЕРСИИ', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'МЕСЯЦ_ВЕРСИИ_1', 'REVISIONMONTH1' ],
	'revisiontimestamp'         => [ '1', 'ОТМЕТКА_ВРЕМЕНИ_ВЕРСИИ', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'ВЕРСИЯ_УЧАСТНИКА', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'ГОД_ВЕРСИИ', 'REVISIONYEAR' ],
	'safesubst'                 => [ '0', 'ЗАЩПОДСТ:', 'SAFESUBST:' ],
	'scriptpath'                => [ '0', 'ПУТЬ_К_СКРИПТУ', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'СЕРВЕР', 'SERVER' ],
	'servername'                => [ '0', 'НАЗВАНИЕ_СЕРВЕРА', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ],
	'special'                   => [ '0', 'служебная', 'special' ],
	'staticredirect'            => [ '1', '__СТАТИЧЕСКОЕ_ПЕРЕНАПРАВЛЕНИЕ__', '__STATICREDIRECT__' ],
	'stylepath'                 => [ '0', 'ПУТЬ_К_СТИЛЮ', 'STYLEPATH' ],
	'subjectpagename'           => [ '1', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'ПРОСТРАНСТВО_СТАТЕЙ', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'ПРОСТРАНСТВО_СТАТЕЙ_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ_2', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'ПОДСТАНОВКА:', 'ПОДСТ:', 'SUBST:' ],
	'tag'                       => [ '0', 'метка', 'тег', 'тэг', 'tag' ],
	'talkpagename'              => [ '1', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ_2', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ_2', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ],
	'uc'                        => [ '0', 'БОЛЬШИМИ_БУКВАМИ:', 'UC:' ],
	'ucfirst'                   => [ '0', 'ПЕРВАЯ_БУКВА_БОЛЬШАЯ:', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'ЗАКОДИРОВАННЫЙ_АДРЕС:', 'URLENCODE:' ],
	'url_path'                  => [ '0', 'ПУТЬ', 'PATH' ],
	'url_query'                 => [ '0', 'ЗАПРОС', 'QUERY' ],
	'url_wiki'                  => [ '0', 'ВИКИ', 'WIKI' ],
];

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i, Y xg j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',

];

$bookstoreList = [
	'Поиск по библиотекам «Сигла»' => 'http://www.sigla.ru/results.jsp?f=7&t=3&v0=$1',
	'Findbook.ru' => 'http://findbook.ru/search/d0?ptype=4&pvalue=$1',
	'Яндекс.Маркет' => 'http://market.yandex.ru/search.xml?text=$1',
	'ОЗОН' => 'http://www.ozon.ru/?context=advsearch_book&isbn=$1',
	'Books.Ru' => 'http://www.books.ru/shop/search?query=$1',
	'Amazon.com' => 'https://www.amazon.com/exec/obidos/ISBN=$1'
];

$separatorTransformTable = [
	',' => "\u{00A0}", # nbsp
	'.' => ','
];
$minimumGroupingDigits = 2; // intentional deviation from CLDR (T262500)

$fallback8bitEncoding = 'windows-1251';
$linkPrefixExtension = false;

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюя]+)(.*)$/sDu';
