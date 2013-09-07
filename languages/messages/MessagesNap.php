<?php
/** Neapolitan (Napulitano)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Carmine Colacino
 * @author Chelin
 * @author Cryptex
 * @author E. abu Filumena
 * @author PiRSquared17
 * @author SabineCretella
 * @author לערי ריינהארט
 */

$fallback = 'it';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciàle',
	NS_TALK             => 'Chiàcchiera',
	NS_USER             => 'Utente',
	NS_USER_TALK        => 'Utente_chiàcchiera',
	NS_PROJECT_TALK     => '$1_chiàcchiera',
	NS_FILE             => 'Fiùra',
	NS_FILE_TALK        => 'Fiùra_chiàcchiera',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_chiàcchiera',
	NS_TEMPLATE         => 'Modello',
	NS_TEMPLATE_TALK    => 'Modello_chiàcchiera',
	NS_HELP             => 'Ajùto',
	NS_HELP_TALK        => 'Ajùto_chiàcchiera',
	NS_CATEGORY         => 'Categurìa',
	NS_CATEGORY_TALK    => 'Categurìa_chiàcchiera',
);

$namespaceAliases = array(
	'Speciale' => NS_SPECIAL,
	'Discussione' => NS_TALK,
	'Utente' => NS_USER,
	'Discussioni_utente' => NS_USER_TALK,
	'Discussioni_$1' => NS_PROJECT_TALK,
	'Immagine' => NS_FILE,
	'Discussioni_immagine' => NS_FILE_TALK,
	'MediaWiki' => NS_MEDIAWIKI,
	'Discussioni_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Discussioni_template' => NS_TEMPLATE_TALK,
	'Aiuto' => NS_HELP,
	'Discussioni_aiuto' => NS_HELP_TALK,
	'Categoria' => NS_CATEGORY,
	'Discussioni_categoria' => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-underline' => "Sottolinia 'e jonte:",
'tog-justify' => "Alliniamento d''e paracrafe mpare",
'tog-hideminor' => "Annascunne 'e cagne piccirille  'int'a ll'úrdeme cagne",
'tog-hidepatrolled' => "Annascunne 'e cagne 'verificate' 'int'a ll'úrdeme cagne",
'tog-extendwatchlist' => "Spanne ll'asservate speciale pe fà vedé tutte 'e cagnàmiente, nun solo l'ultimo",
'tog-usenewrc' => 'Urdeme càgnamiente avanzate (JavaScript)',
'tog-numberheadings' => "Annúmmera automatecamente 'e títule",
'tog-showtoolbar' => "Aspone 'a barra d''e stromiente 'e cagno (JavaScript)",
'tog-editondblclick' => "Cagna 'e pàggene cliccanno ddoje vote (JavaScript)",
'tog-editsection' => "Permette 'e cagnà 'e sezzione cu a jonta [cagna]",
'tog-editsectiononrightclick' => "Permette 'e cangne 'e sezzione cliccanno p''o tasto destro ncopp 'e titule 'e sezzione (JavaScript)",
'tog-showtoc' => "Mosta ll'innece pe 'e paggene cu cchiù 'e 3 sezzione",
'tog-rememberpassword' => "Ricurda 'a registrazzione pe' cchiu sessione (ppe 'numassimo 'e $1 {{PLURAL:$1|juorno|juorne}})",

'underline-always' => 'Sèmpe',
'underline-never' => 'Màje',

# Dates
'sunday' => 'dumméneca',
'monday' => 'lunnerì',
'tuesday' => 'marterì',
'wednesday' => 'miercurì',
'thursday' => 'gioverì',
'friday' => 'viernarì',
'saturday' => 'sàbbato',
'sun' => 'dum',
'mon' => 'lun',
'tue' => 'mar',
'wed' => 'mier',
'thu' => 'gio',
'fri' => 'ven',
'sat' => 'sab',
'january' => 'jennaro',
'february' => 'frevàro',
'march' => 'màrzo',
'april' => 'abbrile',
'may_long' => 'màjo',
'june' => 'giùgno',
'july' => 'luglio',
'august' => 'aùsto',
'september' => 'settembre',
'october' => 'ottovre',
'november' => 'nuvembre',
'december' => 'dicèmbre',
'january-gen' => 'jennaro',
'february-gen' => 'frevaro',
'march-gen' => 'màrzo',
'april-gen' => 'abbrile',
'may-gen' => 'majo',
'june-gen' => 'giùgno',
'july-gen' => 'luglio',
'august-gen' => 'aùsto',
'september-gen' => 'settembre',
'october-gen' => 'ottovre',
'november-gen' => 'nuvembre',
'december-gen' => 'dicembre',
'jan' => 'jen',
'feb' => 'fre',
'mar' => 'mar',
'apr' => 'abb',
'may' => 'maj',
'jun' => 'giu',
'jul' => 'lug',
'aug' => 'aus',
'sep' => 'set',
'oct' => 'ott',
'nov' => 'nuv',
'dec' => 'dic',
'january-date' => '$1 jennaro',
'february-date' => '$1 frevaro',
'march-date' => '$1 màrzo',
'april-date' => '$1 abbrile',
'may-date' => '$1 majo',
'june-date' => '$1 giùgno',
'july-date' => '$1 luglio',
'august-date' => '$1 aùsto',
'september-date' => '$1 settembre',
'october-date' => '$1 ottovre',
'november-date' => '$1 nuvembre',
'december-date' => '$1 dicembre',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Categurìa|Categurìe}}',
'category_header' => 'Paggene rìnt\'a categurìa "$1"',
'subcategories' => 'Categurìe secunnarie',
'category-media-header' => 'File \'int\'â categuría "$1"',
'hidden-categories' => '{{PLURAL:$1|Categurìa annascusa|Categuríe annascuse}}',
'category-subcat-count' => "{{PLURAL:$2|Chesta categurìa cuntene n'unneca sottocategurìa, nzignata 'e seguito.|Chesta categurìa cuntene {{PLURAL:$1|'a sottocategurìa nzignata|'e $1 sottocategurìe nzignate}} 'e seguito, 'a nu totale 'e $2.}}",
'category-article-count' => "{{PLURAL:$2|Chesta categurìa cuntiene un'unneca paggena, nzignata ccà sotto.|Chesta categurìa cuntiene {{PLURAL:$1|'a paggena nzignata|'e $1 paggene nzignate}} ccà sotto, faccenno nu totale 'e $2.}}",
'listingcontinuesabbrev' => 'cont.',
'index-category' => 'Paggene ennecezzate',
'noindex-category' => 'Paggene nun ennecezzate',
'broken-file-category' => "Paggene cca 'ncludono file 'nesistente",

'about' => 'Nfromma',
'article' => 'Articulo',
'newwindow' => "(s'arape n'ata fenèsta)",
'cancel' => 'Scancèlla',
'moredotdotdot' => 'Cchiù...',
'morenotlisted' => 'Atro...',
'mypage' => 'Paggena',
'mytalk' => "'E mmie chiàcchieriate",
'anontalk' => 'Chiacchierate pe chisto IP',
'navigation' => 'Navigazzione',
'and' => '&#32;e',

# Cologne Blue skin
'qbfind' => 'Truòva',
'qbbrowse' => 'Sfoglia',
'qbedit' => 'Càgna',
'qbpageoptions' => 'Chesta paggena',
'qbmyoptions' => "'E ppaggene mie",
'qbspecialpages' => 'Pàggene speciàle',
'faq' => 'FAQ',
'faqpage' => 'Project:Domanne frequente',

# Vector skin
'vector-action-addsection' => 'Aggiunge chiacchierata',
'vector-action-delete' => 'Scancèlla',
'vector-action-move' => 'Spusta',
'vector-action-protect' => 'Prutegge',
'vector-action-undelete' => 'Ripristina',
'vector-action-unprotect' => 'Càgna prutezzione',
'vector-view-create' => 'Cria',
'vector-view-edit' => 'Càgna',
'vector-view-history' => 'Vere cronologgia',
'vector-view-view' => 'Legge',
'vector-view-viewsource' => 'Vere sorgente',
'actions' => 'Azione',
'namespaces' => 'Namespace',
'variants' => 'Variante',

'navigation-heading' => "Menu 'e navigazzione",
'errorpagetitle' => 'Sbaglio',
'returnto' => 'Torna a $1.',
'tagline' => 'Dda {{SITENAME}}.',
'help' => 'Ajùto',
'search' => 'Truova',
'searchbutton' => 'Truova',
'go' => 'Vàje',
'searcharticle' => 'Vàje',
'history' => "Verziune 'e primma",
'history_short' => 'Cronologgia',
'updatedmarker' => "cagnamiénte 'e mija urdema visita",
'printableversion' => "Verzione pe' stampa",
'permalink' => 'Jonta permanente',
'print' => 'Stampà',
'view' => 'Vere',
'edit' => 'Càgna',
'create' => 'Cria',
'editthispage' => 'Càgna chesta paggena',
'create-this-page' => 'Cria sta paggena',
'delete' => 'Scancèlla',
'deletethispage' => 'Scancèlla chésta paggena',
'undeletethispage' => 'Ripristina chista paggena',
'protect' => 'Prutegge',
'protect_change' => 'càgna',
'protectthispage' => 'Ferma chesta paggena',
'unprotect' => 'Càgna prutezzione',
'unprotectthispage' => "Càgna prutezzione 'e chesta paggena",
'newpage' => 'Paggena nòva',
'talkpage' => "Paggena 'e chiàcchiera",
'talkpagelinktext' => 'Chiàcchiera',
'specialpage' => 'Paggena speciàle',
'personaltools' => 'Strumiente perzonale',
'postcomment' => 'Nova sezzione',
'articlepage' => 'Vere a paggena e contenuto',
'talk' => 'Chiàcchiera',
'views' => 'Visite',
'toolbox' => 'Strumiente',
'userpage' => 'Vere a paggena utente',
'projectpage' => "Vere a paggena 'e servizio",
'imagepage' => 'Vere a paggena ddo file',
'mediawikipage' => "Vere 'a mmasciata",
'templatepage' => "Vere 'o template",
'viewhelppage' => "Vere 'a paggena 'e ajùto",
'categorypage' => "Vere 'a categurìa",
'viewtalkpage' => "Vere 'a paggena 'e chiàcchierate",
'otherlanguages' => 'Ate lengue',
'redirectedfrom' => "(Redirect 'a $1)",
'redirectpagesub' => "Paggena 'e redirect",
'lastmodifiedat' => "Urdema cagnamiénto pe' a paggena: $2, $1.",
'viewcount' => 'Chesta paggena è stata lètta {{PLURAL:$1|una vòta|$1 vòte}}.',
'protectedpage' => 'Paggena prutetta',
'jumpto' => 'Vaje a:',
'jumptonavigation' => 'navigazione',
'jumptosearch' => 'truova',
'pool-errorunknown' => 'Errore scanusciuto',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => "'Nfrummazione ncòpp'a {{SITENAME}}",
'aboutpage' => "Project:'Nfrummazione",
'copyright' => "Cuntenuti suggietti a licienza 'e uso $1.",
'copyrightpage' => '{{ns:project}}:Copyrights',
'currentevents' => 'Novità',
'currentevents-url' => 'Project:Novità',
'disclaimers' => 'Avvertimiènte',
'disclaimerpage' => 'Project:Avvertimiènte generale',
'edithelp' => 'Guida',
'helppage' => 'Help:Ajùto',
'mainpage' => 'Paggena prencepale',
'mainpage-description' => 'Paggena prencepale',
'policy-url' => 'Project:Policy',
'portal' => "Porta d''a cummunetà",
'portal-url' => "Project:Porta d''a cummunetà",
'privacy' => "'Nformazzione ppe a privacy",
'privacypage' => "Project:'Nfrummazione ncopp'â privacy",

'badaccess' => "Nun haje 'e premmesse abbastante.",

'ok' => 'OK',
'retrievedfrom' => 'Estratto \'e "$1"',
'youhavenewmessages' => 'Haje $1 ($2).',
'newmessageslink' => "nove 'mmasciàte",
'newmessagesdifflink' => "differenze cu 'a revisione precedente",
'youhavenewmessagesmulti' => 'Tiene nuove mmasciate $1',
'editsection' => 'càgna',
'editold' => 'càgna',
'viewsourceold' => 'vere sorgente',
'editlink' => 'càgna',
'viewsourcelink' => 'Vere sorgente',
'editsectionhint' => 'Modifica a sezzione $1',
'toc' => 'Énnece',
'showtoc' => 'faje vedé',
'hidetoc' => 'annascunne',
'collapsible-expand' => 'faje vede',
'thisisdeleted' => 'Vere o ripristina $1?',
'viewdeleted' => 'Vire $1?',
'feedlinks' => 'Feed:',
'site-atom-feed' => "Feed Atom 'e $1",
'page-atom-feed' => 'Feed Atom ppe "$1"',
'red-link-title' => '$1 (a paggena nun esiste)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Articulo',
'nstab-user' => 'Paggena utente',
'nstab-special' => 'Paggena speciale',
'nstab-project' => "Paggena 'e servizio",
'nstab-image' => 'Fiùra',
'nstab-mediawiki' => "'Mmasciata",
'nstab-template' => 'Modello',
'nstab-help' => 'Ajùto',
'nstab-category' => 'Categurìa',

# General errors
'missingarticle-rev' => "(nummero 'e verzione: $1)",
'internalerror' => "Errore 'nterno",
'internalerror_info' => "Errore 'nterno: $1",
'filedeleteerror' => 'Nun se pô scancellà \'o file "$1"',
'cannotdelete' => "Nun è possibbele scassà 'a paggena o 'a fiura addamannata. (Putria éssere stato già scancellato.)",
'badtitle' => "'O nnomme nun è jùsto",
'viewsource' => 'Vere sorgente',
'viewsource-title' => "Vere surgente 'e $1",
'exception-nologin' => 'Acciesso nun affettuato',

# Login and logout pages
'logouttext' => "'''Site asciùte.'''

Putite cuntinuà a ausà {{SITENAME}} comme n'utente senza nomme, o si nò putite trasì n'ata vota, cu 'o stesso nomme o cu n'ato nomme.",
'welcomeuser' => 'Bemmenuto, $1!',
'yourname' => 'Nomme utente',
'userlogin-yourname' => 'Nomme utente',
'userlogin-yourname-ph' => "'Nserisce 'o tujo nomme utente",
'createacct-another-username-ph' => "'Nserisce 'o nomme utente",
'yourpassword' => 'Password:',
'userlogin-yourpassword' => 'Password',
'userlogin-yourpassword-ph' => "'Nserisce 'a toja password",
'createacct-yourpassword-ph' => "'Nserisce 'na password",
'yourpasswordagain' => "Ripete 'a password:",
'createacct-yourpasswordagain' => 'Cunferma password',
'createacct-yourpasswordagain-ph' => "'Nserisce 'e novo 'a password",
'remembermypassword' => 'Allicuordate d"a password (for a maximum of $1 {{PLURAL:$1|day|days}})',
'userlogin-remembermypassword' => 'Mantienime cullegato',
'yourdomainname' => "Spiecà 'o dumminio",
'login' => 'Tràse',
'nav-login-createaccount' => "Tràse o cria n'acciesso novo",
'userlogin' => "Tràse o cria n'acciesso novo",
'userloginnocreate' => 'Tràse',
'logout' => 'Jèsce',
'userlogout' => 'Jèsce',
'notloggedin' => 'Acciesso nun affettuato',
'userlogin-noaccount' => "Nun haje ancora n'acciesso?",
'nologin' => "Nun haje ancora n'acciesso? '''$1'''.",
'nologinlink' => 'Crialo mmo',
'createaccount' => 'Cria nu cunto novo',
'gotaccount' => "Tiene già nu cunto? '''$1'''.",
'gotaccountlink' => 'Tràse',
'userlogin-resetlink' => "Haje dimenticato 'e toje date 'e acciesso?",
'createaccountreason' => 'Mutivo:',
'createacct-reason' => 'Mutivo',
'createacct-submit' => "Cria 'a toja utenza",
'createacct-another-submit' => "Cria 'n atro account",
'createacct-benefit-heading' => "{{SITENAME}} è fatta 'e perzone comme te.",
'createacct-benefit-body1' => '{{PLURAL:$1|càgnamiento|càgnamiente}}',
'createacct-benefit-body2' => '{{PLURAL:$1|paggena|paggene}}',
'loginerror' => "Probblema 'e accièsso",
'loginsuccesstitle' => 'Acciesso affettuato',
'loginsuccess' => "'''Si stato cunnesso ô server 'e {{SITENAME}} cu 'o nomme utente 'e \"\$1\".'''",
'nosuchusershort' => 'Nun ce stanno utente cu o nòmme "$1". Cuntrolla si scrivìste buòno.',
'nouserspecified' => "Tiene 'a dìcere nu nomme pricìso.",
'acct_creation_throttle_hit' => 'Ce dispiace, haje già criato $1 utente. Nun ne pô crià ate.',
'accountcreated' => 'Cunto criato',
'loginlanguagelabel' => 'Lengua: $1',

# Change password dialog
'resetpass' => 'Càgna password',
'resetpass-submit-cancel' => 'Canciella',

# Special:PasswordReset
'passwordreset-username' => 'Nomme utente:',
'passwordreset-domain' => 'Duminio:',

# Special:ChangeEmail
'changeemail-none' => '(nisciuno)',
'changeemail-cancel' => 'Canciella',

# Edit page toolbar
'bold_sample' => 'Grassetto',
'bold_tip' => 'Grassetto',
'italic_sample' => 'Corsivo',
'italic_tip' => 'Corsivo',
'link_sample' => 'Titulo ddo cullegamente',
'link_tip' => 'Jonte nterne',
'extlink_sample' => 'http://www.example.com titulo ddo cullegamente',
'headline_sample' => 'Testate',
'headline_tip' => "Testate 'e 2° livello",
'image_sample' => 'Essempio.jpg',
'image_tip' => 'Fiùra ncuorporata',
'media_tip' => 'Cullegamente a file multimediale',

# Edit pages
'summary' => 'Énnece',
'minoredit' => 'Chisto è nu cagnamiénto piccerillo',
'watchthis' => "Tiene d'uocchio chesta paggena",
'savearticle' => "Sarva 'a paggena",
'preview' => 'Anteprimma',
'showpreview' => 'Vere anteprimma',
'showdiff' => "Fa veré 'e cagnamiente",
'loginreqtitle' => "Pe' cagnà chesta paggena abbesognate aseguì ll'acciesso ô sito.",
'loginreqlink' => "aseguì ll'acciesso",
'loginreqpagetext' => "Pe' veré ate ppaggene abbesognate $1.",
'accmailtitle' => "'O password è stato mannato.",
'accmailtext' => '\'A password pe ll\'utente "$1" fuje mannata ô nnerizzo $2.',
'newarticle' => '(Novo)',
'previewnote' => "'''Chesta è sola n'anteprimma; 'e cagnamiénte â paggena nun songo ancora sarvate!'''",
'editing' => "Cagnamiento 'e $1",
'editingsection' => "Cagnamiénto 'e $1 (sezzione)",
'templatesused' => "{{PLURAL:$1|Template|Templates}} ausate 'a chesta paggena:",
'template-protected' => '(prutetto)',
'template-semiprotected' => '(semi-prutetto)',

# Content models
'content-model-css' => 'CSS',

# "Undo" feature
'undo-summary' => "Canciella 'o cagnamiento $1 'e [[Special:Contributions/$2|$2]] ([[User talk:$2|Chiàcchiera]])",

# History pages
'currentrev' => "Verzione 'e mmo",
'currentrev-asof' => 'Vversione attuale dde $1',
'revisionasof' => 'Vversione delle $1',
'previousrevision' => "← Verzione 'e primma",
'nextrevision' => 'Vversione cchiù recente →',
'currentrevisionlink' => "Verzione 'e mmo",
'cur' => 'corr',
'last' => 'prec',
'history-show-deleted' => 'Solo chille canciellate',
'histfirst' => 'primma',
'histlast' => 'urdema',

# Revision feed
'history-feed-item-nocomment' => "$1 'o $2",

# Revision deletion
'rev-delundel' => 'faje vedé/annascunne',
'rev-showdeleted' => 'faje vedé',
'revdelete-show-file-submit' => 'Sì',
'revdelete-radio-set' => 'Sì',
'revdelete-radio-unset' => 'No',
'revdel-restore' => 'càgna visiblità',

# Diffs
'lineno' => 'Riga $1:',
'editundo' => 'annulla',

# Search results
'searchresults' => "Risultato d''a recerca",
'searchresults-title' => 'Ascià risultate ppe "$1"',
'searchresulttext' => "Pe sapé de cchiù ncopp'â comme ascia 'a {{SITENAME}}, vere [[{{MediaWiki:Helppage}}|Ricerca in {{SITENAME}}]].",
'notitlematches' => "Voce addemannata nun truvata dint' 'e titule 'e articulo",
'notextmatches' => "Voce addemannata nun truvata dint' 'e teste 'e articulo",
'prevn' => '{{PLURAL:$1|precedente|precedente $1}}',
'nextn' => '{{PLURAL:$1|successivo|successive $1}}',
'prevn-title' => '{{PLURAL:$1|Risultato precediente|$1 risultate precedenti}}',
'nextn-title' => '{{PLURAL:$1|Risultato successivo|$1 risultate successive}}',
'shown-title' => "Fa vere {{PLURAL:$1|'nu risultato|$1 risultate}} ppe paggena",
'viewprevnext' => 'Vere($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-new' => "'''Cria a paggena \"[[:\$1]]\" ncopp'â chisto wiki!'''",
'searchprofile-articles' => "Paggene 'e contenute",
'searchprofile-project' => "Paggene 'e ajùto e relative 'o prugietto",
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Tutto',
'searchprofile-advanced' => 'Avanzate',
'searchprofile-articles-tooltip' => "Circa dint'ô $1",
'searchprofile-project-tooltip' => "Circa dint'ô $1",
'searchprofile-images-tooltip' => 'Circa file',
'searchprofile-advanced-tooltip' => "Circa dint'e namespace perzonalizzate",
'search-result-size' => "$1 ({{PLURAL:$2|'na parola|$2 parole}})",
'search-redirect' => '(redirect $1)',
'search-section' => '(sezzione $1)',
'search-suggest' => 'Prova chisto: $1',
'search-interwiki-caption' => 'Prugiette frate',
'searchall' => 'Tutte',
'search-nonefound' => "'A ricerca nun ha produtto risultate.",
'powersearch' => 'Truova',

# Preferences page
'mypreferences' => "Preferenze d''e mmeje",
'changepassword' => 'Cagna password',
'prefs-rc' => 'Urdeme nove',
'prefs-watchlist' => 'Asservate speciale',
'saveprefs' => 'Sarva',
'columns' => 'Culonne:',
'searchresultshead' => 'Truova',
'timezoneregion-africa' => 'Afreca',
'youremail' => 'E-mail:',
'username' => '{{GENDER:$1|Nomme utente}}:',
'yourrealname' => 'Nomme vero',
'yourlanguage' => 'Lengua:',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'càgna chesta paggena',

# Recent changes
'recentchanges' => 'Urdeme nove',
'recentchanges-legend' => 'Opzione urdeme cagnamiénte',
'recentchanges-summary' => "Ncoppa chesta paggena song' appresentate ll'urdeme cagnamiente fatto ê cuntenute d\"o sito.",
'recentchanges-label-newpage' => "Chista modifica ha criato 'na nova paggena",
'recentchanges-label-minor' => 'Chisto è nu cagnamiénto piccerillo',
'recentchanges-label-unpatrolled' => "Chista modifica nun è stata 'ncora verificata",
'rcnote' => "Ccà sotto nce songo ll'urdeme {{PLURAL:$1|cangiamiento|'''$1''' cangiamiente}} 'e ll'urdeme {{PLURAL:$2|juorno|'''$2''' juorne}}, agghiuornate alle $5 ddo $4.",
'rclistfrom' => "Faje vedé 'e cagnamiénte fatte a partì 'a $1",
'rcshowhideminor' => "$1 'e cagnamiénte piccerille",
'rcshowhidebots' => "$1 'e bot",
'rcshowhideliu' => "$1 ll'utente reggìstrate",
'rcshowhideanons' => "$1 ll'utente anonime",
'rcshowhidemine' => "$1 'e ffatiche mmee",
'rclinks' => "Faje vedé ll'urdeme $1 cagnamiente dint' ll'urdeme $2 juorne<br />$3",
'diff' => 'diff',
'hist' => 'cron',
'hide' => 'annascunne',
'show' => 'faje vedé',
'minoreditletter' => 'm',
'newpageletter' => 'N',
'boteditletter' => 'b',
'rc_categories_any' => 'Qualònca',
'rc-enhanced-expand' => 'Fa vede dettaglie (richiede JavaScript)',
'rc-enhanced-hide' => 'Annascunne dettaglie',

# Recent changes linked
'recentchangeslinked' => 'Cagnamiénte cullegate',
'recentchangeslinked-feed' => 'Cagnamiénte cullegate',
'recentchangeslinked-toolbox' => 'Cagnamiénte cullegate',
'recentchangeslinked-title' => 'Cagnamiénte cullegate a "$1"',
'recentchangeslinked-page' => 'Nomme dda paggena',

# Upload
'upload' => 'Careca file',
'filedesc' => 'Énnece',
'uploadedimage' => 'ha carecato "[[$1]]"',

'license' => 'Licenze:',
'license-header' => 'Licenza',

# Special:ListFiles
'listfiles_name' => 'Nomme',

# File description page
'file-anchor-link' => 'Fiùra',
'filehist' => 'Cronologgia d"o file',
'filehist-revert' => 'ripristina',
'filehist-current' => 'attuale',
'filehist-datetime' => 'Data/Ora',
'filehist-thumb' => 'Miniature',
'filehist-user' => 'Utente',
'filehist-dimensions' => 'Dimenzione',
'imagelinks' => 'Jonte ê ffiure',

# Random page
'randompage' => 'Na paggena qualsiase',
'randompage-nopages' => "Nun gè song paggene {{PLURAL:$2|dint'ô seguente namespace|dint'ê seguenti namespace}}: $1.",

# Statistics
'statistics' => 'Statistiche',

'doubleredirects' => 'Redirect duppie',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories' => '$1 {{PLURAL:$1|categoria|categorie}}',
'nlinks' => '$1 {{PLURAL:$1|cullegamiento|cullegamiente}}',
'nmembers' => '$1 {{PLURAL:$1|elemento|elemente}}',
'popularpages' => "Paggene cchiù 'speziunate",
'wantedpages' => 'Paggene cchiù addemannate',
'shortpages' => 'Paggene curte',
'longpages' => 'Paggene cchiú longhe',
'usercreated' => "{{GENDER:$3|Criato/a}} 'o $1 a $2",
'newpages' => 'Paggene cchiù frische',
'move' => 'Spusta',
'movethispage' => 'Spusta chesta paggena',
'pager-older-n' => '{{PLURAL:$1|1 cchiù viecchio|$1 cchiù viecchie}}',

# Book sources
'booksources' => 'Funte libbrarie',
'booksources-go' => 'Vàje',

# Special:Log
'log' => 'Logs',

# Special:AllPages
'allpages' => "Tutte 'e ppaggene",
'alphaindexline' => 'da $1 a $2',
'allarticles' => "Tutt' 'e vvoce",
'allinnamespace' => "Tutt' 'e ppaggene d''o namespace $1",
'allpagessubmit' => 'Vàje',

# Special:Categories
'categories' => 'Categurìe',
'categoriespagetext' => "Lista cumpleta d\"e categurie presente ncopp' 'o sito.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].",

# Special:LinkSearch
'linksearch-ok' => 'Truova',
'linksearch-line' => "$1 presente dint'â paggena $2",

# Email user
'emailuser' => 'Email chisto utente',

# Watchlist
'mywatchlist' => 'osservate speciale',
'watchlistfor2' => 'Ppe $1 $2',
'watch' => 'Secuta',
'unwatch' => 'Nun segui',
'notanarticle' => 'Chesta paggena nun è na voce',

'changed' => 'cagnata',

# Delete
'deletepage' => 'Scancella paggena',
'excontent' => "'o cuntenuto era: '$1'",
'excontentauthor' => "'o cuntenuto era: '$1' (e ll'unneco cuntribbutore era '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "'O cuntenuto apprimm' 'a ll'arrevacamento era: '$1'",
'exblank' => "'a paggena era vacante",
'actioncomplete' => 'Azzione fernuta',
'deletedtext' => 'Qauccheruno ha scancellata \'a paggena "$1".  Addumannà \'o $2 pe na lista d"e ppaggene scancellate urdemamente.',
'dellogpage' => 'Scancellazione',
'deletionlog' => 'Log d"e scancellazione',
'deletecomment' => 'Raggióne',

# Rollback
'rollback' => "Ausa na revizione 'e primma",
'rollbacklink' => 'a vascio',
'revertpage' => "Cangiaje 'e cagnamiénte 'e [[Special:Contributions/$2|$2]] ([[User talk:$2|discussione]]), cu â verzione 'e pprimma 'e  [[User:$1|$1]]",

# Protect
'protectedarticle' => 'ha prutetto "[[$1]]"',
'prot_1movedto2' => 'ha spustato [[$1]] a [[$2]]',
'protect-expiry-options' => '2 ore:2 hours,1 juorno:1 day,3 juorne:3 days,1 semmana:1 week,2 semmane:2 weeks,1 mise:1 month,3 mese:3 months,6 mese:6 months,1 anno:1 year,infinito:infinite',

# Undelete
'viewdeletedpage' => "Vìre 'e ppàggine scancellate",
'undeleteviewlink' => 'vere',

# Namespace form on various pages
'namespace' => 'Namespace:',
'invert' => "abbarruca 'a sceveta",
'blanknamespace' => '(Prencepale)',

# Contributions
'contributions' => 'Contribbute {{GENDER:$1|utente}}',
'mycontris' => "'E ffatiche d''e mmeje",
'contribsub2' => 'Ppe $1 ($2)',
'uctop' => '(attuale)',
'month' => 'D"o mese (e primma):',
'year' => "'E ll'anno (e primma):",

'sp-contributions-newbies' => "Mosta solo 'e contribbute dde nove utente",
'sp-contributions-blocklog' => 'blocche',
'sp-contributions-logs' => 'registre',
'sp-contributions-talk' => 'Chiàcchiera',
'sp-contributions-username' => 'Nnerizzo IP o nomme utente',
'sp-contributions-submit' => 'Truova',

# What links here
'whatlinkshere' => 'Paggene ca cullegano a chesta',
'whatlinkshere-title' => 'Paggene ca cullegano a $1',
'whatlinkshere-page' => 'Paggena:',
'nolinkshere' => "Nisciuna paggena cuntene jonte ca mpuntano a '''[[:$1]]'''.",
'isredirect' => 'redirect a paggena',
'istemplate' => "'nclusione",
'isimage' => 'Cullegamente a file multimediale',
'whatlinkshere-links' => '← jonte',
'whatlinkshere-hideredirs' => '$1 redirects',
'whatlinkshere-hidetrans' => "$1 'nclusione",
'whatlinkshere-hidelinks' => '$1 jonte',
'whatlinkshere-hideimages' => '$1 links ddo file',
'whatlinkshere-filters' => 'Filtre',

# Block/unblock
'blockip' => 'Ferma utelizzatóre',
'ipadressorusername' => 'Nnerizzo IP o nomme utente',
'ipboptions' => '2 ore:2 hours,1 juorno:1 day,3 juorne:3 days,1 semmana:1 week,2 semmane:2 weeks,1 mise:1 month,3 mese:3 months,6 mese:6 months,1 anno:1 year,infinito:infinite',
'blockipsuccesssub' => 'Blocco aseguito',
'ipblocklist' => 'Utenti bloccate',
'blocklink' => 'ferma',
'unblocklink' => 'sblocca',
'change-blocklink' => 'càgna blocco',
'contribslink' => 'contribuzzione',
'blocklogpage' => 'Blocche',
'blocklogentry' => 'ha fermato "[[$1]]" pe\' nu mumento \'e $2 $3',
'blocklogtext' => "Chesta è 'a lista d''e azzione 'e blocco e sblocco utente.  'E nnerizze IP bloccate automaticamente nun nce so'. Addumannà 'a [[Special:BlockList|lista IP bloccate]] pp' 'a lista d''e nnerizze e nomme utente 'o ca blocco nce sta.",

# Move page
'movearticle' => "Spusta 'a paggena",
'newtitle' => 'Titulo nuovo:',
'movepagebtn' => "Spusta 'a paggena",
'articleexists' => "Na paggena cu chisto nomme asiste già, o pure 'o nomme scegliuto nun è buono.  Scegliere n'ato titulo.",
'movedto' => 'spustata a',
'movelogpage' => 'Spustamente',
'movereason' => 'Raggióne',
'revertmove' => 'ripristina',
'delete_and_move' => 'Scancèlla e spusta',
'delete_and_move_confirm' => "Sì, suprascrivi 'a paggena asistente",

# Export
'export' => "Spurta 'e ppaggene",

# Namespace 8 related
'allmessages' => "'Mmasciate d''o sistema",
'allmessagesname' => 'Nomme',
'allmessagescurrent' => "Testo 'e mo",

# Thumbnails
'thumbnail-more' => 'Ingrandisce',

# Special:Import
'import' => 'Mpurta paggene',
'import-interwiki-submit' => 'Mpurta',

# Import log
'import-logentry-upload' => 'ha mpurtato [[$1]] trammeto upload',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'A toja paggena utente',
'tooltip-pt-mytalk' => "A toja paggena 'e discussione",
'tooltip-pt-preferences' => "Preferenze d''e mmeje",
'tooltip-pt-mycontris' => 'Elenco dde tuje contributte',
'tooltip-pt-login' => 'A reggistrazione è cunsigliata',
'tooltip-pt-logout' => 'Jésce (logout)',
'tooltip-ca-talk' => 'Vede e discussione rilative a chista paggena',
'tooltip-ca-edit' => "Puo modifica chista paggena. Ppe favore usa 'o pulsante 'e anteprimma primma 'e salva",
'tooltip-ca-addsection' => "Cummincia 'na nova sezzione",
'tooltip-ca-viewsource' => "Chista paggena è prutetta, ma puo vere 'o codice sorgente",
'tooltip-ca-history' => "Vversione precedente 'e chista paggena",
'tooltip-ca-protect' => 'Prutegge chesta paggena',
'tooltip-ca-delete' => 'Scancèlla chista paggena',
'tooltip-ca-move' => 'Spusta chista paggena',
'tooltip-search' => "Truova dint'ô {{SITENAME}}",
'tooltip-search-fulltext' => "Ascià 'o testo indicato dint'e paggene",
'tooltip-p-logo' => 'Visita a paggena prencepale',
'tooltip-n-mainpage' => 'Visita a paggena prencepale',
'tooltip-n-mainpage-description' => 'Visita a paggena prencepale',
'tooltip-n-portal' => 'Descrizione ddo prugietto, che pou fa, addo truova e cose',
'tooltip-n-recentchanges' => 'Ennece dde urdeme cagnamiénte ddo sito',
'tooltip-n-randompage' => 'Na paggena qualsiase',
'tooltip-n-help' => "Paggena 'e ajùto",
'tooltip-t-whatlinkshere' => "'Na lista 'e tutte e paggene ca song cullegate a chista",
'tooltip-t-recentchangeslinked' => 'Urdeme cagnamiénte dde paggene ca cullegano a chesta',
'tooltip-t-contributions' => "Lista dde contributte 'e chisto utente",
'tooltip-t-emailuser' => "Manna 'nu email a chisto utente",
'tooltip-t-upload' => 'Careca file',
'tooltip-t-specialpages' => "Lista 'e tutte e paggene speciale",
'tooltip-t-print' => "Vversione pe' stampa 'e chista paggena",
'tooltip-t-permalink' => 'Jonta permanente a chista vversione dda paggena',
'tooltip-ca-nstab-main' => 'Vere a paggena e contenuto',
'tooltip-ca-nstab-user' => 'Vere a paggena utente',
'tooltip-ca-nstab-special' => "Chista è 'na paggena speciale, nun può essere càgnata",
'tooltip-ca-nstab-project' => "Vere a paggena 'e servizio",
'tooltip-ca-nstab-image' => 'Vere a paggena ddo file',
'tooltip-ca-nstab-template' => "Vere 'o modello",
'tooltip-ca-nstab-category' => 'Vere a paggena d"a categurìa',
'tooltip-minoredit' => 'Rénne chìsto cagnamiénto cchiù ppiccirìllo.',
'tooltip-save' => "Sàrva 'e cagnamiénte.",
'tooltip-preview' => "Primma 'e sarvà, vìre primma chille ca hê cagnàte!",
'tooltip-rollback' => "\"A vascio\" annulla 'e modefeche 'e chista paggena ell'ultimo contributore cu n' uneco click",

# Attribution
'others' => 'ate',

# Browsing diffs
'previousdiff' => '← Càgnamiento cchiù viecchio',
'nextdiff' => 'Cagnamiénte successivo →',

# Media information
'file-info-size' => '$1 × $2 pixel, dimenzione ddo file: $3, tipo MIME: $4',
'show-big-image' => 'Vversione ad ata risoluzione',

# Special:NewFiles
'noimages' => "Nun nc'è nind' 'a veré.",
'ilsubmit' => 'Truova',

# Metadata
'metadata' => 'Metadate',

'exif-xyresolution-i' => '$1 punte pe pollice (dpi)',

'exif-meteringmode-0' => 'Scanusciuto',
'exif-meteringmode-255' => 'Ato',

'exif-lightsource-0' => 'Scanusciuta',
'exif-lightsource-10' => "'Ntruvulato",
'exif-lightsource-11' => 'Aumbruso',

'exif-gaincontrol-0' => 'Nisciuno',

'exif-subjectdistancerange-0' => 'Scanusciuta',

# External editor support
'edit-externally-help' => "Pe piglià cchiù nfromma veré 'e [//www.mediawiki.org/wiki/Manual:External_editors struzione] ('n ngrese)",

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tutte',
'namespacesall' => 'Tutte',
'monthsall' => 'tutte',

# Email address confirmation
'confirmemail_needlogin' => "Abbesognate $1 pe cunfirmà 'o nnerizzo 'e e-mail d''o vuosto.",
'confirmemail_loggedin' => "'O nnerizzo 'e e-mail è vàleto",

# Delete conflict
'deletedwhileediting' => 'Attenziòne: quaccherùno have scancellàto chesta pàggena prìmma ca tu accuminciàste â scrìvere!',

# Auto-summaries
'autoredircomment' => 'Redirect â paggena [[$1]]',
'autosumm-new' => "Paggena criate ch''o: $1",

# Special:SpecialPages
'specialpages' => 'Paggene speciale',

# Special:Tags
'tag-filter' => 'Filtra ppe [[Special:Tags|etichetta]]:',

# New logging system
'rightsnone' => '(nisciuno)',

);
