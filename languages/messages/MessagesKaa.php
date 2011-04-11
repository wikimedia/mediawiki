<?php
/** Kara-Kalpak (Qaraqalpaqsha)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AlefZet
 * @author Atabek
 * @author Jiemurat
 * @author Reedy
 * @author Urhixidur
 */

$fallback = 'kk-latn';

$separatorTransformTable = array(
	',' => "\xc2\xa0",
	'.' => ',',
);

$fallback8bitEncoding = 'windows-1254';

$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Arnawlı',
	NS_TALK             => 'Sa\'wbet',
	NS_USER             => 'Paydalanıwshı',
	NS_USER_TALK        => 'Paydalanıwshı_sa\'wbeti',
	NS_PROJECT_TALK     => '$1_sa\'wbeti',
	NS_FILE             => 'Su\'wret',
	NS_FILE_TALK        => 'Su\'wret_sa\'wbeti',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_sa\'wbeti',
	NS_TEMPLATE         => 'Shablon',
	NS_TEMPLATE_TALK    => 'Shablon_sa\'wbeti',
	NS_HELP             => 'Anıqlama',
	NS_HELP_TALK        => 'Anıqlama_sa\'wbeti',
	NS_CATEGORY         => 'Kategoriya',
	NS_CATEGORY_TALK    => 'Kategoriya_sa\'wbeti',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Qos burıwshılar' ),
	'BrokenRedirects'           => array( 'Jaramsız burıwshılar' ),
	'Disambiguations'           => array( 'Ko\'p ma\'nisliler' ),
	'Userlogin'                 => array( 'Kiriw', 'Paydalanıwshı kiriw' ),
	'Userlogout'                => array( 'Shıg\'ıw', 'Paydalanıwshı shıg\'ıw' ),
	'Preferences'               => array( 'Sazlawlar' ),
	'Watchlist'                 => array( 'Baqlaw dizimi' ),
	'Recentchanges'             => array( 'Aqırg\'ı o\'zgerisler' ),
	'Listfiles'                 => array( 'Su\'wretler dizimi' ),
	'Newimages'                 => array( 'Taza su\'wretler' ),
	'Listusers'                 => array( 'Paydalanıwshılar', 'Paydalanıwshı dizimi' ),
	'Statistics'                => array( 'Statistika' ),
	'Randompage'                => array( 'Qa\'legen', 'Qa\'legen bet' ),
	'Lonelypages'               => array( 'Hesh betten siltelmegen betler' ),
	'Uncategorizedpages'        => array( 'Kategoriyasız betler' ),
	'Uncategorizedcategories'   => array( 'Kategoriyasız kategoriyalar' ),
	'Uncategorizedimages'       => array( 'Kategoriyasız su\'wretler' ),
	'Uncategorizedtemplates'    => array( 'Kategoriyasız shablonlar' ),
	'Unusedcategories'          => array( 'Paydalanılmag\'an kategoriyalar' ),
	'Unusedimages'              => array( 'Paydalanılmag\'an fayllar', 'Paydalanılmag\'an su\'wretler' ),
	'Wantedpages'               => array( 'Talap qılıng\'an betler', 'Jaramsız sıltewler' ),
	'Wantedcategories'          => array( 'Talap qılıng\'an kategoriyalar' ),
	'Mostlinked'                => array( 'Ko\'p siltelgenler' ),
	'Mostlinkedcategories'      => array( 'Ko\'p paydalanılg\'an kategoriyalar' ),
	'Mostlinkedtemplates'       => array( 'Ko\'p paydalanılg\'an shablonlar' ),
	'Mostimages'                => array( 'Ko\'p paydalanılg\'an su\'wretler' ),
	'Mostcategories'            => array( 'Ko\'p kategoriyalang\'anlar' ),
	'Mostrevisions'             => array( 'Ko\'p du\'zetilgenler' ),
	'Fewestrevisions'           => array( 'Az du\'zetilgenler' ),
	'Shortpages'                => array( 'Qqısqa betler' ),
	'Longpages'                 => array( 'Uzın betler' ),
	'Newpages'                  => array( 'Taza betler' ),
	'Ancientpages'              => array( 'Eski betler' ),
	'Deadendpages'              => array( 'Hesh betke siltemeytug\'ın betler' ),
	'Protectedpages'            => array( 'Qorg\'alg\'an betler' ),
	'Allpages'                  => array( 'Ha\'mme betler' ),
	'Specialpages'              => array( 'Arnawlı betler' ),
	'Contributions'             => array( 'Paydalanıwshı u\'lesi' ),
	'Emailuser'                 => array( 'Xat jiberiw' ),
	'Whatlinkshere'             => array( 'Siltelgen betler' ),
	'Recentchangeslinked'       => array( 'Baylanıslı aqırg\'ı o\'zgerisler' ),
	'Movepage'                  => array( 'Betti ko\'shiriw' ),
	'Categories'                => array( 'Kategoriyalar' ),
	'Export'                    => array( 'Eksport' ),
	'Version'                   => array( 'Versiya' ),
	'Allmessages'               => array( 'Ha\'mme xabarlar' ),
	'Log'                       => array( 'Jurnal', 'Jurnallar' ),
	'Userrights'                => array( 'Paydalanıwshı huqıqları' ),
	'MIMEsearch'                => array( 'MIME izlew' ),
	'Unwatchedpages'            => array( 'Baqlanılmag\'an betler' ),
	'Listredirects'             => array( 'Burıwshılar dizimi' ),
	'Revisiondelete'            => array( 'Nusqanı o\'shiriw' ),
	'Unusedtemplates'           => array( 'Paydalanılmag\'an shablonlar' ),
	'Randomredirect'            => array( 'Qa\'legen burıwshı' ),
	'Mypage'                    => array( 'Menin\' betim' ),
	'Mytalk'                    => array( 'Menin\' sa\'wbetim' ),
	'Mycontributions'           => array( 'Menin\' u\'lesim' ),
	'Listadmins'                => array( 'Administratorlar' ),
	'Popularpages'              => array( 'Ko\'p ko\'rilgen betler' ),
	'Search'                    => array( 'İzlew' ),
	'Withoutinterwiki'          => array( 'Hesh tilge siltemeytug\'ın betler' ),
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
	'mdy date' => 'xg j, Y "j."',
	'mdy both' => 'H:i, xg j, Y "j."',

	'dmy time' => 'H:i',
	'dmy date' => 'j F, Y "j."',
	'dmy both' => 'H:i, j F, Y "j."',

	'ymd time' => 'H:i',
	'ymd date' => 'Y "j." xg j',
	'ymd both' => 'H:i, Y "j." xg j',

	'yyyy-mm-dd time' => 'xnH:xni:xns',
	'yyyy-mm-dd date' => 'xnY-xnm-xnd',
	'yyyy-mm-dd both' => 'xnH:xni:xns, xnY-xnm-xnd',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$linkTrail = "/^([a-zı'ʼ’“»]+)(.*)$/sDu";

$messages = array(
# User preference toggles
'tog-underline'               => "Siltewdin' astın sız:",
'tog-highlightbroken'         => 'Jaramsız siltewlerdi <a href="" class="new">usılay</a> tuwrıla (alternativ: usınday<a href="" class="internal">?</a>).',
'tog-justify'                 => "Tekstti bettin' ken'ligi boyınsha tuwrılaw",
'tog-hideminor'               => "Aqırg'ı o'zgerislerden kishilerin jasır",
'tog-extendwatchlist'         => "Baqlaw dizimin barlıq o'zgerislerdi ko'rsetetug'ın qılıp ken'eyt (tek aqırg'ıların emes)",
'tog-usenewrc'                => "Ken'eytilgen jaqındag'ı o'zgerislerdi qollan (JavaScript bolıwın talap etedi)",
'tog-numberheadings'          => 'Atamalardı avtomat nomerle',
'tog-showtoolbar'             => "O'zgertiw a'sbapların ko'rset (JavaScript)",
'tog-editondblclick'          => "Eki ma'rte basıp o'zgertiw (JavaScript)",
'tog-editsection'             => "Bo'limlerdi [o'zgertiw] siltew arqalı o'zgertiwdi qos",
'tog-editsectiononrightclick' => "Bo'lim atamasın on' jaqqa basıp o'zgertiwdi qos (JavaScript)",
'tog-showtoc'                 => "Mazmunın ko'rset (3-ten artıq bo'limi bar betlerge)",
'tog-rememberpassword'        => "Menin' kirgenimdi usı kompyuterde saqlap qal (en' ko'bi menen $1 {{PLURAL:$1|ku'nge|ku'nge}} shekem)",
'tog-watchcreations'          => 'Men jaratqan betlerdi baqlaw dizimime qos',
'tog-watchdefault'            => "Men o'zgeris kiritken betlerdi baqlaw dizimime qos",
'tog-watchmoves'              => "Men ko'shirgen betlerdi baqlaw dizimime qos",
'tog-watchdeletion'           => "Men o'shirgen betlerdi baqlaw dizimime qos",
'tog-minordefault'            => "Defolt boyınsha barlıq o'zgerislerdi kishi dep esaplaw",
'tog-previewontop'            => "O'zgertiw maydanınan aldın ko'rip shıg'ıw maydanın ko'rset",
'tog-previewonfirst'          => "Birinshi o'zgertiwdi ko'rip shıq",
'tog-nocache'                 => "Brauzer bet keshlewin o'shir",
'tog-enotifwatchlistpages'    => "Baqlaw dizimimdegi bet o'zgertilgende mag'an xat jiber",
'tog-enotifusertalkpages'     => "Menin' sa'wbetim o'zgertilgende mag'an xat jiber",
'tog-enotifminoredits'        => "Kishi o'zgerisler haqqında da mag'an xat jiber",
'tog-enotifrevealaddr'        => "Eskertiw xatlarında e-mail adresimdi ko'rset",
'tog-shownumberswatching'     => "Baqlag'an paydalanıwshılar sanın ko'rset",
'tog-fancysig'                => 'İmzalardı wikitext dep qabıl etiw (avtomat siltewsiz)',
'tog-externaleditor'          => "Defolt boyınsha sırtqı o'zgertiwshini qollan (tek g'ana ta'jiriybeli paydalanıwshılar ushın, kompyuterin'izde qosımsha sazlawlar qılınıwı kerek)",
'tog-externaldiff'            => "Defoltta sırtqı parqtı qollan (tek ekspert paydalanıwshılar ushın, kompyuterin'izde arnawlı sazlawlardı talap etedi)",
'tog-showjumplinks'           => "«O'tip ketiw» siltewlerin qos",
'tog-uselivepreview'          => "Janlı ko'rip shıg'ıwdı qollan (JavaScript) (Sınawda)",
'tog-forceeditsummary'        => "O'zgertiw juwmag'ı bos qalg'anda mag'an eskert",
'tog-watchlisthideown'        => "Baqlaw dizimindegi menin' o'zgertiwlerimdi jasır",
'tog-watchlisthidebots'       => "Baqlaw dizimindegi bot o'zgertiwlerin jasır",
'tog-watchlisthideminor'      => "Baqlaw diziminen kishi o'zgerislerdi jasır",
'tog-watchlisthideliu'        => "Baqlaw dizimindegi kirgen paydalanıwshılardın' o'zgerislerin jasır",
'tog-watchlisthideanons'      => "Baqlaw dizimindegi anonim paydalanıwshılardın' o'zgerislerin jasır",
'tog-ccmeonemails'            => "Basqa qollanıwshılarg'a jibergen xatlarımnın' ko'shirmesin mag'an da jiber",
'tog-diffonly'                => "Bet mag'lıwmatın parqlardan to'mengi jerde ko'rsetpe",
'tog-showhiddencats'          => "Jasırın kategoriyalardı ko'rset",
'tog-norollbackdiff'          => "Artqa qaytarıwdan keyin parqlardı ko'rsetpe",

'underline-always'  => "Ha'r dayım",
'underline-never'   => 'Hesh qashan',
'underline-default' => "Brawzerdin' sazlawları boyınsha",

# Dates
'sunday'        => 'Ekshenbi',
'monday'        => "Du'yshenbi",
'tuesday'       => 'Siyshenbi',
'wednesday'     => "Sa'rshenbi",
'thursday'      => 'Piyshenbi',
'friday'        => 'Juma',
'saturday'      => 'Shenbi',
'sun'           => 'Eks',
'mon'           => 'Dsh',
'tue'           => 'Ssh',
'wed'           => "Sa'r",
'thu'           => 'Psh',
'fri'           => 'Jum',
'sat'           => 'Shn',
'january'       => 'Yanvar',
'february'      => 'Fevral',
'march'         => 'Mart',
'april'         => 'Aprel',
'may_long'      => 'May',
'june'          => 'İyun',
'july'          => 'İyul',
'august'        => 'Avgust',
'september'     => 'Sentyabr',
'october'       => 'Oktyabr',
'november'      => 'Noyabr',
'december'      => 'Dekabr',
'january-gen'   => "yanvardın'",
'february-gen'  => "fevraldın'",
'march-gen'     => "marttın'",
'april-gen'     => "apreldin'",
'may-gen'       => "maydın'",
'june-gen'      => "iyunnin'",
'july-gen'      => "iyuldin'",
'august-gen'    => "avgusttın'",
'september-gen' => "sentyabrdin'",
'october-gen'   => "oktyabrdin'",
'november-gen'  => "noyabrdin'",
'december-gen'  => "dekabrdin'",
'jan'           => 'Yan',
'feb'           => 'Fev',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'May',
'jun'           => 'İun',
'jul'           => 'İul',
'aug'           => 'Avg',
'sep'           => 'Sen',
'oct'           => 'Okt',
'nov'           => 'Noy',
'dec'           => 'Dek',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategoriya|Kategoriyalar}}',
'category_header'                => '"$1" kategoriyasindag\'ı betler',
'subcategories'                  => 'Podkategoriyalar',
'category-media-header'          => '"$1" kategoriyasindag\'ı media',
'category-empty'                 => "''Bul kategoriyada ha'zir hesh bet yamasa media joq''",
'hidden-categories'              => '{{PLURAL:$1|Jasırın kategoriya|Jasırın kategoriyalar}}',
'hidden-category-category'       => 'Jasırın kategoriyalar',
'category-subcat-count'          => "{{PLURAL:$2|Bul kategoriyada tek to'mendegi podkategoriya bar.|Bul kategoriyada $1 podkategoriya bar (barlıg'ı $2).}}",
'category-subcat-count-limited'  => "Bul kategoriyada to'mendegi {{PLURAL:$1|podkategoriya|$1 podkategoriyalar}} bar.",
'category-article-count'         => "{{PLURAL:$2|Bul kategoriyada tek to'mendegi bet bar.|Bul kategoriyada to'mendegi $1 bet bar (barlıg'ı $2).}}",
'category-article-count-limited' => "Usı kategoriyada to'mendegi {{PLURAL:$1|bet|$1 bet}} bar.",
'category-file-count'            => "{{PLURAL:$2|Bul kategoriyada tek to'mendegi fayl bar.|Bul kategoriyada to'mendegi $1 fayl bar (barlıg'ı $2).}}",
'category-file-count-limited'    => "Usı kategoriyada to'mendegi {{PLURAL:$1|fayl|$1 fayl}} bar.",
'listingcontinuesabbrev'         => 'dawamı',

'linkprefix'        => '/^(.*?)([a-zıA-Zİ\\x80-\\xff]+)$/sDu',
'mainpagetext'      => "'''MediaWiki tabıslı ornatıldı.'''",
'mainpagedocfooter' => "Wiki bag'darlamasın qollanıw haqqındag'i mag'lıwmat usın [http://meta.wikimedia.org/wiki/Help:Contents Paydalanıwshılar qollanbasınan] ken'es alın'.

== Baslaw ushın ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Konfiguratsiya sazlaw dizimi]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWikidin' Ko'p Soralatug'ın Sorawları]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki haqqında xat tarqatıw dizimi]",

'about'         => 'Proyekt haqqında',
'article'       => "Mag'lıwmat beti",
'newwindow'     => "(jan'a aynada)",
'cancel'        => 'Biykar etiw',
'moredotdotdot' => "Ja'ne...",
'mypage'        => "Menin' betim",
'mytalk'        => "Menin' sa'wbetim",
'anontalk'      => "Usı IP sa'wbeti",
'navigation'    => 'Navigatsiya',
'and'           => "&#32;ha'm",

# Cologne Blue skin
'qbfind'         => 'Tabıw',
'qbbrowse'       => "Ko'riw",
'qbedit'         => "O'zgertiw",
'qbpageoptions'  => 'Usı bet',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => "Menin' betlerim",
'qbspecialpages' => 'Arnawlı betler',
'faq'            => 'KBS',
'faqpage'        => 'Project:KBS',

# Vector skin
'vector-action-addsection' => 'Tema qosıw',
'vector-action-delete'     => "O'shiriw",
'vector-action-move'       => "Ko'shiriw",
'vector-action-protect'    => "Qorg'aw",
'vector-action-undelete'   => 'Qayta tiklew',
'vector-action-unprotect'  => "Qorg'amaw",
'vector-view-create'       => 'Jaratıw',
'vector-view-edit'         => "O'zgertiw",
'vector-view-history'      => "Tariyxın ko'riw",
'vector-view-view'         => 'Oqıw',
'vector-view-viewsource'   => "Deregin ko'riw",
'actions'                  => "Ha'reketler",
'namespaces'               => "İsimler ko'plikleri",
'variants'                 => 'Variantlar',

'errorpagetitle'    => 'Qatelik',
'returnto'          => '$1 betine qaytıw.',
'tagline'           => "{{SITENAME}} mag'lıwmatı",
'help'              => 'Anıqlama',
'search'            => 'İzlew',
'searchbutton'      => 'İzle',
'go'                => "O'tiw",
'searcharticle'     => "O'tin'",
'history'           => 'Bet tariyxı',
'history_short'     => 'Tariyx',
'updatedmarker'     => "aqırg'ı kirgenimnen keyin jan'alang'anlar",
'info_short'        => "Mag'lıwmat",
'printableversion'  => 'Baspa nusqası',
'permalink'         => 'Turaqlı siltew',
'print'             => "Baspag'a shıg'arıw",
'edit'              => "O'zgertiw",
'create'            => 'Jaratıw',
'editthispage'      => "Usı betti o'zgertiw",
'create-this-page'  => 'Bul betti jaratıw',
'delete'            => "O'shiriw",
'deletethispage'    => "Usı betti o'shiriw",
'undelete_short'    => "{{PLURAL:$1|1 o'zgeristi|$1 o'zgerisin}} qayta tiklew",
'protect'           => "Qorg'aw",
'protect_change'    => "qorg'awdı o'zgertiw",
'protectthispage'   => "Bul betti qorg'aw",
'unprotect'         => "Qorg'amaw",
'unprotectthispage' => "Bul betti qorg'amaw",
'newpage'           => 'Taza bet',
'talkpage'          => 'Bul betti diskussiyalaw',
'talkpagelinktext'  => "Sa'wbet",
'specialpage'       => 'Arnawlı bet',
'personaltools'     => "Paydalanıwshı a'sbapları",
'postcomment'       => "Taza bo'lim",
'articlepage'       => "Mag'lıwmat betin ko'riw",
'talk'              => 'Diskussiya',
'views'             => "Ko'rinis",
'toolbox'           => "A'sbaplar",
'userpage'          => "Paydalanıwshı betin ko'riw",
'projectpage'       => "Proyekt betin ko'riw",
'imagepage'         => "Fayl betin ko'riw",
'mediawikipage'     => "Xabar betin ko'riw",
'templatepage'      => "Shablon betin ko'riw",
'viewhelppage'      => "Anıqlama betin ko'riw",
'categorypage'      => "Kategoriya betin ko'riw",
'viewtalkpage'      => "Diskussiyanı ko'riw",
'otherlanguages'    => 'Basqa tillerde',
'redirectedfrom'    => "($1 degennen burılg'an)",
'redirectpagesub'   => 'Burıwshı bet',
'lastmodifiedat'    => "Bul bettin' aqırg'ı ma'rte o'zgertilgen waqtı: $2, $1.",
'viewcount'         => "Bul bet {{PLURAL:$1|bir ma'rte|$1 ma'rte}} ko'rip shıg'ılg'an.",
'protectedpage'     => "Qorg'alg'an bet",
'jumpto'            => "Bug'an o'tiw:",
'jumptonavigation'  => 'navigatsiya',
'jumptosearch'      => 'izlew',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} haqqında',
'aboutpage'            => 'Project:Proyekt haqqında',
'copyright'            => "Mag'lıwmat $1 boyınsha alıng'an.",
'copyrightpage'        => '{{ns:project}}:Avtorlıq huquqları',
'currentevents'        => "Ha'zirgi ha'diyseler",
'currentevents-url'    => "Project:Ha'zirgi ha'diyseler",
'disclaimers'          => 'Juwapkershilikten bas tartıw',
'disclaimerpage'       => 'Project:Juwapkershilikten bas tartıw',
'edithelp'             => "O'zgertiw anıqlaması",
'edithelppage'         => "Help:O'zgertiw",
'helppage'             => 'Help:Mazmunı',
'mainpage'             => 'Bas bet',
'mainpage-description' => 'Bas bet',
'policy-url'           => "Project:Qag'ıydalar",
'portal'               => "Ja'miyet portalı",
'portal-url'           => "Project:Ja'miyet Portalı",
'privacy'              => "Konfidentsiallıq qag'ıydası",
'privacypage'          => "Project:Konfidentsiallıq qag'ıydası",

'badaccess'        => 'Ruxsatnama qateligi',
'badaccess-group0' => "Soralıp atırg'an ha'reketin'izdi bejeriwge ruqsatın'ız joq.",
'badaccess-groups' => "Soralıp atırg'an ha'reketin'iz to'mendegi {{PLURAL:$2|topar|toparlardın' birinin'}} paydalanıwshılarına sheklengen: $1",

'versionrequired'     => "MediaWikidin' $1 nusqası kerek",
'versionrequiredtext' => "Bul betti paydalanıw ushın MediaWikidin' $1 nusqası kerek. [[Special:Version|Nusqa beti]]n qaran'.",

'ok'                      => 'OK',
'retrievedfrom'           => '"$1" saytınan alıng\'an',
'youhavenewmessages'      => 'Sizge $1 bar ($2).',
'newmessageslink'         => "jan'a xabarlar",
'newmessagesdifflink'     => "aqırg'ı o'zgeris",
'youhavenewmessagesmulti' => "$1 betinde sizge jan'a xabarlar bar",
'editsection'             => "o'zgertiw",
'editold'                 => "o'zgertiw",
'viewsourceold'           => "deregin ko'riw",
'editlink'                => "o'zgertiw",
'viewsourcelink'          => "kodın ko'riw",
'editsectionhint'         => "$1 bo'limin o'zgertiw",
'toc'                     => 'Mazmunı',
'showtoc'                 => "ko'rset",
'hidetoc'                 => 'jasır',
'thisisdeleted'           => "$1: ko'riw yamasa qayta tiklew?",
'viewdeleted'             => "$1 ko'riw?",
'restorelink'             => "{{PLURAL:$1|bir o'shirilgen o'zgeris|$1 o'shirilgen o'zgeris}}",
'feedlinks'               => 'Jolaq:',
'feed-invalid'            => "Natuwrı jazılıw jolaqsha tu'ri.",
'feed-unavailable'        => "Tarqatılatug'ın jolaqlar joq",
'site-rss-feed'           => '$1 saytının\' "RSS" jolag\'ı',
'site-atom-feed'          => '$1 saytının\' "Atom" jolag\'ı',
'page-rss-feed'           => '"$1" betinin\' "RSS" jolag\'ı',
'page-atom-feed'          => '"$1" betinin\' "Atom" jolag\'ı',
'feed-atom'               => '"Atom"',
'feed-rss'                => '"RSS"',
'red-link-title'          => "$1 (bet ele jaratılmag'an)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Bet',
'nstab-user'      => 'Paydalanıwshı',
'nstab-media'     => 'Media beti',
'nstab-special'   => 'Arnawlı bet',
'nstab-project'   => 'Proyekt beti',
'nstab-image'     => 'Fayl beti',
'nstab-mediawiki' => 'Xabar',
'nstab-template'  => 'Shablon',
'nstab-help'      => 'Anıqlama beti',
'nstab-category'  => 'Kategoriya',

# Main script and global functions
'nosuchaction'      => "Bunday ha'reket joq",
'nosuchactiontext'  => "Bul URLda ko'rsetilgen ha'reket jaramsız
Siz URLdi kiritiwde qa'te jibergenin'iz yamasa nadurıs siltewge o'tken bolıwın'ız mu'mkin
Bul sonday-aq {{SITENAME}} ta'repinen paydalanılg'an bag'darlamasında qa'te barlıg'ın bildiriwi mu'mkin",
'nosuchspecialpage' => 'Bunday arnawlı bet joq',
'nospecialpagetext' => "<strong>Siz sorag'an bunday arnawlı bet joq.</strong>

Arnawlı betlerdin' dizimin [[Special:SpecialPages|{{int:specialpages}}]] betinen tabıwın'ızg'a boladı.",

# General errors
'error'                => "Qa'telik",
'databaseerror'        => "Mag'lıwmatlar bazası qa'tesi",
'dberrortext'          => "Mag'lıwmatlar bazası sorawında sintaksis qa'tesi sa'dir boldı.
Bul bag'darlamada qa'te barlıg'ın bildiriwi mu'mkin.
Aqırg'ı soralg'an mag'lıwmatlar bazası sorawı:
<blockquote><tt>\$1</tt></blockquote>
\"<tt>\$2</tt>\" funktsiyasınan.
Mag'lıwmatlar bazası qaytarg'an qa'tesi \"<tt>\$3: \$4</tt>\".",
'dberrortextcl'        => 'Mag\'lıwmatlar bazası sorawında sintaksis qa\'tesi sa\'dir boldı.
Aqırg\'ı soralg\'an mag\'lıwmatlar bazası sorawı:
"$1"
funktsiya: "$2".
Mag\'lıwmatlar bazası qaytarg\'an qa\'tesi "$3: $4".',
'laggedslavemode'      => "Esletpe: Bette aqırg'ı jan'alanıwlar bolmawı mu'mkin.",
'readonly'             => "Mag'lıwmatlar bazası qulplang'an",
'enterlockreason'      => "Qulıplawdın' sebebin ha'mde qansha waqıtqa esaplang'anlıg'ın ko'rsetin'",
'readonlytext'         => "Bul mag'lıwmatlar bazası ha'zirshe jan'a ha'm basqada o'zgerislerdi kiritiwden qulıplang'an, ba'lkim rejelestirilgen sazlawlar ushındır.
Qulıplag'an administratordın' qaldırg'an tu'sindirmesi: $1",
'missing-article'      => "Bar bolıwı kerek bolg'an to'mendegi bet teksti mag'lıwmatlar bazasında tabılmadı: \"\$1\" \$2.

Bul eskirgen parq siltewine yamasa o'shirilgen bettin' tariyx betine o'tkende sa'dir bolıwı mu'mkin.

Eger bular orınlı bolmasa, bag'darlamadag'ı qa'tege tuwrı kelgen bolıwın'ız mu'mkin.
İltimas bul haqqında URL adresin ko'rsetip, [[Special:ListUsers/sysop|administratorlarg'a]] xabar berin'.",
'missingarticle-rev'   => "(du'zetiw#: $1)",
'missingarticle-diff'  => '(Ayrm.: $1, $2)',
'readonly_lag'         => "Ekilenshi mag'lıwmatlar bazası serveri baslısı menen sixronlasıw waqtında, mag'lıwmatlar bazası waqtınsha avtomatik halda o'zgerislerden bloklang'an",
'internalerror'        => "İshki qa'telik",
'internalerror_info'   => "İshki qa'telik: $1",
'filecopyerror'        => '"$1" faylın "$2" faylına ko\'shiriw a\'melge aspadı.',
'filerenameerror'      => '"$1" faylı atı "$2" atına o\'zgertilmedi.',
'filedeleteerror'      => '"$1" faylı o\'shirilmedi.',
'directorycreateerror' => '"$1" papkası jaratılmadı.',
'filenotfound'         => '"$1" faylı tabılmadı.',
'fileexistserror'      => '"$1" faylına jazıwg\'a bolmaydı: bunday fayl bar',
'unexpected'           => 'Ku\'tilmegen ma\'nis: "$1" = "$2".',
'formerror'            => "Qatelik: forma mag'lıwmatların jiberiw mu'mkin emes",
'badarticleerror'      => "Bunday ha'reket bul bette atqarılmaydı.",
'cannotdelete'         => '"$1" beti yamasa faylı o\'shirilmedi. 
Bunı basqa birew aldınlaw o\'shigen bolıwı mu\'mkin.',
'badtitle'             => 'Jaramsız atama',
'badtitletext'         => "Sorag'an betin'izdin' ataması natuwrı, bos, tillerara yamasa inter-wiki ataması natuwrı ko'rsetilgen. Atamada qollanıwg'a bolmaytug'ın bir yamasa bir neshe simvollar bolıwı mu'mkin.",
'perfcached'           => "To'mendegi mag'lıwmat keshlengen ha'mde jan'alanbag'an bolıwı mu'mkin.",
'perfcachedts'         => "To'mendegi mag'lıwmat keshlengen, aqırg'ı keshlengen waqtı: $1",
'querypage-no-updates' => "Bul bettin' jan'alanıwı ha'zirshe o'shirilgen.
Bul jerde keltirilgen mag'lıwmatlar o'zgertilmeydi.",
'wrong_wfQuery_params' => 'wfQuery() funktsiyası ushın natuwrı parametrler berilgen<br />
Funktsiya: $1<br />
Soraw: $2',
'viewsource'           => "Deregin ko'riw",
'viewsourcefor'        => '$1 ushın',
'actionthrottled'      => "Ha'reket toqtatıldı",
'actionthrottledtext'  => "Spamg'a qarsı gu'res esabında, bunday ha'reketti qısqa waqıtta dım ko'p ma'rte bejeriwin'iz sheklenedi, ha'mde siz usı limitten o'tip ketkensiz.
Birneshe minuttan keyin qaytadan ha'reket qılıp ko'rin'.",
'protectedpagetext'    => "Bul bet o'zgertiwdin' aldın alıw ushın qulplang'an.",
'viewsourcetext'       => "Bul bettin' deregin qarawın'ızg'a ha'mde ko'shirip alıwın'ızg'a boladı:",
'editinginterface'     => "'''Esletpe:''' Siz ishinde MediaWiki sistema xabarı bar bolg'an betti o'zgertip atırsız.
Bul bettin' o'zgeriwi basqa paydalanıwshılardın' sırtqı interfeisine ta'sir etedi.
Audarıw ushın,  MediaWiki programmasın jersindiriw [http://translatewiki.net/wiki/Main_Page?setlang=kaa translatewiki.net proyektisin] qarap shıg'ın'ız.",
'sqlhidden'            => "(SQL sorawı jasırılg'an)",
'namespaceprotected'   => "'''$1''' isimler ko'pligindegi betlerdi o'zgertiwge ruxsatın'ız joq.",
'customcssjsprotected' => "Bul betti o'zgertiwin'izge ruqsatın'ız joq, sebebi bul jerde basqa paydalanıwshılardın' jeke sazlawları bar.",
'ns-specialprotected'  => '"{{ns:special}}:" isimler ko\'pligindegi betler o\'zgertilmeydi',
'titleprotected'       => "Bul atamanı jaratıw [[User:$1|$1]] ta'repinen qorg'alg'an.
Keltirilgen sebep: ''$2''.",

# Virus scanner
'virus-unknownscanner' => 'belgisiz antivirus:',

# Login and logout pages
'logouttext'                 => "'''Siz endi sayttan shıqtın'ız.'''

Siz {{SITENAME}} saytınan anonim halda paydalanıwın'ız mu'mkin.
Yamasa siz ja'ne ha'zirgi yaki basqa paydalanıwshı atı menen [[Special:UserLogin|qaytadan sistemag'a kiriwin'izge]] boladı.
Sonı este saqlan', ayrım betler sizin' brauzerin'izdin' keshi tazalanbag'anlıg'ı sebebli sistemada kirgenin'izdey ko'riniste dawam ettire beriwi mu'mkin.",
'welcomecreation'            => "== Xosh keldin'iz, $1! ==

Akkauntın'ız jaratıldı.
[[Special:Preferences|{{SITENAME}} sazlawların'ızdı]] o'zgertiwdi umıtpan'.",
'yourname'                   => 'Paydalanıwshı atı:',
'yourpassword'               => 'Parol:',
'yourpasswordagain'          => "Paroldi qayta kiritin':",
'remembermypassword'         => "Menin' kirgenimdi usı kompyuterde saqlap qal (en' ko'bi menen $1 {{PLURAL:$1|ku'nge|ku'nge}} shekem)",
'yourdomainname'             => "Sizin' domen:",
'login'                      => 'Kiriw',
'nav-login-createaccount'    => 'Kiriw / akkaunt jaratıw',
'loginprompt'                => "{{SITENAME}} saytına kiriw ushın kukiler qosılg'an bolıwı kerek.",
'userlogin'                  => 'Kiriw / akkaunt jaratıw',
'logout'                     => "Shıg'ıw",
'userlogout'                 => "Shıg'ıw",
'notloggedin'                => 'Kirilmegen',
'nologin'                    => "Akkauntın'ız joqpa? '''$1'''.",
'nologinlink'                => "Akkaunt jaratın'",
'createaccount'              => 'Akkaunt jarat',
'gotaccount'                 => "Akkauntın'ız barma? '''$1'''.",
'gotaccountlink'             => 'Kir',
'createaccountmail'          => 'e-mail arqalı',
'badretype'                  => 'Siz kiritken parol tuwra kelmedi.',
'userexists'                 => "Kiritken paydalanıwshı atı ba'nt. Basqa at kiritin'.",
'loginerror'                 => 'Kiriwde qatelik',
'nocookiesnew'               => "Paydalanıwshı akkauntı jaratıldı, biraq ele kirmegensiz.
Paydalanıwshılar kiriwi ushın {{SITENAME}} kukilerden paydalanadı.
Sizde kukiler o'shirilgen.
Kukilerdi qosıp, jan'a paydalanıwshı atın'ız ha'm parolin'iz arqalı kirin'.",
'nocookieslogin'             => "Paydalanıwshılar kiriwi ushın {{SITENAME}} kukilerden paydalanadı.
Sizde kukiler o'shirilgen.
Kukilerdi qosıp, qaytadan ko'rin'.",
'noname'                     => 'Siz kiritken paydalanıwshı atı qate.',
'loginsuccesstitle'          => "Kiriw tabıslı a'melge asırıldı",
'loginsuccess'               => "'''{{SITENAME}} saytına \"\$1\" paydalanıwshı atı menen kirdin'iz.'''",
'nosuchuser'                 => "\"\$1\" atlı paydalanıwshı joq.
Tuwrı jazılg'anlıg'ın tekserin' yamasa [[Special:UserLogin/signup|taza akkaunt jaratın']].",
'nosuchusershort'            => '"<nowiki>$1</nowiki>" atlı paydalanıwshı joq. Tuwrı jazılg\'anlıg\'ın tekserin\'.',
'nouserspecified'            => "Siz paydalanıwshı atın ko'rsetpedin'iz.",
'wrongpassword'              => "Qate parol kiritlgen. Qaytadan kiritin'.",
'wrongpasswordempty'         => "Parol kiritilmegen. Qaytadan ha'reket etin'.",
'passwordtooshort'           => "Parolin'iz jaramsız yamasa dım qısqa.
En' keminde {{PLURAL:$1|1 ha'rip|$1 ha'rip}} ha'mde paydalanıwshı atın'ızdan o'zgeshe bolıwı kerek.",
'mailmypassword'             => "Paroldi e-mailg'a jiberiw",
'passwordremindertitle'      => '{{SITENAME}} ushın taza waqtınsha parol',
'passwordremindertext'       => "Kimdir (IP adresi: $1, ba'lkim o'zin'iz shıg'ar)
{{SITENAME}} ushın bizden taza parol jiberiwimizdi sorag'an ($4).
Endi «$2» paydalanıwshının' paroli «$3». Eger bul sizin' maqsetin'iz bolg'an bolsa,
ha'zir kirip parolin'izdi almastırıwın'ız kerek.
Sizin' waqtınshalıq parolin'iz {{PLURAL:$5|bir ku'nnen|$5 ku'nnen}} keyin o'z ku'shin jog'altadı.

Eger basqa birew bunı sorag'an bolsa yamasa parolin'izdi eslegen bolsan'ız,
bul xabarg'a itibar bermey, aldıng'ı parolin'izdi qollanıwın'ızg'a boladı.",
'noemail'                    => '"$1" paydalanıwshının\' e-mailı joq.',
'passwordsent'               => "Taza parol «$1» ushın ko'rsetilgen e-mail
adresine jiberildi.
Qabıl qılg'anın'ızdan keyin qaytadan kirin'.",
'blocked-mailpassword'       => "IP adresin'iz o'zgeris kiritiwden bloklang'an, so'nın' ushın paroldi tiklew funktsiyasın ha'm paydalana almaysız.",
'eauthentsent'               => "Tastıyıqlaw xatı e-mail adresin'izge jiberildi.
Basqa e-mail jiberiwden aldın, akkaunt shın'ınan siziki ekenin
tastıyıqlaw ushın xattag'ı ko'rsetpelerdi bejerin'.",
'throttled-mailpassword'     => "Aqırg'ı {{PLURAL:$1|saat|$1 saat}} ishinde parol eskertiw xatı jiberildi.
Jaman jolda paydalanıwdın' aldın alıw ushın, ha'r {{PLURAL:$1|saat|$1 saat}} sayın tek g'ana bir parol eskertiw xatı jiberiledi.",
'mailerror'                  => 'Xat jiberiwde qatelik juz berdi: $1',
'acct_creation_throttle_hit' => "Keshirersiz, siz aldın {{PLURAL:$1|1 akkaunt|$1 akkaunt}} jaratqansız.
Bunnan artıq jaratıw mu'mkinshiligin'iz joq.",
'emailauthenticated'         => "Sizin' e-mail adresin'iz tastıyqlang'an waqtı: $2, $3.",
'emailnotauthenticated'      => "E-mail adresin'iz ele tastıyıqlanbag'an.
To'mendegi mu'mkinshilikler ushın hesh xat jiberilmeydi.",
'noemailprefs'               => "Usı mu'mkinshilikler islewi ushın e-mail adresin'izdi ko'rsetin'.",
'emailconfirmlink'           => "E-mail adresin'izdi tastıyıqlan'",
'invalidemailaddress'        => "E-mail adresin'iz nadurıs formatta bolg'anı ushın qabıl etile almaydı.
Durıs formattag'ı adresin'izdi ko'rsetin', yamasa qatardı bos qaldırın'.",
'accountcreated'             => 'Akkaunt jaratıldı',
'accountcreatedtext'         => '$1 paydalanıwshısına akkaunt jaratıldı.',
'createaccount-title'        => '{{SITENAME}} ushın akkaunt jaratıw',
'createaccount-text'         => 'Kimdir e-mail adresin\'izdi paydalanıp {{SITENAME}} saytında ($4) "$2" atı menen, "$3" paroli menen akkaunt jaratqan.
Endi saytqa kirip parolin\'izdi o\'zgertiwin\'iz kerek.

Eger bul akkaunt nadurıs jaratılg\'an bolsa, bul xabarg\'a itibar bermesen\'izde boladı.',
'loginlanguagelabel'         => 'Til: $1',

# Password reset dialog
'resetpass'                 => "Paroldi o'zgertiw",
'resetpass_announce'        => "E-mailin'izge jiberilgen waqtınshalıq kod penen kirdin'iz.
Kiriw protsessin juwmaqlaw ushın jan'a parolin'izdi usı jerge kiritin':",
'resetpass_header'          => "Akkaunt parolin o'zgertiw",
'oldpassword'               => "Aldıng'ı parol:",
'newpassword'               => 'Taza parol:',
'retypenew'                 => "Taza paroldi qayta kiritin':",
'resetpass_submit'          => "Paroldi kirgizin'",
'resetpass_success'         => "Parolin'iz sa'tli o'zgertildi! Endi kirin'...",
'resetpass_forbidden'       => "Paroller o'zgertile almaydi",
'resetpass-submit-loggedin' => "Paroldi o'zgertiw",
'resetpass-temp-password'   => 'Waqtınshalıq parol:',

# Edit page toolbar
'bold_sample'     => 'Yarım juwan tekst',
'bold_tip'        => 'Yarım juwan tekst',
'italic_sample'   => 'Kursiv tekst',
'italic_tip'      => 'Kursiv tekst',
'link_sample'     => 'Siltew ataması',
'link_tip'        => 'İshki siltew',
'extlink_sample'  => 'http://www.example.com siltew ataması',
'extlink_tip'     => "Sırtqı siltew (http:// prefiksin kiritin')",
'headline_sample' => 'Atama teksti',
'headline_tip'    => "2-shi da'rejeli atama",
'math_sample'     => "Usı jerge formulanı jazın'",
'math_tip'        => 'Matematik formula (LaTeX)',
'nowiki_sample'   => "Formatlanbag'an tekstti usı jerge qoyın'",
'nowiki_tip'      => 'Wiki formatlawın esapqa almaw',
'image_tip'       => "Jaylastırılg'an fayl",
'media_tip'       => 'Fayl siltewi',
'sig_tip'         => "Sizin' imzan'iz ha'mde waqıt belgisi",
'hr_tip'          => "Gorizont bag'ıtındag'ı sızıq (dım ko'p paydalanban')",

# Edit pages
'summary'                          => 'Juwmaq:',
'subject'                          => 'Ataması:',
'minoredit'                        => "Bul kishi o'zgeris",
'watchthis'                        => 'Bul betti baqlaw',
'savearticle'                      => 'Betti saqla',
'preview'                          => "Ko'rip shıg'ıw",
'showpreview'                      => "Ko'rip shıq",
'showlivepreview'                  => "Tez ko'rip shıg'ıw",
'showdiff'                         => "O'zgerislerdi ko'rset",
'anoneditwarning'                  => "'''Esletpe:''' Siz kirmedin'iz. Sizin' IP adresin'iz usi bettin' o'zgeris tariyxında saqlanıp qaladı.",
'missingsummary'                   => "'''Esletpe:''' O'zgeristin' qısqasha mazmunın ko'rsetpedin'iz.
\"Saqlaw\"dı ja'ne bassan'ız, o'zgerislerin'iz hesh qanday kommentariysiz saqlanadı.",
'missingcommenttext'               => "Kommentariydi to'mende kiritin'.",
'missingcommentheader'             => "'''Eskertpe:''' Bul kommentariy ushın atama ko'rsetpedin'iz.
Eger ja'ne \"{{int:savearticle}}\" bassan'ız, o'zgerislerin'iz olsız saqlanadı.",
'summary-preview'                  => "Juwmag'ın ko'rip shıg'ıw:",
'subject-preview'                  => 'Atamanı aldınnan qaraw:',
'blockedtitle'                     => "Paydalanıwshı bloklang'an",
'blockedtext'                      => "'''Paydalaniwshı atın'ız yamasa IP adresin'iz bloklang'an.'''

Bloklawdı \$1 a'melge asırg'an.
Keltirilgen sebebi: ''\$2''.

* Bloklaw baslang'an: \$8
* Bloklaw tamamlang'an: \$6
* Bloklaw maqseti: \$7

Usı bloklawdı diskussiya qılıw ushın \$1 yamasa basqa [[{{MediaWiki:Grouppage-sysop}}|administratorlar]] menen baylanısqa shıg'ıwın'ızg'a boladı.
Siz [[Special:Preferences|akkaunt sazlawların'ızda]] haqıyqıy e-mailin'izdı ko'rsetpegenin'izshe ha'mde onı paydalanıwdan bloklang'an bolg'anısha \"Usı paydalanıwshıg'a xat jazıw\" qa'siyetinen qollana almaysız.
Sizin' ha'zirgi IP adresin'iz: \$3, bloklaw IDı: #\$5.
Usılardın' birewin yamasa ekewinde ha'r bir sorawın'ızg'a qosın'.",
'blockednoreason'                  => 'hesh sebep keltirilmegen',
'blockedoriginalsource'            => "'''$1''' degennin' deregi
to'mende ko'rsetilgen:",
'blockededitsource'                => "'''$1''' degennin' '''siz ozgertken''' teksti to'mende ko'rsetilgen:",
'whitelistedittitle'               => "O'zgertiw ushın sistemag'a kiriwin'iz kerek",
'whitelistedittext'                => "Betterdi o'zgertiw ushın $1 sha'rt.",
'confirmedittext'                  => "Betlerge o'zgeris kiritiwin'iz ushın aldın E-pochta adresin'izdi tastıyıqlawın'ız kerek.
E-pochta adresin'izdi [[Special:Preferences|paydalanıwshı sazlawları bo'limi]] arqalı ko'rsetin' ha'm jaramlılıg'ın tekserin'.",
'nosuchsectiontitle'               => "Bul bo'lim tabılmadı",
'nosuchsectiontext'                => "Ele jaratılmag'an bo'limdi o'zgerpekshi boldın'ız.
Siz bul betti ko'rip atırg'an waqtın'ızda ol ko'shirilgen yamasa o'shirilgen bolıwı mu'mkin.",
'loginreqtitle'                    => "Sistemag'a kiriw kerek",
'loginreqlink'                     => 'kiriw',
'loginreqpagetext'                 => "Basqa betlerdi ko'riw ushın sizge $1 kerek.",
'accmailtitle'                     => 'Parol jiberildi.',
'accmailtext'                      => "[[User talk:$1|$1]] ushın qaytadan jaratılg'an parol $2 g'a jiberildi.
Saytqa kirgenin'izden keyin, bul akkaunt parolin ''[[Special:ChangePassword|change password]]'' betinde o'zgertiwge boladı.",
'newarticle'                       => '(Taza)',
'newarticletext'                   => "Siz ele jaratılmag'an betke siltew arqalı o'ttin'iz.
Betti jaratıw ushın to'mendegi aynada tekstin'izdi kiritin' (qosımsha mag'lıwmat ushın [[{{MediaWiki:Helppage}}|anıqlama betin]] qaran').
Eger bul jerge aljasıp o'tken bolsan'ız, brauzerin'izdin' «Arqag'a» knopkasın basın'.",
'noarticletext'                    => "Ha'zirgi waqıtta bul bette hesh qanday mag'lıwmat joq.
Basqa betlerden usı bet atamasın [[Special:Search/{{PAGENAME}}|izlep ko'riwin'izge]],
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tiyisli jurnallardı izlewin'izge],
yamasa usı betti [{{fullurl:{{FULLPAGENAME}}|action=edit}} jaratıwin'ızga']</span> boladi.",
'userpage-userdoesnotexist'        => "\"\$1\" paydalanıwshı akkauntı registratsiya qılınbag'an. Bul betti jaratqın'ız yamasa o'zgertkin'iz kelse tekserip ko'rin'.",
'updated'                          => "(Jan'alang'an)",
'note'                             => "'''Eskertiw:'''",
'previewnote'                      => "'''Bul ele tek aldınnan ko'rip shıg'ıw; o'zgerisler ele saqlanbadı!'''",
'session_fail_preview'             => "'''Keshirersiz! Sessiya mag'lıwmatlarının' jog'alıwı sebepli o'zgerislerin'izdi qabıl ete almaymız.
Qaytadan ha'reket qılıp ko'rin'. Eger bul payda bermese, [[Special:UserLogout|shıg'ıp]] qaytadan kirip ko'rin'.'''",
'editing'                          => "$1 o'zgertilmekte",
'editingsection'                   => "$1 (bo'limi) o'zgertilmekte",
'editingcomment'                   => "$1 (taza bo'lim) o'zgertilmekte",
'editconflict'                     => "O'zgertiw konflikti: $1",
'yourtext'                         => "Sizin' tekst",
'storedversion'                    => "Saqlang'an nusqası",
'yourdiff'                         => 'Parqlar',
'copyrightwarning'                 => "Este tutın', {{SITENAME}} proyektinde jaylastırılg'an ha'm o'zgertilgen maqalalar tekstleri $2 sha'rt tiykarında qaraladı (tolıqraq mag'lıwmat ushın: $1). Eger siz tekstin'izdin' erkin tarqatılıwın ha'mde qa'legen paydalanıwshı o'zgertiwin qa'lemesen'iz, bul jerge jaylastırmag'anın'ız maqul.<br />
Qosqan u'lesin'iz o'zin'izdin' jazg'anın'ız yamasa ashıq tu'rdegi derekten alıng'anlig'ına wa'de berin'.
'''AVTORLIQ HUQUQI MENEN QORG'ALG'AN MAG'LIWMATLARDI RUXSATSIZ JAYLASTIRMAN'!'''",
'copyrightwarning2'                => "Este tutın', {{SITENAME}} proyektindegi barlıq u'lesler basqa paydalanıwshılar arqalı o'zgertiliwi yamasa o'shiriliwi mu'mkin. Eger siz tekstin'izdin' erkin tarqatılıwın ha'mde qa'legen paydalanıwshı o'zgertiwin qa'lemesen'iz, bul jerge jaylastırmag'anın'ız maqul.<br /> Qosqan u'lesin'iz o'zin'izdin' jazg'anın'ız yamasa ashıq tu'rdegi derekten alıng'anlig'ına wa'de berin' (qosımsha mag'lıwmat ushın $1 hu'jjetin qaran'). '''AVTORLIQ HUQUQI MENEN QORG'ALG'AN MAG'LIWMATLARDI RUXSATSIZ JAYLASTIRMAN'!'''",
'semiprotectedpagewarning'         => "'''Eskertiw:''' Bul bet qulplang'an, onı tek registratsiyadan o'tken paydalanıwshılar g'ana o'zgerte aladı.
To'mende en' aqırg'ı jurnal mag'lıwmatları berilgen.",
'templatesused'                    => "Bul bette qollanılg'an {{PLURAL:$1|shablon|shablonlar}}:",
'templatesusedpreview'             => "Bul aldınnan ko'riw betinde qollanılg'an {{PLURAL:$1|shablon|shablonlar}}:",
'templatesusedsection'             => "Bul bo'limde qollanılg'an {{PLURAL:$1|shablon|shablonlar}}:",
'template-protected'               => "(qorg'alg'an)",
'template-semiprotected'           => "(yarım-qorg'alg'an)",
'hiddencategories'                 => "Bul bet {{PLURAL:$1|1 jasırın kategoriyasının'|$1 jasırın kategoriyalarının'}} ag'zası:",
'nocreatetitle'                    => 'Bet jaratıw sheklengen',
'nocreatetext'                     => "{{SITENAME}} saytında taza betlerdi jaratıw sheklengen.
Arqag'a qaytıp bar betti o'zgertiwin'izge yamasa [[Special:UserLogin|kiriwin'izge / akkaunt jaratıwın'ızg'a]] boladı.",
'nocreate-loggedin'                => "Taza betler jaratıwın'ızg'a ruxsatın'ız joq.",
'permissionserrors'                => 'Ruxsatnamalar Qatelikleri',
'permissionserrorstext-withaction' => "$2 ha'reketine ruxsatın'ız joq, to'mendegi {{PLURAL:$1|sebep|sebepler}} boyınsha:",
'recreate-moveddeleted-warn'       => "'''Esletpe: Aldın o'shirilgen betti qayta jaratajaqsız.'''

Usi betti qaytadan jaratıw tuwrılıg'ın oylap ko'rin'.
Qolaylıq ushın to'mende o'shiriw jurnalı keltirilgen:",
'moveddeleted-notice'              => "Bul bet o'shirilgen.
To'mende mag'lıwmat ushın bettin' o'shiriw ha'm ko'shiriw jurnalı ko'rsetilgen.",
'edit-conflict'                    => "O'zgerislerdegi konflikt.",

# Parser/template warnings
'parser-template-loop-warning' => 'Shablonlarda qaytalanıw tabıldı: [[$1]]',

# Account creation failure
'cantcreateaccounttitle' => 'Akkaunt jaratılmadı',
'cantcreateaccount-text' => "[[User:$3|$3]] usı IP adresten ('''$1''') akkaunt jaratıwın blokladı.

$3 keltirilgen sebebi: ''$2''",

# History pages
'viewpagelogs'           => "Usı bettin' jurnalın ko'riw",
'nohistory'              => "Bul bettin' o'zgertiw tariyxı joq.",
'currentrev'             => "Ha'zirgi nusqa",
'currentrev-asof'        => "Bul nusqanın' waqtı: $1",
'revisionasof'           => '$1 waqtındagı nusqası',
'revision-info'          => "$1 waqtındag'ı $2 istegen nusqası",
'previousrevision'       => '←Eskilew nusqası',
'nextrevision'           => "Jan'alaw nusqası→",
'currentrevisionlink'    => "Ha'zirgi nusqa",
'cur'                    => "ha'z.",
'next'                   => 'keyin.',
'last'                   => 'aqır.',
'page_first'             => 'birinshi',
'page_last'              => "aqırg'ı",
'histlegend'             => "Tu'sindirme: salıstırajaq nusqaların'ızdı saylan' ha'mde <Enter> knopkasın yamasa to'mendegi knopkani basın'.<br />
Sha'rtli belgiler: (ha'z.) = ha'zirgi nusqasi menen parqı,
(aqır.) = aldıng'ı nusqasi menen parqı, k = kishi o'zgeris",
'history-fieldset-title' => 'Tariyxınan izlew',
'histfirst'              => "En' aldıng'ısı",
'histlast'               => "En' aqırg'ısı",
'historysize'            => '({{PLURAL:$1|1 bayt|$1 bayt}})',
'historyempty'           => '(bos)',

# Revision feed
'history-feed-title'          => 'Nusqa tariyxı',
'history-feed-description'    => "Usı bettin' wikidegi nusqa tariyxı",
'history-feed-item-nocomment' => "$2 waqtındag'ı $1",

# Revision deletion
'rev-deleted-comment'       => "(kommentariy o'shirildi)",
'rev-deleted-user'          => "(paydalanıwshı atı o'shirildi)",
'rev-deleted-event'         => "(jurnal ha'reketi o'shirildi)",
'rev-delundel'              => "ko'rsetiw/jasırıw",
'revdelete-selected'        => "'''[[:$1]] {{PLURAL:$2|saylang'an nusqası|saylang'an nusqaları}}:'''",
'revdelete-legend'          => "Ko'rinis sheklewlerin belgilew",
'revdelete-hide-text'       => 'Nusqa tekstin jasır',
'revdelete-hide-image'      => "Fayl mag'lıwmatın jasır",
'revdelete-hide-name'       => "Ha'reket ha'm onın' obyektin jasır",
'revdelete-hide-comment'    => "O'zgertiw kommentariyin jasır",
'revdelete-hide-user'       => "O'zgeriwshi atın/IP jasır",
'revdelete-hide-restricted' => "Mag'lıwmatlardı administratorlar menen basqalardan da jasırıw",
'revdelete-suppress'        => "Mag'lıwmatlardı administratorlar menen basqalardan da jasırıw",
'revdelete-unsuppress'      => 'Qayta tiklengen nusqalardan sheklewlerdi alıp taslaw',
'revdelete-log'             => 'Sebep:',
'revdelete-submit'          => "Saylang'an {{PLURAL:$1|nusqag'a|nusqalarg'a}} qollaw",
'revdelete-logentry'        => "[[$1]] nusqa ko'rinisin o'zgertti",
'logdelete-logentry'        => "[[$1]] waqıya ko'rinisi o'zgerdi",
'revdelete-success'         => "'''Nusqa ko'rinisi tabıslı jan'alandı.'''",
'logdelete-success'         => "'''Jurnal ko'rinisi tabıslı ornatıldı.'''",
'revdel-restore'            => "Ko'rinisin o'zgertiw",
'pagehist'                  => 'Bet tariyxı',
'deletedhist'               => "O'shirilgenler tariyxı",
'revdelete-content'         => "mag'lıwmat",
'revdelete-summary'         => "o'zgerislerdin' qısqasha mazmunı",
'revdelete-uname'           => 'paydalanıwshı atı',
'revdelete-restricted'      => "administratorlarg'a qollanılg'an sheklewler",
'revdelete-unrestricted'    => "administratorlardan alıp taslang'an sheklewler",
'revdelete-hid'             => '$1 jasırıldı',
'revdelete-unhid'           => "$1 ko'rsetildi",
'revdelete-log-message'     => "$2 ushın $1 {{PLURAL:$2|o'zgeris|o'zgeris}}",
'logdelete-log-message'     => '$2 {{PLURAL:$2|waqıya|waqıya}} ushın $1',

# Suppression log
'suppressionlog' => 'Jasırıw jurnalı',

# History merging
'mergehistory'                     => 'Bet tariyxların qos',
'mergehistory-box'                 => "Eki bettin' nusqaların biriktiriw:",
'mergehistory-from'                => 'Derek bet:',
'mergehistory-into'                => 'Belgilengen bet:',
'mergehistory-list'                => "O'zgerislerdin' biriktirilgen tariyxı",
'mergehistory-go'                  => "Qosılıwı mu'mkin bolg'an oz'geriserdi ko'rset",
'mergehistory-submit'              => 'Nusqalardı biriktiriw',
'mergehistory-empty'               => 'Biriktiriwge nusqalar tabılmadı',
'mergehistory-success'             => "[[:$1]] betinin' $3 {{PLURAL:$3|nusqası|nusqaları}} [[:$2]] beti menen tabıslı biriktirildi.",
'mergehistory-fail'                => "Bet tariyxların biriktiriw a'melge aspadı, bet ha'm waqıt sazlawların ja'ne bir tekserip ko'rin'.",
'mergehistory-no-source'           => "$1 derek beti ele jaratılmag'an.",
'mergehistory-no-destination'      => "$1 aqırg'ı beti ele jaratılmag'an.",
'mergehistory-invalid-source'      => "Derek beti durıs atamag'a iye bolıwı sha'rt.",
'mergehistory-invalid-destination' => "Aqırg'ı bet durıs atamag'a iye bolıwı sha'rt.",
'mergehistory-autocomment'         => '[[:$1]] degen [[:$2]] degenge biriktirildi',
'mergehistory-comment'             => '[[:$1]] degen [[:$2]] degenge biriktirildi: $3',
'mergehistory-same-destination'    => "Derek ha'm aqırg'ı betler birdey bolmawı kerek",

# Merge log
'mergelog'           => 'Biriktiriw jurnalı',
'pagemerge-logentry' => '[[$1]] degen [[$2]] degenge biriktirildi ($3 shekemgi nusqalar)',
'revertmerge'        => 'Ajırat',
'mergelogpagetext'   => "To'mende bir bet tariyxının' basqa betke biriktiriliwinin' en' aqırg'ı dizimi keltirilgen.",

# Diffs
'history-title'           => '"$1" betinin\' nusqa tariyxı',
'difference'              => "(Nusqalar arasındag'ı ayırmashılıq)",
'lineno'                  => 'Qatar No $1:',
'compareselectedversions' => "Saylang'an nusqalardı salıstırıw",
'editundo'                => 'qaytar',
'diff-multi'              => "({{PLURAL:$2|bir paydalanıwshı|$2 paydalanıwshı}} ta'repinen {{PLURAL:$1|aralıq bir nusqa|aralıq $1 nusqa}} ko'rsetilmeydi.)",

# Search results
'searchresults'                    => "İzlew na'tiyjeleri",
'searchresults-title'              => '"$1" sorawnaması boyınsha tabılg\'an na\'tiyjeler',
'searchresulttext'                 => "{{SITENAME}} saytında izlew haqqında ko'birek mag'lıwmat alg'ın'ız kelse, [[{{MediaWiki:Helppage}}|{{int:help}} betine]] o'tip qarap ko'rin'.",
'searchsubtitle'                   => 'İzlegenin\'iz: \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|"$1" baslanıwshı barlıq betler]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" siltewshi barlıq betler]])',
'searchsubtitleinvalid'            => "'''$1''' ushın izlegenin'iz",
'toomanymatches'                   => "Dım ko'p sa'ykeslikler qaytarıldı, basqa sorawdı isletip ko'rin'",
'titlematches'                     => "Bet ataması sa'ykes keledi",
'notitlematches'                   => 'Hesh qanday bet ataması tuwra kelmedi',
'textmatches'                      => "Bet tekstinin' tuwra kelgenleri",
'notextmatches'                    => 'Hesh qanday bet teksti tuwra kelmedi',
'prevn'                            => "aldıng'ı {{PLURAL:$1|$1}}",
'nextn'                            => 'keyingi {{PLURAL:$1|$1}}',
'viewprevnext'                     => "Ko'riw: ($1 {{int:pipe-separator}} $2) ($3)",
'searchmenu-legend'                => 'İzlew sazlawları',
'searchmenu-exists'                => "'''Bul wikide \"[[:\$1]]\" atamalı bet bar'''",
'searchmenu-new'                   => "'''Bul wikide \"[[:\$1]]\" betin jaratıw!'''",
'searchhelp-url'                   => 'Help:Mazmunı',
'searchmenu-prefix'                => "[[Special:PrefixIndex/$1|Usı prefiks penen baslanıwshı betlerdi ko'rset]]",
'searchprofile-articles'           => "Mag'lıwmat betleri",
'searchprofile-project'            => "Ja'rdem ha'm Proekt betleri",
'searchprofile-images'             => 'Multimediya',
'searchprofile-everything'         => 'Barlıq jerde',
'searchprofile-advanced'           => "Ken'eytilgen",
'searchprofile-articles-tooltip'   => '$1 izlew',
'searchprofile-project-tooltip'    => '$1 izlew',
'searchprofile-images-tooltip'     => 'Fayllardı izlew',
'searchprofile-everything-tooltip' => "Barlıq betlerde izlew (sa'wbet betlerin qosıp)",
'searchprofile-advanced-tooltip'   => "Berilgen isimler ko'pliginde izlew",
'search-result-size'               => "$1 ({{PLURAL:$2|1 so'z|$2 so'z}})",
'search-result-score'              => "Qatnasıqlıg'ı: $1%",
'search-redirect'                  => "(qayta bag'ıtlandırıw $1)",
'search-section'                   => "(bo'lim $1)",
'search-suggest'                   => "Ba'lkim, siz bunı na'zerde tutqan shig'arsız: $1",
'search-interwiki-caption'         => 'Qarındas proektler',
'search-interwiki-default'         => "$1 na'tiyje:",
'search-interwiki-more'            => "(ko'birek)",
'search-mwsuggest-enabled'         => 'usınıslar menen',
'search-mwsuggest-disabled'        => 'usınıslarsız',
'search-relatedarticle'            => 'Baylanıslı',
'mwsuggest-disable'                => "AJAX usınısların o'shir",
'searchrelated'                    => 'baylanıslı',
'searchall'                        => 'barlıq',
'showingresults'                   => "To'mende '''$2''' ornınan baslap {{PLURAL:$1|'''1''' na'tiyje|'''$1''' shekemgi na'tiyjeler}} ko'rsetilgen.",
'showingresultsnum'                => "To'mende '''$2''' ornınan baslap {{PLURAL:$3|'''1''' na'tiyje|'''$3''' na'tiyje}} ko'rsetilgen.",
'nonefound'                        => "'''Esletpe''': Defolt boyınsha tek g'ana sheklengen isimler ko'pliginen izlenedi.
Barlıq mag'lıwmat tu'rin (sonın' ishinde sa'wbet betlerdi, shablonlardı h.t.b.) izlew ushın izlewin'izdi ''all:'' prefiksi menen baslan', yamasa qa'legen isimler ko'pligin prefiks esabında qollanın'.",
'search-nonefound'                 => "Sorawg'a sa'ykes na'tiyje tabılmadı.",
'powersearch'                      => "Ken'eytilgen izlew",
'powersearch-legend'               => "Ken'eytilgen izlew",
'powersearch-ns'                   => "Usı isimler ko'pliginen izlew:",
'powersearch-redir'                => "Qayta bag'ıtlawshı betlerdi ko'rset",
'powersearch-field'                => "İzlenetug'ın so'z (yamasa so'z dizbegi):",
'search-external'                  => 'Sırtqı izlewshi',

# Quickbar
'qbsettings'               => 'Navigatsiya paneli',
'qbsettings-none'          => 'Hesh qanday',
'qbsettings-fixedleft'     => 'Shepke bekitilgen',
'qbsettings-fixedright'    => "On'g'a bekitilgen",
'qbsettings-floatingleft'  => 'Shepte jıljıwshı',
'qbsettings-floatingright' => "On'da jıljıwshı",

# Preferences page
'preferences'               => 'Sazlawlar',
'mypreferences'             => "Menin' sazlawlarım",
'prefs-edits'               => "O'zgertiwler sanı:",
'prefsnologin'              => 'Kirilmegen',
'prefsnologintext'          => 'Sazlawların\'ızdı ornatıw ushın <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} kiriwin\'iz]</span> sha\'rt.',
'changepassword'            => "Paroldi o'zgertiw",
'prefs-skin'                => "Sırtqı ko'rinis",
'skin-preview'              => 'Korip al',
'prefs-math'                => 'Formulalar',
'datedefault'               => 'Hesh sazlawlarsız',
'prefs-datetime'            => "Sa'ne ha'm waqıt",
'prefs-personal'            => 'Paydalanıwshı profaylı',
'prefs-rc'                  => "Aqırg'ı o'zgerisler",
'prefs-watchlist'           => 'Baqlaw dizimi',
'prefs-watchlist-days'      => "Baqlaw dizimindegi ku'nlerdin' ko'rsetiw sanı:",
'prefs-watchlist-days-max'  => "Maksimum 7 ku'n",
'prefs-watchlist-edits'     => "Ken'eytilgen baqlaw dizimindegi o'zgeristerdin' en' ko'p ko'rsetiw sanı:",
'prefs-watchlist-edits-max' => 'Maksimum: 1000',
'prefs-misc'                => 'Basqa',
'prefs-resetpass'           => "Paroldi o'zgertiw",
'saveprefs'                 => 'Saqla',
'resetprefs'                => "Saqlanbag'an o'zgerislerdi o'shir",
'prefs-editing'             => "O'zgertiw",
'prefs-edit-boxsize'        => "O'zgertiw aynasının' o'lshemi.",
'rows'                      => 'Qatarlar:',
'columns'                   => "Bag'analar:",
'searchresultshead'         => 'İzlew',
'recentchangesdays'         => "Aqırg'ı o'zgerislerde ko'rsetiletug'ın ku'nler:",
'recentchangesdays-max'     => "(maksimum $1 {{PLURAL:$1|ku'n|ku'n}})",
'recentchangescount'        => "U'ndemeslik boyınsha ko'rsetiletug'ın o'zgerisler sanı:",
'savedprefs'                => "Sizin' sazlawların'ız saqlandı.",
'timezonelegend'            => 'Waqıt zonası:',
'localtime'                 => 'Jergilikli waqıt:',
'timezoneuseserverdefault'  => "Serverdin' baslang'ısh sazlawların qollanıw",
'timezoneuseoffset'         => "Basqa (o'zgeristi ko'rsetin')",
'timezoneoffset'            => "Saat o'zgerisi¹:",
'servertime'                => 'Server waqtı:',
'guesstimezone'             => 'Brauzerden alıp toltırıw',
'allowemail'                => 'Basqalardan xat qabıllawdı qos',
'prefs-searchoptions'       => 'İzlew sazlawları',
'prefs-namespaces'          => "İsimler ko'plikleri",
'defaultns'                 => "Bolmasa usı isimler ko'plikleri boyınsha izlew:",
'default'                   => 'defolt',
'prefs-files'               => 'Fayllar',
'youremail'                 => 'E-mail:',
'username'                  => 'Paydalanıwshı atı:',
'uid'                       => 'Paydalanıwshı IDsı:',
'prefs-memberingroups'      => "Kirgen {{PLURAL:$1|toparın'ız|toparların'ız}}:",
'yourrealname'              => "Haqıyqıy isimin'iz:",
'yourlanguage'              => 'Til:',
'yourvariant'               => "Tu'ri",
'yournick'                  => "Laqabın'ız:",
'badsig'                    => "Shala imzalar nadurıs; HTML teglerin tekserip ko'rin'.",
'badsiglength'              => "İmzan'ız dım uzın.
{{PLURAL:$1|simvoldan|simvoldan}} aspawı kerek.",
'email'                     => 'E-mail',
'prefs-help-realname'       => "Haqıyqıy atın'ız (ma'jbu'riy emes): eger onı ko'rsetsen'iz, bet kim ta'repinen o'zgertilgenin ko'rsetiwde qollanıladı.",
'prefs-help-email'          => "E-mail adresin'izdi ko'rsetiw ma'jbu'riy emes, biraq bul eger siz parolin'izdi esten shig'arsan'iz usı e-mailge taza paroldi jiberiw mu'mkinshiligin jaratadı.
Siz ja'ne de basqa paydalanıwshılarg'a siz benen (adresin'izdi bilmegen halda) paydalanıwshı yamasa paydalanıwshı_sa'wbeti betleri arqalı baylanısıw imkaniyatın jaratadı.",
'prefs-help-email-required' => 'E-mail adresi kerek.',

# User rights
'userrights'                  => 'Paydalanıwshı huqıqların basqarıw',
'userrights-lookup-user'      => 'Paydalanıwshı toparların basqarıw',
'userrights-user-editname'    => "Paydalanıwshı atın kiritin':",
'editusergroup'               => "Paydalanıwshı Toparların O'zgertiw",
'editinguser'                 => "<b>$1</b> ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) paydalanıwshısının' huquqları o'zgertilmekte",
'userrights-editusergroup'    => "Paydalanıwshı toparların o'zgertiw",
'saveusergroups'              => 'Paydalanıwshı Toparların Saqlaw',
'userrights-groupsmember'     => "Ag'zalıq toparı:",
'userrights-reason'           => 'Sebep:',
'userrights-nodatabase'       => "$1 mag'lıwmatlar bazası ele jaratılmag'an yamasa jergilikli emes.",
'userrights-nologin'          => "Paydalanıwshılar huquqların belgilew ushın administrator akkauntı menen [[Special:UserLogin|kiriwin'iz]] kerek.",
'userrights-notallowed'       => "Sizin' akkauntın'ızda paydalanıwshılardın' huquqın belgilew imka'niyatı joq.",
'userrights-changeable-col'   => "O'zgerte alatug'ın toparların'ız",
'userrights-unchangeable-col' => "O'zgerte almaytug'ın toparların'ız",

# Groups
'group'               => 'Topar:',
'group-user'          => 'Paydalanıwshılar',
'group-autoconfirmed' => "O'zi tastıyıqlang'anlar",
'group-bot'           => 'Botlar',
'group-sysop'         => 'Administratorlar',
'group-bureaucrat'    => 'Byurokratlar',
'group-all'           => "(ha'mmesi)",

'group-user-member'          => 'paydalanıwshı',
'group-autoconfirmed-member' => "O'zi tastıyıqlang'an",
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Byurokrat',

'grouppage-user'          => '{{ns:project}}:Paydalanıwshılar',
'grouppage-autoconfirmed' => "{{ns:project}}:O'zi tastıyıqlang'an paydalanıwshılar",
'grouppage-bot'           => '{{ns:project}}:Botlar',
'grouppage-sysop'         => '{{ns:project}}:Administratorlar',
'grouppage-bureaucrat'    => '{{ns:project}}:Byurokratlar',

# Rights
'right-read'                 => 'Betlerdi oqıw',
'right-edit'                 => "Betlerdi o'zgertiw",
'right-createpage'           => "Sa'wbet betleri bolmag'an betlerdi jaratıw",
'right-createtalk'           => "Sa'wbet betlerin jaratıw",
'right-createaccount'        => "Jan'a paydalanıwshı akkauntın jaratıw",
'right-minoredit'            => "O'zgerislerdi kishi dep belgilew",
'right-move'                 => "Betlerdi ko'shiriw",
'right-move-subpages'        => "Betlerdi bag'ınıslıları menen birge ko'shiriw",
'right-move-rootuserpages'   => "Tiykarg'ı paydalanıwshı betlerin ko'shiriw",
'right-suppressredirect'     => "Betti ko'shirgende eski atamasınan qayta bag'ıtlawshı jaratpaw",
'right-upload'               => 'Fayllardı aploud qılıw',
'right-reupload'             => "Bar fayldın' u'stine jazıw",
'right-reupload-own'         => "O'zi aploud qılg'an fayl u'stine jazıw",
'right-reupload-shared'      => "Media repozitariy ortalıg'ındag'ı fayllardı jergilikli jazıw",
'right-upload_by_url'        => 'Fayldı URL adresinen aploud qılıw',
'right-autoconfirmed'        => "Yarım-qorg'alg'an betlerdi o'zgertiw",
'right-apihighlimits'        => 'API sorawlarında joqarı sheklewlerdi paydalanıw',
'right-writeapi'             => 'API jazıwın paydalanıw',
'right-delete'               => "Betlerdi o'shiriw",
'right-bigdelete'            => "Uzaq tariyxqa iye betlerdi o'shiriw",
'right-deleterevision'       => "Betlerdin' ayrıqsha nusqaların o'shiriw ha'm qayta tiklew",
'right-browsearchive'        => "O'shirilgen betlerdi izlew",
'right-undelete'             => 'Betti qayta tiklew',
'right-suppressrevision'     => "Administratorlardan jasırılg'an nusqalardı qayta ko'riw ha'm qayta tiklew",
'right-suppressionlog'       => 'Jeke jurnallardı qaraw',
'right-block'                => "Basqa paydalanıwshılardi o'zgertiwden bloklaw",
'right-blockemail'           => "Paydalanıwshının' xat jiberiw mu'mkinshiligin bloklaw",
'right-hideuser'             => "Ja'miyetten jasırg'an halda paydalanıwshı atın bloklaw",
'right-ipblock-exempt'       => "IP boyınsha bloklaw, avtomat bloklaw ha'mde diapazon bloklawların shetlep o'tiw",
'right-proxyunbannable'      => "Proksi serverlerdin' avtomat bloklawlarınan o'tiw",
'right-protect'              => "Qorg'aw da'relelerin o'zgertiw ha'm qorg'alg'an betlerdi o'zgertiw",
'right-editprotected'        => "Qorg'alg'an betlerdi o'zgertiw (kaskadlı qorg'awsız)",
'right-editinterface'        => "Paydalanıwshı interfeysin o'zgertiw",
'right-editusercssjs'        => "Basqa paydalanıwshılardın' CSS ha'm JS faylların o'zgertiw",
'right-editusercss'          => "Basqa paydalanıwshılardın' CSS faylların o'zgertiw",
'right-edituserjs'           => "Basqa paydalanıwshılardın' JS faylların o'zgertiw",
'right-markbotedits'         => "Qaytarılg'an o'zgerislerdi botlardiki dep belgilew",
'right-import'               => 'Basqa wikilerden betlerdi import qılıw',
'right-importupload'         => 'Fayldi aploud qılıw arqalı betlerdi import qılıw',
'right-unwatchedpages'       => "Baqlanbag'an betler dizimin qaraw",
'right-trackback'            => 'Trackbackti jiberiw',
'right-mergehistory'         => "Betlerdin' tariyxın birlestiriw",
'right-userrights'           => "Paydalanıwshılardın' barlıq huquqların o'zgertiw",
'right-userrights-interwiki' => "Basqa wikilerdegi paydalanıwshının' huquqların o'zgertiw",
'right-siteadmin'            => "Mag'lıwmatlar bazasın qulıplaw ha'm qulıplawın o'shiriw",

# User rights log
'rightslog'      => 'Paydalanıwshı huquqları jurnalı',
'rightslogtext'  => "Bul paydalanıwshı huquqların o'zgertiw jurnalı.",
'rightslogentry' => "$1 paydalanıwshısının' ag'za bolg'an toparları $2 degennen $3 degenge o'zgertti",
'rightsnone'     => '(hesh qanday)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'bul betti oqıw',
'action-edit'                 => "bul betti o'zgertiw",
'action-createpage'           => 'betlerdi jaratıw',
'action-createtalk'           => "Sa'wbet betlerin jaratıw",
'action-createaccount'        => 'bul paydalanıwshı akkauntın jaratıw',
'action-minoredit'            => "bul o'zgeristi kish dep esaplaw",
'action-move'                 => "bul betti ko'shiriw",
'action-move-subpages'        => "bul bet ha'm onın' bag'ınıslıların ko'shiriw",
'action-move-rootuserpages'   => "tiykarg'ı paydalanıwshı betlerin ko'shiriw",
'action-movefile'             => "bul betti ko'shiriw",
'action-upload'               => 'bul fayldı aploud qılıw',
'action-reupload'             => "usı fayldı u'stine jazıw",
'action-reupload-shared'      => "ortalıq repozitariyindegi usı fayldı u'stine jaz",
'action-upload_by_url'        => 'bul fayldı URL adresinen aploud qılıw',
'action-writeapi'             => "o'zgerisler ushın APIdı paydalanıw",
'action-delete'               => "bul betti o'shiriw",
'action-deleterevision'       => "bul nusqanı o'shiriw",
'action-deletedhistory'       => "bul bettin' o'shirilgen tariyxın ko'riw",
'action-browsearchive'        => "o'shirilgen betlerdi izlew",
'action-undelete'             => 'bul betti qayta tiklew',
'action-suppressrevision'     => "bul jasırın nusqanı qayta ko'riw ha'm qayta tiklew",
'action-suppressionlog'       => "bul jeke jurnaldı ko'riw",
'action-block'                => "bul paydalanıwshını o'zgerisler kiritiwden bloklaw",
'action-protect'              => "bul bettin' qorg'aw da'rejelerin o'zgertiw",
'action-import'               => 'bul betti basqa wikiden import qılıw',
'action-importupload'         => 'bul betti fayl aploudı arqalı aploud qılıw',
'action-unwatchedpages'       => "baqlanbaytug'ın betlerdin' dizimin ko'riw",
'action-trackback'            => '"trackback"tı jiberiw',
'action-mergehistory'         => "bul bettin' tariyxın birlestiriw",
'action-userrights'           => "paydalanıwshılardın barlıq huquqların o'zgertiw",
'action-userrights-interwiki' => "basqa wikilerdegi paydalanıwshılar huquqların o'zgertiw",
'action-siteadmin'            => "mag'lıwmatlar bazasın bloklaw yamasa bloklawdan shıg'arıw",

# Recent changes
'nchanges'                          => "{{PLURAL:$1|1 o'zgeris|$1 o'zgeris}}",
'recentchanges'                     => "Aqırg'ı o'zgerisler",
'recentchanges-legend'              => "Aqırg'ı o'zgerisler sazlawları",
'recentchangestext'                 => "Bul bette usı wikidegi ha'zirgi o'zgerisler baqlanadı.",
'recentchanges-feed-description'    => "Wikidin' usı ag'ımındag'ı en' aqırg'ı o'zgerislerin baqlaw.",
'recentchanges-label-newpage'       => "Bul o'zgeris arqalı taza bet jaratıldı",
'recentchanges-label-minor'         => "Bul kishi o'zgeris",
'recentchanges-label-bot'           => "Bul o'zgeristi bot kiritti",
'recentchanges-label-unpatrolled'   => "Bul o'zgeris ele baqlanbag'an",
'rcnote'                            => "To'mende $4, $5 waqtındag'ı aqırg'ı {{PLURAL:$2|ku'ndegi|'''$2''' ku'ndegi}} {{PLURAL:$1|'''1''' o'zgeris ko'rsetilgen|aqırg'ı '''$1''' o'zgeris ko'rsetilgen}}.",
'rcnotefrom'                        => "To'mende '''$2''' baslap '''$1''' shekemgi o'zgerisler ko'rsetilgen.",
'rclistfrom'                        => "$1 waqtınan baslap jan'a o'zgerisler ko'rset",
'rcshowhideminor'                   => "Kishi o'zgerislerdi $1",
'rcshowhidebots'                    => 'Botlardı $1',
'rcshowhideliu'                     => 'Kirgenlerdi $1',
'rcshowhideanons'                   => 'Anonim paydalanıwshılardı $1',
'rcshowhidepatr'                    => "Tekserilgen o'zgerislerdi $1",
'rcshowhidemine'                    => "O'zgertiwlerimdi $1",
'rclinks'                           => "Aqırg'ı $2 ku'ndegi aqırg'ı $1 o'zgeristi ko'rset<br />$3",
'diff'                              => 'parq',
'hist'                              => 'tar.',
'hide'                              => 'jasır',
'show'                              => "ko'rset",
'minoreditletter'                   => 'k',
'newpageletter'                     => 'T',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => "[Baqlag'an {{PLURAL:$1|1 paydalanıwshı|$1 paydalanıwshı}}]",
'rc_categories'                     => 'Kategoriyalarg\'a sheklew ("|" belgisi menen ajıratın\')',
'rc_categories_any'                 => "Ha'r qanday",
'newsectionsummary'                 => "/* $1 */ taza bo'lim",
'rc-enhanced-expand'                => "Tolıq mag'lıwmattı ko'rsetiw (JavaScriptti talap etedi)",
'rc-enhanced-hide'                  => "Tolıq mag'lıwmattı jasırıw",

# Recent changes linked
'recentchangeslinked'          => "Baylanıslı o'zgerisler",
'recentchangeslinked-feed'     => "Baylanıslı o'zgerisler",
'recentchangeslinked-toolbox'  => "Baylanıslı o'zgerisler",
'recentchangeslinked-title'    => '"$1" ushın baylanıslı o\'zgerisler',
'recentchangeslinked-noresult' => "Siltelgen betlerde berilgen waqıt dawamında hesh qanday o'zgeris bolmag'an.",
'recentchangeslinked-summary'  => "Bul arnawlı bette siltelgen betlerdegi aqırg'ı o'zgerisler dizimi ko'rsetilgen. [[Special:Watchlist|Baqlaw dizimin'izdegi]] betler '''juwan''' ha'ribi menen ko'rsetilgen.",
'recentchangeslinked-page'     => 'Bet ataması:',
'recentchangeslinked-to'       => "Kerisinshe, berilgen betke silteytug'ın betlerdegi o'zgerislerdi ko'rset",

# Upload
'upload'              => 'Fayldı aploud qılıw',
'uploadbtn'           => 'Aploud!',
'reuploaddesc'        => "Aploudtı biykar etiw ha'm aploud formasına qaytıw",
'uploadnologin'       => 'Kirilmegen',
'uploadnologintext'   => "Fayllardı aploud qılıw ushın [[Special:UserLogin|kiriwin'iz]] kerek.",
'uploaderror'         => "Aploud qa'tesi",
'upload-permitted'    => "Ruxsat etilgen fayl tu'rleri: $1.",
'upload-preferred'    => "Unamlı fayl tu'rleri: $1.",
'upload-prohibited'   => "Ruxsat etilmegen fayl tu'rleri: $1.",
'uploadlog'           => 'aploud jurnalı',
'uploadlogpage'       => 'Aploud jurnalı',
'filename'            => 'Fayl atı',
'filedesc'            => 'Juwmaq',
'fileuploadsummary'   => 'Juwmaq:',
'filestatus'          => 'Avtorlıq huqıqı statusı:',
'filesource'          => 'Fayl deregi:',
'uploadedfiles'       => "Aploud qılıng'an faillar",
'ignorewarning'       => 'Eskertiwlerdi esapqa almay fayldı saqla',
'ignorewarnings'      => 'Hesh qanday eskertiwdi esapqa alma',
'minlength1'          => "Fail atı keminde bir ha'ripten turıwı sha'rt.",
'badfilename'         => 'Fayl atı bug\'an o\'zgertildi: "$1".',
'filetype-badmime'    => '"$1" MIME tu\'rli fayllardı aploud qılıw ruxsat etilmeydi.',
'filetype-missing'    => 'Bul faildın ken\'eytpesi (mısalı ".jpg") joq.',
'largefileserver'     => "Bul faildın mo'lsheri serverdin' ruxsatınan u'lken.",
'uploadwarning'       => 'Aploud eskertiwi',
'savefile'            => 'Fayldı saqla',
'uploadedimage'       => '«[[$1]]» faylı aploud qılındı',
'overwroteimage'      => '"[[$1]]" faylının\' jan\'a nusqası aploud qılındı',
'uploaddisabled'      => 'Aploudqa ruxsat berilmegen',
'uploaddisabledtext'  => "Fayllardı aploud qılıw toqtatılg'an.",
'uploadvirus'         => "Bul failda virus bar! Mag'lıwmat: $1",
'sourcefilename'      => "Derektin' fayl atı:",
'destfilename'        => 'Belgilengen fail atı:',
'upload-maxfilesize'  => "Fayldın' maksimal ko'lemi: $1",
'watchthisupload'     => 'Bul fayldı baqlaw',
'upload-success-subj' => 'Tabıslı aploud',

'upload-proto-error' => 'Nadurıs protokol',
'upload-file-error'  => "İshki qa'telik",
'upload-misc-error'  => 'Belgisiz aploud qatesi',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => 'URL tabılmadı',
'upload-curl-error28' => 'Aploudqa berilgen waqıt pitti',

'license'            => 'Litsenziyalandırıwı:',
'license-header'     => 'Licenziyalandırıwı',
'nolicense'          => "Hesh na'rse saylanbag'an",
'license-nopreview'  => "(Aldınnan ko'riw imkaniyatı joq)",
'upload_source_url'  => " (jaramlı, ha'mmege ashıq URL)",
'upload_source_file' => " (sizin' kompyuterin'izdegi fayl)",

# Special:ListFiles
'listfiles_search_for'  => 'Media atamasın izlew:',
'imgfile'               => 'fayl',
'listfiles'             => 'Fayllar dizimi',
'listfiles_date'        => "Sa'ne",
'listfiles_name'        => 'Atama',
'listfiles_user'        => 'Paydalnıwshı',
'listfiles_size'        => "Ha'jim",
'listfiles_description' => 'Kommentariy',

# File description page
'file-anchor-link'          => 'Fayl beti',
'filehist'                  => 'Fayl tariyxı',
'filehist-help'             => "Aldın usı fayl qanday ko'riniste bolg'anın ko'riw ushin ku'n-ay/waqıt degendi basın'.",
'filehist-deleteall'        => "ha'mmesin o'shir",
'filehist-deleteone'        => "o'shiriw",
'filehist-revert'           => 'qaytarıw',
'filehist-current'          => "ha'zirgi",
'filehist-datetime'         => "Sa'ne/Waqıt",
'filehist-thumb'            => 'Miniatyurası',
'filehist-thumbtext'        => "$1 waqtındag'ı nusqanın' miniatyurası",
'filehist-nothumb'          => 'Miniatyurası joq',
'filehist-user'             => 'Paydalanıwshı',
'filehist-dimensions'       => "O'lshemleri",
'filehist-filesize'         => "Fayldın' ha'jmi",
'filehist-comment'          => 'Kommentariy',
'imagelinks'                => 'Fayl siltewleri',
'linkstoimage'              => "To'mendegi {{PLURAL:$1|bet|$1 bet}} bul faylg'a siltelgen:",
'nolinkstoimage'            => "Bul faylg'a hesh bir bet siltelmegen.",
'sharedupload'              => '$1 ortalıq faylı basqa proektlerde paydalanılsa boladı.',
'uploadnewversion-linktext' => "Bul fayldın' jan'a nusqasın aploud qılıw",

# File reversion
'filerevert'                => '$1 degendi qaytarıw',
'filerevert-legend'         => 'Fayldı qaytarıw',
'filerevert-comment'        => 'Sebep:',
'filerevert-defaultcomment' => "$2, $1 waqtındag'ı nusqasına qaytarıldı",
'filerevert-submit'         => 'Qaytar',

# File deletion
'filedelete'                  => "$1 degendi o'shiriw",
'filedelete-legend'           => "Fayldı o'shiriw",
'filedelete-intro'            => "Siz '''[[Media:$1|$1]]''' faylın tariyxı menen birgelikte o'shirilmektesiz.",
'filedelete-intro-old'        => "[$4 $3, $2] waqtındag'ı '''[[Media:$1|$1]]''' nusqası o'shirilmekte.",
'filedelete-comment'          => 'Sebep:',
'filedelete-submit'           => "O'shiriw",
'filedelete-success'          => "'''$1''' o'shirildi.",
'filedelete-success-old'      => "$3, $2 waqtındag'ı '''[[Media:$1|$1]]''' nusqası o'shirildi.",
'filedelete-nofile'           => "'''$1''' haqqında mag'lıwmat joq.",
'filedelete-otherreason'      => 'Basqa/qosımsha sebep:',
'filedelete-reason-otherlist' => 'Basqa sebep',
'filedelete-edit-reasonlist'  => "O'shiriw sebeplerin o'zgertiw",

# MIME search
'mimesearch' => 'MIME izlew',
'mimetype'   => "MIME tu'ri:",
'download'   => 'koshirip alıw',

# Unwatched pages
'unwatchedpages' => "Baqlanbag'an betler",

# List redirects
'listredirects' => 'Burıwshılar dizimi',

# Unused templates
'unusedtemplates'    => "Paydalanılmag'an shablonlar",
'unusedtemplateswlh' => 'basqa burıwshılar',

# Random page
'randompage'         => "Qa'legen bet",
'randompage-nopages' => "To'mendegi {{PLURAL:$2|isimler ko'pliginde|isimler ko'pliginde}} hesh bet joq: $1",

# Random redirect
'randomredirect'         => "Qa'legen burıwshı",
'randomredirect-nopages' => '"$1" isimler ko\'pliginde qayta bag\'ıtlang\'an betler joq.',

# Statistics
'statistics'               => 'Statistika',
'statistics-header-pages'  => 'Bet statistikası',
'statistics-header-edits'  => "Statistikanı o'zgertiw",
'statistics-header-views'  => "Statistikanı ko'riw",
'statistics-header-users'  => 'Paydalanıwshı statistikası',
'statistics-articles'      => 'Maqalalar',
'statistics-pages'         => 'Betler',
'statistics-pages-desc'    => "Wikidegi barlıw betler, sawbet, qayta bag'itlang'an h.t.b. betlerin qosqanda.",
'statistics-files'         => "Aploud qılıng'an fayllar",
'statistics-edits'         => "{{SITENAME}} ornatılg'anlı beri bettegi o'zgerisler sanı",
'statistics-edits-average' => "Ha'r bir betke ortasha kiritilgen o'zgerisler sanı",
'statistics-views-total'   => "Barlıq ko'riwler",
'statistics-views-peredit' => "Ha'r bir o'zgeriske ko'riwler sanı",
'statistics-users'         => "Registratsiyadan o'tken [[Special:ListUsers|paydalanıwshılar]]",
'statistics-users-active'  => 'Aktiv paydalanıwshılar',
'statistics-mostpopular'   => "En' ko'p ko'rilgen betler",

'disambiguations'     => "Ko'p ma'nisli betler",
'disambiguationspage' => '{{ns:template}}:disambig',

'doubleredirects'       => 'Qos burıwshılar',
'double-redirect-fixer' => "Qayta bag'ıtlawshılardı du'zetiwshi",

'brokenredirects'        => "Hesh betke bag'ıtlamaytug'ın burıwshılar",
'brokenredirectstext'    => "To'mendegi qayta bag'ıtlawshı betler ha'zirgi waqıtta joq betlerge silteydi:",
'brokenredirects-edit'   => "o'zgertiw",
'brokenredirects-delete' => "o'shiriw",

'withoutinterwiki'         => "Hesh tilge siltemeytug'ın betler",
'withoutinterwiki-summary' => "To'mendegi betler basqa tillerdegi nusqalarına siltemeydi.",
'withoutinterwiki-legend'  => 'Prefiks:',
'withoutinterwiki-submit'  => "Ko'rset",

'fewestrevisions' => "En' az du'zetilgen betler",

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|1 bayt|$1 bayt}}',
'ncategories'             => '{{PLURAL:$1|1 kategoriya|$1 kategoriya}}',
'nlinks'                  => '{{PLURAL:$1|1 siltew|$1 siltew}}',
'nmembers'                => "{{PLURAL:$1|1 ag'za|$1 ag'zalar}}",
'nrevisions'              => '{{PLURAL:$1|1 nusqa|$1 nusqa}}',
'nviews'                  => "{{PLURAL:$1|1 ma'rte|$1 ma'rte}} ko'rip shıg'ılg'an",
'specialpage-empty'       => "Bul sorawg'a hesh qanday na'tiyje joq.",
'lonelypages'             => 'Hesh betten siltelmegen betler',
'lonelypagestext'         => "To'mendegi betlerge {{SITENAME}} proektindegi basqa betler siltemeydi.",
'uncategorizedpages'      => 'Kategoriyasız betler',
'uncategorizedcategories' => 'Kategoriyasız kategoriyalar',
'uncategorizedimages'     => 'Kategoriyasız fayllar',
'uncategorizedtemplates'  => 'Kategoriyasız shablonlar',
'unusedcategories'        => "Paydalanılmag'an kategoriyalar",
'unusedimages'            => "Paydalanılmag'an fayllar",
'popularpages'            => "En' ko'p ko'rilgen betler",
'wantedcategories'        => "Talap qılıng'an kategoriyalar",
'wantedpages'             => "Talap qılıng'an betler",
'wantedfiles'             => 'Kerekli fayllar',
'wantedtemplates'         => 'Kerekli shablonlar',
'mostlinked'              => "En' ko'p siltelgen betler",
'mostlinkedcategories'    => "En' ko'p paydalanılg'an kategoriyalar",
'mostlinkedtemplates'     => "En' ko'p paydalanılg'an shablonlar",
'mostcategories'          => "En' ko'p kategoriyalang'an betler",
'mostimages'              => "En' ko'p paydalanılg'an fayllar",
'mostrevisions'           => "En' ko'p du'zetilgen betler",
'prefixindex'             => 'Prefiksi bar barlıq betler',
'shortpages'              => "En' qısqa betler",
'longpages'               => "En' uzın betler",
'deadendpages'            => "Hesh betke siltemeytug'ın betler",
'deadendpagestext'        => "To'mendegi betler {{SITENAME}} proyektindegi basqa betlerge siltelmegen.",
'protectedpages'          => "Qorg'alg'an betler",
'protectedpages-indef'    => "Tek belgisiz qorg'awlar",
'protectedpages-cascade'  => "Tek kaskadlı qorg'awlar",
'protectedpagestext'      => "To'mendegi betler ko'shiriw ha'm o'zgertiwden qorg'alg'an",
'protectedpagesempty'     => "Usı parametrler menen ha'zir hesh bet qorg'almag'an",
'protectedtitles'         => "Qorg'alg'an atamalar",
'protectedtitlestext'     => "To'mendegi atamalar jaratılıwdan qorg'alg'an",
'listusers'               => 'Paydalanıwshı dizimi',
'listusers-editsonly'     => "Tek o'zgeris kiritken paydalanıwshılardı ko'rset",
'usereditcount'           => "$1 {{PLURAL:$1|o'zgeris|o'zgeris}}",
'newpages'                => "En' taza betler",
'newpages-username'       => 'Paydalanıwshı atı:',
'ancientpages'            => "En' eski betler",
'move'                    => "Ko'shiriw",
'movethispage'            => "Bul betti ko'shiriw",
'notargettitle'           => "Nıshan ko'rsetilmegen",
'pager-newer-n'           => "{{PLURAL:$1|jan'alaw 1|jan'alaw $1}}",
'pager-older-n'           => '{{PLURAL:$1|eskilew 1|eskilew $1}}',

# Book sources
'booksources'               => 'Kitap derekleri',
'booksources-search-legend' => 'Kitap haqqında informatsiya izlew',
'booksources-go'            => "O'tin'",

# Special:Log
'specialloguserlabel'  => 'Paydalanıwshı:',
'speciallogtitlelabel' => 'Atama:',
'log'                  => 'Jurnallar',
'all-logs-page'        => 'Barlıq ashıq jurnallar',
'log-title-wildcard'   => "Usı tekstten baslang'an atamalardı izlew",

# Special:AllPages
'allpages'          => "Ha'mme betler",
'alphaindexline'    => '$1 — $2',
'nextpage'          => 'Keyingi bet ($1)',
'prevpage'          => "Aldıng'ı bet ($1)",
'allpagesfrom'      => "Mına betten baslap ko'rsetiw:",
'allpagesto'        => "Usı betke shekem ko'rsetiw:",
'allarticles'       => "Ha'mme betler",
'allinnamespace'    => "Ha'mme betler ($1 isimler ko'pligi)",
'allnotinnamespace' => "Ha'mme betler ($1 isimler ko'pliginen emes)",
'allpagesprev'      => "Aldıng'ı",
'allpagesnext'      => 'Keyingi',
'allpagessubmit'    => "O'tin'",
'allpagesprefix'    => "Mına prefiksten baslag'an betlerdi ko'rsetiw:",
'allpages-bad-ns'   => '{{SITENAME}} proyektinde "$1"  isimler ko\'pligi joq.',

# Special:Categories
'categories'                    => 'Kategoriyalar',
'categoriespagetext'            => "To'mendegi {{PLURAL:$1|kategoriya|kategoriyalar}} o'z ishine betler yamasa medialardı alg'an.
Bul jerde [[Special:UnusedCategories|paydalanılmag'an kategoriyalar]] ko'rsetilmegen.
Ja'nede [[Special:WantedCategories|kerekli kategoriyalardı]] qarap ko'rin'.",
'categoriesfrom'                => "Kategoriyalardı to'mendegilerden baslap ko'rset:",
'special-categories-sort-count' => "sanı boyınsha ta'rtiplew",
'special-categories-sort-abc'   => "a'lipbe boyınsha ta'rtiplew",

# Special:DeletedContributions
'deletedcontributions' => "Paydalanıwshının' o'shiriw u'lesi",

# Special:LinkSearch
'linksearch'      => 'Sırtqı siltewler',
'linksearch-pat'  => 'İzlew shablonı:',
'linksearch-ns'   => "İsimler ko'pligi:",
'linksearch-ok'   => 'İzlew',
'linksearch-line' => '$2 degennen $1 siltegen',

# Special:ListUsers
'listusersfrom'      => "Mına paydalanıwshıdan baslap ko'rsetiw:",
'listusers-submit'   => "Ko'rset",
'listusers-noresult' => 'Paydalanıwshı tabılmadı.',

# Special:Log/newusers
'newuserlogpage'              => 'Paydalanıwshılardı esapqa alıw jurnalı',
'newuserlogpagetext'          => 'Bul paydalanıwshılardı esapqa alıw jurnalı',
'newuserlog-byemail'          => 'parol e-mail arqalı jiberildi',
'newuserlog-create-entry'     => "Jan'a paydalanıwshı",
'newuserlog-create2-entry'    => "jan'a akkaunt jarattı $1",
'newuserlog-autocreate-entry' => 'Akkaunt avtomatik halda jaratıldı',

# Special:ListGroupRights
'listgrouprights'                 => 'Paydalanıwshılar toparı huqıqları',
'listgrouprights-group'           => 'Topar',
'listgrouprights-rights'          => 'Huquqları',
'listgrouprights-helppage'        => 'Help:Topar huquqları',
'listgrouprights-members'         => "(ag'zalar dizimi)",
'listgrouprights-addgroup'        => "$1 {{PLURAL:$2|toparın|toparların}} qosıwı mu'mkin",
'listgrouprights-removegroup'     => "$1 {{PLURAL:$2|toparın|toparların}} o'shiriwi mu'mkin",
'listgrouprights-addgroup-all'    => "Barlıq toparlardı qosıwı mu'mkin",
'listgrouprights-removegroup-all' => "Barlıq toparlardı o'shiriwi mu'mkin",

# E-mail user
'mailnologin'     => 'Jiberiwge adres tabılmadı',
'emailuser'       => 'Xat jiberiw',
'emailpage'       => "Paydalanıwshıg'a e-mail jiberiw",
'defemailsubject' => '{{SITENAME}} e-mail',
'noemailtitle'    => 'E-mail adresi joq',
'email-legend'    => 'Basqa {{SITENAME}} paydalanıwshısına xat jiberiw',
'emailfrom'       => 'Kimnen:',
'emailto'         => 'Kimge:',
'emailsubject'    => 'Teması:',
'emailmessage'    => 'Xat:',
'emailsend'       => 'Jiber',
'emailccme'       => "Menin' xabarımnın' ko'shirmesin e-mailımg'a jiber.",
'emailsent'       => 'Xat jiberildi',
'emailsenttext'   => "E-mail xatın'ız jiberildi.",

# Watchlist
'watchlist'         => 'Betlerdi baqlaw dizimi',
'mywatchlist'       => "Menin' baqlaw dizimim",
'nowatchlist'       => "Baqlaw dizimin'iz bos.",
'watchlistanontext' => "Baqlaw dizimin'izdegilerdi qaraw yamasa o'zgertiw ushın $1 kerek.",
'watchnologin'      => 'Kirilmegen',
'watchnologintext'  => "Baqlaw dizimin'izdi o'zgertiw ushın [[Special:UserLogin|kiriwin'iz]] kerek.",
'addedwatch'        => 'Baqlaw dizimine qosıldı',
'addedwatchtext'    => "\"[[:\$1]]\" beti [[Special:Watchlist|baqlaw dizimin'izge]] qosıldı.
Usı ha'm og'an baylanıslı bolg'an sa'wbet betlerinde bolatug'ın keleshektegi o'zgerisler usı dizimde ko'rsetiledi ha'mde betti tabıwdı an'satlastırıw ushın [[Special:RecentChanges|taza o'zgerisler diziminde]] '''juwan ha'ripte''' ko'rsetiledi.
Eger siz bul betti baqlaw dizimin'izden o'shirmekshi bolsan'ız bettin' joqarg'ı on' jag'ındag'ı \"Baqlamaw\" jazıwın basın'.",
'removedwatch'      => "Baqlaw diziminen o'shirildi",
'removedwatchtext'  => '"[[:$1]]" beti [[Special:Watchlist|sizin\' baqlaw dizimin\'izden]] o\'shirildi.',
'watch'             => 'Baqlaw',
'watchthispage'     => 'Bul betti baqlaw',
'unwatch'           => 'Baqlamaw',
'unwatchthispage'   => 'Baqlawdı toqtatıw',
'notanarticle'      => "Mag'lıwmat beti emes",
'notvisiblerev'     => "Nusqa o'shirildi",
'watchlist-details' => "Baqlaw dizimin'izde (sa'wbet betlerin esapqa almag'anda) {{PLURAL:$1|1 bet|$1 bet}} bar.",
'wlheader-enotif'   => "* E-mail arqalı eskertiw qosılg'an.",
'watchlistcontains' => "Sizin' baqlaw dizimin'izde {{PLURAL:$1|1 bet|$1 bet}} bar.",
'wlnote'            => "To'mende aqırg'ı {{PLURAL:$2|saattag'ı|'''$2''' saattag'ı}} {{PLURAL:$1|aqırg'ı o'zgeris bar|aqırg'ı '''$1''' o'zgeris bar}}.",
'wlshowlast'        => "Aqırg'ı $1 saat, $2 ku'n, $3 ko'rset",
'watchlist-options' => "Baqlaw diziminin' sazlawları",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Baqlaw...',
'unwatching' => 'Baqlamaw...',

'enotif_mailer'                => '{{SITENAME}} eskertiw xatın jiberiw xızmeti',
'enotif_reset'                 => "Barlıq betti ko'rip shıg'ıldı dep belgile",
'enotif_newpagetext'           => 'Bul taza bet.',
'enotif_impersonal_salutation' => '{{SITENAME}} paydalanıwshısı',
'changed'                      => "o'zgertilgen",
'created'                      => "jaratılg'an",
'enotif_subject'               => '{{SITENAME}} proektindegi $PAGEEDITOR $PAGETITLE atamalı betti $CHANGEDORCREATED',
'enotif_lastvisited'           => "Son'g'ı kirwin'izden beri bolg'an o'zgerisler ushın $1 degendi ko'rin'iz.",
'enotif_lastdiff'              => "Usı o'zgeris ushın $1 degendi ko'rin'iz.",
'enotif_anon_editor'           => 'anonim paydalanıwshı $1',

# Delete
'deletepage'            => "Betti o'shir",
'confirm'               => 'Tastıyıqlaw',
'excontent'             => "bolg'an mag'lıwmat: '$1'",
'excontentauthor'       => "bolg'an mag'lıwmat: '$1' (tek '[[Special:Contributions/$2|$2]]' u'lesi)",
'exblank'               => 'bet bos edi',
'delete-confirm'        => '"$1" o\'shiriw',
'delete-legend'         => "O'shiriw",
'historywarning'        => "'''Esletpe:''' Siz o'shireyin dep atırg'an betin'iz shama menen $1 {{PLURAL:$1|nusqanı|nusqanı}} o'z ishine alg'an tariyxı bar:",
'confirmdeletetext'     => "Siz bul betti yamasa su'wretti barliq tariyxı menen mag'lıwmatlar bazasınan o'shirejaqsız.
Bunın' aqıbetin tu'singenin'izdi ha'm [[{{MediaWiki:Policy-url}}]] siyasatına ılayıqlı ekenligin tastıyıqlan'.",
'actioncomplete'        => "Ha'reket tamamlandı",
'deletedtext'           => "\"<nowiki>\$1</nowiki>\" o'shirildi.
Aqırg'ı o'shirilgenlerdin' dizimin ko'riw ushin \$2 ni qaran'",
'deletedarticle'        => '"[[$1]]" o\'shirildi',
'dellogpage'            => "O'shiriw jurnalı",
'dellogpagetext'        => "To'mende en' aqırg'ı o'shirilgenlerdin' dizimi keltirilgen",
'deletionlog'           => "o'shiriw jurnalı",
'reverted'              => "Aldınraqtag'ı nusqasına qaytarılg'an",
'deletecomment'         => 'Sebep:',
'deleteotherreason'     => 'Basqa/qosımsha sebep:',
'deletereasonotherlist' => 'Basqa sebep',

# Rollback
'rollback'       => "O'zgerislerdi biykar etiw",
'rollback_short' => 'Biykar etiw',
'rollbacklink'   => 'qaytarıw',
'rollbackfailed' => "Biykar etiw sa'tsiz tamamlandı",
'editcomment'    => "O'zgertiwge qaldırılg'an kommentariy: \"''\$1''\".",

# Protect
'protectlogpage'              => "Qorg'aw jurnalı",
'protectedarticle'            => '"[[$1]]" qorg\'alg\'an',
'modifiedarticleprotection'   => '"[[$1]]" betinin\' qorg\'aw da\'rejesi ozgertildi',
'unprotectedarticle'          => '"[[$1]]" qorg\'almag\'an',
'protect-legend'              => "Qorg'awdı tastıyıqlaw",
'protectcomment'              => 'Sebep:',
'protectexpiry'               => "Ku'shin joytıw waqtı:",
'protect_expiry_invalid'      => "Nadurıs ku'shin joytıw waqtı.",
'protect_expiry_old'          => "Kushin joytıw waqtı o'tip ketken.",
'protect-text'                => "'''<nowiki>$1</nowiki>''' betinin' qorg'aw da'rejesin ko're yamasa o'zgerte alasız.",
'protect-locked-access'       => "Akkauntın'ızdın' bettın' qorg'aw da'rejesin o'zgertiwge ruxsatı joq.
'''$1''' betinin' ha'zirgi sazlawları:",
'protect-cascadeon'           => "Bul bet ha'zirgi waqıtta qorg'alg'an, sebebi usı bet kaskadlı qorg'awı bar {{PLURAL:$1|betke|betlerine}} qosılg'an. Bul bettin' qorg'aw da'rejesin o'zgerte alasız, biraq bul kaskadlı qorg'awg'a ta'sir etpeydi.",
'protect-default'             => "Barlıq paydalanıwshılarg'a ruxsat etilgen",
'protect-fallback'            => '"$1" ruxsatı kerek',
'protect-level-autoconfirmed' => "Taza ha'm dizimnen o'tpegen paydalanıwshılardı bloklaw",
'protect-level-sysop'         => 'Tek administratorlar',
'protect-summary-cascade'     => "kaskadlang'an",
'protect-expiring'            => 'pitiw waqtı: $1 (UTC)',
'protect-expiry-indefinite'   => "ma'ngi",
'protect-cascade'             => "Bul betke qosılg'an betlerdi qorg'aw (kaskadlı qorg'aw).",
'protect-cantedit'            => "Bul bettin' qorg'aw da'rejesin o'zgerte almaysız, sebebi oni o'zgertiwge sizin' ruxsatın'ız joq.",
'protect-othertime'           => 'Basqa waqıt:',
'protect-othertime-op'        => 'basqa waqıt',
'protect-otherreason'         => 'Basqa/qosımsha sebep:',
'protect-otherreason-op'      => 'Basqa sebep',
'protect-edit-reasonlist'     => "Qorg'aw sebeplerin o'zgertiw",
'protect-expiry-options'      => "1 saat:1 hour,1 ku'n:1 day,1 ha'pte:1 week,2 ha'pte:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 jıl:1 year,ma'ngi:infinite",
'restriction-type'            => 'Ruxsatnama:',
'restriction-level'           => "Sheklew da'rejesi:",
'minimum-size'                => "En' az o'lshemi",
'maximum-size'                => "En' ko'p o'lshemi:",
'pagesize'                    => '(bayt)',

# Restrictions (nouns)
'restriction-edit'   => "O'zgertiw",
'restriction-move'   => "Ko'shiriw",
'restriction-create' => 'Jaratıw',
'restriction-upload' => 'Aploud qılıw',

# Restriction levels
'restriction-level-sysop'         => "tolıq qorg'alg'an",
'restriction-level-autoconfirmed' => "yarım-qorg'alg'an",
'restriction-level-all'           => "ha'mme basqısh",

# Undelete
'undelete'                 => "O'shirilgen betlerdi ko'riw",
'undeletepage'             => "O'shirilgen betlerdi ko'riw ha'm qayta tiklew",
'viewdeletedpage'          => "O'shirilgen betlerdi ko'riw",
'undelete-revision'        => "$1 betinin' $4, $5 waqtındag'ı $3 paydalanıwshı ta'repinen o'shirilgen nusqası:",
'undelete-nodiff'          => "Hesh aldıng'ı nusqa tabılmadı.",
'undeletebtn'              => 'Qayta tiklew',
'undeletelink'             => "ko'riw/qayta tiklew",
'undeletecomment'          => 'Sebep:',
'undeletedarticle'         => '"[[$1]]" qayta tiklendi',
'undeletedrevisions'       => '{{PLURAL:$1|1 nusqa|$1 nusqa}} qayta tiklendi',
'undeletedrevisions-files' => "{{PLURAL:$1|1 nusqa|$1 nusqa}} ha'm {{PLURAL:$2|1 fayl|$2 fayl}} qayta tiklendi",
'undeletedfiles'           => '{{PLURAL:$1|1 fayl|$1 fayl}} qayta tiklendi',
'undelete-search-box'      => "O'shirilgen betlerdi izlew",
'undelete-search-prefix'   => "Mınadan baslag'an betlerdi ko'rsetiw:",
'undelete-search-submit'   => 'İzle',
'undelete-error-short'     => "Faildı tilkewde qa'telik: $1",

# Namespace form on various pages
'namespace'      => "İsimler ko'pligi:",
'invert'         => "Saylaw ta'rtibin almastırıw",
'blanknamespace' => '(Baslı)',

# Contributions
'contributions'       => "Paydalanıwshı u'lesi",
'contributions-title' => "$1 paydalanıwshısının' qosqan u'lesi",
'mycontris'           => "Menin' u'lesim",
'contribsub2'         => '$1 ushın ($2)',
'uctop'               => "(joqarg'ı)",
'month'               => "Aydag'ı (ha'm onnanda erterek):",
'year'                => "Jıldag'ı (ha'm onnanda erterek):",

'sp-contributions-newbies'     => "Tek taza akkauntlar u'leslerin ko'rset",
'sp-contributions-newbies-sub' => 'Taza akkauntlar ushın',
'sp-contributions-blocklog'    => 'Bloklaw jurnalı',
'sp-contributions-userrights'  => 'paydalanıwshı huqıqların basqarıw',
'sp-contributions-search'      => "U'lesi boyınsha izlew",
'sp-contributions-username'    => 'IP Adres yamasa paydalanıwshı atı:',
'sp-contributions-submit'      => 'İzle',

# What links here
'whatlinkshere'            => 'Siltelgen betler',
'whatlinkshere-title'      => '"$1" betine siltelgen betler',
'whatlinkshere-page'       => 'Bet:',
'linkshere'                => "To'mendegi betler mınag'an siltelgen: '''[[:$1]]''':",
'nolinkshere'              => "'''[[:$1]]''' degenge hesh bet siltemeydi.",
'isredirect'               => 'burıwshı bet',
'istemplate'               => 'qosıw',
'isimage'                  => "su'wret siltewi",
'whatlinkshere-prev'       => "{{PLURAL:$1|aldıng'ı|aldıng'ı $1}}",
'whatlinkshere-next'       => '{{PLURAL:$1|keyingi|keyingi $1}}',
'whatlinkshere-links'      => '← siltewler',
'whatlinkshere-hideredirs' => "qayta bag'ıtlawshılardı $1",
'whatlinkshere-hidetrans'  => "qosılg'anlardı $1",
'whatlinkshere-hidelinks'  => 'siltewlerdi $1',
'whatlinkshere-hideimages' => "su'wret siltewlerin $1",
'whatlinkshere-filters'    => 'Filtrler',

# Block/unblock
'blockip'                     => 'Paydalanıwshını bloklaw',
'blockip-legend'              => 'Paydalanıwshını bloklaw',
'ipaddress'                   => 'IP Adres:',
'ipadressorusername'          => 'IP Adres yamasa paydalanıwshı atı:',
'ipbexpiry'                   => "Ku'shin joytıw waqtı:",
'ipbreason'                   => 'Sebep:',
'ipbreasonotherlist'          => 'Basqa sebep',
'ipbanononly'                 => 'Tek anonim paydalanıwshılardı bloklaw',
'ipbcreateaccount'            => "Akkaunt jaratıwdı qadag'an etiw",
'ipbemailban'                 => "Paydalanıwshını e-mail jiberiwden qadag'alaw",
'ipbsubmit'                   => 'Bul paydalanıwshını bloklaw',
'ipbother'                    => 'Basqa waqıt:',
'ipboptions'                  => "2 saat:2 hours,1 ku'n:1 day,3 ku'n:3 days,1 ha'pte:1 week,2 h'apte:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 jil:1 year,sheksiz:infinite",
'ipbotheroption'              => 'basqa',
'ipbotherreason'              => 'Basqa/qosımsha sebep:',
'badipaddress'                => 'Jaramsız IP adres',
'blockipsuccesssub'           => 'Tabıslı qulplaw',
'blockipsuccesstext'          => "[[Special:Contributions/$1|$1]] bloklang'an.<br />
Basqa bloklawlar ushın [[Special:IPBlockList|IP bloklaw dizimin]] ko'rip shıg'ın'iz.",
'ipb-edit-dropdown'           => "Bloklaw sebeplerin o'zgertiw",
'ipb-unblock-addr'            => '$1 degennin qulpın sheshiw',
'ipb-unblock'                 => "Paydalanıwshının' yamasa IP adrestin' qulpın shesh",
'unblockip'                   => "Paydalanıwshının' qulpın sheshiw",
'ipusubmit'                   => 'Bul bloklawdı biykar etiw',
'unblocked-id'                => "$1 bloklawı o'shirildi",
'ipblocklist'                 => "Bloklang'an IP adresler ha'm paydalanıwshılar dizimi",
'ipblocklist-legend'          => "Bloklang'an paydalanıwshını tabıw",
'ipblocklist-username'        => 'Paydalanıwshı atı yamasa IP adres:',
'ipblocklist-submit'          => 'İzle',
'blocklistline'               => '$1, $2 waqıtında $3 blokladı ($4)',
'infiniteblock'               => 'sheksiz',
'expiringblock'               => "ku'shin joytıw waqtı: $1 $2",
'anononlyblock'               => 'tek anon.',
'noautoblockblock'            => "avtoqulplaw o'shirilgen",
'createaccountblock'          => "Akkaunt jaratıw qadag'alang'an",
'emailblock'                  => "e-mail bloklang'an",
'ipblocklist-empty'           => 'Bloklaw dizimi bos.',
'blocklink'                   => 'bloklaw',
'unblocklink'                 => 'bloklamaw',
'change-blocklink'            => "bloklawdı o'zgertiw",
'contribslink'                => "u'lesi",
'blocklogpage'                => 'Bloklaw jurnalı',
'blocklogentry'               => "[[$1]] $2 waqıt aralıg'ına bloklandı $3",
'unblocklogentry'             => "$1 bloklawdı o'shirdi",
'block-log-flags-anononly'    => 'tek anonim paydalanıwshılar',
'block-log-flags-nocreate'    => "Akkaunt jaratıw o'shirilgen",
'block-log-flags-noautoblock' => "Avtoqulplaw o'shirilgen",
'block-log-flags-noemail'     => "e-mail bloklang'an",
'ipb_expiry_invalid'          => "Ku'shin joytıw waqtı nadurıs.",
'ipb_already_blocked'         => '"$1" a\'lle qashan bloklang\'an',
'proxyblocker-disabled'       => "Bul funktsiya o'shirilgen.",
'proxyblocksuccess'           => 'Tamamlandı.',

# Developer tools
'lockdb'              => "Mag'lıwmatlar bazasın qulpla",
'unlockdb'            => "Mag'lıwmatlar bazasının' qulpın shesh",
'lockconfirm'         => "Awa, men mag'lıwmatlar bazasın qulplayman.",
'unlockconfirm'       => "Awa, men mag'lıwmatlar bazasının' qulpın sheshemen.",
'lockbtn'             => "Mag'lıwmatlar bazasın qulpla",
'unlockbtn'           => "Mag'lıwmatlar bazasının' qulpın shesh",
'locknoconfirm'       => "Tastıyıqlaw belgisin qoymadın'ız.",
'lockdbsuccesssub'    => "Mag'lıwmatlar bazasın qulplaw tabıslı tamamlandı",
'unlockdbsuccesssub'  => "Mag'lıwmatlar bazasının' qulpı sheshildi",
'unlockdbsuccesstext' => "Mag'lıwmatlar bazasının' qulpı sheshildi",
'databasenotlocked'   => "Mag'lıwmatlar bazası qulplanbag'an",

# Move page
'move-page-legend'        => "Betti ko'shiriw",
'movepagetext'            => "To'mendegi formanı qollanıw arqalı bettin' ataması o'zgeredi, onın' barlıq tariyxı da taza atamag'a ko'shiredi.
Burıng'ı bet ataması taza atamag'a qayta bag'ıtlang'an bet bolıp qaladı.
Original atamag'a siltewshi qayta bag'ıtlang'an betlerdi avtomatik halda o'zgertiwin'iz mu'mkin.
Eger buni qa'lemesen'iz, [[Special:DoubleRedirects|shınjırlı]] yamasa [[Special:BrokenRedirects|natuwrı qayta bag'ıtlang'an betlerdin']] bar-joqlıg'ınj tekserip ko'rin'.
Siltewlerdin' tuwrı islewine siz juwapker bolasız.

Itibar berin': eger taza atamalı bet aldınnan bar bolsa ha'm son'g'ı o'zgertiw tariyxısız bos bet yamasa qayta bag'ıtlandırıwshı bolg'anına deyin bet '''ko'shirilmeydi'''.
Bul degeni, eger betti aljasıp qayta atag'an bolsan'ız aldıng'ı atamag'a qaytıwın'ızg'a boladı, biraq bar bettin' u'stine jazıwın'ızg'a bolmaydi.

'''ESTE TUTIN'!'''
Bul ko'p qaralatug'ın betke qatan' ha'm ku'tilmegen o'zgerisler alıp keliwi mu'mkin;
dawam ettiriwden aldın qanday aqıbetlerge alıp keliwin oylap ko'rin'.",
'movepagetalktext'        => "To'mendegi sebepler bar '''bolg'anısha''', sa'wbet beti avtomatik halda ko'shiriledi:
* Bos emes sa'wbet beti jan'a atamada bar bolg'anda yaki
* To'mendegi qutını belgilemegen'izde.

Bul jag'daylarda eger qa'lesen'iz betti qoldan ko'shiriwin'iz yamasa qosıwın'izg'a boladı.",
'movearticle'             => "Ko'shiriletug'ın bet:",
'movenologin'             => 'Kirilmegen',
'newtitle'                => 'Taza atama:',
'move-watch'              => 'Bul betti baqlaw',
'movepagebtn'             => "Betti ko'shir",
'pagemovedsub'            => "Tabıslı ko'shirildi",
'movepage-moved'          => "'''\"\$1\" beti \"\$2\" betine ko'shirildi'''",
'articleexists'           => "Bunday atamalı bet bar yamasa natuwrı atama sayladın'ız.
Basqa atama saylan'",
'talkexists'              => "'''Bettin' o'zi a'wmetli ko'shirildi, biraq sa'wbet beti ko'shirilmedi sebebi jan'a atamanın' sa'wbet beti bar eken. Olardı o'zin'iz qoldan qosın'.'''",
'movedto'                 => "betke ko'shirildi",
'movetalk'                => "Baylanıslı sa'wbet betin ko'shiriw",
'1movedto2'               => "[[$1]] beti [[$2]] degenge ko'shirildi",
'1movedto2_redir'         => "[[$1]] beti [[$2]] degen burıwshıg'a ko'shirildi",
'movelogpage'             => "Ko'shiriw jurnalı",
'movelogpagetext'         => "To'mende ko'shirilgen betlerdin' dizimi keltirilgen.",
'movereason'              => 'Sebep:',
'revertmove'              => 'qaytarıw',
'delete_and_move'         => "O'shiriw ha'm ko'shiriw",
'delete_and_move_confirm' => "Awa, bul betti o'shiriw",
'delete_and_move_reason'  => "Ko'shiriwge jol beriw ushın o'shirilgen",

# Export
'export'            => 'Betlerdi eksport qılıw',
'export-submit'     => 'Eksport',
'export-addcattext' => 'Mına kategoriyadan betlerdi qosıw:',
'export-addcat'     => 'Qos',

# Namespace 8 related
'allmessages'        => 'Sistema xabarları',
'allmessagesname'    => 'Atama',
'allmessagesdefault' => 'Defolt tekst',
'allmessagescurrent' => "Ha'zirgi tekst",
'allmessagestext'    => "Bul {{ns:mediawiki}} isimler ko'pligindegi bar bolg'an sistema xabarları dizimi.
Please visit [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [http://translatewiki.net translatewiki.net] if you wish to contribute to the generic MediaWiki localisation.",

# Thumbnails
'thumbnail-more'           => "U'lkeytiw",
'filemissing'              => 'Fayl tabılmadı',
'thumbnail_error'          => "Miniatyura jaratıw qa'teligi: $1",
'thumbnail_invalid_params' => 'Miniatyura sazlawları natuwrı',

# Special:Import
'import'                  => 'Betlerdi import qılıw',
'import-interwiki-submit' => 'Import',
'import-comment'          => 'Kommentariy:',
'importstart'             => 'Betler import qılınbaqta...',
'import-revision-count'   => '{{PLURAL:$1|1 nusqa|$1 nusqa}}',
'importnopages'           => "Import qılınatug'ın betler joq.",
'importunknownsource'     => "Import qılıw derek tu'ri belgisiz",
'importnotext'            => 'Bos yamasa tekstsiz',

# Import log
'importlogpage'                    => 'Import qılıw jurnalı',
'import-logentry-upload-detail'    => '{{PLURAL:$1|1 nusqa|$1 nusqa}}',
'import-logentry-interwiki-detail' => '$2 degennen {{PLURAL:$1|1 nusqa|$1 nusqa}}',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "Sizin' paydalanıwshı betin'iz",
'tooltip-pt-anonuserpage'         => 'Bul IP adres paydalanıwshı beti',
'tooltip-pt-mytalk'               => "Sizin' sa'wbet betin'iz",
'tooltip-pt-anontalk'             => "Bul IP adresten kiritilgen o'zgerisler haqqında diskussiya",
'tooltip-pt-preferences'          => "Menin' sazlawlarım",
'tooltip-pt-watchlist'            => "O'zgerislerin baqlap turg'an betler dizimi",
'tooltip-pt-mycontris'            => "Sizin' u'lesler dizimin'iz",
'tooltip-pt-login'                => "Kiriwin'iz usınıladı, biraq ma'jbu'riy bolmag'an xalda.",
'tooltip-pt-anonlogin'            => "Kiriwin'iz usınıladı, biraq ma'jbu'riy bolmag'an xalda.",
'tooltip-pt-logout'               => "Shıg'ıw",
'tooltip-ca-talk'                 => "Mag'lıwmat beti haqqında diskussiya",
'tooltip-ca-edit'                 => "Siz bul betti o'zgertiwin'izge boladi. Iltimas betti saqlawdan aldın ko'rip shig'ıw knopkasın paydalanın'.",
'tooltip-ca-addsection'           => "Jan'a bo'lim jaratıw.",
'tooltip-ca-viewsource'           => "Bul bet qorg'alg'an. Biraq ko'rip shıg'ıwın'ızg'a boladı.",
'tooltip-ca-history'              => "Bul bettin' aqırg'ı nusqaları.",
'tooltip-ca-protect'              => "Bul betti qorg'aw",
'tooltip-ca-delete'               => "Bul betti o'shiriw",
'tooltip-ca-undelete'             => "Bul bettin' o'shiriwden aldın bolg'an o'zgertiwlerin qaytarıw",
'tooltip-ca-move'                 => "Bul betti ko'shiriw",
'tooltip-ca-watch'                => "Bul betti menin' baqlaw dizimime qosiw",
'tooltip-ca-unwatch'              => "Bul betti menin' baqlaw dizimimnen alıp tasla",
'tooltip-search'                  => '{{SITENAME}} saytınan izlew',
'tooltip-search-go'               => "Eger usı atamag'a iye bolg'an bet bolsa, tuwrı o'tip ketiw",
'tooltip-search-fulltext'         => 'Usı tekst ushın betlerdi izlew',
'tooltip-p-logo'                  => 'Bas bet',
'tooltip-n-mainpage'              => "Bas betke o'tiw",
'tooltip-n-mainpage-description'  => "Bas betke o'tiw",
'tooltip-n-portal'                => "Proyekt haqqında, nelerdi islewin'izge boladi, qayaqtan tabıwın'ızg'a boladi",
'tooltip-n-currentevents'         => "Ha'zirgi ha'diyseler haqqında mag'lıwmat tabıw",
'tooltip-n-recentchanges'         => "Wikidegi aqırg'ı o'zgerislerdin' dizimi.",
'tooltip-n-randompage'            => "Qa'legen betti ju'klew",
'tooltip-n-help'                  => 'Anıqlama tabıw ornı.',
'tooltip-t-whatlinkshere'         => 'Usı betke siltelgen barlıq betler dizimi',
'tooltip-t-recentchangeslinked'   => "Bul betten siltengen betlerdegi aqırg'ı o'zgerisler",
'tooltip-feed-rss'                => 'Bul bettin\' "RSS" jolag\'ı',
'tooltip-feed-atom'               => 'Bul bettin\' "Atom" jolag\'ı',
'tooltip-t-contributions'         => "Usı paydalanıwshının' u'lesler dizimin ko'riw",
'tooltip-t-emailuser'             => "Usı paydalanıwshıg'a e-mail jiberiw",
'tooltip-t-upload'                => 'Fayllardı aploud qılıw',
'tooltip-t-specialpages'          => 'Barlıq arnawlı betler dizimi',
'tooltip-t-print'                 => "Bul bettin' baspa nusqası",
'tooltip-t-permalink'             => "Bul bettegi usı nusqasının' turaqlı siltewi",
'tooltip-ca-nstab-main'           => "Mag'lıwmat betin ko'riw",
'tooltip-ca-nstab-user'           => "Paydalanıwshı betin ko'riw",
'tooltip-ca-nstab-media'          => "Media betin ko'riw",
'tooltip-ca-nstab-special'        => "Bul arnawlı bet, onı o'zgerte almaysız.",
'tooltip-ca-nstab-project'        => "Proyekt betin ko'riw",
'tooltip-ca-nstab-image'          => "Fayl betin ko'riw",
'tooltip-ca-nstab-mediawiki'      => "Sistema xabarın ko'riw",
'tooltip-ca-nstab-template'       => "Shablondı ko'riw",
'tooltip-ca-nstab-help'           => "Anıqlama betin ko'riw",
'tooltip-ca-nstab-category'       => "Kategoriya betin ko'riw",
'tooltip-minoredit'               => "Kishi o'zgeris dep belgilew",
'tooltip-save'                    => "O'zgertiwlerin'izdi saqla",
'tooltip-preview'                 => "Saqlawdan aldın kiritken o'zgerislerin'izdi ko'rip shıg'ın'!",
'tooltip-diff'                    => "Tekstke qanday o'zgeris kiritkenin'izdi ko'rsetiw",
'tooltip-compareselectedversions' => "Bettin' eki nusqasının' ayırmashılıg'ın qaraw.",
'tooltip-watch'                   => "Bul betti baqlaw dizimin'izge qosıw",
'tooltip-upload'                  => 'Aploudtı basla',
'tooltip-rollback'                => "\"Biykar etiw\" arqalı usı betke aqırg'ı u'les qosıwshının' kirgizgen o'zgerislerin qaytarıw mu'mkinshiligine iyesiz",
'tooltip-undo'                    => "\"Qaytarıw\" arqalı bul o'zgeristi artqa qaytarıw ha'm onı aldınnan ko'riw formasında baqlaw menen birge qaytarıwdın' sebebin ko'rsetiw mu'mkinshiligine iyesiz.",

# Attribution
'anonymous'        => "{{SITENAME}} saytının' anonim {{PLURAL:$1|paydalanıwshısı|paydalanıwshıları}}",
'siteuser'         => '{{SITENAME}} paydalanıwshısı $1',
'lastmodifiedatby' => "Bul bettin' aqırg'ı ma'rte $3 o'zgertken waqtı: $2, $1.",
'others'           => 'basqalar',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|paydalanıwshı|paydalanıwshıları}} $1',
'creditspage'      => 'Bet avtorları',

# Info page
'infosubtitle' => "Bet haqqında mag'lıwmat",
'numedits'     => "O'zgerisler sanı (bet): $1",
'numtalkedits' => "O'zgerisler sanı (diskussiya beti): $1",
'numwatchers'  => 'Baqlawshılar sanı: $1',

# Math errors
'math_unknown_error'    => "belgisiz qa'telik",
'math_unknown_function' => 'belgisiz funktsiya',
'math_lexing_error'     => "leksikalıq qa'telik",
'math_syntax_error'     => "sintaksikalıq qa'telik",

# Patrol log
'patrol-log-auto' => '(avtomatlasqan)',

# Image deletion
'deletedrevision'       => "$1 eski nusqasın o'shirdi",
'filedeleteerror-short' => "Fayl o'shiriw qateligi: $1",

# Browsing diffs
'previousdiff' => "← Aldıng'ı parq",
'nextdiff'     => 'Keyingi parq →',

# Media information
'thumbsize'            => "Miniatyuranın' ha'jmi:",
'widthheight'          => '$1 × $2',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|bet|bet}}',
'file-info'            => "fayldın' ha'jmi: $1, MIME tu'ri: $2",
'file-info-size'       => "$1 × $2 piksel, fayldın' ha'jmi: $3, MIME tu'ri: $4",
'file-nohires'         => '<small>Bunnan joqarı imkaniyatlı tabılmadı.</small>',
'svg-long-desc'        => "SVG fayl, $1 × $2 piksel belgilengen, fayldın' ha'jmi: $3",
'show-big-image'       => 'Joqarı imkaniyatlı',
'show-big-image-thumb' => "<small>Bul aldinnan ko'riwdin' ha'jmi: $1 × $2 piksel</small>",

# Special:NewFiles
'newimages'             => 'Taza fayllar galereyasi',
'showhidebots'          => '(botlardı $1)',
'noimages'              => "Ko'riwge su'wret joq.",
'ilsubmit'              => 'İzle',
'bydate'                => "sa'ne boyınsha",
'sp-newimages-showfrom' => "$2, $1 baslap taza fayllardı ko'rset",

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'   => '$1, $2 × $3',
'hours-abbrev' => 'st',

# Bad image list
'bad_image_list' => "Formatı to'mendegishe:

Tek dizim elementleri (* menen baslanatug'ın qatarlar) esaplanadi.
Qatardın' birinshi siltewi natuwrı faylg'a siltewi sha'rt.
Sol qatardag'ı keyingi ha'r bir siltewler tısqarı qabıl etiledi, mısalı qatar ishindegi ushırasatug'ın faylı bar betler.",

# Metadata
'metadata'          => "Metamag'lıwmat",
'metadata-help'     => "Usı faylda a'dette sanlı kamera yamasa skaner arqalı qosılatug'ın qosımsha mag'lıwmat bar.
Eger fayl jaratılg'anınan keyin o'zgertilgen bolsa, geybir parametrleri o'zgertilgen faylg'a tuwra kelmewi mu'mkin.",
'metadata-expand'   => "Qosımsha mag'lıwmatlardı ko'rset",
'metadata-collapse' => "Qosımsha mag'lıwmatlardi jasır",
'metadata-fields'   => "Usı xabarda ko'rsetilgen EXIF metamag'lıwmat qatarları metamag'lıwmat kestesi jasırılg'anda su'wret betinde ko'rsetiledi. Basqalar defolt boyınsha jasırılg'an.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'       => 'Yeni:',
'exif-imagelength'      => "Uzunlıg'ı",
'exif-imagedescription' => "Su'wret ataması",
'exif-artist'           => 'Avtor',

# External editor support
'edit-externally'      => "Bul fayldı sırtqı bag'darlama arqalı o'zgertiw",
'edit-externally-help' => "(Ko'birek mag'lıwmat ushın [http://www.mediawiki.org/wiki/Manual:External_editors ornatıw jolların] qaran')",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => "ha'mmesin",
'imagelistall'     => "ha'mme",
'watchlistall2'    => "ha'mmesin",
'namespacesall'    => "ha'mmesi",
'monthsall'        => "ha'mme",

# E-mail address confirmation
'confirmemail'            => 'E-mail adresin tastıyıqlaw',
'confirmemail_send'       => 'Tastıyıqlaw kodın jiberiw',
'confirmemail_sent'       => 'Tastıyıqlaw xatı jiberildi.',
'confirmemail_oncreate'   => "Tastıyıqlaw kodı e-mail adresin'izge jiberildi.
Bul kod kiriw ushın talap etilmeydi, biraq wikidin' e-mail mu'mkinshiliklerinen paydalanıwın'ız ushın kodtı ko'rsetiwin'iz kerek.",
'confirmemail_sendfailed' => "{{SITENAME}} sizin' tastıyıqlaw xatın'ızdı jibere almadi.
Adresin'izde jaramsız simvollar bolmawına tekserip shıg'ın'.

Xat jiberiwshinin' qaytarg'an mag'lıwmatı: $1",
'confirmemail_invalid'    => "Tastıyıqlaw kodı nadurıs.
Kodtın' jaramlılıq waqtı pitken bolıwı mu'mkin.",
'confirmemail_needlogin'  => "E-mail adresin'izdi tastıyıqlaw ushın $1 kerek.",
'confirmemail_success'    => "Sizin' e-mail adresin'iz tastıyıqlandı. 
Endi wikige [[Special:UserLogin|kiriwin'iz]] mu'mkin.",
'confirmemail_loggedin'   => "Sizin' e-mail adresin'iz endi tastıyıqlandı.",
'confirmemail_error'      => "Tastıyıqlawın'ızdı saqlaw waqtında belgisiz qa'te ju'z berdi.",
'confirmemail_subject'    => '{{SITENAME}} e-pochta adresi tastıyıqaw xatı',
'confirmemail_body'       => "Geybirew, ba'lkimiz o'zin'iz shıg'ar, $1 IP adresinen,
{{SITENAME}} saytında bul E-pochta adresin qollanıp «$2» degen akkaunt jarattı.

Usı akkaunt shın'ınan ha'm siziki ekenin tastıyıqlaw ushın ha'mde {{SITENAME}} saytının'
e-pochta mu'mkinshiliklerin paydalanıw ushın, to'mendegi siltewdi brauzerin'izde ashın':

$3

Eger bul akkauntti jaratpag'an bolsan'ız, to'mendegi siltewge o'tip
e-pochta adresin'izdin' tastıyıqlawın o'shirsen'iz boladı:

$5

Bul tastıyıqlaw kodının' pitetug'ın waqtı: $4.",

# Trackbacks
'trackbackremove' => "([$1 O'shir])",

# Delete conflict
'recreate' => 'Qaytadan jaratıw',

'unit-pixel' => ' px',

# action=purge
'confirm_purge_button' => 'OK',

# Multipage image navigation
'imgmultipageprev' => "← aldıng'ı bet",
'imgmultipagenext' => 'keyingi bet →',
'imgmultigo'       => "O'tin'!",

# Table pager
'ascending_abbrev'         => "o's.",
'descending_abbrev'        => 'kem.',
'table_pager_next'         => 'Keyingi bet',
'table_pager_prev'         => "Aldıng'ı bet",
'table_pager_first'        => 'Birinshi bet',
'table_pager_last'         => "Aqırg'ı bet",
'table_pager_limit_submit' => "O'tin'",
'table_pager_empty'        => "Na'tiyjeler joq",

# Auto-summaries
'autosumm-blank'   => "Bettin' barlıq mag'lıwmatı o'shirilgen",
'autosumm-replace' => "Betti '$1' penen almastırıw",
'autoredircomment' => '[[$1]] degenge burıw',
'autosumm-new'     => 'Taza bet jaratıldı: "$1"',

# Friendlier slave lag warnings
'lag-warn-normal' => "Usı dizimde $1 {{PLURAL:$1|sekundtan|sekundtan}} jan'alaw bolg'an o'zgerisler ko'rsetilmewi mu'mkin.",

# Watchlist editor
'watchlistedit-numitems'      => "Sizin' baqlaw dizimin'izde, sa'wbet betlerin esapqa almag'anda {{PLURAL:$1|1 atama|$1 atama}} bar.",
'watchlistedit-noitems'       => "Baqlaw dizimin'izde atamalar joq.",
'watchlistedit-normal-title'  => "Baqlaw dizimin o'zgertiw",
'watchlistedit-normal-legend' => "Baqlaw diziminen atamalardi o'shıriw",
'watchlistedit-normal-submit' => "Atamalardı O'shir",
'watchlistedit-normal-done'   => "Baqlaw dizimin'izden {{PLURAL:$1|1 atama|$1 atama}} o'shirildi:",
'watchlistedit-raw-title'     => '"Shiyki" baqlaw dizimin o\'zgertiw',
'watchlistedit-raw-legend'    => '"Shiyki" baqlaw dizimin o\'zgertiw',
'watchlistedit-raw-titles'    => 'Atamalar:',
'watchlistedit-raw-submit'    => "Baqlaw dizimin jan'ala",
'watchlistedit-raw-done'      => "Baqılaw dizimin'iz jan'alandı.",
'watchlistedit-raw-added'     => "{{PLURAL:$1|1 atama|$1 atama}} qosilg'an:",
'watchlistedit-raw-removed'   => "{{PLURAL:$1|1 atama|$1 atama}} o'shirildi:",

# Watchlist editing tools
'watchlisttools-view' => "Baylanıslı o'zgerislerdi qaraw",
'watchlisttools-edit' => "Baqlaw dizimin ko'riw ha'm o'zgertiw",
'watchlisttools-raw'  => '"Shiyki" baqlaw dizimin o\'zgertiw',

# Special:Version
'version' => "MediaWikidin' nusqası",

# Special:FilePath
'filepath' => 'Fayl jolı',

# Special:SpecialPages
'specialpages'             => 'Arnawlı betler',
'specialpages-group-users' => "Paydalanıwshılar ha'm olardın' huqıqları",

);
