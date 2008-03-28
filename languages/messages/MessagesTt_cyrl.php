<?php
/** Tatar (Cyrillic) (Tatarça/Татарча (Cyrillic))
 *
 * @addtogroup Language
 *
 * @author Ерней
 * @author Himiq Dzyu
 * @author Nike
 * @author Siebrand
 */

$fallback = 'ru';

$messages = array(
# User preference toggles
'tog-underline'               => 'Сылтамаларны астына сызу:',
'tog-highlightbroken'         => 'Бәйләнешсез сылтамаларны <a href="" class="new">шулай</a> күрсәтергә (юкса болай <a href="" class="internal">?</a>).',
'tog-justify'                 => 'Абзацларны киңлек буенча тигезләргә',
'tog-hideminor'               => 'Баягы үзгәртүләрдә әһәмиятсез үзгәртүләрне яшерергә',
'tog-extendwatchlist'         => 'Барлык кулланган үзгәртүләрне күрсәтү өчен күзәтү исемлегене җәяргә',
'tog-usenewrc'                => 'Баягы үзгәртүләрне яхшыртырган исемлеге (JavaScript)',
'tog-numberheadings'          => 'Башисемләрне автономерлау',
'tog-showtoolbar'             => 'Төзәтү кораллар аслыгыны күрсәтергә (JavaScript)',
'tog-editondblclick'          => 'Ике тапкыр чирттерү белән битләрне үзгәртергә (JavaScript)',
'tog-editsection'             => 'Бүлекне [үзгәртү] сылтама белән үзгәртергә мөмкинлеге',
'tog-editsectiononrightclick' => 'Бүлекне бүлек башисемендә уң чирттерү белән үзгәртергә мөмкинлеге (JavaScript)',
'tog-showtoc'                 => 'Эчтәлек җәдвәлене күрсәтергә (3-тән күбрәк башисемле битләр өчен)',
'tog-rememberpassword'        => 'Теркәү исемемне бу компьютердә онытмаска',
'tog-editwidth'               => 'Үзгәртү кыры күзәтүче тәрәзәсенең тулы киңлеккә',
'tog-watchcreations'          => 'Төзгән битләремне күзәтү исемлегемә өстәргә',
'tog-watchdefault'            => 'Үзгәртергән битләремне күзәтү исемлегемә өстәргә',
'tog-watchmoves'              => 'Күчерергән битләремне күзәтү исемлегемә өстәргә',
'tog-watchdeletion'           => 'Бетерергән битләремне күзәтү исемлегемә өстәргә',
'tog-minordefault'            => 'Барлык үзгәртүләрне килешү буенча әһәмиятсез дип билгеләргә',
'tog-previewontop'            => 'Мәкаләнең алдан карауны үзгәртү тәрәзәсенән өскәрәк күрсәтергә',
'tog-previewonfirst'          => 'Беренче үзгәртү буенча алдан карау',
'tog-enotifwatchlistpages'    => 'Күзәтелгән битләрем үзгәртсә электрон почта аша хәбәр җибәрергә',
'tog-enotifusertalkpages'     => 'Фикер алышу битем үзгәртсә электрон почта аша хәбәр җибәрергә',
'tog-enotifminoredits'        => "Битләрдә үзгәртүләр әһәмиятсез булса да, e-mail'ыма хәбәр җибәрергә",
'tog-watchlisthideown'        => 'Күзәтү исемлегедә үзгәртүләремне яшерергә',
'tog-watchlisthidebots'       => 'Күзәтү исемлегедә бот үзгәртүләрене яшерергә',
'tog-watchlisthideminor'      => 'Күзәтү исемлегедә әһәмиятсез үзгәртүләрене яшерергә',

'underline-always'  => 'Һәрвакыт',
'underline-never'   => 'Һичкайчан',
'underline-default' => 'Күзәтүче көйләүләрне кулланырга',

'skinpreview' => '(Алдан карау)',

# Dates
'sunday'        => 'якшәмбе',
'monday'        => 'дүшәмбе',
'tuesday'       => 'сишәмбе',
'wednesday'     => 'чәршәмбе',
'thursday'      => 'пәнҗешәмбе',
'friday'        => 'җомга',
'saturday'      => 'шимбә',
'sun'           => 'Якш',
'mon'           => 'Дүш',
'tue'           => 'Сиш',
'wed'           => 'Чәр',
'thu'           => 'Пән',
'fri'           => 'Җом',
'sat'           => 'Шим',
'january'       => 'гыйнвар',
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
'january-gen'   => 'гыйнвар',
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
'jan'           => 'гый',
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
'special-categories-sort-count' => 'исәп буенча тәртипләү',
'subcategories'                 => 'Төркемчәләр',

'about'          => 'Тасвир',
'article'        => 'Эчтәлек бите',
'newwindow'      => '(яңа тәрәзәдә ачыла)',
'cancel'         => 'Үткәрмәү',
'qbfind'         => 'Эзләү',
'qbbrowse'       => 'Күзәтү',
'qbedit'         => 'Үзгәртү',
'qbpageoptions'  => 'Бу бит',
'qbpageinfo'     => 'Бит турында мәгълүматлар',
'qbmyoptions'    => 'Битләрем',
'qbspecialpages' => 'Махсус битләр',
'moredotdotdot'  => 'Дәвам…',
'mypage'         => 'Минем битем',
'mytalk'         => 'Фикер алышу битем',
'anontalk'       => 'Бу IP-адрес өчен фикер алышу',
'navigation'     => 'Күчешлек',
'and'            => 'һәм',

# Metadata in edit box
'metadata_help' => 'Мета мәгълүматлар:',

'errorpagetitle'    => 'Хата',
'returnto'          => 'Биткә $1 кайту.',
'tagline'           => '{{grammar:genitive|{{SITENAME}}}} дан',
'help'              => 'Белешмә',
'search'            => 'Эзләү',
'searchbutton'      => 'Эзләү',
'go'                => 'Күчү',
'searcharticle'     => 'Күчү',
'history'           => 'Битнең тарихы',
'history_short'     => 'Тарих',
'updatedmarker'     => 'соңы булуымнан яңартырган',
'info_short'        => 'Мәгълүмат',
'printableversion'  => 'Бастыру өчен юрама',
'permalink'         => 'Даими сылтама',
'print'             => 'Бастыру',
'edit'              => 'Үзгәртү',
'create'            => 'Төзергә',
'editthispage'      => 'Бу битне үзгәртергә',
'create-this-page'  => 'Бу битне төзергә',
'delete'            => 'Бетерергә',
'deletethispage'    => 'Бу битне бетерергә',
'protect'           => 'Якларга',
'protect_change'    => 'яклауны үзгәртергә',
'protectthispage'   => 'Бу битне якларга',
'unprotect'         => 'Якланмаска',
'unprotectthispage' => 'Бу битне якланмаска',
'newpage'           => 'Яңа бит',
'talkpage'          => 'Бу битне фикер алышырга',
'talkpagelinktext'  => 'Фикер алышу',
'specialpage'       => 'Махсус бит',
'personaltools'     => 'Шәхси кораллар',
'postcomment'       => 'Шәрех бирү',
'articlepage'       => 'Битнең эчтәлеге карарга',
'talk'              => 'Фикер алышу',
'views'             => 'Караулар',
'toolbox'           => 'Кораллар җыелмасы',
'userpage'          => 'Кулланучының битене карарга',
'projectpage'       => 'Проект битене карарга',
'imagepage'         => 'Сүрәтнең битене карарга',
'mediawikipage'     => 'Хәбәрнең битене карарга',
'templatepage'      => 'Өлгенең битене карарга',
'viewhelppage'      => 'Белешмәнең битене карарга',
'categorypage'      => 'Төркемнең битене карарга',
'viewtalkpage'      => 'Фикер алышуны карарга',
'otherlanguages'    => 'Башка телләрендә',
'redirectpagesub'   => 'Башка биткә юнәлтү бит',
'lastmodifiedat'    => 'Бу битне соңгы үзгәртү: $2, $1.', # $1 date, $2 time
'protectedpage'     => 'Якланган бит',
'jumpto'            => 'Күчергә:',
'jumptonavigation'  => 'навигация',
'jumptosearch'      => 'эзләү',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{GRAMMAR:genitive|{{SITENAME}}}} турында',
'aboutpage'         => 'Project:Тасвир',
'currentevents'     => 'Агымдагы вакыйгалар',
'currentevents-url' => 'Project:Агымдагы вакыйгалар',
'disclaimers'       => 'Җаваплылыктан баш тарту',
'disclaimerpage'    => 'Project:Җаваплылыктан баш тарту',
'edithelp'          => 'Үзгәртү хакында',
'edithelppage'      => 'Ярдәм:Үзгәртү',
'mainpage'          => 'Төп бит',
'portal'            => 'Җәмгыять үзәге',
'portal-url'        => 'Project:Җәмгыять үзәге',
'privacy'           => 'Яшеренлек сәясәте',
'privacypage'       => 'Project:Яшеренлек сәясәте',

'badaccess' => 'Рөхсәт хатасы',

'versionrequired' => 'MediaWiki версия $1 белән кирәк',

'ok'                      => 'Ярар',
'youhavenewmessages'      => 'Сездә $1 ($2) бар.',
'newmessageslink'         => 'яңа хәбәрләр',
'newmessagesdifflink'     => 'соңы үзгәртү',
'youhavenewmessagesmulti' => 'Сезнең $1 да яңа хәбәрләр бар',
'editsection'             => 'үзгәртү',
'editold'                 => 'үзгәртергә',
'editsectionhint'         => '$1 бүлекне үзгәртергә',
'toc'                     => 'Эчтәлек',
'showtoc'                 => 'күрсәтергә',
'hidetoc'                 => 'яшерергә',
'red-link-title'          => '$1 (әле язылмаган)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Бит',
'nstab-user'      => 'Кулланучы бите',
'nstab-media'     => 'Мультимедиа',
'nstab-special'   => 'Махсус',
'nstab-project'   => 'Проект бите',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Хәбәр',
'nstab-template'  => 'Өлге',
'nstab-help'      => 'Белешмә бите',
'nstab-category'  => 'Төркем',

# Main script and global functions
'nosuchaction'      => 'Шулай гамәл юк',
'nosuchspecialpage' => 'Андый махсус бит юк',

# General errors
'error'               => 'Хата',
'databaseerror'       => 'Мәгълүматлар базасы хатасы',
'internalerror'       => 'Эчке хата',
'internalerror_info'  => 'Эчке хата: $1',
'viewsource'          => 'Чыганак карарга',
'viewsourcefor'       => 'Бит «$1»',
'namespaceprotected'  => "'''$1''' исем киңлегендәге битләрне үзгәртү өчен сезнең рөхсәтегез юк.",
'ns-specialprotected' => '«{{ns:special}}» киңлегендәге битләрне үзгәртеп булмый.',

# Login and logout pages
'logouttitle'                => 'Чыгарга',
'loginpagetitle'             => 'Кулланучының теркәү исеме',
'yourname'                   => 'Кулланучы исеме:',
'yourpassword'               => 'Серсүз:',
'yourpasswordagain'          => 'Серсүзне кабат кертү:',
'remembermypassword'         => 'Теркәү исемемне бу компьютердә онытмаска',
'yourdomainname'             => 'Сезнең доменыгыз:',
'login'                      => 'Керү',
'userlogin'                  => 'Керү / хисап язмасы төзү',
'logout'                     => 'Чыгу',
'userlogout'                 => 'Чыгу',
'notloggedin'                => 'Кермәгәнсез',
'nologinlink'                => 'Хисап язмасыны төзәргә',
'createaccount'              => 'Хисап язмасыны төзәргә',
'gotaccountlink'             => 'Керергә',
'createaccountmail'          => 'электрон почта белән',
'youremail'                  => 'Электрон почта:',
'username'                   => 'Теркәү исеме:',
'uid'                        => 'Кулланучының идентификаторы:',
'yourrealname'               => 'Чын исем:',
'yourlanguage'               => 'Тел:',
'yournick'                   => 'Тахалус:',
'badsiglength'               => 'Ирешү исем ифрат озын, ул $1 хәрефтән күбрәк түгел булырга тиеш.',
'email'                      => 'Электрон почта',
'prefs-help-realname'        => 'Чын исемегез (кирәкми): аны күрсәтсәгез, ул битне үзгәртүче күрсәтү өчен файдалаячак.',
'loginerror'                 => 'Керү хатасы',
'prefs-help-email-required'  => 'Электрон почта адресы кирәк.',
'loginsuccesstitle'          => 'Керү уңышлы үтте',
'loginsuccess'               => "Сез '''$1''' исем белән кергәнсез.",
'nosuchusershort'            => "Кулланучы '''<nowiki>$1</nowiki>''' ирешү исеме белән юк. Исем язу тикшерегез.",
'nouserspecified'            => 'Сез теркәү исмегезне күрсәтергә тиешсез.',
'wrongpassword'              => 'Язылган серсүз дөрес түгел. Тагын бер тапкыр сынагыз.',
'passwordtooshort'           => 'Язылган серсүз начар яки ифрат кыска. Сезсүз $1 хәрефтән булырга һәм кулланучы исеменнән аерылырга тиеш.',
'mailmypassword'             => 'Серсүзне электрон почтага җибәрергә',
'passwordremindertitle'      => '{{SITENAME}} кулланучысына яңа вакытлы серсүз',
'passwordremindertext'       => 'Кемдер (сез, бәлки) $1 IP-адрестан,
без сезгә {{SITENAME}} ($4) кулланучысы яңа серсүзе күндерербез, дип сораган.
Кулланучы $2 өчен хәзер: <code>$3</code>.
Сез системага керергә һәм серсүзне алмаштырырга тиеш.

Әгәр сез серсүзне алмаштыру сорамаса идегез яки серсүз хәтерләсәгез,
сез бу хәбәр игътибарсыз калдыра һәм иске серсүзне куллану дәвам итә аласыз.',
'throttled-mailpassword'     => 'Серсүзне искәртмә соңгы $1 сәгать дәвамында инде күндерелде. Начар куллану булдырмау өчен $1 сәгать дәвамында бер тапкырдан күбрәк түгел сорап була.',
'mailerror'                  => 'Хат күндерү хатасы: $1',
'acct_creation_throttle_hit' => 'Гафу итегез, сез $1 хисап язмасыгызны төзгәнсез инде. Башка төзү алмыйсыз.',
'emailconfirmlink'           => 'Электрон почта адресыгызны раслагыз',
'accountcreated'             => 'Хисап язмасы төзелгән',
'accountcreatedtext'         => '$1 өчен кулланучы хисап язмасы төзелгән.',
'loginlanguagelabel'         => 'Тел: $1',

# Password reset dialog
'resetpass_header'        => 'Серсүзне ташлатырга',
'resetpass_bad_temporary' => 'Вакытлы серсүз дөрес түгел. Бәлки, сез инде серсүзне алмаштырган идегез, яки тагын бер тапкыр вакытлы серсүз сорарга сынагыз.',
'resetpass_forbidden'     => 'Бу вики-системада серсүзләрне алмаштырып булмый.',

# Edit page toolbar
'bold_sample'     => 'Калын язылышы',
'bold_tip'        => 'Калын язылышы',
'italic_sample'   => 'Курсив язылышы',
'italic_tip'      => 'Курсив язылышы',
'link_sample'     => 'Сылтаманың башисеме',
'link_tip'        => 'Эчке сылтама',
'extlink_sample'  => 'http://www.misal.tat сылтаманың башисеме',
'extlink_tip'     => 'Тышкы сылтама (http:// алкушымчасы турында онытмагыз)',
'headline_sample' => 'Башисем тексты',
'math_sample'     => 'Формуланы монда өстәгез',
'math_tip'        => 'Математика формуласы (LaTeX)',
'nowiki_sample'   => 'Форматланмаган текстны монда өстәгез',
'nowiki_tip'      => 'Вики-форматлауны исәпкә алмаска',
'image_tip'       => 'Куелган рәсем',
'media_tip'       => 'Медиа-файлга сылтама',
'sig_tip'         => 'Имзагыз да вакыт',
'hr_tip'          => 'Горизонталь сызык (еш кулланмагыз)',

# Edit pages
'summary'                => 'Үзгәртүләр тасвиры',
'subject'                => 'Тема/башисем',
'minoredit'              => 'Бу әһәмиятсез үзгәртү',
'watchthis'              => 'Бу битне күзәтергә',
'savearticle'            => 'Битне саклау',
'preview'                => 'Алдан карау',
'showpreview'            => 'Алдан карау',
'showlivepreview'        => 'Тиз алдан карау',
'showdiff'               => 'Үзгәртүләрне күрсәтү',
'anoneditwarning'        => "'''Игътибар''': Сез системага кермәгәнсез. IP-адресыгыз бу битнең тарихына язылыр.",
'missingsummary'         => "'''Искәртү.''' Сез үзгәртмә кыска язу бирмәгәнсез. Сез «Битне саклау» кнопкасына тагын бер тапкыр бассагыз, үзгәртмәгез комментсыз сакланыр.",
'missingcommenttext'     => 'Зинһар, аска комментыгыз языгыз.',
'missingcommentheader'   => "'''Искәртү:''' Сез комментыгызның башын күрсәтмәгәнсез.
Сез «Битне саклау» кнопкасына бассагыз, үзгәртмәгез башсыз язылыр.",
'summary-preview'        => 'Җыелма нәтиҗәне алдан карау',
'subject-preview'        => 'Башисемне алдан карау',
'blockedtitle'           => 'Кулланучы кыстырган',
'blockednoreason'        => 'сәбәп күрсәтмәгән',
'blockedoriginalsource'  => 'Бит «$1» тексты аска күрсәткән.',
'blockededitsource'      => "Бит «$1» '''үзгәртүегез''' тексты аска күрсәткән.",
'whitelistedittitle'     => 'Үзгәртү өчен керү кирәк',
'whitelistedittext'      => 'Сез битләрне үзгәртү өчен $1 тиеш.',
'whitelistreadtitle'     => 'Уку өчен керү кирәк',
'whitelistacctitle'      => 'Сездә хисап ясау хокукы юк',
'nosuchsectiontitle'     => 'Андый секция юк',
'loginreqtitle'          => 'Керү кирәк',
'loginreqlink'           => 'керү',
'loginreqpagetext'       => 'Сез башка битләр карау өчен $1 тиеш',
'accmailtitle'           => 'Серсүз җибәрелгән.',
'accmailtext'            => '$1 өченге серсүз $2 кулланучыга күндерелгән.',
'newarticle'             => '(Яңа)',
'newarticletext'         => "Сез әле язылмаган биткәге сылтама куллангансыз.
Яңа бит ясау өчен аскагы тәрәзәдә мәкалә тексты языгыз
([[{{MediaWiki:Helppage}}|ярдәм бите]] к. күбрәк информация алу өчен).
Әгәр сез бу бит ялгышлык белән ачса идегез, гади браузерыгызның '''артка''' кнопкасына басыгыз.",
'anontalkpagetext'       => "----''Бу хисапланмаган да хисапланган исем белән кергән кулланучы фикер алышу бите. Аны билгеләү өчен IP-адрес файдалый. Әгәр сез аноним кулланучы һәм сез, сезгә күндерелмәгән хәбәрләр алдыгыз, дип саныйсыз (бер IP-адрес күп кулланучы өчен була ала), зинһар, [[{{ns:special}}:Userlogin|системага керегез]], киләчәктә аңлашмау теләмәсәгез.''",
'noarticletext'          => "Хәзер бу биттә текст юк. Сез [[{{ns:special}}:Search/{{PAGENAME}}|аның башы башка мәкаләләрдә таба]] яки '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} андый баш белән бит ясый]''' аласыз.",
'clearyourcache'         => "'''Искәрмә:''' Битне саклаудан соң алмаштырма күрү өчен браузерыгызның кешын буш итегез: '''Mozilla / Firefox''': ''Ctrl+Shift+R'', '''IE:''' ''Ctrl+F5'', '''Safari''': ''Cmd+Shift+R'', '''Konqueror''': ''F5'', '''Opera''': ''Tools→Preferences'' меню белән.",
'yourtext'               => 'Сезнең текст',
'storedversion'          => 'Сакланган юрама',
'yourdiff'               => 'Аермалыклар',
'templatesused'          => 'Бу биттә кулланган өлгеләр:',
'templatesusedpreview'   => 'Бу алдан карау биттә кулланган өлгеләр:',
'templatesusedsection'   => 'Бу бүлектә кулланган өлгеләр:',
'template-protected'     => '(якланган)',
'template-semiprotected' => '(өлешчә якланган)',
'nocreatetitle'          => 'Битләр төзүе чикләнгән',

# Account creation failure
'cantcreateaccounttitle' => 'Хисап язмасыны төзергә мөмкинлек юк',

# History pages
'currentrev' => 'Агымдагы юрама',
'cur'        => 'агым.',
'last'       => 'бая.',
'histfirst'  => 'Баштагы',
'histlast'   => 'Баягы',

# Revision deletion
'rev-deleted-comment' => '(искәрмә бетергән)',
'rev-deleted-user'    => '(авторның исеме бетерергән)',
'rev-deleted-event'   => '(язма бетерергән)',
'rev-delundel'        => 'күрсәтергә/яшерергә',

# Diffs
'difference' => '(Төзәтмәләр арасында аермалар)',
'lineno'     => '$1 юл:',
'editundo'   => 'үткәрмәү',

# Search results
'powersearch'        => 'Өстәмә эзләү',
'powersearch-legend' => 'Өстәмә эзләү',

# Preferences page
'preferences'           => 'Көйләүләр',
'mypreferences'         => 'Көйләүләрем',
'prefs-edits'           => 'Үзгәртүләр исәбе:',
'prefsnologin'          => 'Кермәгәнсез',
'prefsnologintext'      => 'Кулланучы көйләүләрене үзгәртү өчен, сез [[{{ns:special}}:Userlogin|керергә]] тиешсез.',
'qbsettings'            => 'Күчешләр аслыгы',
'qbsettings-none'       => 'Күрсәтмәскә',
'prefs-personal'        => 'Шәхси мәгълүматлар',
'prefs-rc'              => 'Баягы үзгәртүләр',
'prefs-watchlist'       => 'Күзәтү исемлеге',
'prefs-watchlist-days'  => 'Күзәтү исемлегендә ничә көн буена үзгәртүләрне күрсәтергә:',
'prefs-watchlist-edits' => 'Яхшыртырган исемлегендә үзгәртүләрнең иң югары исәбе:',
'prefs-misc'            => 'Башка көйләүләр',
'saveprefs'             => 'Саклау',
'resetprefs'            => 'Ташлау',
'oldpassword'           => 'Иске серсүз:',
'newpassword'           => 'Яңа серсүз:',
'retypenew'             => 'Яңа серсүзне кабатлагыз:',

'group-autoconfirmed-member' => 'авторасланган кулланучы',
'group-bot-member'           => 'Бот',
'group-sysop-member'         => 'Идарәче',
'group-bureaucrat-member'    => 'Бюрократ',

# Recent changes
'rcshowhidebots'  => '$1 бот',
'rcshowhideliu'   => '$1 кергән кулланучы',
'rcshowhideanons' => '$1 кермәгән кулланучы',
'rcshowhidemine'  => '$1 минем үзгәртү',
'show'            => 'Күрсәтергә',
'minoreditletter' => 'ә',

# Recent changes linked
'recentchangeslinked' => 'Бәйләнешле үзгәртүләр',

# Upload
'upload' => 'Файлны йөкләргә',

# Special:Imagelist
'imagelist'             => 'Файллар исемлеге',
'imagelist_date'        => 'Вакыт',
'imagelist_name'        => 'Ат',
'imagelist_user'        => 'Кулланучы',
'imagelist_size'        => 'Үлчәм',
'imagelist_description' => 'Тасвир',

# MIME search
'mimesearch' => 'MIME эзләү',

# Unused templates
'unusedtemplates' => 'Кулланмаган өлгеләр',

# Random page
'randompage' => 'Очраклы бит',

# Random redirect
'randomredirect' => 'Очраклы башка биткә күчү',

'withoutinterwiki' => 'Телләрара сылтамасыз битләр',

'fewestrevisions' => 'Аз үзгәртүләр белән битләр',

# Miscellaneous special pages
'uncategorizedpages'      => 'Төркемләнмәгән битләр',
'uncategorizedcategories' => 'Төркемләнмәгән төркемнәр',
'uncategorizedimages'     => 'Төркемләнмәгән файллар',
'uncategorizedtemplates'  => 'Төркемләнмәгән өлгеләр',
'unusedcategories'        => 'Кулланмаган төркемнәр',
'unusedimages'            => 'Кулланмаган файллар',
'wantedcategories'        => 'Зарур төркемнәр',
'wantedpages'             => 'Кирәкле битләр',
'mostlinked'              => 'Күп үзенә сылтамалы битләр',
'mostlinkedcategories'    => 'Күп үзенә сылтамалы төркемнәр',
'mostlinkedtemplates'     => 'Иң кулланган өлгеләр',
'mostcategories'          => 'Күп төркемләргә кертелгән битләр',
'mostimages'              => 'Иң кулланган сүрәтләр',
'mostrevisions'           => 'Күп үзгәртүләр белән битләр',
'shortpages'              => 'Кыска мәкаләләр',
'specialpages'            => 'Махсус битләр',

# Special:Allpages
'allpages'     => 'Барлык битләр',
'allpagesprev' => 'Элекке',
'allpagesnext' => 'Киләсе',

# Watchlist
'watchlist'     => 'Күзәтү исемлегем',
'mywatchlist'   => 'Күзәтү исемлегем',
'watch'         => 'Күзәтергә',
'watchthispage' => 'Бу битне күзәтергә',
'unwatch'       => 'Күзәтмәскә',

# Delete/protect/revert
'deletepage'            => 'Битне бетерергә',
'actioncomplete'        => 'Гамәл башкарган',
'dellogpage'            => 'Бетерү исемлеге',
'deletecomment'         => 'Бетерү сәбәбе:',
'deleteotherreason'     => 'Башка/өстәмә сәбәп:',
'deletereasonotherlist' => 'Башка сәбәп',
'rollbacklink'          => 'кире кайтару',
'protect-default'       => '(килешү буенча)',
'protect-fallback'      => '«$1»нең рөхсәте кирәк',
'protect-level-sysop'   => 'Идарәчеләр генә',
'protect-expiring'      => '$1 үтә (UTC)',
'restriction-type'      => 'Рөхсәт:',
'restriction-level'     => 'Мөмкинлек дәрәҗәсе:',

# Namespace form on various pages
'namespace'      => 'Исемнәр мәйданы:',
'invert'         => 'Сайланганны әйләнергә',
'blanknamespace' => '(Төп)',

# Contributions
'contributions' => 'Кулланучының кертеме',
'mycontris'     => 'Кертемем',

# What links here
'whatlinkshere'       => 'Бирегә нәрсә сылтый',
'nolinkshere'         => "'''[[:$1]]''' битенә башка битләр сылтамыйлар.",
'whatlinkshere-links' => '← сылтамалар',

# Block/unblock
'blocklink'    => 'кысарга',
'unblocklink'  => 'кысмаска',
'contribslink' => 'кертем',

# Move page
'movearticle'    => 'Битне күчерергә:',
'newtitle'       => 'Яңа башлам:',
'move-watch'     => 'Бу битне күзәтергә',
'movepagebtn'    => 'Битне күчерергә',
'pagemovedsub'   => 'Бит күчерергән',
'movepage-moved' => "<big>'''«$1» бит «$2» биткә күчкән'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.

# Tooltip help for the actions
'tooltip-ca-talk'         => 'Битнең эчтәлеге турында фикер алышу',
'tooltip-ca-edit'         => 'Сез бу бит үзгәртә аласыз. Зинһар, саклаганчы карап алуны кулланыгыз.',
'tooltip-search'          => 'Эзләү {{SITENAME}}',
'tooltip-n-mainpage'      => 'Төп битне кереп чыгарга',
'tooltip-n-recentchanges' => 'Баягы үзгәртүләр исемлеге.',
'tooltip-n-randompage'    => 'Очраклы битне карарга',
'tooltip-n-sitesupport'   => 'Безгә ярдәм итегез',
'tooltip-t-whatlinkshere' => 'Бирегә сылтаган барлык битләрнең исемлеге',
'tooltip-t-upload'        => 'Файлларны йөкләргә',
'tooltip-t-specialpages'  => 'Барлык махсус битләр исемлеге',
'tooltip-ca-nstab-user'   => 'Кулланучының битене карарга',
'tooltip-ca-nstab-help'   => 'Белешмәнең битене карарга',
'tooltip-minoredit'       => 'Бу үзгәртүне әһәмиятсез булып билгеләргә',
'tooltip-watch'           => 'Бу битне күзәтү исемлегемә өстәргә',

# 'all' in various places, this might be different for inflected languages
'monthsall' => 'барлык',

# Special:Version
'version' => 'Юрама', # Not used as normal message but as header for the special page itself

);
