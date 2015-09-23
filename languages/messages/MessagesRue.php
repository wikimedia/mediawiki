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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Діскузіа'                => NS_TALK,
	'Діскузіа_з_хоснователём' => NS_USER_TALK,
	'Дізкузія_ку_MediaWiki'   => NS_MEDIAWIKI_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Актівны_хоснователї' ),
	'Allmessages'               => array( 'Сістемовы_повідомлїня' ),
	'AllMyUploads'              => array( 'Вшыткы_мої_файлы' ),
	'Allpages'                  => array( 'Вшыткы_сторінкы' ),
	'Ancientpages'              => array( 'Давны_сторінкы' ),
	'Badtitle'                  => array( 'Планый_тітул' ),
	'Blankpage'                 => array( 'Порожня_сторінка' ),
	'Block'                     => array( 'Заблоковати' ),
	'Booksources'               => array( 'Жрідла_книг' ),
	'BrokenRedirects'           => array( 'Розорваны_напрямлїня' ),
	'Categories'                => array( 'Катеґорії' ),
	'ChangeEmail'               => array( 'Змінити_імейл' ),
	'ChangePassword'            => array( 'Змінити_гесло' ),
	'ComparePages'              => array( 'Порівнаня_сторінок' ),
	'Confirmemail'              => array( 'Потвердити_імейл' ),
	'Contributions'             => array( 'Вклад' ),
	'CreateAccount'             => array( 'Створити_конто' ),
	'Deadendpages'              => array( 'Сторінкы_без_одказів' ),
	'DeletedContributions'      => array( 'Вымазаный_вклад' ),
	'DoubleRedirects'           => array( 'Подвійны_напрямлїня' ),
	'EditWatchlist'             => array( 'Правити_список_мерькованя' ),
	'Emailuser'                 => array( 'Писмо_хоснователёви' ),
	'Export'                    => array( 'Експорт' ),
	'Fewestrevisions'           => array( 'Найменшы_перевіркы' ),
	'FileDuplicateSearch'       => array( 'Гляданя_дуплікатів_файлів' ),
	'Filepath'                  => array( 'Стежка_до_файлу' ),
	'Import'                    => array( 'Імпорт' ),
	'Invalidateemail'           => array( 'Знеплатнити_імейл' ),
	'BlockList'                 => array( 'Список_блоковань' ),
	'LinkSearch'                => array( 'Гляданя_одказів' ),
	'Listadmins'                => array( 'Список_адміністраторів' ),
	'Listbots'                  => array( 'Список_ботів' ),
	'Listfiles'                 => array( 'Список_файлів' ),
	'Listgrouprights'           => array( 'Список_прав_ґруп' ),
	'Listredirects'             => array( 'Список_напрямлїнь' ),
	'Listusers'                 => array( 'Список_хоснователїв' ),
	'Lockdb'                    => array( 'Заблоковати_датабазу' ),
	'Log'                       => array( 'Журналы' ),
	'Lonelypages'               => array( 'Ізолованы_сторінкы' ),
	'Longpages'                 => array( 'Найдовшы_сторінкы' ),
	'MergeHistory'              => array( 'Зєдинїня_історії' ),
	'MIMEsearch'                => array( 'Гляданя_MIME' ),
	'Mostcategories'            => array( 'Найкатеґорізованїшы' ),
	'Mostimages'                => array( 'Найбівше_хоснованы_файлы' ),
	'Mostlinkedcategories'      => array( 'Найвжыванїшы_катеґорії' ),
	'Mostlinkedtemplates'       => array( 'Найвжыванїшы_шаблоны' ),
	'Mostrevisions'             => array( 'Найбівше_ревізій' ),
	'Movepage'                  => array( 'Переменовати' ),
	'Mycontributions'           => array( 'Мій_вклад' ),
	'MyLanguage'                => array( 'Мій_язык' ),
	'Mypage'                    => array( 'Моя_сторінка' ),
	'Mytalk'                    => array( 'Моя_діскузія' ),
	'Myuploads'                 => array( 'Мої_награня_файлів' ),
	'Newimages'                 => array( 'Новы_файлы' ),
	'Newpages'                  => array( 'Новы_сторінкы' ),
	'PasswordReset'             => array( 'Ресет_гесла' ),
	'PermanentLink'             => array( 'Тырвалый_одказ' ),
	'Preferences'               => array( 'Наставлїня' ),
	'Protectedpages'            => array( 'Замкнуты_сторінкы' ),
	'Protectedtitles'           => array( 'Замкнуты_назвы' ),
	'Randompage'                => array( 'Нагодна_статя' ),
	'Randomredirect'            => array( 'Нагодне_напрямлїня' ),
	'Recentchanges'             => array( 'Послїднї_зміны' ),
	'Recentchangeslinked'       => array( 'Повязаны_едітованя' ),
	'Search'                    => array( 'Гляданя' ),
	'Shortpages'                => array( 'Курты_сторінкы' ),
	'Specialpages'              => array( 'Шпеціалны_сторінкы' ),
	'Statistics'                => array( 'Штатістіка' ),
	'Tags'                      => array( 'Позначкы' ),
	'Unblock'                   => array( 'Одблоковати' ),
	'Uncategorizedcategories'   => array( 'Некатеґорізованы_катеґорії' ),
	'Uncategorizedimages'       => array( 'Некатеґорізованы_файлы' ),
	'Uncategorizedpages'        => array( 'Некатеґорізованы_сторінкы' ),
	'Uncategorizedtemplates'    => array( 'Некатеґорізованы_шаблоны' ),
	'Unusedcategories'          => array( 'Нехоснованы_катеґорії' ),
	'Unusedimages'              => array( 'Нехоснованы_файлы' ),
	'Unusedtemplates'           => array( 'Нехоснованы_шаблоны' ),
	'Unwatchedpages'            => array( 'Неслїдованы_сторінкы' ),
	'Wantedcategories'          => array( 'Пожадованы_катеґорії' ),
	'Wantedfiles'               => array( 'Пожадованы_файлы' ),
	'Wantedpages'               => array( 'Пожадованы_сторінкы' ),
	'Wantedtemplates'           => array( 'Пожадованы_шаблоны' ),
	'Whatlinkshere'             => array( 'Одказы_гев' ),
	'Withoutinterwiki'          => array( 'Без_інтервікі' ),
);

