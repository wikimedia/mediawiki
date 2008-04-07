<?php
/** Vèneto (Vèneto)
 *
 * @addtogroup Language
 *
 * @author Nick1915
 * @author BrokenArrow
 * @author לערי ריינהארט
 * @author Nike
 * @author Jon Harald Søby
 * @author Siebrand
 * @author SPQRobin
 * @author Candalua
 */

$fallback = 'it';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciale',
	NS_MAIN             => '',
	NS_TALK             => 'Discussion',
	NS_USER             => 'Utente',
	NS_USER_TALK        => 'Discussion_utente',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Discussion_$1',
	NS_IMAGE            => 'Imagine',
	NS_IMAGE_TALK       => 'Discussion_imagine',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussion_MediaWiki',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Discussion_template',
	NS_HELP             => 'Aiuto',
	NS_HELP_TALK        => 'Discussion_aiuto',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Discussion_categoria'
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Sottolinea links',
'tog-highlightbroken'         => 'Evidenzsia i links che i punta a arthicołi ancora da scrivere',
'tog-justify'                 => 'Paragrafo: giustificato',
'tog-hideminor'               => 'Nascondi le modifiche minori nella pagina "Modifiche recenti"',
'tog-extendwatchlist'         => 'Espandi la funzion osservati speciali mostrando tute le modifiche aplicabili',
'tog-usenewrc'                => 'Ultime modifiche avanzade (ghe vol JavaScript)',
'tog-numberheadings'          => 'Auto-numerazione dei titoli di paragrafo',
'tog-showtoolbar'             => 'Mostra la barra dei strumenti de modifica (ghe vol JavaScript)',
'tog-editondblclick'          => "Doppio click per modificare l'articolo (richiede JavaScript)",
'tog-editsection'             => 'Modifega de łe sezsioni tramite el cołegamento [modifica]',
'tog-editsectiononrightclick' => 'Modifega de łe sezsion tramite clic destro sul titoło (richiede JavaScript)',
'tog-showtoc'                 => "Mostra l'indexe par łe pàxene con pì de 3 sezion",
'tog-rememberpassword'        => 'Ricorda la password (non limitare a una sessione - richiede uso di cookies)',
'tog-editwidth'               => 'Casella di edizione ampliata alla massima larghezza',
'tog-watchcreations'          => 'Xonta łe pàxene creàe a i osservati speciałi',
'tog-watchdefault'            => 'Xonta łe pàxene modifegàe a i osservati speciałi',
'tog-watchmoves'              => 'Zonta le pagine spostà ai osservati speciali',
'tog-watchdeletion'           => 'Zonta le pagine scancelà ai osservati speciali',
'tog-minordefault'            => 'Indica ogni modifica come minore (solo come predefinito)',
'tog-previewontop'            => "Mostra l'anteprima de sora la casèla de modifica",
'tog-previewonfirst'          => "Mostra l'anteprima par la prima modifica",
'tog-nocache'                 => "Disativa la ''cache'' par le pagine",
'tog-enotifwatchlistpages'    => 'Segnàleme via e-mail le modifiche a le pagine osservà',
'tog-enotifusertalkpages'     => 'Segnàleme via e-mail le modifiche a la me pagina de discussion',
'tog-enotifminoredits'        => 'Segnàleme via e-mail anca le modifiche picenine',
'tog-enotifrevealaddr'        => 'Rivela el me indirizo e-mail nei messagi de aviso',
'tog-shownumberswatching'     => "Mostra el nùmaro de utenti che tien d'ocio la pagina",
'tog-fancysig'                => 'No modificar mìa el markup de la firma (da doparar par firme mìa standard)',
'tog-externaleditor'          => 'Dòpara par default un editor de testi esterno',
'tog-externaldiff'            => 'Dòpara par default un programa de diff esterno',
'tog-showjumplinks'           => "Ativa i colegamenti acessibili 'và a'",
'tog-uselivepreview'          => "Ativa la funzion ''Live preview'' (ghe vol JavaScript; sperimental)",
'tog-forceeditsummary'        => "Domanda conferma se l'ogeto de la modifica el xe vodo",
'tog-watchlisthideown'        => 'Scondi łe me modifeghe ne i osservati speciałi',
'tog-watchlisthidebots'       => 'Scondi le modifighe de i bot ne i oservati speciałi',
'tog-watchlisthideminor'      => 'Scondi le modifiche picenine nei osservati speciali',
'tog-ccmeonemails'            => 'Màndeme na copia dei messagi spedìi ai altri utenti',
'tog-diffonly'                => 'No stà mostrar el contenuto de la pagina dopo el confronto tra version',
'tog-showhiddencats'          => 'Mostra le categorie sconte',

'underline-always'  => 'Senpre',
'underline-never'   => 'Mai',
'underline-default' => 'Mantien le inpostazion del browser',

'skinpreview' => 'Anteprima',

# Dates
'sunday'        => 'Domenica',
'monday'        => 'Luni',
'tuesday'       => 'Marti',
'wednesday'     => 'Mèrcoli',
'thursday'      => 'Xòbia',
'friday'        => 'Vènerdi',
'saturday'      => 'Sabo',
'sun'           => 'dom',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mer',
'thu'           => 'gio',
'fri'           => 'ven',
'sat'           => 'sab',
'january'       => 'genaro',
'february'      => 'Febraro',
'march'         => 'Marzso',
'april'         => 'Apriłe',
'may_long'      => 'Majo',
'june'          => 'giugno',
'july'          => 'lujo',
'august'        => 'Agosto',
'september'     => 'Setenbre',
'october'       => 'Otobre',
'november'      => 'Novenbre',
'december'      => 'Diçenbre',
'january-gen'   => 'zenaro',
'february-gen'  => 'febraro',
'march-gen'     => 'marzo',
'april-gen'     => 'avril',
'may-gen'       => 'majo',
'june-gen'      => 'giugno',
'july-gen'      => 'lujo',
'august-gen'    => 'agosto',
'september-gen' => 'setenbre',
'october-gen'   => 'otobre',
'november-gen'  => 'novenbre',
'december-gen'  => 'dicenbre',
'jan'           => 'Gen',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Mag',
'jun'           => 'giu',
'jul'           => 'Lug',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Oto',
'nov'           => 'Nov',
'dec'           => 'Diç',

# Categories related messages
'categories'                    => '{{PLURAL:$1|Categoria|Categorie}}',
'categoriespagetext'            => 'Elenco par intiero de le categorie presenti sul sito.',
'special-categories-sort-count' => 'ordina par nùmaro',
'special-categories-sort-abc'   => 'ordina alfabeticamente',
'pagecategories'                => '{{PLURAL:$1|Categoria|Categorie}}',
'category_header'               => 'Voçi n\'te ła categoria "$1"',
'subcategories'                 => 'Sotocategorie',
'category-media-header'         => 'File ne la categoria "$1"',
'category-empty'                => "''Al momento la categoria no la contien nissuna pagina né file multimediai.''",
'hidden-categories'             => '{{PLURAL:$1|Categoria sconta|Categorie sconte}}',
'hidden-category-category'      => 'Categorie sconte', # Name of the category where hidden categories will be listed
'listingcontinuesabbrev'        => 'cont.',

'mainpagetext'      => "'''MediaWiki xè stà instałà con sucesso.'''",
'mainpagedocfooter' => "Consulta la [http://meta.wikimedia.org/wiki/Aiuto:Sommario Guida utente] (in italian) par verghe piassè informazion su l'uso de sto software wiki.

== Par scuminsiar ==
I seguenti colegamenti i xe in lengua inglese:

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Inpostazion de configurazion]
* [http://www.mediawiki.org/wiki/Manual:FAQ Domande frequenti su MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailing list anunci MediaWiki]",

'about'          => 'Se parla de',
'article'        => 'Voçe',
'newwindow'      => '(se verde in una nova finestra)',
'cancel'         => 'Anuła',
'qbfind'         => 'Cata fora',
'qbbrowse'       => 'Sfója',
'qbedit'         => 'Modifega',
'qbpageoptions'  => 'Opzion pàxena',
'qbpageinfo'     => 'Informazion su ła pàxena',
'qbmyoptions'    => 'Le me opzsion',
'qbspecialpages' => 'Pàxene speciałi',
'moredotdotdot'  => 'Altro...',
'mypage'         => 'La me pàxena',
'mytalk'         => 'le me discussión',
'anontalk'       => 'Discussion par sto IP',
'navigation'     => 'Navigazsión',
'and'            => 'e',

# Metadata in edit box
'metadata_help' => 'Metadati:',

'errorpagetitle'    => 'Erór',
'returnto'          => 'Torna a $1.',
'tagline'           => 'Da {{SITENAME}}.',
'help'              => 'Ciacołe',
'search'            => 'Serca',
'searchbutton'      => 'Serca',
'go'                => 'Và',
'searcharticle'     => 'Và',
'history'           => 'Versión precedenti',
'history_short'     => 'Cronołogia',
'updatedmarker'     => 'modificà da la me ultima visita',
'info_short'        => 'Informazsion',
'printableversion'  => 'Version de stampa',
'permalink'         => 'Colegamento permanente',
'print'             => 'Stanpa',
'edit'              => 'Modifega',
'create'            => 'Crea',
'editthispage'      => 'Modifica voçe',
'create-this-page'  => 'Crea sta pagina',
'delete'            => 'Scanceła',
'deletethispage'    => 'Scanceła pàxena',
'undelete_short'    => 'Recupera {{PLURAL:$1|una revision|$1 revision}}',
'protect'           => 'Proteggi',
'protect_change'    => 'modifica protezion',
'protectthispage'   => 'Protegi sta pàxena',
'unprotect'         => 'sbloca',
'unprotectthispage' => 'Cava protezsion',
'newpage'           => 'Pàxena nova',
'talkpage'          => 'Discussion',
'talkpagelinktext'  => 'discussion',
'specialpage'       => 'Pàxena speciałe',
'personaltools'     => 'Strumenti personali',
'postcomment'       => 'Zonta un comento',
'articlepage'       => 'Varda voçe',
'talk'              => 'Discussion',
'views'             => 'Visite',
'toolbox'           => 'Strumenti',
'userpage'          => 'Varda pàxena utente',
'projectpage'       => 'Varda ła pàxena de servizio',
'imagepage'         => 'Varda la pagina del file multimedial',
'mediawikipage'     => 'Mostra el messagio',
'templatepage'      => 'Mostra el template',
'viewhelppage'      => 'Mostra la pagina de ajuto',
'categorypage'      => 'Mostra la categoria',
'viewtalkpage'      => 'Varda ła pàxena de discussion',
'otherlanguages'    => 'Altre łengoe',
'redirectedfrom'    => '(Reindirizzamento da $1)',
'redirectpagesub'   => 'Pàxena de reindirizamento',
'lastmodifiedat'    => 'Ultima modifica $2, $1.', # $1 date, $2 time
'viewcount'         => 'Sta pàxena la xè stà leta {{PLURAL:$1|na olta|$1 olte}}.',
'protectedpage'     => 'Pàxena proteta',
'jumpto'            => 'Va a:',
'jumptonavigation'  => 'Navigazsion',
'jumptosearch'      => 'zserca',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Se parla de {{SITENAME}}',
'aboutpage'         => 'Project:Se parla de',
'bugreports'        => 'Malfunzsionamenti',
'bugreportspage'    => 'Project:Malfunzsionamenti',
'copyright'         => 'Contenuto disponibile sotto $1.',
'copyrightpagename' => 'El copyright su {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Copyright',
'currentevents'     => 'Atuałità',
'currentevents-url' => 'Project:Atuałità',
'disclaimers'       => 'Avertenze',
'disclaimerpage'    => 'Project:Avertenze xenerali',
'edithelp'          => 'Guida',
'edithelppage'      => 'Help:Come scrivere un articolo',
'faq'               => 'Domande frequenti',
'faqpage'           => 'Project:Domande frequenti',
'helppage'          => 'Help:Ciacołe',
'mainpage'          => 'Pàxena prinçipałe',
'policy-url'        => 'Project:Policy',
'portal'            => 'Portal comunità',
'portal-url'        => 'Project:Portałe Comunità',
'privacy'           => 'Informazion su la privacy',
'privacypage'       => 'Project:Informazion su la privacy',
'sitesupport'       => 'Donazsion',
'sitesupport-url'   => 'Project:Donasioni',

'badaccess'        => 'Eròr ne i permessi',
'badaccess-group0' => "No te ghè i permessi necessari par eseguir l'azion richiesta.",
'badaccess-group1' => 'La funzion richiesta la xe riservà ai utenti che fa parte del grupo $1.',
'badaccess-group2' => 'La funzion richiesta la xe riservà ai utenti che fa parte dei gruppi $1.',
'badaccess-groups' => 'La funzion richiesta la xe riservà ai utenti che fa parte de uno dei seguenti grupi: $1.',

'versionrequired'     => 'Verzsion $1 de MediaWiki richiesta',
'versionrequiredtext' => 'Par doparar sta pagina a ghe vole la version $1 del software MediaWiki. Varda la [[Special:Version|pagina de la version]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Estrato da "$1"',
'youhavenewmessages'      => 'Te ghè $1 ($2).',
'newmessageslink'         => 'Novi messaj',
'newmessagesdifflink'     => 'diff to penultimate revision',
'youhavenewmessagesmulti' => 'Te ghè novi messagi su $1',
'editsection'             => 'modifica',
'editold'                 => 'modifica',
'editsectionhint'         => 'Modifica sezsión: $1',
'toc'                     => 'Indice',
'showtoc'                 => 'mostra',
'hidetoc'                 => 'scondi',
'thisisdeleted'           => 'Varda o ripristina $1?',
'viewdeleted'             => 'Vedito $1?',
'restorelink'             => '{{PLURAL:$1|una modifica scancelà|$1 modifiche scancelà}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Modałità de sotoscrizsion de el feed non vałida.',
'feed-unavailable'        => 'No ghe xe feed disponibili par i contenuti de {{SITENAME}}',
'site-rss-feed'           => 'Feed RSS de $1',
'site-atom-feed'          => 'Feed Atom de $1',
'page-rss-feed'           => 'Feed RSS par "$1"',
'page-atom-feed'          => 'Feed Atom par "$1"',
'red-link-title'          => "$1 ('ncora da scrìvar)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Voçe',
'nstab-user'      => 'Utente',
'nstab-media'     => 'File multimedial',
'nstab-special'   => 'Speciale',
'nstab-project'   => 'Pagina de servizio',
'nstab-image'     => 'Imagine',
'nstab-mediawiki' => 'Messagio',
'nstab-template'  => 'Template',
'nstab-help'      => 'Ajuto',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Operazsion non riconoszua',
'nosuchactiontext'  => "L'operazione richiesta con la URL immessa non è stata riconosciuta dal software MediaWiki",
'nosuchspecialpage' => 'No xè disponibiłe nissuna pàxena speciałe co sto nome',
'nospecialpagetext' => 'Hai richiesto una pagina speciale che non è stata riconosciuta dal software MediaWiki, o che non è disponibile.',

# General errors
'error'                => 'Erór',
'databaseerror'        => 'Eror del database',
'dberrortext'          => 'Errore de sintassi ne la richiesta inoltrà al database.
L\'ultima richiesta inoltrà al database l\'è stà:
<blockquote><tt>$1</tt></blockquote>
da la funzsion "<tt>$2</tt>".
MySQL gà restituio un errore "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Se gà verificà un errore de sintassi ne la richiesta al database.
L\'ultuma richiesta al database l\'è stà:
"$1"
da la funzsion "$2".
MySQL gà restituio l\'errore "$3: $4".',
'noconnect'            => 'Connessione al database fallita su $1',
'nodb'                 => 'Selezione del database $1 fallita',
'cachederror'          => 'La seguente xè na copia de riserva de la pagina richiesta, e podarìa non essere aggiornà.',
'laggedslavemode'      => 'Ocio: la pàxena la podarìa no contegner mìa i ultimi agiornamenti.',
'readonly'             => 'Accesso al database temporaneamente disabilitato',
'enterlockreason'      => 'Fornisi na spiegazsion sui motivi del blocco, includendo le probabili data ed ora de riattivazsion o de rimozsion del blocco.',
'readonlytext'         => "Il database di {{SITENAME}} è al momento bloccato, e non consente nuove immissioni né modifiche, molto probabilmente per manutenzione server, nel qual caso il database sarà presto di nuovo completamente accessibile.<br />
L'amministratore di sistema che ha imposto il blocco, ha lasciato questa nota:
<p>:$1</p>",
'missingarticle'       => 'Il database non ha trovato il testo di una pagina, che invece avrebbe dovuto trovare, intitolata "$1".<br />
Questo non è un errore del database, ma più probabilmente un problema del software.<br />
Per favore, segnalate l\'accaduto ad un amministratore di sistema, segnalando la URL e l\'ora dell\'incidente.',
'readonly_lag'         => 'El database el xe stà blocà automaticamente par consentirghe ai server coi database slave de sincronizarse col master',
'internalerror'        => 'Eròr interno',
'internalerror_info'   => 'Eror interno: $1',
'filecopyerror'        => 'Non xè stà possibiłe copiare el file "$1" come "$2".',
'filerenameerror'      => 'Non xè stà possibile rinominare el file "$1" in "$2".',
'filedeleteerror'      => 'Non xè stà possibiłe scancełare el file "$1".',
'directorycreateerror' => 'Xe inpossibile crear la directory "$1".',
'filenotfound'         => 'Non xè stà possibile trovare el file "$1".',
'fileexistserror'      => 'Xe inpossibile scrìvar el file "$1": sto file l\'esiste de zà',
'unexpected'           => 'Valor inprevisto: "$1"="$2".',
'formerror'            => 'Erór: el modulo non xè stà invià correttamente',
'badarticleerror'      => 'Sta operazion no la xè consentìa su sta pàxena.',
'cannotdelete'         => "No se pol mìa scancełar la pàxena o l'imagine richiesta.",
'badtitle'             => 'El titoło non xè mia coreto',
'badtitletext'         => 'La pàxena richiesta no la xè disponibiłe, ła podaìia èssar mìa vałida, voda, o podarìa tratarse de un eror in un cołegamento interlenguistico o fra diverse version de {{SITENAME}}.',
'perfdisabled'         => 'Siamo davvero rammaricati, ma questa funzionalità è temporaneamente disabilitata durante le ore di maggiore accesso al database, per ragioni di accessibilità al resto del sito!<br />Torna fra le 02:00 e le 14:00 UTC e riprova.<br /><br />Grazie.',
'perfcached'           => "Sta quà xè na copia ''cache'' e quindi non podaria essere completamente agiornà:",
'perfcachedts'         => "I dati che segue i xe tirà fora da na copia ''cache'' del database. Ultimo agiornamento: $1.",
'wrong_wfQuery_params' => 'Parametri errai par wfQuery()<br />
Funzsion: $1<br />
Query: $2',
'viewsource'           => 'Varda ła fonte',
'viewsourcefor'        => 'de $1',
'actionthrottled'      => 'Azion ritardà',
'actionthrottledtext'  => "Come misura de sicureza contro el spam, l'esecuzion de çerte azioni la xe limità a un nùmaro massimo de olte in un determinato periodo de tenpo, limite che in sto caso te ghè superà. Par piaser ripróa tra qualche minuto.",
'protectedpagetext'    => 'Sta pagina la xe stà proteta par inpedìrghene la modifica.',
'viewsourcetext'       => 'Se pol vardar e copiar el codice sorgente de sta pagina:',
'protectedinterface'   => "Sta pàxena la contien un elemento che fa parte de l'interfacia utente del software; e quindi la xè proteta par evitar possibiłi abusi.",
'editinginterface'     => "'''Ocio:''' Te stè modificando na pagina che la fa parte de l'interfacia utente del sito. 
Tute le modifiche che te fè a sta pagina le se riflete su l'aspeto de l'interfacia grafica visualizà da tuti i altri utenti.
Se te ghè bisogno de tradur in veneto un messagio de l'interfacia utente, te pol doparar [http://translatewiki.net/wiki/Main_Page?setlang=vec Betawiki], el progeto de localizazion de MediaWiki.",
'sqlhidden'            => '(la query SQL la xe stà sconta)',
'cascadeprotected'     => 'Su sta pagina no se pol far modifiche parché la xe stà inclusa {{PLURAL:$1|ne la pagina indicà de seguito, che la xe stà proteta|ne le pagine indichè de seguito, che le xe stà protete}} selezionando la protezion "ricorsiva":
$2',
'namespaceprotected'   => "No te ghè i permessi necessari par modificar le pagine del namespace '''$1'''.",
'customcssjsprotected' => "No te pol mìa modificar sta pagina, parché la contien le inpostazion personali de n'altro utente.",
'ns-specialprotected'  => 'No se pol modificar le pagine speciali.',
'titleprotected'       => "La creazion de na pagina con sto titolo la xe stà blocà da [[User:$1|$1]].
La motivazion la xe sta qua: ''$2''.",

# Login and logout pages
'logouttitle'                => 'Logout Utente',
'logouttext'                 => 'Logout effettuato.
Ora puoi continuare ad usare {{SITENAME}} come utente anonimo (ma il tuo indirizzo IP resterà riconoscibile), oppure puoi nuovamente richiedere il login con il precedente username, oppure come uno diverso.',
'welcomecreation'            => "<h2>Benvegnù, $1!</h2><p>El to account l'è stà creà con sucesso.<br />
Grasie par aver scelto de far cresere {{SITENAME}} co'l to aiuto.<br />
Par rendere {{SITENAME}} più tua, e par usarla più scorrevolmente, non dimenticare de personalixare le to preferense.",
'loginpagetitle'             => 'Login',
'yourname'                   => 'El to nome utente (solo la prima en maiuscolo)',
'yourpassword'               => 'Scegli na password',
'yourpasswordagain'          => 'Scrivi la password de novo',
'remembermypassword'         => 'Ricorda la mia password per più sessioni (richiede uso dei cookies).',
'yourdomainname'             => 'Specifica el dominio:',
'externaldberror'            => 'Si gà verificà un erór con el server de autenticazsion esterno, oppure non se dispone de łe autorixazsion necessarie par aggiornar el proprio açesso esterno.',
'loginproblem'               => '<b>Si gà verificà un errore durante el to tentativo de login.</b><br />Riproa, te sarè più fortunà!',
'login'                      => 'Entra',
'loginprompt'                => 'Par acedere a {{SITENAME}} xè necessario abiłitare i cookie.',
'userlogin'                  => 'Entra o crea un novo acesso',
'logout'                     => 'Và fora',
'userlogout'                 => 'và fora',
'notloggedin'                => 'Acesso non efetuà',
'nologin'                    => "Non gheto gnancora n'acezso? $1.",
'nologinlink'                => 'Crealo ora',
'createaccount'              => 'Crea un novo accesso',
'gotaccount'                 => 'Gheto xà un to account? $1.',
'gotaccountlink'             => 'Entra',
'createaccountmail'          => 'via email',
'badretype'                  => 'Le password che te ghè immesso non le coincide, le xè diverse fra lore.',
'userexists'                 => 'Siamo spiacenti.<br />Lo user name che hai scelto è già usato da un altro Utente.<br />Ti preghiamo perciò di voler scegliere uno user name diverso.',
'youremail'                  => 'La to e-mail',
'username'                   => 'Nome utente',
'uid'                        => 'ID utente:',
'yourrealname'               => 'El to vero nome*',
'yourlanguage'               => "Linguaggio del l'interfaccia",
'yourvariant'                => 'Variante de linguaggio:',
'yournick'                   => 'El to soranome (par łe firme)',
'badsig'                     => 'Erór ne ła firma non standard, verifica i tag HTML.',
'badsiglength'               => 'El sopranome sielto el xe massa longo; el pol verghe al massimo $1 caràteri.',
'email'                      => 'Indirizo e-mail',
'prefs-help-realname'        => 'Indicar el proprio nome vero no xe obligatorio; se te siegli de inserirlo, el vegnarà doparà par atribuir la paternità dei contenuti invià.',
'loginerror'                 => 'Errore de Login',
'noname'                     => 'Lo user name indicato non è valido, non è possibile creare un account a questo nome.',
'loginsuccesstitle'          => 'Login effettuato con successo!',
'loginsuccess'               => "'''El cołegamento al server de {{SITENAME}} con el nome utente \"\$1\" xè ativo.'''",
'nosuchuser'                 => 'Attenzione<br /><br />a seguito di verifica, non ci risulta alcun Utente con il nome di  "$1".<br /><br />
Controlla per favore il nome digitato, oppure usa il modulo qui sotto per creare un nuovo user account.',
'nosuchusershort'            => 'Non xè registrà nessun utente de nome "<nowiki>$1</nowiki>". Verifica el nome inserio.',
'nouserspecified'            => 'Bisogna specificar un nome utente.',
'wrongpassword'              => "La password che te ghe messo non l'è mia giusta.<br /><br />Riprova, per favore.",
'wrongpasswordempty'         => 'La password inseria xè voda. Riproa.',
'passwordtooshort'           => "La to password l'è massa breve. La deve contegnere almanco $1 caratteri.",
'mailmypassword'             => 'Spediscimi una nuova password in posta elettronica',
'passwordremindertitle'      => 'Servizio Password Reminder de {{SITENAME}}',
'passwordremindertext'       => 'Qualcheduni (probabilmente ti, con indirizo IP $1) el gà domandà che ghe vegna mandà na nova password par {{SITENAME}} ($4).
La password par l\'utente "$2" la xe stà inpostà a "$3".
Xe oportuno che te esegui l\'acesso \'pena che te pol e te canbi la password subito.

Se no te sì mìa stà ti a far la domanda, opure t\'è vegnù in mente la password e no te vol più canbiarla, te pol ignorar sto mesagio e continuar a doparar la vecia password.',
'noemail'                    => 'Nessuna casella e-mail risulta registrata per l\'Utente "$1".',
'passwordsent'               => 'Una nuova password è stata inviata alla casella e-mail registrata per l\'Utente "$1".
Per favore, fai subito un log in non appena la ricevi.',
'eauthentsent'               => "Una email de conferma xè stà invià a l'indirizzo che te ghè indicà. Prima che qualunque altra mail te vengna invià, te devi seguire le istruzsioni contegnue ne la mail ricevuta, par confermar che quell'indirizzo xè veramente el tuo.",
'mailerror'                  => "Ghe xè stà un eror nel mandare l'email: $1",
'acct_creation_throttle_hit' => 'Me despiase, te ghe xà creà $1 account. Non te pol crearghine ancora.',
'emailauthenticated'         => "El to indiriszo de e-mail l'è stado autenticado su $1.",
'emailnotauthenticated'      => 'El to indirizso email <strong>non xè ancora stà autenticà</strong>. Nessuna email la verrà invià tramite le funzsioni che seguono.',
'emailconfirmlink'           => 'Conferma el to indiriszo de e-mail',
'invalidemailaddress'        => "L'indiriszo email no'l pode essere accettà parché el gà un formato non valido. Per favore inserisci un indirizso valido o svoda la caseła.",
'accountcreated'             => 'Acesso creà',
'accountcreatedtext'         => "Xè stà creà un acesso par l'utente $1.",
'loginlanguagelabel'         => 'Lengua: $1',

# Edit page toolbar
'bold_sample'     => 'Grasseto',
'bold_tip'        => 'Grasseto',
'italic_sample'   => 'Corsivo',
'italic_tip'      => 'Corsivo',
'link_sample'     => 'Nome del link',
'link_tip'        => 'Link interno',
'extlink_sample'  => 'http://www.titolochevuoitu.com titolo del link',
'extlink_tip'     => 'Link esterno (ricordate el prefisso http:// )',
'headline_sample' => 'Intestazsión',
'headline_tip'    => 'Sottointestazsión',
'math_sample'     => 'Inserissi qua la formula',
'math_tip'        => 'Formula matemàtega (LaTeX)',
'nowiki_sample'   => 'Inserissi qua el testo non formatà',
'nowiki_tip'      => 'Ignora la formatazion wiki',
'image_tip'       => 'File incorporado',
'media_tip'       => 'Colegamento a file multimedial',
'sig_tip'         => 'La to firma con data e ora',
'hr_tip'          => 'Linea orizontal (dòparela con giudizio)',

# Edit pages
'summary'                  => 'Ogeto',
'subject'                  => 'Argomento (intestazion)',
'minoredit'                => "Sta quà l'è na modifica minore",
'watchthis'                => "Tegni d'ocio sta voçe",
'savearticle'              => 'Salva la pagina',
'preview'                  => 'Anteprima',
'showpreview'              => 'Mostra anteprima',
'showlivepreview'          => "Funzion ''Live preview''",
'showdiff'                 => 'Mostra canbiamenti',
'anoneditwarning'          => "'''Ocio:''' Acesso mìa efetuà. Ne ła cronołogia de ła pàxena vegnarà registrà l'indirizo IP.",
'missingsummary'           => "'''Ocio:''' No te ghè indicà l'ogeto de la modifica. Macando de novo 'Salva la pagina' la modifica la vegnerà con l'ogeto vodo.",
'missingcommenttext'       => 'Inserissi un comento qua soto.',
'missingcommentheader'     => "'''Ocio:''' No te ghè specificà l'intestazion de sto commento. Macando de novo '''Salva la pagina''' la modifica la vegnarà salvà senza intestazion.",
'summary-preview'          => 'Anteprima ogeto',
'subject-preview'          => 'Anteprima ogeto/intestazion',
'blockedtitle'             => "Stò nome utente corrisponde purtroppo a n'Utente che xè stà disabilità a ła modifica de łe voçi.",
'blockedtext'              => "<big>'''Sto nome utente o indirizo IP i xe stà blocà.'''</big>

El bloco el xe stà messo da $1. La motivazion del bloco la xe sta qua: ''$2''

* Inizio del bloco: $8
* Scadenza del bloco: $6
* Intervalo di bloco: $7

Se te vol, te pol contatar $1 o n'altro [[{{MediaWiki:Grouppage-sysop}}|aministrador]] par discùtar del bloco.

Nota che la funzion 'Scrivi a l'utente' no la xe mìa ativa se no xe stà registrà un indirizo e-mail valido ne le proprie [[{{ns:special}}:Preferences|preferenze]].

Se prega de specificare l'indirizo IP atuale ($3) o el nùmaro del bloco (ID #$5) in qualsiasi richiesta de ciarimenti.",
'autoblockedtext'          => "Sto indirizo IP el xe stà blocà automaticamente parché condiviso con n'altro utente, a so volta blocà da $1.
La motivazion del blocco la xe sta qua:

:''$2''

* Inizio del bloco: $8
* Scadenza del bloco: $6

Se pol contatar $1 o n'altro [[{{MediaWiki:Grouppage-sysop}}|aministrador]] par discùtar del bloco.

Nota che la funzion 'Scrivi a l'utente' no la xe ativa se no xe stà registrà un indirizo e-mail valido ne le proprie [[Special:Preferences|preferenze]].

Se prega de specificar el nùmaro del bloco (ID #$5) in qualsiasi richiesta de ciarimenti.",
'blockednoreason'          => 'nissuna motivazion indicà',
'blockedoriginalsource'    => "El codezse sorjente de '''$1''' el vegne mostrà de seguito:",
'blockededitsource'        => "Łe '''modifeghe''' apportae a '''$1''' łe vegne mostrae de seguito:",
'whitelistedittitle'       => 'Bisogna èssar registrà par poder modificar la pàxena.',
'whitelistedittext'        => 'Par modificar łe pàxene ghe xè bisogno de $1.',
'whitelistreadtitle'       => 'Bisogna èssar registrà par lèxar ła pàxena',
'whitelistreadtext'        => 'Xe necessario effettuar el [[Special:Userlogin|login]] par lexere i articoli.',
'whitelistacctitle'        => 'Non te ghè el permesso de creare un account',
'whitelistacctext'         => 'To be allowed to create accounts in this Wiki you have to [[Special:Userlogin|log]] in and have the appropriate permissions.',
'confirmedittitle'         => 'Ghe vole ła conferma e-mail par scrivare',
'confirmedittext'          => "Te ghè da confermar l'indirizo e-mail prima de editar le pàxene. Par piaxer inposta e conferma el to indirizo e--mail tramite le to [[Special:Preferences|preferenze]].",
'nosuchsectiontitle'       => 'Sta sezion no la esiste',
'loginreqtitle'            => "Par modificar sta pagina bisogna prima eseguir l'acesso al sito.",
'loginreqlink'             => 'login',
'loginreqpagetext'         => 'Par védar altre pagine bisogna $1.',
'accmailtitle'             => 'Password spedia.',
'accmailtext'              => "La password par '$1' l'è sta spedia a $2.",
'newarticle'               => '(Novo)',
'newarticletext'           => "El cołegamento appena seguio corisponde a na paxèna non ancora esistente.
Se te desideri creare ła paxèna ora, basta comiçciare a scrivere el testo ne ła caseła qui sotto
(fare riferimento a łe [[{{MediaWiki:Helppage}}|paxène de aiuto]] par majori informazsion).
Se el cołegamento xè stà seguio par eror, xè suficiente far clic sul botòn '''Indrio''' del proprio browser.",
'anontalkpagetext'         => "----





---- ''Sta quà l'è la pàxena de discussion de un utente anonimo che no'l se gà gnancora registrà o che no l'efetua el login. De conseguenza xè necessario identificarlo tramite l'[[Indirizo IP|indirizo IP]] numerico. Sto indirizo el pode èssar condivixo da diversi utenti. Se te sì un utente anonimo e te pensi che ghe sia stà comenti irilevanti, te podi [[Special:Userlogin|registrarte o efetuar el login]] par evitar confuxion con altri utenti in futuro.''",
'noarticletext'            => 'In sto momento ła pàxena richiesta la xè voda. Se pol [[Special:Search/{{PAGENAME}}|çercar sto titoło]] ne łe altre pàxene del sito opure [{{fullurl:{{FULLPAGENAME}}|action=edit}} modificar ła pàxena desso].',
'clearyourcache'           => "'''Nota:''' dopo aver salvà, te devi pulire la cache del to browser par veder i cambiamenti: '''Mozilla:''' clicca su ''reload'' (oppure ''ctrl-r''), '''IE / Opera:''' ''ctrl-f5'', '''Safari:''' ''cmd-r'', '''Konqueror''' ''ctrl-r''.",
'updated'                  => '(Agiornà)',
'note'                     => '<strong>Nota:</strong>',
'previewnote'              => "Tegni presente che sta qua xè solo n'anteprima, e che la to verzsion NON xè stà ancora salvà!",
'previewconflict'          => "Questa anteprima rappresenta il testo nella casella di edizione di sopra, l'articolo apparirà in questa forma se sceglierai di salvare la pagina ora.",
'session_fail_preview'     => '<strong>Purtroppo non xè stà possibiłe salvare le to modifiche parché i dati de la sezsion i xè andai persi. Per favore, riproa.<br />
Se te rizsevi sto messajo de erór pì olte, proa a scołegarte (struca su "và fora" in alto a destra) e a cołegarte novamente.</strong>',
'editing'                  => 'Modifica de $1',
'editingsection'           => 'Modifica $1 (sezsion)',
'editingcomment'           => 'Modifica $1 (commento)',
'editconflict'             => 'Conflitto de edizsion: $1',
'explainconflict'          => "Qualcun altro ga salvà na so verszion de ła voçe nel tempo in cui te stavi preparando ła to verszion.
La caselła de modifica de sora contegne el testo de la voçe ne ła so forma attuałe (el testo attualmente online).
Le to modifiche łe xè inveçe contegnue ne ła caseła de modifica inferiore.
Te dovarè inserire, se te vołi, le to modifiche nel testo esistente, e perciò scrivarle ne ła caseła de sora.
'''Soltanto''' el testo ne ła caseła de sora sarà salvà se te struchi el botón \"Salva\".",
'yourtext'                 => 'El to testo',
'storedversion'            => 'Versione in archivio',
'editingold'               => '<strong>Attenzsion: Te stè modificando na verzsion de ła voçe non aggiornà. Se te la salvi così, tuti i cambiamenti apportai dopo sta verzsion i verrà persi.</strong>',
'yourdiff'                 => 'Differense',
'copyrightwarning'         => "Nota: tuti i contributi a {{SITENAME}} i se considera rilasià nei termini de la licenza d'uso $2 (varda $1 par savérghene piessè). Se no te voli che i to testi i possa èssar modificà e redistribuìi da chiunque senza nissuna limitazion, no sta inviarli a {{SITENAME}}.<br />
Con l'invio del testo te dichiari inoltre, soto la to responsabilità, che el testo el xe stà scrito da ti personalmente opure che el xe stà copià da na fonte de publico dominio o analogamente lìbara.
<strong>NO STA INVIAR MATERIALE CUERTO DA DIRITO D'AUTOR SENZA AUTORIZAZION!</strong>",
'copyrightwarning2'        => 'Ocio che tuti i contributi a {{SITENAME}} i pode essere editai, alterai, o rimossi da altri contributori.
Se non te voli che i to scriti vengna modificà senzsa pietà, alora non inserirli qua.<br />
Sapi che te stè promettendo che te stè inserendo un testo scrito de to pugno, o copià da na fonte de pubblico dominio o similarmente libera (vedi $1 par i dettagli).
<strong>NON INSERIRE OPERE PROTETTE DA COPYRIGHT SENZSA PERMESSO!</strong>',
'longpagewarning'          => "<strong>OCIO: Sta pàxena la xè longa $1 kilobyte; çerti browser i podarìa verghe dei problemi ne ła modifega de pàxene che se aviçina o supera i 32 KB. Valuta l'oportunità de sudivìdar ła pàxena in sezion pìassè picenine.</strong>",
'longpageerror'            => '<strong>ERROR: The text you have submitted is $1 kilobytes 
long, which is longer than the maximum of $2 kilobytes. It cannot be saved.</strong>',
'protectedpagewarning'     => '<strong>OCIO: Sta pagina la xe sta proteta e solo i aministradori i pode modificarla.</strong>',
'semiprotectedpagewarning' => "'''Nota:''' Sta pàxena la xè stà blocà in modo che solo i utenti registrài i poda modefegarla.",
'templatesused'            => 'Template doparà in sta pagina:',
'templatesusedpreview'     => 'Template doparà in sta anteprima:',
'templatesusedsection'     => 'Template doparà in sta sezion:',
'template-protected'       => '(proteto)',
'template-semiprotected'   => '(semiproteto)',
'nocreatetitle'            => 'Creazion de le pagine limitada',
'nocreatetext'             => 'La possibilità de crear pagine nóve su {{SITENAME}} la xe stà limità ai soli utenti registrà. Se pol tornar indrìo e modificar na pagina esistente, opure [[Special:Userlogin|entrar o crear un nóvo acesso]].',
'nocreate-loggedin'        => 'No te ghè i permessi necessari a crear nove pagine su {{SITENAME}}.',
'permissionserrors'        => 'Eror nei permessi',
'permissionserrorstext'    => "No te ghè i permessi necessari ad eseguir l'azion richiesta, par {{PLURAL:$1|el seguente motivo|i seguenti motivi}}:",
'recreate-deleted-warn'    => "'''Ocio: te stè par ricrear na pagina zà scancelà precedentemente.'''

Par piaser assicùrete che sia dal bon el caso de 'ndar vanti a modificar sta pagina.
L'elenco de le relative scancelazion el vien riportà qua de seguito par comodità:",

# History pages
'viewpagelogs'        => 'Varda i registri relativi a sta pagina qua.',
'nohistory'           => 'La cronołogia de łe version de sta pàxena no la xè reperibiłe.',
'revnotfound'         => 'Verzsion non trovà',
'revnotfoundtext'     => "La version richiesta de ła pàxena no la xè mìa stà catà.
Verifica l'URL che te doparà par açedere a sta pàxena.",
'currentrev'          => 'Verzsion atuałe',
'revisionasof'        => 'Revixion $1',
'revision-info'       => 'Version del $1, autor: $2',
'previousrevision'    => '← Verzsion manco reçente',
'nextrevision'        => 'Verzsion pì reçente →',
'currentrevisionlink' => 'Varda ła verzsion atuałe',
'cur'                 => 'cor',
'next'                => 'suc',
'last'                => 'prec',
'page_first'          => 'prima',
'page_last'           => 'ultima',
'histlegend'          => 'Legenda: (corr) = differenzse con la versión corrente,
(prec) = differenzse con la versión precedente, m = modifica minore',
'deletedrev'          => '[scancełà]',
'histfirst'           => 'Prima',
'histlast'            => 'Ultima',
'historysize'         => '({{PLURAL:$1|1 byte|$1 byte}})',
'historyempty'        => '(voda)',

# Revision feed
'history-feed-title'          => 'Cronołogia',
'history-feed-description'    => 'Cronołogia de ła pàxena su sto sito',
'history-feed-item-nocomment' => '$1 el $2', # user at time
'history-feed-empty'          => 'La pàxena richiesta no la esiste; la podarìa èssar stà scancełà dal sito o rinominà. Verifica con la [[Special:Search|pàxena de riçerca]] se ghe xè nove pàxene.',

# Revision deletion
'rev-deleted-comment'    => '(comento cavà)',
'rev-deleted-user'       => '(nome utente cavà)',
'rev-deleted-event'      => '(elemento cavà)',
'rev-delundel'           => 'mostra/scondi',
'revisiondelete'         => 'Scanceła o ripristina verzsion',
'revdelete-selected'     => '{{PLURAL:$2|Version selezionà|Versioni selezionà}} de [[:$1]]:',
'revdelete-hide-text'    => 'Scondi el testo de ła verzsion',
'revdelete-hide-comment' => "Scondi l'oggetto de ła modifega",
'revdelete-hide-user'    => "Scondi el nome o l'indirizso IP dell'autore",
'revdelete-submit'       => 'Applica a ła revixion selezsionà',

# Diffs
'history-title'           => 'Cronologia de le modifiche de "$1"',
'difference'              => '(Diferenzse fra łe verzsion)',
'lineno'                  => 'Riga $1:',
'compareselectedversions' => 'Confronta łe verzsión selezsionà',
'editundo'                => 'annulla',
'diff-multi'              => '({{plural:$1|Una revision intermedia non mostrà|$1 revision intermedie non mostrà}}.)',

# Search results
'searchresults'         => 'Risultato della ricerca',
'searchresulttext'      => 'Per maggiori informazioni sulla ricerca interna di {{SITENAME}}, vedi [[{{MediaWiki:Helppage}}|Ricerca in {{SITENAME}}]].',
'searchsubtitle'        => "Te ghè çercà '''[[:$1]]'''",
'searchsubtitleinvalid' => "Te ghè çercà '''$1'''",
'noexactmatch'          => "'''La pàxena \"\$1\" no ła esiste.''' Te pol [[:\$1|crearla desso]].",
'noexactmatch-nocreate' => "'''No ghe xe nissuna pagina con titolo \"\$1\".'''",
'titlematches'          => 'Nei titołi de łe voçi',
'notitlematches'        => 'Voce richiesta non trovata in titoli di articolo',
'textmatches'           => 'Nel testo degli articoli',
'notextmatches'         => 'Voce richiesta non trovata in testi di articolo',
'prevn'                 => 'precedenti $1',
'nextn'                 => 'sucessivi $1',
'viewprevnext'          => 'Varda ($1) ($2) ($3).',
'search-redirect'       => '(rimando $1)',
'search-section'        => '(sezion $1)',
'search-suggest'        => 'Forsi te çercavi: $1',
'searchall'             => 'tuti',
'showingresults'        => "Qua de soto vien mostrà al massimo {{PLURAL:$1|'''1''' risultato|'''$1''' risultati}} a partir dal nùmaro '''$2'''.",
'showingresultsnum'     => "Qua soto ghe xe {{PLURAL:$3|'''1''' risultato|'''$3''' risultati}} a partir da #'''$2'''.",
'showingresultstotal'   => "De seguito vien mostrà i risultati da '''$1''' a '''$2''' su un totale de '''$3'''",
'nonefound'             => '<strong>Nota</strong>: la ricerca di parole troppo comuni, come "avere" o "essere", che non sono indicizzate, può causare un esito negativo, così come indicare più di un termine da ricercare (solo le pagine che contengano tutti i termini ricercati verrebbero infatti visualizzate fra i risultati).',
'powersearch'           => 'Riçerca avansada',
'powersearch-legend'    => 'Riçerca avanzada',
'powersearchtext'       => '
Cerca fra i campi :<br />
$1<br />
$2 Elenca i redirects &nbsp; cerca per $3 $9',
'searchdisabled'        => 'La riçerca interna de {{SITENAME}} no la xe ativa; par intanto te pol proár a doparar un motore de riçerca esterno come Google. (Nota però che i contenuti de {{SITENAME}} presenti in sti motori i podarìa èssar mìà agiornà.)',

# Preferences page
'preferences'              => 'Preferenzse',
'mypreferences'            => 'le me preferenze',
'prefs-edits'              => 'Nùmaro de modifiche:',
'prefsnologin'             => 'Non te ghè eseguio el login',
'prefsnologintext'         => 'Te ghè da aver eseguio el [[Special:Userlogin|login]]
par poder personalixare le to preferenzse.',
'prefsreset'               => 'Le to Preferenzse xè stà ripescae da la memoria de sistema del potente server de {{SITENAME}}.',
'qbsettings'               => 'Settaggio barra menu',
'qbsettings-none'          => 'Nessun',
'qbsettings-fixedleft'     => 'Fisso a sinistra',
'qbsettings-fixedright'    => 'Fisso a destra',
'qbsettings-floatingleft'  => 'Fluttuante a sinistra',
'qbsettings-floatingright' => 'Fluttuante a destra',
'changepassword'           => 'Cambia ła password',
'skin'                     => 'Aspetto',
'math'                     => 'Formułe matematiche',
'dateformat'               => 'Formato de la data',
'datedefault'              => 'Nesuna preferenzsa',
'datetime'                 => 'Data e ora',
'math_image_error'         => 'Converzsion in PNG fałía',
'prefs-personal'           => 'Profiło utente',
'prefs-rc'                 => 'Ultime modifeghe',
'prefs-misc'               => 'Preferenzse varie',
'saveprefs'                => 'Salva preferenze',
'resetprefs'               => 'Resetta preferenzse',
'oldpassword'              => 'Vecia password:',
'newpassword'              => 'Nova password',
'retypenew'                => 'Riscrivi la nuova password',
'textboxsize'              => 'Dimensione della casella di edizione',
'rows'                     => 'Righe',
'columns'                  => 'Cołone:',
'searchresultshead'        => 'Settaggio preferenze di ricerca',
'resultsperpage'           => 'Nùmaro de risultati par pàxena:',
'contextlines'             => 'Righe de testo par ciascun risultato',
'contextchars'             => 'Caratteri par linea:',
'recentchangescount'       => 'Numero titołi in "modifeghe reçenti"',
'savedprefs'               => 'Le to preferenzse łe xè stà salvae.',
'timezonelegend'           => 'Fuso orario',
'timezonetext'             => 'Immetti il numero di ore di differenza fra la tua ora locale e la ora del server (UTC).',
'localtime'                => 'Ora Locale',
'timezoneoffset'           => 'Difarenza¹',
'servertime'               => 'Ora del server',
'guesstimezone'            => "Usa l'ora del to browser",
'allowemail'               => 'Consenti la ricezsion de e-mail da altri utenti<sup>1</sup>',
'defaultns'                => 'Szerca in sti namespace se non diversamente specificà:',
'default'                  => 'predefinìo',
'files'                    => 'Imagini',

# User rights
'userrights'                 => 'Gestion dei parmessi relativi ai utenti', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'     => 'Gestion de i gruppi utente',
'userrights-user-editname'   => 'Inserire el nome utente:',
'userrights-groupsmember'    => 'Apartien ai grupi:',
'userrights-groupsremovable' => 'Grupi eliminabili:',
'userrights-groupsavailable' => 'Grupi disponibili:',

# Groups
'group'       => 'Grupo:',
'group-sysop' => 'Aministradori',

'group-sysop-member' => 'Aministrador',

'grouppage-sysop' => '{{ns:project}}:Aministradori',

# User rights log
'rightslog'  => 'Diriti dei utenti',
'rightsnone' => '(nissun)',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|modifica|modifiche}}',
'recentchanges'                  => 'Ultime modifeghe',
'recentchangestext'              => 'Sta pàxena la presenta łe ultime modifeghe aportàe ai contenuti de el sito.',
'recentchanges-feed-description' => 'Sto feed qua el riporta le modifiche piassè recenti ai contenuti del sito.',
'rcnote'                         => "Qua soto ghe xe {{PLURAL:$1|l'ultimo cambiamento|i ultimi '''$1''' canbiamenti}} ne {{PLURAL:$2|l'ultimo giòrno|i ultimi '''$2''' giòrni}}; i dati i xe agiornà al $3.",
'rcnotefrom'                     => " Qui di seguito sono elencate le modifiche da '''$2''' (fino a '''$1''').",
'rclistfrom'                     => 'Mostra łe modifeghe aportae a partire da $1',
'rcshowhideminor'                => '$1 le modifeghe minori',
'rcshowhidebots'                 => '$1 i bot',
'rcshowhideliu'                  => '$1 gli utenti registrai',
'rcshowhideanons'                => '$1 i utenti anonimi',
'rcshowhidepatr'                 => '$1 łe modifeghe controłae',
'rcshowhidemine'                 => '$1 łe me modifeghe',
'rclinks'                        => 'Mostra le ultime $1 modifiche nei ultimi $2 giorni<br />$3',
'diff'                           => 'dif',
'hist'                           => 'cron',
'hide'                           => 'scondi',
'show'                           => 'Mostra',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc_categories'                  => 'Limita a le categorie (separà da "|")',
'rc_categories_any'              => 'Qualsiasi',
'newsectionsummary'              => '/* $1 */ sezion nova',

# Recent changes linked
'recentchangeslinked'          => 'Modifeghe corełae',
'recentchangeslinked-title'    => 'Modifiche ligà a $1',
'recentchangeslinked-noresult' => 'Nel periodo specificà no ghe xe stà nissuna modifica a le pagine colegà.',
'recentchangeslinked-summary'  => "Sta pagina speciale la fa védar le modifiche piassè recenti a le pagine ligà a quela specificà. 
Le pagine su la lista dei osservati speciali le xe in '''grasseto'''.",

# Upload
'upload'                      => 'Carga su un file',
'uploadbtn'                   => 'Carga el file',
'reupload'                    => 'Ri-upload',
'reuploaddesc'                => 'Lassa pèrdar el caricamento e torna al modulo de caricamento',
'uploadnologin'               => 'Te devi fare el login par exeguire sta operazsion.',
'uploadnologintext'           => 'Te ghè da exeguire [[Special:Userlogin|el login]]
par fare el upload de files.',
'uploaderror'                 => 'Errore di Upload',
'uploadtext'                  => "Par cargar novi file, dopara el modulo qua soto. 
Par védar o çercar i file zà caricà, consulta la [[Special:Imagelist|lista dei file caricà]]. I caricamenti e le scancelazion de file te pol védarle nel [[Special:Log/upload|registro dei caricamenti]].

Par métar na imagine drento na pagina, te ghè da far un colegamento de sto tipo:
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.png|testo alternativo]]</nowiki>''' opure
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' par crear un colegamento direto al file.",
'uploadlog'                   => 'File caricai',
'uploadlogpage'               => 'Log dei file caricai',
'uploadlogpagetext'           => 'Qui di seguito la lista degli ultimi files caricati sul server di {{SITENAME}}.
Tutti i tempi indicati sono calcolati sul fuso orario del server (UTC).',
'filename'                    => 'Nome del file',
'filedesc'                    => 'Oggetto',
'fileuploadsummary'           => 'Sommario:',
'filestatus'                  => 'Informazion sul copyright:',
'filesource'                  => 'Fonte:',
'uploadedfiles'               => 'Files Caricati in {{SITENAME}}',
'ignorewarning'               => "Ignora l'avertimento e salva istesso el file.",
'ignorewarnings'              => 'Ignora i messaggi de avvertimento del sistema',
'minlength1'                  => 'El nome del file el ga da contegner almanco un caràtere.',
'illegalfilename'             => 'Il nomefile "$1" contiene caratteri che xè permessi nei titoli delle pagine. Per favore rinomina el file e prova a ricaricarlo.',
'badfilename'                 => 'El nome de el file imagine xè stà convertio in "$1".',
'filetype-badmime'            => 'No xe consentìo de cargar file de tipo MIME "$1".',
'filetype-unwanted-type'      => "Cargar file de tipo '''\".\$1\"''' xe sconsiglià. I tipi de file consiglià i xe \$2.",
'filetype-banned-type'        => "Cargar file de tipo '''\".\$1\"''' no xe mìa consentìo. I tipi de file consentìi i xe \$2.",
'filetype-missing'            => 'El file no\'l gà nissuna estension (ad es. ".jpg").',
'large-file'                  => 'Se racomanda de no superar mìa le dimension de $1 par ciascun file; sto file el xe grando $2.',
'largefileserver'             => 'El file el supera le dimension consentìe da la configurazion del server.',
'emptyfile'                   => 'El file che te ghè caricà xè apparentemente vuoto. Podaria essere par un errore nel nome del file. Per favore controlla se te vol veramente caricar stò file.',
'fileexists'                  => 'Un file con sto nome el esiste xà, per favore controła $1 se non te sì sicuro de volerlo sovrascrivere.',
'filepageexists'              => "La pagina de descrizion de sto file la xe zà stà creà a l'indirizo <strong><tt>$1</tt></strong>, anca se no ghe xe gnancora un file co sto nome. La descrizion de l'ogeto inserìa in fase de caricamento no la vegnarà mìa fora su la pagina de discussion. Par far sì che l'ogeto el conpaja su la pagina de discussion, sarà necessario modificarla a man",
'fileexists-extension'        => 'Ghe xe zà un file co un nome che ghe someja a sto qua:<br />
Nome del file cargà: <strong><tt>$1</tt></strong><br />
Nome del file esistente: <strong><tt>$2</tt></strong><br />
Par piaser siegli un nome difarente.',
'fileexists-thumb'            => "<center>'''File zà esistente'''</center>",
'fileexists-thumbnail-yes'    => "El file cargà el pararìa èssar el risultato de n'anteprima <i>(thumbnail)</i>. Verifica, par confronto, el file <strong><tt>$1</tt></strong>.<br />
Se se trata de la stessa imagine, ne le dimension originali, no xe necessario caricarghene altre anteprime.",
'file-thumbnail-no'           => "El nome del file el scuminsia con <strong><tt>$1</tt></strong>; pararìà quindi che el fusse el risultato de n'anteprima <i>(thumbnail)</i>.
Se se dispone de l'imagine ne la risoluzion originale, se prega di cargarla. In caso contrario, se prega de canbiar el nome del file.",
'fileexists-forbidden'        => 'Un file con sto nome el esiste xà; per favore torna indrio e cambia el nome che te voi dare al file. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Un file con sto nome esiste xè ne l'archivio de risorse multimediałi condivixe. Per favore torna indrio e cambia el nome che te voi dare al file. [[Image:$1|thumb|center|$1]]",
'successfulupload'            => 'Caricamento conpletà',
'uploadwarning'               => 'Avixo de Upload',
'savefile'                    => 'Salva file',
'uploadedimage'               => 'gà caricà "[[$1]]"',
'overwroteimage'              => 'gà cargà na version nova de "[[$1]]"',
'uploaddisabled'              => 'Semo spiacenti, ma el caricamento de file el xe tenporaneamente sospeso.',
'uploaddisabledtext'          => "Su {{SITENAME}} el caricamento dei file no'l xe mìa ativo.",
'uploadscripted'              => 'Sto file contegne codexe HTML o de script, che podaria essere interpretà eroneamente da un browser web.',
'uploadcorrupt'               => 'El file el xe coróto o el gà na estension mìa giusta. Controla el file e dopo próa de novo a cargarlo.',
'uploadvirus'                 => 'Sto file contegne un virus! Detagli: $1',
'sourcefilename'              => 'Nome del file de origine:',
'destfilename'                => 'Nome del file de destinazion:',
'upload-maxfilesize'          => 'Dimension massima del file: $1',
'watchthisupload'             => "Tien d'ocio sta pagina",
'filewasdeleted'              => 'Un file con sto nome xè stato xà caricà e scancełà in passato. Verifica $1 prima de caricarlo de novo.',

'license'            => "Licenza d'uso:",
'nolicense'          => 'Nessuna liçenzsa indicà',
'license-nopreview'  => '(Anteprima mìa disponibile)',
'upload_source_url'  => ' (una URL coreta e acessibile)',
'upload_source_file' => ' (un file sul proprio computer)',

# Special:Imagelist
'imgdesc'               => 'desc',
'imgfile'               => 'file',
'imagelist'             => 'Imagini',
'imagelist_date'        => 'Data',
'imagelist_name'        => 'Nome',
'imagelist_user'        => 'Utente',
'imagelist_size'        => 'Dimension in byte',
'imagelist_description' => 'Descrizion',

# Image description page
'filehist'                  => 'Cronologia del file',
'filehist-help'             => 'Maca su un grupo data/ora par védar el file come el se presentava nel momento indicà.',
'filehist-deleteall'        => 'scancela tuto',
'filehist-deleteone'        => 'scancela sta version',
'filehist-revert'           => 'ripristina',
'filehist-current'          => 'corente',
'filehist-datetime'         => 'Data/Ora',
'filehist-user'             => 'Utente',
'filehist-dimensions'       => 'Dimensioni',
'filehist-filesize'         => 'Dimension del file',
'filehist-comment'          => 'Ogeto',
'imagelinks'                => 'Collegamenti a le immagini',
'linkstoimage'              => 'Le pàxene seguenti le riciama sta imagine:',
'nolinkstoimage'            => 'Nissuna pàxena la punta a sta imagine.',
'sharedupload'              => 'Sto file qua el xe caricamento condiviso; quindi el pol èssar doparà da più progeti wiki.',
'shareduploadwiki'          => 'Varda $1 par ulteriori informazion.',
'shareduploadwiki-desc'     => 'La descrizion che vien fora in quela sede, su la relativa $1, la vien mostrà de seguito.',
'shareduploadwiki-linktext' => 'pagina de descrizion del file',
'noimage'                   => 'Un file con sto nome non esiste; $1?',
'noimage-linktext'          => 'carica ora',
'uploadnewversion-linktext' => 'Carica na nova verzsion de sto file',
'imagepage-searchdupe'      => 'Riçerca dei file duplicà',

# File reversion
'filerevert'                => 'Ripristina $1',
'filerevert-legend'         => 'Ripristina file',
'filerevert-intro'          => '<span class="plainlinks">Te stè par ripristinar el file \'\'\'[[Media:$1|$1]]\'\'\' a la [versione $4 del $2, $3].</span>',
'filerevert-comment'        => 'Comento:',
'filerevert-defaultcomment' => 'Xe stà ripristinà la version del $1, $2',
'filerevert-submit'         => 'Ripristina',
'filerevert-success'        => '<span class="plainlinks">\'\'\'El file [[Media:$1|$1]]\'\'\' el xe stà ripristinà a la [$4 version del $2, $3].</span>',
'filerevert-badversion'     => 'No esiste mìa version locali precedenti del file col timestamp richiesto.',

# File deletion
'filedelete'                  => 'Scancela $1',
'filedelete-legend'           => 'Scancela el file',
'filedelete-intro'            => "Te stè par scancelar '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'        => '<span class="plainlinks">Te sì drio scancelar la version de \'\'\'[[Media:$1|$1]]\'\'\' del [$4 $3, $2].</span>',
'filedelete-comment'          => 'Motivo:',
'filedelete-submit'           => 'Scancela',
'filedelete-success'          => "El file '''$1''' el xe stà scancelà.",
'filedelete-success-old'      => '<span class="plainlinks">La version del $3, $2 del file \'\'\'[[Media:$1|$1]]\'\'\' la xe stà scancelà.</span>',
'filedelete-nofile'           => 'Su {{SITENAME}} no esiste un file $1',
'filedelete-nofile-old'       => "In archivio no ghe xe version de '''$1''' con le carateristiche indicà",
'filedelete-iscurrent'        => 'Te stè proando a scancelar la version piassè recente de sto file. Par piaser, prima ripòrtelo a na version precedente.',
'filedelete-otherreason'      => 'Altra motivazion o motivazion agiuntiva:',
'filedelete-reason-otherlist' => 'Altra motivazion',
'filedelete-reason-dropdown'  => '*Motivazion piassè comuni par la scancelazion
** Violazion de copyright
** File duplicà',
'filedelete-edit-reasonlist'  => 'Modifica le motivazion par la scancelazion',

# MIME search
'mimesearch' => 'Çerca in base al tipo MIME',
'mimetype'   => 'Tipo MIME:',
'download'   => 'descarga',

# Unwatched pages
'unwatchedpages' => 'Pàxene mìa osservàe',

# List redirects
'listredirects' => 'Elenco dei redirect',

# Unused templates
'unusedtemplates'    => 'Template mìa doparà',
'unusedtemplateswlh' => 'altri cołegamenti',

# Random page
'randompage' => 'Pàxena a caso',

# Random redirect
'randomredirect' => 'Un redirect a caso',

# Statistics
'statistics'    => 'Statistiche',
'sitestats'     => 'Statistiche del sito',
'userstats'     => 'Statistiche dei utenti',
'sitestatstext' => "Ghe xe in tuto {{PLURAL:\$1|'''1''' pagina|'''\$1''' pagine}} nel database.
Sto nùmaro el conprende anca le pagine de discussion, quele de servizio de {{SITENAME}}, le voçi pì picenine (\"stub\"), i redirect e altre pagine che probabilmente non le va mìa considerà tra i contenuti del sito.
Escludendo tute le pagine sora descrite, ghe ne xè '''\$2''' de contenuti veri e propri.

Xe stà inoltre caricà '''\$8''' file.

Da l'instalazion del sito fin a sto momento, xe stà visità '''\$3''' pagine ed eseguìe '''\$4''' modifeghe, pari a na media de '''\$5''' modifeghe par pàxena e '''\$6''' richieste de letura par ciascuna modifega.

La [http://meta.wikimedia.org/wiki/Help:Job_queue coa] la contien '''\$7''' proçessi.",
'userstatstext' => "Ghe xe {{PLURAL:$1|'''1''' [[Special:Listusers|utente]]|'''$1''' [[Special:Listusers|utenti]]}} registrà, de cui '''$2''' (pari al '''$4%''') {{PLURAL:$2|el|i}} gà i diriti de $5.",

'disambiguations'      => 'Pàxene de disanbiguazion',
'disambiguationspage'  => 'Template:Disambigua',
'disambiguations-text' => "Le pagine ne la lista che segue le contien dei colegamenti a '''pagine de disanbiguazion''' e no a l'argomento a cui le dovarìà far riferimento.<br />Vien considerà pagine de disanbiguazion tute quele che contien i template elencà in [[MediaWiki:disambiguationspage]]",

'doubleredirects'     => 'Redirect dopi',
'doubleredirectstext' => '<b>Atenzsion:</b> Stà lista la pode talvolta contegnere dei risultati non corretti. Podaria magari accadere perchè ghe fusse del testo aggiuntivo o dei link dopo el tag #REDIRECT.<br /> Ogni riga contegne i link al primo ed al secondo redirect, oltre a la prima riga de testo del secondo redirect che de solito contegne el "reale" articolo de destinazsion, quello al quale anca el primo redirect dovaria puntare.',

'brokenredirects'        => 'Redirect erái',
'brokenredirectstext'    => 'I seguenti redirect i punta a articoli non ancora creai.',
'brokenredirects-edit'   => '(modifica)',
'brokenredirects-delete' => '(scancela)',

'withoutinterwiki'        => 'Pagine che no gà interwiki',
'withoutinterwiki-header' => 'Le pagine indicà de seguito no le gà colegamenti a le version in altre lengue:',
'withoutinterwiki-submit' => 'Mostra',

'fewestrevisions' => 'Voçi con manco revision',

# Miscellaneous special pages
'nbytes'                  => '$1 byte',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorie}}',
'nlinks'                  => '$1 {{PLURAL:$1|colegamento|colegamenti}}',
'nmembers'                => '$1 {{PLURAL:$1|elemento|elementi}}',
'nrevisions'              => '$1 {{PLURAL:$1|revision|revision}}',
'nviews'                  => '$1 {{PLURAL:$1|visita|visite}}',
'specialpage-empty'       => "Sto raporto no'l contien nissun risultato.",
'lonelypages'             => 'Pàxene solitarie',
'lonelypagestext'         => 'Le pagine indicà de seguito no le gà colegamenti che vegna da altre pagine del sito.',
'uncategorizedpages'      => 'Pàxene prive de categorie',
'uncategorizedcategories' => 'Categorie prive de categorie',
'uncategorizedimages'     => 'File che no gà na categoria',
'uncategorizedtemplates'  => 'Template che no gà categorie',
'unusedcategories'        => 'Categorie non utilixae',
'unusedimages'            => 'Imagini non utilixae',
'popularpages'            => 'Pàxene pì viste',
'wantedcategories'        => 'Categorie richieste',
'wantedpages'             => 'Pàxene pì richieste',
'mostlinked'              => 'Pàxene piassè puntà',
'mostlinkedcategories'    => 'Categorie piazsé riciamae',
'mostlinkedtemplates'     => 'Template piassè doparà',
'mostcategories'          => 'Arthicołi con piazsé categorie',
'mostimages'              => 'File piassè riciamà',
'mostrevisions'           => 'Voçi con piazsé revixión',
'prefixindex'             => 'Indice de le voçi par létere iniziali',
'shortpages'              => 'Pàxene curte',
'longpages'               => 'Pàxene longhe',
'deadendpages'            => 'Pàxene senza uscita',
'protectedpages'          => 'Pagine protete',
'listusers'               => 'Elenco dei utenti',
'specialpages'            => 'Pàxene speciałi',
'spheading'               => 'Pàxene speciałi par tuti i utenti',
'restrictedpheading'      => 'Pàxene speciałi par i aministradori',
'newpages'                => 'Pàxene nove',
'ancientpages'            => 'Pàxene pì vece',
'move'                    => 'Sposta',
'movethispage'            => 'Sposta sta pagina',
'unusedimagestext'        => "Par piaser tien conto che altri siti web i podarìa realizar colegamenti ai file doparando diretamente l'URL; quindi sti file i podarìa essar in uso, anca se no i se cata ne l'elenco.",
'unusedcategoriestext'    => 'Le pàxene de łe categorie indicàe de seguito łe xè stà creàe ma no le contien nissuna pàxena né sotocategoria.',
'notargettext'            => "Non hai specificato una pagina o un Utente in relazione al quale eseguire l'operazione richiesta.",

# Book sources
'booksources' => 'Fonti librarie',

# Special:Log
'specialloguserlabel'  => 'Utente:',
'speciallogtitlelabel' => 'Titolo',
'log'                  => 'Registro',
'all-logs-page'        => 'Tuti i registri',
'alllogstext'          => 'Vixualixazion unificà de i registri de caricamento, scancełazion, protezin, blochi e de aministrazin. Te podi restrénzar i criteri de riçerca selezionando el tipo de registro, nome utente, o la pàxena interessà.',

# Special:Allpages
'allpages'          => 'Tute łe pàxene',
'alphaindexline'    => 'da $1 a $2',
'nextpage'          => 'Pàxena dopo ($1)',
'prevpage'          => 'Pagina precedente ($1)',
'allpagesfrom'      => 'Mostra łe pàxene scominsiando da:',
'allarticles'       => 'Tuti le voçi',
'allinnamespace'    => 'Tute łe pàxene ($1 namespace)',
'allnotinnamespace' => 'Tute łe pàxene (via de quele nel namespace $1)',
'allpagesprev'      => 'Preçedenti',
'allpagesnext'      => 'Prozsime',
'allpagessubmit'    => 'Và',
'allpagesprefix'    => 'Mostra łe voçi che inizsia con:',

# E-mail user
'mailnologintext' => 'Par inviare messaj e-mail ad altri utenti xè neçessario [[Special:Userlogin|açedere al sito]] e aver registrà un indirizso vałido ne łe proprie [[Special:Preferences|preferenzse]].',
'emailuser'       => "Scrivi a l'utente",
'emailpage'       => "Scrivi una e-mail all'utente",
'emailpagetext'   => 'Se sto Utente gà registrà na casella e-mail valida, el modulo qui sotto te consentirà di scriverghe un solo messaggio. La e-mail che te ghè indicà ne le to preferenzse la apparirà nel campo "Da" de la mail, così che el destinatario possa, solo se el lo desidera però, risponderte.',
'noemailtitle'    => 'Nessun indirizso e-mail',
'noemailtext'     => 'Questo Utente non ha registrato alcuna casella e-mail, oppure ha scelto di non ricevere posta elettronica dagli altri Utenti.',
'emailmessage'    => 'Messajo',
'emailsent'       => 'E-mail invià',
'emailsenttext'   => 'La to e-mail xè stà invià',

# Watchlist
'watchlist'            => 'osservati speciali',
'mywatchlist'          => 'osservati speciali',
'watchlistfor'         => "(par '''$1''')",
'nowatchlist'          => "Non hai indicato articoli da tenere d'occhio.",
'watchlistanontext'    => "Per vixualixare e modifegar l'ełenco de i osservati speciałi xè necessario $1.",
'watchnologin'         => 'No ghe xe el login',
'watchnologintext'     => 'Devi prima fare il [[Special:Userlogin|login]]
per modificare la tua lista di osservati speciali.',
'addedwatch'           => 'Xontà ai tòi Osservati Speciali',
'addedwatchtext'       => "La paxèna  \"<nowiki>\$1</nowiki>\" l'è stà xontà a la tua [[Special:Watchlist|lista de osservati speciali]].
Le future modifiche a stà pagina e a la relativa pagina de discussion le sarà elencae qui, e la paxèna apparirà in '''grasseto''' ne la paxèna de le [[Special:Recentchanges|modifiche recenti]] par essere pì facile da tener d'ocio.

Se pì avanti te vorè tojere stò articolo da la to lista de Osservati Speciali, clicca \"Non seguire\" nella barra dei menu.",
'removedwatch'         => 'Rimosso dalla lista degli Osservati Speciali',
'removedwatchtext'     => 'La paxèna  "<nowiki>$1</nowiki>" xè stà rimossa da ła łista de i toi Osservati Speciałi.',
'watch'                => "Tien d'ocio",
'watchthispage'        => 'Segui sta voçe',
'unwatch'              => "No sta tegner d'ocio",
'notanarticle'         => 'Non xè na voçe',
'watchnochange'        => "Nezsun de i to ojeti osservai l'è stà edità nel periodo mostrà.",
'watchlist-details'    => '{{PLURAL:$1|$1 pagina|$1 pagine}} tegnù de ocio, senza contar le pagine de discussion.',
'wlheader-enotif'      => '* Xe attivà la notifica via e-mail.',
'wlheader-showupdated' => "* Le pàxene che xe stà modificà da la to ultima visita le xe evidensià en '''grasseto'''",
'watchmethod-recent'   => 'controło de łe modifeghe reçenti par i osservati speciałi',
'watchmethod-list'     => 'controło de i osservati speciałi par modifeghe reçenti',
'watchlistcontains'    => 'La lista de i osservati speciałi la contien {{PLURAL:$1|una pagina|$1 pagine}}.',
'iteminvalidname'      => "Problemi con la voçe '$1', nome non vałido...",
'wlnote'               => "Qua soto te cati {{PLURAL:$1|l'ultimo canbiamento|i ultimi '''$1''' canbiamenti}} ne {{PLURAL:$2|l'ultima ora|le ultime '''$2''' ore}}.",
'wlshowlast'           => 'Mostra le ultime $1 ore $2 giòrni $3',
'watchlist-hide-bots'  => 'Scondi le modifiche dei bot',
'watchlist-hide-own'   => 'Scondi le me modifiche',
'watchlist-hide-minor' => 'Scondi le modifiche picenine',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Zonto ai oservati speciali...',
'unwatching' => 'Cavo dai osservati speciali...',

'enotif_reset'       => 'Segna tute łe pàxene visitae',
'enotif_newpagetext' => 'Sta qua la xe na nova pàxena.',
'changed'            => 'cambià',
'created'            => 'Creà',
'enotif_lastvisited' => 'Varda $1 par tutte le modifiche da la to ultima visita.',
'enotif_body'        => 'Caro $WATCHINGUSERNAME,

ła paxèna $PAGETITLE de {{SITENAME}} xè stà $CHANGEDORCREATED el $PAGEEDITDATE da $PAGEEDITOR, varda $PAGETITLE_URL par ła verzsione atuałe.

$NEWPAGE

Sommario del redattore: $PAGESUMMARY $PAGEMINOREDIT

Contatta el redattore:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Non ghe sarà altre notifiche in caso de ulteriori cambiamenti, a manco che ti non te visiti sta paxèna. Te podi anca rexettar l\'avvixo de notifica par tuti gli osservati speciałi de ła to łista.

             El to amichevole sistema de notifica de {{SITENAME}}

--
Par cambiare łe impostazsion de i to osservati speciałi, visita
{{fullurl:Special:Watchlist/edit}}

Par riscontri e ulteriore assistenzsa:
{{fullurl:Help:Ciacołe}}',

# Delete/protect/revert
'deletepage'                  => 'Scanceła pàxena',
'excontent'                   => "el contenuto xera: '$1'",
'exbeforeblank'               => "El contenuto prima de lo svodamento xera: '$1'",
'exblank'                     => "ła pàxena l'era voda",
'historywarning'              => 'Ocio: La pàxena che te stè par scancełar la gà na cronołogia:',
'confirmdeletetext'           => "Te stè par scancełar permanentemente da el database na paxèna o na imagine, insieme a tuta la so cronołogia.
Par cortesia, conferma che l'è to intenzsion proçedere a tałe scancełazsion, conferma che te ghè piena consapevołezsa de łe conseguenzse de la to azsion, e conferma che la to azsion l'è pienamente ottemperante a łe regołe stabilíe ne ła
[[{{MediaWiki:Policy-url}}]].",
'actioncomplete'              => 'Azsión completà',
'deletedtext'                 => 'La paxèna "<nowiki>$1</nowiki>" l\'è stà scancełà. Varda $2 par un ełenco de łe paxène scancełae de reçente.',
'deletedarticle'              => 'Scancełà "$1"',
'dellogpage'                  => 'Scancełazsión',
'dellogpagetext'              => 'Qui de seguito ghe xe un ełenco de łe pàxene scancełae de reçente.',
'deletionlog'                 => 'Log de scancełasión',
'reverted'                    => 'Ripristinata versione precedente',
'deletecomment'               => 'Motivo de ła scancełazion:',
'deleteotherreason'           => 'Altra motivazion o motivazion agiuntiva:',
'deletereasonotherlist'       => 'Altra motivazion',
'rollback'                    => 'Usa una revisione precedente',
'rollbacklink'                => 'tira indrìo',
'rollbackfailed'              => 'Rollback non riuzsio',
'cantrollback'                => "No xè mia possibiłe tornar a na verzsión precedente: l'ultima modifica xè stà apportà da l'unico utente che gà laorà a stò arthicoło.",
'alreadyrolled'               => "Non xè mia possibile effettuare el rollback de [[$1]] da [[User:$2|$2]] ([[User talk:$2|discussion]]); qualcun altro gà xà modificà o effetuà el rollback de sta voçe. L'ultima modefega l'è stà fata da [[User:$3|$3]] ([[User talk:$3|discussion]]).",
'editcomment'                 => 'El commento a la modifica xera: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Anułate łe modifeghe de [[Special:Contributions/$2|$2]] ([[User talk:$2|discussion]]), riportà a ła verzsion preçedente de [[User:$1|$1]]', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'protectlogpage'              => 'Log de protezsión',
'protectedarticle'            => 'proteto "[[$1]]"',
'protect-title'               => 'Protezsion de "$1"',
'protect-legend'              => 'Conferma la protezsion',
'protectcomment'              => 'Motivo de ła protezsion',
'protectexpiry'               => 'Scadenza:',
'protect_expiry_invalid'      => 'Scadenza mìa valida.',
'protect_expiry_old'          => 'Scadenza zà passà.',
'protect-unchain'             => 'Scołega i permessi de spostamento',
'protect-text'                => 'Sto modulo qua el consente de védar e modificar el livel de protezion par la pagina <strong><nowiki>$1</nowiki></strong>.',
'protect-locked-access'       => 'No te ghè i parmessi necessari par modificar i livèi de protezion de la pagina.
Le impostazion atuali par la pagina le xe <strong>$1</strong>:',
'protect-cascadeon'           => 'Al momento sta pagina la xe blocà parché la xe inclusa {{PLURAL:$1|ne la pagina indicà de seguito, par la quale|ne le pagine indichè de seguito, par le quali}} xe ativa la protezion ricorsiva. Se pol modificar el livel de protezion individual de la pagina, ma le inpostazion derivanti da la protezion ricorsiva no le sarà mìa modificà.',
'protect-default'             => '(predefinìo)',
'protect-fallback'            => 'Ghe vole el parmesso de "$1"',
'protect-level-autoconfirmed' => 'Solo utenti registrai',
'protect-level-sysop'         => 'Solo aministradori',
'protect-summary-cascade'     => 'ricorsiva',
'protect-expiring'            => 'scadenza: $1 (UTC)',
'protect-cascade'             => "Protezion ricorsiva (l'estende la protezion a tute le pagine incluse in sta qua).",
'protect-cantedit'            => 'No te pol modificar i livèi de protezion par la pagina, parché no te ghè mìa i parmessi necessari par modificar la pagina stessa.',
'restriction-type'            => 'Parmesso:',
'restriction-level'           => 'Livel de restrizion',

# Restrictions (nouns)
'restriction-edit' => 'Modifega',

# Undelete
'undelete'                 => 'Recupera na pàxena scancełà',
'undeletepage'             => 'Varda e recupera pàxene scancełàe',
'viewdeletedpage'          => 'Varda łe pàxene scancełàe',
'undeletepagetext'         => "Le pagine qui di seguito indicate sono state cancellate, ma sono ancora in archivio e pertanto possono essere recuperate. L'archivio viene svuotato periodicamente.",
'undeleteextrahelp'        => "Par recuperar la pàxena par intiero, lassa tute łe casełe desełezionàe e struca '''''Ripristina'''''. Par efetuar un ripristino sełetivo, seleziona łe casełe corispondenti a łe revixion da ripristinar e struca '''''Ripristina'''''. Strucando '''''Reset''''' vegnarà deselezionàe tute łe casełe e svodà el posto par el comento.",
'undeletehistory'          => 'Recuperando sta pàxena, tute łe so revixion le vegnarà inserìe da novo ne ła rełativa cronołogia. Se dopo ła scancełazion xè stà creà na nova pàxena col stesso titoło, łe revixion recuperà le sarà inserìe ne ła cronołogia e ła version atualmente online de ła pàxena no la sarà modifegà.',
'undeletehistorynoadmin'   => 'La pàxena la xè stà scancełà. El motivo de ła scancełazion el xè indicà de seguito, insieme ai dati de i utenti che i gavea modifegà ła pàxena prima de ła scancełazion. El testo contegnù ne łe revixion scancełàe i pol védarlo solo i aministradori.',
'undeletebtn'              => 'RIPRISTINA!',
'undeletedarticle'         => 'Recuperà "$1"',
'undeletedrevisions'       => '{{PLURAL:$1|Una revision recuperà|$1 revision recuperà}}',
'undeletedrevisions-files' => '{{PLURAL:$1|Una revision|$1 revision}} e $2 file recuperà',
'undeletedfiles'           => '{{PLURAL:$1|Un file recuperà|$1 file recuperà}}',
'cannotundelete'           => "El recupero no'l xè riussìo: qualchedun altro el podarià aver xà recuperà ła pàxena.",
'undeletedpage'            => "<big>'''$1 xè stà recuperà'''</big>

Consultare el [[Special:Log/delete|log delle scancełazsioni]] par vardare łe scancełazsion e i recuperi pì reçenti.",

# Namespace form on various pages
'namespace'      => 'Namespace:',
'invert'         => 'inverti ła selezsión',
'blanknamespace' => '(Prinzsipałe)',

# Contributions
'contributions' => 'Contributi utente',
'mycontris'     => 'i me contributi',
'contribsub2'   => 'Par $1 ($2)',
'nocontribs'    => 'Nessuna modifica trovata conformemente a questi criteri.',
'uctop'         => '(ultima par ła pàxena)',
'month'         => 'A partir dal mese (e precedenti):',
'year'          => "A partir da l'ano (e precedenti):",

'sp-contributions-newbies-sub' => 'Par i novi utenti',
'sp-contributions-blocklog'    => 'Registro dei blochi',

# What links here
'whatlinkshere'       => 'Pàxene che le punta qua',
'whatlinkshere-title' => 'Pagine che punta a $1',
'linklistsub'         => '(Lista di link)',
'linkshere'           => "Ste pagine qua le contien dei colegamenti a '''[[:$1]]''':",
'nolinkshere'         => "Nissuna pagina la contien colegamenti che punta a '''[[:$1]]'''.",
'isredirect'          => 'pagina de reindirizamento',
'istemplate'          => 'inclusion',
'whatlinkshere-prev'  => '{{PLURAL:$1|precedente|precedenti $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|sucessivo|sucessivi $1}}',
'whatlinkshere-links' => '← colegamenti',

# Block/unblock
'blockip'            => 'Blocca indirizso IP',
'blockiptext'        => "Usare el moduło sottostante par bloccare l'accesso in scrittura ad uno speçifico utente o indirizso IP. El bloco dev'essere operà par prevegnere ati de vandalismo e in stretta osservanzsa de ła [[{{MediaWiki:Policy-url}}|policy de {{SITENAME}}]]. Speçificare in dettałio el motivo de el bloco nel campo seguente (ad es. indicando i titołi de łe paxène oggeto de vandalismo).",
'ipaddress'          => 'Indiriszo IP (IP Address)',
'ipadressorusername' => 'Indiriszo IP o nome utente',
'ipbexpiry'          => 'Scadenzsa',
'ipbreason'          => 'Motivazsión',
'ipbsubmit'          => 'Blocca sto indirizso IP',
'ipbother'           => 'Other time',
'ipboptions'         => '2 ore:2 hours,1 giòrno:1 day,3 giòrni:3 days,1 setimana:1 week,2 setimane:2 weeks,1 mese:1 month,3 mesi:3 months,6 mesi:6 months,1 ano:1 year,par senpre:infinite', # display1:time1,display2:time2,...
'badipaddress'       => "L'indirizso IP indicà non xè coreto.",
'blockipsuccesssub'  => 'Blocco eseguio',
'blockipsuccesstext' => 'L\'indirizzo IP "$1" l\'è sta bloccà.
<br />Varda [[Special:Ipblocklist|lista IP bloccati]].',
'unblockip'          => ' Sblocca indirizzo IP',
'unblockiptext'      => 'Usa il modulo sottostante per restituire il diritto di scrittura ad un indirizzo IP precedentemente bloccato.',
'ipusubmit'          => 'Sblocca sto indirizso IP',
'ipblocklist'        => 'Indiriszi IP bloccai',
'blocklistline'      => '$1, $2 gà bloccà $3 fino al $4',
'infiniteblock'      => 'infinito',
'blocklink'          => 'bloca',
'unblocklink'        => 'sbloca',
'contribslink'       => 'contributi',
'autoblocker'        => 'Bloccà automaticamente parché l\'indirisso IP xè condiviso con "$1". Motivo "$2".',
'blocklogpage'       => 'Block_log',
'blocklogentry'      => 'gà blocà [[$1]] par un periodo de $2 $3',
'blocklogtext'       => "Sto qua xè un elenco de azsioni de blocco e sblocco degli indirizzi IP. Gli indirizzi bloccai in automatico non xè elencai. Vrda [[Special:Ipblocklist|elenco IP bloccati]] per l'elenco degli indirizzi il cui blocco xè operativo.",
'ipb_expiry_invalid' => 'Tempo de scadenzsa non valido. Controlla el [http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html manuale de tar] par la sintassi esatta.',
'ip_range_invalid'   => 'Intervało IP non vałido.',
'proxyblocksuccess'  => 'Fatto.',

# Developer tools
'lockdb'              => 'Blocca el database',
'unlockdb'            => 'Sbloca el database',
'lockdbtext'          => 'Bloccare il database sospenderà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere non consentirà a nessuno di eseguire operazioni che richiedano modifiche del database.<br /><br />
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare e, soprattutto, che il prima possibile sbloccherai nuovamente il database, ripristinandone la corretta funzionalità, non appena avrai terminato le tue manutenzioni.',
'unlockdbtext'        => 'Sbloccare il database ripristinerà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere di eseguire operazioni che richiedano modifiche del database.
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare.',
'lockconfirm'         => 'Sì, effettivamente intendo, sotto la mia responsabilità, bloccare il database.',
'unlockconfirm'       => ' Sì, effettivamente intendo, sotto la mia responsabilità, sbloccare il database.',
'lockbtn'             => 'Blocca el database',
'unlockbtn'           => 'Sbloca el database',
'locknoconfirm'       => 'Non hai spuntato la casellina di conferma.',
'lockdbsuccesssub'    => 'Blocco de el database eseguio',
'unlockdbsuccesssub'  => 'Sblocco del database eseguito, rimosso blocco',
'lockdbsuccesstext'   => 'El database el xe stà blocà.<br />
Tiente in mente de [[Special:Unlockdb|sblocarlo]] co te ghè finìo de far manutenzion.',
'unlockdbsuccesstext' => ' Il database di {{SITENAME}} è stato sbloccato.',

# Move page
'move-page-legend'        => 'Spostamento de paxèna',
'movepagetext'            => "Col modulo qua soto te podi rinominar na pàxena, spostando anca tuta la so cronołogia al novo nome.
El vecio titoło el deventarà automaticamente un rimando (redirect) che punta al novo titoło.
I colegamenti a la vecia pàxena no i sarà mìa agiornài (e i puntarà quindi al rimando);
tiente in mente de [[Special:Manutenzsion|controłar con cura]] che no se crea dopi redirect o redirect interoti.
Resta ne la to responsabilità de controlar che i colegamenti i continua a puntar verso dove i deve dirìgiarse.

Nota ben: la pàxena '''no''' la sarà spostà se ghe fusse xà na voçe col nome novo, a meno che no la sia na pàxena voda o un rimando, e senpre che no la gabia cronologia.
Questo significa che, se te fè un eror, te podi novamente rinominar na pàxena col vecio titoło, ma no te podi sovrascrìvar na pàxena xà esistente.

'''OCIO!'''
Sto canbiamento drastico el podarìa crear contratenpi che no se se speta, specialmente se se tratta de na pàxena molto visità.
Acèrtete de ver ben valutà le conseguenze del spostamento, prima de procédar.",
'movepagetalktext'        => "La corispondente pàxena de discussion la sarà spostà automaticamente insieme a ła pàxena prinçipałe, '''trane che nei seguenti casi:'''
* El spostamento de ła pàxena el xè tra namespace diversi
* In corispondenza del novo titoło ghe xe xà na pàxena de discussion (mìa voda)
* La caseła chi soto la xè stà desełezionà.",
'movearticle'             => 'Rinomina voçe',
'movenologin'             => 'Non te ghè efetuà el login',
'movenologintext'         => 'Te ghè da èssar un utente registrà ed aver efetuà el [[Special:Userlogin|login]] par poder spostar na pàxena.',
'newtitle'                => 'Al novo titoło de',
'move-watch'              => "Tien d'ocio",
'movepagebtn'             => 'Sposta sta pàxena',
'pagemovedsub'            => 'Spostamento efetuà con sucesso',
'movepage-moved'          => '<big>\'\'\'"$1" la xe stà spostà al titolo "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => "Na pàxena con sto nome la existe xà, opure el nome che te ghè sielto no'l xè vałido.<br />Siegli, par piaser, un titoło diverso par ła voçe.",
'talkexists'              => "La pagina è stata spostata correttamente, ma non si è potuto spostare la pagina di discussione perché ne esiste già un'altra con il nuovo titolo. Per favore, modifica manualmente i contenuti delle due pagine discussione, così da mantenerle entrambe per non perdere potenzialmente interessanti riflessioni.",
'movedto'                 => 'spostà a',
'movetalk'                => 'Sposta anche la corrispondente pagina "discussione", se possibile.',
'talkpagemoved'           => 'Anca ła corispondente pàxena de discussion la xè stà spostà.',
'talkpagenotmoved'        => 'La corispondente pàxena de discussion <strong>no la xè mìa stà spostà</strong>.',
'1movedto2'               => '[[$1]] spostà a [[$2]]',
'1movedto2_redir'         => "$1 spostà a $2 co'n redirect",
'movelogpage'             => 'Registro dei spostamenti',
'movelogpagetext'         => 'Lista de pàxene spostàe.',
'movereason'              => 'Reason',
'revertmove'              => 'ripristina',
'delete_and_move'         => 'Scanceła e sposta',
'delete_and_move_text'    => '==Scancełazsion richiesta==

La voçe specificà come destinazsion "[[$1]]" l\'esiste già. Vóto scancełarlo par proseguire con ło spostamento?',
'delete_and_move_confirm' => 'Si! Scancèła ła pàxena',
'delete_and_move_reason'  => 'Scancełà par rendere possibile lo spostamento',
'selfmove'                => 'El novo titoło el xè conpagno del vecio; no se pol spostar ła pàxena su de ela.',
'immobile_namespace'      => "El titolo de destinazion l'è de tipo speciale; no se pol spostar pàxene in quel namespace.",

# Export
'export'          => 'Esporta pàxene',
'exporttext'      => 'Te podi esportar el testo e modificar ła cronołogia de na speçifica paxèna o de un gruppo de paxène raggruppae in XML; questo el pode in seguito essere importà in un altro wiki che utilixa el software MediaWiki, trasformà, o tegnù semplicemente par el to personałe divertimento.',
'exportcuronly'   => "Includi soło ła verzion attuałe, non l'intera cronołogia",
'exportnohistory' => "----
'''Ocio!''' Par motivi ligà a le prestazion del sistema xè stà disabiłità l'esportazion de tuta ła storia de łe pàxene fata co sto modulo.",

# Namespace 8 related
'allmessages'               => 'Tuti i messaj de sistema',
'allmessagesdefault'        => 'Testo de default',
'allmessagescurrent'        => "Testo come che el xe 'deso",
'allmessagestext'           => "Sta quà l'è na lista de tutti i messaggi disponibili nel namespace MediaWiki:",
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' no'l xè supportà parché '''\$wgUseDatabaseMessages''' no'l xè ativo.",
'allmessagesfilter'         => 'Filto su i messaj:',
'allmessagesmodified'       => 'Mostra soło quełi modefegà.',

# Thumbnails
'thumbnail-more'  => 'Ingrandissi',
'thumbnail_error' => 'Eror ne la creazion de la miniatura: $1',

# Special:Import
'import'                   => 'Inporta pàxene',
'import-interwiki-text'    => 'Seleziona un projeto wiki e el titoło de ła pàxena da inportar.
Le date de publicazion e i nomi de i autori de łe varie version i sarà conservà.
Tute łe operazion de inportazion trans-wiki łe xè notà nel [[Special:Log/import|registro de inportazion]].',
'import-interwiki-history' => "Copia l'intiera cronołogia de sta pàxena",
'importtext'               => "Per favore, esporta el file da la wiki de origine usando l'utility Special:Export, salvalo su el to disco e fa l'upload qua.",
'importstart'              => 'Inportazion de łe pàxene in corso...',
'import-revision-count'    => '{{PLURAL:$1|una revixion importà|$1 revixion importae}}',
'importnopages'            => 'Nissuna pàxena da inportar.',
'importfailed'             => 'Importazsion falía: $1',
'importunknownsource'      => "Tipo de origine sconozsuo par l'importazsion",
'importcantopen'           => 'Impozsibiłe verdere el file de importazsion',
'importbadinterwiki'       => 'Cołegamento inter-wiki errà',
'importnotext'             => 'Testo vodo o mancante',
'importsuccess'            => 'Inportazion avegnù con sucesso!',
'importhistoryconflict'    => 'Esiste na revision de la cronołogia in conflito (sta pàxena la podarìa èssar xà stà inportà)',
'importnosources'          => "Non xè stà definia na fonte par l'importazsion transwiki; l'importazsion direta de ła cronołogia non xè ativa.",
'importnofile'             => "Non xè stà caricà nessun file par l'importazsion,",

# Import log
'importlogpage'                    => 'Importazsion',
'importlogpagetext'                => "Registro de łe inportazion d'ufiçio de pàxene provenienti da altre wiki, conplete de cronołogia.",
'import-logentry-upload'           => 'gà importà $1 tramite upload',
'import-logentry-upload-detail'    => '{{PLURAL:$1|una revixion importà|$1 revixion importae}}',
'import-logentry-interwiki'        => 'gà trasferìo da altra wiki ła pàxena $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|una revixion importà|$1 revixion importae}} da $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'La me paxèna utente',
'tooltip-pt-anonuserpage'         => 'La paxèna utente de l',
'tooltip-pt-mytalk'               => 'Le me discussion',
'tooltip-pt-anontalk'             => 'Discussioni riguardo le modifiche fate da sto ip',
'tooltip-pt-preferences'          => 'Le me preferenzse',
'tooltip-pt-watchlist'            => 'La lista de le paxène che te stè tegnendo soto ocio.',
'tooltip-pt-mycontris'            => 'La lista de i me contributi',
'tooltip-pt-login'                => 'Te consigliemo de registrarte, ma non l',
'tooltip-pt-anonlogin'            => 'Te consigliemo de registrarte, ma non l',
'tooltip-pt-logout'               => 'Log out (esci)',
'tooltip-ca-talk'                 => 'Varda łe discussion rełative a la voçe',
'tooltip-ca-edit'                 => 'Te podi modificar sta paxèna. Per favor usa el botton de anteprima prima de salvare.',
'tooltip-ca-addsection'           => 'Xonta un commento a sta discussion.',
'tooltip-ca-viewsource'           => 'Sta paxèna xè proteta, ma te podi vedar el suo codexe sorjente.',
'tooltip-ca-history'              => 'Verzsion preçedenti de sta paxèna.',
'tooltip-ca-protect'              => 'Protedj sta paxèna',
'tooltip-ca-delete'               => 'Scanceła sta paxèna',
'tooltip-ca-undelete'             => 'Ripristina la paxèna come l',
'tooltip-ca-move'                 => 'Sposta sta paxèna a un altro titoło',
'tooltip-ca-watch'                => 'Xonta sta paxèna a l',
'tooltip-ca-unwatch'              => 'Cava sta paxèna da l',
'tooltip-search'                  => 'Zserca in {{SITENAME}}',
'tooltip-p-logo'                  => 'Paxèna prinzsipałe',
'tooltip-n-mainpage'              => 'Visita la Paxèna prinzsipałe',
'tooltip-n-portal'                => 'Descrizsion del projeto, cosa te podi far, e dove trovar le robe',
'tooltip-n-currentevents'         => 'Eventi de atuałità',
'tooltip-n-recentchanges'         => 'La lista de le ultime modifiche a sta wiki.',
'tooltip-n-randompage'            => 'Mostra na paxèna a caso',
'tooltip-n-help'                  => 'Raccolta de manuałi.',
'tooltip-n-sitesupport'           => 'Iútane',
'tooltip-t-whatlinkshere'         => 'Lista de tute le paxène che le porta a sta',
'tooltip-t-recentchangeslinked'   => 'Lista de le ultime modifiche a le paxène linkae da sta quà.',
'tooltip-t-contributions'         => 'Lista de i contributi de sto utente',
'tooltip-t-emailuser'             => 'Manda n',
'tooltip-t-upload'                => 'Meti imagini o file multimediałi su {{SITENAME}}',
'tooltip-t-specialpages'          => 'Lista de tute łe paxène speciali',
'tooltip-ca-nstab-main'           => 'Varda la voçe rełativa',
'tooltip-ca-nstab-user'           => 'Varda la paxèna utente',
'tooltip-ca-nstab-media'          => 'Vedi la paxèna de el file multimediale',
'tooltip-ca-nstab-special'        => 'Sta quà xè na paxèna speciale, non la pode essere modificà.',
'tooltip-ca-nstab-project'        => 'Varda la paxèna del projeto',
'tooltip-ca-nstab-image'          => "Varda la paxèna dell'imagine",
'tooltip-ca-nstab-mediawiki'      => 'Varda el messajo de sistema',
'tooltip-ca-nstab-template'       => 'Varda el template',
'tooltip-ca-nstab-help'           => 'Varda la paxèna de aiuto',
'tooltip-ca-nstab-category'       => 'Varda la paxèna de la categoria',
'tooltip-minoredit'               => 'Segnała come modifega minore',
'tooltip-save'                    => 'Salva łe modifeghe',
'tooltip-preview'                 => 'Anteprima de łe modifeghe (consilià, prima de salvare!)',
'tooltip-diff'                    => 'Varda łe modifeghe apportae al testo',
'tooltip-compareselectedversions' => 'Varda łe difarenze tra łe do version selezionà de sta pàxena.',
'tooltip-watch'                   => 'Zonta sta pagina a la lista dei osservati speciali',
'tooltip-recreate'                => 'Ricrea ła pàxena anca se la xè stà scancełà',

# Metadata
'notacceptable' => 'El server wiki non xè in grado di fornire i dati in un formato łeggibiłe dal client utilixà.',

# Attribution
'anonymous'        => 'Utente(/i) anonimo(/i) de {{SITENAME}}',
'lastmodifiedatby' => "Sta paxèna xè stà modificà l'ultima volta el $2, $1 da $3.", # $1 date, $2 time, $3 user
'creditspage'      => 'Autori de ła pàxena',

# Spam protection
'spam_blanking' => 'Pàxena svodà, tute łe version le contegneva cołegamenti a $1',

# Info page
'infosubtitle'   => 'Informazion par la pàxena',
'numtalkedits'   => 'Nùmaro de modifeghe (pàxena de discussion): $1',
'numtalkauthors' => 'Nùmaro de autori distinti (pàxena de discussion): $1',

# Math options
'mw_math_modern' => 'Raccomandà par i browser pì novi',

# Patrolling
'markaspatrolleddiff'        => 'Segna la modifica come verificà',
'markaspatrolledtext'        => 'Segna sto arthicoło come verificà',
'markedaspatrolled'          => 'Segnà come verificà',
'markedaspatrolledtext'      => 'La revixion selezsionà xè stà segnà come verificata.',
'markedaspatrollederror'     => 'Impossibiłe contrassegnare ła voçe come verificà',
'markedaspatrollederrortext' => 'Occorre speçificare na revixion da contrazsegnare come verificà.',

# Image deletion
'deletedrevision' => 'Vecia verzsion scancełà $1',

# Browsing diffs
'previousdiff' => '← Difarenza precedente',
'nextdiff'     => 'Prossima difarenza →',

# Media information
'imagemaxsize'         => 'Dimension massima de le imagini su le relative pagine de descrizion:',
'file-info-size'       => '($1 × $2 pixel, dimensioni: $3, tipo MIME: $4)',
'file-nohires'         => '<small>No xe mìa disponibili versioni a risoluzion piassè granda.</small>',
'svg-long-desc'        => '(file in formato SVG, dimension nominali $1 × $2 pixel, dimension del file: $3)',
'show-big-image'       => 'Version ad alta risoluzion',
'show-big-image-thumb' => '<small>Dimension de sta anteprima: $1 × $2 pixel</small>',

# Special:Newimages
'newimages'             => 'Imagini nove',
'imagelisttext'         => "Qua ghe xe na lista de '''$1''' {{PLURAL:$1|file|files}} ordinà par $2.",
'noimages'              => 'Non ghè gnente da vardare.',
'ilsubmit'              => 'Zserca',
'sp-newimages-showfrom' => 'Mostra i file piassè novi a partire da le ore $2 del $1',

# Bad image list
'bad_image_list' => 'El formato el xe el seguente:

Vien considerà solo i elenchi puntati (righe che scuminsi col caratere *).
El primo colegamento su ogni riga el gà da èssar un colegamento a un file indesiderà.
I colegamenti sucessivi, su la stessa riga, i xe considerà come ecezion (cioè pagine in do che el file el pode èssar riciamà normalmente).',

# Metadata
'metadata'          => 'Metadati',
'metadata-help'     => 'Sto file qua el contien informazion agiuntive, probabilmente zontà da la fotocamera o dal scanner doparà par crearlo o digitalizarlo. Se el file el xe stà modificà, certi detagli i podarìa no corispóndar più a le modifiche aportà.',
'metadata-expand'   => 'Mostra detagli',
'metadata-collapse' => 'Scondi detagli',
'metadata-fields'   => "I canpi relativi ai metadati EXIF elencà in sto messagio i vegnarà mostrà su la pagina de l'imagine quando la tabela dei metadati la xe presentà in forma curta. Par inpostazion predefinìa, i altri canpi i vegnarà sconti.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength", # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Larghezsa',
'exif-imagelength'                 => 'Altezsa',
'exif-bitspersample'               => 'Bit par campione',
'exif-compression'                 => 'Meccanismo de comprezsion',
'exif-photometricinterpretation'   => 'Strutura de i pixel',
'exif-samplesperpixel'             => 'Numero de łe componenti',
'exif-planarconfiguration'         => 'Disposizsion de i dati',
'exif-ycbcrsubsampling'            => 'Raporto de campionamento Y / C',
'exif-ycbcrpositioning'            => 'Posizsionamento componenti Y e C',
'exif-xresolution'                 => 'Risoluzsion orixontałe',
'exif-yresolution'                 => 'Risoluzsion verticałe',
'exif-resolutionunit'              => 'Unità de mixura rizsoluzsion X e Y',
'exif-stripoffsets'                => 'Posizsion de i dati imagine',
'exif-rowsperstrip'                => 'Numero righe par striscia',
'exif-stripbytecounts'             => 'Numero de byte par strizsia compressa',
'exif-jpeginterchangeformat'       => 'Posizsion byte SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Numero de byte de dati JPEG',
'exif-transferfunction'            => 'Funzsione de trasferimento',
'exif-whitepoint'                  => 'Coordinate cromatiche de el punto de bianco',
'exif-primarychromaticities'       => 'Coordinate cromatiche de i cołori primari',
'exif-ycbcrcoefficients'           => 'Coeficienti matriçe de trasformazsion spazsi de i cołori',
'exif-referenceblackwhite'         => 'Copia de vałori de riferimento (nero e bianco)',
'exif-datetime'                    => 'Data e ora de modifica de el file',
'exif-imagedescription'            => "Descrizsion de l'imagine",
'exif-make'                        => 'Produtore fotocamera',
'exif-model'                       => 'Modeło fotocamera',
'exif-copyright'                   => 'nformazsion su el copyright',
'exif-exifversion'                 => 'Verzsion de el formato Exif',
'exif-flashpixversion'             => 'Verzsione Flashpix supportà',
'exif-colorspace'                  => 'Spazio de i cołori',
'exif-componentsconfiguration'     => 'Significato de ciascuna componente',
'exif-compressedbitsperpixel'      => 'Modalità de compression imagine',
'exif-pixelydimension'             => 'Larghezsa efetiva imagine',
'exif-pixelxdimension'             => 'Altezsa efetiva imagine',
'exif-makernote'                   => 'Note de el produtore',
'exif-usercomment'                 => "Note de l'utente",
'exif-relatedsoundfile'            => 'File audio cołegà',
'exif-datetimeoriginal'            => 'Data e ora de creazsion de i dati',
'exif-datetimedigitized'           => 'Data e ora de digitałixazsion',
'exif-subsectime'                  => 'Data e ora, frazsion de secondo',
'exif-subsectimeoriginal'          => 'Data e ora de creazsion, frazsion de secondo',
'exif-subsectimedigitized'         => 'Data e ora de digitałixazsion, frazsion de secondo',
'exif-exposuretime'                => 'Tempo de esposizsion',
'exif-fnumber'                     => 'Rapporto focałe',
'exif-exposureprogram'             => 'Programa de esposizsion',
'exif-spectralsensitivity'         => 'Sensibilità spetrałe',
'exif-isospeedratings'             => 'Sensibiłità ISO',
'exif-oecf'                        => 'Fattore de converzsion optoełetronica',
'exif-shutterspeedvalue'           => 'Tenpo de esposizsion',
'exif-exposurebiasvalue'           => 'Corezsion esposizsion',
'exif-maxaperturevalue'            => 'Mazsima vérta',
'exif-subjectdistance'             => 'Distanzsa de el sojeto',
'exif-meteringmode'                => 'Metodo de misurazsion',
'exif-lightsource'                 => 'Sorgente łuminoxa',
'exif-flash'                       => 'Caratteristiche e stato de el flash',
'exif-focallength'                 => 'Distanzsa focałe obiettivo',
'exif-subjectarea'                 => 'Area inquadrante el sojeto',
'exif-spatialfrequencyresponse'    => 'Risposta in frequenzsa spazsiałe',
'exif-focalplanexresolution'       => 'Risoluzsion X sul piano focałe',
'exif-focalplaneyresolution'       => 'Risoluzsion Y sul piano focałe',
'exif-focalplaneresolutionunit'    => 'Unità de misura risoluzsion sul piano focałe',
'exif-subjectlocation'             => 'Posizsion de el sojeto',
'exif-exposureindex'               => 'Sensibilità impostà',
'exif-sensingmethod'               => 'Metodo de riłevazsion',
'exif-scenetype'                   => 'Tipo de inquadratura',
'exif-cfapattern'                  => 'Disposizione filtro cołore',
'exif-customrendered'              => 'Elaborazsion personałixà',
'exif-exposuremode'                => 'Modalità de espoxizsion',
'exif-whitebalance'                => 'Biłanciamento de el bianco',
'exif-digitalzoomratio'            => 'Rapporto zoom digitałe',
'exif-focallengthin35mmfilm'       => 'Focałe equivalente su 35 mm',
'exif-scenecapturetype'            => 'Tipo de acquixizsion',
'exif-gaincontrol'                 => 'Controło inquadratura',
'exif-contrast'                    => 'Controło contrasto',
'exif-saturation'                  => 'Controło saturazsion',
'exif-sharpness'                   => 'Controło nitidezsa',
'exif-devicesettingdescription'    => 'Descrizsion impostazsioni dispositivo',
'exif-subjectdistancerange'        => 'Scała distanzsa sojeto',
'exif-imageuniqueid'               => 'ID univoco imagine',
'exif-gpsversionid'                => 'Verzsion de i tag GPS',
'exif-gpsaltituderef'              => "Riferimento par l'altitudine",
'exif-gpstimestamp'                => 'Ora GPS (orołogio atomico)',
'exif-gpssatellites'               => 'Satelliti usai par ła mixurazsion',
'exif-gpsstatus'                   => 'Stato de el riçevitore',
'exif-gpsmeasuremode'              => 'Modalità de misurazsion',
'exif-gpsdop'                      => 'Precixion de ła mixurazsion',
'exif-gpsspeedref'                 => 'Unità de mixura de ła veloçità',
'exif-gpsspeed'                    => 'Veloçità del riçevitore GPS',
'exif-gpstrackref'                 => 'Riferimento par ła direzsion movimento',
'exif-gpstrack'                    => 'Direzsion de el movimento',
'exif-gpsimgdirectionref'          => "Riferimento par ła direzsion de l'imagine",
'exif-gpsimgdirection'             => "Direzsion de l'immagine",
'exif-gpsmapdatum'                 => 'Rilevamento geodetico usà',
'exif-gpsdestlatituderef'          => 'Riferimento par ła latitudine de ła destinazsion',
'exif-gpsdestlatitude'             => 'Latitudine de ła destinazsion',
'exif-gpsdestlongituderef'         => 'Riferimento par ła longitudine de ła destinazsion',
'exif-gpsdestlongitude'            => 'Longitudine de ła destinazsion',
'exif-gpsdestbearingref'           => 'Riferimento par ła direzsion de ła destinazsion',
'exif-gpsdestbearing'              => 'Direzsion de ła destinazsion',
'exif-gpsdestdistanceref'          => 'Riferimento par ła distanzsa de ła destinazsion',
'exif-gpsdestdistance'             => 'Distanzsa de ła destinazsion',
'exif-gpsprocessingmethod'         => 'Nome de el metodo de elaborazsion GPS',
'exif-gpsareainformation'          => 'Nome de ła xòna GPS',
'exif-gpsdifferential'             => 'Corezsion diferenzsiałe GPS',

'exif-orientation-1' => 'Normałe', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Roerzsà orixontalmente', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Ruotà de 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Roersà verticalmente', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Ruotà 90° in senso antiorario e roersà verticalmente', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Ruotà 90° in senso orario', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Ruotà 90° in senso orario e capovolto verticalmente', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Ruotà 90° in senso antiorario', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'a blochi (chunky)',

'exif-xyresolution-i' => '$1 punti par połiçe (dpi)',
'exif-xyresolution-c' => '$1 punti par çentimetro (dpc)',

'exif-exposureprogram-0' => 'Non definio',
'exif-exposureprogram-1' => 'Manuałe',
'exif-exposureprogram-4' => "Priorità all'esposizsion",
'exif-exposureprogram-5' => 'Artistico (orientà a ła profondità de campo)',
'exif-exposureprogram-6' => 'Sportivo (orientà a ła veloçità de riprexa)',

'exif-meteringmode-0' => 'Sconozsuo',
'exif-meteringmode-2' => 'Media pesà çentrà',
'exif-meteringmode-6' => 'Parzsiałe',

'exif-lightsource-0'   => 'Sconozsua',
'exif-lightsource-1'   => 'Luçe diurna',
'exif-lightsource-2'   => 'Lampada a floreçenzsa',
'exif-lightsource-3'   => 'Lampada al tungsteno (a incandeçenzsa)',
'exif-lightsource-9'   => 'Tenpo beło',
'exif-lightsource-10'  => 'Nùvoło',
'exif-lightsource-11'  => 'In ombrìa',
'exif-lightsource-12'  => 'Daylight fluorescent (D 5700 â€“ 7100K)',
'exif-lightsource-13'  => 'Day white fluorescent (N 4600 â€“ 5400K)',
'exif-lightsource-14'  => 'Cool white fluorescent (W 3900 â€“ 4500K)',
'exif-lightsource-15'  => 'White fluorescent (WW 3200 â€“ 3700K)',
'exif-lightsource-17'  => 'Luçe standard A',
'exif-lightsource-18'  => 'Luçe standard B',
'exif-lightsource-19'  => 'Luçe standard C',
'exif-lightsource-20'  => 'Iłuminante D55',
'exif-lightsource-21'  => 'Iłuminante D65',
'exif-lightsource-22'  => 'Iłuminante D75',
'exif-lightsource-23'  => 'Iłuminante D50',
'exif-lightsource-255' => 'Altra sorgente łuminoxa',

'exif-focalplaneresolutionunit-2' => 'połiçi',

'exif-sensingmethod-1' => 'Non definio',
'exif-sensingmethod-2' => 'Sensore area cołore a 1 chip',
'exif-sensingmethod-3' => 'Sensore area cołore a 2 chip',
'exif-sensingmethod-4' => 'Sensore area cołore a 3 chip',
'exif-sensingmethod-5' => 'Sensore area cołore sequenzsiałe',
'exif-sensingmethod-7' => 'Sensore triłineare',
'exif-sensingmethod-8' => 'Sensore łineare cołore sequenzsiałe',

'exif-scenetype-1' => 'Fotografia direta',

'exif-customrendered-0' => 'Processo normałe',
'exif-customrendered-1' => 'Processo personałixà',

'exif-exposuremode-0' => 'Esposizsion automatega',
'exif-exposuremode-1' => 'Esposizsion manuałe',
'exif-exposuremode-2' => 'Bracketing automatego',

'exif-whitebalance-0' => 'Biłanciamento de el bianco automatico',
'exif-whitebalance-1' => 'Biłanciamento de el bianco manuałe',

'exif-scenecapturetype-2' => 'Ritrato',
'exif-scenecapturetype-3' => 'Noturna',

'exif-gaincontrol-1' => 'Enfasi par basso guadagno',
'exif-gaincontrol-2' => 'Enfasi par alto guadagno',
'exif-gaincontrol-3' => 'Deenfasi par basso guadagno',
'exif-gaincontrol-4' => 'Deenfasi par alto guadagno',

'exif-contrast-0' => 'Normałe',

'exif-saturation-0' => 'Normałe',
'exif-saturation-1' => 'Bassa saturazsion',
'exif-saturation-2' => 'Alta saturazsion',

'exif-sharpness-0' => 'Normałe',
'exif-sharpness-1' => 'Minore nitidezsa',
'exif-sharpness-2' => 'Major nitidezsa',

'exif-subjectdistancerange-0' => 'Sconozsua',
'exif-subjectdistancerange-2' => 'Sojeto viçin',
'exif-subjectdistancerange-3' => 'Sojeto łontano',

'exif-gpsstatus-a' => 'Mixurazsion in corzso',
'exif-gpsstatus-v' => 'Mixurazsion interoperabiłe',

'exif-gpsmeasuremode-2' => 'Misurazsion bidimensionałe',
'exif-gpsmeasuremode-3' => 'Misurazsion tridimensionałe',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Chiłometri orari',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direzsion reałe',
'exif-gpsdirection-m' => 'Direzsion magnetica',

# External editor support
'edit-externally'      => 'Modifica stò file usando un programma esterno',
'edit-externally-help' => 'Per maggiori informazioni varda le [http://meta.wikimedia.org/wiki/Help:External_editors istruzsioni] (in inglese)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tute',
'namespacesall' => 'Tuti',
'monthsall'     => 'tuti',

# E-mail address confirmation
'confirmemail'            => 'Conferma indirizso e-mail',
'confirmemail_text'       => "{{SITENAME}} el richiede la verifica de l'indirizo e-mail prima che te possi doparar le funzion ligà a l'e-mail.
Struca el boton qua soto par mandar na richiesta de conferma al to indirizo.
Nel messagio che te riva te catarè un colegamento che contien un codice.
Visita el colegamento col to browser par confermar che el to indirizo el xe valido.",
'confirmemail_send'       => 'Spedisi un codice de conferma via mail.',
'confirmemail_sent'       => 'Email de conferma invià.',
'confirmemail_sendfailed' => "No se riesse a inviar el messagio e-mail de conferma. 
Controla che l'indirizo no'l contegna carateri mìa validi.

El messagio de eror el xe: $1",
'confirmemail_invalid'    => 'Codice de conferma non valido. El codice podarìa esser scadù.',
'confirmemail_needlogin'  => 'Xè necessario $1 par confermare el proprio indirizso e-mail.',
'confirmemail_success'    => "El to indirizso email l'è stato confermà. Ora te podi loggarte e gòderte la wiki.",
'confirmemail_loggedin'   => 'El to indirizso email xè stà confermà.',
'confirmemail_error'      => "Qualcosa l'è andà storto nel salvar la to conferma.",
'confirmemail_subject'    => "{{SITENAME}}: email par la conferma dell'indirizso",
'confirmemail_body'       => 'Qualcheduni, probabilmente ti stesso da l\'indirizo IP $1, el ga registrà n\'account "$2" con sto indirizo e-mail su {{SITENAME}}. 

Par confermar che sto account el xe veramente tuo e poder ativar le funzion relative a l\'e-mail su {{SITENAME}}, verzi sto colegamento col to browser: 

$3 

Se l\'account *no* te lo ghè registrà ti, verzi st\'altro colegamento par anular la conferma de l\'indirizo:

$5

El codice de conferma el scadrà in automatico a le $4.',

# Delete conflict
'deletedwhileediting' => 'Ocio: Sta pàxena la xè stà scancełà dopo che te ghè scominzià a modificarla!',
'confirmrecreate'     => "L'utente [[User:$1|$1]] ([[User talk:$1|discussion]]) ga scancełà sta voçe dopo che te ghè inizsià a modificarla, con ła seguente motivazsion:
: ''$2''
Per favore conferma che te vołi veramente ricrear sta voçe.",

# action=purge
'confirm_purge' => 'Vóto scancełar ła cache in sta pàxena?

$1',

# AJAX search
'searchcontaining' => "Riçerca de łe voçi che contegne ''$1''.",
'articletitles'    => "Rizserca de łe voçi che łe inizsia par ''$1''",

# Auto-summaries
'autoredircomment' => 'Rimando a ła pàxena [[$1]]',

# Watchlist editing tools
'watchlisttools-view' => 'Varda le modifiche pertinenti',
'watchlisttools-edit' => 'Varda e modifica la lista',
'watchlisttools-raw'  => 'Modifica la lista in formato testo',

# Special:Version
'version' => 'Verzsion', # Not used as normal message but as header for the special page itself

# Special:Filepath
'filepath'         => 'Percorso de un file',
'filepath-page'    => 'Nome del file:',
'filepath-submit'  => 'Percorso',
'filepath-summary' => 'Sta pagina speciale la restituìsse el percorso conpleto de un file. Le imagini le vien mostrà a la risoluzione pi granda che se pol, par i altri tipi de file vien avià diretamente el programa associà.

Inserissi el nome del file senza el prefisso "{{ns:image}}:"',

);
