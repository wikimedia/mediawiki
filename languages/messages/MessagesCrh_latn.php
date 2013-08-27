<?php
/** Crimean Turkish (Latin script) (qırımtatarca (Latin)‎)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AlefZet
 * @author Don Alessandro
 * @author Urhixidur
 */

$fallback8bitEncoding = 'windows-1254';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Mahsus',
	NS_TALK             => 'Muzakere',
	NS_USER             => 'Qullanıcı',
	NS_USER_TALK        => 'Qullanıcı_muzakeresi',
	NS_PROJECT_TALK     => '$1_muzakeresi',
	NS_FILE             => 'Fayl',
	NS_FILE_TALK        => 'Fayl_muzakeresi',
	NS_MEDIAWIKI        => 'MediaViki',
	NS_MEDIAWIKI_TALK   => 'MediaViki_muzakeresi',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_muzakeresi',
	NS_HELP             => 'Yardım',
	NS_HELP_TALK        => 'Yardım_muzakeresi',
	NS_CATEGORY         => 'Kategoriya',
	NS_CATEGORY_TALK    => 'Kategoriya_muzakeresi',
);

$namespaceAliases = array(
	# Aliases to Cyrillic (crh-cyrl) namespaces
	"Медиа"                  => NS_MEDIA,
	"Махсус"                 => NS_SPECIAL,
	"Музакере"               => NS_TALK,
	"Къулланыджы"            => NS_USER,
	"Къулланыджы_музакереси" => NS_USER_TALK,
	"$1_музакереси"          => NS_PROJECT_TALK,
	"Ресим"                  => NS_FILE,
	"Ресим_музакереси"       => NS_FILE_TALK,
	"Resim"                  => NS_FILE,
	"Resim_muzakeresi"       => NS_FILE_TALK,
	"МедиаВики"              => NS_MEDIAWIKI,
	"МедиаВики_музакереси"   => NS_MEDIAWIKI_TALK,
	'Шаблон'                 => NS_TEMPLATE,
	'Шаблон_музакереси'      => NS_TEMPLATE_TALK,
	'Ярдым'                  => NS_HELP,
	'Разговор_о_помоћи'      => NS_HELP_TALK,
	'Категория'              => NS_CATEGORY,
	'Категория_музакереси'   => NS_CATEGORY_TALK
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

$separatorTransformTable = array( ',' => '.', '.' => ',' );
$linkTrail = '/^([a-zâçğıñöşüа-яё“»]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline' => 'Bağlantılarnıñ tübüni sızuv:',
'tog-justify' => 'Metinni eki yanğa tegizle',
'tog-hideminor' => '"Soñki deñiştirmeler" saifesinde kiçik deñiştirmelerni gizle',
'tog-hidepatrolled' => 'Soñki deñiştirmeler köstergende teşkerilgen deñiştirmelerni gizle',
'tog-newpageshidepatrolled' => 'Yañı saifeler köstergende teşkerilgen saifelerni gizle',
'tog-extendwatchlist' => 'Közetüv cedvelini, tek soñki degil, bütün deñiştirmelerni körmek içün kenişlet',
'tog-usenewrc' => 'Soñki deñiştirmeler saifesindeki ve közetüv cedvelindeki deñiştirmelerni gruppalandıruv (JavaScript kerek)',
'tog-numberheadings' => 'Serlevalarnı avtomatik nomeralandır',
'tog-showtoolbar' => 'Saifeni deñiştirgen vaqıtta yardımcı dögmelerni köster. (JavaScript)',
'tog-editondblclick' => 'Saifeni çift basıp deñiştirmege başla (JavaScript)',
'tog-editsection' => 'Bölüklerni [deñiştir] bağlantısı vastasınen deñiştirmege ruhset ber',
'tog-editsectiononrightclick' => 'Bölük serlevasına oñ basqanda deñiştirüv penceresini aç. (JavaScript)',
'tog-showtoc' => 'Münderice cedveli köster (3 daneden ziyade serlevası olğan saifeler içün)',
'tog-rememberpassword' => 'Kirişimni bu brauzerde hatırla (eñ çoq $1 {{PLURAL:$1|kün|kün}} içün)',
'tog-watchcreations' => 'Yaratqan saifelerimni ve yüklegen fayllarımnı közetüv cedvelime kirset',
'tog-watchdefault' => 'Deñiştirgen saife ve fayllarımnı közetüv cedvelime kirset',
'tog-watchmoves' => 'Adını men deñiştirgen saife ve fayllarnı közetüv cedvelime kirset',
'tog-watchdeletion' => 'Yoq etken saife ve fayllarımnı közetüv cedvelime kirset',
'tog-minordefault' => 'Yapqan deñiştirmelerimni kiçik deñiştirmedir dep işaretle',
'tog-previewontop' => 'Baqıp çıquvnı yazuv pencereniñ üstünde köster',
'tog-previewonfirst' => 'Deñiştirme saifesine keçkende baqıp çıquvnı köster',
'tog-nocache' => 'Brauzer saifelerni afızasında tutmasın',
'tog-enotifwatchlistpages' => 'Közetüv cedvelimdeki bir saife ya da fayl deñiştirilgende maña e-mail yolla',
'tog-enotifusertalkpages' => 'Qullanıcı saifem deñiştirilgende maña e-mail yolla',
'tog-enotifminoredits' => 'Saife ya da faylda kiçik deñiştirilme olğanda da de maña e-mail yolla',
'tog-enotifrevealaddr' => 'Bildirüv mektüplerinde e-mail adresimni köster',
'tog-shownumberswatching' => 'Közetken qullanıcı sayısını köster',
'tog-oldsig' => 'Şimdiki imza:',
'tog-fancysig' => 'İmza vikimetin kibi olsun (avtomatik bağlantı olmaz)',
'tog-uselivepreview' => 'Canlı baqıp çıquv hususiyetini qullan (JavaScript) (daa deñeme alında)',
'tog-forceeditsummary' => 'Deñiştirmeniñ qısqa tarifini boş taşlasam meni tenbile',
'tog-watchlisthideown' => 'Közetüv cedvelimden menim deñiştirmelerimni gizle',
'tog-watchlisthidebots' => 'Közetüv cedvelimden bot deñiştirmelerini gizle',
'tog-watchlisthideminor' => 'Közetüv cedvelimden kiçik deñiştirmelerni gizle',
'tog-watchlisthideliu' => 'Közetüv cedvelimde qaydlı qullanıcılar tarafından yapılğan deñiştirmelerni kösterme',
'tog-watchlisthideanons' => 'Közetüv cedvelimde qaydsız (anonim) qullanıcılar tarafından yapılğan deñiştirmelerni kösterme',
'tog-watchlisthidepatrolled' => 'Közetüv cedvelinde teşkerilgen deñiştirmelerni gizle',
'tog-ccmeonemails' => 'Diger qullanıcılarğa yollağan mektüplerimniñ kopiyalarını maña da yolla',
'tog-diffonly' => 'Teñeştirme saifelerinde saifeniñ esas mündericesini kösterme',
'tog-showhiddencats' => 'Gizli kategoriyalarnı köster',
'tog-norollbackdiff' => 'Keri qaytaruv yapılğan soñ versiyalar arasındaki farqnı kösterme',

'underline-always' => 'Daima',
'underline-never' => 'Asla',
'underline-default' => 'Brauzer sazlamaları qullanılsın',

# Font style option in Special:Preferences
'editfont-style' => 'Yazuv penceresinde urufat (şrift) türü:',
'editfont-default' => 'Brauzerge köre',
'editfont-monospace' => 'Bir keñlikli urufat',
'editfont-sansserif' => 'Kertiksiz urufat',
'editfont-serif' => 'Kertikli urufat',

# Dates
'sunday' => 'Bazar',
'monday' => 'Bazarertesi',
'tuesday' => 'Salı',
'wednesday' => 'Çarşenbe',
'thursday' => 'Cumaaqşamı',
'friday' => 'Cuma',
'saturday' => 'Cumaertesi',
'sun' => 'Bazar',
'mon' => 'Bazarertesi',
'tue' => 'Salı',
'wed' => 'Çarşenbe',
'thu' => 'Cumaaqşamı',
'fri' => 'Cuma',
'sat' => 'Cumaertesi',
'january' => 'yanvar',
'february' => 'fevral',
'march' => 'mart',
'april' => 'aprel',
'may_long' => 'mayıs',
'june' => 'iyün',
'july' => 'iyül',
'august' => 'avgust',
'september' => 'sentâbr',
'october' => 'oktâbr',
'november' => 'noyabr',
'december' => 'dekabr',
'january-gen' => 'yanvarniñ',
'february-gen' => 'fevralniñ',
'march-gen' => 'martnıñ',
'april-gen' => 'aprelniñ',
'may-gen' => 'mayısnıñ',
'june-gen' => 'iyünniñ',
'july-gen' => 'iyülniñ',
'august-gen' => 'avgustnıñ',
'september-gen' => 'sentâbrniñ',
'october-gen' => 'oktâbrniñ',
'november-gen' => 'noyabrniñ',
'december-gen' => 'dekabrniñ',
'jan' => 'yan',
'feb' => 'fev',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'may',
'jun' => 'iyün',
'jul' => 'iyül',
'aug' => 'avg',
'sep' => 'sen',
'oct' => 'okt',
'nov' => 'noy',
'dec' => 'dek',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Saifeniñ kategoriyası|Saifeniñ kategoriyaları}}',
'category_header' => '"$1" kategoriyasındaki saifeler',
'subcategories' => 'Alt kategoriyalar',
'category-media-header' => '"$1" kategoriyasındaki media faylları',
'category-empty' => "''İşbu kategoriyada iç bir saife ya da media fayl yoq.''",
'hidden-categories' => 'Gizli {{PLURAL:$1|kategoriya|kategoriyalar}}',
'hidden-category-category' => 'Gizli kategoriyalar',
'category-subcat-count' => '{{PLURAL:$2|Bu kategoriyada tek bir aşağıdaki alt kategoriya bar.|Bu kategoriyada toplam $2 kategoriyadan aşağıdaki $1 alt kategoriya bar.}}',
'category-subcat-count-limited' => 'Bu kategoriyada aşağıdaki {{PLURAL:$1|1|$1}} alt kategoriya bar.',
'category-article-count' => '{{PLURAL:$2|Bu kategoriyada tek bir aşağıdaki saife bar.|Bu kategoriyadaki toplam $2 saifeden aşağıdaki $1 saife kösterilgen.}}',
'category-article-count-limited' => 'Bu kategoriyada aşağıdaki {{PLURAL:$1|1|$1}} saife bar.',
'category-file-count' => '{{PLURAL:$2|Bu kategoriyada tek bir aşağıdaki fayl bar.|Bu kategoriyadaki toplam $2 fayldan aşağıdaki $1 fayl kösterilgen.}}',
'category-file-count-limited' => 'Bu kategoriyada aşağıdaki {{PLURAL:$1|1|$1}} fayl bar.',
'listingcontinuesabbrev' => ' (devam)',
'index-category' => 'İndeksli saifeler',
'noindex-category' => 'İndekssiz saifeler',
'broken-file-category' => 'İçinde bozuq fayl bağlantıları olğan saifeler',

'linkprefix' => '/^((?>.*(?<![a-zâçğıñöşüA-ZÂÇĞİÑÖŞÜa-яёА-ЯЁ«„])))(.+)$/sDu',

'about' => 'Aqqında',
'article' => 'Saife',
'newwindow' => '(yañı bir pencerede açılır)',
'cancel' => 'Lâğu',
'moredotdotdot' => 'Daa...',
'morenotlisted' => 'Başqa bir şey yoq...',
'mypage' => 'Saife',
'mytalk' => 'Muzakere',
'anontalk' => 'Bu IP-niñ muzakeresi',
'navigation' => 'Saytta yol tapuv',
'and' => '&#32;ve',

# Cologne Blue skin
'qbfind' => 'Tap',
'qbbrowse' => 'Baqıp çıq',
'qbedit' => 'Deñiştir',
'qbpageoptions' => 'Bu saife',
'qbmyoptions' => 'Saifelerim',
'qbspecialpages' => 'Mahsus saifeler',
'faq' => 'Sıq berilgen sualler',
'faqpage' => 'Project:Sıq berilgen sualler',

# Vector skin
'vector-action-addsection' => 'Mevzu qoş',
'vector-action-delete' => 'Yoq et',
'vector-action-move' => 'Adını deñiştir',
'vector-action-protect' => 'Qorçala',
'vector-action-undelete' => 'Yañıdan yarat',
'vector-action-unprotect' => 'Qorçalavnı deñiştir',
'vector-simplesearch-preference' => 'Sadeleştirilgen qıdıruv satırını işlet (tek Vektor körünişi içün)',
'vector-view-create' => 'Yarat',
'vector-view-edit' => 'Deñiştir',
'vector-view-history' => 'Keçmişini köster',
'vector-view-view' => 'Oqu',
'vector-view-viewsource' => 'Menba kodunı köster',
'actions' => 'Areketler',
'namespaces' => 'İsim fezaları',
'variants' => 'Variantlar',

'navigation-heading' => 'Dolaşuv menüsi',
'errorpagetitle' => 'Hata',
'returnto' => '$1.',
'tagline' => '{{GRAMMAR:ablative|{{SITENAME}}}}',
'help' => 'Yardım',
'search' => 'Qıdıruv',
'searchbutton' => 'Qıdır',
'go' => 'Bar',
'searcharticle' => 'Bar',
'history' => 'Saifeniñ keçmişi',
'history_short' => 'Keçmiş',
'updatedmarker' => 'soñki ziyaretimden soñ yañarğan',
'printableversion' => 'Basılmağa uyğun körüniş',
'permalink' => 'Soñki alına bağlantı',
'print' => 'Bastır',
'view' => 'Köster',
'edit' => 'Deñiştir',
'create' => 'Yarat',
'editthispage' => 'Saifeni deñiştir',
'create-this-page' => 'Bu saifeni yarat',
'delete' => 'Yoq et',
'deletethispage' => 'Saifeni yoq et',
'undelete_short' => '{{PLURAL:$1|1|$1}} deñiştirmeni keri ketir',
'viewdeleted_short' => '{{PLURAL:$1|bir yoq etilgen deñiştirmeni|$1 yoq etilgen deñiştirmeni}} köster.',
'protect' => 'Qorçala',
'protect_change' => 'deñiştir',
'protectthispage' => 'Saifeni qorçalav altına al',
'unprotect' => 'Qorçalavnı deñiştir',
'unprotectthispage' => 'Saife qorçalanuvını deñiştir',
'newpage' => 'Yañı saife',
'talkpage' => 'Saifeni muzakere et',
'talkpagelinktext' => 'Muzakere',
'specialpage' => 'Mahsus Saife',
'personaltools' => 'Şahsiy aletler',
'postcomment' => 'Yañı bölük',
'articlepage' => 'Saifege bar',
'talk' => 'Muzakere',
'views' => 'Körünişler',
'toolbox' => 'Aletler',
'userpage' => 'Qullanıcı saifesini köster',
'projectpage' => 'Leyha saifesini köster',
'imagepage' => 'Fayl saifesini köster',
'mediawikipage' => 'Beyanat saifesisni köster',
'templatepage' => 'Şablon saifesini köster',
'viewhelppage' => 'Yardım saifesini köster',
'categorypage' => 'Kategoriya saifesini köster',
'viewtalkpage' => 'Muzakere saifesini köster',
'otherlanguages' => 'Diger tillerde',
'redirectedfrom' => '($1 saifesinden yollandı)',
'redirectpagesub' => 'Yollama saifesi',
'lastmodifiedat' => 'Bu saife soñki olaraq $1, $2 tarihında yañardı.',
'viewcount' => 'Bu saife {{PLURAL:$1|1|$1}} defa irişilgen.',
'protectedpage' => 'Qorçalanğan saife',
'jumpto' => 'Buña bar:',
'jumptonavigation' => 'qullan',
'jumptosearch' => 'qıdır',
'view-pool-error' => 'Afu etiñiz, server şimdi adden-aşır yüklendi. Pek çoq qullanıcı bu saifeni açmağa tırışa. Lütfen, bu saifeni bir daa açmaqtan evel biraz bekleñiz.

$1',
'pool-timeout' => 'Kilit içün vaqıt bitişi beklene',
'pool-queuefull' => 'Soratma toplayıcısı tolu',
'pool-errorunknown' => 'Bilinmegen hata',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '{{SITENAME}} aqqında',
'aboutpage' => 'Project:Aqqında',
'copyright' => 'Malümat $1 binaen keçilip ola.',
'copyrightpage' => '{{ns:project}}:Müelliflik aqları',
'currentevents' => 'Şimdiki vaqialar',
'currentevents-url' => 'Project:Ağımdaki vaqialar',
'disclaimers' => 'Cevapkârlıq redi',
'disclaimerpage' => 'Project:Umumiy Malümat Muqavelesi',
'edithelp' => 'Saifeler nasıl deñiştirilir?',
'helppage' => 'Help:Münderice',
'mainpage' => 'Baş Saife',
'mainpage-description' => 'Baş Saife',
'policy-url' => 'Project:Qaideler',
'portal' => 'Cemaat portalı',
'portal-url' => 'Project:Cemaat portalı',
'privacy' => 'Gizlilik esası',
'privacypage' => 'Project:Gizlilik esası',

'badaccess' => 'İzin hatası',
'badaccess-group0' => 'Yapacaq olğan areketiñizni yapmağa aqqıñız yoq.',
'badaccess-groups' => 'Yapacaq olğan areketiñizni tek aşağıdaki {{PLURAL:$2|1|$2}} gruppağa aza оlğan qullanıcıları yapıp olalar: $1.',

'versionrequired' => 'MediaWikiniñ $1 versiyası kerek',
'versionrequiredtext' => 'Bu saifeni qullanmaq içün MediaWikiniñ $1 versiyası kerek. [[Special:Version|Versiya]] saifesine baq.',

'ok' => 'Ok',
'retrievedfrom' => 'Menba – "$1"',
'youhavenewmessages' => 'Yañı $1 bar ($2).',
'newmessageslink' => 'beyanatıñız',
'newmessagesdifflink' => 'muzakere saifeñizniñ soñki deñiştirilmesi',
'youhavenewmessagesfromusers' => '{{PLURAL:$3|Başqa bir qullanıcıdan|$3 qullanıcıdan}} $1 bar. ($2)',
'youhavenewmessagesmanyusers' => 'Bir qaç qullanıcıdan $1 bar. ($2)',
'newmessageslinkplural' => '{{PLURAL:$1|yañı beyanatıñız|yañı beyanatlarıñız}}',
'newmessagesdifflinkplural' => 'muzakere saifeñizniñ soñki {{PLURAL:$1|deñiştirilmesi|deñiştirilmeleri}}',
'youhavenewmessagesmulti' => '$1 saifesinde yañı beyanatıñız bar.',
'editsection' => 'deñiştir',
'editold' => 'deñiştir',
'viewsourceold' => 'menba kоdunı köster',
'editlink' => 'deñiştir',
'viewsourcelink' => 'menba kоdunı köster',
'editsectionhint' => 'Deñiştirilgen bölük: $1',
'toc' => 'Münderice',
'showtoc' => 'köster',
'hidetoc' => 'gizle',
'collapsible-collapse' => 'Tarlat',
'collapsible-expand' => 'Kenişlet',
'thisisdeleted' => '$1 körmege ya da keri ketirmege isteysiñizmi?',
'viewdeleted' => '$1 kör?',
'restorelink' => 'yoq etilgen {{PLURAL:$1|1|$1}} deñiştirmesi',
'feedlinks' => 'Bu şekilde:',
'feed-invalid' => 'Abune kanalınıñ çeşiti yañlıştır.',
'feed-unavailable' => 'Sindikatsiya lentaları qullanılıp оlamay.',
'site-rss-feed' => '$1 RSS lentası',
'site-atom-feed' => '$1 Atom lentası',
'page-rss-feed' => '"$1" - RSS lentası',
'page-atom-feed' => '"$1" - Atom lentası',
'red-link-title' => '$1 (böyle saife yоq)',
'sort-descending' => 'Eksilmesi sayın sırala',
'sort-ascending' => 'Artması sayın sırala',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Saife',
'nstab-user' => 'Qullanıcı saifesi',
'nstab-media' => 'Media',
'nstab-special' => 'Mahsus saife',
'nstab-project' => 'Leyha saifesi',
'nstab-image' => 'Fayl',
'nstab-mediawiki' => 'Beyanat',
'nstab-template' => 'Şablon',
'nstab-help' => 'Yardım',
'nstab-category' => 'Kategoriya',

# Main script and global functions
'nosuchaction' => 'Böyle bir areket yoq',
'nosuchactiontext' => 'URL-de bildirilgen areket ruhsetsiz.
Belki de URL-ni yañlış yazğandırsız, ya da doğru olmağan bir bağlantını qullanğandırsız.
Bu, {{SITENAME}} saytındaki bir hatanı da kösterip оla.',
'nosuchspecialpage' => 'Bu isimde bir mahsus saife yoq',
'nospecialpagetext' => '<strong>Tapılmağan bir mahsus saifege kirdiñiz.</strong>

Bar olğan bütün mahsus saifelerni [[Special:SpecialPages|{{int:specialpages}}]] saifesinde körip olursıñız.',

# General errors
'error' => 'Hata',
'databaseerror' => 'Malümat bazasınıñ hatası',
'laggedslavemode' => 'Diqqat! Bu saifede soñki yañaruv olmay bile.',
'readonly' => 'Malümat bazası kilitlendi',
'enterlockreason' => 'Blok etüvniñ sebebini ve devamını kirsetiñiz.',
'readonlytext' => 'Plan işlemelerinden sebep malümat bazası vaqtınca blok etildi. İşlemeler tamamlanğan soñ normal alına qaytacaq.

Malümat bazasını kilitlegen idareciniñ añlatması: $1',
'missing-article' => 'Malümat bazasında tapılması kerek olğan saifeniñ metni tapılmadı, "$1" $2.

Adetince yoq etilgen saifeniñ keçmiş saifesine eskirgen bağlantınen keçip baqqanda bu şey olıp çıqa.

Mesele bunda olmasa, ihtimalı bar ki, programmada bir hata tapqandırsıñız.
Lütfen, URL yazıp bundan [[Special:ListUsers/sysop|idarecige]] haber beriñiz.',
'missingarticle-rev' => '(versiya No. $1)',
'missingarticle-diff' => '(Farq: $1, $2)',
'readonly_lag' => 'Malümat bazasınıñ ekilemci serveri birlemci serverinen zamandaşlaştırılğance malümat bazası deñiştirilmemesi içün avtomatik olaraq blok etildi.',
'internalerror' => 'İçki hata',
'internalerror_info' => 'İçki hata: $1',
'fileappenderrorread' => 'Qoşma yapılğanda "$1" oqulamadı.',
'fileappenderror' => '"$1" faylı "$2" faylına qoşulıp olamay.',
'filecopyerror' => '"$1" faylı "$2" faylına kopiyalanıp olamay.',
'filerenameerror' => 'faylnıñ "$1" degen adı "$2" olaraq deñiştirilip olamay.',
'filedeleteerror' => '"$1" faylı yoq etilip olamay.',
'directorycreateerror' => '"$1" direktoriyası yaratılıp olamay.',
'filenotfound' => '"$1" faylı tapılıp olamay.',
'fileexistserror' => '"$1" faylı saqlanıp olamay, fayl endi bar.',
'unexpected' => 'beklenmegen deger: "$1"="$2".',
'formerror' => 'Hata: formanıñ malümatını yollamaqnıñ iç çaresi yoq',
'badarticleerror' => 'Siz yapmağa istegen işlev bu saifede yapılıp оlamay.',
'cannotdelete' => '"$1" saife ya da faylı yoq etilip olamadı. Başqa bir qullanıcı tarafından yoq etilgen ola bile.',
'cannotdelete-title' => '"$1" saifesini yoq etmege olmaz',
'delete-hook-aborted' => 'Yoq etüv çengel protsedurasınen toqtatıldı.
İç bir izaat berilmedi.',
'badtitle' => 'Ruhsetsiz serleva',
'badtitletext' => 'İstenilgen saife adı doğru degil, o boştır, yahut tillerara bağlantı ya da vikilerara bağlantı doğru yazılmağan. Belki saife adında yasaqlanğan işaretler bar.',
'perfcached' => 'Aşağıdaki malümat keşten alındı ve eskirgen ola bilir! Keşte eñ çoq {{PLURAL:$1|bir netice|$1 netice}} saqlanıp tura.',
'perfcachedts' => 'Aşağıdaki malümat keşten alındı, keşniñ soñki yañartılğan vaqtı: $1. Keşte eñ çoq {{PLURAL:$1|bir netice|$1 netice}} saqlanıp tura.',
'querypage-no-updates' => 'Bu saifeni deñiştirmege şimdi izin yoq. Bu malümat aman yañartılmaycaq.',
'wrong_wfQuery_params' => 'wrong_wfQuery_params - wfQuery() funktsiyası içün izinsiz parametrler<br />
Funktsiya: $1<br />
Soratma: $2',
'viewsource' => 'menba kodunı köster',
'viewsource-title' => '$1 saifesiniñ menba kodu',
'actionthrottled' => 'Areket toqtaldı',
'actionthrottledtext' => 'Spamğa qarşı küreş sebebinden bu areketni az vaqıt içinde çoq kere tekrarlap olamaysıñız. Mümkün olğan qarardan ziyade areket yaptıñız. Bir qaç daqqadan soñ tekrarlap baqıñız.',
'protectedpagetext' => 'Bu saifede deñiştirme ya da başqa bir areket yapılmasın dep o blok etildi.',
'viewsourcetext' => 'Saifeniñ kodunı közden keçirip kopiyalay bilesiñiz:',
'protectedinterface' => 'Bu saifede viki interfeysiniñ metni bar. Onıñ içün mında bir hata çıqmasın dep onı deñiştirmek yasaq.',
'editinginterface' => "'''Tenbi''': MediaWiki sistemasınıñ interfeys saifesini deñiştireyatasız. Bu saifedeki deñiştirmeler interfeysniñ körünişini bu vikiniñ başqa qullanıcıları içün de deñiştirecek. Lütfen, viki interfeysini tercime etmek içün [//translatewiki.net/wiki/Main_Page?setlang=crh translatewiki.net] saytını (MediaWiki resmiy lokalizatsiya leyhası) qullanıñız.",
'cascadeprotected' => 'Bu saifeni deñiştirip olamazsıñız, çünki kaskad qorçalav altında bulunğan {{PLURAL:$1|saifege|saifelerge}} mensüptir:
$2',
'namespaceprotected' => "'''$1''' isim fezasında saifeler deñiştirmege aqqıñız yoq.",
'ns-specialprotected' => '{{ns:special}} isim fezasındaki saifelerni deñiştirmek yasaq.',
'titleprotected' => "Böyle serlevanen saife yaratmaq yasaqtır. Yasaqlağan: [[User:$1|$1]].
Sebep: ''$2''.",

# Virus scanner
'virus-badscanner' => "Yañlış sazlama. Bilinmegen virus skaneri: ''$1''",
'virus-scanfailed' => 'skan etüv muvafaqiyetsiz (kod $1)',
'virus-unknownscanner' => 'bilinmegen antivirus:',

# Login and logout pages
'logouttext' => "'''Oturımnı qapattıñız.'''

Şimdi {{SITENAME}} saytını anonim olaraq qullanıp olasıñız, ya da yañıdan <span class='plainlinks'>[$1 oturım açıp]</span> olasıñız (ister aynı qullanıcı adınen, ister başqa bir qullanıcı adınen). Web brauzeriñiz keşini temizlegence bazı saifeler sanki alâ daa oturımıñız açıq eken kibi körünip olur.",
'yourname' => 'Qullanıcı adıñız',
'yourpassword' => 'Paroliñiz',
'yourpasswordagain' => 'Parolni bir daa yazıñız:',
'remembermypassword' => 'Kirişimni bu kompyuterde hatırla (eñ çoq $1 {{PLURAL:$1|kün|kün}} içün)',
'yourdomainname' => 'Domen adıñız',
'externaldberror' => 'Saytqa kirgende bir hata oldı. Bu tış esabıñıznı deñiştirmek aqqıñız olmağanından sebep meydanğa kelip ola.',
'login' => 'Kiriş',
'nav-login-createaccount' => 'Kiriş / Qayd oluv',
'loginprompt' => 'Oturım açmaq içün "cookies"ge izin bermelisiñiz.',
'userlogin' => 'Kiriş / Qayd oluv',
'userloginnocreate' => 'Kiriş',
'logout' => 'Çıqış',
'userlogout' => 'Çıqış',
'notloggedin' => 'Oturım açmadıñız.',
'nologin' => "Daa esap açmadıñızmı? '''$1'''.",
'nologinlink' => 'Qayd ol',
'createaccount' => 'Yañı esap aç',
'gotaccount' => "Daa evel esap açqan ediñizmi? '''$1'''.",
'gotaccountlink' => 'Oturım açıñız',
'userlogin-resetlink' => 'Kiriş malümatını unuttıñızmı?',
'createaccountmail' => 'e-mail vastasınen',
'createaccountreason' => 'Sebep:',
'badretype' => 'Kirsetken parolleriñiz aynı degil.',
'userexists' => 'Kirsetken qullanıcı adıñız endi qullanıla.
Lütfen, başqa bir qullanıcı adı saylañız.',
'loginerror' => 'Oturım açma hatası',
'createaccounterror' => 'Esap yaratılıp olamay: $1',
'nocookiesnew' => 'Qullanıcı esabı açılğan, faqat tanıtılmağan. {{SITENAME}} qullanıcılarnı tanıtmaq içün "cookies"ni qullana. Sizde bu funktsiya qapalı vaziyettedir. "Cookies" funktsiyasını işletip tekrar yañı adıñız ve paroliñiznen tırışıp baqınız.',
'nocookieslogin' => '{{SITENAME}} "cookies"ni qullana. Sizde bu funktsiya qapalı vaziyettedir. "Cookies" funktsiyasını işletip tekrar tırışıp baqıñız.',
'noname' => 'Doğru qullanıcı adını kirsetmediñiz.',
'loginsuccesstitle' => 'Kiriş yapıldı',
'loginsuccess' => "'''$1 adınen {{SITENAME}} saytında çalışıp olasıñız.'''",
'nosuchuser' => '"$1" adlı qullanıcı yoq.
Qullanıcı adlarında büyük ve kiçik arifler arasında farq bar.
Doğru yazğanıñıznı teşkeriñiz ya da [[Special:UserLogin/signup|yañı qullanıcı esabını açıñız]].',
'nosuchusershort' => '"$1" adlı qullanıcı tapılamadı. Adıñıznı doğru yazğanıñızdan emin oluñız.',
'nouserspecified' => 'Qullanıcı adını kirsetmek kereksiñiz.',
'login-userblocked' => 'Bu qullanıcı blok etilgen. Kirmege ruhset etilmey.',
'wrongpassword' => 'Kirsetken paroliñiz yañlıştır. Lütfen, tekrar etiñiz.',
'wrongpasswordempty' => 'Kirsetken parоliñiz bоştır.
Lütfen, tekrar etiñiz.',
'passwordtooshort' => 'Paroliñizde eñ az {{PLURAL:$1|1|$1}} işaret olmalı.',
'password-name-match' => 'Paroliñiz qullanıcı adıñızdan farqlı olmalı.',
'password-login-forbidden' => 'Bu qullanıcı adı ve parolni qullanmaq yasaqtır.',
'mailmypassword' => 'Yañı parol yiber',
'passwordremindertitle' => '{{grammar:genitive|{{SITENAME}}}} qullanıcınıñ parol hatırlatuvı',
'passwordremindertext' => 'Birev (belki de bu sizsiñiz, $1 IP adresinden) {{SITENAME}} saytı içün ($4) yañı qullanıcı parolini istedi.
$2 qullanıcısına vaqtınca <code>$3</code> paroli yaratıldı. Eger bu kerçekten de siziñ istegiñiz olğan olsa, oturım açıp yañı bir parol yaratmañız kerektir. Muvaqqat paroliñizniñ müddeti {{PLURAL:$5|1 kün|$5 kün}} içinde dolacaq.

Eger de yañı parol talap etmegen olsañız ya da eski paroliñizni hatırlap endi onı deñiştirmege istemeseñiz, bu mektüpni diqqatqa almayıp eski paroliñizni qullanmağa devam etip olasıñız.',
'noemail' => '$1 adlı qullanıcı içün e-mail bildirilmedi.',
'noemailcreate' => 'Doğru bir e-mail adresi bildirmek kereksiñiz',
'passwordsent' => 'Yañı parol e-mail yolunen qullanıcınıñ bildirgen $1 adresine yiberildi. Parolni alğan soñ tekrar kiriş yapıñız.',
'blocked-mailpassword' => 'IP adresiñizden saifeler deñiştirüv yasaqlı, parol hatırlatuv funktsiyası da blok etildi.',
'eauthentsent' => 'Bildirilgen e-mail adresine içinde tasdıq kodu olğan bir mektüp yollandı. Siz şu mektüpte yazılğan areketlerni yapıp bu e-mail adresiniñ saibi kerçekten de siz olğanıñıznı tasdıqlağan soñ başqa mektüp yollanıp olur.',
'throttled-mailpassword' => 'Parol hatırlatuv funktsiyası endi soñki $1 saat devamında işletilgen edi.
{{PLURAL:$1|1|$1}} saat içinde tek bir hatırlatuv işletmek mümkün.',
'mailerror' => 'Poçta yiberilgende bir hata meydanğa keldi: $1',
'acct_creation_throttle_hit' => 'Siziñ IP adresiñizni qullanıp bu vikini ziyaret etkenler soñki künde {{PLURAL:$1|1 esap|$1 esap}} yarattı. Bu vaqıt aralığında bir IP-den daa çoq esap yaratmaq mümkün degil.
Neticede, bu IP adresini qullanğan ziyaretçiler şimdi daa ziyade esap açıp olamazlar.',
'emailauthenticated' => 'E-mail adresiñiz $2 $3 tarihında tasdıqlandı.',
'emailnotauthenticated' => 'E-mail adresiñiz tasdıqlanmadı, vikiniñ e-mail ile bağlı funktsiyaları çalışmaycaq.',
'noemailprefs' => 'Bu funktsiyalarnıñ çalışması içün sazlamalarıñızda bir e-mail adresi bildiriñiz.',
'emailconfirmlink' => 'E-mail adresiñizni tasdıqlañız',
'invalidemailaddress' => 'Yazğan adresiñiz e-mail standartlarında olmağanı içün qabul etilmedi. Lütfen, doğru adresni yazıñız ya da qutunı boş qaldırıñız.',
'accountcreated' => 'Esap açıldı',
'accountcreatedtext' => '$1 içün bir qullanıcı esabı açıldı.',
'createaccount-title' => '{{SITENAME}} saytında yañı bir esap yaratıluvı',
'createaccount-text' => 'Birev siziñ e-mail adresini bildirip {{SITENAME}} saytında ($4) "$2" adlı bir esap yarattı.
Şu esap içün parol budır: "$3".
Siz şimdi oturım açıp paroliñizni deñiştirmek kereksiñiz.

Şu esap hata olaraq yaratılğan olsa bu mektüpke qulaq asmayıp olasıñız.',
'usernamehasherror' => 'Qullanıcı adında # işareti olamaz',
'login-throttled' => 'Yaqın zamanda pek çoq kere kirmege tırıştıñız.
Lütfen, qayta kirmezden evel biraz bekleñiz.',
'loginlanguagelabel' => 'Til: $1',
'suspicious-userlogout' => 'Çıqış istegeniñiz red etildi, çünki bozuq bir brauzer ya da keşleyici proksi tarafından yollanğan kibi körüne.',

# Change password dialog
'resetpass' => 'Parolni deñiştir',
'resetpass_announce' => 'Muvaqqat kod vastasınen kirdiñiz. Kirişni tamamlamaq içün yañı parolni mında qoyuñız:',
'resetpass_header' => 'Esapnıñ parolini deñiştir',
'oldpassword' => 'Eski parol',
'newpassword' => 'Yañı parol',
'retypenew' => 'Yañı parolni tekrar yazıñız',
'resetpass_submit' => 'Parol qoyıp kir',
'changepassword-success' => 'Paroliñiz muvafaqiyetnen deñiştirildi! Oturımıñız açılmaqta...',
'resetpass_forbidden' => 'Parol deñiştirmek yasaq',
'resetpass-no-info' => 'Bu saifege doğrudan irişmek içün oturım açmaq kereksiñiz.',
'resetpass-submit-loggedin' => 'Parolni deñiştir',
'resetpass-submit-cancel' => 'Lâğu',
'resetpass-wrong-oldpass' => 'Ruhsetsiz muvaqqat ya da şimdiki parоl.
Parоliñizni endi muvafaqiyetnen deñiştirdiñiz ya da yañı bir muvaqqat parоl istediñiz.',
'resetpass-temp-password' => 'Muvaqqat parol:',

# Edit page toolbar
'bold_sample' => 'Qalın yazılış',
'bold_tip' => 'Qalın yazılış',
'italic_sample' => 'İtalik (kursiv) yazılış',
'italic_tip' => 'İtalik (kursiv) yazılış',
'link_sample' => 'Saifeniñ serlevası',
'link_tip' => 'İçki bağlantı',
'extlink_sample' => 'http://www.example.com saifeniñ serlevası',
'extlink_tip' => 'Tış bağlantı (Adres ögüne http:// qoymağa unutmañız)',
'headline_sample' => 'Serleva yazısı',
'headline_tip' => '2-nci seviye serleva',
'nowiki_sample' => 'Serbest format metiniñizni mında yazıñız.',
'nowiki_tip' => 'viki format etüvini ignor et',
'image_sample' => 'Resim.jpg',
'image_tip' => 'Endirilgen fayl',
'media_sample' => 'Ses.ogg',
'media_tip' => 'Media faylına bağlantı',
'sig_tip' => 'İmzañız ve tarih',
'hr_tip' => 'Gorizontal sızıq (pek sıq qullanmañız)',

# Edit pages
'summary' => 'Deñiştirmeniñ qısqa tarifi:',
'subject' => 'Mevzu/serleva:',
'minoredit' => 'Bu, kiçik deñiştirmedir',
'watchthis' => 'Saifeni közet',
'savearticle' => 'Saifeni saqla',
'preview' => 'Baqıp çıquv',
'showpreview' => 'Baqıp çıq',
'showlivepreview' => 'Tez baqıp çıquv',
'showdiff' => 'Deñiştirmelerni köster',
'anoneditwarning' => "'''Diqqat''': Saytqa kirmegeniñizden sebep deñiştirmeler tarihına siziñ IP adresiñiz yazılır.",
'anonpreviewwarning' => 'Saytqa kirmediñiz. Saifeni saqlasañız deñiştirmeler tarihına siziñ IP adresiñiz yazılır.',
'missingsummary' => "'''Hatırlatma.''' Deñiştirmeleriñizni qısqadan tarif etmediñiz. \"Saifeni saqla\" dögmesine tekrar basuv ile deñiştirmeleriñiz tefsirsiz saqlanacaqlar.",
'missingcommenttext' => 'Lütfen, aşağıda tefsir yazıñız.',
'missingcommentheader' => "'''Hatırlatma:''' Tefsir mevzusını/serlevasını yazmadıñız. \"{{int:savearticle}}\" dögmesine tekrar basqan soñ tefsiriñiz serlevasız saqlanır.",
'summary-preview' => 'Baqıp çıquv tasviri:',
'subject-preview' => 'Baqıp çıquv serlevası:',
'blockedtitle' => 'Qullanıcı blok etildi.',
'blockedtext' => '\'\'\'Esabıñız ya da IP adresiñiz blok etildi.\'\'\'

Blok yapqan idareci: $1.
Blok sebebi: \'\'"$2"\'\'.

* Bloknıñ başı: $8
* Bloknıñ soñu: $6
* Blok etilgen: $7

Blok etüvni muzakere etmek içün $1 qullanıcısına ya da başqa er angi [[{{MediaWiki:Grouppage-sysop}}|idarecige]] mektüp yollap olasıñız.
Diqqat etiñiz ki, qayd olunmağan ve e-mail adresiñizni [[Special:Preferences|şahsiy sazlamalarda]] tasdıqlamağan alda, em de blok etilgende sizge mektüp yollamaq yasaq etilgen olsa, idarecige mektüp yollap olamazsıñız.
IP adresiñiz — $3, blok etüv identifikatorı — #$5. Lütfen, idarecilerge mektüpleriñizde bu malümatnı bildiriñiz.',
'autoblockedtext' => 'IP adresiñiz evelde blok etilgen qullanıcılardan biri tarafından qullanılğanı içün avtomatik olaraq blok etildi. Onı blok etken idareci ($1) böyle sebepni bildirdi:

:"$2"

* Bloknıñ başı: $8
* Bloknıñ soñu: $6
* Blok etilgen: $7

Blok etüvni muzakere etmek içün $1 qullanıcısına ya da başqa er angi [[{{MediaWiki:Grouppage-sysop}}|idarecige]] mektüp yollap olasıñız.
Diqqat etiñiz ki, qayd olunmağan ve e-mail adresiñizni [[Special:Preferences|şahsiy sazlamalarda]] tasdıqlamağan alda, em de blok etilgende sizge mektüp yollamaq yasaq etilgen olsa, idarecige mektüp yollap olamazsıñız.
IP adresiñiz — $3, blok etüv identifikatorı — #$5. Lütfen, idarecilerge mektüpleriñizde onı bildiriñiz.',
'blockednoreason' => 'sebep bildirilmedi',
'whitelistedittext' => 'Saifeni deñiştirmek içün $1 kereksiñiz.',
'confirmedittext' => 'Saifeni deñiştirmeden evel e-mail adresiñizni tasdıqlamalısıñız. Lütfen, [[Special:Preferences|sazlamalar saifesinde]] e-mail adresiñizni kirsetiñiz ve tasdıqlañız.',
'nosuchsectiontitle' => 'Bölük tapılamadı',
'nosuchsectiontext' => 'Bar olmağan bölükni deñiştirip baqtıñız.
Siz saifeni oquğanda o avuştırılğan ya da yoq etilgen ola bile.',
'loginreqtitle' => 'Oturım açmalısıñız',
'loginreqlink' => 'kiriş',
'loginreqpagetext' => 'Başqa saifelerni baqmaq içün $1 borclusıñız.',
'accmailtitle' => 'Parol yollandı',
'accmailtext' => "[[User talk:$1|$1]] içün tesadufiy işaretlerden yaratılğan parol $2 adresine yollandı.

Bu yañı esap içün parol, kiriş yapqandan soñ ''[[Special:ChangePassword|parolni deñiştir]]'' bölüginde deñiştirilip olur.",
'newarticle' => '(Yañı)',
'newarticletext' => "Siz bu bağlantınen şimdilik yoq olğan saifege avuştıñız. Yañı bir saife yaratmaq içün aşağıda bulunğan pencerege metin yazıñız (tafsilâtlı malümat almaq içün [[{{MediaWiki:Helppage}}|yardım saifesine]] baqıñız). Bu saifege tesadüfen avuşqan olsañız, brauzeriñizdeki '''keri''' dögmesine basıñız.",
'anontalkpagetext' => "----''Bu muzakere saifesi şimdilik qayd olunmağan ya da oturımını açmağan adsız (anonim) qullanıcığa mensüptir. İdentifikatsiya içün IP adres işletile.
Bir IP adresinden bir qaç qullanıcı faydalanıp ola.
Eger siz anonim qullanıcı olsañız ve sizge kelgen beyanatlarnı yañlıştan kelgenini belleseñiz, lütfen, artıq bunıñ kibi qarışıqlıq olmasın dep [[Special:UserLogin/signup|qayd oluñız]] ya da [[Special:UserLogin|oturım açıñız]].''",
'noarticletext' => 'Bu saife şimdi boştır. Bu serlevanı başqa saifelerde [[Special:Search/{{PAGENAME}}|qıdırıp olasıñız]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} bağlı jurnal qaydlarını qıdırıp olasıñız] ya da bu saifeni özüñiz [{{fullurl:{{FULLPAGENAME}}|action=edit}} yazıp olasıñız]</span>.',
'noarticletext-nopermission' => 'Bu saife şimdi boştır. Bu serlevanı başqa saifelerde [[Special:Search/{{PAGENAME}}|qıdıra bile]] ya da <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} bağlı jurnallarnı közden keçire bilesiñiz]</span>.',
'userpage-userdoesnotexist' => '"<nowiki>$1</nowiki>" adlı qullanıcı yoqtır. Tamam bu saifeni deñiştirmege istegeniñizni teşkeriñiz.',
'userpage-userdoesnotexist-view' => '"$1" adlı qullanıcı esabı yoq.',
'blocked-notice-logextract' => 'Bu qullanıcı şimdi blok etilgen.
Blok etüv jurnalınıñ soñki yazısı aşağıda kösterilgen:',
'clearyourcache' => "'''İhtar:''' Belki sazlamalarıñıznı saqlağandan soñ deñişmelerni körmek içün brauzeriñizniñ keşini temizlemek kerek olursıñız. Keş temizlemek içün şunı yapmaq kerek:
* '''Firefox / Safari:''' ''Shift'' basıp saifeni yañıdan yüklemek ya da ''Ctrl-F5'' ya da ''Ctrl-R'' basmaq (Mac içün ''⌘-R'')
* '''Google Chrome:''' ''Ctrl-Shift-R'' basmaq (Mac içün ''⌘-Shift-R'')
* '''Internet Explorer:''' ''Ctrl'' basıp saifeni yañıdan yüklemek ya da ''Ctrl-F5'' basmaq
* '''Opera:''' ''Tools → Preferences'' menüsinde keşni temizlemek",
'usercssyoucanpreview' => "'''Tevsiye:''' Yañı CSS faylını teşkermek içün saifeni saqlamazdan evel \"{{int:showpreview}}\" dögmesine basıñız.",
'userjsyoucanpreview' => "'''Tevsiye:''' Yañı JavaScript-iñizni teşkermek içün saifeni saqlamazdan evel \"{{int:showpreview}}\" dögmesine basıñız.",
'usercsspreview' => "'''Unutmañız, bu tek baqıp çıquv - qullanıcı CSS faylıñız alâ daa saqlanmadı!'''",
'userjspreview' => "'''Unutmañız, siz şimdi tek test etesiñiz ya da baqıp çıquv köresiñiz - qullanıcı JavaScript'i şimdilik saqlanmadı.'''",
'userinvalidcssjstitle' => "'''İhtar:''' \"\$1\" adınen bir tema yoqtır. tema-adı.css ve .js fayllarınıñ adları kiçik afir ile yazmaq kerek, yani {{ns:user}}:Temel/'''V'''ector.css degil, {{ns:user}}:Temel/'''v'''ector.css.",
'updated' => '(Yañardı)',
'note' => "'''İhtar:'''",
'previewnote' => "'''Bu tek baqıp çıquv, metin alâ daa saqlanmağan!'''",
'previewconflict' => 'Bu baqıp çıquv yuqarı tarir penceresindeki metinniñ saqlanuvdan soñ olacaq körünişini aks ete.',
'session_fail_preview' => "''' Server siz yapqan deñiştirmelerni sessiya identifikatorı
coyulğanı sebebinden saqlap olamadı. Bu vaqtınca problemadır. Lütfen, tekrar saqlap baqıñız.
Bundan da soñ olıp çıqmasa, malümat lokal faylğa saqlañız da brauzeriñizni bir qapatıp
açıñız.'''",
'session_fail_preview_html' => "'''Afu etiñiz! HTML sessiyanıñ malümatları ğayıp olğanı sebebinden siziñ deñiştirmeleriñizni qabul etmege imkân yoqtır.'''",
'token_suffix_mismatch' => "'''Siziñ programmañıznıñ öz türlendirüv penceresinde punktuatsiya işaretlerini doğru işlemegeni içün yapqan deñiştirmeleriñiz qabul olunmadı. Deñiştirmeler saife metniniñ körünişi bozulmasın dep lâğu etildi.
Bunıñ kibi problemalar hatalı anonim web-proksiler qullanuvdan çıqıp ola.'''",
'editing' => '"$1" saifesini deñiştireyatasız',
'editingsection' => '"$1" saifesinde bölük deñiştireyatasız',
'editingcomment' => '$1 saifesini deñiştireyatasız (yañı bölük)',
'editconflict' => 'Deñiştirmeler çatışması: $1',
'explainconflict' => "Siz saifeni deñiştirgende başqa biri de deñiştirme yaptı.
Yuqarıdaki yazı saifeniñ şimdiki alını köstere.
Siziñ deñiştirmeleriñiz astında kösterildi. Şimdi yapqan deñiştirmeleriñizni aşağı pencereden yuqarı pencerege avuştırmaq kereksiñiz.
\"{{int:savearticle}}\"ğa basqanda '''tek''' yuqarıdaki yazı saqlanacaq.",
'yourtext' => 'Siziñ metniñiz',
'storedversion' => 'Saqlanğan metin',
'nonunicodebrowser' => "'''TENBİ: Brauzeriñizde Unicode kodlaması tanılmaz. Saifeler deñiştirgende bütün ASCII olmağan işaretlerniñ yerine olarnıñ onaltılıq kodu yazılır.'''",
'editingold' => "'''DİQQAT: Saifeniñ eski bir versiyasını deñiştireyatasız.
Saifeni saqlağanıñızdan soñ bu tarihlı versiyadan künümizge qadar olğan deñiştirmeler yoq olacaq.'''",
'yourdiff' => 'Farqlar',
'copyrightwarning' => "'''Lütfen, diqqat:''' {{SITENAME}} saytına qoşulğan bütün isseler $2 muqavelesi dairesindedir (tafsilât içün $1 saifesine baqıñız).
Qoşqan isseñizniñ başqa insanlar tarafından acımasızca deñiştirilmesini ya da azat tarzda ve sıñırsızca başqa yerlerge dağıtılmasını istemeseñiz, isse qoşmañız.<br />
Ayrıca, mında isse qoşıp, bu isseniñ özüñiz tarafından yazılğanına, ya da cemaatqa açıq bir menbadan ya da başqa bir azat menbadan kopiyalanğanına garantiya bergen olasıñız.<br />
'''MÜELLİFLİK AQQINEN QORÇALANĞAN İÇ BİR METİNNİ MINDA RUHSETSİZ QOŞMAÑIZ!'''",
'copyrightwarning2' => "'''Lütfen, diqqat:''' {{SITENAME}} saytına siz qoşqan bütün isseler başqa bir qullanıcı tarafından deñiştirilip ya da yoq etilip olur. Qoşqan isseñizniñ başqa insanlar tarafından acımasızca deñiştirilmesini ya da azat tarzda ve sıñırsızca başqa yerlerge dağıtılmasını istemeseñiz, isse qoşmañız.<br />
Ayrıca, mında isse qoşıp, bu isseniñ özüñiz tarafından yazılğanına, ya da cemaatqa açıq bir menbadan ya da başqa bir azat menbadan kopiyalanğanına garantiya bergen olasıñız ($1 baqıñız).<br />
'''MÜELLİFLİK AQQINEN QORÇALANĞAN İÇ BİR METİNNİ MINDA RUHSETSİZ QOŞMAÑIZ!'''",
'longpageerror' => "'''TENBİ: Bu saife $1 kilobayt büyükligindedir. Azamiy (maksimal) izinli büyüklik ise $2 kilobayt. Bu saife saqlanıp olamaz.'''",
'readonlywarning' => "'''TENBİ: Baqım sebebi ile malümat bazası şimdi kilitlidir. Bu sebepten yapqan deñiştirmeleriñizni şimdi saqlap olamasıñız. Yazğanlarıñıznı vaqtınca bir tekst faylında saqlap ve daa soñra bir daa mında ketirip saqlap olursıñız'''

Malümat bazasını kilitlegen idareci öz areketini şöyle añlattı: $1",
'protectedpagewarning' => "'''Tenbi: Bu saife qorçalanğan ve tek idareciler tarafından deñiştirilip olur.'''
Jurnalnıñ soñki yazısı aşağıda berilgen:",
'semiprotectedpagewarning' => "'''Tenbi''': Bu saife tek qaydlı qullanıcılar tarafından deñiştirilip olur.
Jurnalnıñ soñki yazısı aşağıda berilgen:",
'cascadeprotectedwarning' => "'''Tenbi:''' Bu saifeni tek \"İdareciler\" gruppasına kirgen qullanıcılar deñiştirip olalar, çünki o kaskad qorçalav altında bulunğan {{PLURAL:\$1|saifege|saifelerge}} mensüptir:",
'titleprotectedwarning' => "'''Tenbi: Bu saife qorçalav altındadır, tek [[Special:ListGroupRights|mahsus aqlarğa]] saip qullanıcılar onı yaratıp ola.'''
Jurnalnıñ soñki yazısı aşağıda berilgen:",
'templatesused' => 'Bu saifede qullanılğan {{PLURAL:$1|şablon|şablonlar}}:',
'templatesusedpreview' => 'Siz baqıp çıqqan saifeñizde qullanılğan {{PLURAL:$1|şablon|şablonlar}}:',
'templatesusedsection' => 'Bu bölükte qullanılğan {{PLURAL:$1|şablon|şablonlar}}:',
'template-protected' => '(qorçalav altında)',
'template-semiprotected' => '(qısmen qorçalav altında)',
'hiddencategories' => 'Bu saife {{PLURAL:$1|1|$1}} gizli kategoriyağa mensüptir:',
'nocreatetext' => '{{SITENAME}} saytında yañı saife yaratuv sıñırlıdır.
Keri qaytıp mevcut olğan saifeni deñiştire, [[Special:UserLogin|oturım aça ya da yañı bir esap yaratıp olasıñız]].',
'nocreate-loggedin' => 'Yañı saifeler yaratmağa iziniñiz yoqtır.',
'sectioneditnotsupported-title' => 'Bölüklerni deñiştirmek mümkün degil.',
'sectioneditnotsupported-text' => 'Bu saifede bölüklerni deñiştirmek mümkün degil.',
'permissionserrors' => 'İrişim aqlarınıñ hataları',
'permissionserrorstext' => 'Bunı yapmağa iziniñiz yoqtır. {{PLURAL:$1|Sebep|Sebepler}}:',
'permissionserrorstext-withaction' => 'Aşağıdaki {{PLURAL:$1|sebepten|sebeplerden}} $2 ruhsetiñiz yoq:',
'recreate-moveddeleted-warn' => "'''Tenbi: Evelce yoq etilgen saifeni yañıdan yaratasıñız.'''

Saifeni deñiştirmege devam etkeni uyğun olıp olmağanını tüşünmelisiñiz.
Saifeniñ yoq etilüv ve avuştırıluv qaydları mında berilgen:",
'moveddeleted-notice' => 'Bu saife yoq etilgen.
Saifeniñ yoq etilüv ve avuştırıluv qaydları aşağıda berilgen.',
'log-fulllog' => 'Jurnalnı tolusınca köster',
'edit-hook-aborted' => 'Deñiştirme çengel protsedurasınen toqtatıldı.
İç bir izaat berilmedi.',
'edit-gone-missing' => 'Saife yañartılıp olamay.
Belki o yoq etilgendir.',
'edit-conflict' => 'Deñiştirmeler çatışması.',
'edit-no-change' => 'Yapqan deñiştirmeñiz saqlanmağan, çünki metinde bir türlü deñiştirilme yapılmadı.',
'edit-already-exists' => 'Yañı saifeni yaratmaq mümkün degil.
O endi bar.',

# "Undo" feature
'undo-success' => 'Deñiştirme lâğu etile bile. Lütfen, mına bu deñiştirmelerni yapmağa istegeniñizden emin olmaq içün versiyalar teñeştirilüvini közden keçirip deñiştirmelerni saqlamaq içün "Saifeni saqla" dögmesine basıñız.',
'undo-failure' => 'Aradaki deñiştirmeler bir-birine kelişikli olmağanı içün deñiştirme lâğu etilip olamay.',
'undo-norev' => 'Deñiştirme lâğu etilip olamaz, çünki o ya da yoq, ya da bar edi, amma yoq etilgen.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|muzakere]]) qullanıcısınıñ $1 nomeralı deñiştirmesini lâğu etüv.',

# Account creation failure
'cantcreateaccounttitle' => 'Esap yaratmaqnıñ iç çaresi yoq.',
'cantcreateaccount-text' => "Bu IP adresinden ('''$1''') esap yaratuv [[User:$3|$3]] tarafından blok etildi.

$3 mına bu sebepni bildirdi: ''$2''",

# History pages
'viewpagelogs' => 'Bu saifeniñ jurnallarını köster',
'nohistory' => 'Bu saifeniñ keçmiş versiyası yoq.',
'currentrev' => 'Şimdiki versiya',
'currentrev-asof' => '$1 tarihında sоñki kere deñiştirilgen saifeniñ şimdiki alı',
'revisionasof' => 'Saifeniñ $1 tarihındaki alı',
'revision-info' => 'Saifeniñ $2 tarafından yazılğan $1 tarihındaki alı',
'previousrevision' => '← Evelki alı',
'nextrevision' => 'Soñraki alı →',
'currentrevisionlink' => 'eñ yañı alını köster',
'cur' => 'farq',
'next' => 'soñraki',
'last' => 'soñki',
'page_first' => 'ilk',
'page_last' => 'soñki',
'histlegend' => "Farq saylanuvı: Teñeştirmege istegen eki versiyañıznı saylap '''{{int:compare-submit}}''' dögmesine basıñız.<br />
Añlatmalar: '''({{int:cur}})''' = şimdiki versiyanen arasındaki farq, '''({{int:last}})''' = evelki versiyanen arasındaki farq, '''{{int:minoreditletter}}''' = kiçik deñiştirme.",
'history-fieldset-title' => 'Keçmişke baquv',
'history-show-deleted' => 'Tek yoq etilgenler',
'histfirst' => 'Eñ eski',
'histlast' => 'Eñ yañı',
'historysize' => '({{PLURAL:$1|1 bayt|$1 bayt}})',
'historyempty' => '(boş)',

# Revision feed
'history-feed-title' => 'Deñiştirmeler tarihı',
'history-feed-description' => 'Vikide bu saifeniñ deñiştirmeler tarihı',
'history-feed-item-nocomment' => '$2 üstünde $1',
'history-feed-empty' => 'İstenilgen saife yoq.
O yoq eilgen ya da adı deñiştirilgen ola bile.
Vikide bu saifege oşağan saifelerni [[Special:Search|tapıp baqıñız]].',

# Revision deletion
'rev-deleted-comment' => '(deñiştirmeniñ tarifi yoq etildi)',
'rev-deleted-user' => '(qullanıcı adı yoq etildi)',
'rev-deleted-event' => '(qayd yoq etildi)',
'rev-delundel' => 'köster/gizle',
'rev-showdeleted' => 'köster',
'revisiondelete' => 'Versiyalarnı yoq et/keri ketir',
'revdelete-hide-comment' => 'Qısqa tarifni kösterme',
'revdelete-hide-user' => 'Deñiştirmeni yapqannıñ qullanıcı adını/IP-ni gizle',
'revdelete-hide-restricted' => 'Malümatnı adiy qullanıcılardan kibi idarecilerden de gizle',
'revdelete-submit' => 'Saylanğan {{PLURAL:$1|versiyağa|versiyalarğa}} işlet',
'revdel-restore' => 'körünüvni deñiştir',

# Merge log
'revertmerge' => 'Ayır',
'mergelogpagetext' => 'Saifelerniñ keçmiş versiyalarınıñ bir-birlerinen eñ soñki birleştirilmeleri aşağıdaki cedvelde kösterilgen.',

# Diffs
'history-title' => '"$1" saifesiniñ deñiştirmeler tarihı',
'difference-multipage' => '(Saifeler arasındaki farq)',
'lineno' => '$1 satır:',
'compareselectedversions' => 'Saylanğan versiyalarnı teñeştir',
'showhideselectedversions' => 'Saylanğan versiyalarnı köster/gizle',
'editundo' => 'lâğu et',
'diff-multi' => '({{PLURAL:$2|Bir qullanıcı|$2 qullanıcı}}nıñ yapqan {{PLURAL:$1|bir ara versiyası|$1 ara versiyası}} kösterilmey)',
'diff-multi-manyusers' => '($2-den ziyade {{PLURAL:$2|qullanıcı|qullanıcı}}nıñ yapqan {{PLURAL:$1|bir ara versiyası|$1 ara versiyası}} kösterilmey)',

# Search results
'searchresults' => 'Qıdıruv neticeleri',
'searchresults-title' => '"$1" içün qıdıruv neticeleri',
'searchresulttext' => '{{SITENAME}} içinde qıdıruv yapmaq hususında malümat almaq içün [[{{MediaWiki:Helppage}}|{{int:help}}]] saifesine baqıp olasıñız.',
'searchsubtitle' => 'Qıdırılğan: \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|"$1" ile başlanğan bütün saifeler]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" saifesine bağlantı olğan bütün saifeler]])',
'searchsubtitleinvalid' => "Siz bunı qıdırdıñız '''$1'''",
'toomanymatches' => 'Pek çoq eşleşme çıqtı, lütfen, başqa bir soratma saylañız',
'titlematches' => 'Saife adı bir kele',
'notitlematches' => 'İç bir serlevada tapılamadı',
'textmatches' => 'Saife metni bir kele',
'notextmatches' => 'İç bir saifede tapılamadı',
'prevn' => 'evelki {{PLURAL:$1|$1}}',
'nextn' => 'soñraki {{PLURAL:$1|$1}}',
'prevn-title' => 'Evelki $1 {{PLURAL:$1|netice|netice}}',
'nextn-title' => 'Soñraki $1 {{PLURAL:$1|netice|netice}}',
'shown-title' => 'Saife başına $1 {{PLURAL:$1|netice|netice}} köster',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Qıdıruv sazlamaları',
'searchmenu-exists' => "'''Bu vikide \"[[:\$1]]\" adlı bir saife bar'''",
'searchmenu-new' => "'''Bu vikide \"[[:\$1]]\" saifesini yarat!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Bu prefiksli saifelerni köster]]',
'searchprofile-articles' => 'Malümat saifeleri',
'searchprofile-project' => 'Yardım ve leyha saifeleri',
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Er şey',
'searchprofile-advanced' => 'Tafsilâtlı',
'searchprofile-articles-tooltip' => '$1 içinde qıdır',
'searchprofile-project-tooltip' => '$1 içinde qıdır',
'searchprofile-images-tooltip' => 'Fayllarnı qıdır',
'searchprofile-everything-tooltip' => 'Bütün saifelerde (muzakere saifelerinde de) qıdır',
'searchprofile-advanced-tooltip' => 'Belgilengen isim fezalarında qıdır',
'search-result-size' => '$1 ({{PLURAL:$2|1|$2}} söz)',
'search-result-score' => 'Uyğunlıq: $1%',
'search-redirect' => '(yollama $1)',
'search-section' => '(bölük $1)',
'search-suggest' => 'Bunımı demege istediñiz: $1',
'search-interwiki-caption' => 'Qardaş leyhalar',
'search-interwiki-default' => '$1 netice:',
'search-interwiki-more' => '(daa çоq)',
'search-relatedarticle' => 'Bağlı',
'mwsuggest-disable' => 'AJAX tevsiyelerini işletme',
'searcheverything-enable' => 'Bütün isim fezalarında qıdır',
'searchrelated' => 'bağlı',
'searchall' => 'episi',
'showingresults' => "Aşağıda №&nbsp;<strong>$2</strong>den başlap {{PLURAL:$1|'''1''' netice|'''$1''' netice}} buluna.",
'showingresultsnum' => "Aşağıda №&nbsp;'''$2'''den başlap {{PLURAL:$3|'''1''' netice|'''$3''' netice}} buluna.",
'nonefound' => "'''İhtar.''' Adiycesine qıdıruv bütün isim fezalarında yapılmay. Bütün isim fezalarında (bu cümleden qullanıcılar subetleri, şablonlar ve ilâhre) qıdırmaq içün ''all:'' yazını qullanıñız, muayyen bir isim fezasında qıdırmaq içün ise ''ad:'' formatında onıñ adını yazıñız.",
'search-nonefound' => 'Soratmanen eşleşken bir netice yoq.',
'powersearch' => 'Qıdır',
'powersearch-legend' => 'Tafsilâtlı qıdıruv',
'powersearch-ns' => 'Bu isim fezalarında qıdır:',
'powersearch-redir' => 'Yollama saifelerini de köster',
'powersearch-field' => 'Qıdır:',
'powersearch-togglelabel' => 'Sayla:',
'powersearch-toggleall' => 'Episi',
'powersearch-togglenone' => 'İç biri',
'search-external' => 'Tış qıdıruv',
'searchdisabled' => '{{SITENAME}} saytında qıdıruv yapma vaqtınca toqtatıldı. Bu arada Google qullanıp {{SITENAME}} içinde qıdıruv yapıp olasıñız. Qıdıruv saytlarında indekslemeleriniñ biraz eski qalğan ola bilecegini köz ögüne alıñız.',

# Preferences page
'preferences' => 'Sazlamalar',
'mypreferences' => 'Sazlamalar',
'prefs-edits' => 'Deñiştirmeler sayısı:',
'prefsnologin' => 'Oturım açmadıñız',
'prefsnologintext' => 'Şahsiy sazlamalarıñıznı deñiştirmek içün <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} oturım açmaq]</span> kereksiñiz.',
'changepassword' => 'Parol deñiştir',
'prefs-skin' => 'Resimleme',
'skin-preview' => 'Baqıp çıquv',
'datedefault' => 'Standart',
'prefs-datetime' => 'Tarih ve saat',
'prefs-personal' => 'Qullanıcı malümatı',
'prefs-rc' => 'Soñki deñiştirmeler',
'prefs-watchlist' => 'Közetüv cedveli',
'prefs-watchlist-days' => 'Közetüv cedvelinde kösterilecek kün sayısı:',
'prefs-watchlist-days-max' => 'Eñ çoq $1 {{PLURAL:$1|kün|kün}}',
'prefs-watchlist-edits' => 'Kenişletilgen közetüv cedvelinde kösterilecek deñiştirmeler sayısı:',
'prefs-watchlist-edits-max' => 'Eñ çoq 1000',
'prefs-watchlist-token' => 'Közetüv cedveli işareti:',
'prefs-misc' => 'Diger sazlamalar',
'prefs-resetpass' => 'Parolni deñiştir',
'prefs-email' => 'E-mail sazlamaları',
'prefs-rendering' => 'Körüniş',
'saveprefs' => 'Saqla',
'resetprefs' => 'Saqlanmağan sazlamalarnı ilk alına ketir',
'restoreprefs' => 'Bütün ög belgilengen sazlamalarnı qaytar',
'prefs-editing' => 'Saifelerni deñiştirüv',
'rows' => 'Satır',
'columns' => 'Sutun',
'searchresultshead' => 'Qıdıruv',
'resultsperpage' => 'Saifede kösterilecek tapılğan saife sayısı',
'recentchangesdays' => 'Soñki deñiştirmeler saifesinde kösterilecek kün sayısı:',
'recentchangesdays-max' => '(eñ çoq $1 {{PLURAL:$1|kün|kün}})',
'recentchangescount' => 'Ög belgilengen kösterilecek deñiştirmeler sayısı:',
'prefs-help-recentchangescount' => 'Bu, soñki deñiştirmeler, saife keçmişi ve jurnal saifelerinde qullanıla.',
'savedprefs' => 'Sazlamalarıñız saqlandı.',
'timezonelegend' => 'Saat quşağı:',
'localtime' => 'Yerli vaqıt:',
'timezoneuseserverdefault' => 'Serverniñ sazlamaları qullanılsın ($1)',
'timezoneuseoffset' => 'Başqa (farqnı kirsetiñiz)',
'timezoneoffset' => 'Saat farqı¹:',
'servertime' => 'Serverniñ saatı:',
'guesstimezone' => 'Brauzeriñiz siziñ yeriñizge köre toldursın',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarktika',
'timezoneregion-arctic' => 'Arktika',
'timezoneregion-asia' => 'Asiya',
'timezoneregion-atlantic' => 'Atlantik okean',
'timezoneregion-australia' => 'Avstraliya',
'timezoneregion-europe' => 'Avropa',
'timezoneregion-indian' => 'İnd okeanı',
'timezoneregion-pacific' => 'Tınç okean',
'allowemail' => 'Diger qullanıcılar maña e-mail mektüpleri yollap olsun',
'prefs-searchoptions' => 'Qıdıruv sazlamaları',
'prefs-namespaces' => 'İsim fezaları',
'defaultns' => 'Akis alda bu isim fezalarında qıdır:',
'default' => 'original',
'prefs-files' => 'Fayllar',
'prefs-reset-intro' => 'Bu saifeni sazlamalarıñıznı sayt ög belgilengenine qaytarmaq içün qullana bilesiñiz. Bu lâğu etilip olamaz.',
'prefs-emailconfirm-label' => 'E-mail tasdıqlanması:',
'youremail' => 'E-mail adresiñiz:',
'username' => 'Qullanıcı adı:',
'uid' => 'Qayd nomeri:',
'prefs-memberingroups' => 'Azası olğan {{PLURAL:$1|gruppa|gruppalar}}:',
'prefs-registration' => 'Qayd tarihı:',
'yourrealname' => 'Kerçek adıñız:',
'yourlanguage' => 'İnterfeys tili:',
'yourvariant' => 'Münderice tiliniñ variantı:',
'yournick' => 'Yañı imzañız:',
'prefs-help-signature' => 'Muzakere saifelerindeki tefsirlerni "<nowiki>~~~~</nowiki>" ile imzalamaq kerek, bu dört tilda yerine imzañız ve vaqıt kösterilir.',
'badsig' => 'Yañlış imza. HTML tegleriniñ doğrulığını baqıñız.',
'badsiglength' => 'Qarardan ziyade uzun imzadır, {{PLURAL:$1|1|$1}} ziyade işaretten ibaret olması mümkün degil.',
'yourgender' => 'Cınsıñız:',
'gender-unknown' => 'Bildirilmegen',
'gender-male' => 'Erkek',
'gender-female' => 'Qadın',
'prefs-help-gender' => 'Mecburiy degil: wiki tarafından doğru cınıs adreslevi içün qullanıla. Bu malümat umumiy olacaq.',
'email' => 'E-mail',
'prefs-help-realname' => 'Kerçek adıñız (mecburiy degildir).
Eger bildirseñiz, saifelerdeki deñiştirmelerni kimniñ yapqanını köstermek içün qullanılacaq.',
'prefs-help-email' => 'E-mail (mecburiy degildir). E-mail adresi bildirilgen olsa, paroliñizni unutsañız, sizge yañı bir parol yollamaq içün qullanılır.',
'prefs-help-email-required' => 'E-mail adresi lâzim.',
'prefs-info' => 'Esas malümat',
'prefs-i18n' => 'Halqlararasılaştıruv',
'prefs-signature' => 'İmza',
'prefs-dateformat' => 'Tarih formatı',
'prefs-timeoffset' => 'Zaman farqı',
'prefs-advancedediting' => 'İlâve sazlamalar',
'prefs-advancedrc' => 'İlâve sazlamalar',
'prefs-advancedrendering' => 'İlâve sazlamalar',
'prefs-advancedsearchoptions' => 'İlâve sazlamalar',
'prefs-advancedwatchlist' => 'İlâve sazlamalar',
'prefs-displayrc' => 'Kösterilüv sazlamaları',
'prefs-displaysearchoptions' => 'Kösterilüv sazlamaları',
'prefs-displaywatchlist' => 'Kösterilüv sazlamaları',
'prefs-diffs' => 'Farqlar',

# User rights
'userrights' => 'Qullanıcı aqlarını idare etüv',
'userrights-lookup-user' => 'Qullanıcı gruppalarını idare et',
'userrights-user-editname' => 'Öz qullanıcı adıñıznı yazıñız:',
'editusergroup' => 'Qullanıcı gruppaları nizamla',
'editinguser' => "'''[[User:$1|$1]]''' $2 qullanıcısınıñ izinlerini deñiştireyatasız",
'userrights-editusergroup' => 'Qullanıcı gruppaları nizamla',
'saveusergroups' => 'Qullanıcı gruppalarını saqla',
'userrights-groupsmember' => 'Azası оlğan gruppalarıñız:',

# Groups
'group' => 'Gruppa:',
'group-user' => 'Qullanıcılar',
'group-autoconfirmed' => 'Avtomatik tasdıqlanğan qullanıcılar',
'group-bot' => 'Botlar',
'group-sysop' => 'İdareciler',
'group-bureaucrat' => 'Bürokratlar',
'group-suppress' => 'Teftişçiler',
'group-all' => '(episi)',

'group-user-member' => '{{GENDER:$1|qullanıcı}}',
'group-autoconfirmed-member' => '{{GENDER:$1|avtomatik tasdıqlanğan qullanıcı}}',
'group-bot-member' => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|idareci}}',
'group-bureaucrat-member' => '{{GENDER:$1|bürokrat}}',
'group-suppress-member' => '{{GENDER:$1|teftişçi}}',

'grouppage-user' => '{{ns:project}}:Qullanıcılar',
'grouppage-autoconfirmed' => '{{ns:project}}:Avtomatik tasdıqlanğan qullanıcılar',
'grouppage-bot' => '{{ns:project}}:Botlar',
'grouppage-sysop' => '{{ns:project}}:İdareciler',
'grouppage-bureaucrat' => '{{ns:project}}:Bürokratlar',
'grouppage-suppress' => '{{ns:project}}:Teftişçiler',

# Special:Log/newusers
'newuserlogpage' => 'Yañı qullanıcı jurnalı',
'newuserlogpagetext' => 'Eñ sоñki qayd оlğan qullanıcı jurnalı.',

# User rights log
'rightslog' => 'Qullanıcınıñ aqları jurnalı',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'bu saifeni deñiştirmege',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|deñiştirme}}',
'recentchanges' => 'Soñki deñiştirmeler',
'recentchanges-legend' => 'Soñki deñiştirmeler sazlamaları',
'recentchanges-summary' => 'Yapılğan eñ soñki deñişitirmelerni bu saifede körip olasıñız.',
'recentchanges-feed-description' => 'Bu lenta vastasınen vikide soñki deñiştirmelerni közet.',
'recentchanges-label-newpage' => 'Bu deñiştirme yañı bir saife yarattı',
'recentchanges-label-minor' => 'Bu, kiçik bir deñiştirme',
'recentchanges-label-bot' => 'Bu bir botnıñ yapqan deñiştirmesi',
'recentchanges-label-unpatrolled' => 'Bu deñiştirme alâ daa teşkerilmegen',
'rcnote' => "$4 $5 tarihında soñki {{PLURAL:$2|künde|'''$2''' künde}} yapılğan '''{{PLURAL:$1|1|$1}}''' deñiştirme:",
'rcnotefrom' => "'''$2''' tarihından itibaren yapılğan deñiştirmeler aşağıdadır (eñ çоq '''$1''' dane saife kösterile).",
'rclistfrom' => '$1 tarihından berli yapılğan deñiştirmelerni köster',
'rcshowhideminor' => 'kiçik deñiştirmelerni $1',
'rcshowhidebots' => 'botlarnı $1',
'rcshowhideliu' => 'qaydlı qullanıcılarnı $1',
'rcshowhideanons' => 'anonim qullanıcılarnı $1',
'rcshowhidepatr' => 'közetilgen deñiştirmelerni $1',
'rcshowhidemine' => 'menim yapqan deñiştirmelerimni $1',
'rclinks' => 'Soñki $2 künde yapılğan soñki $1 deñiştirmeni köster;<br /> $3',
'diff' => 'farq',
'hist' => 'keçmiş',
'hide' => 'gizle',
'show' => 'köster',
'minoreditletter' => 'k',
'newpageletter' => 'Y',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|qullanıcı|qullanıcı}} közete]',
'rc_categories' => 'Tek kategoriyalardan ("|" ile ayırıla)',
'rc_categories_any' => 'Er angi',
'newsectionsummary' => '/* $1 */ yañı bölük',
'rc-enhanced-expand' => 'Tafsilâtını köster (JavaScript kerek)',
'rc-enhanced-hide' => 'Tafsilâtını gizle',

# Recent changes linked
'recentchangeslinked' => 'Bağlı deñiştirmeler',
'recentchangeslinked-feed' => 'Bağlı deñiştirmeler',
'recentchangeslinked-toolbox' => 'Bağlı deñiştirmeler',
'recentchangeslinked-title' => '"$1" ile bağlı deñiştirmeler',
'recentchangeslinked-summary' => "Bu mahsus saifede bağlı saifelerde soñki yapılğan deñiştirmeler cedveli bar. [[Special:Watchlist|Közetüv cedveliñiz]]deki saifeler '''qalın''' olaraq kösterile.",
'recentchangeslinked-page' => 'Saife adı:',
'recentchangeslinked-to' => 'Berilgen saife yerine berilgen saifege bağlantı bergen olğan saifelerni köster',

# Upload
'upload' => 'Fayl yükle',
'uploadbtn' => 'Fayl yükle',
'reuploaddesc' => 'Yükleme formasına keri qayt.',
'upload-tryagain' => 'Deñiştirilgen fayl tarifini yolla',
'uploadnologin' => 'Oturım açmadıñız',
'uploadnologintext' => 'Fayl yüklep olmaq içün [[Special:UserLogin|oturım açmaq]] kereksiñiz.',
'upload_directory_missing' => 'Yüklemeler içün direktoriya ($1) mevcut degil ve veb-server tarafından yapılıp olamay.',
'upload_directory_read_only' => 'Web serverniñ ($1) cüzdanına fayllar saqlamağa aqları yoqtır.',
'uploaderror' => 'Yükleme hatası',
'uploadtext' => "Fayllar yüklemek içün aşağıdaki formanı qullanıñız.
Evelce yüklengen resim tapmaq ya da baqmaq içün [[Special:FileList|yüklengen fayllar cedveline]] keçiñiz, bundan ğayrı fayl yüklenüv ve yoq etilüv qaydlarını [[Special:Log/upload|yüklenüv jurnalında]] ve [[Special:Log/delete|yoq etilüv jurnalında]] tapıp olasıñız.

Saifede resim qullanmaq içün böyle şekilli bağlantılar qullanıñız:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' faylnıñ tam versiyasını qullanmaq içün,
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|tarif]]</nowiki></code>''' bir tarif ile 200 piksel bir resim qullanmaq içün,
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' faylğa vastasız bağlantı içün.",
'upload-permitted' => 'İzinli fayl çeşitleri: $1.',
'upload-preferred' => 'İstenilgen fayl çeşitleri: $1.',
'upload-prohibited' => 'Yasaqlı fayl çeşitleri: $1.',
'uploadlog' => 'yükleme jurnalı',
'uploadlogpage' => 'Fayl yükleme jurnalı',
'uploadlogpagetext' => 'Aşağıda eñ soñki qоşulğan fayllarnıñ cedveli buluna.
Daa körgezmeli körüniş içün [[Special:NewFiles|yañı fayllar galereyasına]] baqıñız.',
'filename' => 'Fayl',
'filedesc' => 'Faylğa ait qısqa tarif',
'fileuploadsummary' => 'Qısqa tarif:',
'filereuploadsummary' => 'Faylnıñ deñiştirilmeleri:',
'filestatus' => 'Tarqatuv şartları:',
'filesource' => 'Menba:',
'uploadedfiles' => 'Yüklengen fayllar',
'ignorewarning' => 'Tenbige qulaq asmayıp faylnı yükle.',
'ignorewarnings' => 'Tenbilerge qulaq asma',
'minlength1' => 'Faylnıñ adı eñ azdan bir ariften ibaret olmalı.',
'illegalfilename' => '"$1" faylınıñ adında serleva içün yasaqlı işaretler bar. Lütfen, fayl adını deñiştirip yañıdan yüklep baqıñız.',
'badfilename' => 'Fayl adı $1 olaraq deñiştirildi.',
'filetype-badmime' => '"$1" MIME çeşitindeki fayllar yükleme yasaqlıdır.',
'filetype-bad-ie-mime' => 'Bu fayl yüklenip olamaz, çünki Internet Explorer onı "$1" yani ruhset berilmegen ve zararlı ola bilgen fayl dep belleycek.',
'filetype-unwanted-type' => "'''\".\$1\"''' — istenilmegen fayl çeşiti.
İstenilgen {{PLURAL:\$3|fayl çeşiti|fayl çeşitleri}}: \$2.",
'filetype-banned-type' => "'''\".\$1\"''' — yasaqlı fayl çeşiti.
İstenilgen {{PLURAL:\$3|fayl çeşiti|fayl çeşitleri}}: \$2.",
'filetype-missing' => 'Faylnıñ iç bir uzantısı yoq (meselâ ".jpg", ".gif" ve ilh.).',
'large-file' => 'Büyükligi $1 bayttan ziyade ibaret olmağan resimler qullanuv tevsiye etile (bu faylnıñ büyükligi $2 bayt).',
'largefileserver' => 'Bu faylnıñ uzunlığı serverde izin berilgenden büyükçedir.',
'emptyfile' => 'İhtimal ki, yüklengen fayl boş. İhtimallı sebep - fayl adlandıruv
hatasıdır. Lütfen, tamam bu faylnı yüklemege isteycek ekeniñizni teşkeriñiz.',
'fileexists' => 'Bu isimde bir fayl endi bar.
Lütfen, eger siz deñiştirmekten emin olmasañız başta <strong>[[:$1]]</strong> faylına köz taşlañız.
[[$1|thumb]]',
'filepageexists' => 'Bu fayl içün tasvir saifesi endi yapılğan (<strong>[[:$1]]</strong>), lâkin bu adda bir fayl yoqtır.
Yazılğan tasviriñiz fayl saifesinde kösterilmeycek.
Tasviriñiz anda kösterilecegi içün, bunı qolnen deñiştirmek kereksiñiz.
[[$1|thumb]]',
'fileexists-extension' => 'Buña oşağan adda bir fayl bar: [[$2|thumb]]
* Yüklengen faylnıñ adı: <strong>[[:$1]]</strong>
* Mevcut olğan faylnıñ adı: <strong>[[:$2]]</strong>
Lütfen, başqa bir ad saylap yazıñız.',
'fileexists-thumbnail-yes' => 'Belki de bu fayl bir ufaqlaştırılğan kopiyadır (thumbnail). [[$1|thumb]]
Lütfen, <strong>[[:$1]]</strong> faylını teşkeriñiz.
Eger şu fayl aynı şu resim olsa, onıñ ufaqlaştırılğan kopiyasını ayrı olaraq yüklemek aceti yoqtır.',
'file-thumbnail-no' => "Faylnıñ adı <strong>$1</strong>nen başlana. Belki de bu resimniñ ufaqlaştırılğan bir kopiyasıdır ''(thumbnail)''.
Eger sizde bu resim tam büyükliginde bar olsa, lütfen, onı yükleñiñiz ya da faylnıñ adını deñiştiriñiz.",
'fileexists-forbidden' => 'Bu isimde bir fayl endi bar, ve üzerine yazılamay.
Faylıñıznı yañıdan yüklemege isteseñiz, lütfen, keri qaytıp yañı bir isim qullanıñız.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Fayllar umumiy tutulğan yerinde bu isimde bir fayl endi bar.
Eger bu faylnı ep bir yüklemege isteseñiz, keri qaytıñız ve fayl ismini deñiştirip yañıdan yükleñiz.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Bu fayl aşağıdaki {{PLURAL:$1|faylnıñ|fayllarnıñ}} dublikatı ola:',
'uploadwarning' => 'Tenbi',
'savefile' => 'Faylnı saqla',
'uploadedimage' => 'Yüklengen: "[[$1]]"',
'overwroteimage' => '"[[$1]]" yañı versiyası yüklendi',
'uploaddisabled' => 'Yükleme yasaqlıdır.',
'uploaddisabledtext' => 'Fayl yükleme yasaqlıdır.',
'uploadscripted' => 'Bu faylda brauzer tarafından yañlışnen işlenip olur HTML kodu ya da skript bar.',
'uploadvirus' => 'Bu fayl viruslıdır! $1 baqıñız',
'sourcefilename' => 'Yüklemege istegen faylıñız:',
'destfilename' => 'Faylnıñ istenilgen adı:',
'upload-maxfilesize' => 'Azamiy (maksimal) fayl büyükligi: $1',
'watchthisupload' => 'Bu faylnı közet',
'filewasdeleted' => 'Bu isimde bir fayl bar edi, amma yoq etilgen edi. Lütfen, tekrar yüklemeden evel $1 teşkeriñiz.',
'filename-bad-prefix' => "Siz yüklegen faylnıñ adı '''\"\$1\"'''-nen başlana. Bu, adetince, raqamlı fotoapparatlardan fayl adına yazılğan manasız işaretlerdir. Lütfen, bu fayl içün añlıca bir ad saylap yazıñız.",
'upload-success-subj' => 'Yüklenüv becerildi',

'upload-proto-error' => 'Yañlış protokol',
'upload-proto-error-text' => 'İnternetten bir resim faylı yüklemege isteseñiz adres <code>http://</code> ya da <code>ftp://</code>nen başlamalı.',
'upload-file-error' => 'İçki hata',
'upload-file-error-text' => 'Serverde muvaqqat fayl yaratılğan vaqıtta içki hata çıqtı. Lütfen, [[Special:ListUsers/sysop|idarecige]] muracaat etiñiz.',
'upload-misc-error' => 'Belgisiz yüklenüv hatası',
'upload-misc-error-text' => 'Belgisiz yüklenüv hatası. Lütfen, adresniñ doğru olğanını teşkerip tekrarlañız. Problema devam etse, [[Special:ListUsers/sysop|idarecige]] muracaat etiñiz.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL adresine irişilip olamadı.',
'upload-curl-error6-text' => 'Bildirilgen URL adresine irişilip olamadı. Lütfen, adresniñ doğru olğanı ve saytqa irişmekniñ çaresi olğanını teşkeriñiz.',
'upload-curl-error28' => 'Yüklenüv vaqtı toldı',
'upload-curl-error28-text' => 'Sayt çoqtan cevap qaytarmay. Lütfen, saytnıñ doğru çalışqanını teşkerip birazdan soñ tekrarlañız. Belki de istegen areketiñizni soñ, sayt boşça olğanda, etmek kerektir.',

'license' => 'Litsenziyalama:',
'license-header' => 'Litsenziyalama',
'nolicense' => 'Yoq',
'license-nopreview' => '(Baqıp çıquv irişilmez)',
'upload_source_url' => ' (doğru, püblik tarzda kirmege musaadeli internet adres)',
'upload_source_file' => ' (kompyuteriñizdeki fayl)',

# Special:ListFiles
'listfiles-summary' => 'Bu mahsus saife bütün yüklengen fayllarnı köstere.
Yaqınlarda yüklengen fayllar cedvelniñ yuqarısında kösterile.
Sutun serlevasına bir basuv sortirlemeniñ tertibini deñiştirir.',
'listfiles_search_for' => 'Fayl adını qıdıruv:',
'imgfile' => 'fayl',
'listfiles' => 'Resim cedveli',
'listfiles_date' => 'Tarih',
'listfiles_name' => 'Fayl adı',
'listfiles_user' => 'Qullanıcı',
'listfiles_size' => 'Büyüklik',
'listfiles_description' => 'Tasvir',
'listfiles_count' => 'Versiyalar',

# File description page
'file-anchor-link' => 'Fayl',
'filehist' => 'Faylnıñ keçmişi',
'filehist-help' => 'Faylnıñ kerekli anki alını körmek içün tarihqa/saatqa basıñız.',
'filehist-deleteall' => 'episini yoq et',
'filehist-deleteone' => 'yoq et',
'filehist-revert' => 'keri al',
'filehist-current' => 'şimdiki',
'filehist-datetime' => 'Tarih ve saat',
'filehist-thumb' => 'Kiçik resim',
'filehist-thumbtext' => '$1 tarihındaki versiyanıñ ufaqlaştırılğan alı',
'filehist-nothumb' => 'Ufaqlaştırılğan resim yoq',
'filehist-user' => 'Qullanıcı',
'filehist-dimensions' => 'En × boy',
'filehist-filesize' => 'Fayl büyükligi',
'filehist-comment' => 'İzaat',
'filehist-missing' => 'Fayl yoq',
'imagelinks' => 'Fayl bağlantıları',
'linkstoimage' => 'Bu faylğa bağlantı olğan {{PLURAL:$1|1|$1}} saife:',
'nolinkstoimage' => 'Bu faylğa bağlanğan saife yoq.',
'sharedupload' => 'Bu fayl $1 saytından ve diger leyhalarda da qullanılıp оla.',
'uploadnewversion-linktext' => 'Faylnıñ yañısını yükleñiz',
'shared-repo-from' => '$1nden',
'shared-repo' => 'ortaq tutulğan yeri',

# File reversion
'filerevert' => '$1 faylını eski alına qaytar',
'filerevert-legend' => 'Faylnı eski alına qaytar',
'filerevert-intro' => "'''[[Media:$1|$1]]''' faylınıñ [$4 $2, $3 tarihındaki versiyası]nı keri ketirmektesiñiz.",
'filerevert-comment' => 'Sebep:',
'filerevert-defaultcomment' => '$1, $2 tarihındaki versiyağa keri qaytarıldı',

# MIME search
'mimesearch' => 'MIME qıdıruvı',
'mimetype' => 'MIME çeşiti:',
'download' => 'yükle',

# Unwatched pages
'unwatchedpages' => 'Közetilmegen saifeler',

# List redirects
'listredirects' => 'Yollamalarnı cedvelge çek',

# Unused templates
'unusedtemplates' => 'Qullanılmağan şablonlar',
'unusedtemplatestext' => 'Bu saife {{ns:template}} isim fezasında bulunğan ve diger saifelerge kirsetilmegen şablonlarnı köstere. Şablonlarğa olğan diger bağlantılarnı da teşkermeden yoq etmeñiz.',
'unusedtemplateswlh' => 'diger bağlantılar',

# Random page
'randompage' => 'Tesadüfiy saife',
'randompage-nopages' => '"$1" {{PLURAL:$2|isim fezasında|isim fezalarında}} iç bir saife yoq.',

# Random redirect
'randomredirect' => 'Tesadüfiy yollama saifesi',
'randomredirect-nopages' => '"$1" isim fezasında iç bir yollama saifesi yoq.',

# Statistics
'statistics' => 'Statistika',
'statistics-header-pages' => 'Saife statistikası',
'statistics-header-edits' => 'Deñiştirüv statistikası',
'statistics-header-views' => 'Közden keçirme statistikası',
'statistics-header-users' => 'Qullanıcı statistikası',
'statistics-header-hooks' => 'Diger statistika',
'statistics-mostpopular' => 'Eñ sıq baqılğan saifeler',

'doubleredirects' => 'Yollamağa olğan yollamalar',
'doubleredirectstext' => 'Bu saifede diger yollama saifelerine yollanma olğan saifeleri kösterile.
Er satırda birinci ve ekinci yollamağa bağlantılar da, ekinci yollamanıñ maqsat saifesi (adetince o birinci yollamanıñ kerekli maqsadı ola) da bar.
<del>Üstü sızılğan</del> meseleler endi çezilgen.',
'double-redirect-fixed-move' => '[[$1]] avuştırıldı, şimdi [[$2]] saifesine yollap tura.',

'brokenredirects' => 'Bar olmağan saifege yapılğan yollamalar',
'brokenredirectstext' => 'Aşağıdaki yollamalar bar olmağan saifelerge bağlantı bereler:',
'brokenredirects-edit' => 'deñiştir',
'brokenredirects-delete' => 'yoq et',

'withoutinterwiki' => 'Diger tillerdeki versiyalarğa bağlantıları olmağan saifeler',
'withoutinterwiki-summary' => 'Bu saifelerde diger tillerdeki versiyalarğa bağlantılar yoq:',
'withoutinterwiki-submit' => 'Köster',

'fewestrevisions' => 'Eñ az deñiştirme yapılğan saifeler',

# Miscellaneous special pages
'nbytes' => '{{PLURAL:$1|1 bayt|$1 bayt}}',
'ncategories' => '{{PLURAL:$1|1 kategoriya|$1 kategoriya}}',
'nlinks' => '{{PLURAL:$1|1 bağlantı|$1 bağlantı}}',
'nmembers' => '{{PLURAL:$1|1 aza|$1 aza}}',
'nrevisions' => '{{PLURAL:$1|1 versiya|$1 versiya}}',
'nviews' => '{{PLURAL:$1|1 körünüv|$1 körünüv}}',
'specialpage-empty' => 'Bu soratma içün iç netice yoq.',
'lonelypages' => 'Özüne iç bağlantı olmağan saifeler',
'lonelypagestext' => 'Aşağıdaki saifelerge {{SITENAME}} saytındaki diger saifelerden bağlantı berilmegen, ondan da ğayrı mezkür saifeler diger saiferlrge kirsetilmegen.',
'uncategorizedpages' => 'Er angi bir kategoriyada olmağan saifeler',
'uncategorizedcategories' => 'Er angi bir kategoriyada olmağan kategoriyalar',
'uncategorizedimages' => 'Er angi bir kategoriyada olmağan resimler',
'uncategorizedtemplates' => 'Er angi bir kategoriyada olmağan şablonlar',
'unusedcategories' => 'Qullanılmağan kategoriyalar',
'unusedimages' => 'Qullanılmağan resimler',
'popularpages' => 'Populâr saifeler',
'wantedcategories' => 'İstenilgen kategoriyalar',
'wantedpages' => 'İstenilgen saifeler',
'wantedfiles' => 'İstenilgen fayllar',
'wantedtemplates' => 'İstenilgen şablоnlar',
'mostlinked' => 'Özüne eñ ziyade bağlantı berilgen saifeler',
'mostlinkedcategories' => 'Eñ çoq saifege saip kategoriyalar',
'mostlinkedtemplates' => 'Özüne eñ ziyade bağlantı berilgen şablonlar',
'mostcategories' => 'Eñ ziyade kategoriyağa bağlanğan saifeler',
'mostimages' => 'Eñ çoq qullanılğan resimler',
'mostrevisions' => 'Eñ çoq deñiştirilgen saifeler',
'prefixindex' => 'Prefiksnen bütün saifeler',
'shortpages' => 'Qısqa saifeler',
'longpages' => 'Uzun saifeler',
'deadendpages' => 'Başqa saifelerge bağlantısı olmağan saifeler',
'deadendpagestext' => 'Bu {{SITENAME}} başqa saifelerine bağlantısı olmağan saifelerdir.',
'protectedpages' => 'Qorçalanğan saifeler',
'protectedpagestext' => 'Bu saifelerniñ deñiştirüvge qarşı qorçalavı bar',
'protectedtitles' => 'Yasaqlanğan serlevalar',
'listusers' => 'Qullanıcılar cedveli',
'listusers-editsonly' => 'Tek eñ azından bir deñiştirme yapqan qullanıcılarnı köster',
'newpages' => 'Yañı saifeler',
'newpages-username' => 'Qullanıcı adı:',
'ancientpages' => 'Eñ eski saifeler',
'move' => 'Adını deñiştir',
'movethispage' => 'Saifeniñ adını deñiştir',
'pager-newer-n' => '{{PLURAL:$1|daa yañı 1|daa yañı $1}}',
'pager-older-n' => '{{PLURAL:$1|daa eski 1|daa eski $1}}',

# Book sources
'booksources' => 'Kitaplar menbası',
'booksources-search-legend' => 'Kitaplar menbasını qıdıruv',
'booksources-go' => 'Qıdır',

# Special:Log
'specialloguserlabel' => 'Qullanıcı:',
'speciallogtitlelabel' => 'Serleva:',
'log' => 'Jurnallar',
'all-logs-page' => 'Bütün umumiy jurnallar',
'logempty' => 'Jurnalda bir kelgen malümat yoq.',
'log-title-wildcard' => 'Bu işaretlerden başlanğan serlevalarnı qıdır',

# Special:AllPages
'allpages' => 'Bütün saifeler',
'alphaindexline' => '$1 saifesinden $2 saifesinece',
'nextpage' => 'Soñraki saife ($1)',
'prevpage' => 'Evelki saife ($1)',
'allpagesfrom' => 'Cedvelge çekmege başlanılacaq arifler:',
'allpagesto' => 'Şunıñnen bitken saifelerni köster:',
'allarticles' => 'Bütün saifeler',
'allinnamespace' => 'Bütün saifeler ($1 saifeleri)',
'allnotinnamespace' => 'Bütün saifeler ($1 isim fezasında olmağanlar)',
'allpagesprev' => 'Evelki',
'allpagesnext' => 'Soñraki',
'allpagessubmit' => 'Köster',
'allpagesprefix' => 'Yazğan ariflernen başlağan saifelerni köster:',
'allpagesbadtitle' => 'Saifeniñ adı ruhsetsizdir. Serlevada tiller arası prefiksi ya da vikiler arası bağlantı ya da başqa qullanıluvı yasaq olğan işaretler bar.',
'allpages-bad-ns' => '{{SITENAME}} saytında "$1" isim fezası yoqtır.',

# Special:Categories
'categories' => 'Saife kategoriyaları',
'categoriespagetext' => 'Aşağıdaki {{PLURAL:$1|kategoriyada|kategoriyalarda}} saife ya da media fayllar bar.
[[Special:UnusedCategories|Qullanılmağan kategoriyalar]] mında kösterilmegen.
Ayrıca [[Special:WantedCategories|talap etilgen kategoriyalarnıñ cedveline]] de baqıñız.',
'special-categories-sort-count' => 'sayılarına köre sırala',
'special-categories-sort-abc' => 'elifbe sırasınen sırala',

# Special:LinkSearch
'linksearch' => 'Tış bağlantılar',
'linksearch-pat' => 'Qıdıruv içün şablon:',
'linksearch-ns' => 'İsim fezası:',
'linksearch-ok' => 'Qıdır',

# Special:ListUsers
'listusers-submit' => 'Köster',
'listusers-noresult' => 'İç bir qullanıcı tapılmadı.',

# Special:ListGroupRights
'listgrouprights-members' => '(azalar cedveli)',

# Email user
'mailnologin' => 'Mektüp yollanacaq adresi yoqtır',
'mailnologintext' => 'Diger qullanıcılarğa elektron mektüpler yollap olmaq içün [[Special:UserLogin|oturım açmalısıñız]] ve [[Special:Preferences|sazlamalarıñızda]] mevcut olğan e-mail adresiniñ saibi olmalısıñız.',
'emailuser' => 'Qullanıcığa mektüp',
'emailpage' => 'Qullanıcığa elektron mektüp yolla',
'emailpagetext' => 'Aşağıdaki formanı toldurıp bu qullanıcığa mektüp yollap olursıñız.
[[Special:Preferences|Öz sazlamalarıñızda]] yazğan elektron adresiñiz mektüpniñ "Kimden" satırında yazılacaq, bunıñ içün mektüp alıcı doğrudan-doğru siziñ adresiñizge cevap yollap olur.',
'usermailererror' => 'E-mail beyanatı yollanğan vaqıtta hata olıp çıqtı',
'defemailsubject' => '{{SITENAME}} e-mail',
'noemailtitle' => 'E-mail adresi yoqtır',
'noemailtext' => 'Bu qullanıcı uyğun elektron poçta adresini bildirmegen.',
'emailfrom' => 'Kimden:',
'emailto' => 'Kimge:',
'emailsubject' => 'Mektüp mevzusı:',
'emailmessage' => 'Mektüp:',
'emailsend' => 'Yolla',
'emailccme' => 'Mektübimniñ bir kopiyasını maña da yolla.',
'emailccsubject' => '$1 qullanıcısına yollanğan mektübiñizniñ kopiyası: $2',
'emailsent' => 'Mektüp yollandı',
'emailsenttext' => 'Siziñ e-mail beyanatıñız yollandı',
'emailuserfooter' => 'Bu mektüp $1 tarafından $2 qullanıcısına, {{SITENAME}} saytındaki "Qullanıcığa e-mail yolla" funktsiyasınen yollanğan.',

# Watchlist
'watchlist' => 'Közetüv cedveli',
'mywatchlist' => 'Közetüv cedvelim',
'nowatchlist' => 'Siziñ közetüv cedveliñiz boştır.',
'watchlistanontext' => 'Közetüv cedvelini baqmaq ya da deñiştirmek içün $1 borclusıñız.',
'watchnologin' => 'Oturım açmaq kerek',
'watchnologintext' => 'Öz közetüv cedveliñizni deñiştirmek içün [[Special:UserLogin|oturım açıñız]]',
'addedwatchtext' => '"[[:$1]]" saifesi [[Special:Watchlist|közetüv cevdeliñizge]] kirsetildi.
Bundan soñ, bu saifede ve onıñ muzakere saifesinde yapılacaq deñiştirmeler anda kösterilecek.',
'removedwatchtext' => '"[[:$1]]" saifesi [[Special:Watchlist|közetüv cedveliñizden]] yoq etildi.',
'watch' => 'Közet',
'watchthispage' => 'Bu saifeni közet',
'unwatch' => 'Közetme',
'unwatchthispage' => 'Bu saifeni közetme',
'notanarticle' => 'Malümat saifesi degil',
'watchlist-details' => 'Muzakere saifelerini esapqa almayıp, közetüv cedveliñizde {{PLURAL:$1|1|$1}} saife bar.',
'wlheader-enotif' => 'E-mail ile haber berüv açıldı.',
'wlheader-showupdated' => "Soñki ziyaretiñizden soñ deñiştirilgen saifeler '''qalın ariflernen''' kösterildi.",
'watchmethod-recent' => 'soñki deñiştirmeler arasında közetken saifeleriñiz qıdırıla',
'watchmethod-list' => 'közetüv cedvelindeki saifeler teşkerile',
'watchlistcontains' => 'Siziñ közetüv cedveliñizde {{PLURAL:$1|1|$1}} saife bar.',
'iteminvalidname' => '"$1" saifesi munasebetinen problema olıp çıqtı, elverişli olmağan isimdir…',
'wlnote' => "Aşağıda saat $3, $4 içün soñki {{PLURAL:$2|saat|'''$2''' saat}} içinde yapılğan soñki {{PLURAL:$1|deñiştirme|'''$1''' deñiştirme}} kösterile.",
'wlshowlast' => 'Soñki $1 saat içün, $2 kün içün ya da $3 köster',
'watchlist-options' => 'Közetüv cedveli sazlamaları',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Közetüv cedveline kirsetilmekte...',
'unwatching' => 'Közetüv cedvelinden yoq etilmekte...',

'enotif_mailer' => '{{SITENAME}} poçta vastasınen haber bergen hızmet',
'enotif_reset' => 'Cümle saifelerni baqılğan olaraq işaretle',
'enotif_impersonal_salutation' => '{{SITENAME}} qullanıcısı',
'enotif_lastvisited' => 'Soñki ziyaretiñizden berli yapılğan deñiştirmelerni körmek içün $1 baqıñız.',
'enotif_anon_editor' => 'adsız (anonim) qullanıcı $1',
'enotif_body' => 'Sayğılı $WATCHINGUSERNAME,

$PAGEINTRO $NEWPAGE

Deñiştirmeniñ qısqa tarifi: $PAGESUMMARY $PAGEMINOREDIT

Saifeni deñiştirgen qullanıcınen bağlanmaq içün:
e-mail adresi: $PAGEEDITOR_EMAIL
viki saifesi: $PAGEEDITOR_WIKI

Bu saifeni ziyaret etmeseñiz, birev onı bir daa deñiştirse de, iç bir tenbi beyanatı yollanmaycaq. Közetüv cedveliñizdeki bütün saifeler içün tenbi sazlamalarını deñiştire bilesiñiz.

{{SITENAME}} bildirüv sisteması

--

Bildirüv sazlamalarını deñiştirmek içün:
{{canonicalurl:{{#special:Preferences}}}}

Közetüv cedveli sazlamalarını deñiştirmek içün:
{{canonicalurl:{{#special:EditWatchlist}}}}

Saifeni közetüv cedvelinden çıqarmaq içün:
$UNWATCHURL

Yardım ve teklifler içün:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'yaratıldı',
'changed' => 'deñiştirildi',

# Delete
'deletepage' => 'Saifeni yoq et',
'confirm' => 'Tasdıqla',
'excontent' => "eski metin: '$1'",
'excontentauthor' => "eski metin: '$1' ('$2' isse qoşqan tek bir qullanıcı)",
'exbeforeblank' => "Yoq etilmegen evelki metin: '$1'",
'exblank' => 'saife metini boş',
'delete-confirm' => '"$1" saifesini yoq etmektesiñiz',
'delete-legend' => 'Yoq etüv',
'historywarning' => "'''Tenbi:''' Siz yoq etmege istegen saifeñizniñ qararnen $1 {{PLURAL:$1|versiyalı|versiyalı}} keçmişi bardır:",
'confirmdeletetext' => 'Bir saifeni ya da resimni bütün keçmişi ile birlikte malümat bazasından qalıcı olaraq yoq etmek üzresiñiz.
Lütfen, neticelerini añlağanıñıznı ve [[{{MediaWiki:Policy-url}}|yoq etüv politikasına]] uyğunlığını diqqatqa alıp, bunı yapmağa istegeniñizni tasdıqlañız.',
'actioncomplete' => 'Areket tamamlandı',
'actionfailed' => 'Areket yapılamadı',
'deletedtext' => '"$1" yoq etildi.
yaqın zamanda yoq etilgenlerni körmek içün: $2.',
'dellogpage' => 'Yoq etüv jurnalı',
'dellogpagetext' => 'Aşağıdaki cedvel soñki yoq etüv jurnalıdır.',
'deletionlog' => 'yoq etüv jurnalı',
'reverted' => 'Evelki versiya keri ketirildi',
'deletecomment' => 'Sebep:',
'deleteotherreason' => 'Diger/ilâveli sebep:',
'deletereasonotherlist' => 'Diger sebep',

# Rollback
'rollback' => 'Deñiştirmelerni keri al',
'rollback_short' => 'keri al',
'rollbacklink' => 'eski alına ketir',
'rollbackfailed' => 'keri aluv muvafaqiyetsiz',
'cantrollback' => 'Deñiştirmeler keri alınamay, saifeni soñki deñiştirgen kişi onıñ tek bir müellifidir',
'editcomment' => "Deñiştirme izaatı: \"''\$1''\" edi.",
'revertpage' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|muzakere]]) tarafından yapılğan deñiştirmeler keri alınıp, [[User:$1|$1]] tarafından deñiştirilgen evelki versiya keri ketirildi.',

# Protect
'protectlogpage' => 'Qorçalav jurnalı',
'protectlogtext' => 'Aşağıdaki, saifeni qorçalavğa aluv/qorçalavdan çıqaruv ile bağlı deñişmeler jurnalıdır.
Qorçalanğan saifeler [[Special:ProtectedPages|tam cedvelini]] de köre bilesiñiz.',
'protectedarticle' => '"[[$1]]" qorçalav altına alındı',
'modifiedarticleprotection' => '"[[$1]]" içün qorçalav seviyesi deñiştirildi',
'unprotectedarticle' => '"[[$1]]" saifesinden qorçalav çıqarlıdı',
'prot_1movedto2' => '"[[$1]]" saifesiniñ adı "[[$2]]" olaraq deñiştirildi',
'protect-legend' => 'Qorçalavnı tasdıqla',
'protectcomment' => 'Sebep:',
'protectexpiry' => 'Bitiş tarihı:',
'protect_expiry_invalid' => 'Bitiş tarihı yañlış.',
'protect_expiry_old' => 'Bitiş zamanı keçmiştedir.',
'protect-text' => "'''[[$1]]''' saifesiniñ qorçalav seviyesini mından körip olur ve deñiştirip olasıñız.",
'protect-locked-access' => "Qullanıcı esabıñız saifeniñ qorçalav seviyelerini deñiştirme yetkisine saip degil. '''$1''' saifesiniñ şimdiki sazlamaları şularıdır:",
'protect-cascadeon' => 'Bu saife şimdi qorçalav altındadır, çünki aşağıda cedvellengen ve kaskadlı qorçalav altındaki {{PLURAL:$1|1|$1}} saifede qullanıla.
Bu saifeniñ qorçalav seviyesini deñiştirip olasıñız, amma kaskadlı qorçalav tesir etilmeycek.',
'protect-default' => 'Bütün qullanıcılarğa ruhset ber',
'protect-fallback' => 'Tek "$1" ruhseti olğan qullanıcılar içün',
'protect-level-autoconfirmed' => 'Tek avtomatik tasdıqlanğan qullanıcılarğa ruhset berile',
'protect-level-sysop' => 'Tek idarecilerge ruhset berile',
'protect-summary-cascade' => 'kaskadlı',
'protect-expiring' => 'bite: $1 (UTC)',
'protect-cascade' => 'Bu saifede qullanılğan bütün saifelerni qorçalavğa al (kaskadlı qorçalav)',
'protect-cantedit' => 'Bu saifeniñ qorçalav seviyesini deñiştirip olamazsıñız, çünki bunı yapmağa yetkiñiz yoq.',
'protect-expiry-options' => '1 saat:1 hour,1 kün:1 day,1 afta:1 week,2 afta:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 yıl:1 year,müddetsiz:infinite',
'restriction-type' => 'Ruhseti:',
'restriction-level' => 'Ruhset seviyesi:',
'minimum-size' => 'Asğariy (minimal) büyüklik',
'maximum-size' => 'Azamiy (maksimal) büyüklik:',
'pagesize' => '(bayt)',

# Restrictions (nouns)
'restriction-edit' => 'Deñiştir',
'restriction-move' => 'Adını deñiştir',

# Restriction levels
'restriction-level-sysop' => 'qorçalav altında',
'restriction-level-autoconfirmed' => 'qısmen qorçalav altında',

# Undelete
'undelete' => 'Yoq etilgen saifelerni köster',
'undeletepage' => 'Saifeniñ yoq etilgen versiyalarına köz at ve keri ketir.',
'viewdeletedpage' => 'Yoq etilgen saifelerge baq',
'undeletebtn' => 'Keri ketir!',
'undeletelink' => 'köster/keri ketir',
'undeletereset' => 'Vazgeç',
'undeletecomment' => 'Sebep:',
'undeletedrevisions' => 'Toplam {{PLURAL:$1|1 qayd|$1 qayd}} keri ketirildi.',
'undelete-header' => 'Keçenlerde yоq etilgen saifelerni körmek içün [[Special:Log/delete|yоq etüv jurnalına]] baqıñız.',

# Namespace form on various pages
'namespace' => 'İsim fezası:',
'invert' => 'Saylanğan tışındakilerni sayla',
'blanknamespace' => '(Esas)',

# Contributions
'contributions' => '{{GENDER:$1|Qullanıcınıñ}} isseleri',
'contributions-title' => '$1 qullanıcısınıñ isseleri',
'mycontris' => 'İsseler',
'contribsub2' => '$1 ($2)',
'nocontribs' => 'Bu kriteriylerge uyğan deñiştirme tapılamadı',
'uctop' => '(soñki)',
'month' => 'Bu ay (ve ondan erte):',
'year' => 'Bu sene (ve ondan erte):',

'sp-contributions-newbies' => 'Tek yañı qullanıcılarnıñ isselerini köster',
'sp-contributions-newbies-sub' => 'Yañı qullanıcılar içün',
'sp-contributions-blocklog' => 'Blok etüv jurnalı',
'sp-contributions-talk' => 'muzakere',
'sp-contributions-userrights' => 'qullanıcı aqlarını idare etüv',
'sp-contributions-search' => 'İsselerni qıdıruv',
'sp-contributions-username' => 'IP adresi ya da qullanıcı adı:',
'sp-contributions-submit' => 'Qıdır',

# What links here
'whatlinkshere' => 'Bu saifege bağlantılar',
'whatlinkshere-title' => '$1 saifesine bağlantı bergen saifeler',
'whatlinkshere-page' => 'Saife:',
'linkshere' => "'''[[:$1]]''' saifesine bağlantı bergen saifeler:",
'nolinkshere' => "'''[[:$1]]''' saifesine bağlantı bergen saife yoq.",
'nolinkshere-ns' => "Saylanğan isim fezasında '''[[:$1]]''' saifesine bağlanğan saife yoqtır.",
'isredirect' => 'Yollama saifesi',
'istemplate' => 'kirsetilme',
'isimage' => 'fayl bağlantısı',
'whatlinkshere-prev' => '{{PLURAL:$1|evelki|evelki $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|soñraki|soñraki $1}}',
'whatlinkshere-links' => '← bağlantılar',
'whatlinkshere-hideredirs' => 'yollamalarnı $1',
'whatlinkshere-hidetrans' => 'çapraz qoşmalarnı $1',
'whatlinkshere-hidelinks' => 'bağlantılarnı $1',
'whatlinkshere-filters' => 'Süzgüçler',

# Block/unblock
'blockip' => 'Bu IP adresinden irişimni blok et',
'blockip-legend' => 'Qullanıcını blok et',
'blockiptext' => 'Aşağıdaki formanı qullanıp belli bir IP adresiniñ ya da qullanıcınıñ irişimini blok etip olasıñız. Bu tek vandalizmni blok etmek içün ve [[{{MediaWiki:Policy-url}}|qaidelerge]] uyğun olaraq yapılmalı. Aşağığa mıtlaqa blok etüv ile bağlı bir izaat yazıñız. (meselâ: Şu saifelerde vandalizm yaptı).',
'ipadressorusername' => 'IP adresi ya da qullanıcı adı',
'ipbexpiry' => 'Bitiş müddeti',
'ipbreason' => 'Sebep',
'ipbsubmit' => 'Bu qullanıcını blok et',
'ipbother' => 'Farqlı zaman',
'ipboptions' => '2 saat:2 hours,1 kün:1 day,3 kün:3 days,1 afta:1 week,2 afta:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 yıl:1 year,müddetsiz:infinite',
'ipbotheroption' => 'farqlı',
'ipbotherreason' => 'Diger/ilâveli sebep:',
'badipaddress' => 'Yañlış IP adresi',
'blockipsuccesssub' => 'Blok etme muvafaqiyetnen yapıldı',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] blok etildi.<br />
Blok etmelerni közden keçirmek içün [[Special:BlockList|IP adresi blok etilgenler]] cedveline baqıñız.',
'unblockip' => 'Qullanıcınıñ blok etmesini çıqar',
'ipusubmit' => 'Bu blok etmeni çıqar',
'ipblocklist' => 'Blok etilgen qullanıcılar ve IP adresleri',
'infiniteblock' => 'müddetsiz',
'expiringblock' => '$1 $2 tarihında bitecek',
'blocklink' => 'blok et',
'unblocklink' => 'blok etmesini çıqar',
'change-blocklink' => 'blok etüvni deñiştir',
'contribslink' => 'İsseler',
'autoblocker' => 'Avtomatik olaraq blok etildiñiz çünki keçenlerde IP adresiñiz "[[User:$1|$1]]" qullanıcısı tarafından qullanıldı. $1 adlı qullanıcınıñ blok etilüvi içün bildirilgen sebep: "\'\'\'$2\'\'\'"',
'blocklogpage' => 'Blok etüv jurnalı',
'blocklogentry' => '"[[$1]]" irişimi $2 $3 toqtatıldı. Sebep',
'blocklogtext' => 'Mında qullanıcı irişimine yönelik blok etüv ve blok çıqaruv qaydları kösterile. Avtomatik IP adresi blok etüvleri cedvelge kirsetilmedi. Şimdi irişimi toqtatılğan qullanıcılarnı [[Special:BlockList|IP blok etüv cedveli]] saifesinden körip olasıñız.',
'unblocklogentry' => '$1 qullanıcısınıñ blok etmesi çıqarıldı',
'block-log-flags-nocreate' => 'yañı esap açmaq yasaq etildi',
'block-log-flags-noemail' => 'e-mail blok etildi',
'ipb_expiry_invalid' => 'Yañlış bitiş zamanı.',
'ipb_already_blocked' => '"$1" endi blok etildi',
'ip_range_invalid' => 'Ruhsetsiz IP aralığı.',

# Developer tools
'lockdb' => 'Malümat bazası kilitli',
'lockbtn' => 'Malümat bazası kilitli',

# Move page
'move-page' => '$1 saifesiniñ adını deñiştireyatasız',
'move-page-legend' => 'Saifeniñ adını deñiştirüv',
'movepagetext' => "Aşağıdaki forma qullanılıp saifeniñ adı deñiştirilir. Bunıñnen beraber deñiştirmeler jurnalı da yañı adğa avuştırılır.
Eski adı yañı adına yollama olur. Eski serlevağa yollama saifelerni avtomatik olaraq yañartıp olasıñız. Bu areketni avtomatik yapmağa istemeseñiz, bütün [[Special:DoubleRedirects|çift]] ve [[Special:BrokenRedirects|yırtıq]] yollama saifelerini özüñiz tüzetmege mecbur olursıñız. Bağlantılar endiden berli doğru çalışmasından emin olmalısıñız.

Yañı adda bir saife endi bar olsa, ad deñiştirilüvi '''yapılmaycaq''', ancaq bar olğan saife yollama ya da boş olsa ad deñiştirilüvi mümkün olacaq. Bu demek ki, saifeniñ adını yañlıştan deñiştirgen olsañız deminki adını keri qaytarıp olasıñız, amma bar olğan saifeni tesadüfen yoq etamaysıñız.

'''TENBİ!'''
Ad deñiştirilüvi populâr saifeler içün büyük ve beklenmegen deñişmelerge sebep ola bilir. Lütfen, deñiştirme yapmazdan evel ola bileceklerni köz ögüne alıñız.",
'movepagetalktext' => "Qoşulğan muzakere saifesiniñ de (bar olsa) adı avtomatik tarzda deñiştirilecek. '''Müstesnalar:'''

*Aynı bu isimde boş olmağan bir muzakere saifesi endi bar;
*Aşağıdaki boşluqqa işaret qoymadıñız.

Böyle allarda, kerek olsa, saifelerni qolnen taşımağa ya da birleştirmege mecbur olursıñız.",
'movearticle' => 'Eski ad',
'movenologin' => 'Oturım açmadıñız',
'movenologintext' => 'Saifeniñ adını deñiştirip olmaq içün [[Special:UserLogin|oturım açıñız]].',
'movenotallowed' => 'Saifeler adlarını deñiştirmege iziniñiz yoq.',
'newtitle' => 'Yañı ad',
'move-watch' => 'Bu saifeni közet',
'movepagebtn' => 'Adını deñiştir',
'pagemovedsub' => 'Saifeniñ adı deñiştirildi',
'movepage-moved' => '\'\'\'"$1" saifesiniñ adı "$2" olaraq deñiştirildi\'\'\'',
'movepage-moved-redirect' => 'Bir yollama yaratıldı.',
'movepage-moved-noredirect' => 'Yollama yaratıluvı bastırıldı.',
'articleexists' => 'Bu adda bir saife endi bar ya da siz yazğan ad yasaqlı.
Lütfen, başqa bir ad saylap yazıñız.',
'cantmove-titleprotected' => 'Siz yazğan yañı ad yasaqlıdır, bunıñ içün saife adını deñiştirmekniñ çaresi yoq.',
'talkexists' => "'''Saifeniñ adı deñiştirildi, amma muzakere saifesiniñ adını deñiştirmege mümkünlik yoqtır, çünki aynı bu adda bir saife endi bar. Lütfen, bularnı qolnen birleştiriñiz.'''",
'movedto' => 'adı deñiştirildi:',
'movetalk' => 'Muzakere saifesiniñ adını deñiştir.',
'move-subpages' => 'Alt saifelerniñ adlarını da deñiştir ($1 saifege qadar)',
'move-talk-subpages' => 'Muzakere saifesi alt saifeleriniñ adlarını da deñiştir ($1 saifege qadar)',
'movepage-page-exists' => '$1 saifesi endi bar, ve avtomatik olaraq yañıdan yazılıp olamaz.',
'movepage-page-moved' => '$1 saifesiniñ adı $2 olaraq deñiştirildi.',
'movepage-page-unmoved' => '$1 saifesiniñ adı $2 olaraq deñiştirilip olamay.',
'movelogpage' => 'Ad deñiştirilmeleri jurnalı',
'movelogpagetext' => 'Aşağıda bulunğan cedvel adı deñiştirilgen saifelerni köstere',
'movesubpage' => '{{PLURAL:$1|Alt saife|Alt saifeler}}',
'movesubpagetext' => 'Bu saifeniñ aşağıda kösterilgen $1 {{PLURAL:$1|alt saifesi|alt saifesi}} bar.',
'movenosubpage' => 'Bu saifeniñ alt saifesi yoq.',
'movereason' => 'Sebep',
'revertmove' => 'Kerige al',
'delete_and_move' => 'Yoq et ve adını deñiştir',
'delete_and_move_text' => '== Yoq etmek lâzimdir ==

"[[:$1]]" saifesi endi bar. Adını deñiştirip olmaq içün onı yoq etmege isteysiñizmi?',
'delete_and_move_confirm' => 'Ebet, bu saifeni yoq et',
'delete_and_move_reason' => 'İsim deñiştirip olmaq içün yoq etildi',
'selfmove' => 'Bu saifeniñ adını deñiştirmege imkân yoqtır, çünki asıl ile yañı adları bir kele.',
'move-leave-redirect' => 'Arqada bir yollama taşla',
'protectedpagemovewarning' => "'''Tenbi:''' Bu saife kilitlengen, adını tek idareciler deñiştirip ola.
Jurnalnıñ soñki yazısı aşağıda berilgen:",
'semiprotectedpagemovewarning' => "'''İhtar:''' Bu saife kilitlengen, adını tek qaydlı qullanıcılar deñiştirip ola.
Jurnalnıñ soñki yazısı aşağıda berilgen:",

# Export
'export' => 'Saifelerni eksport et',

# Namespace 8 related
'allmessages' => 'Sistema beyanatları',
'allmessagesname' => 'İsim',
'allmessagesdefault' => 'Original metin',
'allmessagescurrent' => 'Şimdi qullanılğan metin',
'allmessagestext' => 'İşbu cedvel MediaWikide mevcut olğan bütün sistema beyanatlarınıñ cedvelidir.
MediaWiki interfeysiniñ çeşit tillerge tercime etüvde iştirak etmege isteseñiz [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] ve [//translatewiki.net translatewiki.net] saifelerine ziyaret etiñiz.',

# Thumbnails
'thumbnail-more' => 'Büyüt',
'filemissing' => 'Fayl tapılmadı',
'thumbnail_error' => 'Kiçik resim (thumbnail) yaratılğanda bir hata çıqtı: $1',
'thumbnail_invalid_params' => 'Yañlış kiçik resim parametrleri',
'thumbnail_dest_directory' => 'İstenilgen direktoriyanı yaratmaqnıñ iç çaresi yoq',

# Special:Import
'import-comment' => 'İzaat:',

# Import log
'importlogpage' => 'İmport jurnalı',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Siziñ qullanıcı saifeñiz',
'tooltip-pt-anonuserpage' => 'IP adresim içün qullanıcı saifesi',
'tooltip-pt-mytalk' => 'Siziñ muzakere saifeñiz',
'tooltip-pt-anontalk' => 'Bu IP adresinden yapılğan deñişikliklerni muzakere etüv',
'tooltip-pt-preferences' => 'Sazlamalarıñız (nastroykalarıñız)',
'tooltip-pt-watchlist' => 'Közetüvge alğan saifeleriñiz',
'tooltip-pt-mycontris' => 'Qoşqan isseleriñizniñ cedveli',
'tooltip-pt-login' => 'Oturım açmañız tevsiye olunır amma mecbur degilsiñiz.',
'tooltip-pt-anonlogin' => 'Oturım açmañız tevsiye olunır amma mecbur degilsiñiz.',
'tooltip-pt-logout' => 'Sistemadan çıquv',
'tooltip-ca-talk' => 'Saifedeki malümatnen bağlı muzakere',
'tooltip-ca-edit' => 'Bu saifeni deñiştirip olasıñız. Saqlamazdan evel baqıp çıqmağa unutmañız.',
'tooltip-ca-addsection' => 'Yañı bölükni açuv',
'tooltip-ca-viewsource' => 'Bu saife qorçalav altında. Menba kodunı tek körip olasıñız, deñiştirip olamaysıñız.',
'tooltip-ca-history' => 'Bu saifeniñ keçmiş versiyaları.',
'tooltip-ca-protect' => 'Bu saifeni qorçalav',
'tooltip-ca-unprotect' => 'Bu saifeniñ qorçalavını çıqaruv',
'tooltip-ca-delete' => 'Bu saifeni yoq etüv',
'tooltip-ca-undelete' => 'Saifeni yoq etilmezden evelki alına keri ketiriñiz',
'tooltip-ca-move' => 'Saifeniñ adını deñiştirüv',
'tooltip-ca-watch' => 'Bu saifeni közetüv cedveline aluv',
'tooltip-ca-unwatch' => 'Bu saifeni közetüvni taşlav',
'tooltip-search' => '{{SITENAME}} saytında qıdıruv',
'tooltip-search-go' => 'Bu adda saife mevcut olsa, oña bar',
'tooltip-search-fulltext' => 'Bu metini olğan saifeler qıdır',
'tooltip-p-logo' => 'Baş saife',
'tooltip-n-mainpage' => 'Baş saifege baruv',
'tooltip-n-mainpage-description' => 'Baş saifege bar',
'tooltip-n-portal' => 'Leyha üzerine, ne qaydadır, neni yapıp olasıñız',
'tooltip-n-currentevents' => 'Ağımdaki vaqialarnen bağlı soñki malümat',
'tooltip-n-recentchanges' => 'Vikide yapılğan soñki deñiştirmelerniñ cedveli.',
'tooltip-n-randompage' => 'Tesadüfiy bir saifeni kösterüv',
'tooltip-n-help' => 'Yardım bölügi',
'tooltip-t-whatlinkshere' => 'Bu saifege bağlantı bergen diger viki saifeleriniñ cedveli',
'tooltip-t-recentchangeslinked' => 'Bu saifege bağlantı bergen saifelerdeki soñki deñiştirmeler',
'tooltip-feed-rss' => 'Bu saife içün RSS translâtsiyası',
'tooltip-feed-atom' => 'Bu saife içün atom translâtsiyası',
'tooltip-t-contributions' => 'Qullanıcınıñ isse cedveline baquv',
'tooltip-t-emailuser' => 'Qullanıcığa e-mail mektübini yolla',
'tooltip-t-upload' => 'Sistemağa resim ya da media fayllarnı yükleñiz',
'tooltip-t-specialpages' => 'Bütün mahsus saifelerniñ cedvelini köster',
'tooltip-t-print' => 'Bu saifeniñ basılmağa uyğun körünişi',
'tooltip-t-permalink' => 'Bu saifeniñ versiyasına daimiy bağlantı',
'tooltip-ca-nstab-main' => 'Saifeni köster',
'tooltip-ca-nstab-user' => 'Qullanıcı saifesini köster',
'tooltip-ca-nstab-media' => 'Media saifesini köster',
'tooltip-ca-nstab-special' => 'Bu, mahsus saife olğanı içün deñiştirme yapamazsıñız.',
'tooltip-ca-nstab-project' => 'Leyha saifesini köster',
'tooltip-ca-nstab-image' => 'Resim saifesini köster',
'tooltip-ca-nstab-mediawiki' => 'Sistema beyanatını köster',
'tooltip-ca-nstab-template' => 'Şablonnı köster',
'tooltip-ca-nstab-help' => 'Yardım saifesini köster',
'tooltip-ca-nstab-category' => 'Kategoriya saifesini köster',
'tooltip-minoredit' => 'Bu, kiçik bir deñiştirmedir dep belgile',
'tooltip-save' => 'Yapqan deñiştirmeleriñizni saqlay',
'tooltip-preview' => 'Baqıp çıquv. Saqlamazdan evel bu funktsiyanı qullanıp yapqan deñiştirmeleriñizni baqıp çıqıñız!',
'tooltip-diff' => 'Metinge siz yapqan deñişikliklerni kösterir.',
'tooltip-compareselectedversions' => 'Saylanğan eki versiya arasındaki farqlarnı köster.',
'tooltip-watch' => 'Saifeni közetüv cedveline kirset',
'tooltip-recreate' => 'Yoq etilgen olmasına baqmadan saifeni yañıdan yañart',
'tooltip-upload' => 'Yüklenip başla',
'tooltip-rollback' => '"Keri qaytuv" sıçannen bir basuv vastasınen bu saifeni soñki deñiştirgenniñ deñişikliklerini keri ala',
'tooltip-undo' => '"Keri al" bu deñişiklikni keri ala ve deñişiklik penceresini baqıp çıquv rejiminde aça. Keri aluvnıñ sebebini bildirmege izin bere.',

# Stylesheets
'monobook.css' => '/* monobook temasınıñ ayarlarını (nastroykalarını) deñiştirmek içün bu yerini deñiştiriñiz. Bütün saytta tesirli olur. */',

# Metadata
'notacceptable' => 'Viki-server brauzeriñiz oqup olacaq formatında malümat beralmay.',

# Attribution
'anonymous' => '{{SITENAME}} saytınıñ {{PLURAL:$1|1|$1}} qaydsız (anonim) qullanıcıları',
'siteuser' => '{{SITENAME}} saytınıñ qullanıcısı $1',
'anonuser' => '{{SITENAME}} saytınıñ qaydsız (anonim) qullanıcısı $1',
'lastmodifiedatby' => 'Saife eñ soñki $3 tarafından $1, $2 tarihında deñiştirildi.',
'othercontribs' => 'Bu saifeni yaratqanda iştirak etken: $1.',
'others' => 'digerleri',
'siteusers' => '{{SITENAME}} saytınıñ {{PLURAL:$2|1|$2}} qullanıcısı $1',
'anonusers' => '{{SITENAME}} saytınıñ qaydsız (anonim) {{PLURAL:$2|qullanıcısı|qullanıcıları}} $1',
'creditspage' => 'Teşekkürler',
'nocredits' => 'Bu saife içün qullanıcılar cedveli yoq.',

# Spam protection
'spamprotectiontitle' => 'Spam qarşı qorçalav süzgüçi',
'spamprotectiontext' => 'Saqlamağa istegen saifeñiz spam süzgüçi tarafından blok etildi. Büyük ihtimallı ki, saifede qara cedveldeki bir tış saytqa bağlantı bar.',
'spamprotectionmatch' => 'Spam süzgüçinden bu beyanat keldi: $1',
'spambot_username' => 'Spamdan temizlev',
'spam_reverting' => '$1 saytına bağlantısı olmağan soñki versiyağa keri ketirüv',
'spam_blanking' => 'Bar olğan versiyalarda $1 saytına bağlantılar bar, temizlev',

# Skin names
'skinname-cologneblue' => 'Köln asretligi',
'skinname-monobook' => 'MonoBook',

# Patrol log
'patrol-log-page' => 'Teşkerüv jurnalı',
'log-show-hide-patrol' => 'Teşkerüv jurnalını $1',

# Image deletion
'deletedrevision' => '$1 sayılı eski versiya yoq etildi.',
'filedeleteerror-short' => 'Fayl yoq etkende hata çıqtı: $1',
'filedelete-missing' => '"$1" adlı fayl yoq etilip olamay, çünki öyle bir fayl yoq.',
'filedelete-old-unregistered' => 'Malümat bazasında saylanğan "$1" fayl versiyası yoq.',
'filedelete-current-unregistered' => 'Malümat bazasında saylanğan "$1" adlı fayl yoq.',

# Browsing diffs
'previousdiff' => '← Evelki deñişiklik',
'nextdiff' => 'Soñraki deñişiklik →',

# Media information
'mediawarning' => "'''İhtar''': Bu fayl türüniñ içinde yaman niyetli kod ola bile.
Faylnı işletip işletim sistemañızğa zarar ketirip olursıñız.",
'imagemaxsize' => "Resim ölçüsi sıñırı:<br />''(fayl malümat saifeleri içün)''",
'thumbsize' => 'Kiçik ölçü:',
'widthheightpage' => '$1 × $2, {{PLURAL:$3|1|$3}} saife',
'file-info' => 'fayl büyükligi: $1, MIME çeşiti: $2',
'file-info-size' => '$1 × $2 piksel, fayl büyükligi: $3, MIME çeşiti: $4',
'file-nohires' => 'Daa yüksek ayırımlılıqqa saip versiya yoq.',
'svg-long-desc' => 'SVG faylı, nominal $1 × $2 piksel, fayl büyükligi: $3',
'show-big-image' => 'Tam ayırımlılıq',

# Special:NewFiles
'newimages' => 'Yañı resimler',
'imagelisttext' => "Aşağıdaki cedvelde $2 köre tizilgen {{PLURAL:$1|'''1''' fayldır|'''$1''' fayldır}}.",
'newimages-summary' => 'Bu mahsus saife soñki yüklengen fayllarnı köstere.',
'newimages-legend' => 'Süzgüç',
'newimages-label' => 'Fayl adı (ya da onıñ bir parçası):',
'showhidebots' => '(botlarnı $1)',
'noimages' => 'Resim yoq.',
'ilsubmit' => 'Qıdır',
'bydate' => 'hronologik sıranen',
'sp-newimages-showfrom' => '$1, $2 tarihından başlap yañı fayllar köster',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1, $2 × $3',
'seconds-abbrev' => '$1san.',
'minutes-abbrev' => '$1daq.',
'hours-abbrev' => '$1saat',

# Bad image list
'bad_image_list' => 'Format böyle olmalı:

Er satır * işaretinen başlamalı. Satırnıñ birinci bağlantısı qоşmağa yasaqlanğan faylğa bağlanmalı.
Şu satırda ilerideki bağlantılar istisna olurlar, yani şu saifelerde işbu fayl qullanmaq mümkün.',

# Metadata
'metadata' => 'Resim detalleri',
'metadata-help' => 'Faylda (adetince raqamlı kamera ve skanerlernen qоşulğan) ilâve malümatı bar. Eger bu fayl yaratılğandan soñ deñiştirilse edi, belki de bazı parametrler eskirdi.',
'metadata-expand' => 'Tafsilâtnı köster',
'metadata-collapse' => 'Tafsilâtnı kösterme',
'metadata-fields' => 'Bu cedveldeki EXIF meta malümatı resim saifesinde kösterilecek, başqaları ise gizlenecek.
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

# Exif tags
'exif-make' => 'Kamera markası',
'exif-model' => 'Kamera modeli',
'exif-artist' => 'Yaratıcısı',
'exif-colorspace' => 'Renk aralığı',
'exif-datetimeoriginal' => 'Original saat ve tarih',
'exif-exposuretime' => 'Ekspozitsiya müddeti',
'exif-exposuretime-format' => '$1 saniye ($2)',
'exif-fnumber' => 'Diafragma nomerası',
'exif-spectralsensitivity' => 'Spektral duyğulılıq',
'exif-aperturevalue' => 'Diafragma',
'exif-brightnessvalue' => 'parlaqlıq',
'exif-lightsource' => 'Yarıq menbası',
'exif-exposureindex' => 'Ekspozitsiya indeksi',
'exif-scenetype' => 'Stsena çeşiti',
'exif-digitalzoomratio' => 'Yaqınlaştıruv koeffitsiyenti',
'exif-contrast' => 'Kontrastlıq',
'exif-saturation' => 'Toyğunlıq',
'exif-sharpness' => 'Açıqlıq',
'exif-gpslatitude' => 'Enlik',
'exif-gpslongitude' => 'Boyluq',
'exif-gpsaltitude' => 'Yükseklik',
'exif-gpstimestamp' => 'GPS saatı (atom saatı)',
'exif-gpssatellites' => 'Ölçemek içün qullanğanı sputnikler',

# Exif attributes
'exif-compression-1' => 'Sıqıştırılmağan',

'exif-orientation-3' => '180° aylandırılğan',

'exif-exposureprogram-1' => 'Elnen',

'exif-subjectdistance-value' => '$1 metr',

'exif-meteringmode-0' => 'Bilinmey',
'exif-meteringmode-1' => 'Orta',
'exif-meteringmode-255' => 'Diger',

'exif-lightsource-0' => 'Bilinmey',
'exif-lightsource-2' => 'Fluorestsent',
'exif-lightsource-9' => 'Açıq',
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
'edit-externally' => 'Fayl üzerinde kompyuteriñizde bulunğan programmalar ile deñişiklikler yapıñız',
'edit-externally-help' => '(Daa ziyade malümat içün [//www.mediawiki.org/wiki/Manual:External_editors bu saifege] (İnglizce)  baqıp olasıñız.)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'episini',
'namespacesall' => 'Episi',
'monthsall' => 'Episi',
'limitall' => 'bütüni',

# Email address confirmation
'confirmemail' => 'E-mail adresini tasdıqla',
'confirmemail_noemail' => '[[Special:Preferences|Qullanıcı sazlamalarıñızda]] dоğru bir e-mail adresiñiz yoq.',
'confirmemail_text' => '{{SITENAME}} saytınıñ e-mail funktsiyalarını qullanmazdan evel e-mail adresiñizniñ tasdıqlanması kerek. Adresiñizge tasdıq e-mail mektübini yollamaq içün aşağıdaki dögmeni basıñız. Yollanacaq beyanatta adresiñizni tasdıqlamaq içün brauzeriñiznen irişip olacaq, tasdıq kodu olğan bir bağlantı olacaq.',
'confirmemail_pending' => 'Tasdıq kodu endi sizge yollandı.
Eger esabıñıznı keçenleri açsa ediñiz, belki de yañnı kodnı bir daa sorağanıñızda, biraz beklemek kerek olur.',
'confirmemail_send' => 'Tasdıq kodunı yolla',
'confirmemail_sent' => 'Tasdıq e-mail mektübini yollandı.',
'confirmemail_oncreate' => 'Bildirgen e-mail adresiñizge tasdıq kodunen bir mektüp yollandı.
Şu kod oturım açmaq içün lâzim degil, amma bu saytta elektron poçtasınıñ çarelerini qullanmaq içün ruhset berilmezden evel onı kirsetmelisiñiz.',
'confirmemail_sendfailed' => '{{SITENAME}} tasdıq kodunı yollap olamay. Lütfen, adreste ruhsetsiz arif ya da işaret olmağanını teşkeriñiz.

Serverniñ cevabı: $1',
'confirmemail_invalid' => 'Yañlış tasdıq kodu. Tasdıq kodunıñ soñki qullanma tarihı keçken ola bilir.',
'confirmemail_needlogin' => '$1 yapmaq içün başta e-mail adresiñizni tasdıqlamalısıñız.',
'confirmemail_success' => 'E-mail adresiñiz tasdıqlandı.',
'confirmemail_loggedin' => 'E-mail adresiñiz tasdıqlandı.',
'confirmemail_error' => 'Tasdıqıñız bilinmegen bir hata sebebinden qayd etilmedi.',
'confirmemail_subject' => '{{SITENAME}} e-mail adres tasdıqı.',
'confirmemail_body' => '$1 IP adresinden yapılğan irişim ile {{SITENAME}} saytında
bu e-mail adresinen bağlanğan $2 qullanıcı esabı
açıldı.

Bu e-mail adresiniñ bahsı keçken qullanıcı esabına ait olğanını
tasdıqlamaq ve {{SITENAME}} saytındaki e-mail funktsiyalarını faal alğa
ketirmek içün aşağıdaki bağlantını basıñız:

$3

Bahsı keçken qullanıcı esabı sizge *ait olmağan* olsa bu bağlantını basıñız:

$5

Bu tasdıq kodu $4 tarihına qadar qullanılıp olacaq.',
'confirmemail_invalidated' => 'E-mail adresiniñ tasdıqı lâğu etildi',
'invalidateemail' => 'E-mail adresiniñ tasdıqı lâğu et',

# Scary transclusion
'scarytranscludedisabled' => '["Interwiki transcluding" işlemey]',
'scarytranscludefailed' => '[$1 şablonına irişilip olamadı]',
'scarytranscludetoolong' => '[URL adresi çoq uzun]',

# Delete conflict
'deletedwhileediting' => "'''Tenbi''': Bu saife siz deñişiklik yapmağa başlağandan soñ yoq etildi!",
'confirmrecreate' => "Siz bu saifeni deñiştirgen vaqıtta [[User:$1|$1]] ([[User talk:$1|muzakere]]) qullanıcısı onı yoq etkendir, sebebi:
:''$2''
Saifeni yañıdan yaratmağa isteseñiz, lütfen, bunı tasdıqlañız.",
'recreate' => 'Saifeni yañıdan yarat',

# action=purge
'confirm_purge_button' => 'Ok',
'confirm-purge-top' => 'Saife keşini temizlesinmi?',

# Multipage image navigation
'imgmultipageprev' => '← evelki saife',
'imgmultipagenext' => 'soñraki saife →',
'imgmultigo' => 'Bar',
'imgmultigoto' => '$1 saifesine bar',

# Table pager
'ascending_abbrev' => 'kiçikten büyükke',
'descending_abbrev' => 'büyükten kiçikke',
'table_pager_next' => 'Soñraki saife',
'table_pager_prev' => 'Evelki saife',
'table_pager_first' => 'İlk saife',
'table_pager_last' => 'Soñki saife',
'table_pager_limit' => 'Saife başına $1 dane köster',
'table_pager_limit_submit' => 'Bar',
'table_pager_empty' => 'İç netice yoq',

# Auto-summaries
'autosumm-blank' => 'Saife boşatıldı',
'autosumm-replace' => "Saifedeki malümat '$1' ile deñiştirildi",
'autoredircomment' => '[[$1]] saifesine yollandı',
'autosumm-new' => "Yañı saife yaratıldı. Mündericesi: '$1'",

# Live preview
'livepreview-loading' => 'Yüklenmekte…',
'livepreview-ready' => 'Yüklenmekte… Azır!',
'livepreview-failed' => 'Tez baqıp çıquv işlemey! Adiy baqıp çıquvnı qullanıp baqıñız.',
'livepreview-error' => 'Bağlanamadı: $1 "$2". Adiy baqıp çıquvnı qullanıp baqıñız.',

# Friendlier slave lag warnings
'lag-warn-normal' => '{{PLURAL:$1|1|$1}} saniyeden evel ve ondan soñ yapılğan deñişiklikler bu cedvelde kösterilmeyip olalar.',
'lag-warn-high' => 'Malümat bazasındaki problemalar sebebinden {{PLURAL:$1|1|$1}} saniyeden evel ve ondan soñ yapılğan deñişiklikler bu cedvelde kösterilmeyip olalar.',

# Watchlist editor
'watchlistedit-numitems' => 'Muzakere saifesini esapqa almayıp, közetüv cedveliñizde {{PLURAL:$1|1|$1}} saife bar.',
'watchlistedit-noitems' => 'Közetüv cedveliñizde iç bir saife yoq.',
'watchlistedit-normal-title' => 'Közetüv ceveliñizni deñiştireyatasız',
'watchlistedit-normal-legend' => 'Közetüv cedvelinden saife yoq etilüvi',
'watchlistedit-normal-explain' => 'Közetüv cedveliñizdeki saifeler aşağıda kösterilgen.
Bir saife közetüv cedvelinden yoq etmek içün onı belgilep "{{int:Watchlistedit-normal-submit}}" dögmesine basıñız.
Közetüv cedveliñizni [[Special:EditWatchlist/raw|metin olaraq da]] deñiştirip olasıñız.',
'watchlistedit-normal-submit' => 'Saylanğan saifelerni közetüv cevelinden yoq et',
'watchlistedit-normal-done' => '{{PLURAL:$1|1 saife|$1 saife}} közetüv cedveliñizden yoq etildi:',
'watchlistedit-raw-title' => 'Közetüv ceveliñizni deñiştireyatasız',
'watchlistedit-raw-legend' => 'Közetüv cedvelini deñiştirilüvi',
'watchlistedit-raw-explain' => 'Közetüv cedveliñizdeki saifeler aşağıda kösterilgen. Er satırda bir saife serlevası olmaq üzere, serlevalarnı kirsetip ya da yoq etip cedvelni deñiştirip olasıñız.
Bitirgen soñ "{{int:Watchlistedit-raw-submit}}" yazısına basıñız.
[[Special:EditWatchlist|Standart redaktornı da qullanıp olasıñız]].',
'watchlistedit-raw-titles' => 'Saifeler:',
'watchlistedit-raw-submit' => 'Közetüv cedvelini yañart',
'watchlistedit-raw-done' => 'Közetüv cedveliñiz yañardı.',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 saife|$1 saife}} ilâve olundı:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 saife|$1 saife}} yoq etildi:',

# Watchlist editing tools
'watchlisttools-view' => 'Deñişikliklerni köster',
'watchlisttools-edit' => 'Közetüv cedvelini kör ve deñiştir',
'watchlisttools-raw' => 'Közetüv cedvelini adiy metin olaraq deñiştir',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|muzakere]])',

# Special:Version
'version' => 'Versiya',

# Special:FileDuplicateSearch
'fileduplicatesearch-legend' => 'Dublikatnı qıdır',
'fileduplicatesearch-filename' => 'Fayl adı:',
'fileduplicatesearch-submit' => 'Qıdır',
'fileduplicatesearch-info' => '$1 × $2 piksel<br />Fayl büyükligi: $3<br />MIME çeşiti: $4',
'fileduplicatesearch-result-1' => '"$1" faylınıñ iç kоpiyası yоq.',
'fileduplicatesearch-result-n' => '"$1" faylınıñ {{PLURAL:$2|bir kоpiyası|$2 kоpiyası}} bar.',

# Special:SpecialPages
'specialpages' => 'Mahsus saifeler',
'specialpages-group-maintenance' => 'Baqım esabatları',
'specialpages-group-other' => 'Diger mahsus saifeler',
'specialpages-group-login' => 'Kiriş / Qayd oluv',
'specialpages-group-changes' => 'Soñki deñişiklikler ve jurnallar',
'specialpages-group-media' => 'Fayl esabatları ve yükleme',
'specialpages-group-users' => 'Qullanıcılar ve aqları',
'specialpages-group-highuse' => 'Çоq qullanılğan saifeler',
'specialpages-group-pages' => 'Saifeler cedveli',
'specialpages-group-pagetools' => 'Saife aletleri',
'specialpages-group-wiki' => 'Viki malümat ve aletler',
'specialpages-group-redirects' => 'Yollama mahsus saifeler',
'specialpages-group-spam' => 'Spamğa qarşı aletler',

# Special:BlankPage
'blankpage' => 'Bоş saife',
'intentionallyblankpage' => 'Bu saife aselet boş qaldırılğan',

# Special:ComparePages
'comparepages' => 'Saifelerni teñeştirüv',
'compare-selector' => 'Saifelerniñ versiyalarını teñeştirüv',
'compare-submit' => 'Teñeştir',

# HTML forms
'htmlform-reset' => 'Deñişikliklerni keri al',

# Search suggestions
'searchsuggest-containing' => 'içinde bu olğan...',

);
