<?php
/** Ossetic (Ирон)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amikeco
 * @author Amire80
 * @author Bouron
 * @author HalanTul
 * @author לערי ריינהארט
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Сæрмагонд',
	NS_TALK             => 'Тæрхон',
	NS_USER             => 'Архайæг',
	NS_USER_TALK        => 'Архайæджы_ныхас',
	NS_PROJECT_TALK     => '{{GRAMMAR:genitive|$1}}_тæрхон',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файлы_тæрхон',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-йы_тæрхон',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблоны_тæрхон',
	NS_HELP             => 'Æххуыс',
	NS_HELP_TALK        => 'Æххуысы_тæрхон',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категорийы_тæрхон',
);

$namespaceAliases = array(
	'Дискусси'                    => NS_TALK,
	'Архайæджы_дискусси'          => NS_USER_TALK,
	'Дискусси_$1'                 => NS_PROJECT_TALK,
	'Ныв'                         => NS_FILE,
	'Нывы_тæрхон'                 => NS_FILE_TALK,
	'Нывы_тыххæй_дискусси'        => NS_FILE_TALK,
	'Дискусси_MediaWiki'          => NS_MEDIAWIKI_TALK,
	'Тæрхон_MediaWiki'            => NS_MEDIAWIKI_TALK,
	'Шаблоны_тыххæй_дискусси'     => NS_TEMPLATE_TALK,
	'Æххуысы_тыххæй_дискусси'     => NS_HELP_TALK,
	'Категорийы_тыххæй_дискусси'  => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'АктивонАрхайджытæ' ),
	'Allmessages'               => array( 'ФыстæджытæИууылдæр' ),
	'Allpages'                  => array( 'ФæрстæИууылдæр' ),
	'Blankpage'                 => array( 'АфтидФарс' ),
	'Block'                     => array( 'Блок' ),
	'Blockme'                   => array( 'НыблокМæКæн' ),
	'Booksources'               => array( 'ЧингуытыРавзæрæнтæ' ),
	'BrokenRedirects'           => array( 'ЦъæлРарвыстытæ' ),
	'Categories'                => array( 'Категоритæ' ),
	'ChangeEmail'               => array( 'EmailИвын' ),
	'ChangePassword'            => array( 'ПарольИвын' ),
	'ComparePages'              => array( 'ФæрстæАбарын' ),
	'Confirmemail'              => array( 'EmailБæлвырдКæнын' ),
	'Contributions'             => array( 'Бавæрд' ),
	'CreateAccount'             => array( 'АккаунтСкæнын' ),
	'DeletedContributions'      => array( 'ХафтБавæрд' ),
	'Mycontributions'           => array( 'МæБавæрд' ),
	'Mypage'                    => array( 'МæФарс' ),
	'Mytalk'                    => array( 'МæНыхас' ),
	'Myuploads'                 => array( 'МæБавгæд' ),
	'Newimages'                 => array( 'НогФайлтæ' ),
	'Newpages'                  => array( 'НогФæрстæ' ),
	'Preferences'               => array( 'Фадæттæ' ),
	'Randompage'                => array( 'ÆрхаугæФарс' ),
	'Recentchanges'             => array( 'ФæстагИвдтытæ' ),
	'Search'                    => array( 'Агурын' ),
	'Watchlist'                 => array( 'Цæстдард' ),
);


$magicWords = array(
	'redirect'                  => array( '0', '#РАРВЫСТ', '#перенаправление', '#перенапр', '#REDIRECT' ),
	'img_right'                 => array( '1', 'рахиз', 'справа', 'right' ),
	'img_left'                  => array( '1', 'галиу', 'слева', 'left' ),
);

$linkTrail = '/^((?:[a-z]|а|æ|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я|“|»)+)(.*)$/sDu';
$fallback8bitEncoding =  'windows-1251';

$messages = array(
# User preference toggles
'tog-underline' => 'Æрвитæнты бын хахх',
'tog-justify' => 'Æмвæз абзацтæ',
'tog-hideminor' => 'Чысыл ивддзинæдтæ фæстаг ивддзинæдты номхыгъды мауал æвдис',
'tog-hidepatrolled' => 'Айсын бæрæггонд ивдтытæ фæстаг ивдтытæй',
'tog-newpageshidepatrolled' => 'Басгæрст фæрстæ ног фæрсты номхыгъдæй æмбæхс',
'tog-extendwatchlist' => 'Стырдæр цæстдард номхыгъд алы ивдимæ дæр, æрмæст фæстагимæ нал.',
'tog-usenewrc' => 'Фæстаг æмæ цæстдард ивдтытæ фарсмæ гæсгæ иу кæнæт (домы JavaScript)',
'tog-numberheadings' => 'Сæртæн хæдæвзæргæ номыр æвæрын',
'tog-showtoolbar' => 'Æвдисын ивыны панел (домы JavaScript)',
'tog-editondblclick' => 'Фæрстæ дыкъæппæй ив (JavaScript)',
'tog-editsection' => 'Равдис «баив æй» æрвитæн тексты алы хайы дæр',
'tog-editsectiononrightclick' => 'Хайы сæрыл рахис æркъæппæй ивыны фадат баиу кæнын (домы JavaScript)',
'tog-showtoc' => 'Сæрты номхыгъд æвдисын (æртæйæ фылдæрсæр цы фарсы ис, уым)',
'tog-rememberpassword' => 'Бахъуыды мæ кæнæд ацы браузер ($1 {{PLURAL:$1|бонмæ|бонмæ}})',
'tog-watchcreations' => 'Æз цы фæрстæ аразын æмæ цы файлтæ бавгæнын, уыдон мæ цæстдард уæт.',
'tog-watchdefault' => 'Æз цы фæрстæ æмæ цы файлтæ ивын, уыдон мæ цæстдард уæт',
'tog-watchmoves' => 'Æз цы фæрсты нæмттæ æмæ цы файлтæ ивын, уыдон мæ цæстдард уæт',
'tog-watchdeletion' => 'Æз цы фæрстæ æмæ цы файлтæ хафын, уыдон мæ цæстдард уæт',
'tog-minordefault' => 'Æппæт ивддзинæдтæ банысан кæн куыд чысылтæ',
'tog-previewontop' => 'Разæркасты рудзынг ивыны рудзынджы уæлдæр',
'tog-previewonfirst' => 'Æвдисын иу разæркаст фыццаг ивды рæстæджы',
'tog-nocache' => 'Ахицæн кæнын браузеры кешкæнынад',
'tog-enotifwatchlistpages' => 'Æз цы фæрстæм æмæ цы файлтæм дарын мæ цæст, уыдонæй иу ивд куы æрцæуа, уæд-иу мæм E-mail æрцæуæд',
'tog-enotifusertalkpages' => 'Электронон постæй мæм хъуысынгæнинаг æрвыст уа, мæ тæрхоны фарс куы ивд æрцæуа, уæд',
'tog-enotifminoredits' => 'Фарс кæнæ файлы ивд чысыл куы уа, уæддæр-иу мæм E-mail æрбацæуæт',
'tog-enotifrevealaddr' => 'Æвдисын мæ e-mail хъусын кæныны фыстæджыты',
'tog-shownumberswatching' => 'Цал архайæджы фарсмæ сæ цæст дарынц, уый равдис',
'tog-oldsig' => 'Ныры къухæрфыст:',
'tog-fancysig' => 'Сæвæрын къухæрфыст викитекстæй (æнæ хæдæвзæргæ æрвитæнæй)',
'tog-externaleditor' => 'Архайын æддаг ивæнæй (æрмæст эксперттæн, домы сæрмагонд æвæрдтытæ компьютерыл. [//www.mediawiki.org/wiki/Manual:External_editors Лæмбынæг.])',
'tog-externaldiff' => 'Архайын æндæр иртасæнæй (æрмæст эксперттæн, домы сæрмагонд æвæрдтытæ компьютерыл. [//www.mediawiki.org/wiki/Manual:External_editors Лæмбынæг.])',
'tog-showjumplinks' => 'Тагъд æрвитæнтæй пайда кæн',
'tog-uselivepreview' => 'Архайын тагъд разбакастæй (домы JavaScript) (эксперименталон)',
'tog-forceeditsummary' => 'Фæдзæхсæд иу мæ, кæд ивды афыст афтид уа',
'tog-watchlisthideown' => 'Айсын мæ ивдтытæ цæстдардæй',
'tog-watchlisthidebots' => 'Мæ цæстдарды номхыгъды роботты куыст бамбæхс',
'tog-watchlisthideminor' => 'Мæ цæстдарды номхыгъды чысыл ивддзинæдтæ бамбæхс',
'tog-watchlisthideliu' => 'Айсын системæмæ хызт архайджыты ивдтытæ мæ цæстдардæй',
'tog-watchlisthideanons' => 'Айсын æнæном архайджыты ивдтытæ мæ цæстдардæй',
'tog-watchlisthidepatrolled' => 'Айсын сгæрст ивдтытæ цæстдардæй',
'tog-ccmeonemails' => 'Æз электронон фыстæг æндæр архайæгæн куы рарвитын, уæд уыцы иу фыстæг мæхи адрисмæ дæр æрбацæуæд.',
'tog-diffonly' => 'Ма æвдис фарсы мидис иртасæны бынмæ',
'tog-showhiddencats' => 'Æмбæхст категоритæ æвдис',
'tog-norollbackdiff' => 'Рауадзын иртасæн раздæхты фæстæ',

'underline-always' => 'Æдзух',
'underline-never' => 'Никуы',
'underline-default' => 'Цъар æви сгарæнмæ гæсгæ',

# Font style option in Special:Preferences
'editfont-style' => 'Ивæн бынаты шрифты стил:',
'editfont-default' => 'Браузеры куыд у',
'editfont-monospace' => 'Æмуæрæх шрифт',
'editfont-sansserif' => 'Sans-serif шрифт',
'editfont-serif' => 'Serif шрифт',

# Dates
'sunday' => 'хуыцаубон',
'monday' => 'къуырисæр',
'tuesday' => 'дыццæг',
'wednesday' => 'æртыццæг',
'thursday' => 'цыппæрæм',
'friday' => 'майрæмбон',
'saturday' => 'сабат',
'sun' => 'Хцб',
'mon' => 'Крс',
'tue' => 'Дцг',
'wed' => 'Æрт',
'thu' => 'Цпр',
'fri' => 'Мрб',
'sat' => 'Сбт',
'january' => 'январь',
'february' => 'февраль',
'march' => 'мартъи',
'april' => 'апрель',
'may_long' => 'май',
'june' => 'июнь',
'july' => 'июль',
'august' => 'август',
'september' => 'сентябрь',
'october' => 'октябрь',
'november' => 'ноябрь',
'december' => 'декабрь',
'january-gen' => 'январы',
'february-gen' => 'февралы',
'march-gen' => 'мартъийы',
'april-gen' => 'апрелы',
'may-gen' => 'майы',
'june-gen' => 'июны',
'july-gen' => 'июлы',
'august-gen' => 'августы',
'september-gen' => 'сентябры',
'october-gen' => 'октябры',
'november-gen' => 'ноябры',
'december-gen' => 'декабры',
'jan' => 'янв',
'feb' => 'фев',
'mar' => 'мар',
'apr' => 'апр',
'may' => 'май',
'jun' => 'июн',
'jul' => 'июл',
'aug' => 'авг',
'sep' => 'сен',
'oct' => 'окт',
'nov' => 'ноя',
'dec' => 'дек',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Категори|Категоритæ}}',
'category_header' => 'Категори "$1"',
'subcategories' => 'Дæлкатегоритæ',
'category-media-header' => 'Категори "{{grammar:genitive|$1}}" файлтæ',
'category-empty' => "''Ацы категори афтид у.''",
'hidden-categories' => 'Æмбæхст {{PLURAL:$1|категори|категоритæ}}',
'hidden-category-category' => 'Æмбæхст категоритæ',
'category-subcat-count' => '{{PLURAL:$2|Ацы категорийы мидæг æрмæст иу дæлкатегори ис.|{{PLURAL:$1|$1 дæлкатегори æвдыст у|$1 дæлкатегорийы æвдыст сты}}, æдæппæт $2.}}',
'category-subcat-count-limited' => 'Ацы категорийы мидæг ис {{PLURAL:$1|$1 дæлкатегори|$1 дæлкатегорийы}}.',
'category-article-count' => '{{PLURAL:$2|Ацы категорийы æрмæст иу фарс ис.|Ацы категорийы $2 фарсæй {{PLURAL:$1|æвдыст у $1.|æвдыст сты $1 фарсы.}}}}',
'category-article-count-limited' => 'Ацы категорийы мидæг {{PLURAL:$1|$1 фарс|$1 фарсы}} ис.',
'category-file-count' => '{{PLURAL:$2|Ацы категорийы æрмæст иу файл ис.|Ацы категорийы $2 файлæй {{PLURAL:$1|æвдыст у $1.|æвдыст сты $1.}}}}',
'category-file-count-limited' => 'Ацы категорийы {{PLURAL:$1|$1 файл|$1 файлы}} ис.',
'listingcontinuesabbrev' => '(дарддæргонд)',
'index-category' => 'Индексгонд фæрстæ',
'noindex-category' => 'Нæиндексгонд фæрстæ',
'broken-file-category' => 'Файлтæм саст æрвитæнтæ кæм ис, ахæм фæрстæ',

'about' => 'Афыст',
'article' => 'Уац',
'newwindow' => '(кæны ног рудзынджы)',
'cancel' => 'Ныууадзын',
'moredotdotdot' => 'Фылдæр…',
'mypage' => 'Фарс',
'mytalk' => 'Ныхас',
'anontalk' => 'Ацы IP-адрисы тæрхон',
'navigation' => 'Навигаци',
'and' => '&#32;æмæ',

# Cologne Blue skin
'qbfind' => 'Агур',
'qbbrowse' => 'Фен',
'qbedit' => 'Ивын',
'qbpageoptions' => 'Ацы фарс',
'qbpageinfo' => 'Фарсы контекст',
'qbmyoptions' => 'Мæ фæрстæ',
'qbspecialpages' => 'Сæрмагонд фæрстæ',
'faq' => 'FAQ',
'faqpage' => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Нæуæг ныхас бакæнын',
'vector-action-delete' => 'Схафын',
'vector-action-move' => 'Ном ивын',
'vector-action-protect' => 'Сæхгæнын',
'vector-action-undelete' => 'Рацаразын',
'vector-action-unprotect' => 'Ивын хъахъхъæд',
'vector-simplesearch-preference' => 'Баиу кæнын æнцонгонд агурыны формæ (Вектор цармæн æрмæст)',
'vector-view-create' => 'Скæнын',
'vector-view-edit' => 'Ивын',
'vector-view-history' => 'Истори',
'vector-view-view' => 'Кæсын',
'vector-view-viewsource' => 'Код кæсын',
'actions' => 'Архайдтæ',
'namespaces' => 'Номдæттæ',
'variants' => 'Варианттæ',

'errorpagetitle' => 'Рæдыд',
'returnto' => 'Фæстæмæ $1 фарсмæ.',
'tagline' => '{{grammar:ablative|{{SITENAME}}}}',
'help' => 'Æххуыс',
'search' => 'Агуырд',
'searchbutton' => 'Агурын',
'go' => 'Уацмæ',
'searcharticle' => 'Уацмæ',
'history' => 'Фарсы истори',
'history_short' => 'Истори',
'updatedmarker' => 'ноггонд мæ фæстаг æрбахызтæй',
'printableversion' => 'Мыхурмæ верси',
'permalink' => 'Ацы версимæ æрвитæн',
'print' => 'Мыхуыр',
'view' => 'Æркæст',
'edit' => 'Ивын',
'create' => 'Скæнын',
'editthispage' => 'Ивын ацы фарс',
'create-this-page' => 'Ацы фарс скæнын',
'delete' => 'Схафын',
'deletethispage' => 'Ацы фарс схафын',
'undelete_short' => 'Рацаразын {{PLURAL:$1|иу ивд|$1 ивды}}',
'viewdeleted_short' => 'Кæсын {{PLURAL:$1|иу хафт ивд|$1 хафт ивдтытæ}}',
'protect' => 'Сæхгæнын',
'protect_change' => 'баивын',
'protectthispage' => 'Сæхгæн ацы фарс',
'unprotect' => 'Ивын хъахъхъæд',
'unprotectthispage' => 'Ивын ацы фарсы хъахъхъæд',
'newpage' => 'Ног фарс',
'talkpage' => 'Ацы фарсы тыххæй ныхас',
'talkpagelinktext' => 'Ныхас',
'specialpage' => 'Сæрмагонд фарс',
'personaltools' => 'Мигæнæнтæ',
'postcomment' => 'Ног хай',
'articlepage' => 'Фенын уац',
'talk' => 'Тæрхон',
'views' => 'Æркæстытæ',
'toolbox' => 'Фæрæзтæ',
'userpage' => 'Ацы архайæджы фарс фен',
'projectpage' => 'Проекты фарс фен',
'imagepage' => 'Файлы фарс фен',
'mediawikipage' => 'Фыстæджы фарс фен',
'templatepage' => 'Хуызæджы фарс фенын',
'viewhelppage' => 'Æххуысы фарс фен',
'categorypage' => 'Категорийы фарс фен',
'viewtalkpage' => 'Тæрхон фен',
'otherlanguages' => 'Æндæр æвзæгтыл',
'redirectedfrom' => '({{grammar:ablative|$1}} æрвыст)',
'redirectpagesub' => 'Рарвысты фарс',
'lastmodifiedat' => 'Ацы фарс фæстаг хатт ивд æрцыд: $1, $2.',
'viewcount' => 'Ацы фарс домд æрцыд {{PLURAL:$1|иу хатт|$1 хатты}}.',
'protectedpage' => 'Æхгæд фарс',
'jumpto' => 'Тагъд æрвитæнтæ:',
'jumptonavigation' => 'навигаци',
'jumptosearch' => 'агуырд',
'view-pool-error' => 'Хатыр, сервертæ тынг æнæвдæлон сты ацы тæккæ.
Æгæр бирæ архайæджы фæлварынц ацы фарс фенын.
Дæ хорзæхæй, гыццыл фæлæуу æмæ та нæуæгæй бафæлвар.

$1',
'pool-timeout' => 'Скусыны афон у',
'pool-queuefull' => 'Процессты рад йедзаг у',
'pool-errorunknown' => 'Æбæрæг рæдыд',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{grammar:genitive|{{SITENAME}}}} тыххæй',
'aboutpage' => 'Project:Афыст',
'copyright' => 'Лицензи: $1.',
'copyrightpage' => '{{ns:project}}:Авторы бартæ',
'currentevents' => 'Ног хабæрттæ',
'currentevents-url' => 'Project:Ног хабæрттæ',
'disclaimers' => 'Бæрн',
'disclaimerpage' => 'Project:Бæрн',
'edithelp' => 'Ивынæн æххуыс',
'edithelppage' => 'Help:Ивын',
'helppage' => 'Help:Мидис',
'mainpage' => 'Сæйраг фарс',
'mainpage-description' => 'Сæйраг фарс',
'policy-url' => 'Project:Фæтк',
'portal' => 'Архайджыты æхсæнад',
'portal-url' => 'Project:Архайджыты æхсæнад',
'privacy' => 'Хибардзинады политикæ',
'privacypage' => 'Project:Хибардзинады политикæ',

'badaccess' => 'Бацæуæны рæдыд',
'badaccess-group0' => 'Ацы архайд ракæныны бар дæм нæй.',
'badaccess-groups' => 'Æрдомд арæзтытæ ис бар аразын æрмаст ацы {{PLURAL:$2|къорды|къордты}} уæнгтæн: $1.',

'versionrequired' => 'Хъæуы MediaWiki-йы версии $1',
'versionrequiredtext' => 'Хъæуы MediaWiki-йы $1 фæлтæр, цæмæй ацы фарсæй архайæн уа.
Кæс [[Special:Version|фæлтæры фарс]].',

'ok' => 'Хорз',
'pagetitle' => '$1 — {{SITENAME}}',
'retrievedfrom' => 'Ист æрцыд {{grammar:ablative|"$1"}}',
'youhavenewmessages' => 'Райстай $1 ($2).',
'newmessageslink' => 'ног фыстæгтæ',
'newmessagesdifflink' => 'фæстаг ивд',
'youhavenewmessagesfromusers' => '{{PLURAL:$3|Æндæр архайæгæй|$3 архайæгæй}} дæм $1 æрцыд ($2).',
'youhavenewmessagesmanyusers' => 'Бирæ архайæгæй дæм $1 æрцыдис ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|ног фыстæг|ног фыстæджытæ}}',
'newmessagesdifflinkplural' => 'фæстаг {{PLURAL:$1|ивд|ивдтытæ}}',
'youhavenewmessagesmulti' => 'Дæумæ æрцыдис ног фыстæджытæ {{grammar:genitive|$1}}',
'editsection' => 'ивын',
'editold' => 'ивын',
'viewsourceold' => 'Код кæсын',
'editlink' => 'ивын',
'viewsourcelink' => 'Код кæсын',
'editsectionhint' => 'Хай ивын: $1',
'toc' => 'Сæртæ',
'showtoc' => 'равдисын',
'hidetoc' => 'бамбæхсын',
'collapsible-collapse' => 'Стухын',
'collapsible-expand' => 'Райтынг кæнын',
'thisisdeleted' => '$1 фенын хъæуы, æви рацаразын ?',
'viewdeleted' => '$1 фенын дæ фæнды?',
'restorelink' => '{{PLURAL:$1|иу хафт ивд|$1 хафт ивды}}',
'feedlinks' => 'Лæсæн:',
'feed-invalid' => 'Рæдыд рафыссæн каналы хуыз.',
'feed-unavailable' => 'Синдикацийы лæсæнтæм бавналæн нæй',
'site-rss-feed' => '{{grammar:genitive|$1}} RSS лæсæн',
'site-atom-feed' => '{{grammar:genitive|$1}} Atom лæсæн',
'page-rss-feed' => '"{{grammar:genitive|$1}}" RSS лæсæн',
'page-atom-feed' => '"{{grammar:genitive|$1}}" Atom лæсæн',
'red-link-title' => '$1 (фарс нæй)',
'sort-descending' => 'Радæвæрын цъускæнынмæ',
'sort-ascending' => 'Радæвæрын фылдæркæнынмæ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Уац',
'nstab-user' => 'Архайæджы фарс',
'nstab-media' => 'Медиа фарс',
'nstab-special' => 'Сæрмагонд фарс',
'nstab-project' => 'Проекты фарс',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'Фыстæг',
'nstab-template' => 'Хуызæг',
'nstab-help' => 'Æххуысы фарс',
'nstab-category' => 'Категори',

# Main script and global functions
'nosuchaction' => 'Нæй ахæм гæнæн',
'nosuchactiontext' => 'URL-æй амынд архайд раст неу.
Гæнæн ис раст нæ ныффыстай URL кæнæ рæдыд æрвитæны фæдыл ацыдтæ.
Гæнæн ма ис {{SITENAME}} цы софтæй архайы, уый рæдыд у.',
'nosuchspecialpage' => 'Ахæм сæрмагонд фарс нæй',
'nospecialpagetext' => '<strong>Нæй ахæм сæрмагонд фарс.</strong>

Кæс [[Special:SpecialPages|æппæт сæрмагонд фæрсты номхыгъд]].',

# General errors
'error' => 'Рæдыд',
'databaseerror' => 'Рарддоны рæдыд',
'dberrortext' => 'Рарддонмæ домæны синтаксисы разындис рæдыд.
Уый гæнæн ис нысан кæны рæдыд софты куысты.
Фæстаг домæн рарддонмæ уыдис:
<blockquote><code>$1</code></blockquote>
сæвзæрдис "<code>$2</code>" функцийæ.
Рарддон раздæхта ахæм рæдыд: "<samp>$3: $4</samp>".',
'dberrortextcl' => 'Рарддонмæ домæны синтаксисы разындис рæдыд.
Фæстаг домæн рарддонмæ уыдис:
"$1"
сæвзæрдис "$2" функцийæ.
Рарддон раздæхта ахæм рæдыд: "$3: $4"',
'laggedslavemode' => "'''Сындæг:''' Фарсы гæнæн ис нæй фæстаг нæуæггæндтæ.",
'readonly' => 'Рарддон æхгæд у',
'enterlockreason' => 'Ныффысс æхгæныны бындур æмæ йæ æмгъуыд',
'readonlytext' => 'Рарддон ныртæккæ у æхгæд, цæмæй нæуæг иуæгтæ æмæ модификацитæ хаст æрцæуой æмæ уый фæстæ байгом уыдзæн.

Цы радгæс æй сæхгæдта, уый ныууагъта ахæм амонæн: $1',
'missing-article' => 'Рарддон не ссардта уыцы фарсы текст, кæцы агуырдта. Йæ ном у "$1" $2.

Ацы рæдыд фылдæр рацæуы зæронд иртасæн кæнæ хафт файлы историмæ æрвитæны фæдыл ацæугæйæ.

Кæд уый аххос нæу, уæд гæнæн ис цыдæр раст нæу софты.
Дæ хорзæхæй, сардау ацы рæдыд [[Special:ListUsers/sysop|радгæсыл]] æмæ иу ын ацы фарсы URL дæр зæгъ.',
'missingarticle-rev' => '(фæлтæр № $1)',
'missingarticle-diff' => '(хъауджы: $1, $2)',
'readonly_lag' => 'Рарддон йæхæдæг сæхгæд ис, цалынмæ дыггагон севертæ нæ ссинхронизаци кæной фыццагонтимæ',
'internalerror' => 'Мидæг рæдыд',
'internalerror_info' => 'Мидæг рæдыд: $1',
'fileappenderrorread' => 'Нæ рауадис бафæрсын "$1" æфтауыны рæстæджы.',
'fileappenderror' => 'Нæ рауадис бафтауын "$1" "{{grammar:allative|$2}}".',
'filecopyerror' => 'Файл «$1» файлмæ «$2» халдихгæнæн нæ разынд.',
'filerenameerror' => 'Нæ рауадис фæивын "$1" файлы ном "$2"-мæ.',
'filedeleteerror' => 'Нæ уайы файл «$1» схафын.',
'directorycreateerror' => 'Нæй саразæн файлдон «$1».',
'filenotfound' => 'Нæй ссарæн файл «$1».',
'fileexistserror' => 'Нæй фыссæн "$1" файлы: Файл ис.',
'unexpected' => 'Æнæмбæлон æмиасад: «$1»=«$2».',
'formerror' => 'Рæдыд: формæ арвитæн нæй',
'badarticleerror' => 'Ацы архайд нæ арæзт кæны ацы фарсыл.',
'cannotdelete' => 'Нæ уайы файл кæнæ фарс "$1" схафын.
Гæнæн ис, æндæр чидæр æй схафта.',
'cannotdelete-title' => 'Нæ уайы схафын фарс "$1"',
'delete-hook-aborted' => 'Хук æй хафын нæ бауагъта.
Уæлæмхасæн æмбарынгæнæн нæ радта.',
'badtitle' => 'Æвзæр ном',
'badtitletext' => 'Æрдомд фарсы ном уыд рæдыдимæ кæнæ афтид кæнæ та йæ æхсæн-æвзаг æви йæ интервики æрвитæн раст нæ уыд.
Гæнæн ис Номы ис ахæм дамгъæтæ, кæдон уым æвæрын нæй гæнæн.',
'perfcached' => 'Бындæр цы рардтæ ис, уыдон сты кешгонд æмæ гæнæн ис базæронд сты. Кешы гæнæн ис уа æппæты фылдæр {{PLURAL:$1|иу фæстиуæг|$1 фæстиуæджы}}.',
'perfcachedts' => 'Бындæр цы рардтæ ис, уыдон сты кешгонд æмæ фæстаг хатт нæуæггонд æрцыдысты $1. Кешы гæнæн ис уа æппæты фылдæр {{PLURAL:$4|иу фæстиуæг|$4 фæстиуæджы}}.',
'querypage-no-updates' => 'Ацы фарсы нæуæгкæнын ныртæккæ хицæн у.
Цы рардтæ дзы ис, уыдон нæуæг нæ кæндзысты.',
'wrong_wfQuery_params' => 'Рæдыд параметртæ wfQuery() функцийæн<br />
Функци: $1<br />
Домæн: $2',
'viewsource' => 'Код кæсын',
'viewsource-title' => 'Фенын {{grammar:genitive|$1}} равзæрæн текст',
'actionthrottled' => 'Архайд урæд æрцыд',
'actionthrottledtext' => 'Спамы ныхмæ тохы тыххæй дæуæн нæй гæнæн аразын ацы архайд æвæрд лимитæй фылдæр гыццыл рæстæджы. Ды уыцы лимит сæххæст кодтай.
Дæ хорзæхæй, бафæлвар нæуæгæй цалдæр минуты фæстæ.',
'protectedpagetext' => 'Ацы фарс ивынæй æмæ æндæр митæй æхгæд у.',
'viewsourcetext' => 'Ацы фарсы код фенæн æмæ халдих кæнæн ис:',
'viewyourtext' => "Дæ бон у фенын æмæ скопи кæнын ацы фарсмæ '''цы ивдтытæ сарæзтай''', уыдон бындур текст :",
'protectedinterface' => 'Ацы фарсы ис ацы викийы софты интерфейсæн текст æмæ у æхгæд, цæмæй йæ фехалæн ма уа.
Алы викийы тæлмæцтæ ивынæн, дæ хорзæхæй, архай [//translatewiki.net/ translatewiki.net-æй], кæцы у MediaWiki-йы локализацигæнæн проект.',
'editinginterface' => "'''Сындæг:''' Ды ивыс ахæм фарс, кæм ис софты интерфейсы текст.
Ацы фарсы фæивд фæзындзæн ацы викийы алы архайæджы интерфейсыл дæр.
Алы викийы тæлмæцтæ ивынæн, дæ хорзæхæй, архай [//translatewiki.net/ translatewiki.net-æй], кæцы у MediaWiki-йы локализацигæнæн проект.",
'sqlhidden' => '(SQL домæн æмбæхст у)',
'cascadeprotected' => 'Ацы фарс ивынæй æхгæд у уымæн æмæ у æфтыд бындæр цы "каскадон" хъахъхъонд {{PLURAL:$1|фарс ис, уырдæм|фæрстæ сты, уыдонмæ}}:
$2',
'namespaceprotected' => "Дæуæн нæй бар ивын фæрстæ '''$1''' номдоны.",
'customcssprotected' => 'Дæуæн нæй бар ивын ацы CSS фарс, уымæн æмæ уым ис æндæр архайæджы персоналон æвæрдтытæ.',
'customjsprotected' => 'Дæуæн нæй бар ивын ацы JavaScript фарс, уымæн æмæ уым ис æндæр архайæджы персоналон æвæрдтытæ.',
'ns-specialprotected' => 'Сæрмагонд фæрстæ ({{ns:special}}) баивæн нæй.',
'titleprotected' => 'Ацы ном уыд æхгæд саразынæй. Сæхгæдта йæ [[User:$1|$1]].
Æхгæныны бындур уыд "\'\'$2\'\'".',
'filereadonlyerror' => 'Нæ уайы фæивын файл "$1" уый тыххæй æмæ репозиторий "$2" кусы æрмæст фæрсыны уавæры.

Радгæс, кæцы сæвæрдта ацы уавæр, ныууагъта ахæм амонæн: "\'\'$3\'\'".',
'invalidtitle-knownnamespace' => '"$2" номдон æмæ "$3" тексты рæдыд сæр',
'invalidtitle-unknownnamespace' => 'Æнæзонгæ номдоны нымæц, "$1" æмæ "$2" тексты рæдыд сæр',
'exception-nologin' => 'Системæмæ æнæхызт',
'exception-nologin-text' => 'Ацы фарс кæнæ ми домынц дæуæй, цæмæй системæмæ хызт уай.',

# Virus scanner
'virus-badscanner' => "Æвзæр æвæрд: Нæзонгæ вирусты скане: ''$1''",
'virus-scanfailed' => 'Скан нæ рауадис (код $1)',
'virus-unknownscanner' => 'æнæзонгæ антивирус:',

# Login and logout pages
'logouttext' => "'''Ныр дæ æддæмæ хызт.'''

Дæ бон у дарддæр архайай {{grammar:genitive|{{SITENAME}}}} æнæномæй, æви та [[Special:UserLogin|фæстæмæ бахизын]] раздæры номæй кæнæ та æндæр номæй.
Дæ сæры дар æмæ иуæй иу фæрстæ гæнæн ис æвдыст цæуой афтæ, цымæ нырмæ дæр нæ рахызтæ. Уый тыххæй дæ браузеры кеш сафтид кæн.",
'welcomecreation' => '== Ӕгас цу, $1! ==
Дæ аккаунт арæзт æрцыдис.
Ма дæ ферох уæт æркæсын дæ [[Special:Preferences|{{grammar:genitive|{{SITENAME}}}} уагæвæрдтæм]].',
'yourname' => 'Фæсномыг:',
'yourpassword' => 'Пароль:',
'yourpasswordagain' => 'Дæ пароль иу хатт ма:',
'remembermypassword' => 'Бахъуыды мæ кæнæд ашы браузеры (максимум $1 {{PLURAL:$1|бонмæ|бонмæ}})',
'securelogin-stick-https' => 'Дарддæр дæр HTTPS-ы уылты, бахизыны фæстæ',
'yourdomainname' => 'Дæ домен:',
'password-change-forbidden' => 'Дæ бон нæу пароль фæивын ацы викийы.',
'externaldberror' => 'Кæнæ аутентификацийы рарддоны рæдыд æрцыдис, кæнæ та дæуæн нæй бар снæуæг кæнын дæ æддаг аккаунт.',
'login' => 'Бахизын',
'nav-login-createaccount' => 'Бахизын / срегистраци кæнын',
'loginprompt' => 'Дæ cookies хъуамæ иу уой цæмæй дæ бон уа бахизын {{grammar:allative|{{SITENAME}}}}.',
'userlogin' => 'Бахизын / регистраци кæнын',
'userloginnocreate' => 'Бахизын',
'logout' => 'Рахизын',
'userlogout' => 'Рахизын',
'notloggedin' => 'Системæйæн дæхи нæ бацамыдтай',
'nologin' => "Регистрацигонд нæма дæ? '''$1'''.",
'nologinlink' => 'Регистраци',
'createaccount' => 'Регистраци кæнын',
'gotaccount' => 'Регистрацигонд дæ? $1.',
'gotaccountlink' => 'Бахизын',
'userlogin-resetlink' => 'Ферох дæ сты дæ бахизæнтæ?',
'createaccountmail' => 'адрисмæ гæсгæ',
'createaccountreason' => 'Бындур:',
'badretype' => 'Дыууæ хатты иу пароль хъуамæ ныффыстаис',
'userexists' => 'Уыцы фæсномыг нæвдæлон у.
Дæ хорзæхæй, равзар æндæр.',
'loginerror' => 'Бахизыны рæдыд',
'createaccounterror' => 'Нæ рауадис аккаунт саразын: $1',
'nocookiesnew' => 'Аккаунт арæзт æрцыдис, фæлæ ды нырмæ нæ дæ хызт мидæмæ. 
{{SITENAME}} архайы cookies-æй цæмæй архайджыты æвзара.
Дæумæ cookies хицæн сты.
Дæ хорзæхæй, баиу цæ кæн æмæ стæй бахиз дæ нæуæг ном æмæ паролимæ.',
'nocookieslogin' => '{{SITENAME}} архайы cookies-æй цæмæй архайджыты æвзара.
Дæумæ cookies хицæн сты.
Дæ хорзæхæй, баиу цæ кæн æмæ нæуæгæй бафæлвар.',
'nocookiesfornew' => 'Архайæджы аккаунт нæ сарæзт ис, уымæн æмæ йын мах нæ базыдтам йæ равзæрæн.
Бацархай, цæмæй cookies иу уой, анæуæг кæн ацы фарс æмæ ногæй бафæлвар.',
'noname' => 'Раст фæсномыг нæ ныффыстай.',
'loginsuccesstitle' => 'Бахызтæ',
'loginsuccess' => "'''Ныр ды дæ хыст {{grammar:genitive|{{SITENAME}}}} куыд \"\$1\".'''",
'nosuchuser' => 'Нæй ахæм архайæг "$1" номимæ.
Архайджыты нæмттæ хатынц дамгъæты регистр.
Сбæрæг æй кæн, раст ныффыстай ном, æви [[Special:UserLogin/signup|бакæн ног аккаунт]].',
'nosuchusershort' => 'Нæй архайæг "$1" фæсномыгимæ.
Фен, фæсномыг раст ныффыстай, æви нæ.',
'nouserspecified' => 'Ды хъуамæ зæгъай дæ фæсномыг.',
'login-userblocked' => 'Ацы архайæг хъодыгонд у. Нæй гæнæн бахизын.',
'wrongpassword' => 'Цы пароль ныффыстай, уый раст нæу.
Лæмбынæгæй ныффысс дæ пароль',
'wrongpasswordempty' => 'Пароль афтид у.
Бафæлвар ногæй.',
'passwordtooshort' => 'Паролтæ хъуамæ уой уæддæр {{PLURAL:$1|1 дамгъæйы|$1 дамгъæйы}} дæргъæн.',
'password-name-match' => 'Дæ парол ма хъуамæ уа дæ фæсномыгы хуызæн.',
'password-login-forbidden' => 'Ацы фæсномыг æмæ паролæй нæй гæнæн архайын.',
'mailmypassword' => 'Рарвит мæм ног пароль',
'passwordremindertitle' => 'Ног рæстæгмæ пароль {{grammar:dative|{{SITENAME}}}}',
'passwordremindertext' => 'Чидæр (æвæццæгæн ды, $1 IP адрисæй) æрдомтта ног парол {{grammar:dative|{{SITENAME}}}} ($4). "$2" архайæгæн арæзт æрцыд рæстæгмæ парол. Парол у "$3". Кæд уый ды уыдтæ, уæд дын ныр хъæудзæн бахизын æмæ равзарын ног парол.
Дæ рæстæгмæ парол ма кусдзæнис {{PLURAL:$5|иу бон|$5 боны}}.

Кæд æндæр чидæр æрдомтта ай, кæнæ ды рымысыдтæ дæ парол æмæ дæ нал фæнды фæивын æй, уæд дæ бон у мацæмæ дарай ацы фыстæг æмæ дарддæр дæ зæронд паролæй архайай.',
'noemail' => 'Архайæг $1 йæ электрон посты адрис нæ ныууагъта.',
'noemailcreate' => 'Дæуæн хъæуы раст e-mail адрис',
'passwordsent' => 'Ног парол æрвыст æрцыд e-mail адрисмæ, кæцы уыд регистацигонд {{grammar:dative|$1}}.',
'blocked-mailpassword' => 'Дæ IP адрис уыд хъодыгонд ивынæй æмæ цæмæй мачи разнаггад кæна, уы тыххæй дзы нæй гæнæн парол раивын дæр.',
'eauthentsent' => 'Бæлвырдгæнæн фыстæг æрвыст æрцыд амынд e-mail адрисмæ.
Цæмæй дæм æндæр фыстæджытæ дæр цæуой, уый тыххæй дын хъæудзæн сæххæст кæнын фыстæджы фыст амындтытæ. Уый фæстæ сбæлвыд уыдзæн аккаунт æцæгдæр дæу кæй у, уый.',
'throttled-mailpassword' => 'Паролрымысæн нырид æрвыст æрцыд фæстаг {{PLURAL:$1|сахаты|$1 сахаты}} мидæг.
Цæмæй мачи разнагкад кæна, уый тыххæй æрмæст иу хатт ис гæнæн райсын рымысæнфыстæг {{PLURAL:$1|сахаты|$1 сахаты}} мидæг.',
'mailerror' => 'Фыстæг арвитыны рæдыд: $1',
'acct_creation_throttle_hit' => 'Ацы викимæ дæ IP адрисæй чи æрбацыдис, уыдон фæстаг боны мидæг бакодтой {{PLURAL:$1|1 аккаунт|$1 аккаунты}}. Уымæй фылдæр нæй гæнæн ахæм рæстæгмæ.
Уый тыххæй, ацы тæккæ, ацы IP адрисæй уазджытæн сæ бон нæу бакæнын аккаунттæ.',
'emailauthenticated' => 'Дæ e-mail уыд бæлвырдгонд $2 $3 сахатыл.',
'emailnotauthenticated' => 'Дæ e-mail адрис нырмæ нæу бæлвырдгонд.
Иу e-mail дæр дæм нæ уыдзæн æрвыст ацы функцитæй.',
'noemailprefs' => 'Бафысс e-mail адрис дæ уагæвæрдты, цæмæй ацы функцитæ кусой.',
'emailconfirmlink' => 'Дæ электронон посты адрис сфидар кæн',
'invalidemailaddress' => 'E-mail нæй гæнæн райсын, уымæн æмæ йæ формат раст нæу.
Бафысс раст форматы адрис кæнæ та йæ сафтид кæн.',
'cannotchangeemail' => 'Аккаунты e-mail нæй гæнæн ивын ацы викийы.',
'emaildisabled' => 'Ацы сайтæн йæ бон нæу æрвитын e-mail.',
'accountcreated' => 'Аккаунт конд æрцыд',
'accountcreatedtext' => '$1 архайæджы аккаунт конд æрцыд.',
'createaccount-title' => '{{grammar:genitive|{{SITENAME}}}} аккаунт бакæнын',
'createaccount-text' => '{{grammar:genitive|{{SITENAME}}}} ($4) чидæр бакодта аккаунт дæ e-mail адрисæн æмæ йæ схуыдта "$2", "$3" паролимæ.
Ныр ды хъуамæ бахизай системæмæ æмæ фæивай дæ парол.

Дæ бон у мацæмæ дарай ацы фыстæг, кæд уыцы аккаунт рæдыдæй сарæзтис.',
'usernamehasherror' => 'Фæсномыгы ма хъуамæ уа хызæг нысан',
'login-throttled' => 'Фæстаг рæстæджы ды бахизын æгæр бирæ фæлвардтай.
Дæ хорзæхæй, фæлæуу-иу иннæ фæлварæны размæ.',
'login-abort-generic' => 'Нæ дын бантыст бахизын. Урæд.',
'loginlanguagelabel' => 'Æвзаг: $1',
'suspicious-userlogout' => 'Дæ рахизыны домæн нæ сæххæст ис, уымæн æмæ хæлд браузерæй кæнæ кешгæнæг проксийæ æрвысты хуызæн у.',

# E-mail sending
'php-mail-error-unknown' => 'Нæбæрæг рæдыд PHP-йы mail() функцийы.',
'user-mail-no-addy' => 'Е-mail æрвыста æнæ e-mail адрисæй.',

# Change password dialog
'resetpass' => 'Пароль фæивын',
'resetpass_announce' => 'Ды бахызтæ e-mail-æй æрвыст рæстæгмæ паролæй.
Цæмæй кæронмæ бахизай системæмæ, уый тыххæй ам хъуамæ сæвæрай ног пароль.',
'resetpass_text' => '<!-- Бахæсс дæ текст ам -->',
'resetpass_header' => 'Аккаунты пароль ивын',
'oldpassword' => 'Раздæры пароль:',
'newpassword' => 'Нæуæг пароль:',
'retypenew' => 'Ног пароль ногæй бафысс:',
'resetpass_submit' => 'Пароль сæвæрын æмæ системæмæ бахизын',
'resetpass_success' => 'Дæ пароль ивд æрцыд!
Ныр ды хизыс системæмæ...',
'resetpass_forbidden' => 'Пароль баивæн нæй',
'resetpass-no-info' => 'Ды хъуамæ системæмæ хызт уай, цæмæй ацы фарсмæ комкоммæ бавналай.',
'resetpass-submit-loggedin' => 'Пароль фæивын',
'resetpass-submit-cancel' => 'Ныууадзын',
'resetpass-wrong-oldpass' => 'Рæстæгмæ кæнæ нырыккон пароль нæ бæззы.
Гæнæн ис ды нырид фæивтай дæ пароль кæнæ та ног рæстæгмæ пароль æрдомдтай.',
'resetpass-temp-password' => 'Рæстæгмæ пароль:',

# Special:PasswordReset
'passwordreset' => 'Пароль раппарæн',
'passwordreset-text' => 'Байдзаг кæн ацы формæ, цæмæй райсай дæ аккаунты бахизæнтæ рымысæн e-mail.',
'passwordreset-legend' => 'Пароль раппарын',
'passwordreset-disabled' => 'Парол фæлварын ацы викийы хицæн у.',
'passwordreset-pretext' => '{{PLURAL:$1||Дæлдæр цы рардты хæйттæ ис, уыдонæй иу бафысс}}',
'passwordreset-username' => 'Фæсномыг:',
'passwordreset-domain' => 'Домен:',
'passwordreset-capture' => 'Цы e-mail рауадис, уый æвдыст æрцæуæт?',
'passwordreset-capture-help' => 'Кæд сæвæрай ацы нысан, уæд дын e-mail (рæстæгмæ паролимæ) æвдыст æрцæудзæн. Архайæгмæ дæр æрвыст æрцæудзæн.',
'passwordreset-email' => 'Электрон посты адрис:',
'passwordreset-emailtitle' => '{{grammar:genitive|{{SITENAME}}}} акканты тыххæй',
'passwordreset-emailtext-ip' => 'Чидæр (уæццæгæн ды, $1 IP адрисæй) æрдомдта дæ {{grammar:genitive|{{SITENAME}}}} ($4) аккаунты тыххæй детальтæ. Ацы архайæджы {{PLURAL:$3|аккаунт баст у|аккаунттæ баст сты}} ацы e-mail адрисимæ:

$2

{{PLURAL:$3|Ацы рæстæгмæ пароль|Ацы рæстæгмæ паролтæ}} кусдзысты {{PLURAL:$5|иу бон|$5 боны}}.
Ныр ды хъуамæ бахизай системæмæ æмæ равзарай ног пароль. Кæд ай æндæр чидæр æрдомдта, кæнæ кæд дæ пароль æрхъуыды кодтай æмæ дæ нал фæды фæивай йæ, уæд дæ бон у мацæмæ дарай ацы фыстæг æмæ дарддæр архайай дæ зæронд паролæй.',
'passwordreset-emailtext-user' => '{{grammar:genitive|{{SITENAME}}}} архайæг $1 æрдомдта дæ {{grammar:genitive|{{SITENAME}}}} ($4) аккаунты тыххæй детальтæ. Ацы архайæджы {{PLURAL:$3|аккаунт баст у|аккаунттæ баст сты}} ацы e-mail адрисимæ:

$2

{{PLURAL:$3|Ацы рæстæгмæ пароль|Ацы рæстæгмæ паролтæ}} кусдзысты {{PLURAL:$5|иу бон|$5 боны}}.
Ныр ды хъуамæ бахизай системæмæ æмæ равзарай ног пароль. Кæд ай æндæр чидæр æрдомдта, кæнæ кæд дæ пароль æрхъуыды кодтай æмæ дæ нал фæды фæивай йæ, уæд дæ бон у мацæмæ дарай ацы фыстæг æмæ дарддæр архайай дæ зæронд паролæй.',
'passwordreset-emailelement' => 'Фæсномыг: $1
Рæстæгмæ пароль: $2',
'passwordreset-emailsent' => 'Æрхъуыдыгæнæн e-mail æрвыст æрцыд',
'passwordreset-emailsent-capture' => 'Æрхъуыдыгæнæн e-mail æрвыст æрцыд æмæ бындæр æвдыст у.',
'passwordreset-emailerror-capture' => 'Æрхъуыдыгæнæн e-mail арæзт æрцыд æмæ бындæр æвдыст у. Фæлæ йæ арвитын нæ бантыстис: $1',

# Special:ChangeEmail
'changeemail' => 'E-mail адрис фæивын',
'changeemail-header' => 'Аккаунты e-mail адрис фæивын',
'changeemail-text' => 'Байдзаг кæн ацы формæ, цæмæй дæ e-mail адрис фæивай. Уый тыххæй дын хъæудзæн дæ пароль бафыссын.',
'changeemail-no-info' => 'Ды хъуамæ системæмæ хызт уай, цæмæй ацы фарсмæ комкоммæ бавналай.',
'changeemail-oldemail' => 'Нырыккон e-mail адрис:',
'changeemail-newemail' => 'Ног e-mail адрис:',
'changeemail-none' => '(нæй)',
'changeemail-submit' => 'E-mail фæивын',
'changeemail-cancel' => 'Ныууадзын',

# Edit page toolbar
'bold_sample' => 'Бæзджын текст',
'bold_tip' => 'Бæзджын текст',
'italic_sample' => 'Курсив',
'italic_tip' => 'Курсив',
'link_sample' => 'Æрвитæны текст',
'link_tip' => 'Мидæггаг æрвитæн',
'extlink_sample' => 'http://www.example.com æрвитæны текст',
'extlink_tip' => 'Æддаг æрвитæн (префикс http:// ма рох кæн)',
'headline_sample' => 'Сæргонд текст',
'headline_tip' => 'Бæрц 2 сæргонд',
'nowiki_sample' => 'Батысс нæформатгонд текст ардæм',
'nowiki_tip' => 'Ницæмæ дарын вики формат',
'image_tip' => 'Æфтыд файл',
'media_tip' => 'Файлмæ æрвитæн',
'sig_tip' => 'Дæ къухæрфыст, рæстæгимæ',
'hr_tip' => 'Горизонталон хахх (арæх дзы ма пайда кæн)',

# Edit pages
'summary' => 'Бындур:',
'subject' => 'Темæ/сæр:',
'minoredit' => 'Ай чысыл ивд у.',
'watchthis' => 'Цæст дарын ацы фарсмæ',
'savearticle' => 'Бавæрын',
'preview' => 'Разæркаст',
'showpreview' => 'Фенын',
'showlivepreview' => 'Тагъд разæркаст',
'showdiff' => 'Иртасын',
'anoneditwarning' => "'''Сындæггай:''' Ды системæйы нæ дæ.
Дæ IP адрес фыст æрцæудзæнис фарсы историйы.",
'anonpreviewwarning' => "''Ды системæмæ хызт нæ дæ. Бавæрд дын дæ IP адрис ныффысдзæн фарсы историйы.''",
'missingsummary' => "'''Æрхъуыдыкæнинаг:''' Ды не рхастай ивды афыст.
Кæд ногæй равзарай \"{{int:savearticle}}\", уæд ивд æнæ афыстæй бавæрд уыдзæнис.",
'missingcommenttext' => 'Дæ хорзæхæй, дæлдæр комментари бафысс.',
'missingcommentheader' => "'''Æрхъуыдыкæнинаг:''' Ды ацы комментарийæн не рхастай темæ/сæр.
Кæд ногæй равзарай \"{{int:savearticle}}\", уæд ивд æнæ уымæй бавæрд уыдзæнис.",
'summary-preview' => 'Ивд афыст уыдзæн:',
'subject-preview' => 'Темæ/сæр:',
'blockedtitle' => 'Архайæг хъодыгонд æрцыд',
'blockedtext' => "'''Дæ фæсномыг кæнæ дæ IP адрис блокгонд æрцыд.'''

Блок скодта $1.
Йæ аххосаг у ''$2''.

* Блокы райдиан: $8
* Блокы кæрон: $6
* Блокы мысан: $7

Дæ бон у бадзурай {{grammar:allative|$1}} кæнæ [[{{MediaWiki:Grouppage-sysop}}|радгæсмæ]], цæмæй блокы тыххæй аныхас кæнай.
Дæ бон нæу электронон фыстæг æм арвитай, цалынмæ дæ [[Special:Preferences|аккаунты уагæвæрдты]] раст e-mail нæ бацамонай æмæ цалынмæ уымæй дæр нæ дæ блокгонд.
Дæ нырыккон IP адрис у $3, æмæ блокы бæрæггæнæн у #$5.
Дæ хорзæхæй, уæлдæр цы детальтæ ис, уыдон иу дæ домæнмæ бафтау.",
'autoblockedtext' => "'''Дæ IP адрис йæхæдæг ныблок ис, уымæн æмæ ууылты архайдта æндæр архайæг, кæй ныблок кодта $1.'''
Йæ аххосаг у:

:''$2''

* Блокы райдиан: $8
* Блокы кæрон: $6
* Блокы мысан: $7

Дæ бон у бадзурай {{grammar:allative|$1}} кæнæ æндæр [[{{MediaWiki:Grouppage-sysop}}|радгæсмæ]], цæмæй блокы тыххæй аныхас кæнай.

Дæ бон нæу электронон фыстæг æм арвитай, цалынмæ дæ [[Special:Preferences|аккаунты уагæвæрдты]] раст e-mail нæ бацамонай æмæ цалынмæ уымæй дæр нæ дæ блокгонд.

Дæ нырыккон IP адрис у $3, æмæ блокы бæрæггæнæн у #$5.
Дæ хорзæхæй, уæлдæр цы детальтæ ис, уыдон иу дæ домæнмæ бафтау.",
'blockednoreason' => 'аххос амынд не ’рцыд',
'whitelistedittext' => 'Дæуæн хъæуы $1, цæмæй фæртæ ивай.',
'confirmedittext' => 'Фæрстæ ивыны размæ ды хъуамæ сбæлвырд кæнай дæ e-mail адрис.
Дæ хæрзæхæй, сæвæр æмæ сбæлвырд кæн дæ e-mail адрис дæ [[Special:Preferences|уагæвæрдты]].',
'nosuchsectiontitle' => 'Хай нæ уард кæны',
'nosuchsectiontext' => 'Ды фæлвардтай ахæм фарс ивын, кæцы нæй.
Гæнæн ис, цалынмæ ды фарс кастæ, уый хаст кæнæ хафт æрцыдис.',
'loginreqtitle' => 'Хъæуы бахизын',
'loginreqlink' => 'бахизын',
'loginreqpagetext' => 'Дæуæн хъæуы $1 цæмæй æндæр фæрстæ кæсай.',
'accmailtitle' => 'Пароль æрвыст æрцыд.',
'accmailtext' => "[[User talk:$1|{{grammar:dative|$1}}]] халæй ист пароль æрвыст æрцыд $2-мæ.

Ацы ног аккаунты пароль гæнæн ис фæивын ''[[Special:ChangePassword|пароль ивæн варсыл]]'' бахизыны фæстæ.",
'newarticle' => '(Ног)',
'newarticletext' => 'Ды ныххæцыдтæ ахæм æрвитæныл, кæй фарс нырмæ нæй.
Фарс бакæнынæн байдай фыссын дæлдæр цы къæртт ис, уым (кæс [[{{MediaWiki:Helppage}}|æххуысы фарс]] фылдæр базонынæн).',
'anontalkpagetext' => "----''Ай у æнæном архайæджы ныхасы фарс. Ацы архайæг нырмæ нæ срегистраци кодта, кæнæ та йæ аккаунтæй нæ архайы.
Уый тыххæй мах пайда кæнæм йæ IP адрисæй, цæмæй-иу æй бæрæг кæнæм.
Ахæм IP адристæй гæнæн ис архайой цалдæр архайæджы.
Кæд ды æнæном архайæг дæ æмæ дæм цыдæр зæгъæлы фыстæджытæ цæуы, уæд, дæ хорзæхæй, [[Special:UserLogin/signup|бакæн аккаунт]] кæнæ [[Special:UserLogin|бахиз системæмæ]], цæмæй дæ мауал хæццæ кæной æндæр æнæном архайджытимæ.''",
'noarticletext' => 'Ацы фарсы нырмæ текст нæй.
Дæ бон у [[Special:Search/{{PAGENAME}}|бацагурын ацы фарсы ном]] æндæр фæрсты,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} агурын йæ кой логты],
кæнæ [{{fullurl:{{FULLPAGENAME}}|action=edit}} скæнын ацы фарс]</span>.',
'noarticletext-nopermission' => 'Ацы фарсы нырмæ текст нæй.
Дæ бон у [[Special:Search/{{PAGENAME}}|бацагурын ацы фарсы ном]] æндæр фæрсты, кæнæ <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} агурын йæ кой логты]</span>, фæлæ дын йæ саразыны бар нæй.',
'missing-revision' => '"{{grammar:genitive|{{PAGENAME}}}}" фарсæн $1-æм фæлтæр нæй.

Ай арæх æрцæуы, исчи хафт фарсы зæронд историйы æрвитæны фæдыл куы ацæуы.
Фылдæр гæнæн ис базонын [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} хафыны логы].',
'userpage-userdoesnotexist-view' => 'Архайæджы аккаунт "$1" регистрацигонд нæу.',
'updated' => '(Ноггонд)',
'note' => "'''Фиппаинаг:'''",
'previewnote' => "'''Зон æй, æмæ ай у æрмæстдæр разбакаст.'''
Дæ ивдтытæ нырмæ æвæрд не рцыдысты!",
'continue-editing' => 'Ивыны бынатмæ ацæуын',
'editing' => 'Ивд цæуы $1',
'creating' => 'Конд цæуы $1',
'editingsection' => 'Ивд цæуы $1 (хай)',
'editingcomment' => 'Ивд цæуы $1 (ног хай)',
'editconflict' => 'Ивыны конфликт: $1',
'yourtext' => 'Дæ текст',
'yourdiff' => 'Хицæндзинæдтæ',
'templatesused' => 'Ацы фарсы ис {{PLURAL:$1|хуызæг|хуызæджы}}:',
'template-protected' => '(æхгæд)',
'template-semiprotected' => '(æрдæг-æхгæд)',
'hiddencategories' => 'Ацы фарс у {{PLURAL:$1|1 æмбæхст категорийы|$1 æмбæхст категориты}} уæнг:',
'permissionserrors' => 'Бавналыны рæдыдтытæ',
'permissionserrorstext-withaction' => 'Нæй дын бар $2 {{PLURAL:$1|ай|адон}} тыххæй:',
'recreate-moveddeleted-warn' => "'''Сындæг: Ды нæуæгæй кæныс фарс, кæцы раздæр уыдис хафт.'''

Дзæбæх ахъуыды кæн, æцæг нæуæгæй кæнинаг у, æви нæ.
Хафын æмæ номивыны логтæ бындæр сты фыст.",
'moveddeleted-notice' => 'Ацы фарс хафт уыдис.
Уый тыххæй бындæр ис хафын æмæ номивыны логтæй фыст.',
'edit-conflict' => 'Ивдтыты конфликт.',
'edit-already-exists' => 'Ног фарс скæнæн нæй. Ахæм фарс ис.',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Сындæг: ''' Хуызæджы бавæрд бæрц æгæр стыр у.
Кæцыдæр хуызæгтæ нæ бавæд уыдзысты.",
'post-expand-template-inclusion-category' => 'Фæрстæ, кæм хуызæджы бавæрд бæрц æгæр бирæ у',
'post-expand-template-argument-warning' => "'''Сындæг:''' Ацы фарсы ис уæддæр иу хуызæджы аргумент, кæй райтынг у æгæр стыр.
Уыцы аргументтæ уагъд æрцыдысты.",
'post-expand-template-argument-category' => 'Фæрстæ, кæдоны ис хуызæджы уагъд аргумент',

# History pages
'viewpagelogs' => 'Ацы фарсæн йæ логтæ равдисын',
'nohistory' => 'Ацы фарсæн иввдзинæдты истори нæй.',
'currentrev' => 'Нырыккон фæлтæр',
'currentrev-asof' => 'Нырыккон фæлтæр $1',
'revisionasof' => 'Фæлтæр $1',
'revision-info' => 'Фæлтæр $1; $2',
'previousrevision' => '← Зæронддæр фæлтæр',
'nextrevision' => 'Ногдæр фæлтæр →',
'currentrevisionlink' => 'Нырыккон фæлтæр',
'cur' => 'ныр.',
'next' => 'иннæ',
'last' => 'раз.',
'page_first' => 'фыццаг',
'page_last' => 'фæстаг',
'histlegend' => "Æвзарынæн: банысан кæн фæлтæрты радиобокстæ цæмæй цæ иртæсай æмæ стæй ныххæц enter кæнæ бынæй цы ныххæцæн ис, ууыл.<br />
Легендæ: '''({{int:cur}})''' = иртæсæн фæстаг фæлтæримæ, '''({{int:last}})''' = иртæсæн разфæлтæримæ, '''{{int:minoreditletter}}''' = чысыл ивд.",
'history-fieldset-title' => 'Истори фенын',
'history-show-deleted' => 'Æрмæстдæр хафтытæ',
'histfirst' => 'Фыццаг',
'histlast' => 'Фæстаг',
'historysize' => '({{PLURAL:$1|1 байт|$1 байты}})',
'historyempty' => '(афтид)',

# Revision feed
'history-feed-title' => 'Ивддзинæдты истори',
'history-feed-item-nocomment' => '$1 $2',

# Revision deletion
'rev-deleted-comment' => '(ивды афыст хафт у)',
'rev-deleted-user' => '(фæсномыг хафт у)',
'rev-deleted-event' => '(фыст хафт у)',
'rev-delundel' => 'равдисын/айсын',
'rev-showdeleted' => 'равдисын',
'revisiondelete' => 'Фæлтæртæ схафын/рацаразын',
'revdelete-show-file-submit' => 'О',
'revdelete-radio-same' => '(ма баив)',
'revdelete-radio-set' => 'О',
'revdelete-radio-unset' => 'Нæ',
'revdel-restore' => 'ивын зынæн',
'revdel-restore-deleted' => 'хафт ивдтытæ',
'revdel-restore-visible' => 'зынгæ ивдтытæ',
'pagehist' => 'Фарсы истори',
'revdelete-reason-dropdown' => '*Хафыны арæхдæр бындуртæ
** Сфæлдисæгы барты æфхæрд
** Кæйдæр тыххæй æнæмбæлгæ информаци кæнæ хъуыды
** Æнæмбæлгæ фæсномыг
** Гæнæн ис цъыфкалд у',
'revdelete-reasonotherlist' => 'Æндæр аххос',
'revdelete-offender' => 'Фæлтæры автор:',

# History merging
'mergehistory-reason' => 'Аххос:',

# Merge log
'revertmerge' => 'Ахицæн кæнын',

# Diffs
'history-title' => '"$1", йæ фæлтæрты истори',
'lineno' => 'Рæнхъ $1:',
'compareselectedversions' => 'Абарын æвзæрст фæлтæртæ',
'editundo' => 'раивын',
'diff-multi' => '{{PLURAL:$2|1 архайæджы|$2 архайæджы}} {{PLURAL:$1|1 æхсæйнаг фæлтæр æвдыст нæу|$1 æхсæйнаг фæлтæры æвдыст не сты}}',

# Search results
'searchresults' => 'Агуырды фæстиуджытæ',
'searchresults-title' => 'Агуырды фæстиуæг: «$1»',
'titlematches' => 'Уацы ном æмбæлы',
'notitlematches' => 'Никæцы фарсы ном æмбæлы',
'textmatches' => 'Уацты æмцаутæ',
'prevn' => 'рæздæры {{PLURAL:$1|$1}}',
'nextn' => 'иннæ {{PLURAL:$1|$1}}',
'prevn-title' => 'Раздæр $1 {{PLURAL:$1|фæстиуæг|фæстиуæджы}}',
'nextn-title' => 'Иннæ $1 {{PLURAL:$1|фæстиуæг|фæстиуæджы}}',
'shown-title' => 'Æвдисын $1 {{PLURAL:$1|фæстиуæг|фæстиуæджы}} иу фарсыл',
'viewprevnext' => 'Кæсын ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists' => "'''Ацы викийы ис фарс \"[[:\$1]]\" номимæ.'''",
'searchmenu-new' => "'''Сараз фарс \"[[:\$1]]\" ацы викийы!'''",
'searchhelp-url' => 'Help:Мидис',
'searchprofile-articles' => 'Мидисы фæрстæ',
'searchprofile-project' => 'Æххуыс æмæ Проекты фæрстæ',
'searchprofile-images' => 'Мультимеди',
'searchprofile-everything' => 'Алцыдæр',
'searchprofile-advanced' => 'Лæмбынæг',
'searchprofile-articles-tooltip' => 'Агурын ам: $1',
'searchprofile-project-tooltip' => 'Агурын ам: $1',
'searchprofile-images-tooltip' => 'Агурын файлтæ',
'searchprofile-everything-tooltip' => 'Агурын алыран дæр (тæрхоны фæрсты дæр)',
'searchprofile-advanced-tooltip' => 'Агурын равзаргæ номдæтты',
'search-result-size' => '$1 ({{PLURAL:$2|$2 дзырд|$2 дзырды}})',
'search-result-category-size' => '{{PLURAL:$1|1 уæнг|$1 уæнгы}} ({{PLURAL:$2|1 дæлкатегори|$2 дæлкатегорийы}}, {{PLURAL:$3|1 файл|$3 файлы}})',
'search-redirect' => '({{grammar:ablative|$1}} æрвыст)',
'search-section' => '(хай $1)',
'search-suggest' => 'Кæд мыййаг агурыс: $1',
'search-interwiki-caption' => 'Æфсымæрон проекттæ',
'search-interwiki-default' => '{{grammar:genitive|$1}} фæстиуджытæ:',
'search-interwiki-more' => '(нодзы)',
'search-relatedarticle' => 'Хæстæг',
'searchrelated' => 'хæстæг',
'searchall' => 'иууылдæр',
'showingresultsheader' => "{{PLURAL:$5|Фæстиуæг '''$1''' '''$3'''-йæ|Фæстиуджытæ '''$1 - $2''' '''$3'''-йæ}} '''{{grammar:dative|$4}}'''",
'search-nonefound' => 'Ницы разындис домæнæн',
'powersearch' => 'Сæрмагонд агуырд',
'powersearch-legend' => 'Сæрмагонд агуырд',
'powersearch-redir' => 'Рарвыстытæ æвдисын',
'powersearch-field' => 'Агуырд',
'powersearch-toggleall' => 'Иууылдæр',

# Quickbar
'qbsettings' => 'Навигацион таг',
'qbsettings-none' => 'Ма равдис',
'qbsettings-fixedleft' => 'Галиуырдыгæй',
'qbsettings-fixedright' => 'Рахизырдыгæй',
'qbsettings-floatingleft' => 'Рахизырдыгæй ленккæнгæ',

# Preferences page
'preferences' => 'Уагæвæрдтæ',
'mypreferences' => 'Уагæвæрдтæ',
'prefs-edits' => 'Ивдтыты нымæц:',
'prefsnologin' => 'Системæйæн дæхи нæ бацамыдтай',
'changepassword' => 'Пароль ивæн',
'prefs-skin' => 'Цъар',
'skin-preview' => 'Разæркаст',
'prefs-beta' => 'Бета уагæвæрдтæ',
'prefs-datetime' => 'Датæ æмæ рæстæг',
'prefs-labs' => 'Лабораторон уагæвæрдтæ',
'prefs-personal' => 'Архайæджы профил',
'prefs-rc' => 'Фæстаг ивдтытæ',
'prefs-watchlist' => 'Цæстдард',
'prefs-watchlist-days' => 'Бонты бæрц æвдисынæн:',
'prefs-misc' => 'Æндæртæ',
'prefs-rendering' => 'Фæлгонц',
'saveprefs' => 'Бавæрын',
'prefs-editing' => 'Ивын',
'rows' => 'Рæнхъытæ:',
'searchresultshead' => 'Агуырд',
'stub-threshold-disabled' => 'Хицæн',
'timezonelegend' => 'Сахаты таг:',
'localtime' => 'Бынæттон рæстæг:',
'timezoneuseserverdefault' => 'Архайын серверы æвæрдæй ($1)',
'timezoneuseoffset' => 'Æндæр (бацамон гæпп)',
'timezoneoffset' => 'Гæпп¹:',
'servertime' => 'Серверы рæстæг:',
'guesstimezone' => 'Райсын браузерæй',
'timezoneregion-africa' => 'Африкæ',
'timezoneregion-america' => 'Америкæ',
'timezoneregion-antarctica' => 'Антарктикæ',
'timezoneregion-arctic' => 'Арктикæ',
'timezoneregion-asia' => 'Ази',
'timezoneregion-atlantic' => 'Атлантикон Фурд',
'timezoneregion-australia' => 'Австрали',
'timezoneregion-europe' => 'Европæ',
'timezoneregion-indian' => 'Индийы фурд',
'timezoneregion-pacific' => 'Сабыр Фурд',
'prefs-searchoptions' => 'Агурын',
'prefs-namespaces' => 'Номдæттæ',
'prefs-files' => 'Файлтæ',
'prefs-custom-css' => 'Хиæвæрд CSS',
'prefs-custom-js' => 'Хиæвæрд JavaScript',
'youremail' => 'E-mail:',
'username' => 'Фæсномыг:',
'uid' => 'Архайæджы ID:',
'yourrealname' => 'Æцæг ном:',
'yourlanguage' => 'Техникон фыстыты æвзаг:',
'yourvariant' => 'Мидисы æвзаджы вариант:',
'yournick' => 'Ног къухæрфыст:',
'badsiglength' => 'Æгæр даргъ къухæрфыст, хъуамæ {{PLURAL:$1|дамгъæйæ|дамгъæйæ}} къаддæр уа.',
'yourgender' => 'Æрд:',
'gender-male' => 'нæлгоймаг',
'gender-female' => 'сылгоймаг',
'email' => 'E-mail',
'prefs-help-email' => 'E-mail адрес фæндонæй у, фæлæ дæ дæ пароль куы ферох уа, уæд хъæуджæнис нæуæг пароль æвæрынæн.',
'prefs-help-email-others' => 'Дæ бон ма у равзарын цæмæй дæм иннæтæ бадзурой e-mail-æй, кæцымæ уыдзæн æрвитæн дæ фарс æмæ дæ ныхасы фарсыл.
Дæ e-mail адрис афтæмæй нæ рабæрæг уыдзæнис.',
'prefs-help-email-required' => 'Электронон посты адрис хъæуы.',
'prefs-advancedediting' => 'Фылдæр фадæттæ',
'prefs-advancedrc' => 'Фылдæр фадæттæ',
'prefs-advancedrendering' => 'Фылдæр фадæттæ',
'prefs-advancedsearchoptions' => 'Фылдæр фадæттæ',
'prefs-advancedwatchlist' => 'Фылдæр фадæттæ',
'prefs-displayrc' => 'Æвдисыны фадæттæ',
'prefs-displaysearchoptions' => 'Æвдисыны фадæттæ',
'prefs-displaywatchlist' => 'Æвдисыны фадæттæ',
'prefs-diffs' => 'Иртасæнтæ',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'E-mail раст зыны',
'email-address-validity-invalid' => 'Раст e-mail бацамон',

# User rights
'userrights' => 'Архайæджы барты армдарæн',
'userrights-lookup-user' => 'Архайæджы къордтæ ивын',
'userrights-user-editname' => 'Фæсномыг бацамон:',
'editusergroup' => 'Архайæджы къордтæ фæивын',
'userrights-reason' => 'Бындур:',
'userrights-nodatabase' => 'Рарддон $1 нæй кæнæ бынæттон нæу.',

# Groups
'group' => 'Къорд:',
'group-user' => 'Архайджытæ',
'group-bot' => 'Роботтæ',
'group-sysop' => 'Админтæ',
'group-bureaucrat' => 'Бюрократтæ',
'group-all' => '(æппæт)',

'group-user-member' => '{{GENDER:$1|архайæг}}',
'group-autoconfirmed-member' => '{{GENDER:$1|хæдсгарст архайæг}}',
'group-bot-member' => '{{GENDER:$1|бот}}',
'group-sysop-member' => '{{GENDER:$1|администратор}}',
'group-bureaucrat-member' => '{{GENDER:$1|бюрократ}}',
'group-suppress-member' => '{{GENDER:$1|радгæс}}',

'grouppage-user' => '{{ns:project}}:Архайджытæ',
'grouppage-autoconfirmed' => '{{ns:project}}:Хæдсгарст архайджытæ',
'grouppage-bot' => '{{ns:project}}:Роботтæ',
'grouppage-sysop' => '{{ns:project}}:Администратортæ',
'grouppage-bureaucrat' => '{{ns:project}}:Бюрократтæ',
'grouppage-suppress' => '{{ns:project}}:Радгæстæ',

# Rights
'right-read' => 'Фæрстæ кæсын',
'right-edit' => 'Фæрстæ ивын',
'right-move' => 'Фæрсты нæмттæ ивын',
'right-move-subpages' => 'фæрсты æмæ сæ дæлфæрсты нæмттæ ивын',
'right-movefile' => 'файлты нæмттæ ивын',
'right-upload' => 'Файлтæ æвгæнын',
'right-upload_by_url' => 'Бавгæнын файлтæ интернетæй',
'right-delete' => 'Фæрстæ хафын',
'right-bigdelete' => 'Стыр историимæ фæрстæ хафын',

# User rights log
'rightsnone' => '(нæй)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'ацы фарс кæсын',
'action-edit' => 'ацы фарс ивын',
'action-createpage' => 'фæрстæ кæнын',
'action-createtalk' => 'тæрхоны фæрстæ кæнын',
'action-createaccount' => 'саразын ацы архайæджы аккаунт',
'action-minoredit' => 'ацы ивд чысылæй нысан кæнын',
'action-move' => 'ацы фарсы ном ивын',
'action-movefile' => 'ацы файлы ном ивын',
'action-upload' => 'ацы файл æвгæнын',
'action-delete' => 'ацы фарс схафын',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|ивд|ивды}}',
'recentchanges' => 'Фæстаг ивдтытæ',
'recentchanges-legend' => 'Фæстаг ивдтыты фадæттæ',
'recentchanges-summary' => 'Ацы фарсыл викийы фæстаг ивдтытæ фенæн ис.',
'recentchanges-feed-description' => 'Хъус дарын викийы фæстаг ивдтытæм ацы лæсæны уылты.',
'recentchanges-label-newpage' => 'Ацы ивд нæуæг фарс бакодта',
'recentchanges-label-minor' => 'Ай у чысыл ивд',
'recentchanges-label-bot' => 'Ацы ивд бот сарызта',
'recentchanges-label-unpatrolled' => 'Ацы ивд нырмæ нæу фидар гонд',
'rcnote' => 'Дæлдæр нымад сты афæстаг <strong>$2</strong> боны дæргъы конд <strong>{{PLURAL:$1|иу ивд|$1 ивды}}</strong>, $5, $4 уавæрмæ гæсгæ.',
'rcnotefrom' => "Бындæр сты æвдыст ивдтытæ '''$2'''-æй ('''{{grammar:genitive|$1}}''' йонг).",
'rclistfrom' => 'Равдисын ивдтытæ амæй фæстæ: $1',
'rcshowhideminor' => '$1 чысыл ивдтытæ',
'rcshowhidebots' => '$1 роботты куыст',
'rcshowhideliu' => '$1, йæхи чи бацамыдта, уыцы архайджыты',
'rcshowhideanons' => '$1 æнæном архайджыты',
'rcshowhidepatr' => '$1 бæрæггонд ивдтæ',
'rcshowhidemine' => '$1 мæ ивдтытæ',
'rclinks' => 'Фæстаг $1 ивдтытæ (афæстаг $2 боны дæргъы чи ’рцыдысты) равдис;
$3',
'diff' => 'хицæн.',
'hist' => 'лог',
'hide' => 'Айсын',
'show' => 'Равдисын',
'minoreditletter' => 'ч',
'newpageletter' => 'Н',
'boteditletter' => 'б',
'rc_categories_any' => 'Кæцы фæнды',
'newsectionsummary' => '/* $1 */ ног хай',
'rc-enhanced-expand' => 'Лæмбынæг информаци равдисын (домы JavaScript)',
'rc-enhanced-hide' => 'Айсын лæмбынæг информаци',
'rc-old-title' => 'фыццаг арæзт æрцыд куыд "$1"',

# Recent changes linked
'recentchangeslinked' => 'Баст ивдтытæ',
'recentchangeslinked-feed' => 'Баст ивдтытæ',
'recentchangeslinked-toolbox' => 'Баст ивдтытæ',
'recentchangeslinked-title' => '"{{grammar:comitative|$1}}" баст ивдтытæ',
'recentchangeslinked-noresult' => 'Нæй баст фæрсты ивдтытæ амынд рæстæджы',
'recentchangeslinked-summary' => "Адон сты фæстаг рæстæджы ивдтытæ ахæм уацты, кæдонмæ æрвиты амынд фарс (кæнæ амынд категорийы уæнгтæ). [[Special:Watchlist|Дæ цæстдард фæрстæ]] сты '''бæзджынæй''' нысангонд.",
'recentchangeslinked-page' => 'Фарсы ном:',
'recentchangeslinked-to' => 'Уый бæсты равдисæт амынд фарсмæ æрвитгæ фæрсты ивдтытæ',

# Upload
'upload' => 'Бавгæнын файл',
'uploadbtn' => 'Файл бавгæнын',
'uploadnologin' => 'Системæйæн дæхи нæ бацамыдтай',
'uploaderror' => 'Файл сæвæрыны рæдыд',
'uploadlogpage' => 'Æвгæндты лог',
'filename' => 'Файлы ном',
'filedesc' => 'Афыст:',
'minlength1' => 'Файлы номы хъуамæ æппынкъаддæр иу дамгъæ уа.',
'badfilename' => 'Нывы ном ивд æрцыдис. Ныр хуины «$1».',
'savefile' => 'Файл бавæрын',
'uploadedimage' => 'бавгæдта "[[$1]]"',
'uploadvirus' => 'Файлы ис вирус! 
Лæмбынæг: $1',
'watchthisupload' => 'Ацы файлмæ цæст æрдарын',
'upload-success-subj' => 'Æвгæд æрцыд',

'upload-file-error' => 'Мидæггаг рæдыд',

'license' => 'Лицензи:',
'license-header' => 'Лицензи',

# Special:ListFiles
'listfiles' => 'Нывты номхыгъд',
'listfiles_thumb' => 'Къаддæргонд',
'listfiles_date' => 'Датæ',
'listfiles_name' => 'Ном',
'listfiles_user' => 'Архайæг',
'listfiles_size' => 'Ас',
'listfiles_description' => 'Амынд',
'listfiles_count' => 'Фæлтæртæ',

# File description page
'file-anchor-link' => 'Файл',
'filehist' => 'Файлы истори',
'filehist-help' => 'Ныххæц датæ/рæстæг-ыл, цæмæй фенай еуæд куыд фæзындис.',
'filehist-deleteall' => 'иууылдæр схафын',
'filehist-deleteone' => 'схафын',
'filehist-revert' => 'раивын',
'filehist-current' => 'нырыккон',
'filehist-datetime' => 'Датæ/рæстæг',
'filehist-thumb' => 'Къаддæргонд',
'filehist-thumbtext' => 'Къаддæргонд уыцы версийæн: $1',
'filehist-nothumb' => 'Нæй ын къаддæргонд',
'filehist-user' => 'Архайæг',
'filehist-dimensions' => 'Барæнтæ',
'filehist-filesize' => 'Файлы бæрцуат',
'filehist-comment' => 'Фиппаинаг',
'filehist-missing' => 'Файл нæ зыны',
'imagelinks' => 'Файлæй архайд',
'linkstoimage' => 'Ацы нывæй пайда {{PLURAL:$1|кæны иу фарс|кæнынц ахæм фæрстæ}}:',
'nolinkstoimage' => 'Нæй ахæм фæрстæ, кæдон æрвитынц ацы файлмæ.',
'sharedupload' => 'Ацы файл у {{grammar:ablative|$1}} æмæ дзы гæнæн ис æндæр проекттæ дæр архайой.',
'sharedupload-desc-there' => 'Ацы файл у {{grammar:ablative|$1}} æмæ дзы гæнæн ис æндæр проекттæ дæр архайой.
Кæс [$2 файлы афысты фарс] фылдæр базонынæн.',
'sharedupload-desc-here' => 'Ацы файл у {{grammar:ablative|$1}} æмæ дзы гæнæн ис æндæр проекттæ дæр архайой.
[$2 Йæ фарсæй] афыст у æвдыст бындæр',

# File deletion
'filedelete-comment' => 'Бындур:',
'filedelete-submit' => 'Схафын',
'filedelete-success' => "'''$1''' хафт æрцыд.",
'filedelete-otherreason' => 'Æндæр кæнæ уæлæмхасæн аххос:',
'filedelete-reason-otherlist' => 'Æндæр аххос',

# MIME search
'download' => 'равгæнын',

# Unused templates
'unusedtemplates' => 'Пайда кæмæй нæ чындæуы, ахæм хуызæгтæ',

# Random page
'randompage' => 'Æрхаугæ фарс',

# Statistics
'statistics' => 'Статистикæ',
'statistics-header-pages' => 'Фарсы статистикæ',
'statistics-header-edits' => 'Ивдтыты статистикæ',
'statistics-header-views' => 'Бакастыты статистикæ',
'statistics-header-users' => 'Архайджыты статистикæ',
'statistics-header-hooks' => 'Æндæр статистикæтæ',
'statistics-articles' => 'Мидисы фæрстæ',

'disambiguationspage' => 'Template:бирæнысанон',

'double-redirect-fixer' => 'Рарвыст растгæнæн',

'brokenredirects-edit' => 'ивын',
'brokenredirects-delete' => 'схафын',

'withoutinterwiki-submit' => 'Равдисын',

'fewestrevisions' => 'Къаддæр кæй ивынц, ахæм фæрстæ',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|байт|байты}}',
'ncategories' => '$1 {{PLURAL:$1|категори|категорийы}}',
'ninterwikis' => '$1 {{PLURAL:$1|интервики|интервикийы}}',
'nlinks' => '$1 {{PLURAL:$1|æрвитæн|æрвитæны}}',
'nmembers' => '$1 {{PLURAL:$1|уæнг|уæнгы}}',
'nrevisions' => '$1 {{PLURAL:$1|фæлтæр|фæлтæры}}',
'nviews' => '$1 {{PLURAL:$1|æркаст|æркасты}}',
'nimagelinks' => 'Архайд цæуы $1 {{PLURAL:$1|фарсы}}',
'ntransclusions' => 'архайд цæуы $1 {{PLURAL:$1|фарсы}}',
'lonelypages' => 'Сидзæр фæрстæ',
'uncategorizedpages' => 'Æнæкатегори фæрстæ',
'uncategorizedimages' => 'Æнæкатегори файлтæ',
'uncategorizedtemplates' => 'Æнæкатегори хуызæгтæ',
'popularpages' => 'Популярон фæрстæ',
'wantedcategories' => 'Хъæугæ категоритæ',
'wantedpages' => 'Хъæугæ фæрстæ',
'wantedfiles' => 'Хъæугæ файлтæ',
'mostlinked' => 'Фылдæр æрвитæнтæ кæмæ и, ахæм фæрстæ',
'mostlinkedcategories' => 'Фылдæр æрвитæнтæ кæмæ и, уыцы категоритæ',
'mostrevisions' => 'Фылдæр кæй ивынц, ахæм фæрстæ',
'prefixindex' => 'Фæрстæ префиксмæ гæсгæ',
'shortpages' => 'Цыбыр фæрстæ',
'longpages' => 'Даргъ фæрстæ',
'protectedpages' => 'Æхгæд фæрстæ',
'listusers' => 'Архайджыты номхыгъд',
'usereditcount' => '$1 {{PLURAL:$1|ивд|ивды}}',
'usercreated' => '{{GENDER:$3|Фæзындис}} $1, {{grammar:superessive|$2}}',
'newpages' => 'Ног фæрстæ',
'newpages-username' => 'Архайæг:',
'ancientpages' => 'Зæронддæр фæрстæ',
'move' => 'Ном ивын',
'pager-newer-n' => '{{PLURAL:$1|нæуæгдæр иу|нæуæгдæр $1}}',
'pager-older-n' => '{{PLURAL:$1|раздæр иу|раздæр $1}}',

# Book sources
'booksources' => 'Чиныгисæнтæ',
'booksources-search-legend' => 'Агурын чингуыты равзæрæнтæ',
'booksources-go' => 'Агурын',

# Special:Log
'specialloguserlabel' => 'Гæнæг:',
'speciallogtitlelabel' => 'Мил (ном кæнæ архайæг):',
'log' => 'Логтæ',
'all-logs-page' => 'Иууылдæр логтæ',

# Special:AllPages
'allpages' => 'Иууылдæр фæрстæ',
'alphaindexline' => '$1 (уыдоны ’хсæн цы уацтæ ис, фен) $2',
'nextpage' => 'Фæдылдзог фарс ($1)',
'prevpage' => 'Раздæры фарс ($1)',
'allarticles' => 'Иууылдæр фæрстæ',
'allpagesprev' => 'фæстæмæ',
'allpagesnext' => 'дарддæр',
'allpagessubmit' => 'Ацæуын',

# Special:Categories
'categories' => 'Категоритæ',
'categoriespagetext' => 'Ацы {{PLURAL:$1|категорийы|категориты}} ис фæрстæ кæнæ файлтæ.
[[Special:UnusedCategories|Нæ пайдагонд категоритæ]] æвдыст не сты.
Ноджы кæс [[Special:WantedCategories|хъæугæ категоритæ]].',
'special-categories-sort-count' => 'нымæцмæ гæсгæ равæр',
'special-categories-sort-abc' => 'алфавитмæ гæсгæ равæр',

# Special:LinkSearch
'linksearch' => 'Æддаг æрвитæнтæ агурын',
'linksearch-ns' => 'Номдон:',
'linksearch-ok' => 'Агурын',
'linksearch-line' => '$2 æрвиты {{grammar:allative|$1}}',

# Special:ListUsers
'listusers-submit' => 'Равдисын',

# Special:Log/newusers
'newuserlogpage' => 'Архайджыты фæзындты лог',

# Special:ListGroupRights
'listgrouprights-group' => 'Къорд',
'listgrouprights-rights' => 'Бартæ',
'listgrouprights-members' => '(уæнгты номхыгъд)',

# E-mail user
'mailnologintext' => 'Фыстæгтæ æрвитынмæ хъуамæ [[Special:UserLogin|системæйæн дæхи бавдисай]] æмæ дæ бæлвырд электронон посты адрис [[Special:Preferences|ныффыссай]].',
'emailuser' => 'Ацы архайæгæн электронон фыстæг рарвитт',
'emailpage' => 'Электронон фыстæг йæм барвит',

# Watchlist
'watchlist' => 'Мæ цæстдард рæгъ',
'mywatchlist' => 'Цæстдард',
'watchlistfor2' => 'Архайæг: $1 $2',
'nowatchlist' => 'Иу уацмæ дæр дæ цæст нæ дарыс.',
'watchnologin' => 'Системæйæн дæхи нæ бацамыдтай',
'watchnologintext' => 'Ацы номхыгъд ивынæн хъæуы [[Special:UserLogin|бахизын]].',
'removedwatchtext' => '«[[:$1]]» фарсмæ [[Special:Watchlist|дæ цæст]] нал дарыс.',
'watch' => 'Цæст æрдарын',
'watchthispage' => 'Цæст дарын ацы фарсмæ',
'unwatch' => 'Нал дарын цæст',
'watchnochange' => 'Дæ цæстдард уацтæй иу дæр ивд не ’рцыдис.',
'watchlist-details' => '{{PLURAL:$1|$1 фарсмæ|$1 фарсмæ}} дæ цæст дарыс, тæрхоны фæрстæ нæ нымайгæйæ.',
'watchlistcontains' => 'Дæ цæст $1 {{PLURAL:$1|фарсмæ|фарсмæ}} дарыс.',
'wlnote' => "Дæлæ афæстаг '''$2 сахаты дæргъы''' цы $1 {{PLURAL:$1|ивддзинад|ивддзинады}} æрцыди.",
'wlshowlast' => 'Фæстæг $1 сахаты, $2 боны дæргъы; $3.',
'watchlist-options' => 'Цæстдард рæгъы фадæттæ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Цæстдард фæрсты номхыгъдмæ афтауын...',
'unwatching' => 'Цæстдард фæрсты номхыгъдæй аиуварс кæнын...',

'enotif_newpagetext' => 'Ай у нæуæг фарс.',
'changed' => 'ивд æрцыд',
'enotif_anon_editor' => 'сусæг архайæг $1',

# Delete
'deletepage' => 'Схаф фарс',
'exblank' => 'фарс афтид уыдис',
'delete-confirm' => 'Схаф "$1"',
'actioncomplete' => 'Æххæст',
'actionfailed' => 'Нæ рауадис',
'dellogpage' => 'Хафынты лог',
'deletionlog' => 'хафынты лог',
'deletecomment' => 'Аххос:',
'deleteotherreason' => 'Æндæр кæнæ уæлæмхасæн аххос:',
'deletereasonotherlist' => 'Æндæр аххос',

# Rollback
'rollbacklink' => 'раздæхын',

# Protect
'protectlogpage' => 'Хъахъхъæды лог',
'protectedarticle' => '«[[$1]]» фарс æхгæд æрцыд',
'prot_1movedto2' => '[[$1]] хаст æрцыд [[$2|{{grammar:allative|$2}}]]',
'protectcomment' => 'Аххос:',
'protectexpiry' => 'Кæдмæ æхгæд у:',
'protect-level-sysop' => 'Æрмæст админтæ',
'protect-othertime' => 'Æндæр рæстæг:',
'protect-othertime-op' => 'æндæр рæстæг',
'protect-otherreason' => 'Æндæр аххос/уæлæмхасæн:',
'protect-otherreason-op' => 'Æндæр аххос',
'restriction-type' => 'Бартæ:',

# Restrictions (nouns)
'restriction-edit' => 'Ивын',

# Undelete
'undeletelink' => 'кæсын/рацаразын',
'undeleteviewlink' => 'кæсын',
'undelete-search-submit' => 'Агурын',

# Namespace form on various pages
'namespace' => 'Номдон:',
'invert' => 'Зыгъуыммæ æвзæрст',
'blanknamespace' => '(Сæйраг)',

# Contributions
'contributions' => 'Архайæджы бавæрд',
'contributions-title' => 'Архайæджы бавæрд: $1',
'mycontris' => 'Бавæрд',
'contribsub2' => 'Архайæг: $1 ($2)',
'uctop' => '(фæстаг)',
'month' => 'Ацы мæйы (æмæ раздæр):',
'year' => 'Ацы азы (æмæ раздæр):',

'sp-contributions-newbies' => 'Æвдисын æрмæст нæуæг архайджыты бавæрд',
'sp-contributions-blocklog' => 'хъодыты лог',
'sp-contributions-uploads' => 'бавгæндтытæ',
'sp-contributions-logs' => 'логтæ',
'sp-contributions-talk' => 'ныхас',
'sp-contributions-search' => 'Бавæрд агурæн',
'sp-contributions-username' => 'IP адрис кæнæ фæсномыг:',
'sp-contributions-toponly' => 'Æвдисæт æрмæст фæстаг ивдтытæ',
'sp-contributions-submit' => 'Агурын',

# What links here
'whatlinkshere' => 'Чи æрвиты ардæм',
'whatlinkshere-title' => 'Фæрстæ, кæдон æрвитынц ардæм: «$1»',
'whatlinkshere-page' => 'Фарс:',
'linkshere' => "Ацы фæрстæ æрвитынц '''[[:$1|{{grammar:allative|$1}}]]''':",
'nolinkshere' => "Никæцы фарс æрвиты ардæм: '''[[:$1]]'''.",
'isredirect' => 'æрвитæн фарс',
'istemplate' => 'æфтыдæй',
'isimage' => 'файлмæ æрвитæн',
'whatlinkshere-prev' => '{{PLURAL:$1|раздæры|раздæры $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|иннæ|иннæ $1}}',
'whatlinkshere-links' => '← æрвитæнтæ',
'whatlinkshere-hideredirs' => 'Рарвыстытæ $1',
'whatlinkshere-hidetrans' => '$1 æфтыдтытæ',
'whatlinkshere-hidelinks' => '$1 æрвитæнтæ',
'whatlinkshere-hideimages' => 'Файлмæ æрвитæнтæ $1',
'whatlinkshere-filters' => 'Фильтртæ',

# Block/unblock
'block' => 'Архайæгыл хъоды кæнын',
'blockip' => 'Бахъоды кæнын',
'blockip-title' => 'Архайæгыл хъоды',
'blockip-legend' => 'Архайæгыл хъоды кæнын',
'ipadressorusername' => 'IP адрис кæнæ фæсномыг:',
'ipbexpiry' => 'Фæуыны афон:',
'ipbreason' => 'Аххос:',
'ipbreasonotherlist' => 'Æндæр тыххæй',
'ipboptions' => '2 сахаты:2 hours,1 бон:1 day,3 боны:3 days,1 къуыри:1 week,2 къуырийы:2 weeks,1 мæй:1 month,3 мæййы:3 months,6 мæййы:6 months,1 аз:1 year,нæбæрæг:infinite',
'ipbotheroption' => 'æндæр',
'ipbotherreason' => 'Æндæр кæнæ уæлæмхасæн аххос:',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] хъодыгонд æрцыд.<br />
Кæс [[Special:Ipblocklist|хъодыгонд IP-адристы номхыгъд]].',
'ipb-blocklist' => 'Актуалон хъоды равдис',
'ipb-blocklist-contribs' => '{{grammar:genitive|$1}} бавæрд',
'ipblocklist' => 'Хъодыгонд архайджытæ',
'ipblocklist-legend' => 'Хъодыгонд архайæджы ацагур',
'ipblocklist-submit' => 'Агурын',
'ipblocklist-empty' => 'Хъодыгæндты номхыгъд афтид у.',
'blocklink' => 'бахъоды кæн',
'unblocklink' => 'хъоды айс',
'change-blocklink' => 'ивын хъоды',
'contribslink' => 'бавæрд',
'blocklogpage' => 'Хъодыты лог',
'blocklogentry' => 'бахъоды кодта [[$1]] $2 æмгъуыдмæ $3',
'block-log-flags-nocreate' => 'аккаунт аразæн нал ис',

# Developer tools
'lockdbsuccesssub' => 'Рарддон æхгæд æрцыд',
'unlockdbsuccesssub' => 'Рардон байгом ис',
'unlockdbsuccesstext' => 'Рард дон гом æрцыд.',
'databasenotlocked' => 'Рарддон æхгæд неу.',

# Move page
'movearticle' => 'Номивгæ фарс:',
'movenologin' => 'Системæйæн дæхи нæ бацамыдтай',
'newtitle' => 'Ног ном:',
'move-watch' => 'Зæронд æмæ нæуæг фæрстæм цæст æрдарын',
'movepagebtn' => 'Фарсы ном ивын',
'movelogpage' => 'Номивдтыты лог',
'movereason' => 'Бындур:',
'revertmove' => 'раивын',

# Export
'export' => 'Фæрсты экспорт',

# Namespace 8 related
'allmessages' => 'Системæйы фыстытæ',
'allmessagesname' => 'Ном',
'allmessagesdefault' => 'Разæвæрд текст',
'allmessages-filter-all' => 'Иууылдæр',
'allmessages-language' => 'Æвзаг:',

# Thumbnails
'thumbnail-more' => 'Фестырдæр кæнын',
'thumbnail_error' => 'Чысыл фæлтæр аразыны рæдыд: $1',
'thumbnail_invalid_params' => 'Рæдыд къаддæргонды миниуджытæ',

# Special:Import
'importnotext' => 'Афтид у кæнæ текст дзы нæй',
'importuploaderrortemp' => 'Импорты файл æрвитын нæ фæрæстмæ. Нæй рæстæгмæ файлдон.',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Дæ архайæджы фарс',
'tooltip-pt-mytalk' => 'Дæ ныхасы фарс',
'tooltip-pt-preferences' => 'Дæ уагæвæрдтæ',
'tooltip-pt-watchlist' => 'Фæрстæ кæй ивдтытæм ды дарыс дæ цæст',
'tooltip-pt-mycontris' => 'Дæ бавæрд',
'tooltip-pt-login' => 'Хуыздæр у куы бахизай системæмæ; фæлæ нæмæнг нæу',
'tooltip-pt-logout' => 'Рахизын',
'tooltip-ca-talk' => 'Фарсы тыххæй тæрхон',
'tooltip-ca-edit' => 'Ацы фарс дæ бон у ивын. Дæ хорзæхæй, «Фен уал æй» джыбыйæ пайда кæн',
'tooltip-ca-addsection' => 'Нæуæг хай саразын',
'tooltip-ca-viewsource' => 'Ацы фарс ивын нæй гæнæн.
Дæ бон у фенын ын йæ бындуртекст',
'tooltip-ca-history' => 'Ацы фарсæн йæ раздæры фæлтæртæ',
'tooltip-ca-protect' => 'Ацы фарс ивддзинæдтæй сæхгæнын',
'tooltip-ca-delete' => 'Ацы фарс схафын',
'tooltip-ca-move' => 'Уацы ном ивын',
'tooltip-ca-watch' => 'Бафтау ацы фарс дæ цæстдард рæгъмæ',
'tooltip-ca-unwatch' => 'Айс ацы фарс дæ цæстдард рæгъæй',
'tooltip-search' => '{{grammar:genitive|{{SITENAME}}}} агурын',
'tooltip-search-go' => 'Кæд ахæм ном исты статьямæ и, уыцы статья равдис',
'tooltip-search-fulltext' => 'Ацы текст цы фæрсты ис, уыдон агурын',
'tooltip-p-logo' => 'Сæйраг фарсмæ бацæуын',
'tooltip-n-mainpage' => 'Сæйраг фарсмæ ацæуын',
'tooltip-n-mainpage-description' => 'Сæйраг фарсмæ ацæуын',
'tooltip-n-portal' => 'Проект, дæ бон цы у æмæ кæм цы хъæуы агурыны тыххæй',
'tooltip-n-currentevents' => 'Ныры хабæрттыл информаци ссарынæн',
'tooltip-n-recentchanges' => 'Фæстаг ивдтыты рæгъ',
'tooltip-n-randompage' => 'Бакæнын халæй ист фарс',
'tooltip-n-help' => 'Базонынæн бынат',
'tooltip-t-whatlinkshere' => 'Ацы фарсмæ чи ’рвитынц, ахæм фæрсты номхыгъд',
'tooltip-t-recentchangeslinked' => 'Ацы фарс кæмæ æрвиты, уыцы фæрсты фæстаг ивдтытæ',
'tooltip-feed-atom' => 'Atom feed ацы фарсæн',
'tooltip-t-contributions' => 'Ацы архайæджы бавæрд фенын',
'tooltip-t-emailuser' => 'Арвитын фыстæг ацы архайæгмæ',
'tooltip-t-upload' => 'Файлтæ бавгæнынæн',
'tooltip-t-specialpages' => 'Сæрмагонд фæрсты номхыгъд',
'tooltip-t-print' => 'Ацы фарс мыхуырмæ цæттæ форматы',
'tooltip-t-permalink' => 'Фарсы ацы фæлтæрмæ æрвитæн (фæрстæ ивынц, ацы фæлтæр — нæ)',
'tooltip-ca-nstab-main' => 'Фенын уац',
'tooltip-ca-nstab-user' => 'Архайæджы фарс фенын',
'tooltip-ca-nstab-special' => 'Ай сæрмагонд фарс у, дæ бон æй нæу ивын',
'tooltip-ca-nstab-project' => 'Фенын проекты фарс',
'tooltip-ca-nstab-image' => 'Нывы фарс',
'tooltip-ca-nstab-template' => 'Хуызæг фенын',
'tooltip-ca-nstab-category' => 'Категорийы фарс',
'tooltip-minoredit' => 'Чысыл ивдæй йæ банысан кæнын',
'tooltip-save' => 'Бавæр дæ ивдтытæ',
'tooltip-preview' => 'Бакæс уал дæ ивдмæ. Дæ хорзæхæй ай уал сараз бавæрыны размæ!',
'tooltip-diff' => 'Æвдисы цы ивд бахастай текстмæ',
'tooltip-compareselectedversions' => 'Кæс дыууæ æвзæрст фæлтæры цæмæй хицæн кæнынц',
'tooltip-watch' => 'Ацы фарсмæ цæст æрдарынæн',
'tooltip-watchlistedit-normal-submit' => 'Нæмттæ схафын',
'tooltip-watchlistedit-raw-submit' => 'Цæстдард фæнæуæг кæнын',
'tooltip-recreate' => 'Фарс рацаразын, кæд хафт уыд, уæддæр',
'tooltip-upload' => 'Æвгæнын байдайын',
'tooltip-rollback' => '"Раздæхын" æздæхы ацы фарсы фæстаг архайæджы ивд(тытæ) иу хæстмæ',
'tooltip-undo' => '"Раивын" æздæхы ацы ивд æмæ æвдисы ивæн фарс разбакаст уавæры. Уый дын дæ бавæрдæн фсон скæныны фадат дæтты.',
'tooltip-preferences-save' => 'Уаг бавæрын',
'tooltip-summary' => 'Бафысс чысыл æмбарынгæнæн',

# Metadata
'notacceptable' => 'Ацы викийæн йæ бон нæу радтын рардтæ ахæм форматы, цæмæй цæ дæ клиент фæрса.',

# Attribution
'anonymous' => '{{grammar:genitive|{{SITENAME}}}} æнæном {{PLURAL:$1|архайæг|архайджытæ}}',
'siteuser' => '{{grammar:genitive|{{SITENAME}}}} архайæг $1',
'anonuser' => '{{grammar:genitive|{{SITENAME}}}} æнæном архайæг $1',
'lastmodifiedatby' => 'Ацы фарс фæстаг хатт фæивта $3, $1, $2 сахатыл.',
'others' => 'æндæртæ',

# Spam protection
'spamprotectiontitle' => 'Спамы ныхмæ фильтр',

# Skin names
'skinname-standard' => 'Стандартон',
'skinname-nostalgia' => 'Æнкъард',
'skinname-cologneblue' => 'Кёльны æрхæндæг',
'skinname-monobook' => 'Моно-чиныг',
'skinname-myskin' => 'Мæхи',
'skinname-chick' => 'Карк',

# Browsing diffs
'previousdiff' => '← Зæронддæр ивд',
'nextdiff' => 'Фæстæдæр ивд →',

# Media information
'thumbsize' => 'Къаддæргонды бæрц:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|фарс|фарсы}}',
'file-info-size' => '$1 × $2 пикселы, файлы уæз: $3, MIME тип: $4',
'file-nohires' => 'Нæй ын стырдæр фæлтæр .',
'svg-long-desc' => 'SVG файл, номиналон $1 × $2 пикселы, файлы бæрц: $3',
'show-big-image' => 'Æнæхъæнæй',

# Special:NewFiles
'newimages' => 'Ног нывты галерей',
'showhidebots' => '(роботты куыст $1)',
'ilsubmit' => 'Агурын',
'bydate' => 'рæстæгмæ гæсгæ',

# Bad image list
'bad_image_list' => 'Формат у ахæм:

Æрмæстдæр рæгъон рæнхъытæ (рæнхъытæ, кæдон байдауынц * символæй) нымады кæнынц.
Фыццаг æрвитæн рæнхъы хъуамæ æрвита æвзæр файлмæ.
Иннæ æрвитæнтæ уыцы рæнхъы нымады кæнынц куыд уæлвæткытæ, кæдон сты фæрстæ кæдæм ис бар бавæрын файл.',

# Metadata
'metadata' => 'Метабæрæггæнæнтæ',
'metadata-help' => 'Ацы файлы мидæг ис лæмбынæг информаци, кæцы æвæццæгæн уыд æфтыд нывисæн æви сканерæй, кæдон сарæзтой файл.
Кæд файл уыдис ивд, уæд, гæнæн ис, кæцыдæр рардтæ нæ æмбæлой нæуæг файлмæ.',
'metadata-expand' => 'Равдисын лæмбынæг афыст',
'metadata-collapse' => 'Айсын лæмбынæг афыст',
'metadata-fields' => 'Нывы метарардтæ, кæдон ам сты ранымад, уыдзысты æвдыст нывы фарсыл, мета рардты бынат зылд куы уа.
Иннæтæ уыдзысты æмбæхст разæвæрдæй.
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
'exif-imagewidth' => 'Уæрх',
'exif-imagelength' => 'Бæрзæнд',
'exif-artist' => 'Автор',
'exif-writer' => 'Фыссæг',
'exif-languagecode' => 'Æвзаг',
'exif-iimversion' => 'IIM-ы фæлтæр',
'exif-iimcategory' => 'Категори',
'exif-iimsupplementalcategory' => 'Æндæр категоритæ',
'exif-datetimeexpires' => 'Ма архай ацы датæйы фæстæ',
'exif-datetimereleased' => 'Рауагъды датæ',
'exif-identifier' => 'Бæрæггæнæн',

'exif-gaincontrol-0' => 'Нæй',

# External editor support
'edit-externally' => 'Ивын ацы файл æддаг программæйæ',
'edit-externally-help' => '(Кæс [//www.mediawiki.org/wiki/Manual:External_editors сывæрыны уагæвæрдтæ] фылдæр базонынæн)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'иууылдæр',
'namespacesall' => 'иууылдæр',
'monthsall' => 'иууылдæр',
'limitall' => 'иууылдæр',

# action=purge
'confirm_purge_button' => 'Афтæ уæд!',

# action=watch/unwatch
'confirm-watch-button' => 'Хорз',
'confirm-unwatch-button' => 'Хорз',

# Multipage image navigation
'imgmultipageprev' => '← раздæры фарс',
'imgmultipagenext' => 'иннæ фарс →',
'imgmultigo' => 'Афтæ бакæн!',

# Table pager
'table_pager_next' => 'Фæдылдзог фарс',
'table_pager_prev' => 'Раздæры фарс',
'table_pager_first' => 'Фыццаг фарс',
'table_pager_last' => 'Фæстаг фарс',

# Auto-summaries
'autoredircomment' => 'Фарс æрвыст æрцыд [[$1|{{grammar:allative|$1}}]]',
'autosumm-new' => 'Ног фарс, йæ код райдайы афтæ: «$1»',

# Size units
'size-bytes' => '$1 байт(ы)',
'size-kilobytes' => '$1 КБ',
'size-megabytes' => '$1 МБ',
'size-gigabytes' => '$1 ГБ',

# Live preview
'livepreview-loading' => 'Æвгæд цæуы...',
'livepreview-ready' => 'Æвгæд цæуы...Цæттæ!',
'livepreview-failed' => 'Тагъд разæркастæй пайда кæнæн нæй. Хуымæтæджы разæркастæй пайда кæн.',

# Watchlist editor
'watchlistedit-noitems' => 'Дæ цæстдард афтид у.',
'watchlistedit-normal-title' => 'Цæстдард ивæн',
'watchlistedit-normal-submit' => 'Схафын фыстытæ',
'watchlistedit-raw-titles' => 'Сæртæ:',
'watchlistedit-raw-submit' => 'Номхыгъд бафснай',

# Watchlist editing tools
'watchlisttools-view' => 'Баст ивдтытæ фен',
'watchlisttools-edit' => 'Бакæсын æмæ ивын цæстдард рæгъ',
'watchlisttools-raw' => 'Ивын цæстдард рæгъы бындуртекст',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|ныхас]])',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Сындæг:\'\'\' Разæвæрд сортгæнæн амонæн "$2" раздæры разæвæрд амонæн "$1"-ы бæсты лæууы.',

# Special:Version
'version' => 'Фæлтæр',
'version-skins' => 'Цъар',
'version-other' => 'Æндæр',
'version-version' => '(Фæлтæр $1)',
'version-license' => 'Лицензи',
'version-poweredby-credits' => "Ацы викийæн тых радта '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others' => 'æндæртæ',
'version-software-version' => 'Верси',

# Special:FilePath
'filepath' => 'Файлмæ фæт',
'filepath-page' => 'Файл:',
'filepath-submit' => 'Бацæуын',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Файлы ном:',
'fileduplicatesearch-submit' => 'Агурын',

# Special:SpecialPages
'specialpages' => 'Сæрмагонд фæрстæ',

# External image whitelist
'external_image_whitelist' => ' #Ныууадз ацы рæнхъ куыд у афтæ<pre>
#Бавæр бындæр регуларон дзырды ххæйттæ (кæцы ис дыууæ слешы æхсæн //)
#Уыдон уыдзысты æмхаст æддагон нывты URL-тимæ
#Кæд нывы URL æмбæлы дзырдтимæ, уæд уыдзæн æвдыст куыд ныв, кæннæд та куыд æрвитæн нывмæ.
#Рæнхъытæ, кæдон байдайынц #-йæ сты нымад куыд хъуыдытæ
#Дамгъæ уавæр (стыр æви гыццыл) уæлдай у

#Æвæр регуларон дзырдтæ ацы рæнхъы фæстæ. Ныууадз ацы рæнхъ куыд у афтæ</pre>',

# Special:Tags
'tag-filter' => '[[Special:Tags|Тегты]] фæрсудзæн:',
'tags-edit' => 'ивын',

# Special:ComparePages
'compare-page1' => 'Фарс 1',
'compare-page2' => 'Фарс 2',
'compare-rev1' => 'Фæлтæр 1',
'compare-rev2' => 'Фæлтæр 2',
'compare-submit' => 'Абарын',

# HTML forms
'htmlform-selectorother-other' => 'Æндæр',

# New logging system
'logentry-delete-delete' => '$1 схафта фарс $3',
'logentry-delete-restore' => '$1 рацарæзта фарс $3',

# Feedback
'feedback-subject' => 'Сæр:',
'feedback-message' => 'Фыстæг:',
'feedback-cancel' => 'Ныууадзын',
'feedback-close' => 'Æххæст',

# Search suggestions
'searchsuggest-search' => 'Агурын',

# API errors
'api-error-missingparam' => 'Мидæггаг рæдыд: Курдиаты параметртæ нæй.',
'api-error-missingresult' => 'Мидæггаг рæдыд: Нæ рауадис сбæрæг кæнын къопи кæнын куыд бантыстис.',
'api-error-mustbeloggedin' => 'Ды хъуамæ системæмæ хызт уай, цæмæй файлтæ æвгæнай.',
'api-error-mustbeposted' => 'Мидæггаг рæдыд: Курдиат хъуамæ уа HTTP POST.',
'api-error-noimageinfo' => 'Æвгæд æххæст у, фæлæ нын сервер ницыуал рабæрæг кодта файлы тыххæй.',
'api-error-nomodule' => 'Мидæггаг рæдыд: Бавгæнæн модуль нæу æвæрд.',
'api-error-ok-but-empty' => 'Мидæггаг рæдыд: Серверæй дзуапп нæй.',
'api-error-overwrite' => 'Уæвгæ файл ногæй фыссын нæй гæнæн.',
'api-error-stashfailed' => 'Мидæггаг рæдыд: Серверæн нæ рауадис рæстæгмæ файл фæдарын.',
'api-error-timeout' => 'Сервер нæ радта дзуапп бадзырд рæстæгмæ.',
'api-error-unclassified' => 'Нæзонгæ рæдыд æрцыд.',
'api-error-unknown-code' => 'Нæзонгæ рæдыд: "$1".',
'api-error-unknown-error' => 'Мидæггаг рæдыд: Цыдæр раст нæ ацыдис, файл куы æвгæдтай, уæд.',
'api-error-unknown-warning' => 'Нæзонгæ фæдзæхст: "$1".',
'api-error-unknownerror' => 'Нæзонгæ рæдыд: "$1".',
'api-error-uploaddisabled' => 'Ацы викийы, бавгæныны фадат хицæн у.',
'api-error-verification-error' => 'Ацы файл гæнæн ис хæлд у, кæнæ йæ номы фæстаг хай раст нæу.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|секунд|секунды}}',
'duration-minutes' => '$1 {{PLURAL:$1|минут|минуты}}',
'duration-hours' => '$1 {{PLURAL:$1|сахат|сахаты}}',
'duration-days' => '$1 {{PLURAL:$1|бон|боны}}',
'duration-weeks' => '$1 {{PLURAL:$1|къуыри|къуырийы}}',
'duration-years' => '$1 {{PLURAL:$1|аз|азы}}',
'duration-decades' => '$1 {{PLURAL:$1|дæсадз|дæсадзы}}',
'duration-centuries' => '$1 {{PLURAL:$1|æнус|æнусы}}',
'duration-millennia' => '$1 {{PLURAL:$1|мин аз|мин азы}}',

);
