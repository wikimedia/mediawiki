<?php
/** Rusyn (русиньскый)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Engelseziekte
 * @author Gazeb
 * @author Gleb Borisov
 * @author Kaganer
 * @author Nemo bis
 * @author Reedy
 * @author Tkalyn
 */

$fallback = 'uk, ru';

$namespaceNames = [
	NS_MEDIA            => 'Медіа',
	NS_SPECIAL          => 'Шпеціална',
	NS_TALK             => 'Діскузія',
	NS_USER             => 'Хоснователь',
	NS_USER_TALK        => 'Діскузія_з_хоснователём',
	NS_PROJECT_TALK     => 'Діскузія_ку_{{grammar:3sg|$1}}',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Діскузія_ку_файлу',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Діскузія_ку_MediaWiki',
	NS_TEMPLATE         => 'Шаблона',
	NS_TEMPLATE_TALK    => 'Діскузія_ку_шаблонї',
	NS_HELP             => 'Поміч',
	NS_HELP_TALK        => 'Діскузія_ку_помочі',
	NS_CATEGORY         => 'Катеґорія',
	NS_CATEGORY_TALK    => 'Діскузія_ку_катеґорії',
];

$namespaceAliases = [
	'Діскузіа'                => NS_TALK,
	'Діскузіа_з_хоснователём' => NS_USER_TALK,
	'Дізкузія_ку_MediaWiki'   => NS_MEDIAWIKI_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'Актівны_хоснователї' ],
	'Allmessages'               => [ 'Сістемовы_повідомлїня' ],
	'AllMyUploads'              => [ 'Вшыткы_мої_файлы' ],
	'Allpages'                  => [ 'Вшыткы_сторінкы' ],
	'Ancientpages'              => [ 'Давны_сторінкы' ],
	'Badtitle'                  => [ 'Планый_тітул' ],
	'Blankpage'                 => [ 'Порожня_сторінка' ],
	'Block'                     => [ 'Заблоковати' ],
	'Booksources'               => [ 'Жрідла_книг' ],
	'BrokenRedirects'           => [ 'Розорваны_напрямлїня' ],
	'Categories'                => [ 'Катеґорії' ],
	'ChangeEmail'               => [ 'Змінити_імейл' ],
	'ChangePassword'            => [ 'Змінити_гесло' ],
	'ComparePages'              => [ 'Порівнаня_сторінок' ],
	'Confirmemail'              => [ 'Потвердити_імейл' ],
	'Contributions'             => [ 'Вклад' ],
	'CreateAccount'             => [ 'Створити_конто' ],
	'Deadendpages'              => [ 'Сторінкы_без_одказів' ],
	'DeletedContributions'      => [ 'Вымазаный_вклад' ],
	'DoubleRedirects'           => [ 'Подвійны_напрямлїня' ],
	'EditWatchlist'             => [ 'Правити_список_мерькованя' ],
	'Emailuser'                 => [ 'Писмо_хоснователёви' ],
	'Export'                    => [ 'Експорт' ],
	'Fewestrevisions'           => [ 'Найменшы_перевіркы' ],
	'FileDuplicateSearch'       => [ 'Гляданя_дуплікатів_файлів' ],
	'Filepath'                  => [ 'Стежка_до_файлу' ],
	'Import'                    => [ 'Імпорт' ],
	'Invalidateemail'           => [ 'Знеплатнити_імейл' ],
	'BlockList'                 => [ 'Список_блоковань' ],
	'LinkSearch'                => [ 'Гляданя_одказів' ],
	'Listadmins'                => [ 'Список_адміністраторів' ],
	'Listbots'                  => [ 'Список_ботів' ],
	'Listfiles'                 => [ 'Список_файлів' ],
	'Listgrouprights'           => [ 'Список_прав_ґруп' ],
	'Listredirects'             => [ 'Список_напрямлїнь' ],
	'Listusers'                 => [ 'Список_хоснователїв' ],
	'Lockdb'                    => [ 'Заблоковати_датабазу' ],
	'Log'                       => [ 'Журналы' ],
	'Lonelypages'               => [ 'Ізолованы_сторінкы' ],
	'Longpages'                 => [ 'Найдовшы_сторінкы' ],
	'MergeHistory'              => [ 'Зєдинїня_історії' ],
	'MIMEsearch'                => [ 'Гляданя_MIME' ],
	'Mostcategories'            => [ 'Найкатеґорізованїшы' ],
	'Mostimages'                => [ 'Найбівше_хоснованы_файлы' ],
	'Mostlinkedcategories'      => [ 'Найвжыванїшы_катеґорії' ],
	'Mostlinkedtemplates'       => [ 'Найвжыванїшы_шаблоны' ],
	'Mostrevisions'             => [ 'Найбівше_ревізій' ],
	'Movepage'                  => [ 'Переменовати' ],
	'Mycontributions'           => [ 'Мій_вклад' ],
	'MyLanguage'                => [ 'Мій_язык' ],
	'Mypage'                    => [ 'Моя_сторінка' ],
	'Mytalk'                    => [ 'Моя_діскузія' ],
	'Myuploads'                 => [ 'Мої_награня_файлів' ],
	'Newimages'                 => [ 'Новы_файлы' ],
	'Newpages'                  => [ 'Новы_сторінкы' ],
	'PasswordReset'             => [ 'Ресет_гесла' ],
	'PermanentLink'             => [ 'Тырвалый_одказ' ],
	'Preferences'               => [ 'Наставлїня' ],
	'Protectedpages'            => [ 'Замкнуты_сторінкы' ],
	'Protectedtitles'           => [ 'Замкнуты_назвы' ],
	'Randompage'                => [ 'Нагодна_статя' ],
	'Randomredirect'            => [ 'Нагодне_напрямлїня' ],
	'Recentchanges'             => [ 'Послїднї_зміны' ],
	'Recentchangeslinked'       => [ 'Повязаны_едітованя' ],
	'Search'                    => [ 'Гляданя' ],
	'Shortpages'                => [ 'Курты_сторінкы' ],
	'Specialpages'              => [ 'Шпеціалны_сторінкы' ],
	'Statistics'                => [ 'Штатістіка' ],
	'Tags'                      => [ 'Позначкы' ],
	'Unblock'                   => [ 'Одблоковати' ],
	'Uncategorizedcategories'   => [ 'Некатеґорізованы_катеґорії' ],
	'Uncategorizedimages'       => [ 'Некатеґорізованы_файлы' ],
	'Uncategorizedpages'        => [ 'Некатеґорізованы_сторінкы' ],
	'Uncategorizedtemplates'    => [ 'Некатеґорізованы_шаблоны' ],
	'Unusedcategories'          => [ 'Нехоснованы_катеґорії' ],
	'Unusedimages'              => [ 'Нехоснованы_файлы' ],
	'Unusedtemplates'           => [ 'Нехоснованы_шаблоны' ],
	'Unwatchedpages'            => [ 'Неслїдованы_сторінкы' ],
	'Wantedcategories'          => [ 'Пожадованы_катеґорії' ],
	'Wantedfiles'               => [ 'Пожадованы_файлы' ],
	'Wantedpages'               => [ 'Пожадованы_сторінкы' ],
	'Wantedtemplates'           => [ 'Пожадованы_шаблоны' ],
	'Whatlinkshere'             => [ 'Одказы_гев' ],
	'Withoutinterwiki'          => [ 'Без_інтервікі' ],
];
