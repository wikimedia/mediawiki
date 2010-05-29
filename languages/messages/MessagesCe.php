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
'tog-rememberpassword'      => 'Даглац со хьокх компьютер тIехь',

'underline-never' => 'Цкъа а',

# Dates
'sunday'        => 'К1иранде',
'monday'        => 'Оршот',
'tuesday'       => 'Шинара',
'wednesday'     => 'Кхаара',
'thursday'      => 'Еара',
'friday'        => 'П1ераска',
'saturday'      => 'Шот',
'sun'           => 'К1иранде',
'mon'           => 'Ор',
'tue'           => 'Ши',
'wed'           => 'Кх',
'thu'           => 'Еа',
'fri'           => 'П1e',
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
'pagecategories'                 => '{{PLURAL:$1|Тоба|Тобаш}}',
'category_header'                => 'Агlонаш оц кадегаршчохь «$1»',
'subcategories'                  => 'Бухаркадегарш',
'category-media-header'          => 'Файлош тобашахь «$1»',
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

'about'     => 'Цунах лаьцна',
'article'   => 'Сур',
'newwindow' => '(керла чу корехь)',
'cancel'    => 'Cаца',
'mytalk'    => 'Сан дийцаре агlо',
'anontalk'  => 'Дийцаре хlара IP-долуметтиг',
'and'       => '&#32;а',

# Cologne Blue skin
'qbfind' => 'Лаха',

'errorpagetitle'    => 'Гlалат',
'help'              => 'Гlo',
'search'            => 'Лаха',
'searchbutton'      => 'Каро',
'go'                => 'Дехьадоху',
'searcharticle'     => 'Дехьавала',
'history'           => 'исцlарера',
'history_short'     => 'Исцlарера',
'printableversion'  => 'Зорба тоха башхо',
'print'             => 'Зорба тоха',
'edit'              => 'Нисйе',
'delete'            => 'Дlадайа',
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
'portal'               => 'Юкъаралла',
'portal-url'           => 'Project:Юкъараллин ков',
'privacy'              => 'Балалютта къайлаха',

'youhavenewmessages'      => 'Хьуна кхечи $1 ($2).',
'newmessageslink'         => 'керла кехаташ',
'newmessagesdifflink'     => 'тlаьххьара хийцам',
'youhavenewmessagesmulti' => 'Хьуна кхаьчна керла хаам оцу $1',
'editsection'             => 'нисйе',
'editold'                 => 'нисйе',
'editsectionhint'         => 'Нисде дакъа: $1',
'toc'                     => 'Чулацам',
'showtoc'                 => 'гайта',
'hidetoc'                 => 'дlайаккха',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Сур',
'nstab-user'      => 'Декъашхо',
'nstab-image'     => 'Хlум',
'nstab-mediawiki' => 'Хаам',
'nstab-category'  => 'Кадегар',

# General errors
'error' => 'Гlалат',

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
'newpassword' => 'Керла ишар:',

# Edit pages
'summary'         => 'Хийцамех лаьцна:',
'minoredit'       => 'Жимо хийцам',
'watchthis'       => 'Лата йе хlара агlо тергаме могlам юкъа',
'savearticle'     => 'Дlаязйе агlо',
'showpreview'     => 'Хьалха муха ю хьажар',
'showdiff'        => 'Хlоттина болу хийцам',
'anoneditwarning' => "'''Тергам бе''': Ахьа хьо вовзитина вац гlирсан. Хьан IP-долу меттиг дlаязйина хира ю хlокху агlон исцlрера чу.",
'blockedtitle'    => 'Декъашхо сацийна',
'accmailtitle'    => 'Ишар дlаяхьийтина.',
'accmailtext'     => "Ишар декъашхочуьна [[User talk:$1|$1]], йина ша шех хитта делла дулу чу элпашах, дlа яхийтина хьокху хаамана зlен чу $2.

Дlа язвинчул тlяхьа, кху гlирса чохь шуьга хийцалур ю ''[[Special:ChangePassword|шай ишар]]''.",
'newarticle'      => '(Kерла)',
'newarticletext'  => "Хьо веана хьажоригци хlокху агlон тlе, хlара агlо хlинца йоцаш ю.
Нагахь иза кхолла лаахь, хlотта де лахо гуш долу корехь йоза (мадарра хьажа. [[{{MediaWiki:Helppage}}|гlон агlон чу]]).
Нагахь гlалат даьлла нисвелляхь кхузе, атта тlе тlаlа йе '''юха йоккхуриг''' хьай гlирса тlяхь.",
'editing'         => 'Редаккхар: $1',
'editingsection'  => 'Редаккхар $1 (дакъа)',
'editingcomment'  => 'Редаккхар $1 (керла дакъа)',
'editconflict'    => 'Редаккхарна дойнаш: $1',
'yourtext'        => 'Хьан йоза',

# Diffs
'editundo' => 'дlадаккха',

# Search results
'searchhelp-url' => 'Help:Чулацам',

# Preferences page
'mypreferences'    => 'Гlирс нисбан',
'changepassword'   => 'Хийцамба ишарна',
'prefs-watchlist'  => 'Тергаме могlам',
'prefs-editing'    => 'Редаккхар',
'youremail'        => 'Кехат яздо зlе цlе:',
'yourrealname'     => 'Хьан бакъ цlе:',
'yourlanguage'     => 'Мотт юкъардекъа:',
'yourvariant'      => 'Метта башхо',
'prefs-help-email' => 'Кехат яздо зlе цlе цахlоттийча а хlум дац, иза оьшар ю, нагахь хьуна хьай ишар йицлахь.
Цо атто бийра бу кхечу декъашхошна a хьан кху чура декъа агlонца хьега хаам бахьийта.',

# User rights
'editinguser' => "Хийца декъашхочуьна бакъо '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",

# Recent changes
'recentchanges' => 'Керла нисдарш',
'diff'          => 'башхонаш.',
'hist'          => 'исцlарера',

# Recent changes linked
'recentchangeslinked'         => 'Кхунца долу нисдарш',
'recentchangeslinked-feed'    => 'Кхунца долу нисдарш',
'recentchangeslinked-toolbox' => 'Кхунца долу нисдарш',

# Upload
'upload'   => 'Чуйаккха хlум',
'filename' => 'Хlуман цlе',

# Special:ListFiles
'listfiles_name' => 'Хlуман цlе',
'listfiles_user' => 'Декъашхо',

# File description page
'file-anchor-link' => ' Хlум',

# Random page
'randompage' => 'Ца хууш нисйелла агlо',

'brokenredirects-delete' => 'дlадайа',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|цlинцl|цlинцlа|цlинацl}}',
'ncategories'       => '$1 {{PLURAL:$1|кадегар|кадегарш|кадегарш}}',
'newpages'          => 'Керла агlонаш',
'newpages-username' => 'Декъашхо:',
'move'              => 'Цlе хийца',

# Special:Log
'specialloguserlabel' => 'Декъашхо:',

# Special:AllPages
'allpages'       => 'Массо агlонаш',
'allarticles'    => 'Массо тептараш',
'allpagessubmit' => 'кхочушде',

# Special:Categories
'categories' => 'Тобаш',

# Special:Log/newusers
'newuserlogpage'          => 'Керла юзери терахь',
'newuserlog-create-entry' => 'Керла Юзер',

# E-mail user
'emailuser'       => 'Кехат Язде Юзеран',
'defemailsubject' => '{{SITENAME}} и-пошта',

# Watchlist
'watchlist'    => 'тергалдеш тептараш',
'mywatchlist'  => 'Сан тергалдо список',
'watchnologin' => 'Деза чу валар',
'addedwatch'   => 'Тlетохха хьан тергалдо список чу',
'watch'        => 'зен',
'wlshowlast'   => 'Гайт тІаьххара $1 сахьташ $2 денош $3',

# Delete
'confirm'     => 'Бакъдар',
'dellogpage'  => 'ДІадайан таптараш',
'deletionlog' => 'дІадайан таптараш',

# Protect
'prot_1movedto2' => '«[[$1]]» хийцина - «[[$2]]»',

# Namespace form on various pages
'blanknamespace' => '(Коьртаниг)',

# Contributions
'contributions' => 'Юзери Xьуьнар',
'mycontris'     => 'Сан болх',
'month'         => 'За год (и ранее):',
'year'          => 'За месяц (и ранее):',

'sp-contributions-talk' => 'Дийца',

# What links here
'whatlinkshere' => 'Линкаш Кхуза',

# Block/unblock
'blockip'            => 'Къовла Юзер',
'ipadressorusername' => 'IP-адрес я юзери цІе:',
'blockipsuccesssub'  => 'Блокада йина',
'blocklink'          => 'Къовла',
'contribslink'       => 'Xьуьнар',

# Move page
'movearticle'             => 'цIe хийца таптар',
'1movedto2'               => '«[[$1]]» хийцина - «[[$2]]»',
'delete_and_move'         => 'ДІадайа тІаккха цIe хийца',
'delete_and_move_confirm' => 'ХIаъ, дIадайъа таптар',

# Namespace 8 related
'allmessages'     => 'Система хаамаш',
'allmessagesname' => 'Хаамаш',

# Tooltip help for the actions
'tooltip-ca-talk' => 'Дийца',

# Attribution
'siteuser' => 'Юзер {{grammar:genitive|{{SITENAME}}}} $1',
'others'   => 'Вуьш',

# Media information
'show-big-image' => 'Доккха де сурт',

# Special:NewFiles
'newimages' => 'Керла файлаш галерей',

'exif-scenetype-1' => 'Сурт сфотографировано напрямую',

# Auto-summaries
'autosumm-new' => 'Керла: $1',

# Special:SpecialPages
'specialpages' => 'Спецтаптарш',

);
