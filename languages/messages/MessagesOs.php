<?php
/** Ossetic (Иронау)
 *
 * @addtogroup Language
 *
 * @author Siebrand
 * @author לערי ריינהארט
 * @author Amikeco
 */

$fallback = 'ru';

$skinNames = array(
	'standard' => 'Стандартон',
	'nostalgia' => 'Æнкъард',
	'cologneblue' => 'Кёльны æрхæндæг',
	'monobook' => 'Моно-чиныг',
	'myskin' => 'Мæхи',
	'chick' => 'Карк'
);
$namespaceNames = array(
	NS_MEDIA            => 'Media', //чтоб не писать "Мультимедия"
	NS_SPECIAL          => 'Сæрмагонд',
	NS_MAIN             => '',
	NS_TALK             => 'Дискусси',
	NS_USER             => 'Архайæг',
	NS_USER_TALK        => 'Архайæджы_дискусси',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Дискусси_$1',
	NS_IMAGE            => 'Ныв',
	NS_IMAGE_TALK       => 'Нывы_тыххæй_дискусси',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Дискусси_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблоны_тыххæй_дискусси',
	NS_HELP             => 'Æххуыс',
	NS_HELP_TALK        => 'Æххуысы_тыххæй_дискусси',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категорийы_тыххæй_дискусси',
);

$linkTrail = '/^((?:[a-z]|а|æ|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я|“|»)+)(.*)$/sDu';
$fallback8bitEncoding =  'windows-1251';

$messages = array(
# User preference toggles
'tog-underline' => 'Æрвитæнты бын хахх',
'tog-hideminor' => 'Чысыл ивддзинæдтæ фæстаг ивддзинæдты номхыгъды мауал æвдис',

# Dates
'sunday'        => 'Хуыцаубон',
'monday'        => 'Къуырисæр',
'tuesday'       => 'Дыццæг',
'wednesday'     => 'Æртыццæг',
'thursday'      => 'Цыппарæм',
'saturday'      => 'Сабат',
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
'categories'                    => 'Категоритæ',
'categoriespagetext'            => 'Мæнæ ахæм категоритæ ирон Википедийы ис.',
'pagecategories'                => '{{PLURAL:$1|Категори|Категоритæ}}',
'category_header'               => 'Категори "$1"',
'subcategories'                 => 'Дæлкатегоритæ',
'category-empty'                => "''Ацы категори афтид ма у.''",
'hidden-categories'             => 'Æмбæхст {{PLURAL:$1|категори|категоритæ}}',
'hidden-category-category'      => 'Æмбæхст категоритæ', # Name of the category where hidden categories will be listed
'category-subcat-count-limited' => 'Ацы категорийы мидæг ис {{PLURAL:$1|$1 дæлкатегори|$1 дæлкатегорийы}}.',
'listingcontinuesabbrev'        => '(дарддæрдзу)',

'qbfind'         => 'Агур',
'qbspecialpages' => 'Сæрмагонд фæрстæ',
'mypage'         => 'Дæхи фарс',
'mytalk'         => 'Дæумæ цы дзурынц',
'anontalk'       => 'Ацы IP-адрисы дискусси',
'navigation'     => 'хъæугæ æрвитæнтæ',
'and'            => 'æмæ',

'errorpagetitle'   => 'Рæдыд',
'tagline'          => 'Сæрибар энциклопеди Википедийы æрмæг.',
'help'             => 'Æххуыс',
'search'           => 'агур',
'searchbutton'     => 'агур',
'go'               => 'Статьямæ',
'searcharticle'    => 'Статьямæ',
'history'          => 'Фарсы истори',
'history_short'    => 'Истори',
'printableversion' => 'Мыхурмæ верси',
'permalink'        => 'Ацы версимæ æрвитæн',
'edit'             => 'Баив æй',
'editthispage'     => 'Ацы фарс баив',
'delete'           => 'Аппар',
'deletethispage'   => 'Аппар æй',
'protect'          => 'Сæхгæн',
'protectthispage'  => 'Сæхгæн ацы фарс',
'newpage'          => 'Ног фарс',
'talkpage'         => 'Ацы фарсы тыххæй ныхас',
'talkpagelinktext' => 'Дискусси',
'specialpage'      => 'Сæрмагонд фарс',
'postcomment'      => 'Дæ комментари ныууадз',
'articlepage'      => 'Фен статья',
'talk'             => 'Дискусси',
'toolbox'          => 'мигæнæнтæ',
'userpage'         => 'Ацы архайæджы фарс фен',
'projectpage'      => 'Проекты фарс фен',
'mediawikipage'    => 'Фыстæджы фарс фен',
'templatepage'     => 'Шаблоны фарс фен',
'categorypage'     => 'Категорийы фарс фен',
'otherlanguages'   => 'Æндæр æвзæгтыл',
'lastmodifiedat'   => 'Ацы фарс фæстаг хатт ивд æрцыд: $1, $2.', # $1 date, $2 time
'protectedpage'    => 'Æхгæд фарс',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{grammar:genitive|{{SITENAME}}}} тыххæй',
'aboutpage'            => 'Project:Афыст',
'bugreports'           => 'Рæдыдыл хъуысынгæнинаг',
'currentevents'        => 'Ног хабæрттæ',
'currentevents-url'    => 'Project:Xabar',
'mainpage'             => 'Сæйраг фарс',
'mainpage-description' => 'Сæйраг фарс',
'portal'               => 'Архайджыты æхсæнад',
'privacy'              => 'Хибардзинады политикæ',

'versionrequired' => 'Хъæуы MediaWiki-йы версии $1',

'newmessageslink'     => 'ног фыстæгтæ',
'newmessagesdifflink' => 'фæстаг ивддзинад',
'editsection'         => 'баив æй',
'editold'             => 'баив æй',
'viewsourceold'       => 'йæ код фен',
'editsectionhint'     => 'Баив æй: $1',
'toc'                 => 'Сæргæндтæ',
'showtoc'             => 'равдис',
'hidetoc'             => 'бамбæхс',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'      => 'Архайæджы фарс',
'nstab-special'   => 'Сæрмагонд фарс',
'nstab-project'   => 'Проекты тыххæй',
'nstab-image'     => 'Ныв',
'nstab-mediawiki' => 'Фыстаг',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Æххуысы фарс',
'nstab-category'  => 'Категори',

# Main script and global functions
'nosuchspecialpage' => 'Ахæм сæрмагонд фарс нæй',
'nospecialpagetext' => "<big>'''Нæй ахæм сæрмагонд фарс.'''</big>

Кæс [[{{ns:special}}:Specialpages|æппæт сæрмагонд фæрсты номхыгъд]].",

# General errors
'error'              => 'Рæдыд',
'internalerror'      => 'Мидæг рæдыд',
'internalerror_info' => 'Мидæг рæдыд: $1',
'viewsource'         => 'Йæ код фен',

# Login and logout pages
'logouttitle'             => 'Номсусæг суын',
'welcomecreation'         => '<h2>Æгас цу, $1!</h2><p>Регистрацигонд æрцыдтæ.',
'loginpagetitle'          => 'Дæхи бацамон системæйæн',
'yourname'                => 'Дæ ном кæнæ фæсномыг',
'yourpassword'            => 'Пароль:',
'login'                   => 'Дæхи бавдис системæйæн',
'nav-login-createaccount' => 'Системæйæн дæхи бавдис',
'userlogin'               => 'Системæйæн дæхи бавдис',
'logout'                  => 'Номсусæг суын',
'userlogout'              => 'Номсусæг суын',
'createaccountmail'       => 'адрисмæ гæсгæ',
'youremail'               => 'Дæ электронон посты адрис',
'yourrealname'            => 'Дæ æцæг ном*',
'yourlanguage'            => 'Техникон фыстыты æвзаг:',
'email'                   => 'Эл. посты адрис',
'loginsuccess'            => 'Ныр та Википедийы архайыс $1, зæгъгæ, ахæм номæй.',

# Edit page toolbar
'bold_sample'     => 'Ацы текст бæзджын суыдзæн',
'bold_tip'        => 'Бæзджын текст',
'headline_sample' => 'Ам сæргонды текст уæд',

# Edit pages
'minoredit'   => 'Ай чысыл ивддзинад у.',
'watchthis'   => 'Ацы фарсмæ дæ цæст æрдар',
'savearticle' => 'Афтæ уæд!',
'showpreview' => 'Фен уал æй',
'newarticle'  => '(Ног)',

# History pages
'last'       => 'раздæры',
'histlegend' => 'Куыд æй æмбарын: (нырыккон) = нырыккон версийæ хъауджыдæрдзинад, (раздæры) = раздæры версийæ хъауджыдæрдзинад, Ч = чысыл ивддзинад.',

# Diffs
'lineno' => 'Рæнхъ $1:',

# Search results
'searchresults' => 'Цы ссардæуы',
'titlematches'  => 'Статьяты сæргæндты æмцаутæ',
'textmatches'   => 'Статьяты æмцаутæ',
'prevn'         => '$1 фæстæмæ',
'nextn'         => '$1 размæ',

# Preferences page
'qbsettings-none'         => 'Ма равдис',
'qbsettings-fixedleft'    => 'Галиуырдыгæй',
'qbsettings-fixedright'   => 'Рахизырдыгæй',
'qbsettings-floatingleft' => 'Рахизырдыгæй ленккæнгæ',
'newpassword'             => 'Новый пароль',
'timezonelegend'          => 'Сахаты таг',
'localtime'               => 'Бынатон рæстæг',
'timezoneoffset'          => 'Хъауджыдæрдзинад',

# Recent changes
'recentchanges'     => 'Фæстаг ивддзинæдтæ',
'recentchangestext' => 'Ацы фарсыл ирон Википедийы фæстаг ивддзинæдтæ фенæн ис.',
'rcnote'            => 'Дæлдæр нымад сты афæстаг <strong>$2</strong> боны дæргъы конд <strong>{{PLURAL:$1|ивддзинад|$1 ивддзинады}}</strong>, $3 уавæрмæ гæсгæ.',
'rclinks'           => 'Фæстаг $1 ивддзинæдтæ (афæстаг $2 боны дæргъы чи ’рцыдысты) равдис;
$3',
'diff'              => 'хицæн.',
'hist'              => 'лог',
'hide'              => 'бамбæхс',
'show'              => 'равдис',
'newpageletter'     => 'Н',

# Recent changes linked
'recentchangeslinked' => 'Баст ивддзинæдтæ',

# Upload
'upload'    => 'Ног файл сæвæр',
'uploadbtn' => 'Ног файл сæвæр',
'filename'  => 'Файлы ном',
'savefile'  => 'Бавæр æй',

# Special:Imagelist
'imagelist' => 'Нывты номхыгъд',

# Image description page
'imagelinks'   => 'Æрвитæнтæ',
'linkstoimage' => 'Ацы нывæй чи пайда кæны, ахæм статьятæ:',

# File deletion
'filedelete-submit' => 'Аппар',

# Random page
'randompage' => 'Æнæбары æвзæрст фарс',

# Statistics
'userstatstext' => 'Регистрацигонд æрцыдысты <b>$1</b> архайджыты, уыдонæй <b>$2</b> — админтæ (кæс $3).',

'brokenredirects-delete' => '(аппар)',

# Miscellaneous special pages
'nbytes'       => '$1 байт(ы)',
'nlinks'       => '$1 æрвитæн(ы)',
'nviews'       => '$1 {{PLURAL:$1|æркаст|æркасты}}',
'lonelypages'  => 'Сидзæр фæрстæ',
'wantedpages'  => 'Хъæугæ фæрстæ',
'shortpages'   => 'Цыбыр фæрстæ',
'longpages'    => 'Даргъ фæрстæ',
'listusers'    => 'Архайджыты номхыгъд',
'specialpages' => 'Сæрмагонд фæрстæ',
'spheading'    => 'Сæрмагонд фæрстæ',
'newpages'     => 'Ног фæрстæ',
'ancientpages' => 'Зæронддæр фæрстæ',
'move'         => 'Ном баив',

# Special:Allpages
'allpages'       => 'Æппæт фæрстæ',
'alphaindexline' => '$1 (уыдоны ’хсæн цы статьятæ ис, фен) $2',
'allarticles'    => 'Æппæт статьятæ',
'allpagesprev'   => 'фæстæмæ',
'allpagesnext'   => 'дарддæр',

# E-mail user
'mailnologintext' => 'Фыстæгтæ æрвитынмæ хъуамæ [[Special:Userlogin|системæйæн дæхи бавдисай]] æмæ дæ бæлвырд электронон посты адрис [[Special:Preferences|ныффыссай]].',
'emailpage'       => 'Электронон фыстæг йæм барвит',

# Watchlist
'watchlist'         => 'Дæ цæст кæмæ дарыс, уыцы фæрстæ',
'mywatchlist'       => 'Дæ цæст кæмæ дарыс, уыцы фæрстæ',
'nowatchlist'       => 'Иу статьямæ дæр дæ цæст нæ дарыс.',
'watchnologin'      => 'Системæйæн дæхи нæ бавдыстай',
'watchnologintext'  => 'Ацы номхыгъд ивынмæ <a href="{{localurle:Специальные:Userlogin}}">хъуамæ дæхи бавдисай системæйæн</a>.',
'addedwatch'        => 'Дæ цæст кæмæ дарыс, уыцы статьятæм бафтыд.',
'watch'             => 'Дæ цæст æрдар',
'watchthispage'     => 'Ацы фарсмæ дæ цæст æрдар',
'watchlist-details' => '$1 фæрстæм дæ цæст дарыс, дискусситы фæстæмæ.',
'watchlistcontains' => 'Дæ цæст $1 фæрстæм дарыс.',
'wlnote'            => 'Дæлæ афæстаг <b>$2</b> сахаты дæргъы цы $1 ивддзинад(ы) æрцыди, уыдон.',
'wlshowlast'        => 'Фæстæг $1 сахаты, $2 боны дæргъы; $3.',

# Delete/protect/revert
'exblank' => 'фарс афтид уыдис',

# Namespace form on various pages
'blanknamespace' => '(Сæйраг)',

# Contributions
'contributions' => 'Йæ бавæрд',
'mycontris'     => 'Дæ бавæрд',
'uctop'         => '(уæле баззад)',

# What links here
'whatlinkshere'       => 'Цавæр æрвитæнтæ цæуынц ардæм',
'whatlinkshere-page'  => 'Фарс:',
'linklistsub'         => '(Æрвитæнты номхыгъд)',
'whatlinkshere-links' => '← æрвитæнтæ',

# Block/unblock
'ipbreason'    => 'Аххос',
'contribslink' => 'бавæрд',

# Move page
'movearticle' => 'Статьяйы ном баив',
'movenologin' => 'Системæйæн дæхи нæ бавдыстай',
'newtitle'    => 'Ног ном',

# Namespace 8 related
'allmessages' => 'Æппæт техникон фыстытæ',

# Special:Import
'importnotext' => 'Афтид у кæнæ текст дзы нæй',

# Tooltip help for the actions
'tooltip-ca-protect'      => 'Ацы фарс ивддзинæдтæй сæхгæн',
'tooltip-ca-delete'       => 'Аппар ацы фарс',
'tooltip-n-mainpage'      => 'Сæйраг фарсмæ рацу',
'tooltip-t-whatlinkshere' => 'Ацы фарсмæ чи ’рвитынц, ахæм фæрсты номхыгъд',

# Attribution
'others' => 'æндæртæ',

# Special:Newimages
'newimages' => 'Ног нывты галерей',
'ilsubmit'  => 'Агур',
'bydate'    => 'рæстæгмæ гæсгæ',

# EXIF tags
'exif-artist' => 'Чи йæ систа',

);
