<?php
/** Lezghian (Лезги)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amikeco
 * @author Andrijko Z.
 * @author Migraghvi
 * @author Namik
 * @author Reedy
 */

$messages = array(
# User preference toggles
'tog-underline'               => 'ЭлячIунрин кIаникай цIар чIугун',
'tog-justify'                 => 'Ччинин гьяркьуьвилихъ текст дуьзрун',
'tog-hideminor'               => 'Мукьвара хьайи дегишвилера авай гъвечIи дуьзар хъувунар чуьнуьхун',
'tog-hidepatrolled'           => 'Мукьвара хьайи дегишвилера авай къаравулвал авунвай дуьзар хъувунар чуьнуьхун',
'tog-newpageshidepatrolled'   => 'ЦIийи ччинрин сиягьда къаравулвал авунвай ччинар чуьнуьхун',
'tog-usenewrc'                => 'ЦIийи дегишвилерин сиягь кардик кутун (JavaScript герекзава)',
'tog-numberheadings'          => 'КЬилин цIарариз автоматдаказ номерар эцигун',
'tog-showtoolbar'             => 'Дуьзар хъувунин алатрин кьвати къалура (JavaScript)',
'tog-editondblclick'          => 'Ччинар кьве тIампIуналди дуьзар хъувун (JavaScript герекзава)',
'tog-editsection'             => 'Пай [дуьзар хъувун] патал элячIун къалура',
'tog-editsectiononrightclick' => 'Пайдин кьилин цIардиз эрчIи патан тIампI авуна дуьзар хъувундиз мумкинвал гун (JavaScript герекзава)',
'tog-showtoc'                 => 'КЪенеавайбурун сиягь къалурун (3-й гзаф кьилинцIарар авай ччинар патал)',
'tog-rememberpassword'        => 'И браузерда зи логин рикlел хуьхь (лап гзаф $1 {{PLURAL:$1|югъ|йикъар}})',
'tog-watchcreations'          => 'За туькIуьрнавай ччинар зи гуьзетунин сиягьдиз алава авун',
'tog-watchdefault'            => 'За дуьзар хъувунвай ччинар зи гуьзетунин сиягьдиз алава авун',
'tog-watchmoves'              => 'За тIвар эхцигай ччинар зи гуьзетунин сиягьдиз алава авун',
'tog-watchdeletion'           => 'За алуднавай ччинар зи гуьзетунин сиягьдиз алава авун',
'tog-previewontop'            => 'Сифтедин килигун дуьзар хъувундин дакIардин вилик эцига',
'tog-enotifusertalkpages'     => 'КЬилди жуван веревирдрин ччина хьанвай дегишвилерикай э-почтадиз чар ракъурун.',

'underline-always'  => 'Гьамиша',
'underline-never'   => 'Садрани',
'underline-default' => 'Браузердин низамарунар кардик кутун',

# Font style option in Special:Preferences
'editfont-style'     => 'Дуьзар хъувунин чкадин шрифтдин жуьре',
'editfont-monospace' => 'Моногьяркьуьвилер авай шрифт',
'editfont-sansserif' => 'КЬацI авачир шрифт',

# Dates
'sunday'        => 'Гьяд',
'monday'        => 'Ислен',
'tuesday'       => 'Саласа',
'wednesday'     => 'Арбе',
'thursday'      => 'Хемис',
'friday'        => 'Жуьмя',
'saturday'      => 'Киш',
'sun'           => 'Гья',
'mon'           => 'Исл',
'tue'           => 'Сал',
'wed'           => 'Aрб',
'thu'           => 'Xем',
'fri'           => 'Жум',
'sat'           => 'Киш',
'january'       => 'ГЬер',
'february'      => 'Эхем',
'march'         => 'Ибне',
'april'         => 'Нава',
'may_long'      => 'ТӀул',
'june'          => 'КЪамуг',
'july'          => 'Чиле',
'august'        => 'Пахун',
'september'     => 'Мара',
'october'       => 'БаскӀум',
'november'      => 'ЦӀехуьл',
'december'      => 'ФaндукӀ',
'january-gen'   => 'Гьер',
'february-gen'  => 'Эхем',
'march-gen'     => 'Ибне',
'april-gen'     => 'Нава',
'may-gen'       => 'ТӀул',
'june-gen'      => 'Къамуг',
'july-gen'      => 'Чиле',
'august-gen'    => 'Пахун',
'september-gen' => 'Мара',
'october-gen'   => 'БаскӀум',
'november-gen'  => 'ЦӀехуьл',
'december-gen'  => 'ФaндукӀ',
'jan'           => 'Гье',
'feb'           => 'Эхе',
'mar'           => 'Ибн',
'apr'           => 'Нав',
'may'           => 'ТӀу',
'jun'           => 'Къа',
'jul'           => 'Чил',
'aug'           => 'Пах',
'sep'           => 'Мар',
'oct'           => 'Бас',
'nov'           => 'ЦӀе',
'dec'           => 'Фaн',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Категория|Категории}}',
'category_header'          => '"$1" категориядин ччинар',
'subcategories'            => 'субкатегорияр',
'category-media-header'    => '"$1" категорияда медиа',
'category-empty'           => "''Алай чIава и категория ичIи я.\"",
'hidden-categories'        => '{{PLURAL:$1|Чуьнуьхай категория |Чуьнуьхай категорияр }}',
'hidden-category-category' => 'Чуьнуьхай категорияр',
'category-subcat-count'    => '{{PLURAL:$2|И категорияда анжах гуьгъуьна авай подкатегория ава.|$2-кай {{PLURAL:$1|подкатегория|$1 подкатегория}} къалурнава }}',
'category-article-count'   => '{{PLURAL:$2|И категорияда анжах гуьгъуьна авайди ччин ава |$2-кай къалурнавай {{PLURAL:$1|ччин|$1 ччин}} гьа а категориядин ччин я}}',
'category-file-count'      => '{{PLURAL:$2|И категорияда анжах гуьгъуьна авайди файл ава |$2-кай къалурнавай {{PLURAL:$1|файл|$1 файлар}} гьа а категориядин файл я}}',
'listingcontinuesabbrev'   => '(кьатI)',
'index-category'           => 'Индексавунвай ччинар',
'noindex-category'         => 'Индекстежезвай ччин',

'about'         => 'ГЬакъиндай',
'article'       => 'Макъала',
'newwindow'     => '(цlийи дакlарда ахъа жезва)',
'cancel'        => 'Гьич авун',
'moredotdotdot' => 'Мад...',
'mypage'        => 'Зин чар',
'mytalk'        => 'Зи веревирдрин ччин',
'anontalk'      => 'И IP-адресдиз талукь веревирд.',
'navigation'    => 'КЪекъуьнар',
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
'vector-action-addsection' => 'Тема алава авун',
'vector-action-delete'     => 'Алудун',
'vector-action-move'       => 'ТIвар эхцигун',
'vector-action-protect'    => 'Хуьн',
'vector-action-undelete'   => 'ТуькIуьр хъувун',
'vector-action-unprotect'  => 'Хуьн дегишарун',
'vector-view-create'       => 'Туькlуьрун',
'vector-view-edit'         => 'Дуьзар хъувун',
'vector-view-history'      => 'Тарихдиз килигун',
'vector-view-view'         => 'Кlелун',
'vector-view-viewsource'   => 'Чешме къалурун',
'actions'                  => 'Крар',
'namespaces'               => 'Тlварарин генгвилер',
'variants'                 => 'Жуьреяр',

'errorpagetitle'    => 'ГъалатI',
'returnto'          => '$1 ччиниз элкъвена хтун',
'tagline'           => '{{SITENAME}} Cайтдихъай',
'help'              => 'Куьмек',
'search'            => 'Жугъурун',
'searchbutton'      => 'Жугъурун',
'go'                => 'ЭлячIун',
'searcharticle'     => 'ЭлячIун',
'history'           => 'Ччинин тарих',
'history_short'     => 'Тарих',
'printableversion'  => 'Басма авун патал жуьре',
'permalink'         => 'ГЬамишан элячIун',
'print'             => 'Басма авун',
'edit'              => 'Дуьзар хъувун',
'create'            => 'Туькlуьрун',
'editthispage'      => 'И ччин дуьзар хъувун',
'create-this-page'  => 'И ччин туькIуьрун',
'delete'            => 'Алудун',
'deletethispage'    => 'И ччин алудун',
'protect'           => 'Xуьн',
'protect_change'    => 'Дегишун',
'protectthispage'   => 'И ччин блокарун',
'unprotect'         => 'Хуьн дегишарун',
'unprotectthispage' => 'И ччинин хуьн дегишарун',
'newpage'           => 'ЦIийи ччин',
'talkpage'          => 'И ччин веревирдун',
'talkpagelinktext'  => 'Рахун',
'specialpage'       => 'Куьмекчи ччин',
'personaltools'     => '-КЬилди вичин алатар',
'postcomment'       => 'ЦIйий пай',
'articlepage'       => 'КЪене авайбурун ччиндиз  килигун',
'talk'              => 'Веревирд авун',
'views'             => 'Килигунар',
'toolbox'           => 'Алатрин кьвати',
'userpage'          => 'Иштракчидин ччиниз килигун',
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
'lastmodifiedat'    => 'Ччинин эхиримжи дегиш хьун:  $1,  $2',
'jumpto'            => 'ЭлячIун иниз:',
'jumptonavigation'  => 'Навигация',
'jumptosearch'      => 'Жугъурун',
'pool-errorunknown' => 'Малумтушир гъалатI',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'ГЬакъиндай {{SITENAME}}',
'aboutpage'            => 'Project:ГЬакъиндай',
'copyright'            => 'КЪене авайбур $1 жугъуриз жеда.',
'copyrightpage'        => '{{ns:project}}: Автордин ихтияр',
'currentevents'        => 'Алай вакъиаяр',
'currentevents-url'    => 'Project: Алай вакъиаяр',
'disclaimers'          => 'Жавабдарвал хивяй акъудун',
'disclaimerpage'       => 'Project:Жавабдарвал хивяй акъудун',
'edithelp'             => 'Дуьзар хъувун патал куьмек',
'edithelppage'         => 'Help:Дуьзар хъувун',
'helppage'             => 'Help:КЪене авайбур',
'mainpage'             => 'КЬилин ччин',
'mainpage-description' => 'КЬилин ччин',
'portal'               => 'КIапIалдин портал',
'portal-url'           => 'Project: КIапIалдин портал',
'privacy'              => 'Чинебанвилин политика',
'privacypage'          => 'Project:Чинебанвилин политика',

'badaccess' => 'ГЬатунин гъалатlдин',

'ok'                  => 'ОК',
'retrievedfrom'       => 'Чешне "$1" я',
'youhavenewmessages'  => 'Квез  $1 ($2) атанва.',
'newmessageslink'     => 'цlийи чарар',
'newmessagesdifflink' => 'Эхиримжи дегишарунар',
'editsection'         => 'дуьзар хъувун',
'editold'             => 'Дуьзар хъувун',
'viewsourceold'       => 'сифте кьилин коддиз килига',
'editlink'            => 'Дуьзар хъувун',
'viewsourcelink'      => 'Сифте кьилин коддиз килига',
'editsectionhint'     => 'Пай дуьзар хъувун: $1',
'toc'                 => 'КЪене авайбур',
'showtoc'             => 'къалурун',
'hidetoc'             => 'чуьнуьхун',
'thisisdeleted'       => '$1 килигун ва я туькIуьр хъувун?',
'viewdeleted'         => '$1 килигун?',
'feedlinks'           => 'Хулан жуьре',
'site-rss-feed'       => '$1 — RSS-зул',
'site-atom-feed'      => '$1 -  атом-зул',
'page-rss-feed'       => '"$1" РСС Xуьрек',
'page-atom-feed'      => '"$1" Атом-зул',
'red-link-title'      => '$1 (ихьтин ччин авайд туш)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Ччин',
'nstab-user'      => 'Иштиракчидин ччин',
'nstab-special'   => 'Куьмекчи ччин',
'nstab-project'   => 'Проектдин ччин',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Малумат',
'nstab-template'  => 'Чешне',
'nstab-category'  => 'Категория',

# General errors
'error'              => 'Гъалатl',
'missing-article'    => 'Ганайбурун базади жугъуриз герек тир  "$1" $2 т!вар алай  ччиндин текст жагъанвач

Ихьтин гьал адет яз алуднавай ччиндин дегишвилерин тарихдиз цlуру  рекьяй элячlайла арадал къвезва.

Себеб ам туштlа, виридалайни мумкин тирди куьне программада гъалатl жугъурун я
Тавакъу ийида, URL  къалурна адакай   [[Special:ListUsers/sysop|администратордиз]] хабар це.',
'missingarticle-rev' => '(жуьре#: $1)',
'badtitle'           => 'Ииже текъвер тIвар',
'badtitletext'       => 'ТIалабзавай ччин  я вичел амал алачир я,  я  ичIи я,  ва я чIаларарадин ва я викиарадин кьилин цlарар чlурукlа къалурнава. Мумкин я, кьилин цlарара сад ва я адалай гзаф рехъ гун виже текъвер символар кардик кутунвайди я.',
'viewsource'         => 'Килигун',
'viewsourcefor'      => 'идаз $1',

# Virus scanner
'virus-unknownscanner' => 'Малумтушир антивирус',

# Login and logout pages
'yourname'                => 'Иштиракчидин тlвар',
'yourpassword'            => 'Парол',
'yourpasswordagain'       => 'Парол кхьин хъувун:',
'remembermypassword'      => 'И браузерда зи логин рикlел хуьхь (лап гзаф $1 {{PLURAL:$1|югъ|йикъар}})',
'login'                   => 'Гьахьун',
'nav-login-createaccount' => 'ГЬахьун/аккаунт туькlуьрун',
'loginprompt'             => 'Системадиз гьахьун патал "куки" -яр куькlуьрна кIанзава',
'userlogin'               => 'ГЬахьун/аккаунт туькlуьрун',
'userloginnocreate'       => 'Гьахьун',
'logout'                  => 'ЭкъечIун',
'userlogout'              => 'ЭкъечIун',
'nologin'                 => 'Квез аккаунт авачни? $1.',
'nologinlink'             => 'Аккаунт туькlуьрун',
'createaccount'           => 'Аккаунт туькlуьрун',
'gotaccount'              => 'Квез аакаунт авайд я?$1',
'gotaccountlink'          => 'Гьахьун',
'createaccountmail'       => 'Э-чар галаз',
'createaccountreason'     => 'Себеб:',
'mailmypassword'          => 'ЦIийи парол Э-мейлдиз къачун',
'loginlanguagelabel'      => 'ЧIал: $1',

# Password reset dialog
'resetpass'   => 'Куьлег дегишарун',
'oldpassword' => 'ЦIуру куьлег:',
'newpassword' => 'ЦIийи куьлег:',

# Edit page toolbar
'bold_sample'     => 'КЪалин текст',
'bold_tip'        => 'КЪалин текст',
'italic_sample'   => 'Курсивдин текст',
'italic_tip'      => 'Курсивдин текст',
'link_sample'     => 'Элячlунин кьилин цlар',
'link_tip'        => 'Къенепатан элячlун',
'extlink_sample'  => 'http://www.example.com элячlунин кьилин цlар',
'extlink_tip'     => 'Къецепатан элячlун ( http:// префикс рикlел хуьх)',
'headline_sample' => 'Кьилин цlарцlин текст',
'headline_tip'    => '2-й дережадин кьилин цlар',
'nowiki_sample'   => 'Формат тавунвай текст иниз тур',
'nowiki_tip'      => 'Викидин форматун гьисаба кьамир',
'image_tip'       => 'Ттунвай файл',
'media_tip'       => 'Файлдин элячlун',
'sig_tip'         => 'Куь къулни вахт',
'hr_tip'          => 'КЪаткай цlар (фад-фад кардик кутумир )',

# Edit pages
'summary'                          => 'Нетижа:',
'subject'                          => 'Тема/кьилинцIар',
'minoredit'                        => 'ГЪвечIи дуьзар хъувун',
'watchthis'                        => 'И ччин гуьзетун',
'savearticle'                      => 'Ччин хуьн',
'preview'                          => 'Сифтедин килигун',
'showpreview'                      => 'Сифтедин килигун къалурун',
'showdiff'                         => 'Дегишвилер къалурун',
'anoneditwarning'                  => "'''Дикъет:''' Куьне системадиз жув вуж ятIа лагьанвач. Куь IP-адрес и ччинин дегишвилерин тарихдиз  кхьида.",
'summary-preview'                  => 'Сифте килигун паталди:',
'nosuchsectiontitle'               => 'Пай жугъуриз жезвач',
'loginreqlink'                     => 'гьахьун',
'newarticle'                       => '(ЦIийи)',
'newarticletext'                   => 'Куьне гьеле авачир ччиниз элячlнава.  
Ам туькlуьрун патал агъадихъ галай дакlарда текст гьадра. (гегьеншдиз [[{{MediaWiki:Helppage}}|куьмекдин ччина]] килигиз жеда).
Куьне инал гъалатlдин гъиляй элячlнаватlа, кьу браузердин "кьулухъ"" дуьгмедал илиса.',
'noarticletext'                    => 'Исятда и  ччинда са текстни авач.
Квевай [[Special:Search/{{PAGENAME}}| и тlвар алай ччин]] муькуь ччинра жугъуриз,
<span class="plainlinks"> [{{fullurl: {{# Special:Log}} | ччин = {{FULLPAGENAMEE}}}} журналрин талукь тир кхьей затIар жугъуриз],
ва я [{{fullurl: {{FULLPAGENAME}} | action=edit}} и тlвар алай ччин туькIуьриз жеда] </span>.',
'noarticletext-nopermission'       => 'Исятда и  ччина са текстни авач.
Квевай [[Special:Search/{{PAGENAME}}| и тlвар алай ччин]] муькуь ччинра жугъуриз ва я
<span class="plainlinks"> [{{fullurl: {{# Special:Log}} | page = {{FULLPAGENAMEE}}}} журналрин талукь тир кхьей затIар жугъуриз] жеда.',
'previewnote'                      => "'''Рикlел хуьх хьи, им анжах сифтедин килигун я.'''  
Куь дегишунар гьеле хвенвач!",
'editing'                          => '$1 Дуьзар хъувун',
'editingsection'                   => 'Дуьзар хъувун $1  (пай)',
'copyrightwarning'                 => "Тавакъу ийида, фагьум ая хьи, {{SITENAME}}-диз кутунвай вири крариз $2 лицензиядин шартунал акъуднавайбур хьиз килигда. (гегьеншдиз $1-з килига). 
Квез куьне кхьенвайбур азаддаказ чкIун ва гьар са кас  патахъай дуьзар хъувун кIанзавачтIа, а кхьенвайбур иниз эцигмир.<br />
ГЬакIни, куьне тестикьзава хьи, кутазвай алавайрин автор кьун я, я тахьайтIа, куьне а алаваяр чпин къенеавайбур азад чкIунни дегишун ихтияр гузвай чешмедикай ччин къачунва.<br />
'''АВТОРДИН ИХТИЯР ХУЬЗВАЙ МАЛУМАТАР ИХТИЯР ГАЛАЧИЗ ЭЦИГМИР!'''",
'templatesused'                    => 'И ччина кардик кутунвай {{PLURAL:$1|Чешне|Чешнеяр}}:',
'templatesusedpreview'             => '{{PLURAL:$1|Шаблон|Шаблонар}},илемишзавай дуьз клигунра:',
'template-protected'               => '(хвенвай)',
'template-semiprotected'           => '(са кьадар хвенва)',
'hiddencategories'                 => 'И ччин {{PLURAL: $1 | чуьнуьхай категориядиз | $1 чуьнуьхай категорийриз}} талукь я:',
'permissionserrorstext-withaction' => 'Квез и {{PLURAL:$1|себебдалди|себебралди}} $2 йиз ихтияр авайд туш:',
'recreate-moveddeleted-warn'       => "'''Дикъет! Куьне виликда алуднавай ччин туьхкlуьриз алахъзава.'''
Квевай и ччинин туьхкlуьрунин гереквилиз килигиз тIалабзава.
Агъадихъ и ччинин алудун ва тIвар эхцигунин журнал къалурнава.",
'moveddeleted-notice'              => 'И ччин алуднава. 
Агъадихъ малумат патал и ччинин алудун ва тIвар эхцигунин журнал къалурнава.',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => "'''Дикъет:''' Кутазвай чешнейрин кьадар гзаф чIехи я.
Бязи чешнеяр кутадач.",
'post-expand-template-inclusion-category' => 'Кутунай чешнейрин кьадардилай виниз экъечIнавай ччинар',
'post-expand-template-argument-warning'   => "'''Дикъет:''' И ччина ахъайна гегьеншрун патал гзаф чlехи кьадар авай гьич тахьайтIа са чешнедин аргумент ава.
 Ахьтин аргументар тагана элячIнава.",
'post-expand-template-argument-category'  => 'Кими авунвай  чешнейрин аргументар авай ччинар',

# History pages
'viewpagelogs'           => 'И ччиниз талукь тир журналар къалура',
'currentrev-asof'        => '$1 тарихдиз талукь тир алай жуьре',
'revisionasof'           => '$1 жуьре',
'revision-info'          => '$2 патал авунвай $1 тарихдин дегишун',
'previousrevision'       => '←Вилик алатай жуьре',
'nextrevision'           => 'Мадни цlийи жуьре →',
'currentrevisionlink'    => 'Алай жуьре',
'cur'                    => 'алай',
'next'                   => 'къведайди',
'last'                   => 'вилик алатай',
'page_first'             => 'Садлагьайди',
'page_last'              => 'эхиримжи',
'histlegend'             => 'Тафаватдиз килигун: гекъигиз кlанзавайди жуьредин патав радио-кьватияр лишан ая ва  "ГЬалдун (Enter)"  ва я агъада авай дуьгмедиз илиса.<br />     
ГЪавурда твазвайди: (алай)- алай жуьредикай тафават; (вилик фейи) - вилик фейи жуьредикай тафават; "гъ" - гъвечIи дегишун.',
'history-fieldset-title' => 'Тарихдиз килигун',
'history-show-deleted'   => 'Анжах алуднавайбур',
'histfirst'              => 'Виридалайни цIуру',
'histlast'               => 'Мукьвара хьайи',

# Revision feed
'history-feed-item-nocomment' => '$1  $2-аз',

# Revision deletion
'rev-delundel'               => 'къалурун/кIевирун',
'revdelete-show-file-submit' => 'Эхь',
'revdelete-hide-image'       => 'Файлдин къенеавайбур чуьнуьхун',
'revdelete-hide-name'        => 'Карни адан объект чуьнуьхун',
'revdelete-radio-set'        => 'Эхь',
'revdelete-radio-unset'      => 'Ваъ',
'revdelete-log'              => 'Кар',
'revdelete-logentry'         => 'Дегишарна акунвал "[[$1]]"',
'revdel-restore'             => 'Аквадайвал дегишарун',
'revdel-restore-deleted'     => 'Алуднавай жуьреяр',
'revdel-restore-visible'     => 'Аквадай дегишвилер',
'pagehist'                   => 'Ччинин тарих',
'revdelete-content'          => 'Къйеда',
'revdelete-uname'            => 'ишлемишчидин тIар',
'revdelete-hid'              => 'чуьнуьх авунай $1',
'revdelete-log-message'      => '$1 идай $2 {{PLURAL:$2|Жуьре|Жуьреяр}}',
'revdelete-edit-reasonlist'  => 'Алудунин себебар дуьзар хъувун',
'revdelete-offender'         => 'Автордин жуьре:',

# History merging
'mergehistory-from'   => 'Сифте кьилин ччин:',
'mergehistory-into'   => 'Мураддин ччин',
'mergehistory-submit' => 'Дуьзар хъувунар сад авун',
'mergehistory-reason' => 'Себеб',

# Merge log
'mergelog'    => 'Сад авунин журнал',
'revertmerge' => 'Ччара авун',

# Diffs
'history-title'           => '$1-ан дегишвилерин тарих',
'difference'              => '(Жуьрейрин арада тафаватар)',
'lineno'                  => 'ЦIар $1:',
'compareselectedversions' => 'Хкягъай жуьреяр гекъигун',
'editundo'                => 'гьич авун',
'diff-multi'              => '({{PLURAL:$2|СА иштиракчи|$2 иштиракчияр}} патал авунвай {{PLURAL:$1|са арадин жуьре|$1 арадин жуьреяр}} къалурнавач)',

# Search results
'searchresults'                    => 'Къекъуьнрин нетижаяр',
'searchresults-title'              => '"$1" жугъура',
'searchresulttext'                 => '{{SITENAME}} къекъуьнихъай гегьенш малумат патал  [[{{MediaWiki:Helppage}}|{{int:малумат гудай пай}}]]диз килига.',
'searchsubtitle'                   => '[[Special:WhatLinksHere/$1|И тIварциз элячIзавай]]) [[:$1]] жугъуруниз талукь тир ([[Special:Prefixindex/$1| тIварцихъ галаз эгечIзавай ччинар]]',
'searchsubtitleinvalid'            => "Жугъурзавай: '''$1'''",
'notitlematches'                   => 'Ччинрин тIварара ацалтунар авач',
'notextmatches'                    => 'Авач чарчин кьил матчар',
'prevn'                            => 'Вилик фейи  {{PLURAL:$1|$1}}',
'nextn'                            => 'Гуьгъуьнин {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Вилик фейи  $1 {{PLURAL:$1|нетижа|нетижаяр}}',
'nextn-title'                      => 'КЪведай $1 {{PLURAL:$1|нетижа|нетижаяр}}',
'shown-title'                      => 'Ччина $1 {{PLURAL:$1|нетижа|нетижаяр}} къалура',
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
'search-result-size'               => '$1 ({{PLURAL:$2|1 гаф|$2 гафар}})',
'search-result-category-size'      => '{{PLURAL:$1|1 элемент|$1 элементар}} ({{PLURAL:$2|1 субкатегория|$2 субкатегорияр}}, {{PLURAL:$3|1 файл|$3 файлар}})',
'search-redirect'                  => '(рахкъурун $1)',
'search-section'                   => '(пай $1)',
'search-suggest'                   => 'Мумкин я хьи, куьне им фикирда кьуна: $1',
'search-interwiki-caption'         => 'Мукьва проект',
'search-interwiki-default'         => '$1(жавабар)',
'search-interwiki-more'            => '(мадни)',
'search-mwsuggest-enabled'         => 'меслятар галаз',
'search-mwsuggest-disabled'        => 'меслятар галачиз',
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
'powersearch-toggleall'            => 'Вири',
'powersearch-togglenone'           => 'Садни',

# Quickbar
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
'prefs-datetime'            => 'Нумра ва вахт',
'prefs-personal'            => 'Иштиракчидин профил',
'prefs-rc'                  => 'Mукьвара хьайи дегишвилер',
'prefs-watchlist'           => 'Гуьзетунин сиягь',
'prefs-watchlist-edits-max' => 'Максимум кьадар: 1000',
'prefs-watchlist-token'     => 'Гуьзетунин сиягьдин лишан',
'prefs-misc'                => 'Муькуь низамарунар',
'prefs-resetpass'           => 'Парол дегишарун',
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
'timezonelegend'            => 'Вахтунин минзил',
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
'username'                  => 'Ишлемишчидин тlар',
'yourrealname'              => 'Xалис тIвар:',
'yourlanguage'              => 'ЧIалар',
'gender-female'             => 'Фамили',
'email'                     => 'E-mail',
'prefs-advancedediting'     => 'Гегьенш низамарунар',
'prefs-advancedrc'          => 'Гегьенш низамарунар',
'prefs-advancedrendering'   => 'Гегьенш низамарунар',
'prefs-advancedwatchlist'   => 'Гегьенш низамарунар',

# User rights
'userrights-reason' => 'Кар',

# Groups
'group-sysop' => 'Къавха',
'group-all'   => '(вири)',

'group-user-member' => 'ишлемишчи',
'group-bot-member'  => 'бот',

'grouppage-user'  => '{{ns:project}}:Иштиракчияр',
'grouppage-sysop' => '{{ns:project}}:Къавхаяр',

# Rights
'right-edit' => 'Дегишар хъувун',

# User rights log
'rightslog' => 'Эхтияр Ишлемишчидин дафтlар',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'И ччин дуьзар хъувун',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|дегиш|дегишунар}}',
'recentchanges'                   => 'Mукьвара хьайи дегишвилер',
'recentchanges-legend'            => 'Цlийи дегишвилерин низамарунар',
'recentchangestext'               => 'И ччина викида хьанвай эхиримжи дегишунар гуьзетун',
'recentchanges-feed-description'  => 'И хвала викида хьанвай эхиримжи дегишунар гуьзетун',
'recentchanges-label-newpage'     => 'И дуьзар хъувун  цlийи ччин туькlуьрна',
'recentchanges-label-minor'       => 'Им гъвечlи дуьзар хъувун я',
'recentchanges-label-bot'         => 'И дуьзар хъувун ботди авунвайд я',
'recentchanges-label-unpatrolled' => 'И дуьзар хъувундин винел патрулвал авунвач',
'rcnote'                          => "$5, $4 чIавун кьатIдиз талукь {{PLURAL:$2|югъ|'''$2''' йикъар}} къене{{PLURAL:$1|эхиримжи'''1''' дегишун|эхиримжи '''$1''' дегишунар}}",
'rcnotefrom'                      => "Агъадихъ '''$2'''-ай эгечIна дегишвилер къалурнава ( '''$1''' кьван  къалурнава).",
'rclistfrom'                      => '$1-ла эгечIна цIийи дегишвилер къалура',
'rcshowhideminor'                 => '$1 гъвечlи дуьзар хъувунар',
'rcshowhidebots'                  => '$1 ботар',
'rcshowhideliu'                   => '$1 чпин тlвар къалурнавай иштиракчияр',
'rcshowhideanons'                 => '$1 чуьнуьхай иштиракчияр',
'rcshowhidepatr'                  => '$1 гуьзчивал авунвай дуьзар хъувунар',
'rcshowhidemine'                  => '$1 зи  дуьзар хъувунар',
'rclinks'                         => 'Эхиримжи $2 йикъан къене $1 дегишвилер къалура <br />$3',
'diff'                            => 'тафават',
'hist'                            => 'тарих',
'hide'                            => 'Чуьнуьхун',
'show'                            => 'Къалурун',
'minoreditletter'                 => 'гъ',
'newpageletter'                   => 'ЦI',
'boteditletter'                   => 'б',
'rc-enhanced-expand'              => 'Куьлуь-шуьлуьяр къалурун (JavaScript герекзава)',
'rc-enhanced-hide'                => 'Куьлуь-шуьлуьяр чуьнуьха',

# Recent changes linked
'recentchangeslinked'          => 'Галкlанавай дегишвилер',
'recentchangeslinked-toolbox'  => 'Галкlанвай дегишвилер',
'recentchangeslinked-title'    => '"$1" галаз галкlанавай дегишвилер',
'recentchangeslinked-noresult' => 'Ганвай чlава галкlанавай ччинра са дегишвални хьанвайд туш',
'recentchangeslinked-summary'  => 'Им къалурай ччиниз (ва я къалурай категориядиз гьатзавай ччинриз) элячIзавай ччинра мукьвара хьайи дегишвилерин сиягь я. Куь [[Special:Watchlist| гуьзетунин сиягь  ]]диз гьатзавай  ччинар яцlу шрифтдал къалурнава.',
'recentchangeslinked-page'     => 'Ччинин тlвар:',
'recentchangeslinked-to'       => 'Аксина, къалурай ччиниз элячlзавай ччинра дегишвилер къалура',

# Upload
'upload'        => 'Файл ппарун',
'uploadlogpage' => 'Ппарунин журнал',
'filedesc'      => 'Нетижа',
'uploadedimage' => '"[[$1]]" ппарна',

'license'        => 'Лицензияватун:',
'license-header' => 'Лицинзиярун',

# Special:ListFiles
'listfiles_date' => 'Нумра',
'listfiles_name' => 'ТIар',
'listfiles_user' => 'Иштиракчи',
'listfiles_size' => 'Кьадар',

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
'filehist-user'             => 'Иштиракчи',
'filehist-dimensions'       => 'Кьадарар',
'filehist-comment'          => 'КЪейд',
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

# File deletion
'filedelete-comment' => 'Кар',
'filedelete-submit'  => 'Къакъудун',

# MIME search
'mimesearch' => 'MIME ахтармишун',

# Random page
'randompage' => 'Дуьшуьшдин ччин',

# Statistics
'statistics' => 'Статистика',

'disambiguationspage' => 'Template:гзафманавал',

'brokenredirects-edit'   => 'дегишарун',
'brokenredirects-delete' => 'къакъудун',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|байт|байтар}}',
'nmembers'      => '$1 {{PLURAL:$1|уьзви|уьзвияр}}',
'lonelypages'   => 'Eтим xъувун',
'prefixindex'   => 'Префикс галай вири ччинар',
'shortpages'    => 'Куьруь хъувун',
'longpages'     => 'Яргъи хъувун',
'usercreated'   => 'И чIава туькlуьрнава: $1 $2',
'newpages'      => 'ЦIийи ччинар',
'move'          => 'ТIвар эхцигун',
'movethispage'  => 'Юзун и хъувун',
'pager-newer-n' => '{{PLURAL:$1|мадни цIийи 1|мадни цIийи $1}}',
'pager-older-n' => '{{PLURAL:$1|мадни цIуру 1|мадни цIуру $1}}',

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
'allpagesnext'   => 'Къведайди',
'allpagessubmit' => 'ЭлячIун',

# Special:Categories
'categories' => 'Категорияр',

# Special:LinkSearch
'linksearch'      => 'Къецlин алукьунар',
'linksearch-ok'   => 'Ахтармишун',
'linksearch-line' => '$2-ай $1-аз элячlун',

# Special:Log/newusers
'newuserlogpage'          => 'Иштиракчийрин туькlуьрунин журнал',
'newuserlog-create-entry' => 'ЦIийи ишлемишчидин чин',

# Special:ListGroupRights
'listgrouprights-members' => '(уьзвийрин сиягь)',

# E-mail user
'emailuser'    => 'Иштиракчидиз чар кхьихь',
'emailfrom'    => 'Идай',
'emailmessage' => 'Хъагъаз',

# Watchlist
'watchlist'         => 'Зи гуьзетунин сиягь',
'mywatchlist'       => 'Зи гуьзетунин сиягь',
'watchlistfor2'     => '$1 $2 патал',
'addedwatch'        => 'Ктун хъувун ,ахтармишзай чарчхъ',
'addedwatchtext'    => "Чар \"[[:\$1]]\" тун хъувунай куьн [[Special:Watchlist|watchlist]].                                                                                                             Къвезмай дегишунар и чарчел ва галкlанавай чарчихъ ихтилатар жеда инна, ахъатдава \"сакlус яцlу''''' инна [[Special:RecentChanges|list of recent changes]] гьам кьизил авун.",
'removedwatch'      => 'Чlурнава ахтармишзавай цlарцlяй',
'removedwatchtext'  => 'Чар "[[:$1]]" Идай чlурнай [[Special:Watchlist|ахтармишунин цlарар]].',
'watch'             => 'Гуьзетун',
'watchthispage'     => 'Гелкъуьн и хъувун',
'unwatch'           => 'Гуьзет мийир',
'watchlist-details' => 'Куь гуьзетунин сиягьда {{PLURAL:$1|$1 ччин|$1 ччинар}}, веревирдрин ччинар квачиз.',
'wlshowlast'        => 'Эхиримжи $1 сят $2 югъ $3 къалура',
'watchlist-options' => 'Гуьзетунин сиягьдин низамарунар',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Килигун...',
'unwatching' => 'Амма клигнай',

# Delete
'deletepage'            => 'Къакъудун хъувун',
'confirmdeletetext'     => 'Квез чlуриз кlанзани чарар гьадан вири тарихар галаз.                                                                                                                         Буюр, сидикъара,куьне чlурзатlа, куьн агъавурда автlа вуч ийизатlа ва куьне ийизатlа жуьреда [[{{MediaWiki:Policy-url}}| политика]].',
'actioncomplete'        => 'Кар авунва',
'actionfailed'          => 'Кар йиз алакьнавач',
'deletedtext'           => '"<nowiki>$1</nowiki>" чlурнайтир.                                                                                                                                                       Килиг $2 эхиримжи  чlурунар ахтармишун.',
'deletedarticle'        => 'къакъудун "[[$1]]"',
'dellogpage'            => 'Алудунин журнал',
'deletecomment'         => 'Кар',
'deleteotherreason'     => 'Масса/ ва мад кар',
'deletereasonotherlist' => 'Маса фагьум',

# Rollback
'rollbacklink' => 'КЬулухъди чIугун',

# Protect
'protectlogpage'              => 'Хуьнин журнал',
'protectedarticle'            => '"[[$1]]" ччин хвенва',
'modifiedarticleprotection'   => 'дегиш хьанахуьнун кьадар идаз "[[$1]]"',
'protectcomment'              => 'Кар',
'protectexpiry'               => 'Алатна',
'protect_expiry_invalid'      => 'Вахтун кьадар дуьзди туш.',
'protect_expiry_old'          => 'Вахтун кьадар алатай заманда.',
'protect-text'                => "Квевай клигайтlа ва дегишарайтlа жеза хуьнин къайда чарчин '''<nowiki>$1</nowiki>'''.",
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
'restriction-edit' => 'Дегишарун',

# Undelete
'undeletelink'              => 'Килигун/гуьнгуьна хтун',
'undeleteviewlink'          => 'Килигун',
'undeletecomment'           => 'Кар',
'undeletedarticle'          => 'Туькlуьр хъувуна "[[$1]]"',
'undelete-show-file-submit' => 'Э',

# Namespace form on various pages
'namespace'      => 'Тlварарин генгвал:',
'invert'         => 'Хкягънавайди элкъуьрун',
'blanknamespace' => '(Асул)',

# Contributions
'contributions'       => 'Иштиракчиди кутур крар',
'contributions-title' => '$1 иштиракчидин кутур крар',
'mycontris'           => 'За кутур крар',
'contribsub2'         => '($1)-ин кутур пай  ($2)',
'uctop'               => '(вини кьил)',
'month'               => ' Вацралай (ва адалай вилик)',
'year'                => 'Иисалай (ва адалай вилик):',

'sp-contributions-newbies'  => 'Анжах цlийи иштиракчийрин кутур крар къалура',
'sp-contributions-blocklog' => 'Блокарунин журнал',
'sp-contributions-uploads'  => 'ппарунар',
'sp-contributions-logs'     => 'журналар',
'sp-contributions-talk'     => 'Рахун',
'sp-contributions-search'   => 'Кутур пай жугъура',
'sp-contributions-username' => 'IP -адрес ва я  иштиракчидин тlвар',
'sp-contributions-toponly'  => 'Анжах эхиримжи жуьре тир дуьзар хъувунар къалура',
'sp-contributions-submit'   => 'Жугъурун',

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
'whatlinkshere-hideimages' => '$1 шикилриз элячIунар',
'whatlinkshere-filters'    => 'Куьзунагар',

# Block/unblock
'blockip'                  => 'Ишлемишзавайдан хара',
'ipboptions'               => '2 сят:2 hours,1 югъ:1 day,3 югъ:3 days,1 никIи:1 week,2 никIи:2 weeks,1 варз:1 month,3 варз:3 months,6 варз:6 months,1 йис:1 year,вахт алачир:infinite',
'ipblocklist'              => 'Блокарнавай иштиракчияр',
'blocklink'                => 'Блок авун',
'unblocklink'              => 'Блок къахчун',
'change-blocklink'         => 'Блокарун дегишарун',
'contribslink'             => 'кутур крар',
'blocklogpage'             => 'Блокарунин журнал',
'blocklogentry'            => '[[$1]] блокарна,  $2 $3 чIав кьван',
'unblocklogentry'          => 'Куьлегдай акъудун $1',
'block-log-flags-nocreate' => 'Аккаунт туькIуьрдай ихтияр авач',

# Move page
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
'1movedto2'        => 'хъфена [[$1]] идал [[$2]]',
'1movedto2_redir'  => 'къфена [[$1]] идаз [[$2]] ракъурунар',
'movelogpage'      => 'Тlвар эхцигунрин журнал',
'movereason'       => 'Фагьум:',
'revertmove'       => 'Рахкъурун',

# Export
'export' => 'Ччинрин экспорт',

# Namespace 8 related
'allmessagesname'        => 'Тlвар',
'allmessagesdefault'     => 'Авайд хьиз кьунвай текст',
'allmessages-filter-all' => 'Вири',

# Thumbnails
'thumbnail-more'  => 'ЧIехи авун',
'thumbnail_error' => 'Бицlи шикил  туькlуьрунин гъалатl:$1',

# Special:Import
'import-upload-filename' => 'Шикилдинтlар:',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Куь ишлемишзавайдин ччин',
'tooltip-pt-mytalk'               => 'Куь веревирдрин ччин',
'tooltip-pt-preferences'          => 'Куь низамарунар',
'tooltip-pt-watchlist'            => 'Куьне гуьзетзавай ччинрин сиягь',
'tooltip-pt-mycontris'            => 'Куьне авунвай дуьзар хъувунрин сиягь',
'tooltip-pt-login'                => 'Квез гьахьиз теклифзава, анжах им мажбури туш',
'tooltip-pt-logout'               => 'ЭкъечIун',
'tooltip-ca-talk'                 => 'КЪене авайбурун ччин веревирд авун',
'tooltip-ca-edit'                 => 'Квевай и ччин дуьзар хъииз жеда. Ччин хуьдалай вилик квекай сифтедин килигун дуьгмедикай менфят къачуз тlалабзава
.',
'tooltip-ca-addsection'           => 'Гатlунив цlийи кьил',
'tooltip-ca-viewsource'           => 'И ччин хвенвайд я, амма квевай адан къене авайбуруз килигиз жеда.',
'tooltip-ca-history'              => 'И ччиндин алатай дегишвилерин журнал',
'tooltip-ca-protect'              => 'И ччин хуьн',
'tooltip-ca-delete'               => 'И ччин алудун',
'tooltip-ca-move'                 => 'Ччиндин тIвар дегишрун',
'tooltip-ca-watch'                => 'И ччин куь гуьзетунин сиягьдиз алава авун',
'tooltip-ca-unwatch'              => 'И ччин куь гуьзетунин сиягьдал къахчун',
'tooltip-search'                  => 'И гаф жугъурун{{SITENAME}}',
'tooltip-search-go'               => 'АватIа, гьа и тIвар авай ччиниз элячIун',
'tooltip-search-fulltext'         => 'Къалурай текст авай ччинар жугъура',
'tooltip-p-logo'                  => 'КЬилин ччиндин кьилив фин',
'tooltip-n-mainpage'              => 'КЬилин ччиндиз элячIун',
'tooltip-n-mainpage-description'  => 'КЬилин ччиндиз элячIун',
'tooltip-n-portal'                => 'Проектдикай,  квевай вуч йийз алакьда, са вуч ятIани гьинай жугъурда',
'tooltip-n-currentevents'         => 'Алай вакъийрин сиягь',
'tooltip-n-recentchanges'         => 'Викида мукьвара хьайи дегишвилерин сиягь',
'tooltip-n-randompage'            => 'Дуьшуьшдин чин ппарун',
'tooltip-n-help'                  => 'Жугъурун патал чка',
'tooltip-t-whatlinkshere'         => 'Иниз элячIзавай викидин вири  ччинрин сиягь',
'tooltip-t-recentchangeslinked'   => 'И ччиндиз элячIзавай ччинра  мукьвара хьайи дегишвилер',
'tooltip-feed-rss'                => 'RSS  хуьрек и чарчиз',
'tooltip-feed-atom'               => 'И ччиндин Atom -дин трансляция',
'tooltip-t-contributions'         => 'И иштиракчидин кутур крарин сиягь',
'tooltip-t-emailuser'             => 'И иштиракчидиз электрон чар ракъура',
'tooltip-t-upload'                => 'Шикилар ва я мультимедиядин файлар ппарун',
'tooltip-t-specialpages'          => 'Куьмекчи ччинрин сиягь',
'tooltip-t-print'                 => 'И ччиндин басма авун патал жьуре',
'tooltip-t-permalink'             => 'Ччиндин и жуьредиз гьамишан элячIун',
'tooltip-ca-nstab-main'           => 'КЪене авайбурун ччиндиз  килигун',
'tooltip-ca-nstab-user'           => 'Иштиракчидин ччиниз килигун',
'tooltip-ca-nstab-special'        => 'Им куьмекдин ччин я, квевай и ччин дуьзар хъийиз жедач',
'tooltip-ca-nstab-project'        => 'Проектдин ччиниз килигун',
'tooltip-ca-nstab-image'          => 'Файлдин ччиндиз килигун',
'tooltip-ca-nstab-template'       => 'Чешнедиз килигун',
'tooltip-ca-nstab-category'       => 'Категориядин ччиниз килигун',
'tooltip-minoredit'               => 'И дегишун гъвечlи дуьзар хъувун хьиз къейд ая',
'tooltip-save'                    => 'Куь дегишунар хуьн',
'tooltip-preview'                 => 'Ччин хуьдалай вилик сифтедин килигундикай менфят къачуз т!алабзава',
'tooltip-diff'                    => 'Сифте кьилин текстдиз талукь тир куьне авунвай дегишвилер къалурун',
'tooltip-compareselectedversions' => 'И ччинин кьве хкягъай жуьрейрин арада авай тафаватдиз килигун',
'tooltip-watch'                   => 'И ччин куь гуьзетунин сиягьдиз алава авун',
'tooltip-rollback'                => '« КЬулухъди чIугун »  и ччиндиз эхиримжи кар кутазвайди патай  авунвай дуьзар хъувунар са т!ампуналди  paxкурзава',
'tooltip-undo'                    => '«ГЬич авун»  авунвай  дуьзар хъувун paxкурзава ва сифтедин килигунин режимда  дуьзар хъувундин форма ахъа йийзва. Им нетижадиз себеб алава йийз  мумкинвал гузва',
'tooltip-summary'                 => 'Куьруь нетижа гьадрун',

# Patrol log
'patrol-log-line' => 'кхьена  $1 идай $2 ахтармишнава $3',
'patrol-log-diff' => 'жуьре $1',

# Browsing diffs
'previousdiff' => 'Вилик алатай дуьзар хъувун',
'nextdiff'     => 'ЦIийи дегишунар',

# Media information
'file-info-size' => '$1 × $2 пикселар, файлдин кьадар: $3, MIME жуьре: $4',
'file-nohires'   => '<small>Чlехи ахъаюн авай жуьре авач.</small>',
'svg-long-desc'  => 'SVG файл, номилдаказ $1 $2 × пикселяр, файлдин кьадар: $3',
'show-big-image' => 'Мадни хъсан еридин шикил',

# Bad image list
'bad_image_list' => 'Формат икl хьана кlанзава:

Анжах сиягьда авай (* лишандихъ галаз эгеч!завай ц!арариз) зат!ариз килигда.
ЦlарцIе авай сад лагьай элячIун ттун патал къадагъа алай шикилдиз  элячIун хьана кlанзава.
ГЬа са цlарцIе  авай гьар са ахпагьан элячIунар  кьетIендинбур хьиз кьабулда, мисал яз, суьрет тваз мумкинвал авай ччинар.',

# Metadata
'metadata'          => 'Метамалуматар',
'metadata-help'     => 'И файлдин къене гилигнавай адет яз камера ва я сканер куьмекдалди алава авунвай  малумат ава. Файл ахпа дуьзур хъувуначтlа, бязи параметрар алай суьретдив кьун тахьун мумкин я.',
'metadata-expand'   => 'Къалурун дериндиз',
'metadata-collapse' => 'Кlевун дерин къалурунар',

# EXIF tags
'exif-contrast' => 'Рангар',

'exif-contrast-1' => 'Жими',

'exif-sharpness-1' => 'Жими',

# External editor support
'edit-externally'      => 'И файл патан программа куьмекдалди дуьзар хъувун',
'edit-externally-help' => '(Алава малумат патал [http://www.mediawiki.org/wiki/Manual:External_editors эцигунин регьбервилиз] килига)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'вири',
'imagelistall'     => 'вири',
'watchlistall2'    => 'вири',
'namespacesall'    => 'вири',
'monthsall'        => 'вири',
'limitall'         => 'вири',

# action=purge
'confirm_purge_button' => 'Э(кхьин)












9',

# Multipage image navigation
'imgmultigo' => 'Ша!',

# Watchlist editing tools
'watchlisttools-view' => 'Сиягьда авай ччинра дегишвилер',
'watchlisttools-edit' => 'Гьузетунин сиягь килигун ва дуьзар хъувун',
'watchlisttools-raw'  => 'Текст хьиз дуьзар хъувун',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Дикъет:\'\'\' Авайд хьиз кьунвай жуьрейриз ччара авунин "$2" куьлег  виликан "$1" жуьрейриз ччара авунин куьлег гьич йийзва.',

# Special:SpecialPages
'specialpages' => 'КьетIен  ччинар',

# External image whitelist
'external_image_whitelist' => ' #И цIар авайд хьиз тур</pre>
#Агъада вахт акадар тийиз жезвай (гьамиша къайдадалди ) лугьунрин кьатIар эцига (// арада авай кIус).
#Ибур кьецепатан суьретрин URL галаз гекъигда.
#Дуьзкъвезвайбур суьретар хьиз къалурда, муькуьбур суьретриз тухузвай элячIунар хьиз къалурда.
# "#" галаз эгечIзавай цIарариз къейдериз хьиз килигда.
#ЦIарар регистрдиз фад кьатIудайбур я.

#ЦIарцин винел вири вахт акадар тийиз жезвай лугьунрин кьатIар эцига.И цIар авайд хьиз тур</pre>',

# Special:Tags
'tag-filter' => '[[Special:Tags|Tag]] куьзунаг:',
'tags-edit'  => 'дегишарун',

# Special:ComparePages
'compare-page1' => 'Чар 1',

);
