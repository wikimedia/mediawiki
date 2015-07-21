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

$namespaceNames = array(
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
);

$namespaceAliases = array(
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
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ӘүҙемҠатнашыусылар', 'АктивҠатнашыусылар' ),
	'Allmessages'               => array( 'Система_хәбәрҙәре' ),
	'Allpages'                  => array( 'Барлыҡ_битәр' ),
	'Blankpage'                 => array( 'Буш_бит' ),
	'Block'                     => array( 'Блоклау' ),
	'Booksources'               => array( 'Китап_сығанаҡтары' ),
	'BrokenRedirects'           => array( 'Өҙөлгән_йүнәлтеүҙәр' ),
	'Categories'                => array( 'Категориялар' ),
	'ChangeEmail'               => array( 'Email-ды_алыштырыу' ),
	'ChangePassword'            => array( 'Паролде_алыштырыу' ),
	'ComparePages'              => array( 'Биттәрҙе_сағыштырыу' ),
	'Confirmemail'              => array( 'Email-ды_раҫлау' ),
	'Contributions'             => array( 'Өлөштәр' ),
	'CreateAccount'             => array( 'Иҫәп_яҙыуы_яһау' ),
	'Deadendpages'              => array( 'Көрсөк_биттәр' ),
	'DeletedContributions'      => array( 'Юйылған_өлөш' ),
	'DoubleRedirects'           => array( 'Икеле_йүнәлтеүҙәр' ),
	'EditWatchlist'             => array( 'Күҙәтеү_исемлеген_мөхәррирләү' ),
	'Emailuser'                 => array( 'Ҡатнашыусыға_хат' ),
	'Export'                    => array( 'Экспорт' ),
	'FileDuplicateSearch'       => array( 'Файлдың_дубликаттарын_эҙләү' ),
	'Filepath'                  => array( 'Файл_юлы' ),
	'Import'                    => array( 'Импорт' ),
	'BlockList'                 => array( 'Блоклауҙар_исемлеге' ),
	'LinkSearch'                => array( 'Һылтанмалар_эҙләү' ),
	'Listadmins'                => array( 'Хакимдар_исемлеге' ),
	'Listbots'                  => array( 'Боттар_исемлеге' ),
	'Listfiles'                 => array( 'Файлдар_исемлеге' ),
	'Listgrouprights'           => array( 'Ҡатнашыусы_төркөмдәре_хоҡуҡтары' ),
	'Listredirects'             => array( 'Йүнәлтеүҙәр_исемлеге' ),
	'Listusers'                 => array( 'Ҡатнашыусылар_исемлеге' ),
	'Log'                       => array( 'Журналдар' ),
	'Lonelypages'               => array( 'Етем_биттәр' ),
	'Longpages'                 => array( 'Оҙон_биттәр' ),
	'MergeHistory'              => array( 'Тарихтарҙы_берләштереү' ),
	'Mostimages'                => array( 'Йыш_ҡулланылған_файлдар' ),
	'Movepage'                  => array( 'Бит_исемен_үҙгәртеү' ),
	'Mycontributions'           => array( 'Өлөшөм' ),
	'Mypage'                    => array( 'Битем' ),
	'Mytalk'                    => array( 'Әңгәмә_битем' ),
	'Myuploads'                 => array( 'Тейәүҙәрем' ),
	'Newimages'                 => array( 'Яңы_файлдар' ),
	'Newpages'                  => array( 'Яңы_биттәр' ),
	'PasswordReset'             => array( 'Паролде_яңыртыу' ),
	'PermanentLink'             => array( 'Даими_һылтанма' ),

	'Preferences'               => array( 'Көйләүҙәр' ),
	'Protectedpages'            => array( 'Һаҡланған_биттәр' ),
	'Protectedtitles'           => array( 'Һаҡланған_исемдәр' ),
	'Randompage'                => array( 'Осраҡлы_мәҡәлә' ),
	'Recentchanges'             => array( 'Һуңғы_үҙгәртеүҙәр' ),
	'Recentchangeslinked'       => array( 'Бәйле_үҙгәртеүҙәр' ),
	'Revisiondelete'            => array( 'Төҙәтеүҙе_юйыу' ),
	'Search'                    => array( 'Эҙләү' ),
	'Shortpages'                => array( 'Ҡыҫҡа_биттәр' ),
	'Specialpages'              => array( 'Махсус_биттәр' ),
	'Tags'                      => array( 'Билдәләр' ),
	'Unblock'                   => array( 'Блокты_сисеү' ),
	'Uncategorizedcategories'   => array( 'Категорияланмаған_категориялар' ),
	'Uncategorizedimages'       => array( 'Категорияланмаған_файлдар' ),
	'Uncategorizedpages'        => array( 'Категорияланмаған_биттәр' ),
	'Uncategorizedtemplates'    => array( 'Категорияланмаған_ҡалыптар' ),
	'Undelete'                  => array( 'Тергеҙеү' ),
	'Unusedcategories'          => array( 'Ҡулланылмаған_категориялар' ),
	'Unusedimages'              => array( 'Ҡулланылмаған_файлдар' ),
	'Unusedtemplates'           => array( 'Ҡулланылмаған_ҡалыптар' ),
	'Upload'                    => array( 'Тейәү' ),
	'UploadStash'               => array( 'Йәшерен_тейәү' ),
	'Userlogin'                 => array( 'Танылыу' ),
	'Userlogout'                => array( 'Ултырышты_тамамлау' ),
	'Userrights'                => array( 'Хоҡуҡтарҙы_идаралау' ),
	'Wantedcategories'          => array( 'Кәрәкле_категориялар' ),
	'Wantedfiles'               => array( 'Кәрәкле_файлдар' ),
	'Wantedpages'               => array( 'Кәрәкле_биттәр' ),
	'Wantedtemplates'           => array( 'Кәрәкле_ҡалыптар' ),
	'Watchlist'                 => array( 'Күҙәтеү_исемлеге' ),
	'Whatlinkshere'             => array( 'Бында_һылтанмалар' ),
	'Withoutinterwiki'          => array( 'Интервикиһыҙ' ),
);

// Remove Russian aliases
$namespaceGenderAliases = array();

$linkTrail = '/^((?:[a-z]|а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я|ә|ө|ү|ғ|ҡ|ң|ҙ|ҫ|һ|“|»)+)(.*)$/sDu';

