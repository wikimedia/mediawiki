<?php
/** Tuvinian (тыва дыл)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Agilight
 * @author Andrijko Z.
 * @author Krice from Tyvanet.com
 * @author Sborsody
 * @author friends at tyvawiki.org
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Тускай',
	NS_TALK             => 'Чугаа',
	NS_USER             => 'Aжыглакчы',
	NS_USER_TALK        => 'Aжыглакчы_чугаазу',
	NS_PROJECT_TALK     => '$1_чугаазу',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_чугаазу',
	NS_MEDIAWIKI        => 'МедиаВики',
	NS_MEDIAWIKI_TALK   => 'МедиаВики_чугаазу',
	NS_TEMPLATE         => 'Хээ',
	NS_TEMPLATE_TALK    => 'Хээ_чугаазу',
	NS_HELP             => 'Дуза',
	NS_HELP_TALK        => 'Дуза_чугаазу',
	NS_CATEGORY         => 'Бөлүк',
	NS_CATEGORY_TALK    => 'Бөлүк_чугаазу',
);

$namespaceAliases = array(
	'Aжыглакчы_чугаа' => NS_USER_TALK,
	'$1_чугаа'        => NS_PROJECT_TALK,
	'Чурук'           => NS_FILE,
	'Чурук_чугаа'     => NS_FILE_TALK,
	'МедиаВики_чугаа' => NS_MEDIAWIKI_TALK,
	'Хээ_чугаа'       => NS_TEMPLATE_TALK,
	'Дуза_чугаа'      => NS_HELP_TALK,
	'Бөлүк_чугаа'     => NS_CATEGORY_TALK,
);

$bookstoreList = array(
	'ОЗОН' => 'http://www.ozon.ru/?context=advsearch_book&isbn=$1',
	'Books.Ru' => 'http://www.books.ru/shop/search/advanced?as%5Btype%5D=books&as%5Bname%5D=&as%5Bisbn%5D=$1&as%5Bauthor%5D=&as%5Bmaker%5D=&as%5Bcontents%5D=&as%5Binfo%5D=&as%5Bdate_after%5D=&as%5Bdate_before%5D=&as%5Bprice_less%5D=&as%5Bprice_more%5D=&as%5Bstrict%5D=%E4%E0&as%5Bsub%5D=%E8%F1%EA%E0%F2%FC&x=22&y=8',
	'Яндекс.Маркет' => 'http://market.yandex.ru/search.xml?text=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1',
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'PriceSCAN' => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
	'Barnes & Noble' => 'http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1'
);

$fallback8bitEncoding = "windows-1251";

$messages = array(
# User preference toggles
'tog-underline' => 'Холбааны шыяры:',
'tog-justify' => 'Арынның дооразының аайы-биле сөзүглелди дескилээри',
'tog-hideminor' => 'Сөөлгү өскерлиишкиннер арында бичии өскерлиишкиннерни чажырар',
'tog-hidepatrolled' => 'Амгы өскерлиишкиннер арында истээн өскерлиишкиннерни чажырары',
'tog-newpageshidepatrolled' => 'Чаа арыннарның даңзындан истээн арыннарны чажырары',
'tog-usenewrc' => 'Чаа өскерлиишкиннерниң өөделеттинген даңзызын ажыглаар (JavaScript херек)',
'tog-numberheadings' => 'Эгелерин авто-санаар',
'tog-showtoolbar' => 'Өскертир херекселдерни көргүзер (JavaScript)',
'tog-editondblclick' => 'Арынны өскертирде ийи катап базар (JavaScript)',
'tog-editsection' => '[өскертири] деп холбаалар-биле section editing enable.',
'tog-rememberpassword' => 'Мени бо компьютерге сактыры ($1 {{PLURAL:$1|хүн|хүн}} ишти)',
'tog-watchcreations' => 'Мээң чаяан арыннарымны хайгаарал даңзымче немээри.',
'tog-watchdefault' => 'Мээң өскерткен арыннарымны хайгаарал даңзымче немээри.',
'tog-watchmoves' => 'Мээң катап адаан арыннарымны хайгаарал даңзымче немээри.',
'tog-watchdeletion' => 'Мээң казаан арыннарымны хайгаарал даңзымче немээри.',
'tog-oldsig' => 'Амгы хол үжүү:',
'tog-watchlisthideown' => 'Хайгаарал даңзызындан эдиглеримни чажыр',
'tog-watchlisthidebots' => 'Хайгаарал даңзызындан роботтарның эдиглерин чажыр',
'tog-watchlisthideminor' => 'Хайгаарал даңзызындан бичии эдиглерни чажыр',
'tog-watchlisthidepatrolled' => 'Хайгаарал даңзындан истээн өскерлиишкиннерны чажырары',
'tog-showhiddencats' => 'Чажыт бөлүктерни көргүзери',

'underline-always' => 'Кезээде',
'underline-never' => 'Кажан-даа',
'underline-default' => 'Кештиң азы веб-браузерниң ниити үнези',

# Font style option in Special:Preferences
'editfont-default' => 'Веб-браузерниң ниити үнези',

# Dates
'sunday' => 'Улуг хүн',
'monday' => 'Бир дугаар хүн',
'tuesday' => 'Ийи дугаар хүн',
'wednesday' => 'Үш дугаар хүн',
'thursday' => 'Дөрт дугаар хүн',
'friday' => 'Беш дугаар хүн',
'saturday' => 'Чартык улуг хүн',
'sun' => 'Улуг-хүн',
'mon' => 'Пн',
'tue' => 'Вт',
'wed' => 'Ср',
'thu' => 'Чт',
'fri' => 'Пт',
'sat' => 'Сб',
'january' => 'Бир ай',
'february' => 'ийи ай',
'march' => 'Үш ай',
'april' => 'Дөрт ай',
'may_long' => 'Беш ай',
'june' => 'Алды ай',
'july' => 'Чеди ай',
'august' => 'Сес ай',
'september' => 'Тос ай',
'october' => 'Он ай',
'november' => 'Он бир ай',
'december' => 'Он ийи ай',
'january-gen' => 'Бир айның',
'february-gen' => 'Ийи айның',
'march-gen' => 'Үш айның',
'april-gen' => 'Дөрт айның',
'may-gen' => 'Беш айның',
'june-gen' => 'Алды айның',
'july-gen' => 'Чеди айның',
'august-gen' => 'Сес айның',
'september-gen' => 'Тос айның',
'october-gen' => 'Он айның',
'november-gen' => 'Он бир айның',
'december-gen' => 'Он ийи айның',
'jan' => '1 ай',
'feb' => '2 ай',
'mar' => '3 ай',
'apr' => '4 ай',
'may' => '5 ай',
'jun' => '6 ай',
'jul' => '7 ай',
'aug' => '8 ай',
'sep' => '9 ай',
'oct' => '10 ай',
'nov' => '11 ай',
'dec' => '12 ай',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Бөлүк|Бөлүктер}}',
'category_header' => '«$1» деп бөлүктүң арыннары',
'subcategories' => 'Адаккы бөлүктер',
'category-media-header' => '«$1» деп бөлүкте файлдар',
'category-empty' => "''Амгы бо бөлүкте медиа база арыннар чок.''",
'hidden-categories' => '{{PLURAL:$1|Чажыт бөлүк|Чажыт бөлүктер}}',
'hidden-category-category' => 'Чажыт бөлүктер',
'category-subcat-count' => '{{PLURAL:$2|Бо бөлүк чүгле дараазында адыр-бөлүклүг.|Бо бөлүктүң шупту $2 адыр-бөлүүнүң аразындан дараазында $1 адыр-бөлүктү көргүскен.}}',
'category-subcat-count-limited' => 'Бо бөлүк {{PLURAL:$1|бир|$1}} адаккы бөлүктүү.',
'category-article-count' => '{{PLURAL:$2|Бо бөлүк чүгле дараазында арыннарлыг.|Бо бөлүктүң шупту $2 арыннарының аразындан дараазында $1 арынын көргүскен.}}',
'category-file-count' => '{{PLURAL:$2|Бо бөлүк чүгле чаңгыс файлдыг.|Бо бөлүктүң ниити $2 файлының $1 файлын көргүскен.}}',
'listingcontinuesabbrev' => '(уланчы)',
'noindex-category' => 'Индекстелбес арынар',
'broken-file-category' => 'Ажылдавайн турар файл-шөлүлгелиг арыннар',

'about' => 'Дугайында',
'article' => 'Допчу арын',
'newwindow' => '(чаа көзенээ ажыытынар)',
'cancel' => 'Соксаары',
'moredotdotdot' => 'Артык...',
'mypage' => 'Арын',
'mytalk' => 'Чугаа',
'anontalk' => 'Бо ИП-адрестиң чугаазы',
'navigation' => 'Навигация',
'and' => '&#32;болгаш',

# Cologne Blue skin
'qbfind' => 'Дилээри',
'qbbrowse' => 'Каралаары',
'qbedit' => 'Өскертири',
'qbpageoptions' => 'Бо арын',
'qbpageinfo' => 'Арын дугайында медээ',
'qbmyoptions' => 'Мээң арыннарым',
'qbspecialpages' => 'Тускай арыннар',
'faq' => 'Бо-ла салыр айтырыглар (БлСА)',
'faqpage' => 'Project:БлСА',

# Vector skin
'vector-action-addsection' => 'Кол сөстү немелээри',
'vector-action-delete' => 'Ырадыры',
'vector-action-move' => 'Шимчээри',
'vector-action-protect' => 'Камгалаары',
'vector-action-undelete' => 'Эгидер',
'vector-action-unprotect' => 'Камгалалды өскертири',
'vector-view-create' => 'Чаяары',
'vector-view-edit' => 'Эдери',
'vector-view-history' => 'Төөгүнү көөрү',
'vector-view-view' => 'Номчуур',
'vector-view-viewsource' => 'Дөзү бижиин көөрү',
'actions' => 'Кылыглар',
'namespaces' => 'Аттар делгемнери',
'variants' => 'Бир янзы',

'errorpagetitle' => 'Алдаг',
'returnto' => '«$1» деп арынже эглири.',
'tagline' => '{{SITENAME}} деп веб-сайттан',
'help' => 'Дуза',
'search' => 'Диле',
'searchbutton' => 'Дилээр',
'go' => 'Баары',
'searcharticle' => 'Күүcедири',
'history' => 'Арынның төөгүзү',
'history_short' => 'Төөгү',
'printableversion' => 'Саазынга үндүрерин көөрү',
'permalink' => 'Турум холбаа',
'print' => 'Саазынга үндүрер',
'view' => 'Көөрү',
'edit' => 'Эдери',
'create' => 'Чогаадыры',
'editthispage' => 'Бо арынны өскертири',
'create-this-page' => 'Бо арынны чогаадыры',
'delete' => 'Ыраары',
'deletethispage' => 'Бо арынны ырадыры',
'undelete_short' => '$1 {{PLURAL:$1|эдигни|эдиглерни}} катап үндүрери',
'viewdeleted_short' => '{{PLURAL:$1|Бир ыраткан өскерлиишкинни|$1 ыраткан өскерлиишкиннерни}} көөрү',
'protect' => 'Камгалаары',
'protect_change' => 'өскертири',
'protectthispage' => 'Бо арынны камгалаар',
'unprotect' => 'Камгалалды өскертири',
'unprotectthispage' => 'Бо арынның камгалалын өскертири',
'newpage' => 'Чаа арын',
'talkpage' => 'Бо арын дугайында чугаалажыры',
'talkpagelinktext' => 'Чугаа',
'specialpage' => 'Тускай арын',
'personaltools' => 'Хууда херекселдер',
'postcomment' => 'Чаа салбыр',
'articlepage' => 'Допчу арынны көөрү',
'talk' => 'Чугаа',
'views' => 'Көрүүшкүннери',
'toolbox' => 'Херекселдер',
'userpage' => 'Ажыглакчының арынын көөрү',
'projectpage' => 'Төлевилелдиң арынын көөрү',
'imagepage' => 'Файлдың арынын көөрү',
'mediawikipage' => 'Чагаа арынын көөрү',
'templatepage' => 'Майык арынын көөрү',
'viewhelppage' => 'Дуза арынын көөрү',
'categorypage' => 'Бөлүктүң арынын көөрү',
'viewtalkpage' => 'Чугааны көөрү',
'otherlanguages' => 'Өске дылдарга',
'redirectedfrom' => '($1 шигленген)',
'redirectpagesub' => 'шигледир арын',
'lastmodifiedat' => 'Бо арын сөөлгү катап $1, $2 өскерилген.',
'protectedpage' => 'Камгалаган арын',
'jumpto' => 'Шилчиир:',
'jumptonavigation' => 'навигация',
'jumptosearch' => 'дилээри',
'pool-errorunknown' => 'Билбес алдаг',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{SITENAME}} дугайында',
'aboutpage' => 'Project:Дугайында',
'copyrightpage' => '{{ns:project}}:Чогаалчының эргелери',
'currentevents' => 'Амгы үеде болуушкуннар',
'currentevents-url' => 'Project:Амгы үеде болуушкуннар',
'disclaimers' => 'Ажыглаар харысаалгазын чөрчүүрү (ойталаары)',
'disclaimerpage' => 'Project:Ажыглаар харысаалгазын ойталаары',
'edithelp' => 'Эдеринге дуза',
'edithelppage' => 'Help:Эдери',
'helppage' => 'Help:Допчузу',
'mainpage' => 'Кол Арын',
'mainpage-description' => 'Кол Арын',
'policy-url' => 'Project:Чурум',
'portal' => 'Ниитилелдиң хаалгазы',
'portal-url' => 'Project:Ниитилелдиң хаалгазы',
'privacy' => 'Актыг бүзүрел дугуржулгазы',
'privacypage' => 'Project:Актыг бүзүрел дугуржулгазы',

'badaccess' => 'Алдаг:Эргеңер чок.',

'versionrequired' => 'МедиаВикиниң $1 үндүреризи херек',

'ok' => 'Чөп',
'retrievedfrom' => '«$1» деп адрестен парлаттынган',
'youhavenewmessages' => 'Силерде $1 ($2) бар.',
'newmessageslink' => 'чаа чагаалар',
'newmessagesdifflink' => 'эрткен өскерлиишкин',
'youhavenewmessagesmulti' => '«$1» деп арында силерге чаа чагаалар бар.',
'editsection' => 'эдер',
'editold' => 'эдер',
'viewsourceold' => 'дөзү кодун көөрү',
'editlink' => 'эдер',
'viewsourcelink' => 'дөзү кодун көөрү',
'editsectionhint' => '«$1» деп салбырны эдер',
'toc' => 'Допчузу',
'showtoc' => 'көргүзери',
'hidetoc' => 'чажырары',
'collapsible-collapse' => 'Кызырар',
'collapsible-expand' => 'чазар',
'viewdeleted' => '{{grammar:accusative|$1}} көөр?',
'restorelink' => '{{PLURAL:$1|$1 балаттынган өскерилгелер}}',
'feedlinks' => 'Агым:',
'feed-invalid' => 'Бижидилгениң агым хевири багай-дыр.',
'site-rss-feed' => '$1 RSS Медээ Агымы',
'site-atom-feed' => '$1 Atom Медээ Агымы',
'page-rss-feed' => '«$1» RSS Медээ Агымы',
'page-atom-feed' => '«$1» Atom Медээ Агымы',
'red-link-title' => '$1 (арны чок)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Арын',
'nstab-user' => 'Ажыглакчының арыны',
'nstab-media' => 'Медиа арыны',
'nstab-special' => 'Тускай арын',
'nstab-project' => 'Төлевилелдиң арыны',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'Чагаа',
'nstab-template' => 'Майык',
'nstab-help' => 'Дуза',
'nstab-category' => 'Бөлүк',

# Main script and global functions
'nosuchaction' => 'Ындыг кылыг чок',
'nosuchspecialpage' => 'Ындыг тускай арын чок',

# General errors
'error' => 'Алдаг',
'databaseerror' => 'Медээ шыгжамыры алдаг',
'laggedslavemode' => "'''Оваарымчалыг:''' Бо арында чаартыышкыннар чок болуп болур.",
'readonly' => 'шоочалаарынга медээ шыгжамыры',
'missing-article' => 'Данныйлар базазында тывар ужурлуг «$1» $2 деп арынның негеттинип турар сөзүглели тывылбаан.

Нургулайында ындыг байдал эрги шөлүлге-биле казыттынган арынның өскерилге төөгүзүнче дамчып оралдажырга тыптыр.

А шынында ындыг эвес болза, Силер программа хандырылгазының алдаанга душканыңар хөңнү.

Ооң дугайында кайы-бир [[Special:ListUsers/sysop|удуртукчуларга]], мүн URL-ин айытпышаан, дамчыдыңарам.',
'missingarticle-rev' => '(үндүрериниң саны: $1)',
'missingarticle-diff' => '(Ылгал: $1, $2)',
'internalerror' => 'Иштики алдаг',
'internalerror_info' => 'Иштики алдаг: $1',
'badtitle' => 'Багай ат',
'badtitletext' => 'Негеттинип турар арын ады меге, куруг, чок болза дылдар аразында азы интервики ады шын эвес.
Адында таарышпас демдектер бары чадапчок.',
'viewsource' => 'Дөзүн көөрү',
'actionthrottled' => 'Шеглээн дүрген',
'sqlhidden' => '(SQL айтырыгны чажырган)',
'exception-nologin' => 'Кирбес',

# Login and logout pages
'welcomecreation' => '== Кирип моорлаңар, $1! ==
Силер бүрүткел бижик has been created.
Do not forget to change your [[Special:Preferences|{{SITENAME}} preferences]].',
'yourname' => 'Aжыглакчының ады',
'yourpassword' => 'Чажыт сөс',
'yourpasswordagain' => 'Чажыт сөзүңерни катап бижиңер:',
'remembermypassword' => 'Мени бо компьютерде сактып алыры ($1 {{PLURAL:$1|хүн|хүн}}ге чедир)',
'login' => 'Кирери',
'nav-login-createaccount' => 'Кирери / бүрүткел бижикти чогаадыры',
'loginprompt' => '{{SITENAME}} сайтче кирерде, баштай «cookies»-ти чөшпээрээр ужурлуг Силер.',
'userlogin' => 'Кирери / бүрүткел бижикти чогаадыры',
'userloginnocreate' => 'Кирери',
'logout' => 'Үнери',
'userlogout' => 'Үнери',
'notloggedin' => 'Кирбес',
'nologin' => 'Силерде бүрүткел бижик чок? $1',
'nologinlink' => 'Бүрүткел бижикти бүдүрери',
'createaccount' => 'Бүрүткел бижикти бүдүрери',
'gotaccount' => "Силер бүрүтекнип алдыңар де? '''$1'''.",
'gotaccountlink' => 'Кирер',
'userlogin-resetlink' => 'Кирер бижик-саныңар уттуп алдыңар бе?',
'createaccountmail' => 'Э-чагаадан',
'createaccountreason' => 'Чылдагаан:',
'badretype' => 'Силерниң парлаан чажыт сөзүңер таарышпас.',
'userexists' => 'Силерниң парлаан адыңар амгы үеде ажыглаттынып турар.
Өске аттан шилип алыңар.',
'loginerror' => 'Багай кирери',
'loginsuccesstitle' => 'Чедимчелиг кирери',
'login-userblocked' => 'Бо ажыглакчы blocked.  Кирери хоржок.',
'password-login-forbidden' => 'Бо ажыглакчының ады болгаш чажыт сөс хоржок.',
'mailmypassword' => 'Меңээ чаа чажыт сөсту чорудаары',
'accountcreated' => 'Бүрүткел бижикти бүдүрген',
'accountcreatedtext' => '«$1» деп ажыглакчының бүрүткел бижиини бүдүрген.',
'login-abort-generic' => 'Системаже таптыг эвес кирип тур силер',
'loginlanguagelabel' => 'Дыл: $1',

# E-mail sending
'php-mail-error-unknown' => 'PHP-ниң mail() ажыл-чорудулгазында билбес алдаг бар.',

# Change password dialog
'resetpass' => 'Чажыт сөстү өскертири',
'resetpass_text' => '<!-- Маңаа сөзүглелди немерелээри -->',
'resetpass_header' => 'Чажыт сөстү катап чогаадып кылыры',
'oldpassword' => 'Эгри чажыт сөзүңер:',
'newpassword' => 'Чаа чажыт сөзүңер:',
'retypenew' => 'Чажыт сөзүңерни катап бижиңер:',
'resetpass_submit' => 'Чажыт сөстү чоогадып кылыр база кирер.',
'resetpass_forbidden' => 'Чажыт сөстү өскертивейн болбас',
'resetpass-submit-loggedin' => 'Чажыт сөстү өскертири',
'resetpass-submit-cancel' => 'Соксаары',
'resetpass-temp-password' => 'Түр чажыт сөс:',

# Special:PasswordReset
'passwordreset' => 'Чажыт сөстү дүжүрү',
'passwordreset-legend' => 'Чажыт атты дүжүр',
'passwordreset-username' => 'Aжыглакчының ады:',
'passwordreset-domain' => 'Домен:',
'passwordreset-email' => 'Э-чагааның адреси:',
'passwordreset-emailelement' => 'Ажыглакчы ады: $1
Түр чажыт сөс: $2',

# Special:ChangeEmail
'changeemail' => 'Э-чагааның адресин өскертири',
'changeemail-header' => 'Бүрүткел бижиктиң э-чагааның адресин өскертири',
'changeemail-oldemail' => 'Амгы э-чагааның адреси:',
'changeemail-newemail' => 'Чаа э-чагааның адреси:',
'changeemail-none' => '(чок)',
'changeemail-submit' => 'Э-чагааны өскертири',
'changeemail-cancel' => 'Соксаары',

# Edit page toolbar
'bold_sample' => 'Кара сөзүглел',
'bold_tip' => 'Кара сөзүглел',
'italic_sample' => 'Ийлендирер сөзүглел',
'italic_tip' => 'Курсив бижик',
'link_sample' => 'Холбааның ады',
'link_tip' => 'Иштики холбаа',
'extlink_sample' => 'http://www.example.com холбааның ады',
'extlink_tip' => 'Даштыкы холбаа ("http://" чүве сактып алыр)',
'headline_sample' => 'Кырыкы сөзүглели',
'headline_tip' => '2-ги деңнелдиг кырыкы ады',
'nowiki_sample' => 'Форматтаваан сөзүглелини бээр салыры',
'nowiki_tip' => 'Вики форматтаарын херекке албас',
'image_sample' => 'Чижек.jpg',
'image_tip' => 'Киир туткан файл',
'media_sample' => 'Чижек.ogg',
'media_tip' => 'Файлдың холбаазы',
'sig_tip' => 'Шак-биле хол үжүңер',
'hr_tip' => 'Доора шугум (көвей ажыглаваңар)',

# Edit pages
'summary' => 'Түңнел:',
'subject' => 'Кол сөс:',
'minoredit' => 'Бо эдилге бичии-дир',
'watchthis' => 'Бо арынны хайгаараары',
'savearticle' => 'Арынны шыгжаары',
'preview' => 'Чижеглей көөрү',
'showpreview' => 'Чижеглей көөрү',
'showdiff' => 'Өскерлиишкиннерни көргүзери',
'anoneditwarning' => "'''Кичээңгейлиг!''' Силер сайтче авторжуттунмаан силер.
Бо арынның өскертилге төөгүзүнче Силерниң IP-адрезиңер бижитинип каар.",
'missingcommenttext' => 'Тайылбырни адаанда чогаадыңар.',
'summary-preview' => 'Түңнелдү чижеглей көөрү:',
'subject-preview' => 'Кол сөс чижеглей көөрү:',
'blockednoreason' => 'чылдагаан чок',
'nosuchsectiontitle' => 'Бо салбыр чок',
'loginreqlink' => 'кирери',
'accmailtitle' => 'Чажыт сөс чоргустунган.',
'accmailtext' => "A randomly generated password for [[User talk:$1|$1]] has been sent to $2.

The password for this new account can be changed on the ''[[Special:ChangePassword|change password]]'' page upon logging in.",
'newarticle' => '(Чаа)',
'userpage-userdoesnotexist' => '«<nowiki>$1</nowiki>» деп ажыглакчы is not registered.
Please check if you want to create/edit this page.',
'userpage-userdoesnotexist-view' => '«$1» деп ажыглакчы not registered.',
'note' => "'''Тайылбыр:'''",
'previewnote' => "'''Бо чүгле шенеп көөрү-дүр.'''
 Бижик ам-даа шыгжатынмаан!",
'editing' => '«$1» деп арынны эдери',
'editingsection' => '«$1» деп арынның салбырын эдери',
'editingcomment' => '«$1» деп арынны өскертип турар (чаа салбыр)',
'yourtext' => 'Силерниң сөзүглелиңер',
'yourdiff' => 'Ылгалдар',
'templatesused' => 'Бо арында {{PLURAL:$1|Майык|Майыктар}} ажыглаттырган:',
'template-protected' => '(камгалаан)',
'template-semiprotected' => '(четпес камгалаан)',
'hiddencategories' => 'Бо арын {{PLURAL:$1|$1 чажыт бөлүкке}} хамааржыр:',
'permissionserrorstext-withaction' => "Мында «'''$2'''» силерниң эргеңер чок, {{PLURAL:$1|чылдагааны|чылдагааннары}}:",
'moveddeleted-notice' => 'Бо арын ап каавыткан.
Адаанда ап каавыткан биле өскээр адаан бижиктер шынзылгазын көргүскен.',

# Parser/template warnings
'post-expand-template-inclusion-warning' => 'Сагындырыг: Кошкан майыктарның ниити хемчээли дендии улуг.
Чамдык майыктар коштунмаан боор.',
'post-expand-template-inclusion-category' => 'Кожар майыктарга чөшпээрээн хемчээлин ашкан арыннар',
'post-expand-template-argument-warning' => "'''Кичээнгейлиг:''' бо арында тоң дора дээрге (по крайней мере) чаңгыс майыктыг, а ооң аргументизи эмин эрттир улуг калбаяр хемчээлдиг.
Ындыг чергелиг аргументилерни эрттирип каан.",
'post-expand-template-argument-category' => "Аргументилери салдынмаан майыктарлыг '''арыннар'''",

# History pages
'viewpagelogs' => 'Бо арынның журналын көргүзери',
'nohistory' => 'Бо арынның өскерлиишкин төөгүзү чок.',
'currentrev' => 'Амгы үе үндүрери',
'currentrev-asof' => 'Амгы $1 үениң бижээни',
'revisionasof' => '$1 версиязы',
'revision-info' => '$2 киржикчиниң $1 хүнүнде киирилдези',
'previousrevision' => '←Артык эрги үндүрери',
'nextrevision' => 'Артык чаа үндүрери→',
'currentrevisionlink' => 'Амгы үе үндүрери',
'cur' => 'амгы',
'next' => 'дараазында',
'last' => 'эрткен',
'page_first' => 'бирги',
'page_last' => 'сөөлгү',
'history-fieldset-title' => 'Каралаары төөгүзү',
'history-show-deleted' => 'Чүгле казыттынган',
'histfirst' => 'Эң эрте',
'histlast' => 'Эң дээм чаагы',
'historysize' => '({{PLURAL:$1|$1 байт}})',
'historyempty' => '(куруг)',

# Revision feed
'history-feed-title' => 'Үндүрериниң төөгүзү',
'history-feed-item-nocomment' => '$1 {{grammar:genitive|$2}} иштинде',

# Revision deletion
'rev-deleted-comment' => '(өскерлиишкинниң түңнелин ап каан)',
'rev-deleted-user' => '(ажыглакчының адын ап каан)',
'rev-deleted-event' => '(журналдың кылыын ап каан)',
'rev-delundel' => 'көргүзери/чажырары',
'rev-showdeleted' => 'көргүзери',
'revisiondelete' => 'Үндүрерилерни ырадыры/диргисири',
'revdelete-show-file-submit' => 'Ийе',
'revdelete-hide-comment' => 'Өскерлиишкинниң комментарийн чажырар',
'revdelete-hide-user' => 'Чогаалчының адын/ИП-адресин чажырар',
'revdelete-radio-set' => 'Ийе',
'revdelete-radio-unset' => 'Чок',
'revdelete-log' => 'Чылдагаан:',
'revdel-restore' => 'көскүзүн өскертири',
'revdel-restore-deleted' => 'ыраткан үндүрерилер',
'revdel-restore-visible' => 'көскү үндүрерилер',
'pagehist' => 'Арынның төөгүзү',
'revdelete-otherreason' => 'Өске/немелде чылдагаан:',
'revdelete-reasonotherlist' => 'Өске чылдагаан',

# History merging
'mergehistory-reason' => 'Чылдагаан:',

# Merge log
'revertmerge' => 'Чарары',

# Diffs
'history-title' => '«$1» деп арынның эдилге төөгүзү',
'lineno' => 'Одуруг $1:',
'compareselectedversions' => 'Шилип алган хевирлери деңнээри',
'editundo' => 'чөрчүүрү',
'diff-multi' => '({{PLURAL:$2|$2 киржикчиниң}} {{PLURAL:$1|$1 түр хевирин көргүспээн}})',

# Search results
'searchresults' => 'Түңнелдер',
'searchresults-title' => '«$1» деп диле',
'prevn' => 'эрткен {{PLURAL:$1|$1}}',
'nextn' => 'дараазында {{PLURAL:$1|$1}}',
'prevn-title' => 'Эрткен $1 {{PLURAL:$1|бижик|бижик}}',
'nextn-title' => 'Дараазында $1 {{PLURAL:$1|бижик|бижик}}',
'shown-title' => 'Арынга $1 {{PLURAL:$1|түңнелди|түңнелди}} көргүзери',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3) көөрү',
'searchmenu-exists' => "'''Бо викиде \"[[:\$1]]\" деп арын бар.'''",
'searchmenu-new' => "'''Бо викиде «[[:$1]]» арынны чогаадыры'''",
'searchhelp-url' => 'Help:Допчузу',
'searchprofile-articles' => 'үндезин арыннар',
'searchprofile-project' => 'Төлевилел биле дуза арыннары',
'searchprofile-images' => 'Мультимедиа',
'searchprofile-everything' => 'Бүгүде',
'searchprofile-advanced' => 'Калбайтыр',
'searchprofile-articles-tooltip' => '$1 иштинден дилээри',
'searchprofile-project-tooltip' => '$1 иштинде дилээри',
'searchprofile-images-tooltip' => 'Файлдар дилээри',
'searchprofile-everything-tooltip' => 'Шупту арыннардан дилээри (сумележиишкиннерден база)',
'searchprofile-advanced-tooltip' => 'Айыткан аттар делгемнеринден дилээри',
'search-result-size' => '$1 ({{PLURAL:$2|$2 сөс}})',
'search-result-category-size' => '{{PLURAL:$1|1 кежигүн|$1 кежигүн}} ({{PLURAL:$2|1 aдаккы бөлүк|$2 aдаккы бөлүк}}, {{PLURAL:$3|1 файл|$3 файл}})',
'search-redirect' => '($1-н шиглелге)',
'search-section' => '(«$1» деп салбыр)',
'search-suggest' => 'Силер «$1» деп бодадыңар чадавас',
'search-interwiki-more' => '(артык)',
'searcheverything-enable' => 'Шупту аттар делгемнеринден дилээри',
'searchrelated' => 'холбаалыг',
'searchall' => 'шупту',
'showingresultsheader' => "«'''$4'''» дилээниниң {{PLURAL:$5|'''$3''' одуругдан '''$1''' түңнели|'''$3''' одуругдан '''$1—$2''' түңнелдери}}",
'search-nonefound' => 'Айыткан негелдениң түңнели чок',
'powersearch' => 'Advanced search',
'powersearch-ns' => 'Аттар делгемнеринден дилээри:',
'powersearch-toggleall' => 'Шупту',

# Preferences page
'preferences' => 'Шилилгелер',
'mypreferences' => 'Шилилгелер',
'prefs-edits' => 'Өскерлиишкиннериңерниң саны:',
'changepassword' => 'Чажыт сөстү өскертири',
'prefs-skin' => 'Кеш',
'skin-preview' => 'Чижеглей көөрү',
'prefs-datetime' => 'Ай, хүн болгаш шак',
'prefs-personal' => 'Ажыглакчының медээлери',
'prefs-rc' => 'Дээм чаагы өскерлиишкиннер',
'prefs-watchlist' => 'Хайгаарал даңзы',
'prefs-watchlist-edits' => 'Өскерлиишкиннерниң эң улуг саны expanded хайгаарал даңзында көргүзери:',
'prefs-watchlist-edits-max' => 'Эң улуг сан: 1000',
'prefs-resetpass' => 'Чажыт сөстү өскертири',
'prefs-email' => 'Э-чагаа эдиглери',
'prefs-rendering' => 'Түрү',
'saveprefs' => 'Шыгжаары',
'restoreprefs' => 'Шупту баштайгы ниити шилилгелерни restore',
'prefs-editing' => 'Өскертир',
'prefs-edit-boxsize' => 'Өскертир көзенектиң хемчээли.',
'rows' => 'Одуруглар:',
'columns' => 'Баганалар:',
'searchresultshead' => 'Дилээр',
'timezoneuseserverdefault' => 'Викиниң ниити шилилгезин ажыглаары ($1)',
'servertime' => 'Серверниң шагы:',
'timezoneregion-africa' => 'Африка',
'timezoneregion-america' => 'Америка',
'timezoneregion-antarctica' => 'Антарктика',
'timezoneregion-arctic' => 'Арктика',
'timezoneregion-asia' => 'Азия',
'timezoneregion-australia' => 'Австралия',
'timezoneregion-europe' => 'Европа',
'prefs-namespaces' => 'Аттар делгемнери',
'default' => 'ниити',
'prefs-files' => 'файлдар',
'prefs-custom-css' => 'Бодуңар CSS',
'prefs-custom-js' => 'Бодуңар JavaScript',
'prefs-textboxsize' => 'Өскертир көзенектиң хемчээли',
'youremail' => 'Э-чагааңар:',
'username' => 'Aжыглакчының ады:',
'uid' => 'Ажыглакчынын саны (ID):',
'prefs-memberingroups' => 'Силерниң {{PLURAL:$1|бөлүү|бөлүктери}}:',
'prefs-registration' => 'Кажан даңзылатканыл:',
'yourrealname' => 'Шын адыңар:',
'yourlanguage' => 'Дылыңар:',
'yournick' => 'Шола ат:',
'badsiglength' => 'Хол үжүүңер эмин узун.
It must not be more than $1 {{PLURAL:$1|character|characters}} long.',
'yourgender' => 'Эр-кызы:',
'gender-male' => 'Эр',
'gender-female' => 'Кыс',
'email' => 'Э-чагаа',
'prefs-help-email' => 'Э-шуудаң адрезин айтыры албан эвес, ынчалза-даа, уруңуңар (парольуңар) чиде бээрге, ол херек апаар.',
'prefs-help-email-others' => 'Ол харылзаа медээлели база өске киржикчилерге хуу азы чугаалажылга арныңарга э-шуудаңыңар (e-mail) таварыштыр Силерниң-биле харылзажырынга ажыктыг. Ооң кадында Силерниң э-шуудаң адрезиңер кымга-даа көзүлбес.',
'prefs-info' => 'Кол медээлер',
'prefs-signature' => 'Хол үжүү',
'prefs-diffs' => 'Ылгалдар',

# User rights
'editusergroup' => 'Ажыглакчының бөлгүмнерни өскертири',
'editinguser' => "Changing user rights of user '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'Ажыглакчының бөлгүмнерни өскертири',
'saveusergroups' => 'Ажыглакчының бөлгүмнерни шыгжаары',
'userrights-reason' => 'Чылдагаан:',

# Groups
'group' => 'Бөлгүм:',
'group-user' => 'Ажыглакчылар',
'group-bot' => 'Роботтар',
'group-sysop' => 'Эргелекчилер',
'group-bureaucrat' => 'Бюрократтар',
'group-suppress' => 'Хынакчылар',
'group-all' => '(шупту)',

'group-user-member' => 'ажыглакчы',
'group-autoconfirmed-member' => '{{GENDER:$1|automatic бадыткаттынар ажыглакчы}}',
'group-bot-member' => '{{GENDER:$1|робот}}',
'group-sysop-member' => '{{GENDER:$1|эргелекчи}}',
'group-bureaucrat-member' => '{{GENDER:$1|бюрократ}}',
'group-suppress-member' => '{{GENDER:$1|хынакчы}}',

'grouppage-user' => '{{ns:project}}:Ажыглакчылар',
'grouppage-autoconfirmed' => '{{ns:project}}:Автоматуг бадыткаттынар ажыглакчылар',
'grouppage-bot' => '{{ns:project}}:Роботтар',
'grouppage-sysop' => '{{ns:project}}:Эргелекчилер',
'grouppage-bureaucrat' => '{{ns:project}}:Бюрократтар',
'grouppage-suppress' => '{{ns:project}}:Хынакчы',

# Rights
'right-read' => 'Арыннарны номчууры',
'right-edit' => 'Арыннарны өскертири',
'right-createtalk' => 'Чугаалажырга арыннарны чогаадыры',
'right-createaccount' => 'Чаа бүрүткел бижиктерин бүдүрери',
'right-move' => 'Арыннарны шимчээри',
'right-movefile' => 'Файлдарны шимчээри',
'right-editusercssjs' => 'Өске ажыглакчыларның CSS база Javascript файлдарын өскертири.',
'right-editusercss' => 'Өске ажыглакчыларның CSS файлдарын өскертири.',
'right-edituserjs' => 'Өске ажыглакчыларның JavaScript файлдарын өскертири.',

# User rights log
'rightsnone' => '(чок)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'бо арынны номчууру',
'action-edit' => 'бо арынны эдери',
'action-createpage' => 'арыннарны чогаадыры',
'action-createtalk' => 'чугаалажырга арыннарны чогаадыры',
'action-createaccount' => 'бо бүрүткел бижиктерин бүдүрери',
'action-move' => 'бо арынны шимчээри',
'action-movefile' => 'бо файлды шимчээри',
'action-sendemail' => 'э-чагаалар чорудары',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|өскерлиишкин}}',
'recentchanges' => 'Амгы өскерлиишкиннер',
'recentchanges-legend' => 'Амгы өскерлиишкиннерниң эдиглери',
'recentchanges-summary' => 'Бо агымда викиниң сөөлгү өскерлиишкиннерин көөрү.',
'recentchanges-feed-description' => 'Бо агымда викиниң сөөлгү өскерлиишкиннерин көөрү.',
'recentchanges-label-newpage' => 'Бо өскерлиишкин чаа арынны чогааткан.',
'recentchanges-label-minor' => 'Бо өскерлиишкин бичии-дир',
'recentchanges-label-bot' => 'Бо эдилгени робот күүсеткен.',
'recentchanges-label-unpatrolled' => 'Бо өскертилге истетинмээн (патрульдаттынмаан)',
'rcnote' => "$4 $5 өйде соңгу '''$2''' {{PLURAL:$2|хонуктуң}} {{PLURAL:$1|сөөлгү '''$1''' '''өскерилгелери'''}} .",
'rcnotefrom' => 'Адаанда <strong>$2</strong> тура (<strong>$1</strong> чедир) өскертилгелерни санаан.',
'rclistfrom' => '$1 тура чаа өскерилгелерни көргүзер',
'rcshowhideminor' => 'Бичии өскерлиишкиннерни $1',
'rcshowhidebots' => 'Роботтарну $1',
'rcshowhideliu' => 'Кирер ажыглакчыларны $1',
'rcshowhideanons' => 'Ат эвес ажыглакчыларны $1',
'rcshowhidepatr' => 'истээн өскерлиишкиннерни $1',
'rcshowhidemine' => 'Мээң өскерлиишкинимни $1',
'rclinks' => '$2 хүнде эрткен $1 өскерлиишкиннерни көргүзери<br />$3',
'diff' => 'ылгал',
'hist' => 'төөгү',
'hide' => 'чажырары',
'show' => 'көргүзери',
'minoreditletter' => 'б',
'newpageletter' => 'Ч',
'boteditletter' => 'р',
'number_of_watching_users_pageview' => '[$1 хайгаараар {{PLURAL:$1|ажыглакчы}}]',
'newsectionsummary' => '/* $1 */ чаа салбыр',
'rc-enhanced-expand' => 'Тодаларны көргүзери (JavaScript херек)',
'rc-enhanced-hide' => 'Тодаларны чажырары',

# Recent changes linked
'recentchangeslinked' => 'Хамааржыр өскерлиишкиннер',
'recentchangeslinked-toolbox' => 'Хамааржыр өскерлиишкиннер',
'recentchangeslinked-title' => '«$1» деп арынга хамаарыштырган өскерлиишкиннер',
'recentchangeslinked-noresult' => 'Холбаштырган арыннарда айыткан үе иштинде кандыг-даа өскертилге турбаан.',
'recentchangeslinked-summary' => "Айыткан арынның (азы айыткан бөлүкке хамаарышкан) шөлүлүглериниң чедер арыннарнының чаа өскерилгер даңзызы.
[[Special:Watchlist|Силерниң хайгаарал даңзызынче]] кирип турар арыннарны '''ылгап каан'''.",
'recentchangeslinked-page' => 'Арынның ады:',
'recentchangeslinked-to' => 'Айыткан арынче шөлүп турар арыннарга өскерилгелерни көргүзер',

# Upload
'upload' => 'Файл чүдүрер',
'uploadbtn' => 'Файлды салыры',
'uploadnologin' => 'Кирбес',
'uploaderror' => 'Кииреринге алдаг',
'uploadlogpage' => 'Кожулгалар сеткүүлү',
'filename' => 'файлдың ады:',
'filedesc' => 'Түңнел',
'fileuploadsummary' => 'Түңнел:',
'filesource' => 'Эгези:',
'savefile' => 'Файлды шыгжаары',
'uploadedimage' => '«[[$1]]» деп файлды салган.',
'upload-maxfilesize' => 'Файлдың эң улуг хемчээли: $1',
'watchthisupload' => 'Бо арынны хайгаараары',

'upload-file-error' => 'Иштики алдаг',
'upload-misc-error' => 'Билбес кииреринге алдаг',
'upload-unknown-size' => 'Билбас хемчээл',

# HTTP errors
'http-read-error' => 'HTTP-биле номчуур алдаг.',

'license' => 'Хоойлужудары:',
'license-header' => 'Хоойлужудары',

# Special:ListFiles
'imgfile' => 'файл',
'listfiles' => 'Файл даңзызы',
'listfiles_name' => 'Ат',
'listfiles_user' => 'Ажыглакчы',
'listfiles_size' => 'Хемчээл',
'listfiles_description' => 'Тодарадып бижээни',
'listfiles_count' => 'Үндүрерилер',

# File description page
'file-anchor-link' => 'Файл',
'filehist' => 'Файлдың төөгүзү',
'filehist-help' => 'Ол үеде файлдың көстүрүн көөрде, дата/үеже базыптыңар.',
'filehist-deleteall' => 'шуптуну ырадыры',
'filehist-deleteone' => 'ырадыры',
'filehist-revert' => 'эгидип тургузары',
'filehist-current' => 'амгы',
'filehist-datetime' => 'Ай, Хүн/Шак',
'filehist-thumb' => 'Бичии чурумал',
'filehist-thumbtext' => '$1 хамааржыр хевириниң биче чурумалы (миниатюразы)',
'filehist-user' => 'Ажыглакчы',
'filehist-dimensions' => 'Хемчээлдери',
'filehist-filesize' => 'Файл хемчээли',
'filehist-comment' => 'Тайылбыр',
'imagelinks' => 'Файлдың ажыглаашкыны',
'linkstoimage' => 'Бердинген файлче дараазында {{PLURAL:$1|арын шөлүдүп тур|$1 арын шөлүдүп тур}}:',
'nolinkstoimage' => 'Бердинген файлче шөлүп турар арыннар чок.',
'sharedupload-desc-here' => 'Моон $1 алган файл өске төлевилелдерге ажыглаттынып болур.
Ооң [$2 допчу тайылбыр арынындан] медеглели адаанда бердинген.',

# File reversion
'filerevert' => '$1 эгидип тургузары',
'filerevert-legend' => 'Файлды эгидип тургузары',
'filerevert-comment' => 'Чылдагаан:',
'filerevert-submit' => 'Эгидип тургузары',

# File deletion
'filedelete' => '«$1» деп файлды ырадыры',
'filedelete-legend' => 'Файлды ырадыры',
'filedelete-comment' => 'Чылдагаан:',
'filedelete-submit' => 'Ырадыры',
'filedelete-otherreason' => 'Өске/немелде чылдагаан:',
'filedelete-reason-otherlist' => 'Өске чылдагаан',

# MIME search
'mimetype' => 'MIME хевири:',
'download' => 'алыры',

# Unwatched pages
'unwatchedpages' => 'Хайгаарабас арыннар',

# Unused templates
'unusedtemplates' => 'Ажыглаан эвес майыктар',
'unusedtemplateswlh' => 'өске холбаалар',

# Random page
'randompage' => 'Душ арын',

# Statistics
'statistics' => 'Статистика',
'statistics-pages' => 'Арыннар',

'disambiguationspage' => 'Майык: уш-бажы билдинмес',

'brokenredirects-edit' => 'өскертири',
'brokenredirects-delete' => 'ырадыры',

'withoutinterwiki' => 'Дыл холбаалар эвес арыннар',
'withoutinterwiki-submit' => 'Көргүзери',

'fewestrevisions' => 'Эң эвээш үндүрери арыннар',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|байт}}',
'ncategories' => '$1 {{PLURAL:$1|бөлүк}}',
'nlinks' => '$1 {{PLURAL:$1|холбаа}}',
'nmembers' => '$1 {{PLURAL:$1|кежигүн}}',
'nrevisions' => '$1 {{PLURAL:$1|үндүрери}}',
'nviews' => '$1 {{PLURAL:$1|көрүш}}',
'specialpage-empty' => 'Бо илеткелдиң түңнели чок.',
'lonelypages' => 'Чааскаан арыннар',
'uncategorizedpages' => 'Бөлүк эвес арыннар',
'uncategorizedcategories' => 'Бөлүк эвес бөлүктер',
'uncategorizedimages' => 'Бөлүк эвес файлдар',
'uncategorizedtemplates' => 'Бөлүк эвес майыктар',
'unusedcategories' => 'Ажыглаваан бөлүктер',
'unusedimages' => 'Ажыглаваан файлдар',
'popularpages' => 'Чоннуң арыннар',
'wantedcategories' => 'Күзээринге бөлүктер',
'wantedpages' => 'Күзээрүнге арыннар',
'mostlinked' => 'Эң холбаалар арыннар',
'mostlinkedcategories' => 'Эң холбаалар бөлүктер',
'mostlinkedtemplates' => 'Эң холбаалар майыктар',
'mostcategories' => 'Эңне бөлүктер арыннар',
'mostimages' => 'Эң холбаалар файлдар',
'prefixindex' => 'Арыннарның эгезиниң аайы-биле айтыкчы',
'shortpages' => 'Чолдак арыннар',
'longpages' => 'Узун арыннар',
'protectedpages' => 'Камгалаган арыннар',
'listusers' => 'Ажыглакчылар даңзызы',
'usereditcount' => '$1 {{PLURAL:$1|эдилге}}',
'usercreated' => '$1 хүнде $2 {{GENDER:$3|бүрүткенип алган}}',
'newpages' => 'Чаа арыннар',
'newpages-username' => 'Ажыглакчының ады:',
'ancientpages' => 'Эң эрги арыннар',
'move' => 'Шимчээри',
'movethispage' => 'Бо арынны шимчээри',
'pager-newer-n' => '{{PLURAL:$1|артык чаа}}',
'pager-older-n' => '{{PLURAL:$1|артык эски}}',

# Book sources
'booksources' => 'Номнарның үнген дөзү',
'booksources-search-legend' => 'Номнуң медээлерин дилээри',
'booksources-go' => 'Күүcедири',

# Special:Log
'specialloguserlabel' => 'Күүседикчи:',
'speciallogtitlelabel' => 'Target (aтка азы ажыглакчыга):',
'log' => 'Журналдар',

# Special:AllPages
'allpages' => 'Шупту арыннар',
'alphaindexline' => '«$1» деп арындан «$2» деп арында',
'nextpage' => 'Дараазында арын ($1)',
'prevpage' => 'Эрткен арын ($1)',
'allarticles' => 'Шупту арыннар',
'allpagesprev' => 'Пертинде',
'allpagesnext' => 'Дараазында',
'allpagessubmit' => 'Күүcедири',

# Special:Categories
'categories' => 'Бөлүктер',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'салыышкыннар',

# Special:LinkSearch
'linksearch' => 'Даштыкы холбааларга дилээри',
'linksearch-ns' => 'Аттар делгеми:',
'linksearch-ok' => 'Дилээри',
'linksearch-line' => '$1-же $2-ден шөлүлге',

# Special:ListUsers
'listusers-submit' => 'Көргүзери',

# Special:ActiveUsers
'activeusers-hidebots' => 'Роботтарны чажырары',
'activeusers-hidesysops' => 'Эргелекчыларны чажырары',

# Special:Log/newusers
'newuserlogpage' => 'Чаа ажыглакчы кырында журнал',

# Special:ListGroupRights
'listgrouprights-group' => 'Бөлүк кижилер',
'listgrouprights-members' => '(кежигүннүң даңзызы)',

# E-mail user
'emailuser' => 'Бо ажыглакчыга э-чагааны чорудаары',
'defemailsubject' => '{{grammar:ablative|{{SITENAME}}}} э-чагаа',
'emailusernamesubmit' => 'Күүcедири',
'emailfrom' => 'Кымдан:',
'emailto' => 'Кымга:',
'emailsubject' => 'Кол сөс:',
'emailmessage' => 'Чагаа:',
'emailsend' => 'Чорудары',

# Watchlist
'watchlist' => 'Мээң хайгаарал даңзым',
'mywatchlist' => 'Хайгаарал даңзы',
'watchlistfor2' => '$1, силерге $2',
'nowatchlist' => 'Силерниң хайгаарал даңзыңар куруг.',
'watchnologin' => 'Кирбес',
'watch' => 'Хайгаараары',
'watchthispage' => 'Бо арынны хайгаараары',
'unwatch' => 'Хайгааравас',
'unwatchthispage' => 'Бо арынны хайгаарабас',
'watchlist-details' => 'Чугаалажылга арыннарын санаваска, хайгаарал даңзыңарда {{PLURAL:$1|$1 арын}} бар.',
'wlshowlast' => 'Сөөлү $1 шак болгаш $2 хүн иштинде $3 көргүзери',
'watchlist-options' => 'Хайгаарал даңзының эдиглери',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Хайгаарап турар...',
'unwatching' => 'Хайгааравайн турар...',

'enotif_newpagetext' => 'Бо чаа арын-дыр.',
'enotif_impersonal_salutation' => '{{grammar:genitive|{{SITENAME}}}} ажыглакчызы',
'changed' => 'өскертти',
'enotif_anon_editor' => 'ат эвес ажыглакчы $1',

# Delete
'deletepage' => 'Арынны ырадыры',
'exblank' => 'Арын куруг турган',
'delete-confirm' => '«$1» деп арынны ырадыры',
'delete-legend' => 'Ырадыры',
'actioncomplete' => 'Ажыл доосту',
'actionfailed' => 'Кылыг болдунмаан',
'dellogpage' => 'казыышкыннар',
'deletecomment' => 'Чылдагаан:',
'deleteotherreason' => 'Өске/немелде чылдагаан:',
'deletereasonotherlist' => 'Өске чылдагаан',

# Rollback
'rollbacklink' => 'эглир',

# Protect
'protectlogpage' => 'Камгалал кырында журнал',
'protectedarticle' => '«[[$1]]» деп арынны камгалаан',
'protectcomment' => 'Чылдагаан:',
'protect-default' => 'Allow all users',
'protect-level-sysop' => 'Чүгле эргелекчылар',
'protect-otherreason' => 'Өске/немелде чылдагаан:',
'protect-otherreason-op' => 'Өске чылдагаан',
'restriction-type' => 'Чөпшээрел:',
'minimum-size' => 'Эң биче хемчээл',
'pagesize' => '(байттар)',

# Restrictions (nouns)
'restriction-edit' => 'Өскертири',
'restriction-move' => 'Шимчээри',

# Undelete
'undeletebtn' => 'Диргисир',
'undeletelink' => 'көөрү/диргисири',
'undeleteviewlink' => 'көөрү',
'undeletecomment' => 'Чылдагаан:',
'undelete-search-submit' => 'Дилээр',
'undelete-show-file-submit' => 'Ийе',

# Namespace form on various pages
'namespace' => 'Аттар делгеми:',
'invert' => 'Шилээнин аңдарар. (Обратить выбранное)',
'blanknamespace' => '(Кол)',

# Contributions
'contributions' => 'Ажыглакчыниң салыышкыннары',
'contributions-title' => '«$1» деп ажыглакчының салыышкыннары',
'mycontris' => 'Салыышкыннар',
'contribsub2' => '$1 ($2)',
'uctop' => '(баш)',
'month' => 'Айдан:',
'year' => 'Чылдан:',

'sp-contributions-newbies' => 'Чүгле чаа кежигүннерниң салыышкыннарын көргүзери',
'sp-contributions-blocklog' => 'кызыгаарлаашкынның журналы',
'sp-contributions-uploads' => 'киирген чүүлдер',
'sp-contributions-logs' => 'журналдар',
'sp-contributions-talk' => 'чугаалажыры',
'sp-contributions-search' => 'Салыышкыннарын дилээри',
'sp-contributions-username' => 'ИП-адрес азы ажыглачының ады:',
'sp-contributions-toponly' => 'Чазалгаларның чүгле сөөлгү хевирлерин көргүзер',
'sp-contributions-submit' => 'Дилээри',

# What links here
'whatlinkshere' => 'Шөлүлгелерни бээр',
'whatlinkshere-title' => '«$1» деп арынга шөлүтген арыннар',
'whatlinkshere-page' => 'Арын:',
'linkshere' => "Адаандагы арыннар бээр «'''[[:$1]]'''» шөлүдүп турарлар:",
'nolinkshere' => "'''[[:$1]]''' деп арынче шөлүтткен арыннар чок.",
'isredirect' => 'шиглидер арын',
'istemplate' => 'киирткен арыннар',
'isimage' => 'файлдың холбаазы',
'whatlinkshere-prev' => '{{PLURAL:$1|эрткен|эрткен $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|дараазында|дараазында $1}}',
'whatlinkshere-links' => '← холбаалар',
'whatlinkshere-hideredirs' => '$1-че шиглиглер',
'whatlinkshere-hidetrans' => '$1 даңзылааннар',
'whatlinkshere-hidelinks' => 'холбааларны $1',
'whatlinkshere-hideimages' => 'Файлдың холбааларын $1',
'whatlinkshere-filters' => 'Шүүрлер',

# Block/unblock
'block' => 'Ажыглакчыны кызыгаарлаары',
'blockip' => 'Ажыглакчыны кызыгаарлаары',
'blockip-title' => 'Ажыглакчыны кызыгаарлаары',
'blockip-legend' => 'Ажыглакчыны кызыгаарлаары',
'ipadressorusername' => 'ИП-адрес азы aжыглaкчының aды',
'ipbreason' => 'Чылдагаан:',
'ipbreasonotherlist' => 'Өске чылдагаан',
'ipbsubmit' => 'Бо ажыглакчыны кызыгаарлаары',
'ipbother' => 'Өске шак:',
'ipboptions' => '2 шак:2 hours,1 хүн:1 day,3 хүн:3 days,1 чеди-хонук:1 week,2 чеди-хонук:2 weeks,1 ай:1 month,3 ай:3 months,6 ай:6 months,1 чыл:1 year,төнмес-батпас:infinite',
'ipbotheroption' => 'өске',
'ipbotherreason' => 'Өске/немелде чылдагаан:',
'badipaddress' => 'Багай ИП-адрес',
'ipblocklist' => 'Kызыгаарлаттынган ажыглакчылар',
'blocklist-reason' => 'Чылдагаан',
'ipblocklist-submit' => 'Дилээр',
'infiniteblock' => 'кезээ-мөңгеде',
'blocklink' => 'кызыгаарлаары',
'unblocklink' => 'ажыдып хостаар',
'change-blocklink' => 'кызыгаарлаашкынны өскертири',
'contribslink' => 'салыышкыннар',
'blocklogpage' => 'Кызыгаарлаашкынның журналы',
'blocklogentry' => ', [[$1]] $2 дургузунда кызыгаарлаттынган: $3',
'block-log-flags-anononly' => 'чүгле ат эвес ажыглакчылар',
'block-log-flags-nocreate' => 'Кижилер бүрүткээри хоруглуг',
'block-log-flags-hiddenname' => 'ажыглакчының ады чажырган',

# Developer tools
'lockdb' => 'Медээ шыгжамырын шоочалаар',
'unlockdb' => 'Медээ шыгжамырын ажыттынар',
'lockbtn' => 'Медээ шыгжамырын шоочалаар',
'unlockbtn' => 'Медээ шыгжамырын ажыттынар',

# Move page
'move-page' => '«$1» деп арынны шимчээри',
'move-page-legend' => 'Арынны шимчээр',
'movearticle' => 'Бо арынны шимчээри:',
'newtitle' => 'Чаа ат:',
'move-watch' => 'Бо арынны хайгаараары',
'movepagebtn' => 'Арынны шимчээри',
'movelogpage' => 'Шимчээринге журнал',
'movereason' => 'Чылдагаан:',
'revertmove' => 'эгидип тургузары',
'delete_and_move' => 'Ырадыры болгаш шимчээри',

# Export
'export' => 'Арынар үндүр дамчыдары',

# Namespace 8 related
'allmessages' => 'Системниң дыңнадыглары',
'allmessagesname' => 'Ат',
'allmessagesdefault' => 'Ниити сөзүглел',
'allmessagescurrent' => 'Амгы сөзүглел',
'allmessages-filter-all' => 'Шупту',
'allmessages-language' => 'Дыл:',
'allmessages-filter-submit' => 'Күүcедири',

# Thumbnails
'thumbnail-more' => 'Улгаттыр',
'thumbnail_error' => 'Биче чурумал (миниатюра) чаяарының алдаа: $1',

# Special:Import
'import-comment' => 'Тайылбыр:',

# Import log
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|үндүрери}}',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Силерниң ажыглакчы арыныңнар',
'tooltip-pt-mytalk' => 'Силерниң чугаалажыр арыныңар',
'tooltip-pt-preferences' => 'Силерниң шилилгеңер',
'tooltip-pt-watchlist' => 'Карактап (хынап) турар өскертилгелерге хамааржыр арыннарның даңзызы',
'tooltip-pt-mycontris' => 'Силерниң салыышкыннарыңарның даңзызы',
'tooltip-pt-login' => 'Маңаа системаже киир бүрүткенип болур, ынчалза-даа ол албан эвес.',
'tooltip-pt-logout' => 'Үнери',
'tooltip-ca-talk' => 'Бо арын дугайында чыгаалажыры',
'tooltip-ca-edit' => 'Силер бо арынны эдип болур. Шыгжаар мурунда чижеглеп көрем.',
'tooltip-ca-addsection' => 'Чаа салбыр чаяар',
'tooltip-ca-viewsource' => 'Бо арын камгалаттырган.
Дөзү кодун көрүп болур силер.',
'tooltip-ca-history' => 'Арынның мурнуку өскерлиишкиннери',
'tooltip-ca-protect' => 'Бо арынны камгалаары',
'tooltip-ca-delete' => 'Бо арынны ырадыры',
'tooltip-ca-move' => 'Бо арынны шимчээри',
'tooltip-ca-watch' => 'Силерниң хайгаарал даңзызынга бо арынны немерелээри',
'tooltip-ca-unwatch' => 'Силерниң хайгаарал даңзызындан бо арынны ырадыры',
'tooltip-search' => '{{grammar:locative|{{SITENAME}}}} дилээри',
'tooltip-search-go' => 'Шак ындыг аттыг арынче щилчиир',
'tooltip-search-fulltext' => 'Бо бижике арыннардан дилээри',
'tooltip-p-logo' => 'Кол Арын',
'tooltip-n-mainpage' => 'Кол Арынче шилчиир',
'tooltip-n-mainpage-description' => 'Кол Арынче кирери',
'tooltip-n-portal' => 'Төлевилел дыгайында, чүнү кылып болур силер, кайда чүү чыдарыл',
'tooltip-n-currentevents' => 'Ам болуп турар таварылгалар даңзызы',
'tooltip-n-recentchanges' => 'Викиниң энир өскерлиишкиннери',
'tooltip-n-randompage' => 'Душ арынны көөрү',
'tooltip-n-help' => 'Төлевилелдиң тайылбыры «{{SITENAME}}»',
'tooltip-t-whatlinkshere' => 'Бүгү маңаа шөлүтген вики арыннарның даңзызы',
'tooltip-t-recentchangeslinked' => 'Бо арындан шөлүткен өске арыннарның сөөлгү өскерлиишкиннери',
'tooltip-feed-rss' => 'Бо арының РСС медээ агымы',
'tooltip-feed-atom' => 'Бо арының Атом медээ агымы',
'tooltip-t-contributions' => 'Бо ажыглакчының салыышкыннарының даңзазын көөрү.',
'tooltip-t-emailuser' => 'Бо ажыглакчыга э-чагааны чорудаары',
'tooltip-t-upload' => 'Файлдарны киирери',
'tooltip-t-specialpages' => 'Шупту тускай арыннар даңзызы',
'tooltip-t-print' => 'Бо арынның парлаттынар хевири',
'tooltip-t-permalink' => 'Арынның бо янзы-хевириниң турум шөлүлгези',
'tooltip-ca-nstab-main' => 'Допчы арынын көөрү',
'tooltip-ca-nstab-user' => 'Ажыглакчының арынын көөрү',
'tooltip-ca-nstab-media' => 'Медиа арынын көөрү',
'tooltip-ca-nstab-special' => 'Бо бөлгээт арын-дыр (служебная страница), ооң эдери болдунмас.',
'tooltip-ca-nstab-project' => 'Төлевилелдиң арынын көөрү',
'tooltip-ca-nstab-image' => 'Файлдың арынын көөрү',
'tooltip-ca-nstab-template' => 'Майыкты көөрү',
'tooltip-ca-nstab-help' => 'Дуза арынын көөрү',
'tooltip-ca-nstab-category' => 'Бөлүктүң арынын көөрү',
'tooltip-minoredit' => 'Бо өскертилгени "биче" деп демдеглээр',
'tooltip-save' => 'Силерниң өскерлиишкиннериңерни шыгжаары',
'tooltip-preview' => 'Шыгжаар мурнунда силерниң өскерлиишкиннерин чижеглеп көрем!',
'tooltip-diff' => 'Бо сөзүглелге хамаарыштыр кандыг өскертилгелерни кылган Силер - ону көргүзер.',
'tooltip-compareselectedversions' => 'Бо арынның шилиттинген ийи хевиринниң ылгалын көөр.',
'tooltip-watch' => 'Силерниң хайгаарал даңзызынга бо арынны немерелээри',
'tooltip-rollback' => 'Сөөлгү киржикчиниң өскерилгелерин чаңгыс баскаш, ойталаар',
'tooltip-undo' => 'Киирген эдигни казааш, ойталалдың чылдагаанын айтыр аргалыг мурнай көргүзүүн көргүзер.',
'tooltip-summary' => 'Кысказы-биле бижиңер',

# Attribution
'anonymous' => '{{grammar:genitive|{{SITENAME}}}} ат эвес {{PLURAL:$1|ажыглакчызы|ажыглакчылары}}',

# Skin names
'skinname-standard' => 'Классик',
'skinname-nostalgia' => 'Ностальгия',
'skinname-cologneblue' => 'Cologne Blue',
'skinname-monobook' => 'МоноБук',
'skinname-myskin' => 'МайСкин',
'skinname-chick' => 'Чикк',
'skinname-simple' => 'Симпел',
'skinname-modern' => 'Модерн',
'skinname-vector' => 'Вектор',

# Image deletion
'filedelete-missing' => '«$1» деп файл чок, ынчангаш ол ап калдынмас.',

# Browsing diffs
'previousdiff' => '← Артык эрги үндүрери',
'nextdiff' => 'Артык чаа үндүрери →',

# Media information
'thumbsize' => 'Бичии чурумалдың хемчээли:',
'widthheightpage' => '$1x$2, $3 {{PLURAL:$3|арын}}',
'file-info' => 'файлдың хемчээли: $1, MIME хевири: $2',
'file-info-size' => '$1 × $2 пиксел, Файл хемчээли: $3, MIME хевири: $4',
'file-info-size-pages' => '$1 × $2 пикcелдер, файл хемчээли: $3, MIME хевири: $4, $5 {{PLURAL:$5|арын|арын}}',
'file-nohires' => 'Оон улуг хевири чок',
'svg-long-desc' => 'SVG файл, $1 x $2 пиксел, файл хемчээли: $3',
'show-big-image' => 'Улуг чурумал',
'show-big-image-size' => '$1 × $2 пиксел',

# Special:NewFiles
'newimages-legend' => 'Шүүрү',
'showhidebots' => '(роботтарны $1)',
'noimages' => 'Nothing to see.',
'ilsubmit' => 'Дилээр',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL: $1|$1 секунда|$1 секунда}}',
'minutes' => '{{PLURAL: $1|$1 минут|$1 минут}}',
'hours' => '{{PLURAL:$1|$1 шак|$1 шак}}',
'days' => '{{PLURAL:$1|$1 хүн|$1 хүн}}',
'ago' => '$1 бурунгаар',

# Bad image list
'bad_image_list' => 'Формады мындыг боор ужурлуг:

Чүгле даңзының идегеттери (элементилери) санатынар боор (* деп демдектен эгелээн одуруглар).
Одуругнуң бирги шөлүдүү салдынмас чурумалче шөлүдүг болуру албан.
Ол-ла одуругнуң арткан шөлүдүглери онзагай кылдыр азы чурумал капсырып болур чүүлдер кылдыр санаттынар.',

# Metadata
'metadata' => 'Чурумал дугайында медээлер',
'metadata-help' => 'Бо файл немелде данныйларлыг:санныг камералар азы сканнерлер дугайында медеглел. Файл чаяанының соонда эдидип турган болза, чамдык параметрлери амгы чурумалга меге кылдыр хамааржып болур.',
'metadata-fields' => 'Бо даңзыда айыткан чурумалдар метаданныйларның кезектери чурумалдың арынынга көстүп кээр, метаданныйлар таблицазын дүрүп каан болур. 
Арткан кезектер аайлаан ёзугаар чажыт көстүр.
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
'exif-imagewidth' => 'Калбаа',
'exif-imagelength' => 'Бедик',
'exif-imagedescription' => 'Чуруктуң ады',
'exif-artist' => 'Чогаадыкчы',
'exif-usercomment' => 'Ажыглакчының тайылбырлары',
'exif-jpegfilecomment' => 'JPEG фалй тайылбыры',
'exif-headline' => 'Баш ат',
'exif-languagecode' => 'Дыл',
'exif-pngfilecomment' => 'PNG фалй тайылбыры',
'exif-giffilecomment' => 'GIF фалй тайылбыры',

'exif-subjectdistancerange-2' => 'Чоок көрүш',
'exif-subjectdistancerange-3' => 'ырак көрүш',

'exif-dc-type' => 'Медиа хевири',

'exif-iimcategory-sci' => 'Эртем база техника',
'exif-iimcategory-spo' => 'Спорт',
'exif-iimcategory-wea' => 'Агаар',

# External editor support
'edit-externally' => 'Бо файлды даштыкы капсырылга-биле эдер',
'edit-externally-help' => '(Улаштыр тодарадырда бо [//www.mediawiki.org/wiki/Manual:External_editors кыстып алыр саавырны] көрүңер)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'шупту',
'namespacesall' => 'шупту',
'monthsall' => 'шупту',
'limitall' => 'шупту',

# Delete conflict
'recreate' => 'Катап чогаадыры',

# action=purge
'confirm_purge_button' => 'Чөп',

# action=watch/unwatch
'confirm-watch-button' => 'Чөп',
'confirm-unwatch-button' => 'Чөп',

# Multipage image navigation
'imgmultipageprev' => '← эрткен арын',
'imgmultipagenext' => 'дараазында арын →',
'imgmultigo' => 'Go!',

# Table pager
'table_pager_next' => 'Дараазында арын',
'table_pager_prev' => 'Эрткен арын',
'table_pager_first' => 'Бирги арын',
'table_pager_last' => 'Сөөлгү арын',
'table_pager_limit_submit' => 'Күүcедири',
'table_pager_empty' => 'Түңнеллер чок',

# Auto-summaries
'autosumm-new' => 'Чаа арынны чогаадаан: «$1»',

# Watchlist editor
'watchlistedit-normal-title' => 'Хайгаарал даңзыны өскертири',
'watchlistedit-normal-submit' => 'Аттарны ырадыры',
'watchlistedit-raw-title' => 'Чиг хайгаарал даңзыны өскертири',
'watchlistedit-raw-legend' => 'Чиг хайгаарал даңзыны өскертири',
'watchlistedit-raw-titles' => 'Aттар:',

# Watchlist editing tools
'watchlisttools-view' => 'Даңзы арыннарының өскерлиишкиннери',
'watchlisttools-edit' => 'Хайгаарал даңзыны көөрү/эдери',
'watchlisttools-raw' => 'Чиг хайгаарал даңзыны өскертири',

# Core parser functions
'duplicate-defaultsort' => 'Кичээнгейлиг! Үндезин сорттаашкын дүлгүүрү «$2» биеэги үндезин сорттаашкын дүлгүүрүн «$1» ажыр тодарадып турар.',

# Special:Version
'version' => 'Үндүрери',
'version-specialpages' => 'Тускай арыннар',
'version-other' => 'Өске',
'version-software-version' => 'Үндүрери',

# Special:FilePath
'filepath-page' => 'Файл:',
'filepath-submit' => 'Күүcедири',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Файлдың ады:',
'fileduplicatesearch-submit' => 'Дилээри',
'fileduplicatesearch-info' => '$1 × $2 пиксел<br />Файл хемчээли: $3<br />MIME хевири: $4',

# Special:SpecialPages
'specialpages' => 'Тускай арыннар',
'specialpages-group-other' => 'Өске тускай арыннар',
'specialpages-group-pages' => 'Арыннарның даңзызы',
'specialpages-group-pagetools' => 'Арын херекселдери',

# Special:BlankPage
'blankpage' => 'Куруг арын',

# Special:Tags
'tag-filter' => '[[Special:Tags|демдек]] шүүрү:',
'tag-filter-submit' => 'Шүүрү',
'tags-title' => 'Демдеглелдер',
'tags-edit' => 'өскертири',
'tags-hitcount' => '$1 {{PLURAL:$1|өскерлиишкин}}',

# Special:ComparePages
'comparepages' => 'Арыннарны дөмейлеп көөрү',
'compare-selector' => 'Арынның ылгалдарын дөмейлеп көөрү',
'compare-page1' => 'Арын 1',
'compare-page2' => 'Арын 2',
'compare-submit' => 'Дөмейлээри',

# HTML forms
'htmlform-submit' => 'Күүcедири',
'htmlform-selectorother-other' => 'Өске',

# Feedback
'feedback-subject' => 'Кол сөс:',
'feedback-message' => 'Чагаа:',
'feedback-cancel' => 'Соксаары',

# Durations
'duration-seconds' => '$1 {{PLURAL: $1|секунда|секунда}}',
'duration-minutes' => '$1 {{PLURAL: $1|минут|минут}}',
'duration-hours' => '$1 {{PLURAL: $1|шак|шак}}',
'duration-days' => '$1 {{PLURAL:$1|хүн|хүн}}',
'duration-weeks' => '$1 {{PLURAL: $1|чеди-хонук|чеди-хонук}}',
'duration-years' => '$1 {{PLURAL: $1|чыл|чыл}}',
'duration-decades' => '$1 {{PLURAL:$1|он хонук|он хонук}}',
'duration-centuries' => '$1 {{PLURAL:$1|чүс чыл|чүс чыл}}',

);
