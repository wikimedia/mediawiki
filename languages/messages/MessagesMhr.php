<?php
/** Eastern Mari (Олык Марий)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
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
'tog-extendwatchlist'      => 'Чыла вашталтышым ончыкташлан эскерыме лӱмерым кугемдаш',
'tog-numberheadings'       => 'Вуймутым автоматик йӧн дене радамлаш',
'tog-showtoc'              => 'Вуймут радамым ончыкташ (3 деч шуко вуймутан лаштык-влаклан)',
'tog-rememberpassword'     => 'Тиде компьютерыште мыйын шолыпмутым шарнаш',
'tog-watchcreations'       => 'Мыйын ыштыме лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-watchdefault'         => 'Мыйын тӧрлатыме лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-watchmoves'           => 'Мыйын лӱмым вашталтыме лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-watchdeletion'        => 'Мыйын шӧрымӧ лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-nocache'              => 'Лаштыкым кешироватлымым чараш',
'tog-enotifwatchlistpages' => 'Мыйын эскерыме лӱмер гыч лаштыкыште тӧрлатымыш нерген электрон почто гоч шижтараш',
'tog-enotifusertalkpages'  => 'Мыйын каҥашымаш лаштыкыште тӧрлатымыш нерген электрон почто гоч шижтараш',
'tog-showjumplinks'        => '"Куснаш …" ешартыш ссылкым чӱкташ',
'tog-watchlisthideown'     => 'Эскерыме лӱмер гыч мыйын тӧрлатымаш-влакым ончыкташ огыл',
'tog-watchlisthidebots'    => 'Эскерыме лӱмер гыч бот-влакым тӧрлатымым ончыкташ огыл',
'tog-watchlisthideminor'   => 'Эскерыме лӱмер гыч изирак тӧрлатымаш-влакым ончыкташ огыл',
'tog-ccmeonemails'         => 'Моло ушнышо-влаклан колтымо серышын копийжым мыламат колташ',
'tog-diffonly'             => 'Кок версийым таҥастарыме годым лаштыкыште возымым ончыкташ огыл',
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
'pagecategories'         => '{{PLURAL:$1|Категорий|Категорий}}',
'category_header'        => '"$1" категорийыште лаштык-влак',
'subcategories'          => 'Подкатегорий-влак',
'hidden-categories'      => '{{PLURAL:$1|Шылтыме категорий|Шылтыме категорий-влак}}',
'category-subcat-count'  => '{{PLURAL:$2|Тиде категорийыш ик подкатегорий гына пура.|{{PLURAL:$1|Тыгай $1 подкатегорий|Тыгане $1 подкатегорий-влак}} тиде категорийыште, чыла $2.}}',
'category-article-count' => '{{PLURAL:$2|Тиде категорийыш ик лаштык гына пура.|{{PLURAL:$1|Тыгай $1 лаштык|Тыгане $1 лаштык-влак}} тиде категорийыште, чыла $2.}}',
'listingcontinuesabbrev' => '(умбакыжым)',

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

'errorpagetitle'   => 'Йоҥылыш',
'returnto'         => '$1 деке пӧртылаш.',
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
'create'           => 'Ышташ',
'editthispage'     => 'Тӧрлаташ тиде лаштыкым',
'delete'           => 'Шӧраш',
'protect'          => 'Аралаш',
'protect_change'   => 'вашталташ',
'protectthispage'  => 'Тиде  лаштыкым тӧрлатымаш деч аралаш',
'newpage'          => 'У лаштык',
'talkpage'         => 'Каҥашымаш тиде лаштык нерген',
'talkpagelinktext' => 'Каҥашымаш',
'personaltools'    => 'Шке ӱзгар-влак',
'articlepage'      => 'Лаштыкыште возымым ончыкташ',
'talk'             => 'Каҥашымаш',
'views'            => 'Лаштыкын тӱрлыкшӧ',
'toolbox'          => 'Ӱзгар-влак',
'viewtalkpage'     => 'Ончалаш каҥашымашым',
'otherlanguages'   => 'Вес йылме дене',
'redirectedfrom'   => '(Колтымо $1 гыч)',
'redirectpagesub'  => 'Вес верек колтымо лаштык',
'lastmodifiedat'   => 'Тиде лаштыкым пытартыш гана $2 $1 тӧрлымӧ.', # $1 date, $2 time
'jumpto'           => 'Куснаш:',
'jumptonavigation' => 'навигациеш',
'jumptosearch'     => 'кычалмашке',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} нерген',
'aboutpage'            => 'Project:Нерген',
'copyright'            => 'Лаштыкыште возымо $1 йӧн дене почмо.',
'copyrightpage'        => '{{ns:project}}:Авторский права',
'currentevents'        => 'Кызытсе событий',
'disclaimers'          => 'Мут кучымаш деч кораҥмаш',
'disclaimerpage'       => 'Project:Мут кучымаш деч кораҥмаш',
'edithelp'             => 'Тӧрлатымаште полыш',
'edithelppage'         => 'Help:Тӧрлымаш',
'helppage'             => 'Help:Полшык',
'mainpage'             => 'Тӱҥ лаштык',
'mainpage-description' => 'Тӱҥ лаштык',
'privacy'              => 'Конфиденциальность политике',
'privacypage'          => 'Project:Конфиденциальность политике',

'badaccess' => 'Кертмаште йоҥылыш лийын',

'retrievedfrom'       => 'Налме вер — "$1"',
'youhavenewmessages'  => 'Тендан $1 уло ($2).',
'newmessageslink'     => 'у увертыш-влак',
'newmessagesdifflink' => 'пытартыш тӧрлатымаш',
'editsection'         => 'тӧрлаташ',
'editold'             => 'тӧрлаташ',
'editlink'            => 'тӧрлаташ',
'viewsourcelink'      => 'тӱҥалтыш текстым ончалаш',
'editsectionhint'     => 'Тӧрлаташ ужашым: $1',
'toc'                 => 'Вуйлымаш',
'showtoc'             => 'ончыктаж',
'hidetoc'             => 'шылташ',
'viewdeleted'         => 'Ончалаш $1?',
'site-rss-feed'       => '$1 RSS-кыл',
'site-atom-feed'      => '$1 Atom-кыл',
'page-rss-feed'       => '"$1" RSS-кыл',
'page-atom-feed'      => '"$1" Atom-кыл',
'red-link-title'      => '$1 (тыгай лаштык уке)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Лаштык',
'nstab-user'     => 'Пайдаланышын лаштыкше',
'nstab-special'  => 'Лӱмын ыштыме лаштык',
'nstab-project'  => 'Проект нерген',
'nstab-image'    => 'Файл',
'nstab-template' => 'Ямдылык',
'nstab-category' => 'Категорий',

# General errors
'error'              => 'Йоҥылыш',
'missing-article'    => 'Пале кумдыкыште лаштыкын йодмо текстым муымо огыл (кудым муаш кӱлеш), "$1" $2.

Тыге лиеш, кунам тоштемше ссылке дене шӧрымӧ лаштыкын вашталтымаш историйышкыже куснаш толашыме годым.

Йоҥлыш тыште уке гын, очыни, паша программыште.
Тидын нерген URL-ым ончыктен [[Special:ListUsers/sysop|сайтвиктарышым]] шижтаре.',
'missingarticle-rev' => '(тӱрлык#: $1)',
'badtitletext'       => 'Йодмо лаштыкын лӱмжӧ йонгылыш, але яра, але йылме кокла але интер-вики лӱмжӧ йонгылыш. Ала лӱмыштӧ оккӱл тамга улыт.',
'viewsource'         => 'Тӱҥалтыш текст',
'viewsourcefor'      => '$1 лан',

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
'nosuchuser'              => '"$1" лӱман пайдаланыше уке.
Лӱмым чын возымым терге але [[Special:UserLogin/signup|регистрацийым эрте]].',
'nouserspecified'         => 'Тылат пайдаланышын лӱмжым пуртыман.',
'wrongpassword'           => 'Тый йоҥылыш шолыпмутым пуртенат.
Эше ик гана ыштен ончо.',
'wrongpasswordempty'      => 'Тый яра шолыпмутым пуртенат.
Эше ик гана ыштен ончо.',
'passwordtooshort'        => 'Тыйын шолыпмутет келшен огеш тол але пеш кӱчык.
Тудо {{PLURAL:$1|1 символ|$1 символ}} деч шагал огыл лийшаш да тыйын пайдаланыше лӱмет ден икгай лийшаш огыл.',
'mailmypassword'          => 'У шолыпмутым колташ',
'passwordremindertitle'   => '{{SITENAME}} сайтлан жаплан ыштыме у шолыпмут',
'passwordremindertext'    => '{{SITENAME}} сайтлан ($4) $1 IP адрес гыч ала-кӧ (але тый) у шолыпмутым йодын. "$2" пайдаланышылан жаплан ыштыме у шолыпмутым ыштыме да "$3" электрон адресыш колтымо. Тидым тый йодынат гын, системыш у шолыпмут дене пуро.

Йодмашым весе ыштен гын, але тый шке шолыпмутетым шарненат гын, тиде увертышым шотыш налде, тошто шолыпмут дене пайдалане.',
'noemail'                 => '"$1" пайдаланыше электрон адресым палемден огыл.',
'passwordsent'            => 'У шолыпмутым "$1" пайдаланышын электрон адресышкыже колтымо. Шолыпмутым налмеке системыш угыч пуро.',
'eauthentsent'            => 'Пеҥгыдемдымаш дене серышым темлыме электрон адресыш колтымо. Электрон почто адресын тыйын улмым пеҥгыдемдаш, серышыште улшо инструкцийым шукто.',
'emailauthenticated'      => 'Тыйын почто адресетым пеҥгыдемдыме $1.',
'loginlanguagelabel'      => 'Йылме: $1',

# Password reset dialog
'oldpassword' => 'Тошто шолыпмут:',
'newpassword' => 'У шолыпмут:',
'retypenew'   => 'Пеҥгыдемдыза у шолыпмутым:',

# Edit page toolbar
'bold_sample'     => 'Кӱжгӧ текст',
'bold_tip'        => 'Кӱжгӧ текст',
'italic_sample'   => 'Шӧрын текст',
'italic_tip'      => 'Шӧрын текст',
'link_sample'     => 'Ссылкын лӱмжӧ',
'link_tip'        => 'Кӧргысӧ ссылке',
'extlink_sample'  => 'http://www.example.com ссылкын лӱмжӧ',
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
'summary'                          => 'Тӧрлатымаш нерген:',
'subject'                          => 'Теме/вуймут:',
'minoredit'                        => 'Тиде изирак тӧрлатыме',
'watchthis'                        => 'Тиде лаштыкым эскераш',
'savearticle'                      => 'Лаштыкым аралаш',
'preview'                          => 'Ончылгоч ончымаш',
'showpreview'                      => 'Ончылгоч ончымаш',
'showdiff'                         => 'Тӧрлатымашым ончыкташ',
'anoneditwarning'                  => "'''Тӱтко лий:''': Тый шкенетым палымым ыштен отыл. Тыйын IP адресет лаштыкын вашталтыме историйштыже вазалтен кодеш.",
'summary-preview'                  => 'Тӧрлатымаш нерген ончылгоч ончымаш:',
'newarticle'                       => '(У)',
'newarticletext'                   => "Тый ссылка почеш уке улшо лаштыкыш кусненат.
Лаштыкым ышташлан ӱлнӧ возаш тӱҥал (сайрак палашлан [[{{MediaWiki:Helppage}}|полшыкым]] ончал).
Тый тышке йонгылыш логалынат гын, браузерыште '''шенгек''' кнопкым темдал.",
'noarticletext'                    => 'Кызытсе жаплан тиде лаштыкышты нимом возымо огыл.
Тый тиде лаштыкын лӱмжым вес лаштык-влакыште [[Special:Search/{{PAGENAME}}|кычалын]] кертат, але <span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}}журнал-влакыште кычалын кертат], але [{{fullurl:{{FULLPAGENAME}}|action=edit}} тыйгай лӱман лаштыкым ышташ]</span>.',
'clearyourcache'                   => "'''Ешартыш''': Аралыме деч вара вашталтышым ужаш браузеретын кешыжым эрыкташ логалын кертеш. '''Mozilla / Firefox / Safari:''' ''Shift''-ым темдал кучен ''Reload''-ым темдал але ''Ctrl-F5'' але ''Ctrl-R'' темдал (Macintosh-влак ''Command-R''); '''Konqueror:''' темдал ''Reload'' кнопкым але ''F5'' темдал; '''Opera:''' ''Tools→Preferences''-ыште кешым эрыкте; '''Internet Explorer:''' ''Ctrl''-ым темдал кучен ''Refresh''-ым темдал але ''Ctrl-F5'' темдал.",
'previewnote'                      => "'''Тиде ончылгоч ончымаш гына;
вашталтыш-влакым эше аралыме огыл!'''",
'editing'                          => 'Тӧрлаталтеш $1',
'editingsection'                   => 'Тӧрлаталтеш $1 (ужаш)',
'copyrightwarning'                 => "Шотыш нал, чыла надырым {{SITENAME}} проектыш $2 лицензий почеш лукмо семын шотлыман($1 ончал). 
Тыйын текстет весе-влаклан ынже логал да керек кӧ тудым ынже тӧрлатен керт манын, тышке тудым ит шыҥдаре.<br />
Тыгак тидым тый шке возенат але тудым эрыкан шаркалаш лийше вер гыч налынат манын, мутым пуэт.<br />
'''АВТОР АЛЕ ТУДЫН ПРАВАМ АРАЛЫШЕ-ВЛАК ДЕЧ ЙОДДЕ МАТЕРИАЛЫМ ИТ ШЫҤДАРЕ!'''",
'templatesused'                    => 'Тиде лаштыкыште кучылтмо ямдылык-влак:',
'templatesusedpreview'             => 'Тиде ончылгоч ончымаште кучылтмо ямдылык-влак:',
'template-protected'               => '(тӧрлаташ чарыме)',
'template-semiprotected'           => '(верын аралыме)',
'hiddencategories'                 => 'Тиде лаштык $1 {{PLURAL:$1|шылтыме категорийыш|шылтыме категорийыш}} лектеш:',
'permissionserrorstext-withaction' => "Тыйын '''$2''' кертмешет шагал. Тиде {{PLURAL:$1|амал ден|амал дене}}:",
'deleted-notice'                   => 'Тиде лаштык шӧрымӧ лийын.
Тиде лаштыклан шӧрымӧ нерген журнал ӱлнӧ ончыктымо.',

# History pages
'viewpagelogs'           => 'Тиде лаштык лан журнал-влак ончыкташ',
'currentrev'             => 'Кызытсе тӱрлык',
'currentrev-asof'        => '$1 кечын кызытсе тӱрлык',
'revisionasof'           => '$1 кечын тӱрлык',
'previousrevision'       => '← Ончычсо тӱрлык',
'nextrevision'           => 'Вес тӱрлык →',
'currentrevisionlink'    => 'Кызытсе тӱрлык',
'cur'                    => 'кызыт',
'last'                   => 'ончычсо',
'histlegend'             => "Таҥастарашлаш ӱлнӧ версийым ойырымо кнопкым але Enter-ым темдал.<br />
Легенде: (кызыт) = кызытсе версий деч ойыртеммалтмаш, (ончычсо) = ончычсо версий деч ойыртеммалтмаш, '''и''' = изирак тӧрлатыме.",
'history-fieldset-title' => 'Историйым ончыкташ',
'histfirst'              => 'Эн тошто',
'histlast'               => 'Эн у',

# Revision deletion
'rev-delundel'   => 'ончыкташ/шылташ',
'revdel-restore' => 'Коймашым вашталташ',
'pagehist'       => 'Лаштыкын историй',

# Merge log
'revertmerge' => 'Ойыраш',

# Diffs
'history-title'           => '$1 лан тӱрлык историй',
'difference'              => '(Тӱрлык-влакын ойыртемышт)',
'lineno'                  => '$1 корно:',
'compareselectedversions' => 'Ойырымо версий-влакым таҥастараш',
'editundo'                => 'чараш',

# Search results
'searchresults'             => 'Кычалын мумо',
'searchresults-title'       => 'Кычалын мумо «$1»-лан',
'searchresulttext'          => "{{SITENAME}}'ыште кычалмаш нерген шукырак палнеда гын, [[{{MediaWiki:Helppage}}|полышым]] ончыза.",
'searchsubtitle'            => 'Тые кычалыч \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|чыла лаштык-влакым, кудыжо тӱҥалыт: "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|чыла лаштык-влакым, "$1" дене кылым палемдат]])',
'searchsubtitleinvalid'     => "Тые кычалыч '''$1'''",
'noexactmatch'              => "'''\"\$1\" лӱман лаштык уке.'''
Тый [[:\$1|тиде лаштыкым ыштен]] кертат.",
'noexactmatch-nocreate'     => "'''\"\$1\" лӱман лаштык уке.'''",
'notitlematches'            => 'Лаштык-влакын лӱмыштышт икгайлык уке',
'notextmatches'             => 'Лаштык-влакыште икгайлык возымо уке',
'prevn'                     => 'кодшо $1',
'nextn'                     => 'весе $1',
'viewprevnext'              => 'Ончал ($1) ($2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|$2 мут|$2 мут-влак}})',
'search-redirect'           => '($1 вес вере колтымаш)',
'search-section'            => '(ужаш $1)',
'search-suggest'            => 'Але те $1 возынеда ыле',
'search-interwiki-caption'  => 'Родо проект-влак',
'search-interwiki-default'  => "$1'ште мумо:",
'search-interwiki-more'     => '(эше)',
'search-mwsuggest-enabled'  => 'сугынь дене',
'search-mwsuggest-disabled' => 'сугынь деч посна',
'showingresultstotal'       => "Ӱлнӧ '''$3''' кычалын мумо гыч {{PLURAL:$4|'''$1'''|'''$1 — $2'''}} ончыктымо",
'nonefound'                 => "'''Ешартыш''':  Посна каласыме огыл дык, кычалмаш южо лӱм-влакын кумдыкышто эрта. Уло лӱм-влакын кумдыкышто кычалашлан(чӱктен каҥашымаш лаштык-влакым, ямдылык-влакым и туге молат) шке йодмашыштет ''all:'' префиксым кучылт, але кӱлешан лӱм-влакын кумдыкым ончыкто.",
'powersearch'               => 'Сайынрак кычал',
'powersearch-legend'        => 'Сайынрак кычалаш',
'powersearch-ns'            => 'Кычалаш тиде лӱм-влакын кумдыкышт-влакыште:',
'powersearch-redir'         => 'Вес верек колтымо лаштык-влакым ончыкташ',
'powersearch-field'         => 'Кычалаш',

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
'prefs-watchlist'       => 'Эскерымаш лӱмер',
'prefs-watchlist-days'  => 'Мыняр кече эскерымаш лӱмерыште ончыкталтеш?',
'prefs-watchlist-edits' => 'Мыняр тӧрлатымашым ышташ лиймым кугемдыме эскерымаш лӱмерыште ончыктымо?',
'prefs-misc'            => 'Тӱрлӧ',
'saveprefs'             => 'Аралаш',
'resetprefs'            => 'Тӧрлатымым шотыш налаш огыл',
'searchresultshead'     => 'Кычалме',
'savedprefs'            => 'Тыйын настройке-влакетым аралыме.',
'allowemail'            => 'Вес ушнышо-влак деч электрон почтым налаш кӧнаш',
'default'               => 'ойлыде',

# Groups
'group-bot'   => 'Бот-влак',
'group-sysop' => 'Сайтвиктарыше-влак',

'group-bot-member' => 'бот',

'grouppage-bot'   => '{{ns:project}}:Бот-влак',
'grouppage-sysop' => '{{ns:project}}:Сайтвиктарыше-влак',

# User rights log
'rightslog' => 'Пайдаланышын права нерген журнал',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'тиде лаштыкым тӧрлаташ',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|тӧрлатымаш|тӧрлатымаш-влак}}',
'recentchanges'                  => 'Шукертсе огыл тӧрлымаш-влак',
'recentchanges-legend'           => 'Шукертсе огыл тӧрлымаш-влак настройке',
'recentchanges-feed-description' => 'Викин тиде кылыште пытартыш вашталтышым шекланаш.',
'rcnote'                         => "Ӱлнӧ {{PLURAL:$1|'''1'''|'''$1'''}} вашталтыш пытартыш {{PLURAL:$2||'''$2'''}} кечылан, $5-лан, $4-лан.",
'rclistfrom'                     => '$1 гыч тӱҥалын у вашталтымашым ончыкташ',
'rcshowhideminor'                => 'Изирак тӧрлымым $1',
'rcshowhidebots'                 => 'Бот-влакым $1',
'rcshowhideliu'                  => 'Шолып пайдаланыше-влакым $1',
'rcshowhideanons'                => 'Ончыкталтше пайдаланыше-влакым $1',
'rcshowhidemine'                 => 'Мыйын тӧрлымым $1',
'rclinks'                        => 'Пытартыш $2 кечылан $1 вашталтымашым ончыкташ<br />$3',
'diff'                           => 'ойырт.',
'hist'                           => 'ист.',
'hide'                           => 'шылташ',
'show'                           => 'ончыкташ',
'minoreditletter'                => 'и',
'newpageletter'                  => 'У',
'boteditletter'                  => 'б',
'rc-enhanced-expand'             => 'Тӱткынракым ончыкташ (JavaScript кӱлеш)',
'rc-enhanced-hide'               => 'Тӱткынракым шылташ',

# Recent changes linked
'recentchangeslinked'          => 'Ваш кылдалтше тӧрлатымаш-влак',
'recentchangeslinked-title'    => '"$1" лаштыклан кылдалтше тӧрлатымаш-влак',
'recentchangeslinked-noresult' => 'Ончыктымо пагытыште кылдалтше лаштыклаште вашталтыш лийын огыл.',
'recentchangeslinked-summary'  => "Тиде шукертсе огыл тӧрлымӧ лаштык-влакын, кудыжо палемдыме лаштык дене кылдалтыныт (ала пелемдыме категорийыш пурат) лӱмерже.
[[Special:Watchlist|Тыйын эскерымаш лӱмерыш]] пурышо лаштык-влакым '''кӱжгӱн''' палемдыме.",
'recentchangeslinked-page'     => 'Лаштыкын лӱмжӧ:',
'recentchangeslinked-to'       => 'Тиде лаштык дене кылдалтше лаштык-влакыште тӧрлатымашым ончыкташ тидын олмеш',

# Upload
'upload'            => 'Файлым пурташ',
'uploadbtn'         => 'Файлым пурташ',
'uploadlogpage'     => 'Пуртарыме журнал',
'filedesc'          => 'Файл нерген кӱчыкын увертараш',
'fileuploadsummary' => 'Тидын нерген кӱчыкын:',
'uploadedimage'     => '«[[$1]]» пуртыш',
'watchthisupload'   => 'Тиде лаштыкым эскераш',

# Special:ListFiles
'imgfile'        => 'файл',
'listfiles_user' => 'Пайдаланыше',

# File description page
'filehist'                  => 'Файлын историй',
'filehist-help'             => 'Файл ончыч могай ыле манын ончалнет гын, кече/жапым темдал.',
'filehist-deleteone'        => 'шӧраш',
'filehist-current'          => 'кызыт',
'filehist-datetime'         => 'Кече/жап',
'filehist-thumb'            => 'Иземдыме сӱрет',
'filehist-thumbtext'        => '$1 версий лан иземдыме сӱрет',
'filehist-user'             => 'Пайдаланыше',
'filehist-dimensions'       => 'Кугытшо',
'filehist-filesize'         => 'Файлын кугытшо',
'filehist-comment'          => 'Файл нерген:',
'imagelinks'                => 'Файл деке ссылке-влак',
'linkstoimage'              => 'Тиде {{PLURAL:$1|$1 лаштык саде файл дене кылдалтын|$1 лаштык-влак саде файл дене кылдалтыныт}}:',
'nolinkstoimage'            => 'Тиде файл дене кылдалтше ик лаштыкат уке.',
'sharedupload'              => 'Тиде файлын верже: $1, туге гынат, тудым моло веренат кучылташ лиеш.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki-desc'     => 'Ӱлнӧ $1 возымо ончыктымо.',
'shareduploadwiki-linktext' => 'файлын умылтартыш лаштыкыште',
'uploadnewversion-linktext' => 'Тиде файлын у версийжим пурташ',

# File deletion
'filedelete-comment'          => 'Шӧрымын амалже:',
'filedelete-submit'           => 'Шӧраш',
'filedelete-otherreason'      => 'Вес/ешартыш амал:',
'filedelete-reason-otherlist' => 'Вес амал',

# Random page
'randompage' => 'Вучыдымо (случайный) статья',

# Statistics
'statistics' => 'Статистике',

'brokenredirects-delete' => '(шӧраш)',

'withoutinterwiki-submit' => 'ончыкташ',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|байт|байт}}',
'nmembers'          => '$1 {{PLURAL:$1|лаштык|лаштык-влак}}',
'nviews'            => '$1 {{PLURAL:$1|ончымо|ончымо-влак}}',
'prefixindex'       => 'Чыла лаштык-влак префикс дене',
'newpages'          => 'У лаштык-влак',
'newpages-username' => 'Пайдаланышын лӱмжӧ:',
'move'              => 'Кусараш',
'movethispage'      => 'Тиде лаштыкым кусараш',
'pager-newer-n'     => '{{PLURAL:$1|вес|вес}}',
'pager-older-n'     => '{{PLURAL:$1|ончычсо|ончычсо}}',

# Book sources
'booksources'               => 'Негызым пыштыше книга-влак',
'booksources-search-legend' => 'Негызым пыштыше книгам кычалаш',
'booksources-go'            => 'Муаш',

# Special:Log
'specialloguserlabel' => 'Пайдаланыше:',
'log'                 => 'Журнал-влак',

# Special:AllPages
'allpages'       => 'Чыла лаштык-влак',
'alphaindexline' => '$1 $2 марте',
'prevpage'       => 'Ончычсо лаштык ($1)',
'allpagesfrom'   => 'Лукташ тыгай лӱман лаштык-влакым, кудыжо тӱҥалыт:',
'allpagesto'     => 'кудыжо пытат:',
'allarticles'    => 'Чыла лаштык-влак',
'allpagessubmit' => 'Кайе',

# Special:LinkSearch
'linksearch'    => 'Ӧрдыж ссылке-влак',
'linksearch-ns' => 'Лӱм-влакын кумдыкышт:',
'linksearch-ok' => 'Кычал',

# Special:ListUsers
'listusers-submit' => 'ончыкташ',

# Special:Log/newusers
'newuserlogpage'          => 'У пайдаланыше регистрацийым эртарыме журнал',
'newuserlog-create-entry' => 'У пайдаланыше',

# Special:ListGroupRights
'listgrouprights-members' => '(тӱшкаште улшо-влак)',

# E-mail user
'emailuser' => 'Пайдаланыше дек серыш',

# Watchlist
'watchlist'         => 'Мыйын эскерымаш лӱмер',
'mywatchlist'       => 'Мыйын эскерымаш лӱмер',
'watchlistfor'      => "('''$1''' лан)",
'addedwatch'        => 'Эскерымаш лӱмерыш ешарыме',
'addedwatchtext'    => "\"[[:\$1]]\" лаштыкым тыйын [[Special:Watchlist|эскерымаш лӱмерыш]] ешарыме.
Тиде лаштыкын да тудын каҥашымаш лаштыкым умбакысе тӧрлатымашым тиде спискыште ончыктымо лиеш да, сайрак ужаш манын, [[Special:RecentChanges|шукертсе огыл тӧрлымаш лӱмерыште]] '''кӱжгӧ шрифт''' дене ойырымо.",
'removedwatch'      => 'Эскерымаш лӱмер гыч шӧрымӧ',
'removedwatchtext'  => '«[[:$1]]» лаштыкым [[Special:Watchlist|тыйын эскерыме лӱмер]] гыч кораҥдыме.',
'watch'             => 'Эскераш',
'watchthispage'     => 'Тиде лаштыкым эскераш',
'unwatch'           => 'Эскерыман огыл',
'unwatchthispage'   => 'Эскерымым чарнаш',
'watchlist-details' => 'Тыйын эскерымаш лӱмерыште $1 {{PLURAL:$1|лаштык|лаштык-влак}}, каҥашымаш лаштык-влакым шотлыде.',
'watchlistcontains' => 'Тыйын лӱмерыште $1 {{PLURAL:$1|лаштык|лаштык}}.',
'wlshowlast'        => 'Пытартыш $1 шагат $2 кечылан $3 ончыкташ',
'watchlist-options' => 'Эскерыме лӱмерын настройке-влак',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Эскерымаш лӱмерыш ешарымаш...',
'unwatching' => 'Эскерымаш лӱмер гыч шӧрымаш...',

# Delete
'deletepage'            => 'Лаштыкым шӧраш',
'delete-confirm'        => 'Шӧраш "$1"',
'delete-legend'         => 'Шӧраш',
'historywarning'        => 'Тӱтко лий: шӧраш шонымо лаштыкет вашталтыш-влак нерген историйым нумалеш:',
'confirmdeletetext'     => 'Тый тиде лаштыкым пӱтынь историйже дене йӧршын шӧрынет.
Пеҥгыдемде тидым [[{{MediaWiki:Policy-url}}|правил]] почеш ыштыметым да, мо тидын деч вара лиймым, умылыметым.',
'actioncomplete'        => 'Действийым ыштыме',
'deletedtext'           => '«<nowiki>$1</nowiki>» шӧрымӧ.
Ончо $2 пытартыш шӧрымӧ-влак лӱмер гыч.',
'deletedarticle'        => '«[[$1]]» шӧрымӧ',
'dellogpage'            => 'Шӧрымӧ нерген журнал',
'deletionlog'           => 'шӧрымӧ нерген журнал',
'deletecomment'         => 'Шӧрымын амалже:',
'deleteotherreason'     => 'Вес/ешартыш амал:',
'deletereasonotherlist' => 'Вес амал',

# Rollback
'rollbacklink' => 'пӧртылаш',

# Protect
'protectlogpage'              => 'Тӧрлатымаш деч аралыме нерген журнал',
'protectedarticle'            => '«[[$1]]» тӧрлатымаш деч аралыме лийын',
'modifiedarticleprotection'   => '"[[$1]]" лаштыкын  шыгыремдымашын чотшым вашталтыме.',
'protectcomment'              => 'Аралыме нерген:',
'protectexpiry'               => 'Мучашлалтеш:',
'protect_expiry_invalid'      => 'Йоҥылыш мучашлалтше жап.',
'protect_expiry_old'          => 'Мучашлалтше жап эртен.',
'protect-unchain'             => 'Лаштык кусараш кертме йоным ышташ',
'protect-text'                => "Тыште тый '''<nowiki>$1</nowiki>''' лаштыкын шыгыремдымашыжым ончалаш да тӧрлаташ кертат.",
'protect-locked-access'       => "Тыйын лаштыкын шыгыремдымашыжым тӧрлаш кертмешет шагал.
Ӱлнӧ '''$1''' лаштыкын кызытсе настройкыже-влак.",
'protect-cascadeon'           => 'Тиде лаштыкым кыдалтше аралтышан {{PLURAL:$1|лаштыкыш, куштыжо|лаштык-влакыш, куштыжо}} пурымылан кӧра кызыт аралыме. Тый тиде лаштыкын шыгыремдымашыжым тӧрлатен кертат, тидын годым кылдалтше аралтыш огеш вашталт.',
'protect-default'             => 'Чыла пайдаланыше лан йӧным пуаш',
'protect-fallback'            => '«$1» кертеж кӱлеш',
'protect-level-autoconfirmed' => 'Регистрацийым эртыдыме да у пайдаланыше-влак деч петыраш',
'protect-level-sysop'         => 'Сайтвиктарыше-влак гына',
'protect-summary-cascade'     => 'кылдалтше',
'protect-expiring'            => '$1 мучашлалтеш (UTC)',
'protect-cascade'             => 'Тиде лаштыкыш пурышо лаштыш-влакым аралаш (кылдалтше аралтыш)',
'protect-cantedit'            => 'Тый тиде лаштыкын шыгыремдымашыжым тӧрлатен от керт, тидлан тылат кертеж пуалтын огыл.',
'protect-otherreason'         => 'Вес/ешартыш амал:',
'protect-otherreason-op'      => 'вес/ешартыш амал',
'restriction-type'            => 'Кертеж:',
'restriction-level'           => 'Тыгай шыгыремдаш:',

# Undelete
'undeletelink'           => 'ончалаш/тӧрлатен шындаш',
'undeletedarticle'       => '«[[$1]]» тӧрлатен шынден',
'undelete-search-submit' => 'Кычал',

# Namespace form on various pages
'namespace'      => 'Лӱм-влакын кумдыкышт:',
'invert'         => 'Палемдымашым ваштареш ышташ',
'blanknamespace' => '(Тӱҥ)',

# Contributions
'contributions'       => 'Пайдаланышын надырем',
'contributions-title' => '$1 пайдаланышын надыр',
'mycontris'           => 'Мыйын надырем',
'contribsub2'         => '$1 лан ($2)',
'uctop'               => '(пытартыш)',
'month'               => 'Могай тылзе гыч тӱҥалаш? (але ондакрак):',
'year'                => 'Могай ий гыч тӱҥалаш? (але ондакрак):',

'sp-contributions-newbies'  => 'У пайдалнышын гына надырым ончыкташ',
'sp-contributions-blocklog' => 'йӧным вашталтыме журнал',
'sp-contributions-search'   => 'Надырым кычалаш',
'sp-contributions-username' => 'IP-адрес ала пайдаланышын лӱмжӧ:',
'sp-contributions-submit'   => 'Кычал',

# What links here
'whatlinkshere'            => 'Ссылке-влак тышке',
'whatlinkshere-title'      => '"$1" дене лаштык-влак кылым палемдат',
'whatlinkshere-page'       => 'Лаштык:',
'linkshere'                => "'''[[:$1]]''' лаштык дене кылдалтше лаштык-влак:",
'isredirect'               => 'вес верек колтымо лаштык',
'istemplate'               => 'пурмаш',
'isimage'                  => 'сӱретыш ссылке',
'whatlinkshere-prev'       => '{{PLURAL:$1|ончычсо|$1 ончычсо}}',
'whatlinkshere-next'       => '{{PLURAL:$1|вес|$1 вес}}',
'whatlinkshere-links'      => '← ссылке-влак',
'whatlinkshere-hideredirs' => 'вес вере колтымаш-влакым $1',
'whatlinkshere-hidetrans'  => 'пурмашым $1',
'whatlinkshere-hidelinks'  => 'ссылке-влакым $1',
'whatlinkshere-filters'    => 'Фильтр-влак',

# Block/unblock
'blockip'                  => 'Пайдаланышылан йӧным петраш',
'ipbreason'                => 'Амал:',
'ipbreasonotherlist'       => 'Вес амал',
'ipboptions'               => '2 жап:2 hours,1 кече:1 day,3 кече:3 days,1 арня:1 week,2 арня:2 weeks,1 тылзе:1 month,3 тылзе:3 months,6 тылзе:6 months,1 ий:1 year,нимучашдымылык:infinite', # display1:time1,display2:time2,...
'ipbotherreason'           => 'Вес/ешартыш амал:',
'ipblocklist'              => 'Петырыме IP адрес-влак да пайдаланыше-влак',
'ipblocklist-submit'       => 'Кычал',
'blocklink'                => 'йӧным петраш',
'unblocklink'              => 'йӧным почаш',
'change-blocklink'         => 'йӧным вашталташ',
'contribslink'             => 'надыр',
'blocklogpage'             => 'Йӧным вашталтыме журнал',
'blocklogentry'            => '[[$1]] лан йӧным петрен $2 $3 мучашлалтеш',
'unblocklogentry'          => '$1 лан йӧным почен',
'block-log-flags-nocreate' => 'у пайдаланыше-влаклан регистрацийым чактарыме',

# Move page
'move-page-legend' => 'Лаштыкым кусараш',
'movepagetext'     => "Ӱлыл формо дене пайдаланен, тый лаштыкын лӱмым вашталтен кертат, тудын вашталтыме историйже у верыш кусарыме.
Тошто лӱмыште у лӱмыш колтымо лаштык кодеш.
Тый тошто лӱмыш колтымо лаштык-влакым шке семын вашталтке кертат.
Тый тидым ынет ыште гын, [[Special:DoubleRedirects|кокытан]] да [[Special:BrokenRedirects|пудыргышо вес вере колтымашым]] терге.
Тый палемдыме верыш ссылке-влаклан шуйнымылан да тушко ончыктымылан вуйын шогет.

Шотыш нал: кунам у лӱман лаштык уло, тудо '''ок''' кусаралт. Тыге огыл, кунам лаштык вес вере кусаралтеш але тудо яра да вашталтымаш историйже уке.
Тый лаштыкым йонгылыш кусаренат гын менгешла тудым тошто лӱмыш кусарен кертат, но тый уже улшо лаштыкым ӱштын от керт, манын ончыкта.

'''Тӱтко лий!'''
Чӱчкыдын кучылтмо лаштыклан тиде кугу вашталтышым ыштен кертеш;
Умбаке кайыме деч ончыч шоналте, тый тидын деч вара лиймым умылет.",
'movepagetalktext' => "Тиде лаштыкын каҥашымаш лаштык шке семын огеш кусно, '''тидлан амалже:'''
*Тыгай лӱман яра огыл каҥашымаш лаштык уже уло ала
*Ӱлыч кайыкым от корангде.

Тыгай годым тылат лаштыкым шке кидет дене кусараш але иктеш ушнаш кӱлеш.",
'movearticle'      => 'Тиде лаштыкым кусараш:',
'newtitle'         => 'У лӱм:',
'move-watch'       => 'Тиде лаштыкым эскераш',
'movepagebtn'      => 'Лаштыкым кусараш',
'pagemovedsub'     => 'Кусарымаш сайын эртен',
'movepage-moved'   => '<big>\'\'\'"$1" лаштыкым "$2" лаштыкыш кусарыме\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Тыгай лӱман лаштык уло але тиде лӱмым кучылташ огеш лий. Вес лӱмым ойыро.',
'talkexists'       => "'''Лаштыкым кусарыме гынат, тудын каҥашымаш лаштыкшым тыгай лӱман лаштык улмылан кӧра кусараш огеш лий. Нуным шке кидет дене иктыш ушно.'''",
'movedto'          => 'лаштыкыш кусарыме',
'movetalk'         => 'Каҥашымаш лаштыкым кусараш',
'1movedto2'        => '[[$1]] лаштыкым [[$2]] лаштыкыш кусарыме',
'1movedto2_redir'  => '[[$1]] лаштыкым [[$2]] лаштыкыш кусарыме ӱмбал вес вере колтымаш',
'movelogpage'      => 'Кусарыме нерген журнал',
'movereason'       => 'Амал:',
'revertmove'       => 'мӧҥгешла пӧртылаш',

# Export
'export' => 'Лаштык-влакым келыштараш',

# Thumbnails
'thumbnail-more' => 'Кугемдаш',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Тыйын лаштыкет',
'tooltip-pt-mytalk'               => 'Тыйын каҥашымаш лаштыкет',
'tooltip-pt-preferences'          => 'Мыйын настройке-влак',
'tooltip-pt-watchlist'            => 'Мыйын эскерыме лаштык-влак лӱмер',
'tooltip-pt-mycontris'            => 'Тыйын надыр лӱмер',
'tooltip-pt-login'                => 'Шке денет палымым ыштет гын сайрак лиеш; такшым тидым ыштыдеат кертат.',
'tooltip-pt-logout'               => 'Системе гыч лекташ',
'tooltip-ca-talk'                 => 'Лаштыкыште возымым каҥашымаш',
'tooltip-ca-edit'                 => 'Тый тиде лаштыкым тӧрлатен кертат. Лаштыкым аралыме деч ончыч тудым тергаш ит мондо.',
'tooltip-ca-addsection'           => 'У ужашым тӱҥалаш',
'tooltip-ca-viewsource'           => 'Тиде лаштыкым аралыме.
Тый тудын тӱҥалтыш текстшым ончалын кертат.',
'tooltip-ca-history'              => 'Лаштыкын ончычсо версийже-влак.',
'tooltip-ca-protect'              => 'Тиде лаштыкым тӧрлатымаш деч аралаш',
'tooltip-ca-delete'               => 'Шӧраш тиде лаштыкым',
'tooltip-ca-move'                 => 'Тиде лаштыкым кусараш',
'tooltip-ca-watch'                => 'Тиде лаштыкым тыйын эскерыме-влак лӱмерыш ешараш',
'tooltip-ca-unwatch'              => 'Тиде лаштыкым тыйын эскерыме-влак лӱмер гыч кораҥдаш',
'tooltip-search'                  => "Кычал {{SITENAME}}'ште",
'tooltip-search-go'               => 'Тиде лӱман лаштыкыш куснаш, тыгайже уло гын',
'tooltip-search-fulltext'         => 'Тиде текстан лаштык-влакым кычалаш',
'tooltip-p-logo'                  => 'Тӱҥ лаштык',
'tooltip-n-mainpage'              => 'Тӱҥ лаштыкыш куснаш',
'tooltip-n-portal'                => 'Проект нерген, мом тый ыштен кертат, мо кушто улеш',
'tooltip-n-currentevents'         => 'Кызытсе событий нерген увер',
'tooltip-n-recentchanges'         => 'Шукертсе огыл тӧрлатыме лаштык-влак лӱмер',
'tooltip-n-randompage'            => 'Вучыдымо (случайный) статьяш куснаш',
'tooltip-n-help'                  => 'Википедийым кучылтмо да тӧрлатыме шотышто полшык.',
'tooltip-t-whatlinkshere'         => 'Чыла лаштыкын лӱмерже кудо ты лаштыкыш куснат',
'tooltip-t-recentchangeslinked'   => 'Шукертсе огыл тӧрлымӧ лаштык-влак, кудо дене тиде лаштык кылдалтын',
'tooltip-feed-rss'                => 'Тиде лаштык лан RSS-кыл',
'tooltip-feed-atom'               => 'Тиде лаштык лан Atom-кыл',
'tooltip-t-contributions'         => 'Пайдаланышын надыр лӱмерым ончалаш',
'tooltip-t-emailuser'             => 'Тиде пайдаланышылан электрон серышым возаш',
'tooltip-t-upload'                => 'Файл-влакым пурташ',
'tooltip-t-specialpages'          => 'Лӱмын ыштыме лаштык-влак лӱмер',
'tooltip-t-print'                 => 'Тиде лаштыкым савыкташлан келыштарыме',
'tooltip-t-permalink'             => 'Тиде лаштык версийыш эре улшо ссылке',
'tooltip-ca-nstab-main'           => 'Лаштыкыште возымым ончыкташ',
'tooltip-ca-nstab-user'           => 'Пайдаланышын лаштыкшым ончалаш',
'tooltip-ca-nstab-special'        => 'Тиде лӱмын ыштыме лаштык, тудым тый тӧрлатен от керт',
'tooltip-ca-nstab-project'        => 'Проект нерген лаштыкым ончыкташ',
'tooltip-ca-nstab-image'          => 'Файлын лаштыкшым ончалаш',
'tooltip-ca-nstab-template'       => 'Ямдылыкым ончыкташ',
'tooltip-ca-nstab-category'       => 'Категорийын лаштыкым ончыкташ',
'tooltip-minoredit'               => 'Тиде тӧрлымым изирак семын палемдаш',
'tooltip-save'                    => 'Тыйын тӧрлатымашым аралаш',
'tooltip-preview'                 => 'Лаштыкым аралыме деч ончыч ончылгоч ончал!',
'tooltip-diff'                    => 'Ончыкташ, могай тӧрлатымашым тый ыштенат.',
'tooltip-compareselectedversions' => 'Кок ойырымо лаштык версийын ойыртемым ончалаш.',
'tooltip-watch'                   => 'Тиде лаштыкым тыйын эскерыме-влак лӱмерыш ешараш',
'tooltip-rollback'                => '"Пӧртылаш" ик темдалтыш дене пытартыш пайдаланышын тӧрлатымашым мӧҥгешла пӧртылеш',
'tooltip-undo'                    => '"Чараш" тиде тӧрлатымашым мӧҥгешла пӧртыла да ончылгоч ончымашым почеш.
Тый тӧрлатымаш амалже нерген возымо верыште  возын кертат.',

# Math options
'mw_math_png'    => 'Эре PNG-ым генерироватлаш',
'mw_math_simple' => 'Тыглай годым - HTML, уке гын - PNG',
'mw_math_html'   => 'Лиеш гын - HTML, уке гын - PNG',
'mw_math_source' => 'TeX-ым разметкыште кодаш (текст браузер-влаклан)',
'mw_math_modern' => 'Кызытлык (у) брузер-влаклан темлыме',
'mw_math_mathml' => 'Лиеш гын - MathML (эксперимент опций)',

# Browsing diffs
'previousdiff' => '← Ондакрак тӧрлатымаш',
'nextdiff'     => 'Вес тӧрлатымаш →',

# Media information
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|лаштык|лаштык}}',
'file-info-size'       => '($1 × $2 пиксел, файлын кугытшо: $3, MIME-тип: $4)',
'file-nohires'         => '<small>Кугурак чаплык уке.</small>',
'svg-long-desc'        => '(SVG файл, шкенжын кугытшо: $1 × $2 пиксел, файлын кугытшо: $3)',
'show-big-image'       => 'Шкенжын чаплыкше',
'show-big-image-thumb' => '<small>Ончылгоч ончымашын кугытшо $1 × $2 пиксель</small>',

# Special:NewFiles
'newimages-legend' => 'Фильтр',
'showhidebots'     => '(Бот-влакым $1 )',
'ilsubmit'         => 'Кычал',

# Bad image list
'bad_image_list' => 'Формат тыгай лийшаш:

Лӱмерын ужашыже-влак гына шотыш налалташ тӱналалтыт (* дене туҥалше корно-влак).
Корнышто икымше ссылке шӱкшӧ файлыш ссылке семын лийшаш.
Тиде корнышто вара лийше ссылке-влак лийын кертдыме семын ончалтыт: файлыш ссылкан лаштык-влак.',

# Metadata
'metadata'          => 'Метаданный-влак',
'metadata-help'     => 'Тиде файлыште ешартыш увер уло, кудыжым фотоаппарат але сканер дене ыштыме.
Файлым ыштыме деч вара тӧрлымӧ гын, южо данныйже тиде файллан келшыдыме лийын кертеш.',
'metadata-expand'   => 'Ончыкташ тӱткынрак палым',
'metadata-collapse' => 'Шылташ тӱткынрак палым',
'metadata-fields'   => 'Тиде лӱмер гыч EXIF кумдыкпале пасу сӱретын лаштыкыште эре ончыкталтеш, посна каласыме огыл дык, вес пасу ок ончыкталт.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'Файлым ӧрдыж программыште тӧрлаташ',
'edit-externally-help' => '(Сайрак палашлан ончал [http://www.mediawiki.org/wiki/Manual:External_editors шындымаш нерген туныктымашым])',

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
'watchlisttools-edit' => 'Эскерыме лӱмерым ончалаш да тӧрлаташ',
'watchlisttools-raw'  => 'Эскерыме лӱмерым текст семын тӧрлаш',

# Special:Version
'version-specialpages' => 'Лӱмын ыштыме лаштык-влак',

# Special:FilePath
'filepath-page' => 'Файл:',

# Special:SpecialPages
'specialpages'             => 'Лӱмын ыштыме лаштык-влак',
'specialpages-group-login' => 'Пурымаш / регистрацийым эрташ',

);
