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

'about'         => 'Цунах лаьцна',
'article'       => 'Яззам',
'newwindow'     => '(керлачу корехь)',
'cancel'        => 'Цаоьшу',
'moredotdotdot' => 'Кхин дlа…',
'mypage'        => 'Долахь йолу агlо',
'mytalk'        => 'Сан дийцаре агlо',
'anontalk'      => 'Дийцаре хlара IP-долуметтиг',
'navigation'    => 'Шавигар',
'and'           => '&#32;а',

# Cologne Blue skin
'qbfind' => 'Лахар',
'qbedit' => 'Нисйé',

# Vector skin
'vector-action-delete'       => 'Дlадайá',
'vector-namespace-category'  => 'Кадегар',
'vector-namespace-image'     => 'Хlум',
'vector-namespace-mediawiki' => 'Хаам',
'vector-namespace-template'  => 'Куцкеп',

'errorpagetitle'    => 'Гlалат',
'tagline'           => 'Гlирс хlокхуьна бу {{grammar:genitive|{{SITENAME}}}}',
'help'              => 'Гlo',
'search'            => 'Лахар',
'searchbutton'      => 'Каро',
'go'                => 'Дехьа вала',
'searcharticle'     => 'Дехьа вала',
'history'           => 'исцlарера',
'history_short'     => 'Исцlарера',
'printableversion'  => 'Зорба туху башхо',
'permalink'         => 'Даиман йолу хьажориг',
'print'             => 'Зорба тоха',
'edit'              => 'Нисйé',
'delete'            => 'Дlадайá',
'deletethispage'    => 'Дlайайá хlара агlо',
'protect'           => 'Гlаролла дé',
'protect_change'    => 'хийца',
'protectthispage'   => 'Гlаролла дé хlокху агlон',
'unprotect'         => 'Гlароллех къаста',
'unprotectthispage' => 'Гlароллех къаста',
'newpage'           => 'Керла агlо',
'talkpage'          => 'Дийцаре йила хlара агlо',
'talkpagelinktext'  => 'Дийцаре',
'personaltools'     => 'Долахь болу гlирсаш',
'talk'              => 'Дийцаре',
'views'             => 'Хьажарш',
'toolbox'           => 'Гlирсаш',
'viewtalkpage'      => 'Хьажа дийцаре',
'otherlanguages'    => 'Кхечу маттахь дерш',
'jumpto'            => 'Дехьавала оцу:',
'jumptonavigation'  => 'шавигар',
'jumptosearch'      => 'лахар',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{grammar:genitive|{{SITENAME}}}}х лаьцна',
'aboutpage'            => 'Project:Цунах лаьцна',
'currentevents'        => 'Оьхаш дол хилларш',
'currentevents-url'    => 'Project:Оьхаш дол хилларш',
'disclaimers'          => 'Бехк тlе ца эцар',
'disclaimerpage'       => 'Project:Бяхк тlецалацар',
'edithelp'             => 'Собаркхе оцу редаккхарна',
'edithelppage'         => 'Help:Собаркхе оцу редаккхарна',
'helppage'             => 'Help:Собаркхе',
'mainpage'             => 'Коьрта агlо',
'mainpage-description' => 'Коьрта агlо',
'policy-url'           => 'Project:Бакъо',
'portal'               => 'Юкъаралла',
'portal-url'           => 'Project:Юкъараллин ков',
'privacy'              => 'Балалютта къайлаха',
'privacypage'          => 'Project:Балалютта къайлаха',

'retrievedfrom'           => 'Хьост — «$1»',
'youhavenewmessages'      => 'Хьуна кхечи $1 ($2).',
'newmessageslink'         => 'керла кехаташ',
'newmessagesdifflink'     => 'тlаьххьара хийцам',
'youhavenewmessagesmulti' => 'Хьуна кхаьчна керла хаам оцу $1',
'editsection'             => 'нисйé',
'editold'                 => 'нисйé',
'editlink'                => 'нисйé',
'viewsourcelink'          => 'Хьажар',
'editsectionhint'         => 'Нисде дакъа: $1',
'toc'                     => 'Чулацам',
'showtoc'                 => 'гайта',
'hidetoc'                 => 'дlайаккха',
'viewdeleted'             => 'Хьалххьожи $1?',
'site-rss-feed'           => '$1 — RSS-аса',
'site-atom-feed'          => '$1 — Atom-аса',
'red-link-title'          => '$1 (ишта агlо йоцуш йу)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Яззам',
'nstab-user'      => 'Декъашхо',
'nstab-image'     => 'Хlум',
'nstab-mediawiki' => 'Хаам',
'nstab-template'  => 'Куцкеп',
'nstab-help'      => 'Собаркхе',
'nstab-category'  => 'Кадегар',

# General errors
'error'               => 'Гlалат',
'missingarticle-rev'  => '(башхо № $1)',
'missingarticle-diff' => '(тейп тайпнара: $1, $2)',
'viewsource'          => 'Хьажар',
'viewsourcefor'       => 'Агlо «$1»',
'protectedpagetext'   => 'Хlара агlо дlакъойлина йу рé цадаккхийта.',
'viewsourcetext'      => 'Хьоьга далундерг хьажар а дезахь хlокху агlон чура йоза хьаэцар:',

# Login and logout pages
'yourname'                => 'Декъашхон цlе:',
'yourpassword'            => 'Ишар:',
'yourpasswordagain'       => 'Юха язйе ишар:',
'remembermypassword'      => 'Даглаца сан дlавазвалар хlокху гlулкхдечу гlирса тlяхь',
'yourdomainname'          => 'Хьан машан меттиг:',
'login'                   => 'Вовзийта хьой гlирсан',
'nav-login-createaccount' => 'Вовзийта хьой / дlавазло',
'loginprompt'             => 'Ахьа бакъо йала йеза оцу «cookies» хьайна вовзийта лаахь гlирсан.',
'userlogin'               => 'Чу вала йа дlавазло кхучу',
'logout'                  => 'Ара валар',
'userlogout'              => 'Ара валар',
'nologin'                 => "Хlинца дlа вазвин вац? '''$1'''.",
'nologinlink'             => 'Кхолла керла дlавазвалар',
'createaccount'           => 'Дlавазве керла декъашхо',
'gotaccount'              => "Хьо дlавазвина вуй? '''$1'''.",
'loginsuccesstitle'       => 'Хьо вовзар хаз чакхдели',
'mailmypassword'          => 'Схьаэца керла ишар',
'accountcreated'          => 'Дlавазвар кхоллина дели',
'accountcreatedtext'      => 'Кхоллина декъашхо дlавазвар $1.',
'loginlanguagelabel'      => 'Мотт: $1',

# Password reset dialog
'resetpass'                 => 'Ишар хийца',
'resetpass_text'            => '<!-- Кхузахь язъде хьай йоза -->',
'resetpass_header'          => 'Жамlаш дlаязвеллачуьна ишар хийцар',
'oldpassword'               => 'Шираелла ишар:',
'newpassword'               => 'Керла ишар:',
'retypenew'                 => 'Юха язйе керла ишар:',
'resetpass-submit-loggedin' => 'Хийца ишар',
'resetpass-submit-cancel'   => 'Цаоьшу',

# Edit page toolbar
'extlink_sample' => 'http://www.example.com хьажориг корта',

# Edit pages
'summary'                          => 'Хийцамех лаьцна:',
'subject'                          => 'Дlахьедар/коьрта могlа:',
'minoredit'                        => 'Жим хийцам',
'watchthis'                        => 'Латайе хlара агlо тергаме могlам юкъа',
'savearticle'                      => 'Дlаязйе агlо',
'preview'                          => 'Хьалха муха ю хьажа',
'showpreview'                      => 'Хьалха муха ю хьажар',
'showdiff'                         => 'Хlоттина болу хийцам',
'anoneditwarning'                  => "'''Тергам бе''': Ахьа хьо вовзитина вац гlирсан. Хьан IP-долу меттиг дlаязйина хира ю хlокху агlон исцlрера чу.",
'blockedtitle'                     => 'Декъашхо сацийна',
'accmailtitle'                     => 'Ишар дlаяхьийтина.',
'accmailtext'                      => "Ишар декъашхочуьна [[User talk:$1|$1]], йина ша шех хитта делла чу элпашах, дlаяхийтина хьокху хааман зlен чу $2.

Дlаязвинчултlяхьа, кху гlирса чохь шуьга хийцалур ю ''[[Special:ChangePassword|шай ишар]]''.",
'newarticle'                       => '(Kерла)',
'newarticletext'                   => "Хьо веана хьажоригци хlокху агlон тlе, хlара агlо хlинца йоцаш ю.
Нагахь иза кхолла лаахь, хlотта де лахо гуш долу корехь йоза (мадарра хьажа. [[{{MediaWiki:Helppage}}|гlон агlон чу]]).
Нагахь гlалат даьлла нисвелляхь кхузе, атта тlе тlаlа йе '''юха йоккхуриг''' хьай гlирса тlяхь.",
'anontalkpagetext'                 => "----''Хlара дийцаре агIо къайлаха волу декъашхочуьна  ю, хlинца дlавазвина воцуш, йа лелош воцуш. 
Цундела иза вовзийта лелош ду терахьца IP-долу метаг.
Иза терахь долу меттиг хила мега кхечу декъашхойчух терра.
Нагахь хьо къайлах волу декъашхо валахь хьайна хаам кхаьчна аьлла хеташн, хьуна хьажийна доцуш, дехар до, кхолла хьай меттиг кху чохь[[Special:UserLogin/signup|дlавазло]] йа [[Special:UserLogin|хьой вовзийта]],",
'noarticletext'                    => "<div id=\"noarticletext\" class=\"plainlinks\" style=\"padding-left: 2em; padding-right: 2em\">
Хlокх Энасихалпаде жайни чохь '''дац ишта цIе йолуш {{#if:{{NAMESPACE}}|агIонаш|яззамаш}}'''.
{{#ifexist:{{FULLPAGENAME}})|:Хила мега, ахьа боху яззам агIо <strong style=\"font-size:135%\">[[{{FULLPAGENAME}})]]</strong>}}
Шуьга далундерг:

* '''[[Special:Search/{{PAGENAME}}|лаха]]''' хьехош йолун цIе;
* [[Special:WhatLinksHere/{{FULLPAGENAME}}|лаха йозан агIонаш]], муьлхарш йу хьажжина оцуьнах терра.
----
Йуха а шуьга далундерг изацалуш ишта '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} яззам кхоллар]'''{{#if:{{NAMESPACE}}||, оцун далурду гIо [[Википедийа:Болх сихо бола балитарна хьехар|Болх сихо бола балитарна хьехар]]}}

</div>",
'noarticletext-nopermission'       => 'Хlокх хан чохь кху яззамехь йоза дац.
Шуьга далундерг [[Special:Search/{{PAGENAME}}|лахар ишта агlо]] кхечу яззамехь,
йа <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} лаха кхечу тептаршкахь].</span>',
'editing'                          => 'Редаккхар: $1',
'editingsection'                   => 'Редаккхар $1 (дакъа)',
'editingcomment'                   => 'Редаккхар $1 (керла дакъа)',
'editconflict'                     => 'Редаккхарна дойнаш: $1',
'yourtext'                         => 'Хьан йоза',
'protectedpagewarning'             => "'''Дlахьедар. Хlара агlо гlаролла дина ю хийцам цабайта, иза хийца йа нисйа а бакъо йолуш адаманкуьйгалла лелош болу декъашхой бе бац.'''
Лахахьа гойту хаамаш тlаьхьара бина болу хийцамна тептар чура:",
'cascadeprotectedwarning'          => "'''Дlахьедар:''' Хlокху агlонна редаккха бакъо йолуш хlара тоба йу «Адаманкуьйгалхой», хlунда аьлча иза латийна {{PLURAL:$1|кхечу агlонца|кхечу агlонашца}} хlоттделлачу гlароллийца:",
'templatesused'                    => '{{PLURAL:$1|Куцкеп, лелийна|Куцкепаш, лелош ду}} хlокху агlон башхонца:',
'template-protected'               => '(гlароллийца)',
'template-semiprotected'           => '(дуьззина доцуш гlаролла)',
'permissionserrorstext'            => 'Хьан бакъо яц кхочуш хилийта хийцам оцу {{PLURAL:$1|шолгlа бахьанца|шолгlа бахьанашца}}:',
'permissionserrorstext-withaction' => "Хьан бакъо яц хlумда «'''$2'''» оцу {{PLURAL:$1|шолгlа бахьанца|шолгlа бахьанашца}}:",
'moveddeleted-notice'              => 'Иза агlо дlайайина йара.
Оцу собаркхен лахахьа гойтуш ю цуьнца долу дlаяздарш кху дlадайина тептар чура а цlе хийцарш а.',

# Parser/template warnings
'parser-template-loop-warning' => 'Карийна куцкепаш юкъахь хилла шад: [[$1]]',

# "Undo" feature
'undo-success' => 'Нисйинарг а тlе цалаца мега. Дехар до, хьажа цхьатерра йуй башхо, тешна хила, баккъалла иза хийцам буйте хьуна безарг, тlакха тlе таlайе «дlайазйе агlо», хийцам хlотта ба.',

# Revision deletion
'rev-delundel'      => 'гайта/къайладаккха',
'rev-showdeleted'   => 'гайта',
'revdelete-content' => 'чуьраниг',

# Revision move
'revmove-reasonfield' => 'Бахьан:',

# History merging
'mergehistory-reason' => 'Бахьан:',

# Diffs
'lineno'                   => 'Могlа $1:',
'showhideselectedversions' => 'Гайта/къайлайаха хаьржина башхонаш',
'editundo'                 => 'дlадаккха',

# Search results
'searchmenu-legend'         => 'Лахарна гlирс нисба',
'searchhelp-url'            => 'Help:Чулацам',
'search-result-size'        => '$1 ({{PLURAL:$2|$2 дош|$2 дешнаш|$2 дешнаш}})',
'search-mwsuggest-enabled'  => 'хьехаршца',
'search-mwsuggest-disabled' => 'хьехар доцуш',
'powersearch-toggleall'     => 'Массо',

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
'timezonelegend'      => 'Сахьатан аса:',
'prefs-searchoptions' => 'Лахарна гlирс нисба',
'prefs-files'         => 'Хlумнаш',
'youremail'           => 'Кехат яздо зlе цlе:',
'username'            => 'Дlаязвиначуьна цlе:',
'yourrealname'        => 'Хьан бакъ цlе:',
'yourlanguage'        => 'Мотт юкъардекъа:',
'yourvariant'         => 'Метта башхо',
'gender-unknown'      => 'хlоттийна яц',
'gender-male'         => 'борша',
'gender-female'       => 'сте',
'prefs-help-email'    => 'Кехат яздо зlе цlе цахlоттийча а хlум дац, иза оьшар ю, нагахь хьуна хьай ишар йицлахь.
Цо атто бийра бу кхечу декъашхошна a хьан кху чура декъа агlонца хьега хаам бахьийта.',
'prefs-diffs'         => 'Тейп тайпнара башхо',

# User rights
'userrights'  => 'Декъашхочуьн бакъона урхалладар',
'editinguser' => "Хийца декъашхочуьна бакъо '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",

# Groups
'group'      => 'Тоба:',
'group-user' => 'Декъашхой',
'group-all'  => '(массо)',

'group-user-member' => 'декъашхо',

'grouppage-user' => '{{ns:project}}:Декъашхой',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'нисйа хlара агlо',

# Recent changes
'nchanges'                     => '$1 {{PLURAL:$1|хийцам|хийцамаш|хийцамаш}}',
'recentchanges'                => 'Керла нисдарш',
'recentchanges-legend'         => 'Гlирс нисбарна керла нисдарш',
'recentchanges-legend-newpage' => '$1 — керла агlо',
'recentchanges-label-newpage'  => 'Оцу нисдарца кхоллина керла агlо.',
'rcnote'                       => "{{PLURAL:$1|Тlаьххьара '''$1''' хийцам|Тlаьххьара '''$1''' хийцамаш|Тlаьххьара '''$1''' хийцамаш}} за '''$2''' {{PLURAL:$2|де|дийнахь|дийнахь}}, оцу хан чохь $5 $4.",
'rclistfrom'                   => 'Гайта хийцам оцу $1.',
'rcshowhideminor'              => '$1 кегийра нисдарш',
'rcshowhideliu'                => '$1 вовзитар долу декъашхой',
'rcshowhideanons'              => '$1 хьулбелларш',
'rcshowhidemine'               => '$1 айхьа нисдинарш',
'diff'                         => 'башхонаш.',
'hist'                         => 'исцlарера',
'hide'                         => 'Къайла яккха',
'show'                         => 'Гайта',
'rc_categories_any'            => 'Муьлхаа',
'newsectionsummary'            => '/* $1 */ Керла хьедар',

# Recent changes linked
'recentchangeslinked'         => 'Кхуьнца долу нисдарш',
'recentchangeslinked-feed'    => 'Кхуьнца долу нисдарш',
'recentchangeslinked-toolbox' => 'Кхуьнца долу нисдарш',
'recentchangeslinked-title'   => 'Кхуьнца долу нисдарш $1',

# Upload
'upload'            => 'Чуйаккха хlум',
'uploadlogpagetext' => 'Лахахьа гойтуш бу могlам тlаьххьара чуяхна хlумнаши. Ишта хьажа. [[Special:ImageList|хlумнаши могlам]] йа [[Special:NewImages|галеларе хlумнаши]].',
'filename'          => 'Хlуман цlе',
'uploadwarning'     => 'Дlахьедар',
'upload-wasdeleted' => "'''Тергам бе: ахьа чуйаккха хьийзошйолу хlума, хьалхо дlайайана хlума ю.'''

Юха а хьажа, баккъалла хьуна оьшуш йуй и хlумма. Лахахь далийна дlадайарна тéптар.",

# Special:ListFiles
'imgfile'               => 'хlум',
'listfiles'             => 'Хlумнаши могlам',
'listfiles_name'        => 'Хlуман цlе',
'listfiles_user'        => 'Декъашхо',
'listfiles_size'        => 'Барам',
'listfiles_description' => 'Цунах лаьцна',

# File description page
'file-anchor-link'   => ' Хlум',
'filehist-deleteall' => 'дlадайá массо',
'filehist-deleteone' => 'дlадайá',
'filehist-user'      => 'Декъашхо',

# File deletion
'filedelete-legend' => 'Дlайайá и хlум',
'filedelete-submit' => 'Дlадайá',

# Random page
'randompage' => 'Ца хууш нисйелла агlо',

# Statistics
'statistics-pages' => 'Агlонаш',

'double-redirect-fixed-move' => 'Агlон [[$1]] цlе хийцна, хlинца иза дlахьажийна оцу [[$2]]',

'brokenredirects-edit'   => 'нисйé',
'brokenredirects-delete' => 'дlадайá',

'withoutinterwiki-submit' => 'Гайта',

# Miscellaneous special pages
'nbytes'              => '$1 {{PLURAL:$1|цlинцl|цlинцlа|цlинацl}}',
'ncategories'         => '$1 {{PLURAL:$1|кадегар|кадегарш|кадегарш}}',
'unusedcategories'    => 'Йаьсса кадегарш',
'wantedtemplates'     => 'Оьшуш долу куцкепаш',
'mostlinkedtemplates' => 'Массарел дуккха а леладо куцкепаш',
'listusers'           => 'Декъашхой могlам',
'newpages'            => 'Керла агlонаш',
'newpages-username'   => 'Декъашхо:',
'ancientpages'        => 'Яззамаш оцу терахьца тяххьара редаккхар дина долу',
'move'                => 'Цlе хийца',
'unusedimagestext'    => 'Дехар до, тидаме эца, кхин йолу дуьнана машан-меттигаш а лелош хила мега нисса йогlу хьажориг (URL) хlокху хlуман, хlокху могlаме йогlуш ялахь яцахь а иза хила мега жигара лелош.',

# Book sources
'booksources'    => 'Жайнан хьосташ',
'booksources-go' => 'Лаха',

# Special:Log
'specialloguserlabel'  => 'Декъашхо:',
'speciallogtitlelabel' => 'Корта:',
'log'                  => 'Тéптарш',
'all-logs-page'        => 'Деригге тléкхочучéхь долу тéптарш',
'alllogstext'          => 'Массо тéптар могlам. {{SITENAME}}.
Шуьга харжалур бу хилам оцу тептаре хьаьжжина, декъашхон цlе (дlаязвар диц а цадеш) йа иза хьакхавелла агlонаш (ишта дlаязвар а диц цадеш).',

# Special:AllPages
'allpages'         => 'Массо агlонаш',
'alphaindexline'   => 'оцу $1 кху $2',
'allpagesfrom'     => 'Гучé яха агlонаш, йуьлалуш йолу оцу:',
'allarticles'      => 'Массо агlонаш',
'allinnamespace'   => 'Массо агlонаш оцу цlери анахь «$1»',
'allpagesnext'     => 'Тlаьхьайогlурш',
'allpagessubmit'   => 'Кхочушдé',
'allpagesprefix'   => 'Лаха агlонаш, дlайуьлалуш йолу:',
'allpagesbadtitle' => 'Цамагош йолу агlон цlе. Коьрта могlан юкъах ю юкъарвики меттанашан юкъе тlечlагlйина йолу хьаьрк йа магийна доцу оцу коьрта моlанца сабол элп йа кхин.',
'allpages-bad-ns'  => '{{SITENAME}} кху чохь ана цlераш яц «$1».',

# Special:Categories
'categories' => 'Кадегарш',

# Special:DeletedContributions
'deletedcontributions'             => 'Декъашхочуьн дlабайина къинхьегам',
'sp-deletedcontributions-contribs' => 'къинхьегам',

# Special:LinkSearch
'linksearch-ok' => 'Лаха',

# Special:ListUsers
'listusers-submit' => 'Гайта',

# Special:ActiveUsers
'activeusers' => 'Жигар декъашхой могlам',

# Special:Log/newusers
'newuserlogpage'              => 'Декъашхой дlабазбина тептар',
'newuserlog-create-entry'     => 'Керла декъашхо',
'newuserlog-autocreate-entry' => 'Дlайазвар кхоллина ша шех',

# Special:ListGroupRights
'listgrouprights'          => 'Декъашхойн тобанаши бакъонаш',
'listgrouprights-group'    => 'Тоба',
'listgrouprights-helppage' => 'Help:Тобан бакъонаш',

# E-mail user
'emailuser'       => 'Декъашхочун хааман кехат',
'defemailsubject' => 'Хаам кхузар {{grammar:genitive|{{SITENAME}}}}',
'emailmessage'    => 'Хаам:',

# Watchlist
'watchlist'      => 'Тергаме могlам',
'mywatchlist'    => 'Тергаме могlам',
'watchnologin'   => 'Хьо вовзита веза гlирсан',
'addedwatch'     => 'Юькъатоьхна тергаме могlамна',
'addedwatchtext' => 'Хlар агlо «[[:$1]]» тlетоьхна хьан [[Special:Watchlist|тидаме могlам чу]].
Тlаьхьабогlу хийцамаш хlокх агlонна а кхунца дозуш долу дийцаре агlо а дlаяздийра ду кху могlамашкахь, ишта къастина хирду уьш шуьрочу элпашца хlокх агlон чохь [[Special:RecentChanges|керла хийцаме могlамашкахь]], бгlаьран га атту болуш.',
'watch'          => 'Тидам бе',
'notanarticle'   => 'Дац яззам',
'wlnote'         => 'Лахахьа {{PLURAL:$1|тlаьхьа богlу $1 хийцам|тlаьхьа богlу $1 хийцамаш|тlаьхьа богlу $1 хийцамаш}} хlокху {{PLURAL:$2|тlаьхьар|тlаьхьара|тlаьхьара}} <strong>$2</strong> {{plural:$2|сохьт|сохьатехь|сохьташкахь}}.',
'wlshowlast'     => 'Гайта тlаьххьара $1 сахьташ $2 денош $3',

'enotif_newpagetext' => 'Хlара керла агlо ю.',

# Delete
'deletepage'      => 'Дlайайá агlо',
'confirm'         => 'Къобалде',
'excontent'       => 'чуьраниг: «$1»',
'excontentauthor' => 'чуьраниг: «$1» (дуьххьара кхоллина да вара иза [[Special:Contributions/$2|$2]])',
'exbeforeblank'   => 'чуьраниг дlацlанйале хьалха: «$1»',
'exblank'         => 'агlо йаьсса йара',
'delete-legend'   => 'Дlадайáр',
'deletedarticle'  => 'дlадайинарг «[[$1]]»',
'dellogpage'      => 'Дlадайарш долу тéптар',
'deletionlog'     => 'дlадайарш долу тéптар',

# Protect
'protectedarticle'          => 'гlаролла дина агlо «[[$1]]»',
'prot_1movedto2'            => '«[[$1]]» цlе хийцина оцу «[[$2]]»',
'protectcomment'            => 'Бахьан:',
'protect-expiry-indefinite' => 'хан чаккхе йоцуш',

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
'sp-contributions-search'     => 'Къинхьегам лахар',
'sp-contributions-submit'     => 'Лаха',

# What links here
'whatlinkshere'         => 'Хьажоригаш кхузе',
'whatlinkshere-page'    => 'Агlо:',
'isredirect'            => 'агlо-дlасахьажайар',
'istemplate'            => 'лата йe',
'whatlinkshere-filters' => 'Литтарш',

# Block/unblock
'blockip'                => 'Сацаве',
'ipadressorusername'     => 'IP-долу меттиг йа декъашхон цlе:',
'blockipsuccesssub'      => 'Сацавар чакхдели',
'blockipsuccesstext'     => '[[Special:Contributions/$1|«$1»]] сацийна ву.<br />
Хьажа. [[Special:IPBlockList|могlам сацийна IP-долу меттигаш]].',
'ipb-blocklist-contribs' => 'Декъашхон къинхьегам $1',
'unblocked'              => '[[User:$1|$1]] хьайаьстина.',
'ipblocklist'            => 'Сацийна IP-долу меттиг а дlалаьрра язбаларш',
'ipblocklist-submit'     => 'Лаха',
'blocklink'              => 'сацаве',
'contribslink'           => 'къинхьегам',

# Move page
'move-page'               => '$1 — цlе хийцар',
'movearticle'             => 'Цle хийца хlокху агlон',
'1movedto2'               => 'цlе хийцина «[[$1]]» оцу «[[$2]]»',
'delete_and_move'         => 'Цle а хуьйцуш дlадайá',
'delete_and_move_confirm' => 'Хlаъ, дlайайъа хlара агlо',

# Export
'exporttext'       => 'Шуьга далур ду кхечу меттера чудахарш, йоза а хийцаме тептарш билгалла йолу агlонаш йа гулдина йолу агlонаш хlокх XML барамца, йуха тlяхьа чура [[Special:Import|хьаэцалурдолш]] кхечу вики-хьалхен, болх беш йолу хlокху MediaWiki гlирсаца.

Кхечу меттера яззамаш чуйаха, чуязйе цlе редокхчу метте, цlхьа могlан цlе могlаршкахь, йуха харжа лаьи шуна Кхечу меттер чуйаха массо яззамашна исцlрера хийцамбарш йа тlяхьаралера яззамна башхо.

Шуьга кхи даландерг, лелаеш йолу меттиг къастаман машан хьажориг кхечу меттер чудаха тlяхьарлера башхон яззамаш. Массала оцу яззамна [[{{MediaWiki:Mainpage}}]] хlара хира йу хьажориг [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]].',
'export-templates' => 'Латадé куцкепаш',

# Namespace 8 related
'allmessages'                   => 'Гlирса хаамаш',
'allmessagesname'               => 'Хаам',
'allmessagesdefault'            => 'Шаьшха йоза',
'allmessagescurrent'            => 'Карарчу хенан йоза',
'allmessages-filter-legend'     => 'Литтар',
'allmessages-filter'            => 'Литтар оцу хьола хийцамца:',
'allmessages-filter-unmodified' => 'Хийцан йоцурш',
'allmessages-filter-all'        => 'Массо',
'allmessages-filter-modified'   => 'Хийцнарш',
'allmessages-prefix'            => 'Литтар оцу дешахьалхе:',
'allmessages-language'          => 'Мотт:',
'allmessages-filter-submit'     => 'Дехьа вала',

# Thumbnails
'thumbnail-more' => 'Доккха де',

# Special:Import
'import'                     => 'Кхин яззам агlонаш чуяхар',
'import-interwiki-source'    => 'Вики-хьост/агlо:',
'import-interwiki-templates' => 'Лата де массо куцкепаш',
'import-upload-filename'     => 'Хlуман цlе:',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Декъашхочуьна агlо',
'tooltip-pt-mytalk'              => 'Сан дийцаре агlо',
'tooltip-pt-preferences'         => 'Хьан гlирс нисбар',
'tooltip-pt-watchlist'           => 'Ахьа тергам бо агlонаши хийцаман могlам',
'tooltip-pt-mycontris'           => 'Хьан нисдаран могlам',
'tooltip-pt-logout'              => 'Дlадерзадо болх бар',
'tooltip-ca-talk'                => 'Дийцаре агlон чулацам',
'tooltip-ca-edit'                => 'Хlара агlо хийцалур ю. Лелайе, дехар до, хьалххьажар айхьа чутохале lалашан',
'tooltip-ca-addsection'          => 'Кхолла керла дакъа',
'tooltip-ca-history'             => 'Хlокху агlон хийцамаш болу тептар',
'tooltip-ca-protect'             => 'Гlаролла дé хlокху агlон хийцам цабайта',
'tooltip-ca-unprotect'           => 'Дlадаккха хlокху агlонна долу гаролла',
'tooltip-ca-delete'              => 'Дlайайá хlара агlо',
'tooltip-ca-move'                => 'Агlон цlе хийца',
'tooltip-ca-watch'               => 'Тlетоха хlара агlо сан тергаме могlам юкъа',
'tooltip-ca-unwatch'             => 'Дlайаккха хlара агlо хьай тергаме могlам юкъар',
'tooltip-search'                 => 'Лаха иза дош',
'tooltip-search-go'              => 'Билгала и санна цlе йолучу агlон чу дехьа вала',
'tooltip-search-fulltext'        => 'Лаха агlонаш ше чулацамехь хlара йоза долуш',
'tooltip-p-logo'                 => 'Коьрта агIо',
'tooltip-n-mainpage'             => 'Дехьавалар коьрта агlончу',
'tooltip-n-mainpage-description' => 'Дехьавалар коьрта агlончу',
'tooltip-n-portal'               => 'Оцу кхолламах, мичахь хlу йу лаьташ а хlудалур ду шуьга',
'tooltip-n-currentevents'        => 'Дlаоьхуш болу хаамашна могlам',
'tooltip-n-recentchanges'        => 'Тlаьххьаралера хийцаман могlам',
'tooltip-n-randompage'           => 'Хьажа цахууш нисйеллачу агlоне',
'tooltip-n-help'                 => 'Собаркхе оцу кхоллаца «{{SITENAME}}»',
'tooltip-t-whatlinkshere'        => 'Массо агlон могlам, хlокху агlонтlе хьажийна йолу',
'tooltip-t-recentchangeslinked'  => 'Тlаьхьарлера хийцамаш хlокху агlонашкахь, мичхьа хьажийна хlара агlо',
'tooltip-t-upload'               => 'Чудаха суьрташ йа шагойтуш йолу хlумнаш',
'tooltip-t-specialpages'         => 'Белха агlонаши могlам',
'tooltip-t-print'                => 'Хlокху агlонна зорба туху башхо',
'tooltip-t-permalink'            => 'Даимна йолу хьажориг хlокху башха агlонна',
'tooltip-ca-nstab-main'          => 'Яззамна чулацам',
'tooltip-ca-nstab-user'          => 'Хlора декъашхон долахь йолу агlо',
'tooltip-ca-nstab-media'         => 'Медиа-хlум',
'tooltip-ca-nstab-project'       => 'Кхолламан дакъа',
'tooltip-ca-nstab-mediawiki'     => 'Хааман агlо MediaWiki',

# Attribution
'siteuser'  => 'декъашхо {{grammar:genitive|{{SITENAME}}}} $1',
'others'    => 'кхин',
'nocredits' => 'Бац декъашхойн могlам хlокху яззамца',

# Spam protection
'spamprotectiontitle' => 'Совбиларна литтар',

# Info page
'numedits'   => 'Нисдарна терахь (яззам): $1',
'numauthors' => 'Тейп тайпан куьйга дай (яззам): $1',

# Browsing diffs
'nextdiff' => 'Тlяхьа догlа нисдинарг →',

# Media information
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|агlо|агlонаш|агlонаш}}',
'show-big-image'  => 'Сурт цlанал лакхаро бакъонца',

# Special:NewFiles
'newimages'        => 'Галеларе керлачу хlумни',
'newimages-legend' => 'Литтар',
'ilsubmit'         => 'Лаха',

# Bad image list
'bad_image_list' => 'Барам хила беза ишта:

Лораш хира йу могlамяхь йолу хlумнаш (могlийн, йола луш йолу сабол тlира *).
Дуьхьаралера хьажориг магlарши хила беза хьажориг кху цамагдо сурт дуьлаче.
Тlяхьа йогlуш йолу хьажориг оцу могlарехь хира йу магóш, билгалла аьлча яззамаш долуче, сурт хьаллаточехь.',

# EXIF tags
'exif-datetime'         => 'Хlума хийцина терахь а хан',
'exif-datetimeoriginal' => 'Дуьххьарлера терахь а хан',
'exif-cfapattern'       => 'Бос литтар тайт',

'exif-scenetype-1' => 'Суд ша даьккхина нис дуьххьал',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'массо',
'imagelistall'     => 'массо',
'watchlistall2'    => 'массо',
'namespacesall'    => 'массо',
'monthsall'        => 'массо',
'limitall'         => 'массо',

# Trackbacks
'trackbackremove' => '([$1 дlадайá])',

# Multipage image navigation
'imgmultipageprev' => '← хьалхара агlо',
'imgmultipagenext' => 'тlаьхьара агlо →',
'imgmultigo'       => 'Дехьавала!',
'imgmultigoto'     => 'Дехьавала агlончу $1',

# Table pager
'table_pager_next'         => 'Тlаьхьа йогlу агlо',
'table_pager_prev'         => 'Хьалха йоьду агlо',
'table_pager_first'        => 'Дуьххьаралера агlо',
'table_pager_last'         => 'Тlаьххьаралера агlо',
'table_pager_limit'        => 'Гайта $1 хlумнаш агlон тlаьхь',
'table_pager_limit_submit' => 'Кхочушдé',
'table_pager_empty'        => 'Цакарийна',

# Auto-summaries
'autosumm-blank' => 'Агlон чулацам дlабайина',
'autosumm-new'   => 'Керла агlо: «$1»',

# Live preview
'livepreview-loading' => 'Чуйолуш…',
'livepreview-ready'   => 'Чуйолуш… Кийча йу!',

# Watchlist editor
'watchlistedit-normal-submit' => 'Дlадайá язъдинарш',

# Special:Version
'version' => 'Башхо MediaWiki',

# Special:FilePath
'filepath-page'   => 'Хlум:',
'filepath-submit' => 'Дехьавала',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Лаха',

# Special:SpecialPages
'specialpages'                   => 'Леррина агlонаш',
'specialpages-note'              => '----
* Гуттарлера белха агlонаш.
* <strong class="mw-specialpagerestricted">Кlезиг таронаш йолу леррина агlонаш.</strong>',
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

# Special:BlankPage
'blankpage' => 'Йаьсса агlо',

# Special:Tags
'tags'                 => 'Болш болу хийцаман къастам',
'tag-filter'           => 'Литтар [[Special:Tags|къастам]]:',
'tag-filter-submit'    => 'Литта',
'tags-hitcount-header' => 'Къастам бина нисдарш',
'tags-edit'            => 'нисйé',
'tags-hitcount'        => '$1 {{PLURAL:$1|хийцам|хийцамаш|хийцамаш}}',

# Add categories per AJAX
'ajax-add-category'            => 'Тlетоха кадегар',
'ajax-confirm-title'           => 'Тlечlагlде дийриг',
'ajax-confirm-save'            => 'lалашдан',
'ajax-add-category-summary'    => 'Тlетоьхна кадегар «$1»',
'ajax-remove-category-summary' => 'ДIайакхина кадегар «$1»',
'ajax-error-title'             => 'Гlалат',
'ajax-error-dismiss'           => 'Хlаъ',

);
