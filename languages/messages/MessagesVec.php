<?php
/** Venitian ( Vèneto )
  *
  * @addtogroup Language
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
'tog-highlightbroken'         => 'Evidenzsia i links che i punta a <br />arthicołi ancora da scrivere',
'tog-justify'                 => 'Paragrafo: giustificato',
'tog-hideminor'               => 'Nascondi le modifiche minori<br />nella pagina "Modifiche recenti"',
'tog-numberheadings'          => 'Auto-numerazione dei<br />titoli di paragrafo',
'tog-editondblclick'          => "Doppio click per modificare l'articolo<br />(richiede JavaScript)",
'tog-editsection'             => 'Modifega de łe sezsioni tramite el cołegamento [modifica]',
'tog-editsectiononrightclick' => 'Modifega de łe sezsion tramite clic destro sul titoło (richiede JavaScript)',
'tog-showtoc'                 => "Mostra l'indexe par łe paxène con pì de 3 sezsion",
'tog-rememberpassword'        => 'Ricorda la password<br />(non limitare a una sessione<br />- richiede uso di cookies)',
'tog-editwidth'               => 'Casella di edizione ampliata<br />alla massima larghezza',
'tog-watchcreations'          => 'Xonta łe paxène creae a i osservati speciałi',
'tog-watchdefault'            => 'Xonta łe paxène modifegae a i osservati speciałi',
'tog-minordefault'            => 'Indica ogni modifica come minore<br />(solo come predefinito)',
'tog-watchlisthideown'        => 'Scondi łe me modifeghe ne i osservati speciałi',
'tog-watchlisthidebots'       => 'Scondi le modifighe de i bot ne i oservati speciałi',

'skinpreview' => 'Anteprima',

# Dates
'sunday'    => 'Domenica',
'monday'    => 'Luni',
'tuesday'   => 'Marti',
'wednesday' => 'Mèrcoli',
'thursday'  => 'Xòbia',
'friday'    => 'Vènerdi',
'saturday'  => 'Sabo',
'january'   => 'genaro',
'february'  => 'Febraro',
'march'     => 'Marzso',
'april'     => 'Apriłe',
'may_long'  => 'Majo',
'july'      => 'lujo',
'august'    => 'Agosto',
'september' => 'Setenbre',
'october'   => 'Otobre',
'november'  => 'Novenbre',
'december'  => 'Diçenbre',
'jan'       => 'Gen',
'feb'       => 'Feb',
'mar'       => 'Mar',
'apr'       => 'Apr',
'may'       => 'Mag',
'jul'       => 'Lug',
'aug'       => 'Ago',
'sep'       => 'Set',
'oct'       => 'Oto',
'nov'       => 'Nov',
'dec'       => 'Diç',

# Bits of text used by many pages
'categories'      => 'Categorie',
'pagecategories'  => '{{PLURAL:$1|Categoria|Categorie}}',
'category_header' => 'Voçi n\'te ła categoria "$1"',

'mainpagetext'      => "'''MediaWiki xè stà instałà con sucesso.'''",
'mainpagedocfooter' => "Consult the [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] for information on using the wiki software.

== Getting started ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Se parla de',
'article'        => 'Voçe',
'newwindow'      => '(se verde in una nova finestra)',
'cancel'         => 'Anuła',
'qbedit'         => 'Modifega',
'qbpageoptions'  => 'Opzsion paxèna',
'qbpageinfo'     => 'Informazsion su ła paxèna',
'qbmyoptions'    => 'Le me opzsion',
'qbspecialpages' => 'Paxène speciałi',
'moredotdotdot'  => 'More...',
'mypage'         => 'La me paxèna',
'mytalk'         => 'le me discussión',
'anontalk'       => 'Discussion par sto IP',
'navigation'     => 'Navigazsión',

'errorpagetitle'    => 'Erór',
'help'              => 'Ciacołe',
'search'            => 'Serca',
'searchbutton'      => 'Serca',
'history'           => 'Versión precedenti',
'history_short'     => 'Cronołogia',
'info_short'        => 'Informazsion',
'printableversion'  => 'Version de stampa',
'edit'              => 'Modifega',
'editthispage'      => 'Modifica voçe',
'delete'            => 'Scanceła',
'deletethispage'    => 'Scanceła paxèna',
'protect'           => 'Proteggi',
'protectthispage'   => 'Protegi sta paxèna',
'unprotect'         => 'sbloca',
'unprotectthispage' => 'Cava protezsion',
'newpage'           => 'Nova paxèna',
'talkpage'          => 'Discussion',
'specialpage'       => 'Paxèna Speciałe',
'articlepage'       => 'Varda voçe',
'talk'              => 'Discussion',
'userpage'          => 'Varda paxèna Utente',
'projectpage'       => 'Varda ła paxèna de servizsio',
'imagepage'         => 'Paxèna imagine',
'viewtalkpage'      => 'Varda ła paxèna de discussion',
'otherlanguages'    => 'Altre łengoe',
'redirectedfrom'    => '(Reindirizzamento da $1)',
'redirectpagesub'   => 'Paxèna de redirect',
'lastmodifiedat'    => 'Ultima modifica $2, $1.', # $1 date, $2 time
'viewcount'         => 'Sta paxèna xè stà leta {{PLURAL:$1|na volta|$1 volte}}.',
'protectedpage'     => 'Paxèna proteta',
'jumptonavigation'  => 'Navigazsion',
'jumptosearch'      => 'zserca',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Se parla de {{SITENAME}}',
'aboutpage'         => 'Project:Se parla de',
'bugreports'        => 'Malfunzsionamenti',
'bugreportspage'    => 'Project:Malfunzsionamenti',
'copyright'         => 'Contenuto disponibile sotto $1.',
'copyrightpagename' => '{{SITENAME}} copyright',
'currentevents'     => 'Atuałità',
'currentevents-url' => 'Atuałità',
'edithelppage'      => 'Help:Come scrivere un articolo',
'helppage'          => 'Help:Ciacołe',
'mainpage'          => 'Paxèna prinzsipałe',
'portal-url'        => 'Project:Portałe Comunità',
'sitesupport'       => 'Donazsion',
'sitesupport-url'   => 'Project:Donasioni',

'badaccess' => 'Eròr ne i permessi',

'versionrequired'     => 'Verzsion $1 de MediaWiki richiesta',
'versionrequiredtext' => 'Par usare sta paxèna xè necessario dispore de ła verzsion $1 del software MediaWiki. Varda [[Special:Version]]',

'youhavenewmessages'  => 'Te ghè $1 ($2).',
'newmessageslink'     => 'Novi messaj',
'newmessagesdifflink' => 'diff to penultimate revision',
'editsectionhint'     => 'Modifica sezsión: $1',
'hidetoc'             => 'scondi',
'viewdeleted'         => 'Vedito $1?',
'restorelink'         => '$1 edit scancełai',
'feed-invalid'        => 'Modałità de sotoscrizsion de el feed non vałida.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'  => 'Voçe',
'nstab-media' => 'Media page',
'nstab-image' => 'Imagine',

# Main script and global functions
'nosuchaction'      => 'Operazsion non riconoszua',
'nosuchactiontext'  => "L'operazione richiesta con la URL immessa non è stata riconosciuta dal software MediaWiki",
'nosuchspecialpage' => 'Non xè disponibiłe nesuna paxèna speciałe con sto nome',
'nospecialpagetext' => 'Hai richiesto una pagina speciale che non è stata riconosciuta dal software MediaWiki, o che non è disponibile.',

# General errors
'error'                => 'Erór',
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
'laggedslavemode'      => 'Atenzsion: la paxèna podaria non contegnere gli ultimi aggiornamenti.',
'readonly'             => 'Accesso al database temporaneamente disabilitato',
'enterlockreason'      => 'Fornisi na spiegazsion sui motivi del blocco, includendo le probabili data ed ora de riattivazsion o de rimozsion del blocco.',
'readonlytext'         => "Il database di {{SITENAME}} è al momento bloccato, e non consente nuove immissioni né modifiche, molto probabilmente per manutenzione server, nel qual caso il database sarà presto di nuovo completamente accessibile.<br />
L'amministratore di sistema che ha imposto il blocco, ha lasciato questa nota:
<p>:$1</p>",
'missingarticle'       => 'Il database non ha trovato il testo di una pagina, che invece avrebbe dovuto trovare, intitolata "$1".<br />
Questo non è un errore del database, ma più probabilmente un problema del software.<br />
Per favore, segnalate l\'accaduto ad un amministratore di sistema, segnalando la URL e l\'ora dell\'incidente.',
'internalerror'        => 'Eròr interno',
'filecopyerror'        => 'Non xè stà possibiłe copiare el file "$1" come "$2".',
'filerenameerror'      => 'Non xè stà possibile rinominare el file "$1" in "$2".',
'filedeleteerror'      => 'Non xè stà possibiłe scancełare el file "$1".',
'filenotfound'         => 'Non xè stà possibile trovare el file "$1".',
'formerror'            => 'Erór: el modulo non xè stà invià correttamente',
'badarticleerror'      => 'Stà operazsión non xè consentia su stà paxèna.',
'cannotdelete'         => "Non xè mia possibiłe scancełare la paxèna o l'imagine richiesta.",
'badtitle'             => 'El titoło non xè mia coreto',
'badtitletext'         => 'La paxèna richiesta non xè disponibiłe, ła podaria essare non vałida, voda, o podaria trattarzse de un erór in un cołegamento interlinguistico o fra diverzse verzsion de {{SITENAME}}.',
'perfdisabled'         => 'Siamo davvero rammaricati, ma questa funzionalità è temporaneamente disabilitata durante le ore di maggiore accesso al database, per ragioni di accessibilità al resto del sito!<br />Torna fra le 02:00 e le 14:00 UTC e riprova.<br /><br />Grazie.',
'perfcached'           => "Sta quà xè na copia ''cache'' e quindi non podaria essere completamente agiornà:",
'wrong_wfQuery_params' => 'Parametri errai par wfQuery()<br />
Funzsion: $1<br />
Query: $2',
'viewsource'           => 'Varda ła fonte',
'protectedinterface'   => "Sta paxèna contegne un elemento che fa parte de l'interfaccia utente del software; è quindi xè proteta par evitare possibiłi abusi.",
'editinginterface'     => "'''Atenzsion:''' El testo de sta paxèna fa parte de l'interfaccia utente del sito. Tute łe modifiche apportae a sta paxèna łe se riflette su i messaj vixuałixai par tuti i utenti.",

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
'externaldberror'            => 'Si gà verificà un erór con el server de autenticazsion esterno, oppure non se dispone de łe autorixazsion necessarie par aggiornar el proprio açesso esterno.',
'loginproblem'               => '<b>Si gà verificà un errore durante el to tentativo de login.</b><br />Riproa, te sarè più fortunà!',
'alreadyloggedin'            => '<strong>Ehi, Utente $1, te ghe xà fato el login, te si xà conesso al nostro server!</strong><br />',
'loginprompt'                => 'Par acedere a {{SITENAME}} xè necessario abiłitare i cookie.',
'logout'                     => 'Và fora',
'userlogout'                 => 'và fora',
'nologin'                    => "Non gheto gnancora n'acezso? $1.",
'nologinlink'                => 'Crealo ora',
'createaccount'              => 'Crea un novo accesso',
'gotaccount'                 => 'Gheto xà un to account? $1.',
'createaccountmail'          => 'via email',
'badretype'                  => 'Le password che te ghè immesso non le coincide, le xè diverse fra lore.',
'userexists'                 => 'Siamo spiacenti.<br />Lo user name che hai scelto è già usato da un altro Utente.<br />Ti preghiamo perciò di voler scegliere uno user name diverso.',
'youremail'                  => 'La to e-mail',
'yourrealname'               => 'El to vero nome*',
'yourlanguage'               => "Linguaggio del l'interfaccia",
'yourvariant'                => 'Variante de linguaggio',
'yournick'                   => 'El to soranome (par łe firme)',
'badsig'                     => 'Erór ne ła firma non standard, verifica i tag HTML.',
'email'                      => 'E-mail',
'loginerror'                 => 'Errore de Login',
'noname'                     => 'Lo user name indicato non è valido, non è possibile creare un account a questo nome.',
'loginsuccesstitle'          => 'Login effettuato con successo!',
'loginsuccess'               => "'''El cołegamento al server de {{SITENAME}} con el nome utente \"\$1\" xè ativo.'''",
'nosuchuser'                 => 'Attenzione<br /><br />a seguito di verifica, non ci risulta alcun Utente con il nome di  "$1".<br /><br />
Controlla per favore il nome digitato, oppure usa il modulo qui sotto per creare un nuovo user account.',
'nosuchusershort'            => 'Non xè registrà nessun utente de nome "$1". Verifica el nome inserio.',
'wrongpassword'              => "La password che te ghe messo non l'è mia giusta.<br /><br />Riprova, per favore.",
'wrongpasswordempty'         => 'La password inseria xè voda. Riproa.',
'mailmypassword'             => 'Spediscimi una nuova password in posta elettronica',
'passwordremindertext'       => 'Qualcuno (probabilmente ti, con indirizso IP $1) gà richiesto l\'invio de na nova password de acezso a {{SITENAME}} ($4).
La password par l\'utente "$2" xè stà impostà a "$3". 
Xè opportuno eseguire n\'acezso quanto prima e cambiare ła password immediatamente.',
'noemail'                    => 'Nessuna casella e-mail risulta registrata per l\'Utente "$1".',
'passwordsent'               => 'Una nuova password è stata inviata alla casella e-mail registrata per l\'Utente "$1".
Per favore, fai subito un log in non appena la ricevi.',
'eauthentsent'               => "Una email de conferma xè stà invià a l'indirizzo che te ghè indicà. Prima che qualunque altra mail te vengna invià, te devi seguire le istruzsioni contegnue ne la mail ricevuta, par confermar che quell'indirizzo xè veramente el tuo.",
'mailerror'                  => "Ghe xè stà un eror nel mandare l'email: $1",
'acct_creation_throttle_hit' => 'Me despiase, te ghe xà creà $1 account. Non te pol crearghine ancora.',
'emailauthenticated'         => "El to indiriszo de e-mail l'è stado autenticado su $1.",
'emailnotauthenticated'      => 'El to indirizso email non xè ancora stà autenticà. Nessuna email la verrà invià tramite le funzsioni che seguono.',
'emailconfirmlink'           => 'Conferma el to indiriszo de e-mail',
'invalidemailaddress'        => "L'indiriszo email no'l pode essere accettà parché el gà un formato non valido. Per favore inserisci un indirizso valido o svoda la caseła.",
'accountcreated'             => 'Acesso creà',
'accountcreatedtext'         => "Xè stà creà un acesso par l'utente $1.",

# Edit page toolbar
'bold_sample'     => 'Grasseto',
'bold_tip'        => 'Grasseto',
'link_sample'     => 'Nome del link',
'link_tip'        => 'Link interno',
'extlink_sample'  => 'http://www.titolochevuoitu.com titolo del link',
'extlink_tip'     => 'Link esterno (ricordate el prefisso http:// )',
'headline_sample' => 'Intestazsión',
'headline_tip'    => 'Sottointestazsión',
'math_sample'     => 'Insert formula here',
'math_tip'        => 'Mathematical formula (LaTeX)',
'image_tip'       => 'Imagine',
'media_sample'    => 'Example.ogg',
'media_tip'       => 'Media file link',

# Edit pages
'minoredit'                => "Sta quà l'è na modifica minore",
'watchthis'                => "Tegni d'ocio sta voçe",
'showpreview'              => 'Mostra anteprima',
'showlivepreview'          => 'Live preview',
'anoneditwarning'          => "'''Atenzsion:''' Acesso non effettuà. Ne ła cronołogia de ła paxèna verà redjstrà l'indirizso IP.",
'missingsummary'           => "'''Reminder:''' You have not provided an edit summary. If you click Save again, your edit will be saved without one.",
'missingcommenttext'       => 'Please enter a comment below.',
'blockedtitle'             => "Stò nome utente corrisponde purtroppo a n'Utente che xè stà disabilità a ła modifica de łe voçi.",
'blockedtext'              => "Sto nome utente o indirizso IP i xè stà blocai da $1.
La motivazsion del bloco xè ła seguente:<br />:''$2''<br />Se te lo desideri, te podi contatare $1 o un altro [[Project:administrators|aministrador]] par discutere de el blocco.

Si noti che ła funzsion 'Scrivi a l'utente' non xè attiva se non xè stà registrà un indiriszo e-mail vałido ne łe proprie [[Special:Preferences|preferenzse]].

Specificare l'indirizso IP coinvolto ($3) in qualsiasi richiesta de chiarimenti.",
'blockedoriginalsource'    => "El codezse sorjente de '''$1''' el vegne mostrà de seguito:",
'blockededitsource'        => "Łe '''modifeghe''' apportae a '''$1''' łe vegne mostrae de seguito:",
'whitelistedittitle'       => 'Occorre esser registrai par poder modificar la paxèna.',
'whitelistedittext'        => 'Par modificare łe paxène ghe xè bisogno $1.',
'whitelistreadtitle'       => 'Bisogna essere registrai par lexere ła paxèna',
'whitelistreadtext'        => 'Xe necessario effettuar el [[Special:Userlogin|login]] par lexere i articoli.',
'whitelistacctitle'        => 'Non te ghè el permesso de creare un account',
'whitelistacctext'         => 'To be allowed to create accounts in this Wiki you have to [[Special:Userlogin|log]] in and have the appropriate permissions.',
'confirmedittitle'         => 'Ghe vole ła conferma e-mail par scrivare',
'confirmedittext'          => "Te devi confermar l'indirizso e-mail prime de editare le paxène. Par piaxèr sistema e valida el to indirizso e--mail usando [[Special:Preferences|user preferences]].",
'loginreqtitle'            => 'Login Required',
'loginreqlink'             => 'login',
'loginreqpagetext'         => 'You must $1 to view other pages.',
'accmailtitle'             => 'Password spedia.',
'accmailtext'              => "La password par '$1' l'è sta spedia a $2.",
'newarticle'               => '(Novo)',
'newarticletext'           => "El cołegamento appena seguio corisponde a na paxèna non ancora esistente.
Se te desideri creare ła paxèna ora, basta comiçciare a scrivere el testo ne ła caseła qui sotto
(fare riferimento a łe [[Project:Aiuto|paxène de aiuto]] par majori informazsion).
Se el cołegamento xè stà seguio par eror, xè suficiente far clic sul botòn '''Indrio''' del proprio browser.",
'anontalkpagetext'         => "----





---- ''Sta quà l'è la paxèna de discussion de un utente anonimo che non'l se ga ancora registrà o che non effettua el login. De conseguenzsa xè necessario identificarlo tramite l'[[Indirizzo IP|indirizzo IP]] numerico. Tale indirizso el pode esser condivixo da diversi utenti. Se te sì un utente anonimo e te pensi che ghe sia sta commenti irrilevanti, te podi [[Special:Userlogin|registrarte o effettuare el login]] par evitare confuxion con altri utenti in futuro.''",
'noarticletext'            => "In sto momento ła paxèna richiesta xè voda. Xè possibiłe [[Special:Search/{{PAGENAME}}|çercar sto titoło]] ne łe altre paxène del sito oppure [{{fullurl:{{FULLPAGENAME}}|action=edit}} modificar ła paxèna 'desso].",
'clearyourcache'           => "'''Nota:''' dopo aver salvà, te devi pulire la cache del to browser par veder i cambiamenti: '''Mozilla:''' clicca su ''reload'' (oppure ''ctrl-r''), '''IE / Opera:''' ''ctrl-f5'', '''Safari:''' ''cmd-r'', '''Konqueror''' ''ctrl-r''.",
'previewnote'              => "Tegni presente che sta qua xè solo n'anteprima, e che la to verzsion NON xè stà ancora salvà!",
'previewconflict'          => "Questa anteprima rappresenta il testo nella casella di edizione di sopra, l'articolo apparirà in questa forma se sceglierai di salvare la pagina ora.",
'session_fail_preview'     => '<strong>Purtroppo non xè stà possibiłe salvare le to modifiche parché i dati de la sezsion i xè andai persi. Per favore, riproa.<br />
Se te rizsevi sto messajo de erór pì olte, proa a scołegarte (struca su "và fora" in alto a destra) e a cołegarte novamente.</strong>',
'importing'                => 'Importing $1',
'editingsection'           => 'Modifica $1 (sezsion)',
'editingcomment'           => 'Modifica $1 (commento)',
'editconflict'             => 'Conflitto de edizsion: $1',
'explainconflict'          => 'Qualcun altro ga salvà na so verszion de ła voçe nel tempo in cui te stavi preparando ła to verszion.<br /> La caselła de modifica de sora contegne el testo de la voçe ne ła so forma attuałe (el testo attualmente online). Le to modifiche łe xè inveçe contegnue ne ła caseła de modifica inferiore. Te dovarè inserire, se te vołi, le to modifiche nel testo esistente, e perciò scrivarle ne ła caseła de sora. <b>Soltanto</b> el testo ne ła caseła de sora sarà salvà se te struchi el botón "Salva".<br />',
'yourtext'                 => 'El to testo',
'storedversion'            => 'Versione in archivio',
'editingold'               => '<strong>Attenzsion: Te stè modificando na verzsion de ła voçe non aggiornà. Se te la salvi così, tuti i cambiamenti apportai dopo sta verzsion i verrà persi.</strong>',
'yourdiff'                 => 'Differense',
'copyrightwarning2'        => 'Ocio che tuti i contributi a {{SITENAME}} i pode essere editai, alterai, o rimossi da altri contributori.
Se non te voli che i to scriti vengna modificà senzsa pietà, alora non inserirli qua.<br />
Sapi che te stè promettendo che te stè inserendo un testo scrito de to pugno, o copià da na fonte de pubblico dominio o similarmente libera (vedi $1 par i dettagli).
<strong>NON INSERIRE OPERE PROTETTE DA COPYRIGHT SENZSA PERMESSO!</strong>',
'longpagewarning'          => "<strong>ATENZSION: Sta paxèna xè longa $1 kilobyte; alcuni browser podaria prexentar dei problemi ne ła modifega de paxèna che se aviçina o supera i 32 KB. Valuta l'opportunità de sudividere ła paxèna in sezsion pì piccołe.</strong>",
'longpageerror'            => '<strong>ERROR: The text you have submitted is $1 kilobytes 
long, which is longer than the maximum of $2 kilobytes. It cannot be saved.</strong>',
'protectedpagewarning'     => '<strong>ATENZSION:  Sta paxèna xè sta protetta e soło i aministradori i pode modificarla. Varda, par essere sicuro ła [[Project:Guida a le paxène protette|Guida a le paxène protette]].</strong>',
'semiprotectedpagewarning' => "'''Nota:''' Sta paxèna xè stà blocà in modo che solo i utenti registrai i poda modefegarla.",

# History pages
'revhistory'          => 'Cronołogia de łe verzsion de sta paxèna.',
'nohistory'           => 'Cronołogia de łe verzsion de sta paxèna non xè reperibiłe.',
'revnotfound'         => 'Verzsion non trovà',
'revnotfoundtext'     => 'La verzsion richiesta de ła paxèna non xè stà trovà.
Verifica ła URL usà par açedere a sta paxèna.',
'loadhist'            => 'Caricamento cronologia de sta paxèna',
'currentrev'          => 'Verzsion atuałe',
'revisionasof'        => 'Revixion $1',
'previousrevision'    => '← Verzsion manco reçente',
'nextrevision'        => 'Verzsion pì reçente →',
'currentrevisionlink' => 'Varda ła verzsion atuałe',
'histlegend'          => 'Legenda: (corr) = differenzse con la versión corrente,
(prec) = differenzse con la versión precedente, m = modifica minore',
'deletedrev'          => '[scancełà]',

# Revision feed
'history-feed-title'       => 'Cronołogia',
'history-feed-description' => 'Cronołogia de ła paxèna su sto sito',
'history-feed-empty'       => 'La paxèna richiesta non esiste; podaria essere stà scancełà dal sito o rinominà. Verifica con la [[Special:Search|paxèna de ricerca]] se ghe xè nove paxène.',

# Revision deletion
'rev-delundel'           => 'mostra/scondi',
'revisiondelete'         => 'Scanceła o ripristina verzsion',
'revdelete-selected'     => 'Verzsion selezsionae de [[:$1]]:',
'revdelete-hide-text'    => 'Scondi el testo de ła verzsion',
'revdelete-hide-comment' => "Scondi l'oggetto de ła modifega",
'revdelete-hide-user'    => "Scondi el nome o l'indirizso IP dell'autore",
'revdelete-submit'       => 'Applica a ła revixion selezsionà',

# Diffs
'difference'                => '(Diferenzse fra łe verzsion)',
'loadingrev'                => 'caricamento revixion par differenzse',
'editcurrent'               => 'Modifica la verzsion corente de stà paxèna',
'selectnewerversionfordiff' => 'Selezsiona na verzsion pì reçente par el confronto',
'selectolderversionfordiff' => 'Selezsiona na verzsion manco reçente par el confronto',
'compareselectedversions'   => 'Confronta łe verzsión selezsionà',

# Search results
'searchresults'     => 'Risultato della ricerca "$1"',
'searchresulttext'  => 'Per maggiori informazioni sulla ricerca interna di {{SITENAME}}, vedi [[Project:Ricerca|Ricerca in {{SITENAME}}]].',
'badquery'          => 'Richiesta non xè posta bén',
'badquerytext'      => 'La to richiesta non ła pode esser processà. Questo podria dipendare da l\'aver zsercà na paroła in manco de tre carateri. Oppure te podarezsi aver scritto małe ła richiesta, par esempio "pesce and and azzurro". Per favore, riproa.',
'matchtotals'       => 'La ricerca per l\'esprezsion "$1" gà trovà<br />$2 riscontri nei titołi de le voci e<br />$3 riscontri ne i testi de le voci.',
'noexactmatch'      => "'''La paxèna \"\$1\" non ła esiste.''' Xè possibiłe [[:\$1|crearla ora]].",
'titlematches'      => 'Nei titołi de łe voçi',
'notitlematches'    => 'Voce richiesta non trovata in titoli di articolo',
'textmatches'       => 'Nel testo degli articoli',
'notextmatches'     => 'Voce richiesta non trovata in testi di articolo',
'viewprevnext'      => 'Varda ($1) ($2) ($3).',
'showingresults'    => "Qui de seguito '''$1''' risultati, partendo dal numero #'''$2'''.",
'showingresultsnum' => "Qui de seguito '''$3''' risultati, partendo dal numero #'''$2'''.",
'nonefound'         => '<strong>Nota</strong>: la ricerca di parole troppo comuni, come "avere" o "essere", che non sono indicizzate, può causare un esito negativo, così come indicare più di un termine da ricercare (solo le pagine che contengano tutti i termini ricercati verrebbero infatti visualizzate fra i risultati).',
'powersearch'       => 'Zserca',
'powersearchtext'   => '
Cerca fra i campi :<br />
$1<br />
$2 Elenca i redirects &nbsp; cerca per $3 $9',
'blanknamespace'    => '(Prinzsipałe)',

# Preferences page
'preferences'              => 'Preferenzse',
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
'math_failure'             => 'Failed to parse',
'math_unknown_error'       => 'unknown error',
'math_unknown_function'    => 'unknown function',
'math_lexing_error'        => 'lexing error',
'math_syntax_error'        => 'syntax error',
'math_image_error'         => 'Converzsion in PNG fałía',
'math_bad_tmpdir'          => "Can't write to or create math temp directory",
'math_bad_output'          => "Can't write to or create math output directory",
'math_notexvc'             => 'Missing texvc executable; please see math/README to configure.',
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
'resultsperpage'           => 'Numero de risultati par paxèna:',
'contextlines'             => 'Righe de testo par ciascun risultato',
'contextchars'             => 'Caratteri par linea:',
'recentchangescount'       => 'Numero titołi in "modifeghe reçenti"',
'savedprefs'               => 'Le to preferenzse łe xè stà salvae.',
'timezonetext'             => 'Immetti il numero di ore di differenza fra la tua ora locale e la ora del server (UTC).',
'localtime'                => 'Ora Locale',
'guesstimezone'            => "Usa l'ora del to browser",
'allowemail'               => 'Consenti la ricezsion de e-mail da altri utenti (1)',
'defaultns'                => 'Szerca in sti namespace se non diversamente specificà:',
'default'                  => 'default',
'files'                    => 'Imagini',

# User rights
'userrights-lookup-user'   => 'Gestion de i gruppi utente',
'userrights-user-editname' => 'Inserire el nome utente:',
'userrights-groupshelp'    => "Selezsionar i gruppi ai quałi se vołe assoçiare o rimovere l'utente. L'appartenenzsa ai gruppi non selezsionai non verrà modifegà. Par desełezsionare un gruppo, premere Ctrl + el tasto sinistro del mouse.",

# Groups
'group-sysop' => 'Aministradori',

'group-sysop-member' => 'Aministrador',

# Recent changes
'recentchanges'     => 'Ultime modifeghe',
'recentchangestext' => 'Sta paxèna presenta łe ultime modifeghe aportae ai contenuti de el sito.',
'rcnote'            => 'De seguito xè ełencae łe <strong>$1</strong> modifiche pì reçenti aportae ne i ultimi <strong>$2</strong> jorni, agiornae a $3.',
'rcnotefrom'        => ' Qui di seguito sono elencate le modifiche da <b>$2</b> (fino a <b>$1</b>).',
'rclistfrom'        => 'Mostra łe modifeghe aportae a partire da $1',
'rcshowhideminor'   => '$1 le modifeghe minori',
'rcshowhideliu'     => '$1 gli utenti registrai',
'rcshowhidepatr'    => '$1 łe modifeghe controłae',
'rcshowhidemine'    => '$1 łe me modifeghe',
'rclinks'           => 'Mostra le ultime $1 modifiche nei ultimi $2 giorni<br />$3',
'hide'              => 'scondi',
'show'              => 'Mostra',

# Recent changes linked
'recentchangeslinked' => 'Modifeghe corełae',

# Upload
'reupload'                    => 'Ri-upload',
'reuploaddesc'                => 'Torna al modulo per lo upload.',
'uploadnologin'               => 'Te devi fare el login par exeguire sta operazsion.',
'uploadnologintext'           => 'Te ghè da exeguire [[Special:Userlogin|el login]]
par fare el upload de files.',
'uploaderror'                 => 'Errore di Upload',
'uploadtext'                  => "Usa el moduło sotostante par caricare i novi file. Par vixualixare o riçercare i file xà caricai, consulta el [[Special:Imagelist|log de i file caricai]]. Caricamenti e scancełazsioni de file i xè registrai ne el [[Special:Log/upload|log de i upload]].

Par inserire un'imagine in na paxèna, fare un cołegamento de sto tipo:
'''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:file.jpg]]</nowiki>''' o
'''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:file.png|testo alternativo]]</nowiki>'''; usare inveçe
'''<nowiki>[[</nowiki>{{ns:Media}}<nowiki>:file.ogg]]</nowiki>''' par cołegare diretamente gli altri tipi de file.",
'uploadlog'                   => 'File caricai',
'uploadlogpage'               => 'Log dei file caricai',
'uploadlogpagetext'           => 'Qui di seguito la lista degli ultimi files caricati sul server di {{SITENAME}}.
Tutti i tempi indicati sono calcolati sul fuso orario del server (UTC).',
'filedesc'                    => 'Oggetto',
'fileuploadsummary'           => 'Sommario:',
'filestatus'                  => 'Stato del copyright',
'filesource'                  => 'Sorgente',
'uploadedfiles'               => 'Files Caricati in {{SITENAME}}',
'ignorewarning'               => 'Ignore warning and save file anyway.',
'ignorewarnings'              => 'Ignora i messaggi de avvertimento del sistema',
'illegalfilename'             => 'Il nomefile "$1" contiene caratteri che xè permessi nei titoli delle pagine. Per favore rinomina el file e prova a ricaricarlo.',
'badfilename'                 => 'El nome de el file imagine xè stà convertio in "$1".',
'largefileserver'             => 'This file is bigger than the server is configured to allow.',
'emptyfile'                   => 'El file che te ghè caricà xè apparentemente vuoto. Podaria essere par un errore nel nome del file. Per favore controlla se te vol veramente caricar stò file.',
'fileexists'                  => 'Un file con sto nome el esiste xà, per favore controła $1 se non te sì sicuro de volerlo sovrascrivere.',
'fileexists-forbidden'        => 'Un file con sto nome el esiste xà; per favore torna indrio e cambia el nome che te voi dare al file. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Un file con sto nome esiste xè ne l'archivio de risorse multimediałi condivixe. Per favore torna indrio e cambia el nome che te voi dare al file. [[Image:$1|thumb|center|$1]]",
'uploadwarning'               => 'Avixo de Upload',
'uploadedimage'               => 'gà caricà "[[$1]]"',
'uploaddisabledtext'          => 'El caricamento dei file non xè attivo su sto sito.',
'uploadscripted'              => 'Sto file contegne codexe HTML o de script, che podaria essere interpretà eroneamente da un browser web.',
'uploadvirus'                 => 'Sto file contegne un virus! Detagli: $1',
'destfilename'                => 'Destination filename',
'filewasdeleted'              => 'Un file con sto nome xè stato xà caricà e scancełà in passato. Verifica $1 prima de caricarlo de novo.',

'license'   => 'Licensing',
'nolicense' => 'Nessuna liçenzsa indicà',

# Image list
'imagelist'                 => 'Imagini',
'imagelisttext'             => 'Qui de seguito na łista de $1 imagini, ordinae par $2.',
'imagelistforuser'          => 'This shows only images uploaded by $1.',
'getimagelist'              => 'rizserca ne la lista de le immagini',
'ilsubmit'                  => 'Zserca',
'showlast'                  => 'Lista di $1, fra le ultime immagini, ordinate per $2.',
'bysize'                    => 'peso',
'imglegend'                 => 'Legenda: (desc) = mostra/modifica descrizsion imagine.',
'imghistory'                => 'Cronołogia de sta imagine',
'imghistlegend'             => "Legenda: (corr) = imagine corente, (canc) = scanceła sta verzsion vecia, (ripr) = ripristina sta  verzsion vecia come verzsion atuałe.
<br />''Clicca su una data par vardare l'imagine corrispondente.''",
'imagelinks'                => 'Collegamenti a le immagini',
'linkstoimage'              => 'Le paxène seguenti riciama sta imagine:',
'nolinkstoimage'            => 'Nessuna paxèna linka sta imagine.',
'noimage'                   => 'Un file con sto nome non esiste; $1?',
'noimage-linktext'          => 'carica ora',
'uploadnewversion-linktext' => 'Carica na nova verzsion de sto file',

# MIME search
'mimesearch' => 'MIME search',
'mimetype'   => 'MIME type:',

# Unwatched pages
'unwatchedpages' => 'Paxène non osservae',

# List redirects
'listredirects' => 'List redirects',

# Unused templates
'unusedtemplateswlh' => 'altri cołegamenti',

# Statistics
'sitestats'     => 'Statistiche del sito',
'userstats'     => 'Statistiche dei utenti',
'sitestatstext' => "El database contegne complessivamente '''{{FORMATNUM|\$1}}''' paxène.
Sta çifra comprende anca łe paxène de discussion, quełe de servizsio de {{SITENAME}}, łe voçi pì esigue (\"stub\"), i redirect e altre paxène che probabilmente non łe va considerae tra i contenuti de el sito. Escludendo łe paxène sora descritte, ghe ne xè '''{{FORMATNUM|\$2}}''' de contenuti veri e propri.

Xè stà inoltre caricà '''{{FORMATNUM|\$8}}''' file.

Dall'instałazsione del sito sino a sto momento ghe xè stà visitae '''{{FORMATNUM|\$3}}''' paxène ed eseguie '''{{FORMATNUM|\$4}}''' modifeghe, pari a na media de '''{{FORMATNUM|\$5}}''' modifeghe par paxèna e '''{{FORMATNUM|\$6}}''' richieste de lettura par ciascuna modifica.

La [http://meta.wikimedia.org/wiki/Help:Job_queue coda] contegne '''{{FORMATNUM|\$7}}''' proçessi.",
'userstatstext' => "In sto momento ghe xè '''$1''' utenti registrai, dei quałi '''$2''' (pari al '''$4%''') xè aministradori (varda $3).",

'disambiguations'     => 'Paxène de disambiguazsion',
'disambiguationspage' => 'Template:Disambigua',

'doubleredirectstext' => '<b>Atenzsion:</b> Stà lista la pode talvolta contegnere dei risultati non corretti. Podaria magari accadere perchè ghe fusse del testo aggiuntivo o dei link dopo el tag #REDIRECT.<br /> Ogni riga contegne i link al primo ed al secondo redirect, oltre a la prima riga de testo del secondo redirect che de solito contegne el "reale" articolo de destinazsion, quello al quale anca el primo redirect dovaria puntare.',

'brokenredirects'     => 'Redirect erái',
'brokenredirectstext' => 'I seguenti redirect i punta a articoli non ancora creai.',

# Miscellaneous special pages
'nbytes'                  => '$1 bytes',
'ncategories'             => '$1 categories',
'lonelypages'             => 'Paxène solitarie',
'uncategorizedpages'      => 'Paxène prive de categorie',
'uncategorizedcategories' => 'Categorie prive de categorie',
'uncategorizedimages'     => 'Imagini prive de categorie',
'unusedcategories'        => 'Categorie non utilixae',
'unusedimages'            => 'Imagini non utilixae',
'popularpages'            => 'Paxène pì viste',
'wantedpages'             => 'Paxène pì richieste',
'mostlinked'              => 'Paxène piassè linkae',
'mostlinkedcategories'    => 'Categorie piazsé riciamae',
'mostcategories'          => 'Arthicołi con piazsé categorie',
'mostimages'              => 'Most linked to images',
'mostrevisions'           => 'Voçi con piazsé revixión',
'allpages'                => 'Tute łe paxène',
'randompage'              => 'Paxèna a caso',
'shortpages'              => 'Paxène corte',
'longpages'               => 'Paxène longhe',
'deadendpages'            => 'Paxène senzsa uscita',
'listusers'               => 'Elenco dei utenti',
'specialpages'            => 'Paxène speciałi',
'spheading'               => 'Paxène speciałi par tuti i utenti',
'restrictedpheading'      => 'Paxène speciałi par i aministradori',
'rclsub'                  => '(a łe paxène linkae da "$1")',
'newpages'                => 'Paxène nove',
'ancientpages'            => 'Paxène pì vece',
'intl'                    => 'Link a altri linguaggi',
'unusedimagestext'        => '<p>Nota che altri siti web, come la {{SITENAME}} internazionale, potrebbero aver messo un link ad una immagine per mezzo di una URL diretta, perciò le immagini potrebbero essere listate qui, essendo inutilizzate in questa versione di {{SITENAME}}, anche essendo magari in uso altrove.',
'unusedcategoriestext'    => 'Le paxène de łe categorie indicae de seguito łe xè stà creae ma non contegne nessuna paxèna né sotocategoria.',

'categoriespagetext' => 'In {{SITENAME}} ghèmo ste categorie',
'data'               => 'Data',
'version'            => 'Verzsion',

# Special:Log
'speciallogtitlelabel' => 'Titolo',
'log'                  => 'Logs',
'alllogstext'          => 'Vixualixazsion unificà de i log de upload, scancełazsión, protezsión, blocking e de aministrazsión. Te podi restringere i criteri de rizserca selezsionando el tipo de log, username, o la paxèna interessà.',
'logempty'             => 'No matching items in log.',

# Special:Allpages
'nextpage'          => 'Paxèna dopo ($1)',
'allpagesfrom'      => 'Mostra łe paxène cominzsiando da:',
'allarticles'       => 'Tuti le voçi',
'allinnamespace'    => 'Tute łe paxène ($1 namespace)',
'allnotinnamespace' => 'Tute łe paxène (no ne el namespace $1)',
'allpagesprev'      => 'Preçedenti',
'allpagesnext'      => 'Prozsime',
'allpagessubmit'    => 'Và',
'allpagesprefix'    => 'Mostra łe voçi che inizsia con:',

# E-mail user
'mailnologin'     => 'No send address',
'mailnologintext' => 'Par inviare messaj e-mail ad altri utenti xè neçessario [[Special:Userlogin|açedere al sito]] e aver registrà un indirizso vałido ne łe proprie [[Special:Preferences|preferenzse]].',
'emailpage'       => "Scrivi una e-mail all'utente",
'emailpagetext'   => 'Se sto Utente gà registrà na casella e-mail valida, el modulo qui sotto te consentirà di scriverghe un solo messaggio. La e-mail che te ghè indicà ne le to preferenzse la apparirà nel campo "Da" de la mail, così che el destinatario possa, solo se el lo desidera però, risponderte.',
'defemailsubject' => '{{SITENAME}} e-mail',
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
'watchlistcount'       => "'''La lista de i osservati speciałi contegne {{FORMATNUM|$1}} elementi (comprexe łe paxène de discussion).'''",
'watchnologin'         => 'No ghe xe el login',
'watchnologintext'     => 'Devi prima fare il [[Special:Userlogin|login]]
per modificare la tua lista di osservati speciali.',
'addedwatch'           => 'Xontà ai tòi Osservati Speciali',
'addedwatchtext'       => "La paxèna  \"\$1\" l'è stà xontà a la tua [[Special:Watchlist|lista de osservati speciali]].
Le future modifiche a stà pagina e a la relativa pagina de discussion le sarà elencae qui, e la paxèna apparirà in '''grasseto''' ne la paxèna de le [[Special:Recentchanges|modifiche recenti]] par essere pì facile da tener d'ocio.

Se pì avanti te vorè tojere stò articolo da la to lista de Osservati Speciali, clicca \"Non seguire\" nella barra dei menu.",
'removedwatch'         => 'Rimosso dalla lista degli Osservati Speciali',
'removedwatchtext'     => 'La paxèna  "$1" xè stà rimossa da ła łista de i toi Osservati Speciałi.',
'watchthispage'        => 'Segui sta voçe',
'notanarticle'         => 'Non xè na voçe',
'watchnochange'        => "Nezsun de i to ojeti osservai l'è stà edità nel periodo mostrà.",
'watchlist-details'    => '$1 ojeti osservai no i contegne discussioni.',
'wlheader-enotif'      => '* Xe attivà la notifica via e-mail.',
'wlheader-showupdated' => "* Le paxène che xe stà modificà da la to ultima visita le xe evidensià en '''grasseto'''",
'watchmethod-recent'   => 'controło de łe modifeghe reçenti par i osservati speciałi',
'watchmethod-list'     => 'controło de i osservati speciałi par modifeghe reçenti',
'watchlistcontains'    => 'La lista de i osservati speciałi contiene $1 paxène.',
'iteminvalidname'      => "Problemi con la voçe '$1', nome non vałido...",
'wlnote'               => 'Sotto te trovi le ultime $1 modifiche, nelle ultime <b>$2</b> ore.',
'wlsaved'              => "Questa l'è na version salvà de la to lista de articoli sotto osservasión.",

'enotif_reset'       => 'Segna tute łe paxène visitae',
'enotif_newpagetext' => 'Sta quà xe na nova paxèna.',
'changed'            => 'cambià',
'created'            => 'Creà',
'enotif_subject'     => '{{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED by $PAGEEDITOR',
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
'deletepage'                  => 'Scanceła paxèna',
'excontent'                   => "el contenuto xera: '$1'",
'exbeforeblank'               => "El contenuto prima de lo svodamento xera: '$1'",
'exblank'                     => "ła paxèna l'era voda",
'confirmdelete'               => 'Conferma scancełazsión',
'deletesub'                   => '(Scancełazsion de "$1")',
'historywarning'              => 'Atenzsion: La paxèna che te stè par scancełar gà na cronołogia:',
'confirmdeletetext'           => "Te stè par scancełar permanentemente da el database na paxèna o na imagine, insieme a tuta la so cronołogia.
Par cortesia, conferma che l'è to intenzsion proçedere a tałe scancełazsion, conferma che te ghè piena consapevołezsa de łe conseguenzse de la to azsion, e conferma che la to azsion l'è pienamente ottemperante a łe regołe stabilíe ne ła
[[Project:Policy]].",
'actioncomplete'              => 'Azsión completà',
'deletedtext'                 => 'La paxèna "$1" l\'è stà scancełà. Varda $2 par un ełenco de łe paxène scancełae de reçente.',
'deletedarticle'              => 'Scancełà "$1"',
'dellogpage'                  => 'Scancełazsión',
'dellogpagetext'              => 'Qui de seguito, un ełenco de łe paxène scancełae de reçente.',
'deletionlog'                 => 'Log de scancełasión',
'reverted'                    => 'Ripristinata versione precedente',
'deletecomment'               => 'Motivazsion de ła scancełazsion',
'imagereverted'               => 'Version precedente correttamente ripristinata.',
'rollback'                    => 'Usa una revisione precedente',
'rollbackfailed'              => 'Rollback non riuzsio',
'cantrollback'                => "No xè mia possibiłe tornar a na verzsión precedente: l'ultima modifica xè stà apportà da l'unico utente che gà laorà a stò arthicoło.",
'alreadyrolled'               => "Non xè mia possibile effettuare el rollback de [[:$1]] da [[User:$2|$2]] ([[User talk:$2|discussion]]); qualcun altro gà xà modificà o effetuà el rollback de sta voçe. L'ultima modefega l'è stà fata da [[User:$3|$3]] ([[User talk:$3|discussion]]).",
'editcomment'                 => 'El commento a la modifica xera: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Anułate łe modifeghe de [[Special:Contributions/$2|$2]] ([[User talk:$2|discussion]]), riportà a ła verzsion preçedente de [[User:$1|$1]]',
'protectlogpage'              => 'Log de protezsión',
'protectedarticle'            => 'proteto "[[$1]]"',
'unprotectedarticle'          => 'unprotected "[[$1]]"',
'protectsub'                  => '(Protezsion de "$1")',
'confirmprotect'              => 'Conferma la protezsion',
'protectcomment'              => 'Motivo de ła protezsion',
'unprotectsub'                => '(Sbloco de "$1")',
'protect-unchain'             => 'Scołega i permessi de spostamento',
'protect-level-autoconfirmed' => 'Solo utenti registrai',
'protect-level-sysop'         => 'Solo aministradori',

# Restrictions (nouns)
'restriction-edit' => 'Modifega',

# Undelete
'undelete'                 => 'Recupera na paxèna scancełà',
'undeletepage'             => 'Varda e recupera paxène scancełae',
'viewdeletedpage'          => 'Varda łe paxène scancełae',
'undeletepagetext'         => "Le pagine qui di seguito indicate sono state cancellate, ma sono ancora in archivio e pertanto possono essere recuperate. L'archivio viene svuotato periodicamente.",
'undeleteextrahelp'        => "Par recuperare l'intera paxèna, lazsia tute łe casełe desełezsionae e fa clic su '''''Ripristina'''''. Par effettuare un ripristino sełetivo, selezsiona łe casełe corrispondenti a łe revixion da ripristinare e fa clic su '''''Ripristina'''''. Faxendo clic su '''''Reset''''' łe verà deselezsionae tute łe casełe e svodà lo spazsio par el commento.",
'undeletehistory'          => 'Recuperando sta paxèna, tute łe so revixion verà inserie de novo ne ła rełativa cronołogia. Se dopo ła scancełazsion xè stà creà na nova paxèna con lo stesso titoło, łe revixion recuperae sarà inserie ne ła cronołogia e ła verzsion attualmente online de ła paxèna non verrà modifegà.',
'undeletehistorynoadmin'   => 'La paxèna xè stà scanceła. El motivo de ła scancełazsion xè indicà de seguito, assieme ai dati de i utenti che i gavea modifegà ła paxèna prima de ła scancełazsion. El testo contegnù ne łe revixion scancełae xè disponibiłe solo a i aministradori.',
'undeletebtn'              => 'RIPRISTINA!',
'undeletedarticle'         => 'Recuperà "$1"',
'undeletedrevisions'       => '$1 revixion recuperae',
'undeletedrevisions-files' => '$1 revixion e $2 file recuperai',
'undeletedfiles'           => '$1 file recuperai',
'cannotundelete'           => "El recupero no'l xè riusìo: qualcun altro podariae avere xà recuperà ła paxèna.",
'undeletedpage'            => "<big>'''$1 xè stà recuperà'''</big>

Consultare el [[Special:Log/delete|log delle scancełazsioni]] par vardare łe scancełazsion e i recuperi pì reçenti.",

# Namespace form on various pages
'invert' => 'inverti ła selezsión',

# Contributions
'mycontris'  => 'i me contributi',
'nocontribs' => 'Nessuna modifica trovata conformemente a questi criteri.',
'ucnote'     => 'Qui sotto troverai le ultime <b>$1</b> modifiche effettuate da questo Utente negli ultimi <b>$2</b> giorni.',
'uclinks'    => 'Vedi le ultime $1 modifiche; vedi gli ultimi $2 giorni.',
'uctop'      => ' (ultima par ła paxèna)',

'sp-contributions-older' => '$1 manco reçenti',

'sp-newimages-showfrom' => 'Mostra łe imagini pì reçenti a partire da $1',

# What links here
'whatlinkshere' => 'Paxène che le punta qua',
'notargettext'  => "Non hai specificato una pagina o un Utente in relazione al quale eseguire l'operazione richiesta.",
'linklistsub'   => '(Lista di link)',
'linkshere'     => 'Le seguenti paxène le contegne link che punta qua:',
'nolinkshere'   => 'Nessuna paxèna contegne links che punta a sta quà.',
'istemplate'    => 'inclusion',

# Block/unblock
'blockip'            => 'Blocca indirizso IP',
'blockiptext'        => "Usare el moduło sottostante par bloccare l'accesso in scrittura ad uno speçifico utente o indirizso IP. El bloco dev'essere operà par prevegnere ati de vandalismo e in stretta osservanzsa de ła [[Project:Policy|policy de {{SITENAME}}]]. Speçificare in dettałio el motivo de el bloco nel campo seguente (ad es. indicando i titołi de łe paxène oggeto de vandalismo).",
'ipaddress'          => 'Indiriszo IP (IP Address)',
'ipadressorusername' => 'Indiriszo IP o nome utente',
'ipbexpiry'          => 'Scadenzsa',
'ipbreason'          => 'Motivazsión',
'ipbsubmit'          => 'Blocca sto indirizso IP',
'ipbother'           => 'Other time',
'ipboptions'         => '2 hours:2 hours,1 day:1 day,3 days:3 days,1 week:1 week,2 weeks:2 weeks,1 month:1 month,3 months:3 months,6 months:6 months,1 year:1 year,infinite:infinite',
'ipbotheroption'     => 'other',
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
'autoblocker'        => 'Bloccà automaticamente parché l\'indirisso IP xè condiviso con "$1". Motivo "$2".',
'blocklogpage'       => 'Block_log',
'blocklogentry'      => 'bloccà "$1" par un periodo di $2',
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
'lockdbsuccesstext'   => 'Il database di {{SITENAME}} è stato bloccato.
<br />Ricordati di rimuovere il blocco non appena avrai terminatoi le tue manutenzioni.',
'unlockdbsuccesstext' => ' Il database di {{SITENAME}} è stato sbloccato.',

# Move page
'movepage'                => 'Spostamento de paxèna',
'movepagetext'            => "Con el modulo sottostante te podi rinominar na paxèna, spostando anca tuta la so cronołogia al novo nome. El vecio titoło diverrà automaticamente un redirect che punta al novo titoło. I link a la vecia paxèna non sarà agiornai (e i punterà quindi al redirect); azsertai de [[Special:Manutenzsion|controłare con cura]] che non se crea doppi redirects o redirects interroti. Resta ne la to responsabilità de accertarte che i link i continua a puntare verso dove i deve dirigerse. Nota ben: la paxèna '''non''' sarà spostà se ve fusse xà una voçe con el novo nome, a meno che non sia na paxèna voda o un redirect, e sempre che non abbia cronologia. Questo significa che, se te commetti un error, te podi novamente rinominar na paxèna col vecio titoło, ma non te podi sovrascriver na paxèna xà esistente. <b>ATTENZSION!</b> Sto cambiamento drastico podaria crear inattesi contrattempi, specialmente se se tratta de na paxèna molto visità. Accertai de aver ben valutà le conseguenzse de lo spostamento, prima de procedere. Nel dubbio, contatta un Aministrador.",
'movepagetalktext'        => "La corrispondente paxèna de discussion sarà spostà automaticamente insieme a ła paxèna prinçipałe, '''tranne che nei seguenti casi:'''
* Lo spostamento de ła paxèna xè tra namespace diversi
* In corrispondenzsa del novo titoło esiste xà na paxèna de discussion (non voda)
* La caseła chi soto xè stà desełezsionà.",
'movearticle'             => 'Rinomina voçe',
'movenologin'             => 'Non te ghè efetuà el login',
'movenologintext'         => 'Te ghè da esser un Utente registrà ed aver effettuà el [[Special:Userlogin|login]] par poder spostare na paxèna.',
'newtitle'                => 'Al novo titoło de',
'movepagebtn'             => 'Sposta sta paxèna',
'articleexists'           => 'Na paxèna con sto nome la existe xà, oppure el nome che te ghè zselto non xè vałido.<br /> Zsegli, per cortexia, un titoło diverso par ła voçe.',
'talkexists'              => "La pagina è stata spostata correttamente, ma non si è potuto spostare la pagina di discussione perché ne esiste già un'altra con il nuovo titolo. Per favore, modifica manualmente i contenuti delle due pagine discussione, così da mantenerle entrambe per non perdere potenzialmente interessanti riflessioni.",
'movedto'                 => 'spostà a',
'movetalk'                => 'Sposta anche la corrispondente pagina "discussione", se possibile.',
'talkpagemoved'           => 'Anca ła corrispondente paxèna de discussion xè stà spostatà.',
'talkpagenotmoved'        => 'La corrispondente paxèna de discussion <strong>non xè stà spostà</strong>.',
'1movedto2'               => '[[$1]] spostà a [[$2]]',
'1movedto2_redir'         => "$1 spostà a $2 co'n redirect",
'movelogpage'             => 'Move log',
'movelogpagetext'         => 'Lista de paxène spostae.',
'movereason'              => 'Reason',
'delete_and_move'         => 'Scanceła e sposta',
'delete_and_move_text'    => '==Scancełazsion richiesta==

La voçe specificà come destinazsion "[[$1]]" l\'esiste già. Vóto scancełarlo par proseguire con ło spostamento?',
'delete_and_move_confirm' => 'Si! Scancèła ła paxèna',
'delete_and_move_reason'  => 'Scancełà par rendere possibile lo spostamento',
'selfmove'                => 'El novo titoło xè uguałe al vecio; impossibiłe spostare ła paxèna su se stessa.',
'immobile_namespace'      => "El titolo de destinazsion l'è de tipo speciale; impossibile spostar paxène in quel namespace.",

# Export
'export'          => 'Esporta paxène',
'exporttext'      => 'Te podi esportar el testo e modificar ła cronołogia de na speçifica paxèna o de un gruppo de paxène raggruppae in XML; questo el pode in seguito essere importà in un altro wiki che utilixa el software MediaWiki, trasformà, o tegnù semplicemente par el to personałe divertimento.',
'exportcuronly'   => "Includi soło ła verzion attuałe, non l'intera cronołogia",
'exportnohistory' => "----
'''Ocio!''' par motivi de potenzsa xè stà disabiłità l'esportazsion de tuta ła storia de łe paxène fata co 'sto modeło",

# Namespace 8 related
'allmessages'               => 'Tuti i messaj de sistema',
'allmessagesdefault'        => 'Testo de default',
'allmessagescurrent'        => "Testo come che el xe 'deso",
'allmessagestext'           => "Sta quà l'è na lista de tutti i messaggi disponibili nel namespace MediaWiki:",
'allmessagesnotsupportedUI' => "El linguaggio che te ghè scelto ('''$1''') non l'è mia supportà da '''Special:Allmessages''' in sto sito.",
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' no'l xè supportà parché '''\$wgUseDatabaseMessages''' no'l xè ativo.",
'allmessagesfilter'         => 'Filto su i messaj:',
'allmessagesmodified'       => 'Mostra soło quełi modefegà.',

# Thumbnails
'missingimage' => '<b>Missing image</b><br /><i>$1</i>',

# Special:Import
'import'                   => 'Importa paxène',
'importinterwiki'          => 'Transwiki import',
'import-interwiki-text'    => 'Selezsionare un projeto wiki e el titoło de ła paxèna da importare.
Le date de publicazsion e i nomi de i autori de łe varie verzsion i sarà conservai.
Tute łe operazsion de importazsion trans-wiki łe xè registrae nel [[Special:Log/import|log de importazsion]].',
'import-interwiki-history' => "Copia l'intera cronołogia de sta paxèna",
'importtext'               => "Per favore, esporta el file da la wiki de origine usando l'utility Special:Export, salvalo su el to disco e fa l'upload qua.",
'importstart'              => 'Importazsion de łe paxène in corso...',
'import-revision-count'    => '{{PLURAL:$1|una revixion importà|$1 revixion importae}}',
'importnopages'            => 'Nessuna paxèna da importar.',
'importfailed'             => 'Importazsion falía: $1',
'importunknownsource'      => "Tipo de origine sconozsuo par l'importazsion",
'importcantopen'           => 'Impozsibiłe verdere el file de importazsion',
'importbadinterwiki'       => 'Cołegamento inter-wiki errà',
'importnotext'             => 'Testo vodo o mancante',
'importsuccess'            => 'Importazsion avvegnù con successo!',
'importhistoryconflict'    => 'Esiste revision de la cronołogia in conflitto (sta paxèna podaria essere xà sta importà)',
'importnosources'          => "Non xè stà definia na fonte par l'importazsion transwiki; l'importazsion direta de ła cronołogia non xè ativa.",
'importnofile'             => "Non xè stà caricà nessun file par l'importazsion,",
'importuploaderror'        => "El caricamento de le imamgini xè falío, forse parché el file l'è pì grosso del quel che xè permesso.",

# Import log
'importlogpage'                    => 'Importazsion',
'importlogpagetext'                => "Registro de łe importazsion d'uffiçio de paxène provenienti da altre wiki, complete de cronołogia.",
'import-logentry-upload'           => 'gà importà [[$1]] tramite upload',
'import-logentry-upload-detail'    => '{{PLURAL:$1|una revixion importà|$1 revixion importae}}',
'import-logentry-interwiki'        => 'gà trasferio da altra wiki ła paxèna $1',
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
'tooltip-search'                  => 'Serca sta wiki',
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
'tooltip-feed-rss'                => 'RSS feed for this page',
'tooltip-feed-atom'               => 'Atom feed for this page',
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
'tooltip-diff'                    => 'Varda łe modifeghe apportae al testo.',
'tooltip-compareselectedversions' => 'Varda łe diferenzse tra łe do verzsion selezsionà de sta paxèna.',
'tooltip-recreate'                => 'Ricrea ła paxèna anca se xè stà scancełà',

# Scripts
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Metadata
'notacceptable' => 'El server wiki non xè in grado di fornire i dati in un formato łeggibiłe dal client utilixà.',

# Attribution
'anonymous'        => 'Utente(/i) anonimo(/i) de {{SITENAME}}',
'lastmodifiedatby' => "Sta paxèna xè stà modificà l'ultima volta el $2, $1 da $3.", # $1 date, $2 time, $3 user
'creditspage'      => 'Crediti de ła paxèna',

# Spam protection
'subcategorycount'       => 'Sta categoria contegne {{PLURAL:$1|na sotocategoria| {{FORMATNUM|$1}} sotocategorie}}.',
'categoryarticlecount'   => 'Ghè $1 voçi in sta categoria.',
'spam_blanking'          => 'Paxèna svodà, tute łe verzsion contegneva cołegamenti a $1',

# Info page
'infosubtitle'   => 'Informazsion par la paxèna',
'numtalkedits'   => 'Numero de modifeghe (paxèna de discussion): $1',
'numtalkauthors' => 'Numero de autori distinti (paxèna de discussion): $1',

# Math options
'mw_math_png'    => 'Always render PNG',
'mw_math_simple' => 'HTML if very simple or else PNG',
'mw_math_html'   => 'HTML if possible or else PNG',
'mw_math_source' => 'Leave it as TeX (for text browsers)',
'mw_math_modern' => 'Raccomandà par i browser pì novi',
'mw_math_mathml' => 'MathML if possible (experimental)',

# Patrolling
'markaspatrolleddiff'        => 'Segna la modifica come verificà',
'markaspatrolledtext'        => 'Segna sto arthicoło come verificà',
'markedaspatrolled'          => 'Segnà come verificà',
'markedaspatrolledtext'      => 'La revixion selezsionà xè stà segnà come verificata.',
'rcpatroldisabled'           => 'Recent Changes Patrol disabled',
'markedaspatrollederror'     => 'Impossibiłe contrassegnare ła voçe come verificà',
'markedaspatrollederrortext' => 'Occorre speçificare na revixion da contrazsegnare come verificà.',

# Image deletion
'deletedrevision' => 'Vecia verzsion scancełà $1',

# Browsing diffs
'nextdiff' => 'Next diff →',

# Media information
'mediawarning' => "'''Warning''': This file may contain malicious code, by executing it your system may be compromised.<hr />",
'imagemaxsize' => 'Limita łe imagini o łe paxène de descrizsion de łe imagini a:',

'newimages' => 'Imagini nove',
'noimages'  => 'Non ghè gnente da vardare.',

'passwordtooshort' => "La to password l'è massa breve. La deve contegnere almanco $1 caratteri.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'This file contains additional information, probably added from the digital camera or scanner used to create or digitize it. If the file has been modified from its original state, some details may not fully reflect the modified image.',
'metadata-expand'   => 'Show extended details',
'metadata-collapse' => 'Hide extended details',

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
'namespacesall' => 'all',

# E-mail address confirmation
'confirmemail'            => 'Conferma indirizso e-mail',
'confirmemail_text'       => "Stà wiki richiede che el to indirizso email vengna verificà prima de poder usare le funzsioni connesse all'email. Struca el botton sotto par inviare na mail de conferma al to indirizso. La mail include un link contenente un codice; inseriszi el link nel to browser par confermar che el to indirizso email xè valido.",
'confirmemail_send'       => 'Spedisi un codice de conferma via mail.',
'confirmemail_sent'       => 'Email de conferma invià.',
'confirmemail_sendfailed' => "Impossibiłe inviar na mail de conferma. Controła che l'indirizso non contengna caratteri non vałidi.",
'confirmemail_invalid'    => 'Codice de conferma non valido. El codice podarìa esser scadù.',
'confirmemail_needlogin'  => 'Xè necessario $1 par confermare el proprio indirizso e-mail.',
'confirmemail_success'    => "El to indirizso email l'è stato confermà. Ora te podi loggarte e gòderte la wiki.",
'confirmemail_loggedin'   => 'El to indirizso email xè stà confermà.',
'confirmemail_error'      => "Qualcosa l'è andà storto nel salvar la to conferma.",
'confirmemail_subject'    => "{{SITENAME}}: email par la conferma dell'indirizso",
'confirmemail_body'       => 'Qualcuno, probabilmente ti stesso dall\'indirizso IP $1, ga registrà n\'account "$2" con sto indirizso e-mail su {{SITENAME}}. 

Par confermar che deto account realmente el te apartegne e poder attivar łe opzsioni relative a łe e-mail su {{SITENAME}}, apri el cołegamento seguente con el to browser: 

$3 

Se l\'account *non* xè el tuo, non visitare el cołegamento. El codexe de conferma scadrà a łe $4.',

# Delete conflict
'deletedwhileediting' => 'Attenzsion: Sta paxèna xè stà scancełà dopo che te ghè scominzsià a modificarla!',
'confirmrecreate'     => "L'utente [[User:$1|$1]] ([[User talk:$1|discussion]]) ga scancełà sta voçe dopo che te ghè inizsià a modificarla, con ła seguente motivazsion: ''$2'' 
Per favore conferma che te vołi veramente ricrear sta voçe.",

# action=purge
'confirm_purge'        => 'Vóto scancełar ła cache in sta paxèna?

$1',
'confirm_purge_button' => 'OK',

'searchcontaining' => "Riçerca de łe voçi che contegne ''$1''.",
'articletitles'    => "Rizserca de łe voçi che łe inizsia par ''$1''",
'hideresults'      => 'Hide results',

# Auto-summaries
'autoredircomment' => 'Redirect a ła paxèna [[$1]]', # This should be changed to the new naming convention, but existed beforehand

);



