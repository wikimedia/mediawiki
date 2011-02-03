<?php
/** Slovenian (Slovenščina)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Dbc334
 * @author Freakolowsky
 * @author McDutchie
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
	'BrokenRedirects'           => array( 'PretrganePreusmeritve' ),
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
	'Randompage'                => array( 'Naključno', 'NaključnaStran' ),
	'Lonelypages'               => array( 'OsiroteleStrani' ),
	'Uncategorizedpages'        => array( 'NekategoriziraneStrani' ),
	'Uncategorizedcategories'   => array( 'NekategoriziraneKategorije' ),
	'Uncategorizedimages'       => array( 'NekategoriziraneDatoteke', 'NekategoriziraneSlike' ),
	'Uncategorizedtemplates'    => array( 'NekategoriziranePredloge' ),
	'Unusedcategories'          => array( 'NeuporabljeneKategorije' ),
	'Unusedimages'              => array( 'NeuporabljeneDatoteke', 'NeuporabljeneSlike' ),
	'Wantedpages'               => array( 'ŽeleneStrani' ),
	'Wantedcategories'          => array( 'ŽeleneKategorije' ),
	'Wantedfiles'               => array( 'ŽeleneDatoteke' ),
	'Wantedtemplates'           => array( 'ŽelenePredloge' ),
	'Mostlinked'                => array( 'NajboljPovezaneStrani' ),
	'Mostlinkedcategories'      => array( 'NajboljPovezaneKategorije' ),
	'Mostlinkedtemplates'       => array( 'NajboljPovezanePredloge' ),
	'Mostimages'                => array( 'NajboljPovezaneDatoteke' ),
	'Mostcategories'            => array( 'NajvečKategorij' ),
	'Mostrevisions'             => array( 'NajvečRedakcij' ),
	'Fewestrevisions'           => array( 'NajmanjRedakcij' ),
	'Shortpages'                => array( 'KratkeStrani' ),
	'Longpages'                 => array( 'DolgeStrani' ),
	'Newpages'                  => array( 'NoveStrani' ),
	'Ancientpages'              => array( 'StarodavneStrani' ),
	'Protectedpages'            => array( 'ZaščiteneStrani' ),
	'Protectedtitles'           => array( 'ZaščiteniNaslovi' ),
	'Allpages'                  => array( 'VseStrani' ),
	'Unblock'                   => array( 'Odblokiraj' ),
	'Specialpages'              => array( 'PosebneStrani' ),
	'Contributions'             => array( 'Prispevki' ),
	'Whatlinkshere'             => array( 'KajSePovezujeSem' ),
	'Movepage'                  => array( 'PrestaviStran', 'PremakniStran' ),
	'Blockme'                   => array( 'BlokirajMe' ),
	'Booksources'               => array( 'ViriKnjig' ),
	'Categories'                => array( 'Kategorije' ),
	'Export'                    => array( 'Izvozi' ),
	'Version'                   => array( 'Različica', 'Verzija' ),
	'Allmessages'               => array( 'VsaSporočila' ),
	'Log'                       => array( 'Dnevnik', 'Dnevniki' ),
	'Blockip'                   => array( 'Blokiraj', 'BlokirajIP', 'BlokirajUporabnika' ),
	'Undelete'                  => array( 'Obnovi' ),
	'Import'                    => array( 'Uvoz' ),
	'MIMEsearch'                => array( 'IskanjeMIME' ),
	'Unwatchedpages'            => array( 'NespremljaneStrani' ),
	'Mypage'                    => array( 'MojaStran' ),
	'Mytalk'                    => array( 'MojPogovor' ),
	'Mycontributions'           => array( 'MojiPrispevki' ),
	'Listadmins'                => array( 'SeznamAdministratorjev' ),
	'Listbots'                  => array( 'SeznamBotov' ),
	'Popularpages'              => array( 'PriljubljeneStrani' ),
	'Search'                    => array( 'Iskanje' ),
	'Resetpass'                 => array( 'SpremeniGeslo', 'PonastaviGeslo' ),
	'Withoutinterwiki'          => array( 'BrezInterwikijev' ),
	'MergeHistory'              => array( 'ZdružiZgodovino' ),
	'Filepath'                  => array( 'PotDatoteke' ),
	'Blankpage'                 => array( 'PraznaStran' ),
	'DeletedContributions'      => array( 'IzbrisaniPrispevki' ),
	'Activeusers'               => array( 'AktivniUporabniki' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#PREUSMERITEV', '#REDIRECT' ),
	'notoc'                 => array( '0', '__BREZKAZALAVSEBINE__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__BREZGALERIJE__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__VSILIKAZALOVSEBINE__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__POGLAVJE__', '__TOC__' ),
	'noeditsection'         => array( '0', '__BREZUREJANJARAZDELKOV__', '__NOEDITSECTION__' ),
	'img_thumbnail'         => array( '1', 'sličica', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'sličica=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'desno', 'right' ),
	'img_left'              => array( '1', 'levo', 'left' ),
	'img_none'              => array( '1', 'brez', 'none' ),
	'img_width'             => array( '1', '$1_pik', '$1px' ),
	'img_center'            => array( '1', 'sredina', 'sredinsko', 'center', 'centre' ),
	'img_framed'            => array( '1', 'okvir', 'okvirjeno', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'brezokvirja', 'frameless' ),
	'img_page'              => array( '1', 'stran=$1', 'm_stran $1', 'page=$1', 'page $1' ),
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
	'url_path'              => array( '0', 'POT', 'PATH' ),
	'url_query'             => array( '0', 'POIZVEDBA', 'QUERY' ),
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
$separatorTransformTable = array( ',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Podčrtavanje povezav:',
'tog-highlightbroken'         => 'Oblikuj pretrgane povezave <a href="" class="new">kot to</a> (druga možnost: kot to<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Poravnavaj odstavke',
'tog-hideminor'               => 'Skrij manjše popravke v zadnjih spremembah',
'tog-hidepatrolled'           => 'Skrij pregledana urejanja v zadnjih spremembah',
'tog-newpageshidepatrolled'   => 'Skrij pregledane strani iz seznama novih strani',
'tog-extendwatchlist'         => 'Razširi spisek nadzorov, da bo prikazoval vse spremembe, ne le najnovejše',
'tog-usenewrc'                => 'Izboljšane zadnje spremembe (zahteva JavaScript)',
'tog-numberheadings'          => 'Samodejno številči poglavja',
'tog-showtoolbar'             => 'Prikaži urejevalno orodno vrstico',
'tog-editondblclick'          => 'Omogoči urejanje strani z dvojnim klikom (zahteva JavaScript)',
'tog-editsection'             => 'Omogoči urejanje delov prek povezav [{{int:editsection}}]',
'tog-editsectiononrightclick' => 'Omogoči urejanje razdelkov z desnim klikanjem njihovih naslovov (zahteva JavaScript)',
'tog-showtoc'                 => 'Prikaži vsebino (strani z več kot tremi naslovi)',
'tog-rememberpassword'        => 'Zapomni si me v tem brskalniku (za največ $1 {{PLURAL:$1|dan|dneva|dni}})',
'tog-watchcreations'          => 'Vse ustvarjene strani dodaj na spisek nadzorov',
'tog-watchdefault'            => 'Dodaj na spisek nadzorov vse članke, ki sem jih ustvaril/-a ali spremenil/-a',
'tog-watchmoves'              => 'Dodaj strani, ki jih premaknem, na moj spisek nadzorov',
'tog-watchdeletion'           => 'Dodaj strani, ki jih izbrišem, na moj spisek nadzorov',
'tog-minordefault'            => 'Vsa urejanja označi kot manjša',
'tog-previewontop'            => 'Prikaži predogled pred urejevalnim poljem in ne za njim',
'tog-previewonfirst'          => 'Ob začetku urejanja prikaži predogled',
'tog-nocache'                 => 'Onemogoči predpomnenje strani v brskalniku',
'tog-enotifwatchlistpages'    => 'Ob spremembah strani mi pošlji e-pošto',
'tog-enotifusertalkpages'     => 'Pošlji e-pošto ob spremembah moje pogovorne strani',
'tog-enotifminoredits'        => 'Pošlji e-pošto tudi za manjše spremembe strani',
'tog-enotifrevealaddr'        => 'V sporočilih z obvestili o spremembah razkrij moj e-poštni naslov',
'tog-shownumberswatching'     => 'Prikaži število uporabnikov, ki spremljajo temo',
'tog-oldsig'                  => 'Predogled obstoječega podpisa:',
'tog-fancysig'                => 'Obravnavaj podpis kot wikibesedilo (brez samodejne povezave)',
'tog-externaleditor'          => 'Po privzetem uporabljaj zunanji urejevalnik (samo za strokovnjake; potrebuje posebne nastavitve na vašem računalniku; [http://www.mediawiki.org/wiki/Manual:External_editors več informacij])',
'tog-externaldiff'            => 'Po privzetem uporabljaj zunanje primerjanje (samo za strokovnjake; potrebuje posebne nastavitve na vašem računalniku; [http://www.mediawiki.org/wiki/Manual:External_editors več informacij])',
'tog-showjumplinks'           => 'Prikaži pomožni povezavi »Skoči na«',
'tog-uselivepreview'          => 'Uporabi hitri predogled (zahteva JavaScript) (preizkusno)',
'tog-forceeditsummary'        => 'Ob vpisu praznega povzetka urejanja me opozori',
'tog-watchlisthideown'        => 'Na spisku nadzorov skrij moja urejanja',
'tog-watchlisthidebots'       => 'Na spisku nadzorov skrij urejanja botov',
'tog-watchlisthideminor'      => 'Skrij manjša urejanja na spisku nadzorov',
'tog-watchlisthideliu'        => 'Skrij urejanja prijavljenih uporabnikov v spisku nadzorov',
'tog-watchlisthideanons'      => 'Skrij urejanja anonimnih uporabnikov v spisku nadzorov',
'tog-watchlisthidepatrolled'  => 'Skrij pregledana urejanja s spiska nadzorov',
'tog-ccmeonemails'            => 'Pošlji mi kopijo e-pošt, ki jih pošljem drugim uporabnikom',
'tog-diffonly'                => 'Ne prikaži vsebine strani pod primerjavo',
'tog-showhiddencats'          => 'Prikaži skrite kategorije',
'tog-norollbackdiff'          => 'Ne prikaži primerjave po izvedeni vrnitvi',

'underline-always'  => 'Vedno',
'underline-never'   => 'Nikoli',
'underline-default' => 'Privzeto (brskalnik)',

# Font style option in Special:Preferences
'editfont-style'     => 'Uredi področni slog pisave:',
'editfont-default'   => 'Privzeto po brskalniku',
'editfont-monospace' => 'Pisava monospace',
'editfont-sansserif' => 'Pisava sans-serif',
'editfont-serif'     => 'Pisava serif',

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
'hidden-categories'              => '{{PLURAL:$1|Skrita kategorija|Skriti kategoriji|Skrite kategorije}}',
'hidden-category-category'       => 'Skrite kategorije',
'category-subcat-count'          => 'Ta del kategorije ima $1 {{PLURAL:$1|naslednjo podkategorijo|naslednji podkategoriji|naslednje podkategorije|naslednjih podkategorij}}{{PLURAL:$2||, od skupno $2}}.',
'category-subcat-count-limited'  => 'Ta kategorija ima {{PLURAL:$1|$1 naslednjo podkategorijo|$1 naslednji podkategoriji|$1 naslednje podkategorije|$1 naslednjih podkategorij}}.',
'category-article-count'         => 'Ta del kategorije vsebuje $1 {{PLURAL:$1|naslednjo stran|naslednji strani|naslednje strani|naslednjih strani}}{{PLURAL:$2||, od skupno $2}}.',
'category-article-count-limited' => 'V tej kategoriji {{PLURAL:$1|je $1 naslednja stran|sta $1 naslednji strani|so $1 naslednje strani|je $1 naslednjih strani}}.',
'category-file-count'            => 'Ta kategorija vsebuje $1 {{PLURAL:$1|naslednjo datoteko|naslednji datoteki|naslednje datoteke|naslednjih datotek}}{{PLURAL:$2||, od skupno $2}}.',
'category-file-count-limited'    => 'V tej kategoriji {{PLURAL:$1|je $1 naslednja datoteka|sta $1 naslednji datoteki|so $1 naslednje datoteke|je $1 naslednjih datotek}}.',
'listingcontinuesabbrev'         => 'nadalj.',
'index-category'                 => 'Indeksirane strani',
'noindex-category'               => 'Neindeksirane strani',

'mainpagetext'      => "'''Programje MediaWiki je bilo uspešno nameščeno.'''",
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
'vector-action-addsection'       => 'Dodaj temo',
'vector-action-delete'           => 'Izbriši',
'vector-action-move'             => 'Prestavi',
'vector-action-protect'          => 'Zaščiti',
'vector-action-undelete'         => 'Vrni',
'vector-action-unprotect'        => 'Odstrani zaščito',
'vector-simplesearch-preference' => 'Omogoči izboljšane predloge iskanja (samo koža Vector)',
'vector-view-create'             => 'Ustvari',
'vector-view-edit'               => 'Uredi',
'vector-view-history'            => 'Zgodovina',
'vector-view-view'               => 'Preberi',
'vector-view-viewsource'         => 'Izvorno besedilo',
'actions'                        => 'Dejanja',
'namespaces'                     => 'Imenski prostori',
'variants'                       => 'Različice',

'errorpagetitle'    => 'Napaka',
'returnto'          => 'Vrnite se na $1.',
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
'view'              => 'Ogled',
'edit'              => 'Uredi stran',
'create'            => 'Ustvari',
'editthispage'      => 'Uredi stran',
'create-this-page'  => 'Ustvari to stran',
'delete'            => 'Briši',
'deletethispage'    => 'Briši stran',
'undelete_short'    => 'Vrni $1 {{PLURAL:$1|izbrisano urejanje|izbrisani urejanji|izbrisana urejanja|izbrisanih urejanj|izbrisanih urejanj}}',
'viewdeleted_short' => 'Ogled {{PLURAL:$1|enega izbrisanega urejanja|$1 izbrisanih urejanj}}',
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
'viewcount'         => 'Stran je bila naložena {{PLURAL:$1|$1-krat}}.',
'protectedpage'     => 'Zaščitena stran',
'jumpto'            => 'Skoči na:',
'jumptonavigation'  => 'navigacija',
'jumptosearch'      => 'iskanje',
'view-pool-error'   => 'Žal so strežniki trenutno preobremenjeni.
Preveč uporabnikov skuša obiskati to stran.
Prosimo za potrpežljivost, obiščite nas spet kmalu.

$1',
'pool-timeout'      => 'Časovno obdobje čakanja na zaklep',
'pool-queuefull'    => 'Čakalna vrsta zaloge je polna',
'pool-errorunknown' => 'Neznana napaka',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'O {{GRAMMAR:dajalnik|{{SITENAME}}}}',
'aboutpage'            => 'Project:O {{GRAMMAR:dajalnik|{{SITENAME}}}}',
'copyright'            => 'Besedilo je na razpolago pod pogoji $1.',
'copyrightpage'        => '{{ns:project}}:Avtorske pravice',
'currentevents'        => 'Trenutni dogodki',
'currentevents-url'    => 'Project:Trenutni dogodki',
'disclaimers'          => 'Zanikanja odgovornosti',
'disclaimerpage'       => 'Project:Splošno zanikanje odgovornosti',
'edithelp'             => 'Pomoč pri urejanju',
'edithelppage'         => 'Help:Urejanje',
'helppage'             => 'Help:Vsebina',
'mainpage'             => 'Glavna stran',
'mainpage-description' => 'Glavna stran',
'policy-url'           => 'Project:Pravila',
'portal'               => 'Portal občestva',
'portal-url'           => 'Project:Portal občestva',
'privacy'              => 'Politika zasebnosti',
'privacypage'          => 'Project:Politika zasebnosti',

'badaccess'        => 'Napaka pri dovoljenju',
'badaccess-group0' => 'Zahtevanega dejanja vam ni dovoljeno izvesti.',
'badaccess-groups' => 'Izvajanje želenega dejanja je omejeno na uporabnike v {{PLURAL:$2|skupini|eni izmed skupin}}: $1.',

'versionrequired'     => 'Potrebna je različica MediaWiki $1',
'versionrequiredtext' => 'Za uporabo strani je potrebna različica MediaWiki $1. Glejte [[Special:Version]].',

'ok'                      => 'V redu',
'pagetitle'               => '$1 – {{SITENAME}}',
'retrievedfrom'           => 'Vzpostavljeno iz »$1«',
'youhavenewmessages'      => 'Imate $1 ($2)',
'newmessageslink'         => 'nova sporočila',
'newmessagesdifflink'     => 'zadnja sprememba',
'youhavenewmessagesmulti' => 'Na $1 imate novo sporočilo',
'editsection'             => 'uredi',
'editold'                 => 'spremeni',
'viewsourceold'           => 'izvorno besedilo',
'editlink'                => 'uredi',
'viewsourcelink'          => 'izvorna koda',
'editsectionhint'         => 'Spremeni razdelek: $1',
'toc'                     => 'Vsebina',
'showtoc'                 => 'prikaži',
'hidetoc'                 => 'skrij',
'collapsible-collapse'    => 'Skrči',
'collapsible-expand'      => 'Razširi',
'thisisdeleted'           => 'Prikažem ali vrnem $1?',
'viewdeleted'             => 'Prikažem $1?',
'restorelink'             => '$1 {{PLURAL:$1|izbrisano redakcijo|izbrisani redakciji|izbrisane redakcije|izbrisanih redakcij}}',
'feedlinks'               => 'Podajanje:',
'feed-invalid'            => 'Neveljavna vrsta naročniškega dovoda.',
'feed-unavailable'        => 'Živi zaznamki niso na voljo',
'site-rss-feed'           => '$1 RSS vir',
'site-atom-feed'          => '$1 Atom vir',
'page-rss-feed'           => '»$1« RSS vir',
'page-atom-feed'          => '»$1« Atom vir',
'red-link-title'          => '$1 (stran ne obstaja)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Stran',
'nstab-user'      => 'Uporabniška stran',
'nstab-media'     => 'Predstavnostna stran',
'nstab-special'   => 'Posebna stran',
'nstab-project'   => 'Projektna stran',
'nstab-image'     => 'Datoteka',
'nstab-mediawiki' => 'Sporočilo',
'nstab-template'  => 'Predloga',
'nstab-help'      => 'Pomoč',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Tako dejanje ne obstaja',
'nosuchactiontext'  => 'Dejanje, ki ga označuje spletni naslov, je napačno.
Morda ste se pri naslovu zatipkali ali pa ste sledili napačni povezavi.
Morda ste odkrili hrošča v programski opremi {{GRAMMAR:genitive|{{SITENAME}}}}.',
'nosuchspecialpage' => 'Zahtevana posebna stran ne obstaja',
'nospecialpagetext' => '<strong>Zahtevali ste neveljavno posebno stran.</strong>

Seznam vseh prepoznanih posebnih strani je na razpolago na strani [[Special:SpecialPages|{{int:specialpages}}]].',

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
'laggedslavemode'      => "'''Opozorilo:''' Stran morda ne vsebuje najnovejših posodobitev.",
'readonly'             => 'Zbirka podatkov je zaklenjena',
'enterlockreason'      => 'Vnesite razlog za zaklenitev in oceno, kdaj bo urejanje spet mogoče',
'readonlytext'         => 'Zbirka podatkov je za urejanja in druge spremembe začasno zaklenjena, najverjetneje zaradi rutinskega vzdrževanja zbirke, po katerem bo nazaj v normalnem stanju.

Sistemski skrbnik, ki jo je zaklenil, je podal naslednjo razlago: $1',
'missing-article'      => 'Podatkovna baza ni našla besedila strani, ki ga bi morala najti, z imenom »$1« $2.

To je ponavadi posledica zastarelih sprememb ali pa je bila stran izbrisana.

Če menite, da vzrok ni v tem, ste morda odkrili hrošč v programju.
Prosimo, da o tem obvestite [[Special:ListUsers/sysop|administratorja]] (ne pozabite navesti točen URL).',
'missingarticle-rev'   => '(redakcija št.: $1)',
'missingarticle-diff'  => '(Primerjanje: $1, $2)',
'readonly_lag'         => 'Podatkovna zbirka se je samodejno zaklenila, dokler se podrejeni strežniki ne uskladijo z glavnim.',
'internalerror'        => 'Notranja napaka',
'internalerror_info'   => 'Notranja napaka: $1',
'fileappenderrorread'  => 'Ni bilo mogoče prebrati »$1« med pripenjanjem.',
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
'cannotdelete'         => 'Strani ali datoteke »$1« ni mogoče izbrisati.
Morda jo je izbrisal že kdo drug.',
'badtitle'             => 'Nepravilen naslov',
'badtitletext'         => 'Navedeni naslov strani je neveljaven, prazen, napačno povezan k drugim jezikom oziroma wikiprojektom.
Morda vsebuje enega ali več nepodprtih znakov.',
'perfcached'           => 'Navedeni podatki so shranjeni v predpomnilniku in morda niso popolnoma posodobljeni.',
'perfcachedts'         => 'Prikazani podatki so shranjeni v predpomnilniku in so bili nazadnje osveženi $1.',
'querypage-no-updates' => 'Posodobitve za to stran so trenutno onemogočene. Tukajšnji podatki se v kratkem ne bodo osvežili.',
'wrong_wfQuery_params' => 'Nepravilni parametri za wfQuery()<br />
Funkcija: $1<br />
Poizvedba: $2',
'viewsource'           => 'Izvorno besedilo',
'viewsourcefor'        => 'za $1',
'actionthrottled'      => 'Dejanje zaustavljeno',
'actionthrottledtext'  => 'Kot ukrep proti smetju, je število izvajanj tega dejanja v časovnem obdobju omejeno, in vi ste ta limit presegli.
Prosimo, poskusite znova čez nekaj minut.',
'protectedpagetext'    => 'Ta stran je bila zaklenjena za preprečitev urejanja.',
'viewsourcetext'       => 'Lahko si ogledujete in kopirate vsebino te stran:',
'protectedinterface'   => 'Prikazana stran vsebuje besedilo vmesnika programja in je zaradi preprečevanja zlorabe zaščitena.',
'editinginterface'     => "'''Opozorilo:''' Urejate stran, ki vsebuje besedilo ali drug element uporabniškega vmesnika programja.
Spremembe te strani bodo vplivale na podobo uporabniškega vmesnika.
Za prevajanje vmesnika se prijavite na [http://translatewiki.net/wiki/Main_Page?setlang=sl&useskin=monobook translatewiki.net].",
'sqlhidden'            => '(Poizvedba SQL je skrita)',
'cascadeprotected'     => 'Ta stran je bila zaščitena pred urejanji, ker je vključena na {{PLURAL:$1|sledečo stran, ki je bila zaščitena|sledeči strani, ki sta bili zaščiteni|sledeče strani, ki so bile zaščitene}} z vključeno kaskadno možnostjo:
$2',
'namespaceprotected'   => "Nimate dovoljenja urejati strani v imenskem prostoru '''$1'''.",
'customcssjsprotected' => 'Nimate pravice urejati te strani, ker vsebuje osebne nastavitve drugega uporabnika.',
'ns-specialprotected'  => 'Posebnih strani ni mogoče urejati.',
'titleprotected'       => "Uporabnik [[User:$1|$1]] je preprečil ustvarjanje strani s takim naslovom.
Podani razlog je bil »''$2''«.",

# Virus scanner
'virus-badscanner'     => "Slaba konfiguracija: neznani virus skener: ''$1''",
'virus-scanfailed'     => 'pregled ni uspel (koda $1)',
'virus-unknownscanner' => 'neznan antivirusni program:',

# Login and logout pages
'logouttext'                 => "'''Odjavili ste se.'''

{{GRAMMAR:tožilnik|{{SITENAME}}}} lahko zdaj uporabljate neprijavljeni ali pa se [[Special:UserLogin|ponovno prijavite]] kot enak ali drug uporabnik.
Morda bodo nekatere strani še naprej prikazane, kot da ste prijavljeni, dokler ne boste izpraznili predpomnilnika brskalnika.",
'welcomecreation'            => '== Dobrodošli, $1! ==
Ustvarili ste račun.
Ne pozabite si prilagoditi vaših [[Special:Preferences|nastavitev {{GRAMMAR:rodilnik|{{SITENAME}}}}]].',
'yourname'                   => 'Uporabniško ime',
'yourpassword'               => 'Geslo',
'yourpasswordagain'          => 'Ponovno vpišite geslo',
'remembermypassword'         => 'Zapomni si me na tem računalniku (za največ $1 {{PLURAL:$1|dan|dneva|dni}})',
'securelogin-stick-https'    => 'Po prijavi ostani povezan preko HTTPS',
'yourdomainname'             => 'Domena',
'externaldberror'            => 'Pri potrjevanju istovetnosti je prišlo do notranje napake ali pa za osveževanje zunanjega računa nimate dovoljenja.',
'login'                      => 'Prijava',
'nav-login-createaccount'    => 'Prijavite se / registrirajte se',
'loginprompt'                => 'Za prijavo v {{GRAMMAR:tožilnik|{{SITENAME}}}} morate imeti omogočene piškotke.',
'userlogin'                  => 'Prijavite se / registrirajte se',
'userloginnocreate'          => 'Prijava',
'logout'                     => 'Odjava',
'userlogout'                 => 'Odjava',
'notloggedin'                => 'Niste prijavljeni',
'nologin'                    => 'Še nimate uporabniškega računa? $1!',
'nologinlink'                => 'Registrirajte se',
'createaccount'              => 'Ustvari račun',
'gotaccount'                 => 'Račun že imate? $1.',
'gotaccountlink'             => 'Prijavite se',
'createaccountmail'          => 'Po e-pošti',
'createaccountreason'        => 'Razlog:',
'badretype'                  => 'Gesli, ki ste ju vnesli, se ne ujemata.',
'userexists'                 => 'Uporabniško ime, ki ste ga vnesli, je že zasedeno.
Prosimo, izberite si drugo.',
'loginerror'                 => 'Napaka ob prijavi',
'createaccounterror'         => 'Ne morem ustvariti računa: $1',
'nocookiesnew'               => 'Uporabniški račun je ustvarjen, vendar niste prijavljeni.
{{SITENAME}} za prijavo uporabnikov uporablja piškotke, ki pa so pri vas onemogočeni.
Prosimo, omogočite jih, nato pa se s svojim uporabniškim imenom in geslom ponovno prijavite.',
'nocookieslogin'             => '{{SITENAME}} za prijavljanje uporabnikov uporablja piškotke.
Ker jih imate onemogočene, vas prosimo, da jih omogočite in se ponovno prijavite.',
'nocookiesfornew'            => 'Uporabniški račun ni bil ustvarjen, ker nismo mogli potrditi njegovega izvora.
Poskrbite, da imate omogočene piškotke, osvežite to stran in poskusite znova.',
'noname'                     => 'Niste vnesli veljavnega uporabniškega imena.',
'loginsuccesstitle'          => 'Uspešno ste se prijavili',
'loginsuccess'               => "'''Sedaj ste prijavljeni v {{GRAMMAR:tožilnik|{{SITENAME}}}} kot »$1«.'''",
'nosuchuser'                 => 'Uporabnik z imenom »$1« ne obstaja.
Uporabniška imena so občutljiva na velikost črk.
Preverite črkovanje ali pa si [[Special:UserLogin/signup|ustvarite nov uporabniški račun]].',
'nosuchusershort'            => 'Uporabnik z imenom »<nowiki>$1</nowiki>« ne obstaja.
Preverite črkovanje.',
'nouserspecified'            => 'Prosimo, vpišite uporabniško ime.',
'login-userblocked'          => 'Ta uporabnik je blokiran. Prijava ni dovoljena.',
'wrongpassword'              => 'Vnesli ste napačno geslo. Prosimo, poskusite znova.',
'wrongpasswordempty'         => 'Vpisali ste prazno geslo. Prosimo, poskusite znova.',
'passwordtooshort'           => 'Geslo mora imeti najmanj $1 {{PLURAL:$1|znak|znaka|znake|znakov|znakov}}.',
'password-name-match'        => 'Vaše geslo se mora razlikovati od vašega uporabniškega imena.',
'password-login-forbidden'   => 'Uporaba tega uporabniškega imena in gesla je prepovedana.',
'mailmypassword'             => 'Pošlji mi novo geslo',
'passwordremindertitle'      => 'Novo začasno geslo za {{GRAMMAR:tožilnik|{{SITENAME}}}}',
'passwordremindertext'       => 'Nekdo (verjetno vi, z IP-naslova $1) je zahteval novo
prijavno geslo za {{GRAMMAR:tožilnik|{{SITENAME}}}} ($4). Ustvarjeno je
bilo začasno geslo za uporabnika »$2«, ki je »$3«. Če ste to
hoteli vi, se zdaj prijavite in izberite novo geslo.
Vaše začasno geslo bo poteklo v {{PLURAL:$5|enem dnevu|$5 dneh}}.

Če je geslo zahteval nekdo drug ali ste se spomnili starega
in ga ne želite več spremeniti, lahko sporočilo prezrete in
se še naprej prijavljate s starim geslom.',
'noemail'                    => 'Elektronska pošta uporabnika »$1« ni zapisana.',
'noemailcreate'              => 'Vnesti morate veljaven e-poštni naslov',
'passwordsent'               => 'Na naslov elektronske pošte, vpisanega za »$1«, smo poslali novo geslo.
Ko ga boste prejeli, se ponovno prijavite.',
'blocked-mailpassword'       => 'Urejanje z vašega IP-naslova je blokirano. Da bi preprečili zlorabe, vam ni dovoljeno tudi uporabljati funkcije za povrnitev pozabljenega gesla.',
'eauthentsent'               => 'E-sporočilo je bilo poslano na navedeni e-naslov.
Če želite tja poslati še katero, sledite navodilom v e-sporočilu, da potrdite lastništvo računa.',
'throttled-mailpassword'     => 'Geselski opomnik je bil v {{PLURAL:$1|zadnji uri|zadnjih $1 urah}} že poslan.
Za preprečevanje zlorab je lahko na {{PLURAL:$1|uro|$1 uri|$1 ure|$1 ur}} poslano samo eno opozorilo.',
'mailerror'                  => 'Napaka pri pošiljanju pošte: $1',
'acct_creation_throttle_hit' => 'Obiskovalci {{GRAMMAR:rodilnik|{{SITENAME}}}} so s tem IP-naslovom v zadnjih 24 urah ustvarili že $1 {{PLURAL:$1|uporabniški račun|uporabniška računa|uporabniške račune|uporabniških računov|uporabniških računov}} in s tem dosegli največje dopustno število v omenjenem časovnem obdobju. Novih računov zato s tem IP-naslovom trenutno žal ne morete več ustvariti.

== Urejate prek posredniškega strežnika? ==

Če urejate prek AOL ali iz Bližnjega vzhoda, Afrike, Avstralije, Nove Zelandije ali iz šole, knjižnice ali podjetja, si IP-naslov morda delite z drugimi uporabniki. Če je tako, ste to sporočilo morda prejeli, čeprav niste ustvarili še nobenega računa. Znova se lahko poskusite registrirati po nekaj urah.',
'emailauthenticated'         => 'Vaš e-poštni naslov je bil potrjen dne $2 ob $3.',
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
'usernamehasherror'          => "Uporabniško ime ne sme vsebovati ''hash'' znakov",
'login-throttled'            => 'Nedavno ste izvedli preveč poskusov prijave.
Prosimo počakajte, preden poskusite znova.',
'loginlanguagelabel'         => 'Jezik: $1',
'suspicious-userlogout'      => 'Vaša zahteva za odjavo je bila zavrnjena, saj kaže, da je bila poslana iz pokvarjenega brskalnika ali proxyja s predpomnilnikom.',

# E-mail sending
'php-mail-error-unknown' => 'Neznana napaka v funkciji PHP mail()',

# JavaScript password checks
'password-strength'            => 'Ocenjena moč gesla: $1',
'password-strength-bad'        => 'SLABO',
'password-strength-mediocre'   => 'povprečno',
'password-strength-acceptable' => 'sprejemljivo',
'password-strength-good'       => 'dobro',
'password-retype'              => 'Ponovno vpišite geslo tukaj',
'password-retype-mismatch'     => 'Gesli se ne ujemata',

# Password reset dialog
'resetpass'                 => 'Spremeni geslo',
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
'resetpass-submit-cancel'   => 'Prekliči',
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
'math_tip'        => 'Matematična enačba (LaTeX)',
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
'subject'                          => 'Zadeva/naslov:',
'minoredit'                        => 'Manjše urejanje',
'watchthis'                        => 'Opazuj članek',
'savearticle'                      => 'Shrani stran',
'preview'                          => 'Predogled',
'showpreview'                      => 'Prikaži predogled',
'showlivepreview'                  => 'Predogled v živo',
'showdiff'                         => 'Prikaži spremembe',
'anoneditwarning'                  => "'''Opozorilo''': niste prijavljeni. V zgodovino strani se bo zapisal vaš IP-naslov.",
'anonpreviewwarning'               => 'Niste prijavljeni. Ob spremembi strani se bo vaš IP-naslov zapisal v zgodovini urejanja te strani.',
'missingsummary'                   => "'''Opozorilo:''' Niste napisali povzetka urejanja. Ob ponovnem kliku gumba ''Shrani'' se bo vaše urejanje shranilo brez njega.",
'missingcommenttext'               => 'Prosimo, vpišite v spodnje polje komentar.',
'missingcommentheader'             => "'''Opozorilo:''' Niste vnesli zadeve/naslova za ta komentar.
Če boste ponovno kliknili »{{int:savearticle}}«, bo vaše urejanje shranjeno brez le-tega.",
'summary-preview'                  => 'Predogled povzetka',
'subject-preview'                  => 'Predogled zadeve/naslova:',
'blockedtitle'                     => 'Uporabnik je blokiran',
'blockedtext'                      => "'''Urejanje z vašim uporabniškim imenom oziroma IP-naslovom je bilo onemogočeno.'''

Blokiral vas je $1.
Podan razlog je ''$2''.

* Začetek blokade: $8
* Potek blokade: $6
* Namen blokade: $7

O blokiranju se lahko pogovorite z $1 ali katerim drugim [[{{MediaWiki:Grouppage-sysop}}|administratorjem]].
Vedite, da lahko ukaz »Pošlji uporabniku e-pismo« uporabite le, če ste v [[Special:Preferences|nastavitvah]] vpisali in potrdili svoj elektronski naslov ter le-ta ni bil blokiran.
Vaš IP-naslov je $3, številka blokade pa #$5.
Prosimo, vključite ju v vse morebitne poizvedbe.",
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
'blockededitsource'                => "Besedilo '''vaših urejanj''' v '''$1''' je prikazano spodaj:",
'whitelistedittitle'               => 'Za urejanje se morate prijaviti',
'whitelistedittext'                => 'Za urejanje strani se morate $1.',
'confirmedittext'                  => 'Pred urejanjem strani morate potrditi svoj e-poštni naslov.
Prosimo, da ga z uporabo [[Special:Preferences|uporabniških nastavitev]] vpišete in potrdite.',
'nosuchsectiontitle'               => 'Ne najdem razdelka',
'nosuchsectiontext'                => 'Poskušali ste urediti razdelek, ki ne obstaja.
Morda je bil premaknjen ali izbrisan, medtem ko ste gledali stran.',
'loginreqtitle'                    => 'Treba se je prijaviti',
'loginreqlink'                     => 'prijaviti',
'loginreqpagetext'                 => 'Za ogled drugih strani se morate $1.',
'accmailtitle'                     => 'Geslo je poslano.',
'accmailtext'                      => "Naključno generirano geslo za [[User talk:$1|$1]] je poslano na $2.

Geslo za ta račun lahko po prijavi ''[[Special:ChangePassword|spremenite]]''.",
'newarticle'                       => '(Nov)',
'newarticletext'                   => "Sledili ste povezavi na stran, ki še ne obstaja.
Da bi stran ustvarili, vnesite v spodnji obrazec besedilo
(za več informacij glej [[{{MediaWiki:Helppage}}|pomoč]]).
Če ste sem prišli po pomoti, v svojem brskalniku kliknite gumb ''Nazaj''.",
'anontalkpagetext'                 => "---- ''To je pogovorna stran brezimnega uporabnika, ki si še ni ustvaril računa ali pa ga ne uporablja. Zaradi tega moramo uporabiti IP-naslov za njegovo/njeno ugotavljanje istovetnosti. Takšen IP-naslov si lahko deli več uporabnikov. Če ste brezimni uporabnik in menite, da so nepomembne pripombe namenjene vam, prosimo [[Special:UserLogin|ustvarite račun]] ali pa se [[Special:UserLogin/signup|vpišite]], da preprečite zmedo z drugimi nepodpisanimi uporabniki.''",
'noarticletext'                    => 'Na tej strani ni trenutno nobenega besedila. Naslov strani lahko poskusite [[Special:Search/{{PAGENAME}}|poiskati]] na drugih straneh, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} v dnevniških zapisih] ali pa [{{fullurl:{{FULLPAGENAME}}|action=edit}} stran uredite]</span>.',
'noarticletext-nopermission'       => 'Na tej strani ni trenutno nobenega besedila.
Lahko poskusite [[Special:Search/{{PAGENAME}}|poiskati naslov te strani]] v drugih straneh, ali <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} poiskati v povezanih dnevniških zapisih]</span>.',
'userpage-userdoesnotexist'        => 'Uporabniški račun »$1« ni registriran.
Prosimo preverite, ali res želite ustvariti/urediti to stran.',
'userpage-userdoesnotexist-view'   => 'Uporabniški račun »$1« ni registriran.',
'blocked-notice-logextract'        => 'Ta uporabnik je trenutno blokiran.
Najnovejši vnos v dnevniku blokad je prikazan spodaj:',
'clearyourcache'                   => "'''Opomba:''' Da bodo spremembe prišle do veljave, po shranitvi izpraznite predpomnilnik svojega brskalnika: '''Mozilla/Safari:''' držite ''Shift'' in kliknite ''Reload'' (ali pritisnite ''Ctrl-Shift-R''), '''Internet Explorer:''' ''Ctrl-F5'', '''Opera/Konqueror:''' ''F5''.",
'usercssyoucanpreview'             => "'''Nasvet:''' Za preizkušanje svojega novega CSS pred shranjevanjem uporabite gumb »{{int:showpreview}}«.",
'userjsyoucanpreview'              => "'''Nasvet:''' Za preizkušanje svojega novega JavaScripta pred shranjevanjem uporabite gumb »{{int:showpreview}}«.",
'usercsspreview'                   => "'''Svoj uporabniški CSS le predogledujete.'''
'''Ni še bil shranjen!'''",
'userjspreview'                    => "'''Ne pozabite, da svoj uporabniški JavaScript le preizkušate/predogledujete.'''
'''Ni še bil shranjen!'''",
'sitecsspreview'                   => "'''Ne pozabite, da ta CSS samo preizkušate.'''
'''Ni še bil shranjen!'''",
'sitejspreview'                    => "'''Ne pozabite, da kodo tega JavaScripta samo preizkušate.'''
'''Ni še bila shranjena!'''",
'userinvalidcssjstitle'            => "'''Opozorilo:''' Koža »$1« ne obstaja.
Vedite, da .css in .js strani po meri uporabljajo naslov z malo začetnico, npr. {{ns:user}}:Blabla/vector.css namesto {{ns:user}}:Blabla/Vector.css.",
'updated'                          => '(Posodobljeno)',
'note'                             => "'''Opomba:'''",
'previewnote'                      => "'''Stran le predogledujete in še ni shranjena!'''",
'previewconflict'                  => 'V prikazanem predogledu je v zgornjem predelu urejanja navedeno besedilo, kakor se bo prikazalo, če ga boste shranili.',
'session_fail_preview'             => "'''Oprostite! Zaradi izgube podatkov o seji nam vašega urejanja žal ni uspelo obdelati.'''
Prosimo, poskusite znova.
Če bo spet prišlo do napake, se [[Special:UserLogout|odjavite]] in ponovno prijavite.",
'session_fail_preview_html'        => "'''Oprostite! Zaradi izgube podatkov o seji nam vašega urejanja ni uspelo obdelati.'''

''Ker ima {{SITENAME}} omogočen surovi HTML, je predogled zaradi preprečevanja napadov z JavaScriptom skrit.''

'''Če gre za dobronameren poskus urejanja, vas prosimo, da poskusite znova.'''
Če bo spet prišlo do napake, se [[Special:UserLogout|odjavite]] in ponovno prijavite.",
'token_suffix_mismatch'            => "'''Vaše urejanje je bilo zavrnjeno, ker je vaš odjemalec pokvaril ločila v urejevalnem zahtevku.'''
Urejanje je bilo zavrnjeno z namenom preprečitve okvare v besedilu strani.
Največkrat je razlog uporaba hroščato spletno anonimizacijsko storitev.",
'editing'                          => 'Urejanje $1',
'editingsection'                   => 'Urejanje $1 (razdelek)',
'editingcomment'                   => 'Urejanje $1 (nov razdelek)',
'editconflict'                     => 'Navzkrižje urejanj: $1',
'explainconflict'                  => "Med vašim urejanjem je stran spremenil nekdo drug.
Zgornje urejevalno polje vsebuje njeno trenutno vsebino.
Vaše spremembe so prikazane v spodnjem polju, ki jih boste morali združiti z obstoječim besedilom.
'''Samo''' besedilo v zgornjem polju bo shranjeno, ko boste izbrali ukaz »{{int:savearticle}}«.",
'yourtext'                         => 'Vaše besedilo',
'storedversion'                    => 'Shranjena redakcija',
'nonunicodebrowser'                => "'''Opozorilo: Vaš brskalnik ne podpira Unicode.'''
Za obhod te težave se bodo ne-ASCII-znaki v urejevalnem polju spodaj pojavili kot šestnajstiške kode.",
'editingold'                       => "'''Opozorilo: Urejate staro redakcijo strani.'''
Če jo boste shranili, bodo vse poznejše spremembe razveljavljene.",
'yourdiff'                         => 'Primerjava',
'copyrightwarning'                 => "Vsi prispevki k {{GRAMMAR:dajalnik|{{SITENAME}}}} se obravnavajo kot objave pod pogoji $2 (za podrobnosti glej $1). Če niste pripravljeni na neusmiljeno urejanje in prosto razširjanje vašega gradiva, ga ne prispevajte.<br />
Poleg tega zagotavljate, da ste prispevke napisali oziroma ustvarili sami ali pa prepisali iz javno dostopnega ali podobnega prostega vira.
'''Ne dodajajte avtorsko zaščitenega dela brez dovoljenja!'''",
'copyrightwarning2'                => "Prosimo, upoštevajte, da se vsi prispevki k {{GRAMMAR:dajalnik|{{SITENAME}}}} lahko urejajo, spreminjajo ali odstranijo s strani drugih uporabnikov
Če niste pripravljeni na neusmiljeno urejanje in prosto razširjanje vašega gradiva, ga ne prispevajte.<br />
Poleg tega zagotavljate, da ste prispevke napisali oziroma ustvarili sami ali pa prepisali iz javno dostopnega ali podobnega prostega vira (za podrobnosti glej $1).
'''Ne dodajajte avtorsko zaščitenega dela brez dovoljenja!'''",
'longpageerror'                    => "'''Napaka: Predloženo besedilo je dolgo $1 {{PLURAL:$1|kilobajt|kilobajta|kilobajte|kilobajtov|kilobajtov}}, s čimer presega največjo dovoljeno dolžino $2 {{PLURAL:$2|kilobajta|kilobajtov|kilobajtov|kilobajtov|kilobajtov}}.'''
Zato ga ni mogoče shraniti.",
'readonlywarning'                  => "'''Opozorilo: Zbirka podatkov je zaradi vzdrževanja začasno zaklenjena, kar pomeni, da sprememb trenutno ne morete shraniti. Prosimo, prenesite besedilo v urejevalnik in ga dodajte pozneje.'''

Sistemski skrbnik, ki jo je zaklenil, je podal naslednjo razlago: $1",
'protectedpagewarning'             => "'''Opozorilo: Stran je bila zaklenjena in jo lahko urejajo le uporabniki z administratorskimi pravicami.'''
Zadnji vnos v dnevnik je naveden spodaj:",
'semiprotectedpagewarning'         => "'''Opomba:''' Stran je bila zaklenjena in jo lahko urejajo le registrirani uporabniki.
Zadnji vnos v dnevnik je naveden spodaj:",
'cascadeprotectedwarning'          => "'''Opozorilo:''' Ta stran je zaklenjena, tako da jo lahko urejajo le administratorji, saj je bila vključena med {{PLURAL:$1|sledečo stran|sledeči strani|sledeče strani}} s kaskadno zaščito:",
'titleprotectedwarning'            => "'''Opozorilo: Stran je bila zaklenjena in jo lahko urejajo le uporabniki s [[Special:ListGroupRights|specifičnimi pravicami]].'''
Za sklic je priskrbljen spodnji dnevnik vnosov:",
'templatesused'                    => '{{PLURAL:$1|Predloga, uporabljena|Predlogi, uporabljeni|Predloge, uporabljene}} na strani:',
'templatesusedpreview'             => '{{PLURAL:$1|Predloga, uporabljena|Predlogi, uporabljeni|Predloge, uporabljene}} v predogledu:',
'templatesusedsection'             => '{{PLURAL:$1|Predloga, uporabljena|Predlogi, uporabljeni|Predloge, uporabljene}} v tem delu:',
'template-protected'               => '(zaščitena)',
'template-semiprotected'           => '(delno zaščitena)',
'hiddencategories'                 => 'Ta stran je v vsebovana v {{PLURAL:$1|1 skriti kategoriji|$1 skritih kategorijah}}:',
'edittools'                        => '<!-- To besedilo bo prikazano pod urejevalnim poljem in poljem za nalaganje. -->',
'nocreatetitle'                    => 'Ustvarjanje strani je omejeno',
'nocreatetext'                     => '{{SITENAME}} ima omejeno zmožnost za ustvarjanje novih strani.
Lahko se vrnete nazaj in urejate že obstoječe strani, ali pa se [[Special:UserLogin|prijavite ali ustvarite račun]].',
'nocreate-loggedin'                => 'Nimate pravic, da bi ustvarjali nove strani.',
'sectioneditnotsupported-title'    => 'Urejanje razdelkov ni podprto',
'sectioneditnotsupported-text'     => 'Urejanje razdelkov ni podprto na tej strani.',
'permissionserrors'                => 'Napake dovoljenj',
'permissionserrorstext'            => 'Za izvedbo tega nimate dovoljenja zaradi {{PLURAL:$1|naslednjega razloga|naslednjih razlogov|naslednjih razlogov|naslednjih razlogov|naslednjih razlogov}}:',
'permissionserrorstext-withaction' => 'Nimate dovoljenja za $2, zaradi {{PLURAL:$1|naslednjega razloga|naslednjih $1 razlogov|naslednjih $1 razlogov|naslednjih $1 razlogov}}:',
'recreate-moveddeleted-warn'       => "'''Opozorilo: Pišete stran, ki je bila nekoč že izbrisana.'''

Premislite preden nadaljujete s pisanjem, morda bo stran zaradi istih razlogov ponovno odstranjena.
Spodaj je prikazan dnevnik brisanja in prestavljanja:",
'moveddeleted-notice'              => 'Ta stran je bila izbrisana.
Dnevnik brisanja in prestavljanja strani je na voljo spodaj.',
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
'parser-template-recursion-depth-warning' => 'Prekoračena globina rekurzije predlog ($1)',
'language-converter-depth-warning'        => 'Prekoračena globina pretvorbe jezikov ($1)',

# "Undo" feature
'undo-success' => 'Urejanje ste razveljavili. Prosim, potrdite in nato shranite spodnje spremembe.',
'undo-failure' => 'Zaradi navzkrižij urejanj, ki so se vmes pojavila, tega urejanja ni moč razveljaviti.',
'undo-norev'   => 'Urejanja ni mogoče razveljaviti, ker ne obstaja ali je bilo izbrisano.',
'undo-summary' => 'Redakcija $1 uporabnika [[Special:Contributions/$2|$2]] ([[User talk:$2|pogovor]]) razveljavljena',

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
'histlegend'             => "Izbira primerjave: označite okroglo polje ob redakciji za primerjavo in stisnite enter ali gumb na dnu strani.<br />
Legenda: '''({{int:cur}})''' = primerjava s trenutno redakcijo, '''({{int:last}})''' = primerjava s prejšnjo redakcijo, '''{{int:minoreditletter}}''' = manjše urejanje.",
'history-fieldset-title' => 'Zgodovina poizvedovanj',
'history-show-deleted'   => 'Samo izbrisani',
'histfirst'              => 'Najstarejše',
'histlast'               => 'Najnovejše',
'historysize'            => '({{PLURAL:$1|$1 zlog|$1 zloga|$1 zlogi|$1 zlogov}})',
'historyempty'           => '(prazno)',

# Revision feed
'history-feed-title'          => 'Zgodovina strani',
'history-feed-description'    => 'Zgodovina navedene strani {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'history-feed-item-nocomment' => '$1 ob $2',
'history-feed-empty'          => 'Zahtevana stran ne obstaja.
Morda je bila izbrisana iz wikija ali pa jo je kdo preimenoval.
Prosimo, poskusite [[Special:Search|poiskati v wikiju]] ustrezajoče nove strani.',

# Revision deletion
'rev-deleted-comment'         => '(pripomba je bila odstranjena)',
'rev-deleted-user'            => '(uporabniško ime je bilo odstranjeno)',
'rev-deleted-event'           => '(dnevniški vnos je odstranjen)',
'rev-deleted-user-contribs'   => '[uporabniško ime ali IP naslov odstranjeni - urajenje skrito v prispevkih]',
'rev-deleted-text-permission' => "Prikazana redakcija je bila '''izbrisana'''.
Podrobnosti so na razpolago v [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} dnevniku brisanja].",
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
'rev-suppressed-no-diff'      => "Ogled redakcije ni mogoč, ker je bila ena od sprememb '''izbrisana'''.",
'rev-deleted-unhide-diff'     => "Ena od sprememb v tej redakciji je bila '''izbrisana'''.
Podrobnosti so morda navedene v [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} dnevniku brisanja].
Kot administrator lahko še vedno [$1 pogledate to redakcijo], če želite nadaljevati.",
'rev-suppressed-unhide-diff'  => "Ena od sprememb v tej redakciji je bila '''zadržana'''.
Podrobnosti so morda navedene v [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} dnevniku zadržkov].
Kot administrator lahko še vedno [$1 pogledate to redakcijo], če želite nadaljevati.",
'rev-deleted-diff-view'       => "Ena od sprememb v tej redakciji je bila '''izbrisana'''.
Kot administrator si to redakcijo lahko ogledate; podrobnosti lahko najdete v [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} dnevniku brisanja].",
'rev-suppressed-diff-view'    => "Ena od sprememb v tej redakciji je bila '''zadržana'''.
Kot administrator si to redakcijo lahko ogledate; podrobnosti lahko najdete v [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} dnevniku zadržkov].",
'rev-delundel'                => 'pokaži/skrij',
'rev-showdeleted'             => 'prikaži',
'revisiondelete'              => 'Izbriši/obnovi redakcije',
'revdelete-nooldid-title'     => 'Napačna ciljna redakcija',
'revdelete-nooldid-text'      => 'Bodisi niste navedli ciljne spremembe, navedena sprememba ne obstaja, ali pa poskušate skriti trenutno spremembo.',
'revdelete-nologtype-title'   => 'Tip dnevnik ni podan',
'revdelete-nologtype-text'    => 'Niste navedli vrste dnevnika za prikaz.',
'revdelete-nologid-title'     => 'Neveljaven dnevniški vnos',
'revdelete-nologid-text'      => 'Bodisi niste navedli ciljnega dnevniškega dogodka za izvedbo funkcije, ali pa naveden vnos ne obstaja.',
'revdelete-no-file'           => 'Navedena datoteka ne obstaja.',
'revdelete-show-file-confirm' => 'Ali ste prepričani da si želite ogledati izbrisano verzijo datoteke "<nowiki>$1</nowiki>" od $2 ob $3?',
'revdelete-show-file-submit'  => 'Da',
'revdelete-selected'          => "'''{{PLURAL:$2|Izbrana redakcija|Izbrani redakciji|Izbrane redakcije}} strani [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Izbran dnevniški dogodek|Izbrana dnevniška dogodka|Izbrani dnevniški dogodki}}:'''",
'revdelete-text'              => "'''Izbrisane redakcije in dogodki bodo v zgodovini strani in dnevniki še vedno navedene, vendar bo njihova vsebina za javnost nedostopna.'''
Do skrite vsebine bodo še vedno lahko dostopali drugi administratorji {{GRAMMAR:rodilnik|{{SITENAME}}}} in jo z uporabo istega vmesnika tudi obnovili, razen kjer bodo uveljavljene dodatne omejitve.",
'revdelete-confirm'           => 'Prosim potrdite da nameravate to storiti, da se zavedate posledic in da to počnete v skladu s [[{{MediaWiki:Policy-url}}|politiko]].',
'revdelete-suppress-text'     => "Zadrževanje naj bi bilo uporabljeno '''le''' v sledečih primerih:
* Potencialni klevetniški podatki
* Neprimerni osebni podatki
*: ''domači naslovi in telefonske številke, številke socialnega zavarovanja, etc.''",
'revdelete-legend'            => 'Nastavi omejitve vidnosti',
'revdelete-hide-text'         => 'Skrij besedilo redakcije',
'revdelete-hide-image'        => 'Skrij vsebino datoteke.',
'revdelete-hide-name'         => 'Skrij dejanje in cilj',
'revdelete-hide-comment'      => 'Skrij povzetek urejanja',
'revdelete-hide-user'         => 'Skrij uporabniško ime/IP-naslov urejevalca',
'revdelete-hide-restricted'   => 'Zadrži podatke od administratorjev kakor tudi od ostalih',
'revdelete-radio-same'        => '(ne spremeni)',
'revdelete-radio-set'         => 'Da',
'revdelete-radio-unset'       => 'Ne',
'revdelete-suppress'          => 'Zadrži podatke od administratorjev kakor tudi od ostalih',
'revdelete-unsuppress'        => 'Odpraviti omejitve na obnovljenih redakcijah.',
'revdelete-log'               => 'Razlog:',
'revdelete-submit'            => 'Uporabi za {{PLURAL:$1|izbrano redakcijo|izbrani redakciji|izbrane redakcije}}',
'revdelete-logentry'          => 'sprememba vidnosti redakcij strani [[$1]]',
'logdelete-logentry'          => 'je spremenil vidnost dogodka [[$1]]',
'revdelete-success'           => "'''Vidnost redakcije je bila uspešno nastavljena.'''",
'revdelete-failure'           => "'''Vidnost redakcije ni bilo mogoče nastaviti:'''
$1",
'logdelete-success'           => "'''Vidnost dnevnika je bila uspešno nastavljena.'''",
'logdelete-failure'           => "'''Vidnost dnevnika ne more biti nastavljena!:'''
$1",
'revdel-restore'              => 'Spremeni vidnost',
'revdel-restore-deleted'      => 'izbrisane redakcije',
'revdel-restore-visible'      => 'vidne redakcije',
'pagehist'                    => 'Zgodovina strani',
'deletedhist'                 => 'Zgodovina brisanja',
'revdelete-content'           => 'vsebino',
'revdelete-summary'           => 'povzetek urejanja',
'revdelete-uname'             => 'uporabniško ime',
'revdelete-restricted'        => 'uveljavljene omejitve administratorjev',
'revdelete-unrestricted'      => 'odstranjene omejitve administratorjev',
'revdelete-hid'               => 'skril $1',
'revdelete-unhid'             => 'prikazal $1',
'revdelete-log-message'       => '$1 za $2 {{PLURAL:$2|redakcijo|redakciji|redakcije|redakcij}}',
'logdelete-log-message'       => '$1 za $2 {{PLURAL:$2|dogodek|dogodka|dogodke|dogodkov}}',
'revdelete-hide-current'      => 'Napaka pri skrivanju predmeta z dne $1, $2: gre za trenutno redakcijo.
Te ni mogoče skriti.',
'revdelete-show-no-access'    => 'Napaka pri prikazovanju predmeta z dne $1, $2: predmet je bil označen kot »omejen«.
Nimate dostopa do njega.',
'revdelete-modify-no-access'  => 'Napaka pri spreminjanju predmeta z dne $1, $2: predmet je bil označen kot »omejen«.
Nimate dostopa do njega.',
'revdelete-modify-missing'    => 'Napaka pri spreminjanju predmeta ID $1: manjka v podatkovni bazi!',
'revdelete-no-change'         => "'''Opozorilo:''' predmet z dne $1 ob $2 že ima zahtevane nastavitve vidljivosti.",
'revdelete-concurrent-change' => 'Napaka pri spreminjanju predmeta z dne $1, $2: medtem ko ste nameravali spremeniti njegovo stanje, ga je spremenil že nekdo drug.
Prosimo, preverite dnevnik.',
'revdelete-only-restricted'   => 'Napaka pri skrivanju predmeta z dne $1, $2: ne morete spremeniti vidnosti predmeta pred administratorji brez izbire ene od drugih možnosti vidnosti.',
'revdelete-reason-dropdown'   => '* Pogosti razlogi za izbris
** Kršitev avtorskih pravic
** Neprimerni osebni podatki
** Morebitni žaljivi podatki',
'revdelete-otherreason'       => 'Drug/dodaten razlog:',
'revdelete-reasonotherlist'   => 'Drug razlog',
'revdelete-edit-reasonlist'   => 'Uredi razloge za brisanje',
'revdelete-offender'          => 'Avtor redakcije:',

# Suppression log
'suppressionlog'     => 'Dnevnik vračanj',
'suppressionlogtext' => 'Spodaj je seznam izbrisov in blokiranj, ki vključuje vsebino skrito pred administratorji.
Oglejte si [[Special:IPBlockList|seznam blokiranih IP-jev]] za seznam trenutno aktivnih prepovedi in blokiranj.',

# Revision move
'moverevlogentry'              => 'premaknil(-a) $3 {{PLURAL:$3|redakcijo|redakciji|redakcije|redakcij}} z $1 na $2',
'revisionmove'                 => 'Premakni redakcije z »$1«',
'revmove-explain'              => 'Naslednje redakcije bodo prestavljene s strani $1 na določeno ciljno stran. Če cilj ne obstaja, bo ustvarjen. V nasprotnem primeru bodo redakcije združene z zgodovino strani.',
'revmove-legend'               => 'Določite ciljno stran in povzetek',
'revmove-submit'               => 'Prestavi redakcije na izbrano stran',
'revisionmoveselectedversions' => 'Prestavi izbrane redakcije',
'revmove-reasonfield'          => 'Razlog:',
'revmove-titlefield'           => 'Ciljna stran:',
'revmove-badparam-title'       => 'Nepravilni parametri',
'revmove-badparam'             => 'Vaša zahteva vsebuje neveljavne ali pomanjkljive parametre. Prosimo, izberite »nazaj« in poskusite znova.',
'revmove-norevisions-title'    => 'Neveljavna ciljna redakcija',
'revmove-norevisions'          => 'Niste določili ene ali več ciljnih redakcij za izvedbo te funkcije ali pa izbrana redakcija ne obstaja.',
'revmove-nullmove-title'       => 'Nepravilen naslov',
'revmove-nullmove'             => 'Izvorna in ciljna stran sta isti. Prosimo, kliknite »nazaj« in vnesite ime strani drugačno od »$1«.',
'revmove-success-existing'     => '$1 {{PLURAL:$1|redakcija je bila s strani [[$2]] prestavljena|redakciji sta bili s strani [[$2]] prestavljeni|redakcije so bile s strani [[$2]] prestavljene|redakcij je bilo s strani [[$2]] prestavljenih}} na obstoječo stran [[$3]].',
'revmove-success-created'      => '$1 {{PLURAL:$1|redakcija je bila s strani [[$2]] prestavljena|redakciji sta bili s strani [[$2]] prestavljeni|redakcije so bile s strani [[$2]] prestavljene|redakcij je bilo s strani [[$2]] prestavljenih}} na novo ustvarjeno stran [[$3]].',

# History merging
'mergehistory'                     => 'Združi zgodovine strani',
'mergehistory-header'              => 'Ta stran omogoča združevanje redakcij zgodovine ene izvorne strani v novejšo stran.
Poskrbite, da bo sprememba ohranila povezanost zgodovinskih strani.',
'mergehistory-box'                 => 'Združite redakcije dveh strani:',
'mergehistory-from'                => 'Izvorna stran:',
'mergehistory-into'                => 'Ciljna stran:',
'mergehistory-list'                => 'Redakcije, ki jih je možno združiti',
'mergehistory-merge'               => 'Sledeče redakcije [[:$1]] so lahko združene z [[:$2]].
Uporabite stolpec z okroglimi gumbi za združevanje samo redakcij, ki so bile ustvarjene pred in vključno z navedenim časom.
Upoštevajte, da bo uporaba navigacijskih gumbov ponastavila ta stolpec.',
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
'difference-multipage'     => '(Razlika med stranmi)',
'lineno'                   => 'Vrstica $1:',
'compareselectedversions'  => 'Primerjaj izbrani redakciji',
'showhideselectedversions' => 'Prikaži/skrij izbrane redakcije',
'editundo'                 => 'razveljavi',
'diff-multi'               => '({{PLURAL:$1|$1 vmesna redakcija|$1 vmesni redakciji|$1 vmesne redakcije|$1 vmesnih redakcij}} {{PLURAL:$2|$2 uporabnika|$2 uporabnikov}} {{PLURAL:$1|ni prikazana|nista prikazani|niso prikazane|ni prikazanih}})',
'diff-multi-manyusers'     => '({{PLURAL:$1|$1 vmesna redakcija|$1 vmesni redakciji|$1 vmesne redakcije|$1 vmesnih redakcij}} več kot $2 {{PLURAL:$2|uporabnika|uporabnikov}} {{PLURAL:$1|ni prikazana|nista prikazani|niso prikazane|ni prikazanih}})',

# Search results
'searchresults'                    => 'Izid iskanja',
'searchresults-title'              => 'Zadetki za povpraševanje »$1«',
'searchresulttext'                 => 'Za več informacij o iskanju v {{GRAMMAR:dajalnik|{{SITENAME}}}} si oglejte [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => "Za povpraševanje »'''[[:$1]]'''« ([[Special:Prefixindex/$1|vse strani začenši z »$1«]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|vse strani, ki se povezujejo na »$1«]])",
'searchsubtitleinvalid'            => "Iskali ste '''$1'''",
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
'searchmenu-new-nocreate'          => '»$1« ni veljavno ime strani ali pa je ne morete ustvariti.',
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
'search-result-category-size'      => '$1 {{PLURAL:$1|član|člana|člani|članov}} ($1 {{PLURAL:$2|podkategorija|podkategoriji|podkategorije|podkategorij}}, $1 {{PLURAL:$3|datoteka|datoteki|datoteke|datotek}})',
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
'mwsuggest-disable'                => 'Onemogoči predloge Ajax',
'searcheverything-enable'          => 'Iskanje po vseh imenskih prostorih',
'searchrelated'                    => 'povezano',
'searchall'                        => 'vse',
'showingresults'                   => "Prikazujem do '''$1''' {{PLURAL:$1|zadetek|zadetka|zadetke|zadetkov}}, začenši s št. '''$2'''.",
'showingresultsnum'                => "Prikazujem '''$3''' {{PLURAL:$3|zadetek|zadetka|zadetke|zadetkov|zadetkov}}, začenši s št. '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Zadetek '''$1''' od '''$3'''|Zadetki '''$1 - $2''' od skupno '''$3'''}} za povpraševanje '''$4'''",
'nonefound'                        => "'''Opomba''': neuspešna poizvedovanja so pogosta ob iskanju vsakdanjih besed, na primer ''imeti'' in ''iz'', ki jih ni na seznamu. Ker gre za zelo pogoste besede, boste skoraj zagotovo iskali uspešneje z zožitvijo tematskega področja. Poskusiti dodati predpono ''all:'' in tako iskati po celotni vsebini (vključno  z pogovornimi stranmi, predlogami, itd.) ali pa za predpono uporabite določen imenski prostor.",
'search-nonefound'                 => 'Ni bilo zadetkov, ki ustrezajo poizvedbi.',
'powersearch'                      => 'Napredno iskanje',
'powersearch-legend'               => 'Napredno iskanje',
'powersearch-ns'                   => 'Iskanje v imenskih prostorih:',
'powersearch-redir'                => 'Seznam preusmeritev',
'powersearch-field'                => 'Išči',
'powersearch-togglelabel'          => 'Izberi:',
'powersearch-toggleall'            => 'Vse',
'powersearch-togglenone'           => 'Nič',
'search-external'                  => 'Zunanji iskalnik',
'searchdisabled'                   => 'Iskanje po {{GRAMMAR:dajalnik|{{SITENAME}}}} je onemogoočeno.
Medtem lahko iščete preko Googla.
Upoštevajte, da so njihovi podatki vsebine {{GRAMMAR:rodilnik|{{SITENAME}}}} morda zastareli.',

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
'prefsnologintext'              => 'Za spreminjanje uporabniških nastavitev morate biti <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} prijavljeni]</span>.',
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
'prefs-watchlist-token'         => 'Ključ spiska nadzorov:',
'prefs-misc'                    => 'Druge nastavitve',
'prefs-resetpass'               => 'Spremeni geslo',
'prefs-email'                   => 'Možnosti e-pošte',
'prefs-rendering'               => 'Videz',
'saveprefs'                     => 'Shrani',
'resetprefs'                    => 'Počisti neshranjene spremembe',
'restoreprefs'                  => 'Obnovi vse privzete nastavitve',
'prefs-editing'                 => 'Urejanje',
'prefs-edit-boxsize'            => 'Velikost okna za urejanje.',
'rows'                          => 'Vrstic:',
'columns'                       => 'Stolpcev:',
'searchresultshead'             => 'Nastavitve poizvedovanja',
'resultsperpage'                => 'Prikazanih zadetkov na stran:',
'contextlines'                  => 'Vrstic na zadetek:',
'contextchars'                  => 'Znakov na vrstico:',
'stub-threshold'                => 'Prag označevanja <a href="#" class="stub">škrbin</a> (v bajtih):',
'stub-threshold-disabled'       => 'Onemogočeno',
'recentchangesdays'             => 'Število dni prikazanih v zadnjih spremembah:',
'recentchangesdays-max'         => 'Največ $1 {{PLURAL:$1|dan|dneva|dnevi|dni}}',
'recentchangescount'            => 'Privzeto število prikazanih urejanj:',
'prefs-help-recentchangescount' => 'To vključuje zadnje spremembe, zgodovine strani in dnevniške zapise.',
'prefs-help-watchlist-token'    => 'Izpolnjevanje tega polja s skrivnim ključem bo ustvarilo vir RSS za vaš spisek nadzorov.
Kdorkoli pozna ta ključ bo lahko bral vaš spisek nadzorov, zato izbrite varen in čim daljši ključ.
Tukaj je naključno ustvarjena vrednost, ki jo lahko uporabite: $1',
'savedprefs'                    => 'Spremembe so bile uspešno shranjene.',
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
'prefs-namespaces'              => 'Imenski prostori',
'defaultns'                     => 'Navadno išči v naslednjih imenskih prostorih:',
'default'                       => 'privzeto',
'prefs-files'                   => 'Datoteke',
'prefs-custom-css'              => 'CSS po meri',
'prefs-custom-js'               => 'JS po meri',
'prefs-common-css-js'           => 'Skupni CSS/JS za vse kože:',
'prefs-reset-intro'             => 'To stran lahko uporabite za ponastavitev nastavitev na privzete za to spletišče.
Tega ni mogoče razveljaviti.',
'prefs-emailconfirm-label'      => 'Potrditev e-pošte:',
'prefs-textboxsize'             => 'Velikost urejevalnega polja',
'youremail'                     => 'E-poštni naslov:',
'username'                      => 'Uporabniško ime:',
'uid'                           => 'ID uporabnika:',
'prefs-memberingroups'          => 'Član {{PLURAL:$1|naslednje skupine|naslednjih skupin|naslednjih skupin|naslednjih skupin|naslednjih skupin}}:',
'prefs-memberingroups-type'     => '$1',
'prefs-registration'            => 'Registriran od:',
'yourrealname'                  => 'Pravo ime:',
'yourlanguage'                  => 'Jezik:',
'yourvariant'                   => 'Različica:',
'yournick'                      => 'Nov podpis:',
'prefs-help-signature'          => 'Komentarje na pogovornih straneh, je treba podpisati s »<nowiki>~~~~</nowiki>«, kar bo pretvorjeno v vaš podpis s časom.',
'badsig'                        => 'Neveljaven surovi podpis; preverite oznake HTML.',
'badsiglength'                  => 'Vaš podpis je preobsežen.
Ne sme biti daljši od $1 {{PLURAL:$1|znaka|znakov}}.',
'yourgender'                    => 'Spol:',
'gender-unknown'                => 'nedoločen',
'gender-male'                   => 'moški',
'gender-female'                 => 'ženski',
'prefs-help-gender'             => 'Podatek ni obvezen, uporablja pa se ga izključno za pravilno obliko naslavljanja programja glede na spol.
Podatek bo javno prikazan.',
'email'                         => 'E-pošta',
'prefs-help-realname'           => 'Pravo ime je neobvezno.
Če se ga odločite navesti, bo uporabljeno za priznavanje vašega dela.',
'prefs-help-email'              => 'E-poštni naslov ni obvezen, vendar vam omogoča, da vam v primeru pozabljenega gesla pošljemo novo.',
'prefs-help-email-others'       => 'Poleg tega vpisan e-poštni naslov omogoča drugim, da vam lahko pošiljajo elektronsko pošto brez razkritja vaše istovetnosti.',
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
'prefs-displayrc'               => 'Možnosti prikaza',
'prefs-displaysearchoptions'    => 'Možnosti prikaza',
'prefs-displaywatchlist'        => 'Možnosti prikaza',
'prefs-diffs'                   => 'Primerjave',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'Izgleda veljaven',
'email-address-validity-invalid' => 'Obvezen je veljaven naslov!',

# User rights
'userrights'                   => 'Upravljanje s pravicami uporabnikov',
'userrights-lookup-user'       => 'Upravljanje z uporabniškimi skupinami',
'userrights-user-editname'     => 'Vpišite uporabniško ime:',
'editusergroup'                => 'Uredi uporabniške skupine',
'editinguser'                  => "Urejanje pravic uporabnika '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Urejanje uporabniških skupin',
'saveusergroups'               => 'Shrani spremembe',
'userrights-groupsmember'      => 'Član skupine:',
'userrights-groupsmember-auto' => 'Posreden član:',
'userrights-groups-help'       => 'Spreminjate lahko skupine uporabnika:
* Obkljukano polje pomeni uporabnika, ki je v skupini
* Odkljukano polje pomeni uporabnika, ki ni v skupini
* Zvezdica (*) kaže, da uporabnika ne boste mogli odstraniti iz skupine, ko ga vanjo dodate oz. obratno.',
'userrights-reason'            => 'Razlog:',
'userrights-no-interwiki'      => 'Nimate dovoljenja za urejanje pravic uporabnikov na drugih wikijih.',
'userrights-nodatabase'        => 'Podatkovna baza $1 ne obstaja ali ni lokalna.',
'userrights-nologin'           => 'Za dodeljevanje uporabniških pravic se morate [[Special:UserLogin|prijaviti]] s skrbniškim računom.',
'userrights-notallowed'        => 'Vaš račun nima dovoljenja za dodeljevanje pravic uporabnikom.',
'userrights-changeable-col'    => 'Skupine, ki jih lahko spremenite',
'userrights-unchangeable-col'  => 'Skupine, ki jih ne morete spremeniti',

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
'group-suppress-member'      => 'Nadzornik',

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
'right-bot'                   => 'Naj bo obravnavan kot samodejni proces',
'right-nominornewtalk'        => 'Urejanja pogovornih strani, ki niso označena kot manjša, sprožijo obvestilo o novem sporočilu',
'right-apihighlimits'         => 'Uporaba višje omejitve poizvedb API',
'right-writeapi'              => 'Uporaba napisanega API-ja',
'right-delete'                => 'Brisanje strani',
'right-bigdelete'             => 'Brisanje strani z obsežno zgodovino',
'right-deleterevision'        => 'Brisanje in obnova posebnih redakcij strani',
'right-deletedhistory'        => 'Ogled zgodovine brisanja, brez besedila izbrisanih strani',
'right-deletedtext'           => 'Ogled izbrisanega besedila in primerjava med izbrisanimi redakcijami',
'right-browsearchive'         => 'Iskanje izbrisanih strani',
'right-undelete'              => 'Obnavljanje strani',
'right-suppressrevision'      => 'Pregled in obnova pred administratorjem skritih redakcij',
'right-suppressionlog'        => 'Ogled zasebnih dnevniških zapisov',
'right-block'                 => 'Preprečitev (blokada) urejanja drugih uporabnikov',
'right-blockemail'            => 'Drugemu uporabniku lahko prepreči pošiljanje e-pošte',
'right-hideuser'              => 'Blokiranje uporabnika, in skritje pred javnostjo',
'right-ipblock-exempt'        => 'Izogne se blokadam IP-naslova, samodejnim blokadam in blokadam območij',
'right-proxyunbannable'       => 'Izogne se samodejnim blokadam proxyjev',
'right-unblockself'           => 'Deblokiraj samega sebe',
'right-protect'               => 'Spreminjanje stopnje zaščite in urejanje zaščitenih strani',
'right-editprotected'         => 'Urejanje zaščitenih strani (brez kaskadne zaščite)',
'right-editinterface'         => 'Urejanje uporabniškega vmesnika',
'right-editusercssjs'         => 'Urejanje CSS- in JS-datotek drugih uporabnikov',
'right-editusercss'           => 'Uredi CSS datotek drugih uporabnikov',
'right-edituserjs'            => 'Uredi JS datotek drugih uporabnikov',
'right-rollback'              => 'Hitro vračanje urejanj od zadnjega uporabnika, ki je urejal določeno stran',
'right-markbotedits'          => 'Označi vrnjena urejanja kot urejanja botov',
'right-noratelimit'           => 'Omejitve dejavnosti ne veljajo',
'right-import'                => 'Uvoz strani iz drugih wikijev',
'right-importupload'          => 'Uvoz strani iz naložene datoteke',
'right-patrol'                => 'Označevanje urejanja drugih kot nadzorovana',
'right-autopatrol'            => 'Označevanje urejanj drugih za samodejno nadzarovana',
'right-patrolmarks'           => 'Ogled oznak nadzorov v zadnjih spremembah',
'right-unwatchedpages'        => 'Preglejte seznam ne spremljanih strani',
'right-trackback'             => 'Pošlje sledilnik',
'right-mergehistory'          => 'Spoji zgodovino strani',
'right-userrights'            => 'Urejanje vseh uporabniških pravic',
'right-userrights-interwiki'  => 'Urejanje uporabniških pravic uporabnikov na drugih wikijih',
'right-siteadmin'             => 'Zaklepanje in odklepanje baze podatkov',
'right-reset-passwords'       => 'Ponastavljanje gesla drugih uporabnikov',
'right-override-export-depth' => 'Izvoz strani, vključno s povezaimi straneh do globine 5',
'right-sendemail'             => 'Pošiljanje e-pošte drugim uporabnikom',
'right-revisionmove'          => 'Prestavi redakcije',
'right-disableaccount'        => 'Onemogočanje računov',

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
'action-upload_by_url'        => 'nalaganje te datoteke iz URL-naslova',
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
'action-revisionmove'         => 'prestavitev redakcije',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|sprememba|spremembi|spremembe|sprememb|sprememb}}',
'recentchanges'                     => 'Zadnje spremembe',
'recentchanges-legend'              => 'Možnosti zadnjih sprememb',
'recentchangestext'                 => 'Na tej strani lahko spremljajte najnedavnejše spremembe wikija.',
'recentchanges-feed-description'    => 'Spremljajte najnovejše spremembe wikija prek tega vira.',
'recentchanges-label-newpage'       => 'S tem urejanjem je bila ustvarjena nova stran',
'recentchanges-label-minor'         => 'To je manjše urejanje',
'recentchanges-label-bot'           => 'To urejanje je bilo izvedeno z botom',
'recentchanges-label-unpatrolled'   => 'To urejanje še ni bilo pregledano',
'rcnote'                            => "Prikazujem {{PLURAL:$1|zadnjo spremembo|zadnji '''$1''' spremembi|zadnje '''$1''' spremembe|zadnjih '''$1''' sprememb|zadnjih '''$1''' sprememb}} v {{PLURAL:$2|zadnjem|zadnjih|zadnjih|zadnjih|zadnjih}} '''$2''' {{PLURAL:$2|dnevu|dneh|dneh|dneh|dneh}}, od $5, $4.",
'rcnotefrom'                        => "Navedene so spremembe od '''$2''' dalje (prikazujem jih do '''$1''').",
'rclistfrom'                        => 'Prikaži spremembe od $1 naprej',
'rcshowhideminor'                   => '$1 manjša urejanja',
'rcshowhidebots'                    => '$1 bote',
'rcshowhideliu'                     => '$1 prijavljene uporabnike',
'rcshowhideanons'                   => '$1 brezimne uporabnike',
'rcshowhidepatr'                    => '$1 pregledana urejanja',
'rcshowhidemine'                    => '$1 moja urejanja',
'rclinks'                           => 'Prikaži zadnjih $1 sprememb v zadnjih $2 dneh<br />$3',
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
'newsectionsummary'                 => '/* $1 */ nov razdelek',
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
'recentchangeslinked-page'     => 'Naslov strani:',
'recentchangeslinked-to'       => 'Prikaži spremembe na določeno stran povezanih strani',

# Upload
'upload'                      => 'Naloži datoteko',
'uploadbtn'                   => 'Naloži datoteko',
'reuploaddesc'                => 'Prekliči nalaganje in se vrni na obrazec za nalaganje',
'upload-tryagain'             => 'Vnesite spremenjen opis datoteke',
'uploadnologin'               => 'Niste prijavljeni',
'uploadnologintext'           => 'Za nalaganje datotek se [[Special:UserLogin|prijavite]].',
'upload_directory_missing'    => 'Mapa za nalaganje datotek ($1) manjka in je ni bilo mogoče ustvariti s spletnim strežnikom.',
'upload_directory_read_only'  => 'V mapo za nalaganje datotek ($1) spletni strežnik ne more pisati.',
'uploaderror'                 => 'Napaka',
'upload-recreate-warning'     => "'''Opozorilo: Datoteka s tem imenom je bila izbrisana ali prestavljena.'''

Dnevnik brisanja in prestavitev za to stran sta navedena tukaj:",
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
'uploadlogpagetext'           => 'Prikazan je seznam nedavno naloženih datotek.
Za grafični pogled obiščite [[Special:NewFiles|galerijo novih datotek]].',
'filename'                    => 'Ime datoteke',
'filedesc'                    => 'Povzetek',
'fileuploadsummary'           => 'Povzetek:',
'filereuploadsummary'         => 'Spremembe datoteke:',
'filestatus'                  => 'Položaj avtorskih pravic:',
'filesource'                  => 'Vir:',
'uploadedfiles'               => 'Naložene datoteke',
'ignorewarning'               => 'Naloži kljub opozorilu',
'ignorewarnings'              => 'Prezri vsa opozorila',
'minlength1'                  => 'Imena datotek morajo biti dolga vsaj eno črko.',
'illegalfilename'             => 'Ime datoteke »$1« vsebuje v naslovih strani prepovedane znake. Prosimo, poskusite datoteko naložiti pod drugim imenom.',
'badfilename'                 => 'Ime datoteke se je samodejno popravilo v »$1«.',
'filetype-mime-mismatch'      => 'Datotečna končnica ».$1« se ne ujema z zaznano MIME-vrsto datoteke ($2).',
'filetype-badmime'            => 'Datoteke MIME-vrste »$1« ni dovoljeno nalagati.',
'filetype-bad-ie-mime'        => 'Ne morem naložiti datoteke, ker bi jo Internet Explorer zaznal kot »$1« in jo zavrnil kot potencialno nevarno vrsto datoteke.',
'filetype-unwanted-type'      => "'''».$1«''' je nezaželena datotečna vrsta.
{{PLURAL:$3|Dovoljena datotečna vrsta je|Dovoljena datotečni vrsti sta|Dovoljene datotečne vrste so|Dovoljene datotečne vrste so}} $2.",
'filetype-banned-type'        => "'''».$1«''' {{PLURAL:$4|ni dovoljena datotečna vrsta|nista dovoljeni datotečni vrsti|niso dovoljene datotečne vrste}}.
{{PLURAL:$3|Dovoljena datotečna vrsta je|Dovoljeni datotečni vrsti sta|Dovoljene datotečne vrste so|Dovoljene datotečne vrste so}} $2.",
'filetype-missing'            => 'Datoteka nima končnice (kot ».jpg«).',
'empty-file'                  => 'Datoteka, ki ste jo poslali, je prazna',
'file-too-large'              => 'Datoteka, ki ste jo poslali, je prevelika',
'filename-tooshort'           => 'Ime datoteke je prekratko',
'filetype-banned'             => 'Ta vrsta datoteke je prepovedana',
'verification-error'          => 'Ta datoteka ni opravila preverjanja datoteke',
'hookaborted'                 => 'Spremembo, ki ste jo poskušali narediti, je prekinila razširitev.',
'illegal-filename'            => 'Ime datoteke ni dovoljeno',
'overwrite'                   => 'Prepisovanje obstoječe datoteke ni dovoljeno',
'unknown-error'               => 'Prišlo je do neznane napake',
'tmp-create-error'            => 'Začasne datoteke ni bilo mogoče ustvariti',
'tmp-write-error'             => 'Napaka pri pisanju začasne datoteke',
'large-file'                  => 'Priporočeno je, da datoteke niso večje od $1; ta datoteka je $2.',
'largefileserver'             => 'Velikost datoteke presega strežnikove nastavitve.',
'emptyfile'                   => 'Kaže, da je aložena datoteka prazna.
Do tega bi lahko prišlo zaradi tipkarske napake v imenu.
Ali datoteko resnično želite naložiti?',
'fileexists'                  => "Datoteka s tem imenom že obstaja. Preden jo povozite, preverite stran '''<tt>[[:$1]]</tt>'''.
[[$1|thumb]]",
'filepageexists'              => "Opisna stran za to datoteko je bila že ustvarjena na '''<tt>[[:$1]]</tt>''', vendar datoteka s tem imenom trenutno ne obstaja.
Povzetek, ki ste ga vnesli, se ne bo prikazal na opisni strani.
Da tam prikažete povzetek, morate stran urediti ročno.
[[$1|thumb]]",
'fileexists-extension'        => "Datoteka s podobnim imenom že obstaja: [[$2|thumb]]
* Ime naložene datoteke: '''<tt>[[:$1]]</tt>'''
* Ime obstoječe datoteke: '''<tt>[[:$2]]</tt>'''
Prosimo, izberite drugo ime.",
'fileexists-thumbnail-yes'    => "Kot izgleda, je ta slika pomanjšana ''(thumbnail)''. [[$1|thumb]]
Prosimo, preverite datoteko '''<tt>[[:$1]]</tt>'''.
Če je preverjena datoteka enaka kot ta, ki jo nalage, ni potrebno nalagati še dodatne sličice.",
'file-thumbnail-no'           => "Ime datoteke se začne z '''<tt>$1</tt>'''.
Izgleda, da je to pomanjšana slika ''(thumbnail)''.
Če imate sliko polne resolucije, jo naložite, drugače spremenite ime datoteke.",
'fileexists-forbidden'        => 'Datoteka s tem imenom že obstaja in je ni mogoče prepisati.
Če še vedno želite naložiti vašo datoteko, se prosimo vrnite nazaj in uporabite novo ime.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Datoteka s tem imenom že obstaja v skupnem skladišču datotek.
Prosimo, vrnite se in naložite svojo datoteko pod drugim imenom.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ta datoteka je dvojnik {{PLURAL:$1|naslednje datoteke|naslednjih datotek}}:',
'file-deleted-duplicate'      => 'Datoteka je identična tej ([[$1]]), ki je bila predhodno izbrisana.
Preverite zgodovino brisanja datoteke, preden jo ponovno naložite.',
'uploadwarning'               => 'Opozorilo!',
'uploadwarning-text'          => 'Prosimo, spremenite opis datoteke spodaj in poskusite ponovno.',
'savefile'                    => 'Shrani datoteko',
'uploadedimage'               => 'je naložil(-a) datoteko »[[$1]]«',
'overwroteimage'              => 'je naložil(-a) novo različico datoteke »[[$1]]«',
'uploaddisabled'              => 'Nalaganje je onemogočeno',
'copyuploaddisabled'          => 'Nalaganje preko URL je onemogočeno',
'uploadfromurl-queued'        => 'Vaše nalaganje je bilo postavljeno v čakalno vrsto.',
'uploaddisabledtext'          => 'Nalaganje datotek je onemogočeno.',
'php-uploaddisabledtext'      => 'Nalaganje datotek je onemogočeno v PHP.
Prosimo preverite file_uploads nastavitev.',
'uploadscripted'              => 'Datoteka vsebuje HTML- ali skriptno kodo, ki bi jo lahko brskalnik razlagal napačno.',
'uploadvirus'                 => 'Datoteka vsebuje virus!
Podrobnosti: $1',
'upload-source'               => 'Izvorna datoteka',
'sourcefilename'              => 'Ime izvorne datoteke:',
'sourceurl'                   => 'Izvorni URL:',
'destfilename'                => 'Ime ciljne datoteke:',
'upload-maxfilesize'          => 'Največja velikost datoteke: $1',
'upload-description'          => 'Opis datoteke',
'upload-options'              => 'Možnosti nalaganja',
'watchthisupload'             => 'Opazuj to datoteko',
'filewasdeleted'              => 'Datoteka s tem imenom je bila nekoč že naložena in potem izbrisana. Preden jo znova naložite, preverite $1.',
'upload-wasdeleted'           => "'''Opozorilo: Nalagate datoteko, ki je bila predhodno že izbrisana.'''

Premislite ali je nadaljevanje nalaganja primerno.
Za lažjo presojo je spodaj izpisek iz dnevnika brisanj:",
'filename-bad-prefix'         => "Ime datoteke, ki jo nalagate, se začne z '''»$1«''', ki je neopisno ime, ponavadi dodeljeno samodejno s strani digitalnih fotoaparatov.
Prosimo, izberite bolj opisno ime vaše datoteke.",
'filename-prefix-blacklist'   => ' #<!-- pustite to vrstico takšno, kot je --> <pre>
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
 #</pre> <!-- pustite to vrstico takšno, kot je -->',
'upload-success-subj'         => 'Datoteka je bila uspešno naložena',
'upload-success-msg'          => 'Vaša datoteka iz [$2] je bila uspešno naložena. Na voljo je tukaj: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Težava pri nalaganju',
'upload-failure-msg'          => 'Prišlo je do težave z vašo naloženo datoteko iz [$2]:

$1',
'upload-warning-subj'         => 'Opozorilo pri nalaganju',
'upload-warning-msg'          => 'Prišlo je do težave pri nalaganju datoteke iz [$2]. Lahko se vrnete na [[Special:Upload/stash/$1|obrazec za nalaganje]], da odpravite težavo.',

'upload-proto-error'        => 'Nepravilni protokol',
'upload-proto-error-text'   => 'Oddaljeno nalaganje zahteva, da se URL začenja s <code>http://</code> ali <code>ftp://</code>.',
'upload-file-error'         => 'Notranja napaka',
'upload-file-error-text'    => 'Prišlo je do notranje napake pri poskusu ustvarjanja začasne datoteke na strežniku.
Prosimo, obrnite se na [[Special:ListUsers/sysop|administratorja]].',
'upload-misc-error'         => 'Neznana napaka pri nalaganju',
'upload-misc-error-text'    => 'Med nalaganjem je prišlo do neznane napake.
Prosimo, preverite veljavnost in dostopnost naslova URL ter poskusite ponovno.
Če se težava ponavlja, kontaktirajte [[Special:ListUsers/sysop|administratorja]].',
'upload-too-many-redirects' => 'URL vsebuje preveč preusmeritev',
'upload-unknown-size'       => 'Neznana velikost',
'upload-http-error'         => 'Prišlo je do napake HTTP: $1',

# Special:UploadStash
'uploadstash'          => 'Skrite naložene datoteke',
'uploadstash-summary'  => 'Ta stran omogoča dostop do datotek, ki so naložene (oziroma v postopku nalaganja), vendar še niso objavljene na wikiju. Te datoteke so vidne samo uporabniku, ki jih je naložil, in nikomur drugemu.',
'uploadstash-clear'    => 'Počisti skrite datoteke',
'uploadstash-nofiles'  => 'Nimate skritih datotek.',
'uploadstash-badtoken' => 'Izvedba dejanja ni bila uspešna, morda zaradi izteklih poverilnic za urejanje. Poskusite znova.',
'uploadstash-errclear' => 'Čiščenje datotek ni bilo uspešno.',
'uploadstash-refresh'  => 'Osveži seznam datotek',

# img_auth script messages
'img-auth-accessdenied' => 'Dostop zavrnjen',
'img-auth-nopathinfo'   => 'Manjka PATH_INFO.
Vaš strežnik ne poda te informacije.
Morda temelji na CGI in ne more podpirati img_auth.
Oglejte si http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Zahtevana pot ni v konfigurirani mapi za nalaganje.',
'img-auth-badtitle'     => 'Ni mogoče sestaviti veljavnega naslova iz »$1«.',
'img-auth-nologinnWL'   => 'Niste prijavljeni in »$1« ni na seznamu dovoljenih datotek.',
'img-auth-nofile'       => 'Datoteka »$1« ne obstaja.',
'img-auth-isdir'        => 'Poskušate dostopati do mape »$1«.
Dovoljeno je samo dostopanje do datotek.',
'img-auth-streaming'    => 'Pretakanje »$1«.',
'img-auth-public'       => 'Funkcija img_auth.php je izvoz datotek iz zasebnega wikija.
Ta wiki je konfiguriran kot javni wiki.
Za optimalno varnost je img_auth.php onemogočen.',
'img-auth-noread'       => 'Uporabnik nima dostopa za branje »$1«.',

# HTTP errors
'http-invalid-url'      => 'Napačen URL: $1',
'http-invalid-scheme'   => 'URL-naslovi s shemo »$1« niso podprti.',
'http-request-error'    => 'Zahteva HTTP ni uspela zaradi neznane napake.',
'http-read-error'       => 'Napaka branja HTTP.',
'http-timed-out'        => 'Zahteva HTTP je potekla.',
'http-curl-error'       => 'Napaka pri doseganju URL: $1',
'http-host-unreachable' => 'Ni mogoče doseči URL.',
'http-bad-status'       => 'Med zahtevo HTTP je prišlo do težave: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Ni možno doseči URL',
'upload-curl-error6-text'  => 'Navedenega naslova URL ni mogoče doseči.
Prosimo, ponovno preverite pravilnost URL-a in delovanje strani.',
'upload-curl-error28'      => 'Časovna prekinitev nalaganja',
'upload-curl-error28-text' => 'Odziv strani je trajal predolgo.
Prosimo, preverite delovanje strani, počakajte kratek čas in poskusite ponovno.
Morda želite poskusiti ob času manjše zasedenosti.',

'license'            => 'Dovoljenje:',
'license-header'     => 'Dovoljenje',
'nolicense'          => 'Nobeno',
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
'listfiles_thumb'       => 'Sličica',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Ime',
'listfiles_user'        => 'Uporabnik',
'listfiles_size'        => 'Velikost',
'listfiles_description' => 'Opis',
'listfiles_count'       => 'Različice',

# File description page
'file-anchor-link'                  => 'Datoteka',
'filehist'                          => 'Zgodovina datoteke',
'filehist-help'                     => 'Klikni na datum in čas za ogled datoteke, ki je bila takrat naložena.',
'filehist-deleteall'                => 'izbriši vse',
'filehist-deleteone'                => 'izbriši to',
'filehist-revert'                   => 'vrni',
'filehist-current'                  => 'trenutno',
'filehist-datetime'                 => 'Datum in čas',
'filehist-thumb'                    => 'Sličica',
'filehist-thumbtext'                => 'Sličica za različico $1',
'filehist-nothumb'                  => 'Brez predogleda',
'filehist-user'                     => 'Uporabnik',
'filehist-dimensions'               => 'Dimenzije',
'filehist-filesize'                 => 'Velikost datoteke',
'filehist-comment'                  => 'Komentar',
'filehist-missing'                  => 'Datoteka manjka',
'imagelinks'                        => 'Strani z datoteko',
'linkstoimage'                      => 'Datoteka je del {{PLURAL:$1|naslednje $1 strani|naslednjih $1 strani}} {{GRAMMAR:rodilnik|{{SITENAME}}}}:',
'linkstoimage-more'                 => 'Na to datoteko se {{PLURAL:$1|povezuje več kot $1 stran|povezujeta več kot $1 strani|povezujejo več kot $1 strani|povezuje več kot $1 strani}}.
Naslednji seznam obsega samo {{PLURAL:$1|prvo stran, ki se povezuje|prvi $1 strani, ki se povezujeta|prve $1 strani, ki se povezujejo|prvih $1 strani, ki se povezujejo}} na to datoteko.
Na voljo je tudi [[Special:WhatLinksHere/$2|celotni seznam]].',
'nolinkstoimage'                    => 'Z datoteko se ne povezuje nobena stran.',
'morelinkstoimage'                  => 'Preglejte [[Special:WhatLinksHere/$1|več povezav]] na to datoteko.',
'redirectstofile'                   => 'Na to datoteko {{PLURAL:$1|preusmerja naslednja datoteka|preusmerjata naslednji datoteki|preusmerjajo naslednje $1 datoteke|preusmerja naslednjih $1 datotek|preusmerja naslednjih $1 datotek}}:',
'duplicatesoffile'                  => '{{PLURAL:$1|Sledeča datoteka je dvojnik|Sledeči datoteki sta dvojnika|Sledeče $1 datoteke so dvojniki|Sledečih $1 datotek so dvojniki}} te datoteke ([[Special:FileDuplicateSearch/$2|več podrobnosti]]):',
'sharedupload'                      => 'Datoteka je del $1 in se lahko uporabi v drugih projektih.',
'sharedupload-desc-there'           => 'Ta datoteka je iz $1 in se lahko uporablja v drugih projektih.
Prosimo, oglejte si [$2 opisno stran datoteke] za dodatne informacije.',
'sharedupload-desc-here'            => 'Ta datoteka je iz $1 in se lahko uporablja v drugih projektih.
Povzetek na njeni [$2 opisni strani datoteke] je prikazan spodaj.',
'filepage-nofile'                   => 'Datoteka s tem imenom ne obstaja.',
'filepage-nofile-link'              => 'Datoteka s tem imenom ne obstaja, vendar pa jo lahko [$1 naložite].',
'uploadnewversion-linktext'         => 'Naložite novo različico datoteke',
'shared-repo-from'                  => 'iz $1',
'shared-repo'                       => 'skupno skladišče',
'shared-repo-name-wikimediacommons' => 'Wikimedijine Zbirke',

# File reversion
'filerevert'                => 'Vrni $1',
'filerevert-legend'         => 'Vrni datoteko',
'filerevert-intro'          => "Vračate datoteko '''[[Media:$1|$1]]''' na [$4 različico $3, $2].",
'filerevert-comment'        => 'Razlog:',
'filerevert-defaultcomment' => 'Vrnjeno na različico $2, $1',
'filerevert-submit'         => 'Vrni',
'filerevert-success'        => "Datoteka '''[[Media:$1|$1]]''' je bila vrnjena na [$4 različico $3, $2].",
'filerevert-badversion'     => 'Ne najdem preteklih lokalnih verzij datoteke s podanim časovnim žigom.',

# File deletion
'filedelete'                  => 'Izbriši $1',
'filedelete-legend'           => 'Brisanje datoteke',
'filedelete-intro'            => "Brišete datoteko '''[[Media:$1|$1]]''' skupaj z njeno celotno zgodovino.",
'filedelete-intro-old'        => "Brišete različico datoteke '''[[Media:$1|$1]]''' z dne [$4 $3, $2].",
'filedelete-comment'          => 'Razlog:',
'filedelete-submit'           => 'Izbriši',
'filedelete-success'          => "Datoteka '''$1''' je bila izbrisana.",
'filedelete-success-old'      => "Različica datoteke '''[[Media:$1|$1]]''', z dne $3, $2 je bila izbrisana.",
'filedelete-nofile'           => "Datoteka '''$1''' ne obstaja na tej strani.",
'filedelete-nofile-old'       => "Arhivirana različica datoteke '''$1''' z določenimi vrednostmi ne obstaja.",
'filedelete-otherreason'      => 'Drug/dodaten razlog:',
'filedelete-reason-otherlist' => 'Drug razlog',
'filedelete-reason-dropdown'  => '* Pogosti razlogi brisanja
** kršitev avtorskih pravic
** podvojena datoteka',
'filedelete-edit-reasonlist'  => 'Uredi razloge za brisanje',
'filedelete-maintenance'      => 'Brisanje in obnovitev datotek je začasno onemogočeno zaradi vzdrževanja.',

# MIME search
'mimesearch'         => 'Iskanje po vrsti MIME',
'mimesearch-summary' => 'Ta stran omogoča filtriranje datotek po njihovi vrsti MIME.
Vnesite: vrstavsebine/podvrsta, npr. <tt>image/jpeg</tt>.',
'mimetype'           => 'Vrsta MIME:',
'download'           => 'prenesi',

# Unwatched pages
'unwatchedpages' => 'Nespremljane strani',

# List redirects
'listredirects' => 'Seznam preusmeritev',

# Unused templates
'unusedtemplates'     => 'Osirotele predloge',
'unusedtemplatestext' => 'Naslednji seznam navaja vse strani v imenskem prostoru {{ns:template}}, ki niso vključene v nobeno stran.
Preden jih izbrišete, preverite še druge povezave nanje.',
'unusedtemplateswlh'  => 'druge povezave',

# Random page
'randompage'         => 'Naključni članek',
'randompage-nopages' => 'V {{PLURAL:$2|naslednjem imenskem prostoru|naslednjih imenskih prostorih}} ni strani: $1.',

# Random redirect
'randomredirect'         => 'Naključna preusmeritev',
'randomredirect-nopages' => 'V imenskem prostoru »$1« ni preusmeritev.',

# Statistics
'statistics'                   => 'Statistika',
'statistics-header-pages'      => 'Statistika strani',
'statistics-header-edits'      => 'Statistika urejanj',
'statistics-header-views'      => 'Statistika ogledov',
'statistics-header-users'      => 'Uporabniška statistika',
'statistics-header-hooks'      => 'Drugi statistični podatki',
'statistics-articles'          => 'Članki',
'statistics-pages'             => 'Strani',
'statistics-pages-desc'        => 'Vse strani na wikiju, vključno s pogovornimi stranmi, preusmeritvami itn.',
'statistics-files'             => 'Naložene datoteke',
'statistics-edits'             => 'Urejanja strani od postavitve {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'statistics-edits-average'     => 'Povprečno število urejanj na stran',
'statistics-views-total'       => 'Vseh ogledov',
'statistics-views-total-desc'  => 'Ogledi neoobstoječih in posebnih strani niso vključeni',
'statistics-views-peredit'     => 'Razmerje med ogledi in urejanji',
'statistics-users'             => 'Registrirani [[Special:ListUsers|uporabniki]]',
'statistics-users-active'      => 'Aktivni uporabniki',
'statistics-users-active-desc' => 'Uporabniki, ki so izvedli dejanje v {{PLURAL:$1|zadnjem dnevu|zadnjih $1 dneh}}',
'statistics-mostpopular'       => 'Strani z največ ogledi',

'disambiguations'      => 'Razločitvene strani',
'disambiguationspage'  => 'Template:Razločitev',
'disambiguations-text' => "Naslednje strani se povezujejo na '''razločitvene strani'''.
Namesto tega bi se naj povezovale na primerno temo.<br />
Stran se obravnava kot razločitvena, če uporablja predloge povezane iz [[MediaWiki:Disambiguationspage]]",

'doubleredirects'                   => 'Dvojne preusmeritve',
'doubleredirectstext'               => 'Ta stran navaja strani, ki se preusmerjajo na druge preusmeritvene strani.
Vsaka vrstica vsebuje povezavo do prve in druge preusmeritve, kakor tudi do cilja druge preusmeritve, ki je po navadi »prava« ciljna stran, na katero naj bi kazala prva preusmeritev.
<del>Prečrtani</del> vnosi so bili razrešeni.',
'double-redirect-fixed-move'        => 'Stran [[$1]] je bil premaknjen.
Sedaj je preusmeritev na [[$2]].',
'double-redirect-fixed-maintenance' => 'Popravljanje dvojne preusmeritve z [[$1]] na [[$2]].',
'double-redirect-fixer'             => 'Popravljalec preusmeritev',

'brokenredirects'        => 'Pretrgane preusmeritve',
'brokenredirectstext'    => 'Naslednje preusmeritve kažejo na neobstoječe strani:',
'brokenredirects-edit'   => 'uredi',
'brokenredirects-delete' => 'izbriši',

'withoutinterwiki'         => 'Strani brez jezikovnih povezav',
'withoutinterwiki-summary' => 'Sledeče strani se ne povezujejo na različice v drugih jezikih.',
'withoutinterwiki-legend'  => 'Predpona',
'withoutinterwiki-submit'  => 'Pokaži',

'fewestrevisions' => 'Strani z najmanj urejanji',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|zlog|zloga|zlogi|zlogov|zlogov}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorija|kategoriji|kategorije|kategorij}}',
'nlinks'                  => '$1 {{PLURAL:$1|povezava|povezavi|povezave|povezav|povezav}}',
'nmembers'                => '$1 {{PLURAL:$1|element|elementa|elementi|elementov|elementov}}',
'nrevisions'              => '$1 {{PLURAL:$1|redakcija|redakciji|redakcije|redakcij|redakcij}}',
'nviews'                  => '$1 {{PLURAL:$1|ogled|ogleda|ogledi|ogledov|ogledov}}',
'nimagelinks'             => 'Uporabljeno na $1 {{PLURAL:$1|strani|straneh}}',
'ntransclusions'          => 'uporabljeno na $1 {{PLURAL:$1|strani|straneh}}',
'specialpage-empty'       => 'Za to poročilo ni rezultatov.',
'lonelypages'             => 'Osirotele strani',
'lonelypagestext'         => 'Naslednje strani niso povezane ali vključene v nobeno drugo stran na {{GRAMMAR:locative|{{SITENAME}}}}.',
'uncategorizedpages'      => 'Nekategorizirane strani',
'uncategorizedcategories' => 'Nekategorizirane kategorije',
'uncategorizedimages'     => 'Nekategorizirane datoteke',
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
'mostlinkedtemplates'     => 'Najbolj uporabljane predloge',
'mostcategories'          => 'Članki z največ kategorijami',
'mostimages'              => 'Najbolj uporabljane datoteke',
'mostrevisions'           => 'Največkrat urejane strani',
'prefixindex'             => 'Vse strani s predpono',
'shortpages'              => 'Kratke strani',
'longpages'               => 'Dolge strani',
'deadendpages'            => 'Članki brez delujočih povezav',
'deadendpagestext'        => 'Spodaj navedene strani se ne povezujejo na druge članke v {{GRAMMAR:dajalnik|{{SITENAME}}}}.',
'protectedpages'          => 'Zaščitene strani',
'protectedpages-indef'    => 'Samo neomejene zaščite',
'protectedpages-cascade'  => 'Le kaskadne zaščite',
'protectedpagestext'      => 'Spodaj navedene strani so zaščitene pred urejanjem ali prestavljanjem.',
'protectedpagesempty'     => 'Nobena stran ni trenutno zaščitena s temi parametri.',
'protectedtitles'         => 'Zaščiteni naslovi',
'protectedtitlestext'     => 'Naslednji naslovi so zaščiteni pred ustvarjanjem',
'protectedtitlesempty'    => 'Noben naslov ni trenutno zaščiten s temi parametri.',
'listusers'               => 'Seznam uporabnikov',
'listusers-editsonly'     => 'Pokaži samo uporabnike z urejanji',
'listusers-creationsort'  => 'Razvrsti po datumu ustvaritve',
'usereditcount'           => '$1 {{PLURAL:$1|urejanje|urejanji|urejanja|urejanj}}',
'usercreated'             => 'Ustvarjen $1 ob $2',
'newpages'                => 'Nove strani',
'newpages-username'       => 'Uporabniško ime:',
'ancientpages'            => 'Najdlje nespremenjeni članki',
'move'                    => 'Prestavi',
'movethispage'            => 'Prestavi stran',
'unusedimagestext'        => 'Spodnje datoteke obstajajo, vendar niso vključene v nobeno stran.
Prosimo, upoštevajte, da se lahko druge spletne strani povezujejo na datoteko z neposrednim URL in je zato morda še vedno navedena tukaj, čeprav se aktivno uporablja.',
'unusedcategoriestext'    => 'Naslednje strani kategorij obstajajo, vendar jih ne uporablja noben članek ali druga kategorija.',
'notargettitle'           => 'Ni cilja',
'notargettext'            => 'Niste navedli ciljne strani ali uporabnika za izvedbo ukaza.',
'nopagetitle'             => 'Nobena takšna ciljna stran ne obstaja.',
'nopagetext'              => 'Ciljna stran, ki ste jo navedli, ne obstaja.',
'pager-newer-n'           => '{{PLURAL:$1|novejši|novejša|novejši|novejših}} $1',
'pager-older-n'           => '{{PLURAL:$1|starejši|starejša|starejši|starejših}} $1',
'suppress'                => 'Nadzor',
'querypage-disabled'      => 'Ta posebna stran je onemogočena iz zmogljivostnih razlogov.',

# Book sources
'booksources'               => 'Viri knjig',
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
'allpagesbadtitle'  => 'Podan naslov strani je neveljaven oz. ima predpono interjezik ali interwiki.
Morda vsebuje enega ali več znakov, ki niso dovoljeni v naslovih.',
'allpages-bad-ns'   => '{{SITENAME}} nima imenskega prostora »$1«.',

# Special:Categories
'categories'                    => 'Kategorije',
'categoriespagetext'            => '{{PLURAL:$1|Naslednja $1 kategorija vsebuje|Naslednji $1 kategoriji vsebujeta|Naslednje $1 kategorije vsebujejo|Naslednjih $1 kategorij vsebuje}} strani ali datoteke.
[[Special:UnusedCategories|Neuporabljene kategorije]] niso prikazane.
Glej tudi [[Special:WantedCategories|želene kategorije]].',
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
'linksearch-text'  => 'Jokerje, kot je »*.wikipedia.org«, se lahko uporablja.<br />
Podprti protokoli: <tt>$1</tt>',
'linksearch-line'  => '$1 povezano iz $2',
'linksearch-error' => 'Jokerji se lahko pojavijo le na začetku gostiteljskega imena.',

# Special:ListUsers
'listusersfrom'      => 'Prikaži uporabnike začenši z:',
'listusers-submit'   => 'Prikaži',
'listusers-noresult' => 'Ni najdenih uporabnikov.',
'listusers-blocked'  => '(blokiran)',

# Special:ActiveUsers
'activeusers'            => 'Seznam aktivnih uporabnikov',
'activeusers-intro'      => 'Seznam uporabnikov, ki so bili kakor koli aktivni v {{PLURAL:$1|zadnjem $1 dnevu|zadnjih $1 dneh}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|urejanje|urejanji|urejanja|urejanj}} v {{PLURAL:$3|preteklem dnevu|preteklih $3 dneh}}',
'activeusers-from'       => 'Prikaži uporabnike začenši z:',
'activeusers-hidebots'   => 'Skrij bote',
'activeusers-hidesysops' => 'Skrij administratorje',
'activeusers-noresult'   => 'Noben uporabnik ni bil najden.',

# Special:Log/newusers
'newuserlogpage'              => 'Dnevnik registracij uporabnikov',
'newuserlogpagetext'          => 'Prikazan je dnevnik nedavnih registracij novih uporabnikov.',
'newuserlog-byemail'          => 'geslo je bilo poslano po e-pošti',
'newuserlog-create-entry'     => 'Nov uporabnik',
'newuserlog-create2-entry'    => 'je ustvaril(-a) račun »$1«',
'newuserlog-autocreate-entry' => 'Račun ustvarjen samodejno',

# Special:ListGroupRights
'listgrouprights'                      => 'Pravice uporabniških skupin',
'listgrouprights-summary'              => 'Spodaj se nahaja seznam uporabniških skupin na tem wikiju in njim dodeljene pravice dostopa.
Na voljo so morda [[{{MediaWiki:Listgrouprights-helppage}}|dodatne informacije]] o posameznih skupinah.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Dodeljena pravica</span>
* <span class="listgrouprights-revoked">Odvzeta pravica</span>',
'listgrouprights-group'                => 'Skupina',
'listgrouprights-rights'               => 'Pravice',
'listgrouprights-helppage'             => 'Help:Pravice skupin',
'listgrouprights-members'              => '(seznam članov)',
'listgrouprights-addgroup'             => 'Dodajte {{PLURAL:$2|skupino|skupini|skupine}}: $1',
'listgrouprights-removegroup'          => 'Odstranite {{PLURAL:$2|skupino|skupini|skupine}}: $1',
'listgrouprights-addgroup-all'         => 'Dodaj vse skupine',
'listgrouprights-removegroup-all'      => 'Odstrani vse skupine',
'listgrouprights-addgroup-self'        => 'Dodajte {{PLURAL:$2|skupino|skupini|skupine}} svojemu računu: $1',
'listgrouprights-removegroup-self'     => 'Odstranite {{PLURAL:$2|skupino|skupini|skupine}} od svojega računa: $1',
'listgrouprights-addgroup-self-all'    => 'Lastni račun dodaj v vse skupine',
'listgrouprights-removegroup-self-all' => 'Lastni račun odstrani iz vseh skupin',

# E-mail user
'mailnologin'          => 'Manjka naslov pošiljatelja',
'mailnologintext'      => 'Za pošiljanje e-pošte drugim uporabnikom se [[Special:UserLogin|prijavite]] in v [[Special:Preferences|nastavitvah]] vpišite veljaven e-poštni naslov.',
'emailuser'            => 'Pošlji uporabniku e-pismo',
'emailpage'            => 'Pošlji uporabniku e-pismo',
'emailpagetext'        => 'S spodnjim obrazcem lahko uporabniku pošljete e-poštno sporočilo.
E-poštni naslov, ki ste ga vpisali v [[Special:Preferences|uporabniških nastavitvah]], bo v e-sporočilu naveden kot naslov »Od:«, tako da bo prejemnik lahko odgovoril neposredno vam.',
'usermailererror'      => 'Predmet e-pošte je vrnil napako:',
'defemailsubject'      => 'Elektronska pošta {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'usermaildisabled'     => 'Uporabniška e-pošta je onemogočena',
'usermaildisabledtext' => 'Na tem wikiju ne morete poslati e-pošte drugim uporabnikom',
'noemailtitle'         => 'Ni e-poštnega naslova',
'noemailtext'          => 'Uporabnik ni navedel veljavnega e-poštnega naslova.',
'nowikiemailtitle'     => 'E-pošta ni dovoljena',
'nowikiemailtext'      => 'Ta uporabnik se je odločil, da ne bo prejmal e-pošte od drugih uporabnikov.',
'email-legend'         => 'Pošlji e-pošto drugemu uporabniku {{GRAMMAR:akuzativ|{{SITENAME}}}}',
'emailfrom'            => 'Od:',
'emailto'              => 'Za:',
'emailsubject'         => 'Zadeva:',
'emailmessage'         => 'Sporočilo:',
'emailsend'            => 'Pošlji',
'emailccme'            => 'Po elektronski pošti mi pošlji kopijo mojega sporočila.',
'emailccsubject'       => 'Kopija tvojega sporočila iz $1: $2',
'emailsent'            => 'E-pismo je poslano!',
'emailsenttext'        => 'E-pismo je poslano.',
'emailuserfooter'      => 'To e-poštno sporočilo je bilo poslano od $1 uporabniku $2 preko funkcije »{{int:emailpage}}« na {{GRAMMAR:dative|{{SITENAME}}}}.',

# User Messenger
'usermessage-summary' => 'Pusti sistemsko sporočilo.',
'usermessage-editor'  => 'Sistemski sporočevalec',

# Watchlist
'watchlist'            => 'Spisek nadzorov',
'mywatchlist'          => 'Spisek nadzorov',
'watchlistfor2'        => 'Za $1 $2',
'nowatchlist'          => 'Vaš spisek nadzorov je prazen.',
'watchlistanontext'    => 'Za pregled ali urejanje vsebine vašega spiska nadzorov se morate $1.',
'watchnologin'         => 'Niste prijavljeni',
'watchnologintext'     => 'Za urejanje spiska nadzorov morate biti [[Special:UserLogin|prijavljeni]].',
'addedwatch'           => 'Dodano na spisek nadzorov',
'addedwatchtext'       => "Stran »[[:$1]]« je bila dodana na vaš [[Special:Watchlist|spisek nadzorov]].
Morebitne spremembe te strani in pripadajoče pogovorne strani bodo navedene tukaj, v [[Special:RecentChanges|seznamu zadnjih sprememb]] pa bodo za lažjo izbiro označene '''krepko'''.",
'removedwatch'         => 'Odstranjeno s spiska nadzorov',
'removedwatchtext'     => 'Stran »[[:$1]]« je bila odstranjena z vašega [[Special:Watchlist|spiska nadzorov]].',
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
'watchlistcontains'    => 'Spremljate $1 {{PLURAL:$1|stran|strani}}.',
'iteminvalidname'      => 'Težava z izbiro »$1«, neveljavno ime ...',
'wlnote'               => "{{PLURAL:$1|Navedena je zadnja|Navedeni sta zadnji|Navedene so zadnje|Navedenih je zadnjih}} '''$1''' {{PLURAL:$1|sprememba|spremembi|spremembe|sprememb}} v {{PLURAL:$2|zadnji '''$2''' uri|zadnjih '''$2''' urah}}.",
'wlshowlast'           => 'Prikaži zadnjih $1 ur; $2 dni; $3;',
'watchlist-options'    => 'Možnosti spiska nadzorov',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Nadziranje ...',
'unwatching' => 'Nenadziranje ...',

'enotif_mailer'                => 'Obvestilni poštar {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'enotif_reset'                 => 'Označi vse strani kot prebrane',
'enotif_newpagetext'           => 'To je nova stran.',
'enotif_impersonal_salutation' => 'Uporabnik {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'changed'                      => 'spremenil',
'created'                      => 'ustvaril',
'enotif_subject'               => 'Stran {{GRAMMAR:rodilnik|{{SITENAME}}}} $PAGETITLE je $CHANGEDORCREATED $PAGEEDITOR',
'enotif_lastvisited'           => 'Za spremembe po vašem zadnjem obisku glejte $1.',
'enotif_lastdiff'              => 'Glej $1 za to spremembo.',
'enotif_anon_editor'           => 'brezimni uporabnik $1',
'enotif_body'                  => '$WATCHINGUSERNAME,

stran v {{GRAMMAR:dajalnik|{{SITENAME}}}} $PAGETITLE je dne $PAGEEDITDATE $CHANGEDORCREATED uporabnik $PAGEEDITOR,
za trenutno redakcijo glejte $PAGETITLE_URL.

$NEWPAGE

Urejevalčev povzetek: $PAGESUMMARY $PAGEMINOREDIT

Navežite stik z urejevalcem:
e-pošta: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Nadaljnjih obvestil do obiska strani ne boste prejemali.
Na spisku nadzorov lahko tudi ponastavite zastavice obveščanj za vse spremljane strani.

             Vaš opozorilni sistem {{GRAMMAR:rodilnik|{{SITENAME}}}}

--
Za spremembo nastavitev spiska nadzorov obiščite
{{fullurl:{{#special:Watchlist}}/edit}}

Za odstranitev strani z vašega spiska nadzorov obiščite
$UNWATCHURL

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
'historywarning'         => "'''Opozorilo:''' Stran, ki jo želite izbrisati, ima zgodovino s približno $1 {{PLURAL:$1|redakcijo|redakcijama|redakcijami}}:",
'confirmdeletetext'      => "Iz zbirke podatkov boste izbrisali stran ali sliko skupaj z vso njeno zgodovino.
Prosimo, '''potrdite''', da to resnično želite, da razumete posledice dejanja in da se ravnate po [[{{MediaWiki:Policy-url}}|pravilih]].",
'actioncomplete'         => 'Poseg je končan',
'actionfailed'           => 'Dejanje spodletelo',
'deletedtext'            => 'Stran »<nowiki>$1</nowiki>« je izbrisana.
Za zapise nedavnih brisanj glej $2.',
'deletedarticle'         => 'je izbrisal(-a) »[[$1]]«',
'suppressedarticle'      => 'skril »[[$1]]«',
'dellogpage'             => 'Dnevnik brisanja',
'dellogpagetext'         => 'Spodaj je prikazan seznam nedavnih brisanj.',
'deletionlog'            => 'dnevnik brisanja',
'reverted'               => 'Obnovljeno na prejšnjo redakcijo',
'deletecomment'          => 'Razlog:',
'deleteotherreason'      => 'Drugi/dodatni razlogi:',
'deletereasonotherlist'  => 'Drug razlog',
'deletereason-dropdown'  => '* Pogosti razlogi za brisanje
** zahteva avtorja
** kršitev avtorskih pravic
** vandalizem',
'delete-edit-reasonlist' => 'Uredi razloge za brisanje',
'delete-toobig'          => 'Ta stran ima obsežno zgodovino urejanja, tj. čez $1 {{PLURAL:$1|redakcijo|redakciji|redakcije|redakcij}}.
Izbris takšnih strani je bil omejen v izogib neželenim motnjam {{GRAMMAR:dative|{{SITENAME}}}}.',
'delete-warning-toobig'  => 'Ta stran ima obsežno zgodovino urejanja, tj. čez $1 {{PLURAL:$1|redakcijo|redakciji|redakcije|redakcij}}.
Njeno brisanje lahko zmoti obratovanje zbirke podatkov {{GRAMMAR:dative|{{SITENAME}}}};
nadaljujte s previdnostjo.',

# Rollback
'rollback'          => 'Vrni spremembe',
'rollback_short'    => 'Vrni',
'rollbacklink'      => 'vrni',
'rollbackfailed'    => 'Vrnitev ni uspela',
'cantrollback'      => 'Urejanja ne morem vrniti; zadnji urejevalec je hkrati edini.',
'alreadyrolled'     => 'Zadnje spremembe [[:$1]] uporabnika [[User:$2|$2]] ([[User talk:$2|pogovor]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) ne morem vrniti;
stran je spremenil ali vrnil že nekdo drug.

Zadnji je stran urejal uporabnik [[User:$3|$3]] ([[User talk:$3|pogovor]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Povzetek urejanja je bil: »''$1''«.",
'revertpage'        => 'vrnitev sprememb uporabnika [[Special:Contributions/$2|$2]] ([[User talk:$2|pogovor]]) na zadnje urejanje uporabnika [[User:$1|$1]]',
'revertpage-nouser' => 'vrnitev sprememb (uporabniško ime odstranjeno) na  zadnje urejanje uporabnika [[User:$1|$1]]',
'rollback-success'  => 'Razveljavljene spremembe uporabnika $1;
vrnjeno na urejanje uporabnika $2.',

# Edit tokens
'sessionfailure-title' => 'Neuspeh seje',
'sessionfailure'       => 'Vaša prijava ni uspela; da bi preprečili ugrabitev seje, je bilo dejanje preklicano. Prosimo, izberite »Nazaj« in ponovno naložite stran, s katere prihajate, nato poskusite znova.',

# Protect
'protectlogpage'              => 'Dnevnik zaščit strani',
'protectlogtext'              => 'Prikazan je seznam zaščit in odstranitev zaščit strani.
Oglejte si [[Special:ProtectedPages|seznam zaščitenih strani]] za seznam trenutno zaščitenih strani.',
'protectedarticle'            => 'je zaščitil(-a) stran »[[$1]]«',
'modifiedarticleprotection'   => 'spremenjena stopnja zaščite »[[$1]]«',
'unprotectedarticle'          => 'Zaščita strani $1 je odstranjena.',
'movedarticleprotection'      => 'nastavitve zaščite so prestavljene iz »[[$2]]« na »[[$1]]«',
'protect-title'               => 'Zaščita strani »$1«',
'prot_1movedto2'              => 'je prestavil(-a) [[$1]] na [[$2]]',
'protect-legend'              => 'Potrdite zaščito',
'protectcomment'              => 'Razlog:',
'protectexpiry'               => 'Poteče:',
'protect_expiry_invalid'      => 'Čas izteka je neveljaven.',
'protect_expiry_old'          => 'Čas izteka je v preteklosti.',
'protect-unchain-permissions' => 'Odkleni nadaljne možnosti zaščite',
'protect-text'                => "Tu si lahko ogledate in spremenite raven zaščitenosti strani '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Med blokado ne morete spreminjati stopenj zaščite.
To so trenutne nastavitve za stran '''$1''':",
'protect-locked-dblock'       => "Stopnje zaščite ni mogoče spremeniti zaradi aktivnega zaklepa zbirke podatkov.
To so trenutne nastavitve za stran '''$1''':",
'protect-locked-access'       => "Vaš uporabniški račun nima dovoljenja za spreminjanje stopnje zaščite strani.
Trenutne nastavitve za stran '''$1''' so:",
'protect-cascadeon'           => 'Ta stran je trenutno uaščitena, ker je vključena v {{PLURAL:$1|naslednjo stran, ki ima|naslednji strani, ki imata|naslednje strani, ki imajo|naslednjih strani, ki imajo}} vključeno kaskadno zaščito.
Stopnjo zaščite te strani lahko spremenite, vendar to ne bo vplivalo na kaskadno zaščito.',
'protect-default'             => 'Dovoli vsem uporabnikom',
'protect-fallback'            => 'Potrebujete pravice »$1«',
'protect-level-autoconfirmed' => 'Blokiraj nove in neregistrirane uporabnike',
'protect-level-sysop'         => 'Samo administratorji',
'protect-summary-cascade'     => 'kaskadno',
'protect-expiring'            => 'poteče $1 (UTC)',
'protect-expiry-indefinite'   => 'nedoločeno',
'protect-cascade'             => 'Zaščiti strani, ki so vključene v to stran (kaskadna zaščita)',
'protect-cantedit'            => 'Ne morete spreminjati stopnje zaščite te strani, ker nimate dovoljenja za njeno urejanje.',
'protect-othertime'           => 'Drugačen čas:',
'protect-othertime-op'        => 'drugačen čas',
'protect-existing-expiry'     => 'Obstoječ čas izteka: $3, $2',
'protect-otherreason'         => 'Drug/dodaten razlog:',
'protect-otherreason-op'      => 'Drug razlog',
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
'maximum-size'                => 'Maks. velikost:',
'pagesize'                    => '(bajtov)',

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
'undelete'                     => 'Ogled izbrisanih strani',
'undeletepage'                 => 'Prikaži izbrisane strani in jih obnovi',
'undeletepagetitle'            => "'''Slednje zajema izbrisane redakcije [[:$1|$1]].'''",
'viewdeletedpage'              => 'Pregled izbrisanih strani',
'undeletepagetext'             => '{{PLURAL:$1|Naslednja stran je bila izbrisana, vendar je še vedno v arhivu in jo lahko obnovite.|Naslednji $1 strani sta bili izbrisani, vendar sta še vedno v arhivu in ju lahko obnovite.|Naslednje $1 strani so bile izbrisane, vendar so še vedno v arhivu in jih lahko obnovite.|Naslednjih $1 strani je bilo izbrisanih, vendar so še vedno v arhivu in jih lahko obnovite.}}
Arhiv je treba občasno počistiti.',
'undelete-fieldset-title'      => 'Obnovi redakcije',
'undeleteextrahelp'            => "Da bi obnovili celotno stran z vso njeno zgodovino, pustite vsa potrditvena polja prazna in kliknite '''''Obnovi'''''.
Če želite obnoviti le določene redakcije strani, pred klikom gumba '''''Obnovi''''' označite ustrezna potrditvena polja.
Klik gumba '''''Ponastavi''''' bo izpraznil polje za vnos razloga in vsa potrditvena polja.",
'undeleterevisions'            => '{{PLURAL:$1|Arhivirana je|Arhivirani sta|Arhivirane so|Arhiviranih je|Arhiviranih ni}} $1 {{PLURAL:$1|redakcija|redakciji|redakcije|redakcij|redakcij}}',
'undeletehistory'              => 'Če obnovite stran, bodo v zgodovino obnovljene vse redakcije.
Če je bila po brisanju ustvarjena nova stran z enakim imenom, bodo obnovljene redakcije prikazane v prejšnji zgodovini.',
'undeleterevdel'               => 'Obnovitev ne bo izvedena, če vodi v delni izbris redakcije vrhnje strani ali datoteke.
V takem primeru morate odznačiti ali prikazati najnovejšo izbrisano redakcijo.',
'undeletehistorynoadmin'       => 'Stran je bila izbrisana.
Razlog za izbris je skupaj s podrobnostmi o uporabnikih, ki so jo urejali pred izbrisom, naveden v prikazanem povzetku.
Dejansko besedilo izbrisanih redakcij je dostopno le administratorjem.',
'undelete-revision'            => 'Izbrisana redakcija $1 (dne $4 ob $5) uporabnika $3:',
'undeleterevision-missing'     => 'Napačna ali manjkajoča redakcija.
Morda imate napačno povezavo ali pa je bila redakcija obnovljena ali odstranjena iz arhiva.',
'undelete-nodiff'              => 'Predhodnih različic ne najdem.',
'undeletebtn'                  => 'Obnovi',
'undeletelink'                 => 'poglej/obnovi',
'undeleteviewlink'             => 'ogled',
'undeletereset'                => 'Ponastavi',
'undeleteinvert'               => 'Obrni izbor',
'undeletecomment'              => 'Razlog:',
'undeletedarticle'             => 'je obnovil(-a) »$1«',
'undeletedrevisions'           => '{{PLURAL:$1|obnovljena $1 redakcija|obnovljeni $1 redakciji|obnovljene $1 redakcije|obnovljenih $1 redakcij}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|redakcija|redakciji|redakcije|redakcij}} in $2 {{PLURAL:$2|datoteka|datoteki|datoteke|datotek}} {{PLURAL:$1+$2|obnovljena|obnovljeni|obnovljene|obnovljenih}}',
'undeletedfiles'               => '{{PLURAL:$1|obnovljena je $1 datoteka|obnovljeni sta $1 datoteki|obnovljene so $1 datoteke|obnovljenih je $1 datotek}}',
'cannotundelete'               => 'Obnova ni uspela;
morda je stran obnovil že kdo drug.',
'undeletedpage'                => "'''Obnovili ste stran $1.'''

Nedavna brisanja in obnove so zapisani v [[Special:Log/delete|dnevniku brisanja]].",
'undelete-header'              => 'Glej [[Special:Log/delete|dnevnik brisanja]] za nedavno izbrisane strani.',
'undelete-search-box'          => 'Išči izbrisane strani',
'undelete-search-prefix'       => 'Prikaži strani, ki se začnejo na:',
'undelete-search-submit'       => 'Iskanje',
'undelete-no-results'          => 'Ne najdem ujemajočih strani v arhivu izbrisov.',
'undelete-filename-mismatch'   => 'Redakcije datoteke s časovnim žigom $1 ni mogoče obnoviti: ime datoteke se ne ujema',
'undelete-bad-store-key'       => 'Redakcije datoteke s časovnim žigom $1 ni mogoče obnoviti: datoteka je manjkala pred izbrisom.',
'undelete-cleanup-error'       => 'Napaka pri brisanju neuporabljene arhivske datoteke »$1«.',
'undelete-missing-filearchive' => 'Ne morem obnoviti datoteke arhiva ID $1, ker ga ni v zbirki podatkov.
Morda je bil že obnovljen.',
'undelete-error-short'         => 'Napaka pri obnavljanju datoteke: $1',
'undelete-error-long'          => 'Pri obnavljanju datoteke je prišlo do napak:

$1',
'undelete-show-file-confirm'   => 'Ali si resnično želite ogledati izbrisano redakcijo datoteke »<nowiki>$1</nowiki>« z dne $2 ob $3?',
'undelete-show-file-submit'    => 'Da',

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

'sp-contributions-newbies'             => 'Prikaži samo prispevke novih računov',
'sp-contributions-newbies-sub'         => 'Prispevki novincev',
'sp-contributions-newbies-title'       => 'Uporabniški prispevki novih računov',
'sp-contributions-blocklog'            => 'dnevnik blokiranja',
'sp-contributions-deleted'             => 'izbrisani uporabnikovi prispevki',
'sp-contributions-uploads'             => 'naložene datoteke',
'sp-contributions-logs'                => 'dnevniki',
'sp-contributions-talk'                => 'pogovor',
'sp-contributions-userrights'          => 'upravljanje s pravicami uporabnikov',
'sp-contributions-blocked-notice'      => 'Ta uporabnik je trenutno blokiran.
Najnovejši vnos v dnevniku blokad je naveden spodaj:',
'sp-contributions-blocked-notice-anon' => 'Ta IP-naslov je trenutno blokiran.
Najnovejši vnos v dnevniku blokad je naveden spodaj:',
'sp-contributions-search'              => 'Išči prispevke',
'sp-contributions-username'            => 'IP-naslov ali uporabniško ime:',
'sp-contributions-toponly'             => 'Prikaži samo vrhnje redakcije',
'sp-contributions-submit'              => 'Išči',

# What links here
'whatlinkshere'            => 'Kaj se povezuje sem',
'whatlinkshere-title'      => 'Strani, ki se povezujejo na $1',
'whatlinkshere-page'       => 'Stran:',
'linkshere'                => "Na '''[[:$1]]''' kažejo naslednje strani:",
'nolinkshere'              => "Nobena stran ne kaže na '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nobena stran se ne povezuje na '''[[:$1]]''' v izbranem imenskem prostoru.",
'isredirect'               => 'preusmeritvena stran',
'istemplate'               => 'vključitev',
'isimage'                  => 'povezava na sliko',
'whatlinkshere-prev'       => '{{PLURAL:$1|prejšnji|prejšnja $1|prejšnji $1|prejšnjih $1|prejšnjih $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|naslednji|naslednja $1|naslednji $1|naslednjih $1|naslednjih $1}}',
'whatlinkshere-links'      => '← povezave',
'whatlinkshere-hideredirs' => '$1 preusmeritve',
'whatlinkshere-hidetrans'  => '$1 vključitve',
'whatlinkshere-hidelinks'  => '$1 povezave',
'whatlinkshere-hideimages' => '$1 povezave slik',
'whatlinkshere-filters'    => 'Filtri',

# Block/unblock
'blockip'                         => 'Blokiranje IP-naslova ali uporabniškega imena',
'blockip-title'                   => 'Blokiraj uporabnika',
'blockip-legend'                  => 'Blokiraj uporabnika',
'blockiptext'                     => "Naslednji obrazec vam omogoča, da določenemu IP-naslovu ali uporabniškemu imenu preprečite urejanje.
To storimo le zaradi zaščite pred nepotrebnim uničevanjem in po [[{{MediaWiki:Policy-url}}|pravilih]].
Vnesite tudi razlog (''na primer'' seznam strani, ki jih je uporabnik po nepotrebnem kvaril).",
'ipaddress'                       => 'IP-naslov',
'ipadressorusername'              => 'IP-naslov ali uporabniško ime',
'ipbexpiry'                       => 'Pretek',
'ipbreason'                       => 'Razlog:',
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
'ipbemailban'                     => 'Uporabniku prepreči pošiljanje e-pošte',
'ipbenableautoblock'              => 'Samodejno blokiraj zadnji IP-naslov tega uporabnika in vse naslednje naslove, ki jih bodo hoteli uporabiti',
'ipbsubmit'                       => 'Blokiraj naslov',
'ipbother'                        => 'Drugačen čas',
'ipboptions'                      => '2 uri:2 hours,1 dan:1 day,3 dni:3 days,1 teden:1 week,2 tedna:2 weeks,1 mesec:1 month,3 mesece:3 months,6 mesecev:6 months,1 leto:1 year,neomejeno dolgo:infinite',
'ipbotheroption'                  => 'drugo',
'ipbotherreason'                  => 'Drug/dodaten razlog:',
'ipbhidename'                     => 'Skrij uporabniško ime iz urejanja in seznamov',
'ipbwatchuser'                    => 'Nadzoruj uporabnikovo uporabniško in pogovorno stran',
'ipballowusertalk'                => 'Dovoli temu uporabniku, da med blokado ureja svojo pogovorno stran',
'ipb-change-block'                => 'Ponovno blokiraj uporabnika s temi nastavitvami',
'badipaddress'                    => 'Neveljaven IP-naslov ali uporabniško ime.',
'blockipsuccesssub'               => 'Blokiranje je uspelo',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] je {{GENDER:$1|blokiran|blokirana|blokiran(-a)}}.<br />
Oglejte si [[Special:IPBlockList|seznam blokiranih IP-naslovov]] za pregled blokad.',
'ipb-edit-dropdown'               => 'Uredi razloge blokade',
'ipb-unblock-addr'                => 'Deblokiraj $1',
'ipb-unblock'                     => 'Odblokirajte uporabniško ime ali IP-naslov',
'ipb-blocklist'                   => 'Ogled obstoječih blokad',
'ipb-blocklist-contribs'          => 'Prispevki za $1',
'unblockip'                       => 'Deblokirajte uporabnika',
'unblockiptext'                   => 'Z naslednjim obrazcem obnovite možnost urejanja z blokiranega IP-naslova ali uporabniškega računa.',
'ipusubmit'                       => 'Odstrani blokado',
'unblocked'                       => '[[User:$1|$1]] je bil odblokiran',
'unblocked-id'                    => 'Blokada $1 je odstranjena',
'ipblocklist'                     => 'Seznam blokiranih IP-naslovov in uporabniških imen',
'ipblocklist-legend'              => 'Najdi blokiranega uporabnika',
'ipblocklist-username'            => 'Uporabniško ime ali IP-naslov:',
'ipblocklist-sh-userblocks'       => '$1 blokade računov',
'ipblocklist-sh-tempblocks'       => '$1 začasne blokade',
'ipblocklist-sh-addressblocks'    => '$1 blokade enega IP-naslova',
'ipblocklist-submit'              => 'Išči',
'ipblocklist-localblock'          => 'Lokalna blokada',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Druga blokada|Drugi blokadi|Druge blokade}}',
'blocklistline'                   => '$1, $2 je blokiral(-a) $3 ($4)',
'infiniteblock'                   => 'neomejen čas',
'expiringblock'                   => 'preteče $1 ob $2',
'anononlyblock'                   => 'samo brezim.',
'noautoblockblock'                => 'samodejna blokada je onemogočena',
'createaccountblock'              => 'ustvarjanje računov onemogočeno',
'emailblock'                      => 'e-pošta blokirana',
'blocklist-nousertalk'            => 'preprečeno urejanje lastne pogovorne strani',
'ipblocklist-empty'               => 'Seznam blokad je prazen.',
'ipblocklist-no-results'          => 'Zahtevan IP-naslov ali uporabniško ime ni blokirano.',
'blocklink'                       => 'blokiraj',
'unblocklink'                     => 'deblokiraj',
'change-blocklink'                => 'spremeni blokado',
'contribslink'                    => 'prispevki',
'autoblocker'                     => 'Urejanje vam je bilo samodejno onemogočeno, saj je vaš IP-naslov pred kratkim uporabljal »[[User:$1|$1]]«.
Razlog za blokado uporabnika $1 je: »$2«',
'blocklogpage'                    => 'Dnevnik blokiranja',
'blocklog-showlog'                => 'Ta uporabnik je že bil blokiran.
Dnevnik blokiranja je na voljo spodaj:',
'blocklog-showsuppresslog'        => 'Ta uporabnik je že bil blokiran in skrit.
Dnevnik skrivanja je na voljo spodaj:',
'blocklogentry'                   => '[[$1]] blokiran s časom poteka blokade $2 $3',
'reblock-logentry'                => 'spremenil nastavitve blokade za [[$1]] z iztekom dne $2 ob $3',
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
'ipb_expiry_temp'                 => 'Blokade skritih uporabniških imen morajo biti trajne.',
'ipb_hide_invalid'                => 'Ne morem skriti tega računa; morda ima preveč urejanj.',
'ipb_already_blocked'             => '"$1" je že blokiran',
'ipb-needreblock'                 => '== Uporeabnik je že blokiran ==
$1 je že blokiran.
Ali želite spremeniti nastavitve blokade?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Druga blokada|Drugi blokadi|Druge blokade}}',
'ipb_cant_unblock'                => 'Napaka: blokade št. $1 ni moč najti. Morda je bila že odstranjena.',
'ipb_blocked_as_range'            => 'Napaka: IP-naslov $1 ni blokiran direktno in ga zato ni mogoče odblokirati.
Je pa blokiran kot del območja $2, ki ga lahko odblokirate.',
'ip_range_invalid'                => 'Neveljaven IP-razpon.',
'ip_range_toolarge'               => 'Območja blokade večja od /$1 niso dovoljena.',
'blockme'                         => 'Blokiraj me',
'proxyblocker'                    => 'Blokator posredniških strežnikov',
'proxyblocker-disabled'           => 'Ta funkcija je onemogočena.',
'proxyblockreason'                => 'Ker uporabljate odprti posredniški strežnik, je urejanje z vašega IP-naslova preprečeno.
Gre za resno varnostno težavo, o kateri obvestite svojega internetnega ponudnika ali tehnično podporo.',
'proxyblocksuccess'               => 'Storjeno.',
'sorbsreason'                     => 'Vaš IP-naslov je v DNSBL uvrščen med odprte posredniške strežnike.',
'sorbs_create_account_reason'     => 'Vaš IP-naslov je v DNSBL, ki ga uporablja {{GRAMMAR:tožilnik|{{SITENAME}}}}, naveden kot odprti posredniški strežnik (proxy).
Računa žal ne morete ustvariti.',
'cant-block-while-blocked'        => 'Ne morete blokirati drugih uporabnikove, medtem ko ste sami blokirani.',
'cant-see-hidden-user'            => 'Uporabnik, ki ga poskušate blokirate, je že blokiran in skrit.
Ker nimate pravice hideuser, si ne morete ogledati ali urejati uporabnikove blokade.',
'ipbblocked'                      => 'Ne morete blokirati ali deblokirati drugih uporabnikov, saj ste sami blokirani',
'ipbnounblockself'                => 'Ni vam dovoljeno deblokirati samega sebe',

# Developer tools
'lockdb'              => 'Zakleni zbirko podatkov',
'unlockdb'            => 'Odkleni zbirko podatkov',
'lockdbtext'          => 'Zaklenitev zbirke podatkov bo vsem uporabnikom preprečila možnost urejanja strani, spreminjanja nastavitev, urejanja spiska nadzorov in drugih stvari, ki zahtevajo spremembe zbirke podatkov.
Prosimo, potrdite, da jo resnično želite zakleniti in da jo boste po končanem vzdrževanju spet odklenili.',
'unlockdbtext'        => 'Odklenitev zbirke podatkov bo vsem uporabnikom obnovila možnost urejanja strani, spreminjanja nastavitev, urejanja seznamov nadzorov in drugih stvari, ki zahtevajo spremembe zbirke.
Prosimo, potrdite nedvomni namen.',
'lockconfirm'         => 'Da, zbirko podatkov želim zakleniti.',
'unlockconfirm'       => 'Da, zbirko podatkov želim odkleniti.',
'lockbtn'             => 'Zakleni zbirko podatkov',
'unlockbtn'           => 'Odkleni zbirko podatkov',
'locknoconfirm'       => 'Niste označili potrditvenega polja.',
'lockdbsuccesssub'    => 'Zbirko podatkov ste uspešno zaklenili',
'unlockdbsuccesssub'  => 'Zbirka podatkov je odklenjena',
'lockdbsuccesstext'   => 'Podatkovna baza je bila zaklenjena.<br />
Ne pozabite je [[Special:UnlockDB|odkleniti]], ko boste končali z vzdrževanjem.',
'unlockdbsuccesstext' => 'Zbirka podatkov {{GRAMMAR:rodilnik|{{SITENAME}}}} je spet odklenjena.',
'lockfilenotwritable' => 'Datoteka zaklepanja zbirke podatkov ni zapisljiva.
Za zaklepanje in odklepanje zbirke podatkov mora biti ta datoteka zapisljiva s strani spletnega strežnika.',
'databasenotlocked'   => 'Zbirka podatkov ni zaklenjena.',

# Move page
'move-page'                    => 'Prestavi $1',
'move-page-legend'             => 'Prestavitev strani',
'movepagetext'                 => "Z naslednjim obrazcem lahko stran preimenujete in hkrati prestavite tudi vso njeno zgodovino.
Dosedanja stran se bo spremenila v preusmeritev na prihodnje mesto.
Samodejno lahko posodobite preusmeritve, ki kažejo na dosedanji naslov.
Če se za to ne odločite, ne pozabite preveriti vseh [[Special:DoubleRedirects|dvojnih]] ali [[Special:BrokenRedirects|pretrganih preusmeritev]].
Odgovorni ste, da bodo povezave še naprej kazale na prava mesta.

Kjer stran z izbranim novim imenom že obstaja, dejanje '''ne''' bo izvedeno, razen če je sedanja stran prazna ali preusmeritvena in brez zgodovine urejanj.
To pomeni, da lahko, če se zmotite, strani vrnete prvotno ime, ne morete pa prepisati že obstoječe strani.

'''Opozorilo!'''
Prestavitev strani je lahko za priljubljeno stran velika in nepričakovana sprememba, zato pred izbiro ukaza dobro premislite.",
'movepagetext-noredirectfixer' => "Z uporabo spodnjega obrazca lahko preimenujete stran tako, da prestavite vso njeno zgodovino na novo ime.
Star naslov bo postal preusmeritvena stran na nov naslov.
Ne pozabite preveriti [[Special:DoubleRedirects|dvojnih]] ali [[Special:BrokenRedirects|pretrganih preusmeritev]].
Vi ste odgovorni, da vse povezave še naprej kažejo tja, kamor naj bi.

Upoštevajte, da stran '''ne''' bo prestavljena, če že obstaja stran z novim naslovom, razen če je prazna ali preusmeritev brez pretekle zgodovine urejanj.
To pomeni, da lahko stran preimenujete nazaj, če ste naredili napako, vendar ne morete prepisati obstoječe strani.

'''Opozorilo!'''
To je lahko velika in nepričakovana sprememba za priljubljeno stran;
prosimo, pred nadaljevanjem se prepričajte, da razumete posledice tega dejanja.",
'movepagetalktext'             => "Če obstaja, bo samodejno prestavljena tudi pripadajoča pogovorna stran, '''razen kadar'''
*pod novim imenom že obstaja neprazna pogovorna stran ali
*ste odkljukali spodnji okvirček.

Če je tako, boste morali pogovorno stran, če želite, prestaviti ali povezati ročno.",
'movearticle'                  => 'Prestavi stran:',
'moveuserpage-warning'         => "'''Opozorilo:''' Premikate uporabniško stran. To pomeni, da bo premaknjena samo stran in uporabnik ''ne'' bo preimenovan.",
'movenologin'                  => 'Niste prijavljeni',
'movenologintext'              => 'Za prestavljanje strani morate biti registrirani in [[Special:UserLogin|prijavljeni]].',
'movenotallowed'               => 'Nimate dovoljenja, da premikate strani.',
'movenotallowedfile'           => 'Nimate dovoljenja, da premikate datoteke.',
'cant-move-user-page'          => 'Nimate dovoljenja, da premikate uporabniške strani (razen podstrani).',
'cant-move-to-user-page'       => 'Nimate dovoljenja, da premikate strani na uporabniške strani (razen na uporabniške podstrani).',
'newtitle'                     => 'Na naslov:',
'move-watch'                   => 'Opazuj to stran',
'movepagebtn'                  => 'Prestavi stran',
'pagemovedsub'                 => 'Uspešno prestavljeno',
'movepage-moved'               => "Stran '''»$1«''' je prestavljena na naslov '''»$2«'''.",
'movepage-moved-redirect'      => 'Preusmeritev je bila ustvarjena.',
'movepage-moved-noredirect'    => 'Izdelava preusmeritve je bila zatrta.',
'articleexists'                => 'Izbrano ime je že zasedeno ali pa ni veljavno.
Prosimo, izberite drugo ime.',
'cantmove-titleprotected'      => 'Strani ne morete premakniti na slednjo lokacijo, saj je nov naslov zaščiten pred ustvarjanjem',
'talkexists'                   => "'''Sama stran je bila uspešno prestavljena, pripadajoča pogovorna stran pa ne, ker že obstaja na novem naslovu.
Prosimo, združite ju ročno.'''",
'movedto'                      => 'prestavljeno na',
'movetalk'                     => 'Prestavi tudi pogovorno stran',
'move-subpages'                => 'Premakni podstrani (do $1)',
'move-talk-subpages'           => 'Premakni podstrani pogovorne strani (do $1)',
'movepage-page-exists'         => 'Stran $1 že obstaja in je ni mogoče samodejno prepisati.',
'movepage-page-moved'          => 'Stran $1 je bila prestavljena na $2.',
'movepage-page-unmoved'        => 'Strani $1 ni bilo mogoče premakniti na $2.',
'movepage-max-pages'           => '{{PLURAL:$1|Premaknjena je bila največ $1 stran|Premaknjeni sta bili največ $1 strani|Premaknjene so bile največ $1 strani|Premaknjenih je bilo največ $1 strani}} in nobena več ne bo samodejno premaknjena.',
'1movedto2'                    => 'je prestavil(-a) [[$1]] na [[$2]]',
'1movedto2_redir'              => 'je prestavil(-a) [[$1]] na [[$2]] čez preusmeritev',
'move-redirect-suppressed'     => 'preusmeritev zatrta',
'movelogpage'                  => 'Dnevnik prestavljanja strani',
'movelogpagetext'              => 'Prikazujem seznam prestavljenih strani.',
'movesubpage'                  => '{{PLURAL:$1|Podstran|Podstrani}}',
'movesubpagetext'              => 'Ta stran ima $1 {{PLURAL:$1|podstran prikazano|podstrani prikazane}} spodaj.',
'movenosubpage'                => 'Ta stran nima podstrani.',
'movereason'                   => 'Razlog:',
'revertmove'                   => 'vrni',
'delete_and_move'              => 'Briši in prestavi',
'delete_and_move_text'         => '==Treba bi bilo brisati==

Ciljna stran »[[:$1]]« že obstaja. Ali jo želite, da bi pripravili prostor za prestavitev, izbrisati?',
'delete_and_move_confirm'      => 'Da, izbriši stran',
'delete_and_move_reason'       => 'Izbrisano z namenom pripraviti prostor za prestavitev',
'selfmove'                     => 'Izvirni in ciljni naslov sta enaka;
strani ni mogoče prestaviti samo vaše.',
'immobile-source-namespace'    => 'Ne morem premikati strani v imenskem prostoru »$1«',
'immobile-target-namespace'    => 'Ne morem premakniti strani v imenski prostor »$1«',
'immobile-target-namespace-iw' => 'Povezava interwiki ni veljaven cilj za premik strani.',
'immobile-source-page'         => 'Te strani ni mogoče prestaviti.',
'immobile-target-page'         => 'Ne morem premakniti na ta ciljni naslov.',
'imagenocrossnamespace'        => 'Ne morem premakniti datoteke izven imenskega prostora datotek',
'nonfile-cannot-move-to-file'  => 'Ne morem premakniti nedatoteko v imenski prostor datotek',
'imagetypemismatch'            => 'Nova končnica datoteke se ne ujema z njeno vrsto',
'imageinvalidfilename'         => 'Ciljno ime datoteke je neveljavno',
'fix-double-redirects'         => 'Posodobi vse preusmeritve, ki kažejo na prvotni naslov',
'move-leave-redirect'          => 'Na prejšnji strani ustvari preusmeritev',
'protectedpagemovewarning'     => "'''Opozorilo:''' Stran je bila zaklenjena, tako da jo lahko prestavljajo samo uporabniki z administratorskimi dovoljenji.
Najnovejši vnos v dnevniku je na voljo spodaj:",
'semiprotectedpagemovewarning' => "'''Opomba:''' Stran je bila zaklenjena, tako da jo lahko prestavljajo samo registrirani uporabniki.
Najnovejši vnos v dnevniku je na voljo spodaj:",
'move-over-sharedrepo'         => '== Datoteka obstaja ==
[[:$1]] obstaja v deljeni shrambi. Premik datoteke na ta naslov bo prepisalo deljeno datoteko.',
'file-exists-sharedrepo'       => 'Izbrano ime datoteke je že v uporabi v deljeni shrambi.
Prosimo, izberite drugo ime.',

# Export
'export'            => 'Izvoz strani',
'exporttext'        => 'Besedilo in urejevalno zgodovino ene ali več strani lahko izvozite v obliki XML.
To je mogoče uvoziti v drug wiki z uporabo MediaWiki preko [[Special:Import|strani za uvoz]].

Če želite izvoziti strani, v spodnje polje vpišite naslove (enega v vsako vrstico) in označite, ali želite vse prejšnje različice z vrsticami o zgodovini strani ali le trenutno različico s podatki o trenutnem urejanju.

Če gre za slednje, lahko uporabite tudi povezavo, npr. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] za
stran »[[{{MediaWiki:Mainpage}}]]«.',
'exportcuronly'     => 'Vključi le trenutno redakcijo, ne pa celotne zgodovine.',
'exportnohistory'   => "----
'''Opomba:''' Izvoz celotne zgodovine strani preko tega obrazca je zaradi preobremenjenosti strežnikov onemogočen.",
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
Za lokalizacijo in prevajanje obiščite [http://www.mediawiki.org/wiki/Localisation MediaWiki] in [http://translatewiki.net translatewiki.net] ter tako prispevajte k splošnemu prevodu programja.',
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
'djvu_page_error'          => 'Stran DjVu je izven območja',
'djvu_no_xml'              => 'Ni mogoče pridobiti XML za datoteko DjVu',
'thumbnail_invalid_params' => 'Neveljavni parametri za sličico',
'thumbnail_dest_directory' => 'Ne morem ustvariti ciljnega direktorija',
'thumbnail_image-type'     => 'Vrsta slike ni podprta',
'thumbnail_gd-library'     => 'Nepopolna konfiguracija knjižice GD: manjka funkcija $1',
'thumbnail_image-missing'  => 'Kaže, da datoteka manjka: $1',

# Special:Import
'import'                     => 'Uvoz strani',
'importinterwiki'            => 'Uvoz transwiki',
'import-interwiki-text'      => 'Izberite wiki in naslov strani za uvoz.
Datumi in imena urejevalcev redakcij bodo ohranjena.
Vsi uvozi med wikiji so zabeleženi v [[Special:Log/import|dnevniku uvozov]].',
'import-interwiki-source'    => 'Izvorni wiki/stran:',
'import-interwiki-history'   => 'Kopiraj vse dosedanje redakcije te strani',
'import-interwiki-templates' => 'Vključi vse predloge',
'import-interwiki-submit'    => 'Uvozi',
'import-interwiki-namespace' => 'Prenesi strani v imenski prostor:',
'import-upload-filename'     => 'Ime datoteke:',
'import-comment'             => 'Komentar:',
'importtext'                 => 'Prosimo, izvozite datoteko iz izvornega wikija s pomočjo [[Special:Export|orodja za izvoz]].
Shranite jo na vaš računalnik in naložite tukaj.',
'importstart'                => 'Uvažam strani ...',
'import-revision-count'      => '$1 {{PLURAL:$1|redakcija|redakciji|redakcije|redakcij|redakcij}}',
'importnopages'              => 'Ni strani za uvoz.',
'imported-log-entries'       => '{{PLURAL:$1|Uvožen je bil $1 dnevniški vnos|Uvožena sta bila $1 dnevniška vnosa|Uvoženi so bili $1 dnevniški vnosi|Uvoženih je bilo $1 dnevniških vnosov}}.',
'importfailed'               => 'Uvoz ni uspel: $1',
'importunknownsource'        => 'Neznana vrsta vira uvoza',
'importcantopen'             => 'Neuspešno odpiranje uvožene datoteke',
'importbadinterwiki'         => 'Slaba jezikovna povezava',
'importnotext'               => 'Prazno ali brez besedila',
'importsuccess'              => 'Uspešno uvoženo!',
'importhistoryconflict'      => 'Zgodovina strani vključuje navzkrižno redakcijo (morda je bila stran naložena že prej)',
'importnosources'            => 'Na tem wikiju je ta možnost onemogočena.',
'importnofile'               => 'Uvožena ni bila nobena datoteka.',
'importuploaderrorsize'      => 'Nalaganje datoteke za uvoz ni uspelo.
Datoteka je večja od dovoljene velikosti nalaganja.',
'importuploaderrorpartial'   => 'Nalaganje datoteke za uvoz ni uspelo.
Datoteka je bila prenesena samo delno.',
'importuploaderrortemp'      => 'Nalaganje datoteke za uvoz ni uspelo.
Manjka začasna mapa.',
'import-parse-failure'       => 'Neuspeh razčlenitve uvoza XML',
'import-noarticle'           => 'Ni strani za uvoz!',
'import-nonewrevisions'      => 'Vse redakcije so bile že prej uvožene.',
'xml-error-string'           => '$1 v vrstici $2, znak $3 (bajt $4): $5',
'import-upload'              => 'Naložite podatke XML',
'import-token-mismatch'      => 'Izguba podatkov o seji.
Prosimo, poskusite znova.',
'import-invalid-interwiki'   => 'Uvoz iz navedenega wikija ni možen.',

# Import log
'importlogpage'                    => 'Dnevnik uvozov',
'importlogpagetext'                => 'Administrativni uvozi strani z zgodovino urejanja iz drugih wikijev.',
'import-logentry-upload'           => 'uvozil [[$1]] z nalaganjem datoteke',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|redakcija|redakciji|redakcije|redakcij|redakcij}}',
'import-logentry-interwiki'        => 'prenesel $1 med wikiji',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|redakcija|redakciji|redakcije|redakcij}} uporabnika $2',

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
'tooltip-ca-unprotect'            => 'Odstranite zaščito strani',
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
'tooltip-feed-rss'                => 'Dovod strani RSS',
'tooltip-feed-atom'               => 'Dovod strani Atom',
'tooltip-t-contributions'         => 'Preglejte seznam uporabnikovih prispevkov',
'tooltip-t-emailuser'             => 'Pošljite uporabniku e-pismo',
'tooltip-t-upload'                => 'Naložite slike ali predstavnostne datoteke',
'tooltip-t-specialpages'          => 'Preglejte seznam vseh posebnih strani',
'tooltip-t-print'                 => 'Natisljiva različica strani',
'tooltip-t-permalink'             => 'Stalna povezava na to različico strani',
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
'tooltip-recreate'                => 'Ponovno ustvari stran, čeprav je bila izbrisana',
'tooltip-upload'                  => 'Pričnite z nalaganjem',
'tooltip-rollback'                => 'Funkcija »Vrni« z enim klikom povrne vsa urejanja zadnjega urejevalca te strani',
'tooltip-undo'                    => '"Razveljavi" vrne to urejanje in odpre predogled v oknu za urejanje.
Omogoča vnos pojasnila v povzetku urejanja.',
'tooltip-preferences-save'        => 'Shrani nastavitve',
'tooltip-summary'                 => 'Vnesite kratek povzetek',

# Metadata
'nodublincore'      => 'Metapodatki Dublin Core RDF so na tem strežniku onemogočeni.',
'nocreativecommons' => 'Metapodatki Creative Commons RDF so za ta strežnik onemogočeni.',
'notacceptable'     => 'V obliki, ki jo lahko bere vaš odjemalec, wikistrežnik podatkov ne more ponuditi.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Brezimni uporabnik|Brezimna uporabnika|Brezimni uporabniki}} {{GRAMMAR:rodilnik|{{SITENAME}}}}',
'siteuser'         => 'uporabnik {{GRAMMAR:rodilnik|{{SITENAME}}}} $1',
'anonuser'         => 'Brezimni uporabnik {{GRAMMAR:rodilnik|{{SITENAME}}}} $1',
'lastmodifiedatby' => 'Stran je zadnjič urejal(-a) $3 (čas spremembe: $2, $1).',
'othercontribs'    => 'Temelji na delu $1.',
'others'           => 'drugi',
'siteusers'        => '{{PLURAL:$2|uporabnika|uporabnikov}} {{GRAMMAR:rodilnik|{{SITENAME}}}} $1',
'anonusers'        => '{{PLURAL:$2|Brezimni uporabnik|Brezimna uporabnika|Brezimni uporabniki}} {{GRAMMAR:rodilnik|{{SITENAME}}}} $1',
'creditspage'      => 'Zasluge za stran',
'nocredits'        => 'Ni dostopnih podatkov o zaslugah za stran.',

# Spam protection
'spamprotectiontitle' => 'Zaščita pred neželenimi sporočili',
'spamprotectiontext'  => "Stran, ki ste jo želeli shraniti, je zaščita pred spamom blokirala, saj je vsebovala povezavo na zunanjo stran, navedeno na črni listi spama. Če povezave (glejte spodaj) niste dodali vi, je verjetno obstajala že v prejšnji redakciji ali pa jo je dodalo vohunsko programje (''spyware'') na vašem računalniku.",
'spamprotectionmatch' => 'Naslednje besedilo je sprožilo naš filter neželenih sporočil: $1',
'spambot_username'    => 'Čiščenje navlake MediaWiki',
'spam_reverting'      => 'Vračanje na zadnjo redakcijo brez povezav na $1',
'spam_blanking'       => 'Vse redakcije so vsebovale povezave na $1, izpraznjujem',

# Info page
'infosubtitle'   => 'Podatki o strani',
'numedits'       => 'Število urejanj (stran): $1',
'numtalkedits'   => 'Število urejanj (pogovorna stran): $1',
'numwatchers'    => 'Število oseb, ki spremljajo stran: $1',
'numauthors'     => 'Število različnih avtorjev (stran): $1',
'numtalkauthors' => 'Število različnih avtorjev (pogovorna stran): $1',

# Math options
'mw_math_png'    => 'Vedno prikaži PNG',
'mw_math_simple' => 'Kadar je dovolj preprosto, uporabi HTML, sicer pa PNG',
'mw_math_html'   => 'Kadar je mogoče, uporabi HTML, sicer pa PNG',
'mw_math_source' => 'Pusti v TeX-ovi obliki (za besedilne brskalnike)',
'mw_math_modern' => 'Priporočeno za sodobne brskalnike',
'mw_math_mathml' => 'Če je le mogoče, uporabi MathML (preizkusno)',

# Math errors
'math_failure'          => 'Ni mi uspelo razčleniti',
'math_unknown_error'    => 'neznana napaka',
'math_unknown_function' => 'neznana funkcija',
'math_lexing_error'     => 'slovarska napaka',
'math_syntax_error'     => 'skladenjska napaka',
'math_image_error'      => 'Pretvarjanje v PNG ni uspelo; preverite, ali sta latex in dvips (ali dvips + gs + convert) pravilno nameščena.',
'math_bad_tmpdir'       => 'Začasne mape za math ne morem ustvariti ali pisati vanjo.',
'math_bad_output'       => 'Izhodne mape za math ne morem ustvariti ali pisati vanjo.',
'math_notexvc'          => 'Manjka izvedbena datoteka texvc;
za njeno namestitev si poglejte math/README.',

# Patrolling
'markaspatrolleddiff'                 => 'Označite kot nadzorovano',
'markaspatrolledtext'                 => 'Označite stran kot nadzorovano',
'markedaspatrolled'                   => 'Označeno kot nadzorovano',
'markedaspatrolledtext'               => 'Izbrana redakcija [[:$1]] je bila označena kot nadzorovana.',
'rcpatroldisabled'                    => 'Spremljanje zadnjih sprememb je onemogočeno.',
'rcpatroldisabledtext'                => 'Spremljanje zadnjih sprememb je začasno onemogočeno.',
'markedaspatrollederror'              => 'Ni mogoče označiti kot pregledano',
'markedaspatrollederrortext'          => 'Določite redakcijo, ki jo želite označiti kot pregledano.',
'markedaspatrollederror-noautopatrol' => 'Svojih urejanj vam ni dovoljeno označiti kot nadzorovanih.',

# Patrol log
'patrol-log-page'      => 'Dnevnik patrulje',
'patrol-log-header'    => 'To je dnevnik nadzorovanih redakcij.',
'patrol-log-line'      => 'je označil $1 strani $2 kot preverjeno urejanje $3',
'patrol-log-auto'      => '(samodejno)',
'patrol-log-diff'      => 'redakcija $1',
'log-show-hide-patrol' => '$1 dnevnik nadzora',

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
'previousdiff' => '← Prejšnje urejanje',
'nextdiff'     => 'Novejše urejanje →',

# Media information
'mediawarning'         => "'''Opozorilo''': Tovrstni tip datotek lahko vsebuje zlonamerno kodo.
Z njenim zagonom lahko ogrozite vaš sistem.",
'imagemaxsize'         => "Omejitev velikosti slik:<br />''(za opisne strani datotek)''",
'thumbsize'            => 'Velikost sličice (thumbnail):',
'widthheight'          => '$1&nbsp;×&nbsp;$2',
'widthheightpage'      => '$1 × $2, $3 {{PLURAL:$3|stran|strani}}',
'file-info'            => 'Velikost datoteke: $1, MIME-vrsta: <code>$2</code>',
'file-info-size'       => '$1 × $2 točk, velikost datoteke: $3, MIME-vrsta: $4',
'file-nohires'         => '<small>Slika višje ločljivosti ni na voljo.</small>',
'svg-long-desc'        => 'datoteka SVG, v izvirniku $1 × $2 slikovnih točk, velikost datoteke: $3',
'show-big-image'       => 'Slika v višji ločljivosti',
'show-big-image-thumb' => '<small>Velikost predogleda: $1 × $2 točk</small>',
'file-info-gif-looped' => 'ponavljajoče',
'file-info-gif-frames' => '$1 {{PLURAL:$1|sličica|sličici|sličice|sličic}}',
'file-info-png-looped' => 'ponavljajoče',
'file-info-png-repeat' => 'predvajano {{PLURAL:$1|$1-krat}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|sličica|sličici|sličice|sličic}}',

# Special:NewFiles
'newimages'             => 'Galerija novih datotek',
'imagelisttext'         => 'Prikazujem $1 $2 {{PLURAL:$1|razvrščeno datoteko|razvrščeni datoteki|razvrščene datoteke|razvrščenih datotek|razvrščenih datotek}}.',
'newimages-summary'     => 'Ta posebna stran prikazuje najnovejše naložene datoteke.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Ime datoteke (ali njen del):',
'showhidebots'          => '($1 bote)',
'noimages'              => 'Nič ni videti.',
'ilsubmit'              => 'Išči',
'bydate'                => 'po datumu',
'sp-newimages-showfrom' => 'Prikaži datoteke, naložene od $2, $1 naprej',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1, $2&nbsp;×&nbsp;$3',

# Bad image list
'bad_image_list' => 'Oblika je naslednja:

Upoštevane so le postavke seznama (vrstice, ki se začnejo z *).
Prva povezava v vrstici mora biti povezava do neželene datoteke.
Vse nadaljne povezave v isti vrstici se štejejo za izjeme, tj. za strani, kjer je datoteka lahko vključena.',

# Metadata
'metadata'          => 'Metapodatki',
'metadata-help'     => 'Datoteka vsebuje še druge podatke, ki jih je verjetno dodal za njeno ustvaritev oziroma digitalizacijo uporabljeni fotografski aparat ali optični bralnik. Če je bila datoteka pozneje spremenjena, podatki sprememb morda ne izražajo popolnoma.',
'metadata-expand'   => 'Razširi seznam',
'metadata-collapse' => 'Skrči seznam',
'metadata-fields'   => 'V skrčeni razpredelnici metapodatkov EXIF bodo prikazana le v tem sporočilu našteta polja.
Druga bodo po privzetem skrita.
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
'exif-stripbytecounts'             => 'Zlogov na pas stiskanja',
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

'exif-unknowndate' => 'Neznan datum',

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

# Flash modes
'exif-flash-fired-0'    => 'Flash se ni sprožil',
'exif-flash-fired-1'    => 'Flash se je sprožil',
'exif-flash-return-0'   => 'stroboskop ni uporabil funkcije zaznavanja',
'exif-flash-return-2'   => 'stroboskop ni zaznal svetlobe',
'exif-flash-return-3'   => 'stroboskop je zaznal svetlobo',
'exif-flash-mode-1'     => 'obvezna sprožitev flasha',
'exif-flash-mode-2'     => 'preprečena sprožitev flasha',
'exif-flash-mode-3'     => 'samodejni način',
'exif-flash-function-1' => 'Ni možnosti flasha',
'exif-flash-redeye-1'   => 'način zmanjševanja učinka rdečih oči',

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
'edit-externally-help' => '(Za več informacij glejte [http://www.mediawiki.org/wiki/Manual:External_editors navodila za namestitev])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'vse',
'imagelistall'     => 'vse',
'watchlistall2'    => 'vse',
'namespacesall'    => 'vse',
'monthsall'        => 'vse',
'limitall'         => 'vse',

# E-mail address confirmation
'confirmemail'              => 'Potrditev naslova elektronske pošte',
'confirmemail_noemail'      => 'Nimate določenega veljavnega e-poštnega naslova v vaših [[Special:Preferences|uporabniških nastavitvah]].',
'confirmemail_text'         => 'Za uporabo e-poštnih možnosti {{GRAMMAR:rodilnik|{{SITENAME}}}} morate najprej potrditi svoj e-poštni naslov.
S klikom spodnjega gumba pošljite nanj potrditveno sporočilo in sledite prejetim navodilom.
Ali ste svoj e-poštni naslov že potrdili, lahko preverite v nastavitvah.',
'confirmemail_pending'      => 'Potrditveno geslo vam je že bilo poslano; če ste pred kratkim ustvarili svoj račun, boste na njega morali počakati nekaj minut da prispe, preden boste poskušali zahtevali novo geslo.',
'confirmemail_send'         => 'Pošlji mi potrditveno sporočilo',
'confirmemail_sent'         => 'Potrditveno e-sporočilo je bilo poslano.',
'confirmemail_oncreate'     => 'Potrditveno geslo je bilo poslano na vaš e-poštni naslov.
To geslo ni potrebno za vpis, vendar ga boste morali vnesti pred omogočanjem katere koli funkcije temelječe na e-pošti na wikiju.',
'confirmemail_sendfailed'   => 'Potrditvenega sporočila ni bilo mogoče poslati.
Prosimo, preverite, če niste naslova vnesli napačno.

Posrednik e-pošte je vrnil: $1',
'confirmemail_invalid'      => 'Potrditveno geslo je neveljavno. Morda je poteklo.',
'confirmemail_needlogin'    => 'Za potrditev svojega e-poštnega naslova se morate $1.',
'confirmemail_success'      => 'Vaš e-poštni naslov je potrjen. Zdaj se lahko prijavite in uporabljate wiki.',
'confirmemail_loggedin'     => 'Svoj elektronski naslov ste uspešno potrdili.',
'confirmemail_error'        => 'Vaša potrditev se žal ni shranila.',
'confirmemail_subject'      => 'Potrditev e-poštnega naslova',
'confirmemail_body'         => 'Nekdo, verjetno vi, z IP-naslovom $1,
je v {{GRAMMAR:dajalnik|{{SITENAME}}}} ustvaril račun »$2« in zanj vpisal ta elektronski naslov.

Da bi potrdili, da ta račun resnično pripada vam in s tem
lahko začeli uporabljati e-poštne storitve {{GRAMMAR:rodilnik|{{SITENAME}}}}, odprite naslednjo povezavo:

$3

Če tega *niste* napravili vi, sledite naslednji povezavi
in tako prekličite potrditev elektronskega naslova:

$5

Potrditvena koda bo potekla $4.',
'confirmemail_body_changed' => 'Nekdo, najverjetneje vi, je z IP-naslova $1
na strani {{SITENAME}} spremenil e-poštni naslov računa »$2« na ta naslov.

Da potrdite lastništvo tega računa in ponovno aktivirate
e-poštne funkcije na {{GRAMMAR:dajalnik|{{SITENAME}}}}, odprite to povezavo v vašem brskalniku:

$3

Če omenjeni račun *ni* vaš, sledite spodnji povezavi za preklic
potrditve e-poštnega naslova:

$5

Potrditvena koda poteče $4.',
'confirmemail_body_set'     => 'Nekdo, najverjetneje vi, je z IP-naslova $1
na strani {{SITENAME}} nastavil e-poštni naslov računa »$2« na ta naslov.

Da potrdite lastništvo tega računa in ponovno aktivirate
e-poštne funkcije na {{GRAMMAR:dajalnik|{{SITENAME}}}}, odprite to povezavo v vašem brskalniku:

$3

Če omenjeni račun *ni* vaš, sledite spodnji povezavi za preklic
potrditve e-poštnega naslova:

$5

Potrditvena koda poteče $4.',
'confirmemail_invalidated'  => 'Potrditev e-poštnega naslova preklicana',
'invalidateemail'           => 'Prekliči potrditev e-poštnega naslova',

# Scary transclusion
'scarytranscludedisabled' => '[Prevključevanje med wikiji je onemogočeno]',
'scarytranscludefailed'   => '[Pridobivanje predloge za $1 ni uspelo]',
'scarytranscludetoolong'  => '[Spletni naslov je predolg]',

# Trackbacks
'trackbackbox'      => 'Sledilniki članka:<br />
$1',
'trackbackremove'   => '([$1 Izbris])',
'trackbacklink'     => 'Sledilnik',
'trackbackdeleteok' => 'Sledilnik je uspešno izbrisan.',

# Delete conflict
'deletedwhileediting' => "'''Opozorilo''': Med vašim urejanjem je bila stran izbrisana!",
'confirmrecreate'     => "Medtem ko ste stran urejali, jo je uporabnik [[User:$1|$1]] ([[User talk:$1|pogovor]]) izbrisal z razlogom:
:''$2''
Prosimo, potrdite, da jo resnično želite znova ustvariti.",
'recreate'            => 'Ponovno ustvari',

'unit-pixel' => ' točk',

# action=purge
'confirm_purge_button' => 'Osveži',
'confirm-purge-top'    => 'Osvežim predpomnjenje strani?',
'confirm-purge-bottom' => 'Osvežitev strani počisti predpomnilnik in prisili prikaz najnovejše različice.',

# Multipage image navigation
'imgmultipageprev' => '← prejšnja stran',
'imgmultipagenext' => 'naslednja stran →',
'imgmultigo'       => 'Pojdi!',
'imgmultigoto'     => 'Pojdi na stran $1',

# Table pager
'ascending_abbrev'         => 'nar',
'descending_abbrev'        => 'pad',
'table_pager_next'         => 'Naslednja stran',
'table_pager_prev'         => 'Prejšnja stran',
'table_pager_first'        => 'Prva stran',
'table_pager_last'         => 'Zadnja stran',
'table_pager_limit'        => 'Prikaži $1 postavk na stran',
'table_pager_limit_label'  => 'Postavk na stran:',
'table_pager_limit_submit' => 'Pojdi',
'table_pager_empty'        => 'Ni zadetkov',

# Auto-summaries
'autosumm-blank'   => 'odstranjevanje celotne vsebine strani',
'autosumm-replace' => "Zamenjava strani s/z '$1'",
'autoredircomment' => 'preusmeritev na [[$1]]',
'autosumm-new'     => 'Nova stran z vsebino: $1',

# Live preview
'livepreview-loading' => 'Nalaganje ...',
'livepreview-ready'   => 'Nalaganje ... Pripravljen!',
'livepreview-failed'  => 'Predogled v živo je spodletel!
Poskusite normalni predogled.',
'livepreview-error'   => 'Povezovanje ni uspelo: $1 »$2«.
Poskusite normalni predogled.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Spremembe novejše od $1 {{PLURAL:$1|sekunde|sekund}} morda ne bodo prikazane na seznamu.',
'lag-warn-high'   => 'Zaradi visoke zasedenosti strežniških podatkovnih baz, spremembe novejše od $1 {{PLURAL:$1|sekunde|sekund}} morda ne bodo prikazane na seznamu.',

# Watchlist editor
'watchlistedit-numitems'       => 'Vaš spisek nadzorov vsebuje $1 {{PLURAL:$1|stran|strani}}, izključujoč pogovorne strani.',
'watchlistedit-noitems'        => 'Vaš spisek nadzorov je prazen.',
'watchlistedit-normal-title'   => 'Uredi spisek nadzorov',
'watchlistedit-normal-legend'  => 'Odstrani strani iz spiska nadzorov',
'watchlistedit-normal-explain' => 'Strani na vašem spisku nadzorov so prikazane spodaj.
Da odstranite stran, označite kvadratek poleg nje in kliknite »{{int:Watchlistedit-normal-submit}}«.
Lahko tudi [[Special:Watchlist/raw|uredite gol spisek]].',
'watchlistedit-normal-submit'  => 'Odstrani strani',
'watchlistedit-normal-done'    => 'Iz vašega spiska nadzorov {{PLURAL:$1|je bila odstranjena $1 stran|sta bili odstranjeni $1 strani|so bile odstranjene $1 strani|je bilo odstranjenih $1 strani}}:',
'watchlistedit-raw-title'      => 'Uredi gol spisek nadzorov',
'watchlistedit-raw-legend'     => 'Uredi gol spisek nadzorov',
'watchlistedit-raw-explain'    => 'Strani na vašem spisku nadzorov so prikazane spodaj in jih lahko urejate z dodajanjem in odstranjevanjem s seznama; vsak naslov je v svoji vrstici.
Ko končate, kliknite »{{int:Watchlistedit-raw-submit}}«.
Uporabite lahko tudi [[Special:Watchlist/edit|standardni urejevalnik]].',
'watchlistedit-raw-titles'     => 'Strani:',
'watchlistedit-raw-submit'     => 'Posodobi spisek nadzorov',
'watchlistedit-raw-done'       => 'Vaš spisek nadzorov je bil posodobljen.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Dodana je bila $1 stran|Dodani sta bili $1 strani|Dodane so bile $1 strani|Dodanih je bilo $1 strani}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Odstranjena je bila $1 stran|Odstranjeni sta bili 2 strani|Odstranjene so bile $1 strani|Odstranjenih je bilo $1 strani}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Oglej si ustrezne spremembe',
'watchlisttools-edit' => 'Poglej in uredi spisek nadzorov',
'watchlisttools-raw'  => 'Uredi gol spisek nadzorov',

# Core parser functions
'unknown_extension_tag' => 'Neznana razširitvena etiketa »$1«',
'duplicate-defaultsort' => "'''Opozorilo:''' Privzeti ključ razvrščanja »$2« prepiše prejšnji privzeti ključ razvrščanja »$1«.",

# Special:Version
'version'                          => 'Različica',
'version-extensions'               => 'Nameščene razširitve',
'version-specialpages'             => 'Posebne strani',
'version-parserhooks'              => 'Razširitve razčlenjevalnika',
'version-variables'                => 'Spremenljivke',
'version-antispam'                 => 'Preprečevanje smetja',
'version-skins'                    => 'Kože',
'version-other'                    => 'Ostalo',
'version-mediahandlers'            => 'Upravljavci predstavnostnih vsebin',
'version-hooks'                    => 'Razširitve',
'version-extension-functions'      => 'Funkcije razširitev',
'version-parser-extensiontags'     => 'Etikete razširitev razčlenjevalnika',
'version-parser-function-hooks'    => 'Funkcije razširitev razčlenjevalnika',
'version-skin-extension-functions' => 'Funkcije razširitve kože',
'version-hook-name'                => 'Ime razširitve',
'version-hook-subscribedby'        => 'Naročen s strani',
'version-version'                  => '(Različica $1)',
'version-license'                  => 'Dovoljenje',
'version-poweredby-credits'        => "Ta wiki poganja '''[http://www.mediawiki.org/ MediaWiki]''', vse pravice pridržave © 2001-$1 $2.",
'version-poweredby-others'         => 'drugi',
'version-license-info'             => 'MediaWiki je prosto programje; lahko ga razširjate in / ali spreminjate pod pogoji GNU General Public License, kot ga je objavila Free Software Foundation; bodisi License različice 2 ali (po vaši izbiri) katere koli poznejše različice.

MediaWiki je razširjan v upanju, da bo uporaben, vendar BREZ KAKRŠNEGA KOLI ZAGOTOVILA; tudi brez posrednega jamstva PRODAJNE VREDNOSTI ali PRIMERNOSTI ZA DOLOČEN NAMEN. Oglejte si GNU General Public License za več podrobnosti.

Skupaj s programom bi morali bi prejeti [{{SERVER}}{{SCRIPTPATH}}/COPYING kopijo GNU General Public License]; če je niste, pišite Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA ali jo [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html preberite na spletu].',
'version-software'                 => 'Nameščena programska oprema',
'version-software-product'         => 'Izdelek',
'version-software-version'         => 'Različica',

# Special:FilePath
'filepath'         => 'Pot do datoteke',
'filepath-page'    => 'Datoteka:',
'filepath-submit'  => 'Pojdi',
'filepath-summary' => 'Ta posebna stran vrne polno pot do datoteke.
Slike so prikazane v polni ločljivosti, druge vrste datotek pa se zaženejo v zanje določenih programih.

Vnesite ime datoteke brez predpone »{{ns:image}}:«.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Iskanje podvojenih datotek',
'fileduplicatesearch-summary'  => 'Iskanje podvojenih datotek temelji na podlagi njenih hashvrednosti.

Vnesite ime datoteke brez predpone »{{ns:image}}:«.',
'fileduplicatesearch-legend'   => 'Poišči dvojnik',
'fileduplicatesearch-filename' => 'Ime datoteke:',
'fileduplicatesearch-submit'   => 'Iskanje',
'fileduplicatesearch-info'     => '$1 × $2 pik<br />Velikost datoteke: $3<br />Vrsta MIME: $4',
'fileduplicatesearch-result-1' => 'Datoteka »$1« nima identičnih dvojnikov.',
'fileduplicatesearch-result-n' => 'Datoteka »$1« ima $2 {{PLURAL:$2|identični dvojnik|identična dvojnika|identične dvojnike|identičnih dvojnikov}}.',

# Special:SpecialPages
'specialpages'                   => 'Posebne strani',
'specialpages-note'              => '----
* Navadne posebne strani.
* <strong class="mw-specialpagerestricted">Omejene posebne strani.</strong>',
'specialpages-group-maintenance' => 'Vzdrževalna poročila',
'specialpages-group-other'       => 'Ostale posebne strani',
'specialpages-group-login'       => 'Prijavite se / registrirajte se',
'specialpages-group-changes'     => 'Zadnje spremembe in dnevniki',
'specialpages-group-media'       => 'Poročila o datotekah in nalaganja',
'specialpages-group-users'       => 'Uporabniki in pravice',
'specialpages-group-highuse'     => 'Strani visoke uporabe',
'specialpages-group-pages'       => 'Seznam strani',
'specialpages-group-pagetools'   => 'Orodja strani',
'specialpages-group-wiki'        => 'Podatki in orodja wiki',
'specialpages-group-redirects'   => 'Preusmerjajoče posebne strani',
'specialpages-group-spam'        => 'Orodja za spam',

# Special:BlankPage
'blankpage'              => 'Prazna stran',
'intentionallyblankpage' => 'Ta stran je namenoma prazna.',

# External image whitelist
'external_image_whitelist' => ' #Pustite to vrstico takšno, kot je<pre>
#Navedite odlomke običajnih izrazov (regular expressions) (samo del, ki gre med //) spodaj
#Ti bodo primerjani z URL-ji zunanjih (hotlinkanih) slik
#Tisti, ki se bodo ujemali, bodo prikazani kot slike; v nasprotnem primeru bo prikazana samo povezava do slike
#Vrstice, ki se začnejo z #, so obravnavane kot komentarji
#Zadeva je občutljiva na velikost črk

#Navedite vse izraze regex pod to vrstico. Pustite to vrstico takšno, kot je</pre>',

# Special:Tags
'tags'                    => 'Veljavne etikete sprememb',
'tag-filter'              => 'Filter [[Special:Tags|oznak]]:',
'tag-filter-submit'       => 'Filtriraj',
'tags-title'              => 'Etikete',
'tags-intro'              => 'Ta stran navaja etikete, s katerimi lahko programje označi urejanja, in njihov pomen.',
'tags-tag'                => 'Ime oznake',
'tags-display-header'     => 'Prikaz na seznamu sprememb',
'tags-description-header' => 'Polni opis pomena',
'tags-hitcount-header'    => 'Etiketirane spremembe',
'tags-edit'               => 'uredi',
'tags-hitcount'           => '$1 {{PLURAL:$1|sprememba|spremembi|spremembe|sprememb|sprememb}}',

# Special:ComparePages
'comparepages'     => 'Primerjaj strani',
'compare-selector' => 'Primerjaj redakcije strani',
'compare-page1'    => 'Stran 1',
'compare-page2'    => 'Stran 2',
'compare-rev1'     => 'Redakcija 1',
'compare-rev2'     => 'Redakcija 2',
'compare-submit'   => 'Primerjaj',

# Database error messages
'dberr-header'      => 'Ta wiki ima težavo',
'dberr-problems'    => 'Oprostite!
Ta stran se sooča s tehničnimi težavami.',
'dberr-again'       => 'Poskusite počakati nekaj minut in ponovno naložite stran.',
'dberr-info'        => '(Ne morem se povezati s strežnikom zbirke podatkov: $1)',
'dberr-usegoogle'   => 'V vmesnem času lahko poskusite z iskanjem preko Googla',
'dberr-outofdate'   => 'Pomnite, da so njegovi imeniki naših vsebin lahko zastareli.',
'dberr-cachederror' => 'To je shranjena kopija zahtevane strani, ki morda ni najnovejša.',

# HTML forms
'htmlform-invalid-input'       => 'Z delom vašega vnosa so težave',
'htmlform-select-badoption'    => 'Vrednost, ki ste jo vnesli, ni veljavna.',
'htmlform-int-invalid'         => 'Vrednost, ki ste jo vnesli, ni celo število.',
'htmlform-float-invalid'       => 'Vrednost, ki ste jo vnesli, ni število.',
'htmlform-int-toolow'          => 'Vrednost, ki ste jo vnesli, je manjša od najmanjše dovoljene vrednosti $1',
'htmlform-int-toohigh'         => 'Vrednost, ki ste jo vnesli, je večja od največje dovoljene vrednosti $1',
'htmlform-required'            => 'Ta vrednost je zahtevana',
'htmlform-submit'              => 'Pošlji',
'htmlform-reset'               => 'Razveljavi spremembe',
'htmlform-selectorother-other' => 'Drugo',

# SQLite database support
'sqlite-has-fts' => '$1 s podporo iskanju polnih besedil',
'sqlite-no-fts'  => '$1 brez podpore iskanju polnih besedil',

# Special:DisableAccount
'disableaccount'             => 'Onemogoči uporabniški račun',
'disableaccount-user'        => 'Uporabniško ime:',
'disableaccount-reason'      => 'Razlog:',
'disableaccount-confirm'     => "Onemogočite ta uporabniški račun.
Uporabnik se ne bo mogel prijaviti, ponastaviti svojega gesla ali prejemati e-poštnih obvestil.
Če je uporabnik trenutno kjer koli prijavljen, bo nemudoma odjavljen.
''Pomnite, da povrnitev onemogočitve računa ni mogoča brez posredovanja sistemskega upravljavca.''",
'disableaccount-mustconfirm' => 'Potrditi morate, da želite onemogočiti ta račun.',
'disableaccount-nosuchuser'  => 'Uporabniški račun »$1« ne obstaja.',
'disableaccount-success'     => 'Uporabniški račun »$1« je trajno onemogočen.',
'disableaccount-logentry'    => 'je trajno onemogočil(-a) uporabniški račun [[$1]]',

);
