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
	NS_IMAGE           => 'Immàgini',
	NS_IMAGE_TALK      => 'Immàgini_contièndha'
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
'tog-highlightbroken'  => 'Evidèntzia <a href="" class="new">aici</a> is cullegamentos a pàginas inesistentes (si disativau: aici<a href="" class="internal">?</a>).',
'tog-justify'          => 'Alliniamentu paragrafos gistificados',
'tog-hideminor'        => 'Cua is acontzos minores in sa pàgina de is ùrtimas mudàntzias',
'tog-numberheadings'   => 'Auto-numeratzioni de is tìtulos',
'tog-editondblclick'   => "Acontza pàginas cun d'unu dopiu click (esigit JavaScript)",
'tog-rememberpassword' => 'Ammenta sa password in custa computera',
'tog-editwidth'        => 'Casella di edizione ampliata alla massima larghezza',
'tog-watchdefault'     => 'Notifica articoli nuovi e modificati',
'tog-minordefault'     => 'Signa totu is acontzos comente minores pro difetu',

'underline-always' => 'Semper',
'underline-never'  => 'Mai',

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

# Bits of text used by many pages
'categories'            => 'Categorias',
'pagecategories'        => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header'       => 'Pàginas in sa categoria "$1"',
'subcategories'         => 'Subcategorias',
'category-media-header' => 'Mèdius in sa categoria "$1"',
'category-empty'        => "''In custa categoria non bi est peruna pàgina o mèdiu.''",

'linkprefix' => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',

'about'          => 'A propòsitu de',
'article'        => 'Artìculu',
'newwindow'      => "(aberit in d'una fentana noba)",
'cancel'         => 'Burra',
'qbfind'         => 'Agata',
'qbbrowse'       => 'Nàviga',
'qbedit'         => 'Acontza',
'qbpageoptions'  => 'possibbilidadis de sa pàgina',
'qbpageinfo'     => 'Cuntestu de sa pàgina',
'qbmyoptions'    => 'Is preferentzias meas',
'qbspecialpages' => 'Pàginas ispetziales',
'mypage'         => 'Sa pàgina mea',
'mytalk'         => 'Cuntierras meas',
'navigation'     => 'Navigatzioni',

# Metadata in edit box
'metadata_help' => 'Metadatos:',

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
'printableversion'  => 'Versione de imprentai',
'permalink'         => 'Acapiu permanenti',
'print'             => 'Imprenta',
'edit'              => 'Acontza',
'editthispage'      => 'Acontza custa pàgina',
'delete'            => 'Fùlia',
'deletethispage'    => 'Fùlia custa pàgina',
'protect'           => 'Ampara',
'protect_change'    => 'mudàntzia',
'protectthispage'   => 'Ampàra custa pàgina',
'unprotect'         => 'Disampàra',
'unprotectthispage' => 'Disampàra custa pàgina',
'newpage'           => 'Pàgina noa',
'talkpage'          => "Cummenta s'artìculu",
'talkpagelinktext'  => 'Cuntierras',
'specialpage'       => 'Pàgina Ispetziale',
'personaltools'     => 'Istrumentos personales',
'articlepage'       => "Castia s'artìculu",
'talk'              => 'Cuntierras',
'views'             => 'Bisuras',
'toolbox'           => 'Istrumentos',
'userpage'          => 'Castia sa pàgina usuàriu',
'projectpage'       => 'Castia sa pàgina meta',
'imagepage'         => 'Castia sa pàgina de su file',
'categorypage'      => 'Càstia sa categoria',
'otherlanguages'    => 'Áteras limbas',
'redirectedfrom'    => '(Redirect dae $1)',
'redirectpagesub'   => 'Pàgina de reindiritzamentu',
'lastmodifiedat'    => 'Ùrtimu acontzu su $1, a is $2.', # $1 date, $2 time
'viewcount'         => 'Custu artìculu est istadu lìgiu {{PLURAL:$1|borta|$1 bortas}}.',
'protectedpage'     => 'Pàgina amparada',
'jumpto'            => 'Bae a:',
'jumptonavigation'  => 'navigatzioni',
'jumptosearch'      => 'chirca',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'A propòsitu de {{SITENAME}}',
'aboutpage'         => 'Project:Informatziones',
'bugreports'        => 'Segnalazioni di malfunzionamento',
'bugreportspage'    => 'Project:Malfunzionamenti',
'copyright'         => 'Cuntènnidu a suta lissèntzia $1.',
'copyrightpagename' => 'Copyright de {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Copyrights',
'currentevents'     => 'Novas',
'disclaimers'       => 'Abbertimentos',
'disclaimerpage'    => 'Project:Abbertimentos generales',
'edithelp'          => "Agiudu pro s'acontzu o s'iscritura",
'edithelppage'      => 'Help:Acontzare',
'helppage'          => 'Help:Agiudu',
'mainpage'          => 'Pàgina printzipali',
'privacy'           => 'Polìtiga pro is datos brivados',
'privacypage'       => 'Project:Polìtiga pro is datos brivados',

'pagetitle'            => '$1 - {{SITENAME}}',
'retrievedfrom'        => 'Bogau dae  "$1"',
'youhavenewmessages'   => 'Tenes $1 ($2).',
'newmessageslink'      => 'messàgios nous',
'newmessagesdifflink'  => 'ùrtima mudàntzia',
'editsection'          => 'acontza',
'editsection-brackets' => '[$1]',
'editold'              => 'acontza',
'editsectionhint'      => 'Acontza sa setzioni: $1',
'toc'                  => 'Cuntènnidus',
'showtoc'              => 'amosta',
'hidetoc'              => 'cua',
'viewdeleted'          => 'Bisi $1?',
'feedlinks'            => 'Feed:',
'site-rss-feed'        => 'Feed Atom de $1',
'site-atom-feed'       => 'Feed Atom de $1',
'page-rss-feed'        => 'Feed RSS pro "$1"',
'page-atom-feed'       => 'Feed Atom pro "$1"',
'feed-atom'            => 'Atom',
'feed-rss'             => 'RSS',
'red-link-title'       => '$1 (sa pàgina non esistit)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Pàgina',
'nstab-user'     => 'Pàgina usuàriu',
'nstab-special'  => 'Pàgina ispetziale',
'nstab-project'  => 'Pàgina de servìtziu',
'nstab-image'    => 'File',
'nstab-template' => 'Template',
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
'missingarticle'  => 'Il database non ha trovato il testo di una pagina, che invece avrebbe dovuto trovare, intitolata "$1".
Questo non è un errore del database, ma più probabilmente un problema del software.
Per favore, segnalate l\'accaduto ad un administrator, segnalando la URL e l\'ora dell\'incidente.',
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
'badtitletext'    => "Su tìtulu de sa pagina c'as pediu est bùidu, isbaliau, o iscritu ne is cullegamentus inter-wiki in modu non curregiu o cun carateres non ammitius.",
'perfdisabled'    => 'Siamo davvero rammaricati, ma questa funzionalità è temporaneamente disabilitata durante le ore di maggiore accesso al database per ragioni di accessibilità al resto del sito! Torna fra le 02:00 e le 14:00 UTC e riprova. Grazie.',
'viewsource'      => 'Castia mitza',
'viewsourcefor'   => 'pro $1',

# Login and logout pages
'logouttitle'           => 'Bessida usuàriu',
'logouttext'            => 'Logout effettuato.
Ora puoi continuare ad usare {{SITENAME}} come utente anonimo (ma il tuo indirizzo IP resterà riconoscibile), oppure puoi nuovamente richiedere il login con il precedente username, oppure come uno diverso.',
'welcomecreation'       => '<h2>Benvenuto, $1!</h2><p>Il tuo account è stato creato con successo.<br />Grazie per aver scelto di far crescere {{SITENAME}} con il tuo aiuto.<br />Per rendere {{SITENAME}} più tua, e per usarla più scorrevolmente, non dimenticare di personalizzare le tue preferenze.',
'loginpagetitle'        => 'Login',
'yourname'              => 'Nòmene usuàriu',
'yourpassword'          => 'Pàssword',
'yourpasswordagain'     => 'Arripiti sa pàssword',
'remembermypassword'    => 'Ammenta sa pàssword in custu computer',
'loginproblem'          => '<b>Si è verificato un errore durante il tuo tentativo di login.</b><br />Riprova, sarai più fortunato!',
'login'                 => 'Intra',
'userlogin'             => 'Intra / crea account',
'logout'                => 'Serra sessione',
'userlogout'            => 'Bessida',
'nologinlink'           => 'Crea unu account',
'createaccount'         => 'Crea account',
'gotaccountlink'        => 'Intra',
'badretype'             => 'Sas passwords chi as insertau non currenspundint.',
'userexists'            => 'Siamo spiacenti. Lo user name che hai scelto è già usato da un altro Utente. Ti preghiamo perciò di voler scegliere uno user name diverso.',
'youremail'             => 'E-mail:',
'yourrealname'          => 'Nòmene beru:',
'yourlanguage'          => 'Limba:',
'yournick'              => 'Sa firma tua:',
'email'                 => 'E-mail',
'loginerror'            => 'Login error',
'noname'                => 'Su nòmene usuàriu insertau non est bonu.',
'loginsuccesstitle'     => 'Ses intrau',
'loginsuccess'          => "'''Como ses intrau in {{SITENAME}} cun nòmene usuàriu \"\$1\".'''",
'nosuchuser'            => 'Attenzione<br /><br />a seguito di verifica, non ci risulta alcun Utente con il nome di  "$1".<br /><br />
Controlla per favore il nome digitato, oppure usa il modulo qui sotto per creare un nuovo user account.',
'wrongpassword'         => 'Sa pàssword insertada non est bona. Prova torra.',
'mailmypassword'        => "Ispedi una password noa a s'indiritzu e-mail miu",
'passwordremindertitle' => 'Servitziu Password Reminder di {{SITENAME}}',
'passwordremindertext'  => 'Qualcuno (probabilmente tu, con indirizzo IP $1)
ha richiesto l\'invio di una nuova password per il login a {{SITENAME}}.
La password per l\'Utente "$2" è ora "$3".
Per evidenti ragioni di sicurezza, dovresti fare un log in il prima possibile, e cambiare la password immediatamente.',
'noemail'               => 'Peruna e-mail risultada registrada pro s\'usuàriu "$1".',
'passwordsent'          => 'Una password noa est istada ispedia a s\'indiritzu e-mail de s\'usuàriu "$1".
Pro pregheri, candu d\'arretzis faghe su login.',
'emailconfirmlink'      => "Cunfirma s'indiritzu e-mail tuo",
'loginlanguagelabel'    => 'Limba: $1',

# Edit page toolbar
'bold_sample'     => 'Grassetu',
'bold_tip'        => 'Grassettu',
'italic_sample'   => 'Corsivu',
'italic_tip'      => 'Corsivu',
'link_sample'     => 'Tìtulu cullegamentu',
'link_tip'        => 'Cullegamentu internu',
'extlink_sample'  => 'http://www.esèmpiu.com tìtulu de sa liga',
'extlink_tip'     => 'Ligadura de foras (ammenta su prefissu http://)',
'headline_sample' => 'Testu de su tìtulu',
'headline_tip'    => 'Tìtulu de secundu livellu',
'math_sample'     => 'Inserta sa fòrmula innoi',
'math_tip'        => 'Formula matematica (LaTeX)',
'image_sample'    => 'Example.jpg',
'media_sample'    => 'Example.ogg',
'media_tip'       => 'Cullegamentu a su file',
'sig_tip'         => 'Firma cun data e ora',
'hr_tip'          => 'Lìnnia orizontale (de usai cun critèriu)',

# Edit pages
'summary'                => 'Oggetto',
'minoredit'              => "Custu est un'acontzu minore:",
'watchthis'              => 'Pone ogru a custu artìculu',
'savearticle'            => 'Sarva pàgina',
'preview'                => 'Antiprima',
'showpreview'            => "Amosta s'antiprima",
'showdiff'               => 'Amosta mudàntzias',
'blockedtitle'           => "S'usuàriu est istadu bloccau",
'blockedtext'            => "<big>'''Custu nòmene usuàriu o indiritzu IP est istadu bloccau.'''</big>

Su bloccu est istadu postu dae $1. Su motivu de su bloccu est: ''$2''

* Su bloccu incumentzat: $8
* Su bloccu iscadit: $6
* Intervallu de bloccu: $7

Chi boles, podes cuntatare $1 o un àteru [[{{MediaWiki:Grouppage-sysop}}|aministradori]] pro faeddare de su bloccu.

Nota ca sa funtzioni 'Ispedi un'e-mail a custu usuàriu' non est ativa chi non est istadu registrau un indiritzu e-mail validu ne is [[Special:Preferences|preferentzias]] tuas o chi s'usu de custa funtzioni est istadu bloccau.

S'indiritzu IP atuale est $3, su numeru ID de su bloccu est #$5.
Pro pregheri ispetzifica totu is detaglios innanti in carchi siat pedimentu de chiarimentu.",
'accmailtitle'           => 'Password ispedia.',
'newarticle'             => '(Nou)',
'newarticletext'         => "Custa pagina non esistit ancora.
Pro creare sa pagina, iscrie in su box inoghe in basciu (abàida sa [[{{MediaWiki:Helppage}}|pàgina de agiudu]] pro prus informatziones).
Chi ses intrau inoghe pro isballiu, clicca in su browser tuo su pulsante '''back/indietro'''.",
'noarticletext'          => 'In custu momentu sa pàgina est bùida.
Podes [[Special:Search/{{PAGENAME}}|chircare custu tìtulu]] in àteras pàginas, <span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} chircare ne is registros ligados] oppuru [{{fullurl:{{FULLPAGENAME}}|action=edit}} acontzare sa pàgina]</span>.',
'updated'                => '(Agiornau)',
'note'                   => "'''Nota:'''",
'previewnote'            => "'''Arregodadia  ca custa est isceti una ANTIPRIMA. Sa versione tua non est istada ancora allogada!'''",
'previewconflict'        => "Custa antiprima rapresentada su testu in s'area acontzu testu de susu comente at a aparire chi da sarvas.",
'editing'                => 'Acontzu de $1',
'editinguser'            => "Modifica di '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'editingsection'         => 'Acontzendi $1 (setzioni)',
'editingcomment'         => 'Acontzu de $1 (setzione noa)',
'editconflict'           => 'Cunflitu de editzione: $1',
'explainconflict'        => 'Qualcun altro ha salvato una sua versione dell\'articolo nel tempo in cui tu stavi preparando la tua versione.<br />
La casella di modifica di sopra contiene il testo dell\'articolo nella sua forma attuale (cioè il testo attualmente online). Le tue modifiche sono invece contenute nella casella di modifica inferiore.
Dovrai inserire, se lo desideri, le tue modifiche nel testo esistente, e perciò scriverle nella casella di sopra.
<b>Soltanto</b> il testo nella casella di sopra sarà sakvato se premerai il bottone "Salva".<br />',
'yourtext'               => 'Il tuo testo',
'storedversion'          => 'Versione in archivio',
'editingold'             => "'''ATTENZIONE: Stai modificando una versione dell'articolo non aggiornata.
Se la salvi così, tutti i cambiamenti apportati dopo questa revisione verranno persi per sempre.'''",
'yourdiff'               => 'Differenze',
'templatesused'          => 'Templates impreaus in custa pàgina:',
'templatesusedpreview'   => 'Templates impreadus in custa antiprima:',
'templatesusedsection'   => 'Templates impreaus in custa setzione:',
'template-protected'     => '(amparau)',
'template-semiprotected' => '(semi-amparau)',

# History pages
'viewpagelogs'        => 'Castia sos registros de custa pàgina',
'nohistory'           => 'Cronologia delle versioni di questa pagina non reperibile.',
'revnotfound'         => 'Versione non trovata',
'revnotfoundtext'     => 'La versione precedente di questo articolo che hai richiesto, non è stata trovata.
Controlla per favore la URL che hai usato per accedere a questa pagina.',
'loadhist'            => 'Caricamento cronologia di questa pagina',
'currentrev'          => 'Versione attuale',
'revisionasof'        => 'Arrevisioni de is $1',
'previousrevision'    => '← Acontzu in antis',
'nextrevision'        => 'Acontzu in fatu →',
'currentrevisionlink' => 'Arrevisioni currenti',
'cur'                 => 'curr',
'next'                => 'in fatu',
'last'                => 'ant',
'page_first'          => 'prima',
'page_last'           => 'ùrtima',
'histlegend'          => "Cunfruntu fra versiones: scebera sa casella de sa versione che boles e cracca Invio o su butoni in basciu.

Legenda: '''({{int:cur}})''' = diferentzias cun sa versioni currenti, '''({{int:last}})''' = diferentzias cun sa versioni in antis, '''{{int:minoreditletter}}''' = acontzu minore",
'deletedrev'          => '[fuliada]',
'histfirst'           => 'Prima',
'histlast'            => 'Úrtima',

# Revision feed
'history-feed-item-nocomment' => '$1 su $2', # user at time

# Revision deletion
'rev-delundel' => 'mosta/cua',

# Diffs
'history-title'           => 'Istòria de is arrevisionis de "$1"',
'difference'              => '(Diferèntzias fra revisiones)',
'lineno'                  => 'Lìnnia $1:',
'compareselectedversions' => 'Cumpara versionis scioberadas',
'editundo'                => 'annudda',

# Search results
'searchresults'         => 'Resultau de sa chirca',
'searchresulttext'      => 'Pro àteras informatziones pro sa chirca interna a {{SITENAME}}, càstia [[{{MediaWiki:Helppage}}|Chirca in {{SITENAME}}]].',
'searchsubtitle'        => 'Richiesta "[[:$1]]"',
'searchsubtitleinvalid' => 'As chircadu "$1"',
'titlematches'          => 'Nei titoli degli articoli',
'notitlematches'        => 'Peruna currispondentzia de is tìtulos de pàgina',
'textmatches'           => 'Nel testo degli articoli',
'notextmatches'         => "Peruna currispondentzia in su testu de s'artìculu",
'prevn'                 => 'cabidianos $1',
'nextn'                 => 'imbenientes $1',
'viewprevnext'          => 'Càstia ($1) ($2) ($3).',
'showingresults'        => "Innoe sighendi {{PLURAL:$1|benit amostau '''1''' risultadu|benint amostaus '''$1''' risultados}} a incumentzai dae su numeru '''$2'''.",
'nonefound'             => '<strong>Nota</strong>: la ricerca di parole troppo comuni, come "avere" o "essere", che non sono indicizzate, può causare un esito negativo, così come indicare più di un termine da ricercare (solo le pagine che contengano tutti i termini ricercati verrebbero infatti visualizzate fra i risultati).',
'powersearch'           => 'Chirca delantada',
'powersearchtext'       => '
Cerca fra i campi :<br />
$1<br />
$2 Elenca i redirects &nbsp; cerca per $3 $9',

# Preferences page
'preferences'              => 'Preferentzias',
'mypreferences'            => 'Preferentzias meas',
'prefsnologin'             => 'Non hai eseguito il login',
'prefsnologintext'         => 'Depis èssere <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} intrau]</span> pro scioberai is preferentzias.',
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
'oldpassword'              => 'Password betza:',
'newpassword'              => 'Password noa:',
'retypenew'                => 'Re-iscrie sa password noa:',
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

# Groups
'group-autoconfirmed' => 'Usuàrios autocunfirmadus',
'group-bot'           => 'Bots',
'group-sysop'         => 'Aministratoris',
'group-bureaucrat'    => 'Burocrates',
'group-all'           => '(totus)',

'group-autoconfirmed-member' => 'Autocunfirmados usuàrios',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Aministradore',
'group-bureaucrat-member'    => 'Burocrate',

'grouppage-autoconfirmed' => '{{ns:project}}:Usuàrios autocunfirmadus',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Aministradores',
'grouppage-bureaucrat'    => '{{ns:project}}:Burocrates',

# User rights log
'rightslog' => 'Deretos de is usuàrios',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|mudàntzia|mudàntzias}}',
'recentchanges'                  => 'Úrtimas mudàntzias',
'recentchanges-feed-description' => 'Custu feed riportada is ùrtimas mudàntzias a is cuntènnidos de su giassu.',
'rcnote'                         => 'Qui di seguito sono elencate le ultime <strong>$1</strong> pagine modificate negli ultimi <strong>$2</strong> giorni.',
'rcnotefrom'                     => "Sighendi dui sunt amostadas is mudàntzias dae '''$2''' (fintzas a '''$1''').",
'rclistfrom'                     => 'Amosta mudàntzias dae $1',
'rcshowhideminor'                => '$1 acontzos minores',
'rcshowhidebots'                 => '$1 bots',
'rcshowhideliu'                  => '$1 usuàrios intraus',
'rcshowhideanons'                => '$1 usuàrios anònimus',
'rcshowhidemine'                 => '$1 is acontzos meos',
'rclinks'                        => 'Amosta is $1 ùrtimas mudàntzias fatas ne is ùrtimas $2 dies<br />$3',
'diff'                           => 'dif',
'hist'                           => 'istò',
'hide'                           => 'Cua',
'show'                           => 'Amosta',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'sectionlink'                    => '→',
'rc-change-size'                 => '$1',

# Recent changes linked
'recentchangeslinked'       => 'Mudàntzias ligadas',
'recentchangeslinked-title' => 'Mudàntzias ligadas a "$1"',

# Upload
'upload'            => 'Carriga file',
'reupload'          => 'Torra a carrigai',
'reuploaddesc'      => 'Torna al modulo per lo upload.',
'uploadnologin'     => 'Devi fare il login per eseguire questa operazione.',
'uploadnologintext' => 'Devi eseguire [[Special:UserLogin|il login]]
per fare lo upload di files.',
'uploaderror'       => 'Faddina de carrigamentu',
'uploadtext'        => "'''FERMA!''' Prima di effettuare un upload su {{SITENAME}}, accertati di avere ben letto e soprattutto compreso
le regole di {{SITENAME}} sull'uso delle immagini.

Per visualizzare o cercare immagini precedentemente caricate su {{SITENAME}}, vai alla [[Special:ImageList|lista delle immagini già caricate]].
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
'filename'          => 'Nòmene file',
'filedesc'          => 'Ogetu',
'uploadedfiles'     => 'Files carrigaus',
'badfilename'       => 'Il nome del file immagine è stato convertito in "$1".',
'successfulupload'  => 'Caricamento completato',
'uploadwarning'     => 'Avviso di Upload',
'savefile'          => 'Sarva file',
'uploadedimage'     => 'carrigadu "[[$1]]"',

# Image list
'imagelist'                 => 'Lista delle immagini',
'imagelisttext'             => "Innoe sighendi du est una lista de '''$1''' {{PLURAL:$1|file|files}} ordinada $2.",
'getimagelist'              => 'ricerca nella lista delle immagini',
'ilsubmit'                  => 'Cerca',
'showlast'                  => 'Mostra le ultime $1 immagini ordinate per $2.',
'byname'                    => 'nome',
'bydate'                    => 'data',
'bysize'                    => 'peso',
'imgdelete'                 => 'canc',
'filehist'                  => 'Istòria de su file',
'filehist-current'          => 'currenti',
'filehist-datetime'         => 'Data/Ora',
'filehist-user'             => 'Usuàriu',
'filehist-dimensions'       => 'Dimensiones',
'filehist-comment'          => 'Cummentu',
'imagelinks'                => 'Ligant a custu file',
'linkstoimage'              => 'Le pagine seguenti linkano questa immagine:',
'nolinkstoimage'            => 'Peruna pàgina ligat cun custu file.',
'shareduploadwiki-linktext' => 'Pàgina che descriet su file',
'uploadnewversion-linktext' => 'Carriga una versione noa de custu file',

# File reversion
'filerevert-backlink' => '← $1',

# File deletion
'filedelete-backlink' => '← $1',
'filedelete-success'  => "Su file '''$1''' est istadu fuliau.",

# List redirects
'listredirects' => 'Lista de totu is redirects',

# Random page
'randompage' => 'Una pàgina a sorte',

# Statistics
'statistics'    => 'Istatisticas',
'sitestats'     => 'Statistiche del sito',
'userstats'     => 'Statistiche del {{SITENAME}}',
'sitestatstext' => 'Ci sono ben <b>$1</b> pagine nel database.
Questa cifra comprende le pagine "talk" (discussione), pagine su {{SITENAME}}, articoli esigui ("stub"), redirects, e altre pagine che probabilmente non andrebbero conteggiate fra gli articoli.
Escludendo queste, ci sono ben  <b>$2</b> pagine che sono con buona probabilità propriamente degli articoli.<p>
Ci sono state un totale di <b>$3</b> pagine viste, e <b>$4</b> modifiche agli articoli da quando il software è stato potenziato (Dicembre, 2002).
Questa media rivela che ci sono state una media di  <b>$5</b> modifiche per ciascun articolo, e che l\'articolo è stato letto <b>$6</b> volte per ciascuna modifica.',
'userstatstext' => 'Ci sono <b>$1</b> Utenti registrati.
<b>$2</b> di questi hanno il grado di amministratori (vedi $3).',

'disambiguationspage' => '{{ns:project}}:Links_to_disambiguating_pages',

'doubleredirects'     => 'Doppi Redirects',
'doubleredirectstext' => '<b>Attenzione:</b> Questa lista può talvolta contenere dei risultati non corretti. Ciò potrebbe magari accadere perchè vi sono del testo aggiuntivo o dei link dopo il tag #REDIRECT.<br />
Ogni riga contiene i link al primo ed al secondo redirect, oltre alla prima riga di testo del secondo redirect che di solito contiene il "reale" articolo di destinazione, quello al quale anche il primo redirect dovrebbe puntare.',

'brokenredirects'     => 'Redirects errati',
'brokenredirectstext' => 'Custus redirects ligant cun pàginas chi non esistint.',

'withoutinterwiki-submit' => 'Amosta',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'       => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'            => '$1 {{PLURAL:$1|liga|ligas}}',
'nmembers'          => '$1 {{PLURAL:$1|elementu|elementos}}',
'nviews'            => '$1 {{PLURAL:$1|bisura|bisuras}}',
'lonelypages'       => 'Pàginas burdas',
'unusedimages'      => 'Immagini non utilizzate',
'popularpages'      => 'Pàginas popularis',
'wantedpages'       => 'Articoli più richiesti',
'allpages'          => 'Totu is pàginas',
'shortpages'        => 'Pagine corte',
'longpages'         => 'Pagine lunghe',
'listusers'         => 'Elenco degli Utenti',
'specialpages'      => 'Pàginas ispetziales',
'spheading'         => 'Pagine speciali',
'newpages'          => 'Pàginas noas',
'newpages-username' => 'Nòmene usuàriu:',
'move'              => 'Movi',
'movethispage'      => 'Movi custa pàgina',
'unusedimagestext'  => '<p>Nota che altri siti web, come la {{SITENAME}} internazionale, potrebbero aver messo un link ad una immagine per mezzo di una URL diretta, perciò le immagini potrebbero essere listate qui anche essendo magari in uso.',
'notargettitle'     => 'Dati mancanti',
'notargettext'      => "Non hai specificato una pagina o un Utente in relazione al quale eseguire l'operazione richiesta.",

# Book sources
'booksources-isbn' => 'ISBN:',
'booksources-go'   => 'Bae',

'alphaindexline' => 'da $1 a $2',
'version'        => 'Versioni',

# Special:Log
'specialloguserlabel' => 'Usuàriu:',
'log'                 => 'Registros',

# Special:Allpages
'prevpage'       => 'Pàgina innantis ($1)',
'allpagesfrom'   => 'Amosta pàginas a partiri dae:',
'allarticles'    => 'Totu is pàginas',
'allpagessubmit' => 'Bae',

# Special:Listusers
'listusers-submit' => 'Amosta',

# E-mail user
'mailnologintext' => 'Devi fare il [[Special:UserLogin|login]]
ed aver registrato una valida casella e-mail nelle tue [[Special:Preferences|preferenze]] per mandare posta elettronica ad altri Utenti.',
'emailuser'       => 'E-mail a custu usuàriu',
'emailpagetext'   => 'Se questo Utente ha registrato una valida casella e-mail, il modulo qui sotto ti consentirà di scrivergli un solo messaggio.
La e-mail che hai indicato nelle tue preferenze apparirà nel campo "From" della mail, così che il destinatario possa, solo se lo desidera però, risponderti.',
'noemailtitle'    => 'Perunu indiritzu e-mail',
'noemailtext'     => 'Questo Utente non ha registrato alcuna casella e-mail,
oppure ha scelto di non ricevere  posta elettronica dagli altri Utenti.',
'emailsent'       => 'E-mail ispedia',
'emailsenttext'   => 'La tua e-mail è stata inviata.',

# Watchlist
'watchlist'        => 'Sa watchlist mea',
'mywatchlist'      => 'Sa watchlist mea',
'watchlistfor'     => "(pro '''$1''')",
'nowatchlist'      => "Non hai indicato articoli da tenere d'occhio.",
'watchnologin'     => 'Non intrau (log in)',
'watchnologintext' => 'Devi prima fare il [[Special:UserLogin|login]]
per modificare la tua lista di osservati speciali.',
'addedwatch'       => 'Aciùngiu a sa watchlist tua',
'addedwatchtext'   => "Sa pàgina \"[[:\$1]]\" est istada aciunta a sa [[Special:Watchlist|watchlist]] tua. 
Is mudàntzias de custa pàgina e de sa pàgina de cuntierras sua ant a bennere elencadas inoe, e su tìtulu at a aparire in '''grassetto''' in sa pàgina de is [[Special:RecentChanges|ùrtimas mudàntzias]] pro du bidere mengius.",
'removedwatch'     => 'Tirau dae sa watchlist tua',
'removedwatchtext' => 'Sa pàgina  "[[:$1]]" est istada tirada dae sa [[Special:Watchlist|watchlist tua]].',
'watch'            => 'Poni in sa watchlist',
'watchthispage'    => 'Pone ogru a custu artìculu',
'unwatch'          => 'Tira dae sa watchlist',
'unwatchthispage'  => 'Smetti di seguire',
'notanarticle'     => 'Custa pàgina non est unu artìculu',
'wlshowlast'       => 'Amosta is ùrtimas $1 oras $2 dies $3',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Aciungendi a sa watchlist...',
'unwatching' => 'Tirendi dae sa watchlist...',

'enotif_impersonal_salutation' => 'Usuàriu de {{SITENAME}}',

# Delete/protect/revert
'deletepage'                  => 'Fùlia pàgina',
'confirm'                     => 'Cunfima',
'delete-backlink'             => '← $1',
'confirmdeletetext'           => "Ses acanta de burrare una pàgina cun totu s'istòria sua.
Pro pregheri, cunfirma ca est intentzioni tua faghere custu, ca connosches is conseguentzias de s'atzione tua, a ca custa est cunforme a is [[{{MediaWiki:Policy-url}}|lìnnias polìtigas]].",
'actioncomplete'              => 'Atzioni cumpletada',
'deletedtext'                 => 'Sa pàgina "<nowiki>$1</nowiki>" est istada fuliada.
Castia su log $2 pro unu registru de is ùrtimas fuliaduras.',
'deletedarticle'              => 'at fuliau "[[$1]]"',
'dellogpage'                  => 'Burraduras',
'dellogpagetext'              => 'Qui di seguito, un elenco delle pagine cancellate di recente.
Tutti i tempi sono in ora del server.',
'reverted'                    => 'Torrada a sa versioni in antis',
'deletecomment'               => 'Motivu de sa burradura:',
'rollback'                    => 'Annudda is acontzos',
'rollbacklink'                => 'rollback',
'cantrollback'                => "Non si podet furriai s'acontzu;
s'ùrtimu contribudori est s'ùnicu autori de custa pàgina.",
'revertpage'                  => 'Burrados is acontzos de [[Special:Contributions/$2|$2]] ([[User talk:$2|discussione]]), torrada a sa versione cabidiana de [[User:$1|$1]]', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'protectlogpage'              => 'Amparaduras',
'protectcomment'              => 'Cummentu:',
'protectexpiry'               => 'Iscadèntzia:',
'protect_expiry_old'          => 'Iscadentzia giai passada.',
'protect-unchain'             => 'Sblocca is permissos de movere',
'protect-default'             => 'Autoritza totu is usuàrios',
'protect-fallback'            => 'Esigit su permissu "$1"',
'protect-level-autoconfirmed' => 'Blocca is usuàrios nobos o non registrados',
'protect-level-sysop'         => 'Isceti aministratoris',
'protect-summary-cascade'     => 'ricorsiva',
'protect-expiring'            => 'Iscadentzia: $1 (UTC)',
'restriction-type'            => 'Permissu:',
'restriction-level'           => 'Livellu de restritzioni:',

# Undelete
'undelete'          => 'Càstia pàginas fuliadas',
'undeletepage'      => 'Càstia e restaura pàginas fuliadas',
'undeletepagetext'  => "Le pagine qui di seguito indicate sono state cancellate, ma sono ancora in archivio e pertanto possono essere recuperate. L'archivio viene svuotato periodicamente.",
'undeleterevisions' => '$1 {{PLURAL:$1|arrevisioni|arrevisionis}} in archìviu',
'undeletehistory'   => 'Se recuperi questo articolo, tutte le sue revisioni verranno recuperate nella relativa cronologia.
Se una nuova pagina è stata creata con questo stesso nome dopo la cancellazione, le revisioni recuperate saranno inserite nella cronologia e la versione attualmente online della pagina non verrà modificata.',
'undeletebtn'       => 'Ripristina',
'undeletelink'      => 'càstia/riprìstina',
'undeletedarticle'  => 'Restaurada "$1"',

# Namespace form on various pages
'namespace'      => 'Nòmene logu:',
'invert'         => 'Fùrria sa seletzioni',
'blanknamespace' => '(Printzipali)',

# Contributions
'contributions' => 'Contributziones usuàriu',
'mycontris'     => 'Contributziones meas',
'contribsub2'   => 'Pro $1 ($2)',
'nocontribs'    => 'Nessuna modifica trovata conformemente a questi criteri.',
'ucnote'        => 'Qui sotto troverai le ultime <b>$1</b> modifiche effettuate da questo Utente negli ultimi <b>$2</b> giorni.',
'uclinks'       => 'Vedi le ultime $1 modifiche; vedi gli ultimi $2 giorni.',
'uctop'         => '(ùrtimu de sa pàgina)',
'month'         => 'Dae su mese (e innantis):',
'year'          => "Dae s'annu (e innantis):",

'sp-contributions-newbies'  => 'Amosta isceti is contributziones de is account novos',
'sp-contributions-blocklog' => 'registru de is bloccos',
'sp-contributions-search'   => 'Chirca contributziones',
'sp-contributions-username' => 'Indiritzu IP o nòmene usuàriu:',
'sp-contributions-submit'   => 'Chirca',

# What links here
'whatlinkshere'       => 'Pàginas chi ligant a custa',
'whatlinkshere-title' => 'Pàginas chi ligant a "$1"',
'whatlinkshere-page'  => 'Pàgina:',
'linklistsub'         => '(Lista di links)',
'linkshere'           => "Sas pàginas chi sighint ligant a '''[[:$1]]''':",
'nolinkshere'         => "Peruna pàgina ligat a '''[[:$1]]'''.",
'isredirect'          => 'redirect',
'whatlinkshere-links' => '← acapius',

# Block/unblock
'blockip'            => 'Blocca usuàriu',
'blockiptext'        => "Usa il modulo sottostante per bloccare l'accesso con diritto di scrittura da uno specifico indirizzo IP. Questo blocco deve essere operato SOLO per prevenire atti di vandalismo, ed in stretta osservanza dei principi tutti della [[{{MediaWiki:Policy-url}}|policy di {{SITENAME}}]]. Il blocco non può in nessun caso essere applicato per motivi ideologici.
Scrivi un motivo specifico per il quale questo indirizzo IP dovrebbe a tuo avviso essere bloccato (per esempio, cita i titoli di pagine eventualmente già oggetto di vandalismo editoriale).",
'ipaddress'          => 'Indiritzu IP:',
'ipadressorusername' => 'Indiritzu IP o nòmene usuàriu:',
'ipbreason'          => 'Motivu:',
'ipbsubmit'          => 'Blocca custu usuàriu',
'ipboptions'         => '2 oras:2 hours,1 die:1 day,3 dies:3 days,1 chida:1 week,2 chidas:2 weeks,1 mese:1 month,3 meses:3 months,6 meses:6 months,1 annu:1 year,infinidu:infinite', # display1:time1,display2:time2,...
'badipaddress'       => "L'indirizzo IP indicato non è corretto.",
'blockipsuccesssub'  => 'Blocco eseguito',
'blockipsuccesstext' => ' L\'indirizzo IP "$1" è stato bloccato.
<br />Vedi [[Special:IPBlockList|lista IP bloccati]].',
'ipb-unblock-addr'   => 'Sblocca $1',
'unblockip'          => "Sblocca s'usuàriu",
'unblockiptext'      => 'Usa il modulo sottostante per restituire il diritto di scrittura ad un indirizzo IP precedentemente bloccato.',
'ipusubmit'          => 'Sblocca questo indirizzo IP',
'ipblocklist'        => 'Usuàrios e indiritzos bloccados',
'ipblocklist-submit' => 'Chirca',
'blocklistline'      => '$1, $2 ha bloccato $3 ($4)',
'blocklink'          => 'blocca',
'unblocklink'        => 'sblocca',
'contribslink'       => 'contributziones',
'blocklogpage'       => 'Bloccos de usuàrios',
'blocklogentry'      => 'bloccau [[$1]] pro unu tempu de $2 $3',
'unblocklogentry'    => 'at sbloccau $1',
'sorbs'              => 'DNSBL',

# Developer tools
'lockdb'              => 'Blocca su database',
'unlockdb'            => 'Sblocca su database',
'lockdbtext'          => 'Bloccare il database sospenderà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere non consentirà a nessuno di eseguire operazioni che richiedano modifiche del database.<br /><br />
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare e, soprattutto, che il prima possibile sbloccherai nuovamente il database, ripristinandone la corretta funzionalità, non appena avrai terminato le tue manutenzioni.',
'unlockdbtext'        => 'Sbloccare il database ripristinerà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere di eseguire operazioni che richiedano modifiche del database.
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare.',
'lockconfirm'         => 'Sì, effettivamente intendo, sotto la mia responsabilità, bloccare il database.',
'unlockconfirm'       => ' Sì, effettivamente intendo, sotto la mia responsabilità, sbloccare il database.',
'lockbtn'             => 'Blocca su database',
'unlockbtn'           => 'Sblocca su database',
'locknoconfirm'       => 'Non hai spuntato la casellina di conferma.',
'lockdbsuccesssub'    => 'Blocco del database eseguito',
'unlockdbsuccesssub'  => 'Sblocco del database eseguito, rimosso blocco',
'lockdbsuccesstext'   => 'Il database di {{SITENAME}} è stato bloccato.
<br />Ricordati di rimuovere il blocco non appena avrai terminatoi le tue manutenzioni.',
'unlockdbsuccesstext' => 'Su database est istadu sbloccau.',

# Move page
'movepage'         => 'Spostamento di pagina',
'movepagetext'     => "Con il modulo sottostante puoi rinominare una pagina, spostando anche tutta la sua cronologia al nuovo nome.
Il vecchior titolo diverrà automaticamente un redirect che punta al nuovo titolo.
I link alla vecchia pagina non saranno aggiornati (e punteranno quindi al redirect);
accertati di controllare con cura che non si creino doppi redirects o redirects interrotti.
Resta nella tua responsabilità di accertarti che i link continuino a puntare verso dove devono dirigersi.

Nota bene: la pagina '''non''' sarà spostata se vi fosse già un articolo con il nuovo nome, a meno che non sia una pagina vuota o un redirect e che non abbia cronologia.
Questo significa che, se commetti un errore, puoi nuovamente rinominare una pagina col vecchio titolo, ma non puoi sovrascrivere una pagina già esistente.

'''ATTENZIONE!'''
Questo cambiamento drastico potrebbe creare inattesi contrattempi, specialmente se si tratta di una pagina molto visitata. Accertati di aver ben valutato le conseguenze dello spostamento, prima di procedere. Nel dubbio, contatta un Amministratore.",
'movepagetalktext' => "Sa pàgina cuntierras asotziada, chi esistit, at a èssere movida automaticamenti impare a sa pàgina printzipali, '''a parte in custos casos''':
* su movimentu de sa pàgina est tra namespaces diversos;
* in currispondentzia de su tìtulu nou esistit giai una pàgina de cuntierras (non bùida);
* sa casella inoe in basciu non est istata sceberada.

In custus casos, chi boles, depis mòvere a manu su cuntentu de sa pàgina.",
'movearticle'      => 'Movi sa pàgina:',
'movenologin'      => 'Non hai effettuato il login',
'movenologintext'  => 'Depis èssere unu usuàriu registrau e [[Special:UserLogin|intrau]] pro poder mòvere una pàgina',
'newtitle'         => 'Tìtulu nou:',
'move-watch'       => 'Pone ogru a custa pàgina',
'movepagebtn'      => 'Movi sa pàgina',
'pagemovedsub'     => 'Movimentu andau beni',
'movepage-moved'   => '<big>\'\'\'"$1" est istada mòvida a "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Una pàgina cun custu nòmene esistit giai, o su nòmene chi as sceberau non est validu.
Pro pregheri scebera un àteru nòmene.',
'talkexists'       => "'''Su movimentu de sa pàgina est andau beni, ma non est istadu possibile moviri sa pàgina de cuntierras proite ndi esistit giai un àtera cun su stessu tìtulu. Pro preghere aciungi tue su cuntestu de sa pàgina becia.'''",
'movedto'          => 'mòvida a',
'movetalk'         => 'Movi puru sa pàgina de cuntierras',
'talkpagemoved'    => 'Anche la corrispondente pagina di discussione è stata spostata.',
'talkpagenotmoved' => 'La corrispondente pagina di discussione <strong>non è stata spostata</strong>.',
'1movedto2'        => 'at mòvidu [[$1]] a [[$2]]',
'1movedto2_redir'  => 'at movidu [[$1]] a [[$2]] subra redirect',
'movelogpage'      => 'Moviduras',
'movereason'       => 'Motivu:',
'revertmove'       => 'fùrria',

# Export
'export'          => 'Esporta pàginas',
'export-download' => 'Sarva comente file',

# Thumbnails
'thumbnail-more' => 'Amannia',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Sa pàgina usuàriu tua',
'tooltip-pt-mytalk'               => 'Sa pàgina de is cuntierras tuas',
'tooltip-pt-preferences'          => 'Is preferentzias ca podes scioberai',
'tooltip-pt-watchlist'            => 'Sa lista de is pàginas che dui ses ponendi ogru',
'tooltip-pt-mycontris'            => 'Sa lista de is contributziones meas',
'tooltip-pt-login'                => 'Si cunsigiat sa registratzioni; mancari non siat obligatoria',
'tooltip-pt-logout'               => 'Bessida (log out)',
'tooltip-ca-talk'                 => 'Cuntierras a propositu de su cuntestu de sa pàgina',
'tooltip-ca-edit'                 => "Podes acontzare custa pàgina.
Pro pregheri, prima de sarvari càstia s'antiprima",
'tooltip-ca-addsection'           => 'Incumintza una setzioni noa',
'tooltip-ca-viewsource'           => 'Sa pàgina est amparada.
Podes castiare sa mitza sua',
'tooltip-ca-history'              => 'Versiones innantis de custa pàgina',
'tooltip-ca-protect'              => 'Ampara custa pàgina',
'tooltip-ca-delete'               => 'Fùlia custa pàgina',
'tooltip-ca-move'                 => 'Movi custa pàgina',
'tooltip-ca-watch'                => 'Aciungi custa pàgina a sa watchlist tua',
'tooltip-ca-unwatch'              => 'Tira custa pàgina da sa watchlist tua',
'tooltip-search'                  => 'Chirca a intru de {{SITENAME}}',
'tooltip-search-go'               => 'Bae a una pàgina cun custu nòmene, chi esistit',
'tooltip-search-fulltext'         => 'Chirca custu testu ne is pàginas',
'tooltip-n-mainpage'              => 'Visita sa pàgina printzipali',
'tooltip-n-portal'                => 'Descritzioni de su progetu, ita podes faghere, ainnui agatas cosas',
'tooltip-n-recentchanges'         => 'Sa lista de is ùrtimas mudàntzias de su giassu',
'tooltip-n-randompage'            => 'Mosta una pàgina a sorte',
'tooltip-n-help'                  => 'Pàginas de agiudu',
'tooltip-t-whatlinkshere'         => 'Lista de totu is pàginas che ligant a custa',
'tooltip-t-recentchangeslinked'   => 'Lista de is ùrtimas mudàntzias de is pàgina chi ligant a custa',
'tooltip-feed-rss'                => 'RSS feed pro custa pàgina',
'tooltip-feed-atom'               => 'Atom feed pro custa pàgina',
'tooltip-t-contributions'         => 'Càstia sa lista de is contributziones de custu usuàriu',
'tooltip-t-emailuser'             => 'Ispedi una missada eletronica a custu usuàriu',
'tooltip-t-upload'                => 'Carriga file multimediale',
'tooltip-t-specialpages'          => 'Lista de is pàginas ispetziales',
'tooltip-t-print'                 => "Versione de custa pàgina pro s'imprenta",
'tooltip-t-permalink'             => 'Acapiu permanenti a custa versioni de sa pàgina',
'tooltip-ca-nstab-main'           => 'Càstia su cuntènnidu de sa pàgina',
'tooltip-ca-nstab-user'           => 'Càstia sa pàgina usuàriu',
'tooltip-ca-nstab-special'        => 'Custa est una pàgina ispetziale, non da podes acontzare',
'tooltip-ca-nstab-project'        => 'Càstia sa pàgina de servìtziu',
'tooltip-ca-nstab-image'          => 'Càstia sa pàgina de su file',
'tooltip-ca-nstab-template'       => 'Castia su template',
'tooltip-ca-nstab-category'       => 'Càstia sa pàgina de sa categoria',
'tooltip-minoredit'               => 'Signa comente acontzu minore',
'tooltip-save'                    => 'Sarva is mudàntzias tuas',
'tooltip-preview'                 => 'Antiprima de is mudàntzias, pro pregeri usa custu prima de sarvari!',
'tooltip-diff'                    => 'Amosta is mudàntzias chi as fatu a su testu',
'tooltip-compareselectedversions' => 'Càstia is diferèntzias de is duas versiones scioberadas de custa pàgina',
'tooltip-watch'                   => 'Aciungi custa pàgina a sa watchlist tua',
'tooltip-recreate'                => 'Torra a creare sa pàgina mancari siat istada fuliada',
'tooltip-upload'                  => 'Cumentza a carrigari',

# Spam protection
'listingcontinuesabbrev' => 'sìghi',

# Browsing diffs
'previousdiff' => '← Acontzu in antis',
'nextdiff'     => 'Acontzu in fatu →',

# Media information
'widthheight'    => '$1×$2',
'file-info-size' => '($1 × $2 pixels, mannesa de su file: $3, tipu de MIME: $4)',
'svg-long-desc'  => '(file in formadu SVG, mannesa nominale $1 × $2 pixel, mannesa de su file: $3)',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2×$3',
'seconds-abbrev' => 's',
'minutes-abbrev' => 'm',
'hours-abbrev'   => 'h',

# Metadata
'metadata' => 'Metadatos',

# EXIF tags
'exif-fnumber-format'     => 'f/$1',
'exif-flash'              => 'Flash',
'exif-focallength-format' => '$1 mm',

# EXIF attributes
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',

# External editor support
'edit-externally'      => 'Acontza custu file usendi unu programma de foras',
'edit-externally-help' => '(Pro àteras informatziones càstia is [http://www.mediawiki.org/wiki/Manual:External_editors istrutziones])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'totu',
'watchlistall2'    => 'totu',
'namespacesall'    => 'totu',
'monthsall'        => 'totu',

# action=purge
'confirm_purge_button' => 'OK',

# Separators for various lists
'comma-separator' => ',&#32;',

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Watchlist editor
'watchlistedit-raw-titles' => 'Tìtulos:',

# Watchlist editing tools
'watchlisttools-edit' => 'Castia e acontza sa watchlist',
'watchlisttools-raw'  => 'Acontza sa watchlist dae su testu',

# Special:Version
'version-software-version' => 'Versioni',

# Special:Filepath
'filepath-page' => 'Nòmene de su file:',

);
