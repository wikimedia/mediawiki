<?php

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesIt = array(
	-2	=> "Media",
	-1	=> "Speciale",
	0	=> "",
	1	=> "Discussione",
	2	=> "Utente",
	3	=> "Discussioni_utente",
	4	=> $wgMetaNamespace,
	5	=> "Discussioni_".$wgMetaNamespace,
	6	=> "Immagine",
	7	=> "Discussioni_immagine",
	8	=> "MediaWiki",
	9	=> "Discussione_MediaWiki",
);

/* private */ $wgQuickbarSettingsIt = array(
	"Nessuno", "Fisso a sinistra", "Fisso a destra", "Fluttuante a sinistra"
);

/* private */ $wgSkinNamesIt = array(
	"Standard", "Nostalgia", "Cologne Blue"
);

/* private */ $wgUserTogglesIt = array(
	"hover"		=> "Mostra etichetta sui wiki-links",
	"underline" => "Sottolinea links",
	"highlightbroken" => "Evidenzia i links che puntano ad<br>articoli ancora da scrivere",
	"justify"	=> "Paragrafo: giustificato",
	"hideminor" => "Nascondi le modifiche minori<br>nella pagina \"Modifiche recenti\"",
	"usenewrc" => "Enhanced recent changes (not for all browsers)",
	"numberheadings" => "Auto-numerazione dei<br>titoli di paragrafo",
	"showtoolbar" => "Show edit toolbar",
	"editondblclick" => "Doppio click per modificare l'articolo<br>(richiede JavaScript)",
	"editsection"=>"Enable section editing via [edit] links",
	"editsectiononrightclick"=>"Enable section editing by right clicking<br> on section titles (JavaScript)",
	"showtoc"=>"Show table of contents<br>(for articles with more than 3 headings)",
	"rememberpassword" => "Ricorda la password<br>(non limitare a una sessione<br>- richiede uso di cookies)",
	"editwidth" => "Casella di edizione ampliata<br>alla massima larghezza",
	"watchdefault" => "Notifica articoli nuovi e modificati",
	"minordefault" => "Indica ogni modifica come minore<br>(solo come predefinito)",
	"previewontop" => "Show preview before edit box and not after it",
	"nocache" => "Disable page caching"
);

/* These should be localized... any Italian online bookstores take ISBN searches? */
/* private */ $wgBookstoreListIt = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* Just inherit the (mostly) native-language plus latinized formed */
/* private */ $wgLanguageNamesIt = $wgLanguageNamesEn;

/* private */ $wgWeekdayNamesIt = array(
	"Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì",
	"Venerdì", "Sabato"
);

/* private */ $wgMonthNamesIt = array(
	"Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno",
	"Luglio", "Agosto", "Settembre", "Ottobre", "Novembre",
	"Dicembre"
);

/* private */ $wgMonthAbbreviationsIt = array(
	"Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago",
	"Set", "Ott", "Nov", "Dic"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesIt = array(
	"Userlogin"		=> "", # These two intentionally left blank
	"Userlogout"	=> "", #...
	"Preferences"	=> "Preferenze",
	"Watchlist"		=> "Osservati Speciali",
	"Recentchanges" => "Ultime modifiche",
	"Upload"		=> "Salva immagini",
	"Imagelist"		=> "Elenco Immagini",
	"Listusers"		=> "Utenti registrati",
	"Statistics"	=> "Statistiche",
	"Randompage"	=> "Una pagina a caso",

	"Lonelypages"	=> "Articoli orfani",
	"Unusedimages"	=> "Immagini orfane",
	"Popularpages"	=> "Articoli più letti",
	"Wantedpages"	=> "Articoli più richiesti",
	"Shortpages"	=> "Articoli più corti",
	"Longpages"		=> "Articoli più lunghi",
	"Newpages"		=> "Articoli nuovi",
	"Ancientpages"		=> "Oldest pages",
	"Allpages"		=> "Tutti i titoli",

	"Ipblocklist"	=> "Indirizzi IP bloccati",
	"Maintenance" => "Manutenzioni",
	"Specialpages"  => "", # From here on intentionally left blank!
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "",
	"Export"	=> "XML export",
	"Version"	=> "Version",
);

/* private */ $wgSysopSpecialPagesIt = array(
	"Makesysop" => "Turn a user into a sysop",
	"Blockip"		=> "Blocca indirizzo IP",
	"Asksql"		=> "Interroga database",
	"Undelete"		=> "Leggi e ripara pagine cancellate"
);

/* private */ $wgDeveloperSpecialPagesIt = array(
	"Lockdb"		=> "Rendi il database read-only (di sola lettura, blocca le modifiche)",
	"Unlockdb"		=> "Ripristina scrittura su database (lettura\/scrittura)",
);

/* private */ $wgAllMessagesIt = array(

# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Wikipedia",
"about"			=> "A proposito di ",
"aboutwikipedia" => "A proposito di Wikipedia",
"aboutpage"		=> "{$wgMetaNamespace}:About",
"help"			=> "Aiuto",
"helppage"		=> "{$wgMetaNamespace}:Aiuto",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "Malfunzionamenti",
"bugreportspage" => "{$wgMetaNamespace}:Malfunzionamenti",
"faq"			=> "FAQ",
"faqpage"		=> "{$wgMetaNamespace}:FAQ",
"edithelp"		=> "Guida",
"edithelppage"	=> "{$wgMetaNamespace}:Come_scrivere_un_articolo",
"cancel"		=> "Cancella",
"qbfind"		=> "Trova",
"qbbrowse"		=> "Sfoglia",
"qbedit"		=> "Modifica",
"qbpageoptions" => "Opzioni pagina",
"qbpageinfo"	=> "Informazioni sulla pagina",
"qbmyoptions"	=> "Le mie opzioni",
"mypage"		=> "La mia pagina",
"mytalk"		=> "Le mie discussioni",
"currentevents" => "Attualità",
"errorpagetitle" => "Errore",
"returnto"		=> "Torna a $1.",
"fromwikipedia"	=> "Da Wikipedia, l'enciclopedia libera.",
"whatlinkshere"	=> "Pagine che linkano questa",
"help"			=> "Aiuto",
"search"		=> "Cerca",
"history"		=> "Versioni precedenti",
"printableversion" => "Versione stampabile",
"editthispage"	=> "Modifica articolo",
"deletethispage" => "Cancella pagina",
"protectthispage" => "Proteggi pagina",
"unprotectthispage" => "Togli protezione",
"talkpage"		=> "Discussione",
"articlepage"	=> "Vedi articolo",
"subjectpage"	=> "Vedi articolo ", # For compatibility
"userpage" => "Vedi pagina Utente",
"wikipediapage" => "Vedi pagina meta ",
"imagepage" => 	"Pagina immagine",
"otherlanguages" => "Altre lingue",
"redirectedfrom" => "(Reindirizzamento da $1)",
"lastmodified"	=> "Ultima modifica il $1.",
"viewcount"		=> "Questo articolo è stato letto $1 volte.",
"gnunote" => "Questa pagina è disponibile con licenza <a class=internal href='/wiki/GNU_FDL'>GNU FDL</a>.",
"printsubtitle" => "(Articolo di http://www.wikipedia.org)",
"protectedpage" => "Pagina protetta",
"administrators" => "{$wgMetaNamespace}:Amministratori",
"sysoptitle"	=> "Riservato Sysop",
"sysoptext"		=> "Questa operazione può essere eseguita solo da Utenti con grado di \"sysop\".
Vedi $1.",
"developertitle" => "Riservato agli sviluppatori",
"developertext"	=> " Questa operazione può essere eseguita solo da Utenti con grado di \"developer\".
Vedi $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Vai",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "L'Enciclopedia libera",
"retrievedfrom" => "Ricavato da  \"$1\"",

# Main script and global functions
#
"nosuchaction"	=> "Operazione non riconosciuta",
"nosuchactiontext" => "L'operazione richiesta con la URL immessa non è stata riconosciuta dal software di Wikipedia",
"nosuchspecialpage" => "Nessuna simile pagina speciale è disponibile",
"nospecialpagetext" => "Hai richiesto una pagina speciale che non è stata riconosciuta dal software di Wikipedia, o che non è disponibile.",

# General errors
#
"error"			=> "Errore",
"databaseerror" => "Errore del database ",
"dberrortext"	=> "Errore di sintassi nella richiesta inoltrata al database.<br>Potrebbe trattarsi di una richiesta non consentita (vedi $5), oppure potrebbe esserci un errore software.<br>
L'ultima richiesta inoltrata al database è stata:<br>
<blockquote><tt>$1</tt></blockquote>
dalla funzione \"<tt>$2</tt>\".<br>
MySQL ha restituito un errore \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Connessione al database fallita su $1",
"nodb"			=> "Selezione del database $1 fallita",
"readonly"		=> "Accesso al database temporaneamente disabilitato",
"enterlockreason" => "Fornisci una spiegazione sui motivi del blocco, includendo le probabili data ed ora di riattivazione o di rimozione del blocco.",
"readonlytext"	=> "Il database di Wikipedia è al momento bloccato, e non consente nuove immissioni né modifiche, molto probabilmente per manutenzione server, nel qual caso il database sarà presto di nuovo completamente accessibile.<br>
L'amministratore di sistema che ha imposto il blocco, ha lasciato questa nota:
<p>:$1",
"missingarticle" => "Il database non ha trovato il testo di una pagina, che invece avrebbe dovuto trovare, intitolata \"$1\".<br>
Questo non è un errore del database, ma più probabilmente un problema del software.<br>
Per favore, segnalate l'accaduto ad un amministratore di sistema, segnalando la URL e l'ora dell'incidente.",
"internalerror" => "Errore interno",
"filecopyerror" => "Non è stato possibile copiare il file \"$1\" come \"$2\".",
"filerenameerror" => "Non è stato possibile rinominare il file \"$1\" in \"$2\".",
"filedeleteerror" => "Non è stato possibile cancellare il file \"$1\".",
"filenotfound"	=> " Non è stato possibile trovare il file \"$1\".",
"unexpected"	=> "Valore imprevisto: \"$1\"=\"$2\".",
"formerror"		=> "Errore: il modulo non è stato inviato correttamente",	
"badarticleerror" => "Questa operazione non è consentita su questa pagina.",
"cannotdelete"	=> "Impossibile cancellare la pagina o l'immagine richiesta.",
"badtitle"		=> "Titolo non corretto",
"badtitletext"	=> "La pagina richiesta non è disponibile, potrebbe essere non valida, vuota, o potrebbe trattarsi di un errore in un link interlinguistico o fra diverse versioni di Wikipedia.",
"perfdisabled" => "Siamo davvero rammaricati, ma questa funzionalità è temporaneamente disabilitata durante le ore di maggiore accesso al database, per ragioni di accessibilità al resto del sito!<br>Torna fra le 02:00 e le 14:00 UTC e riprova.<br><br>Grazie.",

# Login and logout pages
#
"logouttitle"	=> "Logout Utente",
"logouttext"	=> "Logout effettuato.
Ora puoi continuare ad usare Wikipedia come utente anonimo (ma il tuo indirizzo IP resterà riconoscibile), oppure puoi nuovamente richiedere il login con il precedente username, oppure come uno diverso.\n",

"welcomecreation" => "<h2>Benvenuto, $1!</h2><p>Il tuo account è stato creato con successo.<br>Grazie per aver scelto di far crescere Wikipedia con il tuo aiuto.<br>Per rendere Wikipedia più tua, e per usarla più scorrevolmente, non dimenticare di personalizzare le tue preferenze.",

"loginpagetitle" => "Login",
"yourname"		=> "Il tuo  user name",
"yourpassword"	=> "La tua  password",
"yourpasswordagain" => "Ripeti la password",
"newusersonly"	=> " (solo per nuovi Utenti)",
"remembermypassword" => "Ricorda la mia password per più sessioni (richiede uso dei cookies).",
"loginproblem"	=> "<b>Si è verificato un errore durante il tuo tentativo di login.</b><br>Riprova, sarai più fortunato!",
"alreadyloggedin" => "<font color=red><b>Ehi, Utente $1, hai già fatto il login, sei già connesso al nostro server!</b></font><br>\n",

"login"			=> "Log in",
"userlogin"		=> "Log in",
"logout"		=> "Log out",
"userlogout"	=> "Log out",
"createaccount"	=> "Crea nuovo account",
"badretype"		=> "Le password che hai immesso non coincidono, sono diverse fra loro.",
"userexists"	=> "Siamo spiacenti.<br>Lo user name che hai scelto è già usato da un altro Utente.<br>Ti preghiamo perciò di voler scegliere uno user name diverso.",
"youremail"		=> "La tua e-mail",
"yournick"		=> "Il tuo diminutivo o soprannome (per le firme)",
"emailforlost"	=> "Se per caso ti dimenticassi della tua password, ne potresti ricevere una nuova di zecca presso la casella e-mail che ci hai indicato.",
"loginerror"	=> "Errore di Login",
"noname"		=> "Lo user name indicato non è valido, non è possibile creare un account a questo nome.",
"loginsuccesstitle" => "Login effettuato con successo!",
"loginsuccess"	=> "Sei stato ammesso alla connessione al server di Wikipedia con il nome utente di \"$1\".",
"nosuchuser"	=> "Attenzione<br><br>a seguito di verifica, non ci risulta alcun Utente con il nome di  \"$1\".<br><br>
Controlla per favore il nome digitato, oppure usa il modulo qui sotto per creare un nuovo user account.",
"wrongpassword"	=> "La password immessa non è corretta.<br><br>Riprova, per favore.",
"mailmypassword" => "Spediscimi una nuova password in posta elettronica",
"passwordremindertitle" => "Servizio Password Reminder di Wikipedia",
"passwordremindertext" => "Qualcuno (probabilmente tu, con indirizzo IP $1)
ha richiesto l'invio di una nuova password per il login a Wikipedia. 
La password per l'Utente \"$2\" è ora \"$3\".
Per evidenti ragioni di sicurezza, dovresti fare un log in il prima possibile, e cambiare la password immediatamente.",
"noemail"		=> "Nessuna casella e-mail risulta registrata per l'Utente \"$1\".",
"passwordsent"	=> "Una nuova password è stata inviata alla casella e-mail registrata per l'Utente \"$1\".
Per favore, fai subito un log in non appena la ricevi.",

# Edit pages
#
"summary"		=> "Oggetto",
"minoredit"		=> "Questa è una modifica minore",
"watchthis"		=> "Tieni d'occhio questo articolo",
"savearticle"	=> "Salva la pagina",
"preview"		=> "Anteprima",
"showpreview"	=> "Visualizza Anteprima",
"blockedtitle"	=> "Questo User name corrisponde purtroppo ad un Utente che è stato disabilitato alla modifica degli articoli.",
"blockedtext"	=> "Il tuo User name o il tuo indirizzo IP sono stati bloccati da $1.<br>
La motivazione del blocco è la seguente:<br>:''$2''<p>Se lo desideri, puoi contattare $1, o uno degli altri [[{$wgMetaNamespace}:administrators|amministratori]] per discutere del blocco.",
"newarticle"	=> "(Nuovo)",
"newarticletext" => "Scrivi qui il tuo testo.",
"noarticletext" => "(Questo articolo è vuoto, potresti gentilmente iniziare l'articolo, oppure richiedere la cancellazione di questa pagina)",
"updated"		=> "(Aggiornato)",
"note"			=> "<strong>NOTA:</strong> ",
"previewnote"	=> "Tieni presente che questa è solo una ANTEPRIMA, e che la tua versione NON è ancora stata salvata!",
"previewconflict" => "Questa anteprima rappresenta il testo nella casella di edizione di sopra, l'articolo apparirà in questa forma se sceglierai di salvare la pagina ora.",
"editing"		=> "Modifica di $1",
"editconflict"	=> "Conflitto di edizione: $1",
"explainconflict" => "Qualcun altro ha salvato una sua versione dell'articolo nel tempo in cui tu stavi preparando la tua versione.<br>
La casella di modifica di sopra contiene il testo dell'articolo nella sua forma attuale (cioè il testo attualmente online). Le tue modifiche sono invece contenute nella casella di modifica inferiore. 
Dovrai inserire, se lo desideri, le tue modifiche nel testo esistente, e perciò scriverle nella casella di sopra. 
<b>Soltanto</b> il testo nella casella di sopra sarà salvato se premerai il bottone \"Salva\".\n<p>",
"yourtext"		=> "Il tuo testo",
"storedversion" => "Versione in archivio",
"editingold"	=> "<strong>ATTENZIONE: Stai modificando una versione dell'articolo non aggiornata.<br>
Se la salvi così, tutti i cambiamenti apportati dopo questa revisione verranno persi per sempre.</strong>\n",
"yourdiff"		=> "Differenze",
"copyrightwarning" => "Nota, per favore, che tutti i contributi a Wikipedia si considerano rilasciati sotto licenza di tipo GNU Free Documentation License (vedi $1 per maggiori dettagli).
Se non vuoi che il tuo testo possa essere modificato e ridistribuito da chiunque senza pietà e senza altri limiti, allora non inviarlo a Wikipedia, ma realizza piuttosto un tuo sito web personale.<br>
Con l'invio di questo testo stai garantendo, a tua responsabilità, che il testo è stato scritto da te personalmente ed originalmente, oppure che è stato copiato da una fonte di publico dominio, o da una simile fonte, oppure che hai ottenuto espressa autorizzazione ad usare questo testo e che puoi dimostrarlo.
<strong>NON USARE MATERIALE COPERTO DA DIRITTO DI AUTORE (COPYRIGHT - (c)) IN MANCANZA DI ESPRESSA AUTORIZZAZIONE!!!</strong>",


# History pages
#
"revhistory"	=> "Cronologia delle versioni di questa pagina.",
"nohistory"		=> "Cronologia delle versioni di questa pagina non reperibile.",
"revnotfound"	=> "Versione non trovata ",
"revnotfoundtext" => "La versione precedente di questo articolo che hai richiesto, non è stata trovata.
Controlla per favore la URL che hai usato per accedere a questa pagina.\n",
"loadhist"		=> "Caricamento cronologia di questa pagina",
"currentrev"	=> "Versione attuale",
"revisionasof"	=> "Revisione $1",
"cur"			=> "corr",
"next"			=> "succ",
"last"			=> "prec",
"orig"			=> "orig",
"histlegend"	=> "Legend: (corr) = differenze con la versione corrente,
(prec) = differenze con la versione precedente, M = modifica minore",

# Diffs
#
"difference"	=> "(Differenze fra le revisioni)",
"loadingrev"	=> "caricamento revisione per differenze",
"lineno"		=> "Riga $1:",
"editcurrent"	=> "Modifica la versione corrente di questa pagina",

# Search results
#
"searchresults" => "Risultato della ricerca",
"searchhelppage" => "{$wgMetaNamespace}:Ricerca",
"searchingwikipedia" => "Ricerca in Wikipedia",
"searchresulttext" => "Per maggiori informazioni sulla ricerca interna di Wikipedia, vedi $1.",
"searchquery"	=> "Richiesta \"$1\"",
"badquery"		=> "Richiesta mal inoltrata ",
"badquerytext"	=> "La tua richiesta non ha potuto essere processata.
Questo potrebbe dipendere dall'aver ricercato una parola di meno di tre caratteri.
Oppure potresti aver scritto male la richiesta, per esempio \"pesce and and azzurro\".
Per favore, riprova.",
"matchtotals"	=> "La ricerca per la voce \"$1\" ha trovato<br>$2 riscontri nei titoli degli articoli e<br>$3 riscontri nei testi degli articoli.",
"titlematches"	=> "Nei titoli degli articoli",
"notitlematches" => "Voce richiesta non trovata in titoli di articolo",
"textmatches"	=> "Nel testo degli articoli ",
"notextmatches"	=> "Voce richiesta non trovata in testi di articolo",
"prevn"			=> "precedenti $1",
"nextn"			=> "successivi $1",
"viewprevnext"	=> "Vedi ($1) ($2) ($3).",
"showingresults" => "Qui di seguito <b>$1</b> risultati, partendo dal numero #<b>$2</b>.",
"nonefound"		=> "<strong>Nota</strong>: la ricerca di parole troppo comuni, come \"avere\" o \"essere\", che non sono indicizzate, può causare un esito negativo, così come indicare più di un termine da ricercare (solo le pagine che contengano tutti i termini ricercati verrebbero infatti visualizzate fra i risultati).",
"powersearch" => "Ricerca",
"powersearchtext" => "
Cerca fra i campi :<br>
$1<br>
$2 Elenca i redirects &nbsp; cerca per $3 $9",


# Preferences page
#
"preferences"	=> "Preferenze",
"prefsnologin" => "Non hai eseguito il login",
"prefsnologintext"	=> "Devi avere eseguito il <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">login</a>
per poter personalizzare le tue preferenze.",
"prefslogintext" => "Sei connesso a Wikipedia come \"$1\".
Il tuo numero identificativo (ID) interno è $2.",
"prefsreset"	=> "Le tue Preferenze sono state ripescate dalla memoria di sistema del potente server di Wikipedia.",
"qbsettings"	=> "Settaggio barra menu", 
"changepassword" => "Cambia password",
"skin"			=> "Aspetto",
"saveprefs"		=> "Salva preferenze",
"resetprefs"	=> "Resetta preferenze",
"oldpassword"	=> "Vecchia password",
"newpassword"	=> "Nuova password",
"retypenew"		=> "Riscrivi la nuova password",
"textboxsize"	=> "Dimensione della casella di edizione ",
"rows"			=> "Righe",
"columns"		=> "Colonne",
"searchresultshead" => "Settaggio preferenze di ricerca ",
"resultsperpage" => "Risultati da visualizzare per pagina",
"contextlines"	=> "Righe di testo per ciascun risultato",
"contextchars"	=> "Caratteri per linea",
"stubthreshold" => "Stub visualizzati",
"recentchangescount" => "Numero titoli in \"modifiche recenti\" ",
"savedprefs"	=> "Le tue preferenze sono state salvate.",
"timezonetext"	=> "Immetti il numero di ore di differenza fra la tua ora locale e la ora del server (UTC).",
"localtime"	=> "Ora Locale",
"timezoneoffset" => "Offset",
"emailflag"		=> "Nascondi la mia e-mail<br>agli altri utenti",

# Recent changes
#
"recentchanges" => "Ultime Modifiche",
"recentchangestext" => "Segui in questa pagina le ultime modifiche apportate agli articoli di Wikipedia.
[[{$wgMetaNamespace}:Welcome,_newcomers|Benvenuto]]!
Leggi anche queste pagine: [[{$wgMetaNamespace}:FAQ|Wikipedia FAQ]],
[[{$wgMetaNamespace}:Policies and guidelines|la policy di Wikipedia]]
(specialmente [[{$wgMetaNamespace}:Convenzioni di nomenclatura| Convenzioni di nomenclatura]],
[[{$wgMetaNamespace}:Neutral point of view|oggettività e neutralità]]),
e [[{$wgMetaNamespace}:Most common Wikipedia faux pas|facili errori nell'uso di Wikipedia]].

Se tieni al successo di questo progetto, è molto importante che eviti di immettere materiale coperto da diritti di autore ([[{$wgMetaNamespace}:Copyrights|copyrights]]).
Gli aspetti legali connessi potrebbero dare fastidio a noi ed a te personalmente, perciò controlla bene che quanto scrivi sia di [[pubblico dominio]], o prova ad ottenere le relative autorizzazioni, che in genere vengono concesse molto facilmente. Vedi anche [http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion].",
"rcloaderr"		=> "Caricamento modifiche recenti ",
"rcnote"		=> "Qui di seguito sono elencate le ultime <strong>$1</strong> pagine modificate negli ultimi <strong>$2</strong> giorni.",
"rcnotefrom"	=> " Qui di seguito sono elencate le modifiche da <b>$2</b> (fino a <b>$1</b>).",
"rclistfrom"	=> "Mostra modifiche a partire da $1",
# "rclinks"		=> "Mostra le ultime $1 modifiche nelle ultime $2 ore / negli ultimi $3 giorni",
"rclinks"		=> " Mostra le ultime $1 modifiche negli ultimi $2 giorni.",
"rchide"		=> "in $4 form; $1 modifiche minori; $2 namespaces secondari; $3 modifiche multiple.",
"diff"			=> "diff",
"hist"			=> "cron",
"hide"			=> "nascondi",
"show"			=> "mostra",
"tableform"		=> "tabella",
"listform"		=> "elenco",
"nchanges"		=> "$1 modifiche",
"minoreditletter" => "m",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Upload",
"uploadbtn"		=> "Upload",
"uploadlink"	=> "Upload immagini",
"reupload"		=> "Ri-upload",
"reuploaddesc"	=> "Torna al modulo per lo upload.",
"uploadnologin" => "Devi fare il login per eseguire questa operazione.",
"uploadnologintext"	=> "Devi eseguire  <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">il login</a>
per fare lo upload di files.",
"uploadfile"	=> "Upload",
"uploaderror"	=> "Errore di Upload",
"uploadtext"	=> "<strong>FERMA!</strong> Prima di effettuare un upload su Wikipedia, accertati di avere ben letto e soprattutto compreso <a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Image_use_policy" ) . "\">le regole di Wikipedia sull'uso delle immagini</a>.
<p>Per visualizzare o cercare immagini precedentemente caricate su Wikipedia, vai alla <a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">lista delle immagini già caricate</a>.
Uploads e cancellazioni delle immagini sono registrati nello <a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Upload_log" ) . "\">upload log</a>.
<p>Usa il modulo sottostante per caricare nuovi files immagine da utilizzare per arricchire ed illustrare i tuoi articoli.
Sulla maggior parte dei browsers, dovresti vedere un bottone con la scritta \"Browse...\" (oppure \"Sfoglia...\", che aprirà una comune finestra di dialogo.<br>
Scegliendo uno dei files sul tuo PC, il nome di questo file verrà scritto in automatico nella casella di testo a fianco al bottone.<p>
'''Devi anche selezionare la casellina nella quale affermi che con questo upload non stai violando nessun copyright.'''<p>
Premi poi il bottone \"Upload\" per completare il caricamento.
Il caricamento può richiedere qualche minuto se hai una connessione ad Internet lenta, o se l'immagine è eccessivamente pesante (sconsigliato).
<p>I formati immagine preferibili sono il JPEG per immagini fotografiche, il PNG
per disegni ed altre immagini iconiche o simboliche, l' OGG per i suoni.
Per cortesia, rinomina i tuoi files, prima di caricarli, usando un nome il più possibile descrittivo del contenuto, così da evitare confusioni. 
Per inserire poi la nuova immagine in un articolo, usa semplicemente un link nella forma
<b>[[image:file.jpg]]</b> o <b>[[image:file.png|alt text, testo alternativo]]</b>
o <b>[[media:file.ogg]]</b> per i suoni.
<p>Tieni presente che, come per tutte le pagine di Wikipedia, chiunque può modificare o sostituire o cancellare i tuoi files ove ritenga che ciò sia negli interessi della nostra enciclopedia. Tieni anche presente che, in caso di abuso, o di sovraccarico sul sistema, potresti essere bloccato (oltre ad essere perseguito per le connesse responsabilità).",
"uploadlog"		=> "upload log",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Qui di seguito la lista degli ultimi files caricati sul server di Wikipedia.
Tutti i tempi indicati sono calcolati sul fuso orario del server (UTC).
<ul>
</ul>
",
"filename"		=> "Nome del file",
"filedesc"		=> "Oggetto",
"affirmation"	=> "Io dichiaro che il titolare dei diritti di autore (copyright, (c)) su questo file consente a cederlo in licenza di uso nei termini del $1.",
"copyrightpage" => "{$wgMetaNamespace}:Copyrights",
"copyrightpagename" => "Wikipedia copyright",
"uploadedfiles"	=> "Files Caricati in Wikipedia",
"noaffirmation" => "Devi dichiarare che il caricamento di questo file non viola, in alcun modo, alcun tipo di diritto altrui, e segnatamente non infrange nessun diritto di autore (copyright - (c)).",
"ignorewarning"	=> "Ignora le avvertenze e salva comunque il file.",
"minlength"		=> "I nomi dei file immagine debbono essere lunghi almeno tre caratteri, ma è preferibile usare nomi lumghi, purché descrittìvi.",
"badfilename"	=> "Il nome del file immagine è stato convertito in \"$1\".",
"badfiletype"	=> "\".$1\" non è un tipo di file raccomandato per le immagini, almeno ai nostri fini.",
"largefile"		=> "Il peso raccomandato per le immagini deve essere inferiore a 100kb.",
"successfulupload" => "Caricamento completato",
"fileuploaded"	=> "File \"$1\" correttamente caricato sul server.
Segui questo link: ($2) per modificare la pagina di descrizione del file che hai appena caricato, e immetti subito le informazioni che ritieni opportune (cosa rappresenta, dove lo hai trovato, chi lo ha creato e quando, etc) oltre ad una nota circa la situazione di copyright sul file.<br>Non omettere la nota sul copyright, o il file verrebbe cancellato molto presto. ",
"uploadwarning" => "Avviso di Upload",
"savefile"		=> "Salva file",
"uploadedimage" => "caricato \"$1\"",

# Image list
#
"imagelist"		=> "Lista delle immagini",
"imagelisttext"	=> "Qui di seguito una lista di $1 immagini, ordinate per $2.",
"getimagelist"	=> "ricerca nella lista delle immagini ",
"ilshowmatch"	=> "Mostra tutte le immagini con nomi di file corrispondenti alla ricerca",
"ilsubmit"		=> "Cerca",
"showlast"		=> "Lista di $1, fra le ultime immagini, ordinate per $2.",
"all"			=> "tutte",
"byname"		=> "nome",
"bydate"		=> "data",
"bysize"		=> "peso",
"imgdelete"		=> "canc",
"imgdesc"		=> "desc",
"imglegend"		=> "Legenda: (desc) = mostra/modifica descrizione immagine.",
"imghistory"	=> "Storia di questa immagine",
"revertimg"		=> "ripr",
"deleteimg"		=> "canc",
"imghistlegend" => "Legenda: (cur) = immagine corrente, (canc) = cancella questa vecchia versione, (ripr) = ripristina questa vecchia versione come versione attuale.
<br><i>Clicca su una data per vedere tutte le immagini che sono state caricate in quella data </i>.",
"imagelinks"	=> "Link alle immagini",
"linkstoimage"	=> "Le pagine seguenti linkano questa immagine:",
"nolinkstoimage" => "Nessuna pagina linka questa immagine.",

# Statistics
#
"statistics"	=> "Statistiche",
"sitestats"		=> "Statistiche del sito",
"userstats"		=> "Statistiche del nostro Wikipediano",
"sitestatstext" => "Ci sono ben <b>$1</b> pagine nel database.
Questa cifra comprende le pagine \"talk\" (discussione), pagine su Wikipedia, articoli esigui (\"stub\"), redirects, e altre pagine che probabilmente non andrebbero conteggiate fra gli articoli.
Escludendo queste, ci sono ben  <b>$2</b> pagine che sono con buona probabilità propriamente degli articoli.<p>
Ci sono state un totale di <b>$3</b> pagine viste, e <b>$4</b> modifiche agli articoli da quando il software è stato potenziato (Dicembre, 2002).
Questa media rivela che ci sono state una media di  <b>$5</b> modifiche per ciascun articolo, e che l'articolo è stato letto <b>$6</b> volte per ciascuna modifica.",
"userstatstext" => "Ci sono <b>$1</b> Utenti registrati ([[Wikipediani]]).
<b>$2</b> di questi hanno il grado di amministratori (vedi $3).",

# Maintenance Page
#
"maintenance"		=> "Pagina manutenzioni",
"maintnancepagetext"	=> "In questa pagina sono elencati alcuni utili strumenti per una comoda manutenzione quotidiana della nostra enciclopedia. Alcune delle funzioni tendono a stressare il database, assorbendo molte risorse, perciò non fatene un uso continuo: non aggiornate le pagine (reload, refresh) subito dopo ogni singolo intervento. ;-)",
"maintenancebacklink"	=> "Torna alla pagina manutenzione",
"disambiguations"	=> "Disambiguation pages",
"disambiguationspage"	=> "{$wgMetaNamespace}:Links_to_disambiguating_pages",
"disambiguationstext"	=> "The following articles link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br>A page is treated as dismbiguation if it is linked from $1.<br>Links from other namespaces are <i>not</i> listed here.",
"doubleredirects"	=> "Doppi Redirects",
"doubleredirectstext"	=> "<b>Attenzione:</b> Questa lista può talvolta contenere dei risultati non corretti. Ciò potrebbe magari accadere perchè vi sono del testo aggiuntivo o dei link dopo il tag #REDIRECT.<br>\nOgni riga contiene i link al primo ed al secondo redirect, oltre alla prima riga di testo del secondo redirect che di solito contiene il \"reale\" articolo di destinazione, quello al quale anche il primo redirect dovrebbe puntare.",
"brokenredirects"	=> "Redirects errati",
"brokenredirectstext"	=> "I seguenti redirects puntano ad articoli non ancora creati.",
"selflinks"		=> "Pagine con Auto-Links",
"selflinkstext"		=> "Le pagine seguenti contengono link che puntano a sé stesse, e in questo caso occorre eliminare questi auto-links.",
"mispeelings"           => "Pagine con errori di ortografia ",
"mispeelingstext"               => "Le pagine che seguono contengono errori comuni di ortografia, che sono elencati alla pagina $1. ",
"mispeelingspage"       => "Lista di comuni errori di ortografia",
"missinglanguagelinks"  => "Link interlinguistici mancanti",
"missinglanguagelinksbutton"    => "Trova interlinks per ",
"missinglanguagelinkstext"      => "Questi articoli <i>non</i> hanno link verso i corrispondenti articoli in $1. Redirects e sub-pagine <i>non</i> sono elencati.",


# Miscellaneous special pages
#
"orphans"		=> "Pagine orfane",
"lonelypages"	=> "Pagine solitarie",
"unusedimages"	=> "Immagini non utilizzate",
"popularpages"	=> "Pagine più viste",
"nviews"		=> "$1 visite",
"wantedpages"	=> "Articoli più richiesti",
"nlinks"		=> "$1 links",
"allpages"		=> "Tutte le pagine",
"randompage"	=> "Una pagina a caso",
"shortpages"	=> "Pagine corte",
"longpages"		=> "Pagine lunghe",
"listusers"		=> "Elenco degli Utenti",
"specialpages"	=> "Pagine speciali",
"spheading"		=> "Pagine speciali",
"sysopspheading" => "Pagine speciali riservate ai sysop",
"developerspheading" => " Pagine speciali riservate ai developer",
"protectpage"	=> "Proteggi questa pagina ",
"recentchangeslinked" => "Modifiche correlate",
"rclsub"		=> "(alle pagine linkate da \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "Pagine nuove",
"movethispage"	=> "Sposta questa pagina",
"unusedimagestext" => "<p>Nota che altri siti web, come la Wikipedia internazionale, potrebbero aver messo un link ad una immagine per mezzo di una URL diretta, perciò le immagini potrebbero essere listate qui, essendo inutilizzate in questa versione di Wikipedia, anche essendo magari in uso altrove.",
"booksources"	=> "Book sources",
"booksourcetext" => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.
Wikipedia is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.",

# Email this user
#
"mailnologin"	=> "No send address",
"mailnologintext" => "Devi fare il  <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">login</a>
ed aver registrato una valida casella e-mail nelle tue <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">preferenze</a> per mandare posta elettronica ad altri Utenti.",
"emailuser"		=> "Manda una E-mail a questo Utente",
"emailpage"		=> "E-mail user",
"emailpagetext"	=> "Se questo Utente ha registrato una valida casella e-mail, il modulo qui sotto ti consentirà di scrivergli un solo messaggio.
La e-mail che hai indicato nelle tue preferenze apparirà nel campo \"From\" della mail, così che il destinatario possa, solo se lo desidera però, risponderti.",
"noemailtitle"	=> "Nessun indirizzo e-mail",
"noemailtext"	=> "Questo Utente non ha registrato alcuna casella e-mail, oppure ha scelto di non ricevere posta elettronica dagli altri Utenti.",
"emailfrom"		=> "Da",
"emailto"		=> "A",
"emailsubject"	=> "Oggetto",
"emailmessage"	=> "Messaggio",
"emailsend"		=> "Invia",
"emailsent"		=> "E-mail inviata",
"emailsenttext" => "La tua e-mail è stata inviata.",

# Watchlist
#
"watchlist"		=> "Osservati Speciali",
"watchlistsub"	=> "(per l'Utente \"$1\")",
"nowatchlist"	=> "Non hai indicato articoli da tenere d'occhio.",
"watchnologin"	=> "Manca il login",
"watchnologintext"	=> "Devi prima fare il <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">login</a>
per modificare la tua lista di osservati speciali.",
"addedwatch"	=> "Aggiunto ai tuoi Osservati Speciali",
"addedwatchtext" => " La pagina  \"$1\" è stata aggiunta alla tua <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\"> lista di osservati speciali </a>.
Le future modifiche a questa pagina ed alla relativa pagina di discussione saranno elencate qui, e la pagina apparirà in <b>grassetto</b> nella pagina delle <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">modifiche recenti</a> per essere più facile da tener d'occhio.</p>

<p>Se in seguito vorrai togliere questo articolo dalla tua lista di Osservati Speciali, clicca \" Smetti di seguire \" nella barra dei menu.",
"removedwatch"	=> "Rimosso dalla lista degli Osservati Speciali",
"removedwatchtext" => "La pagina  \"$1\" è stata rimossa dalla lista dei tuoi Osservati Speciali.",
"watchthispage"	=> "Segui questo articolo",
"unwatchthispage" => "Smetti di seguire",
"notanarticle"	=> "Non è un articolo",

# Delete/protect/revert
#
"deletepage"	=> "Cancella pagina",
"confirm"		=> "Conferma",
"confirmdelete" => "Conferma cancellazione",
"deletesub"		=> "(Cancellazione di \"$1\")",
"confirmdeletetext" => "Stai per cancellare permanentemente dal database una pagina o una immagine, insieme a tutta la sua cronologia.<p>
Per cortesia, conferma che è tua intenzione procedere a tale cancellazione, conferma che hai piena consapevolezza delle conseguenze della tua azione, e conferma che la tua azione è pienamente ottemperante alle regole stabilite nella 
[[{$wgMetaNamespace}:Policy]].",
"confirmcheck"	=> "Sì, voglio davvero procedere con la cancellazione della pagina.",
"actioncomplete" => "Azione completata",
"deletedtext"	=> "La pagina \"$1\" è stata cancellata.
Vedi $2 per un elenco delle pagine cancellate di recente.",
"deletedarticle" => "Cancellata \"$1\"",
"dellogpage"	=> "Deletion_log",
"dellogpagetext" => "Qui di seguito, un elenco delle pagine cancellate di recente.
Tutti i tempi sono in ora del server (UTC).
<ul>
</ul>
",
"deletionlog"	=> "deletion log",
"reverted"		=> "Ripristinata versione precedente",
"deletecomment"	=> "Motivazione della cancellazione ",
"imagereverted" => "Versione precedente correttamente ripristinata.",
"rollback"		=> "Usa una revisione precedente",
"cantrollback"	=> "Impossibile tornare ad una versione precedente: l'ultima modifica è stata apportata dall'unico utente che abbia lavorato a questo articolo.",
"revertpage"	=> "Riportata alla revisione precedente da $1",

# Undelete
"undelete" => "Recupera una pagina cancellata",
"undeletepage" => "Vedi e recupera pagine cancellate ",
"undeletepagetext" => "Le pagine qui di seguito indicate sono state cancellate, ma sono ancora in archivio e pertanto possono essere recuperate. L'archivio viene svuotato periodicamente.",
"undeletearticle" => "Recupera un articolo cancellato",
"undeleterevisions" => "$1 revisioni in archivio",
"undeletehistory" => "Se recuperi questo articolo, tutte le sue revisioni verranno recuperate nella relativa cronologia.<br>
Se una nuova pagina è stata creata con questo stesso nome dopo la cancellazione, le revisioni recuperate saranno inserite nella cronologia e la versione attualmente online della pagina non verrà modificata.",
"undeleterevision" => "Cancellata revisione $1",
"undeletebtn" => "RIPRISTINA!",
"undeletedarticle" => "Recuperata \"$1\"",
"undeletedtext"   => "La pagina [[$1]] è stata recuperata.
Vedi [[{$wgMetaNamespace}:Deletion_log]] per un elenco delle pagine cancellate e recuperate di recente.",

# Contributions
#
"contributions"	=> "Contributi di questo Utente",
"contribsub"	=> "Per $1",
"nocontribs"	=> "Nessuna modifica trovata conformemente a questi criteri.",
"ucnote"		=> "Qui sotto troverai le ultime <b>$1</b> modifiche effettuate da questo Utente negli ultimi <b>$2</b> giorni.",
"uclinks"		=> "Vedi le ultime $1 modifiche; vedi gli ultimi $2 giorni.",
"uctop"		=> " (ultima per la pagina)" ,

# What links here
#
"whatlinkshere"	=> "Pagine che linkano questa",
"notargettitle" => "Dati mancanti",
"notargettext"	=> "Non hai specificato una pagina o un Utente in relazione al quale eseguire l'operazione richiesta.",
"linklistsub"	=> "(Lista di links)",
"linkshere"		=> "Le seguenti pagine contengono link che puntano qui:",
"nolinkshere"	=> "Nessuna pagina contiene links che puntano a questa.",
"isredirect"	=> "redirect ",

# Block/unblock IP
#
"blockip"		=> "Blocca indirizzo IP",
"blockiptext"	=> "Usa il modulo sottostante per bloccare l'accesso con diritto di scrittura da uno specifico indirizzo IP.<br>
Questo blocco deve essere operato SOLO per prevenire atti di vandalismo, ed in stretta osservanza dei principi tutti della [[{$wgMetaNamespace}:Policy|policy di Wikipedia]].<br>Il blocco non può in nessun caso essere applicato per motivi ideologici.<p> 
Scrivi un motivo specifico per il quale questo indirizzo IP dovrebbe a tuo avviso essere bloccato (per esempio, cita i titoli di pagine eventualmente già oggetto di vandalismo editoriale).",
"ipaddress"		=> "Indirizzo IP (IP Address)",
"ipbreason"		=> "Motivazione",
"ipbsubmit"		=> "Blocca questo indirizzo IP",
"badipaddress"	=> "L'indirizzo IP indicato non è corretto.",
"noblockreason" => "Devi obbligatoriamente fornire una motivazione per il blocco.",
"blockipsuccesssub" => "Blocco eseguito",
"blockipsuccesstext" => " L'indirizzo IP \"$1\" è stato bloccato.
<br>Vedi [[Special:Ipblocklist|lista IP bloccati]].",
"unblockip"		=> " Sblocca indirizzo IP",
"unblockiptext"	=> "Usa il modulo sottostante per restituire il diritto di scrittura ad un indirizzo IP precedentemente bloccato.",
"ipusubmit"		=> "Sblocca questo indirizzo IP",
"ipusuccess"	=> "Indirizzo IP \"$1\" sbloccato",
"ipblocklist"	=> "Lista degli indirizzi IP bloccati",
"blocklistline"	=> "$1, $2 ha bloccato $3",
"blocklink"		=> "blocca",
"unblocklink"	=> "sblocca",
"contribslink"	=> "contributi",

# Developer tools
#
"lockdb"		=> "Blocca il database",
"unlockdb"		=> "Sblocca il database",
"lockdbtext"	=> "Bloccare il database sospenderà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere non consentirà a nessuno di eseguire operazioni che richiedano modifiche del database.<br><br>
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare e, soprattutto, che il prima possibile sbloccherai nuovamente il database, ripristinandone la corretta funzionalità, non appena avrai terminato le tue manutenzioni.",
"unlockdbtext"	=> "Sbloccare il database ripristinerà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere di eseguire operazioni che richiedano modifiche del database.
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare.",
"lockconfirm"	=> "Sì, effettivamente intendo, sotto la mia responsabilità, bloccare il database.",
"unlockconfirm"	=> " Sì, effettivamente intendo, sotto la mia responsabilità, sbloccare il database.",
"lockbtn"		=> "Blocca il database",
"unlockbtn"		=> "Sblocca il database",
"locknoconfirm" => "Non hai spuntato la casellina di conferma.",
"lockdbsuccesssub" => "Blocco del database eseguito",
"unlockdbsuccesssub" => "Sblocco del database eseguito, rimosso blocco",
"lockdbsuccesstext" => "Il database di Wikipedia è stato bloccato.
<br>Ricordati di rimuovere il blocco non appena avrai terminatoi le tue manutenzioni.",
"unlockdbsuccesstext" => " Il database di Wikipedia è stato sbloccato.",

# SQL query
#
"asksql"		=> "Interrogazione SQL",
"asksqltext"	=> "Usa il modulo sottostante per effettuare una interrogazione diretta (query) al database di Wikipedia.
Usa singole virgolette ('come queste') per delimitare una stringa letterale.
Questo può considerevolmente sovraccaricare e di fatto rallentare il server, perciò per cortesia usa questa funzionalità solo quando necessario.",
"sqlquery"		=> "Immetti stringa da ricercare",
"querybtn"		=> "Invia interrogazione",
"selectonly"	=> "Interrogazioni diverse da \"SELECT\" sono riservate agli sviluppatori di Wikipedia (developers).",
"querysuccessful" => "Interrogazione riuscita",

# Move page
#
"movepage"		=> "Spostamento di pagina",
"movepagetext"	=> "Con il modulo sottostante puoi rinominare una pagina, spostando anche tutta la sua cronologia al nuovo nome.
Il vecchio titolo diverrà automaticamente un redirect che punta al nuovo titolo. 
I link alla vecchia pagina non saranno aggiornati (e punteranno quindi al redirect); accertati di [[Special:Manutenzioni|controllare con cura]] che non si creino doppi redirects o redirects interrotti.
Resta nella tua responsabilità di accertarti che i link continuino a puntare verso dove devono dirigersi.

Nota bene: la pagina '''non''' sarà spostata se vi fosse già un articolo con il nuovo nome, a meno che non sia una pagina vuota o un redirect, e sempre che non abbia cronologia. Questo significa che, se commetti un errore, puoi nuovamente rinominare una pagina col vecchio titolo, ma non puoi sovrascrivere una pagina già esistente.

<b>ATTENZIONE!</b>
Questo cambiamento drastico potrebbe creare inattesi contrattempi, specialmente se si tratta di una pagina molto visitata. Accertati di aver ben valutato le conseguenze dello spostamento, prima di procedere. Nel dubbio, contatta un Amministratore.",
"movepagetalktext" => "La corrispondente pagina di discussione, se esiste, sarà spostata automaticamente insieme all'articolo, '''tranne che nei seguenti casi:'''
*Spostamento della pagina fra i namespaces,
*Una pagina di discussione (non vuota) già esiste per il nuovo nome, oppure
*Hai deselezionato la casellina qui sotto.
In questi casi, se lo ritieni opportuno, dovrai spostare o aggiungere manualmente la pagina di discussione.",
"movearticle"	=> "Rinomina articolo",
"movenologin"	=> "Non hai effettuato il login",
"movenologintext" => "Devi essere un Utente registrato ed aver effettuato il <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">login</a>
per poter spostare una pagina.",
"newtitle"		=> "Al nuovo titolo di ",
"movepagebtn"	=> "Sposta questa pagina",
"pagemovedsub"	=> "Spostamento effettuato con successo",
"pagemovedtext" => "Pagina \"[[$1]]\" rinominata in \"[[$2]]\".",
"articleexists" => "Una pagina con questo nome esiste già, oppure il nome che hai scelto non è valido.<br>
Scegli, per cortesia, un titolo diverso per l'articolo.",
"talkexists"	=> "La pagina è stata spostata correttamente, ma non si è potuto spostare la pagina di discussione perché ne esiste già un'altra con il nuovo titolo. Per favore, modifica manualmente i contenuti delle due pagine discussione, così da mantenerle entrambe per non perdere potenzialmente interessanti riflessioni.",
"movedto"		=> "spostata a ",
"movetalk"		=> "Sposta anche la corrispondente pagina \"discussione\", se possibile.",
"talkpagemoved" => "Anche la corrispondente pagina di discussione è stata spostata.",
"talkpagenotmoved" => "La corrispondente pagina di discussione <strong>non è stata spostata</strong>."

);

include_once( "LanguageUtf8.php" );

class LanguageIt extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListIt;
		return $wgBookstoreListIt;
	}

	function getNamespaces() {
		global $wgNamespaceNamesIt;
		return $wgNamespaceNamesIt;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesIt;
		return $wgNamespaceNamesIt[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesIt;

		foreach ( $wgNamespaceNamesIt as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsIt;
		return $wgQuickbarSettingsIt;
	}

	function getSkinNames() {
		global $wgSkinNamesIt;
		return $wgSkinNamesIt;
	}

	function getUserToggles() {
		global $wgUserTogglesIt;
		return $wgUserTogglesIt;
	}

	function getLanguageNames() {
		global $wgLanguageNamesIt;
		return $wgLanguageNamesIt;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesIt;
		if ( ! array_key_exists( $code, $wgLanguageNamesIt ) ) {
			return "";
		}
		return $wgLanguageNamesIt[$code];
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesIt;
		return $wgMonthNamesIt[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsIt;
		return $wgMonthAbbreviationsIt[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesIt;
		return $wgWeekdayNamesIt[$key-1];
	}

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . (0 + substr( $ts, 6, 2 )) . ", " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesIt;
		return $wgValidSpecialPagesIt;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesIt;
		return $wgSysopSpecialPagesIt;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesIt;
		return $wgDeveloperSpecialPagesIt;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesIt;
		if(array_key_exists($key, $wgAllMessagesIt))
			return $wgAllMessagesIt[$key];
		else
			return Language::getMessage($key);
	}

}

?>
