<?php
/** Lezghian (лезги)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amikeco
 * @author Andrijko Z.
 * @author Aslan4ik
 * @author Cekli829
 * @author MF-Warburg
 * @author Migraghvi
 * @author Namik
 * @author Nemo bis
 * @author Ole Yves
 * @author Reedy
 * @author Soul Train
 */

$fallback = 'ru';

$messages = array(
# User preference toggles
'tog-underline'               => 'ЭлячIунрин кIаникай цIар чIугун',
'tog-justify'                 => 'Ччинин гьяркьуьвилихъ текст дуьзрун',
'tog-hideminor'               => 'Мукьвара хьайи дегишвилера авай гъвечIи дуьзар хъувунар чуьнуьхун',
'tog-hidepatrolled'           => 'Мукьвара хьайи дегишвилера авай къаравулвал авунвай дуьзар хъувунар чуьнуьхун',
'tog-newpageshidepatrolled'   => 'ЦIийи ччинрин сиягьда къаравулвал авунвай ччинар чуьнуьхун',
'tog-extendwatchlist'         => 'ЧӀехи сиягь килигунин, кутазвай вири дегишунар, амма са эхирбур туш',
'tog-usenewrc'                => 'Мукьвара хьайи масакIавилерин ччина ва вилив хуьнин сиягьда  дуьзар хъувунар кIеретIриз ччара авун. (JavaScript герекзава)',
'tog-numberheadings'          => 'Кьилин цӀарариз автоматдаказ номерар эцигун',
'tog-showtoolbar'             => 'Дуьзар хъувунин алатрин кьвати къалура (JavaScript)',
'tog-editondblclick'          => 'Ччинар кьве тIампIуналди дуьзар хъувун (JavaScript герекзава)',
'tog-editsection'             => 'Пай [дуьзар хъувун] патал элячIун къалура',
'tog-editsectiononrightclick' => 'Пайдин кьилинцIардиз эрчIи патан тIампI авуна пайдин дуьзар хъувуниз мумкинвал гун (JavaScript герекзава)',
'tog-showtoc'                 => 'Къенеавайбурун сиягь къалурун (3-й гзаф кьилинцӀарар авай ччинар патал)',
'tog-rememberpassword'        => 'И браузерда зи логин рикlел хуьхь (лап гзаф $1 {{PLURAL:$1|югъ|йикъар}})',
'tog-watchcreations'          => 'За туькIуьрнавай ччинар зи гуьзетунин сиягьдиз алава авун',
'tog-watchdefault'            => 'За дуьзар хъувунвай ччинар зи гуьзетунин сиягьдиз алава авун',
'tog-watchmoves'              => 'За тIвар эхцигай ччинар зи гуьзетунин сиягьдиз алава авун',
'tog-watchdeletion'           => 'За алуднавай ччинар зи гуьзетунин сиягьдиз алава авун',
'tog-minordefault'            => 'Авайвилелди, вири дуьзар хъувунар гъвечIи дуьзар хъувунар хьиз лишан авун',
'tog-previewontop'            => 'Сифтедин килигун дуьзар хъувундин дакIардин вилик эцига',
'tog-previewonfirst'          => 'Дуьзар хъувундиз эгечIайла сифтедин килигун къалурун',
'tog-enotifwatchlistpages'    => 'Зи гуьзетунин ччин масакIа хьайила заз эмейл ракъура.',
'tog-enotifusertalkpages'     => 'КЬилди жуван веревирдрин ччина хьанвай дегишвилерикай э-почтадиз чар ракъурун.',
'tog-oldsig'                  => 'Алай къул:',
'tog-showhiddencats'          => 'Чуьнуьхай категорияр къалурун',

'underline-always'  => 'Гьамиша',
'underline-never'   => 'Садрани',
'underline-default' => 'Браузердин низамарунар кардик кутун',

# Font style option in Special:Preferences
'editfont-style'     => 'Дуьзар хъувунин чкадин шрифтдин жуьре',
'editfont-default'   => 'Браузердин низамарунрикай шрифт',
'editfont-monospace' => 'Моногьяркьуьвилер авай шрифт',
'editfont-sansserif' => 'КЬацI авачир шрифт',
'editfont-serif'     => 'КьацI авай кхьин',

# Dates
'sunday'        => 'Гьяд',
'monday'        => 'Ислен',
'tuesday'       => 'Саласа',
'wednesday'     => 'Арбе',
'thursday'      => 'Хемис',
'friday'        => 'Жуьмя',
'saturday'      => 'Киш',
'sun'           => 'Гьяд',
'mon'           => 'Исл',
'tue'           => 'Сал',
'wed'           => 'Aрб',
'thu'           => 'Xем',
'fri'           => 'Жум',
'sat'           => 'Киш',
'january'       => 'гьер (январь)',
'february'      => 'эхен (февраль)',
'march'         => 'ибне (март)',
'april'         => 'нава (апрель)',
'may_long'      => 'тӀул (май)',
'june'          => 'кьамуг (июнь)',
'july'          => 'чиле (июль)',
'august'        => 'пахун (август)',
'september'     => 'мара (сентябрь)',
'october'       => 'баскӀум (октябрь)',
'november'      => 'цӀехуьл (ноябрь)',
'december'      => 'фундукӀ (декабрь)',
'january-gen'   => 'гьер (январдиз)',
'february-gen'  => 'эхен (февралдиз)',
'march-gen'     => 'ибне (мартдиз)',
'april-gen'     => 'нава (апрелдиз)',
'may-gen'       => 'тӀул (майдиз)',
'june-gen'      => 'кьамуг (июндиз)',
'july-gen'      => 'чиле (июлдиз)',
'august-gen'    => 'пахун (августдиз)',
'september-gen' => 'мара (сентябрдиз)',
'october-gen'   => 'баскӀум (октябрдиз)',
'november-gen'  => 'цӀехуьл (ноябрдиз)',
'december-gen'  => 'фундукӀ (декабрдиз)',
'jan'           => 'гьер (январь)',
'feb'           => 'эхен (февраль)',
'mar'           => 'ибне (март)',
'apr'           => 'нава (апрель)',
'may'           => 'тӀул (май)',
'jun'           => 'кьамуг (июнь)',
'jul'           => 'чиле (июль)',
'aug'           => 'пахун (август)',
'sep'           => 'мара (сентябрь)',
'oct'           => 'баскӀум (октябрь)',
'nov'           => 'цӀехуьл (ноябрь)',
'dec'           => 'фандукl (декабрь)',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Категория|Категории}}',
'category_header'                => '"$1" категориядин ччинар',
'subcategories'                  => 'агъакатегорияр',
'category-media-header'          => '"$1" категоридин медиа',
'category-empty'                 => "''Алай чIава и категория ичIи я.\"",
'hidden-categories'              => '{{PLURAL:$1|Чуьнуьхай категория |Чуьнуьхай категорияр }}',
'hidden-category-category'       => 'Чуьнуьхай категорияр',
'category-subcat-count'          => '{{PLURAL:$2|И категорияда анжах гуьгъуьна авай подкатегория ава.|$2-кай {{PLURAL:$1|подкатегория|$1 подкатегория}} къалурнава }}',
'category-subcat-count-limited'  => 'И категорияда {{PLURAL:$1|$1 агъакатегория|$1 агъакатегорияр|$1 подкатегорий}} ава.',
'category-article-count'         => '{{PLURAL:$2|И категорияда анжах гуьгъуьна авайди ччин ава |$2-кай къалурнавай {{PLURAL:$1|ччин|$1 ччин}} гьа а категориядин ччин я}}',
'category-article-count-limited' => 'И категорияда {{PLURAL:$1|$1 ччин}} ава.',
'category-file-count'            => '{{PLURAL:$2|И категорияда анжах гуьгъуьна авайди файл ава |$2-кай къалурнавай {{PLURAL:$1|файл|$1 файлар}} гьа а категориядин файл я}}',
'category-file-count-limited'    => 'И категорияда {{PLURAL:$1|$1 файл}} ава.',
'listingcontinuesabbrev'         => '(кьатI)',
'index-category'                 => 'Индексавунвай ччинар',
'noindex-category'               => 'Индекстежезвай ччин',
'broken-file-category'           => 'ЧIуру файлдин элячIунар авай ччинар',

'about'         => 'Гьакъиндай',
'article'       => 'Макъала',
'newwindow'     => '(цlийи дакlарда ахъа жезва)',
'cancel'        => 'Гьич авун',
'moredotdotdot' => 'Мад...',
'mypage'        => 'Зин чар',
'mytalk'        => 'Зи веревирдрин ччин',
'anontalk'      => 'И IP-адресдиз талукь веревирд.',
'navigation'    => 'Навигация',
'and'           => '&#32;ва',

# Cologne Blue skin
'qbfind'         => 'Жугъун',
'qbbrowse'       => 'Килигун',
'qbedit'         => 'Дегишарун',
'qbpageoptions'  => 'Ччинин низамарунар',
'qbpageinfo'     => 'Ччиникай малумат',
'qbmyoptions'    => 'Зи ччинар',
'qbspecialpages' => 'Кьетlен хъувун',
'faq'            => 'Фад-фад гузвай жузунар (ФГЖ)',
'faqpage'        => 'Project:ФГС',

# Vector skin
'vector-action-addsection'       => 'Тема алава авун',
'vector-action-delete'           => 'Алудун',
'vector-action-move'             => 'ТIвар эхцигун',
'vector-action-protect'          => 'Хуьн',
'vector-action-undelete'         => 'ТуькIуьр хъувун',
'vector-action-unprotect'        => 'Хуьн дегишарун',
'vector-simplesearch-preference' => 'Гегьенш жагъурунин рикIел гъун кутун (кьилди "Вектор" акунар патал)',
'vector-view-create'             => 'Туькlуьрун',
'vector-view-edit'               => 'Расун',
'vector-view-history'            => 'Тарихдиз килигун',
'vector-view-view'               => 'Кlелун',
'vector-view-viewsource'         => 'Чешме къалурун',
'actions'                        => 'Крар',
'namespaces'                     => 'Тlварарин генгвилер',
'variants'                       => 'Жуьреяр',

'errorpagetitle'    => 'ГъалатI',
'returnto'          => '$1 ччиниз элкъвена хтун',
'tagline'           => '{{SITENAME}} Cайтдихъай',
'help'              => 'Куьмек',
'search'            => 'Жугъурун',
'searchbutton'      => 'Жагъурун',
'go'                => 'ЭлячIун',
'searcharticle'     => 'ЭлячIун',
'history'           => 'Ччинин тарих',
'history_short'     => 'Тарих',
'updatedmarker'     => 'Зи эхиримжи гьахьун гуьгуьнлай цIийи авунва',
'printableversion'  => 'Басма авун патал жуьре',
'permalink'         => 'Гьамишан элячIун',
'print'             => 'Басма авун',
'view'              => 'Килигун',
'edit'              => 'Дуьзар хъувун',
'create'            => 'Туькlуьрун',
'editthispage'      => 'И ччин дуьзар хъувун',
'create-this-page'  => 'И ччин туькIуьрун',
'delete'            => 'Алудун',
'deletethispage'    => 'И ччин алудун',
'undelete_short'    => '$1 {{PLURAL:$1|дуьзар хъувун|дуьзар хъувунар}} туьхкIуьрун',
'viewdeleted_short' => '{{PLURAL:$1|дуьзар хъувуниз|$1 дуьзар хъувунриз}} килигун',
'protect'           => 'Xуьн',
'protect_change'    => 'масакIа авун',
'protectthispage'   => 'И ччин блокарун',
'unprotect'         => 'Хуьн дегишарун',
'unprotectthispage' => 'И ччинин хуьн дегишарун',
'newpage'           => 'ЦIийи ччин',
'talkpage'          => 'И ччин веревирдун',
'talkpagelinktext'  => 'Рахун',
'specialpage'       => 'Куьмекчи ччин',
'personaltools'     => 'Кьилди вичин алатар',
'postcomment'       => 'ЦIйий пай',
'articlepage'       => 'Къене авайбурун ччиндиз килигун',
'talk'              => 'Веревирд авун',
'views'             => 'Килигунар',
'toolbox'           => 'Алатрин кьвати',
'userpage'          => 'Уртахдин ччиниз килигун',
'projectpage'       => 'Проектдин ччиниз килигун',
'imagepage'         => 'Файлдин ччиниз килигун',
'mediawikipage'     => 'Чардин ччиниз килигун',
'templatepage'      => 'Чешнедин ччиниз килигун',
'viewhelppage'      => 'Куьмекдин ччиниз килигун',
'categorypage'      => 'Категориядин ччиниз килигун',
'viewtalkpage'      => 'Веревирдриз килигун',
'otherlanguages'    => 'Маса чIаларал',
'redirectedfrom'    => '($1-кай рахкъурнава )',
'redirectpagesub'   => 'Рахкъурунин ччин',
'lastmodifiedat'    => 'Ччинин эхиримжи масакIа хьун:  $1,  $2',
'protectedpage'     => 'Хвенвай ччин',
'jumpto'            => 'ЭлячIун иниз:',
'jumptonavigation'  => 'Навигаци',
'jumptosearch'      => 'Жугъурун',
'pool-queuefull'    => 'ТIалабар кIватзавайди ацIа я',
'pool-errorunknown' => 'Малумтушир гъалатI',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => ' {{SITENAME}}кай',
'aboutpage'            => 'Project:Гьакъиндай',
'copyright'            => 'КЪене авайбур $1 жугъуриз жеда.',
'copyrightpage'        => '{{ns:project}}: Автордин ихтияр',
'currentevents'        => 'Алай вакъиаяр',
'currentevents-url'    => 'Project:Алай вакъиаяр',
'disclaimers'          => 'Жавабдарвал хивяй акъудун',
'disclaimerpage'       => 'Project:Жавабдарвал хивяй акъудун',
'edithelp'             => 'Дуьзар хъувун патал куьмек',
'edithelppage'         => 'Help:Дуьзар хъувун',
'helppage'             => 'Help:Къене авайбур',
'mainpage'             => 'Кьилин ччин',
'mainpage-description' => 'Кьилин ччин',
'policy-url'           => 'Project:Къайдаяр',
'portal'               => 'КIапIалдин портал',
'portal-url'           => 'Project:КIапIалдин портал',
'privacy'              => 'Чинебанвилин сиясат',
'privacypage'          => 'Project:Чинебанвилин политика',

'badaccess' => 'ГЬатунин гъалатlдин',

'ok'                      => 'ОК',
'retrievedfrom'           => 'Чешне "$1" я',
'youhavenewmessages'      => 'Квез  $1 ($2) атанва.',
'newmessageslink'         => 'цlийи чарар',
'newmessagesdifflink'     => 'Эхиримжи масакIавилер',
'youhavenewmessagesmulti' => '"$1"-да квез цIийи чарар атанва.',
'editsection'             => 'Расун',
'editold'                 => 'Дуьзар хъувун',
'viewsourceold'           => 'сифте кьилин коддиз килига',
'editlink'                => 'Дуьзар хъувун',
'viewsourcelink'          => 'Сифте кьилин коддиз килига',
'editsectionhint'         => 'Пай дуьзар хъувун: $1',
'toc'                     => 'Къене авайбур',
'showtoc'                 => 'къалурун',
'hidetoc'                 => 'чуьнуьхун',
'collapsible-collapse'    => 'Алчудрун',
'collapsible-expand'      => 'Гегьеншрун',
'thisisdeleted'           => '$1 килигун ва я туькIуьр хъувун?',
'viewdeleted'             => '$1 килигун?',
'feedlinks'               => 'Хулан жуьре',
'site-rss-feed'           => '$1 — RSS-зул',
'site-atom-feed'          => '$1 -  атом-зул',
'page-rss-feed'           => '"$1" РСС Xуьрек',
'page-atom-feed'          => '"$1" Атом-зул',
'red-link-title'          => '$1 (ихьтин ччин авайди туш)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Ччин',
'nstab-user'      => 'Уртахдин ччин',
'nstab-media'     => 'Медия ччин',
'nstab-special'   => 'Куьмекчи ччин',
'nstab-project'   => 'Проектдин ччин',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Малумат',
'nstab-template'  => 'Чешне',
'nstab-help'      => 'Куьмекдин ччин',
'nstab-category'  => 'Категория',

# Main script and global functions
'nosuchaction'      => 'Ихьтин кар авайд ттуш',
'nosuchspecialpage' => 'Ихьтин куьмекчи ччин авайд ттуш',

# General errors
'error'              => 'Гъалатl',
'databaseerror'      => 'Ганайбурун базадин гъалатI',
'missing-article'    => 'Малуматрин базада, квез герек авай "$1" $2 тIвар алай ччиндин текст жагъанвач

Ихьтин гьал адет яз алуднавай ччинин масакIавилерин тарихдиз цlуру  рекьяй элячlайла арадал къвезва.

Себеб ам туштlа, виридалайни мумкин тирди куьне программада гъалатl жугъурун я
Тавакъу ийида, URL  къалурна адакай   [[Special:ListUsers/sysop|администратордиз]] хабар це.',
'missingarticle-rev' => '(жуьре#: $1)',
'internalerror'      => 'КЪенепатан гъалатI',
'internalerror_info' => 'КЪенепатан гъалатI $1',
'filecopyerror'      => 'Kopi aqudiz jezach fail "$1"  "$2" - diz.',
'filerenameerror'    => '«$1» файл  «$2» -з тIвар эхцигиз жезвач..',
'badarticleerror'    => 'И ччина и кар кьилиз акъудиз мумкин ттуш.',
'cannotdelete-title' => ' "$1" ччин алудиз жезвач',
'badtitle'           => 'Ииже текъвер тIвар',
'badtitletext'       => 'ТIалабзавай ччин  я вичел амал алачир я,  я  ичIи я,  ва я чIаларарадин ва я викиарадин кьилин цlарар чlурукlа къалурнава. Мумкин я, кьилин цlарара сад ва я адалай гзаф рехъ гун виже текъвер символар кардик кутунвайди я.',
'viewsource'         => 'Килигун',
'viewsource-title'   => '$1 патал чешмедиз килигун',
'actionthrottled'    => 'Фадвилин сергьятар',

# Virus scanner
'virus-unknownscanner' => 'Малумтушир антивирус',

# Login and logout pages
'yourname'                => 'Уртахдин тlвар',
'yourpassword'            => 'Парол',
'yourpasswordagain'       => 'Парол кхьин хъувун:',
'remembermypassword'      => 'И браузерда зи логин рикlел хуьхь (лап гзаф $1 {{PLURAL:$1|югъ|йикъар}})',
'yourdomainname'          => 'Куь домен',
'login'                   => 'Гьахьун',
'nav-login-createaccount' => 'Гьахьун/аккаунт туькlуьрун',
'loginprompt'             => 'Системадиз гьахьун патал "куки" -яр куькlуьрна кIанзава',
'userlogin'               => 'ГЬахьун/аккаунт туькlуьрун',
'userloginnocreate'       => 'Гьахьун',
'logout'                  => 'ЭкъечIун',
'userlogout'              => 'ЭкъечIун',
'notloggedin'             => 'Куьн гьахьнавач',
'nologin'                 => 'Квез аккаунт авачни? $1.',
'nologinlink'             => 'Аккаунт туькlуьрун',
'createaccount'           => 'Аккаунт туькlуьрун',
'gotaccount'              => 'Квез аккаунт авани?$1',
'gotaccountlink'          => 'Гьахьун',
'userlogin-resetlink'     => 'Гьахьунин куьлуь-шуьлуьяр рикlел алатнани?',
'createaccountmail'       => 'Э-чар галаз',
'createaccountreason'     => 'Себеб:',
'loginerror'              => 'ГЬахьунин гъалатI',
'createaccounterror'      => 'И аккаунт туькIуьриз мумкин ттуш: $1',
'loginsuccesstitle'       => 'Агалкьунралди гьахьун',
'wrongpasswordempty'      => 'Тавакъу ийида, ичIи тушир парол ттур.',
'mailmypassword'          => 'ЦIийи парол Э-мейлдиз къачун',
'mailerror'               => 'Чар ракъурунин гъалатI: $1',
'emailconfirmlink'        => 'Куь электрон почтунин адрес тестикьун.',
'accountcreated'          => 'Аккаунт туькIуьрнава',
'usernamehasherror'       => 'Уртахдин тIвар "диез"дин лишан квачиз хьана кIанзава',
'loginlanguagelabel'      => 'ЧIал: $1',

# Change password dialog
'resetpass'                 => 'Куьлег дегишарун',
'resetpass_header'          => 'Аккаунтдин парол дегишун',
'oldpassword'               => 'ЦIуру парол:',
'newpassword'               => 'ЦIийи парол:',
'retypenew'                 => 'Парол кхьин хъувун:',
'resetpass_submit'          => 'Парол эцигун ва гьахьун',
'resetpass_forbidden'       => 'Парол дегишиз мумкин ттуш',
'resetpass-submit-loggedin' => 'Парол дегишарун',
'resetpass-submit-cancel'   => 'Гьич авун',

# Special:PasswordReset
'passwordreset'              => 'Парол алудна гадрун',
'passwordreset-legend'       => 'Парол алудна гадрун',
'passwordreset-username'     => 'Уртахдин тlвар:',
'passwordreset-domain'       => 'Домен:',
'passwordreset-email'        => 'E-mail адрес',
'passwordreset-emailelement' => 'Уртахдин тIвар: $1
Вахтуналди тир пароль: $2',

# Special:ChangeEmail
'changeemail'        => 'Э-почта дегишарун',
'changeemail-none'   => '(садни)',
'changeemail-submit' => 'E-адрес дегишун',
'changeemail-cancel' => 'Гьич авун',

# Edit page toolbar
'bold_sample'     => 'ЯцIу текст',
'bold_tip'        => 'Къалин текст',
'italic_sample'   => 'Курсивдин текст',
'italic_tip'      => 'Курсивдин текст',
'link_sample'     => 'Элячlунин кьилин цlар',
'link_tip'        => 'Къенепатан элячlун',
'extlink_sample'  => 'http://www.example.com элячlунин кьилин цlар',
'extlink_tip'     => 'Къецепатан элячlун ( http:// префикс рикlел хуьх)',
'headline_sample' => 'Кьилинцlарцlин текст',
'headline_tip'    => '2-й дережадин кьилин цlар',
'nowiki_sample'   => 'Формат тавунвай текст иниз тур',
'nowiki_tip'      => 'Викидин форматун гьисаба кьамир',
'image_tip'       => 'Ттунвай файл',
'media_tip'       => 'Файлдин элячlун',
'sig_tip'         => 'Куь къулни вахт',
'hr_tip'          => 'Къаткай цlар (фад-фад кардик кутумир )',

# Edit pages
'summary'                          => 'Нетижа:',
'subject'                          => 'Тема/кьилинцIар',
'minoredit'                        => 'ГъвечIи дуьзар хъувун',
'watchthis'                        => 'И ччин гуьзетун',
'savearticle'                      => 'Ччин хуьн',
'preview'                          => 'Сифтедин килигун',
'showpreview'                      => 'Сифтедин килигун къалурун',
'showlivepreview'                  => 'Фад сифтедин килигун',
'showdiff'                         => 'МасакIавилер къалурун',
'anoneditwarning'                  => "'''Дикъет:''' Куьне системадиз жув вуж ятIа лагьанвач. Куь IP-адрес и ччинин масакIавилерин тарихдиз  кхьида.",
'summary-preview'                  => 'Сифте килигун паталди:',
'subject-preview'                  => 'КьилинцIарцIин сифтедин килигун:',
'blockedtitle'                     => 'Иштиракчи блокарнава',
'blockednoreason'                  => 'Са себебни ганвач',
'nosuchsectiontitle'               => 'Пай жугъуриз жезвач',
'loginreqtitle'                    => 'Логин герекзава',
'loginreqlink'                     => 'гьахьун',
'accmailtitle'                     => 'Парол ракъурнава.',
'newarticle'                       => '(ЦIийи)',
'newarticletext'                   => 'Куьне гьеле авачир ччиниз элячlнава.  
Ам туькlуьрун патал агъадихъ галай дакlарда текст гьадра. (гегьеншдиз [[{{MediaWiki:Helppage}}|куьмекдин ччина]] килигиз жеда).
Куьне инал гъалатlдин гъиляй элячlнаватlа, кьу браузердин "кьулухъ"" дуьгмедал илиса.',
'noarticletext'                    => 'Исятда и  ччинда са текстни авач.
Квевай [[Special:Search/{{PAGENAME}}| и тlвар алай ччин]] муькуь ччинра жугъуриз,
<span class="plainlinks"> [{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} журналрин талукь тир кхьей затIар жугъуриз],
ва я [{{fullurl:{{FULLPAGENAME}}|action=edit}} и тlвар алай ччин туькIуьриз жеда] </span>.',
'noarticletext-nopermission'       => 'Исятда и  ччина са текстни авач.
Квевай [[Special:Search/{{PAGENAME}}| и тlвар алай ччин]] муькуь ччинра жугъуриз ва я
<span class="plainlinks"> [{{fullurl: {{# Special:Log}} | page = {{FULLPAGENAMEE}}}} журналрин талукь тир кхьей затIар жугъуриз] жеда.',
'blocked-notice-logextract'        => 'И уртах алайчIава блокарнава.
Агъадихъ блокарунин журналдикай эхиримжи кхьинар къалурнава:',
'previewnote'                      => "'''Рикlел хуьх хьи, им анжах сифтедин килигун я.'''  
Куь масакIавилер гьеле хвенвач!",
'editing'                          => '$1 Дуьзар хъувун',
'editingsection'                   => 'Дуьзар хъувун $1  (пай)',
'editingcomment'                   => '$1 дуьзар хъувун (цIийи пай)',
'editconflict'                     => 'Дуьзар хъувунрин акьунар: $1',
'yourtext'                         => 'Зи текст',
'yourdiff'                         => 'Тафаватар',
'copyrightwarning'                 => "Тавакъу ийида, фагьум ая хьи, {{SITENAME}}-диз кутунвай вири крариз $2 лицензиядин шартунал акъуднавайбур хьиз килигда. (гегьеншдиз $1-з килига). 
Квез куьне кхьенвайбур азаддаказ чкIун ва гьар са кас  патахъай дуьзар хъувун кIанзавачтIа, а кхьенвайбур иниз эцигмир.<br />
ГЬакIни, куьне тестикьзава хьи, кутазвай алавайрин автор кьун я, я тахьайтIа, куьне а алаваяр чпин къенеавайбур азад чкIунни дегишун ихтияр гузвай чешмедикай ччин къачунва.<br />
'''АВТОРДИН ИХТИЯР ХУЬЗВАЙ МАЛУМАТАР ИХТИЯР ГАЛАЧИЗ ЭЦИГМИР!'''",
'templatesused'                    => 'И ччина кардик кутунвай {{PLURAL:$1|Чешне|Чешнеяр}}:',
'templatesusedpreview'             => '{{PLURAL:$1|Шаблон|Шаблонар}},илемишзавай дуьз клигунра:',
'template-protected'               => '(хвенвай)',
'template-semiprotected'           => '(са кьадар хвенва)',
'hiddencategories'                 => 'И ччин {{PLURAL: $1 | чуьнуьхай категориядиз | $1 чуьнуьхай категорийриз}} талукь я:',
'permissionserrors'                => 'ГЬахьнин гъалатlар',
'permissionserrorstext-withaction' => 'Квез и {{PLURAL:$1|себебдалди|себебралди}} $2 йиз ихтияр авайд туш:',
'recreate-moveddeleted-warn'       => "'''Дикъет! Куьне виликда алуднавай ччин туьхкlуьриз алахъзава.'''
Квевай и ччинин туьхкlуьрунин гереквилиз килигиз тIалабзава.
Агъадихъ и ччинин алудун ва тIвар эхцигунин журнал къалурнава.",
'moveddeleted-notice'              => 'И ччин алуднава. 
Агъадихъ малумат патал и ччинин алудунин ва тIвар эхцигунин журнал къалурнава.',
'log-fulllog'                      => 'Вири журналдиз килигун',
'edit-conflict'                    => 'Дуьзар хъувунрин акьунар',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => "'''Дикъет:''' Кутазвай чешнейрин кьадар гзаф чIехи я.
Бязи чешнеяр кутадач.",
'post-expand-template-inclusion-category' => 'Кутунай чешнейрин кьадардилай виниз экъечIнавай ччинар',
'post-expand-template-argument-warning'   => "'''Дикъет:''' И ччина ахъайна гегьеншрун патал гзаф чlехи кьадар авай гьич тахьайтIа са чешнедин аргумент ава.
 Ахьтин аргументар тагана элячIнава.",
'post-expand-template-argument-category'  => 'Кими авунвай  чешнейрин аргументар авай ччинар',

# History pages
'viewpagelogs'           => 'И ччиниз талукь тир журналар къалура',
'nohistory'              => 'И ччиниз талукь тир дуьзар хъувунин тарих авайд ттуш.',
'currentrev'             => 'Алай жуьре',
'currentrev-asof'        => '$1 тарихдиз талукь тир алай жуьре',
'revisionasof'           => '$1 жуьре',
'revision-info'          => '$2 патал авунвай $1 тарихдин масакIавал',
'previousrevision'       => '←Вилик алатай жуьре',
'nextrevision'           => 'Мадни цlийи жуьре →',
'currentrevisionlink'    => 'Алай жуьре',
'cur'                    => 'алай',
'next'                   => 'къведайди',
'last'                   => 'вилик алатай',
'page_first'             => 'Садлагьайди',
'page_last'              => 'эхиримжи',
'histlegend'             => 'Тафаватдиз килигун: гекъигиз кlанзавайди жуьредин патав радио-кьватияр лишан ая ва  "ГЬалдун (Enter)"  ва я агъада авай дуьгмедиз илиса.<br />     
Гъавурда твазвайди: (алай)- алай жуьредикай тафават; (вилик фейи) - вилик фейи жуьредикай тафават; "гъ" - гъвечIи масакIавал.',
'history-fieldset-title' => 'Тарихдиз килигун',
'history-show-deleted'   => 'Анжах алуднавайбур',
'histfirst'              => 'Виридалайни цIуру',
'histlast'               => 'Мукьвара хьайи',
'historyempty'           => '(ичIи)',

# Revision feed
'history-feed-title'          => 'Дуьзар хъувунрин тарих',
'history-feed-item-nocomment' => '$1  $2-аз',

# Revision deletion
'rev-deleted-comment'        => 'Дуьзар хъувунин тегьерар кхьин алуднава',
'rev-deleted-user'           => '(иштиракчидин тIвар алуднава)',
'rev-deleted-event'          => '(къейд алуднава)',
'rev-delundel'               => 'къалурун/кIевирун',
'rev-showdeleted'            => 'къалурун',
'revdelete-show-file-submit' => 'Эхь',
'revdelete-hide-text'        => 'Ччинин и  жуьредин текст чуьнуьхун',
'revdelete-hide-image'       => 'Файлдин къенеавайбур чуьнуьхун',
'revdelete-hide-name'        => 'Карни адан объект чуьнуьхун',
'revdelete-radio-set'        => 'Эхь',
'revdelete-radio-unset'      => 'Ваъ',
'revdelete-log'              => 'Кар',
'revdel-restore'             => 'Аквадайвал масакIа авун',
'revdel-restore-deleted'     => 'Алуднавай жуьреяр',
'revdel-restore-visible'     => 'Аквадай масакIавилер',
'pagehist'                   => 'Ччинин тарих',
'deletedhist'                => 'Алудунин тарих',
'revdelete-reasonotherlist'  => 'Муькуь себеб',
'revdelete-edit-reasonlist'  => 'Алудунин себебар дуьзар хъувун',
'revdelete-offender'         => 'Автордин жуьре:',

# Suppression log
'suppressionlog' => 'КIевирунин журнал',

# History merging
'mergehistory-from'   => 'Сифте кьилин ччин:',
'mergehistory-into'   => 'Мураддин ччин',
'mergehistory-submit' => 'Дуьзар хъувунар сад авун',
'mergehistory-reason' => 'Себеб',

# Merge log
'mergelog'    => 'Сад авунин журнал',
'revertmerge' => 'Ччара авун',

# Diffs
'history-title'           => 'Masak\'avilerin q\'isa "$1"',
'difference'              => '(Жуьрейрин арада тафаватар)',
'lineno'                  => 'ЦIар $1:',
'compareselectedversions' => 'Хкягъай жуьреяр гекъигун',
'editundo'                => 'гьич авун',
'diff-multi'              => '({{PLURAL:$2|Са уртах|$2 уртахар}} патал авунвай {{PLURAL:$1|са арадин жуьре|$1 арадин жуьреяр}} къалурнавач)',

# Search results
'searchresults'                    => 'Къекъуьнрин нетижаяр',
'searchresults-title'              => '"$1" жугъура',
'searchresulttext'                 => '{{SITENAME}} къекъуьнихъай гегьенш малумат патал  [[{{MediaWiki:Helppage}}|{{int:малумат гудай пай}}]]диз килига.',
'searchsubtitle'                   => '[[Special:WhatLinksHere/$1|И тIварциз элячIзавай]]) [[:$1]] жугъуруниз талукь тир ([[Special:Prefixindex/$1| тIварцихъ галаз эгечIзавай ччинар]]',
'searchsubtitleinvalid'            => "Жугъурзавай: '''$1'''",
'titlematches'                     => 'Ччинрин тIварарин  ацалтунар',
'notitlematches'                   => 'Ччинрин тIварара ацалтунар авач',
'textmatches'                      => 'Ччинрин текстрин ацалтунар',
'notextmatches'                    => 'Авач чарчин кьил матчар',
'prevn'                            => 'Вилик фейи  {{PLURAL:$1|$1}}',
'nextn'                            => 'Гуьгъуьнин {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Вилик фейи  $1 {{PLURAL:$1|нетижа|нетижаяр}}',
'nextn-title'                      => 'КЪведай $1 {{PLURAL:$1|нетижа|нетижаяр}}',
'shown-title'                      => 'Ччина $1 {{PLURAL:$1|нетижа|нетижа}} къалурун',
'viewprevnext'                     => 'Килигун ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Жагъурунин низамарунар',
'searchmenu-exists'                => "'''И вики-проектда \"[[:\$1]]\" тlвар алай ччин ава.'''",
'searchmenu-new'                   => "'''И вики-проектда \"[[:\$1]]\" ччин туькlуьрун !'''",
'searchhelp-url'                   => 'Help:КЪене авайбур',
'searchprofile-articles'           => 'Асул ччинар',
'searchprofile-project'            => 'Куьмек гунин ва проектдин ччинар',
'searchprofile-images'             => 'Мультимедиа',
'searchprofile-everything'         => 'Вири',
'searchprofile-advanced'           => 'Гегьеншдиз',
'searchprofile-articles-tooltip'   => '$1-да къекъуьгъ',
'searchprofile-project-tooltip'    => '$1-да къекъуьгъ',
'searchprofile-images-tooltip'     => 'Файлар жугъура',
'searchprofile-everything-tooltip' => 'Вири ччинра къекъуьгъ (веревирдрин ччинар кваз)',
'searchprofile-advanced-tooltip'   => 'Ганвай тlварарин генгвилера къекъуьгъ',
'search-result-size'               => '$1 ({{PLURAL:$2|1 гаф|$2 гаф}})',
'search-result-category-size'      => '{{PLURAL:$1|1 элемент|$1 элементар}} ({{PLURAL:$2|1 агъакатегория|$2 агъакатегорияр}}, {{PLURAL:$3|1 файл|$3 файлар}})',
'search-redirect'                  => '(рахкъурун $1)',
'search-section'                   => '(пай $1)',
'search-suggest'                   => 'Мумкин я хьи, куьне им фикирда кьуна: $1',
'search-interwiki-caption'         => 'Мукьва проект',
'search-interwiki-default'         => '$1(жавабар)',
'search-interwiki-more'            => '(мадни)',
'search-mwsuggest-enabled'         => 'меслятар галаз',
'search-mwsuggest-disabled'        => 'меслятар галачиз',
'search-relatedarticle'            => 'Галкlанавай',
'searchrelated'                    => 'Галкlанавай',
'searchall'                        => 'вири',
'showingresultsheader'             => "'''$4'''  патал {{PLURAL:$5|'''$3''' - кай  '''$1''' нетижа|'''$3''' - кай  '''$1 - $2''' нетижаяр}}",
'nonefound'                        => "'''Асулзава''': Са шумуд тlарар жагъурзава хвенайвал.
Клига префикс \"вири\" жагъурун паталди кхьинар (ихтилат ва рахунин чарар, шаблонар ва масабу), ва ишлемишна кlанзавай тlарар префикс патала.",
'search-nonefound'                 => 'Тlалабдив кьадай са нетижани жагъанвач.',
'powersearch'                      => 'Гегьенш жугъурун',
'powersearch-legend'               => 'Гегьенш жугъурун',
'powersearch-ns'                   => 'Жугъурун тlварарин генгвилера:',
'powersearch-redir'                => 'Рахкъурунар къалура',
'powersearch-field'                => 'Идаз жагъурун',
'powersearch-togglelabel'          => 'Акун',
'powersearch-toggleall'            => 'Вири',
'powersearch-togglenone'           => 'Садни',

# Quickbar
'qbsettings'               => 'КЪекъуьнрин панел',
'qbsettings-none'          => 'Садни',
'qbsettings-fixedleft'     => 'Чапла патахъай юзан тийир',
'qbsettings-fixedright'    => 'ЭрчIи патахъай юзан тийир',
'qbsettings-floatingleft'  => 'Чапла патаз алгъурзава',
'qbsettings-floatingright' => 'ЭрчIи патаз алгъурзава',

# Preferences page
'preferences'               => 'Туькlуьрун',
'mypreferences'             => 'Зи низамарунар',
'prefs-edits'               => 'Дьузар хъувунрин кьадар',
'prefsnologin'              => 'Куьне гьахьнавач',
'changepassword'            => 'Парол дегишарун',
'prefs-skin'                => 'КЪайдадиз ттунин тема',
'skin-preview'              => 'Сифтедин килигун',
'datedefault'               => 'Туькlуьрмир',
'prefs-beta'                => 'Бета-мумкинвилер',
'prefs-datetime'            => 'Нумра ва вахт',
'prefs-labs'                => 'Экспериментдин мумкинвилер',
'prefs-personal'            => 'Иштиракчидин профил',
'prefs-rc'                  => 'Mукьвара хьайи дегишвилер',
'prefs-watchlist'           => 'Гуьзетунин сиягь',
'prefs-watchlist-edits-max' => 'Максимум кьадар: 1000',
'prefs-watchlist-token'     => 'Гуьзетунин сиягьдин лишан',
'prefs-misc'                => 'Муькуь низамарунар',
'prefs-resetpass'           => 'Парол дегишарун',
'prefs-changeemail'         => 'Э-почта дегишарун',
'prefs-setemail'            => 'Э-почта эцигна туькIуьрун',
'prefs-email'               => 'E-mail туькlуьрунин кьадарар',
'prefs-rendering'           => 'КЪецепатан  акунар',
'saveprefs'                 => 'Хуьн',
'resetprefs'                => 'Хуьн тавунвай дегишвилер алудун',
'restoreprefs'              => 'Авайл хьиз кьунвай низамарунар туькIуьр хъувун',
'prefs-editing'             => 'Дуьзар хъувун',
'prefs-edit-boxsize'        => 'Дуьзар хъувунин дакIардин кьадар',
'rows'                      => 'ЦIарар',
'columns'                   => 'Гулар:',
'searchresultshead'         => 'Ахтармишун',
'resultsperpage'            => 'Са ччиниз талукь тир жагъанвай нетижаяр',
'stub-threshold-disabled'   => 'Галуднава',
'timezonelegend'            => 'Вахтунин минзил',
'localtime'                 => 'Чкадин вахт',
'timezoneoffset'            => 'Вахтунин тафават',
'servertime'                => 'Сервердир вахт:',
'timezoneregion-africa'     => 'Африка',
'timezoneregion-america'    => 'Америка',
'timezoneregion-antarctica' => 'Антарктида',
'timezoneregion-arctic'     => 'Арктика',
'timezoneregion-asia'       => 'Азия',
'timezoneregion-atlantic'   => 'Атлантик чIехи гуьл',
'timezoneregion-australia'  => 'Австралия',
'timezoneregion-europe'     => 'Эуропа',
'timezoneregion-indian'     => 'Индия чIехи гуьл',
'timezoneregion-pacific'    => 'Секин чIехи гуьл',
'prefs-files'               => 'Шикил',
'youremail'                 => 'Электрон почта:',
'username'                  => 'Уртахдин тӀвар',
'uid'                       => 'Уртах тайин ийидайди:',
'yourrealname'              => 'Xалис тIвар:',
'yourlanguage'              => 'ЧIалар',
'yournick'                  => 'ЦӀийи къул:',
'yourgender'                => 'Жинс:',
'gender-male'               => 'итимдин',
'gender-female'             => 'папарин',
'email'                     => 'E-mail',
'prefs-help-email'          => 'Электрон почтунин адрес кхьи  мажбури туш, амма куьне парол рикIелай ракъурдатIа, ам герек жеда.',
'prefs-help-email-others'   => 'Квевай куь уртахдин, ва я куь веревирдрин ччина элячIун къалурна муькуь уртахар галаз электрон почтадин куькмедалди алакъа хуьз жеда.
Квез электрондин чар кхьидайла муькуь уртахриз куь электрондин почтадин адрес аквадач.',
'prefs-advancedediting'     => 'Гегьенш низамарунар',
'prefs-advancedrc'          => 'Гегьенш низамарунар',
'prefs-advancedrendering'   => 'Гегьенш низамарунар',
'prefs-advancedwatchlist'   => 'Гегьенш низамарунар',

# User rights
'userrights-reason' => 'Кар',

# Groups
'group'               => 'КIеретI',
'group-user'          => 'Иштиракчияр',
'group-autoconfirmed' => 'Автотестикь хьанвай иштиракчияр',
'group-bot'           => 'Ботар',
'group-sysop'         => 'Къавха',
'group-bureaucrat'    => 'Бюрократар',
'group-suppress'      => 'Ревизорар',
'group-all'           => '(вири)',

'group-user-member'       => '{{GENDER:$1|иштиракчи}}',
'group-bot-member'        => '{{GENDER:$1|бот}}',
'group-sysop-member'      => '{{GENDER:$1|администратор}}',
'group-bureaucrat-member' => '{{GENDER:$1|бюрократ}}',

'grouppage-user'  => '{{ns:project}}:Иштиракчияр',
'grouppage-bot'   => '{{ns:project}}:Бот',
'grouppage-sysop' => '{{ns:project}}:Къавхаяр',

# Rights
'right-read'          => 'Ччинар кIелун',
'right-edit'          => 'Дегишар хъувун',
'right-move'          => 'Ччинрин тIварар эхцигун',
'right-movefile'      => 'Файлрин тIварар эхцигун',
'right-upload'        => 'Файлар ппарун',
'right-delete'        => 'Ччинрин алудун',
'right-browsearchive' => 'Алуднавай ччинар жугъурун',
'right-undelete'      => 'Алуднавай ччинар туькIуьр хъувун',

# User rights log
'rightslog'  => 'Эхтияр Ишлемишчидин дафтlар',
'rightsnone' => '(садни)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'               => 'И ччин кIелун',
'action-edit'               => 'И ччин дуьзар хъувун',
'action-createpage'         => 'ччин туькIуьрун',
'action-createtalk'         => 'веревирдрин ччин туькIуьрун',
'action-createaccount'      => 'И иштиракчидин ччин туькIуьрун',
'action-minoredit'          => 'и дуьзар хъувун гъвечIи хьиз лишан авун',
'action-move'               => 'Ччинин тIвар эхцигун',
'action-move-subpages'      => 'и ччинин адан агъаччинрин  тIварар эхцигун',
'action-move-rootuserpages' => 'дувулдин иштиракчийрин ччинрин тIварар эхцигун',
'action-movefile'           => 'файлдин тIвар эхцигун',
'action-upload'             => 'и файл ппарун',
'action-reupload'           => 'авай файл цIийикIа ппарун',
'action-delete'             => 'и ччин алудун',
'action-deleterevision'     => 'ччинин и жуьре алудун',
'action-undelete'           => 'и ччин туькIуьр хъувун',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|масакIавал|масакIавилер}}',
'recentchanges'                   => 'Mукьвара хьайи масакӀавилер',
'recentchanges-legend'            => 'Цlийи масакIавилерин низамарунар',
'recentchangestext'               => 'И ччина викида хьанвай эхиримжи дегишунар гуьзетун',
'recentchanges-feed-description'  => 'И хвала викида хьанвай эхиримжи масакIавилер вилив хуьн',
'recentchanges-label-newpage'     => 'И дуьзар хъувун  цlийи ччин туькlуьрна',
'recentchanges-label-minor'       => 'Им гъвечlи дуьзар хъувун я',
'recentchanges-label-bot'         => 'И дуьзар хъувун ботди авунвайд я',
'recentchanges-label-unpatrolled' => 'И дуьзар хъувундин винел патрулвал авунвач',
'rcnote'                          => "$5, $4 чIавун кьатIдиз талукь {{PLURAL:$2|югъ|'''$2''' йикъар}} къене{{PLURAL:$1|эхиримжи'''1''' масакIавал|эхиримжи '''$1''' масакIавилер}}",
'rcnotefrom'                      => "Агъадихъ '''$2'''-ай эгечIна масакIавилер къалурнава ( '''$1''' кьван  къалурнава).",
'rclistfrom'                      => '$1-й эгечIна цIийи масакIавилер къалурун',
'rcshowhideminor'                 => '$1 гъвечlи дуьзар хъувунар',
'rcshowhidebots'                  => '$1 ботар',
'rcshowhideliu'                   => '$1 чпин тlвар къалурнавай уртахар',
'rcshowhideanons'                 => '$1 чуьнуьхай уртахар',
'rcshowhidepatr'                  => '$1 гуьзчивал авунвай дуьзар хъувунар',
'rcshowhidemine'                  => '$1 зи  дуьзар хъувунар',
'rclinks'                         => 'Эхиримжи $2 йикъан къене $1 масакIавилер къалура <br />$3',
'diff'                            => 'тафават',
'hist'                            => 'тарих',
'hide'                            => 'Чуьнуьхун',
'show'                            => 'Къалурун',
'minoreditletter'                 => 'гъ',
'newpageletter'                   => 'ЦI',
'boteditletter'                   => 'б',
'rc_categories_any'               => 'ГЬар са',
'rc-enhanced-expand'              => 'Куьлуь-шуьлуьяр къалурун (JavaScript герекзава)',
'rc-enhanced-hide'                => 'Куьлуь-шуьлуьяр чуьнуьха',

# Recent changes linked
'recentchangeslinked'          => 'Галкlанвай дуьзар хъувунар',
'recentchangeslinked-feed'     => 'Галкlанвай дуьзар хъувунар',
'recentchangeslinked-toolbox'  => 'Галкlанвай масакIавилер',
'recentchangeslinked-title'    => '"$1" галаз галкlанавай масакIавилер',
'recentchangeslinked-noresult' => 'Ганвай чlава галкlанавай ччинра са масакIавални хьанвайд туш',
'recentchangeslinked-summary'  => 'Им къалурай ччиниз (ва я къалурай категориядиз гьатзавай ччинриз) элячIзавай ччинра мукьвара хьайи масакIавилерин сиягь я. Куь [[Special:Watchlist| вилив хуьнин сиягь ]]диз гьатзавай  ччинар яцlу шрифтдал къалурнава.',
'recentchangeslinked-page'     => 'Ччинин тlвар:',
'recentchangeslinked-to'       => 'Аксина, къалурай ччиниз элячlзавай ччинра масакIавилер къалура',

# Upload
'upload'              => 'Файл ппарун',
'uploadbtn'           => 'Файл ппарун',
'uploaderror'         => 'Ппарунин гъалатI',
'uploadlog'           => 'ппарунин журнал',
'uploadlogpage'       => 'Ппарунин журнал',
'filename'            => 'Файлдин тIвар',
'filedesc'            => 'Нетижа',
'fileuploadsummary'   => 'Нетижа:',
'filereuploadsummary' => 'Файлдин къене дегишвилер:',
'filestatus'          => 'Автордин ихтиярдин статус:',
'filesource'          => 'Чешме:',
'uploadedfiles'       => 'Ппарнавай файлар',
'uploadedimage'       => '"[[$1]]" ппарна',
'upload-source'       => 'Чешмедин файл',
'sourcefilename'      => 'Чешмедин тир файлдин тIвар:',
'sourceurl'           => 'Чешмедин URL-адрес:',
'destfilename'        => 'Файлдин цIийи тIвар:',
'upload-maxfilesize'  => 'Файлдин лап виниз тир кьадар: $1',
'upload-description'  => 'Файлдин тегьерар кхьин',
'upload-options'      => 'Ппарунин шартIар',
'watchthisupload'     => 'И файл гуьзетун',

'upload-file-error'   => 'КЪенепатан гъалатI',
'upload-unknown-size' => 'Тийижир кьадар',

# Special:UploadStash
'uploadstash-refresh' => 'Файлрин сиягь цIийи хъувун',

# img_auth script messages
'img-auth-accessdenied' => 'Гьахьун къадагъа авунва',

'license'            => 'Лицензиярун',
'license-header'     => 'Лицинзиярун',
'nolicense'          => 'ЗатIни хкягънавач',
'license-nopreview'  => '(Сифтедин килигун авайд ттуш)',
'upload_source_file' => ' (куь компьютерда авай файл)',

# Special:ListFiles
'imgfile'               => 'Файл',
'listfiles'             => 'Файлдин сиягь',
'listfiles_thumb'       => 'Бицlи суьрет',
'listfiles_date'        => 'Нумра',
'listfiles_name'        => 'ТIар',
'listfiles_user'        => 'Иштиракчи',
'listfiles_size'        => 'Кьадар',
'listfiles_description' => 'Тегьерар кхьин',
'listfiles_count'       => 'Жуьреяр',

# File description page
'file-anchor-link'          => 'Файл',
'filehist'                  => 'Файлдин тарих',
'filehist-help'             => 'Файлдин виликан жуьре килигун патал, гьа а жуьредин тарих/вахт илиса,',
'filehist-deleteall'        => 'вири къакъудун',
'filehist-deleteone'        => 'къакъудун',
'filehist-revert'           => 'элкъуьрна хкун',
'filehist-current'          => 'алай',
'filehist-datetime'         => 'Тарих/вахт',
'filehist-thumb'            => 'Бицlи шикил',
'filehist-thumbtext'        => '$1 тарих алай жьуредин бицlи акунар',
'filehist-nothumb'          => 'БицIи суьрет авайд ттуш',
'filehist-user'             => 'Уртах',
'filehist-dimensions'       => 'Кьадарар',
'filehist-filesize'         => 'Файлдин кьадар',
'filehist-comment'          => 'Къейд',
'filehist-missing'          => 'Файл авачиз я',
'imagelinks'                => 'Файл кардик кутун',
'linkstoimage'              => 'Къведай {{PLURAL: $1 | ччин | $1 ччинар}} гьа и файлдиз элячlзава',
'nolinkstoimage'            => 'И файлдиз элячlзавай ччинар авайд туш',
'sharedupload'              => 'И шикил $1 масса хакъидайра ишлемишатlа жезава.',
'sharedupload-desc-here'    => 'И файл $1-кай я ва ам маса проектра  кардик кутаз жеда.
Адан [$2 тегьерар кхьинин ччина авай малумат] агъуз къалурнава.',
'uploadnewversion-linktext' => 'Хтун хъувун цlийи жюреяр и шкилдин',
'shared-repo-from'          => 'идай $1',

# File reversion
'filerevert-comment' => 'Кар',
'filerevert-submit'  => 'Элкъуьрна хкун',

# File deletion
'filedelete'                  => '$1 алудун',
'filedelete-legend'           => 'Файл алудун',
'filedelete-comment'          => 'Кар',
'filedelete-submit'           => 'Къакъудун',
'filedelete-reason-otherlist' => 'Муькуь себеб',

# MIME search
'mimesearch' => 'MIME ахтармишун',
'download'   => 'АцIун',

# Unused templates
'unusedtemplateswlh' => 'муькуь элячIунар',

# Random page
'randompage' => 'Дуьшуьшдин ччин',

# Statistics
'statistics'              => 'Статистика',
'statistics-header-pages' => 'Ччинрин статистика',
'statistics-header-edits' => 'Дуьзар хъувунрин статистика',
'statistics-header-views' => 'Статистикадиз килигун',
'statistics-header-users' => 'Иштиракчидин статистика',
'statistics-articles'     => 'Макъалаяр',
'statistics-pages'        => 'Ччинар',
'statistics-files'        => 'Ппарнавай файлар',
'statistics-views-total'  => 'Вири килигунар',
'statistics-users-active' => 'Актив уртахар',

'disambiguationspage' => 'Template:гзафманавал',

'brokenredirects-edit'   => 'дегишарун',
'brokenredirects-delete' => 'къакъудун',

'withoutinterwiki-submit' => 'КЪалурун',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|байт|байтар}}',
'nmembers'          => '$1 {{PLURAL:$1|уьзви|уьзвияр}}',
'lonelypages'       => 'Eтим xъувун',
'prefixindex'       => 'Префикс галай вири ччинар',
'shortpages'        => 'Куьруь хъувун',
'longpages'         => 'Яргъи хъувун',
'protectedpages'    => 'Хвенвай ччинар',
'listusers'         => 'Уртахрин сиягь',
'usercreated'       => '{{GENDER:$3|Created}} идав $1 идал $2',
'newpages'          => 'ЦIийи ччинар',
'newpages-username' => 'Иштиракчидин тlвар',
'ancientpages'      => 'виридалайни цIуру ччинар',
'move'              => 'ТIвар эхцигун',
'movethispage'      => 'Юзун и хъувун',
'pager-newer-n'     => '{{PLURAL:$1|мадни цIийи 1|мадни цIийи $1}}',
'pager-older-n'     => '{{PLURAL:$1|мадни цIуру 1|мадни цIуру $1}}',
'suppress'          => 'Чуьнуьхун',

# Book sources
'booksources'               => 'Ктабрин чешмеяр',
'booksources-search-legend' => 'Ктабдикай малумат жугъурун',
'booksources-go'            => 'Фин',

# Special:Log
'log' => 'Журналар',

# Special:AllPages
'allpages'       => 'Вири ччинар',
'alphaindexline' => '$1-кай $2 -ди',
'prevpage'       => 'Алатай чар ($1)',
'allpagesfrom'   => 'Къалур хъувун,идалай гатIунай:',
'allpagesto'     => 'Акъудан чарар, куьтягь жезвай:',
'allarticles'    => 'Вири ччинар',
'allpagesprev'   => 'Вилик алатай',
'allpagesnext'   => 'Къведайди',
'allpagessubmit' => 'ЭлячIун',

# Special:Categories
'categories' => 'Категорияр',

# Special:LinkSearch
'linksearch'      => 'КЪецепатан элячIунар жугъурун',
'linksearch-ns'   => 'Тlварарин генгвал:',
'linksearch-ok'   => 'Ахтармишун',
'linksearch-line' => '$2-ай $1-аз элячlун',

# Special:ListUsers
'listusers-submit'   => 'КЪалурун',
'listusers-noresult' => 'Иштиракчияр жагъуриз хьанвач',
'listusers-blocked'  => '(блокарнава)',

# Special:Log/newusers
'newuserlogpage' => 'Уртахар регистрация авунин журнал',

# Special:ListGroupRights
'listgrouprights-group'   => 'КIеретI',
'listgrouprights-members' => '(уьзвийрин сиягь)',

# E-mail user
'emailuser'           => 'Уртахдиз чар кхьихь',
'emailusername'       => 'Уртахдин тlвар:',
'emailusernamesubmit' => 'Ракъурун',
'emailfrom'           => 'Идай',
'emailto'             => 'Эхир:',
'emailsubject'        => 'Тема:',
'emailmessage'        => 'Хъагъаз',
'emailsend'           => 'Ракъурун',

# Watchlist
'watchlist'         => 'Зи вилив хуьнин сиягь',
'mywatchlist'       => 'Зи вилив хюнин сиягь',
'watchlistfor2'     => '$1 $2 патал',
'addedwatchtext'    => "Чар \"[[:\$1]]\" тун хъувунай куьн [[Special:Watchlist|watchlist]].                                                                                                             Къвезмай дегишунар и чарчел ва галкlанавай чарчихъ ихтилатар жеда инна, ахъатдава \"сакlус яцlу''''' инна [[Special:RecentChanges|list of recent changes]] гьам кьизил авун.",
'removedwatchtext'  => 'Чар "[[:$1]]" Идай чlурнай [[Special:Watchlist|ахтармишунин цlарар]].',
'watch'             => 'Вилив хуьн',
'watchthispage'     => 'Гелкъуьн и хъувун',
'unwatch'           => 'Вилив хуьмир',
'watchlist-details' => 'Куь вилив хуьнин сиягьда {{PLURAL:$1|$1 ччин|$1 ччин}} авайди я, веревирдрин ччинар квачиз.',
'wlshowlast'        => 'Эхиримжи $1 сят $2 югъ $3 къалура',
'watchlist-options' => 'Вилив хуьнин сиягьдин низамарунар',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Килигун...',
'unwatching' => 'Амма клигнай',

'changed' => 'дегишнава',
'created' => 'туькIуьрнава',

# Delete
'deletepage'            => 'Къакъудун хъувун',
'confirm'               => 'Тестикьун',
'delete-confirm'        => 'Къакъудун "$1"',
'delete-legend'         => 'Къакъудун',
'confirmdeletetext'     => 'Квез чlуриз кlанзани чарар гьадан вири тарихар галаз.                                                                                                                         Буюр, сидикъара,куьне чlурзатlа, куьн агъавурда автlа вуч ийизатlа ва куьне ийизатlа жуьреда [[{{MediaWiki:Policy-url}}| политика]].',
'actioncomplete'        => 'Кар авунва',
'actionfailed'          => 'Кар йиз алакьнавач',
'deletedtext'           => '"$1" чlурнайтир.                                                                                                                                                       Килиг $2 эхиримжи  чlурунар ахтармишун.',
'dellogpage'            => 'Алудунин журнал',
'deletecomment'         => 'Кар',
'deleteotherreason'     => 'Масса/ ва мад кар',
'deletereasonotherlist' => 'Маса фагьум',

# Rollback
'rollback_short' => 'КЬулухъди чIугун',
'rollbacklink'   => 'Кьулухъди чIугун',

# Edit tokens
'sessionfailure-title' => 'Гьахьунин гъалатI',

# Protect
'protectlogpage'              => 'Хуьнин журнал',
'protectedarticle'            => '"[[$1]]" ччин хвенва',
'modifiedarticleprotection'   => 'дегиш хьанахуьнун кьадар идаз "[[$1]]"',
'protectcomment'              => 'Кар',
'protectexpiry'               => 'Алатна',
'protect_expiry_invalid'      => 'Вахтун кьадар дуьзди туш.',
'protect_expiry_old'          => 'Вахтун кьадар алатай заманда.',
'protect-text'                => "Квевай клигайтlа ва дегишарайтlа жеза хуьнин къайда чарчин '''$1'''.",
'protect-locked-access'       => "Квез ахтияр авач дегишариз чарчин хуьн къайда.                                                                                                                             Ингье физвай туькlуьрунар чарчиз '''$1''':",
'protect-cascadeon'           => 'Хуьн авун чарар къараул ийизвай алай вахтунда, гьама ктуна{{PLURAL:$1|чар, гьама|чарар, гьабур}} галай галай хуьн куькlуьрна.Куьвай жеза дегишариз хуьнин кьадар чарчин, гьама кядач галай галай хуьнив.',
'protect-default'             => ' Эхтияр гуз вири ишлемишчийриз',
'protect-fallback'            => 'Тlалабун "$1" эхтияр',
'protect-level-autoconfirmed' => 'Къаб цlийи ва кхьитунавай ишлемишчияр',
'protect-level-sysop'         => 'Гилан къавха',
'protect-summary-cascade'     => 'къвалагай къвалагай',
'protect-expiring'            => 'алатиз $1 (UTC)',
'protect-cascade'             => 'Тажум чарчин куькlуьрнава и чарчел(пат пат тажум)',
'protect-cantedit'            => 'Кевай дегиш жедач и чар, вучиз лагьайтlа квез ахтияр авач амма дегишариз.',
'restriction-type'            => 'Ихтияр:',
'restriction-level'           => 'Кьадардин кьадар',

# Restrictions (nouns)
'restriction-edit'   => 'Дегишарун',
'restriction-move'   => 'ТIвар эхцигун',
'restriction-upload' => 'Ппарун',

# Restriction levels
'restriction-level-autoconfirmed' => '(са кьадар хвенва)',
'restriction-level-all'           => 'гьар са дережа',

# Undelete
'undeletebtn'               => 'ТуьхкIуьрун',
'undeletelink'              => 'Килигун/гуьнгуьна хтун',
'undeleteviewlink'          => 'Килигун',
'undeletereset'             => 'Алудна гадрун',
'undeletecomment'           => 'Кар',
'undelete-search-submit'    => 'Жагъурун',
'undelete-show-file-submit' => 'Э',

# Namespace form on various pages
'namespace'      => 'Тlварарин генгвал:',
'invert'         => 'Хкягънавайди элкъуьрун',
'blanknamespace' => '(Асул)',

# Contributions
'contributions'       => 'Уртахди кутур крар',
'contributions-title' => '$1 уртахди кутур крар',
'mycontris'           => 'За кутур кар',
'contribsub2'         => '($1)-ин кутур пай  ($2)',
'uctop'               => '(вини кьил)',
'month'               => ' Вацралай (ва адалай вилик)',
'year'                => 'Иисалай (ва адалай вилик):',

'sp-contributions-newbies'  => 'Анжах цlийи уртахрин кутур крар къалура',
'sp-contributions-blocklog' => 'Блокарунин журнал',
'sp-contributions-uploads'  => 'ппарунар',
'sp-contributions-logs'     => 'журналар',
'sp-contributions-talk'     => 'Рахун',
'sp-contributions-search'   => 'Кутунвай пай жугъура',
'sp-contributions-username' => 'IP -адрес ва я  уртахдин тlвар',
'sp-contributions-toponly'  => 'Анжах эхиримжи жуьре тир дуьзар хъувунар къалура',
'sp-contributions-submit'   => 'Жагъурун',

# What links here
'whatlinkshere'            => 'Иниз вуч элячIзава',
'whatlinkshere-title'      => '"$1" - даз элячlзавай ччинар',
'whatlinkshere-page'       => 'Ччин:',
'linkshere'                => "Гуьгъуьнин ччинар '''[[:$1]]''': - даз  элячlзава",
'nolinkshere'              => "'''[[:$1]]''' ччиниз са ччинни элячIзавач.",
'isredirect'               => 'Рахкъурунин ччин',
'istemplate'               => 'кутун',
'isimage'                  => 'Файлдин элячlун',
'whatlinkshere-prev'       => '{{PLURAL:$1|вилик фейи|вилик фейи $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|гуьгъуьнин|гуьгъуьнин $1}}',
'whatlinkshere-links'      => '← элячlунар',
'whatlinkshere-hideredirs' => '$1 рахкъурунар',
'whatlinkshere-hidetrans'  => '$1 кутунар',
'whatlinkshere-hidelinks'  => '$1 элячlунар',
'whatlinkshere-hideimages' => '$1 шикилриз элячӀунар',
'whatlinkshere-filters'    => 'Куьзунагар',

# Block/unblock
'block'                    => 'Уртах блокарун',
'unblock'                  => 'Уртах блокдай хкудун',
'blockip'                  => 'Ишлемишзавайдан хара',
'ipbreason'                => 'Себеб:',
'ipbsubmit'                => 'И уртах блокарун',
'ipboptions'               => '2 сят:2 hours,1 югъ:1 day,3 югъ:3 days,1 никIи:1 week,2 никIи:2 weeks,1 варз:1 month,3 варз:3 months,6 варз:6 months,1 йис:1 year,вахт алачир:infinite',
'ipb-confirm'              => 'Блок тестикьун',
'ipblocklist'              => 'Блокарнавай уртахар',
'blocklist-reason'         => 'Себеб',
'blocklink'                => 'Блок авун',
'unblocklink'              => 'Блок къахчун',
'change-blocklink'         => 'блокарун масакIа авун',
'contribslink'             => 'Кутур крар',
'blocklogpage'             => 'Блокарунин журнал',
'blocklogentry'            => '[[$1]] блокарна,  $2 $3 чIав кьван',
'unblocklogentry'          => 'Куьлегдай акъудун $1',
'block-log-flags-nocreate' => 'Аккаунт туькIуьрдай изинар авач',

# Move page
'move-page'        => '$1 тIвар эхцигун',
'move-page-legend' => 'Юзун хъувун',
'movepagetext'     => "Ишлемишиз кlеневай къаб,чарчин тlар дегишариp, кьиспесдин  чка дегишарун, цlийи тlар авун.
Иски тlарцlи ракъурда цlийи тlарцlел.
Квевай жеда цlийи хъийиз ракъурун, къалурзай дуьз тlарцlел вуч вичиз.
Квез кlанзаштlа,рекlел алудмир ахтармишиз инна [[Special:DoubleRedirects|double]] ва [[Special:BrokenRedirects|broken redirects]].
Куьне тухузва шаидвал,мадни гьабур гьамиша алукьдайвал, гьиниз гьабур фена кlанзатl.

Ахтармиша, чар ''ваъ''' ракъур хьун, гьахьтин тlар алай чар ава, гьама ичlиди ятlа, ракъурнатlа ва дегишарунин кьиспес авачтlа.
Кевай жеда гьа чар тlар дегишараз кьулукъ гьаниз элкъуьриз, гьина гьадан тlар дегиш авунатlа, куьне гъалатl авунатlа, куьне чин тийиз авай чарчик гъалатl тада.

'''Килига!'''
Амма бейхабар жеда гзаф герекзай чарариз;
Буюр, килиг , куьне фикирзатlа вуч жезатlа, кхьин хъийидади.",
'movepagetalktext' => "Ухшар авай чарар ихтилатдин, фида масса чкадал вуч вичиз ибур галаз''',амма:'''                                                                               *Эчlи чар ихтилатдин ава цlийи тlар алаз, ва                                                                                                                                           *Куьне иляйда пайдах кlеникай                                                                                                                                                                                  Гьа вахтунда, куьне чка дегишар авун или санал авуна чар гъилелди, кlанда",
'movearticle'      => 'Юзун хъувун:',
'newtitle'         => 'Цlийи тlарцlихъ:',
'move-watch'       => 'Гелкъуьн и хъувун',
'movepagebtn'      => 'Юзун хъувун',
'pagemovedsub'     => 'Рахъурун хьана',
'movepage-moved'   => '\'\'"$1" рахъурнай "$2"\'\'\'',
'articleexists'    => 'Ахьтин тlар алай чар ава, амма тlар, куьне хкянай дуьз туш.Башуьсте, масса тlар хкяй.',
'talkexists'       => "'''Чарчин тlар дегишарна, чарчин ихтилатар дегишар жезатуш, вучиз лагьайтlа ихтилатар цlийи чарчел фена.Галкlура гълелди.'''",
'movedto'          => 'хтана иниз',
'movetalk'         => 'Югъун,галкlана рахун',
'movelogpage'      => 'Тlвар эхцигунрин журнал',
'movereason'       => 'Фагьум:',
'revertmove'       => 'Рахкъурун',
'delete_and_move'  => 'Алудун ва тIвар эхцигун',

# Export
'export'          => 'Ччинрин экспорт',
'export-addns'    => 'Алава авун',
'export-download' => 'Файл хьиз хуьн',

# Namespace 8 related
'allmessagesname'           => 'Тlвар',
'allmessagesdefault'        => 'Авайд хьиз кьунвай текст',
'allmessages-filter-legend' => 'Куьзунаг',
'allmessages-filter-all'    => 'Вири',
'allmessages-language'      => 'Чlал:',
'allmessages-filter-submit' => 'ЭлячIун',

# Thumbnails
'thumbnail-more'  => 'ЧIехи авун',
'thumbnail_error' => 'Бицlи шикил  туькlуьрунин гъалатl:$1',

# Special:Import
'import-upload-filename' => 'Шикилдинтlар:',
'import-token-mismatch'  => 'Сеансдин  ганайбур квахьнава. Тавакъу ийида, мадни алахъун ая.',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Куьн (уртахдин) ччин',
'tooltip-pt-mytalk'               => 'Куь веревирдрин ччин',
'tooltip-pt-preferences'          => 'Куь низамарунар',
'tooltip-pt-watchlist'            => 'Куьне вилив хуьзвай ччинрин сиягь',
'tooltip-pt-mycontris'            => 'Куьне авунвай дуьзар хъувунрин сиягь',
'tooltip-pt-login'                => 'Квез гьахьиз теклифзава, анжах им мажбури туш',
'tooltip-pt-logout'               => 'ЭкъечIун',
'tooltip-ca-talk'                 => 'Къене авайбурун ччин веревирд авун',
'tooltip-ca-edit'                 => 'Квевай и ччин масакIа ийиз жеда. Ччин хуьдалди вилик, сифте килигундиз илис.',
'tooltip-ca-addsection'           => 'Гатlунив цlийи кьил',
'tooltip-ca-viewsource'           => 'И ччин хвенвайд я, амма квевай адан къене авайбуруз килигиз жеда.',
'tooltip-ca-history'              => 'И ччинин алатай масакIавилерин журнал',
'tooltip-ca-protect'              => 'И ччин хуьн',
'tooltip-ca-delete'               => 'И ччин алудун',
'tooltip-ca-move'                 => 'Ччиндин тIвар масакIа авун',
'tooltip-ca-watch'                => 'И ччин куь вилив хуьнин сиягьдиз алава авун',
'tooltip-ca-unwatch'              => 'И ччин куь вилив хуьнин сиягьдай къахчун',
'tooltip-search'                  => '{{SITENAME}} къекъуьн',
'tooltip-search-go'               => 'АватIа, гьа и тIвар авай ччиниз элячIун',
'tooltip-search-fulltext'         => 'Къалурай текст авай ччинар жугъурун',
'tooltip-p-logo'                  => 'Кьилин ччиниз элячIун',
'tooltip-n-mainpage'              => 'Кьилин ччиниз элячIун',
'tooltip-n-mainpage-description'  => 'Кьилин ччиндиз элячIун',
'tooltip-n-portal'                => 'Проектдикай,  квевай вуч йийз алакьда, са вуч ятIани гьинай жугъурда',
'tooltip-n-currentevents'         => 'Алай вакъийрин сиягь',
'tooltip-n-recentchanges'         => 'Викида мукьвара хьайи масакIавилерин сиягь',
'tooltip-n-randompage'            => 'Дуьшуьшдин чин ппарун',
'tooltip-n-help'                  => 'Жагъурун патал чка',
'tooltip-t-whatlinkshere'         => 'Иниз элячIзавай викидин вири  ччинрин сиягь',
'tooltip-t-recentchangeslinked'   => 'И ччиндиз элячIзавай ччинра  мукьвара хьайи масакIавилер',
'tooltip-feed-rss'                => 'RSS  хуьрек и чарчиз',
'tooltip-feed-atom'               => 'И ччиндин Atom -дин трансляция',
'tooltip-t-contributions'         => 'И уртахдин кутур крарин сиягь',
'tooltip-t-emailuser'             => 'И уртахдиз электрон чар ракъура',
'tooltip-t-upload'                => 'Шикилар ва я мультимедиядин файлар ппарун',
'tooltip-t-specialpages'          => 'Куьмекчи ччинрин сиягь',
'tooltip-t-print'                 => 'И ччиндин басма авун патал жьуре',
'tooltip-t-permalink'             => 'Ччиндин и жуьредиз гьамишан элячIун',
'tooltip-ca-nstab-main'           => 'Макъалада авайбриз килига',
'tooltip-ca-nstab-user'           => 'Уртахдин ччиниз килигун',
'tooltip-ca-nstab-special'        => 'Им куьмекдин ччин я, квевай и ччин дуьзар хъийиз жедач',
'tooltip-ca-nstab-project'        => 'Проектдин ччиниз килигун',
'tooltip-ca-nstab-image'          => 'Файлдин ччиндиз килигун',
'tooltip-ca-nstab-template'       => 'Чешнедиз килигун',
'tooltip-ca-nstab-category'       => 'Категориядин ччиниз килигун',
'tooltip-minoredit'               => 'И масакIавал гъвечlи дуьзар хъувун хьиз лишан ая',
'tooltip-save'                    => 'Куь масакIавал хуьн',
'tooltip-preview'                 => 'Ччин хуьдалди вилик, сифте килигун кардик кутун тавакъу ийизва',
'tooltip-diff'                    => 'Сифте кьилин текстдиз талукь тир куьне авунвай масакIавилер къалурун',
'tooltip-compareselectedversions' => 'И ччинин кьве хкягъай жуьрейрин арада авай тафаватдиз килигун',
'tooltip-watch'                   => 'И ччин куь вилив хуьнин сиягьдиз алава авун',
'tooltip-rollback'                => '« КЬулухъди чIугун »  и ччиндиз эхиримжи кар кутазвайди патай  авунвай дуьзар хъувунар са т!ампуналди  paxкурзава',
'tooltip-undo'                    => '«Гьич авун»  авунвай дуьзар хъувун paxкурзава ва сифтедин килигунин режимда  дуьзар хъувундин форма ахъа йийзва. Им нетижадиз себеб алава йийз мумкинвал гузва',
'tooltip-summary'                 => 'Куьруь нетижа гьадрун',

# Info page
'pageinfo-header-edits'     => 'Дуьзар хъувун',
'pageinfo-header-watchlist' => 'Гуьзетунин сиягь',
'pageinfo-header-views'     => 'Килигунар',
'pageinfo-subjectpage'      => 'Ччин',
'pageinfo-talkpage'         => 'Веревирдрин ччин',
'pageinfo-edits'            => 'Дьузар хъувунрин кьадар',
'pageinfo-views'            => 'Килигунрин кьадар',

# Browsing diffs
'previousdiff' => 'Вилик алатай дуьзар хъувун',
'nextdiff'     => 'ЦIийи масакIаяр',

# Media information
'file-info-size' => '$1 × $2 пикселар, файлдин кьадар: $3, MIME жуьре: $4',
'file-nohires'   => 'Идалайни хъсан ери авайд туш',
'svg-long-desc'  => 'SVG файл, номилдаказ $1 $2 × пикселяр, файлдин кьадар: $3',
'show-big-image' => 'Цlарафа хвена тунвай жергедай',

# Bad image list
'bad_image_list' => 'Формат гьихьтинди хьана кlанда:

Cиягьда авай анжах (* лишандихъ галаз эгечIзавай цIарарин) элементар гьисабдиз къачуда.
ЦlарцIе авай сад лагьай элячIун ттун патал къадагъа алай шикилдиз элячIун хьана кlанзава.
Гьар са цlарцIе авай гьар са ахпагьан элячIунар кьетIендинбур хьиз кьабулда, мисал яз, суьрет тваз мумкинвал авай ччинар.',

# Metadata
'metadata'          => 'Метамалуматар',
'metadata-help'     => 'И файлдин къене гилигнавай адет яз камера ва я сканер куьмекдалди алава авунвай  малумат ава. Файл ахпа дуьзур хъувуначтlа, бязи параметрар алай суьретдив кьун тахьун мумкин я.',
'metadata-expand'   => 'Къалурун дериндиз',
'metadata-collapse' => 'Кlевун дерин къалурунар',
'metadata-fields'   => 'Метамалуматрин таблица чIур хьайла и сиягьда тlварар кьунвай суьретдин метамалуматрин чкаяр сутретдин ччина къалурда.  Муькуьбур авайдхьиз кьуна чуьнуьхда.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'          => 'Гьяркьуь',
'exif-planarconfiguration' => 'Ганайбур тешкил авун',
'exif-ycbcrsubsampling'    => ' Y  ва C компонентрин кьадаррин нисбет',
'exif-ycbcrpositioning'    => ' Y ва C компонентрин чкайрал эцигунин къайда',
'exif-stripoffsets'        => 'Суьретдин ганайбурун авай чка',
'exif-rowsperstrip'        => 'Са блокда  авай цIарарин кьадар',
'exif-contrast'            => 'Рангар',
'exif-keywords'            => 'Куьлегдин гафар',
'exif-languagecode'        => 'Чlал',
'exif-disclaimer'          => 'Жавабдарвал хивяй акъудун',

'exif-contrast-1' => 'Жими',

'exif-sharpness-1' => 'Жими',

# External editor support
'edit-externally'      => 'И файл патан программа куьмекдалди дуьзар хъувун',
'edit-externally-help' => '(Алава малумат патал [//www.mediawiki.org/wiki/Manual:External_editors эцигунин регьбервилиз] килига)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'вири',
'namespacesall' => 'вири',
'monthsall'     => 'вири',
'limitall'      => 'вири',

# action=purge
'confirm_purge_button' => 'ЭХь',
'confirm-purge-top'    => 'И ччинин кэш алуддани?',

# action=watch/unwatch
'confirm-watch-button'   => 'ОК',
'confirm-watch-top'      => 'И ччин куь гуьзетунин сиягьдиз алава ийидани?',
'confirm-unwatch-button' => 'ОК',
'confirm-unwatch-top'    => 'И ччин куь гуьзетунин сиягьдал къахчудани?',

# Multipage image navigation
'imgmultipageprev' => '← вилик алатай ччин',
'imgmultipagenext' => 'гуьгъуьнин ччин →',
'imgmultigo'       => 'Ша!',
'imgmultigoto'     => ' $1 ччиниз элячIун',

# Table pager
'table_pager_next'         => 'Гуьгъуьнин ччин',
'table_pager_prev'         => 'Вилик алатай ччин',
'table_pager_first'        => 'Сад лагьай ччин',
'table_pager_last'         => 'Эхиримжи ччин',
'table_pager_limit'        => 'са ччина  $1 затIар къалурун',
'table_pager_limit_label'  => 'Са ччиниз талукь тир затIар:',
'table_pager_limit_submit' => 'Фин',
'table_pager_empty'        => 'Жагъанвач',

# Live preview
'livepreview-loading' => 'Ппарзава...',
'livepreview-ready'   => 'Ппарзава... ГЬазур я!',

# Watchlist editor
'watchlistedit-normal-title' => 'Гуьзетунин сиягь дуьзар хъувун',
'watchlistedit-raw-title'    => 'Гуьзетунин сиягь текст хьиз дуьзар хъувун',
'watchlistedit-raw-legend'   => 'Гуьзетунин сиягь текст хьиз дуьзар хъувун',
'watchlistedit-raw-titles'   => 'КЬилинцIарар:',
'watchlistedit-raw-submit'   => 'Гуьзетунин сиягь цIийи хъувун',

# Watchlist editing tools
'watchlisttools-view' => 'Сиягьда авай ччинра масакIавилер',
'watchlisttools-edit' => 'Гьузетунин сиягь килигун ва дуьзар хъувун',
'watchlisttools-raw'  => 'Текст хьиз дуьзар хъувун',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Дикъет:\'\'\' Авайд хьиз кьунвай жуьрейриз ччара авунин "$2" куьлег  виликан "$1" жуьрейриз ччара авунин куьлег гьич йийзва.',

# Special:Version
'version'          => 'Жуьре',
'version-antispam' => 'Спамдин вилик пад атIун',
'version-skins'    => 'КЪайдадиз ттунин темаяр',
'version-other'    => 'Муькуьбур',

# Special:SpecialPages
'specialpages' => 'КьетIен  ччинар',

# External image whitelist
'external_image_whitelist' => ' #И цӀар авайд хьиз тур<pre>
#Агъада вахт акадар тийиз жезвай (гьамиша къайдадалди) лугьунрин кьатӀар эцига (// арада авай кӀус).
#Ибур кьецепатан суьретрин URL галаз гекъигда.
#Дуьзкъвезвайбур суьретар хьиз къалурда, муькуьбур суьретриз тухузвай элячӀунар хьиз къалурда.
#«#» галаз эгечӀзавай цӀарариз къейдериз хьиз килигда.
#ЦӀарар регистрдиз фад кьатӀудайбур я.

#ЦӀарцин винел вири вахт акадар тийиз жезвай лугьунрин кьатӀар эцига. И цӀар авайд хьиз тур</pre>',

# Special:Tags
'tag-filter' => '[[Special:Tags|Tag]] куьзунаг:',
'tags-edit'  => 'дегишарун',

# Special:ComparePages
'compare-page1' => 'Чар 1',

# Feedback
'feedback-subject' => 'Тема:',
'feedback-message' => 'Чар:',
'feedback-cancel'  => 'Гьич авун',
'feedback-close'   => 'Авунва',

);
