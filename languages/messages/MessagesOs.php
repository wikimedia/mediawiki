<?php
/** Ossetic (Иронау)
 *
 * @addtogroup Language
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

# Dates
'sunday'    => 'Хуыцаубон',
'monday'    => 'Къуырисæр',
'tuesday'   => 'Дыццæг',
'wednesday' => 'Æртыццæг',
'thursday'  => 'Цыппарæм',
'saturday'  => 'Сабат',

# Bits of text used by many pages
'categories'      => 'Категоритæ',
'pagecategories'  => 'Категоритæ',
'category_header' => 'Категори "$1"',
'subcategories'   => 'Дæлкатегоритæ',

'qbfind'         => 'Агур',
'qbspecialpages' => 'Сæрмагонд фæрстæ',
'mypage'         => 'Дæхи фарс',
'mytalk'         => 'Дæумæ цы дзурынц',
'navigation'     => 'хъæугæ æрвитæнтæ',

'errorpagetitle'   => 'Рæдыд',
'tagline'          => 'Сæрибар энциклопеди Википедийы æрмæг.',
'help'             => 'Æххуыс',
'search'           => 'агур',
'searchbutton'     => 'агур',
'go'               => 'Статьямæ',
'searcharticle'    => 'Статьямæ',
'history_short'    => 'Истори',
'printableversion' => 'Мыхурмæ верси',
'edit'             => 'Баив æй',
'newpage'          => 'Ног фарс',
'talkpage'         => 'Ацы фарсы тыххæй ныхас',
'specialpage'      => 'Сæрмагонд фарс',
'articlepage'      => 'Фен статья',
'talk'             => 'Дискусси',
'toolbox'          => 'мигæнæнтæ',
'userpage'         => 'Ацы архайæджы фарс фен',
'otherlanguages'   => 'Æндæр æвзæгтыл',
'lastmodifiedat'   => '<span style="white-space: normal;">Кæд æмæ ацы статьяйы ссардтай рæдыд, уæд сраст æй кæн: ацы фарсы уæлæ ис æрвитæн «баив æй».
<br /> Ацы фарс фæстаг хатт ивд æрцыд: $1.</span>', # $1 date, $2 time

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents'     => 'Ног хабæрттæ',
'currentevents-url' => 'Xabar',
'mainpage'          => 'Сæйраг фарс',
'portal'            => 'Архайджыты æхсæнад',

'newmessageslink' => 'ног фыстæгтæ',
'editsection'     => 'баив æй',
'editold'         => 'баив æй',
'toc'             => 'Сæргæндтæ',
'showtoc'         => 'равдис',
'hidetoc'         => 'бамбæхс',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-user'      => 'Архайæджы фарс',
'nstab-special'   => 'Сæрмагонд фарс',
'nstab-image'     => 'Ныв',
'nstab-mediawiki' => 'Фыстаг',
'nstab-template'  => 'Шаблон',

# General errors
'error'         => 'Рæдыд',
'internalerror' => 'Мидæг рæдыд',

# Login and logout pages
'logouttitle'       => 'Номсусæг суын',
'welcomecreation'   => '<h2>Æгас цу, $1!</h2><p>Регистрацигонд æрцыдтæ.',
'loginpagetitle'    => 'Дæхи бацамон системæйæн',
'yourname'          => 'Дæ ном кæнæ фæсномыг',
'login'             => 'Дæхи бавдис системæйæн',
'userlogin'         => 'Системæйæн дæхи бавдис',
'logout'            => 'Номсусæг суын',
'userlogout'        => 'Номсусæг суын',
'createaccountmail' => 'адрисмæ гæсгæ',
'youremail'         => 'Дæ электронон посты адрис',
'yourrealname'      => 'Дæ æцæг ном*',
'yourlanguage'      => 'Техникон фыстыты æвзаг',
'loginsuccess'      => 'Ныр та Википедийы архайыс $1, зæгъгæ, ахæм номæй.',

# Edit page toolbar
'bold_sample'     => 'Ацы текст бæзджын суыдзæн',
'bold_tip'        => 'Бæзджын текст',
'headline_sample' => 'Ам сæргонды текст уæд',

# Edit pages
'minoredit'   => 'Ай чысыл ивддзинад у.',
'watchthis'   => 'Ацы фарсмæ дæ цæст æрдар',
'savearticle' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Афтæ!&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
'showpreview' => '&nbsp;&nbsp;Фен уал æй&nbsp;&nbsp;',
'newarticle'  => '(Ног)',

# History pages
'revhistory' => 'Ивддзинæдты истори',
'last'       => 'раздæры',
'histlegend' => 'Куыд æй æмбарын: (нырыккон) = нырыккон версийæ хъауджыдæрдзинад, (раздæры) = раздæры версийæ хъауджыдæрдзинад, Ч = чысыл ивддзинад.',

# Diffs
'lineno' => 'Рæнхъ $1:',

# Search results
'searchresults'  => 'Цы ссардæуы',
'titlematches'   => 'Статьяты сæргæндты æмцаутæ',
'textmatches'    => 'Статьяты æмцаутæ',
'prevn'          => '$1 фæстæмæ',
'nextn'          => '$1 размæ',
'blanknamespace' => '(Сæйраг)',

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
'rcnote'            => 'Дæлдæр нымад сты афæстаг <strong>$2</strong> боны дæргъы конд <strong>$1</strong> ивддзинад(ы).',
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
'filename' => 'Файлы ном:',
'savefile' => 'Бавæр æй',

# Image list
'imagelist'    => 'Нывты номхыгъд',
'ilsubmit'     => 'Агур',
'byname'       => 'номмæ гæсгæ',
'bydate'       => 'рæстæгмæ гæсгæ',
'bysize'       => 'асмæ гæсгæ',
'imghistory'   => 'Нывы ивддзинæдты лог',
'linkstoimage' => 'Ацы нывæй чи пайда кæны, ахæм статьятæ:',

# Statistics
'userstatstext' => 'Регистрацигонд æрцыдысты <b>$1</b> архайджыты, уыдонæй <b>$2</b> — админтæ (кæс $3).',

# Miscellaneous special pages
'nbytes'       => '$1 байт(ы)',
'nlinks'       => '$1 æрвитæн(ы)',
'lonelypages'  => 'Сидзæр фæрстæ',
'wantedpages'  => 'Хъæугæ фæрстæ',
'allpages'     => 'Æппæт фæрстæ',
'randompage'   => 'Æнæбары æвзæрст фарс',
'shortpages'   => 'Цыбыр фæрстæ',
'longpages'    => 'Даргъ фæрстæ',
'listusers'    => 'Архайджыты номхыгъд',
'specialpages' => 'Сæрмагонд фæрстæ',
'spheading'    => 'Сæрмагонд фæрстæ',
'newpages'     => 'Ног фæрстæ',
'ancientpages' => 'Зæронддæр фæрстæ',
'intl'         => 'Æндæр æвзæгтæм æрвитæнтæ',
'move'         => 'Ном баив',

'categoriespagetext' => 'Мæнæ ахæм категоритæ ирон Википедийы ис.',
'alphaindexline'     => '$1 (уыдоны ’хсæн цы статьятæ ис, фен) $2',

# Special:Allpages
'allarticles'  => 'Æппæт статьятæ',
'allpagesprev' => 'фæстæмæ',
'allpagesnext' => 'дарддæр',

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
'watchdetails'      => '($1 фæрстæм дæ цæст дарыс, дискусситы фæстæмæ; $3... [$4 Æххæст номхыгъд фен].)',
'watchlistcontains' => 'Дæ цæст $1 фæрстæм дарыс.',
'wlnote'            => 'Дæлæ афæстаг <b>$2</b> сахаты дæргъы цы $1 ивддзинад(ы) æрцыди, уыдон.',
'wlshowlast'        => 'Фæстæг $1 сахаты, $2 боны дæргъы; $3.',

# Delete/protect/revert
'exblank' => 'фарс афтид уыдис',

# Contributions
'contributions' => 'Йæ бавæрд',
'mycontris'     => 'Дæ бавæрд',
'uctop'         => '(уæле баззад)',

# What links here
'whatlinkshere' => 'Цавæр æрвитæнтæ цæуынц ардæм',
'linklistsub'   => '(Æрвитæнты номхыгъд)',

# Block/unblock
'ipbreason' => 'Аххос',

# Move page
'movearticle' => 'Статьяйы ном баив',
'movenologin' => 'Системæйæн дæхи нæ бавдыстай',
'newtitle'    => 'Ног ном',

# Namespace 8 related
'allmessages' => 'Æппæт техникон фыстытæ',

# Special:Import
'importnotext' => 'Афтид у кæнæ текст дзы нæй',

# Attribution
'and'    => 'æмæ',
'others' => 'æндæртæ',

# Spam protection
'subcategorycount'     => 'Ацы категорийы мидæг $1 дæлкатегорийы ис.',
'categoryarticlecount' => 'Ацы категорийы мидæг $1 статьяйы ис.',

'newimages' => 'Ног нывты галерей',

);

?>
