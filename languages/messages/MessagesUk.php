<?php
/** Ukrainian (українська)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
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

$separatorTransformTable = array(
	',' => "\xc2\xa0", # nbsp
	'.' => ','
);

$fallback = 'ru';
$fallback8bitEncoding = 'windows-1251';
$linkPrefixExtension = true;

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Спеціальні' => NS_SPECIAL,
	'Зображення' => NS_FILE,
	'Обговорення_зображення' => NS_FILE_TALK,
	'Обговорення_шаблона' => NS_TEMPLATE_TALK,
);

// Remove Russian aliases
$namespaceGenderAliases = array();

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
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Активні_дописувачі' ),
	'Allmessages'               => array( 'Системні_повідомлення' ),
	'Allpages'                  => array( 'Усі_сторінки' ),
	'Ancientpages'              => array( 'Давні_сторінки' ),
	'Badtitle'                  => array( 'Помилковий_заголовок' ),
	'Blankpage'                 => array( 'Порожня_сторінка' ),
	'Block'                     => array( 'Заблокувати' ),
	'Booksources'               => array( 'Джерела_книг' ),
	'BrokenRedirects'           => array( 'Розірвані_перенаправлення' ),
	'Categories'                => array( 'Категорії' ),
	'ChangeEmail'               => array( 'Змінити_e-mail' ),
	'ChangePassword'            => array( 'Змінити_пароль' ),
	'ComparePages'              => array( 'Порівняння_сторінок' ),
	'Confirmemail'              => array( 'Підтвердити_e-mail' ),
	'Contributions'             => array( 'Внесок' ),
	'CreateAccount'             => array( 'Створити_обліковий_запис' ),
	'Deadendpages'              => array( 'Сторінки_без_посилань' ),
	'DeletedContributions'      => array( 'Вилучений_внесок' ),
	'DoubleRedirects'           => array( 'Подвійні_перенаправлення' ),
	'EditWatchlist'             => array( 'Редагувати_список_спостереження' ),
	'Emailuser'                 => array( 'Лист_користувачеві' ),
	'ExpandTemplates'           => array( 'Розгортання_шаблонів' ),
	'Export'                    => array( 'Експорт' ),
	'Fewestrevisions'           => array( 'Найменшредаговані' ),
	'FileDuplicateSearch'       => array( 'Пошук_дублікатів_файлів' ),
	'Filepath'                  => array( 'Шлях_до_файлу' ),
	'Import'                    => array( 'Імпорт' ),
	'Invalidateemail'           => array( 'Неперевірена_email-адреса' ),
	'BlockList'                 => array( 'Список_блокувань', 'Блокування', 'Блокування_IP-адрес' ),
	'LinkSearch'                => array( 'Пошук_посилань' ),
	'Listadmins'                => array( 'Список_адміністраторів' ),
	'Listbots'                  => array( 'Список_ботів' ),
	'Listfiles'                 => array( 'Список_файлів' ),
	'Listgrouprights'           => array( 'Список_прав_груп', 'Права_груп_користувачів' ),
	'Listredirects'             => array( 'Список_перенаправлень' ),
	'Listusers'                 => array( 'Список_користувачів' ),
	'Lockdb'                    => array( 'Заблокувати_базу_даних' ),
	'Log'                       => array( 'Журнали' ),
	'Lonelypages'               => array( 'Ізольовані_сторінки' ),
	'Longpages'                 => array( 'Найдовші_сторінки' ),
	'MergeHistory'              => array( 'Об\'єднання_історії' ),
	'MIMEsearch'                => array( 'Пошук_за_MIME' ),
	'Mostcategories'            => array( 'Найбільш_категоризовані' ),
	'Mostimages'                => array( 'Найуживаніші_файли' ),
	'Mostinterwikis'            => array( 'Найбільше_інтервікі' ),
	'Mostlinked'                => array( 'Найуживаніші_сторінки', 'Найбільше_посилань' ),
	'Mostlinkedcategories'      => array( 'Найуживаніші_категорії' ),
	'Mostlinkedtemplates'       => array( 'Найуживаніші_шаблони' ),
	'Mostrevisions'             => array( 'Найбільш_редаговані' ),
	'Movepage'                  => array( 'Перейменувати' ),
	'Mycontributions'           => array( 'Мій_внесок' ),
	'Mypage'                    => array( 'Моя_сторінка' ),
	'Mytalk'                    => array( 'Моє_обговорення' ),
	'Myuploads'                 => array( 'Мої_завантаження' ),
	'Newimages'                 => array( 'Нові_файли' ),
	'Newpages'                  => array( 'Нові_сторінки' ),
	'PasswordReset'             => array( 'Скинути_пароль' ),
	'PermanentLink'             => array( 'Постійне_посилання' ),
	'Popularpages'              => array( 'Популярні_сторінки' ),
	'Preferences'               => array( 'Налаштування' ),
	'Prefixindex'               => array( 'Покажчик_за_початком_назви' ),
	'Protectedpages'            => array( 'Захищені_сторінки' ),
	'Protectedtitles'           => array( 'Захищені_назви_сторінок' ),
	'Randompage'                => array( 'Випадкова_сторінка' ),
	'Randomredirect'            => array( 'Випадкове_перенаправлення' ),
	'Recentchanges'             => array( 'Нові_редагування' ),
	'Recentchangeslinked'       => array( 'Пов\'язані_редагування' ),
	'Revisiondelete'            => array( 'Вилучити_редагування' ),
	'Search'                    => array( 'Пошук' ),
	'Shortpages'                => array( 'Короткі_сторінки' ),
	'Specialpages'              => array( 'Спеціальні_сторінки' ),
	'Statistics'                => array( 'Статистика' ),
	'Tags'                      => array( 'Мітки' ),
	'Unblock'                   => array( 'Розблокувати' ),
	'Uncategorizedcategories'   => array( 'Некатегоризовані_категорії' ),
	'Uncategorizedimages'       => array( 'Некатегоризовані_файли' ),
	'Uncategorizedpages'        => array( 'Некатегоризовані_сторінки' ),
	'Uncategorizedtemplates'    => array( 'Некатегоризовані_шаблони' ),
	'Undelete'                  => array( 'Відновити' ),
	'Unlockdb'                  => array( 'Розблокувати_базу_даних' ),
	'Unusedcategories'          => array( 'Порожні_категорії' ),
	'Unusedimages'              => array( 'Невикористані_файли' ),
	'Unusedtemplates'           => array( 'Невикористані_шаблони' ),
	'Unwatchedpages'            => array( 'Неспостережувані' ),
	'Upload'                    => array( 'Завантаження' ),
	'UploadStash'               => array( 'Приховане_завантаження' ),
	'Userlogin'                 => array( 'Вхід' ),
	'Userlogout'                => array( 'Вихід' ),
	'Userrights'                => array( 'Керування_правами_користувачів' ),
	'Version'                   => array( 'Версія' ),
	'Wantedcategories'          => array( 'Потрібні_категорії' ),
	'Wantedfiles'               => array( 'Потрібні_файли' ),
	'Wantedpages'               => array( 'Потрібні_сторінки' ),
	'Wantedtemplates'           => array( 'Потрібні_шаблони' ),
	'Watchlist'                 => array( 'Список_спостереження' ),
	'Whatlinkshere'             => array( 'Посилання_сюди' ),
	'Withoutinterwiki'          => array( 'Без_інтервікі' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ПЕРЕНАПРАВЛЕННЯ', '#ПЕРЕНАПР', '#перенапр', '#перенаправление', '#REDIRECT' ),
	'notoc'                     => array( '0', '__БЕЗ_ЗМІСТУ__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__БЕЗ_ГАЛЕРЕЇ__', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ОБОВ_ЗМІСТ__', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__ОБЯЗ_ОГЛ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ЗМІСТ__', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ),
	'noeditsection'             => array( '0', '__БЕЗ_РЕДАГУВ_РОЗДІЛУ__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'ПОТОЧНИЙ_МІСЯЦЬ', 'ПОТОЧНИЙ_МІСЯЦЬ_2', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'ПОТОЧНИЙ_МІСЯЦЬ_1', 'ТЕКУЩИЙ_МЕСЯЦ_1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'НАЗВА_ПОТОЧНОГО_МІСЯЦЯ', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'НАЗВА_ПОТОЧНОГО_МІСЯЦЯ_РОД', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'НАЗВА_ПОТОЧНОГО_МІСЯЦЯ_АБР', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_АБР', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'ПОТОЧНИЙ_ДЕНЬ', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'ПОТОЧНИЙ_ДЕНЬ_2', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'НАЗВА_ПОТОЧНОГО_ДНЯ', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ПОТОЧНИЙ_РІК', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'ПОТОЧНИЙ_ЧАС', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ПОТОЧНА_ГОДИНА', 'ТЕКУЩИЙ_ЧАС', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'ЛОКАЛЬНИЙ_МІСЯЦЬ', 'ЛОКАЛЬНИЙ_МІСЯЦЬ_2', 'МЕСТНЫЙ_МЕСЯЦ', 'МЕСТНЫЙ_МЕСЯЦ_2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'ЛОКАЛЬНИЙ_МІСЯЦЬ_1', 'МЕСТНЫЙ_МЕСЯЦ_1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'НАЗВА_ЛОКАЛЬНОГО_МІСЯЦЯ', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'НАЗВА_ЛОКАЛЬНОГО_МІСЯЦЯ_РОД', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_РОД', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'НАЗВА_ЛОКАЛЬНОГО_МІСЯЦЯ_АБР', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_АБР', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'ЛОКАЛЬНИЙ_ДЕНЬ', 'МЕСТНЫЙ_ДЕНЬ', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'ЛОКАЛЬНИЙ_ДЕНЬ_2', 'МЕСТНЫЙ_ДЕНЬ_2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'НАЗВА_ЛОКАЛЬНОГО_ДНЯ', 'НАЗВАНИЕ_МЕСТНОГО_ДНЯ', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'ЛОКАЛЬНИЙ_РІК', 'МЕСТНЫЙ_ГОД', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'ЛОКАЛЬНИЙ_ЧАС', 'МЕСТНОЕ_ВРЕМЯ', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'ЛОКАЛЬНА_ГОДИНА', 'МЕСТНЫЙ_ЧАС', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'КІЛЬКІСТЬ_СТОРІНОК', 'КОЛИЧЕСТВО_СТРАНИЦ', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'КІЛЬКІСТЬ_СТАТЕЙ', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'КІЛЬКІСТЬ_ФАЙЛІВ', 'КОЛИЧЕСТВО_ФАЙЛОВ', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'КІЛЬКІСТЬ_КОРИСТУВАЧІВ', 'КОЛИЧЕСТВО_УЧАСТНИКОВ', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'КІЛЬКІСТЬ_АКТИВНИХ_КОРИСТУВАЧІВ', 'КОЛИЧЕСТВО_АКТИВНЫХ_УЧАСТНИКОВ', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'КІЛЬКІСТЬ_РЕДАГУВАНЬ', 'КОЛИЧЕСТВО_ПРАВОК', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'КІЛЬКІСТЬ_ПЕРЕГЛЯДІВ', 'КОЛИЧЕСТВО_ПРОСМОТРОВ', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'НАЗВА_СТОРІНКИ', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'НАЗВА_СТОРІНКИ_2', 'НАЗВАНИЕ_СТРАНИЦЫ_2', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ПРОСТІР_НАЗВ', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ПРОСТІР_НАЗВ_2', 'ПРОСТРАНСТВО_ИМЁН_2', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'ПРОСТІР_ОБГОВОРЕННЯ', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'ПРОСТІР_ОБГОВОРЕННЯ_2', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ_2', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ПРОСТІР_СТАТЕЙ', 'ПРОСТРАНСТВО_СТАТЕЙ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ПРОСТІР_СТАТЕЙ_2', 'ПРОСТРАНСТВО_СТАТЕЙ_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'ПОВНА_НАЗВА_СТОРІНКИ', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'ПОВНА_НАЗВА_СТОРІНКИ_2', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ_2', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'НАЗВА_ПІДСТОРІНКИ', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'НАЗВА_ПІДСТОРІНКИ_2', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ_2', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'ОСНОВА_НАЗВИ_ПІДСТОРІНКИ', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'ОСНОВА_НАЗВИ_ПІДСТОРІНКИ_2', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ_2', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'НАЗВА_СТОРІНКИ_ОБГОВОРЕННЯ', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'НАЗВА_СТОРІНКИ_ОБГОВОРЕННЯ_2', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ_2', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'НАЗВА_СТАТТІ', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'НАЗВА_СТАТТІ_2', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'ПОВІД:', 'ПОВІДОМЛЕННЯ:', 'СООБЩЕНИЕ:', 'СООБЩ:', 'MSG:' ),
	'subst'                     => array( '0', 'ПІДСТ:', 'ПІДСТАНОВКА:', 'ПОДСТАНОВКА:', 'ПОДСТ:', 'SUBST:' ),
	'safesubst'                 => array( '0', 'БЕЗПЕЧНА_ПІДСТАНОВКА:', 'ЗАЩПОДСТ:', 'SAFESUBST:' ),
	'msgnw'                     => array( '0', 'ПОВІД_БЕЗ_ВІКІ:', 'СООБЩ_БЕЗ_ВИКИ:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'міні', 'мініатюра', 'мини', 'миниатюра', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'міні=$1', 'мініатюра=$1', 'мини=$1', 'миниатюра=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'праворуч', 'справа', 'right' ),
	'img_left'                  => array( '1', 'ліворуч', 'слева', 'left' ),
	'img_none'                  => array( '1', 'без', 'none' ),
	'img_width'                 => array( '1', '$1пкс', '$1px' ),
	'img_center'                => array( '1', 'центр', 'center', 'centre' ),
	'img_framed'                => array( '1', 'обрамити', 'рамка', 'обрамить', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'безрамки', 'frameless' ),
	'img_page'                  => array( '1', 'сторінка=$1', 'сторінка_$1', 'страница=$1', 'страница_$1', 'страница $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'зверхуправоруч', 'зверхуправоруч=$1', 'зверхуправоруч_$1', 'сверхусправа', 'сверхусправа=$1', 'сверхусправа_$1', 'сверхусправа $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'межа', 'граница', 'border' ),
	'img_baseline'              => array( '1', 'основа', 'основание', 'baseline' ),
	'img_sub'                   => array( '1', 'під', 'под', 'sub' ),
	'img_super'                 => array( '1', 'над', 'super', 'sup' ),
	'img_top'                   => array( '1', 'зверху', 'сверху', 'top' ),
	'img_text_top'              => array( '1', 'текст-зверху', 'текст-сверху', 'text-top' ),
	'img_middle'                => array( '1', 'посередині', 'посередине', 'middle' ),
	'img_bottom'                => array( '1', 'знизу', 'снизу', 'bottom' ),
	'img_text_bottom'           => array( '1', 'текст-знизу', 'текст-снизу', 'text-bottom' ),
	'img_link'                  => array( '1', 'посилання=$1', 'ссылка=$1', 'link=$1' ),
	'img_alt'                   => array( '1', 'альт=$1', 'alt=$1' ),
	'int'                       => array( '0', 'ВНУТР:', 'INT:' ),
	'sitename'                  => array( '1', 'НАЗВА_САЙТУ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ),
	'ns'                        => array( '0', 'ПН:', 'ПИ:', 'NS:' ),
	'nse'                       => array( '0', 'ПН_2:', 'ПИК:', 'NSE:' ),
	'localurl'                  => array( '0', 'ЛОКАЛЬНА_АДРЕСА:', 'ЛОКАЛЬНЫЙ_АДРЕС:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'ЛОКАЛЬНА_АДРЕСА_2:', 'ЛОКАЛЬНЫЙ_АДРЕС_2:', 'LOCALURLE:' ),
	'server'                    => array( '0', 'СЕРВЕР', 'SERVER' ),
	'servername'                => array( '0', 'НАЗВА_СЕРВЕРА', 'НАЗВАНИЕ_СЕРВЕРА', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'ШЛЯХ_ДО_СКРИПТУ', 'ПУТЬ_К_СКРИПТУ', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'ШЛЯХ_ДО_СТИЛЮ', 'ПУТЬ_К_СТИЛЮ', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'ВІДМІНОК:', 'ПАДЕЖ:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'СТАТЬ:', 'ПОЛ:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__БЕЗ_ПЕРЕТВОРЕННЯ_ЗАГОЛОВКУ__', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ЗАГОЛОВКА__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__БЕЗ_ПЕРЕТВОРЕННЯ_ТЕКСТУ__', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ТЕКСТА__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'ПОТОЧНИЙ_ТИЖДЕНЬ', 'ТЕКУЩАЯ_НЕДЕЛЯ', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'ПОТОЧНИЙ_ДЕНЬ_ТИЖНЯ', 'ТЕКУЩИЙ_ДЕНЬ_НЕДЕЛИ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'ЛОКАЛЬНИЙ_ТИЖДЕНЬ', 'МЕСТНАЯ_НЕДЕЛЯ', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'ЛОКАЛЬНИЙ_ДЕНЬ_ТИЖНЯ', 'МЕСТНЫЙ_ДЕНЬ_НЕДЕЛИ', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'ІД_ВЕРСІЇ', 'ИД_ВЕРСИИ', 'REVISIONID' ),
	'revisionday'               => array( '1', 'ДЕНЬ_ВЕРСІЇ', 'ДЕНЬ_ВЕРСИИ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'ДЕНЬ_ВЕРСІЇ_2', 'ДЕНЬ_ВЕРСИИ_2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'МІСЯЦЬ_ВЕРСІЇ', 'МЕСЯЦ_ВЕРСИИ', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'МІСЯЦЬ_ВЕРСІЇ_1', 'МЕСЯЦ_ВЕРСИИ_1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'РІК_ВЕРСІЇ', 'ГОД_ВЕРСИИ', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'МІТКА_ЧАСУ_ВЕРСІЇ', 'ОТМЕТКА_ВРЕМЕНИ_ВЕРСИИ', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'ВЕРСІЯ_КОРИСТУВАЧА', 'ВЕРСИЯ_УЧАСТНИКА', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'МНОЖИНА:', 'МНОЖЕСТВЕННОЕ_ЧИСЛО:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'ПОВНА_АДРЕСА:', 'ПОЛНЫЙ_АДРЕС:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'ПОВНА_АДРЕСА_2:', 'ПОЛНЫЙ_АДРЕС_2:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'НР_ПЕРША:', 'ПЕРША_БУКВА_МАЛА:', 'ПЕРВАЯ_БУКВА_МАЛЕНЬКАЯ:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'ВР_ПЕРША:', 'ПЕРША_БУКВА_ВЕЛИКА:', 'ПЕРВАЯ_БУКВА_БОЛЬШАЯ:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'НР:', 'НИЖНІЙ_РЕГІСТР:', 'МАЛИМИ_БУКВАМИ:', 'МАЛЕНЬКИМИ_БУКВАМИ:', 'LC:' ),
	'uc'                        => array( '0', 'ВР:', 'ВЕРХНІЙ_РЕГІСТР:', 'ВЕЛИКИМИ_БУКВАМИ:', 'БОЛЬШИМИ_БУКВАМИ:', 'UC:' ),
	'raw'                       => array( '0', 'НЕОБРОБ:', 'НЕОБРАБ:', 'RAW:' ),
	'displaytitle'              => array( '1', 'ПОКАЗАТИ_ЗАГОЛОВОК', 'ПОКАЗАТЬ_ЗАГОЛОВОК', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'Н', 'R' ),
	'newsectionlink'            => array( '1', '__ПОСИЛАННЯ_НА_НОВИЙ_РОЗДІЛ__', '__ССЫЛКА_НА_НОВЫЙ_РАЗДЕЛ__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__БЕЗ_ПОСИЛАННЯ_НА_НОВИЙ_РОЗДІЛ__', '__БЕЗ_ССЫЛКИ_НА_НОВЫЙ_РАЗДЕЛ__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'ПОТОЧНА_ВЕРСІЯ', 'ТЕКУЩАЯ_ВЕРСИЯ', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'ЗАКОДОВАНА_АДРЕСА:', 'ЗАКОДИРОВАННЫЙ_АДРЕС:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'КОДУВАТИ_МІТКУ', 'КОДИРОВАТЬ_МЕТКУ', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'МІТКА_ПОТОЧНОГО_ЧАСУ', 'ОТМЕТКА_ТЕКУЩЕГО_ВРЕМЕНИ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'МІТКА_ЛОКАЛЬНОГО_ЧАСУ', 'ОТМЕТКА_МЕСТНОГО_ВРЕМЕНИ', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'НАПРЯМОК_ПИСЬМА', 'НАПРАВЛЕНИЕ_ПИСЬМА', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#МОВА:', '#ЯЗЫК:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'МОВА_ВМІСТУ', 'ЯЗЫК_СОДЕРЖАНИЯ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'СТОРІНОК_У_ПРОСТОРІ_НАЗВ:', 'СТРАНИЦ_В_ПРОСТРАНСТВЕ_ИМЁН:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'КІЛЬКІСТЬ_АДМІНІСТРАТОРІВ', 'КОЛИЧЕСТВО_АДМИНИСТРАТОРОВ', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'ФОРМАТУВАТИ_ЧИСЛО', 'ФОРМАТИРОВАТЬ_ЧИСЛО', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'ЗАПОВНИТИ_ЛІВОРУЧ', 'ЗАПОЛНИТЬ_СЛЕВА', 'PADLEFT' ),
	'padright'                  => array( '0', 'ЗАПОВНИТИ_ПРАВОРУЧ', 'ЗАПОЛНИТЬ_СПРАВА', 'PADRIGHT' ),
	'special'                   => array( '0', 'спеціальна', 'служебная', 'special' ),
	'defaultsort'               => array( '1', 'СТАНДАРТНЕ_СОРТУВАННЯ:', 'СОРТУВАННЯ:', 'СОРТИРОВКА_ПО_УМОЛЧАНИЮ', 'КЛЮЧ_СОРТИРОВКИ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'ШЛЯХ_ДО_ФАЙЛУ:', 'ПУТЬ_К_ФАЙЛУ:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'тег', 'мітка', 'метка', 'тэг', 'tag' ),
	'hiddencat'                 => array( '1', '__ПРИХОВ_КАТ__', '__СКРЫТАЯ_КАТЕГОРИЯ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'СТОР_В_КАТ', 'СТОР_У_КАТ', 'СТРАНИЦ_В_КАТЕГОРИИ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'РОЗМІР', 'РОЗМІР_СТОРІНКИ', 'РАЗМЕР_СТРАНИЦЫ', 'PAGESIZE' ),
	'index'                     => array( '1', '__ІНДЕКС__', '__ИНДЕКС__', '__INDEX__' ),
	'noindex'                   => array( '1', '__БЕЗ_ІНДЕКСУ__', '__БЕЗ_ИНДЕКСА__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'КІЛЬКІСТЬ_У_ГРУПІ', 'ЧИСЛО_В_ГРУППЕ', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__СТАТИЧНЕ_ПЕРЕНАПРАВЛЕННЯ__', '__СТАТИЧЕСКОЕ_ПЕРЕНАПРАВЛЕНИЕ__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'РІВЕНЬ_ЗАХИСТУ', 'УРОВЕНЬ_ЗАЩИТЫ', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'форматдати', 'форматдаты', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'ШЛЯХ', 'ПУТЬ', 'PATH' ),
	'url_wiki'                  => array( '0', 'ВІКІ', 'ВИКИ', 'WIKI' ),
	'url_query'                 => array( '0', 'ЗАПИТ', 'ЗАПРОС', 'QUERY' ),
);

$linkTrail = '/^([a-zабвгґдеєжзиіїйклмнопрстуфхцчшщьєюяёъы“»]+)(.*)$/sDu';
$linkPrefixCharset = '„«';

