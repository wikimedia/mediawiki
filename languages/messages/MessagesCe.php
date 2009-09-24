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
 * @author Girdi
 * @author Mega programmer
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
	'notoc'                 => array( '0', '__СДЖдац__', '__БЕЗ_ОГЛ__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__ГалерйЯц__', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ),
	'language'              => array( '0', '#МОТТ', '#ЯЗЫК:', '#LANGUAGE:' ),
	'special'               => array( '0', 'башхо', 'служебная', 'special' ),
);

$messages = array(
# User preference toggles
'tog-rememberpassword' => 'Даглац со хьокх компьютер тIехь',

'underline-never' => 'Цкъа а',

# Dates
'sunday'    => 'К1иранде',
'monday'    => 'Оршот',
'tuesday'   => 'Шинара',
'wednesday' => 'Кхаара',
'thursday'  => 'Еара',
'friday'    => 'П1ераска',
'saturday'  => 'Шот',
'sun'       => 'К1иранде',
'mon'       => 'Ор',
'tue'       => 'Ши',
'wed'       => 'Кх',
'thu'       => 'Еа',
'fri'       => 'П1e',
'sat'       => 'Шот',

# Categories related messages
'pagecategories'        => '{{PLURAL:$1|Тоба|Тобаш}}',
'category-media-header' => 'Файлош тобашахь «$1»',

'about'     => 'Цунах лаьцна',
'article'   => 'таптар',
'newwindow' => '(керла кор)',
'cancel'    => 'Cаца',
'mytalk'    => 'Сан цІера дийцар',
'anontalk'  => 'ХІар IP-адреси дийцар',
'and'       => '&#32;а',

# Cologne Blue skin
'qbfind' => 'Лахар',

'errorpagetitle'    => 'ГІалат',
'help'              => 'ГIo',
'search'            => 'Лахар',
'searchbutton'      => 'Каро',
'go'                => 'Дехьадоху',
'searcharticle'     => 'Дехьадоху',
'history'           => 'терахь',
'history_short'     => 'терахь',
'printableversion'  => 'Зорба тоха версия',
'print'             => 'Зорба тоха',
'edit'              => 'Xийца',
'delete'            => 'ДІадайа',
'protect'           => 'лар е',
'protectthispage'   => 'лар е',
'unprotect'         => 'Лар ма е',
'unprotectthispage' => 'Лар ма е',
'newpage'           => 'Керла таптар',
'talkpage'          => 'Дийца',
'talkpagelinktext'  => 'Дийца',
'talk'              => 'Дийца',
'toolbox'           => 'вабанаш',
'viewtalkpage'      => 'Зен дийца cайт',
'otherlanguages'    => 'Вуьш маттахь дерш',
'jumptosearch'      => 'Лахар',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{grammar:genitive|{{SITENAME}}}}х лаьцна',
'aboutpage'            => 'Project:Цунах лаьцна',
'currentevents'        => 'Гулам',
'currentevents-url'    => 'Project:Гулам',
'disclaimers'          => 'Бехк ТIицалацар',
'edithelp'             => 'Справка',
'edithelppage'         => 'Help:Справка (керл кор)',
'helppage'             => 'Help:ГIo',
'mainpage'             => 'Коьртан АгIо',
'mainpage-description' => 'Коьртан АгIо',
'portal'               => 'Джамаат',
'portal-url'           => 'Project:Джамаат',
'privacy'              => 'Конфиденциальнийн политика',

'youhavenewmessages'      => 'Хьуна кхечи $1 ($2).',
'newmessageslink'         => 'Керла кехаташ',
'newmessagesdifflink'     => 'ТІаьххьара хийцам',
'youhavenewmessagesmulti' => '$1 тІехь хьуна керла кехат кхечи',
'editsection'             => 'xийца',
'editold'                 => 'Хийца',
'editsectionhint'         => 'Хийца секция: $1',
'toc'                     => 'Содержаний',
'showtoc'                 => 'доту',
'hidetoc'                 => 'цІанъян',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'таптар',
'nstab-user'      => 'юзер',
'nstab-image'     => 'Сурт',
'nstab-mediawiki' => 'хаам',
'nstab-category'  => 'Тоба',

# General errors
'error' => 'ГІалат',

# Login and logout pages
'yourname'           => 'Хьан цIе',
'yourpassword'       => 'Хьан тешаман дош',
'yourpasswordagain'  => 'Юха язде тешаман дош:',
'remembermypassword' => 'Даглац со хьокх компьютер тьях',
'yourdomainname'     => 'Хьан домен',
'login'              => 'Чу валар',
'loginprompt'        => 'Нагахь сан, чу вал лаахь «cookies» схьалат е.',
'userlogin'          => 'Чу валар',
'logout'             => 'Ар валар',
'userlogout'         => 'Ар валар',
'nologin'            => "Хьа хинца регистраций яц? '''$1'''.",
'nologinlink'        => 'Керл аккаунт кхолла',
'createaccount'      => 'Керл юзеран регистраци е',
'gotaccount'         => "Регистрации йолш вуй хьо? '''$1'''.",
'mailmypassword'     => 'Тешам дош хийца',
'accountcreated'     => 'Аккаунт кхоллна',
'accountcreatedtext' => '$1 юзер аккаунт кхоллна.',
'loginlanguagelabel' => 'Мотт: $1',

# Password reset dialog
'newpassword' => 'Керла тешаман дош:',

# Edit pages
'summary'         => 'Хийцами комментарий:',
'minoredit'       => 'Жим Хийцам',
'watchthis'       => 'TIяргалдеш таптарш юккхе язде',
'savearticle'     => 'ДIаязде Таптар',
'showpreview'     => 'Кеп Таптар',
'showdiff'        => 'Йин Xийцамаш',
'anoneditwarning' => "'''Тергам бе''': Хьо системан чувеана вац. Хьан IP-адрес дІаязайор ю хьар таптари терахь чу.",
'blockedtitle'    => 'Юзер заблокирован',
'accmailtitle'    => 'Тешаман дош дахьийтина.',
'accmailtext'     => '$1ий тешаман дош дахьийтина $2ан.',
'newarticle'      => '(Kерла)',
'newarticletext'  => "ХІар тептар хІинца а кхоьллина дац.
Керл тептар кхолла лаахь, дІаязде текст лахара кор чохь (см. [[{{MediaWiki:Helppage}}|гІо тептар]] еша кхин информацинаш хаар хьам).
Хьо кхуза гІалат вал кхаьчнехь, '''тІехьа воьрзу''' кнопку таІ йе хьан браузера тІехь.",
'editing'         => 'Хийца $1',
'editingsection'  => 'Хийца $1 (секция)',
'editingcomment'  => 'Хийца $1 (комментарий)',
'editconflict'    => 'Хийца Конфликт: $1',
'yourtext'        => 'Хьан текст',

# Diffs
'editundo' => 'саца',

# Search results
'searchhelp-url' => 'Help:ГIo',

# Preferences page
'mypreferences'    => 'сан настройки',
'changepassword'   => 'Тешаман дош хийцар хьам',
'prefs-watchlist'  => 'тергалдеш таптарш',
'prefs-editing'    => 'Xийца',
'youremail'        => 'И-пошта:',
'yourrealname'     => 'Хьан бакъ цІе:',
'yourlanguage'     => 'Хьан мотт:',
'yourvariant'      => 'Кепара мотт',
'prefs-help-email' => 'И-пошта, сил чIoгI оьшург пункт яц, амма и хилч кхийч юзерашан аьтто хир ду шуц хабари вал.',

# User rights
'editinguser' => "Хийца юзер '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",

# Recent changes
'recentchanges' => 'Керла хийцамаш',
'diff'          => 'хийцам',
'hist'          => 'терахь',

# Recent changes linked
'recentchangeslinked'         => 'Xиттина Xийцамаш',
'recentchangeslinked-feed'    => 'Xиттина Xийцамаш',
'recentchangeslinked-toolbox' => 'Xиттина Xийцамаш',

# Upload
'upload'   => 'Чуоза Файл',
'filename' => 'файл цIе',

# Special:ListFiles
'listfiles_name' => 'Файли цІе',
'listfiles_user' => 'юзер',

# File description page
'file-anchor-link' => 'Сурт',

# Random page
'randompage' => 'Ца хууш нисделла таптар',

'brokenredirects-delete' => 'дІадайа',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|байт|байтош|байтош}}',
'ncategories'       => '$1 {{PLURAL:$1|тоба|тобаш|тоба}}',
'newpages'          => 'Керла таптараш',
'newpages-username' => 'Юзер:',
'move'              => 'цIe хийца',

# Special:Log
'specialloguserlabel' => 'Юзер:',

# Special:AllPages
'allpages'       => 'Массо таптараш',
'allarticles'    => 'Массо таптараш',
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
'watchlist'    => 'тергалдеш таптарш',
'mywatchlist'  => 'Сан тергалдо список',
'watchnologin' => 'Деза чу валар',
'addedwatch'   => 'Т1етохха хьан тергалдо список чу',
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
