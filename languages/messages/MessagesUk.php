<?php
/** Ukrainian (українська)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author A1
 * @author AS
 * @author Aced
 * @author Ahonc
 * @author Aleksandrit
 * @author Alex Khimich
 * @author AlexSm
 * @author Amire80
 * @author Andrijko Z.
 * @author Andriykopanytsia
 * @author Arturyatsko
 * @author AtUkr
 * @author Base
 * @author Dim Grits
 * @author DixonD
 * @author DonDrakon
 * @author Dubyk
 * @author EugeneZelenko
 * @author Geitost
 * @author Gucci Mane Burrr
 * @author Gutsul (Gutsul.ua at Google Mail)
 * @author Ickis
 * @author Ilyaroz
 * @author Innv
 * @author KEL
 * @author Kalan
 * @author Matma Rex
 * @author Microcell
 * @author Nemo bis
 * @author NickK
 * @author Olvin
 * @author Prima klasy4na
 * @author RLuts
 * @author Riwnodennyk
 * @author Sodmy
 * @author Ua2004
 * @author Urhixidur
 * @author Vittalio
 * @author VolodymyrF
 * @author Vox
 * @author Yuriy Apostol
 * @author Ата
 * @author Дар'я Козлова
 * @author Максим Підліснюк
 * @author Тест
 */

$separatorTransformTable = [
	',' => "\xc2\xa0", # nbsp
	'.' => ','
];

$fallback = 'ru';
$fallback8bitEncoding = 'windows-1251';
$linkPrefixExtension = true;

$namespaceNames = [
	NS_MEDIA            => 'Медіа',
	NS_SPECIAL          => 'Спеціальна',
	NS_TALK             => 'Обговорення',
	NS_USER             => 'Користувач',
	NS_USER_TALK        => 'Обговорення_користувача',
	NS_PROJECT_TALK     => 'Обговорення_{{GRAMMAR:genitive|$1}}',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Обговорення_файлу',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Обговорення_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Обговорення_шаблону',
	NS_HELP             => 'Довідка',
	NS_HELP_TALK        => 'Обговорення_довідки',
	NS_CATEGORY         => 'Категорія',
	NS_CATEGORY_TALK    => 'Обговорення_категорії',
];

$namespaceAliases = [
	'Спеціальні' => NS_SPECIAL,
	'Зображення' => NS_FILE,
	'Обговорення_зображення' => NS_FILE_TALK,
	'Обговорення_шаблона' => NS_TEMPLATE_TALK,
];

// Remove Russian aliases
$namespaceGenderAliases = [];

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
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
];

$specialPageAliases = [
	'Activeusers'               => [ 'Активні_дописувачі' ],
	'Allmessages'               => [ 'Системні_повідомлення' ],
	'AllMyUploads'              => [ 'Усі_мої_файли' ],
	'Allpages'                  => [ 'Усі_сторінки' ],
	'Ancientpages'              => [ 'Давні_сторінки' ],
	'Badtitle'                  => [ 'Помилковий_заголовок' ],
	'Blankpage'                 => [ 'Порожня_сторінка' ],
	'Block'                     => [ 'Заблокувати' ],
	'Booksources'               => [ 'Джерела_книг' ],
	'BrokenRedirects'           => [ 'Розірвані_перенаправлення' ],
	'Categories'                => [ 'Категорії' ],
	'ChangeEmail'               => [ 'Змінити_e-mail' ],
	'ChangePassword'            => [ 'Змінити_пароль' ],
	'ComparePages'              => [ 'Порівняння_сторінок' ],
	'Confirmemail'              => [ 'Підтвердити_e-mail' ],
	'Contributions'             => [ 'Внесок' ],
	'CreateAccount'             => [ 'Створити_обліковий_запис' ],
	'Deadendpages'              => [ 'Сторінки_без_посилань' ],
	'DeletedContributions'      => [ 'Вилучений_внесок' ],
	'DoubleRedirects'           => [ 'Подвійні_перенаправлення' ],
	'EditWatchlist'             => [ 'Редагувати_список_спостереження' ],
	'Emailuser'                 => [ 'Лист_користувачеві' ],
	'ExpandTemplates'           => [ 'Розгортання_шаблонів' ],
	'Export'                    => [ 'Експорт' ],
	'Fewestrevisions'           => [ 'Найменшредаговані' ],
	'FileDuplicateSearch'       => [ 'Пошук_дублікатів_файлів' ],
	'Filepath'                  => [ 'Шлях_до_файлу' ],
	'Import'                    => [ 'Імпорт' ],
	'Invalidateemail'           => [ 'Неперевірена_email-адреса' ],
	'JavaScriptTest'            => [ 'JavaScript_тест' ],
	'BlockList'                 => [ 'Список_блокувань', 'Блокування', 'Блокування_IP-адрес' ],
	'LinkSearch'                => [ 'Пошук_посилань' ],
	'Listadmins'                => [ 'Список_адміністраторів' ],
	'Listbots'                  => [ 'Список_ботів' ],
	'Listfiles'                 => [ 'Список_файлів' ],
	'Listgrouprights'           => [ 'Список_прав_груп', 'Права_груп_користувачів' ],
	'Listredirects'             => [ 'Список_перенаправлень' ],
	'ListDuplicatedFiles'       => [ 'Список_дубльованих_файлів' ],
	'Listusers'                 => [ 'Список_користувачів' ],
	'Lockdb'                    => [ 'Заблокувати_базу_даних' ],
	'Log'                       => [ 'Журнали' ],
	'Lonelypages'               => [ 'Ізольовані_сторінки' ],
	'Longpages'                 => [ 'Найдовші_сторінки' ],
	'MergeHistory'              => [ 'Об\'єднання_історії' ],
	'MIMEsearch'                => [ 'Пошук_за_MIME' ],
	'Mostcategories'            => [ 'Найбільш_категоризовані' ],
	'Mostimages'                => [ 'Найуживаніші_файли' ],
	'Mostinterwikis'            => [ 'Найбільше_інтервікі' ],
	'Mostlinked'                => [ 'Найуживаніші_сторінки', 'Найбільше_посилань' ],
	'Mostlinkedcategories'      => [ 'Найуживаніші_категорії' ],
	'Mostlinkedtemplates'       => [ 'Найуживаніші_шаблони' ],
	'Mostrevisions'             => [ 'Найбільш_редаговані' ],
	'Movepage'                  => [ 'Перейменувати' ],
	'Mycontributions'           => [ 'Мій_внесок' ],
	'MyLanguage'                => [ 'Моя_мова' ],
	'Mypage'                    => [ 'Моя_сторінка' ],
	'Mytalk'                    => [ 'Моє_обговорення' ],
	'Myuploads'                 => [ 'Мої_завантаження' ],
	'Newimages'                 => [ 'Нові_файли' ],
	'Newpages'                  => [ 'Нові_сторінки' ],
	'PasswordReset'             => [ 'Скинути_пароль' ],
	'PermanentLink'             => [ 'Постійне_посилання' ],
	'Preferences'               => [ 'Налаштування' ],
	'Prefixindex'               => [ 'Покажчик_за_початком_назви' ],
	'Protectedpages'            => [ 'Захищені_сторінки' ],
	'Protectedtitles'           => [ 'Захищені_назви_сторінок' ],
	'Randompage'                => [ 'Випадкова_сторінка' ],
	'Randomredirect'            => [ 'Випадкове_перенаправлення' ],
	'Recentchanges'             => [ 'Нові_редагування' ],
	'Recentchangeslinked'       => [ 'Пов\'язані_редагування' ],
	'Redirect'                  => [ 'Перенаправлення' ],
	'Revisiondelete'            => [ 'Вилучити_редагування' ],
	'Search'                    => [ 'Пошук' ],
	'Shortpages'                => [ 'Короткі_сторінки' ],
	'Specialpages'              => [ 'Спеціальні_сторінки' ],
	'Statistics'                => [ 'Статистика' ],
	'Tags'                      => [ 'Мітки' ],
	'Unblock'                   => [ 'Розблокувати' ],
	'Uncategorizedcategories'   => [ 'Некатегоризовані_категорії' ],
	'Uncategorizedimages'       => [ 'Некатегоризовані_файли' ],
	'Uncategorizedpages'        => [ 'Некатегоризовані_сторінки' ],
	'Uncategorizedtemplates'    => [ 'Некатегоризовані_шаблони' ],
	'Undelete'                  => [ 'Відновити' ],
	'Unlockdb'                  => [ 'Розблокувати_базу_даних' ],
	'Unusedcategories'          => [ 'Порожні_категорії' ],
	'Unusedimages'              => [ 'Невикористані_файли' ],
	'Unusedtemplates'           => [ 'Невикористані_шаблони' ],
	'Unwatchedpages'            => [ 'Неспостережувані' ],
	'Upload'                    => [ 'Завантаження' ],
	'UploadStash'               => [ 'Приховане_завантаження' ],
	'Userlogin'                 => [ 'Вхід' ],
	'Userlogout'                => [ 'Вихід' ],
	'Userrights'                => [ 'Керування_правами_користувачів' ],
	'Version'                   => [ 'Версія' ],
	'Wantedcategories'          => [ 'Потрібні_категорії' ],
	'Wantedfiles'               => [ 'Потрібні_файли' ],
	'Wantedpages'               => [ 'Потрібні_сторінки' ],
	'Wantedtemplates'           => [ 'Потрібні_шаблони' ],
	'Watchlist'                 => [ 'Список_спостереження' ],
	'Whatlinkshere'             => [ 'Посилання_сюди' ],
	'Withoutinterwiki'          => [ 'Без_інтервікі' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#ПЕРЕНАПРАВЛЕННЯ', '#ПЕРЕНАПР', '#перенапр', '#перенаправление', '#REDIRECT' ],
	'notoc'                     => [ '0', '__БЕЗ_ЗМІСТУ__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__БЕЗ_ГАЛЕРЕЇ__', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__ОБОВ_ЗМІСТ__', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__ОБЯЗ_ОГЛ__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__ЗМІСТ__', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ],
	'noeditsection'             => [ '0', '__БЕЗ_РЕДАГУВ_РОЗДІЛУ__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'ПОТОЧНИЙ_МІСЯЦЬ', 'ПОТОЧНИЙ_МІСЯЦЬ_2', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'ПОТОЧНИЙ_МІСЯЦЬ_1', 'ТЕКУЩИЙ_МЕСЯЦ_1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'НАЗВА_ПОТОЧНОГО_МІСЯЦЯ', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'НАЗВА_ПОТОЧНОГО_МІСЯЦЯ_РОД', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'НАЗВА_ПОТОЧНОГО_МІСЯЦЯ_АБР', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_АБР', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'ПОТОЧНИЙ_ДЕНЬ', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'ПОТОЧНИЙ_ДЕНЬ_2', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'НАЗВА_ПОТОЧНОГО_ДНЯ', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'ПОТОЧНИЙ_РІК', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'ПОТОЧНИЙ_ЧАС', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'ПОТОЧНА_ГОДИНА', 'ТЕКУЩИЙ_ЧАС', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'ЛОКАЛЬНИЙ_МІСЯЦЬ', 'ЛОКАЛЬНИЙ_МІСЯЦЬ_2', 'МЕСТНЫЙ_МЕСЯЦ', 'МЕСТНЫЙ_МЕСЯЦ_2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'ЛОКАЛЬНИЙ_МІСЯЦЬ_1', 'МЕСТНЫЙ_МЕСЯЦ_1', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', 'НАЗВА_ЛОКАЛЬНОГО_МІСЯЦЯ', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'НАЗВА_ЛОКАЛЬНОГО_МІСЯЦЯ_РОД', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_РОД', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'НАЗВА_ЛОКАЛЬНОГО_МІСЯЦЯ_АБР', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_АБР', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'ЛОКАЛЬНИЙ_ДЕНЬ', 'МІСЦЕВИЙ_ДЕНЬ', 'МЕСТНЫЙ_ДЕНЬ', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'ЛОКАЛЬНИЙ_ДЕНЬ_2', 'МІСЦЕВИЙ_ДЕНЬ_2', 'МЕСТНЫЙ_ДЕНЬ_2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'НАЗВА_ЛОКАЛЬНОГО_ДНЯ', 'НАЗВА_МІСЦЕВОГО_ДНЯ', 'НАЗВАНИЕ_МЕСТНОГО_ДНЯ', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'ЛОКАЛЬНИЙ_РІК', 'МІСЦЕВИЙ_РІК', 'МЕСТНЫЙ_ГОД', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'ЛОКАЛЬНИЙ_ЧАС', 'МІСЦЕВИЙ_ЧАС', 'МЕСТНОЕ_ВРЕМЯ', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'ЛОКАЛЬНА_ГОДИНА', 'МІСЦЕВА_ГОДИНА', 'МЕСТНЫЙ_ЧАС', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'КІЛЬКІСТЬ_СТОРІНОК', 'КОЛИЧЕСТВО_СТРАНИЦ', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'КІЛЬКІСТЬ_СТАТЕЙ', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'КІЛЬКІСТЬ_ФАЙЛІВ', 'КОЛИЧЕСТВО_ФАЙЛОВ', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'КІЛЬКІСТЬ_КОРИСТУВАЧІВ', 'КОЛИЧЕСТВО_УЧАСТНИКОВ', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'КІЛЬКІСТЬ_АКТИВНИХ_КОРИСТУВАЧІВ', 'КОЛИЧЕСТВО_АКТИВНЫХ_УЧАСТНИКОВ', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'КІЛЬКІСТЬ_РЕДАГУВАНЬ', 'КОЛИЧЕСТВО_ПРАВОК', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'НАЗВА_СТОРІНКИ', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'НАЗВА_СТОРІНКИ_2', 'НАЗВАНИЕ_СТРАНИЦЫ_2', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'ПРОСТІР_НАЗВ', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ПРОСТІР_НАЗВ_2', 'ПРОСТРАНСТВО_ИМЁН_2', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'ПРОСТІР_ОБГОВОРЕННЯ', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'ПРОСТІР_ОБГОВОРЕННЯ_2', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ_2', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'ПРОСТІР_СТАТЕЙ', 'ПРОСТРАНСТВО_СТАТЕЙ', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'ПРОСТІР_СТАТЕЙ_2', 'ПРОСТРАНСТВО_СТАТЕЙ_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'ПОВНА_НАЗВА_СТОРІНКИ', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ПОВНА_НАЗВА_СТОРІНКИ_2', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ_2', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'НАЗВА_ПІДСТОРІНКИ', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'НАЗВА_ПІДСТОРІНКИ_2', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ_2', 'SUBPAGENAMEE' ],
	'basepagename'              => [ '1', 'ОСНОВА_НАЗВИ_ПІДСТОРІНКИ', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'ОСНОВА_НАЗВИ_ПІДСТОРІНКИ_2', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ_2', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'НАЗВА_СТОРІНКИ_ОБГОВОРЕННЯ', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'НАЗВА_СТОРІНКИ_ОБГОВОРЕННЯ_2', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ_2', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', 'НАЗВА_СТАТТІ', 'НАЗВА_СТОРІНКИ_СТАТТІ', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'НАЗВА_СТАТТІ_2', 'НАЗВА_СТОРІНКИ_СТАТТІ_2', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'msg'                       => [ '0', 'ПОВІД:', 'ПОВІДОМЛЕННЯ:', 'СООБЩЕНИЕ:', 'СООБЩ:', 'MSG:' ],
	'subst'                     => [ '0', 'ПІДСТ:', 'ПІДСТАНОВКА:', 'ПОДСТАНОВКА:', 'ПОДСТ:', 'SUBST:' ],
	'safesubst'                 => [ '0', 'БЕЗПЕЧНА_ПІДСТАНОВКА:', 'ЗАЩПОДСТ:', 'SAFESUBST:' ],
	'msgnw'                     => [ '0', 'ПОВІД_БЕЗ_ВІКІ:', 'СООБЩ_БЕЗ_ВИКИ:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'міні', 'мини', 'мініатюра', 'миниатюра', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'міні=$1', 'мініатюра=$1', 'мини=$1', 'миниатюра=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'праворуч', 'справа', 'right' ],
	'img_left'                  => [ '1', 'ліворуч', 'слева', 'left' ],
	'img_none'                  => [ '1', 'без', 'none' ],
	'img_width'                 => [ '1', '$1пкс', '$1px' ],
	'img_center'                => [ '1', 'центр', 'center', 'centre' ],
	'img_framed'                => [ '1', 'обрамити', 'рамка', 'обрамить', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'безрамки', 'frameless' ],
	'img_page'                  => [ '1', 'сторінка=$1', 'сторінка_$1', 'страница=$1', 'страница $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'зверхуправоруч', 'зверхуправоруч=$1', 'зверхуправоруч_$1', 'сверхусправа', 'сверхусправа=$1', 'сверхусправа $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'межа', 'границя', 'граница', 'border' ],
	'img_baseline'              => [ '1', 'основа', 'основание', 'baseline' ],
	'img_sub'                   => [ '1', 'під', 'под', 'sub' ],
	'img_super'                 => [ '1', 'над', 'super', 'sup' ],
	'img_top'                   => [ '1', 'зверху', 'сверху', 'top' ],
	'img_text_top'              => [ '1', 'текст-зверху', 'текст-сверху', 'text-top' ],
	'img_middle'                => [ '1', 'посередині', 'посередине', 'middle' ],
	'img_bottom'                => [ '1', 'знизу', 'снизу', 'bottom' ],
	'img_text_bottom'           => [ '1', 'текст-знизу', 'текст-снизу', 'text-bottom' ],
	'img_link'                  => [ '1', 'посилання=$1', 'ссылка=$1', 'link=$1' ],
	'img_alt'                   => [ '1', 'альт=$1', 'alt=$1' ],
	'int'                       => [ '0', 'ВНУТР:', 'INT:' ],
	'sitename'                  => [ '1', 'НАЗВА_САЙТУ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ],
	'ns'                        => [ '0', 'ПН:', 'ПИ:', 'NS:' ],
	'nse'                       => [ '0', 'ПН_2:', 'ПИК:', 'NSE:' ],
	'localurl'                  => [ '0', 'ЛОКАЛЬНА_АДРЕСА:', 'ЛОКАЛЬНЫЙ_АДРЕС:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'ЛОКАЛЬНА_АДРЕСА_2:', 'ЛОКАЛЬНЫЙ_АДРЕС_2:', 'LOCALURLE:' ],
	'articlepath'               => [ '0', 'ШЛЯХ_ДО_СТАТТІ', 'ПУТЬ_К_СТАТЬЕ', 'ARTICLEPATH' ],
	'pageid'                    => [ '0', 'ІДЕНТИФІКАТОР_СТОРІНКИ', 'ИДЕНТИФИКАТОР_СТРАНИЦЫ', 'PAGEID' ],
	'server'                    => [ '0', 'СЕРВЕР', 'SERVER' ],
	'servername'                => [ '0', 'НАЗВА_СЕРВЕРА', 'НАЗВАНИЕ_СЕРВЕРА', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'ШЛЯХ_ДО_СКРИПТУ', 'ПУТЬ_К_СКРИПТУ', 'SCRIPTPATH' ],
	'stylepath'                 => [ '0', 'ШЛЯХ_ДО_СТИЛЮ', 'ПУТЬ_К_СТИЛЮ', 'STYLEPATH' ],
	'grammar'                   => [ '0', 'ВІДМІНОК:', 'ПАДЕЖ:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'СТАТЬ:', 'ПОЛ:', 'GENDER:' ],
	'notitleconvert'            => [ '0', '__БЕЗ_ПЕРЕТВОРЕННЯ_ЗАГОЛОВКУ__', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ЗАГОЛОВКА__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__БЕЗ_ПЕРЕТВОРЕННЯ_ТЕКСТУ__', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ТЕКСТА__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'ПОТОЧНИЙ_ТИЖДЕНЬ', 'ТЕКУЩАЯ_НЕДЕЛЯ', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'ПОТОЧНИЙ_ДЕНЬ_ТИЖНЯ', 'ТЕКУЩИЙ_ДЕНЬ_НЕДЕЛИ', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'ЛОКАЛЬНИЙ_ТИЖДЕНЬ', 'МЕСТНАЯ_НЕДЕЛЯ', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'ЛОКАЛЬНИЙ_ДЕНЬ_ТИЖНЯ', 'МЕСТНЫЙ_ДЕНЬ_НЕДЕЛИ', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'ІД_ВЕРСІЇ', 'ИД_ВЕРСИИ', 'REVISIONID' ],
	'revisionday'               => [ '1', 'ДЕНЬ_ВЕРСІЇ', 'ДЕНЬ_ВЕРСИИ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'ДЕНЬ_ВЕРСІЇ_2', 'ДЕНЬ_ВЕРСИИ_2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'МІСЯЦЬ_ВЕРСІЇ', 'МЕСЯЦ_ВЕРСИИ', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'МІСЯЦЬ_ВЕРСІЇ_1', 'МЕСЯЦ_ВЕРСИИ_1', 'REVISIONMONTH1' ],
	'revisionyear'              => [ '1', 'РІК_ВЕРСІЇ', 'ГОД_ВЕРСИИ', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'МІТКА_ЧАСУ_ВЕРСІЇ', 'ОТМЕТКА_ВРЕМЕНИ_ВЕРСИИ', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'ВЕРСІЯ_КОРИСТУВАЧА', 'ВЕРСИЯ_УЧАСТНИКА', 'REVISIONUSER' ],
	'plural'                    => [ '0', 'МНОЖИНА:', 'МНОЖЕСТВЕННОЕ_ЧИСЛО:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'ПОВНА_АДРЕСА:', 'ПОЛНЫЙ_АДРЕС:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'ПОВНА_АДРЕСА_2:', 'ПОЛНЫЙ_АДРЕС_2:', 'FULLURLE:' ],
	'lcfirst'                   => [ '0', 'НР_ПЕРША:', 'ПЕРША_БУКВА_МАЛА:', 'ПЕРША_ЛІТЕРА_МАЛА:', 'МАЛА_ПЕРША_ЛІТЕРА:', 'ПЕРВАЯ_БУКВА_МАЛЕНЬКАЯ:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'ВР_ПЕРША:', 'ПЕРША_БУКВА_ВЕЛИКА:', 'ПЕША_ДІТЕРА_ВЕЛИКА:', 'ВЕЛИКА_ПЕРША_ЛІТЕРА:', 'ПЕРВАЯ_БУКВА_БОЛЬШАЯ:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'НР:', 'НИЖНІЙ_РЕГІСТР:', 'МАЛИМИ_БУКВАМИ:', 'МАЛИМИ_ЛІТЕРАМИ:', 'МАЛЕНЬКИМИ_БУКВАМИ:', 'LC:' ],
	'uc'                        => [ '0', 'ВР:', 'ВЕРХНІЙ_РЕГІСТР:', 'ВЕЛИКИМИ_БУКВАМИ:', 'ВЕЛИКИМИ_ЛІТЕРАМИ:', 'БОЛЬШИМИ_БУКВАМИ:', 'UC:' ],
	'raw'                       => [ '0', 'НЕОБРОБ:', 'НЕОБРАБ:', 'RAW:' ],
	'displaytitle'              => [ '1', 'ПОКАЗАТИ_ЗАГОЛОВОК', 'ПОКАЗАТЬ_ЗАГОЛОВОК', 'DISPLAYTITLE' ],
	'rawsuffix'                 => [ '1', 'Н', 'R' ],
	'newsectionlink'            => [ '1', '__ПОСИЛАННЯ_НА_НОВИЙ_РОЗДІЛ__', '__ССЫЛКА_НА_НОВЫЙ_РАЗДЕЛ__', '__NEWSECTIONLINK__' ],
	'nonewsectionlink'          => [ '1', '__БЕЗ_ПОСИЛАННЯ_НА_НОВИЙ_РОЗДІЛ__', '__БЕЗ_ССЫЛКИ_НА_НОВЫЙ_РАЗДЕЛ__', '__NONEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'ПОТОЧНА_ВЕРСІЯ', 'ТЕКУЩАЯ_ВЕРСИЯ', 'CURRENTVERSION' ],
	'urlencode'                 => [ '0', 'ЗАКОДОВАНА_АДРЕСА:', 'ЗАКОДИРОВАННЫЙ_АДРЕС:', 'URLENCODE:' ],
	'anchorencode'              => [ '0', 'КОДУВАТИ_МІТКУ', 'КОДИРОВАТЬ_МЕТКУ', 'ANCHORENCODE' ],
	'currenttimestamp'          => [ '1', 'МІТКА_ПОТОЧНОГО_ЧАСУ', 'ОТМЕТКА_ТЕКУЩЕГО_ВРЕМЕНИ', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'МІТКА_ЛОКАЛЬНОГО_ЧАСУ', 'ОТМЕТКА_МЕСТНОГО_ВРЕМЕНИ', 'LOCALTIMESTAMP' ],
	'directionmark'             => [ '1', 'НАПРЯМОК_ПИСЬМА', 'НАПРАВЛЕНИЕ_ПИСЬМА', 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                  => [ '0', '#МОВА:', '#ЯЗЫК:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'МОВА_ВМІСТУ', 'ЯЗЫК_СОДЕРЖАНИЯ', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'СТОРІНОК_У_ПРОСТОРІ_НАЗВ:', 'СТРАНИЦ_В_ПРОСТРАНСТВЕ_ИМЁН:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'КІЛЬКІСТЬ_АДМІНІСТРАТОРІВ', 'КОЛИЧЕСТВО_АДМИНИСТРАТОРОВ', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'ФОРМАТУВАТИ_ЧИСЛО', 'ФОРМАТИРОВАТЬ_ЧИСЛО', 'FORMATNUM' ],
	'padleft'                   => [ '0', 'ЗАПОВНИТИ_ЛІВОРУЧ', 'ЗАПОЛНИТЬ_СЛЕВА', 'PADLEFT' ],
	'padright'                  => [ '0', 'ЗАПОВНИТИ_ПРАВОРУЧ', 'ЗАПОЛНИТЬ_СПРАВА', 'PADRIGHT' ],
	'special'                   => [ '0', 'спеціальна', 'служебная', 'special' ],
	'defaultsort'               => [ '1', 'СТАНДАРТНЕ_СОРТУВАННЯ:_КЛЮЧ_СОРТУВАННЯ', 'СОРТИРОВКА_ПО_УМОЛЧАНИЮ', 'КЛЮЧ_СОРТИРОВКИ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'ШЛЯХ_ДО_ФАЙЛУ:', 'ПУТЬ_К_ФАЙЛУ:', 'FILEPATH:' ],
	'tag'                       => [ '0', 'тег', 'мітка', 'теґ', 'метка', 'тэг', 'tag' ],
	'hiddencat'                 => [ '1', '__ПРИХОВ_КАТ__', '__ПРИХОВАНА_КАТЕГОРІЯ__', '__СКРЫТАЯ_КАТЕГОРИЯ__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'СТОР_В_КАТ', 'СТОР_У_КАТ', 'СТОРІНОК_У_КАТЕГОРІЇ', 'СТРАНИЦ_В_КАТЕГОРИИ', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'РОЗМІР', 'РОЗМІР_СТОРІНКИ', 'РАЗМЕР_СТРАНИЦЫ', 'PAGESIZE' ],
	'index'                     => [ '1', '__ІНДЕКС__', '__ИНДЕКС__', '__INDEX__' ],
	'noindex'                   => [ '1', '__БЕЗ_ІНДЕКСУ__', '__БЕЗ_ИНДЕКСА__', '__NOINDEX__' ],
	'numberingroup'             => [ '1', 'КІЛЬКІСТЬ_У_ГРУПІ', 'ЧИСЛО_В_ГРУППЕ', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'staticredirect'            => [ '1', '__СТАТИЧНЕ_ПЕРЕНАПРАВЛЕННЯ__', '__СТАТИЧЕСКОЕ_ПЕРЕНАПРАВЛЕНИЕ__', '__STATICREDIRECT__' ],
	'protectionlevel'           => [ '1', 'РІВЕНЬ_ЗАХИСТУ', 'УРОВЕНЬ_ЗАЩИТЫ', 'PROTECTIONLEVEL' ],
	'formatdate'                => [ '0', 'форматдати', 'форматдаты', 'formatdate', 'dateformat' ],
	'url_path'                  => [ '0', 'ШЛЯХ', 'ПУТЬ', 'PATH' ],
	'url_wiki'                  => [ '0', 'ВІКІ', 'ВИКИ', 'WIKI' ],
	'url_query'                 => [ '0', 'ЗАПИТ', 'ЗАПРОС', 'QUERY' ],
	'pagesincategory_all'       => [ '0', 'усе', 'все', 'all' ],
	'pagesincategory_pages'     => [ '0', 'сторінки', 'страницы', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'підкатегорії', 'подкатегории', 'subcats' ],
];

$linkTrail = '/^([a-zабвгґдеєжзиіїйклмнопрстуфхцчшщьєюяёъы“»]+)(.*)$/sDu';
$linkPrefixCharset = '„«';

