<?php
/** Kirghiz (Кыргызча)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AidaBishkek
 * @author Aidabishkek
 * @author Amire80
 * @author Chorobek
 * @author Growingup
 * @author Muratjumashev
 * @author Ztimur
 */

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Атайын',
	NS_TALK             => 'Баарлашуу',
	NS_USER             => 'Колдонуучу',
	NS_USER_TALK        => 'Колдонуучунун_баарлашуулары',
	NS_PROJECT_TALK     => '$1_баарлашуу',
	NS_FILE             => 'Файл',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_TEMPLATE         => 'Калып',
	NS_HELP             => 'Жардам',
	NS_CATEGORY         => 'Категория',
);

$messages = array(
# User preference toggles
'tog-underline' => 'Шилтемелердин алдын сызуу:',
'tog-justify' => 'Тексти барактын жазысы боюнча түздөө',
'tog-hideminor' => 'Соңку өзгөрүүлөрдөгү арасындагы майда өзгөрүүлөрдү жашыр',
'tog-hidepatrolled' => 'Акыркы өзгөрүүлөрдүн тизмесинен патрулдалган оңдоолорду жашыруу',
'tog-newpageshidepatrolled' => 'Жаңы барактар тизмесинен патрулдалган барактарды жашыруу',
'tog-extendwatchlist' => 'Бүт өзгөртүүлөрдү көрсөтүш үчүн көзөмөл тизмесин кеңейтүү, эң акыркыларды гана эмес эле',
'tog-usenewrc' => 'Акыркы өзгөрүүлөрдөгү жана көзөмөл тизмедеги өзгөрүүлөрдү топтоо (JavaScript талап кылынат)',
'tog-numberheadings' => 'Башжазууларды автоматтуу түрдө номердөө',
'tog-showtoolbar' => 'Оңдоо учурунда аспаптар тактасын көрсөтүү (JavaScript талап кылынат)',
'tog-editondblclick' => 'Эки басып баракты оңдоо (JavaScript талап кылынат)',
'tog-editsection' => 'Ар бир секция үчүн «оңдоо» шилтемесин көрсөтүү',
'tog-editsectiononrightclick' => 'Чычкандын оң кнопкасы менен секциянын башжазуусун басканда редакторлоо секциясын ачуу (JavaScript талап кылынат)',
'tog-showtoc' => 'Мазмунду көрсөтүү (3 мазмундан артык барактар үчүн)',
'tog-rememberpassword' => 'Бул браузерде ($1 {{PLURAL:$1|күн}}) менин эсеп жазуумду эске сактоо',
'tog-watchcreations' => 'Көзөмөл тизмесине мен жараткан барактарды жана мен жүктөгөн файлдарды кошуу',
'tog-watchdefault' => 'Мен өзгөрткөн барактарды жана файлдарды көзөмөл тизмеме кошуу',
'tog-watchmoves' => 'Мен атын өзгөрткөн барактарды жана файлдарды көзөмөл тизмеме кошуу',
'tog-watchdeletion' => 'Мен өчүргөн барактарды жана файлдарды көзөмөл тизмеме кошуу',
'tog-minordefault' => 'Жарыяланбасча бүт оңдоолорду майда деп белги салуу',
'tog-previewontop' => 'Оңдоо терезесинин алдына алдын ала көрсөтүүнү орундаштыруу',
'tog-previewonfirst' => 'Оңдоого өткөндө алдын алдын ала көрсөтүү',
'tog-nocache' => 'Барактарды кэштөөнү браузерден өчүрүү',
'tog-enotifwatchlistpages' => 'Көзөмөл тизмемдеги барак же файл өзгөртүлгөндө мага эл. почта аркылуу билдирүү',
'tog-enotifusertalkpages' => 'Талкуулоо барагым өзгөртүлгөндө мага эл. почта аркылуу билдирүү',
'tog-enotifminoredits' => 'Барак же файлдардын майда өзгөртүлөрүн да мага эл. почта аркылуу билдирүү',
'tog-enotifrevealaddr' => 'Кабардандыруу билдирүүлөрүндө менин эл. почта дарегимди көрсөтүү',
'tog-shownumberswatching' => 'Көзөмөлдөп жаткан катышуучулардын санын көрсөтүү',
'tog-oldsig' => 'Кезектеги кол тамга:',
'tog-fancysig' => 'Кол тамганын өз уики-белгиси (автоматтык шилтемесиз)',
'tog-externaleditor' => 'Тышкы редакторду жарыяланбасча колдонуу (адистер үчүн гана, компьютериңизде өзгөчө ырастоолор керек. [//www.mediawiki.org/wiki/Manual:External_editors кошумча маалымат.])',
'tog-externaldiff' => 'Салыштыруу үчүн тышкы программаны жарыяланбасча колдонуу (адистер үчүн гана, компьютериңизде өзгөчө ырастоолор керек. [//www.mediawiki.org/wiki/Manual:External_editors кошумча маалымат.])',
'tog-showjumplinks' => '«өтүү» деген жардамчы шилтемелерди күйгүзүү',
'tog-uselivepreview' => 'Тез алдын ала көрсөтүүнү колдонуу (JavaScript талап кылынат) (эксперименталдык)',
'tog-forceeditsummary' => 'Оңдоо баяндоосунун талаасы бош болгон кезинде мага алдын ала эскертүү',
'tog-watchlisthideown' => 'Көзөмөлдөө тизмесинен менин оңдоолорумду жашыруу',
'tog-watchlisthidebots' => 'Көзөмөлдөө тизмесинен боттун оңдоолорун жашыруу',
'tog-watchlisthideminor' => 'Көзөмөлдөө тизмесинен майда оңдоолорду жашыруу',
'tog-watchlisthideliu' => 'Көзөмөлдөө тизмесинен системага кирген катышуучулардын оңдоолорун жашыруу',
'tog-watchlisthideanons' => 'Көзөмөлдөө тизмесинен анонимдүү катышуучулардын оңдоолорун жашыруу',
'tog-watchlisthidepatrolled' => 'Көзөмөлдөө тизмесинен патрулдалган оңдоолорду жашыруу',
'tog-ccmeonemails' => 'Мен башка катышуучуларга жөнөтүп жаткан каттардын көчүрмөлөрүн мага жөнөтүү',
'tog-diffonly' => 'Эки версияны салыштыруунун астынан барактын мазмунун көрсөтпөө',
'tog-showhiddencats' => 'Жашырылган категорияларды көрсөтүү',
'tog-norollbackdiff' => 'Кетенчиктөөнү аткаргандан кийин версиялардын айырмасын көрсөтпөө',

'underline-always' => 'Дайыма',
'underline-never' => 'Эч качан',
'underline-default' => 'Браузердин ырастоолорун колдонуу',

# Font style option in Special:Preferences
'editfont-style' => 'Оңдолуп жаткан жердин тамга жасалгасы:',
'editfont-default' => 'Браузер ырастоолорунун шрифти',
'editfont-monospace' => 'Моножазы шрифт',
'editfont-sansserif' => 'Кесүүсү жок шрифт',
'editfont-serif' => 'Кесүүсү бар шрифт',

# Dates
'sunday' => 'Жекшемби',
'monday' => 'Дүйшөмбү',
'tuesday' => 'Шейшемби',
'wednesday' => 'Шаршемби',
'thursday' => 'Бейшемби',
'friday' => 'Жума',
'saturday' => 'Ишемби',
'sun' => 'Жк',
'mon' => 'Дш',
'tue' => 'Ше',
'wed' => 'Ша',
'thu' => 'Бш',
'fri' => 'Жм',
'sat' => 'Иш',
'january' => 'Январь (Үчтүн айы)',
'february' => 'Февраль (Бирдин айы)',
'march' => 'Март (Жалган куран)',
'april' => 'Апрель (Чын куран)',
'may_long' => 'Май (Бугу)',
'june' => 'Июнь (Кулжа)',
'july' => 'Июль (Теке)',
'august' => 'Август (Баш оона)',
'september' => 'Сентябрь (Аяк оона)',
'october' => 'Октябрь (Тогуздун айы)',
'november' => 'Ноябрь (Жетинин айы)',
'december' => 'Декабрь (Бештин айы)',
'january-gen' => 'Январь (Үчтүн айы)',
'february-gen' => 'Февраль (Бирдин айы)',
'march-gen' => 'Март (Жалган куран)',
'april-gen' => 'Апрель (Чын куран)',
'may-gen' => 'Май (Бугу)',
'june-gen' => 'Июнь (Кулжа)',
'july-gen' => 'Июль (Теке)',
'august-gen' => 'Август (Баш оона)',
'september-gen' => 'Сентябрь (Аяк оона)',
'october-gen' => 'Октябрь (Тогуздун айы)',
'november-gen' => 'Ноябрь (Жетинин айы)',
'december-gen' => 'Декабрь (Бештин айы)',
'jan' => 'Янв',
'feb' => 'Фев',
'mar' => 'Март',
'apr' => 'Апр',
'may' => 'Май',
'jun' => 'Июнь',
'jul' => 'Июль',
'aug' => 'Авг',
'sep' => 'Сент',
'oct' => 'Окт',
'nov' => 'Ноя',
'dec' => 'Дек',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Категория|Категориялар}}',
'category_header' => '"$1" категориядагы барактар',
'subcategories' => 'Ички категориялар',
'category-media-header' => '"$1" категориясындагы медиафайлдар',
'category-empty' => "''Бул категорияда азыр эч бир барак же файл жок.''",
'hidden-categories' => '{{PLURAL:$1|Жашырылган категория|Жашырылган категориялар}}',
'hidden-category-category' => 'Жашырылган категориялар',
'category-subcat-count' => '{{PLURAL:$2|Бул категория төмөнкү көмөк категорияны камтыйт.|Бул категорияда жалпы $2, {{PLURAL:$1|көмөк категория|$1 көмөк категория}} бар.}}',
'category-subcat-count-limited' => 'Бул категорияда {{PLURAL:$1|$1|$1|$1}} ички категория бар.',
'category-article-count' => '{{PLURAL:$2|Бул категория төмөнкү баракты камтыйт.|Бул категорияда жалпы $2, төмөнкү {{PLURAL:$1|барак|$1 барак}} бар.}}',
'category-article-count-limited' => 'Бул категорияда {{PLURAL:$1|$1 барак}} бар.',
'category-file-count' => '{{PLURAL:$2|Бул категория төмөнкү файлды камтыйт.|Бул категорияда жалпы $2, төмөнкү {{PLURAL:$1|файл|$1 файл}} бар.}}',
'category-file-count-limited' => 'Бул категорияда {{PLURAL:$1|$1|$1|$1}} файл бар.',
'listingcontinuesabbrev' => 'уланд.',
'index-category' => 'Индекстелген барактар',
'noindex-category' => 'Индекстелбеген барактар',
'broken-file-category' => 'Файлдарга туура эмес шилтеме берген барактар',

'about' => 'Тууралуу',
'article' => 'Макала',
'newwindow' => '(жаңы терезеде ачылат)',
'cancel' => 'Жокко чыгаруу',
'moredotdotdot' => 'Көбүрөөк...',
'mypage' => 'Барак',
'mytalk' => 'Талкуулоо',
'anontalk' => 'Бул IP-дарек үчүн талкуулоо',
'navigation' => 'Навигация',
'and' => '&#32;жана',

# Cologne Blue skin
'qbfind' => 'Табуу',
'qbbrowse' => 'Карап чыгуу',
'qbedit' => 'Оңдоо',
'qbpageoptions' => 'Бул барак',
'qbpageinfo' => 'Контекст',
'qbmyoptions' => 'Барактарым',
'qbspecialpages' => 'Кызмат барактары',
'faq' => 'КБС',
'faqpage' => 'Project:КБС',

# Vector skin
'vector-action-addsection' => 'Тема кошумчалоо',
'vector-action-delete' => 'Өчүрүү',
'vector-action-move' => 'Атын өзгөртүү',
'vector-action-protect' => 'Коргоо',
'vector-action-undelete' => 'Калыбына келтирүү',
'vector-action-unprotect' => 'Коргоону өзгөртүү',
'vector-simplesearch-preference' => 'Жөнөкөйлөнгөн издөө сабын күйгүзүү («Вектор» темасы гана)',
'vector-view-create' => 'Башта',
'vector-view-edit' => 'Оңдоо',
'vector-view-history' => 'Тарыхын кароо',
'vector-view-view' => 'Окуу',
'vector-view-viewsource' => 'Кайнарын кароо',
'actions' => 'Аракеттер',
'namespaces' => 'Аталыштар мейкиндиги',
'variants' => 'Варианттар',

'errorpagetitle' => 'Ката',
'returnto' => '$1 барагына кайтуу.',
'tagline' => '{{SITENAME}} дан',
'help' => 'Жардам',
'search' => 'Издөө',
'searchbutton' => 'Издөө',
'go' => 'Өтүү',
'searcharticle' => 'Алга',
'history' => 'Барактын тарыхы',
'history_short' => 'Тарыхы',
'updatedmarker' => 'менин акыркы келүүмдөн кийин жаңыртылган',
'printableversion' => 'Басма үлгүсү',
'permalink' => 'Туруктуу шилтеме',
'print' => 'Басып чыгаруу',
'view' => 'Кароо',
'edit' => 'Оңдоо',
'create' => 'Башта',
'editthispage' => 'Бул баракты оңдоо',
'create-this-page' => 'Бул баракты түзүү',
'delete' => 'Өчүрүү',
'deletethispage' => 'Бул баракты өчүрүү',
'undelete_short' => '$1 {{PLURAL:$1|оңдоону}} калыбына келтирүү',
'viewdeleted_short' => '{{PLURAL:$1|өчүрүлгөн оңдоону}} көрүү',
'protect' => 'Коргоо',
'protect_change' => 'өзгөртүү',
'protectthispage' => 'Бул баракты коргоо',
'unprotect' => 'Коргоону өзгөртүү',
'unprotectthispage' => 'Бул барактын коргоосун өзгөртүү',
'newpage' => 'Жаңы барак',
'talkpage' => 'Бул баракты талкууга алуу',
'talkpagelinktext' => 'талкуулоо',
'specialpage' => 'Кызмат барагы',
'personaltools' => 'Жеке аспаптар',
'postcomment' => 'Жаңы бөлүм',
'articlepage' => 'Макаланы кароо',
'talk' => 'Талкуулоо',
'views' => 'Көрсөтүүлөр',
'toolbox' => 'Аспаптар',
'userpage' => 'Катышуучунун барагын кароо',
'projectpage' => 'Долбоор барагын кароо',
'imagepage' => 'Файлдын барагын кароо',
'mediawikipage' => 'Кабардын  барагын кароо',
'templatepage' => 'Шаблондун барагын кароо',
'viewhelppage' => 'Жардам алуу',
'categorypage' => 'Категория барагын көрсөтүү',
'viewtalkpage' => 'Талкуулоону кароо',
'otherlanguages' => 'Башка тилдерде',
'redirectedfrom' => '($1 барагындан багытталды)',
'redirectpagesub' => 'Айдама барак',
'lastmodifiedat' => 'Бул барак соңку жолу $1, $2 өзгөртүлгөн.',
'viewcount' => 'Бул барак {{PLURAL:$1|$1|$1}} жолу ачылды.',
'protectedpage' => 'Корголгон барак',
'jumpto' => 'Өтүү:',
'jumptonavigation' => 'навигация',
'jumptosearch' => 'издөө',
'view-pool-error' => 'Кечириңиз, азыркы учурда серверлер ашыра жүктөлгөн болуп турат.
Өтө көп колдонуучулар бул баракты көрүүгө аракет кылып жатышат.
Бул баракка кайта кирээр алдында кичине бир убакыт күтө туруңуз.

$1',
'pool-timeout' => 'Бөгөттөөнү күтүү убакытысы аяктады',
'pool-queuefull' => 'Суроо жыйнагыч толук',
'pool-errorunknown' => 'Белгисиз ката',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{SITENAME}} жөнүндө',
'aboutpage' => 'Project:Долбоор жөнүндө',
'copyright' => '$1 лицензиясына ылайк жеткиликтүү мазмун.',
'copyrightpage' => '{{ns:project}}:Автордук укуктар',
'currentevents' => 'Учурдагы окуялар',
'currentevents-url' => 'Project:Учурдагы окуялар',
'disclaimers' => 'Жоопкерчиликтен баш тартуу',
'disclaimerpage' => 'Project:Жоопкерчиликтен баш тартуу',
'edithelp' => 'Өзгөртүү боюнча жардам',
'edithelppage' => 'Help:Редакторлоо',
'helppage' => 'Help:Мазмуну',
'mainpage' => 'Башбарак',
'mainpage-description' => 'Башбарак',
'policy-url' => 'Project:Эрежелер',
'portal' => 'Жамаат порталы',
'portal-url' => 'Project:Жамаат порталы',
'privacy' => 'Жашырындуулук саясаты',
'privacypage' => 'Project:Жашырындуулук саясаты',

'badaccess' => 'Кирүү катасы',
'badaccess-group0' => 'Сиз сураган аракетти аткарала албайсыз.',

'versionrequired' => "MediaWiki'нин $1 версиясы керек",

'ok' => 'OK',
'retrievedfrom' => '"$1" булагынан алынды',
'youhavenewmessages' => 'Сизге $1 ($2) бар.',
'newmessageslink' => 'жаңы билдирүүлөр',
'newmessagesdifflink' => 'соңку өзгөрүү',
'youhavenewmessagesmanyusers' => 'Көп колдонуучулардан сиз $1 алдыңыз ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|жаңы билдирүү}}',
'newmessagesdifflinkplural' => 'соңку {{PLURAL:$1|өзгөртүү|өзгөртүүлөр}}',
'youhavenewmessagesmulti' => 'Сизге $1 жаңы кат бар',
'editsection' => 'оңдоо',
'editold' => 'оңдоо',
'viewsourceold' => 'баштапкы кодун көрүү',
'editlink' => 'оңдоо',
'viewsourcelink' => 'Кайнарды кара',
'editsectionhint' => '$1 бөлүмүн оңдоо',
'toc' => 'Мазмуну',
'showtoc' => 'көрсөтүү',
'hidetoc' => 'жашыруу',
'collapsible-collapse' => 'Түрүү',
'collapsible-expand' => 'жаюу',
'thisisdeleted' => '$1 көрүү же калыбына келтирүү?',
'viewdeleted' => 'Көрүү $1?',
'restorelink' => '{{PLURAL:$1|$1 өчүрүлгөн оңдоо}}',
'feed-unavailable' => 'Синдикация лентасы жеткиликтүү эмес',
'site-rss-feed' => '$1 RSS тилкеси',
'site-atom-feed' => '$1 Atom агымы',
'page-rss-feed' => '«$1» — RSS-лента',
'page-atom-feed' => '«$1» — Atom-лента',
'red-link-title' => '$1 (мындай барак жок)',
'sort-descending' => 'Кемүү боюнча сорттоо',
'sort-ascending' => 'Өсүү боюнча сорттоо',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Макала',
'nstab-user' => 'Катышуучу',
'nstab-media' => 'Мультимедиа',
'nstab-special' => 'Кызмат барагы',
'nstab-project' => 'Долбоордун барагы',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'Билдирүү',
'nstab-template' => 'Калып',
'nstab-help' => 'Жардам',
'nstab-category' => 'Категория',

# Main script and global functions
'nosuchaction' => 'Мындай аракет жок',
'nosuchspecialpage' => 'Мындай кызмат барагы жок',

# General errors
'error' => 'Ката',
'databaseerror' => 'Маалымат базасынын катасы',
'dberrortext' => 'Маалымат базасынын суроо синтаксисинде ката табылды.
Бул программалык камсыздоодо ката бар болгонун билдирет.
Акыркы маалымат базасынын суроосу:
<blockquote><code>$1</code></blockquote>
«<code>$2</code>» функциясынан пайда болду.
Маалымат базасы «<samp>$3: $4</samp>» катасын кайтарды.',
'laggedslavemode' => "'''Эскертүү:''' баракта акыркы жаңыртуулар жок болуп калышы мүмкүн.",
'readonly' => 'Маалымат базасы камаланган',
'enterlockreason' => 'Бөгөттөө себебин жана анын мөөнөтүн көрсөтүңүз',
'missing-article' => 'Табылууга тийиш «$1» $2 деп аталган баракта текст маалыматтар базасында табылган жок.

Бул сыяктуу абал өчүрүлгөн барактын өзгөрүүлөрдүн тарыхына эски шилтеме менен өткөндө учурайт.

Эгерде башка себеби бар болсо, анда Сиз программалык жабдууда ката таптыңыз. Кичи пейилдикке, ушул URL көрсөтүп [[Special:ListUsers/sysop|администраторлордун]] бирине кабарлап коюңуз.',
'missingarticle-rev' => '(версия#: $1)',
'missingarticle-diff' => '(айырмачылык: $1, $2)',
'internalerror' => 'Ички ката',
'internalerror_info' => 'Ички ката: $1',
'fileappenderrorread' => 'Аягына кошуу үчүн «$1» файлы ачылбады.',
'fileappenderror' => '"$1" файлы "$2" файлынын аягына кошулбады.',
'filecopyerror' => '"$1" файлы "$2" файлына көчүрүлбөдү.',
'filerenameerror' => '«$1» файлын бул «$2» атка өзгөртүүгө мүмкүн эмес.',
'filedeleteerror' => '"$1" файлын өчүрүүгө болбоду.',
'directorycreateerror' => '"$1" каталогун түзүүгө болбоду.',
'filenotfound' => '"$1" файлын табууга мүмкүн эмес.',
'fileexistserror' => '"$1" файлына жазууга болбоду: Мурдатан бар.',
'unexpected' => 'Күтүлбөгөн маани: "$1"="$2".',
'formerror' => 'Ката: Форманы жөнөтүүгө болбоду.',
'badarticleerror' => 'Бул аракетти бул баракта аткарууга болбой.',
'cannotdelete-title' => '"$1" барагын өчүрүүгө болбойт',
'badtitle' => 'Ыксыз аталыш',
'badtitletext' => 'Талап кылынган барак аталышы туура эмес, бош, же тилдер-аралык же уики-аралык аталышы туура эмес шилтемеленген.
Балким аталышта колдонулбай турган бир же андан көп белги камтылган.',
'wrong_wfQuery_params' => 'wfQuery() функциясы үчүн жарабай турган параметрлер<br />
Функция: $1<br />
Суроо: $2',
'viewsource' => 'Кароо',
'viewsource-title' => '$1 барагынын баштапкы кодун көрүү',
'actionthrottled' => 'Аралык боюнча чектөө',
'viewsourcetext' => 'Сиз бул барактын баштапкы кодун көрүп жана көчүрүп алсаңыз болот:',
'sqlhidden' => '(SQL суроо жашырылган)',
'ns-specialprotected' => 'Кызмат барактарын оңдоого мүмкүн эмес.',
'exception-nologin' => 'Сиз система кирген жоксуз',

# Virus scanner
'virus-badscanner' => "Ырастоо катасы. Белгисиз вирус сканери: ''$1''",
'virus-scanfailed' => 'скандоо катасы (код $1)',
'virus-unknownscanner' => 'белгисиз антивирус:',

# Login and logout pages
'welcomecreation' => '== Кош келиңиз, $1! ==

Сиз катоодон өттүңүз. {{SITENAME}} түзөө киргизүүнү унутпаңыз.',
'yourname' => 'Эсеп жазуусунун аты:',
'yourpassword' => 'Сырсөз:',
'yourpasswordagain' => 'Сырсөздү кайта терүү:',
'remembermypassword' => 'Бул браузерде менин эсеп жазуумду ($1 {{PLURAL:$1|күнгө}} чейин сактоо)',
'securelogin-stick-https' => 'Киргенден кийин HTTPS аркылуу туташтырууну улантуу',
'yourdomainname' => 'Сиздин домен',
'password-change-forbidden' => 'Сиз бул уикиден сырсөзүңүздү өзгөртө албайсыз.',
'login' => 'Кирүү',
'nav-login-createaccount' => 'Кирүү / Каттоо',
'loginprompt' => '{{SITENAME}} сайтына кириш үчүн сиз «кукилерге» уруксат беришиңиз керек.',
'userlogin' => 'Кирүү / Каттоо',
'userloginnocreate' => 'Кирүү',
'logout' => 'Сеансты аяктоо',
'userlogout' => 'Сеансты аяктоо',
'notloggedin' => 'Сиз системага кире элексиз',
'nologin' => 'Эсеп жазууңуз жокпу? $1.',
'nologinlink' => 'Эсеп жазуусун жаратуу',
'createaccount' => 'Эсеп жазуусун жаратуу',
'gotaccount' => "Катталгансызбы? '''$1'''.",
'gotaccountlink' => 'Кирүү',
'userlogin-resetlink' => 'Кирүүчү маалыматарыңызды унутуп калдыңызбы?',
'createaccountmail' => 'Электрондук дарек боюнча',
'createaccountreason' => 'Себеби:',
'badretype' => 'Сиз киргизген сырсөздөр дал келишпейт',
'userexists' => 'Сиз тандаган катышуучунун аты бош эмес.
Башка атты тандаңыз.',
'loginerror' => 'Кирүү катасы',
'createaccounterror' => '$1 эсеп жазуусун жаратууга мүмкүн эмес',
'nocookieslogin' => '{{SITENAME}} сайты катышуучуларды киргизүү үчүн кукилерди колдонот.
Алар сизде өчүрүлүп турат.
Аларды күйгүзүп анан кайта аракет кылып көрүңүз.',
'noname' => 'Сиз анык катышуучунун атын көрсөткөн жоксуз.',
'loginsuccesstitle' => 'Сиз ийгиликтүү кирдиңиз',
'loginsuccess' => "'''Сиз эми {{SITENAME}} сайтына \"\$1\" болуп кирдиңиз.'''",
'nouserspecified' => 'Сиз катышуучу атын көрсөтүшүңүз керек.',
'login-userblocked' => 'Бул катышуучу бөгөттөлгөн. Системага кирүүгө уруксат жок.',
'wrongpassword' => 'Ката сырсөз киргизилди. Кайтадан аракет кылып көрүңүз.',
'wrongpasswordempty' => 'Сырсөз киргизилген жок. Кайтадан аракет кылып көрүңүз.',
'passwordtooshort' => 'Сырсөз {{PLURAL:$1|1 символдон}} кем эмес болуш керек.',
'password-name-match' => 'Сиздин сырсөзүңүз катышуучу атыңыздан айырмаланыш керек.',
'password-login-forbidden' => 'Бул катышуучунун атын жана сырсөзүн колдонууга тыюу салынган.',
'mailmypassword' => 'Жаңы сырсөздү эл. почта аркылуу жөнөтүү',
'passwordremindertitle' => '{{SITENAME}} үчүн жаңы убактылуу сырсөз',
'noemailcreate' => 'Эл. почтанын анык дарегин көрсөтүшүңүз керек',
'mailerror' => 'Почтаны жөнөтүү кезиндеги ката: $1',
'emailauthenticated' => 'Сиздин почта дарегиңиз аныкталды $2/$3.',
'emailconfirmlink' => 'Электрондук дарегиңизди ырастаңыз',
'emaildisabled' => 'Бул сайт эл. почтанын билдирүүлөрүн жөнөтө албайт.',
'accountcreated' => 'Эсеп жазуусу жаратылды',
'createaccount-title' => '{{SITENAME}} үчүн эсеп жазуусун жаратуу',
'login-abort-generic' => 'Сиздин кирүүңүз ийгиликтүү эмес болду - Үзүлдү',
'loginlanguagelabel' => 'Тил: $1',

# E-mail sending
'php-mail-error-unknown' => "PHP'нин mail() функциясындагы белгисиз ката.",

# Change password dialog
'resetpass' => 'Сырсөздү өзгөртүү',
'resetpass_header' => 'Эсеп жазуусунун сырсөзүн өзгөртүү',
'oldpassword' => 'Эски сырсөз:',
'newpassword' => 'Жаңы сырсөз:',
'retypenew' => 'Жаңы сырсөздү кайта киргизиңиз:',
'resetpass_submit' => 'Сырсөз коюу анан кирүү',
'resetpass_success' => 'Сиздин сырсөзүңүз ийгиликтүү өзгөртүлдү!
Системага кирүү аткарылып жатат...',
'resetpass_forbidden' => 'Сырсөздү өзгөртүүгө мүмкүн эмес',
'resetpass-no-info' => 'Бул баракка түз кайрылыш үчүн, сиз системага киришиңиз керек.',
'resetpass-submit-loggedin' => 'Сырсөздү өзгөртүү',
'resetpass-submit-cancel' => 'Жокко чыгаруу',
'resetpass-temp-password' => 'Убактылуу сырсөз:',

# Special:PasswordReset
'passwordreset' => 'Сырсөздү түшүрүү',
'passwordreset-legend' => 'Сырсөздү түшүрүү',
'passwordreset-disabled' => 'Бул уикиде сырсөздү түшүрүү мүмкүнчүлүгү өчүрүлгөн.',
'passwordreset-username' => 'Катышуучу аты:',
'passwordreset-domain' => 'Домен:',
'passwordreset-capture' => 'Чыккан катты көрүү?',
'passwordreset-email' => 'E-mail дарек:',
'passwordreset-emailtitle' => '{{SITENAME}} сайтындагы эсеп жазуусу жөнүндөгү маалымат',
'passwordreset-emailelement' => 'Катышуучу аты: $1
Убактылуу сырсөз: $2',
'passwordreset-emailsent' => 'Эл. почта аркылуу эске салуу жөнөтүлдү.',
'passwordreset-emailsent-capture' => 'Төмөн жакта жөнөтүлгөн эске салуу кат келтирилди.',
'passwordreset-emailerror-capture' => 'Жаратылган эске салуу каты астында көрсөтүлүп турат, бирок аны катышуучуга жөнөтүү оңунан чыккан жок: $1',

# Special:ChangeEmail
'changeemail' => 'E-mail даректи өзгөртүү',
'changeemail-header' => 'Эл. почтанын дарегин өзгөртүү',
'changeemail-no-info' => 'Бул баракка түз кайрылыш үчүн, сиз системага киришиңиз керек.',
'changeemail-oldemail' => 'Кезектеги e-mail дарек:',
'changeemail-newemail' => 'Жаңы e-mail дарек:',
'changeemail-none' => '(жок)',
'changeemail-submit' => "E-mail'ди өзгөртүү",
'changeemail-cancel' => 'Жокко чыгаруу',

# Edit page toolbar
'bold_sample' => 'Кара текст',
'bold_tip' => 'Кара текст',
'italic_sample' => 'Курсив текст',
'italic_tip' => 'жантык текст',
'link_sample' => 'Шилтеме аты',
'link_tip' => 'Ички шилтеме',
'extlink_sample' => 'http://www.example.com шилтеме аталышы',
'extlink_tip' => 'Сырткы шилтемелерге (http:// префиксин койгонду унутпаңыз)',
'headline_sample' => 'Ат тексти',
'headline_tip' => '2-деңгээлдеги баш аты',
'nowiki_sample' => 'Форматталбаган текстти бул жерге киргизиңиз',
'nowiki_tip' => 'Уики-форматтоого көңүл бурбоо',
'image_tip' => 'Кыстарылган файл',
'media_tip' => 'Файлга шилтеме',
'sig_tip' => 'Кол тамгаңыз жана убакыт мөөрү',
'hr_tip' => 'Туурасынын сызык (жыш колдонбоңуз)',

# Edit pages
'summary' => 'Кыска түшүндүрүү:',
'subject' => 'Тема/баш аты:',
'minoredit' => 'Майда оңдоо',
'watchthis' => 'Бул баракты көзөмөлдөө',
'savearticle' => 'Баракты сактоо',
'preview' => 'Алдын ала көрүү',
'showpreview' => 'Алдын ала көрсөтүү',
'showlivepreview' => 'Ылдам карап чыгуу',
'showdiff' => 'Өзгөртүүлөрдү көрсөтүү',
'anoneditwarning' => "'''Эскертүү:''' Сиз каттоодон өткөн жоксуз.
IP дарегиңиз бул барактын оңдоо тарыхына жазылат.",
'missingcommenttext' => 'Төмөн жакка комментарийиңизди киргизиңиз.',
'blockedtitle' => 'Катышуучу тосмолонгон',
'blockedtext' => 'Сиздин колдонуучу атыңыз же IP дарегиңиз тосмолонгон',
'blockednoreason' => 'себеби көрсөтүлгөн жок',
'whitelistedittext' => 'Баркаты оңдоо үчүн сизге $1 керек.',
'nosuchsectiontitle' => 'Бөлүктүү табууга мүмкүн эмес',
'loginreqtitle' => 'Авторизация талап кылынат',
'loginreqlink' => 'Кирүү',
'accmailtitle' => 'Сырсөз жөнөтүлдү.',
'accmailtext' => ' [[User talk:$1|$1]] үчүн сырсөз $2 ге жөнөтүлдү.',
'newarticle' => '(Жаңы)',
'newarticletext' => "Сиз ачыла элек баракка шилтемени бастыңыз.
Бул баракты ачуу үчүн, ылдый жактагы терезечеге жаза баштаңыз (кошумча маалымат алуу үчүн [[{{MediaWiki:Helppage}}|жардам барагы]] караңыз).
Эгерде Сиз бул жерге жаңылыштык менен кирип калсаңыз, анда браузериңизде '''артка''' баскычын басыңыз.",
'noarticletext' => "Азыр бул баракта текст жок.
Сиз [[Special:Search/{{PAGENAME}}|ушул аталыш менен баракты изде]] башка барактарда 
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} тийиштүү жазууларды таба аласыз],
же '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} ошондой аталыш менен барак ача аласыз]'''</span>.",
'noarticletext-nopermission' => 'Азыр бул баракта текст жок.
Сиз [[Special:Search/{{PAGENAME}}|бул ат жөнүндө эскертүүлөрдү]] башка барактардан таба аласыз, же <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} тийиштүү журналдардын жазууларын таба аласыз]</span>. Бул баракты жаратууга укуктарыңыз жок.',
'userpage-userdoesnotexist' => '"$1" Мындай катышуучу катталган эмес. Ушул баракты түзүүнү же оңдогонду каалганыңыз анык болсун',
'updated' => '(Жаңыртылды)',
'note' => "'''Эскертүү:'''",
'previewnote' => "'''Бул алдын ала көрүнүшү гана болгонун эсиңизге алыңыз.'''
Өзгөртүүлөрүңүз сактала элек!",
'continue-editing' => 'Өзгөртүүүлөрдү улантабыз',
'session_fail_preview' => 'Кечиресиз, байланыш үзүлгөндүктөн сиздин өзгөртүүлөр сакталган жок. Дагы бир жолу аракет кылып көрүңүз. Болбосо, [[Special:UserLogout|logging out]] аткарып, кайра кирип көрүңүз.',
'editing' => 'Оңдоо $1',
'creating' => '$1 түзүлүүдө',
'editingsection' => '$1 (бөлүмү) оңдолууда',
'editingcomment' => ' $1 оңдолууда (жаңы бөлүм)',
'editconflict' => 'Оңдоо конфликти: $1',
'yourtext' => 'Текстиңиз',
'storedversion' => 'Сакталган версия',
'yourdiff' => 'Айырмалар',
'templatesused' => 'Бул баракта колдонулган {{PLURAL:$1|калып}}:',
'templatesusedpreview' => 'Бул алдын ала көрсөтүүдө колдонулган {{PLURAL:$1|шаблон|шаблондор}}:',
'template-protected' => '(корголгон)',
'template-semiprotected' => '(жарым-жартылай корголгон)',
'hiddencategories' => 'Бул барак {{PLURAL:$1|1 жашыруун категориянын|$1 жашыруун категориялардын}} мүчөсү:',
'nocreatetitle' => 'Барактарды жаратуу чектелген',
'nocreate-loggedin' => 'Жаңы барактарды жаратууга сизде уруксат жок.',
'permissionserrors' => 'Кирүү укуктарынын каталары',
'permissionserrorstext-withaction' => 'Сизге $2, төмөнкү {{PLURAL:$1|себеп|себеп}} менен уруксат жок:',
'recreate-moveddeleted-warn' => "'''Эскертүү: Сиз мурун өчүрүлгөн баракты кайра баштап жатасыз.'''

Бул баракты кайра кайтаруу чындап керек экендигине көзүңүз жетсин.
Ыңгайлуулук үчүн төмөндө өчүрүүлөрдүн жана өзгөртүүлөрдүн тизмеси берилген:",
'moveddeleted-notice' => 'Бул барак өчүрүлгөн.
Маалымат үчүн төмөндө өчүрүүлөрдүн жана өзгөртүүлөрдүн тизмеси берилген.',
'log-fulllog' => 'Журналды бүтүн бойдон көрүү',
'edit-conflict' => 'Өзгөртүүлөрдүн конфликти',
'edit-already-exists' => 'Жаңы барак түзүү мүмкүн эмес. Мындай барак бар',
'defaultmessagetext' => 'Жарыяланбасча текст',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Эскертүү:''' Камтылган калыптардын өлчөмү өтө чоң.
Кээ бир калыптар камтылбайт.",
'post-expand-template-inclusion-category' => 'Камтылган калыптардын өлчөмү ашып кеткен барактар',
'post-expand-template-argument-warning' => "'''Эскертүү:''' Бул барак, жок дегенде, абдан чоң көлөмдүү калыптын бир жүйөсүн камтыйт жана  жайылганда өлчөмү абдан чоң болуп кетет. 
Ушул сыяктуу жүйөлөр аттатылды.",
'post-expand-template-argument-category' => 'Калыптардын аттатылган жүйөлөрүн камтыган барактар',
'parser-template-loop-warning' => 'Калыптарда айланма бар:[[$1]]',

# Account creation failure
'cantcreateaccounttitle' => 'Эсеп жазуусун жаратууга мүмкүн эмес',

# History pages
'viewpagelogs' => 'Бул барак үчүн журналды көрсөтүү',
'nohistory' => 'Бул барактын өзгөртүүлөр тарыхы жок',
'currentrev' => 'Акыркы версиясы',
'currentrev-asof' => '$1 -га соңку версиясы',
'revisionasof' => '$1 -деги абалы',
'revision-info' => '$1 карата $2 тарабынан жасалган версия',
'previousrevision' => 'Мурунку версиясы',
'nextrevision' => 'Жаңыраак версиясы →',
'currentrevisionlink' => 'Соңку версиясы',
'cur' => 'учрдг.',
'next' => 'кийинки',
'last' => 'соңку',
'page_first' => 'биринчи',
'page_last' => 'акыркы',
'histlegend' => "Айырмаларды тандоо: Салыштырыла турган версияларлын тушундагы тегеректерди белгилеп туруп \"Enter\"-ди же астындагы баскычты бас.<br />
Түшүндүрүү: '''({{int:cur}})''' = соңку версиясынан айырма, '''({{int:last}})''' = мурунку версиясынан айырма, '''{{int:minoreditletter}}''' = майда оңдоо.",
'history-fieldset-title' => 'Тарыхын кароо',
'history-show-deleted' => 'Өчүрүлгөндөрдү гана',
'histfirst' => 'Эң эски',
'histlast' => 'Соңку',
'historysize' => '({{PLURAL:$1|1 байт}})',
'historyempty' => 'бош',

# Revision feed
'history-feed-title' => 'Өзгөртүүлөр тарыхы',
'history-feed-description' => 'Уикидеги бул барактын өзгөртүү тарыхы',
'history-feed-item-nocomment' => '$1, $2 карата',

# Revision deletion
'rev-deleted-user' => '(катышуучу аты өчүрүлдү)',
'rev-delundel' => 'көрсөтүү/жашыруу',
'rev-showdeleted' => 'көрсөтүү',
'revdelete-nologid-title' => 'Журналдын туура эмес жазуусу',
'revdelete-show-file-submit' => 'Ооба',
'revdelete-hide-text' => 'Версия текстин жашыруу',
'revdelete-hide-image' => 'Файл мазмунун жашыруу',
'revdelete-hide-name' => 'Аракетин жана объектин жашыруу',
'revdelete-hide-comment' => 'Оңдоо баяндамасын жашыруу',
'revdelete-hide-user' => 'Редактордун катышуучу атын/IP-дарегин жашыруу',
'revdelete-radio-same' => '(өзгөртпөө)',
'revdelete-radio-set' => 'Ооба',
'revdelete-radio-unset' => 'Жок',
'revdelete-log' => 'Себеп:',
'revdel-restore' => 'көрүнүшүн өзгөртүү',
'revdel-restore-deleted' => 'өчүрүлгөн версиялар',
'revdel-restore-visible' => 'көрүнүүчү версиялары',
'pagehist' => 'Барактын тарыхы',
'deletedhist' => 'Өчүрүүлөрдүн тарыхы',
'revdelete-reason-dropdown' => '*Өчүрүүнүн стандарттуу себептери
** Автордук укуктарды бузуу
** Орунсуз комментарий же өздүк маалымат
** Орунсуз катышуучу аты
** Потенциалдуу ушактаган маалымат',
'revdelete-otherreason' => 'Башка/кошумча себеп:',
'revdelete-reasonotherlist' => 'Башка себеп',
'revdelete-edit-reasonlist' => 'Өчүрүү себептерин оңдоо',
'revdelete-offender' => 'Барак версиясынын автору:',

# History merging
'mergehistory' => 'Барактардын тарыхын бириктирүү',
'mergehistory-from' => 'Баштапкы барак:',
'mergehistory-into' => 'Максаттык барак:',
'mergehistory-submit' => 'Версияларды бириктирүү',
'mergehistory-invalid-source' => 'Баштапкы барагынын башжазуусуу туура бар болуш керек.',
'mergehistory-invalid-destination' => 'Максаттык барагынын башжазуусуу туура бар болуш керек.',
'mergehistory-same-destination' => 'Баштапкы жана максаттык барактары окшош эмес болуш керек',
'mergehistory-reason' => 'Себеп',

# Merge log
'mergelog' => 'Бириктирүүлөрдүн журналы',
'revertmerge' => 'Бөлүү',

# Diffs
'history-title' => '"$1" өзгөрүүлөр тарыхы',
'difference-multipage' => '(Барактардын арасындагы айырма)',
'lineno' => '$1 -сап:',
'compareselectedversions' => 'Тандалган версияларды салыштыруу',
'showhideselectedversions' => 'Тандалган версияларды көрсөтүү/жашыруу',
'editundo' => 'жокко чыгаруу',
'diff-multi' => '({{PLURAL:$2|катышуучу}} тарабынан жасалган {{PLURAL:$1|аралык версия}} көрсөтүлгөн жок)',

# Search results
'searchresults' => 'Издөө жыйынтыктары',
'searchresults-title' => '"$1" үчүн издөө жыйынтыктары',
'prevn' => 'абалкы {{PLURAL:$1|$1}}',
'nextn' => 'кийинки{{PLURAL:$1|$1}}',
'prevn-title' => 'Мурунку $1 {{PLURAL:$1|жыйынтык}}',
'nextn-title' => 'Кийинки $1 {{PLURAL:$1|жыйынтык}}',
'shown-title' => 'Барактан $1 {{PLURAL:$1|жыйынтыкты}} көрсөтүү',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3) кароо',
'searchmenu-legend' => 'Издөө опциялары',
'searchmenu-exists' => "'''Бул Уикиде \"[[:\$1]]\" деп аталган барак бар.'''",
'searchmenu-new' => "'''Бул Уикиде \"[[:\$1]]\" барагын түз!'''",
'searchhelp-url' => 'Help:Мазмун',
'searchprofile-articles' => 'Негизги барактар',
'searchprofile-project' => 'Жардам жана долбоор барактары',
'searchprofile-images' => 'Мултимедиа',
'searchprofile-everything' => 'Баары',
'searchprofile-advanced' => 'Кеңейтилген',
'searchprofile-articles-tooltip' => '$1 -де издөө',
'searchprofile-project-tooltip' => '$1 -де издөө',
'searchprofile-images-tooltip' => 'Файлдарды издөө',
'searchprofile-everything-tooltip' => 'Баардык барактардан (талкуу барактарды кошо) издөө',
'searchprofile-advanced-tooltip' => 'Белгиленген аталыш мейкиндиктеринде издөө',
'search-result-size' => '$1 ({{PLURAL:$2|1 сөз|$2 сөз}})',
'search-result-category-size' => '{{PLURAL:$1|1 мүчө|$1 мүчө}} ({{PLURAL:$2|1 көмөк категория|$2 көмөк категория}}, {{PLURAL:$3|1 файл|$3 файл}})',
'search-result-score' => 'Релеванттуулук: $1%',
'search-redirect' => '($1 кайра багыттоо)',
'search-section' => '($1 бөлүмү)',
'search-suggest' => 'Ушуну кааладыңызбы: $1',
'search-interwiki-caption' => 'Тектеш долбоорлор',
'search-interwiki-default' => '$1 жыйын.:',
'search-interwiki-more' => '(көбүрөөк)',
'search-relatedarticle' => 'Байланыштуу',
'mwsuggest-disable' => 'AJAX-сунуштарын өчүрүү',
'searcheverything-enable' => 'Бүт ат мейкиндиктеринен издөө',
'searchrelated' => 'байланыштуу',
'searchall' => 'баары',
'showingresultsheader' => "'''$4''' үчүн {{PLURAL:$5|'''$3''' жыйынтыктан '''$1'''-и|'''$1 - $2''' -дан '''$3''' жыйынтык}}",
'search-nonefound' => 'Талапка төп маалымат табылган жок.',
'powersearch' => 'Кеңейтилген издөө',
'powersearch-legend' => 'Кеңейтилген издөө',
'powersearch-ns' => 'Ат мейкиндиктеринен издөө:',
'powersearch-redir' => 'Багыттоолорду чыгаруу',
'powersearch-field' => 'Издөө',
'powersearch-togglelabel' => 'Белги салуу:',
'powersearch-toggleall' => 'Баары',
'powersearch-togglenone' => 'Эчтеке',
'search-external' => 'Тышкы издөө',

# Quickbar
'qbsettings' => 'Навигация панели',
'qbsettings-none' => 'Көрсөтпөө',
'qbsettings-fixedleft' => 'Жылбаган сол',
'qbsettings-fixedright' => 'Жылбаган оң',
'qbsettings-floatingleft' => 'Оома сол',
'qbsettings-floatingright' => 'Оома оң',

# Preferences page
'preferences' => 'Ырастоолор',
'mypreferences' => 'Ырастоолор',
'prefs-edits' => 'Өзгөртүүлөрдүн саны',
'prefsnologin' => 'Система кирген жоксуз',
'changepassword' => 'Сырсөздү өзгөртүү',
'prefs-skin' => 'Тема',
'skin-preview' => 'Алдын ала көрүү',
'datedefault' => 'Жарыяланбасча',
'prefs-beta' => 'Бета-мүмкүнчүлүктөр',
'prefs-datetime' => 'Дата жана убакыт',
'prefs-labs' => 'Эксперименталдык мүмкүнчүлүктөр',
'prefs-user-pages' => 'Катышуучунун барактары',
'prefs-personal' => 'Өздүк маалыматтар',
'prefs-rc' => 'Соңку өзгөрүүлөр',
'prefs-watchlist' => 'Көзөмөл тизмеси',
'prefs-watchlist-days-max' => 'Эң көп $1 {{PLURAL:$1|күн}}',
'prefs-watchlist-edits-max' => 'Эң чоң сан: 1000',
'prefs-watchlist-token' => 'Көзөмөл тизмесинин токени:',
'prefs-resetpass' => 'Сырсөздү өзгөртүү',
'prefs-changeemail' => 'Эл. почтанын дарегин өзгөртүү',
'prefs-setemail' => 'Эл. почтанын дарегин коюу',
'prefs-email' => 'Электрондук почтанын параметрлери',
'prefs-rendering' => 'Сырткы көрүнүш',
'saveprefs' => 'Сактоо',
'resetprefs' => 'Сакталбаган өзгөртүүлөрдү тазалоо',
'restoreprefs' => 'Жарыяланбасча ырастоолорду калыбына келтирүү',
'prefs-editing' => 'Редакторлоо',
'prefs-edit-boxsize' => 'Редакторлоо терезесинин өлчөмү.',
'rows' => 'Сап:',
'columns' => 'Тилке:',
'searchresultshead' => 'Издөө',
'stub-threshold-disabled' => 'Өчүрүлгөн',
'recentchangesdays-max' => 'Эң көп $1 {{PLURAL:$1|күн}}',
'timezonelegend' => 'Сааттык алкак:',
'localtime' => 'Жердик убакыт:',
'timezoneuseoffset' => 'Башка (жылышты көрсөтүңүз)',
'timezoneoffset' => 'Жылыш¹:',
'servertime' => 'Сервер убакыты:',
'timezoneregion-africa' => 'Африка',
'timezoneregion-america' => 'Америка',
'timezoneregion-antarctica' => 'Антарктика',
'timezoneregion-arctic' => 'Арктика',
'timezoneregion-asia' => 'Азия',
'timezoneregion-atlantic' => 'Атлантика океаны',
'timezoneregion-australia' => 'Австралия',
'timezoneregion-europe' => 'Европа',
'timezoneregion-indian' => 'Индий океаны',
'timezoneregion-pacific' => 'Тынч океаны',
'prefs-searchoptions' => 'Издөө',
'prefs-namespaces' => 'Ат мейкиндиктери',
'default' => 'жарыяланбасча',
'prefs-files' => 'Файлдар',
'prefs-custom-css' => 'Өз CSS',
'prefs-custom-js' => 'Өз JavaScript',
'prefs-emailconfirm-label' => 'Эл. почтаны аныктоо:',
'prefs-textboxsize' => 'Редакторлоо терезесинин өлчөмү',
'youremail' => 'Электрондук дарек:',
'username' => 'Колдонуучунун аты:',
'uid' => 'Колдонуучунун ID си:',
'prefs-registration' => 'Каттоо убагы:',
'yourrealname' => 'Анык атыңыз:',
'yourlanguage' => 'Тил:',
'yourvariant' => 'Мазмундун тил варианты:',
'yournick' => 'Жаңы колуңуз:',
'badsig' => 'Туура эмес кол тамга.
HTML-тегдеринин тууралыгын текшериңиз.',
'yourgender' => 'Жыныс:',
'gender-male' => 'Эркек',
'gender-female' => 'Аял',
'email' => 'Электрондук дарек',
'prefs-help-email' => 'Электрондук дарек милдетүү эмес, бирок сырсөздү унутуп калсаңыз ал сырсөздү жиберүүгө керек.',
'prefs-help-email-others' => 'Ошондой эле башкалар сиз менен катышуучу же талкуулоо барактарыңыздагы шилтеме аркылуу байланыш түзүүгө уруксат берүүнү тандай аласыз.
Электрондук дарегиңиз башка кодонуучуларга байланыш түзгөндө көрүнбөйт.',
'prefs-help-email-required' => 'Эл. почтанын дарегин көрсөтүү керек.',
'prefs-info' => 'Негизги маалыматтар',
'prefs-i18n' => 'Интернационализация',
'prefs-signature' => 'Кол тамга',
'prefs-dateformat' => 'Дата форматы',
'prefs-timeoffset' => 'Алкак убакытынын жылышы',
'prefs-advancedediting' => 'Кеңейтилген ырастоолор',
'prefs-advancedrc' => 'Кеңейтилген ырастоолор',
'prefs-advancedrendering' => 'Кеңейтилген ырастоолор',
'prefs-advancedsearchoptions' => 'Кеңейтилген ырастоолор',
'prefs-advancedwatchlist' => 'Кеңейтилген ырастоолор',
'prefs-displayrc' => 'Көрсөтүүнүн ырастоолору',
'prefs-displaysearchoptions' => 'Көрсөтүүнүн ырастоолору',
'prefs-displaywatchlist' => 'Көрсөтүүнүн ырастоолору',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'Эл. почтанын дареги анык көрүнөт',
'email-address-validity-invalid' => 'Эл. почтанын анык дарегин киргизиңиз!',

# User rights
'userrights' => 'Катышуучулардын укуктарын башкаруу',
'userrights-lookup-user' => 'Катышуучу топторун башкаруу',
'userrights-user-editname' => 'Катышуучу атыңызды киргизиңиз:',
'editusergroup' => 'Катышуучу топторун оңдоо',
'userrights-editusergroup' => 'Катышуучу топторун оңдоо',
'saveusergroups' => 'Катышуучу топторун сактоо',
'userrights-groupsmember' => 'Топтордо турат:',
'userrights-reason' => 'Себеп:',
'userrights-changeable-col' => 'Сиз өзгөртө алган топтор',
'userrights-unchangeable-col' => 'Сиз өзгөртө албаган топтор',

# Groups
'group' => 'Топ:',
'group-user' => 'Катышуучулар',
'group-autoconfirmed' => 'Автоаныкталган катышуучулар',
'group-bot' => 'Боттор',
'group-sysop' => 'Администраторлор',
'group-bureaucrat' => 'Бюрократтар',
'group-suppress' => 'Ревизорлор',
'group-all' => '(баары)',

'group-user-member' => '{{GENDER:$1|катышуучу}}',
'group-autoconfirmed-member' => '{{GENDER:$1|автоаныкталган катышуучу}}',
'group-bot-member' => '{{GENDER:$1|бот}}',
'group-sysop-member' => '{{GENDER:$1|администратор}}',
'group-bureaucrat-member' => '{{GENDER:$1|бюрократ}}',
'group-suppress-member' => '{{GENDER:$1|ревизор}}',

'grouppage-user' => '{{ns:project}}:Катышуучулар',
'grouppage-autoconfirmed' => '{{ns:project}}:Автоаныкталган катышуучулар',
'grouppage-bot' => '{{ns:project}}:Боттор',
'grouppage-sysop' => '{{ns:project}}:Администраторлор',
'grouppage-bureaucrat' => '{{ns:project}}:Бюрократтар',
'grouppage-suppress' => '{{ns:project}}:Ревизорлор',

# Rights
'right-read' => 'барактарды карап чыгуу',
'right-edit' => 'Барактарды оңдоо',
'right-move' => 'барактардын атын өзгөртүү',
'right-move-rootuserpages' => 'катышуучулардын түпкү барактарынын атын өзгөртүү',
'right-movefile' => 'файлдардын атын өзгөртүү',
'right-upload' => 'Файлдарды жүктөө',
'right-reupload' => 'Бар болгон файлдардын үстүнөн жаздыруу',
'right-delete' => 'Барактарды өчүрүү',
'right-browsearchive' => 'Өчүрүлгөн барактарды издөө',
'right-suppressionlog' => 'Жеке журналдарды көрүү',
'right-userrights' => 'Бүт катышуучулардын укуктарын оңдоо',

# User rights log
'rightsnone' => '(жок)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'бул баракты окуу',
'action-edit' => 'бул баракты оңдоо',
'action-createpage' => 'барактарды жаратуу',
'action-createtalk' => 'талкуулоо барагын жаратуу',
'action-createaccount' => 'бул эсеп жазуусун жаратуу',
'action-upload' => 'бул файлды жүктөө',
'action-delete' => 'бул баракты өчүрүү',
'action-suppressionlog' => 'бул жеке журналды көрүү',
'action-userrights' => 'бүт катышуучулардын укуктарын оңдоо',
'action-sendemail' => 'электрондук каттарды жөнөтүү',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|өзгөрүү|өзгөрүү}}',
'recentchanges' => 'Соңку өзгөрүүлөр',
'recentchanges-legend' => 'Соңку өзгөртүүлөрдүн ырастоолору',
'recentchanges-summary' => 'Уикидеги соңку өзгөрүүлөрдү ушул барактан көзөмөлдө.',
'recentchanges-feed-description' => 'Ушул агымдагы уикидеги соңку өзгөрүүлөрдү көзөмөлдө.',
'recentchanges-label-newpage' => 'Бул оңдоо жаңы баракты жаратты',
'recentchanges-label-minor' => 'Бул майда оңдоо',
'recentchanges-label-bot' => 'Бул оңдоо бот тарабынан жасалды',
'recentchanges-label-unpatrolled' => 'Бул оңдоо көзөмөлдөн өтө элек.',
'rcnote' => "Ылдый жакта $5, $4 карата соңку {{PLURAL:$2|күндө|'''$2''' күндө}} жасалган {{PLURAL:$1| '''1''' өзгөрүү| '''$1''' өзгөрүү}}.",
'rcnotefrom' => "'''$2''' -тан өзгөрүүлөр ылдый жакта ('''$1''' чейин көрсөтүлдү).",
'rclistfrom' => '$1 күнүнөн баштап жаңы өзгөртүүлөрдү көрсөтүү',
'rcshowhideminor' => 'Майда оңдоолорду $1',
'rcshowhidebots' => 'ботторду $1',
'rcshowhideliu' => '$1 катталган катышуучу',
'rcshowhideanons' => '$1 жашыруун катышуучу',
'rcshowhidepatr' => 'Көзөмөл алдындагы оңдоолорду $1',
'rcshowhidemine' => 'Оңдоолорумду $1',
'rclinks' => 'Соңку $2 күндө жасалган акыркы $1 өзгөртүүлөрдү көрсөтүү<br />$3',
'diff' => 'айырма',
'hist' => 'тарыхы',
'hide' => 'Жашыруу',
'show' => 'Көрсөтүү',
'minoreditletter' => 'м',
'newpageletter' => 'Ж',
'boteditletter' => 'б',
'rc_categories_any' => 'Каалаган',
'rc-enhanced-expand' => 'Кошумча маалыматтарды көрсөтүү (JavaScript талап кылынат)',
'rc-enhanced-hide' => 'Кошумча маалыматтарды жашыруу',

# Recent changes linked
'recentchangeslinked' => 'Тиешелүү өзгөрүүлөр',
'recentchangeslinked-feed' => 'Тиешелүү өзгөрүүлөр',
'recentchangeslinked-toolbox' => 'Байланыштуу өзгөрүүлөр',
'recentchangeslinked-title' => '"$1" үчүн тийиштүү өзгөртүүлөр',
'recentchangeslinked-noresult' => 'Берилген мөөнөттө шилтемеленген барактарда өзгөртүү жок.',
'recentchangeslinked-summary' => 'Бул көрсөтүлгөн (же көрсөтүлгөн категорияга кирген) барактан шилтемеленген барактардагы жакын арада жасалган өзгөрүүлөрдүн тизмеси.
[[Special:Watchlist|Көзөмөл тизмеңиз]]деги барактар калын арип менен белгиленген.',
'recentchangeslinked-page' => 'Барактын аталышы',
'recentchangeslinked-to' => 'Белгиленген барактан шилтемеленген барактардын ордуна өзгөртүулөрдү көрсөтүү',

# Upload
'upload' => 'Файлды жүктөө',
'uploadbtn' => 'Файлды жүктөө',
'uploaderror' => 'Жүктөө катасы',
'uploadlog' => 'жүктөө журналы',
'uploadlogpage' => 'Жүктөөлөрдүн тизмеси',
'filename' => 'Файл аты',
'filedesc' => 'Кыска түшүндүрмө',
'fileuploadsummary' => 'Кыскача баяндама:',
'filereuploadsummary' => 'Файлдагы өзгөрүүлөр:',
'filesource' => 'Булак:',
'uploadedfiles' => 'Жүктөлгөн файлдар',
'savefile' => 'Файлды сактоо',
'uploadedimage' => '"[[$1]]" жүктөлдү',
'upload-source' => 'Баштапкы файл',
'sourcefilename' => 'Булактын файл аты:',
'sourceurl' => 'Булактын URL-дареги:',
'upload-description' => 'Файл баяндамасы',
'upload-success-subj' => 'Ийгиликтүү жүктөлдү',
'upload-failure-subj' => 'Жүктөө көйгөйү',

'upload-proto-error' => 'Туура эмес протокол',
'upload-file-error' => 'Ички ката',
'upload-unknown-size' => 'Белгисиз өлчөм',

# File backend
'backend-fail-closetemp' => 'Убактылуу файлды жабуу оңунан чыкпай жатат.',

# HTTP errors
'http-invalid-url' => 'Туура эмес URL: $1',

'license' => 'Лицензиялоо:',
'license-header' => 'Лицензиялоо',

# Special:ListFiles
'imgfile' => 'файл',
'listfiles' => 'Файлдар тизмеси',
'listfiles_thumb' => 'Миниатюра',
'listfiles_date' => 'Дата',
'listfiles_name' => 'Ат',
'listfiles_user' => 'Катышуучу',
'listfiles_size' => 'Өлчөм',
'listfiles_description' => 'Баяндама',
'listfiles_count' => 'Версиялар',

# File description page
'file-anchor-link' => 'Файл',
'filehist' => 'Файлдын тарыхы',
'filehist-help' => 'Файлдын ошол учурдагы көрүнүшүн кароо үчүн күнү/сааты бөлүмүнө басыңыз',
'filehist-deleteall' => 'баарын өчүрүү',
'filehist-deleteone' => 'өчүрүү',
'filehist-revert' => 'кайтаруу',
'filehist-current' => 'кезектеги',
'filehist-datetime' => 'Дата/Убакыт',
'filehist-thumb' => 'Миниатюра',
'filehist-thumbtext' => '$1 -дагы версиясы үчүн кичирейтилген сүрөтү',
'filehist-nothumb' => 'Миниатюра жок',
'filehist-user' => 'Катышуучу',
'filehist-dimensions' => 'Өлчөмдөр',
'filehist-filesize' => 'Файл өлчөмү',
'filehist-comment' => 'Эскертүү',
'filehist-missing' => 'Файл жок болот',
'imagelinks' => 'Файлды колдонуу',
'linkstoimage' => 'Бул файлга болгон {{PLURAL:$1|шилтеме|$1 шилтеме}} :',
'nolinkstoimage' => 'Бул файлга шилтеме берген барак жок.',
'sharedupload-desc-here' => 'Бул файл $1 -дан  жана башка долбоорлордо пайдаланылышы мүмкүн.
Төмөндө анын [$2 файлды сыпаттоо барагы]нан сыпаттамасы көрсөтүлгөн.',

# File reversion
'filerevert-comment' => 'Себеп:',

# File deletion
'filedelete' => '$1 — өчүрүү',
'filedelete-legend' => 'Файлды өчүрүү',
'filedelete-comment' => 'Себеп:',
'filedelete-submit' => 'Өчүрүү',
'filedelete-reason-otherlist' => 'Башка себеп',
'filedelete-maintenance-title' => 'Файлды өчүрүү оңунан чыкпай жатат',

# MIME search
'mimesearch' => 'MIME боюнча издөө',
'mimetype' => 'MIME-түр:',
'download' => 'жүктөп алуу',

# Unused templates
'unusedtemplates' => 'Колдонулбаган шаблондор',
'unusedtemplateswlh' => 'башка шилтемелер',

# Random page
'randompage' => 'Тушкелди макала',

# Statistics
'statistics' => 'Статистика',
'statistics-header-views' => 'Көрүү статистикасы',
'statistics-header-users' => 'Коолдонуучулардын статистикасы',
'statistics-header-hooks' => 'Башка статистика',
'statistics-articles' => 'Макалалар',
'statistics-pages' => 'Барак',
'statistics-files' => 'Жүктөлгөн файлдар',

'disambiguationspage' => 'Template:көп маанилүү',

'brokenredirects-edit' => 'оңдоо',
'brokenredirects-delete' => 'өчүрүү',

'withoutinterwiki-legend' => 'Префикс',
'withoutinterwiki-submit' => 'Көрсөтүү',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|байт|байт}}',
'nmembers' => '$1{{PLURAL:$1|мүчө|мүчө}}',
'uncategorizedpages' => 'Категияланбаган барактар',
'uncategorizedcategories' => 'Категияланбаган категориялар',
'uncategorizedimages' => 'Категияланбаган файлдар',
'uncategorizedtemplates' => 'Категияланбаган шаблондор',
'unusedcategories' => 'Колдонулбаган категориялар',
'unusedimages' => 'Колдонулбаган файлдар',
'popularpages' => 'Популярдуу барактар',
'wantedcategories' => 'Керек болгон категориялар',
'wantedpages' => 'Керек болгон барактар',
'wantedfiles' => 'Керек болгон файлдар',
'wantedtemplates' => 'Керек болгон шаблондор',
'prefixindex' => 'Бардык барактар префикстери менен',
'shortpages' => 'Кыска макалалар',
'listusers' => 'Катышуучулар тизмеси',
'usercreated' => "$1 күнү $2'да {{GENDER:$3|катталды}}.",
'newpages' => 'Жаңы барактар',
'newpages-username' => 'Катышуучунун аты:',
'ancientpages' => 'Эң эски барактар',
'move' => 'Аталышын өзгөртүү',
'movethispage' => 'Бул барактын атын өзгөртүү',
'pager-newer-n' => '{{PLURAL:$1|жаңыраак 1|жаңыраак $1}}',
'pager-older-n' => '{{PLURAL:$1|эскирээк 1|эскирээк $1}}',

# Book sources
'booksources' => 'Китеп тууралуу маалыматтар',
'booksources-search-legend' => 'Китеп тууралуу маалыматтарды издөө',
'booksources-go' => 'Табуу',

# Special:Log
'specialloguserlabel' => 'Аткаруучу:',
'speciallogtitlelabel' => 'Аталышы:',
'log' => 'Журналдар',

# Special:AllPages
'allpages' => 'Бардык барактар',
'alphaindexline' => '$1 -дан $2 чейин',
'nextpage' => 'Кийинки барак ($1)',
'prevpage' => 'Мурунку барак ($1)',
'allpagesfrom' => '-дан башталган барактарды көрсөтүү:',
'allarticles' => 'Бүт барактар',
'allinnamespace' => '«$1» ат мейкиндигинин бүт барактары',
'allnotinnamespace' => 'Бүт барактар («$1» ат мейкиндигинен башка)',
'allpagesprev' => 'Абалкы',
'allpagesnext' => 'Кийинки',
'allpagessubmit' => 'Аткаруу',
'allpagesprefix' => '- префикси менен барактарды көрсөтүү',

# Special:Categories
'categories' => 'Категориялар',

# Special:LinkSearch
'linksearch-ok' => 'Издөө',
'linksearch-line' => '$1-га $2-дан шилтеме берилди',

# Special:ListUsers
'listusers-submit' => 'Көрсөтүү',
'listusers-noresult' => 'Катышуучу табылган жок.',
'listusers-blocked' => '(блокировкаланды)',

# Special:ActiveUsers
'activeusers' => 'Активдүү катышуучулардын тизмеси',
'activeusers-hidebots' => 'Ботторду жашыруу',
'activeusers-hidesysops' => 'Администраторлорду жашыруу',
'activeusers-noresult' => 'Катышуучулар табылган жок.',

# Special:Log/newusers
'newuserlogpage' => 'Катышуучулардын каттоо журналы',

# Special:ListGroupRights
'listgrouprights-group' => 'Топ',
'listgrouprights-rights' => 'Укуктар',
'listgrouprights-helppage' => 'Help:Топтордун укуктары',
'listgrouprights-members' => '(мүчөлөрдүн тизмеси)',

# E-mail user
'emailuser' => 'Бул катышуучуга кат жиберүү',
'emailusername' => 'Катышуучунун аты:',
'emailusernamesubmit' => 'Жөнөтүү',
'emailfrom' => '- дан',
'emailto' => 'Кимге:',
'emailsubject' => 'Тема:',
'emailmessage' => 'Билдирүү:',
'emailsend' => 'Жөнөтүү',

# Watchlist
'watchlist' => 'Көзөмөл тизмем',
'mywatchlist' => 'Көзөмөл тизмеси',
'watchlistfor2' => '$1 үчүн $2',
'watchnologin' => 'Катталган жок',
'watch' => 'Көзөмөлдөө',
'unwatch' => 'Көзөмөлдөбөө',
'watchlist-details' => 'Талкуу барактарын эсепке албаганда көзөмөл тизмеңизде {{PLURAL:$1|$1 барак|$1 барак}} бар.',
'watchlistcontains' => 'Сиздин көзөмөл тизмеңизде $1 {{PLURAL:$1|барак}} бар.',
'wlshowlast' => 'Соңку $1 саат $2 күн $3 көрсөтүү.',
'watchlist-options' => 'Көзөмөл тизменин ырастоолору',

'changed' => 'өзгөртүлдү',
'created' => 'түзүлдү',

# Delete
'deletepage' => 'Баракты өчүрүү',
'confirm' => 'Аныктоо',
'delete-legend' => 'Өчүрүү',
'actioncomplete' => 'Иш-аракет жыйынтыкталды',
'actionfailed' => 'Аракет натыйжасыз болду',
'dellogpage' => 'Өчүрүүлөрдүн журналы',
'deletecomment' => 'Себеп:',
'deletereasonotherlist' => 'Башка себеп',

# Rollback
'rollbacklink' => 'кайтаруу',

# Protect
'protectlogpage' => 'Коргоо тизмеси',
'protectedarticle' => '"[[$1]]" корголгон',
'protectcomment' => 'Себеп:',
'protect-level-sysop' => 'Администраторлор гана',
'protect-othertime' => 'Башка убакыт:',
'protect-othertime-op' => 'башка убакыт',
'restriction-type' => 'Укуктар:',
'pagesize' => '(байт)',

# Restrictions (nouns)
'restriction-edit' => 'Оңдоо',
'restriction-move' => 'Атын өзгөртүү',
'restriction-create' => 'Жаратуу',
'restriction-upload' => 'Жүктөө',

# Restriction levels
'restriction-level-all' => 'бүт деңгээлдер',

# Undelete
'undeletebtn' => 'Калыбына келтирүү',
'undeletelink' => 'кароо/калыбына келтирүү',
'undeleteviewlink' => 'кароо',
'undeletereset' => 'Түшүрүү',
'undeletecomment' => 'Себеп:',
'undelete-search-submit' => 'Издөө',
'undelete-show-file-submit' => 'Ооба',

# Namespace form on various pages
'namespace' => 'Аталыштар мейкиндиги:',
'invert' => 'Белгиленгенди текскерилетүү',
'blanknamespace' => '(Негизги)',

# Contributions
'contributions' => 'Колдонуучунун салымдары',
'contributions-title' => '$1 үчүн катышуучунун салымдары',
'mycontris' => 'Салымдар',
'contribsub2' => '$1 үчүн ($2)',
'uctop' => '(соңку)',
'month' => 'Айынан (же андан мурун):',
'year' => 'Жылынан (жана андан мурун):',

'sp-contributions-newbies' => 'Жаңы эсеп жазуулардын гана салымдарын көрсөтүү',
'sp-contributions-blocklog' => 'тосмолоолордун журналы',
'sp-contributions-uploads' => 'жүктөөлөр',
'sp-contributions-logs' => 'журналдар',
'sp-contributions-talk' => 'талкуулоо',
'sp-contributions-search' => 'Салымдарымды издөө',
'sp-contributions-username' => 'IP-дарек же катышуучунун аты:',
'sp-contributions-toponly' => 'Соңку версиялары болгон оңдоолорду гана көрсөтүү',
'sp-contributions-submit' => 'Издөө',

# What links here
'whatlinkshere' => 'Шилтемелерди бул жакка',
'whatlinkshere-title' => '"$1" -га шилтеме берген барактар',
'whatlinkshere-page' => 'Барак:',
'linkshere' => "'''[[:$1]]''' барагына шилтеме берген барактар:",
'nolinkshere' => "'''[[:$1]]''' барагына шилтеме берген барак жок.",
'isredirect' => 'кайра багыттоо барагы',
'istemplate' => 'кошуу',
'isimage' => 'файл шилтемеси',
'whatlinkshere-prev' => '{{PLURAL:$1|мурунку}}',
'whatlinkshere-next' => '{{PLURAL:$1|кийинки}}',
'whatlinkshere-links' => '← шилтемелер',
'whatlinkshere-hideredirs' => 'Багыттоолорду $1',
'whatlinkshere-hidetrans' => '$1 кошуулары',
'whatlinkshere-hidelinks' => 'Шилтемелерди $1',
'whatlinkshere-hideimages' => '$1 файл шилтемелери',
'whatlinkshere-filters' => 'Фильтрлер',

# Block/unblock
'block' => 'Катышуучуну блокировкалоо',
'blockip' => 'Колдонуучунуу бөгөттө',
'blockip-title' => 'Колдонуучунуу бөгөттө',
'blockip-legend' => 'Колдонуучуну бөгөттө',
'ipadressorusername' => 'IP-дарек же катышуучу аты:',
'ipbreason' => 'Себеп:',
'ipbreasonotherlist' => 'Башка себеп',
'ipbsubmit' => 'Бул колдонуучуну бөгөттө',
'ipbother' => 'Башка убакыт:',
'ipboptions' => '2 саат:2 hours,1 күн:1 day,3 күн:3 days,1 жума:1 week,2 жума:2 weeks,1 ай:1 month,3 ай:3 months,6 ай:6 months,1 жыл:1 year,мөөнөтсүз:infinite',
'ipbotheroption' => 'башка',
'ipblocklist' => 'Тосмолонгон катышуучулар',
'blocklist-target' => 'Максат',
'blocklist-reason' => 'Себеп',
'ipblocklist-submit' => 'Издөө',
'anononlyblock' => 'анонимдер гана',
'emailblock' => 'кат жөнөтүүгө тыюу салынган',
'ipblocklist-empty' => 'Бөгөт тизмеси бош.',
'blocklink' => 'бөгөттө',
'unblocklink' => 'бөгөттөн чыгар',
'change-blocklink' => 'бөгөттү өзгөрт',
'contribslink' => 'салымдары',
'emaillink' => 'кат жиберүү',
'blocklogpage' => 'Тосмолоолордун журналы',
'blocklogentry' => '[[$1]] тосмолонду, тосмолоо мөөнөтү: $2 $3',
'block-log-flags-anononly' => 'анонимдик катышуучулар гана',
'block-log-flags-nocreate' => 'эсеп жазуусун жаратуу өчүрүлгөн',
'block-log-flags-noemail' => 'кат жөнөтүүгө тыюу салынган',
'block-log-flags-hiddenname' => 'катышуучу аты жашырылган',
'blockme' => 'Мени бөгөттө',
'proxyblocker' => 'Проксини блокировкалоо',

# Developer tools
'lockdb' => 'Маалымат базасын камоо',
'lockbtn' => 'Маалымат базасын камоо',

# Move page
'move-page' => '$1 — атын өзгөртүү',
'move-page-legend' => 'Барактын атын өзгөртүү',
'movearticle' => 'Барактын атын өзгөртүү:',
'movenologin' => 'Сиз система кирген жоксуз',
'newtitle' => 'Жаңы ат:',
'movepagebtn' => 'Барактын атын өзгөртүү',
'pagemovedsub' => 'Барактын аты өзгөртүлдү',
'movepage-moved-redirect' => 'Багыттоо жаратылды.',
'movelogpage' => 'Аталыштардын өзгөртүлүш тарыхы',
'movereason' => 'Себеп:',
'revertmove' => 'кайтаруу',
'delete_and_move' => 'Өчүрүү же атын өзгөртүү',
'delete_and_move_confirm' => 'Ооба, бул баракты өчүрөм',
'immobile-source-page' => 'Бул барактын атын өзгөртсө болбойт.',
'imageinvalidfilename' => 'Максаттык файл аты туура эмес',
'move-leave-redirect' => 'Багыттоону калтыруу',

# Export
'export' => 'Барактарды экспорттоо',
'exportall' => 'Бүт барактарды экспорттоо',
'export-submit' => 'Экспорттоо',
'export-addcattext' => 'Категориядан барактарды кошуу:',
'export-addcat' => 'Кошуу',
'export-addnstext' => 'Ат мейкиндигинен барактарды кошуу:',
'export-addns' => 'Кошуу',
'export-download' => 'Файлга ат коюп сактоо',
'export-templates' => 'Калыптарды кошуу',

# Namespace 8 related
'allmessages' => 'Системалык билдирүүлөр',
'allmessagesname' => 'Ат',
'allmessagesdefault' => 'Белгиленген билдирүүнүн тексти',
'allmessagescurrent' => 'Учурдагы текст',
'allmessages-filter-legend' => 'Фильтр',
'allmessages-filter-unmodified' => 'Өзгөртүлбөгөндөр',
'allmessages-filter-all' => 'Баары',
'allmessages-filter-modified' => 'Өзгөртүлгөндөр',
'allmessages-prefix' => 'Префикс боюнча филтрдөө:',
'allmessages-language' => 'Тил:',
'allmessages-filter-submit' => 'Өтүү',

# Thumbnails
'thumbnail-more' => 'Чоңойтуу',
'filemissing' => 'Файл табылган жок',
'thumbnail_error' => 'Кичирейтилген сүрөттү түзүүдө ката: $1',
'thumbnail_image-type' => 'Сүрөт түрү колдолбойт',

# Special:Import
'import' => 'Барактарды импорттоо',
'importinterwiki' => 'Уики аралык импорт',
'import-interwiki-source' => 'Уики-булак/барак:',
'import-interwiki-history' => 'Бул барактын бүт өзгөртүү тарыхын көчүрүү',
'import-interwiki-templates' => 'Бүт калыптарды кошуу',
'import-interwiki-submit' => 'Импорттоо',
'import-interwiki-namespace' => 'Максаттык ат мейкиндиги:',
'import-interwiki-rootpage' => 'Максаттык түпкү барагы (сөзсүз эмес):',
'import-upload-filename' => 'Файл аты:',
'import-comment' => 'Эскертүү:',
'importstart' => 'Барактарды импорттоо...',
'import-revision-count' => '$1 {{PLURAL:$1|версия}}',
'importnopages' => 'Импорттоого барактар жок.',
'imported-log-entries' => '$1 {{PLURAL:$1|журнал жазуусу}} импорттолду.',
'importfailed' => 'Импорттоо оңунан чыккан жок: <nowiki>$1</nowiki>',
'importunknownsource' => 'Импорттолуп жаткан барактын белгисиз түрү',
'importcantopen' => 'Импорт файлын ачууга мүмкүн эмес',
'importbadinterwiki' => 'Туура эмес интеруики-шилтеме',
'importnotext' => 'Бош же тексти жок',
'importsuccess' => 'Импорттоо аякталды!',
'importnofile' => 'Импорттоо файлы жүктөлгөн жок.',
'importuploaderrorpartial' => 'Импорт файлын жүктөө оңунан чыккан жок.
Ал жарым-жартылай эле жүктөлдү.',
'importuploaderrortemp' => 'Импорт файлын жүктөө оңунан чыккан жок.
Убактылуу папка жок.',
'import-parse-failure' => "Импорттоо учурундагы XML'ди талдоо катасы",
'import-noarticle' => 'Импорттоого барактар жок!',
'import-nonewrevisions' => 'Бүт версиялар мурда импорттолгон.',
'import-upload' => 'XML-маалыматтарды жүктөө',
'import-token-mismatch' => 'Сеанстын маалыматтары жоготулду.
Дагы аракет кылып көрүңүз.',
'import-invalid-interwiki' => 'Көрсөтүлгөн уикиден импорттоого мүмкүн эмес.',

# Import log
'importlogpage' => 'Импорт журналы',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|версия}}',
'import-logentry-interwiki' => '«$1» — уики аралык импорту',

# JavaScriptTest
'javascripttest' => "JavaScript'ти текшерүү",
'javascripttest-title' => '$1 үчүн текшерүү жүргүзүлүп жатат',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Катышуучу барагыңыз',
'tooltip-pt-mytalk' => 'Талкуулоо барагыңыз',
'tooltip-pt-anontalk' => 'Бул IP дарекке арналган талкуулоо барагы',
'tooltip-pt-preferences' => 'Ырастоолоруңуз',
'tooltip-pt-watchlist' => 'Өзгөрүүлөрүн көзөмөлгө алган барактардын тизмеси',
'tooltip-pt-mycontris' => 'Салымдарыңыздын тизмеси',
'tooltip-pt-login' => 'Сизге системада катталууга сунуш кылынат, бирок милдеттүү эмес',
'tooltip-pt-logout' => 'Иш сеансын аяктоо',
'tooltip-ca-talk' => 'Барактын мазмунун талкуулоо',
'tooltip-ca-edit' => 'Сиз бул баракты оңдой аласыз. Кичи пейилдикке, сактоодон мурда алдын ала көрсөтүү баскычын колдонуңуз.',
'tooltip-ca-addsection' => 'Жаңы бөлүмдү жаратуу',
'tooltip-ca-viewsource' => 'Бул барак корголгон.
Сиз анын кайнарын көрө аласыз',
'tooltip-ca-history' => 'Бул барактын мурунку оңдоолору',
'tooltip-ca-protect' => 'Бул баракты коргоо',
'tooltip-ca-delete' => 'Бул баракты өчүрүү',
'tooltip-ca-move' => 'Барактын атын өзгөртүү',
'tooltip-ca-watch' => 'Бул баракты көзөмөл тизмеңизге кошуңуз',
'tooltip-ca-unwatch' => 'Бул баракты көзөмөл тизмеңизден алып салыңыз',
'tooltip-search' => '{{SITENAME}} издөө',
'tooltip-search-go' => 'Так ушундай аталыштагы баракты көрсөтүү',
'tooltip-search-fulltext' => 'Ушул текст менен барактарды издөө',
'tooltip-p-logo' => 'Башбаракка өтүү',
'tooltip-n-mainpage' => 'Башбаракка өтүү',
'tooltip-n-mainpage-description' => 'Башбаракка өтүү',
'tooltip-n-portal' => 'Долбоор тууралуу, эмне жасай аласыз, кайсы жерде эмне бар жөнүндө',
'tooltip-n-currentevents' => 'Учурдагы окуялар тууралуу кошумча маалымат табуу',
'tooltip-n-recentchanges' => 'Уикидеги соңку өзгөртүүлөрдүн тизмеси',
'tooltip-n-randompage' => 'Тушкелди баракты ачып кара',
'tooltip-n-help' => 'Маалымат алуу үчүн',
'tooltip-t-whatlinkshere' => 'Ушул жерге шилтемеси бар бардык уики барактардын тизмеси',
'tooltip-t-recentchangeslinked' => 'Бул барактан шилтеме берилген барактардагы соңку өзгөрүүлөр',
'tooltip-feed-atom' => 'Бул барак үчүн Atom агымы',
'tooltip-t-contributions' => 'Бул катышуучунун салымдарынын тизмеси',
'tooltip-t-emailuser' => 'Бул катышуучуга кат жиберүү',
'tooltip-t-upload' => 'Файлдарды жүктөө',
'tooltip-t-specialpages' => 'Бүт кызмат барактарынын тизмеси',
'tooltip-t-print' => 'Бул барактын басып чыгарууга ылайыктуу түрү',
'tooltip-t-permalink' => 'Барактын бул версиясына туруктуу шилтеме',
'tooltip-ca-nstab-main' => 'Барактын мазмунун кароо',
'tooltip-ca-nstab-user' => 'Катышуучунун барагын көрсөтүү',
'tooltip-ca-nstab-media' => 'Медиа барагын көрүү',
'tooltip-ca-nstab-special' => 'Бул кызмат барагы, аны оңдой албайсыз',
'tooltip-ca-nstab-project' => 'Долбоор барагын көрүү',
'tooltip-ca-nstab-image' => 'Файл барагын көрүү',
'tooltip-ca-nstab-mediawiki' => 'Системалык билдирүүсүн көрүү',
'tooltip-ca-nstab-template' => 'Калыпты көрүү',
'tooltip-ca-nstab-help' => 'Жардам барагын көрүү',
'tooltip-ca-nstab-category' => 'Категория барагын көрүү',
'tooltip-minoredit' => 'Муну майда оңдоо деп белгилөө',
'tooltip-save' => 'Өзгөртүүлөрүңүздү сактоо',
'tooltip-preview' => 'Кичи пейлдикке, өзгөртүүлөрдү алдын ала көрсөтүүнү сактоодон мурун колдонуңуз!',
'tooltip-diff' => 'Тексттке киргизген өзгөртүүлөрдү көрсөтүү',
'tooltip-compareselectedversions' => 'Бул барактын тандалган эки версиясынын айырмаларын кароо',
'tooltip-watch' => 'Бул баракты көзөмөл тизмеңизге кошуңуз',
'tooltip-watchlistedit-raw-submit' => 'Көзөмөл тизмесин жаңыртуу',
'tooltip-upload' => 'Жүктөөнү баштоо',
'tooltip-rollback' => '"Кайтар" бир баскыч менен бул барактын соңку оңдоочусунун өзгөртүүлөрүн алып салат',
'tooltip-undo' => 'Киргизилген оңдоону алып салат жана жокко чыгаруунун себебин белгилөөгө мүмкүнчүлүк берип алдын ала көрсөтүүнү ачат',
'tooltip-preferences-save' => 'Ырастоолорду сактоо',
'tooltip-summary' => 'Кыска баяндаманы киргизиңиз',

# Attribution
'others' => 'башкалар',
'anonusers' => '{{SITENAME}} анонимдик {{PLURAL:$2|катышуучу}} $1',
'creditspage' => 'Алкыштар',

# Info page
'pageinfo-title' => '«$1» үчүн маалымат',
'pageinfo-header-basic' => 'Негизги маалыматтар',
'pageinfo-header-edits' => 'Оңдоо тарыхы',
'pageinfo-display-title' => 'Көрсөтүлгөн башжазуусу',
'pageinfo-article-id' => 'Барактын идентификатору',
'pageinfo-views' => 'Көрүү саны',
'pageinfo-redirects-name' => 'Бул баракка багыттоолор',
'pageinfo-firstuser' => 'Барактын жаратуучусу',
'pageinfo-lastuser' => 'Акыркы редактор',

# Patrol log
'patrol-log-page' => 'Патрулдоо журналы',

# Browsing diffs
'previousdiff' => '← Эскисин оңдоо',
'nextdiff' => 'Жаңысын оңдоо →',

# Media information
'file-info-size' => '$1 × $2 пиксель, файлдын көлөмү: $3, MIME түрү: $4',
'file-nohires' => 'Мындан чоңураак чечим жок.',
'svg-long-desc' => 'SVG файл, шарттуу түрдө $1 × $2 пиксел, файлдын көлөмү: $3',
'show-big-image' => 'Толук чечими',

# Special:NewFiles
'newimages' => 'Жаңы файлдардын галереясы',
'newimages-legend' => 'Фильтр',
'newimages-label' => 'Файл аты (же анын жартысы):',
'showhidebots' => '($1 бот)',
'noimages' => 'Көрүүгө эчтеке жок.',
'ilsubmit' => 'Издөө',
'bydate' => 'дата боюнча',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 секунда}}',
'minutes' => '{{PLURAL:$1|$1 мүнөт}}',
'hours' => '{{PLURAL:$1|$1 саат}}',
'days' => '{{PLURAL:$1|$1 күн}}',
'ago' => '$1 мурун',

# Bad image list
'bad_image_list' => 'Төмөнкү калыпта болуш керек:

Тизмедегилер гана окулат (* белги менен башталган саптар).
Саптын биринчи шилтемеси койгонго тыюу салынган файлга шилтеме болуш керек.
Ошол саптагы кийинки шилтемелер айрыкча каралып, же файл киргизиле бере турган макалалар.',

# Metadata
'metadata' => 'Метамаалыматтар',
'metadata-help' => 'Бул файл адатта санарип камера же сканнер кошуучу маалыматтарды камтыйт. 
Эгерде файл баштапкы абалынан өзгөртүлсө, анда анын кээ бир сыпаттары толук чагылдырылбашы мүмкүн.',
'metadata-fields' => 'Төмөндө тизмеленген сүрөт метамаалыматтарынын саптары метамаалыматтардын жадыбалы түрүлүү учурда сүрөт барагына кошумчаланат.
Калгандары баштапкы абалда (өзгөртүлбөсө) көргөзүлбөйт.
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

# EXIF tags
'exif-imagewidth' => 'Туурасы',
'exif-imagelength' => 'Бийиктик',
'exif-imagedescription' => 'Сүрөт аты',
'exif-artist' => 'Автор',
'exif-pixelydimension' => 'Сүрөттүн туурасы',
'exif-pixelxdimension' => 'Сүрөттүн бийиктиги',
'exif-usercomment' => 'Колдонуучунун комментарийи',
'exif-relatedsoundfile' => 'Байланыштуу аудио-файл',
'exif-fnumber' => 'Диафрагманын саны',
'exif-lightsource' => 'Жарыктын булагы',
'exif-flash' => 'Жарк',
'exif-filesource' => 'Файл булагы',
'exif-scenetype' => 'Сахнанын түрү',
'exif-contrast' => 'Карама-каршылык',
'exif-gpslatitude' => 'Кеңдик',
'exif-gpslongitude' => 'Узундук',
'exif-gpsaltitude' => 'Бийиктик',
'exif-gpstimestamp' => 'GPS убакыты (атомдук саат)',
'exif-gpsspeedref' => 'Ылдамдыктын өлчөө бирдиги',
'exif-gpsdatestamp' => 'Дата',
'exif-jpegfilecomment' => 'JPEG-файл үчүн эскертүү',
'exif-keywords' => 'Ачкыч сөздөр',
'exif-countrydest' => 'Көрсөтүлгөн өлкө',
'exif-citydest' => 'Көрсөтүлгөн шаар',
'exif-objectname' => 'Кыска ат',
'exif-source' => 'Булак',
'exif-contact' => 'Байланыш маалыматы',
'exif-writer' => 'Тексттин автору',
'exif-languagecode' => 'Тил',
'exif-iimversion' => 'IIM версиясы',
'exif-iimcategory' => 'Категория',
'exif-identifier' => 'Идентификатор',
'exif-label' => 'Белги',
'exif-rating' => 'Баа (5тен)',
'exif-copyrighted' => 'Автордук-укуктук статус',
'exif-copyrightowner' => 'Автордук укуктардын ээси',
'exif-usageterms' => 'Колдонуу шарттары',
'exif-pngfilecomment' => 'PNG-файл үчүн эскертүү',
'exif-disclaimer' => 'Жоопкерчиликтен баш тартуу',
'exif-contentwarning' => 'Мазмун жөнүндө эскертүү',
'exif-giffilecomment' => 'GIF-файл үчүн эскертүү',
'exif-intellectualgenre' => 'Объекттин түрү',
'exif-subjectnewscode' => 'Теманын коду',

# EXIF attributes
'exif-compression-1' => 'Кыстырылбаган',

'exif-copyrighted-true' => 'Автордук укук менен корголгон',
'exif-copyrighted-false' => 'Коомдук мүлк',

'exif-unknowndate' => 'Белгисиз дата',

'exif-orientation-1' => 'Нормалдуу',

'exif-exposureprogram-1' => 'Кол менен',
'exif-exposureprogram-2' => 'Программалык режим (нормалдуу)',

'exif-subjectdistance-value' => '$1 метр',

'exif-meteringmode-0' => 'Белгисиз',
'exif-meteringmode-1' => 'Орточо',
'exif-meteringmode-5' => 'Матрицалуу',
'exif-meteringmode-6' => 'Жарым-жартылай',
'exif-meteringmode-255' => 'Башка',

'exif-lightsource-0' => 'Белгисиз',
'exif-lightsource-4' => 'Жарк',
'exif-lightsource-11' => 'Көлөкө',
'exif-lightsource-255' => 'Жарыктын башка булагы',

# Flash modes
'exif-flash-mode-3' => 'автоматтык режим',

'exif-focalplaneresolutionunit-2' => 'дюйм',

'exif-sensingmethod-1' => 'Аныкталбаган',

'exif-scenecapturetype-0' => 'Стандарттуу',
'exif-scenecapturetype-1' => 'Ландшафт',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Түнкү тартуу',

'exif-gaincontrol-0' => 'Жок',

'exif-contrast-0' => 'Кадимки',
'exif-contrast-1' => 'Жумшак жогорулатуу',
'exif-contrast-2' => 'Катуу жогорулатуу',

'exif-saturation-0' => 'Кадимки',

'exif-sharpness-0' => 'Кадимки',
'exif-sharpness-1' => 'Жумшак жогорулатуу',
'exif-sharpness-2' => 'Катуулатуу',

'exif-subjectdistancerange-0' => 'Белгисиз',
'exif-subjectdistancerange-1' => 'Макротартуу',
'exif-subjectdistancerange-2' => 'Жакын аралыктагы тартуу',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Километр',
'exif-gpsdestdistance-m' => 'Миля',

'exif-gpsdop-excellent' => 'Мыкты ($1)',
'exif-gpsdop-good' => 'Жакшы ($1)',
'exif-gpsdop-moderate' => 'Орточо ($1)',
'exif-gpsdop-poor' => 'Начар ($1)',

'exif-dc-contributor' => 'Авторлоштор',
'exif-dc-date' => 'Дата(лар)',
'exif-dc-publisher' => 'Бастыруучу',
'exif-dc-rights' => 'Укуктар',
'exif-dc-source' => 'Баштапкы медиа',
'exif-dc-type' => 'Медианын түрү',

'exif-iimcategory-fin' => 'Экономика жана бизнес',
'exif-iimcategory-edu' => 'Билим',
'exif-iimcategory-evn' => 'Айлана чөйрө',
'exif-iimcategory-hth' => 'Ден соолук',
'exif-iimcategory-lab' => 'Эмгек',
'exif-iimcategory-pol' => 'Саясат',
'exif-iimcategory-rel' => 'Дин жана ишеним',
'exif-iimcategory-sci' => 'Илим жана техника',
'exif-iimcategory-soi' => 'Социалдык маселелер',
'exif-iimcategory-spo' => 'Спорт',
'exif-iimcategory-wea' => 'Аба ырайы',

# External editor support
'edit-externally' => 'Бул файлды сырткы программа колдонуу аркылуу оңдоо',
'edit-externally-help' => '(Толук маалымат алуу үчүн [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] барагына кайрылсаңыз болот)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'баары',
'namespacesall' => 'баары',
'monthsall' => 'баары',
'limitall' => 'баары',

# E-mail address confirmation
'confirmemail' => 'Электрондук даректи аныктоо',
'confirmemail_loggedin' => 'Электрондук дарегиңиз аныкталды.',

# Delete conflict
'recreate' => 'Кайтадан жаратуу',

# action=purge
'confirm_purge_button' => 'OK',

# action=watch/unwatch
'confirm-watch-button' => 'ОК',
'confirm-unwatch-button' => 'ОК',

# Multipage image navigation
'imgmultipageprev' => '← мурунку барак',
'imgmultipagenext' => 'кийинки барак →',
'imgmultigo' => 'Өтүү!',
'imgmultigoto' => '$1 барагына өтүү',

# Table pager
'ascending_abbrev' => 'өсүү',
'descending_abbrev' => 'кемүү',
'table_pager_next' => 'Кийинки барак',
'table_pager_prev' => 'Мурунку барак',
'table_pager_first' => 'Биринчи барак',
'table_pager_last' => 'Акыркы барак',
'table_pager_limit_submit' => 'Аткаруу',
'table_pager_empty' => 'Табылган жок',

# Live preview
'livepreview-loading' => 'Жүктөө...',
'livepreview-ready' => 'Жүктөө… Даяр!',

# Watchlist editor
'watchlistedit-raw-titles' => 'Жазуулар:',
'watchlistedit-raw-submit' => 'Көзөмөл тизмесин жаңыртуу',

# Watchlist editing tools
'watchlisttools-view' => 'Тийиштүү өзгөрүүлөрдү көрсөтүү',
'watchlisttools-edit' => 'Көзөмөл тизмесин кароо жана оңдоо',
'watchlisttools-raw' => 'Жетиле элек көзөмөл тизмени оңдоо',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Эскертүү:\'\'\' "$2" белгиленген ылгоочу ачкыч "$1" мурунку белгиленген ылгоочу ачкычты жокко чыгарат.',

# Special:Version
'version' => 'Версия',
'version-extensions' => 'Орнотулган кеңейтүүлөр',
'version-specialpages' => 'Кызмат барактары',
'version-variables' => 'Өзгөрмөлөр',
'version-skins' => 'Темалар',
'version-other' => 'Башка',
'version-version' => '(Версия $1)',
'version-license' => 'Лицензия',
'version-software' => 'Орнотулган программалык камсыздоо',
'version-software-product' => 'Продукт',
'version-software-version' => 'Версия',
'version-entrypoints-header-url' => 'URL',

# Special:FilePath
'filepath' => 'Файл жолу',
'filepath-page' => 'Файл:',
'filepath-submit' => 'Өтүү',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Файл аты:',
'fileduplicatesearch-submit' => 'Издөө',

# Special:SpecialPages
'specialpages' => 'Кызмат барактары',
'specialpages-group-other' => 'Башка кызмат барактары',
'specialpages-group-login' => 'Кирүү / Каттоо',
'specialpages-group-pagetools' => 'Барак аспаптары',
'specialpages-group-spam' => 'Спам үчүн аспаптар',

# Special:BlankPage
'blankpage' => 'Бош барак',

# External image whitelist
'external_image_whitelist' => ' #Бул сапты болгондой калтыруу<pre>
#Туруктуу айтылыштардын бөлүмдөрүн (// арасындагы бөлүмүн гана) астына жайгаштыру 
#Алар сырткы сүрөттөрдүн URL менен байланыштырылат
#Ылайыктуулары сүрөт болуп көрсөтүлөт, калгандары сүрөттөргө шилтеме болуп көрсөтүлөт
## менен башталган саптар, түшүндүрмө болуп эсептелет
#Баш же кичине тамга айырмасыз

#Туруктуу айтылыштардын бөлүмдөрүн ушул саптын үстүнө жайгаштыр. Бул сапты болгондой калтыруу.</pre>',

# Special:Tags
'tag-filter' => '[[Special:Tags|Белги]] фильтри:',
'tag-filter-submit' => 'Фильтрдөө',
'tags-title' => 'Белгилер',
'tags-tag' => 'Белгинин аты',
'tags-hitcount-header' => 'Белгиленген өзгөрүүлөр',
'tags-edit' => 'оңдоо',
'tags-hitcount' => '$1 {{PLURAL:$1|өзгөрүү}}',

# Special:ComparePages
'comparepages' => 'Барактарды салыштыруу',
'compare-selector' => 'Барактардын версияларын салыштыруу',
'compare-page1' => 'Биринчи барак',
'compare-page2' => 'Экинчи барак',
'compare-rev1' => 'Биринчи версия',
'compare-rev2' => 'Экинчи версия',
'compare-submit' => 'Салыштыруу',

# Database error messages
'dberr-header' => 'Бул уикиде көйгөйлөр бар болуп жатат',

# HTML forms
'htmlform-required' => 'Бул чоңдук керек болот',
'htmlform-submit' => 'Жөнөтүү',
'htmlform-reset' => 'Өзгөртүүлөрдү жокко чыгаруу',
'htmlform-selectorother-other' => 'Башка',

# New logging system
'logentry-delete-delete' => '$1 катышуучу $3 барагын өчүрдү',
'revdelete-content-hid' => 'мазмун жашырылган',
'revdelete-summary-hid' => 'оңдоонун баяндамасы жашырылган',
'revdelete-uname-hid' => 'катышуучу аты жашырылган',
'revdelete-unrestricted' => 'администраторлор үчүн чектөөлөр алынды',
'logentry-newusers-newusers' => '$1 эсеп жазуусу жаратылды',
'logentry-newusers-create' => '$1 эсеп жазуусу жаратылды',
'logentry-newusers-create2' => '$1 эсеп жазуусун жаратты',
'logentry-newusers-autocreate' => 'Автоматтуу түрдө $1 эсеп жазуусу жаратылды',
'newuserlog-byemail' => 'сырсөз эл. почта аркылуу жөнөтүлдү',

# Feedback
'feedback-subject' => 'Тема:',
'feedback-message' => 'Билдирүү:',
'feedback-cancel' => 'Жокко чыгаруу',
'feedback-submit' => 'Пикир жөнөтүү',
'feedback-adding' => 'Баракка пикирди кошуу…',
'feedback-error1' => "Ката. API'ден белгисиз натыйжа",
'feedback-error2' => 'Ката: Оңдоо оңунан чыккан жок',
'feedback-error3' => "Ката: API'ден жооп жок",
'feedback-close' => 'Даяр',
'feedback-bugnew' => 'Мен текшердим. Жаңы ката жөнүндө маалымдоо',

# Search suggestions
'searchsuggest-search' => 'Издөө',
'searchsuggest-containing' => 'кармагандар...',

# API errors
'api-error-badtoken' => 'Ички ката: анык эмес токен.',
'api-error-file-too-large' => 'Сиз жөнөткөн файл өтө чоң.',
'api-error-filename-tooshort' => 'Файл аты өтө кыска.',
'api-error-filetype-banned' => 'Бул файл түрүнө тыюу салынган.',
'api-error-illegal-filename' => 'Жарабай турган файл аты.',
'api-error-unclassified' => 'Белгисиз ката пайда болду.',
'api-error-unknown-code' => 'Белгисиз ката: "$1".',
'api-error-unknown-warning' => 'Белгисиз эскертүү: "$1".',
'api-error-unknownerror' => 'Белгисиз ката: «$1».',
'api-error-uploaddisabled' => 'Бул уикиде файлдарды жүктөө мүмкүнчүлүгү өчүрүлгөн.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|секунда}}',
'duration-minutes' => '$1 {{PLURAL:$1|мүнөт}}',
'duration-hours' => '$1 {{PLURAL:$1|саат}}',
'duration-days' => '$1 {{PLURAL:$1|күн}}',
'duration-weeks' => '$1 {{PLURAL:$1|жума}}',
'duration-years' => '$1 {{PLURAL:$1|жыл}}',
'duration-decades' => '$1 {{PLURAL:$1|оң жылдык мөөнөт}}',
'duration-centuries' => '$1 {{PLURAL:$1|кылым}}',
'duration-millennia' => '$1 {{PLURAL:$1|миң жылдык мөөнөт}}',

);
