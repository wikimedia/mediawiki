<?php
/** Russian (русский)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Изображение' => NS_FILE,
	'Обсуждение_изображения' => NS_FILE_TALK,
);

$namespaceGenderAliases = array(
	NS_USER      => array( 'male' => 'Участник', 'female' => 'Участница' ),
	NS_USER_TALK => array( 'male' => 'Обсуждение_участника', 'female' => 'Обсуждение_участницы' ),
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Активные_участники' ),
	'Allmessages'               => array( 'Системные_сообщения' ),
	'AllMyUploads'              => array( 'Все_мои_файлы' ),
	'Allpages'                  => array( 'Все_страницы' ),
	'Badtitle'                  => array( 'Недопустимое_название' ),
	'Blankpage'                 => array( 'Пустая_страница' ),
	'Block'                     => array( 'Заблокировать' ),
	'Booksources'               => array( 'Источники_книг' ),
	'BrokenRedirects'           => array( 'Разорванные_перенаправления' ),
	'Categories'                => array( 'Категории' ),
	'ChangeEmail'               => array( 'Сменить_e-mail', 'Сменить_почту' ),
	'ChangePassword'            => array( 'Сменить_пароль' ),
	'ComparePages'              => array( 'Сравнение_страниц' ),
	'Confirmemail'              => array( 'Подтвердить_e-mail', 'Подтвердить_почту' ),
	'Contributions'             => array( 'Вклад' ),
	'CreateAccount'             => array( 'Создать_учётную_запись', 'Создать_пользователя', 'Зарегистрироваться' ),
	'Deadendpages'              => array( 'Тупиковые_страницы' ),
	'DeletedContributions'      => array( 'Удалённый_вклад' ),
	'DoubleRedirects'           => array( 'Двойные_перенаправления' ),
	'EditWatchlist'             => array( 'Править_список_наблюдения' ),
	'Emailuser'                 => array( 'Письмо_участнику', 'Отправить_письмо' ),
	'ExpandTemplates'           => array( 'Развёртка_шаблонов' ),
	'Export'                    => array( 'Экспорт', 'Выгрузка' ),
	'Fewestrevisions'           => array( 'Редко_редактируемые' ),
	'FileDuplicateSearch'       => array( 'Поиск_дубликатов_файлов' ),
	'Filepath'                  => array( 'Путь_к_файлу' ),
	'Import'                    => array( 'Импорт' ),
	'Invalidateemail'           => array( 'Отменить_подтверждение_адреса' ),
	'JavaScriptTest'            => array( 'Тестирование_JavaScript' ),
	'BlockList'                 => array( 'Список_блокировок', 'Блокировки' ),
	'LinkSearch'                => array( 'Поиск_ссылок' ),
	'Listadmins'                => array( 'Список_администраторов' ),
	'Listbots'                  => array( 'Список_ботов' ),
	'Listfiles'                 => array( 'Список_файлов', 'Список_изображений' ),
	'Listgrouprights'           => array( 'Права_групп_участников', 'Список_прав_групп' ),
	'Listredirects'             => array( 'Список_перенаправлений' ),
	'Listusers'                 => array( 'Список_участников' ),
	'Lockdb'                    => array( 'Заблокировать_БД', 'Заблокировать_базу_данных' ),
	'Log'                       => array( 'Журналы', 'Журнал' ),
	'Lonelypages'               => array( 'Изолированные_страницы' ),
	'Longpages'                 => array( 'Длинные_страницы' ),
	'MergeHistory'              => array( 'Объединение_историй' ),
	'MIMEsearch'                => array( 'Поиск_по_MIME' ),
	'Mostcategories'            => array( 'Самые_категоризованные' ),
	'Mostimages'                => array( 'Самые_используемые_файлы' ),
	'Mostinterwikis'            => array( 'Наибольшее_количество_интервики-ссылок' ),
	'Mostlinked'                => array( 'Самые_используемые_страницы' ),
	'Mostlinkedcategories'      => array( 'Самые_используемые_категории' ),
	'Mostlinkedtemplates'       => array( 'Самые_используемые_шаблоны' ),
	'Mostrevisions'             => array( 'Наибольшее_количество_версий' ),
	'Movepage'                  => array( 'Переименовать_страницу', 'Переименование', 'Переименовать' ),
	'Mycontributions'           => array( 'Мой_вклад' ),
	'Mypage'                    => array( 'Моя_страница' ),
	'Mytalk'                    => array( 'Моё_обсуждение' ),
	'Myuploads'                 => array( 'Мои_загрузки' ),
	'Newimages'                 => array( 'Новые_файлы' ),
	'Newpages'                  => array( 'Новые_страницы' ),
	'PasswordReset'             => array( 'Сброс_пароля' ),
	'PermanentLink'             => array( 'Постоянная_ссылка' ),
	'Popularpages'              => array( 'Популярные_страницы' ),
	'Preferences'               => array( 'Настройки' ),
	'Prefixindex'               => array( 'Указатель_по_началу_названия' ),
	'Protectedpages'            => array( 'Защищённые_страницы' ),
	'Protectedtitles'           => array( 'Защищённые_названия' ),
	'Randompage'                => array( 'Случайная_страница', 'Случайная' ),
	'Randomredirect'            => array( 'Случайное_перенаправление' ),
	'Recentchanges'             => array( 'Свежие_правки' ),
	'Recentchangeslinked'       => array( 'Связанные_правки' ),
	'Revisiondelete'            => array( 'Удаление_правки' ),
	'Search'                    => array( 'Поиск' ),
	'Shortpages'                => array( 'Короткие_страницы' ),
	'Specialpages'              => array( 'Спецстраницы' ),
	'Statistics'                => array( 'Статистика' ),
	'Tags'                      => array( 'Метки' ),
	'Unblock'                   => array( 'Разблокировка' ),
	'Uncategorizedcategories'   => array( 'Некатегоризованные_категории' ),
	'Uncategorizedimages'       => array( 'Некатегоризованные_файлы' ),
	'Uncategorizedpages'        => array( 'Некатегоризованные_страницы' ),
	'Uncategorizedtemplates'    => array( 'Некатегоризованные_шаблоны' ),
	'Undelete'                  => array( 'Восстановить', 'Восстановление' ),
	'Unlockdb'                  => array( 'Разблокировка_БД' ),
	'Unusedcategories'          => array( 'Неиспользуемые_категории' ),
	'Unusedimages'              => array( 'Неиспользуемые_файлы' ),
	'Unusedtemplates'           => array( 'Неиспользуемые_шаблоны' ),
	'Upload'                    => array( 'Загрузка' ),
	'UploadStash'               => array( 'Скрытная_загрузка' ),
	'Userlogin'                 => array( 'Вход' ),
	'Userlogout'                => array( 'Завершение_сеанса', 'Выход' ),
	'Userrights'                => array( 'Управление_правами' ),
	'Version'                   => array( 'Версия' ),
	'Wantedcategories'          => array( 'Требуемые_категории' ),
	'Wantedfiles'               => array( 'Требуемые_файлы' ),
	'Wantedpages'               => array( 'Требуемые_страницы' ),
	'Wantedtemplates'           => array( 'Требуемые_шаблоны' ),
	'Watchlist'                 => array( 'Список_наблюдения' ),
	'Whatlinkshere'             => array( 'Ссылки_сюда' ),
	'Withoutinterwiki'          => array( 'Без_интервики' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#перенаправление', '#перенапр', '#REDIRECT' ),
	'notoc'                     => array( '0', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__ОБЯЗ_ОГЛ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ),
	'noeditsection'             => array( '0', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'ТЕКУЩИЙ_МЕСЯЦ_1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_АБР', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ТЕКУЩИЙ_ЧАС', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'МЕСТНЫЙ_МЕСЯЦ', 'МЕСТНЫЙ_МЕСЯЦ_2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'МЕСТНЫЙ_МЕСЯЦ_1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_РОД', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_АБР', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'МЕСТНЫЙ_ДЕНЬ', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'МЕСТНЫЙ_ДЕНЬ_2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'НАЗВАНИЕ_МЕСТНОГО_ДНЯ', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'МЕСТНЫЙ_ГОД', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'МЕСТНОЕ_ВРЕМЯ', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'МЕСТНЫЙ_ЧАС', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'КОЛИЧЕСТВО_СТРАНИЦ', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'КОЛИЧЕСТВО_ФАЙЛОВ', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'КОЛИЧЕСТВО_УЧАСТНИКОВ', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'КОЛИЧЕСТВО_АКТИВНЫХ_УЧАСТНИКОВ', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'КОЛИЧЕСТВО_ПРАВОК', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'КОЛИЧЕСТВО_ПРОСМОТРОВ', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ_2', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ПРОСТРАНСТВО_ИМЁН_2', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', 'НОМЕР_ПРОСТРАНСТВА_ИМЁН', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ_2', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ПРОСТРАНСТВО_СТАТЕЙ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ПРОСТРАНСТВО_СТАТЕЙ_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ_2', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ_2', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ_2', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ_2', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'СООБЩЕНИЕ:', 'СООБЩ:', 'MSG:' ),
	'subst'                     => array( '0', 'ПОДСТАНОВКА:', 'ПОДСТ:', 'SUBST:' ),
	'safesubst'                 => array( '0', 'ЗАЩПОДСТ:', 'SAFESUBST:' ),
	'msgnw'                     => array( '0', 'СООБЩ_БЕЗ_ВИКИ:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'мини', 'миниатюра', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'мини=$1', 'миниатюра=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'справа', 'right' ),
	'img_left'                  => array( '1', 'слева', 'left' ),
	'img_none'                  => array( '1', 'без', 'none' ),
	'img_width'                 => array( '1', '$1пкс', '$1px' ),
	'img_center'                => array( '1', 'центр', 'center', 'centre' ),
	'img_framed'                => array( '1', 'обрамить', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'безрамки', 'frameless' ),
	'img_page'                  => array( '1', 'страница=$1', 'страница $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'сверхусправа', 'сверхусправа=$1', 'сверхусправа $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'граница', 'border' ),
	'img_baseline'              => array( '1', 'основание', 'baseline' ),
	'img_sub'                   => array( '1', 'под', 'sub' ),
	'img_super'                 => array( '1', 'над', 'super', 'sup' ),
	'img_top'                   => array( '1', 'сверху', 'top' ),
	'img_text_top'              => array( '1', 'текст-сверху', 'text-top' ),
	'img_middle'                => array( '1', 'посередине', 'middle' ),
	'img_bottom'                => array( '1', 'снизу', 'bottom' ),
	'img_text_bottom'           => array( '1', 'текст-снизу', 'text-bottom' ),
	'img_link'                  => array( '1', 'ссылка=$1', 'link=$1' ),
	'img_alt'                   => array( '1', 'альт=$1', 'alt=$1' ),
	'int'                       => array( '0', 'ВНУТР:', 'INT:' ),
	'sitename'                  => array( '1', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ),
	'ns'                        => array( '0', 'ПИ:', 'NS:' ),
	'nse'                       => array( '0', 'ПИК:', 'NSE:' ),
	'localurl'                  => array( '0', 'ЛОКАЛЬНЫЙ_АДРЕС:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'ЛОКАЛЬНЫЙ_АДРЕС_2:', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'ПУТЬ_К_СТАТЬЕ', 'ARTICLEPATH' ),
	'pageid'                    => array( '0', 'ИДЕНТИФИКАТОР_СТРАНИЦЫ', 'PAGEID' ),
	'server'                    => array( '0', 'СЕРВЕР', 'SERVER' ),
	'servername'                => array( '0', 'НАЗВАНИЕ_СЕРВЕРА', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'ПУТЬ_К_СКРИПТУ', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'ПУТЬ_К_СТИЛЮ', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'ПАДЕЖ:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'ПОЛ:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ЗАГОЛОВКА__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ТЕКСТА__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'ТЕКУЩАЯ_НЕДЕЛЯ', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'ТЕКУЩИЙ_ДЕНЬ_НЕДЕЛИ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'МЕСТНАЯ_НЕДЕЛЯ', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'МЕСТНЫЙ_ДЕНЬ_НЕДЕЛИ', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'ИД_ВЕРСИИ', 'REVISIONID' ),
	'revisionday'               => array( '1', 'ДЕНЬ_ВЕРСИИ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'ДЕНЬ_ВЕРСИИ_2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'МЕСЯЦ_ВЕРСИИ', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'МЕСЯЦ_ВЕРСИИ_1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'ГОД_ВЕРСИИ', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'ОТМЕТКА_ВРЕМЕНИ_ВЕРСИИ', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'ВЕРСИЯ_УЧАСТНИКА', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'МНОЖЕСТВЕННОЕ_ЧИСЛО:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'ПОЛНЫЙ_АДРЕС:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'ПОЛНЫЙ_АДРЕС_2:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'ПЕРВАЯ_БУКВА_МАЛЕНЬКАЯ:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'ПЕРВАЯ_БУКВА_БОЛЬШАЯ:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'МАЛЕНЬКИМИ_БУКВАМИ:', 'LC:' ),
	'uc'                        => array( '0', 'БОЛЬШИМИ_БУКВАМИ:', 'UC:' ),
	'raw'                       => array( '0', 'НЕОБРАБ:', 'RAW:' ),
	'displaytitle'              => array( '1', 'ПОКАЗАТЬ_ЗАГОЛОВОК', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'Н', 'R' ),
	'newsectionlink'            => array( '1', '__ССЫЛКА_НА_НОВЫЙ_РАЗДЕЛ__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__БЕЗ_ССЫЛКИ_НА_НОВЫЙ_РАЗДЕЛ__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'ТЕКУЩАЯ_ВЕРСИЯ', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'ЗАКОДИРОВАННЫЙ_АДРЕС:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'КОДИРОВАТЬ_МЕТКУ', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'ОТМЕТКА_ТЕКУЩЕГО_ВРЕМЕНИ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'ОТМЕТКА_МЕСТНОГО_ВРЕМЕНИ', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'НАПРАВЛЕНИЕ_ПИСЬМА', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#ЯЗЫК:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'ЯЗЫК_СОДЕРЖАНИЯ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'СТРАНИЦ_В_ПРОСТРАНСТВЕ_ИМЁН:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'КОЛИЧЕСТВО_АДМИНИСТРАТОРОВ', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'ФОРМАТИРОВАТЬ_ЧИСЛО', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'ЗАПОЛНИТЬ_СЛЕВА', 'PADLEFT' ),
	'padright'                  => array( '0', 'ЗАПОЛНИТЬ_СПРАВА', 'PADRIGHT' ),
	'special'                   => array( '0', 'служебная', 'special' ),
	'defaultsort'               => array( '1', 'СОРТИРОВКА_ПО_УМОЛЧАНИЮ', 'КЛЮЧ_СОРТИРОВКИ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'ПУТЬ_К_ФАЙЛУ:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'метка', 'тег', 'тэг', 'tag' ),
	'hiddencat'                 => array( '1', '__СКРЫТАЯ_КАТЕГОРИЯ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'СТРАНИЦ_В_КАТЕГОРИИ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'РАЗМЕР_СТРАНИЦЫ', 'PAGESIZE' ),
	'index'                     => array( '1', '__ИНДЕКС__', '__INDEX__' ),
	'noindex'                   => array( '1', '__БЕЗ_ИНДЕКСА__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'ЧИСЛО_В_ГРУППЕ', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__СТАТИЧЕСКОЕ_ПЕРЕНАПРАВЛЕНИЕ__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'УРОВЕНЬ_ЗАЩИТЫ', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'форматдаты', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'ПУТЬ', 'PATH' ),
	'url_wiki'                  => array( '0', 'ВИКИ', 'WIKI' ),
	'url_query'                 => array( '0', 'ЗАПРОС', 'QUERY' ),
	'pagesincategory_all'       => array( '0', 'все', 'all' ),
	'pagesincategory_pages'     => array( '0', 'страницы', 'pages' ),
	'pagesincategory_subcats'   => array( '0', 'подкатегории', 'subcats' ),
	'pagesincategory_files'     => array( '0', 'файлы', 'files' ),
);


$dateFormats = array(
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

);

$bookstoreList = array(
	'Поиск по библиотекам «Сигла»' => 'http://www.sigla.ru/results.jsp?f=7&t=3&v0=$1',
	'Findbook.ru' => 'http://findbook.ru/search/d0?ptype=4&pvalue=$1',
	'Яндекс.Маркет' => 'http://market.yandex.ru/search.xml?text=$1',
	'ОЗОН' => 'http://www.ozon.ru/?context=advsearch_book&isbn=$1',
	'Books.Ru' => 'http://www.books.ru/shop/search?query=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

/**
 * Изменения сделанные в этом файле будут потеряны при обновлении MediaWiki.
 *
 * Если необходимо внести изменения в перевод отдельных строк интерфейса,
 * сделайте это посредством редактирования страниц вида «MediaWiki:*».
 * Их список можно найти на странице «Special:Allmessages».
 */

$separatorTransformTable = array(
	',' => "\xc2\xa0", # nbsp
	'.' => ','
);

$fallback8bitEncoding = 'windows-1251';
$linkPrefixExtension = false;

$imageFiles = array(
	'button-bold'   => 'cyrl/button_bold.png',
	'button-italic' => 'cyrl/button_italic.png',
	'button-link'   => 'cyrl/button_link.png',
);

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюя]+)(.*)$/sDu';

