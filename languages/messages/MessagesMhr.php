<?php
/** Eastern Mari (Олык Марий)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amdf
 * @author Azim
 * @author Jose77
 * @author Сай
 */

$fallback = 'ru';

$messages = array(
# User preference toggles
'tog-underline'             => 'Кузе кылвер-влакым ӱлычын удыралаш?',
'tog-highlightbroken'       => 'Лийдыме кылвер-влакым <a href="" class="new">тидын гай</a> ышташ (уке гын, тиде семын<a href="" class="internal">?</a>).',
'tog-justify'               => 'Абзацым лопкыт дене тӧрлаш',
'tog-hideminor'             => 'Пытартыш тӧрлатымаш-влак лӱмер гыч изирак тӧрлатымаш-влакым ончыкташ огыл',
'tog-hidepatrolled'         => 'Тергыме тӧрлатымаш-влакым пытартыш тӧрлатымаш лӱмерыште шылташ',
'tog-newpageshidepatrolled' => 'Тергыме лаштык-влакым у лаштык лӱмерыште шылташ',
'tog-extendwatchlist'       => 'Чыла вашталтышым, а пытартыш гына огылым ончыкташлан эскерыме лӱмерым кугемдаш',
'tog-usenewrc'              => 'У тӧрлатымаш саемдыме лӱмерым кучылташ (JavaScript кӱлеш)',
'tog-numberheadings'        => 'Вуймутым автоматик йӧн дене радамлаш',
'tog-showtoolbar'           => 'Тӧрлатымаш ӱзгараҥам ончыкташ (JavaScript кӱлеш)',
'tog-showtoc'               => 'Вуймут радамым ончыкташ (3 деч шуко вуймутан лаштык-влаклан)',
'tog-rememberpassword'      => 'Тиде компучырышто мыйын шолыпмутым шарнаш',
'tog-watchcreations'        => 'Мыйын ыштыме лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-watchdefault'          => 'Мыйын тӧрлатыме лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-watchmoves'            => 'Мыйын лӱмым вашталтыме лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-watchdeletion'         => 'Мыйын шӧрымӧ лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-minordefault'          => 'Посна каласыме огыл дык, чыла тӧрлатымашым изи тӧрлатымаш гай палемдаш',
'tog-previewontop'          => 'Тӧрлатымаш тӧрза деч ончыч ончылгоч ончымашым шындаш',
'tog-previewonfirst'        => 'Ончылгоч ончымашым икымше тӧрлатымаш годым ончыкташ',
'tog-nocache'               => 'Лаштыкым кешироватлымым чараш',
'tog-enotifwatchlistpages'  => 'Мыйын эскерыме лӱмер гыч лаштыкыште тӧрлатымыш нерген электрон почто гоч шижтараш',
'tog-enotifusertalkpages'   => 'Мыйын каҥашымаш лаштыкыште тӧрлатымыш нерген электрон почто гоч шижтараш',
'tog-showjumplinks'         => '"Куснаш …" ешартыш кылверым чӱкташ',
'tog-watchlisthideown'      => 'Эскерыме лӱмер гыч мыйын тӧрлатымаш-влакым ончыкташ огыл',
'tog-watchlisthidebots'     => 'Эскерыме лӱмер гыч бот-влакын тӧрлатымым ончыкташ огыл',
'tog-watchlisthideminor'    => 'Эскерыме лӱмер гыч изирак тӧрлатымаш-влакым ончыкташ огыл',
'tog-ccmeonemails'          => 'Моло ушнышо-влаклан колтымо серышын копийжым мыламат колташ',
'tog-diffonly'              => 'Кок версийым таҥастарыме годым лаштыкыште возымым ончыкташ огыл',
'tog-showhiddencats'        => 'Шылтыме категорийым ончыкташ',

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
'march'         => 'Ӱярня',
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
'march-gen'     => 'Ӱярня',
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
'mar'           => 'Ӱярня',
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
'pagecategories'           => '{{PLURAL:$1|Категорий|Категорий}}',
'category_header'          => '"$1" категорийыште лаштык-влак',
'subcategories'            => 'Ӱлылкатегорий-влак',
'hidden-categories'        => '{{PLURAL:$1|Шылтыме категорий|Шылтыме категорий-влак}}',
'hidden-category-category' => 'Шылтымо категорий-влак', # Name of the category where hidden categories will be listed
'category-subcat-count'    => '{{PLURAL:$2|Тиде категорийыш ик ӱлылкатегорий гына пура.|{{PLURAL:$1|Тыгай $1 ӱлылкатегорий|Тыгане $1 ӱлылкатегорий-влак}} тиде категорийыште, чыла $2.}}',
'category-article-count'   => '{{PLURAL:$2|Тиде категорийыш ик лаштык гына пура.|{{PLURAL:$1|Тыгай $1 лаштык|Тыгане $1 лаштык-влак}} тиде категорийыште, чыла $2.}}',
'listingcontinuesabbrev'   => '(умбакыжым)',

'mainpagetext' => "'''MediaWiki сай шындыме.'''",

'about'          => 'Нерген',
'article'        => 'Возымо лаштык',
'newwindow'      => '(у тӧрзаште почылтеш)',
'cancel'         => 'Чараш',
'qbfind'         => 'Муаш',
'qbedit'         => 'Тӧрлаташ',
'qbpageoptions'  => 'Тиде лаштык',
'qbmyoptions'    => 'Мыйын лаштык-влак',
'qbspecialpages' => 'Лӱмын ыштыме лаштык-влак',
'moredotdotdot'  => 'Рашрак...',
'mypage'         => 'Мыйын лаштык',
'mytalk'         => 'Мыйын каҥашымаш',
'anontalk'       => 'Каҥашымаш тиде IP нерген',
'navigation'     => 'Корнылаш',

'errorpagetitle'   => 'Йоҥылыш',
'returnto'         => '$1 деке пӧртылаш.',
'tagline'          => '{{SITENAME}} гыч',
'help'             => 'Полшык',
'search'           => 'Кычалмаш',
'searchbutton'     => 'Кычалаш',
'go'               => 'Куснаш',
'searcharticle'    => 'Куснаш',
'history'          => 'Лаштыкын эртымгорно',
'history_short'    => 'Эртымгорно',
'printableversion' => 'Савыкташлан келыштарыме',
'permalink'        => 'Эре улшо кылвер',
'print'            => 'Савык',
'edit'             => 'Тӧрлаташ',
'create'           => 'Ышташ',
'editthispage'     => 'Тӧрлаташ тиде лаштыкым',
'create-this-page' => 'Тиде лаштыкым ышташ',
'delete'           => 'Шӧраш',
'deletethispage'   => 'Тиде лаштыкым шӧраш',
'protect'          => 'Аралаш',
'protect_change'   => 'вашталташ',
'protectthispage'  => 'Тиде  лаштыкым тӧрлатымаш деч аралаш',
'newpage'          => 'У лаштык',
'talkpage'         => 'Тиде лаштыкым каҥашаш',
'talkpagelinktext' => 'Каҥашымаш',
'specialpage'      => 'Лӱмын ыштыме лаштык',
'personaltools'    => 'Шке ӱзгар-влак',
'postcomment'      => 'У ужаш',
'articlepage'      => 'Лаштыкыште возымым ончыкташ',
'talk'             => 'Каҥашымаш',
'views'            => 'Ончымаш-влак',
'toolbox'          => 'Ӱзгар-влак',
'userpage'         => 'Пайдаланышын лаштыкым ончалаш',
'imagepage'        => 'Файлын лаштыкым ончалаш',
'templatepage'     => 'Ямдылыкын лаштыкым ончалаш',
'viewhelppage'     => 'Полшык лаштыкым ончалаш',
'categorypage'     => 'Категорийын лаштыкым ончалаш',
'viewtalkpage'     => 'Ончалаш каҥашымашым',
'otherlanguages'   => 'Вес йылме дене',
'redirectedfrom'   => '(Колтымо $1 гыч)',
'redirectpagesub'  => 'Вес вереш колтышо лаштык',
'lastmodifiedat'   => 'Тиде лаштыкым пытартыш гана $2 $1 тӧрлымӧ.', # $1 date, $2 time
'protectedpage'    => 'Тӧрлатымаш деч аралыме лаштык',
'jumpto'           => 'Куснаш:',
'jumptonavigation' => 'корнылашыш',
'jumptosearch'     => 'кычалмашке',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} нерген',
'aboutpage'            => 'Project:Нерген',
'copyright'            => 'Лаштыкыште возымо $1 йӧн дене почмо.',
'copyrightpage'        => '{{ns:project}}:Автор кертыж',
'currentevents'        => 'Кызытсе событий',
'disclaimers'          => 'Мут кучымаш деч кораҥмаш',
'disclaimerpage'       => 'Project:Мут кучымаш деч кораҥмаш',
'edithelp'             => 'Тӧрлатымаште полыш',
'edithelppage'         => 'Help:Тӧрлымаш',
'helppage'             => 'Help:Полшык',
'mainpage'             => 'Тӱҥ лаштык',
'mainpage-description' => 'Тӱҥ лаштык',
'portal'               => 'Тӱшка',
'privacy'              => 'Конфиденциальность политике',
'privacypage'          => 'Project:Конфиденциальность политике',

'badaccess' => 'Пурымаште йоҥылыш',

'ok'                  => 'Йӧра',
'retrievedfrom'       => 'Налме вер — "$1"',
'youhavenewmessages'  => 'Тендан $1 уло ($2).',
'newmessageslink'     => 'у увертыш-влак',
'newmessagesdifflink' => 'пытартыш тӧрлатымаш',
'editsection'         => 'тӧрлаташ',
'editold'             => 'тӧрлаташ',
'viewsourceold'       => 'тӱҥалтыш текстым ончалаш',
'editlink'            => 'тӧрлаташ',
'viewsourcelink'      => 'тӱҥалтыш текстым ончалаш',
'editsectionhint'     => 'Тӧрлаташ ужашым: $1',
'toc'                 => 'Вуйлымаш',
'showtoc'             => 'ончыкташ',
'hidetoc'             => 'шылташ',
'viewdeleted'         => 'Ончалаш $1?',
'site-rss-feed'       => '$1 RSS-кыл',
'site-atom-feed'      => '$1 Atom-кыл',
'page-rss-feed'       => '"$1" RSS-кыл',
'page-atom-feed'      => '"$1" Atom-кыл',
'red-link-title'      => '$1 (тыгай лаштык уке)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Лаштык',
'nstab-user'      => 'Пайдаланышын лаштыкше',
'nstab-media'     => 'Мультимедиа',
'nstab-special'   => 'Лӱмын ыштыме лаштык',
'nstab-project'   => 'Проект нерген',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Увертыш',
'nstab-template'  => 'Ямдылык',
'nstab-help'      => 'Полыш лаштык',
'nstab-category'  => 'Категорий',

# Main script and global functions
'nosuchspecialpage' => 'Тигай лӱмын-ыштыме лаштык уке',

# General errors
'error'              => 'Йоҥылыш',
'missing-article'    => 'Пале кумдыкыште лаштыкын йодмо текстым муымо огыл (кудым муаш кӱлеш), "$1" $2.

Тыге лиеш, кунам тоштемше кылвер дене шӧрымӧ лаштыкын вашталтымаш эртымгорнышкыже куснаш толашыме годым.

Йоҥлыш тыште уке гын, очыни, паша программыште.
Тидын нерген URL-ым ончыктен [[Special:ListUsers/sysop|сайтвиктарышым]] шижтаре.',
'missingarticle-rev' => '(тӱрлык#: $1)',
'internalerror'      => 'Кӧргысӧ йоҥылыш',
'internalerror_info' => 'Кӧргысӧ йоҥылыш: $1',
'filecopyerror'      => '«$1» гыч «$2» файлыш копийым ышташ лийдыме.',
'fileexistserror'    => '«$1» файлыш возыкым ышташ лийдыме: файл уло.',
'unexpected'         => 'Келшыдыме кугыт: «$1»=«$2».',
'badtitle'           => 'Сай огыл лӱм',
'badtitletext'       => 'Йодмо лаштыкын лӱмжӧ йоҥылыш, але яра, але йылме кокла але интер-вики лӱмжӧ йоҥылыш. Ала лӱмыштӧ кӱлдымӧ тамга улыт.',
'viewsource'         => 'Тӱҥалтыш текст',
'viewsourcefor'      => '$1 лан',

# Virus scanner
'virus-badscanner'     => "Келыштарымаш йоҥылыш: палыдыме вирус сканер: ''$1''",
'virus-unknownscanner' => 'палыдыме антивирус:',

# Login and logout pages
'yourname'                => 'Пайдаланышын лӱмжӧ:',
'yourpassword'            => 'Шолыпмут:',
'yourpasswordagain'       => 'Шолыпмутым угыч пуртымаш:',
'remembermypassword'      => 'Тиде компучырышто мыйын шолыпмутым шарнаш',
'yourdomainname'          => 'Тендан домен:',
'login'                   => 'Шке денет палымым ыште',
'nav-login-createaccount' => 'Шке денет палымым ыште/Регистрацийым эрте',
'loginprompt'             => '{{SITENAME}} тый денет палыме лиймашлан, cookies чӱкталтын улшаш.',
'userlogin'               => 'Шке денет палымым ыште/Регистрацийым эрте',
'logout'                  => 'Лекташ',
'userlogout'              => 'Лекташ',
'nologin'                 => "Тый регистрацийым эше эртен отыл? '''$1'''.",
'nologinlink'             => 'Регистрацийым эрте',
'createaccount'           => 'Регистрацийым эрте',
'gotaccount'              => "Тый регистрацийым эртенат? '''$1'''.",
'gotaccountlink'          => 'Шке денет палымым ыште',
'createaccountmail'       => 'e-mail дене',
'youremail'               => 'Электрон почто:',
'username'                => 'Пайдаланышын лӱмжӧ:',
'uid'                     => 'Пайдаланышын ID-же:',
'prefs-memberingroups'    => '{{PLURAL:$1|Тӱшкаште шогышо|Тӱшка-влакыште шогышо}}:',
'yourrealname'            => 'Чын лӱмжӧ:',
'yourlanguage'            => 'Йылме:',
'yournick'                => 'Кидпале:',
'gender-male'             => 'Пӧръеҥ',
'gender-female'           => 'Ӱдырамаш',
'email'                   => 'Электрон почто',
'prefs-help-email'        => 'Электрон почтын адресшым ончыктыде кертат, адакшым тудо моло ушнышо-влаклан тыйын лаштык гоч тый денет кылым кучаш йӧным ышта, тыгодымак нунылан палыдыме кодеш.',
'nosuchuser'              => '"$1" лӱман пайдаланыше уке.
Пайдаланышын лӱмыштӧ йӱкпале-влакын кугытшо тӱрыс лийшаш.
Лӱмым чын возымым терге але [[Special:UserLogin/signup|регистрацийым эрте]].',
'nosuchusershort'         => '"<nowiki>$1</nowiki>" лӱман пайдаланыше уке.
Лӱмым чын возымым терге.',
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
'emailauthenticated'      => 'Тыйын почто адресетым пеҥгыдемдыме $2, $3.',
'loginlanguagelabel'      => 'Йылме: $1',

# Password reset dialog
'resetpass'                 => 'Шолыпмутым вашталташ',
'oldpassword'               => 'Тошто шолыпмут:',
'newpassword'               => 'У шолыпмут:',
'retypenew'                 => 'Пеҥгыдемдыза у шолыпмутым:',
'resetpass-submit-loggedin' => 'Шолыпмутым вашталташ',

# Edit page toolbar
'bold_sample'     => 'Кӱжгӧ текст',
'bold_tip'        => 'Кӱжгӧ текст',
'italic_sample'   => 'Шӧрын текст',
'italic_tip'      => 'Шӧрын текст',
'link_sample'     => 'Кылверын лӱмжӧ',
'link_tip'        => 'Кӧргысӧ кылвер',
'extlink_sample'  => 'http://www.example.com кылверын лӱмжӧ',
'extlink_tip'     => 'Ӧрдыж кылвер (http:// префиксым ит мондо)',
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
'anoneditwarning'                  => "'''Тӱткӧ лий:''': Тый шкенетым палымым ыштен отыл. Тыйын IP-адресет лаштыкын вашталтымаш эртымгорныштыжо возалтен кодеш.",
'summary-preview'                  => 'Тӧрлатымаш нерген ончылгоч ончымаш:',
'accmailtitle'                     => 'Шолыпмут колтымо.',
'newarticle'                       => '(У)',
'newarticletext'                   => "Тый кылвер почеш уке улшо лаштыкыш кусненат.
Лаштыкым ышташлан ӱлнӧ возаш тӱҥал (сайрак палашлан [[{{MediaWiki:Helppage}}|полшыкым]] ончал).
Тый тышке йонгылыш логалынат гын, браузерыште '''шенгек''' полдышым темдал.",
'noarticletext'                    => 'Кызытсе жаплан тиде лаштыкыште нимом возымо огыл.
Тый тиде лаштыкын лӱмжым вес лаштык-влакыште [[Special:Search/{{PAGENAME}}|кычалын]] кертат, але <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} журнал-влакыште кычалын] кертат, але [{{fullurl:{{FULLPAGENAME}}|action=edit}} тыгай лӱман лаштыкым ышташ] кертат</span>.',
'clearyourcache'                   => "'''Ешартыш''': Аралыме деч вара вашталтышым ужаш браузеретын кэшыжым эрыкташ логалын кертеш. '''Mozilla / Firefox / Safari:''' ''Shift''-ым темдал кучен ''Reload''-ым темдал але ''Ctrl-F5'' але ''Ctrl-R'' темдал (Macintosh-влак ''Command-R''); '''Konqueror:''' темдал ''Reload'' полдышым але ''F5'' темдал; '''Opera:''' ''Tools→Preferences''-ыште кэшым эрыкте; '''Internet Explorer:''' ''Ctrl''-ым темдал кучен ''Refresh''-ым темдал але ''Ctrl-F5'' темдал.",
'previewnote'                      => "'''Тиде ончылгоч ончымаш гына;
вашталтыш-влакым эше аралыме огыл!'''",
'editing'                          => 'Тӧрлаталтеш $1',
'editingsection'                   => 'Тӧрлаталтеш $1 (ужаш)',
'yourtext'                         => 'Тендан текст',
'yourdiff'                         => 'Ойыртем',
'copyrightwarning'                 => "Шотыш нал, чыла пашам {{SITENAME}} проектыш $2 лицензий почеш лукмо семын шотлыман($1 ончал). 
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
'viewpagelogs'           => 'Тиде лаштыклан журнал-влакым ончыкташ',
'currentrev'             => 'Кызытсе тӱрлык',
'currentrev-asof'        => '$1 кечын кызытсе тӱрлык',
'revisionasof'           => '$1 кечын тӱрлык',
'previousrevision'       => '← Ончычсо тӱрлык',
'nextrevision'           => 'Вес тӱрлык →',
'currentrevisionlink'    => 'Кызытсе тӱрлык',
'cur'                    => 'кызыт',
'last'                   => 'ончычсо',
'page_first'             => 'икымше',
'page_last'              => 'пытартыш',
'histlegend'             => "Таҥастарашлаш ӱлнӧ версийым ойырымо полдышым але Enter-ым темдал.<br />
Умылтарымаш: (кызыт) = кызытсе версий деч ойыртем, (ончычсо) = ончычсо версий деч ойыртем, '''и''' = изирак тӧрлатыме.",
'history-fieldset-title' => 'Эртымгорным ончыкташ',
'histfirst'              => 'Эн тошто',
'histlast'               => 'Эн у',
'historyempty'           => '(яра)',

# Revision deletion
'rev-delundel'         => 'ончыкташ/шылташ',
'revdelete-hide-user'  => 'Тӧрлатышын лӱмжым шылташ',
'revdelete-hide-image' => 'Файл кӧргым шылташ',
'revdel-restore'       => 'Койымашым вашталташ',
'pagehist'             => 'Лаштыкын эртымгорно',
'deletedhist'          => 'Шӧрымо эртымгорно',
'revdelete-content'    => 'кӧргӧ',
'revdelete-summary'    => 'тӧрлатымаш-влакым возен ончыктымаш',
'revdelete-uname'      => 'Пайдаланышын лӱмжӧ',
'revdelete-hid'        => '$1 шылтыме',

# History merging
'mergehistory-reason' => 'Амал:',

# Merge log
'revertmerge' => 'Ойыраш',

# Diffs
'history-title'           => '$1лан тӱрлык эртымгорно',
'difference'              => '(Тӱрлык-влакын ойыртемышт)',
'lineno'                  => '$1 корно:',
'compareselectedversions' => 'Ойырымо версий-влакым таҥастараш',
'editundo'                => 'чараш',

# Search results
'searchresults'                  => 'Кычалын мумо',
'searchresults-title'            => '«$1»лан кычалын мумо',
'searchresulttext'               => "{{SITENAME}}'ыште кычалмаш нерген шукырак палнеда гын, [[{{MediaWiki:Helppage}}|полышым]] ончыза.",
'searchsubtitle'                 => 'Тые кычалыч \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|чыла лаштык-влакым, кудыжо тӱҥалыт: "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|чыла лаштык-влакым, "$1" дене кылым палемдат]])',
'searchsubtitleinvalid'          => "Тый кычалыч '''$1'''",
'noexactmatch'                   => "'''\"\$1\" лӱман лаштык уке.'''
Тый [[:\$1|тиде лаштыкым ыштен]] кертат.",
'noexactmatch-nocreate'          => "'''\"\$1\" лӱман лаштык уке.'''",
'notitlematches'                 => 'Лаштык-влакын лӱмыштышт икгайлык уке',
'notextmatches'                  => 'Лаштык-влакыште икгайлык возымо уке',
'prevn'                          => 'кодшо $1',
'nextn'                          => 'весе $1',
'viewprevnext'                   => 'Ончал ($1) ($2) ($3)',
'searchmenu-new'                 => "'''Тиде вики-проектыште «[[:$1]]» лӱман лаштыкым ышташ!'''",
'searchhelp-url'                 => 'Help:Вуйлымаш',
'searchprofile-everything'       => 'Чыла',
'searchprofile-articles-tooltip' => 'Кычалмаш $1ште',
'searchprofile-project-tooltip'  => 'Кычалмаш $1ште',
'searchprofile-images-tooltip'   => 'Файл-влакым кычалмаш',
'search-result-size'             => '$1 ({{PLURAL:$2|$2 мут|$2 мут-влак}})',
'search-redirect'                => '($1 вес вере колтымаш)',
'search-section'                 => '(ужаш $1)',
'search-suggest'                 => 'Але те $1 возынеда ыле',
'search-interwiki-caption'       => 'Родо проект-влак',
'search-interwiki-default'       => "$1'ште мумо:",
'search-interwiki-more'          => '(эше)',
'search-mwsuggest-enabled'       => 'темлымаш дене',
'search-mwsuggest-disabled'      => 'темлымаш деч посна',
'searchall'                      => 'чыла',
'showingresultstotal'            => "Ӱлнӧ '''$3''' кычалын мумо гыч {{PLURAL:$4|'''$1'''|'''$1 — $2'''}} ончыктымо",
'nonefound'                      => "'''Ешартыш''':  Посна каласыме огыл дык, кычалмаш южо лӱм-влакын кумдыкышто эрта. Уло лӱм-влакын кумдыкышто кычалашлан(чӱктен каҥашымаш лаштык-влакым, ямдылык-влакым и туге молат) шке йодмашыштет ''all:'' префиксым кучылт, але кӱлешан лӱм-влакын кумдыкым ончыкто.",
'search-nonefound'               => 'Тыйын йодышлан нимом кычален мумо уке.',
'powersearch'                    => 'Сайынрак кычал',
'powersearch-legend'             => 'Сайынрак кычалаш',
'powersearch-ns'                 => 'Кычалаш тиде лӱм-влакын кумдыкышт-влакыште:',
'powersearch-redir'              => 'Вес верек колтымо лаштык-влакым ончыкташ',
'powersearch-field'              => 'Кычалаш',

# Preferences page
'preferences'               => 'Келыштарымаш',
'mypreferences'             => 'Келыштарымаш',
'prefs-edits'               => 'Тӧрлатымаш чот:',
'changepassword'            => 'Шолыпмутым вашталташ',
'skin'                      => 'Сӧрастарыме йӧн',
'skin-preview'              => 'Ончылгоч ончымаш',
'math'                      => 'Формуло-влак',
'prefs-personal'            => 'Пайдаланышын профильже',
'prefs-rc'                  => 'Шукертсе огыл тӧрлымаш-влак',
'prefs-watchlist'           => 'Эскерымаш лӱмер',
'prefs-watchlist-days'      => 'Мыняр кече эскерымаш лӱмерыште ончыкталтеш?',
'prefs-watchlist-edits'     => 'Мыняр тӧрлатымашым ышташ лиймым кугемдыме эскерымаш лӱмерыште ончыктымо?',
'prefs-misc'                => 'Тӱрлӧ',
'prefs-resetpass'           => 'Шолыпмутым вашталташ',
'saveprefs'                 => 'Аралаш',
'resetprefs'                => 'Тӧрлатымым шотыш налаш огыл',
'restoreprefs'              => 'Чыла ойлыде келыштарымашым пӧртылаш',
'searchresultshead'         => 'Кычалме',
'savedprefs'                => 'Тыйын келыштарымашым аралыме.',
'timezonelegend'            => 'Шагат ÿштö:',
'localtime'                 => 'Верысе жап:',
'timezoneregion-africa'     => 'Африка',
'timezoneregion-america'    => 'Америка',
'timezoneregion-antarctica' => 'Антарктика',
'timezoneregion-arctic'     => 'Арктика',
'timezoneregion-asia'       => 'Азий',
'timezoneregion-atlantic'   => 'Атлантик таптеҥыз',
'timezoneregion-australia'  => 'Австралий',
'timezoneregion-europe'     => 'Европо',
'timezoneregion-indian'     => 'Индий таптеҥыз',
'allowemail'                => 'Вес ушнышо-влак деч электрон почтым налаш кӧнаш',
'prefs-searchoptions'       => 'Кычалмаш',
'prefs-namespaces'          => 'Лӱм-влакын кумдыкышт-влак',
'default'                   => 'ойлыде',

# Groups
'group-bot'   => 'Бот-влак',
'group-sysop' => 'Сайтвиктарыше-влак',
'group-all'   => '(чыла)',

'group-bot-member' => 'бот',

'grouppage-bot'   => '{{ns:project}}:Бот-влак',
'grouppage-sysop' => '{{ns:project}}:Сайтвиктарыше-влак',

# User rights log
'rightslog' => 'Пайдаланышын кертыж нерген журнал',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'тиде лаштыкым тӧрлаташ',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|тӧрлатымаш|тӧрлатымаш-влак}}',
'recentchanges'                  => 'Пытартыш тӧрлатымаш-влак',
'recentchanges-legend'           => 'Пытартыш тӧрлатымаш-влакын келыштарымашышт',
'recentchangestext'              => 'Тиде лаштыкыште пытартыш тӧрлатымашым шекланаш.',
'recentchanges-feed-description' => 'Тиде кылыште пытартыш тӧрлатымашым шекланаш.',
'rcnote'                         => "Ӱлнӧ {{PLURAL:$1|'''1'''|'''$1'''}} вашталтыш пытартыш {{PLURAL:$2||'''$2'''}} кечылан, $5-лан, $4-лан.",
'rclistfrom'                     => '$1 гыч тӱҥалын у вашталтымашым ончыкташ',
'rcshowhideminor'                => 'Изирак тӧрлымым $1',
'rcshowhidebots'                 => 'Бот-влакым $1',
'rcshowhideliu'                  => 'Шолып пайдаланыше-влакым $1',
'rcshowhideanons'                => 'Ончыкталтше пайдаланыше-влакым $1',
'rcshowhidemine'                 => 'Мыйын тӧрлымым $1',
'rclinks'                        => 'Пытартыш $2 кечылан $1 вашталтымашым ончыкташ<br />$3',
'diff'                           => 'ойырт.',
'hist'                           => 'эрт.',
'hide'                           => 'шылташ',
'show'                           => 'ончыкташ',
'minoreditletter'                => 'и',
'newpageletter'                  => 'У',
'boteditletter'                  => 'б',
'rc-enhanced-expand'             => 'Тӱткынракым ончыкташ (JavaScript кӱлеш)',
'rc-enhanced-hide'               => 'Рашлык-влакым шылташ',

# Recent changes linked
'recentchangeslinked'          => 'Ваш кылдалтше тӧрлатымаш-влак',
'recentchangeslinked-title'    => '"$1" лаштыклан кылдалтше тӧрлатымаш-влак',
'recentchangeslinked-noresult' => 'Ончыктымо пагытыште кылдалтше лаштыклаште вашталтыш лийын огыл.',
'recentchangeslinked-summary'  => "Тиде шукертсе огыл тӧрлымӧ лаштык-влакын, кудыжо палемдыме лаштык дене кылдалтыныт (ала пелемдыме категорийыш пурат) лӱмерже.
[[Special:Watchlist|Тыйын эскерымаш лӱмерыш]] пурышо лаштык-влакым '''кӱжгӱн''' палемдыме.",
'recentchangeslinked-page'     => 'Лаштыкын лӱмжӧ:',
'recentchangeslinked-to'       => 'Тиде лаштык дене кылдалтше лаштык-влакыште тӧрлатымашым ончыкташ тидын олмеш',

# Upload
'upload'            => 'Файлым опташ',
'uploadbtn'         => 'Файлым пурташ',
'uploadlogpage'     => 'Оптымаш-влак журнал',
'filedesc'          => 'Файл нерген кӱчыкын увертараш',
'fileuploadsummary' => 'Тидын нерген кӱчыкын:',
'uploadedimage'     => '«[[$1]]» пуртыш',
'watchthisupload'   => 'Тиде файлым эскераш',

# Special:ListFiles
'imgfile'        => 'файл',
'listfiles_user' => 'Пайдаланыше',

# File description page
'filehist'                  => 'Файлын эртымгорно',
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
'imagelinks'                => 'Файл деке кылвер-влак',
'linkstoimage'              => 'Тиде {{PLURAL:$1|$1 лаштык саде файл дене кылдалтын|$1 лаштык-влак саде файл дене кылдалтыныт}}:',
'nolinkstoimage'            => 'Тиде файл дене кылдалтше ик лаштыкат уке.',
'sharedupload'              => 'Тиде файлын верже: $1, туге гынат, тудым моло веренат кучылташ лиеш.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki-desc'     => 'Ӱлнӧ $1 возымо ончыктымо.',
'shareduploadwiki-linktext' => 'файлын умылтартыш лаштыкыште',
'uploadnewversion-linktext' => 'Тиде файлын у тӱрлыкшым пурташ',

# File deletion
'filedelete-comment'          => 'Шӧрымын амалже:',
'filedelete-submit'           => 'Шӧраш',
'filedelete-otherreason'      => 'Вес/ешартыш амал:',
'filedelete-reason-otherlist' => 'Вес амал',

# List redirects
'listredirects' => 'Вес верек колтымаш-влак лӱмер',

# Random page
'randompage' => 'Вучыдымо лаштык',

# Statistics
'statistics'                   => 'Иктешлымаш',
'statistics-header-pages'      => 'Лаштык коклам иктешлымаш',
'statistics-header-edits'      => 'Тӧрлатымаш коклам иктешлымаш',
'statistics-header-views'      => 'Ончымаш коклам иктешлымаш',
'statistics-header-users'      => 'Пайдаланыше коклам иктешлымаш',
'statistics-articles'          => 'Возымо лаштык-влак',
'statistics-pages'             => 'Лаштык-влак',
'statistics-pages-desc'        => 'Чыла лаштык-влак, чӱктен каҥашымаш лаштык-влакым, вес верек колтымо лаштык-влакым и туге молат',
'statistics-files'             => 'Пуртымо файл-влак',
'statistics-edits'             => '{{SITENAME}} шындымеке тӧрлымӧ чот',
'statistics-edits-average'     => 'Ик лаштыкым покшел тӧрлымӧ чот',
'statistics-views-total'       => 'Чылажге ончымо',
'statistics-views-peredit'     => 'Ик тӧрлатымашлан ончымо',
'statistics-jobqueue'          => '[http://www.mediawiki.org/wiki/Manual:Job_queue Паша черетын] кужытшо',
'statistics-users'             => 'Регистрацийым эртыше [[Special:ListUsers|пайдаланыше-влак]]',
'statistics-users-active'      => 'Чӱчкыдын пайдаланыше-влак',
'statistics-users-active-desc' => 'Пытартыш {{PLURAL:$1|кечыште|$1 кечыште}} иктаж-мом ыштыше пайаланыше-влак',
'statistics-mostpopular'       => 'Эн чӱчкыдын ончымо лаштык-влак',

'brokenredirects'        => 'Пудыртымо вес верек колтымаш-влак',
'brokenredirects-delete' => '(шӧраш)',

'withoutinterwiki-submit' => 'ончыкташ',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|байт|байт}}',
'nmembers'          => '$1 {{PLURAL:$1|лаштык|лаштык-влак}}',
'nviews'            => '$1 {{PLURAL:$1|ончымо|ончымо-влак}}',
'lonelypages'       => 'Тулык лаштык-влак',
'wantedcategories'  => 'Ыштыман категорий-влак',
'wantedpages'       => 'Ыштыман лаштык-влак',
'wantedfiles'       => 'Ыштыман файл-влак',
'wantedtemplates'   => 'Ыштыман ямдылык-влак',
'prefixindex'       => 'Чыла лаштык-влак префикс дене',
'shortpages'        => 'Кӱчык лаштык-влак',
'longpages'         => 'Кужу лаштык-влак',
'protectedpages'    => 'Тӧрлатымаш деч аралыме лаштык-влак',
'newpages'          => 'У лаштык-влак',
'newpages-username' => 'Пайдаланышын лӱмжӧ:',
'move'              => 'Кусараш',
'movethispage'      => 'Тиде лаштыкым кусараш',
'pager-newer-n'     => '{{PLURAL:$1|вес|вес}}',
'pager-older-n'     => '{{PLURAL:$1|ончычсо|ончычсо}}',

# Book sources
'booksources'               => 'Негызым пыштыше кнага-влак',
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
'allpagessubmit' => 'Кычалаш',

# Special:Categories
'categories' => 'Категорий-влак',

# Special:LinkSearch
'linksearch'    => 'Ӧрдыж кылвер-влак',
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
Тиде лаштыкын да тудын каҥашымаш лаштыкым умбакысе тӧрлатымашым тиде спискыште ончыктымо лиеш да, сайрак ужаш манын, [[Special:RecentChanges|пытартыш тӧрлатымаш лӱмерыште]] '''кӱжгӧ шрифт''' дене ойырымо.",
'removedwatch'      => 'Эскерымаш лӱмер гыч шӧрымӧ',
'removedwatchtext'  => '«[[:$1]]» лаштыкым [[Special:Watchlist|тыйын эскерыме лӱмер]] гыч кораҥдыме.',
'watch'             => 'Эскераш',
'watchthispage'     => 'Тиде лаштыкым эскераш',
'unwatch'           => 'Эскерыман огыл',
'unwatchthispage'   => 'Эскерымым чарнаш',
'watchlist-details' => 'Тыйын эскерымаш лӱмерыште $1 {{PLURAL:$1|лаштык|лаштык-влак}}, каҥашымаш лаштык-влакым шотлыде.',
'watchlistcontains' => 'Тыйын лӱмерыште $1 {{PLURAL:$1|лаштык|лаштык}}.',
'wlshowlast'        => 'Пытартыш $1 шагат $2 кечылан $3 ончыкташ',
'watchlist-options' => 'Эскерыме лӱмерын келыштарымаш',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Эскерымаш лӱмерыш ешарымаш...',
'unwatching' => 'Эскерымаш лӱмер гыч шӧрымаш...',

'enotif_newpagetext' => 'Тиде у лаштык.',

# Delete
'deletepage'            => 'Лаштыкым шӧраш',
'delete-confirm'        => 'Шӧраш "$1"',
'delete-legend'         => 'Шӧраш',
'historywarning'        => 'Тӱтко лий: шӧраш шонымо лаштыкет вашталтыш-влак нерген историйым нумалеш:',
'confirmdeletetext'     => 'Тый тиде лаштыкым пӱтынь эртымгорныже дене йӧршын шӧрынет.
Пеҥгыдемде тидым [[{{MediaWiki:Policy-url}}|правил]] почеш ыштыметым да, мо тидын деч вара лиймым, умылыметым.',
'actioncomplete'        => 'Ыштыме',
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
'protectcomment'              => 'Амал:',
'protectexpiry'               => 'Мучашлалтеш:',
'protect_expiry_invalid'      => 'Йоҥылыш мучашлалтше жап.',
'protect_expiry_old'          => 'Мучашлалтше жап эртен.',
'protect-unchain'             => 'Лаштык кусараш кертме йӧным ышташ',
'protect-text'                => "Тыште тый '''<nowiki>$1</nowiki>''' лаштыкын шыгыремдымашыжым ончалаш да тӧрлаташ кертат.",
'protect-locked-access'       => "Тыйын лаштыкын шыгыремдымашыжым тӧрлаш кертмешет шагал.
Ӱлнӧ '''$1''' лаштыкын кызытсе келыштарымаш.",
'protect-cascadeon'           => 'Тиде лаштыкым кыдалтше аралтышан {{PLURAL:$1|лаштыкыш, куштыжо|лаштык-влакыш, куштыжо}} пурымылан кӧра кызыт аралыме. Тый тиде лаштыкын шыгыремдымашыжым тӧрлатен кертат, тидын годым кылдалтше аралтыш огеш вашталт.',
'protect-default'             => 'Чыла пайдаланыше-влаклан йӧным пуаш',
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
'contributions'       => 'Пайдаланышын пашаже',
'contributions-title' => '$1 пайдаланышын паша',
'mycontris'           => 'Мыйын паша',
'contribsub2'         => '$1 лан ($2)',
'uctop'               => '(пытартыш)',
'month'               => 'Могай тылзе гыч тӱҥалаш? (але ондакрак):',
'year'                => 'Могай ий гыч тӱҥалаш? (але ондакрак):',

'sp-contributions-newbies'  => 'У пайдалнышын гына пашам ончыкташ',
'sp-contributions-blocklog' => 'йӧным вашталтыме журнал',
'sp-contributions-search'   => 'Пашам кычалаш',
'sp-contributions-username' => 'IP-адрес ала пайдаланышын лӱмжӧ:',
'sp-contributions-submit'   => 'Кычал',

# What links here
'whatlinkshere'            => 'Кылвер-влак тышке',
'whatlinkshere-title'      => '"$1" дене лаштык-влак кылым палемдат',
'whatlinkshere-page'       => 'Лаштык:',
'linkshere'                => "'''[[:$1]]''' лаштык дене кылдалтше лаштык-влак:",
'nolinkshere'              => "'''[[:$1]]''' лаштык дене нимогай вес лаштык-влак кылым огыт кучо.",
'nolinkshere-ns'           => "Тыгай лӱм-влакын кумдыкышто '''[[:$1]]''' лаштык дене нимогай вес лаштык-влак кылым огыт кучо.",
'isredirect'               => 'вес вереш колтышо лаштык',
'istemplate'               => 'пуртымаш',
'isimage'                  => 'сӱретыш кылвер',
'whatlinkshere-prev'       => '{{PLURAL:$1|ончычсо|$1 ончычсо}}',
'whatlinkshere-next'       => '{{PLURAL:$1|вес|$1 вес}}',
'whatlinkshere-links'      => '← кылвер-влак',
'whatlinkshere-hideredirs' => 'вес вере колтымаш-влакым $1',
'whatlinkshere-hidetrans'  => 'пуртымашым $1',
'whatlinkshere-hidelinks'  => 'кылвер-влакым $1',
'whatlinkshere-filters'    => 'Фильтр-влак',

# Block/unblock
'blockip'                  => 'Пайдаланышылан йӧным петыраш',
'ipbreason'                => 'Амал:',
'ipbreasonotherlist'       => 'Вес амал',
'ipboptions'               => '2 жап:2 hours,1 кече:1 day,3 кече:3 days,1 арня:1 week,2 арня:2 weeks,1 тылзе:1 month,3 тылзе:3 months,6 тылзе:6 months,1 ий:1 year,нимучашдымылык:infinite', # display1:time1,display2:time2,...
'ipbotherreason'           => 'Вес/ешартыш амал:',
'ipblocklist'              => 'Петырыме IP адрес-влак да пайдаланыше-влак',
'ipblocklist-submit'       => 'Кычал',
'blocklink'                => 'йӧным петыраш',
'unblocklink'              => 'йӧным почаш',
'change-blocklink'         => 'йӧным вашталташ',
'contribslink'             => 'паша',
'blocklogpage'             => 'Йӧным вашталтыме журнал',
'blocklogentry'            => '[[$1]] лан йӧным петрен $2 $3 мучашлалтеш',
'unblocklogentry'          => '$1лан йӧным почмо',
'block-log-flags-nocreate' => 'у пайдаланыше-влаклан регистрацийым чактарыме',

# Move page
'move-page-legend'          => 'Лаштыкым кусараш',
'movepagetext'              => "Ӱлыл формо дене пайдаланен, тый лаштыкын лӱмым вашталтен кертат, тудын вашталтыме эртымгорныже у верыш кусарыме.
Тошто лӱмыштӧ у лӱмыш колтымо лаштык кодеш.
Тый тошто лӱмыш колтымо лаштык-влакым шке семын вашталтке кертат.
Тый тидым ынет ыште гын, [[Special:DoubleRedirects|кокытан]] да [[Special:BrokenRedirects|пудыргышо вес вере колтымашым]] терге.
Тый палемдыме верыш кылвер-влаклан шуйнымылан да тушко ончыктымылан вуйын шогет.

Шотыш нал: кунам у лӱман лаштык уло, тудо '''ок''' кусаралт. Тыге огыл, кунам лаштык вес вере кусаралтеш але тудо яра да вашталтымаш эртымгорныже уке.
Тый лаштыкым йонгылыш кусаренат гын менгешла тудым тошто лӱмыш кусарен кертат, но тый уже улшо лаштыкым ӱштын от керт, манын ончыкта.

'''Тӱтко лий!'''
Чӱчкыдын кучылтмо лаштыклан тиде кугу вашталтышым ыштен кертеш;
Умбаке кайыме деч ончыч шоналте, тый тидын деч вара лиймым умылет.",
'movepagetalktext'          => "Тиде лаштыкын каҥашымаш лаштык шке семын огеш кусно, '''тидлан амалже:'''
*Тыгай лӱман яра огыл каҥашымаш лаштык уло ала
*Ӱлыч кайыкым от корангде.

Тыгай годым тылат лаштыкым шке кидет дене кусараш але иктеш ушнаш кӱлеш.",
'movearticle'               => 'Тиде лаштыкым кусараш:',
'newtitle'                  => 'У лӱм:',
'move-watch'                => 'Тиде лаштыкым эскераш',
'movepagebtn'               => 'Лаштыкым кусараш',
'pagemovedsub'              => 'Кусарымаш сайын эртен',
'movepage-moved'            => '\'\'\'"$1" лаштыкым "$2" лаштыкыш кусарыме\'\'\'', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movepage-moved-redirect'   => 'Вес верек колтымаш ыштыме.',
'movepage-moved-noredirect' => 'Вес верек колтымаш ыштыме огыл.',
'articleexists'             => 'Тыгай лӱман лаштык уло але тиде лӱмым кучылташ огеш лий. Вес лӱмым ойыро.',
'talkexists'                => "'''Лаштыкым кусарыме гынат, тудын каҥашымаш лаштыкшым тыгай лӱман лаштык улмылан кӧра кусараш огеш лий. Нуным шке кидет дене иктыш ушно.'''",
'movedto'                   => 'лаштыкыш кусарыме',
'movetalk'                  => 'Каҥашымаш лаштыкым кусараш',
'1movedto2'                 => '[[$1]] лаштыкым [[$2]] лаштыкыш кусарыме',
'1movedto2_redir'           => '[[$1]] лаштыкым [[$2]] лаштыкыш кусарыме ӱмбал вес вереш колтымаш',
'move-redirect-suppressed'  => 'вес верек колтымаш ыштыме огыл',
'movelogpage'               => 'Кусарыме нерген журнал',
'movereason'                => 'Амал:',
'revertmove'                => 'мӧҥгешла пӧртылаш',

# Export
'export' => 'Лаштык-влакым келыштараш',

# Thumbnails
'thumbnail-more' => 'Кугемдаш',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Тыйын лаштыкет',
'tooltip-pt-mytalk'               => 'Тыйын каҥашымаш лаштыкет',
'tooltip-pt-preferences'          => 'Мыйын келыштарымаш',
'tooltip-pt-watchlist'            => 'Мыйын эскерыме лаштык-влак лӱмер',
'tooltip-pt-mycontris'            => 'Тыйын надыр лӱмер',
'tooltip-pt-login'                => 'Шке денет палымым ыштет гын сайрак лиеш; такшым тидым ыштыдеат кертат.',
'tooltip-pt-logout'               => 'Системе гыч лекташ',
'tooltip-ca-talk'                 => 'Лаштыкыште возымым каҥашымаш',
'tooltip-ca-edit'                 => 'Тый тиде лаштыкым тӧрлатен кертат. Лаштыкым аралыме деч ончыч тудым тергаш ит мондо.',
'tooltip-ca-addsection'           => 'У ужашым тӱҥалаш',
'tooltip-ca-viewsource'           => 'Тиде лаштыкым аралыме.
Тый тудын тӱҥалтыш текстшым ончалын кертат.',
'tooltip-ca-history'              => 'Лаштыкын ончычсо тӱрлык-влакше.',
'tooltip-ca-protect'              => 'Тиде лаштыкым тӧрлатымаш деч аралаш',
'tooltip-ca-delete'               => 'Тиде лаштыкым шӧраш',
'tooltip-ca-move'                 => 'Тиде лаштыкым кусараш',
'tooltip-ca-watch'                => 'Тиде лаштыкым тыйын эскерыме-влак лӱмерыш ешараш',
'tooltip-ca-unwatch'              => 'Тиде лаштыкым тыйын эскерыме-влак лӱмер гыч кораҥдаш',
'tooltip-search'                  => "Кычал {{SITENAME}}'ште",
'tooltip-search-go'               => 'Тиде лӱман лаштыкыш куснаш, тыгайже уло гын',
'tooltip-search-fulltext'         => 'Тиде текстан лаштык-влакым кычалаш',
'tooltip-p-logo'                  => 'Тӱҥ лаштык',
'tooltip-n-mainpage'              => 'Тӱҥ лаштыкыш куснаш',
'tooltip-n-portal'                => 'Проект нерген, мом тый ыштен кертат, мо кушто уло',
'tooltip-n-currentevents'         => 'Кызытсе лиймаш-влак нерген увер',
'tooltip-n-recentchanges'         => 'Шукертсе огыл тӧрлатыме лаштык-влак лӱмер',
'tooltip-n-randompage'            => 'Вучыдымо лаштыкыш куснаш',
'tooltip-n-help'                  => 'Википедийым кучылтмо да тӧрлатыме шотышто полшык.',
'tooltip-t-whatlinkshere'         => 'Тышке колтышо чыла лаштык-влакын лӱмерже',
'tooltip-t-recentchangeslinked'   => 'Шукертсе огыл тӧрлымӧ лаштык-влак, кудо дене тиде лаштык кылдалтын',
'tooltip-feed-rss'                => 'Тиде лаштыклан RSS-кыл',
'tooltip-feed-atom'               => 'Тиде лаштыклан Atom-кыл',
'tooltip-t-contributions'         => 'Пайдаланышын паша лӱмерым ончалаш',
'tooltip-t-emailuser'             => 'Тиде пайдаланышылан электрон серышым возаш',
'tooltip-t-upload'                => 'Файл-влакым пурташ',
'tooltip-t-specialpages'          => 'Лӱмын ыштыме лаштык-влак лӱмер',
'tooltip-t-print'                 => 'Тиде лаштыкым савыкташлан келыштарыме',
'tooltip-t-permalink'             => 'Тиде лаштык тӱрлыкыш эре улшо кылвер',
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
Корнышто икымше кылвер шӱкшӧ файлыш кылвер семын лийшаш.
Тиде корнышто вара лийше кылвер-влак лийын кертдыме семын ончалтыт: файлыш кылверан лаштык-влак.',

# Metadata
'metadata'          => 'Метаданный-влак',
'metadata-help'     => 'Тиде файлыште ешартыш увер уло, кудыжым фотоаппарат але сканер дене ыштыме.
Файлым ыштыме деч вара тӧрлымӧ гын, южо данныйже тиде файллан келшыдыме лийын кертеш.',
'metadata-expand'   => 'Ешартыш рашлык-влакым ончыкташ',
'metadata-collapse' => 'Ешартыш рашлык-влакым шылташ',
'metadata-fields'   => 'Тиде лӱмер гыч EXIF кумдыкпале пасу сӱретын лаштыкыште эре ончыкталтеш, посна каласыме огыл гын, вес пасу ок ончыкталт.
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
'recentchangesall' => 'чыла',
'imagelistall'     => 'чыла',
'watchlistall2'    => 'чыла',
'namespacesall'    => 'чыла',
'monthsall'        => 'чыла',

# action=purge
'confirm_purge_button' => 'Йӧра',

# Multipage image navigation
'imgmultipageprev' => '← ончычсо лаштык',
'imgmultipagenext' => 'вес лаштык →',

# Table pager
'table_pager_next'         => 'Вес лаштык',
'table_pager_prev'         => 'Ончычсо лаштык',
'table_pager_limit_submit' => 'Кычалаш',

# Auto-summaries
'autoredircomment' => '[[$1]] лаштыкыш колтымаш',
'autosumm-new'     => "У лаштык '$1' дене тӱҥалеш",

# Watchlist editing tools
'watchlisttools-view' => 'Келшыше тӧрлатымаш-влакым ончалаш',
'watchlisttools-edit' => 'Эскерыме лӱмерым ончалаш да тӧрлаташ',
'watchlisttools-raw'  => 'Эскерыме лӱмерым текст семын тӧрлаш',

# Special:Version
'version-specialpages' => 'Лӱмын ыштыме лаштык-влак',

# Special:FilePath
'filepath-page' => 'Файл:',

# Special:SpecialPages
'specialpages'                 => 'Лӱмын ыштыме лаштык-влак',
'specialpages-group-other'     => 'Весе лӱмын ыштыме лаштык-влак',
'specialpages-group-login'     => 'Пурымаш / регистрацийым эрташ',
'specialpages-group-users'     => 'Пайдаланыше-влак да нунын йӧн-влак',
'specialpages-group-highuse'   => 'Чӱчкыдын пайдалын кучылтышо лаштык-влак',
'specialpages-group-pages'     => 'Лаштык лӱмер-влак',
'specialpages-group-pagetools' => 'Лаштык ӱзгар-влак',
'specialpages-group-redirects' => 'Вес верек колтышо лӱмын ыштыме лаштык-влак',

);
