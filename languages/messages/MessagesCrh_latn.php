<?php
/** Crimean Turkish (Latin) (Qırımtatarca (Latin))
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AlefZet
 * @author Alessandro
 * @author Urhixidur
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
    NS_FILE                      => 'Resim',
    NS_FILE_TALK                 => 'Resim_muzakeresi',
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
	"Ресим"                  => NS_FILE,
	"Ресим_музакереси"       => NS_FILE_TALK,
	"МедиаВики"              => NS_MEDIAWIKI,
	"МедиаВики_музакереси"   => NS_MEDIAWIKI_TALK,
	'Шаблон'                 => NS_TEMPLATE,
	'Шаблон_музакереси'      => NS_TEMPLATE_TALK,
	'Ярдым'                  => NS_HELP,
	'Разговор_о_помоћи'      => NS_HELP_TALK,
	'Категория'              => NS_CATEGORY,
	'Категория_музакереси'   => NS_CATEGORY_TALK,
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

$linkTrail = '/^([a-zâçğıñöşüа-яё“»]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Bağlantılarnıñ altını sız',
'tog-highlightbroken'         => 'Boş bağlantılarnı <a href="" class="new">bu şekilde</a> (alternativ: <a href="" class="internal">bu şekilde</a>) köster.',
'tog-justify'                 => 'Paragraf eki yaqqa yaslap tiz',
'tog-hideminor'               => 'Kiçik deñişikliklerni "Soñki deñişiklikler" saifesinde gizle',
'tog-extendwatchlist'         => 'Kelişken közetüv cedveli',
'tog-usenewrc'                => 'Kelişken soñki deñişiklikler cedveli (JavaScript)',
'tog-numberheadings'          => 'Serlevalarnı avtomatik nomeralandır',
'tog-showtoolbar'             => 'Deñişiklik yapqan vaqıtta yardımcı dögmelerni köster. (JavaScript)',
'tog-editondblclick'          => 'Saifeni çift basıp deñiştirmege başla (JavaScript)',
'tog-editsection'             => 'Bölüklerni [deñiştir] bağlantılarnı ile deñiştirme aqqı ber',
'tog-editsectiononrightclick' => 'Bölük serlevasına oñ basıp bölükte deñişiklikke ruhset ber. (JavaScript)',
'tog-showtoc'                 => 'Münderice cedveli köster (3 daneden ziyade serlevası olğan saifeler içün)',
'tog-rememberpassword'        => 'Parolni hatırla',
'tog-editwidth'               => 'Yazuv penceresi tam kenişlikte olsun',
'tog-watchcreations'          => 'Men yaratqan saifelerni közetüv cedvelime kirset',
'tog-watchdefault'            => 'Men deñiştirgen saifelerni közetüv cedvelime kirset',
'tog-watchmoves'              => 'Menim tarafımdan adı deñiştirilgen saifelerni közetüv cedvelime kirset',
'tog-watchdeletion'           => 'Men yoq etken saifelerni közetüv cedvelime kirset',
'tog-minordefault'            => 'Yapqan deñişikliklerimni kiçik deñişiklik olaraq işaretle',
'tog-previewontop'            => 'Baqıp çıquvnı yazuv pencereniñ üstünde köster',
'tog-previewonfirst'          => 'Deñiştirmede baqıp çıquvnı köster',
'tog-nocache'                 => 'Saifelerni hatırlama',
'tog-enotifwatchlistpages'    => 'Saife deñişikliklerinde maña e-mail yolla',
'tog-enotifusertalkpages'     => 'Qullanıcı saifemde deñişiklik olğanda maña e-mail yolla',
'tog-enotifminoredits'        => 'Saifelerde kiçik deñişiklik olğanda da de maña e-mail yolla',
'tog-enotifrevealaddr'        => 'Bildirüv mektüplerinde e-mail adresimni köster',
'tog-shownumberswatching'     => 'Közetken qullanıcı sayısını köster',
'tog-fancysig'                => 'Adiy imza (imzañız yuqarıda belgilegeniñiz kibi körünir. Saifeñizge avtomatik bağlantı yaratılmaz)',
'tog-externaleditor'          => 'Deñişikliklerni başqa editor programması ile yap',
'tog-externaldiff'            => 'Teñeştirmelerni tış programmağa yaptır.',
'tog-showjumplinks'           => '"Bar" bağlantısını faalleştir',
'tog-uselivepreview'          => 'Canlı baqıp çıquv hususiyetini qullan (JavaScript) (daa deñeme alında)',
'tog-forceeditsummary'        => 'Deñişiklik qısqa tarifini boş taşlağanda meni tenbile',
'tog-watchlisthideown'        => 'Közetüv cedvelimden menim deñişikliklerimni gizle',
'tog-watchlisthidebots'       => 'Közetüv cedvelimden bot deñişikliklerini gizle',
'tog-watchlisthideminor'      => 'Közetüv cedvelimden kiçik deñişikliklerni gizle',
'tog-watchlisthideliu'        => 'Közetüv cedvelimde qaydlı qullanıcılar tarafından yapılğan deñişikliklerni kösterme',
'tog-watchlisthideanons'      => 'Közetüv cedvelimde qaydsız (anonim) qullanıcılar tarafından yapılğan deñişikliklerni kösterme',
'tog-nolangconversion'        => 'Yazuv sisteması variantları deñiştirüvni işletme',
'tog-ccmeonemails'            => 'Diger qullanıcılarğa yollağan mektüplerimniñ kopiyalarını maña da yolla',
'tog-diffonly'                => 'Teñeştirme saifelerinde saifeniñ esas mündericesini kösterme',
'tog-showhiddencats'          => 'Gizli kategoriyalarnı köster',

'underline-always'  => 'Daima',
'underline-never'   => 'Asla',
'underline-default' => 'Brauzer qarar bersin',

# Dates
'sunday'        => 'Bazar',
'monday'        => 'Bazarertesi',
'tuesday'       => 'Salı',
'wednesday'     => 'Çarşenbe',
'thursday'      => 'Cumaaqşamı',
'friday'        => 'Cuma',
'saturday'      => 'Cumaertesi',
'sun'           => 'Bazar',
'mon'           => 'Bazarertesi',
'tue'           => 'Salı',
'wed'           => 'Çarşenbe',
'thu'           => 'Cumaaqşamı',
'fri'           => 'Cuma',
'sat'           => 'Cumaertesi',
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

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Saifeniñ kategoriyası|Saifeniñ kategoriyaları}}',
'category_header'                => '"$1" kategoriyasındaki saifeler',
'subcategories'                  => 'Alt kategoriyalar',
'category-media-header'          => '"$1" kategoriyasındaki media faylları',
'category-empty'                 => "''İşbu kategoriyada iç bir saife ya da media fayl yoq.''",
'hidden-categories'              => 'Gizli {{PLURAL:$1|kategoriya|kategoriyalar}}',
'hidden-category-category'       => 'Gizli kategoriyalar', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Bu kategoriyada tek bir aşağıdaki alt kategoriya bar.|Bu kategoriyada toplam $2 kategoriyadan aşağıdaki $1 alt kategoriya bar.}}',
'category-subcat-count-limited'  => 'Bu kategoriyada aşağıdaki $1 alt kategoriya bar.',
'category-article-count'         => '{{PLURAL:$2|Bu kategoriyada tek bir aşağıdaki saife bar.|Bu kategoriyadaki toplam $2 saifeden aşağıdaki $1 saife kösterilgen.}}',
'category-article-count-limited' => 'Bu kategoriyada aşağıdaki $1 saife bar.',
'category-file-count'            => '{{PLURAL:$2|Bu kategoriyada tek bir aşağıdaki fayl bar.|Bu kategoriyadaki toplam $2 fayldan aşağıdaki $1 fayl kösterilgen.}}',
'category-file-count-limited'    => 'Bu kategoriyada aşağıdaki $1 fayl bar.',
'listingcontinuesabbrev'         => ' (devam)',

'linkprefix'        => '/^(.*?)([a-zâçğıñöşüA-ZÂÇĞİÑÖŞÜa-яёА-ЯЁ«„]+)$/sDu',
'mainpagetext'      => "<big>'''MediaWiki muvafaqiyetnen quruldı.'''</big>",
'mainpagedocfooter' => "Bu vikiniñ yol-yoruğını [http://meta.wikimedia.org/wiki/Help:Contents User's Guide qullanıcı qılavuzından] ögrenip olasıñız.

== Bazı faydalı saytlar ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Olucı sazlamalar cedveli];
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki boyunca sıq berilgen suallernen cevaplar];
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki-niñ yañı versiyalarınıñ çıquvından haber yiberüv].",

'about'          => 'Aqqında',
'article'        => 'Saife',
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
'mytalk'         => 'Muzakere saifem',
'anontalk'       => 'Bu IP-niñ muzakeresi',
'navigation'     => 'Saytta yol tapuv',
'and'            => '&#32;ve',

# Metadata in edit box
'metadata_help' => 'Meta malümatı:',

'errorpagetitle'    => 'Hata',
'returnto'          => '$1.',
'tagline'           => '{{GRAMMAR:ablative|{{SITENAME}}}}',
'help'              => 'Yardım',
'search'            => 'Qıdıruv',
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
'create'            => 'Yarat',
'editthispage'      => 'Saifeni deñiştir',
'create-this-page'  => 'Bu saifeni yarat',
'delete'            => 'Yoq et',
'deletethispage'    => 'Saifeni yoq et',
'undelete_short'    => '{{PLURAL:$1|1|$1}} deñişiklikni keri ketir',
'protect'           => 'Qorçalavğa al',
'protect_change'    => 'deñiştir',
'protectthispage'   => 'Saifeni qorçalav altına al',
'unprotect'         => 'Qorçalavnı çıqar',
'unprotectthispage' => 'Saife qorçalavını çıqar',
'newpage'           => 'Yañı saife',
'talkpage'          => 'Saifeni muzakere et',
'talkpagelinktext'  => 'Muzakere',
'specialpage'       => 'Mahsus Saife',
'personaltools'     => 'Şahsiy aletler',
'postcomment'       => 'Yañı bölük',
'articlepage'       => 'Saifege bar',
'talk'              => 'Muzakere',
'views'             => 'Körünişler',
'toolbox'           => 'Aletler',
'userpage'          => 'Qullanıcı saifesini köster',
'projectpage'       => 'Proyekt saifesini köster',
'imagepage'         => 'Fayl saifesini köster',
'mediawikipage'     => 'Beyanat saifesisni köster',
'templatepage'      => 'Şablon saifesini köster',
'viewhelppage'      => 'Yardım saifesini köster',
'categorypage'      => 'Kategoriya saifesini köster',
'viewtalkpage'      => 'Muzakere saifesini köster',
'otherlanguages'    => 'Diger tillerde',
'redirectedfrom'    => '($1 saifesinden yollandı)',
'redirectpagesub'   => 'Yollama saifesi',
'lastmodifiedat'    => 'Bu saife soñki olaraq $2, $1 tarihında yañardı.', # $1 date, $2 time
'viewcount'         => 'Bu saife {{PLURAL:$1|1|$1}} defa irişilgen.',
'protectedpage'     => 'Qorçalavlı saife',
'jumpto'            => 'Buña bar:',
'jumptonavigation'  => 'qullan',
'jumptosearch'      => 'qıdır',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} aqqında',
'aboutpage'            => 'Project:Aqqında',
'copyright'            => 'Malümat $1 binaen keçilip ola.',
'copyrightpagename'    => '{{SITENAME}} müelliflik aqları',
'copyrightpage'        => '{{ns:project}}:Müelliflik aqları',
'currentevents'        => 'Ağımdaki vaqialar',
'currentevents-url'    => 'Project:Ağımdaki vaqialar',
'disclaimers'          => 'Cevapkârlıq redi',
'disclaimerpage'       => 'Project:Umumiy Malümat Muqavelesi',
'edithelp'             => 'Nasıl deñiştirilir?',
'edithelppage'         => 'Help:Saife nasıl deñiştirilir',
'faq'                  => 'Sıq berilgen sualler',
'faqpage'              => 'Project:Sıq berilgen sualler',
'helppage'             => 'Help:Münderice',
'mainpage'             => 'Baş Saife',
'mainpage-description' => 'Baş Saife',
'policy-url'           => 'Project:Qaideler',
'portal'               => 'Cemaat portalı',
'portal-url'           => 'Project:Cemaat portalı',
'privacy'              => 'Gizlilik esası',
'privacypage'          => 'Project:Gizlilik esası',

'badaccess'        => 'İzin hatası',
'badaccess-group0' => 'Yapacaq olğan areketiñizni yapmağa aqqıñız yoq.',
'badaccess-groups' => 'Yapacaq olğan areketiñizni tek aşağıdaki $2 gruppağa aza оlğan qullanıcıları yapıp olalar: $1.',

'versionrequired'     => 'MediaWikiniñ $1 versiyası kerek',
'versionrequiredtext' => 'Bu saifeni qullanmaq içün MediaWikiniñ $1 versiyası kerek. [[Special:Version|Versiya]] saifesine baq.',

'ok'                      => 'Ok',
'retrievedfrom'           => 'Menba – "$1"',
'youhavenewmessages'      => 'Yañı $1 bar ($2).',
'newmessageslink'         => 'beyanatıñız',
'newmessagesdifflink'     => 'muzakere saifeñizniñ soñki deñişikligi',
'youhavenewmessagesmulti' => '$1 saifesinde yañı beyanatıñız bar.',
'editsection'             => 'deñiştir',
'editold'                 => 'deñiştir',
'viewsourceold'           => 'menbanı kör',
'editlink'                => 'deñiştir',
'viewsourcelink'          => 'menba kоdunı köster',
'editsectionhint'         => 'Deñiştirilgen bölük: $1',
'toc'                     => 'Münderice',
'showtoc'                 => 'köster',
'hidetoc'                 => 'gizle',
'thisisdeleted'           => '$1 körmege ya da keri ketirmege isteysiñizmi?',
'viewdeleted'             => '$1 kör?',
'restorelink'             => 'yoq etilgen {{PLURAL:$1|1|$1}} deñişikligi',
'feedlinks'               => 'Bu şekilde:',
'feed-invalid'            => 'Abune kanalınıñ çeşiti yañlıştır.',
'feed-unavailable'        => 'Sindikatsiya lentaları qullanılıp оlamay.',
'site-rss-feed'           => '$1 RSS lentası',
'site-atom-feed'          => '$1 Atom lentası',
'page-rss-feed'           => '"$1" - RSS lentası',
'page-atom-feed'          => '"$1" - Atom lentası',
'red-link-title'          => '$1 (böyle saife yоq)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Saife',
'nstab-user'      => 'Qullanıcı saifesi',
'nstab-media'     => 'Media',
'nstab-special'   => 'Mahsus saife',
'nstab-project'   => 'Proyekt saifesi',
'nstab-image'     => 'Fayl',
'nstab-mediawiki' => 'Beyanat',
'nstab-template'  => 'Şablon',
'nstab-help'      => 'Yardım',
'nstab-category'  => 'Kategoriya',

# Main script and global functions
'nosuchaction'      => 'Öyle areket yoq',
'nosuchactiontext'  => 'URL-de belgilengen areket ruhsetsiz.
Belki de URL-ni yañlış yazğandırsız, ya da doğru olmağan bir bağlantını qullanğandırsız.
Bu, {{SITENAME}} saytındaki bir hatanı da kösterip оla.',
'nosuchspecialpage' => 'Bu isimde bir mahsus saife yoq',
'nospecialpagetext' => "<big>'''Tapılmağan bir mahsus saifege kirdiñiz.'''</big>

Bar olğan bütün mahsus saifelerni [[Special:SpecialPages|{{int:specialpages}}]] saifesinde körip olursıñız.",

# General errors
'error'                => 'Hata',
'databaseerror'        => 'Malümat bazasınıñ hatası',
'dberrortext'          => 'Malümat bazasına hata oldı.
Bu bir içki hatası ola bile.
"<tt>$2</tt>" funktsiyasından deñengen soñki sorğulama:
<blockquote><tt>$1</tt></blockquote>.
MySQL-niñ esabat etkeni hata "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Malümat bazasına muracaat etüvde sintaksis hatası çıqtı.
Malümat bazasına soñki muracaat:
"$1"
"$2" funktsiyasından asıl oldı.
MySQL "$3: $4" hatasını bildirdi.',
'noconnect'            => 'Bağışlañız! Tehnikiy problemalar sebebinden wiki malümat bazasınıñ serverinen bağlınıp olamay. <br /> $1',
'nodb'                 => '$1 malümat bazasını saylamağa çare yoq',
'cachederror'          => 'Aşağıda siz istegen saifeniñ keşirlengen kopiyasıdır. Bunıñ içün o eskirgen ola bile.',
'laggedslavemode'      => 'Diqqat! Bu saifede soñki yañaruv olmay bile.',
'readonly'             => 'Malümat bazası kilitlendi',
'enterlockreason'      => 'Blok etüvniñ sebebini ve tarihını belgileñiz.',
'readonlytext'         => 'Plan işlemelerinden sebep malümat bazası vaqtınca blok etildi. İşlemeler tamamlanğan soñ normalge dönecek.

Malümat bazasını kilitlegen administratornıñ açıqlaması: $1',
'missing-article'      => 'Malümat bazasında tapılması kerek olğan saifeniñ metini tapılmadı, "$1" $2.
 
Adetince yoq etilgen saifeniñ keçmiş saifesine eskirgen bağlantınen keçip baqqanda bu şey olıp çıqa.
 
Mesele bunda olmasa, ihtimalı bar ki, programmada bir hata tapqandırsıñız.
Lütfen, URL yazıp bundan [[Special:ListUsers/sysop|administratorğa]] haber beriñiz.',
'missingarticle-rev'   => '(versiya No. $1)',
'missingarticle-diff'  => '(Farq: $1, $2)',
'readonly_lag'         => 'Malümat bazasınıñ ekilemci serveri birlemci serverinen sinhronizirlengence malümat bazası deñiştirilmemesi içün avtomatik olaraq blok etildi.',
'internalerror'        => 'İçki hata',
'internalerror_info'   => 'İçki hata: $1',
'filecopyerror'        => '"$1" faylı "$2" faylına kopiyalanıp olamay.',
'filerenameerror'      => 'faylnıñ "$1" degen adı "$2" olaraq deñiştirilip olamay.',
'filedeleteerror'      => '"$1" faylı yoq etilip olamay.',
'directorycreateerror' => '"$1" direktoriyası yaratılıp olamay.',
'filenotfound'         => '"$1" faylı tapılıp olamay.',
'fileexistserror'      => '"$1" faylı saqlanıp olamay. Öyle fayl endi mevcut.',
'unexpected'           => 'beklenmegen deger: "$1"="$2".',
'formerror'            => 'Hata: formanıñ malümatını yollamaqnıñ iç çaresi yoq',
'badarticleerror'      => 'Siz yapmağa istegen işlev bu saifede yapılıp оlamay.',
'cannotdelete'         => 'Belgilengen saife ya da körüniş yoq etilip olamadı. (başqa bir qullanıcı tarafından yoq etilgen ola bilir).',
'badtitle'             => 'Ruhsetsiz serleva',
'badtitletext'         => 'İstenilgen saife adı doğru degil, boş yahut interviki ya da tillerara adı doğru belgilenmegen. İhtimalı bar ki, saife adında yasaqlanğan işaretler qullanıladır.',
'perfcached'           => 'Malümatlar daa evelceden azırlanğan ola bilir. Bu sebepten eskirgen ola bilir!',
'perfcachedts'         => 'Aşağıda keşte saqlanğan malümat buluna, soñki yañaruv zamanı: $1.',
'querypage-no-updates' => 'Bu saifeni deñiştirmege şimdi izin yoq. Bu malümat aman yañartılmaycaq.',
'wrong_wfQuery_params' => 'wrong_wfQuery_params - wfQuery() funktsiyası içün izinsiz parametrler<br />
Funktsiya: $1<br />
İstintaq: $2',
'viewsource'           => 'menba kodunı köster',
'viewsourcefor'        => '$1 içün',
'actionthrottled'      => 'Areket toqtaldı',
'actionthrottledtext'  => 'Spamğa qarşı küreş sebebinden bu areketni az vaqıt içinde çoq kere tekrarlap olamaysıñız. Mümkün olğan qarardan ziyade areket yaptıñız. Bir qaç daqqadan soñ tekrarlap baqıñız.',
'protectedpagetext'    => 'Bu saifeni kimse deñiştirmesin dep o blok etildi.',
'viewsourcetext'       => 'Saifeniñ kodunı közden keçirip kopiyalay bilesiñiz:',
'protectedinterface'   => 'Bu saifede sistema interfeysiniñ metini bulunğanı içün mında hata çıqmasın dep deñişiklik yapmaq yasaq.',
'editinginterface'     => "'''Tenbi''': MediaWiki sistema beyanatılı bir saifeni deñiştirmektesiñiz. Bu saifedeki deñişiklikler qullanıcı interfeys körünişini diger qullanıcılar içün de deñiştirecek. Lütfen, tercimeler içün [http://translatewiki.net/wiki/Main_Page?setlang=crh translatewiki.net] saytını (MediaWiki resmiy lokalizatsiya proyekti) qullanıñız.",
'sqlhidden'            => '(SQL istintağı saqlı)',
'cascadeprotected'     => 'Bu saifeni deñiştirip olamazsıñız, çünki kaskad qorçalav altında bulunğan {{PLURAL:$1|saifege|saifelerge}} mensüptir:
$2',
'namespaceprotected'   => "'''$1''' isim fezasında saifeler deñiştirmege aqqıñız yoq.",
'customcssjsprotected' => 'Bu saifede diger qullanıcınıñ şahsiy sazlamaları bar olğanı içün saifeni deñiştirip olamazsıñız.',
'ns-specialprotected'  => '{{ns:special}} isim fezasındaki saifelerni deñiştirmek yasaq.',
'titleprotected'       => "Böyle serlevanen saife yaratmaq yasaqtır. Yasaqlağan: [[User:$1|$1]].
Sebep: ''$2''.",

# Login and logout pages
'logouttitle'                => 'Oturımnı qapat',
'logouttext'                 => "'''Oturımnı qapattıñız.'''

Şimdi {{SITENAME}} saytını anonim olaraq qullanıp olasıñız, ya da yañıdan [[Special:UserLogin|oturım açıp]] olasıñız (ister aynı qullanıcı adınen, ister başqa bir qullanıcı adınen). Web brauzeriñiz keşini temizlegence bazı saifeler sanki alâ daa oturımıñız açıq eken kibi körünip olur.",
'welcomecreation'            => '== Hoş keldiñiz, $1! ==
Esabıñız açıldı.
Bu saytnıñ [[Special:Preferences|sazlamalarını]] şahsıñızğa köre deñiştirmege unutmañız.',
'loginpagetitle'             => 'Oturım aç',
'yourname'                   => 'Qullanıcı adıñız',
'yourpassword'               => 'Paroliñiz',
'yourpasswordagain'          => 'Parolni yañıdan yaz',
'remembermypassword'         => 'Bu kompyuterde meni hatırla',
'yourdomainname'             => 'Domen adıñız',
'externaldberror'            => 'Oturımıñız açılğanda bir hata oldı. Bu tış esabıñızğa deñişiklik yapmağa aqqıñız olmayuvından meydanğa kelip ola.',
'login'                      => 'Oturım aç',
'nav-login-createaccount'    => 'Oturım aç / Qayd ol',
'loginprompt'                => 'Oturım açmaq içün "cookies"ge izin bermelisiñiz.',
'userlogin'                  => 'Oturım aç / Qayd ol',
'logout'                     => 'Oturımnı qapat',
'userlogout'                 => 'Çıqış',
'notloggedin'                => 'Oturım açmadıñız.',
'nologin'                    => 'Daa esap açmadıñızmı? $1.',
'nologinlink'                => 'Qayd ol',
'createaccount'              => 'Yañı esap aç',
'gotaccount'                 => 'Daa evel esap açqan ediñizmi? $1.',
'gotaccountlink'             => 'Oturım açıñız',
'createaccountmail'          => 'e-mail vastasınen',
'badretype'                  => 'Siz belgilegen paroller bir birinen teñ degil.',
'userexists'                 => 'Belgilegeniñiz adlı qullanıcı endi bar. Başqa bir qullanıcı adı belgileñiz.',
'youremail'                  => 'E-mail adresiñiz:',
'username'                   => 'Qullanıcı adı:',
'uid'                        => 'Qayd nomeri:',
'prefs-memberingroups'       => 'Azası olğan {{PLURAL:$1|gruppa|gruppalar}}:',
'yourrealname'               => 'Kerçek adıñız:',
'yourlanguage'               => 'İnterfeys tili:',
'yourvariant'                => 'Til saylavı:',
'yournick'                   => 'Siziñ lağabıñız (imzalarda kösterilecek):',
'badsig'                     => 'Yañlış imza. HTML tegleriniñ doğrulığını baqıñız.',
'badsiglength'               => 'Qarardan ziyade uzun imzadır, $1 ziyade işaretten ibaret olması mümkün degil.',
'yourgender'                 => 'Cınıs:',
'gender-unknown'             => 'Belgilenmegen',
'gender-male'                => 'Erkek',
'gender-female'              => 'Qadın',
'prefs-help-gender'          => 'Mecburiy degil: wiki tarafından doğru cınıs adreslevi içün qullanıla. Bu malümat umumiy olacaq.',
'email'                      => 'E-mail',
'prefs-help-realname'        => 'Adıñız (mecburiy degildir): Eger belgileseñiz, saifelerdeki deñişikliklerini kimniñ yapqanını köstermek içün qullanılacaq.',
'loginerror'                 => 'Oturım açma hatası',
'prefs-help-email'           => 'E-mail (mecburiy degildir). E-mail adresi belgilengen olsa, şimdiki paroliñizni unutsañız, yañı bir parol istep olasıñız.
Em de bu vikideki saifeñizden diger qullanıcılarğa siznen bağlanmağa imkân berecek. E-mail adresiñiz başqa qullanıcılarğa kösterilmeycek.',
'prefs-help-email-required'  => 'E-mail adresi lâzim.',
'nocookiesnew'               => 'Qullanıcı esabı açılğan, faqat tanıtılmağan. {{SITENAME}} qullanıcılarnı tanıtmaq içün "cookies"ni qullana. Sizde bu funktsiya qapalı vaziyettedir. "Cookies" funktsiyasını işletip tekrar yañı adıñız ve paroliñiznen tırışıp baqınız.',
'nocookieslogin'             => '{{SITENAME}} "cookies"ni qullana. Sizde bu funktsiya qapalı vaziyettedir. "Cookies" funktsiyasını işletip tekrar tırışıp baqıñız.',
'noname'                     => 'Qullanıcı adını belgilemediñiz.',
'loginsuccesstitle'          => 'Kiriş yapıldı',
'loginsuccess'               => "'''$1 adınen {{SITENAME}} saytında çalışıp olasıñız.'''",
'nosuchuser'                 => '"$1" adlı qullanıcı yoq. Doğru yazğanıñıznı teşkeriñiz ya da [[Special:UserLogin/signup|yañı qullanıcı esabını açıñız]].',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" adlı qullanıcı tapılamadı. Adıñıznı doğru yazğanıñızdan emin oluñız.',
'nouserspecified'            => 'Qullanıcı adını belgilemek kereksiñiz.',
'wrongpassword'              => 'Kirsetken paroliñiz yañlıştır. Lütfen, tekrar etiñiz.',
'wrongpasswordempty'         => 'Kirsetken parоliñiz bоştır.
Lütfen, tekrar etiñiz.',
'passwordtooshort'           => 'Paroliñiz pek qısqa. Eñ az $1 arif ve ya raqamdan ibaret olmalı.',
'mailmypassword'             => 'Yañı parol yiber',
'passwordremindertitle'      => '{{grammar:genitive|{{SITENAME}}}} qullanıcınıñ parol hatırlatuvı',
'passwordremindertext'       => 'Birev (belki de bu sizsiñiz, $1 IP adresinden) {{SITENAME}} saytı içün ($4) yañı qullanıcı parolini istedi.
$2 qullanıcısına vaqtınca <code>$3</code> paroli yaratıldı. Eger bu kerçekten de siziñ istegiñiz olğan olsa, oturım açıp yañı bir parol yaratmañız kerektir. Muvaqqat paroliñizniñ müddeti {{PLURAL:$5|1 kün|$5 kün}} içinde dolacaq.

Eger de yañı parol talap etmegen olsañız ya da eski paroliñizni hatırlap endi onı deñiştirmege istemeseñiz, bu mektüpni diqqatqa almayıp eski paroliñizni qullanmağa devam etip olasıñız.',
'noemail'                    => '$1 adlı qullanıcı içün e-mail belgilenmedi.',
'passwordsent'               => 'Yañı parol e-mail yolunen qullanıcınıñ belgilegen $1 adresine yiberildi. Parolni alğan soñ tekrar kiriş yapıñız.',
'blocked-mailpassword'       => 'IP adresiñizden saifeler deñiştirüv yasaqlı, parol hatırlatuv funktsiyası da blok etildi.',
'eauthentsent'               => 'Belgilengen e-mail adresine adresni deñiştirüv tasdıqını soraycaq bir mektüp yollandı. Em de mektüpte bu e-mail adresine kerçekten siz saipsiñiz dep tasdıqlamaq içün yapılması kerek areketler tasvir etilgen.',
'throttled-mailpassword'     => 'Parol hatırlatuv funktsiyası endi soñki $1 saat devamında işletilgen edi. $1 saat içinde tek bir hatırlatuv işletmek mümkün.',
'mailerror'                  => 'Poçta yiberilgende bir hata meydanğa keldi: $1',
'acct_creation_throttle_hit' => 'Siz endi $1 dane qullanıcı esabını açtıñız. Daa ziyade açtıramazsıñız.',
'emailauthenticated'         => 'E-mail adresiñiz $2 $3 tarihında tasdıqlandı.',
'emailnotauthenticated'      => 'E-mail adresiñiz tasdıqlanmadı, vikiniñ e-mail ile bağlı funktsiyaları çalışmaycaq.',
'noemailprefs'               => 'Bu funktsiyalarnıñ çalışması içün bir e-mail adresi belgileñiz.',
'emailconfirmlink'           => 'E-mail adresiñizni tasdıqlañız',
'invalidemailaddress'        => 'Yazğan adresiñiz e-mail standartlarında olmağanı içün qabul etilmedi. Lütfen, doğru adresni yazıñız ya da qutunı boş qaldırıñız.',
'accountcreated'             => 'Esap açıldı',
'accountcreatedtext'         => '$1 içün bir qullanıcı esabı açıldı.',
'createaccount-title'        => '{{SITENAME}} saytında yañı bir esap yaratıluvı',
'createaccount-text'         => 'Birev siziñ e-mail adresini belgilep {{SITENAME}} saytında ($4) "$2" adlı bir esap yarattı.
Şu esap içün parol böyledir: "$3".
Siz oturım açıp paroliñizni şimdi deñiştirmek kereksiñiz.

İşbu esap hata olaraq yaratılğan olsa bu beyanatnı ignor etip olasıñız.',
'login-throttled'            => 'Bu esap içün parolni pek çoq kere kirsetip baqtıñız. Lütfen, qayta kirsetmezden evel biraz bekleñiz.',
'loginlanguagelabel'         => 'Til: $1',

# Password reset dialog
'resetpass'                 => 'Parolni deñiştir',
'resetpass_announce'        => 'Muvaqqat kod vastasınen kirdiñiz. Kirişni tamamlamaq içün yañı parolni mında qoyuñız:',
'resetpass_header'          => 'Esapnıñ parolini deñiştir',
'oldpassword'               => 'Eski parol',
'newpassword'               => 'Yañı parol',
'retypenew'                 => 'Yañı parolni tekrar yazıñız',
'resetpass_submit'          => 'Parol qoyıp kir',
'resetpass_success'         => 'Paroliñiz muvafaqiyetnen deñiştirildi! Oturımıñız açılmaqta...',
'resetpass_bad_temporary'   => 'Muvaqqat paroliñiz yañlıştır. Ola bilir ki, siz endi paroliñizni muvafaqiyetnen deñiştirgen ya da e-mail-ge yañı bir parol yollamağa rica etkendirsiñiz.',
'resetpass_forbidden'       => 'Parol deñiştirmek yasaq',
'resetpass-no-info'         => 'Bu saifege doğrudan irişmek içün oturım açmaq kereksiñiz.',
'resetpass-submit-loggedin' => 'Parolni deñiştir',
'resetpass-wrong-oldpass'   => 'Ruhsetsiz muvaqqat ya da al-azırki parоl.
Parоliñizni endi muvafaqiyetnen deñiştirdiñiz ya da yañı bir muvaqqat parоl istediñiz.',
'resetpass-temp-password'   => 'Muvaqqat parol:',

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
'headline_tip'    => '2-nci seviye serleva',
'math_sample'     => 'Bu yerge formulanı kirsetiñiz',
'math_tip'        => 'Riyaziy (matematik) formula (LaTeX formatında)',
'nowiki_sample'   => 'Serbest format metiniñizni mında yazıñız.',
'nowiki_tip'      => 'viki format etüvini ignor et',
'image_sample'    => 'Resim.jpg',
'image_tip'       => 'Endirilgen fayl',
'media_sample'    => 'Ses.ogg',
'media_tip'       => 'Media faylına bağlantı',
'sig_tip'         => 'İmzañız ve tarih',
'hr_tip'          => 'Gorizontal sızıq (pek sıq qullanmañız)',

# Edit pages
'summary'                          => 'Deñişiklik qısqa tasviri:',
'subject'                          => 'Mevzu/serleva:',
'minoredit'                        => 'Kiçik deñişiklik',
'watchthis'                        => 'Saifeni közet',
'savearticle'                      => 'Saifeni saqla',
'preview'                          => 'Baqıp çıquv',
'showpreview'                      => 'Baqıp çıq',
'showlivepreview'                  => 'Tez baqıp çıquv',
'showdiff'                         => 'Deñişikliklerni köster',
'anoneditwarning'                  => "'''Diqqat''': Oturım açmağanıñızdan sebep siziñ IP adresiñiz deñişiklik tarihına yazılır.",
'missingsummary'                   => "'''Hatırlatma.''' Deñiştirmeleriñizni qısqadan tarif etmediñiz. \"Saifeni saqla\" dögmesine tekrar basuv ile deñiştirmeleriñiz tefsirsiz saqlanacaqlar.",
'missingcommenttext'               => 'Lütfen, aşağıda tefsir yazıñız.',
'missingcommentheader'             => "'''Hatırlatuv:''' Tefsir serlevasını belgilemediñiz. \"Saifeni saqla\" dögmesine tekrar basqan soñ tefsiriñiz serlevasız saqlanır.",
'summary-preview'                  => 'Baqıp çıquv tasviri:',
'subject-preview'                  => 'Baqıp çıquv serlevası:',
'blockedtitle'                     => 'Qullanıcı blok etildi.',
'blockedtext'                      => '<big>\'\'\'Esabıñız ya da IP adresiñiz blok etildi.\'\'\'</big>

Blok yapqan administrator: $1.
Blok sebebi: \'\'"$2"\'\'.

* Bloknıñ başı: $8
* Bloknıñ soñu: $6
* Blok etilgen: $7

Blok etüvni muzakere etmek içün $1 qullanıcısına ya da başqa er angi [[{{MediaWiki:Grouppage-sysop}}|administratorğa]] mektüp yollap olasıñız.
Diqqat etiñiz ki, qayd olunmağan ve e-mail adresiñizni [[Special:Preferences|şahsiy sazlamalarda]] tasdıqlamağan alda, em de blok etilgende sizge mektüp yollamaq yasaq etilgen olsa, administratorğa mektüp yollap olamazsıñız.
IP adresiñiz — $3, blok etüv identifikatorı — #$5. Lütfen, administratorlarğa mektüpleriñizde bu malümatnı belgileñiz.',
'autoblockedtext'                  => 'IP adresiñiz evelde blok etilgen qullanıcılardan biri tarafından qullanılğanı içün avtomatik olaraq blok etildi. Onı blok etken administrator ($1) böyle sebepni belgiledi:

:"$2"

* Bloknıñ başı: $8
* Bloknıñ soñu: $6
* Blok etilgen: $7

Blok etüvni muzakere etmek içün $1 qullanıcığa ya da başqa er angi [[{{MediaWiki:Grouppage-sysop}}|administratorğa]] mektüp yollap olasıñız.
Diqqat etiñiz ki, qayd olunmağan ve e-mail adresiñizni [[Special:Preferences|şahsiy sazlamalarda]] tasdıqlamağan alda, em de blok etilgende sizge mektüp yollamaq yasaq etilgen olsa, administratorğa mektüp yollap olamazsıñız.
IP adresiñiz — $3, blok etüv identifikatorı — #$5. Lütfen, administratorlarğa mektüpleriñizde onı belgileñiz.',
'blockednoreason'                  => 'sebep belgilenmedi',
'blockedoriginalsource'            => 'Aşağıda "$1" saifesiniñ metini buluna.',
'blockededitsource'                => "Aşağıda \"\$1\" saifesindeki '''yapqan deñiştirmeleriñizniñ''' metini buluna.",
'whitelistedittitle'               => 'Deñiştirmek içün oturım açmalısıñız',
'whitelistedittext'                => 'Saifeni deñiştirmek içün $1 kereksiñiz.',
'confirmedittitle'                 => 'E-mail adresini tasdıqlamaq lâzimdir',
'confirmedittext'                  => 'Saifeni deñiştirmeden evel e-mail adresiñizni tasdıqlamalısıñız. Lütfen, [[Special:Preferences|sazlamalar saifesinde]] e-mail adresiñizni kirsetiñiz ve tasdıqlañız.',
'nosuchsectiontitle'               => 'Öyle bölük yoq',
'nosuchsectiontext'                => 'Bar olmağan bölükni deñiştirip baqtıñız. $1 bölügi yoq olğanı içün metniñiz saqlanacaq yeri yoq.',
'loginreqtitle'                    => 'Oturım açmalısıñız',
'loginreqlink'                     => 'oturım aç',
'loginreqpagetext'                 => 'Başqa saifelerni baqmaq içün $1 borclusıñız.',
'accmailtitle'                     => 'Parol yollandı',
'accmailtext'                      => "[[User talk:$1|$1]] içün tesadufiy işaretlerden yaratılğan parol $2 adresine yollandı.

Bu yañı esap içün parol, kiriş yapqandan soñ ''[[Special:ChangePassword|parolni deñiştir]]'' bölüginde deñiştirilip olur.",
'newarticle'                       => '(Yañı)',
'newarticletext'                   => "Siz bu bağlantınen şimdilik yoq olğan saifege avuştıñız. Yañı bir saife yaratmaq içün aşağıda bulunğan pencerege metin yazıñız (tafsilâtlı malümat almaq içün [[{{MediaWiki:Helppage}}|yardım saifesine]] baqıñız). Bu saifege tesadüfen avuşqan olsañız, brauzeriñizdeki '''keri''' dögmesine basıñız.",
'anontalkpagetext'                 => "----''Bu muzakere saifesi şimdilik qayd olunmağan ya da oturımını açmağan adsız (anonim) qullanıcığa mensüptir. İdentifikatsiya içün IP adres işletile. 
Bir IP adresinden bir qaç qullanıcı faydalanıp ola.
Eger siz anonim qullanıcı olsañız ve sizge kelgen beyanatlarnı yañlıştan kelgenini belleseñiz, lütfen, artıq bunıñ kibi qarışıqlıq olmasın dep [[Special:UserLogin|oturım açıñız]].''",
'noarticletext'                    => 'Bu saife şimdi boştır. Bu serlevanı başqa saifelerde [[Special:Search/{{PAGENAME}}|qıdırıp olasıñız]], <span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} bağlı jurnal qaydlarını qıdırıp olasıñız] ya da bu saifeni özüñiz [{{fullurl:{{FULLPAGENAME}}|action=edit}} yazıp olasıñız]</span>.',
'userpage-userdoesnotexist'        => '"$1" adlı qullanıcı yoqtır. Tamam bu saifeni deñiştirmege istegeniñizni teşkeriñiz.',
'clearyourcache'                   => "'''İhtar:''' Sazlamalarıñıznı saqlağandan soñ deñişikliklerni körmek içün brauzeriñizniñ keşini temizlemek kereksiñiz.
'''Mozilla / Firefox / Safari:''' ''Shift'' basılı ekende saifeni yañıdan yüklep ya da ''Ctrl-Shift-R'' yapıp (Macintosh içün ''Command-R'');
'''Konqueror:''' saifeni yañıdan yükle dögmesine ya da F5 basıp;
'''Opera:''' ''Tools → Preferences'' menüsinde keşni temizlep;
'''Internet Explorer:''' ''Ctrl'' basılı ekende saifeni yañıdan yüklep ya da ''Ctrl-F5'' basıp.",
'usercssjsyoucanpreview'           => "'''Tevsiye:''' Saifeni saqlamazdan evel '''baqıp çıq''' dögmesine basıp yapqan yañı saifeñizni közden keçiriñiz.",
'usercsspreview'                   => "'''Unutmañız, bu tek baqıp çıquv - qullanıcı CSS faylıñız alâ daa saqlanmadı!'''",
'userjspreview'                    => "'''Unutmañız, siz şimdi tek test etesiñiz ya da baqıp çıquv köresiñiz - qullanıcı JavaScript'i şimdilik saqlanmadı.'''",
'userinvalidcssjstitle'            => "'''İhtar:''' \"\$1\" adınen bir tema yoqtır. tema-adı.css ve .js fayllarınıñ adları kiçik afir ile yazmaq kerek, yani {{ns:user}}:Temel/'''M'''onobook.css degil, {{ns:user}}:Temel/'''m'''onobook.css.",
'updated'                          => '(Yañardı)',
'note'                             => "'''İhtar:'''",
'previewnote'                      => "'''Bu tek baqıp çıquv, metin alâ daa saqlanmağan!'''",
'previewconflict'                  => 'Bu baqıp çıquv yuqarı tarir penceresindeki metinniñ saqlanuvdan soñ olacaq körünişini aks ete.',
'session_fail_preview'             => "''' Server siz yapqan deñiştirmelerni sessiya identifikatorı
coyulğanı sebebinden saqlap olamadı. Bu vaqtınca problemadır. Lütfen, tekrar saqlap baqıñız.
Bundan da soñ olıp çıqmasa, malümat lokal faylğa saqlañız da brauzeriñizni bir qapatıp
açıñız.'''",
'session_fail_preview_html'        => "'''Afu etiñiz! HTML sessiyanıñ malümatları ğayıp olğanı sebebinden siziñ deñiştirmeleriñizni qabul etmege imkân yoqtır.'''",
'token_suffix_mismatch'            => "'''Siziñ programmañız tarir penceresinde punktuatsiya işaretlerini doğru işlemegeni içün yapqan deñişikligiñiz qabul olunmadı. Deñişiklik saifeniñ metni körünişiniñ bozulmaması içün lâğu etildi.
Bunıñ kibi problemalar anonimizirlegen hatalı web-proksiler qullanuvdan çıqıp olalar.'''",
'editing'                          => '"$1" saifesini deñiştirmektesiñiz',
'editingsection'                   => '"$1" saifesinde bölük deñiştirmektesiñiz',
'editingcomment'                   => '$1 saifesini deñiştirmektesiñiz (yañı bölük)',
'editconflict'                     => 'Deñişiklik zıt ketüvi: $1',
'explainconflict'                  => "Siz saifeni deñiştirgen vaqıtta başqa biri de deñişiklik yaptı.
Yuqarıdaki yazı saifeniñ şimdiki alını köstere.
Siziñ deñişiklikleriñiz altqa kösterildi. Şimdi yapqan deñişiklikleriñizni aşağı pencereden yuqarı pencerege avuştırmaq kerek olacaqsıñız.
\"Saifeni saqla\"ğa basqanda '''tek''' yuqarıdaki yazı saqlanacaq.",
'yourtext'                         => 'Siziñ metniñiz',
'storedversion'                    => 'Saqlanğan metin',
'nonunicodebrowser'                => "'''TENBİ: Brauzeriñizde Unicode kodlaması tanılmaz. Saifeler deñiştirgende bütün ASCII olmağan işaretlerniñ yerine olarnıñ onaltılıq kodu yazılır.'''",
'editingold'                       => "'''DİQQAT: Saifeniñ eski bir versiyasında deñişiklik yapmaqtasıñız.
Saqlağanıñızda bu tarihlı versiyadan künümizge qadar olğan deñişiklikler yoq olacaq.'''",
'yourdiff'                         => 'Farqlar',
'copyrightwarning'                 => "'''Lütfen, diqqat:''' {{SITENAME}} saytına qoşulğan bütün isseler <i>$2</i> muqavelesi dairesindedir (tafsilât içün $1 saifesine baqıñız).
Qoşqan isseñizniñ başqa insanlar tarafından acımasızca deñiştirilmesini ya da azat tarzda ve sıñırsızca başqa yerlerge dağıtılmasını istemeseñiz, isse qoşmañız.<br />
Ayrıca, mında isse qoşıp, bu isseniñ özüñiz tarafından yazılğanına, ya da cemaatqa açıq bir menbadan ya da başqa bir azat menbadan kopiyalanğanına garantiya bergen olasıñız.<br />
'''<center>MÜELLİFLİK AQQINEN QORÇALANĞAN İÇ BİR METİNNİ MINDA RUHSETSİZ QOŞMAÑIZ!</center>'''",
'copyrightwarning2'                => "'''Lütfen, diqqat:''' {{SITENAME}} saytına siz qoşqan bütün isseler başqa bir qullanıcı tarafından deñiştirilip ya da yoq etilip olur. Qoşqan isseñizniñ başqa insanlar tarafından acımasızca deñiştirilmesini ya da azat tarzda ve sıñırsızca başqa yerlerge dağıtılmasını istemeseñiz, isse qoşmañız.<br />
Ayrıca, mında isse qoşıp, bu isseniñ özüñiz tarafından yazılğanına, ya da cemaatqa açıq bir menbadan ya da başqa bir azat menbadan kopiyalanğanına garantiya bergen olasıñız ($1 baqıñız).<br />
'''MÜELLİFLİK AQQINEN QORÇALANĞAN İÇ BİR METİNNİ MINDA RUHSETSİZ QOŞMAÑIZ!'''",
'longpagewarning'                  => "'''TENBİ: Bu saife $1 kilobayt büyükligindedir; bazı brauzerler deñişiklik yapqan vaqıtta 32 kb ve üstü büyükliklerde problemalar yaşap olur. Saifeni parçalarğa ayırmağa tırışıñız.'''",
'longpageerror'                    => "'''TENBİ: Bu saife $1 kilobayt büyükligindedir. Azamiy (maksimal) izinli büyüklik ise $2 kilobayt. Bu saife saqlanıp olamaz.'''",
'readonlywarning'                  => "'''TENBİ: Baqım sebebi ile malümat bazası al-azırda kilitlidir. Bu sebepten deñişiklikleriñiz şimdi saqlap olamasıñız. Yazğanlarıñıznı başqa bir editor programmasına alıp saqlap olur ve daa soñ tekrar mında ketirip saqlap olursıñız'''

Malümat bazasını kilitlegen administratоr öz areketini böyle añlattı: $1",
'protectedpagewarning'             => "'''TENBİ: Bu saife qorçalav altına alınğan ve yalıñız administratorlar tarafından deñiştirilip olur.'''",
'semiprotectedpagewarning'         => "'''Tenbi''': Bu saife tek qaydlı qullanıcılar tarafından deñiştirilip olur.",
'cascadeprotectedwarning'          => "'''Tenbi:''' Bu saifeni tek \"Administratorlar\" gruppasına kirgen qullanıcılar deñiştirip olalar, çünki o kaskad qorçalav altında bulunğan {{PLURAL:\$1|saifege|saifelerge}} mensüptir:",
'titleprotectedwarning'            => "'''TENBİ: Bu saife qorçalav altındadır, tek [[Special:ListGroupRights|mahsus aqlarğa]] saip qullanıcılar onı yaratıp olalar.'''",
'templatesused'                    => 'Bu saifede qullanılğan şablonlar:',
'templatesusedpreview'             => 'Siz baqıp çıqqan saifeñizde qullanılğan şablonlar:',
'templatesusedsection'             => 'Bu bölükte qullanılğan şablonlar:',
'template-protected'               => '(qorçalav altında)',
'template-semiprotected'           => '(qısmen qorçalav altında)',
'hiddencategories'                 => 'Bu saife $1 gizli kategoriyağa mensüptir:',
'nocreatetitle'                    => 'Saife yaratuv sıñırlıdır',
'nocreatetext'                     => '{{SITENAME}} saytında yañı saife yaratuv sıñırlıdır.
Keri qaytıp mevcut olğan saifeni deñiştire, [[Special:UserLogin|oturım aça ya da yañı bir esap yaratıp olasıñız]].',
'nocreate-loggedin'                => 'Yañı saifeler yaratmağa iziniñiz yoqtır.',
'permissionserrors'                => 'İrişim aqlarınıñ hataları',
'permissionserrorstext'            => 'Bunı yapmağa iziniñiz yoqtır. {{PLURAL:$1|Sebep|Sebepler}}:',
'permissionserrorstext-withaction' => 'Aşağıdaki {{PLURAL:$1|sebepten|sebeplerden}} $2 işlemini yapmağa yetkiñiz yoq:',
'recreate-deleted-warn'            => "'''Diqqat: evelce yoq etilgen saifeni yañıdan yaratmağa tırışasıñız.'''

Bu saifeni kerçekten de yañıdan yaratmağa isteysiñizmi? Aşağıda yoq etilüv jurnalı buluna.",
'deleted-notice'                   => 'Bu saife yoq etildi.
Yoq etüv jurnalından qaydları aşağıda kösterile.',
'edit-gone-missing'                => 'Saifeniñ yañaruvı mümkün degil.
Ola bile ki, o yoq etilgendir.',
'edit-conflict'                    => 'Deñişiklikler konflikti.',
'edit-no-change'                   => 'Yapqan deñişikligiñiz saqlanmağan, çünki metinde bir türlü deñişiklik yapılmadı.',
'edit-already-exists'              => 'Yañı saifeni yaratmaq mümkün degil.
O endi mevcut.',

# "Undo" feature
'undo-success' => 'Deñişiklik lâğu etile bile. Lütfen, aynı bu deñişiklikler meni meraqlandıra dep emin olmaq içün versiyalar teñeştirilüvini közden keçirip deñişikliklerni tamamen yapmaq içün "Saifeni saqla" dögmesine basıñız.',
'undo-failure' => 'Aradaki deñişiklikler biri-birine kelişikli olmağanı içün deñişiklik lâğu etilip olamay.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|muzakere]]) qullanıcısınıñ $1 nomeralı deñişikligini lâğu etüv.',

# Account creation failure
'cantcreateaccounttitle' => 'Esap yaratmaqnıñ iç çaresi yoq.',
'cantcreateaccount-text' => "Bu IP adresinden ('''$1''') esap yaratuv [[User:$3|$3 qullanıcı]] tarafından blok etildi.

$3 mına böyle bir sebep belgiledi: ''$2''",

# History pages
'viewpagelogs'           => 'Bu saifeniñ jurnallarını köster',
'nohistory'              => 'Bu saifeniñ keçmiş versiyası yoq.',
'currentrev'             => 'Al-azırki versiya',
'currentrev-asof'        => '$1 tarihında sоñki оlaraq deñiştirilgen saifeniñ şimdiki alı',
'revisionasof'           => 'Saifeniñ $1 tarihındaki alı',
'revision-info'          => 'Saifeniñ $2 tarafından oluştırılğan $1 tarihındaki alı', # Additionally available: $3: revision id
'previousrevision'       => '← Evelki alı',
'nextrevision'           => 'Soñraki alı →',
'currentrevisionlink'    => 'eñ yañı alını köster',
'cur'                    => 'farq',
'next'                   => 'soñraki',
'last'                   => 'soñki',
'page_first'             => 'ilk',
'page_last'              => 'soñki',
'histlegend'             => "(farq) = al-azırki versiya ile aradaki farq,
(soñki) = evelki versiya ile aradaki farq, '''k''' = kiçik deñişiklik",
'history-fieldset-title' => 'Keçmişke baq',
'deletedrev'             => '[yoq etildi]',
'histfirst'              => 'Eñ eski',
'histlast'               => 'Eñ yañı',
'historysize'            => '({{PLURAL:$1|1 bayt|$1 bayt}})',
'historyempty'           => '(boş)',

# Revision feed
'history-feed-title'          => 'Deñişiklikler tarihı',
'history-feed-description'    => 'Vikide bu saifeniñ deñişiklikler tarihı',
'history-feed-item-nocomment' => '$2 üstünde $1', # user at time
'history-feed-empty'          => 'İstenilgen saife mevcut degil.
O yoq eilgen ya da adı deñiştirilgen ola bile.
Vikide bu saifege oşağan saifelerni [[Special:Search|tapıp baqıñız]].',

# Revision deletion
'rev-deleted-comment'       => '(tefsir yoq etildi)',
'rev-deleted-user'          => '(qullanıcı adı yoq etildi)',
'rev-deleted-event'         => '(qayd yoq etildi)',
'rev-delundel'              => 'köster/gizle',
'revisiondelete'            => 'Versiyalarnı yoq et/keri ketir',
'revdelete-hide-comment'    => 'Qısqa tarifni kösterme',
'revdelete-hide-user'       => 'Deñişiklikni yapqan qullanıcı adını/IP-ni gizle',
'revdelete-hide-restricted' => 'Bu sıñırlavlarnı administratorlar ve qullanıcılar içün işlet',
'revdelete-submit'          => 'Saylanğan versiyağa işlet',

# Diffs
'history-title'           => '"$1" saifesiniñ deñişiklik tarihı',
'difference'              => '(Versiyalar arası farqlar)',
'lineno'                  => '$1 satır:',
'compareselectedversions' => 'Saylanğan versiyalarnı teñeştir',
'editundo'                => 'lâğu et',
'diff-multi'              => '({{PLURAL:$1|1 aradaki versiya|$1 aradaki versiya}} kösterilmedi.)',

# Search results
'searchresults'             => 'Qıdıruv neticeleri',
'searchresults-title'       => '"$1" içün qıdıruv neticeleri',
'searchresulttext'          => '{{SITENAME}} içinde qıdıruv yapmaq hususında malümat almaq içün [[{{MediaWiki:Helppage}}|{{int:help}}]] saifesine baqıp olasıñız.',
'searchsubtitle'            => 'Qıdırılğan: \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|"$1" ile başlanğan bütün saifeler]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" saifesine bağlantı olğan bütün saifeler]])',
'searchsubtitleinvalid'     => "Siz bunı qıdırdıñız '''$1'''",
'noexactmatch'              => "'''\"\$1\" serlevalı bir saife tapılamadı.''' Bu saifeni özüñiz [[:\$1|yaratıp olasıñız]].",
'noexactmatch-nocreate'     => "'''\"\$1\" adlı saife yoq.'''",
'titlematches'              => 'Saife adı bir kele',
'notitlematches'            => 'İç bir serlevada tapılamadı',
'textmatches'               => 'Saife metni bir kele',
'notextmatches'             => 'İç bir saifede tapılamadı',
'prevn'                     => 'evelki $1',
'nextn'                     => 'soñraki $1',
'viewprevnext'              => '($1) ($2) ($3).',
'searchhelp-url'            => 'Help:Münderice',
'search-result-size'        => '$1 ($2 söz)',
'search-result-score'       => 'Uyğunlıq: $1%',
'search-redirect'           => '(yollama $1)',
'search-section'            => '(bölük $1)',
'search-suggest'            => 'Bunımı demege istediñiz: $1',
'search-interwiki-caption'  => 'Qardaş prоyektler',
'search-interwiki-default'  => '$1 netice:',
'search-interwiki-more'     => '(daa çоq)',
'search-mwsuggest-enabled'  => 'tevsiyelernen',
'search-mwsuggest-disabled' => 'tevsiye yoq',
'search-relatedarticle'     => 'Bağlı',
'searchrelated'             => 'bağlı',
'searchall'                 => 'episi',
'showingresults'            => "Aşağıda №&nbsp;<strong>$2</strong>den başlap {{PLURAL:$1|'''1''' netice|'''$1''' netice}} buluna.",
'showingresultsnum'         => "Aşağıda №&nbsp;'''$2'''den başlap {{PLURAL:$3|'''1''' netice|'''$3''' netice}} buluna.",
'showingresultstotal'       => "Aşağıda {{PLURAL:$4|'''$3''' daneden '''$1''' netice kösterilgen|'''$3''' daneden '''$1 — $2''' netice kösterilgen}}",
'nonefound'                 => "'''İhtar.''' Adiycesine qıdıruv bütün isim fezalarında yapılmay. Bütün isim fezalarında (bu cümleden qullanıcılar subetleri, şablonlar ve ilâhre) qıdırmaq içün ''all:'' yazını qullanıñız ya da kerekli isim fezasını belgileñiz.",
'powersearch'               => 'Qıdır',
'search-external'           => 'Tış qıdıruv',
'searchdisabled'            => '{{SITENAME}} saytında qıdıruv yapma vaqtınca toqtatıldı. Bu arada Google qullanıp {{SITENAME}} içinde qıdıruv yapıp olasıñız. Qıdıruv saytlarında indekslemeleriniñ biraz eski qalğan ola bilecegini köz ögüne alıñız.',

# Preferences page
'preferences'           => 'Sazlamalar',
'mypreferences'         => 'Sazlamalarım',
'prefs-edits'           => 'Yapqan deñişiklik sayısı:',
'prefsnologin'          => 'Oturım açmadıñız',
'prefsnologintext'      => 'Şahsiy sazlamalarıñıznı deñiştirmek içün <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} oturım açmaq]</span> kereksiñiz.',
'prefsreset'            => 'Sazlamalar ilk alına ketirildi.',
'qbsettings'            => 'Vızlı irişim sutun sazlamaları',
'changepassword'        => 'Parol deñiştir',
'skin'                  => 'Resimleme',
'skin-preview'          => 'Baqıp çıquv',
'math'                  => 'Riyaziy (matematik) işaretler',
'dateformat'            => 'Tarih kösterimi',
'datedefault'           => 'Standart',
'datetime'              => 'Tarih ve saat',
'math_unknown_error'    => 'bilinmegen hata',
'math_unknown_function' => 'belgisiz funktsiya',
'math_lexing_error'     => 'leksik hata',
'math_syntax_error'     => 'sintaksis hatası',
'prefs-personal'        => 'Qullanıcı malümatı',
'prefs-rc'              => 'Soñki deñişiklikler',
'prefs-watchlist'       => 'Közetüv cedveli',
'prefs-watchlist-days'  => 'Közetüv cedvelinde kösterilecek kün sayısı:',
'prefs-watchlist-edits' => 'Kenişletilgen közetüv cedvelinde kösterilecek deñişiklik sayısı:',
'prefs-misc'            => 'Diger sazlamalar',
'saveprefs'             => 'Deñişikliklerni saqla',
'resetprefs'            => 'Saqlanmağan sazlamalarnı ilk alına ketir',
'textboxsize'           => 'Saife yazuv penceresi',
'rows'                  => 'Satır',
'columns'               => 'Sutun',
'searchresultshead'     => 'Qıdıruv',
'resultsperpage'        => 'Saifede kösterilecek tapılğan saife sayısı',
'contextlines'          => 'Tapılğan saife içün ayrılğan satır sayısı',
'contextchars'          => 'Satırdaki arif sayısı',
'recentchangesdays'     => 'Soñki deñişiklikler saifesinde kösterilecek kün sayısı:',
'recentchangescount'    => 'Çeşit-türlü cedvel ve jurnallarda kösterilgen deñişiklikler ög belgilengen sayısı:',
'savedprefs'            => 'Sazlamalarıñız saqlandı.',
'timezonelegend'        => 'Saat quşağı',
'timezonetext'          => 'Viki serveri (UTC/GMT) ile arañızdaki saat farqı. (Ukraina ve Türkiye içün +02:00)',
'localtime'             => 'Yerli vaqıt:',
'timezoneoffset'        => 'Saat farqı¹:',
'servertime'            => 'Serverniñ saatı:',
'guesstimezone'         => 'Brauzeriñiz siziñ yeriñizge toldursın',
'allowemail'            => 'Diger qullanıcılar maña e-mail mektüpleri yollap olsun',
'prefs-searchoptions'   => 'Qıdıruv sazlamaları',
'prefs-namespaces'      => 'İsim fezaları',
'defaultns'             => 'Qıdıruvnı aşağıda saylanğan isim fezalarında yap.',
'default'               => 'original',
'files'                 => 'Fayllar',

# User rights
'userrights'               => 'Qullanıcı aqlarını idare etüv', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'   => 'Qullanıcı gruppalarnını idare et',
'userrights-user-editname' => 'Öz qullanıcı adıñıznı yazıñız:',
'editusergroup'            => 'Qullanıcı gruppaları nizamla',
'editinguser'              => "'''[[User:$1|$1]]''' qullanıcısınıñ ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) izinlerini deñiştirmektesiñiz",
'userrights-editusergroup' => 'Qullanıcı gruppaları nizamla',
'userrights-groupsmember'  => 'Azası оlğan gruppalarıñız:',

# Groups
'group'            => 'Gruppa:',
'group-user'       => 'Qullanıcılar',
'group-bot'        => 'Botlar',
'group-sysop'      => 'Administratorlar',
'group-bureaucrat' => 'Bürokratlar',
'group-all'        => '(episi)',

'group-user-member'       => 'Qullanıcı',
'group-sysop-member'      => 'Administrator',
'group-bureaucrat-member' => 'Bürokrat',

'grouppage-user'       => '{{ns:project}}:Qullanıcılar',
'grouppage-bot'        => '{{ns:project}}:Botlar',
'grouppage-sysop'      => '{{ns:project}}:Administratorlar',
'grouppage-bureaucrat' => '{{ns:project}}:Bürokratlar',

# User rights log
'rightslog' => 'Qullanıcınıñ aqları jurnalı',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|deñişiklik|deñişiklik}}',
'recentchanges'                     => 'Soñki deñişiklikler',
'recentchanges-legend'              => 'Soñki deñişiklikler sazlamaları',
'recentchangestext'                 => 'Yapılğan eñ soñki deñişikliklerni bu saifede körip olasıñız.',
'recentchanges-feed-description'    => 'Bu lenta vastasınen vikide soñki deñişikliklerni közet.',
'rcnote'                            => "$4 $5 tarihında soñki {{PLURAL:$2|künde|'''$2''' künde}} yapılğan '''{{PLURAL:$1|1|$1}}''' deñişiklik:",
'rcnotefrom'                        => "'''$2''' tarihından itibaren yapılğan deñişiklikler aşağıdadır (eñ çоq '''$1''' dane saife kösterile).",
'rclistfrom'                        => '$1 tarihından berli yapılğan deñişikliklerni köster',
'rcshowhideminor'                   => 'kiçik deñişikliklerni $1',
'rcshowhidebots'                    => 'botlarnı $1',
'rcshowhideliu'                     => 'qaydlı qullanıcılarnı $1',
'rcshowhideanons'                   => 'anonim qullanıcılarnı $1',
'rcshowhidepatr'                    => 'közetilgen deñişikliklerni $1',
'rcshowhidemine'                    => 'menim deñişiklerimni $1',
'rclinks'                           => 'Soñki $2 künde yapılğan soñki $1 deñişiklikni köster;<br /> $3',
'diff'                              => 'farq',
'hist'                              => 'keçmiş',
'hide'                              => 'gizle',
'show'                              => 'köster',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'Y',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|qullanıcı|qullanıcı}} közete]',
'rc_categories'                     => 'Tek kategoriyalardan ("|" ile ayırıla)',
'rc_categories_any'                 => 'Er angi',
'newsectionsummary'                 => '/* $1 */ yañı bölük',

# Recent changes linked
'recentchangeslinked'          => 'Bağlı deñişiklikler',
'recentchangeslinked-title'    => '"$1" ile bağlı deñişiklikler',
'recentchangeslinked-noresult' => 'Saylanğan vaqıtta bağlı saifelerde iç deñişiklik yoq edi.',
'recentchangeslinked-summary'  => "Bu mahsus saifede bağlı saifelerde soñki yapqan deñişiklikler cedveli mevcut. [[Special:Watchlist|Közetüv cedveliñiz]]deki saifeler '''qalın''' olaraq kösterile.",

# Upload
'upload'                      => 'Fayl yükle',
'uploadbtn'                   => 'Fayl yükle',
'reupload'                    => 'Yañıdan yükle',
'reuploaddesc'                => 'Yükleme formasına keri qayt.',
'uploadnologin'               => 'Oturım açmadıñız',
'uploadnologintext'           => 'Fayl yüklep olmaq içün [[Special:UserLogin|oturım açmaq]] kereksiñiz.',
'upload_directory_missing'    => 'Yüklemeler içün direktoriya ($1) mevcut degil ve veb-server tarafından yapılıp olamay.',
'upload_directory_read_only'  => 'Web serverniñ ($1) cüzdanına fayllar saqlamağa aqları yoqtır.',
'uploaderror'                 => 'Yükleme hatası',
'uploadtext'                  => "Fayllar yüklemek içün aşağıdaki formanı qullanıñız.
Evelce yüklengen resim tapmaq ya da baqmaq içün [[Special:FileList|yüklengen fayllar cedveline]] keçiñiz, bundan ğayrı fayl yüklenüv ve yoq etilüv qaydlarını [[Special:Log/upload|yüklenüv jurnalında]] ve [[Special:Log/delete|yoq etilüv jurnalında]] tapıp olasıñız.

Saifede resim qullanmaq içün böyle şekilli bağlantılar qullanıñız: 
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' faylnıñ tam versiyasını qullanmaq içün,
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|tarif]]</nowiki></tt>''' bir tarif ile 200 piksel bir resim qullanmaq içün,
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' faylğa vastasız bağlantı içün.",
'upload-permitted'            => 'İzinli fayl çeşitleri: $1.',
'upload-preferred'            => 'İstenilgen fayl çeşitleri: $1.',
'upload-prohibited'           => 'Yasaqlı fayl çeşitleri: $1.',
'uploadlog'                   => 'yükleme jurnalı',
'uploadlogpage'               => 'Fayl yükleme jurnalı',
'uploadlogpagetext'           => 'Aşağıda eñ soñki qоşulğan fayllarnıñ cedveli buluna.
Daa körgezmeli körüniş içün [[Special:NewFiles|yañı fayllar galereyasına]] baqıñız.',
'filename'                    => 'Fayl',
'filedesc'                    => 'Faylğa ait qısqa tarif',
'fileuploadsummary'           => 'Qısqa tarif:',
'filestatus'                  => 'Darqatuv şartları:',
'filesource'                  => 'Menba:',
'uploadedfiles'               => 'Yüklengen fayllar',
'ignorewarning'               => 'Tenbini ignor etip faylnı yükle.',
'ignorewarnings'              => 'Tenbini ignor et',
'minlength1'                  => 'Faylnıñ adı eñ azdan bir ariften ibaret olmalı.',
'illegalfilename'             => '"$1" faylınıñ adında serleva içün yasaqlı işaretler bar. Lütfen, fayl adını deñiştirip yañıdan yüklep baqıñız.',
'badfilename'                 => 'Fayl adı $1 olaraq deñiştirildi.',
'filetype-badmime'            => '"$1" MIME çeşitindeki fayllar yükleme yasaqlıdır.',
'filetype-unwanted-type'      => "'''\".\$1\"''' — istenilmegen fayl çeşiti. 
İstenilgen {{PLURAL:\$3|fayl çeşiti|fayl çeşitleri}}: \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' — yasaqlı fayl çeşiti.
İstenilgen {{PLURAL:\$3|fayl çeşiti|fayl çeşitleri}}: \$2.",
'filetype-missing'            => 'Faylnıñ iç bir uzantısı yoq (meselâ ".jpg", ".gif" ve ilh.).',
'large-file'                  => 'Büyükligi $1 bayttan ziyade ibaret olmağan resimler qullanuv tevsiye etile (bu faylnıñ büyükligi $2 bayt).',
'largefileserver'             => 'Bu faylnıñ uzunlığı serverde izin berilgenden büyükçedir.',
'emptyfile'                   => 'İhtimal ki, yüklengen fayl boş. İhtimallı sebep - fayl adlandıruv
hatasıdır. Lütfen, tamam bu faylnı yüklemege isteycek ekeniñizni teşkeriñiz.',
'fileexists'                  => "Bu isimde bir fayl endi bar. Lütfen, eger siz deñiştirmekten emin olmasañız başta '''<tt>$1</tt>''' faylına köz taşlañız.",
'filepageexists'              => "Bu fayl içün tasvir saifesi endi yapılğan ('''<tt>$1</tt>'''), lâkin bu adda bir fayl yoqtır. Yazılğan tasvir resim tasvir saifesinde kösterilmeycek. Yañı bir tasvir qоşmaq içün onı qolnen deñiştirmege mecbursıñız.",
'fileexists-extension'        => "Buña oşağan adda bir fayl bar:<br />
Yüklengen faylnıñ adı: '''<tt>$1</tt>'''<br />
Mevcut olğan faylnıñ adı: '''<tt>$2</tt>'''<br />
Lütfen, başqa bir ad saylap yazıñız.",
'fileexists-thumb'            => "<center>'''Mevcut fayl'''</center>",
'fileexists-thumbnail-yes'    => "Belki de bu fayl bir küçülgen kopiyadır (thumbnail). Lütfen, '''<tt>$1</tt>''' faylını teşkeriñiz.<br />
Eger belgilengen fayl aynı şu resim olsa, onıñ küçülgen kopiyasını ayrı olaraq yüklemek aceti yoqtır.",
'file-thumbnail-no'           => "Faylnıñ adı '''<tt>$1</tt>'''nen başlana. Belki de bu resimniñ ufaqlaştırılğan bir kopiyasıdır ''(thumbnail)''.
Eger sizde bu resim tam büyükliginde bar olsa, lütfen, onı yükleñiñiz ya da faylnıñ adını deñiştiriñiz.",
'fileexists-forbidden'        => 'Bu isimde bir fayl endi bar, ve üzerine yazılamay.
Faylıñıznı yañıdan yüklemege isteseñiz, lütfen, keri qaytıp yañı bir isim qullanıñız.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Fayllar umumiy tutulğan yerinde bu isimde bir fayl endi bar.
Eger bu faylnı ep bir yüklemege isteseñiz, keri qaytıñız ve fayl ismini deñiştirip yañıdan yükleñiz.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Bu fayl aşağıdaki {{PLURAL:$1|faylnıñ|fayllarnıñ}} dublikatı ola:',
'successfulupload'            => 'Yüklenüv becerildi',
'uploadwarning'               => 'Tenbi',
'savefile'                    => 'Faylnı saqla',
'uploadedimage'               => 'Yüklengen: "[[$1]]"',
'overwroteimage'              => '"[[$1]]" yañı versiyası yüklendi',
'uploaddisabled'              => 'Yükleme yasaqlıdır.',
'uploaddisabledtext'          => 'Fayl yükleme yasaqlıdır.',
'uploadscripted'              => 'Bu faylda brauzer tarafından yañlışnen işlenip olur HTML kodu ya da skript bar.',
'uploadcorrupt'               => 'Bu fayl ya zararlandı, ya da yañlış uzantılı. Lütfen, faylnı teşkerip yañıdan yüklep baqıñız.',
'uploadvirus'                 => 'Bu fayl viruslıdır! $1 baqıñız',
'sourcefilename'              => 'Yüklemege istegeniñiz fayl:',
'destfilename'                => 'Faylnıñ istenilgen adı:',
'upload-maxfilesize'          => 'Azamiy (maksimal) fayl büyükligi: $1',
'watchthisupload'             => 'Bu faylnı közetüv cedveline kirset',
'filewasdeleted'              => 'Bu isimde bir fayl bar edi, amma yoq etilgen edi. Lütfen, tekrar yüklemeden evel $1 teşkeriñiz.',
'upload-wasdeleted'           => "'''Diqqat: Evelde yoq etilgen faylnı yüklemektesiñiz.'''

Er alda bu faylnı yüklemege devam etmege isteysiñizmi?
Bu fayl içün yoq etüvniñ jurnalını mında baqıp olasıñız:",
'filename-bad-prefix'         => "Siz yüklegen faylnıñ adı '''\"\$1\"'''-nen başlana. Bu, adetince, raqamlı fotoapparatlardan fayl adına yazılğan manasız işaretlerdir. Lütfen, bu fayl içün añlıca bir ad saylap yazıñız.",

'upload-proto-error'      => 'Yañlış protokol',
'upload-proto-error-text' => 'İnternetten bir resim faylı yüklemege isteseñiz adres <code>http://</code> ya da <code>ftp://</code>nen başlamalı.',
'upload-file-error'       => 'İçki hata',
'upload-file-error-text'  => 'Serverde muvaqqat fayl yaratılğan vaqıtta içki hata çıqtı. Lütfen, [[Special:ListUsers/sysop|administratorğa]] muracaat etiñiz.',
'upload-misc-error'       => 'Belgisiz yüklenüv hatası',
'upload-misc-error-text'  => 'Belgisiz yüklenüv hatası. Lütfen, adresniñ doğru olğanını teşkerip tekrarlañız. Problema devam etse, [[Special:ListUsers/sysop|administratorğa]] muracaat etiñiz.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Belgilengen URL adresine irişilip olamadı.',
'upload-curl-error6-text'  => 'Belgilengen URL adresine irişilip olamadı. Lütfen, adresniñ doğru ve saytqa irişmekniñ çaresi olğanını teşkerip tekrarlañız.',
'upload-curl-error28'      => 'Yüklenüv vaqtı toldı',
'upload-curl-error28-text' => 'Sayt çoqtan cevap qaytarmay. Lütfen, saytnıñ doğru çalışqanını teşkerip birazdan soñ tekrarlañız. Belki de istegen areketiñizni soñ, sayt boşça olğanda, etmek kerektir.',

'license'            => 'Litsenzirleme:',
'nolicense'          => 'Yoq',
'license-nopreview'  => '(Baqıp çıquv irişilmez)',
'upload_source_url'  => ' (doğru, püblik tarzda kirmege musaadeli internet adres)',
'upload_source_file' => ' (kompyuteriñizdeki fayl)',

# Special:ListFiles
'listfiles-summary'     => 'Bu mahsus saife bütün yüklengen fayllarnı köstere.
Yaqınlarda yüklengen fayllar cedvelniñ yuqarısında kösterile.
Sutun serlevasına bir basuv sortirlemeniñ tertibini deñiştirir.',
'listfiles_search_for'  => 'Fayl adını qıdıruv:',
'imgfile'               => 'fayl',
'listfiles'             => 'Resim cedveli',
'listfiles_date'        => 'Tarih',
'listfiles_name'        => 'Fayl adı',
'listfiles_user'        => 'Qullanıcı',
'listfiles_size'        => 'Büyüklik',
'listfiles_description' => 'Tasvir',

# File description page
'filehist'                  => 'Faylnıñ keçmişi',
'filehist-help'             => 'Faylnıñ kerekli anki alını körmek içün tarihqa/saatqa basıñız.',
'filehist-deleteall'        => 'episini yoq et',
'filehist-deleteone'        => 'yoq et',
'filehist-revert'           => 'keri al',
'filehist-current'          => 'al-azırki',
'filehist-datetime'         => 'Tarih ve saat',
'filehist-user'             => 'Qullanıcı',
'filehist-dimensions'       => 'En × boy',
'filehist-filesize'         => 'Fayl büyükligi',
'filehist-comment'          => 'İzaat',
'imagelinks'                => 'Fayl bağlantıları',
'linkstoimage'              => 'Bu faylğa bağlantı olğan $1 saife:',
'nolinkstoimage'            => 'Bu faylğa bağlanğan saife yoq.',
'sharedupload'              => 'Bu fayl $1 saytına yüklengen ve diger proyektlerde de qullanılıp оla.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki'          => 'Tafsilâtnı $1 saifesinde tapmaq mümkün.',
'shareduploadwiki-linktext' => 'fayl malümat saifesi',
'noimage'                   => 'Bu isimde fayl yoq, amma siz $1.',
'noimage-linktext'          => 'оnı yüklep olasıñız',
'uploadnewversion-linktext' => 'Faylnıñ yañısını yükleñiz',

# File reversion
'filerevert'                => 'Eski versiyağa keri qayt $1',
'filerevert-legend'         => 'Eski versiyağa keri qayt',
'filerevert-comment'        => 'İzaat:',
'filerevert-defaultcomment' => '$2 tarihında $1 qullanıcısı tarafından yapılğan versiyağa keri qayt',

# MIME search
'mimesearch' => 'MIME qıdıruvı',
'mimetype'   => 'MIME çeşiti:',
'download'   => 'yükle',

# Unwatched pages
'unwatchedpages' => 'Közetilmegen saifeler',

# List redirects
'listredirects' => 'Yollamalarnı cedvelge çek',

# Unused templates
'unusedtemplates'     => 'Qullanılmağan şablonlar',
'unusedtemplatestext' => 'Bu saife {{ns:template}} isim fezasında bulunğan ve diger saifelerge kirsetilmegen şablonlarnı köstere. Şablonlarğa olğan diger bağlantılarnı da teşkermeden yoq etmeñiz.',
'unusedtemplateswlh'  => 'diger bağlantılar',

# Random page
'randompage'         => 'Tesadüfiy saife',
'randompage-nopages' => '"$1" isim fezasında iç bir saife yoq.',

# Random redirect
'randomredirect'         => 'Tesadüfiy yollama saifesi',
'randomredirect-nopages' => '"$1" isim fezasında iç bir yollama saifesi yoq.',

# Statistics
'statistics'              => 'Statistika',
'statistics-header-users' => 'Qullanıcı statistikası',
'statistics-mostpopular'  => 'Eñ sıq baqılğan saifeler',

'disambiguations'      => 'Çoq manalı terminler saifeleri',
'disambiguationspage'  => '{{ns:template}}:disambig',
'disambiguations-text' => "Aşağıdıki saifeler '''çoq manalı saifeler'''ge bağlantı ola.
Belki de olar bir konkret saifege bağlantı olmalı.<br />
Eger saifede, [[MediaWiki:Disambiguationspage]] saifesinde adı keçken şablon yerleştirilgen olsa, o saife çoq manalıdır.",

'doubleredirects'     => 'Yollamağa olğan yollamalar',
'doubleredirectstext' => 'Er satırda, ekinci yollama metniniñ ilk satırınıñ (umumen ekinci yollamanıñ da işaret etmek kerek olğanı "asıl" maqsatnıñ) yanında ilk ve ekinci yollamağa bağlantılar bar.',

'brokenredirects'        => 'Bar olmağan saifege yapılğan yollamalar',
'brokenredirectstext'    => 'Aşağıdaki yollamalar bar olmağan saifelerge bağlantı bereler:',
'brokenredirects-edit'   => '(deñiştir)',
'brokenredirects-delete' => '(yoq et)',

'withoutinterwiki'         => 'Diger tillerdeki versiyalarğa bağlantıları olmağan saifeler',
'withoutinterwiki-summary' => 'Bu saifelerde diger tillerdeki versiyalarğa bağlantılar yoq:',

'fewestrevisions' => 'Eñ az deñiştirme yapılğan saifeler',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|1 bayt|$1 bayt}}',
'ncategories'             => '{{PLURAL:$1|1 kategoriya|$1 kategoriya}}',
'nlinks'                  => '{{PLURAL:$1|1 bağlantı|$1 bağlantı}}',
'nmembers'                => '{{PLURAL:$1|1 aza|$1 aza}}',
'nrevisions'              => '{{PLURAL:$1|1 versiya|$1 versiya}}',
'nviews'                  => '{{PLURAL:$1|1 körünüv|$1 körünüv}}',
'lonelypages'             => 'Özüne iç bağlantı olmağan saifeler',
'lonelypagestext'         => 'İlerideki saifelerge {{SITENAME}} saytınıñ diger saifelerinden bağlantı yoqtır.',
'uncategorizedpages'      => 'Er angi bir kategoriyada olmağan saifeler',
'uncategorizedcategories' => 'Er angi bir kategoriyada olmağan kategoriyalar',
'uncategorizedimages'     => 'Er angi bir kategoriyada olmağan resimler',
'uncategorizedtemplates'  => 'Er angi bir kategoriyada olmağan şablonlar',
'unusedcategories'        => 'Qullanılmağan kategoriyalar',
'unusedimages'            => 'Qullanılmağan resimler',
'popularpages'            => 'Populâr saifeler',
'wantedcategories'        => 'İstenilgen kategoriyalar',
'wantedpages'             => 'İstenilgen saifeler',
'wantedfiles'             => 'İstenilgen fayllar',
'wantedtemplates'         => 'İstenilgen şablоnlar',
'mostlinked'              => 'Özüne eñ ziyade bağlantı berilgen saifeler',
'mostlinkedcategories'    => 'Eñ çoq saifege saip kategoriyalar',
'mostlinkedtemplates'     => 'Özüne eñ ziyade bağlantı berilgen şablonlar',
'mostcategories'          => 'Eñ ziyade kategoriyağa bağlanğan saifeler',
'mostimages'              => 'Eñ çoq qullanılğan resimler',
'mostrevisions'           => 'Eñ çoq deñişiklikke oğrağan saifeler',
'prefixindex'             => 'Prefiksnen bütün saifeler',
'shortpages'              => 'Qısqa saifeler',
'longpages'               => 'Uzun saifeler',
'deadendpages'            => 'Başqa saifelerge bağlantısı olmağan saifeler',
'deadendpagestext'        => 'Bu {{SITENAME}} başqa saifelerine bağlantısı olmağan saifelerdir.',
'protectedpages'          => 'Qorçalanğan saifeler',
'protectedpagestext'      => 'Bu saifelerniñ deñiştirüvge qarşı qorçalavı bar',
'protectedtitles'         => 'Yasaqlanğan serlevalar',
'listusers'               => 'Qullanıcılar cedveli',
'newpages'                => 'Yañı saifeler',
'newpages-username'       => 'Qullanıcı adı:',
'ancientpages'            => 'Eñ eski saifeler',
'move'                    => 'Adını deñiştir',
'movethispage'            => 'Saifeniñ adını deñiştir',
'pager-newer-n'           => '{{PLURAL:$1|1 daa yañıca|$1 daa yañıca}}',
'pager-older-n'           => '{{PLURAL:$1|1 daa eskice|$1 daa eskice}}',

# Book sources
'booksources'               => 'Kitaplar menbası',
'booksources-search-legend' => 'Kitaplar menbasını qıdıruv',
'booksources-go'            => 'Qıdır',

# Special:Log
'specialloguserlabel'  => 'Qullanıcı:',
'speciallogtitlelabel' => 'Serleva:',
'log'                  => 'Jurnallar',
'all-logs-page'        => 'Bütün jurnallar',
'logempty'             => 'Jurnalda bir kelgen malümat yoq.',
'log-title-wildcard'   => 'Bu işaretlerden başlanğan serlevalarnı qıdır',

# Special:AllPages
'allpages'          => 'Bütün saifeler',
'alphaindexline'    => '$1 saifesinden $2 saifesinece',
'nextpage'          => 'Soñraki saife ($1)',
'prevpage'          => 'Evelki saife ($1)',
'allpagesfrom'      => 'Cedvelge çekmege başlanılacaq arifler:',
'allarticles'       => 'Bütün saifeler',
'allinnamespace'    => 'Bütün saifeler ($1 saifeleri)',
'allnotinnamespace' => 'Bütün saifeler ($1 isim fezasında olmağanlar)',
'allpagesprev'      => 'Evelki',
'allpagesnext'      => 'Soñraki',
'allpagessubmit'    => 'Köster',
'allpagesprefix'    => 'Yazğan ariflernen başlağan saifelerni köster:',
'allpagesbadtitle'  => 'Saifeniñ adı ruhsetsizdir. Serlevada tiller arası prefiksi ya da vikiler arası bağlantı ya da başqa qullanıluvı yasaq olğan işaretler bar.',
'allpages-bad-ns'   => '{{SITENAME}} saytında "$1" isim fezası yoqtır.',

# Special:Categories
'categories'                    => 'Saife kategoriyaları',
'categoriespagetext'            => 'Aşağıdaki kategoriyalarda saifeler ya da media-fayllar bar.
Mında [[Special:UnusedCategories|qullanılmağan kategoriyalar]] kösterilmegen.
[[Special:WantedCategories|Talap etilgen kategoriyalarnıñ cedvelini]] de baqıñız.',
'special-categories-sort-count' => 'sayılarına köre sırala',
'special-categories-sort-abc'   => 'elifbe sırasınen sırala',

# Special:ListUsers
'listusers-submit'   => 'Köster',
'listusers-noresult' => 'İç bir qullanıcı tapılmadı.',

# Special:Log/newusers
'newuserlogpage'              => 'Yañı qullanıcı jurnalı',
'newuserlogpagetext'          => 'Eñ sоñki qayd оlğan qullanıcı jurnalı.',
'newuserlog-create-entry'     => 'Yañı qullanıcı',
'newuserlog-create2-entry'    => 'yañı esap yarattı $1',
'newuserlog-autocreate-entry' => 'Esap avtomatik olaraq yaratıldı',

# E-mail user
'mailnologin'     => 'Mektüp yollanacaq adresi yoqtır',
'mailnologintext' => 'Diger qullanıcılarğa elektron mektüpler yollap olmaq içün [[Special:UserLogin|oturım açmalısıñız]] ve [[Special:Preferences|sazlamalarıñızda]] mevcut olğan e-mail adresiniñ saibi olmalısıñız.',
'emailuser'       => 'Qullanıcığa mektüp',
'emailpage'       => 'Qullanıcığa elektron mektüp yolla',
'emailpagetext'   => 'Aşağıdaki formanı toldurıp bu qullanıcığa mektüp yollap olursıñız.
[[Special:Preferences|Öz sazlamalarıñızda]] yazğan elektron adresiñiz mektüpniñ "Kimden" satırında yazılacaq, bunıñ içün mektüp alıcı doğrudan-doğru siziñ adresiñizge cevap yollap olur.',
'usermailererror' => 'E-mail beyanatı yollanğan vaqıtta hata olıp çıqtı',
'defemailsubject' => '{{SITENAME}} e-mail',
'noemailtitle'    => 'E-mail adresi yoqtır',
'noemailtext'     => 'Bu qullanıcı uyğun elektron poçta adresini bildirmegen.',
'emailfrom'       => 'Kimden:',
'emailto'         => 'Kimge:',
'emailsubject'    => 'Mektüp mevzusı:',
'emailmessage'    => 'Mektüp:',
'emailsend'       => 'Yolla',
'emailccme'       => 'Mektübimniñ bir kopiyasını maña da yolla.',
'emailccsubject'  => '$1 qullanıcısına yollanğan mektübiñizniñ kopiyası: $2',
'emailsent'       => 'Mektüp yollandı',
'emailsenttext'   => 'Siziñ e-mail beyanatıñız yollandı',

# Watchlist
'watchlist'            => 'Közetüv cedveli',
'mywatchlist'          => 'Közetüv cedvelim',
'watchlistfor'         => "('''$1''' içün)",
'nowatchlist'          => 'Siziñ közetüv cedveliñiz boştır.',
'watchlistanontext'    => 'Közetüv cedvelini baqmaq ya da deñiştirmek içün $1 borclusıñız.',
'watchnologin'         => 'Oturım açmaq kerek',
'watchnologintext'     => 'Öz közetüv cedveliñizni deñiştirmek içün [[Special:UserLogin|oturım açıñız]]',
'addedwatch'           => 'Közetüv cedveline kirsetmek',
'addedwatchtext'       => '"[[:$1]]" saifesi [[Special:Watchlist|kozetüv cevdeliñizge]] kirsetildi. Bu saifedeki ve onıñnen bağlı saifelerdeki olacaq deñişiklikler bu cedvelde belgilenecek, em de olar közge çarpması içün [[Special:RecentChanges|yañı deñişiklik cedveli]] bulunğan saifede qalın olaraq kösterilir.
Birazdan soñ közetüv cedveliñizden bir de bir saifeni yoq etmege isteseñiz de, saifeniñ yuqarısındaki sol tarafta "közetme" dögmesine basıñız.',
'removedwatch'         => 'Közetüv cedvelinden yoq et',
'removedwatchtext'     => '"[[:$1]]" saifesi közetüv cedveliñizden yoq etildi.',
'watch'                => 'Közet',
'watchthispage'        => 'Bu saifeni közet',
'unwatch'              => 'Közetme',
'unwatchthispage'      => 'Bu saifeni közetme',
'notanarticle'         => 'Malümat saifesi degil',
'watchnochange'        => 'Kösterilgen zaman aralığında közetüv cedveliñizdeki saifelerniñ iç biri deñiştirilmegen.',
'watchlist-details'    => 'Muzakere saifelerini esapqa almayıp, közetüv cedveliñizde {{PLURAL:$1|1|$1}} saife bar.',
'wlheader-enotif'      => '* E-mail ile haber berüv açıldı.',
'wlheader-showupdated' => "* Soñki ziyaretiñizden soñraki saife deñişiklikleri '''qalın''' olaraq kösterildi.",
'watchmethod-recent'   => 'soñki deñişiklikler arasında közetken saifeleriñiz qıdırıla',
'watchmethod-list'     => 'közetüv cedvelindeki saifeler teşkerile',
'watchlistcontains'    => 'Siziñ közetüv cedveliñizde {{PLURAL:$1|1|$1}} saife bar.',
'iteminvalidname'      => '"$1" saifesi munasebetinen problema olıp çıqtı, elverişli olmağan isimdir…',
'wlnote'               => "Aşağıda soñki {{PLURAL:$2|saat|'''$2''' saat}} içinde yapılğan soñki {{PLURAL:$1|deñişiklik|'''$1''' deñişiklik}} kösterile.",
'wlshowlast'           => 'Soñki $2 kün $1 saat içün $3 köster',
'watchlist-options'    => 'Közetüv cedveli sazlamaları',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Közetüv cedveline kirsetilmekte...',
'unwatching' => 'Közetüv cedvelinden yoq etilmekte...',

'enotif_mailer'                => '{{SITENAME}} poçta vastasınen haber bergen hızmet',
'enotif_reset'                 => 'Cümle saifelerni baqılğan olaraq işaretle',
'enotif_newpagetext'           => 'Bu yañı bir saifedir.',
'enotif_impersonal_salutation' => '{{SITENAME}} qullanıcısı',
'changed'                      => 'deñiştirildi',
'created'                      => 'yaratıldı',
'enotif_subject'               => '"{{SITENAME}}" $PAGETITLE saifesi $PAGEEDITOR qullanıcı tarafından $CHANGEDORCREATED',
'enotif_lastvisited'           => 'Soñki ziyaretiñizden berli yapılğan deñişikliklerni bilmek içün $1 baqıñız.',
'enotif_anon_editor'           => 'adsız (anonim) qullanıcı $1',
'enotif_body'                  => 'Sayğılı $WATCHINGUSERNAME,


{{SITENAME}} saytındaki $PAGETITLE serlevalı saife $PAGEEDITDATE tarihında $PAGEEDITOR tarafından $CHANGEDORCREATED. Şimdiki versiyağa $PAGETITLE_URL adresinden yetişip olasıñız.

$NEWPAGE

Açıqlaması: $PAGESUMMARY $PAGEMINOREDIT

Saifeni deñiştirgen qullanıcınıñ irişim malümatı:
e-mail: $PAGEEDITOR_EMAIL
Viki: $PAGEEDITOR_WIKI

Bahsı keçken saifeni siz ziyaret etmegen müddet içinde saifenen bağlı başqa deñişiklik tenbisi yollanmaycaq. Tenbi sazlamalarını közetüv cedveliñizdeki bütün saifeler içün deñiştirip olursıñız.

{{SITENAME}} tenbi sisteması.

--
Sazlamalarnı deñiştirmek içün:
{{fullurl:Special:Watchlist/edit}}

Yardım ve teklifler içün:
{{fullurl:Help:Contents}}',

# Delete
'deletepage'            => 'Saifeni yoq et',
'confirm'               => 'Tasdıqla',
'excontent'             => "eski metin: '$1'",
'excontentauthor'       => "eski metin: '$1' ('$2' isse qoşqan tek bir qullanıcı)",
'exbeforeblank'         => "Yoq etilmegen evelki metin: '$1'",
'exblank'               => 'saife metini boş',
'historywarning'        => 'Tenbi: Siz yoq etmek üzre olğan saifeniñ keçmişi bardır:',
'confirmdeletetext'     => 'Bir saifeni ya da resimni bütün keçmişi ile birlikte malümat bazasından qalıcı olaraq yoq etmek üzresiñiz.
Lütfen, neticelerini añlağanıñıznı ve [[{{MediaWiki:Policy-url}}|yoq etüv politikasına]] uyğunlığını diqqatqa alıp, bunı yapmağa istegeniñizni tasdıqlañız.',
'actioncomplete'        => 'İşlem tamamlandı.',
'deletedtext'           => '"<nowiki>$1</nowiki>" yoq etildi.
yaqın zamanda yoq etilgenlerni körmek içün: $2.',
'deletedarticle'        => '"[[$1]]" yoq etildi',
'dellogpage'            => 'Yoq etüv jurnalı',
'dellogpagetext'        => 'Aşağıdaki cedvel soñki yoq etüv jurnalıdır.',
'deletionlog'           => 'yoq etüv jurnalı',
'reverted'              => 'Evelki versiya keri ketirildi',
'deletecomment'         => 'Yoq etüv sebebi',
'deleteotherreason'     => 'Diger/ilâveli sebep:',
'deletereasonotherlist' => 'Diger sebep',

# Rollback
'rollback'       => 'deñişikliklerni keri al',
'rollback_short' => 'keri al',
'rollbacklink'   => 'eski alına ketir',
'rollbackfailed' => 'keri aluv işlemi muvafaqiyetsiz',
'cantrollback'   => 'Deñişiklikler keri alınamay, soñki deñiştirgen kişi saifeniñ tek bir müellifidir',
'editcomment'    => "Deñiştirme izaatı: \"''\$1''\" edi.", # only shown if there is an edit comment
'revertpage'     => '[[Special:Contributions/$2|$2]] ([[User talk:$2|muzakere]]) tarafından yapılğan deñişiklikler keri alınıp, [[User:$1|$1]] tarafından deñiştirilgen evelki versiya keri ketirildi.', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from

# Protect
'protectlogpage'              => 'Qorçalav jurnalı',
'protectlogtext'              => 'Qorçalavğa aluv/çıqaruv ile bağlı deñişiklikler jurnalını körmektesiñiz.
Qorçalav altına alınğan saifeler tam cedveli [[Special:ProtectedPages|bu saifede]] körip olasıñız.',
'protectedarticle'            => '"[[$1]]" qorçalav altına alındı',
'unprotectedarticle'          => 'qorçalav çıqarlıdı: "[[$1]]"',
'prot_1movedto2'              => '"[[$1]]" saifesiniñ adı "[[$2]]" olaraq deñiştirildi',
'protect-legend'              => 'Qorçalavnı tasdıqla',
'protectcomment'              => 'Qorçalav altına aluv sebebi',
'protectexpiry'               => 'Bitiş tarihı:',
'protect_expiry_invalid'      => 'Bitiş tarihı yañlış.',
'protect_expiry_old'          => 'Bitiş zamanı keçmiştedir.',
'protect-unchain'             => 'Saife adı deñiştirüv kilitini çıqar',
'protect-text'                => "'''[[<nowiki>$1</nowiki>]]''' saifesiniñ qorçalav seviyesini mından körip olur ve deñiştirip olasıñız.",
'protect-locked-access'       => "Qullanıcı esabıñız saifeniñ qorçalav seviyelerini deñiştirme yetkisine saip degil. '''$1''' saifesiniñ şimdiki sazlamaları şularıdır:",
'protect-cascadeon'           => 'Bu saife şimdi qorçalav altındadır, çünki aşağıda cedvellengen ve kaskadlı qorçalav altındaki $1 saifede qullanıla.
Bu saifeniñ qorçalav seviyesini deñiştirip olasıñız, amma kaskadlı qorçalav tesir etilmeycek.',
'protect-default'             => 'Bütün qullanıcılarğa ruhset ber',
'protect-fallback'            => '"$1" izni kerektir',
'protect-level-autoconfirmed' => 'Qaydsız ve yañı qullanıcılarnı blоk et',
'protect-level-sysop'         => 'tek administratorlar',
'protect-summary-cascade'     => 'kaskadlı',
'protect-expiring'            => 'bite: $1 (UTC)',
'protect-cascade'             => 'Bu saifede qullanılğan bütün saifelerni qorçalavğa al (kaskadlı qorçalav)',
'protect-cantedit'            => 'Bu saifeniñ qorçalav seviyesini deñiştirip olamazsıñız, çünki bunı yapmağa yetkiñiz yoq.',
'protect-expiry-options'      => '2 saat:2 hours,1 kün:1 day,3 kün:3 days,1 afta:1 week,2 afta:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 yıl:1 year,müddetsiz:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Ruhseti:',
'restriction-level'           => 'Ruhset seviyesi:',
'minimum-size'                => 'Asğariy (minimal) büyüklik',
'maximum-size'                => 'Azamiy (maksimal) büyüklik:',
'pagesize'                    => '(bayt)',

# Restrictions (nouns)
'restriction-edit' => 'Deñiştir',
'restriction-move' => 'Adını deñiştir',

# Restriction levels
'restriction-level-sysop'         => 'qorçalav altında',
'restriction-level-autoconfirmed' => 'qısmen qorçalav altında',

# Undelete
'undelete'           => 'Yoq etilgen saifelerni köster',
'undeletepage'       => 'Saifeniñ yoq etilgen versiyalarına köz at ve keri ketir.',
'viewdeletedpage'    => 'Yoq etilgen saifelerge baq',
'undeletebtn'        => 'Keri ketir!',
'undeletereset'      => 'Vazgeç',
'undeletecomment'    => 'İzaat:',
'undeletedarticle'   => '"[[$1]]" keri ketirildi.',
'undeletedrevisions' => 'Toplam {{PLURAL:$1|1 qayd|$1 qayd}} keri ketirildi.',
'undelete-header'    => 'Keçenlerde yоq etilgen saifelerni körmek içün [[Special:Log/delete|yоq etüv jurnalına]] baqıñız.',

# Namespace form on various pages
'namespace'      => 'İsim fezası:',
'invert'         => 'Saylanğan tışındakilerni sayla',
'blanknamespace' => '(Esas)',

# Contributions
'contributions'       => 'Qullanıcınıñ isseleri',
'contributions-title' => '$1 qullanıcısınıñ isseleri',
'mycontris'           => 'isselerim',
'contribsub2'         => '$1 ($2)',
'nocontribs'          => 'Bu kriteriylerge uyğan deñişiklik tapılamadı', # Optional parameter: $1 is the user name
'uctop'               => '(soñki)',
'month'               => 'Bu ay (ve ondan erte):',
'year'                => 'Bu sene (ve ondan erte):',

'sp-contributions-newbies'     => 'Tek yañı qullanıcılarnıñ isselerini köster',
'sp-contributions-newbies-sub' => 'Yañı qullanıcılar içün',
'sp-contributions-blocklog'    => 'Blok etüv jurnalı',
'sp-contributions-search'      => 'İsselerni qıdıruv',
'sp-contributions-username'    => 'IP adresi ya da qullanıcı adı:',
'sp-contributions-submit'      => 'Qıdır',

# What links here
'whatlinkshere'       => 'Saifege bağlantılar',
'whatlinkshere-title' => '$1 saifesine bağlantı olğan saifeler',
'whatlinkshere-page'  => 'Saife:',
'linkshere'           => "Bu saifeler '''[[:$1]]''' saifesine bağlantısı olğan:",
'nolinkshere'         => "'''[[:$1]]''' saifesine bağlanğan saife yoq.",
'nolinkshere-ns'      => "Saylanğan isim fezasında '''[[:$1]]''' saifesine bağlanğan saife yoqtır.",
'isredirect'          => 'Yollama saifesi',
'istemplate'          => 'kirsetilme',
'whatlinkshere-prev'  => '{{PLURAL:$1|evelki|evelki $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|soñraki|soñraki $1}}',
'whatlinkshere-links' => '← bağlantılar',

# Block/unblock
'blockip'                 => 'Bu IP adresinden irişimni ban et',
'blockiptext'             => 'Aşağıdaki formanı qullanıp belli bir IP adresiniñ ya da qullanıcınıñ irişimini ban etip olasıñız. Bu tek vandalizmni ban etmek içün ve [[{{MediaWiki:Policy-url}}|qaidelerge]] uyğun olaraq yapılmalı. Aşağığa mıtlaqa ban etüv ile bağlı bir açıqlama yazıñız. (meselâ: Şu saifelerde vandalizm yaptı).',
'ipaddress'               => 'IP adresi',
'ipadressorusername'      => 'IP adresi ya da qullanıcı adı',
'ipbexpiry'               => 'Bitiş müddeti',
'ipbreason'               => 'Sebep',
'ipbsubmit'               => 'Bu qullanıcını ban et',
'ipbother'                => 'Farqlı zaman',
'ipboptions'              => '2 saat:2 hours,1 kün:1 day,3 kün:3 days,1 afta:1 week,2 afta:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 yıl:1 year,müddetsiz:infinite', # display1:time1,display2:time2,...
'ipbotheroption'          => 'farqlı',
'ipbotherreason'          => 'Diger/ilâveli sebep:',
'badipaddress'            => 'Yañlış IP adresi',
'blockipsuccesssub'       => 'IP adresni ban etüv işlevi muvafaqiyetli oldı',
'blockipsuccesstext'      => '"$1" ban etildi.
<br />[[Special:IPBlockList|IP adresi ban etilgenler]] cedveline baqıñız .',
'unblockip'               => 'Qullanıcınıñ ban etüvini çıqar',
'ipusubmit'               => 'Bu blok etmeni çıqar',
'ipblocklist'             => 'Blok etilgen qullanıcılar ve IP adresleri',
'blocklistline'           => '$1, $2 blok etti: $3 ($4)',
'infiniteblock'           => 'müddetsiz',
'expiringblock'           => '$1 tarihında bitecek',
'blocklink'               => 'ban et',
'unblocklink'             => 'ban etüvni çıqar',
'contribslink'            => 'İsseler',
'autoblocker'             => 'Avtomatik olaraq ban ettiñiz çünki yaqın zamanda IP adresiñiz "[[User:$1|$1]]" qullanıcısı tarafından qullanıldı. $1 adlı qullanıcınıñ ban etilüvi içün berilgen sebep: "\'\'\'$2\'\'\'"',
'blocklogpage'            => 'İrişim ban etüv jurnalı',
'blocklogentry'           => '"[[$1]]" irişimi $2 $3 toqtatıldı. Sebep',
'blocklogtext'            => 'Mında qullanıcı irişimine yönelik ban etüv ve ban çıqaruv jurnalı cedvellene. Avtomatik IP adresi ban etüvleri cedvelge kirsetilmedi. Al-azırda irişimi toqtatılğan qullanıcılarnı [[Special:IPBlockList|IP ban etüv cedveli]] saifesinden körip olasıñız.',
'unblocklogentry'         => '$1 qullanıcınıñ ban etüvi çıqarıldı',
'block-log-flags-noemail' => 'e-mail blok etildi',
'ipb_expiry_invalid'      => 'Yañlış bitiş zamanı.',
'ipb_already_blocked'     => '"$1" endi blok etildi',
'ip_range_invalid'        => 'Ruhsetsiz IP aralığı.',

# Developer tools
'lockdb'  => 'Malümat bazası kilitli',
'lockbtn' => 'Malümat bazası kilitli',

# Move page
'move-page-legend'        => 'Ad deñişikligi',
'movepagetext'            => "Aşağıdaki formanı qullanıp saifeniñ adını deñiştirilir. Bunıñnen beraber deñişiklik jurnalı da yañı adğa avuştırılır.
Eski ad yañı adğa yollama olur. Eski serlevağa yollama saifelerni avtomatik olaraq yañartıp olasıñız. Bu işlemi avtomatik yapmağa istemeseñiz, bütün [[Special:DoubleRedirects|çift]] ve [[Special:BrokenRedirects|yırtıq]] yollama saifelerini özüñiz tüzetmege mecbur olursıñız. Bağlantılar endiden berli doğru çalışmasından emin olmalısıñız.

Yañı adda bir ad endi bar olsa, ad deñişikligi '''yapılmaycaq''', ancaq mevcut olğan saife yollama ya da boş olsa ad deñişikligi mümkün olacaq. Bu demek ki, saife adını yañlıştan deñiştirgen olsañız deminki adını keri qaytarıp olasıñız, amma mevcut olğan saifeni tesadüfen yoq etalmaysıñız.

'''TENBİ!'''
Ad deñiştirüv populâr saifeler içün büyük deñişmelerge sebep ola bilir. Lütfen, deñişiklikni yapmadan evel ola bileceklerni köz ögüne alıñız.",
'movepagetalktext'        => "Qoşulğan muzakere saifesiniñ de (bar olsa) adı avtomatik tarzda deñiştirilecek. '''Müstesnalar:'''

*Aynı bu isimde boş olmağan bir muzakere saifesi endi bar;
*Aşağıdaki boşluqqa işaret qoymadıñız.

Böyle allarda, kerek olsa, saifelerni qolnen taşımağa ya da birleştirmege mecbur olursıñız.",
'movearticle'             => 'Eski ad',
'movenologin'             => 'Oturım açmadıñız',
'movenologintext'         => 'Saifeniñ adını deñiştirip olmaq içün [[Special:UserLogin|oturım açıñız]].',
'movenotallowed'          => 'Saifeler adlarını deñiştirmege iziniñiz yoq.',
'newtitle'                => 'Yañı ad',
'move-watch'              => 'Bu saifeni közet',
'movepagebtn'             => 'Adını deñiştir',
'pagemovedsub'            => 'Ad deñişikligi tamamlandı',
'movepage-moved'          => '<big>\'\'\'"$1" saifesiniñ adı "$2" olaraq deñiştirildi\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Bu adda bir saife endi bar ya da siz yazğan ad yasaqlı.
Lütfen, başqa bir ad saylap yazıñız.',
'cantmove-titleprotected' => 'Siz yazğan yañı ad yasaqlıdır, bunıñ içün saife adını deñiştirmekniñ çaresi yoq.',
'talkexists'              => "'''Saifeniñ adı deñiştirildi, amma muzakere saifesiniñ adını deñiştirmege mümkünlik yoqtır, çünki aynı bu adda bir saife endi bar. Lütfen, bularnı qolnen birleştiriñiz.'''",
'movedto'                 => 'adı deñiştirildi:',
'movetalk'                => 'Muzakere saifesiniñ adını deñiştir.',
'1movedto2'               => '"[[$1]]" saifesiniñ adı "[[$2]]" olaraq deñiştirildi',
'1movedto2_redir'         => '[[$1]] serlevası [[$2]] saifesine yollandı',
'movelogpage'             => 'Ad deñişikligi jurnalı',
'movelogpagetext'         => 'Aşağıda bulunğan cedvel adı deñiştirilgen saifelerni köstere',
'movereason'              => 'Sebep',
'revertmove'              => 'Kerige al',
'delete_and_move'         => 'Yoq et ve adını deñiştir',
'delete_and_move_text'    => '== Yoq etmek lâzimdir ==

"[[:$1]]" saifesi endi bar. Adını deñiştirip olmaq içün onı yoq etmege isteysiñizmi?',
'delete_and_move_confirm' => 'Ebet, bu saifeni yoq et',
'delete_and_move_reason'  => 'İsim deñiştirip olmaq içün yoq etildi',
'selfmove'                => 'Bu saifeniñ adını deñiştirmege imkân yoqtır, çünki asıl ile yañı adları bir kele.',

# Export
'export' => 'Saifelerni eksport et',

# Namespace 8 related
'allmessages'         => 'Sistema beyanatları',
'allmessagesname'     => 'İsim',
'allmessagesdefault'  => 'Original metin',
'allmessagescurrent'  => 'Şimdi qullanılğan metin',
'allmessagestext'     => 'İşbu cedvel MediaWikide mevcut olğan bütün sistema beyanatlarınıñ cedvelidir.
MediaWiki interfeysiniñ çeşit tillerge tercime etüvde iştirak etmege isteseñiz [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] ve [http://translatewiki.net translatewiki.net] saifelerine ziyaret etiñiz.',
'allmessagesfilter'   => 'Metin ayrıştırıcı filtrı:',
'allmessagesmodified' => 'Tek deñiştirilgenlerni köster',

# Thumbnails
'thumbnail-more'           => 'Büyüt',
'filemissing'              => 'Fayl tapılmadı',
'thumbnail_error'          => 'Kiçik resim (thumbnail) yaratılğanda bir hata çıqtı: $1',
'thumbnail_invalid_params' => 'Yañlış thumbnail parametri',
'thumbnail_dest_directory' => 'İstenilgen direktoriyanı yaratmaqnıñ iç çaresi yoq',

# Special:Import
'import-comment' => 'İzaat:',

# Import log
'importlogpage' => 'İmport jurnalı',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Siziñ qullanıcı saifeñiz',
'tooltip-pt-anonuserpage'         => 'IP adresim içün qullanıcı saifesi',
'tooltip-pt-mytalk'               => 'Siziñ muzakere saifeñiz',
'tooltip-pt-anontalk'             => 'Bu IP adresinden yapılğan deñişikliklerni muzakere et',
'tooltip-pt-preferences'          => 'Sazlamalarım (nastroykalarım)',
'tooltip-pt-watchlist'            => 'Men közetüvge alğan saifeler',
'tooltip-pt-mycontris'            => 'Qoşqan isseleriñizniñ cedveli',
'tooltip-pt-login'                => 'Oturım açmañız tevsiye olunır amma mecbur degilsiñiz.',
'tooltip-pt-anonlogin'            => 'Oturım açmañız tevsiye olunır amma mecbur degilsiñiz.',
'tooltip-pt-logout'               => 'Oturımnı qapat',
'tooltip-ca-talk'                 => 'Saifedeki malümat ile bağlı zan belgile',
'tooltip-ca-edit'                 => 'Bu saifeni deñiştirip olasıñız. Saqlamazdan evel baqıp çıqmağa unutmañız.',
'tooltip-ca-addsection'           => 'Yañı bölük aç',
'tooltip-ca-viewsource'           => 'Bu saife qorçalav altında. Menba kodunı tek körip olasıñız, deñiştirip olamaysıñız.',
'tooltip-ca-history'              => 'Bu saifeniñ keçmiş versiyaları.',
'tooltip-ca-protect'              => 'Bu saifeni qorçala',
'tooltip-ca-delete'               => 'Saifeni yoq et',
'tooltip-ca-undelete'             => 'Saifeni yoq etilmezden evelki alına keri ketiriñiz',
'tooltip-ca-move'                 => 'Saifeniñ adını deñiştir',
'tooltip-ca-watch'                => 'Bu saifeni közetüvge al',
'tooltip-ca-unwatch'              => 'Bu saifeni közetmekni taşla',
'tooltip-search'                  => '{{SITENAME}} saytında qıdıruv yap',
'tooltip-search-go'               => 'Bu adda saife mevcut olsa, oña bar',
'tooltip-search-fulltext'         => 'Bu metini olğan saifeler qıdır',
'tooltip-p-logo'                  => 'Baş saife',
'tooltip-n-mainpage'              => 'Başlanğıç saifesine qaytıñız',
'tooltip-n-portal'                => 'Proyekt üzerine, ne qaydadır, neni yapıp olasıñız',
'tooltip-n-currentevents'         => 'Ağımdaki vaqialarnen bağlı soñki malümatlar',
'tooltip-n-recentchanges'         => 'Vikide yapılğan soñki deñişikliklerniñ cedveli.',
'tooltip-n-randompage'            => 'Tesadüfiy bir saifege barıñız',
'tooltip-n-help'                  => 'Yardım almaq içün.',
'tooltip-t-whatlinkshere'         => 'Bu saifege bağlantı bergen diger viki saifeleriniñ cedveli',
'tooltip-t-recentchangeslinked'   => 'Bu saifege bağlantı bergen saifelerdeki soñki deñişiklikler',
'tooltip-feed-rss'                => 'Bu saife içün RSS translâtsiyası',
'tooltip-feed-atom'               => 'Bu saife içün atom translâtsiyası',
'tooltip-t-contributions'         => 'Qullanıcınıñ isse cedvelini kör',
'tooltip-t-emailuser'             => 'Qullanıcığa e-mail mektübini yolla',
'tooltip-t-upload'                => 'Sistemağa resim ya da media fayllarnı yükleñiz',
'tooltip-t-specialpages'          => 'Bütün mahsus saifelerniñ cedvelini köster',
'tooltip-t-print'                 => 'Bu saifeniñ basılmağa uyğun körünişi',
'tooltip-t-permalink'             => 'Bu saifeniñ versiyasına daimiy bağlantı',
'tooltip-ca-nstab-main'           => 'Saifeni köster',
'tooltip-ca-nstab-user'           => 'Qullanıcı saifesini köster',
'tooltip-ca-nstab-media'          => 'Media saifesini köster',
'tooltip-ca-nstab-special'        => 'Bu mahsus saife olğanı içün deñişiklik yapamazsıñız.',
'tooltip-ca-nstab-project'        => 'Proyekt saifesini köster',
'tooltip-ca-nstab-image'          => 'Resim saifesini köster',
'tooltip-ca-nstab-mediawiki'      => 'Sistema beyanatını köster',
'tooltip-ca-nstab-template'       => 'Şablonnı köster',
'tooltip-ca-nstab-help'           => 'Yardım saifesini körmek içün basıñız',
'tooltip-ca-nstab-category'       => 'Kategoriya saifesini köster',
'tooltip-minoredit'               => 'Kiçik deñişiklik olaraq işaretle',
'tooltip-save'                    => 'Deñişikliklerni saqla',
'tooltip-preview'                 => 'Baqıp çıquv. Saqlamazdan evel bu hususiyetni qullanıp deñişiklikleriñizni baqıp çıqıñız!',
'tooltip-diff'                    => 'Metinge siz yapqan deñişikliklerni kösterir.',
'tooltip-compareselectedversions' => 'Saylanğan eki versiya arasındaki farqlarnı köster.',
'tooltip-watch'                   => 'Saifeni közetüv cedveline kirset',
'tooltip-recreate'                => 'Yoq etilgen olmasına baqmadan saifeni yañıdan yañart',
'tooltip-upload'                  => 'Yüklenip başla',

# Stylesheets
'monobook.css' => '/* monobook temasınıñ ayarlarını (nastroykalarını) deñiştirmek içün bu yerini deñiştiriñiz. Bütün saytta tesirli olur. */',

# Metadata
'nodublincore'      => 'Dublin Core RDF meta malümatı bu server içün yasaqlı.',
'nocreativecommons' => 'Creative Commons RDF meta malümatı bu server içün yasaqlı.',
'notacceptable'     => 'Viki-server brauzeriñiz oqup olacaq formatında malümat beralmay.',

# Attribution
'anonymous'        => '{{SITENAME}} saytınıñ $1 adsız (anonim) qullanıcıları',
'siteuser'         => '{{SITENAME}} qullanıcı $1',
'lastmodifiedatby' => 'Saife eñ soñki $3 tarafından $2, $1 tarihında deñiştirildi.', # $1 date, $2 time, $3 user
'othercontribs'    => '$1 menbasına binaen.',
'others'           => 'digerleri',
'siteusers'        => '{{SITENAME}} saytınıñ $2 qullanıcısı $1',
'creditspage'      => 'Teşekkürler',
'nocredits'        => 'Bu saife içün qullanıcılar cedveli yoq.',

# Spam protection
'spamprotectiontitle' => 'Spam qarşı qorçalav filtri',
'spamprotectiontext'  => 'Saqlamağa istegen saifeñiz spam filtri tarafından blok etildi. Büyük ihtimallı ki, saifede qara cedveldeki bir tış saytqa bağlantı bar.',
'spamprotectionmatch' => 'Spam-filtrden işbu beyanat keldi: $1',
'spambot_username'    => 'Spamdan temizlev',
'spam_reverting'      => '$1 saytına bağlantısı olmağan soñki versiyağa keri ketirüv',
'spam_blanking'       => 'Bar olğan versiyalarda $1 saytına bağlantılar bar, temizlev',

# Info page
'infosubtitle'   => 'Saife aqqında malümat',
'numedits'       => 'Deñişiklik sayısı (saife): $1',
'numtalkedits'   => 'Deñişiklik sayısı (muzakere saifesi): $1',
'numwatchers'    => 'Közetici sayısı: $1',
'numauthors'     => 'Müellif sayısı (saife): $1',
'numtalkauthors' => 'Müellif sayısı (muzakere saifesi): $1',

# Skin names
'skinname-standard'    => 'Standart',
'skinname-nostalgia'   => 'Nostalgiya',
'skinname-cologneblue' => 'Köln asretligi',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'Öz resimleme',
'skinname-chick'       => 'Çipçe',
'skinname-simple'      => 'Adiy',

# Math options
'mw_math_png'    => 'Daima PNG resim formatına çevir',
'mw_math_simple' => 'Pek basit olsa HTML, yoqsa PNG',
'mw_math_html'   => 'Mümkün olsa HTML, yoqsa PNG',
'mw_math_source' => 'Deñiştirmeden TeX olaraq taşla  (metin temelli brauzerler içün)',
'mw_math_modern' => 'Zemaneviy brauzerler içün tevsiye etilgen',
'mw_math_mathml' => 'Mümkün olsa MathML (daa deñeme alında)',

# Image deletion
'deletedrevision'                 => '$1 sayılı eski versiya yoq etildi.',
'filedeleteerror-short'           => 'Fayl yoq etkende hata çıqtı: $1',
'filedelete-missing'              => '"$1" adlı fayl yoq etilip olamay, çünki öyle bir fayl yoq.',
'filedelete-old-unregistered'     => 'Malümat bazasında saylanğan "$1" fayl versiyası yoq.',
'filedelete-current-unregistered' => 'Malümat bazasında saylanğan "$1" adlı fayl yoq.',

# Browsing diffs
'previousdiff' => '← Evelki deñişiklik',
'nextdiff'     => 'Soñraki deñişiklik →',

# Media information
'mediawarning'         => "'''DİQQAT!''': Bu faylda yaman maqsatlı (virus kibi) qısım bulunıp ola ve operatsion sistemañızğa zarar ketirip olur.
<hr />",
'imagemaxsize'         => 'Resimlerniñ malümat saifelerindeki resimniñ azamiy (maksimal) ölçüsi:',
'thumbsize'            => 'Kiçik ölçü:',
'widthheightpage'      => '$1 × $2, $3 saife',
'file-info'            => '(fayl büyükligi: $1, MIME çeşiti: $2)',
'file-info-size'       => '($1 × $2 piksel, fayl büyükligi: $3, MIME çeşiti: $4)',
'file-nohires'         => '<small>Daa yüksek çezinirlikke saip versiya yoq.</small>',
'svg-long-desc'        => '(SVG faylı, nominal $1 × $2 piksel, fayl büyükligi: $3)',
'show-big-image'       => 'Tam çezinirlik',
'show-big-image-thumb' => '<small>Baqıp çıquvda resim büyükligi: $1 × $2 piksel</small>',

# Special:NewFiles
'newimages'             => 'Yañı resimler',
'imagelisttext'         => "Aşağıdaki cedvelde $2 köre tizilgen {{PLURAL:$1|'''1''' fayldır|'''$1''' fayldır}}.",
'showhidebots'          => '(botlarnı $1)',
'noimages'              => 'Resim yoq.',
'ilsubmit'              => 'Qıdır',
'bydate'                => 'hronologik sıranen',
'sp-newimages-showfrom' => '$1, $2 tarihından başlap yañı fayllar köster',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2 × $3',
'seconds-abbrev' => 'san.',
'minutes-abbrev' => 'daq.',
'hours-abbrev'   => 'saat',

# Bad image list
'bad_image_list' => 'Format böyle olmalı:

Er satır * işaretinen başlamalı. Satırnıñ birinci bağlantısı qоşmağa yasaqlanğan faylğa bağlanmalı.
Şu satırda ilerideki bağlantılar istisna olurlar, yani şu saifelerde işbu fayl qullanmaq mümkün.',

# Metadata
'metadata'          => 'Resim detalleri',
'metadata-help'     => 'Faylda (adetince raqamlı kamera ve skanerlernen qоşulğan) ilâve malümatı bar. Eger bu fayl yaratılğandan soñ deñiştirilse edi, belki de bazı parametrler eskirdi.',
'metadata-expand'   => 'Tafsilâtnı köster',
'metadata-collapse' => 'Tafsilâtnı kösterme',
'metadata-fields'   => 'Bu cedveldeki EXIF meta malümatı resim saifesinde kösterilecek, başqaları ise gizlenecek.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength', # Do not translate list items

# EXIF tags
'exif-make'                => 'Kamera markası',
'exif-model'               => 'Kamera modeli',
'exif-artist'              => 'Yaratıcısı',
'exif-colorspace'          => 'Renk aralığı',
'exif-datetimeoriginal'    => 'Original saat ve tarih',
'exif-exposuretime'        => 'Ekspozitsiya müddeti',
'exif-exposuretime-format' => '$1 saniye ($2)',
'exif-fnumber'             => 'Diafragma nomerası',
'exif-spectralsensitivity' => 'Spektral duyğulılıq',
'exif-aperturevalue'       => 'Diafragma',
'exif-brightnessvalue'     => 'parlaqlıq',
'exif-lightsource'         => 'Yarıq turuşı',
'exif-exposureindex'       => 'Ekspozitsiya indeksi',
'exif-scenetype'           => 'Stsena çeşiti',
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
'edit-externally-help' => '(Daa ziyade malümat içün [http://www.mediawiki.org/wiki/Manual:External_editors bu saifege] (İnglizce)  baqıp olasıñız.)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'episini',
'imagelistall'     => 'Cümlesi',
'watchlistall2'    => 'episini',
'namespacesall'    => 'Episi',
'monthsall'        => 'Episi',

# E-mail address confirmation
'confirmemail'             => 'E-mail adresini tasdıqla',
'confirmemail_noemail'     => '[[Special:Preferences|Qullanıcı sazlamalarıñızda]] dоğru bir e-mail adresiñiz yoq.',
'confirmemail_text'        => '{{SITENAME}} saytınıñ e-mail funktsiyalarını qullanmazdan evel e-mail adresiñizniñ tasdıqlanması kerek. Adresiñizge tasdıq e-mail mektübini yollamaq içün aşağıdaki dögmeni basıñız. Yollanacaq beyanatta adresiñizni tasdıqlamaq içün brauzeriñiznen irişip olacaq, tasdıq kodu olğan bir bağlantı olacaq.',
'confirmemail_pending'     => 'Tasdıq kodu endi sizge yollandı.
Eger esabıñıznı keçenleri açsa ediñiz, belki de yañnı kodnı bir daa sorağanıñızda, biraz beklemek kerek olur.',
'confirmemail_send'        => 'Tasdıq kodunı yolla',
'confirmemail_sent'        => 'Tasdıq e-mail mektübini yollandı.',
'confirmemail_oncreate'    => 'Belgilegen e-mail adresiñizge tasdıq kodunen mektüp yollandı.
İşbu kod oturım açmaq içün lâzim degil, amma bu proyektte elektron poçtasınıñ çarelerini qullanmaq içün ruhset berilmezden evel onı belgilemelisiñiz.',
'confirmemail_sendfailed'  => '{{SITENAME}} tasdıq kodunı yollap olamay. Lütfen, adreste ruhsetsiz arif ya da işaret olmağanını teşkeriñiz. 

Serverniñ cevabı: $1',
'confirmemail_invalid'     => 'Yañlış tasdıq kodu. Tasdıq kodunıñ soñki qullanma tarihı keçken ola bilir.',
'confirmemail_needlogin'   => '$1 yapmaq içün başta e-mail adresiñizni tasdıqlamalısıñız.',
'confirmemail_success'     => 'E-mail adresiñiz tasdıqlandı.',
'confirmemail_loggedin'    => 'E-mail adresiñiz tasdıqlandı.',
'confirmemail_error'       => 'Tasdıqıñız bilinmegen bir hata sebebinden qayd etilmedi.',
'confirmemail_subject'     => '{{SITENAME}} e-mail adres tasdıqı.',
'confirmemail_body'        => '$1 IP adresinden yapılğan irişim ile {{SITENAME}} saytında
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
'invalidateemail'          => 'E-mail adresiniñ tasdıqı lâğu et',

# Scary transclusion
'scarytranscludedisabled' => '["Interwiki transcluding" işlemey]',
'scarytranscludefailed'   => '[$1 şablonına irişilip olamadı]',
'scarytranscludetoolong'  => '[URL adresi çoq uzun]',

# Trackbacks
'trackbackbox'      => 'Bu saife içün trackback:<br />
$1',
'trackbackremove'   => '([$1 yoq et])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback muvafaqiyetnen yoq etildi.',

# Delete conflict
'deletedwhileediting' => "'''Tenbi''': Bu saife siz deñişiklik yapmağa başlağandan soñ yoq etildi!",
'confirmrecreate'     => "Siz bu saifeni deñiştirgen vaqıtta [[User:$1|$1]] ([[User talk:$1|muzakere]]) qullanıcısı onı yoq etkendir, sebebi:
:''$2''
Saifeni yañıdan yaratmağa isteseñiz, lütfen, bunı tasdıqlañız.",
'recreate'            => 'Saifeni yañıdan yarat',

# action=purge
'confirm_purge_button' => 'Ok',
'confirm-purge-top'    => 'Saife keşini temizlesinmi?',

# Multipage image navigation
'imgmultipageprev' => '← evelki saife',
'imgmultipagenext' => 'soñraki saife →',
'imgmultigo'       => 'Bar',

# Table pager
'ascending_abbrev'         => 'kiçikten büyükke',
'descending_abbrev'        => 'büyükten kiçikke',
'table_pager_next'         => 'Soñraki saife',
'table_pager_prev'         => 'Evelki saife',
'table_pager_first'        => 'İlk saife',
'table_pager_last'         => 'Soñki saife',
'table_pager_limit'        => 'Saife başına $1 dane köster',
'table_pager_limit_submit' => 'Bar',
'table_pager_empty'        => 'İç netice yoq',

# Auto-summaries
'autosumm-blank'   => 'Saife boşatıldı',
'autosumm-replace' => "Saifedeki malümat '$1' ile deñiştirildi",
'autoredircomment' => '[[$1]] saifesine yollandı',
'autosumm-new'     => "Yañı saife yaratıldı. Mündericesi: '$1'",

# Live preview
'livepreview-loading' => 'Yüklenmekte…',
'livepreview-ready'   => 'Yüklenmekte… Azır!',
'livepreview-failed'  => 'Tez baqıp çıquv işlemey! Adiy baqıp çıquvnı qullanıp baqıñız.',
'livepreview-error'   => 'Bağlanamadı: $1 "$2". Adiy baqıp çıquvnı qullanıp baqıñız.',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 saniyeden evel ve ondan soñ yapılğan deñişiklikler bu cedvelde kösterilmeyip olalar.',
'lag-warn-high'   => 'Malümat bazasındaki problemalar sebebinden $1 saniyeden evel ve ondan soñ yapılğan deñişiklikler bu cedvelde kösterilmeyip olalar.',

# Watchlist editor
'watchlistedit-numitems'       => 'Muzakere saifesini esapqa almayıp, közetüv cedveliñizde {{PLURAL:$1|1|$1}} saife bar.',
'watchlistedit-noitems'        => 'Közetüv cedveliñizde iç bir saife yoq.',
'watchlistedit-normal-title'   => 'Közetüv ceveliñizni deñiştirmektesiñiz',
'watchlistedit-normal-legend'  => 'Közetüv cedvelinden saife yoq etilüvi',
'watchlistedit-normal-explain' => 'Közetüv cedveliñizdeki saifeler aşağıda buluna. Saife közetüv cedvelinden yoq etmek içün onı belgilep "Saylanğan saifelerni közetüv cedvelinden yoq et" yazısına basıñız. Közetüv cedveliñizni [[Special:Watchlist/raw|metin olaraq da deñiştirip]] olasıñız.',
'watchlistedit-normal-submit'  => 'Saylanğan saifelerni közetüv cevelinden yoq et',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 saife|$1 saife}} közetüv cedveliñizden yoq etildi:',
'watchlistedit-raw-title'      => 'Közetüv ceveliñizni deñiştirmektesiñiz',
'watchlistedit-raw-legend'     => 'Közetüv cedvelini deñiştirilüvi',
'watchlistedit-raw-explain'    => 'Közetüv cedveliñizdeki saifeler aşağıda buluna. Cedvelge saife adı kirsetip ya da ondan yoq etip (er satırda birer ad) onı deñiştirip olasıñız. Bitirgen soñ "közetüv cedvelini yañart" yazısına basıñız. [[Special:Watchlist/edit|Standart redaktornı da qullanıp olursıñız]].',
'watchlistedit-raw-titles'     => 'Saifeler:',
'watchlistedit-raw-submit'     => 'Közetüv cedvelini yañart',
'watchlistedit-raw-done'       => 'Közetüv cedveliñiz yañardı.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 saife|$1 saife}} ilâve olundı:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 saife|$1 saife}} yoq etildi:',

# Watchlist editing tools
'watchlisttools-view' => 'Deñişikliklerni köster',
'watchlisttools-edit' => 'Közetüv cedvelini kör ve deñiştir',
'watchlisttools-raw'  => 'Közetüv cedvelini adiy metin olaraq deñiştir',

# Special:Version
'version' => 'Versiya', # Not used as normal message but as header for the special page itself

# Special:FileDuplicateSearch
'fileduplicatesearch-legend'   => 'Dublikatnı qıdır',
'fileduplicatesearch-filename' => 'Fayl adı:',
'fileduplicatesearch-submit'   => 'Qıdır',
'fileduplicatesearch-info'     => '$1 × $2 piksel<br />Fayl büyükligi: $3<br />MIME çeşiti: $4',
'fileduplicatesearch-result-1' => '"$1" faylınıñ iç kоpiyası yоq.',
'fileduplicatesearch-result-n' => '"$1" faylınıñ {{PLURAL:$2|bir kоpiyası|$2 kоpiyası}} bar.',

# Special:SpecialPages
'specialpages'                   => 'Mahsus saifeler',
'specialpages-group-maintenance' => 'Baqım esabatları',
'specialpages-group-other'       => 'Diger mahsus saifeler',
'specialpages-group-login'       => 'Oturım aç / Qayd оl',
'specialpages-group-changes'     => 'Soñki deñişiklikler ve jurnallar',
'specialpages-group-media'       => 'Fayl esabatları ve yükleme',
'specialpages-group-users'       => 'Qullanıcılar ve aqları',
'specialpages-group-highuse'     => 'Çоq qullanılğan saifeler',
'specialpages-group-pages'       => 'Saifeler cedveli',
'specialpages-group-pagetools'   => 'Saife aletleri',
'specialpages-group-wiki'        => 'Viki malümat ve aletler',
'specialpages-group-redirects'   => 'Yollama mahsus saifeler',

# Special:BlankPage
'blankpage'              => 'Bоş saife',
'intentionallyblankpage' => 'Bu saife aselet boş qaldırılğan',

);
