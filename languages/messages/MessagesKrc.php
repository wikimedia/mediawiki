<?php
/** Karachay-Balkar (Къарачай-Малкъар)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Don Alessandro
 * @author Iltever
 */

$fallback = 'ru';

$messages = array(
# Dates
'january'       => 'январь',
'february'      => 'февраль',
'march'         => 'март',
'april'         => 'апрель',
'may_long'      => 'май',
'june'          => 'июнь',
'july'          => 'июль',
'august'        => 'август',
'september'     => 'сентябрь',
'october'       => 'октябрь',
'november'      => 'ноябрь',
'december'      => 'декабрь',
'january-gen'   => 'январь',
'february-gen'  => 'февраль',
'march-gen'     => 'март',
'april-gen'     => 'апрель',
'may-gen'       => 'май',
'june-gen'      => 'июнь',
'july-gen'      => 'июль',
'august-gen'    => 'август',
'september-gen' => 'сентябрь',
'october-gen'   => 'октябрь',
'november-gen'  => 'ноябрь',
'december-gen'  => 'декабрь',
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
'pagecategories'         => '{{PLURAL:$1|Категориясы|Категориялары}}',
'category_header'        => '«$1» категориядагъы бетле',
'subcategories'          => 'Тюбкатегорияла',
'hidden-categories'      => '{{PLURAL:$1|Джашырылгъан категория|Джашырылгъан категорияла}}',
'category-subcat-count'  => '{{PLURAL:$2|Бу категориягъа къуру баргъан тюбкатегория киреди.|$2 тюбкатегориядан $1 киреди бу категориягъа.}}',
'category-article-count' => '{{PLURAL:$2|Бу категорияда къуру бир бет барды.|Бу категориядагъы $2 бетден $1 кёргюзюлюннгенди.}}',
'listingcontinuesabbrev' => '(баргъаны)',

'newwindow'  => '(джангы терезеде ачылады)',
'cancel'     => 'Ызына алыу',
'mytalk'     => 'Мени сюзюу бетим',
'navigation' => 'Навигация',

# Cologne Blue skin
'qbfind' => 'Излеу',

'errorpagetitle'   => 'Хата',
'returnto'         => '$1 бетге къайт',
'tagline'          => '{{SITENAME}} сайтдан',
'help'             => 'Болушлукъ',
'search'           => 'Излеу',
'searchbutton'     => 'Таб',
'searcharticle'    => 'Кёч',
'history'          => 'Бетни тарихи',
'history_short'    => 'Тарих',
'printableversion' => 'Басмагъа версиясы',
'permalink'        => 'Дайым линк',
'edit'             => 'Тюзетиу',
'create'           => 'Къура',
'editthispage'     => 'Бу бетни тюзет',
'delete'           => 'Къурут',
'protect'          => 'Джакъла',
'protect_change'   => 'тюрлендир',
'newpage'          => 'Джангы бет',
'talkpage'         => 'Бу бетни сюз',
'talkpagelinktext' => 'сюзюу',
'personaltools'    => 'Энчи инструментле',
'talk'             => 'Сюзюу',
'views'            => 'Къараула',
'toolbox'          => 'Инструментле',
'otherlanguages'   => 'Башха тилледе',
'redirectedfrom'   => '($1 бетден джиберилгенди)',
'redirectpagesub'  => 'Башха бетге джибериучю бет',
'lastmodifiedat'   => 'Бу бетни ахыр тюрлениулери: $2, $1.',
'jumpto'           => 'Бери кёчерге:',
'jumptonavigation' => 'навигация',
'jumptosearch'     => 'излеу',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => '{{SITENAME}} атлы сайтны юсюнден',
'aboutpage'      => 'Project:Ачыкълау',
'copyright'      => 'Информация мунга кёре хайырланады: $1',
'copyrightpage'  => '{{ns:project}}:Авторлыкъ хакълары',
'disclaimers'    => 'Джууаплылыкъны унамау',
'disclaimerpage' => 'Project:Джууаплылыкъны унамау',
'edithelp'       => 'Тюзетиуню юсюнден болушлукъ',
'edithelppage'   => 'Help:Тюзетиу',
'helppage'       => 'Help:Болушлукъ',
'mainpage'       => 'Баш бет',
'privacy'        => 'Джашыргъанлыкъ политика',
'privacypage'    => 'Project:Джашыргъанлыкъ политика',

'badaccess' => 'Кириуню хатасы',

'retrievedfrom'       => 'Чыкъгъаны — «$1»',
'youhavenewmessages'  => 'Сизге $1 келди ($2).',
'newmessageslink'     => 'джангы билдириуле',
'newmessagesdifflink' => 'сюзюу бетигизни ахыр тюрлениуи',
'editsection'         => 'тюзетиу',
'editold'             => 'тюзет',
'editlink'            => 'тюзетиу',
'viewsourcelink'      => 'башланнган кодха къара',
'editsectionhint'     => 'Тюзетирге секцияны: $1',
'toc'                 => 'Башлары',
'showtoc'             => 'кёгюз',
'hidetoc'             => 'джашыр',
'site-rss-feed'       => '$1 — RSS-лента',
'site-atom-feed'      => '$1 — Atom лентасы',
'page-rss-feed'       => '«$1» — RSS-лентасы',
'page-atom-feed'      => '«$1» — Atom-лентасы',
'red-link-title'      => '$1 быллай бет джокъду',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Статья',
'nstab-user'     => 'Къошулуучуну бети',
'nstab-special'  => 'Специал бет',
'nstab-project'  => 'Проектни бети',
'nstab-image'    => 'Файл',
'nstab-template' => 'Шаблон',
'nstab-category' => 'Категория',

# General errors
'missing-article'    => 'Билгилени базасында табылыргъа керек болгъан, сорулгъан бетни тексти табылмагъанды, «$1» $2.

Быллай турум, къоратылгъан бетни, тюрлениулерини тарихине эски болгъан линк бла, кирирге изленсе болады.

Иш мында тюл эсе, сиз программа баджарыуда халат табхансыз. Халатны юсюнден URL -ни билдириб [[Special:ListUsers/sysop|администраторгъа]] билдиригиз.',
'missingarticle-rev' => '(версия № $1)',
'badtitletext'       => 'Сорулгъан бетни аты терсди, бошду, неда интервики аты терс джазылгъанды. Келишмеген (хайырланыргъа болмагъан) символла хайырланыргъада боллукъдула атында.',
'viewsource'         => 'Къарау',

# Login and logout pages
'yourname'                => 'Къошулуучуну аты',
'yourpassword'            => 'Парольугъуз:',
'remembermypassword'      => 'Бу компьютерде къошулуучу атымы  тут',
'login'                   => 'Кир',
'nav-login-createaccount' => 'Кириу / регистрация этиу',
'userlogin'               => 'Кир / регистрация эт',
'logout'                  => 'Чыгъыу',
'userlogout'              => 'Чыгъыу',
'nologinlink'             => 'Тергеу джазыу (аккоунт) къурагъыз',
'mailmypassword'          => 'Джангы пароль джибер E-mail бла',

# Edit page toolbar
'bold_sample'     => 'Къалын джазыу',
'bold_tip'        => 'Къалын джазыу',
'italic_sample'   => 'Курсив джазыу',
'italic_tip'      => 'Курсив джазыу',
'link_sample'     => 'Джибериуню башлыгъы',
'link_tip'        => 'Ички джибериу',
'extlink_sample'  => 'http://www.example.com линкни ачыкълауу',
'extlink_tip'     => 'Тыш джибериу (http:// префиксни унутмагъыз)',
'headline_sample' => 'Башлыкъны тексти',
'headline_tip'    => '2-чи дараджалы башлыкъ',
'math_sample'     => 'Формуланы бери салыгъыз',
'math_tip'        => 'Математика формула (LaTeX формат)',
'nowiki_sample'   => 'Формат этилинмеген текстни бери салыгъыз',
'nowiki_tip'      => 'Вики-формат этиуню игнор эт',
'image_tip'       => 'Эндирилген файл',
'media_tip'       => 'Медиа-файлгъа джибериу',
'sig_tip'         => 'Къол салыуугъуз эмда заман',
'hr_tip'          => 'Горизонтал сыз (кёб хайырландырмагъыз)',

# Edit pages
'summary'                          => 'Тюрлениулени юсюнден къысха:',
'subject'                          => 'Тема/башлыкъ:',
'minoredit'                        => 'Бу гитче тюрлениудю',
'watchthis'                        => 'Сынчыкълау списокга къош бу бетни',
'savearticle'                      => 'Бетни сакъла',
'preview'                          => 'Ал къарау',
'showpreview'                      => 'Ал къарау',
'showdiff'                         => 'Къошулгъан тюрлениуле',
'anoneditwarning'                  => "'''Эс бёлюгюз''': Сиз системагъа кирмегенсиз. Сизни IP-адресигиз бу бетни тюрлениу тарихине джазыллыкъды.",
'summary-preview'                  => 'Суратлауу былай боллукъду:',
'newarticle'                       => '(Джангы)',
'newarticletext'                   => 'Сиз джибериу бла алкъын къуралмагъан бетге кёчгенсиз.
Аны къурар ючюн тюбюндеги терезеде статьяны текстин басмалагъыз ( толуракъ [[{{MediaWiki:Helppage}}|ангылатыу бетде]] къарагъыз).
Джангылыб кирген эсегиз а уа бери, браузеригизни "артха" кнопкасын басыгъызда къоюгъуз.',
'noarticletext'                    => "Бусагъатда бу бетде текст джокъду.
Сиз [[Special:Search/{{PAGENAME}}|бу атны башха статьялада]] излерге , <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} журналлагъа къараргъа], неда '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} быллай атлы джангы бет къураргъа боллукъсуз]'''</span>.",
'previewnote'                      => "'''Бу къуру ал къарауду, текст алкъын сакъланмагъанды!'''",
'editing'                          => '$1 бетни тюрлендириу',
'editingsection'                   => '«$1» бетде бёлюмню тюзетиу',
'copyrightwarning'                 => 'Статьяны текстинде бютеу къошуула, тюрлендириуле $2 лицензияны тамалында (къарагъыз: $1) чыкъгъаннга саналгъанына эс бёлюгюз!
Сизни текстлеригизни хар излегеннге эркин джайыллыгъын, эмда тюрлендириллигин излеймей эсегиз аланы бери  салмагъыз.<br />
Дагъыда сиз, этилген тюрлениулени автору болгъаныгъызгъа, неда аланы эркин джайылыргъа эмда тюрлендирирге эркинлик берген джерледен алыннганына шагъатлыкъ этесиз .<br />
"ЭРКИНЛИКСИЗ, АВТОР ХАКЪ БЛА ДЖАКЪЛАННГАН МАТЕРИАЛЛАНЫ САЛМАГЪЫЗ"',
'templatesused'                    => 'Бу битде хайырланылгъан {{PLURAL:$1|шаблон|шаблонла}}:',
'templatesusedpreview'             => 'Ал къаралыучу бетде хайырланнган {{PLURAL:$1|шаблон|шаблонла}}:',
'template-protected'               => '(джакъланнган)',
'template-semiprotected'           => '(джарты джакъланыбды)',
'hiddencategories'                 => 'Бу бет $1 {{PLURAL:$1|1 джашырылыннган категориягъа|$1 джашырылыннган категориялагъа}} киреди:',
'permissionserrorstext-withaction' => "«'''$2'''» этерге амалыгъыз джокъду. {{PLURAL:$1|Чуруму|Чурумлары}}:",

# History pages
'viewpagelogs'           => 'Бу бетни журналларына къара',
'currentrev-asof'        => 'Бусагъатдагъы версия, $1',
'revisionasof'           => '$1 версиясы',
'previousrevision'       => '← Алдагъы',
'nextrevision'           => 'Эндиги →',
'currentrevisionlink'    => 'Бусагъатдагъы версия',
'cur'                    => 'бусагъатдагъы',
'last'                   => 'алдагъы',
'histlegend'             => "Ангылатыула: '''({{int:cur}})''' — бусагъатдагъы версиядан башхалыгъы, '''({{int:last}})''' — алдагъы версиядан башхалыгъы; '''({{int:last}})''' — гитче тюрлениу",
'history-fieldset-title' => 'Тарихине къара',
'histfirst'              => 'Эм эски',
'histlast'               => 'Эм джангы',

# Revision deletion
'rev-delundel'   => 'кёгюзтюу/джашырыу',
'revdel-restore' => 'кёрюнюуню тюрлендир',

# Merge log
'revertmerge' => 'Бёлюрге',

# Diffs
'history-title'           => '$1 бетни тюрлениу тарихи',
'difference'              => '(Версияланы араларында башхалыкъ)',
'lineno'                  => 'Тизгин $1:',
'compareselectedversions' => 'Сайланнган версияланы тенглешдириу',
'editundo'                => 'ызына алыу',

# Search results
'searchresults'             => 'Излеуню эсеби',
'searchresults-title'       => 'Изле «$1»',
'searchresulttext'          => 'Проектни бетлеринде излеуню юсюндет толу информация табарча [[{{MediaWiki:Helppage}}|{{int:help}}]] бетге киригиз.',
'searchsubtitle'            => 'Изленнген «[[:$1]]» ([[Special:Prefixindex/$1|бу ат бла башланнган бетле]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|бу атха линк берген бетле]])',
'searchsubtitleinvalid'     => '«$1» сордугъуз',
'notitlematches'            => 'Бетлени атларында бирчалыкълары джокъду',
'notextmatches'             => 'Бетлени текстлеринде бирчалыкъ джокъду',
'prevn'                     => 'алдагъы {{PLURAL:$1|$1}}',
'nextn'                     => 'эндиги {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Къара ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|$2 сёз}})',
'search-redirect'           => '(Джиберилиниу $1)',
'search-section'            => '($1 бёлюм)',
'search-suggest'            => 'Муну излей болурмусуз: $1',
'search-interwiki-caption'  => 'Джууукъ проектле',
'search-interwiki-default'  => '$1 эсеблери:',
'search-interwiki-more'     => '(дагъыда)',
'search-mwsuggest-enabled'  => 'юретиуле бла',
'search-mwsuggest-disabled' => 'юретиулесиз',
'nonefound'                 => "'''Эсгериу.''' Тынгылау бла излеу бютеу атланы аламында бардырылмайды. бютеу атланы аламында излер ючюн ( къошулуучуланы сюзюулери, шаблонла эмда башхала) аллындан \"all:\" къошакъны хайырландырыгъыз, неда керекли атланы аламын хайырландырыгъыз.",
'powersearch'               => 'Кенг излеу',
'powersearch-legend'        => 'Кенг излеу',
'powersearch-ns'            => 'Атла аламда излеу:',
'powersearch-redir'         => 'Джибериулени кёргюз',
'powersearch-field'         => 'Излеу:',

# Preferences page
'preferences'   => 'Джарашдырыула',
'mypreferences' => 'Джараштырыуларым',

# Groups
'group-sysop' => 'Администраторла',

'grouppage-sysop' => '{{ns:project}}:Администраторла',

# User rights log
'rightslog' => 'Къошулуучуну хакъларыны журналы',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'бу бетни тюзетиу',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|тюрлениу|тюрлениу}}',
'recentchanges'                  => 'Джангы тюзетиуле',
'recentchanges-legend'           => 'Ахыр тюрлениулени джарашдырыулары',
'recentchanges-feed-description' => 'Викиде бу лентада тюрлниулени сынчыкъла.',
'rcnote'                         => '$4 $5 заманнга, арт {{PLURAL:$1|1|$1}} тюрлениу {{PLURAL:$2|1|$2}}  кюнню ичинде',
'rclistfrom'                     => '$1 башлаб джангы тюрлениулени кёгюз',
'rcshowhideminor'                => 'гитче тюзетиулени $1',
'rcshowhidebots'                 => 'ботланы $1',
'rcshowhideliu'                  => 'кирген къошулуучуланы $1',
'rcshowhideanons'                => 'анонимлени $1',
'rcshowhidemine'                 => 'кесими тюзетиулерим $1',
'rclinks'                        => 'Ахыр $2 кюнню ичинде этилиннген $1 тюрлениуню кёгюз;<br /> $3',
'diff'                           => 'башх.',
'hist'                           => 'тарих',
'hide'                           => 'джашыр',
'show'                           => 'кёгюз',
'minoreditletter'                => 'г',
'newpageletter'                  => 'Дж',
'boteditletter'                  => 'б',
'rc-enhanced-expand'             => 'Къошакъланы кёгюз (JavaScript хайырланады)',
'rc-enhanced-hide'               => 'Къошакъланы джашыр',

# Recent changes linked
'recentchangeslinked'         => 'Байламлы тюзетиуле',
'recentchangeslinked-title'   => '$1 бет бла байламлы тюзетиуле',
'recentchangeslinked-summary' => "Белгиленнген бет (неда белгиленнген категориягъа киргенле) джиберген бетледе тюрлениулени списогуду.
[[Special:Watchlist|Сынчыкълау список]]га кирген бетле ''чертилгендиле'''.",
'recentchangeslinked-page'    => 'Бетни аты:',
'recentchangeslinked-to'      => 'Муну орнуна, бу бетге джиберген бетледе тюрлениулени кёргюз',

# Upload
'upload'        => 'Файлны джюклеу',
'uploadlogpage' => 'Джюклеулени журналы',
'uploadedimage' => '«[[$1]]» джюкленнгенди',

# File description page
'filehist'                  => 'Файлны тарихи',
'filehist-help'             => 'Датагъа/заманнга басыгъыз, ол сагъатда файл къаллай болгъанын кёрюр ючюн.',
'filehist-current'          => 'бусагъатдагъы',
'filehist-datetime'         => 'Дата/заман',
'filehist-thumb'            => 'Миниатюра',
'filehist-thumbtext'        => '$1 кюнден версияны миниатюрасы',
'filehist-user'             => 'Къошулуучу',
'filehist-dimensions'       => 'Ёлчеми',
'filehist-comment'          => 'Эсгериу',
'imagelinks'                => 'Файллагъа джибериуле',
'linkstoimage'              => 'Бу файлгъа {{PLURAL:$1|бет|$1 бет}} джибередиле:',
'sharedupload'              => "Бу файл $1'данды эм башха проектледе хайырландыргъа боллукъду.",
'uploadnewversion-linktext' => 'Бу файлны джангы версиясын джюклеу',

# Statistics
'statistics' => 'Статистика',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|байт}}',
'nmembers'      => '$1 {{PLURAL:$1|объект}}',
'prefixindex'   => 'Бетлени атлары башланнганына кёре кёргюзюу',
'newpages'      => 'Джангы бетле',
'move'          => 'Атын тюрлендириу',
'movethispage'  => 'Бу бетни атын тюрлендир',
'pager-newer-n' => '{{PLURAL:$1|1 джангыракъ|$1 джангыракъ}}',
'pager-older-n' => '{{PLURAL:$1|1 эскирек|$1 эскирек}}',

# Book sources
'booksources'               => 'Китабланы чыкъгъан джерлери',
'booksources-search-legend' => 'Китабны юсюнден информация излеу',
'booksources-go'            => 'Таб',

# Special:Log
'log' => 'Журналла',

# Special:AllPages
'allpages'       => 'Бютёу бетле',
'alphaindexline' => '$1 бетден $2 бетге дери',
'prevpage'       => 'Алдагъы бет ($1)',
'allpagesfrom'   => 'Мунга башланнган бетлени чыгъар:',
'allpagesto'     => 'Мында чыгъарыуну тохтат:',
'allarticles'    => 'Бютёу бары бетле',
'allpagessubmit' => 'Кёгюз',

# Special:LinkSearch
'linksearch' => 'Тыш джибериуле',

# Special:Log/newusers
'newuserlogpage'          => 'Къошулуучуланы регистрацияларыны журналы',
'newuserlog-create-entry' => 'Джангы къошулуучу',

# Special:ListGroupRights
'listgrouprights-members' => '(группаны списогу)',

# E-mail user
'emailuser' => 'Къошулуучугъа письмо',

# Watchlist
'watchlist'         => 'Сынчыкълау списогум',
'mywatchlist'       => 'Сынчыкълау списогум',
'watchlistfor'      => "('''$1''' ючюн)",
'addedwatch'        => 'Сынчыкълау бетге къош',
'addedwatchtext'    => '«[[:$1]]» бет [[Special:Watchlist|сынчыкълау бетигизге]] къошулду.
Бу бетни эмда муну бла байламлы сюзюу бетни тюрлениулери ол списокда белгиленникдиле, [[Special:RecentChanges|джангы тюрлениулени списогуну]] бетинде уа къалын шрифт бла чертилинникдиле, кёрюрге тынчыракъ болурча.',
'removedwatch'      => 'Сынчыкълау бетден кетерилди',
'removedwatchtext'  => '«[[:$1]]» бет сизни [[Special:Watchlist|сынчыкълау списогугъуздан]] кетерилгенди.',
'watch'             => 'Сынчыкъла',
'watchthispage'     => 'Бу бетни сынчыкъла',
'unwatch'           => 'Сынчыкълама',
'watchlist-details' => 'Сынчыкълау списогугъузда, сюзюу бетлени санамай, {{PLURAL:$1|$1 бет|$1 бет}}  барды.',
'wlshowlast'        => 'Арт $1 сагъат $2 кюннге $3 кёгюз',
'watchlist-options' => 'Сынчыкълау списокну джарашдырыулары',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Сынчыкълау списокга къошуу...',
'unwatching' => 'Сынчыкълау списокдан къоратыу...',

# Delete
'deletepage'            => 'Бетни къурут',
'confirmdeletetext'     => 'Сиз бетни (неда суратны) бютёу тюрлениу тарихи бла толу къурутууун соргъансыз.
Алай этерге керти излегенигизни эм  [[{{MediaWiki:Policy-url}}къурутуу политика]] бёлюмде ачыкъланнган джорукъла бла этгенигизни, бегитигиз.',
'actioncomplete'        => 'Этим толтурулду',
'deletedtext'           => '«<nowiki>$1</nowiki>» къурутулуннган бетди.
Ахыр къурутууланы списогун кёрюр ючюн $2 къарагъыз.',
'deletedarticle'        => '«[[$1]]» къурутулунду',
'dellogpage'            => 'Кетерилгенлени журналы',
'deletecomment'         => 'Къурутулууну чуруму:',
'deleteotherreason'     => 'башха чурум / дагъыда:',
'deletereasonotherlist' => 'Башха чурум',

# Rollback
'rollbacklink' => 'ызына къайтарыу',

# Protect
'protectlogpage'              => 'Джакъланыуну журналы',
'protectedarticle'            => '«[[$1]]» джакъланыбды',
'modifiedarticleprotection'   => '"[[$1]]" бетни джакъланыу дараджасы тюрленилгенди',
'protectcomment'              => 'Чурум:',
'protectexpiry'               => 'Бошалады:',
'protect_expiry_invalid'      => 'Джакъланыуну бошалгъан заманы терсди.',
'protect_expiry_old'          => 'Джакъланыу бошалгъанны заманы озгъанда.',
'protect-text'                => "Былайда сиз '''<nowiki>$1</nowiki>''' бетни джакълау дараджасына къараб тюрлендирирге боллукъсуз.",
'protect-locked-access'       => "Сизге хакъ джетмейди бетни джакълау дараджасын тюрлендирирге. '''$1''' бетни бусагъатдагъы джакълау джарашдырыулары:",
'protect-cascadeon'           => 'Бу бет {{PLURAL:$1|тюбюнде белгиленнген бетге|тюбюнде белгиленнген бетлеге}} къошулгъаны ючюн джакъланыбды. Ол {{PLURAL:$1|бетде|бетледе}} каскадлы джакълау салыныб турады. Сиз бу бетни джакълау дараджасын тюрлендирирге боллукъсуз, алай а ол каскад джакълауну тюрледирлик тюлдю.',
'protect-default'             => 'Джакълаусуз',
'protect-fallback'            => '«$1» эркинлик керекди',
'protect-level-autoconfirmed' => 'Джангы эм регистрация этмеген къошулуучуладан джакъла',
'protect-level-sysop'         => 'Къуру администраторла',
'protect-summary-cascade'     => 'каскадлы',
'protect-expiring'            => 'бошалады $1 (UTC)',
'protect-cascade'             => 'Бу бетге кирген бетлени джакъла (каскадлы джакълау)',
'protect-cantedit'            => 'Сиз бу бетни джакълау дараджасын тюрлендиреллик тюйюлсюз, бу бетни тюрлендирирге хакъыгъыз болмагъаны ючюн.',
'restriction-type'            => 'Хакълары:',
'restriction-level'           => 'Ийилген дараджасы:',

# Undelete
'undeletelink'     => 'къара/ызына сал',
'undeletedarticle' => '«[[$1]]» орнуна келтирилди',

# Namespace form on various pages
'namespace'      => 'Атла алам:',
'invert'         => 'Сайланнганны айландыр',
'blanknamespace' => '(Тамал)',

# Contributions
'contributions'       => 'Къошулуучуну къошханы',
'contributions-title' => '$1 къошулуучуну къошагъы',
'mycontris'           => 'Мени къошханым',
'contribsub2'         => '$1 ($2) къошакъ',
'uctop'               => '(ахыргъы)',
'month'               => 'Айдан башлаб (эм алгъаракъ):',
'year'                => 'Джылдан башлаб (эм алгъаракъ):',

'sp-contributions-newbies'  => 'Джангы тергеу джазыу (аккоунт) бла этилге къошакъны кёргюз',
'sp-contributions-blocklog' => 'Блок этиуню журналы',
'sp-contributions-search'   => 'Къошакъны излеу',
'sp-contributions-username' => 'IP-адреси неда аты къошулуучуну:',
'sp-contributions-submit'   => 'Таб',

# What links here
'whatlinkshere'            => 'Линкле былайгъа',
'whatlinkshere-title'      => '«$1» бетге джиберген бетле',
'whatlinkshere-page'       => 'Бет:',
'linkshere'                => "'''[[:$1]]''' битге джиберген бетле:",
'isredirect'               => 'джибериу бет',
'istemplate'               => 'къошуу',
'isimage'                  => 'суратха джибериу',
'whatlinkshere-prev'       => '{{PLURAL:$1|алдагъы|алдагъы $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|эндиги|эндиги$1}}',
'whatlinkshere-links'      => '← джибериуле',
'whatlinkshere-hideredirs' => 'джибериуле $1',
'whatlinkshere-hidetrans'  => 'Къошулуулары $1',
'whatlinkshere-hidelinks'  => '$1 джибериуле',
'whatlinkshere-filters'    => 'Фильтрле',

# Block/unblock
'blockip'                  => 'Бу къошулуучуну блок эт',
'ipboptions'               => '2 сагъат:2 hours,1 кюн:1 day,3 кюн:3 days,1 ыйыкъ:1 week,2 ыйыкъ:2 weeks,1 ай:1 month,3 ай:3 months,6 ай:6 months,1 джыл:1 year,чекленмеген:infinite',
'ipblocklist'              => 'Блок этилиннген IP-адресле эм къошулуучуланы атлары',
'blocklink'                => 'блок эт',
'unblocklink'              => 'блокну ал',
'change-blocklink'         => 'блок этиуню тюрлендир',
'contribslink'             => 'къошакъ',
'blocklogpage'             => 'Блок этиуню журналы',
'blocklogentry'            => '[[$1]] блок этилингенди $2 заманнга $3',
'unblocklogentry'          => '$1 къошулуучудан блок алынды',
'block-log-flags-nocreate' => 'Тергеу джазыуланы (аккоунтланы) регистрациялары тыйылыбды',

# Move page
'movepagetext'     => "Тюбюндеги форма бла хайырланыб, сиз бетни атын тюрлендирликсиз, аны бла бирге аны тюрлениулерини журналын джангы оруннга кёчюрлюксюз.
Эски аты джангы атына джибериу боллукъду.
Эски атына баргъан джибериулени автоматик халда джангыртыргъа боллукъсуз.
Алай этмей эсегиз [[Special:DoubleRedirects|экили]] бла [[Special:BrokenRedirects|юзюлген джибериуле]] бармыдыла деб къарагъыз.
Джибериулени мындан арыда керекли джерни кёргюзюулерине сиз джууаблысыз.
Бет джангыдан джибериу болгъанны, неда тюзетиулени тарихи болмай бош болгъанны тышында, джангы аты бла бет бар эсе, бетни атын тюрлдендиреллик '''тюлсюз''' .
Аны магъанасы, сиз бетни атын , мындан алгъа болгъан атына къайтарыргъа боллукъсуз, халат бла атын тюрлендирген эсегиз, болгъан бет билмей кетерилиб къаллыкъ тюлдю.

'''ЭСГЕРТИУ!'''
Атын тюрлендириу, \"айырма бетлени\" уллу ёлчемде , эмда сакъланмагъан тюрлениулеге келтирирге боллукъду.
Тилейбиз, мындан ары бардырлыгъыгъызны аллы бла, ахырында чыгъарыкъ эсеблени ангылагъаныгъызгъа ишексиз болугъуз.",
'movepagetalktext' => "Къошулгъан сюзюу бет да автоматик кёчюрюлюннюкдю, '''быллай ситауция болмаса''':

*Бош болмагъан сюзюу бет бу аты бла барды;
*Тюбюндеги тизгинде белги салмагъансыз.

Аллай ситуация болса, сиз бетлени къол бла кёчюрюрге неда къошаргъа керек боллукъсуз.",
'movearticle'      => 'Бетни атын тюрлендир:',
'newtitle'         => 'Джангы ат:',
'move-watch'       => 'Сынчыкълау списокга къош',
'movepagebtn'      => 'Бетни атын тюрлендир',
'pagemovedsub'     => 'Бетни аты тюрленди',
'movepage-moved'   => "<big>'''«$1» бет «$2» бетге кёчдю'''</big>",
'articleexists'    => 'Быллай аты бла бет барды неда сиз джазгъан ат джарамайды.
Башха ат сайлагъыз.',
'talkexists'       => "'''Бетни аты тюрленнгенди, алай а сюзюу бетни кёчюрюрге джарамайды, аллай аты бла бет болгъаны ючюн. Къол бла къошугъуз аланы бири-бирлерине.'''",
'movedto'          => 'аты тюрленнгенди:',
'movetalk'         => 'Байламлы сюзюу бетни атын тюрлендир',
'1movedto2'        => '«[[$1]]» бет «[[$2]]» бетге тюрленилинди',
'1movedto2_redir'  => '«[[$1]]» бет «[[$2]]» бетге тюрленди джибериуню юсю бла',
'movelogpage'      => 'Атла тюрлениуню журналы',
'movereason'       => 'Чурум:',
'revertmove'       => 'ызына къайтыу',

# Export
'export' => 'Статьяланы экспорт эт',

# Thumbnails
'thumbnail-more' => 'Уллу эт',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Къошулуучу бетигиз',
'tooltip-pt-mytalk'               => 'Сизни сюзюу бетигиз',
'tooltip-pt-preferences'          => 'Джараштырыуларыгъыз',
'tooltip-pt-watchlist'            => 'Мен сынчыкълагъан бетлени списогу',
'tooltip-pt-mycontris'            => 'Сизни тюзетиулеригизни списогу',
'tooltip-pt-login'                => 'Былайда регистрация этерге боллукъду, алай а ол ажымсыз керекли тюйюлдю',
'tooltip-pt-logout'               => 'Чыгъыу',
'tooltip-ca-talk'                 => 'Бетни ичиндегин сюзюу',
'tooltip-ca-edit'                 => 'Бу бетни тюзетирге болады. Сакълагъынчы ал къарауну хайырландырыгъыз.',
'tooltip-ca-addsection'           => 'Джангы бёлюм къура',
'tooltip-ca-viewsource'           => 'Бу бет тюрлендириуден джакъланыбды. Алай а сиз къараб, текстин копия этерге боллукъсуз',
'tooltip-ca-history'              => 'Бетни алгъын версиялары',
'tooltip-ca-protect'              => 'Бу бетни джакъла',
'tooltip-ca-delete'               => 'Бу бетни къурут',
'tooltip-ca-move'                 => 'Бу бетни атын тюрлендириу',
'tooltip-ca-watch'                => 'Бу бетни сынчыкълау списогугъузгъа къошугъуз',
'tooltip-ca-unwatch'              => 'Сынчыкълау списокдан кетер бу бетни',
'tooltip-search'                  => 'Бу сёзню излерге',
'tooltip-search-go'               => 'Тамам быллай аты болгъан бетге кёчерге',
'tooltip-search-fulltext'         => 'Быллай текст болгъан бетлени табаргъа',
'tooltip-n-mainpage'              => 'Баш бетге кёчюу',
'tooltip-n-mainpage-description'  => 'Баш бетге кёчюу',
'tooltip-n-portal'                => 'Проектни юсюнден, сизни не этерге боллугъугъузню юсюнден, хар не къайда болгъаныны юсюнден',
'tooltip-n-currentevents'         => 'Бусагъатда болгъан ишлени списогу',
'tooltip-n-recentchanges'         => 'Ахыр тюрленнгенлени списогу',
'tooltip-n-randompage'            => 'Къайсы болсада бир бетге къара',
'tooltip-n-help'                  => '«{{SITENAME}}» проектге справочник',
'tooltip-t-whatlinkshere'         => 'Бу бетге линк берген бютёу бетлени списогу',
'tooltip-t-recentchangeslinked'   => 'Бу бет линкле берген бетледе ахыргъы тюрлениуле',
'tooltip-feed-rss'                => 'Бу битге RSS-трансляция',
'tooltip-feed-atom'               => 'Бу бетге Atom-трансляция',
'tooltip-t-contributions'         => 'Къошулуучуну тюрлендирген бетлерине къара',
'tooltip-t-emailuser'             => 'Бу къошулуучугъа письмо джибер',
'tooltip-t-upload'                => 'Файлланы джюклеу',
'tooltip-t-specialpages'          => 'Бютеу специал бетлени списогу',
'tooltip-t-print'                 => 'Бу бетни басмагъа версиясы',
'tooltip-t-permalink'             => 'Бетни бу версиясына дайым линк',
'tooltip-ca-nstab-main'           => 'Статьяны ичиндеги',
'tooltip-ca-nstab-user'           => 'Къошулуучуну бетине къарау',
'tooltip-ca-nstab-special'        => 'Бу специал бетди, тюрлендирилмейди',
'tooltip-ca-nstab-project'        => 'Проектни бетине къара',
'tooltip-ca-nstab-image'          => 'Файлны бетине къара',
'tooltip-ca-nstab-template'       => 'Шаблоннга къара',
'tooltip-ca-nstab-category'       => 'Категорияны бетине къара',
'tooltip-minoredit'               => 'Асыры гитчеди деб ызына алыу бу тюрлендириуню',
'tooltip-save'                    => 'Тюрлендириулеринги сакъла',
'tooltip-preview'                 => 'Ал къарауну бетге хайырландырыгъыз, тюрлендириулеригизни сакъларны аллы бла!',
'tooltip-diff'                    => 'Тюрлендириулеригизни кёрюгюз',
'tooltip-compareselectedversions' => 'Бу бетни сайланнган эки версиясыны араларында башхалыкъларын кёр',
'tooltip-watch'                   => 'Сынчыкълау списокга къош бу бетни',
'tooltip-rollback'                => 'Бир басхан бла ахыр къошулуучуну тюрлендиргенин кетер',
'tooltip-undo'                    => 'Этилиннген тюзетиуню ызына ал эмда ал къарауну кёгюз, ызына нек алыннгын чертирча',

# Browsing diffs
'previousdiff' => '← Алдагъы тюзетиу',
'nextdiff'     => 'Эндиги тюзетиу →',

# Media information
'file-info-size'       => '($1 × $2 пиксель, файлны ёлчеми: $3, MIME тибы: $4)',
'file-nohires'         => '<small>Мындан ары ачыкъланнган версиясы джокъду</small>',
'svg-long-desc'        => '(SVG файл, шартлы $1 × $2 пиксель, файлны ёлчеми: $3)',
'show-big-image'       => 'Толу ачыкълау',
'show-big-image-thumb' => '<small>Ал къарауда ёлчеми: $1 × $2 пиксель</small>',

# Bad image list
'bad_image_list' => 'Формат быллай болургъа керекди:

Къуру списокну кесеклери (* символдан башланнганла) саналлыкъдыла.
Тизгинни биринчи линки салыргъа болмагъан (аман) суратха линк болургъа керекди.
Андан ары баргъан линкле ол тизгинде, сурат къошулургъа болгъан статьялагъа линклеге саналлыкъдыла.',

# Metadata
'metadata'          => 'Метаданныйле',
'metadata-help'     => 'Файл, кёбюсюне цифралы камерала бла, неда сканерле бла къошулгъан, къошакъ билгилени тутаргъа болады. Файл къуралгъанындан сора тюрлендирилген эсе, бир-бир параметрлери бусагъатдагъы суратына келишмезге болур.',
'metadata-expand'   => 'Дагъыда билгиле кёгюз',
'metadata-collapse' => 'Къошакъ билгилени джашыр',
'metadata-fields'   => 'Бу списокда келтирилинген метаданныйлени тизгинлери, суратны бетинде тынгылау бла кёргюзтюлюнлюкдюле, къалгъанла джашырылыныб боллукъдула.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Бу файлны тыш программа бла тюзетигиз',
'edit-externally-help' => '(толу информациягъа мында къарагъыз: [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'бютёу',
'namespacesall' => 'бютёу',
'monthsall'     => 'бютёу',

# Watchlist editing tools
'watchlisttools-view' => 'Списокдан бетледе тюрлениуле',
'watchlisttools-edit' => 'Къара/тюзет списокну',
'watchlisttools-raw'  => 'Текстча тюзетиу',

# Special:SpecialPages
'specialpages' => 'Специал бет',

);
