<?php
/** Eastern Mari (Олык Марий)
 *
 * @ingroup Language
 * @file
 *
 * @author Jose77
 * @author Сай
 */

$fallback = 'ru';

$messages = array(
# User preference toggles
'tog-underline'            => 'Кузе ссылкe-влакым ӱлычын удыралаш?',
'tog-highlightbroken'      => 'Лийдыме ссылке-влакым <a href="" class="new">тидын гай</a> ышташ (уке гын, тиде семын<a href="" class="internal">?</a>).',
'tog-justify'              => 'Абзацым лопкыт дене тӧрлаш',
'tog-extendwatchlist'      => 'Чыла вашталтышым ончыкташлан эскерыме спискым кугемдаш',
'tog-numberheadings'       => 'Вуймутым автоматик йӧн дене радамлаш',
'tog-showtoc'              => 'Вуймут радамым ончыкташ (3 деч шуко вуймутан лаштык-влаклан)',
'tog-rememberpassword'     => 'Тиде компьютерыште мыйын шолыпмутым шарнаш',
'tog-watchcreations'       => 'Мыйын ыштыме лаштык-влакым эскерыме спискыш ешараш',
'tog-watchdefault'         => 'Мыйын тӧрлатыме лаштык-влакым эскерыме спискыш ешараш',
'tog-watchmoves'           => 'Мыйын лӱмым вашталтыме лаштык-влакым эскерыме спискыш ешараш',
'tog-watchdeletion'        => 'Мыйын шӧрымӧ лаштык-влакым эскерыме спискыш ешараш',
'tog-nocache'              => 'Лаштыкым кешироватлымым чараш',
'tog-enotifwatchlistpages' => 'Мыйын эскерыме списке гыч лаштыкыште тӧрлатымыш нерген электрон почто гоч шижтараш',
'tog-enotifusertalkpages'  => 'Мыйын каҥашымаш лаштыкыште тӧрлатымыш нерген электрон почто гоч шижтараш',
'tog-showjumplinks'        => '"Куснаш …" ешартыш ссылкым чӱкташ',
'tog-watchlisthideown'     => 'Эскерыме списке гыч мыйын тӧрлатымаш-влакым ончыкташ огыл',
'tog-watchlisthidebots'    => 'Эскерыме списке гыч бот-влакым тӧрлатымым ончыкташ огыл',
'tog-watchlisthideminor'   => 'Эскерыме списке гыч изирак тӧрлатымаш-влакым ончыкташ огыл',
'tog-ccmeonemails'         => 'Моло ушнышо-влаклан колтымо серышын копийжым мыламат колташ',
'tog-diffonly'             => 'Кок версийым таҥастарыме годым лаштыкын содержанийжым ончыкташ огыл',
'tog-showhiddencats'       => 'Шылтыме категорийым ончыкташ',

'underline-always'  => 'Кеч-кунам',
'underline-never'   => 'Нигунам',
'underline-default' => 'Браузерысе семын палемдыде',

# Dates
'sunday'        => 'Рушарня',
'monday'        => 'Шочмо',
'tuesday'       => 'Кушкыжмо',
'wednesday'     => 'Вӱргече',
'thursday'      => 'Изарня',
'friday'        => 'Кугарня',
'saturday'      => 'Шуматкече',
'sun'           => 'Рш',
'mon'           => 'Шч',
'tue'           => 'Кш',
'wed'           => 'Вр',
'thu'           => 'Из',
'fri'           => 'Кг',
'sat'           => 'Шм',
'january'       => 'Шорыкйол',
'february'      => 'Пургыж',
'march'         => 'Ӱарня',
'april'         => 'Вӱдшор',
'may_long'      => 'Ага',
'june'          => 'Пеледыш',
'july'          => 'Сӱрем',
'august'        => 'Сорла',
'september'     => 'Идым',
'october'       => 'Шыжа',
'november'      => 'Кылме',
'december'      => 'Теле',
'january-gen'   => 'Шорыкйол',
'february-gen'  => 'Пургыж',
'march-gen'     => 'Ӱарня',
'april-gen'     => 'Вӱдшор',
'may-gen'       => 'Ага',
'june-gen'      => 'Пеледыш',
'july-gen'      => 'Сӱрем',
'august-gen'    => 'Сорла',
'september-gen' => 'Идым',
'october-gen'   => 'Шыжа',
'november-gen'  => 'Кылме',
'december-gen'  => 'Теле',
'jan'           => 'Шорыкйол',
'feb'           => 'Пургыж',
'mar'           => 'Ӱарня',
'apr'           => 'Вӱдшор',
'may'           => 'Ага',
'jun'           => 'Пеледыш',
'jul'           => 'Сӱрем',
'aug'           => 'Сорла',
'sep'           => 'Идым',
'oct'           => 'Шыжа',
'nov'           => 'Кылме',
'dec'           => 'Теле',

# Categories related messages
'pagecategories'  => '{{PLURAL:$1|Категорий|Категорий}}',
'category_header' => '"$1" категорийыште лаштык-влак',

'about'          => 'Нерген',
'newwindow'      => '(у окнаште почылтеш)',
'cancel'         => 'Чараш',
'qbfind'         => 'Муаш',
'qbedit'         => 'Тӧрлаташ',
'qbpageoptions'  => 'Тиде лаштык',
'qbmyoptions'    => 'Мыйын лаштык-влак',
'qbspecialpages' => 'Лӱмын ыштыме лаштык-влак',
'mypage'         => 'Мыйын лаштык',
'mytalk'         => 'Мыйын каҥашымаш',
'anontalk'       => 'Каҥашымаш тиде IP нерген',
'navigation'     => 'Навигаций',

'tagline'          => '{{SITENAME}} гыч',
'help'             => 'Полшык',
'search'           => 'Кычалмаш',
'searchbutton'     => 'Кычалаш',
'searcharticle'    => 'Куснаш',
'history'          => 'Лаштыкын историй',
'history_short'    => 'Историй',
'printableversion' => 'Савыкташлан келыштарыме',
'permalink'        => 'Эре улшо ссылке',
'edit'             => 'Тӧрлаташ',
'editthispage'     => 'Тӧрлаташ тиде лаштыкым',
'delete'           => 'Шӧраш',
'protectthispage'  => 'Тиде  лаштыкым тӧрлатымаш деч аралаш',
'newpage'          => 'У лаштык',
'talkpage'         => 'Каҥашымаш тиде лаштык нерген',
'talkpagelinktext' => 'Каҥашымаш',
'personaltools'    => 'Шке ӱзгар-влак',
'talk'             => 'Каҥашымаш',
'toolbox'          => 'Ӱзгар-влак',
'viewtalkpage'     => 'Ончалаш каҥашымашым',
'jumpto'           => 'Куснаш:',
'jumptonavigation' => 'навигациеш',
'jumptosearch'     => 'кычалмашке',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} нерген',
'edithelp'             => 'Тӧрлатымаште полыш',
'mainpage'             => 'Тӱҥ лаштык',
'mainpage-description' => 'Тӱҥ лаштык',

'editsection'     => 'тӧрлаташ',
'editold'         => 'тӧрлаташ',
'editsectionhint' => 'Тӧрлаташ ужашым: $1',
'toc'             => 'Вуйлымаш',
'showtoc'         => 'ончыктаж',
'hidetoc'         => 'шылташ',
'viewdeleted'     => 'Ончалаш $1?',
'site-rss-feed'   => '$1 RSS-тасма',
'site-atom-feed'  => '$1 Atom-тасма',
'page-rss-feed'   => '"$1" RSS-тасма',
'page-atom-feed'  => '"$1" Atom-тасма',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Лаштык',
'nstab-user'     => 'Пайдаланышын лаштыкше',
'nstab-image'    => 'Файл',
'nstab-category' => 'Категорий',

# General errors
'viewsource'    => 'Тӱҥалтыш текст',
'viewsourcefor' => '$1 лан',

# Login and logout pages
'yourname'                => 'Пайдаланышын лӱмжӧ:',
'yourpassword'            => 'Шолыпмут:',
'remembermypassword'      => 'Тиде компьютерыште мыйын шолыпмутым шарнаш',
'login'                   => 'Шке денет палымым ыште',
'nav-login-createaccount' => 'Шке денет палымым ыште/Регистрацийым эрте',
'userlogin'               => 'Шке денет палымым ыште/Регистрацийым эрте',
'logout'                  => 'Лекташ',
'userlogout'              => 'Лекташ',
'nologin'                 => 'Тый регистрацийым эше эртен отыл? $1.',
'nologinlink'             => 'Регистрацийым эрте',
'gotaccount'              => 'Тый регистрацийым эртенат? $1.',
'gotaccountlink'          => 'Шке денет палымым ыште',
'youremail'               => 'Электрон почто:',
'username'                => 'Пайдаланышын лӱмжӧ:',
'uid'                     => 'Пайдаланышын ID-же:',
'prefs-memberingroups'    => '{{PLURAL:$1|Тӱшкаште шогышо|Тӱшка-влакыште шогышо}}:',
'yourrealname'            => 'Чын лӱмжӧ:',
'yourlanguage'            => 'Йылме:',
'yournick'                => 'Кидпале:',
'email'                   => 'Электрон почто',
'prefs-help-email'        => 'Электрон почтын адресшым ончыктыде кертат, адакшым тудо моло ушнышо-влаклан тыйын лаштык гоч тый денет кылым кучаш йӧным ышта, тыгодымак нунылан палыдыме кодеш.',
'wrongpassword'           => 'Тый йоҥылыш шолыпмутым пуртенат.
Эше ик гана ыштен ончо.',
'wrongpasswordempty'      => 'Тый яра шолыпмутым пуртенат.
Эше ик гана ыштен ончо.',
'passwordtooshort'        => 'Тыйын шолыпмутет келшен огеш тол але пеш кӱчык.
Тудо {{PLURAL:$1|1 символ|$1 символ}} деч шагал огыл лийшаш да тыйын пайдаланыше лӱмет ден икгай лийшаш огыл.',
'noemail'                 => '"$1" пайдаланыше электрон адресым палемден огыл.',
'emailauthenticated'      => 'Тыйын почто адресетым пеҥгыдемдыме $1.',
'loginlanguagelabel'      => 'Йылме: $1',

# Edit page toolbar
'bold_sample'     => 'Кӱжгӧ текст',
'bold_tip'        => 'Кӱжгӧ текст',
'italic_sample'   => 'Шӧрын текст',
'italic_tip'      => 'Шӧрын текст',
'link_sample'     => 'Ссылкын лӱмжӧ',
'link_tip'        => 'Кӧргысӧ ссылке',
'extlink_sample'  => 'http://www.mari-el.name ссылкын лӱмжӧ',
'extlink_tip'     => 'Ӧрдыж ссылке (http:// префиксым ит мондо)',
'headline_sample' => 'Вуймут',
'headline_tip'    => '2-шо кӱкшытан вуймут',
'math_sample'     => 'Формулым тышке шынде',
'math_tip'        => 'Математик формул (LaTeX)',
'nowiki_sample'   => 'Форматироватлыдыме текстым тышке шынде',
'nowiki_tip'      => 'Вики-форматированийым шотыш налаш огыл',
'image_tip'       => 'Пуртымо сӱрет',
'media_tip'       => 'Пуртымо медиа-файл',
'sig_tip'         => 'Тыйын кидпалет да шындеме жап ден кече',
'hr_tip'          => 'Тореш (шуэн кучылт)',

# Edit pages
'summary'            => 'Тӧрлатымаш нерген',
'minoredit'          => 'Тиде изирак тӧрлатыме',
'watchthis'          => 'Тиде лаштыкым эскераш',
'savearticle'        => 'Лаштыкым аралаш',
'preview'            => 'Ончылгоч ончымаш',
'showpreview'        => 'Ончылгоч ончымаш',
'showdiff'           => 'Тӧрлатымашым ончыкташ',
'clearyourcache'     => "'''Ешартыш''': Аралыме деч вара вашталтышым ужаш браузеретын кешыжым эрыкташ логалын кертеш. '''Mozilla / Firefox / Safari:''' ''Shift''-ым темдал кучен ''Reload''-ым темдал але ''Ctrl-F5'' але ''Ctrl-R'' темдал (Macintosh-влак ''Command-R''); '''Konqueror:''' темдал ''Reload'' кнопкым але ''F5'' темдал; '''Opera:''' ''Tools→Preferences''-ыште кешым эрыкте; '''Internet Explorer:''' ''Ctrl''-ым темдал кучен ''Refresh''-ым темдал але ''Ctrl-F5'' темдал.",
'previewnote'        => '<strong>Тиде ончылгоч ончымаш гына;
вашталтыш-влакым эше аралыме огыл!</strong>',
'editing'            => 'Тӧрлаталтеш $1',
'template-protected' => '(тӧрлаташ чарыме)',

# History pages
'currentrev'          => 'Кызытсе версий',
'revisionasof'        => '$1-лан версий',
'currentrevisionlink' => 'Кызытсе версий',
'cur'                 => 'кызыт',
'histfirst'           => 'Эн тошто',
'histlast'            => 'Эн у',

# Revision deletion
'pagehist' => 'Лаштыкын историй',

# Diffs
'difference'              => '(Версий-влакын ойыртемышт)',
'compareselectedversions' => 'Ойырымо версий-влакым таҥастараш',
'editundo'                => 'чараш',

# Search results
'searchresults'            => 'Кычалын мумо',
'prevn'                    => 'кодшо $1',
'nextn'                    => 'весе $1',
'viewprevnext'             => 'Ончал ($1) ($2) ($3)',
'search-interwiki-default' => "$1'ште мумо:",
'powersearch'              => 'Сайынрак кычал',
'powersearch-legend'       => 'Сайынрак кычалаш',
'powersearch-field'        => 'Кычалаш',

# Preferences page
'preferences'           => 'Настройке-влак',
'mypreferences'         => 'Настройке-влак',
'prefs-edits'           => 'Мыняр тӧрлатымашым ыштен?:',
'changepassword'        => 'Шолыпмутым вашталташ',
'skin'                  => 'Сӧрастарыме йӧн',
'skin-preview'          => 'Ончылгоч ончымаш',
'math'                  => 'Формуло-влак',
'prefs-personal'        => 'Пайдаланышын профильже',
'prefs-rc'              => 'Шукертсе огыл тӧрлымаш-влак',
'prefs-watchlist'       => 'Эскерымаш списке',
'prefs-watchlist-days'  => 'Мыняр кече эскерымаш спискыште ончыкталтеш?',
'prefs-watchlist-edits' => 'Мыняр тӧрлатымашым ышташ лиймым кугемдыме эскерымаш спискыште ончыктымо?',
'prefs-misc'            => 'Тӱрлӧ',
'saveprefs'             => 'Аралаш',
'resetprefs'            => 'Тӧрлатымым шотыш налаш огыл',
'oldpassword'           => 'Тошто шолыпмут:',
'newpassword'           => 'У шолыпмут:',
'retypenew'             => 'Пеҥгыдемдыза у шолыпмутым:',
'searchresultshead'     => 'Кычалме',
'savedprefs'            => 'Тыйын настройке-влакетым аралыме.',
'allowemail'            => 'Вес ушнышо-влак деч электрон почтым налаш кӧнаш',
'default'               => 'ойлыде',

# Groups
'group-bot' => 'Бот-влак',

'group-bot-member' => 'бот',

'grouppage-bot' => '{{ns:project}}:Бот-влак',

# Recent changes
'recentchanges'   => 'Шукертсе огыл тӧрлымаш-влак',
'rcnote'          => "Ӱлнӧ {{PLURAL:$1|'''1'''|'''$1'''}} вашталтыш пытартыш {{PLURAL:$2||'''$2'''}} кечылан, $5-лан, $4-лан.",
'rclistfrom'      => '$1 гыч тӱҥалын у вашталтымашым ончыкташ',
'rcshowhideminor' => 'Изирак тӧрлымым $1',
'rcshowhidebots'  => 'Бот-влакым $1',
'rcshowhideliu'   => 'Шолып пайдаланыше-влакым $1',
'rcshowhideanons' => 'Ончыкталтше пайдаланыше-влакым $1',
'rcshowhidemine'  => 'Мыйын тӧрлымым $1',
'rclinks'         => 'Пытартыш $2 кечылан $1 вашталтымашым ончыкташ<br />$3',
'diff'            => 'ойырт.',
'hist'            => 'ист.',
'hide'            => 'шылташ',
'show'            => 'ончыкташ',
'minoreditletter' => 'и',
'newpageletter'   => 'У',
'boteditletter'   => 'б',

# Recent changes linked
'recentchangeslinked'          => 'Ваш кылдалтше тӧрлатымаш-влак',
'recentchangeslinked-title'    => '"$1" лаштыклан кылдалтше тӧрлатымаш-влак',
'recentchangeslinked-noresult' => 'Ончыктымо пагытыште кылдалтше лаштыклаште вашталтыш лийын огыл.',
'recentchangeslinked-page'     => 'Лаштыкын лӱмжӧ:',

# Upload
'upload'            => 'Файлым пурташ',
'uploadbtn'         => 'Файлым пурташ',
'filedesc'          => 'Файл нерген кӱчыкын увертараш',
'fileuploadsummary' => 'Тидын нерген кӱчыкын:',
'watchthisupload'   => 'Тиде лаштыкым эскераш',

# Special:ImageList
'imgfile'        => 'файл',
'imagelist_user' => 'Пайдаланыше',

# Image description page
'filehist-deleteone' => 'шӧраш',
'filehist-current'   => 'кызыт',
'filehist-user'      => 'Пайдаланыше',
'filehist-filesize'  => 'Файлын кугытшо',
'imagelinks'         => 'Ссылке-влак',
'nolinkstoimage'     => 'Тиде файл дене кылдалтше ик лаштыкат уке.',

# File deletion
'filedelete-submit' => 'Шӧраш',

# Random page
'randompage' => 'Вучыдымо (случайный) статья',

'brokenredirects-delete' => '(шӧраш)',

'withoutinterwiki-submit' => 'ончыкташ',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|байт|байт}}',
'nviews'            => '$1 {{PLURAL:$1|ончымо|ончымо-влак}}',
'newpages'          => 'У лаштык-влак',
'newpages-username' => 'Пайдаланышын лӱмжӧ:',
'move'              => 'Кусараш',
'movethispage'      => 'Тиде лаштыкым кусараш',

# Special:Log
'specialloguserlabel' => 'Пайдаланыше:',

# Special:AllPages
'allpages'       => 'Чыла лаштык-влак',
'alphaindexline' => '$1 $2 марте',
'prevpage'       => 'Ончычсо лаштык ($1)',
'allpagesfrom'   => 'Лукташ тыгай лӱман лаштык-влакым, кудыжо тӱҥалыт:',
'allarticles'    => 'Чыла лаштык-влак',
'allpagessubmit' => 'Кайе',

# Special:LinkSearch
'linksearch-ok' => 'Кучал',

# Special:ListUsers
'listusers-submit' => 'ончыкташ',

# E-mail user
'emailuser' => 'Пайдаланыше дек серыш',

# Watchlist
'watchlist'            => 'Мыйын эскерымаш списке',
'mywatchlist'          => 'Мыйын эскерымаш списке',
'watchlistfor'         => "('''$1''' лан)",
'addedwatch'           => 'Эскерымаш спискыш ешарыме',
'removedwatch'         => 'Эскерымаш списке гыч шӧрымӧ',
'watch'                => 'Эскераш',
'watchthispage'        => 'Тиде лаштыкым эскераш',
'unwatch'              => 'Эскерыман огыл',
'unwatchthispage'      => 'Эскерымым чарнаш',
'watchlistcontains'    => 'Тыйын спискыште $1 {{PLURAL:$1|лаштык|лаштык}}.',
'wlshowlast'           => 'Пытартыш $1 шагат $2 кечылан $3 ончыкташ',
'watchlist-hide-bots'  => 'Ботын тӧрлымым шылташ',
'watchlist-hide-own'   => 'Мыйын тӧрлымым шылташ',
'watchlist-hide-minor' => 'Изирак тӧрлымым шылташ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Эскерымаш спискыш ешарымаш...',
'unwatching' => 'Эскерымаш списке гыч шӧрымаш...',

# Delete
'deletepage'     => 'Лаштыкым шӧраш',
'delete-confirm' => 'Шӧраш "$1"',
'delete-legend'  => 'Шӧраш',
'dellogpage'     => 'Шӧрымӧ нерген журнал',
'deletionlog'    => 'шӧрымӧ нерген журнал',

# Protect
'protectcomment'  => 'Аралыме нерген:',
'protect-default' => '(ойлыде)',

# Undelete
'undelete-search-submit' => 'Кычал',

# Namespace form on various pages
'invert'         => 'Палемдымашым ваштареш ышташ',
'blanknamespace' => '(Тӱҥ)',

# Contributions
'mycontris'   => 'Мыйын надырем',
'contribsub2' => '$1 лан ($2)',
'month'       => 'Могай тылзе гыч тӱҥалаш? (але ондакрак):',
'year'        => 'Могай ий гыч тӱҥалаш? (але ондакрак):',

'sp-contributions-submit' => 'Кычал',

# What links here
'whatlinkshere'       => 'Ссылке-влак тышке',
'whatlinkshere-title' => '"$1" дене лаштык-влак кылым палемдат',
'whatlinkshere-page'  => 'Лаштык:',
'whatlinkshere-prev'  => '{{PLURAL:$1|ончычсо|$1 ончычсо}}',
'whatlinkshere-next'  => '{{PLURAL:$1|вес|$1 вес}}',
'whatlinkshere-links' => '← ссылке-влак',

# Block/unblock
'ipblocklist-submit' => 'Кычал',
'contribslink'       => 'надыр',

# Move page
'move-page-legend' => 'Лаштыкым кусараш',
'movearticle'      => 'Тиде лаштыкым кусараш:',
'move-watch'       => 'Тиде лаштыкым эскераш',
'movepagebtn'      => 'Лаштыкым кусараш',
'movepage-moved'   => '<big>\'\'\'"$1" лаштыкым "$2" лаштыкыш кусарыме\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movedto'          => 'лаштыкыш кусарыме',
'1movedto2'        => '[[$1]] лаштыкым [[$2]] лаштыкыш кусарыме',
'movelogpage'      => 'Кусарыме нерген журнал',

# Thumbnails
'thumbnail-more' => 'Кугемдаш',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Мыйын лаштыкем',
'tooltip-pt-mytalk'               => 'Мыйын каҥашымаш лаштыкем',
'tooltip-pt-preferences'          => 'Мыйын настройке-влак',
'tooltip-pt-watchlist'            => 'Мыйын эскерыме лаштык-влак списке',
'tooltip-pt-mycontris'            => 'Мыйын надыр списке',
'tooltip-pt-login'                => 'Шке денет палымым ыштет гын сайрак лиеш; такшым тидым ыштыдеат кертат.',
'tooltip-pt-logout'               => 'Системе гыч лекташ',
'tooltip-ca-talk'                 => 'Лаштыкыште возымым каҥашымаш',
'tooltip-ca-edit'                 => 'Тый тиде лаштыкым тӧрлатен кертат. Лаштыкым аралыме деч ончыч тудым тергаш ит мондо.',
'tooltip-ca-viewsource'           => 'Тиде лаштыкым аралыме.
Тый тудын тӱҥалтыш текстшым ончалын кертат.',
'tooltip-ca-history'              => 'Лаштыкын ончычсо версийже-влак.',
'tooltip-ca-protect'              => 'Тиде  лаштыкым тӧрлатымаш деч аралаш',
'tooltip-ca-delete'               => 'Шӧраш тиде лаштыкым',
'tooltip-ca-move'                 => 'Тиде лаштыкым кусараш',
'tooltip-ca-watch'                => 'Тиде лаштыкым тыйын эскерыме-влак спискыш ешараш',
'tooltip-ca-unwatch'              => 'Тиде лаштыкым тыйын эскерыме-влак списке гыч кораҥдаш',
'tooltip-search'                  => "Кычал {{SITENAME}}'ште",
'tooltip-search-go'               => 'Тиде лӱман лаштыкыш куснаш, тыгайже уло гын',
'tooltip-search-fulltext'         => 'Тиде текстан лаштык-влакым кычалаш',
'tooltip-p-logo'                  => 'Тӱҥ лаштык',
'tooltip-n-mainpage'              => 'Тӱҥ лаштыкыш куснаш',
'tooltip-n-recentchanges'         => 'Шукертсе огыл тӧрлатыме лаштык-влак списке.',
'tooltip-n-randompage'            => 'Вучыдымо (случайный) статьяш куснаш',
'tooltip-n-help'                  => 'Википедийым кучылтмо да тӧрлатыме шотышто полшык.',
'tooltip-t-whatlinkshere'         => 'Чыла лаштыкын спискыже кудо ты лаштыкыш куснат',
'tooltip-t-emailuser'             => 'Тиде пайдаланышылан электрон серышым возаш',
'tooltip-t-upload'                => 'Файл-влакым пурташ',
'tooltip-t-specialpages'          => 'Лӱмын ыштыме лаштык-влак списке',
'tooltip-t-print'                 => 'Тиде лаштыкым савыкташлан келыштарыме',
'tooltip-t-permalink'             => 'Тиде лаштык версийыш эре улшо ссылке',
'tooltip-ca-nstab-user'           => 'Пайдаланышын лаштыкшым ончалаш',
'tooltip-ca-nstab-image'          => 'Файлын лаштыкшым ончалаш',
'tooltip-minoredit'               => 'Тиде тӧрлымым изирак семын палемдаш',
'tooltip-save'                    => 'Тыйын тӧрлатымашым аралаш',
'tooltip-preview'                 => 'Лаштыкым аралыме деч ончыч ончылгоч ончал!',
'tooltip-diff'                    => 'Ончыкташ, могай тӧрлатымашым тый ыштенат.',
'tooltip-compareselectedversions' => 'Кок ойырымо лаштык версийын ойыртемым ончалаш.',
'tooltip-watch'                   => 'Тиде лаштыкым тыйын эскерыме-влак спискыш ешараш',

# Math options
'mw_math_png'    => 'Эре PNG-ым генерироватлаш',
'mw_math_simple' => 'Тыглай годым - HTML, уке гын - PNG',
'mw_math_html'   => 'Лиеш гын - HTML, уке гын - PNG',
'mw_math_source' => 'TeX-ым разметкыште кодаш (текст браузер-влаклан)',
'mw_math_modern' => 'Кызытлык (у) брузер-влаклан темлыме',
'mw_math_mathml' => 'Лиеш гын - MathML (эксперимент опций)',

# Media information
'widthheightpage' => '$1×$2, $3 {{PLURAL:$3|лаштык|лаштык}}',

# Special:NewImages
'showhidebots' => '(Бот-влакым $1 )',
'ilsubmit'     => 'Кычал',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'чыла',
'namespacesall' => 'чыла',
'monthsall'     => 'чыла',

# Multipage image navigation
'imgmultipageprev' => '← ончычсо лаштык',
'imgmultipagenext' => 'вес лаштык →',

# Table pager
'table_pager_next'         => 'Вес лаштык',
'table_pager_prev'         => 'Ончычсо лаштык',
'table_pager_limit_submit' => 'Кайе',

# Watchlist editing tools
'watchlisttools-view' => 'Келшыше тӧрлатымаш-влакым ончалаш',
'watchlisttools-edit' => 'Эскерыме спискым ончалаш да тӧрлаташ',
'watchlisttools-raw'  => 'Эскерыме спискым текст семын тӧрлаш',

# Special:Version
'version-specialpages' => 'Лӱмын ыштыме лаштык-влак',

# Special:FilePath
'filepath-page' => 'Файл:',

# Special:SpecialPages
'specialpages'             => 'Лӱмын ыштыме лаштык-влак',
'specialpages-group-login' => 'Пурымаш / регистрацийым эрташ',

);
