<?php
/** Bashkir (башҡортса)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'ru';

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Махсус',
	NS_TALK             => 'Фекерләшеү',
	NS_USER             => 'Ҡатнашыусы',
	NS_USER_TALK        => 'Ҡатнашыусы_менән_һөйләшеү',
	NS_PROJECT_TALK     => '$1_буйынса_фекерләшеү',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_буйынса_фекерләшеү',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_буйынса_фекерләшеү',
	NS_TEMPLATE         => 'Ҡалып',
	NS_TEMPLATE_TALK    => 'Ҡалып_буйынса_фекерләшеү',
	NS_HELP             => 'Белешмә',
	NS_HELP_TALK        => 'Белешмә_буйынса_фекерләшеү',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_буйынса_фекерләшеү',
];

$namespaceAliases = [
	'Ярҙамсы'                     => NS_SPECIAL,
	'Фекер_алышыу'                => NS_TALK,
	'Ҡатнашыусы_м-н_фекер_алышыу' => NS_USER_TALK,
	'$1_б-са_фекер_алышыу'        => NS_PROJECT_TALK,
	'Рәсем'                       => NS_FILE,
	'Рәсем_буйынса_фекерләшеү'    => NS_FILE_TALK,
	'Рәсем_б-са_фекер_алышыу'     => NS_FILE_TALK,
	'MediaWiki_б-са_фекер_алышыу' => NS_MEDIAWIKI_TALK,
	'Ҡалып_б-са_фекер_алышыу'     => NS_TEMPLATE_TALK,
	'Белешмә_б-са_фекер_алышыу'   => NS_HELP_TALK,
	'Төркөм'                      => NS_CATEGORY,
	'Төркөм_буйынса_фекерләшеү'   => NS_CATEGORY_TALK,
	'Категория_б-са_фекер_алышыу' => NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'ӘүҙемҠатнашыусылар', 'АктивҠатнашыусылар' ],
	'Allmessages'               => [ 'Система_хәбәрҙәре' ],
	'Allpages'                  => [ 'Барлыҡ_битәр' ],
	'Blankpage'                 => [ 'Буш_бит' ],
	'Block'                     => [ 'Блоклау' ],
	'Booksources'               => [ 'Китап_сығанаҡтары' ],
	'BrokenRedirects'           => [ 'Өҙөлгән_йүнәлтеүҙәр' ],
	'Categories'                => [ 'Категориялар' ],
	'ChangeEmail'               => [ 'Email-ды_алыштырыу' ],
	'ChangePassword'            => [ 'Паролде_алыштырыу' ],
	'ComparePages'              => [ 'Биттәрҙе_сағыштырыу' ],
	'Confirmemail'              => [ 'Email-ды_раҫлау' ],
	'Contributions'             => [ 'Өлөштәр' ],
	'CreateAccount'             => [ 'Иҫәп_яҙыуы_яһау' ],
	'Deadendpages'              => [ 'Көрсөк_биттәр' ],
	'DeletedContributions'      => [ 'Юйылған_өлөш' ],
	'DoubleRedirects'           => [ 'Икеле_йүнәлтеүҙәр' ],
	'EditWatchlist'             => [ 'Күҙәтеү_исемлеген_мөхәррирләү' ],
	'Emailuser'                 => [ 'Ҡатнашыусыға_хат' ],
	'Export'                    => [ 'Экспорт' ],
	'FileDuplicateSearch'       => [ 'Файлдың_дубликаттарын_эҙләү' ],
	'Filepath'                  => [ 'Файл_юлы' ],
	'Import'                    => [ 'Импорт' ],
	'BlockList'                 => [ 'Блоклауҙар_исемлеге' ],
	'LinkSearch'                => [ 'Һылтанмалар_эҙләү' ],
	'Listadmins'                => [ 'Хакимдар_исемлеге' ],
	'Listbots'                  => [ 'Боттар_исемлеге' ],
	'Listfiles'                 => [ 'Файлдар_исемлеге' ],
	'Listgrouprights'           => [ 'Ҡатнашыусы_төркөмдәре_хоҡуҡтары' ],
	'Listredirects'             => [ 'Йүнәлтеүҙәр_исемлеге' ],
	'Listusers'                 => [ 'Ҡатнашыусылар_исемлеге' ],
	'Log'                       => [ 'Журналдар' ],
	'Lonelypages'               => [ 'Етем_биттәр' ],
	'Longpages'                 => [ 'Оҙон_биттәр' ],
	'MergeHistory'              => [ 'Тарихтарҙы_берләштереү' ],
	'Mostimages'                => [ 'Йыш_ҡулланылған_файлдар' ],
	'Movepage'                  => [ 'Бит_исемен_үҙгәртеү' ],
	'Mycontributions'           => [ 'Өлөшөм' ],
	'Mypage'                    => [ 'Битем' ],
	'Mytalk'                    => [ 'Әңгәмә_битем' ],
	'Myuploads'                 => [ 'Тейәүҙәрем' ],
	'Newimages'                 => [ 'Яңы_файлдар' ],
	'Newpages'                  => [ 'Яңы_биттәр' ],
	'PasswordReset'             => [ 'Паролде_яңыртыу' ],
	'PermanentLink'             => [ 'Даими_һылтанма' ],
	'Preferences'               => [ 'Көйләүҙәр' ],
	'Protectedpages'            => [ 'Һаҡланған_биттәр' ],
	'Protectedtitles'           => [ 'Һаҡланған_исемдәр' ],
	'Randompage'                => [ 'Осраҡлы_мәҡәлә' ],
	'Recentchanges'             => [ 'Һуңғы_үҙгәртеүҙәр' ],
	'Recentchangeslinked'       => [ 'Бәйле_үҙгәртеүҙәр' ],
	'Revisiondelete'            => [ 'Төҙәтеүҙе_юйыу' ],
	'Search'                    => [ 'Эҙләү' ],
	'Shortpages'                => [ 'Ҡыҫҡа_биттәр' ],
	'Specialpages'              => [ 'Махсус_биттәр' ],
	'Tags'                      => [ 'Билдәләр' ],
	'Unblock'                   => [ 'Блокты_сисеү' ],
	'Uncategorizedcategories'   => [ 'Категорияланмаған_категориялар' ],
	'Uncategorizedimages'       => [ 'Категорияланмаған_файлдар' ],
	'Uncategorizedpages'        => [ 'Категорияланмаған_биттәр' ],
	'Uncategorizedtemplates'    => [ 'Категорияланмаған_ҡалыптар' ],
	'Undelete'                  => [ 'Тергеҙеү' ],
	'Unusedcategories'          => [ 'Ҡулланылмаған_категориялар' ],
	'Unusedimages'              => [ 'Ҡулланылмаған_файлдар' ],
	'Unusedtemplates'           => [ 'Ҡулланылмаған_ҡалыптар' ],
	'Upload'                    => [ 'Тейәү' ],
	'UploadStash'               => [ 'Йәшерен_тейәү' ],
	'Userlogin'                 => [ 'Танылыу' ],
	'Userlogout'                => [ 'Ултырышты_тамамлау' ],
	'Userrights'                => [ 'Хоҡуҡтарҙы_идаралау' ],
	'Wantedcategories'          => [ 'Кәрәкле_категориялар' ],
	'Wantedfiles'               => [ 'Кәрәкле_файлдар' ],
	'Wantedpages'               => [ 'Кәрәкле_биттәр' ],
	'Wantedtemplates'           => [ 'Кәрәкле_ҡалыптар' ],
	'Watchlist'                 => [ 'Күҙәтеү_исемлеге' ],
	'Whatlinkshere'             => [ 'Бында_һылтанмалар' ],
	'Withoutinterwiki'          => [ 'Интервикиһыҙ' ],
];

// Remove Russian aliases
$namespaceGenderAliases = [];

$linkTrail = '/^((?:[a-z]|а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я|ә|ө|ү|ғ|ҡ|ң|ҙ|ҫ|һ|“|»)+)(.*)$/sDu';

