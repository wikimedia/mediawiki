<?php
/** Tajik (Тоҷикӣ)
 *
 * @addtogroup Language
 *
 * @author Francis Tyers
 * @author SPQRobin
 * @author Soroush
 * @author FrancisTyers
 * @author Siebrand
 * @author לערי ריינהארט
 * @author Cbrown1023
 * @author Ibrahim
 * @author Nike
 * @author Farrukh
 */

$namespaceNames = array(
	NS_MEDIA          => "Медиа",
	NS_SPECIAL        => "Вижа",
	NS_MAIN           => "",
	NS_TALK           => "Баҳс",
	NS_USER           => "Корбар",
	NS_USER_TALK      => "Баҳси_корбар",
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => "Баҳси_$1",
	NS_IMAGE          => "Акс",
	NS_IMAGE_TALK     => "Баҳси_акс",
	NS_MEDIAWIKI      => "Медиавики",
	NS_MEDIAWIKI_TALK => "Баҳси_медиавики",
	NS_TEMPLATE       => "Шаблон",
	NS_TEMPLATE_TALK  => "Баҳси_шаблон",
	NS_HELP           => "Роҳнамо",
	NS_HELP_TALK      => "Баҳси_роҳнамо",
	NS_CATEGORY       => "Гурӯҳ",
	NS_CATEGORY_TALK  => "Баҳси_гурӯҳ",
);

$datePreferences = array(
	'default',
	'dmy',
	'persian',
	'ISO 8601',
);

$defaultDateFormat = 'dmy';

$datePreferenceMigrationMap = array(
	'default',
	'default',
	'default',
	'default'
);

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i، j xg Y',

	'persian time' => 'H:i',
	'persian date' => 'xij xiF xiY',
	'persian both' => 'H:i، xij xiF xiY',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхчшъэюяғӣқўҳҷцщыь]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'        => 'Зерпайвандҳо хат кашида шаванд:',
'tog-highlightbroken'  => 'Пайвандҳои шикастаро <a href="" class="new">ҳамин хел</a> қолаббандӣ кунед (Имкони дигар:ба ин шакл<a href="" class="internal">?</a>).',
'tog-justify'          => 'Тамомченкардани бандҳо',
'tog-hideminor'        => 'Нишон надодани тағйироти ҷузъи дар феҳристи тағйироти охир',
'tog-extendwatchlist'  => 'Густариши феҳристи пайгириҳо барои нишон додани ҳамаи тағйиротҳои марбута',
'tog-numberheadings'   => 'шуморагузори~и худкори инвонҳо',
'tog-showtoolbar'      => 'Намоиши навори абзори вироиш (JavaScript)',
'tog-rememberpassword' => 'Маро дар хотири компютер нигоҳ дор',
'tog-previewonfirst'   => 'Нишон додани пешнамоиш дар нахустин вироиш',
'tog-ccmeonemails'     => 'Нусхаҳои хатҳоро ба ман рои кунед, ман онҳоро ба корбарон рои мекунам',

'underline-always' => 'Доимо',
'underline-never'  => 'Ҳеҷгоҳ',

# Dates
'sunday'        => 'Якшанбе',
'monday'        => 'Душанбе',
'tuesday'       => 'Сешанбе',
'wednesday'     => 'Чоршанбе',
'thursday'      => 'Панҷшанбе',
'friday'        => 'Ҷумъа',
'saturday'      => 'Шанбе',
'sun'           => 'Яш',
'mon'           => 'Ду',
'tue'           => 'Сш',
'wed'           => 'Чш',
'thu'           => 'Пш',
'fri'           => 'Ҷу',
'sat'           => 'Шн',
'january'       => 'Январ',
'february'      => 'Феврал',
'march'         => 'Март',
'april'         => 'Апрел',
'may_long'      => 'май',
'june'          => 'Июн',
'july'          => 'Июл',
'august'        => 'Август',
'september'     => 'Сентябр',
'october'       => 'Октябр',
'november'      => 'Ноябр',
'december'      => 'Декабр',
'january-gen'   => 'Январ',
'february-gen'  => 'феврали',
'march-gen'     => 'марти',
'april-gen'     => 'Апрел',
'may-gen'       => 'май',
'june-gen'      => 'июни',
'july-gen'      => 'Июл',
'august-gen'    => 'Август',
'september-gen' => 'сентябри',
'october-gen'   => 'Октябр',
'november-gen'  => 'Ноябр',
'december-gen'  => 'Декабри',
'jan'           => 'Ян',
'feb'           => 'Фев',
'mar'           => 'Мар',
'apr'           => 'Апр',
'may'           => 'май',
'jun'           => 'Июн',
'jul'           => 'Июл',
'aug'           => 'Авг',
'sep'           => 'Сент',
'oct'           => 'Окт',
'nov'           => 'Нов',
'dec'           => 'Дек',

# Bits of text used by many pages
'categories'      => 'Гурӯҳҳо',
'category_header' => 'Мақолаҳо дар гурӯҳи "$1"',
'subcategories'   => 'Зергурӯҳҳо',

'about'         => 'Дар бораи',
'newwindow'     => '(дар равзанаи ҷадид боз мешавад)',
'cancel'        => 'Лағв',
'qbedit'        => 'Вироиш',
'qbmyoptions'   => 'Саҳифаҳои ман',
'moredotdotdot' => 'Бештар...',
'mypage'        => 'Саҳифаи ман',
'mytalk'        => 'Гуфтугӯи ман',
'anontalk'      => 'Баҳс бо ин IP',
'navigation'    => 'Гаштан',

'returnto'         => 'Бозгашт ба $1.',
'tagline'          => 'Аз {{SITENAME}}',
'help'             => 'Роҳнамо',
'search'           => 'Ҷустуҷӯ',
'searchbutton'     => 'Ҷустуҷӯ',
'go'               => 'Рав',
'searcharticle'    => 'Бирав',
'history'          => 'Таърих',
'history_short'    => 'Таърих',
'printableversion' => 'Нусхаи чопӣ',
'permalink'        => 'Пайванди доимӣ',
'print'            => 'Чоп',
'edit'             => 'Вироиш',
'editthispage'     => 'Вироиши ин саҳифа',
'delete'           => 'Ҳазф',
'deletethispage'   => 'Ин саҳифаро ҳазф кунед',
'protect'          => 'Ҳифз кардан',
'protectthispage'  => 'Ҳифз намудани ин саҳифа',
'newpage'          => 'Саҳифаи нав',
'talkpage'         => 'Ин саҳифаро муҳокима кунед',
'talkpagelinktext' => 'Мубоҳисавии',
'specialpage'      => 'Саҳифаи вижа',
'personaltools'    => 'Абзорҳои шахсӣ',
'talk'             => 'Мубоҳисавии',
'views'            => 'Назарот',
'toolbox'          => 'Ҷаъбаи абзор',
'userpage'         => 'Саҳифаи корбарро бинед',
'projectpage'      => 'Дидани саҳифаи лоиҳа',
'templatepage'     => 'Нигаристани саҳифаи шаблон',
'otherlanguages'   => 'бо забонҳои дигар',
'redirectedfrom'   => '(Тағйири масир аз $1)',
'lastmodifiedat'   => 'Ин саҳифа бори охир $2, $1 дигаргун карда шудааст.', # $1 date, $2 time
'jumpto'           => 'Ҷаҳиш ба:',
'jumptonavigation' => 'новбари',
'jumptosearch'     => 'Ҷустуҷӯи',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Дар бораи {{SITENAME}}',
'aboutpage'         => 'Project:Дар бораи',
'copyrightpagename' => 'Википедиа copyright',
'copyrightpage'     => 'Википедиа:Copyrights',
'currentevents'     => 'Вокеаҳои кунунӣ',
'currentevents-url' => 'Воқеаҳои кунунӣ',
'disclaimers'       => 'Такзибнома',
'disclaimerpage'    => 'Лоиҳа:Такзибномаи умумӣ',
'edithelp'          => 'Роҳнамои вироиш',
'edithelppage'      => 'Роҳнамо:Вироиш',
'helppage'          => 'Роҳнамо:Мундариҷа',
'mainpage'          => 'Саҳифаи Аслӣ',
'portal'            => 'Вуруди корбарон',
'portal-url'        => 'Project:Вуруди корбарон',
'privacy'           => 'Сиёсати ҳифзи асрор',
'privacypage'       => 'Лоиҳа:Сиёсати ҳифзи асрор',
'sitesupport'       => 'Кӯмаки молӣ',
'sitesupport-url'   => 'Лоиҳа:Кӯмаки молӣ',

'badaccess'        => 'Иштибоҳи иҷоза',
'badaccess-group1' => 'Амали шумо дархосткарда ба корбарони ин гурӯҳ $1 маҳдуд аст.',

'pagetitle'               => '$1 - Википедиа',
'retrievedfrom'           => 'Баргирифта аз "$1"',
'youhavenewmessages'      => 'Шумо $1 ($2) доред.',
'youhavenewmessagesmulti' => 'Шумо номаҳои нав дар $1 доред.',
'editsection'             => 'вироиш',
'editold'                 => 'вироиш',
'editsectionhint'         => 'Вироиши қисмат: $1',
'toc'                     => 'Мундариҷа',
'showtoc'                 => 'Намоиш дода шавад',
'hidetoc'                 => 'Пинҳон кардани',
'site-rss-feed'           => 'Барои $1 RSS Хабархон',
'site-atom-feed'          => 'Барои $1 Atom Хабархон',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Мақола',
'nstab-user'      => 'Саҳифаи корбар',
'nstab-special'   => 'Махсус',
'nstab-project'   => 'Саҳифаи лоиҳа',
'nstab-image'     => 'файл',
'nstab-mediawiki' => 'Пайём',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Кӯмак',
'nstab-category'  => 'Гурӯҳ',

# General errors
'error'            => 'Иштибоҳ',
'viewsource'       => 'Намоиши матни вики',
'viewsourcefor'    => 'барои $1',
'editinginterface' => "'''Огоҳи:''' Шумо саҳифаеро вироиш карда истодаед, ки матни интерфейси барнома мебошад. Тағйироти ин саҳифа барои намуди интерфейси дигар корбарон таъсир хоҳад расонид.",

# Login and logout pages
'loginpagetitle'             => 'Вуруди корбар',
'yourname'                   => 'Номи корбар',
'yourpassword'               => 'Калимаи убур\\пароль',
'yourpasswordagain'          => 'Калимаи убурро боз нависед',
'remembermypassword'         => 'Манро дар хотир нигоҳ дор',
'yourdomainname'             => 'Домейни Шумо',
'login'                      => 'Вуруд',
'userlogin'                  => 'Вуруд / Сохтани ҳисоби ҷадид',
'logout'                     => 'Хуруҷ аз систем',
'userlogout'                 => 'Хуруҷ аз систем',
'nologin'                    => 'Номи корбар надоред? $1.',
'nologinlink'                => 'Ҳисоберо созед',
'createaccount'              => 'Ҳисоби ҷадидеро созед',
'userexists'                 => 'Номи корбарӣ дохил кардашуда мавриди истифода аст. Номи дигарероро интихоб кунед.',
'username'                   => 'Номи корбар:',
'uid'                        => 'ID-и корбар:',
'yourrealname'               => 'Номи аслӣ *',
'yourlanguage'               => 'Забон:',
'yourvariant'                => 'Вариант',
'yournick'                   => 'Ники шумо:',
'loginerror'                 => 'Иштибоҳ дар вуруд',
'noname'                     => 'Номи корбари дурустеро шумо пешниҳод накардед.',
'loginsuccesstitle'          => 'Вуруд бо муваффақият',
'loginsuccess'               => "'''Шумо акнун ба Википедиа ҳамчун \"\$1\". вуруд кардед'''",
'wrongpassword'              => 'Калимаи убури нодуруст дохил карда шуд. Бори дигар санҷед.',
'wrongpasswordempty'         => 'Калимаи убури дохил шуда холӣ аст. Бори дигар санҷед.',
'passwordtooshort'           => 'Калимаи убур хеле кӯтоҳ аст. Вай бояд ҳадиақал $1 аломатҳо дошта бошад.',
'mailmypassword'             => 'Калимаи убурро ба E-mail фиристед',
'passwordsent'               => 'Калимаи убури нав ба адреси e-mail, ки барои "$1" номнавис шудааст фиристода шуд.
Баъд аз дастрас кардани он, марҳамат карда вуруд кунед.',
'acct_creation_throttle_hit' => 'Бубахшед, Шумо аллакай $1 ҳисобҳо сохтед. Шумо бештар сохта наметавонед.',
'emailauthenticated'         => 'E-mail нишонаи Шумо дар санаи $1 сабт шудааст.',
'emailconfirmlink'           => 'Адреси почтаи электрониаторо тасдиқ кунед',
'accountcreated'             => 'Ҳисоби ҷадид сохта шуд',
'accountcreatedtext'         => 'Ҳисоби корбар барои $1 сохта шуд.',
'loginlanguagelabel'         => 'Забон: $1',

# Edit page toolbar
'bold_sample'     => 'Матни пурранг',
'bold_tip'        => 'Матни пурранг',
'italic_sample'   => 'Матни хобида',
'italic_tip'      => 'Матни хобида',
'link_sample'     => 'Унвони пайванд',
'link_tip'        => 'Пайванди дохилӣ',
'extlink_sample'  => 'http://www.example.com унвони пайванд',
'extlink_tip'     => 'Пайванди беруна (пешванди http:// фаромӯш накунед)',
'headline_sample' => 'Матни унвон',
'headline_tip'    => 'Унвони сатҳи 2',
'math_sample'     => 'Илова кардани формула дар инҷо',
'math_tip'        => 'Формулаи риёзӣ (LaTeX)',
'nowiki_sample'   => 'Инҷо матни қолаббанди-нашударо дохил кунед',
'nowiki_tip'      => 'Рад кардани қолаббандии вики',
'image_tip'       => 'Тасвири дохили матн',
'media_tip'       => 'Пайванди парвандаи расона',
'sig_tip'         => 'Имзои Шумо бо мӯҳри сана',
'hr_tip'          => 'Хати уфуқӣ (сарфакорона истифода кунед)',

# Edit pages
'summary'               => 'Хулоса',
'subject'               => 'Мавзӯъ/сарлавҳа',
'minoredit'             => 'Ин вироиши хурд аст',
'watchthis'             => 'Назар кардани ин саҳифа',
'savearticle'           => 'Саҳифа захира шавад',
'preview'               => 'Пешнамоиш',
'showpreview'           => 'Пеш намоиш',
'showdiff'              => 'Намоиши тағйирот',
'anoneditwarning'       => "'''Огоҳӣ:''' Шумо вуруд накардаед. Суроғаи IP Шумо дар вироишоти ин саҳифа сабт хоҳад шуд.",
'blockedtitle'          => 'Корбар баста шудааст',
'whitelistedittitle'    => 'Барои вироиш вуруд бояд кард',
'whitelistreadtitle'    => 'Барои хондан бояд вуруд кард',
'whitelistacctitle'     => 'Ба Шумо барои сохтани номи корбар иҷозат нест.',
'nosuchsectiontext'     => 'Шумо хостед, ки қисмеро вироиш кунед, ки он вуҷуд надорад.  То ҳоле, ки қисми $1 вуҷуд надорад, барои вироиши шумо ҷой нест.',
'accmailtitle'          => 'Калимаи убур фиристода шуд.',
'accmailtext'           => 'Калимаи убур барои "$1" ба $2 фиристода шуд.',
'newarticletext'        => "Шумо пайвандеро интихоб кардед, ки саҳифа дар он арзи вуҷуд надорад. Барои 
сохтани саҳифа, ба қуттии зерин нависед ([[{{MediaWiki:Helppage}}|саҳифаи роҳнаморо]] 
барои маълумоти бештар нигаред). Агар аз сабаби хатогӣ ва ё иштибоҳ омадед, 
тугмаи '''Ба оқиб'''-ро дар браузери худ пахш кунед.",
'noarticletext'         => 'Дар ин саҳифа то кунун, матн вуҷуд надорад, шумо метавонед дар дигар саҳифаҳо [[Special:Search/{{PAGENAME}}|унвони ин саҳифаро ҷустуҷӯ кунед]] ё [{{fullurl:{{FULLPAGENAME}}|action=edit}} ин саҳифаро вироиш кунед].',
'previewnote'           => '<strong>Ин фақат пешнамоиш аст; дигаргуниҳо ҳоло захира нашудаанд!</strong>',
'editing'               => 'Дар ҳоли вироиш $1',
'editingsection'        => 'Дар ҳоли вироиши $1 (қисмат)',
'editingcomment'        => 'Дар ҳоли вироиш $1 (comment)',
'yourtext'              => 'Матни Шумо',
'yourdiff'              => 'Фарқиятҳо',
'copyrightwarning'      => 'Ҳамаи ҳиссагузорӣ ба {{SITENAME}} аз рӯи қонунҳои зерин $2 (нигаред $1 барои маълумоти бештар) ҳиссагузорӣ мешаванд. Агар Шумо намехоҳед, ки навиштаҷоти Шумо вироиш ва паҳн нашаванд, Шумо метавонед ин мақоларо нафиристед.<br /> Шумо ваъда медиҳед, ки худатон ин мақоларо навиштед ё ки аз сарчашмаҳои кушод нусхабардорӣ кардаед. <strong>АСАРҲОИ ҚОБИЛИ ҲУҚУҚИ МУАЛЛИФРО БЕ ИҶОЗАТ НАФИРИСТЕД!</strong>',
'templatesused'         => 'Шаблонҳои дар ин саҳифа истифодашуда:',
'template-protected'    => '(ҳифзшуда)',
'recreate-deleted-warn' => "'''Диққат: Шумо саҳифаеро барқарор карда истодаед, ки пештар ҳазф шудааст.''' 

Шумо зарурияти вироиши ин саҳифаро дида баромаданатон лозим. Сабти ҳазфшавии 
ин саҳифа барои фароҳам овардани имкониятҳои қулай оварда шудааст:",

# Account creation failure
'cantcreateaccounttitle' => 'Ҳисобе сохта наметавонам',

# History pages
'nohistory'        => 'Таърихи вироиш барои ин саҳифа вуҷуд надорад.',
'currentrev'       => 'Вироишоти кунунӣ',
'revisionasof'     => 'Нусха $1',
'previousrevision' => '←Нусхаи кӯҳнатар',
'nextrevision'     => 'Нусхаи навтарин→',
'cur'              => 'феълӣ',
'last'             => 'қаблӣ',
'page_first'       => 'аввал',
'histfirst'        => 'Аввалин',
'histlast'         => 'Охирин',

# Diffs
'difference'              => '(Фарқияти байни нусхаҳо)',
'lineno'                  => 'Сатри $1:',
'compareselectedversions' => 'Нусхаҳои интихобшударо муқоиса кунед',
'editundo'                => 'ботил',

# Search results
'searchresults' => 'Натиҷаҳои ҷустуҷӯ',
'noexactmatch'  => "'''Бо сарлавҳаи \"\$1\" мақола вуҷуд надорад.''' Шумо метавонед [[:\$1|ин саҳифаро бинависед]].",
'prevn'         => 'қаблӣ $1',
'nextn'         => 'баъдӣ $1',
'viewprevnext'  => 'Намоиш ($1) ($2) ($3)',
'powersearch'   => 'Ҷустуҷӯ',

# Preferences page
'preferences'       => 'тарҷиҳот',
'mypreferences'     => 'Тарҷиҳоти ман',
'prefs-edits'       => 'Шумораи вироишҳо:',
'changepassword'    => 'Иваз намудани калимаи убур',
'datetime'          => 'Сана ва вақт',
'prefs-rc'          => 'Тағйироти охирин',
'oldpassword'       => 'Калимаи кӯҳнаи убур:',
'newpassword'       => 'Калимаи нави убур:',
'retypenew'         => 'Калимаи нави убурро такроран нависед:',
'textboxsize'       => 'Дар ҳоли вироиш',
'searchresultshead' => 'Ҷустуҷӯ',
'allowemail'        => 'Иҷозат додани e-mail аз дигар корбарон',
'files'             => 'Файлҳо',

# User rights
'userrights-user-editname' => 'Номи корбарро дохил кунед:',
'editusergroup'            => 'Гуруҳҳои корбарро вироиш кунед',
'userrights-groupsmember'  => 'Аъзои:',

# Groups
'group-all' => '(ҳама)',

# Recent changes
'recentchanges'     => 'Таъғироти охирин',
'recentchangestext' => 'Назорати тағйиротҳои навтарин дар Википедиа дар ҳамин саҳифа аст.',
'rcnote'            => "Дар поён '''$1''' тағийротҳои охирин дар давоми '''$2''' рӯзҳои охир, сар карда аз $3.",
'rcnotefrom'        => 'Дар зер тағйиротҳои охирин аз <b>$2</b> (то <b>$1</b> нишон дода шудааст).',
'rclistfrom'        => 'Нишон додани тағйиротҳои нав сар карда аз $1',
'rcshowhideminor'   => '$1 вироишҳои хурд',
'rcshowhidebots'    => '$1 ботҳо',
'rcshowhideliu'     => '$1 корбарони вурудшуда',
'rcshowhideanons'   => '$1 корбарони вуруднашуда',
'rcshowhidemine'    => '$1 вироишҳои ман',
'rclinks'           => 'Нишон додани $1 тағйироти охирин дар $2 рӯзи охир<br />$3',
'diff'              => 'фарқият',
'hist'              => 'таърих',
'hide'              => 'Пинҳон кардани',
'show'              => 'Нишон додани',
'minoreditletter'   => 'х',
'newpageletter'     => 'Нав',
'boteditletter'     => 'б',
'newsectionsummary' => '/* $1 */ бахши ҷадид',

# Recent changes linked
'recentchangeslinked'          => 'Таъғироти монандӣ',
'recentchangeslinked-title'    => 'Тағйирҳои алоқаманд ба $1',
'recentchangeslinked-noresult' => 'Дар давоми замони додашуда тағйире дар саҳифаҳои пайваста рух надодааст.',
'recentchangeslinked-summary'  => "Ин саҳифаи вижа тағйироти охири саҳифаҳои пайвастаро дар бар мегирад. Саҳифаҳои дар рӯизати назароти шумо буда  '''пурранг''' ҳастанд.",

# Upload
'upload'            => 'Фиристодани файл',
'uploadbtn'         => 'Фиристодани файл',
'uploadnologin'     => 'Вуруд накарда',
'uploadnologintext' => 'Барои фиристодани файлҳо Шумо бояд [[Special:Userlogin|вуруд кунед]].',
'uploaderror'       => 'Иштибоҳи фиристодан',
'uploadlog'         => 'сабти фиристодан',
'uploadlogpage'     => 'Сабти фиристодан',
'fileuploadsummary' => 'Натиҷа:',
'uploadedfiles'     => 'Файлҳои фиристодашуда',
'successfulupload'  => 'Фиристодан бомуваффақият',
'uploadwarning'     => 'Огоҳии фиристодан',

# Image list
'imagelist'           => 'Рӯйхати файлҳо',
'ilsubmit'            => 'Ҷустуҷӯи',
'byname'              => 'аз рӯи ном',
'bydate'              => 'аз рӯи сана',
'bysize'              => 'аз рӯи ҳаҷм',
'filehist'            => 'Таърихи файл',
'filehist-help'       => 'Рӯи таърихҳо клик кунед то нусхаи марбути парвандаро бубинед.',
'filehist-current'    => 'нусхаи феълӣ',
'filehist-datetime'   => 'Таърих',
'filehist-user'       => 'Корбар',
'filehist-dimensions' => 'Андоза',
'filehist-filesize'   => 'Андозаи парванда',
'filehist-comment'    => 'Тавзеҳ',
'imagelinks'          => 'Пайвандҳо',
'linkstoimage'        => 'Саҳифаҳои зерин ба ин акс пайванданд:',
'sharedupload'        => 'Ин парванда бо таври умумӣ бор карда шудааст ва шояд аз тарафи дигар лоиҳаҳо мавриди истифода бошад.',
'imagelist_user'      => 'Корбар',

# MIME search
'mimesearch' => 'Ҷустуҷӯ бо стандарти MIME',

# List redirects
'listredirects' => 'Рӯйхати саҳифаҳои равонакунӣ',

# Unused templates
'unusedtemplates'    => 'Шаблонҳои истифоданашуда',
'unusedtemplateswlh' => 'дигар пайвандҳо',

# Random page
'randompage' => 'Саҳифаҳои тасодуфӣ',

# Statistics
'statistics'             => 'Омор\\Статистика',
'statistics-mostpopular' => 'Саҳифаҳои бисёр назаркардашуда',

'brokenredirects'        => 'Саҳифаҳои кандашудаи равонакунӣ',
'brokenredirects-edit'   => '(вироиш)',
'brokenredirects-delete' => '(ҳазв)',

'withoutinterwiki' => 'Саҳифаҳои бидуни пайвандҳои забонӣ',

'fewestrevisions' => 'Саҳифаҳое, ки шумораи ками нусхаҳо доранд',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт|байтҳо}}',
'nmembers'                => '$1 {{PLURAL:$1|узв}}',
'uncategorizedpages'      => 'Саҳифаҳое, ки ба ягон гурӯҳ дохил нестанд',
'uncategorizedcategories' => 'Гурӯҳҳое, ки ба ягон гурӯҳ дохил нестанд',
'uncategorizedimages'     => 'Аксҳое, ки ба ягон гурӯҳ дохил нестанд',
'uncategorizedtemplates'  => 'Шаблонҳое, ки ба ягон гурӯҳ дохил нестанд',
'unusedcategories'        => 'Гурӯҳҳои истифоданашуда',
'unusedimages'            => 'Файлҳои истифоданашуда',
'wantedcategories'        => 'Гурӯҳҳои дархостӣ',
'wantedpages'             => 'Саҳифаҳои дархостӣ',
'mostlinked'              => 'Саҳифаҳое, ки ба онҳо аз ҳама бештар пайвандҳо равона карда шудааст',
'mostlinkedcategories'    => 'Саҳифаҳое, ки дар бештари гурӯҳҳо дохил шудаанд',
'mostrevisions'           => 'Саҳифахое, ки аз ҳама бештар вироиш шудаанд',
'allpages'                => 'Ҳамаи саҳифаҳо',
'shortpages'              => 'Саҳифаҳои кӯтоҳ',
'longpages'               => 'Саҳифаҳои калон',
'deadendpages'            => 'Саҳифаҳои бемаъно',
'protectedpages'          => 'Саҳифаҳои ҳифзшуда',
'listusers'               => 'Рӯйхати корбарон',
'specialpages'            => 'Саҳифаҳои вижа',
'spheading'               => 'Саҳифаҳои вижа барои ҳама корбарон',
'newpages'                => 'Саҳифаҳои нав',
'newpages-username'       => 'Номи корбар:',
'ancientpages'            => 'Саҳифаҳои кӯҳнатарин',
'move'                    => 'Кӯчонидан',

# Book sources
'booksources'    => 'Манбаҳои китобҳо',
'booksources-go' => 'Бирав',

'categoriespagetext' => 'Гурӯҳҳои зерин дар вики вуҷуд доранд.',
'alphaindexline'     => '$1 то $2',
'version'            => 'Нусхаи Медиавики',

# Special:Log
'specialloguserlabel'  => 'Корбар:',
'speciallogtitlelabel' => 'Сарлавҳа:',
'log'                  => 'Гузоришҳо',
'all-logs-page'        => 'Ҳамаи сабтҳо',
'log-search-submit'    => 'Бирав',

# Special:Allpages
'allarticles'    => 'Ҳамаи мақолаҳо',
'allinnamespace' => 'Ҳамаи саҳифаҳо ($1 namespace)',
'allpagesprev'   => 'Пешина',
'allpagesnext'   => 'Баъдина',
'allpagessubmit' => 'Рав',

# Special:Listusers
'listusers-submit' => 'Нишон додани',

# E-mail user
'emailuser'       => 'Фиристодани email ба ин корбар',
'defemailsubject' => 'Википедиа e-mail',
'emailfrom'       => 'Аз',
'emailto'         => 'Ба',
'emailsubject'    => 'Мавзӯъ',
'emailmessage'    => 'Пайём',
'emailsend'       => 'Ирсол',
'emailccme'       => 'Нусхаи пайёми маро ба E-mail-и ман фирист.',

# Watchlist
'watchlist'      => 'Феҳристи назаротӣ ман',
'mywatchlist'    => 'Феҳристи назаротӣ ман',
'watchnologin'   => 'Вуруд нашуда',
'addedwatchtext' => "Ин саҳифа \"[[:\$1]]\" ва [[{{ns:special}}:Watchlist|феҳристи назаротӣ]] Шумо илова шуд.
Дигаргуниҳои ояндаи ин саҳифа ва саҳифи баҳси алоқаманд дар рӯихати онҷо хоҳад шуд,
ва саҳифа '''ғафс''' дар [[{{ns:special}}:Recentchanges|рӯихати тағйироти охирин]] барои бо осони дарёфт кардан хоҳад ба назар расид.

Агар шумо дертар аз феҳристи назаротатон ин саҳифаро ҳазв кардан хоҳед, дар меню \"Назар накардан\"-ро пахш кунед.",
'watch'          => 'Назар кардан',
'unwatch'        => 'Назар накардан',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Пайгири...',
'unwatching' => 'Тавқифи пайгири...',

'enotif_newpagetext' => 'Ин саҳифаи нав аст',

# Delete/protect/revert
'confirm'             => 'Тасдиқ',
'confirmdelete'       => 'Тасдиқи ҳазф',
'actioncomplete'      => 'Амал иҷро шуд',
'deletedarticle'      => 'ҳазфшуда "[[$1]]"',
'dellogpage'          => 'Гузоришҳои ҳазф',
'reverted'            => 'Ба нусхаи пештара вогардонида шуд',
'deletecomment'       => 'Сабаби ҳазфкунӣ',
'rollbacklink'        => 'вогардони',
'protect-level-sysop' => 'Танҳо барои мудирон',
'protect-cascade'     => 'Муҳофизати обшорӣ - Аз ҳама саҳифаҳое, ки дар ин саҳифа омадаанд муҳофизат мешаванд',
'protect-cantedit'    => 'Шумо вазъияти ҳифзи ин саҳифаро тағйир дода наметавонед, чун иҷозати вироиши онро надоред.',

# Restrictions (nouns)
'restriction-edit' => 'Вироиш',

# Restriction levels
'restriction-level-sysop' => 'пурра ҳифзшуда',

# Undelete
'undeletehistory'        => 'Агар ин саҳифаро эҳё кунед, ҳамаи нусхаҳои он дар таърихча эҳё хоҳанд шуд. Агар саҳифаи ҷадиде бо номи яксон аз замони ҳазф эҷод шуда бошад, нусхаҳои эҳёшуда дар таърихчаи қабули хоҳанд омад. Ва нусхаи фаъоли саҳифаи зинда ба таври худкорӣ ҷойгузин нахоҳад шуд.',
'undeletehistorynoadmin' => 'Ин мақола ҳазв карда шудааст. Сабаби ҳазв дар эзоҳ дар зер бо дигар маълумотҳои корбар ки ин саҳифаро ҳазф кард оварда шудааст. Матни аслии ин вироишоти ҳазфшуда фақат ба мудирон-администраторон дастрас аст.',
'undeletebtn'            => 'Барқарор кардан',
'undelete-search-submit' => 'Ҷустуҷӯ',

# Namespace form on various pages
'namespace'      => 'Фазоином:',
'invert'         => 'Пинҳон кардани интихобкардашуда',
'blanknamespace' => '(Аслӣ)',

# Contributions
'contributions' => 'Ҳиссагузории корбар',
'mycontris'     => 'Хиссагузории ман',

'sp-contributions-newbies'  => 'Фақат ҳиссагузориҳои ҳисобҳои ҷадидро нишон деҳ',
'sp-contributions-search'   => 'Ҷустуҷӯи ҳиссагузориҳо',
'sp-contributions-username' => 'IP нишона ё номи корбар:',
'sp-contributions-submit'   => 'Ҷустуҷӯ',

# What links here
'whatlinkshere'       => 'Пайвандҳои дар ин сахифа',
'whatlinkshere-title' => 'Саҳифаҳое ки ба $1 пайванд доранд',
'linklistsub'         => '(Феҳристи пайвандҳо)',
'linkshere'           => "Саҳифаҳои зерин ба '''[[:$1]]''' пайванданд:",
'nolinkshere'         => "Ягон саҳифа ба '''[[:$1]]''' пайванд нест.",
'isredirect'          => 'саҳифаи тағйири масир',
'istemplate'          => 'истифодашуда дар саҳифа',
'whatlinkshere-prev'  => '{{PLURAL:$1|қаблӣ|қаблӣ $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|баъдӣ|баъдӣ $1}}',
'whatlinkshere-links' => '← пайвандҳо',

# Block/unblock
'blockip'            => 'Бастани корбар',
'ipadressorusername' => 'IP нишона ё номи корбар:',
'ipbreason'          => 'Сабаб',
'blockipsuccesssub'  => 'Бастан муваффақ щуд',
'ipblocklist'        => 'Рӯйхати IP нишонаҳо ва корбарҳои баста шуда',
'blocklink'          => 'бастан',
'contribslink'       => 'ҳиссагузорӣ',
'blocklogpage'       => 'Сабти басташавӣ',

# Move page
'movepage'        => 'Кӯчонидани саҳифа',
'movearticle'     => 'Кӯчонидани саҳифа:',
'movenologin'     => 'Вуруд нашудаед',
'movenologintext' => 'Барои кӯчонидани саҳифа шумо бояд корбари сабтшуда ва [[Special:Userlogin|ба систем вурудшуда]] бошед.',
'movenotallowed'  => 'Шумо иҷозати кӯчонидани саҳифаҳоро дар Википедиа надоред.',
'newtitle'        => 'Ба унвони ҷадид:',
'movepagebtn'     => 'Кӯчонидани саҳифа',
'movedto'         => 'кӯчонидашуда ба',
'1movedto2'       => '[[$1]] ба [[$2]] кӯчонида шудааст',
'movereason'      => 'Сабаби кӯчонидан:',
'revertmove'      => 'вогардонӣ',

# Export
'export' => 'Судури саҳифаҳо',

# Namespace 8 related
'allmessages'         => 'Пайёмҳои системавӣ',
'allmessagesname'     => 'Ном',
'allmessagesdefault'  => 'Матни қарордодӣ',
'allmessagescurrent'  => 'Матни кунунӣ',
'allmessagestext'     => 'Ин рӯйхати паёмҳои системавӣ мебошад, ки дар фазои номҳои MediaWiki дастрас карда шудаанд.',
'allmessagesfilter'   => 'Филтри номи пайём:',
'allmessagesmodified' => 'Фақат тағйирдодаро нишон деҳ',

# Thumbnails
'thumbnail-more'  => 'Бузург шавад',
'thumbnail_error' => 'Хато дар эҷоди ангуштдона: $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Саҳифаи корбарии ман',
'tooltip-pt-mytalk'               => 'Саҳифаи баҳси ман',
'tooltip-pt-preferences'          => 'Тарҷиҳоти ман',
'tooltip-pt-watchlist'            => 'Рӯйхати саҳифаҳое, ки тағйиротҳояшонро Шумо назорат мекунед',
'tooltip-pt-mycontris'            => 'Рӯихати ҳиссагузориҳои ман',
'tooltip-pt-login'                => 'Тавсия мешавад ки ба систем ворид шавад, лекин иҷборӣ нест.',
'tooltip-pt-logout'               => 'Хуруҷ аз систем',
'tooltip-ca-talk'                 => 'Баҳси матни таркибии ин саҳифа',
'tooltip-ca-edit'                 => 'Шумо ин саҳифаро вироиш карда метавонед. Пеш аз захира кардани саҳифа пешнамоишро истифода баред.',
'tooltip-ca-addsection'           => 'Илова кардани эзоҳот ба ин баҳс',
'tooltip-ca-viewsource'           => 'Ин саҳифа ҳифз карда шудааст. Шумо танҳо таркиби онро дида метавонед.',
'tooltip-ca-history'              => 'Нусхаи охирини ин саҳифа.',
'tooltip-ca-protect'              => 'Ҳифз намудани ин саҳифа',
'tooltip-ca-delete'               => 'Ин саҳифаро ҳазф кунед',
'tooltip-ca-move'                 => 'Кӯчонидани ин саҳифа',
'tooltip-ca-watch'                => 'Ин саҳифаро метавонед ба феҳристи назароти худ дохил кунед',
'tooltip-ca-unwatch'              => 'Гирифта партофтани ин саҳифа аз феҳристи назароти Шумо',
'tooltip-search'                  => 'Ҷустуҷӯи {{SITENAME}}',
'tooltip-search-go'               => 'Гузаштан ба саҳифае, ки айнан чунин ном дорад, агар вуҷуд дошта бошад',
'tooltip-search-fulltext'         => 'Ҷустуҷӯи саҳифаҳое, ки чунин матн доранд',
'tooltip-p-logo'                  => 'Саҳифаи Аслӣ',
'tooltip-n-mainpage'              => 'Гузаштан ба Саҳифаи Аслӣ',
'tooltip-n-portal'                => 'Дар бораи лоиҳа ва чи корҳоро метавонед кард',
'tooltip-n-currentevents'         => 'Ёфтани иттилооти пешзамина перомуни воқеаҳои кунунӣ',
'tooltip-n-recentchanges'         => 'Рӯйхати тағйиротҳо дар Википедиа',
'tooltip-n-randompage'            => 'Овардани як саҳифаи тасодуфӣ',
'tooltip-n-help'                  => 'Гузаштан ба Роҳнамо.',
'tooltip-n-sitesupport'           => 'Моро дастгирӣ намоед',
'tooltip-t-whatlinkshere'         => 'Рӯйхати ҳамаи саҳифаҳое, ки ба ин саҳифа пайванд доранд',
'tooltip-t-upload'                => 'Фиристодани аксҳо ё медиа-файлҳо',
'tooltip-t-specialpages'          => 'Рӯихати ҳамаи саҳифаҳои вижа',
'tooltip-t-print'                 => 'Нусхаи чопии ин саҳифа',
'tooltip-ca-nstab-user'           => 'Намоиши саҳифаи корбар',
'tooltip-ca-nstab-special'        => 'Ин саҳифаи махсус мебошад, Шумо онро вироиш карда наметавонед',
'tooltip-ca-nstab-project'        => 'Намоиши саҳифаи лоиҳа',
'tooltip-ca-nstab-image'          => 'Намоиши саҳифаи акс',
'tooltip-ca-nstab-template'       => 'Намоиши шаблон',
'tooltip-ca-nstab-help'           => 'Намоиши саҳифаи роҳнамо',
'tooltip-ca-nstab-category'       => 'Намоиши саҳифаи гурӯҳ',
'tooltip-minoredit'               => 'Инро ҳамчун вироиши хурд қайд намудан',
'tooltip-save'                    => 'Захира намудани тағйиротҳои худ',
'tooltip-preview'                 => 'Тағйироти худро пешнамоиш кунед, лутфан ин амалро пеш аз захира кардан истифода кунед!',
'tooltip-diff'                    => 'Намоиши тағйиротҳое, ки Шумо бо матн кардаед',
'tooltip-compareselectedversions' => 'Намоиши фарқияти байни ду нусхаи интихобкардашудаи ин саҳифа.',
'tooltip-watch'                   => 'Ин саҳифаро ба рӯихатӣ назароти худ илова кунед',

# Attribution
'siteuser'  => 'Википедиа user $1',
'and'       => 'ва',
'others'    => 'дигарон',
'siteusers' => 'Википедиа user(s) $1',

# Spam protection
'spamprotectiontitle'    => 'Филтри муҳофизат аз спам',
'categoryarticlecount'   => '{{PLURAL:$1|мақола|$1 мақолаҳо}} дар ин гурӯҳ вуҷуд дорад.',
'listingcontinuesabbrev' => 'идома',

# Browsing diffs
'previousdiff' => '← Фарқияти аввалина',

# Media information
'file-info-size'       => '($1 × $2 пиксел, ҳаҷми парванда: $3, навъи MIME: $4)',
'file-nohires'         => '<small>Нусхаи ҳаҷман ва сифатан баландтар дастрас нест.</small>',
'show-big-image'       => 'Акси пурра',
'show-big-image-thumb' => '<small>Андозаи ин пешнамоиш: $1 × $2 пиксел</small>',

# Special:Newimages
'showhidebots' => '($1 ботҳо)',

# Bad image list
'bad_image_list' => 'Иттилоотро бояд бо ин шакл ворид кунед:

Фақат сатрҳое, ки бо * шурӯъ шаванд ба назар гирифта мешаванд. Аввалин пайванд дар ҳар сатр, бояд пайванде ба як тасвир ва ё акси бад бошад. Пайвандҳои баъдӣ дар ҳамон сатр, ба унвони мавриди истисно ба назар гирифта мешавад.',

# Metadata
'metadata'          => 'Метадода',
'metadata-help'     => 'Ин парванда иттилооти иловагиро дар бар мегирад, эҳтимол аз аксбардораки рақамӣ ё сканер дар вақти сохтан ва рақамӣ кардан, илова шуда. Агар парванда аз вазъияти ибтидоиаш тағйир дода бошад, мумкин аст, шарҳу тафсилоти мавҷуди иттилооти аксро тамоман бозтоб надиҳад.',
'metadata-expand'   => 'Намоиши ҷузъиёти тафсилӣ',
'metadata-collapse' => 'Пинҳон кардани ҷузъиёти тафсилӣ',
'metadata-fields'   => 'EXIF фосилаҳои додаҳо, ки дар ин паём оварда шудаанд дар ҷадвали акс ҷамъ шуда бошанд ҳам, намоиш дода хоҳанд шуд. Бақия онҳо танҳо дар вақти боз кардани ҷадвал нишон дода хоҳанд шуд.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-artist' => 'Муаллиф',

# External editor support
'edit-externally'      => 'Ин файлро бо барномаи беруна таҳрир кунед',
'edit-externally-help' => 'Барои иттилооти бештар [http://meta.wikimedia.org/wiki/Help:External_editors роҳнамои танзимотро оиди вироишгарони беруна] нигаред.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ҳама',
'imagelistall'     => 'ҳама',
'namespacesall'    => 'ҳама',

# Delete conflict
'deletedwhileediting' => 'Огоҳӣ: Ин саҳифа баъди ба вироиш шурӯъ кардани шумо ҳазф шуда буд!',
'recreate'            => 'Аз нав созед',

# Multipage image navigation
'imgmultigo' => 'Бирав!',

# Table pager
'table_pager_next'  => 'Саҳифаи навбатӣ',
'table_pager_prev'  => 'Саҳифаи гузашта',
'table_pager_first' => 'Саҳифаи аввал',
'table_pager_last'  => 'Саҳифаи охир',

# Auto-summaries
'autosumm-replace' => "Ивазкунии саҳифа бо '$1'",
'autosumm-new'     => 'Саҳифаи нав: $1',

# Iranian month names
'iranian-calendar-m1'  => 'Ҳамал',
'iranian-calendar-m2'  => 'Савр',
'iranian-calendar-m3'  => 'Ҷавзо',
'iranian-calendar-m4'  => 'Саратон',
'iranian-calendar-m5'  => 'Асад',
'iranian-calendar-m6'  => 'Сунбула',
'iranian-calendar-m7'  => 'Мизон',
'iranian-calendar-m8'  => 'Ақраб',
'iranian-calendar-m9'  => 'Қавс',
'iranian-calendar-m10' => 'Ҷадӣ',
'iranian-calendar-m11' => 'Далв',
'iranian-calendar-m12' => 'Ҳут',

);
