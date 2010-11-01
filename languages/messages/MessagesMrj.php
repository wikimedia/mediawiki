<?php
/** Hill Mari (Кырык мары)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amdf
 * @author Andrijko Z.
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Спецӹлӹштӓш',
	NS_TALK             => 'Кӓнгӓшӹмӓш',
	NS_USER             => 'Сирӹшӹ',
	NS_USER_TALK        => 'Сирӹшӹн_кӓнгӓшӹмӓшӹжӹ',
	NS_PROJECT_TALK     => '$1_кӓнгӓшӹмӓш',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_кӓнгӓшӹмӓш',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_кӓнгӓшӹмӓш',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_кӓнгӓшӹмӓш',
	NS_HELP             => 'Палшык',
	NS_HELP_TALK        => 'Палшыкын_кӓнгӓшӹмӓш',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категори_кӓнгӓшӹмӓш',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Ажедмӓшвлӓм ыдыралаш',
'tog-highlightbroken'         => 'Уке ылшы ажедмӓшвлӓм <a href="" class="new"> тенге </a> (уке гӹнь тенге<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Текстӹм ӹлӹштӓш кымдык тӧрлӓш',
'tog-hideminor'               => 'Изи тӧрлӹмӓшвлӓм у вашталтымашвлӓ лошты шӹлташ, анжыкташ агыл',
'tog-hidepatrolled'           => 'Патрулируйымы тӧрлӹмӓшвлӓм у вашталтымашвлӓ лошты шӹлташ, анжыкташ агыл',
'tog-newpageshidepatrolled'   => 'Патрулирыйымы ӹлӹшташвлӓм у вашталтымашвлӓ лошты шӹлташ, анжыкташ агыл',
'tog-extendwatchlist'         => 'Вангӹмашӹн (анжымашын) кымда списокшы, кышты остатка веле агыл, а цилӓ вашталтымашым пыртымы',
'tog-usenewrc'                => 'У вашталтымашвлӓн яжорак списокым кычылтман (JavaScript келеш)',
'tog-numberheadings'          => 'Артикль лӹмвлӓм автоматически нумеруяш',
'tog-showtoolbar'             => 'Текстӹм тӧрлӹмӹ годым кӱшӹл панельӹм анжыкташ (JavaScript)',
'tog-editondblclick'          => 'Ӹлӹшташвлӓм кок гӓнӓ темдӓл, тӧрлӓш (JavaScript)',
'tog-editsection'             => '«Тӧрлӓш» ажедмӓшӹм м цилӓ секцилӓнок анжыкташ',
'tog-editsectiononrightclick' => 'Секцим тӧрлӹмӹ годым артикль лӹмӹм каля доно вургымлашты темдӓлӓш (JavaScript)',
'tog-showtoc'                 => 'Кӧргӹштӹш лӹмвлӓм анжыкташ (3 гӹц шукырак артикль лӹмӓн ӹлӹштӓшвлӓштӹ)',
'tog-rememberpassword'        => 'Мӹньӹн шотыш нӓлмӹ сирмӓшем ти компьютерӹштӹ ӓштӓш (максимум $1 {{PLURAL:$1|кечы|кечы}})',
'tog-watchcreations'          => 'Мӹньӹн ӹштӹмӹ ӹлӹштӓшвлӓэм вӓнгӹмӹ списокыш пырташ',
'tog-watchdefault'            => 'Мӹньӹн вашталтымы ӹлӹштӓшвлӓэм вӓнгӹмӹ списокыш пырташ',
'tog-watchmoves'              => 'Мӹньӹн лӹмӹм вашталтымы ӹлӹштӓшвлӓэм вӓнгӹмӹ списокыш пырташ',
'tog-watchdeletion'           => 'Мӹньӹн карангдымы ӹлӹштӓшвлӓэм вӓнгӹмӹ списокыш пырташ',
'tog-minordefault'            => 'Пäлдӹртӹмӹ агыл тöрлӹмäшвлäм когонжок керäлеш шотлаш агыл',
'tog-previewontop'            => 'Текстӹм анзыц анжен лӓкмӹм тӧрлӹмӹ окня анзыкы шӹндӓш',
'tog-previewonfirst'          => 'Текстӹм анзыц анжен лӓкмӹм тӧрлӓш тӹнгӓлмӹ анзыц анжыкташ',
'tog-nocache'                 => 'Ӹлӹштӓшвлӓн кешированим цӓрӓш',
'tog-enotifwatchlistpages'    => 'Мам вӓнгӹмӹ списокын ӹлӹштӓшӹштӹ вашталтымы, тӹдӹм эл. почта доно увертӓрӓш',
'tog-enotifusertalkpages'     => 'Мам персональный ӹлӹштӓшӹштӹ дискуссилӓн пачмы, тӹ вашталтмашым эл. почта доно увертӓрӓш',
'tog-enotifminoredits'        => 'Изи вашталтымашвлӓ гишӓнӓт эл. почта доно увертӓрӓш',
'tog-enotifrevealaddr'        => 'Мӹньӹн эл. адресем увертӓрӹмвлӓштӹ анжыкташ',
'tog-shownumberswatching'     => 'Манярын ӹлӹштӓшӹм вӓнгӹмӹ списокышкышты пыртенӹт, анжыкташ',
'tog-oldsig'                  => 'Ылшы сирӹмӹ подписьӹм анзыцок анжен лӓктӓш',
'tog-fancysig'                => 'Вики-пӓлӹкӹн ӹшке подписьшӹ (автоматический ажедмӓш гӹц пасна)',
'tog-externaleditor'          => 'Тӱнӹш тӧрлӹшӹм кычылташ (компьютерӹн йори настройкыжы келеш)',
'tog-externaldiff'            => 'Вариантвлӓн тӓнгӓштӓрӹмӹ годым тӱнӹш программым кычылташ (компьютерӹн йори настройкыжы келеш)',
'tog-showjumplinks'           => '«ванжаш» палшышы ажедмӓшвлӓм чӱктӓш',
'tog-uselivepreview'          => 'Текстӹм пӹсӹн анзыц анжен лӓкмӹм кычылташ (эксперимент семӹнь JavaScript)',
'tog-forceeditsummary'        => 'Мам тӧрлӹмӹ тӹ «нырын» охыр ылмыжы гишӓн пӓлдӹртӓш',
'tog-watchlisthideown'        => 'Мам тӧрленӓм, тӹдӹм вӓнгӹмӹ спискышты шӹлтӓш, анжыкташ агыл',
'tog-watchlisthidebots'       => 'Ботвлӓм тӧрлӹмӹм вӓнгӹмӹ списокышты шӹлтӓш, анжыкташ агыл',
'tog-watchlisthideminor'      => 'Изи тӧрлӹмӓшвлӓм вӓнгӹмӹ списокышты шӹлтӓш, анжыкташ агыл',
'tog-watchlisthideliu'        => 'Лӹмӹштӹм анжыктышывлӓн тӧрлӹмӹштӹм вӓнгӹмӹ списокышты шӹлтӓш, анжыкташ агыл',
'tog-watchlisthideanons'      => 'Кӱ лӹмжӹм анжыктыде, тӹдӹм вӓнгӹмӹ списокышты шӹлтӓш, анжыкташ агыл',
'tog-watchlisthidepatrolled'  => 'Патрулируйымы тӧрлӹмӓшвлӓм вӓнгӹмӹ списокышты шӹлтӓш, анжыкташ агыл',
'tog-ccmeonemails'            => 'Кӱлӓн сирмӓшӹм сирем, тӹдӹн копижӹм мӹлӓм колташ',
'tog-diffonly'                => 'Кок верси доно тӓнгӓштӓрен ӹлӹштӓшӹн текстшӹм колташ агыл',
'tog-showhiddencats'          => 'Шӹлтӹмӹ категоривлӓм анжыкташ',
'tog-norollbackdiff'          => 'Лишӹц колтымын вариантвлӓн айыртемӹштӹм анжыкташ агыл',

'underline-always'  => 'Соок',
'underline-never'   => 'Нигнамат',
'underline-default' => 'Браузерӹн настройкыжым кычылташ',

# Font style option in Special:Preferences
'editfont-style'     => 'Тӧрлӹмӓштӹ шрифтӹн типшӹ',
'editfont-default'   => 'Браузерӹн настройкывлӓн шрифтӹштӹ',
'editfont-sansserif' => 'Шрифт ыдыралтышвлӓ гӹц пасна',
'editfont-serif'     => 'Шрифт ыдыралтышвлӓ доно',

# Dates
'sunday'        => 'рушӓрня',
'monday'        => 'шачмы',
'tuesday'       => 'кышкыжмы',
'wednesday'     => 'вӹргечӹ',
'thursday'      => 'изӓрня',
'friday'        => 'когарня',
'saturday'      => 'кукшыгечӹ',
'sun'           => 'рш.',
'mon'           => 'шч',
'tue'           => 'кш',
'wed'           => 'вӹ',
'thu'           => 'из',
'fri'           => 'кг',
'sat'           => 'ку',
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
'january-gen'   => 'январьын',
'february-gen'  => 'февральын',
'march-gen'     => 'мартын',
'april-gen'     => 'апрельӹн',
'may-gen'       => 'майын',
'june-gen'      => 'июньын',
'july-gen'      => 'июльын',
'august-gen'    => 'августын',
'september-gen' => 'сентябрьын',
'october-gen'   => 'октябрьын',
'november-gen'  => 'ноябрьын',
'december-gen'  => 'декабрьын',
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
'pagecategories'         => '{{PLURAL:$1|Категори|Категоривлӓ}}',
'category_header'        => 'Категори «$1» ӹлӹштӓшвлӓ',
'subcategories'          => 'Лӹвӓл категоривлӓ',
'hidden-categories'      => '{{PLURAL:$1|Шӹлтӹмӹ категори| Шӹлтӹмӹ категоривлӓ}}',
'category-subcat-count'  => '{{PLURAL:$2|Ти категориштӹ лач ти лӹвӓл категори веле.|{{PLURAL:$1|Лӹвӓл категорим $1 анжыктымы|Лӹвӓл категоривлӓм $1 анжыктымы|Лӹвӓл категорим $1}} анжыктымы $2.}}',
'category-article-count' => '{{PLURAL:$2|Ти категориштӹ ик ӹлыштӓш веле. |{{PLURAL:$1|Анжыктымы$1 ӹлӹшташ|Анжыктымы$1 ӹлӹштӓшӹм|Анжыктымы$1 ӹлыштӓшвлӓм}}ти категори гӹц$2.}}',
'listingcontinuesabbrev' => '(пакыла)',

'newwindow'  => '(у окняшты)',
'cancel'     => 'Вашталташ',
'mytalk'     => 'Мӹньӹн кӓнгӓшӹмӹ ӹлӹштӓшем',
'navigation' => 'Навигаци',

# Cologne Blue skin
'qbfind' => 'Кӹчӓлӓш',
'qbedit' => 'Торлӓш',

# Vector skin
'vector-action-delete'   => 'Карангдаш',
'vector-action-move'     => 'Лӹмӹм вашталташ',
'vector-action-protect'  => 'Ӹшӹклӓш',
'vector-view-create'     => 'Ӹштӓш',
'vector-view-edit'       => 'Тӧрлӹмӓш',
'vector-view-history'    => 'Историм анжымаш',
'vector-view-view'       => 'Лыдмаш',
'vector-view-viewsource' => 'Сек пӹтӓриш кодым анжалаш',

'errorpagetitle'   => 'Самынь',
'returnto'         => 'Мӹнгеш ӹлӹштӓшӹш $1.',
'tagline'          => 'гӹц материал {{grammar:genitive|{{SITENAME}}}}',
'help'             => 'Палшык',
'search'           => 'Кӹчӓлӓш',
'searchbutton'     => 'Моаш',
'searcharticle'    => 'Ванжаш',
'history'          => 'Истори',
'history_short'    => 'Истори',
'printableversion' => 'Пецӓтлӓш верси',
'permalink'        => 'Соок ылшы (постоянный) ажедмӓш',
'edit'             => 'Торлӓш',
'create'           => 'Ӹштӓш',
'editthispage'     => 'Ти ӹлӹштӓшӹм тӧрлӓш',
'delete'           => 'Карангдаш',
'protect'          => 'Ӹшӹклӓш',
'protect_change'   => 'вашталташ',
'newpage'          => 'У ӹлӹштӓш',
'talkpage'         => 'Ти ӹлӹштӓш гишӓн хытыраш',
'talkpagelinktext' => 'Кӓнгӓшӹмӓш',
'personaltools'    => 'Персональный инструментвлӓ',
'talk'             => 'Дискусси',
'views'            => 'Анжымашвлӓ',
'toolbox'          => 'Инструментвлӓ',
'otherlanguages'   => 'Вес йӹлмӹвлӓ доно',
'redirectedfrom'   => '($1 гӹц колтымы)',
'redirectpagesub'  => 'Вес вӓр гӹц колтымы ӹлӹштӓш',
'lastmodifiedat'   => 'Ти ӹлӹштӓшӹн остатка вашталтымашвлӓжӹ: $2, $1.',
'jumpto'           => 'Ванжаш:',
'jumptonavigation' => 'навигаци',
'jumptosearch'     => 'кӹчӓлӓш',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Тидӹ гишӓн сирӹмӓш {{grammar:genitive|{{SITENAME}}}}',
'aboutpage'            => 'Project:Сирӹмӓш',
'copyright'            => 'Тидӹ, тидӹ семӹнь ылеш $1.',
'copyrightpage'        => '{{ns:project}}:Авторын праважы',
'disclaimers'          => 'Вӓшештӹмӹ шая (ответственность) гӹц карангмаш',
'disclaimerpage'       => 'Project:Вӓшештӹмӓш (ответственность) гӹц карангмаш',
'edithelp'             => 'Тӧрлӓш манын палшык',
'edithelppage'         => 'Help:Тӧрлӓш манын палшык',
'helppage'             => 'Help:Палшык',
'mainpage'             => 'Тӹнг ӹлӹштӓш',
'mainpage-description' => 'Тӹнг ӹлӹштӓш',
'privacy'              => 'Весӹвлӓлӓн шайышташ ак ли ылмы политика',
'privacypage'          => 'Project:Весӹвлӓлӓн шайышташ ак ли ылмы политика',

'badaccess' => 'Коргӹш сирӓлтмӓштӹдӓ тама самынь улы',

'retrievedfrom'       => 'Кышец нӓлмӹ «$1»',
'youhavenewmessages'  => 'Тӓ нӓлӹндӓ $1 ($2).',
'newmessageslink'     => 'у увервлӓ',
'newmessagesdifflink' => 'пӹтӓртӹш вашталтымаш',
'editsection'         => 'тӧрлӓш',
'editold'             => 'тӧрлӓш',
'editlink'            => 'тӧрлӓш',
'viewsourcelink'      => 'сек пӹтӓриш кодым анжалаш',
'editsectionhint'     => 'Секцим тӧрлӓш: $1',
'toc'                 => 'Кӧргӹштӹжӹ',
'showtoc'             => 'анжыкташ',
'hidetoc'             => 'карангдаш',
'site-rss-feed'       => '$1 — RSS-вола',
'site-atom-feed'      => '$1 — Atom-вола',
'page-rss-feed'       => '«$1» — RSS-вола',
'page-atom-feed'      => '«$1» — Atom-вола',
'red-link-title'      => '$1 (техень ӹлӹштӓш уке)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Ӹлӹштӓш',
'nstab-user'     => 'Ӹлӹштӓшым сирӹшӹ',
'nstab-special'  => 'Спецӹлӹштӓш',
'nstab-project'  => 'Проект гишӓн',
'nstab-image'    => 'Файл',
'nstab-template' => 'Шаблон',
'nstab-category' => 'Категори',

# General errors
'missing-article'    => 'Информаци базышты ядмы текст уке, кыдым моаш лиэш ылын «$1» $2.

Тенге ӹлӹштӓшӹн вашталтымашвлӓштӹ тошты ажедмӓшвлӓм анжыктымы
годым лиӓлтеш.
Ядмаш тиштӹ агыл гӹнь , лин кердеш, тӹнӓм тӓ программышты тамахань самыньым монда. Пуры лидӓ, тидӹ гишӓн увертӓрӹдӓ [[Special:ListUsers/sysop|администратор]], анжыктен URL.',
'missingarticle-rev' => '(верси № $1)',
'badtitletext'       => 'Ядмы ӹлӹштӓшдӓн лӹмжӹ самынь, охыр, лин кердеш тӧр агыл лӹмӹм ӓль интервикым анжыктендӓ,  ӓнят лӹмӹштӹ кычылташ лидӹмӹ символым сирендӓ.',
'viewsource'         => 'Анжен лӓктӓш',

# Login and logout pages
'yourname'                => 'Сирӹшӹн лӹмжӹ:',
'yourpassword'            => 'Пароль:',
'remembermypassword'      => 'Ти компьютерӹштӹ мӹньӹн учетный сирмӓшем ӓштӓш (максимум $1 {{PLURAL:$1|кечы|кечы}})',
'login'                   => 'Системыш сирӓлтдӓ',
'nav-login-createaccount' => 'Коргӹшкӹ сирӓлтдӓ/регистрируялтда',
'userlogin'               => 'Кӧргӹшкӹ сирӓлтдӓ ӓль регистрируялтда',
'logout'                  => 'Сеансым пӹтӓрӓш',
'userlogout'              => 'Сеансым кашарташ',
'nologinlink'             => 'Учётный сирмӓшӹм ӹштӹдӓ',
'mailmypassword'          => 'У парольым колташ',

# Edit page toolbar
'bold_sample'     => 'Пеле кӹжгӹн сирӹмӹ',
'bold_tip'        => 'Пеле кӹжгӹн сирӹмӹ',
'italic_sample'   => 'Пылен сирӹмӹ',
'italic_tip'      => 'Пылен сирӹмы',
'link_sample'     => 'Ажедмӓшвлӓн лӹмвлӓштӹ',
'link_tip'        => 'Кӧргӹштӹш ажедмӓш',
'extlink_sample'  => 'http://www.example.com ажедмӓшвлӓ',
'extlink_tip'     => 'Тӱнӹш ажедмӓш (префиксӹм идӓ монды  http:// )',
'headline_sample' => 'Текстӹн лӹмжӹ',
'headline_tip'    => '2-шы кӱкшӹцӓн тӹнг лӹм',
'math_sample'     => 'Тишкӹ формулым шӹндӹдӓ',
'math_tip'        => 'Математика формула (формат LaTeX)',
'nowiki_sample'   => 'Тишкӹ форматируйымы агыл текствлӓм шӹндӹдӓ',
'nowiki_tip'      => 'Вики-форматированим мондаш',
'image_tip'       => 'Кӧргӹш пыртен шӹндӹмӹ файл',
'media_tip'       => 'Медиа-файлыш ажедмӓш',
'sig_tip'         => ' Кынам лӹм лӹвӓлӓн киддӓм-пиштендӓ, дата',
'hr_tip'          => 'Горизонталь вола (шӹренжок идӓ кычылт)',

# Edit pages
'summary'                          => 'Мам вашталтымы:',
'subject'                          => 'Тема/вуй лӹм:',
'minoredit'                        => 'Изи тӧрлӹмӓш',
'watchthis'                        => 'Ти ӹлыштӓшӹм вӓнгӓш манын списокыш пырташ',
'savearticle'                      => 'Ӹлӹштӓшӹм темӓш',
'preview'                          => 'Анзыц анжен лӓктӓш',
'showpreview'                      => 'Анзыц анжен лӓкмӓш',
'showdiff'                         => 'Пыртымы вашталтымашвлӓ',
'anoneditwarning'                  => "'''ӓштӹдӓ''':  Тӓ кӧргӹш сирӓлтделда. Системылан ӹшке донда пӓлӹмӹм ӹштӹделда.  Тӓмдӓн IP-адресдӓ ти ӹлыштӓшӹн вашталтымашвлӓн историэшӹжӹ кодеш.",
'summary-preview'                  => 'Сирӹмӹ лиэш:',
'newarticle'                       => '(У)',
'newarticletext'                   => "Ажедмӹ доно тӓ эче ӹштӹмӹ агыл ӹлӹшташӹш вӓрештӹндӓ. Тӹдӹм ӹштӓш манын,  ӱлнӹрӓк ылшы окняэш лӹмӹм сирӓлтӹдӓ. (шукыракым пӓлен нӓлаш, анжал. [[{{MediaWiki:Helppage}}|палшыкын ӹлӹштӓшӹм]])
Самынь тишкӹ вӓрештӹндӓ гӹнь лач браузердӓн '''мӹнгеш''' кнопкыжым веле темдӓлдӓ.",
'noarticletext'                    => "Кӹзӹт ти ӹлӹштӓшӹштӹ текст уке. Ти лӹмӹм [[Special:Search/{{PAGENAME}}|тӓ вес лӹм доно вес ӹлӹштӓшвлӓштӹ мон кердӹдӓ<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} тӓ вес лӹм доно вес ӹлӹштӓшвлӓштӹ мон кердӹда -]] журналвлӓн вес сирмӓшвлӓштӓт моаш лиэш], ӓль
'''[{{fullurl:{{FULLPAGENAME}}|action=edit}}  техень лӹм доно ӹлӹштӓшӹм пачаш лиэш]'''</span>.",
'previewnote'                      => "'''Тидӹ анзыц анжымаш веле, текстӹм эче сирӹмӹ агыл!'''",
'editing'                          => 'Редактируйымаш: $1',
'editingsection'                   => 'Тӧрлӹмаш  $1 (пӧлкӓ)',
'copyrightwarning'                 => 'Пуры лидӓ, ӓшӹшкӹдӓ пиштӹда! Цилӓ мам тӧрлӹмӹ, ушештӹмӹ дӓ вашталтымы, $2 (см. $1) негӹцеш ӹштӹмы семӹнь анжымы лиэш. Мам сиредӓ, тӹдӹм кычылтмы  дӓ тӧрлӹмӹ ваштареш ылыда гӹнь пуры лидӓ, тишӓк идӓ сирӹ.<br /> Тенгеок мам сиредӓ  тӹдӹн авторжы ылыда дӓ мам копируедӓ, тидӹ ирӹкӓн кычылтмашты лишӓшлык<br />.

Автор пӓшӓлӓнжӹ публикаяш разрешеним пуде гӹнь, тишӓк идӓ сирӹ!!!',
'templatesused'                    => '{{PLURAL:$1|Кычылтмы шаблон|Кычылтмы шаблонвлӓ}} ӹлӹштӓшӹн ти версиштӹжӹ:',
'templatesusedpreview'             => '{{PLURAL:$1|Кычылтмы шаблон|Кычылтмы шаблонвлӓ }} анзыц анжымы ӹлӹштӓшӹштӹ:',
'template-protected'               => '(ӹшӹклӓлтеш, перегӓлтеш)',
'template-semiprotected'           => '(лаштыкын-лыштыкын ӹшӹклӓлтеш)',
'hiddencategories'                 => 'Ти ӹлӹштӓш $1 {{PLURAL:$1|шӹлтӹмӹ категориш|шӹлтӹмӹ  категоривлӓш|шӹлтӹмӹ категориш пыра}}:',
'permissionserrorstext-withaction' => "Тидӹм (действим) ӹштӓш манын  тӓмдӓн разрешенидӓ уке«'''$2'''» семеш {{PLURAL:$1|ти ӓмӓл|ӓмӓлвлӓ доно}}:",

# History pages
'viewpagelogs'           => 'Ти ӹлӹштӓшлӓн журналвлӓм анжыкташ',
'currentrev-asof'        => 'Кӹзӹтшӹ верси $1-штӹ',
'revisionasof'           => 'Верси $1',
'previousrevision'       => '← Тошты/первирӓкшӹ верси',
'nextrevision'           => 'Весӹ→',
'currentrevisionlink'    => 'Ти ылшы верси',
'cur'                    => 'кӹзӹтшӹ',
'last'                   => 'анзыц ылшы',
'histlegend'             => "Ынгылдарымашвлӓ: (кӹзӹтшӹ) —кӹзӹтшӹ верси гӹц айыртемӓлтеш ; (анзыл.) — анзылныш верси гӹц айыртемӓлтеш; '''и''' — изи вашталтымаш",
'history-fieldset-title' => 'Историм анжалаш',
'histfirst'              => 'сек тоштывлӓ',
'histlast'               => 'шукердшӹ агыл',

# Revision deletion
'rev-delundel'   => 'анжыкташ/шӹлтӓш',
'revdel-restore' => 'ужаш лимӹм вашталташ',

# Merge log
'revertmerge' => 'Пайылаш',

# Diffs
'history-title'           => '$1 — вашталтымашвлӓн историштӹ',
'difference'              => '(Версивлӓ лошты вашталтмашвлӓ)',
'lineno'                  => 'Сирӹмӹ корны $1:',
'compareselectedversions' => 'Айырен нӓлмӹ версивлӓм тӓнгӓштӓрӓш',
'editundo'                => 'ярал агыл/вашталташ',

# Search results
'searchresults'             => 'Кӹчӓлмӓшӹн результатшы',
'searchresults-title'       => 'Кӹчӓлӓш «$1»',
'searchresulttext'          => 'Ӹлӹштӓшӹшты шукырак информацим нӓлӓш манын [[{{MediaWiki:Helppage}}| палшыкым анжал]]',
'searchsubtitle'            => 'Кӹчӓлмӹ годым«[[:$1]]» ([[Special:Prefixindex/$1|кыды, ти лӹм доно тӹнгӓлӓлтеш]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|кыдывлӓ ти лӹмӹм анжыктенӹт]])',
'searchsubtitleinvalid'     => 'Ядмы семӹнь «$1»',
'notitlematches'            => 'Ӹлӹштӓшвлӓн лӹм икань агыл',
'notextmatches'             => 'Ӹлӹштӓшӹн текствлӓ доно икань агыл',
'prevn'                     => '{{PLURAL:$1|анзылнышы $1|анзылнышывлӓ $1|анзылнышывлӓ $1}}',
'nextn'                     => '{{PLURAL:$1|паштек кешӹ $1|паштек кешӹвлӓ $1|паштек кешӹвлӓ $1}}',
'viewprevnext'              => 'Анжен лӓктӓш ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|$2 шамак|$2 шамаквлӓ|$2 шамак}})',
'search-redirect'           => '(вес вӓрӹш ажед колташ $1)',
'search-section'            => '(кӹдеж $1)',
'search-suggest'            => 'Анят тӓ $1 шанендӓ:',
'search-interwiki-caption'  => 'Техеньок проектвлӓ',
'search-interwiki-default'  => '$1 результ.:',
'search-interwiki-more'     => '(эче)',
'search-mwsuggest-enabled'  => 'согоньвлӓ доно',
'search-mwsuggest-disabled' => 'согоньвлӓдеок',
'nonefound'                 => "'''Шотыш нӓлдӓ.''' Нимат ак лиӓлт гӹнь, керӓл шамакым цилӓ вӓреок кӹчӓлӓш ак тӹнгӓл. Кӹчӓлжӹ манын,   ''all:'' префиксӹм кычылтда.  Тенге тӓ лӹмвлӓм мода (кӱ тишкӹ сирӓ, нӹнӹн Кӓнгӓшӹмӓшвлӓштӹмӓт, шаблонвлӓмӓт дӓ молымат), уке гӹнь, шукырак лӹмӹм анжыктыда",
'powersearch'               => 'Кымдан кӹчӓлмӓш',
'powersearch-legend'        => 'Кымдан кӹчӓлмӓш',
'powersearch-ns'            => 'Кымдецвлӓштӹ лӹмвлӓм кӹчӓлмӓш:',
'powersearch-redir'         => 'Ажедмӓшвлӓм анжыкташ',
'powersearch-field'         => 'Кӹчӓлӓш',

# Preferences page
'preferences'   => 'Настройкывлӓ',
'mypreferences' => 'Настройкывлӓ',

# Groups
'group-sysop' => 'Администраторвлӓ',

'grouppage-sysop' => '{{ns:project}}:Администраторвлӓ',

# User rights log
'rightslog' => 'Сирӹшӹн прававлӓжӹм анжыктышы журнал',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'ти ӹлӹштӓшӹм тӧрлӹмӓш',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|вашталтымаш|вашталтымашвлӓ|вашталтымаш}}',
'recentchanges'                  => 'У тӧрлӹмӓшвлӓ',
'recentchanges-legend'           => 'У тӧрлӹмашвлӓн настройкышты',
'recentchanges-feed-description' => 'Ти лиништӹ Викиштӹш вашталтмашвлӓм анжалаш.',
'rcnote'                         => "{{PLURAL:$1|Пӹтӓртӹш вашталтымаш'''$1''' вашталтымаш|Пӹтӓртыш '''$1''' вашталтымашвлӓ|Пӹтӓртӹш '''$1''' вашталтымашвлӓ}}  '''$2''' {{PLURAL:$2|кечӹштӹ|кечӹштӹ|кечӹвлӓштӹ}}, ти моментеш $5 $4.",
'rclistfrom'                     => '$1 доно вашталтмашвлӓм анжыкташ',
'rcshowhideminor'                => '$1 изи тӧрлӹмаш',
'rcshowhidebots'                 => '$1 бот',
'rcshowhideliu'                  => '$1 тинӓрӹн сирӓлтӹнӹт',
'rcshowhideanons'                => '$1 анонимвлӓ',
'rcshowhidemine'                 => '$1 ӹшке тӧрлӹмӓшвлӓэт',
'rclinks'                        => 'Пӹтӓрӹтш  $1кечӹвлаштӹш $2 вашталтмашвлӓм  анжыкташ<br />$3',
'diff'                           => 'ма-шон',
'hist'                           => 'истори',
'hide'                           => 'шӹлтӓш',
'show'                           => 'анжыкташ',
'minoreditletter'                => 'м',
'newpageletter'                  => 'У',
'boteditletter'                  => 'б',
'rc-enhanced-expand'             => 'Цилӓ анжыкташ (JavaScript кычылтда)',
'rc-enhanced-hide'               => 'Цилӓ анжыкташ агыл',

# Recent changes linked
'recentchangeslinked'         => 'Кӹлдӓлтшӹ тӧрлӹмӓшвлӓ',
'recentchangeslinked-feed'    => 'Кӹлдӓлтшӹ тӧрлӹмшвлӓ',
'recentchangeslinked-title'   => 'Кӹлдӓлтшӹ торлӹмӓшвлӓ $1 доно',
'recentchangeslinked-summary' => "Тиштӹ шукердӹ агыл ӹштӹмӹ вашталтмашвлӓ анжыкталтыт, кышты ти ӹлыштӓш ажедеш (ӓль ти категоришкӹ пырышывлӓ). Ӹлӹштӓшвлӓ, кыдывлӓ пырат [[Special:Watchlist|тӓмдӓн вангӹмӹ списокда]] графаш,  '''айырымы ылыт'''.",
'recentchangeslinked-page'    => 'Ӹлӹштӓшӹн лӹмжӹ:',
'recentchangeslinked-to'      => 'Анешлӓ, тӹ ӹлыштӓшвлӓштӹш вашталтымашвлӓм анжыкташ, кыдывлӓ ти ӹлӹштӓшӹшкӹ ажедӹт',

# Upload
'upload'        => 'Файлым темӓш',
'uploadlogpage' => 'Оптымашвлӓн журналышты',
'uploadedimage' => 'оптымы «[[$1]]»',

# File description page
'filehist'                  => 'Файлын историжӹ',
'filehist-help'             => 'Файл тӹнӓм махань ылын, тидӹм ужаш манын датым темдӓлдӓ.',
'filehist-current'          => 'кӹзӹтшӹ',
'filehist-datetime'         => 'Дата/жеп',
'filehist-thumb'            => 'Миниатюра',
'filehist-thumbtext'        => '$1 гӹц версилӓн миниатюра вариантжы',
'filehist-user'             => 'Сирӹшӹ',
'filehist-dimensions'       => 'Объектӹн размержӹ',
'filehist-comment'          => 'Пӓлӹквлӓ',
'imagelinks'                => 'Файлышкы ажедмӓшвлӓ',
'linkstoimage'              => '{{PLURAL:$1|Паштек $1 вес ӹлӹштӓш ажедеш| $1 вес ӹлӹштӓшвлӓ ажедӹт|Вес  $1 ӹлӹштӓшвлӓ ти файлыш}} ажедӹт:',
'sharedupload'              => 'Ти $1 файлым вес проектвлӓштӹ кычылташ лиэш',
'uploadnewversion-linktext' => 'Файлын у версижӹм темӓш',

# Random page
'randompage' => 'Самынь ӹлӹштӓш',

# Statistics
'statistics' => 'Статистика',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|байт|байтан|байтвлӓ}}',
'nmembers'      => '$1 {{PLURAL:$1|объект|объектӹн|объект}}',
'prefixindex'   => 'Ӹлыштӓшвлӓн лӹмӹштӹн тӹнгӓлтӹш доно анжыктымы',
'newpages'      => 'У ӹлӹштӓшвлӓ',
'move'          => 'Вес лӹмӹм пуаш',
'movethispage'  => 'Ти ӹлыштӓшӹн лӹмжӹм вашталташ',
'pager-newer-n' => '{{PLURAL:$1|урак|ураквлӓ|ураквлӓ гӹц}} $1',
'pager-older-n' => '{{PLURAL:$1|тоштырак|тоштыраквлӓ|тоштыраквлӓ гӹц}} $1',

# Book sources
'booksources'               => 'Книгӓн кӹлвлӓжӹ (источник)',
'booksources-search-legend' => 'Книгӓ гишӓн информацим кӹчӓлмӓш',
'booksources-go'            => 'Моаш',

# Special:Log
'log' => 'Журналвлӓ',

# Special:AllPages
'allpages'       => 'Цилӓ ӹлӹштӓш',
'alphaindexline' => '$1 гӹц $2 якте',
'prevpage'       => 'Анзыл ӹлӹштӓш ($1)',
'allpagesfrom'   => 'Анжыкташ тӹ ӹлӹштӓшвлӓм , кыдывлӓ тӹнгӓлӓлтӹт:',
'allpagesto'     => 'Лыкмашым тишӓк шагалташ:',
'allarticles'    => 'Цилӓ ӹлӹштӓш',
'allpagessubmit' => 'Ӹштӓш',

# Special:LinkSearch
'linksearch' => 'Тӱнӹш ажедмӓшвлӓ',

# Special:Log/newusers
'newuserlogpage'          => 'Сирӹшӹвлӓм регистрируйышы журнал',
'newuserlog-create-entry' => 'У сирӹшӹ',

# Special:ListGroupRights
'listgrouprights-members' => '(группын списокшы)',

# E-mail user
'emailuser' => 'Сирӹшӹлӓн сирмӓш',

# Watchlist
'watchlist'         => 'Вӓнгӹмӹ список',
'mywatchlist'       => 'Вӓнгӹмӹ сирмӓш, список',
'addedwatch'        => 'Вӓнгӹмӓш списокыш пыртымы',
'addedwatchtext'    => 'Ӹлӹштӓшӹм«[[:$1]]» тӓмдӓн вӓнгӹмӹ [[Special:Watchlist|ӹлӹштӓшӹшкӹдӓ пыртымы]]. Тидӹ паштек ӹштӹмӹ ӹлӹштӓшӹн вашталтымашвлӓ  ти списокышты анжыктымы дӓ тенгеок кӹжгӹ буквавлӓ доно у вашталтымашвлӓн списокышты пӓлдӹртӹмӹ лиэш[[Special:RecentChanges| пӓлдӹртӹмӹ лит]] нӹнӹм  айыраш куштылгырак лижӹ манын.',
'removedwatch'      => 'Вӓнгӹмӹ список гӹц карангдымы',
'removedwatchtext'  => 'Ӹлӹштӓш «[[:$1]]» тӓмдӓн вӓнгӹмӹ ӹлӹштӓшдӓ гӹц [[Special:Watchlist|карангдымы]].',
'watch'             => 'Вӓнгӓш',
'watchthispage'     => 'Ти ӹлӹштӓшӹм вӓнгӓш',
'unwatch'           => 'Вӓнгӓш агыл',
'watchlist-details' => 'Тӓмдӓн вӓнгӹмӹ списокыштыда $1 {{PLURAL:$1|ӹлӹштӓш|ӹлӹштӓшвлӓ|ӹлӹштӓш}}, Кӓнгӓшӹмӓш ӹлыштӓшвлӓ гӹц пасна.',
'wlshowlast'        => 'Анжыкташ эртӹш  $1 час $2 кечӹвлӓн $3',
'watchlist-options' => 'Вӓнгӹмӹ списокын настройкыжы',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Вӓнгӹмӹ списокыш пырташ...',
'unwatching' => 'Вӓнгӹмӹ список гӹц карангдаш...',

# Delete
'deletepage'            => 'Ӹлӹштӓшӹм карангдаш',
'confirmdeletetext'     => 'Тӓ ӹлӹштӓшӹн (изображенин) цилӓ информацижӹм  дӓ базышты ылшы вашталтымашвлӓн историм ӹштӹл шуаш ядыда. Пуры лидӓ, дӓ лачокат ма тенге ӹштӹнедӓ, шаналтыда. Махань последствивлӓ тидӹ паштек вычат, ынгылышашлык ылыда дӓ тидӹм  ти кӹдешӹтӹ анжыктымы правилывлӓштӹ анжалда [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'        => 'Лачокат ӹштӹмӹ',
'deletedtext'           => '«<nowiki>$1</nowiki>» карангдымы.
Анжы: $2 тидӹ мам карангдымы тӹ списокым анжыкта',
'deletedarticle'        => '«[[$1]]» карангдымы',
'dellogpage'            => 'Мам карангдымы анжыктышы сирмӓш',
'deletecomment'         => 'Ӓмӓлжӹ:',
'deleteotherreason'     => 'Вес ӓмӓл/ынгылдарал:',
'deletereasonotherlist' => 'Вес ӓмӓл',

# Rollback
'rollbacklink' => 'лишӹц колташ',

# Protect
'protectlogpage'              => 'Ӹшӹклӹмӹ журнал',
'protectedarticle'            => 'ӹлӹштӓш ӹшӹклӓлтеш «[[$1]]»',
'modifiedarticleprotection'   => 'ӹлӹштӓшӹн ӹшӹклӹмӹ кӱкшӹцшӹм вашталтымы «[[$1]]»',
'protectcomment'              => 'Ӓмӓлжӹ:',
'protectexpiry'               => 'Жепшӹ пӹтӓ:',
'protect_expiry_invalid'      => 'Ӹшӹклӹмӹ жепӹм самынь анжыктымы.',
'protect_expiry_old'          => 'Пӹтӹм жепшӹ эртен',
'protect-text'                => "Тиштӹ тӓ ӹлӹштӓшӹн ӹшӹклӹмӹ кӱкшӹцшӹм анжал дӓ вашталтен кердӹдӓ'''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Тӓмдӓн учетный карточкыдан ӹшӹклӹмӓш кӱкшӹцӹм вашталташ манын ситӓлык праважы уке. Ти ӹлӹштӓшӹм шӹндӹдӓ'''$1''':",
'protect-cascadeon'           => 'Ти ӹлӹштӓшӹм {{PLURAL:$1|-шкы пыртымат, ӹшӹклӓлтеш, кыды ӱлнӹрӓк ылшы ӹлӹштӓшӹш|ӹлӹштӓшвлӓшкӹ ажедеш, кышкы }} каскадан ӹшӹклӹмашӹм шӹндӹмӹ. Тӓ ти ӹлӹштӓшӹн ӹшӹклӹмӹ кӱкшӹцшӹм вашталтен кердӹдӓ, но тидӹ каскадан ӹшӹклӹмашӹм ак тӹкӓл лиэш.',
'protect-default'             => 'Ӹшӹклӹмӹ агыл',
'protect-fallback'            => 'Разрешени келеш «$1»',
'protect-level-autoconfirmed' => 'У дӓ регистрируялтшы агыл сирӹшӹвла гӹц ӹшӹклӓш',
'protect-level-sysop'         => 'Администраторвлӓ веле',
'protect-summary-cascade'     => 'каскадан',
'protect-expiring'            => 'жепшӹ пӹтӓ $1 (UTC)',
'protect-cascade'             => 'Ти ӹлӹштӓшӹшкӹ пыртымы ӹлӹштӓшвлӓм ӹшӹклӓш (каскадан ӹшӹклӹмӓш)',
'protect-cantedit'            => 'Ти текстӹм тӧрлӓш правада укеӓт, тӓ ти ӹлӹштӓшӹн ӹшӹклӹмӹ кӱкшӹцшӹм вашталтен ада керд.',
'restriction-type'            => 'Прававлӓ:',
'restriction-level'           => 'Пыраш лимӹ кӱкшӹц:',

# Undelete
'undeletelink'     => 'анжен лӓктӓш/угӹц ӹштӓш',
'undeletedarticle' => 'мӹнгеш шӹндӹмӹ «[[$1]]»',

# Namespace form on various pages
'namespace'      => 'Лӹмвлӓн кымдецӹштӹ:',
'invert'         => 'Мам айырендӓ, мынгеш шӹндӓш',
'blanknamespace' => '(Тӹнг)',

# Contributions
'contributions'       => 'Лӓктӹшет',
'contributions-title' => 'Сирӹшӹн лӓктӹшӹжӹ $1',
'mycontris'           => 'Мӹньын лӓктӹшем',
'contribsub2'         => 'Лӓктӹш $1 ($2)',
'uctop'               => '(пӹтӓртӹш)',
'month'               => 'Тӹлзӹ гӹц (ирӹрӓкӓт):',
'year'                => 'Ти и гӹц (ирӹрӓкӓт):',

'sp-contributions-newbies'  => 'Лач тӹ лӓктӹшвлӓм веле анжыкташ, кыдывлӓм у сирӹмӓшвлӓштӹ шотыш нӓлмӹ',
'sp-contributions-blocklog' => 'блокировкывлӓ',
'sp-contributions-search'   => 'Лӓктӹшӹм кӹчӓлмӓш',
'sp-contributions-username' => 'IP-сирӹшӹн адрес дон лӹмжӹ:',
'sp-contributions-submit'   => 'Моаш',

# What links here
'whatlinkshere'            => 'Тишкӹ ажедмӓшвлӓ  (ссылкывлӓ)',
'whatlinkshere-title'      => 'Ӹлӹштӓшвлӓ, кыдывлӓ ажедӹт «$1»',
'whatlinkshere-page'       => 'Ӹлӹштӓш:',
'linkshere'                => "Ти ӹлӹштӓшвлӓ тишкӹ ажедӹт '''[[:$1]]''':",
'isredirect'               => 'вес вӓре колтымым анжыктышы ӹлӹштӓш',
'istemplate'               => 'кӧргӹш пыртымы',
'isimage'                  => 'изображени докы ажедмӓш',
'whatlinkshere-prev'       => '{{PLURAL:$1|анзылнышы|анзылнышывлӓ|анзылнышывла}} $1',
'whatlinkshere-next'       => '{{PLURAL:$1|пакылашы|пакылашывлӓ|пакылашывлӓ}} $1',
'whatlinkshere-links'      => '← ажедмӓшвлӓ',
'whatlinkshere-hideredirs' => '$1 вес вӓрӹш колтымаш',
'whatlinkshere-hidetrans'  => '$1 кӧргӹш пыртымывлӓ',
'whatlinkshere-hidelinks'  => '$1 ажедмӓшвлӓ',
'whatlinkshere-filters'    => 'Фильтрвлӓ',

# Block/unblock
'blockip'                  => 'Блокируяш',
'ipboptions'               => '2 часеш:2 hours,1 кечеш:1 day,3 кечеш:3 days,1 ӓрняэш:1 week,2 ӓрняэш:2 weeks,1 тӹлзеш:1 month,3 тӹлзеш:3 months,6 тӹлзеш:6 months,1 иэш:1 year, соэшок:infinite',
'ipblocklist'              => 'Блокируйымы IP-адресвлӓ дон шотыш нӓлмӹ сирӹмӓшвлӓ',
'blocklink'                => 'блокируяш',
'unblocklink'              => 'блокировкым пачаш',
'change-blocklink'         => 'блокировкым вашталташ',
'contribslink'             => 'лӓктӹш, мам ӹштендӓ тӹдӹ',
'blocklogpage'             => 'Блокировкывлӓн журналышты',
'blocklogentry'            => 'периодеш [[$1]] блокируйымы$2 $3',
'unblocklogentry'          => 'блокировкым пачмы $1',
'block-log-flags-nocreate' => 'учётный сирмӓшвлӓн регистрацим запрещӓйӹмӹ',

# Move page
'movepagetext'     => 'Ӱлнӹш формым кычылт, тӓ ӹлӹштӓшлӓн у лӹмӹм пуэдӓ дӓ тӹ годымок вашталтымашвлӓн журналышты у вӓрӹш шӹндедӓ. Тошты лӹм у лӹмӹн  вес вӓрӹшкӹ колтышы семӹнь лиэш. Тошты лӹмӹшкӹ пыртымы вес вӓрӹшкӹ колтымашвлӓм автоматически уэмден кердӹдӓ. Тидӹм ада ӹштӹ гӹнь, пуры лидӓ, контролируен лӓкдӓ [[Special:DoubleRedirects|коктым]] дон [[Special:BrokenRedirects|кӹрмӹ вес вӓре ажедмӓшвлӓм]]. Кышкы ажедмӓшвлӓ анжыктышашлык ылыт, тӓ тидӹ верц вӓшештедӓ. Шотыш нӓлдӓ, у лӹм доно ӹлӹштӓш улы гӹнь, тӹдӹн лӹмжӹ «ак вашталт»;  вес вӓрӹшкӹ колтымаш ӓль охыр дӓ тӧрлӹмӓшвлӓн историштӹ уке ылмы гӹц пасна.
Тидӹ теве мам анжыкта, самынь у лӹмӹм пуэндӓ гӹнь, изиш анзыцырак ӹлӹштӓшӹн махань лӹмжӹ ылын, тӹ лӹмӹшкок вашталтен кердӹдӓ,  но ти улы ылшы ӹлӹштӓшӹм тӓ ӹштӹл шуэн ада керд.
«ӒШТӸДӒ!»
У лӹмӹм пумаш  «популярный» ӹлӹштӓшвлӓлӓн пиш кого вычыдымашвлӓм канден кердеш.
Пуры лидӓ, пакыла сирӓш шанедӓ гӹнь, кышкы тидӹ канден кердеш, ынгылышашлык ылыда.',
'movepagetalktext' => 'Пижӹктӹмӹ кӓнгӓшӹмӓш ӹлӹштӓшӓт  лӹмжӹм автоматически вашталта, техень лиӓлтмӓшвлӓ гӹц пасна:
*Техень лӹмӓн кӓнгӓшӹмӓш ӹлӹштӓш тӹтежӓт улы ӓль
*ӱлнӹрӓк ылшы  ныреш кагырикӹм ыдыралделда.
Техень годым, келеш гӹнь, тӓ ти ӹлыштӓшӹм вес вӓрӹшкӹ кид доно шӹндӹшӓшлык ӓль ушештӹшӓшлык ылыда.',
'movearticle'      => 'Ӹлӹштӓш лӹмӹм вашталташ',
'newtitle'         => 'У лӹм:',
'move-watch'       => 'Ти ӹлыштӓшӹм вӓнгӹмӓшӹн списокыш пыртымыла',
'movepagebtn'      => 'Ӹлӹштӓшӹн лӹмжӹм вашталташ',
'pagemovedsub'     => 'Ӹлӹштӓшӹн лӹмжӹм вашталтымы',
'movepage-moved'   => "'''Ӹлӹштӓшӹн  «$1» лӹмжӹ ӹнде«$2»'''",
'articleexists'    => 'Техень лӹмӓн ӹлӹштӓш тӹтежӓт улы. Пуры лидӓ, вес лӹмӹм айырыда.',
'talkexists'       => "'''Ӹлӹштӓш лӹм вашталтымы, но техень лӹмӓн ӹлӹштӓш тӹтежӓт улы, кӓнгӓшӹмӓш ӹлӹштӓшӹн лӹмжӹм вашталташ ак ли. Пуры лидӓ, кид доно нӹнӹм ушыда.'''",
'movedto'          => 'техеньӹш вашталтымы',
'movetalk'         => 'Ти кӓнгӓшӹмӓш ӹлӹштӓшӹн лӹмжӹм вашталташ',
'1movedto2'        => 'лӹмӹм вашталтымы «[[$1]]» в «[[$2]]»',
'1movedto2_redir'  => '«[[$1]]» у лӹмӹм пумы «[[$2]]» вес вӓрыш колтымы вӹлец',
'movelogpage'      => 'Лӹмвлӓм вашталтымы журнал',
'movereason'       => 'Ӓмӓлжӹ:',
'revertmove'       => 'лишӹц колтымаш',

# Export
'export' => 'Артикльвлӓм экспортируйымаш',

# Thumbnails
'thumbnail-more' => 'Когоэмдӓш',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Тӓмдӓн сирӹмӹ ӹлӹштӓшда',
'tooltip-pt-mytalk'               => 'Тӓмдӓн Кӓнгӓшӹмӓш ӹлӹштӓшдӓ',
'tooltip-pt-preferences'          => 'Мӹньӹн настройкывлӓэм',
'tooltip-pt-watchlist'            => 'Мам вӓнгенӓм, тӹдӹн сирмӓшӹжӹ',
'tooltip-pt-mycontris'            => 'Мам тӧрлендӓ , тӹдӹн списокшы',
'tooltip-pt-login'                => 'Тиштӹ регистрируялташ лиэш, но обязательны агыл',
'tooltip-pt-logout'               => 'Пӓшан сеансшым пӹтӓрӓш',
'tooltip-ca-talk'                 => 'Мам ӹлӹштӓшӹштӹ сирӹмӹ, тӹдӹ гишӓн хытыраш',
'tooltip-ca-edit'                 => 'Ти ӹлӹштӓшӹм вашталташ лиэш. Пуры лидӓ, «переген кодаш» графам темдӓлмешкӹдӓ «анзыц анжалаш» графам кычылтда.',
'tooltip-ca-addsection'           => 'У трансляцин пӧлкӓм ӹштӓш',
'tooltip-ca-viewsource'           => 'Ти ӹлӹштӓш вашталтымашвлӓ гӹц ӹшӹклӓлтеш, тенге гӹнят тӓ махань тӹдӹ пӹтӓри ылын анжал дӓ копируен кердӹдӓ',
'tooltip-ca-history'              => 'Мам ӹлӹштӓшӹштӹ вашталтымы',
'tooltip-ca-protect'              => 'Ӹлӹштӓшӹм вашталтымашвлӓ гыц ӹшӹклӓш',
'tooltip-ca-delete'               => 'Ти ӹлӹштӓшӹм ӹштӹл шуаш',
'tooltip-ca-move'                 => 'Ӹлӹштӓшлӓн вес лӹмӹм пуаш',
'tooltip-ca-watch'                => 'Ти ӹлыштӓшӹм вӓнгӹшӹвлӓн сирмӓшӹш пырташ',
'tooltip-ca-unwatch'              => 'Ти ӹлыштӓшӹм вӓнгӹмы сирмӓшдӓ гӹц ӹштӹл шуаш',
'tooltip-search'                  => 'Кӹчӓлӓш {{SITENAME}}',
'tooltip-search-go'               => 'Техень лӹм донок ылшы вес ӹлӹштӓшӹш ванжаш',
'tooltip-search-fulltext'         => 'Техень текстӓн ӹлӹштӓшвлӓшкӹ ванжаш',
'tooltip-n-mainpage'              => 'Тӹнг ӹлӹштӓшӹш ванжаш',
'tooltip-n-mainpage-description'  => 'Тӹнг ӹлӹштӓшӹш ванжаш',
'tooltip-n-portal'                => 'Тӹдӹ, мам ӹштен кердӹдӓ дӓ кышты ма вӓрлӓнӓ, тӹдӹ гишӓн проект',
'tooltip-n-currentevents'         => 'Ма лиӓлтӹн, тӹдӹм анжыктышы сирмӓш',
'tooltip-n-recentchanges'         => 'Техень текстӓн ӹлӹштӓшвлӓшкӹ ванжаш',
'tooltip-n-randompage'            => 'Самынь вӓрештшӹ ӹлӹштӓшӹм анжен лӓктӓш',
'tooltip-n-help'                  => '«{{SITENAME}}» проект доно справочник',
'tooltip-t-whatlinkshere'         => 'Цилӓ ӹлӹштӓшӹн список, кыдывлӓ ти ӹлӹштӓшӹш ажедӹт',
'tooltip-t-recentchangeslinked'   => 'Тӹ ӹлӹштӓшвлӓштӹ остатка вашталтмашвлӓ, кышкы ти ӹлӹштӓш ажедеш',
'tooltip-feed-rss'                => 'Ти ӹлӹштӓшлӓн RSS-шты трансляци',
'tooltip-feed-atom'               => 'Ти ӹлӹштӓшлӓн Atom-шты трансляци',
'tooltip-t-contributions'         => 'Мам ти сирӹшӹ вашталтен, ӹлӹштӓшвлӓн список',
'tooltip-t-emailuser'             => 'Ти сирӹшӹлӓн сирмӓшӹм колташ',
'tooltip-t-upload'                => 'Изображенивлӓ дон мультимеди-файлым темӓш',
'tooltip-t-specialpages'          => 'Спецӹлӹштӓшвлӓн список',
'tooltip-t-print'                 => 'Ти ӹлӹштӓшӹн пецӓтлӹмӹ версижӹ',
'tooltip-t-permalink'             => 'Соок ти ӹлӹтӓшӹн вариантышкыжы ажедмӓш',
'tooltip-ca-nstab-main'           => 'Ӹлӹштӓшӹн кӧргӹжӹ',
'tooltip-ca-nstab-user'           => 'Сирӹшӹн ӹшке ӹлӹштӓшӹжӹ',
'tooltip-ca-nstab-special'        => 'Тидӹ спецӹлӹштӓш, кыдым редактируяш ак ли',
'tooltip-ca-nstab-project'        => 'Проектӹн ӹлӹштӓшӹжӹ',
'tooltip-ca-nstab-image'          => 'Файлын ӹлӹштӓшӹжӹ',
'tooltip-ca-nstab-template'       => 'Шаблонын ӹлӹшташӹжӹ',
'tooltip-ca-nstab-category'       => 'Категорин ӹлӹштӓшӹжӹ',
'tooltip-minoredit'               => 'Ти вашталтымаш кого агылат, вашталташ',
'tooltip-save'                    => 'Тӧрлӹмашдӓм ӹшӹклен кодаш',
'tooltip-preview'                 => 'Переген кодаш кнопкым темдӓлмӹда анзыц, пуры лидӓ, анзыц анжалмым кычылтда!',
'tooltip-diff'                    => 'Мам тӧрлӹмӹдӓ якте текстӹштӹ сирӹмӹ ылын, тӹ вашталтымашвлӓм анжыкташ',
'tooltip-compareselectedversions' => 'Ти кок айырымы верси лошты махань айыртем, анжалаш',
'tooltip-watch'                   => 'Ти ӹлӹштӓшӹм вӓнгӹмӹ списокыш пырташ',
'tooltip-rollback'                => 'Мам анзыцда тӧрлӹшӹ ӹштен, тӹдым ик гӓнӓ темдӓлок ӹштӹл шуаш',
'tooltip-undo'                    => 'Тӧрлӹмӹм ӹштӹл шуаш, анзыц анжалмым  дӓ  лиэш гӹнь вашталтымашын  ӓмӓлжӹм анжыкташ',

# Browsing diffs
'previousdiff' => '← Анзыл тӧрлӹмӓш',
'nextdiff'     => 'Вес тӧрлӹмӓш →',

# Media information
'file-info-size'       => '($1 × $2 пиксел,  файлын размержӹ: $3, MIME-тип: $4)',
'file-nohires'         => '<small>Кого разрешени доно верси уке.</small>',
'svg-long-desc'        => '(SVG-файл, номинально $1 × $2 пиксель,  файлын размержӹ: $3)',
'show-big-image'       => 'Кӱкшӹрӓк разрешениӓн изображени',
'show-big-image-thumb' => '<small>Размер анзыц анжымы годым: $1 × $2 пиксель</small>',

# Bad image list
'bad_image_list' => 'Техень форматан лишӓшлык:

Лач списокын элементвлӓжӹм веле шотыш нӓлмӹ лиэш (* пӓлӹк доно тӹнгӓлӓлтшӹ символвлӓ)
Корнын пӹтӓриш ажедмӓшӹжӹ ӹштӓш ак ли ылмы лаштыкым анжыктышашлык, тӹшкӹ ажедшӓшлык.
Тидӹ паштек ажедмӓшвлӓ, кыдывлӓм ти корнышты анжыктымы, исключени семӹнь анжымы лит дӓ тӹшкӹ ти изображеним шӹндӓш лиэш.',

# Metadata
'metadata'          => 'Метадата',
'metadata-help'     => 'Дигитальный камеры  дӓ сканер доно шӹндӹмӹ файлыштыш дополнительный информаци. Ӹштӹмӹ паштек файлым редактируйымы гӹнь, тӹнӓм кыды-тидӹ параметржӹ анжыктымы изображенилан ак вӓшештӹ.',
'metadata-expand'   => 'Дополнительный информацим анжыкташ',
'metadata-collapse' => 'Дополнительный информацим шӹлтӓш',
'metadata-fields'   => 'Ти списокышты ылшы метадатывлӓн нырыштым изображенин ӹлӹштӓшӹштӹ анжыктымы лиэш, весӹвлӓжӹ шӹлтӹмӹ лит.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Тӱнӹш программым кычылт, ти файлым тӧрлӓш',
'edit-externally-help' => '(шукыракым анжал: [http://www.mediawiki.org/wiki/Manual:External_editors установкын правилывлӓжӹ])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'цилӓ',
'namespacesall' => 'цилӓ',
'monthsall'     => 'цилӓ',

# Watchlist editing tools
'watchlisttools-view' => 'Список гӹц нӓлмӹ ӹлӹштӓшӹштӹш вашталтымашвлӓ',
'watchlisttools-edit' => 'Анжалаш/списокым тӧрлӓш',
'watchlisttools-raw'  => 'Текст семӹнь тӧрлӓш',

# Special:SpecialPages
'specialpages' => 'Спецӹлӹштӓшвлӓ',

);
