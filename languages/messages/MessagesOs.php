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
 * @author Amire80
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
'tog-rememberpassword'        => 'Системæ бахъуыды кæнæд мæ пароль ацы компьютерыл ($1 бонæй къаддæр æмгъуыдмæ)',
'tog-watchcreations'          => 'Æз цы фæрстæ райдайын, уыдонмæ мæ цæст дарын мæ фæнды',
'tog-watchdefault'            => 'Æз цы фæрстæ ивын, уыдонмæ мæ цæст дарын мæ фæнды',
'tog-watchmoves'              => 'Æз цы фæрсты нæмттæ ивын, уыдонмæ мæ цæст дарын мæ фæнды',
'tog-watchdeletion'           => 'Æз цы фæрстæ аппарын, уыдонмæ мæ цæст дарын мæ фæнды',
'tog-minordefault'            => 'Æппæт ивддзинæдтæ банысан кæн куыд чысылтæ',
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
'sun'           => 'Хцб',
'mon'           => 'Крс',
'tue'           => 'Дцг',
'wed'           => 'Æрт',
'thu'           => 'Цпр',
'fri'           => 'Мрб',
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
'category-article-count'         => '{{PLURAL:$2|Ацы категорийы æрмæст иу фарс и.|Ацы категорийы $2 фарсæй {{PLURAL:$1|æвдыст у $1.|æвдыст сты $1 фарсы.}}}}',
'category-article-count-limited' => 'Ацы категорийы мидæг {{PLURAL:$1|$1 фарс|$1 фарсы}} ис.',
'category-file-count-limited'    => 'Ацы категорийы {{PLURAL:$1|$1 файл|$1 файлы}} ис.',
'listingcontinuesabbrev'         => '(дарддæрдзу)',
'index-category'                 => 'Индексгонд фæрстæ',

'mainpagetext' => "'''Вики-скрипт «MediaWiki» æнтыстджынæй æвæрд æрцыд.'''",

'about'         => 'Афыст',
'article'       => 'Статья',
'newwindow'     => '(ног рудзынджы)',
'cancel'        => 'Фæстæмæ',
'moredotdotdot' => 'Фылдæр…',
'mypage'        => 'Дæхи фарс',
'mytalk'        => 'Мæ дискусси',
'anontalk'      => 'Ацы IP-адрисы дискусси',
'navigation'    => 'Навигаци',
'and'           => '&#32;æмæ',

# Cologne Blue skin
'qbfind'         => 'Агур',
'qbbrowse'       => 'Фен',
'qbedit'         => 'Ивын',
'qbpageoptions'  => 'Ацы фарс',
'qbpageinfo'     => 'Фарсы контекст',
'qbmyoptions'    => 'Мæ фæрстæ',
'qbspecialpages' => 'Сæрмагонд фæрстæ',
'faq'            => 'FAQ',

# Vector skin
'vector-action-addsection' => 'Нæуæг ныхас бакæн',
'vector-action-delete'     => 'Аппар',
'vector-action-move'       => 'Ном ивын',
'vector-action-protect'    => 'Сæхгæн',
'vector-view-create'       => 'Скæн æй',
'vector-view-edit'         => 'Ивын',
'vector-view-history'      => 'Истори',
'vector-view-view'         => 'Фæрсын',
'vector-view-viewsource'   => 'Йæ код фен',
'actions'                  => 'Архайдтæ',
'namespaces'               => 'Нæмтты тыгъдæдттæ',
'variants'                 => 'Варианттæ',

'errorpagetitle'    => 'Рæдыд',
'returnto'          => '$1 фарсмæ раздæхын.',
'tagline'           => 'Сæрибар энциклопеди Википедийы æрмæг.',
'help'              => 'Æххуыс',
'search'            => 'Агуырд',
'searchbutton'      => 'Агур',
'go'                => 'Статьямæ',
'searcharticle'     => 'Агур',
'history'           => 'Фарсы истори',
'history_short'     => 'Истори',
'info_short'        => 'Информаци',
'printableversion'  => 'Мыхурмæ верси',
'permalink'         => 'Ацы версимæ æрвитæн',
'print'             => 'Мыхуыр',
'edit'              => 'Ивын',
'create'            => 'Скæн æй',
'editthispage'      => 'Ивын ацы фарс',
'create-this-page'  => 'Ацы фарс скæнын',
'delete'            => 'Аппар',
'deletethispage'    => 'Аппарын ацы фарс',
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
'toolbox'           => 'Фæрæзтæ',
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
'copyright'            => 'Лицензи: $1.',
'copyrightpage'        => '{{ns:project}}:Авторы бартæ',
'currentevents'        => 'Ног хабæрттæ',
'currentevents-url'    => 'Project:Xabar',
'disclaimers'          => 'Бæрн нæ исыны тыххæй',
'disclaimerpage'       => 'Project:Нæ бæрн исыны тыххæй',
'edithelp'             => 'Ивын æххуыс',
'edithelppage'         => 'Help:Ивд',
'helppage'             => 'Help:Сæргæндтæ',
'mainpage'             => 'Сæйраг фарс',
'mainpage-description' => 'Сæйраг фарс',
'portal'               => 'Архайджыты æхсæнад',
'portal-url'           => 'Project:Архайджыты æхсæнад',
'privacy'              => 'Хибардзинады политикæ',
'privacypage'          => 'Project:Хибардзинады политикæ',

'badaccess-group0' => 'Ацы архайд ракæныны бар дæм нæй.',

'versionrequired' => 'Хъæуы MediaWiki-йы версии $1',

'ok'                  => 'Афтæ уæд!',
'pagetitle'           => '$1 — {{SITENAME}}',
'retrievedfrom'       => 'Ратæдзæн: «$1»',
'youhavenewmessages'  => 'Райстай $1 ($2).',
'newmessageslink'     => 'ног фыстæгтæ',
'newmessagesdifflink' => 'фæстаг ивд',
'editsection'         => 'ивын',
'editold'             => 'ивын',
'viewsourceold'       => 'йæ код фен',
'editlink'            => 'ивын',
'viewsourcelink'      => 'йæ код фен',
'editsectionhint'     => 'Ив хай: $1',
'toc'                 => 'Сæргæндтæ',
'showtoc'             => 'равдис',
'hidetoc'             => 'бамбæхсын',
'viewdeleted'         => '$1 фенын дæ фæнды?',
'site-rss-feed'       => '$1 — RSS-уадздзаг',
'site-atom-feed'      => '$1 — Atom-уадздзаг',
'page-rss-feed'       => '$1 — RSS-лæсæн',
'page-atom-feed'      => '$1 — Atom-лæсæн',
'red-link-title'      => '$1 (фыст нæу)',

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
'missingarticle-rev'   => '(фæлтæр № $1)',
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
'viewsource'           => 'Код кæсын',
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
'remembermypassword'      => 'Системæ бахъуыды кæнæд мæ пароль ацы браузеры ($1 бонæй къаддæр æмгъуыдмæ)',
'yourdomainname'          => 'Дæ домен:',
'login'                   => 'Бахизын',
'nav-login-createaccount' => 'Системæйæн дæхи бавдис',
'userlogin'               => 'Бахизын / регистраци кæнын',
'logout'                  => 'Номсусæг суын',
'userlogout'              => 'Рахизын',
'notloggedin'             => 'Системæйæн дæхи нæ бацамыдтай',
'nologin'                 => "Регистрацигонд нæма дæ? '''$1'''.",
'nologinlink'             => 'Регистраци',
'createaccount'           => 'Регистраци кæнын',
'gotaccount'              => 'Регистрацигонд дæ? $1.',
'gotaccountlink'          => 'Бахизын',
'createaccountmail'       => 'адрисмæ гæсгæ',
'badretype'               => 'Дыууæ хатты иу пароль хъуамæ ныффыстаис',
'loginsuccess'            => 'Ныр та Википедийы архайыс $1, зæгъгæ, ахæм номæй.',
'nouserspecified'         => 'Дæхи бацамонын хъæуы: дæ архайæджы ном цы у.',
'wrongpassword'           => 'Цы пароль ныффыстай, уый раст нæу.
Лæмбынæгæй ныффысс дæ пароль',
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
'headline_sample' => 'Ам сæргонд текст уæд',
'math_sample'     => 'Ныффысс формулæ',
'math_tip'        => 'Математикон формулæ (формат LaTeX)',
'nowiki_sample'   => 'Батысс нæформатгонд текст ардæм',
'image_tip'       => 'Æфтыд файл',
'media_tip'       => 'Файлмæ æрвитæн',
'sig_tip'         => 'Дæ ырфыст рæстæгимæ',

# Edit pages
'summary'                          => 'Ивдтыты мидис:',
'subject'                          => 'Темæ/сæргонд:',
'minoredit'                        => 'Ай чысыл ивд у.',
'watchthis'                        => 'Цæст дарын ацы фарсмæ',
'savearticle'                      => 'Фæивын',
'preview'                          => 'Разæркаст',
'showpreview'                      => 'Разбакаст равдисын',
'showlivepreview'                  => 'Тагъд разæркаст',
'showdiff'                         => 'Ивд æвдисын',
'summary-preview'                  => 'Ивд афыст уыдзæн:',
'blockedtitle'                     => 'Архайæг хъодыгонд æрцыд',
'blockednoreason'                  => 'аххос амынд не ’рцыд',
'whitelistedittitle'               => 'Ацы текст ивынмæ хъуамæ дæхи бацамонай системæйæн',
'loginreqtitle'                    => 'Хъуамæ дæхи бацамонай',
'accmailtitle'                     => 'Пароль рарвыст у.',
'newarticle'                       => '(Ног)',
'noarticletext'                    => 'Ацы фарсы ныма текст нæй.
Дæ бон у [[Special:Search/{{PAGENAME}}|бацагурын ацы фарсы ном]] æндæр фæрсты,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} агурын хæстæг логты],
кæнæ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ивын ацы фарс]</span>.',
'noarticletext-nopermission'       => 'Ацы фарсы ныма текст нæй.
Дæ бон у [[Special:Search/{{PAGENAME}}|бацагурын ацы фарсы ном]] æндæр фæрсты
кæнæ <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} агурын хæстæг логты]</span>.',
'note'                             => "'''Бафиппай:'''",
'editing'                          => 'Ивыс: $1',
'editingsection'                   => 'Ивыс $1 (фарсы хай)',
'editconflict'                     => 'Ивыны конфликт: $1',
'yourtext'                         => 'Дæхи текст',
'yourdiff'                         => 'Хицæндзинæдтæ',
'templatesused'                    => 'Ацы фарсы ис {{PLURAL:$1|шаблон|шаблоны}}:',
'template-protected'               => '(æхгæд)',
'template-semiprotected'           => '(æрдæг-æхгæд)',
'permissionserrorstext-withaction' => 'Нæй дын бар $2 {{PLURAL:$1|ай|адон}} тыххæй:',
'edit-conflict'                    => 'Иввдзинæдты конфликт.',
'edit-already-exists'              => 'Ног фарс скæнæн нæй. Ахæм фарс ис.',

# History pages
'viewpagelogs'           => 'Ацы фарсæн йæ логтæ равдис',
'nohistory'              => 'Ацы фарсæн иввдзинæдты истори нæй.',
'currentrev'             => 'Нырыккон фæлтæр',
'currentrev-asof'        => 'Нырыккон фæлтæр $1',
'revisionasof'           => 'Фæлтæр $1',
'revision-info'          => 'Фæлтæр $1; $2',
'previousrevision'       => '← Раздæры',
'nextrevision'           => 'Ногдæр фæлтæр →',
'currentrevisionlink'    => 'Нырыккон фæлтæр',
'cur'                    => 'ныр.',
'next'                   => 'фæдылдзог',
'last'                   => 'раздæры',
'page_first'             => 'фыццаг',
'page_last'              => 'фæстаг',
'histlegend'             => "Æвзарынæн: банысан кæн фæлтæрты радиобокстæ цæмæй цæ иртæсай æмæ стæй ныххæц enter кæнæ бынæй цы ныххæцæн ис, ууыл.<br />
Легендæ: '''({{int:cur}})''' = иртæсæн фæстаг фæлтæримæ, '''({{int:last}})''' = иртæсæн разфæлтæримæ, '''{{int:minoreditletter}}''' = чысыл ивд.",
'history-fieldset-title' => 'Истори фен',
'history-show-deleted'   => 'Æрмæстдæр хафтытæ',
'histfirst'              => 'раздæр',
'histlast'               => 'фæстæдæр',
'historysize'            => '({{PLURAL:$1|1 байт|$1 байты}})',
'historyempty'           => '(афтид)',

# Revision feed
'history-feed-title'          => 'Ивддзинæдты истори',
'history-feed-item-nocomment' => '$1 $2',

# Revision deletion
'rev-deleted-comment'    => '(комментарий аппæрст у)',
'rev-deleted-user'       => '(архайæджы ном аппæрст у)',
'rev-deleted-event'      => '(фыст аппæрст у)',
'rev-delundel'           => 'равдис/бамбæхс',
'revisiondelete'         => 'Аппар / рацараз фарсы фæлтæртæ',
'revdel-restore-deleted' => 'хафт ивдтытæ',
'revdel-restore-visible' => 'зынгæ ивдтытæ',
'pagehist'               => 'Фарсы истори',
'revdelete-summary'      => 'ивддзинады мидис',
'revdelete-uname'        => 'архайæджы ном',

# History merging
'mergehistory-reason' => 'Аххос:',

# Merge log
'revertmerge' => 'Ахицæн кæнын',

# Diffs
'difference'              => '(Дыууæ фæлтæры ’хсæн хъауджы)',
'lineno'                  => 'Рæнхъ $1:',
'compareselectedversions' => 'Æвзæрст фæлтæртæ абар',
'editundo'                => 'раивын',

# Search results
'searchresults'                    => 'Цы ссардæуы',
'searchresults-title'              => 'Агуырды фæстиуæг: «$1»',
'titlematches'                     => 'Статьяты сæргæндты æмцаутæ',
'notitlematches'                   => 'Фæрсты сæргæндты нæй',
'textmatches'                      => 'Статьяты æмцаутæ',
'prevn'                            => 'рæздæры {{PLURAL:$1|$1}}',
'nextn'                            => 'иннæ {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Раздæр $1 {{PLURAL:$1|фæстиуæг|фæстиуæджы}}',
'nextn-title'                      => 'Дарддæр $1 {{PLURAL:$1|фæстиуæг|фæстиуæджы}}',
'shown-title'                      => 'Æвдисын $1 {{PLURAL:$1|фæстиуæг|фæстиуæджы}} иу фарсыл',
'viewprevnext'                     => 'Фен ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists'                => "'''Ацы викийы ис фарс \"[[:\$1]]\" номимæ.'''",
'searchmenu-new'                   => "'''Ивын фарс \"[[:\$1]]\" ацы викийы!'''",
'searchprofile-articles'           => 'Сæргæндты фæрстæ',
'searchprofile-project'            => 'Æххуыс æмæ Проекты фæрстæ',
'searchprofile-images'             => 'Мультимеди',
'searchprofile-everything'         => 'Алцыдæр',
'searchprofile-advanced'           => 'Бæстондæр',
'searchprofile-articles-tooltip'   => 'Агурын ам: $1',
'searchprofile-project-tooltip'    => 'Агурын ам: $1',
'searchprofile-images-tooltip'     => 'Агурын файлтæ',
'searchprofile-everything-tooltip' => 'Агурын алыран дæр (дискуссийы фæрсты дæр)',
'search-result-size'               => '$1 ({{PLURAL:$2|$2 дзырд|$2 дзырды}})',
'search-result-category-size'      => '{{PLURAL:$1|1 уæнг|$1 уæнгы}} ({{PLURAL:$2|1 дæлкатегори|$2 дæлкатегорийы}}, {{PLURAL:$3|1 файл|$3 файлы}})',
'search-redirect'                  => '(рарвыст ардыгæй: $1)',
'search-section'                   => '(хай $1)',
'search-suggest'                   => 'Кæд мыййаг агурыс: $1',
'search-interwiki-caption'         => 'Æфсымæрон проекттæ',
'search-interwiki-more'            => '(нодзы)',
'search-mwsuggest-enabled'         => 'æмбарынгæнæнтимæ',
'search-mwsuggest-disabled'        => 'æнæ æмбарынгæнæнтæ',
'searchrelated'                    => 'хæстæг',
'searchall'                        => 'æппæт',
'showingresultsheader'             => "{{PLURAL:$5|Фæстиуæг '''$1''' '''$3'''-йæ|Фæстиуæджы '''$1 - $2''' '''$3'''-йæ}} '''$4'''-æн",
'powersearch'                      => 'Сæрмагонд агуырд',
'powersearch-legend'               => 'Сæрмагонд агуырд',
'powersearch-redir'                => 'Рарвыстытæ дæр æвдис',
'powersearch-field'                => 'Агуырд',

# Quickbar
'qbsettings'              => 'Навигацион таг',
'qbsettings-none'         => 'Ма равдис',
'qbsettings-fixedleft'    => 'Галиуырдыгæй',
'qbsettings-fixedright'   => 'Рахизырдыгæй',
'qbsettings-floatingleft' => 'Рахизырдыгæй ленккæнгæ',

# Preferences page
'mypreferences'             => 'Мæ фадæттæ',
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
'group-sysop-member'      => 'админ',
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
'nchanges'                        => '$1 {{PLURAL:$1|ивд|ивды}}',
'recentchanges'                   => 'Фæстаг ивдтытæ',
'recentchanges-legend'            => 'Фæстаг ивдтыты фадæттæ',
'recentchangestext'               => 'Ацы фарсыл ирон Википедийы фæстаг ивддзинæдтæ фенæн ис.',
'recentchanges-label-newpage'     => 'Ацы ивд нæуæг фарс бакодта',
'recentchanges-label-minor'       => 'Ай у чысыл ивд',
'recentchanges-label-bot'         => 'Ацы ивд бот сарызта',
'recentchanges-label-unpatrolled' => 'Ацы ивд нырмæ нæу фидар гонд',
'rcnote'                          => 'Дæлдæр нымад сты афæстаг <strong>$2</strong> боны дæргъы конд <strong>{{PLURAL:$1|иу ивд|$1 ивды}}</strong>, $5, $4 уавæрмæ гæсгæ.',
'rcnotefrom'                      => "Бындæр сты ивдтытæ '''$2'''-æй ('''$1'''-ы йонг æвдыст)",
'rclistfrom'                      => 'Равдисын ивдтытæ амæй фæстæ: $1',
'rcshowhideminor'                 => '$1 чысыл ивдтытæ',
'rcshowhidebots'                  => '$1 роботты куыст',
'rcshowhideliu'                   => '$1 ырбахизгæ архайæджы',
'rcshowhideanons'                 => '$1 сусæг архайæджы',
'rcshowhidemine'                  => '$1 мæ ивдтытæ',
'rclinks'                         => 'Фæстаг $1 ивдтытæ (афæстаг $2 боны дæргъы чи ’рцыдысты) равдис;
$3',
'diff'                            => 'хицæн.',
'hist'                            => 'лог',
'hide'                            => 'Бамбæхсын',
'show'                            => 'Равдисын',
'minoreditletter'                 => 'ч',
'newpageletter'                   => 'Н',
'boteditletter'                   => 'б',
'rc-enhanced-expand'              => 'Лыстæгдзинæдтæ равдис (JavaScript хъæуы)',
'rc-enhanced-hide'                => 'Лыстæгдзинæдтæ бамбæхс',

# Recent changes linked
'recentchangeslinked'         => 'Баст ивдтытæ',
'recentchangeslinked-feed'    => 'Баст ивддзинæдтæ',
'recentchangeslinked-toolbox' => 'Баст ивддзинæдтæ',
'recentchangeslinked-title'   => '"$1"-имæ баст ивдтытæ',
'recentchangeslinked-page'    => 'Фарсы ном:',

# Upload
'upload'              => 'Ног файл сæвæр',
'uploadbtn'           => 'Ног файл сæвæр',
'uploadnologin'       => 'Системæйæн дæхи нæ бацамыдтай',
'uploaderror'         => 'Файл сæвæрыны рæдыд',
'uploadlogpage'       => 'Æвгæндты лог',
'filename'            => 'Файлы ном',
'filedesc'            => 'Ивдтыты фсон:',
'minlength1'          => 'Файлы номы хъуамæ æппынкъаддæр иу дамгъæ уа.',
'badfilename'         => 'Нывы ном ивд æрцыдис. Ныр хуины «$1».',
'savefile'            => 'Бавæр æй',
'uploadedimage'       => 'бавгæндис "[[$1]]"',
'uploadvirus'         => 'Файлы разынд вирус! Кæс $1',
'watchthisupload'     => 'Цæст дарын ацы файлмæ',
'upload-success-subj' => 'Файлы сæвæрд фæрæстмæ',

'upload-file-error' => 'Мидæг рæдыд',

'license' => 'Лицензи:',

# Special:ListFiles
'listfiles' => 'Нывты номхыгъд',

# File description page
'file-anchor-link'  => 'Ныв',
'filehist'          => 'Файлы истори',
'filehist-revert'   => 'раивын',
'filehist-current'  => 'нырыккон',
'filehist-datetime' => 'Датæ/рæстæг',
'filehist-user'     => 'Архайæг',
'filehist-filesize' => 'Файлы бæрцуат',
'filehist-comment'  => 'Фиппаинаг',
'imagelinks'        => 'Æрвитæнтæ файлмæ',
'linkstoimage'      => 'Ацы нывæй пайда {{PLURAL:$1|кæны иу фарс|кæнынц ахæм фæрстæ}}:',
'nolinkstoimage'    => 'Нæй ахæм фæрстæ, кæдон æрвитынц ацы файлмæ.',

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

'brokenredirects-edit'   => 'ивын',
'brokenredirects-delete' => 'аппар',

'withoutinterwiki-submit' => 'Равдис',

'fewestrevisions' => 'Къаддæр кæй ивынц, ахæм фæрстæ',

# Miscellaneous special pages
'nbytes'                 => '$1 {{PLURAL:$1|байт|байты}}',
'nlinks'                 => '$1 {{PLURAL:$1|æрвитæн|æрвитæны}}',
'nmembers'               => '$1 {{PLURAL:$1|уæнг|уæнгы}}',
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
'usercreated'            => 'Фæзындис $1, $2-ыл',
'newpages'               => 'Ног фæрстæ',
'newpages-username'      => 'Архайæг:',
'ancientpages'           => 'Зæронддæр фæрстæ',
'move'                   => 'Сæргонд баив',
'pager-newer-n'          => '{{PLURAL:$1|фæстæдæр иу|фæстæдæр $1}}',
'pager-older-n'          => '{{PLURAL:$1|раздæр иу|раздæр $1}}',

# Book sources
'booksources-go' => 'Агур',

# Special:Log
'specialloguserlabel'  => 'Архайæг:',
'speciallogtitlelabel' => 'Сæргонд:',
'log'                  => 'Логтæ',
'all-logs-page'        => 'Æппæт логтæ',

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
'linksearch'      => 'Æддаг æрвитæнтæ',
'linksearch-ok'   => 'Агур',
'linksearch-line' => '$2 æрвиты $1-мæ',

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
'mywatchlist'       => 'Мæ цæстдард фæрстæ',
'watchlistfor2'     => 'Архайæг: $1 $2',
'nowatchlist'       => 'Иу статьямæ дæр дæ цæст нæ дарыс.',
'watchnologin'      => 'Системæйæн дæхи нæ бацамыдтай',
'watchnologintext'  => 'Ацы номхыгъд ивынмæ [[Special:UserLogin|хъуамæ дæхи бацамонай системæйæн]].',
'addedwatch'        => 'Дæ цæст кæмæ дарыс, уыцы статьятæм бафтыд.',
'removedwatch'      => 'Нал дарыс дæ цæст',
'removedwatchtext'  => 'Фарсмæ «[[:$1]]» нал [[Special:Watchlist|дарыс дæ цæст]].',
'watch'             => 'Цæст æрдар',
'watchthispage'     => 'Цæст дарын ацы фарсмæ',
'unwatch'           => 'Мауал дар цæст',
'watchnochange'     => 'Дæ цæст кæмæ дарыс, уыцы статьятæй иу дæр ивд не ’рцыди.',
'watchlist-details' => '{{PLURAL:$1|$1 фарсмæ|$1 фарсмæ}} дæ цæст дарыс, дискусситы нæ нымайгæйæ.',
'watchlistcontains' => 'Дæ цæст $1 фарсмæ дарыс.',
'wlnote'            => "Дæлæ афæстаг '''$2 сахаты дæргъы''' цы $1 {{PLURAL:$1|ивддзинад|ивддзинады}} æрцыди.",
'wlshowlast'        => 'Фæстæг $1 сахаты, $2 боны дæргъы; $3.',
'watchlist-options' => 'Цæстдард рæгъы фадæттæ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Цæстдард фæрсты номхыгъдмæ афтауын...',
'unwatching' => 'Цæстдард фæрсты номхыгъдæй аиуварс кæнын...',

'enotif_newpagetext' => 'Ай у нæуæг фарс.',
'changed'            => 'ивд æрцыд',
'enotif_anon_editor' => 'сусæг архайæг $1',

# Delete
'deletepage'            => 'Схаф фарс',
'exblank'               => 'фарс афтид уыдис',
'delete-confirm'        => 'Схаф "$1"',
'deletedarticle'        => 'схафта "[[$1]]"',
'dellogpage'            => 'Аппарыны лог',
'deletionlog'           => 'аппарыны лог',
'deletecomment'         => 'Аххос:',
'deleteotherreason'     => 'Æндæр кæнæ уæлæмхасæн аххос:',
'deletereasonotherlist' => 'Æндæр аххос',

# Rollback
'rollbacklink' => 'æздæхын',

# Protect
'protectedarticle'       => '«[[$1]]» — фарс æхгæд æрцыд',
'prot_1movedto2'         => '[[$1]] хаст æрцыд [[$2]], зæгъгæ, ахæм фарсмæ',
'protectcomment'         => 'Фсон:',
'protectexpiry'          => 'Кæдмæ æхгæд у:',
'protect-level-sysop'    => 'Æрмæст админтæ',
'protect-othertime'      => 'Æндæр рæстæг:',
'protect-othertime-op'   => 'æндæр рæстæг',
'protect-otherreason'    => 'Æндæр аххос/уæлæмхасæн:',
'protect-otherreason-op' => 'Æндæр аххос',
'restriction-type'       => 'Бартæ:',

# Restrictions (nouns)
'restriction-edit' => 'Ивын',

# Undelete
'undeletelink'     => 'кæсын/рацаразын',
'undeleteviewlink' => 'кæсын',

# Namespace form on various pages
'namespace'      => 'Нæмтты тыгъдад:',
'blanknamespace' => '(Сæйраг)',

# Contributions
'contributions'       => 'Архайæджы бавæрд',
'contributions-title' => 'Архайæджы бавæрд: $1',
'mycontris'           => 'Мæ бавæрд',
'contribsub2'         => 'Архайæг: $1 ($2)',
'uctop'               => '(фæстаг)',
'month'               => 'Ацы мæй (æмæ раздæр):',
'year'                => 'Ацы аз (æмæ раздæр):',

'sp-contributions-newbies'  => 'Æвдисын æрмæст нæуæг архайджыты бавæрд',
'sp-contributions-blocklog' => 'Хъодыты лог',
'sp-contributions-uploads'  => 'бавгæндтытæ',
'sp-contributions-logs'     => 'логтæ',
'sp-contributions-talk'     => 'Дискусси',
'sp-contributions-search'   => 'Ивдтытæ агурын',
'sp-contributions-username' => 'IP адрис кæнæ архайæджы ном:',
'sp-contributions-submit'   => 'Агурын',

# What links here
'whatlinkshere'            => 'Цавæр æрвитæнтæ цæуынц ардæм',
'whatlinkshere-title'      => '«$1»: цавæр фæрстæ æрвитынц ардæм',
'whatlinkshere-page'       => 'Фарс:',
'linkshere'                => "Ацы фæрстæ æрвитынц '''[[:$1]]-мæ''':",
'nolinkshere'              => "Никæцы фарс æрвиты ардæм: '''[[:$1]]'''.",
'isredirect'               => 'рарвысты фарс',
'istemplate'               => 'æфтыдæй',
'isimage'                  => 'фарсы мидæг ныв',
'whatlinkshere-prev'       => '{{PLURAL:$1|раздæры|раздæры $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|иннæ|иннæ $1}}',
'whatlinkshere-links'      => '← æрвитæнтæ',
'whatlinkshere-hideredirs' => '$1 рарвыстытæ',
'whatlinkshere-hidetrans'  => '$1 æфтыдтытæ',
'whatlinkshere-hidelinks'  => '$1 æрвитæнтæ',
'whatlinkshere-hideimages' => '$1 нывмæ æрвитæнтæ',
'whatlinkshere-filters'    => 'Фильтртæ',

# Block/unblock
'blockip'                => 'Бахъоды кæн',
'blockip-legend'         => 'Бахъоды æй кæн',
'ipbreason'              => 'Аххос:',
'ipbreasonotherlist'     => 'Æндæр аххос',
'ipboptions'             => '2 сахаты:2 hours,1 бон:1 day,3 боны:3 days,1 къуыри:1 week,2 къуырийы:2 weeks,1 мæй:1 month,3 мæййы:3 months,6 мæййы:6 months,1 аз:1 year,нæбæрæг:infinite',
'ipbotherreason'         => 'Æндæр кæнæ уæлæмхасæн аххос:',
'blockipsuccesstext'     => '[[Special:Contributions/$1|$1]] хъодыгонд æрцыд.<br />
Кæс [[Special:Ipblocklist|хъодыгонд IP-адристы номхыгъд]].',
'ipb-blocklist'          => 'Актуалон хъоды равдис',
'ipb-blocklist-contribs' => '$1, зæгъгæ, уыцы архайæджы бавæрд',
'ipblocklist'            => 'Хъодыгонд IP-адристæ æмæ архайджыты нæмттæ',
'ipblocklist-legend'     => 'Хъодыгонд архайæджы ацагур',
'blocklistline'          => '$1, $2 бахъоды кодта $3 ($4)',
'ipblocklist-empty'      => 'Хъодыгæндты номхыгъд афтид у.',
'blocklink'              => 'бахъоды кæн',
'unblocklink'            => 'хъоды айс',
'contribslink'           => 'бавæрд',
'blocklogpage'           => 'Хъодыты лог',
'blocklogentry'          => 'бахъоды кодта [[$1]] $2 æмгъуыдмæ $3',

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
'movepagebtn'              => 'Фарсы ном ивын',
'move-redirect-suppressed' => 'рарвысты фарс нæ бахъуыд',
'movereason'               => 'Фсон:',
'revertmove'               => 'раивын',

# Export
'export' => 'Фæрсты экспорт',

# Namespace 8 related
'allmessages'     => 'Æппæт техникон фыстытæ',
'allmessagesname' => 'Ном',

# Thumbnails
'thumbnail-more' => 'Фестырдæр кæнын',

# Special:Import
'importnotext'          => 'Афтид у кæнæ текст дзы нæй',
'importuploaderrortemp' => 'Импорты файл æрвитын нæ фæрæстмæ. Нæй рæстæгмæ файлдон.',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Мæхи фарс (дæу тыххæй ам ныффысс)',
'tooltip-pt-mytalk'              => 'Дæ дискусси',
'tooltip-pt-preferences'         => 'Дæ фадæттæ',
'tooltip-pt-watchlist'           => 'Фæрстæ кæй ивдтытæм ды дарыс дæ цæст',
'tooltip-pt-mycontris'           => 'Дæ бавæрд',
'tooltip-pt-login'               => 'Системæмæ дæхи бацамонай, кæд æцæг дæ хæс нæу, уæддæр',
'tooltip-pt-logout'              => 'Рахизын',
'tooltip-ca-talk'                => 'Фарсы тыххæй дискусси',
'tooltip-ca-edit'                => 'Ацы фарс дæ бон у ивын. Дæ хорзæхæй, «Фен уал æй» джыбыйæ пайда кæн',
'tooltip-ca-addsection'          => 'Нæуæг хай сараз',
'tooltip-ca-viewsource'          => 'Ацы фарс ивынæй æхгæд у, фæлæ йæ код фенæн и',
'tooltip-ca-history'             => 'Ацы фарсæн йæ раздæры фæлтæртæ (ивыны лог)',
'tooltip-ca-protect'             => 'Ацы фарс ивддзинæдтæй сæхгæн',
'tooltip-ca-delete'              => 'Аппарын ацы фарс',
'tooltip-ca-move'                => 'Статьяйы ном ивын',
'tooltip-ca-watch'               => 'Дæ цæст кæмæ дарыс, уыцы фæрсты номхыгъдмæ бафтау',
'tooltip-ca-unwatch'             => 'Айс ацы фарс дæ цæстдард рæгъæй',
'tooltip-search'                 => '{{SITENAME}} агурын',
'tooltip-search-go'              => 'Кæд ахæм ном исты статьямæ и, уыцы статья равдис',
'tooltip-search-fulltext'        => 'Ацы текст цы фæрсты ис, уыдон агурын',
'tooltip-p-logo'                 => 'Сæйраг фарсмæ бацæуын',
'tooltip-n-mainpage'             => 'Сæйраг фарсмæ ацæуын',
'tooltip-n-mainpage-description' => 'Сæйраг фарсмæ ацæуын',
'tooltip-n-portal'               => 'Проект, дæ бон цы у æмæ кæм цы хъæуы агурыны тыххæй',
'tooltip-n-currentevents'        => 'Ныры цаутæ',
'tooltip-n-recentchanges'        => 'Фæстаг ивдтыты рæгъ',
'tooltip-n-randompage'           => 'Æнæбары æвзæрст фарс фен',
'tooltip-n-help'                 => 'Базонынæн бынат',
'tooltip-t-whatlinkshere'        => 'Ацы фарсмæ чи ’рвитынц, ахæм фæрсты номхыгъд',
'tooltip-t-recentchangeslinked'  => 'Ацы фарс кæмæ æрвиты, уыцы фæрсты фæстаг ивдтытæ',
'tooltip-feed-atom'              => 'Atom feed ацы фарсæн',
'tooltip-t-contributions'        => 'Ацы архайæг цы фæрстæ ивта, уый номхыгъд фен',
'tooltip-t-emailuser'            => 'Арвитын фыстæг ацы архайæгмæ',
'tooltip-t-upload'               => 'Нывтæ кæнæ мультимедиа-файлтæ бавæр',
'tooltip-t-specialpages'         => 'Сæрмагонд фæрсты номхыгъд',
'tooltip-t-print'                => 'Ацы фарс мыхуырмæ цæттæ форматы',
'tooltip-t-permalink'            => 'Фарсы ацы фæлтæрмæ æрвитæн (фæрстæ ивынц, ацы фæлтæр — нæ)',
'tooltip-ca-nstab-main'          => 'Фен статья',
'tooltip-ca-nstab-user'          => 'Архайæджы фарс фен',
'tooltip-ca-nstab-special'       => 'Ай сæрмагонд фарс у, дæ бон æй нæу ивын',
'tooltip-ca-nstab-project'       => 'Проекты фарс',
'tooltip-ca-nstab-image'         => 'Нывы фарс',
'tooltip-ca-nstab-category'      => 'Категорийы фарс',
'tooltip-minoredit'              => 'Чысыл ивдæй йæ банысан кæн',
'tooltip-save'                   => 'Бавæр дæ ивдтытæ',
'tooltip-preview'                => 'Бакæс уал дæ ивдмæ. Дæ хорзæхæй ай уал сараз бавæрыны размæ!',
'tooltip-diff'                   => 'Æвдисы цы ивд бахастай текстмæ',
'tooltip-watch'                  => 'Ацы фарс, дæ цæст кæмæ дарыс, уыцы фæрсты номхыгъдмæ бафтау',
'tooltip-rollback'               => '"Æздæхын" æздæхы ацы фарсы фæстаг архайæджы ивд(тытæ) иу хæстмæ',
'tooltip-undo'                   => '"Раивын" æздæхы ацы ивд æмæ æвдисы ивæн фарс разбакаст уавæры. Уый дын дæ бавæрдæн фсон скæныны фадат дæтты.',

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

# Patrol log
'patrol-log-auto' => '(автоматон)',
'patrol-log-diff' => 'ивд $1',

# Browsing diffs
'previousdiff' => '← Раздæры ивд',
'nextdiff'     => 'Фæстæдæр ивд →',

# Media information
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|фарс|фарсы}}',
'file-info-size'  => '$1 × $2 пикселы, файлы уæз: $3, MIME тип: $4',
'show-big-image'  => 'Æнæхъæнæй',

# Special:NewFiles
'newimages'    => 'Ног нывты галерей',
'showhidebots' => '(роботты куыст $1)',
'ilsubmit'     => 'Агур',
'bydate'       => 'рæстæгмæ гæсгæ',

# Bad image list
'bad_image_list' => 'Формат у ахæм:

Æрмæстдæр рæгъон рæнхъытæ (рæнхъытæ, кæдон байдауынц * символæй) нымады кæнынц.
Фыццаг æрвитæн рæнхъы хъуамæ æрвита æвзæр файлмæ.
Иннæ æрвитæнтæ уыцы рæнхъы нымады кæнынц куыд уæлвæткытæ, кæдон сты фæрстæ кæдæм ис бар бавæрын файл.',

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

# Auto-summaries
'autosumm-new' => 'Ног фарс, йæ код райдайы афтæ: «$1»',

# Size units
'size-bytes'     => '$1 байт(ы)',
'size-kilobytes' => '$1 КБ',
'size-megabytes' => '$1 МБ',
'size-gigabytes' => '$1 ГБ',

# Live preview
'livepreview-failed' => 'Тагъд разæркастæй пайда кæнæн нæй. Хуымæтæджы разæркастæй пайда кæн.',

# Watchlist editor
'watchlistedit-noitems'       => 'Иу фарсмæ дæр нæ дарыс дæ цæст, ацы номхыгъд афтид у.',
'watchlistedit-normal-title'  => 'Дæ цæст кæмæ дарыс, уыцы фæрсты номхыгъд ивыс',
'watchlistedit-normal-submit' => 'Аппар фыстытæ',
'watchlistedit-raw-titles'    => 'Фæрстæ:',
'watchlistedit-raw-submit'    => 'Номхыгъд бафснай',

# Watchlist editing tools
'watchlisttools-view' => 'Баст ивдтытæ фен',
'watchlisttools-edit' => 'Бакæсын æмæ ивын цæстдард рæгъ',
'watchlisttools-raw'  => 'Ивын цæстдард рæгъы бындуртекст',

# Special:Version
'version'                  => 'MediaWiki-йы верси',
'version-version'          => '(Фæлтæр $1)',
'version-software-version' => 'Верси',

# Special:SpecialPages
'specialpages' => 'Сæрмагонд фæрстæ',

);
