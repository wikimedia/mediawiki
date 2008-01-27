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
'categories'            => 'Гурӯҳҳо',
'category_header'       => 'Мақолаҳо дар гурӯҳи "$1"',
'subcategories'         => 'Зергурӯҳҳо',
'category-media-header' => 'Парвандаҳои гурӯҳ "$1"',
'category-empty'        => "''Дар ҳоли ҳозир ин гурӯҳ дорои мақола ё парвандаҳо нест.''",

'about'         => 'Дар бораи',
'newwindow'     => '(дар равзанаи ҷадид боз мешавад)',
'cancel'        => 'Лағв',
'qbfind'        => 'Ёфтан',
'qbedit'        => 'Вироиш',
'qbmyoptions'   => 'Саҳифаҳои ман',
'moredotdotdot' => 'Бештар...',
'mypage'        => 'Саҳифаи ман',
'mytalk'        => 'Гуфтугӯи ман',
'anontalk'      => 'Баҳс бо ин IP',
'navigation'    => 'Гаштан',

'errorpagetitle'   => 'Хато',
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
'redirectpagesub'  => 'Саҳифаи равонакунӣ',
'lastmodifiedat'   => 'Ин саҳифа бори охир $2, $1 дигаргун карда шудааст.', # $1 date, $2 time
'jumpto'           => 'Ҷаҳиш ба:',
'jumptonavigation' => 'новбари',
'jumptosearch'     => 'Ҷустуҷӯи',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Дар бораи {{SITENAME}}',
'aboutpage'         => 'Project:Дар бораи',
'bugreports'        => 'Гузориши ишколот',
'bugreportspage'    => 'Лоиҳа:Гузоришҳои ишколот',
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
'newmessageslink'         => 'пайёмҳои нав',
'newmessagesdifflink'     => 'тағйироти охирин',
'youhavenewmessagesmulti' => 'Шумо номаҳои нав дар $1 доред.',
'editsection'             => 'вироиш',
'editold'                 => 'вироиш',
'editsectionhint'         => 'Вироиши қисмат: $1',
'toc'                     => 'Мундариҷа',
'showtoc'                 => 'Намоиш дода шавад',
'hidetoc'                 => 'Пинҳон кардани',
'site-rss-feed'           => 'Барои $1 RSS Хабархон',
'site-atom-feed'          => 'Барои $1 Atom Хабархон',
'page-rss-feed'           => 'Барои "$1" RSS Хабархон',

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
'badtitle'         => 'Унвони бад',
'badtitletext'     => 'Унвони саҳифаи дархостшуда номӯътабар, холӣ, ё пайванди байнизабони ё байнивикии нодуруст буд. Он метавонад як ё якчанд аломатҳоеро дар бар гирад, ки дар унвонҳо истифода шуда наметавонанд.',
'viewsource'       => 'Намоиши матни вики',
'viewsourcefor'    => 'барои $1',
'viewsourcetext'   => 'Шумо метавонед матни викии ин саҳифаро назар кунед ё нусха бардоред:',
'editinginterface' => "'''Огоҳи:''' Шумо саҳифаеро вироиш карда истодаед, ки матни интерфейси барнома мебошад. Тағйироти ин саҳифа барои намуди интерфейси дигар корбарон таъсир хоҳад расонид.",

# Login and logout pages
'loginpagetitle'             => 'Вуруди корбар',
'yourname'                   => 'Номи корбар',
'yourpassword'               => 'Калимаи убур\\пароль',
'yourpasswordagain'          => 'Калимаи убурро боз нависед',
'remembermypassword'         => 'Манро дар хотир нигоҳ дор',
'yourdomainname'             => 'Домейни Шумо',
'login'                      => 'Вуруд',
'loginprompt'                => 'Барои вуруд ба {{SITENAME}} бояд кукиҳоро фаъол кунед.',
'userlogin'                  => 'Вуруд / Сохтани ҳисоби ҷадид',
'logout'                     => 'Хуруҷ аз систем',
'userlogout'                 => 'Хуруҷ аз систем',
'nologin'                    => 'Номи корбар надоред? $1.',
'nologinlink'                => 'Ҳисоберо созед',
'createaccount'              => 'Ҳисоби ҷадидеро созед',
'gotaccount'                 => 'Ҳисоби корбарӣ доред? $1.',
'gotaccountlink'             => 'Вуруд шавед',
'userexists'                 => 'Номи корбарӣ дохил кардашуда мавриди истифода аст. Номи дигарероро интихоб кунед.',
'username'                   => 'Номи корбар:',
'uid'                        => 'ID-и корбар:',
'yourrealname'               => 'Номи аслӣ *',
'yourlanguage'               => 'Забон:',
'yourvariant'                => 'Вариант',
'yournick'                   => 'Ники шумо:',
'prefs-help-realname'        => 'Номи ҳақиқӣ ихтиёрӣ ва агар шумо онро пешниҳод кунед онро ҳамчун муаллифи эҷодиётатон ёдоварӣ карда хоҳад шуд.',
'loginerror'                 => 'Иштибоҳ дар вуруд',
'noname'                     => 'Номи корбари дурустеро шумо пешниҳод накардед.',
'loginsuccesstitle'          => 'Вуруд бо муваффақият',
'loginsuccess'               => "'''Шумо акнун ба Википедиа ҳамчун \"\$1\". вуруд кардед'''",
'nosuchuser'                 => 'Корбаре бо номи "$1" вуҷуд надорад. Амали номро барраси кунед, ё ҳисоби ҷадидеро эҷод кунед.',
'nosuchusershort'            => 'Ягон корбаре бо номи "$1" вуҷуд надорад. Тарзи навишти номро санҷед.',
'nouserspecified'            => 'Шумо бояд як номи корбарӣ мушаххас кунед.',
'wrongpassword'              => 'Калимаи убури нодуруст дохил карда шуд. Бори дигар санҷед.',
'wrongpasswordempty'         => 'Калимаи убури дохил шуда холӣ аст. Бори дигар санҷед.',
'passwordtooshort'           => 'Калимаи убур хеле кӯтоҳ аст. Вай бояд ҳадиақал $1 аломатҳо дошта бошад.',
'mailmypassword'             => 'Калимаи убурро ба E-mail фиристед',
'passwordremindertitle'      => 'Муваққатан калимаи убурӣ ҷадид барои {{SITENAME}}',
'passwordremindertext'       => 'Someone (probably you, from IP address $1)
requested that we send you a new password for {{SITENAME}} ($4).
The password for user "$2" is now "$3".
You should log in and change your password now.

If someone else made this request or if you have remembered your password and
you no longer wish to change it, you may ignore this message and continue using
your old password.

Як нафар (эҳтимол Шумо, аз нишонаи IP $1 дархост кардааст, ки калимаи убури ҷадиде барои {{SITENAME}} ($4) барои шумо бифиристем.
Калимаи убури корбар "$2" ҳамакнун "$3" аст.
Ҳоло бояд вориди систем шавед ва калимаи убури худро тағйир диҳед.

Агар касе дигаре инро дахост кардааст ё инки шумо калимаи убури пешинаи худро ба ёд овардаед ва дигар хоҳиши тағйир додани онро надоред, ба ин паём аҳмият надиҳед ва калимаи убури пешинаи худро истифода баред.',
'noemail'                    => 'Ҳеҷ нишонаи почтаи электронӣ барои корбар "$1" сабт нашудааст.',
'passwordsent'               => 'Калимаи убури нав ба адреси e-mail, ки барои "$1" номнавис шудааст фиристода шуд.
Баъд аз дастрас кардани он, марҳамат карда вуруд кунед.',
'eauthentsent'               => 'Номаи барои тасдиқ ба нишонаи почта электронӣ фиристода шуд. Пеш аз фиристодани нома ба ин ҳисоб, шумо бояд дастуроте ки ба он нишонаи почтаи электронӣ омадаст, иҷро карда, дар ҳақиқат ҳисоби худ буданашро бояд тасдиқ кунед.',
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
'summary'                => 'Хулоса',
'subject'                => 'Мавзӯъ/сарлавҳа',
'minoredit'              => 'Ин вироиши хурд аст',
'watchthis'              => 'Назар кардани ин саҳифа',
'savearticle'            => 'Саҳифа захира шавад',
'preview'                => 'Пешнамоиш',
'showpreview'            => 'Пеш намоиш',
'showdiff'               => 'Намоиши тағйирот',
'anoneditwarning'        => "'''Огоҳӣ:''' Шумо вуруд накардаед. Суроғаи IP Шумо дар вироишоти ин саҳифа сабт хоҳад шуд.",
'summary-preview'        => 'Пешнамоиши хулоса',
'blockedtitle'           => 'Корбар баста шудааст',
'blockedtext'            => "<big>'''Номи корбарии Шумо ё нишонаи IP баста шудааст.'''</big>

Бастан аз тарафи $1 иҷро шуд. Сабаби он ''$2'' аст.

* Замони қатъ кардан: $8
* Замони саромадани қатъи дастрасӣ: $6
* Қатъкунанда: $7

Шумо метавонед бо $1 ё яке дигаре аз [[{{MediaWiki:Grouppage-sysop}}|мудирон]] барои гуфтугӯ роҷеъ ба қатъи дастрасӣ тамос гиред.
Таваҷҷӯҳ кунед, ки аз қобилияти 'фиристодаи почтаи электронӣ ба ин корбар' наметавоне истифода кард, магар ин ки як нишони мӯътабари почтаи электронӣ дар [[Special:Preferences|тарҷиҳоти корбарии]] худ сабт карда бошед ва аз корбурди он манъ нашуда бошед.
Нишонаи кунунии IP Шумо $3 аст, ва шиносаи қатъи дастрасии Шумо #$5 аст. Лутфан ин ё онро ва ё ҳардуи онро дар дархостҳои худ зикр кунед.",
'whitelistedittitle'     => 'Барои вироиш вуруд бояд кард',
'whitelistreadtitle'     => 'Барои хондан бояд вуруд кард',
'whitelistacctitle'      => 'Ба Шумо барои сохтани номи корбар иҷозат нест.',
'nosuchsectiontext'      => 'Шумо хостед, ки қисмеро вироиш кунед, ки он вуҷуд надорад.  То ҳоле, ки қисми $1 вуҷуд надорад, барои вироиши шумо ҷой нест.',
'accmailtitle'           => 'Калимаи убур фиристода шуд.',
'accmailtext'            => 'Калимаи убур барои "$1" ба $2 фиристода шуд.',
'newarticle'             => '(Нав)',
'newarticletext'         => "Шумо пайвандеро интихоб кардед, ки саҳифа дар он арзи вуҷуд надорад. Барои 
сохтани саҳифа, ба қуттии зерин нависед ([[{{MediaWiki:Helppage}}|саҳифаи роҳнаморо]] 
барои маълумоти бештар нигаред). Агар аз сабаби хатогӣ ва ё иштибоҳ омадед, 
тугмаи '''Ба оқиб'''-ро дар браузери худ пахш кунед.",
'noarticletext'          => 'Дар ин саҳифа то кунун, матн вуҷуд надорад, шумо метавонед дар дигар саҳифаҳо [[Special:Search/{{PAGENAME}}|унвони ин саҳифаро ҷустуҷӯ кунед]] ё [{{fullurl:{{FULLPAGENAME}}|action=edit}} ин саҳифаро вироиш кунед].',
'previewnote'            => '<strong>Ин фақат пешнамоиш аст; дигаргуниҳо ҳоло захира нашудаанд!</strong>',
'editing'                => 'Дар ҳоли вироиш $1',
'editingsection'         => 'Дар ҳоли вироиши $1 (қисмат)',
'editingcomment'         => 'Дар ҳоли вироиш $1 (comment)',
'yourtext'               => 'Матни Шумо',
'yourdiff'               => 'Фарқиятҳо',
'copyrightwarning'       => 'Ҳамаи ҳиссагузорӣ ба {{SITENAME}} аз рӯи қонунҳои зерин $2 (нигаред $1 барои маълумоти бештар) ҳиссагузорӣ мешаванд. Агар Шумо намехоҳед, ки навиштаҷоти Шумо вироиш ва паҳн нашаванд, Шумо метавонед ин мақоларо нафиристед.<br /> Шумо ваъда медиҳед, ки худатон ин мақоларо навиштед ё ки аз сарчашмаҳои кушод нусхабардорӣ кардаед. <strong>АСАРҲОИ ҚОБИЛИ ҲУҚУҚИ МУАЛЛИФРО БЕ ИҶОЗАТ НАФИРИСТЕД!</strong>',
'longpagewarning'        => '<strong>Ҳушдор: Ин саҳифа $1 килобайт дароз аст; баъзе мурургарҳо мумкин ба вироиши саҳифаҳои наздик ба 32kb ё дарозтар аз он мушкили дошта бошанд.
Лутфан дар барои ба қисматҳои хурд ҷудо кардани ин саҳифа фикр кунед.</strong>',
'templatesused'          => 'Шаблонҳои дар ин саҳифа истифодашуда:',
'templatesusedpreview'   => 'Шаблонҳои истифодашуда дар ин пешнамоиш:',
'template-protected'     => '(ҳифзшуда)',
'template-semiprotected' => '(нима-муҳофизатшуда)',
'nocreatetext'           => '{{SITENAME}} қобилияти эҷоди саҳифаҳои ҷадидро маҳдуд карда аст.
Шумо метавонед бозгашта саҳифаи мавҷудбударо вироиш кунед, ё [[Special:Userlogin|ба систем вуруд кунед ё ҳисоби корбарӣ эҷод кунед]].',
'recreate-deleted-warn'  => "'''Диққат: Шумо саҳифаеро барқарор карда истодаед, ки пештар ҳазф шудааст.''' 

Шумо зарурияти вироиши ин саҳифаро дида баромаданатон лозим. Сабти ҳазфшавии 
ин саҳифа барои фароҳам овардани имкониятҳои қулай оварда шудааст:",

# Account creation failure
'cantcreateaccounttitle' => 'Ҳисобе сохта наметавонам',

# History pages
'viewpagelogs'        => 'Намоиши гузоришҳои марбута ба ин саҳифа',
'nohistory'           => 'Таърихи вироиш барои ин саҳифа вуҷуд надорад.',
'currentrev'          => 'Вироишоти кунунӣ',
'revisionasof'        => 'Нусха $1',
'revision-info'       => 'Нусхаи вироиш $2 дар таърихи $1',
'previousrevision'    => '←Нусхаи кӯҳнатар',
'nextrevision'        => 'Нусхаи навтарин→',
'currentrevisionlink' => 'Намоиши нусхаи феълӣ',
'cur'                 => 'феълӣ',
'last'                => 'қаблӣ',
'page_first'          => 'аввал',
'page_last'           => 'охирин',
'histlegend'          => 'Интихоби тафовут:қуттии нусхаҳоро барои тафовут қайд кунед ва тугмаи дохил кардан ё тугмаи зерро пахш кунед.<br />
Шарҳ: (феълӣ) тафовут бо нусхаи феълӣ
(қаблӣ) = тафовут бо нусхаи феълӣ, ҷузъ = вироиши ҷузъӣ',
'histfirst'           => 'Аввалин',
'histlast'            => 'Охирин',

# Revision feed
'history-feed-item-nocomment' => '$1 дар $2', # user at time

# Diffs
'history-title'           => 'Таърихчаи вироишҳои "$1"',
'difference'              => '(Фарқияти байни нусхаҳо)',
'lineno'                  => 'Сатри $1:',
'compareselectedversions' => 'Нусхаҳои интихобшударо муқоиса кунед',
'editundo'                => 'ботил',
'diff-multi'              => '({{PLURAL:$1|вироиши миёнӣ|$1 вироишоти миёнӣ}} нишон дода нашудааст.)',

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

'grouppage-sysop' => '{{ns:project}}:Мудирон',

# User rights log
'rightslog' => 'Гузориши ихтиёроти корбар',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|тағйир|тағйирот}}',
'recentchanges'                  => 'Таъғироти охирин',
'recentchangestext'              => 'Назорати тағйиротҳои навтарин дар Википедиа дар ҳамин саҳифа аст.',
'recentchanges-feed-description' => 'Радёбии охирин тағйироти ин вики дар ин хурд.',
'rcnote'                         => "Дар поён '''$1''' тағийротҳои охирин дар давоми '''$2''' рӯзҳои охир, сар карда аз $3.",
'rcnotefrom'                     => 'Дар зер тағйиротҳои охирин аз <b>$2</b> (то <b>$1</b> нишон дода шудааст).',
'rclistfrom'                     => 'Нишон додани тағйиротҳои нав сар карда аз $1',
'rcshowhideminor'                => '$1 вироишҳои хурд',
'rcshowhidebots'                 => '$1 ботҳо',
'rcshowhideliu'                  => '$1 корбарони вурудшуда',
'rcshowhideanons'                => '$1 корбарони вуруднашуда',
'rcshowhidepatr'                 => '$1 вироишҳои гаштӣ',
'rcshowhidemine'                 => '$1 вироишҳои ман',
'rclinks'                        => 'Нишон додани $1 тағйироти охирин дар $2 рӯзи охир<br />$3',
'diff'                           => 'фарқият',
'hist'                           => 'таърих',
'hide'                           => 'Пинҳон кардани',
'show'                           => 'Нишон додани',
'minoreditletter'                => 'х',
'newpageletter'                  => 'Нав',
'boteditletter'                  => 'б',
'newsectionsummary'              => '/* $1 */ бахши ҷадид',

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
'uploadedimage'     => 'боршуда "[[$1]]"',

# Image list
'imagelist'                 => 'Рӯйхати файлҳо',
'ilsubmit'                  => 'Ҷустуҷӯи',
'byname'                    => 'аз рӯи ном',
'bydate'                    => 'аз рӯи сана',
'bysize'                    => 'аз рӯи ҳаҷм',
'filehist'                  => 'Таърихи файл',
'filehist-help'             => 'Рӯи таърихҳо клик кунед то нусхаи марбути парвандаро бубинед.',
'filehist-current'          => 'нусхаи феълӣ',
'filehist-datetime'         => 'Таърих',
'filehist-user'             => 'Корбар',
'filehist-dimensions'       => 'Андоза',
'filehist-filesize'         => 'Андозаи парванда',
'filehist-comment'          => 'Тавзеҳ',
'imagelinks'                => 'Пайвандҳо',
'linkstoimage'              => 'Саҳифаҳои зерин ба ин акс пайванданд:',
'nolinkstoimage'            => 'Ҳеҷ саҳифае ба ин акс пайванд надорад.',
'sharedupload'              => 'Ин парванда бо таври умумӣ бор карда шудааст ва шояд аз тарафи дигар лоиҳаҳо мавриди истифода бошад.',
'noimage'                   => 'Ҳеҷ парвандае бо ин ном мавҷуд нест, шумо метавонед $1.',
'noimage-linktext'          => 'онро бор кунед',
'uploadnewversion-linktext' => 'Бор кардани нусхаи ҷадидӣ ин парванда',
'imagelist_user'            => 'Корбар',

# MIME search
'mimesearch' => 'Ҷустуҷӯ бо стандарти MIME',

# List redirects
'listredirects' => 'Рӯйхати саҳифаҳои равонакунӣ',

# Unused templates
'unusedtemplates'    => 'Шаблонҳои истифоданашуда',
'unusedtemplateswlh' => 'дигар пайвандҳо',

# Random page
'randompage' => 'Саҳифаҳои тасодуфӣ',

# Random redirect
'randomredirect' => 'Масири тасодуфӣ',

# Statistics
'statistics'             => 'Омор\\Статистика',
'statistics-mostpopular' => 'Саҳифаҳои бисёр назаркардашуда',

'disambiguations' => 'Саҳифаҳои ибҳомзудоӣ',

'doubleredirects' => 'Тағйири масирҳои дутоӣ',

'brokenredirects'        => 'Саҳифаҳои кандашудаи равонакунӣ',
'brokenredirects-edit'   => '(вироиш)',
'brokenredirects-delete' => '(ҳазв)',

'withoutinterwiki' => 'Саҳифаҳои бидуни пайвандҳои забонӣ',

'fewestrevisions' => 'Саҳифаҳое, ки шумораи ками нусхаҳо доранд',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт|байтҳо}}',
'nlinks'                  => '$1 {{PLURAL:$1|пайванд|пайвандҳо}}',
'nmembers'                => '$1 {{PLURAL:$1|узв}}',
'lonelypages'             => 'Саҳифаҳои ятим',
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
'mostlinkedtemplates'     => 'Шаблонҳое ки бештар аз ҳама бо онҳо пайванд шудааст',
'mostcategories'          => 'Мақолаҳое ки бештарин теъдоди гурӯҳҳоро доранд',
'mostimages'              => 'Аксҳое ки бештар аз ҳама бо онҳо пайванд шудааст',
'mostrevisions'           => 'Саҳифахое, ки аз ҳама бештар вироиш шудаанд',
'allpages'                => 'Ҳамаи саҳифаҳо',
'prefixindex'             => 'Намоиши пешвандӣ',
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
'movethispage'            => 'Кӯчонидани ин саҳифа',

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
'nextpage'       => 'Саҳифаи баъдина ($1)',
'prevpage'       => 'Саҳифаи пешина ($1)',
'allpagesfrom'   => 'Намоиши саҳифаҳо бо шурӯъ аз:',
'allarticles'    => 'Ҳамаи мақолаҳо',
'allinnamespace' => 'Ҳамаи саҳифаҳо ($1 namespace)',
'allpagesprev'   => 'Пешина',
'allpagesnext'   => 'Баъдина',
'allpagessubmit' => 'Рав',
'allpagesprefix' => 'Намоиши саҳифаҳои дорои пешванд:',

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
'watchlist'            => 'Феҳристи назаротӣ ман',
'mywatchlist'          => 'Феҳристи назаротӣ ман',
'watchlistfor'         => "(барои '''$1''')",
'watchnologin'         => 'Вуруд нашуда',
'addedwatch'           => 'Ба феҳристи пайгириҳо илова карда шуд',
'addedwatchtext'       => "Ин саҳифа \"[[:\$1]]\" ва [[{{ns:special}}:Watchlist|феҳристи назаротӣ]] Шумо илова шуд.
Дигаргуниҳои ояндаи ин саҳифа ва саҳифи баҳси алоқаманд дар рӯихати онҷо хоҳад шуд,
ва саҳифа '''ғафс''' дар [[{{ns:special}}:Recentchanges|рӯихати тағйироти охирин]] барои бо осони дарёфт кардан хоҳад ба назар расид.

Агар шумо дертар аз феҳристи назаротатон ин саҳифаро ҳазв кардан хоҳед, дар меню \"Назар накардан\"-ро пахш кунед.",
'removedwatch'         => 'Аз феҳристи пайгириҳо бардошта шуд',
'removedwatchtext'     => 'Саҳифаи "[[:$1]]" аз феҳристи пайгириҳои шумо бардошта шуд.',
'watch'                => 'Назар кардан',
'watchthispage'        => 'Пайгирии ин саҳифа',
'unwatch'              => 'Назар накардан',
'watchlist-details'    => '{{PLURAL:$1|$1 саҳифаи|$1 саҳифаҳои}} бидуни ҳисоби саҳифаҳои баҳс.',
'wlshowlast'           => 'Намоиши охирин $1 соат $2 рӯзҳо $3',
'watchlist-hide-bots'  => 'Пинҳон кардани вироишҳои роботҳо',
'watchlist-hide-own'   => 'Пинҳон кардани вироишҳои ман',
'watchlist-hide-minor' => 'Пинҳон намудани вироишҳои хурд',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Пайгири...',
'unwatching' => 'Тавқифи пайгири...',

'enotif_newpagetext' => 'Ин саҳифаи нав аст',

# Delete/protect/revert
'deletepage'                  => 'Ҳазфи саҳифа',
'confirm'                     => 'Тасдиқ',
'confirmdelete'               => 'Тасдиқи ҳазф',
'deletesub'                   => '(Дар ҳоли ҳазф "$1")',
'historywarning'              => 'Ҳушдор: Саҳифае ки шумо ҳазф карданиед, таърих дорад:',
'confirmdeletetext'           => 'Шумо дар ҳоли ҳазф кардани як саҳифа ё аксе аз пойгоҳ дода ҳамроҳ бо тамоми таърихи он ҳастед. Лутфан ин амалро тасдиқ кунед ва итминон ҳосил кунед, ки оқибати ин корро медонед ва ин амалро мутобиқи [[{{MediaWiki:Policy-url}}|сиёсати ҳазф]] анҷом медиҳед.',
'actioncomplete'              => 'Амал иҷро шуд',
'deletedtext'                 => '"$1" ҳазф шудааст.
Нигаред ба $2 барои гузориши ҳазфи охирин.',
'deletedarticle'              => 'ҳазфшуда "[[$1]]"',
'dellogpage'                  => 'Гузоришҳои ҳазф',
'reverted'                    => 'Ба нусхаи пештара вогардонида шуд',
'deletecomment'               => 'Сабаби ҳазфкунӣ',
'deleteotherreason'           => 'Далели дигар/иловагӣ:',
'deletereasonotherlist'       => 'Дигар сабаб',
'rollbacklink'                => 'вогардони',
'protectlogpage'              => 'Гузориши муҳофизат',
'confirmprotect'              => 'Тасдиқи муҳофизат',
'protectcomment'              => 'Далели муҳофизат:',
'protectexpiry'               => 'Замони саромадан:',
'protect_expiry_invalid'      => 'Замони саромадан номӯътабар аст.',
'protect_expiry_old'          => 'Замони саромадан дар гузашта аст.',
'protect-unchain'             => 'Боз кардани иҷозати кӯчонидан',
'protect-text'                => 'Шумо инҷо сатҳи муҳофизати саҳифаи strong>$1</strong> метавонед нигаред ё тағйир диҳед',
'protect-locked-access'       => 'Ҳисоби шумо иҷозати тағйири сатҳи ҳифозати саҳифаро надорад. 
Танзимоти кунунии саҳифа ба ин қарор аст <strong>$1</strong>:',
'protect-cascadeon'           => 'Ин саҳифа дар ҳоли ҳозир муҳофизат шудааст, чунки он дар {{PLURAL:$1|саҳифае, ки муҳофизати обшорӣ дорад|саҳифаҳое, ки муҳофизати обшорӣ доранд}} илова шудааст. Шумо метавонед сатҳи муҳофизати ин саҳифаро тағйир диҳед, аммо он ба муҳофизати обшорӣ таъсир нахоҳад расонд.',
'protect-default'             => '(пешфарз)',
'protect-fallback'            => 'Сатҳи дастрасӣ "$1" лозим аст',
'protect-level-autoconfirmed' => 'Бастани корбарони сабтиномнакарда',
'protect-level-sysop'         => 'Танҳо барои мудирон',
'protect-summary-cascade'     => 'обшорӣ',
'protect-expiring'            => 'замони саромадан $1 (UTC)',
'protect-cascade'             => 'Муҳофизати обшорӣ - Аз ҳама саҳифаҳое, ки дар ин саҳифа омадаанд муҳофизат мешаванд',
'protect-cantedit'            => 'Шумо вазъияти ҳифзи ин саҳифаро тағйир дода наметавонед, чун иҷозати вироиши онро надоред.',
'restriction-type'            => 'Дастраси:',
'restriction-level'           => 'Сатҳи маҳдудият:',

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
'contribsub2'   => 'Барои $1 ($2)',
'uctop'         => ' (боло)',
'month'         => 'Дар ин моҳ (ва қабл аз он):',
'year'          => 'Дар ин сол (ва қабл аз он):',

'sp-contributions-newbies'     => 'Фақат ҳиссагузориҳои ҳисобҳои ҷадидро нишон деҳ',
'sp-contributions-newbies-sub' => 'Барои навкорон',
'sp-contributions-blocklog'    => 'Гузориши басташуданҳо',
'sp-contributions-search'      => 'Ҷустуҷӯи ҳиссагузориҳо',
'sp-contributions-username'    => 'IP нишона ё номи корбар:',
'sp-contributions-submit'      => 'Ҷустуҷӯ',

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
'ipboptions'         => '2 соат:2 соат,1 рӯз:1 рӯз,3 рӯз:3 рӯз,1 ҳафта:1 ҳафта,2 ҳафта:2 ҳафта,1 моҳ:1 моҳ,3 моҳ:3 моҳ,6 моҳ:6 моҳ,1 сол:1 сол,беохир:беохир', # display1:time1,display2:time2,...
'blockipsuccesssub'  => 'Бастан муваффақ щуд',
'ipblocklist'        => 'Рӯйхати IP нишонаҳо ва корбарҳои баста шуда',
'blocklink'          => 'бастан',
'unblocklink'        => 'боз шавад',
'contribslink'       => 'ҳиссагузорӣ',
'blocklogpage'       => 'Сабти басташавӣ',
'blocklogentry'      => 'баста шуд [[$1]] бо вақти саромадан $2 $3',

# Move page
'movepage'         => 'Кӯчонидани саҳифа',
'movepagetext'     => "Бо истифодаи аз формаи зерин номи саҳифа тағйир хоҳад шуд, ва тамоми таърихаш ба номи ҷадид кӯчонида хоҳад шуд. 
Унвони пешина табдил ба як саҳифаи масир ба унвони ҷадид хоҳад шуд.
Пайвандҳо ба унвони пешинаи саҳифа тағйир нахоҳанд кард; ҳатман тағйири масирҳои дутоӣ ё шикастаро барраси кунед. 
Шумо масъул итминон ҳастед ки ин пайвандҳо ҳанӯз ба ҳамон ҷое ки қарор аст бираванд.

Таваҷҷӯҳ кунед, ки агар саҳифае дар унвони ҷадид вуҷуд дошта бошад саҳифа кӯчонида '''нахоҳад шуд''', магар ин ки саҳифа холӣ ё тағйири масир бошад ва таърихи вироиши надошта бошад. Ин яъне иштибоҳ агар иштибоҳ кардед метавонед саҳифаро ба ҳамон ҷое ки аз он кӯчонида шуда буд баргардонед, ва ин ки наметавонед рӯи саҳифаҳои мавҷудбуда бинависед.

<b>ҲУШДОР!</b>
Кӯчонидани саҳифаҳо ба номи ҷадид мумкин аст тағйири асосӣ ва ғайримунтазире барои саҳифаҳои машҳур бошад; лутфан мутмаин шавед ки пеш аз кӯчонидани саҳифа, оқибати ин корро дарк мекунед.",
'movepagetalktext' => "Саҳифаи баҳси марбута, агар вуҷуд дошта бошад, ба таври худкорӣ ҳамроҳ бо мақолаи аслӣ кӯчонида хоҳад шуд '''магар инки:'''

*дар ҳоли кӯчонидани саҳифа аз ин фазои ном ба фазои номи дигаре бошед,
*як саҳифаи баҳси ғайрихолӣ таҳти ин номи ҷадид вуҷуд дошта бошад, ё 
*ҷаъбаи зерро тик назада бошед.

Дар ин ҳолат, саҳифаро бояд ба таври дастӣ кӯчонид ва ё ду саҳифаро бо вироиш як кунед.",
'movearticle'      => 'Кӯчонидани саҳифа:',
'movenologin'      => 'Вуруд нашудаед',
'movenologintext'  => 'Барои кӯчонидани саҳифа шумо бояд корбари сабтшуда ва [[Special:Userlogin|ба систем вурудшуда]] бошед.',
'movenotallowed'   => 'Шумо иҷозати кӯчонидани саҳифаҳоро дар Википедиа надоред.',
'newtitle'         => 'Ба унвони ҷадид:',
'move-watch'       => 'Назар кардани ин саҳифа',
'movepagebtn'      => 'Кӯчонидани саҳифа',
'pagemovedsub'     => 'Кӯчониш бомуваффақият анҷом ёфт',
'movepage-moved'   => '<big>\'\'\'"$1" ба "$2" кӯчонида шудааст\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Саҳифае бо ин ном вуҷуд надорад, ё номе,
ки интихоб кардаед мӯътабар нест. 
Лутфан номи дигареро интихоб намоед.',
'talkexists'       => "'''Саҳифа бо мувафаққият худаш кӯчонида шуд, вале саҳифаи баҳсро, ба ин далел ки саҳифаи баҳсе дар унвони ҷадид вуҷуд дорад, кӯчонида намешавад. Лутфан онҳоро дастӣ таркиб кунед.'''",
'movedto'          => 'кӯчонидашуда ба',
'movetalk'         => 'Саҳифаи баҳси алоқаманд ҳам кӯчонида шавад',
'talkpagemoved'    => 'Саҳифаи баҳси алоқаманд низ кӯчонида шуд.',
'talkpagenotmoved' => 'Саҳифаи баҳси алоқаманд кӯчонида <strong>нашуд</strong>.',
'1movedto2'        => '[[$1]] ба [[$2]] кӯчонида шудааст',
'movelogpage'      => 'Кӯчонидани гузориш',
'movereason'       => 'Сабаби кӯчонидан:',
'revertmove'       => 'вогардонӣ',

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

# Import log
'importlogpage' => 'Вориди гузоришҳо',

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
'tooltip-t-contributions'         => 'Мушоҳидаи феҳристи ҳиссагузориҳои ин корбар',
'tooltip-t-emailuser'             => 'Фиристодани почтаи электронӣ ба ин корбар',
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
'subcategorycount'       => '{{PLURAL:$1|зергурӯҳ|$1 зергурӯҳҳо}} дар ин гурӯҳ вуҷуд дорад.',
'categoryarticlecount'   => '{{PLURAL:$1|мақола|$1 мақолаҳо}} дар ин гурӯҳ вуҷуд дорад.',
'category-media-count'   => '{{PLURAL:$1|парванда|$1 парвандаҳо}} дар ин гурӯҳ вуҷуд дорад.',
'listingcontinuesabbrev' => 'идома',

# Browsing diffs
'previousdiff' => '← Фарқияти аввалина',
'nextdiff'     => 'Фарқияти баъдина →',

# Media information
'file-info-size'       => '($1 × $2 пиксел, ҳаҷми парванда: $3, навъи MIME: $4)',
'file-nohires'         => '<small>Нусхаи ҳаҷман ва сифатан баландтар дастрас нест.</small>',
'svg-long-desc'        => '(SVG парванда, исмӣ $1 × $2 пиксел, андозаи парванда: $3)',
'show-big-image'       => 'Акси пурра',
'show-big-image-thumb' => '<small>Андозаи ин пешнамоиш: $1 × $2 пиксел</small>',

# Special:Newimages
'newimages'    => 'Намоишгоҳи парвандаҳои ҷадид',
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
'watchlistall2'    => 'ҳама',
'namespacesall'    => 'ҳама',
'monthsall'        => 'ҳама',

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

# Watchlist editing tools
'watchlisttools-view' => 'Намоиши тағйироти алоқаманди феҳристи пайгириҳо',
'watchlisttools-edit' => 'Мушоҳида ва вироиши феҳристи пайгириҳо',
'watchlisttools-raw'  => 'Вироиши феҳристи хоми пайгириҳо',

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
