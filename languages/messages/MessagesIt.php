<?php
/** Italian (italiano)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author .anaconda
 * @author Airon90
 * @author Amire80
 * @author Andria
 * @author Aushulz
 * @author Beta16
 * @author Blaisorblade
 * @author Broc
 * @author BrokenArrow
 * @author Brownout
 * @author Candalua
 * @author Civvì
 * @author Codicorumus
 * @author Cruccone
 * @author Cryptex
 * @author Dakrismeno
 * @author Danmaz74
 * @author Darth Kule
 * @author F. Cosoleto
 * @author Felis
 * @author FollowTheMedia
 * @author Gianfranco
 * @author HalphaZ
 * @author Kaganer
 * @author Klutzy
 * @author Marco 27
 * @author Martorell
 * @author Marzedu
 * @author McDutchie
 * @author Melos
 * @author Minerva Titani
 * @author Nemo bis
 * @author Nick1915
 * @author Ninniuz
 * @author Od1n
 * @author Oile11
 * @author Omnipaedista
 * @author PaoloRomano
 * @author Pietrodn
 * @author Pinodd
 * @author Ramac
 * @author Raoli
 * @author Remember the dot
 * @author Rippitippi
 * @author S.Örvarr.S
 * @author SabineCretella
 * @author Stefano-c
 * @author Tonyfroio
 * @author Trixt
 * @author Una giornata uggiosa '94
 * @author Vajotwo
 * @author Valepert
 * @author Ximo17
 * @author Xpensive
 * @author ZioNicco
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciale',
	NS_TALK             => 'Discussione',
	NS_USER             => 'Utente',
	NS_USER_TALK        => 'Discussioni_utente',
	NS_PROJECT_TALK     => 'Discussioni_$1',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'Discussioni_file',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussioni_MediaWiki',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Discussioni_template',
	NS_HELP             => 'Aiuto',
	NS_HELP_TALK        => 'Discussioni_aiuto',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Discussioni_categoria',
);

$namespaceAliases = array(
	'Immagine' => NS_FILE,
	'Discussioni_immagine' => NS_FILE_TALK,
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'H:i, M j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'H:i, j M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'H:i, Y M j',
);

$specialPageAliases = array(
	'Activeusers'               => array( 'UtentiAttivi' ),
	'Allmessages'               => array( 'Messaggi' ),
	'Allpages'                  => array( 'TutteLePagine' ),
	'Ancientpages'              => array( 'PagineMenoRecenti' ),
	'Badtitle'                  => array( 'TitoloErrato' ),
	'Blankpage'                 => array( 'PaginaVuota' ),
	'Block'                     => array( 'Blocca' ),
	'Blockme'                   => array( 'BloccaProxy' ),
	'Booksources'               => array( 'RicercaISBN' ),
	'BrokenRedirects'           => array( 'RedirectErrati' ),
	'Categories'                => array( 'Categorie' ),
	'ChangePassword'            => array( 'CambiaPassword' ),
	'ComparePages'              => array( 'ComparaPagine' ),
	'Confirmemail'              => array( 'ConfermaEMail' ),
	'Contributions'             => array( 'Contributi', 'ContributiUtente' ),
	'CreateAccount'             => array( 'CreaAccount' ),
	'Deadendpages'              => array( 'PagineSenzaUscita' ),
	'DeletedContributions'      => array( 'ContributiCancellati' ),
	'Disambiguations'           => array( 'Disambigua', 'Disambigue' ),
	'DoubleRedirects'           => array( 'RedirectDoppi' ),
	'EditWatchlist'             => array( 'ModifcaListaSeguiti' ),
	'Emailuser'                 => array( 'InviaEMail' ),
	'Export'                    => array( 'Esporta' ),
	'Fewestrevisions'           => array( 'PagineConMenoRevisioni' ),
	'FileDuplicateSearch'       => array( 'CercaFileDuplicati' ),
	'Filepath'                  => array( 'Percorso' ),
	'Import'                    => array( 'Importa' ),
	'Invalidateemail'           => array( 'InvalidaEMail' ),
	'BlockList'                 => array( 'IPBloccati', 'ElencoBlocchi', 'Blocchi' ),
	'LinkSearch'                => array( 'CercaCollegamenti' ),
	'Listadmins'                => array( 'Amministratori', 'ElencoAmministratori', 'Admin' ),
	'Listbots'                  => array( 'Bot', 'ElencoBot' ),
	'Listfiles'                 => array( 'File', 'Immagini' ),
	'Listgrouprights'           => array( 'ElencoPermessiGruppi' ),
	'Listredirects'             => array( 'Redirect', 'ElencoRedirect' ),
	'Listusers'                 => array( 'Utenti', 'ElencoUtenti' ),
	'Lockdb'                    => array( 'BloccaDB' ),
	'Log'                       => array( 'Registri', 'Registro' ),
	'Lonelypages'               => array( 'PagineOrfane' ),
	'Longpages'                 => array( 'PaginePiùLunghe' ),
	'MergeHistory'              => array( 'FondiCronologia', 'UnificaCronologia' ),
	'MIMEsearch'                => array( 'RicercaMIME' ),
	'Mostcategories'            => array( 'PagineConPiùCategorie' ),
	'Mostimages'                => array( 'ImmaginiPiùRichiamate' ),
	'Mostlinked'                => array( 'PaginePiùRichiamate' ),
	'Mostlinkedcategories'      => array( 'CategoriePiùRichiamate' ),
	'Mostlinkedtemplates'       => array( 'TemplatePiùRichiamati' ),
	'Mostrevisions'             => array( 'PagineConPiùRevisioni' ),
	'Movepage'                  => array( 'Sposta', 'Rinomina' ),
	'Mycontributions'           => array( 'MieiContributi' ),
	'Mypage'                    => array( 'MiaPaginaUtente', 'MiaPagina' ),
	'Mytalk'                    => array( 'MieDiscussioni' ),
	'Myuploads'                 => array( 'MieiUpload' ),
	'Newimages'                 => array( 'ImmaginiRecenti' ),
	'Newpages'                  => array( 'PaginePiùRecenti' ),
	'PasswordReset'             => array( 'ReimpostaPassword' ),
	'Popularpages'              => array( 'PaginePiùVisitate' ),
	'Preferences'               => array( 'Preferenze' ),
	'Prefixindex'               => array( 'Prefissi' ),
	'Protectedpages'            => array( 'PagineProtette' ),
	'Protectedtitles'           => array( 'TitoliProtetti' ),
	'Randompage'                => array( 'PaginaCasuale' ),
	'Randomredirect'            => array( 'RedirectCasuale' ),
	'Recentchanges'             => array( 'UltimeModifiche' ),
	'Recentchangeslinked'       => array( 'ModificheCorrelate' ),
	'Revisiondelete'            => array( 'CancellaRevisione' ),
	'Search'                    => array( 'Ricerca', 'Cerca' ),
	'Shortpages'                => array( 'PaginePiùCorte' ),
	'Specialpages'              => array( 'PagineSpeciali' ),
	'Statistics'                => array( 'Statistiche' ),
	'Tags'                      => array( 'Etichette' ),
	'Unblock'                   => array( 'ElencoSblocchi', 'Sblocchi' ),
	'Uncategorizedcategories'   => array( 'CategorieSenzaCategorie' ),
	'Uncategorizedimages'       => array( 'ImmaginiSenzaCategorie' ),
	'Uncategorizedpages'        => array( 'PagineSenzaCategorie' ),
	'Uncategorizedtemplates'    => array( 'TemplateSenzaCategorie' ),
	'Undelete'                  => array( 'Ripristina' ),
	'Unlockdb'                  => array( 'SbloccaDB' ),
	'Unusedcategories'          => array( 'CategorieNonUsate', 'CategorieVuote' ),
	'Unusedimages'              => array( 'ImmaginiNonUsate' ),
	'Unusedtemplates'           => array( 'TemplateNonUsati' ),
	'Unwatchedpages'            => array( 'PagineNonOsservate' ),
	'Upload'                    => array( 'Carica' ),
	'Userlogin'                 => array( 'Entra', 'Login' ),
	'Userlogout'                => array( 'Esci', 'Logout' ),
	'Userrights'                => array( 'PermessiUtente' ),
	'Version'                   => array( 'Versione' ),
	'Wantedcategories'          => array( 'CategorieRichieste' ),
	'Wantedfiles'               => array( 'FileRichiesti' ),
	'Wantedpages'               => array( 'PagineRichieste' ),
	'Wantedtemplates'           => array( 'TemplateRichiesti' ),
	'Watchlist'                 => array( 'OsservatiSpeciali' ),
	'Whatlinkshere'             => array( 'PuntanoQui' ),
	'Withoutinterwiki'          => array( 'PagineSenzaInterwiki' ),
);

$magicWords = array(
	'redirect'                => array( '0', '#RINVIA', '#RINVIO', '#RIMANDO', '#REDIRECT' ),
	'currentmonth'            => array( '1', 'MESECORRENTE', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'        => array( '1', 'NOMEMESECORRENTE', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'     => array( '1', 'NOMEMESECORRENTEGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'      => array( '1', 'MESECORRENTEABBREV', 'CURRENTMONTHABBREV' ),
	'currentday'              => array( '1', 'GIORNOCORRENTE', 'CURRENTDAY' ),
	'currentday2'             => array( '1', 'GIORNOCORRENTE2', 'CURRENTDAY2' ),
	'currentdayname'          => array( '1', 'NOMEGIORNOCORRENTE', 'CURRENTDAYNAME' ),
	'currentyear'             => array( '1', 'ANNOCORRENTE', 'CURRENTYEAR' ),
	'currenttime'             => array( '1', 'ORARIOATTUALE', 'CURRENTTIME' ),
	'currenthour'             => array( '1', 'ORACORRENTE', 'CURRENTHOUR' ),
	'localmonth'              => array( '1', 'MESELOCALE', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'          => array( '1', 'NOMEMESELOCALE', 'LOCALMONTHNAME' ),
	'localmonthnamegen'       => array( '1', 'NOMEMESELOCALEGEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'        => array( '1', 'MESELOCALEABBREV', 'LOCALMONTHABBREV' ),
	'localday'                => array( '1', 'GIORNOLOCALE', 'LOCALDAY' ),
	'localday2'               => array( '1', 'GIORNOLOCALE2', 'LOCALDAY2' ),
	'localdayname'            => array( '1', 'NOMEGIORNOLOCALE', 'LOCALDAYNAME' ),
	'localyear'               => array( '1', 'ANNOLOCALE', 'LOCALYEAR' ),
	'localtime'               => array( '1', 'ORARIOLOCALE', 'LOCALTIME' ),
	'localhour'               => array( '1', 'ORALOCALE', 'LOCALHOUR' ),
	'numberofpages'           => array( '1', 'NUMEROPAGINE', 'NUMBEROFPAGES' ),
	'numberofarticles'        => array( '1', 'NUMEROARTICOLI', 'NUMBEROFARTICLES' ),
	'numberoffiles'           => array( '1', 'NUMEROFILE', 'NUMBEROFFILES' ),
	'numberofusers'           => array( '1', 'NUMEROUTENTI', 'NUMBEROFUSERS' ),
	'numberofactiveusers'     => array( '1', 'NUMEROUTENTIATTIVI', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'           => array( '1', 'NUMEROEDIT', 'NUMBEROFEDITS' ),
	'numberofviews'           => array( '1', 'NUMEROVISITE', 'NUMBEROFVIEWS' ),
	'pagename'                => array( '1', 'TITOLOPAGINA', 'PAGENAME' ),
	'pagenamee'               => array( '1', 'TITOLOPAGINAE', 'PAGENAMEE' ),
	'subpagename'             => array( '1', 'NOMESOTTOPAGINA', 'SUBPAGENAME' ),
	'subpagenamee'            => array( '1', 'NOMESOTTOPAGINAE', 'SUBPAGENAMEE' ),
	'subst'                   => array( '0', 'SOST:', 'SUBST:' ),
	'img_right'               => array( '1', 'destra', 'right' ),
	'img_left'                => array( '1', 'sinistra', 'left' ),
	'img_none'                => array( '1', 'nessuno', 'none' ),
	'img_center'              => array( '1', 'centro', 'center', 'centre' ),
	'img_page'                => array( '1', 'pagina=$1', 'pagina $1', 'page=$1', 'page $1' ),
	'img_border'              => array( '1', 'bordo', 'border' ),
	'sitename'                => array( '1', 'NOMESITO', 'SITENAME' ),
	'servername'              => array( '0', 'NOMESERVER', 'SERVERNAME' ),
	'gender'                  => array( '0', 'GENERE:', 'GENDER:' ),
	'currentweek'             => array( '1', 'SETTIMANACORRENTE', 'CURRENTWEEK' ),
	'localweek'               => array( '1', 'SETTIMANALOCALE', 'LOCALWEEK' ),
	'plural'                  => array( '0', 'PLURALE:', 'PLURAL:' ),
	'language'                => array( '0', '#LINGUA', '#LANGUAGE:' ),
	'numberofadmins'          => array( '1', 'NUMEROADMIN', 'NUMBEROFADMINS' ),
	'special'                 => array( '0', 'speciale', 'special' ),
	'pagesincategory'         => array( '1', 'PAGINEINCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                => array( '1', 'DIMENSIONEPAGINA', 'PESOPAGINA', 'PAGESIZE' ),
	'index'                   => array( '1', '__INDICE__', '__INDEX__' ),
	'noindex'                 => array( '1', '__NOINDICE__', '__NOINDEX__' ),
	'protectionlevel'         => array( '1', 'LIVELLOPROTEZIONE', 'PROTECTIONLEVEL' ),
);

$linkTrail = '/^([a-zàéèíîìóòúù]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Sottolinea i collegamenti:',
'tog-highlightbroken'         => 'Evidenzia <a href="" class="new">così</a> i collegamenti a pagine inesistenti (se disattivato: così<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Allineamento dei paragrafi giustificato',
'tog-hideminor'               => 'Nascondi le modifiche minori nelle ultime modifiche',
'tog-hidepatrolled'           => 'Nascondi le modifiche verificate nelle ultime modifiche',
'tog-newpageshidepatrolled'   => "Nascondi le pagine verificate dall'elenco delle pagine più recenti",
'tog-extendwatchlist'         => "Mostra tutte le modifiche agli osservati speciali, non solo l'ultima",
'tog-usenewrc'                => 'Raggruppa le modifiche per pagina nelle ultime modifiche e negli osservati speciali (richiede JavaScript)',
'tog-numberheadings'          => 'Numerazione automatica dei titoli di sezione',
'tog-showtoolbar'             => 'Mostra barra degli strumenti di modifica (richiede JavaScript)',
'tog-editondblclick'          => 'Modifica delle pagine tramite doppio clic (richiede JavaScript)',
'tog-editsection'             => 'Modifica delle sezioni tramite il collegamento [modifica]',
'tog-editsectiononrightclick' => 'Modifica delle sezioni tramite clic destro sul titolo (richiede JavaScript)',
'tog-showtoc'                 => "Mostra l'indice per le pagine con più di 3 sezioni",
'tog-rememberpassword'        => 'Ricorda la password su questo browser (per un massimo di $1 {{PLURAL:$1|giorno|giorni}})',
'tog-watchcreations'          => 'Aggiungi le pagine create e i file caricati agli osservati speciali',
'tog-watchdefault'            => 'Aggiungi le pagine e i file modificati agli osservati speciali',
'tog-watchmoves'              => 'Aggiungi le pagine e i file spostati agli osservati speciali',
'tog-watchdeletion'           => 'Aggiungi le pagine e i file cancellati agli osservati speciali',
'tog-minordefault'            => 'Indica ogni modifica come minore (solo come predefinito)',
'tog-previewontop'            => "Mostra l'anteprima sopra la casella di modifica e non sotto",
'tog-previewonfirst'          => "Mostra l'anteprima per la prima modifica",
'tog-nocache'                 => 'Disabilita la cache delle pagine del browser',
'tog-enotifwatchlistpages'    => 'Inviami una email quando viene modificata una pagina o un file presente tra gli osservati speciali',
'tog-enotifusertalkpages'     => 'Segnalami via e-mail le modifiche alla mia pagina di discussione',
'tog-enotifminoredits'        => 'Inviami una email anche per le modifiche minori di pagine e file',
'tog-enotifrevealaddr'        => 'Rivela il mio indirizzo e-mail nei messaggi di avviso',
'tog-shownumberswatching'     => 'Mostra il numero di utenti che hanno la pagina in osservazione',
'tog-oldsig'                  => 'Firma attuale:',
'tog-fancysig'                => 'Tratta la firma come wikitesto (senza un collegamento automatico)',
'tog-externaleditor'          => "Usa per default un editor di testi esterno (solo per utenti esperti, richiede l'uso di impostazioni particolari sul proprio computer. [//www.mediawiki.org/wiki/Manual:External_editors Ulteriori informazioni.])",
'tog-externaldiff'            => "Usa per default un programma di diff esterno (solo per utenti esperti, richiede l'uso di impostazioni particolari sul proprio computer. [//www.mediawiki.org/wiki/Manual:External_editors Ulteriori informazioni.])",
'tog-showjumplinks'           => 'Attiva i collegamenti accessibili "vai a"',
'tog-uselivepreview'          => "Abilita la funzione ''Live preview'' (anteprima in diretta - richiede JavaScript; sperimentale)",
'tog-forceeditsummary'        => 'Chiedi conferma se il campo oggetto è vuoto',
'tog-watchlisthideown'        => 'Nascondi le mie modifiche negli osservati speciali',
'tog-watchlisthidebots'       => 'Nascondi le modifiche dei bot negli osservati speciali',
'tog-watchlisthideminor'      => 'Nascondi le modifiche minori negli osservati speciali',
'tog-watchlisthideliu'        => 'Nascondi le modifiche degli utenti registrati negli osservati speciali',
'tog-watchlisthideanons'      => 'Nascondi le modifiche degli utenti anonimi negli osservati speciali',
'tog-watchlisthidepatrolled'  => 'Nascondi le modifiche verificate negli osservati speciali',
'tog-nolangconversion'        => 'Disattiva la conversione tra varianti linguistiche',
'tog-ccmeonemails'            => 'Inviami una copia dei messaggi spediti agli altri utenti',
'tog-diffonly'                => 'Non visualizzare il contenuto della pagina dopo il confronto tra versioni',
'tog-showhiddencats'          => 'Mostra categorie nascoste',
'tog-noconvertlink'           => 'Disattiva la conversione dei titoli dei link',
'tog-norollbackdiff'          => 'Non mostrare il confronto tra versioni dopo aver effettuato un rollback',

'underline-always'  => 'Sempre',
'underline-never'   => 'Mai',
'underline-default' => 'Mantieni le impostazioni del browser o della skin',

# Font style option in Special:Preferences
'editfont-style'     => 'Stile del carattere nella casella di modifica:',
'editfont-default'   => 'Predefinito del browser',
'editfont-monospace' => 'Font monospazio',
'editfont-sansserif' => 'Font sans-serif',
'editfont-serif'     => 'Font serif',

# Dates
'sunday'        => 'domenica',
'monday'        => 'lunedì',
'tuesday'       => 'martedì',
'wednesday'     => 'mercoledì',
'thursday'      => 'giovedì',
'friday'        => 'venerdì',
'saturday'      => 'sabato',
'sun'           => 'dom',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mer',
'thu'           => 'gio',
'fri'           => 'ven',
'sat'           => 'sab',
'january'       => 'gennaio',
'february'      => 'febbraio',
'march'         => 'marzo',
'april'         => 'aprile',
'may_long'      => 'maggio',
'june'          => 'giugno',
'july'          => 'luglio',
'august'        => 'agosto',
'september'     => 'settembre',
'october'       => 'ottobre',
'november'      => 'novembre',
'december'      => 'dicembre',
'january-gen'   => 'gennaio',
'february-gen'  => 'febbraio',
'march-gen'     => 'marzo',
'april-gen'     => 'aprile',
'may-gen'       => 'maggio',
'june-gen'      => 'giugno',
'july-gen'      => 'luglio',
'august-gen'    => 'agosto',
'september-gen' => 'settembre',
'october-gen'   => 'ottobre',
'november-gen'  => 'novembre',
'december-gen'  => 'dicembre',
'jan'           => 'gen',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'mag',
'jun'           => 'giu',
'jul'           => 'lug',
'aug'           => 'ago',
'sep'           => 'set',
'oct'           => 'ott',
'nov'           => 'nov',
'dec'           => 'dic',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoria|Categorie}}',
'category_header'                => 'Pagine nella categoria "$1"',
'subcategories'                  => 'Sottocategorie',
'category-media-header'          => 'File nella categoria "$1"',
'category-empty'                 => "''Al momento la categoria non contiene alcuna pagina o file multimediale.''",
'hidden-categories'              => '{{PLURAL:$1|Categoria nascosta|Categorie nascoste}}',
'hidden-category-category'       => 'Categorie nascoste',
'category-subcat-count'          => "{{PLURAL:$2|Questa categoria contiene un'unica sottocategoria, indicata di seguito.|Questa categoria contiene {{PLURAL:$1|la sottocategoria indicata|le $1 sottocategorie indicate}} di seguito, su un totale di $2.}}",
'category-subcat-count-limited'  => 'Questa categoria contiene {{PLURAL:$1|una sottocategoria, indicata|$1 sottocategorie, indicate}} di seguito.',
'category-article-count'         => "{{PLURAL:$2|Questa categoria contiene un'unica pagina, indicata di seguito.|Questa categoria contiene {{PLURAL:$1|la pagina indicata|le $1 pagine indicate}} di seguito, su un totale di $2.}}",
'category-article-count-limited' => 'Questa categoria contiene {{PLURAL:$1|la pagina indicata|le $1 pagine indicate}} di seguito.',
'category-file-count'            => '{{PLURAL:$2|Questa categoria contiene un solo file, indicato di seguito.|Questa categoria contiene {{PLURAL:$1|un file, indicato|$1 file, indicati}} di seguito, su un totale di $2.}}',
'category-file-count-limited'    => 'Questa categoria contiene {{PLURAL:$1|il file indicato|i $1 file indicati}} di seguito.',
'listingcontinuesabbrev'         => 'cont.',
'index-category'                 => 'Pagine indicizzate',
'noindex-category'               => 'Pagine non indicizzate',
'broken-file-category'           => 'Pagine che includono file inesistenti',

'about'         => 'Informazioni',
'article'       => 'Voce',
'newwindow'     => '(si apre in una nuova finestra)',
'cancel'        => 'Annulla',
'moredotdotdot' => 'Altro...',
'mypage'        => 'Pagina',
'mytalk'        => 'discussioni',
'anontalk'      => 'Discussioni per questo IP',
'navigation'    => 'Navigazione',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Trova',
'qbbrowse'       => 'Sfoglia',
'qbedit'         => 'Modifica',
'qbpageoptions'  => 'Opzioni pagina',
'qbpageinfo'     => 'Informazioni pagina',
'qbmyoptions'    => 'Le mie pagine',
'qbspecialpages' => 'Pagine speciali',
'faq'            => 'Domande frequenti',
'faqpage'        => 'Project:Domande frequenti',

# Vector skin
'vector-action-addsection'       => 'Aggiungi discussione',
'vector-action-delete'           => 'Cancella',
'vector-action-move'             => 'Sposta',
'vector-action-protect'          => 'Proteggi',
'vector-action-undelete'         => 'Recupera',
'vector-action-unprotect'        => 'Cambia la protezione',
'vector-simplesearch-preference' => 'Abilita la barra per la ricerca semplificata (solo per la skin Vector)',
'vector-view-create'             => 'Crea',
'vector-view-edit'               => 'Modifica',
'vector-view-history'            => 'Visualizza cronologia',
'vector-view-view'               => 'Leggi',
'vector-view-viewsource'         => 'Visualizza sorgente',
'actions'                        => 'Azioni',
'namespaces'                     => 'Namespace',
'variants'                       => 'Varianti',

'errorpagetitle'    => 'Errore',
'returnto'          => 'Torna a $1.',
'tagline'           => 'Da {{SITENAME}}.',
'help'              => 'Aiuto',
'search'            => 'Ricerca',
'searchbutton'      => 'Ricerca',
'go'                => 'Vai',
'searcharticle'     => 'Vai',
'history'           => 'Versioni precedenti',
'history_short'     => 'Cronologia',
'updatedmarker'     => 'modificata dalla mia ultima visita',
'printableversion'  => 'Versione stampabile',
'permalink'         => 'Link permanente',
'print'             => 'Stampa',
'view'              => 'Visualizzare',
'edit'              => 'Modifica',
'create'            => 'Crea',
'editthispage'      => 'Modifica questa pagina',
'create-this-page'  => 'Crea questa pagina',
'delete'            => 'Cancella',
'deletethispage'    => 'Cancella questa pagina',
'undelete_short'    => 'Recupera {{PLURAL:$1|una revisione|$1 revisioni}}',
'viewdeleted_short' => 'Vedi {{PLURAL:$1|una modifica cancellata|$1 modifiche cancellate}}',
'protect'           => 'Proteggi',
'protect_change'    => 'cambia',
'protectthispage'   => 'Proteggi questa pagina',
'unprotect'         => 'Cambia la protezione',
'unprotectthispage' => 'Cambia la protezione a questa pagina',
'newpage'           => 'Nuova pagina',
'talkpage'          => 'Pagina di discussione',
'talkpagelinktext'  => 'Discussione',
'specialpage'       => 'Pagina speciale',
'personaltools'     => 'Strumenti personali',
'postcomment'       => 'Nuova sezione',
'articlepage'       => 'Vedi la voce',
'talk'              => 'Discussione',
'views'             => 'Visite',
'toolbox'           => 'Strumenti',
'userpage'          => 'Visualizza la pagina utente',
'projectpage'       => 'Visualizza la pagina di servizio',
'imagepage'         => 'Visualizza la pagina del file',
'mediawikipage'     => 'Visualizza il messaggio',
'templatepage'      => 'Visualizza il template',
'viewhelppage'      => 'Visualizza la pagina di aiuto',
'categorypage'      => 'Visualizza la categoria',
'viewtalkpage'      => 'Visualizza la pagina di discussione',
'otherlanguages'    => 'Altre lingue',
'redirectedfrom'    => '(Reindirizzamento da <b>$1</b>)',
'redirectpagesub'   => 'Pagina di reindirizzamento',
'lastmodifiedat'    => "Questa pagina è stata modificata per l'ultima volta il $1 alle $2.",
'viewcount'         => 'Questa pagina è stata letta {{PLURAL:$1|una volta|$1 volte}}.',
'protectedpage'     => 'Pagina bloccata',
'jumpto'            => 'Vai a:',
'jumptonavigation'  => 'navigazione',
'jumptosearch'      => 'ricerca',
'view-pool-error'   => 'In questo momento i server sono sovraccarichi.
Troppi utenti stanno tentando di visualizzare questa pagina.
Attendere qualche minuto prima di riprovare a caricare la pagina.

$1',
'pool-timeout'      => "Timeout durante l'attesa dello sblocco",
'pool-queuefull'    => 'La coda del pool è piena',
'pool-errorunknown' => 'Errore sconosciuto',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Informazioni su {{SITENAME}}',
'aboutpage'            => 'Project:Informazioni',
'copyright'            => "Contenuti soggetti a licenza d'uso $1.",
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Attualità',
'currentevents-url'    => 'Project:Attualità',
'disclaimers'          => 'Avvertenze',
'disclaimerpage'       => 'Project:Avvertenze generali',
'edithelp'             => 'Guida',
'edithelppage'         => 'Help:Modifica',
'helppage'             => 'Help:Indice',
'mainpage'             => 'Pagina principale',
'mainpage-description' => 'Pagina principale',
'policy-url'           => 'Project:Policy',
'portal'               => 'Portale comunità',
'portal-url'           => 'Project:Portale comunità',
'privacy'              => 'Informazioni sulla privacy',
'privacypage'          => 'Project:Informazioni sulla privacy',

'badaccess'        => 'Permessi non sufficienti',
'badaccess-group0' => "Non si dispone dei permessi necessari per eseguire l'azione richiesta.",
'badaccess-groups' => 'La funzione richiesta è riservata agli utenti che appartengono {{PLURAL:$2|al gruppo|a uno dei seguenti gruppi}}: $1.',

'versionrequired'     => 'Versione $1 di MediaWiki richiesta',
'versionrequiredtext' => "Per usare questa pagina è necessario disporre della versione $1 del software MediaWiki. Vedi [[Special:Version|l'apposita pagina]].",

'ok'                      => 'OK',
'retrievedfrom'           => 'Estratto da "$1"',
'youhavenewmessages'      => 'Hai $1 ($2).',
'newmessageslink'         => 'nuovi messaggi',
'newmessagesdifflink'     => 'ultima modifica',
'youhavenewmessagesmulti' => 'Hai nuovi messaggi su $1',
'editsection'             => 'modifica',
'editold'                 => 'modifica',
'viewsourceold'           => 'visualizza sorgente',
'editlink'                => 'modifica',
'viewsourcelink'          => 'visualizza sorgente',
'editsectionhint'         => 'Modifica la sezione $1',
'toc'                     => 'Indice',
'showtoc'                 => 'mostra',
'hidetoc'                 => 'nascondi',
'collapsible-collapse'    => 'Comprimi',
'collapsible-expand'      => 'Espandi',
'thisisdeleted'           => 'Vedi o ripristina $1?',
'viewdeleted'             => 'Vedi $1?',
'restorelink'             => '{{PLURAL:$1|una modifica cancellata|$1 modifiche cancellate}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Modalità di sottoscrizione del feed non valida.',
'feed-unavailable'        => 'Non sono disponibili feed',
'site-rss-feed'           => 'Feed RSS di $1',
'site-atom-feed'          => 'Feed Atom di $1',
'page-rss-feed'           => 'Feed RSS per "$1"',
'page-atom-feed'          => 'Feed Atom per "$1"',
'red-link-title'          => '$1 (la pagina non esiste)',
'sort-descending'         => 'Ordinamento decrescente',
'sort-ascending'          => 'Ordinamento crescente',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pagina',
'nstab-user'      => 'Utente',
'nstab-media'     => 'File multimediale',
'nstab-special'   => 'Pagina speciale',
'nstab-project'   => 'Pagina di servizio',
'nstab-image'     => 'File',
'nstab-mediawiki' => 'Messaggio',
'nstab-template'  => 'Template',
'nstab-help'      => 'Aiuto',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Operazione non riconosciuta',
'nosuchactiontext'  => "L'azione specificata nella URL non è valida.
È possibile che la URL sia stata digitata in modo errato o che sia stato seguito un collegamento non valido.
Ciò potrebbe anche indicare un bug in {{SITENAME}}.",
'nosuchspecialpage' => 'Pagina speciale non disponibile',
'nospecialpagetext' => "<strong>La pagina speciale richiesta non è stata riconosciuta.</strong>

L'elenco delle pagine speciali valide si trova in [[Special:SpecialPages|Elenco delle pagine speciali]].",

# General errors
'error'                => 'Errore',
'databaseerror'        => 'Errore del database',
'dberrortext'          => 'Errore di sintassi nella richiesta inoltrata al database.
Ciò potrebbe indicare la presenza di un bug nel software.
L\'ultima query inviata al database è stata:
<blockquote><code>$1</code></blockquote>
richiamata dalla funzione "<code>$2</code>".
Il database ha restituito il seguente errore "<samp>$3: $4</samp>".',
'dberrortextcl'        => 'Errore di sintassi nella richiesta inoltrata al database.
L\'ultima query inviata al database è stata:
"$1"
richiamata dalla funzione "$2".
Il database ha restituito il seguente errore "$3: $4".',
'laggedslavemode'      => "'''Attenzione:''' la pagina potrebbe non riportare gli aggiornamenti più recenti.",
'readonly'             => 'Database bloccato',
'enterlockreason'      => 'Indicare il motivo del blocco, specificando il momento in cui è presumibile che venga rimosso',
'readonlytext'         => "In questo momento il database è bloccato e non sono possibili aggiunte o modifiche alle pagine. Il blocco è di solito legato a operazioni di manutenzione ordinaria, al termine delle quali il database è di nuovo accessibile.

L'amministratore di sistema che ha imposto il blocco ha fornito questa spiegazione: $1",
'missing-article'      => 'Il database non ha trovato il testo di una pagina che avrebbe dovuto trovare sotto il nome di "$1" $2.

Di solito ciò si verifica quando viene richiamato, a partire dalla cronologia o dal confronto tra revisioni, un collegamento a una pagina cancellata, a un confronto tra revisioni inesistenti o a un confronto tra revisioni ripulite dalla cronologia.

In caso contrario, si è probabilmente scoperto un errore del software MediaWiki.
Si prega di segnalare l\'accaduto a un [[Special:ListUsers/sysop|amministratore]] specificando la URL in questione.',
'missingarticle-rev'   => '(numero della revisione: $1)',
'missingarticle-diff'  => '(Diff: $1, $2)',
'readonly_lag'         => 'Il database è stato bloccato automaticamente per consentire ai server con i database slave di sincronizzarsi con il master',
'internalerror'        => 'Errore interno',
'internalerror_info'   => 'Errore interno: $1',
'fileappenderrorread'  => 'Non è stato possibile leggere "$1" durante l\'aggiunta.',
'fileappenderror'      => 'Impossibile aggiungere "$1" a "$2".',
'filecopyerror'        => 'Impossibile copiare il file "$1" in "$2".',
'filerenameerror'      => 'Impossibile rinominare il file "$1" in "$2".',
'filedeleteerror'      => 'Impossibile cancellare il file "$1".',
'directorycreateerror' => 'Impossibile creare la directory "$1".',
'filenotfound'         => 'File "$1" non trovato.',
'fileexistserror'      => 'Impossibile scrivere il file "$1": il file esiste già',
'unexpected'           => 'Valore imprevisto: "$1"="$2".',
'formerror'            => 'Errore: impossibile inviare il modulo',
'badarticleerror'      => 'Operazione non consentita per questa pagina.',
'cannotdelete'         => 'Non è stato possibile cancellare il file "$1".
Potrebbe essere stato già cancellato da qualcun altro.',
'cannotdelete-title'   => 'Impossibile eliminare la pagina "$1"',
'badtitle'             => 'Titolo non corretto',
'badtitletext'         => 'Il titolo della pagina richiesta è vuoto, errato o con caratteri non ammessi oppure deriva da un errore nei collegamenti tra siti wiki diversi o versioni in lingue diverse dello stesso sito.',
'perfcached'           => "I dati che seguono sono estratti da una copia ''cache'' del database, e potrebbero non essere aggiornati. Un massimo di {{PLURAL:$1|un risultato è disponibile|$1 risultati sono disponibili}} in cache.",
'perfcachedts'         => "I dati che seguono sono estratti da una copia ''cache'' del database, il cui ultimo aggiornamento risale al $1. Un massimo di {{PLURAL:$4|un risultato è disponibile|$4 risultati sono disponibili}} in cache.",
'querypage-no-updates' => 'Gli aggiornamenti della pagina sono temporaneamente sospesi. I dati in essa contenuti non verranno aggiornati.',
'wrong_wfQuery_params' => 'Errore nei parametri inviati alla funzione wfQuery()<br />
Funzione: $1<br />
Query: $2',
'viewsource'           => 'Visualizza sorgente',
'viewsource-title'     => 'Visualizza sorgente di $1',
'actionthrottled'      => 'Azione ritardata',
'actionthrottledtext'  => "Come misura di sicurezza contro lo spam, l'esecuzione di alcune azioni è limitata a un numero massimo di volte in un determinato periodo di tempo, limite che in questo caso è stato superato. Si prega di riprovare tra qualche minuto.",
'protectedpagetext'    => 'Questa pagina è stata protetta per impedirne la modifica.',
'viewsourcetext'       => 'È possibile visualizzare e copiare il codice sorgente di questa pagina:',
'viewyourtext'         => "È possibile visualizzare e copiare il codice sorgente delle '''tue modifiche''' a questa pagina:",
'protectedinterface'   => "Questa pagina contiene un elemento che fa parte dell'interfaccia utente del software di questo sito ed è protetta per evitare possibili abusi.
Per aggiungere o modificare traduzioni per tutti i wiki usare [//translatewiki.net/ translatewiki.net], il progetto di localizzazione di MediaWiki,",
'editinginterface'     => "'''Attenzione:''' Il testo di questa pagina fa parte dell'interfaccia utente del sito. Tutte le modifiche apportate a questa pagina si riflettono sui messaggi visualizzati per tutti gli utenti su questo wiki.
Per aggiungere o modificare le traduzioni valide su tutti i wiki, considera la possibilità di usare [//translatewiki.net/wiki/Main_Page?setlang=it translatewiki.net], il progetto MediaWiki per la localizzazione.",
'sqlhidden'            => '(la query SQL è stata nascosta)',
'cascadeprotected'     => 'Su questa pagina non è possibile effettuare modifiche perché è stata inclusa {{PLURAL:$1|nella pagina indicata di seguito, che è stata protetta|nelle pagine indicate di seguito, che sono state protette}} selezionando la protezione "ricorsiva":
$2',
'namespaceprotected'   => "Non si dispone dei permessi necessari per modificare le pagine del namespace '''$1'''.",
'customcssprotected'   => 'Non si dispone dei permessi necessari alla modifica di questa pagina CSS, in quanto contiene le impostazioni personali di un altro utente.',
'customjsprotected'    => 'Non si dispone dei permessi necessari alla modifica di questa pagina JavaScript, in quanto contiene le impostazioni personali di un altro utente.',
'ns-specialprotected'  => 'Non è possibile modificare le pagine speciali.',
'titleprotected'       => "La creazione di una pagina con questo titolo è stata bloccata da [[User:$1|$1]].
La motivazione è la seguente: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Errore di configurazione: antivirus sconosciuto: ''$1''",
'virus-scanfailed'     => 'scansione fallita (codice $1)',
'virus-unknownscanner' => 'antivirus sconosciuto:',

# Login and logout pages
'logouttext'                 => "'''Logout effettuato.'''

Si può continuare ad usare {{SITENAME}} come utente anonimo oppure [[Special:UserLogin|eseguire un nuovo accesso]], con lo stesso nome utente o un nome diverso.
Nota che alcune pagine potrebbero continuare ad apparire come se il logout non fosse avvenuto finché non viene pulita la cache del proprio browser.",
'welcomecreation'            => "== Benvenuto, $1! ==

L'account è stato creato correttamente. Non dimenticare di personalizzare le [[Special:Preferences|preferenze di {{SITENAME}}]].",
'yourname'                   => 'Nome utente:',
'yourpassword'               => 'Password:',
'yourpasswordagain'          => 'Ripeti la password:',
'remembermypassword'         => 'Ricorda la password su questo browser (per un massimo di $1 {{PLURAL:$1|giorno|giorni}})',
'securelogin-stick-https'    => 'Resta connesso attraverso HTTPS dopo il login',
'yourdomainname'             => 'Specificare il dominio',
'externaldberror'            => 'Si è verificato un errore con il server di autenticazione esterno, oppure non si dispone delle autorizzazioni necessarie per aggiornare il proprio accesso esterno.',
'login'                      => 'Entra',
'nav-login-createaccount'    => 'Entra / registrati',
'loginprompt'                => 'Per accedere a {{SITENAME}} è necessario abilitare i cookie.',
'userlogin'                  => 'Entra / registrati',
'userloginnocreate'          => 'Entra',
'logout'                     => 'Esci',
'userlogout'                 => 'esci',
'notloggedin'                => 'Accesso non effettuato',
'nologin'                    => "Non hai ancora un accesso? '''$1'''.",
'nologinlink'                => 'Registrati',
'createaccount'              => 'Crea un accesso',
'gotaccount'                 => "Hai già un accesso? '''$1'''.",
'gotaccountlink'             => 'Entra',
'userlogin-resetlink'        => 'Hai dimenticato i tuoi dati di accesso?',
'createaccountmail'          => 'Tramite email',
'createaccountreason'        => 'Motivo:',
'badretype'                  => 'Le password inserite non coincidono tra loro.',
'userexists'                 => 'Il nome utente inserito è già utilizzato.
Scegliere un nome utente diverso.',
'loginerror'                 => "Errore durante l'accesso",
'createaccounterror'         => "Impossibile creare l'account: $1",
'nocookiesnew'               => "La registrazione è stata completata, ma non è stato possibile accedere a {{SITENAME}} perché i cookie sono disattivati. Riprovare l'accesso con il nome utente e la password appena creati dopo aver attivato i cookie nel proprio browser.",
'nocookieslogin'             => "L'accesso a {{SITENAME}} richiede l'uso dei cookie, che risultano disattivati. Riprovare l'accesso dopo aver attivato i cookie nel proprio browser.",
'nocookiesfornew'            => "L'account utente non è stato creato, poiché non abbiamo potuto confermare la sua fonte.
Assicurati di avere attivato i cookie, ricarica questa pagina e riprova.",
'noname'                     => 'Il nome utente indicato non è valido.',
'loginsuccesstitle'          => 'Accesso effettuato',
'loginsuccess'               => "'''Sei stato connesso al server di {{SITENAME}} con il nome utente di \"\$1\".'''",
'nosuchuser'                 => 'Non è registrato alcun utente di nome "$1". I nomi utente sono sensibili alle maiuscole. Verificare il nome inserito o [[Special:UserLogin/signup|creare un nuovo accesso]].',
'nosuchusershort'            => 'Non è registrato alcun utente di nome "$1". Verificare il nome inserito.',
'nouserspecified'            => 'È necessario specificare un nome utente.',
'login-userblocked'          => 'Questa utenza è bloccata. Non è possibile effettuare il login.',
'wrongpassword'              => 'La password inserita non è corretta. Riprovare.',
'wrongpasswordempty'         => 'Non è stata inserita alcuna password. Riprovare.',
'passwordtooshort'           => 'Le password devono contenere almeno {{PLURAL:$1|1 carattere|$1 caratteri}}.',
'password-name-match'        => 'La password deve essere diversa dal nome utente.',
'password-login-forbidden'   => "L'uso di questo nome utente e password è stato proibito.",
'mailmypassword'             => 'Invia una nuova password al mio indirizzo e-mail',
'passwordremindertitle'      => 'Servizio Password Reminder di {{SITENAME}}',
'passwordremindertext'       => 'Qualcuno (probabilmente tu, con indirizzo IP $1) ha richiesto l\'invio di una nuova password di accesso a {{SITENAME}} ($4).
Una password temporanea per l\'utente "$2" è stata impostata a "$3".
È opportuno eseguire un accesso quanto prima e cambiare la password immediatamente. La password temporanea scadrà dopo {{PLURAL:$5|un giorno|$5 giorni}}.

Se non sei stato tu a fare la richiesta, oppure hai ritrovato la password e non desideri più cambiarla, puoi ignorare questo messaggio e continuare a usare la vecchia password.',
'noemail'                    => 'Nessun indirizzo e-mail registrato per l\'utente "$1".',
'noemailcreate'              => 'È necessario fornire un indirizzo e-mail valido',
'passwordsent'               => 'Una nuova password è stata inviata all\'indirizzo e-mail registrato per l\'utente "$1".
Per favore, effettua un accesso non appena la ricevi.',
'blocked-mailpassword'       => 'Per prevenire abusi, non è consentito usare la funzione "Invia nuova password" da un indirizzo IP bloccato.',
'eauthentsent'               => "Un messaggio e-mail di conferma è stato spedito all'indirizzo indicato.
Per abilitare l'invio di messaggi e-mail per questo accesso è necessario seguire le istruzioni che vi sono indicate, in modo da confermare che si è i legittimi proprietari dell'indirizzo",
'throttled-mailpassword'     => 'Una nuova password è già stata inviata da meno di {{PLURAL:$1|1 ora|$1 ore}}.
Per prevenire abusi, la funzione "Invia nuova password" può essere usata solo una volta ogni {{PLURAL:$1|ora|$1 ore}}.',
'mailerror'                  => "Errore nell'invio del messaggio: $1",
'acct_creation_throttle_hit' => "I visitatori del sito che usano il tuo indirizzo IP hanno creato {{PLURAL:$1|1 account|$1 account}} nell'ultimo giorno, che è il massimo consentito in questo periodo di tempo.
Perciò, gli utenti che usano questo indirizzo IP non possono creare altri account per il momento.",
'emailauthenticated'         => "L'indirizzo e-mail è stato confermato il $2 alle $3.",
'emailnotauthenticated'      => "L'indirizzo e-mail non è stato ancora confermato. Non verranno inviati messaggi e-mail attraverso le funzioni elencate di seguito.",
'noemailprefs'               => 'Indicare un indirizzo e-mail per attivare queste funzioni.',
'emailconfirmlink'           => 'Confermare il proprio indirizzo e-mail',
'invalidemailaddress'        => "L'indirizzo e-mail indicato ha un formato non valido. Inserire un indirizzo valido o svuotare la casella.",
'cannotchangeemail'          => 'Gli indirizzi e-mail non possono essere modificati in questo wiki.',
'accountcreated'             => 'Accesso creato',
'accountcreatedtext'         => "È stato creato un accesso per l'utente $1.",
'createaccount-title'        => 'Creazione di un accesso a {{SITENAME}}',
'createaccount-text'         => 'Qualcuno ha creato un accesso a {{SITENAME}} ($4) a nome di $2, associato a questo indirizzo di posta elettronica. La password per l\'utente "$2" è impostata a "$3".
È opportuno eseguire un accesso quanto prima e cambiare la password immediatamente.

Se l\'accesso è stato creato per errore, si può ignorare questo messaggio.',
'usernamehasherror'          => 'Il nome utente non può contenere caratteri hash',
'login-throttled'            => 'Sono stati effettuati troppi tentativi di accesso in breve tempo.
Riprovare più tardi.',
'login-abort-generic'        => 'Il tuo login non ha avuto successo - Annullato',
'loginlanguagelabel'         => 'Lingua: $1',
'suspicious-userlogout'      => 'La tua richiesta di disconnessione è stata negata perché sembra inviata da un browser non funzionante o un proxy di caching.',

# E-mail sending
'php-mail-error-unknown' => 'Errore sconosciuto nella funzione PHP mail()',
'user-mail-no-addy'      => 'Hai cercato di inviare una e-mail senza un indirizzo.',

# Change password dialog
'resetpass'                 => 'Cambia la password',
'resetpass_announce'        => "L'accesso è stato effettuato con un codice temporaneo, inviato via e-mail. Per completare l'accesso è necessario impostare una nuova password:",
'resetpass_text'            => '<!-- Aggiungere il testo qui -->',
'resetpass_header'          => "Cambia la password dell'account",
'oldpassword'               => 'Vecchia password:',
'newpassword'               => 'Nuova password:',
'retypenew'                 => 'Riscrivi la nuova password:',
'resetpass_submit'          => 'Imposta la password e accedi al sito',
'resetpass_success'         => 'La password è stata modificata. Accesso in corso...',
'resetpass_forbidden'       => 'Non è possibile modificare le password',
'resetpass-no-info'         => "Devi aver effettuato l'accesso per accedere a questa pagina direttamente.",
'resetpass-submit-loggedin' => 'Cambia password',
'resetpass-submit-cancel'   => 'Annulla',
'resetpass-wrong-oldpass'   => 'Password temporanea o attuale non valida.
La password potrebbe essere stata già cambiata, oppure potrebbe essere stata richiesta una nuova password temporanea.',
'resetpass-temp-password'   => 'Password temporanea:',

# Special:PasswordReset
'passwordreset'                    => 'Reimposta password',
'passwordreset-text'               => 'Completa questo modulo per ricevere i dettagli del tuo account via e-mail.',
'passwordreset-legend'             => 'Reimposta password',
'passwordreset-disabled'           => 'La reimpostazione delle password è stata disabilitata su questa wiki',
'passwordreset-pretext'            => '{{PLURAL:$1||Immetti una delle porzioni di dati qui sotto}}',
'passwordreset-username'           => 'Nome utente:',
'passwordreset-domain'             => 'Dominio:',
'passwordreset-capture'            => 'Visualizzare il contenuto del messaggio e-mail?',
'passwordreset-capture-help'       => "Se si seleziona questa casella, l'indirizzo e-mail (con la password temporanea), verrà mostrato a voi, oltre ad essere inviato all'utente.",
'passwordreset-email'              => 'Indirizzo e-mail:',
'passwordreset-emailtitle'         => 'Dettagli account su {{SITENAME}}',
'passwordreset-emailtext-ip'       => "Qualcuno (probabilmente tu, con indirizzo IP $1) ha richiesto l'invio di una nuova password per l'accesso a {{SITENAME}} ($4). {{PLURAL:$3|L'utente associato|Gli utenti associati}} a questo indirizzo e-mail sono:

$2

{{PLURAL:$3|Questa password temporanea scadrà|Queste password temporanee scadranno}} dopo {{PLURAL:$5|un giorno|$5 giorni}}.
Dovresti accedere e scegliere una nuova password ora. 

Se non sei stato tu a fare la richiesta, o se ti sei ricordato la password originale e non vuoi più cambiarla, puoi ignorare questo messaggio e continuare al utilizzare la tua vecchia password.",
'passwordreset-emailtext-user'     => "L'utente $1 di {{SITENAME}} ha richiesto l'invio di una nuova password per l'accesso a {{SITENAME}} ($4). {{PLURAL:$3|L'utente associato|Gli utenti associati}} a questo indirizzo e-mail sono:

$2

{{PLURAL:$3|Questa password temporanea scadrà|Queste password temporanee scadranno}} dopo {{PLURAL:$5|un giorno|$5 giorni}}.
Dovresti accedere e scegliere una nuova password ora. 

Se non sei stato tu a fare la richiesta, o se ti sei ricordato la password originale e non vuoi più cambiarla, puoi ignorare questo messaggio e continuare al utilizzare la tua vecchia password.",
'passwordreset-emailelement'       => 'Nome utente: $1
Password temporanea: $2',
'passwordreset-emailsent'          => 'È stata inviata una e-mail di promemoria.',
'passwordreset-emailsent-capture'  => 'È stata inviata una e-mail di promemoria, il contenuto è riportato di seguito.',
'passwordreset-emailerror-capture' => "È stata generata l'e-mail di promemoria riportata di seguito. L'invio all'utente non è riuscito: $1",

# Special:ChangeEmail
'changeemail'          => "Modifica l'indirizzo e-mail",
'changeemail-header'   => "Modifica l'indirizzo e-mail dell'account",
'changeemail-text'     => 'Completa questo modulo per cambiare il tuo indirizzo e-mail. Sarà necessario inserire la password per confermare la modifica.',
'changeemail-no-info'  => "Devi aver effettuato l'accesso per accedere a questa pagina direttamente.",
'changeemail-oldemail' => 'Indirizzo e-mail attuale:',
'changeemail-newemail' => 'Nuovo indirizzo e-mail:',
'changeemail-none'     => '(nessuno)',
'changeemail-submit'   => 'Modifica e-mail',
'changeemail-cancel'   => 'Annulla',

# Edit page toolbar
'bold_sample'     => 'Grassetto',
'bold_tip'        => 'Grassetto',
'italic_sample'   => 'Corsivo',
'italic_tip'      => 'Corsivo',
'link_sample'     => 'Titolo del collegamento',
'link_tip'        => 'Collegamento interno',
'extlink_sample'  => 'http://www.example.com titolo del collegamento',
'extlink_tip'     => 'Collegamento esterno (ricorda il prefisso http:// )',
'headline_sample' => 'Intestazione',
'headline_tip'    => 'Intestazione di 2° livello',
'nowiki_sample'   => 'Inserire qui il testo non formattato',
'nowiki_tip'      => 'Ignora la formattazione wiki',
'image_sample'    => 'Esempio.jpg',
'image_tip'       => 'Incorpora file',
'media_sample'    => 'Esempio.ogg',
'media_tip'       => 'Collegamento a file multimediale',
'sig_tip'         => 'Firma con data e ora',
'hr_tip'          => 'Linea orizzontale (usare con giudizio)',

# Edit pages
'summary'                          => 'Oggetto:',
'subject'                          => 'Argomento (intestazione):',
'minoredit'                        => 'Questa è una modifica minore',
'watchthis'                        => 'Aggiungi agli osservati speciali',
'savearticle'                      => 'Salva la pagina',
'preview'                          => 'Anteprima',
'showpreview'                      => 'Visualizza anteprima',
'showlivepreview'                  => "Funzione ''Live preview''",
'showdiff'                         => 'Mostra cambiamenti',
'anoneditwarning'                  => "'''Attenzione:''' Accesso non effettuato. Nella cronologia della pagina verrà registrato l'indirizzo IP.",
'anonpreviewwarning'               => "''Non è stato eseguito il login. Salvando la pagina, il proprio indirizzo IP sarà registrato nella cronologia.''",
'missingsummary'                   => "'''Attenzione:''' non è stato specificato l'oggetto di questa modifica. Premendo di nuovo \"{{int:savearticle}}\" la modifica verrà salvata con l'oggetto vuoto.",
'missingcommenttext'               => 'Inserire un commento qui sotto.',
'missingcommentheader'             => "'''Attenzione:''' non è stata specificato l'oggetto/l'intestazione di questo commento. Premendo di nuovo \"{{int:savearticle}}\" la modifica verrà salvata senza intestazione.",
'summary-preview'                  => "Anteprima dell'oggetto:",
'subject-preview'                  => 'Anteprima oggetto/intestazione:',
'blockedtitle'                     => 'Utente bloccato.',
'blockedtext'                      => "'''Il tuo nome utente o indirizzo IP è stato bloccato.'''

Il blocco è stato imposto da $1. La motivazione del blocco è la seguente: ''$2''

* Inizio del blocco: $8
* Scadenza del blocco: $6
* Intervallo di blocco: $7

Se lo si desidera, è possibile contattare $1 o un altro [[{{MediaWiki:Grouppage-sysop}}|amministratore]] per discutere del blocco.

Si noti che la funzione 'Scrivi all'utente' non è attiva se non è stato registrato un indirizzo e-mail valido nelle proprie [[Special:Preferences|preferenze]] o se l'utilizzo di tale funzione è stato bloccato.

L'indirizzo IP attuale è $3, il numero ID del blocco è #$5.
Si prega di specificare tutti i dettagli precedenti in qualsiasi richiesta di chiarimenti.",
'autoblockedtext'                  => "Questo indirizzo IP è stato bloccato automaticamente perché condiviso con un altro utente, a sua volta bloccato da $1.
La motivazione del blocco è la seguente:

:''$2''

* Inizio del blocco: $8
* Scadenza del blocco: $6
* Intervallo di blocco: $7

È possibile contattare $1 o un altro [[{{MediaWiki:Grouppage-sysop}}|amministratore]] per richiedere eventuali chiarimenti circa il blocco.

Si noti che la funzione 'Scrivi all'utente' non è attiva se non è stato registrato un indirizzo e-mail valido nelle proprie [[Special:Preferences|preferenze]] e, comunque, se nell'applicare il blocco, tale funzione è stata disabilitata (per la durata del blocco).

L'indirizzo IP attuale è $3, il numero ID del blocco è #$5
Si prega di specificare tutti i dettagli qui inclusi nel compilare qualsiasi richiesta di chiarimenti.",
'blockednoreason'                  => 'nessuna motivazione indicata',
'whitelistedittext'                => 'Per modificare le pagine è necessario $1.',
'confirmedittext'                  => "Per essere abilitati alla modifica delle pagine è necessario confermare il proprio indirizzo e-mail. Per impostare e confermare l'indirizzo servirsi delle [[Special:Preferences|preferenze]].",
'nosuchsectiontitle'               => 'Impossibile trovare la sezione',
'nosuchsectiontext'                => 'Si è tentato di modificare una sezione inesistente.
Potrebbe essere stata spostata o eliminata mentre si stava visionando la pagina.',
'loginreqtitle'                    => 'Accesso richiesto',
'loginreqlink'                     => "eseguire l'accesso",
'loginreqpagetext'                 => 'Per vedere altre pagine è necessario $1.',
'accmailtitle'                     => 'Password inviata.',
'accmailtext'                      => "Una password generata casualmente per [[User talk:$1|$1]] è stata inviata a $2.

La password per questo nuovo account può essere modificata all'accesso nella pagina per ''[[Special:ChangePassword|cambiare la password]]''.",
'newarticle'                       => '(Nuovo)',
'newarticletext'                   => "Il collegamento appena seguito corrisponde ad una pagina non ancora esistente.
Se vuoi creare la pagina ora, basta cominciare a scrivere il testo nella casella qui sotto (vedi la [[{{MediaWiki:Helppage}}|pagina di aiuto]] per maggiori informazioni).
Se il collegamento è stato aperto per errore, è sufficiente fare clic sul pulsante '''Indietro''' del proprio browser.",
'anontalkpagetext'                 => "----''Questa è la pagina di discussione di un utente anonimo, che non ha ancora creato un accesso o comunque non lo usa. Per identificarlo è quindi necessario usare il numero del suo indirizzo IP. Gli indirizzi IP possono però essere condivisi da più utenti. Se sei un utente anonimo e ritieni che i commenti presenti in questa pagina non si riferiscano a te, [[Special:UserLogin/signup|crea un nuovo accesso]] o [[Special:UserLogin|entra con quello che già hai]] per evitare di essere confuso con altri utenti anonimi in futuro.''",
'noarticletext'                    => 'In questo momento la pagina richiesta è vuota. È possibile [[Special:Search/{{PAGENAME}}|cercare questo titolo]] nelle altre pagine del sito, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} cercare nei registri correlati] oppure [{{fullurl:{{FULLPAGENAME}}|action=edit}} modificare la pagina ora]</span>.',
'noarticletext-nopermission'       => 'In questo momento la pagina richiesta è vuota. È possibile [[Special:Search/{{PAGENAME}}|cercare questo titolo]] nelle altre pagine del sito o <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} cercare nei registri correlati]</span>.',
'userpage-userdoesnotexist'        => 'L\'account "<nowiki>$1</nowiki>" non corrisponde a un utente registrato. Verificare che si intenda davvero creare o modificare questa pagina.',
'userpage-userdoesnotexist-view'   => 'L\'account utente "$1" non è registrato.',
'blocked-notice-logextract'        => "Questo utente è attualmente bloccato.
L'ultimo elemento del registro dei blocchi è riportato di seguito per informazione:",
'clearyourcache'                   => "'''Nota:''' dopo aver salvato, potrebbe essere necessario pulire la cache del proprio browser per vedere i cambiamenti. 
*'''Firefox / Safari''': tenere premuto il tasto delle maiuscole e fare clic su ''Ricarica'', oppure premere ''Ctrl-F5'' o ''Ctrl-R'' (''⌘-R'' su Mac)
*'''Google Chrome''': premere ''Ctrl-Shift-R'' (''⌘-Shift-R'' su un Mac)
*'''Internet Explorer''': tenere premuto il tasto ''Ctrl'' mentre si fa clic su ''Refresh'', oppure premere ''Ctrl-F5''
*'''Opera''': svuotare completamente la cache dal menu ''Strumenti → Preferenze''",
'usercssyoucanpreview'             => "'''Suggerimento:''' usa il pulsante 'Visualizza anteprima' per provare il tuo nuovo CSS prima di salvarlo.",
'userjsyoucanpreview'              => "'''Suggerimento:''' usa il pulsante 'Visualizza anteprima' per provare il tuo nuovo JavaScript prima di salvarlo.",
'usercsspreview'                   => "'''Questa è solo un'anteprima del proprio CSS personale. Le modifiche non sono ancora state salvate!'''",
'userjspreview'                    => "'''Questa è solo un'anteprima per provare il proprio JavaScript personale; le modifiche non sono ancora state salvate!'''",
'sitecsspreview'                   => "Questa è solo un'anteprima del CSS. Le modifiche non sono ancora state salvate!'''",
'sitejspreview'                    => "Questa è solo un'anteprima per provare il JavaScript; le modifiche non sono ancora state salvate!'''",
'userinvalidcssjstitle'            => "'''Attenzione:'''  Non esiste alcuna skin con nome \"\$1\". Si noti che le pagine per i .css e .js personalizzati hanno l'iniziale del titolo minuscola, ad esempio {{ns:user}}:Esempio/vector.css e non {{ns:user}}:Esempio/Vector.css.",
'updated'                          => '(Aggiornato)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Ricorda che questa è solo un'anteprima.'''
Le tue modifiche NON sono ancora state salvate!",
'previewconflict'                  => 'L\'anteprima corrisponde al testo presente nella casella di modifica superiore e rappresenta la pagina come apparirà se si sceglie di premere "Salva la pagina" in questo momento.',
'session_fail_preview'             => "'''Non è stato possibile elaborare la modifica perché sono andati persi i dati relativi alla sessione.
Riprovare.
Se il problema persiste, si può tentare di [[Special:UserLogout|scollegarsi]] ed effettuare un nuovo accesso.'''",
'session_fail_preview_html'        => "'''Non è stato possibile elaborare la modifica perché sono andati persi i dati relativi alla sessione.'''

''Poiché in {{SITENAME}} è abilitato l'uso di HTML senza limitazioni, l'anteprima non viene visualizzata; si tratta di una misura di sicurezza contro gli attacchi JavaScript.''

'''Se questo è un legittimo tentativo di modifica, riprovare. Se il problema persiste, si può provare a [[Special:UserLogout|scollegarsi]] ed effettuare un nuovo accesso.'''",
'token_suffix_mismatch'            => "'''La modifica non è stata salvata perché il client ha mostrato di gestire in modo errato i caratteri di punteggiatura nel token associato alla stessa. Per evitare una possibile corruzione del testo della pagina, è stata rifiutata l'intera modifica. Questa situazione può verificarsi, talvolta, quando vengono usati alcuni servizi di proxy anonimi via web che presentano dei bug.'''",
'edit_form_incomplete'             => "'''Alcune parti del modulo di modifica non hanno raggiunto il server; controllare che le modifiche siano intatte e riprovare.'''",
'editing'                          => 'Modifica di $1',
'editingsection'                   => 'Modifica di $1 (sezione)',
'editingcomment'                   => 'Modifica di $1 (nuova sezione)',
'editconflict'                     => 'Conflitto di edizione su $1',
'explainconflict'                  => "Un altro utente ha salvato una nuova versione della pagina mentre stavi effettuando le modifiche.
La casella di modifica superiore contiene il testo della pagina attualmente online, così come è stato aggiornato dall'altro utente.
La versione con le tue modifiche è invece riportata nella casella di modifica inferiore.
Se desideri confermarle, devi riportare le tue modifiche nel testo esistente (casella superiore).
Premendo il pulsante '{{int:savearticle}}', verrà salvato '''solo''' il testo contenuto nella casella di modifica superiore.",
'yourtext'                         => 'Il tuo testo',
'storedversion'                    => 'La versione memorizzata',
'nonunicodebrowser'                => "'''Attenzione: si sta utilizzando un browser non compatibile con i caratteri Unicode. Per consentire la modifica delle pagine senza creare inconvenienti, i caratteri non ASCII vengono visualizzati nella casella di modifica sotto forma di codici esadecimali.'''",
'editingold'                       => "'''Attenzione: si sta modificando una versione non aggiornata della pagina.<br />
Se si sceglie di salvarla, tutti i cambiamenti apportati dopo questa revisione andranno perduti.'''",
'yourdiff'                         => 'Differenze',
'copyrightwarning'                 => "Per favore tieni presente che tutti i contributi a {{SITENAME}} si considerano pubblicati nei termini d'uso della licenza $2 (vedi $1 per maggiori dettagli).
Se non desideri che i tuoi testi possano essere modificati e ridistribuiti da chiunque senza alcuna limitazione, non inviarli qui.<br />
Inviando il testo dichiari inoltre, sotto tua responsabilità, che è stato scritto da te personalmente oppure è stato copiato da una fonte di pubblico dominio o similarmente libera.
'''Non inviare materiale protetto da copyright senza autorizzazione!'''",
'copyrightwarning2'                => "Per favore tieni presente che tutti i contributi a {{SITENAME}} possono essere modificati, stravolti o cancellati da altri contributori.
Se non desideri che i tuoi testi possano essere alterati, non inviarli qui.<br />
Inviando il testo dichiari inoltre, sotto tua responsabilità, che è stato scritto da te personalmente oppure è stato copiato da una fonte di pubblico dominio o similarmente libera (vedi $1 per maggiori dettagli).
'''Non inviare materiale protetto da copyright senza autorizzazione!'''",
'longpageerror'                    => "'''Errore: il testo inviato è lungo {{PLURAL:$1|1|$1}} kilobyte, che è maggiore della dimensione massima consentita ({{PLURAL:$2|1|$2}} kilobyte).'''
Il testo non può essere salvato.",
'readonlywarning'                  => "'''Attenzione: il database è stato bloccato per manutenzione, è quindi impossibile salvare le modifiche in questo momento.'''
Per non perderle, è possibile copiare quanto inserito finora nella casella di modifica, incollarlo in un programma di elaborazione testi e salvarlo in attesa dello sblocco del database.

L'amministratore che ha bloccato il database ha fornito questa spiegazione: $1",
'protectedpagewarning'             => "'''Attenzione: questa pagina è stata bloccata in modo che solo gli utenti con privilegi di amministratore possano modificarla.'''
L'ultimo elemento del registro è riportato di seguito per informazione:",
'semiprotectedpagewarning'         => "'''Nota:''' Questa pagina è stata bloccata in modo che solo gli utenti registrati possano modificarla.
L'ultimo elemento del registro è riportato di seguito per informazione:",
'cascadeprotectedwarning'          => "'''Attenzione:''' Questa pagina è stata bloccata in modo che solo gli utenti con privilegi di amministratore possano modificarla. Ciò avviene perché la pagina è inclusa {{PLURAL:\$1|nella pagina indicata di seguito, che è stata protetta|nelle pagine indicate di seguito, che sono state protette}} selezionando la protezione \"ricorsiva\":",
'titleprotectedwarning'            => "'''Attenzione: questa pagina è stata bloccata in modo che siano necessari [[Special:ListGroupRights|diritti specifici]] per crearla.'''
L'ultimo elemento del registro è riportato di seguito per informazione:",
'templatesused'                    => '{{PLURAL:$1|Template utilizzato|Template utilizzati}} in questa pagina:',
'templatesusedpreview'             => '{{PLURAL:$1|Template utilizzato|Template utilizzati}} in questa anteprima:',
'templatesusedsection'             => '{{PLURAL:$1|Template utilizzato|Template utilizzati}} in questa sezione:',
'template-protected'               => '(protetto)',
'template-semiprotected'           => '(semiprotetto)',
'hiddencategories'                 => 'Questa pagina appartiene a {{PLURAL:$1|una categoria nascosta|$1 categorie nascoste}}:',
'edittools'                        => '<!-- Testo che appare al di sotto del modulo di modifica e di upload. -->',
'nocreatetitle'                    => 'Creazione delle pagine limitata',
'nocreatetext'                     => 'La possibilità di creare nuove pagine su {{SITENAME}} è stata limitata ai soli utenti registrati. È possibile tornare indietro e modificare una pagina esistente, oppure [[Special:UserLogin|entrare o registrarsi]].',
'nocreate-loggedin'                => 'Non si dispone dei permessi necessari a creare nuove pagine.',
'sectioneditnotsupported-title'    => 'Modifica delle sezioni non supportata',
'sectioneditnotsupported-text'     => 'La modifica delle sezioni non è supportata in questa pagina.',
'permissionserrors'                => 'Errore nei permessi',
'permissionserrorstext'            => "Non si dispone dei permessi necessari ad eseguire l'azione richiesta, per {{PLURAL:$1|il seguente motivo|i seguenti motivi}}:",
'permissionserrorstext-withaction' => 'Non si dispone dei permessi necessari per $2, per {{PLURAL:$1|il seguente motivo|i seguenti motivi}}:',
'recreate-moveddeleted-warn'       => "'''Attenzione: si sta per ricreare una pagina già cancellata in passato.'''

Accertarsi che sia davvero opportuno continuare a modificare questa pagina.
L'elenco delle relative cancellazioni e degli spostamenti viene riportato di seguito per comodità:",
'moveddeleted-notice'              => "Questa pagina è stata cancellata. L'elenco delle relative cancellazioni e degli spostamenti viene riportato di seguito per informazione.",
'log-fulllog'                      => 'Visualizza log completo',
'edit-hook-aborted'                => "La modifica è stata annullata dall'hook.
Non è stata restituita alcuna spiegazione.",
'edit-gone-missing'                => 'Impossibile aggiornare la pagina.
Sembra che sia stata cancellata.',
'edit-conflict'                    => 'Conflitto di edizione.',
'edit-no-change'                   => 'La modifica è stata ignorata poiché non sono stati apportati cambiamenti al testo.',
'edit-already-exists'              => 'Impossibile creare una nuova pagina.
Esiste già.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Attenzione:''' Questa pagina contiene troppe chiamate alle parser functions.

Dovrebbe averne meno di $2, al momento ce {{PLURAL:$1|n'è $1|ne sono $1}}.",
'expensive-parserfunction-category'       => 'Pagine con troppe chiamate alle parser functions',
'post-expand-template-inclusion-warning'  => "'''Attenzione:''' la dimensione dei template inclusi è troppo grande.
Alcuni template non verranno inclusi.",
'post-expand-template-inclusion-category' => 'Pagine per le quali la dimensione dei template inclusi supera il limite consentito',
'post-expand-template-argument-warning'   => "'''Attenzione:''' questa pagina contiene uno o più argomenti di template troppo grandi per essere espansi. Tali argomenti verranno omessi.",
'post-expand-template-argument-category'  => 'Pagine contenenti template con argomenti mancanti',
'parser-template-loop-warning'            => 'Rilevato loop del template: [[$1]]',
'parser-template-recursion-depth-warning' => 'È stato raggiunto il limite di ricorsione nel template ($1)',
'language-converter-depth-warning'        => 'Limite di profondità del convertitore di lingua superato ($1)',

# "Undo" feature
'undo-success' => 'Questa modifica può essere annullata. Verificare il confronto presentato di seguito per accertarsi che il contenuto corrisponda a quanto desiderato e quindi salvare le modifiche per completare la procedura di annullamento.',
'undo-failure' => 'Impossibile annullare la modifica a causa di un conflitto con modifiche intermedie.',
'undo-norev'   => 'La modifica non può essere annullata perché non esiste o è stata cancellata.',
'undo-summary' => 'Annullata la modifica $1 di [[Special:Contributions/$2|$2]] ([[User talk:$2|discussione]])',

# Account creation failure
'cantcreateaccounttitle' => 'Impossibile registrare un utente',
'cantcreateaccount-text' => "La creazione di nuovi account a partire da questo indirizzo IP ('''$1''') è stata bloccata da [[User:$3|$3]].

La motivazione del blocco fornita da $3 è la seguente: ''$2''",

# History pages
'viewpagelogs'           => 'Visualizza i log relativi a questa pagina.',
'nohistory'              => 'Cronologia delle versioni di questa pagina non reperibile.',
'currentrev'             => 'Versione attuale',
'currentrev-asof'        => 'Versione attuale delle $1',
'revisionasof'           => 'Versione delle $1',
'revision-info'          => 'Versione delle $1, autore: $2',
'previousrevision'       => '← Versione meno recente',
'nextrevision'           => 'Versione più recente →',
'currentrevisionlink'    => 'Versione attuale',
'cur'                    => 'corr',
'next'                   => 'succ',
'last'                   => 'prec',
'page_first'             => 'prima',
'page_last'              => 'ultima',
'histlegend'             => "Confronto tra versioni: selezionare le caselle corrispondenti alle versioni desiderate e premere Invio o il pulsante in basso.

Legenda: '''({{int:cur}})''' = differenze con la versione attuale, '''({{int:last}})''' = differenze con la versione precedente, '''{{int:minoreditletter}}''' = modifica minore",
'history-fieldset-title' => 'Scorri nella cronologia',
'history-show-deleted'   => 'Solo quelli cancellati',
'histfirst'              => 'Prima',
'histlast'               => 'Ultima',
'historysize'            => '({{PLURAL:$1|1 byte|$1 byte}})',
'historyempty'           => '(vuota)',

# Revision feed
'history-feed-title'          => 'Cronologia',
'history-feed-description'    => 'Cronologia della pagina su questo sito',
'history-feed-item-nocomment' => '$1 il $2',
'history-feed-empty'          => 'La pagina richiesta non esiste; potrebbe essere stata cancellata dal sito o rinominata. Verificare con la [[Special:Search|pagina di ricerca]] se vi sono nuove pagine.',

# Revision deletion
'rev-deleted-comment'         => '(Oggetto della modifica rimosso)',
'rev-deleted-user'            => '(nome utente rimosso)',
'rev-deleted-event'           => '(azione del log rimossa)',
'rev-deleted-user-contribs'   => '[nome utente o indirizzo IP rimosso - edit nascosto dalla cronologia]',
'rev-deleted-text-permission' => "Questa versione della pagina è stata '''cancellata'''.
Consultare il [{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} log delle cancellazioni] per ulteriori dettagli.",
'rev-deleted-text-unhide'     => "Questa versione della pagina è stata '''cancellata'''.
Consultare il [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log delle cancellazioni] per ulteriori dettagli.
Agli amministratori è ancora consentito [$1 visualizzare questa versione] se necessario.",
'rev-suppressed-text-unhide'  => "Questa versione della pagina è stata '''rimossa'''.
Consultare il [{{fullurl:{{#Special:Log}}/suppress|page={{PAGENAMEE}}}} log di rimozione] per ulteriori dettagli.
Agli amministratori è ancora consentito [$1 visualizzare questa versione] se necessario.",
'rev-deleted-text-view'       => "Questa versione della pagina è stata '''cancellata'''.
Gli amministratori possono ancora visualizzarla; consultare il [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log delle cancellazioni] per ulteriori dettagli.",
'rev-suppressed-text-view'    => "Questa versione della pagina è stata '''rimossa'''.
Gli amministratori possono ancora visualizzarla; consultare il [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log di rimozione] per ulteriori dettagli.",
'rev-deleted-no-diff'         => "Non è possibile visualizzare questo confronto tra versioni perché una delle revisioni è stata '''cancellata'''.
Consultare il [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log delle cancellazioni] per ulteriori dettagli.",
'rev-suppressed-no-diff'      => "Non è possibile visualizzare questo confronto tra versioni perché una delle revisioni è stata '''cancellata'''.",
'rev-deleted-unhide-diff'     => "Una delle revisioni di questo confronto tra versioni è stata '''cancellata'''.
Consultare il [{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} log delle cancellazioni] per ulteriori dettagli.
Agli amministratori è ancora consentito [$1 visualizzare il confronto] se necessario.",
'rev-suppressed-unhide-diff'  => "Una delle revisioni di questo confronto di versioni è stata '''rimossa'''.
Potrebbero esserci dettagli nel [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log delle rimozioni].
In quanto amministratore puoi ancora [$1 visualizzare questo confronto di versioni], se vuoi procedere.",
'rev-deleted-diff-view'       => "Una delle revisioni di questo confronto di versioni è stata '''cancellata'''.
In quanto amministratore, puoi visualizzare questo confronto di versioni; potrebbero esserci dettagli nel [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log delle cancellazioni].",
'rev-suppressed-diff-view'    => "Una delle revisioni di questo confronto di versioni è stata '''rimossa'''.
In quanto amministratore puoi visualizzare questo confronto di versioni; potrebbero esserci dettagli nel [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log delle rimozioni].",
'rev-delundel'                => 'mostra/nascondi',
'rev-showdeleted'             => 'mostra',
'revisiondelete'              => 'Cancella o ripristina versioni',
'revdelete-nooldid-title'     => 'Versione non specificata',
'revdelete-nooldid-text'      => 'Non è stata specificata alcuna versione della pagina su cui eseguire questa funzione, la versione specificata non esiste oppure si sta tentando di nascondere la versione attuale.',
'revdelete-nologtype-title'   => 'Nessun tipo di registro specificato',
'revdelete-nologtype-text'    => "Non è stato specificato alcun tipo di registro su cui eseguire l'azione.",
'revdelete-nologid-title'     => 'Errore di indicazione del log',
'revdelete-nologid-text'      => 'Non è stato specificato un evento del registro su cui eseguire questa funzione oppure il log non esiste.',
'revdelete-no-file'           => 'Il file specificato non esiste.',
'revdelete-show-file-confirm' => 'Si desidera visualizzare la versione cancellata del file "<nowiki>$1</nowiki>" del $2 alle $3?',
'revdelete-show-file-submit'  => 'Sì',
'revdelete-selected'          => "'''{{PLURAL:$2|Versione selezionata|Versioni selezionate}} di [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Evento del registro selezionato|Eventi del registro selezionati}}:'''",
'revdelete-text'              => "'''Le versioni cancellate restano visibili nella cronologia della pagina, mentre il testo contenuto non è accessibile al pubblico.'''
Gli altri amministratori di {{SITENAME}} potranno accedere comunque ai contenuti nascosti e ripristinarli attraverso questa stessa interfaccia, se non sono state impostate altre limitazioni in fase di installazione del sito.",
'revdelete-confirm'           => 'Per favore conferma che questo è quanto intendi fare, che sei consapevole delle conseguenze, e che stai facendo questo nel rispetto delle [[{{MediaWiki:Policy-url}}|linee guida]].',
'revdelete-suppress-text'     => "La rimozione deve essere utilizzata '''unicamente''' nei seguenti casi:
* Dati personali inopportuni
*: ''indirizzi, numeri di telefono, codici fiscali, ecc.''",
'revdelete-legend'            => 'Imposta le seguenti limitazioni sulle versioni cancellate:',
'revdelete-hide-text'         => 'Nascondi il testo della versione',
'revdelete-hide-image'        => 'Nascondi i contenuti del file',
'revdelete-hide-name'         => 'Nascondi azione e oggetto della stessa',
'revdelete-hide-comment'      => "Nascondi l'oggetto della modifica o la motivazione dell'azione",
'revdelete-hide-user'         => "Nascondi il nome o l'indirizzo IP dell'autore",
'revdelete-hide-restricted'   => 'Nascondi le informazioni indicate anche agli amministratori',
'revdelete-radio-same'        => '(non cambiare)',
'revdelete-radio-set'         => 'Sì',
'revdelete-radio-unset'       => 'No',
'revdelete-suppress'          => 'Nascondi le informazioni anche agli amministratori',
'revdelete-unsuppress'        => 'Elimina le limitazioni sulle revisioni ripristinate',
'revdelete-log'               => 'Motivo:',
'revdelete-submit'            => 'Applica {{PLURAL:$1|alla revisione selezionata|alle revisioni selezionate}}',
'revdelete-success'           => "'''Visibilità della revisione aggiornata correttamente.'''",
'revdelete-failure'           => "'''La visibilità della versione non può essere aggiornata:'''
$1",
'logdelete-success'           => "'''Visibilità dell'evento impostata correttamente.'''",
'logdelete-failure'           => "'''La visibilità dell'evento non può essere impostata:'''
$1",
'revdel-restore'              => 'cambia la visibilità',
'revdel-restore-deleted'      => 'revisioni cancellate',
'revdel-restore-visible'      => 'revisioni visibili',
'pagehist'                    => 'Cronologia della pagina',
'deletedhist'                 => 'Cronologia cancellata',
'revdelete-hide-current'      => "Impossibile nascondere l'oggetto con data $1 $2 in quanto è la revisione attuale.",
'revdelete-show-no-access'    => 'Impossibile mostrare l\'oggetto con data $1 $2 in quanto è stato identificato come "riservato" e non si dispone del relativo accesso.',
'revdelete-modify-no-access'  => 'Impossibile modificare l\'oggetto con data $1 $2 in quanto è stato identificato come "riservato" e non si dispone del relativo accesso.',
'revdelete-modify-missing'    => "Impossibile modificare l'oggetto con ID $1 in quanto non è presente nel database.",
'revdelete-no-change'         => "'''Attenzione:''' l'oggetto con data $1 $2 aveva già le impostazioni di visibilità richieste.",
'revdelete-concurrent-change' => "Impossibile modificare l'oggetto con data $1 $2 in quanto il suo stato è stato modificato da un altro utente mentre se ne tentava la modifica.",
'revdelete-only-restricted'   => "Errore nel nascondere l'oggetto datato $1, $2: non è possibile nascondere gli oggetti alla vista degli amministratori senza selezionare almeno un'altra delle opzioni di rimozione.",
'revdelete-reason-dropdown'   => '* Motivazioni più comuni per la cancellazione
** Violazione di copyright
** Commenti o informazioni personali inappropriate
** Nome utente inappropriato
** Informazione potenzialmente diffamatoria',
'revdelete-otherreason'       => 'Altra motivazione o motivazione aggiuntiva:',
'revdelete-reasonotherlist'   => 'Altra motivazione',
'revdelete-edit-reasonlist'   => 'Modifica le motivazioni per la cancellazione',
'revdelete-offender'          => 'Autore della versione:',

# Suppression log
'suppressionlog'     => 'Soppressioni',
'suppressionlogtext' => "Di seguito sono elencate le cancellazioni e i blocchi con del contenuto nascosto agli amministratori.
Vedi l'[[Special:BlockList|elenco dei blocchi]] per l'elenco dei bandi e dei blocchi attivi al momento.",

# History merging
'mergehistory'                     => 'Unione cronologie',
'mergehistory-header'              => 'Questa pagina consente di unire le revisioni appartenenti alla cronologia di una pagina (detta pagina di origine) alla cronologia di una pagina più recente.
È necessario accertarsi che la continuità storica della pagina non venga alterata.',
'mergehistory-box'                 => 'Unisci la cronologia di due pagine:',
'mergehistory-from'                => 'Pagina di origine:',
'mergehistory-into'                => 'Pagina di destinazione:',
'mergehistory-list'                => "Cronologia cui è applicabile l'unione",
'mergehistory-merge'               => 'È possibile unire le revisioni di [[:$1]] indicate di seguito alla cronologia di [[:$2]]. Usare la colonna con i pulsanti di opzione per unire tutte le revisioni fino alla data e ora indicate. Si noti che se vengono usati i pulsanti di navigazione, la colonna con i pulsanti di opzione viene azzerata.',
'mergehistory-go'                  => 'Mostra le modifiche che possono essere unite',
'mergehistory-submit'              => 'Unisci le revisioni',
'mergehistory-empty'               => 'Nessuna revisione da unire.',
'mergehistory-success'             => '{{PLURAL:$3|Una revisione di [[:$1]] è stata unita|$3 revisioni di [[:$1]] sono state unite}} alla cronologia di [[:$2]].',
'mergehistory-fail'                => 'Impossibile unire le cronologie. Verificare la pagina e i parametri temporali.',
'mergehistory-no-source'           => 'La pagina di origine $1 non esiste.',
'mergehistory-no-destination'      => 'La pagina di destinazione $1 non esiste.',
'mergehistory-invalid-source'      => 'La pagina di origine deve avere un titolo corretto.',
'mergehistory-invalid-destination' => 'La pagina di destinazione deve avere un titolo corretto.',
'mergehistory-autocomment'         => 'Unione di [[:$1]] in [[:$2]]',
'mergehistory-comment'             => 'Unione di [[:$1]] in [[:$2]]: $3',
'mergehistory-same-destination'    => 'Le pagine di origine e di destinazione non possono coincidere',
'mergehistory-reason'              => 'Motivo:',

# Merge log
'mergelog'           => 'Unioni',
'pagemerge-logentry' => 'ha unito [[$1]] a [[$2]] (revisioni fino a $3)',
'revertmerge'        => 'Annulla unioni',
'mergelogpagetext'   => 'Di seguito sono elencate le ultime operazioni di unione della cronologia di due pagine.',

# Diffs
'history-title'            => '$1: cronologia delle modifiche',
'difference'               => '(Differenze fra le revisioni)',
'difference-multipage'     => '(Differenze fra le pagine)',
'lineno'                   => 'Riga $1:',
'compareselectedversions'  => 'Confronta le versioni selezionate',
'showhideselectedversions' => 'Mostra/nascondi versioni selezionate',
'editundo'                 => 'annulla',
'diff-multi'               => '({{PLURAL:$1|Una revisione intermedia|$1 revisioni intermedie}} di {{PLURAL:$2|un utente|$2 utenti}} non mostrate)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Una revisione intermedia|$1 revisioni intermedie}} di oltre $2 {{PLURAL:$2|utente|utenti}} non mostrate)',

# Search results
'searchresults'                    => 'Risultati della ricerca',
'searchresults-title'              => 'Risultati della ricerca di "$1"',
'searchresulttext'                 => 'Per maggiori informazioni sulla ricerca interna di {{SITENAME}}, vedi [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Ricerca di \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tutte le pagine che iniziano per "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tutte le pagine che puntano a "$1"]])',
'searchsubtitleinvalid'            => "Ricerca di '''$1'''",
'toomanymatches'                   => 'Troppe corrispondenze. Modificare la richiesta.',
'titlematches'                     => 'Corrispondenze nel titolo delle pagine',
'notitlematches'                   => 'Nessuna corrispondenza nei titoli delle pagine',
'textmatches'                      => 'Corrispondenze nel testo delle pagine',
'notextmatches'                    => 'Nessuna corrispondenza nel testo delle pagine',
'prevn'                            => '{{PLURAL:$1|precedente|precedenti $1}}',
'nextn'                            => '{{PLURAL:$1|successivo|successivi $1}}',
'prevn-title'                      => '{{PLURAL:$1|Risultato precedente|$1 risultati precedenti}}',
'nextn-title'                      => '{{PLURAL:$1|Risultato successivo|$1 risultati successivi}}',
'shown-title'                      => 'Mostra {{PLURAL:$1|un risultato|$1 risultati}} per pagina',
'viewprevnext'                     => 'Vedi ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Opzioni di ricerca',
'searchmenu-exists'                => "'''Sul sito esiste una pagina il cui nome è \"[[:\$1]]\"'''",
'searchmenu-new'                   => 'Crea la pagina "[[:$1]]" su questo sito',
'searchhelp-url'                   => 'Help:Indice',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Visualizza le pagine con questo prefisso]]',
'searchprofile-articles'           => 'Pagine di contenuti',
'searchprofile-project'            => 'Pagine di aiuto e relative al progetto',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Tutto',
'searchprofile-advanced'           => 'Avanzata',
'searchprofile-articles-tooltip'   => 'Cerca in $1',
'searchprofile-project-tooltip'    => 'Cerca in $1',
'searchprofile-images-tooltip'     => 'Cerca file',
'searchprofile-everything-tooltip' => 'Cerca ovunque (incluse le pagine di discussione)',
'searchprofile-advanced-tooltip'   => 'Cerca nei namespace personalizzati',
'search-result-size'               => '$1 ({{PLURAL:$2|una parola|$2 parole}})',
'search-result-category-size'      => '{{PLURAL:$1|1 utente|$1 utenti}} ({{PLURAL:$2|1 sottocategoria|$2 sottocategorie}}, {{PLURAL:$3|1 file|$3 files}})',
'search-result-score'              => 'Rilevanza: $1%',
'search-redirect'                  => '(redirect $1)',
'search-section'                   => '(sezione $1)',
'search-suggest'                   => 'Forse cercavi: $1',
'search-interwiki-caption'         => 'Progetti fratelli',
'search-interwiki-default'         => 'Risultati da $1:',
'search-interwiki-more'            => '(altro)',
'search-mwsuggest-enabled'         => 'con suggerimenti',
'search-mwsuggest-disabled'        => 'senza suggerimenti',
'search-relatedarticle'            => 'Risultati correlati',
'mwsuggest-disable'                => 'Disattiva suggerimenti AJAX',
'searcheverything-enable'          => 'Cerca in tutti i namespace',
'searchrelated'                    => 'correlati',
'searchall'                        => 'tutti',
'showingresults'                   => "Di seguito {{PLURAL:$1|viene presentato al massimo '''1''' risultato|vengono presentati al massimo '''$1''' risultati}} a partire dal numero '''$2'''.",
'showingresultsnum'                => "Di seguito {{PLURAL:$3|viene presentato '''1''' risultato|vengono presentati '''$3''' risultati}} a partire dal numero '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Risultato '''$1''' di '''$3'''|Risultati '''$1 - $2''' di '''$3'''}} per '''$4'''",
'nonefound'                        => "'''Nota''': la ricerca è effettuata per default solo in alcuni namespace. Prova a premettere ''all:'' al testo della ricerca per cercare in tutti i namespace (compresi pagine di discussione, template, ecc) oppure usa il namespace desiderato come prefisso.",
'search-nonefound'                 => 'La ricerca non ha prodotto risultati.',
'powersearch'                      => 'Ricerca',
'powersearch-legend'               => 'Ricerca avanzata',
'powersearch-ns'                   => 'Cerca nei namespace:',
'powersearch-redir'                => 'Elenca redirect',
'powersearch-field'                => 'Cerca',
'powersearch-togglelabel'          => 'Seleziona:',
'powersearch-toggleall'            => 'Tutti',
'powersearch-togglenone'           => 'Nessuno',
'search-external'                  => 'Ricerca esterna',
'searchdisabled'                   => 'La ricerca interna di {{SITENAME}} non è attiva; nel frattempo si può provare ad usare un motore di ricerca esterno come Google. (Si noti però che i contenuti di {{SITENAME}} presenti in tali motori potrebbero non essere aggiornati.)',

# Quickbar
'qbsettings'                => 'Quickbar',
'qbsettings-none'           => 'Nessuno',
'qbsettings-fixedleft'      => 'Fisso a sinistra',
'qbsettings-fixedright'     => 'Fisso a destra',
'qbsettings-floatingleft'   => 'Fluttuante a sinistra',
'qbsettings-floatingright'  => 'Fluttuante a destra',
'qbsettings-directionality' => 'Fisso, a seconda della direzione del testo nella tua lingua',

# Preferences page
'preferences'                   => 'Preferenze',
'mypreferences'                 => 'preferenze',
'prefs-edits'                   => 'Modifiche effettuate:',
'prefsnologin'                  => 'Accesso non effettuato',
'prefsnologintext'              => 'Per poter personalizzare le preferenze è necessario effettuare l\'<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} accesso]</span>.',
'changepassword'                => 'Cambia password',
'prefs-skin'                    => 'Aspetto grafico (skin)',
'skin-preview'                  => 'Anteprima',
'datedefault'                   => 'Nessuna preferenza',
'prefs-beta'                    => 'Funzionalità beta',
'prefs-datetime'                => 'Data e ora',
'prefs-labs'                    => 'Funzionalità sperimentali',
'prefs-personal'                => 'Profilo utente',
'prefs-rc'                      => 'Ultime modifiche',
'prefs-watchlist'               => 'Osservati speciali',
'prefs-watchlist-days'          => 'Numero di giorni da mostrare negli osservati speciali:',
'prefs-watchlist-days-max'      => 'Massimo $1 {{PLURAL:$1|giorno|giorni}}',
'prefs-watchlist-edits'         => 'Numero di modifiche da mostrare con le funzioni avanzate:',
'prefs-watchlist-edits-max'     => 'Numero massimo: 1000',
'prefs-watchlist-token'         => 'Token osservati speciali:',
'prefs-misc'                    => 'Varie',
'prefs-resetpass'               => 'Cambia password',
'prefs-changeemail'             => 'Modifica e-mail',
'prefs-setemail'                => 'Imposta un indirizzo e-mail',
'prefs-email'                   => 'Opzioni email',
'prefs-rendering'               => 'Aspetto',
'saveprefs'                     => 'Salva le preferenze',
'resetprefs'                    => 'Reimposta le preferenze',
'restoreprefs'                  => 'Ripristina le impostazioni predefinite',
'prefs-editing'                 => 'Casella di modifica',
'prefs-edit-boxsize'            => 'Dimensioni della finestra di modifica.',
'rows'                          => 'Righe:',
'columns'                       => 'Colonne:',
'searchresultshead'             => 'Ricerca',
'resultsperpage'                => 'Numero di risultati per pagina:',
'stub-threshold'                => 'Valore minimo per i <a href="#" class="stub">collegamenti agli stub</a>, in byte:',
'stub-threshold-disabled'       => 'Disattivato',
'recentchangesdays'             => 'Numero di giorni da mostrare nelle ultime modifiche:',
'recentchangesdays-max'         => '(massimo $1 {{PLURAL:$1|giorno|giorni}})',
'recentchangescount'            => 'Numero di modifiche da mostrare per default:',
'prefs-help-recentchangescount' => 'Comprende ultime modifiche, cronologie e registri.',
'prefs-help-watchlist-token'    => "Compilando questo campo con una chiave segreta verrà generato un feed RSS per i propri osservati speciali. Chiunque conosca la chiave in questo campo potrà leggere gli osservati speciali, quindi assicurasi di inserire un valore sicuro. Qui c'è un valore generato casualmente che si può usare: $1",
'savedprefs'                    => 'Le preferenze sono state salvate.',
'timezonelegend'                => 'Fuso orario:',
'localtime'                     => 'Ora locale:',
'timezoneuseserverdefault'      => 'Usa ora predefinita del wiki ($1)',
'timezoneuseoffset'             => 'Altro (specificare differenza)',
'timezoneoffset'                => 'Differenza¹:',
'servertime'                    => 'Ora del server:',
'guesstimezone'                 => "Usa l'ora del browser",
'timezoneregion-africa'         => 'Africa',
'timezoneregion-america'        => 'America',
'timezoneregion-antarctica'     => 'Antartide',
'timezoneregion-arctic'         => 'Artide',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Oceano Atlantico',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Oceano Indiano',
'timezoneregion-pacific'        => 'Oceano Pacifico',
'allowemail'                    => 'Abilita la ricezione di messaggi e-mail da altri utenti',
'prefs-searchoptions'           => 'Ricerca',
'prefs-namespaces'              => 'Namespace',
'defaultns'                     => 'In caso contrario, cerca in questi namespace:',
'default'                       => 'predefinito',
'prefs-files'                   => 'File',
'prefs-custom-css'              => 'CSS personalizzato',
'prefs-custom-js'               => 'JS personalizzato',
'prefs-common-css-js'           => 'CSS/JS condiviso per tutte le skin:',
'prefs-reset-intro'             => "È possibile usare questa pagina per reimpostare le proprie preferenze a quelle predefinite del sito.
L'operazione non può essere annullata.",
'prefs-emailconfirm-label'      => "Conferma dell'e-mail:",
'prefs-textboxsize'             => 'Dimensione della finestra di modifica',
'youremail'                     => 'Indirizzo e-mail:',
'username'                      => 'Nome utente:',
'uid'                           => 'ID utente:',
'prefs-memberingroups'          => 'Membro {{PLURAL:$1|del gruppo|dei gruppi}}:',
'prefs-registration'            => 'Data di registrazione:',
'yourrealname'                  => 'Nome vero:',
'yourlanguage'                  => "Lingua dell'interfaccia:",
'yourvariant'                   => 'Variante della lingua:',
'prefs-help-variant'            => 'La variante o grafia in cui preferisci che le pagine del wiki ti siano mostrate.',
'yournick'                      => 'Soprannome (nickname):',
'prefs-help-signature'          => 'I commenti nelle pagine di discussione devono essere firmati con "<nowiki>~~~~</nowiki>" che verrà convertito nella propria firma seguita dalla data.',
'badsig'                        => 'Errore nella firma non standard, verificare i tag HTML.',
'badsiglength'                  => 'La firma scelta è troppo lunga, non deve superare $1 {{PLURAL:$1|carattere|caratteri}}.',
'yourgender'                    => 'Genere:',
'gender-unknown'                => 'Non specificato',
'gender-male'                   => 'Maschile',
'gender-female'                 => 'Femminile',
'prefs-help-gender'             => "Opzionale: consente di adattare i messaggi del software in funzione del genere dell'utente. Questa informazione sarà pubblica.",
'email'                         => 'Indirizzo email',
'prefs-help-realname'           => "L'indicazione del proprio nome vero è opzionale; se si sceglie di inserirlo, verrà utilizzato per attribuire la paternità dei contenuti inviati.",
'prefs-help-email'              => "L'inserimento del proprio indirizzo e-mail è facoltativo, ma permette di ricevere la propria password qualora venisse dimenticata.",
'prefs-help-email-others'       => 'Puoi anche scegliere di lasciare che gli altri ti contattino via posta elettronica con un collegamento dalla tua pagina utente o di discussione.
Il tuo indirizzo non viene rivelato quando gli altri utenti ti contattano.',
'prefs-help-email-required'     => 'Indirizzo e-mail obbligatorio.',
'prefs-info'                    => 'Informazioni di base',
'prefs-i18n'                    => 'Internazionalizzazione',
'prefs-signature'               => 'Firma',
'prefs-dateformat'              => 'Formato data',
'prefs-timeoffset'              => 'Ore di differenza',
'prefs-advancedediting'         => 'Opzioni avanzate',
'prefs-advancedrc'              => 'Opzioni avanzate',
'prefs-advancedrendering'       => 'Opzioni avanzate',
'prefs-advancedsearchoptions'   => 'Opzioni avanzate',
'prefs-advancedwatchlist'       => 'Opzioni avanzate',
'prefs-displayrc'               => 'Opzioni di visualizzazione',
'prefs-displaysearchoptions'    => 'Opzioni di visualizzazione',
'prefs-displaywatchlist'        => 'Opzioni di visualizzazione',
'prefs-diffs'                   => 'Differenze',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => "L'indirizzo e-mail sembra valido",
'email-address-validity-invalid' => 'Inserisci un indirizzo e-mail valido',

# User rights
'userrights'                   => 'Gestione dei permessi relativi agli utenti',
'userrights-lookup-user'       => 'Gestione dei gruppi utente',
'userrights-user-editname'     => 'Inserire il nome utente:',
'editusergroup'                => 'Modifica gruppi utente',
'editinguser'                  => "Modifica diritti utente dell'utente '''[[User:$1|$1]]''' $2",
'userrights-editusergroup'     => 'Modifica gruppi utente',
'saveusergroups'               => 'Salva gruppi utente',
'userrights-groupsmember'      => 'Appartiene ai gruppi:',
'userrights-groupsmember-auto' => 'Membro implicito di:',
'userrights-groups-help'       => "È possibile modificare i gruppi cui è assegnato l'utente.
* Una casella di spunta selezionata indica l'appartenenza dell'utente al gruppo
* Una casella di spunta deselezionata indica la sua mancata appartenenza al gruppo.
* Il simbolo * indica che non è possibile eliminare l'appartenenza al gruppo dopo averla aggiunta (o vice versa).",
'userrights-reason'            => 'Motivo:',
'userrights-no-interwiki'      => 'Non si dispone dei permessi necessari per modificare i diritti degli utenti su altri siti.',
'userrights-nodatabase'        => 'Il database $1 non esiste o non è un database locale.',
'userrights-nologin'           => "Per assegnare diritti agli utenti è necessario [[Special:UserLogin|effettuare l'accesso]] come amministratore.",
'userrights-notallowed'        => "Il tuo account non dispone dell'autorizzazione per aggiungere o rimuovere i permessi utente.",
'userrights-changeable-col'    => 'Gruppi modificabili',
'userrights-unchangeable-col'  => 'Gruppi non modificabili',

# Groups
'group'               => 'Gruppo:',
'group-user'          => 'Utenti',
'group-autoconfirmed' => 'Utenti autoconvalidati',
'group-bot'           => 'Bot',
'group-sysop'         => 'Amministratori',
'group-bureaucrat'    => 'Burocrati',
'group-suppress'      => 'Oversight',
'group-all'           => 'Utenti',

'group-user-member'          => '{{GENDER:$1|utente}}',
'group-autoconfirmed-member' => '{{GENDER:$1|utente autoconvalidato|utente autoconvalidata|utente autoconvalidato/a}}',
'group-bot-member'           => '{{GENDER:$1|bot}}',
'group-sysop-member'         => '{{GENDER:$1|amministratore|amministratrice|amministratore/trice}}',
'group-bureaucrat-member'    => '{{GENDER:$1|burocrate}}',
'group-suppress-member'      => '{{GENDER:$1|oversight}}',

'grouppage-user'          => '{{ns:project}}:Utenti',
'grouppage-autoconfirmed' => '{{ns:project}}:Utenti autoconvalidati',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Amministratori',
'grouppage-bureaucrat'    => '{{ns:project}}:Burocrati',
'grouppage-suppress'      => '{{ns:project}}:Oversight',

# Rights
'right-read'                  => 'Legge pagine',
'right-edit'                  => 'Modifica pagine',
'right-createpage'            => 'Crea pagine (escluse le pagine di discussione)',
'right-createtalk'            => 'Crea pagine di discussione',
'right-createaccount'         => 'Crea nuovi account utente',
'right-minoredit'             => 'Segna le modifiche come minori',
'right-move'                  => 'Sposta pagine',
'right-move-subpages'         => 'Sposta le pagine insieme alle relative sottopagine',
'right-move-rootuserpages'    => 'Sposta le pagine principali degli utenti',
'right-movefile'              => 'Sposta file',
'right-suppressredirect'      => 'Non crea un redirect automatico quando si sposta una pagina',
'right-upload'                => 'Carica file',
'right-reupload'              => 'Sovrascrive un file esistente',
'right-reupload-own'          => 'Sovrascrive un file esistente caricato dallo stesso utente',
'right-reupload-shared'       => "Sovrascrive localmente file presenti nell'archivio condiviso",
'right-upload_by_url'         => 'Carica un file da un indirizzo URL',
'right-purge'                 => 'Pulisce la cache del sito senza conferma',
'right-autoconfirmed'         => 'Modifica le pagine semiprotette',
'right-bot'                   => 'Da trattare come processo automatico',
'right-nominornewtalk'        => "Fa sì che le modifiche minori alle pagine di discussione non facciano comparire l'avviso di nuovo messaggio",
'right-apihighlimits'         => 'Usa limiti più alti per le interrogazioni API',
'right-writeapi'              => "Usa l'API in scrittura",
'right-delete'                => 'Cancella pagine',
'right-bigdelete'             => 'Cancella pagine con cronologie lunghe',
'right-deleterevision'        => 'Nasconde revisioni specifiche delle pagine',
'right-deletedhistory'        => 'Visualizza le revisioni della cronologia cancellate senza il testo associato',
'right-deletedtext'           => 'Visualizza testo cancellato e modifiche fra revisioni cancellate',
'right-browsearchive'         => 'Ricerca nelle pagine cancellate',
'right-undelete'              => 'Recupera una pagina',
'right-suppressrevision'      => 'Rivede e recupera revisioni nascoste agli amministratori',
'right-suppressionlog'        => 'Visualizza log privati',
'right-block'                 => 'Blocca le modifiche da parte di altri utenti',
'right-blockemail'            => 'Impedisce a un utente di inviare email',
'right-hideuser'              => 'Blocca un nome utente, nascondendolo al pubblico',
'right-ipblock-exempt'        => 'Ignora i blocchi degli IP, i blocchi automatici e i blocchi di range di IP',
'right-proxyunbannable'       => 'Scavalca i blocchi sui proxy',
'right-unblockself'           => 'Sblocca se stesso',
'right-protect'               => 'Cambia i livelli di protezione e modifica pagine protette',
'right-editprotected'         => 'Modifica pagine protette',
'right-editinterface'         => "Modifica l'interfaccia utente",
'right-editusercssjs'         => 'Modifica i file CSS e JS di altri utenti',
'right-editusercss'           => 'Modifica i file CSS di altri utenti',
'right-edituserjs'            => 'Modifica i file JS di altri utenti',
'right-rollback'              => "Annulla rapidamente le modifiche dell'ultimo utente che ha modificato una particolare pagina",
'right-markbotedits'          => 'Segna le modifiche soggette a rollback come effettuate da bot',
'right-noratelimit'           => 'Non soggetto al limite di azioni',
'right-import'                => 'Importa pagine da altri wiki',
'right-importupload'          => 'Importa pagine da un upload di file',
'right-patrol'                => 'Segna le modifiche degli altri utenti come verificate',
'right-autopatrol'            => 'Segna automaticamente le proprie modifiche come verificate',
'right-patrolmarks'           => 'Usa la funzione di verifica delle ultime modifiche',
'right-unwatchedpages'        => 'Visualizza una lista di pagine non osservate',
'right-mergehistory'          => 'Fonde la cronologia delle pagine',
'right-userrights'            => "Modifica tutti i diritti dell'utente",
'right-userrights-interwiki'  => 'Modifica i diritti degli utenti di altre wiki',
'right-siteadmin'             => 'Blocca e sblocca il database',
'right-override-export-depth' => 'Esporta le pagine includendo le pagine collegate fino ad una profondità di 5',
'right-sendemail'             => 'Invia e-mail ad altri utenti',
'right-passwordreset'         => 'Vede i messaggi di reimpostazione della password',

# User rights log
'rightslog'                  => 'Diritti degli utenti',
'rightslogtext'              => 'Di seguito sono elencate le modifiche ai diritti assegnati agli utenti.',
'rightslogentry'             => "ha modificato l'appartenenza di $1 dal gruppo $2 al gruppo $3",
'rightslogentry-autopromote' => 'è stato/a automaticamente promosso/a da $2 a $3',
'rightsnone'                 => '(nessuno)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'leggere questa pagina',
'action-edit'                 => 'modificare questa pagina',
'action-createpage'           => 'creare pagine',
'action-createtalk'           => 'creare pagine di discussione',
'action-createaccount'        => 'creare questo account utente',
'action-minoredit'            => 'segnare questa modifica come minore',
'action-move'                 => 'spostare questa pagina',
'action-move-subpages'        => 'spostare questa pagina e le relative sottopagine',
'action-move-rootuserpages'   => 'spostare le pagine principali degli utenti',
'action-movefile'             => 'spostare questo file',
'action-upload'               => 'caricare questo file',
'action-reupload'             => 'sovrascrivere questo file esistente',
'action-reupload-shared'      => "sovrascrivere questo file presente nell'archivio condiviso",
'action-upload_by_url'        => 'caricare questo file da un indirizzo URL',
'action-writeapi'             => 'usare le API in scrittura',
'action-delete'               => 'cancellare questa pagina',
'action-deleterevision'       => 'cancellare questa versione',
'action-deletedhistory'       => 'visualizzare la cronologia cancellata di questa pagina',
'action-browsearchive'        => 'cercare pagine cancellate',
'action-undelete'             => 'recuperare custa pàgina',
'action-suppressrevision'     => 'rivedere e ripristinare le modifiche nascoste',
'action-suppressionlog'       => 'visionare questo log privato',
'action-block'                => 'bloccare questo utente in scrittura',
'action-protect'              => 'modificare i livelli di protezione per questa pagina',
'action-rollback'             => "annullare rapidamente le modifiche dell'ultimo utente che ha modificato una determinata pagina",
'action-import'               => "importare questa pagina da un'altra wiki",
'action-importupload'         => 'importare questa pagina tramite upload da file',
'action-patrol'               => 'segnare le modifiche degli altri utenti come verificate',
'action-autopatrol'           => 'segnare le proprie modifiche come verificate',
'action-unwatchedpages'       => 'visionare la lista di pagine non osservate',
'action-mergehistory'         => 'unire la cronologia di questa pagina',
'action-userrights'           => 'modificare tutti i diritti degli utenti',
'action-userrights-interwiki' => 'modificare i diritti degli utenti su altre wiki',
'action-siteadmin'            => 'bloccare e sbloccare il database',
'action-sendemail'            => 'inviare e-mail',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|modifica|modifiche}}',
'recentchanges'                     => 'Ultime modifiche',
'recentchanges-legend'              => 'Opzioni ultime modifiche',
'recentchangestext'                 => 'Questa pagina presenta le modifiche più recenti ai contenuti del sito.',
'recentchanges-feed-description'    => 'Questo feed riporta le modifiche più recenti ai contenuti del sito.',
'recentchanges-label-newpage'       => 'Questa modifica ha creato una nuova pagina',
'recentchanges-label-minor'         => 'Questa è una modifica minore',
'recentchanges-label-bot'           => 'Questa modifica è stata effettuata da un bot',
'recentchanges-label-unpatrolled'   => 'Questa modifica non è stata ancora verificata',
'rcnote'                            => "Di seguito {{PLURAL:$1|è elencata la modifica più recente apportata|sono elencate le '''$1''' modifiche più recenti apportate}} al sito {{PLURAL:$2|nelle ultime 24 ore|negli scorsi '''$2''' giorni}}; i dati sono aggiornati alle $5 del $4.",
'rcnotefrom'                        => "Di seguito sono elencate le modifiche apportate a partire da '''$2''' (fino a '''$1''').",
'rclistfrom'                        => 'Mostra le modifiche apportate a partire da $1',
'rcshowhideminor'                   => '$1 le modifiche minori',
'rcshowhidebots'                    => '$1 i bot',
'rcshowhideliu'                     => '$1 gli utenti registrati',
'rcshowhideanons'                   => '$1 gli utenti anonimi',
'rcshowhidepatr'                    => '$1 le modifiche verificate',
'rcshowhidemine'                    => '$1 le mie modifiche',
'rclinks'                           => 'Mostra le $1 modifiche più recenti apportate negli ultimi $2 giorni<br />$3',
'diff'                              => 'diff',
'hist'                              => 'cron',
'hide'                              => 'Nascondi',
'show'                              => 'Mostra',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[osservata da {{PLURAL:$1|un utente|$1 utenti}}]',
'rc_categories'                     => 'Limita alle categorie (separate da "|")',
'rc_categories_any'                 => 'Qualsiasi',
'rc-change-size-new'                => '$1 {{PLURAL:$1|byte|byte}} dopo la modifica',
'newsectionsummary'                 => '/* $1 */ nuova sezione',
'rc-enhanced-expand'                => 'Mostra dettagli (richiede JavaScript)',
'rc-enhanced-hide'                  => 'Nascondi dettagli',
'rc-old-title'                      => 'originariamente creata come "$1"',

# Recent changes linked
'recentchangeslinked'          => 'Modifiche correlate',
'recentchangeslinked-feed'     => 'Modifiche correlate',
'recentchangeslinked-toolbox'  => 'Modifiche correlate',
'recentchangeslinked-title'    => 'Modifiche correlate a "$1"',
'recentchangeslinked-noresult' => 'Nessuna modifica alle pagine collegate nel periodo specificato.',
'recentchangeslinked-summary'  => "Questa pagina speciale mostra le modifiche più recenti alle pagine collegate da quella specificata (o contenute nella categoria specificata).
Le pagine contenute nella propria lista degli [[Special:Watchlist|Osservati speciali]] sono evidenziate in '''grassetto'''.",
'recentchangeslinked-page'     => 'Nome della pagina:',
'recentchangeslinked-to'       => 'Mostra solo le modifiche alle pagine collegate a quella specificata',

# Upload
'upload'                      => 'Carica un file',
'uploadbtn'                   => 'Carica',
'reuploaddesc'                => 'Torna al modulo per il caricamento.',
'upload-tryagain'             => 'Invia la descrizione del file modificato',
'uploadnologin'               => 'Accesso non effettuato',
'uploadnologintext'           => "Il caricamento dei file è consentito solo agli utenti registrati che hanno eseguito [[Special:UserLogin|l'accesso]] al sito.",
'upload_directory_missing'    => 'La directory di upload ($1) non esiste e non può essere creata dal server web.',
'upload_directory_read_only'  => 'Il server web non è in grado di scrivere nella directory di upload ($1).',
'uploaderror'                 => 'Errore nel caricamento',
'upload-recreate-warning'     => "'Attenzione'': un file con questo nome è stato cancellato o spostato.'''
Ill log delle cancellazioni e degli spostamenti di questa pagina viene qui riportato per comodità:",
'uploadtext'                  => "Usare il modulo sottostante per caricare nuovi file. Per visualizzare o ricercare i file già caricati, consultare il [[Special:FileList|log dei file caricati]]. Caricamenti di file e di nuove versioni di file sono registrati nel [[Special:Log/upload|log degli upload]], le cancellazioni nell'[[Special:Log/delete|apposito]].

Per inserire un file all'interno di una pagina, fare un collegamento di questo tipo:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' per usare la versione completa del file
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|testo alternativo]]</nowiki></code>''' per usare una versione larga 200 pixel inserita in un box, allineata a sinistra e con 'testo alternativo' come didascalia
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' per generare un collegamento diretto al file senza visualizzarlo",
'upload-permitted'            => 'Tipi di file consentiti: $1.',
'upload-preferred'            => 'Tipi di file consigliati: $1.',
'upload-prohibited'           => 'Tipi di file non consentiti: $1.',
'uploadlog'                   => 'File caricati',
'uploadlogpage'               => 'File caricati',
'uploadlogpagetext'           => "Di seguito sono elencati gli ultimi file caricati.
Consultare la [[Special:NewFiles|galleria dei nuovi file]] per una visione d'insieme.",
'filename'                    => 'Nome del file',
'filedesc'                    => 'Dettagli',
'fileuploadsummary'           => 'Dettagli del file:',
'filereuploadsummary'         => 'Cambiamenti al file:',
'filestatus'                  => 'Informazioni sul copyright:',
'filesource'                  => 'Fonte:',
'uploadedfiles'               => 'Elenco dei file caricati',
'ignorewarning'               => "Ignora l'avviso e salva comunque il file",
'ignorewarnings'              => 'Ignora i messaggi di avvertimento del sistema',
'minlength1'                  => "Il nome del file dev'essere composto da almeno un carattere.",
'illegalfilename'             => 'Il nome "$1" contiene dei caratteri non ammessi nei titoli delle pagine. Dare al file un nome diverso e provare a caricarlo di nuovo.',
'filename-toolong'            => 'I nomi dei file non possono superare i 240 byte.',
'badfilename'                 => 'Il nome del file è stato convertito in "$1".',
'filetype-mime-mismatch'      => 'L\'estensione del file ".$1" non corrisponde al tipo MIME rilevato dal file ($2).',
'filetype-badmime'            => 'Non è consentito caricare file di tipo MIME "$1".',
'filetype-bad-ie-mime'        => 'Impossibile caricare il file perché Internet Explorer lo rileverebbe come "$1", che è un tipo di file non consentito e potenzialmente pericoloso.',
'filetype-unwanted-type'      => "Caricare file di tipo '''\".\$1\"''' è sconsigliato. {{PLURAL:\$3|Il tipo di file consigliato è|I tipi di file consigliati sono}} \$2.",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|non è un tipo di file consentito|non sono tipi di file consentiti}}. {{PLURAL:$3|Il tipo di file consentito è|I tipi di file consentiti sono}} $2.',
'filetype-missing'            => 'Il file è privo di estensione (ad es. ".jpg").',
'empty-file'                  => 'Il file che hai inviato è vuoto.',
'file-too-large'              => 'Il file che hai inviato è troppo grande.',
'filename-tooshort'           => 'Il nome del file è troppo breve.',
'filetype-banned'             => 'Questo tipo di file è vietato.',
'verification-error'          => 'Questo file non ha superato la verifica.',
'hookaborted'                 => "La modifica che si è tentato di fare è stata interrotta da un hook di un'estensione.",
'illegal-filename'            => 'Il nome del file non è ammesso.',
'overwrite'                   => 'Sovrascrivere un file esistente non è permesso.',
'unknown-error'               => 'Si è verificato un errore sconosciuto.',
'tmp-create-error'            => 'Impossibile creare il file temporaneo.',
'tmp-write-error'             => 'Errore di scrittura del file temporaneo.',
'large-file'                  => 'Si raccomanda di non superare le dimensioni di $1 per ciascun file; questo file è grande $2.',
'largefileserver'             => 'Il file supera le dimensioni consentite dalla configurazione del server.',
'emptyfile'                   => 'Il file appena caricato sembra essere vuoto. Ciò potrebbe essere dovuto ad un errore nel nome del file. Verificare che si intenda realmente caricare questo file.',
'windows-nonascii-filename'   => 'Questo wiki non supporta nomi di file con caratteri speciali.',
'fileexists'                  => 'Un file con questo nome esiste già.
Verificare prima <strong>[[:$1]]</strong> se non si è sicuri di volerlo sovrascrivere.
[[$1|thumb]]',
'filepageexists'              => "La pagina di descrizione di questo file è già stata creata all'indirizzo <strong>[[:$1]]</strong>, anche se non esiste ancora un file con questo nome. La descrizione dell'oggetto inserita in fase di caricamento non apparirà sulla pagina di descrizione. Per far sì che l'oggetto compaia sulla pagina di descrizione, sarà necessario modificarla manualmente.
[[$1|thumb]]",
'fileexists-extension'        => 'Un file con nome simile a questo esiste già: [[$2|thumb]]
* Nome del file caricato: <strong>[[:$1]]</strong>
* Nome del file esistente: <strong>[[:$2]]</strong>
Scegliere un nome diverso.',
'fileexists-thumbnail-yes'    => "Il file caricato sembra essere una miniatura ''(thumbnail)''. [[$1|thumb]]
Verificare, per confronto, il file <strong>[[:$1]]</strong>.
Se si tratta della stessa immagine, nelle dimensioni originali, non è necessario caricarne altre miniature.",
'file-thumbnail-no'           => "Il nome del file inizia con <strong>$1</strong>; sembra quindi essere una miniatura ''(thumbnail)''.
Se si dispone dell'immagine nella risoluzione originale, si prega di caricarla. In caso contrario, si prega di cambiare il nome del file.",
'fileexists-forbidden'        => 'Un file con questo nome esiste già e non può essere sovrascritto. Tornare indietro e modificare il nome con il quale caricare il file. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Un file con questo nome esiste già nell'archivio di risorse multimediali condivise. Se si desidera ancora caricare il file, tornare indietro e modificare il nome con il quale caricare il file. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => 'Questo file è un duplicato {{PLURAL:$1|del seguente|dei seguenti}} file:',
'file-deleted-duplicate'      => 'Un file identico a questo ([[:$1]]) è stato cancellato in passato. Verificare la cronologia delle cancellazioni prima di caricarlo di nuovo.',
'uploadwarning'               => 'Avviso di caricamento',
'uploadwarning-text'          => 'Per favore modifica qui sotto la descrizione del file e prova di nuovo.',
'savefile'                    => 'Salva file',
'uploadedimage'               => 'ha caricato "[[$1]]"',
'overwroteimage'              => 'ha caricato una nuova versione di "[[$1]]"',
'uploaddisabled'              => 'Il caricamento dei file è temporaneamente sospeso.',
'copyuploaddisabled'          => 'Il caricamento tramite URL è disabilitato.',
'uploadfromurl-queued'        => 'Il caricamento è stato accodato.',
'uploaddisabledtext'          => 'Il caricamento dei file non è attivo.',
'php-uploaddisabledtext'      => 'Il caricamento di file tramite PHP è disabilitato. Controlla la configurazione di file_uploads.',
'uploadscripted'              => 'Questo file contiene codice HTML o di script, che potrebbe essere interpretato erroneamente da un browser web.',
'uploadvirus'                 => 'Questo file contiene un virus! Dettagli: $1',
'uploadjava'                  => "Questo file è un file ZIP che contiene un file .class Java.
Carica i file Java non è consentito, perché possono causare l'aggiramento delle restrizioni di sicurezza.",
'upload-source'               => 'File di origine',
'sourcefilename'              => 'Nome del file di origine:',
'sourceurl'                   => 'URL di origine:',
'destfilename'                => 'Nome del file di destinazione:',
'upload-maxfilesize'          => 'Dimensione massima del file: $1',
'upload-description'          => 'Descrizione del file',
'upload-options'              => 'Opzioni di caricamento',
'watchthisupload'             => 'Aggiungi agli osservati speciali',
'filewasdeleted'              => 'Un file con questo nome è stato già caricato e cancellato in passato. Verificare il log delle $1 prima di caricarlo di nuovo.',
'filename-bad-prefix'         => "Il nome del file che si sta caricando inizia con '''\"\$1\"''', che è un nome generico simile a quelli assegnati automaticamente dalle fotocamere digitali. Si prega di scegliere un nome più descrittivo per il file.",
'filename-prefix-blacklist'   => ' #<!-- lascia questa riga esattamente com\'è --> <pre>
# La sintassi è la seguente:
#   * Tutto ciò che segue il carattere "#" sino alla fine della riga è un commento
#   * Ogni riga non vuota è un prefisso per nomi di file tipici assegnati automaticamente da fotocamere digitali
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # alcuni telefonini
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- lascia questa riga esattamente com\'è -->',
'upload-success-subj'         => 'Caricamento completato',
'upload-success-msg'          => "L'upload da [$2] è riuscito. Il file è disponibile qui: [[:{{ns:file}}:$1]]",
'upload-failure-subj'         => "Problema nell'upload",
'upload-failure-msg'          => 'Si è verificato un problema con il caricamento da [$2]:

$1',
'upload-warning-subj'         => 'Avviso di caricamento',
'upload-warning-msg'          => "C'è stato un problema con il caricamento da [$2]. Si può tornare al [[Special:Upload/stash/$1|form di upload]] per correggere questo problema.",

'upload-proto-error'        => 'Protocollo errato',
'upload-proto-error-text'   => "Per l'upload remoto è necessario specificare URL che iniziano con <code>http://</code> oppure <code>ftp://</code>.",
'upload-file-error'         => 'Errore interno',
'upload-file-error-text'    => 'Si è verificato un errore interno durante la creazione di un file temporaneo sul server. Contattare un [[Special:ListUsers/sysop|amministratore]] del sito.',
'upload-misc-error'         => 'Errore di caricamento sconosciuto',
'upload-misc-error-text'    => 'Si è verificato un errore non identificato durante il caricamento del file. Verificare che la URL sia corretta e accessibile e provare di nuovo. Se il problema persiste, contattare un [[Special:ListUsers/sysop|amministratore]] del sito.',
'upload-too-many-redirects' => "L'URL conteneva troppi redirect",
'upload-unknown-size'       => 'Dimensione sconosciuta',
'upload-http-error'         => 'Si è verificato un errore HTTP: $1',

# File backend
'backend-fail-stream'        => 'Impossibile trasmettere il file $1.',
'backend-fail-backup'        => 'Impossibile eseguire il backup del file $1 .',
'backend-fail-notexists'     => 'Il file $1 non esiste.',
'backend-fail-hashes'        => 'Impossibile ottenere hash dei file per confronto.',
'backend-fail-notsame'       => 'Esiste già un file non identico a  $1 .',
'backend-fail-invalidpath'   => '$1 non è un percorso di archiviazione valido.',
'backend-fail-delete'        => 'Impossibile cancellare il file $1.',
'backend-fail-alreadyexists' => 'Il file $1 esiste già.',
'backend-fail-store'         => 'Impossibilie memorizzare file  $1  in  $2 .',
'backend-fail-copy'          => 'Impossibile copiare il file  $1  in  $2 .',
'backend-fail-move'          => 'Impossibile spostare file  $1  in  $2 .',
'backend-fail-opentemp'      => 'Impossibile aprire il file temporaneo.',
'backend-fail-writetemp'     => 'Impossibile creare il file temporaneo.',
'backend-fail-closetemp'     => 'Impossibile chiudere il file temporaneo.',
'backend-fail-read'          => 'Impossibile leggere il file  $1 .',
'backend-fail-create'        => 'Impossibile creare il file $1.',
'backend-fail-readonly'      => 'Il backend "$1" è attualmente di sola lettura. La ragione indicata è: "$2"',
'backend-fail-synced'        => 'Il file "$1" è in uno stato non coerente nei backend di memoria interna.',
'backend-fail-connect'       => 'Impossibile connettersi al backend di memoria "$1".',
'backend-fail-internal'      => 'Si è verificato un errore sconosciuto nel backend di memoria "$1".',
'backend-fail-contenttype'   => 'Impossibile determinare la tipologia del file da archiviare in "$1".',
'backend-fail-batchsize'     => 'Il backend di memoria ha programmato una serie di $1 {{PLURAL:$1|operazione|operazioni}} su file; il limite è di $2 {{PLURAL:$2|operazione|operazioni}}.',

# Lock manager
'lockmanager-notlocked'        => 'Impossibile sbloccare "$1"; non è bloccato.',
'lockmanager-fail-closelock'   => 'Non riuscita chiusura del file di blocco per "$1".',
'lockmanager-fail-deletelock'  => 'Non riuscita cancellazione del file di blocco per "$1".',
'lockmanager-fail-acquirelock' => 'Non riuscita acquisizione blocco per "$1".',
'lockmanager-fail-openlock'    => 'Non riuscita apertura del file di blocco per "$1".',
'lockmanager-fail-releaselock' => 'Non riuscito rilascio del blocco per "$1".',
'lockmanager-fail-db-bucket'   => 'Impossibile contattare i necessari database di blocco nel bucket $1.',
'lockmanager-fail-db-release'  => 'Impossibile revocare i blocchi sul database $1.',
'lockmanager-fail-svr-release' => 'Impossibile revocare i blocchi sul server $1.',

# ZipDirectoryReader
'zip-file-open-error' => "Si è verificato un errore durante l'apertura del file per i controlli ZIP.",
'zip-wrong-format'    => 'Il file specificato non è un file ZIP.',
'zip-bad'             => 'Il file è un file ZIP corrotto o altrimenti illeggibile.
Non può essere adeguatamente controllato per la sicurezza.',
'zip-unsupported'     => 'Il file è un file ZIP che usa caratteristiche ZIP non supportate da MediaWiki.
Non può essere adeguatamente controllato per la sicurezza.',

# Special:UploadStash
'uploadstash'          => 'Carica stash',
'uploadstash-summary'  => "Questa pagina consente l'accesso ai file che sono stati caricati (o sono in fase di caricamento) ma che non sono stati ancora pubblicati sul wiki. Questi file sono visibili solo all'utente che li ha caricati.",
'uploadstash-clear'    => 'Elimina i file in stash',
'uploadstash-nofiles'  => 'Non hai file in stash.',
'uploadstash-badtoken' => 'Questa azione non ha avuto successo, forse perché le tue credenziali di modifica sono scadute. Prova ancora.',
'uploadstash-errclear' => 'La cancellazione dei file non ha avuto successo.',
'uploadstash-refresh'  => "Aggiorna l'elenco dei file",
'invalid-chunk-offset' => 'Offset della parte non valido.',

# img_auth script messages
'img-auth-accessdenied'     => 'Accesso negato',
'img-auth-nopathinfo'       => 'PATH_INFO mancante.
Il server non è impostato per passare questa informazione.
Potrebbe essere basato su CGI e non può supportare img_auth.
Vedi https://www.mediawiki.org/wiki/Manual:Image_Authorization',
'img-auth-notindir'         => 'Il percorso richiesto non si trova nella directory di upload configurata.',
'img-auth-badtitle'         => 'Impossibile costruire un titolo valido da "$1".',
'img-auth-nologinnWL'       => 'Non si è effettuato l\'accesso e "$1" non è nella whitelist.',
'img-auth-nofile'           => 'File "$1" non esiste.',
'img-auth-isdir'            => 'Si sta tentando di accedere a una directory "$1".
Solo l\'accesso ai file è consentito.',
'img-auth-streaming'        => '"$1" in streaming.',
'img-auth-public'           => 'La funzione di img_auth.php è di dare in output file da un sito wiki privato.
Questo sito è configurato come un wiki pubblico.
Per una sicurezza ottimale, img_auth.php è disattivato.',
'img-auth-noread'           => 'L\'utente non ha accesso alla lettura di "$1".',
'img-auth-bad-query-string' => "L'URL contiene una stringa di query non valida.",

# HTTP errors
'http-invalid-url'      => 'URL non valido: $1',
'http-invalid-scheme'   => 'URL con il prefisso "$1" non sono supportati.',
'http-request-error'    => 'Richiesta HTTP fallita a causa di un errore sconosciuto.',
'http-read-error'       => 'Errore di lettura HTTP.',
'http-timed-out'        => 'Richiesta HTTP scaduta.',
'http-curl-error'       => "Errore durante il recupero dell'URL: $1",
'http-host-unreachable' => 'URL non raggiungibile.',
'http-bad-status'       => "C'è stato un problema durante la richiesta HTTP: $1 $2",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL non raggiungibile',
'upload-curl-error6-text'  => 'Impossibile raggiungere la URL specificata. Verificare che la URL sia scritta correttamente e che il sito in questione sia attivo.',
'upload-curl-error28'      => "Tempo scaduto per l'upload",
'upload-curl-error28-text' => 'Il sito remoto ha impiegato troppo tempo a rispondere. Verificare che il sito sia attivo, attendere qualche minuto e provare di nuovo, eventualmente in un momento di minore traffico.',

'license'            => 'Licenza:',
'license-header'     => 'Licenza',
'nolicense'          => 'Nessuna licenza indicata',
'license-nopreview'  => '(Anteprima non disponibile)',
'upload_source_url'  => ' (una URL corretta e accessibile)',
'upload_source_file' => ' (un file sul proprio computer)',

# Special:ListFiles
'listfiles-summary'     => "Questa pagina speciale mostra tutti i file caricati.
Se vengono filtrati per utente, saranno mostrati solamente quei file per i quali l'utente ha caricato la versione più recente.",
'listfiles_search_for'  => 'Ricerca immagini per nome:',
'imgfile'               => 'file',
'listfiles'             => 'Elenco dei file',
'listfiles_thumb'       => 'Miniatura',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nome',
'listfiles_user'        => 'Utente',
'listfiles_size'        => 'Dimensione in byte',
'listfiles_description' => 'Descrizione',
'listfiles_count'       => 'Versioni',

# File description page
'file-anchor-link'          => 'File',
'filehist'                  => 'Cronologia del file',
'filehist-help'             => 'Fare clic su un gruppo data/ora per vedere il file come si presentava nel momento indicato.',
'filehist-deleteall'        => 'cancella tutto',
'filehist-deleteone'        => 'cancella',
'filehist-revert'           => 'ripristina',
'filehist-current'          => 'attuale',
'filehist-datetime'         => 'Data/Ora',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura della versione delle $1',
'filehist-nothumb'          => 'Nessuna miniatura',
'filehist-user'             => 'Utente',
'filehist-dimensions'       => 'Dimensioni',
'filehist-filesize'         => 'Dimensione del file',
'filehist-comment'          => 'Oggetto',
'filehist-missing'          => 'File mancante',
'imagelinks'                => 'Uso del file',
'linkstoimage'              => '{{PLURAL:$1|La seguente pagina contiene|Le seguenti $1 pagine contengono}} collegamenti al file:',
'linkstoimage-more'         => 'Più di $1 {{PLURAL:$1|pagina punta|pagine puntano}} a questo file.
Di seguito sono elencate solo {{PLURAL:$1|la prima pagina che punta|le prime $1 pagine che puntano}} a questo file.
È disponibile un [[Special:WhatLinksHere/$2|elenco completo]].',
'nolinkstoimage'            => 'Nessuna pagina contiene collegamenti al file.',
'morelinkstoimage'          => 'Visualizza [[Special:WhatLinksHere/$1|altri link]] a questo file.',
'linkstoimage-redirect'     => '$1 (reindirizzamento file) $2',
'duplicatesoffile'          => '{{PLURAL:$1|Il seguente file è un duplicato|I seguenti $1 file sono duplicati}} di questo file ([[Special:FileDuplicateSearch/$2|ulteriori dettagli]]):',
'sharedupload'              => 'Questo file proviene da $1 e può essere utilizzato da altri progetti.',
'sharedupload-desc-there'   => 'Questo file proviene da $1 e può essere utilizzato da altri progetti.
Consultare la [$2 pagina di descrizione del file] per ulteriori informazioni.',
'sharedupload-desc-here'    => 'Questo file proviene da $1 e può essere utilizzato da altri progetti.
Di seguito viene mostrata la descrizione presente nella [$2 pagina di descrizione del file].',
'filepage-nofile'           => 'Non esiste un file con questo nome.',
'filepage-nofile-link'      => 'Non esiste un file con questo nome, ma è possibile [$1 caricarlo].',
'uploadnewversion-linktext' => 'Carica una nuova versione di questo file',
'shared-repo-from'          => 'da $1',
'shared-repo'               => 'un archivio condiviso',
'filepage.css'              => '/* Il CSS messo qui viene incluso nella pagina di descrizione del file, inclusa anche su wiki client esterni */',

# File reversion
'filerevert'                => 'Ripristina $1',
'filerevert-legend'         => 'Ripristina file',
'filerevert-intro'          => "Si sta per ripristinare il file '''[[Media:$1|$1]]''' alla [$4 versione del $2, $3].",
'filerevert-comment'        => 'Motivo:',
'filerevert-defaultcomment' => 'Ripristinata la versione delle $2, $1',
'filerevert-submit'         => 'Ripristina',
'filerevert-success'        => "'''Il file [[Media:$1|$1]]''' è stato ripristinato alla [$4 versione del $2, $3].",
'filerevert-badversion'     => 'Non esistono versioni locali precedenti del file con il timestamp richiesto.',

# File deletion
'filedelete'                   => 'Cancella $1',
'filedelete-legend'            => 'Cancella il file',
'filedelete-intro'             => "Stai per cancellare il file '''[[Media:$1|$1]]''' con tutta la sua cronologia.",
'filedelete-intro-old'         => "Stai cancellando la versione di '''[[Media:$1|$1]]''' del [$4 $2, $3].",
'filedelete-comment'           => 'Motivo:',
'filedelete-submit'            => 'Cancella',
'filedelete-success'           => "Il file '''$1''' è stato cancellato.",
'filedelete-success-old'       => "La versione del file '''[[Media:$1|$1]]''' del $2, $3  è stata cancellata.",
'filedelete-nofile'            => "Non esiste un file '''$1'''.",
'filedelete-nofile-old'        => "In archivio non ci sono versioni di '''$1''' con le caratteristiche indicate",
'filedelete-otherreason'       => 'Altra motivazione o motivazione aggiuntiva:',
'filedelete-reason-otherlist'  => 'Altra motivazione',
'filedelete-reason-dropdown'   => '*Motivazioni più comuni per la cancellazione
** Violazione di copyright
** File duplicato',
'filedelete-edit-reasonlist'   => 'Modifica le motivazioni per la cancellazione',
'filedelete-maintenance'       => 'Cancellazione e recupero di file temporaneamente disattivati durante la manutenzione.',
'filedelete-maintenance-title' => 'Impossibile eliminare il file',

# MIME search
'mimesearch'         => 'Ricerca in base al tipo MIME',
'mimesearch-summary' => 'Questa pagina consente di filtrare i file in base al tipo MIME. Inserire la stringa di ricerca nella forma tipo/sottotipo, ad es. <code>image/jpeg</code>.',
'mimetype'           => 'Tipo MIME:',
'download'           => 'scarica',

# Unwatched pages
'unwatchedpages' => 'Pagine non osservate',

# List redirects
'listredirects' => 'Elenco di tutti i redirect',

# Unused templates
'unusedtemplates'     => 'Template non utilizzati',
'unusedtemplatestext' => 'In questa pagina vengono elencate le pagine del namespace {{ns:template}} che non sono incluse in nessuna pagina. Prima di cancellarli è opportuno verificare che i singoli template non abbiano altri collegamenti entranti.',
'unusedtemplateswlh'  => 'altri collegamenti',

# Random page
'randompage'         => 'Una pagina a caso',
'randompage-nopages' => 'Non ci sono pagine {{PLURAL:$2|nel seguente namespace|nei seguenti namespace}}: $1.',

# Random redirect
'randomredirect'         => 'Un redirect a caso',
'randomredirect-nopages' => 'Non ci sono redirect nel namespace "$1".',

# Statistics
'statistics'                   => 'Statistiche',
'statistics-header-pages'      => 'Statistiche relative alle pagine',
'statistics-header-edits'      => 'Statistiche relative alle modifiche',
'statistics-header-views'      => 'Statistiche relative alle visualizzazioni',
'statistics-header-users'      => 'Statistiche relative agli utenti',
'statistics-header-hooks'      => 'Altre statistiche',
'statistics-articles'          => 'Pagine di contenuti',
'statistics-pages'             => 'Pagine',
'statistics-pages-desc'        => 'Tutte le pagine del sito, comprese le pagine di discussione, i redirect, ecc.',
'statistics-files'             => 'File caricati',
'statistics-edits'             => "Modifiche a partire dall'installazione di {{SITENAME}}",
'statistics-edits-average'     => 'Media delle modifiche per pagina',
'statistics-views-total'       => 'Visualizzazioni totali',
'statistics-views-total-desc'  => 'Visualizzazioni di pagine inesistenti e pagine speciali non sono incluse',
'statistics-views-peredit'     => 'Visualizzazioni per modifica',
'statistics-users'             => '[[Special:ListUsers|Utenti]] registrati',
'statistics-users-active'      => 'Utenti attivi',
'statistics-users-active-desc' => "Utenti che hanno effettuato un'azione {{PLURAL:$1|nell'ultimo giorno|negli ultimi $1 giorni}}",
'statistics-mostpopular'       => 'Pagine più visitate',

'disambiguations'      => 'Pagine che si collegano a pagine di disambiguazione',
'disambiguationspage'  => 'Template:Disambigua',
'disambiguations-text' => "Le pagine nella lista che segue contengono almeno un collegamento a una '''pagina di disambiguazione'''.
Esse potrebbero dover puntare a una pagina più appropriata.<br />
Vengono considerate pagine di disambiguazione tutte quelle che contengono i template elencati in [[MediaWiki:Disambiguationspage]].",

'doubleredirects'                   => 'Redirect doppi',
'doubleredirectstext'               => 'In questa pagina sono elencate pagine che reindirizzano ad altre pagine di redirect.
Ciascuna riga contiene i collegamenti al primo ed al secondo redirect, oltre alla prima riga di testo del secondo redirect che di solito contiene la pagina di destinazione "corretta" alla quale dovrebbe puntare anche il primo redirect.
I redirect <del>cancellati</del> sono stati corretti.',
'double-redirect-fixed-move'        => '[[$1]] è stata spostata automaticamente, ora è un redirect a [[$2]]',
'double-redirect-fixed-maintenance' => 'Corretto doppio redirect da [[$1]] a [[$2]].',
'double-redirect-fixer'             => 'Correttore di redirect',

'brokenredirects'        => 'Redirect errati',
'brokenredirectstext'    => 'I seguenti redirect puntano a pagine inesistenti:',
'brokenredirects-edit'   => 'modifica',
'brokenredirects-delete' => 'cancella',

'withoutinterwiki'         => 'Pagine prive di interwiki',
'withoutinterwiki-summary' => 'Le pagine indicate di seguito sono prive di collegamenti alle versioni in altre lingue:',
'withoutinterwiki-legend'  => 'Prefisso',
'withoutinterwiki-submit'  => 'Mostra',

'fewestrevisions' => 'Pagine con meno revisioni',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorie}}',
'nlinks'                  => '$1 {{PLURAL:$1|collegamento|collegamenti}}',
'nmembers'                => '$1 {{PLURAL:$1|elemento|elementi}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisione|revisioni}}',
'nviews'                  => '$1 {{PLURAL:$1|visita|visite}}',
'nimagelinks'             => 'Utilizzato su $1 {{PLURAL:$1|pagina|pagine}}',
'ntransclusions'          => 'usato in $1 {{PLURAL:$1|pagina|pagine}}',
'specialpage-empty'       => 'Questa pagina speciale è attualmente vuota.',
'lonelypages'             => 'Pagine orfane',
'lonelypagestext'         => 'Le pagine indicate di seguito sono prive di collegamenti provenienti da altre pagine di {{SITENAME}} e non sono incluse in nessuna pagina del sito.',
'uncategorizedpages'      => 'Pagine prive di categorie',
'uncategorizedcategories' => 'Categorie prive di categorie',
'uncategorizedimages'     => 'File privi di categorie',
'uncategorizedtemplates'  => 'Template privi di categorie',
'unusedcategories'        => 'Categorie vuote',
'unusedimages'            => 'File non utilizzati',
'popularpages'            => 'Pagine più visitate',
'wantedcategories'        => 'Categorie richieste',
'wantedpages'             => 'Pagine più richieste',
'wantedpages-badtitle'    => 'Titolo non valido nel gruppo di risultati: $1',
'wantedfiles'             => 'File richiesti',
'wantedfiletext-cat'      => 'I seguenti file sono richiamati da wikilink, ma non esistono. I file ospitati su repository esterni potrebbero essere elencati anche se di fatto esistenti. Questi falsi positivi saranno <del>barrati</del>. Le pagine che incorporano i file che non esistono sono elencate in [[:$1]].',
'wantedfiletext-nocat'    => 'I seguenti file sono richiamati da wikilink, ma non esistono. I file ospitati su repository esterni potrebbero essere elencati anche se di fatto esistenti. Questi falsi positivi saranno <del>barrati</del>.',
'wantedtemplates'         => 'Template richiesti',
'mostlinked'              => 'Pagine più richiamate',
'mostlinkedcategories'    => 'Categorie più richiamate',
'mostlinkedtemplates'     => 'Template più utilizzati',
'mostcategories'          => 'Pagine con più categorie',
'mostimages'              => 'File più richiamati',
'mostrevisions'           => 'Pagine con più versioni',
'prefixindex'             => 'Indice delle pagine per lettere iniziali',
'prefixindex-namespace'   => 'Tutte le pagine con il prefisso del namespace $1',
'shortpages'              => 'Pagine più corte',
'longpages'               => 'Pagine più lunghe',
'deadendpages'            => 'Pagine senza uscita',
'deadendpagestext'        => 'Le pagine indicate di seguito sono prive di collegamenti verso altre pagine di {{SITENAME}}.',
'protectedpages'          => 'Pagine protette',
'protectedpages-indef'    => 'Solo protezioni infinite',
'protectedpages-cascade'  => 'Solo protezioni ricorsive',
'protectedpagestext'      => 'Di seguito sono elencate le pagine protette, di cui è impedita la modifica o lo spostamento',
'protectedpagesempty'     => 'Al momento non vi sono pagine protette',
'protectedtitles'         => 'Titoli protetti',
'protectedtitlestext'     => 'Non è possibile creare pagine con i titoli elencati di seguito',
'protectedtitlesempty'    => 'Al momento non esistono titoli protetti con i parametri specificati.',
'listusers'               => 'Elenco degli utenti',
'listusers-editsonly'     => 'Mostra solo utenti con dei contributi',
'listusers-creationsort'  => 'Ordina per data di creazione',
'usereditcount'           => '$1 {{PLURAL:$1|contributo|contributi}}',
'usercreated'             => '{{GENDER:$3|Creato/a}} il $1 alle $2',
'newpages'                => 'Pagine più recenti',
'newpages-username'       => 'Nome utente:',
'ancientpages'            => 'Pagine meno recenti',
'move'                    => 'Sposta',
'movethispage'            => 'Sposta questa pagina',
'unusedimagestext'        => 'In questo elenco sono presenti i file caricati e non usati nel sito.
Potrebbero essere presenti immagini che sono usate da altri siti con un collegamento diretto.',
'unusedcategoriestext'    => 'Le pagine delle categorie indicate di seguito sono state create ma non contengono nessuna pagina né sottocategoria.',
'notargettitle'           => 'Dati mancanti',
'notargettext'            => "Non è stata indicata una pagina o un utente in relazione al quale eseguire l'operazione richiesta.",
'nopagetitle'             => 'La pagina di destinazione non esiste',
'nopagetext'              => 'La pagina richiesta non esiste.',
'pager-newer-n'           => '{{PLURAL:$1|1 più recente|$1 più recenti}}',
'pager-older-n'           => '{{PLURAL:$1|1 meno recente|$1 meno recenti}}',
'suppress'                => 'Oversight',
'querypage-disabled'      => 'Questa pagina speciale è disattivata per motivi di prestazioni.',

# Book sources
'booksources'               => 'Fonti librarie',
'booksources-search-legend' => 'Ricerca di fonti librarie',
'booksources-isbn'          => 'Codice ISBN:',
'booksources-go'            => 'Vai',
'booksources-text'          => 'Di seguito sono elencati alcuni collegamenti verso siti esterni che vendono libri nuovi e usati, attraverso i quali è possibile ottenere maggiori informazioni sul testo cercato.',
'booksources-invalid-isbn'  => "L'ISBN inserito sembra non essere valido; verificare che non siano stati commessi errori nel copiarlo dalla fonte originale.",

# Special:Log
'specialloguserlabel'  => 'Azione effettuata da:',
'speciallogtitlelabel' => 'Azione effettuata su:',
'log'                  => 'Log',
'all-logs-page'        => 'Tutti i registri pubblici',
'alllogstext'          => "Presentazione unificata di tutti i registri di {{SITENAME}}.
È possibile restringere i criteri di ricerca selezionando il tipo di registro, l'utente che ha eseguito l'azione, e/o la pagina interessata (entrambi i campi sono sensibili al maiuscolo/minuscolo).",
'logempty'             => 'Il registro non contiene elementi corrispondenti alla ricerca.',
'log-title-wildcard'   => 'Ricerca dei titoli che iniziano con',

# Special:AllPages
'allpages'          => 'Tutte le pagine',
'alphaindexline'    => 'da $1 a $2',
'nextpage'          => 'Pagina successiva ($1)',
'prevpage'          => 'Pagina precedente ($1)',
'allpagesfrom'      => 'Mostra le pagine a partire da:',
'allpagesto'        => 'Mostra le pagine fino a:',
'allarticles'       => 'Tutte le pagine',
'allinnamespace'    => 'Tutte le pagine del namespace $1',
'allnotinnamespace' => 'Tutte le pagine, escluso il namespace $1',
'allpagesprev'      => 'Precedenti',
'allpagesnext'      => 'Successive',
'allpagessubmit'    => 'Vai',
'allpagesprefix'    => 'Mostra le pagine che iniziano con:',
'allpagesbadtitle'  => 'Il titolo indicato per la pagina non è valido o contiene prefissi interlingua o interwiki. Potrebbe inoltre contenere uno o più caratteri il cui uso non è ammesso nei titoli.',
'allpages-bad-ns'   => 'Il namespace "$1" non esiste su {{SITENAME}}.',

# Special:Categories
'categories'                    => 'Categorie',
'categoriespagetext'            => '{{PLURAL:$1|La categoria indicata di seguito contiene|Le categorie indicate di seguito contengono}} pagine o file multimediali.
Le [[Special:UnusedCategories|categorie vuote]] non sono mostrate qui.
Vedi anche le [[Special:WantedCategories|categorie richieste]].',
'categoriesfrom'                => 'Mostra le categorie a partire da:',
'special-categories-sort-count' => 'ordina per numero',
'special-categories-sort-abc'   => 'ordina alfabeticamente',

# Special:DeletedContributions
'deletedcontributions'             => 'Contributi utente cancellati',
'deletedcontributions-title'       => 'Contributi utente cancellati',
'sp-deletedcontributions-contribs' => 'contributi',

# Special:LinkSearch
'linksearch'       => 'Ricerca collegamenti esterni',
'linksearch-pat'   => 'Pattern di ricerca:',
'linksearch-ns'    => 'Namespace:',
'linksearch-ok'    => 'Cerca',
'linksearch-text'  => 'È possibile fare uso di metacaratteri, ad esempio "*.wikipedia.org".<br />
È necessario almeno un dominio di primo livello, ad esempio "*.org".<br />
Protocolli supportati: <code>$1</code> (predefinito http:// se nessun protocollo è specificato).',
'linksearch-line'  => '$1 presente nella pagina $2',
'linksearch-error' => "I metacaratteri possono essere usati solo all'inizio dell'indirizzo.",

# Special:ListUsers
'listusersfrom'      => 'Mostra gli utenti a partire da:',
'listusers-submit'   => 'Mostra',
'listusers-noresult' => 'Nessun utente risponde ai criteri impostati.',
'listusers-blocked'  => '(bloccato)',

# Special:ActiveUsers
'activeusers'            => 'Elenco degli utenti attivi',
'activeusers-intro'      => 'Questo è un elenco di utenti che hanno avuto qualche tipo di attività da $1 {{PLURAL:$1|giorno|giorni}} a questa parte.',
'activeusers-count'      => "$1 {{PLURAL:$1|modifica|modifiche}} {{PLURAL:$3|nell'ultimo giorno|negli ultimi $3 giorni}}",
'activeusers-from'       => 'Mostra gli utenti a partire da:',
'activeusers-hidebots'   => 'Nascondi i bot',
'activeusers-hidesysops' => 'Nascondi gli amministratori',
'activeusers-noresult'   => 'Nessun utente risponde ai criteri impostati.',

# Special:Log/newusers
'newuserlogpage'     => 'Nuovi utenti',
'newuserlogpagetext' => 'Di seguito sono elencate le utenze di nuova creazione.',

# Special:ListGroupRights
'listgrouprights'                      => 'Diritti del gruppo utente',
'listgrouprights-summary'              => "Di seguito sono elencati i gruppi utente definiti per questo sito, con i diritti d'accesso loro associati.
Potrebbero esserci [[{{MediaWiki:Listgrouprights-helppage}}|ulteriori informazioni]] sui diritti individuali.",
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Diritto assegnato</span>
* <span class="listgrouprights-revoked">Diritto revocato</span>',
'listgrouprights-group'                => 'Gruppo',
'listgrouprights-rights'               => 'Diritti',
'listgrouprights-helppage'             => 'Help:Diritti del gruppo',
'listgrouprights-members'              => '(Elenco dei membri)',
'listgrouprights-addgroup'             => 'Può aggiungere {{PLURAL:$2|al gruppo|ai gruppi}}: $1',
'listgrouprights-removegroup'          => 'Può rimuovere {{PLURAL:$2|dal gruppo|dai gruppi}}: $1',
'listgrouprights-addgroup-all'         => 'Può aggiungere a tutti i gruppi',
'listgrouprights-removegroup-all'      => 'Può rimuovere da tutti i gruppi',
'listgrouprights-addgroup-self'        => 'Può aggiungersi {{PLURAL:$2|al gruppo|ai gruppi}}: $1',
'listgrouprights-removegroup-self'     => 'Può rimuoversi {{PLURAL:$2|dal gruppo|dai gruppi}}: $1',
'listgrouprights-addgroup-self-all'    => 'Può aggiungersi a tutti i gruppi',
'listgrouprights-removegroup-self-all' => 'Può rimuoversi da tutti i gruppi',

# E-mail user
'mailnologin'          => 'Nessun indirizzo cui inviare il messaggio',
'mailnologintext'      => 'Per inviare messaggi e-mail ad altri utenti è necessario [[Special:UserLogin|accedere al sito]] e aver registrato un indirizzo valido nelle proprie [[Special:Preferences|preferenze]].',
'emailuser'            => "Scrivi all'utente",
'emailpage'            => "Invia un messaggio e-mail all'utente",
'emailpagetext'        => 'Usare il modulo sottostante per inviare un messaggio e-mail all\'{{GENDER:$1|utente}} indicato. L\'indirizzo specificato nelle [[Special:Preferences|preferenze]] del mittente apparirà nel campo "Da:" del messaggio per consentire al destinatario di rispondere direttamente.',
'usermailererror'      => "L'oggetto mail ha restituito l'errore:",
'defemailsubject'      => 'Messaggio da {{SITENAME}} dall\'utente "$1"',
'usermaildisabled'     => 'e-mail utente disabilitata',
'usermaildisabledtext' => 'Non è possibile inviare e-mail ad altri utenti su questo wiki',
'noemailtitle'         => 'Nessun indirizzo e-mail',
'noemailtext'          => 'Questo utente non ha indicato un indirizzo e-mail valido.',
'nowikiemailtitle'     => 'E-mail non permessa',
'nowikiemailtext'      => 'Questo utente ha scelto di non ricevere messaggi di posta elettronica dagli altri utenti.',
'emailnotarget'        => 'Nome utente del destinatario inesistente o non valido.',
'emailtarget'          => 'Inserisci il nome utente del destinatario',
'emailusername'        => 'Nome utente:',
'emailusernamesubmit'  => 'Invia',
'email-legend'         => 'Invia un messaggio e-mail a un altro utente di {{SITENAME}}',
'emailfrom'            => 'Da:',
'emailto'              => 'A:',
'emailsubject'         => 'Oggetto:',
'emailmessage'         => 'Messaggio:',
'emailsend'            => 'Invia',
'emailccme'            => 'Invia in copia al mio indirizzo.',
'emailccsubject'       => 'Copia del messaggio inviato a $1: $2',
'emailsent'            => 'Messaggio inviato',
'emailsenttext'        => 'Il messaggio e-mail è stato inviato.',
'emailuserfooter'      => 'Questa e-mail è stata inviata da $1 a $2 attraverso la funzione "Invia un messaggio e-mail all\'utente" su {{SITENAME}}.',

# User Messenger
'usermessage-summary'  => 'Messaggio di sistema',
'usermessage-editor'   => 'Messaggero di sistema',
'usermessage-template' => 'MediaWiki:MessaggioUtente',

# Watchlist
'watchlist'            => 'Osservati speciali',
'mywatchlist'          => 'osservati speciali',
'watchlistfor2'        => "Dell'utente $1 $2",
'nowatchlist'          => 'La lista degli osservati speciali è vuota.',
'watchlistanontext'    => "Per visualizzare e modificare l'elenco degli osservati speciali è necessario $1.",
'watchnologin'         => 'Accesso non effettuato',
'watchnologintext'     => "Per modificare la lista degli osservati speciali è necessario prima eseguire l'[[Special:UserLogin|accesso al sito]].",
'addwatch'             => 'Aggiungi agli osservati speciali',
'addedwatchtext'       => "La pagina \"[[:\$1]]\" è stata aggiunta alla propria [[Special:Watchlist|lista degli osservati speciali]].
D'ora in poi, le modifiche apportate alla pagina e alla sua discussione verranno elencate in quella sede;
il titolo della pagina apparirà in '''grassetto''' nella pagina delle [[Special:RecentChanges|ultime modifiche]] per renderlo più visibile.",
'removewatch'          => 'Rimuovi dagli osservati speciali',
'removedwatchtext'     => 'La pagina "[[:$1]]" è stata eliminata dalla [[Special:Watchlist|lista degli osservati speciali]].',
'watch'                => 'Segui',
'watchthispage'        => 'Segui questa pagina',
'unwatch'              => 'Non seguire',
'unwatchthispage'      => 'Smetti di seguire',
'notanarticle'         => 'Questa pagina non è una voce',
'notvisiblerev'        => 'La revisione è stata cancellata',
'watchnochange'        => 'Nessuna delle pagine osservate è stata modificata nel periodo selezionato.',
'watchlist-details'    => 'La lista degli osservati speciali contiene {{PLURAL:$1|una pagina (e la rispettiva pagina di discussione)|$1 pagine (e le rispettive pagine di discussione)}}.',
'wlheader-enotif'      => '* La notifica via e-mail è attiva.',
'wlheader-showupdated' => "* Le pagine che sono state modificate dopo l'ultima visita sono evidenziate in '''grassetto'''.",
'watchmethod-recent'   => 'controllo delle modifiche recenti per gli osservati speciali',
'watchmethod-list'     => 'controllo degli osservati speciali per modifiche recenti',
'watchlistcontains'    => 'La lista degli osservati speciali contiene {{PLURAL:$1|una pagina|$1 pagine}}.',
'iteminvalidname'      => "Problemi con la pagina '$1', nome non valido...",
'wlnote'               => "Di seguito {{PLURAL:$1|è elencata la modifica più recente apportata|sono elencate le '''$1''' modifiche più recenti apportate}} {{PLURAL:$2|nella scorsa ora|nelle scorse '''$2''' ore}}; i dati sono aggiornati alle $4 del $3.",
'wlshowlast'           => 'Mostra le ultime $1 ore $2 giorni $3',
'watchlist-options'    => 'Opzioni osservati speciali',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'Aggiunta agli osservati speciali...',
'unwatching'     => 'Eliminazione dagli osservati speciali...',
'watcherrortext' => 'Si è verificato un errore durante la modifica degli osservati speciali per "$1".',

'enotif_mailer'                => 'Sistema di notifica via e-mail di {{SITENAME}}',
'enotif_reset'                 => 'Segna tutte le pagine come già visitate',
'enotif_newpagetext'           => 'Questa è una nuova pagina.',
'enotif_impersonal_salutation' => 'Utente di {{SITENAME}}',
'changed'                      => 'modificata',
'created'                      => 'creata',
'enotif_subject'               => 'La pagina $PAGETITLE di {{SITENAME}} è stata $CHANGEDORCREATED da $PAGEEDITOR',
'enotif_lastvisited'           => "Consultare $1 per vedere tutte le modifiche dall'ultima visita.",
'enotif_lastdiff'              => 'Vedere $1 per visualizzare la modifica.',
'enotif_anon_editor'           => 'utente anonimo $1',
'enotif_body'                  => 'Gentile $WATCHINGUSERNAME,

la pagina $PAGETITLE di {{SITENAME}} è stata $CHANGEDORCREATED in data $PAGEEDITDATE da $PAGEEDITOR; la versione attuale si trova all\'indirizzo $PAGETITLE_URL.

$NEWPAGE

Oggetto della modifica, inserito dall\'autore: $PAGESUMMARY $PAGEMINOREDIT

Contatta l\'autore della modifica:
via e-mail: $PAGEEDITOR_EMAIL
sul sito: $PAGEEDITOR_WIKI

Non verranno inviate altre notifiche in caso di ulteriori cambiamenti, a meno che tu non visiti la pagina. Inoltre, è possibile reimpostare l\'avviso di notifica per tutte le pagine nella lista degli osservati speciali.

             Il sistema di notifica di {{SITENAME}}, al tuo servizio

--
Per modificare le impostazioni delle notifiche via e-mail, visita 
{{canonicalurl:{{#special:Preferences}}}}

Per modificare la lista degli osservati speciali, visita 
{{canonicalurl:{{#special:EditWatchlist}}}}

Per rimuovere la pagina dalla lista degli osservati speciali, visita
$UNWATCHURL

Per commentare e ricevere aiuto:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Cancella pagina',
'confirm'                => 'Conferma',
'excontent'              => "il contenuto era: '$1'",
'excontentauthor'        => "il contenuto era: '$1' (e l'unico contributore era '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "Il contenuto prima dello svuotamento era: '$1'",
'exblank'                => 'la pagina era vuota',
'delete-confirm'         => 'Cancella "$1"',
'delete-legend'          => 'Cancella',
'historywarning'         => "'''Attenzione:''' La pagina che stai per cancellare ha una cronologia con approssimativamente $1 {{PLURAL:$1|revisione|revisioni}}:",
'confirmdeletetext'      => 'Stai per cancellare una pagina con tutta la sua cronologia. Per cortesia, conferma che è tua intenzione procedere a tale cancellazione, che hai piena consapevolezza delle conseguenze della tua azione e che essa è conforme alle linee guida stabilite in [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'         => 'Azione completata',
'actionfailed'           => 'Azione fallita',
'deletedtext'            => 'La pagina "$1" è stata cancellata.
Consultare il log delle $2 per un elenco delle pagine cancellate di recente.',
'dellogpage'             => 'Cancellazioni',
'dellogpagetext'         => 'Di seguito sono elencate le pagine cancellate di recente.',
'deletionlog'            => 'cancellazioni',
'reverted'               => 'Ripristinata la versione precedente',
'deletecomment'          => 'Motivo:',
'deleteotherreason'      => 'Altra motivazione o motivazione aggiuntiva:',
'deletereasonotherlist'  => 'Altra motivazione',
'deletereason-dropdown'  => "*Motivazioni più comuni per la cancellazione
** Richiesta dell'autore
** Violazione di copyright
** Vandalismo",
'delete-edit-reasonlist' => 'Modifica i motivi di cancellazione',
'delete-toobig'          => 'La cronologia di questa pagina è molto lunga (oltre $1 {{PLURAL:$1|revisione|revisioni}}). La sua cancellazione è stata limitata per evitare di creare accidentalmente dei problemi di funzionamento al database di {{SITENAME}}.',
'delete-warning-toobig'  => 'La cronologia di questa pagina è molto lunga (oltre $1 {{PLURAL:$1|revisione|revisioni}}). La sua cancellazione può creare dei problemi di funzionamento al database di {{SITENAME}}; procedere con cautela.',

# Rollback
'rollback'          => 'Annulla le modifiche',
'rollback_short'    => 'Rollback',
'rollbacklink'      => 'rollback',
'rollbackfailed'    => 'Rollback fallito',
'cantrollback'      => "Impossibile annullare le modifiche; l'utente che le ha effettuate è l'unico ad aver contribuito alla pagina.",
'alreadyrolled'     => 'Non è possibile annullare le modifiche apportate alla pagina [[:$1]] da parte di [[User:$2|$2]] ([[User talk:$2|discussione]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); un altro utente ha già modificato la pagina oppure ha effettuato il rollback.

La modifica più recente alla pagina è stata apportata da [[User:$3|$3]] ([[User talk:$3|discussione]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "L'oggetto della modifica era: \"''\$1''\".",
'revertpage'        => 'Annullate le modifiche di [[Special:Contributions/$2|$2]] ([[User talk:$2|discussione]]), riportata alla versione precedente di [[User:$1|$1]]',
'revertpage-nouser' => 'Annullate le modifiche di (nome utente rimosso), riportata alla versione precedente di [[User:$1|$1]]',
'rollback-success'  => "Annullate le modifiche di $1; pagina riportata all'ultima versione di $2.",

# Edit tokens
'sessionfailure-title' => 'Sessione fallita',
'sessionfailure'       => "Si è verificato un problema nella sessione che identifica l'accesso; il sistema non ha eseguito il comando impartito per precauzione. Tornare alla pagina precedente con il tasto 'Indietro' del proprio browser, ricaricare la pagina e riprovare.",

# Protect
'protectlogpage'              => 'Protezioni',
'protectlogtext'              => "Di seguito sono elencate le modifiche alle protezioni delle pagine.
Vedi la [[Special:ProtectedPages|lista delle pagine protette]] per l'elenco delle protezioni di pagina attualmente attive.",
'protectedarticle'            => 'ha protetto "[[$1]]"',
'modifiedarticleprotection'   => 'ha modificato il livello di protezione di "[[$1]]"',
'unprotectedarticle'          => 'ha sprotetto "[[$1]]"',
'movedarticleprotection'      => 'ha spostato la protezione da "[[$2]]" a "[[$1]]"',
'protect-title'               => 'Modifica del livello di protezione per "$1"',
'protect-title-notallowed'    => 'Visualizza il livello di protezione di " $1 "',
'prot_1movedto2'              => 'ha spostato [[$1]] a [[$2]]',
'protect-badnamespace-title'  => 'Namespace non suscettibile di protezione',
'protect-badnamespace-text'   => 'Le pagine di questo namespace non possono essere protette.',
'protect-legend'              => 'Conferma la protezione',
'protectcomment'              => 'Motivo:',
'protectexpiry'               => 'Scadenza:',
'protect_expiry_invalid'      => 'Scadenza non valida.',
'protect_expiry_old'          => 'Scadenza già trascorsa.',
'protect-unchain-permissions' => 'Sblocca ulteriori opzioni di protezione',
'protect-text'                => "Questo modulo consente di vedere e modificare il livello di protezione per la pagina '''$1'''.",
'protect-locked-blocked'      => "Non è possibile modificare i livelli di protezione quando è attivo un blocco. Le impostazioni correnti per la pagina sono '''$1''':",
'protect-locked-dblock'       => "Impossibile modificare i livelli di protezione durante un blocco del database.
Le impostazioni correnti per la pagina sono '''$1''':",
'protect-locked-access'       => "Non si dispone dei permessi necessari per modificare i livelli di protezione della pagina.
Le impostazioni correnti per la pagina sono '''$1''':",
'protect-cascadeon'           => 'Al momento questa pagina è bloccata perché viene inclusa {{PLURAL:$1|nella pagina indicata di seguito, per la quale|nelle pagine indicate di seguito, per le quali}} è attiva la protezione ricorsiva. È possibile modificare il livello di protezione individuale della pagina, ma le impostazioni derivanti dalla protezione ricorsiva non saranno modificate.',
'protect-default'             => 'Autorizza tutti gli utenti',
'protect-fallback'            => 'È richiesto il permesso "$1"',
'protect-level-autoconfirmed' => 'Blocca gli utenti nuovi e i non registrati',
'protect-level-sysop'         => 'Solo amministratori',
'protect-summary-cascade'     => 'ricorsiva',
'protect-expiring'            => 'scadenza: $1 (UTC)',
'protect-expiring-local'      => 'scade il $1',
'protect-expiry-indefinite'   => 'infinito',
'protect-cascade'             => 'Protezione ricorsiva (estende la protezione a tutte le pagine incluse in questa).',
'protect-cantedit'            => 'Non è possibile modificare i livelli di protezione per la pagina in quanto non si dispone dei permessi necessari per modificare la pagina stessa.',
'protect-othertime'           => 'Durata non in elenco:',
'protect-othertime-op'        => 'durata non in elenco',
'protect-existing-expiry'     => 'Scadenza attuale: $2, $3',
'protect-otherreason'         => 'Altri motivi/dettagli:',
'protect-otherreason-op'      => 'Altra motivazione',
'protect-dropdown'            => '*Motivi comuni di protezione
** Reiterati vandalismi
** Reiterati inserimenti di spam
** Edit war
** Pagina molto usata',
'protect-edit-reasonlist'     => 'Modifica i motivi per la protezione',
'protect-expiry-options'      => '1 ora:1 hour,1 giorno:1 day,1 settimana:1 week,2 settimane:2 weeks,1 mese:1 month,3 mesi:3 months,6 mesi:6 months,1 anno:1 year,infinito:infinite',
'restriction-type'            => 'Permesso',
'restriction-level'           => 'Livello di restrizione',
'minimum-size'                => 'Dimensione minima',
'maximum-size'                => 'Dimensione massima:',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Modifica',
'restriction-move'   => 'Spostamento',
'restriction-create' => 'Creazione',
'restriction-upload' => 'Carica',

# Restriction levels
'restriction-level-sysop'         => 'protetta',
'restriction-level-autoconfirmed' => 'semi-protetta',
'restriction-level-all'           => 'tutti i livelli',

# Undelete
'undelete'                     => 'Visualizza pagine cancellate',
'undeletepage'                 => 'Visualizza e recupera le pagine cancellate',
'undeletepagetitle'            => "'''Quanto segue è composto da revisioni cancellate di [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Visualizza le pagine cancellate',
'undeletepagetext'             => "{{PLURAL:$1|La pagina indicata di seguito è stata cancellata, ma è ancora in archivio e pertanto può essere recuperata|Le pagine indicate di seguito sono state cancellate, ma sono ancora in archivio e pertanto possono essere recuperate}}. L'archivio può essere svuotato periodicamente.",
'undelete-fieldset-title'      => 'Ripristina versioni',
'undeleteextrahelp'            => "Per recuperare l'intera cronologia della pagina, lasciare tutte le caselle deselezionate e fare clic su '''''{{int:undeletebtn}}'''''.
Per effettuare un ripristino selettivo, selezionare le caselle corrispondenti alle revisioni da ripristinare e fare clic su '''''{{int:undeletebtn}}'''''.",
'undeleterevisions'            => '{{PLURAL:$1|Una revisione|$1 revisioni}} in archivio',
'undeletehistory'              => 'Recuperando questa pagina, tutte le sue revisioni verranno ripristinate nella relativa cronologia. Se dopo la cancellazione è stata creata una nuova pagina con lo stesso titolo, le revisioni recuperate saranno inserite nella cronologia precedente.',
'undeleterevdel'               => "Il ripristino non verrà effettuato se determina la cancellazione parziale della versione attuale della pagina o del file interessato. In tal caso, è necessario rimuovere il segno di spunta o l'oscuramento dalle revisioni cancellate più recenti.",
'undeletehistorynoadmin'       => "Questa pagina è stata cancellata.
Il motivo della cancellazione è mostrato qui sotto, assieme ai dettagli dell'utente che ha modificato questa pagina prima della cancellazione.
Il testo contenuto nelle revisioni cancellate è disponibile solo agli amministratori.",
'undelete-revision'            => 'Revisione cancellata della pagina $1, inserita il $4 alle $5 da $3:',
'undeleterevision-missing'     => "Revisione errata o mancante. Il collegamento è errato oppure la revisione è stata già ripristinata o eliminata dall'archivio.",
'undelete-nodiff'              => 'Non è stata trovata nessuna revisione precedente.',
'undeletebtn'                  => 'Ripristina',
'undeletelink'                 => 'visualizza/ripristina',
'undeleteviewlink'             => 'visualizza',
'undeletereset'                => 'Reimposta',
'undeleteinvert'               => 'Inverti selezione',
'undeletecomment'              => 'Motivo:',
'undeletedrevisions'           => '{{PLURAL:$1|Una revisione recuperata|$1 revisioni recuperate}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|Una revisione|$1 revisioni}} e $2 file recuperati',
'undeletedfiles'               => '{{PLURAL:$1|Un file recuperato|$1 file recuperati}}',
'cannotundelete'               => 'Ripristino non riuscito; è possibile che la pagina sia già stata recuperata da un altro utente.',
'undeletedpage'                => "'''La pagina $1 è stata recuperata'''

Consultare il [[Special:Log/delete|log delle cancellazioni]] per vedere le cancellazioni e i recuperi più recenti.",
'undelete-header'              => 'Consultare il [[Special:Log/delete|log delle cancellazioni]] per vedere le cancellazioni più recenti.',
'undelete-search-title'        => 'Ricerca nelle pagine cancellate',
'undelete-search-box'          => 'Ricerca nelle pagine cancellate',
'undelete-search-prefix'       => 'Mostra le pagine il cui titolo inizia con:',
'undelete-search-submit'       => 'Cerca',
'undelete-no-results'          => "Nessuna pagina corrispondente nell'archivio delle cancellazioni.",
'undelete-filename-mismatch'   => 'Impossibile annullare la cancellazione della revisione del file con timestamp $1: nome file non corrispondente.',
'undelete-bad-store-key'       => 'Impossibile annullare la cancellazione della revisione del file con timestamp $1: file non disponibile prima della cancellazione.',
'undelete-cleanup-error'       => 'Errore nella cancellazione del file di archivio non utilizzato "$1".',
'undelete-missing-filearchive' => "Impossibile ripristinare l'ID $1 dell'archivio file in quanto non è presente nel database. Potrebbe essere stato già ripristinato.",
'undelete-error'               => 'Errore nel ripristino della pagina',
'undelete-error-short'         => 'Errore nel ripristino del file: $1',
'undelete-error-long'          => 'Si sono verificati degli errori nel tentativo di annullare la cancellazione del file:

$1',
'undelete-show-file-confirm'   => 'Si desidera visualizzare la versione cancellata del file "<nowiki>$1</nowiki>" del $2 alle $3?',
'undelete-show-file-submit'    => 'Sì',

# Namespace form on various pages
'namespace'                     => 'Namespace:',
'invert'                        => 'inverti la selezione',
'tooltip-invert'                => "Seleziona questa casella per nascondere le modifiche alle pagine all'interno del namespace selezionato (ed il relativo namespace, se selezionato)",
'namespace_association'         => 'Namespace associato',
'tooltip-namespace_association' => "Seleziona questa casella per includere anche la pagina di discussione o l'oggetto del namespace associato con il namespace selezionato",
'blanknamespace'                => '(Principale)',

# Contributions
'contributions'       => 'Contributi utente',
'contributions-title' => 'Contributi di $1',
'mycontris'           => 'contributi',
'contribsub2'         => 'Per $1 ($2)',
'nocontribs'          => 'Non sono state trovate modifiche che soddisfino i criteri di ricerca.',
'uctop'               => '(ultima per la pagina)',
'month'               => 'Dal mese (e precedenti):',
'year'                => "Dall'anno (e precedenti):",

'sp-contributions-newbies'             => 'Mostra solo i contributi dei nuovi utenti',
'sp-contributions-newbies-sub'         => 'Per i nuovi utenti',
'sp-contributions-newbies-title'       => 'Contributi dei nuovi utenti',
'sp-contributions-blocklog'            => 'blocchi',
'sp-contributions-deleted'             => 'contributi utente cancellati',
'sp-contributions-uploads'             => 'file caricati',
'sp-contributions-logs'                => 'registri',
'sp-contributions-talk'                => 'discussione',
'sp-contributions-userrights'          => 'gestione dei permessi',
'sp-contributions-blocked-notice'      => "Questo utente è attualmente bloccato. L'ultimo elemento del registro dei blocchi è riportato di seguito per informazione:",
'sp-contributions-blocked-notice-anon' => "Questo indirizzo IP è attualmente bloccato. Di seguito è riportato l'ultimo elemento del registro dei blocchi:",
'sp-contributions-search'              => 'Ricerca contributi',
'sp-contributions-username'            => 'Indirizzo IP o nome utente:',
'sp-contributions-toponly'             => 'Mostra solo i contributi che sono le ultime revisioni per la pagina',
'sp-contributions-submit'              => 'Ricerca',

# What links here
'whatlinkshere'            => 'Puntano qui',
'whatlinkshere-title'      => 'Pagine che puntano a "$1"',
'whatlinkshere-page'       => 'Pagina:',
'linkshere'                => "Le seguenti pagine contengono dei collegamenti a '''[[:$1]]''':",
'nolinkshere'              => "Nessuna pagina contiene collegamenti che puntano a '''[[:$1]]'''.",
'nolinkshere-ns'           => "Non vi sono pagine che puntano a '''[[:$1]]''' nel namespace selezionato.",
'isredirect'               => 'redirect',
'istemplate'               => 'inclusione',
'isimage'                  => 'link verso file',
'whatlinkshere-prev'       => '{{PLURAL:$1|precedente|precedenti $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|successivo|successivi $1}}',
'whatlinkshere-links'      => '← collegamenti',
'whatlinkshere-hideredirs' => '$1 redirect',
'whatlinkshere-hidetrans'  => '$1 inclusioni',
'whatlinkshere-hidelinks'  => '$1 link',
'whatlinkshere-hideimages' => '$1 link da file',
'whatlinkshere-filters'    => 'Filtri',

# Block/unblock
'autoblockid'                     => 'Autoblocco #$1',
'block'                           => 'Blocca utente',
'unblock'                         => 'Sblocca utente',
'blockip'                         => 'Blocco utente',
'blockip-title'                   => 'Blocca utente',
'blockip-legend'                  => "Blocca l'utente",
'blockiptext'                     => "Usare il modulo sottostante per bloccare l'accesso in scrittura a uno specifico indirizzo IP o un utente registrato.
Il blocco dev'essere operato per prevenire atti di vandalismo e in stretta osservanza della [[{{MediaWiki:Policy-url}}|policy di {{SITENAME}}]].
Indicare il motivo specifico per il quale si procede al blocco (per esempio, citando i titoli di eventuali pagine oggetto di vandalismo).",
'ipadressorusername'              => 'Indirizzo IP o nome utente:',
'ipbexpiry'                       => 'Scadenza del blocco:',
'ipbreason'                       => 'Motivo:',
'ipbreasonotherlist'              => 'Altra motivazione',
'ipbreason-dropdown'              => '*Motivazioni più comuni per i blocchi
** Inserimento di informazioni false
** Rimozione di contenuti dalle pagine
** Collegamenti promozionali a siti esterni
** Inserimento di contenuti privi di senso
** Comportamenti intimidatori o molestie
** Uso indebito di più account
** Nome utente non consono',
'ipb-hardblock'                   => 'Impedisci agli utenti registrati di contribuire da questo indirizzo IP',
'ipbcreateaccount'                => 'Impedisci la creazione di altri account',
'ipbemailban'                     => "Impedisci all'utente l'invio di e-mail",
'ipbenableautoblock'              => "Blocca automaticamente l'ultimo indirizzo IP usato dall'utente e i successivi con cui vengono tentate modifiche",
'ipbsubmit'                       => "Blocca l'utente",
'ipbother'                        => 'Durata non in elenco:',
'ipboptions'                      => '2 ore:2 hours,1 giorno:1 day,3 giorni:3 days,1 settimana:1 week,2 settimane:2 weeks,1 mese:1 month,3 mesi:3 months,6 mesi:6 months,1 anno:1 year,infinito:infinite',
'ipbotheroption'                  => 'altro',
'ipbotherreason'                  => 'Altri motivi/dettagli:',
'ipbhidename'                     => 'Nascondi il nome utente dalle modifiche e dagli elenchi.',
'ipbwatchuser'                    => 'Segui le pagine e le discussioni utente di questo utente',
'ipb-disableusertalk'             => 'Impedisci a questo utente di modificare la propria pagina di discussioni mentre è bloccato',
'ipb-change-block'                => "Ri-blocca l'utente con queste impostazioni",
'ipb-confirm'                     => 'Conferma il blocco',
'badipaddress'                    => 'Indirizzo IP non valido.',
'blockipsuccesssub'               => 'Blocco eseguito',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] è stato bloccato.<br />
Consultare la [[Special:BlockList|lista dei blocchi]] per vedere i blocchi attivi.',
'ipb-blockingself'                => 'Stai per bloccare te stesso! Sei sicuro di volerlo fare?',
'ipb-confirmhideuser'             => 'Si sta per bloccare un utente con l\'opzione "Nascondi utente" abilitata.
In questo modo si evita che il nome utente compaia in tutte le liste e le voci di registro.
Sei sicuro di voler continuare?',
'ipb-edit-dropdown'               => 'Modifica i motivi per il blocco',
'ipb-unblock-addr'                => 'Sblocca $1',
'ipb-unblock'                     => 'Sblocca un utente o un indirizzo IP',
'ipb-blocklist'                   => 'Elenca i blocchi attivi',
'ipb-blocklist-contribs'          => 'Contributi di $1',
'unblockip'                       => "Sblocca l'utente",
'unblockiptext'                   => "Usare il modulo sottostante per restituire l'accesso in scrittura ad un utente o indirizzo IP bloccato.",
'ipusubmit'                       => 'Rimuovi questo blocco',
'unblocked'                       => "L'utente [[User:$1|$1]] è stato sbloccato",
'unblocked-range'                 => '$1 è stato sbloccato',
'unblocked-id'                    => 'Il blocco $1 è stato rimosso',
'blocklist'                       => 'Utenti bloccati',
'ipblocklist'                     => 'Utenti bloccati',
'ipblocklist-legend'              => 'Trova un utente bloccato',
'blocklist-userblocks'            => 'Nascondi i blocchi degli account',
'blocklist-tempblocks'            => 'Nascondi i blocchi temporanei',
'blocklist-addressblocks'         => 'Nascondi i blocchi di un solo IP',
'blocklist-rangeblocks'           => 'Nascondi i blocchi di range',
'blocklist-timestamp'             => 'Data e ora',
'blocklist-target'                => 'Destinazione',
'blocklist-expiry'                => 'Scade',
'blocklist-by'                    => 'Amministratore che ha bloccato',
'blocklist-params'                => 'Parametri di blocco',
'blocklist-reason'                => 'Motivo',
'ipblocklist-submit'              => 'Ricerca',
'ipblocklist-localblock'          => 'Blocchi locali',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Altro blocco|Altri blocchi}}',
'infiniteblock'                   => 'infinito',
'expiringblock'                   => 'scade il $1 alle $2',
'anononlyblock'                   => 'solo anonimi',
'noautoblockblock'                => 'blocco automatico disabilitato',
'createaccountblock'              => 'creazione account bloccata',
'emailblock'                      => 'e-mail bloccate',
'blocklist-nousertalk'            => 'non può modificare la propria pagina di discussione',
'ipblocklist-empty'               => "L'elenco dei blocchi è vuoto.",
'ipblocklist-no-results'          => "L'indirizzo IP o nome utente richiesto non è bloccato.",
'blocklink'                       => 'blocca',
'unblocklink'                     => 'sblocca',
'change-blocklink'                => 'cambia blocco',
'contribslink'                    => 'contributi',
'emaillink'                       => 'invia email',
'autoblocker'                     => 'Bloccato automaticamente perché l\'indirizzo IP è condiviso con l\'utente "[[User:$1|$1]]".
Il blocco dell\'utente $1 è stato imposto per il seguente motivo: "$2".',
'blocklogpage'                    => 'Blocchi',
'blocklog-showlog'                => 'Questo utente è stato bloccato in precedenza. Il registro dei blocchi è riportato di seguito per informazione:',
'blocklog-showsuppresslog'        => 'Questo utente è stato bloccato e nascosto in precedenza. Il registro delle rimozioni è riportato di seguito per informazione:',
'blocklogentry'                   => 'ha bloccato [[$1]] per un periodo di $2 $3',
'reblock-logentry'                => 'ha cambiato le impostazioni del blocco per [[$1]] con una scadenza di $2 $3',
'blocklogtext'                    => "Di seguito sono elencate le azioni di blocco e sblocco utenti.
Gli indirizzi IP bloccati automaticamente non sono elencati.
Consultare l'[[Special:BlockList|elenco dei blocchi]] per l'elenco dei bandi o blocchi attualmente operativi.",
'unblocklogentry'                 => 'ha sbloccato $1',
'block-log-flags-anononly'        => 'solo utenti anonimi',
'block-log-flags-nocreate'        => 'creazione account bloccata',
'block-log-flags-noautoblock'     => 'blocco automatico disattivato',
'block-log-flags-noemail'         => 'e-mail bloccate',
'block-log-flags-nousertalk'      => 'non può modificare la propria pagina di discussione',
'block-log-flags-angry-autoblock' => 'blocco automatico avanzato attivo',
'block-log-flags-hiddenname'      => 'nome utente nascosto',
'range_block_disabled'            => 'La possibilità di bloccare intervalli di indirizzi IP non è attiva al momento.',
'ipb_expiry_invalid'              => 'Durata o scadenza del blocco non valida.',
'ipb_expiry_temp'                 => 'I blocchi dei nomi utenti nascosti dovrebbero essere infiniti',
'ipb_hide_invalid'                => "Impossibile cancellare l'account; potrebbe avere troppe modifiche.",
'ipb_already_blocked'             => 'L\'utente "$1" è già bloccato',
'ipb-needreblock'                 => "L'utente $1 è già bloccato. Modificare le impostazioni?",
'ipb-otherblocks-header'          => '{{PLURAL:$1|Altro blocco|Altri blocchi}}',
'unblock-hideuser'                => 'Non puoi sbloccare questo utente, perché il suo nome utente è stato nascosto.',
'ipb_cant_unblock'                => 'Errore: Impossibile trovare il blocco con ID $1. Il blocco potrebbe essere già stato rimosso.',
'ipb_blocked_as_range'            => "Errore: L'indirizzo IP $1 non è soggetto a blocco individuale e non può essere sbloccato. Il blocco è invece attivo a livello dell'intervallo $2, che può essere sbloccato.",
'ip_range_invalid'                => 'Intervallo di indirizzi IP non valido.',
'ip_range_toolarge'               => 'Non è possibile bloccare range superiori al /$1',
'blockme'                         => 'Bloccami',
'proxyblocker'                    => 'Blocco dei proxy aperti',
'proxyblocker-disabled'           => 'Questa funzione è disabilitata.',
'proxyblockreason'                => 'Questo indirizzo IP è stato bloccato perché risulta essere un proxy aperto. Si prega di contattare il proprio fornitore di accesso a Internet o il supporto tecnico e informarli di questo grave problema di sicurezza.',
'proxyblocksuccess'               => 'Fatto.',
'sorbsreason'                     => 'Questo indirizzo IP è elencato come proxy aperto nella blacklist DNSBL utilizzata da {{SITENAME}}.',
'sorbs_create_account_reason'     => 'Non è possibile creare nuovi accessi da questo indirizzo IP perché è elencato come proxy aperto nella blacklist DNSBL utilizzata da {{SITENAME}}.',
'cant-block-while-blocked'        => 'Non è possibile bloccare altri utenti mentre si è bloccati.',
'cant-see-hidden-user'            => 'L\'utente che si sta tentando di bloccare è stato già bloccato e nascosto. Poiché non hai il permesso "hideuser", non è possibile visualizzare o modificare il blocco dell\'utente.',
'ipbblocked'                      => 'Non puoi bloccare o sbloccare altri utenti, perché tu stesso sei bloccato',
'ipbnounblockself'                => 'Non puoi sbloccare te stesso',

# Developer tools
'lockdb'              => 'Blocca il database',
'unlockdb'            => 'Sblocca il database',
'lockdbtext'          => "Il blocco del database comporta l'interruzione, per tutti gli utenti, della possibilità di modificare le pagine o di crearne di nuove, di cambiare le preferenze e modificare le liste degli osservati speciali, e in generale di tutte le operazioni che richiedono modifiche al database. Per cortesia, conferma che ciò corrisponde effettivamente all'azione da te richiesta e che al termine della manutenzione provvederai allo sblocco del database.",
'unlockdbtext'        => "Lo sblocco del database consente di nuovo a tutti gli utenti di modificare le pagine o di crearne di nuove, di cambiare le preferenze e modificare le liste degli osservati speciali, e in generale di compiere tutte le operazioni che richiedono modifiche al database. Per cortesia, conferma che ciò corrisponde effettivamente all'azione da te richiesta.",
'lockconfirm'         => 'Sì, intendo effettivamente bloccare il database.',
'unlockconfirm'       => 'Sì, intendo effettivamente sbloccare il database.',
'lockbtn'             => 'Blocca il database',
'unlockbtn'           => 'Sblocca il database',
'locknoconfirm'       => 'Non è stata spuntata la casella di conferma.',
'lockdbsuccesssub'    => 'Blocco del database eseguito',
'unlockdbsuccesssub'  => 'Sblocco del database eseguito',
'lockdbsuccesstext'   => 'Il database è stato bloccato.<br />
Ricordare di [[Special:UnlockDB|rimuovere il blocco]] dopo aver terminato le operazioni di manutenzione.',
'unlockdbsuccesstext' => 'Il database è stato sbloccato.',
'lockfilenotwritable' => "Impossibile scrivere sul file di ''lock'' del database. L'accesso in scrittura a tale file da parte del server web è necessario per bloccare e sbloccare il database.",
'databasenotlocked'   => 'Il database non è bloccato.',
'lockedbyandtime'     => '(da $1 il $2 alle $3)',

# Move page
'move-page'                    => 'Spostamento di $1',
'move-page-legend'             => 'Spostamento di pagina',
'movepagetext'                 => "Questo modulo consente di rinominare una pagina, spostando tutta la sua cronologia al nuovo nome. La pagina attuale diverrà automaticamente un redirect al nuovo titolo. Puoi aggiornare automaticamente i redirect che puntano al titolo originale. Puoi decidere di non farlo, ma ricordati di verificare che lo spostamento non abbia creato [[Special:DoubleRedirects|doppi redirect]] o [[Special:BrokenRedirects|redirect errati]]. L'onere di garantire che i collegamenti alla pagina restino corretti spetta a chi la sposta.

Si noti che la pagina '''non''' sarà spostata se ne esiste già una con il nuovo nome, a meno che non sia vuota o costituita solo da un redirect alla vecchia e sia priva di versioni precedenti. In caso di spostamento errato si può quindi tornare subito al vecchio titolo, e non è possibile sovrascrivere per errore una pagina già esistente.

'''ATTENZIONE:'''
Un cambiamento così drastico può creare contrattempi e problemi, soprattutto per le pagine più visitate. Accertarsi di aver valutato le conseguenze dello spostamento prima di procedere.",
'movepagetext-noredirectfixer' => "Questo modulo consente di rinominare una pagina, spostando tutta la sua cronologia al nuovo nome. La pagina attuale diverrà automaticamente un redirect al nuovo titolo. Controlla che lo spostamento non abbia creato [[Special:DoubleRedirects|doppi redirect]] o [[Special:BrokenRedirects|redirect errati]]. L'onere di garantire che i collegamenti alla pagina restino corretti spetta a chi la sposta.

Si noti che la pagina '''non''' sarà spostata se ne esiste già una con il nuovo nome, a meno che non sia vuota o costituita solo da un redirect alla vecchia e sia priva di versioni precedenti. In caso di spostamento errato si può quindi tornare subito al vecchio titolo, e non è possibile sovrascrivere per errore una pagina già esistente.

'''ATTENZIONE:'''
Un cambiamento così drastico può creare contrattempi e problemi, soprattutto per le pagine più visitate. Accertarsi di aver valutato le conseguenze dello spostamento prima di procedere.",
'movepagetalktext'             => "La corrispondente pagina di discussione, se esiste, sarà spostata automaticamente insieme alla pagina principale, '''tranne che nei seguenti casi''':
* lo spostamento della pagina è tra namespace diversi;
* in corrispondenza del nuovo titolo esiste già una pagina di discussione (non vuota);
* la casella qui sotto è stata deselezionata.

In questi casi, se lo si ritiene opportuno, occorre spostare o aggiungere manualmente le informazioni contenute nella pagina di discussione.",
'movearticle'                  => 'Sposta la pagina:',
'moveuserpage-warning'         => "'''Attenzione:''' Si sta per spostare una pagina utente. Nota che verrà spostata solamente la pagina. L'utente ''non'' sarà rinominato.",
'movenologin'                  => 'Accesso non effettuato',
'movenologintext'              => "Lo spostamento delle pagine è consentito solo agli utenti registrati che hanno eseguito l'[[Special:UserLogin|accesso]] al sito.",
'movenotallowed'               => 'Non si dispone dei permessi necessari per spostare le pagine.',
'movenotallowedfile'           => 'Non si dispone dei permessi necessari per spostare i file.',
'cant-move-user-page'          => 'Non si dispone dei permessi necessari per spostare le pagine utente (escluse le sottopagine).',
'cant-move-to-user-page'       => 'Non si dispone dei permessi necessari per spostare la pagina su una pagina utente (escluse le sottopagine utente).',
'newtitle'                     => 'Nuovo titolo:',
'move-watch'                   => 'Aggiungi la pagina agli osservati speciali',
'movepagebtn'                  => 'Sposta la pagina',
'pagemovedsub'                 => 'Spostamento effettuato con successo',
'movepage-moved'               => '\'\'\'"$1" è stata spostata a "$2"\'\'\'',
'movepage-moved-redirect'      => 'È stato creato un redirect.',
'movepage-moved-noredirect'    => 'La creazione di un redirect è stata soppressa.',
'articleexists'                => 'Una pagina con questo nome esiste già oppure il nome scelto non è valido.
Scegliere un titolo diverso per la pagina.',
'cantmove-titleprotected'      => 'Lo spostamento della pagina non è possibile in quanto il nuovo titolo è stato protetto per impedirne la creazione',
'talkexists'                   => "'''La pagina è stata spostata correttamente, ma non è stato possibile spostare la pagina di discussione perché ne esiste già un'altra con il nuovo titolo. Integrare manualmente i contenuti delle due pagine.'''",
'movedto'                      => 'spostata a',
'movetalk'                     => 'Sposta anche la pagina di discussione.',
'move-subpages'                => 'Sposta le sottopagine (sino a $1)',
'move-talk-subpages'           => 'Sposta le sottopagine di discussione (fino a $1)',
'movepage-page-exists'         => 'La pagina $1 esiste già e non può essere automaticamente sovrascritta.',
'movepage-page-moved'          => 'La pagina $1 è stata spostata a $2.',
'movepage-page-unmoved'        => 'La pagina $1 non può essere spostata a $2.',
'movepage-max-pages'           => 'È stato spostato il numero massimo di $1 {{PLURAL:$1|pagina|pagine}} e non potranno essere spostate ulteriori pagine automaticamente.',
'movelogpage'                  => 'Spostamenti',
'movelogpagetext'              => 'Di seguito sono elencate le pagine spostate di recente.',
'movesubpage'                  => '{{PLURAL:$1|Sottopagina|Sottopagine}}',
'movesubpagetext'              => 'Questa pagina ha $1 {{PLURAL:$1|sottopagina mostrata|sottopagine mostrate}} di seguito.',
'movenosubpage'                => 'Questa pagina non ha sottopagine.',
'movereason'                   => 'Motivo:',
'revertmove'                   => 'ripristina',
'delete_and_move'              => 'Cancella e sposta',
'delete_and_move_text'         => '==Cancellazione richiesta==

La pagina specificata come destinazione "[[:$1]]" esiste già. Vuoi cancellarla per proseguire con lo spostamento?',
'delete_and_move_confirm'      => 'Sì, sovrascrivi la pagina esistente',
'delete_and_move_reason'       => 'Cancellata per rendere possibile lo spostamento da "[[$1]]"',
'selfmove'                     => 'Il nuovo titolo è uguale al vecchio; impossibile spostare la pagina su se stessa.',
'immobile-source-namespace'    => 'Non è possibile spostare pagine del namespace "$1"',
'immobile-target-namespace'    => 'Non è possibile spostare pagine nel namespace "$1"',
'immobile-target-namespace-iw' => 'Un collegamento interwiki non è una destinazione valida per spostare la pagina.',
'immobile-source-page'         => 'Questa pagina non può essere spostata.',
'immobile-target-page'         => 'Non è possibile spostare sul titolo indicato.',
'imagenocrossnamespace'        => 'Non è possibile spostare un file fuori dal relativo namespace.',
'nonfile-cannot-move-to-file'  => 'Non è possibile spostare un file fuori dal relativo namespace.',
'imagetypemismatch'            => 'La nuova estensione del file non corrisponde al tipo dello stesso',
'imageinvalidfilename'         => "Il nome dell'immagine non è valido",
'fix-double-redirects'         => 'Aggiorna tutti i redirect che puntano al titolo originale',
'move-leave-redirect'          => 'Crea un redirect con lo spostamento',
'protectedpagemovewarning'     => "'''Attenzione: Questa pagina è stata bloccata in modo che solo gli utenti con privilegi di amministratore possano spostarla.'''
L'ultimo elemento del registro è riportato di seguito per informazione:",
'semiprotectedpagemovewarning' => "'''Nota:''' Questa pagina è stata bloccata in modo che solo gli utenti registrati possano spostarla.
L'ultimo elemento del registro è riportato di seguito per informazione:",
'move-over-sharedrepo'         => '== File già esistente ==
[[:$1]] è già presente in una struttura condivisa. Spostare un file a questo titolo comporterà la sovrascrittura del file condiviso.',
'file-exists-sharedrepo'       => 'Il nome che hai scelto per il file è già utilizzato.
Per favore, scegli un nome diverso.',

# Export
'export'            => 'Esporta pagine',
'exporttext'        => "È possibile esportare il testo e la cronologia delle modifiche di una pagina o di un gruppo di pagine in formato XML per importarle in altri siti che utilizzano il software MediaWiki, attraverso la [[Special:Import|pagina delle importazioni]].

Per esportare le pagine indicare i titoli nella casella di testo sottostante, uno per riga, e specificare se si desidera ottenere l'ultima versione e tutte le versioni precedenti, con i dati della cronologia della pagina, oppure soltanto l'ultima versione e i dati corrispondenti all'ultima modifica.

In quest'ultimo caso si può anche utilizzare un collegamento, ad esempio [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] per esportare \"[[{{MediaWiki:Mainpage}}]]\".",
'exportall'         => 'Esporta tutte le pagine',
'exportcuronly'     => "Includi solo la revisione attuale, non l'intera cronologia",
'exportnohistory'   => "----
'''Nota:''' l'esportazione dell'intera cronologia delle pagine attraverso questa interfaccia è stata disattivata per motivi legati alle prestazioni del sistema.",
'exportlistauthors' => "Includi l'elenco completo dei contributori per ogni pagina",
'export-submit'     => 'Esporta',
'export-addcattext' => 'Aggiungi pagine dalla categoria:',
'export-addcat'     => 'Aggiungi',
'export-addnstext'  => 'Aggiungi pagine dal namespace:',
'export-addns'      => 'Aggiungi',
'export-download'   => 'Richiedi il salvataggio come file',
'export-templates'  => 'Includi i template',
'export-pagelinks'  => 'Includi pagine correlate ad una profondità di:',

# Namespace 8 related
'allmessages'                   => 'Messaggi di sistema',
'allmessagesname'               => 'Nome',
'allmessagesdefault'            => 'Testo predefinito',
'allmessagescurrent'            => 'Testo attuale',
'allmessagestext'               => 'Questa è la lista di tutti i messaggi di sistema disponibili nel namespace MediaWiki.
Visitare [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] e [//translatewiki.net translatewiki.net] se si desidera contribuire alla localizzazione generica di MediaWiki.',
'allmessagesnotsupportedDB'     => "Non è possibile utilizzare questa pagina perché il flag '''\$wgUseDatabaseMessages''' non è attivo.",
'allmessages-filter-legend'     => 'Filtro',
'allmessages-filter'            => 'Filtra per stato di modifica:',
'allmessages-filter-unmodified' => 'Non modificati',
'allmessages-filter-all'        => 'Tutti',
'allmessages-filter-modified'   => 'Modificati',
'allmessages-prefix'            => 'Filtra per prefisso:',
'allmessages-language'          => 'Lingua:',
'allmessages-filter-submit'     => 'Vai',

# Thumbnails
'thumbnail-more'           => 'Ingrandisci',
'filemissing'              => 'File mancante',
'thumbnail_error'          => 'Errore nella creazione della miniatura: $1',
'djvu_page_error'          => 'Numero di pagina DjVu errato',
'djvu_no_xml'              => "Impossibile ottenere l'XML per il file DjVu",
'thumbnail-temp-create'    => 'Impossibile creare il file temporaneo delle miniature',
'thumbnail-dest-create'    => 'Impossibile salvare la miniatura nella destinazione',
'thumbnail_invalid_params' => 'Parametri miniatura non corretti',
'thumbnail_dest_directory' => 'Impossibile creare la directory di destinazione',
'thumbnail_image-type'     => 'Tipo di immagine non supportato',
'thumbnail_gd-library'     => 'Configurazione incompleta della libreria GD: funzione $1 mancante',
'thumbnail_image-missing'  => 'Sembra essere mancante il file: $1',

# Special:Import
'import'                     => 'Importa pagine',
'importinterwiki'            => 'Importazione transwiki',
'import-interwiki-text'      => 'Selezionare un progetto wiki e il titolo della pagina da importare.
Le date di pubblicazione e i nomi degli autori delle varie versioni saranno conservati.
Tutte le operazioni di importazione trans-wiki sono registrate nel [[Special:Log/import|log di importazione]].',
'import-interwiki-source'    => 'Sorgente wiki/pagina:',
'import-interwiki-history'   => "Copia l'intera cronologia di questa pagina",
'import-interwiki-templates' => 'Includi tutti i template',
'import-interwiki-submit'    => 'Importa',
'import-interwiki-namespace' => 'Namespace di destinazione:',
'import-upload-filename'     => 'Nome file:',
'import-comment'             => 'Oggetto:',
'importtext'                 => 'Si prega di esportare il file dal sito wiki di origine con la [[Special:Export|funzione di esportazione]], salvarlo sul proprio disco e poi caricarlo qui.',
'importstart'                => 'Importazione delle pagine in corso...',
'import-revision-count'      => '{{PLURAL:$1|una revisione importata|$1 revisioni importate}}',
'importnopages'              => 'Nessuna pagina da importare.',
'imported-log-entries'       => 'Importat{{PLURAL:$1|o|i}} $1 {{PLURAL:$1|evento|eventi}} di log.',
'importfailed'               => 'Importazione non riuscita: <nowiki>$1</nowiki>',
'importunknownsource'        => "Tipo di origine sconosciuto per l'importazione",
'importcantopen'             => 'Impossibile aprire il file di importazione',
'importbadinterwiki'         => 'Collegamento inter-wiki errato',
'importnotext'               => 'Testo vuoto o mancante',
'importsuccess'              => 'Importazione riuscita.',
'importhistoryconflict'      => 'La cronologia contiene delle versioni in conflitto (questa pagina potrebbe essere già stata importata)',
'importnosources'            => "Non è stata definita una fonte per l'importazione transwiki; l'importazione diretta della cronologia non è attiva.",
'importnofile'               => "Non è stato caricato nessun file per l'importazione.",
'importuploaderrorsize'      => "Caricamento del file per l'importazione non riuscito. Il file supera le dimensioni massime consentite per l'upload.",
'importuploaderrorpartial'   => "Caricamento del file per l'importazione non riuscito. Il file è stato caricato solo in parte.",
'importuploaderrortemp'      => "Caricamento del file per l'importazione non riuscito. Manca una cartella temporanea.",
'import-parse-failure'       => "Errore di analisi nell'importazione XML",
'import-noarticle'           => 'Nessuna pagina da importare.',
'import-nonewrevisions'      => 'Tutte le revisioni sono già state importate in precedenza.',
'xml-error-string'           => '$1 a riga $2, colonna $3 (byte $4): $5',
'import-upload'              => 'Carica dati XML',
'import-token-mismatch'      => 'I dati relativi alla sessione sono andati persi. Riprovare.',
'import-invalid-interwiki'   => 'Impossibile importare dal progetto wiki indicato.',
'import-error-edit'          => 'La pagina "$1" non è stata importata poiché non sei autorizzato a modificarla.',
'import-error-create'        => 'La pagina "$1" non è stata importata poiché non sei autorizzato a crearla.',
'import-error-interwiki'     => 'La pagina "$1" non viene importata perché il suo nome è riservato per il collegamento esterno (interwiki).',
'import-error-special'       => 'La pagina "$1" non viene importata perché appartiene a un namespace speciale che non permette pagine.',
'import-error-invalid'       => 'La pagina "$1" non viene importata perché il suo nome non è valido.',

# Import log
'importlogpage'                    => 'Importazioni',
'importlogpagetext'                => 'Di seguito sono elencate le importazioni di pagine provenienti da altre wiki, complete di cronologia.',
'import-logentry-upload'           => 'ha importato [[$1]] tramite upload',
'import-logentry-upload-detail'    => '{{PLURAL:$1|una revisione importata|$1 revisioni importate}}',
'import-logentry-interwiki'        => 'ha trasferito da altra wiki la pagina $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|una revisione importata|$1 revisioni importate}} da $2',

# JavaScriptTest
'javascripttest'                           => 'Sperimentazione JavaScript',
'javascripttest-disabled'                  => 'Questa funzione non è abilitata su questo wiki.',
'javascripttest-title'                     => 'In esecuzione test per $1',
'javascripttest-pagetext-noframework'      => "Questa pagina è riservata all'esecuzione di test di JavaScript.",
'javascripttest-pagetext-unknownframework' => 'Framework di test sconosciuto "$1".',
'javascripttest-pagetext-frameworks'       => 'Per cortesia, scegli uno dei seguenti framework per i test: $1',
'javascripttest-pagetext-skins'            => 'Scegli una skin con cui eseguire i test:',
'javascripttest-qunit-intro'               => 'Vedi su mediawiki.org la [$1 documentazione riguardante i test].',
'javascripttest-qunit-heading'             => 'Suite di test di JavaScript per QUnit in MediaWiki',

# Tooltip help for the actions
'tooltip-pt-userpage'                 => 'La tua pagina utente',
'tooltip-pt-anonuserpage'             => 'La pagina utente di questo indirizzo IP',
'tooltip-pt-mytalk'                   => 'La tua pagina di discussione',
'tooltip-pt-anontalk'                 => 'Discussioni sulle modifiche fatte da questo indirizzo IP',
'tooltip-pt-preferences'              => 'Le mie preferenze',
'tooltip-pt-watchlist'                => 'La lista delle pagine che stai tenendo sotto osservazione',
'tooltip-pt-mycontris'                => 'Elenco dei tuoi contributi',
'tooltip-pt-login'                    => 'La registrazione è consigliata, anche se non obbligatoria',
'tooltip-pt-anonlogin'                => 'La registrazione è consigliata, anche se non obbligatoria',
'tooltip-pt-logout'                   => 'Uscita (logout)',
'tooltip-ca-talk'                     => 'Vedi le discussioni relative a questa pagina',
'tooltip-ca-edit'                     => 'Puoi modificare questa pagina. Per favore usa il pulsante di anteprima prima di salvare',
'tooltip-ca-addsection'               => 'Inizia una nuova sezione',
'tooltip-ca-viewsource'               => 'Questa pagina è protetta, ma puoi vedere il suo codice sorgente',
'tooltip-ca-history'                  => 'Versioni precedenti di questa pagina',
'tooltip-ca-protect'                  => 'Proteggi questa pagina',
'tooltip-ca-unprotect'                => 'Modifica la protezione di questa pagina',
'tooltip-ca-delete'                   => 'Cancella questa pagina',
'tooltip-ca-undelete'                 => "Ripristina la pagina com'era prima della cancellazione",
'tooltip-ca-move'                     => 'Sposta questa pagina (cambia titolo)',
'tooltip-ca-watch'                    => 'Aggiungi questa pagina alla tua lista degli osservati speciali',
'tooltip-ca-unwatch'                  => 'Elimina questa pagina dalla tua lista degli osservati speciali',
'tooltip-search'                      => "Cerca all'interno di {{SITENAME}}",
'tooltip-search-go'                   => 'Vai a una pagina con il titolo indicato, se esiste',
'tooltip-search-fulltext'             => 'Cerca il testo indicato nelle pagine',
'tooltip-p-logo'                      => 'Visita la pagina principale',
'tooltip-n-mainpage'                  => 'Visita la pagina principale',
'tooltip-n-mainpage-description'      => 'Visita la pagina principale',
'tooltip-n-portal'                    => 'Descrizione del progetto, cosa puoi fare, dove trovare le cose',
'tooltip-n-currentevents'             => 'Informazioni sugli eventi di attualità',
'tooltip-n-recentchanges'             => 'Elenco delle ultime modifiche del sito',
'tooltip-n-randompage'                => 'Mostra una pagina a caso',
'tooltip-n-help'                      => 'Pagine di aiuto',
'tooltip-t-whatlinkshere'             => 'Elenco di tutte le pagine che sono collegate a questa',
'tooltip-t-recentchangeslinked'       => 'Elenco delle ultime modifiche alle pagine collegate a questa',
'tooltip-feed-rss'                    => 'Feed RSS per questa pagina',
'tooltip-feed-atom'                   => 'Feed Atom per questa pagina',
'tooltip-t-contributions'             => 'Lista dei contributi di questo utente',
'tooltip-t-emailuser'                 => 'Invia un messaggio e-mail a questo utente',
'tooltip-t-upload'                    => 'Carica file multimediali',
'tooltip-t-specialpages'              => 'Lista di tutte le pagine speciali',
'tooltip-t-print'                     => 'Versione stampabile di questa pagina',
'tooltip-t-permalink'                 => 'Collegamento permanente a questa versione della pagina',
'tooltip-ca-nstab-main'               => 'Vedi la voce',
'tooltip-ca-nstab-user'               => 'Vedi la pagina utente',
'tooltip-ca-nstab-media'              => 'Vedi la pagina del file multimediale',
'tooltip-ca-nstab-special'            => 'Questa è una pagina speciale, non può essere modificata',
'tooltip-ca-nstab-project'            => 'Vedi la pagina di servizio',
'tooltip-ca-nstab-image'              => 'Vedi la pagina del file',
'tooltip-ca-nstab-mediawiki'          => 'Vedi il messaggio di sistema',
'tooltip-ca-nstab-template'           => 'Vedi il template',
'tooltip-ca-nstab-help'               => 'Vedi la pagina di aiuto',
'tooltip-ca-nstab-category'           => 'Vedi la pagina della categoria',
'tooltip-minoredit'                   => 'Segnala come modifica minore',
'tooltip-save'                        => 'Salva le modifiche',
'tooltip-preview'                     => 'Anteprima delle modifiche (consigliata prima di salvare)',
'tooltip-diff'                        => 'Guarda le modifiche apportate al testo',
'tooltip-compareselectedversions'     => 'Guarda le differenze tra le due versioni selezionate di questa pagina.',
'tooltip-watch'                       => 'Aggiungi questa pagina alla tua lista degli osservati speciali',
'tooltip-watchlistedit-normal-submit' => 'Rimuovi i titoli',
'tooltip-watchlistedit-raw-submit'    => 'Aggiorna la lista degli osservati speciali',
'tooltip-recreate'                    => 'Ricrea la pagina anche se è stata cancellata',
'tooltip-upload'                      => 'Inizia il caricamento',
'tooltip-rollback'                    => '"Rollback" annulla le modifiche a questa pagina dell\'ultimo contributore con un solo clic.',
'tooltip-undo'                        => '"Annulla" permette di annullare questa modifica e apre il modulo di modifica in modalità di anteprima. Permette di inserire una motivazione nell\'oggetto della modifica.',
'tooltip-preferences-save'            => 'Salva le preferenze',
'tooltip-summary'                     => 'Inserire una breve sintesi',

# Stylesheets
'common.css'              => '/* Gli stili CSS inseriti qui si applicano a tutte le skin */',
'standard.css'            => '/* Gli stili CSS inseriti qui si applicano agli utenti che usano la skin Standard */',
'nostalgia.css'           => '/* Gli stili CSS inseriti qui si applicano agli utenti che usano la skin Nostalgia */',
'cologneblue.css'         => '/* Gli stili CSS inseriti qui si applicano agli utenti che usano la skin Cologne Blue */',
'monobook.css'            => '/* Gli stili CSS inseriti qui si applicano agli utenti che usano la skin Monobook */',
'myskin.css'              => '/* Gli stili CSS inseriti qui si applicano agli utenti che usano la skin MySkin */',
'chick.css'               => '/* Gli stili CSS inseriti qui si applicano agli utenti che usano la skin Chick */',
'simple.css'              => '/* Gli stili CSS inseriti qui si applicano agli utenti che usano la skin Simple */',
'modern.css'              => '/* Gli stili CSS inseriti qui si applicano agli utenti che usano la skin Modern */',
'vector.css'              => '/* Gli stili CSS inseriti qui si applicano agli utenti che usano la skin Vector */',
'print.css'               => "/* Gli stili CSS inseriti qui si applicano all'output in stampa */",
'handheld.css'            => '/* Gli stili CSS inseriti qui si applicano ai dispositivi portatili basati sulla skin configurata in $wgHandheldStyle */',
'noscript.css'            => '/ * Gli stili CSS inseriti qui si applicano agli utenti che hanno JavaScript disabilitato * /',
'group-autoconfirmed.css' => '/ * Gli stili CSS inseriti qui si applicheranno solo ad utenti autoconvalidati * /',
'group-bot.css'           => '/ * Gli stili CSS inseriti qui si applicheranno solo ai bot * /',
'group-sysop.css'         => '/ * Gli stili CSS inseriti qui si applicheranno solo agli amministratori/sysop * /',
'group-bureaucrat.css'    => '/ * Gli stili CSS inseriti qui si applicheranno solo ai burocrati * /',

# Scripts
'common.js'              => '/* Il codice JavaScript inserito qui viene caricato da ciascuna pagina, per tutti gli utenti. */',
'standard.js'            => '/* Il codice JavaScript inserito qui viene caricato dagli utenti che usano la skin Standard */',
'nostalgia.js'           => '/* Il codice JavaScript inserito qui viene caricato dagli utenti che usano la skin Nostalgia */',
'cologneblue.js'         => '/* Il codice JavaScript inserito qui viene caricato dagli utenti che usano la skin Cologne Blue */',
'monobook.js'            => '/* Il codice JavaScript inserito qui viene caricato dagli utenti che usano la skin MonoBook */',
'myskin.js'              => '/* Il codice JavaScript inserito qui viene caricato dagli utenti che usano la skin MySkin */',
'chick.js'               => '/* Il codice JavaScript inserito qui viene caricato dagli utenti che usano la skin Chick */',
'simple.js'              => '/* Il codice JavaScript inserito qui viene caricato dagli utenti che usano la skin Simple */',
'modern.js'              => '/* Il codice JavaScript inserito qui viene caricato dagli utenti che usano la skin Modern */',
'vector.js'              => '/* Il codice JavaScript inserito qui viene caricato dagli utenti che usano la skin Vector */',
'group-autoconfirmed.js' => '/ * Il codice JavaScript inserito qui viene caricato solo per gli utenti autoconvalidati * /',
'group-bot.js'           => '/ * Il codice JavaScript inserito qui viene caricato solo per i bot * /',
'group-sysop.js'         => '/ * Il codice JavaScript inserito qui viene caricato solo per gli amministratori/sysop * /',
'group-bureaucrat.js'    => '/ * Il codice JavaScript inserito qui viene caricato solo per i burocrati * /',

# Metadata
'notacceptable' => 'Il server wiki non è in grado di fornire i dati in un formato leggibile dal client.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Utente anonimo|Utenti anonimi}} di {{SITENAME}}',
'siteuser'         => '$1, utente di {{SITENAME}}',
'anonuser'         => '$1, utente anonimo di {{SITENAME}}',
'lastmodifiedatby' => "Questa pagina è stata modificata per l'ultima volta il $1 alle $2 da $3.",
'othercontribs'    => 'Il testo attuale è basato su contributi di $1.',
'others'           => 'altri',
'siteusers'        => '$1, {{PLURAL:$2|utente|utenti}} di {{SITENAME}}',
'anonusers'        => '$1, {{PLURAL:$2|utente anonimo|utenti anonimi}} di {{SITENAME}}',
'creditspage'      => 'Autori della pagina',
'nocredits'        => 'Nessuna informazione sugli autori disponibile per questa pagina.',

# Spam protection
'spamprotectiontitle' => 'Filtro anti-spam',
'spamprotectiontext'  => 'La pagina che si è tentato di salvare è stata bloccata dal filtro anti-spam. Ciò è probabilmente dovuto alla presenza di un collegamento a un sito esterno presente nella blacklist.',
'spamprotectionmatch' => 'Il filtro anti-spam è stato attivato dal seguente testo: $1',
'spambot_username'    => 'MediaWiki - sistema di rimozione spam',
'spam_reverting'      => "Ripristinata l'ultima versione priva di collegamenti a $1",
'spam_blanking'       => 'Pagina svuotata, tutte le versioni contenevano collegamenti a $1',

# Info page
'pageinfo-title'            => 'Informazioni per "$1"',
'pageinfo-header-edits'     => 'Cronologia delle modifiche',
'pageinfo-header-watchlist' => 'Osservati speciali',
'pageinfo-header-views'     => 'Visualizzazioni',
'pageinfo-subjectpage'      => 'Pagina',
'pageinfo-talkpage'         => 'Pagina di discussione',
'pageinfo-watchers'         => 'Numero di utenti che hanno la pagina nei loro osservati speciali',
'pageinfo-edits'            => 'Numero di edit',
'pageinfo-authors'          => 'Numero totale di autori diversi',
'pageinfo-views'            => 'Numero di visualizzazioni',
'pageinfo-viewsperedit'     => 'Visualizzazioni per modifica',

# Patrolling
'markaspatrolleddiff'                 => 'Segna la modifica come verificata',
'markaspatrolledtext'                 => 'Segna questa pagina come verificata',
'markedaspatrolled'                   => 'Modifica verificata',
'markedaspatrolledtext'               => 'La modifica di [[:$1]] selezionata è stata segnata come verificata.',
'rcpatroldisabled'                    => 'La verifica delle ultime modifiche è disattivata',
'rcpatroldisabledtext'                => 'La funzione di verifica delle ultime modifiche al momento non è attiva.',
'markedaspatrollederror'              => 'Impossibile contrassegnare la voce come verificata',
'markedaspatrollederrortext'          => 'Occorre specificare una modifica da contrassegnare come verificata.',
'markedaspatrollederror-noautopatrol' => 'Non si dispone dei permessi necessari per segnare le proprie modifiche come verificate.',

# Patrol log
'patrol-log-page'      => 'Modifiche verificate',
'patrol-log-header'    => 'Di seguito sono elencate le verifiche delle modifiche.',
'log-show-hide-patrol' => '$1 log delle modifiche verificate',

# Image deletion
'deletedrevision'                 => 'Cancellata la vecchia revisione di $1.',
'filedeleteerror-short'           => 'Errore nella cancellazione del file: $1',
'filedeleteerror-long'            => 'Si sono verificati degli errori nel tentativo di cancellare il file:

$1',
'filedelete-missing'              => 'Impossibile cancellare il file "$1" in quanto non esiste.',
'filedelete-old-unregistered'     => 'La revisione del file indicata, "$1", non è contenuta nel database.',
'filedelete-current-unregistered' => 'Il file specificato, "$1", non è contenuto nel database.',
'filedelete-archive-read-only'    => 'Il server Web non è in grado di scrivere nella directory di archivio "$1".',

# Browsing diffs
'previousdiff' => '← Differenza precedente',
'nextdiff'     => 'Differenza successiva →',

# Media information
'mediawarning'           => "'''Attenzione''': Questo file potrebbe contenere codice maligno. La sua esecuzione potrebbe danneggiare il tuo sistema.",
'imagemaxsize'           => "Dimensione massima delle immagini:<br />''(per le pagine di descrizione del file)''",
'thumbsize'              => 'Grandezza delle miniature:',
'widthheightpage'        => '$1 × $2, $3 {{PLURAL:$3|pagina|pagine}}',
'file-info'              => 'dimensione del file: $1, tipo MIME: $2',
'file-info-size'         => '$1 × $2 pixel, dimensione del file: $3, tipo MIME: $4',
'file-info-size-pages'   => '$1 × $2 pixel, dimensione del file: $3, tipo MIME: $4, $5 {{PLURAL:$5|pagina|pagine}}',
'file-nohires'           => 'Non sono disponibili versioni a risoluzione più elevata.',
'svg-long-desc'          => 'file in formato SVG, dimensioni nominali $1 × $2 pixel, dimensione del file: $3',
'show-big-image'         => 'Versione ad alta risoluzione',
'show-big-image-preview' => 'Dimensioni di questa anteprima: $1.',
'show-big-image-other'   => '{{PLURAL:$2|Altra risoluzione|Altre risoluzioni}}: $1.',
'show-big-image-size'    => '$1 × $2 pixel',
'file-info-gif-looped'   => 'ciclico',
'file-info-gif-frames'   => '$1 {{PLURAL:$1|frame|frame}}',
'file-info-png-looped'   => 'ciclico',
'file-info-png-repeat'   => 'ripetuto $1 {{PLURAL:$1|volta|volte}}',
'file-info-png-frames'   => '$1 {{PLURAL:$1|frame|frame}}',

# Special:NewFiles
'newimages'             => 'Galleria dei nuovi file',
'imagelisttext'         => "La lista presentata di seguito, costituita da {{PLURAL:$1|un file|'''$1''' file}}, è ordinata $2.",
'newimages-summary'     => 'Questa pagina speciale mostra i file caricati più di recente.',
'newimages-legend'      => 'Filtra',
'newimages-label'       => 'Nome file (o una parte di esso):',
'showhidebots'          => '($1 i bot)',
'noimages'              => "Non c'è nulla da vedere.",
'ilsubmit'              => 'Ricerca',
'bydate'                => 'per data',
'sp-newimages-showfrom' => 'Mostra i file più recenti a partire dalle ore $2 del $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => '$1&nbsp;s',
'minutes-abbrev' => '$1&nbsp;min',
'hours-abbrev'   => '$1&nbsp;h',
'days-abbrev'    => '$1&nbsp;gg.',
'seconds'        => '{{PLURAL:$1|un secondo|$1 secondi}}',
'minutes'        => '{{PLURAL:$1|un minuto|$1 minuti}}',
'hours'          => "{{PLURAL:$1|un'ora|$1 ore}}",
'days'           => '{{PLURAL:$1|un giorno|$1 giorni}}',
'ago'            => '$1 fa',

# Bad image list
'bad_image_list' => "Il formato è il seguente:

Vengono considerati soltanto gli elenchi puntati (righe che cominciano con il carattere *). Il primo collegamento su ciascuna riga dev'essere un collegamento a un file indesiderato.
I collegamenti successivi, sulla stessa riga, sono considerati come eccezioni (ovvero, pagine nelle quali il file può essere richiamato normalmente).",

# Metadata
'metadata'          => 'Metadati',
'metadata-help'     => 'Questo file contiene informazioni aggiuntive, probabilmente aggiunte dalla fotocamera o dallo scanner usati per crearlo o digitalizzarlo. Se il file è stato modificato, alcuni dettagli potrebbero non corrispondere alla realtà.',
'metadata-expand'   => 'Mostra dettagli',
'metadata-collapse' => 'Nascondi dettagli',
'metadata-fields'   => "I campi relativi ai metadati dell'immagine elencati in questo messaggio verranno mostrati sulla pagina dell'immagine quando la tabella dei metadati è presentata nella forma breve. Per impostazione predefinita, gli altri campi verranno nascosti.
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
* gpsaltitude",

# EXIF tags
'exif-imagewidth'                  => 'Larghezza',
'exif-imagelength'                 => 'Altezza',
'exif-bitspersample'               => 'Bit per campione',
'exif-compression'                 => 'Meccanismo di compressione',
'exif-photometricinterpretation'   => 'Struttura dei pixel',
'exif-orientation'                 => 'Orientamento',
'exif-samplesperpixel'             => 'Numero delle componenti',
'exif-planarconfiguration'         => 'Disposizione dei dati',
'exif-ycbcrsubsampling'            => 'Rapporto di campionamento Y / C',
'exif-ycbcrpositioning'            => 'Posizionamento componenti Y e C',
'exif-xresolution'                 => 'Risoluzione orizzontale',
'exif-yresolution'                 => 'Risoluzione verticale',
'exif-stripoffsets'                => 'Posizione dei dati immagine',
'exif-rowsperstrip'                => 'Numero righe per striscia',
'exif-stripbytecounts'             => 'Numero di byte per striscia compressa',
'exif-jpeginterchangeformat'       => 'Posizione byte SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Numero di byte di dati JPEG',
'exif-whitepoint'                  => 'Coordinate cromatiche del punto di bianco',
'exif-primarychromaticities'       => 'Coordinate cromatiche dei colori primari',
'exif-ycbcrcoefficients'           => 'Coefficienti matrice di trasformazione spazi dei colori',
'exif-referenceblackwhite'         => 'Coppia di valori di riferimento (nero e bianco)',
'exif-datetime'                    => 'Data e ora di modifica del file',
'exif-imagedescription'            => "Descrizione dell'immagine",
'exif-make'                        => 'Produttore fotocamera',
'exif-model'                       => 'Modello fotocamera',
'exif-software'                    => 'Software',
'exif-artist'                      => 'Autore',
'exif-copyright'                   => 'Informazioni sul copyright',
'exif-exifversion'                 => 'Versione del formato Exif',
'exif-flashpixversion'             => 'Versione Flashpix supportata',
'exif-colorspace'                  => 'Spazio dei colori',
'exif-componentsconfiguration'     => 'Significato di ciascuna componente',
'exif-compressedbitsperpixel'      => 'Modalità di compressione immagine',
'exif-pixelydimension'             => 'Larghezza immagine',
'exif-pixelxdimension'             => 'Altezza immagine',
'exif-usercomment'                 => "Note dell'utente",
'exif-relatedsoundfile'            => 'File audio collegato',
'exif-datetimeoriginal'            => 'Data e ora di creazione dei dati',
'exif-datetimedigitized'           => 'Data e ora di digitalizzazione',
'exif-subsectime'                  => 'Data e ora, frazioni di secondo',
'exif-subsectimeoriginal'          => 'Data e ora di creazione, frazioni di secondo',
'exif-subsectimedigitized'         => 'Data e ora di digitalizzazione, frazioni di secondo',
'exif-exposuretime'                => 'Tempo di esposizione',
'exif-exposuretime-format'         => '$1 s ($2)',
'exif-fnumber'                     => 'Rapporto focale',
'exif-exposureprogram'             => 'Programma di esposizione',
'exif-spectralsensitivity'         => 'Sensibilità spettrale',
'exif-isospeedratings'             => 'Sensibilità ISO',
'exif-shutterspeedvalue'           => "Velocità dell'otturatore APEX",
'exif-aperturevalue'               => 'Apertura APEX',
'exif-brightnessvalue'             => 'Luminosità APEX',
'exif-exposurebiasvalue'           => 'Correzione esposizione',
'exif-maxaperturevalue'            => 'Apertura massima',
'exif-subjectdistance'             => 'Distanza del soggetto',
'exif-meteringmode'                => 'Metodo di misurazione',
'exif-lightsource'                 => 'Sorgente luminosa',
'exif-flash'                       => 'Caratteristiche e stato del flash',
'exif-focallength'                 => 'Distanza focale obiettivo',
'exif-subjectarea'                 => 'Area inquadrante il soggetto',
'exif-flashenergy'                 => 'Potenza del flash',
'exif-focalplanexresolution'       => 'Risoluzione X sul piano focale',
'exif-focalplaneyresolution'       => 'Risoluzione Y sul piano focale',
'exif-focalplaneresolutionunit'    => 'Unità di misura risoluzione sul piano focale',
'exif-subjectlocation'             => 'Posizione del soggetto',
'exif-exposureindex'               => 'Sensibilità impostata',
'exif-sensingmethod'               => 'Metodo di rilevazione',
'exif-filesource'                  => 'Origine del file',
'exif-scenetype'                   => 'Tipo di inquadratura',
'exif-customrendered'              => 'Elaborazione personalizzata',
'exif-exposuremode'                => 'Modalità di esposizione',
'exif-whitebalance'                => 'Bilanciamento del bianco',
'exif-digitalzoomratio'            => 'Rapporto zoom digitale',
'exif-focallengthin35mmfilm'       => 'Focale equivalente su 35 mm',
'exif-scenecapturetype'            => 'Tipo di acquisizione',
'exif-gaincontrol'                 => 'Controllo inquadratura',
'exif-contrast'                    => 'Controllo contrasto',
'exif-saturation'                  => 'Controllo saturazione',
'exif-sharpness'                   => 'Controllo nitidezza',
'exif-devicesettingdescription'    => 'Descrizione impostazioni dispositivo',
'exif-subjectdistancerange'        => 'Scala distanza soggetto',
'exif-imageuniqueid'               => 'ID univoco immagine',
'exif-gpsversionid'                => 'Versione dei tag GPS',
'exif-gpslatituderef'              => 'Latitudine nord/sud',
'exif-gpslatitude'                 => 'Latitudine',
'exif-gpslongituderef'             => 'Longitudine est/ovest',
'exif-gpslongitude'                => 'Longitudine',
'exif-gpsaltituderef'              => "Riferimento per l'altitudine",
'exif-gpsaltitude'                 => 'Altitudine',
'exif-gpstimestamp'                => 'Ora GPS (orologio atomico)',
'exif-gpssatellites'               => 'Satelliti usati per la misurazione',
'exif-gpsstatus'                   => 'Stato del ricevitore',
'exif-gpsmeasuremode'              => 'Modalità di misurazione',
'exif-gpsdop'                      => 'Precisione della misurazione',
'exif-gpsspeedref'                 => 'Unità di misura della velocità',
'exif-gpsspeed'                    => 'Velocità del ricevitore GPS',
'exif-gpstrackref'                 => 'Riferimento per la direzione movimento',
'exif-gpstrack'                    => 'Direzione del movimento',
'exif-gpsimgdirectionref'          => "Riferimento per la direzione dell'immagine",
'exif-gpsimgdirection'             => "Direzione dell'immagine",
'exif-gpsmapdatum'                 => 'Rilevamento geodetico usato',
'exif-gpsdestlatituderef'          => 'Riferimento per la latitudine della destinazione',
'exif-gpsdestlatitude'             => 'Latitudine della destinazione',
'exif-gpsdestlongituderef'         => 'Riferimento per la longitudine della destinazione',
'exif-gpsdestlongitude'            => 'Longitudine della destinazione',
'exif-gpsdestbearingref'           => 'Riferimento per la direzione della destinazione',
'exif-gpsdestbearing'              => 'Direzione della destinazione',
'exif-gpsdestdistanceref'          => 'Riferimento per la distanza della destinazione',
'exif-gpsdestdistance'             => 'Distanza della destinazione',
'exif-gpsprocessingmethod'         => 'Nome del metodo di elaborazione GPS',
'exif-gpsareainformation'          => 'Nome della zona GPS',
'exif-gpsdatestamp'                => 'Data GPS',
'exif-gpsdifferential'             => 'Correzione differenziale GPS',
'exif-jpegfilecomment'             => 'Commento del file JPEG',
'exif-keywords'                    => 'Parole chiave',
'exif-worldregioncreated'          => "Regione del Mondo in cui l'immagine è stata scattata",
'exif-countrycreated'              => 'Paese dove è stata scattata la foto',
'exif-countrycodecreated'          => 'Codice del paese dove è stata scattata la foto',
'exif-provinceorstatecreated'      => 'Provincia o stato dove è stata scattata la foto',
'exif-citycreated'                 => 'Città dove è stata scattata la foto',
'exif-sublocationcreated'          => 'Parte della città in cui la foto è stata scattata',
'exif-worldregiondest'             => 'Regione del Mondo visualizzata',
'exif-countrydest'                 => 'Nazione visualizzata',
'exif-countrycodedest'             => 'Codice per il Paese indicato',
'exif-provinceorstatedest'         => 'Provincia o stato visualizzato',
'exif-citydest'                    => 'Città mostrata',
'exif-sublocationdest'             => 'Parte della città visualizzata',
'exif-objectname'                  => 'Titolo breve',
'exif-specialinstructions'         => 'Istruzioni speciali',
'exif-headline'                    => 'Titolo',
'exif-credit'                      => 'Crediti',
'exif-source'                      => 'Fonte',
'exif-editstatus'                  => "Stato di edizione dell'immagine",
'exif-urgency'                     => 'Urgenza',
'exif-fixtureidentifier'           => 'Nome del riferimento',
'exif-locationdest'                => 'Località raffigurata',
'exif-locationdestcode'            => 'Codice del luogo raffigurato',
'exif-objectcycle'                 => 'Momento del giorno per il quale il medium è progettato',
'exif-contact'                     => 'Informazioni di contatto',
'exif-writer'                      => 'Scrittore',
'exif-languagecode'                => 'Lingua',
'exif-iimversion'                  => 'Versione IIM',
'exif-iimcategory'                 => 'Categoria',
'exif-iimsupplementalcategory'     => 'Categorie aggiuntive',
'exif-datetimeexpires'             => 'Non utilizzare dopo',
'exif-datetimereleased'            => 'Rilasciato il',
'exif-originaltransmissionref'     => 'Codice del luogo di trasmissione originaria',
'exif-identifier'                  => 'Identificativo',
'exif-lens'                        => 'Obiettivo utilizzato',
'exif-serialnumber'                => 'Numero di serie della fotocamera',
'exif-cameraownername'             => 'Proprietario della macchina fotografica',
'exif-label'                       => 'Etichetta',
'exif-datetimemetadata'            => "Data in cui i metadata sono stati modificati l'ultima volta",
'exif-nickname'                    => "Nome informale dell'immagine",
'exif-rating'                      => 'Valutazione (su 5)',
'exif-rightscertificate'           => 'Certificato di gestione dei diritti',
'exif-copyrighted'                 => 'Informazioni sul copyright',
'exif-copyrightowner'              => 'Detentore del copyright',
'exif-usageterms'                  => 'Termini di utilizzo',
'exif-webstatement'                => 'Dichiarazione online di copyright',
'exif-originaldocumentid'          => 'ID univoco del documento originale',
'exif-licenseurl'                  => 'URL per la licenza del copyright',
'exif-morepermissionsurl'          => 'Informazioni sulle licenze alternative',
'exif-attributionurl'              => "Per il riutilizzo di quest'opera, si prega di inserire un collegamento ipertestuale a",
'exif-preferredattributionname'    => "Per il riutilizzo di quest'opera, si prega di attribuirne la paternità a",
'exif-pngfilecomment'              => 'Commento del file PNG',
'exif-disclaimer'                  => 'Avvertenze',
'exif-contentwarning'              => 'Avviso sul contenuto',
'exif-giffilecomment'              => 'Commento del file GIF',
'exif-intellectualgenre'           => 'Tipo di elemento',
'exif-subjectnewscode'             => "Codice dell'oggetto",
'exif-scenecode'                   => 'Codice di scena IPTC',
'exif-event'                       => 'Evento raffigurato',
'exif-organisationinimage'         => 'Organizzazione raffigurata',
'exif-personinimage'               => 'Persona raffigurata',
'exif-originalimageheight'         => "Altezza dell'immagine prima che fosse ritagliata",
'exif-originalimagewidth'          => "Larghezza dell'immagine prima che fosse ritagliata",

# EXIF attributes
'exif-compression-1' => 'Nessuno',
'exif-compression-2' => 'CCITT gruppo 3 monodimensionale - codifica run length di Huffman modificata',
'exif-compression-3' => 'Codifica fax CCITT Group 3',
'exif-compression-4' => 'Codifica fax CCITT gruppo 4',
'exif-compression-6' => 'JPEG (vecchio)',

'exif-copyrighted-true'  => 'Protetto da copyright',
'exif-copyrighted-false' => 'Pubblico dominio',

'exif-unknowndate' => 'Data sconosciuta',

'exif-orientation-1' => 'Normale',
'exif-orientation-2' => 'Capovolto orizzontalmente',
'exif-orientation-3' => 'Ruotato di 180°',
'exif-orientation-4' => 'Capovolto verticalmente',
'exif-orientation-5' => 'Ruotato 90° in senso antiorario e capovolto verticalmente',
'exif-orientation-6' => 'Ruotato di 90° in senso antiorario',
'exif-orientation-7' => 'Ruotato 90° in senso orario e capovolto verticalmente',
'exif-orientation-8' => 'Ruotato di 90° in senso orario',

'exif-planarconfiguration-1' => 'a blocchi (chunky)',
'exif-planarconfiguration-2' => 'lineare (planar)',

'exif-xyresolution-i' => '$1 punti per pollice (dpi)',
'exif-xyresolution-c' => '$1 punti per centimetro (dpc)',

'exif-colorspace-65535' => 'Non calibrato',

'exif-componentsconfiguration-0' => 'assente',

'exif-exposureprogram-0' => 'Non definito',
'exif-exposureprogram-1' => 'Manuale',
'exif-exposureprogram-2' => 'Standard',
'exif-exposureprogram-3' => 'Priorità al diaframma',
'exif-exposureprogram-4' => "Priorità all'esposizione",
'exif-exposureprogram-5' => 'Artistico (orientato alla profondità di campo)',
'exif-exposureprogram-6' => 'Sportivo (orientato alla velocità di ripresa)',
'exif-exposureprogram-7' => 'Ritratto (soggetti vicini con sfondo fuori fuoco)',
'exif-exposureprogram-8' => 'Panorama (soggetti lontani con sfondo a fuoco)',

'exif-subjectdistance-value' => '$1 metri',

'exif-meteringmode-0'   => 'Sconosciuto',
'exif-meteringmode-1'   => 'Media',
'exif-meteringmode-2'   => 'Media pesata centrata',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Pattern',
'exif-meteringmode-6'   => 'Parziale',
'exif-meteringmode-255' => 'Altro',

'exif-lightsource-0'   => 'Sconosciuta',
'exif-lightsource-1'   => 'Luce diurna',
'exif-lightsource-2'   => 'Lampada a fluorescenza',
'exif-lightsource-3'   => 'Lampada al tungsteno (a incandescenza)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Bel tempo',
'exif-lightsource-10'  => 'Nuvoloso',
'exif-lightsource-11'  => 'In ombra',
'exif-lightsource-12'  => 'Daylight fluorescent (D 5700 - 7100K)',
'exif-lightsource-13'  => 'Day white fluorescent (N 4600 - 5400K)',
'exif-lightsource-14'  => 'Cool white fluorescent (W 3900 - 4500K)',
'exif-lightsource-15'  => 'White fluorescent (WW 3200 - 3700K)',
'exif-lightsource-17'  => 'Luce standard A',
'exif-lightsource-18'  => 'Luce standard B',
'exif-lightsource-19'  => 'Luce standard C',
'exif-lightsource-20'  => 'Illuminante D55',
'exif-lightsource-21'  => 'Illuminante D65',
'exif-lightsource-22'  => 'Illuminante D75',
'exif-lightsource-23'  => 'Illuminante D50',
'exif-lightsource-24'  => 'Lampada da studio ISO al tungsteno',
'exif-lightsource-255' => 'Altra sorgente luminosa',

# Flash modes
'exif-flash-fired-0'    => 'Il flash non è scattato',
'exif-flash-fired-1'    => 'Flash scattato',
'exif-flash-return-0'   => 'nessuna funzione di individuazione del ritorno della luce stroboscopica',
'exif-flash-return-2'   => 'luce stroboscopica di ritorno non individuata',
'exif-flash-return-3'   => 'luce stroboscopica di ritorno individuata',
'exif-flash-mode-1'     => 'attivazione flash forzato',
'exif-flash-mode-2'     => 'rimozione flash forzato',
'exif-flash-mode-3'     => 'modalità automatica',
'exif-flash-function-1' => 'Disattiva flash',
'exif-flash-redeye-1'   => 'modalità riduzione occhi rossi',

'exif-focalplaneresolutionunit-2' => 'pollici',

'exif-sensingmethod-1' => 'Non definito',
'exif-sensingmethod-2' => 'Sensore area colore a 1 chip',
'exif-sensingmethod-3' => 'Sensore area colore a 2 chip',
'exif-sensingmethod-4' => 'Sensore area colore a 3 chip',
'exif-sensingmethod-5' => 'Sensore area colore sequenziale',
'exif-sensingmethod-7' => 'Sensore trilineare',
'exif-sensingmethod-8' => 'Sensore lineare colore sequenziale',

'exif-filesource-3' => 'Fotocamera digitale',

'exif-scenetype-1' => 'Fotografia diretta',

'exif-customrendered-0' => 'Processo normale',
'exif-customrendered-1' => 'Processo personalizzato',

'exif-exposuremode-0' => 'Esposizione automatica',
'exif-exposuremode-1' => 'Esposizione manuale',
'exif-exposuremode-2' => 'Bracketing automatico',

'exif-whitebalance-0' => 'Bilanciamento del bianco automatico',
'exif-whitebalance-1' => 'Bilanciamento del bianco manuale',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Panorama',
'exif-scenecapturetype-2' => 'Ritratto',
'exif-scenecapturetype-3' => 'Notturna',

'exif-gaincontrol-0' => 'Nessuno',
'exif-gaincontrol-1' => 'Enfasi per basso guadagno',
'exif-gaincontrol-2' => 'Enfasi per alto guadagno',
'exif-gaincontrol-3' => 'Deenfasi per basso guadagno',
'exif-gaincontrol-4' => 'Deenfasi per alto guadagno',

'exif-contrast-0' => 'Normale',
'exif-contrast-1' => 'Alto contrasto',
'exif-contrast-2' => 'Basso contrasto',

'exif-saturation-0' => 'Normale',
'exif-saturation-1' => 'Bassa saturazione',
'exif-saturation-2' => 'Alta saturazione',

'exif-sharpness-0' => 'Normale',
'exif-sharpness-1' => 'Minore nitidezza',
'exif-sharpness-2' => 'Maggiore nitidezza',

'exif-subjectdistancerange-0' => 'Sconosciuta',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Soggetto vicino',
'exif-subjectdistancerange-3' => 'Soggetto lontano',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitudine nord',
'exif-gpslatitude-s' => 'Latitudine sud',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitudine est',
'exif-gpslongitude-w' => 'Longitudine ovest',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metro|metri}} sul livello del mare',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metro|metri}} sotto il livello del mare',

'exif-gpsstatus-a' => 'Misurazione in corso',
'exif-gpsstatus-v' => 'Misurazione interoperabile',

'exif-gpsmeasuremode-2' => 'Misurazione bidimensionale',
'exif-gpsmeasuremode-3' => 'Misurazione tridimensionale',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Chilometri orari',
'exif-gpsspeed-m' => 'Miglia orarie',
'exif-gpsspeed-n' => 'Nodi',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Chilometri',
'exif-gpsdestdistance-m' => 'Miglia',
'exif-gpsdestdistance-n' => 'Miglia nautiche',

'exif-gpsdop-excellent' => 'Eccellente ($1)',
'exif-gpsdop-good'      => 'Buono ($1)',
'exif-gpsdop-moderate'  => 'Moderata ($1)',
'exif-gpsdop-fair'      => 'Discreto ($1)',
'exif-gpsdop-poor'      => 'Scarso ($1)',

'exif-objectcycle-a' => 'Solo la mattina',
'exif-objectcycle-p' => 'Solo la sera',
'exif-objectcycle-b' => 'Mattina e sera',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direzione reale',
'exif-gpsdirection-m' => 'Direzione magnetica',

'exif-ycbcrpositioning-1' => 'Centrato',
'exif-ycbcrpositioning-2' => 'Co-situato',

'exif-dc-contributor' => 'Collaboratori',
'exif-dc-coverage'    => 'Ambito spaziale o temporale dei media',
'exif-dc-date'        => 'Data (e)',
'exif-dc-publisher'   => 'Editore',
'exif-dc-relation'    => 'File correlati',
'exif-dc-rights'      => 'Diritti',
'exif-dc-source'      => 'Fonte del file',
'exif-dc-type'        => 'Tipologia di file',

'exif-rating-rejected' => 'Rifiutato',

'exif-isospeedratings-overflow' => 'Maggiore di 65535',

'exif-iimcategory-ace' => 'Arte, cultura e spettacolo',
'exif-iimcategory-clj' => 'Criminalità e diritto',
'exif-iimcategory-dis' => 'Disastri e incidenti',
'exif-iimcategory-fin' => 'Economia e affari',
'exif-iimcategory-edu' => 'Istruzione',
'exif-iimcategory-evn' => 'Ambiente',
'exif-iimcategory-hth' => 'Salute',
'exif-iimcategory-hum' => 'Interesse umano',
'exif-iimcategory-lab' => 'Lavoro',
'exif-iimcategory-lif' => 'Stile di vita e tempo libero',
'exif-iimcategory-pol' => 'Politica',
'exif-iimcategory-rel' => 'Religione e fede',
'exif-iimcategory-sci' => 'Scienza e tecnologia',
'exif-iimcategory-soi' => 'Questioni sociali',
'exif-iimcategory-spo' => 'Sport',
'exif-iimcategory-war' => 'Guerra, conflitti e disordini',
'exif-iimcategory-wea' => 'Meteo',

'exif-urgency-normal' => 'Normale ($1)',
'exif-urgency-low'    => 'Bassa ($1)',
'exif-urgency-high'   => 'Alta ($1)',
'exif-urgency-other'  => "Priorità definite dal'utente ($1)",

# External editor support
'edit-externally'      => 'Modifica questo file usando un programma esterno',
'edit-externally-help' => '(Per maggiori informazioni consultare le [//www.mediawiki.org/wiki/Manual:External_editors istruzioni])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tutte',
'namespacesall' => 'tutti',
'monthsall'     => 'tutti',
'limitall'      => 'tutti',

# E-mail address confirmation
'confirmemail'              => 'Conferma indirizzo email',
'confirmemail_noemail'      => 'Non è stato indicato un indirizzo e-mail valido nelle proprie [[Special:Preferences|preferenze]].',
'confirmemail_text'         => "{{SITENAME}} richiede la verifica dell'indirizzo e-mail prima di poter usare le relative funzioni. Premere il pulsante qui sotto per inviare una richiesta di conferma al proprio indirizzo; nel messaggio è presente un collegamento che contiene un codice. Visitare il collegamento con il proprio browser per confermare che l'indirizzo e-mail è valido.",
'confirmemail_pending'      => "Il codice di conferma è già stato spedito via posta elettronica; se l'account è stato
creato di recente, si prega di attendere l'arrivo del codice per qualche minuto prima
di tentare di richiederne uno nuovo.",
'confirmemail_send'         => 'Invia un codice di conferma via e-mail.',
'confirmemail_sent'         => 'Messaggio e-mail di conferma inviato.',
'confirmemail_oncreate'     => "Un codice di conferma è stato spedito all'indirizzo
di posta elettronica indicato. Il codice non è necessario per accedere al sito,
ma è necessario fornirlo per poter abilitare tutte le funzioni del sito che fanno
uso della posta elettronica.",
'confirmemail_sendfailed'   => '{{SITENAME}} non può inviare il messaggio e-mail di conferma. Verificare che il proprio indirizzo e-mail non contenga caratteri non validi.

Messaggio di errore del mailer: $1',
'confirmemail_invalid'      => 'Codice di conferma non valido. Il codice potrebbe essere scaduto.',
'confirmemail_needlogin'    => 'È necessario $1 per confermare il proprio indirizzo e-mail.',
'confirmemail_success'      => "L'indirizzo e-mail è confermato. Ora è possibile [[Special:UserLogin|eseguire l'accesso]] e fare pieno uso del sito.",
'confirmemail_loggedin'     => "L'indirizzo e-mail è stato confermato.",
'confirmemail_error'        => 'Errore nel salvataggio della conferma.',
'confirmemail_subject'      => "{{SITENAME}}: richiesta di conferma dell'indirizzo",
'confirmemail_body'         => 'Qualcuno, probabilmente tu stesso dall\'indirizzo IP $1, ha registrato l\'account "$2" su {{SITENAME}} indicando questo indirizzo e-mail.

Per confermare che l\'account ti appartiene veramente e attivare le funzioni relative all\'invio di e-mail su {{SITENAME}}, apri il collegamento seguente con il tuo browser:

$3

Se *non* hai registrato tu l\'account, segui questo collegamento per annullare la conferma dell\'indirizzo e-mail:

$5

Questo codice di conferma scadrà automaticamente alle $4.',
'confirmemail_body_changed' => 'Qualcuno, probabilmente tu stesso dall\'indirizzo IP $1,
ha modificato l\'indirizzo e-mail dell\'account "$2" su {{SITENAME}} indicando questo indirizzo e-mail.

Per confermare che l\'account ti appartiene veramente e riattivare le funzioni relative all\'invio
di e-mail su {{SITENAME}}, apri il collegamento seguente con il tuo browser:

$3

Se l\'account *non* ti appartiene, segui questo collegamento
per annullare la conferma dell\'indirizzo e-mail:

$5

Questo codice di conferma scadrà automaticamente alle $4.',
'confirmemail_body_set'     => 'Qualcuno, probabilmente tu stesso dall\'indirizzo IP $1,
ha impostato l\'indirizzo e-mail dell\'account "$2" su {{SITENAME}} indicando questo indirizzo e-mail.

Per confermare che l\'account ti appartiene veramente e riattivare le funzioni relative all\'invio
di e-mail su {{SITENAME}}, apri il collegamento seguente con il tuo browser:

$3

Se l\'account *non* ti appartiene, segui questo collegamento
per annullare la conferma dell\'indirizzo e-mail:

$5

Questo codice di conferma scadrà automaticamente alle $4.',
'confirmemail_invalidated'  => 'Richiesta di conferma indirizzo e-mail annullata',
'invalidateemail'           => 'Annulla richiesta di conferma e-mail',

# Scary transclusion
'scarytranscludedisabled' => "[L'inclusione di pagine tra siti wiki non è attiva]",
'scarytranscludefailed'   => '[Errore: Impossibile ottenere il template $1]',
'scarytranscludetoolong'  => '[Errore: URL troppo lunga]',

# Delete conflict
'deletedwhileediting'      => "'''Attenzione''': questa pagina è stata cancellata dopo che hai cominciato a modificarla!",
'confirmrecreate'          => "L'utente [[User:$1|$1]] ([[User talk:$1|discussioni]]) ha cancellato questa pagina dopo che hai iniziato a modificarla, per il seguente motivo: ''$2''
Per favore, conferma che desideri veramente ricreare questa pagina.",
'confirmrecreate-noreason' => "L'utente [[User:$1|$1]] ([[User talk:$1|discussioni]]) ha cancellato questa pagina dopo che hai iniziato a modificarla.
Per favore, conferma che desideri veramente ricreare questa pagina.",
'recreate'                 => 'Ricrea',

# action=purge
'confirm_purge_button' => 'Conferma',
'confirm-purge-top'    => 'Vuoi pulire la cache di questa pagina?',
'confirm-purge-bottom' => 'Ripulire la cache di una pagina consente di mostrare la sua versione più aggiornata.',

# action=watch/unwatch
'confirm-watch-button'   => 'OK',
'confirm-watch-top'      => 'Aggiungi questa pagina alla tua lista degli osservati speciali?',
'confirm-unwatch-button' => 'OK',
'confirm-unwatch-top'    => 'Elimina questa pagina dalla tua lista degli osservati speciali?',

# Multipage image navigation
'imgmultipageprev' => '← pagina precedente',
'imgmultipagenext' => 'pagina seguente →',
'imgmultigo'       => 'Vai',
'imgmultigoto'     => 'Vai alla pagina $1',

# Table pager
'ascending_abbrev'         => 'cresc',
'descending_abbrev'        => 'decresc',
'table_pager_next'         => 'Pagina successiva',
'table_pager_prev'         => 'Pagina precedente',
'table_pager_first'        => 'Prima pagina',
'table_pager_last'         => 'Ultima pagina',
'table_pager_limit'        => 'Mostra $1 file per pagina',
'table_pager_limit_label'  => 'Elementi per pagina:',
'table_pager_limit_submit' => 'Vai',
'table_pager_empty'        => 'Nessun risultato',

# Auto-summaries
'autosumm-blank'   => 'Pagina svuotata',
'autosumm-replace' => "Pagina sostituita con '$1'",
'autoredircomment' => 'Redirect alla pagina [[$1]]',
'autosumm-new'     => "Creata pagina con '$1'",

# Size units
'size-bytes' => '$1 byte',

# Live preview
'livepreview-loading' => 'Caricamento in corso...',
'livepreview-ready'   => 'Caricamento in corso… Pronto.',
'livepreview-failed'  => "Errore nella funzione Live preview.
Usare l'anteprima standard.",
'livepreview-error'   => 'Impossibile effettuare il collegamento: $1 "$2"
Usare l\'anteprima standard.',

# Friendlier slave lag warnings
'lag-warn-normal' => "Le modifiche apportate {{PLURAL:$1|nell'ultimo secondo|negli ultimi $1 secondi}} potrebbero non apparire in questa lista.",
'lag-warn-high'   => "A causa di un eccessivo ritardo nell'aggiornamento del server di database, le modifiche apportate {{PLURAL:$1|nell'ultimo secondo|negli ultimi $1 secondi}} potrebbero non apparire in questa lista.",

# Watchlist editor
'watchlistedit-numitems'       => 'La lista degli osservati speciali contiene {{PLURAL:$1|una pagina (e la rispettiva pagina di discussione)|$1 pagine (e le rispettive pagine di discussione)}}.',
'watchlistedit-noitems'        => 'La lista degli osservati speciali è vuota.',
'watchlistedit-normal-title'   => 'Modifica osservati speciali',
'watchlistedit-normal-legend'  => 'Eliminazione di pagine dagli osservati speciali',
'watchlistedit-normal-explain' => 'Di seguito sono elencate tutte le pagine osservate.
Per rimuovere una o più pagine dalla lista, selezionare le caselle relative e fare clic sul pulsante "{{int:Watchlistedit-normal-submit}}" in fondo all\'elenco.
Si noti che è anche possibile [[Special:EditWatchlist/raw|modificare la lista in formato testuale]].',
'watchlistedit-normal-submit'  => 'Elimina pagine',
'watchlistedit-normal-done'    => 'Dalla lista degli osservati speciali {{PLURAL:$1|è stata eliminata una pagina|sono state eliminate $1 pagine}}:',
'watchlistedit-raw-title'      => 'Modifica degli osservati speciali in forma testuale',
'watchlistedit-raw-legend'     => 'Modifica testuale osservati speciali',
'watchlistedit-raw-explain'    => 'Di seguito sono elencate tutte le pagine osservate. Per modificare la lista aggiungere o rimuovere i rispettivi titoli, uno per riga.
Una volta terminato, fare clic su "{{int:Watchlistedit-raw-submit}}" in fondo all\'elenco.
Si noti che è anche possibile [[Special:EditWatchlist|modificare la lista con l\'interfaccia standard]].',
'watchlistedit-raw-titles'     => 'Titoli delle pagine:',
'watchlistedit-raw-submit'     => 'Aggiorna la lista',
'watchlistedit-raw-done'       => 'La lista degli osservati speciali è stata aggiornata.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|È stata aggiunta una pagina|Sono state aggiunte $1 pagine}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|È stata eliminata una pagina|Sono state eliminate $1 pagine}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Visualizza le modifiche pertinenti',
'watchlisttools-edit' => 'Visualizza e modifica la lista degli osservati speciali',
'watchlisttools-raw'  => 'Modifica la lista in formato testo',

# Hebrew month names
'hebrew-calendar-m10'     => 'Tammuz',
'hebrew-calendar-m10-gen' => 'Tammuz',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|discussioni]])',

# Core parser functions
'unknown_extension_tag' => 'Tag estensione sconosciuto: "$1"',
'duplicate-defaultsort' => 'Attenzione: la chiave di ordinamento predefinita "$2" sostituisce la precedente "$1".',

# Special:Version
'version'                       => 'Versione',
'version-extensions'            => 'Estensioni installate',
'version-specialpages'          => 'Pagine speciali',
'version-parserhooks'           => 'Hook del parser',
'version-variables'             => 'Variabili',
'version-antispam'              => 'Prevenzione dello spam',
'version-skins'                 => 'Skin',
'version-other'                 => 'Altro',
'version-mediahandlers'         => 'Gestori di contenuti multimediali',
'version-hooks'                 => 'Hook',
'version-extension-functions'   => 'Funzioni introdotte da estensioni',
'version-parser-extensiontags'  => 'Tag riconosciuti dal parser introdotti da estensioni',
'version-parser-function-hooks' => 'Hook per funzioni del parser',
'version-hook-name'             => "Nome dell'hook",
'version-hook-subscribedby'     => 'Sottoscrizioni',
'version-version'               => '(Versione $1)',
'version-license'               => 'Licenza',
'version-poweredby-credits'     => "Questo wiki è realizzato con '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others'      => 'altri',
'version-license-info'          => 'MediaWiki è un software libero; puoi redistribuirlo e/o modificarlo secondo i termini della GNU General Public License, come pubblicata dalla Free Software Foundation; o la versione 2 della Licenza o (a propria scelta) qualunque versione successiva.

MediaWiki è distribuito nella speranza che sia utile, ma SENZA ALCUNA GARANZIA; senza neppure la garanzia implicita di NEGOZIABILITÀ o di APPLICABILITÀ PER UN PARTICOLARE SCOPO. Si veda la GNU General Public License per maggiori dettagli.

Questo programma deve essere distribuito assieme ad [{{SERVER}}{{SCRIPTPATH}}/COPYING una copia della GNU General Public License]; in caso contrario, se ne può ottenere una scrivendo alla Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA oppure [http://www.softwarelibero.it/gnudoc/gpl.it.txt leggerla in rete].',
'version-software'              => 'Software installato',
'version-software-product'      => 'Prodotto',
'version-software-version'      => 'Versione',

# Special:FilePath
'filepath'         => 'Percorso di un file',
'filepath-page'    => 'Nome del file:',
'filepath-submit'  => 'Vai',
'filepath-summary' => 'Questa pagina speciale restituisce il percorso completo di un file.
Le immagini vengono mostrate alla massima risoluzione disponibile, per gli altri tipi di file viene avviato direttamente il programma associato.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Ricerca dei file duplicati',
'fileduplicatesearch-summary'   => "Ricerca di eventuali duplicati del file in base al valore di ''hash''.",
'fileduplicatesearch-legend'    => 'Ricerca di un duplicato',
'fileduplicatesearch-filename'  => 'Nome del file:',
'fileduplicatesearch-submit'    => 'Ricerca',
'fileduplicatesearch-info'      => '$1 × $2 pixel<br />Dimensione del file: $3<br />Tipo MIME: $4',
'fileduplicatesearch-result-1'  => 'Non esistono duplicati identici al file "$1".',
'fileduplicatesearch-result-n'  => '{{PLURAL:$2|Esiste un duplicato identico|Esistono $2 duplicati identici}} al file "$1".',
'fileduplicatesearch-noresults' => 'Nessun file di nome "$1" trovato.',

# Special:SpecialPages
'specialpages'                   => 'Pagine speciali',
'specialpages-note'              => '----
* Pagine speciali non riservate.
* <span class="mw-specialpagerestricted">Pagine speciali riservate ad alcune categorie di utenti.</span>
* <span class="mw-specialpagecached">Pagine speciali disponibili in versione cache (potrebbero essere obsolete).</span>',
'specialpages-group-maintenance' => 'Resoconti di manutenzione',
'specialpages-group-other'       => 'Altre pagine speciali',
'specialpages-group-login'       => 'Accesso / creazione utenze',
'specialpages-group-changes'     => 'Ultime modifiche e registri',
'specialpages-group-media'       => 'File multimediali - caricamento e resoconti',
'specialpages-group-users'       => 'Utenti e diritti',
'specialpages-group-highuse'     => 'Pagine molto usate',
'specialpages-group-pages'       => 'Elenchi di pagine',
'specialpages-group-pagetools'   => 'Strumenti utili per le pagine',
'specialpages-group-wiki'        => 'Strumenti e informazioni sul progetto',
'specialpages-group-redirects'   => 'Pagine speciali di redirect',
'specialpages-group-spam'        => 'Strumenti contro lo spam',

# Special:BlankPage
'blankpage'              => 'Pagina vuota',
'intentionallyblankpage' => 'Questa pagina è lasciata volutamente vuota ed è usata per benchmark, ecc.',

# External image whitelist
'external_image_whitelist' => " #Lasciare questa riga esattamente com'è<pre>
#Inserire i frammenti delle espressioni regolari (solo la parte che va fra //) di seguito
#Queste verranno messe a confronto con gli indirizzi URL delle immagini esterne (hotlinked)
#Le corrispondenze saranno mostrate come immagini, altrimenti verrà mostrato solo un collegamento
#Le righe che iniziano con # sono considerate dei commenti
#La differenza tra maiuscole e minuscole non è significativa

#Inserire sopra questa riga tutti i frammenti di regex. Lasciare questa riga esattamente com'è</pre>",

# Special:Tags
'tags'                    => 'Etichette di modifiche valide',
'tag-filter'              => 'Filtra per [[Special:Tags|etichetta]]:',
'tag-filter-submit'       => 'Filtra',
'tags-title'              => 'Etichette',
'tags-intro'              => 'Questa pagina elenca le etichette che il software potrebbe associare a una modifica e il loro significato.',
'tags-tag'                => "Nome dell'etichetta",
'tags-display-header'     => 'Aspetto nella lista delle modifiche',
'tags-description-header' => 'Descrizione completa del significato',
'tags-hitcount-header'    => 'Modifiche che hanno etichetta',
'tags-edit'               => 'modifica',
'tags-hitcount'           => '$1 {{PLURAL:$1|modifica|modifiche}}',

# Special:ComparePages
'comparepages'                => 'Confronta le pagine',
'compare-selector'            => 'Confronta le revisioni di una pagina',
'compare-page1'               => 'Pagina 1',
'compare-page2'               => 'Pagina 2',
'compare-rev1'                => 'Revisione 1',
'compare-rev2'                => 'Revisione 2',
'compare-submit'              => 'Confronta',
'compare-invalid-title'       => 'Il titolo che hai specificato non è valido.',
'compare-title-not-exists'    => 'Il titolo che hai specificato non esiste.',
'compare-revision-not-exists' => 'La revisione che hai specificato non esiste.',

# Database error messages
'dberr-header'      => 'Questa wiki ha un problema',
'dberr-problems'    => 'Questo sito sta avendo dei problemi tecnici.',
'dberr-again'       => 'Prova ad attendere qualche minuto e ricaricare.',
'dberr-info'        => '(Impossibile contattare il server del database: $1)',
'dberr-usegoogle'   => 'Puoi provare a cercare su Google nel frattempo.',
'dberr-outofdate'   => 'Nota che la loro indicizzazione dei nostri contenuti potrebbe non essere aggiornata.',
'dberr-cachederror' => 'Quella che segue è una copia cache della pagina richiesta, e potrebbe non essere aggiornata.',

# HTML forms
'htmlform-invalid-input'       => "Ci sono problemi con l'input inserito",
'htmlform-select-badoption'    => "Il valore specificato non è un'opzione valida.",
'htmlform-int-invalid'         => 'Il valore specificato non è un intero.',
'htmlform-float-invalid'       => 'Il valore specificato non è un numero.',
'htmlform-int-toolow'          => 'Il valore specificato è inferiore al minimo di $1',
'htmlform-int-toohigh'         => 'Il valore specificato è superiore al massimo di $1',
'htmlform-required'            => 'Questo valore è necessario',
'htmlform-submit'              => 'Invia',
'htmlform-reset'               => 'Annulla modifiche',
'htmlform-selectorother-other' => 'Altro',

# SQLite database support
'sqlite-has-fts' => '$1 con la possibilità di ricerca completa nel testo',
'sqlite-no-fts'  => '$1 senza la possibilità di ricerca completa nel testo',

# New logging system
'logentry-delete-delete'              => '$1 ha cancellato la pagina $3',
'logentry-delete-restore'             => '$1 ha ripristinato "$3"',
'logentry-delete-event'               => '$1 ha modificato la visibilità di {{PLURAL:$5|un\'azione del registro|$5 azioni del registro}} di "$3": $4',
'logentry-delete-revision'            => '$1 ha modificato la visibilità per {{PLURAL:$5|una revisione|$5 revisioni}} della pagina $3: $4',
'logentry-delete-event-legacy'        => '$1 ha modificato la visibilità di alcune azioni del registro di "$3"',
'logentry-delete-revision-legacy'     => '$1 ha modificato la visibilità per le revisioni della pagina $3',
'logentry-suppress-delete'            => '$1 ha nascosto la pagina "$3"',
'logentry-suppress-event'             => '$1 ha segretamente modificato la visibilità di {{PLURAL:$5|un\'azione del registro|$5 azioni del registro}} di "$3": $4',
'logentry-suppress-revision'          => '$1 ha segretamente modificato la visibilità di {{PLURAL:$5|una versione|$5 versioni}} di "$3": $4',
'logentry-suppress-event-legacy'      => '$1 ha segretamente modificato la visibilità di alcune azioni del registro di "$3"',
'logentry-suppress-revision-legacy'   => '$1 ha segretamente modificato la visibilità di alcune versioni di "$3"',
'revdelete-content-hid'               => 'contenuto nascosto',
'revdelete-summary-hid'               => 'oggetto della modifica nascosto',
'revdelete-uname-hid'                 => 'nome utente nascosto',
'revdelete-content-unhid'             => 'contenuto ripristinato',
'revdelete-summary-unhid'             => 'oggetto ripristinato',
'revdelete-uname-unhid'               => 'nome utente ripristinato',
'revdelete-restricted'                => 'limitazioni ai soli amministratori attivate',
'revdelete-unrestricted'              => 'limitazioni ai soli amministratori rimosse',
'logentry-move-move'                  => '$1 ha spostato la pagina $3 a $4',
'logentry-move-move-noredirect'       => '$1 ha spostato la pagina $3 a $4 senza lasciare redirect',
'logentry-move-move_redir'            => '$1 ha spostato la pagina $3 a $4 tramite redirect',
'logentry-move-move_redir-noredirect' => '$1 ha spostato la pagina $3 a $4 al posto di un redirect senza lasciare redirect',
'logentry-patrol-patrol'              => '$1 ha segnato la versione $4 della pagina $3 come verificata',
'logentry-patrol-patrol-auto'         => '$1 ha segnato automaticamente la versione $4 della pagina $3 come verificata',
'logentry-newusers-newusers'          => "L'account utente $1 è stato creato",
'logentry-newusers-create'            => "L'account utente $1 è stato creato",
'logentry-newusers-create2'           => "L'account utente $3 è stato creato da $1",
'logentry-newusers-autocreate'        => "L'utenza $1 è stata creata automaticamente",
'newuserlog-byemail'                  => 'password inviata via mail',

# Feedback
'feedback-bugornote' => 'Se si è in grado di descrivere il problema tecnico riscontrato in maniera precisa, [$1 segnalate il bug]. In alternativa, si può usare il modulo semplificato sottostante. Il commento inserito sarà aggiunto alla pagina "[$3 $2]", insieme al proprio nome utente e al browser in uso.',
'feedback-subject'   => 'Oggetto:',
'feedback-message'   => 'Messaggio:',
'feedback-cancel'    => 'Annulla',
'feedback-submit'    => 'Invia feedback',
'feedback-adding'    => 'Inserimento del feedback nella pagina...',
'feedback-error1'    => 'Errore: Dalla API è arrivato un risultato non riconosciuto',
'feedback-error2'    => 'Errore: Non è stato possibile eseguire la modifica',
'feedback-error3'    => 'Errore: Nessuna risposta dalla API',
'feedback-thanks'    => 'Grazie! Il tuo feedback è stato pubblicato alla pagina "[$2 $1]".',
'feedback-close'     => 'Fatto',
'feedback-bugcheck'  => 'Ottimo! Verifica che non sia già fra i [$1 bug conosciuti].',
'feedback-bugnew'    => 'Controllo effettuato. Segnala un nuovo bug',

# API errors
'api-error-badaccess-groups'              => 'Non sei autorizzato a caricare documenti su questa wiki.',
'api-error-badtoken'                      => 'Errore interno: token errato.',
'api-error-copyuploaddisabled'            => 'Il caricamento tramite URL è disabilitato su questo server.',
'api-error-duplicate'                     => "Sul sito {{PLURAL:$1|c'è già [$2 un altro documento]|ci sono già [$2 altri documenti]}} con lo stesso contenuto.",
'api-error-duplicate-archive'             => "{{PLURAL:$1|C'era [$2 un altro file]|C'erano [$2 altri file]}} già nel sito con lo stesso contenuto, ma {{PLURAL:$1|è stato cancellato|sono stati cancellati}}.",
'api-error-duplicate-archive-popup-title' => 'File duplicat{{PLURAL:$1|o che è già stato cancellato|i che sono già stati cancellati}}',
'api-error-duplicate-popup-title'         => '{{PLURAL:$1|documento duplicato|documenti duplicati}}',
'api-error-empty-file'                    => 'Il file selezionato era vuoto.',
'api-error-emptypage'                     => 'La creazione di nuove pagine vuote non è consentita.',
'api-error-fetchfileerror'                => "Errore interno: c'è stato un problema durante il recupero del documento.",
'api-error-file-too-large'                => 'Il file selezionato era troppo grande.',
'api-error-filename-tooshort'             => 'Il nome del file è troppo breve.',
'api-error-filetype-banned'               => 'Questo tipo di file non è accettato.',
'api-error-filetype-missing'              => "Al file manca l'estensione.",
'api-error-hookaborted'                   => "La modifica hai tentato di fare è stata interrotta da un passaggio dell'estensione.",
'api-error-http'                          => 'Errore interno: impossibile connettersi al server.',
'api-error-illegal-filename'              => 'Il nome del file non è ammesso.',
'api-error-internal-error'                => "Errore interno: qualcosa è andato storto con l'elaborazione del tuo caricamento sulla wiki.",
'api-error-invalid-file-key'              => 'Errore interno: file non presente nella cartella dei file temporanei.',
'api-error-missingparam'                  => 'Errore interno: parametri della richiesta mancanti.',
'api-error-missingresult'                 => 'Errore interno: impossibile determinare se la copia è riuscita.',
'api-error-mustbeloggedin'                => "Devi aver effettuato l'accesso per caricare i file.",
'api-error-mustbeposted'                  => 'Errore interno: la richiesta richiede HTTP POST.',
'api-error-noimageinfo'                   => 'Il caricamento è riuscito, ma il server non ci ha dato alcuna informazione sul file.',
'api-error-nomodule'                      => 'Errore interno: non è stato impostato il modulo di caricamento.',
'api-error-ok-but-empty'                  => 'Errore interno: nessuna risposta dal server.',
'api-error-overwrite'                     => 'Sovrascrivere un file esistente non è consentito.',
'api-error-stashfailed'                   => 'Errore interno: il server non è riuscito a memorizzare il documento temporaneo.',
'api-error-timeout'                       => 'Il server non ha risposto entro il tempo previsto.',
'api-error-unclassified'                  => 'Si è verificato un errore sconosciuto.',
'api-error-unknown-code'                  => 'Errore sconosciuto: "$1"',
'api-error-unknown-error'                 => 'Errore interno: qualcosa è andato storto provando a caricare il file.',
'api-error-unknown-warning'               => 'Avviso sconosciuto: $1',
'api-error-unknownerror'                  => 'Errore sconosciuto: "$1".',
'api-error-uploaddisabled'                => 'Il caricamento è disabilitato su questa wiki.',
'api-error-verification-error'            => "Questo file potrebbe essere danneggiato, o avere l'estensione sbagliata.",

);
