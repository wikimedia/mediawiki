<?php
/** Serbian (Cyrillic script) (српски (ћирилица)‎)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bjankuloski06
 * @author CERminator
 * @author Charmed94
 * @author FriedrickMILBarbarossa
 * @author Geitost
 * @author Helios13
 * @author Kaganer
 * @author Kale
 * @author Meno25
 * @author Milicevic01
 * @author Millosh
 * @author Nikola Smolenski
 * @author Rancher
 * @author Red Baron
 * @author Reedy
 * @author Sasa Stefanovic
 * @author Slaven Kosanovic
 * @author TheStefan12345
 * @author Јованвб
 * @author Жељко Тодоровић
 * @author Милан Јелисавчић
 * @author Михајло Анђелковић
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Медиј',
	NS_SPECIAL          => 'Посебно',
	NS_TALK             => 'Разговор',
	NS_USER             => 'Корисник',
	NS_USER_TALK        => 'Разговор_са_корисником',
	NS_PROJECT_TALK     => 'Разговор_о_$1',
	NS_FILE             => 'Датотека',
	NS_FILE_TALK        => 'Разговор_о_датотеци',
	NS_MEDIAWIKI        => 'Медијавики',
	NS_MEDIAWIKI_TALK   => 'Разговор_о_Медијавикију',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Разговор_о_шаблону',
	NS_HELP             => 'Помоћ',
	NS_HELP_TALK        => 'Разговор_о_помоћи',
	NS_CATEGORY         => 'Категорија',
	NS_CATEGORY_TALK    => 'Разговор_о_категорији',
);

$namespaceAliases = array(
        # Aliases for Latin script namespaces
	"Medija"                  => NS_MEDIA,
	"Posebno"                 => NS_SPECIAL,
	"Razgovor"                => NS_TALK,
	"Korisnik"                => NS_USER,
	"Razgovor_sa_korisnikom"  => NS_USER_TALK,
	"Razgovor_o_$1"           => NS_PROJECT_TALK,
	"Slika"                   => NS_FILE,
	"Razgovor_o_slici"        => NS_FILE_TALK,
	"MedijaViki"              => NS_MEDIAWIKI,
	"Razgovor_o_MedijaVikiju" => NS_MEDIAWIKI_TALK,
	'Šablon'                  => NS_TEMPLATE,
	'Razgovor_o_šablonu'      => NS_TEMPLATE_TALK,
	'Pomoć'                   => NS_HELP,
	'Razgovor_o_pomoći'       => NS_HELP_TALK,
	'Kategorija'              => NS_CATEGORY,
	'Razgovor_o_kategoriji'   => NS_CATEGORY_TALK,

	'Медија'                  => NS_MEDIA,
	'Слика'                   => NS_FILE,
	'Разговор_о_слици'        => NS_FILE_TALK,
	'МедијаВики'              => NS_MEDIAWIKI,
	'Разговор_о_МедијаВикију' => NS_MEDIAWIKI_TALK,
);

$datePreferenceMigrationMap = array(
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
);

$specialPageAliases = array(
	'Activeusers'               => array( 'АктивниКорисници', 'Активни_корисници' ),
	'Allmessages'               => array( 'СвеПоруке', 'Све_поруке' ),
	'Allpages'                  => array( 'Све_странице' ),
	'Ancientpages'              => array( 'НајстаријиЧланци' ),
	'Badtitle'                  => array( 'Лош_наслов' ),
	'Block'                     => array( 'Блокирај', 'БлокирајИП', 'БлокирајКорисника' ),
	'Blockme'                   => array( 'БлокирајМе', 'Блокирај_ме' ),
	'BrokenRedirects'           => array( 'Покварена_преусмерења', 'Неисправна_преусмерења' ),
	'Categories'                => array( 'Категорије' ),
	'ComparePages'              => array( 'Упореди_странице' ),
	'Confirmemail'              => array( 'ПотврдиЕ-пошту', 'Потврда_е-поште' ),
	'Contributions'             => array( 'Доприноси', 'Прилози' ),
	'CreateAccount'             => array( 'ОтвориНалог', 'Отвори_налог' ),
	'Disambiguations'           => array( 'Вишезначне_одреднице' ),
	'DoubleRedirects'           => array( 'Двострука_преусмерења' ),
	'Export'                    => array( 'Извези' ),
	'Fewestrevisions'           => array( 'ЧланциСаНајмањеРевизија' ),
	'Filepath'                  => array( 'Путања_датотеке' ),
	'Import'                    => array( 'Увези' ),
	'Listadmins'                => array( 'ПописАдминистратора', 'Списак_администратора' ),
	'Listbots'                  => array( 'ПописБотова', 'Списак_ботова' ),
	'Listfiles'                 => array( 'СписакСлика', 'Списак_датотека' ),
	'Listgrouprights'           => array( 'СписакКорисничкихПрава', 'Списак_корисничких_права' ),
	'Listredirects'             => array( 'СписакПреусмерења', 'Списак_преусмерења' ),
	'Listusers'                 => array( 'СписакКорисника', 'КорисничкиСписак', 'Списак_корисника', 'Кориснички_списак' ),
	'Lockdb'                    => array( 'ЗакључајБазу', 'Закључај_базу' ),
	'Log'                       => array( 'Извештај', 'Извештаји' ),
	'Lonelypages'               => array( 'Сирочићи' ),
	'Longpages'                 => array( 'ДугачкеСтране' ),
	'MergeHistory'              => array( 'Споји_историју' ),
	'MIMEsearch'                => array( 'MIME_претрага' ),
	'Mostcategories'            => array( 'ЧланциСаНајвишеКатегорија' ),
	'Mostimages'                => array( 'НајповезанијеСлике' ),
	'Mostlinked'                => array( 'НајповезанијеСтране' ),
	'Mostlinkedcategories'      => array( 'НајповезанијеКатегорије' ),
	'Mostlinkedtemplates'       => array( 'НајповезанијиШаблони' ),
	'Mostrevisions'             => array( 'ЧланциСаНајвишеРевизија' ),
	'Movepage'                  => array( 'Преусмери', 'Премести_страницу' ),
	'Mycontributions'           => array( 'МојиДоприноси', 'Моји_доприноси', 'Моји_прилози' ),
	'Mypage'                    => array( 'МојаСтраница', 'Моја_страница' ),
	'Mytalk'                    => array( 'МојРазговор', 'Мој_разговор' ),
	'Myuploads'                 => array( 'Моја_слања' ),
	'Newimages'                 => array( 'НовиФајлови', 'Нове_датотеке', 'НовеСлике' ),
	'Newpages'                  => array( 'НовеСтране' ),
	'PermanentLink'             => array( 'Привремена_веза' ),
	'Popularpages'              => array( 'Популарне_странице' ),
	'Preferences'               => array( 'Подешавања', 'Поставке' ),
	'Protectedpages'            => array( 'ЗаштићенеСтранице', 'Заштићене_странице' ),
	'Protectedtitles'           => array( 'Заштићени_наслови' ),
	'Randompage'                => array( 'СлучајнаСтрана', 'Насумична_страница' ),
	'Recentchanges'             => array( 'СкорашњеИзмене', 'Скорашње_измене' ),
	'Search'                    => array( 'Претражи' ),
	'Shortpages'                => array( 'КраткиЧланци' ),
	'Specialpages'              => array( 'СпецијалнеСтране', 'Посебне_странице' ),
	'Statistics'                => array( 'Статистике' ),
	'Tags'                      => array( 'Ознаке' ),
	'Uncategorizedcategories'   => array( 'КатегоријеБезКатегорија', 'Несврстане_категорије' ),
	'Uncategorizedimages'       => array( 'СликеБезКатегорија', 'ФајловиБезКатегорија' ),
	'Uncategorizedpages'        => array( 'ЧланциБезКатегорија', 'Чланци_без_категорија' ),
	'Uncategorizedtemplates'    => array( 'ШаблониБезКатегорија' ),
	'Undelete'                  => array( 'Врати' ),
	'Unlockdb'                  => array( 'ОткључајБазу', 'Откључај_базу' ),
	'Unusedcategories'          => array( 'НеискоришћенеКатегорије' ),
	'Unusedimages'              => array( 'НеискоришћенеСлике', 'НеискоришћениФајлови' ),
	'Upload'                    => array( 'Пошаљи' ),
	'UploadStash'               => array( 'Складиште' ),
	'Userlogin'                 => array( 'Корисничка_пријава' ),
	'Userlogout'                => array( 'Корисничка_одјава' ),
	'Version'                   => array( 'Верзија', 'Издање' ),
	'Wantedcategories'          => array( 'ТраженеКатегорије' ),
	'Wantedfiles'               => array( 'ТраженеСлике' ),
	'Wantedpages'               => array( 'ТраженеСтране' ),
	'Wantedtemplates'           => array( 'ТражениШаблони' ),
	'Watchlist'                 => array( 'СписакНадгледања', 'Списак_надгледања' ),
	'Whatlinkshere'             => array( 'Шта_је_повезано_овде' ),
	'Withoutinterwiki'          => array( 'Без_међувикије' ),
);

$datePreferences = array(
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
);

$defaultDateFormat = 'hh:mm d. month y.';

$dateFormats = array(
	/*
	'Није битно',
	'06:12, 5. јануар 2001.',
	'06:12, 5 јануар 2001',
	'06:12, 05.01.2001.',
	'06:12, 5.1.2001.',
	'06:12, 5. јан 2001.',
	'06:12, 5 јан 2001',
	'6:12, 5. јануар 2001.',
	'6:12, 5 јануар 2001',
	'6:12, 05.01.2001.',
	'6:12, 5.1.2001.',
	'6:12, 5. јан 2001.',
	'6:12, 5 јан 2001',
	 */

	'hh:mm d. month y. time'    => 'H:i',
	'hh:mm d month y time'      => 'H:i',
	'hh:mm dd.mm.yyyy time'     => 'H:i',
	'hh:mm d.m.yyyy time'       => 'H:i',
	'hh:mm d. mon y. time'      => 'H:i',
	'hh:mm d mon y time'        => 'H:i',
	'h:mm d. month y. time'     => 'G:i',
	'h:mm d month y time'       => 'G:i',
	'h:mm dd.mm.yyyy time'      => 'G:i',
	'h:mm d.m.yyyy time'        => 'G:i',
	'h:mm d. mon y. time'       => 'G:i',
	'h:mm d mon y time'         => 'G:i',

	'hh:mm d. month y. date'    => 'j. F Y.',
	'hh:mm d month y date'      => 'j F Y',
	'hh:mm dd.mm.yyyy date'     => 'd.m.Y',
	'hh:mm d.m.yyyy date'       => 'j.n.Y',
	'hh:mm d. mon y. date'      => 'j. M Y.',
	'hh:mm d mon y date'        => 'j M Y',
	'h:mm d. month y. date'     => 'j. F Y.',
	'h:mm d month y date'       => 'j F Y',
	'h:mm dd.mm.yyyy date'      => 'd.m.Y',
	'h:mm d.m.yyyy date'        => 'j.n.Y',
	'h:mm d. mon y. date'       => 'j. M Y.',
	'h:mm d mon y date'         => 'j M Y',

	'hh:mm d. month y. both'    => 'H:i, j. F Y.',
	'hh:mm d month y both'      => 'H:i, j F Y',
	'hh:mm dd.mm.yyyy both'     => 'H:i, d.m.Y',
	'hh:mm d.m.yyyy both'       => 'H:i, j.n.Y',
	'hh:mm d. mon y. both'      => 'H:i, j. M Y.',
	'hh:mm d mon y both'        => 'H:i, j M Y',
	'h:mm d. month y. both'     => 'G:i, j. F Y.',
	'h:mm d month y both'       => 'G:i, j F Y',
	'h:mm dd.mm.yyyy both'      => 'G:i, d.m.Y',
	'h:mm d.m.yyyy both'        => 'G:i, j.n.Y',
	'h:mm d. mon y. both'       => 'G:i, j. M Y.',
	'h:mm d mon y both'         => 'G:i, j M Y',
);

/* NOT USED IN STABLE VERSION */
$magicWords = array(
	'redirect'                  => array( '0', '#Преусмери', '#преусмери', '#ПРЕУСМЕРИ', '#Преусмјери', '#преусмјери', '#ПРЕУСМЈЕРИ', '#redirect', '#REDIRECT' ),
	'notoc'                     => array( '0', '__БЕЗСАДРЖАЈА__', '__БЕЗ_САДРЖАЈА__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__БЕЗГАЛЕРИЈЕ__', '__БЕЗ_ГАЛЕРИЈЕ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ФОРСИРАНИСАДРЖАЈ__', '__ФОРСИРАНИ_САДРЖАЈ__', '__ПРИМОРАНИСАДРЖАЈ__', '__ПРИМОРАНИ_САДРЖАЈ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__САДРЖАЈ__', '__TOC__' ),
	'noeditsection'             => array( '0', '__БЕЗИЗМЕНА__', '__БЕЗ_ИЗМЕНА__', '__БЕЗИЗМЈЕНА__', '__БЕЗ_ИЗМЈЕНА__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'ТРЕНУТНИМЕСЕЦ', 'ТРЕНУТНИ_МЕСЕЦ', 'ТЕКУЋИМЕСЕЦ', 'ТЕКУЋИ_МЕСЕЦ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'ТРЕНУТНИМЕСЕЦ1', 'ТРЕНУТНИ_МЕСЕЦ1', 'ТЕКУЋИМЕСЕЦ1', 'ТЕКУЋИ_МЕСЕЦ1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'ТРЕНУТНИМЕСЕЦИМЕ', 'ИМЕТЕКУЋЕГМЕСЕЦА', 'ИМЕ_ТЕКУЋЕГ_МЕСЕЦА', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'ТРЕНУТНИМЕСЕЦГЕН', 'ТЕКУЋИМЕСЕЦГЕН', 'ТЕКУЋИ_МЕСЕЦ_ГЕН', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'ТРЕНУТНИМЕСЕЦСКР', 'ТЕКУЋИМЕСЕЦСКР', 'ТЕКУЋИ_МЕСЕЦ_СКР', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'ТРЕНУТНИДАН', 'ТЕКУЋИДАН', 'ТЕКУЋИ_ДАН', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'ТЕКУЋИДАН2', 'ТЕКУЋИ_ДАН_2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'ТРЕНУТНИДАНИМЕ', 'ИМЕТЕКУЋЕГДАНА', 'ИМЕ_ТЕКУЋЕГ_ДАНА', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ТРЕНУТНАГОДИНА', 'ТЕКУЋАГОДИНА', 'ТЕКУЋА_ГОДИНА', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'ТРЕНУТНОВРЕМЕ', 'ТЕКУЋЕВРЕМЕ', 'ТЕКУЋЕ_ВРЕМЕ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ТЕКУЋИСАТ', 'ТЕКУЋИ_САТ', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'ЛОКАЛНИМЕСЕЦ', 'ЛОКАЛНИ_МЕСЕЦ', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'ЛОКАЛНИМЕСЕЦ2', 'ЛОКАЛНИ_МЕСЕЦ2', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'ИМЕЛОКАЛНОГМЕСЕЦА', 'ИМЕ_ЛОКАЛНОГ_МЕСЕЦА', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'ЛОКАЛНИМЕСЕЦГЕН', 'ЛОКАЛНИ_МЕСЕЦ_ГЕН', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'ЛОКАЛНИМЕСЕЦСКР', 'ЛОКАЛНИ_МЕСЕЦ_СКР', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'ЛОКАЛНИДАН', 'ЛОКАЛНИ_ДАН', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'ЛОКАЛНИДАН2', 'ЛОКАЛНИ_ДАН2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'ИМЕЛОКАЛНОГДАНА', 'ИМЕ_ЛОКАЛНОГ_ДАНА', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'ЛОКАЛНАГОДИНА', 'ЛОКАЛНА_ГОДИНА', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'ЛОКАЛНОВРЕМЕ', 'ЛОКАЛНО_ВРЕМЕ', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'ЛОКАЛНИСАТ', 'ЛОКАЛНИ_САТ', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'БРОЈСТРАНИЦА', 'БРОЈ_СТРАНИЦА', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'БРОЈЧЛАНАКА', 'БРОЈ_ЧЛАНАКА', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'БРОЈДАТОТЕКА', 'БРОЈ_ДАТОТЕКА', 'БРОЈФАЈЛОВА', 'БРОЈ_ФАЈЛОВА', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'БРОЈКОРИСНИКА', 'БРОЈ_КОРИСНИКА', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'БРОЈАКТИВНИХКОРИСНИКА', 'БРОЈ_АКТИВНИХ_КОРИСНИКА', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'БРОЈИЗМЕНА', 'БРОЈ_ИЗМЕНА', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'БРОЈПРЕГЛЕДА', 'БРОЈ_ПРЕГЛЕДА', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'ИМЕСТРАНИЦЕ', 'ИМЕ_СТРАНИЦЕ', 'СТРАНИЦА', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'ИМЕНАСТРАНИЦА', 'ИМЕНА_СТРАНИЦА', 'СТРАНИЦЕ', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ИМЕНСКИПРОСТОР', 'ИМЕНСКИ_ПРОСТОР', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ИМЕНСКИПРОСТОРИ', 'ИМЕНСКИ_ПРОСТОРИ', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'РАЗГОВОР', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'РАЗГОВОРИ', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ИМЕНСКИПРОСТОРЧЛАНКА', 'ИМЕНСКИ_ПРОСТОР_ЧЛАНКА', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ИМЕНСКИПРОСТОРЧЛАНАКА', 'ИМЕНСКИ_ПРОСТОР_ЧЛАНАКА', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'ПУНОИМЕСТРАНИЦЕ', 'ПУНОИМЕСТРАНЕ', 'ПУНО_ИМЕ_СТРАНИЦЕ', 'ПУНО_ИМЕ_СТРАНЕ', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'ПУНАИМЕНАСТРАНИЦА', 'ПУНАИМЕНАСТРАНА', 'ПУНА_ИМЕНА_СТРАНИЦА', 'ПУНА_ИМЕНА_СТРАНА', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'ИМЕПОДСТРАНИЦЕ', 'ИМЕПОДСТРАНЕ', 'ИМЕ_ПОДСТРАНИЦЕ', 'ИМЕ_ПОДСТРАНЕ', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'ИМЕНАПОДСТРАНИЦА', 'ИМЕНАПОДСТРАНА', 'ИМЕНА_ПОДСТРАНИЦА', 'ИМЕНА_ПОДСТРАНА', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'ИМЕОСНОВЕ', 'ИМЕ_ОСНОВЕ', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'ИМЕНАОСНОВА', 'ИМЕНА_ОСНОВА', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'ИМЕРАЗГОВОРА', 'ИМЕ_РАЗГОВОРА', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'ИМЕНАРАЗГОВОРА', 'ИМЕНА_РАЗГОВОРА', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'ИМЕЧЛАНКА', 'ИМЕ_ЧЛАНКА', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'ИМЕНАЧЛАНАКА', 'ИМЕНА_ЧЛАНАКА', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'ПОР:', 'MSG:' ),
	'subst'                     => array( '0', 'ЗАМЕНИ:', 'ЗАМЕНА:', 'SUBST:' ),
	'safesubst'                 => array( '0', 'БЕЗБЕДНАЗАМЕНА', 'БЕЗБЕДНА_ЗАМЕНА', 'SAFESUBST:' ),
	'msgnw'                     => array( '0', 'НВПОР:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'мини', 'умањено', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'мини=$1', 'умањено=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'десно', 'д', 'right' ),
	'img_left'                  => array( '1', 'лево', 'л', 'left' ),
	'img_none'                  => array( '1', 'без', 'н', 'none' ),
	'img_width'                 => array( '1', '$1пискел', '$1п', '$1px' ),
	'img_center'                => array( '1', 'центар', 'ц', 'center', 'centre' ),
	'img_framed'                => array( '1', 'оквир', 'рам', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'безоквира', 'без_оквира', 'безрама', 'без_рама', 'frameless' ),
	'img_page'                  => array( '1', 'страница=$1', 'страна=$1', 'страница_$1', 'страна_$1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'усправно', 'усправно=$1', 'усправно_$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'ивица', 'border' ),
	'img_baseline'              => array( '1', 'основа', 'baseline' ),
	'img_sub'                   => array( '1', 'под', 'sub' ),
	'img_super'                 => array( '1', 'супер', 'super', 'sup' ),
	'img_top'                   => array( '1', 'врх', 'top' ),
	'img_text_top'              => array( '1', 'врхтекста', 'врх_текста', 'text-top' ),
	'img_middle'                => array( '1', 'средина', 'middle' ),
	'img_bottom'                => array( '1', 'дно', 'bottom' ),
	'img_text_bottom'           => array( '1', 'срединатекста', 'средина_текста', 'text-bottom' ),
	'img_link'                  => array( '1', 'веза=$1', 'link=$1' ),
	'img_alt'                   => array( '1', 'алт=$1', 'alt=$1' ),
	'int'                       => array( '0', 'ИНТ:', 'INT:' ),
	'sitename'                  => array( '1', 'ИМЕСАЈТА', 'SITENAME' ),
	'ns'                        => array( '0', 'ИП:', 'NS:' ),
	'localurl'                  => array( '0', 'ЛОКАЛНААДРЕСА:', 'ЛОКАЛНА_АДРЕСА:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'ЛОКАЛНЕАДРЕСЕ:', 'ЛОКАЛНЕ_АДРЕСЕ:', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'ПУТАЊАЧЛАНКА', 'ПУТАЊА_ЧЛАНКА', 'ARTICLEPATH' ),
	'server'                    => array( '0', 'СЕРВЕР', 'SERVER' ),
	'servername'                => array( '0', 'ИМЕСЕРВЕРА', 'ИМЕ_СЕРВЕРА', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'СКРИПТА', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'ПУТАЊАСТИЛА', 'ПУТАЊА_СТИЛА', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'ГРАМАТИКА:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'РОД:', 'ЛИЦЕ:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__БЕЗКН__', '__BEZKN__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__БЕЗЦЦ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'ТРЕНУТНАНЕДЕЉА', 'ТРЕНУТНА_НЕДЕЉА', 'ТЕКУЋАНЕДЕЉА', 'ТЕКУЋА_НЕДЕЉА', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'ТРЕНУТНИДОВ', 'ТЕКУЋИДУН', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'ЛОКАЛНАНЕДЕЉА', 'ЛОКАЛНА_НЕДЕЉА', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'ЛОКАЛНИДУН', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'ИДРЕВИЗИЈЕ', 'ИД_РЕВИЗИЈЕ', 'REVISIONID' ),
	'revisionday'               => array( '1', 'ДАНИЗМЕНЕ', 'ДАН_ИЗМЕНЕ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'ДАНИЗМЕНЕ2', 'ДАН_ИЗМЕНЕ2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'МЕСЕЦИЗМЕНЕ', 'МЕСЕЦ_ИЗМЕНЕ', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'МЕСЕЦИЗМЕНЕ1', 'МЕСЕЦ_ИЗМЕНЕ1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'ГОДИНАИЗМЕНЕ', 'ГОДИНА_ИЗМЕНЕ', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'ВРЕМЕИЗМЕНЕ', 'ВРЕМЕ_ИЗМЕНЕ', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'КОРИСНИКИЗМЕНЕ', 'КОРИСНИК_ИЗМЕНЕ', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'МНОЖИНА:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'ПУНУРЛ:', 'ЦЕЛААДРЕСА', 'ЦЕЛА_АДРЕСА', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'ПУНУРЛЕ:', 'ЦЕЛЕАДРЕСЕ', 'ЦЕЛЕ_АДРЕСЕ', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'ЛЦПРВИ:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'УЦПРВИ:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'ЛЦ:', 'LC:' ),
	'uc'                        => array( '0', 'УЦ:', 'UC:' ),
	'raw'                       => array( '0', 'ЧИСТ:', 'RAW:' ),
	'displaytitle'              => array( '1', 'НАЗИВПРИКАЗА', 'НАЗИВ_ПРИКАЗА', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'Р', 'R' ),
	'newsectionlink'            => array( '1', '__НОВАВЕЗАОДЕЉКА__', '__НОВА_ВЕЗА_ОДЕЉКА__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__БЕЗНОВЕВЕЗЕОДЕЉКА__', '__БЕЗ_НОВЕ_ВЕЗЕ_ОДЕЉКА__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'ТЕКУЋЕИЗДАЊЕ', 'ТЕКУЋЕ_ИЗДАЊЕ', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'КОДИРАЊЕАДРЕСЕ', 'КОДИРАЊЕ_АДРЕСЕ', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'КОДИРАЊЕВЕЗЕ', 'КОДИРАЊЕ_ВЕЗЕ', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'ТЕКУЋИОТИСАКВРЕМЕНА', 'ТЕКУЋИ_ОТИСАК_ВРЕМЕНА', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'ОТИСАКВРЕМЕНА', 'ОТИСАК_ВРЕМЕНА', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'СМЕРОЗНАКЕ', 'СМЕР	_ОЗНАКЕ', 'DIRECTIONMARK', 'DIRMARK' ),
	'contentlanguage'           => array( '1', 'ЈЕЗИКСАДРЖАЈА', 'ЈЕЗИК_САДРЖАЈА', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'СТРАНИЦАУИМЕНСКОМПРОСТОРУ', 'СТРАНАУИМЕНСКОМПРОСТОРУ', 'СТРАНИЦА_У_ИМЕНСКОМ_ПРОСТОРУ', 'СТРАНА_У_ИМЕНСКОМ_ПРОСТОРУ', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'БРОЈАДМИНА', 'БРОЈ_АДМИНА', 'NUMBEROFADMINS' ),
	'padleft'                   => array( '0', 'ПОРАВНАЈЛЕВО', 'ПОРАВНАЈ_ЛЕВО', 'PADLEFT' ),
	'padright'                  => array( '0', 'ПОРАВНАЈДЕСНО', 'ПОРАВНАЈ_ДЕСНО', 'PADRIGHT' ),
	'special'                   => array( '0', 'посебно', 'special' ),
	'filepath'                  => array( '0', 'ПУТАЊАДАТОТЕКЕ', 'ПУТАЊА_ДАТОТЕКЕ', 'FILEPATH:' ),
	'tag'                       => array( '0', 'ознака', 'tag' ),
	'hiddencat'                 => array( '1', '__САКРИВЕНАКАТ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'СТРАНИЦАУКАТЕГОРИЈИ', 'СТРАНАУКАТЕГОРИЈИ', 'СТРАНИЦА_У_КАТЕГОРИЈИ', 'СТРАНА_У_КАТЕГОРИЈИ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'ВЕЛИЧИНАСТРАНИЦЕ', 'ВЕЛИЧИНАСТРАНЕ', 'ВЕЛИЧИНА_СТРАНИЦЕ', 'ВЕЛИЧИНА_СТРАНЕ', 'PAGESIZE' ),
	'index'                     => array( '1', '__ИНДЕКС__', '__INDEX__' ),
	'noindex'                   => array( '1', '__БЕЗИНДЕКСА__', '__БЕЗ_ИНДЕКСА__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'БРОЈУГРУПИ', 'БРОЈ_У_ГРУПИ', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__СТАТИЧКОПРЕУСМЕРЕЊЕ__', 'СТАТИЧКО_ПРЕУСМЕРЕЊЕ', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'НИВОЗАШТИТЕ', 'НИВО_ЗАШТИТЕ', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'форматдатума', 'формат_датума', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'ПУТАЊА', 'PATH' ),
	'url_wiki'                  => array( '0', 'ВИКИ', 'WIKI' ),
	'url_query'                 => array( '0', 'РЕДОСЛЕД', 'QUERY' ),
);
$separatorTransformTable = array( ',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline' => 'Подвлачење веза:',
'tog-justify' => 'Поравнај пасусе',
'tog-hideminor' => 'Сакриј мање измене у списку скорашњих измена',
'tog-hidepatrolled' => 'Сакриј прегледане измене у списку скорашњих измена',
'tog-newpageshidepatrolled' => 'Сакриј прегледане странице са списка нових страница',
'tog-extendwatchlist' => 'Прошири списак надгледања за приказ свих измена, не само скорашњих',
'tog-usenewrc' => 'Промене у групи по страници у списку скорашњих измена и надгледаних страница',
'tog-numberheadings' => 'Самостално нумериши поднаслове',
'tog-showtoolbar' => 'Прикажи траку с алаткама за уређивање',
'tog-editondblclick' => 'Уређивање страница двоструким кликом',
'tog-editsection' => 'Везе за уређивање појединачних одељака',
'tog-editsectiononrightclick' => 'Уређивање одељака десним кликом на њихове наслове',
'tog-showtoc' => 'Прикажи садржај страница које имају више од три поднаслова',
'tog-rememberpassword' => 'Запамти ме на овом прегледачу (најдуже $1 {{PLURAL:$1|дан|дана|дана}})',
'tog-watchcreations' => 'Додај странице које направим и датотеке које пошаљем у списак надгледања',
'tog-watchdefault' => 'Додај странице и датотеке које изменим у списак надгледања',
'tog-watchmoves' => 'Додај странице и датотеке које преместим у списак надгледања',
'tog-watchdeletion' => 'Додај странице и датотеке које обришем у списак надгледања',
'tog-minordefault' => 'Означавај све измене као мање',
'tog-previewontop' => 'Прикажи преглед пре оквира за уређивање',
'tog-previewonfirst' => 'Прикажи преглед на првој измени',
'tog-nocache' => 'Онемогући привремено меморисање страница',
'tog-enotifwatchlistpages' => 'Пошаљи ми е-поруку када се промени страница или датотека коју надгледам',
'tog-enotifusertalkpages' => 'Пошаљи ми е-поруку када се промени моја страница за разговор',
'tog-enotifminoredits' => 'Пошаљи ми е-поруку и за мање измене у страницама и датотекама',
'tog-enotifrevealaddr' => 'Прикажи моју е-адресу у порукама обавештења',
'tog-shownumberswatching' => 'Прикажи број корисника који надгледају',
'tog-oldsig' => 'Текући потпис:',
'tog-fancysig' => 'Сматрај потпис као викитекст (без самоповезивања)',
'tog-uselivepreview' => 'Користи тренутан преглед (експериментално)',
'tog-forceeditsummary' => 'Упозори ме када не унесем опис измене',
'tog-watchlisthideown' => 'Сакриј моје измене са списка надгледања',
'tog-watchlisthidebots' => 'Сакриј измене ботова са списка надгледања',
'tog-watchlisthideminor' => 'Сакриј мање измене са списка надгледања',
'tog-watchlisthideliu' => 'Сакриј измене пријављених корисника са списка надгледања',
'tog-watchlisthideanons' => 'Сакриј измене анонимних корисника са списка надгледања',
'tog-watchlisthidepatrolled' => 'Сакриј прегледане измене са списка надгледања',
'tog-ccmeonemails' => 'Пошаљи ми копије е-порука које пошаљем другим корисницима',
'tog-diffonly' => 'Не приказуј садржај странице испод разлика',
'tog-showhiddencats' => 'Прикажи скривене категорије',
'tog-noconvertlink' => 'Онемогући претварање наслова веза',
'tog-norollbackdiff' => 'Изостави разлику након извршеног враћања',
'tog-useeditwarning' => 'Упозори ме када напуштам страницу са несачуваним променама',
'tog-prefershttps' => 'Увек користи сигурну конекцију када сам пријављен.',

'underline-always' => 'увек подвлачи',
'underline-never' => 'никад не подвлачи',
'underline-default' => 'према теми или прегледачу',

# Font style option in Special:Preferences
'editfont-style' => 'Изглед фонта у уређивачком оквиру:',
'editfont-default' => 'по поставкама прегледача',
'editfont-monospace' => 'сразмерно широк фонт',
'editfont-sansserif' => 'бесерифни фонт',
'editfont-serif' => 'серифни фонт',

# Dates
'sunday' => 'недеља',
'monday' => 'понедељак',
'tuesday' => 'уторак',
'wednesday' => 'среда',
'thursday' => 'четвртак',
'friday' => 'петак',
'saturday' => 'субота',
'sun' => 'нед',
'mon' => 'пон',
'tue' => 'уто',
'wed' => 'сре',
'thu' => 'чет',
'fri' => 'пет',
'sat' => 'суб',
'january' => 'јануар',
'february' => 'фебруар',
'march' => 'март',
'april' => 'април',
'may_long' => 'мај',
'june' => 'јун',
'july' => 'јул',
'august' => 'август',
'september' => 'септембар',
'october' => 'октобар',
'november' => 'новембар',
'december' => 'децембар',
'january-gen' => 'јануара',
'february-gen' => 'фебруара',
'march-gen' => 'марта',
'april-gen' => 'априла',
'may-gen' => 'маја',
'june-gen' => 'јуна',
'july-gen' => 'јула',
'august-gen' => 'августа',
'september-gen' => 'септембра',
'october-gen' => 'октобра',
'november-gen' => 'новембра',
'december-gen' => 'децембра',
'jan' => 'јан',
'feb' => 'феб',
'mar' => 'мар',
'apr' => 'апр',
'may' => 'мај',
'jun' => 'јун',
'jul' => 'јул',
'aug' => 'авг',
'sep' => 'сеп',
'oct' => 'окт',
'nov' => 'нов',
'dec' => 'дец',
'january-date' => '$1 јануар',
'february-date' => '$1 фебруар',
'march-date' => '$1 март',
'april-date' => '$1 април',
'may-date' => '$1 мај',
'june-date' => '$1 јун',
'july-date' => '$1 јул',
'august-date' => '$1 август',
'september-date' => '$1 семптембар',
'october-date' => '$1 окотобар',
'november-date' => '$1 новембар',
'december-date' => '$1 децембар',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Категорија|Категорије}}',
'category_header' => 'Странице у категорији „$1“',
'subcategories' => 'Поткатегорије',
'category-media-header' => 'Датотеке у категорији „$1“',
'category-empty' => "<div style=\"margin:2em 1em 0 1em; padding:0.5em; border:1px solid #AAA; text-align:center;\">''Ова категорија тренутно не садржи странице или датотеке.''</div>",
'hidden-categories' => '{{PLURAL:$1|Сакривена категорија|Сакривене категорије}}',
'hidden-category-category' => 'Сакривене категорије',
'category-subcat-count' => '{{PLURAL:$2|Ова категорија садржи само следећу поткатегорију.|Ова категорија има {{PLURAL:$1|следећу поткатегорију|следеће $1 поткатегорије|следећих $1 поткатегорија}}, од укупно $2.}}',
'category-subcat-count-limited' => 'Ова категорија садржи {{PLURAL:$1|следећу поткатегорију|следеће $1 поткатегорије|следећих $1 поткатегорија}}.',
'category-article-count' => '{{PLURAL:$2|Ова категорија садржи само следећу страницу.|{{PLURAL:$1|Следећа страница је|Следеће $1 странице су|Следећих $1 страница је}} у овој категорији, од укупно $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Следећа страница је|Следеће $1 странице су|Следећих $1 страница је}} у овој категорији.',
'category-file-count' => '{{PLURAL:$2|Ова категорија садржи само следећу датотеку.|{{PLURAL:$1|Следећа датотека је|Следеће $1 датотеке су|Следећих $1 датотека је}} у овој категорији, од укупно $2.}}',
'category-file-count-limited' => '{{PLURAL:$1|Следећа датотека је|Следеће $1 датотеке су|Следећих $1 датотека је}} у овој категорији.',
'listingcontinuesabbrev' => 'наст.',
'index-category' => 'Пописане странице',
'noindex-category' => 'Непописане странице',
'broken-file-category' => 'Странице с неисправним везама до датотека',

'linkprefix' => '/^((?>.*(?<![a-zA-Z\\x80-\\xff])))(.+)$/sD',

'about' => 'О нама',
'article' => 'Страница са садржајем',
'newwindow' => '(отвара се у новом прозору)',
'cancel' => 'Откажи',
'moredotdotdot' => 'Више…',
'morenotlisted' => 'Ова листа није комплетна.',
'mypage' => 'Страница',
'mytalk' => 'Разговор',
'anontalk' => 'Разговор за ову ИП адресу',
'navigation' => 'Навигација',
'and' => '&#32;и',

# Cologne Blue skin
'qbfind' => 'Пронађи',
'qbbrowse' => 'Потражи',
'qbedit' => 'Уреди',
'qbpageoptions' => 'Поставке странице',
'qbmyoptions' => 'Моје странице',
'qbspecialpages' => 'Посебне странице',
'faq' => 'НПП',
'faqpage' => 'Project:НПП',

# Vector skin
'vector-action-addsection' => 'Додај тему',
'vector-action-delete' => 'Обриши',
'vector-action-move' => 'Премести',
'vector-action-protect' => 'Заштити',
'vector-action-undelete' => 'Врати',
'vector-action-unprotect' => 'Промени заштиту',
'vector-simplesearch-preference' => 'Упрошћено поље за претрагу (само за тему „Векторско“)',
'vector-view-create' => 'Направи',
'vector-view-edit' => 'Уреди',
'vector-view-history' => 'Историја',
'vector-view-view' => 'Читај',
'vector-view-viewsource' => 'Изворни код',
'actions' => 'Радње',
'namespaces' => 'Именски простори',
'variants' => 'Варијанте',

'navigation-heading' => 'Навигациони мени',
'errorpagetitle' => 'Грешка',
'returnto' => 'Назад на $1.',
'tagline' => 'Извор: {{SITENAME}}',
'help' => 'Помоћ',
'search' => 'Претрага',
'searchbutton' => 'Претражи',
'go' => 'Иди',
'searcharticle' => 'Иди',
'history' => 'Историја странице',
'history_short' => 'Историја',
'updatedmarker' => 'ажурирано од моје последње посете',
'printableversion' => 'Верзија за штампу',
'permalink' => 'Трајна веза',
'print' => 'Штампај',
'view' => 'Погледај',
'edit' => 'Уреди',
'create' => 'Направи',
'editthispage' => 'Уреди ову страницу',
'create-this-page' => 'Направи ову страницу',
'delete' => 'Обриши',
'deletethispage' => 'Обриши ову страницу',
'undeletethispage' => 'Поврати ову страницу',
'undelete_short' => 'Врати {{PLURAL:$1|једну обрисану измену|$1 обрисане измене|$1 обрисаних измена}}',
'viewdeleted_short' => 'Погледај {{PLURAL:$1|обрисану измену|$1 обрисане измене|$1 обрисаних измена}}',
'protect' => 'Заштити',
'protect_change' => 'промени',
'protectthispage' => 'Заштити ову страницу',
'unprotect' => 'Промени заштиту',
'unprotectthispage' => 'Промени заштиту ове странице',
'newpage' => 'Нова страница',
'talkpage' => 'Разговор ове странице',
'talkpagelinktext' => 'разговор',
'specialpage' => 'Посебна страница',
'personaltools' => 'Личне алатке',
'postcomment' => 'Нови одељак',
'articlepage' => 'Погледај страницу са садржајем',
'talk' => 'Разговор',
'views' => 'Прегледи',
'toolbox' => 'Алатке',
'userpage' => 'Погледај корисничку страницу',
'projectpage' => 'Погледај страницу пројекта',
'imagepage' => 'Погледај страницу датотеке',
'mediawikipage' => 'Погледај страницу поруке',
'templatepage' => 'Погледај страницу шаблона',
'viewhelppage' => 'Погледај страницу помоћи',
'categorypage' => 'Погледај страницу категорија',
'viewtalkpage' => 'Погледај разговор',
'otherlanguages' => 'Други језици',
'redirectedfrom' => '(преусмерено са $1)',
'redirectpagesub' => 'Преусмерење',
'lastmodifiedat' => 'Ова страница је последњи пут измењена $1 у $2.',
'viewcount' => 'Ова страница је прегледана {{PLURAL:$1|једанпут|$1 пута|$1 пута}}.',
'protectedpage' => 'Заштићена страница',
'jumpto' => 'Иди на:',
'jumptonavigation' => 'навигацију',
'jumptosearch' => 'претрагу',
'view-pool-error' => 'Нажалост, сервери су тренутно преоптерећени.
Превише корисника покушава да прегледа ову страницу.
Сачекајте неко време пре него што поново покушате да јој приступите.

$1',
'pool-timeout' => 'Истек времена чека на закључавање',
'pool-queuefull' => 'Ред је пун захтева',
'pool-errorunknown' => 'Непозната грешка',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'О пројекту {{SITENAME}}',
'aboutpage' => 'Project:О нама',
'copyright' => 'Садржај је доступан под лиценцом $1.',
'copyrightpage' => '{{ns:project}}:Ауторска права',
'currentevents' => 'Актуелности',
'currentevents-url' => 'Project:Новости',
'disclaimers' => 'Одрицање одговорности',
'disclaimerpage' => 'Project:Одрицање одговорности',
'edithelp' => 'Помоћ при уређивању',
'helppage' => 'Help:Садржај',
'mainpage' => 'Главна страна',
'mainpage-description' => 'Главна страна',
'policy-url' => 'Project:Правила',
'portal' => 'Радионица',
'portal-url' => 'Project:Радионица',
'privacy' => 'Политика приватности',
'privacypage' => 'Project:Политика приватности',

'badaccess' => 'Грешке у овлашћењима',
'badaccess-group0' => 'Није вам дозвољено да извршите захтевану радњу.',
'badaccess-groups' => 'Радња је доступна само корисницима у {{PLURAL:$2|следећој групи|следећим групама}}:  $1.',

'versionrequired' => 'Потребно је издање $1 Медијавикија',
'versionrequiredtext' => 'Потребно је издање $1 Медијавикија да бисте користили ову страницу.
Погледајте страницу за [[Special:Version|издање]].',

'ok' => 'У реду',
'pagetitle' => '$1 – {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'backlinksubtitle' => '← $1',
'retrievedfrom' => 'Преузето из „$1“',
'youhavenewmessages' => 'Имате $1 ($2).',
'newmessageslink' => 'нових порука',
'newmessagesdifflink' => 'последња измена',
'youhavenewmessagesfromusers' => 'Имате $1 од {{PLURAL:$3|другог корисника|$3 корисника|$3 корисника}} ($2).',
'youhavenewmessagesmanyusers' => 'Имате $1 од много корисника ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|нову поруку|нове поруке}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|последњу измену|последње измене}}',
'youhavenewmessagesmulti' => 'Имате нових порука на $1',
'editsection' => 'уреди',
'editold' => 'уреди',
'viewsourceold' => 'изворни код',
'editlink' => 'уреди',
'viewsourcelink' => 'Извор',
'editsectionhint' => 'Уредите одељак „$1“',
'toc' => 'Садржај',
'showtoc' => 'прикажи',
'hidetoc' => 'сакриј',
'collapsible-collapse' => 'скупи',
'collapsible-expand' => 'прошири',
'thisisdeleted' => 'Погледати или вратити $1?',
'viewdeleted' => 'Погледати $1?',
'restorelink' => '{{PLURAL:$1|обрисану измену|$1 обрисане измене|$1 обрисаних измена}}',
'feedlinks' => 'Довод:',
'feed-invalid' => 'Неисправна врста довода.',
'feed-unavailable' => 'Доводи нису доступни',
'site-rss-feed' => '$1 RSS довод',
'site-atom-feed' => '$1 Атом довод',
'page-rss-feed' => '„$1“ RSS довод',
'page-atom-feed' => '„$1“ Атом довод',
'feed-atom' => 'Атом',
'feed-rss' => 'RSS',
'red-link-title' => '$1 (страница не постоји)',
'sort-descending' => 'Поређај опадајуће',
'sort-ascending' => 'Поређај растуће',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Страница',
'nstab-user' => '{{GENDER:{{BASEPAGENAME}}|Корисник|Корисница}}',
'nstab-media' => 'Медији',
'nstab-special' => 'Посебно',
'nstab-project' => 'Пројекат',
'nstab-image' => 'Датотека',
'nstab-mediawiki' => 'Порука',
'nstab-template' => 'Шаблон',
'nstab-help' => 'Помоћ',
'nstab-category' => 'Категорија',

# Main script and global functions
'nosuchaction' => 'Нема такве радње',
'nosuchactiontext' => 'Радња наведена у адреси није исправна.
Можда сте погрешно написали адресу или сте пратили застарелу везу.
Могуће је и да се ради о грешци у софтверу викија.',
'nosuchspecialpage' => 'Нема такве посебне странице',
'nospecialpagetext' => '<strong>Посебна страница не постоји.</strong>

Списак свих посебних страница налази се [[Special:SpecialPages|овде]].',

# General errors
'error' => 'Грешка',
'databaseerror' => 'Грешка у бази података',
'databaseerror-function' => 'Функција: $1',
'databaseerror-error' => 'Грешка: $1',
'laggedslavemode' => "'''Упозорење:''' страница је можда застарела.",
'readonly' => 'База података је закључана',
'enterlockreason' => 'Унесите разлог за закључавање, укључујући и време откључавања',
'readonlytext' => 'База података је тренутно закључана, што значи да је није могуће мењати.

Разлог: $1',
'missing-article' => 'Текст странице под називом „$1“ ($2) није пронађен.

Узрок ове грешке је обично застарела измена или веза до обрисане странице.

Ако се не ради о томе, онда сте вероватно пронашли грешку у софтверу.
Пријавите је [[Special:ListUsers/sysop|администратору]] уз одговарајућу везу.',
'missingarticle-rev' => '(измена#: $1)',
'missingarticle-diff' => '(разлика: $1, $2)',
'readonly_lag' => 'База података је закључана док се споредни базни сервери не ускладе с главним.',
'internalerror' => 'Унутрашња грешка',
'internalerror_info' => 'Унутрашња грешка: $1',
'fileappenderrorread' => 'Не могу да прочитам „$1“ током качења.',
'fileappenderror' => 'Не могу да закачим „$1“ на „$2“.',
'filecopyerror' => 'Не могу да умножим датотеку „$1“ у „$2“.',
'filerenameerror' => 'Не могу да преименујем датотеку „$1“ у „$2“.',
'filedeleteerror' => 'Не могу да обришем датотеку „$1“.',
'directorycreateerror' => 'Не могу да направим фасциклу „$1“.',
'filenotfound' => 'Не могу да пронађем датотеку „$1“.',
'fileexistserror' => 'Не могу да пишем по датотеци „$1“: датотека већ постоји',
'unexpected' => 'Неочекивана вредност: „$1“=„$2“.',
'formerror' => 'Грешка: не могу да пошаљем образац',
'badarticleerror' => 'Ова радња се не може извршити на овој страници.',
'cannotdelete' => 'Не могу да обришем страницу или датотеку „$1“.
Вероватно ју је неко други обрисао.',
'cannotdelete-title' => 'Не могу да обришем страницу „$1“',
'delete-hook-aborted' => 'Брисање је прекинула кука.
Није дато никакво образложење.',
'badtitle' => 'Неисправан наслов',
'badtitletext' => 'Наслов странице је неисправан, празан или је међујезички или међувики наслов погрешно повезан.
Можда садржи знакове који се не могу користити у насловима.',
'perfcached' => 'Следећи подаци су кеширани и могу бити застарели. Кеш садржи највише {{PLURAL:$1|један резултат|$1 резултата|$1 резултата}}.',
'perfcachedts' => 'Следећи подаци су кеширани, а последњи пут су ажурирани $2 у $3. Кеш садржи највише {{PLURAL:$4|један резултат|$4 резултата|$4 резултата}}.',
'querypage-no-updates' => 'Ажурирање ове странице је тренутно онемогућено.
Подаци који се овде налазе могу бити застарели.',
'wrong_wfQuery_params' => 'Неисправни параметри за wfQuery()<br />
Функција: $1<br />
Упит: $2',
'viewsource' => 'Изворни код',
'viewsource-title' => 'Приказ извора странице $1',
'actionthrottled' => 'Радња је успорена',
'actionthrottledtext' => 'У циљу борбе против непожељних порука, ограничене су вам измене у одређеном времену, а управо сте прешли то ограничење. Покушајте поново за неколико минута.',
'protectedpagetext' => 'Ова страница је закључана за измене и друге радње.',
'viewsourcetext' => 'Можете да погледате и копирате изворни текст ове странице:',
'viewyourtext' => "Можете да погледате и копирате извор '''ваших измена''' на овој страници:",
'protectedinterface' => 'Ова страница садржи текст корисничког окружења за софтвер на овом викију и заштићена је ради спречавања злоупотребе.
Да бисте додали или изменили преводе свих викија, посетите [//translatewiki.net/ Транслејтвики], пројекат за локализацију Медијавикија.',
'editinginterface' => "'''Упозорење:''' уређујете страницу која се користи за приказивање текста корисничког окружења.
Измене на овој страници ће утицати на све кориснике овог викија.
Да бисте додали или изменили преводе свих викија, посетите [//translatewiki.net/wiki/Main_Page?setlang=sr_ec Транслејтвики], пројекат за локализацију Медијавикија.",
'cascadeprotected' => 'Ова страница је закључана јер садржи {{PLURAL:$1|следећу страницу која је заштићена|следеће странице које су заштићене}} „преносивом“ заштитом:
$2',
'namespaceprotected' => "Немате дозволу да уређујете странице у именском простору '''$1'''.",
'customcssprotected' => 'Немате дозволу да мењате ову CSS страницу јер садржи личне поставке другог корисника.',
'customjsprotected' => 'Немате дозволу да мењате ову страницу јаваскрипта јер садржи личне поставке другог корисника.',
'mycustomcssprotected' => 'Немате дозволу за мењање ове CSS странице.',
'mycustomjsprotected' => 'Немате дозволу за мењање ове JavaScript странице.',
'myprivateinfoprotected' => 'Немате дозволу за мењање ваших личних информација.',
'mypreferencesprotected' => 'Немате дозволу за мењање ваших подешавања.',
'ns-specialprotected' => 'Посебне странице се не могу уређивати.',
'titleprotected' => "Овај наслов је {{GENDER:$1|заштитио корисник|заштитила корисница|заштитио корисник}} [[User:$1|$1]].
Наведени разлог: ''$2''.",
'filereadonlyerror' => 'Не могу да изменим датотеку „$1“ јер је ризница „$2“ у режиму за читање.

Администратор који ју је закључао понудио је следеће објашњење: „$3“.',
'invalidtitle-knownnamespace' => 'Неисправан наслов с именским простором „$2“ и текстом „$3“',
'invalidtitle-unknownnamespace' => 'Неисправан наслов с именским простором бр. $1 и текстом „$2“',
'exception-nologin' => 'Нисте пријављени',
'exception-nologin-text' => 'Ова страница или радња захтева да будете пријављени на вики.',

# Virus scanner
'virus-badscanner' => "Неисправна поставка: непознати скенер за вирусе: ''$1''",
'virus-scanfailed' => 'неуспешно скенирање (код $1)',
'virus-unknownscanner' => 'непознати антивирус:',

# Login and logout pages
'logouttext' => "'''Одјављени сте.'''

Имајте на уму да неке странице могу наставити да се приказују као да сте још пријављени, све док не очистите привремену меморију свог прегледача.",
'welcomeuser' => 'Добро дошли, $1!',
'welcomecreation-msg' => 'Ваш налог је отворен.
Не заборавите да промените своја [[Special:Preferences|подешавања]].',
'yourname' => 'Корисничко име:',
'userlogin-yourname' => 'Корисничко име',
'userlogin-yourname-ph' => 'Унесите ваше корисничко име',
'createacct-another-username-ph' => 'Унесите корисничко име',
'yourpassword' => 'Лозинка:',
'userlogin-yourpassword' => 'Лозинка',
'userlogin-yourpassword-ph' => 'Унесите вашу лозинку',
'createacct-yourpassword-ph' => 'Унесите нову лозинку',
'yourpasswordagain' => 'Потврда лозинке:',
'createacct-yourpasswordagain' => 'Потврдите лозинку',
'createacct-yourpasswordagain-ph' => 'Унесите лозинку још једном',
'remembermypassword' => 'Запамти моју лозинку на овом прегледачу (најдуже $1 {{PLURAL:$1|дан|дана}})',
'userlogin-remembermypassword' => 'Остави ме пријављеног/у',
'userlogin-signwithsecure' => 'Користите сигурну конекцију',
'yourdomainname' => 'Домен:',
'password-change-forbidden' => 'Не можете да промените лозинку на овом викију.',
'externaldberror' => 'Дошло је до грешке при препознавању базе података или немате овлашћења да ажурирате свој спољни налог.',
'login' => 'Пријави ме',
'nav-login-createaccount' => 'Пријава/регистрација',
'loginprompt' => 'Омогућите колачиће да бисте се пријавили на овај вики.',
'userlogin' => 'Пријава/регистрација',
'userloginnocreate' => 'Пријава',
'logout' => 'Одјава',
'userlogout' => 'Одјави ме',
'notloggedin' => 'Нисте пријављени',
'userlogin-noaccount' => 'Немате налог?',
'userlogin-joinproject' => 'Отворите га',
'nologin' => 'Немате налог? Идите на страницу $1.',
'nologinlink' => 'Отварање налога',
'createaccount' => 'Отвори налог',
'gotaccount' => 'Већ имате налог? Идите на страницу „$1“.',
'gotaccountlink' => 'Пријава',
'userlogin-resetlink' => 'Заборавили сте податке за пријаву?',
'userlogin-resetpassword-link' => 'Ресетујте лозинку',
'helplogin-url' => 'Help:Logging in',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Помоћ при пријављивању]]',
'userlogin-createanother' => 'Отвори још један налог',
'createacct-join' => 'Унесите своје податке испод',
'createacct-another-join' => 'Унесите податке за нови налог испод.',
'createacct-emailrequired' => 'Адреса е-поште',
'createacct-emailoptional' => 'Адреса е-поште (опционо)',
'createacct-email-ph' => 'Унесите вашу адресу е-поште',
'createacct-another-email-ph' => 'Унесите адресу е-поште',
'createaccountmail' => 'Користите привремену, случајно створену лозинку и пошаљите на наведену адресу електронске поште',
'createacct-realname' => 'Право име (опционо)',
'createaccountreason' => 'Разлог:',
'createacct-reason' => 'Разлог',
'createacct-reason-ph' => 'Зашто правите још један налог?',
'createacct-captcha' => 'Сигурносна провера',
'createacct-imgcaptcha-ph' => 'Унесите текст који видите изнад',
'createacct-submit' => 'Отвори налог',
'createacct-another-submit' => 'Отвори још један налог',
'createacct-benefit-heading' => '{{SITENAME}} је направљен од стране људи као што сте ви.',
'createacct-benefit-body1' => '{{PLURAL:$1|измена}}',
'createacct-benefit-body2' => '{{PLURAL:$1|страница}}',
'createacct-benefit-body3' => 'скорашњих {{PLURAL:$1|доприноса}}',
'badretype' => 'Унете лозинке се не поклапају.',
'userexists' => 'Корисничко име је заузето. Изаберите друго.',
'loginerror' => 'Грешка при пријављивању',
'createacct-error' => 'Дошло је до грешке при креирању налога',
'createaccounterror' => 'Не могу да отворим налог: $1',
'nocookiesnew' => 'Кориснички налог је отворен, али нисте пријављени.
Овај вики користи колачиће за пријаву. Вама су колачићи онемогућени.
Омогућите их, па се онда пријавите са својим корисничким именом и лозинком.',
'nocookieslogin' => 'Овај вики користи колачиће за пријављивање корисника.
Вама су колачићи онемогућени. Омогућите их и покушајте поново.',
'nocookiesfornew' => 'Кориснички налог није отворен јер његов извор није потврђен.
Омогућите колачиће на прегледачу и поново учитајте страницу.',
'nocookiesforlogin' => '{{int:nocookieslogin}}',
'noname' => 'Унели сте неисправно корисничко име.',
'loginsuccesstitle' => 'Успешно пријављивање',
'loginsuccess' => "'''Пријављени сте као „$1“.'''",
'nosuchuser' => 'Не постоји корисник с именом „$1“.
Корисничка имена су осетљива на мала и велика слова.
Проверите да ли сте га добро унели или [[Special:UserLogin/signup|отворите нови налог]].',
'nosuchusershort' => 'Корисник с именом „$1“ не постоји.
Проверите да ли сте правилно написали.',
'nouserspecified' => 'Морате навести корисничко име.',
'login-userblocked' => '{{GENDER:$1|Овај корисник је блокиран|Ова корисница је блокирана|Овај корисник је блокиран}}. Пријава није дозвољена.',
'wrongpassword' => 'Унели сте неисправну лозинку. Покушајте поново.',
'wrongpasswordempty' => 'Нисте унели лозинку. Покушајте поново.',
'passwordtooshort' => 'Лозинка мора имати најмање {{PLURAL:$1|један знак|$1 знака|$1 знакова}}.',
'password-name-match' => 'Лозинка се мора разликовати од корисничког имена.',
'password-login-forbidden' => 'Коришћење овог корисничког имена и лозинке је забрањено.',
'mailmypassword' => 'Пошаљи нову лозинку',
'passwordremindertitle' => '{{SITENAME}} – подсетник за лозинку',
'passwordremindertext' => 'Неко, вероватно ви, са ИП адресе $1 је затражио нову лозинку на викију {{SITENAME}} ($4).
Створена је привремена лозинка за {{GENDER:$2|корисника|корисницу|корисника}} $2 која гласи $3.
Уколико је ово ваш захтев, сада се пријавите и поставите нову лозинку.
Привремена лозинка истиче за {{PLURAL:$5|један дан|$5 дана|$5 дана}}.

Ако је неко други затражио промену лозинке, или сте се сетили ваше лозинке и не желите да је мењате, занемарите ову поруку.',
'noemail' => 'Не постоји е-адреса за {{GENDER:$1|корисника|корисницу|корисника}} $1.',
'noemailcreate' => 'Морате навести исправну е-адресу',
'passwordsent' => 'Нова лозинка је послата на е-адресу {{GENDER:$1|корисника|кориснице|корисника}} $1.
Пријавите се пошто је примите.',
'blocked-mailpassword' => 'Вашој ИП адреси је онемогућено уређивање страница, као и могућност захтевања нове лозинке.',
'eauthentsent' => 'На наведену е-адресу је послат потврдни код.
Пре него што пошаљемо даљње поруке, пратите упутства с е-поште да бисте потврдили да сте ви отворили налог.',
'throttled-mailpassword' => 'Подсетник за лозинку је послат {{PLURAL:$1|пре сат времена|у последња $1 сата|у последњих $1 сати}}.
Да бисмо спречили злоупотребу, подсетник шаљемо само једном у року од {{PLURAL:$1|једног сата|$1 сата|$1 сати}}.',
'mailerror' => 'Грешка при слању поруке: $1',
'acct_creation_throttle_hit' => 'Посетиоци овог викија који користе вашу ИП адресу су већ отворили {{PLURAL:$1|један налог|$1 налога|$1 налога}} претходни дан, што је највећи дозвољени број у том временском периоду.
Због тога посетиоци с ове ИП адресе тренутно не могу отворити више налога.',
'emailauthenticated' => 'Ваша е-адреса је потврђена $2 у $3.',
'emailnotauthenticated' => 'Ваша е-адреса још није потврђена.
Поруке неће бити послате ни за једну од следећих могућности.',
'noemailprefs' => 'Унесите е-адресу како би ове могућности радиле.',
'emailconfirmlink' => 'Потврдите своју е-адресу',
'invalidemailaddress' => 'Е-адреса не може бити прихваћена јер је неисправног облика.
Унесите исправну адресу или оставите празно поље.',
'cannotchangeemail' => 'На овом викију не можете променити е-адресу налога.',
'emaildisabled' => 'Овај сајт не може да шаље е-поруке.',
'accountcreated' => 'Налог је отворен',
'accountcreatedtext' => 'Кориснички налог [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|talk]]) је отворен.',
'createaccount-title' => 'Отварање корисничког налога за {{SITENAME}}',
'createaccount-text' => 'Неко је отворио налог с вашом е-адресом на {{SITENAME}} ($4) под именом $2 и лозинком $3.
Пријавите се и промените своју лозинку.

Ако је ово грешка, занемарите ову поруку.',
'usernamehasherror' => 'Корисничко име не може садржати тарабе',
'login-throttled' => 'Превише пута сте покушали да се пријавите.
Молимо вас да сачекате $1 пре него што покушате поново.',
'login-abort-generic' => 'Неуспешна пријава – прекинуто',
'loginlanguagelabel' => 'Језик: $1',
'suspicious-userlogout' => 'Ваш захтев за одјаву је одбијен јер је послат од стране неисправног прегледача или посредника.',
'createacct-another-realname-tip' => 'Право име није обавезно.
Ако изаберете да га унесете, оно ће бити коришћено за приписивање вашег рада.',

# Email sending
'php-mail-error-unknown' => 'Непозната грешка у функцији PHP mail().',
'user-mail-no-addy' => 'Покушали сте да пошаљете поруку без е-адресе.',
'user-mail-no-body' => 'Покушано слање електронске поруке с празним или неразумно кратким садржајем.',

# Change password dialog
'resetpass' => 'Промена лозинке',
'resetpass_announce' => 'Пријављени сте с привременом лозинком.
Да бисте завршили пријаву, подесите нову лозинку овде:',
'resetpass_text' => '<!-- Овде унесите текст -->',
'resetpass_header' => 'Промена лозинке налога',
'oldpassword' => 'Стара лозинка:',
'newpassword' => 'Нова лозинка:',
'retypenew' => 'Потврда лозинке:',
'resetpass_submit' => 'Постави лозинку и пријави ме',
'changepassword-success' => 'Ваша лозинка је успешно промењена!',
'resetpass_forbidden' => 'Лозинка не може бити промењена',
'resetpass-no-info' => 'Морате бити пријављени да бисте приступили овој страници.',
'resetpass-submit-loggedin' => 'Промени лозинку',
'resetpass-submit-cancel' => 'Откажи',
'resetpass-wrong-oldpass' => 'Неисправна привремена или текућа лозинка.
Можда сте већ променили лозинку или сте затражили нову привремену лозинку.',
'resetpass-temp-password' => 'Привремена лозинка:',
'resetpass-abort-generic' => 'Промену лозинке је спречио додатак.',

# Special:PasswordReset
'passwordreset' => 'Обнављање лозинке',
'passwordreset-text-one' => 'Попуните овај образац да бисте ресетовали лозинку.',
'passwordreset-legend' => 'Поништи лозинку',
'passwordreset-disabled' => 'Обнављање лозинке је онемогућено на овом викију.',
'passwordreset-emaildisabled' => 'Е-пошта је онемогућена на овом викију.',
'passwordreset-username' => 'Корисничко име:',
'passwordreset-domain' => 'Домен:',
'passwordreset-capture' => 'Погледати крајњу поруку?',
'passwordreset-capture-help' => 'Ако означите ову кућицу, е-порука с привременом лозинком ће бити приказана и послата кориснику.',
'passwordreset-email' => 'Е-адреса:',
'passwordreset-emailtitle' => 'Детаљи налога на викију {{SITENAME}}',
'passwordreset-emailtext-ip' => 'Неко, вероватно Ви, са ИП адресе $1 је затражио нову лозинку на викију {{SITENAME}} ($4).
Следећи {{PLURAL:$3|кориснички налог је повезан|кориснички налози су повезани}} с овом е-адресом:

$2

{{PLURAL:$3|Привремена лозинка истиче|Привремене лозинке истичу}} за {{PLURAL:$5|један дан|$5 дана}}.
Пријавите се и изаберите нову лозинку. Ако је неко други захтевао ову радњу или сте се сетили лозинке и не желите да је мењате, занемарите ову поруку и наставите користити стару лозинку.',
'passwordreset-emailtext-user' => '{{GENDER:$1|Корисник|Корисница|Корисник}} је затражио подсетник о подацима за пријаву на викију {{SITENAME}} ($4).
Следећи {{PLURAL:$3|кориснички налог је повезан|кориснички налози су повезани}} с овом е-адресом:

$2

{{PLURAL:$3|Привремена лозинка истиче|Привремене лозинке истичу}} за {{PLURAL:$5|један дан|$5 дана|$5 дана}}.
Пријавите се и изаберите нову лозинку. Ако је неко други захтевао ову радњу или сте се сетили лозинке и не желите да је мењате, занемарите ову поруку.',
'passwordreset-emailelement' => 'Корисничко име: $1
Привремена лозинка: $2',
'passwordreset-emailsent' => 'Подсетник о лозинци је послат на вашу адресу.',
'passwordreset-emailsent-capture' => 'Послат је подсетник преко е-поште (приказан доле).',
'passwordreset-emailerror-capture' => 'Е-порука за ресетовање лозинке, приказана испод је послата, али слање {{GENDER:$2|кориснику|корисници}} није успело: $1',

# Special:ChangeEmail
'changeemail' => 'Промена е-адресе',
'changeemail-header' => 'Промените е-адресу налога',
'changeemail-text' => 'Попуните овај образац да бисте променили своју е-адресу. Мораћете да унесете лозинку да потврдите измену.',
'changeemail-no-info' => 'Морате бити пријављени да бисте приступили овој страници.',
'changeemail-oldemail' => 'Тренутна е-адреса:',
'changeemail-newemail' => 'Нова е-адреса:',
'changeemail-none' => '(ништа)',
'changeemail-password' => 'Ваша лозинка:',
'changeemail-submit' => 'Промени',
'changeemail-cancel' => 'Откажи',

# Edit page toolbar
'bold_sample' => 'Подебљан текст',
'bold_tip' => 'Подебљан текст',
'italic_sample' => 'Искошени текст',
'italic_tip' => 'Искошени текст',
'link_sample' => 'Наслов везе',
'link_tip' => 'Унутрашња веза',
'extlink_sample' => 'http://www.primer.com наслов везе',
'extlink_tip' => "Спољна веза (с предметком ''http://'')",
'headline_sample' => 'Наслов',
'headline_tip' => 'Поднаслов',
'nowiki_sample' => 'Убаците необликован текст овде',
'nowiki_tip' => 'Занемари вики обликовање',
'image_sample' => 'Пример.jpg',
'image_tip' => 'Уграђена датотека',
'media_sample' => 'Пример.ogg',
'media_tip' => 'Веза',
'sig_tip' => 'Потпис с тренутним временом',
'hr_tip' => 'Водоравна линија (користити ретко)',

# Edit pages
'summary' => 'Опис измене:',
'subject' => 'Тема/наслов:',
'minoredit' => 'мања измена',
'watchthis' => 'надгледај ову страницу',
'savearticle' => 'Сачувај страницу',
'preview' => 'Претпреглед',
'showpreview' => 'Прикажи претпреглед',
'showlivepreview' => 'Тренутни преглед',
'showdiff' => 'Прикажи измене',
'anoneditwarning' => "'''Упозорење:''' нисте пријављени.
Ваша ИП адреса ће бити забележена у историји ове странице.",
'anonpreviewwarning' => "''Нисте пријављени. Ваша ИП адреса ће бити забележена у историји ове странице.''",
'missingsummary' => "'''Напомена:''' нисте унели опис измене.
Ако поново кликнете на „{{int:savearticle}}“, ваша измена ће бити сачувана без описа.",
'missingcommenttext' => 'Унесите коментар испод.',
'missingcommentheader' => "'''Напомена:''' нисте унели наслов овог коментара.
Ако поново кликнете на „{{int:savearticle}}“, ваша измена ће бити сачувана без наслова.",
'summary-preview' => 'Преглед описа:',
'subject-preview' => 'Преглед теме/наслова:',
'blockedtitle' => 'Корисник је блокиран',
'blockedtext' => "'''Ваше корисничко име или ИП адреса је блокирана.'''

Блокирање је {{GENDER:$4|извршио|извршила|извршио}} $1.
Разлог: ''$2''.

* Датум блокирања: $8
* Блокирање истиче: $6
* Име корисника: $7

Обратите се {{GENDER:$4|кориснику|корисници|кориснику}} $1 или [[{{MediaWiki:Grouppage-sysop}}|администратору]] да разјасните ствар.
Не можете користити могућност „Пошаљи поруку овом кориснику“ ако нисте унели исправну е-адресу у [[Special:Preferences|подешавањима]].
Ваша блокирана ИП адреса је $3, а ИБ $5.
Наведите све податке изнад при стварања било каквих упита.",
'autoblockedtext' => "Ваша ИП адреса је блокирана јер ју је употребљавао други корисник, кога је {{GENDER:$1|блокирао|блокирала|блокирао}} $1.
Разлог:

:''$2''

* Датум блокирања: $8
* Блокирање истиче: $6
* Име корисника: $7

Обратите се {{GENDER:$1|кориснику|корисници|кориснику}} $1 или [[{{MediaWiki:Grouppage-sysop}}|администратору]] да разјасните ствар.

Не можете користити могућност „Пошаљи поруку овом кориснику“ ако нисте унели исправну е-адресу у [[Special:Preferences|подешавањима]].

Ваша блокирана ИП адреса је $3, а ИБ $5.
Наведите све податке изнад при стварању било каквих упита.",
'blockednoreason' => 'разлог није наведен',
'whitelistedittext' => 'За уређивање странице је потребно да будете $1.',
'confirmedittext' => 'Морате да потврдите своју е-адресу пре уређивања страница.
Поставите и потврдите адресу преко [[Special:Preferences|подешавања]].',
'nosuchsectiontitle' => 'Не могу да пронађем одељак',
'nosuchsectiontext' => 'Покушали сте да уредите одељак који не постоји.
Можда је премештен или обрисан док сте прегледали страницу.',
'loginreqtitle' => 'Потребна је пријава',
'loginreqlink' => 'пријављени',
'loginreqpagetext' => 'Морате бити $1 да бисте видели друге странице.',
'accmailtitle' => 'Лозинка је послата.',
'accmailtext' => 'Лозинка за {{GENDER:$1|корисника|корисницу}} [[User talk:$1|$1]] је послата на $2. Након пријаве, лозинка се може променити [[Special:ChangePassword|овде]].',
'newarticle' => '(нови)',
'newarticletext' => 'Дошли сте на страницу која још не постоји.
Да бисте је направили, почните куцати у прозор испод овог текста (погледајте [[{{MediaWiki:Helppage}}|страницу за помоћ]]).
Ако сте овде дошли грешком, вратите се на претходну страницу.',
'anontalkpagetext' => '---- Ово је страница за разговор с анонимним корисником који још нема налог или га не користи.
Због тога морамо да користимо бројчану ИП адресу како бисмо га препознали.
Такву адресу може делити више корисника.
Ако сте анонимни корисник и мислите да су вам упућене примедбе, [[Special:UserLogin/signup|отворите налог]] или се [[Special:UserLogin|пријавите]] да бисте избегли будућу забуну с осталим анонимним корисницима.',
'noarticletext' => 'На овој страници тренутно нема садржаја.
Можете [[Special:Search/{{PAGENAME}}|потражити овај наслов]] на другим страницама,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} претражити сродне извештаје] или [{{fullurl:{{FULLPAGENAME}}|action=edit}} уредити страницу]</span>.',
'noarticletext-nopermission' => 'На овој страници тренутно нема садржаја.
Можете [[Special:Search/{{PAGENAME}}|потражити овај наслов]] на другим страницама или <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} претражити сродне дневнике]</span>, али немате дозволу да направите ову страницу.',
'missing-revision' => 'Не могу да пронађем измену бр. $1 на страници под називом „{{PAGENAME}}“.

Ово се обично дешава када пратите застарелу везу до странице која је обрисана.
Више информација можете пронаћи у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} дневнику брисања].',
'userpage-userdoesnotexist' => 'Кориснички налог „<nowiki>$1</nowiki>“ није отворен.
Размислите да ли заиста желите да направите или уредите ову страницу.',
'userpage-userdoesnotexist-view' => 'Кориснички налог „$1“ није отворен.',
'blocked-notice-logextract' => 'Овај корисник је тренутно блокиран.
Извештај о последњем блокирању можете погледати испод:',
'clearyourcache' => "'''Напомена:''' након чувања, можда ћете морати да очистите кеш прегледача.
*'''Фајерфокс и Сафари:''' држите ''Shift'' и кликните на ''Освежи'', или притисните ''Ctrl-F5'' или Ctrl-R (''⌘-R'' на Макинтошу)
*'''Гугл кроум:''' притисните ''Ctrl-Shift-R'' (''⌘-Shift-R'' на Макинтошу)
*'''Интернет експлорер: '''држите ''Ctrl'' и кликните на ''Освежи'', или притисните ''Ctrl-F5''
*'''Опера:''' очистите привремену меморију преко менија ''Алатке → Поставке''.",
'usercssyoucanpreview' => "'''Савет:''' кориситите дугме „{{int:showpreview}}“ да испробате свој нови CSS пре него што га сачувате.",
'userjsyoucanpreview' => "'''Савет:''' кориситите дугме „{{int:showpreview}}“ да испробате свој нови јаваскрипт пре него што га сачувате.",
'usercsspreview' => "'''Ово је само преглед CSS-а.'''
'''Страница још није сачувана!'''",
'userjspreview' => "'''Ово је само преглед јаваскрипта.'''
'''Страница још није сачувана!'''",
'sitecsspreview' => "'''Ово је само преглед CSS-а.'''
'''Страница још није сачувана!'''",
'sitejspreview' => "'''Ово је само преглед јаваскрипта.'''
'''Страница још није сачувана!'''",
'userinvalidcssjstitle' => "'''Упозорење:''' не постоји тема „$1“.
Прилагођене странице CSS и јаваскрипт почињу малим словом, нпр. {{ns:user}}:Foo/vector.css, а не {{ns:user}}:Foo/Vector.css.",
'updated' => '(Ажурирано)',
'note' => "'''Напомена:'''",
'previewnote' => "'''Имајте у виду да је ово само преглед.'''
Ваше измене још нису сачуване!",
'continue-editing' => 'Иди на уређивачки оквир',
'previewconflict' => 'Овај преглед осликава како ће текст у текстуалном оквиру изгледати.',
'session_fail_preview' => "'''Нисмо могли да обрадимо вашу измену због губитка података сесије.'''
Покушајте поново.
Ако и даље не ради, покушајте да се [[Special:UserLogout|одјавите]] и поново пријавите.",
'session_fail_preview_html' => "'''Нисмо могли да обрадимо вашу измену због губитка података сесије.'''

''Будући да је на овом викију омогућен унос HTML ознака, преглед је сакривен као мера предострожности против напада преко јаваскрипта.''

'''Ако сте покушали да направите праву измену, покушајте поново.
Ако и даље не ради, покушајте да се [[Special:UserLogout|одјавите]] и поново пријавите.'''",
'token_suffix_mismatch' => "'''Ваша измена је одбачена јер је ваш прегледач убацио знакове интерпункције у новчић уређивања.
То се понекад догађа када се користи неисправан посредник.'''",
'edit_form_incomplete' => "'''Неки делови обрасца за уређивање нису достигли до сервера. Проверите да ли су измене промењене и покушајте поново.'''",
'editing' => 'Уређујете $1',
'creating' => 'Прављење странице $1',
'editingsection' => 'Уређујете $1 (одељак)',
'editingcomment' => 'Уређујете $1 (нови одељак)',
'editconflict' => 'Сукобљене измене: $1',
'explainconflict' => "Неко други је у међувремену променио ову страницу.
Горњи оквир садржи текст странице.
Ваше измене су приказане у доњем пољу.
Мораћете да унесете своје промене у постојећи текст.
'''Само''' ће текст у горњем текстуалном оквиру бити сачуван када кликнете на „{{int:savearticle}}“.",
'yourtext' => 'Ваш текст',
'storedversion' => 'Ускладиштена измена',
'nonunicodebrowser' => "'''Упозорење: ваш прегледач не подржава уникод.'''
Промените га пре него што почнете с уређивањем.",
'editingold' => "'''Упозорење: уређујете застарелу измену ове странице.
Ако је сачувате, све новије измене ће бити изгубљене.'''",
'yourdiff' => 'Разлике',
'copyrightwarning' => "Имајте на уму да се сви доприноси на овом викију сматрају као објављени под лиценцом $2 (више на $1).
Ако не желите да се ваши текстови мењају и размењују без ограничења, онда их не шаљите овде.<br />
Исто тако обећавате да сте ви аутор текста, или да сте га умножили с извора који је у јавном власништву.
'''Не шаљите радове заштићене ауторским правима без дозволе!'''",
'copyrightwarning2' => "Имајте на уму да се сви доприноси на овом викију могу мењати, враћати или брисати од других корисника.
Ако не желите да се ваши текстови слободно мењају и расподељују, не шаљите их овде.<br />
Исто тако обећавате да сте ви аутор текста, или да сте га умножили с извора који је у јавном власништву (више на $1).
'''Не шаљите радове заштићене ауторским правима без дозволе!'''",
'longpageerror' => "'''Грешка: текст који сте унели је величине {{PLURAL:$1|један килобајт|$1 килобајта|$1 килобајта}}, што је веће од {{PLURAL:$2|дозвољеног једног килобајта|дозвољена $2 килобајта|дозвољених $2 килобајта}}.'''
Страница не може бити сачувана.",
'readonlywarning' => "'''Упозорење: база података је закључана ради одржавања, тако да тренутно нећете моћи да сачувате измене.'''
Можда бисте желели сачувати текст за касније у некој текстуалној датотеци.

Администратор који је закључао базу дао је следеће објашњење: $1",
'protectedpagewarning' => "'''Упозорење: ова страница је заштићена, тако да само администратори могу да је мењају.'''
Последња ставка у историји је приказана испод:",
'semiprotectedpagewarning' => "'''Напомена:''' ова страница је заштићена, тако да само регистровани корисници могу да је уређују.
Последњи запис у дневнику је приказан испод:",
'cascadeprotectedwarning' => "'''Упозорење:''' ова страница је заштићена тако да је могу уређивати само администратори, јер је она укључена у {{PLURAL:$1|следећу страницу која је|следеће странице које су}} заштићене „преносивом“ заштитом:",
'titleprotectedwarning' => "'''Упозорење: ова страница је заштићена тако да је могу направити само корисници [[Special:ListGroupRights|с одређеним правима]].'''",
'templatesused' => '{{PLURAL:$1|Шаблон|Шаблони}} на овој страници:',
'templatesusedpreview' => '{{PLURAL:$1|Шаблон|Шаблони}} у овом прегледу:',
'templatesusedsection' => '{{PLURAL:$1|Шаблон|Шаблони}} у овом одељку:',
'template-protected' => '(заштићен)',
'template-semiprotected' => '(полузаштићен)',
'hiddencategories' => 'Ова страница је члан {{PLURAL:$1|једне скривене категорије|$1 скривене категорије|$1 скривених категорија}}:',
'edittools' => '<!-- Овај текст ће бити приказан испод обрасца за уређивање и отпремање. -->',
'edittools-upload' => '-',
'nocreatetext' => 'На овом викију је ограничено прављење нових страница.
Можете се вратити и уредити постојећу страницу, или се [[Special:UserLogin|пријавите или отворите налог]].',
'nocreate-loggedin' => 'Немате дозволу да правите нове странице.',
'sectioneditnotsupported-title' => 'Уређивање одељка није подржано',
'sectioneditnotsupported-text' => 'Уређивање одељка није подржано на овој страници.',
'permissionserrors' => 'Грешка у дозволи',
'permissionserrorstext' => 'Немате овлашћење за ту радњу из {{PLURAL:$1|следећег|следећих}} разлога:',
'permissionserrorstext-withaction' => 'Немате дозволу за $2 из {{PLURAL:$1|следећег|следећих}} разлога:',
'recreate-moveddeleted-warn' => "'''Упозорење: поново правите страницу која је претходно обрисана.'''

Размотрите да ли је прикладно да наставите с уређивањем ове странице.
Овде је наведена историја брисања и премештања с образложењем:",
'moveddeleted-notice' => 'Ова страница је обрисана.
Историја њеног брисања и премештања налази се испод:',
'log-fulllog' => 'Погледај целу историју',
'edit-hook-aborted' => 'Измену је прекинула кука.
Није дато никакво образложење.',
'edit-gone-missing' => 'Не могу да ажурирам страницу.
Изгледа да је обрисана.',
'edit-conflict' => 'Сукоб измена.',
'edit-no-change' => 'Ваша измена је занемарена јер није било никаквих измена у тексту.',
'postedit-confirmation' => 'Ваша измена је сачувана.',
'edit-already-exists' => 'Не могу да направим страницу.
Изгледа да она већ постоји.',
'defaultmessagetext' => 'Подразумевани текст поруке',
'content-failed-to-parse' => 'Не могу да рашчланим садржај типа $2 за модел $1: $3',
'invalid-content-data' => 'Неисправни подаци садржаја',
'content-not-allowed-here' => 'Садржај модела „$1“ није дозвољен на страници [[$2]]',
'editwarning-warning' => 'Ако напустите ову страницу, изгубићете све измене које сте направили.
Ако сте пријављени, можете онемогућити ово упозорење у својим подешавањима, у одељку „Уређивање“.',

# Content models
'content-model-wikitext' => 'викитекст',
'content-model-text' => 'чист текст',
'content-model-javascript' => 'јаваскрипт',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Упозорење:''' ова страница садржи превише позива за рашчлањивање.

Требало би да има мање од $2 {{PLURAL:$2|позив|позива|позива}}, а сада има $1.",
'expensive-parserfunction-category' => 'Странице с превише позива за рашчлањивање',
'post-expand-template-inclusion-warning' => "'''Упозорење:''' величина укљученог шаблона је превелика.
Неки шаблони неће бити укључени.",
'post-expand-template-inclusion-category' => 'Странице где су укључени шаблони превелики',
'post-expand-template-argument-warning' => "'''Упозорење:''' ова страница садржи најмање један аргумент у шаблону који има превелику величину.
Овакве аргументе би требало избегавати.",
'post-expand-template-argument-category' => 'Странице које садрже изостављене аргументе у шаблону',
'parser-template-loop-warning' => 'Откривена је петља шаблона: [[$1]]',
'parser-template-recursion-depth-warning' => 'Дубина укључивања шаблона је прекорачена ($1)',
'language-converter-depth-warning' => 'Прекорачена је граница дубине језичког претварача ($1)',
'node-count-exceeded-category' => 'Странице у којима је прекорачен број чворова',
'node-count-exceeded-warning' => 'Страница у којој је прекорачен број чворова',
'expansion-depth-exceeded-category' => 'Странице у којима је прекорачена дубина проширења',
'expansion-depth-exceeded-warning' => 'Страница у којој је прекорачена дубина проширења',
'parser-unstrip-loop-warning' => 'Утврђена је петља',
'parser-unstrip-recursion-limit' => 'Прекорачено је ограничење рекурзије ($1)',
'converter-manual-rule-error' => 'Пронађена је грешка у правилу за ручно претварање језика',

# "Undo" feature
'undo-success' => 'Измена се може вратити.
Проверите разлике испод, па сачувајте измене.',
'undo-failure' => 'Не могу да вратим измену због постојања сукобљених међуизмена.',
'undo-norev' => 'Не могу да вратим измену јер не постоји или је обрисана.',
'undo-summary' => 'Поништена измена $1  {{GENDER:$2|корисника|кориснице}} [[Special:Contributions/$2|$2]] ([[User talk:$2|разговор]])',
'undo-summary-username-hidden' => 'Поништи измену $1 скривеног корисника',

# Account creation failure
'cantcreateaccounttitle' => 'Не могу да отворим налог',
'cantcreateaccount-text' => "Отварање налога с ове IP адресе ('''$1''') је {{GENDER:$3|блокирао|блокирала|блокирао}} [[User:$3|$3]].

Разлог који је навео {{GENDER:$3|корисник|корисница|корисник}} $3 је ''$2''",

# History pages
'viewpagelogs' => 'Погледај дневнике ове странице',
'nohistory' => 'Не постоји историја измена ове странице.',
'currentrev' => 'Текућа измена',
'currentrev-asof' => 'Текућа измена од $2 у $3',
'revisionasof' => 'Измена од $2 у $3',
'revision-info' => 'Измена од $1; $2',
'previousrevision' => '← Старија измена',
'nextrevision' => 'Новија измена →',
'currentrevisionlink' => 'Текућа измена',
'cur' => 'трен',
'next' => 'след',
'last' => 'разл',
'page_first' => 'прва',
'page_last' => 'последња',
'histlegend' => "Избор разлика: изаберите кутијице измена за упоређивање и притисните ентер или дугме на дну.<br />
Објашњење: '''({{int:cur}})''' – разлика с тренутном изменом,
'''({{int:last}})''' – разлика с претходном изменом, '''{{int:minoreditletter}}''' – мала измена",
'history-fieldset-title' => 'Преглед историје',
'history-show-deleted' => 'само обрисано',
'histfirst' => 'најстарије',
'histlast' => 'најновије',
'historysize' => '({{PLURAL:$1|1 бајт|$1 бајта|$1 бајтова}})',
'historyempty' => '(празно)',

# Revision feed
'history-feed-title' => 'Историја измена',
'history-feed-description' => 'Историја измена ове странице на викију',
'history-feed-item-nocomment' => '$1 у $2',
'history-feed-empty' => 'Тражена страница не постоји.
Могуће да је обрисана с викија или је преименована.
Покушајте да [[Special:Search|претражите вики]] за сличне странице.',

# Revision deletion
'rev-deleted-comment' => '(опис измене је уклоњен)',
'rev-deleted-user' => '(корисничко име је уклоњено)',
'rev-deleted-event' => '(историја је уклоњена)',
'rev-deleted-user-contribs' => '[корисничко име или ИП адреса је уклоњена – измена је сакривена са списка доприноса]',
'rev-deleted-text-permission' => "Измена ове странице је '''обрисана'''.
Детаље можете видети у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-deleted-text-unhide' => "Измена ове странице је '''обрисана'''.
Детаље можете видети у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].
Ипак можете да [$1 видите ову измену] ако желите да наставите.",
'rev-suppressed-text-unhide' => "Измена ове странице је '''сакривена'''.
Детаље можете видети у [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} историји сакривања].
Ипак можете да [$1 видите ову измену] ако желите да наставите.",
'rev-deleted-text-view' => "Измена ове странице је '''обрисана'''.
Можете је погледати; више детаља можете наћи у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-suppressed-text-view' => "Измена ове странице је '''сакривена'''.
Можете је погледати; више детаља можете наћи у [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} историји сакривања].",
'rev-deleted-no-diff' => "Не можете видети ову разлику јер је једна од измена '''обрисана'''.
Детаљи се налазе у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-suppressed-no-diff' => "Не можете видети ову разлику јер је једна од измена '''обрисана'''.",
'rev-deleted-unhide-diff' => "Једна од измена у овом прегледу разлика је '''обрисана'''.
Детаљи се налазе у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].
Ипак можете да [$1 видите ову разлику] ако желите да наставите.",
'rev-suppressed-unhide-diff' => "Једна од измена ове разлике је '''сакривена'''.
Детаљи се налазе у [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} историји сакривања].
Ипак можете да [$1 видите ову разлику] ако желите да наставите.",
'rev-deleted-diff-view' => "Једна од измена ове разлике је '''обрисана'''.
Ипак можете да видите ову разлику; више детаља можете наћи у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-suppressed-diff-view' => "Једна од измена ове разлике је '''сакривена'''.
Ипак можете да видите ову разлику; више детаља можете наћи у [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} историји сакривања].",
'rev-delundel' => 'прикажи/сакриј',
'rev-showdeleted' => 'прикажи',
'revisiondelete' => 'Обриши/врати измене',
'revdelete-nooldid-title' => 'Нема тражене измене',
'revdelete-nooldid-text' => 'Нисте навели жељену измену, она не постоји или покушавате да је сакријете.',
'revdelete-nologtype-title' => 'Није наведена врста историје',
'revdelete-nologtype-text' => 'Нисте навели врсту историје над којом желите да извршите ову радњу.',
'revdelete-nologid-title' => 'Неисправан запис у дневнику',
'revdelete-nologid-text' => 'Нисте одредили одредишну историју или наведени унос не постоји.',
'revdelete-no-file' => 'Тражена датотека не постоји.',
'revdelete-show-file-confirm' => 'Желите ли да видите обрисану измену датотеке „<nowiki>$1</nowiki>“ од $2; $3?',
'revdelete-show-file-submit' => 'Да',
'revdelete-selected' => "'''{{PLURAL:$2|Изабрана измена|Изабране измене}} странице '''[[:$1]]''''''",
'logdelete-selected' => "'''{{PLURAL:$1|Изабрана ставка у историји|Изабране ставке у историји}}:'''",
'revdelete-text' => "'''Обрисане измене ће и даље бити приказане у историји страница и записима, али делови њиховог садржаја неће бити доступни јавности.'''
Други администратори на овом викију ће и даље имати приступ сакривеном садржају, а они ће тај садржај моћи да врате путем овог сучеља, осим ако нису постављена додатна ограничења.",
'revdelete-confirm' => 'Потврдите да намеравате ово урадити, да разумете последице и да то чините у складу с [[{{MediaWiki:Policy-url}}|правилима]].',
'revdelete-suppress-text' => "Сакривање измена би требало користити '''само''' у следећим случајевима:
* Злонамерни или погрдни подаци
* Неприкладни лични подаци
*: ''кућна адреса и број телефона, број банковне картице итд.''",
'revdelete-legend' => 'Ограничења видљивости',
'revdelete-hide-text' => 'сакриј текст измене',
'revdelete-hide-image' => 'Сакриј садржај датотеке',
'revdelete-hide-name' => 'Сакриј радњу и одредиште',
'revdelete-hide-comment' => 'сакриј опис измене',
'revdelete-hide-user' => 'сакриј име уређивача',
'revdelete-hide-restricted' => 'Сакриј податке од администратора и других корисника',
'revdelete-radio-same' => '(не мењај)',
'revdelete-radio-set' => 'да',
'revdelete-radio-unset' => 'не',
'revdelete-suppress' => 'Сакриј податке од администратора и других корисника',
'revdelete-unsuppress' => 'Уклони ограничења на враћеним изменама',
'revdelete-log' => 'Разлог:',
'revdelete-submit' => 'Примени на {{PLURAL:$1|изабрану измену|изабране измене}}',
'revdelete-success' => "'''Видљивост измене је ажурирана.'''",
'revdelete-failure' => "'''Не могу да ажурирам видљивост измене:'''
$1",
'logdelete-success' => "'''Видљивост историје је постављена.'''",
'logdelete-failure' => "'''Не могу да поставим видљивост историје:'''
$1",
'revdel-restore' => 'промени видљивост',
'revdel-restore-deleted' => 'обрисане измене',
'revdel-restore-visible' => 'видљиве измене',
'pagehist' => 'Историја странице',
'deletedhist' => 'Обрисана историја',
'revdelete-hide-current' => 'Грешка при сакривању ставке од $1, $2: ово је тренутна измена.
Не може бити сакривена.',
'revdelete-show-no-access' => 'Грешка при приказивању ставке од $1, $2: означена је као „ограничена“.
Немате приступ до ње.',
'revdelete-modify-no-access' => 'Грешка при мењању ставке од $1, $2: означена је као „ограничена“.
Немате приступ до ње.',
'revdelete-modify-missing' => 'Грешка при мењању ИБ ставке $1: она не постоји у бази података.',
'revdelete-no-change' => "'''Упозорење:''' ставка од $1, $2 већ поседује затражене поставке видљивости.",
'revdelete-concurrent-change' => 'Грешка при мењању ставке од $1, $2: њено стање је у међувремену промењено од стране другог корисника.
Погледајте историју.',
'revdelete-only-restricted' => 'Грешка при сакривању ставке од $1, $2: не можете сакрити ставке од администратора без избора других могућности видљивости.',
'revdelete-reason-dropdown' => '*Уобичајени разлози за брисање
** Кршење ауторског права
** Неприкладан коментар или лични подаци
** Неприкладно корисничко име
** Увредљиви подаци',
'revdelete-otherreason' => 'Други/додатни разлог:',
'revdelete-reasonotherlist' => 'Други разлог',
'revdelete-edit-reasonlist' => 'Уреди разлоге за брисање',
'revdelete-offender' => 'Аутор измене:',

# Suppression log
'suppressionlog' => 'Историја сакривања',
'suppressionlogtext' => 'Испод се налази списак брисања и блокирања који укључује садржај сакривен од администратора. Текуће забране и блокирања можете наћи [[Special:BlockList|овде]].',

# History merging
'mergehistory' => 'Споји историје страница',
'mergehistory-header' => 'Ова страница вам омогућава да спојите измене неке изворне странице у нову страницу.
Запамтите да ће ова измена оставити непромењен садржај историје странице.',
'mergehistory-box' => 'Споји измене две странице:',
'mergehistory-from' => 'Изворна страница:',
'mergehistory-into' => 'Одредишна страница:',
'mergehistory-list' => 'Спојива историја измена',
'mergehistory-merge' => 'Следеће измене странице [[:$1]] могу се спојити са [[:$2]].
Користите дугмиће у колони да бисте спојили измене које су направљене пре наведеног времена.
Коришћење навигационих веза ће поништити ову колону.',
'mergehistory-go' => 'Прикажи измене које се могу спојити',
'mergehistory-submit' => 'Споји измене',
'mergehistory-empty' => 'Нема измена за спајање.',
'mergehistory-success' => '$3 {{PLURAL:$3|измена странице [[:$1]] је спојена|измене странице [[:$1]] су спојене|измена странице [[:$1]] је спојено}} у [[:$2]].',
'mergehistory-fail' => 'Не могу да спојим историје. Проверите страницу и временске параметре.',
'mergehistory-no-source' => 'Изворна страница $1 не постоји.',
'mergehistory-no-destination' => 'Одредишна страница $1 не постоји.',
'mergehistory-invalid-source' => 'Изворна страница мора имати исправан наслов.',
'mergehistory-invalid-destination' => 'Одредишна страница мора имати исправан наслов.',
'mergehistory-autocomment' => 'Страница [[:$1]] је спојена у [[:$2]]',
'mergehistory-comment' => 'Страница [[:$1]] је спојена у [[:$2]]: $3',
'mergehistory-same-destination' => 'Изворна и одредишна страница не могу бити исте',
'mergehistory-reason' => 'Разлог:',

# Merge log
'mergelog' => 'Дневник спајања',
'pagemerge-logentry' => 'страница [[$1]] је спојена у [[$2]] (све до измене $3)',
'revertmerge' => 'растави',
'mergelogpagetext' => 'Испод се налази списак скорашњих спајања историја страница.',

# Diffs
'history-title' => 'Историја измена странице „$1“',
'difference-title' => 'Разлика између измена на страници „$1“',
'difference-title-multipage' => 'Разлика између страница „$1“ и „$2“',
'difference-multipage' => '(разлике између страница)',
'lineno' => 'Ред $1:',
'compareselectedversions' => 'Упореди изабране измене',
'showhideselectedversions' => 'Прикажи/сакриј изабране измене',
'editundo' => 'поништи',
'diff-empty' => '(Нема разлике)',
'diff-multi' => '({{PLURAL:$1|није приказана међуизмена|нису приказане $1 међуизмене|није приказано $1 међуизмена}} {{PLURAL:$2|једног|$2|$2}} корисника)',
'diff-multi-manyusers' => '({{PLURAL:$1|Није приказана међуизмена|Нису приказане $1 међуизмене|Није приказано $1 међуизмена}} од више од $2 корисника)',
'difference-missing-revision' => 'Не могу да пронађем {{PLURAL:$2|једну измену|$2 измене|$2 измена}} од ове разлике ($1).

Ово се обично дешава када пратите застарелу везу до странице која је обрисана.
Више информација можете пронаћи у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} дневнику брисања].',

# Search results
'searchresults' => 'Резултати претраге',
'searchresults-title' => 'Резултати претраге за „$1“',
'searchresulttext' => 'За више информација о претраживању пројекта {{SITENAME}} погледајте [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => "Тражили сте '''[[:$1]]''' ([[Special:Prefixindex/$1|све странице које почињу са „$1“]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|све странице које воде до „$1“]])",
'searchsubtitleinvalid' => "Тражили сте '''$1'''",
'toomanymatches' => 'Пронађено је превише резултата. Измените упит.',
'titlematches' => 'Наслов странице одговара',
'notitlematches' => 'Ниједан наслов странице не одговара',
'textmatches' => 'Текст странице одговара',
'notextmatches' => 'Ниједан текст странице не одговара',
'prevn' => 'претходних {{PLURAL:$1|$1}}',
'nextn' => 'следећих {{PLURAL:$1|$1}}',
'prevn-title' => '$1 {{PLURAL:$1|претходни  резултат|претходна резултата|претходних резултата}}',
'nextn-title' => '$1 {{PLURAL:$1|следећи резултат|следећа резултата|следећих резултата}}',
'shown-title' => 'Прикажи $1 {{PLURAL:$1|резултат|резултата|резултата}} по страници',
'viewprevnext' => 'Погледај ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Поставке претраге',
'searchmenu-exists' => "'''Постоји и чланак под називом „[[:$1]]“.'''",
'searchmenu-new' => "'''Направите страницу „[[:$1]]“.'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Прегледај странице с овим префиксом]]',
'searchprofile-articles' => 'Чланци',
'searchprofile-project' => 'Странице помоћи и пројеката',
'searchprofile-images' => 'Датотеке',
'searchprofile-everything' => 'Све',
'searchprofile-advanced' => 'Напредно',
'searchprofile-articles-tooltip' => 'Претражите: $1',
'searchprofile-project-tooltip' => 'Претражите: $1',
'searchprofile-images-tooltip' => 'Претражите датотеке',
'searchprofile-everything-tooltip' => 'Претражите сав садржај (укључујући странице за разговор)',
'searchprofile-advanced-tooltip' => 'Претражите прилагођене именске просторе',
'search-result-size' => '$1 ({{PLURAL:$2|1 реч|$2 речи|$2 речи}})',
'search-result-category-size' => '{{PLURAL:$1|1 члан|$1 члана|$1 чланова}}, ({{PLURAL:$2|1 поткатегорија|$2 поткатегорије|$2 поткатегорија}}, {{PLURAL:$3|1 датотека|$3 датотеке|$3 датотека}})',
'search-result-score' => 'Релевантност: $1%',
'search-redirect' => '(преусмерење $1)',
'search-section' => '(одељак $1)',
'search-suggest' => 'Да ли сте мислили на: $1',
'search-interwiki-caption' => 'Братски пројекти',
'search-interwiki-default' => '$1 резултати:',
'search-interwiki-more' => '(више)',
'search-relatedarticle' => 'Повезано',
'mwsuggest-disable' => 'Онемогући предлоге при претраживању',
'searcheverything-enable' => 'сви именски простори',
'searchrelated' => 'повезано',
'searchall' => 'све',
'showingresults' => "Испод {{PLURAL:$1|је приказан '''1''' резултат|су приказана '''$1''' резултата|је приказано '''$1''' резултата}} почев од броја '''$2'''.",
'showingresultsnum' => "Испод {{PLURAL:$3|је приказан '''1''' резултат|су приказана '''$3''' резултата|је приказано '''$3''' резултата}} почев од броја '''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Резултат '''$1''' од '''$3'''|Резултата '''$1 – $2''' од '''$3'''}} за '''$4'''",
'nonefound' => "'''Напомена''': само се неки именски простори претражују по подразумеваним поставкама.
Ако желите све да претражите, додајте префикс '''all:''' испред траженог садржаја (ово укључује странице за разговор, шаблоне итд.) или користите префикс жељеног именског простора.",
'search-nonefound' => 'Нема поклапања.',
'powersearch' => 'Претражи',
'powersearch-legend' => 'Напредна претрага',
'powersearch-ns' => 'Тражи у следећим именским просторима:',
'powersearch-redir' => 'Списак преусмерења',
'powersearch-field' => 'Тражи',
'powersearch-togglelabel' => 'Изабери:',
'powersearch-toggleall' => 'све',
'powersearch-togglenone' => 'ништа',
'search-external' => 'Спољна претрага',
'searchdisabled' => 'Претрага је онемогућена.
У међувремену можете тражити преко Гугла.
Упамтите да његови пописи овог викија могу бити застарели.',

# Preferences page
'preferences' => 'Подешавања',
'mypreferences' => 'Подешавања',
'prefs-edits' => 'Број измена:',
'prefsnologin' => 'Нисте пријављени',
'prefsnologintext' => 'Морате бити <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} пријављени]</span> да бисте подешавали корисничке поставке.',
'changepassword' => 'Промени лозинку',
'prefs-skin' => 'Тема',
'skin-preview' => 'Прегледај',
'datedefault' => 'Свеједно',
'prefs-beta' => 'Бета могућности',
'prefs-datetime' => 'Датум и време',
'prefs-labs' => 'Пробне могућности',
'prefs-user-pages' => 'Корисничке странице',
'prefs-personal' => 'Профил',
'prefs-rc' => 'Скорашње измене',
'prefs-watchlist' => 'Списак надгледања',
'prefs-watchlist-days' => 'Број дана у списку надгледања:',
'prefs-watchlist-days-max' => 'Највише $1 {{PLURAL:$1|дан|дана|дана}}',
'prefs-watchlist-edits' => 'Највећи број измена у проширеном списку надгледања:',
'prefs-watchlist-edits-max' => 'Највећа вредност је хиљаду',
'prefs-watchlist-token' => 'Жетон списка надгледања:',
'prefs-misc' => 'Друга подешавања',
'prefs-resetpass' => 'Промени лозинку',
'prefs-changeemail' => 'Промени е-адресу',
'prefs-setemail' => 'Постави е-адресу',
'prefs-email' => 'Поставке е-поште',
'prefs-rendering' => 'Изглед',
'saveprefs' => 'Сачувај',
'resetprefs' => 'Очисти несачуване измене',
'restoreprefs' => 'Врати све на подразумевано (у свим одељцима)',
'prefs-editing' => 'Уређивање',
'rows' => 'Редова:',
'columns' => 'Колоне:',
'searchresultshead' => 'Претрага',
'resultsperpage' => 'Погодака по страници:',
'stub-threshold' => 'Праг за обликовање <a href="#" class="stub">везе као клице</a> (у бајтовима):',
'stub-threshold-disabled' => 'Онемогућено',
'recentchangesdays' => 'Број дана у скорашњим изменама:',
'recentchangesdays-max' => '(највише $1 {{PLURAL:$1|дан|дана|дана}})',
'recentchangescount' => 'Број измена за приказ:',
'prefs-help-recentchangescount' => 'Подразумева скорашње измене, историје страница и дневнике.',
'savedprefs' => 'Ваша подешавања су сачувана.',
'timezonelegend' => 'Временска зона:',
'localtime' => 'Локално време:',
'timezoneuseserverdefault' => 'подразумеване вредности ($1)',
'timezoneuseoffset' => 'друго (унесите одступање)',
'timezoneoffset' => 'Одступање¹:',
'servertime' => 'Време на серверу:',
'guesstimezone' => 'попуни из прегледача',
'timezoneregion-africa' => 'Африка',
'timezoneregion-america' => 'Америка',
'timezoneregion-antarctica' => 'Антарктик',
'timezoneregion-arctic' => 'Арктик',
'timezoneregion-asia' => 'Азија',
'timezoneregion-atlantic' => 'Атлантски океан',
'timezoneregion-australia' => 'Аустралија',
'timezoneregion-europe' => 'Европа',
'timezoneregion-indian' => 'Индијски океан',
'timezoneregion-pacific' => 'Тихи океан',
'allowemail' => 'Омогући примање е-порука од других корисника',
'prefs-searchoptions' => 'Претрага',
'prefs-namespaces' => 'Именски простори',
'defaultns' => 'Ако није наведено другачије, тражи у овим именским просторима:',
'default' => 'подразумевано',
'prefs-files' => 'Датотеке',
'prefs-custom-css' => 'Прилагођени CSS',
'prefs-custom-js' => 'Прилагођени јаваскрипт',
'prefs-common-css-js' => 'Дељени CSS/јаваскрипт за све теме:',
'prefs-reset-intro' => 'Можете користити ову страницу да поништите своје поставке на подразумеване вредности.
Ова радња се не може вратити.',
'prefs-emailconfirm-label' => 'Потврда е-адресе:',
'youremail' => 'Е-адреса:',
'username' => '{{GENDER:$1|Корисничко име}}:',
'uid' => '{{GENDER:$1|Кориснички}} ID:',
'prefs-memberingroups' => '{{GENDER:$2|Корисник|Корисница}} је члан {{PLURAL:$1|групе|група}}:',
'prefs-memberingroups-type' => '$1',
'prefs-registration' => 'Време уписа:',
'prefs-registration-date-time' => '$1',
'yourrealname' => 'Право име:',
'yourlanguage' => 'Језик:',
'yourvariant' => 'Варијанта језика:',
'prefs-help-variant' => 'Жељена варијанта или правопис за приказ страница са садржајем овог викија.',
'yournick' => 'Нови потпис:',
'prefs-help-signature' => "Коментаре на страницама за разговор потпишите са ''<nowiki>~~~~</nowiki>''. Ови знакови ће бити претворени у ваш потпис и тренутно време.",
'badsig' => 'Потпис је неисправан.
Проверите ознаке HTML.',
'badsiglength' => 'Ваш потпис је предугачак.
Не сме бити дужи од $1 {{PLURAL:$1|знака|знака|знакова}}.',
'yourgender' => 'Како желите да се представите?',
'gender-unknown' => 'Не желим да се представим',
'gender-male' => 'Он уређује вики странице',
'gender-female' => 'Она уређује вики странице',
'prefs-help-gender' => 'Необавезно: користи се за исправно обраћање софтвера корисницима, зависно од њиховог пола.
Овај податак ће бити јаван.',
'email' => 'Е-адреса',
'prefs-help-realname' => 'Право име није обавезно.
Ако изаберете да га унесете, оно ће бити коришћено за приписивање вашег рада.',
'prefs-help-email' => 'Е-адреса није обавезна, али је потребна у случају да заборавите лозинку.',
'prefs-help-email-others' => 'Можете је користити и да омогућите другима да вас контактирају преко корисничке странице или странице за разговор, без откривања свог идентитета.',
'prefs-help-email-required' => 'Потребна је е-адреса.',
'prefs-info' => 'Основни подаци',
'prefs-i18n' => 'Интернационализација',
'prefs-signature' => 'Потпис',
'prefs-dateformat' => 'Формат датума',
'prefs-timeoffset' => 'Временска разлика',
'prefs-advancedediting' => 'Главна подешавања',
'prefs-editor' => 'Уређивач',
'prefs-preview' => 'Претпреглед',
'prefs-advancedrc' => 'Напредне поставке',
'prefs-advancedrendering' => 'Напредне поставке',
'prefs-advancedsearchoptions' => 'Напредне поставке',
'prefs-advancedwatchlist' => 'Напредне поставке',
'prefs-displayrc' => 'Поставке приказа',
'prefs-displaysearchoptions' => 'Поставке приказа',
'prefs-displaywatchlist' => 'Поставке приказа',
'prefs-diffs' => 'Разлике',
'prefs-help-prefershttps' => 'Ова подешавања ће ступити на снагу при следећој пријави.',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'Е-адреса је исправна',
'email-address-validity-invalid' => 'Унесите исправну е-адресу',

# User rights
'userrights' => 'Управљање корисничким правима',
'userrights-lookup-user' => 'Управљање корисничким групама',
'userrights-user-editname' => 'Унесите корисничко име:',
'editusergroup' => 'Промени корисничке групе',
'editinguser' => "Мењате корисничка права {{GENDER:$1|корисника|кориснице|корисника}} '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Промена корисничких група',
'saveusergroups' => 'Сачувај корисничке групе',
'userrights-groupsmember' => 'Члан:',
'userrights-groupsmember-auto' => 'Укључени члан од:',
'userrights-groups-help' => 'Можете променити групе којима овај корисник припада.
* Означена кућица означава да се корисник налази у тој групи.
* Неозначена кућица означава да се корисник не налази у тој групи.
* Звездица означава да не можете уклонити групу ако је додате и обратно.',
'userrights-reason' => 'Разлог:',
'userrights-no-interwiki' => 'Немате овлашћења да мењате корисничка права на другим викијима.',
'userrights-nodatabase' => 'База података $1 не постоји или није локална.',
'userrights-nologin' => 'Морате се [[Special:UserLogin|пријавити]] с администраторским налогом да бисте додали корисничка права.',
'userrights-notallowed' => 'Немате овлашћења да додајете или уклањате корисничка права.',
'userrights-changeable-col' => 'Групе које можете да промените',
'userrights-unchangeable-col' => 'Групе које не можете да промените',
'userrights-irreversible-marker' => '$1*',

# Groups
'group' => 'Група:',
'group-user' => 'Корисници',
'group-autoconfirmed' => 'Аутоматски потврђени корисници',
'group-bot' => 'Ботови',
'group-sysop' => 'Администратори',
'group-bureaucrat' => 'Бирократе',
'group-suppress' => 'Ревизори',
'group-all' => '(све)',

'group-user-member' => '{{GENDER:$1|корисник|корисница|корисник}}',
'group-autoconfirmed-member' => '{{GENDER:$1|аутоматски потврђен корисник|аутоматски потврђена корисница|аутоматски потврђен корисник}}',
'group-bot-member' => '{{GENDER:$1|бот}}',
'group-sysop-member' => '{{GENDER:$1|администратор|администраторка|администратор}}',
'group-bureaucrat-member' => '{{GENDER:$1|бирократа|бирократкиња|бирократа}}',
'group-suppress-member' => '{{GENDER:$1|ревизор|ревизорка|ревизор}}',

'grouppage-user' => '{{ns:project}}:Корисници',
'grouppage-autoconfirmed' => '{{ns:project}}:Аутоматски потврђени корисници',
'grouppage-bot' => '{{ns:project}}:Ботови',
'grouppage-sysop' => '{{ns:project}}:Администратори',
'grouppage-bureaucrat' => '{{ns:project}}:Бирократе',
'grouppage-suppress' => '{{ns:project}}:Ревизор',

# Rights
'right-read' => 'прегледање страница',
'right-edit' => 'уређивање страница',
'right-createpage' => 'прављење страница (изузев страница за разговор)',
'right-createtalk' => 'прављење страница за разговор',
'right-createaccount' => 'отварање нових корисничких налога',
'right-minoredit' => 'означавање измена као мање',
'right-move' => 'премештање страница',
'right-move-subpages' => 'премештање страница с њиховим подстраницама',
'right-move-rootuserpages' => 'премештање основних корисничких страница',
'right-movefile' => 'премештање датотека',
'right-suppressredirect' => 'прескакање стварања преусмерења при премештању страница',
'right-upload' => 'отпремање датотека',
'right-reupload' => 'замењивање постојећих датотека',
'right-reupload-own' => 'замењивање сопствених датотека',
'right-reupload-shared' => 'мењање датотека на дељеном складишту мултимедије',
'right-upload_by_url' => 'отпремање датотека са веб адресе',
'right-purge' => 'чишћење кеш меморије странице без потврде',
'right-autoconfirmed' => 'Not be affected by IP-based rate limits',
'right-bot' => 'сматрање измена као аутоматски процес',
'right-nominornewtalk' => 'непоседовање малих измена на страницама за разговор отвара прозор за нове поруке',
'right-apihighlimits' => 'коришћење виших граница за упите из АПИ-ја',
'right-writeapi' => 'писање АПИ-ја',
'right-delete' => 'брисање страница',
'right-bigdelete' => 'брисање страница с великом историјом',
'right-deletelogentry' => 'Брисање и враћање одређених ставки у дневнику',
'right-deleterevision' => 'брисање и враћање одређених измена страница',
'right-deletedhistory' => 'прегледање обрисаних ставки историје без повезаног текста',
'right-deletedtext' => 'прегледање обрисаног текста и измена између обрисаних измена',
'right-browsearchive' => 'тражење обрисаних страница',
'right-undelete' => 'враћање обрисаних страница',
'right-suppressrevision' => 'прегледање и враћање измена које су сакривене од стране администратора',
'right-suppressionlog' => 'гледање приватних дневника',
'right-block' => 'блокирање даљих измена других корисника',
'right-blockemail' => 'онемогућавање корисницима да шаљу е-поруке',
'right-hideuser' => 'блокирање корисничког имена и његово сакривање од јавности',
'right-ipblock-exempt' => 'заобилажење блокирања IP адресе, самоблокирања и блокирања опсега',
'right-proxyunbannable' => 'заобилажење самоблокирања посредника',
'right-unblockself' => 'Одблокирај самог себе',
'right-protect' => 'Промени нивое заштите и уреди каскадно заштићене странице',
'right-editprotected' => 'Уреди странице под заштитом „{{int:protect-level-sysop}}“',
'right-editsemiprotected' => 'Уреди странице под заштитом „{{int:protect-level-autoconfirmed}}“',
'right-editinterface' => 'уређивање корисничког окружења',
'right-editusercssjs' => 'уређивање туђих CSS и јаваскрипт датотека',
'right-editusercss' => 'уређивање туђих CSS датотека',
'right-edituserjs' => 'уређивање туђих јаваскрипт датотека',
'right-viewmyprivateinfo' => 'Видите своје личне податке (нпр. адресу е-поште, право име)',
'right-editmyprivateinfo' => 'Уреди своје личне податке (нпр. адресу е-поште, право име)',
'right-editmyoptions' => 'Уредите своја подешавања',
'right-rollback' => 'брзо враћање измена последњег корисника који је мењао одређену страницу',
'right-markbotedits' => 'означавање враћених измена као измене бота',
'right-noratelimit' => 'отпорност на ограничења',
'right-import' => 'увожење страница из других викија',
'right-importupload' => 'увожење страница из отпремљене датотеке',
'right-patrol' => 'означавање туђих измена као прегледаних',
'right-autopatrol' => 'самоозначавање измена као прегледане',
'right-patrolmarks' => 'прегледање ознака за патролирање унутар скорашњих измена',
'right-unwatchedpages' => 'прегледање списка ненадгледаних страница',
'right-mergehistory' => 'спајање историја страница',
'right-userrights' => 'уређивање свих корисничких права',
'right-userrights-interwiki' => 'уређивање корисничких права на другим викијима',
'right-siteadmin' => 'закључавање и откључавање базе података',
'right-override-export-depth' => 'извоз страница укључујући и повазене странице до дубине од пет веза',
'right-sendemail' => 'слање е-порука другим корисницима',
'right-passwordreset' => 'прегледање порука за обнављање лозинке',

# Special:Log/newusers
'newuserlogpage' => 'Дневник нових корисника',
'newuserlogpagetext' => 'Ово је историја нових корисника.',

# User rights log
'rightslog' => 'Дневник корисничких права',
'rightslogtext' => 'Ово је дневник измена корисничких права.',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'читање ове странице',
'action-edit' => 'уређивање ове странице',
'action-createpage' => 'прављење страница',
'action-createtalk' => 'прављење страница за разговор',
'action-createaccount' => 'отварање овог корисничког налога',
'action-minoredit' => 'означавање ове измене као мање',
'action-move' => 'премештање ове странице',
'action-move-subpages' => 'премештање ове странице и њених подстраница',
'action-move-rootuserpages' => 'премештање основних корисничких страница',
'action-movefile' => 'премештање ове датотеке',
'action-upload' => 'слање ове датотеке',
'action-reupload' => 'замењивање постојеће датотеке',
'action-reupload-shared' => 'постављање ове датотеке на заједничко складиште',
'action-upload_by_url' => 'слање ове датотеке преко URL адресе',
'action-writeapi' => 'писање АПИ-ја',
'action-delete' => 'брисање ове странице',
'action-deleterevision' => 'брисање ове измене',
'action-deletedhistory' => 'прегледање обрисане историје ове странице',
'action-browsearchive' => 'претраживање обрисаних страница',
'action-undelete' => 'враћање ове странице',
'action-suppressrevision' => 'прегледање и враћање ове сакривене измене',
'action-suppressionlog' => 'прегледање ове приватне историје',
'action-block' => 'блокирање даљих измена овог корисника',
'action-protect' => 'мењање степена заштите ове странице',
'action-rollback' => 'брзо враћање измена последњег корисника који је мењао одређену страницу',
'action-import' => 'увоз ове странице с другог викија',
'action-importupload' => 'увоз ове странице слањем датотеке',
'action-patrol' => 'означавање туђих измена прегледаним',
'action-autopatrol' => 'самоозначавање измена прегледаним',
'action-unwatchedpages' => 'прегледање списка ненадгледаних страница',
'action-mergehistory' => 'спајање историје ове странице',
'action-userrights' => 'уређивање свих корисничких права',
'action-userrights-interwiki' => 'уређивање корисничких права на другим викијима',
'action-siteadmin' => 'закључавање или откључавање базе података',
'action-sendemail' => 'слање е-порука',
'action-editmywatchlist' => 'измени свој списак надгледања',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|измена|измене|измена}}',
'enhancedrc-since-last-visit' => '$1 {{PLURAL:$1|измена од ваше последње посете}}',
'enhancedrc-history' => 'историја',
'recentchanges' => 'Скорашње измене',
'recentchanges-legend' => 'Поставке скорашњих измена',
'recentchanges-summary' => 'Пратите скорашње измене на овој страници.',
'recentchanges-feed-description' => 'Пратите скорашње измене уз помоћ овог довода.',
'recentchanges-label-newpage' => 'Нова страница',
'recentchanges-label-minor' => 'Мања измена',
'recentchanges-label-bot' => 'Ову измену је направио бот',
'recentchanges-label-unpatrolled' => 'Ова измена још није прегледана',
'rcnote' => "Испод {{PLURAL:$1|је '''1''' измена|су последње '''$1''' измене|су последњих '''$1''' измена}} {{PLURAL:$2|претходни дан|у последњa '''$2''' дана|у последњих '''$2''' дана}}, од $4; $5.",
'rcnotefrom' => 'Испод су измене од <b>$3; $4</b> (до <b>$1</b> измена).',
'rclistfrom' => 'Прикажи нове измене почев од $1',
'rcshowhideminor' => '$1 мање измене',
'rcshowhidebots' => '$1 ботове',
'rcshowhideliu' => '$1 пријављене кориснике',
'rcshowhideanons' => '$1 анонимне кориснике',
'rcshowhidepatr' => '$1 патролиране измене',
'rcshowhidemine' => '$1 моје измене',
'rclinks' => 'Прикажи последњих $1 измена {{PLURAL:$2|претходни дан|у последња $2 дана|у последњих $2 дана}}<br />$3',
'diff' => 'разл',
'hist' => 'ист',
'hide' => 'сакриј',
'show' => 'прикажи',
'minoreditletter' => ' м',
'newpageletter' => 'Н',
'boteditletter' => 'б',
'unpatrolledletter' => '!',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|корисник надгледа|корисника надгледају|корисника надгледају}}]',
'rc_categories' => 'Ограничи на категорије (раздвоји с усправном цртом)',
'rc_categories_any' => 'Све',
'rc-change-size' => '$1',
'rc-change-size-new' => '$1 {{PLURAL:$1|бајт|бајта|бајтова}} после измене',
'newsectionsummary' => '/* $1 */ нови одељак',
'rc-enhanced-expand' => 'Прикажи детаље',
'rc-enhanced-hide' => 'Сакриј детаље',
'rc-old-title' => 'првобитно направљено као „$1“',

# Recent changes linked
'recentchangeslinked' => 'Сродне измене',
'recentchangeslinked-feed' => 'Сродне измене',
'recentchangeslinked-toolbox' => 'Сродне измене',
'recentchangeslinked-title' => 'Сродне измене са „$1“',
'recentchangeslinked-summary' => "Ова посебна страница приказује списак последњих измена на страницама које су повезане (или чланови одређене категорије).
Странице с [[Special:Watchlist|вашег списка надгледања]] су '''подебљане'''.",
'recentchangeslinked-page' => 'Назив странице:',
'recentchangeslinked-to' => 'Прикажи измене страница које су повезане с датом страницом',

# Upload
'upload' => 'Пошаљи датотеку',
'uploadbtn' => 'Пошаљи датотеку',
'reuploaddesc' => 'Назад на образац за отпремање',
'upload-tryagain' => 'Пошаљи измењени опис датотеке',
'uploadnologin' => 'Нисте пријављени',
'uploadnologintext' => 'Морате бити $1 да бисте отпремали датотеке.',
'upload_directory_missing' => 'Фасцикла за слање ($1) недостаје и сервер је не може направити.',
'upload_directory_read_only' => 'Сервер не може да пише по фасцикли за слање ($1).',
'uploaderror' => 'Грешка при отпремању',
'upload-recreate-warning' => "'''Упозорење: датотека с тим називом је обрисана или премештена.'''

Историја брисања и премештања се налази испод:",
'uploadtext' => "Користите образац испод да бисте отпремили датотеке.
За преглед или претрагу постојећих датотека, погледајте [[Special:FileList|списак отпремљених датотека]], поновна отпремања су наведена у [[Special:Log/upload|дневнику отпремања]], а брисања у [[Special:Log/delete|дневнику брисања]].

Датотеку додајете на жељену страницу користећи следеће обрасце:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Слика.jpg]]</nowiki></code>''' за верзију слике у пуној величини
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Слика.png|200п|мини|лево|опис]]</nowiki></code>''' за верзију слике с величином од 200 пиксела која је приказана у засебном оквиру, заједно с описом.
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Датотека.ogg]]</nowiki></code>''' за директно повезивање с датотеком без њеног приказивања",
'upload-permitted' => 'Дозвољене врсте датотека: $1.',
'upload-preferred' => 'Пожељне врсте датотека: $1.',
'upload-prohibited' => 'Забрањене врсте датотека: $1.',
'uploadlog' => 'дневник отпремања',
'uploadlogpage' => 'Дневник отпремања',
'uploadlogpagetext' => 'Испод је списак скорашњих слања.
Погледајте [[Special:NewFiles|галерију нових датотека]] за лепши преглед.',
'filename' => 'Назив датотеке',
'filedesc' => 'Опис',
'fileuploadsummary' => 'Опис:',
'filereuploadsummary' => 'Измене датотеке:',
'filestatus' => 'Статус ауторског права:',
'filesource' => 'Извор:',
'uploadedfiles' => 'Послате датотеке',
'ignorewarning' => 'Занемари упозорења и сачувај датотеку',
'ignorewarnings' => 'Занемари сва упозорења',
'minlength1' => 'Назив датотеке мора имати барем један знак.',
'illegalfilename' => 'Датотека „$1“ садржи знакове који нису дозвољени у називима страница.
Промените назив датотеке и поново је пошаљите.',
'filename-toolong' => 'Називи датотека могу имати највише 240 бајтова.',
'badfilename' => 'Назив датотеке је промењен у „$1“.',
'filetype-mime-mismatch' => 'Екстензија „.$1“ не одговара препознатој врсти MIME датотеке ($2).',
'filetype-badmime' => 'Датотеке MIME врсте „$1“ није дозвољено слати.',
'filetype-bad-ie-mime' => 'Ова датотека се не може послати зато што би је Интернет експлорер уочио као „$1“, а то је забрањена и опасна врста датотеке.',
'filetype-unwanted-type' => '„.$1“ је непожељна врста датотеке.
{{PLURAL:$3|Пожељна врста датотеке је|Пожељне врсте датотека су}} $2.',
'filetype-banned-type' => '\'\'\'".$1"\'\'\' {{PLURAL:$4|је забрањена врста датотеке|су забрањене врсте датотека}}.
{{PLURAL:$3|Дозвољена врста датотеке је|Дозвољене врсте датотека су}} $2.',
'filetype-missing' => 'Ова датотека нема екстензију.',
'empty-file' => 'Послата датотека је празна.',
'file-too-large' => 'Послата датотека је превелика.',
'filename-tooshort' => 'Назив датотеке је прекратак.',
'filetype-banned' => 'Врста датотеке је забрањена.',
'verification-error' => 'Ова датотека није прошла проверу.',
'hookaborted' => 'Измена је одбачена од куке за проширења.',
'illegal-filename' => 'Назив датотеке је забрањен.',
'overwrite' => 'Замењивање постојеће датотеке је забрањено.',
'unknown-error' => 'Дошло је до непознате грешке.',
'tmp-create-error' => 'Не могу да направим привремену датотеку.',
'tmp-write-error' => 'Грешка при писању привремене датотеке.',
'large-file' => 'Препоручљиво је да датотеке не буду веће од $1; ова датотека је $2.',
'largefileserver' => 'Ова датотека прелази ограничење величине.',
'emptyfile' => 'Датотека коју сте послали је празна.
Узрок може бити грешка у називу датотеке.
Проверите да ли заиста желите да је пошаљете.',
'windows-nonascii-filename' => 'Овај вики не подржава називе датотека с посебним знацима.',
'fileexists' => 'Датотека с овим називом већ постоји. Погледајте <strong>[[:$1]]</strong> ако нисте сигурни да ли желите да је промените.
[[$1|thumb]]',
'filepageexists' => 'Страница с описом ове датотеке је већ направљена овде <strong>[[:$1]]</strong>, иако датотека не постоји.
Опис који сте навели се неће појавити на страници с описом.
Да би се ваш опис овде нашао, потребно је да га ручно измените.
[[$1|thumb]]',
'fileexists-extension' => 'Датотека са сличним називом већ постоји: [[$2|thumb]]
* Назив датотеке коју шаљете: <strong>[[:$1]]</strong>
* Назив постојеће датотеке: <strong>[[:$2]]</strong>
Изаберите другачији назив.',
'fileexists-thumbnail-yes' => "Изгледа да је датотека умањено издање слике ''(thumbnail)''.
[[$1|thumb]]
Проверите датотеку <strong>[[:$1]]</strong>.
Ако је проверена датотека иста слика оригиналне величине, није потребно слати додатну слику.",
'file-thumbnail-no' => "Датотека почиње са <strong>$1</strong>.
Изгледа да се ради о умањеној слици ''(thumbnail)''.
Уколико имате ову слику у пуној величини, пошаљите је, а ако немате, промените назив датотеке.",
'fileexists-forbidden' => 'Датотека с овим називом већ постоји и не може се заменити.
Ако и даље желите да пошаљете датотеку, вратите се и изаберите други назив.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Датотека с овим називом већ постоји у заједничком складишту.
Вратите се и пошаљите датотеку с другим називом.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Ово је дупликат {{PLURAL:$1|следеће датотеке|следећих датотека}}:',
'file-deleted-duplicate' => 'Датотека истоветна овој ([[:$1]]) је претходно обрисана.
Погледајте историју брисања пре поновног слања.',
'uploadwarning' => 'Упозорење при отпремању',
'uploadwarning-text' => 'Измените опис датотеке и покушајте поново.',
'savefile' => 'Сачувај датотеку',
'uploadedimage' => '{{GENDER:|је послао|је послала|је послао}} „[[$1]]“',
'overwroteimage' => '{{GENDER:|је послао|је послала}} нову верзију датотеке „[[$1]]“',
'uploaddisabled' => 'Отпремање је онемогућено.',
'copyuploaddisabled' => 'Слање путем URL адресе је онемогућено.',
'uploadfromurl-queued' => 'Слање је стављено на списак чекања.',
'uploaddisabledtext' => 'Отпремање датотека је онемогућено.',
'php-uploaddisabledtext' => 'Слање датотека је онемогућено у PHP-у.
Проверите поставке file_uploads.',
'uploadscripted' => 'Датотека садржи HTML или скриптни код који може бити погрешно протумачен од стране прегледача.',
'uploadvirus' => 'Датотека садржи вирус!
Детаљи: $1',
'uploadjava' => 'Датотека је формата ZIP који садржи јава .class елемент.
Слање јава датотека није дозвољено јер оне могу изазвати заобилажење сигурносних ограничења.',
'upload-source' => 'Изворна датотека',
'sourcefilename' => 'Назив изворне датотеке:',
'sourceurl' => 'Адреса извора:',
'destfilename' => 'Назив:',
'upload-maxfilesize' => 'Највећа величина датотеке: $1',
'upload-description' => 'Опис датотеке',
'upload-options' => 'Поставке слања',
'watchthisupload' => 'Надгледај ову датотеку',
'filewasdeleted' => 'Датотека с овим називом је раније послата, али је обрисана.
Проверите $1 пре него што наставите с поновним слањем.',
'filename-bad-prefix' => "Назив датотеке коју шаљете почиње са '''\"\$1\"''', а њега обично додељују дигитални фотоапарати.
Изаберите назив датотеке који описује њен садржај.",
'filename-prefix-blacklist' => ' #<!-- оставите овај ред онаквим какав јесте --> <pre>
# Синтакса је следећа:
#   * Све од тарабе па до краја реда је коментар
#   * Сваки ред означава префикс типичних назива датотека које додељивају дигитални апарати
CIMG # Касио
DSC_ # Никон
DSCF # Фуџи
DSCN # Никон
DUW # неки мобилни телефони
IMG # опште
JD # Џеноптик
MGP # Пентакс
PICT # разно
 #</pre> <!-- оставите овај ред онаквим какав јесте -->',
'upload-success-subj' => 'Успешно отпремање',
'upload-success-msg' => 'Датотека из [$2] је послата. Доступна је овде: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Грешка при отпремању',
'upload-failure-msg' => 'Дошло је до проблема при слању из [$2]:

$1',
'upload-warning-subj' => 'Упозорење при отпремању',
'upload-warning-msg' => 'Дошло је до грешке при слању из [$2]. Вратите се на [[Special:Upload/stash/$1|страницу за слање датотека]] да бисте решили проблем.',

'upload-proto-error' => 'Неисправан протокол',
'upload-proto-error-text' => 'Слање са спољне локације захтева адресу која почиње са <code>http://</code> или <code>ftp://</code>.',
'upload-file-error' => 'Унутрашња грешка',
'upload-file-error-text' => 'Дошло је до унутрашње грешке при отварању привремене датотеке на серверу.
Контактирајте [[Special:ListUsers/sysop|администратора]].',
'upload-misc-error' => 'Непозната грешка при слању датотеке',
'upload-misc-error-text' => 'Непозната грешка при слању датотеке.
Проверите да ли је адреса исправна и покушајте поново.
Ако се проблем не реши, контактирајте [[Special:ListUsers/sysop|администратора]].',
'upload-too-many-redirects' => 'Адреса садржи превише преусмерења',
'upload-unknown-size' => 'Непозната величина',
'upload-http-error' => 'Дошло је до HTTP грешке: $1',
'upload-copy-upload-invalid-domain' => 'Примерци отпремања нису доступни на овом домену.',

# File backend
'backend-fail-stream' => 'Не могу да емитујем датотеку $1.',
'backend-fail-backup' => 'Не могу да направим резерву датотеке $1.',
'backend-fail-notexists' => 'Датотека $1 не постоји.',
'backend-fail-hashes' => 'Не могу да добијем дисперзије датотеке за упоређивање.',
'backend-fail-notsame' => 'Већ постоји неистоветна датотека – $1.',
'backend-fail-invalidpath' => '$1 није исправна путања за складиштење.',
'backend-fail-delete' => 'Не могу да обришем датотеку $1.',
'backend-fail-describe' => 'Не могу да променим метаподатке за датотеку „$1“.',
'backend-fail-alreadyexists' => 'Датотека $1 већ постоји.',
'backend-fail-store' => 'Не могу да сместим датотеку $1 у $2.',
'backend-fail-copy' => 'Не могу да умножим датотеку $1 у $2.',
'backend-fail-move' => 'Не могу да преместим датотеку $1 у $2.',
'backend-fail-opentemp' => 'Не могу да отворим привремену датотеку.',
'backend-fail-writetemp' => 'Не могу да пишем у привременој датотеци.',
'backend-fail-closetemp' => 'Не могу да затворим привремену датотеку.',
'backend-fail-read' => 'Не могу да прочитам датотеку $1.',
'backend-fail-create' => 'Не могу да запишем датотеку $1.',
'backend-fail-maxsize' => 'Не могу да запишем датотеку $1 јер је већа од {{PLURAL:$2|$2 бајта|$2 бајта|$2 бајтова}}.',
'backend-fail-readonly' => 'Складишна основа „$1“ тренутно не може да се записује. Наведени разлог гласи: „$2“',
'backend-fail-synced' => 'Датотека „$1“ је недоследна између унутрашњих складишних основа',
'backend-fail-connect' => 'Не могу да се повежем са складишном основом „$1“.',
'backend-fail-internal' => 'Дошло је до непознате грешке у складишној основи „$1“.',
'backend-fail-contenttype' => 'Не могу да утврдим какав садржај има датотека коју треба да сместим у „$1“.',
'backend-fail-batchsize' => 'Складишна основа је добила блокаду од $1 {{PLURAL:$1|операције|операције|операција}}; ограничење је $2 {{PLURAL:$2|операција|операције|операција}}.',
'backend-fail-usable' => 'Не могу да прочитам или запишем датотеку „$1“ јер немате довољно дозвола или вам недостају фасцикле/садржаоци.',

# File journal errors
'filejournal-fail-dbconnect' => 'Не могу да се повежем с новинарском базом за складишну основу „$1“.',
'filejournal-fail-dbquery' => 'Не могу да ажурирам новинарску базу за складишну основу „$1“.',

# Lock manager
'lockmanager-notlocked' => 'Не могу да откључам „$1“ јер није закључан.',
'lockmanager-fail-closelock' => 'Не могу да затворим катанац за „$1“.',
'lockmanager-fail-deletelock' => 'Не могу да обришем катанац за „$1“.',
'lockmanager-fail-acquirelock' => 'Не могу да добијем катанац за „$1“.',
'lockmanager-fail-openlock' => 'Не могу да отворим катанац за „$1“.',
'lockmanager-fail-releaselock' => 'Не могу да ослободим катанац за „$1“.',
'lockmanager-fail-db-bucket' => 'Не могу да контактирам с довољно катанаца у канти $1.',
'lockmanager-fail-db-release' => 'Не могу да ослободим катанце у бази $1.',
'lockmanager-fail-svr-acquire' => 'Не могу да добијем катанце на серверу $1.',
'lockmanager-fail-svr-release' => 'Не могу да ослободим катанце на серверу $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Дошло је до грешке при отварању датотеке за проверу ZIP архиве.',
'zip-wrong-format' => 'Наведена датотека није формата ZIP.',
'zip-bad' => 'Датотека је оштећена или је нечитљива ZIP датотека.
Безбедносна провера не може да се изврши како треба.',
'zip-unsupported' => 'Датотека је формата ZIP који користи могућности које не подржава Медијавики.
Безбедносна провера не може да се изврши како треба.',

# Special:UploadStash
'uploadstash' => 'Тајно складиште',
'uploadstash-summary' => 'Ова страница пружа приступ датотекама које су послате (или се шаљу), али још нису објављене. Ове датотеке су видљиве само кориснику који га је послао.',
'uploadstash-clear' => 'Очисти сакривене датотеке',
'uploadstash-nofiles' => 'Немате сакривене датотеке.',
'uploadstash-badtoken' => 'Извршавање дате радње није успело. Разлог томе може бити истек времена за уређивање. Покушајте поново.',
'uploadstash-errclear' => 'Чишћење датотека није успело.',
'uploadstash-refresh' => 'Освежи списак датотека',
'invalid-chunk-offset' => 'Неисправна полазна тачка',

# img_auth script messages
'img-auth-accessdenied' => 'Приступ је одбијен',
'img-auth-nopathinfo' => 'Недостаје PATH_INFO.
Ваш сервер није подешен да прослеђује овакве податке.
Можда је заснован на CGI-ју који не подржава img_auth.
Погледајте https://www.mediawiki.org/wiki/Manual:Image_Authorization?uselang=sr-ec.',
'img-auth-notindir' => 'Захтевана путања није у подешеној фасцикли за слање.',
'img-auth-badtitle' => 'Не могу да створим исправан наслов за „$1“.',
'img-auth-nologinnWL' => 'Нисте пријављени и „$1“ није на списку дозвољених.',
'img-auth-nofile' => 'Датотека „$1“ не постоји.',
'img-auth-isdir' => 'Покушавате да приступите фасцикли „$1“.
Дозвољен је само приступ датотекама.',
'img-auth-streaming' => 'Учитавање „$1“.',
'img-auth-public' => 'Сврха img_auth.php је да прослеђује датотеке из приватних викија.
Овај вики је постављен као јавни.
Ради сигурности, img_auth.php је онемогућен.',
'img-auth-noread' => 'Корисник нема приступ за читање „$1“.',
'img-auth-bad-query-string' => 'Адреса има неисправну ниску упита.',

# HTTP errors
'http-invalid-url' => 'Неисправна адреса: $1',
'http-invalid-scheme' => 'Адресе са шемом „$1“ нису подржане.',
'http-request-error' => 'HTTP захтев није прошао због непознате грешке.',
'http-read-error' => 'HTTP грешка при читању.',
'http-timed-out' => 'Захтев HTTP је истекао.',
'http-curl-error' => 'Грешка при отварању адресе: $1',
'http-bad-status' => 'Дошло је до проблема током захтева HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Не могу да приступим адреси',
'upload-curl-error6-text' => 'Не могу да приступим наведеној адреси.
Проверите да ли је адреса исправна и доступна.',
'upload-curl-error28' => 'Слање је истекло',
'upload-curl-error28-text' => 'Сервер не одговара на упит.
Проверите да ли сајт ради, мало осачекајте и покушајте поново.
Пробајте касније када буде мање оптерећење.',

'license' => 'Лиценца:',
'license-header' => 'Лиценца:',
'nolicense' => 'није изабрано',
'license-nopreview' => '(преглед није доступан)',
'upload_source_url' => ' (исправна и јавно доступна адреса)',
'upload_source_file' => ' (датотека на вашем рачунару)',

# Special:ListFiles
'listfiles-summary' => 'Ова посебна страница приказује све послате датотеке.',
'listfiles_search_for' => 'Назив датотеке:',
'imgfile' => 'датотека',
'listfiles' => 'Списак датотека',
'listfiles_thumb' => 'Минијатура',
'listfiles_date' => 'Датум',
'listfiles_name' => 'Назив',
'listfiles_user' => 'Корисник',
'listfiles_size' => 'Величина',
'listfiles_description' => 'Опис',
'listfiles_count' => 'Верзије',
'listfiles-latestversion' => 'Тренутна верзија',
'listfiles-latestversion-yes' => 'Да',
'listfiles-latestversion-no' => 'Не',

# File description page
'file-anchor-link' => 'Датотека',
'filehist' => 'Историја датотеке',
'filehist-help' => 'Кликните на датум/време да видите тадашњу верзију датотеке.',
'filehist-deleteall' => 'обриши све',
'filehist-deleteone' => 'обриши',
'filehist-revert' => 'врати',
'filehist-current' => 'тренутно',
'filehist-datetime' => 'Датум/време',
'filehist-thumb' => 'Минијатура',
'filehist-thumbtext' => 'Умањени приказ за издање од $1',
'filehist-nothumb' => 'Нема минијатуре',
'filehist-user' => 'Корисник',
'filehist-dimensions' => 'Димензије',
'filehist-filesize' => 'Величина датотеке',
'filehist-comment' => 'Коментар',
'filehist-missing' => 'Датотека недостаје',
'imagelinks' => 'Употреба датотеке',
'linkstoimage' => '{{PLURAL:$1|Следећа страница користи|$1 следеће странице користе|$1 следећих страница користи}} ову датотеку:',
'linkstoimage-more' => 'Више од $1 {{PLURAL:$1|странице|странице|страница}} је повезано с овом датотеком.
Следећи списак приказује само {{PLURAL:$1|прву страницу повезану|прве $1 странице повезане|првих $1 страница повезаних}} с овом датотеком.
Доступан је и [[Special:WhatLinksHere/$2|потпуни списак]].',
'nolinkstoimage' => 'Нема страница које користе ову датотеку.',
'morelinkstoimage' => 'Погледајте [[Special:WhatLinksHere/$1|више веза]] до ове датотеке.',
'linkstoimage-redirect' => '$1 (преусмерење датотеке) $2',
'duplicatesoffile' => '{{PLURAL:$1|Следећа датотека је дупликат|Следеће $1 датотеке су дупликати|Следећих $1 датотека су дупликати}} ове датотеке ([[Special:FileDuplicateSearch/$2|детаљније]]):',
'sharedupload' => 'Ова датотека се налази на $1 и може се користити и на другим пројектима.',
'sharedupload-desc-there' => 'Ова датотека се налази на $1 и може се користити и на другим пројектима.
Погледајте [$2 страницу за опис датотеке] за више детаља о њој.',
'sharedupload-desc-here' => 'Ова датотека се налази на $1 и може да се користи на другим пројектима.
Опис њене [$2 странице за опис] је приказан испод.',
'sharedupload-desc-edit' => 'Ова датотека се налази на $1 и може да се користи на другим пројектима.
Њен опис можете да измените на [$2 одговарајућој страници].',
'sharedupload-desc-create' => 'Ова датотека се налази на $1 и може да се користи на другим пројектима.
Њен опис можете да измените на [$2 одговарајућој страници].',
'filepage-nofile' => 'Не постоји датотека с овим називом.',
'filepage-nofile-link' => 'Не постоји датотека с овим називом, али је можете [$1 послати].',
'uploadnewversion-linktext' => 'Пошаљи нову верзију ове датотеке',
'shared-repo-from' => 'из $1',
'shared-repo' => 'заједничко складиште',
'shared-repo-name-wikimediacommons' => 'Викимедијина остава',
'filepage.css' => '/* CSS који је постављен овде се налази на страницама за опис датотека, као и на страним викијима */',
'upload-disallowed-here' => 'Не можете да замените ову датотеку.',

# File reversion
'filerevert' => 'Врати $1',
'filerevert-legend' => 'Врати датотеку',
'filerevert-intro' => "Враћате датотеку '''[[Media:$1|$1]]''' на [$4 издање од $2; $3].",
'filerevert-comment' => 'Разлог:',
'filerevert-defaultcomment' => 'Враћено на издање од $1; $2',
'filerevert-submit' => 'Врати',
'filerevert-success' => "Датотека '''[[Media:$1|$1]]''' је враћена на [$4 издање од $2; $3].",
'filerevert-badversion' => 'Не постоји раније локално издање датотеке с наведеним временским подацима.',

# File deletion
'filedelete' => 'Обриши $1',
'filedelete-legend' => 'Обриши датотеку',
'filedelete-intro' => "Бришете датотеку '''[[Media:$1|$1]]''' заједно с њеном историјом.",
'filedelete-intro-old' => "Бришете верзију датотеке '''[[Media:$1|$1]]''' од [$4 $2; $3].",
'filedelete-comment' => 'Разлог:',
'filedelete-submit' => 'Обриши',
'filedelete-success' => "Датотека '''$1''' је обрисана.",
'filedelete-success-old' => "Издање '''[[Media:$1|$1]]''' од $2, $3 је обрисано.",
'filedelete-nofile' => "Датотека '''$1''' не постоји.",
'filedelete-nofile-old' => "Не постоји архивирано издање датотеке '''$1''' с наведеним особинама.",
'filedelete-otherreason' => 'Други/додатни разлог:',
'filedelete-reason-otherlist' => 'Други разлог',
'filedelete-reason-dropdown' => '*Најчешћи разлози брисања
** Кршење ауторских права
** Дупликати датотека',
'filedelete-edit-reasonlist' => 'Уреди разлоге брисања',
'filedelete-maintenance' => 'Брисање и враћање датотека је привремено онемогућено током одржавања.',
'filedelete-maintenance-title' => 'Не могу да обришем датотеку',

# MIME search
'mimesearch' => 'MIME претрага',
'mimesearch-summary' => 'Ова страница омогућава филтрирање датотека према њиховим врстама MIME.
Улазни подаци: contenttype/subtype, нпр. <code>image/jpeg</code>.',
'mimetype' => 'MIME врста:',
'download' => 'преузми',

# Unwatched pages
'unwatchedpages' => 'Ненадгледане странице',

# List redirects
'listredirects' => 'Списак преусмерења',

# Unused templates
'unusedtemplates' => 'Некоришћени шаблони',
'unusedtemplatestext' => 'Ова страница наводи све странице у именском простору {{ns:template}} које нису укључене ни на једној другој страници.
Пре брисања проверите да ли друге странице воде до тих шаблона.',
'unusedtemplateswlh' => 'остале везе',

# Random page
'randompage' => 'Случајна страница',
'randompage-nopages' => 'Нема страница у {{PLURAL:$2|следећем именском простору|следећим именским просторима}}: $1.',

# Random page in category
'randomincategory' => 'Случајна страница у категорији',
'randomincategory-selectcategory-submit' => 'Иди',

# Random redirect
'randomredirect' => 'Случајно преусмерење',
'randomredirect-nopages' => 'Нема преусмерења у именском простору „$1”.',

# Statistics
'statistics' => 'Статистике',
'statistics-header-pages' => 'Странице',
'statistics-header-edits' => 'Измене',
'statistics-header-views' => 'Прегледи',
'statistics-header-users' => 'Корисници',
'statistics-header-hooks' => 'Остало',
'statistics-articles' => 'Странице са садржајем',
'statistics-pages' => 'Страница',
'statistics-pages-desc' => 'Све странице на викију, укључујући странице за разговор, преусмерења итд.',
'statistics-files' => 'Отпремљено датотека',
'statistics-edits' => 'Број измена страница откад постоји {{SITENAME}}',
'statistics-edits-average' => 'Просечан број измена по страници',
'statistics-views-total' => 'Укупно прегледа',
'statistics-views-total-desc' => 'Прегледи непостојећих и посебних страница нису укључени',
'statistics-views-peredit' => 'Прегледа по измени',
'statistics-users' => 'Уписани корисници ([[Special:ListUsers|списак чланова]])',
'statistics-users-active' => 'Активни корисници',
'statistics-users-active-desc' => 'Корисници који су извршили бар једну радњу {{PLURAL:$1|претходни дан|у последња $1 дана|у последњих $1 дана}}',
'statistics-mostpopular' => 'Најпосећеније странице',

'pageswithprop' => 'Стране с особином стране',
'pageswithprop-legend' => 'Стране с особином стране',
'pageswithprop-text' => 'Ова страна излистава стране које имају одређену особину',
'pageswithprop-prop' => 'Име особине:',
'pageswithprop-submit' => 'Иди',

'doubleredirects' => 'Двострука преусмерења',
'doubleredirectstext' => 'Ова страница приказује странице које преусмеравају на друга преусмерења.
Сваки ред садржи везе према првом и другом преусмерењу, као и одредишну страницу другог преусмерења која је обично „прави“ чланак на кога прво преусмерење треба да упућује.
<del>Прецртани</del> уноси су већ решени.',
'double-redirect-fixed-move' => '[[$1]] је премештен.
Сада је преусмерење на [[$2]].',
'double-redirect-fixed-maintenance' => 'Исправљање двоструких преусмерења из [[$1]] у [[$2]].',
'double-redirect-fixer' => 'Исправљач преусмерења',

'brokenredirects' => 'Покварена преусмерења',
'brokenredirectstext' => 'Следећа преусмерења упућују на непостојеће странице:',
'brokenredirects-edit' => 'уреди',
'brokenredirects-delete' => 'обриши',

'withoutinterwiki' => 'Странице без језичких веза',
'withoutinterwiki-summary' => 'Следеће странице нису повезане с другим језицима.',
'withoutinterwiki-legend' => 'Префикс',
'withoutinterwiki-submit' => 'Прикажи',

'fewestrevisions' => 'Странице с најмање измена',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|бајт|бајта|бајтова}}',
'ncategories' => '$1 {{PLURAL:$1|категорија|категорије|категорија}}',
'ninterwikis' => '$1 {{PLURAL:$1|међувики|међувикија|међувикија}}',
'nlinks' => '$1 {{PLURAL:$1|веза|везе|веза}}',
'nmembers' => '$1 {{PLURAL:$1|члан|члана|чланова}}',
'nrevisions' => '$1 {{PLURAL:$1|измена|измене|измена}}',
'nviews' => '$1 {{PLURAL:$1|преглед|прегледа|прегледа}}',
'nimagelinks' => 'Користи се на $1 {{PLURAL:$1|страници|странице|страница}}',
'ntransclusions' => 'користи се на $1 {{PLURAL:$1|страници|странице|страница}}',
'specialpage-empty' => 'Нема резултата за овај извештај.',
'lonelypages' => 'Сирочићи',
'lonelypagestext' => 'Следеће странице нису повезане с другим страницама, нити су укључене трансклузијом у друге странице.',
'uncategorizedpages' => 'Некатегорисане странице',
'uncategorizedcategories' => 'Некатегорисане странице',
'uncategorizedimages' => 'Датотеке без категорија',
'uncategorizedtemplates' => 'Некатегорисани шаблони',
'unusedcategories' => 'Некоришћене категорије',
'unusedimages' => 'Некоришћене датотеке',
'popularpages' => 'Популарне странице',
'wantedcategories' => 'Тражене категорије',
'wantedpages' => 'Тражене странице',
'wantedpages-badtitle' => 'Неисправан наслов у низу резултата: $1',
'wantedfiles' => 'Тражене датотеке',
'wantedfiletext-cat' => 'Следеће датотеке се користе, али не постоје. Датотеке из других ризница могу бити наведене иако не постоје. Такве датотеке ће бити <del>поништене</del> са списка. Поред тога, странице које садрже непостојеће датотеке се налазе [[:$1|овде]].',
'wantedfiletext-nocat' => 'Следеће датотеке се користе, али не постоје. Датотеке из других ризница могу бити наведене иако не постоје. Такве датотеке ће бити <del>поништене</del> са списка.',
'wantedtemplates' => 'Тражени шаблони',
'mostlinked' => 'Странице с највише веза',
'mostlinkedcategories' => 'Чланци с највише категорија',
'mostlinkedtemplates' => 'Шаблони с највише веза',
'mostcategories' => 'Чланци с највише категорија',
'mostimages' => 'Датотеке с највише веза',
'mostinterwikis' => 'Странице са највише међувикија',
'mostrevisions' => 'Странице с највише измена',
'prefixindex' => 'Све странице с префиксом',
'prefixindex-namespace' => 'Све странице с предметком (именски простор $1)',
'shortpages' => 'Кратке странице',
'longpages' => 'Дугачке странице',
'deadendpages' => 'Странице без унутрашњих веза',
'deadendpagestext' => 'Следеће странице немају везе до других страница на овом викију.',
'protectedpages' => 'Заштићене странице',
'protectedpages-indef' => 'само неограничене заштите',
'protectedpages-cascade' => 'само преносиве заштите',
'protectedpagestext' => 'Следеће странице су заштићене од премештања или уређивања',
'protectedpagesempty' => 'Нема заштићених страница с овим параметрима.',
'protectedtitles' => 'Заштићени наслови',
'protectedtitlestext' => 'Следећи наслови су заштићени од стварања',
'protectedtitlesempty' => 'Нема заштићених наслова с овим параметрима.',
'listusers' => 'Списак корисника',
'listusers-editsonly' => 'прикажи само кориснике који су уређивали',
'listusers-creationsort' => 'поређај по датуму стварања',
'usereditcount' => '$1 {{PLURAL:$1|измена|измене|измена}}',
'usercreated' => '{{GENDER:$3|је направио|је направила|је направио}} дана $1 у $2',
'newpages' => 'Нове странице',
'newpages-username' => 'Корисничко име:',
'ancientpages' => 'Најстарије странице',
'move' => 'премести',
'movethispage' => 'Премести ову страницу',
'unusedimagestext' => 'Следеће датотеке постоје, али не користе се ни у једној страници.
Друге веб странице могу користити слику преко директне адресе, тако да и поред тога могу бити приказане овде поред активне употребе.',
'unusedcategoriestext' => 'Следеће странице категорија постоје иако их ниједан други чланак или категорија не користе.',
'notargettitle' => 'Нема одредишта',
'notargettext' => 'Нисте навели одредишну страницу или корисника на коме би се извела ова радња.',
'nopagetitle' => 'Не постоји таква страница',
'nopagetext' => 'Тражена страница не постоји.',
'pager-newer-n' => '{{PLURAL:$1|новији 1|новија $1|новијих $1}}',
'pager-older-n' => '{{PLURAL:$1|старији 1|старија $1|старијих $1}}',
'suppress' => 'Надзор',
'querypage-disabled' => 'Ова посебна страница је онемогућена ради побољшања перформанси.',

# Book sources
'booksources' => 'Штампани извори',
'booksources-search-legend' => 'Тражење извора књиге',
'booksources-isbn' => 'ISBN:',
'booksources-go' => 'Иди',
'booksources-text' => 'Испод се налази списак веза ка сајтовима који се баве продајом нових и половних књига, а који би могли имати додатне податке о књигама које тражите:',
'booksources-invalid-isbn' => 'Наведени ISBN број није исправан. Проверите да није дошло до грешке при умножавању из првобитног извора.',

# Special:Log
'specialloguserlabel' => 'Извршилац:',
'speciallogtitlelabel' => 'Циљ (наслов или корисник):',
'log' => 'Дневници',
'all-logs-page' => 'Сви јавни дневници',
'alllogstext' => 'Скупни приказ свих доступних историја овог викија.
Можете сузити приказ одабирући врсту историје, корисничког имена или тражене странице.',
'logempty' => 'Нема пронађених ставки у историји.',
'log-title-wildcard' => 'тражи наслове који почињу с овим текстом',
'showhideselectedlogentries' => 'Прикажи/сакриј изабране записе',

# Special:AllPages
'allpages' => 'Све странице',
'alphaindexline' => '$1 до $2',
'nextpage' => 'Следећа страница ($1)',
'prevpage' => 'Претходна страница ($1)',
'allpagesfrom' => 'Прикажи странице почев од:',
'allpagesto' => 'Прикажи странице завршно са:',
'allarticles' => 'Све странице',
'allinnamespace' => 'Све странице (именски простор $1)',
'allnotinnamespace' => 'Све странице ван именског простора $1',
'allpagesprev' => 'Претходна',
'allpagesnext' => 'Следећа',
'allpagessubmit' => 'Иди',
'allpagesprefix' => 'Прикажи странице с префиксом:',
'allpagesbadtitle' => 'Наведени назив странице није исправан или садржи међујезички или међувики префикс.
Можда садржи знакове који се не могу користити у насловима.',
'allpages-bad-ns' => '{{SITENAME}} нема именски простор „$1“.',
'allpages-hide-redirects' => 'Сакриј преусмерења',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Гледате кеширану верзију ове странице, која може бити стара и до $1.',
'cachedspecial-viewing-cached-ts' => 'Гледате кеширану верзију ове странице, која може да се разликује од тренутне.',
'cachedspecial-refresh-now' => 'Погледај најновију.',

# Special:Categories
'categories' => 'Категоријe',
'categoriespagetext' => '{{PLURAL:$1|Следећа категорија садржи|Следеће категорије садрже}} странице или датотеке.
[[Special:UnusedCategories|Некоришћене категорије]] нису приказане овде.
Погледајте и [[Special:WantedCategories|тражене категорије]].',
'categoriesfrom' => 'Прикажи категорије почев од:',
'special-categories-sort-count' => 'поређај по броју',
'special-categories-sort-abc' => 'поређај по азбучном реду',

# Special:DeletedContributions
'deletedcontributions' => 'Обрисани кориснички доприноси',
'deletedcontributions-title' => 'Обрисани кориснички доприноси',
'sp-deletedcontributions-contribs' => 'доприноси',

# Special:LinkSearch
'linksearch' => 'Претрага спољних веза',
'linksearch-pat' => 'Образац претраге:',
'linksearch-ns' => 'Именски простор:',
'linksearch-ok' => 'Претражи',
'linksearch-text' => 'Могу се користити џокери попут „*.wikipedia.org“.
Потребан је највиши домен, као „*.org“.<br />
{{PLURAL:$2|Подржан протокол|Подржани протоколи}}: <code>$1</code> (задаје http:// ако не наведете протокол).',
'linksearch-line' => '$1 веза у $2',
'linksearch-error' => 'Џокери се могу појавити само на почетку адресе.',

# Special:ListUsers
'listusersfrom' => 'Прикажи кориснике почев од:',
'listusers-submit' => 'Прикажи',
'listusers-noresult' => 'Корисник није пронађен.',
'listusers-blocked' => '({{GENDER:$1|блокиран|блокирана|блокиран}})',

# Special:ActiveUsers
'activeusers' => 'Списак активних корисника',
'activeusers-intro' => 'Ово је списак корисника који су били активни {{PLURAL:$1|претходни дан|у последња $1 дана|у последњих $1 дана}}.',
'activeusers-count' => '$1 {{PLURAL:$1|измена|измене|измена}} {{PLURAL:$3|претходни дан|у последња $3 дана|у последњих $3 дана}}',
'activeusers-from' => 'Прикажи кориснике почев од:',
'activeusers-hidebots' => 'Сакриј ботове',
'activeusers-hidesysops' => 'Сакриј администраторе',
'activeusers-noresult' => 'Корисник није пронађен.',

# Special:ListGroupRights
'listgrouprights' => 'Права корисничких група',
'listgrouprights-summary' => 'Следи списак корисничких група на овом викију, заједно с правима приступа.
Погледајте [[{{MediaWiki:Listgrouprights-helppage}}|више детаља]] о појединачним правима.',
'listgrouprights-key' => 'Легенда:
* <span class="listgrouprights-granted">Додељено право</span>
* <span class="listgrouprights-revoked">Укинуто право</span>',
'listgrouprights-group' => 'Група',
'listgrouprights-rights' => 'Права',
'listgrouprights-helppage' => 'Help:Права групе',
'listgrouprights-members' => '(списак чланова)',
'listgrouprights-right-display' => '<span class="listgrouprights-granted">$1 <code>($2)</code></span>',
'listgrouprights-right-revoked' => '<span class="listgrouprights-revoked">$1 <code>($2)</code></span>',
'listgrouprights-addgroup' => 'додаје {{PLURAL:$2|следећу групу|следеће групе}}: $1',
'listgrouprights-removegroup' => 'брише {{PLURAL:$2|следећу групу|следеће групе}}: $1',
'listgrouprights-addgroup-all' => 'додавање свих група',
'listgrouprights-removegroup-all' => 'брисање свих група',
'listgrouprights-addgroup-self' => 'додавање {{PLURAL:$2|групе|група}} на свој налог: $1',
'listgrouprights-removegroup-self' => 'уклањање {{PLURAL:$2|групе|група}} са свог налога: $1',
'listgrouprights-addgroup-self-all' => 'Додај све групе на сопствени налог',
'listgrouprights-removegroup-self-all' => 'Уклони све групе са сопственог налога',

# Email user
'mailnologin' => 'Нема адресе за слање',
'mailnologintext' => 'Морате бити [[Special:UserLogin|пријављени]] и имати исправну е-адресу у [[Special:Preferences|подешавањима]] да бисте слали е-поруке другим корисницима.',
'emailuser' => 'Пошаљи е-поруку',
'emailuser-title-target' => 'Слање е-поруке {{GENDER:$1|кориснику|корисници|кориснику}}',
'emailuser-title-notarget' => 'Слање е-поруке кориснику',
'emailpage' => 'Слање е-порука',
'emailpagetext' => 'Можете да користите доњи образац да пошаљете е-поруку {{GENDER:$1|овом кориснику|овој корисници|овом кориснику|}}.
Е-адреса коју сте унели у вашим [[Special:Preferences|подешавањима]] ће се приказати у пољу „Од“, тако да ће прималац моћи да вам одговори директно.',
'usermailererror' => 'Дошло је до грешке при слању поруке:',
'defemailsubject' => '{{SITENAME}} е-адреса {{GENDER:$1|корисника|кориснице|корисника}} $1',
'usermaildisabled' => 'Корисничка е-пошта је онемогућена',
'usermaildisabledtext' => 'Не можете да шаљете е-поруке другим корисницима на овом викију',
'noemailtitle' => 'Нема е-адресе',
'noemailtext' => 'Овај корисник није навео исправну е-адресу.',
'nowikiemailtitle' => 'Е-пошта није дозвољена',
'nowikiemailtext' => 'Овај корисник је одлучио да не прима е-поруке од других корисника.',
'emailnotarget' => 'Непостојеће или неисправно корисничко име примаоца.',
'emailtarget' => 'Унос корисничког имена примаоца',
'emailusername' => 'Корисничко име:',
'emailusernamesubmit' => 'Пошаљи',
'email-legend' => 'Слање е-порука другом кориснику',
'emailfrom' => 'Од:',
'emailto' => 'За:',
'emailsubject' => 'Наслов:',
'emailmessage' => 'Порука:',
'emailsend' => 'Пошаљи',
'emailccme' => 'Пошаљи ми примерак поруке е-поштом',
'emailccsubject' => 'Примерак ваше поруке за $1: $2',
'emailsent' => 'Порука је послата',
'emailsenttext' => 'Ваша порука је послата е-поштом.',
'emailuserfooter' => 'Ову е-поруку је {{GENDER:|послао|послала|послао}} $1 кориснику $2 путем е-поште с викија {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Слање системске поруке.',
'usermessage-editor' => 'Уређивач системских порука',
'usermessage-template' => 'MediaWiki:UserMessage',

# Watchlist
'watchlist' => 'Списак надгледања',
'mywatchlist' => 'Списак надгледања',
'watchlistfor2' => 'За $1 $2',
'nowatchlist' => 'Ваш списак надгледања је празан.',
'watchlistanontext' => 'Морате бити $1 да бисте гледали и уређивали ставке на вашем списку надгледања.',
'watchnologin' => 'Нисте пријављени',
'watchnologintext' => 'Морате бити [[Special:UserLogin|пријављени]] да бисте мењали списак надгледања.',
'addwatch' => 'Додај на списак надгледања',
'addedwatchtext' => 'Страница „[[:$1]]“ је додата на ваш [[Special:Watchlist|списак надгледања]].
Будуће измене ове странице и њене странице за разговор биће наведене тамо.',
'removewatch' => 'Уклони са списка надгледања',
'removedwatchtext' => 'Страница „[[:$1]]“ је уклоњена с вашег [[Special:Watchlist|списка надгледања]].',
'watch' => 'Надгледај',
'watchthispage' => 'Надгледај ову страницу',
'unwatch' => 'Прекини надгледање',
'unwatchthispage' => 'Прекини надгледање',
'notanarticle' => 'Није страница са садржајем',
'notvisiblerev' => 'Измена је обрисана',
'watchlist-details' => '{{PLURAL:$1|$1 страница|$1 странице|$1 страница}} на вашем списку надгледања, не рачунајући странице за разговор.',
'wlheader-enotif' => 'Обавештење е-поруком је омогућено.',
'wlheader-showupdated' => "Странице које су измењене откад сте их последњи пут посетили су '''подебљане'''.",
'watchmethod-recent' => 'проверава се да ли има надгледаних страница у скорашњим изменама',
'watchmethod-list' => 'проверава се да ли има скорашњих измена у надгледаним страницама',
'watchlistcontains' => 'Ваш списак надгледања садржи $1 {{PLURAL:$1|страницу|странице|страница}}.',
'iteminvalidname' => 'Проблем са ставком „$1“. Неисправан назив.',
'wlnote' => "Испод {{PLURAL:$1|је последња измена|су последње '''$1''' измене|је последњих '''$1''' измена}} у {{PLURAL:$2|претходном сату|претходна '''$2''' сата|претходних '''$2''' сати}}, закључно са $3, $4.",
'wlshowlast' => 'Прикажи последњих $1 сати, $2 дана, $3',
'watchlist-options' => 'Поставке списка надгледања',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Надгледање…',
'unwatching' => 'Прекидање надгледања…',
'watcherrortext' => 'Дошло је до грешке при промени поставки вашег списка надгледања за „$1“.',

'enotif_mailer' => '{{SITENAME}} е-обавештење',
'enotif_reset' => 'Означи све странице као посећене',
'enotif_impersonal_salutation' => '{{SITENAME}} корисник',
'enotif_subject_deleted' => 'Страницу $1 на {{SITENAME}} {{GENDER:$2|обрисао је|обрисала је|обрисао је}} $2',
'enotif_subject_created' => 'Страницу $1 на {{SITENAME}} {{GENDER:$2|направио је|направила је|направио је}} $2',
'enotif_subject_moved' => 'Страницу $1 на {{SITENAME}} {{GENDER:$2|преместио је|преместила је}} $2',
'enotif_subject_restored' => 'Страницу $1 на {{SITENAME}} {{GENDER:$2|вратио је|вратила је|вратио је}} $2',
'enotif_subject_changed' => 'Страницу $1 на {{SITENAME}} {{GENDER:$2|променио је|променила је|променио је}} $2',
'enotif_body_intro_deleted' => 'Страницу $1 на {{SITENAME}} {{GENDER:$2|обрисао је|обрисала је|обрисао је}} $2 дана $PAGEEDITDATE. Погледајте $3.',
'enotif_body_intro_created' => 'Страницу $1 на {{SITENAME}} {{GENDER:$2|направио је|направила је|направио је}} $2 дана $PAGEEDITDATE. Тренутна измена налази се на $3.',
'enotif_body_intro_moved' => 'Страницу $1 на {{SITENAME}} {{GENDER:$2|преместио је|преместила је}} $2 дана $PAGEEDITDATE. Тренутна измена налази се на  $3.',
'enotif_body_intro_restored' => 'Страницу $1 на {{SITENAME}} {{GENDER:$2|вратио је|вратила је|вратио је}} $2 дана $PAGEEDITDATE. Тренутна измена налази се на $3.',
'enotif_body_intro_changed' => 'Страницу $1 на {{SITENAME}} {{GENDER:$2|променио је|променила је|променио је}} $2 дана $PAGEEDITDATE. Тренутна измена налази се на $3.',
'enotif_lastvisited' => 'Погледајте $1 за све измене од ваше последње посете.',
'enotif_lastdiff' => 'Погледајте $1 да видите ову измену.',
'enotif_anon_editor' => 'анониман корисник $1',
'enotif_body' => 'Поштовани $WATCHINGUSERNAME,


Страница $PAGETITLE на викију {{SITENAME}} је $CHANGEDORCREATED дана $PAGEEDITDATE од стране {{GENDER:$PAGEEDITOR|корисника|кориснице|корисника}} $PAGEEDITOR. Погледајте $PAGETITLE_URL за текућу измену.

$NEWPAGE

Опис: $PAGESUMMARY $PAGEMINOREDIT

Контакт:
е-адреса: $PAGEEDITOR_EMAIL
вики: $PAGEEDITOR_WIKI

Неће бити других обавештења у случају даљих измена уколико не посетите ову страницу.
Можете и да поништите поставке обавештења за све странице у вашем списку надгледања.

Срдачан поздрав, {{SITENAME}}

--
Да бисте променили поставке у вези са е-обавештењима, посетите
{{canonicalurl:{{#special:Preferences}}}}

Да бисте променили поставке у вези са списком надгледања, посетите
{{canonicalurl:{{#special:EditWatchlist}}}}

Да бисте уклонили ову страницу са списка надгледања, посетите
$UNWATCHURL

Подршка и даља помоћ:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'направљена',
'changed' => 'измењена',

# Delete
'deletepage' => 'Обриши страницу',
'confirm' => 'Потврди',
'excontent' => 'садржај је био: „$1“',
'excontentauthor' => 'садржај је био: „$1“ (једину измену {{GENDER:|направио је|направила је|направио је}} [[Special:Contributions/$2|$2]])',
'exbeforeblank' => 'садржај пре брисања је био: „$1“',
'exblank' => 'страница је била празна',
'delete-confirm' => 'Брисање странице „$1“',
'delete-legend' => 'Обриши',
'historywarning' => "'''Упозорење:''' страница коју желите да обришете има историју с приближно $1 {{PLURAL:$1|изменом|измене|измена}}:",
'confirmdeletetext' => 'Управо ћете обрисати страницу, укључујући и њену историју.
Потврдите своју намеру, да разумете последице и да ово радите у складу с [[{{MediaWiki:Policy-url}}|правилима]].',
'actioncomplete' => 'Радња је завршена',
'actionfailed' => 'Радња није успела',
'deletedtext' => "Страница „$1“ је обрисана.
Погледајте ''$2'' за више детаља.",
'dellogpage' => 'Дневник брисања',
'dellogpagetext' => 'Испод је списак последњих брисања.',
'deletionlog' => 'дневник брисања',
'reverted' => 'Враћено на ранију измену',
'deletecomment' => 'Разлог:',
'deleteotherreason' => 'Други/додатни разлог:',
'deletereasonotherlist' => 'Други разлог',
'deletereason-dropdown' => '*Најчешћи разлози за брисање
** Захтев аутора
** Кршење ауторских права
** Вандализам',
'delete-edit-reasonlist' => 'Уреди разлоге брисања',
'delete-toobig' => 'Ова страница има велику историју, преко $1 {{PLURAL:$1|измене|измене|измена}}.
Брисање таквих страница је ограничено да би се спречило случајно оптерећење сервера.',
'delete-warning-toobig' => 'Ова страница има велику историју, преко $1 {{PLURAL:$1|измене|изменe|измена}}.
Њено брисање може пореметити базу података, стога поступајте с опрезом.',

# Rollback
'rollback' => 'Врати измене',
'rollback_short' => 'Врати',
'rollbacklink' => 'врати',
'rollbacklinkcount' => 'врати $1 {{PLURAL:$1|измену|измене|измена}}',
'rollbacklinkcount-morethan' => 'врати више од $1 {{PLURAL:$1|измене|измене|измена}}',
'rollbackfailed' => 'Неуспешно враћање',
'cantrollback' => 'Не могу да вратим измену.
Последњи аутор је уједно и једини.',
'alreadyrolled' => 'Враћање последње измене странице [[:$1]] од стране {{GENDER:$2|корисника|кориснице|корисника}} [[User:$2|$2]] ([[User talk:$2|разговор]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); неко други је већ изменио или вратио страницу.

Последњу измену је {{GENDER:$3|направио|направила|направио}} [[User:$3|$3]] ([[User talk:$3|разговор]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "Опис измене: \"''\$1''\".",
'revertpage' => 'Враћене измене [[Special:Contributions/$2|$2]] ([[User talk:$2|разговор]]) на последњу измену корисника [[User:$1|$1]]',
'revertpage-nouser' => 'Враћене су измене скривеног корисника на последњу измену члана [[User:$1|$1]]',
'rollback-success' => 'Враћене су измене {{GENDER:$1|корисника|кориснице|корисника}} $1
на последњу измену {{GENDER:$2|корисника|кориснице|корисника}} $2.',

# Edit tokens
'sessionfailure-title' => 'Сесија је окончана',
'sessionfailure' => 'Изгледа да постоји проблем с вашом сесијом;
ова радња је отказана да би се избегла злоупотреба.
Вратите се на претходну страницу, поново је учитајте и покушајте поново.',

# Protect
'protectlogpage' => 'Дневник заштите',
'protectlogtext' => 'Испод је списак измена у виду заштите страница.
Погледајте [[Special:ProtectedPages|списак заштићених страница]] за више детаља.',
'protectedarticle' => '{{GENDER:|је заштитио|је заштитила|је заштитио}} „[[$1]]“',
'modifiedarticleprotection' => '{{GENDER:|је променио|је променила|је променио}} степен заштите за „[[$1]]“',
'unprotectedarticle' => '{{GENDER:|је уклонио|је уклонила|је уклонио}} заштиту са странице „[[$1]]“',
'movedarticleprotection' => '{{GENDER:|је преместио|је преместила|је преместио}} поставке заштите са „[[$2]]“ на „[[$1]]“',
'protect-title' => 'Степен заштите за „$1“',
'protect-title-notallowed' => 'Преглед степена заштите за „$1“',
'prot_1movedto2' => '{{GENDER:|је преместио|је преместила|је преместио}} [[$1]] у [[$2]]',
'protect-badnamespace-title' => 'Незаштитљив именски простор',
'protect-badnamespace-text' => 'Странице у овом именском простору се не могу заштитити.',
'protect-norestrictiontypes-title' => 'Незаштитљива страна',
'protect-legend' => 'Потврдите заштиту',
'protectcomment' => 'Разлог:',
'protectexpiry' => 'Истиче:',
'protect_expiry_invalid' => 'Време истека је неисправно.',
'protect_expiry_old' => 'Време истека је старије од тренутног времена.',
'protect-unchain-permissions' => 'Откључај даљње поставке заштите',
'protect-text' => "Овде можете да погледате и промените степен заштите за страницу '''$1'''.",
'protect-locked-blocked' => "Не можете мењати степене заштите док сте блокирани.
Ово су тренутне поставке странице '''$1''':",
'protect-locked-dblock' => "Степени заштите се не могу мењати јер је активна база података закључана.
Ово су поставке странице '''$1''':",
'protect-locked-access' => "Немате овлашћења за мењање степена заштите странице.
Ово су тренутне поставке странице '''$1''':",
'protect-cascadeon' => 'Ова страница је тренутно заштићена јер се налази на {{PLURAL:$1|страници која има|страницама које имају}} преносиву заштиту.
Можете да промените степен заштите, али то неће утицати на преносиву заштиту.',
'protect-default' => 'Дозволи свим корисницима',
'protect-fallback' => 'Дозвољено само корисницима са дозволом „$1“',
'protect-level-autoconfirmed' => 'Допуштено само аутоматски потврђеним корисницима',
'protect-level-sysop' => 'Допуштено само администраторима',
'protect-summary-cascade' => 'преносива заштита',
'protect-expiring' => 'истиче $1 (UTC)',
'protect-expiring-local' => 'истиче $1',
'protect-expiry-indefinite' => 'неодређено',
'protect-cascade' => 'Заштити све странице које су укључене у ову (преносива заштита)',
'protect-cantedit' => 'Не можете мењати степене заштите ове странице јер немате овлашћења за уређивање.',
'protect-othertime' => 'Друго време:',
'protect-othertime-op' => 'друго време',
'protect-existing-expiry' => 'Постојеће време истека: $2 у $3',
'protect-otherreason' => 'Други/додатни разлог:',
'protect-otherreason-op' => 'Други разлог',
'protect-dropdown' => '* Најчешћи разлози заштићивања
** Прекомерни вандализам
** Непожељне поруке
** Непродуктивни рат измена
** Страница великог промета',
'protect-edit-reasonlist' => 'Уреди разлоге заштићивања',
'protect-expiry-options' => '1 сат:1 hour,1 дан:1 day,1 недеља:1 week,2 недеље:2 weeks,1 месец:1 month,3 месеца:3 months,6 месеци:6 months,1 година:1 year,трајно:infinite',
'restriction-type' => 'Дозвола:',
'restriction-level' => 'Степен ограничења:',
'minimum-size' => 'Најмања величина',
'maximum-size' => 'Највећа величина:',
'pagesize' => '(бајтови)',

# Restrictions (nouns)
'restriction-edit' => 'уређивање',
'restriction-move' => 'премештање',
'restriction-create' => 'стварање',
'restriction-upload' => 'слање',

# Restriction levels
'restriction-level-sysop' => 'потпуно заштићено',
'restriction-level-autoconfirmed' => 'полузаштићено',
'restriction-level-all' => 'било који степен',

# Undelete
'undelete' => 'Приказ обрисаних страница',
'undeletepage' => 'Приказ и враћање обрисаних страница',
'undeletepagetitle' => "'''Следећи садржај се састоји од обрисаних измена странице [[:$1|$1]]'''.",
'viewdeletedpage' => 'Приказ обрисаних страница',
'undeletepagetext' => '{{PLURAL:$1|Следећа страница је обрисана, али је још у архиви и може бити враћена|Следеће $1 странице су обрисане, али су још у архиви и могу бити враћене|Следећих $1 страница је обрисано, али су још у архиви и могу бити враћене}}.
Архива се повремено чисти од оваквих страница.',
'undelete-fieldset-title' => 'Враћање измена',
'undeleteextrahelp' => "Да бисте вратили целу историју странице, оставите све кућице неозначене и кликните на дугме '''''{{int:undeletebtn}}'''''.
Ако желите да вратите одређене измене, означите их и кликните на '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '$1 {{PLURAL:$1|измена је архивирана|измене су архивиране|измена је архивирано}}',
'undeletehistory' => 'Ако вратите страницу, све измене ће бити враћене њеној историји.
Ако је у међувремену направљена нова страница с истим називом, враћене измене ће се појавити у ранијом историји.',
'undeleterevdel' => 'Враћање неће бити извршено ако је резултат тога делимично брисање последње измене.
У таквим случајевима морате искључити или открити најновије обрисане измене.',
'undeletehistorynoadmin' => 'Ова страница је обрисана.
Разлог за брисање се налази испод, заједно с детаљима о кориснику који је изменио ову страницу пре брисања.
Текст обрисаних измена је доступан само администраторима.',
'undelete-revision' => 'Обрисана измена странице $1 (дана $4; $5) од стране {{GENDER:$3|корисника|кориснице|корисника}} $3:',
'undeleterevision-missing' => 'Неисправна или непостојећа измена.
Можда сте унели погрешну везу, или је измена враћена или уклоњена из архиве.',
'undelete-nodiff' => 'Претходне измене нису пронађене.',
'undeletebtn' => 'Врати',
'undeletelink' => 'погледај/врати',
'undeleteviewlink' => 'погледај',
'undeletereset' => 'Поништи',
'undeleteinvert' => 'Обрни избор',
'undeletecomment' => 'Разлог:',
'undeletedrevisions' => '{{PLURAL:$1|Измена је враћена|$1 измене су враћене|$1 измена је враћено}}',
'undeletedrevisions-files' => '$1 {{PLURAL:$1|измена|измене|измена}} и $2 {{PLURAL:$2|датотека|датотеке|датотека}} је враћено',
'undeletedfiles' => '{{PLURAL:$1|Датотека је враћена|$1 датотеке су враћене|$1 датотека је враћено}}',
'cannotundelete' => 'Враћање није успело:
$1',
'undeletedpage' => "'''Страница $1 је враћена'''

Погледајте [[Special:Log/delete|историју брисања]] за записе о скорашњим брисањима и враћањима.",
'undelete-header' => 'Погледајте [[Special:Log/delete|историјат брисања]] за недавно обрисане странице.',
'undelete-search-title' => 'Претрага обрисаних страница',
'undelete-search-box' => 'Претражи обрисане странице',
'undelete-search-prefix' => 'Прикажи странице које почињу са:',
'undelete-search-submit' => 'Претражи',
'undelete-no-results' => 'Одговарајућа страница у дневнику брисања није пронађена.',
'undelete-filename-mismatch' => 'Не могу да вратим измену датотеке од $1: назив датотеке се не поклапа',
'undelete-bad-store-key' => 'Не могу да вратим измену датотеке од $1: датотека је недостајала пре брисања.',
'undelete-cleanup-error' => 'Грешка при брисању некоришћене архиве „$1“.',
'undelete-missing-filearchive' => 'Не могу да вратим архиву с ИБ $1 јер се она не налази у бази података.
Можда је већ била враћена.',
'undelete-error' => 'Дошло је до грешке при враћању обрисане странице',
'undelete-error-short' => 'Грешка при враћању датотеке: $1',
'undelete-error-long' => 'Дошло је до грешке при враћању датотеке:

$1',
'undelete-show-file-confirm' => 'Желите ли да видите обрисану измену датотеке „<nowiki>$1</nowiki>“ од $2; $3?',
'undelete-show-file-submit' => 'Да',

# Namespace form on various pages
'namespace' => 'Именски простор:',
'invert' => 'Обрни избор',
'tooltip-invert' => 'Означите ову кућицу да бисте сакрили измене на страницама у одабраном именском простору (и повезаним именским просторима, ако је означено)',
'namespace_association' => 'Повезани именски простор',
'tooltip-namespace_association' => 'Означите ову кућицу да бисте укључили и разговор или именски простор теме која је повезана с одабраним именским простором',
'blanknamespace' => '(Главно)',

# Contributions
'contributions' => '{{GENDER:$1|Кориснички}} доприноси',
'contributions-title' => 'Доприноси {{GENDER:$1|корисника|кориснице|корисника}} $1',
'mycontris' => 'Доприноси',
'contribsub2' => 'За {{GENDER:$3|$1}} ($2)',
'nocontribs' => 'Измене које одговарају овим условима нису пронађене.',
'uctop' => '(последња)',
'month' => 'од месеца (и раније):',
'year' => 'од године (и раније):',

'sp-contributions-newbies' => 'Прикажи само доприносе нових корисника',
'sp-contributions-newbies-sub' => 'За нове кориснике',
'sp-contributions-newbies-title' => 'Доприноси нових корисника',
'sp-contributions-blocklog' => 'дневник блокирања',
'sp-contributions-deleted' => 'обрисани кориснички доприноси',
'sp-contributions-uploads' => 'отпремања',
'sp-contributions-logs' => 'дневници',
'sp-contributions-talk' => 'разговор',
'sp-contributions-userrights' => 'управљање корисничким правима',
'sp-contributions-blocked-notice' => 'Овај корисник је блокиран. Испод су наведени последњи записи у дневнику блокирања:',
'sp-contributions-blocked-notice-anon' => 'Овој ИП адреси је тренутно забрањен приступ.
Извештај о блокираним корисницима се налази испод:',
'sp-contributions-search' => 'Претрага доприноса',
'sp-contributions-username' => 'ИП адреса или корисничко име:',
'sp-contributions-toponly' => 'Прикажи само најновије измене',
'sp-contributions-submit' => 'Претражи',

# What links here
'whatlinkshere' => 'Шта води овде',
'whatlinkshere-title' => 'Странице које су повезане са „$1“',
'whatlinkshere-page' => 'Страница:',
'linkshere' => "Следеће странице имају везу до '''[[:$1]]''':",
'nolinkshere' => "Ниједна страница није повезана са: '''[[:$1]]'''.",
'nolinkshere-ns' => "Ниједна страница не води до '''[[:$1]]''' у изабраном именском простору.",
'isredirect' => 'преусмерење',
'istemplate' => 'укључивање',
'isimage' => 'веза до датотеке',
'whatlinkshere-prev' => '{{PLURAL:$1|претходни|претходних $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|следећи|следећих $1}}',
'whatlinkshere-links' => '← везе',
'whatlinkshere-hideredirs' => '$1 преусмерења',
'whatlinkshere-hidetrans' => '$1 укључивања',
'whatlinkshere-hidelinks' => '$1 везе',
'whatlinkshere-hideimages' => '$1 везе до датотеке',
'whatlinkshere-filters' => 'Филтери',

# Block/unblock
'autoblockid' => 'Самоблокирање #$1',
'block' => 'Блокирај корисника',
'unblock' => 'Деблокирај корисника',
'blockip' => 'Блокирај корисника',
'blockip-title' => 'Блокирање корисника',
'blockip-legend' => 'Блокирај корисника',
'blockiptext' => 'Користите доњи образац да бисте забранили приступ за писање с одређене ИП адресе или корисничког имена.
Ово би требало да вршите само ради спречавања вандализма, у складу са [[{{MediaWiki:Policy-url}}|смерницама]].
Изаберите конкретан разлог испод (пример: навођење конкретних страница које су вандализоване).',
'ipadressorusername' => 'ИП адреса или корисничко име:',
'ipbexpiry' => 'Истиче:',
'ipbreason' => 'Разлог:',
'ipbreasonotherlist' => 'Други разлог',
'ipbreason-dropdown' => '*Најчешћи разлози за блокирање
** Уношење лажних информација
** Уклањање садржаја са страница
** Постављање веза до спољашњих сајтова
** Уношење бесмислица у странице
** Непристојно понашање
** Употреба више налога
** Неприхватљиво корисничко име',
'ipb-hardblock' => 'Забрани пријављеним корисницима да уређују с ове ИП адресе',
'ipbcreateaccount' => 'Онемогући отварање налога',
'ipbemailban' => 'Онемогући кориснику да шаље е-поруке',
'ipbenableautoblock' => 'Аутоматски блокирај последњу ИП адресу овог корисника и све даљње адресе с којих покуша да уређује',
'ipbsubmit' => 'Блокирај овог корисника',
'ipbother' => 'Друго време:',
'ipboptions' => '2 сата:2 hours,1 дан:1 day,3 дана:3 days,1 недеља:1 week,2 недеље:2 weeks,1 месец:1 month,3 месеца:3 months,6 месеци:6 months,1 година:1 year,трајно:infinite',
'ipbotheroption' => 'друго',
'ipbotherreason' => 'Други/додатни разлог:',
'ipbhidename' => 'Сакриј корисничко име са измена и спискова',
'ipbwatchuser' => 'Надгледај корисничке странице и странице за разговор овог корисника',
'ipb-disableusertalk' => 'Забрани овом кориснику да уређује своју страницу за разговор док је блокиран',
'ipb-change-block' => 'Поново блокирај корисника с овим поставкама',
'ipb-confirm' => 'Потврди блокирање',
'badipaddress' => 'Неисправна IP адреса',
'blockipsuccesssub' => 'Блокирање је успело',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] је {{GENDER:$1|блокиран|блокирана|блокиран}}.<br />
Блокирања можете да погледате [[Special:BlockList|овде]].',
'ipb-blockingself' => 'Овом радњом ћете блокирати себе! Јесте ли сигурни да то желите?',
'ipb-confirmhideuser' => 'Управо ћете блокирати корисника с укљученом могућношћу „сакриј корисника“. Овим ће корисничко име бити сакривено у свим списковима и извештајима. Желите ли то да урадите?',
'ipb-edit-dropdown' => 'Уреди разлоге блокирања',
'ipb-unblock-addr' => 'Деблокирај $1',
'ipb-unblock' => 'Деблокирај корисничко име или ИП адресу',
'ipb-blocklist' => 'Погледај постојећа блокирања',
'ipb-blocklist-contribs' => 'Доприноси за $1',
'unblockip' => 'Деблокирај корисника',
'unblockiptext' => 'Користите образац испод да бисте вратили право писања блокираној IP адреси или корисничком имену.',
'ipusubmit' => 'Уклони ову блокаду',
'unblocked' => '[[User:$1|$1]] је деблокиран',
'unblocked-range' => '$1 је {{GENDER:$1|деблокиран|деблокирана|деблокиран}}',
'unblocked-id' => 'Блокирање $1 је уклоњено',
'blocklist' => 'Блокирани корисници',
'ipblocklist' => 'Блокирани корисници',
'ipblocklist-legend' => 'Проналажење блокираног корисника',
'blocklist-userblocks' => 'Сакриј блокирања налога',
'blocklist-tempblocks' => 'Сакриј привремена блокирања',
'blocklist-addressblocks' => 'Сакриј појединачна блокирања ИП адресе',
'blocklist-rangeblocks' => 'Сакриј блокирања опсега',
'blocklist-timestamp' => 'Време и датум',
'blocklist-target' => 'Корисник',
'blocklist-expiry' => 'Истиче',
'blocklist-by' => 'Блокирао',
'blocklist-params' => 'Забрањене радње',
'blocklist-reason' => 'Разлог',
'ipblocklist-submit' => 'Претражи',
'ipblocklist-localblock' => 'Локално блокирање',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Друго блокирање|Друга блокирања}}',
'infiniteblock' => 'никада',
'expiringblock' => 'истиче дана $1 у $2',
'anononlyblock' => 'само анонимни',
'noautoblockblock' => 'аутоматско блокирање је онемогућено',
'createaccountblock' => 'отварање налога је блокирано',
'emailblock' => 'е-пошта је блокирана',
'blocklist-nousertalk' => 'забрањено уређивање сопствене странице за разговор',
'ipblocklist-empty' => 'Списак блокирања је празан.',
'ipblocklist-no-results' => 'Тражена ИП адреса или корисничко име није блокирано.',
'blocklink' => 'блокирај',
'unblocklink' => 'деблокирај',
'change-blocklink' => 'промени блокирање',
'contribslink' => 'доприноси',
'emaillink' => 'пошаљи е-поруку',
'autoblocker' => 'Самоблокирани сте јер делите ИП адресу с {{GENDER:$1|корисником|корисницом|корисником}} [[User:$1|$1]].
Разлог блокирања: "\'\'\'$2\'\'\'"',
'blocklogpage' => 'Дневник блокирања',
'blocklog-showlog' => '{{GENDER:$1|Овај корисник је раније блокиран|Ова корисница је раније блокирана|Овај корисник је раније блокиран}}.
Историја блокирања се налази испод:',
'blocklog-showsuppresslog' => '{{GENDER:|Овај корисник је раније блокиран и сакривен|Ова корисница је раније блокирана и сакривена|Овај корисник је раније блокиран и сакривен}}.
Историја сакривања се налази испод:',
'blocklogentry' => '{{GENDER:|је блокирао|је блокирала|је блокирао}} „[[$1]]” с роком истицања од $2 $3',
'reblock-logentry' => '{{GENDER:|је променио|је променила|је променио}} подешавања за блокирање {{GENDER:$1|корисника|кориснице|корисника}} [[$1]] с роком истека од $2 ($3)',
'blocklogtext' => 'Ово је дневник блокирања и деблокирања корисника.
Аутоматски блокиране ИП адресе нису наведене.
Текуће забране и блокирања можете наћи [[Special:BlockList|овде]].',
'unblocklogentry' => '{{GENDER:|је деблокирао|је деблокирала|је деблокирао}} „$1“',
'block-log-flags-anononly' => 'само анонимни корисници',
'block-log-flags-nocreate' => 'онемогућено отварање налога',
'block-log-flags-noautoblock' => 'аутоматско блокирање је онемогућено',
'block-log-flags-noemail' => 'е-пошта је блокирана',
'block-log-flags-nousertalk' => 'забрањено уређивање сопствене странице за разговор',
'block-log-flags-angry-autoblock' => 'проширено аутоматско блокирање је омогућено',
'block-log-flags-hiddenname' => 'корисничко име је сакривено',
'range_block_disabled' => 'Администраторска могућност за блокирање распона ИП адреса је онемогућена.',
'ipb_expiry_invalid' => 'Време истека је неисправно.',
'ipb_expiry_temp' => 'Сакривене блокаде корисника морају бити трајне.',
'ipb_hide_invalid' => 'Не могу да потиснем овај налог. Можда има превише измена.',
'ipb_already_blocked' => '„$1“ је већ блокиран',
'ipb-needreblock' => '$1 је већ блокиран. Желите ли да промените подешавања?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Друга блокада|Друге блокаде}}',
'unblock-hideuser' => 'Не можете деблокирати овог корисника јер је његово корисничко име сакривено.',
'ipb_cant_unblock' => 'Грешка: блокада $1 не постоји. Можда је корисник деблокиран.',
'ipb_blocked_as_range' => 'Грешка: ИП адреса $1 није директно блокирана и не може да се деблокира.
Она је блокирана као део блокаде $2, која може бити деблокирана.',
'ip_range_invalid' => 'Неисправан распод ИП адреса.',
'ip_range_toolarge' => 'Опсежна блокирања већа од /$1 нису дозвољена.',
'proxyblocker' => 'Блокер посредника',
'proxyblockreason' => 'Ваша ИП адреса је блокирана јер представља отворени посредник.
Обратите се вашем добављачу интернет услуга или техничку подршку и обавестите их о овом озбиљном безбедносном проблему.',
'sorbs' => 'DNSBL',
'sorbsreason' => 'Ваша ИП адреса је наведена као отворени посредник у DNSBL-у који користи {{SITENAME}}.',
'sorbs_create_account_reason' => 'Ваша ИП адреса је наведена као отворени посредник у DNSBL-у који користи {{SITENAME}}.
Не можете да отворите налог.',
'cant-block-while-blocked' => 'Не можете да блокирате друге кориснике док сте и сами блокирани.',
'cant-see-hidden-user' => 'Члан коме желите да забраните приступ је већ блокиран и сакривен.
С обзиром на то да немате права за сакривање корисника, не можете да видите нити измените забрану.',
'ipbblocked' => 'Не можете забранити или вратити приступ другим корисницима јер сте и сами блокирани',
'ipbnounblockself' => 'Није вам дозвољено да деблокирате себе',

# Developer tools
'lockdb' => 'Закључавање базе података',
'unlockdb' => 'Откључавање базе података',
'lockdbtext' => 'Закључавање базе података ће укинути могућност свим корисницима да уређују странице, мењају подешавања, уређују спискове надгледања и све остало што захтева измене у бази.
Потврдите да је ово ваша намера и да ћете откључати базу када завршите с радом око њеног одржавања.',
'unlockdbtext' => 'Откључавање базе података ће вратити могућност свим корисницима да уређују странице, мењају подешавања, уређују спискове надгледања и све остало што захтева измене у бази.
Потврдите да је ово ваша намера.',
'lockconfirm' => 'Желим да закључам базу.',
'unlockconfirm' => 'Желим да откључам базу.',
'lockbtn' => 'Закључај базу',
'unlockbtn' => 'Откључај базу',
'locknoconfirm' => 'Нисте проверили потврдну кутију.',
'lockdbsuccesssub' => 'База је закључана',
'unlockdbsuccesssub' => 'База је откључана',
'lockdbsuccesstext' => 'База података је закључана.<br />
Сетите се да је [[Special:UnlockDB|откључате]] када завршите с одржавањем.',
'unlockdbsuccesstext' => 'База је откључана.',
'lockfilenotwritable' => 'Датотека за закључавање базе није отворена за писање.
Да бисте закључали и откључали базу, датотека мора бити доступна за писање од стране мрежног сервера.',
'databasenotlocked' => 'База није закључана.',
'lockedbyandtime' => '(од $1 дана $2 у $3)',

# Move page
'move-page' => 'Премештање „$1“',
'move-page-legend' => 'Премештање странице',
'movepagetext' => "Доњи образац ће преименовати страницу, премештајући целу историју на ново име.
Стари наслов постаће преусмерење на нови наслов.
Можете аутоматски изменити преусмерење до изворног наслова.
Погледајте [[Special:DoubleRedirects|двострука]] или [[Special:BrokenRedirects|покварена]] преусмерења.
На вама је одговорност да везе и даље иду тамо где би требало да иду.

Страница '''неће''' бити премештена ако већ постоји страница с тим именом, осим ако је она празна, садржи преусмерење или нема историју измена.
То значи да можете вратити страницу на претходно место ако погрешите, али не можете заменити постојећу страницу.

'''Пажња!'''
Ово може представљати драстичну и неочекивану измену за популарну страницу;
добро размислите о последицама пре него што наставите.",
'movepagetext-noredirectfixer' => "Доњи образац ће преименовати страницу, премештајући целу историју на ново име.
Стари наслов постаће преусмерење на нови наслов.
Погледајте [[Special:DoubleRedirects|двострука]] или [[Special:BrokenRedirects|покварена]] преусмерења.
На вама је одговорност да везе и даље иду тамо где би требало да иду.

Страница '''неће''' бити премештена ако већ постоји страница с тим именом, осим ако је она празна, садржи преусмерење или нема историју измена.
То значи да можете вратити страницу на претходно место ако погрешите, али не можете заменити постојећу страницу.

'''Пажња!'''
Ово може представљати драстичну и неочекивану измену за популарну страницу;
добро размислите о последицама пре него што наставите.",
'movepagetalktext' => 'Одговарајућа страница за разговор, ако постоји, биће аутоматски премештена заједно с њом, осим ако:
*Већ постоји страница за разговор с новим насловом, или
*Ако је означено доленаведено поље.

У овом случају мора ручно да се премести или споји, ако има потребе за тим.',
'movearticle' => 'Премести страницу:',
'moveuserpage-warning' => "'''Упозорење:''' на путу сте да преместите корисничку страницу. Имајте у виду да ће само страница бити премештена, а сам корисник ''неће'' бити преименован.",
'movenologin' => 'Нисте пријављени',
'movenologintext' => 'Морате да будете регистровани и [[Special:UserLogin|пријављени]] да бисте премештали странице.',
'movenotallowed' => 'Немате дозволу да премештате странице.',
'movenotallowedfile' => 'Немате дозволу да премештате датотеке.',
'cant-move-user-page' => 'Немате дозволу за премештање основних корисничких страница (осим подстраница).',
'cant-move-to-user-page' => 'Немате дозволу за премештање странице на вашу корисничку страницу (осим на корисничку подстраницу).',
'newtitle' => 'Нови наслов:',
'move-watch' => 'Надгледај ову страницу',
'movepagebtn' => 'Премести страницу',
'pagemovedsub' => 'Успешно премештање',
'movepage-moved' => "'''„$1“ је премештена на „$2“'''",
'movepage-moved-redirect' => 'Преусмерење је направљено.',
'movepage-moved-noredirect' => 'Стварање преусмерења је онемогућено.',
'articleexists' => 'Страница с тим именом већ постоји, или је име неисправно.
Изаберите друго име.',
'cantmove-titleprotected' => 'Не можете да преместите страницу на то место јер је жељени наслов заштићен од стварања',
'talkexists' => "'''Сама страница је премештена, али страница за разговор није јер таква већ постоји на новом наслову.
Ручно их спојите.'''",
'movedto' => 'премештена на',
'movetalk' => 'Премести и страницу за разговор',
'move-subpages' => 'Премести и подстранице (до $1)',
'move-talk-subpages' => 'Премести подстранице странице за разговор (до $1)',
'movepage-page-exists' => 'Страница $1 већ постоји и не може се заменити.',
'movepage-page-moved' => 'Страница $1 је премештена у $2.',
'movepage-page-unmoved' => 'Страница $1 не може да се премести на $2.',
'movepage-max-pages' => 'Највише $1 {{PLURAL:$1|страница је премештена|странице су премештене|страница је премештено}}, и више не може да буде аутоматски премештено.',
'movelogpage' => 'Дневник премештања',
'movelogpagetext' => 'Испод се налази списак премештања страница.',
'movesubpage' => '{{PLURAL:$1|Подстраница|Подстранице|Подстраница}}',
'movesubpagetext' => 'Ова страница има $1 {{PLURAL:$1|подстраницу приказану|подстранице приказане|подстраница приказаних}} испод.',
'movenosubpage' => 'Ова страница нема подстрана.',
'movereason' => 'Разлог:',
'revertmove' => 'врати',
'delete_and_move' => 'Обриши и премести',
'delete_and_move_text' => '== Потребно брисање ==

Одредишна страница „[[:$1]]“ већ постоји. 
Желите ли да је обришете да бисте ослободили место за преусмерење?',
'delete_and_move_confirm' => 'Да, обриши страницу',
'delete_and_move_reason' => 'Обрисано да се ослободи место за премештање из „[[$1]]“',
'selfmove' => 'Изворни и одредишни наслови су истоветни;
не могу да преместим страницу преко саме себе.',
'immobile-source-namespace' => 'Не могу да преместим странице у именском простору „$1“',
'immobile-target-namespace' => 'Не могу да преместим странице у именском простору „$1”',
'immobile-target-namespace-iw' => 'Међувики веза није исправно одредиште за премештање странице.',
'immobile-source-page' => 'Ова страница се не може преместити.',
'immobile-target-page' => 'Не могу да преместим на жељени наслов.',
'bad-target-model' => 'Жељено одредиште користи другачији модел садржаја. Не могу да претворим из $1 у $2.',
'imagenocrossnamespace' => 'Датотека се не може преместити у именски простор који не припада датотекама.',
'nonfile-cannot-move-to-file' => 'Не-датотеке не можете преместити у именски простор за датотеке',
'imagetypemismatch' => 'Екстензија нове датотеке се не поклапа с њеном врстом',
'imageinvalidfilename' => 'Циљани назив датотеке је неисправан',
'fix-double-redirects' => 'Ажурирајте сва преусмерења која воде до првобитног наслова',
'move-leave-redirect' => 'Направи преусмерење',
'protectedpagemovewarning' => "'''Упозорење:''' ова страница је заштићена, тако да само корисници с администраторским овлашћењима могу да је преместе.
За више информација, последњи запис у дневнику измена је приказан испод:",
'semiprotectedpagemovewarning' => "'''Напомена:''' ова страница је заштићена, тако да само регистровани корисници могу да је преместе.
За више информација, последњи запис у дневнику измена је приказан испод:",
'move-over-sharedrepo' => '== Датотека постоји ==
[[:$1]] се налази на дељеном складишту. Ако преместите датотеку на овај наслов, то ће заменити дељену датотеку.',
'file-exists-sharedrepo' => 'Наведени назив датотеке се већ користи у дељеном складишту.
Изаберите други назив.',

# Export
'export' => 'Извоз страница',
'exporttext' => 'Можете извести текст и историју измена одређене странице или групе страница у формату XML.
Ово онда може бити увезено у други вики који користи Медијавики софтвер преко [[Special:Import|странице за увоз]].

Да бисте извезли странице, унесите називе у оквиру испод, с једним насловом по реду, и изаберите да ли желите текућу измену и све остале, или само текућу измену с подацима о последњој измени.

У другом случају, можете користити и везу, на пример [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] за страницу [[{{MediaWiki:Mainpage}}]].',
'exportall' => 'Извези све странице',
'exportcuronly' => 'Укључи само текућу измену, не целу историју',
'exportnohistory' => "----
'''Напомена:''' извоз пуне историје страница преко овог обрасца је онемогућено из техничких разлога.",
'exportlistauthors' => 'Укључи целокупан списак доприносилаца за сваку страницу',
'export-submit' => 'Извези',
'export-addcattext' => 'Додај странице из категорије:',
'export-addcat' => 'Додај',
'export-addnstext' => 'Додај странице из именског простора:',
'export-addns' => 'Додај',
'export-download' => 'Сачувај као датотеку',
'export-templates' => 'Укључи шаблоне',
'export-pagelinks' => 'Укључи повезане странице до дубине од:',

# Namespace 8 related
'allmessages' => 'Системске поруке',
'allmessagesname' => 'Назив',
'allmessagesdefault' => 'Подразумевани текст',
'allmessagescurrent' => 'Текст поруке',
'allmessagestext' => 'Ово је списак свих системских порука које су доступне у именском простору „Медијавики“.
Посетите [//www.mediawiki.org/wiki/Localisation Медијавики локализацију] и [//translatewiki.net Транслејтвики] ако желите да помогнете у превођењу.',
'allmessagesnotsupportedDB' => "Ова страница не може да се користи јер је '''\$wgUseDatabaseMessages''' онемогућен.",
'allmessages-filter-legend' => 'Филтер',
'allmessages-filter' => 'Филтрирај по стању:',
'allmessages-filter-unmodified' => 'неизмењене',
'allmessages-filter-all' => 'све',
'allmessages-filter-modified' => 'измењене',
'allmessages-prefix' => 'Филтрирај по предметку:',
'allmessages-language' => 'Језик:',
'allmessages-filter-submit' => 'Иди',

# Thumbnails
'thumbnail-more' => 'Повећај',
'filemissing' => 'Недостаје датотека',
'thumbnail_error' => 'Грешка при стварању минијатуре: $1',
'djvu_page_error' => 'DjVu страница је недоступна',
'djvu_no_xml' => 'Не могу да преузмем XML за датотеку DjVu.',
'thumbnail-temp-create' => 'Не могу да направим привремену датотеку минијатуре',
'thumbnail-dest-create' => 'Не могу да сачувам минијатуру у одредишту',
'thumbnail_invalid_params' => 'Неисправни параметри за минијатуру',
'thumbnail_dest_directory' => 'Не могу да направим одредишну фасциклу',
'thumbnail_image-type' => 'Врста слике није подржана',
'thumbnail_gd-library' => 'Недовршене поставке графичке библиотеке: недостаје функција $1',
'thumbnail_image-missing' => 'Датотека недостаје: $1',

# Special:Import
'import' => 'Увоз страница',
'importinterwiki' => 'Међувики увоз',
'import-interwiki-text' => 'Изаберите вики и наслов странице за увоз.
Датуми и имена уредника ће бити сачувани.
Све радње при увозу с других викија су забележене у [[Special:Log/import|дневнику увоза]].',
'import-interwiki-source' => 'Извор викија/странице:',
'import-interwiki-history' => 'Копирај све старије измене ове странице',
'import-interwiki-templates' => 'Укључи све шаблоне',
'import-interwiki-submit' => 'Увези',
'import-interwiki-namespace' => 'Одредишни именски простор:',
'import-interwiki-rootpage' => 'Одредишна основна страница (необавезно):',
'import-upload-filename' => 'Назив датотеке:',
'import-comment' => 'Коментар:',
'importtext' => 'Извезите датотеку с изворног викија користећи [[Special:Export|извоз]].
Сачувајте је на рачунар и пошаљите овде.',
'importstart' => 'Увозим странице…',
'import-revision-count' => '$1 {{PLURAL:$1|измена|измене|измена}}',
'importnopages' => 'Нема страница за увоз.',
'imported-log-entries' => '{{PLURAL:$1|Увезена је $1 ставка извештаја|Увезене су $1 ставке извештаја|Увезено је $1 ставки извештаја}}.',
'importfailed' => 'Неуспешан увоз: <nowiki>$1</nowiki>',
'importunknownsource' => 'Непозната врста за увоз',
'importcantopen' => 'Не могу да отворим датотеку за увоз',
'importbadinterwiki' => 'Неисправна међувики веза',
'importnotext' => 'Празно или без текста',
'importsuccess' => 'Увожење је завршено!',
'importhistoryconflict' => 'Постоји сукобљена измена у историји (можда је ова страница већ увезена раније)',
'importnosources' => 'Није одређен ниједан међувики извор за увоз, тако да је отпремање историје онемогућено.',
'importnofile' => 'Увозна датотека није послата.',
'importuploaderrorsize' => 'Не могу да отпремим датотеку за увоз.
Датотека је већа од дозвољене величине.',
'importuploaderrorpartial' => 'Не могу да отпремим датотеку за увоз.
Датотека је само делимично послата.',
'importuploaderrortemp' => 'Не могу да пошаљем датотеку за увоз.
Недостаје привремена фасцикла.',
'import-parse-failure' => 'Погрешно рашчлањивање XML-а.',
'import-noarticle' => 'Нема странице за увоз!',
'import-nonewrevisions' => 'Све измене су претходно увезене.',
'xml-error-string' => '$1 у реду $2, колона $3 (бајт $4): $5',
'import-upload' => 'Отпремање XML података',
'import-token-mismatch' => 'Губитак података о сесији.
Покушајте поново.',
'import-invalid-interwiki' => 'Не могу да увозим с наведеног викија.',
'import-error-edit' => 'Страница „$1“ није увезена јер вам није дозвољено да је уређујете.',
'import-error-create' => 'Страница „$1“ није увезена јер вам није дозвољено да је направите.',
'import-error-interwiki' => 'Не могу да увезем страницу „$1“ јер је њен назив резервисан за спољно повезивање (међувики).',
'import-error-special' => 'Не могу да увезем страницу „$1“ јер она припада посебном именском простору које не прихвата странице.',
'import-error-invalid' => 'Не могу да увезем страницу „$1“ јер је њен назив неисправан.',
'import-error-unserialize' => 'Верзија $2 странице $1 не може бити прочитана/увезена. Записано је да верзија користи $3 тип садржаја у $4 формату.',
'import-options-wrong' => '{{PLURAL:$2|Погрешна опција|Погрешне опције}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'Наведена основна страница има неисправан наслов.',
'import-rootpage-nosubpage' => 'Именски простор „$1“ основне странице не дозвољава подстранице.',

# Import log
'importlogpage' => 'Дневник увоза',
'importlogpagetext' => 'Административни увози страница с историјама измена с других викија.',
'import-logentry-upload' => '{{GENDER:|је увезао|је увезла|уведе}} „[[$1]]“ отпремањем датотеке',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|измена|измене|измена}}',
'import-logentry-interwiki' => 'премештено с другог викија: $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|измена|измене|измена}} од $2',

# JavaScriptTest
'javascripttest' => 'Јаваскрипт тест',
'javascripttest-title' => 'Извршавање тестова за $1',
'javascripttest-pagetext-noframework' => 'Ова страница је резервисана за извршавање јаваскрипт тестова.',
'javascripttest-pagetext-unknownframework' => 'Непознати радни оквир „$1“.',
'javascripttest-pagetext-frameworks' => 'Изаберите један од следећих радних оквира: $1',
'javascripttest-pagetext-skins' => 'Изаберите с којом темом желите да покренете пробу:',
'javascripttest-qunit-intro' => 'Погледајте [$1 документацију за тестирање] на mediawiki.org.',
'javascripttest-qunit-heading' => 'Медијавикијин пакет за тестирање – QUnit',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Ваша корисничка страница',
'tooltip-pt-anonuserpage' => 'Корисничка страница за ИП адресу с које уређујете',
'tooltip-pt-mytalk' => 'Ваша страница за разговор',
'tooltip-pt-anontalk' => 'Разговор о изменама с ове ИП адресе',
'tooltip-pt-preferences' => 'Ваша подешавања',
'tooltip-pt-watchlist' => 'Списак страница које надгледате',
'tooltip-pt-mycontris' => 'Списак ваших доприноса',
'tooltip-pt-login' => 'Препоручујемо вам да се пријавите, иако то није обавезно.',
'tooltip-pt-anonlogin' => 'Препоручујемо вам да се пријавите, иако то није обавезно.',
'tooltip-pt-logout' => 'Одјавите се',
'tooltip-ca-talk' => 'Разговор о страници са садржајем',
'tooltip-ca-edit' => 'Можете да уређујете ову страницу. Користите претпреглед пре снимања',
'tooltip-ca-addsection' => 'Започните нови одељак',
'tooltip-ca-viewsource' => 'Ова страница је закључана. Можете да видите изворни код.',
'tooltip-ca-history' => 'Претходне верзије ове странице',
'tooltip-ca-protect' => 'Заштитите ову страницу',
'tooltip-ca-unprotect' => 'Промени заштиту ове странице',
'tooltip-ca-delete' => 'Обришите ову страницу',
'tooltip-ca-undelete' => 'Врати измене направљене на овој страници пре него што буде обрисана',
'tooltip-ca-move' => 'Премести ову страницу',
'tooltip-ca-watch' => 'Додај ову страницу на списак надгледања',
'tooltip-ca-unwatch' => 'Уклони ову страницу са списка надгледања',
'tooltip-search' => 'Претрага',
'tooltip-search-go' => 'Идите на страницу с овим именом, ако постоји',
'tooltip-search-fulltext' => 'Претражите странице с овим текстом',
'tooltip-p-logo' => 'Посетите насловну страну',
'tooltip-n-mainpage' => 'Посетите насловну страну',
'tooltip-n-mainpage-description' => 'Посетите насловну страну',
'tooltip-n-portal' => 'О пројекту, шта можете да радите и где да пронађете ствари',
'tooltip-n-currentevents' => 'Сазнајте више о текућим догађајима',
'tooltip-n-recentchanges' => 'Списак скорашњих измена на викију',
'tooltip-n-randompage' => 'Отворите случајну страницу',
'tooltip-n-help' => 'Место где можете да се информишете',
'tooltip-t-whatlinkshere' => 'Списак свих страница које воде овде',
'tooltip-t-recentchangeslinked' => 'Скорашње измене на страницама које су повезана с овом страницом',
'tooltip-feed-rss' => 'RSS довод ове странице',
'tooltip-feed-atom' => 'Атом довод ове странице',
'tooltip-t-contributions' => 'Погледајте списак доприноса овог корисника',
'tooltip-t-emailuser' => 'Пошаљите е-поруку овом кориснику',
'tooltip-t-upload' => 'Пошаљите датотеке',
'tooltip-t-specialpages' => 'Списак свих посебних страница',
'tooltip-t-print' => 'Издање за штампање ове странице',
'tooltip-t-permalink' => 'Стална веза ка овој измени странице',
'tooltip-ca-nstab-main' => 'Погледајте страницу са садржајем',
'tooltip-ca-nstab-user' => 'Погледајте корисничку страницу',
'tooltip-ca-nstab-media' => 'Погледајте мултимедијалну датотеку',
'tooltip-ca-nstab-special' => 'Ово је посебна страница. Не можете је мењати.',
'tooltip-ca-nstab-project' => 'Погледајте страницу пројекта',
'tooltip-ca-nstab-image' => 'Прикажи страну датотеке',
'tooltip-ca-nstab-mediawiki' => 'Погледајте системску поруку',
'tooltip-ca-nstab-template' => 'Погледајте шаблон',
'tooltip-ca-nstab-help' => 'Погледајте страницу за помоћ',
'tooltip-ca-nstab-category' => 'Погледајте страницу категорија',
'tooltip-minoredit' => 'Означите измену као мању',
'tooltip-save' => 'Сачувајте измене које сте направили',
'tooltip-preview' => 'Прегледајте своје измене. Пожељно је да користите ово дугме пре чувања',
'tooltip-diff' => 'Погледајте све измене које сте направили на тексту',
'tooltip-compareselectedversions' => 'Погледаjте разлике између две изабране измене ове странице.',
'tooltip-watch' => 'Додајте ову страницу на списак надгледања',
'tooltip-watchlistedit-normal-submit' => 'Уклони наслове',
'tooltip-watchlistedit-raw-submit' => 'Ажурирај списак',
'tooltip-recreate' => 'Поново направите страницу иако је обрисана',
'tooltip-upload' => 'Започните отпремање',
'tooltip-rollback' => '„Врати“ враћа измене последњег корисника једним кликом',
'tooltip-undo' => 'Враћа ову измену и отвара образац за уређивање.',
'tooltip-preferences-save' => 'Сачувај поставке',
'tooltip-summary' => 'Унесите кратак опис',

# Stylesheets
'common.css' => '/** CSS постављен овде ће се одразити на све теме */',
'cologneblue.css' => '/* CSS постављен овде ће утицати на све кориснике теме „Келнско плава“ */',
'monobook.css' => '/* CSS постављен овде ће утицати на све кориснике теме „Монобук“ */',
'modern.css' => '/* CSS постављен овде ће утицати на све кориснике теме „Савремено“ */',
'vector.css' => '/* CSS постављен овде ће утицати на све кориснике теме „Векторско“ */',
'print.css' => '/* CSS постављен овде ће утицати на издање за штампу */',
'noscript.css' => '/* CSS постављен овде ће утицати на све кориснике којима је онемогућен јаваскрипт */',
'group-autoconfirmed.css' => '/* CSS постављен овде ће утицати на самопотврђене кориснике */',
'group-bot.css' => '/* CSS постављен овде ће утицати само на ботове */',
'group-sysop.css' => '/* CSS постављен овде ће утицати само на системске операторе */',
'group-bureaucrat.css' => '/* CSS постављен овде ће утицати само на бирократе */',

# Scripts
'common.js' => '/* Јаваскрипт постављен овде ће се користити за све кориснике при отварању сваке странице. */',
'cologneblue.js' => '/* Јаваскрипт постављен овде ће се учитати за све оне који користе тему „Келнско плава“ */',
'monobook.js' => '/* Јаваскрипт постављен овде ће се учитати за све оне који користе тему „Монобук“ */',
'modern.js' => '/* Јаваскрипт постављен овде ће се учитати за све оне који користе тему „Савремено“ */',
'vector.js' => '/* Јаваскрипт постављен овде ће се учитати за све оне који користе тему „Векторско“ */',
'group-autoconfirmed.js' => '/* Јаваскрипт постављен овде ће се учитати за самопотврђене кориснике */',
'group-bot.js' => '/* Јаваскрипт постављен овде ће се учитати само за ботове */',
'group-sysop.js' => '/* Јаваскрипт постављен овде ће се учитати само за системске операторе */',
'group-bureaucrat.js' => '/* Јаваскрипт постављен овде ће се учитати само за бирократе */',

# Metadata
'notacceptable' => 'Сервер не може да направи податке у формату погодном за ваш клијент.',

# Attribution
'anonymous' => 'Анонимни {{PLURAL:$1|корисник|корисници}} на пројекту {{SITENAME}}',
'siteuser' => '{{SITENAME}} корисник $1',
'anonuser' => '{{SITENAME}} анониман корисник $1',
'lastmodifiedatby' => 'Ову страницу је последњи пут {{GENDER:$4|изменио|изменила|изменио}} $3, $1 у $2.',
'othercontribs' => 'Засновано на раду корисникâ $1.',
'others' => 'други',
'siteusers' => '{{PLURAL:$2|корисник|корисници}} на пројекту {{SITENAME}} $1',
'anonusers' => '{{PLURAL:$2|анониман корисник|анонимни корисници}} на пројекту {{SITENAME}} $1',
'creditspage' => 'Аутори странице',
'nocredits' => 'Не постоје подаци о аутору ове странице.',

# Spam protection
'spamprotectiontitle' => 'Филтер за заштиту од непожељних порука',
'spamprotectiontext' => 'Страница коју желите да сачувате је блокирана од филтера против непожељних порука.
Ово је вероватно изазвано везом до спољашњег сајта који се налази на црној листи.',
'spamprotectionmatch' => 'Следећи текст је изазвао наш филтер за непожељне поруке: $1',
'spambot_username' => 'Чишћење непожељних порука у Медијавикији',
'spam_reverting' => 'Враћам на последњу измену која не садржи везе до $1',
'spam_blanking' => 'Све измене садрже везе до $1. Чистим',
'spam_deleting' => 'Све измене садрже везе до $1. Бришем',

# Info page
'pageinfo-title' => 'Подаци о „$1“',
'pageinfo-not-current' => 'Нажалост, немогуће је прибавити ове податке за старије измене.',
'pageinfo-header-basic' => 'Основни подаци',
'pageinfo-header-edits' => 'Историја измена',
'pageinfo-header-restrictions' => 'Заштита странице',
'pageinfo-header-properties' => 'Својства странице',
'pageinfo-display-title' => 'Наслов за приказ',
'pageinfo-default-sort' => 'Подразумевани кључ сортирања',
'pageinfo-length' => 'Дужина странице (у бајтовима)',
'pageinfo-article-id' => 'ИД странице',
'pageinfo-language' => 'Језик садржаја странице',
'pageinfo-robot-policy' => 'Индексирање од стране робота',
'pageinfo-robot-index' => 'Дозвољено',
'pageinfo-robot-noindex' => 'Није дозвољено',
'pageinfo-views' => 'Број прегледа',
'pageinfo-watchers' => 'Број надгледача страница',
'pageinfo-few-watchers' => 'Мање од $1 {{PLURAL:$1|пратиоца|пратилаца}}',
'pageinfo-redirects-name' => 'Број преусмерења на ову страницу',
'pageinfo-subpages-name' => 'Подстранице ове странице',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|преусмерење|преусмерења|преусмерења}}; $3 {{PLURAL:$3|непреусмерење|непреусмерења|непреусмерења}})',
'pageinfo-firstuser' => 'Аутор странице',
'pageinfo-firsttime' => 'Датум стварања странице',
'pageinfo-lastuser' => 'Последњи уредник',
'pageinfo-lasttime' => 'Датум последње измене',
'pageinfo-edits' => 'Број измена',
'pageinfo-authors' => 'Број засебних аутора',
'pageinfo-recent-edits' => 'Број скорашњих измена (у последњих $1)',
'pageinfo-recent-authors' => 'Број скорашњих засебних аутора',
'pageinfo-magic-words' => '{{PLURAL:$1|Магична реч|Магичне речи}} ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Сакривена категорија|Сакривене категорије}} ($1)',
'pageinfo-templates' => '{{PLURAL:$1|Укључени шаблон|Укључени шаблони}} ($1)',
'pageinfo-transclusions' => '{{PLURAL:$1|Страница|Странице}} укључене у ($1)',
'pageinfo-toolboxlink' => 'Подаци о страници',
'pageinfo-redirectsto' => 'Преусмерава на',
'pageinfo-redirectsto-info' => 'подаци',
'pageinfo-contentpage' => 'Рачуна се као страница са садржајем',
'pageinfo-contentpage-yes' => 'Да',
'pageinfo-protect-cascading' => 'Преносива заштита страница важи одавде',
'pageinfo-protect-cascading-yes' => 'Да',
'pageinfo-protect-cascading-from' => 'Странице са преносивом заштитом од',
'pageinfo-category-info' => 'Информације о категорији',
'pageinfo-category-pages' => 'Број страница',
'pageinfo-category-subcats' => 'Број поткатегорија',
'pageinfo-category-files' => 'Број датотека',

# Skin names
'skinname-cologneblue' => 'Келнско плава',
'skinname-monobook' => 'Монобук',
'skinname-modern' => 'Савремено',
'skinname-vector' => 'Векторско',

# Patrolling
'markaspatrolleddiff' => 'Означи као патролирано',
'markaspatrolledtext' => 'Означи страницу као патролирану',
'markedaspatrolled' => 'Означено као патролирано',
'markedaspatrolledtext' => 'Изабрана измена на [[:$1]] је означена као прегледана.',
'rcpatroldisabled' => 'Патролирање скорашњих измена је онемогућено',
'rcpatroldisabledtext' => 'Патролирање скорашњих измена је онемогућено.',
'markedaspatrollederror' => 'Не могу да означим као патролирано',
'markedaspatrollederrortext' => 'Морате изабрати измену да бисте је означили као прегледану.',
'markedaspatrollederror-noautopatrol' => 'Не можете да означите своје измене као патролиране.',
'markedaspatrollednotify' => 'Ова измена на страници „$1“ је означена као прегледана.',
'markedaspatrollederrornotify' => 'Није успешно означавање ове странице као прегледаном.',

# Patrol log
'patrol-log-page' => 'Дневник патролирања',
'patrol-log-header' => 'Ово је дневник патролираних измена.',
'log-show-hide-patrol' => '$1 дневник патролирања',

# Image deletion
'deletedrevision' => 'Обрисана стара измена $1.',
'filedeleteerror-short' => 'Грешка при брисању датотеке: $1',
'filedeleteerror-long' => 'Дошло је до грешака при брисању датотеке:

$1',
'filedelete-missing' => 'Датотека „$1“ се не може обрисати јер не постоји.',
'filedelete-old-unregistered' => 'Наведена измена датотеке „$1“ не постоји у бази података.',
'filedelete-current-unregistered' => 'Наведена датотека „$1“ не постоји у бази података.',
'filedelete-archive-read-only' => 'Сервер не може да пише по складишној фасцикли ($1).',

# Browsing diffs
'previousdiff' => '← Старија измена',
'nextdiff' => 'Новија измена →',

# Media information
'mediawarning' => "'''Упозорење''': ова врста датотеке може садржати штетан код.
Ако га покренете, ваш рачунар може бити угрожен.",
'imagemaxsize' => "Ограничење величине слике:<br />''(на страницама за опис датотека)''",
'thumbsize' => 'Величина минијатуре:',
'widthheight' => '$1 × $2',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|страница|странице|страница}}',
'file-info' => 'величина датотеке: $1, MIME тип: $2',
'file-info-size' => '$1 × $2 пиксела, величина датотеке: $3, MIME тип: $4',
'file-info-size-pages' => '$1 × $2 пиксела, величина: $3, MIME врста: $4, $5 {{PLURAL:$5|страница|странице|страница}}',
'file-nohires' => 'Већа резолуција није доступна.',
'svg-long-desc' => 'SVG датотека, номинално $1 × $2 пиксела, величина: $3',
'svg-long-desc-animated' => 'Анимирана SVG датотека, номинално: $1 × $2 пиксела, величина: $3',
'svg-long-error' => 'Неисправна SVG датотека: $1',
'show-big-image' => 'Пуна величина',
'show-big-image-preview' => 'Величина овог приказа: $1.',
'show-big-image-other' => '{{PLURAL:$2|Друга резолуција|Друге резолуције}}: $1.',
'show-big-image-size' => '$1 × $2 пиксела',
'file-info-gif-looped' => 'петља',
'file-info-gif-frames' => '$1 {{PLURAL:$1|кадар|кадра|кадрова}}',
'file-info-png-looped' => 'петља',
'file-info-png-repeat' => 'поновљено $1 {{PLURAL:$1|пут|пута|пута}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|кадар|кадра|кадрова}}',
'file-no-thumb-animation' => "'''Напомена: због техничких ограничења, минијатуре ове датотеке се неће анимирати.'''",
'file-no-thumb-animation-gif' => "'''Напомена: због техничких ограничења, минијатуре GIF слика високе резолуције као што је ова неће се анимирати.'''",

# Special:NewFiles
'newimages' => 'Галерија нових датотека',
'imagelisttext' => "Испод је списак од '''$1''' {{PLURAL:$1|датотеке|датотеке|датотека}} поређаних $2.",
'newimages-summary' => 'Ова посебна страница приказује последње послате датотеке.',
'newimages-legend' => 'Филтер',
'newimages-label' => 'Назив датотеке (или њен део):',
'showhidebots' => '($1 ботове)',
'noimages' => 'Нема ништа.',
'ilsubmit' => 'Претражи',
'bydate' => 'по датуму',
'sp-newimages-showfrom' => 'прикажи нове датотеке почевши од $1, $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1, $2×$3',
'seconds-abbrev' => '$1 с',
'minutes-abbrev' => '$1 м',
'hours-abbrev' => '$1 ч',
'days-abbrev' => '$1 д',
'seconds' => '{{PLURAL:$1|$1 секунда|$1 секунде|$1 секунди}}',
'minutes' => '{{PLURAL:$1|$1 минут|$1 минута|$1 минута}}',
'hours' => '{{PLURAL:$1|$1 сат|$1 сата|$1 сати}}',
'days' => '{{PLURAL:$1|$1 дан|$1 дана|$1 дана}}',
'weeks' => '{{PLURAL:$1|$1 седмица}}',
'months' => '{{PLURAL:$1|$1 месец|$1 месеци}}',
'years' => '{{PLURAL:$1|$1 година|$1 године}}',
'ago' => 'пре $1',
'just-now' => 'управо сад',

# Human-readable timestamps
'hours-ago' => 'Пре $1 {{PLURAL:$1|сат|сати}}',
'minutes-ago' => 'Пре $1 {{PLURAL:$1|минут|минута}}',
'seconds-ago' => 'Пре $1 {{PLURAL:$1|секунда|секунди}}',
'monday-at' => 'Понедељак у $1',
'tuesday-at' => 'Уторак у $1',
'wednesday-at' => 'Среда у $1',
'thursday-at' => 'Четвртак у $1',
'friday-at' => 'Петак у $1',
'saturday-at' => 'Субота у $1',
'sunday-at' => 'Недеља у $1',
'yesterday-at' => 'Јуче у $1',

# Bad image list
'bad_image_list' => 'Формат је следећи:

Разматрају се само набрајања (редови који почињу са звездицом).
Прва веза у реду мора да буде веза до неисправне датотеке.
Све даљње везе у истом реду сматрају се изузецима.',

/*
Short names for language variants used for language conversion links.
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',
'variantname-zh-hant' => 'hant',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-mo' => 'mo',
'variantname-zh-sg' => 'sg',
'variantname-zh-my' => 'my',
'variantname-zh' => 'zh',

# Variants for Gan language
'variantname-gan-hans' => 'hans',
'variantname-gan-hant' => 'hant',
'variantname-gan' => 'gan',

# Variants for Serbian language
'variantname-sr-ec' => 'Ћирилица',
'variantname-sr-el' => 'Latinica',
'variantname-sr' => 'sr',

# Variants for Kazakh language
'variantname-kk-kz' => 'kk-kz',
'variantname-kk-tr' => 'kk-tr',
'variantname-kk-cn' => 'kk-cn',
'variantname-kk-cyrl' => 'kk-cyrl',
'variantname-kk-latn' => 'kk-latn',
'variantname-kk-arab' => 'kk-arab',
'variantname-kk' => 'kk',

# Variants for Kurdish language
'variantname-ku-arab' => 'ku-Arab',
'variantname-ku-latn' => 'ku-Latn',
'variantname-ku' => 'ku',

# Variants for Tajiki language
'variantname-tg-cyrl' => 'tg-Cyrl',
'variantname-tg-latn' => 'tg-Latn',
'variantname-tg' => 'tg',

# Variants for Inuktitut language
'variantname-ike-cans' => 'ike-Cans',
'variantname-ike-latn' => 'ike-Latn',
'variantname-iu' => 'iu',

# Variants for Tachelhit language
'variantname-shi-tfng' => 'shi-Tfng',
'variantname-shi-latn' => 'shi-Latn',
'variantname-shi' => 'shi',

# Metadata
'metadata' => 'Метаподаци',
'metadata-help' => 'Ова датотека садржи додатне податке који вероватно долазе од дигиталног фотоапарата или скенера.
Ако је првобитно стање датотеке промењено, могуће је да неки детаљи не описују измењену датотеку.',
'metadata-expand' => 'Прикажи детаље',
'metadata-collapse' => 'Сакриј детаље',
'metadata-fields' => 'Поља за метаподатке слике наведена у овој поруци ће бити укључена на страници за слике када се скупи табела метаподатака. Остала поља ће бити сакривена по подразумеваним поставкама.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',
'metadata-langitem' => "'''$2:''' $1",
'metadata-langitem-default' => '$1',

# Exif tags
'exif-imagewidth' => 'Ширина',
'exif-imagelength' => 'Висина',
'exif-bitspersample' => 'Дубина боје',
'exif-compression' => 'Начин сажимања',
'exif-photometricinterpretation' => 'Састав пиксела',
'exif-orientation' => 'Оријентација',
'exif-samplesperpixel' => 'Број делова',
'exif-planarconfiguration' => 'Распоред података',
'exif-ycbcrsubsampling' => 'Однос величине Y према C',
'exif-ycbcrpositioning' => 'Положај Y и C',
'exif-xresolution' => 'Водоравна резолуција',
'exif-yresolution' => 'Усправна резолуција',
'exif-stripoffsets' => 'Место података',
'exif-rowsperstrip' => 'Број редова по линији',
'exif-stripbytecounts' => 'Бајтова по сажетом блоку',
'exif-jpeginterchangeformat' => 'Почетак JPEG прегледа',
'exif-jpeginterchangeformatlength' => 'Бајтови JPEG података',
'exif-whitepoint' => 'Хроматичност беле тачке',
'exif-primarychromaticities' => 'Хроматичност основних боја',
'exif-ycbcrcoefficients' => 'Матрични коефицијенти трансформације бојног простора',
'exif-referenceblackwhite' => 'Упутне вредности пара беле и црне тачке',
'exif-datetime' => 'Датум и време последње измене датотеке',
'exif-imagedescription' => 'Назив слике',
'exif-make' => 'Произвођач камере',
'exif-model' => 'Модел камере',
'exif-software' => 'Коришћени софтвер',
'exif-artist' => 'Аутор',
'exif-copyright' => 'Носилац ауторских права',
'exif-exifversion' => 'Exif издање',
'exif-flashpixversion' => 'Подржано издање FlashPix-а',
'exif-colorspace' => 'Простор боје',
'exif-componentsconfiguration' => 'Значење сваког дела',
'exif-compressedbitsperpixel' => 'Режим сажимања слике',
'exif-pixelydimension' => 'Ширина слике',
'exif-pixelxdimension' => 'Висина слике',
'exif-usercomment' => 'Кориснички коментари',
'exif-relatedsoundfile' => 'Повезани звучни запис',
'exif-datetimeoriginal' => 'Датум и време сликања',
'exif-datetimedigitized' => 'Датум и време дигитализације',
'exif-subsectime' => 'Део секунде у којем је сликано',
'exif-subsectimeoriginal' => 'Део секунде у којем је фотографисано',
'exif-subsectimedigitized' => 'Део секунде у којем је дигитализовано',
'exif-exposuretime' => 'Експозиција',
'exif-exposuretime-format' => '$1 сек. ($2)',
'exif-fnumber' => 'F број',
'exif-fnumber-format' => 'f/$1',
'exif-exposureprogram' => 'Режим експозиције',
'exif-spectralsensitivity' => 'Спектрална осетљивост',
'exif-isospeedratings' => 'ISO осетљивост на светло',
'exif-shutterspeedvalue' => 'Брзина затварача',
'exif-aperturevalue' => 'Отвор бленде',
'exif-brightnessvalue' => 'Осветљеност',
'exif-exposurebiasvalue' => 'Компензација експозиције',
'exif-maxaperturevalue' => 'Највећи број отвора бленде',
'exif-subjectdistance' => 'Удаљеност до објекта',
'exif-meteringmode' => 'Режим мерача',
'exif-lightsource' => 'Извор светлости',
'exif-flash' => 'Блиц',
'exif-focallength' => 'Жаришна даљина сочива',
'exif-focallength-format' => '$1 мм',
'exif-subjectarea' => 'Положај и површина објекта',
'exif-flashenergy' => 'Енергија блица',
'exif-focalplanexresolution' => 'Резолуција фокусне равни X',
'exif-focalplaneyresolution' => 'Резолуција фокусне равни Y',
'exif-focalplaneresolutionunit' => 'Јединица за резолуцију фокусне равни',
'exif-subjectlocation' => 'Положај објекта',
'exif-exposureindex' => 'Попис експозиције',
'exif-sensingmethod' => 'Врста сензора',
'exif-filesource' => 'Изворна датотека',
'exif-scenetype' => 'Врста сцене',
'exif-customrendered' => 'Прилагођена обрада слика',
'exif-exposuremode' => 'Режим експозиције',
'exif-whitebalance' => 'Бела равнотежа',
'exif-digitalzoomratio' => 'Однос дигиталног увеличања',
'exif-focallengthin35mmfilm' => 'Жаришна даљина за филм од 35 мм',
'exif-scenecapturetype' => 'Врста снимања сцена',
'exif-gaincontrol' => 'Контрола сцене',
'exif-contrast' => 'Контраст',
'exif-saturation' => 'Засићење',
'exif-sharpness' => 'Оштрина',
'exif-devicesettingdescription' => 'Опис поставки уређаја',
'exif-subjectdistancerange' => 'Опсег удаљености објекта',
'exif-imageuniqueid' => 'Назнака слике',
'exif-gpsversionid' => 'Издање GPS ознаке',
'exif-gpslatituderef' => 'Северна или јужна ширина',
'exif-gpslatitude' => 'Ширина',
'exif-gpslongituderef' => 'Источна или западна дужина',
'exif-gpslongitude' => 'Дужина',
'exif-gpsaltituderef' => 'Упутна тачка за висину',
'exif-gpsaltitude' => 'Висина',
'exif-gpstimestamp' => 'GPS време (атомски сат)',
'exif-gpssatellites' => 'Коришћени сателити',
'exif-gpsstatus' => 'Стање пријемника',
'exif-gpsmeasuremode' => 'Режим мерења',
'exif-gpsdop' => 'Прецизност мерења',
'exif-gpsspeedref' => 'Јединица за брзину',
'exif-gpsspeed' => 'Брзина GPS пријемника',
'exif-gpstrackref' => 'Упут за правац кретања',
'exif-gpstrack' => 'Правац кретања',
'exif-gpsimgdirectionref' => 'Упут за правац слике',
'exif-gpsimgdirection' => 'Правац слике',
'exif-gpsmapdatum' => 'Коришћени геодетски координатни систем',
'exif-gpsdestlatituderef' => 'Индекс за географску ширину објекта',
'exif-gpsdestlatitude' => 'Географска ширина објекта',
'exif-gpsdestlongituderef' => 'Индекс за географску дужину објекта',
'exif-gpsdestlongitude' => 'Географска дужина објекта',
'exif-gpsdestbearingref' => 'Индекс азимута објекта',
'exif-gpsdestbearing' => 'Азимут објекта',
'exif-gpsdestdistanceref' => 'Мерна јединица за удаљеност објекта',
'exif-gpsdestdistance' => 'Удаљеност објекта',
'exif-gpsprocessingmethod' => 'Име начина обраде GPS података',
'exif-gpsareainformation' => 'Име GPS подручја',
'exif-gpsdatestamp' => 'GPS датум',
'exif-gpsdifferential' => 'GPS диференцијална исправка',
'exif-coordinate-format' => '$1° $2′ $3″ $4',
'exif-jpegfilecomment' => 'Коментар на датотеку JPEG',
'exif-keywords' => 'Кључне речи',
'exif-worldregioncreated' => 'Област света где је сликана фотографија',
'exif-countrycreated' => 'Земља где је сликана фотографија',
'exif-countrycodecreated' => 'Код земље где је слика направљена',
'exif-provinceorstatecreated' => 'Покрајина или држава где је сликана фотографија',
'exif-citycreated' => 'Град где је сликана фотографија',
'exif-sublocationcreated' => 'Област града где је сликана фотографија',
'exif-worldregiondest' => 'Приказана област света',
'exif-countrydest' => 'Приказана земља',
'exif-countrycodedest' => 'Код приказане земље',
'exif-provinceorstatedest' => 'Приказана покрајина или држава',
'exif-citydest' => 'Приказани град',
'exif-sublocationdest' => 'Приказана област града',
'exif-objectname' => 'Кратак наслов',
'exif-specialinstructions' => 'Посебна упутства',
'exif-headline' => 'Наслов',
'exif-credit' => 'Заслуге/пружалац услуга',
'exif-source' => 'Извор',
'exif-editstatus' => 'Уреднички статус слике',
'exif-urgency' => 'Хитност',
'exif-fixtureidentifier' => 'Назив рубрике',
'exif-locationdest' => 'Приказана локација',
'exif-locationdestcode' => 'Код приказаног места',
'exif-objectcycle' => 'Доба дана за који је медиј намењен',
'exif-contact' => 'Подаци за контакт',
'exif-writer' => 'Писац',
'exif-languagecode' => 'Језик',
'exif-iimversion' => 'IIM издање',
'exif-iimcategory' => 'Категорија',
'exif-iimsupplementalcategory' => 'Допунске категорије',
'exif-datetimeexpires' => 'Не користи након',
'exif-datetimereleased' => 'Објављено',
'exif-originaltransmissionref' => 'Изворни пренос кôда локације',
'exif-identifier' => 'Назнака',
'exif-lens' => 'Коришћени објектив',
'exif-serialnumber' => 'Серијски број камере',
'exif-cameraownername' => 'Власник камере',
'exif-label' => 'Назив',
'exif-datetimemetadata' => 'Датум последње измене метаподатака',
'exif-nickname' => 'Неформалан назив слике',
'exif-rating' => 'Оцена (од 1 до 5)',
'exif-rightscertificate' => 'Потврда за управљање правима',
'exif-copyrighted' => 'Статус ауторског права',
'exif-copyrightowner' => 'Носилац ауторског права',
'exif-usageterms' => 'Правила коришћења',
'exif-webstatement' => 'Изјава о ауторском праву',
'exif-originaldocumentid' => 'Јединствени ИБ изворног документа',
'exif-licenseurl' => 'Адреса лиценце за ауторска права',
'exif-morepermissionsurl' => 'Резервни подаци о лиценцирању',
'exif-attributionurl' => 'При поновном коришћењу овог рада, користите везу до',
'exif-preferredattributionname' => 'При поновном коришћењу овог рада, поставите заслуге',
'exif-pngfilecomment' => 'Коментар на датотеку PNG',
'exif-disclaimer' => 'Одрицање одговорности',
'exif-contentwarning' => 'Упозорење о садржају',
'exif-giffilecomment' => 'Коментар на датотеку GIF',
'exif-intellectualgenre' => 'Врста ставке',
'exif-subjectnewscode' => 'Код предмета',
'exif-scenecode' => 'IPTC код сцене',
'exif-event' => 'Приказани догађај',
'exif-organisationinimage' => 'Приказана организација',
'exif-personinimage' => 'Приказана особа',
'exif-originalimageheight' => 'Висина слике пре исецања',
'exif-originalimagewidth' => 'Ширина слике пре исецања',

# Make & model, can be wikified in order to link to the camera and model name
'exif-contact-value' => '$1

$2
<div class="adr">
$3

$4, $5, $6 $7
</div>
$8',
'exif-subjectnewscode-value' => '$2 ($1)',

# Exif attributes
'exif-compression-1' => 'Несажето',
'exif-compression-2' => 'CCITT Group 3 1 – Димензионално измењено Хафманово кодирање по дужини',
'exif-compression-3' => 'CCITT Group 3 факс кодирање',
'exif-compression-4' => 'CCITT Group 4 факс кодирање',
'exif-compression-5' => 'LZW',
'exif-compression-6' => 'JPEG (стари)',
'exif-compression-7' => 'JPEG',
'exif-compression-8' => 'Deflate (Адоби)',
'exif-compression-32773' => 'PackBits (Макинтош RLE)',
'exif-compression-32946' => 'Deflate (PKZIP)',
'exif-compression-34712' => 'JPEG2000',

'exif-copyrighted-true' => 'Заштићено ауторским правом',
'exif-copyrighted-false' => 'Статус ауторских права није подешен',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-unknowndate' => 'Непознат датум',

'exif-orientation-1' => 'Нормално',
'exif-orientation-2' => 'Обрнуто по хоризонтали',
'exif-orientation-3' => 'Заокренуто 180°',
'exif-orientation-4' => 'Обрнуто по вертикали',
'exif-orientation-5' => 'Заокренуто 90° улево и обрнуто по вертикали',
'exif-orientation-6' => 'Заокренуто 90° супротно од смера казаљке',
'exif-orientation-7' => 'Заокренуто 90° удесно и обрнуто по вертикали',
'exif-orientation-8' => 'Заокренуто 90° у смеру казаљке',

'exif-planarconfiguration-1' => 'груби формат',
'exif-planarconfiguration-2' => 'планарни формат',

'exif-xyresolution-i' => '$1 тпи',
'exif-xyresolution-c' => '$1 тпц',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-65535' => 'Дештеловано',

'exif-componentsconfiguration-0' => 'не постоји',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Неодређено',
'exif-exposureprogram-1' => 'Ручно',
'exif-exposureprogram-2' => 'Нормалан програм',
'exif-exposureprogram-3' => 'Приоритет отвора бленде',
'exif-exposureprogram-4' => 'Приоритет затварача',
'exif-exposureprogram-5' => 'Креативни режим (на основи потребне дубине поља)',
'exif-exposureprogram-6' => 'Спортски режим (на основи што бржег затварача)',
'exif-exposureprogram-7' => 'Портретни режим (за крупне кадрове с нејасном позадином)',
'exif-exposureprogram-8' => 'Пејзажни режим (за фотографије пејзажа с оштром позадином)',

'exif-subjectdistance-value' => '$1 метара',

'exif-meteringmode-0' => 'Непознато',
'exif-meteringmode-1' => 'Просечно',
'exif-meteringmode-2' => 'Просек с тежиштем на средини',
'exif-meteringmode-3' => 'Тачка',
'exif-meteringmode-4' => 'Више тачака',
'exif-meteringmode-5' => 'Матрични',
'exif-meteringmode-6' => 'Делимични',
'exif-meteringmode-255' => 'Друго',

'exif-lightsource-0' => 'Непознато',
'exif-lightsource-1' => 'Дневна светлост',
'exif-lightsource-2' => 'Флуоресцентно',
'exif-lightsource-3' => 'Волфрамско',
'exif-lightsource-4' => 'Блиц',
'exif-lightsource-9' => 'Ведро време',
'exif-lightsource-10' => 'Облачно време',
'exif-lightsource-11' => 'Сенка',
'exif-lightsource-12' => 'Флуоресцентна светлост (D 5700 – 7100K)',
'exif-lightsource-13' => 'Флуоресцентна светлост (N 4600 – 5400K)',
'exif-lightsource-14' => 'Флуоресцентна светлост (W 3900 – 4500K)',
'exif-lightsource-15' => 'Бела флуоресценција (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Стандардно светло типа A',
'exif-lightsource-18' => 'Стандардно светло типа B',
'exif-lightsource-19' => 'Стандардно светло типа C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO студијска лампа',
'exif-lightsource-255' => 'Други светлосни извор',

# Flash modes
'exif-flash-fired-0' => 'Блиц није активиран',
'exif-flash-fired-1' => 'Блиц је активиран',
'exif-flash-return-0' => 'без употребе функције стробоскопа',
'exif-flash-return-2' => 'није препознато позадинско осветљење са стробоскопа',
'exif-flash-return-3' => 'препознато је позадинско осветљење са стробоскопа',
'exif-flash-mode-1' => 'обавезно са блицем',
'exif-flash-mode-2' => 'обавезно без блица',
'exif-flash-mode-3' => 'аутоматски режим',
'exif-flash-function-1' => 'Нема функције за блиц',
'exif-flash-redeye-1' => 'режим исправке црвених очију',

'exif-focalplaneresolutionunit-2' => 'инчи',

'exif-sensingmethod-1' => 'Неодређено',
'exif-sensingmethod-2' => 'Једнокристални матрични сензор',
'exif-sensingmethod-3' => 'Двокристални матрични сензор',
'exif-sensingmethod-4' => 'Трокристални матрични сензор',
'exif-sensingmethod-5' => 'Секвенцијални матрични сензор',
'exif-sensingmethod-7' => 'Тробојни линеарни сензор',
'exif-sensingmethod-8' => 'Секвенцијални линеарни сензор',

'exif-filesource-3' => 'Дигитални фотоапарат',

'exif-scenetype-1' => 'Директно фотографисана слика',

'exif-customrendered-0' => 'Нормалан процес',
'exif-customrendered-1' => 'Нестандардан процес',

'exif-exposuremode-0' => 'Аутоматска експозиција',
'exif-exposuremode-1' => 'Ручна експозиција',
'exif-exposuremode-2' => 'Аутоматски са задатим распоном',

'exif-whitebalance-0' => 'Аутоматска бела равнотежа',
'exif-whitebalance-1' => 'Ручна бела равнотежа',

'exif-scenecapturetype-0' => 'Стандардно',
'exif-scenecapturetype-1' => 'Пејзаж',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Ноћна сцена',

'exif-gaincontrol-0' => 'Нема',
'exif-gaincontrol-1' => 'Мало повећање',
'exif-gaincontrol-2' => 'Велико повећање',
'exif-gaincontrol-3' => 'Мало смањење',
'exif-gaincontrol-4' => 'Велико смањење',

'exif-contrast-0' => 'Нормално',
'exif-contrast-1' => 'Меко',
'exif-contrast-2' => 'Тврдо',

'exif-saturation-0' => 'Нормално',
'exif-saturation-1' => 'Ниско засићење',
'exif-saturation-2' => 'Високо засићење',

'exif-sharpness-0' => 'Нормално',
'exif-sharpness-1' => 'Меко',
'exif-sharpness-2' => 'Тврдо',

'exif-subjectdistancerange-0' => 'Непознато',
'exif-subjectdistancerange-1' => 'Крупни кадар',
'exif-subjectdistancerange-2' => 'Блиски кадар',
'exif-subjectdistancerange-3' => 'Далеки кадар',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Северна ширина',
'exif-gpslatitude-s' => 'Јужна ширина',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Источна дужина',
'exif-gpslongitude-w' => 'Западна дужина',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|метар|метра|метара}} надморске висине',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|метар|метра|метара}} испод нивоа мора',

'exif-gpsstatus-a' => 'Мерење у току',
'exif-gpsstatus-v' => 'Спреман за пренос',

'exif-gpsmeasuremode-2' => 'Дводимензионално мерење',
'exif-gpsmeasuremode-3' => 'Тродимензионално мерење',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Километри на час',
'exif-gpsspeed-m' => 'Миље на час',
'exif-gpsspeed-n' => 'Чворови',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Километри',
'exif-gpsdestdistance-m' => 'Миље',
'exif-gpsdestdistance-n' => 'Наутичке миље',

'exif-gpsdop-excellent' => 'Одлично ($1)',
'exif-gpsdop-good' => 'Добро ($1)',
'exif-gpsdop-moderate' => 'Умерено ($1)',
'exif-gpsdop-fair' => 'Задовољавајуће ($1)',
'exif-gpsdop-poor' => 'Лоше ($1)',

'exif-objectcycle-a' => 'Само ујутру',
'exif-objectcycle-p' => 'Само увече',
'exif-objectcycle-b' => 'И ујутру и увече',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Прави правац',
'exif-gpsdirection-m' => 'Магнетни правац',

'exif-ycbcrpositioning-1' => 'Центрирано',
'exif-ycbcrpositioning-2' => 'Упоредо',

'exif-dc-contributor' => 'Доприносиоци',
'exif-dc-coverage' => 'Просторни или временски опсег медија',
'exif-dc-date' => 'Датум',
'exif-dc-publisher' => 'Издавач',
'exif-dc-relation' => 'Сродни медији',
'exif-dc-rights' => 'Права',
'exif-dc-source' => 'Извор медија',
'exif-dc-type' => 'Врста медија',

'exif-rating-rejected' => 'Одбијено',

'exif-isospeedratings-overflow' => 'Веће од 65535',

'exif-maxaperturevalue-value' => '$1 APEX (f/$2)',

'exif-iimcategory-ace' => 'Уметност, култура и забава',
'exif-iimcategory-clj' => 'Криминал и закон',
'exif-iimcategory-dis' => 'Катастрофе и несреће',
'exif-iimcategory-fin' => 'Економија и посао',
'exif-iimcategory-edu' => 'Образовање',
'exif-iimcategory-evn' => 'Околина',
'exif-iimcategory-hth' => 'Здравље',
'exif-iimcategory-hum' => 'Занимање',
'exif-iimcategory-lab' => 'Рад',
'exif-iimcategory-lif' => 'Начин живота и слободно време',
'exif-iimcategory-pol' => 'Политика',
'exif-iimcategory-rel' => 'Религија и веровања',
'exif-iimcategory-sci' => 'Наука и технологија',
'exif-iimcategory-soi' => 'Друштвена питања',
'exif-iimcategory-spo' => 'Спорт',
'exif-iimcategory-war' => 'Рат, сукоби и немири',
'exif-iimcategory-wea' => 'Време',

'exif-urgency-normal' => 'Нормално ($1)',
'exif-urgency-low' => 'Ниско ($1)',
'exif-urgency-high' => 'Високо ($1)',
'exif-urgency-other' => 'Прилагођени приоритет ($1)',

# External editor support
'edit-externally' => 'Измени ову датотеку користећи спољашњи програм',
'edit-externally-help' => '(Погледајте [//www.mediawiki.org/wiki/Manual:External_editors упутство за подешавање] за више информација)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'све',
'namespacesall' => 'све',
'monthsall' => 'све',
'limitall' => 'све',

# Email address confirmation
'confirmemail' => 'Потврда е-адресе',
'confirmemail_noemail' => 'Нисте унели исправну е-адресу у [[Special:Preferences|подешавањима]].',
'confirmemail_text' => '{{SITENAME}} захтева да потврдите е-адресу пре него што почнете да користите могућности е-поште.
Кликните на дугме испод за слање поруке на вашу е-адресу.
У поруци ће се налазити веза с потврдним кодом;
унесите је у прегледач да бисте потврдили да је ваша е-адреса исправна.',
'confirmemail_pending' => 'Потврдни код вам је већ послат. Ако сте управо отворили налог, онда вероватно треба да сачекате неколико минута да пристигне, пре него што поново затражите нови код.',
'confirmemail_send' => 'Пошаљи потврдни код',
'confirmemail_sent' => 'Потврдна порука је послата.',
'confirmemail_oncreate' => 'Послат је потврдни код на вашу е-адресу.
Овај код није потребан за пријављивање, али вам треба да бисте укључили могућности е-поште на викију.',
'confirmemail_sendfailed' => '{{SITENAME}} не може да пошаље поруку.
Проверите да ли је е-адреса правилно написана.

Грешка: $1',
'confirmemail_invalid' => 'Потврдни код је неисправан. Вероватно је истекао.',
'confirmemail_needlogin' => 'Морате бити $1 да бисте потврдили е-адресу.',
'confirmemail_success' => 'Ваша е-адреса је потврђена.
Сада можете да се [[Special:UserLogin|пријавите]]. Желимо вам пријатан боравак на викију.',
'confirmemail_loggedin' => 'Ваша е-адреса је сада потврђена.',
'confirmemail_error' => 'Нешто је кренуло наопако при чувању ваше потврде.',
'confirmemail_subject' => '{{SITENAME}} – потврда е-адресе',
'confirmemail_body' => 'Неко, вероватно ви, са ИП адресе $1 је отворио налог „$2“ на викију {{SITENAME}}, наводећи ову е-адресу.

Да потврдите да овај налог стварно припада вама, као и да
омогућите могућности е-поште, отворите ову везу у прегледачу:

$3

Уколико нисте отворили налог, пратите везу
испод како бисте прекинули поступак уписа:

$5

Овај потврдни код истиче $6 у $5.',
'confirmemail_body_changed' => 'Неко, вероватно ви, са ИП адресе $1 је променио е-адресу налога „$2“ у ову адресу на викију {{SITENAME}}.

Да бисте потврдили да овај налог стварно припада вама и поново активирали могућности е-поште, отворите следећу везу у прегледачу:

$3

Ако налог *не* припада вама, пратите следећу везу да откажете потврду е-адресе:

$5

Овај потврдни код истиче $6 у $7.',
'confirmemail_body_set' => 'Неко, вероватно ви, са ИП адресе $1 
је променио е-адресу налога „$2“ у ову адресу на {{SITENAME}}.

Да бисмо потврдили да овај налог стварно припада вама и поново активирали могућности е-поште на {{SITENAME}}, отворите следећу везу у прегледачу:

$3

Ако налог *не* припада вама, пратите следећу везу да откажете потврду е-адресе:

$5

Овај потврдни код истиче $4.',
'confirmemail_invalidated' => 'Потврда е-поште је отказана',
'invalidateemail' => 'Отказивање потврде е-поште',

# Scary transclusion
'scarytranscludedisabled' => '[Међувики укључивање шаблона је онемогућено]',
'scarytranscludefailed' => '[Добављање шаблона за $1 није успело]',
'scarytranscludefailed-httpstatus' => '[Не могу да преузмем шаблон $1: HTTP $2]',
'scarytranscludetoolong' => '[URL адреса је предугачка]',

# Delete conflict
'deletedwhileediting' => "'''Упозорење''': ова страница је обрисана након што сте почели с уређивањем!",
'confirmrecreate' => "[[User:$1|$1]] ([[User talk:$1|разговор]]) {{GENDER:$1|је обрисао|је обрисала|обриса}} ову страницу након што сте почели да је уређујете из следећег разлога:
: ''$2''
Потврдите да стварно желите да направите страницу.",
'confirmrecreate-noreason' => 'Корисник [[User:$1|$1]] ([[User talk:$1|разговор]]) је обрисао ову страницу након што сте почели да га уређујете. Потврдите да стварно желите да поново направите ову страницу.',
'recreate' => 'Поново направи',

'unit-pixel' => 'px',

# action=purge
'confirm_purge_button' => 'У реду',
'confirm-purge-top' => 'Очистити привремену меморију ове стране?',
'confirm-purge-bottom' => 'Ова радња чисти привремену меморију и приказује најновију измену.',

# action=watch/unwatch
'confirm-watch-button' => 'У реду',
'confirm-watch-top' => 'Додати ову страницу у списак надгледања?',
'confirm-unwatch-button' => 'У реду',
'confirm-unwatch-top' => 'Уклонити ову страницу са списка надгледања?',

# Separators for various lists, etc.
'semicolon-separator' => ';&#32;',
'comma-separator' => ',&#32;',
'colon-separator' => ':&#32;',
'pipe-separator' => '&#32;•&#32;',
'word-separator' => '&#32;',
'ellipsis' => '…',
'percent' => '$1%',
'parentheses' => '($1)',

# Multipage image navigation
'imgmultipageprev' => '← претходна страница',
'imgmultipagenext' => 'следећа страница →',
'imgmultigo' => 'Иди!',
'imgmultigoto' => 'Иди на страницу $1',

# Table pager
'ascending_abbrev' => 'раст.',
'descending_abbrev' => 'опад.',
'table_pager_next' => 'Следећа страница',
'table_pager_prev' => 'Претходна страница',
'table_pager_first' => 'Прва страница',
'table_pager_last' => 'Последња страница',
'table_pager_limit' => 'Прикажи $1 ставки по страници',
'table_pager_limit_label' => 'Ставки по страници:',
'table_pager_limit_submit' => 'Иди',
'table_pager_empty' => 'Нема резултата',

# Auto-summaries
'autosumm-blank' => 'Потпуно обрисана страница',
'autosumm-replace' => 'Замена садржаја странице са „$1“',
'autoredircomment' => 'Преусмерење на [[$1]]',
'autosumm-new' => 'Направљена страница са: „$1“',

# Size units
'size-bytes' => '$1 B',
'size-kilobytes' => '$1 kB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Учитавам…',
'livepreview-ready' => 'Учитавање… спремно!',
'livepreview-failed' => 'Неуспешно прегледање.
Пробајте обичан преглед.',
'livepreview-error' => 'Не могу да се повежем: $1 „$2“.
Пробајте обичан приказ.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Измене новије од $1 {{PLURAL:$1|секунде|секунде|секунди}} неће бити приказане.',
'lag-warn-high' => 'Због преоптерећења базе података, измене новије од $1 {{PLURAL:$1|секунда|секунде}} неће бити приказане.',

# Watchlist editor
'watchlistedit-numitems' => 'Ваш списак надгледања садржи {{PLURAL:$1|један наслов|$1 наслова|$1 наслова}}, без страница за разговор.',
'watchlistedit-noitems' => 'Ваш списак надгледања не садржи странице.',
'watchlistedit-normal-title' => 'Уређивање списка надгледања',
'watchlistedit-normal-legend' => 'Уклањање наслова са списка надгледања',
'watchlistedit-normal-explain' => 'Наслови на вашем списку надгледања су приказани испод.
Да бисте уклонили наслов, означите кућицу до њега и кликните на „{{int:Watchlistedit-normal-submit}}“.
Можете и да [[Special:EditWatchlist/raw|уредите сиров списак]].',
'watchlistedit-normal-submit' => 'Уклони',
'watchlistedit-normal-done' => '{{PLURAL:$1|Једна страница је уклоњена|$1 странице су уклоњене|$1 страница је уклоњено}} с вашег списка надгледања:',
'watchlistedit-raw-title' => 'Напредно уређивање списка надгледања',
'watchlistedit-raw-legend' => 'Напредно уређивање списка надгледања',
'watchlistedit-raw-explain' => 'Наслови са списка надгледања су приказани испод и могу се уређивати додавањем или уклањањем ставки са списка;
један наслов по реду.
Када завршите, кликните на „{{int:Watchlistedit-raw-submit}}“.
Можете да [[Special:EditWatchlist|користите и обичан уређивач]].',
'watchlistedit-raw-titles' => 'Наслови:',
'watchlistedit-raw-submit' => 'Ажурирај списак',
'watchlistedit-raw-done' => 'Ваш списак надгледања је ажуриран.',
'watchlistedit-raw-added' => '{{PLURAL:$1|Додат је један наслов|Додата су $1 наслова|Додато је $1 наслова}}:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|Уклоњен је један наслов|Уклоњена су $1 наслова|Уклоњено је $1 наслова}}:',

# Watchlist editing tools
'watchlisttools-view' => 'прикажи сродне измене',
'watchlisttools-edit' => 'прикажи и уреди списак надгледања',
'watchlisttools-raw' => 'измени сиров списак надгледања',

# Iranian month names
'iranian-calendar-m1' => 'Фарвардин',
'iranian-calendar-m2' => 'Ордибехешт',
'iranian-calendar-m3' => 'Хордад',
'iranian-calendar-m4' => 'Тир',
'iranian-calendar-m5' => 'Мордад',
'iranian-calendar-m6' => 'Шахривар',
'iranian-calendar-m7' => 'Мехр',
'iranian-calendar-m8' => 'Абан',
'iranian-calendar-m9' => 'Азар',
'iranian-calendar-m10' => 'Деј',
'iranian-calendar-m11' => 'Бахман',
'iranian-calendar-m12' => 'Есфанд',

# Hijri month names
'hijri-calendar-m1' => 'Мухарам',
'hijri-calendar-m2' => 'Сафар',
'hijri-calendar-m3' => 'Рабија I',
'hijri-calendar-m4' => 'Рабија II',
'hijri-calendar-m5' => 'Јумада I',
'hijri-calendar-m6' => 'Јумада II',
'hijri-calendar-m7' => 'Раџаб',
'hijri-calendar-m8' => 'Шабан',
'hijri-calendar-m9' => 'Рамазан',
'hijri-calendar-m10' => 'Шавал',
'hijri-calendar-m11' => 'Дулкада',
'hijri-calendar-m12' => 'Дулхиџа',

# Hebrew month names
'hebrew-calendar-m1' => 'Тишри',
'hebrew-calendar-m2' => 'Хешван',
'hebrew-calendar-m3' => 'Кислев',
'hebrew-calendar-m4' => 'Тевет',
'hebrew-calendar-m5' => 'Шеват',
'hebrew-calendar-m6' => 'Адар',
'hebrew-calendar-m6a' => 'Адар I',
'hebrew-calendar-m6b' => 'Адар II',
'hebrew-calendar-m7' => 'Нисан',
'hebrew-calendar-m8' => 'Ијар',
'hebrew-calendar-m9' => 'Сиван',
'hebrew-calendar-m10' => 'Тамуз',
'hebrew-calendar-m11' => 'Ав',
'hebrew-calendar-m12' => 'Елул',
'hebrew-calendar-m1-gen' => 'Тишри',
'hebrew-calendar-m2-gen' => 'Хешван',
'hebrew-calendar-m3-gen' => 'Кислев',
'hebrew-calendar-m4-gen' => 'Тевет',
'hebrew-calendar-m5-gen' => 'Шеват',
'hebrew-calendar-m6-gen' => 'Адар',
'hebrew-calendar-m6a-gen' => 'Адар I',
'hebrew-calendar-m6b-gen' => 'Адар II',
'hebrew-calendar-m7-gen' => 'Нисан',
'hebrew-calendar-m8-gen' => 'Ијар',
'hebrew-calendar-m9-gen' => 'Сиван',
'hebrew-calendar-m10-gen' => 'Тамуз',
'hebrew-calendar-m11-gen' => 'Ав',
'hebrew-calendar-m12-gen' => 'Елул',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|разговор]])',
'timezone-utc' => 'UTC',

# Core parser functions
'unknown_extension_tag' => 'Непозната ознака проширења „$1“',
'duplicate-defaultsort' => "'''Упозорење:''' подразумевани кључ сврставања „$2“ мења некадашњи кључ „$1“.",

# Special:Version
'version' => 'Верзија',
'version-extensions' => 'Инсталирана проширења',
'version-specialpages' => 'Посебне странице',
'version-parserhooks' => 'Куке рашчлањивача',
'version-variables' => 'Променљиве',
'version-antispam' => 'Спречавање непожељних порука',
'version-skins' => 'Теме',
'version-api' => 'АПИ',
'version-other' => 'Друго',
'version-mediahandlers' => 'Руководиоци медијима',
'version-hooks' => 'Куке',
'version-parser-extensiontags' => 'Ознаке',
'version-parser-function-hooks' => 'Куке',
'version-hook-name' => 'Назив куке',
'version-hook-subscribedby' => 'Пријављено од',
'version-version' => '(издање $1)',
'version-svn-revision' => '(изм. $2)',
'version-license' => 'Лиценца',
'version-poweredby-credits' => "Овај вики покреће '''[//www.mediawiki.org/ Медијавики]''', ауторска права © 2001-$1 $2.",
'version-poweredby-others' => 'остали',
'version-poweredby-translators' => 'translatewiki.net преводиоци',
'version-credits-summary' => 'Желели бисмо да захвалимо следећим људима на њиховом доприносу [[Special:Version|Медијавикији]].',
'version-license-info' => 'Медијавики је слободан софтвер можете га редистрибуирати и/или модификовати под условима ГНУ-ове опште јавне лиценце верзија 2 или сваке следеће коју објави Задужбина за слободан софтвер.

Медијавики се редистрибуирати у нади да ће бити од користи, али БЕЗ ИКАКВЕ ГАРАНЦИЈЕ чак и без ПОДРАЗУМЕВАНЕ ГАРАНЦИЈЕ ФУНКЦИОНАЛНОСТИ или ПРИКЛАДНОСТИ ЗА ОДРЕЂЕНЕУ НАМЕНУ. Погледајте ГНУ-ову општу јавну лиценцу за више информација.

Требало би да сте примили [{{SERVER}}{{SCRIPTPATH}}/COPYING примерак ГНУ-ове опште јавне лиценце] заједно са овим програмом. Ако нисте, пишите на Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA или [//www.gnu.org/licenses/old-licenses/gpl-2.0.html прочитајте овде].',
'version-software' => 'Инсталирани софтвер',
'version-software-product' => 'Производ',
'version-software-version' => 'Верзија',
'version-entrypoints' => 'Адресе улазне тачке',
'version-entrypoints-header-entrypoint' => 'Улазна тачка',
'version-entrypoints-header-url' => 'Адреса',

# Special:Redirect
'redirect-legend' => 'Преусмери на датотеку или страницу',
'redirect-submit' => 'Иди',
'redirect-value' => 'Вредност:',
'redirect-file' => 'Назив датотеке',
'redirect-not-exists' => 'Вредност није пронађена',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Претрага дупликата',
'fileduplicatesearch-summary' => 'Претрага дуплираних датотека према вредностима дисперзије.',
'fileduplicatesearch-legend' => 'Претрага дупликата',
'fileduplicatesearch-filename' => 'Назив датотеке:',
'fileduplicatesearch-submit' => 'Претражи',
'fileduplicatesearch-info' => '$1 × $2 пиксела<br />Величина: $3<br />MIME врста: $4',
'fileduplicatesearch-result-1' => 'Датотека „$1“ нема истоветних дупликата.',
'fileduplicatesearch-result-n' => 'Датотека „$1“ има {{PLURAL:$2|идентичан дупликат|$2 идентична дупликата|$2 идентичних дупликата}}.',
'fileduplicatesearch-noresults' => 'Датотека под називом „$1“ није пронађена.',

# Special:SpecialPages
'specialpages' => 'Посебне странице',
'specialpages-note' => '----
* обичне посебне странице
* <span class="mw-specialpagerestricted">ограничене посебне странице</span>
* <span class="mw-specialpagecached">привремено меморисане посебне странице</span>',
'specialpages-group-maintenance' => 'Извештаји одржавања',
'specialpages-group-other' => 'Остале посебне странице',
'specialpages-group-login' => 'Пријава/регистрација',
'specialpages-group-changes' => 'Скорашње измене и дневници',
'specialpages-group-media' => 'Извештаји о мултимедијалном садржају и отпремања',
'specialpages-group-users' => 'Корисници и корисничка права',
'specialpages-group-highuse' => 'Најчешће коришћене странице',
'specialpages-group-pages' => 'Спискови страница',
'specialpages-group-pagetools' => 'Алатке',
'specialpages-group-wiki' => 'Подаци и алати',
'specialpages-group-redirects' => 'Преусмеравање посебних страница',
'specialpages-group-spam' => 'Алатке против непожељних порука',

# Special:BlankPage
'blankpage' => 'Празна страница',
'intentionallyblankpage' => 'Ова страница је намерно остављена празном.',

# External image whitelist
'external_image_whitelist' => ' #Оставите овај ред онаквим какав јесте<pre>
#Испод додајте одломке регуларних израза (само део који се налази између //)
#Они ће бити упоређени с адресама спољашњих слика
#Оне које се поклапају биће приказане као слике, а преостале као везе до слика
#Редови који почињу с тарабом се сматрају коментарима
#Сви уноси су осетљиви на мала и велика слова

#Додајте све одломке регуларних израза изнад овог реда. Овај ред не дирајте</pre>',

# Special:Tags
'tags' => 'Важеће ознаке измена',
'tag-filter' => 'Филтер за [[Special:Tags|ознаке]]:',
'tag-filter-submit' => 'Филтрирај',
'tag-list-wrapper' => '([[Special:Tags|{{PLURAL:$1|Ознака|Ознаке}}]]: $2)',
'tags-title' => 'Ознаке',
'tags-intro' => 'На овој страници је наведен списак ознака с којима програм може да означи измене и његово значење.',
'tags-tag' => 'Назив ознаке',
'tags-display-header' => 'Изглед на списковима измена',
'tags-description-header' => 'Опис значења',
'tags-hitcount-header' => 'Означене измене',
'tags-edit' => 'уреди',
'tags-hitcount' => '$1 {{PLURAL:$1|измена|измене|измена}}',

# Special:ComparePages
'comparepages' => 'Упоређивање страница',
'compare-selector' => 'Упоређивање измена странице',
'compare-page1' => 'Страница 1',
'compare-page2' => 'Страница 2',
'compare-rev1' => 'Измена 1',
'compare-rev2' => 'Измена 2',
'compare-submit' => 'Упореди',
'compare-invalid-title' => 'Наведени наслов је неисправан.',
'compare-title-not-exists' => 'Наведени наслов не постоји.',
'compare-revision-not-exists' => 'Наведена измена не постоји.',

# Database error messages
'dberr-header' => 'Овај вики не ради како треба',
'dberr-problems' => 'Дошло је до техничких проблема.',
'dberr-again' => 'Сачекајте неколико минута и поново учитајте страницу.',
'dberr-info' => '(не могу да се повежем са сервером базе: $1)',
'dberr-usegoogle' => 'У међувремену, покушајте да претражите помоћу Гугла.',
'dberr-outofdate' => 'Имајте на уму да њихови примерци нашег садржаја могу бити застарели.',
'dberr-cachederror' => 'Ово је привремено меморисан примерак стране који можда није ажуран.',

# HTML forms
'htmlform-invalid-input' => 'Пронађени су проблеми у вашем уносу',
'htmlform-select-badoption' => 'Наведена вредност није исправна опција.',
'htmlform-int-invalid' => 'Наведена вредност није цели број.',
'htmlform-float-invalid' => 'Наведена вредност није број.',
'htmlform-int-toolow' => 'Наведена вредност је испод минимума од $1',
'htmlform-int-toohigh' => 'Наведена вредност је изнад максимума од $1',
'htmlform-required' => 'Ова вредност је обавезна',
'htmlform-submit' => 'Пошаљи',
'htmlform-reset' => 'Врати измене',
'htmlform-selectorother-other' => 'Друго',
'htmlform-no' => 'Не',
'htmlform-yes' => 'Да',
'htmlform-chosen-placeholder' => 'Изаберите опцију',

# SQLite database support
'sqlite-has-fts' => '$1 с подршком претраге целог текста',
'sqlite-no-fts' => '$1 без подршке претраге целог текста',

# New logging system
'logentry-delete-delete' => '$1 је {{GENDER:|обрисао|обрисала}} $3',
'logentry-delete-restore' => '$1 је {{GENDER:$2|вратио|вратила}} страницу $3',
'logentry-delete-event' => '$1 је {{GENDER:$2|променио|променила}} видљивост {{PLURAL:$5|догађаја|$5 догађаја}} у дневнику на $3: $4',
'logentry-delete-revision' => '$1 је {{GENDER:$2|променио|променила}} видљивост {{PLURAL:$5|измене|$5 измена}} на страници $3: $4',
'logentry-delete-event-legacy' => '$1 је {{GENDER:$2|променио|променила}} видљивост догађаја у дневнику на $3',
'logentry-delete-revision-legacy' => '$1 је {{GENDER:$2|променио|променила}} видљивост изменâ на страници $3',
'logentry-suppress-delete' => '$1 је {{GENDER:$2|потиснуо|потиснула}} страницу $3',
'logentry-suppress-event' => '$1 је потајно {{GENDER:$2|променио|променила}} видљивост {{PLURAL:$5|догађаја|$5 догађаја}} у дневнику на $3: $4',
'logentry-suppress-revision' => '$1 је потајно {{GENDER:$2|променио|променила}} видљивост {{PLURAL:$5|измене|$5 измена}} на страници $3: $4',
'logentry-suppress-event-legacy' => '$1 је потајно {{GENDER:$2|променио|променила}} видљивост догађаја у дневнику на $3',
'logentry-suppress-revision-legacy' => '$1 је потајно {{GENDER:$2|променио|променила}} видљивост измена на страници $3',
'revdelete-content-hid' => 'садржај је сакривен',
'revdelete-summary-hid' => 'опис измене је сакривен',
'revdelete-uname-hid' => 'корисничко име је сакривено',
'revdelete-content-unhid' => 'садржај је откривен',
'revdelete-summary-unhid' => 'опис измене је откривен',
'revdelete-uname-unhid' => 'корисничко име је откривено',
'revdelete-restricted' => 'примењена ограничења за администраторе',
'revdelete-unrestricted' => 'уклоњена ограничења за администраторе',
'logentry-move-move' => '$1 је {{GENDER:$2|преместио|преместила}} страницу $3 на $4',
'logentry-move-move-noredirect' => '$1 је {{GENDER:$2|преместио|преместила}} страницу $3 на $4 без остављања преусмерења',
'logentry-move-move_redir' => '$1 је {{GENDER:$2|преместио|преместила}} страницу $3 на $4 преко преусмерења',
'logentry-move-move_redir-noredirect' => '$1 је {{GENDER:$2|преместио|преместила}} страницу $3 на $4 преко преусмерења без остављања преусмерења',
'logentry-patrol-patrol' => '$1 је {{GENDER:$2|означио|означила}} измену $4 странице $3 као патролирану',
'logentry-patrol-patrol-auto' => '$1 је аутоматски {{GENDER:$2|означио|означила}} измену $4 странице $3 као прегледану',
'logentry-newusers-newusers' => '$1 је {{GENDER:$2|отворио|отворила}} кориснички налог',
'logentry-newusers-create' => '$1 је {{GENDER:$2|отворио|отворила}} кориснички налог',
'logentry-newusers-create2' => '$1 је {{GENDER:$2|отворио|отворила}} кориснички налог $3',
'logentry-newusers-byemail' => 'Кориснички налог $3 је {{GENDER:$2|направљен}} од стране $1 и лозинка је послата на е-пошту',
'logentry-newusers-autocreate' => 'Кориснички налог $1 је аутоматски {{GENDER:$2|отворен}}',
'logentry-rights-rights' => '$1 је {{GENDER:$2|променио|променила}} чланство групе за $3 из $4 у $5',
'logentry-rights-rights-legacy' => '$1 је {{GENDER:$2|променио|променила}} чланство групе за $3',
'logentry-rights-autopromote' => '$1 је аутоматски {{GENDER:$2|унапређен|унапређена}} из $4 у $5',
'rightsnone' => '(ништа)',

# Feedback
'feedback-bugornote' => 'Ако сте спремни да детаљно опишете технички проблем, онда [$1 пријавите грешку].
У супротном, послужите се једноставним обрасцем испод. Ваш коментар ће стајати на страници „[$3 $2]“, заједно с корисничким именом и прегледачем који користите.',
'feedback-subject' => 'Наслов:',
'feedback-message' => 'Порука:',
'feedback-cancel' => 'Откажи',
'feedback-submit' => 'Пошаљи повратну информацију',
'feedback-adding' => 'Додајем повратну информацију на страницу…',
'feedback-error1' => 'Грешка: непрепознат резултат од АПИ-ја',
'feedback-error2' => 'Грешка: уређивање није успело',
'feedback-error3' => 'Грешка: нема одговора од АПИ-ја',
'feedback-thanks' => 'Хвала! Ваша повратна информација је постављена на страницу „[$2 $1]“.',
'feedback-close' => 'Урађено',
'feedback-bugcheck' => 'Одлично! Проверите да ли је грешка [$1 позната од пре].',
'feedback-bugnew' => 'Проверено. Пријави нову грешку',

# Search suggestions
'searchsuggest-search' => 'Претрага',
'searchsuggest-containing' => 'садржи...',

# API errors
'api-error-badaccess-groups' => 'Није вам дозвољено да отпремате датотеке на овај вики.',
'api-error-badtoken' => 'Унутрашња грешка: неисправан жетон.',
'api-error-copyuploaddisabled' => 'Отпремање путем адресе је онемогућено на овом серверу.',
'api-error-duplicate' => 'Већ {{PLURAL:$1|постоји [$2 друга датотека]|постоје [$2 друге датотеке]}} с истим садржајем.',
'api-error-duplicate-archive' => '{{PLURAL:$1|Постојала је [$2 друга датотека]|Постојале су [$2 друге датотеке]}} с истим садржајем, али {{PLURAL:$1|је обрисана|су обрисане}}.',
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|Дуплирана датотека која је већ обрисана|Дуплиране датотеке које су већ обрисане}}',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|Дуплирана датотека|Дуплиране датотеке}}',
'api-error-empty-file' => 'Послата датотека је празна.',
'api-error-emptypage' => 'Стварање нових празних страница није дозвољено.',
'api-error-fetchfileerror' => 'Унутрашња грешка: дошло је до грешке при добављању датотеке.',
'api-error-fileexists-forbidden' => 'Већ постоји датотека с именом „$1“ и не може да се замени.',
'api-error-fileexists-shared-forbidden' => 'Већ постоји датотека с именом „$1“ у заједничкој ризници и не може да се замени.',
'api-error-file-too-large' => 'Послата датотека је превелика.',
'api-error-filename-tooshort' => 'Назив датотеке је прекратак.',
'api-error-filetype-banned' => 'Ова врста датотеке је забрањена.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|је забрањена врста датотеке|су забрањене врсте датотека}}. {{PLURAL:$3|Дозвољена је|Дозвољене су}} $2.',
'api-error-filetype-missing' => 'Датотеци недостаје наставак.',
'api-error-hookaborted' => 'Измена је одбачена од куке за проширења.',
'api-error-http' => 'Унутрашња грешка: не могу да се повежем са сервером.',
'api-error-illegal-filename' => 'Назив датотеке је забрањен.',
'api-error-internal-error' => 'Унутрашња грешка: дошло је до грешке при обрађивању датотеке на викију.',
'api-error-invalid-file-key' => 'Унутрашња грешка: не могу да пронађем датотеку у привременом складишту.',
'api-error-missingparam' => 'Унутрашња грешка: недостају параметри у захтеву.',
'api-error-missingresult' => 'Унутрашња грешка: не могу да утврдим да ли је умножавање успело.',
'api-error-mustbeloggedin' => 'Морате бити пријављени да бисте отпремали датотеке.',
'api-error-mustbeposted' => 'Унутрашња грешка: не користи се исправан HTTP метод.',
'api-error-noimageinfo' => 'Отпремање је успело, али сервер није дао никакав податак о датотеци.',
'api-error-nomodule' => 'Унутрашња грешка: није постављен модул за отпремање.',
'api-error-ok-but-empty' => 'Унутрашња грешка: сервер не одговара.',
'api-error-overwrite' => 'Замењивање постојеће датотеке је забрањено.',
'api-error-stashfailed' => 'Унутрашња грешка: сервер не може да сачува привремену датотеку.',
'api-error-publishfailed' => 'Унутрашња грешка: сервер није успео да објави привремену датотеку.',
'api-error-timeout' => 'Сервер није одговорио у очекивано време.',
'api-error-unclassified' => 'Дошло је до непознате грешке',
'api-error-unknown-code' => 'Непозната грешка: „$1“',
'api-error-unknown-error' => 'Унутрашња грешка: дошло је до грешке при отпремању датотеке.',
'api-error-unknown-warning' => 'Непознато упозорење: $1',
'api-error-unknownerror' => 'Непозната грешка: „$1“.',
'api-error-uploaddisabled' => 'Отпремање је онемогућено на овом викију.',
'api-error-verification-error' => 'Датотека је оштећена или има неисправан наставак.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|секунд|секунде|секунди}}',
'duration-minutes' => '$1 {{PLURAL:$1|минут|минута|минута}}',
'duration-hours' => '$1 {{PLURAL:$1|сат|сата|сати}}',
'duration-days' => '$1 {{PLURAL:$1|дан|дана|дана}}',
'duration-weeks' => '$1 {{PLURAL:$1|недеља|недеље|недеља}}',
'duration-years' => '$1 {{PLURAL:$1|година|године|година}}',
'duration-decades' => '$1 {{PLURAL:$1|деценија|деценије|деценија}}',
'duration-centuries' => '$1 {{PLURAL:$1|век|века|векова}}',
'duration-millennia' => '$1 {{PLURAL:$1|миленијум|миленијума|миленијума}}',

# Image rotation
'rotate-comment' => 'Слика је ротирана у смеру казаљке на сату за {{PLURAL:$1|степени}}',

# Limit report
'limitreport-walltime-value' => '$1 {{PLURAL:$1|секунда|секунди}}',
'limitreport-postexpandincludesize-value' => '$1/$2 бајтова',
'limitreport-templateargumentsize-value' => '$1/$2 бајтова',

);
