<?php
/** Ossetic (Иронау)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amikeco
 * @author Bouron
 * @author HalanTul
 * @author לערי ריינהארט
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Сæрмагонд',
	NS_TALK             => 'Дискусси',
	NS_USER             => 'Архайæг',
	NS_USER_TALK        => 'Архайæджы_дискусси',
	NS_PROJECT_TALK     => 'Дискусси_$1',
	NS_FILE             => 'Ныв',
	NS_FILE_TALK        => 'Нывы_тыххæй_дискусси',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Дискусси_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблоны_тыххæй_дискусси',
	NS_HELP             => 'Æххуыс',
	NS_HELP_TALK        => 'Æххуысы_тыххæй_дискусси',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категорийы_тыххæй_дискусси',
);

$magicWords = array(
	'redirect'              => array( '0', '#РАРВЫСТ', '#перенаправление', '#перенапр', '#REDIRECT' ),
	'img_right'             => array( '1', 'рахиз', 'справа', 'right' ),
	'img_left'              => array( '1', 'галиу', 'слева', 'left' ),
);

$linkTrail = '/^((?:[a-z]|а|æ|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я|“|»)+)(.*)$/sDu';
$fallback8bitEncoding =  'windows-1251';

$messages = array(
# User preference toggles
'tog-underline'               => 'Æрвитæнты бын хахх',
'tog-justify'                 => 'Æмвæз абзацтæ',
'tog-hideminor'               => 'Чысыл ивддзинæдтæ фæстаг ивддзинæдты номхыгъды мауал æвдис',
'tog-newpageshidepatrolled'   => 'Басгæрст фæрстæ ног фæрсты номхыгъдæй æмбæхс',
'tog-numberheadings'          => 'Сæргæндты автоматикон нумераци',
'tog-editondblclick'          => 'Фæрстæ дыкъæппæй ив (JavaScript)',
'tog-editsection'             => 'Равдис «баив æй» æрвитæн тексты алы хайы дæр',
'tog-editsectiononrightclick' => 'Сæргондыл рахиз æркъæппæй фарсы хæйттæ ив (JavaScript)',
'tog-showtoc'                 => 'Сæргæндты номхыгъд æвдис (æртæ сæргондæй фылдæр цы фарсы ис, уым)',
'tog-rememberpassword'        => 'Системæ бахъуыды кæнæд мæ пароль ацы компьютерыл (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'          => 'Æз цы фæрстæ райдайын, уыдонмæ мæ цæст дарын мæ фæнды',
'tog-watchdefault'            => 'Æз цы фæрстæ ивын, уыдонмæ мæ цæст дарын мæ фæнды',
'tog-watchmoves'              => 'Æз цы фæрсты нæмттæ ивын, уыдонмæ мæ цæст дарын мæ фæнды',
'tog-watchdeletion'           => 'Æз цы фæрстæ аппарын, уыдонмæ мæ цæст дарын мæ фæнды',
'tog-previewontop'            => 'Разæркасты рудзынг ивыны рудзынджы уæлдæр',
'tog-enotifwatchlistpages'    => 'Электронон постæй мæм хъуысынгæнинаг æрвыст уа, æз цы фæрстæм мæ цæст дарын, уыдонæй иу куы ивд æрцæуа, уæд',
'tog-enotifusertalkpages'     => 'Электронон постæй мæм хъуысынгæнинаг æрвыст уа, мæ дискусси куы ивд æрцæуа, уæд',
'tog-enotifminoredits'        => 'Кæд ивддзинад чысыл у, уæддæр мæм электронон фыстæг æрбацæуа',
'tog-shownumberswatching'     => 'Цал архайæджы фарсмæ сæ цæст дарынц, уый равдис',
'tog-showjumplinks'           => 'Тагъд æрвитæнтæй пайда кæн',
'tog-watchlisthideown'        => 'Мæ цæстдарды номхыгъды, мæхæдæг цы ивддзинæдтæ бахæстон, уыдон бамбæхс',
'tog-watchlisthidebots'       => 'Мæ цæстдарды номхыгъды роботты куыст бамбæхс',
'tog-watchlisthideminor'      => 'Мæ цæстдарды номхыгъды чысыл ивддзинæдтæ бамбæхс',
'tog-watchlisthidepatrolled'  => 'Басгæрст ивддзинæдтæ, дæ цæст кæмæ дарыс, уыцы номхыгъды ма ’вдис',
'tog-ccmeonemails'            => 'Æз электронон фыстæг æндæр архайæгæн куы рарвитын, уæд уыцы иу фыстæг мæхи адрисмæ дæр æрбацæуæд.',
'tog-showhiddencats'          => 'Æмбæхст категоритæ æвдис',

'underline-always'  => 'Æдзух',
'underline-never'   => 'Никуы',
'underline-default' => 'Браузеры куыд у',

# Font style option in Special:Preferences
'editfont-default' => 'Браузеры куыд у',

# Dates
'sunday'        => 'Хуыцаубон',
'monday'        => 'Къуырисæр',
'tuesday'       => 'Дыццæг',
'wednesday'     => 'Æртыццæг',
'thursday'      => 'Цыппæрæм',
'friday'        => 'майрæмбон',
'saturday'      => 'Сабат',
'sun'           => 'Хц',
'mon'           => 'Къ',
'tue'           => 'Дц',
'wed'           => 'Æр',
'thu'           => 'Цп',
'fri'           => 'Мрм',
'sat'           => 'Сбт',
'january'       => 'январь',
'february'      => 'февраль',
'march'         => 'мартъи',
'april'         => 'апрель',
'may_long'      => 'май',
'june'          => 'июнь',
'july'          => 'июль',
'august'        => 'август',
'september'     => 'сентябрь',
'october'       => 'октябрь',
'november'      => 'ноябрь',
'december'      => 'декабрь',
'january-gen'   => 'январы',
'february-gen'  => 'февралы',
'march-gen'     => 'мартъийы',
'april-gen'     => 'апрелы',
'may-gen'       => 'майы',
'june-gen'      => 'июны',
'july-gen'      => 'июлы',
'august-gen'    => 'августы',
'september-gen' => 'сентябры',
'october-gen'   => 'октябры',
'november-gen'  => 'ноябры',
'december-gen'  => 'декабры',
'jan'           => 'янв',
'feb'           => 'фев',
'mar'           => 'мар',
'apr'           => 'апр',
'may'           => 'май',
'jun'           => 'июн',
'jul'           => 'июл',
'aug'           => 'авг',
'sep'           => 'сен',
'oct'           => 'окт',
'nov'           => 'ноя',
'dec'           => 'дек',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Категори|Категоритæ}}',
'category_header'                => 'Категори "$1"',
'subcategories'                  => 'Дæлкатегоритæ',
'category-media-header'          => 'Категори «$1»-ы файлтæ',
'category-empty'                 => "''Ацы категори афтид ма у.''",
'hidden-categories'              => 'Æмбæхст {{PLURAL:$1|категори|категоритæ}}',
'hidden-category-category'       => 'Æмбæхст категоритæ',
'category-subcat-count'          => '{{PLURAL:$2|Ацы категорийы мидæг æрмæст иу дæлкатегори и.|{{PLURAL:$1|$1 дæлкатегори æвдыст у|$1 дæлкатегорийы æвдыст сты}}, æдæппæт $2.}}',
'category-subcat-count-limited'  => 'Ацы категорийы мидæг ис {{PLURAL:$1|$1 дæлкатегори|$1 дæлкатегорийы}}.',
'category-article-count-limited' => 'Ацы категорийы мидæг {{PLURAL:$1|$1 фарс|$1 фарсы}} ис.',
'category-file-count-limited'    => 'Ацы категорийы {{PLURAL:$1|$1 файл|$1 файлы}} ис.',
'listingcontinuesabbrev'         => '(дарддæрдзу)',

'mainpagetext' => "'''Вики-скрипт «MediaWiki» æнтыстджынæй æвæрд æрцыд.'''",

'about'         => 'Афыст',
'article'       => 'Статья',
'newwindow'     => '(ног рудзынджы)',
'cancel'        => 'Фæстæмæ',
'moredotdotdot' => 'Фылдæр…',
'mypage'        => 'Дæхи фарс',
'mytalk'        => 'Дæумæ цы дзурынц',
'anontalk'      => 'Ацы IP-адрисы дискусси',
'navigation'    => 'хъæугæ æрвитæнтæ',
'and'           => '&#32;æмæ',

# Cologne Blue skin
'qbfind'         => 'Агур',
'qbbrowse'       => 'Фен',
'qbedit'         => 'Баив æй',
'qbpageoptions'  => 'Ацы фарс',
'qbpageinfo'     => 'Фарсы контекст',
'qbmyoptions'    => 'Мæ фæрстæ',
'qbspecialpages' => 'Сæрмагонд фæрстæ',

# Vector skin
'vector-view-create'     => 'Скæн æй',
'vector-view-edit'       => 'Баив æй',
'vector-view-history'    => 'Истори',
'vector-view-view'       => 'Кæс',
'vector-view-viewsource' => 'Йæ код фен',

'errorpagetitle'    => 'Рæдыд',
'returnto'          => '$1 фарсмæ раздæх.',
'tagline'           => 'Сæрибар энциклопеди Википедийы æрмæг.',
'help'              => 'Æххуыс',
'search'            => 'агур',
'searchbutton'      => 'агур',
'go'                => 'Статьямæ',
'searcharticle'     => 'Статьямæ',
'history'           => 'Фарсы истори',
'history_short'     => 'Истори',
'info_short'        => 'Информаци',
'printableversion'  => 'Мыхурмæ верси',
'permalink'         => 'Ацы версимæ æрвитæн',
'print'             => 'Мыхуыр',
'edit'              => 'Баив æй',
'create'            => 'Скæн æй',
'editthispage'      => 'Ацы фарс баив',
'create-this-page'  => 'Ацы фарс скæн',
'delete'            => 'Аппар',
'deletethispage'    => 'Аппар æй',
'protect'           => 'Сæхгæн',
'protect_change'    => 'баив',
'protectthispage'   => 'Сæхгæн ацы фарс',
'unprotect'         => 'Мауал хъахъхъæн',
'unprotectthispage' => 'Ацы фарс ивынмæ байгом',
'newpage'           => 'Ног фарс',
'talkpage'          => 'Ацы фарсы тыххæй ныхас',
'talkpagelinktext'  => 'Дискусси',
'specialpage'       => 'Сæрмагонд фарс',
'personaltools'     => 'Мигæнæнтæ',
'postcomment'       => 'Ног хай',
'articlepage'       => 'Фен статья',
'talk'              => 'Дискусси',
'views'             => 'Æркæстытæ',
'toolbox'           => 'мигæнæнтæ',
'userpage'          => 'Ацы архайæджы фарс фен',
'projectpage'       => 'Проекты фарс фен',
'imagepage'         => 'Файлы фарс фен',
'mediawikipage'     => 'Фыстæджы фарс фен',
'templatepage'      => 'Шаблоны фарс фен',
'viewhelppage'      => 'Æххуысы фарс фен',
'categorypage'      => 'Категорийы фарс фен',
'viewtalkpage'      => 'Дискусси фен',
'otherlanguages'    => 'Æндæр æвзæгтыл',
'redirectedfrom'    => '(Рарвыстæуыд ацы статьяйæ: «$1»)',
'redirectpagesub'   => 'Рарвысты фарс',
'lastmodifiedat'    => 'Ацы фарс фæстаг хатт ивд æрцыд: $1, $2.',
'protectedpage'     => 'Æхгæд фарс',
'jumpto'            => 'Тагъд æрвитæнтæ:',
'jumptonavigation'  => 'навигаци',
'jumptosearch'      => 'агуырд',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{grammar:genitive|{{SITENAME}}}} тыххæй',
'aboutpage'            => 'Project:Афыст',
'copyrightpage'        => '{{ns:project}}:Авторы бартæ',
'currentevents'        => 'Ног хабæрттæ',
'currentevents-url'    => 'Project:Xabar',
'disclaimers'          => 'Бæрн нæ исыны тыххæй',
'disclaimerpage'       => 'Project:Бæрн нæ исыны тыххæй',
'mainpage'             => 'Сæйраг фарс',
'mainpage-description' => 'Сæйраг фарс',
'portal'               => 'Архайджыты æхсæнад',
'privacy'              => 'Хибардзинады политикæ',
'privacypage'          => 'Project:Хибардзинады политикæ',

'badaccess-group0' => 'Ацы архайд ракæныны бар дæм нæй.',

'versionrequired' => 'Хъæуы MediaWiki-йы версии $1',

'ok'                  => 'Афтæ уæд!',
'pagetitle'           => '$1 — {{SITENAME}}',
'retrievedfrom'       => 'Ратæдзæн: «$1»',
'youhavenewmessages'  => 'Райстай $1 ($2).',
'newmessageslink'     => 'ног фыстæгтæ',
'newmessagesdifflink' => 'фæстаг ивддзинад',
'editsection'         => 'баив æй',
'editold'             => 'баив æй',
'viewsourceold'       => 'йæ код фен',
'editlink'            => 'баив æй',
'viewsourcelink'      => 'йæ код фен',
'editsectionhint'     => 'Баив æй: $1',
'toc'                 => 'Сæргæндтæ',
'showtoc'             => 'равдис',
'hidetoc'             => 'бамбæхс',
'viewdeleted'         => '$1 фенын дæ фæнды?',
'site-rss-feed'       => '$1 — RSS-уадздзаг',
'site-atom-feed'      => '$1 — Atom-уадздзаг',
'page-rss-feed'       => '$1 — RSS-лæсæн',
'page-atom-feed'      => '$1 — Atom-лæсæн',
'red-link-title'      => '$1 (фыст нæма у)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Статья',
'nstab-user'      => 'Архайæджы фарс',
'nstab-media'     => 'Мультимеди',
'nstab-special'   => 'Сæрмагонд фарс',
'nstab-project'   => 'Проекты тыххæй',
'nstab-image'     => 'Ныв',
'nstab-mediawiki' => 'Фыстаг',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Æххуысы фарс',
'nstab-category'  => 'Категори',

# Main script and global functions
'nosuchspecialpage' => 'Ахæм сæрмагонд фарс нæй',
'nospecialpagetext' => '<strong>Нæй ахæм сæрмагонд фарс.</strong>

Кæс [[Special:SpecialPages|æппæт сæрмагонд фæрсты номхыгъд]].',

# General errors
'error'                => 'Рæдыд',
'databaseerror'        => 'Бæрæггæнæнты базæйы рæдыд',
'readonly'             => 'Бæрæггæнæнты базæ фыссынæн æхгæд у',
'missingarticle-rev'   => '(верси № $1)',
'missingarticle-diff'  => '(хъауджы: $1, $2)',
'internalerror'        => 'Мидæг рæдыд',
'internalerror_info'   => 'Мидæг рæдыд: $1',
'filecopyerror'        => 'Файл «$1» файлмæ «$2» халдихгæнæн нæ разынд.',
'filedeleteerror'      => 'Нæй аппарæн файл «$1».',
'directorycreateerror' => 'Нæй саразæн файлдон «$1».',
'filenotfound'         => 'Нæй ссарæн файл «$1».',
'unexpected'           => 'Æнæмбæлон æмиасад: «$1»=«$2».',
'formerror'            => 'Рæдыд: формæ арвитæн нæй',
'cannotdelete'         => 'Нæй аппарæн файл кæнæ фарс "$1".
Æндæр исчи йæ аппæрста мыййаг.',
'badtitle'             => 'Æнæмбæлон сæргонд',
'viewsource'           => 'Йæ код фен',
'viewsourcefor'        => 'Фарс «$1»',
'protectedpagetext'    => 'Ацы фарс у ивынæй æхгæд.',
'viewsourcetext'       => 'Ацы фарсы код фенæн æмæ халдих кæнæн ис:',
'ns-specialprotected'  => 'Сæрмагонд фæрстæ ({{ns:special}}) баивæн нæй.',

# Virus scanner
'virus-unknownscanner' => 'æнæзонгæ антивирус:',

# Login and logout pages
'welcomecreation'         => '<h2>Æгас цу, $1!</h2><p>Регистрацигонд æрцыдтæ.',
'yourname'                => 'Архайæджы ном:',
'yourpassword'            => 'Пароль:',
'yourpasswordagain'       => 'Дæ пароль иу хатт ма:',
'remembermypassword'      => 'Системæ бахъуыды кæнæд мæ пароль ацы компьютерыл (for a maximum of $1 {{PLURAL:$1|day|days}})',
'yourdomainname'          => 'Дæ домен:',
'login'                   => 'Дæхи бавдис системæйæн',
'nav-login-createaccount' => 'Системæйæн дæхи бавдис',
'userlogin'               => 'Системæйæн дæхи бавдис',
'logout'                  => 'Номсусæг суын',
'userlogout'              => 'Номсусæг су',
'notloggedin'             => 'Системæйæн дæхи нæ бацамыдтай',
'nologin'                 => "Регистрацигонд нæма дæ? '''$1'''.",
'nologinlink'             => 'Регистраци',
'createaccountmail'       => 'адрисмæ гæсгæ',
'badretype'               => 'Дыууæ хатты иу пароль хъуамæ ныффыстаис',
'loginsuccess'            => 'Ныр та Википедийы архайыс $1, зæгъгæ, ахæм номæй.',
'nouserspecified'         => 'Дæхи бацамонын хъæуы: дæ архайæджы ном цы у.',
'wrongpasswordempty'      => 'Пароль афтид уыд. Афтæ нæ баззы, ныффыс-ма исты пароль.',
'mailmypassword'          => 'Рарвит мæм ног пароль',
'noemail'                 => 'Архайæг $1 йæ электрон посты адрис нæ ныууагъта.',
'mailerror'               => 'Фыстæг арвитыны рæдыд: $1',
'emailconfirmlink'        => 'Дæ электронон посты адрис сфидар кæн',
'loginlanguagelabel'      => 'Æвзаг: $1',

# Password reset dialog
'resetpass_text'            => '<!-- Бахæсс дæ текст ам -->',
'oldpassword'               => 'Раздæры пароль:',
'newpassword'               => 'Новый пароль',
'resetpass_forbidden'       => 'Пароль баивæн нæй',
'resetpass-submit-loggedin' => 'Пароль баив',
'resetpass-temp-password'   => 'Рæстæгмæ пароль:',

# Edit page toolbar
'bold_sample'     => 'Ацы текст бæзджын суыдзæн',
'bold_tip'        => 'Бæзджын текст',
'italic_sample'   => 'Курсив',
'italic_tip'      => 'Курсив',
'link_sample'     => 'Æрвитæны текст',
'link_tip'        => 'Мидæг æрвитæн (æндæр статьямæ)',
'extlink_sample'  => 'http://www.example.com æрвитæны текст',
'extlink_tip'     => 'Æддаг æрвитæн (префикс http:// ма рох кæн)',
'headline_sample' => 'Ам сæргонды текст уæд',
'math_sample'     => 'Ныффысс формулæ',
'math_tip'        => 'Математикон формулæ (формат LaTeX)',
'media_tip'       => 'Файлмæ æрвитæн',

# Edit pages
'summary'                => 'Ивддзинæдты мидис:',
'subject'                => 'Темæ/сæргонд:',
'minoredit'              => 'Ай чысыл ивддзинад у.',
'watchthis'              => 'Ацы фарсмæ дæ цæст æрдар',
'savearticle'            => 'Афтæ уæд!',
'preview'                => 'Разæркаст',
'showpreview'            => 'Фен уал æй',
'showlivepreview'        => 'Тагъд разæркаст',
'showdiff'               => 'Цы баивтай ацы тексты, уый фен',
'summary-preview'        => 'Ивддзинады афыст уыдзæн:',
'blockedtitle'           => 'Архайæг хъодыгонд æрцыд',
'blockednoreason'        => 'аххос амынд не ’рцыд',
'accmailtitle'           => 'Пароль рарвыст у.',
'newarticle'             => '(Ног)',
'note'                   => "'''Бафиппай:'''",
'editing'                => 'Ивыс: $1',
'editingsection'         => 'Ивыс $1 (фарсы хай)',
'editconflict'           => 'Ивыны конфликт: $1',
'yourtext'               => 'Дæхи текст',
'longpagewarning'        => "'''РАГФÆДЗАХСТ: Ацы фарсы бæрцуат у $1 килобайты.
Сæ бæрцуат 32 килобайтæй фылдæр кæмæн у, ахæм фæрстæ иуæй-иу браузерты раст нæ зынынц.
Кæд ахæм вариант и, уæд ацы фарсæй цалдæр фарсы скæн.'''",
'templatesused'          => 'Ацы фарсы ис {{PLURAL:$1|шаблон|шаблоны}}:',
'template-protected'     => '(æхгæд)',
'template-semiprotected' => '(æрдæг-æхгæд)',
'edit-conflict'          => 'Иввдзинæдты конфликт.',
'edit-already-exists'    => 'Ног фарс скæнæн нæй. Ахæм фарс ис.',

# History pages
'viewpagelogs'           => 'Ацы фарсæн йæ логтæ равдис',
'nohistory'              => 'Ацы фарсæн иввдзинæдты истори нæй.',
'currentrev'             => 'Нырыккон верси',
'currentrev-asof'        => 'Нырыккон верси $1',
'revisionasof'           => 'Верси $1',
'revision-info'          => 'Верси $1; $2',
'previousrevision'       => '← Раздæры',
'nextrevision'           => 'Ногдæр верси →',
'currentrevisionlink'    => 'Нырыккон верси',
'cur'                    => 'ныр.',
'next'                   => 'фæдылдзог',
'last'                   => 'раздæры',
'page_first'             => 'фыццаг',
'page_last'              => 'фæстаг',
'histlegend'             => 'Куыд æй æмбарын: (нырыккон) = нырыккон версийæ хъауджыдæрдзинад, (раздæры) = раздæры версийæ хъауджыдæрдзинад, Ч = чысыл ивддзинад.',
'history-fieldset-title' => 'Истори фен',
'histfirst'              => 'раздæр',
'histlast'               => 'фæстæдæр',
'historysize'            => '({{PLURAL:$1|1 байт|$1 байты}})',
'historyempty'           => '(афтид)',

# Revision feed
'history-feed-title'          => 'Ивддзинæдты истори',
'history-feed-item-nocomment' => '$1 $2',

# Revision deletion
'rev-deleted-comment' => '(комментарий аппæрст у)',
'rev-deleted-user'    => '(архайæджы ном аппæрст у)',
'rev-deleted-event'   => '(фыст аппæрст у)',
'rev-delundel'        => 'равдис/бамбæхс',
'revisiondelete'      => 'Аппар / рацараз фарсы верситæ',
'pagehist'            => 'Фарсы истори',
'revdelete-summary'   => 'ивддзинады мидис',
'revdelete-uname'     => 'архайæджы ном',

# History merging
'mergehistory-reason' => 'Аххос:',

# Diffs
'difference' => '(Дыууæ верситы ’хсæн хъауджы)',
'lineno'     => 'Рæнхъ $1:',

# Search results
'searchresults'             => 'Цы ссардæуы',
'searchresults-title'       => 'Агуырды фæстиуæг: «$1»',
'titlematches'              => 'Статьяты сæргæндты æмцаутæ',
'notitlematches'            => 'Фæрсты сæргæндты нæй',
'textmatches'               => 'Статьяты æмцаутæ',
'prevn'                     => '{{PLURAL:$1|$1}} фæстæмæ',
'nextn'                     => '{{PLURAL:$1|$1}} размæ',
'viewprevnext'              => 'Фен ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|$2 дзырд|$2 дзырды}})',
'search-redirect'           => '(рарвыст ардыгæй: $1)',
'search-section'            => '(хай $1)',
'search-suggest'            => 'Кæд мыййаг агурыс: $1',
'search-interwiki-caption'  => 'Æфсымæрон проекттæ',
'search-interwiki-more'     => '(фылдæр)',
'search-mwsuggest-enabled'  => 'æмбарынгæнæнтимæ',
'search-mwsuggest-disabled' => 'æнæ æмбарынгæнæнтæ',
'searchall'                 => 'æппæт',
'powersearch'               => 'Сæрмагонд агуырд',
'powersearch-legend'        => 'Сæрмагонд агуырд',
'powersearch-redir'         => 'Рарвыстытæ дæр æвдис',

# Quickbar
'qbsettings'              => 'Навигацион таг',
'qbsettings-none'         => 'Ма равдис',
'qbsettings-fixedleft'    => 'Галиуырдыгæй',
'qbsettings-fixedright'   => 'Рахизырдыгæй',
'qbsettings-floatingleft' => 'Рахизырдыгæй ленккæнгæ',

# Preferences page
'mypreferences'             => 'Æрмадз',
'prefsnologin'              => 'Системæйæн дæхи нæ бацамыдтай',
'changepassword'            => 'Пароль баив',
'skin-preview'              => 'Разæркаст',
'prefs-datetime'            => 'Датæ æмæ рæстæг',
'prefs-watchlist'           => 'Дæ цæст кæмæ дарыс, уыцы фæрсты номхыгъд',
'prefs-watchlist-days'      => 'Цал боны ивддзинæдтæ æвдисын:',
'saveprefs'                 => 'Афтæ уæд',
'rows'                      => 'Рæнхътæ:',
'timezonelegend'            => 'Сахаты таг:',
'localtime'                 => 'Бынæттон рæстæг:',
'timezoneoffset'            => 'Хъауджыдæрдзинад',
'servertime'                => 'Серверы рæстæг:',
'timezoneregion-africa'     => 'Африкæ',
'timezoneregion-america'    => 'Америкæ',
'timezoneregion-antarctica' => 'Антарктикæ',
'timezoneregion-arctic'     => 'Арктикæ',
'timezoneregion-asia'       => 'Ази',
'timezoneregion-australia'  => 'Австрали',
'timezoneregion-europe'     => 'Европæ',
'timezoneregion-indian'     => 'Индийы фурд',
'youremail'                 => 'Дæ электронон посты адрис',
'username'                  => 'Регистрацигонд ном:',
'yourrealname'              => 'Дæ æцæг ном*',
'yourlanguage'              => 'Техникон фыстыты æвзаг:',
'yourvariant'               => 'Æвзаджы вариант:',
'yournick'                  => 'Фæсномыг (къухæрфыстытæм):',
'badsiglength'              => 'Æгæр даргъ къухæрфыст, хъуамæ {{PLURAL:$1|дамгъæйæ|дамгъæйæ}} къаддæр уа.',
'yourgender'                => 'Æрд:',
'gender-male'               => 'нæлгоймаг',
'gender-female'             => 'сылгоймаг',
'email'                     => 'Эл. посты адрис',
'prefs-help-email-required' => 'Электронон посты адрис хъæуы.',

# User rights
'userrights-nodatabase' => 'Бæрæггæнæнты базæ $1 нæй кæнæ уый у бынæттон базæ.',

# Groups
'group'            => 'Къорд:',
'group-user'       => 'Архайджытæ',
'group-bot'        => 'Роботтæ',
'group-sysop'      => 'Админтæ',
'group-bureaucrat' => 'Бюрократтæ',
'group-all'        => '(æппæт)',

'group-user-member'       => 'архайæг',
'group-bot-member'        => 'робот',
'group-sysop-member'      => 'дьаһабыл',
'group-bureaucrat-member' => 'бюрократ',

'grouppage-user'       => '{{ns:project}}:Архайджытæ',
'grouppage-bot'        => '{{ns:project}}:Роботтæ',
'grouppage-sysop'      => '{{ns:project}}:Админтæ',
'grouppage-bureaucrat' => '{{ns:project}}:Бюрократтæ',

# Rights
'right-read'          => 'фæрстæ кæсын',
'right-edit'          => 'фæрстæ ивын',
'right-move'          => 'фæрсты нæмттæ ивын',
'right-move-subpages' => 'фæрсты æмæ сæ дæлфæрсты нæмттæ ивын',
'right-movefile'      => 'файлты нæмттæ ивын',
'right-upload'        => 'файлтæ сæвæрын',
'right-upload_by_url' => 'интернет-адрисæй файлтæ сæвæрын',
'right-delete'        => 'фæрстæ аппарын',
'right-bigdelete'     => 'фæрстæ æмæ сæ ивды истори аппарын',

# User rights log
'rightsnone' => '(нæй)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'       => 'ацы фарс кæсын',
'action-edit'       => 'ацы фарс ивын',
'action-createpage' => 'фæрстæ скæн',
'action-move'       => 'ацы фарсы ном ивын',
'action-movefile'   => 'ацы файлы ном ивын',
'action-delete'     => 'ацы фарс аппарын',

# Recent changes
'nchanges'           => '$1 {{PLURAL:$1|ивдзинад|ивддзинады}}',
'recentchanges'      => 'Фæстаг ивддзинæдтæ',
'recentchangestext'  => 'Ацы фарсыл ирон Википедийы фæстаг ивддзинæдтæ фенæн ис.',
'rcnote'             => 'Дæлдæр нымад сты афæстаг <strong>$2</strong> боны дæргъы конд <strong>{{PLURAL:$1|иу ивддзинад|$1 ивддзинады}}</strong>, $5, $4 уавæрмæ гæсгæ.',
'rcshowhideminor'    => '$1 чысыл ивддзинæдтæ',
'rcshowhidebots'     => '$1 роботты куыст',
'rcshowhidemine'     => '$1, дæхæдæг цы ивддзинæдтæ скодтай, уыдон',
'rclinks'            => 'Фæстаг $1 ивддзинæдтæ (афæстаг $2 боны дæргъы чи ’рцыдысты) равдис;
$3',
'diff'               => 'хицæн.',
'hist'               => 'лог',
'hide'               => 'бамбæхс',
'show'               => 'Равдис',
'minoreditletter'    => 'ч',
'newpageletter'      => 'Н',
'boteditletter'      => 'б',
'rc-enhanced-expand' => 'Лыстæгдзинæдтæ равдис (JavaScript хъæуы)',
'rc-enhanced-hide'   => 'Лыстæгдзинæдтæ бамбæхс',

# Recent changes linked
'recentchangeslinked'         => 'Баст ивддзинæдтæ',
'recentchangeslinked-feed'    => 'Баст ивддзинæдтæ',
'recentchangeslinked-toolbox' => 'Баст ивддзинæдтæ',
'recentchangeslinked-page'    => 'Фарсы ном:',

# Upload
'upload'              => 'Ног файл сæвæр',
'uploadbtn'           => 'Ног файл сæвæр',
'uploadnologin'       => 'Системæйæн дæхи нæ бацамыдтай',
'uploaderror'         => 'Файл сæвæрыны рæдыд',
'filename'            => 'Файлы ном',
'minlength1'          => 'Файлы номы хъуамæ æппынкъаддæр иу дамгъæ уа.',
'savefile'            => 'Бавæр æй',
'uploadvirus'         => 'Файлы разынд вирус! Кæс $1',
'watchthisupload'     => 'Ацы файлмæ дæ цæст æрдар',
'upload-success-subj' => 'Файлы сæвæрд фæрæстмæ',

'upload-file-error' => 'Мидæг рæдыд',

# Special:ListFiles
'listfiles' => 'Нывты номхыгъд',

# File description page
'file-anchor-link'  => 'Ныв',
'filehist'          => 'Файлы истори',
'filehist-current'  => 'нырыккон',
'filehist-datetime' => 'Датæ/рæстæг',
'filehist-user'     => 'Архайæг',
'filehist-filesize' => 'Файлы бæрцуат',
'filehist-comment'  => 'Фиппаинаг',
'imagelinks'        => 'Æрвитæнтæ файлмæ',
'linkstoimage'      => 'Ацы нывæй пайда {{PLURAL:$1|кæны иу фарс|кæнынц ахæм фæрстæ}}:',

# File deletion
'filedelete-submit'           => 'Аппар',
'filedelete-otherreason'      => 'Æндæр кæнæ уæлæмхасæн аххос:',
'filedelete-reason-otherlist' => 'Æндæр аххос',

# MIME search
'download' => 'æрбавгæн',

# Unused templates
'unusedtemplates' => 'Пайда кæмæй нæ чындæуы, ахæм шаблонтæ',

# Random page
'randompage' => 'Æнæбары æвзæрст фарс',

# Statistics
'statistics'              => 'Статистикæ',
'statistics-header-users' => 'Архайджыты статистикæ',

'brokenredirects-edit'   => 'баив æй',
'brokenredirects-delete' => 'аппар',

'withoutinterwiki-submit' => 'Равдис',

'fewestrevisions' => 'Къаддæр кæй ивынц, ахæм фæрстæ',

# Miscellaneous special pages
'nbytes'                 => '$1 {{PLURAL:$1|байт|байты}}',
'nlinks'                 => '$1 {{PLURAL:$1|æрвитæн|æрвитæны}}',
'nviews'                 => '$1 {{PLURAL:$1|æркаст|æркасты}}',
'lonelypages'            => 'Сидзæр фæрстæ',
'uncategorizedpages'     => 'Æнæкатегори фæрстæ',
'uncategorizedimages'    => 'Æнæкатегори файлтæ',
'uncategorizedtemplates' => 'Æнæкатегори шаблонтæ',
'popularpages'           => 'Популярон фæрстæ',
'wantedcategories'       => 'Хъæугæ категоритæ',
'wantedpages'            => 'Хъæугæ фæрстæ',
'mostlinked'             => 'Фылдæр æрвитæнтæ кæмæ и, ахæм фæрстæ',
'mostlinkedcategories'   => 'Фылдæр æрвитæнтæ кæмæ и, уыцы категоритæ',
'mostrevisions'          => 'Фылдæр кæй ивынц, ахæм фæрстæ',
'shortpages'             => 'Цыбыр фæрстæ',
'longpages'              => 'Даргъ фæрстæ',
'protectedpages'         => 'Æхгæд фæрстæ',
'listusers'              => 'Архайджыты номхыгъд',
'newpages'               => 'Ног фæрстæ',
'newpages-username'      => 'Архайæг:',
'ancientpages'           => 'Зæронддæр фæрстæ',
'move'                   => 'Ном баив',
'pager-newer-n'          => '{{PLURAL:$1|фæстæдæр иу|фæстæдæр $1}}',
'pager-older-n'          => '{{PLURAL:$1|раздæр иу|раздæр $1}}',

# Book sources
'booksources-go' => 'Агур',

# Special:Log
'specialloguserlabel'  => 'Архайæг:',
'speciallogtitlelabel' => 'Сæргонд:',
'log'                  => 'Логтæ',

# Special:AllPages
'allpages'       => 'Æппæт фæрстæ',
'alphaindexline' => '$1 (уыдоны ’хсæн цы статьятæ ис, фен) $2',
'nextpage'       => 'Фæдылдзог фарс ($1)',
'prevpage'       => 'Раздæры фарс ($1)',
'allarticles'    => 'Æппæт статьятæ',
'allpagesprev'   => 'фæстæмæ',
'allpagesnext'   => 'дарддæр',
'allpagessubmit' => 'Агур',

# Special:Categories
'categories'                    => 'Категоритæ',
'categoriespagetext'            => 'Мæнæ ахæм категоритæ ирон Википедийы ис.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',
'special-categories-sort-count' => 'нымæцмæ гæсгæ равæр',
'special-categories-sort-abc'   => 'алфавитмæ гæсгæ равæр',

# Special:LinkSearch
'linksearch'    => 'Æддаг æрвитæнтæ',
'linksearch-ok' => 'Агур',

# Special:ListUsers
'listusers-submit' => 'Равдис',

# Special:Log/newusers
'newuserlog-create-entry' => 'Ног архайæджы аккаунт',

# Special:ListGroupRights
'listgrouprights-group'   => 'Къорд',
'listgrouprights-members' => '(уæнгты номхыгъд)',

# E-mail user
'mailnologintext' => 'Фыстæгтæ æрвитынмæ хъуамæ [[Special:UserLogin|системæйæн дæхи бавдисай]] æмæ дæ бæлвырд электронон посты адрис [[Special:Preferences|ныффыссай]].',
'emailuser'       => 'Ацы архайæгæн электронон фыстæг рарвитт',
'emailpage'       => 'Электронон фыстæг йæм барвит',

# Watchlist
'watchlist'         => 'Цæстдарды номхыгъд',
'mywatchlist'       => 'Дæ цæст кæмæ дарыс, уыцы фæрстæ',
'nowatchlist'       => 'Иу статьямæ дæр дæ цæст нæ дарыс.',
'watchnologin'      => 'Системæйæн дæхи нæ бацамыдтай',
'watchnologintext'  => 'Ацы номхыгъд ивынмæ [[Special:UserLogin|хъуамæ дæхи бацамонай системæйæн]].',
'addedwatch'        => 'Дæ цæст кæмæ дарыс, уыцы статьятæм бафтыд.',
'removedwatch'      => 'Нал дарыс дæ цæст',
'removedwatchtext'  => 'Фарсмæ «[[:$1]]» нал [[Special:Watchlist|дарыс дæ цæст]].',
'watch'             => 'Дæ цæст æрдар',
'watchthispage'     => 'Ацы фарсмæ дæ цæст æрдар',
'unwatch'           => 'Мауал дæ цæст дар',
'watchlist-details' => '$1 фарсмæ дæ цæст дарыс, дискусситы фæстæмæ.',
'watchlistcontains' => 'Дæ цæст $1 фарсмæ дарыс.',
'wlnote'            => "Дæлæ афæстаг '''$2 сахаты дæргъы''' цы $1 {{PLURAL:$1|ивддзинад|ивддзинады}} æрцыди.",
'wlshowlast'        => 'Фæстæг $1 сахаты, $2 боны дæргъы; $3.',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Цæстдард фæрсты номхыгъдмæ афтауын...',
'unwatching' => 'Цæстдард фæрсты номхыгъдæй аиуварс кæнын...',

# Delete
'deletepage'            => 'Фарс аппар',
'exblank'               => 'фарс афтид уыдис',
'dellogpage'            => 'Аппарыны лог',
'deletionlog'           => 'аппарыны лог',
'deletecomment'         => 'Аххос:',
'deleteotherreason'     => 'Æндæр кæнæ уæлæмхасæн аххос:',
'deletereasonotherlist' => 'Æндæр аххос',

# Protect
'protectedarticle'       => '«[[$1]]» — фарс æхгæд æрцыд',
'protectcomment'         => 'Аххос:',
'protectexpiry'          => 'Кæдмæ æхгæд у:',
'protect-level-sysop'    => 'Æрмæст админтæ',
'protect-othertime'      => 'Æндæр рæстæг:',
'protect-othertime-op'   => 'æндæр рæстæг',
'protect-otherreason'    => 'Æндæр аххос/уæлæмхасæн:',
'protect-otherreason-op' => 'Æндæр аххос',
'restriction-type'       => 'Бартæ:',

# Restrictions (nouns)
'restriction-edit' => 'Ивын',

# Namespace form on various pages
'namespace'      => 'Нæмтты тыгъдад:',
'blanknamespace' => '(Сæйраг)',

# Contributions
'contributions'       => 'Йæ бавæрд',
'contributions-title' => 'Архайæджы бавæрд: $1',
'mycontris'           => 'Дæ бавæрд',
'uctop'               => '(уæле баззад)',

'sp-contributions-blocklog' => 'Хъодыты лог',
'sp-contributions-talk'     => 'Дискусси',

# What links here
'whatlinkshere'            => 'Цавæр æрвитæнтæ цæуынц ардæм',
'whatlinkshere-title'      => '«$1»: цавæр фæрстæ æрвитынц ардæм',
'whatlinkshere-page'       => 'Фарс:',
'isredirect'               => 'рарвысты фарс',
'isimage'                  => 'фарсы мидæг ныв',
'whatlinkshere-next'       => '{{PLURAL:$1|фæдылдзог|фæдылдзог $1}}',
'whatlinkshere-links'      => '← æрвитæнтæ',
'whatlinkshere-hideredirs' => '$1 рарвыстытæ',
'whatlinkshere-hidelinks'  => '$1 æрвитæнтæ',
'whatlinkshere-filters'    => 'Фильтртæ',

# Block/unblock
'blockip'                => 'Бахъоды кæн',
'blockip-legend'         => 'Бахъоды æй кæн',
'ipbreason'              => 'Аххос:',
'ipbreasonotherlist'     => 'Æндæр аххос',
'ipbotherreason'         => 'Æндæр кæнæ уæлæмхасæн аххос:',
'ipb-blocklist'          => 'Актуалон хъоды равдис',
'ipb-blocklist-contribs' => '$1, зæгъгæ, уыцы архайæджы бавæрд',
'ipblocklist'            => 'Хъодыгонд IP-адристæ æмæ архайджыты нæмттæ',
'ipblocklist-legend'     => 'Хъодыгонд архайæджы ацагур',
'ipblocklist-empty'      => 'Хъодыгæндты номхыгъд афтид у.',
'blocklink'              => 'бахъоды кæн',
'unblocklink'            => 'хъоды айс',
'contribslink'           => 'бавæрд',
'blocklogpage'           => 'Хъодыты лог',

# Developer tools
'lockdbsuccesssub'    => 'Бæрæггæнæнты базæ æхгæд у',
'unlockdbsuccesssub'  => 'Бæрæггæнæнты базæ æхгæд нал у',
'unlockdbsuccesstext' => 'Бæрæггæнæнты базæ æхгæд нал у.',
'databasenotlocked'   => 'Бæрæггæнæнты базæ æхгæд не ’рцыд.',

# Move page
'movearticle'              => 'Статьяйы ном баив',
'movenologin'              => 'Системæйæн дæхи нæ бацамыдтай',
'newtitle'                 => 'Ног ном',
'move-watch'               => 'Ацы фарсмæ дæ цæст æрдар',
'movepagebtn'              => 'Фарсы ном баив',
'move-redirect-suppressed' => 'рарвысты фарс нæ бахъуыд',
'movereason'               => 'Аххос:',

# Namespace 8 related
'allmessages' => 'Æппæт техникон фыстытæ',

# Thumbnails
'thumbnail-more' => 'Фестырдæр кæн',

# Special:Import
'importnotext'          => 'Афтид у кæнæ текст дзы нæй',
'importuploaderrortemp' => 'Импорты файл æрвитын нæ фæрæстмæ. Нæй рæстæгмæ файлдон.',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Мæхи фарс (дæу тыххæй ам ныффысс)',
'tooltip-pt-mytalk'              => 'Æндæр ахрхайджытæ мын цы дзурынц',
'tooltip-pt-preferences'         => 'Википеди куыд кусы, уый срæвдз кæн дæ хъæуындзинæдтæм гæсгæ',
'tooltip-pt-watchlist'           => 'Дæ цæст кæмæ дарыс, уыцы фæрсты номхыгъд',
'tooltip-pt-mycontris'           => 'Цы у мæ бавæрд',
'tooltip-pt-login'               => 'Системæмæ дæхи бацамонай, кæд æцæг дæ хæс нæу, уæддæр',
'tooltip-pt-logout'              => 'Регистрацигонд сеансæй рацу',
'tooltip-ca-talk'                => 'Фарсыл цы фыст у, уый тыххæй дискусси',
'tooltip-ca-edit'                => 'Ацы фарс дæ бон у ивын. Дæ хорзæхæй, «Фен уал æй» джыбыйæ пайда кæн',
'tooltip-ca-viewsource'          => 'Ацы фарс ивынæй æхгæд у, фæлæ йæ код фенæн и',
'tooltip-ca-history'             => 'Ацы фарсæн йæ раздæры верситæ (ивыны лог)',
'tooltip-ca-protect'             => 'Ацы фарс ивддзинæдтæй сæхгæн',
'tooltip-ca-delete'              => 'Аппар ацы фарс',
'tooltip-ca-move'                => 'Ацы статьяйы ном баив',
'tooltip-ca-watch'               => 'Дæ цæст кæмæ дарыс, уыцы фæрсты номхыгъдмæ бафтау',
'tooltip-search'                 => 'Ацы дзырд агур',
'tooltip-search-go'              => 'Кæд ахæм ном исты статьямæ и, уыцы статья равдис',
'tooltip-search-fulltext'        => 'Ацы текст цы фæрсты и, уыдон бацагур',
'tooltip-n-mainpage'             => 'Сæйраг фарсмæ рацу',
'tooltip-n-mainpage-description' => 'Сæйраг фарсмæ',
'tooltip-n-portal'               => 'Проекты тыххæй æмæ, дæу цы бон у, уый тыххæй дæр',
'tooltip-n-recentchanges'        => 'Чи æмæ цавæр статьяты баивта',
'tooltip-n-randompage'           => 'Æнæбары æвзæрст фарс фен',
'tooltip-n-help'                 => 'Кæд цыдæртæ нæ бамбæрстай',
'tooltip-t-whatlinkshere'        => 'Ацы фарсмæ чи ’рвитынц, ахæм фæрсты номхыгъд',
'tooltip-t-recentchangeslinked'  => 'Ацы фарс кæмæ æрвиты, уыцы фæрсты фæстаг ивддзинæдтæ',
'tooltip-t-contributions'        => 'Ацы архайæг цы фæрстæ ивта, уый номхыгъд фен',
'tooltip-t-upload'               => 'Нывтæ кæнæ мультимедиа-файлтæ бавæр',
'tooltip-t-specialpages'         => 'Сæрмагонд фæрсты номхыгъд',
'tooltip-t-print'                => 'Ацы фарс мыхуырмæ цæттæ форматы',
'tooltip-t-permalink'            => 'Фарсы ацы версимæ æрвитæн (фæрстæ ивынц, ацы верси — никуы)',
'tooltip-ca-nstab-user'          => 'Архайæджы фарс фен',
'tooltip-ca-nstab-project'       => 'Проекты фарс',
'tooltip-ca-nstab-image'         => 'Нывы фарс',
'tooltip-ca-nstab-category'      => 'Категорийы фарс',
'tooltip-minoredit'              => 'Чысыл ивддзинад у',
'tooltip-save'                   => 'Цы ивддзинæдтæ бахастай, уыдон бавæр',
'tooltip-diff'                   => 'Раздæры версимæ абаргæйæ цы ивддзинæдтæ хæссыс текстмæ, уый фен',
'tooltip-watch'                  => 'Ацы фарс, дæ цæст кæмæ дарыс, уыцы фæрсты номхыгъдмæ бафтау',

# Attribution
'others' => 'æндæртæ',

# Spam protection
'spamprotectiontitle' => 'Спамы ныхмæ фильтр',

# Skin names
'skinname-standard'    => 'Стандартон',
'skinname-nostalgia'   => 'Æнкъард',
'skinname-cologneblue' => 'Кёльны æрхæндæг',
'skinname-monobook'    => 'Моно-чиныг',
'skinname-myskin'      => 'Мæхи',
'skinname-chick'       => 'Карк',

# Math errors
'math_unknown_function' => 'нæзонгæ функци',
'math_syntax_error'     => 'синтаксисы рæдыд',

# Browsing diffs
'previousdiff' => '← Раздæры ивддзинад',
'nextdiff'     => 'Фæстæдæр ивддзинад →',

# Media information
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|фарс|фарсы}}',

# Special:NewFiles
'newimages'    => 'Ног нывты галерей',
'showhidebots' => '(роботты куыст $1)',
'ilsubmit'     => 'Агур',
'bydate'       => 'рæстæгмæ гæсгæ',

# Metadata
'metadata'        => 'Метабæрæггæнæнтæ',
'metadata-expand' => 'Фылдæр детальтæ равдис',

# EXIF tags
'exif-imagewidth'  => 'Уæрх',
'exif-imagelength' => 'Бæрзæнд',
'exif-artist'      => 'Чи йæ систа',

'exif-gaincontrol-0' => 'Нæй',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'æппæт',
'imagelistall'     => 'æппæт',
'watchlistall2'    => 'æппæт',
'namespacesall'    => 'æппæт',
'monthsall'        => 'æппæт',

# action=purge
'confirm_purge_button' => 'Афтæ уæд!',

# Multipage image navigation
'imgmultipageprev' => '← раздæры фарс',
'imgmultipagenext' => 'фæдылдзог фарс →',
'imgmultigo'       => 'Афтæ бакæн!',

# Table pager
'table_pager_next'  => 'Фæдылдзог фарс',
'table_pager_prev'  => 'Раздæры фарс',
'table_pager_first' => 'Фыццаг фарс',
'table_pager_last'  => 'Фæстаг фарс',

# Size units
'size-bytes'     => '$1 байт(ы)',
'size-kilobytes' => '$1 КБ',
'size-megabytes' => '$1 МБ',
'size-gigabytes' => '$1 ГБ',

# Live preview
'livepreview-failed' => 'Тагъд разæркастæй пайда кæнæн нæй. Хуымæтæджы разæркастæй пайда кæн.',

# Watchlist editor
'watchlistedit-noitems'      => 'Иу фарсмæ дæр нæ дарыс дæ цæст, ацы номхыгъд афтид у.',
'watchlistedit-normal-title' => 'Дæ цæст кæмæ дарыс, уыцы фæрсты номхыгъд ивыс',

# Watchlist editing tools
'watchlisttools-view' => 'Баст ивддзинæдтæ фен',
'watchlisttools-edit' => 'Номхыгъд фен/баив',

# Special:Version
'version'                  => 'MediaWiki-йы верси',
'version-version'          => '(Верси $1)',
'version-software-version' => 'Верси',

# Special:SpecialPages
'specialpages' => 'Сæрмагонд фæрстæ',

);
