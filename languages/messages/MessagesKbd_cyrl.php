<?php
/** Kabardian (Cyrillic) (къэбэрдеибзэ/qabardjajəbza (Cyrillic))
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Алёшка
 * @author Тамэ Балъкъэрхэ
 */

$fallback = 'ru';

$messages = array(
# User preference toggles
'tog-underline'          => 'Ссылкэхэр щIэтхъэн:',
'tog-highlightbroken'    => 'ЩымыIэ ссылкэхэр къэгъэлъэгъуэн <a href="" class="new">вот так</a> (ар мыхъумэ мыпхуэдэу<a href="" class="internal">?</a>).',
'tog-justify'            => 'БгъуагъкIэ напэкIуэцIыр зэгъэзэхуэн',
'tog-hideminor'          => 'ГъэпщкIун: кIуэдкIэ зыхэмылэжьыхьа, щIэуэ яхъуэжа списокым',
'tog-rememberpassword'   => 'Компьютерым си логиныр щыхъумэн',
'tog-watchcreations'     => 'Сэ сщIа напэкIуэцIхэр сызыкIэлъыплъ списокым хэлъхьэн',
'tog-watchdefault'       => 'Сэ схъуэжа напэкIуэцIхэр сызыкIэлъыплъ списокым хэлъхьэн',
'tog-watchmoves'         => 'Зи цIэ схъуэжа напэкIуэцIхэр сызыкIэлъыплъ списокым хэлъхьэн',
'tog-watchdeletion'      => 'Сэ тезгъэкIыжа напэкIуэцIхэр сызыкIэлъыплъ списокым хэлъхьэн',
'tog-nocache'            => 'Кеш щIа напэкIуэцIхэр гъэбыдэн',
'tog-watchlisthideown'   => 'Си хъуэжапхъэхэр сызыкIэлъыплъ спискэм хэгъэпщкIухьым',
'tog-watchlisthidebots'  => 'Ботым ихъуэжахэр сызыкIэлъыплъ спискэм щыгъэпщкIуын',
'tog-watchlisthideminor' => 'МащIэу хъуэжахэр сызыкIэлъыплъ спискэм щыгъэпщкIуын',
'tog-showhiddencats'     => 'ГъэпщкIуа категориехэр къэгъэлъэгъуэжын',

'underline-always'  => 'Сыт щыгъуи',
'underline-never'   => 'ЗейкI',
'underline-default' => 'Браузерым и теухуапхъэхэр къэгъэсэбэпын',

# Dates
'sunday'        => 'Тхьэмахуэ',
'monday'        => 'Блыщхьэ',
'tuesday'       => 'Гъубж',
'wednesday'     => 'Бэрэжьей',
'thursday'      => 'Махуэку',
'friday'        => 'Мэрем',
'saturday'      => 'Щэбэт',
'sun'           => 'Тхьм',
'mon'           => 'Блщ',
'tue'           => 'Гбж',
'wed'           => 'Бржь',
'thu'           => 'Мку',
'fri'           => 'Мрм',
'sat'           => 'Щбт',
'january'       => 'ЩIышылэ(01)',
'february'      => 'Мазае(02)',
'march'         => 'Гъатхэпэ(03)',
'april'         => 'Мэлыжьыхь(04)',
'may_long'      => 'Накъыгъэ(05)',
'june'          => 'Мэкъуауэгъуэ(06)',
'july'          => 'Бадзэуэгъуэ(07)',
'august'        => 'ШыщхьэIу(08)',
'september'     => 'ФокIадэ(09)',
'october'       => 'Жэпуэгъуэ(10)',
'november'      => 'ЩакIуэгъуэ(11)',
'december'      => 'Дыгъэгъазэ(12)',
'january-gen'   => 'ЩIышылэ(01)',
'february-gen'  => 'Мазае(02)',
'march-gen'     => 'Гъатхэпэ(03)',
'april-gen'     => 'Мэлыжьыхь(04)',
'may-gen'       => 'Накъыгъэ(05)',
'june-gen'      => 'Мэкъуауэгъуэ(06)',
'july-gen'      => 'Бадзэуэгъуэ(07)',
'august-gen'    => 'ШыщхьэIу(08)',
'september-gen' => 'ФокIадэ(09)',
'october-gen'   => 'Жэпуэгъуэ(10)',
'november-gen'  => 'ЩакIуэгъуэ(11)',
'december-gen'  => 'Дыгъэгъазэ(12)',
'jan'           => 'ЩIш',
'feb'           => 'Мзе',
'mar'           => 'Гъп',
'apr'           => 'Мжьхь',
'may'           => 'Нкъ',
'jun'           => 'Мкъу',
'jul'           => 'Бдз',
'aug'           => 'ШIу',
'sep'           => 'Фдэ',
'oct'           => 'Жэп',
'nov'           => 'ЩкIу',
'dec'           => 'Дгъз',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Категория|Категориехэр}}',
'category_header'        => 'НапэкIуэцIхэр "$1" категорием',
'subcategories'          => 'КатегориещIагъ',
'category-empty'         => '"Мы категорием иджыпстукIэ зыри илъкъым."',
'listingcontinuesabbrev' => '(пыщэпхъэр)',
'index-category'         => 'Индекс зырат напэкIуэцIхэр',
'noindex-category'       => 'НапэкIуэцI индекс зыхуэмыщIахэр',

'mainpagetext' => "'''Вики-движок \"MediaWiki\"-р хъэрзынэ дыдэу тетха хъуащ.'''",

'about'         => 'Тетхыхьа',
'article'       => 'Статья',
'newwindow'     => '(щхьэгъумбжэщIэм)',
'cancel'        => 'ЩIегъуэжын',
'moredotdotdot' => 'АкIэ',
'mypage'        => 'Си напэкIуэцI',
'mytalk'        => 'Си псалъэмакъым и напэкIуэцI',
'navigation'    => 'Навигацэ',
'and'           => 'икIи',

# Cologne Blue skin
'qbfind'         => 'Лъыхъу',
'qbbrowse'       => 'Хэплъэн',
'qbedit'         => 'Хъуэжын',
'qbpageoptions'  => 'НапэкIуэцIым и теухуапхъэхэр',
'qbpageinfo'     => 'НапэкIуэцIым теухуауэ',
'qbmyoptions'    => 'Си теухуапхъэхэр',
'qbspecialpages' => 'СпецнапэкIуэцIхэр',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-delete'   => 'ИгъэкIыжын',
'vector-action-move'     => 'ЦIэр хъуэжын',
'vector-action-protect'  => 'Хъумэн',
'vector-action-undelete' => 'ЗыфIэгъэувэжын',
'vector-namespace-image' => 'Файл',
'vector-view-view'       => 'Еджэн',

'errorpagetitle'   => 'Щыуагъэ',
'returnto'         => '$1 напэІуэцІым кІуэжын.',
'tagline'          => 'Къыздихар {{grammar:genitive|{{SITENAME}}}}',
'help'             => 'Справкэ',
'search'           => 'Лъыхъу',
'searchbutton'     => 'Къэгъуэтын',
'searcharticle'    => 'ЕкIуэкIын',
'history'          => 'Тхыдэ',
'history_short'    => 'Тхыдэ',
'info_short'       => 'Информацэ',
'printableversion' => 'Печатым теухуа версие',
'permalink'        => 'Ссылкэ зызымыхъуэж',
'print'            => 'Печать',
'edit'             => 'Хъуэжын',
'create'           => 'ЩIын',
'editthispage'     => 'Мы напэкIуэцIыр хъуэжын',
'create-this-page' => 'Мыбы и напэкIуэцI щIын',
'delete'           => 'ТегъэкIын',
'protect'          => 'Хъумэн',
'protect_change'   => 'зэхъуэкIын',
'newpage'          => 'НапэкIуэцIыщIэ',
'talkpage'         => 'НапэкIуэцIым тепсэлъыхьын',
'talkpagelinktext' => 'Псалъэмакъ',
'personaltools'    => 'Уи щхьэ и Iэмэпсымэхэр',
'postcomment'      => 'РазделыщIэ',
'articlepage'      => 'Статьям хэплъэн',
'talk'             => 'Псалъэмакъ',
'views'            => 'Зыхэплъахэр',
'toolbox'          => 'Iэмэпсымэхэр',
'imagepage'        => 'Файлым и напэкIуэцIым еплъын',
'mediawikipage'    => 'Тхыгъэм и напэкIуэцIым еплъын',
'templatepage'     => 'Шаблоным и напэкIуэцIым хэплъэн',
'viewhelppage'     => 'ЩIэупщIэм и напэкIуэцI',
'categorypage'     => 'Категорием и напэкIуэцIым хэплъэн',
'otherlanguages'   => 'Адрейхэм ябзэхэмк1э',
'redirectedfrom'   => '($1 мыбы къикIащ)',
'redirectpagesub'  => 'НапэкIуэцI-уезыгъэкIуэкI',
'lastmodifiedat'   => 'Иужь дыдэу напэкIуэцIыр щахъуэжар: $1, $2 тэлайхэм ирихьэлIэу.',
'jumpto'           => 'Мыбы кIуэн:',
'jumptonavigation' => 'навигацэ',
'jumptosearch'     => 'лъыхъуэн',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{grammar:genitive|{{SITENAME}}}} -м теухуауэ',
'aboutpage'            => 'Project:Теухуауэ',
'copyright'            => 'Мыбы итыр къэутIыпщащ зытещIыхьари: $1.',
'copyrightpage'        => '{{ns:project}}:ЗиIэдакъэм и хуитыныгъэ',
'currentevents'        => 'КъекIуэкI Iуэхугъуэхэр',
'disclaimers'          => 'Жэуап Iыгъыныр зыщхьэщыхын',
'edithelp'             => 'Хъуэжыным и справкэ',
'edithelppage'         => 'Help:Хъуэжыным и дэIэпыкъуэгъу',
'helppage'             => 'Help:ДэIэпыкъуэгъу',
'mainpage'             => 'НапэкIуэцI нэхъыщхьэ',
'mainpage-description' => 'НапэкIуэцI нэхъыщхьэ',
'portal'               => 'ЗэцIыхубэхэр',
'privacy'              => 'Конфиденциальностым и политикэ',
'privacypage'          => 'Project:Конфиденциальностым и политикэ',

'badaccess' => 'Хуитыныгъэм щыуагъэ иIэщ',

'retrievedfrom'       => 'Къыздрахар: "$1"',
'newmessageslink'     => 'тхыгъэщIэхэр',
'newmessagesdifflink' => 'иужьрей зэхъуэкІыныгъэр',
'editsection'         => 'хъуэжын',
'editold'             => 'хъуэжын',
'viewsourceold'       => 'къызыхэкIа кодым хэплъэн',
'editlink'            => 'хъуэжын',
'viewsourcelink'      => 'къызыхэкIа кодым еплъын',
'editsectionhint'     => 'Секцэр хъуэжын: $1',
'toc'                 => 'Хэтхахэр',
'showtoc'             => 'гъэлъэгъуэн',
'hidetoc'             => 'гъэпщкIуын',
'viewdeleted'         => 'Ухэплэну $1?',
'site-rss-feed'       => '$1 — RSS-лентIэ',
'site-atom-feed'      => '$1 — Atom-лентIэ',
'page-rss-feed'       => '$1 — RSS-лентIэ',
'page-atom-feed'      => '$1 — Atom-лентIэ',
'red-link-title'      => '$1 (апхуэдэ напэкIуэцI щыIэкъым)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Статья',
'nstab-user'      => 'ЦIыхухэт',
'nstab-media'     => 'Медием и напэкIуэцI',
'nstab-special'   => 'Служебнэ напэкIуэцI',
'nstab-project'   => 'Проектым теухуауэ',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Тхыгъэ',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'ДэIэпыкъуэгъу',
'nstab-category'  => 'Категорие',

# General errors
'missingarticle-rev' => '(версие № $1)',
'viewsource'         => 'Хэплъэн',

# Login and logout pages
'yourname'                => 'Уи цIэр:',
'yourpassword'            => 'Пэроль:',
'yourpasswordagain'       => 'Иджыри зэ пэролыр:',
'remembermypassword'      => 'Сызэрихьэр компьютерым щыIыгъын (махуэу $1 щIимыгъуу)',
'login'                   => 'Системэм зыкъегъэцIыхуын',
'nav-login-createaccount' => 'Ихьэн/щIэуэ зитхэн',
'userlogin'               => 'Ихьэн/зыхэтхэн',
'logout'                  => 'ИкIыжын',
'userlogout'              => 'ИкIыжын',
'mailmypassword'          => 'ПэролыщIэ къеIыхын',
'loginlanguagelabel'      => 'Бзэ: $1',

# Password reset dialog
'resetpass'         => 'Пэролым и хъуэжын',
'oldpassword'       => 'Паролыжьыр:',
'newpassword'       => 'ПаролыщIэр:',
'retypenew'         => 'ПаролыщIэр иджырэ зэ итхэж:',
'resetpass_submit'  => 'Паролыр итхи ихьэ',
'resetpass_success' => 'Уи паролыр хъуэжа хъуащ! Иджыпсту системэм йохьэ...',

# Edit page toolbar
'bold_sample'     => 'Текстыр Iуву(жирнэу)',
'bold_tip'        => 'Текстыр Iуву(жирнэу)',
'italic_sample'   => 'Текстыр укъуэншауэ',
'italic_tip'      => 'Текстыр зыблэшауэ',
'link_sample'     => 'Ссылкэм ицIэр',
'link_tip'        => 'КІуэцІ техьэпІэ',
'extlink_sample'  => 'http://www.example.com link title ссылкэм и заголовэк',
'extlink_tip'     => 'Ссылкэ щIыб (зыщывмыгъэгъупщэ http:// префиксыр)',
'headline_sample' => 'Заголовокым и текст',
'headline_tip'    => 'ЕтІуанэ щхьагъ зиІэ фІэщыгъэцІэ',
'math_sample'     => 'Мыбдеж формулэ итхэ',
'math_tip'        => 'Математикэм тещIыхьауэ формулэ (LaTeX)',
'nowiki_tip'      => 'Вики-форматыр Iухын',
'image_tip'       => 'Файл кIуэцIылъу',
'media_tip'       => 'Файлым и ссылкэ',
'sig_tip'         => 'Уи Іэ тедзэмрэ заман къыщыхъуамрэ',
'hr_tip'          => 'Сатыр горизонталну (куэдрэ къэвмыгъэмэбэп)',

# Edit pages
'summary'                          => 'Хъуэжахэм тепсэлъыхь:',
'subject'                          => 'Темэ/Загэловок',
'minoredit'                        => 'МащIэу хъуэжа',
'watchthis'                        => 'Мы напэкIуэцIыр список узыхэплъэм хэлъхьэн',
'savearticle'                      => 'НапэкIуэцIыр хъумэн',
'preview'                          => 'Япэеплъ',
'showpreview'                      => 'Хэплъэн япэ щIыкIэ',
'showdiff'                         => 'ЗэхъуэкIыныгъэ хэлъхьахэр',
'anoneditwarning'                  => "'''Уэхьэхьей''': Зыкъебгъэц1ыхуакъым системэм. Уи IP-адресыр иритхэнущ  напэкIуэцIым и зэхъуэкIыныгъэ тхыдэм.",
'missingcommenttext'               => 'Кхъа, илъабжьэм итхэ уи тхыгъэр.',
'blockednoreason'                  => 'щхьэусыгъуэр итхакъым',
'accmailtitle'                     => 'Пэролыр егъэхьащ.',
'newarticle'                       => '(ЩIэрыпсу)',
'editing'                          => 'Хъуэжын: $1',
'editingsection'                   => 'Хъуэжын $1 (секцэр)',
'template-protected'               => '(хъумащ)',
'template-semiprotected'           => '(иныкъуэр хъумащ)',
'permissionserrorstext-withaction' => "«'''$2'''» Iуэхугъуэр пщIэну ухуиткъым, абы {{PLURAL:$1|и щхьэусыгъуэр|и щхьэусыгъуэхэр}}:",

# History pages
'viewpagelogs'           => 'Мы напэкIуэцIым и журналыр къэгъэлъэгъуэн',
'revisionasof'           => '$1 версие',
'previousrevision'       => '← Ипэ итыр',
'nextrevision'           => 'КъыкIэлъыкIуэр →',
'currentrevisionlink'    => 'КъекIуэкI версиер',
'cur'                    => 'къекIуэкIыр',
'next'                   => 'кIэлъыкIуэр',
'last'                   => 'ипэ.',
'page_first'             => 'ипэр',
'page_last'              => 'иужьдыдэр',
'history-fieldset-title' => 'Тхыдэм хэплъэн',
'history-show-deleted'   => 'ТегъэкIыжам фIэкI',
'histfirst'              => 'жьыдыдэхэр',
'histlast'               => 'Куэд мыщIахэр',
'historyempty'           => '(нэщIщ)',

# Revision deletion
'rev-delundel'               => 'зыIухын/зыIулъхьэн',
'rev-showdeleted'            => 'гъэлъэгъуэн',
'revdelete-show-file-submit' => 'НытIэ',
'revdel-restore'             => 'лъагъукIэр хъуэжын',

# Merge log
'revertmerge' => 'Зыхэдзын',

# Diffs
'history-title'           => '$1 - зэхъуэкIыныгъэм и тхыдэ',
'difference'              => '(Іэмалхэр зэрызыщхьэщыкІыр)',
'lineno'                  => 'Сатыр $1:',
'compareselectedversions' => 'Хэха версиехэр зэгъэпщэн',
'editundo'                => 'щIегъуэжын',

# Search results
'searchresults'             => 'Лъыхъум къигъуэтахэр',
'searchresults-title'       => '"$1" мыбы щхьа лъыхъум къигъуэтахэр',
'notitlematches'            => 'Зэтехуэ хэткъым напэкIуэцIхэм я цIэм',
'notextmatches'             => 'Зэтехуэ хэткъым напэкIуэцIхэм кІуэцІылъхэм',
'search-result-size'        => '$1 ({{PLURAL:$2|псалъэу $2|псалъэу $2|псалъэу $2}})',
'search-redirect'           => '(егъэкIуэкIын $1)',
'search-section'            => '(секцэ $1)',
'search-suggest'            => 'Мырагъэнщ, фи гу илъар: $1',
'search-interwiki-caption'  => 'Проект зэкъуэтхэр',
'search-interwiki-more'     => '(иджыри)',
'search-mwsuggest-enabled'  => 'чэнджэщ иIэу',
'search-mwsuggest-disabled' => 'чэнджэщыншэу',
'powersearch'               => 'Убгъуауэ лъыхъу',
'powersearch-legend'        => 'Убгъуауэ лъыхъу',
'powersearch-ns'            => 'ПространствэцIэм щылъыхъуэн',
'powersearch-field'         => 'Лъыхъу',

# Preferences page
'preferences'   => 'Теухуапхъэхэр',
'mypreferences' => 'Си теухуапхъэхэр',

# Groups
'group-sysop' => 'Адинистраторхэр',

# User rights log
'rightslog' => 'ЦIыхухэтым и хуитыныгъэхэм и журнал',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'мы напэкIуэцIыр хъуэжын',

# Recent changes
'recentchanges'                  => 'Хъуэжыныгъэ щIэхэр',
'recentchanges-feed-description' => 'Вики и иужьырей зэхъуэкIыныгъэхэм кIэлъылъын мыбы и потокым.',
'rcshowhideminor'                => '$1 мащІэу яхъуэжахэр',
'rcshowhidebots'                 => 'Боту $1',
'rcshowhideliu'                  => 'ЦIыхухэту, ихьауэ $1',
'rcshowhidemine'                 => '$1 схъуэжахэр',
'diff'                           => 'зэмылI.',
'hist'                           => 'тхыдэ',
'hide'                           => 'ГъэпщкIун',
'show'                           => 'Гъэлъэгъуэн',
'minoreditletter'                => 'м',
'newpageletter'                  => 'Н',
'boteditletter'                  => 'б',
'rc-enhanced-expand'             => 'Нэхъыбэ къэгъэлъагъуэн (JavaScript къегъэсэбэп)',
'rc-enhanced-hide'               => 'Гъэхуа жыІэхэр Іухын',

# Recent changes linked
'recentchangeslinked'      => 'ЗэпыщIа хъуэжыныгъэхэр',
'recentchangeslinked-page' => 'НапэкIуэцIым и цIэр:',
'recentchangeslinked-to'   => 'Пхэнжу, къэгъэлъэгъуэн напэкІуэцІхэм я хъуэжахэр, къыхэпха напэкІуэцІым къекІуэкІхэм',

# Upload
'upload'        => 'Файл илъхьэн',
'uploadlogpage' => 'Иралъхьам и къебжэкI',
'uploadedimage' => 'изылъхьар "[[$1]]"',

# File description page
'filehist'                  => 'Файлым и тхыдэ',
'filehist-help'             => 'Махуэ/зэманым текъузэ файлыр дэпщэщ дэуэду щытами уеплъынумэ',
'filehist-current'          => 'иджырер',
'filehist-datetime'         => 'Махуэ/Зэман',
'filehist-thumb'            => 'Миниатюрэ',
'filehist-user'             => 'ЦIыхухэт',
'filehist-dimensions'       => 'Объектым и инагъ',
'filehist-comment'          => 'Коментарэ',
'imagelinks'                => 'Файлым и ссылкэ',
'uploadnewversion-linktext' => 'Файлым и версиещIэ илъхьэн',

# Statistics
'statistics' => 'Статистикэ',

# Miscellaneous special pages
'prefixindex'  => 'Мы префикс зыIэ напэкIуэцIу хъуар',
'newpages'     => 'НапэкIуэцIыщIэхэр',
'move'         => 'ЦIэр хъуэжын',
'movethispage' => 'МынапэкIуэц1ым и цIэр хъуэжын',

# Book sources
'booksources'               => 'Тхылъ къыздиха',
'booksources-search-legend' => 'Тхылъым и хъыбар къэлъыхъуэн',
'booksources-go'            => 'Къэгъуэтын',

# Special:Log
'log' => 'Журналхэр',

# Special:AllPages
'allpages'       => 'НапэкIуэцIухъуар',
'alphaindexline' => '$1-м щыщIэдзауэ $2-м нэс',
'prevpage'       => 'Ипэ напэкIуэцIыр ($1)',
'allpagesfrom'   => 'МыбыкIэ щIидзэ напэкIуэцIхэр къихын:',
'allpagesto'     => 'Къихыныр къыщыгъэувыIэн:',
'allarticles'    => 'НапэкIуэцIухъуар',
'allpagessubmit' => 'ЩIын',

# Special:LinkSearch
'linksearch' => 'ЩIыб ссылкэ',

# Special:Log/newusers
'newuserlogpage'          => 'ЦIыхухэтхэм зэрызыратхэм и къебжэкI',
'newuserlog-create-entry' => 'ЦIыхухэтыщIэ',

# Special:ListGroupRights
'listgrouprights-members' => '(гупым и список)',

# E-mail user
'emailuser' => 'Тхыгъэ хуэтхын',

# Watchlist
'watchlist'     => 'Сызыхэплъэ списокыр',
'mywatchlist'   => 'СызыкIэлъыплъхэм и список',
'addedwatch'    => 'Список узыкIэлъыплъым хэтхащ',
'removedwatch'  => 'Хэплъэ къебжэкІым хэгъэкІыжащ',
'watch'         => 'КIэлъплъын',
'watchthispage' => 'НапэкIуэцIым кIэлъыплъын',
'unwatch'       => 'КIэлъымыплъын',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'СызыкIэлъыплъ списокым хэлъхьэн...',
'unwatching' => 'Сызык1элъыплъ списокым хэхын',

# Delete
'deletepage'            => 'НапэкIуэцIыр Iухын',
'actioncomplete'        => 'Лэжьыгъэр гъэзэщIащ',
'deletedarticle'        => 'тегъэкIыжащ «[[$1]]»',
'dellogpage'            => 'ТрагъэкIыжахэм и журнал',
'deletecomment'         => 'Щхьэусыгъуэ:',
'deleteotherreason'     => 'НэгъуэщI щхьэусыгъуэ/щIыгъупхъэ:',
'deletereasonotherlist' => 'НэгъуэщI щхьэусыгъуэ',

# Rollback
'rollbacklink' => 'къегъэзэн',

# Protect
'protectlogpage'              => 'Протектым и журнал',
'protectcomment'              => 'Щхьэусыгъуэ:',
'protectexpiry'               => 'Еухыр:',
'protect_expiry_invalid'      => 'Защитэм и зэман щиухар пц1ыщ.',
'protect_expiry_old'          => 'Щиуха зэманыр - блэкIам.',
'protect-default'             => 'Хъумаиншэхэр',
'protect-fallback'            => '"$1" хуитыныгъэ ухуещ',
'protect-level-autoconfirmed' => 'ЦIыхухэтыщIэмрэ щIэуэ къыхыхьахэмрэ щыхъумэн',
'protect-level-sysop'         => 'Администраторхэм фIэкIа',
'protect-summary-cascade'     => 'каскаднууэ',
'protect-expiring'            => 'йокIыр $1 (UTC)',
'protect-cascade'             => 'НапэкIуэцIыр хъумэн, напэкIуэцIым хэтхэри (каскаднэ хъумэныгъэ)',
'protect-cantedit'            => 'Мы напэкIуэцIым и защитэм и уровеныр пхъуэжыну ухуиткъым, уэ абы и хъуэжыным хуитыныгъэ ухуиIэкъым.',
'restriction-type'            => 'Хуитыныгъэ:',
'restriction-level'           => 'Хуитыныгъэм и уровень:',

# Undelete
'undeletelink'     => 'хэплъэн/ипIэ игъэувэжын',
'undeletedarticle' => 'зыщIыжар "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'ЦIэхэм и пространствэ:',
'invert'         => 'Къыхэхар пхэнж щІын',
'blanknamespace' => '(Нэхъыщхьэ)',

# Contributions
'contributions' => 'ЦIыхухэты хилъхьахэр',
'mycontris'     => 'Си хэлъхьэгъуэхэр',
'uctop'         => '(иужьыр)',
'month'         => 'Мазэм щыщIэдзауэ (икIи нэхъ пасэу):',
'year'          => 'Мы илъэсым щыщIэдзауэ (е нэхъпасэжу):',

'sp-contributions-blocklog' => 'блокировкэхэр',
'sp-contributions-username' => 'IP-адрес е цIыхухэтым и цIэр:',
'sp-contributions-submit'   => 'Къэгъуэтын',

# What links here
'whatlinkshere'            => 'Ссылкэхэр мыбдеж',
'whatlinkshere-title'      => '«$1» узыгъакІуэ напэкІуэцІхэр',
'whatlinkshere-page'       => 'НапэкIуэцI:',
'isredirect'               => 'НапэкIуэцI-уезыгъэкIуэкI',
'istemplate'               => 'хэгъэхьэныгъэ',
'isimage'                  => 'сурэтым и ссылкэ',
'whatlinkshere-links'      => '← ссылкэхэр',
'whatlinkshere-hideredirs' => '$1 уезыгъэкІуэкІхэр',
'whatlinkshere-hidetrans'  => '$1 хэтхэныгъэ',
'whatlinkshere-hidelinks'  => '$1 ссылкэхэр',
'whatlinkshere-filters'    => 'Фильтры',

# Block/unblock
'ipblocklist'      => 'IP-адрес учетнэ запись гъэбыдахэмрэ',
'blocklink'        => 'зэхуэщIын',
'change-blocklink' => 'блокировкэр зэхъуэкIын',
'contribslink'     => 'хэлъхьэгъуэ',
'blocklogpage'     => 'Блокировкэхэм я къебжэкІ',

# Move page
'movearticle'     => 'НапэкIуэцIым и цIэр хъуэжын',
'newtitle'        => 'ЩIэуэ и цIэр',
'move-watch'      => 'НапэкІуэцІыр узыкІэлъыплъ къебжэкІым хэтхэн',
'movepagebtn'     => 'НапэкIкэцIым и цIэр хъуэжын',
'pagemovedsub'    => 'НапэкIуэцIым и цIэр хъуэжащ',
'movepage-moved'  => "'''«$1» напэкIуэцIым и цIэр хъуэжащ мыпхуэдэу: «$2»'''",
'movedto'         => 'зэдзэкIащ мыпхуэдэу',
'movetalk'        => 'Мыбы тегъэщIа псалъэмакъым и напэкIуэцIым и цIэр хъуэжын',
'1movedto2'       => '«[[$1]]» - мыбы къикIыу «[[$2]]» - мыпхуэдэу и цIэр хъуэжын',
'1movedto2_redir' => '«[[$1]]»-м и цIэр хъуэжащ «[[$2]]» перенаправлением ищхьэкIэ',
'movelogpage'     => 'ЦIэхъуэжыным и журнал',
'movereason'      => 'Щхьэусыгъуэ:',
'revertmove'      => 'къегъэзэн',

# Export
'export' => 'НапэкIуэцIхэр экспорт щIын',

# Thumbnails
'thumbnail-more' => 'Ин щIын',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Си цIыхухэт напэкIуэцIыр',
'tooltip-pt-mytalk'              => 'Уи псалъэмакъ напэкIуэцIыр',
'tooltip-pt-preferences'         => 'Си теухуапхъэхэр',
'tooltip-pt-login'               => 'Мыбдеж системэм зиптхэфынущ, ауэ ар Iэмалыншэкъым.',
'tooltip-pt-logout'              => 'ИкIыжын',
'tooltip-ca-talk'                => 'НапэкIуэцIым итым тепсэлъыхьын',
'tooltip-ca-edit'                => 'НапэкIуэцIыр пхъуэж хъунущ. Пхъумэн ипэ къихуэу зэ еплъыж.',
'tooltip-ca-addsection'          => 'КъудамэщIэ щIэдзэн',
'tooltip-ca-viewsource'          => 'Мы напэкIуэцIыр и зэхъуэкIыныгъэр гъэбыдащ, ауэ ухуитщ къызыхэкIа текстым уеплъынууи копие пщIынууи',
'tooltip-ca-history'             => 'НапэкIуэцIым зэрызихъуэжам и журнал',
'tooltip-ca-protect'             => 'Хъуэжыным напэкIуэцIыр щыхъумэн',
'tooltip-ca-delete'              => 'Мы напэкIуэцIыр тегъэкIын',
'tooltip-ca-move'                => 'НапэкIуэцIым и цIэр хъуэжын',
'tooltip-ca-watch'               => 'Мы напэкIуэцIыр узыкІэлъыплъ къебжэкІым хэлъхьэн',
'tooltip-ca-unwatch'             => 'Мы напэкIуэцIыр узыкІэлъыплъ къебжэкІым хэхын',
'tooltip-search'                 => 'Мы псалъэр къэлъыхъуэн',
'tooltip-search-go'              => 'Мыпхуэдабзэ цIэ зиIэ напэкIуэцIым кIуэн',
'tooltip-search-fulltext'        => 'Мы текстыр зыхэт напэкIуэцIхэр къэгъуэтын',
'tooltip-n-mainpage'             => 'НапэкIуэцI нэхъыщхьэм кIуэн',
'tooltip-n-mainpage-description' => 'НапэкIуэцI нэхъыщхьэм кIуэн',
'tooltip-n-portal'               => 'Проетым теухуауэ, уэ епщIэфынур, дэнэ сыт щыIэми',
'tooltip-n-recentchanges'        => 'Иужьырей зэхъуэкIыныгъэхэм и список',
'tooltip-n-randompage'           => 'ЗэрамыщIэкIэ хэха напэкIуэцI еплъын',
'tooltip-n-help'                 => 'Проектым и дэIэпыкъуэгъу «{{SITENAME}}»',
'tooltip-t-whatlinkshere'        => 'Мы напэкIуэцым и цIэр къизыIуу хъуам  и список',
'tooltip-t-recentchangeslinked'  => 'Мы напэкIуэц1ым зызэхуигъазэ напэкIуэцIхэм и иужьрей зэхъуэкIыныгъэхэр',
'tooltip-feed-rss'               => 'НапэкІуэцІым щхьа RSS пыщІэн',
'tooltip-feed-atom'              => 'НапэкІуэцІым щхьа Atom пыщІэн',
'tooltip-t-emailuser'            => 'Мы цIыхухэтым и e-mail хуэтхын',
'tooltip-t-upload'               => 'Сурэт, уэрэд сыт зыгуэр илъхьэн',
'tooltip-t-specialpages'         => 'Служебнэ напэкIуэцIхэм и список',
'tooltip-t-print'                => 'НапэкIуэцIым и версие, печатым щхьа',
'tooltip-ca-nstab-main'          => 'Статьям кIуэцIылъыр',
'tooltip-ca-nstab-user'          => 'ЦIыхухэтым и напэкIуэцIыр къэгъэлъэгъуэн',
'tooltip-ca-nstab-project'       => 'НапэкІуэцІым и проект',
'tooltip-ca-nstab-image'         => 'Файлым и напэкIуэцI',
'tooltip-ca-nstab-category'      => 'Категорием и напэкIуэцI',
'tooltip-minoredit'              => 'ЗэхъуэкІыныгъэр жьгъейуэ бжын',
'tooltip-save'                   => 'Фи зэхъуэкIыныгъэхэр хъумэн',
'tooltip-preview'                => 'НапэкІуэцІым и япэеплъ, пхъумэн ипэ къэгъэсэбэп!',
'tooltip-watch'                  => 'Мы напэкIуэцIыр узыкІэлъыплъ къебжэкІым хэлъхьэн',
'tooltip-rollback'               => 'Зы текъузэкIэ иужьрей зэхъуэкIыныгъэхэр зыщIам и щIахэм къегъэзэжын',

# Browsing diffs
'nextdiff' => 'КIэлъыкIуэ хъуэжыгъэр →',

# Media information
'file-nohires'         => '<small>Ин плъыфэу къэгъэлъэгъуэн щыIэкъым.</small>',
'show-big-image'       => 'Сурэтыр нэхъ къабзэу',
'show-big-image-thumb' => '<small>Япэеплъым и инагъ: пиксел: $1 × $2</small>',

# Metadata
'metadata'        => 'Метаданнэхэр',
'metadata-fields' => 'Метаданнэхэр, мыбы кърибжэкІхэр къызэрыгуэкІыу сурэтым и напэкІуэцІым къщридзэнущ, адрейхэр гъэпщкІуау щытынущ.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally' => 'Файлыр хъуэжын, нэгъуэщI программэ и сэбэпкIэ',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'псори',
'namespacesall' => 'псори',
'monthsall'     => 'псори',

# Watchlist editing tools
'watchlisttools-view' => 'Списокым хэт напэкIуэцIхэм щыщу хъуэжахэр',
'watchlisttools-edit' => 'Еплъын/хъуэжын списокыр',
'watchlisttools-raw'  => 'Текст хуэдэу хъуэжын',

# Special:SpecialPages
'specialpages' => 'СпецнапэкIуэцIхэр',

);
