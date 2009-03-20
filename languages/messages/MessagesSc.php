<?php
/** Sardinian (Sardu)
 *
 * @ingroup Language
 * @file
 *
 * @author Marzedu
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_SPECIAL         => 'Speciale',
	NS_MAIN            => '',
	NS_TALK            => 'Contièndha',
	NS_USER            => 'Utente',
	NS_USER_TALK       => 'Utente_discussioni',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK    => '$1_discussioni',
	NS_FILE            => 'Immàgini',
	NS_FILE_TALK       => 'Immàgini_contièndha'
);

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

$linkTrail = "/^([a-z]+)(.*)\$/sD";

$messages = array(
# User preference toggles
'tog-underline'        => 'Sutalìnia is cullegamentos',
'tog-highlightbroken'  => 'Evidenzia i links che puntano ad articoli ancora da scrivere',
'tog-justify'          => 'Paragrafo: giustificato',
'tog-hideminor'        => 'Nascondi le modifiche minori nella pagina "Modifiche recenti"',
'tog-numberheadings'   => 'Auto-numerazione dei titoli di paragrafo',
'tog-editondblclick'   => "Doppio click per modificare l'articolo (richiede JavaScript)",
'tog-rememberpassword' => 'Ricorda la password (non limitare a una sessione - richiede uso di cookies)',
'tog-editwidth'        => 'Casella di edizione ampliata alla massima larghezza',
'tog-watchdefault'     => 'Notifica articoli nuovi e modificati',
'tog-minordefault'     => 'Indica ogni modifica come minore (solo come predefinito)',

# Dates
'sunday'        => 'Domìniga',
'monday'        => 'Lunis',
'tuesday'       => 'Martis',
'wednesday'     => 'Mércuris',
'thursday'      => 'Giòvia',
'friday'        => 'Chenàbura',
'saturday'      => 'Sàbadu',
'sun'           => 'Dom',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Mèr',
'thu'           => 'Giò',
'fri'           => 'Che',
'sat'           => 'Sàb',
'january'       => 'Ghennàrgiu',
'february'      => 'Freàrgiu',
'march'         => 'Martzu',
'april'         => 'Abrile',
'may_long'      => 'Maju',
'june'          => 'Làmpadas',
'july'          => 'Trìulas',
'august'        => 'Austu',
'september'     => 'Cabudanni',
'october'       => 'Santugaine',
'november'      => 'Santandria',
'december'      => 'Nadale',
'january-gen'   => 'Ghennàrgiu',
'february-gen'  => 'Freàrgiu',
'march-gen'     => 'Martzu',
'april-gen'     => 'Abrile',
'may-gen'       => 'Maju',
'june-gen'      => 'Làmpadas',
'july-gen'      => 'Trìulas',
'august-gen'    => 'Austu',
'september-gen' => 'Cabudanni',
'october-gen'   => 'Santugaine',
'november-gen'  => 'Santandria',
'december-gen'  => 'Nadale',
'jan'           => 'Ghe',
'feb'           => 'Fre',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'Maj',
'jun'           => 'Làm',
'jul'           => 'Trì',
'aug'           => 'Aus',
'sep'           => 'Cab',
'oct'           => 'Stg',
'nov'           => 'Std',
'dec'           => 'Nad',

# Categories related messages
'pagecategories'        => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header'       => 'Pàginas in sa categoria "$1"',
'subcategories'         => 'Subcategorias',
'category-media-header' => 'Mèdius in sa categoria "$1"',
'category-empty'        => "''In custa categoria non bi est peruna pàgina o mèdiu.''",

'about'         => 'A propòsitu de',
'cancel'        => 'Burra',
'qbfind'        => 'Agata',
'qbbrowse'      => 'Nàviga',
'qbedit'        => 'Acontza',
'qbpageoptions' => 'possibbilidadis de sa pàgina',
'qbpageinfo'    => 'Cuntestu de sa pàgina',
'qbmyoptions'   => 'Is preferentzias meas',
'mypage'        => 'Sa pàgina mea',
'mytalk'        => 'Cuntierras meas',

'errorpagetitle'    => 'Faddina',
'returnto'          => 'Torra a $1.',
'tagline'           => 'Dae {{SITENAME}}',
'help'              => 'Agiudu',
'search'            => 'Chirca',
'searchbutton'      => 'Chirca',
'go'                => 'Bae',
'searcharticle'     => 'Bae',
'history'           => 'Istòria de sa pàgina',
'history_short'     => 'Istòria',
'printableversion'  => 'Versione stampabile',
'edit'              => 'Acontza',
'create'            => 'Crea',
'editthispage'      => 'Acontza custa pàgina',
'create-this-page'  => 'Crea custa pàgina',
'delete'            => 'Fùlia',
'deletethispage'    => 'Fùlia custa pàgina',
'protectthispage'   => 'Ampàra custa pàgina',
'unprotect'         => 'Disampàra',
'unprotectthispage' => 'Disampàra custa pàgina',
'newpage'           => 'Pàgina noa',
'talkpage'          => "Cummenta s'artìculu",
'articlepage'       => "Castia s'artìculu",
'talk'              => 'Cuntierras',
'userpage'          => 'Castia sa pàgina usuàriu',
'projectpage'       => 'Castia sa pàgina meta',
'imagepage'         => 'Vedi pagina immagine',
'otherlanguages'    => 'Áteras limbas',
'redirectedfrom'    => '(Redirect dae $1)',
'lastmodifiedat'    => 'Ùrtimu acontzu su $1, a is $2.', # $1 date, $2 time
'viewcount'         => 'Custu artìculu est istetiu lìgiu $1 bortas.',
'protectedpage'     => 'Pàgina amparada',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A propòsitu de {{SITENAME}}',
'currentevents'        => 'Novas',
'edithelp'             => "Agiudu pro s'acontzu o s'iscritura",
'edithelppage'         => "Help:Comente_iscrìere_un'artìculu",
'helppage'             => 'Help:Agiudu',
'mainpage'             => 'Pàgina printzipali',
'mainpage-description' => 'Pàgina printzipali',

'retrievedfrom' => 'Bogau dae  "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => 'Pàgina usuàriu',
'nstab-category' => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Operazione non riconosciuta',
'nosuchactiontext'  => "L'operazione richiesta con la URL immessa non è stata riconosciuta dal software di {{SITENAME}}",
'nosuchspecialpage' => 'Nessuna simile pagina speciale è disponibile',
'nospecialpagetext' => 'Hai richiesto una pagina speciale che non è stata riconosciuta dal software di {{SITENAME}}, o che non è disponibile.',

# General errors
'error'           => 'Errore',
'databaseerror'   => 'Errore del database',
'dberrortext'     => 'Errore di sintassi nella richiesta inoltrata al database.
L\'ultima richiesta inoltrata al database è stata:
<blockquote><tt>$1</tt></blockquote>
dalla funzione "<tt>$2</tt>".
MySQL ha restituito un errore "<tt>$3: $4</tt>".',
'noconnect'       => 'Connessione al database fallita su $1',
'nodb'            => 'Selezione del database $1 fallita',
'readonly'        => 'Accesso al database temporaneamente disabilitato',
'enterlockreason' => 'Fornisci una spiegazione sui motivi del blocco, includendo le probabili data ed ora di riattivazione o di rimozione del blocco.',
'readonlytext'    => "Il database di {{SITENAME}} è al momento bloccato, e non consente nuove immissioni né modifiche, molto probabilmente per manutenzione server, nel qual caso il database sarà presto di nuovo completamente accessibile.
L/'amministratore di sistema che ha imposto il blocco, ha lasciato questa nota:
<p>$1",
'internalerror'   => 'Errore interno',
'filecopyerror'   => 'Non è stato possibile copiare il file "$1" come "$2".',
'filerenameerror' => 'Non è stato possibile rinominare il file "$1" in "$2".',
'filedeleteerror' => 'Non è stato possibile cancellare il file "$1".',
'filenotfound'    => ' Non è stato possibile trovare il file "$1".',
'unexpected'      => 'Valore imprevisto: "$1"="$2".',
'formerror'       => 'Errore: il modulo non è stato inviato correttamente',
'badarticleerror' => 'Questa operazione non è consentita su questa pagina.',
'cannotdelete'    => "Impossibile cancellare la pagina o l'immagine richiesta.",
'badtitle'        => 'Titolo non corretto',
'badtitletext'    => 'La pagina richiesta non è disponibile, potrebbe essere non valida, vuota, o potrebbe trattarsi di un errore in un link interlinguistico o fra diverse versioni di {{SITENAME}}.',

# Login and logout pages
'logouttitle'             => 'Logout Utente',
'logouttext'              => 'Logout effettuato.
Ora puoi continuare ad usare {{SITENAME}} come utente anonimo (ma il tuo indirizzo IP resterà riconoscibile), oppure puoi nuovamente richiedere il login con il precedente username, oppure come uno diverso.',
'welcomecreation'         => '<h2>Benvenuto, $1!</h2><p>Il tuo account è stato creato con successo.<br />Grazie per aver scelto di far crescere {{SITENAME}} con il tuo aiuto.<br />Per rendere {{SITENAME}} più tua, e per usarla più scorrevolmente, non dimenticare di personalizzare le tue preferenze.',
'loginpagetitle'          => 'Login',
'yourname'                => 'Nòmene usuàriu',
'yourpassword'            => 'Pàssword',
'yourpasswordagain'       => 'Arripiti sa pàssword',
'remembermypassword'      => 'Ricorda la mia password per più sessioni (richiede uso dei cookies).',
'nav-login-createaccount' => 'Intra / crea account',
'userlogin'               => 'Intrada',
'createaccount'           => 'Crea account',
'badretype'               => 'Sas password chi as insertau non currenspundint.',
'userexists'              => 'Siamo spiacenti. Lo user name che hai scelto è già usato da un altro Utente. Ti preghiamo perciò di voler scegliere uno user name diverso.',
'youremail'               => 'E-mail:',
'yournick'                => 'Sa firma tua:',
'loginerror'              => 'Login error',
'noname'                  => 'Su nòmene usuàriu insertau non est bonu.',
'loginsuccesstitle'       => 'Ses intrau',
'loginsuccess'            => "'''Como ses intrau in {{SITENAME}} cun nòmene usuàriu \"\$1\".'''",
'nosuchuser'              => 'Attenzione<br /><br />a seguito di verifica, non ci risulta alcun Utente con il nome di  "$1".<br /><br />
Controlla per favore il nome digitato, oppure usa il modulo qui sotto per creare un nuovo user account.',
'wrongpassword'           => 'Sa pàssword insertada non est bona. Prova torra.',
'mailmypassword'          => 'Spediscimi una nuova password in posta elettronica',
'passwordremindertitle'   => 'Servitziu Password Reminder di {{SITENAME}}',
'passwordremindertext'    => 'Qualcuno (probabilmente tu, con indirizzo IP $1)
ha richiesto l\'invio di una nuova password per il login a {{SITENAME}}.
La password per l\'Utente "$2" è ora "$3".
Per evidenti ragioni di sicurezza, dovresti fare un log in il prima possibile, e cambiare la password immediatamente.',
'noemail'                 => 'Peruna e-mail risultada registrada pro s\'usuàriu "$1".',
'passwordsent'            => 'Una password noa est istetia ispedia a s\'indiritzu e-mail de s\'usuàriu "$1".
Pro pregheri, candu d\'arretzis faghe su login.',

# Password reset dialog
'oldpassword' => 'Password betza:',
'newpassword' => 'Password noa:',
'retypenew'   => 'Re-iscrie sa password noa:',

# Edit pages
'summary'         => 'Ogetu:',
'minoredit'       => "Custu est un'acontzu minore:",
'watchthis'       => 'Pone ogru a custu artìculu',
'savearticle'     => 'Sarva sa pàgina',
'preview'         => 'Antiprima',
'showpreview'     => "Castia s'antiprima",
'blockedtitle'    => "S'usuàriu est istetiu bloccau",
'blockedtext'     => "Il tuo User name o il tuo indirizzo IP sono stati bloccati da $1.


La motivazione del blocco è la seguente:

''$2''

Se lo desideri, puoi contattare $1, o uno degli altri [[{{MediaWiki:Grouppage-sysop}}|administrators]] per discutere del blocco.",
'newarticle'      => '(Nou)',
'newarticletext'  => "Custa pagina non esistit ancora.
Pro creare sa pagina, iscrie in su box inoghe in basciu (abàida sa [[{{MediaWiki:Helppage}}|pàgina de agiudu]] pro prus informatziones).
Chi ses intrau inoghe pro isballiu, clicca in su browser tuo su pulsante '''back/indietro'''.",
'noarticletext'   => "(L'articolo è vuoto, potresti gentilmente iniziare l'articolo oppure richiedere la cancellazione di questa pagina)",
'updated'         => '(Agiornau)',
'note'            => "'''Nota:'''",
'previewnote'     => "'''Arregodadia  ca custa est isceti una ANTIPRIMA. Sa versione tua non est istetia ancora allogada!'''",
'previewconflict' => "Custa antiprima rapresentada su testu in s'area acontzu testu de susu comente at a aparire chi da sarvas.",
'editing'         => 'Acontzu de $1',
'editconflict'    => 'Cunflitu de editzione: $1',
'explainconflict' => 'Qualcun altro ha salvato una sua versione dell\'articolo nel tempo in cui tu stavi preparando la tua versione.<br />
La casella di modifica di sopra contiene il testo dell\'articolo nella sua forma attuale (cioè il testo attualmente online). Le tue modifiche sono invece contenute nella casella di modifica inferiore.
Dovrai inserire, se lo desideri, le tue modifiche nel testo esistente, e perciò scriverle nella casella di sopra.
<b>Soltanto</b> il testo nella casella di sopra sarà sakvato se premerai il bottone "Salva".<br />',
'yourtext'        => 'Il tuo testo',
'storedversion'   => 'Versione in archivio',
'editingold'      => "'''ATTENZIONE: Stai modificando una versione dell'articolo non aggiornata.
Se la salvi così, tutti i cambiamenti apportati dopo questa revisione verranno persi per sempre.'''",
'yourdiff'        => 'Differenze',

# History pages
'nohistory'    => 'Cronologia delle versioni di questa pagina non reperibile.',
'currentrev'   => 'Versione attuale',
'revisionasof' => 'Revisione $1',
'cur'          => 'cur',
'next'         => 'succ',
'last'         => 'prev',
'histlegend'   => "Cunfruntu fra versiones: scebera sa casella de sa versione che boles e cracca Invio o su butoni in basciu.

Legenda: '''({{int:cur}})''' = diferentzias cun sa versione currenti, '''({{int:last}})''' = diferentzias cun sa versione de prima, '''{{int:minoreditletter}}''' = acontzu minore",

# Diffs
'difference' => '(Diferèntzias fra revisiones)',
'lineno'     => 'Lìnnia $1:',

# Search results
'searchresults'         => 'Risultato della ricerca',
'searchresulttext'      => 'Pro àteras informatziones pro sa chirca interna de {{SITENAME}}, castia [[{{MediaWiki:Helppage}}|Chirca in {{SITENAME}}]].',
'searchsubtitle'        => 'Richiesta "[[:$1]]"',
'searchsubtitleinvalid' => 'As chircadu "$1"',
'titlematches'          => 'Nei titoli degli articoli',
'notitlematches'        => 'Peruna currispondentzia de is tìtulos de pàgina',
'textmatches'           => 'Nel testo degli articoli',
'notextmatches'         => "Peruna currispondentzia in su testu de s'artìculu",
'prevn'                 => 'cabidianos $1',
'nextn'                 => 'imbenientes $1',
'viewprevnext'          => 'Càstia ($1) ($2) ($3).',
'searchhelp-url'        => 'Help:Aiuto',
'showingresults'        => 'Qui di seguito <b>$1</b> risultati, partendo dal numero #<b>$2</b>.',
'nonefound'             => '<strong>Nota</strong>: la ricerca di parole troppo comuni, come "avere" o "essere", che non sono indicizzate, può causare un esito negativo, così come indicare più di un termine da ricercare (solo le pagine che contengano tutti i termini ricercati verrebbero infatti visualizzate fra i risultati).',
'powersearch'           => 'Chirca delantada',

# Preferences page
'preferences'              => 'Preferenze',
'prefsnologin'             => 'Non hai eseguito il login',
'prefsnologintext'         => 'Devi avere eseguito il [[Special:UserLogin|login]]
per poter personalizzare le tue preferenze.',
'prefsreset'               => 'Le tue Preferenze sono state ripescate dalla memoria di sistema del potente server di {{SITENAME}}.',
'qbsettings'               => 'Settaggio della barra menu',
'qbsettings-none'          => 'Nessuno',
'qbsettings-fixedleft'     => 'Fisso a sinistra',
'qbsettings-fixedright'    => 'Fisso a destra',
'qbsettings-floatingleft'  => 'Fluttuante a sinistra',
'qbsettings-floatingright' => 'Fluttuante a destra',
'changepassword'           => 'Cambia password',
'skin'                     => 'Aspetto',
'saveprefs'                => 'Salva preferenze',
'resetprefs'               => 'Resetta preferenze',
'textboxsize'              => 'Dimensione della casella di edizione',
'rows'                     => 'Righe',
'columns'                  => 'Colonne',
'searchresultshead'        => 'Settaggio delle preferenze per la ricerca',
'resultsperpage'           => 'Risultati da visualizzare per pagina',
'contextlines'             => 'Righe di testo da mostrare per ciascun risultato',
'contextchars'             => 'Caratteri per linea',
'recentchangescount'       => 'Numero di titoli nelle "modifiche recenti"',
'savedprefs'               => 'Le tue preferenze sono state salvate.',
'timezonetext'             => 'Immetti il numero di ore di differenza fra la tua ora locale e la ora del server (UTC).',
'localtime'                => 'Ora Locale',
'timezoneoffset'           => 'Offset',

# User rights
'editinguser' => "Modifica di '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",

# Recent changes
'recentchanges'   => 'Úrtimas mudàntzias',
'rcnote'          => 'Qui di seguito sono elencate le ultime <strong>$1</strong> pagine modificate negli ultimi <strong>$2</strong> giorni.',
'rcnotefrom'      => ' Qui di seguito sono elencate le modifiche da <b>$2</b> (fino a <b>$1</b>).',
'rclistfrom'      => 'Amosta mudaduras dae $1',
'rclinks'         => 'Mostra le ultime $1 modifiche negli ultimi $2 giorni.',
'hist'            => 'istò',
'hide'            => 'Cua',
'show'            => 'Amosta',
'minoreditletter' => 'm',

# Recent changes linked
'recentchangeslinked' => 'Cambiamentos ligados',

# Upload
'reupload'          => 'Ri-upload',
'reuploaddesc'      => 'Torna al modulo per lo upload.',
'uploadnologin'     => 'Devi fare il login per eseguire questa operazione.',
'uploadnologintext' => 'Devi eseguire [[Special:UserLogin|il login]]
per fare lo upload di files.',
'uploaderror'       => 'Errore di Upload',
'uploadtext'        => "'''FERMA!''' Prima di effettuare un upload su {{SITENAME}}, accertati di avere ben letto e soprattutto compreso
le regole di {{SITENAME}} sull'uso delle immagini.

Per visualizzare o cercare immagini precedentemente caricate su {{SITENAME}}, vai alla [[Special:FileList|lista delle immagini già caricate]].
Uploads e cancellazioni delle immagini sono registrati nello
upload log.

Usa il modulo sottostante per caricare nuovi files immagine da utilizzare per arricchire ed illustrare i tuoi articoli.
Sulla maggior parte dei browsers, dovresti vedere un bottone con la scritta \"Browse...\" (oppure \"Sfoglia...\", che aprirà una comune finestra di dialogo.
Scegliendo uno dei files sul tuo PC, il nome di questo file verrà scritto in automatico nella casella di testo a fianco al bottone.
Devi anche selezionare la casellina nella quale affermi che con questo upload non stai violando nessun copyright.
Premi poi il bottone \"Upload\" per completare il caricamento.
Il caricamento può richiedere qualche minuto se hai una connessione ad Internet lenta, o se l'immagine è eccessivamente pesante (sconsigliato).

I formati immagine preferibili sono il JPEG per immagini fotografiche, il PNG
per disegni ed altre immagini iconiche o simboliche, il OGG per i suoni.
Per cortesia, rinomina i tuoi files, prima di caricarli, usando un nome il più possibile descrittivo del contenuto, così da evitare confusioni.
Per inserire la nuova immagine in un articolo, usa semplicemente un link nella forma
'''<nowiki>[[image:file.jpg]]</nowiki>''' o
'''<nowiki>[[image:file.png|alt text, testo alternativo]]</nowiki>''' o
'''<nowiki>[[media:file.ogg]]</nowiki>''' per i suoni.

Tieni presente che, come per tutte le pagine di {{SITENAME}}, chiunque può modificare o sostituire o cancellare i tuoi files ove ritenga che ciò sia negli interessi della nostra enciclopedia. Tieni anche presente che, in caso di abuso, o di sovraccarico sul sistema, potresti essere bloccato (oltre ad essere perseguito per le connesse responsabilità).",
'uploadlogpage'     => 'Carrigadas',
'uploadlogpagetext' => 'Qui di seguito la lista degli ultimi files caricati sul server di {{SITENAME}}.
Tutti i tempi indicati sono calcolati sul fuso orario del server.',
'filename'          => 'Nome del file',
'filedesc'          => 'Oggetto',
'uploadedfiles'     => 'Files Caricati in {{SITENAME}}',
'badfilename'       => 'Il nome del file immagine è stato convertito in "$1".',
'successfulupload'  => 'Caricamento completato',
'uploadwarning'     => 'Avviso di Upload',
'savefile'          => 'Salva file',
'uploadedimage'     => 'carrigadu "[[$1]]"',

# Special:ListFiles
'listfiles' => 'Lista delle immagini',

# File description page
'filehist-user'  => 'Usuariu',
'imagelinks'     => 'Link alle immagini',
'linkstoimage'   => 'Is pàginas chi sighint ligant a custa imàgine:',
'nolinkstoimage' => 'Nessuna pagina linka questa immagine.',

# Random page
'randompage' => 'Una pagina a caso',

# Statistics
'statistics'              => 'Statistiche',
'statistics-header-users' => 'Statistiche del {{SITENAME}}',

'disambiguationspage' => '{{ns:project}}:Links_to_disambiguating_pages',

'doubleredirects'     => 'Doppi Redirects',
'doubleredirectstext' => '<b>Attenzione:</b> Questa lista può talvolta contenere dei risultati non corretti. Ciò potrebbe magari accadere perchè vi sono del testo aggiuntivo o dei link dopo il tag #REDIRECT.<br />
Ogni riga contiene i link al primo ed al secondo redirect, oltre alla prima riga di testo del secondo redirect che di solito contiene il "reale" articolo di destinazione, quello al quale anche il primo redirect dovrebbe puntare.',

'brokenredirects'     => 'Redirects errati',
'brokenredirectstext' => 'I seguenti redirects puntano ad articoli non ancora creati.',

# Miscellaneous special pages
'nbytes'           => '$1 bytes',
'nlinks'           => '$1 links',
'nviews'           => '$1 visite',
'lonelypages'      => 'Pagine solitarie',
'unusedimages'     => 'Immagini non utilizzate',
'popularpages'     => 'Pagine più viste',
'wantedpages'      => 'Articoli più richiesti',
'shortpages'       => 'Pagine corte',
'longpages'        => 'Pagine lunghe',
'listusers'        => 'Elenco degli Utenti',
'newpages'         => 'Pàginas noas',
'movethispage'     => 'Sposta questa pagina',
'unusedimagestext' => '<p>Nota che altri siti web, come la {{SITENAME}} internazionale, potrebbero aver messo un link ad una immagine per mezzo di una URL diretta, perciò le immagini potrebbero essere listate qui anche essendo magari in uso.',
'notargettitle'    => 'Dati mancanti',
'notargettext'     => "Non hai specificato una pagina o un Utente in relazione al quale eseguire l'operazione richiesta.",

# Special:AllPages
'allpages' => 'Totu is pàginas',

# E-mail user
'mailnologintext' => 'Devi fare il [[Special:UserLogin|login]]
ed aver registrato una valida casella e-mail nelle tue [[Special:Preferences|preferenze]] per mandare posta elettronica ad altri Utenti.',
'emailuser'       => "Ispedi un'e-mail a custu usuàriu",
'emailpagetext'   => 'Se questo Utente ha registrato una valida casella e-mail, il modulo qui sotto ti consentirà di scrivergli un solo messaggio.
La e-mail che hai indicato nelle tue preferenze apparirà nel campo "From" della mail, così che il destinatario possa, solo se lo desidera però, risponderti.',
'noemailtitle'    => 'Nessun indirizzo e-mail',
'noemailtext'     => 'Questo Utente non ha registrato alcuna casella e-mail,
oppure ha scelto di non ricevere  posta elettronica dagli altri Utenti.',
'emailsent'       => 'E-mail inviata',
'emailsenttext'   => 'La tua e-mail è stata inviata.',

# Watchlist
'watchlist'        => 'Sa watchlist mea',
'mywatchlist'      => 'Sa watchlist mea',
'nowatchlist'      => "Non hai indicato articoli da tenere d'occhio.",
'watchnologin'     => 'Manca il login',
'watchnologintext' => 'Devi prima fare il [[Special:UserLogin|login]]
per modificare la tua lista di osservati speciali.',
'addedwatch'       => 'Aggiunto agli Osservati Speciali',
'addedwatchtext'   => ' La pagina  "<nowiki>$1</nowiki>" è stata aggiunta alla tua [[Special:Watchlist|lista di osservati speciali]].
Le future modifiche a questa pagina ed alla relativa pagina di discussione saranno elencate qui, e la pagina apparirà in <b>grassetto</b> nella pagina delle [[Special:RecentChanges|modifiche recenti]] per essere più facile da tener d\'occhio.

Se in seguito vorrai togliere questo articolo dalla tua lista di osservati speciali, clicca " Smetti di seguire " nella barra dei menu.',
'removedwatch'     => 'Rimosso dalla lista degli Osservati Speciali',
'removedwatchtext' => 'La pagina  "<nowiki>$1</nowiki>" è stata rimossa dalla lista degli Osservati Speciali.',
'watchthispage'    => 'Segui questo articolo',
'unwatchthispage'  => 'Smetti di seguire',
'notanarticle'     => 'Non è un articolo',

# Delete
'deletepage'        => 'Cancella pagina',
'confirm'           => 'Conferma',
'confirmdeletetext' => 'Stai per cancellare permanentemente dal database una pagina o una immagine, insieme a tutta la sua cronologia.
Per cortesia, conferma che è tua intenzione procedere a tale cancellazione, conferma che hai piena consapevolezza delle conseguenze della tua azione, e conferma che la tua azione è pienamente ottemperante alle regole stabilite nella
[[{{MediaWiki:Policy-url}}]].',
'actioncomplete'    => 'Atzioni cumpletada',
'deletedtext'       => 'La pagina "<nowiki>$1</nowiki>" è stata cancellata.
Vedi $2 per un elenco delle pagine cancellate di recente.',
'deletedarticle'    => 'fuliada "[[$1]]"',
'dellogpage'        => 'Burraduras',
'dellogpagetext'    => 'Qui di seguito, un elenco delle pagine cancellate di recente.
Tutti i tempi sono in ora del server.',
'reverted'          => 'Ripristinata versione precedente',
'deletecomment'     => 'Motivazione della cancellazione',

# Rollback
'rollback'     => 'Usa una revisione precdente',
'cantrollback' => "Impossibile tornare ad una versione precedente: l'ultima modifica è stata apportata dall'unico utente che abbia lavorato a questo articolo.",
'revertpage'   => 'Riportata alla revisione precedente da [[User:$1|$1]]', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from

# Undelete
'undelete'          => 'Recupera una pagina cancellata',
'undeletepage'      => 'Vedi e recupera pagine cancellate',
'undeletepagetext'  => "Le pagine qui di seguito indicate sono state cancellate, ma sono ancora in archivio e pertanto possono essere recuperate. L'archivio viene svuotato periodicamente.",
'undeleterevisions' => '$1 revisioni in archivio',
'undeletehistory'   => 'Se recuperi questo articolo, tutte le sue revisioni verranno recuperate nella relativa cronologia.
Se una nuova pagina è stata creata con questo stesso nome dopo la cancellazione, le revisioni recuperate saranno inserite nella cronologia e la versione attualmente online della pagina non verrà modificata.',
'undeletebtn'       => 'RIPRISTINA!',
'undeletedarticle'  => 'Recuperadu "$1"',

# Contributions
'contributions' => "Contributziones de s'usuàriu",
'contribsub2'   => 'Pro $1 ($2)',
'nocontribs'    => 'Nessuna modifica trovata conformemente a questi criteri.', # Optional parameter: $1 is the user name
'uctop'         => '(ùrtimu de sa pàgina)',

# What links here
'whatlinkshere' => 'Pàginas che ligant a custa',
'linkshere'     => 'Le seguenti pagine contengono link che puntano qui:',
'nolinkshere'   => 'Nessuna pagina contiene links che puntano a questa.',
'isredirect'    => 'redirect',

# Block/unblock
'blockip'            => 'Blocca indirizzo IP',
'blockiptext'        => "Usa il modulo sottostante per bloccare l'accesso con diritto di scrittura da uno specifico indirizzo IP. Questo blocco deve essere operato SOLO per prevenire atti di vandalismo, ed in stretta osservanza dei principi tutti della [[{{MediaWiki:Policy-url}}|policy di {{SITENAME}}]]. Il blocco non può in nessun caso essere applicato per motivi ideologici.
Scrivi un motivo specifico per il quale questo indirizzo IP dovrebbe a tuo avviso essere bloccato (per esempio, cita i titoli di pagine eventualmente già oggetto di vandalismo editoriale).",
'ipaddress'          => 'Indirizzo IP (IP Address)',
'ipbreason'          => 'Motivazione',
'ipbsubmit'          => 'Blocca questo indirizzo IP',
'badipaddress'       => "L'indirizzo IP indicato non è corretto.",
'blockipsuccesssub'  => 'Blocco eseguito',
'blockipsuccesstext' => ' L\'indirizzo IP "$1" è stato bloccato.
<br />Vedi [[Special:IPBlockList|lista IP bloccati]].',
'unblockip'          => ' Sblocca indirizzo IP',
'unblockiptext'      => 'Usa il modulo sottostante per restituire il diritto di scrittura ad un indirizzo IP precedentemente bloccato.',
'ipusubmit'          => 'Sblocca questo indirizzo IP',
'ipblocklist'        => 'Lista degli indirizzi IP bloccati',
'blocklistline'      => '$1, $2 ha bloccato $3 ($4)',
'blocklink'          => 'blocca',
'unblocklink'        => 'sblocca',
'contribslink'       => 'contributziones',

# Developer tools
'lockdb'              => 'Blocca il database',
'unlockdb'            => 'Sblocca il database',
'lockdbtext'          => 'Bloccare il database sospenderà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere non consentirà a nessuno di eseguire operazioni che richiedano modifiche del database.<br /><br />
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare e, soprattutto, che il prima possibile sbloccherai nuovamente il database, ripristinandone la corretta funzionalità, non appena avrai terminato le tue manutenzioni.',
'unlockdbtext'        => 'Sbloccare il database ripristinerà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere di eseguire operazioni che richiedano modifiche del database.
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare.',
'lockconfirm'         => 'Sì, effettivamente intendo, sotto la mia responsabilità, bloccare il database.',
'unlockconfirm'       => ' Sì, effettivamente intendo, sotto la mia responsabilità, sbloccare il database.',
'lockbtn'             => 'Blocca il database',
'unlockbtn'           => 'Sblocca il database',
'locknoconfirm'       => 'Non hai spuntato la casellina di conferma.',
'lockdbsuccesssub'    => 'Blocco del database eseguito',
'unlockdbsuccesssub'  => 'Sblocco del database eseguito, rimosso blocco',
'lockdbsuccesstext'   => 'Il database di {{SITENAME}} è stato bloccato.
<br />Ricordati di rimuovere il blocco non appena avrai terminatoi le tue manutenzioni.',
'unlockdbsuccesstext' => ' Il database di {{SITENAME}} è stato sbloccato.',

# Move page
'move-page-legend' => 'Spostamento di pagina',
'movepagetext'     => "Con il modulo sottostante puoi rinominare una pagina, spostando anche tutta la sua cronologia al nuovo nome.
Il vecchior titolo diverrà automaticamente un redirect che punta al nuovo titolo.
I link alla vecchia pagina non saranno aggiornati (e punteranno quindi al redirect);
accertati di controllare con cura che non si creino doppi redirects o redirects interrotti.
Resta nella tua responsabilità di accertarti che i link continuino a puntare verso dove devono dirigersi.

Nota bene: la pagina '''non''' sarà spostata se vi fosse già un articolo con il nuovo nome, a meno che non sia una pagina vuota o un redirect e che non abbia cronologia.
Questo significa che, se commetti un errore, puoi nuovamente rinominare una pagina col vecchio titolo, ma non puoi sovrascrivere una pagina già esistente.

'''ATTENZIONE!'''
Questo cambiamento drastico potrebbe creare inattesi contrattempi, specialmente se si tratta di una pagina molto visitata. Accertati di aver ben valutato le conseguenze dello spostamento, prima di procedere. Nel dubbio, contatta un Amministratore.",
'movepagetalktext' => "La corrispondente pagina di discussione, se esiste, sarà spostata automaticamente insieme all'articolo, '''tranne che nei seguenti casi:'''
*Spostamento della pagina fra i namespaces,
*Una pagina di discussione (non vuota) già esiste per il nuovo nome, oppure
*Hai deselezionato la casellina qui sotto.

In questi casi, se lo ritieni opportuno, dovrai spostare o aggiungere manualmente la pagina di discussione.",
'movearticle'      => 'Rinomina articolo',
'movenologin'      => 'Non hai effettuato il login',
'movenologintext'  => 'Devi essere un Utente registrato ed aver effettuato il [[Special:UserLogin|login]]
per poter spostare una pagina.',
'newtitle'         => 'Al nuovo titolo di',
'movepagebtn'      => 'Sposta questa pagina',
'pagemovedsub'     => 'Spostamento effettuato con successo',
'articleexists'    => "Una pagina con questo nome esiste già, oppure il nome che hai scelto non è valido.<br />
Scegli, per cortesia, un titolo diverso per l'articolo.",
'talkexists'       => "La pagina è stata spostata correttamente, ma la pagina di dicussione non poteva essere spostata perché ne esiste già un'altra con il nuovo titolo. Per favore, modifica manualmente i contenuti delle due pagine discussione, così da mantenerle entrambe per non perdere potenzialmente interessanti riflessioni.",
'movedto'          => 'spostata a',
'movetalk'         => 'Sposta anche la corrispondente pagina "discussione", se possibile.',

# Special:NewFiles
'imagelisttext' => 'Qui di seguito una lista di $1 immagini, ordinate per $2.',
'ilsubmit'      => 'Cerca',
'bydate'        => 'data',

# 'all' in various places, this might be different for inflected languages
'monthsall' => 'totu',

# Special:SpecialPages
'specialpages' => 'Pàginas ispetziales',

);
