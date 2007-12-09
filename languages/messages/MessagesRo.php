<?php
/** Romanian (Română)
 *
 * @addtogroup Language
 *
 * @author Emily
 * @author KlaudiuMihaila
 * @author Laurap
 * @author SCriBu
 * @author Nike
 * @author Gangleri
 * @author Siebrand
 * @author Teak
 * @author לערי ריינהארט
 * @author Meno25
 */

$skinNames = array(
	'standard' => 'Normală',
	'nostalgia' => 'Nostalgie'
);

$magicWords = array(
	#   ID                                 CASE  SYNONYMS
	'notoc'                  => array( 0,    '__NOTOC__', '__FARACUPRINS__'                    ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__FARAEDITSECTIUNE__'       ),
	'currentmonth'           => array( 1,    'CURRENTMONTH', 'NUMARLUNACURENTA'                ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME', 'NUMELUNACURENTA'             ),
	'currentday'             => array( 1,    'CURRENTDAY', 'NUMARZIUACURENTA'                  ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME', 'NUMEZIUACURENTA'               ),
	'currentyear'            => array( 1,    'CURRENTYEAR', 'ANULCURENT'                       ),
	'currenttime'            => array( 1,    'CURRENTTIME', 'ORACURENTA'                       ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES', 'NUMARDEARTICOLE'             ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN', 'NUMELUNACURENTAGEN'       ),
	'msgnw'                  => array( 0,    'MSGNW:', 'MSJNOU:'                               ),
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Special',
	NS_MAIN           => '',
	NS_TALK           => 'Discuţie',
	NS_USER           => 'Utilizator',
	NS_USER_TALK      => 'Discuţie_Utilizator',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Discuţie_$1',
	NS_IMAGE          => 'Imagine',
	NS_IMAGE_TALK     => 'Discuţie_Imagine',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Discuţie_MediaWiki',
	NS_TEMPLATE       => 'Format',
	NS_TEMPLATE_TALK  => 'Discuţie_Format',
	NS_HELP           => 'Ajutor',
	NS_HELP_TALK      => 'Discuţie_Ajutor',
	NS_CATEGORY       => 'Categorie',
	NS_CATEGORY_TALK  => 'Discuţie_Categorie'
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y H:i',
);

$fallback8bitEncoding = 'iso8859-2';

$messages = array(
# User preference toggles
'tog-underline'               => 'Subliniază legăturile',
'tog-highlightbroken'         => 'Formatează legăturile necreate <a href="" class="new">aşa</a> (alternativă: aşa<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Aranjează justificat paragrafele',
'tog-hideminor'               => 'Ascunde modificările minore în schimbări recente',
'tog-extendwatchlist'         => 'Extinde lista de articole urmărite pentru a arăta toate schimbările făcute',
'tog-usenewrc'                => 'Schimbări recente avansate (JavaScript)',
'tog-numberheadings'          => 'Numerotează automat secţiunile',
'tog-showtoolbar'             => 'Afişează bara de unelte pentru modificare (JavaScript)',
'tog-editondblclick'          => 'Modifică pagini la dublu clic (JavaScript)',
'tog-editsection'             => 'Activează modificarea secţiunilor prin legăturile [modifică]',
'tog-editsectiononrightclick' => 'Activează modificarea secţiunilor prin clic dreapta<br />
pe titlul secţiunii (JavaScript)',
'tog-showtoc'                 => 'Arată cuprinsul (pentru paginile cu mai mult de 3 paragrafe cu titlu)',
'tog-rememberpassword'        => 'Aminteşte-ţi între sesiuni',
'tog-editwidth'               => 'Căsuţa de modificare are lăţime maximă',
'tog-watchcreations'          => 'Adaugă paginile pe care le creez la lista mea de urmărire',
'tog-watchdefault'            => 'Adaugă paginile pe care le modific la lista mea de urmărire',
'tog-watchmoves'              => 'Adaugă paginile pe care le mut la lista mea de urmărire',
'tog-watchdeletion'           => 'Adaugă paginile pe care le şterg în lista mea de urmărire',
'tog-minordefault'            => 'Marchează toate modificările minore din oficiu',
'tog-previewontop'            => 'Arată previzualizarea înainte de a modifica secţiunea',
'tog-previewonfirst'          => 'Arată previzualizarea la prima modificare',
'tog-nocache'                 => 'Dezactivează cache-ul paginilor',
'tog-enotifwatchlistpages'    => 'Trimite-mi un email la modificările paginilor',
'tog-enotifusertalkpages'     => 'Trimite-mi un email când pagina mea de discuţii este modificată',
'tog-enotifminoredits'        => 'Trimite-mi un email de asemenea pentru modificările minore ale paginilor',
'tog-enotifrevealaddr'        => 'Descoperă-mi adresa email în mesajele de notificare',
'tog-shownumberswatching'     => 'Arată numărul utilizatorilor care urmăresc',
'tog-fancysig'                => 'Semnătură brută (fără legătură automată)',
'tog-externaleditor'          => 'Utilizează modificator extern ca standard',
'tog-externaldiff'            => 'Utilizează diferenţele externe ca standard',
'tog-showjumplinks'           => 'Activează legăturile de accesibilitate "salt la"',
'tog-uselivepreview'          => 'Utilizează previzualizarea live (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Avertizează-mă când uit să descriu modificările',
'tog-watchlisthideown'        => 'Ascunde modificările mele la lista mea de urmărire',
'tog-watchlisthidebots'       => 'Ascunde modificările boţilor la lista mea de urmărire',
'tog-watchlisthideminor'      => 'Ascunde modificările minore la lista mea de urmărire',
'tog-nolangconversion'        => 'Dezactivează conversia variabilelor',
'tog-ccmeonemails'            => 'Trimite-mi o copie când trimit un email altui utilizator',
'tog-diffonly'                => 'Nu arăta conţinutul paginii prin dif',

'underline-always'  => 'Întotdeauna',
'underline-never'   => 'Niciodată',
'underline-default' => 'Standardul browser-ului',

'skinpreview' => '(Previzualizare)',

# Dates
'sunday'        => 'duminică',
'monday'        => 'luni',
'tuesday'       => 'marţi',
'wednesday'     => 'miercuri',
'thursday'      => 'joi',
'friday'        => 'vineri',
'saturday'      => 'sâmbătă',
'sun'           => 'Dum',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Mie',
'thu'           => 'Joi',
'fri'           => 'Vin',
'sat'           => 'Sâm',
'january'       => 'ianuarie',
'february'      => 'februarie',
'march'         => 'martie',
'april'         => 'aprilie',
'may_long'      => 'mai',
'june'          => 'iunie',
'july'          => 'iulie',
'august'        => 'august',
'september'     => 'septembrie',
'october'       => 'octombrie',
'november'      => 'noiembrie',
'december'      => 'decembrie',
'january-gen'   => 'ianuarie',
'february-gen'  => 'februarie',
'march-gen'     => 'martie',
'april-gen'     => 'aprilie',
'may-gen'       => 'mai',
'june-gen'      => 'iunie',
'july-gen'      => 'iulie',
'august-gen'    => 'august',
'september-gen' => 'septembrie',
'october-gen'   => 'octombrie',
'november-gen'  => 'noiembrie',
'december-gen'  => 'decembrie',
'jan'           => 'ian',
'feb'           => 'feb',
'mar'           => 'mart',
'apr'           => 'apr',
'may'           => 'mai',
'jun'           => 'iun',
'jul'           => 'iul',
'aug'           => 'aug',
'sep'           => 'sept',
'oct'           => 'oct',
'nov'           => 'nov',
'dec'           => 'dec',

# Bits of text used by many pages
'categories'            => '{{PLURAL:$1|Categorie|Categorii}}',
'pagecategories'        => '{{PLURAL:$1|Categorie|Categorii}}',
'category_header'       => 'Articole din categoria "$1"',
'subcategories'         => 'Subcategorii',
'category-media-header' => 'Fişiere media în categoria "$1"',
'category-empty'        => "''Această categorie nu conţine articole sau fişiere media.''",

'mainpagetext' => "<big>'''Programul Wiki a fost instalat cu succes.'''</big>",

'about'          => 'Despre',
'article'        => 'Articol',
'newwindow'      => '(se deschide într-o fereastră nouă)',
'cancel'         => 'Renunţă',
'qbfind'         => 'Găseşte',
'qbbrowse'       => 'Răsfoieşte',
'qbedit'         => 'Modifică',
'qbpageoptions'  => 'Opţiuni ale paginii',
'qbpageinfo'     => 'Informaţii ale paginii',
'qbmyoptions'    => 'Opţiunile mele',
'qbspecialpages' => 'Pagini speciale',
'moredotdotdot'  => 'Altele...',
'mypage'         => 'Pagina mea',
'mytalk'         => 'Discuţii',
'anontalk'       => 'Discuţia pentru această adresă IP',
'navigation'     => 'Navigare',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Eroare',
'returnto'          => 'Înapoi la $1.',
'tagline'           => 'De la {{SITENAME}}',
'help'              => 'Ajutor',
'search'            => 'Caută',
'searchbutton'      => 'Caută',
'go'                => 'Du-te',
'searcharticle'     => 'Du-te',
'history'           => 'Versiuni mai vechi',
'history_short'     => 'istoric',
'updatedmarker'     => 'încărcat de la ultima mea vizită',
'info_short'        => 'Informaţii',
'printableversion'  => 'Versiune de tipărit',
'permalink'         => 'Legătură permanentă',
'print'             => 'Tipărire',
'edit'              => 'Modifică',
'editthispage'      => 'Modifică pagina',
'delete'            => 'Şterge',
'deletethispage'    => 'Şterge pagina',
'undelete_short'    => 'Recuperarea {{PLURAL:$1|unei editări|de $1 editări}}',
'protect'           => 'Protejează',
'protect_change'    => 'schimbă protecţia',
'protectthispage'   => 'Protejează pagina',
'unprotect'         => 'Deprotejare',
'unprotectthispage' => 'Deprotejează pagina',
'newpage'           => 'Pagină nouă',
'talkpage'          => 'Discută pagina',
'talkpagelinktext'  => 'Discuţie',
'specialpage'       => 'Pagină Specială',
'personaltools'     => 'Unelte personale',
'postcomment'       => 'Adaugă un comentariu',
'articlepage'       => 'Vezi articolul',
'talk'              => 'Discuţie',
'views'             => 'Vizualizări',
'toolbox'           => 'Trusa de unelte',
'userpage'          => 'Vezi pagina utilizatorului',
'projectpage'       => 'Vezi pagina proiectului',
'imagepage'         => 'Vezi pagina imaginii',
'mediawikipage'     => 'Vezi pagina mesajului',
'templatepage'      => 'Vezi pagina formatului',
'viewhelppage'      => 'Vezi pagina de ajutor',
'categorypage'      => 'Vezi pagina categoriei',
'viewtalkpage'      => 'Vezi discuţia',
'otherlanguages'    => 'În alte limbi',
'redirectedfrom'    => '(Redirecţionat de la $1)',
'redirectpagesub'   => 'Pagină de redirecţionare',
'lastmodifiedat'    => 'Ultima modificare $2, $1.', # $1 date, $2 time
'viewcount'         => 'Această pagină a fost vizitată {{PLURAL:$1|odată|de $1 ori}}.',
'protectedpage'     => 'Pagină protejată',
'jumpto'            => 'Salt la:',
'jumptonavigation'  => 'Navigare',
'jumptosearch'      => 'căutare',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Despre {{SITENAME}}',
'aboutpage'         => 'Project:Despre',
'bugreports'        => 'Raportare probleme',
'bugreportspage'    => 'Project:Rapoarte probleme',
'copyright'         => 'Conţinutul este disponibil sub $1.',
'copyrightpagename' => 'Drepturi de autor în {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Drepturi de autor',
'currentevents'     => 'Discută la cafenea',
'currentevents-url' => 'Project:Cafenea',
'disclaimers'       => 'Termeni',
'disclaimerpage'    => 'Project:Termeni',
'edithelp'          => 'Ajutor pentru modificare',
'edithelppage'      => 'Help:Cum să modifici o pagină',
'faq'               => 'Întrebări frecvente',
'faqpage'           => 'Project:Întrebări frecvente',
'helppage'          => 'Help:Ajutor',
'mainpage'          => 'Pagina principală',
'policy-url'        => 'Project:Politică',
'portal'            => 'Portalul comunităţii',
'portal-url'        => 'Project:Portal Comunitate',
'privacy'           => 'Politica de confidenţialitate',
'privacypage'       => 'Project:Politica de confidenţialitate',
'sitesupport'       => 'Donaţii',
'sitesupport-url'   => 'Project:Donaţii',

'badaccess'        => 'Eroare permisiune',
'badaccess-group0' => 'Execuţia acţiunii cerute nu este permisă.',
'badaccess-group1' => 'Acţiunea cerută este rezervată utilizatorilor din grupul $1.',
'badaccess-group2' => 'Acţiunea cerută este rezervată utilizatorilor din unul din grupurile $1.',
'badaccess-groups' => 'Acţiunea cerută este rezervată utilizatorilor din unul din grupurile $1.',

'versionrequired'     => 'Este necesară versiunea $1 MediaWiki',
'versionrequiredtext' => 'Versiunea $1 MediaWiki este necesară pentru a folosi această pagină. Vezi [[Special:Version|versiunea actuală]].',

'ok'                      => 'Trimite',
'retrievedfrom'           => 'Adus de la "$1"',
'youhavenewmessages'      => 'Aveţi $1 ($2).',
'newmessageslink'         => 'mesaje noi',
'newmessagesdifflink'     => 'comparaţie cu versiunea precedentă',
'youhavenewmessagesmulti' => 'Aveţi mesaje noi la $1',
'editsection'             => 'modifică',
'editold'                 => 'modifică',
'editsectionhint'         => 'Modifică secţiunea: $1',
'toc'                     => 'Cuprins',
'showtoc'                 => 'arată',
'hidetoc'                 => 'ascunde',
'thisisdeleted'           => 'Vizualizare sau recuperare $1?',
'viewdeleted'             => 'Vizualizează $1?',
'restorelink'             => '{{PLURAL:$1|o modificare ştearsă|$1 modificări şterse}}',
'feedlinks'               => 'Întreţinere:',
'feed-invalid'            => 'Tip de abonament invalid',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Articol',
'nstab-user'      => 'Pagină de utilizator',
'nstab-media'     => 'Pagină Media',
'nstab-special'   => 'Special',
'nstab-project'   => 'Proiect',
'nstab-image'     => 'Fişier',
'nstab-mediawiki' => 'Mesaj',
'nstab-template'  => 'Format',
'nstab-help'      => 'Ajutor',
'nstab-category'  => 'Categorie',

# Main script and global functions
'nosuchaction'      => 'Această acţiune nu există',
'nosuchactiontext'  => 'Acţiunea specificată în adresă nu este recunoscută de {{SITENAME}}.',
'nosuchspecialpage' => 'Această pagină specială nu există',
'nospecialpagetext' => 'Ai cerut o [[Special:Specialpages|pagină specială]] care nu este recunoscută de {{SITENAME}}.',

# General errors
'error'                => 'Eroare',
'databaseerror'        => 'Eroare la baza de date',
'dberrortext'          => 'A apărut o eroare în execuţia query-ului. Aceasta se poate datora unui query ilegal (vezi $5), sau poate indica o problemă în program. Ultimul query încercat a fost: <blockquote><tt>$1</tt></blockquote> în cadrul funcţiei "<tt>$2</tt>". MySQL a returnat eroarea "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'A apărut o eroare de sintaxă în query. Ultimul query încercat a fost: "$1" din funcţia "$2". MySQL a returnat eroarea "$3: $4".',
'noconnect'            => 'Nu s-a putut conecta baza de date pe $1',
'nodb'                 => 'Nu s-a putut selecta baza de date $1',
'cachederror'          => 'Aceasta este o versiune din cache a paginii cerute şi este posibil să nu fie ultima variantă a acesteia.',
'laggedslavemode'      => 'Atenţie: S-ar putea ca pagina să nu conţină ultimele actualizări.',
'readonly'             => 'Baza de date este blocată la scriere',
'enterlockreason'      => 'Precizează motivul pentru blocare, incluzând o estimare a termenului de deblocare a bazei de date',
'readonlytext'         => 'Baza de date {{SITENAME}} este momentan blocată la scriere, probabil pentru o operaţiune de rutină, după care va fi deblocată şi se va reveni la starea normală.

Administratorul care a blocat-o a oferit această explicaţie: $1',
'missingarticle'       => 'Textul "$1" nu a putut fi găsit în baza de date, aşa cum ar fi trebuit. Aceasta nu este o problemă legată de programul care gestionează baza de date, ci probabil o problemă în programul care administrează {{SITENAME}}. Te rugăm să raportezi această problemă unui administrator, incluzând şi adresa acestei pagini.',
'readonly_lag'         => 'Baza de date a fost închisă automatic în timp ce serverele secundare ale bazei de date îl urmează pe cel principal.',
'internalerror'        => 'Eroare internă',
'internalerror_info'   => 'Eroare internă: $1',
'filecopyerror'        => 'Fişierul "$1" nu a putut fi copiat la "$2".',
'filerenameerror'      => 'Fişierul "$1" nu a putut fi mutat la "$2".',
'filedeleteerror'      => 'Fişierul "$1" nu a putut fi şters.',
'directorycreateerror' => 'Nu se poate crea directorul "$1".',
'filenotfound'         => 'Fişierul "$1" nu a putut fi găsit.',
'fileexistserror'      => 'Imposibil de scris fişierul "$1": fişierul există deja',
'unexpected'           => 'Valoare neaşteptată: "$1"="$2".',
'formerror'            => 'Eroare: datele nu au putut fi trimise',
'badarticleerror'      => 'Această acţiune nu poate fi efectuată pe această pagină.',
'cannotdelete'         => 'Comanda de ştergere nu s-a putut executa! Probabil că ştergerea a fost operată între timp.',
'badtitle'             => 'Titlu invalid',
'badtitletext'         => 'Titlul căutat a fost invalid, gol sau o legătură invalidă inter-linguală sau inter-wiki.',
'perfdisabled'         => 'Ne pare rău! Această opţiune a fost dezactivată temporar în timpul orelor de vârf din motive de performanţă. Te rugăm să revii la altă oră şi să încerci din nou.',
'perfcached'           => 'Datele următoare au fost păstrate în cache şi s-ar putea să nu fie la zi.',
'perfcachedts'         => "Informaţiile de mai jos provin din ''cache''; ultima actualizare s-a efectuat la $1.",
'querypage-no-updates' => 'Actualizările acestei pagini sunt momentan dezactivate. Informaţiile de aici nu sunt împrospătate.',
'wrong_wfQuery_params' => 'Număr incorect de parametri pentru wfQuery()<br />
Funcţia: $1<br />
Interogarea: $2',
'viewsource'           => 'Vezi sursa',
'viewsourcefor'        => 'pentru $1',
'protectedpagetext'    => 'Această pagină este protejată împotriva modificărilor.',
'viewsourcetext'       => 'Se poate vizualiza şi copia conţinutul acestei pagini:',
'protectedinterface'   => 'Această pagină asigură textul interfeţei pentru software şi este protejată pentru a preveni abuzurile.',
'editinginterface'     => "'''Avertizare''': Editezi o pagină care este folosită pentru a furniza textul interfeţei pentru software. Modificările aduse acestei pagini vor afecta aspectul interfeţei utilizatorului pentru alţi utilizatori.",
'sqlhidden'            => '(interogare SQL ascunsă)',
'cascadeprotected'     => 'Această pagină a fost protejată la scriere deoarece este inclusă în {{PLURAL:$1|următoarea pagină|următoarele pagini}}, care {{PLURAL:$1|este protejată|sunt protejate}} în cascadă:',
'namespaceprotected'   => "Nu ai permisiunea de a edita pagini în spaţiul de nume '''$1'''.",
'customcssjsprotected' => 'Nu aveţi permisiunea să editaţi această pagină, deoarece conţine datele private ale unui alt utilizator.',
'ns-specialprotected'  => 'Paginile din spaţiul de nume {{ns:special}} nu pot fi editate.',

# Login and logout pages
'logouttitle'                => 'Sesiune închisă',
'logouttext'                 => 'Sesiunea ta în {{SITENAME}} a fost închisă. Poţi continua să foloseşti {{SITENAME}} anonim, sau poţi să te reautentifici ca acelaşi sau ca alt utilizator.',
'welcomecreation'            => '==Bun venit, $1!==

Contul dumneavoatră a fost creat. Nu uitaţi să vă personalizaţi preferinţele în {{SITENAME}}.',
'loginpagetitle'             => 'Autentificare utilizator',
'yourname'                   => 'Numele de utilizator',
'yourpassword'               => 'Parola',
'yourpasswordagain'          => 'Repetă parola',
'remembermypassword'         => 'Reţine-mi parola între sesiuni',
'yourdomainname'             => 'Domeniul tău',
'externaldberror'            => 'A fost fie o eroare de bază de date pentru o autentificare extenă sau nu aveţi permisiunea să actualizaţi contul extern.',
'loginproblem'               => '<b>A apărut o problemă la autentificarea ta.</b><br />
Încearcă din nou!',
'login'                      => 'Autentificare',
'loginprompt'                => 'Trebuie să ai modulele cookie activate pentru a te autentifica la {{SITENAME}}.',
'userlogin'                  => 'Creare cont / Autentificare',
'logout'                     => 'Închide sesiunea',
'userlogout'                 => 'Închide sesiunea',
'notloggedin'                => 'Nu sunteţi autentificat',
'nologin'                    => 'Nu aveţi cont încă? $1.',
'nologinlink'                => 'Creaţi-vă un cont de utilizator acum',
'createaccount'              => 'Creare cont',
'gotaccount'                 => 'Aveţi deja un cont de utilizator? $1.',
'gotaccountlink'             => 'Autentificaţi-vă',
'createaccountmail'          => 'după e-mail',
'badretype'                  => 'Parolele pe care le-ai introdus diferă.',
'userexists'                 => 'Numele de utilizator pe care l-aţi introdus există deja. Încercaţi cu un alt nume.',
'youremail'                  => 'Adresa de e-mail (opţional)*',
'username'                   => 'Nume de utilizator:',
'uid'                        => 'ID utilizator:',
'yourrealname'               => 'Numele dvs. real*',
'yourlanguage'               => 'Limbă interfaţă',
'yourvariant'                => 'Varianta',
'yournick'                   => 'Versiune scurtă a numelui, pentru semnături',
'badsig'                     => 'Semnătură brută incorectă; verificaţi tag-urile HTML.',
'badsiglength'               => 'Nume prea lung; dimensiunea trebuie să fie mai mică de $1 caractere.',
'email'                      => 'E-mail',
'prefs-help-realname'        => '* Numele dumneavoastră real (opţional): Dacă decideţi introducerea numelui real aici, acesta va fi folosit pentru a vă atribui munca.<br />',
'loginerror'                 => 'Eroare de autentificare',
'prefs-help-email'           => '*Adresa de e-mail (opţional): Permite altor utilizatori să vă contacteze prin e-mail via {{SITENAME}} fără a vă divulga identitatea. De asemenea, permite recuperarea parolei dacă o uitaţi.',
'prefs-help-email-required'  => 'Adresa de e-mail este necesară.',
'nocookiesnew'               => 'Contul a fost creat, dar dvs. nu sunteţi autentificat(ă). {{SITENAME}} foloseşte cookie-uri pentru a reţine utilizatorii autentificaţi. Browser-ul dvs. are modulele cookie dezactivate (disabled). Vă rugăm să le activaţi şi să vă reautentificaţi folosind noul nume de utilizator şi noua parolă.',
'nocookieslogin'             => '{{SITENAME}} foloseşte module cookie pentru a autentifica utilizatorii. Browser-ul dvs. are cookie-urile dezactivate. Vă rugăm să le activaţi şi să incercaţi din nou.',
'noname'                     => 'Numele de utilizator pe care l-ai specificat este invalid.',
'loginsuccesstitle'          => 'Autentificare reuşită',
'loginsuccess'               => 'Aţi fost autentificat în {{SITENAME}} ca "$1".',
'nosuchuser'                 => 'Nu există nici un utilizator cu numele "$1". Verifică dacă ai scris corect sau foloseşte această pagină pentru a crea un nou utilizator.',
'nosuchusershort'            => 'Nu este nici un utilizator cu numele "$1". Verificaţi dacă aţi scris corect.',
'nouserspecified'            => 'Trebuie să specificaţi un nume de utilizator.',
'wrongpassword'              => 'Parola pe care ai introdus-o este greşită. Te rugăm să încerci din nou.',
'wrongpasswordempty'         => 'Spaţiul pentru introducerea parolei nu a fost completat. Vă rugăm să încercaţi din nou.',
'passwordtooshort'           => 'Parola dumneavoastră este prea scurtă. Trebuie să aibă cel puţin $1 caractere.',
'mailmypassword'             => 'Trimite-mi parola pe e-mail!',
'passwordremindertitle'      => 'Noua parolă temporară la {{SITENAME}}',
'passwordremindertext'       => 'Cineva (probabil tu, de la adresa $1)
a cerut să ţi se trimită o nouă parolă pentru {{SITENAME}} ($4).
Parola pentru utilizatorul "$2" este acum "$3".
Este recomandat să intri pe {{SITENAME}} şi să-ţi schimbi parola cât mai curând.',
'noemail'                    => 'Nu este nici o adresă de e-mail înregistrată pentru utilizatorul "$1".',
'passwordsent'               => 'O nouă parolă a fost trimisă la adresa de e-mail a utilizatorului "$1". Te rugăm să te autentifici pe {{SITENAME}} după ce o primeşti.',
'blocked-mailpassword'       => 'Această adresă IP este blocată la editare, şi deci nu este permisă utilizarea funcţiei de recuperare a parolei pentru a preveni abuzul.',
'eauthentsent'               => 'Un email de confirmare a fost trimis adresei nominalizate. Înainte de a fi trimis orice alt email acestui cont, trebuie să urmaţi intrucţiunile din email, pentru a confirma că acest cont este într-adevăr al dvs.',
'throttled-mailpassword'     => 'O parolă a fost deja trimisă în {{PLURAL:$1|ultima oră|ultimele $1 ore}}. Pentru a preveni abuzul, se poate trimite doar o parolă la {{PLURAL:$2|o oră|$2 ore}}.',
'mailerror'                  => 'Eroare la trimitere e-mail: $1',
'acct_creation_throttle_hit' => 'Ne pare rău, aţi creat deja $1 conturi de utilizator. Nu mai puteţi crea altul.',
'emailauthenticated'         => 'Adresa de email a fost autentificată la $1.',
'emailnotauthenticated'      => 'Adresa de email <strong>nu este autentificată încă</strong>. Nici un email nu va fi trimis pentru nici una din întrebuinţările următoare.',
'noemailprefs'               => '<strong>Nu a fost specificată o adresă email</strong>, următoarele nu vor funcţiona.',
'emailconfirmlink'           => 'Confirmaţi adresa dvs. de email',
'invalidemailaddress'        => 'Adresa de email nu a putut fi acceptată pentru că pare a avea un format invalid. Vă rugăm să reintroduceţi o adresă bine formatată sau să goliţi acel câmp.',
'accountcreated'             => 'Contul a fost creat.',
'accountcreatedtext'         => 'Contul utilizatorului pentru $1 a fost creat.',
'createaccount-title'        => 'Creare de cont la {{SITENAME}}',
'createaccount-text'         => 'Cineva ($1) a creat un cont pentru $2 la {{SITENAME}}
($4). Parola pentru "$2" este "$3". Este de dorit să vă autentificaţi şi să schimbaţi parola cât mai repede.

Ignoraţi acest mesaj, dacă acea creare a fost o greşeală.',
'loginlanguagelabel'         => 'Limba: $1',

# Password reset dialog
'resetpass'               => 'Resetează parola contului',
'resetpass_announce'      => 'Sunteţi autentificat cu un cod temporar trimis pe mail. Pentru a termina acţiunea de autentificare, trebuie să setaţi o parolă nouă aici:',
'resetpass_text'          => '<!-- Adaugă text aici -->',
'resetpass_header'        => 'Resetează parola',
'resetpass_submit'        => 'Setează parola şi autentifică',
'resetpass_success'       => 'Parola a fost schimbată cu succes! Autentificare în curs...',
'resetpass_bad_temporary' => 'Parola temporară nu este validă. Este posibil să vă fi schimbat deja parola cu succes sau să fi cerut o nouă parolă temporară.',
'resetpass_forbidden'     => 'Parolele nu pot fi schimbate pe acest wiki',
'resetpass_missing'       => 'Nu există date în formular.',

# Edit page toolbar
'bold_sample'     => 'Text aldin',
'bold_tip'        => 'Text aldin',
'italic_sample'   => 'Text cursiv',
'italic_tip'      => 'Text cursiv',
'link_sample'     => 'Titlul legăturii',
'link_tip'        => 'Legătură internă',
'extlink_sample'  => 'http://www.exemplu.ro titlul legăturii',
'extlink_tip'     => 'Legătură externă (nu uitaţi prefixul http://)',
'headline_sample' => 'Text de titlu',
'headline_tip'    => 'Titlu de nivel 2',
'math_sample'     => 'Introduceţi formula aici',
'math_tip'        => 'Formulă matematică (LaTeX)',
'nowiki_sample'   => 'Introduceţi text neformatat aici',
'nowiki_tip'      => 'Ignoră formatarea wiki',
'image_sample'    => 'Exemplu.jpg',
'image_tip'       => 'Inserează imagine',
'media_sample'    => 'Exemplu.ogg',
'media_tip'       => 'Legătură la fişier media',
'sig_tip'         => 'Semnătura dvs. datată',
'hr_tip'          => 'Linie orizontală (folosiţi-o cumpătat)',

# Edit pages
'summary'                   => 'Sumar',
'subject'                   => 'Subiect / titlu',
'minoredit'                 => 'Aceasta este o editare minoră',
'watchthis'                 => 'Urmăreşte această pagină',
'savearticle'               => 'Salvează pagina',
'preview'                   => 'Previzualizare',
'showpreview'               => 'Arată previzualizare',
'showlivepreview'           => 'Previzualizare live',
'showdiff'                  => 'Arată diferenţele',
'anoneditwarning'           => "'''Avertizare:''' Nu sunteţi logat(ă). Adresa IP vă va fi înregistrată în istoricul acestei pagini.",
'missingsummary'            => "'''Atenţie:''' Nu aţi completat caseta \"descriere modificări\". Dacă apăsaţi din nou butonul \"salvează pagina\" modificările vor fi salvate fără descriere.",
'missingcommenttext'        => 'Vă rugăm să introduceţi un comentariu.',
'missingcommentheader'      => "'''Atenţie:''' Nu aţi furnizat un titlu/subiect pentru acest comentariu. Dacă daţi click pe \"Salvaţi din nou\", modificarea va fi salvată fără titlu.",
'summary-preview'           => 'Previzualizare descriere',
'subject-preview'           => 'Previzualizare subiect/titlu:',
'blockedtitle'              => 'Utilizatorul este blocat',
'blockedtext'               => "Adresa IP sau contul de utilizator v-a fost blocat de către $1 pentru următorul motiv:

:''$2''

Adresa dumneavoastră IP este $3, iar identificatorul de blocare este #$5.<br />
Blocarea expiră la: $6<br />
Intended blockee: $7

Dacă nu înţelegeţi motivul blocării vă invităm să consultaţi [[{{MediaWiki:Policy-url}}|regulamentul de blocare]]. În cazul în care vă consideraţi nedreptăţit îl puteţi contacta pe $1 sau pe unul dintre ceilalţi [[{{MediaWiki:Grouppage-sysop}}|administratori]] pentru a explica situaţia.

'''Blocarea nu se referă la citirea paginilor {{SITENAME}}, ci doar la modificarea lor.''' 

Pentru a afla cînd vă expiră blocarea căutaţi-vă numele de utilizator sau adresa IP în [[Special:Ipblocklist|lista de utilizatori blocaţi]].",
'autoblockedtext'           => 'Această adresă IP a fost blocată automat deoarece a fost folosită de către un alt utilizator, care a fost blocat de $1.
Motivul blocării este:

:\'\'$2\'\'

Blcoarea expiră la: $6

Puteţi contacta pe $1 sau pe unul dintre ceilalţi
[[{{MediaWiki:Grouppage-sysop}}|administratori]] pentru a discuta situaţia.

Nu veţi putea folosi opţiunea de "trimite e-mail" decât dacă aveţi înregistrată o adresă de e-mail validă la [[Special:Preferences|preferinţe]] şi nu sunteţi blocat la folosirea ei.

Identificatorul dumneavoastră de blocare este $5. Vă rugăm să îl includeţi în orice cerere pe care o faceţi.',
'blockednoreason'           => 'nici un motiv oferit',
'blockedoriginalsource'     => "Sursa pentru '''$1''' apare mai jos:",
'blockededitsource'         => "Textul '''modificărilor tale''' la  '''$1''' este redat mai jos:",
'whitelistedittitle'        => 'Este necesară autentificarea pentru a edita',
'whitelistedittext'         => 'Trebuie să $1 pentru a edita articole.',
'whitelistreadtitle'        => 'Este necesară autentificarea pentru a citi',
'whitelistreadtext'         => 'Trebuie să te [[Special:Userlogin|autentifici]] pentru a citi articole.',
'whitelistacctitle'         => 'Nu ai dreptul de a crea conturi',
'whitelistacctext'          => 'Trebuie să te [[Special:Userlogin|autentifici]] şi să ai permisiunile corecte pentru a crea conturi.',
'confirmedittitle'          => 'Pentru a edita e necesară confirmarea adresei de e-mail',
'confirmedittext'           => 'Trebuie să vă confirmaţi adresa de e-mail înainte de a edita pagini. Vă rugăm să vă setaţi şi să vă validaţi adresa de e-mail cu ajutorul [[Special:Preferences|preferinţelor utilizatorului]].',
'nosuchsectiontitle'        => 'Nu există o astfel de secţiune',
'nosuchsectiontext'         => 'Aţi încercat să modificaţi o secţiune care nu există. Deoarece nu există secţiunea $1, modificarea nu va fi salvată.',
'loginreqtitle'             => 'Necesită autentificare',
'loginreqlink'              => 'autentifici',
'loginreqpagetext'          => 'Trebuie să te $1 pentru a vizualiza alte pagini.',
'accmailtitle'              => 'Parola a fost trimisă.',
'accmailtext'               => "Parola pentru '$1' a fost trimisă la $2.",
'newarticle'                => '(Nou)',
'newarticletext'            => 'Ai ajuns la o pagină care nu există. Pentru a o crea, începe să scrii în caseta de mai jos (vezi [[{{MediaWiki:Helppage}}|pagina de ajutor]] pentru mai multe informaţii). Dacă ai ajuns aici din greşeală, întoarce-te folosind controalele browser-ului tău',
'anontalkpagetext'          => "---- ''Aceasta este pagina de discuţii pentru un utilizator care nu şi-a creat un cont încă, sau care nu s-a autentificat. De aceea trebuie să folosim adresă IP pentru a identifica această persoană. O adresă IP poate fi folosită în comun de mai mulţi utilizatori. Dacă sunteţi un astfel de utilizator şi credeţi că vă sunt adresate mesaje irelevante, vă rugăm să [[Special:Userlogin|vă creaţi un cont sau să vă autentificaţi]] pentru a evita confuzii cu alţi utilizatori anonimi în viitor.''",
'noarticletext'             => '{{SITENAME}} nu are încă un articol referitor la această pagină. Puteţi [[Special:Search/{{PAGENAME}}|căuta titlul paginii cu acest nume]] în alte pagini sau [{{fullurl:{{FULLPAGENAME}}|action=edit}} edita această pagină].',
'clearyourcache'            => "'''Notă:''' După salvare, trebuie să treceţi peste cache-ul browser-ului pentru a vedea modificările. '''Mozilla/Safari/Konqueror:''' ţineţi apăsat ''Shift'' în timp ce apăsaţi ''Reload'' (sau apăsaţi ''Ctrl-Shift-R''), '''IE:''' apăsaţi ''Ctrl-F5'', '''Opera:''' apăsaţi ''F5''.",
'usercssjsyoucanpreview'    => "<strong>Sfat:</strong> Foloseşte butonul 'Arată previzualizare' pentru a testa noul tău css/js înainte de a salva.",
'usercsspreview'            => "'''Reţine că urmăreşti doar o previzualizare a css-ului tău de utilizator, acesta nu este încă salvat!'''",
'userjspreview'             => "'''Reţine că urmăreşti doar un test/o previzualizare a javascript-ului tău de utilizator, acesta nu este încă salvat!'''",
'userinvalidcssjstitle'     => '<b>Avertizare:</b> Nu există skin "$1". Aminteşte-ţi că paginile .css and .js specifice utilizatorilor au titluri care încep cu literă mică, de exemplu {{ns:user}}:Foo/monobook.css în comparaţie cu {{ns:user}}:Foo/Monobook.css.',
'updated'                   => '(Actualizat)',
'note'                      => '<strong>Notă:</strong>',
'previewnote'               => 'Aceasta este doar o previzualizare! Pentru a salva pagina în forma actuală, descrieţi succint modificările efectuate şi apăsaţi butonul <strong>Salvează pagina</strong>.',
'previewconflict'           => 'Această pre-vizualizare reflectă textul din caseta de sus, respectiv felul în care va arăta articolul dacă alegeţi să-l salvaţi acum.',
'session_fail_preview'      => '<strong>Ne pare rău! Nu am putut procesa modificarea dumneavoastră din cauza pierderii datelor sesiunii. Vă rugăm să încercaţi din nou. Dacă tot nu funcţionează, încercaţi să închideţi sesiunea şi să vă autentificaţi din nou.</strong>',
'session_fail_preview_html' => "<strong>Ne pare rău! Modificările tale nu au putut fi procesate din cauza pierderii datelor sesiunii.</strong> 

''Deoarece acest wiki are activat raw HTML, previzualizarea este ascunsă ca măsură de precauţie împotriva atacurilor JavaScript.''

<strong>Dacă această încercare de modificare este legitimă, te rugăm să încerci din nou. Dacă nu funcţionează nici în acest fel, închide sesiunea şi încearcă să te autentifici din nou.</strong>",
'editing'                   => 'modificare $1',
'editinguser'               => 'modificare $1',
'editingsection'            => 'modificare $1 (secţiune)',
'editingcomment'            => 'modificare $1 (comentariu)',
'editconflict'              => 'Conflict de modificare: $1',
'explainconflict'           => 'Altcineva a modificat această pagină de când ai început să o editezi. Caseta de text de sus conţine pagina aşa cum este ea acum (după editarea celeilalte persoane). Pagina cu modificările tale (aşa cum ai încercat să o salvezi) se află în caseta de jos. Va trebui să editezi manual caseta de sus pentru a reflecta modificările pe care tocmai le-ai făcut în cea de jos. <b>Numai</b> textul din caseta de sus va fi salvat atunci când vei apăsa pe "Salvează pagina".<br />',
'yourtext'                  => 'Textul tău',
'storedversion'             => 'Versiunea curentă',
'nonunicodebrowser'         => '<strong>ATENŢIE: Browser-ul dumneavoastră nu este compilant unicode, vă rugăm să îl schimbaţi înainte de a începe modificarea unui articol.</strong>',
'editingold'                => '<strong>ATENŢIE! Modifici o variantă mai veche a acestei pagini! Orice modificări care s-au făcut de la această versiune şi până la cea curentă se vor pierde!</strong>',
'yourdiff'                  => 'Diferenţe',
'copyrightwarning'          => "<!-- Gol deocamdată. Avertismentul se află în MediaWiki:Summary -->
Please note that all contributions to {{SITENAME}} are considered to be released under the $2 (see $1 for details). If you don't want your writing to be edited mercilessly and redistributed at will, then don't submit it here.<br /> You are also promising us that you wrote this yourself, or copied it from a public domain or similar free resource. <strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>",
'copyrightwarning2'         => 'Reţineţi că toate contribuţiile la {{SITENAME}} sunt considerate ca respectând $2 (vezi $1 pentru detalii).<br />
Dacă nu doriţi ca ceea ce scrieţi să fie modificat fără milă şi redistribuit în voie, atunci nu trimiteţi materialele respective aici. Ceea ce aţi scris a fost compoziţie proprie sau copie dintr-o resursă publică sau liberă (vedeţi $1 pentru detalii).<br />',
'longpagewarning'           => '<strong>ATENŢIE! Conţinutul acestei pagini are $1 KB; unele browsere au probleme la modificarea paginilor în jur de 32 KB sau mai mari. Te rugăm să iei în considerare posibilitatea de a împărţi pagina în mai multe secţiuni.</strong>',
'longpageerror'             => '<strong>EROARE: Textul pe care vrei să-l salvezi are $1 kilobytes,
ceea ce înseamnă mai mult decât maximum de $2 kilobytes. Salvarea nu este posibilă.</strong>',
'readonlywarning'           => '<strong>ATENŢIE! Baza de date a fost blocată pentru întreţinere, deci nu vei putea să salvezi editările în acest moment. Poţi copia textul într-un fişier text local pentru a modifica conţinutul în {{SITENAME}} când va fi posibil.</strong>',
'protectedpagewarning'      => '<strong>ATENŢIE! Această pagină a fost protejată la scriere şi numai utilizatorii cu privilegii de administrator o pot modifica. Vă rugăm urmaţi sugestiile [[Special:Protectedpages|despre pagini protejate]] când modificaţi.</strong>',
'semiprotectedpagewarning'  => "'''Atenţie:''' Această pagină poate fi modificată numai de utilizatorii înregistraţi.",
'cascadeprotectedwarning'   => "'''Atenţie:''' Această pagină a fost blocată astfel încât numai administratorii o pot modifica, deoarece este inclusă în {{PLURAL:$1|următoarea pagină protejată|următoarele pagini protejate}} în cascadă:",
'templatesused'             => 'Formate folosite în această pagină:',
'templatesusedpreview'      => 'Formate utilizate în această previzualizare:',
'templatesusedsection'      => 'Formate utilizate în această secţiune:',
'template-protected'        => '(protejat)',
'template-semiprotected'    => '(semi-protejat)',
'edittools'                 => '<!-- Acest text va apărea după caseta de editare şi formularele de trimitere fişier. -->',
'nocreatetitle'             => 'Creare de pagini limitată',
'nocreatetext'              => 'Acest site a restricţionat abilitatea de creare a paginilor noi. Puteţi edita o pagină deja existentă sau puteţi să vă [[Special:Userlogin|autentificaţi/creaţi]] un cont de utilizator.',
'nocreate-loggedin'         => 'Nu ai permisiunea să creezi pagini noi pe această wiki.',
'permissionserrors'         => 'Erori de permisiune',
'permissionserrorstext'     => 'Nu aveţi permisiune pentru a face acest lucru, din următoarele {{PLURAL:$1|motiv|motive}}:',

# "Undo" feature
'undo-success' => 'Modificarea poate fi anulată. Verificaţi diferenţa de dedesupt şi apoi salvaţi pentru a termina anularea modificării.',
'undo-summary' => 'Anularea modificării $1 făcute de [[Special:Contributions/$2|$2]] ([[Discuţie Utilizator:$2|discuţie]])',

# Account creation failure
'cantcreateaccounttitle' => 'Crearea contului nu poate fi realizată',

# History pages
'viewpagelogs'        => 'Vezi rapoartele pentru această pagină',
'nohistory'           => 'Nu există istoric pentru această pagină.',
'revnotfound'         => 'Versiunea nu a fost găsită',
'revnotfoundtext'     => 'Versiunea mai veche a paginii pe care aţi cerut-o nu a fost găsită. Vă rugăm să verificaţi legătura pe care aţi folosit-o pentru a accesa această pagină.',
'loadhist'            => 'Încarc istoria versiunilor',
'currentrev'          => 'Versiunea curentă',
'revisionasof'        => 'Versiunea de la data $1',
'revision-info'       => 'Revizia pentru $1; $2',
'previousrevision'    => '←Versiunea anterioară',
'nextrevision'        => 'Versiunea următoare →',
'currentrevisionlink' => 'afişează versiunea curentă',
'cur'                 => 'actuală',
'next'                => 'următoarea',
'last'                => 'prec',
'orig'                => 'orig',
'page_first'          => 'prim',
'page_last'           => 'ultim',
'histlegend'          => 'Legendă: (actuală) = diferenţe faţă de versiunea curentă,
(prec) = diferenţe faţă de versiunea precedentă, M = modificare minoră',
'deletedrev'          => '[şters]',
'histfirst'           => 'Primele',
'histlast'            => 'Ultimele',
'historysize'         => '({{PLURAL:$1|1 octet|$1 octeţi}})',
'historyempty'        => '(gol)',

# Revision feed
'history-feed-title'          => 'Revizia istoricului',
'history-feed-description'    => 'Revizia istoricului pentru această pagină de pe wiki',
'history-feed-item-nocomment' => '$1 la $2', # user at time
'history-feed-empty'          => 'Pagina solicitată nu există.
E posibil să fi fost ştearsă sau redenumită.
Încearcă să [[Special:Search|cauţi]] pe wiki pentru pagini noi semnificative.',

# Revision deletion
'rev-deleted-comment'         => '(comentariu şters)',
'rev-deleted-user'            => '(nume de utilizator şters)',
'rev-deleted-event'           => '(intrare ştearsă)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Revizia acestei pagini a fost ştearsă din arhivele publice. Mai multe detalii la [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} deletion log].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">Revizia acestei pagini a fost ştearsă din arhivele publice.
Ca administrator la acest site poţi să o vezi; s-ar putea să găseşti mai multe detalii la [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} deletion log].
</div>',
'rev-delundel'                => 'arată/ascunde',
'revisiondelete'              => 'Şterge/recuperează revizii',
'revdelete-selected'          => '{{PLURAL:$2|Revizia aleasă|Reviziile alese}} pentru [[:$1]]:',
'revdelete-text'              => 'Reviziile şterse vor apărea în istoricul paginii, dar conţinutul lor nu va fi accesibil publicului.

Alţi administratori de pe acest wiki vor putea accesa conţinutul ascuns şi îl pot recupera prin aceeaşi interfaţă, dacă nu este impusă o altă restricţie de către operatorii sitului.',
'revdelete-legend'            => 'Setează restricţii pentru revizie:',
'revdelete-hide-text'         => 'Ascunde textul reviziei',
'revdelete-hide-comment'      => 'Ascunde descrierea modificării',
'revdelete-hide-user'         => 'Ascunde numele de utilizator/IP-ul editorului',
'revdelete-hide-restricted'   => 'Aplică aceste restricţii administratorilor şi celorlalţi',
'revdelete-hide-image'        => 'Ascunde conţinutul fişierului',
'revdelete-log'               => 'Comentariu log:',
'revdelete-submit'            => 'Aplică reviziilor selectate',
'revdelete-logentry'          => 'vizibilitatea reviziei pentru [[$1]] a fost modificată',

# History merging
'mergehistory-from'           => 'Pagina sursă:',
'mergehistory-into'           => 'Pagina destinaţie:',
'mergehistory-no-source'      => 'Pagina sursă $1 nu există.',
'mergehistory-no-destination' => 'Pagina de destinaţie $1 nu există.',

# Diffs
'history-title'           => 'Istoria reviziilor pentru "$1"',
'difference'              => '(Diferenţa dintre versiuni)',
'lineno'                  => 'Linia $1:',
'compareselectedversions' => 'Compară versiunile selectate',
'editundo'                => 'anulează',
'diff-multi'              => '({{plural:$1|O revizie intermediară neafişată|$1 revizii intermediare neafişate}})',

# Search results
'searchresults'         => 'Rezultatele căutării',
'searchresulttext'      => 'Pentru mai multe detalii despre căutarea în {{SITENAME}}, vezi [[Project:Căutare]].',
'searchsubtitle'        => 'Pentru căutarea "[[$1]]"',
'searchsubtitleinvalid' => 'Pentru căutarea "$1"',
'noexactmatch'          => "'''Pagina cu titlul \"\$1\" nu există.''' Poţi [[:\$1|crea această pagină]].",
'titlematches'          => 'Rezultate în titluri de articole',
'notitlematches'        => 'Nici un rezultat în titlurile articolelor',
'textmatches'           => 'Rezultate în textele articolelor',
'notextmatches'         => 'Nici un rezultat în textele articolelor',
'prevn'                 => 'anterioarele $1',
'nextn'                 => 'următoarele $1',
'viewprevnext'          => 'Vezi ($1) ($2) ($3).',
'showingresults'        => 'Mai jos apar <b>$1</b> rezultate începând cu #<b>$2</b>.',
'showingresultsnum'     => 'Mai jos apar <b>$3</b> rezultate începând cu #<b>$2</b>.',
'nonefound'             => "'''Notă''': căutările nereuşite sunt în general datorate căutării unor cuvinte prea comune care nu sunt indexate, sau cautărilor a mai multe cuvinte (numai articolele care conţin ''toate'' cuvintele specificate apar ca rezultate).",
'powersearch'           => 'Caută',
'powersearchtext'       => 'Caută în secţiunile:<br />
$1<br />
$2 Redirecţionări<br />
Căutări după $3 $9',
'searchdisabled'        => '<p>Ne pare rău! Căutarea după text a fost dezactivată temporar, din motive de performanţă. Între timp puteţi folosi căutarea prin Google mai jos, însă aceasta poate să dea rezultate învechite.</p>',

# Preferences page
'preferences'             => 'Preferinţe',
'mypreferences'           => 'preferinţe',
'prefs-edits'             => 'Număr de modificări:',
'prefsnologin'            => 'Neautentificat',
'prefsnologintext'        => 'Trebuie să fii [[Special:Userlogin|autentificat]] pentru a-ţi putea salva preferinţele.',
'prefsreset'              => 'Preferinţele au fost resetate.',
'qbsettings'              => 'Setări pentru bara rapidă',
'qbsettings-none'         => 'Fără',
'qbsettings-fixedleft'    => 'Fixă, în stânga',
'qbsettings-fixedright'   => 'Fixă, în dreapta',
'qbsettings-floatingleft' => 'Liberă',
'changepassword'          => 'Schimbă parola',
'skin'                    => 'Aspect',
'math'                    => 'Aspect formule',
'dateformat'              => 'Formatul datelor',
'datedefault'             => 'Nici o preferinţă',
'datetime'                => 'Data şi ora',
'math_failure'            => 'Nu s-a putut interpreta',
'math_unknown_error'      => 'eroare necunoscută',
'math_unknown_function'   => 'funcţie necunoscută',
'math_lexing_error'       => 'eroare lexicală',
'math_syntax_error'       => 'eroare de sintaxă',
'math_image_error'        => 'Conversiune în PNG eşuată',
'math_bad_tmpdir'         => 'Nu se poate crea sau nu se poate scrie în directorul temporar pentru formule matematice',
'math_bad_output'         => 'Nu se poate crea sau nu se poate scrie în directorul de ieşire pentru formule matematice',
'math_notexvc'            => 'Lipseşte executabilul texvc; vezi math/README pentru configurare.',
'prefs-personal'          => 'Date de utilizator',
'prefs-rc'                => 'Modificări recente şi cioturi',
'prefs-watchlist'         => 'Listă de urmărire',
'prefs-watchlist-days'    => 'Numărul de zile care apar în lista paginilor urmărite:',
'prefs-watchlist-edits'   => 'Numărul de editări care apar în lista extinsă a paginilor urmărite:',
'prefs-misc'              => 'Parametri diverşi',
'saveprefs'               => 'Salvează preferinţele',
'resetprefs'              => 'Resetează preferinţele',
'oldpassword'             => 'Parola veche',
'newpassword'             => 'Parola nouă',
'retypenew'               => 'Repetă parola nouă',
'textboxsize'             => 'Dimensiunile casetei de text',
'rows'                    => 'Rânduri:',
'columns'                 => 'Coloane',
'searchresultshead'       => 'Parametri căutare',
'resultsperpage'          => 'Numărul de rezultate per pagină',
'contextlines'            => 'Numărul de linii per rezultat',
'contextchars'            => 'Numărul de caractere per linie',
'recentchangesdays'       => 'Numărul de zile afişate în schimbări recente:',
'recentchangescount'      => 'Numărul de articole pentru schimbări recente:',
'savedprefs'              => 'Preferinţele tale au fost salvate.',
'timezonelegend'          => 'Fus orar',
'timezonetext'            => 'Introduceţi numărul de ore diferenţă între ora Dv. locală şi ora serverului (UTC, timp universal). Dacă vă aflaţi în România, diferenţa este 02:00 iarna şi 03:00 vara.',
'localtime'               => 'Ora locală',
'timezoneoffset'          => 'Diferenţa¹',
'servertime'              => 'Ora serverului',
'guesstimezone'           => 'Încearcă determinarea automată a diferenţei',
'allowemail'              => 'Activează email de la alţi utilizatori',
'defaultns'               => 'Caută în aceste secţiuni implicit:',
'default'                 => 'standard',
'files'                   => 'Fişiere',

# User rights
'userrights-lookup-user'      => 'Administrare grupuri de utilizatori',
'userrights-user-editname'    => 'Introdu un nume de utilizator:',
'editusergroup'               => 'Modificare grup de utilizatori',
'userrights-editusergroup'    => 'Modifică grupul de utilizatori',
'saveusergroups'              => 'Salvează grupul de utilizatori',
'userrights-groupsmember'     => 'Membru al:',
'userrights-groupsavailable'  => 'Grupuri disponibile:',
'userrights-groupshelp'       => 'Selectează grupurile din care doreşti ca utilizatorul să fie şters sau în care doreşti să fie adăugat. Grupurile deselectate nu se vor modifica. Poţi deselecta un grup cu CTRL + Clic stânga',
'userrights-reason'           => 'Motivul schimbării:',
'userrights-available-add'    => 'Poţi adăuga utilizatorii la $1.',
'userrights-available-remove' => 'Poţi muta utilizatorii din $1.',

# Groups
'group'               => 'Grup:',
'group-autoconfirmed' => 'Utilizatori autoconfirmaţi',
'group-bot'           => 'Roboţi',
'group-sysop'         => 'Administratori',
'group-bureaucrat'    => 'Birocraţi:',
'group-all'           => '(toţi)',

'group-autoconfirmed-member' => 'Utilizator autoconfirmat',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Birocrat',

'grouppage-bot'        => '{{ns:project}}:Boţi',
'grouppage-sysop'      => '{{ns:project}}:Administratori',
'grouppage-bureaucrat' => '{{ns:project}}:Birocraţi',

# User rights log
'rightslog'      => 'Raportul drepturilor de utilizator',
'rightslogtext'  => 'Acesta este un raport al modificărilor drepturilor utilizatorilor.',
'rightslogentry' => 'a schimbat pentru $1 apartenenţa la un grup de la $2 la $3',
'rightsnone'     => '(niciunul)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|modificare|modificări}}',
'recentchanges'                     => 'Schimbări recente',
'recentchangestext'                 => 'Schimbări recente ... (Log)',
'rcnote'                            => 'Mai jos se află ultimele <strong>$1</strong> modificări din ultimele <strong>$2</strong> zile sau din <strong>$3</strong>.',
'rcnotefrom'                        => 'Dedesubt sunt modificările de la <b>$2</b> (maxim <b>$1</b> de modificări sunt afişate - schimbă numărul maxim de linii alegând altă valoare mai jos).',
'rclistfrom'                        => 'Arată modificările începând de la $1',
'rcshowhideminor'                   => '$1 modificările minore',
'rcshowhidebots'                    => '$1 roboţii',
'rcshowhideliu'                     => '$1 utilizatorii autentificaţi',
'rcshowhideanons'                   => '$1 utilizatorii anonimi',
'rcshowhidepatr'                    => '$1 modificările patrulate',
'rcshowhidemine'                    => '$1 editările mele',
'rclinks'                           => 'Arată ultimele $1 modificări din ultimele $2 zile.<br />
$3',
'diff'                              => 'dif',
'hist'                              => 'ist',
'hide'                              => 'ascunde',
'show'                              => 'arată',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|utilizator|utilizatori}} care urmăresc]',
'rc_categories'                     => 'Limitează la categoriile (separate prin "|")',
'rc_categories_any'                 => 'Oricare',
'newsectionsummary'                 => '/* $1 */ secţiune nouă',

# Recent changes linked
'recentchangeslinked'       => 'Modificări corelate',
'recentchangeslinked-title' => 'Modificări legate de $1',

# Upload
'upload'                      => 'Trimite fişier',
'uploadbtn'                   => 'Trimite fişier',
'reupload'                    => 'Re-trimite',
'reuploaddesc'                => 'Întoarcere la formularul de trimitere.',
'uploadnologin'               => 'Nu sunteţi autentificat',
'uploadnologintext'           => 'Trebuie să fiţi [[Special:Userlogin|autentificat]] pentru a putea trimite fişiere.',
'upload_directory_read_only'  => 'Directorul de trimitere ($1) nu are drepturi de scriere de către server.',
'uploaderror'                 => 'Eroare la trimitere fişier',
'uploadtext'                  => "<strong>STOP!</strong> Înainte de a trimite un fişier aici, te rugăm să citeşti şi să respecţi [[{{MediaWiki:Policy-url}}|politica de utilizare a imaginilor]].<br />
Pentru a vizualiza sau căuta imagini deja trimise, mergi la [[Special:Imagelist|lista de imagini]]. Fişierele noi şi cele şterse sunt contorizate pe pagina [[Special:Log/upload|raport de trimiteri]].<br />
Foloseşte formularul de mai jos pentru a trimite imagini noi pe care le vei putea folosi pentru a ilustra articolele. În majoritatea browserelor vei vedea un buton \"Răsfoire...\" (sau \"Browse...\") care va deschide fereastra standard dialog a sistemului tău de operare pentru alegerea de fişiere. Când alegei un fişier în acest fel, caseta de dialog se va completa cu calea locală către acesta. Este de asemenea necesar să bifezi căsuţa asociată textului în care confirmi că nu violezi nici un drept de autor trimiţând această imagine. În final, apasă pe butonul \"Trimite\" pentru a trimite efectiv fişierul. Această operaţiune poate dura, mai ales dacă ai o legătură lentă la Internet.<br />
Formatele preferate sunt JPEG pentru imagini fotografice, PNG pentru desene şi alte imagini cu contururi clare şi OGG pentru fişiere de sunet. Te rugăm să foloseşti nume explicite pentru fişiere ca să eviţi confuziile. Pentru a include o imagine într-un articol, foloseşte o legătură de forma<br />
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Fişier.jpg]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Fişier.png|text alternativ]]</nowiki>'''
Pentru a include un fişier de sunet într-un articol, foloseşti o legătură de forma
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fişier.ogg]]</nowiki>'''
Te rugăm reţine că, la fel ca şi în cazul celorlalte secţiuni din {{SITENAME}}, alte persoane pot edita sau şterge fişierele pe care le trimiţi dacă e în interesul enciclopediei, şi ţi se poate chiar bloca accesul la trimiterea de fişiere dacă abuzezi de sistem.",
'uploadlog'                   => 'Raportul fişierelor trimise',
'uploadlogpage'               => 'Raportul fişierelor trimise',
'uploadlogpagetext'           => 'Găseşti mai jos lista ultimelor fişiere trimise.',
'filename'                    => 'Nume fişier',
'filedesc'                    => 'Descriere fişier',
'fileuploadsummary'           => 'Descriere:',
'filestatus'                  => 'Statutul drepturilor de autor',
'filesource'                  => 'Sursa',
'uploadedfiles'               => 'Fişiere trimise',
'ignorewarning'               => 'Ignoră avertismentul şi salvează fişierul.',
'ignorewarnings'              => 'Ignoră orice avertismente.',
'minlength1'                  => 'Numele fişierelor trebuie să fie cel puţin o literă.',
'illegalfilename'             => 'Numele fişierului "$1" conţine caractere care nu sunt permise în titlurile paginilor. Vă rugăm redenumiţi fişierul şi încercaţi să îl încărcaţi din nou.',
'badfilename'                 => 'Numele imaginii a fost schimbat; noul nume este "[[:$1]]".',
'filetype-badmime'            => 'Nu este permisă încărcarea de fişiere de tipul MIME "$1".',
'filetype-badtype'            => "'''\".\$1\"''' este un tip de fişier neacceptat.
: Lista tipurilor de fişiere permise: \$2",
'filetype-missing'            => 'Fişierul nu are extensie (precum ".jpg").',
'large-file'                  => 'Este recomandat ca fişierele să nu fie mai mari de $1; acest fişier are $2.',
'largefileserver'             => 'Fişierul este mai mare decât este configurat serverul să permită.',
'emptyfile'                   => 'Fişierul pe care l-aţi încărcat pare a fi gol. Aceasta poate fi datorită unei greşeli în numele fişierului. Verificaţi dacă într-adevăr doriţi să încărcaţi acest fişier.',
'fileexists'                  => 'Un fişier cu acelaşi nume există deja, vă rugăm verificaţi $1 dacă nu sunteţi sigur dacă doriţi să îl modificaţi.',
'fileexists-thumb'            => "<center>'''Imagine existentă'''</center>",
'fileexists-forbidden'        => 'Un fişier cu acest nume există deja; mergeţi înapoi şi încărcaţi acest fişier sub un nume nou. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Un fişier cu acest nume există deja în magazia de imagini comune; mergeţi înapoi şi încărcaţi fişierul sub un nou nume. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Fişierul a fost trimis',
'uploadwarning'               => 'Avertizare la trimiterea fişierului',
'savefile'                    => 'Salvează fişierul',
'uploadedimage'               => 'a trimis [[$1]]',
'overwroteimage'              => 'încărcat o versiune nouă a fişierului "[[$1]]"',
'uploaddisabled'              => 'Ne pare rău, trimiterea de imagini este dezactivată.',
'uploaddisabledtext'          => 'Încărcarea de fişiere este dezactivată pe acest wiki.',
'uploadscripted'              => 'Fişierul conţine HTML sau cod script care poate fi interpretat în mod eronat de un browser.',
'uploadcorrupt'               => 'Fişierul este corupt sau are o extensie incorectă. Verifică fişierul şi trimite-l din nou.',
'uploadvirus'                 => 'Fişierul conţine un virus! Detalii: $1',
'sourcefilename'              => 'Nume fişier sursă',
'destfilename'                => 'Nume fişier destinaţie',
'watchthisupload'             => 'Urmăreşte această pagină',
'filewasdeleted'              => 'Un fişier cu acest nume a fost anterior încărcat şi apoi şters. Ar trebui să verificaţi $1 înainte să îl încărcaţi din nou.',

'upload-proto-error'     => 'Protocol incorect',
'upload-file-error'      => 'Eroare internă',
'upload-file-error-text' => 'A apărut o eroare internă la crearea unui fişier temporar pe server. Vă rugăm să contactaţi un administrator.',
'upload-misc-error'      => 'Eroare de încărcare necunoscută',
'upload-misc-error-text' => 'A apărut o eroare necunoscută în timpul încărcării. Vă rugăm să verificaţi dacă adresa URL este validă şi accesibilă şi îcnercaţi din nou. Dacă problema persistă, contactaţi un administrator.',

'license'            => 'Licenţiere',
'nolicense'          => 'Nici una selectată',
'license-nopreview'  => '(Previzualizare indisponibilă)',
'upload_source_url'  => ' (un URL valid, accesibil public)',
'upload_source_file' => ' (un fişier de pe computerul tău)',

# Image list
'imagelist'                 => 'Lista imaginilor',
'imagelisttext'             => 'Mai jos se află lista a $1 imagini ordonate $2.',
'getimagelist'              => 'încarc lista de imagini',
'ilsubmit'                  => 'Caută',
'showlast'                  => 'Arată ultimele $1 imagini ordonate $2.',
'byname'                    => 'după nume',
'bydate'                    => 'după dată',
'bysize'                    => 'după mărime',
'imgdelete'                 => 'şterge',
'imgdesc'                   => 'desc',
'imgfile'                   => 'fişier',
'filehist'                  => 'Istoricul fişierului',
'filehist-deleteall'        => 'şterge tot',
'filehist-deleteone'        => 'şterge',
'filehist-revert'           => 'revenire',
'filehist-current'          => 'curentă',
'filehist-datetime'         => 'Dată/Timp',
'filehist-user'             => 'Utilizator',
'filehist-dimensions'       => 'Dimensiuni',
'filehist-filesize'         => 'Mărimea fişierului',
'filehist-comment'          => 'Comentariu',
'imagelinks'                => 'Legăturile imaginii',
'linkstoimage'              => 'Următoarele pagini leagă la această imagine:',
'nolinkstoimage'            => 'Nici o pagină nu se leagă la această imagine.',
'sharedupload'              => 'Acest fişier transferat (upload) poate fi folosit în comun de către alte proiecte.',
'shareduploadwiki'          => 'Vă rugăm citiţi [$1 pagina de descriere a fişierului] pentru alte informaţii.',
'shareduploadwiki-linktext' => 'pagina descriptivă a fişierului',
'noimage'                   => 'Nu există nici un fişier cu acest nume, puteţi să îl $1.',
'noimage-linktext'          => 'trimiteţi',
'uploadnewversion-linktext' => 'Încarcă o versiune nouă a acestui fişier',
'imagelist_date'            => 'Data',
'imagelist_name'            => 'Nume',
'imagelist_user'            => 'Utilizator',
'imagelist_size'            => 'Mărime (octeţi)',
'imagelist_description'     => 'Descriere',
'imagelist_search_for'      => 'Caută imagine după nume:',

# File reversion
'filerevert'                => 'Revenire $1',
'filerevert-comment'        => 'Comentariu:',
'filerevert-defaultcomment' => 'Revenire la versiunea din $2, $1',
'filerevert-submit'         => 'Revenire',

# File deletion
'filedelete'            => 'Şterge $1',
'filedelete-legend'     => 'Şterge fişierul',
'filedelete-intro'      => "Ştergi '''[[Media:$1|$1]]'''.",
'filedelete-comment'    => 'Comentariu:',
'filedelete-submit'     => 'Şterge',
'filedelete-success'    => "'''$1''' a fost şters.",
'filedelete-nofile'     => "'''$1''' nu există pe acest sit.",
'filedelete-nofile-old' => "Nu există nicio versiune arhivată a '''$1''' cu atributele specificate.",

# MIME search
'mimesearch' => 'Căutare MIME',
'mimetype'   => 'Tip MIME:',
'download'   => 'descarcă',

# Unwatched pages
'unwatchedpages' => 'Pagini neurmărite',

# List redirects
'listredirects' => 'Lista de redirecţionări',

# Unused templates
'unusedtemplates'     => 'Formate neutilizate',
'unusedtemplatestext' => 'Lista de mai jos cuprinde toate formatele care nu sînt incluse în nici o altă pagină. Înainte de a le şterge asiguraţi-vă că într-adevăr nu există legături dinspre alte pagini.',
'unusedtemplateswlh'  => 'alte legături',

# Random page
'randompage'         => 'Pagină aleatorie',
'randompage-nopages' => 'Nu există pagini în acest spaţiu de nume.',

# Random redirect
'randomredirect'         => 'Redirecţionare aleatorie',
'randomredirect-nopages' => 'Nu există redirecţionări în acest spaţiu de nume.',

# Statistics
'statistics'             => 'Statistici',
'sitestats'              => 'Statisticile sitului {{SITENAME}}',
'userstats'              => 'Statistici legate de utilizatori',
'sitestatstext'          => 'Există un număr total de <b>$1</b> pagini în baza de date.
Acest număr include paginile de "discuţii", paginile despre {{SITENAME}}, pagini minimale ("cioturi"), pagini de redirecţionare şi altele care probabil că nu intră de fapt în categoria articolelor reale.
În afară de acestea, există <b>$2</b> pagini care sunt probabil articole (numărate automat, în funcţie strict de mărime).<br />

<b>$8</b> pagini au fost transferate (upload).

În total au fost <b>$3</b> vizite (accesări) şi <b>$4</b> modificări de la lansarea acestei wiki.
În medie rezultă <b>$5</b> modificări per pagină sau <b>$6</b> vizualizări la fiecare modificare.

Mărimea [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] este <b>$7</b>.',
'userstatstext'          => "Există un număr de '''$1''' utilizatori înregistraţi. Dintre aceştia '''$2''' (sau '''$4%''') sunt administratori (vezi '''$3''').",
'statistics-mostpopular' => 'Paginile cele mai vizualizate',

'disambiguations'     => 'Pagini de dezambiguizare',
'disambiguationspage' => 'Template:Dezambiguizare',

'doubleredirects'     => 'Redirecţionări duble',
'doubleredirectstext' => '<b>Atenţie:</b> Această listă poate conţine articole care nu sunt în fapt duble rediriecţionări. Acest lucru înseamnă de obicei că există text adiţional sub primul #REDIRECT.<br />',

'brokenredirects'        => 'Redirecţionări greşite',
'brokenredirectstext'    => 'Următoarele redirecţionări conduc spre articole inexistente.',
'brokenredirects-edit'   => '(modifică)',
'brokenredirects-delete' => '(şterge)',

'withoutinterwiki'        => 'Pagini fără legături interwiki',
'withoutinterwiki-header' => 'Următoarele pagini nu se leagă la versiuni ale lor în alte limbi:',

'fewestrevisions' => 'Articole cu cele mai puţine revizii',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|un octet|$1 octeţi}}',
'ncategories'             => '{{PLURAL:$1|o categorie|$1 categorii}}',
'nlinks'                  => '{{PLURAL:$1|o legătură|$1 legături}}',
'nmembers'                => '{{PLURAL:$1|un membru|$1 membri}}',
'nrevisions'              => '{{PLURAL:$1|o revizie|$1 revizii}}',
'nviews'                  => '{{PLURAL:$1|o accesare|$1 accesări}}',
'specialpage-empty'       => 'Această pagină este goală.',
'lonelypages'             => 'Pagini orfane',
'lonelypagestext'         => 'La următoarele pagini nu se leagă nici o altă pagină din acest wiki.',
'uncategorizedpages'      => 'Pagini necategorizate',
'uncategorizedcategories' => 'Categorii necategorizate',
'uncategorizedimages'     => 'Imagini necategorizate',
'uncategorizedtemplates'  => 'Formate necategorizate',
'unusedcategories'        => 'Categorii neutilizate',
'unusedimages'            => 'Pagini neutilizate',
'popularpages'            => 'Pagini populare',
'wantedcategories'        => 'Categorii dorite',
'wantedpages'             => 'Pagini dorite',
'mostlinked'              => 'Cele mai căutate articole',
'mostlinkedcategories'    => 'Cele mai căutate categorii',
'mostlinkedtemplates'     => 'Cele mai folosite formate',
'mostcategories'          => 'Articole cu cele mai multe categorii',
'mostimages'              => 'Cele mai căutate imagini',
'mostrevisions'           => 'Articole cu cele mai multe revizuiri',
'allpages'                => 'Toate paginile',
'prefixindex'             => 'Afişare articole începând de la',
'shortpages'              => 'Pagini scurte',
'longpages'               => 'Pagini lungi',
'deadendpages'            => 'Pagini fără legături',
'deadendpagestext'        => 'Următoarele pagini nu se leagă de alte pagini din acest wiki.',
'protectedpages'          => 'Pagini protejate',
'protectedpagestext'      => 'Următoarele pagini sunt protejate la mutare sau editare',
'protectedpagesempty'     => 'Nu există pagini protejate',
'listusers'               => 'Lista de utilizatori',
'specialpages'            => 'Pagini speciale',
'spheading'               => 'Pagini speciale pentru toţi utilizatorii',
'restrictedpheading'      => 'Pagini speciale restricţionate',
'rclsub'                  => '(cu pagini legate de la "$1")',
'newpages'                => 'Pagini noi',
'newpages-username'       => 'Nume de utilizator:',
'ancientpages'            => 'Cele mai vechi articole',
'intl'                    => 'Legături între limbi',
'move'                    => 'Mută',
'movethispage'            => 'Mută această pagină',
'unusedimagestext'        => '<p>Te rugăm ţine cont de faptul că alte situri, inclusiv alte versiuni de limbă {{SITENAME}} pot să aibă legături aici fără ca aceste pagini să fie listate aici - această listă se referă strict la {{SITENAME}} în română.</p>',
'unusedcategoriestext'    => 'Următoarele categorii de pagini există şi totuşi nici un articol sau categorie nu le foloseşte.',
'notargettitle'           => 'Lipsă ţintă',
'notargettext'            => 'Nu ai specificat nici o pagină sau un utilizator ţintă pentru care să se efectueze această operaţiune.',

# Book sources
'booksources'               => 'Surse de cărţi',
'booksources-search-legend' => 'Caută surse pentru cărţi',

'categoriespagetext' => 'Următoarele categorii există în wiki.',
'data'               => 'Data',
'userrights'         => 'Administrarea drepturilor de utilizator',
'groups'             => 'Grupuri de utilizatori',
'alphaindexline'     => '$1 către $2',
'version'            => 'Versiune',

# Special:Log
'specialloguserlabel'  => 'Utilizator:',
'speciallogtitlelabel' => 'Titlu:',
'log'                  => 'Rapoarte',
'all-logs-page'        => 'Toate jurnalele',
'alllogstext'          => 'Afişare combinată a încărcărilor, ştergerilor, protecţiilor, blocărilor şi a rapoartelor administratorilor. Puteţi limita vizualizarea selectând tipul raportului, numele de utilizator sau pagina afectată.',
'logempty'             => 'Nici o înregistrare în raport.',
'log-title-wildcard'   => 'Caută titluri care încep cu acest text',

# Special:Allpages
'nextpage'          => 'Pagina următoare ($1)',
'prevpage'          => 'Pagina anterioară ($1)',
'allpagesfrom'      => 'Afişează paginile pornind de la:',
'allarticles'       => 'Toate articolele',
'allinnamespace'    => 'Toate paginile (spaţiu de nume $1)',
'allnotinnamespace' => 'Toate paginile (în afara spaţiului de nume $1)',
'allpagesprev'      => 'Anterior',
'allpagesnext'      => 'Următor',
'allpagessubmit'    => 'Trimite',
'allpagesprefix'    => 'Afişează paginile cu prefix:',
'allpagesbadtitle'  => 'Titlul paginii este nevalid sau conţine un prefix inter-wiki. Este posibil să conţină unul sau mai multe caractere care nu pot fi folosite în titluri.',
'allpages-bad-ns'   => '{{SITENAME}} nu are spaţiul de nume "$1".',

# Special:Listusers
'listusersfrom'      => 'Afişează utilizatori începând cu:',
'listusers-submit'   => 'Arată',
'listusers-noresult' => 'Nici un utilizator găsit.',

# E-mail user
'mailnologin'     => 'Nu există adresă de trimitere',
'mailnologintext' => 'Trebuie să fii [[Special:Userlogin|autentificat]] şi să ai o adresă validă de e-mail în [[Special:Preferences|preferinţe]] pentru a trimite e-mail altor utilizatori.',
'emailuser'       => 'Trimite e-mail',
'emailpage'       => 'E-mail către utilizator',
'emailpagetext'   => 'Dacă acest utilizator a introdus o adresă de e-mail validă în pagina de preferinţe atunci formularul de mai jos poate fi folosit pentru a-i trimite un mesaj prin e-mail. Adresa pe care ai introdus-o în pagina ta de preferinţe va apărea ca adresa de origine a mesajului, astfel încât destinatarul să îţi poată răspunde direct.',
'usermailererror' => 'Obiectul de mail a dat eroare:',
'defemailsubject' => 'E-mail {{SITENAME}}',
'noemailtitle'    => 'Fără adresă de e-mail',
'noemailtext'     => 'Utilizatorul nu a specificat o adresă validă de e-mail, sau a ales să nu primească e-mail de la alţi utilizatori.',
'emailfrom'       => 'De la',
'emailto'         => 'Către',
'emailsubject'    => 'Subiect',
'emailmessage'    => 'Mesaj',
'emailsend'       => 'Trimite',
'emailccme'       => 'Trimite-mi pe e-mail o copie a mesajului meu.',
'emailccsubject'  => 'O copie a mesajului la $1: $2',
'emailsent'       => 'E-mail trimis',
'emailsenttext'   => 'E-mailul tău a fost trimis.',

# Watchlist
'watchlist'            => 'Pagini urmărite',
'mywatchlist'          => 'Pagini urmărite',
'watchlistfor'         => "(pentru '''$1''')",
'nowatchlist'          => 'Nu aţi ales să urmăriţi nici o pagină.',
'watchlistanontext'    => 'Te rugăm să $1 pentru a vizualiza sau edita itemii de pe lista ta de urmărire.',
'watchnologin'         => 'Nu sunteţi autentificat',
'watchnologintext'     => 'Trebuie să fiţi [[Special:Userlogin|autentificat]] pentru a vă modifica lista de pagini urmărite.',
'addedwatch'           => 'Adăugată la lista de pagini urmărite',
'addedwatchtext'       => 'Pagina "[[:$1]]" a fost adăugată la lista ta de [[Special:Watchlist|articole urmărite]]. Modificările viitoare ale acestei pagini şi a paginii asociate de discuţii vor fi listate aici, şi în plus ele vor apărea cu <b>caractere îngroşate</b> în pagina de [[Special:Recentchanges|modificări recente]] pentru evidenţiere.

Dacă doreşti să elimini această pagină din lista ta de pagini urmărite în viitor, apasă pe "Nu mai urmări" în bara de comenzi în timp ce această pagină este vizibilă.',
'removedwatch'         => 'Ştearsă din lista de pagini urmărite',
'removedwatchtext'     => 'Pagina "[[:$1]]" a fost eliminată din lista de pagini urmărite.',
'watch'                => 'Urmăreşte',
'watchthispage'        => 'Urmăreşte pagina',
'unwatch'              => 'Nu mai urmări',
'unwatchthispage'      => 'Nu mai urmări',
'notanarticle'         => 'Nu este un articol',
'watchnochange'        => 'Nici una dintre paginile pe care le urmăriţi nu a fost modificată în perioada de timp afişată.',
'watchlist-details'    => '$1 pagini urmărite în afară de paginile de discuţie.',
'wlheader-enotif'      => '*Notificarea email este activată',
'wlheader-showupdated' => "* Paginile care au modificări de la ultima ta vizită sunt afişate '''îngroşat'''",
'watchmethod-recent'   => 'căutarea schimbărilor recente pentru paginile urmărite',
'watchmethod-list'     => 'căutarea paginilor urmărite pentru schimbări recente',
'watchlistcontains'    => 'Lista de pagini urmărite conţine $1 elemente.',
'iteminvalidname'      => "E o problemă cu elementul '$1', numele este invalid...",
'wlnote'               => 'Mai jos se află ultimele $1 schimbări din ultimele <b>$2</b> ore.',
'wlshowlast'           => 'Arată ultimele $1 ore $2 zile $3',
'watchlist-show-bots'  => 'Arată editările roboţilor',
'watchlist-hide-bots'  => 'Ascunde editările roboţilor',
'watchlist-show-own'   => 'Arată editările mele',
'watchlist-hide-own'   => 'Ascunde editările mele',
'watchlist-show-minor' => 'Arată editările minore',
'watchlist-hide-minor' => 'Ascunde editările minore',

'enotif_mailer'                => 'Sistemul de notificare {{SITENAME}}',
'enotif_reset'                 => 'Marchează toate paginile vizitate.',
'enotif_newpagetext'           => 'Aceasta este o pagină nouă.',
'enotif_impersonal_salutation' => '{{SITENAME}} utilizator',
'changed'                      => 'modificat',
'created'                      => 'creat',
'enotif_subject'               => 'Pagina $PAGETITLE de la {{SITENAME}} a fost $CHANGEDORCREATED de $PAGEEDITOR',
'enotif_lastvisited'           => 'Vedeţi $1 pentru toate modificările de la ultima dvs. vizită.',
'enotif_anon_editor'           => 'utilizator anonim $1',
'enotif_body'                  => 'Domnule/Doamnă $WATCHINGUSERNAME,

pagina $PAGETITLE de la {{SITENAME}} a fost $CHANGEDORCREATED în $PAGEEDITDATE de $PAGEEDITOR, vedeţi la $PAGETITLE_URL versiunea curentă.

$NEWPAGE

Sumarul utilizatorului: $PAGESUMMARY $PAGEMINOREDIT

Contactaţi utilizatorul:
email: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Nu vor mai fi alte notificări în cazul unor viitoare modificări în afara cazului în care vizitaţi pagina. Puteţi de asemenea reseta notificările pentru alte pagini urmărite.

             Al dvs. amic, sistemul de notificare {{SITENAME}}

--
Pentru a modifica preferinţele listei de urmărire, vizitaţi
{{fullurl:{{ns:special}}:Watchlist/edit}}

Asistenţă şi suport:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Şterge pagina',
'confirm'                     => 'Confirmă',
'excontent'                   => "conţinutul era: '$1'",
'excontentauthor'             => "conţinutul a fost: '$1' (şi unicul contribuitor era '$2')",
'exbeforeblank'               => "conţinutul înainte de golire era: '$1'",
'exblank'                     => 'pagina era goală',
'confirmdelete'               => 'Confirmă ştergere',
'deletesub'                   => '(Şterg "$1")',
'historywarning'              => 'Atenţie! Pagina pe care o ştergi are istorie:',
'confirmdeletetext'           => 'Sunteţi pe cale să ştergeţi permanent o pagină sau imagine din baza de date, împreună cu istoria asociată acesteia. Vă rugăm să confirmaţi alegerea făcută de dvs., faptul că înţelegeţi consecinţele acestei acţiuni şi faptul că o faceţi în conformitate cu [[{{MediaWiki:Policy-url}}|Politica oficială]].',
'actioncomplete'              => 'Acţiune finalizată',
'deletedtext'                 => 'Pagina "$1" a fost ştearsă. Vedeţi $2 pentru o listă a elementelor şterse recent.',
'deletedarticle'              => 'a şters "[[$1]]"',
'dellogpage'                  => 'Jurnal pagini şterse',
'dellogpagetext'              => 'Mai jos se află lista celor mai recente elemente şterse.',
'deletionlog'                 => 'raportul de ştergeri',
'reverted'                    => 'Revenire la o versiune mai veche',
'deletecomment'               => 'Motiv pentru ştergere',
'deletereasonotherlist'       => 'Alt motiv',
'deletereason-dropdown'       => '*Motive uzuale
** Cererea autorului
** Violare drepturi de autor
** Vandalism',
'rollback'                    => 'Editări de revenire',
'rollback_short'              => 'Revenire',
'rollbacklink'                => 'revenire',
'rollbackfailed'              => 'Revenirea nu s-a putut face',
'cantrollback'                => 'Nu se poate reveni; ultimul contribuitor este autorul acestui articol.',
'alreadyrolled'               => 'Nu se poate reveni peste ultima modificare a articolului [[$1]]
făcută de către [[User:$2|$2]] ([[User talk:$2|discuţie]]); altcineva a modificat articolul sau a revenit deja.

Ultima editare a fost făcută de către [[User:$3|$3]] ([[User talk:$3|discuţie]]).',
'editcomment'                 => 'Comentariul de modificare a fost: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Reveniri la ultima modificare de către [[Special:Contributions/$2|$2]] ([[User talk:$2|discuţie]]); revenire la ultima versiune de către [[User:$1|$1]]',
'sessionfailure'              => 'Se pare că este o problemă cu sesiunea de autentificare; această acţiune a fost oprită ca o precauţie împotriva hijack. Apăsaţi "back" şi reîncărcaţi pagina de unde aţi venit, apoi reîncercaţi.',
'protectlogpage'              => 'Jurnal protecţii',
'protectlogtext'              => 'Mai jos se află lista de blocări/deblocări a paginilor. Vezi [[Special:Protectedpages]] pentru mai multe informaţii.',
'protectedarticle'            => 'a protejat "[[$1]]"',
'modifiedarticleprotection'   => 'schimbat nivelul de protecţie pentru "[[$1]]"',
'unprotectedarticle'          => 'a deprotejat "[[$1]]"',
'protectsub'                  => '(Protejare "$1")',
'confirmprotect'              => 'Confirmă protejare',
'protectcomment'              => 'Motiv pentru protejare',
'protectexpiry'               => 'Expirare',
'protect_expiry_invalid'      => 'Timpul de expirare este nevalid.',
'protect_expiry_old'          => 'Timpul de expirare este în trecut.',
'unprotectsub'                => '(Deprotejând "$1")',
'protect-unchain'             => 'Deblochează permisiunile de mutare',
'protect-text'                => 'Poţi vizualiza sau modifica nivelul de protecţie pentru pagina <strong>$1</strong>.',
'protect-default'             => '(standard)',
'protect-fallback'            => 'Cere permisiunea "$1"',
'protect-level-autoconfirmed' => 'Blochează utilizatorii neînregistraţi',
'protect-level-sysop'         => 'Numai administratorii',
'protect-summary-cascade'     => 'în cascadă',
'protect-expiring'            => 'expiră $1 (UTC)',
'protect-cascade'             => 'Protejare în cascadă - toate paginile incluse în această pagină vor fi protejate.',
'restriction-type'            => 'Permisiune:',
'restriction-level'           => 'Nivel de restricţie:',
'minimum-size'                => 'Mărime minimă',
'maximum-size'                => 'Mărime maximă',
'pagesize'                    => '(octeţi)',

# Restrictions (nouns)
'restriction-edit' => 'Editează',
'restriction-move' => 'Mută',

# Restriction levels
'restriction-level-sysop'         => 'protejat complet',
'restriction-level-autoconfirmed' => 'semi-protejat',
'restriction-level-all'           => 'orice nivel',

# Undelete
'undelete'                 => 'Recuperează pagina ştearsă',
'undeletepage'             => 'Vizualizează şi recuperează pagini şterse',
'viewdeletedpage'          => 'Vezi paginile şterse',
'undeletepagetext'         => 'Următoarele pagini au fost şterse, dar încă se află în arhivă şi pot fi recuperate. Reţine că arhiva se poate şterge din timp în timp.',
'undeleteextrahelp'        => "Pentru a recupera întreaga pagină lăsaţi toate căsuţele nebifate şi apăsaţi butonul '''''Recuperează'''''. Pentru a realiza o recuperare selectivă bifaţi versiunile pe care doriţi să le recuperaţi şi apăsaţi butonul '''''Recuperează'''''. Butonul '''''Resetează'''''  va şterge comentariul şi toate bifările.",
'undeleterevisions'        => '$1 versiuni arhivate',
'undeletehistory'          => 'Dacă recuperaţi pagina, toate versiunile asociate vor fi adăugate retroactiv în istorie. Dacă o pagină nouă cu acelaşi nume a fost creată de la momentul ştergerii acesteia, versiunile recuperate vor apărea în istoria paginii, iar versiunea curentă a paginii nu va fi înlocuită automat de către versiunea recuperată.',
'undeletehistorynoadmin'   => 'Acest articol a fost şters. Motivul ştergerii apare mai jos, alături de detaliile utilzatorilor care au editat această pagină înainte de ştergere. Textul prorpiu-zis al reviziilor şterse este disponibil doar administratorilor.',
'undelete-revision'        => 'Şters revizia $1 din $2:',
'undelete-nodiff'          => 'Nu s-a găsit vreo revizie anterioară.',
'undeletebtn'              => 'Recuperează',
'undeletereset'            => 'Resetează',
'undeletecomment'          => 'Comentariu:',
'undeletedarticle'         => '"[[$1]]" a fost recuperat',
'undeletedrevisions'       => '{{PLURAL:$1|o revizie restaurată|$1 revizii restaurate}}',
'undeletedrevisions-files' => '$1 {{PLURAL:$1|revizie|revizii}} şi $2 {{PLURAL:$2|fişier|fişiere}} recuperate',
'undeletedfiles'           => '$1 {{PLURAL:$1|revizie recuperată|revizii recuperate}}',
'cannotundelete'           => 'Recuperarea a eşuat; este posibil ca altcineva să fi recuperat pagina deja.',
'undeletedpage'            => "<big>'''$1 a fost recuperat'''</big>

Consultaţi [[Special:Log/delete|raportul ştergerilor]] pentru a vedea toate ştergerile şi recuperările recente.",
'undelete-header'          => 'Vezi [[Special:Log/delete|logul de ştergere]] pentru paginile şterse recent.',
'undelete-search-box'      => 'Caută pagini şterse',
'undelete-search-prefix'   => 'Arată paginile care încep cu:',
'undelete-search-submit'   => 'Caută',
'undelete-error-long'      => 'S-au găsit erori la ştergerea fişierului:

$1',

# Namespace form on various pages
'namespace'      => 'Spaţiul:',
'invert'         => 'Exclude spaţiul:',
'blanknamespace' => '(Principală)',

# Contributions
'contributions' => 'Contribuţii ale utilizatorului',
'mycontris'     => 'Contribuţii',
'contribsub2'   => 'Pentru $1 ($2)',
'nocontribs'    => 'Nu a fost găsită nici o modificare care să satisfacă acest criteriu.',
'ucnote'        => 'Mai jos se află ultimele <b>$1</b> modificări ale utilizatorului din ultimele <b>$2</b> zile.',
'uclinks'       => 'Vezi ultimele $1 modificări; vezi ultimele $2 zile.',
'uctop'         => ' (sus)',
'month'         => 'Din luna (şi dinainte):',

'sp-contributions-newbies'     => 'Arată doar contribuţiile conturilor noi',
'sp-contributions-newbies-sub' => 'Pentru începători',
'sp-contributions-blocklog'    => 'Raport blocări',
'sp-contributions-search'      => 'Caută contribuţii',
'sp-contributions-username'    => 'Adresă IP sau nume de utilizator:',
'sp-contributions-submit'      => 'Caută',

'sp-newimages-showfrom' => 'Arată imaginile noi începând cu $1',

# What links here
'whatlinkshere'       => 'Ce se leagă aici',
'whatlinkshere-title' => 'Pagini care se leagă de $1',
'whatlinkshere-page'  => 'Pagina:',
'linklistsub'         => '(Lista de legături)',
'linkshere'           => "Următoarele pagini conţin legături către '''[[:$1]]''':",
'nolinkshere'         => "Nici o pagină nu se leagă la '''[[:$1]]'''.",
'nolinkshere-ns'      => "Nici o pagină din spaţiul de nume ales nu se leagă la '''[[:$1]]'''.",
'isredirect'          => 'pagină de redirecţionare',
'istemplate'          => 'prin includerea formatului',
'whatlinkshere-prev'  => '{{PLURAL:$1|anterioara|anterioarele $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|următoarea|urmatoarele $1}}',
'whatlinkshere-links' => '← legături',

# Block/unblock
'blockip'                     => 'Blochează utilizator / IP',
'blockiptext'                 => "Pentru a bloca un utilizator completaţi rubricile de mai jos.<br />
'''Respectaţi [[{{MediaWiki:Policy-url}}|politica de blocare]].'''<br />
Precizaţi motivul blocării; de exemplu indicaţi paginile vandalizate de acest utilizator.",
'ipaddress'                   => 'Adresa IP',
'ipadressorusername'          => 'Adresă IP sau nume de utilizator',
'ipbexpiry'                   => 'Expiră',
'ipbreason'                   => 'Motiv',
'ipbreasonotherlist'          => 'Alt motiv',
'ipbreason-dropdown'          => '*Motivele cele mai frecvente
** Introducere de informaţii false
** Ştergere conţinut fără explicaţii
** Introducere de legături externe de publicitate (spam)
** Creare pagini fără sens
** Tentative de intimidare
** Abuz utilizare conturi multiple
** Nume de utilizator inacceptabil',
'ipbanononly'                 => 'Blochează doar utilizatorii anonimi',
'ipbcreateaccount'            => 'Nu permite crearea de conturi',
'ipbenableautoblock'          => 'Blochează automat ultima adresă IP folosită de acest utilizator şi toate adresele de la care încearcă să editeze în viitor',
'ipbsubmit'                   => 'Blochează acest utilizator',
'ipbother'                    => 'Alt termen',
'ipboptions'                  => '15 minute:15 minutes,1 oră:1 hour,3 ore:3 hours,24 ore:24 hours,48 ore:48 hours,1 săptămână:1 week,1 lună:1 month,nelimitat:infinite',
'ipbotheroption'              => 'altul',
'ipbotherreason'              => 'Alt motiv sau suplimentar:',
'badipaddress'                => 'Adresa IP este invalidă.',
'blockipsuccesssub'           => 'Utilizatorul a fost blocat',
'blockipsuccesstext'          => 'Adresa IP "$1" a fost blocată.
<br />Vezi [[Special:Ipblocklist|lista de adrese IP şi conturi blocate]] pentru a revizui adresele blocate.',
'ipb-unblock-addr'            => 'Deblochează $1',
'ipb-unblock'                 => 'Deblochează un cont de utilizator sau o adresă IP',
'ipb-blocklist-addr'          => 'Vezi blocările existente pentru $1',
'ipb-blocklist'               => 'Vezi blocările existente',
'unblockip'                   => 'Deblochează adresă IP',
'unblockiptext'               => 'Foloseşte chestionarul de mai jos pentru a restaura
drepturile de scriere pentru o adresă IP blocată anterior..',
'ipusubmit'                   => 'Deblochează adresa',
'unblocked'                   => '[[User:$1|$1]] a fost deblocat',
'ipblocklist'                 => 'Lista de adrese IP şi conturi blocate',
'ipblocklist-legend'          => 'Găseşte un utilizator blocat',
'ipblocklist-username'        => 'Nume de utilizator sau adresă IP:',
'ipblocklist-submit'          => 'Caută',
'blocklistline'               => '$1, $2 a blocat $3 ($4)',
'infiniteblock'               => 'termen nelimitat',
'expiringblock'               => 'expiră la $1',
'anononlyblock'               => 'doar anonimi',
'noautoblockblock'            => 'autoblocare dezactivată',
'createaccountblock'          => 'crearea de conturi blocată',
'emailblock'                  => 'e-mail blocat',
'ipblocklist-empty'           => 'Lista blocărilor este goală.',
'blocklink'                   => 'blochează',
'unblocklink'                 => 'deblochează',
'contribslink'                => 'contribuţii',
'autoblocker'                 => 'Autoblocat fiindcă foloseşti aceeaşi adresă IP ca şi "$1". Motivul este "$2".',
'blocklogpage'                => 'Jurnal blocări',
'blocklogentry'               => 'a blocat "[[$1]]" pe o perioadă de $2 $3',
'blocklogtext'                => 'Acesta este un raport al acţiunilor de blocare şi deblocare. Adresele IP blocate automat nu sunt afişate. Vizitaţi [[Special:Ipblocklist|lista de adrese blocate]] pentru o listă explicită a adreselor blocate în acest moment.',
'unblocklogentry'             => 'a deblocat $1',
'block-log-flags-anononly'    => 'doar utilizatorii anonimi',
'block-log-flags-nocreate'    => 'creare de conturi dezactivată',
'block-log-flags-noautoblock' => 'autoblocare dezactivată',
'block-log-flags-noemail'     => 'e-mail blocat',
'range_block_disabled'        => 'Abilitatea dezvoltatorilor de a bloca serii de adrese este dezactivată.',
'ipb_expiry_invalid'          => 'Dată de expirare invalidă.',
'ipb_already_blocked'         => '"$1" este deja blocat',
'ipb_cant_unblock'            => 'Eroare: nu găsesc identificatorul $1. Probabil a fost deja deblocat.',
'ip_range_invalid'            => 'Serie IP invalidă.',
'proxyblocker'                => 'Blocaj de proxy',
'proxyblocker-disabled'       => 'Această funcţie este dezactivată.',
'proxyblockreason'            => 'Adresa ta IP a fost blocată pentru că este un proxy deschis. Te rog, contactează provider-ul tău de servicii Internet sau tehnicieni IT şi informează-i asupra acestei probleme serioase de securitate.',
'proxyblocksuccess'           => 'Realizat.',
'sorbsreason'                 => 'Adresa dumneavoastră IP este listată ca un proxy deschis în DNSBL.',
'sorbs_create_account_reason' => 'Adresa dvs. IP este listată la un proxy deschis în lista neagră DNS. Nu vă puteţi crea un cont',

# Developer tools
'lockdb'              => 'Blochează baza de date',
'unlockdb'            => 'Deblochează baza de date',
'lockdbtext'          => 'Blocarea bazei de date va împiedica pe toţi utilizatorii
să modifice pagini, să-şi schimbe preferinţele, să-şi modifice listele de
pagini urmărite şi orice alte operaţiuni care ar necesita schimări
în baza de date.
Te rugăm să confirmi că intenţionezi acest lucru şi faptul că vei debloca
baza de date atunci când vei încheia operaţiunile de întreţinere.',
'unlockdbtext'        => 'Deblocarea bazei de date va permite tuturor utilizatorilor să editeze pagini, să-şi schimbe preferinţele, să-şi editeze listele de pagini urmărite şi orice alte operaţiuni care ar necesita schimări în baza de date. Te rugăm să-ţi confirmi intenţia de a face acest lucru.',
'lockconfirm'         => 'Da, chiar vreau să blochez baza de date.',
'unlockconfirm'       => 'Da, chiar vreau să deblochez baza de date.',
'lockbtn'             => 'Blochează baza de date',
'unlockbtn'           => 'Deblochează baza de date',
'locknoconfirm'       => 'Nu aţi bifat căsuţa de confirmare.',
'lockdbsuccesssub'    => 'Baza de date a fost blocată',
'unlockdbsuccesssub'  => 'Baza de date a fost deblocată',
'lockdbsuccesstext'   => 'Baza de date {{SITENAME}} a fost blocată la scriere.<br />
Nu uita să o deblochezi după ce termini operaţiunile administrative pentru care ai blocat-o.',
'unlockdbsuccesstext' => 'Baza de date a fost deblocată.',
'databasenotlocked'   => 'Baza de date nu este blocată.',

# Move page
'movepage'                => 'Mută pagina',
'movepagetext'            => "Puteţi folosi formularul de mai jos pentru a redenumi
o pagină, mutându-i toată istoria sub noul nume.
Pagina veche va deveni o pagină de redirecţionare către pagina nouă.
Legăturile către pagina veche nu vor fi redirecţionate către cea nouă;
nu uitaţi să verificaţi dacă nu există redirecţionări duble sau invalide.

Te rugăm să reţineţi că sunteţi responsabil(ă) pentru a face legăturile vechi să rămână valide.

Reţineţi că pagina '''nu va fi mutată''' dacă există deja o
pagină cu noul titlu, în afară de cazul că este complet goală sau este
o redirecţionare şi în plus nu are nici o istorie de modificare.
Cu alte cuvinte, veţi putea muta înapoi o pagină pe care aţi mutat-o
greşit, dar nu veţi putea suprascrie o pagină validă existentă prin
mutarea alteia.

<b>ATENŢIE!</b>
Aceasta poate fi o schimbare drastică şi neaşteptată pentru o pagină populară;
vă rugăm, să vă asiguraţi că înţelegeţi toate consecinţele înainte de a continua.",
'movepagetalktext'        => "Pagina asociată de discuţii, dacă există, va fi mutată
automat odată cu aceasta '''afară de cazul că''':
* Mutaţi pagina în altă secţiune a {{SITENAME}}
* Există deja o pagină de discuţii cu conţinut (care nu este goală), sau
* Nu confirmi căsuţa de mai jos.

În oricare din cazurile de mai sus va trebui să muţi sau să unifici
manual paginile de discuţii, dacă doreşti acest lucru.",
'movearticle'             => 'Mută pagina',
'movenologin'             => 'Nu eşti autentificat',
'movenologintext'         => 'Trebuie să fii un utilizator înregistrat şi să te [[Special:Userlogin|autentifici]] pentru a muta o pagină.',
'newtitle'                => 'Titlul nou',
'move-watch'              => 'Urmăreşte această pagină',
'movepagebtn'             => 'Mută pagina',
'pagemovedsub'            => 'Pagina a fost mutată',
'movepage-moved'          => '<big>\'\'\'"$1" a fost mutat la "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'O pagină cu acelaşi nume există deja, sau numele pe care l-ai ales este invalid. Te rugăm să alegi un alt nume.',
'talkexists'              => "'''Pagina în sine a fost mutată, dar pagina de discuţii nu a putut fi mutată deoarece deja există o alta cu acelaşi nume. Te rugăm să unifici manual cele două pagini de discuţii.'''",
'movedto'                 => 'mutată la',
'movetalk'                => 'Mută şi pagina de "discuţii" dacă se poate.',
'talkpagemoved'           => 'Şi pagina de discuţii asociată a fost mutată.',
'talkpagenotmoved'        => 'Pagina asociată de discuţii <strong>nu</strong> a fost mutată.',
'1movedto2'               => 'a mutat [[$1]] la [[$2]]',
'1movedto2_redir'         => 'a mutat [[$1]] la [[$2]] prin redirect',
'movelogpage'             => 'Jurnal mutări',
'movelogpagetext'         => 'Mai jos se află o listă cu paginile mutate.',
'movereason'              => 'Motiv',
'revertmove'              => 'revenire',
'delete_and_move'         => 'Şterge şi mută',
'delete_and_move_text'    => '==Ştergere necesară==

Articolul de destinaţie "[[$1]]" există deja. Doriţi să îl ştergeţi pentru a face loc mutării?',
'delete_and_move_confirm' => 'Da, şterge pagina.',
'delete_and_move_reason'  => 'Şters pentru a face loc mutării',
'selfmove'                => 'Titlurile sursei şi ale destinaţiei sunt aceleaşi; nu puteţi muta o pagină peste ea însăşi.',
'immobile_namespace'      => 'Titlul destinaţiei este al unui tip special; nu se pot muta pagini în acel spaţiu de nume.',

# Export
'export'            => 'Exportă pagini',
'exporttext'        => 'Poţi exporta textul şi istoria unei pagini anume sau ale unui grup de pagini în XML. Acesta poate fi apoi importat în alt Wiki care rulează software MediaWiki, poate fi transformat sau păstrat pur şi simplu fiindcă doreşti tu să-l păstrezi.',
'exportcuronly'     => 'Include numai versiunea curentă, nu şi toată istoria',
'exportnohistory'   => "---- '''Notă:''' exportarea versiunii complete a paginilor prin acest formular a fost scoasă din uz din motive de performanţă.",
'export-submit'     => 'Exportă',
'export-addcattext' => 'Adaugă pagini din categoria:',
'export-addcat'     => 'Adaugă',

# Namespace 8 related
'allmessages'               => 'Toate mesajele',
'allmessagesname'           => 'Nume',
'allmessagesdefault'        => 'Textul standard',
'allmessagescurrent'        => 'Textul curent',
'allmessagestext'           => 'Aceasta este lista completă a mesajelor disponibile în domeniul "MediaWiki:"',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' nu poate fi folosit deoarece '''\$wgUseDatabaseMessages''' este închisă.",
'allmessagesfilter'         => 'Filtrare în funcţie de titlul mesajului:',
'allmessagesmodified'       => 'Arată doar mesajele modificate.',

# Thumbnails
'thumbnail-more'  => 'Extinde',
'missingimage'    => '<b>Imagine lipsă</b><br />
<i>$1</i>',
'filemissing'     => 'Fişier lipsă',
'thumbnail_error' => 'Eroare la generarea previzualizării: $1',

# Special:Import
'import'                     => 'Importă pagini',
'importinterwiki'            => 'Import transwiki',
'import-interwiki-text'      => 'Selectează un wiki şi titlul paginii care trebuie importate. Datele reviziilor şi numele editorilor vor fi salvate. Toate acţiunile de import transwiki pot fi găsite la [[Special:Log/import|log import]]',
'import-interwiki-history'   => 'Copiază toate versiunile istoricului acestei pagini',
'import-interwiki-submit'    => 'Importă',
'import-interwiki-namespace' => 'Transferă paginile la spaţiul de nume:',
'importtext'                 => 'Te rog exportă fişierul din sursa wiki folosind funcţia Special:Export, salvează-l pe discul tău şi trimite-l aici.',
'importstart'                => 'Se importă paginile...',
'import-revision-count'      => '$1 revizie(i)',
'importnopages'              => 'Nu există pagini de importat.',
'importfailed'               => 'Import eşuat: $1',
'importunknownsource'        => 'Tipul sursei de import este necunoscut',
'importcantopen'             => 'Fişierul importat nu a putut fi deschis',
'importbadinterwiki'         => 'Legătură interwiki greşită',
'importnotext'               => 'Gol sau fără text',
'importsuccess'              => 'Import reuşit!',
'importhistoryconflict'      => 'Există istorii contradictorii (se poate să fi importat această pagină înainte)',
'importnosources'            => 'Nici o sursă de import transwiki a fost definită şi încărcările directe ale istoricului sunt oprite.',
'importnofile'               => 'Nici un fişier pentru import nu a fost încărcat.',
'importuploaderror'          => 'Încărcarea fişierului de import nu a avut succes; probabil mărimea fişierului este mai mare decât mărimea maximă acceptată pentru fişiere.',

# Import log
'importlogpage'                    => 'Log import',
'importlogpagetext'                => 'Imoprturi administrative de pagini de la alte wiki, cu istoricul editărilor.',
'import-logentry-upload'           => '$1 importate prin upload',
'import-logentry-upload-detail'    => '$1 revizie(i)',
'import-logentry-interwiki'        => 'transwikificat $1',
'import-logentry-interwiki-detail' => '$1 revizie(i) de la $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Pagina mea de utilizator',
'tooltip-pt-anonuserpage'         => 'Pagina de utilizator pentru adresa IP curentă',
'tooltip-pt-mytalk'               => 'Pagina mea de discuţii',
'tooltip-pt-anontalk'             => 'Discuţii despre editări pentru adresa IP curentă',
'tooltip-pt-preferences'          => 'Preferinţele mele',
'tooltip-pt-watchlist'            => 'Lista paginilor pe care le monitorizez.',
'tooltip-pt-mycontris'            => 'Listă de contribuţii',
'tooltip-pt-login'                => 'Eşti încurajat să te autentifici, deşi acest lucru nu este obligatoriu.',
'tooltip-pt-anonlogin'            => 'Eşti încurajat să te autentifici, deşi acest lucru nu este obligatoriu.',
'tooltip-pt-logout'               => 'Închide sesiunea',
'tooltip-ca-talk'                 => 'Discuţie despre articol',
'tooltip-ca-edit'                 => 'Poţi edita această pagină. Te rugăm să previzualizezi conţinutul înainte de salvare.',
'tooltip-ca-addsection'           => 'Adaugă un comentariu acestei discuţii.',
'tooltip-ca-viewsource'           => 'Aceasta pagina este protejată. Poţi sa vezi doar codul sursă.',
'tooltip-ca-history'              => 'Versiuni vechi ale acestui document.',
'tooltip-ca-protect'              => 'Protejează acest document.',
'tooltip-ca-delete'               => 'Şterge acest document.',
'tooltip-ca-undelete'             => 'Restaureaza editările făcute acestui document, înainte să fi fost şters.',
'tooltip-ca-move'                 => 'Mută acest document.',
'tooltip-ca-watch'                => 'Adaugă acest document în lista ta de monitorizare.',
'tooltip-ca-unwatch'              => 'Şterge acest document din lista ta de monitorizare.',
'tooltip-search'                  => 'Căutare în {{SITENAME}}',
'tooltip-p-logo'                  => 'Pagina principală',
'tooltip-n-mainpage'              => 'Vizitează pagina principală',
'tooltip-n-portal'                => 'Despre proiect, ce poţi face tu, unde găseşti soluţii.',
'tooltip-n-currentevents'         => 'Găseşte informaţii despre evenimente curente',
'tooltip-n-recentchanges'         => 'Lista ultimelor schimbări realizate în acest wiki.',
'tooltip-n-randompage'            => 'Mergi spre o pagină aleatoare',
'tooltip-n-help'                  => 'Locul în care găseşti ajutor.',
'tooltip-n-sitesupport'           => 'Sprijină-ne',
'tooltip-t-whatlinkshere'         => 'Lista tuturor paginilor wiki care conduc spre această pagină',
'tooltip-t-recentchangeslinked'   => 'Schimbări recente în legătură cu această pagină',
'tooltip-feed-rss'                => 'Alimentează fluxul RSS pentru această pagină',
'tooltip-feed-atom'               => 'Alimentează fluxul Atom pentru această pagină',
'tooltip-t-contributions'         => 'Vezi lista de contribuţii ale acestui utilizator',
'tooltip-t-emailuser'             => 'Trimite un e-mail acestui utilizator',
'tooltip-t-upload'                => 'Trimite imagini sau fişiere media',
'tooltip-t-specialpages'          => 'Lista tuturor paginilor speciale',
'tooltip-t-print'                 => 'Versiunea de tipărit a acestei pagini',
'tooltip-ca-nstab-main'           => 'Vezi articolul',
'tooltip-ca-nstab-user'           => 'Vezi pagina de utilizator',
'tooltip-ca-nstab-media'          => 'Vezi pagina media',
'tooltip-ca-nstab-special'        => 'Aceasta este o pagină specială, (nu) poţi edita pagina în sine.',
'tooltip-ca-nstab-project'        => 'Vezi pagina proiectului',
'tooltip-ca-nstab-image'          => 'Vezi pagina imaginii',
'tooltip-ca-nstab-mediawiki'      => 'Vezi mesajul de sistem',
'tooltip-ca-nstab-template'       => 'Vezi formatul',
'tooltip-ca-nstab-help'           => 'Vezi pagina de ajutor',
'tooltip-ca-nstab-category'       => 'Vezi categoria',
'tooltip-minoredit'               => 'Marcaţi această modificare ca fiind minoră',
'tooltip-save'                    => 'Salvează modificările tale',
'tooltip-preview'                 => 'Previzualizarea modificărilor tale, foloseşte-o te rog înainte de a salva!',
'tooltip-diff'                    => 'Arată ce modificări ai făcut textului.',
'tooltip-compareselectedversions' => 'Vezi diferenţele între cele două versiuni selectate de pe această pagină.',
'tooltip-watch'                   => 'Adaugă această pagină la lista mea de pagini urmărite',
'tooltip-recreate'                => 'Recreează',

# Stylesheets
'common.css'   => '/** CSS plasate aici vor fi aplicate tuturor apariţiilor */',
'monobook.css' => '/* modificaţi acest fişier pentru a adapta înfăţişarea monobook-ului pentru tot situl*/',

# Metadata
'nodublincore'      => 'Metadatele Dublin Core RDF sunt dezactivate pentru acest server.',
'nocreativecommons' => 'Metadatele Creative Commons RDF dezactivate pentru acest server.',
'notacceptable'     => 'Serverul wiki nu poate oferi date într-un format pe care clientul tău să-l poată citi.',

# Attribution
'anonymous'        => 'Utilizator(i) anonimi ai {{SITENAME}}',
'siteuser'         => 'Utilizator {{SITENAME}} $1',
'lastmodifiedatby' => 'Această pagină a fost modificată $2, $1 de către $3.', # $1 date, $2 time, $3 user
'and'              => 'şi',
'othercontribs'    => 'Bazat pe munca lui $1.',
'others'           => 'alţii',
'siteusers'        => 'Utilizator(i) {{SITENAME}} $1',
'creditspage'      => 'Credenţiale',
'nocredits'        => 'Nu există credenţiale disponibile pentru această pagină.',

# Spam protection
'spamprotectiontitle'    => 'Filtru de protecţie spam',
'spamprotectiontext'     => 'Pagina pe care doriţi să o salvaţi a fost blocată de filtrul spam. Aceasta se datorează probabil unei legături spre un site extern. Aţi putea verifica următoarea expresie regulată:',
'spamprotectionmatch'    => 'Următorul text a fost oferit de filtrul de spam: $1',
'subcategorycount'       => 'Această categorie conţine {{PLURAL:$1|o subcategorie|$1 subcategorii}}.',
'categoryarticlecount'   => 'Această categorie conţine {{PLURAL:$1|un articol|$1 articole}}.',
'category-media-count'   => 'Această categorie conţine {{PLURAL:$1|un articol|$1 articole}}.',
'listingcontinuesabbrev' => ' cont.',
'spambot_username'       => 'Curăţarea de spam a MediaWiki',
'spam_reverting'         => 'Revenire la ultima versiune care nu conţine legături către $1',
'spam_blanking'          => 'Toate reviziile conţinând legături către $1, au eşuat',

# Info page
'infosubtitle'   => 'Informaţii pentru pagină',
'numedits'       => 'Număr de modificări (articole): $1',
'numtalkedits'   => 'Număr de modificări (pagina de discuţii): $1',
'numwatchers'    => 'Număr de utilizatori care urmăresc: $1',
'numauthors'     => 'Număr de autori distincţi (articole): $1',
'numtalkauthors' => 'Număr de autori distincţi (pagini de discuţii): $1',

# Math options
'mw_math_png'    => 'Întodeauna afişează PNG',
'mw_math_simple' => 'HTML pentru formule simple, altfel PNG',
'mw_math_html'   => 'HTML dacă este posibil, altfel PNG',
'mw_math_source' => 'Lasă ca TeX (pentru browser-ele text)',
'mw_math_modern' => 'Recomandat pentru browser-ele moderne',
'mw_math_mathml' => 'MathML dacă este posibil (experimental)',

# Patrolling
'markaspatrolleddiff'                 => 'Marchează ca patrulat',
'markaspatrolledtext'                 => 'Marchează acest articol ca patrulat',
'markedaspatrolled'                   => 'A fost marcat ca patrulat',
'markedaspatrolledtext'               => 'Modificarea selectată a fost marcată ca patrulată.',
'rcpatroldisabled'                    => 'Opţiunea de patrulare a modificărilor recente este dezactivată',
'rcpatroldisabledtext'                => 'Patrularea modificărilor recente este în prezent dezactivată.',
'markedaspatrollederror'              => 'Nu se poate marca ca patrulat',
'markedaspatrollederrortext'          => 'Trebuie să specificaţi o revizie care să fie marcată ca patrulată.',
'markedaspatrollederror-noautopatrol' => 'Nu puteţi marca propriile modificări ca patrulate.',

# Patrol log
'patrol-log-page' => 'Jurnal patrulări',
'patrol-log-auto' => '(automat)',

# Image deletion
'deletedrevision'       => 'A fost ştearsă vechea revizie $1.',
'filedeleteerror-short' => 'Eroare la ştergerea fişierului: $1',
'filedelete-missing'    => 'Fişierul "$1" nu poate fi şters, deoarece nu există.',

# Browsing diffs
'previousdiff' => '← Diferenţa anterioară',
'nextdiff'     => 'Diferenţa următoare →',

# Media information
'mediawarning'         => "'''Atenţie''': Acest fişier poate conţine cod maliţios, executându-l, sistemul dvs. poate fi compromis.<hr />",
'imagemaxsize'         => 'Limitează imaginile pe paginile de descriere la:',
'thumbsize'            => 'Mărime thumbnail:',
'widthheight'          => '$1x$2',
'widthheightpage'      => '$1×$2, $3 pagini',
'file-info'            => '(mărime fişier: $1, tip MIME: $2)',
'file-info-size'       => '($1 × $2 pixeli, mărime fişier: $3, tip MIME: $4)',
'file-nohires'         => '<small>Rezoluţii mai mari nu sunt disponibile.</small>',
'show-big-image'       => 'Măreşte rezoluţia imaginii',
'show-big-image-thumb' => '<small>Mărimea acestei previzualizări: $1 × $2 pixeli</small>',

# Special:Newimages
'newimages'    => 'Galeria de imagini noi',
'showhidebots' => '($1 roboţi)',
'noimages'     => 'Nimic de văzut.',

# Metadata
'metadata'          => 'Informaţii',
'metadata-help'     => 'Acest fişier conţine informaţii suplimentare, introduse probabil de aparatul fotografic digital sau scannerul care l-a generat. Dacă fişierul a fost modificat între timp, este posibil ca unele detalii să nu mai fie valabile.',
'metadata-expand'   => 'Afişează detalii suplimentare',
'metadata-collapse' => 'Ascunde detalii suplimentare',

# EXIF tags
'exif-imagewidth'                  => 'Lăţime',
'exif-imagelength'                 => 'Înălţime',
'exif-bitspersample'               => 'Biţi pe componentă',
'exif-compression'                 => 'Metodă de comprimare',
'exif-photometricinterpretation'   => 'Compoziţia pixelilor',
'exif-orientation'                 => 'Orientare',
'exif-samplesperpixel'             => 'Numărul de componente',
'exif-planarconfiguration'         => 'Aranjarea datelor',
'exif-ycbcrsubsampling'            => 'Mostră din fracţia Y/C',
'exif-ycbcrpositioning'            => 'Poziţionarea Y şi C',
'exif-xresolution'                 => 'Rezoluţie orizontală',
'exif-yresolution'                 => 'Rezoluţie verticală',
'exif-resolutionunit'              => 'Unitate de rezoluţie pentru X şi Y',
'exif-stripoffsets'                => 'Locaţia datelor imaginii',
'exif-stripbytecounts'             => 'Biţi corespunzători benzii comprimate',
'exif-jpeginterchangeformat'       => 'Offset pentru JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Biţi de date JPEG',
'exif-transferfunction'            => 'Funcţia de transfer',
'exif-whitepoint'                  => 'Cromaticitatea punctului alb',
'exif-ycbcrcoefficients'           => 'Tăria culorii coeficienţilor matricei de transformare',
'exif-referenceblackwhite'         => 'Perechile de valori de referinţă albe şi negre',
'exif-datetime'                    => 'Data şi ora modificării fişierului',
'exif-imagedescription'            => 'Titlul imaginii',
'exif-make'                        => 'Producătorul aparatului foto',
'exif-model'                       => 'Modelul aparatului foto',
'exif-software'                    => 'Software folosit',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Titularul drepturilor de autor',
'exif-exifversion'                 => 'Versiune exif',
'exif-flashpixversion'             => 'Versiune susţinută de Flashpix',
'exif-colorspace'                  => 'Spaţiu de culoare',
'exif-componentsconfiguration'     => 'Semnificaţia componentelor',
'exif-compressedbitsperpixel'      => 'Mod de comprimare a imaginii',
'exif-pixelydimension'             => 'Lăţimea validă a imaginii',
'exif-pixelxdimension'             => 'Valind image height',
'exif-makernote'                   => 'Observaţiile producătorului',
'exif-usercomment'                 => 'Comentariile utilizatorilor',
'exif-relatedsoundfile'            => 'Fişierul audio asemănător',
'exif-datetimeoriginal'            => 'Data şi ora producerii imaginii',
'exif-datetimedigitized'           => 'Data şi ora digitizării',
'exif-subsectime'                  => 'Data/Ora milisecunde',
'exif-subsectimeoriginal'          => 'Data/Ora/Original milisecunde',
'exif-subsectimedigitized'         => 'Milisecunde DateTimeDigitized',
'exif-exposuretime'                => 'Timp de expunere',
'exif-exposuretime-format'         => '$1 sec ($2)',
'exif-fnumber'                     => 'Diafragmă',
'exif-exposureprogram'             => 'Program de expunere',
'exif-spectralsensitivity'         => 'Sensibilitate spectrală',
'exif-isospeedratings'             => 'Evaluarea vitezei ISO',
'exif-oecf'                        => 'Factorul de conversie optoelectronic',
'exif-shutterspeedvalue'           => 'Viteza de închidere',
'exif-aperturevalue'               => 'Diafragmă',
'exif-brightnessvalue'             => 'Luminozitate',
'exif-exposurebiasvalue'           => 'Ajustarea expunerii',
'exif-subjectdistance'             => 'Distanţa faţă de subiect',
'exif-meteringmode'                => 'Forma de măsurare',
'exif-lightsource'                 => 'Sursă de lumină',
'exif-flash'                       => 'Bliţ',
'exif-focallength'                 => 'Distanţa focală a obiectivului',
'exif-subjectarea'                 => 'Suprafaţa subiectului',
'exif-flashenergy'                 => 'Energie flash',
'exif-spatialfrequencyresponse'    => 'Răspunsul frecvenţei spaţiale',
'exif-focalplanexresolution'       => 'Rezoluţia focală plană X',
'exif-focalplaneyresolution'       => 'Rezoluţia focală plană Y',
'exif-focalplaneresolutionunit'    => 'Unitatea de măsură pentru rezoluţia focală plană',
'exif-subjectlocation'             => 'Locaţia subiectului',
'exif-exposureindex'               => 'Indexul expunerii',
'exif-sensingmethod'               => 'Metoda sensibilă',
'exif-filesource'                  => 'Fişier sursă',
'exif-scenetype'                   => 'Tipul scenei',
'exif-cfapattern'                  => 'Mozaic CFA (filtre color)',
'exif-customrendered'              => 'Prelucrarea imaginii',
'exif-exposuremode'                => 'Mod de expunere',
'exif-whitebalance'                => 'Balanţa albă',
'exif-digitalzoomratio'            => 'Raportul zoom-ului digital',
'exif-focallengthin35mmfilm'       => 'Distanţă focală pentru film de 35 mm',
'exif-scenecapturetype'            => 'Tipul de surprindere a scenei',
'exif-gaincontrol'                 => 'Controlul scenei',
'exif-contrast'                    => 'Contrast',
'exif-saturation'                  => 'Saturaţie',
'exif-sharpness'                   => 'Ascuţime',
'exif-devicesettingdescription'    => 'Descrierea reglajelor aparatului',
'exif-imageuniqueid'               => 'Identificarea imaginii unice',
'exif-gpsversionid'                => 'Versiunea de conversie GPS',
'exif-gpslatituderef'              => 'Latitudine nordică sau sudică',
'exif-gpslatitude'                 => 'Latitudine',
'exif-gpslongituderef'             => 'Longitudine estică sau vestică',
'exif-gpslongitude'                => 'Longitudine',
'exif-gpsaltituderef'              => 'Indicarea altitudinii',
'exif-gpsaltitude'                 => 'Altitudine',
'exif-gpstimestamp'                => 'ora GPS (ceasul atomic)',
'exif-gpssatellites'               => 'Sateliţi utilizaţi pentru măsurare',
'exif-gpsstatus'                   => 'Starea receptorului',
'exif-gpsmeasuremode'              => 'Mod de măsurare',
'exif-gpsdop'                      => 'Precizie de măsurare',
'exif-gpsspeedref'                 => 'Unitatea de măsură pentru viteză',
'exif-gpsspeed'                    => 'Viteza receptorului GPS',
'exif-gpstrackref'                 => 'Referinţă pentru direcţia de mişcare',
'exif-gpstrack'                    => 'Direcţie de mişcare',
'exif-gpsimgdirectionref'          => 'Referinţă pentru direcţia imaginii',
'exif-gpsimgdirection'             => 'Direcţia imaginii',
'exif-gpsmapdatum'                 => 'Expertiza geodezică a datelor utilizate',
'exif-gpsdestlatituderef'          => 'Referinţă pentru latitudinea destinaţiei',
'exif-gpsdestlatitude'             => 'Destinaţia latitudinală',
'exif-gpsdestlongituderef'         => 'Referinţă pentru longitudinea destinaţiei',
'exif-gpsdestlongitude'            => 'Longitudinea destinaţiei',
'exif-gpsdestbearingref'           => 'Referinţă pentru raportarea destinaţiei',
'exif-gpsdestbearing'              => 'Raportarea destinaţiei',
'exif-gpsdestdistanceref'          => 'Referinţă pentru distanţa până la destinaţie',
'exif-gpsdestdistance'             => 'Distanţa până la destinaţie',
'exif-gpsprocessingmethod'         => 'Numele metodei de procesare GPS',
'exif-gpsareainformation'          => 'Numele domeniului GPS',
'exif-gpsdatestamp'                => 'Data GPS',
'exif-gpsdifferential'             => 'Corecţia diferenţială GPS',

# EXIF attributes
'exif-compression-1' => 'Necomprimată',

'exif-unknowndate' => 'Dată necunoscută',

'exif-orientation-1' => 'Normală', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Oglindită orizontal', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotită cu 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Oglindită vertical', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rotită 90° în sens opus acelor de ceasornic şi oglindită vertical', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Rotită 90° în sensul acelor de ceasornic', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Rotită 90° în sensul acelor de ceasornic şi oglindită vertical', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rotită 90° în sens opus acelor de ceasornic', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'format compact',
'exif-planarconfiguration-2' => 'format plat',

'exif-componentsconfiguration-0' => 'neprecizat',

'exif-exposureprogram-0' => 'Neprecizat',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Program normal',
'exif-exposureprogram-3' => 'Prioritate diafragmă',
'exif-exposureprogram-4' => 'Prioritate timp',
'exif-exposureprogram-5' => 'Program creativ (prioritate dată profunzimii)',
'exif-exposureprogram-6' => 'Program acţiune (prioritate dată timpului de expunere scurt)',
'exif-exposureprogram-7' => 'Mod portret (focalizare pe subiect şi fundal neclar)',
'exif-exposureprogram-8' => 'Mod peisaj (focalizare pe fundal)',

'exif-subjectdistance-value' => '$1 metri',

'exif-meteringmode-0'   => 'Necunoscut',
'exif-meteringmode-1'   => 'Medie',
'exif-meteringmode-3'   => 'Punct',
'exif-meteringmode-4'   => 'MultiPunct',
'exif-meteringmode-5'   => 'Model',
'exif-meteringmode-6'   => 'Parţial',
'exif-meteringmode-255' => 'Alta',

'exif-lightsource-0'   => 'Necunoscută',
'exif-lightsource-1'   => 'Lumină solară',
'exif-lightsource-2'   => 'Fluorescent',
'exif-lightsource-3'   => 'Tungsten (lumină incandescentă)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Vreme frumoasă',
'exif-lightsource-10'  => 'Cer noros',
'exif-lightsource-11'  => 'Umbră',
'exif-lightsource-12'  => 'Fluorescent luminos (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescent luminos alb (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescent alb rece (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluorescent alb (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Lumină standard A',
'exif-lightsource-18'  => 'Lumină standard B',
'exif-lightsource-19'  => 'Lumină standard C',
'exif-lightsource-24'  => 'Lumină artificială normată ISO în studio',
'exif-lightsource-255' => 'Altă sursă de lumină',

'exif-focalplaneresolutionunit-2' => 'ţoli',

'exif-sensingmethod-1' => 'Nedefinit',
'exif-sensingmethod-2' => 'Senzorul suprafeţei color one-chip',
'exif-sensingmethod-3' => 'Senzorul suprafeţei color two-chip',
'exif-sensingmethod-4' => 'Senzorul suprafeţei color three-chip',
'exif-sensingmethod-5' => 'Senzorul suprafeţei color secvenţiale',
'exif-sensingmethod-7' => 'Senzor triliniar',
'exif-sensingmethod-8' => 'Senzorul linear al culorii secvenţiale',

'exif-scenetype-1' => 'O imagine fotografiată direct',

'exif-customrendered-0' => 'Prelucrare normală',
'exif-customrendered-1' => 'Prelucrare nestandard',

'exif-exposuremode-0' => 'Expunere automată',
'exif-exposuremode-1' => 'Expunere manuală',
'exif-exposuremode-2' => 'Serie automată de expuneri',

'exif-whitebalance-0' => 'Auto-balanţa albă',
'exif-whitebalance-1' => 'Balanţa manuală albă',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Portret',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Scenă nocturnă',

'exif-gaincontrol-0' => 'Niciuna',
'exif-gaincontrol-1' => 'Avantajul scăzut de sus',
'exif-gaincontrol-2' => 'Avantajul mărit de sus',
'exif-gaincontrol-3' => 'Avantajul scăzut de jos',
'exif-gaincontrol-4' => 'Avantajul mărit de jos',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Redus',
'exif-contrast-2' => 'Mărit',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturaţie redusă',
'exif-saturation-2' => 'Saturaţie ridicată',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Uşor',
'exif-sharpness-2' => 'Tare',

'exif-subjectdistancerange-0' => 'Necunoscut',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Apropiat',
'exif-subjectdistancerange-3' => 'Îndepărtat',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'latitudine nordică',
'exif-gpslatitude-s' => 'latitudine sudică',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'longitudine estică',
'exif-gpslongitude-w' => 'longitudine vestică',

'exif-gpsstatus-a' => 'Măsurare în curs',
'exif-gpsstatus-v' => 'Măsurarea interoperabilităţii',

'exif-gpsmeasuremode-2' => 'măsurătoare bidimensională',
'exif-gpsmeasuremode-3' => 'măsurătoare tridimensională',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometri pe oră',
'exif-gpsspeed-m' => 'Mile pe oră',
'exif-gpsspeed-n' => 'Noduri',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direcţia reală',
'exif-gpsdirection-m' => 'Direcţie magnetică',

# External editor support
'edit-externally'      => 'Editează acest fişier folosind o aplicaţie externă.',
'edit-externally-help' => 'Vedeţi [http://meta.wikimedia.org/wiki/Help:External_editors instrucţiuni de instalare] pentru mai multe informaţii.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tot',
'imagelistall'     => 'toate',
'watchlistall2'    => 'toate',
'namespacesall'    => 'toate',
'monthsall'        => 'toate',

# E-mail address confirmation
'confirmemail'            => 'Confirmă adresa de email',
'confirmemail_noemail'    => 'Nu aveţi o adresă de email validă setată la [[Special:Preferences|preferinţe]].',
'confirmemail_text'       => 'Acest wiki necesită validarea adresei de email înaintea folosirii funcţiilor email. Apăsaţi butonul de dedesupt pentru a trimite un email de confirmare către adresa dvs. Acesta va include o legătură care va conţine codul; încărcaţi legătura în browser pentru a valida adresa de email.',
'confirmemail_send'       => 'Trimite un cod de confirmare',
'confirmemail_sent'       => 'E-mailul de confirmare a fost trimis.',
'confirmemail_oncreate'   => 'Un cod de confirmare a fost trimis la adresa de e-mail.
Acest cod nu este necesar pentru autentificare, dar trebuie transmis înainte de activarea oricăror proprietăţi bazate pe e-mail din wiki.',
'confirmemail_sendfailed' => 'Nu am putut trimite e-mailul de confirmare. Verificaţi adresa după caractere invalide.

Serverul de mail a returnat: $1',
'confirmemail_invalid'    => 'Cod de confirmare invalid. Acest cod poate fi expirat.',
'confirmemail_needlogin'  => 'Trebuie să vă $1 pentru a vă confirma adresa de email.',
'confirmemail_success'    => 'Adresa de email a fost confirmată. Vă puteţi autentifica şi bucura de wiki.',
'confirmemail_loggedin'   => 'Adresa de email a fost confirmată.',
'confirmemail_error'      => 'Ceva nu a funcţionat la salvarea confirmării.',
'confirmemail_subject'    => 'Confirmare adresă email la {{SITENAME}}',
'confirmemail_body'       => 'Cineva, probabil dumneavoastră de la adresa IP $1, şi-a înregistrat un cont "$2" cu această adresă de email la {{SITENAME}}.

Pentru a confirma că acest cont aparţine într-adevăr dumneavoastră şi să vă activaţi funcţionalităţile email la {{SITENAME}}, deschideţi această legătură în browser:

$3

Dacă *nu* sunteţi dumneavoastră, nu deschideţi legătura. Codul de confirmare va expira la $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Transcluderea interwiki este dezactivată]',
'scarytranscludefailed'   => '[Şiretlicul formatului a dat greş pentru $1; ne pare rău]',
'scarytranscludetoolong'  => '[URL-ul este prea lung; ne pare rău]',

# Trackbacks
'trackbackbox'      => "<div id='mw_trackbacks'>
Urmăritori la acest articol:<br />
$1
</div>",
'trackbackremove'   => ' ([$1 Şterge])',
'trackbacklink'     => 'Urmăritor',
'trackbackdeleteok' => 'Urmăritorul a fost şters cu succes.',

# Delete conflict
'deletedwhileediting' => 'Atenţie: Această pagină a fost ştearsă după ce ai început să o modifici!',
'confirmrecreate'     => "Utilizatorul [[User:$1|$1]] ([[User talk:$1|discuţie]]) a şters acest articol după ce aţi început să contribuţi la el din motivul:
: ''$2''
Vă rugăm să confirmaţi faptul că într-adevăr doriţi să recreaţi acest articol.",
'recreate'            => 'Recreează',

# HTML dump
'redirectingto' => 'Redirecţionând la [[$1]]...',

# action=purge
'confirm_purge'        => 'Doriţi să reîncărcaţi pagina? $1',
'confirm_purge_button' => 'Da',

# AJAX search
'searchcontaining' => "Caută articolele care conţin ''$1''.",
'searchnamed'      => "Caută articole cu numele ''$1''.",
'articletitles'    => "Articole începând cu ''$1''",
'hideresults'      => 'Ascunde rezultatele',

# Multipage image navigation
'imgmultipageprev' => '← pagina anterioară',
'imgmultipagenext' => 'pagina următoare →',
'imgmultigotopre'  => 'Mergi la pagina',

# Table pager
'table_pager_next'  => 'Pagina următoare',
'table_pager_prev'  => 'Pagina anterioară',
'table_pager_first' => 'Prima pagină',
'table_pager_last'  => 'Ultima pagină',
'table_pager_limit' => 'Arată $1 itemi pe pagină',
'table_pager_empty' => 'Nici un rezultat',

# Auto-summaries
'autosumm-blank'   => 'Şters conţinutul paginii',
'autosumm-replace' => "Înlocuit pagina cu '$1'",
'autoredircomment' => 'Redirecţionat înspre [[$1]]',
'autosumm-new'     => 'Pagină nouă: $1',

# Live preview
'livepreview-loading' => 'Încărcare…',
'livepreview-ready'   => 'Încărcare… Gata!',

# Watchlist editor
'watchlistedit-normal-title' => 'Editează lista de urmărire',
'watchlistedit-raw-titles'   => 'Titluri:',

# Watchlist editing tools
'watchlisttools-view' => 'Vizualizează schimbările relevante',

);
