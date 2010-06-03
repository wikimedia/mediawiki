<?php
/** Chechen (Нохчийн)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Chechenka
 * @author Comp1089
 * @author Girdi
 * @author Mega programmer
 * @author Sasan700
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Медйа',
	NS_SPECIAL          => 'Башхо',
	NS_TALK             => 'Дийца',
	NS_USER             => 'Юзер',
	NS_USER_TALK        => 'Юзери_дийца',
	NS_PROJECT_TALK     => '$1_Дийца',
	NS_FILE             => 'Сурт',
	NS_FILE_TALK        => 'Сурти_дийца',
	NS_MEDIAWIKI        => 'МедйаВики',
	NS_MEDIAWIKI_TALK   => 'МедйаВики_дийца',
	NS_TEMPLATE         => 'Дакъа',
	NS_TEMPLATE_TALK    => 'Дакъан_дийца',
	NS_HELP             => 'ГІо',
	NS_HELP_TALK        => 'ГІодан_дийца',
	NS_CATEGORY         => 'Тоба',
	NS_CATEGORY_TALK    => 'Тобан_дийца',
);

$magicWords = array(
	'notoc'                 => array( '0', '__СДЖдац__', '__БЕЗ_ОГЛ__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__ГалерйЯц__', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ),
	'language'              => array( '0', '#МОТТ', '#ЯЗЫК:', '#LANGUAGE:' ),
	'special'               => array( '0', 'башхо', 'служебная', 'special' ),
);

$messages = array(
# User preference toggles
'tog-underline'             => 'Кlел сиз хьака хьажориган:',
'tog-highlightbroken'       => 'Гайта йоцуш йолу хьажоригаш <a href="" class="new">ишта</a> (йа ишта<a href="" class="internal">?</a>).',
'tog-justify'               => 'Нисде йоза шораллий агlонца',
'tog-hideminor'             => 'Къайладаха кигийра нисдарш оц могlама керла хийцамехь',
'tog-hidepatrolled'         => 'Къайладаха гlаролладина нисдарш оц могlама керла нисдашкахь',
'tog-newpageshidepatrolled' => 'Къайлайаха гlароллайина агlонаш оц могlама керла агlонашкахь',
'tog-extendwatchlist'       => 'Шорбина тlехьажарна могlам, ша беригге а хийцамаш чубогlуш, тlяхьабина боцурш а',
'tog-usenewrc'              => 'Лелабе дика могlам керла чу хийцамашна (оьшу JavaScript)',
'tog-numberheadings'        => 'Ша шех хlитто терахь корташна',
'tog-showtoolbar'           => 'Гайта лакхара гlирсан дакъа нисйеш аттон оц редаккхар чохь (JavaScript)',
'tog-rememberpassword'      => 'Даглаца сан дlавазвалар хlокху гlулкхдечу гlирса тlяхь',

'underline-never' => 'Цкъа а',

# Dates
'sunday'        => 'кlиранан де',
'monday'        => 'Оршот',
'tuesday'       => 'Шинара',
'wednesday'     => 'Кхаара',
'thursday'      => 'Еара',
'friday'        => 'Пlераска',
'saturday'      => 'Шот',
'sun'           => 'Кlиранан де',
'mon'           => 'Ор',
'tue'           => 'Ши',
'wed'           => 'Кх',
'thu'           => 'Еа',
'fri'           => 'Пle',
'sat'           => 'Шот',
'january'       => 'нажи бутт',
'february'      => 'мархи бутт',
'march'         => 'биэкарг бутт',
'april'         => 'тушоли бутт',
'may_long'      => 'сели бутт',
'june'          => 'мангал бутт',
'july'          => 'мятсел бутт',
'august'        => 'эгиш бутт',
'september'     => 'тав бутт',
'october'       => 'ардар бутт',
'november'      => 'эрх бутт',
'december'      => 'огой бутт',
'january-gen'   => 'нажи бутт',
'february-gen'  => 'мархи бутт',
'march-gen'     => 'биэкарг бутт',
'april-gen'     => 'тушоли бутт',
'may-gen'       => 'сели бутт',
'june-gen'      => 'мангал бутт',
'july-gen'      => 'мятсел бутт',
'august-gen'    => 'эгиш бутт',
'september-gen' => 'тав бутт',
'october-gen'   => 'ардар бутт',
'november-gen'  => 'эрх бутт',
'december-gen'  => 'огой бутт',
'jan'           => 'нажи бутт',
'feb'           => 'мархи бутт',
'mar'           => 'биэкарг бутт',
'apr'           => 'тушоли бутт',
'may'           => 'сели бутт',
'jun'           => 'мангал бутт',
'jul'           => 'мятсел бутт',
'aug'           => 'эгиш бутт',
'sep'           => 'тав бутт',
'oct'           => 'ардар бутт',
'nov'           => 'эрх бутт',
'dec'           => 'огой бутт',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Кадегар|Кадегарш}}',
'category_header'                => 'Агlонаш оц кадегаршчохь «$1»',
'subcategories'                  => 'Бухаркадегарш',
'category-media-header'          => 'Хlумнаш оцу кадегар чохь «$1»',
'category-empty'                 => "''Хlара кадегар хlинца йаьсса ю.''",
'hidden-categories'              => '{{PLURAL:$1|Къайлаха кадегар|Къайлаха йолу кадегарш}}',
'hidden-category-category'       => 'Къайлаха йолу кадегарш',
'category-subcat-count'          => '{{PLURAL:$2|Хlокх кадегар чохь ю хlокхуьна бухар кадегар.|{{PLURAL:$1|Гойташ $1 бухар кадегар|Гойту $1 бухар кадегар|Гойту $1 бухар кадегар}} оцу $2.}}',
'category-subcat-count-limited'  => 'Хlокх кадегар чохь {{PLURAL:$1|$1 бухар кадегар|$1 бухар кадегарша|$1 бухар кадегарш}}.',
'category-article-count'         => '{{PLURAL:$2|Хlокх кадегар чохь яц цхьа агlо бе.|{{PLURAL:$1|Гойташ $1 агlо|Гойту $1 агlонаш|Гойту $1 агlонаш}} хlокх кадегарца кху $2.}}',
'category-article-count-limited' => 'Хlокх кадегар чохь {{PLURAL:$1|$1 агlо|$1 агlонаш|$1 агlонаш}}.',
'category-file-count'            => '{{PLURAL:$2|Хlокх кадегар чохь цхьа хlум бе яц.|{{PLURAL:$1|Гойта $1 хlум|Гойту $1 хlума|Гойту $1 хlумнаш}} хlокх кадегарца кху $2.}}',
'category-file-count-limited'    => 'Хlокх кадегар чохь {{PLURAL:$1|$1 хlум|$1 хlума|$1 хlумнаш}}.',
'listingcontinuesabbrev'         => '(кхин дlа)',
'index-category'                 => 'Меттигтерахьйо агlонаш',

'mainpagetext' => "'''Вики-белха гlирс «MediaWiki» кхочуш дика дlахlоттийна.'''",

'about'     => 'Цунах лаьцна',
'article'   => 'Сур',
'newwindow' => '(керла чу корехь)',
'cancel'    => 'Цаоьшу',
'mytalk'    => 'Сан дийцаре агlо',
'anontalk'  => 'Дийцаре хlара IP-долуметтиг',
'and'       => '&#32;а',

# Cologne Blue skin
'qbfind' => 'Лахар',
'qbedit' => 'Нисйé',

# Vector skin
'vector-action-delete'      => 'Дlадайá',
'vector-namespace-category' => 'Кадегар',
'vector-namespace-image'    => 'Хlум',
'vector-namespace-template' => 'Куцкеп',

'errorpagetitle'    => 'Гlалат',
'help'              => 'Гlo',
'search'            => 'Лахар',
'searchbutton'      => 'Каро',
'go'                => 'Дехьавала',
'searcharticle'     => 'Дехьавала',
'history'           => 'исцlарера',
'history_short'     => 'Исцlарера',
'printableversion'  => 'Зорба туху башхо',
'permalink'         => 'Даиман йолу хьажориг',
'print'             => 'Зорба тоха',
'edit'              => 'Нисйé',
'delete'            => 'Дlадайá',
'deletethispage'    => 'Дlайайá хlара агlо',
'protect'           => 'Гlаролла дé',
'protectthispage'   => 'Гlаролла дé хlокху агlон',
'unprotect'         => 'Гlароллех къаста',
'unprotectthispage' => 'Гlароллех къаста',
'newpage'           => 'Керла агlо',
'talkpage'          => 'Дийцаре йила хlара агlо',
'talkpagelinktext'  => 'Дийцаре',
'talk'              => 'Дийцаре',
'toolbox'           => 'Гlирсаш',
'viewtalkpage'      => 'Хьажа дийцаре',
'otherlanguages'    => 'Кхечу маттахь дерш',
'jumptosearch'      => 'лахар',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Цунах лаьцна {{grammar:genitive|{{SITENAME}}}}',
'aboutpage'            => 'Project:Цунах лаьцна',
'currentevents'        => 'Оьхаш дол хилларш',
'currentevents-url'    => 'Project:Оьхаш дол хилларш',
'disclaimers'          => 'Бехк тlе ца эцар',
'edithelp'             => 'Собаркхе оцу редаккхарна',
'edithelppage'         => 'Help:Собаркхе оцу редаккхарна',
'helppage'             => 'Help:Собаркхе',
'mainpage'             => 'Коьрта агlо',
'mainpage-description' => 'Коьрта агlо',
'policy-url'           => 'Project:Бакъо',
'portal'               => 'Юкъаралла',
'portal-url'           => 'Project:Юкъараллин ков',
'privacy'              => 'Балалютта къайлаха',

'youhavenewmessages'      => 'Хьуна кхечи $1 ($2).',
'newmessageslink'         => 'керла кехаташ',
'newmessagesdifflink'     => 'тlаьххьара хийцам',
'youhavenewmessagesmulti' => 'Хьуна кхаьчна керла хаам оцу $1',
'editsection'             => 'нисйé',
'editold'                 => 'нисйé',
'editlink'                => 'нисйé',
'editsectionhint'         => 'Нисде дакъа: $1',
'toc'                     => 'Чулацам',
'showtoc'                 => 'гайта',
'hidetoc'                 => 'дlайаккха',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Сур',
'nstab-user'      => 'Декъашхо',
'nstab-image'     => 'Хlум',
'nstab-mediawiki' => 'Хаам',
'nstab-template'  => 'Куцкеп',
'nstab-category'  => 'Кадегар',

# General errors
'error'      => 'Гlалат',
'viewsource' => 'Хьажар',

# Login and logout pages
'yourname'           => 'Декъашхон цlе:',
'yourpassword'       => 'Ишар:',
'yourpasswordagain'  => 'Юха язйе ишар:',
'remembermypassword' => 'Даглаца сан дlавазвалар хlокху гlулкхдечу гlирса тlяхь',
'yourdomainname'     => 'Хьан машан меттиг:',
'login'              => 'Вовзийта хьой гlирсан',
'loginprompt'        => 'Ахьа бакъо йала йеза оцу «cookies» хьайна вовзийта лаахь гlирсан.',
'userlogin'          => 'Чу вала йа дlавазло кхучу',
'logout'             => 'Ара валар',
'userlogout'         => 'Ара валар',
'nologin'            => "Хlинца дlа вазвин вац? '''$1'''.",
'nologinlink'        => 'Кхолла керла дlавазвалар',
'createaccount'      => 'Дlавазве керла декъашхо',
'gotaccount'         => "Хьо дlавазвина вуй? '''$1'''.",
'mailmypassword'     => 'Схьаэца керла ишар',
'accountcreated'     => 'Дlавазвар кхоллина дели',
'accountcreatedtext' => 'Кхоллина декъашхо дlавазвар $1.',
'loginlanguagelabel' => 'Мотт: $1',

# Password reset dialog
'resetpass'                 => 'Ишар хийца',
'resetpass_text'            => '<!-- Кхузахь язъде хьай йоза -->',
'resetpass_header'          => 'Жамlаш дlаязвеллачуьна ишар хийцар',
'oldpassword'               => 'Шираелла ишар:',
'newpassword'               => 'Керла ишар:',
'retypenew'                 => 'Юха язйе керла ишар:',
'resetpass-submit-loggedin' => 'Хийца ишар',
'resetpass-submit-cancel'   => 'Цаоьшу',

# Edit pages
'summary'             => 'Хийцамех лаьцна:',
'minoredit'           => 'Жимо хийцам',
'watchthis'           => 'Лата йе хlара агlо тергаме могlам юкъа',
'savearticle'         => 'Дlаязйе агlо',
'preview'             => 'Хьалха муха ю хьажа',
'showpreview'         => 'Хьалха муха ю хьажар',
'showdiff'            => 'Хlоттина болу хийцам',
'anoneditwarning'     => "'''Тергам бе''': Ахьа хьо вовзитина вац гlирсан. Хьан IP-долу меттиг дlаязйина хира ю хlокху агlон исцlрера чу.",
'blockedtitle'        => 'Декъашхо сацийна',
'accmailtitle'        => 'Ишар дlаяхьийтина.',
'accmailtext'         => "Ишар декъашхочуьна [[User talk:$1|$1]], йина ша шех хитта делла дулу чу элпашах, дlа яхийтина хьокху хаамана зlен чу $2.

Дlа язвинчул тlяхьа, кху гlирса чохь шуьга хийцалур ю ''[[Special:ChangePassword|шай ишар]]''.",
'newarticle'          => '(Kерла)',
'newarticletext'      => "Хьо веана хьажоригци хlокху агlон тlе, хlара агlо хlинца йоцаш ю.
Нагахь иза кхолла лаахь, хlотта де лахо гуш долу корехь йоза (мадарра хьажа. [[{{MediaWiki:Helppage}}|гlон агlон чу]]).
Нагахь гlалат даьлла нисвелляхь кхузе, атта тlе тlаlа йе '''юха йоккхуриг''' хьай гlирса тlяхь.",
'editing'             => 'Редаккхар: $1',
'editingsection'      => 'Редаккхар $1 (дакъа)',
'editingcomment'      => 'Редаккхар $1 (керла дакъа)',
'editconflict'        => 'Редаккхарна дойнаш: $1',
'yourtext'            => 'Хьан йоза',
'moveddeleted-notice' => 'Иза агlо дlайайина йара.
Оцу собаркхен лахахьа гойтуш ю цуьнца долу дlаяздарш кху дlадайина тептар чура а цlе хийцарш а.',

# Parser/template warnings
'parser-template-loop-warning' => 'Карийна куцкепаш юкъахь хилла шад: [[$1]]',

# "Undo" feature
'undo-success' => 'Нисйинарг а тlе цалаца мега. Дехар до, хьажа цхьатерра йуй башхо, тешна хила, баккъалла иза хийцам буйте хьуна безарг, тlакха тlе таlайе «дlайазйе агlо», хийцам хlотта ба.',

# Revision deletion
'rev-delundel'      => 'гайта/къайладаккха',
'revdelete-content' => 'чуьраниг',

# Diffs
'lineno'   => 'Могlа $1:',
'editundo' => 'дlадаккха',

# Search results
'searchmenu-legend'     => 'Лахарна гlирс нисба',
'searchhelp-url'        => 'Help:Чулацам',
'powersearch-toggleall' => 'Массо',

# Preferences page
'preferences'         => 'Гlирс нисбан',
'mypreferences'       => 'Гlирс нисбан',
'changepassword'      => 'Хийцамба ишарна',
'skin-preview'        => 'Хьалха муха ю хьажа',
'prefs-datetime'      => 'Терахь а хан',
'prefs-personal'      => 'Долахь болу хаамаш',
'prefs-rc'            => 'Керла нисдаршан агlо',
'prefs-watchlist'     => 'Тергаме могlам',
'prefs-misc'          => 'Кхин гlирсаш',
'prefs-resetpass'     => 'Хийца ишар',
'prefs-rendering'     => 'Арахьара хатl',
'saveprefs'           => 'lалашдан',
'prefs-editing'       => 'Редаккхар',
'prefs-searchoptions' => 'Лахарна гlирс нисба',
'prefs-files'         => 'Хlумнаш',
'youremail'           => 'Кехат яздо зlе цlе:',
'username'            => 'Дlаязвиначуьна цlе:',
'yourrealname'        => 'Хьан бакъ цlе:',
'yourlanguage'        => 'Мотт юкъардекъа:',
'yourvariant'         => 'Метта башхо',
'prefs-help-email'    => 'Кехат яздо зlе цlе цахlоттийча а хlум дац, иза оьшар ю, нагахь хьуна хьай ишар йицлахь.
Цо атто бийра бу кхечу декъашхошна a хьан кху чура декъа агlонца хьега хаам бахьийта.',

# User rights
'userrights'  => 'Декъашхочуьн бакъона урхалладар',
'editinguser' => "Хийца декъашхочуьна бакъо '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",

# Groups
'group-all' => '(массо)',

# Recent changes
'nchanges'             => '$1 {{PLURAL:$1|хийцам|хийцамаш|хийцамаш}}',
'recentchanges'        => 'Керла нисдарш',
'recentchanges-legend' => 'Гlирс нисбарна керла нисдарш',
'rcnote'               => "{{PLURAL:$1|Тlаьххьара '''$1''' хийцам|Тlаьххьара '''$1''' хийцамаш|Тlаьххьара '''$1''' хийцамаш}} за '''$2''' {{PLURAL:$2|де|дийнахь|дийнахь}}, оцу хан чохь $5 $4.",
'rclistfrom'           => 'Гайта хийцам оцу $1.',
'rcshowhideminor'      => '$1 кегийра нисдарш',
'rcshowhideliu'        => '$1 вовзитар долу декъашхой',
'rcshowhideanons'      => '$1 хьулбелларш',
'rcshowhidemine'       => '$1 айхьа нисдинарш',
'diff'                 => 'башхонаш.',
'hist'                 => 'исцlарера',
'rc_categories_any'    => 'Муьлхаа',
'newsectionsummary'    => '/* $1 */ Керла хьедар',

# Recent changes linked
'recentchangeslinked'         => 'Кхуьнца долу нисдарш',
'recentchangeslinked-feed'    => 'Кхуьнца долу нисдарш',
'recentchangeslinked-toolbox' => 'Кхуьнца долу нисдарш',

# Upload
'upload'            => 'Чуйаккха хlум',
'uploadlogpagetext' => 'Лахахьа гойтуш бу могlам тlаьххьара чуяхна хlумнаши. Ишта хьажа. [[Special:ImageList|хlумнаши могlам]] йа [[Special:NewImages|галеларе хlумнаши]].',
'filename'          => 'Хlуман цlе',
'upload-wasdeleted' => "'''Тергам бе: ахьа чуйаккха хьийзошйолу хlума, хьалхо дlайайана хlума ю.'''

Юха а хьажа, баккъалла хьуна оьшуш йуй и хlумма. Лахахь далийна дlадайарна тéптар.",

# Special:ListFiles
'imgfile'        => 'хlум',
'listfiles'      => 'Хlумнаши могlам',
'listfiles_name' => 'Хlуман цlе',
'listfiles_user' => 'Декъашхо',

# File description page
'file-anchor-link'   => ' Хlум',
'filehist-deleteall' => 'дlадайá массо',
'filehist-deleteone' => 'дlадайá',

# File deletion
'filedelete-legend' => 'Дlайайá и хlум',
'filedelete-submit' => 'Дlадайá',

# Random page
'randompage' => 'Ца хууш нисйелла агlо',

'brokenredirects-edit'   => 'нисйé',
'brokenredirects-delete' => 'дlадайá',

# Miscellaneous special pages
'nbytes'              => '$1 {{PLURAL:$1|цlинцl|цlинцlа|цlинацl}}',
'ncategories'         => '$1 {{PLURAL:$1|кадегар|кадегарш|кадегарш}}',
'unusedcategories'    => 'Йаьсса кадегарш',
'wantedtemplates'     => 'Оьшуш долу куцкепаш',
'mostlinkedtemplates' => 'Массарел дуккха а леладо куцкепаш',
'listusers'           => 'Декъашхой могlам',
'newpages'            => 'Керла агlонаш',
'newpages-username'   => 'Декъашхо:',
'move'                => 'Цlе хийца',
'unusedimagestext'    => 'Дехар до, тидаме эца, кхин йолу дуьнана машан-меттигаш а лелош хила мега нисса йогlу хьажориг (URL) хlокху хlуман, хlокху могlаме йогlуш ялахь яцахь а иза хила мега жигара лелош.',

# Book sources
'booksources'    => 'Жайнан хьосташ',
'booksources-go' => 'Лаха',

# Special:Log
'specialloguserlabel'  => 'Декъашхо:',
'speciallogtitlelabel' => 'Корта:',
'log'                  => 'Тéптарш',

# Special:AllPages
'allpages'       => 'Массо агlонаш',
'allarticles'    => 'Массо агlонаш',
'allpagessubmit' => 'Кхочушдé',

# Special:Categories
'categories' => 'Кадегарш',

# Special:DeletedContributions
'deletedcontributions' => 'Декъашхочуьн дlабайина къинхьегам',

# Special:LinkSearch
'linksearch-ok' => 'Лаха',

# Special:ActiveUsers
'activeusers' => 'Жигар декъашхой могlам',

# Special:Log/newusers
'newuserlogpage'          => 'Декъашхой дlабазбина тептар',
'newuserlog-create-entry' => 'Керла декъашхо',

# Special:ListGroupRights
'listgrouprights' => 'Декъашхойн тобанаши бакъонаш',

# E-mail user
'emailuser'       => 'Декъашхочун хааман кехат',
'defemailsubject' => 'Хаам кхузар {{grammar:genitive|{{SITENAME}}}}',

# Watchlist
'watchlist'    => 'Тергаме могlам',
'mywatchlist'  => 'Тергаме могlам',
'watchnologin' => 'Хьо вовзита веза гlирсан',
'addedwatch'   => 'Юкъатоьхна тергаме могlамна',
'watch'        => 'Тидам бе',
'wlnote'       => 'Лахахьа {{PLURAL:$1|тlаьхьа богlу $1 хийцам|тlаьхьа богlу $1 хийцамаш|тlаьхьа богlу $1 хийцамаш}} хlокху {{PLURAL:$2|тlаьхьар|тlаьхьара|тlаьхьара}} <strong>$2</strong> {{plural:$2|сохьт|сохьатехь|сохьташкахь}}.',
'wlshowlast'   => 'Гайта тlаьххьара $1 сахьташ $2 денош $3',

# Delete
'deletepage'      => 'Дlайайá агlо',
'confirm'         => 'Къобалде',
'excontent'       => 'чуьраниг: «$1»',
'excontentauthor' => 'чуьраниг: «$1» (дуьххьара кхоллина да вара иза [[Special:Contributions/$2|$2]])',
'exbeforeblank'   => 'чуьраниг дlацlанйале хьалха: «$1»',
'delete-legend'   => 'Дlадайáр',
'deletedarticle'  => 'дlадайинарг «[[$1]]»',
'dellogpage'      => 'Дlадайан тептараш',
'deletionlog'     => 'дlадайан тептараш',

# Protect
'prot_1movedto2' => '«[[$1]]» цlе хийцина оцу «[[$2]]»',

# Restrictions (nouns)
'restriction-upload' => 'Чуйолуш',

# Restriction levels
'restriction-level-all' => 'массо барам',

# Undelete
'undeleterevdel'         => 'Метта хlоттор хира дац, нагахь иза дакъошкахь дlадайина далахь а тlаьххьара кисак башхо йа хlума.
Иштнарг хилча ахьа дlабаккха беза хlоттийна болу къастам йа хьагайта тlаьххьара дlайайина башхо.',
'undeletelink'           => 'хьажа/метта хlоттаде',
'undeletedarticle'       => 'метта хlоттийна «[[$1]]»',
'undelete-search-submit' => 'Лаха',

# Namespace form on various pages
'invert'         => 'Хаьржинарг хилийта',
'blanknamespace' => '(Коьрта)',

# Contributions
'contributions' => 'Декъашхон къинхьегам',
'mycontris'     => 'Сан къинхьегам',
'month'         => 'Беттаца (йа хьалхе):',
'year'          => 'Шерачохь (йа хьалхе):',

'sp-contributions-logs'       => 'тéптарш',
'sp-contributions-talk'       => 'дийцаре',
'sp-contributions-userrights' => 'декъашхочуьн бакъона урхалладар',
'sp-contributions-submit'     => 'Лаха',

# What links here
'whatlinkshere' => 'Хьажоригаш кхузе',
'istemplate'    => 'лата де',

# Block/unblock
'blockip'            => 'Сацаве',
'ipadressorusername' => 'IP-долу меттиг йа декъашхон цlе:',
'blockipsuccesssub'  => 'Сацавар чакхдели',
'blockipsuccesstext' => '[[Special:Contributions/$1|«$1»]] сацийна ву.<br />
Хьажа. [[Special:IPBlockList|могlам сацийна IP-долу меттигаш]].',
'unblocked'          => '[[User:$1|$1]] хьайаьстина.',
'ipblocklist'        => 'Сацийна IP-долу меттиг а дlалаьрра язбаларш',
'ipblocklist-submit' => 'Лаха',
'blocklink'          => 'сацаве',
'contribslink'       => 'къинхьегам',

# Move page
'move-page'               => '$1 — цlе хийцар',
'movearticle'             => 'Цle хийца хlокху агlон',
'1movedto2'               => 'цlе хийцина «[[$1]]» оцу «[[$2]]»',
'delete_and_move'         => 'Цle а хуьйцуш дlадайá',
'delete_and_move_confirm' => 'Хlаъ, дlайайъа хlара агlо',

# Export
'export-templates' => 'Лата де куцкепаш',

# Namespace 8 related
'allmessages'            => 'Гlирса хаамаш',
'allmessagesname'        => 'Хаам',
'allmessages-filter-all' => 'Массо',

# Special:Import
'import-interwiki-templates' => 'Лата де массо куцкепаш',
'import-upload-filename'     => 'Хlуман цlе:',

# Tooltip help for the actions
'tooltip-ca-talk'        => 'Дийцаре чулацам агlонаши',
'tooltip-ca-delete'      => 'Дlайайá хlара агlо',
'tooltip-p-logo'         => 'Коьрта агIо',
'tooltip-ca-nstab-media' => 'Медиа-хlум',

# Attribution
'siteuser' => 'декъашхо {{grammar:genitive|{{SITENAME}}}} $1',
'others'   => 'кхин',

# Media information
'show-big-image' => 'Сурт цlанал лакхаро бакъонца',

# Special:NewFiles
'newimages' => 'Галеларе керла чу хlумни',
'ilsubmit'  => 'Лаха',

# EXIF tags
'exif-datetime'         => 'Хlума хийцина терахь а хан',
'exif-datetimeoriginal' => 'Дуьххьарлера терахь а хан',

'exif-scenetype-1' => 'Суд ша даьккхина нис дуьххьал',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'массо',
'imagelistall'     => 'массо',
'namespacesall'    => 'массо',
'monthsall'        => 'массо',
'limitall'         => 'массо',

# Trackbacks
'trackbackremove' => '([$1 дlадайá])',

# Auto-summaries
'autosumm-new' => 'Керла агlо: «$1»',

# Live preview
'livepreview-loading' => 'Чуйолуш…',
'livepreview-ready'   => 'Чуйолуш… Кийча йу!',

# Watchlist editor
'watchlistedit-normal-submit' => 'Дlадайá язъдинарш',

# Special:FilePath
'filepath-page' => 'Хlум:',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Лаха',

# Special:SpecialPages
'specialpages'                   => 'Леррина агlонаш',
'specialpages-group-maintenance' => 'Жамlаш гlирса хьашташ кхочушдар',
'specialpages-group-other'       => 'Кхин гlуллакхан агlонаш',
'specialpages-group-login'       => 'Хьой вовзийта / Дlавазло',
'specialpages-group-changes'     => 'Керла нисдарш а тéптарш',
'specialpages-group-media'       => 'Жамlаш оцу медиа-гlирсашан а чуяхарш',
'specialpages-group-users'       => 'Декъашхой а бакъонаш',
'specialpages-group-highuse'     => 'Уггаре дукха лелайо агlонаш',
'specialpages-group-pages'       => 'Агlонаши могlамаш',
'specialpages-group-pagetools'   => 'Гlирсаш оцу агlонашан',
'specialpages-group-wiki'        => 'Вики-баххаш а гlирсаш',
'specialpages-group-redirects'   => 'Дlасахьажош йолу гlуллакхан агlонаш',
'specialpages-group-spam'        => 'Гlирсаш совбиларна дуьхьал',

# Special:Tags
'tags'          => 'Болш болу хийцаман къастам',
'tags-edit'     => 'нисйé',
'tags-hitcount' => '$1 {{PLURAL:$1|хийцам|хийцамаш|хийцамаш}}',

# Add categories per AJAX
'ajax-confirm-save' => 'lалашдан',

);
