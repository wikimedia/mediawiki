<?php
/** ‪Crimean Tatar (Latin)‬ (‪Qırımtatarca (Latin)‬)
 *
 * @addtogroup Language
 *
 * @author Alessandro
 */

$fallback8bitEncoding = 'windows-1254';	

$separatorTransformTable = array(','     => '.', '.'     => ',' );

$namespaceNames = array(
    NS_MEDIA                     => 'Media',
    NS_SPECIAL                   => 'Mahsus',
    NS_MAIN                      => '',
    NS_TALK                      => 'Muzakere',
    NS_USER                      => 'Qullanıcı',
    NS_USER_TALK                 => 'Qullanıcı_muzakeresi',
    # NS_PROJECT set by $wgMetaNamespace
    NS_PROJECT_TALK              => '$1_muzakeresi',
    NS_IMAGE                     => 'Resim',
    NS_IMAGE_TALK                => 'Resim_muzakeresi',
    NS_MEDIAWIKI                 => 'MediaViki',
    NS_MEDIAWIKI_TALK            => 'MediaViki_muzakeresi',
    NS_TEMPLATE                  => 'Şablon',
    NS_TEMPLATE_TALK             => 'Şablon_muzakeresi',
    NS_HELP                      => 'Yardım',
    NS_HELP_TALK                 => 'Yardım_muzakeresi',
    NS_CATEGORY                  => 'Kategoriya',
    NS_CATEGORY_TALK             => 'Kategoriya_muzakeresi',
);

# Aliases to cyril namespaces 
$namespaceAliases = array(
	"Медиа"                  => NS_MEDIA,
	"Махсус"                 => NS_SPECIAL,
	"Музакере"               => NS_TALK,
	"Къулланыджы"            => NS_USER,
	"Къулланыджы_музакереси" => NS_USER_TALK,
	"$1_музакереси"          => NS_PROJECT_TALK,
	"Ресим"                  => NS_IMAGE,
	"Ресим_музакереси"       => NS_IMAGE_TALK,
	"МедиаВики"              => NS_MEDIAWIKI,
	"МедиаВики_музакереси"   => NS_MEDIAWIKI_TALK,
	'Шаблон'                 => NS_TEMPLATE,
	'Шаблон_музакереси'      => NS_TEMPLATE_TALK,
	'Ярдым'                  => NS_HELP,
	'Разговор_о_помоћи'      => NS_HELP_TALK,
	'Категория'              => NS_CATEGORY,
	'Категория_музакереси'   => NS_CATEGORY_TALK,
);

$skinNames = array(
    'standard'    => 'Standart',
    'nostalgia'   => 'Nostalgiya',
    'cologneblue' => 'Köln asretligi',
    'monobook'    => 'MonoBook',
    'myskin'      => 'Öz resimleme',
    'chick'       => 'Çipçe',
    'simple'      => 'Adiy'
);

$datePreferences = array(
    'default',
    'mdy',
    'dmy',
    'ymd',
    'yyyy-mm-dd',
    'ISO 8601',
);

$defaultDateFormat = 'ymd';

$datePreferenceMigrationMap = array(
    'default',
    'mdy',
    'dmy',
    'ymd'
);

$dateFormats = array(
    'mdy time' => 'H:i',
    'mdy date' => 'F j Y "s."',
    'mdy both' => 'H:i, F j Y "s."',

    'dmy time' => 'H:i',
    'dmy date' => 'j F Y "s."',
    'dmy both' => 'H:i, j F Y "s."',

    'ymd time' => 'H:i',
    'ymd date' => 'Y "s." xg j',
    'ymd both' => 'H:i, Y "s." xg j',

    'yyyy-mm-dd time' => 'xnH:xni:xns',
    'yyyy-mm-dd date' => 'xnY-xnm-xnd',
    'yyyy-mm-dd both' => 'xnH:xni:xns, xnY-xnm-xnd',

    'ISO 8601 time' => 'xnH:xni:xns',
    'ISO 8601 date' => 'xnY.xnm.xnd',
    'ISO 8601 both' => 'xnY.xnm.xnd"T"xnH:xni:xns',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Bağlantılarnıñ altını sız',
'tog-highlightbroken'         => 'Boş bağlantılarnı <a href="" class="new">bu şekilde</a> (alternativ: bu şekilde<a href="" class="internal">?</a>) köster.',
'tog-justify'                 => 'Paragraf eki yaqqa yaslayaraq tiz',
'tog-hideminor'               => 'Kiçik deñişikliklerni "Soñki deñişiklikler" saifesinde gizle',
'tog-extendwatchlist'         => 'Kelişken közetüv cedveli',
'tog-usenewrc'                => 'Kelişken soñki deñişiklikler cedveli (JavaScript)',
'tog-numberheadings'          => 'Serlevalarnı avtomatik nomeralandır',
'tog-showtoolbar'             => 'Deñişiklik yapqan vaqıtta yardımcı dögmelerni köster. (JavaScript)',
'tog-editondblclick'          => 'Saifeni çift basaraq deñiştirmege başla (JavaScript)',
'tog-editsection'             => 'Bölümlerni [deñiştir] bağlantılarnı ile deñiştirme aqqı ber',
'tog-editsectiononrightclick' => 'Bölüm serlevasına oñ basaraq bölümde deñişiklikke izin ber.(JavaScript)',
'tog-showtoc'                 => 'İçindekiler tablosını yap<br />(3 daneden ziyade serlevası olğan saifeler içün)',
'tog-rememberpassword'        => 'Parolni hatırla',
'tog-editwidth'               => 'Yazuv fezası tam kenişlikte olsun',
'tog-watchcreations'          => 'Yaratqan saifelerimni közetüv cedvelime ekle',
'tog-watchdefault'            => 'Deñişiklik yapılğan saifeni közetüv cedveline ekle',
'tog-minordefault'            => "Deñişiklikni 'kiçik deñişiklik' olaraq seçili ketir",
'tog-previewontop'            => 'Og baquvnı yazuv fezanıñ üstünde köster',
'tog-previewonfirst'          => 'Deñiştirmede ög baquvnı köster',
'tog-nocache'                 => 'Saifelerni hatırlama',
'tog-enotifwatchlistpages'    => 'Saife deñişikliklerinde maña e-mail yolla',
'tog-enotifusertalkpages'     => 'Qullanıcı saifemde deñişiklik olğanda maña e-mail yolla',
'tog-enotifminoredits'        => 'Saifelerdeki kiçik deñişikliklerde de maña e-mail yolla',
'tog-shownumberswatching'     => 'Közetken qullanıcı sayısını köster',
'tog-fancysig'                => 'Adiy imza (imzañız yuqarıda belgilegeniñiz kibi körünir. Saifeñizge avtomatik bağlantı yaratılmaz)',
'tog-externaleditor'          => 'Deñişikliklerni başqa editor programması ile yap',
'tog-externaldiff'            => 'Teñeştirmelerni tış programmağa yaptır.',
'tog-showjumplinks'           => '"Bar" bağlantısını faalleştir',
'tog-uselivepreview'          => 'Canlı ög baquv hususiyetini qullan (JavaScript) (daa deñeme turuşında)',
'tog-forceeditsummary'        => 'Deñişiklik qısqa tarifini boş taşlağanda meni tenbile',
'tog-watchlisthideown'        => 'Közetüv cedvelimden menim deñişikliklerimni gizle',
'tog-watchlisthidebots'       => 'Közetüv cedvelimden bot deñişikliklerini gizle',

'underline-always'  => 'Daima',
'underline-never'   => 'Asla',
'underline-default' => 'Brauzer qarar bersin',

'skinpreview' => '(Ög baquv)',

# Dates
'sunday'        => 'Bazar',
'monday'        => 'Bazarertesi',
'tuesday'       => 'Salı',
'wednesday'     => 'Çarşenbe',
'thursday'      => 'Cumaaqşamı',
'friday'        => 'Cuma',
'saturday'      => 'Cumaertesi',
'january'       => 'yanvar',
'february'      => 'fevral',
'march'         => 'mart',
'april'         => 'aprel',
'may_long'      => 'mayıs',
'june'          => 'iyün',
'july'          => 'iyül',
'august'        => 'avgust',
'september'     => 'sentâbr',
'october'       => 'oktâbr',
'november'      => 'noyabr',
'december'      => 'dekabr',
'january-gen'   => 'yanvarniñ',
'february-gen'  => 'fevralniñ',
'march-gen'     => 'martnıñ',
'april-gen'     => 'aprelniñ',
'may-gen'       => 'mayısnıñ',
'june-gen'      => 'iyünniñ',
'july-gen'      => 'iyülniñ',
'august-gen'    => 'avgustnıñ',
'september-gen' => 'sentâbrniñ',
'october-gen'   => 'oktâbrniñ',
'november-gen'  => 'noyabrniñ',
'december-gen'  => 'dekabrniñ',
'jan'           => 'yan',
'feb'           => 'fev',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'may',
'jun'           => 'iyün',
'jul'           => 'iyül',
'aug'           => 'avg',
'sep'           => 'sen',
'oct'           => 'okt',
'nov'           => 'noy',
'dec'           => 'dek',

# Bits of text used by many pages
'categories'            => 'Saife kategoriyaları',
'pagecategories'        => 'Saife {{PLURAL:$1|kategoriyası|kategoriyaları}}',
'category_header'       => '"$1" kategoriyasındaki saifeler',
'subcategories'         => 'Alt kategoriyalar',
'category-media-header' => '"$1" kategoriyasındaki media faylları',
'category-empty'        => "''İşbu kategoriyada iç bir saife ya da media fayl yoq.''",

'linkprefix'   => '/^(.*?)([a-zâçğıñöşüA-ZÂÇĞİÑÖŞÜ«„]+)$/sDu',
'mainpagetext' => "<big>'''Mediaviki muvafaqiyetnen quruldı.'''</big>",

'about'          => 'Aqqında',
'article'        => 'Maqale',
'newwindow'      => '(yañı bir pencerede açılır)',
'cancel'         => 'Lâğu',
'qbfind'         => 'Tap',
'qbbrowse'       => 'Baqıp çıq',
'qbedit'         => 'Deñiştir',
'qbpageoptions'  => 'Bu saife',
'qbpageinfo'     => 'Bağlam',
'qbmyoptions'    => 'Saifelerim',
'qbspecialpages' => 'Mahsus saifeler',
'moredotdotdot'  => 'Daa...',
'mypage'         => 'Saifem',
'mytalk'         => 'Muzakere saifesim',
'anontalk'       => 'Bu IP-niñ muzakeresi',
'navigation'     => 'Saytta yol tapuv',

'errorpagetitle'    => 'Hata',
'returnto'          => '$1.',
'tagline'           => '{{GRAMMAR:ablative|{{SITENAME}}}}',
'help'              => 'Yardım',
'search'            => 'Qıdır',
'searchbutton'      => 'Qıdır',
'go'                => 'Bar',
'searcharticle'     => 'Bar',
'history'           => 'Saifeniñ keçmişi',
'history_short'     => 'Keçmiş',
'updatedmarker'     => 'soñki ziyaretimden soñ yañarğan',
'info_short'        => 'Malümat',
'printableversion'  => 'Basılmağa uyğun körüniş',
'permalink'         => 'Soñki alına bağlantı',
'print'             => 'Bastır',
'edit'              => 'Deñiştir',
'editthispage'      => 'Saifeni deñiştir',
'delete'            => 'Yoq et',
'deletethispage'    => 'Saifeni yoq et',
'undelete_short'    => '{{PLURAL:$1|1|$1}} deñişiklikni keri ketir',
'protect'           => 'Qorçalavğa al',
'protectthispage'   => 'Saifeni qorçalav altına al',
'unprotect'         => 'Qorçalavnı çıqar',
'unprotectthispage' => 'Saife qorçalavını çıqar',
'newpage'           => 'Yañı saife',
'talkpage'          => 'Saifeni muzakere et',
'specialpage'       => 'Mahsus Saife',
'personaltools'     => 'Şahsiy aletler',
'postcomment'       => 'Tefsir ekle',
'articlepage'       => 'Maqalege bar',
'talk'              => 'Muzakere',
'views'             => 'Körünişler',
'toolbox'           => 'Aletler',
'userpage'          => 'Qullanıcı saifesini köster',
'projectpage'       => 'Proekt saifesini köster',
'viewtalkpage'      => 'Muzakere saifesini köster',
'otherlanguages'    => 'Diger tillerde',
'redirectedfrom'    => '($1 saifesinden yollandı)',
'redirectpagesub'   => 'Yollama saifesi',
'lastmodifiedat'    => 'Bu saife soñki olaraq $2, $1 tarihında yañardı.', # $1 date, $2 time
'viewcount'         => 'Bu saife {{PLURAL:$1|1|$1}} defa irişilgen.',
'protectedpage'     => 'Qorçalavlı saife',
'jumpto'            => 'Bar ve:',
'jumptonavigation'  => 'qullan',
'jumptosearch'      => 'qıdır',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} aqqında',
'aboutpage'         => 'Project:Aqqında',
'bugreports'        => 'Hata raportları',
'bugreportspage'    => 'Project:Hata raportları',
'copyright'         => 'Malümat $1 binaen keçile bile.',
'copyrightpagename' => '{{SITENAME}} müellif aqları',
'copyrightpage'     => 'Project:Muellif aqları',
'currentevents'     => 'Ağımdaki vaqialar',
'currentevents-url' => 'Ağımdaki vaqialar',
'disclaimers'       => 'Cevapkârlıqnı qabul etmeyim',
'disclaimerpage'    => 'Project:Umumiy_Malümat_Muqavelesi',
'edithelp'          => 'Nasıl deñiştirilir?',
'edithelppage'      => 'Yardım:Saife nasıl deñiştirilir',
'faq'               => 'Sıq berilgen sualler',
'faqpage'           => 'Project:Sıq berilgen sualler',
'helppage'          => 'Yardım:İçindekiler',
'mainpage'          => 'Baş Saife',
'portal'            => 'Toplulıq portalı',
'portal-url'        => 'Project:Toplulıq portalı',
'privacy'           => 'Gizlilik esası',
'privacypage'       => 'Project:Gizlilik_esası',
'sitesupport'       => 'Bağışlar',
'sitesupport-url'   => 'Project:Bağış',

'badaccess' => 'İzin hatası',

'versionrequired' => 'Mediavikiniñ $1 versiyası kerek',

'ok'                      => 'Ok',
'retrievedfrom'           => '"$1"\'dan alındı',
'youhavenewmessages'      => 'Yañı <u>$1</u> bar. ($2)',
'newmessageslink'         => 'beyanatıñız',
'newmessagesdifflink'     => 'Bir evelki versiyağa köre eklengen yazı farqı',
'youhavenewmessagesmulti' => '$1-de yañı beyanatıñız bar.',
'editsection'             => 'deñiştir',
'editold'                 => 'deñiştir',
'editsectionhint'         => 'Deñiştirilgen bölüm: $1',
'toc'                     => 'Mevzu serlevaları',
'showtoc'                 => 'köster',
'hidetoc'                 => 'gizle',
'thisisdeleted'           => '$1 körmek ya da keri ketirmek istersiñmi?',
'viewdeleted'             => '$1 kör?',
'restorelink'             => 'yoq etilgen {{PLURAL:$1|1|$1}} deñişikligi',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Maqale',
'nstab-user'      => 'Qullanıcı saifesi',
'nstab-media'     => 'Media',
'nstab-special'   => 'Mahsus',
'nstab-project'   => 'Proekt saifesi',
'nstab-image'     => 'Fayl',
'nstab-mediawiki' => 'Beyanat',
'nstab-template'  => 'Şablon',
'nstab-help'      => 'Yardım',
'nstab-category'  => 'Kategoriya',

# Main script and global functions
'nosuchspecialpage' => 'Bu isimde bir mahsus saife yoq',
'nospecialpagetext' => "'''<big>Tapılmağan bir mahsus saifege kirdiñiz.</big>'''

Bar olğan bütün mahsus saifelerni [[Special:Specialpages]] saifesinde körip olursıñız.",

# General errors
'error'            => 'Hata',
'databaseerror'    => 'Malümat bazasınıñ hatası',
'dberrortext'      => 'Malümat bazasınıñ hatası.
Bu bir içki hatası ola bile.
"<tt>$2</tt>" funktsiyasından deñengen soñki sorğulama:
<blockquote><tt>$1</tt></blockquote>.
MySQL\'niñ raport etkeni hata "<tt>$3: $4</tt>".',
'cachederror'      => 'Aşağıdaki, siz istegen saifeniñ keşirlengen köpiyasıdır ve eskirgen ola bile.',
'readonly'         => 'Malümat bazası kilitlendi',
'readonlytext'     => 'Malümat bazası adiy baqım/âñıdan tamir etüv (restavratsiya) çalışmaları sebebinden, muvaqqat kiriş ve deñişiklik yapmağa qapatıldı. Qısqa müddet soñ normalge dönecektir.

Malümat bazasını kilitlegen öperatornıñ açıqlaması: $1',
'internalerror'    => 'İçki hata',
'unexpected'       => 'beklenmegen deger: "$1"="$2".',
'badarticleerror'  => 'Siz yapmağa istegen işlev keçersizdir.',
'cannotdelete'     => 'Belgilengen saife ya da körüniş yoq etilip olamadı. (başqa bir qullanıcı tarafından yoq etilgen ola bilir).',
'badtitle'         => 'Keçersiz serleva',
'perfdisabled'     => 'Afu etiñiz! Bu hususiyet, malümat bazasını qullanılamaycaq derecede yavaşlatqanı içün, muvaqqat qullanımdan çıqarıldı.',
'perfcached'       => 'Malümatlar daa evelceden azırlanğan ola bilir. Bu sebepten eskirgen ola bilir!',
'perfcachedts'     => 'Aşağıda saqlanğan malümat bulunmaqta, soñki yañaruv zamanı: $1.',
'viewsource'       => 'HTML kodunı köster',
'viewsourcefor'    => '$1 içün',
'editinginterface' => "'''Tenbi''': Mediaviki sistema beyanatılı bir saifeni deñiştirmektesiñiz. Bu saifedeki deñişiklikler qullanıcı interfeys körünişini diger qullanıcılar içün de deñiştirecektir.",

# Login and logout pages
'logouttitle'                => 'Oturımnı qapat',
'logouttext'                 => 'Oturımnı qapattıñız.
Şimdi kimligiñizni belgilemeksizniñ {{SITENAME}} saytını qullanmağa devam ete bilirsiñiz, 
ya da yañıdan oturım aça bilirsiñiz (ister aynı qullanıcı adınen, ister başqa bir qullanıcı adınen). 
Web brauzeriñiz keşini temizlegence bazı saifeler sanki alâ daa oturımıñız açıq eken 
kibi körünip olur.',
'welcomecreation'            => '== Hoş keldiñiz, $1! == 
Esabıñız açıldı. 
Bu saytnıñ ayarlarını (nastroykalarını) şahsıñızğa köre [[{{ns:special}}:Preferences|deñiştirmege]] unutmañız.',
'loginpagetitle'             => 'Sistemağa özüñizni tanıtıñ',
'yourname'                   => 'Qullanıcı adıñız',
'yourpassword'               => 'Paroliñiz',
'yourpasswordagain'          => 'Parolni yañıdan yaz',
'remembermypassword'         => 'Bu kompyuterde meni hatırla',
'yourdomainname'             => 'Domen adıñız',
'externaldberror'            => 'Sistemağa tanıtılğanda bir hata oldı. Bu tış esabıñızğa deñişiklik yapmağa aqqıñız olmayuvından meydanğa kele bile.',
'login'                      => 'Sistemağa kir',
'loginprompt'                => 'Oturım açmaq içün "cookies"ke izin bermelisiñiz.',
'userlogin'                  => 'Sistemağa kir / Yañı esap aç',
'logout'                     => 'Sistemadan çıq',
'userlogout'                 => 'Sistemadan çıq',
'notloggedin'                => 'Özüñizni sistemağa tanıtmadıñız.',
'nologin'                    => 'Daa esap açmadıñızmı? $1.',
'nologinlink'                => 'Qayd ol',
'createaccount'              => 'Yañı esap aç',
'gotaccount'                 => 'Daa evel esap açqan ediñizmi? $1.',
'gotaccountlink'             => 'Sistemağa özüñizni tanıtıñız',
'createaccountmail'          => 'e-mail vastasınen',
'badretype'                  => 'Siz belgilegen paroller bir birinen teñ degil.',
'userexists'                 => 'Belgilegeniñiz qullanıcı adlı azamız bar. Başqa bir qullanıcı adı belgileñiz.',
'youremail'                  => 'E-mail adresiñiz:',
'username'                   => 'Qullanıcı adı:',
'uid'                        => 'Qayd nomeri:',
'yourrealname'               => 'Kerçek adıñız:',
'yourlanguage'               => 'İnterfeys tili:',
'yourvariant'                => 'Til seçimi',
'yournick'                   => 'Siziñ lağabıñız (imzalarda kösterilecektir):',
'badsig'                     => 'Yañlış imza. HTML tegleriniñ doğrulığını baqınız.',
'prefs-help-realname'        => 'Adıñız (mecburiy degildir): Eger belgileseñiz, maqaledeki deñişikliklerin kimniñ yapqanını köstermek içün qullanılacaqtır.',
'loginerror'                 => 'Qullanıcı tanıma hatası',
'prefs-help-email'           => '*E-mail (mecburiy degildir) başqa qullanıcılarnıñ siznen bağ tutmalarını mümkün qıla. E-mail adresiñiz başqa qullanıcılarğa kösterilmeycek.',
'nocookiesnew'               => 'Qullanıcı esabı açılğan, faqat tanıtılmağan. {{SITENAME}} qullanıcılarnı tanıtmaq içün "cookies" qullanmaqta. Sizde bu funktsiya qapalı vaziyettedir. "Cookies" funktsiyasını açıp tekrar yañı adıñız ve paroliñiznen tırışıp baqınız.',
'nocookieslogin'             => '{{SITENAME}} "cookies" qullanmaqta. Sizde bu funktsiya qapalı vaziyettedir. "Cookies" funktsiyasını açıp tekrar tırışıp baqıñız.',
'noname'                     => 'Qullanıcı adını belgilemediñiz.',
'loginsuccesstitle'          => 'Kiriş yapıldı',
'loginsuccess'               => '$1 adınen çalışa bilesiñiz.',
'nosuchuser'                 => '$1 adlı qullanıcı yoq. Doğru yazğanıñıznı teşkeriñ ya da yañı qullanıcı esabını açmaq içün aşağıdaki formanı qullanıñız.',
'nosuchusershort'            => '$1 adlı qullanıcı tapılamadı. Adıñıznı doğru yazğanıñızdan emin oluñız.',
'nouserspecified'            => 'Qullanıcı adını belgilemek kereksiñiz.',
'wrongpassword'              => 'Kirgen/Belgilegen paroliñiz yañlıştır.',
'wrongpasswordempty'         => 'Boş parol kirmeñiz/belgilemeñiz.',
'passwordtooshort'           => 'Paroliñiz pek qısqa. Eñ az $1 ariften ve/â da raqamdan ibaret olmalı.',
'mailmypassword'             => 'Yañı parol yiber',
'passwordremindertitle'      => '{{grammar:genitive|{{SITENAME}}}} qullanıcınıñ parol hatırlatuvı',
'passwordremindertext'       => 'Birev (er alda bu sizsiñiz) $1 IP adresinden yañı qullanıcı {{grammar:genitive|{{SITENAME}}}} ($4) parolini talap etti. 
$2 qullanıcısı içün parol <code>$3</code> olaraq deñiştirildi.
Yañı parol <code>$3</code>.
Eger de yañı parol talap etmegen olsañız ya da eski paroliñizni bilseñiz bunı diqqatqa almayıp eski paroliñizni qullana bilesiñiz.',
'noemail'                    => '$1 adlı qullanıcı içün e-mail belgilenmedi.',
'passwordsent'               => 'Yañı parol e-mail yolunen qullanıcınıñ belgilegen $1 adresine yiberildi. Parolni alğan soñ sistemağa tekrar kiriş yapıñız.',
'eauthentsent'               => 'Keçici parol e-mail yolunen yañı qullanıcınıñ $1 adresine yiberildi. e-mail’ni tasdıqlamaq içün yapılacaqlar yiberilgen mektüpte añlatıla.',
'mailerror'                  => 'Poçta yiberilgende bir hata meydanğa keldi: $1',
'acct_creation_throttle_hit' => '$1 dane qullanıcı esapnı açtırğan aldasıñız. Daa ziyade açtıramazsıñız.',
'emailauthenticated'         => 'Poçta adresiñiz $1-nen teñeştirildi.',
'emailnotauthenticated'      => 'E-mail adresiñiz tasdıqlanmadı, vikiniñ e-mail ile bağlı funktsiyaları çalışmaycaqtır',
'noemailprefs'               => 'E-mail adresiñizni belgilemegeniñiz içün, vikiniñ e-mail ile bağlı funktsiyaları çalışmaycaqtır.',
'emailconfirmlink'           => 'E-mail adresiñizni tasdıqlañız',
'invalidemailaddress'        => 'Yazğan adresiñiz e-mail standartlarında olmağanı içün qabul etilmedi. Doğru adresni yazıñız ya da qutunı boş qaldırıñız',
'accountcreated'             => 'Esap açıldı',
'accountcreatedtext'         => '$1 içün bir qullanıcı esabı açıldı.',

# Edit page toolbar
'bold_sample'     => 'Qalın yazılış',
'bold_tip'        => 'Qalın yazılış',
'italic_sample'   => 'İtalik (kursiv) yazılış',
'italic_tip'      => 'İtalik (kursiv) yazılış',
'link_sample'     => 'Saifeniñ serlevası',
'link_tip'        => 'İçki bağlantı',
'extlink_sample'  => 'http://www.example.com saifeniñ serlevası',
'extlink_tip'     => 'Tış bağlantı (Adres ögüne http:// qoymağa unutmañız)',
'headline_sample' => 'Serleva yazısı',
'headline_tip'    => '2-nci seviye serlevası',
'math_sample'     => 'Bu yerge formulanı kirsetiñiz',
'math_tip'        => 'Riyaziy (matematik) formula (LaTeX formatında)',
'nowiki_sample'   => 'Serbest format metiniñizni mında yazıñız.',
'nowiki_tip'      => 'viki format etüvini ignor et',
'image_tip'       => 'Resim ekleme',
'media_tip'       => 'Media faylına bağlantı',
'sig_tip'         => 'İmzañız ve tarih',
'hr_tip'          => 'Gorizontal sızıq (pek sıq qullanmañız)',

# Edit pages
'summary'                   => 'Deñişiklik qısqa tarifi',
'subject'                   => 'Mevzu/serleva',
'minoredit'                 => 'Kiçik deñişiklik',
'watchthis'                 => 'Saifeni közet',
'savearticle'               => 'Saifeni saqla',
'preview'                   => 'Ög baquv',
'showpreview'               => 'Ög baquvnı köster',
'showlivepreview'           => 'Tez ög baquv',
'showdiff'                  => 'Deñişikliklerni köster',
'anoneditwarning'           => "'''Diqqat etiñiz''': Oturım açmağanıñızdan sebep siziñ IP adresiñiz deñişiklik tarihına yazılır.",
'missingsummary'            => "'''Hatırlatma.''' Deñiştirmeleriñizni qısqadan tarif etmediñiz. \"Saqla\" dögmesine tekrar basuv ile deñiştirmeleriñiz tefsirsiz saqlanacaqlar.",
'missingcommenttext'        => 'Lütfen aşağıda tefsir yazıñız.',
'blockedtitle'              => 'Qullanıcı blok etildi.',
'blockedoriginalsource'     => 'Aşağıda "$1" saifesiniñ metini buluna.',
'blockededitsource'         => "Aşağıda \"\$1\" saifesindeki '''yapqan deñiştirmeleriñizniñ''' metini buluna.",
'whitelistedittitle'        => 'Deñiştirmek içün oturım açmalısıñız',
'whitelistedittext'         => 'Saifeni deñiştirmek içün $1 kereksiñiz.',
'whitelistreadtitle'        => 'Oqumaq içün oturım açmaq kerek',
'whitelistreadtext'         => 'Bu saifelerni oqumaq içün [[Special:Userlogin|qayd olunmalısıñız]].',
'whitelistacctitle'         => 'Esap açma iziniñiz yoq',
'whitelistacctext'          => 'Bu vikide esap açmağa çare saibi olmaq içün [[Special:Userlogin|qayd olunmaq]] ve kelişikli aqlar saibi olmaq kereksiñiz.',
'confirmedittitle'          => 'Elektron poçta adresini tasdıqlamaq lâzimdir',
'confirmedittext'           => 'Saifeni deñiştirmeden evel elektron poçta adresiñizni tasdıqlamalısıñız. Lütfen [[Special:Preferences|ayarlar (nastroyka) saifesinde]] elektron poçta adresiñizni ekleñiz ve tasdıqlañız.',
'loginreqtitle'             => 'Oturım açmalısıñız',
'loginreqlink'              => 'oturım aç',
'loginreqpagetext'          => 'Başqa saifelerni baqmaq içün $1 borclusıñız.',
'accmailtitle'              => 'Parol yollandı',
'accmailtext'               => '$1 içün parol mında yollandı: $2.',
'newarticle'                => '(Yañı)',
'newarticletext'            => "Siz bu bağlantınen şimdilik yoq olğan maqlege avuştıñız. Yañı bir saife yaratmaq içün aşağıda 
bulunğan pencerege metin yazıñız (tafsilâtlı malümat almaq içün [[{{ns:help}}:Ârdım|malümat saifesine]] 
baqıñız). Bu saifege tesadufen avuşqan olsañız, brauzeriñizdeki '''keri''' dögmesine basıñız.",
'anontalkpagetext'          => "----'' Bu muzakere saifesi şimdilik qayd olunmağan ya da oturımını 
açmağan anonim qullanıcığa mensüptir. İdentefikatsiya içün IP adres işletile. Eger siz anonim 
qullanıcı olsañız ve sizge kelgen beyanlarnı yañlıştan kelgenini belleseñiz (bir IP adresten 
bir qaç qullanıcı faydalanabileler), lütfen artıq bunıñ kibi qarışıqlıq olmasın 
dep [[Slujebnaya:Userlogin|oturım açıñız]].''",
'noarticletext'             => 'Bu saife boştır. Bu serlevanı diger saifelerde [[{{ns:special}}:Search/{{PAGENAME}}|qıdırabilesiñiz]] ya da bu saifeni siz [{{fullurl:{{FULLPAGENAME}}|action=edit}} yazabilesiñiz].',
'clearyourcache'            => "'''İhtar:''' Ayarlarıñıznı (nastroykalarıñıznı) saqlağandan soñ, brauzeriñizniñ keşini de temizlemek kereksiñiz: '''Mozilla / Firefox / Safari:''' ''Shift'' basılı ekende saifeni yañıdan yükleyerek ya da ''Ctrl-Shift-R'' yaparaq (Apple Mac içün ''Cmd-Shift-R'');, '''IE:''' ''Ctrl-F5'', '''Konqueror:''' Tek seifeni yañıdan yükle klavişasına basaraq.",
'usercssjsyoucanpreview'    => "<strong>Tevsiye:</strong> Saifeni saqlamazdan evel <font style=\"border: 1px solid #0; background: #EEEEEE; padding : 2px\">'''ög baquvnı köster'''</font>'ge basaraq yapqan yañı saifeñizni közden keçiriñiz.",
'usercsspreview'            => "'''Tek test etesiñiz ya da ög baquv köresiñiz - qullanıcı CSS faylı şimdilik saqlanmadı.'''",
'userjspreview'             => "'''Tek test etesiñiz ya da ög baquv köresiñiz - qullanıcı JavaScript'i şimdilik saqlanmadı.'''",
'userinvalidcssjstitle'     => "''İhtar:''' \"\$1\" adınen bir tema yoqtır. tema-adı.css ve .js fayllarınıñ adları kiçik afir ile yazmaq kerek, yani User:Temel/'''M'''onobook.css degil, User:Temel/'''m'''onobook.css.",
'updated'                   => '(Yañardı)',
'note'                      => '<strong>İhtar:</strong>',
'previewnote'               => 'Bu ög baquvdır, metin alâ daa saqlanmağan!',
'previewconflict'           => 'Bu ög baquv yuqarı tarir penceresindeki metinniñ saqlanuvdan soñ olacaq körünişini aks ete.',
'session_fail_preview'      => '<strong> Server siz yapqan deñiştirmelerni sessiya identefikatorı 
coyulğanı sebebinden saqlap olamadı. Bu vaqtınca problemadır. Lütfen tekrar saqlap baqıñız. 
Bundan da soñ olıp çıqmasa, malümat lokal faylğa saqlañız da brauzeriñizni bir qapatıp 
açıñız.</strong>',
'session_fail_preview_html' => '<strong>Afu etiñiz! HTML sessiyanıñ malümatları ğayıp olğanı sebebinden siziñ deñiştirmeleriñizni qabul etmege imkân yoqtır.</strong>',
'editing'                   => '"$1" saifesini deñiştirmektesiñiz',
'editinguser'               => '"$1" saifesini deñiştirmektesiñiz',
'editingsection'            => '"$1" saifesinde bölüm deñiştirmektesiñiz',
'editingcomment'            => '$1 saifesine beyanat eklemektesiñiz.',
'editconflict'              => 'Deñişiklik zıt ketüvi: $1',
'explainconflict'           => 'Siz saifeni deñiştirgen vaqıtta başqa biri de deñişiklik yaptı.
Yuqarıdaki yazı saifeniñ şu anki alını köstermekte.
Siziñ deñişiklikleriñiz altqa kösterildi. Soñki deñişikleriñizni yazınıñ içine eklemek kerek olacaqsıñız. "Saifeni saqla"ğa basqanda <b>tek</b> yuqarıdaki yazı saqlanacaqtır. <br />',
'yourtext'                  => 'Siziñ metniñiz',
'storedversion'             => 'Saqlanğan metin',
'editingold'                => '<strong>DİQQAT: Saifeniñ eski bir versiyasında deñişiklik yapmaqtasıñız. 
Saqlağanıñızda bu tarihlı versiyadan künümizge qadar olğan deñişiklikler yoq olacaqtır.</strong>',
'yourdiff'                  => 'Qarşılaştırma',
'copyrightwarning'          => "<strong>Lütfen diqqat:</strong> {{SITENAME}} saytına qoşulğan bütün isseler <i>$2</i>
muqavelesi dairesindedir (tafsilât içün $1'ğa baqıñız).
Qoşqan isseñizniñ başqa insanlar tarafından acımasızca deñiştirilmesini ya da azat tarzda ve sıñırsızca başqa yerlerge dağıtılmasını istemeseñiz, isse qoşmañız.<br />
Ayrıca, mında isse qoşaraq, bu isseniñ özüñiz tarafından yazılğanına, ya da cemaatqa açıq bir menbadan ya da başqa bir azat menbadan köpirlengenine garantiya bergen olasıñız.<br />
<strong><center>MUELLİF AQQI İLE QORÇALANĞAN İÇ BİR ÇALIŞMANI MINDA EKLEMEÑİZ!</center></strong>",
'longpagewarning'           => '<strong>TENBİ: Bu saife $1 kilobayt büyükligindedir; bazı brauzerler deñişiklik yapqan vaqıtta 32kb ve üstü büyükliklerde problemalar yaşay bilir. Saifeni bölümlerge ayırmağa tırışıñız.</strong>',
'readonlywarning'           => '<strong>DİQQAT: Baqım sebebi ile malümat bazası şu ande kilitlidir. Bu sebepten deñişiklikleriñiz şu ande saqlanamamaqta. Yazğanlarıñıznı başqa bir editorge alıp saqlay bilir ve daa soñ tekrar mında ketirip saqlay bilirsiñiz</strong>',
'protectedpagewarning'      => 'TENBİ: Bu saife qorçalav altına alınğandır ve yalıñız administrator olğanlar tarafından deñiştirile bilir. Bu saifeni deñiştirgen vaqıtta lütfen [[Project:Qorçalav altına alınğan saife|qorçalavlı saife qaidelerini]] işletiñiz.',
'semiprotectedpagewarning'  => "'''Tenbi''': Bu saife tek registrirlengen qullanıcı olğanlar tarafından deñiştirile bilir.",
'templatesused'             => 'Bu saifede qullanılğan şablonlar:',

# History pages
'revhistory'          => 'Keçmiş versiyalar',
'nohistory'           => 'Bu saifeniñ keçmiş versiyası yoq.',
'revnotfound'         => 'Versiya tapılmadı',
'loadhist'            => 'Saife keçmişi yüklene',
'currentrev'          => 'Ağımdaki versiya',
'revisionasof'        => 'Saifeniñ $1 tarihındaki alı',
'previousrevision'    => '← Evelki alı',
'nextrevision'        => 'Soñraki alı →',
'currentrevisionlink' => 'eñ yañı alını köster',
'cur'                 => 'farq',
'next'                => 'soñraki',
'last'                => 'soñki',
'orig'                => 'asıl',
'histlegend'          => '(farq)  = ağımdaki versiya ile aradaki farq,
(soñki)  = evelki versiya ile aradaki farq, K= kiçik deñişiklik',
'deletedrev'          => '[yoq etildi]',
'histfirst'           => 'Eñ eski',
'histlast'            => 'Eñ yañı',
'historysize'         => '({{PLURAL:$1|1 bayt|$1 bayt}})',
'historyempty'        => '(boş)',

# Revision deletion
'rev-deleted-comment'       => '(tefsir yoq etildi)',
'rev-deleted-user'          => '(qullanıcı adı yoq etildi)',
'rev-delundel'              => 'köster/gizle',
'revisiondelete'            => 'Versiyalarnı yoq et/keri ketir',
'revdelete-hide-comment'    => 'Qısqa tarifni kösterme',
'revdelete-hide-user'       => 'Deñişiklikni yapqan qullanıcı adını/IP-ni gizle',
'revdelete-hide-restricted' => 'Bu sıñırlavlarnı administratorlar ve qullanıcılar içün işlet',
'revdelete-submit'          => 'Seçilgen versiyağa işlet',

# Diffs
'difference'              => '(Versiyalar arası farqlar)',
'lineno'                  => '$1. satır:',
'editcurrent'             => 'Saifeniñ şu anki versiyasını deñiştir',
'compareselectedversions' => 'Seçilgen versiyalarnı qarşılaştır',

# Search results
'searchresults'    => 'Qıdıruv neticeleri',
'searchresulttext' => '{{SITENAME}} içinde qıdıruv yapmaq hususında malümat almaq içün [[Project:Qıdıruv|"{{SITENAME}} içinde qıdıruv"]] saifesine baqa bilirsiñiz.',
'searchsubtitle'   => 'Qıdırılğan: "[[:$1]]" [[Special:Allpages/$1|&#x5B;Indeks&#x5D;]]',
'noexactmatch'     => "'''\"\$1\" serlevalı bir saife tapılamadı.''' Bu maqaleniñ yazılmasınıñ siz [[:\$1|başlata bilirsiñiz]].",
'titlematches'     => 'Maqale adı bir kele',
'notitlematches'   => 'İç bir serlevada tapılamadı',
'textmatches'      => 'Saife metni bir kele',
'notextmatches'    => 'İç bir saifede tapılamadı',
'prevn'            => 'evelki $1',
'nextn'            => 'soñraki $1',
'viewprevnext'     => '($1) ($2) ($3).',
'powersearch'      => 'Qıdır',
'powersearchtext'  => 'Qıdıruv yapılacaq fezalarnı seçiñiz :<br />
$1<br />
$2 yollanmalarnı cedvelle &nbsp; Qıdırılacaq: $3 $9',
'searchdisabled'   => '{{SITENAME}} saytında qıdıruv yapma vaqtınca toqtatıldı. Bu arada Google qullanaraq {{SITENAME}} içinde qıdıruv yapa bilirsiñiz. Qıdıruv saytlarında indekslemeleriniñ biraz eski qalğan ola bilecegini köz ögüne alıñız.',

# Preferences page
'preferences'           => 'Ayarlar (nastroykalar)',
'prefsnologin'          => 'Oturım açıq degil',
'qbsettings'            => 'Vızlı irişi sutun ayarları (nastroykaları)',
'changepassword'        => 'Parol deñiştir',
'skin'                  => 'Resimleme',
'math'                  => 'Riyaziy (matematik) simvollar',
'dateformat'            => 'Tarih kösterimi',
'datedefault'           => 'Optsiya yoq',
'datetime'              => 'Tarih ve saat',
'math_unknown_error'    => 'bilinmegen hata',
'prefs-personal'        => 'Qullanıcı malümatı',
'prefs-rc'              => 'Soñki deñişiklikler',
'prefs-watchlist'       => 'Közetüv cedveli',
'prefs-watchlist-days'  => 'Közetüv cedvelinde kösterilecek kün sayısı:',
'prefs-watchlist-edits' => 'Kenişletilgen közetüv cedvelinde kösterilecek deñişiklik sayısı:',
'prefs-misc'            => 'Diger ayarlar (nastroykalar)',
'saveprefs'             => 'Deñişikliklerni saqla',
'resetprefs'            => 'Ayarlarnı (Nastroykalarnı) ilk turuşına ketir',
'oldpassword'           => 'Eski parol',
'newpassword'           => 'Yañı parol',
'retypenew'             => 'Yañı parolnen tekrar kiriñiz',
'textboxsize'           => 'Saife yazuv fezası',
'rows'                  => 'Satır',
'columns'               => 'Sutun',
'searchresultshead'     => 'Qıdıruv',
'resultsperpage'        => 'Saifede kösterilecek tapılğan maqale sayısı',
'contextlines'          => 'Tapılğan maqale içün ayrılğan satır sayısı',
'contextchars'          => 'Satırdaki arif sayısı',
'recentchangescount'    => 'Soñki deñişiklikler saifesindeki maqale sayısı',
'savedprefs'            => 'Ayarlar (nastroykalar) saqlandı.',
'timezonelegend'        => 'Saat quşağı',
'timezonetext'          => 'Viki serveri (UTC/GMT) ile arañızdaki saat farqı. (Ukraina ve Türkiye içün +02:00)',
'localtime'             => 'Şu an siziñ saatıñız',
'timezoneoffset'        => 'Saat farqı',
'servertime'            => 'Viki serverinde şu anki saat',
'guesstimezone'         => 'Brauzeriñiz siziñ yeriñizge toldursın',
'allowemail'            => 'Diger qullanıcılar sizge e-mail ata bilsin',
'defaultns'             => 'Qıdıruvnı aşağıdaki seçili fezalarda yap.',
'default'               => 'original',
'files'                 => 'Fayllar',

# User rights
'userrights-lookup-user'   => 'Qullanıcı gruppalarnını idare et',
'userrights-user-editname' => 'Öz qullanıcı adıñıznen kiriñiz:',
'editusergroup'            => 'Qullanıcı gruppaları nizamla',
'userrights-editusergroup' => 'Qullanıcı gruppaları nizamla',

# Groups
'group'            => 'Gruppa:',
'group-bot'        => 'Botlar',
'group-sysop'      => 'Administratorlar',
'group-bureaucrat' => 'Bürokratlar',
'group-all'        => '(epsi)',

'group-sysop-member'      => 'Administrator',
'group-bureaucrat-member' => 'Bürokrat',

'grouppage-bot'        => 'Project:Botlar',
'grouppage-sysop'      => 'Project:Administratorlar',
'grouppage-bureaucrat' => 'Project:Administratorlar#Bürokratlar',

# Recent changes
'recentchanges'     => 'Soñki deñişiklikler',
'recentchangestext' => 'Yapılğan eñ soñki deñişikliklerni bu saifeden közetiñiz.',
'rcnote'            => '$3 (UTC) tarihında soñki <strong>{{PLURAL:$2|1|$2}}</strong> künde yapılğan <strong>{{PLURAL:$1|1|$1}}</strong> deñişiklik:',
'rcnotefrom'        => '<b>$2</b> tarihından itibaren yapılğan deñişiklikler aşağıdadır (eñ fazla <b>$1</b> dane maqale kösterilmekte).',
'rclistfrom'        => '$1 tarihından berli yapılğan deñişikliklerni köster',
'rcshowhideminor'   => 'kiçik deñişikliklerni $1',
'rcshowhidebots'    => 'botlarnı $1',
'rcshowhideliu'     => 'registrirlengen qullanıcılarnı $1',
'rcshowhideanons'   => 'anonim qullanıcılarnı $1',
'rcshowhidepatr'    => 'közetilgen deñişikliklerni $1',
'rcshowhidemine'    => 'deñişiklerimni $1',
'rclinks'           => 'Soñki $2 künde yapılğan soñki $1 deñişiklikni köster;<br /> $3',
'diff'              => 'farq',
'hist'              => 'keçmiş',
'hide'              => 'gizle',
'show'              => 'köster',
'minoreditletter'   => 'K',
'newpageletter'     => 'Y',

# Recent changes linked
'recentchangeslinked' => 'Bağlı deñişiklikler',

# Upload
'upload'                      => 'Fayl yükle',
'uploadbtn'                   => 'Fayl yükle',
'reupload'                    => 'Yañıdan yükle',
'reuploaddesc'                => 'Yükleme formasına keri qayt.',
'uploadnologin'               => 'Oturım açıq degil',
'uploadnologintext'           => 'Fayl yükleybilmek içün [[Special:Userlogin|oturım aç]]maq kereksiñiz.',
'upload_directory_read_only'  => 'Veb serverniñ ($1) papkasına fayllar saqlamağa aqları yoqtır.',
'uploaderror'                 => 'Yükleme hatası',
'uploadlog'                   => 'yükleme jurnalı',
'uploadlogpage'               => 'Fayl yükleme jurnalları',
'uploadlogpagetext'           => 'Aşağıda eñ soñki eklengen fayllarnıñ cedveli buluna.<ul></ul>',
'filename'                    => 'Fayl',
'filedesc'                    => 'Faylğa ait qısqa tarif',
'fileuploadsummary'           => 'Qısqa tarif:',
'filestatus'                  => 'Darqatuv şartları',
'filesource'                  => 'Menba',
'uploadedfiles'               => 'Yüklengen fayllar',
'ignorewarning'               => 'Tenbini ignor etip faylnı yükle.',
'ignorewarnings'              => 'Tenbini ignor et',
'illegalfilename'             => '"$1" faylınıñ isiminde serleva içün yasaqlı işaretler mevcüt. 
Lütfen fayl isimini deñiştirip yañıdan yüklep baqıñız.',
'badfilename'                 => 'Fayl isimi $1 olaraq deñiştirildi.',
'largefileserver'             => 'Bu faylnıñ uzunlığı serverde izin berilgenden büyükçedir.',
'emptyfile'                   => 'İhtimal ki, yüklengen fayl boş. İhtimallı sebep - fayl adlandıruv 
hatasıdır. Lütfen tamam bu faylnı yüklemege isteycek ekeniñizni teşkeriñiz.',
'fileexists'                  => 'Bu isimde bir fayl mevcüttir. Lütfen, eger siz deñiştirmekten emin 
olmasañız başta $1 faylına köz taşlañız.',
'fileexists-forbidden'        => 'Bu isimde bir fayl mevcüttir. Lütfen keri qaytıñız, fayl isimini 
deñiştirip yañıdan yükleñiz. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Bu isimde fayllar umumiy tutulğan yerinde bir fayl mevcüttir. 
Lütfen keri qaytıñız, fayl isimini deñiştirip yañıdan yükleñiz. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Yüklenüv becerildi',
'uploadwarning'               => 'Tenbi',
'savefile'                    => 'Faylnı saqla',
'uploadedimage'               => 'Yüklengen: "[[$1]]"',
'uploaddisabled'              => 'Bu ande yükleme yasaqlıdır. Birazdan soñ bir daa yüklep baqıñız.',
'uploaddisabledtext'          => 'Bu viki saytında fayl yükleme yasaqlıdır.',
'uploadscripted'              => 'Bu faylda brauzer tarafından yañlışnen işlenebilir HTML-kod ya da skript mevcüt.',
'uploadcorrupt'               => 'Bu fayl ya zararlandı, ya da yañlış rasşireniyeli. Lütfen faylnı teşkerip yañıdan yüklep baqıñız.',
'uploadvirus'                 => 'Bu fayl viruslıdır! $1 baqıñız',
'sourcefilename'              => 'Yüklemek istegeniñiz fayl',
'destfilename'                => '{{SITENAME}} saytındaki fayl adı',
'watchthisupload'             => 'Bu faylnı közetüv cedveline kirset',
'filewasdeleted'              => 'Bu isimde bir fayl mevcüt edi, amma yoq etilgen edi. Lütfen tekrar yüklemeden evel $1 teşkeriñiz.',

'license'            => 'Litsenzirleme',
'nolicense'          => 'Yoq',
'upload_source_url'  => ' (doğru, püblik tarzda kirmege musaadeli internet adres)',
'upload_source_file' => ' (kompyuteriñizdeki fayl)',

# Image list
'imagelist'                 => 'Resim cedveli',
'ilsubmit'                  => 'Qıdır',
'showlast'                  => 'Eñ soñki $1 faylnı $2 köster.',
'byname'                    => 'elifbe sırasınen',
'bydate'                    => 'hronologik sıranen',
'bysize'                    => 'büyüklik sırasınen',
'imgdelete'                 => 'yoq et',
'imgdesc'                   => 'tanıtuv',
'imagelinks'                => 'Qullanılğanı saifeler',
'linkstoimage'              => 'Bu suret faylına bağlantı olğan saifeler:',
'nolinkstoimage'            => 'Bu suret faylına bağlanğan saife yoq.',
'sharedupload'              => 'Bu fayl ortaq fezağa yüklengen ve diger proektlerde de qullanılğan bir fayl ola bilir.',
'shareduploadwiki-linktext' => 'fayl açıqlama saifesi',
'noimage'                   => 'Bu isimde fayl yoq. Siz $1.',
'noimage-linktext'          => 'yükley bilirsiñiz',
'uploadnewversion-linktext' => 'Faylnıñ yañısını yükleñiz',

# MIME search
'mimesearch' => 'MIME qıdıruvı',
'mimetype'   => 'MIME tipi:',
'download'   => 'yükle',

# Unwatched pages
'unwatchedpages' => 'Közetilmegen saifeler',

# List redirects
'listredirects' => 'Yollamalarnı cedvelge çek',

# Unused templates
'unusedtemplates'     => 'Qullanılmağan şablonlar',
'unusedtemplatestext' => 'Bu saife "şablon" isim fezasında bulunğan ve diger saifelerge eklenmegen şablonlarnı köstermekte. Şablonlarğa olğan diger bağlantılarnı da teşkermeden yoq etmeñiz.',
'unusedtemplateswlh'  => 'diger bağlantılar',

# Random page
'randompage'         => 'Tesadüfiy saife',
'randompage-nopages' => 'Bu isim fezasında iç bir saife yoq.',

# Random redirect
'randomredirect'         => 'Tesadüfiy yollama saifesi',
'randomredirect-nopages' => 'Bu isim fezasında iç bir yollama saifesi yoq.',

# Statistics
'statistics'    => 'Statistika',
'sitestats'     => '{{SITENAME}} statistikası',
'userstats'     => 'Qullanıcı statistikası',
'sitestatstext' => "{{SITENAME}} saytında şu ande '''{{PLURAL:\$2|1 keçerli saife|\$2 keçerli saife}}''' mevcüttir.

Bu cümleden; \"yollama\", \"muzakere\", \"resim\", \"qullanıcı\", \"yardım\", \"{{SITENAME}}\", \"şablon\" isim fezalarındakiler ve içki bağlantısız maqaleler kirsetilmedi. Keçerli maqale sayısına bu saifelerniñ sayısı eklengende ise toplam '''\$1''' saife mevcüttir.

\$8 dane fayl yüklendi.

Sayt qurulğanından bu künge qadar toplam '''\$4''' saife deñişikligi ve saife başına tahminen '''\$5''' isse qoşuldı.

Toplam saife kösterilme sayısı '''\$3''', deñişiklik başına kösterme sayısı '''\$6''' oldı.

Şu andeki [http://meta.wikimedia.org/wiki/Help:Job_queue iş sırası] sayısı '''\$7'''.",
'userstatstext' => "Şu ande '''{{PLURAL:$1|1|$1}}''' registrirlengen qullanıcımız bar. Bunlardan '''{{PLURAL:$2|1|$2}}''' (ya da '''$4%''') danesi - $5.",

'disambiguations'     => 'Çoq manalı terminler saifeleri',
'disambiguationspage' => 'Şablon:Çoq manalı',

'doubleredirects'     => 'Yollamağa olğan yollamalar',
'doubleredirectstext' => 'Er satırda, ekinci yollama metniniñ ilk satırınıñ (umumen ekinci yollamanıñ da işaret etmek kerek olğanı "asıl" maqsatnıñ) yanında ilk ve ekinci yollamağa bağlantılar bar.',

'brokenredirects'     => 'Bar olmağan maqalege yapılğan yollamalar',
'brokenredirectstext' => 'Aşağıdki yollama, mevcüt olmağan bir saifege işaret ete.',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|1 bayt|$1 bayt}}',
'ncategories'             => '{{PLURAL:$1|1 kategoriya|$1 kategoriya}}',
'nlinks'                  => '{{PLURAL:$1|1 bağlantı|$1 bağlantı}}',
'nmembers'                => '{{PLURAL:$1|1 aza|$1 aza}}',
'nrevisions'              => '{{PLURAL:$1|1 közden keçirüv|$1 közden keçirüv}}',
'nviews'                  => '{{PLURAL:$1|1 körünüv|$1 körünüv}}',
'lonelypages'             => 'Özüne iç bağlantı olmağan saifeler',
'uncategorizedpages'      => 'Er angi bir kategoriyada olmağan saifeler',
'uncategorizedcategories' => 'Er angi bir kategoriyada olmağan kategoriyalar',
'uncategorizedimages'     => 'Er angi bir kategoriyada olmağan resimler',
'unusedcategories'        => 'Qullanılmağan kategoriyalar',
'unusedimages'            => 'Qullanılmağan resimler',
'popularpages'            => 'Populâr saifeler',
'wantedcategories'        => 'İstenilgen kategoriyalar',
'wantedpages'             => 'İstenilgen saifeler',
'mostlinked'              => 'Özüne eñ ziyade bağlantı berilgen saifeler',
'mostlinkedcategories'    => 'Eñ çoq maqalege saip kategoriyalar',
'mostcategories'          => 'Eñ ziyade kategoriyağa bağlanğan saifeler',
'mostimages'              => 'Eñ çoq qullanılğan resimler',
'mostrevisions'           => 'Eñ çoq deñişiklikke oğrağan saifeler',
'allpages'                => 'Bütün saifeler',
'shortpages'              => 'Qısqa saifeler',
'longpages'               => 'Uzun saifeler',
'deadendpages'            => 'Başqa saifelerge bağlantısı olmağan saifeler',
'listusers'               => 'Qullanıcı cedveli',
'specialpages'            => 'Mahsus saifeler',
'spheading'               => 'Bütün qullanıcılarnı meraqlandıra bilecek mahsus saifeler',
'restrictedpheading'      => 'Administratorlarnıñ tesirlerinen bağlı mahsus saifeler',
'rclsub'                  => '("$1" saifesine bağlanğan saifelerde)',
'newpages'                => 'Yañı saifeler',
'ancientpages'            => 'Eñ soñki deñişiklik tarihı eñ eski olğan maqaleler',
'intl'                    => 'Tiller arası bağlantılar',
'move'                    => 'Adını deñiştir',
'movethispage'            => 'Saifeni taşı',

# Book sources
'booksources'               => 'Kitaplar menbası',
'booksources-search-legend' => 'Kitaplar menbasını qıdıruv',
'booksources-go'            => 'Qıdır',

'categoriespagetext' => 'Vikide aşağıdaki kategoriyalar mevcüttir.',
'data'               => 'Malümatlar',
'userrights'         => 'Qullanıcı aqları idare etüvi.',
'groups'             => 'Qullanıcı gruppaları',
'alphaindexline'     => '$1-den $2-ge',
'version'            => 'Versiya',

# Special:Log
'specialloguserlabel'  => 'Qullanıcı:',
'speciallogtitlelabel' => 'Serleva:',
'log'                  => 'Jurnallar',
'log-search-legend'    => 'Jurnalnı qıdıruv',
'log-search-submit'    => 'Qıdır',
'logempty'             => 'Jurnalda bir kelgen malümat yoq.',

# Special:Allpages
'nextpage'          => 'Soñraki saife ($1)',
'allpagesfrom'      => 'Cedvelge çekmege başlanılacaq arifler:',
'allarticles'       => 'Bütün maqaleler',
'allinnamespace'    => 'Bütün saifeler ($1 saifeleri)',
'allnotinnamespace' => 'Bütün saifeler ($1 isim fezasında olmağanlar)',
'allpagesprev'      => 'Evelki',
'allpagesnext'      => 'Soñraki saife',
'allpagessubmit'    => 'Ketir',
'allpagesprefix'    => 'Yazğan ariflernen başlağan saifelerni köster:',

# E-mail user
'mailnologin'     => 'Mektüp yollanacaq adresi yoqtır',
'mailnologintext' => 'Diger qullanıcılarğa elektron mektüpler yollap olmaq içün [[Special:Userlogin|oturım açmalısıñız]] ve [[Special:Preferences|ayarlarıñızda (nastroykalarıñızda)]] mevcüt olğan elektron poçta adresiniñ saibi olmalısıñız.',
'emailuser'       => 'Qullanıcığa mektüp',
'emailpage'       => 'Qullanıcığa elektron mektüp yolla',
'emailpagetext'   => 'İşbu qullanıcı öz ayarlarında (nastroykalarında) mevcüt olğan elektron poçta adresini yazğan olsa, aşağıdaki formanı toldurıp oña beyan yollap olursıñız.
Öz ayarlarıñızda (nastroykalarıñızda) yazğan elektron adresiñiz mektüpniñ "Kimden" yazısı yerine yazılacaq, bunıñ içün mektüp alıcı da sizge cevap olaraq mektüp yollap olur.',
'usermailererror' => 'Elektron poçta beyanı yollanğan vaqıtta hata olıp çıqtı',
'noemailtitle'    => 'Elektron poçta adresi yoqtır',
'noemailtext'     => 'Bu qullanıcı ya mevcüt olğan elektron poçta adresini yazmağan, ya da başqa qullanıcılardan mektüp aluvdan vazgeçken.',
'emailfrom'       => 'Kimden',
'emailto'         => 'Kimge',
'emailsubject'    => 'Mektüp mevzusı',
'emailmessage'    => 'Mektüp metini',
'emailsend'       => 'Yolla',
'emailsent'       => 'Mektüp yollandı',
'emailsenttext'   => 'Siziñ elektron beyanıñız yollandı',

# Watchlist
'watchlist'            => 'Közetüv cedveli',
'mywatchlist'          => 'Közetüv cedveli',
'watchlistfor'         => "('''$1'''degen qullanıcınıñ)",
'nowatchlist'          => 'Siziñ közetüv cedveliñiz boştır.',
'watchlistanontext'    => 'Közetüv cedvelini baqmaq ya da tarir  etmek içün $1 borclusıñız.',
'watchnologin'         => 'Oturım açmaq kerek',
'watchnologintext'     => 'Öz közetüv cedveliñizni deñiştirmek içün [[Special:Userlogin|oturım açıñız]]',
'addedwatch'           => 'Közetüv cedveline kirsetmek',
'addedwatchtext'       => '"[[:$1]]" saifesi [[{{ns:special}}:Watchlist|kozetüv cevdeliñizge]] kirsetildi. Bu saifedeki ve onıñnen bağlı saifelerdeki olacaq deñişiklikler bu cedvelde belgilenecek, em de olar közge çarpması içün [[{{ns:special}}:Recentchanges|âñı deñişiklik cedveli]] bulunğan saifede qalın olaraq kösterilir.

Birazdan soñ közetüv cedveliñizden bir de bir saifeni yoq etmege isteseñiz de, saifeniñ yuqarısındaki sol tarafta "közetme" dögmesine basıñız.',
'removedwatch'         => 'Közetüv cedvelinden yoq et',
'removedwatchtext'     => '"[[:$1]]" saifesi közetüv cedveliñizden yoq etildi.',
'watch'                => 'Közet',
'watchthispage'        => 'Bu saifeni közet',
'unwatch'              => 'Saifeni közetme',
'unwatchthispage'      => 'Saife közetmekni toqtat',
'notanarticle'         => 'Maqale degil',
'watchnochange'        => 'Kösterilgen zaman aralığında közetüv cedveliñizdeki saifelerniñ iç biri deñiştirilmegen.',
'wlheader-enotif'      => '* E-mail ile haber berüv açıldı.',
'wlheader-showupdated' => "* Soñki ziyaretiñizden soñraki saife deñişiklikleri '''qalın''' olaraq kösterildi.",
'watchmethod-recent'   => 'soñki deñişiklikler arasında közetken saifeleriñiz qıdırıla',
'watchmethod-list'     => 'közetüv cedvelindeki saifeler teşkerile',
'watchlistcontains'    => 'Siziñ közetüv cedveliñizde {{PLURAL:$1|1|$1}} saife mevcüttir.',
'iteminvalidname'      => '"$1" saifesi munasebetinen problema olıp çıqtı, elverişli olmağan isimdir…',
'wlnote'               => 'Aşağıda soñki <strong>{{PLURAL:$2|1|$2}}</strong> saat içinde yapılğan soñki {{PLURAL:$1|1|$1}} deñişiklik kösterile.',
'wlshowlast'           => 'Soñki $2 kün $1 saat içün $3 köster',

'enotif_mailer'      => '{{SITENAME}} poçta vastasınen haber bergen hızmet',
'enotif_reset'       => 'Cümle saifelerni baqılğan olaraq işaretle',
'enotif_newpagetext' => 'Bu yañı bir saifedir.',
'changed'            => 'deñiştirildi',
'created'            => 'yaratıldı',
'enotif_subject'     => '"{{SITENAME}}" $PAGETITLE saifesi $PAGEEDITOR qullanıcı tarafından $CHANGEDORCREATED',
'enotif_lastvisited' => 'Soñki ziyaretiñizden berli yapılğan deñişikliklerni bilmek içün $1 baqıñız.',
'enotif_body'        => 'Sayğılı $WATCHINGUSERNAME,


{{SITENAME}} saytındaki $PAGETITLE serlevalı saife $PAGEEDITDATE tarihında $PAGEEDITOR tarafından $CHANGEDORCREATED. Keçerli versiyağa $PAGETITLE_URL adresinden yetişe bilesiñiz.

$NEWPAGE

Açıqlaması: $PAGESUMMARY $PAGEMINOREDIT

Saifeni deñiştirgen qullanıcınıñ irişim malümatı:
e-mail: $PAGEEDITOR_EMAIL
Viki: $PAGEEDITOR_WIKI

Bahsı keçken saifeni siz ziyaret etmegen müddet içinde saifenen bağlı başqa deñişiklik tenbisi yollanmaycaqtır. Tenbi ayarlarını (nastroykalarını) közetüv cedveliñizdeki bütün saifeler içün deñiştire bilirsiñiz.

{{SITENAME}} tenbi sisteması.

--
Ayarlarnı (nastroykalarnı) deñiştirmek içün:
{{fullurl:Special:Watchlist/edit}}

Yardım ve teklifler içün:
{{fullurl:Help:Contents}}',

# Delete/protect/revert
'deletepage'                  => 'Saifeni yoq et',
'confirm'                     => 'Tasdıqla',
'excontent'                   => "eski metin: '$1'",
'excontentauthor'             => "eski metin: '$1' ('$2' isse qoşqan tek bir qullanıcı)",
'exbeforeblank'               => "Yoq etilmegen evelki metin: '$1'",
'exblank'                     => 'saife metini boş',
'confirmdelete'               => 'Yoq etüv işlemini tasdıqla',
'deletesub'                   => '("$1" yoq etile)',
'historywarning'              => 'Tenbi: Siz yoq etmek üzre olğan saifeniñ keçmişi bardır:',
'confirmdeletetext'           => "Bir saifeni ya da resimni bütün keçmişi ile birlikte malümat bazasından qalıcı olaraq yoq etmek üzresiñiz.
Lütfen neticelerini añlağanıñıznı, [[Special:Whatlinkshere/{{FULLPAGENAME}}|saifege bağlantılarını]] teşkergenden soñ ve [[Project:Yoq etüv politikası]]'na uyğunlığını diqqatqa alaraq, bunı yapmağa istegeniñizni tasdıqlañız.",
'actioncomplete'              => 'İşlem tamamlandı.',
'deletedtext'                 => '"$1" yoq etildi.
yaqın zamanda yoq etilgenlerni körmek içün: $2.',
'deletedarticle'              => '"$1" yoq etildi',
'dellogpage'                  => 'Yoq etüv jurnalları',
'dellogpagetext'              => 'Aşağıdaki cedvel soñki yoq etüv jurnallarıdır.',
'deletionlog'                 => 'yoq etüv jurnalları',
'reverted'                    => 'Evelki versiya keri ketirildi',
'deletecomment'               => 'Yoq etüv sebebi',
'rollback'                    => 'deñişikliklerni keri al',
'rollback_short'              => 'keri al',
'rollbacklink'                => 'eski alına ketir',
'rollbackfailed'              => 'keri aluv işlemi muvafaqiyetsiz',
'cantrollback'                => 'Deñişiklikler keri alınamay, soñki deñiştirgen kişi saifeniñ tek bir müellifidir',
'editcomment'                 => 'Deñiştirme izaatı: "<i>$1</i>" edi.', # only shown if there is an edit comment
'revertpage'                  => '[[User:$2|$2]] tarafından yapılğan deñişiklikler keri alınaraq, [[User:$1|$1]] tarafından deñiştirilgen evelki versiya keri ketirildi.',
'protectlogpage'              => 'Qorçalav jurnalı',
'protectlogtext'              => 'Qorçalavğa aluv/çıqaruv ile bağlı deñişikliklerni körmektesiñiz.
Daa fazla malümat içün [[Project:Qorçalav altına alınğan saife]] saifesine baqa bilirsiñiz.',
'protectedarticle'            => '"[[$1]]" qorçalav altına alındı',
'unprotectedarticle'          => 'qorçalav çıqarlıdı: "[[$1]]"',
'confirmprotect'              => 'Qorçalavnı tasdıqla',
'protectcomment'              => 'Qorçalav altına aluv sebebi',
'unprotectsub'                => '(qorçalav çıqarılır "$1")',
'protect-text'                => '[[$1]] saifesiniñ qorçalav turuşını mından köre bilir ve deñiştire bilirsiñiz. Lütfen [[Project:Qorçalav politikası|qorçalav politikasına]] uyğun areket etkeniñizden emin oluñız.',
'protect-default'             => '(standart)',
'protect-level-autoconfirmed' => 'registrirlenmegen deñiştirmesin',
'protect-level-sysop'         => 'tek administratorlar',
'protect-expiring'            => 'bite: $1 (UTC)',
'restriction-type'            => 'Ruhseti:',
'restriction-level'           => 'Ruhset seviyesi:',
'minimum-size'                => 'Minimal büyüklik',
'maximum-size'                => 'Maksimal büyüklik',
'pagesize'                    => '(bayt)',

# Restrictions (nouns)
'restriction-edit' => 'Deñiştir',
'restriction-move' => 'Adını deñiştir',

# Undelete
'undelete'           => 'Yoq etilgen saifelerni köster',
'undeletepage'       => 'Saifeniñ yoq etilgen versiyalarına köz at ve keri ketir.',
'viewdeletedpage'    => 'Yoq etilgen saifelerge baq',
'undeletebtn'        => 'Keri ketir!',
'undeletereset'      => 'Vazgeç',
'undeletecomment'    => 'Neden:',
'undeletedarticle'   => '"$1" keri ketirildi.',
'undeletedrevisions' => 'Toplam {{PLURAL:$1|1 qayd|$1 qayd}} keri ketirildi.',

# Namespace form on various pages
'namespace'      => 'İsim fezası:',
'invert'         => 'Seçili harıçındakilerni köster',
'blanknamespace' => '(Baş)',

# Contributions
'contributions' => 'Qullanıcınıñ isseleri',
'mycontris'     => 'İsselerim',
'contribsub2'   => '$1 ($2)',
'nocontribs'    => 'Bu kriteriylerge uyğan deñişiklik tapılamadı',
'uctop'         => '(soñki)',

'sp-contributions-newest'      => 'Eñ yañı',
'sp-contributions-oldest'      => 'Eñ eski',
'sp-contributions-newer'       => 'Soñraki $1',
'sp-contributions-older'       => 'Evelki $1',
'sp-contributions-newbies-sub' => 'Yañı qullanıcılar içün',

# What links here
'whatlinkshere' => 'Saifege bağlantılar',
'linklistsub'   => '(Bağlantı cedveli)',
'linkshere'     => "Bu saifeler '''[[:$1]]''' saifesine bağlantısı olğan:",
'nolinkshere'   => "'''[[:$1]]''' saifesine bağlanğan saife yoq.",
'isredirect'    => 'Yollama saifesi',
'istemplate'    => 'ekleme',

# Block/unblock
'blockip'            => 'Bu IP adresinden irişimni ban et',
'blockiptext'        => 'Aşağıdaki formanı qullanaraq belli bir IP-niñ ya da qullanıcınıñ irişimini ban ete bilirsiñiz. Bu tek vandalizmni ban etmek içün ve [[{{MediaWiki:policy-url}}|qaidelerge]] uyğun olaraq yapılmalı. Aşağığa mıtlaqa ban etüv ile bağlı bir açıqlama yazıñız. (meselâ: Şu saifelerde vandalizm yaptı).',
'ipaddress'          => 'IP adresi',
'ipadressorusername' => 'IP adresi ya da qullanıcı adı',
'ipbexpiry'          => 'Bitiş müddeti',
'ipbreason'          => 'Sebep',
'ipbsubmit'          => 'Bu qullanıcını ban et',
'ipbother'           => 'Farqlı zaman',
'ipboptions'         => '15 daqqa:15 minutes,1 saat:1 hour,3 saat:3 hours,24 saat:24 hours,48 saat:48 hours,1 afta:1 week,1 ay:1 month,muddetsiz:infinite',
'ipbotheroption'     => 'farqlı',
'badipaddress'       => 'Keçersiz IP adresi',
'blockipsuccesssub'  => 'IP adresni ban etüv işlevi muvafaqiyetli oldı',
'blockipsuccesstext' => '"$1" ban etildi.
<br />[[Special:Ipblocklist|IP adresi ban etilgenler]] cedveline baqıñız .',
'unblockip'          => 'Qullanıcınıñ ban etüvini çıqar',
'ipusubmit'          => 'Bu adresniñ ban etüvini çıqar',
'ipblocklist'        => 'İrişimi toqtatılğan qullanıcılar ve IP adresleri cedveli',
'blocklistline'      => '$1, $2 blok etti: $3 ($4)',
'infiniteblock'      => 'müddetsiz',
'expiringblock'      => '$1 tarihında tola',
'blocklink'          => 'ban et',
'unblocklink'        => 'ban etüvni çıqar',
'contribslink'       => 'İsseler',
'autoblocker'        => 'Avtomatik olaraq ban ettiñiz çünki yaqın zamanda IP adresiñiz "[[User:$1|$1]]" qullanıcısı tarafından qullanıldı. $1 isimli qullanıcınıñ ban etilüvi içün berilgen sebep: "\'\'\'$2\'\'\'"',
'blocklogpage'       => 'İrişim ban etüv jurnalları',
'blocklogentry'      => '"[[$1]]" irişimi $2 $3 toqtatıldı. Sebep',
'blocklogtext'       => 'Mında qullanıcı irişimine yönelik ban etüv ya da ban etüv çıqaruv jurnalları cedvellenmekte. Avtomatik IP adresi ban etüvleri cedvelge kirsetilmedi. Şu ande irişimi toqtatılğan qullanıcılarnı [[Special:Ipblocklist|IP ban etüv cedveli]] saifesinden köre bilirsiñiz.',
'unblocklogentry'    => '$1 qullanıcınıñ ban etüvi çıqarıldı',
'ipb_expiry_invalid' => 'Keçersiz bitiş zamanı.',
'ip_range_invalid'   => 'Keçersiz IP aralığı.',

# Developer tools
'lockdb'  => 'Malümat bazası kilitli',
'lockbtn' => 'Malümat bazası kilitli',

# Move page
'movepage'                => 'İsim deñişikligi',
'movepagetext'            => "Aşağıdaki formanı qullanıp saifeniñ isimini deñiştirirsiñiz. Bunıñnen beraber deñişiklik jurnalını da yañı isimge avuştırırsıñız.
Eski isim yañı isimge yöneltici olur.
Eski isimge doğru olğan bağlantılar olğanı kibi qalır (lütfen [[Special:DoubleRedirects|çift]] ve [[Special:BrokenRedirects|keçersiz]] yönelticiler mevcüt olğanını teşkeriñiz).
Bağlantılar endiden berli eskisi kibi çalışmasından emin olmalısıñız.

Yañı isimde bir isim zaten mevcüt olsa, isim deñişikligi '''yapılmaycaq''\, ancaq mevcüt olğan saife yöneltici ya da boş olsa isim deñişikligi mümkün olacaqtır. Bu demek ki, saife isimini yañlıştan deñiştirgen olsañız deminki isimini keri qaytarabilesiñiz, amma mevcüt olğan saifeni tesadufen yoq etalmaysıñız.

'''TENBİ!'''
İsim deñiştirüv ''populâr'' saifeler içün büyük deñişmelerge sebep olabilir. Lütfen deñişiklikni yapmadan evel olabileceklerni köz ögüne alıñız.",
'movepagetalktext'        => "Qoşulğan muzakere saifesiniñ de (mevcüt olsa)
isimi avtomatik tarzda deñiştirilecek. '''Müstesnalar:'''

*Aynı bu isimde boş olmağan bir muzakere saifesi endi mevcüttir;
*Aşağıdaki boşluqqa işaret qoymadıñız.

Böyle allarda, kerek olsa, saifelerni qolnen taşımağa ya da birleştirmege mecbur olursıñız.",
'movearticle'             => 'Eski isim',
'movenologin'             => 'Oturım açmadıñız',
'movenologintext'         => 'Saifeniñ adını deñiştirebilmek içün [[Special:Userlogin|oturım açıñız]].',
'newtitle'                => 'Yañı isim',
'movepagebtn'             => 'İsimini deñiştir',
'pagemovedsub'            => 'İsim deñişikligi tamamlandı',
'articleexists'           => 'Bu isimde bir saife endi mevcüt ya da siz yazğan isim izinli degil.
Lütfen başqa bir isim seçip yazıñız.',
'talkexists'              => "'''Saifeniñ adı deñiştirildi, amma muzakere saifesiniñ
adını deñiştirmege mümkünlik yoqtır, çünki aynı bu adda bir saife endi mevcüttir.
Lütfen bularnı qolnen birleştiriñiz.'''",
'movedto'                 => 'adı deñiştirildi:',
'movetalk'                => 'Muzakere saifesiniñ adını deñiştir.',
'talkpagemoved'           => 'Bağlı muzakere saifesiniñ de adı deñiştirildi.',
'talkpagenotmoved'        => 'Bağlı muzakere seifesiniñ adı <strong>deñiştirilmedi</strong>.',
'1movedto2'               => '"[[$1]]" saifesiniñ adı "[[$2]]" olaraq deñiştirildi',
'movelogpage'             => 'İsim deñişikligi jurnalları',
'movelogpagetext'         => 'Aşağıda bulunğan cedvel adı deñiştirilgen saifelerni köstere',
'movereason'              => 'Sebep',
'revertmove'              => 'Kerige al',
'delete_and_move'         => 'Yoq et ve adını deñiştir',
'delete_and_move_text'    => '==Yoq etmek lâzimdir==

[[$1|"$1"]] maqalesi endi mevcüt. Adını deñiştirebilmek içün onı yoq etmege isteysiñizmi?',
'delete_and_move_confirm' => 'Ebet,bu saifeni yoq et',
'delete_and_move_reason'  => 'İsim deñiştire bilmek içün yoq etildi',
'selfmove'                => 'Bu saifeniñ adını deñiştirmege imkân yoqtır, çünki asıl ile yañı adları bir kele.',
'immobile_namespace'      => 'Bu saifeniñ adını deñiştirmege imkân yoqtır, çünki yañı ya da eksi adında rezerv etilgen yardımcı söz bardır.',

# Export
'export' => 'Saife saqla',

# Namespace 8 related
'allmessages'         => 'Sistema beyanatları',
'allmessagesname'     => 'İsim',
'allmessagesdefault'  => 'Öriginal metin',
'allmessagescurrent'  => 'Qullanımdaki metin',
'allmessagestext'     => 'Bu cedvel Mediavikide mevcüt olğan bütün terminlerniñ cedvelidir',
'allmessagesfilter'   => 'Metin ayrıştırıcı filtrı:',
'allmessagesmodified' => 'Tek deñiştirilgenlerni köster',

# Thumbnails
'thumbnail-more' => 'Buyut',
'missingimage'   => '<b>Tapılmağan resim</b><br /><i>$1</i>',
'filemissing'    => 'Fayl tapılmadı',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Şahsiy saifem',
'tooltip-pt-anonuserpage'         => 'IP adresim içün qullanıcı saifesi',
'tooltip-pt-mytalk'               => 'Muzakere saifesim',
'tooltip-pt-anontalk'             => 'Bu IP adresinden yapılğan deñişikliklerni muzakere et',
'tooltip-pt-preferences'          => 'Ayarlarım (nastroykalarım)',
'tooltip-pt-watchlist'            => 'Men közetüvge alğan saifeler',
'tooltip-pt-mycontris'            => 'Qoşqan isselerimniñ cedveli',
'tooltip-pt-login'                => 'Oturım açmañız tevsiye olunır amma mecbur degilsiñiz.',
'tooltip-pt-anonlogin'            => 'Oturım açmañız tevsiye olunır amma mecbur degilsiñiz.',
'tooltip-pt-logout'               => 'Sistemadan çıq',
'tooltip-ca-talk'                 => 'Saifedeki malümat ile bağlı zan belgile',
'tooltip-ca-edit'                 => 'Bu saifeni deñiştire bilesiñiz. Saqlamazdan evel ög baquv yapmağa unutmañız.',
'tooltip-ca-addsection'           => 'Bu muzakerege tefsir ekleñiz.',
'tooltip-ca-viewsource'           => 'Bu saife qorçalav altında. Menba kodunı tek köre bilirsiñiz. Deñiştirip olamazsıñız.',
'tooltip-ca-history'              => 'Bu saifeniñ keçmiş versiyaları.',
'tooltip-ca-protect'              => 'Bu saifeni qorçala',
'tooltip-ca-delete'               => 'Saifeni yoq et',
'tooltip-ca-undelete'             => 'Saifeni yoq etilmezden evelki alına keri ketiriñiz',
'tooltip-ca-move'                 => 'Saifeniñ adını deñiştir',
'tooltip-ca-watch'                => 'Bu saifeni közetüvge al',
'tooltip-ca-unwatch'              => 'Bu saifeni közetmekni taşla',
'tooltip-search'                  => 'Bu vikide qıdıruv yap',
'tooltip-p-logo'                  => 'Baş saife',
'tooltip-n-mainpage'              => 'Başlanğıç saifesine qaytıñız',
'tooltip-n-portal'                => 'Proekt üzerine, ne qaydadır, neni yapa bilesiñiz',
'tooltip-n-currentevents'         => 'Ağımdaki vaqialarnen bağlı soñki malümatlar',
'tooltip-n-recentchanges'         => 'Vikide yapılğan soñki deñişikliklerniñ cedveli.',
'tooltip-n-randompage'            => 'Tesadüfiy bir maqalege barıñız',
'tooltip-n-help'                  => 'Yardım almaq içün.',
'tooltip-n-sitesupport'           => 'Maddiy destek',
'tooltip-t-whatlinkshere'         => 'Bu saifege bağlantı bergen diger viki saifeleriniñ cedveli',
'tooltip-t-recentchangeslinked'   => 'Bu saifege bağlantı bergen saifelerdeki soñki deñişiklikler',
'tooltip-feed-rss'                => 'Bu saife içün RSS translâtsiyası',
'tooltip-feed-atom'               => 'Bu saife içün atom translâtsiyası',
'tooltip-t-contributions'         => 'Qullanıcınıñ isse cedvelini kör',
'tooltip-t-emailuser'             => 'Qullanıcığa e-mail köster',
'tooltip-t-upload'                => 'Sistemağa resim ya da media fayllarnı yükleñiz',
'tooltip-t-specialpages'          => 'Bütün mahsus saifelerniñ cedvelini köster',
'tooltip-ca-nstab-main'           => 'Saifeni köster',
'tooltip-ca-nstab-user'           => 'Qullanıcı saifesini köster',
'tooltip-ca-nstab-media'          => 'Media saifesini köster',
'tooltip-ca-nstab-special'        => 'Bu mahsus saife olğanı içün deñişiklik yapamazsıñız.',
'tooltip-ca-nstab-project'        => 'Proekt saifesini köster',
'tooltip-ca-nstab-image'          => 'Resim saifesini köster',
'tooltip-ca-nstab-mediawiki'      => 'Sistema beyanatını köster',
'tooltip-ca-nstab-template'       => 'Şablonnı köster',
'tooltip-ca-nstab-help'           => 'Yardım saifesini körmek içün basıñız',
'tooltip-ca-nstab-category'       => 'Kategoriya saifesini köster',
'tooltip-minoredit'               => 'Kiçik deñişiklik olaraq işaretle',
'tooltip-save'                    => 'Deñişikliklerni saqla',
'tooltip-preview'                 => 'Ög baquv; saqlamazdan evel bu hususiyetni qullanaraq deñişiklikleriñizni közden keçiriñiz!',
'tooltip-diff'                    => 'Metinge siz yapqan deñişikliklerni kösterir.',
'tooltip-compareselectedversions' => 'Seçilgen eki versiya arasındaki farqlarnı köster.',
'tooltip-watch'                   => 'Saifeni közetüv cedveline ekle',

# Stylesheets
'monobook.css' => '/* monobook temasınıñ ayarlarını (nastroykalarını) deñiştirmek içün bu yerini deñiştiriñiz. Bütün saytta tesirli olur. */',

# Attribution
'siteuser'         => '{{SITENAME}} qullanıcı $1',
'lastmodifiedatby' => 'Saife eñ soñki $3 tarafından $2, $1 tarihında deñiştirildi.', # $1 date, $2 time, $3 user
'and'              => 've',
'others'           => 'digerleri',
'siteusers'        => '{{SITENAME}} qullanıcılar $1',

# Spam protection
'spamprotectiontitle'    => 'Spam qarşı qorçalav filtri',
'spamprotectiontext'     => 'Saqlamağa istegen saife spam filtri tarafından blok etildi. Büyük ihtimallı ki, bir tış bağlantıdan menbalanmaqta.',
'subcategorycount'       => 'Bu kategoriyada {{PLURAL:$1|1|$1}} altkategoriya bar.',
'categoryarticlecount'   => 'Bu kategoriyada {{PLURAL:$1|1|$1}} maqale bar.',
'listingcontinuesabbrev' => ' (devam)',

# Info page
'infosubtitle'   => 'Saife aqqında malümat',
'numedits'       => 'Deñişiklik sayısı (saife): $1',
'numtalkedits'   => 'Deñişiklik sayısı (muzakere saifesi): $1',
'numwatchers'    => 'Közetici sayısı: $1',
'numauthors'     => 'Müellif sayısı (saife): $1',
'numtalkauthors' => 'Müellif sayısı (muzakere saifesi): $1',

# Math options
'mw_math_png'    => 'Daima PNG resim formatına çevir',
'mw_math_simple' => 'Pek basit olsa HTML, yoqsa PNG',
'mw_math_html'   => 'Mümkün olsa HTML, yoqsa PNG',
'mw_math_source' => 'Deñiştirmeden TeX olaraq taşla  (metin temelli brauzerler içün)',
'mw_math_modern' => 'Zemaneviy brauzerler içün tevsiye etilgen',
'mw_math_mathml' => 'Mümkün olsa MathML (daa deñeme turuşında)',

# Image deletion
'deletedrevision' => '$1 sayılı eski versiya yoq etildi.',

# Browsing diffs
'previousdiff' => '← Evelki versiyanen aradaki farq',
'nextdiff'     => 'Soñraki versiyanen aradaki farq →',

# Media information
'mediawarning' => "'''DİQQAT!''': Bu faylda yaman maqsatlı (virus kibi) qısım buluna bilir ve öperatsion sistemañızğa zarar ketire bilir.
<hr />",
'imagemaxsize' => 'Resim açıqlamalar saifelerindeki resimniñ eñ büyük büyükligi:',
'thumbsize'    => 'Kiçik büyüklik:',

# Special:Newimages
'newimages'    => 'Yañı resimler',
'showhidebots' => '(botlarnı $1)',
'noimages'     => 'Resim yoq.',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2 × $3',
'seconds-abbrev' => 'san.',
'minutes-abbrev' => 'daq.',
'hours-abbrev'   => 'saat',

# Metadata
'metadata'          => 'Resim detalleri',
'metadata-expand'   => 'Tafsilâtnı köster',
'metadata-collapse' => 'Tafsilâtnı kösterme',

# EXIF tags
'exif-make'                => 'Kamera markası',
'exif-model'               => 'Kamera modeli',
'exif-artist'              => 'Yaratıcısı',
'exif-colorspace'          => 'Renk aralığı',
'exif-datetimeoriginal'    => 'Öriginal saat ve tarih',
'exif-exposuretime'        => 'Ekspozitsiya müddeti',
'exif-exposuretime-format' => '$1 saniye ($2)',
'exif-fnumber'             => 'Diafragma nomerası',
'exif-spectralsensitivity' => 'Spektral duyğulılıq',
'exif-aperturevalue'       => 'Diafragma',
'exif-brightnessvalue'     => 'parlaqlıq',
'exif-lightsource'         => 'Yarıq turuşı',
'exif-exposureindex'       => 'Ekspozitsiya indeksi',
'exif-scenetype'           => 'Stsena tipi',
'exif-digitalzoomratio'    => 'Yaqınlaştıruv koeffitsiyenti',
'exif-contrast'            => 'Kontrastlıq',
'exif-saturation'          => 'Toyğunlıq',
'exif-sharpness'           => 'Açıqlıq',
'exif-gpslatitude'         => 'Enlik',
'exif-gpslongitude'        => 'Boyluq',
'exif-gpsaltitude'         => 'Yükseklik',
'exif-gpstimestamp'        => 'GPS saatı (atom saatı)',
'exif-gpssatellites'       => 'Ölçemek içün qullanğanı sputnikler',

# EXIF attributes
'exif-compression-1' => 'Sıqıştırılmağan',

'exif-orientation-3' => '180° aylandırılğan', # 0th row: bottom; 0th column: right

'exif-exposureprogram-1' => 'Elnen',

'exif-subjectdistance-value' => '$1 metr',

'exif-meteringmode-0'   => 'Bilinmey',
'exif-meteringmode-1'   => 'Orta',
'exif-meteringmode-255' => 'Diger',

'exif-lightsource-0'  => 'Bilinmey',
'exif-lightsource-2'  => 'Fluorestsent',
'exif-lightsource-9'  => 'Açıq',
'exif-lightsource-10' => 'Qapalı',
'exif-lightsource-11' => 'Kölge',
'exif-lightsource-15' => 'Beyaz fluorestsent (WW 3200 – 3700K)',

'exif-sensingmethod-1' => 'Tanıtuvsız',

'exif-scenecapturetype-0' => 'Standart',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Gece syomkası',

'exif-subjectdistancerange-0' => 'Bilinmey',
'exif-subjectdistancerange-1' => 'Makro',

# External editor support
'edit-externally'      => 'Fayl üzerinde kompyuteriñizde bulunğan programmalar ile deñişiklikler yapıñız',
'edit-externally-help' => 'Daa fazla malümat içün metadaki [http://meta.wikimedia.org/wiki/Help:External_editors] (İnglizce) saifesine baqa bilesiñiz.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Episini köster',
'imagelistall'     => 'Cümlesi',
'watchlistall2'    => 'Episini köster',
'namespacesall'    => 'Epsi',
'monthsall'        => 'Episi',

# E-mail address confirmation
'confirmemail'            => 'E-mail adresini tasdıqla',
'confirmemail_text'       => 'Viki-niñ e-mail funktsiyalarını qullanmazdan evel e-mail adresiñizniñ
tasdıqlanması kerek. Adresiñizge tasdıq e-mail-i yollamaq içün aşağıdaki
dögmeni basıñız. Yollanacaq beyanatta adresiñizni tasdıqlamaq içün brauzeriñiznen 
irişe bilecek, tasdıq kodu olğan bir bağlantı olacaq.',
'confirmemail_send'       => 'Tasdıq kodunı yolla',
'confirmemail_sent'       => 'Tasdıq e-mail-i yollandı.',
'confirmemail_sendfailed' => 'Tasdıq kodu yollanmadı. Adreste keçersiz arif ya da işaret olmağanından eminsiñizmi? Mektüp qaytıldı: $1',
'confirmemail_invalid'    => 'Keçersiz tasdıq kodu. Tasdıq kodunıñ soñki qullanma tarihı keçken ola bilir.',
'confirmemail_needlogin'  => '$1 yapmaq içün başta e-mail adresiñizni tasdıqlamalısıñız.',
'confirmemail_success'    => 'E-mail adresiñiz tasdıqlandı.',
'confirmemail_loggedin'   => 'E-mail adresiñiz tasdıqlandı.',
'confirmemail_error'      => 'Tasdıqıñız bilinmegen bir hata sebebinden qayd etilmedi.',
'confirmemail_subject'    => '{{SITENAME}} e-mail adres tasdıqı.',
'confirmemail_body'       => '$1 internet adresinden yapılğan irişim ile {{SITENAME}} saytında 
bu e-mail adresi ile bağlanğan $2 qullanıcı esabı 
açıldı.  

Bu e-mail adresiniñ bahsı keçken qullanıcı esabına ait olğanını
tasdıqlamaq ve {{SITENAME}} saytındaki e-mail funktsiyalarını aktiv alğa 
ketirmek içün aşağıdaki bağlantını basıñız.

$3

Bahsı keçken qullanıcı esabı sizge ait olmağan olsa siz yapmaq kerek olğan
bir şeyiñiz yoq. Bu tasdıq kodu $4 tarinına qadar keçerli olacaq.',

# Delete conflict
'deletedwhileediting' => 'Tenbi: Bu saife siz deñişiklik yapmağa başlağandan soñ yoq etildi!',
'confirmrecreate'     => "Bu saifeni [[User:$1|$1]] ([[User talk:$1|muzakere]]) qullanıcısı siz saifede deñişiklik yaparken silgendir, sebebi:
:''$2'' 
Saifeni yañıdan açmağa isteseñiz, lütfen tasdıqlañız.",
'recreate'            => 'Saifeni yañıdan aç',

# HTML dump
'redirectingto' => 'Yollama [[$1]]...',

# action=purge
'confirm_purge'        => 'Saife keşini temizlesinmi? $1',
'confirm_purge_button' => 'Ok',

# AJAX search
'searchcontaining' => "''$1'' degen sözler ile saifelerni qıdıruv.",
'searchnamed'      => "''$1'' adlı saifelerni qıdıruv.",
'articletitles'    => "''$1'' ile başlağan saifelerni qıdıruv.",
'hideresults'      => 'Neticelerni gizle',

# Multipage image navigation
'imgmultipageprev' => '← evelki saife',
'imgmultipagenext' => 'soñraki saife →',
'imgmultigo'       => 'Bar',
'imgmultigotopre'  => 'Bu saifege bar',

# Table pager
'ascending_abbrev'         => 'kiçikten büyükke',
'descending_abbrev'        => 'büyükten kiçikke',
'table_pager_next'         => 'Soñraki saife',
'table_pager_prev'         => 'Evelki saife',
'table_pager_first'        => 'İlk saife',
'table_pager_last'         => 'Soñki saife',
'table_pager_limit_submit' => 'Bar',
'table_pager_empty'        => 'İç netice yoq',

# Auto-summaries
'autosumm-blank'   => 'Saife boşatıldı',
'autosumm-replace' => "Saifedeki malümat '$1' ile deñiştirildi",
'autoredircomment' => '[[$1]] saifesine yollandı',
'autosumm-new'     => 'Yañı saife: $1',

);
