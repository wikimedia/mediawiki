<?php
/** Basque (Euskara)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author An13sa
 * @author Bengoa
 * @author Helix84
 * @author Kaganer
 * @author Kaustubh
 * @author Kobazulo
 * @author Malafaya
 * @author Reedy
 * @author Theklan
 * @author Unai Fdz. de Betoño
 * @author Urhixidur
 * @author Xabier Armendaritz
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Berezi',
	NS_TALK             => 'Eztabaida',
	NS_USER             => 'Lankide',
	NS_USER_TALK        => 'Lankide_eztabaida',
	NS_PROJECT_TALK     => '$1_eztabaida',
	NS_FILE             => 'Fitxategi',
	NS_FILE_TALK        => 'Fitxategi_eztabaida',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_eztabaida',
	NS_TEMPLATE         => 'Txantiloi',
	NS_TEMPLATE_TALK    => 'Txantiloi_eztabaida',
	NS_HELP             => 'Laguntza',
	NS_HELP_TALK        => 'Laguntza_eztabaida',
	NS_CATEGORY         => 'Kategoria',
	NS_CATEGORY_TALK    => 'Kategoria_eztabaida',
);

$namespaceAliases = array(
	'Aparteko' => NS_SPECIAL,
	'Irudi' => NS_FILE,
	'Irudi_eztabaida' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'BirzuzenketaBikoitzak' ),
	'BrokenRedirects'           => array( 'HautsitakoBirzuzenketak' ),
	'Disambiguations'           => array( 'Argipenak' ),
	'Userlogin'                 => array( 'SaioaHasi' ),
	'Userlogout'                => array( 'SaioaItxi' ),
	'CreateAccount'             => array( 'KontuaSortu' ),
	'Preferences'               => array( 'Hobespenak' ),
	'Watchlist'                 => array( 'JarraipenZerrenda' ),
	'Recentchanges'             => array( 'AzkenAldaketak' ),
	'Upload'                    => array( 'Igo' ),
	'Listfiles'                 => array( 'FitxategiZerrenda' ),
	'Newimages'                 => array( 'FitxategiBerriak' ),
	'Listusers'                 => array( 'LankideZerrenda' ),
	'Statistics'                => array( 'Estatistikak' ),
	'Randompage'                => array( 'Ausazkoa' ),
	'Uncategorizedpages'        => array( 'KategorizatuGabekoOrrialdeak' ),
	'Uncategorizedcategories'   => array( 'KategorizatuGabekoKategoriak' ),
	'Uncategorizedimages'       => array( 'KategorizatuGabekoFitxategiak' ),
	'Uncategorizedtemplates'    => array( 'KategorizatuGabekoTxantiloiak' ),
	'Wantedpages'               => array( 'EskatutakoOrrialdeak' ),
	'Wantedcategories'          => array( 'EskatutakoKategoriak' ),
	'Wantedfiles'               => array( 'EskatutakoFitxategiak' ),
	'Wantedtemplates'           => array( 'EskatutakoTxantiloiak' ),
	'Shortpages'                => array( 'OrrialdeMotzak' ),
	'Longpages'                 => array( 'OrrialdeLuzeak' ),
	'Newpages'                  => array( 'OrrialdeBerriak' ),
	'Ancientpages'              => array( 'OrrialdeZaharrak' ),
	'Protectedpages'            => array( 'BabestutakoOrrialdeak' ),
	'Protectedtitles'           => array( 'BabestutakoIzenburuak' ),
	'Allpages'                  => array( 'OrrialdeGuztiak' ),
	'Specialpages'              => array( 'OrrialdeBereziak' ),
	'Contributions'             => array( 'Ekarpenak' ),
	'Emailuser'                 => array( 'LankideEmaila' ),
	'Confirmemail'              => array( 'EmailaBaieztatu' ),
	'Whatlinkshere'             => array( 'ZerkLotzenDuHona' ),
	'Movepage'                  => array( 'OrrialdeaMugitu' ),
	'Blockme'                   => array( 'BlokeaNazazu' ),
	'Categories'                => array( 'Kategoriak' ),
	'Export'                    => array( 'Esportatu' ),
	'Version'                   => array( 'Bertsioa' ),
	'Allmessages'               => array( 'MezuGuztiak' ),
	'Blockip'                   => array( 'Blokeatu' ),
	'Import'                    => array( 'Inportatu' ),
	'Mypage'                    => array( 'NireOrrialdea' ),
	'Mytalk'                    => array( 'NireEztabaida' ),
	'Mycontributions'           => array( 'NireEkarpenak' ),
	'Listadmins'                => array( 'AdministratzaileZerrenda' ),
	'Listbots'                  => array( 'BotZerrenda' ),
	'Search'                    => array( 'Bilatu' ),
	'Resetpass'                 => array( 'PasahitzaAldatu' ),
	'Blankpage'                 => array( 'OrrialdeZuria' ),
	'Tags'                      => array( 'Etiketak' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#BIRZUZENDU', '#REDIRECT' ),
	'currentmonth'          => array( '1', 'ORAINGOHILABETE', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'ORAINGOHILABETEIZEN', 'CURRENTMONTHNAME' ),
	'currentday'            => array( '1', 'ORAINGOEGUN', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'ORAINGOEGUN2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'ORAINGOEGUNIZEN', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ORAINGOURTE', 'CURRENTYEAR' ),
	'numberofpages'         => array( '1', 'ORRIALDEKOPURU', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ARTIKULUKOPURU', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'FITXATEGIKOPURU', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'LANKIDEKOPURU', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'ALDAKETAKOPURU', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'ORRIALDEIZEN', 'PAGENAME' ),
	'img_right'             => array( '1', 'eskuinera', 'right' ),
	'img_left'              => array( '1', 'ezkerrera', 'left' ),
	'img_center'            => array( '1', 'erdian', 'center', 'centre' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' ); /* Bug 15717 */

$messages = array(
# User preference toggles
'tog-underline'               => 'Loturak azpimarratu:',
'tog-highlightbroken'         => 'Lotura hautsiak <a href="" class="new">horrela</a> erakutsi (bestela, honela<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Paragrafoak justifikatu',
'tog-hideminor'               => 'Azken aldaketetan aldaketa txikiak ezkutatu',
'tog-hidepatrolled'           => 'Ezkutatu patruilatutako aldaketa azken aldaketetan',
'tog-newpageshidepatrolled'   => 'Ezkutatu patruilatutako orriak, orri-zerrenda berritik',
'tog-extendwatchlist'         => 'Jarraipen-zerrenda zabaldu aldaketa guztiak ikusteko, ez bakarrik azken aldaketak',
'tog-usenewrc'                => 'Hobetutako azken aldaketak (JavaScript behar da)',
'tog-numberheadings'          => 'Goiburukoak automatikoki zenbakitu',
'tog-showtoolbar'             => 'Aldaketen tresna-barra erakutsi (JavaScript)',
'tog-editondblclick'          => 'Klik bikoitzaren bitartez orrialdeak aldatu (JavaScript)',
'tog-editsection'             => 'Atalak [aldatu] loturen bitartez aldatzeko aukera gaitu',
'tog-editsectiononrightclick' => 'Atalen izenburuetan klik eginez atala<br />aldatzea gaitu (JavaScript)',
'tog-showtoc'                 => 'Edukien taula erakutsi (3 goiburukotik gorako orrialdeentzako)',
'tog-rememberpassword'        => 'Nire saioa ordenagailu honetan gorde ({{PLURAL:$1|egun baterako| $1 egunerako}} gehienez)',
'tog-watchcreations'          => 'Sortzen ditudan orrialdeak nire segimendu zerrendara gehitu',
'tog-watchdefault'            => 'Aldatzen ditudan orrialdeak nire segimendu zerrendara gehitu',
'tog-watchmoves'              => 'Izena aldatutako orrialdeak segimendu zerrendan erakutsi',
'tog-watchdeletion'           => 'Ezabatzen ditudan orrialdeak nire segimendu zerrendara gehitu',
'tog-minordefault'            => 'Lehenetsi bezala aldaketa txiki bezala markatu guztiak',
'tog-previewontop'            => 'Aurrebista aldaketa koadroaren aurretik erakutsi',
'tog-previewonfirst'          => 'Lehen aldaketan aurrebista erakutsi',
'tog-nocache'                 => 'Orrialdeen katxea ezgaitu',
'tog-enotifwatchlistpages'    => 'Bidal iezadazue e-postako mezua, jarraitzen ari naizen orri bat aldatzen denean',
'tog-enotifusertalkpages'     => 'Nire eztabaida orrialdea aldatzen denean e-posta jaso',
'tog-enotifminoredits'        => 'Aldaketa txikiak direnean ere e-posta jaso',
'tog-enotifrevealaddr'        => 'Jakinarazpen mezuetan nire e-posta helbidea erakutsi',
'tog-shownumberswatching'     => 'Jarraitzen duen erabiltzaile kopurua erakutsi',
'tog-oldsig'                  => 'Egungo sinadura:',
'tog-fancysig'                => 'Sinadura wikitestu gisa tratatu (lotura automatikorik gabe)',
'tog-externaleditor'          => 'Lehenetsi bezala kanpoko editore bat erabili (adituentzako bakarrik, zure ordenagailuak konfigurazio berezia izan behar du. [http://www.mediawiki.org/wiki/Manual:External_editors Informazio gehiago.])',
'tog-externaldiff'            => 'Lehenetsi bezala kanpoko diff erreminta erabili (adituentzako bakarrik, zure ordenagailuak konfigurazio berezia izan behar du. [http://www.mediawiki.org/wiki/Manual:External_editors Informazio gehiago.])',
'tog-showjumplinks'           => '"Hona jo" irisgarritasun loturak gaitu',
'tog-uselivepreview'          => 'Zuzeneko aurrebista erakutsi (JavaScript) (Proba fasean)',
'tog-forceeditsummary'        => 'Aldaketaren laburpena zuri uzterakoan ohartarazi',
'tog-watchlisthideown'        => 'Segimendu zerrendan nire aldaketak ezkutatu',
'tog-watchlisthidebots'       => 'Segimendu zerrendan bot-en aldaketak ezkutatu',
'tog-watchlisthideminor'      => 'Segimendu zerrendan, aldaketa txikiak ezkutatu',
'tog-watchlisthideliu'        => 'Ezkutatu izena emana duten lankideen aldaketak, jarraitze-zerrendan',
'tog-watchlisthideanons'      => 'Ezkutatu lankide anonimoen aldaketak, jarraitze-zerrendan',
'tog-watchlisthidepatrolled'  => 'Ezkutatu patruilatutako aldaketak jarraitze-zerrendan',
'tog-nolangconversion'        => 'Aldaeren arteko konbertsioa ezgaitu',
'tog-ccmeonemails'            => 'Beste erabiltzaileei bidaltzen dizkiedan mezuen kopiak niri ere bidali',
'tog-diffonly'                => "''Diff''-ak agertzen direnean, orrialdearen edukiera ezkutatu",
'tog-showhiddencats'          => 'Ikusi kategoria ezkutuak',
'tog-norollbackdiff'          => 'Rollback bat egin ondoren ezberdintasunak ez hartu aintzat',

'underline-always'  => 'Beti',
'underline-never'   => 'Inoiz ez',
'underline-default' => 'Nabigatzailearen lehenetsitako balioa',

# Font style option in Special:Preferences
'editfont-style'     => 'Aldatu eremuko letra tipoa:',
'editfont-default'   => 'Nabigatzaileak aurretik zehaztua',
'editfont-monospace' => 'Monospace iturria',
'editfont-sansserif' => 'Sans-serif iturria',
'editfont-serif'     => 'Serif iturria',

# Dates
'sunday'        => 'Igandea',
'monday'        => 'Astelehena',
'tuesday'       => 'Asteartea',
'wednesday'     => 'Asteazkena',
'thursday'      => 'Osteguna',
'friday'        => 'Ostirala',
'saturday'      => 'Larunbata',
'sun'           => 'Iga',
'mon'           => 'Asl',
'tue'           => 'Asr',
'wed'           => 'Asz',
'thu'           => 'Osg',
'fri'           => 'Osr',
'sat'           => 'Lar',
'january'       => 'Urtarrila',
'february'      => 'Otsaila',
'march'         => 'Martxoa',
'april'         => 'Apirila',
'may_long'      => 'Maiatza',
'june'          => 'Ekaina',
'july'          => 'Uztaila',
'august'        => 'Abuztua',
'september'     => 'Iraila',
'october'       => 'Urria',
'november'      => 'Azaroa',
'december'      => 'Abendua',
'january-gen'   => 'Urtarril',
'february-gen'  => 'Otsail',
'march-gen'     => 'Martxo',
'april-gen'     => 'Apiril',
'may-gen'       => 'Maiatz',
'june-gen'      => 'Ekain',
'july-gen'      => 'Uztail',
'august-gen'    => 'Abuztu',
'september-gen' => 'Irail',
'october-gen'   => 'Urri',
'november-gen'  => 'Azaro',
'december-gen'  => 'Abendu',
'jan'           => 'Urt',
'feb'           => 'Ots',
'mar'           => 'Mar',
'apr'           => 'Api',
'may'           => 'Mai',
'jun'           => 'Eka',
'jul'           => 'Uzt',
'aug'           => 'Abu',
'sep'           => 'Ira',
'oct'           => 'Urr',
'nov'           => 'Aza',
'dec'           => 'Abe',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategoria|Kategoriak}}',
'category_header'                => '"$1" kategoriako artikuluak',
'subcategories'                  => 'Azpikategoriak',
'category-media-header'          => 'Media "$1" kategorian',
'category-empty'                 => "''Kategoria honek ez dauka artikulurik uneotan.''",
'hidden-categories'              => '{{PLURAL:$1|Izkutuko kategoria|Izkutuko kategoriak}}',
'hidden-category-category'       => 'Kategoria ezkutuak',
'category-subcat-count'          => '{{PLURAL:$2|Kategoria honek beste honako azpikategoria baino ez du.|Kategoria honek honako {{PLURAL:$1|azpikategoria du|$1 azpikategoriak ditu}}, guztira dauden $2tik.}}',
'category-subcat-count-limited'  => 'Kategoria honek {{PLURAL:$1|azpikategoria hau du|$1 azpikategoria hauek ditu}}.',
'category-article-count'         => '{{PLURAL:$2|Kategoria honek honako orrialdea baino ez du.|Honako {{PLURAL:$1|orrialdea kategoria honetan dago|$1 orrialdeak kategoria hauetan daude}}, guztira dauden $2tik.}}',
'category-article-count-limited' => 'Honako orrialde {{PLURAL:$1|hau kategoria honetan dago|$1 hauek kategoria hauetan daude}}:',
'category-file-count'            => '{{PLURAL:$2|Kategoria honek fitxategi hau baino ez du.|Honako {{PLURAL:$1|fitxategia kategoria honetan dago|$1 fitxategiak kategoria honetan daude}} guztira dauden $2tik.}}',
'category-file-count-limited'    => 'Ondorengo {{PLURAL:$1|artxiboa kategoria honetan dago.|$1 artxiboak kategoria honetan daude.}}',
'listingcontinuesabbrev'         => 'jarr.',
'index-category'                 => 'Indexatutako orrialdeak',
'noindex-category'               => 'Indexatugabeko orrialdeak',

'mainpagetext'      => "'''MediaWiki arrakastaz instalatu da.'''",
'mainpagedocfooter' => 'Ikus [http://meta.wikimedia.org/wiki/Help:Contents Erabiltzaile Gida] wiki softwarea erabiltzen hasteko informazio gehiagorako.

== Nola hasi ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Konfigurazio balioen zerrenda]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ (Maiz egindako galderak)]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWikiren argitalpenen posta zerrenda]',

'about'         => 'Honi buruz',
'article'       => 'Artikulua',
'newwindow'     => '(leiho berrian irekitzen da)',
'cancel'        => 'Utzi',
'moredotdotdot' => 'Gehiago...',
'mypage'        => 'Nire orrialdea',
'mytalk'        => 'Nire eztabaida',
'anontalk'      => 'IP honen eztabaida',
'navigation'    => 'Nabigazioa',
'and'           => '&#32;eta',

# Cologne Blue skin
'qbfind'         => 'Aurkitu',
'qbbrowse'       => 'Arakatu',
'qbedit'         => 'Aldatu',
'qbpageoptions'  => 'Orrialde hau',
'qbpageinfo'     => 'Testuingurua',
'qbmyoptions'    => 'Nire orrialdeak',
'qbspecialpages' => 'Aparteko orrialdeak',
'faq'            => 'Maiz egindako galderak',
'faqpage'        => 'Project:Maiz egindako galderak',

# Vector skin
'vector-action-addsection'       => 'Mintzagaia gehitu',
'vector-action-delete'           => 'Ezabatu',
'vector-action-move'             => 'Mugitu',
'vector-action-protect'          => 'Babestu',
'vector-action-undelete'         => 'Berreskuratu',
'vector-action-unprotect'        => 'Babesa aldatu',
'vector-simplesearch-preference' => 'Baimendu bilaketa gomendio hobetuak (Vector itxurarekin bakarrik)',
'vector-view-create'             => 'Sortu',
'vector-view-edit'               => 'Aldatu',
'vector-view-history'            => 'Historia ikusi',
'vector-view-view'               => 'Irakurri',
'vector-view-viewsource'         => 'Kodea ikusia',
'actions'                        => 'Ekintzak',
'namespaces'                     => 'Izen-tarteak',
'variants'                       => 'Aldaerak',

'errorpagetitle'    => 'Errorea',
'returnto'          => '$1(e)ra itzuli.',
'tagline'           => '{{SITENAME}}(e)tik',
'help'              => 'Laguntza',
'search'            => 'Bilatu',
'searchbutton'      => 'Bilatu',
'go'                => 'Joan',
'searcharticle'     => 'Joan',
'history'           => 'Orrialdearen historia',
'history_short'     => 'Historia',
'updatedmarker'     => 'nire azkeneko bisitaz geroztik eguneratuta',
'info_short'        => 'Informazioa',
'printableversion'  => 'Inprimatzeko bertsioa',
'permalink'         => 'Lotura finkoa',
'print'             => 'Inprimatu',
'edit'              => 'Aldatu',
'create'            => 'Sortu',
'editthispage'      => 'Orrialde hau aldatu',
'create-this-page'  => 'Orrialde hau sortu',
'delete'            => 'Ezabatu',
'deletethispage'    => 'Orrialde hau ezabatu',
'undelete_short'    => 'Berreskuratu {{PLURAL:$1|aldaketa bat|$1 aldaketa}}',
'protect'           => 'Babestu',
'protect_change'    => 'aldatu',
'protectthispage'   => 'Orrialde hau babestu',
'unprotect'         => 'Babesa aldatu',
'unprotectthispage' => 'Orrialde honen babesa aldatu',
'newpage'           => 'Orrialde berria',
'talkpage'          => 'Orrialde honi buruz eztabaidatu',
'talkpagelinktext'  => 'Eztabaida',
'specialpage'       => 'Aparteko orrialdea',
'personaltools'     => 'Tresna pertsonalak',
'postcomment'       => 'Atal berria',
'articlepage'       => 'Artikulua ikusi',
'talk'              => 'Eztabaida',
'views'             => 'Ikustaldiak',
'toolbox'           => 'Tresnak',
'userpage'          => 'Lankide orrialdea ikusi',
'projectpage'       => 'Proiektuaren orrialdea ikusi',
'imagepage'         => 'Ikusi fitxategiaren orria',
'mediawikipage'     => 'Mezu orrialdea ikusi',
'templatepage'      => 'Txantiloi orrialdea ikusi',
'viewhelppage'      => 'Laguntza orrialdea ikusi',
'categorypage'      => 'Kategoria orrialdea ikusi',
'viewtalkpage'      => 'Eztabaida ikusi',
'otherlanguages'    => 'Beste hizkuntzetan',
'redirectedfrom'    => '($1(e)tik birzuzenduta)',
'redirectpagesub'   => 'Birzuzenketa orrialdea',
'lastmodifiedat'    => 'Orrialdearen azken aldaketa: $2, $1.',
'viewcount'         => 'Orrialde hau {{PLURAL:$1|behin|$1 aldiz}} bisitatu da.',
'protectedpage'     => 'Babestutako orrialdea',
'jumpto'            => 'Hona jo:',
'jumptonavigation'  => 'nabigazioa',
'jumptosearch'      => 'bilatu',
'view-pool-error'   => 'Barkatu, zerbitzariak gainezka daude uneotan.
Erabiltzaile gehiegi ari da orrialde hau ikusi nahiean.
Mesedez itxaron ezazu unetxo bat orrialde honetara berriz sartzen saiatu baino lehen.

$1',
'pool-queuefull'    => 'Prozesuen zerrenda beteta dago',
'pool-errorunknown' => 'Errore ezezaguna',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}(e)ri buruz',
'aboutpage'            => 'Project:Honi_buruz',
'copyright'            => 'Eduki guztia $1(r)en babespean dago.',
'copyrightpage'        => '{{ns:project}}:Eskubideak',
'currentevents'        => 'Albisteak',
'currentevents-url'    => 'Project:Albisteak',
'disclaimers'          => 'Mugaketak',
'disclaimerpage'       => 'Project:Erantzukizunen mugaketa orokorra',
'edithelp'             => 'Aldaketak egiteko laguntza',
'edithelppage'         => 'Help:Aldaketak egiteko laguntza',
'helppage'             => 'Help:Laguntza',
'mainpage'             => 'Azala',
'mainpage-description' => 'Azala',
'policy-url'           => 'Project:Politikak',
'portal'               => 'Komunitatearen ataria',
'portal-url'           => 'Project:Komunitatearen ataria',
'privacy'              => 'Pribatutasun politika',
'privacypage'          => 'Project:Pribatutasun politika',

'badaccess'        => 'Baimen errorea',
'badaccess-group0' => 'Ez daukazu ekintza hori burutzeko baimenik.',
'badaccess-groups' => 'Eskatu duzun ekintza honako {{PLURAL:$2|taldeko|taldeetako}} lankideei mugatuta dago: $1.',

'versionrequired'     => 'MediaWikiren $1 bertsioa beharrezkoa da',
'versionrequiredtext' => 'MediaWikiren $1 bertsioa beharrezkoa da orrialde hau erabiltzeko. Ikus [[Special:Version]]',

'ok'                      => 'Ados',
'retrievedfrom'           => '"$1"(e)tik jasota',
'youhavenewmessages'      => '$1 dauzkazu ($2).',
'newmessageslink'         => 'Mezu berriak',
'newmessagesdifflink'     => 'azken aldaketa ikusi',
'youhavenewmessagesmulti' => 'Mezu berriak dituzu $1(e)n',
'editsection'             => 'aldatu',
'editold'                 => 'aldatu',
'viewsourceold'           => 'kodea ikusi',
'editlink'                => 'aldatu',
'viewsourcelink'          => 'jatorria ikusi',
'editsectionhint'         => 'Atala aldatu: $1',
'toc'                     => 'Edukiak',
'showtoc'                 => 'erakutsi',
'hidetoc'                 => 'ezkutatu',
'thisisdeleted'           => '$1 ikusi edo leheneratu?',
'viewdeleted'             => '$1 ikusi?',
'restorelink'             => '{{PLURAL:$1|ezabatutako aldaketa bat|ezabatutako $1 aldaketa}}',
'feedlinks'               => 'Jarioa:',
'feed-invalid'            => 'Baliogabeko harpidetza jario mota.',
'feed-unavailable'        => 'Jarioak ez daude eskuragarri.',
'site-rss-feed'           => '$1 RSS Jarioa',
'site-atom-feed'          => '$1 Atom Jarioa',
'page-rss-feed'           => '"$1" RSS Jarioa',
'page-atom-feed'          => '"$1" Atom Jarioa',
'red-link-title'          => '$1 (orria ez da existitzen)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Orrialdea',
'nstab-user'      => 'Erabiltzaile orrialdea',
'nstab-media'     => 'Media orrialdea',
'nstab-special'   => 'Orri berezia',
'nstab-project'   => 'Proiektu orrialdea',
'nstab-image'     => 'Fitxategia',
'nstab-mediawiki' => 'Mezua',
'nstab-template'  => 'Txantiloi',
'nstab-help'      => 'Laguntza orrialdea',
'nstab-category'  => 'Kategoria',

# Main script and global functions
'nosuchaction'      => 'Ekintza hori ez da existitizen',
'nosuchactiontext'  => 'URL bidez zehaztutako ekintza okerra da.
URLa gaizki idatzi duzu, edo hautsitako lotura jarraitu duzu.
Honek akatsa indikatzen du {{SITENAME}}-(e)n.',
'nosuchspecialpage' => 'Ez da aparteko orrialde hori existitzen',
'nospecialpagetext' => '<strong>Baliogabeko aparteko orrialde bat eskatu duzu.</strong>

Existitzen direnen zerrenda ikus dezakezu  [[Special:SpecialPages|{{int:specialpages}}]] orrialdean.',

# General errors
'error'                => 'Errorea',
'databaseerror'        => 'Datu-base errorea',
'dberrortext'          => 'Datu-basean kontsulta egiterakoan sintaxi errore bat gertatu da. Baliteke softwareak bug bat izatea. Datu-basean egindako azken kontsulta:
<blockquote><tt>$1</tt></blockquote>
funtzio honekin: "<tt>$2</tt>".
Datu-baseak emandako errore informazioa: "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Datu-basean kontsulta egiterakoan sintaxi errore bat gertatu da.
Datu-basean egindako azken kontsulta:
"$1"
funtzio honekin: "$2".
Datu-baseak emandako errore informazioa: "$3: $4"',
'laggedslavemode'      => 'Oharra: Baliteke orrialde honetan azken aldaketak ez erakustea.',
'readonly'             => 'Datu-basea blokeatuta dago',
'enterlockreason'      => 'Zehaztu blokeatzeko arrazoia, noiz kenduko den jakinaraziz',
'readonlytext'         => 'Datu-basea artikulu berriak sortu edo aldaketak ez egiteko blokeatuta dago, seguruenik mantenu lanak direla-eta. Blokeo hori kentzerakoan beti bezala egongo da berriz.

Blokeatu duen administratzaileak azalpen hau eman du: $1',
'missing-article'      => 'Databaseak ez du aurkitu berez baden "$1" izeneko orrialdearen testua, $2.

Hau askotan dataz kanpo dagoen ezb. historia bat jarraitzen delako gertatzen da eta ezabatu den orrialde bati lotura egiten diolako.

Hau ez bada kasua, agian akats bat aurkitu duzu softwarean.
Mesedez, bidali gertakar hau administradore bati, URLaren izena jarriz.',
'missingarticle-rev'   => '(berrikuspena#: $1)',
'missingarticle-diff'  => '(Ezb: $1, $2)',
'readonly_lag'         => 'Datu-basea automatikoki blokeatu da menpeko zerbitzariak nagusiarekin sinkronizatu bitartean',
'internalerror'        => 'Barne errorea',
'internalerror_info'   => 'Barne errorea: $1',
'fileappenderrorread'  => 'Ezin izan da "$1" irakurri, gehitzean.',
'fileappenderror'      => 'Ezin da gehitu "$1" "$2"(e)ra.',
'filecopyerror'        => 'Ezin izan da "$1" fitxategia "$2"(e)ra kopiatu.',
'filerenameerror'      => 'Ezin izan zaio "$1" fitxategiari "$2" izen berria eman.',
'filedeleteerror'      => 'Ezin izan da "$1" fitxategia ezabatu.',
'directorycreateerror' => 'Ezin izan da "$1" karpeta sortu.',
'filenotfound'         => 'Ezin izan da "$1" fitxategia aurkitu.',
'fileexistserror'      => 'Ezin da "$1" fitxategian idatzi: lehendik existitzen da',
'unexpected'           => 'Espero ez zen balioa: "$1"="$2".',
'formerror'            => 'Errorea: ezin izan da formularioa bidali',
'badarticleerror'      => 'Ezin da ekintza hau orrialde honetan burutu.',
'cannotdelete'         => 'Ezin izan da "$1" orrialde edo fitxategia ezabatu.
Baliteke beste norbaitek ezabatu izana.',
'badtitle'             => 'Izenburu ezegokia',
'badtitletext'         => 'Eskatutako orrialde izenburua ez da baliozkoa, hutsik dago, edo gaizki lotutako hizkuntzen arteko lotura da. Baliteke izenburuetan erabili ezin den karaktereren bat izatea.',
'perfcached'           => 'Hurrengo datuak katxean gordeta daude eta litekeena da guztiz eguneratuta ez egotea:',
'perfcachedts'         => 'Hurrengo datuak katxean daude, $1 eguneratu zen azkenekoz.',
'querypage-no-updates' => 'Orrialde honen berritzeak ez dira baimentzen. Hemen dagoen data ez da zuzenean berrituko.',
'wrong_wfQuery_params' => 'Baliogabeko parametroak eman zaizkio wfQuery() funtzioari<br />
Funtzioa: $1<br />
Kontsulta: $2',
'viewsource'           => 'Kodea ikusi',
'viewsourcefor'        => '$1(r)entzako',
'actionthrottled'      => 'Ekintzaren gainetik pasa da',
'actionthrottledtext'  => 'Spamaren aurkako neurri gisa ekintza hau denbora tarte laburrean aldi askotan egiteko mugapena duzu, eta muga hori zeharkatu duzu.
Saia zaitez berriro minutu batzuen buruan, mesedez.',
'protectedpagetext'    => 'Orrialde hau aldaketak saihesteko blokeatu egin da.',
'viewsourcetext'       => 'Orrialde honen testua ikusi eta kopiatu dezakezu:',
'protectedinterface'   => 'Orrialde honek softwarearentzako interfaze testua gordetzen du eta blokeatuta dago bandalismoak saihesteko.',
'editinginterface'     => "'''Oharra:''' Softwarearentzako interfaze testua duen orrialde bat aldatzen ari zara.
Orrialde honetako aldaketek erabiltzaile guztiei eragingo die.
Itzulpenetarako, [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net] erabili ezazu, MediaWiki proiektuan.",
'sqlhidden'            => '(ezkutuko SQL kontsulta)',
'cascadeprotected'     => "Orrialde hau aldaketen aurka babestua dago, ''kaskada'' aukerarekin babestu {{PLURAL:$1|duten orrialde honetan|dituzten orrialde hauetan}} txertaturik dagoelako:
$2",
'namespaceprotected'   => "Ez daukazu '''$1''' izen-tarteko orrialdeak aldatzeko baimenik.",
'customcssjsprotected' => 'Ez daukazu orrialde hau aldatzeko baimenik, beste erabiltzaile baten hobespenak definitzen dituelako.',
'ns-specialprotected'  => 'Ezin dira {{ns:special}} izen-tarteko orrialdeak editatu.',
'titleprotected'       => "[[User:$1|$1]]ek izenburu hau sortzea ekidin zuen.
Emandako arrazoia ''$2'' izan zen.",

# Virus scanner
'virus-badscanner'     => "Ezarpen txarrak: antibirus ezezaguna: ''$1''",
'virus-scanfailed'     => 'eskaneatze txarra ($1 kodea)',
'virus-unknownscanner' => 'antibirus ezezaguna:',

# Login and logout pages
'logouttext'                 => "'''Saioa itxi egin duzu.'''

Erabiltzaile anonimo bezala jarrai dezakezu {{SITENAME}} erabiltzen, edo [[Special:UserLogin|saioa has dezakezu berriz]] erabiltzaile berdinarekin edo ezberdin batekin.
Kontuan izan orrialde batzuk saioa hasita bazenu bezala ikus ditzakezula nabigatzailearen katxea garbitu arte.",
'welcomecreation'            => '== Ongi etorri, $1! ==

Zure kontua sortu egin da. Ez ahaztu [[Special:Preferences|{{SITENAME}}(e)ko hobespenak]] aldatzea.',
'yourname'                   => 'Erabiltzaile izena',
'yourpassword'               => 'Pasahitza:',
'yourpasswordagain'          => 'Pasahitza berriz',
'remembermypassword'         => 'Nire saioa ordenagailu honetan gogoratu ({{PLURAL:$1|egun baterako|$1 egunetarako }} gehienez)',
'securelogin-stick-https'    => 'Mantendu konektatua HTTPSra sartu ondoren',
'yourdomainname'             => 'Zure domeinua',
'externaldberror'            => 'Kanpoko datu-base autentifikazio errorea gertatu da edo ez duzu zure kanpo kontua eguneratzeko baimenik.',
'login'                      => 'Saioa hasi',
'nav-login-createaccount'    => 'Saioa hasi / kontua sortu',
'loginprompt'                => 'Cookieak gaituta izatea beharrezkoa da {{SITENAME}}(e)n saioa hasteko.',
'userlogin'                  => 'Saioa hasi / kontua sortu',
'userloginnocreate'          => 'Saioa hasi',
'logout'                     => 'Saioa itxi',
'userlogout'                 => 'Saioa itxi',
'notloggedin'                => 'Saioa hasi gabe',
'nologin'                    => "Ez duzu erabiltzaile konturik? '''$1'''.",
'nologinlink'                => 'Kontua sortu',
'createaccount'              => 'Kontua sortu',
'gotaccount'                 => "Baduzu erabiltzaile kontua? '''$1'''.",
'gotaccountlink'             => 'Saioa hasi',
'createaccountmail'          => 'e-postaz',
'createaccountreason'        => 'Arrazoia:',
'badretype'                  => 'Idatzitako pasahitzak ez dira berdinak.',
'userexists'                 => 'Aukeratutako erabiltzaile izena hartuta dago.
Mesedez beste bat aukeratu.',
'loginerror'                 => 'Errorea saioa hastean',
'createaccounterror'         => 'Ezin izan da kontua sortu: $1',
'nocookiesnew'               => 'Erabiltzaile kontua sortu da, baina ez da saioa hasi. {{SITENAME}}(e)k cookieak erabiltzen ditu saioekin eta ezgaituta dauzkazu. Gaitu itzazu mesedez, eta ondoren saiatu saioa hasten zure erabiltzaile izen eta pasahitz berriak erabiliz.',
'nocookieslogin'             => '{{SITENAME}}(e)k cookieak erabiltzen ditu saioekin eta ezgaituta dauzkazu. Gaitu itzazu mesedez, eta saiatu berriz.',
'noname'                     => 'Ez duzu baliozko erabiltzaile izen bat zehaztu.',
'loginsuccesstitle'          => 'Saio hasiera egina',
'loginsuccess'               => "'''Saioa hasi duzu {{SITENAME}}(e)n \"\$1\" izenarekin.'''",
'nosuchuser'                 => 'Ez dago "$1" izena duen lankiderik.
Lankide izenak zehatza izan behar du.
Egiaztatu ondo idatzi duzun, edo [[Special:UserLogin/signup|kontu berria sor ezazu]].',
'nosuchusershort'            => 'Ez dago "<nowiki>$1</nowiki>" izena duen erabiltzailerik. Egiaztatu ongi idatzi duzula.',
'nouserspecified'            => 'Erabiltzaile izena zehaztu beharra daukazu.',
'login-userblocked'          => 'Erabiltzailea blokeatua dago. Ezin du saioa hasi.',
'wrongpassword'              => 'Pasahitza ez da zuzena. Saiatu berriz.',
'wrongpasswordempty'         => 'Pasahitza hutsik dago. Saiatu berriz.',
'passwordtooshort'           => 'Pasahitzek {{PLURAL:$1|karaktere 1|$1 karaktere}} gutxienez eduki behar dituzte.',
'password-name-match'        => 'Zure pasahitza ezin da zure erabiltzaile-izen bera izan.',
'password-login-forbidden'   => 'Erabiltzaile izen eta pasahitz hau erabiltzea debekaturik dago.',
'mailmypassword'             => 'Pasahitza berria e-postaz bidali',
'passwordremindertitle'      => 'Pasahitzaren gogorarazpena {{SITENAME}}(e)tik',
'passwordremindertext'       => 'Norbaitek (ziurrenik zuk, $1 IP helbidetik) pasahitz berri bat
eskatu du {{SITENAME}}(r)ako ($4). Momentu honetan erabiltzeko "$2" lankidearentzat
pasahitza sortu da eta "$3"(r)a aldatu da. Hau zuk eginiko saiakuntza bazen,
orain saioa hasi beharko duzu zure pasahitza berria aukeratzeko. Zure aldi baterako pasahitzak {{PLURAL:$5|egun baterako|$5 egunetarako}} baino ez du balio izango.

Beste norbaitek eskari hau egin bazuen, edo zure pasahitza gogoratu baduzu,
eta ez baduzu aldatu nahi, mezu honetan irakurritakoari jaramonik ez egin
eta aurretik zenuen pasahitza erabiltzen jarrai ezazu.',
'noemail'                    => 'Ez dago "$1" erabiltzailearen e-posta helbiderik gordeta.',
'noemailcreate'              => 'Balioduna den e-posta helbidea eman behar duzu',
'passwordsent'               => 'Pasahitz berria bidali da "$1" erabiltzailearen e-posta helbidera.
Mesedez, saioa hasi jasotakoan.',
'blocked-mailpassword'       => 'Zure IP helbidea aldaketak egiteko blokeatuta dago, eta beraz ezin da pasahitza berreskuratzeko aukera erabili.',
'eauthentsent'               => 'Egiaztapen mezu bat bidali da zehaztutako e-posta helbidera.
Helbide horretara beste edozein mezu bidali aurretik, bertan azaltzen diren argibideak jarraitu behar dituzu, e-posta hori zurea dela egiaztatzeko.',
'throttled-mailpassword'     => 'Pasahitz gogorarazle bat bidali da jada azken {{PLURAL:$1|orduan|$1 orduetan}}.
Bandalismoa saihesteko pasahitz eskaera bat baino ezin da egin {{PLURAL:$1|orduan|$1 orduan}} behin.',
'mailerror'                  => 'Errorea mezua bidaltzerakoan: $1',
'acct_creation_throttle_hit' => 'Sentitzen dugu, {{PLURAL:$1|erabiltzaile kontu bat sortu duzu|$1 erabiltzaile kontu sortu dituzu}} dagoeneko.
Ondorioz, ezin duzu kontu gehiago sortu.',
'emailauthenticated'         => 'Zure e-posta helbidea autentifikatu da $2an $3(e)tan.',
'emailnotauthenticated'      => 'Zure posta helbidea egiaztatu gabe dago. Ez da mezurik bidaliko hurrengo ezaugarrientzako.',
'noemailprefs'               => 'Zehaztu e-posta helbide bat ezaugarri hauek erabili ahal izateko.',
'emailconfirmlink'           => 'Egiaztatu zure e-posta helbidea',
'invalidemailaddress'        => 'Ezin da e-posta helbide hori ontzat eman baliogabeko formatua duela dirudielako.

Mesedez, formatu egokia duen helbide bat zehaztu, edo hutsik utzi.',
'accountcreated'             => 'Kontua sortuta',
'accountcreatedtext'         => '$1 erabiltzaile kontua sortu egin da.',
'createaccount-title'        => '{{SITENAME}}-rako kontua sortu',
'createaccount-text'         => 'Norbaitek zure e-postarekin kontu bat sortu du {{SITENAME}}(e)n ($4) "$2" izenarekin eta "$3" pasahitzarekin.
Orain bertan sar zaitezke eta zure pasahitza aldatu.

Kontu honen sorrera akats bat dela uste baduzu mezu honi ez diozu zertan jaramonik egin.',
'usernamehasherror'          => 'Erabiltzaile-izenak ezin du kuxin-karaktererik eduki',
'login-throttled'            => 'Saioa hasteko saiakera gehiegi egin berri dituzu.
Berriro saiatu aurretik itxaron ezazu, mesedez.',
'loginlanguagelabel'         => 'Hizkuntza: $1',
'suspicious-userlogout'      => 'Saioa amaitzeko egin duzun eskaria ukatu da. Izan ere, ematen du eskari hori gaizki dabilen nabigatzaile edo cache proxy batek bidali duela.',

# E-mail sending
'php-mail-error-unknown' => 'PHPren mail() funtzioan arazo ezezagun bat egon da.',

# Password reset dialog
'resetpass'                 => 'Pasahitza aldatu',
'resetpass_announce'        => 'E-postaz jasotako kode tenporal baten bidez saioa hasi duzu. Saioa hasierarekin jarraitzeko, pasahitz berri bat definitu beharra daukazu:',
'resetpass_text'            => '<!-- Testua hemen idatzi -->',
'resetpass_header'          => 'Pasahitza aldatu',
'oldpassword'               => 'Pasahitz zaharra:',
'newpassword'               => 'Pasahitz berria:',
'retypenew'                 => 'Pasahitz berria berriz idatzi:',
'resetpass_submit'          => 'Pasahitza definitu eta saioa hasi',
'resetpass_success'         => 'Zure pasahitza aldatu egin da! Saioa hasten...',
'resetpass_forbidden'       => 'Ezin dira pasahitzak aldatu',
'resetpass-no-info'         => 'Orrialde honetara zuzenean sartzeko izena eman behar duzu.',
'resetpass-submit-loggedin' => 'Pasahitza aldatu',
'resetpass-submit-cancel'   => 'Utzi',
'resetpass-wrong-oldpass'   => 'Behin-behineko edo oraintxuko pasahitza ez da baliagarria.
Agian dagoeneko ondo aldatu duzu zure pasahitza edo behin-behineko pasahitza bat eskatu duzu.',
'resetpass-temp-password'   => 'Behin-behineko pasahitza:',

# Edit page toolbar
'bold_sample'     => 'Testu beltza',
'bold_tip'        => 'Testu beltza',
'italic_sample'   => 'Testu etzana',
'italic_tip'      => 'Testu etzana',
'link_sample'     => 'Loturaren izenburua',
'link_tip'        => 'Barne lotura',
'extlink_sample'  => 'http://www.example.com loturaren izenburua',
'extlink_tip'     => 'Kanpo lotura (gogoratu http:// aurrizkia)',
'headline_sample' => 'Goiburuko testua',
'headline_tip'    => '2. mailako goiburukoa',
'math_sample'     => 'Formula hemen idatzi',
'math_tip'        => 'Formula matematikoa (LaTeX)',
'nowiki_sample'   => 'Formatu gabeko testua hemen idatzi',
'nowiki_tip'      => 'Ez egin jaramonik wiki formatuari',
'image_sample'    => 'Adibidea.jpg',
'image_tip'       => 'Txertatutako irudia',
'media_sample'    => 'Adibidea.ogg',
'media_tip'       => 'Media fitxategi lotura',
'sig_tip'         => 'Zure sinadura data eta orduarekin',
'hr_tip'          => 'Lerro horizontala (gutxitan erabili)',

# Edit pages
'summary'                          => 'Laburpena:',
'subject'                          => 'Izenburua:',
'minoredit'                        => 'Hau aldaketa txikia da',
'watchthis'                        => 'Orrialde hau jarraitu',
'savearticle'                      => 'Gorde orrialdea',
'preview'                          => 'Aurrebista erakutsi',
'showpreview'                      => 'Aurrebista erakutsi',
'showlivepreview'                  => 'Zuzeneko aurrebista',
'showdiff'                         => 'Aldaketak erakutsi',
'anoneditwarning'                  => "'''Oharra:''' Ez duzu saioa hasi. Zure IP helbidea orrialde honetako historian gordeko da.",
'anonpreviewwarning'               => "''Ez duzu saioa hasi. Gordez gero, zure IP helbidea grabatuko da orri honen edizio historian.''",
'missingsummary'                   => "'''Gogorarazpena:''' Ez duzu aldaketa laburpen bat zehaztu. Berriz ere gordetzeko aukeratzen baduzu, laburpen mezurik gordeko da.",
'missingcommenttext'               => 'Mesedez, iruzkin bat idatzi jarraian.',
'missingcommentheader'             => "'''Oharra:''' Ez duzu iruzkin honetarako gairik edo goiburukorik ezarri. «{{int:Savearticle}}» klikatzen baduzu, hutsune horrekin gordeko da.",
'summary-preview'                  => 'Laburpenaren aurreikuspena:',
'subject-preview'                  => 'Gaia/Izenburuaren aurreikuspena:',
'blockedtitle'                     => 'Erabiltzailea blokeatuta dago',
'blockedtext'                      => "'''Zure erabiltzaile izena edo IP helbidea blokeaturik dago.'''

$1 administratzaileak ezarri du blokeoa.
Emandako arrazoia hau da: ''$2''.

* Blokeoaren hasiera: $8
* Blokeoaren bukaera: $6
* Blokeatua: $7

Blokeoari buruz eztabaidatzeko, jo ezazu $1 administratzailearengana edo beste [[{{MediaWiki:Grouppage-sysop}}|administratzaile]] batengana.
«Bidali mezu elektronikoa lankide honi» tresna erabili ahal izateko, ezinbestekoa da zure [[Special:Preferences|hobespenetan]] baliozkoa den helbide elektroniko bat emanda izatea, eta tresna hori erabiltzeko aukera zuri blokeatu ez izana.
Orain duzun IP helbidea $3 da, eta blokeoaren zenbakia #$5 da.
Eman itzazu datu hauek guztiak, blokeoari buruzko edozein eskaera egitean.",
'autoblockedtext'                  => "Zure IP helbidea automatikoki blokeaturik dago, $1 administratzaileak blokeatutako beste wikilari batek erabili zuelako. Emandako arrazoia hau da:

:''$2''

* Blokeoaren hasiera: $8
* Blokeoaren bukaera: $6
* Blokeatua: $7

Blokeoari buruz eztabaidatzeko, jo ezazu $1 administratzailearengana edo beste [[{{MediaWiki:Grouppage-sysop}}|administratzaile]] batengana.

«Bidali mezu elektronikoa lankide honi» tresna erabili ahal izateko, ezinbestekoa da zure [[Special:Preferences|hobespenetan]] baliozkoa den helbide elektroniko bat emanda izatea, eta tresna hori erabiltzeko aukera zuri blokeatu ez izana.

Orain duzun IP helbidea $3 da, eta blokeoaren zenbakia #$5 da.

Eman itzazu datu hauek guztiak, blokeoari buruzko edozein eskaera egitean.",
'blockednoreason'                  => 'ez da arrazoirik zehaztu',
'blockedoriginalsource'            => "Jarraian ikus daiteke '''$1'''(r)en kodea:",
'blockededitsource'                => "Jarraian ikus daitezke '''$1'''(e)n egin dituzun aldaketak:",
'whitelistedittitle'               => 'Saioa hastea beharrezkoa da aldaketak egiteko',
'whitelistedittext'                => '$1 behar duzu orrialdeak aldatu ahal izateko..',
'confirmedittext'                  => 'Orrialdeetan aldaketak egin aurretik zure e-posta helbidea egiaztatu beharra daukazu. Mesedez, zehaztu eta egiaztatu zure e-posta helbidea [[Special:Preferences|hobespenetan]].',
'nosuchsectiontitle'               => 'Atala ez da aurkitu',
'nosuchsectiontext'                => 'Existitzen ez den atala editatzen saiatu zara.
Baliteke orrialdea begiratzen zenuen bitartean norbaitek ezabatu edo izenburua aldatu izana.',
'loginreqtitle'                    => 'Saioa hastea beharrezkoa',
'loginreqlink'                     => 'saioa hasi',
'loginreqpagetext'                 => 'Beste orrialde batzuk ikusteko $1 beharra daukazu..',
'accmailtitle'                     => 'Pasahitza bidali da.',
'accmailtext'                      => "[[User talk:$1|$1]]-entzako ausaz sortutako pasahitza $2-(r)a bidali da.

Kontu berri honentzako pasahitza edozein unetan alda daiteke ''[[Special:ChangePassword|pasahitz aldaketa]]'' orrian, saioa hasi ondoren.",
'newarticle'                       => '(Berria)',
'newarticletext'                   => "Orrialde hau ez da existitzen oraindik. Orrialde sortu nahi baduzu, beheko koadroan idazten hasi zaitezke (ikus [[{{MediaWiki:Helppage}}|laguntza orrialdea]] informazio gehiagorako). Hona nahi gabe etorri bazara, nabigatzaileko '''atzera''' botoian klik egin.",
'anontalkpagetext'                 => "----''Orrialde hau konturik sortu ez edo erabiltzen ez duen erabiltzaile anonimo baten eztabaida orria da.
Bere IP helbidea erabili beharko da beraz identifikatzeko.
Erabiltzaile batek baino gehiagok IP bera erabil dezakete ordea.
Erabiltzaile anonimoa bazara eta zurekin zerikusirik ez duten mezuak jasotzen badituzu, mesedez [[Special:UserLogin/signup|Izena eman]] edo [[Special:UserLogin|saioa hasi]] etorkizunean horrelakoak gerta ez daitezen.''",
'noarticletext'                    => 'Oraindik ez dago testurik orrialde honetan.
Beste orrialde batzuetan [[Special:Search/{{PAGENAME}}|bilatu dezakezu izenburu hau]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} bilatu lotutako logak],
edo [{{fullurl:{{FULLPAGENAME}}|action=edit}} berau aldatu ere egin dezakezu]</span>.',
'noarticletext-nopermission'       => 'Une honetan ez dago texturik orri honetan.
Beste orrietan [[Special:Search/{{PAGENAME}}|testua bilatu dezakezu]],
edo <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} erlazionatutako erregistroak ikusi]</span>.',
'userpage-userdoesnotexist'        => '"$1" lankidea ez dago erregistatuta. Mesedez, konprobatu orri hau editatu/sortu nahi duzun.',
'userpage-userdoesnotexist-view'   => '"$1" erabiltzaile-kontua ez dago erregistraturik.',
'blocked-notice-logextract'        => 'Erabiltzaile hau blokeatuta dago une honetan.
Azken blokeoaren erregistroa ageri da behean, erreferentzia gisa:',
'clearyourcache'                   => "'''Oharra:''' Gorde ondoren zure nabigatzailearen katxea ekidin beharko duzu aldaketak ikusteko.
* '''Firefox / Safari:''' ''Shift'' tekla sakatu birkargatzeko momentuan, edo ''Ctrl-Shift-R'' sakatu (''⌘-R''' Mac baten)
* '''Google Chrome:''' ''Ctrl-Shift-R'' sakatu (''⌘-R'' Mac batean)
* '''Internet Explorer:''' ''Ctrl'' tekla sakatu birkargatzeko momentuan, edo ''Ctrl-F5'' sakatu
* '''Konqueror:''': Birkargatzeko klik egin, edo F5 sakatu, besterik ez
* '''Opera''' erabiltzaileek ''Tresnak → Hobespenak'' atalera jo eta katxea garbitzeko aukera hautatu",
'usercssyoucanpreview'             => "'''Laguntza:''' Zure CSS berria gorde aurretik probatzeko \"{{int:showpreview}}\" botoia erabili.",
'userjsyoucanpreview'              => "'''Laguntza:''' Zure JS berria gorde aurretik probatzeko \"{{int:showpreview}}\" botoia erabili.",
'usercsspreview'                   => "'''Ez ahaztu zure CSS kodea aurreikusten zabiltzala.'''
'''Oraindik gorde gabe dago!'''",
'userjspreview'                    => "'''Gogoratu zure JavaScript kodea probatu/aurreikusten zabiltzala, oraindik ez da gorde!'''",
'sitecsspreview'                   => "'''Ez ahaztu zure CSS kodea aurreikusten zabiltzala.'''
'''Oraindik gorde gabe dago!'''",
'sitejspreview'                    => "'''Gogoratu zure JavaScript kodea probatu/aurreikusten zabiltzala'''
'''Oraindik ez da gorde!'''",
'userinvalidcssjstitle'            => "'''Oharra:''' Ez da \"\$1\" itxura existitzen. Kontuan izan .css eta .js fitxategi pertsonalizatuen izenak letra xehez idatzi behar direla; adibidez, {{ns:user}}:Adibide/vector.css, eta ez {{ns:user}}:Adibide/Vector.css.",
'updated'                          => '(Eguneratua)',
'note'                             => "'''Oharra:'''",
'previewnote'                      => "'''Gogoratu hau aurreikusketa bat dela, beraz gorde egin beharko duzu!'''",
'previewconflict'                  => 'Aurreikuspenak aldaketen koadroan idatzitako testua erakusten du, gorde ondoren agertuko den bezala.',
'session_fail_preview'             => "'''Sentitzen dugu! Ezin izan da zure aldaketa prozesatu, saioko datu batzuen galera dela-eta. Mesedez, saiatu berriz. Arazoak jarraitzen badu, saiatu saioa amaitu eta berriz hasten.'''",
'session_fail_preview_html'        => "'''Sentitzen dugu! Ezin izan dugu zure aldaketa burutu, saio datu galera bat medio.'''

''Wiki honek HTML kodea onartzen duenez, aurreikuspena ezgaituta dago JavaScript erasoak saihestu asmoz.''

'''Aldaketa saiakera hau zuzena baldin bada, saiatu berriro mesedez. Arazoak jarraitzen badu, saiatu saioa itxi eta berriz hasten.'''",
'token_suffix_mismatch'            => "'''Zure aldaketa ezeztatua izan da zure bezeroak puntuazio-karaktereak itxuragabetu dituelako.
Aldaketa ezeztatua izan da testuaren galtzea galarazteko.
Hau batzuetan gertatzen da buggyan oinarritutako web proxy zerbitzua erabiltzean.'''",
'editing'                          => '$1 aldatzen',
'editingsection'                   => '$1 aldatzen (atala)',
'editingcomment'                   => '$1 aldatzen (atal berria)',
'editconflict'                     => 'Aldaketa gatazka: $1',
'explainconflict'                  => "Zu orrialdea aldatzen hasi ondoren beste norbaitek ere aldaketak egin ditu.
Goiko testu koadroan ikus daiteke orrialdeak uneotan duen edukia.
Zure aldaketak beheko testu koadroan ikus daitezke.
Zure testua dagoenarekin elkartu beharko duzu.
Orrialdea gordetzeko erabakitzen duzun unean goiko koadroko edukia '''bakarrik''' gordeko da.",
'yourtext'                         => 'Zure testua',
'storedversion'                    => 'Gordetako bertsioa',
'nonunicodebrowser'                => "'''OHARRA: Zure nabigatzailea ez dator Unicode arauarekin bat. Artikuluak modu seguruan aldatu ahal izateko beste sistema bat gaitu da: ASCII ez diren karaktereak kode hamaseitar bezala agertuko dira aldaketa koadroan.'''",
'editingold'                       => "'''KONTUZ: Artikulu honen bertsio zahar bat aldatzen ari zara. Gorde egiten baduzu, azkenengo aldaketa baino lehenagoko aldakuntzak, ezabatuak izango dira.'''",
'yourdiff'                         => 'Ezberdintasunak',
'copyrightwarning'                 => "Kontuan izan {{SITENAME}}(e)n egindako ekarpen guztiak $2 baldintzapean argitaratzen direla (ikus $1 informazio gehiagorako). Zure testua banatzeko baldintza hauekin ados ez bazaude, ez ezazu bidali.<br />
Era berean, bidaltzen ari zaren edukia zuk zeuk idatzitakoa dela edo jabetza publikoko edo baliabide aske batetik kopiatu duzula zin egin ari zara.
'''EZ BIDALI BAIMENIK GABEKO COPYRIGHTDUN EDUKIRIK!'''",
'copyrightwarning2'                => "Mesedez, kontuan izan {{SITENAME}}(e)n egindako ekarpen guztiak besteek aldatu edo ezabatu ditzaketela. Ez baduzu besteek aldaketak egitea nahi, ez ezazu bidali.<br />
Era berean, bidaltzen ari zaren edukia zuk zeuk idatzitakoa dela edo jabetza publikoko edo baliabide aske batetik kopiatu duzula zin egin ari zara (ikus $1 informazio gehiagorako).
'''EZ BIDALI BAIMENIK GABEKO COPYRIGHTDUN EDUKIRIK!'''",
'longpageerror'                    => "'''ERROREA: Bidali duzun testuak $1 kilobyteko luzera du, eta $2 kilobyteko maximoa baino luzeagoa da. Ezin da gorde.'''",
'readonlywarning'                  => "'''OHARRA: Datu-basea blokeatu egin da mantenu lanak burutzeko, beraz ezingo dituzu orain zure aldaketak gorde. Testua fitxategi baten kopiatu dezakezu, eta beranduago erabiltzeko gorde.

Blokeatu zuen administratzaileak honako azalpena eman zuen: $1'''",
'protectedpagewarning'             => "'''Oharra:  Orri hau blokeatua dago administratzaileek soilik eraldatu ahal dezaten.'''
Azken erregistroa ondoren ikusgai dago erreferentzia gisa:",
'semiprotectedpagewarning'         => "'''Oharra''': Orrialde hau erregistratutako erabiltzaileek bakarrik aldatzeko babestuta dago.
Erregistroko azken sarrera azpian jartzen da erreferentzia gisa:",
'cascadeprotectedwarning'          => "'''Oharra:''' Orrialde hau blokeatua izan da eta administratzaileek baino ez dute berau aldatzeko ahalmena, honako {{PLURAL:$1|orrialdeko|orrialdeetako}} kaskada-babesean txertatuta dagoelako:",
'titleprotectedwarning'            => "'''Oharra: Orrialde hau blokeatuta dago eta bakarrik [[Special:ListGroupRights|erabiltzaile batzuek]] sortu dezakete.'''
Azken erregistroko sarrera ematen da azpian erreferentzia gisa:",
'templatesused'                    => 'Orrialde honetan erabiltzen {{PLURAL:$1|den txantiloia|diren txantiloiak}}:',
'templatesusedpreview'             => 'Aurreikuspen honetan erabiltzen {{PLURAL:$1|den txantiloia|diren txantiloiak}}:',
'templatesusedsection'             => 'Atal honetan erabiltzen {{PLURAL:$1|den txantiloia|diren txantiloiak}}:',
'template-protected'               => '(babestua)',
'template-semiprotected'           => '(erdi-babestua)',
'hiddencategories'                 => 'Orrialde hau {{PLURAL:$1|kategoria izkutu bateko|$1 kategoria izkutuko}} kide da:',
'edittools'                        => '<!-- Hemen jarritako testua aldaketa eta igoera formularioen azpian agertuko da. -->',
'nocreatetitle'                    => 'Orrialdeak sortzea mugatuta',
'nocreatetext'                     => 'Gune honek orrialde berriak sortzeko gaitasuna mugatu du. Atzera egin dezakezu existitzen den orrialde bat aldatzeko, edo [[Special:UserLogin|saio hasi edo kontua sortu]].',
'nocreate-loggedin'                => 'Ez daukazu orrialde berriak sortzeko baimenik.',
'sectioneditnotsupported-title'    => 'Ezin dira atalak aldatu',
'sectioneditnotsupported-text'     => 'Ezin dira atalak aldatu orrialde honetan.',
'permissionserrors'                => 'Baimen erroreak',
'permissionserrorstext'            => 'Ez duzu hori egiteko baimenik, hurrengo {{PLURAL:$1|arrazoia dela eta|arrazoiak direla eta}}:',
'permissionserrorstext-withaction' => 'Ez duzu $2 egiteko eskumenik, honako {{PLURAL:$1|arrazoia dela eta:|arrazoiak direla eta:}}',
'recreate-moveddeleted-warn'       => "'''Oharra: Lehenago ezabatutako orrialdea birsortzen ari zara.'''

Pentsatu ea orrialde hau editatzen jarraitzeak zentzurik baduen.
Hemen duzu orrialde honen ezabaketa erregistroa badaezpada ere:",
'moveddeleted-notice'              => 'Orrialde hau ezabatua izan da.
Orrialdearen ezabatze erregistroa behean agertzen da erreferentzia gisa.',
'log-fulllog'                      => 'Erregistro osoa ikusi',
'edit-hook-aborted'                => 'Gehigarriak aldaketa ezeztatu du.
Ez du azalpenik eman.',
'edit-gone-missing'                => 'Ezin da orria eguneratu. Ezabatu omen dute.',
'edit-conflict'                    => 'Aldaketa gatazka.',
'edit-no-change'                   => 'Zure edizioa baztertu da testua aldatu ez duzulako.',
'edit-already-exists'              => 'Ezin izan da orri berria sortu.
Jada existitzen da.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Adi: Orrialde honek parser funtzio deialdi oso garesti gehiegi ditu.

$2 {{PLURAL:$2|deialdi|deialdi}} baino gutxiago eduki beharko lituzke, eta orain $1 {{PLURAL:$1|dago|daude}}.',
'expensive-parserfunction-category'       => 'Parser funtzio deialdi oso garesti gehiegi dituzten orrialdeak',
'post-expand-template-inclusion-warning'  => 'Kontuz: Txantiloiak sartzen duena oso handia da.
Txantiloi batzuk ez dira erabiliko.',
'post-expand-template-inclusion-category' => 'Txantiloiaren inklusio tamaina gainditu den orrialdeak',
'post-expand-template-argument-warning'   => 'Oharra: Orri honek gutxienez txantiloi eztabaida bat du, zein luzeegia den.
Eztabaidak aipatu gabe utzi dira.',
'post-expand-template-argument-category'  => 'Kontuan hartu ez diren txantiloiak dituzten orrialdeak',
'parser-template-loop-warning'            => 'Txantiloiaren itzul-biraketa aurkitu da: [[$1]]',
'parser-template-recursion-depth-warning' => 'Txantiloaren rekurtsio sakoneraren muga gainditu da ($1)',
'language-converter-depth-warning'        => 'Hizkuntza-bihurgailuaren sakonerak ($1) muga gainditu du',

# "Undo" feature
'undo-success' => 'Aldaketa desegin daiteke.
Mesedez beheko alderaketa egiaztatu, egin nahi duzuna hori dela frogatzeko, eta ondoren azpiko aldaketak gorde, aldaketa desegiten amaitzeko.',
'undo-failure' => 'Ezin izan da aldaketa desegin tarteko aldaketekin gatazkak direla-eta.',
'undo-norev'   => 'Aldaketa ezin da desegin ez delako existitzen edo ezabatu zutelako.',
'undo-summary' => '[[Special:Contributions/$2|$2(r)en]] $1 berrikuspena desegin da ([[User talk:$2|Eztabaida]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ezin izan da kontua sortu',
'cantcreateaccount-text' => "IP helbide honetatik ('''$1''') kontu berria sortzeko aukera blokeatu du [[User:$3|$3]](e)k.

$3(e)k emandako arrazoia: ''$2''",

# History pages
'viewpagelogs'           => 'Orrialde honen erregistroak ikusi',
'nohistory'              => 'Orrialde honek ez dauka aldaketa historiarik.',
'currentrev'             => 'Oraingo berrikuspena',
'currentrev-asof'        => 'Hauxe da oraingo bertsioa, $1 data duena',
'revisionasof'           => '$1(e)ko berrikuspena',
'revision-info'          => '$2(r)en berrikusketa, ordua: $1',
'previousrevision'       => '←Berrikuspen zaharragoa',
'nextrevision'           => 'Berrikuspen berriagoa→',
'currentrevisionlink'    => 'Oraingo berrikuspena ikusi',
'cur'                    => 'orain',
'next'                   => 'hurrengoa',
'last'                   => 'azkena',
'page_first'             => 'lehena',
'page_last'              => 'azkena',
'histlegend'             => 'Diff hautapena: hautatu alderatu nahi dituzun bi bertsioak eta beheko botoian klik egin.<br />
Legenda: (orain) = oraingo bertsioarekiko ezberdintasuna,
(azkena) = aurreko bertsioarekiko ezberdintasuna, t = aldaketa txikia.',
'history-fieldset-title' => 'Historia erakutsi',
'history-show-deleted'   => 'Ezabatuak soilik',
'histfirst'              => 'Lehena',
'histlast'               => 'Azkena',
'historysize'            => '({{PLURAL:$1|byte 1|$1 byte}})',
'historyempty'           => '(hutsik)',

# Revision feed
'history-feed-title'          => 'Berrikuspenen historia',
'history-feed-description'    => 'Wikiko orrialde honen berrikuspenen historia',
'history-feed-item-nocomment' => 'nork: $1 noiz: $2',
'history-feed-empty'          => 'Eskatutako orrialdea ez da existitzen. Baliteke wikitik ezabatu edo izenez aldatu izana. Saiatu [[Special:Search|wikian zerikusia duten orrialdeak bilatzen]].',

# Revision deletion
'rev-deleted-comment'         => '(aldaketa laburpena ezabatu da)',
'rev-deleted-user'            => '(erabiltzailea ezabatu da)',
'rev-deleted-event'           => '(log ekintza ezabatu da)',
'rev-deleted-user-contribs'   => '[lankide izena edo Ip helbidea ezabatua - aldatu ezkutapena ekarpenetatik]',
'rev-deleted-text-permission' => "Orrialdearen berrikuspen hau '''ezabatua''' izan da.
Xehetasunak [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ezabaketa erregistroan] ikus daitezke.",
'rev-deleted-text-unhide'     => "Orriaren bertsio hau '''ezabatu''' da.
Xehetasunak ikusgai daude [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ezabatze erregistroan].
Administratzailea zarenez, oraindik [$1 bertsio hau ikus dezakezu], nahi izanez gero.",
'rev-suppressed-text-unhide'  => "Orriaren bertsio hau '''ezeztatu''' da.
Xehetasunak ikusgai daude [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ezeztatze erregistroan].
Administratzailea zarenez, oraindik [$1 bertsio hau ikus dezakezu], nahi izanez gero.",
'rev-deleted-text-view'       => "Orrialdearen berrikuspen hau '''ezabatua''' izan da.
Zuk ikusteko aukera daukazu; xehetasunak [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ezabaketa erregistroan] ikus ditzakezu.",
'rev-suppressed-text-view'    => "Berrikuspen hau '''ezabatua''' izan da.
Administratzaile bezala ikus dezakezu; xehetasun gehiagorako [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ezabapen erregistrora joan].",
'rev-deleted-no-diff'         => "Ezin duzu ezberdintasun hau ikusi, berrikuspenetako bat '''ezabatua''' izan delako.
Xehetasunak [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ezabaketa erregistroan] aurki ditzakezu.",
'rev-suppressed-no-diff'      => "Ezin duzu ezberdintasunik ikusi berrikuspenen bat '''ezabatua''' izan delako.",
'rev-deleted-unhide-diff'     => "diff honen bertsioetako bat '''ezabatu''' da.
Xehetasunak ikusgai daude [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ezabatze erregistroan].
Administratzailea zarenez, oraindik [$1 diff hau ikus dezakezu], nahi izanez gero.",
'rev-suppressed-unhide-diff'  => "diff honen bertsioetako bat '''ezeztatu''' da.
Xehetasunak ikusgai daude [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ezeztatze erregistroan].
Administratzailea zarenez, oraindik [$1 diff hau ikus dezakezu], nahi izanez gero.",
'rev-deleted-diff-view'       => "diff honen bertsioetako bat '''ezabatu''' da.
Administratzailea zarenez, diff hau ikus dezakezu. Xehetasunak ikusgai daude [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ezabatze erregistroan].",
'rev-suppressed-diff-view'    => "diff honen bertsioetako bat '''ezeztatu''' da.
Administratzailea zarenez, diff hau ikus dezakezu. Xehetasunak ikusgai daude [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ezabaketa erregistroan].",
'rev-delundel'                => 'erakutsi/ezkutatu',
'rev-showdeleted'             => 'erakutsi',
'revisiondelete'              => 'Berrikuspenak ezabatu/leheneratu',
'revdelete-nooldid-title'     => 'Helburu berrikuspenik ez',
'revdelete-nooldid-text'      => 'Ez d(it)uzu eragiketa hau burutzeko helburu berrikuspena(k) zehaztu.',
'revdelete-nologtype-title'   => 'Log motarik ez da zehaztu',
'revdelete-nologtype-text'    => 'Ez duzu log motarik zehaztu ekintza hori burutzeko.',
'revdelete-nologid-title'     => 'Log sarrera okerra',
'revdelete-nologid-text'      => 'Ez duzu log helburu ekintzarik zehaztu funtzioa betetzeko, edo zehaztutako sarrera ez da existitzen.',
'revdelete-no-file'           => 'Zehazturiko fitxategia ez da existitzen.',
'revdelete-show-file-confirm' => '"<nowiki>$1</nowiki>" fitxategiaren bertsio ezabatua (eguna: $2; ordua: $3) ikusi nahi duzu?',
'revdelete-show-file-submit'  => 'Bai',
'revdelete-selected'          => "'''{{PLURAL:$2|[[:$1]](r)en hautatutako berrikuspena:|[[:$1]](r)en hautatutako berrikuspenak}}'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Aukeratutako log gertakaria|Aukeratutako log gertakariak}}:'''",
'revdelete-text'              => "'''Ezabatutako berrikuspenek orrialdearen historian agertzen jarraituko dute, baina bere edukiak ez dira publikoki eskuratu ahal izango.'''
{{SITENAME}}ko administratzaileek ezkutuko eduki hau ikusteko aukera izango dute, eta baita leheneratzeko ere, gunearen arduradunek beste mugapenen bat ezartzen ez badute behintzat.",
'revdelete-confirm'           => 'Baiezta ezazu hori dela zure asmoa, ulertzen dituzula ondorioak, eta [[{{MediaWiki:Policy-url}}|irizpideak]] errespetatuz egiten ari zarela hau.',
'revdelete-suppress-text'     => "Ezabaketa '''bakarrik''' arrazoi hauek direla eta erabili beharko litzateke:
* Informazio pertsonal desegokia
*: ''etxeko helbideak eta telefono zenbakiak, segurtasun sozial zenbakiak, etab.''",
'revdelete-legend'            => 'Berrikuspen mugapenak ezarri:',
'revdelete-hide-text'         => 'Berrikuspenaren testua ezkutatu',
'revdelete-hide-image'        => 'Fitxategiaren edukia ezkutatu',
'revdelete-hide-name'         => 'Helburua eta ekintza izkutatu',
'revdelete-hide-comment'      => 'Aldaketaren iruzkina ezkutatu',
'revdelete-hide-user'         => 'Egilearen erabiltzaile izena/IPa ezkutatu',
'revdelete-hide-restricted'   => 'Mugapen hauek administratzaileei zein besteei aplikatu',
'revdelete-radio-same'        => '(ez aldatu)',
'revdelete-radio-set'         => 'Bai',
'revdelete-radio-unset'       => 'Ez',
'revdelete-suppress'          => 'Administratzaileen eta bestelakoen datuak kendu',
'revdelete-unsuppress'        => 'Berrezarritako aldaketen mugak kendu',
'revdelete-log'               => 'Arrazoia:',
'revdelete-submit'            => 'Hautatutako {{PLURAL:$1|berrikuspenari|berrikuspenei}} aplikatu',
'revdelete-logentry'          => '[[$1]] wikilariak egindako berriskupenaren ikusgaitasuna aldatu da',
'logdelete-logentry'          => '[[$1]] zerrendako gertakarien ikusgaitasuna aldatu da',
'revdelete-success'           => "'''Berrikuspenen ikusgarritasuna eguneratu da.'''",
'revdelete-failure'           => "'''Ezin da berrikuspenaren ikuspena eguneratu:'''
$1",
'logdelete-success'           => "'''Log ikusgarritasuna ondo ezarri da.'''",
'logdelete-failure'           => "'''Erregistroaren ikusgaitasuna ezin da honela ezarri:'''
$1",
'revdel-restore'              => 'Aldatu ikusgaitasuna',
'revdel-restore-deleted'      => 'ezabatutako berraztertzeak',
'revdel-restore-visible'      => 'ageriko berrikuspenak',
'pagehist'                    => 'Orriaren historia',
'deletedhist'                 => 'Ezabatutako historia',
'revdelete-content'           => 'edukia',
'revdelete-summary'           => 'aldaketaren laburpena',
'revdelete-uname'             => 'lankide izena',
'revdelete-restricted'        => 'administratzaileentzako mugak ezarri dira',
'revdelete-unrestricted'      => 'administratzaileentzako mugak kendu dira',
'revdelete-hid'               => '$1 ezkutatu da',
'revdelete-unhid'             => '$1 agerrarazi da',
'revdelete-log-message'       => '$1 {{PLURAL:$2|berrikuste baterako|$2 berrikustetarako}}',
'logdelete-log-message'       => '$1 {{PLURAL:$2|gertakari baterako|$2 gertakaritarako}}',
'revdelete-hide-current'      => 'Errorea, $1 $2 data duen elementua ezkutatzean: hau da oraingo bertsioa.
Ezin da ezkutatu.',
'revdelete-show-no-access'    => 'Errorea, $1 $2 data duen elementua erakustean: elementu hau «mugatua» dela markatu da.
Ezin duzu atzitu.',
'revdelete-modify-no-access'  => 'Errorea, $1 $2 data duen elementua aldatzean: elementu hau «mugatua» dela markatu da.
Ezin duzu atzitu.',
'revdelete-modify-missing'    => 'Errorea, $1 identifikazioa duen elementua aldatzean: datu basean ez da ageri.',
'revdelete-no-change'         => "'''Abisua:''' $1 $2 data duen elementuak jadanik bazituen eskatutako ikusgaitasun ezarpenak.",
'revdelete-concurrent-change' => 'Errorea, $1 $2 data duen elementua aldatzean: badirudi haren egoera aldatu duela nor edo nork, zu aldatzen saiatzen ari zinela.
Begira itzazu erregistroak.',
'revdelete-reason-dropdown'   => '*Ezabatzeko ohiko arrazoiak
**Egile eskubideen urraketa
**Informazio pertsonal desegokia
**Iraingarria izan daitekeen informazioa',
'revdelete-otherreason'       => 'Bestelako arrazoia:',
'revdelete-reasonotherlist'   => 'Beste arrazoi bat',
'revdelete-edit-reasonlist'   => 'Ezabaketa arrazoiak aldatu',
'revdelete-offender'          => 'Bertsioaren egilea:',

# Suppression log
'suppressionlog'     => 'Ezabatze loga',
'suppressionlogtext' => 'Azpian administratzaileek ezkutatutako edukia duten ezabaketa eta blokeoen zerrenda dago.
Ikusi [[Special:IPBlockList|IP blokeoen zerrenda]] orain dauden blokeoak ikusi ahal izateko.',

# History merging
'mergehistory'                     => 'Orrialdeen historiak bateratu',
'mergehistory-header'              => 'Orri honek iturri baten historiaren berrikuspenak bateratzea ahalbidetzen du, orri berri batean.
Ziurtatu aldaketa honek ez duela orri historikoaren jarraipena etengo.',
'mergehistory-box'                 => 'Bi orrialderen berrikuspenak bateratu:',
'mergehistory-from'                => 'Jatorrizko orrialdea:',
'mergehistory-into'                => 'Helburu orrialdea:',
'mergehistory-list'                => 'Batu daitekeen aldaketen historia',
'mergehistory-merge'               => '[[:$1]]-(e)n ondorengo berrikuspena [[:$2]]-(r)ekin bateratu daiteke.
Zutabe botoia erabili zehaztutako orduan sortutako berrikuspenak bakarrik bateratzeko.
Kontura zaitez nabigazio loturek, zutabea ezabatu dezakela.',
'mergehistory-go'                  => 'Aldaketa bateragarriak erakutsi',
'mergehistory-submit'              => 'Berrikuspenak bateratu',
'mergehistory-empty'               => 'Ezin da berrikuspenik bateratu',
'mergehistory-success'             => '[[:$1]](e)ko {{PLURAL:$3|berrikuspen|berrikuspen}} bateratu egin dira [[:$2]](e)n.',
'mergehistory-fail'                => 'Ezin izan da historia bateratu; egiaztatu orrialde eta denbora parametroak.',
'mergehistory-no-source'           => 'Ez da $1 jatorrizko orrialdea existitzen.',
'mergehistory-no-destination'      => 'Ez da $1 helburu orrialdea existitzen.',
'mergehistory-invalid-source'      => 'Jatorrizko orrialdea baliozko izenburua izan behar da.',
'mergehistory-invalid-destination' => 'Helburu orrialdea baliozko izenburua izan behar da.',
'mergehistory-autocomment'         => '[[:$1]] [[:$2]]rekin batu da',
'mergehistory-comment'             => '[[:$1]] [[:$2]]rekin batu da: $3',
'mergehistory-same-destination'    => 'Jatorri eta helmugako orriak ezin dira berdinak izan',
'mergehistory-reason'              => 'Arrazoia:',

# Merge log
'mergelog'           => 'Bateratze erregistroa',
'pagemerge-logentry' => '[[$1]] [[$2]](r)ekin batu da ($3(e)raino berrikuspenak)',
'revertmerge'        => 'Bereiztu',
'mergelogpagetext'   => 'Jarraian dagoen zerrendak orrialde baten historiatik beste batera egindako azken bateratzeak erakusten ditu.',

# Diffs
'history-title'            => '"$1" orrialdearen historia laburpena',
'difference'               => '(Bertsioen arteko ezberdintasunak)',
'difference-multipage'     => '(Orrialdeen arteko ezberdintasunak)',
'lineno'                   => '$1. lerroa:',
'compareselectedversions'  => 'Hautatutako bertsioak alderatu',
'showhideselectedversions' => 'Erakutsi/izkutatu aukeratutako berrikuspenak',
'editundo'                 => 'desegin',
'diff-multi'               => '({{PLURAL:$1|Ez da tarteko berrikuspen bat|Ez dira tarteko $1 berrikuspen}} erakusten {{PLURAL:$2|lankide batena|$2 lankiderena}}.)',

# Search results
'searchresults'                    => 'Bilaketaren emaitzak',
'searchresults-title'              => '"$1(e)rako" emaitzak bilatu',
'searchresulttext'                 => '{{SITENAME}}(e)n bilaketak egiteko informazio gehiagorako, ikus [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => '\'\'\'[[:$1]]\'\'\' bilatu duzu ([[Special:Prefixindex/$1|"$1" hasten diren orri guztiak]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" orrirako lotura duten orri guztiak]])',
'searchsubtitleinvalid'            => "'''$1''' bilatu duzu",
'toomanymatches'                   => 'Aukera gehiegi aurkitu dira, saia zaitez beste eskaera ezberdin batekin',
'titlematches'                     => 'Emaitzak artikuluen izenburuetan',
'notitlematches'                   => 'Ez dago bat datorren orrialde izenbururik',
'textmatches'                      => 'Emaitza orrialde testuetan',
'notextmatches'                    => 'Ez dago bat datorren orrialde testurik',
'prevn'                            => 'aurreko {{PLURAL:$1|$1}}ak',
'nextn'                            => 'hurrengo {{PLURAL:$1|$1}}ak',
'prevn-title'                      => 'Aurreko {{PLURAL:$1|emaitza|emaitzak}}',
'nextn-title'                      => 'Hurrengo $1 {{PLURAL:$1|emaitza|emaitzak}}',
'shown-title'                      => 'Erakutsi {{PLURAL:$1|emaitza $1|$1 emaitza}} orrialdeko',
'viewprevnext'                     => 'Ikusi ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Bilaketa aukerak',
'searchmenu-exists'                => "'''\"[[:\$1]]\" izena duen orrialde bat badago wiki honetan'''",
'searchmenu-new'                   => "'''\"[[:\$1]]\" orrialde sortu wiki honetan!'''",
'searchhelp-url'                   => 'Help:Laguntza',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Aurrizki hau duten orrialdeetatik nabigatu]]',
'searchprofile-articles'           => 'Eduki-orriak',
'searchprofile-project'            => 'Laguntza eta Proiektu-orriak',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Guztia',
'searchprofile-advanced'           => 'Aurreratua',
'searchprofile-articles-tooltip'   => '$1(e)n bilatu',
'searchprofile-project-tooltip'    => '$1(e)n bilatu',
'searchprofile-images-tooltip'     => 'Fitxategiak bilatu',
'searchprofile-everything-tooltip' => 'Bilatu eduki guztian (lankide orrialdeak barne)',
'searchprofile-advanced-tooltip'   => 'Lankideen izen zehatzetan bilatu',
'search-result-size'               => '$1 ({{PLURAL:$2|hitz bat|$2 hitz}})',
'search-result-category-size'      => '{{PLURAL:$1|kide 1|$1 kide}} ({{PLURAL:$2|azpikategoria 1|$2 azpikategoria}}, {{PLURAL:$3|fitxategi 1|$3 fitxategi}})',
'search-result-score'              => 'Garrantzia: %$1',
'search-redirect'                  => '($1 birzuzenketa)',
'search-section'                   => '($1 atala)',
'search-suggest'                   => '$1 esan nahi zenuen',
'search-interwiki-caption'         => 'Beste proiektuak',
'search-interwiki-default'         => '$1(r)en emaitzak:',
'search-interwiki-more'            => '(gehiago)',
'search-mwsuggest-enabled'         => 'iradokizunekin',
'search-mwsuggest-disabled'        => 'ez dago gomendiorik',
'search-relatedarticle'            => 'Erlazionatua',
'mwsuggest-disable'                => 'AJAX gomendioak ezgaitu',
'searcheverything-enable'          => 'Bilatu izen-tarte guztietan',
'searchrelated'                    => 'erlazionatua',
'searchall'                        => 'guztia',
'showingresults'                   => "Jarraian {{PLURAL:$1|emaitza '''1''' ikus daiteke|'''$1''' emaitza ikus daitezke}}, #'''$2'''.etik hasita.",
'showingresultsnum'                => "Hasieran #'''$2''' duten {{PLURAL:$3|emaitza '''1'''|'''$3''' emaitza}} erakusten dira jarraian.",
'showingresultsheader'             => "{{PLURAL:$5|'''$1'''(e)tik '''$3''' emaitza|'''$1 - $2'''(e)tik '''$3''' emaitza}} '''$4'''(r)entzat",
'nonefound'                        => "'''Oharra''': Bakarrik izen-tarte batzuetan egiten da berez bilaketa.
Saia zaitez zure eskeraren aurretik ''all:'' jartzen eduki guztien artean bilatzeko (eztabaida orrialdea, txantiloiak, etab. sartuz) edo bestela erabil ezazu nahi duzun izen-tartea aurrizki gisa.",
'search-nonefound'                 => 'Ez dago eskaerarekin bat egiten duten emaitzarik.',
'powersearch'                      => 'Bilatu',
'powersearch-legend'               => 'Bilaketa aurreratua',
'powersearch-ns'                   => 'Bilatu honako izen-tartetan:',
'powersearch-redir'                => 'Birzuzenketen zerrenda',
'powersearch-field'                => 'Bilatu',
'powersearch-togglelabel'          => 'Egiaztatu:',
'powersearch-toggleall'            => 'Guztiak',
'powersearch-togglenone'           => 'Bat ere',
'search-external'                  => 'Kanpo bilaketa',
'searchdisabled'                   => '{{SITENAME}}(e)n ezgaituta dago bilaketa. Dena dela, Google erabiliz ere egin dezakezu bilaketa. Kontuan izan bertan dituzten {{SITENAME}}(e)ko emaitzak zaharkituta egon daitezkeela.',

# Quickbar
'qbsettings'               => 'Laster-barra',
'qbsettings-none'          => 'Ezein ere',
'qbsettings-fixedleft'     => 'Eskuinean',
'qbsettings-fixedright'    => 'Ezkerrean',
'qbsettings-floatingleft'  => 'Ezkerrean mugikor',
'qbsettings-floatingright' => 'Eskubian flotatzen',

# Preferences page
'preferences'                   => 'Hobespenak',
'mypreferences'                 => 'Nire hobespenak',
'prefs-edits'                   => 'Aldaketa kopurua:',
'prefsnologin'                  => 'Saioa hasi gabe',
'prefsnologintext'              => '<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} Izena eman]</span> behar duzu zure hobespenak ezartzeko.',
'changepassword'                => 'Pasahitza aldatu',
'prefs-skin'                    => 'Itxura',
'skin-preview'                  => 'Aurrebista',
'prefs-math'                    => 'Formulak',
'datedefault'                   => 'Hobespenik ez',
'prefs-datetime'                => 'Data eta ordua',
'prefs-personal'                => 'Erabiltzaile profila',
'prefs-rc'                      => 'Azken aldaketak',
'prefs-watchlist'               => 'Jarraipen zerrenda',
'prefs-watchlist-days'          => 'Jarraipen zerrendan erakutsi beharreko egun kopurua:',
'prefs-watchlist-days-max'      => 'Gehienez 7 egun',
'prefs-watchlist-edits'         => 'Jarraipen zerrendan erakutsi beharreko aldaketa kopurua:',
'prefs-watchlist-edits-max'     => 'Gehenezko zenbakia: 1000',
'prefs-watchlist-token'         => 'Jarraipen zerrendaren tokena:',
'prefs-misc'                    => 'Denetarik',
'prefs-resetpass'               => 'Pasahitza aldatu',
'prefs-email'                   => 'E-posta aukerak',
'prefs-rendering'               => 'Itxura',
'saveprefs'                     => 'Gorde',
'resetprefs'                    => 'Hasieratu',
'restoreprefs'                  => 'Konfigurazio lehenetsi guztiak berrezarri',
'prefs-editing'                 => 'Aldatzen',
'prefs-edit-boxsize'            => 'Edizio lehioaren tamaina.',
'rows'                          => 'Lerroak:',
'columns'                       => 'Zutabeak:',
'searchresultshead'             => 'Bilaketa',
'resultsperpage'                => 'Emaitza orrialdeko:',
'contextlines'                  => 'Lerro emaitzako:',
'contextchars'                  => 'Lerro bakoitzeko karaktere kopurua:',
'stub-threshold'                => '<a href="#" class="stub">stub link</a> formaturako atalasea (byteak):',
'stub-threshold-disabled'       => 'Ezgaitua',
'recentchangesdays'             => 'Aldaketa berrietan erakutsi beharreko egun kopurua:',
'recentchangesdays-max'         => '(gehienez {{PLURAL:$1|egun bat|$1 egun}})',
'recentchangescount'            => 'Erakusteko aldaketa kopurua, lehenetsita:',
'prefs-help-recentchangescount' => 'Honek azken aldaketak, orrialdeen historiak eta logak barne-biltzen ditu.',
'savedprefs'                    => 'Zure hobespenak gorde egin dira.',
'timezonelegend'                => 'Ordu-eremua:',
'localtime'                     => 'Ordu lokala:',
'timezoneuseserverdefault'      => 'Erabiltzailearen zerbitzariaren berezkoa',
'timezoneuseoffset'             => 'Beste bat (diferentzia ezarri)',
'timezoneoffset'                => 'Ezberdintasuna¹:',
'servertime'                    => 'Zerbitzariko ordua:',
'guesstimezone'                 => 'Nabigatzailetik jaso',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Artikoa',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Atlantiar Ozeanoa',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Indiar Ozeanoa',
'timezoneregion-pacific'        => 'Ozeano Barea',
'allowemail'                    => 'Beste erabiltzaileengandik e-posta mezuak jasotzea gaitu',
'prefs-searchoptions'           => 'Bilaketa aukerak',
'prefs-namespaces'              => 'Izen-tarteak',
'defaultns'                     => 'Bestela izen-tarte hauetan bilatu:',
'default'                       => 'lehenetsia',
'prefs-files'                   => 'Fitxategiak',
'prefs-custom-css'              => 'CSS pertsonalizatua',
'prefs-custom-js'               => 'JS pertsonalizatua',
'prefs-common-css-js'           => 'Azal mota guztietan elkarbanatutako CSS/JS:',
'prefs-emailconfirm-label'      => 'E-posta baieztapena:',
'prefs-textboxsize'             => 'Editatze lehioaren tamaina',
'youremail'                     => 'E-posta:',
'username'                      => 'Erabiltzaile izena:',
'uid'                           => 'Erabiltzaile zenbakia:',
'prefs-memberingroups'          => '{{PLURAL:$1|Taldeko|taldeetako}} kidea:',
'prefs-registration'            => 'Erregistratzeko unea:',
'yourrealname'                  => 'Benetako izena:',
'yourlanguage'                  => 'Hizkuntza:',
'yourvariant'                   => 'Edukiaren hizkuntza aldaera:',
'yournick'                      => 'Erabiltzaile izena:',
'prefs-help-signature'          => 'Eztabaida orrietako iruzkinak "<nowiki>~~~~</nowiki>" ikurrekin sinatu behar dira, honela zure sinadura eta sinatzeko-unea azalduko dira.',
'badsig'                        => 'Baliogabeko sinadura; egiaztatu HTML etiketak.',
'badsiglength'                  => 'Zure sinadura luzeegia da.
$1 {{PLURAL:$1|karakteretik|karakteretik}} behera izan behar ditu.',
'yourgender'                    => 'Generoa:',
'gender-unknown'                => 'Zehaztugabea',
'gender-male'                   => 'Gizona',
'gender-female'                 => 'Emakumea',
'prefs-help-gender'             => 'Hautazkoa: softwareak generoa zehazteko erabilia. Informazio hau publikoa da.',
'email'                         => 'E-posta',
'prefs-help-realname'           => '* Benetako izena (aukerakoa): zehaztea erabakiz gero, zure lanarentzako atribuzio bezala balioko du.',
'prefs-help-email'              => 'E-posta helbidea aukerakoa da, baina zure pasahitza ahaztekotan berriro zure e-postara bidaltzeko aukera ematen dizu.
Gainera beste lankideek zurekin kontakta dezakete zure lankide edo lankide_eztabaid orrialdeak erabilita zure identitatea ezagutzera eman gabe.',
'prefs-help-email-required'     => 'E-mail helbidea derrigorrezkoa da.',
'prefs-info'                    => 'Oinarrizko informazioa',
'prefs-i18n'                    => 'Nazioartekotasuna',
'prefs-signature'               => 'Sinadura',
'prefs-dateformat'              => 'Data-formatua',
'prefs-timeoffset'              => 'Denbora ezberdintasuna',
'prefs-advancedediting'         => 'Aukera aurreratuak',
'prefs-advancedrc'              => 'Aukera aurreratuak',
'prefs-advancedrendering'       => 'Aukera aurreratuak',
'prefs-advancedsearchoptions'   => 'Aukera aurreratuak',
'prefs-advancedwatchlist'       => 'Aukera aurreratuak',
'prefs-displayrc'               => 'Aukerak erakutsi',
'prefs-displaysearchoptions'    => 'Aukerak erakutsi',
'prefs-displaywatchlist'        => 'Aukerak erakutsi',
'prefs-diffs'                   => 'Ezberdintasunak',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'E-posta helbidea zuzena dela dirudi',
'email-address-validity-invalid' => 'E-posta helbide zuzena idatzi',

# User rights
'userrights'                   => 'Erabiltzaile baimenen kudeaketa',
'userrights-lookup-user'       => 'Erabiltzaile taldeak kudeatu',
'userrights-user-editname'     => 'Erabiltzaile izena idatzi:',
'editusergroup'                => 'Erabiltzaile taldeak editatu',
'editinguser'                  => "'''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) lankidearen erabiltzaile-eskubideak aldatzen",
'userrights-editusergroup'     => 'Erabiltzaile taldeak editatu',
'saveusergroups'               => 'Erabiltzaile taldeak gorde',
'userrights-groupsmember'      => 'Partaide da hemen:',
'userrights-groupsmember-auto' => 'Honen kide inplizitua:',
'userrights-groups-help'       => 'Lankide hau dagoen taldeak aldatu dituzu:
* Aukeratutako taulak esan nahi du lankidea talde horretan dagoela.
* Aukeratu gabeko taulak esan nahi du lankidea ez dagoela talde horretan.
* *-k erakusten du ezin duzula taldea ezabatu, behin gehitu ondoren, edo alderantziz.',
'userrights-reason'            => 'Arrazoia:',
'userrights-no-interwiki'      => 'Ez duzu beste wikietan erabiltzaile eskumenak aldatzeko baimenik.',
'userrights-nodatabase'        => '$1 datubasea ez da existitzen edo ez dago lokalki.',
'userrights-nologin'           => 'Administratzaile kontu batekin [[Special:UserLogin|hasi behar duzu saioa]] erabiltzaile eskubideak esleitzeko.',
'userrights-notallowed'        => 'Zure kontuak ez du baimenik erabiltzaile eskumenak aldatzeko.',
'userrights-changeable-col'    => 'Alda ditzakezun taldeak',
'userrights-unchangeable-col'  => 'Aldatu ezin ditzakezun taldeak',

# Groups
'group'               => 'Taldea:',
'group-user'          => 'Erabiltzaileak',
'group-autoconfirmed' => 'Lankide autokonfirmatuak',
'group-bot'           => 'Bot-ak',
'group-sysop'         => 'Administratzaileak',
'group-bureaucrat'    => 'Burokratak',
'group-suppress'      => 'Gainikupsenak',
'group-all'           => '(guztiak)',

'group-user-member'          => 'Lankide',
'group-autoconfirmed-member' => 'Erabiltzaile autokonfirmatua',
'group-bot-member'           => 'Bot-a',
'group-sysop-member'         => 'Administratzaile',
'group-bureaucrat-member'    => 'Burokrata',
'group-suppress-member'      => 'Gainikuspena',

'grouppage-user'          => '{{ns:project}}:Lankideak',
'grouppage-autoconfirmed' => '{{ns:project}}:Erabiltzaile autokonfirmatuak',
'grouppage-bot'           => '{{ns:project}}:Bot-ak',
'grouppage-sysop'         => '{{ns:project}}:Administratzaileak',
'grouppage-bureaucrat'    => '{{ns:project}}:Burokratak',
'grouppage-suppress'      => '{{ns:project}}:Gainikuspena',

# Rights
'right-read'                  => 'Orriak irakurri',
'right-edit'                  => 'Orriak aldatu',
'right-createpage'            => 'Orrialdeak sortu (eztabaida orrialdeak ez direnak)',
'right-createtalk'            => 'Eztabaida orriak sortu',
'right-createaccount'         => 'Erabiltzaile kontu berria sortu',
'right-minoredit'             => 'Aldaketa txiki gisa markatu',
'right-move'                  => 'Orrialdeak mugitu',
'right-move-subpages'         => 'Mugitu orrialdeak bere azpiorrialdeekin',
'right-move-rootuserpages'    => 'Erro-lankidearen orriak mugitu',
'right-movefile'              => 'Fitxategiak mugitu',
'right-suppressredirect'      => 'Ez sortu birzuzenketa bat antzinako izenetik orrialdea mugitzerakoan',
'right-upload'                => 'Fitxategia igo',
'right-reupload'              => 'Jada existitzen den artxibo bat gainidatzi',
'right-reupload-own'          => 'Norberak igotako fitxategi baten gainean idatzi',
'right-reupload-shared'       => 'Media biltegi komun batean dauden fitxategiak lokalki gainpasa',
'right-upload_by_url'         => 'URL helbide batetik fitxategi bat igo',
'right-purge'                 => 'Leku honen katxea garbitu konfirmaziorik gabeko orrialde batentzat',
'right-autoconfirmed'         => 'Erdi-babestuak dauden orriak aldatu',
'right-bot'                   => 'Prozesu automatikoki gisa jokatu',
'right-nominornewtalk'        => 'Estabaida orrietan aldaketa txikirik ez edukitzea mezu berrietan',
'right-apihighlimits'         => 'API eskaeretan goreneko mugak erabili',
'right-writeapi'              => 'API idaztekoa erabili',
'right-delete'                => 'Orrialdeak ezabatu',
'right-bigdelete'             => 'Historia luzea duten orrialdeak ezabatu',
'right-deleterevision'        => 'Orrialdeen berrikuspen espezifikoak ezabatu eta leheneratu',
'right-deletedhistory'        => 'Ezabatutako sarreren historia ikusi, euren atxikitutako testurik gabe',
'right-deletedtext'           => 'Ikusi ezabatutako testua eta ezabatutako berrikuspenen arteko aldaketak',
'right-browsearchive'         => 'Ezabatutako orrialdeak bilatu',
'right-undelete'              => 'Ezabatutako orrialde bat itzularazi',
'right-suppressrevision'      => 'Administratzaileentzat izkutatutako berrikuspenak berrikusi edo berrezarri',
'right-suppressionlog'        => 'Log pribatuak ikusi',
'right-block'                 => 'Beste lankideek edita ez dezaten blokeatu',
'right-blockemail'            => 'Erabiltzaile batek emailak bidal ez ditzan blokeatu',
'right-hideuser'              => 'Erabiltzaile izen bat blokeatu, publikotik izkutatuz',
'right-ipblock-exempt'        => 'IP blokeoen, auto-blokeoen eta maila blokeoen gainetik pasa.',
'right-proxyunbannable'       => 'Proxyen blokeo automatikoen gainetik pasa',
'right-unblockself'           => 'Beren burua desblokeatu',
'right-protect'               => 'Orrialde babestuak aldatu eta babes maila aldatu',
'right-editprotected'         => 'Babestutako orrialdeak aldatu (babes jauzirik gabe)',
'right-editinterface'         => 'Erabiltzailearen interfazea aldatu',
'right-editusercssjs'         => 'Beste lankideen CSS eta JS fitxategiak aldatu',
'right-editusercss'           => 'Beste lankideen CSS fitxategiak aldatu',
'right-edituserjs'            => 'Beste lankideen JS fitxategiak aldatu',
'right-rollback'              => 'Orrialde zehatz bat aldatu zuen azken lankidearen aldaketak modu azkar batean leheneratu',
'right-markbotedits'          => 'Atzera bueltan eginiko aldaketak bot baten aldaketak balira markatu',
'right-noratelimit'           => 'Ez dio eragiten erlazio mugak',
'right-import'                => 'Orrialdeak beste wiki batetik inportatu',
'right-importupload'          => 'Igotako fitxategi batetik orrialdeak inportatu',
'right-patrol'                => 'Besteen edizioak patrullatu moduan markatu',
'right-autopatrol'            => 'Norberak egiten dituen aldaketa guztiak automatikoki gain-ikusi gisa markatu',
'right-patrolmarks'           => 'Ikusi azken aldaketen jarraitze markak',
'right-unwatchedpages'        => 'Ikusi gabeko orrialdeen zerrenda bat ikusi',
'right-trackback'             => 'Aipua bidali',
'right-mergehistory'          => 'Orrialdeen historia batu',
'right-userrights'            => 'Erabiltzaile guztien eskumenak aldatu',
'right-userrights-interwiki'  => 'Beste wiki batzuetan erabiltzaileen eskumenak aldatu',
'right-siteadmin'             => 'Databasea blokeatu eta desblokeatu',
'right-reset-passwords'       => 'Bese erabiltzaile batzuen pasahitzak berritu',
'right-override-export-depth' => '5eko sakonerararteko loturiko orrialdeak barne esportatu',
'right-sendemail'             => 'Beste erabiltzaileei e-posta bidali',

# User rights log
'rightslog'      => 'Erabiltzaile eskubideen erregistroa',
'rightslogtext'  => 'Erabiltzaile eskubideetan izandako aldaketen erregistroa da hau.',
'rightslogentry' => '$1(r)en partaidetza aldatu da $2(e)tik $3(e)ra',
'rightsnone'     => '(bat ere ez)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'orrialde hau irakurri',
'action-edit'                 => 'orrialde hau aldatu',
'action-createpage'           => 'orrialdeak sortu',
'action-createtalk'           => 'eztabaida orrialdeak sortu',
'action-createaccount'        => 'lankide hau sortu',
'action-minoredit'            => 'aldaketa hau txiki gisa markatu',
'action-move'                 => 'orrialde hau mugitu',
'action-move-subpages'        => 'orrialde hau eta bere azpiorrialdeak mugitu',
'action-move-rootuserpages'   => 'mugitu lankidearen oinarri orrialdeak',
'action-movefile'             => 'fitxategi hau mugitu',
'action-upload'               => 'fitxategi hau igo',
'action-reupload'             => 'dagoeneko baden fitxategi honen gainean idatzi',
'action-reupload-shared'      => 'biltegi komun batean dagoen fitxategi hau gainpasa',
'action-upload_by_url'        => 'URL helbide batetik fitxategi hau igo',
'action-writeapi'             => 'idazteko APIa erabili',
'action-delete'               => 'orrialde hau ezabatu',
'action-deleterevision'       => 'berrikuspen hau ezabatu',
'action-deletedhistory'       => 'orrialde honetako ezabatutako historia ikusi',
'action-browsearchive'        => 'ezabatutako orrialdeak bilatu',
'action-undelete'             => 'ezabatutako orrialde hau bergaitu',
'action-suppressrevision'     => 'izkutuko berrikuspen hau berrikusi eta gaitu',
'action-suppressionlog'       => 'log pribatu hau ikusi',
'action-block'                => 'lankide honi aldaketak egitea ekidin',
'action-protect'              => 'orrialde honetako babes mailak aldatu',
'action-import'               => 'orrialde hau beste wiki batetik inportatu',
'action-importupload'         => 'igotako fitxategi batetik orrialde hau inportatu',
'action-patrol'               => 'besteen aldaketak patruilatu moduan markatu',
'action-autopatrol'           => 'zeure aldaketak patruilatutzat markatu',
'action-unwatchedpages'       => 'ikusi gabeko orrialdeen zerrenda ikusi',
'action-trackback'            => 'aipu bat bidali',
'action-mergehistory'         => 'orrialde honen historia batu',
'action-userrights'           => 'lankide guztien eskumenak aldatu',
'action-userrights-interwiki' => 'beste wikietako lankideen lankide-eskumenak aldatu',
'action-siteadmin'            => 'datubasea babestu edo babesa kendu',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|aldaketa 1|$1 aldaketa}}',
'recentchanges'                     => 'Aldaketa berriak',
'recentchanges-legend'              => 'Azken aldaketen aukerak',
'recentchangestext'                 => 'Orrialde honetan wiki honetan egindako azken aldaketak erakusten dira.',
'recentchanges-feed-description'    => 'Sindikazio honetan wikian eginiko azkeneko aldaketak jarrai daitezke.',
'recentchanges-label-newpage'       => 'Aldaketa honek orrialde berri bat sortu du',
'recentchanges-label-minor'         => 'Hau aldaketa txikia da',
'recentchanges-label-bot'           => 'Aldaketa hau bot batek egin du',
'recentchanges-label-unpatrolled'   => 'Aldaketa hau ez da oraindik patruilatua izan',
'rcnote'                            => "Beheko azken {{PLURAL:$2|eguneko|'''$2''' egunetako}} azken {{PLURAL:$1|aldaketa|'''$1''' aldaketak}} hurrengo datan egin ziren: $5, $4.",
'rcnotefrom'                        => 'Jarraian azaltzen diren aldaketak data honetatik aurrerakoak dira: <b>$2</b> (gehienez <b>$1</b> erakusten dira).',
'rclistfrom'                        => 'Erakutsi $1 ondorengo aldaketa berriak',
'rcshowhideminor'                   => '$1 aldaketa txikiak',
'rcshowhidebots'                    => '$1 bot-ak',
'rcshowhideliu'                     => '$1 erabiltzaile erregistratuak',
'rcshowhideanons'                   => '$1 erabiltzaile anonimoak',
'rcshowhidepatr'                    => '$1 patruilatutako aldaketak',
'rcshowhidemine'                    => '$1 nire ekarpenak',
'rclinks'                           => 'Erakutsi azken $2 egunetako $1 aldaketak<br />$3',
'diff'                              => 'ezb',
'hist'                              => 'hist',
'hide'                              => 'Ezkutatu',
'show'                              => 'Erakutsi',
'minoreditletter'                   => 't',
'newpageletter'                     => 'B',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|lankide|lankide}} jarraitzen]',
'rc_categories'                     => 'Kategorietara mugatu ("|" karaktereaz banandu)',
'rc_categories_any'                 => 'Edozein',
'newsectionsummary'                 => '/* $1 */ atal berria',
'rc-enhanced-expand'                => 'Erakutsi xehetasunak (JavaScript beharrezkoa da)',
'rc-enhanced-hide'                  => 'Xehetasunak ezkutatu',

# Recent changes linked
'recentchangeslinked'          => 'Lotutako orrialdeen aldaketak',
'recentchangeslinked-feed'     => 'Lotutako orrialdeen aldaketak',
'recentchangeslinked-toolbox'  => 'Lotutako orrialdeen aldaketak',
'recentchangeslinked-title'    => '"$1"(e)kin harremanetan dauden aldaketak',
'recentchangeslinked-noresult' => 'Emandako epean ez da egon aldaketarik loturiko orrialdetan.',
'recentchangeslinked-summary'  => "Zerrenda honetan zehazturiko orrialde bati (edo kategoria berezi bateko azkeneko kideei) lotura duten orrietan eginiko azken aldaketak agertzen dira.
[[Special:Watchlist|Zurre jarraitze zerrenda]]n agertzen diren orrialdeak '''beltze'''z agertzen dira.",
'recentchangeslinked-page'     => 'Orriaren izena:',
'recentchangeslinked-to'       => 'Lotutako orrietarako aldaketak erakutsi emandako orriaren ordez',

# Upload
'upload'                      => 'Fitxategia igo',
'uploadbtn'                   => 'Fitxategia igo',
'reuploaddesc'                => 'Igotzeko formulariora itzuli.',
'upload-tryagain'             => 'Aldatutako fitxategiaren deskribapena bidali',
'uploadnologin'               => 'Saioa hasi gabe',
'uploadnologintext'           => 'Fitxategiak igotzeko [[Special:UserLogin|saioa hasi]] behar duzu.',
'upload_directory_missing'    => 'Igoeren direktorioa ($1) ezin da aurkitu eta web zerbitzariak ezin du sortu.',
'upload_directory_read_only'  => 'Web zerbitzariak ez dauka igoera direktorioan ($1) idazteko baimenik.',
'uploaderror'                 => 'Errorea igotzerakoan',
'uploadtext'                  => "Fitxategiak igotzeko beheko formularioa erabil dezakezu. 
Aurretik igotako irudiak ikusi edo bilatzeko [[Special:FileList|igotako fitxategien zerrendara]] jo. Igoerak [[Special:Log/upload|igoera erregistroan]] ikus daitezke eta ezabatutakoak [[Special:Log/delete|ezabaketa erregistroan]] zerrendatzen dira.

Orrialde baten irudi bat txertatzeko, erabili kode hauetako bat:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''',
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' * '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' irudia zuzenean erabiltzeko.",
'upload-permitted'            => 'Baimendutako fitxategi motak: $1.',
'upload-preferred'            => 'Fitxategi mota hobetsiak: $1.',
'upload-prohibited'           => 'Debekatutako fitxategi motak: $1.',
'uploadlog'                   => 'igoera erregistroa',
'uploadlogpage'               => 'Igoera erregistroa',
'uploadlogpagetext'           => 'Jarraian azken igoeren zerrenda ikus daiteke.',
'filename'                    => 'Fitxategi izena',
'filedesc'                    => 'Laburpena',
'fileuploadsummary'           => 'Laburpena:',
'filereuploadsummary'         => 'Fitxategi aldaketak:',
'filestatus'                  => 'Copyright egoera:',
'filesource'                  => 'Jatorria:',
'uploadedfiles'               => 'Igotako fitxategiak',
'ignorewarning'               => 'Oharra ezikusi eta fitxategia gorde.',
'ignorewarnings'              => 'Edozein ohar ezikusi.',
'minlength1'                  => 'Fitxategi izenek letra bat izan behar dute gutxienez.',
'illegalfilename'             => '"$1" fitxategiaren izenak orrialdeen izenburuetan erabili ezin diren karaktereak ditu. Mesedez, fitxategiari izena aldatu eta saiatu berriz igotzen.',
'badfilename'                 => 'Irudiaren izena aldatu da: "$1".',
'filetype-badmime'            => 'Ezin dira "$1" MIME motako fitxategiak igo.',
'filetype-bad-ie-mime'        => 'Ezin da fitxategia igo, Internet Explorerek "$1" bezala detektatuko lukeelako, zein fitxategi mota ez onartua eta arriskutsua den.',
'filetype-unwanted-type'      => '\'\'\'".$1"\'\'\' fitxategi mota ez da gustokoa. Hobesten {{PLURAL:$3|den fitxategi mota|diren fitxategi motak}} {{PLURAL:$2|$2 da|$2 dira}}.',
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' ez dago baimendutako fitxategi moten artean.
Baimendutako fitxategi {{PLURAL:$3|mota $2 da|motak $2 dira}}.',
'filetype-missing'            => 'Fitxategi honek ez du luzapenik (adibidez, ".jpg").',
'empty-file'                  => 'Bidali duzun fitxategia hutsik dago.',
'file-too-large'              => 'Bidali duzun fitxategia handiegia zen.',
'filename-tooshort'           => 'Fitxategiaren izena laburregia da.',
'filetype-banned'             => 'Mota horretako fitxategiak debekatuta daude.',
'verification-error'          => 'Fitxategiak ez du egiaztapena gainditu.',
'illegal-filename'            => 'Fitxategiaren izena ez da onartzen.',
'overwrite'                   => 'Jada existitzen den fitxategi bat ezin da berridatzi.',
'unknown-error'               => 'Ezezaguna den errorea gertatu da.',
'tmp-create-error'            => 'Ezin izan da behin-behineko fitxategirik sortu.',
'tmp-write-error'             => 'Errorea behin-behineko fitxategia idazten.',
'large-file'                  => 'Ez da gomendagarria fitxategiak $1 baino handiagoak izatea; fitxategi honen tamaina: $2.',
'largefileserver'             => 'Fitxategi hau zerbitzariak baimentzen duena baino handiagoa da.',
'emptyfile'                   => 'Badirudi igotzen ari zaren fitxategia hutsik dagoela. Mesedez, egiaztatu fitxategi hori dela igo nahi duzuna.',
'fileexists'                  => "Badago izen hori daukan fitxategi bat; mesedez, ikusi existitzen den '''<tt>[[:$1]]</tt>''' fitxategia aldatu nahi duzun egiaztatzeko.
[[$1|thumb]]",
'filepageexists'              => "Fitxategi honen deskribapen orria dagoeneko sortuta dago '''<tt>[[:$1]]</tt>'''-en, baina, ez da existitzen izen hori duen fitxategirik.
Idazten duzun laburpena ez da deskribapen orrian agertuko.
Zure laburpena agertzeko, eskuz aldatu beharko duzu.
[[$1|thumb]]",
'fileexists-extension'        => "Badago antzeko izena duen fitxategi bat: [[$2|thumb]]
* Igotako fitxategiaren izena: '''<tt>[[:$1]]</tt>'''
* Aurretik dagoen fitxategiaren izena: '''<tt>[[:$2]]</tt>'''
Hautatu beste izen bat.",
'fileexists-thumbnail-yes'    => "Badirudi neurri txikiko irudia dela ''(irudi txikia)''. [[$1|thumb]]
Egiaztatu '''<tt>[[:$1]]</tt>''' fitxategia.
Egiaztatutako fitxategia eta jatorrizkoa berdinak badira ez dago irudi txikia igo beharrik.",
'file-thumbnail-no'           => "Fitxategiaren izena '''<tt>$1</tt>'''-(r)ekin hasten da.
Badirudi tamaina txikiko irudia ''(thumbnail)'' dela.
Irudi hau bereizmen handiagoan izango bazenu igo ezazu, bestela, fitxategiaren izena aldatu mesedez.",
'fileexists-forbidden'        => 'Badago izen hori daukan fitxategia, eta ezin da gainidatzi.
Oraindik fitxategia igo nahi baduzu, mesedez atzera itzuli eta igo fitxategia izen ezberdin batekin. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Badago izen hori daukan fitxategi bat elkarbanatutako fitxategi-biltegian.
Oraindik ere fitxategia igo nahi baduzu atzera itzuli eta izen berri bat erabili, mesedez. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Fitxategi hau beste fitxategi {{PLURAL:$1|honen|hauen}} kopia bat da:',
'file-deleted-duplicate'      => 'Fitxategi honen ([[:$1]]) fitxategi berbera aldez aurretik ezabatua izan da. Fitxategi horren ezabaketa-erregistroa begiratu beharko zenuke berriz igo baino lehen.',
'uploadwarning'               => 'Igotzeko oharra',
'uploadwarning-text'          => 'Aldatu beheko fitxategiaren deskribapena, mesedez, eta saiatu berriz.',
'savefile'                    => 'Fitxategia gorde',
'uploadedimage'               => '"[[$1]]" igo da',
'overwroteimage'              => '"[[$1]]"ren bertsio berri bat igo',
'uploaddisabled'              => 'Igoerak ezgaituta daude',
'copyuploaddisabled'          => 'URL bidezko igoera desaktibatuta.',
'uploadfromurl-queued'        => 'Zure igoera ilaran jarri da.',
'uploaddisabledtext'          => 'Fitxategiak igotzea ezgaituta dago.',
'php-uploaddisabledtext'      => 'Fitxategi igoerak PHP-n ezinduta daude. Ikusi fitxategi_igoerak mesedez.',
'uploadscripted'              => 'Fitxategi honek web zerbitzariak modu ezegokian interpretatu lezakeen HTML edo script kodea dauka.',
'uploadvirus'                 => 'Fitxategiak birusa dauka! Xehetasunak: $1',
'upload-source'               => 'Jatorrizko fitxategia',
'sourcefilename'              => 'Iturri-fitxategiaren izena:',
'sourceurl'                   => 'Jatorrizko URL-a:',
'destfilename'                => 'Helburu fitxategi izena:',
'upload-maxfilesize'          => 'Fitxategien gehienezko tamaina: $1',
'upload-description'          => 'Fitxategiaren deskribapena',
'upload-options'              => 'Igoera-aukerak',
'watchthisupload'             => 'Fitxategi hau jarraitu',
'filewasdeleted'              => 'Izen hau duen fitxategi bat igo eta ezabatu da jada. $1 aztertu beharko zenuke berriz igo aurretik.',
'upload-wasdeleted'           => "'''Oharra: Lehenago ezabatutako fitxategia igotzen ari zara.'''

Kontuan izan fitxategia igotzea egokia ote den.
Fitxategi honen ezabaketa erregistroa jarraian ikus dezakezu:",
'filename-bad-prefix'         => "Igotzen ari zaren fitxategiaren izena '''\"\$1\"'''ekin hasten da, normalki kamera digitalek automatikoki ezartzen duten izen ez deskriptibo bat.
Aukera ezazu, mesedez, fitxategi izen deskriptiboago bat.",
'upload-success-subj'         => 'Igoera arrakastatsua',
'upload-success-msg'          => 'Zure [$2] igoera arrakastatsua izan da. Hemen duzu eskuragarri: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Igoera-arazoa',
'upload-failure-msg'          => 'Hurrengo arazoa egon da zure [$2] igoerarekin:

$1',
'upload-warning-subj'         => 'Igoera oharra',

'upload-proto-error'        => 'Protokolo ezegokia',
'upload-proto-error-text'   => 'Kanpo igoerak <code>http://</code> edo <code>ftp://</code> hasiera duen URLa izan behar du.',
'upload-file-error'         => 'Barne errorea',
'upload-file-error-text'    => 'Barne errore bat gertatu da zerbitzarian fitxategi tenporal bat sortzen saiatzean. Mesedez, jar zaitez [[Special:ListUsers/sysop|administratzaile]] batekin harremanetan.',
'upload-misc-error'         => 'Errore ezezaguna igotzerakoan',
'upload-misc-error-text'    => 'Errore ezezagun bat gertatu da fitxategia igotzen ari zenean. Mesedez, egiaztatu URLa baliozkoa eta eskuragarria dela eta berriz saiatu. Arazoak jarraitzen badu, jar zaitez [[Special:ListUsers/sysop|administratzailearekin]] harremanetan.',
'upload-too-many-redirects' => 'URLak birzuzenketa gehiegi zituen',
'upload-unknown-size'       => 'Tamaina ezezaguna',
'upload-http-error'         => 'HTTP errorea gertatu da: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Sarbide ukatua',
'img-auth-nopathinfo'   => 'PATH_INFO falta da.
Zure zerbitzaria ez dago informazio hau pasatzeko konfiguratuta.
CGI-oinarriduna izan daiteke, img_auth onartzen ez duena.
Ikus http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Eskatutako bidea ez dago kofiguratutako igoera-direktorioan.',
'img-auth-badtitle'     => 'Ezin izan da baleko izenbururik eraiki "$1" izenetik abiatuta',
'img-auth-nologinnWL'   => 'Ez duzu saioa hasi eta "$1" ez dago zerrenda zurian.',
'img-auth-nofile'       => 'Ez dago "$1" fitxategirik.',
'img-auth-isdir'        => '"$1" direktorio batera iristen saiatzen ari zara.
Fitxategien sarbidea baino ez da onartzen.',
'img-auth-streaming'    => '"$1" sekuentziatzen.',
'img-auth-noread'       => 'Erabiltzaileak ez du "$1" irakurtzeko sarbiderik.',

# HTTP errors
'http-invalid-url'      => 'URL baliogabea: $1',
'http-read-error'       => 'HTTP irakurketa-akatsa.',
'http-timed-out'        => 'HTTP eskaera iraungi da.',
'http-curl-error'       => 'Errorea URLa bilatzerakoan: $1',
'http-host-unreachable' => 'Ezin da URL-a atzeman.',
'http-bad-status'       => 'Arazo bat egon da HTTP eskaera bitartean: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Ezin izan da URLa eskuratu',
'upload-curl-error6-text'  => 'Ezin da emandako URLa eskuratu. Mesedez, ziurtatu URLa zuzena dela eta gunea eskuragarri dagoela.',
'upload-curl-error28'      => 'Denbora gehiegi igotzerakoan',
'upload-curl-error28-text' => 'Guneak denbora gehiegi behar du erantzuteko. Egiaztatu gunea martxan dagoela, itxaron pixka bat eta saiatu berriz. Karga txikiagoa denean probatu zenezake.',

'license'            => 'Lizentzia:',
'license-header'     => 'Lizentzia',
'nolicense'          => 'Hautatu gabe',
'license-nopreview'  => '(Aurreikuspenik ez)',
'upload_source_url'  => ' (baliozko URL publikoa)',
'upload_source_file' => ' (zure ordenagailuko fitxategi bat)',

# Special:ListFiles
'listfiles-summary'     => 'Orri berezi honek igotako fitxategi guztiak erakusten ditu.
Berez, azken igotako fitxategiak zerrendaren goiko aldean azaltzen dira.',
'listfiles_search_for'  => 'Irudiaren izenagatik bilatu:',
'imgfile'               => 'fitxategia',
'listfiles'             => 'Fitxategien zerrenda',
'listfiles_thumb'       => 'Iruditxoa',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Izena',
'listfiles_user'        => 'Erabiltzailea',
'listfiles_size'        => 'Tamaina (byte)',
'listfiles_description' => 'Deskribapena',
'listfiles_count'       => 'Bertsioak',

# File description page
'file-anchor-link'          => 'Fitxategia',
'filehist'                  => 'Fitxategiaren historia',
'filehist-help'             => 'Data/orduan klik egin fitxategiak orduan zuen itxura ikusteko.',
'filehist-deleteall'        => 'guztiak ezabatu',
'filehist-deleteone'        => 'hau ezabatu',
'filehist-revert'           => 'desegin',
'filehist-current'          => 'oraingoa',
'filehist-datetime'         => 'Data/Ordua',
'filehist-thumb'            => 'Iruditxoa',
'filehist-thumbtext'        => '$1 bertsioaren iruditxoa',
'filehist-nothumb'          => 'Ez dago iruditxorik',
'filehist-user'             => 'Erabiltzailea',
'filehist-dimensions'       => 'Neurriak',
'filehist-filesize'         => 'Tamaina',
'filehist-comment'          => 'Iruzkina',
'filehist-missing'          => 'Fitxategia falta da',
'imagelinks'                => 'Fitxategiaren erabilera',
'linkstoimage'              => 'Hurrengo {{PLURAL:$1|orrialdeak du|$1 orrialdeek dute}} fitxategi honetarako lotura:',
'linkstoimage-more'         => '$1 {{PLURAL:$1|orri lotura|orri lotura}} baino gehiago daude fitxategira.
Ondorengo zerrendak fitxategira dauden {{PLURAL:$1|lehen lotura|lehen $1 loturak}} erakusten ditu bakarrik.
[[Special:WhatLinksHere/$2|Zerrenda osoa]] ere eskuragarri dago.',
'nolinkstoimage'            => 'Ez dago fitxategi honetara lotura egiten duen orrialderik.',
'morelinkstoimage'          => 'Ikusi fitxategi honen [[Special:WhatLinksHere/$1|lotura gehiago]].',
'redirectstofile'           => 'Honako {{PLURAL:$1|artxiboak fitxategi honetara birzuzentzen du:|$1 artxiboek fitxategi honetara birzuzentzen dute:}}',
'duplicatesoffile'          => 'Ondorengo fitxategi {{PLURAL:$1|hau beste honen berdina da|$1 hauek beste honen berdinak dira}} ([[Special:FileDuplicateSearch/$2|zehaztasun gehiago]]):',
'sharedupload'              => 'Elkarbanatutako fitxategi hau $1-(e)ko igoera bat da eta beste proiektuek ere erabil dezakete.',
'filepage-nofile'           => 'Izen horrekin ez dago fitxategirik.',
'uploadnewversion-linktext' => 'Fitxategi honen bertsio berri bat igo',
'shared-repo-from'          => '$1-tik',
'shared-repo'               => 'elkarbanatutako biltegia',

# File reversion
'filerevert'                => '$1 leheneratu',
'filerevert-legend'         => 'Fitxategia leheneratu',
'filerevert-intro'          => "'''[[Media:$1|$1]]''' berrezartzen ari zara [$4 $3(e)ko, $2(e)tako bertsiora].",
'filerevert-comment'        => 'Arrazoia:',
'filerevert-defaultcomment' => '$2, $1 bertsiora leheneratu da',
'filerevert-submit'         => 'Leheneratu',
'filerevert-success'        => "'''[[Media:$1|$1]]''' [$4 $3(e)ko, $2(e)tako bertsiora] lehenratua izan da.",
'filerevert-badversion'     => 'Ez dago aurreragoko fitxategi honen bertsio lokalik emandako denbora tartean.',

# File deletion
'filedelete'                  => '$1 ezabatu',
'filedelete-legend'           => 'Fitxategia ezabatu',
'filedelete-intro'            => "'''[[Media:$1|$1]]''' fitxategiaezabatzen ari zara eta honen historiarekin batera.",
'filedelete-intro-old'        => "'''[[Media:$1|$1]]'''ren bertsioa ezabatzen ari zara, [$4 $3, $2].",
'filedelete-comment'          => 'Arrazoia:',
'filedelete-submit'           => 'Ezabatu',
'filedelete-success'          => "'''$1''' ezabatu da.",
'filedelete-success-old'      => "'''[[Media:$1|$1]]'''en $3, $2 bertsioa ezabatu da.",
'filedelete-nofile'           => "Ez dago '''$1''' izeneko fitxategirik.",
'filedelete-nofile-old'       => "Ez dago '''$1'''en bertsio artxibaturik zuk jarritako izaera horrekin.",
'filedelete-otherreason'      => 'Beste arrazoiak/gehigarriak:',
'filedelete-reason-otherlist' => 'Bestelako arrazoiak',
'filedelete-reason-dropdown'  => '*Ezabatzeko arrazoi ohikoa
** Copyright bortxaketa
** Bikoiztutako fitxategia',
'filedelete-edit-reasonlist'  => 'Ezabaketa arrazoiak aldatu',

# MIME search
'mimesearch'         => 'MIME bilaketa',
'mimesearch-summary' => 'Orrialde honek fitxategiak bere MIME motaren arabera iragaztea ahalbidetzen du. Iragazkia: eduki-mota/azpi-mota, adib. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME mota:',
'download'           => 'jaitsi',

# Unwatched pages
'unwatchedpages' => 'Jarraitu gabeko orrialdeak',

# List redirects
'listredirects' => 'Birzuzenketen zerrenda',

# Unused templates
'unusedtemplates'     => 'Erabili gabeko txantiloiak',
'unusedtemplatestext' => 'Orrialde honetan beste edozein orrialdetan erabiltzen ez diren {{ns:template}} izen-tarteko orrialdeak zerrendatzen dira. Ez ahaztu txantiloietara egon daitezkeen loturak egiaztatzeaz ezabatu aurretik.',
'unusedtemplateswlh'  => 'beste loturak',

# Random page
'randompage'         => 'Ausazko orria',
'randompage-nopages' => 'Ez dago orrialderik honako {{PLURAL:$2|kategorian|kategoriatan}}: $1',

# Random redirect
'randomredirect'         => 'Ausazko birzuzenketa',
'randomredirect-nopages' => 'Ez dago birzuzenketarik "$1" izen-tartean.',

# Statistics
'statistics'                   => 'Estatistikak',
'statistics-header-pages'      => 'Orrialdeen estatistikak',
'statistics-header-edits'      => 'Aldaketen estatistikak',
'statistics-header-views'      => 'Ikustaldien estatistikak',
'statistics-header-users'      => 'Erabiltzaile estatistikak',
'statistics-header-hooks'      => 'Beste estatistikak',
'statistics-articles'          => 'Edukiak dituzten orrialdeak',
'statistics-pages'             => 'Orrialdeak',
'statistics-pages-desc'        => 'Wikian dauden orrialde guztiak, eztabaida orrialdeak, birzuzenketa, etab. barne.',
'statistics-files'             => 'Igotako fitxategiak',
'statistics-edits'             => '{{SITENAME}} sortu zenetik eginiko aldaketa kopurua',
'statistics-edits-average'     => 'Bataz-besteko aldaketak orrialdeko',
'statistics-views-total'       => 'Ikusitako orrialdeak guztira',
'statistics-views-peredit'     => 'Ikusitako orrialdeak aldaketa bakoitzeko',
'statistics-users'             => 'Izen-emandako [[Special:ListUsers|lankideak]]',
'statistics-users-active'      => 'Lankide aktiboak',
'statistics-users-active-desc' => 'Aurreko {{PLURAL:$1|egunean|egunetan}} jardueraren bat gauzatu duten erabiltzaileak',
'statistics-mostpopular'       => 'Orri bisitatuenak',

'disambiguations'      => 'Argipen orrietara lotzen duten orriak',
'disambiguationspage'  => 'Template:argipen',
'disambiguations-text' => "Jarraian azaltzen diren orrialdeek '''argipen orrialde''' baterako lotura dute. Kasu bakoitzean dagokion artikulu zuzenarekin izan beharko lukete lotura.<br />Orrialde bat argipen motakoa dela antzeman ohi da [[MediaWiki:Disambiguationspage]] orrialdean agertzen den txantiloietako bat duenean.",

'doubleredirects'            => 'Birzuzenketa bikoitzak',
'doubleredirectstext'        => 'Lerro bakoitzean lehen eta bigarren birzuzenketetarako loturak ikus daitezke, eta baita edukia daukan edo eduki beharko lukeen orrialderako lotura ere. Lehen birzuzenketak azken honetara <del>zuzendu</del> beharko luke.',
'double-redirect-fixed-move' => '[[$1]] mugitu da eta orain [[$2]](e)ra birzuzenketa bat da',
'double-redirect-fixer'      => 'Birzuzenketa zuzentzailea',

'brokenredirects'        => 'Hautsitako birzuzenketak',
'brokenredirectstext'    => 'Jarraian zerrendatutako birzuzenketa loturak existitzen ez diren orrietara zuzenduta daude:',
'brokenredirects-edit'   => 'aldatu',
'brokenredirects-delete' => 'ezabatu',

'withoutinterwiki'         => 'Hizkuntza loturarik gabeko orrialdeak',
'withoutinterwiki-summary' => 'Orrialde hauek ez daukate beste hizkuntzetarako loturarik:',
'withoutinterwiki-legend'  => 'Aurrizkia',
'withoutinterwiki-submit'  => 'Erakutsi',

'fewestrevisions' => 'Berrikusketa gutxien dituzten artikuluak',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|byte 1|$1 byte}}',
'ncategories'             => '{{PLURAL:$1|kategoria 1|$1 kategoria}}',
'nlinks'                  => '{{PLURAL:$1|lotura 1|$1 lotura}}',
'nmembers'                => '{{PLURAL:$1|partaide 1|$1 partaide}}',
'nrevisions'              => '{{PLURAL:$1|berrikuspen 1|$1 berrikuspen}}',
'nviews'                  => '{{PLURAL:$1|ikusketa 1|$1 ikusketa}}',
'specialpage-empty'       => 'Ez dago emaitzarik bilaketa honetarako.',
'lonelypages'             => 'Orrialde umezurtzak',
'lonelypagestext'         => 'Jarraian zerrendatutako orrialdeek ez daukate {{SITENAME}}(e)n beste orrialdeetatik loturarik.',
'uncategorizedpages'      => 'Kategorizatu gabeko orrialdeak',
'uncategorizedcategories' => 'Kategorizatu gabeko kategoriak',
'uncategorizedimages'     => 'Kategorizatu gabeko irudiak',
'uncategorizedtemplates'  => 'Sailkatu gabeko txantiloiak',
'unusedcategories'        => 'Erabili gabeko kategoriak',
'unusedimages'            => 'Erabili gabeko fitxategiak',
'popularpages'            => 'Orrialde bisitatuenak',
'wantedcategories'        => 'Eskatutako kategoriak',
'wantedpages'             => 'Eskatutako orrialdeak',
'wantedpages-badtitle'    => 'Izenburu okerra hautatutako emaitzetan: $1',
'wantedfiles'             => 'Eskatutako fitxategiak',
'wantedtemplates'         => 'Eskatutako txantiloiak',
'mostlinked'              => 'Gehien lotutako orrialdeak',
'mostlinkedcategories'    => 'Gehien lotutako kategoriak',
'mostlinkedtemplates'     => 'Txantiloi erabilienak',
'mostcategories'          => 'Sailkapenean kategoria gehien dituzten orrialdeak',
'mostimages'              => 'Gehien lotutako irudiak',
'mostrevisions'           => 'Berrikuspen gehien dituzten orrialdeak',
'prefixindex'             => 'Orri guztiak aurrizkiekin',
'shortpages'              => 'Orrialde laburrak',
'longpages'               => 'Orrialde luzeak',
'deadendpages'            => 'Orrialde itsuak',
'deadendpagestext'        => 'Jarraian zerrendatutako orrialdeek ez daukate wikiko beste edozein orrialdetarako loturarik.',
'protectedpages'          => 'Babestutako orrialdeak',
'protectedpages-indef'    => 'Babes mugagabeak bakarrik',
'protectedpages-cascade'  => 'Kaskada moduko babesak bakarrik',
'protectedpagestext'      => 'Jarraian azaltzen diren orrialdeak mugitu edo aldatzeko babestuta daude',
'protectedpagesempty'     => 'Ez dago parametro horiek dituen babesturiko orrialderik oraintxe.',
'protectedtitles'         => 'Babestutako tituluak',
'protectedtitlestext'     => 'Hurrengo tituluen sorrera babestua dago',
'protectedtitlesempty'    => 'Ez dago parametro horiek dituen babesturiko izenbururik oraintxe.',
'listusers'               => 'Erabiltzaileen zerrenda',
'listusers-editsonly'     => 'Aldaketak egin dituzten erabiltzaileak soilik erakutsi',
'listusers-creationsort'  => 'Sorrera dataren arabera sailkatu',
'usereditcount'           => '{{PLURAL:$1|aldaketa $1|$1 aldaketa}}',
'usercreated'             => '$2-(e)tan $1-(a)n sortua',
'newpages'                => 'Orrialde berriak',
'newpages-username'       => 'Erabiltzaile izena:',
'ancientpages'            => 'Orrialde zaharrenak',
'move'                    => 'Mugitu',
'movethispage'            => 'Orrialde hau mugitu',
'unusedimagestext'        => 'Ondorengo fitxategiak existizen dira baina ez daude inongo orrietatik lotuta.
Mesedez, kontuan izan beste webgune batzutatik URL zuzena erabiliz lotura izan dezaketela irudira, eta kasu horretan ez lirateke hemengo zerrendetan azalduko.',
'unusedcategoriestext'    => 'Hurrengo kategoria orrialde guztiak datu-basean existitzen dira, baina ez du inongo orrialde edo kategoriak erabiltzen.',
'notargettitle'           => 'Helburu orrialderik ez',
'notargettext'            => 'Ez duzu eragiketa hau burutzeko helburu orrialde edo erabiltzaile bat zehaztu.',
'nopagetitle'             => 'Ez dago horrelako helburu orrialderik',
'nopagetext'              => 'Zuk ezarri duzun helburuko orrialdea ez da existitzen.',
'pager-newer-n'           => '{{PLURAL:$1|berriago den 1|berriagoak diren $1}}',
'pager-older-n'           => '{{PLURAL:$1|zaharragoa den 1|zaharragoak diren $1}}',
'suppress'                => 'Gain-ikuspena',

# Book sources
'booksources'               => 'Iturri liburuak',
'booksources-search-legend' => 'Liburuen bilaketa',
'booksources-go'            => 'Joan',
'booksources-text'          => 'Jarraian liburu berri eta erabiliak saltzen dituzten guneetarako loturen zerrenda bat ikus dezakezu, bilatzen ari zaren liburu horientzako informazio gehigarria aurkitzeko lagungarria izan daitekeena:',
'booksources-invalid-isbn'  => 'Badirudi emandako ISBNa ez dela baliagarria; egiazta ezazu ea akatsik egin duzun jatorrizko iturritik kopiatzean.',

# Special:Log
'specialloguserlabel'  => 'Egilea:',
'speciallogtitlelabel' => 'Helburua (izenburua edo lankidea):',
'log'                  => 'Erregistroak',
'all-logs-page'        => 'Erregistro publiko guztiak',
'alllogstext'          => '{{SITENAME}} orrialdearen erregistro guztien erakusketa konbinatua.
Erregistro mota, erabiltzailearen izena edota orrialdearen izena iragaziz bistaratu daiteke. Letra larriak eta xeheak bereizten dira.',
'logempty'             => 'Ez dago emaitzarik erregistroan.',
'log-title-wildcard'   => 'Testu honekin hasten diren izenburuak bilatu',

# Special:AllPages
'allpages'          => 'Orrialde guztiak',
'alphaindexline'    => '$1(e)tik $2(e)raino',
'nextpage'          => 'Hurrengo orrialdea ($1)',
'prevpage'          => 'Aurreko orrialdea ($1)',
'allpagesfrom'      => 'Honela hasten diren orrialdeak erakutsi:',
'allpagesto'        => 'Orrialde honetara zuzentzen diren guztiak erakutsi:',
'allarticles'       => 'Artikulu guztiak',
'allinnamespace'    => 'Orrialde guztiak ($1 izen-tartea)',
'allnotinnamespace' => 'Orrialde guztiak ($1 izen-tartean ez daudenak)',
'allpagesprev'      => 'Aurrekoa',
'allpagesnext'      => 'Hurrengoa',
'allpagessubmit'    => 'Joan',
'allpagesprefix'    => 'Aurrizki hau duten orrialdeak bistaratu:',
'allpagesbadtitle'  => 'Orrialdearen izena baliogabekoa da edo interwiki edo hizkuntzen arteko aurrizkia dauka. Izenburuetan erabili ezin daitezkeen karaktere bat edo gehiago izan ditzake.',
'allpages-bad-ns'   => '{{SITENAME}}(e)k ez dauka "$1" izeneko izen-tarterik.',

# Special:Categories
'categories'                    => 'Kategoriak',
'categoriespagetext'            => 'Hurrengo {{PLURAL:$1|kategoriak orrialdeak edo fitxategiak ditu|kategoriek orrialdeak edo fitxategiak dituzte}}.
[[Special:UnusedCategories|Erabili gabeko kategoriak]] ez dira hemen erakusten.
Ikus, gainera [[Special:WantedCategories|kategoriarik eskatuenak]].',
'categoriesfrom'                => 'Honela hasten diren kategoriak erakutsi:',
'special-categories-sort-count' => 'kontatetzearen arabera ordenatu',
'special-categories-sort-abc'   => 'alfabetikoki aldatu',

# Special:DeletedContributions
'deletedcontributions'             => 'Ezabatutako ekarpenak',
'deletedcontributions-title'       => 'Ezabatutako ekarpenak',
'sp-deletedcontributions-contribs' => 'ekarpenak',

# Special:LinkSearch
'linksearch'       => 'Kanpo loturen bilaketa',
'linksearch-pat'   => 'Bilaketa katea:',
'linksearch-ns'    => 'Izen-tartea:',
'linksearch-ok'    => 'Bilatu',
'linksearch-text'  => '"*.wikipedia.org" bezalako izartxoak erabil daitezke.
Gutxienez goi mailako domeinua behar du, adibidez "*.org".<br />
Baimendutako protokoloak: <tt>$1</tt> (zure bilaketan hauek ez gehitu).',
'linksearch-line'  => '$1, $2(e)tik lotuta',
'linksearch-error' => 'Komodinak izenaren hasieran bakarrik agertu beharko lirateke.',

# Special:ListUsers
'listusersfrom'      => 'Hemendik aurrerako erabiltzaileak bistaratu:',
'listusers-submit'   => 'Erakutsi',
'listusers-noresult' => 'Ez da erabiltzailerik aurkitu.',
'listusers-blocked'  => '(blokeatua)',

# Special:ActiveUsers
'activeusers'            => 'Lankide aktiboen zerrenda',
'activeusers-count'      => '{{PLURAL:$1|Aldaketa berri bat|$1 aldaketa berri}} azken {{PLURAL:$3|egunean|$3 egunetan}}',
'activeusers-from'       => 'Bilatu honela hasten diren lankideak:',
'activeusers-hidebots'   => 'Ezkutatu bot-ak',
'activeusers-hidesysops' => 'Ezkutatu administratzaileak',
'activeusers-noresult'   => 'Ez da lankiderik aurkitu.',

# Special:Log/newusers
'newuserlogpage'              => 'Erabiltzaile erregistroa',
'newuserlogpagetext'          => 'Hau azken erabiltzaileen sorreren erregistroa da.',
'newuserlog-byemail'          => 'pasahitza e-postaz bidali da',
'newuserlog-create-entry'     => 'Erabiltzaile berria',
'newuserlog-create2-entry'    => '$1 kontu berria sortu da',
'newuserlog-autocreate-entry' => 'Automatikoki sorturiko kontua',

# Special:ListGroupRights
'listgrouprights'                      => 'Erabiltzaile talde eskumenak',
'listgrouprights-summary'              => 'Ondorengo zerrendak wikian dauden lankide taldeak agertzen dira, beraien eskubideekin.
Badago [[{{MediaWiki:Listgrouprights-helppage}}|informazio osagarria]] banakako eskubideei buruz.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Eskubidea emanda</span>
* <span class="listgrouprights-revoked">Eskubidea kenduta</span>',
'listgrouprights-group'                => 'Taldea',
'listgrouprights-rights'               => 'Eskumenak',
'listgrouprights-helppage'             => 'Help:Talde eskumenak',
'listgrouprights-members'              => '(kideen zerrenda)',
'listgrouprights-addgroup'             => '{{PLURAL:$2|Taldea gehitu ahal duzu|Taldeak gehitu ahal dituzu}}: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2|Taldea kendu ahal duzu|Taldeak kendu ahal dituzu}}: $1',
'listgrouprights-addgroup-all'         => 'Talde guztiak gehitu daitezke',
'listgrouprights-removegroup-all'      => 'Talde guztiak kendu daitezke',
'listgrouprights-addgroup-self-all'    => 'Talde guztiak norbere kontura gehitu',
'listgrouprights-removegroup-self-all' => 'Talde guztiak norbere kontutik ezabatu',

# E-mail user
'mailnologin'          => 'Bidalketa helbiderik ez',
'mailnologintext'      => 'Beste erabiltzaileei e-posta mezuak bidaltzeko [[Special:UserLogin|saioa hasi]] eta baliozko e-posta helbidea behar duzu izan zure [[Special:Preferences|hobespenetan]].',
'emailuser'            => 'Erabiltzaile honi e-posta bidali',
'emailpage'            => 'Erabiltzaileari e-posta bidali',
'emailpagetext'        => 'Erabiltzaile honek baliozko e-posta helbide bat ezarri badu bere hobespenetan, beheko formularioa erabiliz mezu bat bidal dakioke.
[[Special:Preferences|Hobespenetan]] daukazun e-posta helbidea azalduko da mezuaren bidaltzaile bezala eta beraz erantzun ahal izango dizu.',
'usermailererror'      => 'Mail objektuak errore hau itzuli du:',
'defemailsubject'      => 'E-posta {{SITENAME}}(e)tik',
'usermaildisabled'     => 'Erabiltzailearen e-maila desaktibatuta',
'usermaildisabledtext' => 'Wiki honetan ezin diezu beste erabiltzaileei posta elektronikorik bidali',
'noemailtitle'         => 'Posta helbiderik ez',
'noemailtext'          => 'Erabiltzaile honek ez du baliozko e-posta helbiderik zehaztu.',
'nowikiemailtitle'     => 'Ezin da e-postarik bidali',
'nowikiemailtext'      => 'Erabiltzaile honek beste erabiltzaileengandik e-postak ez jasotzea hautatu du.',
'email-legend'         => 'Bidali e-posta bat {{SITENAME}}(e)ko beste lankide bati',
'emailfrom'            => 'Nork:',
'emailto'              => 'Nori:',
'emailsubject'         => 'Gaia:',
'emailmessage'         => 'Mezua:',
'emailsend'            => 'Mezua',
'emailccme'            => 'Mezu honen kopia bat niri bidali.',
'emailccsubject'       => 'Zure mezuaren kopia $1(r)i: $2',
'emailsent'            => 'Mezua bidali egin da',
'emailsenttext'        => 'Zure e-posta mezua bidali egin da.',
'emailuserfooter'      => 'E-posta hau $1(e)k bidali dio $2(r)i {{SITENAME}}ko "E-posta bidali" funtzioa erabiliz.',

# User Messenger
'usermessage-editor' => 'Sistemako mezularia',

# Watchlist
'watchlist'            => 'Nire jarraipen zerrenda',
'mywatchlist'          => 'Nire jarraipen zerrenda',
'watchlistfor2'        => '$1 ($2)',
'nowatchlist'          => 'Zure jarraipen zerrenda hutsik dago.',
'watchlistanontext'    => 'Mesedez $1 zure jarraipen zerrendako orrialdeak ikusi eta aldatu ahal izateko.',
'watchnologin'         => 'Saioa hasi gabe',
'watchnologintext'     => '[[Special:UserLogin|Saioa hasi]] behar duzu zure jarraipen zerrenda aldatzeko.',
'addedwatch'           => 'Jarraipen zerrendan gehitu da',
'addedwatchtext'       => "\"<nowiki>\$1</nowiki>\" orrialdea zure [[Special:Watchlist|jarraipen edo zelatatuen zerrendara]] erantsi da. Orrialde honen hurrengo aldaketak zerrenda horretan ageriko dira aurrerantzean, eta gainera [[Special:RecentChanges|aldaketa berrien zerrendan]] beltzez ageriko da, erraztasunez antzeman ahal izateko.

Jarraipen zerrendatik artikulua kentzeko, artikuluan ''ez jarraitu''ri eman.",
'removedwatch'         => 'Jarraipen zerrendatik ezabatuta',
'removedwatchtext'     => '"[[:$1]]" orrialdea zure [[Special:Watchlist|jarraipen zerrendatik]] kendu da.',
'watch'                => 'Jarraitu',
'watchthispage'        => 'Orrialde hau jarraitu',
'unwatch'              => 'Ez jarraitu',
'unwatchthispage'      => 'Jarraitzeari utzi',
'notanarticle'         => 'Ez da eduki orrialdea',
'notvisiblerev'        => 'Berrikusketa desegin da',
'watchnochange'        => 'Hautatutako denbora tartean ez da aldaketarik izan zure jarraipen zerrendako orrialdeetan.',
'watchlist-details'    => '{{PLURAL:$1|Orrialde $1|$1 orrialde}} jarraitzen, eztabaida orrialdeak kontuan hartu gabe.',
'wlheader-enotif'      => '* Posta bidezko ohartarazpena gaituta dago.',
'wlheader-showupdated' => "* Bisitatu zenituen azken alditik aldaketak izan dituzten orrialdeak '''beltzez''' nabarmenduta daude",
'watchmethod-recent'   => 'Aldaketa berriak aztertzen jarraipen zerrendako orrialdeen bila',
'watchmethod-list'     => 'jarraipen zerrendako orrialdeak aldaketa berrien bila aztertzen',
'watchlistcontains'    => 'Zure jarraipen zerrendak {{PLURAL:$1|orrialde $1 du|$1 orrialde ditu}}.',
'iteminvalidname'      => "Arazoa '$1' elementuarekin, baliogabeko izena...",
'wlnote'               => "Jarraian {{PLURAL:$2|ikus daiteke azken orduko|ikus daitezke azken '''$2''' orduetako}} azken {{PLURAL:$1|aldaketa|'''$1''' aldaketak}}.",
'wlshowlast'           => 'Erakutsi azken $1 orduak $2 egunak $3',
'watchlist-options'    => 'Jarraitze-zerrendaren aukerak',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Zerrendan gehitzen...',
'unwatching' => 'Zerrendatik kentzen...',

'enotif_mailer'                => '{{SITENAME}}(e)ko Oharpen Postaria',
'enotif_reset'                 => 'Orrialde guztiak bisitatu bezala markatu',
'enotif_newpagetext'           => 'Honako hau orrialde berria da.',
'enotif_impersonal_salutation' => '{{SITENAME}} erabiltzailea',
'changed'                      => 'aldatu',
'created'                      => 'sortu',
'enotif_subject'               => '{{SITENAME}}(e)ko $PAGETITLE orrialdea $PAGEEDITOR(e)k $CHANGEDORCREATED du',
'enotif_lastvisited'           => 'Jo $1 orrialdera zure azken bisitaz geroztik izandako aldaketa guztiak ikusteko.',
'enotif_lastdiff'              => 'Jo $1(e)ra aldaketa hau ikusteko.',
'enotif_anon_editor'           => '$1 erabiltzaile anonimoa',
'enotif_body'                  => 'Kaixo $WATCHINGUSERNAME,

{{SITENAME}}-(e)ko $PAGETITLE orrialdea $CHANGEDORCREATED egin da $PAGEEDITOR-(e)k une honetan: $PAGEEDITDATE, ikus $PAGETITLE_URL azken bertsiorako.

$NEWPAGE

Egilearen laburpena: $PAGESUMMARY $PAGEMINOREDIT

Egilearekin harremanetan jarri:
posta: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Ez dira oharpen gehiago bidaliko orrialde hau berriz bisitatzen ez baduzu.
Horrez gain, orrialdeen oharpen konfigurazioa leheneratu dezakezu jarraipen zerrendatik.

             Adeitasunez {{SITENAME}}(e)ko oharpen sistema

--
Zure epostaren jakinarazpenen konfigurazioa aldatzeko, ikus
{{fullurl:{{#special:Preferences}}}}

Zure jarraipen zerrendako konfigurazioa aldatzeko, ikus
{{fullurl:{{#special:Watchlist}}/edit}}

Orrialdea zure jarraipen zerrendatik ezabatzeko, ikus
$UNWATCHURL

Laguntza:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Orrialdea ezabatu',
'confirm'                => 'Baieztatu',
'excontent'              => "edukia hau zen: '$1'",
'excontentauthor'        => 'edukia hau zen: "$1" (parte hartu duen lankide bakarra: "[[Special:Contributions/$2|$2]]")',
'exbeforeblank'          => "hustu aurreko edukiera: '$1'",
'exblank'                => 'orrialdea hutsik zegoen',
'delete-confirm'         => '"$1" ezabatu',
'delete-legend'          => 'Ezabatu',
'historywarning'         => "'''Oharra:''' Ezabatzera zoazen orrialdeak $1 {{PLURAL:$1|berrikuspen du|berrikuspen ditu}} gutxi gorabehera:",
'confirmdeletetext'      => 'Orrialde edo irudi bat eta beste historia guztia datu-basetik ezabatzear zaude. Mesedez, egiaztatu hori egin nahi duzula, ondorioak zeintzuk diren badakizula, eta [[{{MediaWiki:Policy-url}}|politikak]] errespetatuz egingo duzula.',
'actioncomplete'         => 'Ekintza burutu da',
'actionfailed'           => 'Ekintzak huts egin du',
'deletedtext'            => '"<nowiki>$1</nowiki>" ezabatu egin da. Ikus $2 azken ezabaketen erregistroa ikusteko.',
'deletedarticle'         => '"[[$1]]" ezabatu da',
'suppressedarticle'      => '"[[$1]]" kendua',
'dellogpage'             => 'Ezabaketa erregistroa',
'dellogpagetext'         => 'Behean ikus daiteke azken ezabaketen zerrenda.',
'deletionlog'            => 'ezabaketa erregistroa',
'reverted'               => 'Lehenagoko berrikuspen batera itzuli da',
'deletecomment'          => 'Arrazoia:',
'deleteotherreason'      => 'Arrazoi gehigarria:',
'deletereasonotherlist'  => 'Beste arrazoi bat',
'deletereason-dropdown'  => '*Ezabatzeko ohiko arrazoiak
** Egileak eskatuta
** Egile eskubideak urratzea
** Bandalismoa',
'delete-edit-reasonlist' => 'Ezabaketa arrazoiak aldatu',
'delete-toobig'          => 'Orrialde honek aldaketa historia luzea du, {{PLURAL:$1|berrikuspen batetik|$1 berrikuspenetik}} gorakoa.
Orrialde horien ezabaketa mugatua dago {{SITENAME}}n ezbeharrak saihesteko.',
'delete-warning-toobig'  => 'Orrialde honek aldaketa historia luzea du, {{PLURAL:$1|berrikuspen batetik|$1 berrikuspenetik}} gorakoa.
Ezabatzeak ezbeharrak eragin ditzake {{SITENAME}}ren datu-basean;
kontu izan.',

# Rollback
'rollback'         => 'Aldaketak desegin',
'rollback_short'   => 'Desegin',
'rollbacklink'     => 'desegin',
'rollbackfailed'   => 'Desegiteak huts egin dud',
'cantrollback'     => 'Ezin da aldaketa desegin; erabiltzaile bakarrak hartu du parte.',
'alreadyrolled'    => 'Ezin da [[User:$2|$2]](e)k ([[User talk:$2|Eztabaida]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) [[$1]](e)n egindako azken aldaketa desegin;
beste norbaitek editatu du edo jada desegin du.

 Azken aldaketa [[User:$3|$3]](e)k ([[User talk:$3|Eztabaida]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) egin du.',
'editcomment'      => "Aldaketaren laburpena: \"''\$1''\".",
'revertpage'       => '[[Special:Contributions/$2|$2]] ([[User talk:$2|talk]]) wikilariaren aldaketak deseginda, edukia [[User:$1|$1]] wikilariaren azken bertsiora itzuli da.',
'rollback-success' => '$1 wikilariaren aldaketak deseginda,
edukia $2 wikilariaren azken bertsiora itzuli da.',

# Edit tokens
'sessionfailure-title' => 'Saio-akatsa',
'sessionfailure'       => 'Badirudi saioarekin arazoren bat dagoela; bandalismoak saihesteko ekintza hau ezeztatu egin da. Mesedez, nabigatzaileko "atzera" botoian klik egin, hona ekarri zaituen orrialde hori berriz kargatu, eta saiatu berriz.',

# Protect
'protectlogpage'              => 'Babes erregistroa',
'protectlogtext'              => 'Orrialdeen blokeo eta desblokeo zerrenda azaltzen da jarraian.',
'protectedarticle'            => '"[[$1]]" babestu da"',
'modifiedarticleprotection'   => '"[[$1]]"(r)en babes maila aldatu da',
'unprotectedarticle'          => '"[[$1]]"-(r)i babesa kendu zaio',
'movedarticleprotection'      => 'babes hobespenak "[[$2]]"tik "[[$1]]"(e)ra mugitu dira',
'protect-title'               => '"$1" babesten',
'prot_1movedto2'              => '$1 izenburua $2(r)engatik aldatu da',
'protect-legend'              => 'Babesa baieztatu',
'protectcomment'              => 'Arrazoia:',
'protectexpiry'               => 'Iraungipena:',
'protect_expiry_invalid'      => 'Babesaldiaren bukaerako data ez da baliozkoa.',
'protect_expiry_old'          => 'Babesaldiaren bukaera iraganekoa da.',
'protect-unchain-permissions' => 'Babes aukerak desblokeatu',
'protect-text'                => "'''<nowiki>$1</nowiki>''' orrialdearen babes maila ikusi eta aldatu egin beharko zenuke.",
'protect-locked-blocked'      => "Babes-mailak ezin dira aldatu blokeatuta dagoen bitartean.
Hemen daude '''$1''' orrialdearen egungo ezarpenak:",
'protect-locked-dblock'       => "Babes-mailak ezin dira aldatu, datu-basea blokeatuta baitago.
Hemen daude '''$1''' orriaren oraingo ezarpenak:",
'protect-locked-access'       => "Zure kontuak ez du baimenik babes mailak aldatzeko.
Hemen daude '''$1''' orrialderako oraingo ezarpenak:",
'protect-cascadeon'           => 'Orrialde hau babestuta dago orain, ondorengo orrialde {{PLURAL:$1|honek kaskada bidezko babesa aktibatua duelako|hauek kaskada bidezko babesa aktibatua dutelako}}.
Orrialde honen babes maila alda dezakezu, baina ez du eraginik izango kaskada bidezko babesean.',
'protect-default'             => 'Lankide guztiak baimendu',
'protect-fallback'            => '"$1" baimena eskatu',
'protect-level-autoconfirmed' => 'Lankide berri eta erregistratu gabekoak blokeatu',
'protect-level-sysop'         => 'Administratzaileak bakarrik',
'protect-summary-cascade'     => 'jauzian',
'protect-expiring'            => 'babesaldiaren bukaera: $1 (UTC)',
'protect-expiry-indefinite'   => 'mugagabea',
'protect-cascade'             => 'Babes masiboa - orrialde honen barneko orrialde guztiak blokeatu.',
'protect-cantedit'            => 'Ezin duzu orrialde honetako babes-maila aldatu, ez duzulako berau aldatzeko eskumenik.',
'protect-othertime'           => 'Beste denbora:',
'protect-othertime-op'        => 'beste denbora',
'protect-existing-expiry'     => 'Iraungitze ordua: $2, $3',
'protect-otherreason'         => 'Bestelako arrazoiak (edo gehigarriak):',
'protect-otherreason-op'      => 'Bestelako arrazoiak',
'protect-dropdown'            => '*Babesteko arrazoi ohikoenak
** Gehiegizko bandalismoa
** Gehiegizko spama
** Produkzioaren aurkakoa den edizio gerra
** Trafiko handiko orrialdea',
'protect-edit-reasonlist'     => 'Babesteko arrazoiak aldatu',
'protect-expiry-options'      => 'ordubete:1 hour,1 egun:1 day,astebete:1 week,2 aste:2 weeks,hilabete:1 month,3 hilabete:3 months,6 hilabete:6 months,urtebete:1 year,betiko:infinite',
'restriction-type'            => 'Baimena:',
'restriction-level'           => 'Murrizketa maila:',
'minimum-size'                => 'Tamaina minimoa',
'maximum-size'                => 'Tamaina handiena, maximoa:',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Aldatu',
'restriction-move'   => 'Mugitu',
'restriction-create' => 'Sortu',
'restriction-upload' => 'Igo',

# Restriction levels
'restriction-level-sysop'         => 'babestua',
'restriction-level-autoconfirmed' => 'erdi-babestua',
'restriction-level-all'           => 'edozein maila',

# Undelete
'undelete'                     => 'Ezabatutako orrialdeak ikusi',
'undeletepage'                 => 'Ezabatutako orrialdeak ikusi eta leheneratu',
'undeletepagetitle'            => "'''Hurrengoa [[:$1|$1]](r)en ezabatutako aldaketak dira'''.",
'viewdeletedpage'              => 'Ezabatutako orrialdeak ikusi',
'undeletepagetext'             => 'Jarraian zerrendatzen {{PLURAL:$1|den orrialdea ezabatu da baina oraindik artxiboan gordeta dago eta leheneratu egin daiteke.|diren orrialdeak ezabatu dira baina oraindik artxiboan gordeta daude eta leheneratu egin daitezke.}}
Artxiboa noizean behin hustu egin liteke.',
'undelete-fieldset-title'      => 'Berrikuspenak berrezarri',
'undeleteextrahelp'            => "Orrialde osoa leheneratzeko, koadrotxo guztiak hautatu gabe utzi eta '''''Leheneratu'''''n klik egin.
Aukeratutako leheneratze bat burutzeko, leheneratu nahi dituzun berrikuspenen koadrotxoak markatu eta '''''Leheneratu''''' klik egin.
'''''Hasiera'''''n klik eginez gero koadrotxo guztiak eta iruzkin koadroa hustu egingo dira.",
'undeleterevisions'            => '$1 {{PLURAL:$1|berrikuspen|berrikuspen}} artxibatuta',
'undeletehistory'              => 'Orrialdea leheneratzen baduzu, berrikuspena guztiak leheneratuko dira historian.
Ezabatu ondoren izen berdina duen orrialde berri bat sortzen bada leheneratutako berrikuspenak azalduko dira historian.',
'undeleterevdel'               => 'Berrezarpena ez da egingo goreneko orrialde edo fitxategia partzialki ezabatua suertatzen bada.
Kasu horietan ezabatutako azken aldaketen aukeraketa kendu edo agertarazi beharko dituzu.

Undeletion will not be performed if it will result in the top page or file revision being partially deleted.
In such cases, you must uncheck or unhide the newest deleted revision.',
'undeletehistorynoadmin'       => 'Artikulua ezabatu egin da. Ezabatzeko azalpena beheko laburpenean erakusten da, ezabatu aurretik parte hartu zuten erabiltzaileen xehetasunekin batera. Ezabatutako berrikuspenen oraingo testua administratzaileek bakarrik ikus dezakete.',
'undelete-revision'            => '$1(e)n berrikuspen $3(e)k ezabatu du ($4(e)ko $5(e)tan):',
'undeleterevision-missing'     => 'Baliogabeko berrikuspena. Baliteke lotura ezegokia izatea, edo berriskupena leheneratu edo kendu izana.',
'undelete-nodiff'              => 'Ez da aurkitu aurreko berrikuspenik.',
'undeletebtn'                  => 'Leheneratu',
'undeletelink'                 => 'ikusi/leheneratu',
'undeleteviewlink'             => 'ikusi',
'undeletereset'                => 'Hasieratu',
'undeleteinvert'               => 'Aukeraketa alderanztu',
'undeletecomment'              => 'Arrazoia:',
'undeletedarticle'             => '"[[$1]]" leheneratu da',
'undeletedrevisions'           => '{{PLURAL:$1|Berrikuspen 1 leheneratu da|$1 berrikuspen leheneratu dira}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|berrikuspen|berrikuspen}} eta {{PLURAL:$2|fitxategi|fitxategi}} leheneratu dira',
'undeletedfiles'               => '{{PLURAL:$1|fitxategi|fitxategi}} leheneratu dira',
'cannotundelete'               => 'Errorea birsortzerakoan; baliteke beste norbaitek lehenago birsortu izana.',
'undeletedpage'                => "'''$1 leheneratu egin da'''

[[Special:Log/delete|Ezabaketa erregistrora]] jo azken ezabaketa eta leheneraketak ikusteko.",
'undelete-header'              => 'Berriki ezabatutako orrialdeak ikusteko [[Special:Log/delete|ezabaketa erregistrora]] jo.',
'undelete-search-box'          => 'Ezabatutako orrialdeak bilatu',
'undelete-search-prefix'       => 'Honela hasten diren orrialdeak erakutsi:',
'undelete-search-submit'       => 'Bilatu',
'undelete-no-results'          => 'Ez da bat datorren orrialderik aurkitu ezabaketen artxiboan.',
'undelete-filename-mismatch'   => 'Ezin da $1 denbora-marka duten fitxategi aldaketa ezabatua berrezarri: fitxategi-izena ez dator bat',
'undelete-bad-store-key'       => 'Ezin da $1 denbora-marka duten fitxategi aldaketa ezabatua berrezarri: fitxategi ezabatu aurretik ez zegoen.',
'undelete-cleanup-error'       => 'Erabiltzen ez den "$1" fitxategia ezabatzen arazoa egon da.',
'undelete-missing-filearchive' => 'Fitxategiaren $1 IDa ezin da leheneratu, ez baitago datu-basean.
Aurretik ez luke ezabatua izan behar.',
'undelete-error-short'         => 'Errorea fitxategia berreskuratzerakoan: $1',
'undelete-error-long'          => 'Errorea gertatu da hurrengo orrialdea berreskuratzerakoan:

$1',
'undelete-show-file-confirm'   => 'Ziur zaude $3, $2-ko "<nowiki>$1</nowiki>" fitxategiaren ezabatutako berrikuspena ikusi nahi duzula?',
'undelete-show-file-submit'    => 'Bai',

# Namespace form on various pages
'namespace'      => 'Izen-tartea:',
'invert'         => 'Hautapena alderanztu',
'blanknamespace' => '(Nagusia)',

# Contributions
'contributions'       => 'Lankidearen ekarpenak',
'contributions-title' => '$1(r)entzat lankidearen ekarpenak',
'mycontris'           => 'Nire ekarpenak',
'contribsub2'         => '$1 ($2)',
'nocontribs'          => 'Ez da ezaugarri horiekin bat datorren aldaketarik aurkitu.',
'uctop'               => ' (Azken aldaketa)',
'month'               => 'Hilabetea (eta lehenagokoak):',
'year'                => 'Urtea (eta lehenagokoak):',

'sp-contributions-newbies'        => 'Soilik kontu berrien ekarpenak erakutsi',
'sp-contributions-newbies-sub'    => 'Hasiberrientzako',
'sp-contributions-newbies-title'  => 'Lankideen ekarpenak lankide berrietn',
'sp-contributions-blocklog'       => 'Blokeaketa erregistroa',
'sp-contributions-deleted'        => 'lankide-ekarpen ezabatuak',
'sp-contributions-uploads'        => 'igoerak',
'sp-contributions-logs'           => 'erregistroak',
'sp-contributions-talk'           => 'eztabaida',
'sp-contributions-userrights'     => 'erabiltzaile-baimenen kudeaketa',
'sp-contributions-blocked-notice' => 'Lankide hau une honetan blokeatuta dago.
Blokeo erregistroa azken sarrera ematen da azpian erreferentziarako:',
'sp-contributions-search'         => 'Ekarpenentzako bilaketa',
'sp-contributions-username'       => 'IP helbidea edo erabiltzaile izena:',
'sp-contributions-toponly'        => 'Azken aldaketak direnak soilik erakutsi',
'sp-contributions-submit'         => 'Bilatu',

# What links here
'whatlinkshere'            => 'Orri honetaranzko lotura dutenak',
'whatlinkshere-title'      => '$1(e)ra lotura duten orriak',
'whatlinkshere-page'       => 'Orrialdea:',
'linkshere'                => "Hauek dute '''[[:$1]]''' orrialderako lotura:",
'nolinkshere'              => "Ez dago '''[[:$1]]''' lotura duen orrialderik.",
'nolinkshere-ns'           => "Hautatutako izen-tartean ez dago '''[[:$1]]''' orrialderako lotura duenik.",
'isredirect'               => 'birzuzenketa orrialdea',
'istemplate'               => 'erabilpena',
'isimage'                  => 'fitxategi lotura',
'whatlinkshere-prev'       => '{{PLURAL:$1|aurrekoa|aurreko $1ak}}',
'whatlinkshere-next'       => '{{PLURAL:$1|hurrengoa|hurrengo $1ak}}',
'whatlinkshere-links'      => '← loturak',
'whatlinkshere-hideredirs' => '$1 birzuzenketak',
'whatlinkshere-hidetrans'  => '$1 transklusioak',
'whatlinkshere-hidelinks'  => '$1 loturak',
'whatlinkshere-hideimages' => '$1 irudiak loturak ditu',
'whatlinkshere-filters'    => 'Iragazleak',

# Block/unblock
'blockip'                         => 'Erabiltzailea blokeatu',
'blockip-title'                   => 'Erabiltzailea blokeatu',
'blockip-legend'                  => 'Erabiltzailea blokeatu',
'blockiptext'                     => 'IP helbide edo erabiltzaile izen bati idazketa baimenak kentzeko beheko formularioa erabil dezakezu. Ekintza hau bandalismoa saihesteko baino ez da burutu behar, eta beti ere [[{{MediaWiki:Policy-url}}|politikak]] errespetatuz. Blokeoaren arrazoi bat ere zehaztu ezazu (adibidez, orrialde batzuk zehaztuz).',
'ipaddress'                       => 'IP Helbidea',
'ipadressorusername'              => 'IP Helbidea edo erabiltzaile izena',
'ipbexpiry'                       => 'Iraungipena',
'ipbreason'                       => 'Arrazoia:',
'ipbreasonotherlist'              => 'Beste arrazoiak',
'ipbreason-dropdown'              => '*Blokeaketa arrazoi arruntak
** Benetakoa ez den informazioa ezartzea
** Orrialdetatik edukia ezabatzea
** Spam-a edota kanpoko loturak ezarri
** Bandalismoa egitea
** Beste erabiltzaileei mehatxatzea
** Kontu ugari erabiltzea
** Erabiltzaile izen desegokia',
'ipbanononly'                     => 'Blokeatu erabiltzaile anonimoak, beste inor ez',
'ipbcreateaccount'                => 'Kontua sortzea debekatu',
'ipbemailban'                     => 'Erabiltzaileak e-mailak bidal ditzan ekidin',
'ipbenableautoblock'              => 'Erabiltzaile honek erabilitako azken IP helbidea automatikoki blokeatu, eta baita erabili dezakeen beste edozein IP ere',
'ipbsubmit'                       => 'Erabiltzaile hau blokeatu',
'ipbother'                        => 'Beste denbora-tarte bat',
'ipboptions'                      => '2 ordu:2 hours,1 egun:1 day,3 egun:3 days,astebete:1 week,2 aste:2 weeks,hilabete:1 month,3 hilabete:3 months,6 hilabete:6 months,urtebete:1 year,betiko:infinite',
'ipbotheroption'                  => 'beste bat',
'ipbotherreason'                  => 'Arrazoi gehigarria:',
'ipbhidename'                     => 'Lankide izena aldaketa eta zerrendetatik ezkutatu',
'ipbwatchuser'                    => 'Erabiltzaile honen erabiltzaile eta eztabaida orrialdeak jarraitu',
'ipballowusertalk'                => 'Blokeatuta izanagatik ere, lankide honek bere eztabaida-orria aldatzea baimendu',
'ipb-change-block'                => 'Lankidea honako balioekin bir-blokeatu',
'badipaddress'                    => 'Baliogabeko IP helbidea',
'blockipsuccesssub'               => 'Blokeoa burutu da',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] erabiltzaileari blokeoa ezarri zaio.<br />
Ikus [[Special:IPBlockList|IP blokeoen zerrenda]] blokeoak aztertzeko.',
'ipb-edit-dropdown'               => 'Blokeatzeko arrazoiak aldatu',
'ipb-unblock-addr'                => '$1 lankide edo IP helbideari blokeoa baliogabetu',
'ipb-unblock'                     => 'Erabiltzaile izen edo IP helbide bati blokeoa kendu',
'ipb-blocklist'                   => 'Blokeaketak ikusi',
'ipb-blocklist-contribs'          => '$1(r)en ekarpenak',
'unblockip'                       => 'Erabiltzailea desblokeatu',
'unblockiptext'                   => 'Erabili beheko formularioa lehenago blokeatutako IP helbide edo erabiltzaile baten idazketa baimenak leheneratzeko.',
'ipusubmit'                       => 'Blokeoa ezabatu',
'unblocked'                       => '[[User:$1|$1]] desblokeatu egin da',
'unblocked-id'                    => '$1 blokeaketa ezabatu da',
'ipblocklist'                     => 'Blokeatutako erabiltzaileak',
'ipblocklist-legend'              => 'Blokeatutako erabiltzaile bat bilatu',
'ipblocklist-username'            => 'Erabiltzaile izena edo IP helbidea:',
'ipblocklist-sh-userblocks'       => 'Kontuaren blokeoak $1',
'ipblocklist-sh-tempblocks'       => 'Denbora baterako blokeoak $1',
'ipblocklist-sh-addressblocks'    => 'IP bakarreko blokeoak $1',
'ipblocklist-submit'              => 'Bilatu',
'ipblocklist-localblock'          => 'Tokiko blokeoa',
'ipblocklist-otherblocks'         => 'Bestelako {{PLURAL:$1|blokeoa|blokeoak}}',
'blocklistline'                   => '$1, $2(e)k $3 blokeatu du (iraungipena: $4)',
'infiniteblock'                   => 'infinitu',
'expiringblock'                   => 'blokeoaldiaren bukaera: $1, $2',
'anononlyblock'                   => 'anon. soilik',
'noautoblockblock'                => 'autoblokeoa ezgaituta',
'createaccountblock'              => 'kontua sortzea blokeatuta',
'emailblock'                      => 'e-posta blokeatuta',
'blocklist-nousertalk'            => 'zure buruaren eztabaida orrialdea ezin duzu aldatu',
'ipblocklist-empty'               => 'Blokeaketa zerrenda hutsik dago.',
'ipblocklist-no-results'          => 'Zehaztutako IP helbide edo erabiltzaile izena ez dago blokeatuta.',
'blocklink'                       => 'blokeatu',
'unblocklink'                     => 'blokeoa kendu',
'change-blocklink'                => 'blokeoa aldatu',
'contribslink'                    => 'ekarpenak',
'autoblocker'                     => '"[[User:$1|$1]]"(e)k berriki erabili duen IP helbidea duzulako autoblokeatu zaizu. $1(e)k emandako arrazoia zera da: "\'\'\'$2\'\'\'"',
'blocklogpage'                    => 'Blokeo erregistroa',
'blocklog-showlog'                => 'Lankide hau aurretik blokeatua izan da.
Blokeo erregistroa ematen da azpian erreferentziarako:',
'blocklog-showsuppresslog'        => 'Lankide hau aurretik blokeatua eta ezkutatua izan da.
Erregistroa ematen da azpian erreferentziarako:',
'blocklogentry'                   => '"[[$1]]" wikilariari blokeoa ezarri zaio. Blokeoaldia: $2 $3',
'reblock-logentry'                => '[[$1]] wikilariari blokeoaldia aldatu diogu. Blokeoaldi berria: $2 $3',
'blocklogtext'                    => 'Erabiltzaileen blokeoen ezarpen eta ezabaketen erregistroa da hau. Ez dira automatikoki blokeatutako IP helbideak zerrendatzen. Ikus [[Special:IPBlockList|IP blokeoen zerrenda]] aktibo dauden blokeoak aztertzeko.',
'unblocklogentry'                 => '$1 desblokeatu da',
'block-log-flags-anononly'        => 'erabiltzaile anonimoak bakarrik',
'block-log-flags-nocreate'        => 'kontuak sortzea ezgaituta',
'block-log-flags-noautoblock'     => 'auto-blokeaketa ezgaitu da',
'block-log-flags-noemail'         => 'e-posta blokeatuta',
'block-log-flags-nousertalk'      => 'ezin da aldatu norbere eztabaida-orria',
'block-log-flags-angry-autoblock' => 'hobetutako autoblokeoa gaituta',
'block-log-flags-hiddenname'      => 'lankide-izen ezkutua',
'range_block_disabled'            => 'Administratzaileak IP eremuak blokeatzeko gaitasuna ezgaituta dago.',
'ipb_expiry_invalid'              => 'Aldiaren bukaerako data ez da baliozkoa.',
'ipb_expiry_temp'                 => 'Izkutuan dauden lankide izenen blokeoa betierekikoa izan behar du.',
'ipb_hide_invalid'                => 'Ezin izan da kontu hau ezabatu; aldaketa asko izan baitezake.',
'ipb_already_blocked'             => '"$1" badago blokeatuta',
'ipb-needreblock'                 => '== Dagoeneko blokeaturik ==
$1 dagoeneko blokeaturik dago. Ezarpenak aldatu nahi al dituzu?',
'ipb-otherblocks-header'          => 'Bestelako {{PLURAL:$1|blokeoa|blokeoak}}',
'ipb_cant_unblock'                => 'Errorea: Ez da $1 IDa duen blokeoa aurkitu. Baliteke blokeoa jada kenduta egotea.',
'ipb_blocked_as_range'            => 'Akatsa: $1 IPa ez dago zuzenean blokeatuta eta ezin da blokeoa kendu.
Hala ere, $2-(r)en parte denez, blokeoa kendu daiteke.',
'ip_range_invalid'                => 'Baliogabeko IP eremua.',
'blockme'                         => 'Blokea nazazu',
'proxyblocker'                    => 'Proxy blokeatzailea',
'proxyblocker-disabled'           => 'Funtzio hau ez-gaitua dago.',
'proxyblockreason'                => 'Zure IP helbidea blokeatu egin da proxy ireki baten zaudelako. Mesedez, zure Interneteko Zerbitzu Hornitzailearekin harremanetan jar zaitez segurtasun arazo honetaz ohartarazteko.',
'proxyblocksuccess'               => 'Egina.',
'sorbsreason'                     => 'Zure IP helbidea proxy ireki bezala zerrendatuta dago DNSBLan.',
'sorbs_create_account_reason'     => 'Zure IP helbidea proxy ireki bezala zerrendatuta dago DNSBLan. Ezin duzu kontua sortu.',
'cant-block-while-blocked'        => 'Blokeatuta zauden bitartean ezin dituzu beste lankideak blokeatu.',
'ipbblocked'                      => 'Ezin dituzu beste erabiltzaileak blokeatu edo desblokeatu, zu zeu blokeatuta zaudelako',
'ipbnounblockself'                => 'Ez duzu baimenik zure buruari blokeoa kentzeko',

# Developer tools
'lockdb'              => 'Datu-basea blokeatu',
'unlockdb'            => 'Datu-basea desblokeatu',
'lockdbtext'          => 'Datu-basea blokeatzeak edozein erabiltzailek orrialdeak aldatzea, hobespenak aldatzea, jarraipen zerrendan aldaketak egitea, eta datu-basean edozein aldaketa behar duen edozein ekintza galaraziko du. Mesedez, baieztatu zure asmoa hori dela, eta blokeoa kenduko duzula mantenua burutu ondoren.',
'unlockdbtext'        => 'Datu-basea desblokeatzerakoan erabiltzaile guztiek orrialdeak aldatu, beraien hobespenak ezarri, jarraipen zerrendan aldaketak egin eta beste eragiketa batzuk burutzeko gaitasuna leheneratuko du. Mesedez, baieztatu egin nahi duzuna hori dela.',
'lockconfirm'         => 'Bai, datu-basea blokeatu nahi dut',
'unlockconfirm'       => 'Bai, datu-basea desblokeatu nahi dut',
'lockbtn'             => 'Datu-basea blokeatu',
'unlockbtn'           => 'Datu-basea desblokeatu',
'locknoconfirm'       => 'Ez duzu baieztapen kutxa hautatu.',
'lockdbsuccesssub'    => 'Datu-basea blokeatu egin da',
'unlockdbsuccesssub'  => 'Datu-basearen blokeoa kendu da',
'lockdbsuccesstext'   => 'Datu-basea blokeatu egin da. <br />Ez ahaztu mantenu lanak burutu ondoren [[Special:UnlockDB|blokeoa kentzeaz]].',
'unlockdbsuccesstext' => 'Datu-basea desblokeatu egin da.',
'lockfilenotwritable' => 'Ezin da datu-baseko blokeo fitxategian idatzi. Datu-basea blokeatu edo desblokeatzeko, zerbitzariak idazteko aukera izan beharra dauka.',
'databasenotlocked'   => 'Datu-basea ez dago blokeatuta.',

# Move page
'move-page'                    => '$1 mugitu',
'move-page-legend'             => 'Orrialdea mugitu',
'movepagetext'                 => "Beheko formularioa erabiliz orrialde baten izena aldatuko da, historia osoa izen berrira mugituz.
Izenburu zaharra izenburu berrira daraman birbideratze bilakatuko da.
Jatorrizko izenburura doazen birbideratzeak automatikoki egunera ditzakezu.
Ezetz aukeratzen baduzu, egiazta itzazu birbideratze [[Special:DoubleRedirects|bikoitz]] edo [[Special:BrokenRedirects|apurtuak]].
Loturak modu zuzenean mantentzea zure erantzukizuna da.

Konturatu zaitez orrialdea '''ez''' dela mugituko izenburu berria duen orrialde bat badago jadanik, ez bada aldaketa-historiarik gabeko orrialde huts edo birbideratze bat.
Horrek esan nahi du hanka sartzekotan orrialde baten jatorrizko izenburua berreskuratu daitekeela, baina ezin dela jada existitzen den orrialde baten gainean idatzi.

'''Oharra!'''

Aldaketa hau drastikoa eta ustekabekoa izan daiteke orrialde oso ezagunetan;
mesedez, egiazta ezazu honen ondorioak ulertzen dituzula, jarraitu baino lehen.",
'movepagetalktext'             => "Dagokion eztabaida orrialdea berarekin batera mugitu da, honako kasu hauetan '''ezik:'''
* Hutsik ez dagoen eztabaida orrialde bat existitzen bada izen berrian.
* Beheko koadroa hautatzen ez baduzu.

Kasu horietan orrialdea eskuz mugitu edo bestearekin bateratu beharko duzu.",
'movearticle'                  => 'Orrialdea mugitu',
'moveuserpage-warning'         => "'''Oharra:''' Lankide orrialde bat mugitzera zoaz. Kontutan izan orrialde bakarrik mugituko duzula eta '''ez''' duzula lankide izena aldatuko.",
'movenologin'                  => 'Saioa hasi gabe',
'movenologintext'              => 'Orrialde bat mugitzeko erregistratutako lankidea izan behar duzu eta [[Special:UserLogin|saioa hasi]].',
'movenotallowed'               => 'Ez daukazu orrialdeak mugitzeko baimenik.',
'movenotallowedfile'           => 'Ez duzu fitxategiak mugitzeko eskumenik.',
'cant-move-user-page'          => 'Ez duzu lankide orrialdeak mugitzeko eskumenik (azpiorrialdeetatik at).',
'cant-move-to-user-page'       => 'Ez duzu orrialde bat lankide orrialde batera mugitzeko eskumenik (lankide azpiorrialde batera izan ezik).',
'newtitle'                     => 'Izenburu berria',
'move-watch'                   => 'Orrialde hau jarraitu',
'movepagebtn'                  => 'Orrialde mugitu',
'pagemovedsub'                 => 'Mugimendua eginda',
'movepage-moved'               => '\'\'\'"$1" "$2"(e)ra mugitu da\'\'\'',
'movepage-moved-redirect'      => 'Birzuzenketa orrialde bat sortu da.',
'movepage-moved-noredirect'    => 'Birzuzenketa baten sorrera kendu da.',
'articleexists'                => 'Izen hori duen artikulu bat badago edo hautatutako izena ez da baliozkoa. Mesedez, beste izen bat aukeratu.',
'cantmove-titleprotected'      => 'Ezin duzu orrialde bat leku honetara mugitu izenburu berri hori sor ez dadin babesa duelako',
'talkexists'                   => "'''Orrialde hau arazorik gabe mugitu da, baina eztabaida orrialde ezin izan da mugitu izenburu berriarekin jada bat existitzen delako. Mesedez, eskuz batu itzazu biak.'''",
'movedto'                      => 'hona mugitu da:',
'movetalk'                     => 'Eztabaida orrialdea ere mugitu, ahal bada.',
'move-subpages'                => 'Azpiorrialde guztiak ($1-tik gora) mugitu',
'move-talk-subpages'           => 'Azpiorrialdeen eztabaida orrialde guztiak ($1-tik gora) mugitu',
'movepage-page-exists'         => '$1 orrialdea jada badago eta ezin da automatikoki gainetik idatzi.',
'movepage-page-moved'          => '$1 orrialdea $2(e)ra mugitu da.',
'movepage-page-unmoved'        => '$1 orrialdea ezin da $2(e)ra mugitu.',
'movepage-max-pages'           => '$1 {{PLURAL:$1|orrialderen|orrialdeen}} maximoa mugitu da eta jada ez dira gehiago mugituko modu automatikoan.',
'1movedto2'                    => '«[[$1]]» izenburuaren ordez, «[[$2]]» ezarri da',
'1movedto2_redir'              => '«[[$1]]» izenburuaren ordez, «[[$2]]» ezarri da, birzuzenketaren gainetik',
'move-redirect-suppressed'     => 'birzuzenketa ezabatua',
'movelogpage'                  => 'Mugimendu erregistroa',
'movelogpagetext'              => 'Mugitutako orrialdeen zerrenda bat azaltzen da jarraian.',
'movesubpage'                  => '{{PLURAL:$1|Azpiorrialde|Azpiorrialdeak}}',
'movesubpagetext'              => 'Orrialde honen {{PLURAL:$1|orrialde $1 erakusten da|$1 orrialdea erakusten dira}} azpian.',
'movenosubpage'                => 'Orrialde honek ez du azpiorrialderik.',
'movereason'                   => 'Arrazoia:',
'revertmove'                   => 'desegin',
'delete_and_move'              => 'Ezabatu eta mugitu',
'delete_and_move_text'         => '== Ezabatzeko beharra ==

"[[:$1]]" helburua existitzen da. Lekua egiteko ezabatu nahi al duzu?',
'delete_and_move_confirm'      => 'Bai, orrialdea ezabatu',
'delete_and_move_reason'       => 'Lekua egiteko ezabatu da',
'selfmove'                     => 'Helburu izenburua berdina da; ezin da orrialde bat bere gainera mugitu.',
'immobile-source-namespace'    => '"$1" motako orrialdeak ezin dira mugitu',
'immobile-target-namespace'    => 'Orrialdeak ezin dira "$1" motara mugitu',
'immobile-target-namespace-iw' => 'Interwiki lotura ez da baliagarria orrialdea mugitu ahal izateko.',
'immobile-source-page'         => 'Orrialde hau mugiezina da.',
'immobile-target-page'         => 'Helburuko orrialdera ezin da mugitu.',
'imagenocrossnamespace'        => 'Ezin da mugitu fitxategia fitxategiena ez den izen batera',
'imagetypemismatch'            => 'Fitxategiaren luzapen berriak ez du bere motako fitxategiekin bat egiten',
'imageinvalidfilename'         => 'Xede-artxiboaren izenak ez du balio',
'fix-double-redirects'         => 'Hasierako izenburura zuzentzen duten birzuzenketa guztiak aldatu',
'move-leave-redirect'          => 'Atzean birzuzenketa bat utzi',
'protectedpagemovewarning'     => "'''Oharra:''' Orrialde hau babestua izan da, beraz administratzaile eskumenak dituztenek alda dezakete bakarrik.
Azken erregistroko sarrera ematen da azpian erreferentzia gisa:",
'semiprotectedpagemovewarning' => "'''Oharra:''' Orrialde hau blokeatu dute, izena emanda duten erabiltzaileek soilik mugitu ahal dezaten. Erregistroko azken sarrera erakusten da jarraian erreferentzia gisa:",
'move-over-sharedrepo'         => '== Fitxategia badago ==
[[:$1]] badago datu-base partekatuan. Izenburu honetara fitxategi bat mugitzean partekatutako fitxategia gainezarriko du.',

# Export
'export'            => 'Orrialdeak esportatu',
'exporttext'        => 'Orrialde bat edo batzuen testua eta historia esportatu dezakezu XML fitxategi batzuetan. Ondoren, MediaWiki erabiltzen duen beste wiki baten jarri dezakezu [[Special:Import|import page]] orrialdea erabiliz.

Orrialdeak esportatzeko zehaztu hauen izenburuak beheko koadroan, izenburu bat lerroko, eta aukeratu zein bertsio esportatu nahi dituzun.

Horrez gain, lotura zuzena ere erabil dezakezu; adibidez, [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] "[[{{MediaWiki:Mainpage}}]]" orrialdearentzako.',
'exportcuronly'     => 'Oraingo berrikuspena bakarrik hartu, ez historia guztia',
'exportnohistory'   => "----
'''Oharra:''' Formulario honen bitartez orrialdeen historia osoak esportatzeko aukera ezgaitu egin da, errendimendua dela-eta.",
'export-submit'     => 'Esportatu',
'export-addcattext' => 'Orrialdeak gehitu kategoria honetatik:',
'export-addcat'     => 'Gehitu',
'export-addnstext'  => 'Izen-tarteko orrialdeak gehitu:',
'export-addns'      => 'Gehitu',
'export-download'   => 'Fitxategi moduan gordetzeko eskaini',
'export-templates'  => 'Txantiloiak barneratu',
'export-pagelinks'  => 'Sartu lotutako orriak honako sakoneran:',

# Namespace 8 related
'allmessages'                   => 'Sistemako mezu guztiak',
'allmessagesname'               => 'Izena',
'allmessagesdefault'            => 'Testu lehenetsia',
'allmessagescurrent'            => 'Oraingo testua',
'allmessagestext'               => 'MediaWikin erabiltzen diren mezu guztien zerrenda.
Mesedez bisitatu [http://www.mediawiki.org/wiki/Localisation MediaWiki] eta [http://translatewiki.net translatewiki.net] orrialdeak MediaWikira ekarpenak egin badituzu.',
'allmessagesnotsupportedDB'     => "Ezin da '''{{ns:special}}:Allmessages''' erabili '''\$wgUseDatabaseMessages''' ezgaituta dagoelako.",
'allmessages-filter-legend'     => 'Iragazi',
'allmessages-filter'            => 'Aldaketa-egoeraren arabera iragazi:',
'allmessages-filter-unmodified' => 'Aldatugabeak',
'allmessages-filter-all'        => 'Denak',
'allmessages-filter-modified'   => 'Aldatua',
'allmessages-prefix'            => 'Aurrizkiaren arabera iragazi:',
'allmessages-language'          => 'Hizkuntza:',
'allmessages-filter-submit'     => 'Joan',

# Thumbnails
'thumbnail-more'           => 'Handitu',
'filemissing'              => 'Fitxategia falta da',
'thumbnail_error'          => 'Errorea irudi txikia sortzerakoan: $1',
'djvu_page_error'          => 'DjVu orrialdea eremuz kanpo',
'djvu_no_xml'              => 'Ezinezkoa izan da DjVu fitxategiaren XML lortzea',
'thumbnail_invalid_params' => 'Irudi txikiaren ezarpenak ez dira baliagarriak',
'thumbnail_dest_directory' => 'Ezinezkoa izan da helburu direktorioa sortu',
'thumbnail_image-type'     => 'Irudi mota ez babestua',
'thumbnail_gd-library'     => 'GD liburutegiaren konfigurazio osagabea: $1 funtzioa falta da',
'thumbnail_image-missing'  => 'Fitxategirik ez dagoela dirudi: $1',

# Special:Import
'import'                     => 'Orrialdeak inportatu',
'importinterwiki'            => 'Wikien arteko inportazioa',
'import-interwiki-text'      => 'Aukeratu inportatzeko wiki eta orrialde izenburu bat. Berrikuspenen datak eta egileak gorde egingo dira. Inportazio ekintza guzti hauek [[Special:Log/import|inportazio erregistroan]] gordetzen dira.',
'import-interwiki-source'    => 'Jatorrizko wiki/orrialdea:',
'import-interwiki-history'   => 'Orrialde honen historiako bertsio guztiak kopiatu',
'import-interwiki-templates' => 'Txantiloi guztiak sartu',
'import-interwiki-submit'    => 'Inportatu',
'import-interwiki-namespace' => 'Helburuko izen-tartea:',
'import-upload-filename'     => 'Fitxategiaren izena:',
'import-comment'             => 'Iruzkina:',
'importtext'                 => 'Mesedez, jatorrizko wikitik orrialdea esportatzeko [[Special:Export|esportazio tresna]] erabil ezazu, zure diskoan gorde eta jarraian hona igo.',
'importstart'                => 'Orrialdeak inportatzen...',
'import-revision-count'      => '{{PLURAL:$1|berrikuspen 1|$1 berrikuspen}}',
'importnopages'              => 'Ez dago orrialderik inportatzeko.',
'importfailed'               => 'Inportazioak huts egin du: $1',
'importunknownsource'        => 'Inportazio iturri mota ezezaguna',
'importcantopen'             => 'Ezin izan da inportazio fitxategia ireki',
'importbadinterwiki'         => 'Interwiki lotura ezegokia',
'importnotext'               => 'Hutsik dago edo testurik gabe',
'importsuccess'              => 'Inportazioa burutu da!',
'importhistoryconflict'      => 'Gatazka sortzen ari den berrikuspen historia dago (baliteke orrialdea lehenago inportatu izana)',
'importnosources'            => 'Ez dago wikien arteko inportazio iturririk eta historialak zuzenean igotzea ezgaituta dago.',
'importnofile'               => 'Ez da inportazio fitxategirik igo.',
'importuploaderrorsize'      => 'Inportatutako artxiboaren igoera-porrota.
Artxiboa onartutako igoera-tamaina baino handiagoa da.',
'importuploaderrorpartial'   => 'Fitxategiaren igoera eta inportazioak huts egin du.
Fitxategiaren atal bat baino ez zen igo.',
'importuploaderrortemp'      => 'Inportatze fitxategiaren igoeran akatsa egon da. Karpeta tenporal bat falta da.',
'import-parse-failure'       => 'XML inportatze parseak akatsa izan du',
'import-noarticle'           => 'Ez dago inportatzeko orrialderik!',
'import-nonewrevisions'      => 'Berrikuspen guztiak aurrez inportatu ziren.',
'xml-error-string'           => '$1 $2 lerroan, $3 zutabean ($4 byte): $5',
'import-upload'              => 'Igo XML datuak',
'import-token-mismatch'      => 'Sesio data galdu da. Saia saitez berriro ere, mesedez.',
'import-invalid-interwiki'   => 'Ezin da esandako wikitik inportatu.',

# Import log
'importlogpage'                    => 'Inportazio erregistroa',
'importlogpagetext'                => 'Beste wiki batzutatik historial eta guzti egindako orrialdeen inportazio administratiboak.',
'import-logentry-upload'           => '[[$1]] igoera bitartez inportatu da',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|berrikuspen|berrikuspen}}',
'import-logentry-interwiki'        => '$1 wiki artean mugitu da',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|berrikuspen|berrikuspen}} $2-(e)tik',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Nire lankide orria',
'tooltip-pt-anonuserpage'         => 'Zure IParen lankide orrialdea',
'tooltip-pt-mytalk'               => 'Nire eztabaida orria',
'tooltip-pt-anontalk'             => 'Zure IParen eztabaida',
'tooltip-pt-preferences'          => 'Nire hobespenak',
'tooltip-pt-watchlist'            => 'Jarraitzen dituzun orrialdeen zerrenda.',
'tooltip-pt-mycontris'            => 'Nire ekarpenen zerrenda',
'tooltip-pt-login'                => 'Izen ematera gonbidatzen zaitugu.',
'tooltip-pt-anonlogin'            => 'Izen ematera gonbidatzen zaitugu.',
'tooltip-pt-logout'               => 'Saioa itxi',
'tooltip-ca-talk'                 => 'Artikuluari buruzko eztabaida',
'tooltip-ca-edit'                 => 'Artikulu hau aldatu dezakezu. Mesedez, aurrebista botoia erabil ezazu gorde baino lehen.',
'tooltip-ca-addsection'           => 'Iruzkin berria erantsi',
'tooltip-ca-viewsource'           => 'Artikulu hau babesturik dago. Bere kodea soilik ikus dezakezu.',
'tooltip-ca-history'              => 'Artikulu honen aurreko bertsioak.',
'tooltip-ca-protect'              => 'Artikulu hau babestu',
'tooltip-ca-unprotect'            => 'Orrialde honen babesa aldatu',
'tooltip-ca-delete'               => 'Artikulu hau ezabatu',
'tooltip-ca-undelete'             => 'Ezabatu baino lehenago egindako aldaketak berrezarri.',
'tooltip-ca-move'                 => 'Orrialde hau mugitu',
'tooltip-ca-watch'                => 'Orrialde hau jarraipen zerrendan gehitu',
'tooltip-ca-unwatch'              => 'Orrialde hau jarraipen zerrendatik kendu',
'tooltip-search'                  => 'Wiki honetan bilatu',
'tooltip-search-go'               => 'Baldin balego zehazki izen honetako orrialdera joan',
'tooltip-search-fulltext'         => 'Textu honetarako orriak bilatu',
'tooltip-p-logo'                  => 'Azala',
'tooltip-n-mainpage'              => 'Azala bisitatu',
'tooltip-n-mainpage-description'  => 'Azala bisitatu',
'tooltip-n-portal'                => 'Proiektuaren inguruan, zer egin dezakezu, non aurkitu nahi duzuna',
'tooltip-n-currentevents'         => 'Oraingo gertaeren inguruko informazio gehigarria',
'tooltip-n-recentchanges'         => 'Wikiko azken aldaketen zerrenda.',
'tooltip-n-randompage'            => 'Ausazko orrialde bat kargatu',
'tooltip-n-help'                  => 'Aurkitzeko lekua.',
'tooltip-t-whatlinkshere'         => 'Orri honetara lotura duten wiki orri guztien zerrenda',
'tooltip-t-recentchangeslinked'   => 'Orrialde honetatik lotutako orrialdeen azken aldaketak',
'tooltip-feed-rss'                => 'Orrialde honen RSS jarioa',
'tooltip-feed-atom'               => 'Orrialde honen atom jarioa',
'tooltip-t-contributions'         => 'Lankide honen ekarpen zerrenda ikusi',
'tooltip-t-emailuser'             => 'Lankide honi e-posta mezua bidali',
'tooltip-t-upload'                => 'Irudiak edo media fitxategiak igo',
'tooltip-t-specialpages'          => 'Aparteko orrialde guztien zerrenda',
'tooltip-t-print'                 => 'Orrialde honen bertsio inprimagarria',
'tooltip-t-permalink'             => 'Orrialde honen bertsio honetara lotura egonkorra',
'tooltip-ca-nstab-main'           => 'Eduki orrialdea ikusi',
'tooltip-ca-nstab-user'           => 'Lankide orrialdea ikusi',
'tooltip-ca-nstab-media'          => 'Media orrialdea ikusi',
'tooltip-ca-nstab-special'        => 'Hau aparteko orrialde bat da, ezin duzu orrialdea aldatu.',
'tooltip-ca-nstab-project'        => 'Proiektuaren orrialdea ikusi',
'tooltip-ca-nstab-image'          => 'Irudiaren orrialdea ikusi',
'tooltip-ca-nstab-mediawiki'      => 'Sistemaren mezua ikusi',
'tooltip-ca-nstab-template'       => 'Txantiloia ikusi',
'tooltip-ca-nstab-help'           => 'Laguntza orrialdea ikusi',
'tooltip-ca-nstab-category'       => 'Kategoria orrialdea ikusi',
'tooltip-minoredit'               => 'Aldaketa txiki bezala markatu hau',
'tooltip-save'                    => 'Zure aldaketak gorde',
'tooltip-preview'                 => 'Zure aldaketak aurreikusi, mesedez gorde aurretik erabili!',
'tooltip-diff'                    => 'Testuari egindako aldaketak erakutsi.',
'tooltip-compareselectedversions' => 'Orrialde honen bi hautatutako bertsioen arteko ezberdintasunak ikusi.',
'tooltip-watch'                   => 'Orrialde hau zure segimendu zerrendan gehitu',
'tooltip-recreate'                => 'Orrialdea birsortu ezabatu egin den arren',
'tooltip-upload'                  => 'Igoera hasi',
'tooltip-rollback'                => '"Desegin" ekintzak orriaren azken egilearen ekarpena ezabatzen du klik batean',
'tooltip-undo'                    => '"Desegin" botoiak egindako aldaketa ezeztatzen du eta aurreikuspen bista erakusten du.
Laburpenean arrazoi bat gehitzea baimentzen du',
'tooltip-preferences-save'        => 'Hobespenak gorde',
'tooltip-summary'                 => 'Laburpen labur bat sar ezazu',

# Stylesheets
'common.css'   => '/** Hemen idatzitako CSS kodeak itxura guztietan izango du eragina */',
'monobook.css' => '/* Hemen idatzitako CSS kodeak Monobook itxuran bakarrik izango du eragina */',

# Scripts
'common.js' => '/* Hemen idatzitako JavaScript kode oro erabiltzaile guztiek edozein orrialde irekitzerakoan kargatuko da. */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadatuak ezgaitu egin dira zerbitzari honetarako.',
'nocreativecommons' => 'Creative Commons RDF metadatuak ezgaitu egin dira zerbitzari honetarako.',
'notacceptable'     => 'Wiki zerbitzariak ezin ditu datuak zure bezeroak irakur ditzakeen formatu batean eskaini.',

# Attribution
'anonymous'        => '{{SITENAME}}(e)ko lankide {{PLURAL:$1|anonimoa|anonimoak}}',
'siteuser'         => '{{SITENAME}}(e)ko $1 erabiltzailea',
'anonuser'         => '{{SITENAME}}-(e)ko $1 erabiltzaile anonimoa',
'lastmodifiedatby' => 'Orrialdearen azken aldaketa: $2, $1. Nork: $3.',
'othercontribs'    => '$1(r)en lanean oinarrituta.',
'others'           => 'besteak',
'siteusers'        => '{{SITENAME}}(e)ko $1 {{PLURAL:$2|erabiltzailea|erabiltzaileak}}',
'anonusers'        => '{{SITENAME}}-(e)ko $1 {{PLURAL:$2|erabiltzaile}} anonimoak',
'creditspage'      => 'Orrialdearen kredituak',
'nocredits'        => 'Ez dago krediturik eskuragarri orrialde honentzako.',

# Spam protection
'spamprotectiontitle' => 'Spam-arengandik babesteko iragazkia',
'spamprotectiontext'  => 'Gorde nahi duzun orrialdea spam iragazkiak blokeatu zuen.
Baliteke zerrenda beltzean dagoen kanpo lotura batek sortzea arazo hori.',
'spamprotectionmatch' => 'Gure spam iragazkiak testu hau antzeman du: $1',
'spambot_username'    => 'MediaWikiren spam garbiketa',
'spam_reverting'      => '$1(e)rako loturarik ez daukan azken bertsiora itzultzen',
'spam_blanking'       => 'Berrikuspen guztiek $1(e)rako lotura zeukaten, husten',

# Info page
'infosubtitle'   => 'Orrialdearen informazioa',
'numedits'       => 'Aldaketa kopurua (artikulua): $1',
'numtalkedits'   => 'Aldaketa kopurua (eztabaida orrialdea): $1',
'numwatchers'    => 'Jarraitzaile kopurua: $1',
'numauthors'     => 'Egile ezberdinen kopurua (artikulua): $1',
'numtalkauthors' => 'Egile ezberdinen kopurua (eztabaida orrialdea): $1',

# Skin names
'skinname-standard'    => 'Lehenetsia',
'skinname-nostalgia'   => 'Nostalgia',
'skinname-cologneblue' => 'Cologne Blue',
'skinname-simple'      => 'Arrunta',
'skinname-modern'      => 'Modernoa',

# Math options
'mw_math_png'    => 'Beti PNG irudiak sortu',
'mw_math_simple' => 'Oso sinplea bada HTML, eta bestela PNG',
'mw_math_html'   => 'Posible bada HTML, eta bestela PNG',
'mw_math_source' => 'TeX bezala utzi (testu bidezko nabigatzaileentzako)',
'mw_math_modern' => 'Nabigatzaile berrientzako gomendatuta',
'mw_math_mathml' => 'MathML posible bada (proba fasean)',

# Math errors
'math_failure'          => 'Interpretazio errorea',
'math_unknown_error'    => 'errore ezezaguna',
'math_unknown_function' => 'funtzio ezezaguna',
'math_lexing_error'     => 'errore lexikoa',
'math_syntax_error'     => 'sintaxi errorea',
'math_image_error'      => 'PNG bilakatze errorea; egiaztatu latex eta dvipng (edo dvips + gs + convert) ongi instalatuta dauden begiratu',
'math_bad_tmpdir'       => 'Ezin da math direktorio tenporala sortu edo bertan idatzi',
'math_bad_output'       => 'Ezin da math direktorioa sortu edo bertan idatzi',
'math_notexvc'          => 'texvc exekutagarria falta da; mesedez, ikus math/README konfiguratzeko.',

# Patrolling
'markaspatrolleddiff'                 => 'Patruilatutzat markatu',
'markaspatrolledtext'                 => 'Artikulu hau patruilatutzat markatu',
'markedaspatrolled'                   => 'Patruilatutzat markatu da',
'markedaspatrolledtext'               => '[[:$1]]-(r)en bertsio hautatua patruilatutzat markatu da.',
'rcpatroldisabled'                    => 'Aldaketa berrien patruilaketa ezgaituta dago',
'rcpatroldisabledtext'                => 'Aldaketa berrien patruilaketa ezaugarria ezgaituta dago orain.',
'markedaspatrollederror'              => 'Ezin da patruilatutzat markatu',
'markedaspatrollederrortext'          => 'Patruilatutzat markatzeko berrikuspen bat hautatu behar duzu.',
'markedaspatrollederror-noautopatrol' => 'Ez daukazu zeure aldaketak patruilatutzat markatzeko baimenik.',

# Patrol log
'patrol-log-page'      => 'Patrullatze loga',
'patrol-log-header'    => 'Hau patruliatutako aldaketen log bat da.',
'patrol-log-line'      => '$1etik $2 markatu da patruilatu moduan $3',
'patrol-log-auto'      => '(automatikoa)',
'patrol-log-diff'      => '$1 berrikuspena',
'log-show-hide-patrol' => '$1 patruilatze loga',

# Image deletion
'deletedrevision'                 => '$1 berrikuspen zaharra ezabatu da',
'filedeleteerror-short'           => 'Errorea fitxategia ezabatzerakoan: $1',
'filedeleteerror-long'            => 'Erroreak gertatu dira fitxategia ezabatzerakoan:

$1',
'filedelete-missing'              => 'Ezin da "$1" fitxategia ezabatu, ez baita existitzen.',
'filedelete-old-unregistered'     => 'Hautatutako "$1" berrikuspena ez dago datu-basean.',
'filedelete-current-unregistered' => 'Hautatutako "$1" fitxategia ez dago datu-basean.',
'filedelete-archive-read-only'    => 'Web zerbitzariak ezin du "$1" karpetan idatzi.',

# Browsing diffs
'previousdiff' => '← Aldaketa zaharragoa',
'nextdiff'     => 'Aldaketa berriagoa →',

# Media information
'mediawarning'         => "'''Oharra''': Fitxategi honek kode mingarria izan lezake.
Zure sisteman exekutatzea arriskutsua izan liteke.",
'imagemaxsize'         => "Irudiaren tamainaren muga:<br />''(fitxategi deskribapen-orrietarako)''",
'thumbsize'            => 'Irudi txikiaren tamaina:',
'widthheightpage'      => '$1 × $2, $3 {{PLURAL:$3|orri|orri}}',
'file-info'            => 'fitxategiaren tamaina: $1, MIME mota: $2',
'file-info-size'       => '$1 × $2 pixel, fitxategiaren tamaina: $3, MIME mota: $4',
'file-nohires'         => '<small>Ez dago bereizmen handiagorik.</small>',
'svg-long-desc'        => 'SVG fitxategia, nominaldi $1 × $2 pixel, fitxategiaren tamaina: $3',
'show-big-image'       => 'Bereizmen handikoa',
'show-big-image-thumb' => '<small>Aurreikuspen honen neurria: $1 × $2 pixel</small>',
'file-info-gif-looped' => 'kiribildua',
'file-info-gif-frames' => '{{PLURAL:$1|Irudi $1|$1 irudi}}',
'file-info-png-looped' => 'begiztatua',
'file-info-png-frames' => '{{PLURAL:$1|Frame bat|$1 frame}}',

# Special:NewFiles
'newimages'             => 'Fitxategi berrien galeria',
'imagelisttext'         => "Jarraian duzu $2(e)z ordenatutako {{PLURAL:$1|fitxategi baten|'''$1''' fitxategiren}} zerrenda.",
'newimages-summary'     => 'Orrialde berezi honek igotako azkeneko fitxategiak erakusten ditu.',
'newimages-legend'      => 'Iragazkia',
'newimages-label'       => 'Fitxategia (edo bere zati bat):',
'showhidebots'          => '($1 bot-ak)',
'noimages'              => 'Ez dago ezer ikusteko.',
'ilsubmit'              => 'Bilatu',
'bydate'                => 'dataren arabera',
'sp-newimages-showfrom' => 'Irudi berriak erakutsi $1(e)ko $2tik hasita',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2×$3',
'seconds-abbrev' => 's',
'minutes-abbrev' => 'm',
'hours-abbrev'   => 'o',

# Bad image list
'bad_image_list' => 'Formatua hurrengoa da:

Zerrenda elementuak (hasieran * duten lerroak) baino ez dira kontuan hartzen. Lerro bateko lehen lotura irudi ezegoki batera zuzendutakoa izan behar da. Lerro bereko gainontzeko loturak salbuespentzat hartzen dira, adib. irudia izan dezaketen artikuluak.',

# Metadata
'metadata'          => 'Metadatuak',
'metadata-help'     => 'Fitxategi honek informazio gehigarri dauka, ziurrenik kamera digital edo eskanerrak egiterako momentuan gehitutakoa. Hori dela-eta, jatorrizko fitxategi hori aldatu egin bada, baliteke xehetasun batzuek errealitatearekin bat ez egitea.',
'metadata-expand'   => 'Xehetasunak erakutsi',
'metadata-collapse' => 'Xehetasunak ezkutatu',
'metadata-fields'   => 'Mezu honetan zerrendatutako EXIF metadatu eremuak irudiaren orrialdean erakutsiko dira. Gainontzekoak ezkutatu egindako dira lehenetsi bezala.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Zabalera',
'exif-imagelength'                 => 'Altuera',
'exif-bitspersample'               => 'Bit osagaiko',
'exif-compression'                 => 'Konpresio eskema',
'exif-photometricinterpretation'   => 'Pixelen konposaketa',
'exif-orientation'                 => 'Orientazioa',
'exif-samplesperpixel'             => 'Atal kopurua',
'exif-planarconfiguration'         => 'Datuen banaketa',
'exif-ycbcrsubsampling'            => 'Ytik Crako azpisanpleatu erlazioa',
'exif-ycbcrpositioning'            => 'Y eta Cren kokatzea',
'exif-xresolution'                 => 'Bereizmen horizontala',
'exif-yresolution'                 => 'Bereizmen bertikala',
'exif-resolutionunit'              => 'X eta Yren erresoluzioen batura',
'exif-stripoffsets'                => 'Irudiaren datuen kokalekua',
'exif-rowsperstrip'                => 'Zutabe bakoitzean dauden lerro kopurua',
'exif-stripbytecounts'             => 'Konprimatutako zerrenda bakoitzeko byte kopurua',
'exif-jpeginterchangeformat'       => 'JPEG SOIren offseta',
'exif-jpeginterchangeformatlength' => 'JPEG datuen byteak',
'exif-transferfunction'            => 'Transferentzia funtzioa',
'exif-whitepoint'                  => 'Puntu txuriaren kromatizitatea',
'exif-primarychromaticities'       => 'Primarioen kromatizitateak',
'exif-ycbcrcoefficients'           => 'Kolore espzioaren aldatze koefiziente matrizeak',
'exif-referenceblackwhite'         => 'Txuri eta beltzaren erreferentzia balioen parea',
'exif-datetime'                    => 'Fitxategi aldaketaren data eta ordua',
'exif-imagedescription'            => 'Irudiaren izenburua',
'exif-make'                        => 'Kameraren fabrikatzailea',
'exif-model'                       => 'Kamara mota',
'exif-software'                    => 'Erabilitako softwarea',
'exif-artist'                      => 'Egilea',
'exif-copyright'                   => 'Copyright-aren jabea',
'exif-exifversion'                 => 'Exif bertsioa',
'exif-flashpixversion'             => 'Gaitutako Flashpix bertsioa',
'exif-colorspace'                  => 'Kolore tartea',
'exif-componentsconfiguration'     => 'Osagai bakoitzaren esanahia',
'exif-compressedbitsperpixel'      => 'Irudi konpresio mota',
'exif-pixelydimension'             => 'Irudiaren zabalera',
'exif-pixelxdimension'             => 'Irudiaren altuera',
'exif-makernote'                   => 'Egilearen oharrak',
'exif-usercomment'                 => 'Erabiltzailearen iruzkinak',
'exif-relatedsoundfile'            => 'Harremanetan dagoen audio fitxategia',
'exif-datetimeoriginal'            => 'Datuen sorreraren data eta ordua',
'exif-datetimedigitized'           => 'Digitalizazioaren data eta ordua',
'exif-subsectime'                  => 'DataDenbora azpisegunduak',
'exif-subsectimeoriginal'          => 'DataDenboraOrijinala azpisegunduak',
'exif-subsectimedigitized'         => 'DataDenboraDigitalizatu azpisekunduak',
'exif-exposuretime'                => 'Esposizio denbora',
'exif-exposuretime-format'         => '$1 seg ($2)',
'exif-fnumber'                     => 'F Zenbakia',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Esposizio Programa',
'exif-spectralsensitivity'         => 'Sentiberatasun espektrala',
'exif-isospeedratings'             => 'ISO abiadura',
'exif-oecf'                        => 'Aldatze faktore optoelektronikoa',
'exif-shutterspeedvalue'           => 'APEX argazkiaren itxiera-abiadura',
'exif-aperturevalue'               => 'APEX irekiera',
'exif-brightnessvalue'             => 'APEX distira',
'exif-exposurebiasvalue'           => 'Esposizio biasa',
'exif-maxaperturevalue'            => 'Gehienezko landa irekiera',
'exif-subjectdistance'             => 'Subjetuarekiko distantzia',
'exif-meteringmode'                => 'Distantzia-neurtze modua',
'exif-lightsource'                 => 'Argiaren jatorria',
'exif-flash'                       => 'Flash',
'exif-focallength'                 => 'Lentearen fokatze luzera',
'exif-focallength-format'          => '$1 mm',
'exif-subjectarea'                 => 'Subjektuaren zonaldea',
'exif-flashenergy'                 => 'Flasharen indarra',
'exif-spatialfrequencyresponse'    => 'Frekuentzia espazialarekiko erantzuna',
'exif-focalplanexresolution'       => 'Plano fokalaren X erresoluzioa',
'exif-focalplaneyresolution'       => 'Plano fokalaren Y erresoluzioa',
'exif-focalplaneresolutionunit'    => 'Plano fokalaren erresoluzio unitatea',
'exif-subjectlocation'             => 'Subjektuaren kokalekua',
'exif-exposureindex'               => 'Esposaketa indizea',
'exif-sensingmethod'               => 'Sensorearen metodoa',
'exif-filesource'                  => 'Fitxategiaren jatorria',
'exif-scenetype'                   => 'Eskena mota',
'exif-cfapattern'                  => 'CFA patroia',
'exif-customrendered'              => 'Irudien prozesamendu pertsonalizatua',
'exif-exposuremode'                => 'Esposaketa mota',
'exif-whitebalance'                => 'Txurien oreka',
'exif-digitalzoomratio'            => 'Zoom digitalaren eskala',
'exif-focallengthin35mmfilm'       => '35 mm.ko filmean luzera fokala',
'exif-scenecapturetype'            => 'Eskena hartze mota',
'exif-gaincontrol'                 => 'Eskena kontrola',
'exif-contrast'                    => 'Kontrastea',
'exif-saturation'                  => 'Asetasuna',
'exif-sharpness'                   => 'Fokatzea',
'exif-devicesettingdescription'    => 'Gailuaren konfigurazio deskribapena',
'exif-subjectdistancerange'        => 'Subjektuaren distantzia balio-eremua',
'exif-imageuniqueid'               => 'Irudiaren ID bakarra',
'exif-gpsversionid'                => 'GPS etiketa bertsioa',
'exif-gpslatituderef'              => 'Iparraldeko edo hegoaldeko latitudea',
'exif-gpslatitude'                 => 'Latitudea',
'exif-gpslongituderef'             => 'Ekialdeko edo mendebaldeko longitudea',
'exif-gpslongitude'                => 'Longitudea',
'exif-gpsaltituderef'              => 'Garaiera erreferentzia',
'exif-gpsaltitude'                 => 'Garaiera',
'exif-gpstimestamp'                => 'GPS ordua (erloju atomikoa)',
'exif-gpssatellites'               => 'Neurketarako erabilitako sateliteak',
'exif-gpsstatus'                   => 'Hartzailearen egoera',
'exif-gpsmeasuremode'              => 'Neurketarako modua',
'exif-gpsdop'                      => 'Neurketaren zehaztasuna',
'exif-gpsspeedref'                 => 'Abiadura unitatea',
'exif-gpsspeed'                    => 'GPS hartzailearen abiadura',
'exif-gpstrackref'                 => 'Mugimenduaren norabidearentzako erreferentzia',
'exif-gpstrack'                    => 'Mugimenduaren norabidea',
'exif-gpsimgdirectionref'          => 'Irudiaren norabidearentzako erreferentzia',
'exif-gpsimgdirection'             => 'Irudiaren norabidea',
'exif-gpsmapdatum'                 => 'Ikerketa geodetikorako erabilitako datuak',
'exif-gpsdestlatituderef'          => 'Helburu eta latituderako erreferentzia',
'exif-gpsdestlatitude'             => 'Latitude helburua',
'exif-gpsdestlongituderef'         => 'Luzera eta helbururako erreferentzia',
'exif-gpsdestlongitude'            => 'Helburuaren luzera',
'exif-gpsdestbearingref'           => 'Helburuaren norabiderako erreferentzia',
'exif-gpsdestbearing'              => 'Helburuaren norabidea',
'exif-gpsdestdistanceref'          => 'Helbururako dagoen distantziarako erreferentzia',
'exif-gpsdestdistance'             => 'Helburuarekiko distantzia',
'exif-gpsprocessingmethod'         => 'GPS prozesamendu metodoaren izena',
'exif-gpsareainformation'          => 'GPS eskualdearen izena',
'exif-gpsdatestamp'                => 'GPS data',
'exif-gpsdifferential'             => 'GPSaren zuzenketa diferentziala',
'exif-objectname'                  => 'Izenburua laburra',

# EXIF attributes
'exif-compression-1' => 'Konprimatu gabe',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'GBU (RGB)',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-unknowndate' => 'Data ezezaguna',

'exif-orientation-1' => 'Arrunta',
'exif-orientation-2' => 'Horizontalki buelta emana',
'exif-orientation-3' => '180° biratuta',
'exif-orientation-4' => 'Bertikalki buelta emana',
'exif-orientation-5' => 'Erlojuaren aurka 90º biratuta eta bertikalki buelta emana',
'exif-orientation-6' => 'Erlojuaren norantzaren aurka 90º biratuta',
'exif-orientation-7' => 'Erlojuaren norantzan 90º biratuta eta bertikalki buelta emana',
'exif-orientation-8' => 'Erlojuaren norantzan 90º biratuta',

'exif-planarconfiguration-1' => 'formatu potoloa',
'exif-planarconfiguration-2' => 'formatu planarra',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'ez da existitzen',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'G',
'exif-componentsconfiguration-5' => 'B',
'exif-componentsconfiguration-6' => 'U',

'exif-exposureprogram-0' => 'Definitu gabe',
'exif-exposureprogram-1' => 'Eskuz',
'exif-exposureprogram-2' => 'Programa arrunta',
'exif-exposureprogram-3' => 'Irekiera prioritatea',
'exif-exposureprogram-4' => 'Abiaduraren prioritatea',
'exif-exposureprogram-5' => 'Programa kreatiboa (eremu sakonera lortze bidean)',
'exif-exposureprogram-6' => 'Akzioa (argazki abiadura azkarra lortze bidean)',
'exif-exposureprogram-7' => 'Erretratu modua (atzealde ez fokatua duten gertuko argazkientzat)',
'exif-exposureprogram-8' => 'Paisaia modua (atzealde fokatua duten paisaia argazkientzat)',

'exif-subjectdistance-value' => '$1 metro',

'exif-meteringmode-0'   => 'Ezezaguna',
'exif-meteringmode-1'   => 'Bataz bestekoa',
'exif-meteringmode-2'   => 'ZentruanNeurketaBatazBestekoa',
'exif-meteringmode-3'   => 'Puntua',
'exif-meteringmode-4'   => 'MultiPuntua',
'exif-meteringmode-5'   => 'Eredua',
'exif-meteringmode-6'   => 'Partziala',
'exif-meteringmode-255' => 'Beste bat',

'exif-lightsource-0'   => 'Ezezaguna',
'exif-lightsource-1'   => 'Egun argia',
'exif-lightsource-2'   => 'Fluoreszentea',
'exif-lightsource-3'   => 'Wolframioa (argi inkandeszentea)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Eguraldi ona',
'exif-lightsource-10'  => 'Eguraldi lainotsua',
'exif-lightsource-11'  => 'Itzala',
'exif-lightsource-12'  => 'Egun argiko fluoreszentea (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Eguneko fluoreszente txuria (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluoreszente txuri hotza (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluoreszente txuria (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'A argi estandarra',
'exif-lightsource-18'  => 'B argi estandarra',
'exif-lightsource-19'  => 'C argi estandarra',
'exif-lightsource-20'  => 'D55',
'exif-lightsource-21'  => 'D65',
'exif-lightsource-22'  => 'D75',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'ISO estudio wolframioa',
'exif-lightsource-255' => 'Beste argi iturburu bat',

# Flash modes
'exif-flash-fired-0'    => 'Flashik gabe',
'exif-flash-fired-1'    => 'Flasharekin',
'exif-flash-return-0'   => 'ez dauka estrobo itzulera detekzio funtziorik',
'exif-flash-return-2'   => 'estrobo itzulera argirik ez da detektatu',
'exif-flash-return-3'   => 'estrobo itzulera argia detektatu da',
'exif-flash-mode-1'     => 'flashak derrigorrez bota du argia',
'exif-flash-mode-2'     => 'flasha derrigorrez kendu da',
'exif-flash-mode-3'     => 'auto modua',
'exif-flash-function-1' => 'Ez dauka flash funtziorik',
'exif-flash-redeye-1'   => 'begi-gorriak kentzeko modua',

'exif-focalplaneresolutionunit-2' => 'hazbete',

'exif-sensingmethod-1' => 'Definitu gabea',
'exif-sensingmethod-2' => 'Txip bakarreko kolorezko eremu sentsorea',
'exif-sensingmethod-3' => 'Bi txipeko kolorezko eremu sentsorea',
'exif-sensingmethod-4' => 'Hiru txipeko kolorezko eremu sentsorea',
'exif-sensingmethod-5' => 'Kolore sekuentzialeko eremu sentsorea',
'exif-sensingmethod-7' => 'Hiru lerroko sentsorea',
'exif-sensingmethod-8' => 'Kolore sekuentzialeko sentsore linearra',

'exif-filesource-3' => 'Argazki kamera digitala',

'exif-scenetype-1' => 'Zuzenean argazkia atera zaion irudi bat',

'exif-customrendered-0' => 'Prozesu arrunta',
'exif-customrendered-1' => 'Prozesu pertsonalizatua',

'exif-exposuremode-0' => 'Esposizio automatikoa',
'exif-exposuremode-1' => 'Eskuzko esposizioa',
'exif-exposuremode-2' => 'Bracket automatikoa',

'exif-whitebalance-0' => 'Zurien balantze automatikoa',
'exif-whitebalance-1' => 'Zurien eskuzko balantzea',

'exif-scenecapturetype-0' => 'Arrunta',
'exif-scenecapturetype-1' => 'Paisaia',
'exif-scenecapturetype-2' => 'Erretratua',
'exif-scenecapturetype-3' => 'Gau eskena',

'exif-gaincontrol-0' => 'Ezer',
'exif-gaincontrol-1' => 'Gain igotze baxua',
'exif-gaincontrol-2' => 'Gain igotze altua',
'exif-gaincontrol-3' => 'Gain beheragotze baxua',
'exif-gaincontrol-4' => 'Gain beheratze altua',

'exif-contrast-0' => 'Arrunta',
'exif-contrast-1' => 'Leuna',
'exif-contrast-2' => 'Zakarra',

'exif-saturation-0' => 'Arrunta',
'exif-saturation-1' => 'Asetasun baxua',
'exif-saturation-2' => 'Asetasun altua',

'exif-sharpness-0' => 'Arrunta',
'exif-sharpness-1' => 'Leuna',
'exif-sharpness-2' => 'Zakarra',

'exif-subjectdistancerange-0' => 'Ezezaguna',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Ikuspegi itxia',
'exif-subjectdistancerange-3' => 'Urruneko ikuspegia',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Iparraldeko latitudea',
'exif-gpslatitude-s' => 'Hegoaldeko latitudea',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Ekialdeko longitudea',
'exif-gpslongitude-w' => 'Mendebaldeko longitudea',

'exif-gpsstatus-a' => 'Neurketa burutzen',
'exif-gpsstatus-v' => 'Neurketen interoperabilitatea',

'exif-gpsmeasuremode-2' => '2 dimentsioko neurketa',
'exif-gpsmeasuremode-3' => '3 dimentsioko neurketa',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometro orduko',
'exif-gpsspeed-m' => 'Milia orduko',
'exif-gpsspeed-n' => 'Lotailuak',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Benetako norabidea',
'exif-gpsdirection-m' => 'Norabide magnetikoa',

# External editor support
'edit-externally'      => 'Fitxategi hau editatu kanpo-aplikazio bat erabiliz',
'edit-externally-help' => '(Ikus [http://www.mediawiki.org/wiki/Manual:External_editors konfiguraziorako argibideak] informazio gehiagorako)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'guztiak',
'imagelistall'     => 'guztiak',
'watchlistall2'    => 'guztiak',
'namespacesall'    => 'guztiak',
'monthsall'        => 'guztiak',
'limitall'         => 'guztiak',

# E-mail address confirmation
'confirmemail'             => 'E-posta helbidea egiaztatu',
'confirmemail_noemail'     => 'Ez daukazu e-posta helbiderik zehaztuta zure [[Special:Preferences|hobespenetan]].',
'confirmemail_text'        => 'Wiki honetan zure e-posta helbidea egiaztatzea beharrezkoa da e-postarekin zerikusia duten ezaugarriak erabili aurretik. Beheko botoia jo zure helbidera egiaztapen mezu bat bidaltzeko. Mezuan kode bat duen lotura bat joango da atxikita; lotura hori zure nabigatzailean ireki ezazu e-posta helbidea egiaztatzeko.',
'confirmemail_pending'     => 'Egiaztapen kode bat bidali zaizu jada; erabiltzaile kontua duela gutxi sortu baduzu, mezua iritsi bitartean minutu batzuk itxaron beharko zenituzke kode berri bat eskatu aurretik.',
'confirmemail_send'        => 'Egiaztapen kodea e-postaz bidali',
'confirmemail_sent'        => 'Egiaztapen mezua bidali da.',
'confirmemail_oncreate'    => 'Egiaztapen kodea bidali zaizu zure e-posta helbidera. Kode hau ez da beharrezkoa saioa hasteko, baina bai wikiak eskaintzen dituen e-posta zerbitzuez profitatzeko.',
'confirmemail_sendfailed'  => '{{SITENAME}}(e)k ezin izan du egiaztapen mezua bidali.
Ziurtatu e-posta helbidean baliogabeko karaktererik ez dagoela.

Zerbitzariaren mezua: $1',
'confirmemail_invalid'     => 'Baliogabeko egiaztapen kodea. Baliteke kodea iraungi izana.',
'confirmemail_needlogin'   => '$1 behar duzu zure e-posta helbidea egiaztatzeko.',
'confirmemail_success'     => 'Zure e-posta helbidea egiaztatu da. Saioa hasi eta ekarpenak egin ditzakezu orain.',
'confirmemail_loggedin'    => 'Zure e-posta helbidea egiaztatu da.',
'confirmemail_error'       => 'Akatsen bat gertatu da egiaztapena burutzerakoan.',
'confirmemail_subject'     => 'E-posta helbide egiaztapena {{SITENAME}}(e)n',
'confirmemail_body'        => 'Norbaitek, ziurrenik zuk $1 IP helbidetik, "$2" kontua erregistratu du {{SITENAME}}(e)n e-posta helbide honekin.

Izen hori zuri dagokizula eta {{SITENAME}}(e)n zure e-posta egiaztatzeko, hurrengo lotura hau zure nabigatzailean ireki behar duzu:

$3

Zu *ez* bazara, ez jo lotura horretara, jarraitu beste lotura hau e-posta bidezko helbide egiaztatzea ezeztatzeko:

$5

Egiaztapen kode hau $4 iraungiko da.',
'confirmemail_invalidated' => 'E-mail bidezko ziurtatzea kantzelatu da',
'invalidateemail'          => 'E-mail bidezko ziurtatzea deuseztu',

# Scary transclusion
'scarytranscludedisabled' => '[Interwikien transklusioa ezgaituta dago]',
'scarytranscludefailed'   => '[Arazoa $1 txantiloia eskuratzerakoan]',
'scarytranscludetoolong'  => '[URLa luzeegia da]',

# Trackbacks
'trackbackbox'      => 'Artikulu honen aipuak:<br />
$1',
'trackbackremove'   => '([$1 Ezabatu])',
'trackbacklink'     => 'Aipua',
'trackbackdeleteok' => 'Aipua ezabatu egin da.',

# Delete conflict
'deletedwhileediting' => "'''Oharra''': Zu aldaketak egiten hasi ondoren orrialdea ezabatua izan da!",
'confirmrecreate'     => "[[User:$1|$1]] erabiltzaileak ([[User talk:$1|eztabaida]]) orrialde hau ezabatu zu aldatzen hasi eta gero. Hona arrazoia: : ''$2'' Mesedez, baieztatu orrialde hau berriz sortu nahi duzula.",
'recreate'            => 'Birsortu',

# action=purge
'confirm_purge_button' => 'Ados',
'confirm-purge-top'    => 'Orrialde honen katxea ezabatu?',
'confirm-purge-bottom' => 'Orrialdea purgatzean katxea ezabatzen du eta orrialdearen bertsiorik eguneratuena erakustera behartzen du.',

# Multipage image navigation
'imgmultipageprev' => '&larr; aurreko orrialdea',
'imgmultipagenext' => 'hurrengo orrialdea &rarr;',
'imgmultigo'       => 'Joan!',
'imgmultigoto'     => '$1 orrialdera joan',

# Table pager
'ascending_abbrev'         => 'gor',
'descending_abbrev'        => 'behe',
'table_pager_next'         => 'Hurrengo orrialdea',
'table_pager_prev'         => 'Aurreko orrialdea',
'table_pager_first'        => 'Lehen orrialdea',
'table_pager_last'         => 'Azken orrialdea',
'table_pager_limit'        => 'Orrialdeko $1 elementu erakutsi',
'table_pager_limit_label'  => 'Gaiak orrialdeko:',
'table_pager_limit_submit' => 'Joan',
'table_pager_empty'        => 'Emaitzik ez',

# Auto-summaries
'autosumm-blank'   => 'Orritik eduki guztia ezabatuta',
'autosumm-replace' => 'Orriaren edukiaren ordez, «$1» jarri da',
'autoredircomment' => '[[$1]] orrialdera birzuzentzentzen',
'autosumm-new'     => 'Orrialde berria $1-(e)kin sortua',

# Live preview
'livepreview-loading' => 'Kargatzen…',
'livepreview-ready'   => 'Kargatzen… Prest!',
'livepreview-failed'  => 'Huts egin du berehalako aurreikuspenak! Saiatu aurreikuspen normala erabiltzen.',
'livepreview-error'   => 'Ezin izan da konektatu: $1 "$2". Saiatu aurreikuspen normala erabiltzen.',

# Friendlier slave lag warnings
'lag-warn-normal' => '{{PLURAL:$1|segundu $1|$1 segundu}} baino berriagoak diren aldaketak ez dira zerrenda honetan agertuko.',
'lag-warn-high'   => 'Zerbitzariaren atzerapen handia dela eta, {{PLURAL:$1|segundu $1|$1 segundu}} baino berriagoak diren aldaketak baliteke zerrenda honetan ez azaltzea.',

# Watchlist editor
'watchlistedit-numitems'       => 'Zure jarraipen zerrendak {{PLURAL:$1|titulu bat du|$1 titulu ditu}}, eztabaida orrialdeak kenduta.',
'watchlistedit-noitems'        => 'Zure jarraitze-zerrendak ez du izenbururik.',
'watchlistedit-normal-title'   => 'Jarraitze zerrenda aldatu',
'watchlistedit-normal-legend'  => 'Jarraipen-zerrendatik izenburuak kendu',
'watchlistedit-normal-explain' => 'Zure jarraipen zerrendako izenburuak azpian daude.
Titulu bat kentzeko ondoan dagoen kutxa marka ezazu eta "{{int:Watchlistedit-normal-submit}}" gainean klik egin.
Gainera [[Special:Watchlist/raw|zerrenda gordina aldatu]] dezakezu.',
'watchlistedit-normal-submit'  => 'Izenburuak kendu',
'watchlistedit-normal-done'    => 'Zure jarraipen-zerrendatik {{PLURAL:$1|izenburu bat kendu da|$1 izenburu kendu dira}}.',
'watchlistedit-raw-title'      => 'Jarraitze zerrenda gordina aldatu',
'watchlistedit-raw-legend'     => 'Jarraitze zerrenda gordina aldatu',
'watchlistedit-raw-explain'    => 'Azpian zure jarraipen zerrendako izenburuak daude, eta aldatuak izan daitezke zerrendatik gehitu edo ezabatzean;
lerroko izenburu bat.
Bukatzean, klikatu "{{int:Watchlistedit-raw-submit}}" botoian.
Halaber [[Special:Watchlist/edit|aldatzaile estandarra]] erabil dezakezu.',
'watchlistedit-raw-titles'     => 'Izenburuak:',
'watchlistedit-raw-submit'     => 'Jarraitze-zerrenda eguneratu',
'watchlistedit-raw-done'       => 'Zure jarraipen zerrenda berritu da.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Titulu 1 gehitu da|$1 gehitu dira}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Izenburu 1|$1 izenburu}} ezabatu dira:',

# Watchlist editing tools
'watchlisttools-view' => 'Aldaketa garrantzitsuak ikusi',
'watchlisttools-edit' => 'Zerrenda ikusi eta aldatu',
'watchlisttools-raw'  => 'Zerrenda idatziz aldatu',

# Core parser functions
'unknown_extension_tag' => '"$1" luzapen etiketa ezezaguna',
'duplicate-defaultsort' => 'Adi: Berezko "$2" antolatzeak aurreko berezko "$1" antolatzea gainditzen du.',

# Special:Version
'version'                          => 'Bertsioa',
'version-extensions'               => 'Instalatutako luzapenak',
'version-specialpages'             => 'Aparteko orrialdeak',
'version-parserhooks'              => 'Parser estentsioak',
'version-variables'                => 'Aldagaiak',
'version-other'                    => 'Bestelakoak',
'version-mediahandlers'            => 'Media gordailuak',
'version-hooks'                    => 'Estentsioak',
'version-extension-functions'      => 'Luzapen funtzioak',
'version-parser-extensiontags'     => 'Parser luzapen etiketak',
'version-parser-function-hooks'    => 'Parser funtzio estentsioak',
'version-skin-extension-functions' => 'Itxura luzapen funtzioak',
'version-hook-name'                => 'Estentsioaren izena',
'version-hook-subscribedby'        => 'Hauen harpidetzarekin',
'version-version'                  => '(Bertsioa $1)',
'version-license'                  => 'Lizentzia',
'version-poweredby-credits'        => "Wiki hau '''[http://www.mediawiki.org/ MediaWiki]'''k sustatzen du (copyright © 2001-$1 $2).",
'version-poweredby-others'         => 'beste batzuk',
'version-software'                 => 'Instalatutako softwarea',
'version-software-product'         => 'Produktua',
'version-software-version'         => 'Bertsioa',

# Special:FilePath
'filepath'         => 'Fitxategi bidea',
'filepath-page'    => 'Fitxategia:',
'filepath-submit'  => 'Joan',
'filepath-summary' => 'Orri berezi honek fitxategiaren ibilbidea itzultzen du.
Irudiak bereizmen handienean daude, bestelako fitxategi motak beraiei esleitutako programarekin hasiko dira zuzenean.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Artxibo bikoiztuen bilaketa',
'fileduplicatesearch-summary'  => 'Bikoiztutako fitxategiak bilatu bere hash balioaren arabera.

Fitxategiaren izena sartu "{{ns:file}}:" aurrizkia gabe.',
'fileduplicatesearch-legend'   => 'Duplikatu bat bilatu',
'fileduplicatesearch-filename' => 'Fitxategi izena:',
'fileduplicatesearch-submit'   => 'Bilaketa',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Fitxategiaren tamaina: $3<br />MIME mota: $4',
'fileduplicatesearch-result-1' => '"$1" fitxategiak ez du duplikazio zehazki berdinik.',
'fileduplicatesearch-result-n' => '"$1" fitxategiak {{PLURAL:$2|kopia zehatz bakarra du|$2 kopia zehatz ditu}}.',

# Special:SpecialPages
'specialpages'                   => 'Orrialde bereziak',
'specialpages-note'              => '----
* Orrialde berezi arruntak.
* <strong class="mw-specialpagerestricted">Mugatutako orrialde bereziak.</strong>',
'specialpages-group-maintenance' => 'Mantentze-oharrak',
'specialpages-group-other'       => 'Beste orrialde berezi batzuk',
'specialpages-group-login'       => 'Sartu / Izena eman',
'specialpages-group-changes'     => 'Aldaketa berriak eta erregistroak',
'specialpages-group-media'       => 'Artxiboen orriak',
'specialpages-group-users'       => 'Erabiltzaileak eta eskumenak',
'specialpages-group-highuse'     => 'Erabilera handiko orrialdeak',
'specialpages-group-pages'       => 'Orrialdeen zerrendak',
'specialpages-group-pagetools'   => 'Orrialde tresnak',
'specialpages-group-wiki'        => 'Wiki datuak eta tresnak',
'specialpages-group-redirects'   => 'Berbideraketa-orri bereziak',
'specialpages-group-spam'        => 'Spam tresnak',

# Special:BlankPage
'blankpage'              => 'Orrialde txuria',
'intentionallyblankpage' => 'Orri hau nahita utzi da hutsik',

# External image whitelist
'external_image_whitelist' => ' #Lerro hau utzi dagoen bezala<pre>
#Jarri espresio zati erregularrak (bakarrik // artean doan zatia) azpian
#Hauek kanpo irudien URLekin lotuko dira
#Lotutako horiek irudi bezala agertuko dira, bestela lotura besterik ez da agertuko
# #-arekin hasten diren lerroak iruzkin bezala hartuko dira
# Hau case-insensitive da

#Jarri regex zatiak lerro honen gainetik. Lerro hau utzi dagoen bezala</pre>',

# Special:Tags
'tags'                    => 'Etiketa aldaketa zuzena',
'tag-filter'              => '[[Special:Tags|Etiketa]] iragazkia:',
'tag-filter-submit'       => 'Iragazkia',
'tags-title'              => 'Etiketak',
'tags-intro'              => 'Orri honek softwareak aldatzeko bezala marka ditzazkeen etiketak zerrendatzen ditu, eta berauen esanahia.',
'tags-tag'                => 'Etiketaren izena',
'tags-display-header'     => 'Aldaketa zerrenden itxura',
'tags-description-header' => 'Esanahiaren deskribapen osoa',
'tags-hitcount-header'    => 'Etiketatutako aldaketak',
'tags-edit'               => 'aldatu',
'tags-hitcount'           => '$1 {{PLURAL:$1|aldaketa|aldaketa}}',

# Special:ComparePages
'comparepages'     => 'Orrialdeak alderatu',
'compare-selector' => 'Orrialde-berrikuspenak alderatu',
'compare-page1'    => '1. orrialdea',
'compare-page2'    => '2. orrialdea',
'compare-rev1'     => '1. berrikuspena',
'compare-rev2'     => '2. berrikuspena',
'compare-submit'   => 'Alderatu',

# Database error messages
'dberr-header'      => 'Wiki honek arazo bat du',
'dberr-problems'    => 'Barkatu! Webgune honek zailtasun teknikoak jasaten ari da.',
'dberr-again'       => 'Saiatu pare bat minutu itxaroten edo kargatu ezazu orrialdea berriro.',
'dberr-info'        => '($1: Ezin da datu-base zerbitzariarekin konektatu)',
'dberr-usegoogle'   => 'Bitartean Google bidez bilatzen saiatu zintezke.',
'dberr-outofdate'   => 'Eduki hauek aurkibideak eguneratu gabe egon daitezke.',
'dberr-cachederror' => 'Ondorengoa eskatutako orriaren katxedun kopia da, eta eguneratu gabe egon daiteke.',

# HTML forms
'htmlform-invalid-input'       => 'Zure sarrera batzuekin arazoak daude',
'htmlform-select-badoption'    => 'Zuk zehaztutako balioa ez da baliozko aukera.',
'htmlform-int-invalid'         => 'Zuk zehaztutako balioa ez da zenbaki osoa.',
'htmlform-float-invalid'       => 'Zuk zehaztutako balioa ez da zenbakia.',
'htmlform-int-toolow'          => 'Zuk zehaztutako balioa $1 minimoaren azpitik dago',
'htmlform-int-toohigh'         => 'Zuk zehaztutako balioa $1 maximoaren gainetik dago',
'htmlform-required'            => 'Balio hori beharrezkoa da',
'htmlform-submit'              => 'Bidali',
'htmlform-reset'               => 'Aldaketak desegin',
'htmlform-selectorother-other' => 'Beste bat',

);
