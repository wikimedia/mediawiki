<?php
/** Slovenian (Slovenščina)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Smihael
 * @author XJamRastafire
 * @author Yerpo
 * @author romanm
 * @author sl.wikipedia.org administrators
 */

$namespaceNames = array(
	NS_MEDIA            => 'Datoteka',
	NS_SPECIAL          => 'Posebno',
	NS_TALK             => 'Pogovor',
	NS_USER             => 'Uporabnik',
	NS_USER_TALK        => 'Uporabniški_pogovor',
	NS_PROJECT_TALK     => 'Pogovor_{{grammar:mestnik|$1}}',
	NS_FILE             => 'Slika',
	NS_FILE_TALK        => 'Pogovor_o_sliki',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Pogovor_o_MediaWiki',
	NS_TEMPLATE         => 'Predloga',
	NS_TEMPLATE_TALK    => 'Pogovor_o_predlogi',
	NS_HELP             => 'Pomoč',
	NS_HELP_TALK        => 'Pogovor_o_pomoči',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Pogovor_o_kategoriji',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'DvojnePreusmeritve' ),
	'BrokenRedirects'           => array( 'PokvarjenePreusmeritve' ),
	'Disambiguations'           => array( 'Razločitve' ),
	'Userlogin'                 => array( 'Prijava' ),
	'Userlogout'                => array( 'Odjava' ),
	'CreateAccount'             => array( 'Registracija' ),
	'Preferences'               => array( 'Nastavitve' ),
	'Watchlist'                 => array( 'SpisekNadzorov' ),
	'Recentchanges'             => array( 'ZadnjeSpremembe' ),
	'Upload'                    => array( 'Nalaganje' ),
	'Listfiles'                 => array( 'SeznamDatotek', 'SeznamSlik' ),
	'Newimages'                 => array( 'NoveDatoteke', 'NoveSlike' ),
	'Listusers'                 => array( 'SeznamUporabnikov' ),
	'Listgrouprights'           => array( 'SeznamPravicSkupin' ),
	'Statistics'                => array( 'Statistika' ),
	'Randompage'                => array( 'Nakljucno', 'NakljucnaStran' ),
	'Lonelypages'               => array( 'OsiroteleStrani' ),
	'Uncategorizedpages'        => array( 'NekategoriziraneStrani' ),
	'Uncategorizedcategories'   => array( 'NekategoriziraneKategorije' ),
	'Uncategorizedimages'       => array( 'NekategoriziraneDatoteke', 'NekategoriziraneSlike' ),
	'Uncategorizedtemplates'    => array( 'NekategoriziranePredloge' ),
	'Unusedcategories'          => array( 'NeuporabljeneKategorije' ),
	'Unusedimages'              => array( 'NeuporabljeneDatoteke', 'NeuporabljeneSlike' ),
	'Wantedpages'               => array( 'ZeleneStrani' ),
	'Wantedcategories'          => array( 'ZeleneKategorije' ),
	'Wantedfiles'               => array( 'ZeleneDatoteke' ),
	'Wantedtemplates'           => array( 'ZelenePredloge' ),
	'Mostlinked'                => array( 'NajboljPovezaneStrani' ),
	'Mostcategories'            => array( 'NajvecKategorij' ),
	'Mostrevisions'             => array( 'NajvecRedakcij' ),
	'Fewestrevisions'           => array( 'NajmanjRedakcij' ),
	'Shortpages'                => array( 'KratkeStrani' ),
	'Longpages'                 => array( 'DolgeStrani' ),
	'Newpages'                  => array( 'NoveStrani' ),
	'Ancientpages'              => array( 'StarodavneStrani' ),
	'Protectedpages'            => array( 'ZasciteneStrani' ),
	'Protectedtitles'           => array( 'ZasciteniNaslovi' ),
	'Allpages'                  => array( 'VseStrani' ),
	'Specialpages'              => array( 'PosebneStrani' ),
	'Contributions'             => array( 'Prispevki' ),
	'Export'                    => array( 'Izvozi' ),
	'Version'                   => array( 'Verzija' ),
	'Mypage'                    => array( 'MojaStran' ),
	'Mytalk'                    => array( 'MojPogovor' ),
	'Search'                    => array( 'Iskanje' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#PREUSMERITEV', '#REDIRECT' ),
	'notoc'                 => array( '0', '__BREZKAZALAVSEBINE__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__BREZGALERIJE__', '__NOGALLERY__' ),
	'toc'                   => array( '0', '__POGLAVJE__', '__TOC__' ),
	'img_right'             => array( '1', 'desno', 'right' ),
	'img_left'              => array( '1', 'levo', 'left' ),
	'img_none'              => array( '1', 'brez', 'none' ),
	'img_width'             => array( '1', '$1pik', '$1px' ),
	'img_center'            => array( '1', 'sredina', 'sredinsko', 'center', 'centre' ),
	'img_framed'            => array( '1', 'okvir', 'okvirjeno', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'brezokvirja', 'frameless' ),
	'img_page'              => array( '1', 'stran=$1m stran $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'zgorajdesno', 'zgorajdesno=$1', 'zgorajdesno $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'obroba', 'border' ),
	'img_sub'               => array( '1', 'pod', 'podpisano', 'sub' ),
	'img_super'             => array( '1', 'nad', 'nadpisano', 'super', 'sup' ),
	'img_top'               => array( '1', 'vrh', 'top' ),
	'img_text_top'          => array( '1', 'vrh-besedila', 'text-top' ),
	'img_bottom'            => array( '1', 'dno', 'bottom' ),
	'img_text_bottom'       => array( '1', 'dno-besedila', 'text-bottom' ),
	'sitename'              => array( '1', 'IMESTRANI', 'SITENAME' ),
	'server'                => array( '0', 'STREZNIK', 'SERVER' ),
	'grammar'               => array( '0', 'SKLON:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'SPOL:', 'GENDER:' ),
	'plural'                => array( '0', 'MNOZINA:', 'PLURAL:' ),
	'language'              => array( '0', '#JEZIK:', '#LANGUAGE:' ),
	'tag'                   => array( '0', 'oznaka', 'tag' ),
	'hiddencat'             => array( '1', '__SKRITAKATEGORIJA__', '__HIDDENCAT__' ),
	'index'                 => array( '1', '__KAZALO__', '__INDEX__' ),
	'noindex'               => array( '1', '__BREZKAZALA__', '__NOINDEX__' ),
	'staticredirect'        => array( '1', '__STATICNAPREUSMERITEV__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'STOPNJAZASCITE', 'PROTECTIONLEVEL' ),
);

$datePreferences = array(
	'default',
	'dmy short',
	'dmy full',
	'ISO 8601',
);

/**
 * The date format to use for generated dates in the user interface.
 * This may be one of the above date preferences, or the special value
 * "dmy or mdy", which uses mdy if $wgAmericanDates is true, and dmy
 * if $wgAmericanDates is false.
 */
$defaultDateFormat = 'dmy full';

$dateFormats = array(
	'dmy short time' => 'H:i',
	'dmy short date' => 'j. F Y',
	'dmy short both' => 'H:i, j. M Y',

	'dmy full time' => 'H:i',
	'dmy full date' => 'j. F Y',
	'dmy full both' => 'H:i, j. F Y',
);

$fallback8bitEncoding = "iso-8859-2";
$separatorTransformTable = array(',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Podčrtavanje povezav:',
'tog-highlightbroken'         => 'Oblikuj pretrgane povezave <a href="" class="new">kot</a> (druga možnost: kot<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Poravnavaj odstavke',
'tog-hideminor'               => 'Skrij manjše popravke v zadnjih spremembah',
'tog-hidepatrolled'           => 'Skrij pregledana urejanja v zadnjih spremembah',
'tog-newpageshidepatrolled'   => 'Skrij pregledane strani iz seznama novih strani',
'tog-extendwatchlist'         => 'Razširi spisek nadzorov, da bo prikazoval vse spremembe, ne le najnovejše',
'tog-usenewrc'                => 'Izboljšane zadnje spremembe (zahtevan JavaScript)',
'tog-numberheadings'          => 'Samodejno številči poglavja',
'tog-showtoolbar'             => 'Prikaži urejevalno orodno vrstico',
'tog-editondblclick'          => 'Omogoči urejanje strani z dvojnim klikom (JavaScript)',
'tog-editsection'             => 'Omogoči urejanje delov prek povezav [spremeni]',
'tog-editsectiononrightclick' => 'Omogoči urejanje delov z desnim klikanjem naslovov delov (JavaScript)',
'tog-showtoc'                 => 'Prikaži vsebino (strani z več kot tremi naslovi)',
'tog-rememberpassword'        => 'Geslo si zapomni skozi vse seje',
'tog-editwidth'               => 'Razširi urejevalno polje čez vso širino',
'tog-watchcreations'          => 'Vse ustvarjene strani dodaj na spisek nadzorov',
'tog-watchdefault'            => 'Dodaj na spisek nadzorov vse članke, ki sem jih ustvaril/-a ali spremenil/-a',
'tog-watchmoves'              => 'Dodaj strani, ki jih premaknem, na moj spisek nadzorov',
'tog-watchdeletion'           => 'Dodaj strani, ki jih izbrišem, na moj spisek nadzorov',
'tog-minordefault'            => 'Vsa urejanja označi kot manjša',
'tog-previewontop'            => 'Prikaži predogled pred urejevalnim poljem in ne za njim',
'tog-previewonfirst'          => 'Ob začetku urejanja prikaži predogled',
'tog-nocache'                 => 'Onemogoči predpomnenje strani',
'tog-enotifwatchlistpages'    => 'Ob spremembah strani mi pošlji e-pošto',
'tog-enotifusertalkpages'     => 'Pošlji e-pošto ob spremembah moje pogovorne strani',
'tog-enotifminoredits'        => 'Pošlji e-pošto tudi za manjše spremembe strani',
'tog-enotifrevealaddr'        => 'V sporočilih z obvestili o spremembah razkrij moj e-poštni naslov',
'tog-shownumberswatching'     => 'Prikaži število uporabnikov, ki spremljajo temo',
'tog-oldsig'                  => 'Predogled obstoječega podpisa:',
'tog-fancysig'                => 'Surovi podpisi v obliki Wikibesedila (brez samodejne povezave)',
'tog-externaleditor'          => 'Po privzetem uporabljaj zunanji urejevalnik',
'tog-externaldiff'            => 'Po privzetem uporabljaj zunanje primerjanje',
'tog-showjumplinks'           => 'Prikaži pomožni povezavi »Skoči na«',
'tog-uselivepreview'          => 'Uporabi hitri predogled (JavaScript) (preizkusno)',
'tog-forceeditsummary'        => 'Ob vpisu praznega povzetka urejanja me opozori',
'tog-watchlisthideown'        => 'Na spisku nadzorov skrij moja urejanja',
'tog-watchlisthidebots'       => 'Na spisku nadzorov skrij urejanja botov',
'tog-watchlisthideminor'      => 'Skrij manjša urejanja na spisku nadzorov',
'tog-watchlisthideliu'        => 'Skrij urejanja prijavljenih uporabnikov v spisku nadzorov',
'tog-watchlisthideanons'      => 'Skrij urejanja anonimnih uporabnikov v spisku nadzorov',
'tog-watchlisthidepatrolled'  => 'Skrij pregledana urejanja s spiska nadzorov',
'tog-ccmeonemails'            => 'Pošlji mi kopijo e-sporočil, ki jih pošljem drugim uporabnikom',
'tog-diffonly'                => 'Ne prikaži vsebine strani pod primerjavo',
'tog-showhiddencats'          => 'Prikaži skrite kategorije',

'underline-always'  => 'Vedno',
'underline-never'   => 'Nikoli',
'underline-default' => 'Privzeto (brskalnik)',

# Font style option in Special:Preferences
'editfont-style'     => 'Uredi področni slog pisave:',
'editfont-default'   => 'Privzeti brskalnik',
'editfont-monospace' => 'Pisava Monospace',
'editfont-sansserif' => 'Pisava Sans-serif',
'editfont-serif'     => 'Pisava Serif',

# Dates
'sunday'        => 'nedelja',
'monday'        => 'ponedeljek',
'tuesday'       => 'torek',
'wednesday'     => 'sreda',
'thursday'      => 'četrtek',
'friday'        => 'petek',
'saturday'      => 'sobota',
'sun'           => 'ned',
'mon'           => 'pon',
'tue'           => 'tor',
'wed'           => 'sre',
'thu'           => 'čet',
'fri'           => 'pet',
'sat'           => 'sob',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'marec',
'april'         => 'april',
'may_long'      => 'maj',
'june'          => 'junij',
'july'          => 'julij',
'august'        => 'avgust',
'september'     => 'september',
'october'       => 'oktober',
'november'      => 'november',
'december'      => 'december',
'january-gen'   => 'januarja',
'february-gen'  => 'februarja',
'march-gen'     => 'marca',
'april-gen'     => 'aprila',
'may-gen'       => 'maja',
'june-gen'      => 'junija',
'july-gen'      => 'julija',
'august-gen'    => 'avgusta',
'september-gen' => 'septembra',
'october-gen'   => 'oktobra',
'november-gen'  => 'novembra',
'december-gen'  => 'decembra',
'jan'           => 'jan.',
'feb'           => 'feb.',
'mar'           => 'mar.',
'apr'           => 'apr.',
'may'           => 'maj',
'jun'           => 'jun.',
'jul'           => 'jul.',
'aug'           => 'avg.',
'sep'           => 'sep.',
'oct'           => 'okt.',
'nov'           => 'nov.',
'dec'           => 'dec.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorija|Kategoriji|Kategorije|Kategorije|Kategorije}}',
'category_header'                => 'Strani v kategoriji »$1«',
'subcategories'                  => 'Podkategorije',
'category-media-header'          => 'Predstavnostno gradivo v kategoriji »$1«',
'category-empty'                 => "''Ta kategorija trenutno ne vsebuje člankov ali drugega gradiva.''",
'hidden-categories'              => '{{PLURAL:$1|Skrita kategorija|Skrite kategorije}}',
'hidden-category-category'       => 'Skrite kategorije',
'category-subcat-count'          => 'Ta del kategorije ima {{PLURAL:$1|$1 sledečo podkategorijo|$1 sledeči podkategoriji|$1 sledeče podkategorije|$1 sledečih podkategorij|$1 sledečih podkategorij}}{{PLURAL:$2||, od skupno $2}}.',
'category-subcat-count-limited'  => 'Ta kategorija ima {{PLURAL:$1|$1 sledečo podkategorijo|$1 sledeči podkategoriji|$1 sledeče podkategorije|$1 sledečih podkategorij|$1 sledečih podkategorij}}.',
'category-article-count'         => 'Ta del kategorije vsebuje {{PLURAL:$1|$1 sledečo stran|$1 sledeči strani|$1 sledeče strani|$1 sledečih strani|$1 sledečih strani}}{{PLURAL:$2||, od skupno $2}}.',
'category-article-count-limited' => 'V tej kategoriji {{PLURAL:$1|je $1 sledeča stran|sta $1 sledeči strani|so $1 sledeče strani|je $1 sledečih strani|je $1 sledečih strani}}.',
'category-file-count'            => 'Ta del kategorije vsebuje {{PLURAL:$1|$1 sledečo datoteko|$1 sledeči datoteki|$1 sledeče datoteke|$1 sledečih datotek|$1 sledečih datotek}}{{PLURAL:$2||, od skupno $2}}.',
'category-file-count-limited'    => 'V tej kategoriji {{PLURAL:$1|je $1 sledeča datoteka|sta $1 sledeči datoteki|so $1 sledeče datoteke|je $1 sledečih datotek|je $1 sledečih datotek}}.',
'listingcontinuesabbrev'         => 'nadalj.',

'mainpagetext'      => "<big>'''MediaWiki programje ste uspešno naložili!'''</big>",
'mainpagedocfooter' => 'Za uporabo in pomoč pri nastavitvi, prosimo, preglejte [http://meta.wikimedia.org/wiki/MediaWiki_localisation dokumentacijo za prilagajanje vmesnika]
in [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Uporabniški priročnik].',

'about'         => 'O projektu',
'article'       => 'Članek',
'newwindow'     => '(odpre se novo okno)',
'cancel'        => 'Prekliči',
'moredotdotdot' => 'Več ...',
'mypage'        => 'Moja stran',
'mytalk'        => 'Pogovor',
'anontalk'      => 'Pogovorna stran IP',
'navigation'    => 'Navigacija',
'and'           => '&#32;in',

# Cologne Blue skin
'qbfind'         => 'Poišči',
'qbbrowse'       => 'Prebrskaj',
'qbedit'         => 'Uredi',
'qbpageoptions'  => 'Možnosti strani',
'qbpageinfo'     => 'Podatki o strani',
'qbmyoptions'    => 'Moje možnosti',
'qbspecialpages' => 'Posebne strani',
'faq'            => 'Najpogostejša vprašanja',
'faqpage'        => 'Project:Najpogostejša vprašanja',

# Vector skin
'vector-action-addsection'   => 'Dodaj temo',
'vector-action-delete'       => 'Izbriši',
'vector-action-move'         => 'Premakni',
'vector-action-protect'      => 'Zaščiti',
'vector-action-undelete'     => 'Vrni',
'vector-action-unprotect'    => 'Odstrani zaščito',
'vector-namespace-category'  => 'Kategorija',
'vector-namespace-help'      => 'Stran s pomočjo',
'vector-namespace-image'     => 'Datoteka',
'vector-namespace-main'      => 'Stran',
'vector-namespace-media'     => 'Multimedijska stran',
'vector-namespace-mediawiki' => 'Sporočilo',
'vector-namespace-project'   => 'Projektna stran',
'vector-namespace-special'   => 'Posebna stran',
'vector-namespace-talk'      => 'Pogovor',
'vector-namespace-template'  => 'Predloga',
'vector-namespace-user'      => 'Uporabniška stran',
'vector-view-create'         => 'Ustvari',
'vector-view-edit'           => 'Uredi',
'vector-view-history'        => 'Pregled zgodovine',
'vector-view-view'           => 'Preberi',
'vector-view-viewsource'     => 'Izvorno besedilo',
'actions'                    => 'Dejanja',
'namespaces'                 => 'Imenski prostori',
'variants'                   => 'Različice',

# Metadata in edit box
'metadata_help' => 'Metapodatki:',

'errorpagetitle'    => 'Napaka',
'returnto'          => 'Vrnitev na: $1.',
'tagline'           => 'Iz {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'help'              => 'Pomoč',
'search'            => 'Iskanje',
'searchbutton'      => 'Iskanje',
'go'                => 'Pojdi na',
'searcharticle'     => 'Pojdi na',
'history'           => 'Zgodovina strani',
'history_short'     => 'Zgodovina strani',
'updatedmarker'     => 'Posodobljeno od mojega zadnjega obiska',
'info_short'        => 'Sporočilo',
'printableversion'  => 'Različica za tisk',
'permalink'         => 'Trajna povezava',
'print'             => 'Tisk',
'edit'              => 'Uredite stran',
'create'            => 'ustvari',
'editthispage'      => 'Uredi stran',
'create-this-page'  => 'Ustvari to stran',
'delete'            => 'Briši',
'deletethispage'    => 'Briši stran',
'undelete_short'    => 'Vrni $1 {{PLURAL:$1|izbrisano urejanje|izbrisani urejanji|izbrisana urejanja|izbrisanih urejanj|izbrisanih urejanj}}',
'protect'           => 'Zaščiti',
'protect_change'    => 'spremeni zaščito',
'protectthispage'   => 'Zaščiti stran',
'unprotect'         => 'Odstrani zaščito',
'unprotectthispage' => 'Odstrani zaščito strani',
'newpage'           => 'Nova stran',
'talkpage'          => 'Pogovorite se o strani',
'talkpagelinktext'  => 'Pogovor',
'specialpage'       => 'Posebna stran',
'personaltools'     => 'Osebna orodja',
'postcomment'       => 'Nov razdelek',
'articlepage'       => 'Prikaže članek',
'talk'              => 'Pogovor',
'views'             => 'Pogled',
'toolbox'           => 'Pripomočki',
'userpage'          => 'Prikaži uporabnikovo stran',
'projectpage'       => 'Prikaži projektno stran',
'imagepage'         => 'Pokaži stran z datoteko',
'mediawikipage'     => 'Poglej stran s sporočilom',
'templatepage'      => 'Poglej stran s predlogo',
'viewhelppage'      => 'Poglej stran s pomočjo',
'categorypage'      => 'Prikaži stran kategorije',
'viewtalkpage'      => '< Pogovor',
'otherlanguages'    => 'V drugih jezikih',
'redirectedfrom'    => '(Preusmerjeno z $1)',
'redirectpagesub'   => 'Preusmeritvena stran',
'lastmodifiedat'    => 'Čas zadnje spremembe: $2, $1.',
'viewcount'         => 'Stran je bila naložena {{PLURAL:$1|enkrat|dvakrat|$1-krat|$1-krat|$1-krat}}.',
'protectedpage'     => 'Zaščitena stran',
'jumpto'            => 'Skoči na:',
'jumptonavigation'  => 'navigacija',
'jumptosearch'      => 'iskanje',
'view-pool-error'   => 'Žal so strežniki trenutno preobremenjeni.
Preveč uporabnikov skuša obiskati to stran.
Prosimo za potrpežljivost, obiščite nas spet kmalu.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{UCFIRST:{{GRAMMAR:mestnik|{{SITENAME}}}}}}',
'aboutpage'            => 'Project:{{UCFIRST:{{GRAMMAR:mestnik|{{SITENAME}}}}}}',
'copyright'            => 'Besedilo je na razpolago pod pogoji $1.',
'copyrightpage'        => '{{ns:project}}:Avtorske pravice {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'currentevents'        => 'Trenutni dogodki',
'currentevents-url'    => 'Project:Trenutni dogodki',
'disclaimers'          => 'Zanikanja odgovornosti',
'disclaimerpage'       => 'Project:Splošno_zanikanje_odgovornosti',
'edithelp'             => 'Pomoč pri urejanju',
'edithelppage'         => 'Help:Urejanje slovenskih strani',
'helppage'             => 'Help:Vsebina',
'mainpage'             => 'Glavna stran',
'mainpage-description' => 'Glavna stran',
'policy-url'           => 'Project:Pravila',
'portal'               => 'Portal občestva',
'portal-url'           => 'Project:Portal občestva',
'privacy'              => 'Politika zasebnosti',
'privacypage'          => 'Project:Politika_zasebnosti',

'badaccess'        => 'Napaka pri dovoljenju',
'badaccess-group0' => 'Izvedba zahtevanega dejanja vam ni dovoljena.',
'badaccess-groups' => 'Izvajanje želenega dejanja je omejeno na uporabnike {{PLURAL:$2|na naslednjo skupino|eno izmed naslednjih skupin}}: $1.',

'versionrequired'     => 'Potrebna je različica MediaWiki $1',
'versionrequiredtext' => 'Za uporabo strani je potrebna različica MediaWiki $1. Glejte [[Special:Version]].',

'ok'                      => 'V redu',
'retrievedfrom'           => 'Vzpostavljeno iz »$1«',
'youhavenewmessages'      => 'Imate $1 ($2)',
'newmessageslink'         => 'novo sporočilo',
'newmessagesdifflink'     => 'zadnja sprememba',
'youhavenewmessagesmulti' => 'Na $1 imate novo sporočilo',
'editsection'             => 'spremeni',
'editold'                 => 'spremeni',
'viewsourceold'           => 'izvorno besedilo',
'editlink'                => 'uredi',
'viewsourcelink'          => 'izvorna koda',
'editsectionhint'         => 'Spremeni razdelek: $1',
'toc'                     => 'Vsebina',
'showtoc'                 => 'prikaži',
'hidetoc'                 => 'skrij',
'thisisdeleted'           => 'Prikažem ali vrnem $1?',
'viewdeleted'             => 'Prikažem $1?',
'restorelink'             => '$1 {{PLURAL:$1|izbrisana redakcija|izbrisani redakciji|izbrisane redakcije|izbrisanih redakcij|izbrisanih redakcij}}',
'feedlinks'               => 'Podajanje:',
'feed-invalid'            => 'Neveljavna vrsta naročniškega dovoda.',
'feed-unavailable'        => 'Živi zaznamki niso na voljo',
'site-rss-feed'           => '$1 RSS vir',
'site-atom-feed'          => '$1 Atom vir',
'page-rss-feed'           => '»$1« RSS vir',
'page-atom-feed'          => '»$1« Atom vir',
'red-link-title'          => '$1 (članek še ni napisan)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Članek',
'nstab-user'      => 'Uporabniška stran',
'nstab-media'     => 'Predstavnostna stran',
'nstab-special'   => 'posebna stran',
'nstab-project'   => 'Projektna stran',
'nstab-image'     => 'Datoteka',
'nstab-mediawiki' => 'Sporočilo',
'nstab-template'  => 'Predloga',
'nstab-help'      => 'Pomoč',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Tako dejanje ne obstaja',
'nosuchactiontext'  => 'Dejanje, ki ga označuje spletni naslov je napačno.
Morda je sintaksa spletnega naslova napačna, ali pa ste sledili napačni povezavi.
Morda ste odkrili hrošča v programski opremi {{GRAMMAR:genitive|{{SITENAME}}}}.',
'nosuchspecialpage' => 'Posebna stran, ki ste jo zahtevali, ne obstaja',
'nospecialpagetext' => 'Posebne strani, ki ste jo zahtevali, programje {{GRAMMAR:rodilnik|{{SITENAME}}}} ne prepozna ali pa nimate dostopa do nje. Seznam vseh prepoznanih posebnih strani je na razpolago na strani [[Special:SpecialPages]].',

# General errors
'error'                => 'Napaka',
'databaseerror'        => 'Napaka zbirke podatkov',
'dberrortext'          => 'Prišlo je do napake podatkovne zbirke.
Vzrok bi lahko bil nesprejemljiv iskalni niz ali programski hrošč.
Zadnje poskušano iskanje:
<blockquote><tt>$1</tt></blockquote>
znotraj funkcije »<tt>$2</tt>«.
Podatkovna zbirka je vrnila napako »<tt>$3: $4</tt>«.',
'dberrortextcl'        => 'Pri iskanju v podatkoovni zbirki je prišlo do skladenjske napake.
Zadnje iskanje v zbirki podatkov:
»$1«
iz funkcije »$2«.
Podatkovna zbirka je vrnila napako »$3: $4«.',
'laggedslavemode'      => 'Opozorilo: stran morda ne vsebuje najnovejših posodobitev',
'readonly'             => 'Zbirka podatkov je zaklenjena',
'enterlockreason'      => 'Vnesite razlog za zaklenitev in oceno, kdaj bo urejanje spet mogoče',
'readonlytext'         => "Zbirka podatkov je za urejanja in druge spremembe začasno zaklenjena. To navadno pomeni, da nadgrajujejo programje strežnikov ali pa rutinsko vzdrževanje zbirke.

Sistemski skrbnik, ki jo je zaklenil, je podal naslednjo razlago: ''\"\$1\"''",
'missing-article'      => 'Podatkovna baza ni našla besedila strani, ki ga bi morala najti, z imenom »$1« $2.

Ta je ponavadi posledica zastarelih sprememb ali pa je bila stran izbrisana.

Če menite, da vzrok ni v tem, ste morda odkrili hrošč v programju.
Prosimo, da o tem obvestite [[Special:ListUsers/sysop|administratorja]] (ne pozabite navesti točen URL).',
'missingarticle-rev'   => '(redakcija št.: $1)',
'missingarticle-diff'  => '(Primerjanje: $1, $2)',
'readonly_lag'         => 'Podatkovna zbirka se je samodejno zaklenila, dokler se podrejeni strežniki ne uskladijo z glavnim.',
'internalerror'        => 'Notranja napaka',
'internalerror_info'   => 'Notranja napaka: $1',
'fileappenderror'      => 'Ne morem pripeti »$1« v »$2«.',
'filecopyerror'        => 'Datoteke »$1« ni mogoče prepisati v »$2«.',
'filerenameerror'      => 'Datoteke »$1« ni mogoče preimenovati v »$2«.',
'filedeleteerror'      => 'Datoteke »$1« ni mogoče izbrisati.',
'directorycreateerror' => 'Ne morem ustvariti direktorija »$1«.',
'filenotfound'         => 'Datoteke »$1« ne najdem.',
'fileexistserror'      => 'Ne morem pisati v datoteko »$1«: datoteka obstaja',
'unexpected'           => 'Nepričakovana vrednost: "$1"="$2".',
'formerror'            => 'Napaka: obrazca ni mogoče predložiti',
'badarticleerror'      => 'Na tej strani dejanja ne morem izvesti. Morda je bila stran med predložitvijo vaše zahteve že izbrisana.',
'cannotdelete'         => 'Navedene strani ali datoteke ni mogoče izbrisati. Morda jo je izbrisal že kdo drug.',
'badtitle'             => 'Nepravilen naslov',
'badtitletext'         => 'Navedeni naslov strani je neveljaven, prazen, napačno povezan k drugim jezikom oziroma wikiprojektom ali pa vsebuje nepodprte znake.',
'perfcached'           => 'Navedeni podatki morda niso popolnoma posodobljeni.',
'perfcachedts'         => 'Prikazani podatki so shranjeni v predpomnilniku. Čas zadnje osvežitve: $1.',
'querypage-no-updates' => 'Posodobitve za to stran so trenutno onemogočene. Tukajšnji podatki se v kratkem ne bodo osvežili.',
'wrong_wfQuery_params' => 'Nepravilni parametri za wfQuery()<br />
Funkcija: $1<br />
Iskanje: $2',
'viewsource'           => 'Izvorno besedilo',
'viewsourcefor'        => 'za $1',
'actionthrottled'      => 'Dejanje zaustavljeno',
'actionthrottledtext'  => 'Kot ukrep proti smetju, je število izvajanj tega dejanja v časovnem obdobju omejeno, in vi ste ta limit presegli.
Prosimo, poskusite znova čez nekaj minut.',
'protectedpagetext'    => 'Ta stran je bila zaklenjena za preprečitev urejanja.',
'viewsourcetext'       => 'Lahko si ogledujete in kopirate vsebino te stran:',
'protectedinterface'   => 'Prikazana stran vsebuje besedilo ali drug element uporabniškega vmesnika programja. Zaradi preprečevanja zlorabe je zaščitena.',
'editinginterface'     => "'''Opozorilo:''' Urejate stran, ki vsebuje besedilo ali drug element uporabniškega vmesnika programja.
Spremembe te strani bodo vplivale na podobo uporabniškega vmesnika.
Za prevajanje vmesnika se prijavite na [http://translatewiki.net/wiki/Main_Page?setlang=sl&useskin=monobook translatewiki.net].",
'sqlhidden'            => '(SQL-poizvedovanje je skrito)',
'cascadeprotected'     => 'Ta stran je bila zaščitena pred urejanji, ker je vključena na {{PLURAL:$1|page|sledeče strani}}, ki so bile zaščitene z vključeno kaskadno možnostjo:
$2',
'namespaceprotected'   => "Nimate dovoljenja urejati strani v imenskem prostoru '''$1'''.",
'customcssjsprotected' => 'Nimate pravice urejati te strani, ker vsebuje osebne nastavitve drugega uporabnika.',
'ns-specialprotected'  => 'Strani v imenskem prostoru {{ns:special}} ni možno urejati.',
'titleprotected'       => "Uporabnik [[User:$1|$1]] je preprečil ustvarjanje strani s takim naslovom.
Podani razlog je bil »''$2''«.",

# Virus scanner
'virus-badscanner'     => "Slaba konfiguracija: neznani virus skener: ''$1''",
'virus-scanfailed'     => 'pregled ni uspel (koda $1)',
'virus-unknownscanner' => 'neznan antivirusni program:',

# Login and logout pages
'logouttext'                 => 'Odjavili ste se. {{GRAMMAR:tožilnik|{{SITENAME}}}} lahko zdaj uporabljate neprijavljeni ali pa se ponovno prijavite. Morda bodo nekatere strani še naprej prikazane, kot da ste prijavljeni. To lahko popravite z izpraznitvijo predpomnilnika.',
'welcomecreation'            => '== Dobrodošli, $1! ==
Ustvarili ste račun.
Če želite, si lahko prilagodite [[Special:Preferences|nastavitve za {{GRAMMAR:tožilnik|{{SITENAME}}}}]].',
'yourname'                   => 'Uporabniško ime',
'yourpassword'               => 'Geslo',
'yourpasswordagain'          => 'Ponovno vpišite geslo',
'remembermypassword'         => 'Zapomni si me (samodejna prijava)',
'yourdomainname'             => 'Domena',
'externaldberror'            => 'Pri potrjevanju istovetnosti je prišlo do notranje napake ali pa za osveževanje zunanjega računa nimate dovoljenja.',
'login'                      => 'Prijava',
'nav-login-createaccount'    => 'Prijavite se / registrirajte se',
'loginprompt'                => '<!--Za prijavo v {{GRAMMAR:tožilnik|{{SITENAME}}}} omogočite piškotke.-->',
'userlogin'                  => 'Prijavite se / registrirajte se',
'logout'                     => 'Odjava',
'userlogout'                 => 'Odjava',
'notloggedin'                => 'Niste prijavljeni',
'nologin'                    => "Še nimate uporabniškega računa? '''$1'''!",
'nologinlink'                => 'Registrirajte se',
'createaccount'              => 'Ustvari račun',
'gotaccount'                 => "Račun že imate? '''$1'''.",
'gotaccountlink'             => 'Prijavite se',
'createaccountmail'          => 'Po e-pošti',
'badretype'                  => 'Gesli, ki ste ju vnesli, se ne ujemata.',
'userexists'                 => 'Uporabniško ime, ki ste ga vnesli, je že zasedeno.
Prosimo, izberite si drugo.',
'loginerror'                 => 'Napaka ob prijavi',
'nocookiesnew'               => 'Uporabniški račun je ustvarjen, vendar niste prijavljeni. {{SITENAME}} za prijavo uporabnikov uporablja piškotke, ki pa so pri vas onemogočeni. Prosimo, omogočite jih, nato pa se s svojim uporabniškim imenom in geslom ponovno poskusite prijaviti.',
'nocookieslogin'             => '{{SITENAME}} za prijavljanje uporabnikov uporablja piškotke. Ker jih imate onemogočene, vas prosimo, da jih omogočite in se ponovno prijavite.',
'noname'                     => 'Niste vnesli veljavnega uporabniškega imena.',
'loginsuccesstitle'          => 'Uspešno ste se prijavili',
'loginsuccess'               => 'Sedaj ste vpisani v {{GRAMMAR:tožilnik|{{SITENAME}}}} kot "$1".',
'nosuchuser'                 => 'Uporabnik z imenom »$1« ne obstaja.
Preverite črkovanje ali pa si z [[Special:UserLogin/signup|ustvarite nov uporabniški račun]].',
'nosuchusershort'            => 'Uporabnik z imenom »<nowiki>$1</nowiki>« ne obstaja. Preverite črkovanje.',
'nouserspecified'            => 'Prosimo, vpišite uporabniško ime.',
'wrongpassword'              => 'Vnesli ste napačno geslo. Prosimo, poskusite znova.',
'wrongpasswordempty'         => 'Vpisali ste prazno geslo. Prosimo, poskusite znova.',
'passwordtooshort'           => 'Geslo mora imeti najmanj $1 {{PLURAL:$1|znak|znaka|znake|znakov|znakov}}.',
'password-name-match'        => 'Vaše geslo se mora razlikovati od vašega uporabniškega imena.',
'mailmypassword'             => 'Pošlji mi novo geslo',
'passwordremindertitle'      => 'Geselski opomnik iz {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'passwordremindertext'       => "Nekdo (verjetno vi, z IP-naslova $1)
je zahteval, da vam pošljemo novo prijavno geslo za {{GRAMMAR:tožilnik|{{SITENAME}}}} ($4).
Geslo uporabnika ''$2'' je odslej ''$3''.
Z njim se lahko prijavite in ga spremenite.
To začasno geslo bo poteklo v {{PLURAL:$5|enem dnevu|$5 dnevih|$5 dnevih}}.


Če je geslo zahteval nekdo drug ali ste se spomnili starega in ga ne želite več spremeniti, lahko sporočilo prezrete in se še naprej prijavljate s starim.",
'noemail'                    => 'Elektronska pošta uporabnika »$1« ni zapisana.',
'passwordsent'               => 'Na naslov elektronske pošte, vpisane za "$1", smo poslali novo geslo. Ko ga boste prejeli, se lahko ponovno prijavite.',
'blocked-mailpassword'       => 'Urejanje z vašega IP naslova je blokirano. Da bi preprečili zlorabe, vam ni dovoljeno tudi uporabljati funkcije za povrnitev pozabljenega gesla.',
'eauthentsent'               => 'E-sporočilo je poslano na navedeni e-naslov. Če želite tja poslati še katero, po v omenjenem sporočilu navedenih navodilih potrdite lastništvo naslova.',
'throttled-mailpassword'     => 'Geselski opomnik je bil v {{PLURAL:$1|zadnji uri|zadnjih $1 urah}} že poslan.
Za preprečevanje zlorab je lahko na {{PLURAL:$1|uro|$1 uri|$1 ure|$1 ur}} poslano samo eno opozorilo.',
'mailerror'                  => 'Napaka pri pošiljanju pošte: $1',
'acct_creation_throttle_hit' => 'Obiskovalci {{GRAMMAR:rodilnik|{{SITENAME}}}} so s tem IP-naslovom v zadnjih 24 urah ustvarili že $1 {{PLURAL:$1|uporabniški račun|uporabniška računa|uporabniške račune|uporabniških računov|uporabniških računov}} in s tem dosegli največje dopustno število v omenjenem časovnem obdobju. Novih računov zato s tem IP-naslovom trenutno žal ne morete več ustvariti.

== Urejate prek posredniškega strežnika? ==

Če urejate prek AOL ali iz Bližnjega vzhoda, Afrike, Avstralije, Nove Zelandije ali iz šole, knjižnice ali podjetja, si IP-naslov morda delite z drugimi uporabniki. Če je tako, ste to sporočilo morda prejeli, čeprav niste ustvarili še nobenega računa. Znova se lahko poskusite registrirati po nekaj urah.',
'emailauthenticated'         => 'Vaš e-poštni naslov je bil potrjen $2 $3.',
'emailnotauthenticated'      => 'Vaš e-poštni naslov še ni potrjen. Za navedene
možnosti se e-pošte ne bo pošiljalo.',
'noemailprefs'               => 'E-poštnega naslova niste vnesli, zato naslednje možnosti ne bodo delovale.',
'emailconfirmlink'           => 'Potrdite svoj e-poštni naslov',
'invalidemailaddress'        => 'E-poštni naslov zaradi neveljavne oblike ni sprejemljiv.
Prosimo, vpišite pravilno oblikovanega ali polje izpraznite.',
'accountcreated'             => 'Račun je ustvarjen',
'accountcreatedtext'         => 'Uporabniški račun za »$1« je ustvarjen.',
'createaccount-title'        => 'Ustvarjanje računa za {{GRAMMAR:tožilnik|{{SITENAME}}}}',
'createaccount-text'         => 'Nekdo je ustvaril račun $2 na {{GRAMMAR:dajalnik|{{SITENAME}}}} ($4). Geslo za »$2« je »$3«. Priporočljivo je, da se prijavite in spremenite svoje geslo sedaj.

To sporočilo lahko prezrete, če je bil račun ustvarjen pomotoma.',
'login-throttled'            => 'Prevečkrat ste poskusili prijavo z napačnim geslom. 
Prosimo počakajte, preden poskusite znova.',
'loginlanguagelabel'         => 'Jezik: $1',

# Password reset dialog
'resetpass'                 => 'Ponastavi geslo računa',
'resetpass_announce'        => 'Trenutno ste prijavljeni z začasno e-poštno kodo. 
Za zaključitev prijave, morate tukaj nastaviti novo geslo:',
'resetpass_text'            => '<!-- Namesto te vrstice vstavite besedilo -->',
'resetpass_header'          => 'Spremeni geslo',
'oldpassword'               => 'Staro geslo:',
'newpassword'               => 'Novo geslo:',
'retypenew'                 => 'Ponovno vpišite geslo:',
'resetpass_submit'          => 'Nastavi geslo in se prijavi',
'resetpass_success'         => 'Vaše geslo je bilo uspešno spremenjeno! Prijava poteka ...',
'resetpass_forbidden'       => 'Gesla ne morete spremeniti',
'resetpass-no-info'         => 'Za neposreden dostop do te strani morate biti prijavljeni.',
'resetpass-submit-loggedin' => 'Spremenite geslo',
'resetpass-wrong-oldpass'   => 'Neveljavno začano ali trenutno geslo.
Morda ste že uspešno spremenili geslo ali pa ste zahtevali novo začasno geslo.',
'resetpass-temp-password'   => 'Začasno geslo:',

# Edit page toolbar
'bold_sample'     => 'Krepko besedilo',
'bold_tip'        => 'Krepko besedilo',
'italic_sample'   => 'Ležeče besedilo',
'italic_tip'      => 'Ležeče besedilo',
'link_sample'     => 'Naslov povezave',
'link_tip'        => 'Notranja povezava',
'extlink_sample'  => 'http://www.example.com naslov povezave',
'extlink_tip'     => 'Zunanja povezava (ne pozabite na predpono http://)',
'headline_sample' => 'Besedilo naslovne vrstice',
'headline_tip'    => 'Naslovna vrstica druge ravni',
'math_sample'     => 'Tu vnesite enačbo',
'math_tip'        => 'Matematična enačba (TeX/LaTeX)',
'nowiki_sample'   => 'Tu vnesite neoblikovano besedilo',
'nowiki_tip'      => 'Prezri wikioblikovanje',
'image_sample'    => 'Zgled.jpg',
'image_tip'       => 'Povezava na sliko',
'media_sample'    => 'Zgled.ogg',
'media_tip'       => 'Povezava na predstavnostno datoteko',
'sig_tip'         => 'Vaš podpis z datumom',
'hr_tip'          => 'Vodoravna črta (uporabljajte zmerno)',

# Edit pages
'summary'                          => 'Povzetek urejanja:',
'subject'                          => 'Tema/naslov:',
'minoredit'                        => 'Manjše urejanje',
'watchthis'                        => 'Opazuj članek',
'savearticle'                      => 'Shrani stran',
'preview'                          => 'Predogled',
'showpreview'                      => 'Prikaži predogled',
'showlivepreview'                  => 'Predogled v živo',
'showdiff'                         => 'Prikaži spremembe',
'anoneditwarning'                  => "'''Opozorilo''': niste prijavljeni. V zgodovino strani se bo zapisal vaš IP-naslov.",
'missingsummary'                   => "'''Opozorilo:''' Niste napisali povzetka urejanja. Ob ponovnem kliku gumba ''Shrani'' se bo vaše urejanje shranilo brez njega.",
'missingcommenttext'               => 'Prosimo, vpišite v spodnje polje komentar.',
'missingcommentheader'             => "'''Opozorilo:''' Niste vnesli zadeve/naslova za ta komentar. Če boste ponovno kliknili Shrani, bo vaše urejanje shranjeno brez le-tega.",
'summary-preview'                  => 'Predogled povzetka',
'subject-preview'                  => 'Predogled zadeve/naslova',
'blockedtitle'                     => 'Uporabnik je blokiran.',
'blockedtext'                      => "<big>'''Urejanje z vašim uporabniškim imenom oziroma IP-naslovom je bilo onemogočeno.'''</big>

Blokiral vas je $1.
Podan razlog je ''$2''.

* Začetek blokade: $8
* Pote blokade: $6
* Nameravane blokade: $7

O blokiranju se lahko pogovorite z $1 ali katerim drugim [[{{MediaWiki:Grouppage-sysop}}|administratorjem]].

Vedite, da lahko ukaz »Pošlji uporabniku e-pismo« uporabite le, če ste v [[Special:Preferences|nastavitvah]] vpisali in potrdili svoj elektronski naslov ter le ta ni bil blokiran. 

Vaš IP-naslov je $3, številka blokade pa #$5. Prosimo, vključite ga v vse morebitne poizvedbe.",
'autoblockedtext'                  => "Vaš IP-naslov je bil samodejno blokiran, saj je bil uporabljen s strani drugega uporabnika, ki ga je blokiral $1.
Podan razlog je:

:''$2''

* Začetek blokade: $8
* Prenehanje blokade: $6
* Predvidena blokada: $7

Kontaktirate lahko $1 ali katerega od drugih [[{{MediaWiki:Grouppage-sysop}}|administratorjev]], da razpravljate o blokadi.

Pomnite, da ne morete uporabljati funkcije »{{:MediaWiki:Emailuser}}«, dokler ne vnesete veljavnega e-poštnega naslova v vaše [[Special:Preferences|uporabniške nastavitve]] in vam njihova uporaba ni bila preprečena.

Vaš trenutni IP-naslov je $3, ID blokiranja pa #$5. Prosimo, vključite ta ID v vsako zastavljeno vprašanje.",
'blockednoreason'                  => 'razlog ni podan',
'blockedoriginalsource'            => "Izvorno besedilo strani '''$1''' je na razpolago spodaj:",
'blockededitsource'                => "Besedilo '''vaših urejanj''' strani '''$1''' je prikazano spodaj:",
'whitelistedittitle'               => 'Za urejanje se morate prijaviti',
'whitelistedittext'                => 'Za urejanje strani se $1.',
'confirmedittext'                  => 'Pred urejanjem strani morate potrditi svoj e-poštni naslov. Prosimo, da ga z uporabo [[Special:Preferences|uporabniških nastavitev]] vpišete in potrdite.',
'nosuchsectiontitle'               => 'Ni takega razdelka',
'nosuchsectiontext'                => 'Poskušali ste urediti razdelek, ki ne obstaja. Ker ni razdelka $1, ni prostora za shranitev vašega urejanja.',
'loginreqtitle'                    => 'Treba se je prijaviti',
'loginreqlink'                     => 'prijava',
'loginreqpagetext'                 => 'Za ogled drugih strani morate $1.',
'accmailtitle'                     => 'Geslo je poslano.',
'accmailtext'                      => "Naključno generirano geslo za [[User talk:$1|$1]] je poslano na $2.

Geslo za ta račun lahko po prijavi ''[[Special:ChangePassword|spremenite]]''.",
'newarticle'                       => '(Nov)',
'newarticletext'                   => "Sledili ste povezavi na stran, ki še ne obstaja.
Da bi stran ustvarili, vnesite v spodnji obrazec besedilo
(za več informacij glej [[{{MediaWiki:Helppage}}|pomoč]]).
Če ste sem prišli po pomoti, v svojem brskalniku kliknite gumb ''Nazaj''.",
'anontalkpagetext'                 => "---- ''To je pogovorna stran za nepodpisanega uporabnika, ki še ni ustvaril računa ali, ki ga ne uporablja. Zaradi tega moramo uporabiti števčen IP-naslov za njegovo/njeno ugotavljanje istovetnosti. Takšen IP naslov si lahko deli več uporabnikov. Če ste nepodpisan uporabnik in če menite, da so nepomembne pripombe namenjene vam, prosimo [[Special:UserLogin|ustvarite račun ali pa se vpišite]], da preprečite naslednje zmede z drugimi nepodpisanimi uporabniki.''",
'noarticletext'                    => 'Na tej strani ni trenutno nobenega besedila. Naslov strani lahko poskusite [[Special:Search/{{PAGENAME}}|poiskati]] na drugih straneh, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} v dnevniških zapisih] ali pa [{{fullurl:{{FULLPAGENAME}}|action=edit}} stran uredite]</span>.',
'userpage-userdoesnotexist'        => 'Uporabniški račun »$1« ni registriran.
Prosimo preverite, ali res želite ustvariti/urediti to stran.',
'clearyourcache'                   => "'''Opomba:''' Da bodo spremembe prišle do veljave, po shranitvi izpraznite predpomnilnik svojega brskalnika: '''Mozilla/Safari:''' držite ''Shift'' in kliknite ''Reload'' (ali pritisnite ''Ctrl-Shift-R''), '''Internet Explorer:''' ''Ctrl-F5'', '''Opera/Konqueror:''' ''F5''.",
'usercssyoucanpreview'             => "'''Nasvet:''' Za preizkušanje svojega novega CSS pred shranjevanjem uporabite gumb ''Prikaži predogled''.",
'userjsyoucanpreview'              => "'''Nasvet:''' Za preizkušanje svojega novega JS pred shranjevanjem uporabite gumb ''Prikaži predogled''.",
'usercsspreview'                   => "'''OPOZORILO: svoj uporabniški CSS le predogledujete in ga še niste shranili!'''",
'userjspreview'                    => "'''Svoj uporabniški Javascript le predogledujete in še ni shranjen!'''",
'userinvalidcssjstitle'            => "'''Opozorilo:''' koža »$1« ne obstaja. Vedite, da .css in .js strani po meri uporabljajo naslov z malo začetnico, npr. {{ns:user}}:Blabla/monobook.css namesto {{ns:user}}:Blabla/Monobook.css.",
'updated'                          => '(Posodobljeno)',
'note'                             => "'''Opomba:'''",
'previewnote'                      => "'''Stran le predogledujete in še ni shranjena!'''",
'previewconflict'                  => 'V prikazanem predogledu je v zgornjem predelu urejanja navedeno besedilo, kakor se bo prikazalo, če ga boste shranili.',
'session_fail_preview'             => "'''Zaradi izgube podatkov o seji nam vašega urejanja žal ni uspelo obdelati. Prosimo, poskusite znova. Če bo spet prišlo do napake, se odjavite in ponovno prijavite. Za nevšečnosti se opravičujemo.'''",
'session_fail_preview_html'        => "'''Zaradi izgube podatkov o seji nam vašega urejanja žal ni uspelo obdelati.'''

''Ker ima {{SITENAME}} vklopljen surovi HTML, je predogled zaradi preprečevanja napadov z JavaScriptom skrit.''

'''Če gre za dobronameren poskus urejanja, vas prosimo, da poskusite znova.'''
Če bo spet prišlo do napake, se [[Special:UserLogout|odjavite]] in ponovno prijavite. Za nevšečnosti se opravičujemo.",
'token_suffix_mismatch'            => "'''Vaše urejanje je bilo zavrnjeno, ker je vaš odjemalec pokvaril ločila v urejevalnem zahtevku.'''
Urejanje je bilo zavrnjeno z namenom preprečitve okvare v besedilu strani.
Največkrat je razlog uporaba hroščato spletno anonimizacijsko storitev.",
'editing'                          => 'Urejanje $1',
'editingsection'                   => 'Urejanje $1 (razdelek)',
'editingcomment'                   => 'Urejanje $1 (nov razdelek)',
'editconflict'                     => 'Navzkrižje urejanj: $1',
'explainconflict'                  => 'Med vašim urejanjem je stran spremenil nekdo drug. Zgornje besedilno območje vsebuje njeno trenutno vsebino in bo edino, ki se bo ob izbiri ukaza »Shrani stran« shranilo. V spodnjem območju so prikazane vaše spremembe, ki jih boste morali vključiti v zgornje.<br />',
'yourtext'                         => 'Vaše besedilo',
'storedversion'                    => 'Shranjena različica',
'nonunicodebrowser'                => "'''OPOMBA''': Vaš brskalnik ne podpira Unicode, zato boste pri urejanju strani z nelatiničnimi znaki morda imeli težave. Za obhod te težave se bodo '''ne-ASCII-znaki v urejevalnem polju spodaj pojavili kot šestnajstiške kode'''.",
'editingold'                       => "'''OPOZORILO: Urejate staro redakcijo strani.'''
Če jo boste shranili, bodo vse poznejše spremembe razveljavljene.",
'yourdiff'                         => 'Primerjava',
'copyrightwarning'                 => "Vsi prispevki k {{GRAMMAR:dajalnik|{{SITENAME}}}} se obravnavajo kot objave pod pogoji $2 (za podrobnosti glej $1). Če niste pripravljeni na neusmiljeno urejanje in prosto razširjanje vašega gradiva, ga ne prispevajte.

Poleg tega zagotavljate, da ste prispevke napisali oziroma ustvarili sami ali pa prepisali iz javno dostopnega ali podobnega prostega vira oziroma da pri tem ne kršite avtorskih pravic.
'''NE DODAJAJTE AVTORSKO ZAŠČITENEGA DELA BREZ DOVOLJENJA !'''",
'copyrightwarning2'                => "Vsi prispevki k {{GRAMMAR:dajalnik|{{SITENAME}}}} se lahko urejajo, spreminjajo ali odstranijo s strani drugih uporabnikov. Če niste pripravljeni na neusmiljeno urejanje in prosto razširjanje vašega gradiva, ga ne prispevajte.

Poleg tega zagotavljate, da ste prispevke napisali oziroma ustvarili sami ali pa prepisali iz javno dostopnega ali podobnega prostega vira oziroma da pri tem ne kršite avtorskih pravic ($1).
'''NE DODAJAJTE AVTORSKO ZAŠČITENEGA DELA BREZ DOVOLJENJA !'''",
'longpagewarning'                  => 'Stran je dolga $1 {{PLURAL:$1|kilobajt|kilobajta|kilobajte|kilobajtov|kilobajtov}}. To je morda več, kot bi želeli, zato premislite o razdelitvi na podstrani oziroma arhiviranju.',
'longpageerror'                    => "'''NAPAKA: Predloženo besedilo je dolgo $1 {{PLURAL:$1|kilobajt|kilobajta|kilobajte|kilobajtov|kilobajtov}}, s čimer presega največjo dovoljeno dolžino $2 {{PLURAL:$2|kilobajta|kilobajtov|kilobajtov|kilobajtov|kilobajtov}}. Zato ga žal ni mogoče shraniti.'''",
'readonlywarning'                  => "'''OPOZORILO: Zbirka podatkov je zaradi vzdrževanja začasno  zaklenjena, kar pomeni, da sprememb ne morete shraniti. Prosimo, prenesite besedilo v urejevalnik in ga dodajte pozneje.'''

Sistemski skrbnik, ki jo je zaklenil, je podal naslednjo razlago: $1",
'protectedpagewarning'             => "'''OPOMBA:''' Stran je zaklenjena in jo lahko urejajo le sodelavci z vzdrževalnimi pravicami. Pri urejanju sledite [[Project:Smernice_zaščitenih_strani|smernicam zaščitenih strani]].",
'semiprotectedpagewarning'         => "'''Opomba:''' Stran je [[Project:Delna zaščita|zaščitena]] in jo lahko urejajo le uveljavljeni uporabniki.",
'cascadeprotectedwarning'          => "'''Opozorilo:''' Ta stran je zaklenjena, tako da jo lahko urejajo le administratorji, saj je bila vključena med sledeče {{PLURAL:$1|stran|strani}}:",
'titleprotectedwarning'            => "'''Opozorilo: Ta stran je bila zaklenjena, tako da so potrebne [[Special:ListGroupRights|posebne pravice]], da jo ustvarite.'''",
'templatesused'                    => 'Na strani uporabljene predloge:',
'templatesusedpreview'             => 'Predloge, uporabljene v tem predogledu:',
'templatesusedsection'             => 'Predloge, uporabljene v tem delu:',
'template-protected'               => '(zaščitena)',
'template-semiprotected'           => '(delno zaščitena)',
'hiddencategories'                 => 'Ta stran je v vsebovana v {{PLURAL:$1|1 skriti kategoriji|$1 skritih kategorijah}}:',
'edittools'                        => '<!-- To besedilo bo prikazano pod urejevalnim poljem in poljem za nalaganje. -->',
'nocreatetitle'                    => 'Članka nisem našel',
'nocreatetext'                     => '{{SITENAME}} ima omejeno zmožnost za ustvarjanje novih strani.
Lahko se vrnete nazaj in urejate že obstoječe strani, ali pa se [[Special:UserLogin|prijavite ali ustvarite račun]].',
'nocreate-loggedin'                => 'Nimate pravic, da bi ustvarjali nove strani na {{GRAMMAR:dajalnik|{{SITENAME}}}}.',
'permissionserrors'                => 'Napake dovoljenj',
'permissionserrorstext'            => 'Nimate dovoljenja zaradi {{PLURAL:$1|naslednjega razloga|naslednjih razlogov|naslednjih razlogov|naslednjih razlogov|naslednjih razlogov}}:',
'permissionserrorstext-withaction' => 'Nimate dovoljenja za $2, zaradi {{PLURAL:$1|naslednjega razloga|naslednjih $1 razlogov|naslednjih $1 razlogov|naslednjih $1 razlogov}}:',
'recreate-moveddeleted-warn'       => "''Opozorilo: Pišete stran, ki je bila nekoč že izbrisana.'''

Premislite preden nadaljujete s pisanjem, morda bo stran zaradi istih razlogov ponovno odstranjena.
Spodaj je prikazan dnevnik brisanja z razlogi za brisanje:",
'moveddeleted-notice'              => 'Ta stran je bila izbrisana.
Dnevnik brisanja za stran je na voljo spodaj.',
'log-fulllog'                      => 'Ogled celotnih dnevniških zapiskov',
'edit-hook-aborted'                => 'Urejanje je bilo brez obrazložitve prekinjeno zaradi neznane napake.',
'edit-gone-missing'                => 'Strani ni mogoče posodobiti.
Izgleda, da je bila izbrisana.',
'edit-conflict'                    => 'Navzkrižje urejanj.',
'edit-no-change'                   => 'Vaše urejanje je bilo prezrto, saj ni vsebovalo sprememb.',
'edit-already-exists'              => 'Ni bilo mogoče ustvariti nove strani, ker že obstaja.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Opozorilo:''' Ta stran vsebuje preveč klicev funkcije razčlenjevalnika kode.

Stran naj bi vsebovala manj kot $2 {{PLURAL:$2|klic|klica|klice|klicev}}, trenutno {{PLURAL:$1|je v uporabi $1 klic|sta v uporabi $1 klica|so v uporabi $1 klici|je v uporabi $1 klicev}}.",
'expensive-parserfunction-category'       => 'Strani s prevelikim številom klicev na funkcijo razčlenjevalnika kode',
'post-expand-template-inclusion-warning'  => "'''Opozorilo:''' Velikost vključenih predlog je prevelika.
Nekatere predloge ne bodo prikazane.",
'post-expand-template-inclusion-category' => 'Strani, kjer je maksimalno število vključenih predlog preseženo',
'post-expand-template-argument-warning'   => "'''Opozorilo:'''' Ta stran vsebuje vsaj en argument predloge, ki ima preveliko razširitev.
Naslednji argumenti so bili izpuščeni.",
'post-expand-template-argument-category'  => 'Strani z izpuščenimi argumenti predloge',
'parser-template-loop-warning'            => 'V predlogi je bila odkrita zanka: [[$1]]',

# "Undo" feature
'undo-success' => 'Urejanje ste razveljavili. Prosim, potrdite in nato shranite spodnje spremembe.',
'undo-failure' => 'Zaradi navzkrižij urejanj, ki so se vmes pojavila, tega urejanja ni moč razveljaviti.',
'undo-norev'   => 'Urejanja ni mogoče razveljaviti, ker ne obstaja ali je bilo izbrisano.',
'undo-summary' => 'Redakcija $1 uporabnika [[Special:Contributions/$2|$2]] ([[Uporabniški pogovor:$2|pogovor]]) razveljavljena',

# Account creation failure
'cantcreateaccounttitle' => 'Računa ni moč ustvariti',
'cantcreateaccount-text' => "Registracija novega uporabnika iz tega IP-naslova ('''$1''') je bila blokirana s strani [[User:$3|$3]].

Razlog, ki ga je podal $3, je ''$2''.",

# History pages
'viewpagelogs'           => 'Poglej dnevniške zapise o strani',
'nohistory'              => 'Stran nima zgodovine urejanja.',
'currentrev'             => 'Trenutna redakcija',
'currentrev-asof'        => 'Trenutna redakcija s časom $1',
'revisionasof'           => 'Redakcija: $1',
'revision-info'          => 'Redakcija iz $1 od $2',
'previousrevision'       => '← Starejša redakcija',
'nextrevision'           => 'Novejša redakcija →',
'currentrevisionlink'    => 'poglejte trenutno redakcijo',
'cur'                    => 'tren',
'next'                   => 'nasl',
'last'                   => 'prej',
'page_first'             => 'prva',
'page_last'              => 'zadnja',
'histlegend'             => 'Za ogled redakcije kliknite njen datum.

Napotek: (tren) = primerjava s trenutno redakcijo,
(prej) = primerjava s prejšnjo redakcijo, <b>m</b> = manjše urejanje',
'history-fieldset-title' => 'Zgodovina poizvedovanj',
'histfirst'              => 'Najstarejše',
'histlast'               => 'Najnovejše',
'historysize'            => '({{PLURAL:$1|$1 zlog|$1 zloga|$1 zlogi|$1 zlogov}})',
'historyempty'           => '(prazno)',

# Revision feed
'history-feed-title'          => 'Zgodovina strani',
'history-feed-description'    => 'Zgodovina navedene strani {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'history-feed-item-nocomment' => '$1 ob $2',
'history-feed-empty'          => 'Želena stran ne obstaja. Morda je bila iz {{GRAMMAR:rodilnik|{{SITENAME}}}} izbrisana ali pa jo je kdo preimenoval. Prosimo, poskusite v {{GRAMMAR:dajalnik|{{SITENAME}}}} [[Special:Search|poiskati]] ustrezajoče nove strani.',

# Revision deletion
'rev-deleted-comment'         => '(pripomba je bila odstranjena)',
'rev-deleted-user'            => '(uporabniško ime je bilo odstranjeno)',
'rev-deleted-event'           => '(vnos je odstranjen)',
'rev-deleted-text-permission' => 'Prikazana redakcija je bila iz javnih arhivov odstranjena. 
Podrobnosti so morda na razpolago v [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} dnevniku brisanja].',
'rev-deleted-text-unhide'     => "Ta sprememba je bila '''izbrisana'''.
Podrobnosti so morda navedene v [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} dnevniku brisanja].
Kot administrator lahko še vedno [$1 pogledate to redakcijo], če želite nadaljevati.",
'rev-suppressed-text-unhide'  => "Ta sprememba je bila '''zavrnjena'''.
Podrobnosti so morda navedene v [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} dnevniku zavračanj].
Kot administrator lahko še vedno [$1 pogledate to redakcijo], če želite nadaljevati.",
'rev-deleted-text-view'       => 'Prikazana redakacija strani je bila iz javnih arhivov odstranjena. Ogledate si jo lahko, ker ste administrator. Podrobnosti so morda navedene v [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} dnevniku brisanja].',
'rev-suppressed-text-view'    => "Ta sprememba je bila '''zavrnjena'''.
Kot administrator lahko jo vidite; podrobnosti so morda navedene v [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} dnevniku zavračanj].",
'rev-deleted-no-diff'         => "Povzetka sprememb ne morete videti, ker je bil eden od popravkov '''izbrisan'''.
Podrobnosti so morda navedene v  [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} dnevniku brisanja].",
'rev-delundel'                => 'pokaži/skrij',
'revisiondelete'              => 'Izbriši/obnovi redakcije',
'revdelete-nooldid-title'     => 'Napačna ciljna redakcija',
'revdelete-nologtype-title'   => 'Tip dnevnik ni podan',
'revdelete-nologtype-text'    => 'Niste navedli vrste dnevnika za prikaz.',
'revdelete-nologid-title'     => 'Neveljaven dnevniški vnos',
'revdelete-no-file'           => 'Navedena datoteka ne obstaja.',
'revdelete-show-file-submit'  => 'Da',
'revdelete-selected'          => "'''{{PLURAL:$2|Izbrana redakcija|$2 izbrani redakciji|$2 izbrane redakcije|$2 izbranih redakcij|$2 izbranih redakcij}} strani [[:$1]]:'''",
'revdelete-text'              => "'''Izbrisane redakcije bodo v zgodovini strani še vedno navedene, vendar bo njihova vsebina za javnost nedostopna.'''
Do skrite vsebine bodo še vedno lahko dostopali drugi administratorji {{GRAMMAR:rodilnik|{{SITENAME}}}} in jo z uporabo istega vmesnika tudi obnovili, razen kjer bodo operaterji spletišča uveljavili dodatne omejitve.",
'revdelete-legend'            => 'Nastavitve z redakcijami povezanih omejitev:',
'revdelete-hide-text'         => 'Skrij besedilo redakcije',
'revdelete-hide-name'         => 'Skrij dejanje in cilj',
'revdelete-hide-comment'      => 'Skrij povzetek urejanja',
'revdelete-hide-user'         => 'Skrij urejevalčevo uporabniško ime/IP-naslov',
'revdelete-hide-restricted'   => 'Omejitve naj veljajo za vse uporabnike, z administratorji vred',
'revdelete-hide-image'        => 'Skrij vsebino datoteke.',
'revdelete-log'               => 'Dnevniški komentar:',
'revdelete-submit'            => 'Uporabi za izbrano redakcijo',
'revdelete-logentry'          => 'sprememba vidnosti redakcij strani [[$1]]',
'logdelete-failure'           => "'''Vidnost dnevnika ne more biti nastavljena!:'''
$1",
'revdel-restore'              => 'Spremeni vidnost',
'pagehist'                    => 'Zgodovina strani',
'deletedhist'                 => 'Zgodovina brisanja',
'revdelete-content'           => 'vsebina',
'revdelete-summary'           => 'povzetek urejanja',
'revdelete-uname'             => 'uporabniško ime',
'revdelete-hid'               => 'hid $1',
'revdelete-unhid'             => 'unhid $1',
'revdelete-log-message'       => '$1 za $2 {{PLURAL:$2|redakcija|redakciji|redakcije|redakcij|redakcij}}',
'logdelete-log-message'       => '$1 za $2 {{PLURAL:$2|dogodek|dogodka|dogodke|dogodkov}}',
'revdelete-edit-reasonlist'   => 'Uredi razloge za brisanje',

# Suppression log
'suppressionlog' => 'Dnevnik vračanj',

# History merging
'mergehistory'                     => 'Združi zgodovine strani',
'mergehistory-box'                 => 'Združite redakcije dveh strani:',
'mergehistory-from'                => 'Izvorna stran:',
'mergehistory-into'                => 'Ciljna stran:',
'mergehistory-list'                => 'Redakcije, ki jih je možno združiti',
'mergehistory-go'                  => 'Prikaži redakcije, ki jih je možno združiti',
'mergehistory-submit'              => 'Združi redakcije',
'mergehistory-empty'               => 'Redakcij ni moč združiti.',
'mergehistory-success'             => '$3 {{PLURAL:$3|redakcija|redakciji|redakcije|redakcij}} [[:$1]] je uspešno spojenih v [[:$2]].',
'mergehistory-fail'                => 'Ne morem izvesti združitev zgodovine, prosimo, ponovno preverite strani in parametre časa.',
'mergehistory-no-source'           => 'Izvirna stran $1 ne obstaja.',
'mergehistory-no-destination'      => 'Ciljna stran $1 ne obstaja.',
'mergehistory-invalid-source'      => 'Izhodiščna stran mora imeti veljaven naslov.',
'mergehistory-invalid-destination' => 'Ciljna stran mora imeti veljaven naslov.',
'mergehistory-autocomment'         => '[[:$1]] združen z/s [[:$2]]',
'mergehistory-comment'             => '[[:$1]] združen z/s [[:$2]]: $3',
'mergehistory-same-destination'    => 'Izhodiščna in ciljna stran ne moreta imeti enak naslov',
'mergehistory-reason'              => 'Razlog:',

# Merge log
'mergelog'           => 'Dnevnik združevanj',
'pagemerge-logentry' => '[[$1]] združen z/s [[$2]] (redakcije do $3)',
'revertmerge'        => 'Razdruži',
'mergelogpagetext'   => 'Prikazan je seznam nedavnih združevanj zgodovin strani.',

# Diffs
'history-title'            => 'Zgodovina strani »$1«',
'difference'               => '(Primerjava redakcij)',
'lineno'                   => 'Vrstica $1:',
'compareselectedversions'  => 'Primerjaj izbrani redakciji',
'showhideselectedversions' => 'Prikaži/skrij izbrane revizije',
'visualcomparison'         => 'Vizualna primerjava',
'wikicodecomparison'       => 'Primerjava wiki-sintakse',
'editundo'                 => 'razveljavi',
'diff-multi'               => '({{PLURAL:$1|$1 vmesna redakcija ni prikazana|$1 vmesni redakciji nista prikazani|$1 vmesne redakcije niso prikazane|$1 vmesnih redakcij ni prikazanih}})',
'diff-movedto'             => 'prestavljeno na $1',
'diff-styleadded'          => '$1 stil dodan',
'diff-added'               => '$1 dodan',
'diff-changedto'           => 'spremenjen v $1',
'diff-movedoutof'          => 'premaknjeno iz $1',
'diff-styleremoved'        => '$1 stil odstranjen',
'diff-removed'             => '$1 odstranjen',
'diff-changedfrom'         => 'spremenjeno iz $1',
'diff-src'                 => 'vir',
'diff-withdestination'     => 's ciljem $1',
'diff-with'                => '&#32;z $1 $2',
'diff-with-additional'     => '$1 $2',
'diff-with-final'          => '&#32;in $1 $2',
'diff-width'               => 'širina',
'diff-height'              => 'višina',
'diff-p'                   => "'''odstavek'''",
'diff-blockquote'          => "'''navedek'''",
'diff-h1'                  => "'''naslov (raven 1)'''",
'diff-h2'                  => "'''naslov (raven 2)'''",
'diff-h3'                  => "'''naslov (raven 3)'''",
'diff-h4'                  => "'''naslov (raven 4)'''",
'diff-h5'                  => "'''naslov (raven 5)'''",
'diff-pre'                 => "'''predoblikovano polje'''",
'diff-div'                 => "'''razdelek'''",
'diff-ul'                  => "'''neurejen seznam'''",
'diff-ol'                  => "'''urejen seznam'''",
'diff-li'                  => "'''vnos v seznamu'''",
'diff-table'               => "'''tabela'''",
'diff-tbody'               => "'''vsebina tabele'''",
'diff-tr'                  => "'''vrstica'''",
'diff-td'                  => "'''celica'''",
'diff-th'                  => "'''glava'''",
'diff-br'                  => "'''prelom'''",
'diff-hr'                  => "'''vodoravno pravilo'''",
'diff-code'                => "'''blok računalniške kode'''",
'diff-dl'                  => "'''opredelitev seznama'''",
'diff-dt'                  => "'''opredelitev izraza'''",
'diff-dd'                  => "'''definicija'''",
'diff-input'               => "'''vnos'''",
'diff-form'                => "'''polje'''",
'diff-img'                 => "'''slika'''",
'diff-span'                => "'''span'''",
'diff-a'                   => "'''povezava'''",
'diff-i'                   => "'''ležeče'''",
'diff-b'                   => "'''krepko'''",
'diff-strong'              => "'''strong'''",
'diff-em'                  => "'''poudarek'''",
'diff-font'                => "'''pisava'''",
'diff-big'                 => "'''velik'''",
'diff-del'                 => "'''izbrisano'''",
'diff-tt'                  => "'''fiksna širina'''",
'diff-sub'                 => "'''podpisano'''",
'diff-sup'                 => "'''nadpisano'''",
'diff-strike'              => "'''prečrtano'''",

# Search results
'searchresults'                    => 'Izid iskanja',
'searchresults-title'              => 'Zadetki za povpraševanje »$1«',
'searchresulttext'                 => 'Za več sporočil o iskanju v {{GRAMMAR:dajalnik|{{SITENAME}}}} glej [[{{MediaWiki:Helppage}}|Iščem v {{GRAMMAR:dajalnik|{{SITENAME}}}}]].',
'searchsubtitle'                   => "Za povpraševanje »'''[[$1]]'''« ([[Special:Prefixindex/$1|vse strani začensi z »$1«]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|vse strani, ki se povezujejo na »$1«]])",
'searchsubtitleinvalid'            => 'Za povpraševanje "$1"',
'noexactmatch'                     => "'''Stran z naslovom ''$1'' ne obstaja.''' Lahko [[:$1|jo ustvarite]].",
'noexactmatch-nocreate'            => "'''Stran z naslovom »$1« ne obstaja.'''",
'toomanymatches'                   => 'Vrnjenih je bilo preveč zadetkov, poskusite z drugačno poizvedbo',
'titlematches'                     => 'Ujemanje z naslovom članka',
'notitlematches'                   => 'Iskanih besed ne vsebuje noben naslov članka',
'textmatches'                      => 'Ujemanje z besedilom članka',
'notextmatches'                    => 'Iskanih besed ne vsebuje nobeno besedilo članka',
'prevn'                            => '{{PLURAL:$1|prejšnja|prejšnji|prejšnje|prejšnjih|prejšnjih}} $1',
'nextn'                            => '{{PLURAL:$1|naslednja|naslednji|naslednjih|naslednjih|naslednjih}} $1',
'prevn-title'                      => '{{PLURAL:$1|Prejšnji rezultat|Prejšnja $1 rezultata|Prejšnji $1 rezultati|Prejšnjih $1 rezultatov}}',
'nextn-title'                      => '{{PLURAL:$1|Naslednji rezultat|Naslednja $1 rezultata|Naslednji $1 rezultati|Naslednjih $1 rezultatov}}',
'shown-title'                      => 'Prikaži $1 {{PLURAL:$1|rezultat|rezultata|rezultate|rezultatov}} na stran',
'viewprevnext'                     => 'Prikazujem ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Možnosti za iskanje',
'searchmenu-exists'                => "'''Na tem wikiju obstaja stran »[[:$1]]«'''",
'searchmenu-new'                   => "'''Ustvari stran »[[:$1]]« na tem wikiju!'''",
'searchhelp-url'                   => 'Help:Vsebina',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Iskanje strani s to predpono]]',
'searchprofile-articles'           => 'Članki',
'searchprofile-project'            => 'Pomoč in projektne strani',
'searchprofile-images'             => 'Večpredstavnost',
'searchprofile-everything'         => 'Vse',
'searchprofile-advanced'           => 'Napredni pogled',
'searchprofile-articles-tooltip'   => 'Išči v $1',
'searchprofile-project-tooltip'    => 'Išči v $1',
'searchprofile-images-tooltip'     => 'Išči datoteke',
'searchprofile-everything-tooltip' => 'Išči po vsej vsebini (vključno s pogovornimi stranmi)',
'searchprofile-advanced-tooltip'   => 'Iskanje v imenskih prostorih po meri',
'search-result-size'               => '$1 ({{PLURAL:$2|1 beseda|2 besedi|$2 besede|$2 besed|$2 besed}})',
'search-result-score'              => 'Ustreznost: $1%',
'search-redirect'                  => '(preusmeritev $1)',
'search-section'                   => '(razdelek $1)',
'search-suggest'                   => 'Iščete morda: $1',
'search-interwiki-caption'         => 'Sorodni projekti',
'search-interwiki-default'         => '$1 zadetkov:',
'search-interwiki-more'            => '(več)',
'search-mwsuggest-enabled'         => 's predlogi',
'search-mwsuggest-disabled'        => 'brez predlogov',
'search-relatedarticle'            => 'Podobno',
'mwsuggest-disable'                => 'Onemogoči AJAX predloge',
'searcheverything-enable'          => 'Iskanje po vseh imenskih prostorih',
'searchrelated'                    => 'povezano',
'searchall'                        => 'vse',
'showingresults'                   => 'Prikazujem <strong>$1</strong> {{PLURAL:$1|zadetek|zadetka|zadetke|zadetkov|zadetkov}}, začenši s št. <strong>$2</strong>.',
'showingresultsnum'                => "Prikazujem '''$3''' {{PLURAL:$3|zadetek|zadetka|zadetke|zadetkov|zadetkov}}, začenši s št. '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Zadetek '''$1''' od '''$3'''|Zadetki '''$1 - $2''' od skupno '''$3'''}} za povpraševanje '''$4'''",
'nonefound'                        => "'''Opomba''': neuspešna poizvedovanja so pogosta ob iskanju vsakdanjih besed, na primer ''imeti'' in ''iz'', ki jih ni na seznamu. Ker gre za zelo pogoste besede, boste skoraj zagotovo iskali uspešneje z zožitvijo tematskega področja. Poskusiti dodati predpono ''all:'' in tako iskati po celotni vsebini (vključno  z pogovornimi stranmi, predlogami, itd.) ali pa za predpono uporabite določen imenski prostor.",
'search-nonefound'                 => 'Ni bilo zadetkov, ki ustrezajo poizvedbi.',
'powersearch'                      => 'Napredno iskanje',
'powersearch-legend'               => 'Napredno iskanje',
'powersearch-ns'                   => 'Iskanje v imenskih prostorih:',
'powersearch-redir'                => 'Seznam preusmeritev',
'powersearch-field'                => 'Iščem:',
'powersearch-togglelabel'          => 'Izberi:',
'powersearch-toggleall'            => 'Vse',
'powersearch-togglenone'           => 'Nič',
'search-external'                  => 'Zunanji iskalnik',
'searchdisabled'                   => '<p>Zaradi hitrejšega delovanja {{GRAMMAR:rodilnik|{{SITENAME}}}} je iskanje po vsej zbirki podatkov začasno onemogočeno. Uporabite lahko Googlov ali Yahoojev iskalnik, vendar so njihovi podatki morda že zastareli.</p>',

# Quickbar
'qbsettings'               => 'Nastavitve hitre vrstice',
'qbsettings-none'          => 'Brez',
'qbsettings-fixedleft'     => 'Levo nepomično',
'qbsettings-fixedright'    => 'Desno nepomično',
'qbsettings-floatingleft'  => 'Levo leteče',
'qbsettings-floatingright' => 'Desno leteče',

# Preferences page
'preferences'                   => 'Nastavitve',
'mypreferences'                 => 'Nastavitve',
'prefs-edits'                   => 'Število urejanj:',
'prefsnologin'                  => 'Niste prijavljeni',
'prefsnologintext'              => 'Za spreminjanje uporabniških nastavitev se <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} prijavite]</span>.',
'changepassword'                => 'Zamenjava gesla',
'prefs-skin'                    => 'Koža',
'skin-preview'                  => 'Predogled',
'prefs-math'                    => 'Prikaz matematičnega besedila',
'datedefault'                   => 'Kakor koli',
'prefs-datetime'                => 'Datum in čas',
'prefs-personal'                => 'Podatki o uporabniku',
'prefs-rc'                      => 'Zadnje spremembe',
'prefs-watchlist'               => 'Spisek nadzorov',
'prefs-watchlist-days'          => 'Število dni za prikaz na spisku nadzorov:',
'prefs-watchlist-days-max'      => 'Največ 7 dni',
'prefs-watchlist-edits'         => 'Število urejanj za prikaz na razširjenem spisku nadzorov:',
'prefs-watchlist-edits-max'     => 'Največje število: 1000',
'prefs-watchlist-token'         => 'Ključ za spisek nadzorov:',
'prefs-misc'                    => 'Druge nastavitve',
'prefs-resetpass'               => 'Spremeni geslo',
'prefs-email'                   => 'Možnosti E-pošte',
'prefs-rendering'               => 'Videz',
'saveprefs'                     => 'Shrani',
'resetprefs'                    => 'Ponastavi',
'restoreprefs'                  => 'Obnovi vse privzete nastavitve',
'prefs-editing'                 => 'Urejanje',
'prefs-edit-boxsize'            => 'Velikost okna za urejanje.',
'rows'                          => 'Vrstic:',
'columns'                       => 'Stolpcev:',
'searchresultshead'             => 'Nastavitve poizvedovanja',
'resultsperpage'                => 'Prikazanih zadetkov na stran:',
'contextlines'                  => 'Vrstic na zadetek:',
'contextchars'                  => 'Znakov na vrstico:',
'stub-threshold'                => 'Prag označevanja <a href="" class="stub" onclick="return false">škrbin</a>:',
'recentchangesdays'             => 'Število dni prikazanih v zadnjih spremembah:',
'recentchangesdays-max'         => 'Največ $1 {{PLURAL:$1|dan|dneva|dnevi|dni}}',
'recentchangescount'            => 'Privzeto število prikazanih urejanj:',
'prefs-help-recentchangescount' => 'To vključuje zadnje spremembe, zgodovine strani in dnevniške zapise.',
'prefs-help-watchlist-token'    => 'Izpolnjevanje tega polja s skrivnim ključem bo ustvarilo vir RSS za vaš spisek nadzorov.
Kdorkoli pozna ta ključ bo lahko bral vaš spisek nadzorov, zato izbrite varen in čim daljši ključ.
Tukaj je naključno ustvarjena vrednost, ki jo lahko uporabite: $1',
'savedprefs'                    => 'Spremembe ste uspešno shranili!',
'timezonelegend'                => 'Časovni pas',
'localtime'                     => 'Krajevni čas:',
'timezoneuseserverdefault'      => 'Uporabi privzeti strežniški čas',
'timezoneuseoffset'             => 'Drugo (navedite izravnavo)',
'timezoneoffset'                => 'Izravnava¹:',
'servertime'                    => 'Strežniški čas:',
'guesstimezone'                 => 'Izpolni iz brskalnika',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktika',
'timezoneregion-asia'           => 'Azija',
'timezoneregion-atlantic'       => 'Atlantski ocean',
'timezoneregion-australia'      => 'Avstralija',
'timezoneregion-europe'         => 'Evropa',
'timezoneregion-indian'         => 'Indijski ocean',
'timezoneregion-pacific'        => 'Tihi ocean',
'allowemail'                    => 'Drugim uporabnikom omogoči pošiljanje e-pošte',
'prefs-searchoptions'           => 'Možnosti iskanja',
'prefs-namespaces'              => 'Imenski prostori:',
'defaultns'                     => 'Oziroma išči v naslednjih imenskih prostorih:',
'default'                       => 'privzeto',
'prefs-files'                   => 'Datoteke',
'prefs-custom-css'              => 'CSS po meri',
'prefs-custom-js'               => 'JS po meri',
'prefs-reset-intro'             => 'To stran lahko uporabite za ponastavitev nastavitev na privzete za to spletišče.
Tega ni mogoče razveljaviti.',
'prefs-emailconfirm-label'      => 'Potrditev E-pošte:',
'prefs-textboxsize'             => 'Velikost urejevalnega polja',
'youremail'                     => 'E-pošta:',
'username'                      => 'Uporabniško ime:',
'uid'                           => 'ID-številka:',
'prefs-memberingroups'          => 'Član {{PLURAL:$1|naslednje skupine|naslednjih skupin|naslednjih skupin|naslednjih skupin|naslednjih skupin}}:',
'prefs-memberingroups-type'     => '$1',
'prefs-registration'            => 'Registriran od:',
'yourrealname'                  => 'Resnično ime:',
'yourlanguage'                  => 'Jezik:',
'yourvariant'                   => 'Jezikovna različica:',
'yournick'                      => 'Podpis:',
'prefs-help-signature'          => 'Komentarje na pogovornih straneh, je treba podpisati s »<nowiki>~~~~</nowiki>«, kar bo pretvorjeno v vaš podpis s časom.',
'badsig'                        => 'Neveljaven surovi podpis; preverite oznake HTML.',
'badsiglength'                  => 'Vaš podpis je preobsežen.
Ne sme biti daljši od $1 {{PLURAL:$1|znaka|znakov}}.',
'yourgender'                    => 'Spol:',
'gender-unknown'                => 'nedoločen',
'gender-male'                   => 'moški',
'gender-female'                 => 'ženski',
'prefs-help-gender'             => 'Podatek ni obvezen, uporablja pa se ga izključno za pravilno obliko nasavljanja našega programja glede na spol. Podatek bo javno prikazan.',
'email'                         => 'E-pošta',
'prefs-help-realname'           => 'Pravo ime je neobvezno. 
Če se odločite, da ga boste navedli, bo uporabljeno za priznavanje vašega dela.',
'prefs-help-email'              => 'E-poštni naslov ni obvezen, vendar vam omogoča da vam v primeru pozabljenega gesla pošljemo novo.
Poleg tega vpisan e-poštni naslov omogoča drugim, da vam lahko pošiljajo elektronsko pošto brez razkritja vaše identitete.',
'prefs-help-email-required'     => 'E-poštni naslov je obvezen.',
'prefs-info'                    => 'Osnovni podatki',
'prefs-i18n'                    => 'Internacionalizacija',
'prefs-signature'               => 'Podpis',
'prefs-dateformat'              => 'Zapis datuma',
'prefs-timeoffset'              => 'Čas za izravnavo',
'prefs-advancedediting'         => 'Napredne možnosti',
'prefs-advancedrc'              => 'Napredne možnosti',
'prefs-advancedrendering'       => 'Napredne možnosti',
'prefs-advancedsearchoptions'   => 'Napredne možnosti',
'prefs-advancedwatchlist'       => 'Napredne možnosti',
'prefs-display'                 => 'Prikaži možnosti',
'prefs-diffs'                   => 'Razlike',

# User rights
'userrights'                  => 'Upravljanje s pravicami uporabnikov',
'userrights-lookup-user'      => 'Upravljanje z uporabniškimi skupinami',
'userrights-user-editname'    => 'Vpišite uporabniško ime:',
'editusergroup'               => 'Uredi uporabniške skupine',
'editinguser'                 => "Urejanje pravic uporabnika '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Urejanje uporabniških skupin',
'saveusergroups'              => 'Shrani spremembe',
'userrights-groupsmember'     => 'Član skupine:',
'userrights-groups-help'      => 'Spreminjate lahko skupine uporabnika:
* Obkljukano polje pomeni uporabnika, ki je v skupini 
* Odkljukano polje pomeni uporabnika, ki ni v skupini
* Zvezdica (*) kaže, da uporabnika ne boste mogli odstraniti iz skupine, ko ga vanjo dodate oz. obratno.',
'userrights-reason'           => 'Razlog za spremembo:',
'userrights-no-interwiki'     => 'Nimate dovoljenja za urejanje pravic uporabnikov na drugih wikijih.',
'userrights-nodatabase'       => 'Podatkovna baza $1 ne obstaja ali ni lokalna.',
'userrights-nologin'          => 'Za dodeljevanje uporabniških pravic se morate [[Special:UserLogin|prijaviti]] s skrbniškim računom.',
'userrights-notallowed'       => 'Vaš račun nima dovoljenja za dodeljevanje pravic uporabnikom.',
'userrights-changeable-col'   => 'Skupine, ki jih lahko spremenite',
'userrights-unchangeable-col' => 'Skupine ne morete spremeniti',

# Groups
'group'               => 'Skupina:',
'group-user'          => 'Uporabniki',
'group-autoconfirmed' => 'Samodejno potrjeni uporabniki',
'group-bot'           => 'Boti',
'group-sysop'         => 'Administratorji',
'group-bureaucrat'    => 'Birokrati',
'group-suppress'      => 'Nadzorniki',
'group-all'           => '(vsi)',

'group-user-member'          => 'Uporabnik',
'group-autoconfirmed-member' => 'Samodejno potrjen uporabnik',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Birokrat',
'group-suppress-member'      => 'Nadzorik',

'grouppage-user'          => '{{ns:project}}:Uporabniki',
'grouppage-autoconfirmed' => '{{ns:project}}:Samodejno potrjeni uporabniki',
'grouppage-bot'           => '{{ns:project}}:Boti',
'grouppage-sysop'         => '{{ns:project}}:Administratorji',
'grouppage-bureaucrat'    => '{{ns:project}}:Birokrati',
'grouppage-suppress'      => '{{ns:project}}:Nadzorniki',

# Rights
'right-read'                  => 'Branje strani',
'right-edit'                  => 'Urejanje strani',
'right-createpage'            => 'Ustvarjanje strani (ki niso pogovorne)',
'right-createtalk'            => 'Ustvarjanje pogovornih strani',
'right-createaccount'         => 'Ustvarjanje novih uporabniških računov',
'right-minoredit'             => 'Označevanje urejanj kot manjših',
'right-move'                  => 'Premikanje strani',
'right-move-subpages'         => 'Premikanje strani s pripadajočimi podstranmi',
'right-move-rootuserpages'    => 'Premikanje korenskih uporabniških strani',
'right-movefile'              => 'Premikanje datotek',
'right-suppressredirect'      => 'Možnost izpuščanja preusmeritve pri premikanju strani',
'right-upload'                => 'Nalaganje datotek',
'right-reupload'              => 'Nadomeščanje obstoječih datotek',
'right-reupload-own'          => 'Nadomeščanje obstoječih lastnih datotek',
'right-reupload-shared'       => 'Nalaganje lokalnih zamenjav za datoteke iz skupnih večpredstavnostnih skladišč',
'right-upload_by_url'         => 'Nalaganje datotek iz naslova URL',
'right-purge'                 => 'Osvežitev predpomnilnika strani, brez potrditve',
'right-autoconfirmed'         => 'Urejanje delno-zaščitenih strani',
'right-apihighlimits'         => 'Uporaba višje omejitve API poizvedb',
'right-writeapi'              => 'Uporaba napisanega APIja',
'right-delete'                => 'Brisanje strani',
'right-bigdelete'             => 'Brisanje strani z obsežno zgodovino',
'right-deleterevision'        => 'Brisanje in obnova posebnih redakcij strani',
'right-deletedhistory'        => 'Ogled zgodovine brisanja, brez besedila izbrisanih strani',
'right-browsearchive'         => 'Iskanje izbrisanih strani',
'right-undelete'              => 'Obnavljanje strani',
'right-suppressrevision'      => 'Pregled in obnova pred administratorjem skritih redakcij',
'right-suppressionlog'        => 'Ogled zasebnih dnevniških zapisov',
'right-block'                 => 'Preprečitev (blokada) urejanja drugih uporabnikov',
'right-blockemail'            => 'Drugemu uporabniku lahko prepreči pošiljanje e-pošte',
'right-hideuser'              => 'Blokiranje uporabnika, in skritje pred javnostjo',
'right-protect'               => 'Spreminjanje stopnje zaščite in urejanje zaščitenih strani',
'right-editprotected'         => 'Urejanje zaščitenih strani (brez kaskadne zaščite)',
'right-editinterface'         => 'Urejanje uporabniškega vmesnika',
'right-editusercssjs'         => 'Urejanje CSS in JS datotek drugih uporabnikov',
'right-editusercss'           => 'Uredi CSS datotek drugih uporabnikov',
'right-edituserjs'            => 'Uredi JS datotek drugih uporabnikov',
'right-rollback'              => 'Hitro vračanje urejanj od zadnjega uporabnika, ki je urejal določeno stran',
'right-markbotedits'          => 'Označi vrnjena urejanja kot urejanja botov',
'right-noratelimit'           => 'Omejitve dejavnosti ne veljajo',
'right-import'                => 'Uvoz strani iz drugih wikijev',
'right-importupload'          => 'Uvoz strani iz naložene datoteke',
'right-patrol'                => 'Označevanje urejanja drugih kot nadzorovana',
'right-autopatrol'            => 'Označevanje urejanj drugih za samodejno nadzarovana',
'right-unwatchedpages'        => 'Preglejte seznam ne spremljanih strani',
'right-trackback'             => 'Pošlje sledilnik',
'right-mergehistory'          => 'Spoji zgodovino strani',
'right-userrights'            => 'Urejanje vseh uporabniških pravic',
'right-userrights-interwiki'  => 'Urejanje uporabniških pravic uporabnikov na drugih wikijih',
'right-siteadmin'             => 'Zaklepanje in odklepanje baze podatkov',
'right-reset-passwords'       => 'Ponastavljanje gesla drugih uporabnikov',
'right-override-export-depth' => 'Izvoz strani, vključno s povezaimi straneh do globine 5',
'right-versiondetail'         => 'Pregledovanje razširjenih informacij o različici programske opreme',

# User rights log
'rightslog'      => 'Dnevnik uporabniških pravic',
'rightslogtext'  => 'Prikazan je dnevnik sprememb uporabniških pravic.',
'rightslogentry' => '- sprememba pravic uporabnika $1 iz $2 v $3',
'rightsnone'     => '(nobeno)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'branje te strani',
'action-edit'                 => 'urejanje te strani',
'action-createpage'           => 'ustvarjenje strani',
'action-createtalk'           => 'ustvarjanje pogovornih strani',
'action-createaccount'        => 'registracija tega uporabniškega računa',
'action-minoredit'            => 'označevanje tega urejanja kot manjšega',
'action-move'                 => 'premik te strani',
'action-move-subpages'        => 'premik te strani in njenih podstrani',
'action-move-rootuserpages'   => 'premik korenskih uporabniških strani',
'action-movefile'             => 'premik te datoteke',
'action-upload'               => 'nalaganje te datoteke',
'action-reupload'             => 'prepis obstoječe datoteke',
'action-reupload-shared'      => 'povoz te datoteke na skupnem skladišču',
'action-upload_by_url'        => 'nalaganje te datoteke iz URL naslova',
'action-writeapi'             => 'uporabo API-ja za pisanje',
'action-delete'               => 'brisanje te strani',
'action-deleterevision'       => 'brisanje te redakcije',
'action-deletedhistory'       => 'pregled zgodovine izbrisanih redakcij te strani',
'action-browsearchive'        => 'iskanje izbrisanih strani',
'action-undelete'             => 'obnavljanje te strani',
'action-suppressrevision'     => 'vpogled in obnavljanje te skrite redakcije',
'action-suppressionlog'       => 'vpogled tega zasebnega dnevnika',
'action-block'                => 'blokiranje urejanja s tega uporabniškega računa',
'action-protect'              => 'spremembo stopnje zaščite te strani',
'action-import'               => 'uvoz te strani iz drugega wikija',
'action-importupload'         => 'uvoz strani iz naložene datoteke',
'action-patrol'               => 'označevanje sprememb drugih kot nadzorovane',
'action-autopatrol'           => 'označevanje svojih urejanj kot nadzorovane',
'action-unwatchedpages'       => 'ogled seznama nenadzorovanih strani',
'action-trackback'            => 'pošlje sledilnik',
'action-mergehistory'         => 'združitev zgodovine te strani',
'action-userrights'           => 'upravljanje vseh uporabnikovih pravic',
'action-userrights-interwiki' => 'upravljanje uporabniških pravic za uporabnike drugih wikijev',
'action-siteadmin'            => 'zaklenitev ali odklepanje podatkovne baze',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|sprememba|spremembi|spremembe|sprememb|sprememb}}',
'recentchanges'                     => 'Zadnje spremembe',
'recentchanges-legend'              => 'Možnosti zadnjih sprememb',
'recentchangestext'                 => 'Na tej strani lahko spremljajte najnedavnejše spremembe wikija.',
'recentchanges-feed-description'    => 'Spremljajte najnovejše spremembe wikija prek tega vira.',
'recentchanges-label-legend'        => 'Legenda: $1.',
'recentchanges-legend-newpage'      => '$1 - nova stran',
'recentchanges-label-newpage'       => 'S tem urejanjem je bila ustvarjena nova stran',
'recentchanges-legend-minor'        => '$1 - manjše urejanje',
'recentchanges-label-minor'         => 'To je manjše urejanje',
'recentchanges-legend-bot'          => '$1 - urejanja bota',
'recentchanges-label-bot'           => 'To urejanje je bilo izvedeno z botom',
'recentchanges-legend-unpatrolled'  => '$1 - ne-patruljirano urejanje',
'recentchanges-label-unpatrolled'   => 'To urejanje še ni bila pregledano',
'rcnote'                            => "Prikazujem {{PLURAL:$1|zadnjo spremembo|zadnji '''$1''' spremembi|zadnje '''$1''' spremembe|zadnjih '''$1''' sprememb|zadnjih '''$1''' sprememb}} v {{PLURAL:$2|zadnjem|zadnjih|zadnjih|zadnjih|zadnjih}} '''$2''' {{PLURAL:$2|dnevu|dneh|dneh|dneh|dneh}}, od $5, $4.",
'rcnotefrom'                        => 'Navedene so spremembe od <b>$2</b> dalje (prikazujem jih do <b>$1</b>).',
'rclistfrom'                        => 'Prikaži spremembe od $1 naprej.',
'rcshowhideminor'                   => '$1 manjša urejanja',
'rcshowhidebots'                    => '$1 bote',
'rcshowhideliu'                     => '$1 prijavljene uporabnike',
'rcshowhideanons'                   => '$1 brezimne uporabnike',
'rcshowhidepatr'                    => '$1 pregledana urejanja',
'rcshowhidemine'                    => '$1 moja urejanja',
'rclinks'                           => 'Prikaži zadnji $1 spremembi v zadnjih $2 dneh;<br />$3',
'diff'                              => 'prim',
'hist'                              => 'zgod',
'hide'                              => 'skrij',
'show'                              => 'prikaži',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[temo {{PLURAL:$1|spremlja|spremljata|spremljajo|spremlja|spremlja}} $1 {{PLURAL:$1|uporabnik|uporabnika|uporabniki|uporabnikov|uporabnikov}}]',
'rc_categories'                     => 'Omejitev na kategorije (ločite jih z »|«)',
'rc_categories_any'                 => 'Katero koli',
'newsectionsummary'                 => '/* $1 */ nova razprava',
'rc-enhanced-expand'                => 'Pokaži podrobnosti (potrebuje JavaScript)',
'rc-enhanced-hide'                  => 'Skrij podrobnosti',

# Recent changes linked
'recentchangeslinked'          => 'Sorodne spremembe',
'recentchangeslinked-feed'     => 'Sorodne spremembe',
'recentchangeslinked-toolbox'  => 'Sorodne spremembe',
'recentchangeslinked-title'    => 'Spremembe, povezane z "$1"',
'recentchangeslinked-noresult' => 'Na povezanih straneh v določenem obdobju ni bilo nobenih sprememb.',
'recentchangeslinked-summary'  => "To je seznam nedavnih sprememb strani povezanih na določeno stran (ali iz določene kategorije).
Strani iz [[Special:Watchlist|vašega spiska nadzorov]] so '''odebeljene'''.",
'recentchangeslinked-page'     => 'Ime strani:',
'recentchangeslinked-to'       => 'Prikaži spremembe na določeno stran povezanih strani',

# Upload
'upload'                      => 'Naloži datoteko',
'uploadbtn'                   => 'Naloži datoteko',
'reuploaddesc'                => 'Vrnitev na obrazec za nalaganje.',
'uploadnologin'               => 'Niste prijavljeni',
'uploadnologintext'           => 'Za nalaganje datotek se [[Special:UserLogin|prijavite]].',
'upload_directory_missing'    => 'Mapa za nalaganje datotek ($1) manjka in je ni bilo mogoče ustvariti s spletnim strežnikom.',
'upload_directory_read_only'  => 'V mapo za nalaganje datotek ($1) spletni strežnik ne more pisati.',
'uploaderror'                 => 'Napaka',
'uploadtext'                  => "Spodnji obrazec lahko uporabite za nalaganje datotek.
Za ogled ali iskanje že naloženih pojdite na [[Special:FileList|seznam naloženih datotek]], ponovne naložitve so zapisane tudi v [[Special:Log/upload|dnevniku nalaganja]], izbrisi pa v [[Special:Log/delete|dnevniku brisanja]].

Datoteko lahko na želeno stran vključite z naslednjo skladnjo
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datoteka.jpg]]</nowiki></tt>''' (polna velikost)
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datoteka.jpg|200px|thumb|left|opisno besedilo]]</nowiki></tt>''' (slika pomanjšana na velikost 200px, uokvirjena, z levo poravnavo in opisom »opisno besedilo«)
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Datoteka.ogg]]</nowiki></tt>''' (neposredna povezava z datoteko)",
'upload-permitted'            => 'Dovoljene vrste datotek: $1.',
'upload-preferred'            => 'Priporočene vrste datotek: $1.',
'upload-prohibited'           => 'Prepovedane vrste datotek: $1.',
'uploadlog'                   => 'dnevnik nalaganja',
'uploadlogpage'               => 'Dnevnik nalaganja datotek',
'uploadlogpagetext'           => 'Prikazan je seznam nedavno naloženih predstavnostnih datotek.
Za grafični pogled obiščite [[Special:NewFiles|galerijo novih datotek]].',
'filename'                    => 'Ime datoteke',
'filedesc'                    => 'Povzetek',
'fileuploadsummary'           => 'Povzetek (avtor, spletni naslov vira ipd.):',
'filereuploadsummary'         => 'Spremembe datoteke:',
'filestatus'                  => 'Položaj avtorskih pravic:',
'filesource'                  => 'Vir:',
'uploadedfiles'               => 'Naložene datoteke',
'ignorewarning'               => 'Naloži kljub opozorilu',
'ignorewarnings'              => 'Prezri vsa opozorila',
'minlength1'                  => 'Imena datotek mora biti dolga vsaj eno črko.',
'illegalfilename'             => 'Ime datoteke »$1« vsebuje v naslovih strani prepovedane znake. Prosimo, poskusite datoteko naložiti pod drugim imenom.',
'badfilename'                 => 'Ime datoteke se je samodejno popravilo v »$1«.',
'filetype-badmime'            => 'Datoteke MIME-vrste »$1« ni dovoljeno nalagati.',
'filetype-bad-ie-mime'        => 'Ne morem naložiti datoteke, ker bi jo Internet Explorer zaznal kot »$1« in jo zavrnil kot potencialno nevarno vrsto datoteke.',
'filetype-unwanted-type'      => "'''».$1«''' je nezaželena datotečna vrsta.
{{PLURAL:$3|Dovoljena datotečna vrsta je|Dovoljena datotečni vrsti sta|Dovoljene datotečne vrste so|Dovoljene datotečne vrste so}} $2.",
'filetype-banned-type'        => "'''».$1«''' ni dovoljena datotečna vrsta.
{{PLURAL:$3|Dovoljena datotečna vrsta je|Dovoljeni datotečni vrsti sta|Dovoljene datotečne vrste so|Dovoljene datotečne vrste so}} $2.",
'filetype-missing'            => 'Datoteka nima končnice (kot ».jpg«).',
'large-file'                  => 'Priporočeno je, da datoteke niso večje od $1; ta datoteka je $2.',
'largefileserver'             => 'Velikost datoteke presega strežnikove nastavitve.',
'emptyfile'                   => 'Naložena datoteka je morda prazna. Do tega bi lahko prišlo zaradi slovnične napake v imenu. Ali datoteko resnično želite naložiti?',
'fileexists'                  => "Datoteka s tem imenom že obstaja.
Preden jo povozite, preverite stran '''<tt>[[:$1]]</tt>'''.
Da preprečite navzkrižja z že obstoječimi datotekami, uporabljajte za datoteke opisna imena (npr.
»Eifflov stolp, Pariz, ponoči.jpg«).
[[$1|thumb]]",
'fileexists-extension'        => "Datoteka s podobnim imenom že obstaja: [[$2|thumb]]
* Ime naložene datoteke: '''<tt>[[:$1]]</tt>'''
* Ime obstoječe datoteke: '''<tt>[[:$2]]</tt>'''
Prosimo, izberite drugo ime.",
'fileexists-thumbnail-yes'    => "Kot izgleda, je ta slika pomanjšana ''(thumbnail)''. [[$1|thumb]]
Prosimo, preverite datoteko '''<tt>[[:$1]]</tt>'''.
Če je preverjena datoteka enaka kot ta, ki jo nalage, ni potrebno nalagati še dodatne sličice.",
'file-thumbnail-no'           => "Ime datoteke se začne z '''<tt>$1</tt>'''. Izgleda, da je to pomanjšana slika ''(thumbnail)''.
Če imate sliko polne resolucije, jo naložite, drugače spremenite ime datoteke.",
'fileexists-forbidden'        => 'Datoteka s tem imenom že obstaja in je ni mogoče prepisati.
Poskusite svojo datoteko naložiti pod drugim imenom. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Datoteka s tem imenom že obstaja v skupnem skladišču datotek.
Prosimo, vrnite se in naložite svojo datoteko pod drugim imenom. 
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ta datoteka je dvojnik {{PLURAL:$1|naslednje datoteke|naslednjih datotek}}:',
'file-deleted-duplicate'      => 'Datoteka je identična tej ([[$1]]), ki je bila predhodno izbrisana.
Preverite zgodovino brisanja datoteke, preden jo ponovno naložite.',
'successfulupload'            => 'Datoteko ste uspešno naložili',
'uploadwarning'               => 'Opozorilo!',
'savefile'                    => 'Shrani datoteko',
'uploadedimage'               => '- naložena datoteka »[[$1]]«',
'overwroteimage'              => 'naložena nova različica datoteke »[[$1]]«',
'uploaddisabled'              => 'Nalaganje je onemogočeno',
'uploaddisabledtext'          => 'Nalaganje datotek je onemogočeno.',
'php-uploaddisabledtext'      => 'Nalaganje datotek je onemogočeno v PHP.
Prosimo preverite file_uploads nastavitev.',
'uploadscripted'              => 'Datoteka vsebuje HTML- ali skriptno kodo, ki bi jo lahko brskalnik razlagal napačno.',
'uploadcorrupt'               => 'Datoteka je poškodovana ali pa ima napačno končnico. Prosimo, preverite jo in znova naložite.',
'uploadvirus'                 => 'Datoteka morda vsebuje virus! Podrobnosti: $1',
'sourcefilename'              => 'Ime izvorne datoteke:',
'destfilename'                => 'Ime ciljne datoteke:',
'upload-maxfilesize'          => 'Največja velikost datoteke: $1',
'watchthisupload'             => 'Opazuj to datoteko',
'filewasdeleted'              => 'Datoteka s tem imenom je bila nekoč že naložena in potem izbrisana. Preden jo znova naložite, preverite $1.',
'upload-wasdeleted'           => "'''Opozorilo: Nalagate datoteko, ki je bila predhodno že izbrisana.'''

Premislite ali je nadaljevanje nalaganja primerno.
Za lažjo presojo je spodaj izpisek iz dnevnika brisanj:",
'filename-bad-prefix'         => "Ime datoteke, ki jo nalagate, se začne z '''»$1«''', ki je neopisno ime, ponavadi dodeljeno samodejno s strani digitalnih fotoaparatov. Prosimo, določite bolj opisno ime vaše datoteke.",
'filename-prefix-blacklist'   => ' #<!-- leave this line exactly as it is --> <pre>
# Sintaksa:
#   * Vse od znaka »#« in do konca vrstice je komentar
#   * Vsaka neprazna vrstica je predpona za tipična imena datotek, določena samodejno s strani digitalnih fotoaparatov
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # nekateri mobilni telefoni
IMG # generično
JD # Jenoptik
MGP # Pentax
PICT # mešano
 #</pre> <!-- leave this line exactly as it is -->',

'upload-proto-error'        => 'Nepravilni protokol',
'upload-file-error'         => 'Notranja napaka',
'upload-file-error-text'    => 'Prišlo je do notranje napake pri poskusu ustvariti začasne datoteke na strežnik.
Prosimo obrnite se na [[Special:ListUsers/sysop|administrator]]ja.',
'upload-misc-error'         => 'Neznana napaka nalaganja',
'upload-too-many-redirects' => 'URL vsebuje preveč preusmeritev',
'upload-unknown-size'       => 'Neznana velikost',
'upload-http-error'         => 'HTTP napaka: $1',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => 'Ni možno doseči URL',
'upload-curl-error28' => 'Časovna prekinitev nalaganja',

'license'            => 'Dovoljenje:',
'license-header'     => 'Dovoljenje:',
'nolicense'          => 'Nobeno (opomba: datoteka bo morda izbrisana)',
'license-nopreview'  => '(Predogled ni na voljo)',
'upload_source_url'  => ' (veljaven, javnosti dostopen URL)',
'upload_source_file' => ' (datoteka na vašem računalniku)',

# Special:ListFiles
'listfiles-summary'     => 'Ta posebna stran prikazuje vse naložene datoteke.
Po privzetem so na vrhu seznama najnovejše datoteke.
Za spremembo razvrščanja kliknete na glavo stolpca.',
'listfiles_search_for'  => 'Išči po imenu datoteke:',
'imgfile'               => 'dat.',
'listfiles'             => 'Seznam datotek',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Ime',
'listfiles_user'        => 'Uporabnik',
'listfiles_size'        => 'Velikost',
'listfiles_description' => 'Opis',
'listfiles_count'       => 'Različice',

# File description page
'file-anchor-link'          => 'Datoteka',
'filehist'                  => 'Zgodovina datoteke',
'filehist-help'             => 'Klikni na datum in čas za ogled datoteke, ki je bila takrat naložena.',
'filehist-deleteall'        => 'izbriši vse',
'filehist-deleteone'        => 'izbriši to',
'filehist-revert'           => 'vrni',
'filehist-current'          => 'trenutno',
'filehist-datetime'         => 'Datum in čas',
'filehist-thumb'            => 'Sličica',
'filehist-thumbtext'        => 'Sličica za različico $1',
'filehist-nothumb'          => 'Brez predogleda',
'filehist-user'             => 'Uporabnik',
'filehist-dimensions'       => 'Dimenzije',
'filehist-filesize'         => 'Velikost datoteke',
'filehist-comment'          => 'Komentar',
'filehist-missing'          => 'Datoteka manjka',
'imagelinks'                => 'Strani z datoteko',
'linkstoimage'              => 'Datoteka je del {{PLURAL:$1|naslednje strani|naslednjih $1 strani|naslednjih $1 strani|naslednjih $1 strani}} {{GRAMMAR:rodilnik|{{SITENAME}}}} (strani drugih projektov niso navedene):',
'nolinkstoimage'            => 'Z datoteko se ne povezuje nobena stran.',
'morelinkstoimage'          => 'Preglejte [[Special:WhatLinksHere/$1|več povezav]] na to datoteko.',
'redirectstofile'           => 'Na to datoteko {{PLURAL:$1|preusmerja naslednja datoteka|preusmerjata naslednji datoteki|preusmerjajo naslednje $1 datoteke|preusmerja naslednjih $1 datotek|preusmerja naslednjih $1 datotek}}:',
'sharedupload'              => 'Datoteka je del $1 in se s tega mesta lahko uporabi tudi v drugih projektih.',
'filepage-nofile'           => 'Datoteka s tem imenom ne obstaja.',
'filepage-nofile-link'      => 'Datoteka s tem imenom ne obstaja, vendar pa jo lahko [$1 naložite].',
'uploadnewversion-linktext' => 'Naložite novo različico datoteke',
'shared-repo-from'          => 'iz $1',
'shared-repo'               => 'skupno skladišče',

# File reversion
'filerevert'                => 'Vrni $1',
'filerevert-legend'         => 'Vrni datoteko',
'filerevert-intro'          => '<span class="plainlinks">Vračate datoteko \'\'\'[[Media:$1|$1]]\'\'\' na [$4 različico $3, $2].</span>',
'filerevert-comment'        => 'Komentar:',
'filerevert-defaultcomment' => 'Vrnjeno na različico $2, $1',
'filerevert-submit'         => 'Vrni',
'filerevert-success'        => '<span class="plainlinks">Datoteka \'\'\'[[Media:$1|$1]]\'\'\' je bila vrnjena na [$4 različico $3, $2].</span>',
'filerevert-badversion'     => 'Ne najdem preteklih lokalnih verzij datoteke s podanim časovnim žigom.',

# File deletion
'filedelete'                  => 'Izbriši $1',
'filedelete-legend'           => 'Brisanje datoteke',
'filedelete-intro'            => "Brišete datoteko '''[[Media:$1|$1]]''' skupaj z njeno celotno zgodovino.",
'filedelete-intro-old'        => '<span class="plainlinks">Brišete različico datoteke \'\'\'[[Media:$1|$1]]\'\'\' [$4 $3, $2].</span>',
'filedelete-comment'          => 'Komentar:',
'filedelete-submit'           => 'Izbriši',
'filedelete-success'          => "Datoteka '''$1''' je bila izbrisana.",
'filedelete-success-old'      => "<span class=\"plainlinks\">Različica datoteke '''[[Media:\$1|\$1]]''', ''\$3, \$2'', je bila izbrisana.</span>",
'filedelete-nofile'           => "Datoteka '''$1''' ne obstaja na tej strani.",
'filedelete-nofile-old'       => "Arhivirana različica datoteke '''$1''' z določenimi vrednostmi ne obstaja.",
'filedelete-otherreason'      => 'Drug/dodaten razlog:',
'filedelete-reason-otherlist' => 'Drug razlog',
'filedelete-reason-dropdown'  => '* Pogosti razlogi brisanja
** kršitev avtorskih pravic
** neumnosti v besedilu
** podvojena datoteka
** potrjen predlog za brisanje',
'filedelete-edit-reasonlist'  => 'Uredi razloge za brisanje',

# MIME search
'mimesearch'         => 'Iskanje po MIME-tipu',
'mimesearch-summary' => 'Ta stran omogoča filtriranje datotek po njihovi vrsti MIME. Vnesite: vrstavsebine/podvrsta, npr. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-tip:',
'download'           => 'naloži',

# Unwatched pages
'unwatchedpages' => 'Nespremljane strani',

# List redirects
'listredirects' => 'Seznam preusmeritev',

# Unused templates
'unusedtemplates'     => 'Osirotele predloge',
'unusedtemplatestext' => 'Naslednji seznam podaja vse strani v imenskem prostoru {{GRAMMAR:rodilnik|{{ns:template}}}}, ki niso vključene v nobeno stran.
Preden jih izbrišete, preverite še druge povezave nanje.',
'unusedtemplateswlh'  => 'druge povezave',

# Random page
'randompage'         => 'Naključni članek',
'randompage-nopages' => 'Ni strani v imenskem prostoru »$1«.',

# Random redirect
'randomredirect'         => 'Naključna preusmeritev',
'randomredirect-nopages' => 'Ni preusmeritev v imenski prostor »$1«.',

# Statistics
'statistics'                   => 'Statistika',
'statistics-header-pages'      => 'Statistika strani',
'statistics-header-edits'      => 'Statistika urejanj',
'statistics-header-views'      => 'Statistika ogledov',
'statistics-header-users'      => 'Uporabniška statistika',
'statistics-header-hooks'      => 'Drugi statistični podatki',
'statistics-articles'          => 'Število člankov slovenske Wikipedije',
'statistics-pages'             => 'Število vseh strani na slovenski Wikipediji <small>(vštete so pogovorne strani, posebne strani slovenske Wikipedije, projektne strani, nanoškrbine, preusmeritve ipd.)</small>',
'statistics-pages-desc'        => 'Vse strani na wikiju, vključno z pogovornimi stranmi, preusmeritvam, itd.',
'statistics-files'             => 'Število naloženih datotek',
'statistics-edits'             => 'Število vseh urejanj na slovenski Wikipediji',
'statistics-edits-average'     => 'Povprečno število urejanj na stran',
'statistics-views-total'       => 'Vseh ogledov',
'statistics-views-peredit'     => 'Razmerje med ogledi in urejanji',
'statistics-jobqueue'          => 'Dolžina [http://www.mediawiki.org/wiki/Manual:Job_queue vrste opravil]',
'statistics-users'             => 'Registrirani [[Special:ListUsers|uporabniki]]',
'statistics-users-active'      => 'Aktivni uporabniki',
'statistics-users-active-desc' => 'Uporabniki, ki so izvajali dejanje v {{PLURAL:$1|zadnjem dnevu|zadnjih $1 dneh}}',
'statistics-mostpopular'       => 'Strani z največ ogledi',

'disambiguations'     => 'Razločitvene strani',
'disambiguationspage' => 'Template:Razločitev',

'doubleredirects'            => 'Dvojne preusmeritve',
'doubleredirectstext'        => '<b>Pozor:</b> seznam morda vsebuje neprave člane. To navadno pomeni, da pod prvim ukazom #REDIRECT obstaja dodatno besedilo s povezavami.<br />
Vsaka vrstica vsebuje povezave k prvi in drugi preusmeritvi ter prvo vrstico besedila druge preusmeritve. To navadno da pravi ciljni članek, h kateremu naj kaže prva preusmeritev.',
'double-redirect-fixed-move' => 'Stran [[$1]] je bil premaknjen. 
Sedaj je preusmeritev na [[$2]].',
'double-redirect-fixer'      => 'Popravljalec preusmeritev',

'brokenredirects'        => 'Pretrgane preusmeritve',
'brokenredirectstext'    => 'Naslednje preusmeritve kažejo na neobstoječe strani:',
'brokenredirects-edit'   => 'uredi',
'brokenredirects-delete' => 'izbriši',

'withoutinterwiki'        => 'Strani brez jezikovnih povezav',
'withoutinterwiki-legend' => 'Predpona',
'withoutinterwiki-submit' => 'Pokaži',

'fewestrevisions' => 'Strani z najmanj urejanji',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|zlog|zloga|zlogi|zlogov|zlogov}}',
'ncategories'             => '$1 {{PLURAL:$1|category|kategorij}}',
'nlinks'                  => '$1 {{PLURAL:$1|povezava|povezavi|povezave|povezav|povezav}}',
'nmembers'                => '$1 {{PLURAL:$1|element|elementa|elementi|elementov|elementov}}',
'nrevisions'              => '$1 {{PLURAL:$1|redakcija|redakciji|redakcije|redakcij|redakcij}}',
'nviews'                  => '$1 {{PLURAL:$1|ogled|ogleda|ogledi|ogledov|ogledov}}',
'specialpage-empty'       => 'Ta stran je prazna.',
'lonelypages'             => 'Osirotele strani',
'lonelypagestext'         => 'Naslednje strani niso povezane ali vključene v nobeno drugo stran na {{GRAMMAR:locative|{{SITENAME}}}}.',
'uncategorizedpages'      => 'Nekategorizirane strani',
'uncategorizedcategories' => 'Nekategorizirane kategorije',
'uncategorizedimages'     => 'Nekategorizirane slike',
'uncategorizedtemplates'  => 'Nekategorizirane predloge',
'unusedcategories'        => 'Osirotele kategorije',
'unusedimages'            => 'Osirotele datoteke',
'popularpages'            => 'Priljubljene strani',
'wantedcategories'        => 'Želene kategorije',
'wantedpages'             => 'Želene strani',
'wantedpages-badtitle'    => 'Neveljaven naslov v končnem nizu: $1',
'wantedfiles'             => 'Želene datoteke',
'wantedtemplates'         => 'Želene predloge',
'mostlinked'              => 'Strani, na katere se največ povezuje',
'mostlinkedcategories'    => 'Kategorije z največ elementi',
'mostlinkedtemplates'     => 'Najbolj uporabljene predloge',
'mostcategories'          => 'Članki z največ kategorijami',
'mostimages'              => 'Najbolj uporabljane datoteke',
'mostrevisions'           => 'Največkrat urejane strani',
'prefixindex'             => 'Iskanje po začetnih črkah',
'shortpages'              => 'Kratke strani',
'longpages'               => 'Dolge strani',
'deadendpages'            => 'Članki brez delujočih povezav',
'deadendpagestext'        => 'Spodaj navedene strani se ne povezujejo na druge članke v tem wikiju.',
'protectedpages'          => 'Zaščitene strani',
'protectedpages-cascade'  => 'Le kaskadne zaščite',
'protectedpagestext'      => 'Spodaj navedene strani so zaščitene pred urejanjem in ali prestavljanjem.',
'protectedtitles'         => 'Zaščiteni naslovi',
'protectedtitlestext'     => 'Naslednji naslovi so zaščiteni pred ustvarjanjem',
'protectedtitlesempty'    => 'Noben naslov ni trenutno zaščiten s temi parametri.',
'listusers'               => 'Seznam uporabnikov',
'listusers-editsonly'     => 'Pokaži samo uporabnike z urejanji',
'listusers-creationsort'  => 'Razvrsti po datumu ustvaritve',
'usercreated'             => 'Prispevano $1 ob $2',
'newpages'                => 'Nove strani',
'newpages-username'       => 'Uporabniško ime:',
'ancientpages'            => 'Najdlje nespremenjeni članki',
'move'                    => 'Prestavi',
'movethispage'            => 'Prestavi stran',
'unusedimagestext'        => '<p>Prosimo, upoštevajte, da lahko druge spletne strani datoteko uporabljajo neposredno z navedbo spletnega naslova. Zato so datoteke lahko navedene, čeprav se uporabljajo.</p>',
'unusedcategoriestext'    => 'Naslednje strani kategorij obstajajo, vendar jih ne uporablja noben članek ali druga kategorija.',
'notargettitle'           => 'Ni cilja',
'notargettext'            => 'Niste navedli ciljne strani ali uporabnika za izvedbo ukaza.',
'nopagetitle'             => 'Nobena takšna ciljna stran ne obstaja.',
'pager-newer-n'           => '{{PLURAL:$1|novejši 1|novejša 2|novejši $1|novejših $1}}',
'pager-older-n'           => '{{PLURAL:$1|starejši 1|starejša 2|starejši $1|starejših $1}}',

# Book sources
'booksources'               => 'Prepoznava ISBN-številk',
'booksources-search-legend' => 'Išči knjižne vire',
'booksources-go'            => 'Pojdi',
'booksources-text'          => 'Sledi seznam povezav do drugi spletnih strani, ki prodajajo nove in rabljene knjige, in imajo morda nadaljne informacije o knjigah, ki jih iščete:',
'booksources-invalid-isbn'  => 'Za dani ISBN se ne zdi, da je veljaven; preverite za morebitne napake pri kopiranju iz prvotnega vira.',

# Special:Log
'specialloguserlabel'  => 'Uporabnik:',
'speciallogtitlelabel' => 'Naslov:',
'log'                  => 'Dnevniki',
'all-logs-page'        => 'Vsi javni dnevniki',
'alllogstext'          => 'Združeno so prikazani dnevniki sprememb uporabniških pravic, preimenovanj uporabnikov, nalaganja predstavnostnih datotek, prestavljanja in zaščite strani, brisanja, registracij uporabnikov, sprememb položaja botov ter blokiranja in deblokiranja uporabnikov na strani {{SITENAME}}. Pogled lahko zožite z izbiro dnevnika, uporabniškega imena ali strani. Vedite, da polje »Uporabnik« razlikuje med malimi in velikimi črkami.',
'logempty'             => 'O tej strani ni v dnevniku ničesar.',
'log-title-wildcard'   => 'Iskanje po naslovih, začenši s tem besedilom',

# Special:AllPages
'allpages'          => 'Vse strani',
'alphaindexline'    => '$1 do $2',
'nextpage'          => 'Naslednja stran ($1)',
'prevpage'          => 'Prejšnja stran ($1)',
'allpagesfrom'      => 'Prikaži strani, ki se začnejo na:',
'allpagesto'        => 'Prikaži strani, ki se končajo na:',
'allarticles'       => 'Vsi članki',
'allinnamespace'    => 'Vse strani (imenski prostor $1)',
'allnotinnamespace' => 'Vse strani (brez imenskega prostora $1)',
'allpagesprev'      => 'Predhodna',
'allpagesnext'      => 'Naslednja',
'allpagessubmit'    => 'Pojdi',
'allpagesprefix'    => 'Prikaži strani z začetnimi črkami:',
'allpagesbadtitle'  => 'Podan naslov strani je neveljaven oz. ima predpono inter-jezik ali inter-wiki. Morda vsebuje enega ali več znakov, ki niso dovoljeni v naslovih.',
'allpages-bad-ns'   => '{{SITENAME}} nima imenskega prostora »$1«.',

# Special:Categories
'categories'                    => 'Kategorije',
'categoriespagetext'            => '{{PLURAL:$1|Naslednja{{#ifeq:$1|1||&nbsp;$1}} kategorija vsebuje|Naslednji{{#ifeq:$1|2||&nbsp;$1}} kategoriji vsebujeta|Naslednje $1 kategorije vsebujejo|Naslednjih $1 kategorij vsebuje}} strani ali datoteke.

[[Special:UnusedCategories|Neuporabljene kategorije]] niso prikazane.
Glej tudi [[Special:WantedCategories|želena kategorije]].',
'categoriesfrom'                => 'Prikaži kategorije, ki se začnejo na:',
'special-categories-sort-count' => 'razvrsti po številu',
'special-categories-sort-abc'   => 'razvrsti po abecedi',

# Special:DeletedContributions
'deletedcontributions'             => 'Izbrisani uporabnikovi prispevki',
'deletedcontributions-title'       => 'Izbrisani uporabnikovi prispevki',
'sp-deletedcontributions-contribs' => 'prispevki',

# Special:LinkSearch
'linksearch'       => 'Zunanje povezave',
'linksearch-pat'   => 'Iskalni vzorec:',
'linksearch-ns'    => 'Imenski prostor:',
'linksearch-ok'    => 'Išči',
'linksearch-line'  => '$1 povezano iz $2',
'linksearch-error' => 'Jokerji se lahko pojavijo le na začetku gostiteljskega imena.',

# Special:ListUsers
'listusersfrom'      => 'Prikaži uporabnike začenši z:',
'listusers-submit'   => 'Prikaži',
'listusers-noresult' => 'Ni najdenih uporabnikov.',
'listusers-blocked'  => '(blokiran)',

# Special:ActiveUsers
'activeusers'          => 'Seznam aktivnih uporabnikov',
'activeusers-count'    => '$1 {{PLURAL:$1|nedavno urejanje|nedavni urejanji|nedavna urejanja|nedavnih urejanj}}',
'activeusers-from'     => 'Prikaži uporabnike začenši z:',
'activeusers-noresult' => 'Noben uporabnik ni bil najden.',

# Special:Log/newusers
'newuserlogpage'              => 'Dnevnik registracij uporabnikov',
'newuserlogpagetext'          => 'Prikazan je dnevnik nedavnih registracij novih uporabnikov.',
'newuserlog-byemail'          => 'geslo je bilo poslano po e-pošti',
'newuserlog-create-entry'     => 'Nov uporabnik',
'newuserlog-create2-entry'    => 'ustvaritev računa »$1«',
'newuserlog-autocreate-entry' => 'Račun ustvarjen samodejno',

# Special:ListGroupRights
'listgrouprights'                      => 'Pravice uporabniških skupin',
'listgrouprights-group'                => 'Skupina',
'listgrouprights-rights'               => 'Pravice',
'listgrouprights-helppage'             => 'Help:Pravice skupin',
'listgrouprights-members'              => '(seznam članov)',
'listgrouprights-addgroup-all'         => 'Dodaj vse skupine',
'listgrouprights-removegroup-all'      => 'Odstrani vse skupine',
'listgrouprights-addgroup-self-all'    => 'Lastni račun dodaj v vse skupine',
'listgrouprights-removegroup-self-all' => 'Lastni račun odstrani iz vseh skupin',

# E-mail user
'mailnologin'      => 'Manjka naslov pošiljatelja',
'mailnologintext'  => "Za pošiljanje pošte se [[Special:UserLogin|prijavite]] in v [[Special:Preferences|nastavitvah]] vpišite veljaven '''overjen''' e-poštni naslov.",
'emailuser'        => 'Pošlji uporabniku e-pismo',
'emailpage'        => 'Pošlji uporabniku e-pismo',
'emailpagetext'    => "S spodnjim obrazcem lahko uporabniku pošljete e-poštno sporočilo.
Da bo prejemnik lahko odgovoril neposredno vam, bo v glavi sporočila zapisan '''vaš e-poštni naslov''' (kot ste ga vpisali v [[Special:Preferences|uporabniških nastavitvah]]).",
'usermailererror'  => 'Predmet e-pošte je vrnil napako:',
'defemailsubject'  => 'Elektronska pošta {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'noemailtitle'     => 'Ni e-poštnega naslova.',
'noemailtext'      => 'Uporabnik ni navedel veljavnega e-poštnega naslova ali pa elektronske pošte ne želi prejemati.',
'nowikiemailtitle' => 'E-pošta ni dovoljena',
'nowikiemailtext'  => 'Ta uporabnik se je odločil, da ne bo prejmal e-pošte od drugih uporabnikov.',
'email-legend'     => 'Pošlji e-pošto drugemu uporabniku {{GRAMMAR:akuzativ|{{SITENAME}}}}',
'emailfrom'        => 'Od:',
'emailto'          => 'Za:',
'emailsubject'     => 'Predmet:',
'emailmessage'     => 'Sporočilo:',
'emailsend'        => 'Pošlji',
'emailccme'        => 'Po elektronski pošti mi pošlji kopijo mojega sporočila.',
'emailccsubject'   => 'Kopija tvojega sporočila iz $1: $2',
'emailsent'        => 'E-pismo je poslano!',
'emailsenttext'    => 'E-pismo je poslano.',

# Watchlist
'watchlist'            => 'Spisek nadzorov',
'mywatchlist'          => 'Spisek nadzorov',
'watchlistfor'         => "(za '''$1''')",
'nowatchlist'          => 'Vaš spisek nadzorov je prazen.',
'watchlistanontext'    => 'Prosimo, $1 za pregled ali urejanje vsebine vašega spiska nadzorov.',
'watchnologin'         => 'Niste prijavljeni',
'watchnologintext'     => 'Za urejanje spiska nadzorov se [[Special:UserLogin|prijavite]].',
'addedwatch'           => 'Dodano na spisek nadzorov',
'addedwatchtext'       => "Stran »'''<nowiki>$1</nowiki>'''« je bila dodana na vaš [[Special:Watchlist|spisek nadzorov]], kjer bodo odslej navedene njene morebitne spremembe in spremembe pripadajoče pogovorne strani. Za lažjo izbiro bodo tudi v [[Special:RecentChanges|seznamu zadnjih sprememb]] prikazane <b>krepko</b>. Če jo želite odstraniti s spiska, kliknite zavihek »Prenehaj opazovati«.",
'removedwatch'         => 'Odstranjena s spiska nadzorov',
'removedwatchtext'     => 'Stran »<nowiki>$1</nowiki>« je odstranjena z vašega spiska nadzorov.',
'watch'                => 'Opazuj',
'watchthispage'        => 'Opazuj stran',
'unwatch'              => 'Prenehaj opazovati',
'unwatchthispage'      => 'Prenehaj opazovati stran',
'notanarticle'         => 'Ni članek',
'notvisiblerev'        => 'Redakcija je bila izbrisana',
'watchnochange'        => 'V prikazanem časovnem obdobju se ni spremenila nobena med nadzorovanimi stranmi.',
'watchlist-details'    => 'Spremljate $1 {{PLURAL:$1|stran|strani|strani|strani|strani}} (pogovorne strani niso vštete).',
'wlheader-enotif'      => '* Obveščanje po elektronski pošti je omogočeno.',
'wlheader-showupdated' => "* Od vašega zadnjega ogleda spremenjene strani so prikazanje '''krepko'''.",
'watchmethod-recent'   => 'med nedavnimi urejanji iščem spremljane strani',
'watchmethod-list'     => 'med spremljanimi stranmi iščem nedavna urejanja',
'watchlistcontains'    => 'Spremljate $1 {{PLURAL:$1|stran|strani|strani|strani|strani}}.',
'iteminvalidname'      => "Težava z izbiro '$1', neveljavno ime ...",
'wlnote'               => 'Navedenih je {{PLURAL:$1|zadnja|zadnji|zadnje|zadnjih|zadnjih}} $1 {{PLURAL:$1|sprememba|spremembi|spremembe|sprememb}} v {{PLURAL:$2|zadnji|zadnjih|zadnjih|zadnjih|zadnjih}} <b>$2</b> {{PLURAL:$2|uri|urah|urah|urah|urah}}.',
'wlshowlast'           => 'Prikaži zadnjih $1 ur; $2 dni; $3;',
'watchlist-options'    => 'Možnosti spiska nadzorov',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Nadziranje ...',
'unwatching' => 'Nenadziranje ...',

'enotif_mailer'                => '{{SITENAME}} - obvestilni poštar',
'enotif_reset'                 => 'Označi vse strani kot prebrane',
'enotif_newpagetext'           => 'To je nova stran.',
'enotif_impersonal_salutation' => 'Uporabnik {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'changed'                      => 'spremenjena',
'created'                      => 'ustvarjena',
'enotif_subject'               => 'Stran {{GRAMMAR:rodilnik|{{SITENAME}}}} $PAGETITLE je $CHANGEDORCREATED $PAGEEDITOR',
'enotif_lastvisited'           => 'Za spremembe po vašem zadnjem obisku glejte $1.',
'enotif_lastdiff'              => 'Glej $1 za to spremembo.',
'enotif_anon_editor'           => 'anonimni uporabnik $1',
'enotif_body'                  => '$WATCHINGUSERNAME,

stran v {{GRAMMAR:dajalnik|{{SITENAME}}}} $PAGETITLE je bila $PAGEEDITDATE $CHANGEDORCREATED s strani $PAGEEDITOR,
za trenutno redakcijo glejte $PAGETITLE_URL

$NEWPAGE

Urejevalčev povzetek: $PAGESUMMARY $PAGEMINOREDIT

Navežite stik z urejevalcem:
e-pošta $PAGEEDITOR_EMAIL
wiki $PAGEEDITOR_WIKI

Nadaljnjih obvestil do obiska strani ne boste prejemali. Na spisku nadzorov lahko znova nastavite zastavice obveščanj za vse spremljane strani.

             Vaš opozorilni sistem slovenskega {{GRAMMAR:rodilnik|{{SITENAME}}}}

--
Za spremembo nastavitev spiska nadzorov obiščite
{{fullurl:Special:Watchlist/edit}}

Povratna sporočila in pomoč:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Briši stran',
'confirm'                => 'Potrdi',
'excontent'              => "vsebina: '$1'",
'excontentauthor'        => "vsebina: '$1' (edini urejevalec pa '$2')",
'exbeforeblank'          => "vsebina pred brisanjem: '$1'",
'exblank'                => 'prazna stran',
'delete-confirm'         => 'Brisanje »$1«',
'delete-legend'          => 'Izbriši',
'historywarning'         => 'OPOZORILO: stran, ki jo želite izbrisati, ima zgodovino:',
'confirmdeletetext'      => "Iz zbirke podatkov boste izbrisali stran ali sliko skupaj z vso njeno zgodovino. 
Prosimo, '''potrdite''', da to resnično želite, da razumete posledice dejanja in da se ravnate po [[{{MediaWiki:Policy-url}}|pravilih]].",
'actioncomplete'         => 'Poseg je končan',
'actionfailed'           => 'Dejanje spodletelo',
'deletedtext'            => "»'''<nowiki>$1</nowiki>'''« je bila izbrisana. 
Za zapise nedavnih brisanj glej $2.",
'deletedarticle'         => 'je izbrisal(-a) »$1«',
'dellogpage'             => 'Dnevnik brisanja',
'dellogpagetext'         => 'Prikazan je seznam nedavnih brisanj z navedenim strežniškim časom.',
'deletionlog'            => 'dnevnik brisanja',
'reverted'               => 'Obnova prejšnje redakcije',
'deletecomment'          => 'Razlog za brisanje',
'deleteotherreason'      => 'Drugi/dodatni razlogi:',
'deletereasonotherlist'  => 'Drug razlog',
'deletereason-dropdown'  => '* Pogosti razlogi za brisanje
** zahteva avtorja
** kršitev avtorskih pravic
** vandalizem',
'delete-edit-reasonlist' => 'Uredi razloge za brisanje',

# Rollback
'rollback'         => 'Vrni spremembe',
'rollback_short'   => 'Vrni',
'rollbacklink'     => 'vrni',
'rollbackfailed'   => 'Vrnitev ni uspela.',
'cantrollback'     => 'Urejanja ne morem vrniti; zadnji urejevalec je hkrati edini.',
'alreadyrolled'    => 'Ne morem vrniti zadnje spremembe [[:$1]] uporabnika [[User:$2|$2]] ([[User talk:$2|Pogovor]]); nekdo drug je že spremenil ali vrnil članek.

Zadnja sprememba od uporabnika [[User:$3|$3]] ([[User talk:$3|Pogovor]]).',
'editcomment'      => "Pripomba k spremembi: »''$1''«.",
'revertpage'       => 'vrnitev sprememb uporabnika »[[Special:Contributions/$2|$2]]« ([[User talk:$2|pogovor]]) na zadnje urejanje uporabnika »$1«',
'rollback-success' => 'Razveljavljene spremembe uporabnika $1; vrnjeno na urejanje uporabnika $2.',
'sessionfailure'   => 'Vaša prijava ni uspela; da bi preprečili ugrabitev seje, je bilo dejanje preklicano. Prosimo, izberite »Nazaj« in ponovno naložite stran, s katere prihajate, nato poskusite znova.',

# Protect
'protectlogpage'              => 'Dnevnik zaščit strani',
'protectlogtext'              => 'Prikazan je seznam zaščit in odstranitev zaščit strani. Za več podatkov glejte [[Project:Zaščitena stran]] in [[Project:Pravila zaščite]]. Vedite, da polje »Uporabnik« razlikuje med malimi in velikimi črkami.',
'protectedarticle'            => 'Zaščita strani "[[$1]]"',
'modifiedarticleprotection'   => 'stopnja zaščite spremenjena za »[[$1]]«',
'unprotectedarticle'          => 'Zaščita strani $1 je odstranjena.',
'protect-title'               => 'Zaščita strani »$1«',
'prot_1movedto2'              => '- prestavitev [[$1]] na [[$2]]',
'protect-legend'              => 'Potrdite zaščito',
'protectcomment'              => 'Razlog:',
'protectexpiry'               => 'Poteče:',
'protect_expiry_invalid'      => 'Čas izteka je neveljaven.',
'protect_expiry_old'          => 'Čas izteka je v preteklosti.',
'protect-unchain'             => 'Deblokiraj dovoljenja za premikanje',
'protect-text'                => "Tu si lahko ogledate in spremenite raven zaščitenosti strani '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Vaš uporabniški račun nima dovoljenja za spreminjanje stopnje zaščite strani. 
Trenutne nastavitve za stran '''$1''' so:",
'protect-cascadeon'           => 'Ta stran je trenutno uaščitena, ker je vključena v {{PLURAL:$1|naslednjo stran, ki ima|naslednji strani, ki imata|naslednje strani, ki imajo|naslednjih strani, ki imajo}} vključeno kaskadno zaščito. 
Stopnjo zaščite te strani lahko spremenite, vendar to ne bo vplivalo na kaskadno zaščito.',
'protect-default'             => 'Omogoči urejanje vsem uporabnikom',
'protect-fallback'            => 'Potrebujete pravice »$1«',
'protect-level-autoconfirmed' => 'Blokiraj nove in neregistrirane uporabnike',
'protect-level-sysop'         => 'Blokiraj vse uporabnike (razen administratorjev)',
'protect-summary-cascade'     => 'kaskadno',
'protect-expiring'            => 'poteče $1 (UTC)',
'protect-expiry-indefinite'   => 'nedoločeno',
'protect-cascade'             => 'Zaščiti strani, ki so vključene v to stran (kaskadna zaščita)',
'protect-cantedit'            => 'Ne morete spreminjati stopnje zaščite te strani, ker nimate dovoljenja za njeno urejanje.',
'protect-othertime'           => 'Drugačen čas:',
'protect-othertime-op'        => 'drugačen čas',
'protect-otherreason'         => 'Drug/dodaten razlog:',
'protect-otherreason-op'      => 'drug/dodaten razlog',
'protect-dropdown'            => '*Pogosti razlogi za zaščito
** Prekomeren vandalizem
** Vztrajno dodajanje reklamnih povezav
** Neproduktivne urejevalske vojne
** Zelo obiskana stran',
'protect-edit-reasonlist'     => 'Uredi razloge zaščite',
'protect-expiry-options'      => '1 uro:1 hour,1 dan:1 day,1 teden:1 week,2 tedna:2 weeks,1 mesec:1 month,3 mesece:3 months,6 mesecev:6 months,1 leto:1 year,neomejeno dolgo:infinite',
'restriction-type'            => 'Dovoljenje:',
'restriction-level'           => 'Stopnja zaščite:',
'minimum-size'                => 'Min. velikost',
'maximum-size'                => 'Maks. velikost',
'pagesize'                    => '(bitov)',

# Restrictions (nouns)
'restriction-edit'   => 'Urejanje',
'restriction-move'   => 'Prestavljanje',
'restriction-create' => 'Ustvari',
'restriction-upload' => 'Naloži',

# Restriction levels
'restriction-level-sysop'         => 'popolna zaščita',
'restriction-level-autoconfirmed' => 'delno zaščiteno',
'restriction-level-all'           => 'katera koli raven',

# Undelete
'undelete'                  => 'Obnovi izbrisano stran',
'undeletepage'              => 'Prikaži izbrisane strani in jih obnovi',
'viewdeletedpage'           => 'Pregled izbrisanih strani',
'undeletepagetext'          => '{{PLURAL:$1|Naslednja stran je bila izbrisana, vendar je še vedno v arhivu in jo lahko obnovite.|Naslednji $1 strani sta bili izbrisani, vendar sta še vedno v arhivu in ju lahko obnovite.|Naslednje $1 strani so bile izbrisane, vendar so še vedno v arhivu in jih lahko obnovite.|Naslednjih $1 strani je bilo izbrisanih, vendar so še vedno v arhivu in jih lahko obnovite.|V arhivu ni več nobene izbrisane strani.}} Arhiv je treba občasno počistiti.',
'undelete-fieldset-title'   => 'Obnovi redakcije',
'undeleteextrahelp'         => "Da bi obnovili celotno stran z vso njeno zgodovino, pustite vsa potrditvena polja prazna in kliknite '''''Obnovi'''''.
Če želite obnoviti le določene redakcije strani, pred klikom gumba '''''Obnovi''''' označite ustrezna potrditvena polja.
Klik gumba '''''Ponastavi''''' bo izpraznil polje za vnos razloga in vsa potrditvena polja.",
'undeleterevisions'         => '{{PLURAL:$1|Arhivirana je|Arhivirani sta|Arhivirane so|Arhiviranih je|Arhiviranih ni}} $1 {{PLURAL:$1|redakcija|redakciji|redakcije|redakcij|redakcij}}',
'undeletehistory'           => 'Z obnovo strani se bodo po privzetem obnovile tudi vse pripadajoče redakcije. Če se želite temu izogniti, označite le želene.

Kjer je bila po brisanju ustvarjena nova stran s tem imenom, se trenutna redakcija obstoječe strani ne bo samodejno zamenjala, temveč se bodo obnovljene redakcije pojavile v prejšnji zgodovini. Pazite, da se temu izognete, razen seveda, kadar resnično nameravate združiti zgodovini obeh strani.',
'undeletehistorynoadmin'    => 'Stran je izbrisana. Razlog za izbris je skupaj s podrobnostmi o uporabnikih, ki so jo urejali pred izbrisom, naveden v prikazanem povzetku. Dejansko besedilo izbrisanih redakcij je dostopno le administratorjem.',
'undeleterevision-missing'  => 'Napačna ali manjkajoča redakcija. Imate lahko napačno povezavo ali pa je bila redakcija obnovljena ali odstranjena iz arhiva.',
'undelete-nodiff'           => 'Predhodnih različic ne najdem.',
'undeletebtn'               => 'Obnovi',
'undeletelink'              => 'poglej/obnovi',
'undeleteviewlink'          => 'ogled',
'undeletereset'             => 'Ponastavi',
'undeleteinvert'            => 'Obrni izbor',
'undeletecomment'           => 'Razlog:',
'undeletedarticle'          => 'je obnovil(-a) »$1«',
'undeletedrevisions'        => 'obnovljeno: $1 {{PLURAL:$1|redakcija|redakciji|redakcije|redakcij|redakcij}}',
'undeletedfiles'            => 'Obnovili ste $1 {{PLURAL:datoteka|datoteki|datoteke|datotek|datotek}}',
'cannotundelete'            => 'Obnova ni uspela. Morda je stran obnovil že kdo drug.',
'undeletedpage'             => "<big>'''Obnovili ste stran '$1'.'''</big>

Nedavna brisanja in obnove so zapisani v [[Special:Log/delete|dnevniku brisanja]].",
'undelete-header'           => 'Glej [[Special:Log/delete|dnevnik brisanja]] za nedavno izbrisane strani.',
'undelete-search-box'       => 'Išči izbrisane strani',
'undelete-search-prefix'    => 'Prikaži strani, ki se začnejo na:',
'undelete-search-submit'    => 'Iskanje',
'undelete-show-file-submit' => 'Da',

# Namespace form on various pages
'namespace'      => 'Imenski prostor:',
'invert'         => 'Obrni izbor',
'blanknamespace' => '(Osnovno)',

# Contributions
'contributions'       => 'Uporabnikovi prispevki',
'contributions-title' => 'Prispevki uporabnika $1',
'mycontris'           => 'Prispevki',
'contribsub2'         => 'Uporabnik: $1 ($2)',
'nocontribs'          => 'Ne najdem nobene merilom ustrezajoče spremembe.',
'uctop'               => ' (vrh)',
'month'               => 'Od meseca (in prej):',
'year'                => 'Od leta (in prej):',

'sp-contributions-newbies'     => 'Prikaži samo prispevke novih računov',
'sp-contributions-newbies-sub' => 'Prispevki novincev',
'sp-contributions-blocklog'    => 'Dnevnik blokiranja',
'sp-contributions-deleted'     => 'Izbrisani uporabnikovi prispevki',
'sp-contributions-logs'        => 'dnevniki',
'sp-contributions-talk'        => 'pogovor',
'sp-contributions-userrights'  => 'upravljanje s pravicami uporabnikov',
'sp-contributions-search'      => 'Išči prispevke',
'sp-contributions-username'    => 'IP-naslov ali uporabniško ime:',
'sp-contributions-submit'      => 'Išči',

# What links here
'whatlinkshere'            => 'Kaj se povezuje sem',
'whatlinkshere-title'      => 'Strani, ki se povezujejo na $1',
'whatlinkshere-page'       => 'Stran:',
'linkshere'                => '[[:$1|Sem]] kažejo naslednje strani:',
'nolinkshere'              => '[[:$1|Sem]] ne kaže nobena stran.',
'nolinkshere-ns'           => "Nobena stran se ne povezuje na '''[[:$1]]''' v izbranem imenskem prostoru.",
'isredirect'               => 'preusmeritvena stran',
'istemplate'               => 'vključitev',
'isimage'                  => 'povezava na sliko',
'whatlinkshere-prev'       => '{{PLURAL:$1|prejšnji|prejšnja $1|prejšnji $1|prejšnjih $1|prejšnjih $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|naslednji|naslednja $1|naslednji $1|naslednjih $1|naslednjih $1}}',
'whatlinkshere-links'      => '← povezave',
'whatlinkshere-hideredirs' => '$1 preusmeritve',
'whatlinkshere-hidetrans'  => '$1 translukcije',
'whatlinkshere-hidelinks'  => '$1 povezave',
'whatlinkshere-filters'    => 'Filtri',

# Block/unblock
'blockip'                         => 'Blokiranje IP-naslova ali uporabniškega imena',
'blockip-legend'                  => 'Blokiraj uporabnika',
'blockiptext'                     => "Naslednji obrazec vam omogoča, da določenemu IP-naslovu ali uporabniškemu imenu preprečite urejanje. To storimo le zaradi zaščite pred nepotrebnim uničevanjem in po [[{{MediaWiki:Policy-url}}|pravilih]]. Vnesite tudi razlog, ''na primer'' seznam strani, ki jih je uporabnik po nepotrebnem kvaril.",
'ipaddress'                       => 'IP-naslov',
'ipadressorusername'              => 'IP-naslov ali uporabniško ime',
'ipbexpiry'                       => 'Pretek',
'ipbreason'                       => 'Razlog',
'ipbreasonotherlist'              => 'Drug razlog',
'ipbreason-dropdown'              => '*Razlogi za blokado
** vandalizem
** dodajanje napačnih/lažnih podatkov
** brisanje strani
** dodajanje nepotrebnih zunanjih povezav
** dodajanje neumnosti v besedilo
** nadlegovanje/osebni napadi
** neprimerno uporabniško ime',
'ipbanononly'                     => 'Blokiraj le brezimne uporabnike',
'ipbcreateaccount'                => 'Prepreči ustvarjanje računov',
'ipbemailban'                     => 'uporabniku prepreči pošiljanje e-pošte',
'ipbenableautoblock'              => 'Avtomatično blokiraj zadnji IP naslov tega uporabnika in vse naslednje naslove, ki jih bodo hoteli uporabiti',
'ipbsubmit'                       => 'Blokiraj naslov',
'ipbother'                        => 'Drugačen čas',
'ipboptions'                      => '2 ure:2 hours,1 dan:1 day,3 dni:3 days,1 teden:1 week,2 tedna:2 weeks,1 mesec:1 month,3 mesece:3 months,6 mesecev:6 months,1 leto:1 year,neomejeno dolgo:infinite',
'ipbotheroption'                  => 'drugo',
'ipbotherreason'                  => 'Drug/dodaten razlog:',
'ipbhidename'                     => 'Skrij uporabniško ime iz urejanja in seznamov',
'ipbwatchuser'                    => 'Nadzoruj uporabnikovo uporabniško in pogovorno stran',
'badipaddress'                    => 'Neveljaven IP-naslov ali uporabniško ime.',
'blockipsuccesssub'               => 'Blokiranje je uspelo',
'blockipsuccesstext'              => 'IP-naslov ali uporabniški račun »[[Special:Contributions/$1|$1]]« je blokiran.<br />
Preglejte [[Special:IPBlockList|seznam blokiranih IP-naslovov]].',
'ipb-edit-dropdown'               => 'Uredi razloge blokade',
'ipb-unblock-addr'                => 'Deblokiraj $1',
'unblockip'                       => 'Omogočite urejanje IP-naslovu',
'unblockiptext'                   => 'Z naslednjim obrazcem lahko obnovite možnost urejanja z blokiranega IP-naslova ali uporabniškega računa.',
'ipusubmit'                       => 'Deblokiraj naslov',
'ipblocklist'                     => 'Seznam blokiranih IP-naslovov in uporabniških imen',
'ipblocklist-submit'              => 'Išči',
'blocklistline'                   => '$1, $2 je blokiral(-a) $3 ($4)',
'infiniteblock'                   => 'neomejen čas',
'expiringblock'                   => 'preteče $1 $2',
'anononlyblock'                   => 'samo brezim.',
'noautoblockblock'                => 'Avtomatska blokada je onemogočena',
'createaccountblock'              => 'ustvarjanje računov onemogočeno',
'emailblock'                      => 'e-pošta blokirana',
'blocklink'                       => 'blokiraj',
'unblocklink'                     => 'deblokiraj',
'change-blocklink'                => 'spremeni blokado',
'contribslink'                    => 'prispevki',
'autoblocker'                     => 'Ker si delite IP-naslov z »$1«, vam je urejanje samodejno onemogočeno. Razlog: »$2«.',
'blocklogpage'                    => 'Dnevnik blokiranja',
'blocklogentry'                   => 'uporabnika »$1« sem blokiral(-a) za $2 zaradi $3',
'blocklogtext'                    => 'Prikazan je dnevnik blokiranja in deblokiranja uporabnikov. Samodejno blokirani IP-naslovi niso navedeni. Trenutno veljavna blokiranja so navedena na [[Special:IPBlockList|seznamu blokiranih IP-naslovov]].',
'unblocklogentry'                 => 'je deblokiral(-a) »$1«',
'block-log-flags-anononly'        => 'samo za brezimne uporabnike',
'block-log-flags-nocreate'        => 'ustvarjanje uporabniških računov onemogočeno',
'block-log-flags-noautoblock'     => 'samodejno blokiranje onemogočeno',
'block-log-flags-noemail'         => 'e-naslov blokiran',
'block-log-flags-nousertalk'      => 'prepreči urejanje lastne pogovorne strani',
'block-log-flags-angry-autoblock' => 'okrepljeno avtoblokada omogočena',
'block-log-flags-hiddenname'      => 'uporabniško ime skrito',
'range_block_disabled'            => 'Možnost administratorjev za blokiranje urejanja IP-razponom je onemogočena.',
'ipb_expiry_invalid'              => 'Neveljaven čas preteka',
'ipb_already_blocked'             => '"$1" je že blokiran',
'ipb-needreblock'                 => '== Uporeabnik je že blokiran ==
$1 je že blokiran.
Ali želite spremeniti nastavitve blokade?',
'ipb_cant_unblock'                => 'Napaka: blokade št. $1 ni moč najti. Morda je bila že odstranjena.',
'ip_range_invalid'                => 'Neveljaven IP-razpon.',
'blockme'                         => 'Blokiraj me',
'proxyblocker'                    => 'Blokator posredniških strežnikov',
'proxyblocker-disabled'           => 'Ta funkcija je onemogočena.',
'proxyblockreason'                => 'Ker uporabljate odprti posredniški strežnik, je urejanje z vašega IP-naslova preprečeno. Gre za resno varnostno težavo, o kateri obvestite svojega internetnega ponudnika.',
'proxyblocksuccess'               => 'Storjeno.',
'sorbsreason'                     => 'Vaš IP-naslov je v DNSBL uvrščen med odprte posredniške strežnike.',
'sorbs_create_account_reason'     => 'Vaš IP-naslov je v DNSBL naveden kot odprti posredniški strežnik. Računa zato žal ne morete ustvariti.',
'cant-block-while-blocked'        => 'Ne morete blokirati drugih uporabnikove, medtem ko ste sami blokirani.',

# Developer tools
'lockdb'              => 'Zakleni zbirko podatkov',
'unlockdb'            => 'Odkleni zbirko podatkov',
'lockdbtext'          => 'Zaklenitev zbirke podatkov bo vsem uporabnikom preprečila možnost urejanja strani, spreminjanja nastavitev, urejanja spiska nadzorov in drugih stvari, ki zahtevajo spremembe zbirke podatkov. Prosimo, potrdite, da jo resnično želite zakleniti in da jo boste po končanem vzdrževanju spet odklenili.',
'unlockdbtext'        => 'Odklenitev zbirke podatkov bo vsem uporabnikom obnovila možnost urejanja strani, spreminjanja nastavitev, urejanja seznamov nadzorov in drugih stvari, ki zahtevajo spremembe zbirke. Prosimo, potrdite nedvomni namen.',
'lockconfirm'         => 'Da, zbirko podatkov želim zakleniti.',
'unlockconfirm'       => 'Da, zbirko podatkov želim odkleniti.',
'lockbtn'             => 'Zakleni zbirko podatkov',
'unlockbtn'           => 'Odkleni zbirko podatkov',
'locknoconfirm'       => 'Namere niste potrdili.',
'lockdbsuccesssub'    => 'Zbirko podatkov ste uspešno zaklenili',
'unlockdbsuccesssub'  => 'Zbirka podatkov je odklenjena',
'lockdbsuccesstext'   => 'Podatkovna baza {{GRAMMAR:rodilnik|{{SITENAME}}}} je bila zaklenjena.
<br />Ne pozabite odkleniti, ko boste končali z vzdrževanjem.',
'unlockdbsuccesstext' => 'Zbirka podatkov {{GRAMMAR:rodilnik|{{SITENAME}}}} je spet odklenjena.',
'databasenotlocked'   => 'Zbirka podatkov ni zaklenjena.',

# Move page
'move-page'               => 'Prestavi $1',
'move-page-legend'        => 'Prestavitev strani',
'movepagetext'            => "Z naslednjim obrazcem lahko stran preimenujete in hkrati prestavite tudi vso njeno zgodovino. Dosedanja stran se bo spremenila v preusmeritev na prihodnje mesto. 

'''Povezave na dosedanji naslov strani se ne bodo spremenile, zato vas prosimo, da po prestavitvi strani z uporabo pripomočka »Kaj se povezuje sem« popravite vse dvojne preusmeritve, ki bodo morda nastale.''' Odgovorni ste, da bodo povezave še naprej kazale na prava mesta.

Kjer stran z izbranim novim imenom že obstaja, dejanje '''ne''' bo izvedeno, razen če je sedanja stran prazna ali preusmeritvena in brez zgodovine urejanj. To pomeni, da lahko, če se zmotite, strani vrnete prvotno ime, ne morete pa prepisati že obstoječe strani.

<b>OPOZORILO!</b>
Prestavitev strani je lahko za priljubljeno stran velika in nepričakovana sprememba, zato pred izbiro ukaza dobro premislite.",
'movepagetalktext'        => "Če obstaja, bo samodejno prestavljena tudi pripadajoča pogovorna stran, '''razen kadar'''
*stran prestavljate prek imenskih prostorov,
*pod novim imenom že obstaja neprazna pogovorna stran ali
*ste odkljukali spodnji okvirček.

Če je tako, boste morali pogovorno stran, če želite, prestaviti ali povezati ročno. Če tega ne morete storiti, predlagajte prestavitev na strani [[Project:Želene prestavitve]], vsekakor pa tega '''''ne''''' počnite s preprostim izrezanjem in prilepljenjem vsebine, saj bi tako pokvarili zgodovino urejanja strani.",
'movearticle'             => 'Prestavi stran',
'movenologin'             => 'Niste prijavljeni',
'movenologintext'         => 'Za prestavljanje strani morate biti registrirani in [[Special:UserLogin|prijavljeni]].',
'newtitle'                => 'Na naslov',
'move-watch'              => 'Opazuj to stran',
'movepagebtn'             => 'Prestavi stran',
'pagemovedsub'            => 'Uspešno prestavljeno',
'movepage-moved'          => "<big>Stran '''»$1«''' je prestavljena na naslov '''»$2«'''.</big>",
'articleexists'           => 'Izbrano ime je že zasedeno ali pa ni veljavno. 
Prosimo izberite drugo ciljno ime.',
'cantmove-titleprotected' => 'Strani ne morete premakniti na slednjo lokacijo, saj je nov naslov zaščiten pred ustvarjanjem',
'talkexists'              => "'''Sama stran je bila uspešno prestavljena, pripadajoča pogovorna stran pa ne, ker že obstaja na novem naslovu. Prosimo, združite ju ročno. Če tega ne morete storiti, prosite za pomoč katerega izmed administratorjev, nikakor pa tega NE počnite z izrezanjem in prilepljenjem vsebine.'''",
'movedto'                 => 'prestavljeno na',
'movetalk'                => 'Če je mogoče, prestavi tudi pogovorno stran.',
'1movedto2'               => '- prestavitev [[$1]] na [[$2]]',
'1movedto2_redir'         => '- prestavitev [[$1]] na [[$2]] čez preusmeritev',
'movelogpage'             => 'Dnevnik prestavljanja strani',
'movelogpagetext'         => 'Prikazujem seznam prestavljenih strani.',
'movenosubpage'           => 'Ta stran nima podstrani.',
'movereason'              => 'Razlog',
'revertmove'              => 'vrni',
'delete_and_move'         => 'Briši in prestavi',
'delete_and_move_text'    => '==Treba bi bilo brisati==

Ciljna stran »[[:$1]]« že obstaja. Ali jo želite, da bi pripravili prostor za prestavitev, izbrisati?',
'delete_and_move_confirm' => 'Da, izbriši stran',
'delete_and_move_reason'  => 'Izbrisano z namenom pripraviti prostor za prestavitev.',
'selfmove'                => "'''Naslova vira in cilja sta enaka; stran ni mogoče prestaviti samo vase.''' Prosimo, preverite, ali niste naslova cilja namesto v polje »Na naslov« vpisali v polje »Razlog«.",
'immobile-source-page'    => 'Te strani ni mogoče prestaviti.',
'imagenocrossnamespace'   => 'Ne morem premakniti datoteke izven imenskega prostora datotek',

# Export
'export'            => 'Izvoz strani',
'exporttext'        => "Besedilo in urejevalno zgodovino ene ali več strani lahko izvozite v obliki XML. V prihodnosti bo to vsebino morda mogoče izvoziti v drug wiki, ki ga bo poganjalo programje MediaWiki, v trenutni različici pa so možnosti za to zelo omejene (kjer je omogočeno orodje ''Special:Import'', lahko vsebino z njegovo uporabo uvozijo administratorji).

Če želite izvoziti članke, v spodnje polje vpišite njihove naslove (enega v vsako vrstico) in označite, ali želite le trenutno različico s podatki o trenutnem urejanju ali tudi vse prejšnje z vrsticami o zgodovini strani.

Če gre za slednje, lahko uporabite tudi povezavo, npr. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] za 
članek \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => 'Vključi le trenutno redakcijo, ne pa celotne zgodovine.',
'exportnohistory'   => "----
'''Opomba:''' izvoz celotne zgodovine strani je do nadaljnjega zaradi preobremenjenosti strežnikov onemogočen. Popolno zgodovino lahko najdete v [http://download.wikimedia.org/ izmetih zbirke podatkov] — administratorji strežnikov Wikimedije.",
'export-submit'     => 'Izvozi',
'export-addcattext' => 'Dodaj strani iz kategorije:',
'export-addcat'     => 'Dodaj',
'export-addnstext'  => 'Dodaj strani iz imenskega prostora:',
'export-addns'      => 'Dodaj',
'export-download'   => 'Shrani kot datoteko',
'export-templates'  => 'Vključi predloge',
'export-pagelinks'  => 'Vključi povezane strani do globine:',

# Namespace 8 related
'allmessages'                   => 'Sistemska sporočila',
'allmessagesname'               => 'Ime',
'allmessagesdefault'            => 'Prednastavljeno besedilo',
'allmessagescurrent'            => 'Trenutno besedilo',
'allmessagestext'               => 'Navedena so v imenskem prostoru MediaWiki dostopna sistemska sporočila.
Za lokalizacijo in prevajanje obiščite [http://www.mediawiki.org/wiki/Localisation MediaWiki] in [http://translatewiki.net translatewiki.net] in tako prispevajte k splošnem prevodu programja.',
'allmessagesnotsupportedDB'     => "Te strani ni mogoče uporabljati, ker je bilo '''\$wgUseDatabaseMessages''' izključeno.",
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter'            => 'Filtriraj po prilagoditvenem stanju:',
'allmessages-filter-unmodified' => 'Nespremenjen',
'allmessages-filter-all'        => 'Vse',
'allmessages-filter-modified'   => 'Spremenjeno',
'allmessages-prefix'            => 'Filtriraj po predponi:',
'allmessages-language'          => 'Jezik:',
'allmessages-filter-submit'     => 'Pojdi',

# Thumbnails
'thumbnail-more'           => 'Povečaj',
'filemissing'              => 'Datoteka manjka',
'thumbnail_error'          => 'Napaka pri izdelavi sličice: $1',
'thumbnail_invalid_params' => 'Neveljavni parametri za sličico',

# Special:Import
'import'                     => 'Uvoz strani',
'importinterwiki'            => 'Transwikiuvoz',
'import-interwiki-history'   => 'Kopiraj vse dosedanje redakcije te strani',
'import-interwiki-submit'    => 'Uvozi',
'import-interwiki-namespace' => 'Prenesi strani v imenski prostor:',
'importtext'                 => 'Z uporabo orodja Special:Export izvozite datoteko iz izvornega wikija, shranite jo na disk in naložite tu.',
'importstart'                => 'Uvažam strani ...',
'importfailed'               => 'Uvoz ni uspel: $1',
'importcantopen'             => 'Neuspešno odpiranje uvožene datoteke',
'importbadinterwiki'         => 'Slaba jezikovna povezava',
'importnotext'               => 'Prazno ali brez besedila',
'importsuccess'              => 'Uspešno uvoženo!',
'importhistoryconflict'      => 'Zgodovina strani vključuje navzkrižno redakcijo (morda je bila stran naložena že prej)',
'importnosources'            => 'Na tem wikiju je ta možnost onemogočena.',
'importnofile'               => 'Uvožena ni bila nobena datoteka.',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Vaša uporabniška stran',
'tooltip-pt-anonuserpage'         => 'Uporabniška stran IP-naslova, ki ga uporabljate',
'tooltip-pt-mytalk'               => 'Vaša pogovorna stran',
'tooltip-pt-anontalk'             => 'Pogovor o urejanjih s tega IP-naslova',
'tooltip-pt-preferences'          => 'Vaše nastavitve',
'tooltip-pt-watchlist'            => 'Seznam strani, katerih spremembe spremljate',
'tooltip-pt-mycontris'            => 'Seznam vaših prispevkov',
'tooltip-pt-login'                => 'Prijava ni obvezna, vendar je zaželena',
'tooltip-pt-anonlogin'            => 'Prijava ni obvezna, vendar je zaželena',
'tooltip-pt-logout'               => 'Odjavite se',
'tooltip-ca-talk'                 => 'Pogovor o strani',
'tooltip-ca-edit'                 => 'Stran lahko uredite. Preden jo shranite, uporabite gumb za predogled.',
'tooltip-ca-addsection'           => 'Začnite novo razpravo',
'tooltip-ca-viewsource'           => 'Stran je zaščitena, ogledate si lahko njeno izvorno kodo',
'tooltip-ca-history'              => 'Prejšnje redakcije strani',
'tooltip-ca-protect'              => 'Zaščitite stran',
'tooltip-ca-delete'               => 'Brišite stran',
'tooltip-ca-undelete'             => 'Obnovite pred izbrisom napravljena urejanja strani.',
'tooltip-ca-move'                 => 'Preimenujte stran',
'tooltip-ca-watch'                => 'Dodajte stran na seznam nadzorov',
'tooltip-ca-unwatch'              => 'Odstranite stran s seznama nadzorov',
'tooltip-search'                  => 'Preiščite wiki',
'tooltip-search-go'               => 'Pojdi na strani z natanko takim imenom, če obstaja',
'tooltip-search-fulltext'         => 'Najde vneseno besedilo po straneh',
'tooltip-p-logo'                  => 'Glavna stran',
'tooltip-n-mainpage'              => 'Obiščite Glavno stran',
'tooltip-n-mainpage-description'  => 'Obiščite glavno stran',
'tooltip-n-portal'                => 'O projektu, kaj lahko storite, kje lahko kaj najdete',
'tooltip-n-currentevents'         => 'Spoznajte ozadje trenutnih dogodkov',
'tooltip-n-recentchanges'         => 'Seznam zadnjih sprememb {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'tooltip-n-randompage'            => 'Naložite naključno stran',
'tooltip-n-help'                  => 'Kraj za pomoč',
'tooltip-t-whatlinkshere'         => 'Seznam vseh s trenutno povezanih strani',
'tooltip-t-recentchangeslinked'   => 'Zadnje spremembe na s trenutno povezanih straneh',
'tooltip-feed-rss'                => 'RSS-dovod strani',
'tooltip-feed-atom'               => 'Atom-dovod strani',
'tooltip-t-contributions'         => 'Preglejte seznam uporabnikovih prispevkov',
'tooltip-t-emailuser'             => 'Pošljite uporabniku e-pismo',
'tooltip-t-upload'                => 'Naložite slike ali predstavnostne datoteke',
'tooltip-t-specialpages'          => 'Preglejte seznam vseh posebnih strani',
'tooltip-t-print'                 => 'Natisljiva različica strani',
'tooltip-t-permalink'             => 'Stalna povezava na to verzijo strani',
'tooltip-ca-nstab-main'           => 'Prikaže članek',
'tooltip-ca-nstab-user'           => 'Prikaže uporabniško stran',
'tooltip-ca-nstab-media'          => 'Prikaže stran s predstavnostno vsebino',
'tooltip-ca-nstab-special'        => 'Te posebne strani ne morete urejati',
'tooltip-ca-nstab-project'        => 'Prikaže stran projekta',
'tooltip-ca-nstab-image'          => 'Prikaže stran s sliko ali drugo datoteko',
'tooltip-ca-nstab-mediawiki'      => 'Prikaže sistemsko sporočilo',
'tooltip-ca-nstab-template'       => 'Prikaže stran predloge',
'tooltip-ca-nstab-help'           => 'Prikaže stran s pomočjo',
'tooltip-ca-nstab-category'       => 'Prikaže stran kategorije',
'tooltip-minoredit'               => 'Označite kot manjše urejanje',
'tooltip-save'                    => 'Shranite vnesene spremembe (ste si jih predogledali?)',
'tooltip-preview'                 => 'Pred shranjevanjem si, prosimo, predoglejte stran!',
'tooltip-diff'                    => 'Preglejte spremembe, ki ste jih vnesli.',
'tooltip-compareselectedversions' => 'Preglejte razlike med izbranima redakcijama.',
'tooltip-watch'                   => 'Dodajte stran na svoj spisek nadzorov.',
'tooltip-recreate'                => 'Ta stran je namenoma (skoraj) prazna.',
'tooltip-upload'                  => 'Naložite slikovno ali večpredstavno gradivo [alt-u]',
'tooltip-rollback'                => 'Funkcija »Vrni« z enim klikom povrne vsa urejanja zadnjega urejevalca te strani',
'tooltip-undo'                    => '"Razveljavi" vrne to urejanje in odpre predogled v oknu za urejanje.
Omogoča vnos pojasnila v povzetku urejanja.',

# Metadata
'nodublincore'      => 'Metapodatki Dublin Core RDF so na tem strežniku onemogočeni.',
'nocreativecommons' => 'Metapodatki Creative Commons RDF so za ta strežnik onemogočeni.',
'notacceptable'     => 'V obliki, ki jo lahko bere vaš odjemalec, wikistrežnik podatkov ne more ponuditi.',

# Attribution
'anonymous'        => 'Brezimni {{PLURAL:$1|uporabniki|uporabnika|uporabniki|uporabniki|uporabniki}} {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'siteuser'         => 'Uporabnik $1',
'lastmodifiedatby' => 'Stran je zadnjič urejal(a) $3 (čas spremembe: $2, $1).',
'othercontribs'    => '<!--Temelji na delu $1. Ne deluje pravilno-->Prispevki uporabnika.',
'others'           => 'drugi',
'siteusers'        => '{{PLURAL:$2|Uporabnik|Uporabnika|Uporabniki|Uporabniki|Uporabniki|Uporabniki}} {{GRAMMAR:rodilnik|{{SITENAME}}}} $1',
'creditspage'      => 'Zasluge za stran',
'nocredits'        => 'Ni dostopnih podatkov o zaslugah za stran.',

# Spam protection
'spamprotectiontitle' => 'Zaščita pred neželenimi sporočili',
'spamprotectiontext'  => "Stran, ki ste jo želeli shraniti, je zaščita pred spamom blokirala, saj je vsebovala povezavo na zunanjo stran, navedeno na črni listi spama. Če povezave (glejte spodaj) niste dodali vi, je verjetno obstajala že v prejšnji redakciji ali pa jo je dodalo vohunsko programje (''spyware'') na vašem računalniku.",
'spamprotectionmatch' => 'Naslednje besedilo je sprožilo naš filter neželenih sporočil: $1',
'spambot_username'    => 'MediaWiki čiščenje navlake',
'spam_reverting'      => 'Vračanje na zadnjo redakcijo brez povezav na $1',
'spam_blanking'       => 'Vse redakcije so vsebovale povezave na $1, izpraznjujem',

# Info page
'infosubtitle'   => 'Podatki o strani',
'numedits'       => 'Število urejanj (članek): $1',
'numtalkedits'   => 'Število urejanj (pogovorna stran): $1',
'numwatchers'    => 'Število oseb, ki spremljajo stran: $1',
'numauthors'     => 'Število avtorjev: $1',
'numtalkauthors' => 'Število avtorjev (pogovorna stran): $1',

# Math options
'mw_math_png'    => 'Vedno prikaži PNG',
'mw_math_simple' => 'Kadar je dovolj preprosto, uporabi HTML, sicer pa PNG',
'mw_math_html'   => 'Kadar je mogoče, uporabi HTML, sicer pa PNG',
'mw_math_source' => 'Pusti v TeX-ovi obliki (za besedilne brskalnike)',
'mw_math_modern' => 'Priporočeno za sodobne brskalnike',
'mw_math_mathml' => 'če je le mogoče, uporabi MathML (preizkusno)',

# Math errors
'math_failure'          => 'Ni mi uspelo razčleniti',
'math_unknown_error'    => 'neznana napaka',
'math_unknown_function' => 'neznana funkcija',
'math_lexing_error'     => 'slovarska napaka',
'math_syntax_error'     => 'skladenjska napaka',
'math_image_error'      => 'Pretvarjanje v PNG ni uspelo; preverite, ali so latex, dvips, gs, in convert pravilno nameščeni.',
'math_bad_tmpdir'       => 'Začasne mape za matematiko ne morem ustvariti ali pisati vanjo.',
'math_bad_output'       => 'Izhodne mape za matematiko ne morem ustvariti ali pisati vanjo.',
'math_notexvc'          => "Manjka izvedbena datoteka 'texvc'; za njeno namestitev si poglejte math/README.",

# Patrolling
'markaspatrolleddiff'        => 'Označite kot nadzorovano',
'markaspatrolledtext'        => 'Označite članek kot nadzorovan',
'markedaspatrolled'          => 'Označeno kot nadzorovano',
'markedaspatrolledtext'      => 'Izbrano različico ste označili kot nadzorovano.',
'rcpatroldisabled'           => 'Spremljanje zadnjih sprememb je onemogočeno.',
'rcpatroldisabledtext'       => 'Spremljanje zadnjih sprememb je začasno onemogočeno.',
'markedaspatrollederror'     => 'Ni mogoče označiti kot pregledano',
'markedaspatrollederrortext' => 'Določite redakcijo, ki jo želite označiti kot pregledano.',

# Patrol log
'patrol-log-page' => 'Dnevnik patrulje',
'patrol-log-line' => 'je označil $1 strani $2 kot preverjeno urejanje $3',
'patrol-log-auto' => '(samodejno)',

# Image deletion
'deletedrevision'                 => 'Prejšnja redakcija $1 je izbrisana',
'filedeleteerror-short'           => 'Napaka pri brisanju datoteke: $1',
'filedeleteerror-long'            => 'Pri brisanju datoteke so se pojavile napake:

$1',
'filedelete-missing'              => 'Datoteka »$1« ne more biti izbrisana, saj ne obstaja.',
'filedelete-old-unregistered'     => 'Izbrana različica datoteke »$1« ne obstaja v zbirki podatkov.',
'filedelete-current-unregistered' => 'Izbrana datoteka »$1« ni v zbirki podatkov.',
'filedelete-archive-read-only'    => 'Arhivna mapa »$1« ni zapisljiva s strani spletnega strežnika.',

# Browsing diffs
'previousdiff' => '← Starejša redakcija',
'nextdiff'     => 'Novejše urejanje →',

# Media information
'mediawarning'         => "'''Opozorilo''': Tovrstni tip datotek lahko vsebuje kodo, ki bi mogla ogroziti vaš sistem.
<hr />",
'imagemaxsize'         => 'Slike na opisnih straneh omeji na:',
'thumbsize'            => 'Velikost sličice (thumbnail):',
'file-info'            => 'Velikost datoteke: $1, MIME-vrsta: <code>$2</code>',
'file-info-size'       => '($1 × $2 točk, velikost datoteke: $3, MIME-vrsta: $4)',
'file-nohires'         => '<small>Slika višje resolucije ni na voljo.</small>',
'svg-long-desc'        => '(datoteka SVG, v izvirniku $1 × $2 slikovnih točk, velikost datoteke: $3)',
'show-big-image'       => 'Slika v višji resoluciji',
'show-big-image-thumb' => '<small>Velikost predogleda: $1 × $2 točk</small>',

# Special:NewFiles
'newimages'             => 'Galerija novih datotek',
'imagelisttext'         => 'Prikazujem $1 $2 {{PLURAL:$1|razvrščeno datoteko|razvrščeni datoteki|razvrščene datoteke|razvrščenih datotek|razvrščenih datotek}}.',
'showhidebots'          => '($1 bote)',
'noimages'              => 'Nič ni videti/datoteke ni.',
'ilsubmit'              => 'Išči',
'bydate'                => 'po datumu',
'sp-newimages-showfrom' => 'Prikaži datoteke, naložene od $2, $1 naprej',

# Bad image list
'bad_image_list' => 'Prikaz naslednjih slik v člankih je preprečen s tehničnimi sredstvi. Zaradi pohitritve delovanja poskušajte stran obdržati kratko, npr. pod 10 KB.
<!-- spodaj naštejte slike, ki jih želite v skladu s soglasjem občestva izključiti; sicer glejte razpravo v en: -->',

# Metadata
'metadata'          => 'Metapodatki',
'metadata-help'     => 'Datoteka vsebuje še druge podatke, ki jih je verjetno dodal za njeno ustvaritev oziroma digitalizacijo uporabljeni fotografski aparat ali optični bralnik. Če je bila datoteka pozneje spremenjena, podatki sprememb morda ne izražajo popolnoma.',
'metadata-expand'   => 'Razširi seznam',
'metadata-collapse' => 'Skrči seznam',
'metadata-fields'   => 'V skrčeni razpredelnici metapodatkov EXIF bodo prikazana le v tem sporočilu našteta polja. Druga bodo po privzetem skrita.
* make
* model
* datetimeoriginal
* exposuretime 
* fnumber
* isospeedratings 
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Širina',
'exif-imagelength'                 => 'Višina',
'exif-bitspersample'               => 'Bitov na barvni gradnik',
'exif-compression'                 => 'Shema stiskanja',
'exif-photometricinterpretation'   => 'Sestava točke',
'exif-orientation'                 => 'Usmerjenost',
'exif-samplesperpixel'             => 'Število gradnikov',
'exif-planarconfiguration'         => 'Poravnava podatkov',
'exif-ycbcrsubsampling'            => 'Podvzorčno razmerje med Y in C',
'exif-ycbcrpositioning'            => 'Razmestitev Y in C',
'exif-xresolution'                 => 'Vodoravna ločljivost',
'exif-yresolution'                 => 'Navpična ločljivost',
'exif-resolutionunit'              => 'Enota ločljivosti X in Y',
'exif-stripoffsets'                => 'Mesto podatkov slike',
'exif-rowsperstrip'                => 'Število vrstic na pas',
'exif-stripbytecounts'             => 'Zlogov na pas stiskanja.',
'exif-jpeginterchangeformat'       => 'Odtis na JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Zlogov JPEG-podatkov',
'exif-transferfunction'            => 'Funkcija prenosa',
'exif-whitepoint'                  => 'Kromatičnost bele točke',
'exif-primarychromaticities'       => 'Kromatičnosti osnovnih barv',
'exif-ycbcrcoefficients'           => 'Koeficient matrice transformacije barvnega prostora',
'exif-referenceblackwhite'         => 'Par črnih in belih referenčnih vrednosti',
'exif-datetime'                    => 'Datum in čas spremembe datoteke',
'exif-imagedescription'            => 'Naslov slike',
'exif-make'                        => 'Proizvajalec fotoaparata',
'exif-model'                       => 'Model fotoaparata',
'exif-software'                    => 'Uporabljeno programje',
'exif-artist'                      => 'Fotograf',
'exif-copyright'                   => 'Imetnik avtorskih pravic',
'exif-exifversion'                 => 'Različica Exif',
'exif-flashpixversion'             => 'Podprta različica Flashpix',
'exif-colorspace'                  => 'Barvni prostor',
'exif-componentsconfiguration'     => 'Pomen posameznih gradnikov',
'exif-compressedbitsperpixel'      => 'Velikost točke po stiskanju (v bitih)',
'exif-pixelydimension'             => 'Veljavna širina slike',
'exif-pixelxdimension'             => 'Veljavna višina slike',
'exif-makernote'                   => 'Opombe proizvajalca',
'exif-usercomment'                 => 'Uporabniške pripombe',
'exif-relatedsoundfile'            => 'Pripadajoča zvočna datoteka',
'exif-datetimeoriginal'            => 'Datum in čas ustvaritve podatkov',
'exif-datetimedigitized'           => 'Datum in čas digitalizacije',
'exif-subsectime'                  => 'Čas pomnilnika (1/100 s)',
'exif-subsectimeoriginal'          => 'Čas zajema',
'exif-subsectimedigitized'         => 'Digitalizacijski čas (1/100 s)',
'exif-exposuretime'                => 'Čas osvetlitve',
'exif-exposuretime-format'         => '$1 s ($2)',
'exif-fnumber'                     => 'Goriščno razmerje',
'exif-exposureprogram'             => 'Program osvetlitve',
'exif-spectralsensitivity'         => 'Spektralna občutljivost',
'exif-isospeedratings'             => 'Občutljivost filma ali tipala (ISO)',
'exif-oecf'                        => 'Optoelektronski pretvorbeni faktor',
'exif-shutterspeedvalue'           => 'Hitrost zaklopa',
'exif-aperturevalue'               => 'Zaslonka',
'exif-brightnessvalue'             => 'Svetlost',
'exif-exposurebiasvalue'           => 'Popravek osvetlitve',
'exif-maxaperturevalue'            => 'Največja vrednost zaslonke',
'exif-subjectdistance'             => 'Oddaljenost predmeta',
'exif-meteringmode'                => 'Način merjenja svetlobe',
'exif-lightsource'                 => 'Svetlobni vir',
'exif-flash'                       => 'Bliskavica',
'exif-focallength'                 => 'Goriščna razdalja leč',
'exif-subjectarea'                 => 'Površina predmeta',
'exif-flashenergy'                 => 'Energija bliskavice',
'exif-spatialfrequencyresponse'    => 'Odziv prostorske frekvence',
'exif-focalplanexresolution'       => 'Ločljivost goriščne ravnine X',
'exif-focalplaneyresolution'       => 'Ločljivost goriščne ravnine Y',
'exif-focalplaneresolutionunit'    => 'Enota ločljivosti goriščne ravnine',
'exif-subjectlocation'             => 'Položaj predmeta',
'exif-exposureindex'               => 'Indeks osvetlitve',
'exif-sensingmethod'               => 'Zaznavni postopek',
'exif-filesource'                  => 'Vir datoteke',
'exif-scenetype'                   => 'Vrsta prizora',
'exif-cfapattern'                  => 'Matrica filtracije barv',
'exif-customrendered'              => 'Obdelava slike po meri',
'exif-exposuremode'                => 'Nastavitev osvetlitve',
'exif-whitebalance'                => 'Ravnotežje belega',
'exif-digitalzoomratio'            => 'Razmerje digitalne povečave',
'exif-focallengthin35mmfilm'       => 'Goriščna razdalja pri 35-milimetrskem filmu',
'exif-scenecapturetype'            => 'Način zajema prizora',
'exif-gaincontrol'                 => 'Ojačanje',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Nasičenost',
'exif-sharpness'                   => 'Ostrina',
'exif-devicesettingdescription'    => 'Opis nastavitev naprave',
'exif-subjectdistancerange'        => 'Območje oddaljenosti predmeta',
'exif-imageuniqueid'               => 'ID slike',
'exif-gpsversionid'                => 'Različica GPS-oznake',
'exif-gpslatituderef'              => 'Severna ali južna zemljepisna širina',
'exif-gpslatitude'                 => 'Zemljepisna širina',
'exif-gpslongituderef'             => 'Vzhodna ali zahodna zemljepisna dolžina',
'exif-gpslongitude'                => 'Zemljepisna dolžina',
'exif-gpsaltituderef'              => 'Referenca višine',
'exif-gpsaltitude'                 => 'Višina',
'exif-gpstimestamp'                => 'GPS-čas (atomska ura)',
'exif-gpssatellites'               => 'Za merjenje uporabljeni sateliti',
'exif-gpsstatus'                   => 'Položaj sprejemnika',
'exif-gpsmeasuremode'              => 'Način merjenja',
'exif-gpsdop'                      => 'Natančnost merjenja',
'exif-gpsspeedref'                 => 'Enota hitrosti',
'exif-gpsspeed'                    => 'Hitrost GPS-sprejemnika',
'exif-gpstrackref'                 => 'Referenca smeri gibanja',
'exif-gpstrack'                    => 'Smer merjenja',
'exif-gpsimgdirectionref'          => 'Referenca smeri slike',
'exif-gpsimgdirection'             => 'Smer slike',
'exif-gpsmapdatum'                 => 'Uporabljeni geodetski podatki',
'exif-gpsdestlatituderef'          => 'Referenca zemljepisne širine cilja',
'exif-gpsdestlatitude'             => 'Zemljepisna širina cilja',
'exif-gpsdestlongituderef'         => 'Referenca zemljepisne dolžine cilja',
'exif-gpsdestlongitude'            => 'Zemljepisna dolžina cilja',
'exif-gpsdestbearingref'           => 'Referenca smeri cilja',
'exif-gpsdestbearing'              => 'Smer cilja',
'exif-gpsdestdistanceref'          => 'Referenca razdalje do cilja',
'exif-gpsdestdistance'             => 'Razdalja do cilja',
'exif-gpsprocessingmethod'         => 'Ime postopka obdelave GPS-opazovanj',
'exif-gpsareainformation'          => 'Ime GPS-območja',
'exif-gpsdatestamp'                => 'GPS-datum',
'exif-gpsdifferential'             => 'Diferencialni popravek GPS',

# EXIF attributes
'exif-compression-1' => 'Nestisnjeno',

'exif-orientation-1' => 'Navadna',
'exif-orientation-2' => 'Vodoravno zrcaljeno',
'exif-orientation-3' => 'Zasukano za 180°',
'exif-orientation-4' => 'Navpično zrcaljeno',
'exif-orientation-5' => 'Zasukano za 90° v levo in navpično zrcaljeno',
'exif-orientation-6' => 'Zasukano za 90° v desno',
'exif-orientation-7' => 'Zasukano za 90° v desno in navpično zrcaljeno',
'exif-orientation-8' => 'Zasukano za 90° v levo',

'exif-planarconfiguration-1' => 'grudast format',
'exif-planarconfiguration-2' => 'ravninski format',

'exif-xyresolution-i' => '$1 dpi ({{plural:$1|točka/palec|točki/palec|točke/palec|točk/palec|točk/palec}})',
'exif-xyresolution-c' => '$1 dpc ({{plural:$1|točka/centimeter|točki/centimeter|točke/centimeter|točk/centimeter|točk/centimeter}})',

'exif-componentsconfiguration-0' => 'ne obstaja',

'exif-exposureprogram-0' => 'Ni določen',
'exif-exposureprogram-1' => 'Ročno',
'exif-exposureprogram-2' => 'Navaden',
'exif-exposureprogram-3' => 'Prednost zaslonke',
'exif-exposureprogram-4' => 'Prednost zaklopa',
'exif-exposureprogram-5' => 'Ustvarjalni program (prednost globinske ostrine)',
'exif-exposureprogram-6' => 'Akcijski program (prednost kratke osvetlitve)',
'exif-exposureprogram-7' => 'Portretna nastavitev (fotografije od blizu, ozadje ni ostro)',
'exif-exposureprogram-8' => 'Pokrajinska nastavitev (fotografije pokrajine, ostro ozadje)',

'exif-subjectdistance-value' => '$1 {{PLURAL:$1|meter|metra|metre|metrov|metrov}}',

'exif-meteringmode-0'   => 'Neznan',
'exif-meteringmode-1'   => 'Povprečno',
'exif-meteringmode-2'   => 'Središčno obteženo povprečno',
'exif-meteringmode-3'   => 'Točkovno',
'exif-meteringmode-4'   => 'Večtočkovno',
'exif-meteringmode-5'   => 'Vzorčno',
'exif-meteringmode-6'   => 'Delno',
'exif-meteringmode-255' => 'Drugače',

'exif-lightsource-0'   => 'Neznan',
'exif-lightsource-1'   => 'Dnevna svetloba',
'exif-lightsource-2'   => 'Fluorescenčen',
'exif-lightsource-3'   => 'Volfram (žarnica)',
'exif-lightsource-4'   => 'Bliskavica',
'exif-lightsource-9'   => 'Lepo vreme',
'exif-lightsource-10'  => 'Oblačno',
'exif-lightsource-11'  => 'Senca',
'exif-lightsource-12'  => 'Dnevni fluorescenčen (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Dnevni bel fluorescenčen (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Hladen bel fluorescenčen (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Bel fluorescenčen (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Običajna svetloba A',
'exif-lightsource-18'  => 'Običajna svetloba B',
'exif-lightsource-19'  => 'Običajna svetloba C',
'exif-lightsource-24'  => 'ISO-ateljejski volfram',
'exif-lightsource-255' => 'Drugačen',

'exif-focalplaneresolutionunit-2' => 'palcev',

'exif-sensingmethod-1' => 'Nedoločen',
'exif-sensingmethod-2' => 'Enočipno barvno ploskovno tipalo',
'exif-sensingmethod-3' => 'Dvočipno barvno ploskovno tipalo',
'exif-sensingmethod-4' => 'Tričipno barvno ploskovno tipalo',
'exif-sensingmethod-5' => 'Zaporedno barvno ploskovno tipalo',
'exif-sensingmethod-7' => 'Trikratno tipalo',
'exif-sensingmethod-8' => 'Zaporedno barvno črtno tipalo',

'exif-scenetype-1' => 'Neposredno fotografirana slika',

'exif-customrendered-0' => 'Navaden postopek',
'exif-customrendered-1' => 'Prilagojen postopek',

'exif-exposuremode-0' => 'Samodejno',
'exif-exposuremode-1' => 'Ročno',
'exif-exposuremode-2' => 'Samodejna konzola',

'exif-whitebalance-0' => 'Samodejno',
'exif-whitebalance-1' => 'Ročno',

'exif-scenecapturetype-0' => 'Navadni',
'exif-scenecapturetype-1' => 'Pokrajina',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Nočni prizor',

'exif-gaincontrol-0' => 'Brez',
'exif-gaincontrol-1' => 'Nizko ojačanje zgoraj',
'exif-gaincontrol-2' => 'Visoko ojačanje zgoraj',
'exif-gaincontrol-3' => 'Nizko ojačanje spodaj',
'exif-gaincontrol-4' => 'Visoko ojačanje spodaj',

'exif-contrast-0' => 'Navaden',
'exif-contrast-1' => 'Nizek',
'exif-contrast-2' => 'Visok',

'exif-saturation-0' => 'Navadna',
'exif-saturation-1' => 'Nizka nasičenost',
'exif-saturation-2' => 'Visoka nasičenost',

'exif-sharpness-0' => 'Navadna',
'exif-sharpness-1' => 'Mehka',
'exif-sharpness-2' => 'Trda',

'exif-subjectdistancerange-0' => 'Neznano',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Pogled od blizu',
'exif-subjectdistancerange-3' => 'Pogled od daleč',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Severna zemljepisna širina',
'exif-gpslatitude-s' => 'Južna zemljepisna širina',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Vzhodna zemljepisna dolžina',
'exif-gpslongitude-w' => 'Zahodna zemljepisna dolžina',

'exif-gpsstatus-a' => 'Merjenje poteka',
'exif-gpsstatus-v' => 'Interoperabilnost merjenja',

'exif-gpsmeasuremode-2' => 'Dvorazsežnostno merjenje',
'exif-gpsmeasuremode-3' => 'Trirazsežnostno merjenje',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometri na uro',
'exif-gpsspeed-m' => 'Milje na uro',
'exif-gpsspeed-n' => 'Vozli',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Pravi azimut',
'exif-gpsdirection-m' => 'Magnetni azimut',

# External editor support
'edit-externally'      => 'Uredite datoteko z uporabo zunanjega orodja',
'edit-externally-help' => '(Za več informacij glej [http://www.mediawiki.org/wiki/Manual:External_editors navodila za namestitev])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'vse',
'imagelistall'     => 'vse',
'watchlistall2'    => 'vse',
'namespacesall'    => 'vse',
'monthsall'        => 'vse',

# E-mail address confirmation
'confirmemail'             => 'Potrditev naslova elektronske pošte',
'confirmemail_noemail'     => 'Nimate določenega veljavnega e-poštnega naslova v vaših [[Special:Preferences|uporabniških nastavitvah]].',
'confirmemail_text'        => 'Za uporabo e-poštnih možnosti {{GRAMMAR:rodilnik|{{SITENAME}}}} morate najprej potrditi svoj e-poštni naslov.
S klikom spodnjega gumba pošljite nanj potrditveno sporočilo in sledite prejetim navodilom.
Ali ste svoj e-poštni naslov že potrdili, lahko preverite v nastavitvah.',
'confirmemail_pending'     => 'Potrditveno geslo vam je že bilo poslano; če ste pred kratkim ustvarili svoj račun, boste na njega morali počakati nekaj minut da prispe, preden boste poskušali zahtevali novo geslo.',
'confirmemail_send'        => 'Pošlji mi potrditveno sporočilo',
'confirmemail_sent'        => 'Potrditveno e-sporočilo je bilo poslano.',
'confirmemail_oncreate'    => 'Potrditveno geslo je bilo poslano na vaš e-poštni naslov.
To geslo ni potrebno za vpis, vendar ga boste morali vnesti pred omogočanjem katere koli funkcije temelječe na e-pošti na wikiju.',
'confirmemail_sendfailed'  => 'Potrditvenega sporočila ni bilo mogoče poslati. Prosimo, preverite, če niste naslova vnesli napačno.

Posrednik e-pošte je vrnil: $1',
'confirmemail_invalid'     => 'Potrditveno geslo je neveljavno. Morda je poteklo.',
'confirmemail_needlogin'   => 'Za potrditev svojega e-poštnega se morate $1.',
'confirmemail_success'     => 'Vaš e-poštni naslov je potrjen. Zdaj se lahko prijavite in uporabljate wiki.',
'confirmemail_loggedin'    => 'Svoj elektronski naslov ste uspešno potrdili.',
'confirmemail_error'       => 'Vaša potrditev se žal ni shranila.',
'confirmemail_subject'     => 'Potrditev e-poštnega naslova',
'confirmemail_body'        => 'Nekdo z IP-naslovom »$1« (verjetno vi) je v {{GRAMMAR:dajalnik|{{SITENAME}}}} ustvaril račun »$2« in zanj vpisal vaš elektronski naslov. Da bi potrdili, da ta resnično pripada vam in s tem lahko začeli uporabljati e-poštne storitve {{GRAMMAR:rodilnik|{{SITENAME}}}}, odprite naslednjo povezavo: 

$3

Če tega niste napravili vi, sledite naslednji povezavi in tako prekličite potrditev elektronskega naslova:

$5

Potrditvena koda bo potekla ob $4.',
'confirmemail_invalidated' => 'Potrditev e-poštnega naslova preklicana',

# Scary transclusion
'scarytranscludedisabled' => '[prevključevanje med wikiji je onemogočeno]',
'scarytranscludefailed'   => '[pridobivanje predloge za $1 žal ni uspelo]',
'scarytranscludetoolong'  => '[Spletni naslov je žal predolg]',

# Trackbacks
'trackbackbox'      => 'Sledilniki članka:<br />
$1',
'trackbackremove'   => '([$1 Izbris])',
'trackbacklink'     => 'Sledilnik',
'trackbackdeleteok' => 'Sledilnik je uspešno izbrisan.',

# Delete conflict
'deletedwhileediting' => "'''Opozorilo''': Med vašim urejanjem je eden izmed administratorjev stran izbrisal!",
'confirmrecreate'     => "Medtem ko ste stran urejali, jo je uporabnik [[User:$1|$1]] ([[User talk:$1|pogovor]]) izbrisal z razlogom: 
:''$2'' 
Prosimo, potrdite, da jo resnično želite znova ustvariti.",
'recreate'            => 'Ponovno ustvari',

'unit-pixel' => ' točk',

# action=purge
'confirm_purge_button' => 'Osveži',
'confirm-purge-top'    => 'Osvežim predpomnjenje strani?',

# Multipage image navigation
'imgmultigo' => 'Pojdi!',

# Table pager
'ascending_abbrev'         => 'nar',
'descending_abbrev'        => 'pad',
'table_pager_next'         => 'Naslednja stran',
'table_pager_prev'         => 'Prejšnja stran',
'table_pager_first'        => 'Prva stran',
'table_pager_last'         => 'Zadnja stran',
'table_pager_limit'        => 'Prikaži $1 postavk na stran',
'table_pager_limit_submit' => 'Pojdi',
'table_pager_empty'        => 'Ni zadetkov',

# Auto-summaries
'autosumm-blank'   => 'Odstranjevanje celotne vsebine strani',
'autosumm-replace' => "Zamenjava strani s/z '$1'",
'autoredircomment' => 'preusmeritev na [[$1]]',
'autosumm-new'     => 'Nova stran z vsebino: $1',

# Watchlist editor
'watchlistedit-numitems'       => 'Tvoj spisek nadzorov vsebuje {{PLURAL:$1|1 stran|2 strani|$1 strani|$1 strani}}, izključujoč pogovorne strani.',
'watchlistedit-noitems'        => 'Tvoj spisek nadzorov je prazen.',
'watchlistedit-normal-title'   => 'Uredi spisek nadzorov',
'watchlistedit-normal-legend'  => 'Odstrani strani iz spiska nadzorov',
'watchlistedit-normal-explain' => 'Strani na vašem spisku nadzorov so prikazane spodaj.
Da odstranite stran, označite kvadratek poleg nje in kliknite {{:MediaWiki:Watchlistedit-normal-submit}}.
Lahko tudi [[Special:Watchlist/raw|uredite gol spisek]].',
'watchlistedit-normal-submit'  => 'Odstrani strani',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 stran je bila odstranjena|2 strani sta bili odstranjeni|$1 strani so bile odstranjene|$1 strani je bilo odstranjenih|$1 strani je bilo odstranjenih}} iz tvojega spiska nadzorov:',
'watchlistedit-raw-title'      => 'Uredi gol spisek nadzorov',
'watchlistedit-raw-legend'     => 'Uredi gol spisek nadzorov',
'watchlistedit-raw-titles'     => 'Strani:',
'watchlistedit-raw-submit'     => 'Posodobi spisek nadzorov',
'watchlistedit-raw-done'       => 'Tvoj spisek nadzorov je bil posodobljen.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 stran je bila dodana|2 strani sta bili dodani|$1 strani so bile dodane|$1 strani je bilo dodanih}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 stran je bila odstranjena|2 strani sta bili odstranjeni|$1 strani so bile odstranjene|$1 strani je bilo odstranjenih}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Oglej si ustrezne spremembe',
'watchlisttools-edit' => 'Poglej in uredi spisek nadzorov',
'watchlisttools-raw'  => 'Uredi gol spisek nadzorov',

# Special:Version
'version'                  => 'Različica',
'version-specialpages'     => 'Posebne strani',
'version-variables'        => 'Spremenljivke',
'version-other'            => 'Ostalo',
'version-version'          => '(Različica $1)',
'version-license'          => 'Dovoljenje',
'version-software-product' => 'Izdelek',
'version-software-version' => 'Različica',

# Special:FilePath
'filepath'         => 'Pot do datoteke',
'filepath-page'    => 'Datoteka:',
'filepath-submit'  => 'Pot',
'filepath-summary' => 'Ta posebna stran vrne polno pot do datoteke. Slike so prikazane v polni resoluciji, druge vrste datotek pa se zaženejo v za njih določenih programih. Vtipkajte ime datoteke brez predpone »{{ns:image}}:«.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Iskanje podvojenih datotek',
'fileduplicatesearch-summary'  => 'Iskanje podvojenih datotek temelji na podlagi njenih hash vrednosti.

Vnesite ime datoteke brez predpone »{{ns:image}}:«.',
'fileduplicatesearch-legend'   => 'Poišči dvojnik',
'fileduplicatesearch-filename' => 'Ime datoteke:',
'fileduplicatesearch-submit'   => 'Iskanje',

# Special:SpecialPages
'specialpages'                   => 'Posebne strani',
'specialpages-group-maintenance' => 'Vzdrževalna poročila',
'specialpages-group-other'       => 'Ostale posebne strani',
'specialpages-group-login'       => 'Prijavite se / registrirajte se',
'specialpages-group-changes'     => 'Zadnje spremembe in dnevniki',
'specialpages-group-media'       => 'Poročila o datotekah in nalaganja',
'specialpages-group-users'       => 'Uporabniki in pravice',
'specialpages-group-highuse'     => 'Strani visoke uporabe',

# Special:BlankPage
'blankpage' => 'Prazna stran',

# Special:Tags
'tag-filter'        => 'Filter [[Special:Tags|oznak]]:',
'tag-filter-submit' => 'Filter',
'tags-title'        => 'Oznake',
'tags-tag'          => 'Ime oznake',
'tags-edit'         => 'uredi',
'tags-hitcount'     => '$1 {{PLURAL:$1|sprememba|spremembi|spremembe|sprememb|sprememb}}',

# HTML forms
'htmlform-select-badoption'    => 'Vrednost, ki ste jo vnesli, ni veljavna.',
'htmlform-int-invalid'         => 'Vrednost, ki ste jo vnesli, ni celo število.',
'htmlform-float-invalid'       => 'Vrednost, ki ste jo vnesli, ni število.',
'htmlform-int-toolow'          => 'Vrednost, ki ste jo vnesli, je manjša od najmanjše dovoljene vrednosti $1',
'htmlform-int-toohigh'         => 'Vrednost, ki ste jo vnesli, je večja od največje dovoljene vrednosti $1',
'htmlform-submit'              => 'Pošlji',
'htmlform-reset'               => 'Razveljavi spremembe',
'htmlform-selectorother-other' => 'Drugo',

);
