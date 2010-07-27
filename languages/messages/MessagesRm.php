<?php
/** Romansh (Rumantsch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Gion
 * @author Gion-andri
 * @author Kazu89
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$messages = array(
# User preference toggles
'tog-underline'               => 'suttastritgar colliaziuns:',
'tog-highlightbroken'         => 'Formatar links betg existents <a href="" class="new">uschia</a> (alternativa: uschia<a href="" class="internal">?</a>)',
'tog-justify'                 => "Text en furma da 'bloc'",
'tog-hideminor'               => 'Zuppentar pitschnas midadas en las ultimas midadas',
'tog-usenewrc'                => "Duvrar la versiun extendida da las ''Ulimas midadas'' (dovra JavaScript)",
'tog-numberheadings'          => 'Numerar automaticamain ils titels',
'tog-showtoolbar'             => "Mussa la trav d'utensils (basegna JavaScript)",
'tog-editondblclick'          => 'Editar paginas cun in clic dubel (basegna JavaScript)',
'tog-editsection'             => 'Activar links per [editar] secziuns',
'tog-editsectiononrightclick' => "Activar la pussaivladad d'editar secziuns cun in clic dretg (basegna JavaScript)",
'tog-showtoc'                 => 'Mussar ina tabla da cuntegn sin paginas cun dapli che trais tetels',
'tog-rememberpassword'        => "S'annunziar permanantamain (be sch'il pled-clav n'è batg vegnì generà da la software da MediaWiki)",
'tog-watchcreations'          => "Observar paginas ch'jau hai creà",
'tog-watchdefault'            => "Observar paginas ch'jau hai edità",
'tog-watchmoves'              => "Observar paginas ch'jau hai spustà",
'tog-watchdeletion'           => "Observar paginas ch'jau hai stizzà",
'tog-nocache'                 => 'deactivar il caching da la pagina',
'tog-enotifwatchlistpages'    => "Trametta in e-mail sch'ina pagina sin mia glista d'observaziun vegn midada",
'tog-enotifusertalkpages'     => "Trametta in e-mail sch'i ha dà midadas sin mia pagina da discussiun.",
'tog-enotifminoredits'        => 'Trametta era in e-mail tar pitschnas midadas da las paginas',
'tog-fancysig'                => "Suttascripziun senza link automatic tar la pagina da l'utilisader.",
'tog-showjumplinks'           => 'Activar las colliaziuns "seglir a"',
'tog-watchlisthideown'        => "Zuppa mias modificaziuns en la glista d'observaziun",
'tog-watchlisthidebots'       => "Zuppa modificaziuns da bots en la glista d'observaziun",
'tog-watchlisthideminor'      => "Zuppa pitschnas modificaziuns en la glista d'observaziun",
'tog-watchlisthideliu'        => "Zuppa modificaziuns d'utilisaders ch'èn s'annunziads en la glista d'observaziun",
'tog-watchlisthideanons'      => "Zuppa modificaziuns da utilisaders anonims en la glista d'observaziun",
'tog-showhiddencats'          => 'Mussar categorias zuppendatas',

'underline-always'  => 'adina suttastritgar',
'underline-never'   => 'mai suttastritgar',
'underline-default' => 'surprender standard dal browser',

# Dates
'sunday'        => 'Dumengia',
'monday'        => 'Glindesdi',
'tuesday'       => 'mardi',
'wednesday'     => 'mesemna',
'thursday'      => 'Gievgia',
'friday'        => 'Venderdi',
'saturday'      => 'sonda',
'sun'           => 'du',
'mon'           => 'Gli',
'tue'           => 'ma',
'wed'           => 'mes',
'thu'           => 'gie',
'fri'           => 've',
'sat'           => 'so',
'january'       => 'schaner',
'february'      => 'favrer',
'march'         => 'mars',
'april'         => 'avril',
'may_long'      => 'matg',
'june'          => 'zercladur',
'july'          => 'fanadur',
'august'        => 'avust',
'september'     => 'Settember',
'october'       => 'october',
'november'      => 'november',
'december'      => 'december',
'january-gen'   => 'schaner',
'february-gen'  => 'favrer',
'march-gen'     => 'mars',
'april-gen'     => 'avril',
'may-gen'       => 'matg',
'june-gen'      => 'zercladur',
'july-gen'      => 'fanadur',
'august-gen'    => 'avust',
'september-gen' => 'settember',
'october-gen'   => 'october',
'november-gen'  => 'november',
'december-gen'  => 'december',
'jan'           => 'schan',
'feb'           => 'favr',
'mar'           => 'mars',
'apr'           => 'avr',
'may'           => 'matg',
'jun'           => 'zercl',
'jul'           => 'fan',
'aug'           => 'avu',
'sep'           => 'sett',
'oct'           => 'oct',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header'          => 'Artitgels en la categoria "$1"',
'subcategories'            => 'sutcategorias',
'category-media-header'    => 'Datotecas en la categoria "$1"',
'category-empty'           => "''Questa categoria cuntegna actualmain nagins artitgels e naginas datotecas.''",
'hidden-categories'        => '{{PLURAL:$1|Categoria zuppentada|Categorias zuppentadas}}',
'hidden-category-category' => 'Zuppa categorias', # Name of the category where hidden categories will be listed
'category-subcat-count'    => '{{PLURAL:$2|Questa categoria cuntegna be suandanta sutcategoria.|Questa categoria cuntegna {{PLURAL:$1|la suandanta sutcategoria|las $1 suandantas sutcategorias}} da totalmain $2 sutcategoria.}}',
'category-article-count'   => '{{PLURAL:$2|Questa categoria cuntegna be la suandanta pagina.|{{PLURAL:$1|La suandanta pagina è|Las $1 suandantas paginas èn}} en questa categoria che cuntegna totalmain $2 paginas.}}',
'listingcontinuesabbrev'   => 'cuntinuaziun',

'about'          => 'Surda',
'article'        => 'artitgel',
'newwindow'      => '(avra ina nova fanestra)',
'cancel'         => 'refusar las midadas',
'qbfind'         => 'Chattar',
'qbbrowse'       => 'Sfegliar',
'qbedit'         => 'Editar',
'qbpageoptions'  => 'Questa pagina',
'qbpageinfo'     => 'Context',
'qbmyoptions'    => 'Mia pagina',
'qbspecialpages' => 'paginas spezialas',
'moredotdotdot'  => 'Dapli...',
'mypage'         => 'mia pagina',
'mytalk'         => 'Mia pagina da discussiun',
'anontalk'       => 'Pagina da discussiun da questa IP',
'navigation'     => 'Navigaziun',
'and'            => '&#32;e',

# Metadata in edit box
'metadata_help' => 'Datas da meta:',

'errorpagetitle'    => 'Errur',
'returnto'          => 'Enavos tar $1.',
'tagline'           => 'Ord {{SITENAME}}',
'help'              => 'Agid',
'search'            => 'Tschertgar',
'searchbutton'      => 'Tschertgar',
'go'                => 'Artitgel',
'searcharticle'     => 'dai!',
'history'           => 'versiuns',
'history_short'     => 'versiuns/auturs',
'updatedmarker'     => "actualisà dapi mi'ultima visita",
'info_short'        => 'Infurmaziun',
'printableversion'  => 'versiun per stampar',
'permalink'         => 'Link permanent',
'print'             => 'stampar',
'edit'              => 'Editar',
'create'            => 'Crear',
'editthispage'      => 'Editar questa pagina',
'create-this-page'  => 'Crear questa pagina',
'delete'            => 'Stizzar',
'deletethispage'    => 'Stizzar questa pagina',
'undelete_short'    => 'Revocar {{PLURAL:$1|ina modificaziun|$1 modificaziuns}}',
'protect'           => 'proteger',
'protect_change'    => 'midar',
'protectthispage'   => 'Protegier questa pagina',
'unprotect'         => 'Nunprotegì',
'unprotectthispage' => 'Annullescha la protecziun da la pagina',
'newpage'           => 'Nova pagina',
'talkpage'          => 'Discutar quest artitgel',
'talkpagelinktext'  => 'Discussiun',
'specialpage'       => 'Pagina speziala',
'personaltools'     => 'Utensils persunals',
'postcomment'       => 'Nova secziun',
'articlepage'       => 'guardar artitgel',
'talk'              => 'discussiun',
'views'             => 'Questa pagina',
'toolbox'           => 'Utensils',
'userpage'          => "Guardar la pagina d'utilisader",
'projectpage'       => 'Guardar la pagina da project',
'imagepage'         => 'Guardar la pagina da datotecas',
'mediawikipage'     => 'Guardar la pagina da messadis',
'templatepage'      => 'Mussar la pagina dal model',
'viewhelppage'      => "Guardar pagina d'agid",
'categorypage'      => 'Guardar la pagina da questa categoria',
'viewtalkpage'      => 'guardar la discussiun',
'otherlanguages'    => 'En auteras linguas',
'redirectedfrom'    => '(renvià da $1)',
'redirectpagesub'   => "questa pagina renviescha tar in'auter artitgel",
'lastmodifiedat'    => "L'ultima modificaziun da questa pagina: ils $1 a las $2.", # $1 date, $2 time
'viewcount'         => 'Questa pagina è vegnida guardada {{PLURAL:$1|ina giada|$1 giadas}}.',
'protectedpage'     => 'Pagina protegida',
'jumpto'            => 'Midar tar:',
'jumptonavigation'  => 'navigaziun',
'jumptosearch'      => 'tschertga',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Davart {{SITENAME}}',
'aboutpage'            => 'Project:Davart',
'copyright'            => 'Cuntegn disponibel sut $1.',
'copyrightpagename'    => '{{ns:project}}:Resguardar_dretgs_d_autur',
'copyrightpage'        => '{{ns:project}}:Resguardar_dretgs_d_autur',
'currentevents'        => 'Events actuals',
'currentevents-url'    => 'Project:Events actuals',
'disclaimers'          => 'Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'agid per editar',
'edithelppage'         => 'Help:Prims pass',
'faq'                  => 'FAQ',
'faqpage'              => 'Project:FAQ',
'helppage'             => 'Help:Cuntegn',
'mainpage'             => 'Pagina principala',
'mainpage-description' => 'Pagina principala',
'policy-url'           => 'Project:Directivas',
'portal'               => 'Portal da {{SITENAME}}',
'portal-url'           => 'Project:Portal da {{SITENAME}}',
'privacy'              => 'Protecziun da datas',
'privacypage'          => 'Project:Protecziun_da_datas',

'badaccess'        => "Errur dad access: vus n'avais betg avunda dretgs",
'badaccess-group0' => "Vus na dastgais betg exequir l'acziun giavischada.",
'badaccess-groups' => "L'acziun che vus vulais far dastgan mo utilisaders en {{PLURAL:$2|las gruppas|la gruppa}} $1 exequir.",

'versionrequired'     => 'Versiun $1 da MediaWiki è necessaria',
'versionrequiredtext' => 'Ti dovras versiun $1 da mediawiki per duvrar questa pagina. Guarda [[Special:Version| qua!]]',

'ok'                      => "D'accord",
'retrievedfrom'           => 'Da "$1"',
'youhavenewmessages'      => 'Ti has $1 ($2).',
'newmessageslink'         => 'novs messadis',
'newmessagesdifflink'     => "l'ultima midada",
'youhavenewmessagesmulti' => 'Ti as novs messadis en $1',
'editsection'             => 'editar',
'editold'                 => 'editar',
'viewsourceold'           => 'guardar code funtauna',
'editlink'                => 'editar',
'viewsourcelink'          => 'guardar code funtauna',
'editsectionhint'         => 'Editar secziun: $1',
'toc'                     => 'Cuntegn',
'showtoc'                 => 'mussar',
'hidetoc'                 => 'zuppar',
'thisisdeleted'           => 'Guardar u restaurar $1?',
'viewdeleted'             => 'Mussa $1?',
'restorelink'             => '{{PLURAL:$1|ina modificaziun stizzada|$1 modificaziuns stizzadas}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Faus tip da feed per la subscripziun.',
'feed-unavailable'        => "Feed n'è betg disponibel",
'site-rss-feed'           => 'RSS Feed da $1',
'site-atom-feed'          => 'Atom Feed da $1',
'page-rss-feed'           => 'RSS Feed "$1"',
'page-atom-feed'          => 'Atom feed "$1"',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => "$1 (n'exista betg)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artitgel',
'nstab-user'      => "Pagina da l'utilisader",
'nstab-media'     => 'Pagina da medias',
'nstab-special'   => 'Pagina speziala',
'nstab-project'   => 'pagina dal project',
'nstab-image'     => 'Datoteca',
'nstab-mediawiki' => 'messadi',
'nstab-template'  => 'Model',
'nstab-help'      => 'Agid',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => "Talas acziuns n'existan betg",
'nosuchactiontext'  => "L'acziun specifitgada per questa URL è faussa.
Vus avais endatà fauss la URL, u avais suandà in link incorrect.
I po dentant er esser in errur en la software da {{SITENAME}}.",
'nosuchspecialpage' => "I n'exista betg ina tala pagina speziala",
'nospecialpagetext' => "<strong>Vus avais tschertgà ina pagina speziala che n'exista betg.</strong>

Ina glista da las paginas spezialas existentas chattais vus sut [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'               => 'Errur',
'databaseerror'       => 'Sbagl da la datoteca',
'dberrortext'         => 'In sbagl da la sintaxa da la dumonda a la banca da datas è capità. 
Quai po esser in sbagl en la software. 
L\'ultima dumonda per la banca da datas era:
<blockquote><tt>$1</tt></blockquote>
ord la funcziun "<tt>$2</tt>".
La banca da datas ha rapporta l\'errur "<tt>$3: $4</tt>".',
'dberrortextcl'       => 'In sbagl da la sintaxa da la dumonda a la banca da datas è capità. 
L\'ultima dumonda per la banca da datas era:
"$1"
ord la funcziun "$2".
La banca da datas ha rapporta l\'errur "$3: $4"',
'laggedslavemode'     => 'Attenziun: La pagina mussada na cuntign eventualmain betg anc las ultimas midadas.',
'missing-article'     => 'Il text da la pagina cun il num "$1" $2 n\'è betg vegnì chattà en la banca da datas.

Quai capita sch\'ins suonda in link che n\'è betg pli actuals u in link sin ina pagina ch\'è vegnida stizzada.

Sche quai na duess betg esser il cas, lura è quai in sbagl da la software.
Annunzia per plaschair la URL ad in [[Special:ListUsers/sysop|administratur]].',
'missingarticle-rev'  => '(number da la versiun: $1)',
'missingarticle-diff' => '(Differenza tranter versiuns: $1, $2)',
'internalerror'       => 'Errur intern',
'badtitle'            => "Il num da titel endatà n'è betg valaivel",
'badtitletext'        => 'Il titel da pagina era betg valaivels, vids u in titel inter-lingua u inter-wiki betg correct. 
El po cuntegnair in u plirs segns che na pon betg vegnir utilisads en titels.',
'perfcached'          => 'Las suandantas datas vegnan ord il cache ed èn eventualmain betg cumplettamain actualas:',
'viewsource'          => 'guardar fontaunas',
'viewsourcefor'       => 'per $1',
'viewsourcetext'      => 'Ti pos guardar e copiar il code-fundamental da questa pagina:',
'editinginterface'    => "'''Attenziun:''' Questa pagina cuntegn text che vegn duvra da software da mediawiki. Midadas influenzeschan directamain l'interface da l'utilisader. Sche ti vuls far translaziuns u correcturas: Studegia da far quai sin [http://translatewiki.net/wiki/Main_Page?setlang=rm translatewiki.net], per che las midadas pon vegnidas surprendidas da tut ils projects.",
'namespaceprotected'  => "Ti n'has betg la lubientscha da modifitgar paginas dal tip da pagina '''$1'''.",

# Login and logout pages
'logouttitle'                => "Log-out d'utilisaders",
'logouttext'                 => "'''Sortì cun success.'''

Ti pos cuntinuar cun utilisar {{SITENAME}} anonimamain, u che ti pos [[Special:UserLogin|t'annunziar]] sco medem u in'auter utilisader. Resguarda che entginas paginas pon anc vesair or tuttina sco sche ti eras annunzià enfin che ti has stizzà il cache da tes browser.",
'welcomecreation'            => '==Bainvegni, $1! ==

Tes conto è vegni creà. 
Betg emblida da midar tias [[Special:Preferences|preferenzas da {{SITENAME}}]].',
'loginpagetitle'             => 'Log-in per utilisaders',
'yourname'                   => "Num d'utilisader",
'yourpassword'               => 'pled-clav',
'yourpasswordagain'          => 'repeter pled-clav',
'remembermypassword'         => 'Memorisar il pled-clav',
'yourdomainname'             => 'Vossa domain',
'login'                      => "T'annunziar",
'nav-login-createaccount'    => "T'annunziar / registrar",
'loginprompt'                => "Ti stos avair '''activà ils cookies''' per pudair t'annunziar tar {{SITENAME}}.",
'userlogin'                  => "T'annunziar / registrar",
'logout'                     => 'Sortir',
'userlogout'                 => 'Sortir',
'notloggedin'                => "Betg s'annunzià",
'nologin'                    => "Anc nagin conto? '''$1'''.",
'nologinlink'                => "Crear in conto d'utilisader",
'createaccount'              => "Crear in conto d'utilisader",
'gotaccount'                 => "Gia in conto d'utilisader? '''$1'''.",
'gotaccountlink'             => "T'annunziar",
'createaccountmail'          => 'per email',
'badretype'                  => 'Ils dus pleds-clav na corrispundan betg.',
'userexists'                 => "Quest num d'utilisader vegn gia duvrà. Tscherna per plaschair in'auter.",
'youremail'                  => 'Email *',
'username'                   => "Num d'utilisader:",
'uid'                        => 'ID dal utilisader:',
'prefs-memberingroups'       => 'Commember {{PLURAL:$1|da la gruppa|da las gruppas}}:',
'yourrealname'               => 'Voss num endretg (nagin duair):',
'yourlanguage'               => 'linguatg:',
'yourvariant'                => 'varianta',
'yournick'                   => 'Signatura:',
'yourgender'                 => 'Schlattaina',
'gender-unknown'             => 'Betg inditgà',
'gender-male'                => 'Masculin',
'gender-female'              => 'Feminin',
'email'                      => 'E-mail',
'prefs-help-realname'        => "Opziun: Qua pos inditgar in surnum che vegn inditga empè da tes num d'utilisader en tias suttascripziuns cun '''<nowiki>--~~~~</nowiki>'''.",
'loginerror'                 => "Sbagl cun t'annunziar",
'prefs-help-email'           => "L'adressa d'email è opziunala, pussibilitescha dentant da trametter in nov pled-clav en cass da sperdita. Plinavant pussibilitescha ella ad auters utilisaders da contactar tai per email senza che ti stos publitgar tia identitad.",
'prefs-help-email-required'  => "Inditgar in'adressa d'email è obligatoric.",
'nocookieslogin'             => "{{SITENAME}} utilisescha cookies per ch'utilisaders pon s'annunziar. 
Ti has deactivà tes cookies.
Activescha per plaschair ils cookis en tes navigatur ed emprova danovamain.",
'loginsuccesstitle'          => "T'annunzià cun success",
'loginsuccess'               => "'''Ti es t'annunzia tar {{SITENAME}} sco \"\$1\".'''",
'nosuchuser'                 => 'I exista nagin utilisader cun il num "$1". 
Fa stim dad utilisar correctamain maiusclas e minusclas. 
Curregia il num u [[Special:UserLogin/signup|creescha in nov conto]].',
'nosuchusershort'            => 'I dat nagin utilisader cun il num "<nowiki>$1</nowiki>".
Curregia ti\'endataziun.',
'nouserspecified'            => "Inditgescha per plaschair in num d'utilisader.",
'wrongpassword'              => "Quai n'era betg il pled-clav correct. Prova anc ina giada.",
'wrongpasswordempty'         => 'Ti as emblidà da scriver tes pled-clav. Prova anc ina giada.',
'passwordtooshort'           => "Tes pled-clav n'è betg valaivels u memia curts. 
El sto cuntegnair almain {{PLURAL:$1|in bustab|$1 bustabs}} e na po betg correspunder tes num d'utilisader.",
'mailmypassword'             => 'Trametter in nov pled-clav per email',
'passwordremindertitle'      => 'Nov pled-clav temporar per {{SITENAME}}',
'passwordremindertext'       => 'Insatgi (probablamain ti, cun l\'adressa d\'IP $1) ha dumandà in nov pled-clav per {{SITENAME}} ($4). Il pled-clav temporar "$3" per l\'utilisader "$2" è vegnì creà. Sche quai era tes intent, ti al dovras per t\'annunziar e tscherner lura in nov pled-clav. Quest pled-clav temporar vegn a scrudar en {{PLURAL:$5|in di|$5 dis}}.

Sch\'insatgi auter ha fatg questa dumonda, ni sch\'il pled-clav è vegnì endament e ti na vuls betg pli midar el, pos ti simplamain ignorar quest messadi e cuntinuar la lavur cun tes pled-clav vegl.',
'noemail'                    => 'L\'utilisader "$1" n\'ha inditgà nagina adressa d\'e-mail.',
'passwordsent'               => "In nov pled-clav è vegnì tramess a l'adressa d'e-mail ch'è registrada per l'utilisader \"\$1\".
T'annunzia per plaschair sche ti has retschavì el.",
'eauthentsent'               => "In e-mail da confermaziun è vegnì tramess a l'adressa d'e-mail numnada. 
Suonda las infurmaziuns en l'e-mail per confirmar ch'il conto d'utilisader è il tes.",
'mailerror'                  => "Errur cun trametter l'e-mail: $1",
'acct_creation_throttle_hit' => "Visitaders da questa wiki cun tia adressa d'IP han gia creà {{PLURAL:$1|1 conto|$1 contos}} l'ultim di. Quai è il maximum lubì en questa perioda. 
Perquai pon visitaders cun questa IP betg pli crear dapli contos per il mument.",
'emailauthenticated'         => "Tia adressa d'email è vegnida verifitgada ils $2 las $3.",
'emailnotauthenticated'      => "Vus n'avais betg anc <strong>confermà vossa adressa dad email</strong>.<br />
Perquei è anc nagin retschaiver e trametter dad emails per las suandantas funcziuns pussaivel.",
'emailconfirmlink'           => "Confirmar l'adressa dad email",
'accountcreated'             => "Creà il conto d'utilisader",
'accountcreatedtext'         => "Il conto d'utilisader per $1 è vegnì creà.",
'login-throttled'            => "Ti has empruvà memia savens da t'annunziar. 
Spetga per plaschair avant ch'empruvar anc ina giada.",
'loginlanguagelabel'         => 'Lingua: $1',

# Password reset dialog
'resetpass'               => 'Midar il pled-clav',
'resetpass_header'        => 'Midar il pled-clav dal conto',
'oldpassword'             => 'pled-clav vegl:',
'newpassword'             => 'pled-clav nov:',
'retypenew'               => 'repeter pled-clav nov:',
'resetpass-temp-password' => 'Pled-clav temporar:',

# Edit page toolbar
'bold_sample'     => 'Text grass',
'bold_tip'        => 'Text grass',
'italic_sample'   => 'Text cursiv',
'italic_tip'      => 'Text cursiv',
'link_sample'     => 'Titel da la colliaziun',
'link_tip'        => 'Colliaziun interna',
'extlink_sample'  => 'http://www.example.com link title',
'extlink_tip'     => 'Link extern (risguardar il prefix http:// )',
'headline_sample' => 'Titel',
'headline_tip'    => 'Titel da segund livel',
'math_sample'     => 'Scriva qua tia furmla',
'math_tip'        => 'Furmla matematica (LaTeX)',
'nowiki_sample'   => 'Scriva qua text che na duai betg vegnir formatà',
'nowiki_tip'      => 'Ignorar las formataziuns vichi',
'image_sample'    => 'Exempel.jpg',
'image_tip'       => 'Integrar ina datoteca',
'media_tip'       => 'Colliaziun ad ina datoteca',
'sig_tip'         => 'Tia suttascripziun cun data e temp',
'hr_tip'          => 'Lingia orizontala (betg utilisar savens!)',

# Edit pages
'summary'                          => 'Resumaziun:',
'subject'                          => 'Pertutga:',
'minoredit'                        => 'Midà be bagatellas',
'watchthis'                        => 'observar quest artitgel',
'savearticle'                      => "memorisar l'artitgel",
'preview'                          => 'prevista',
'showpreview'                      => 'mussar prevista',
'showlivepreview'                  => 'prevista directa',
'showdiff'                         => 'mussar midadas',
'anoneditwarning'                  => "Vus essas betg annunziads. Empè dal num d'utilisader vign l'adressa dad IP registrada en la historia da las versiuns.",
'missingcommenttext'               => 'Endatescha per plaschair ina resumaziun.',
'summary-preview'                  => 'prevista da la resumaziun:',
'blockedtitle'                     => 'Utilisader è bloccà',
'blockedtext'                      => "'''Tes num d'utilisader u tia adressa d'IP è vegnida bloccada.'''

''$1'' ha bloccà tai.
Il motiv inditgà è: ''$2''.

* Bloccà davent da: $8
* Bloccà enfin: $6
* Intended blockee: $7

Ti pos contactar $1 u in auter[[{{MediaWiki:Grouppage-sysop}}|administratur]] per discutar questa bloccada. 
Ti na pos betg utilisar la funcziun 'Trametter in email a quest utilisader' senza avair inditgà in'adressa valaivla en tias [[Special:Preferences|preferenzas]] e sche ti n'ès betg vegnì bloccà per utilisar la funcziun. 
Ti'adressa d'IP actuala è $3, ed la block ID è #$5.

Integrescha per plaschair tut las indicaziuns survart sche ti contacteschas insatgi.",
'blockednoreason'                  => 'inditgà nagina raschun',
'whitelistedittitle'               => "t'annunzia per editar",
'confirmedittitle'                 => 'Per editar è la confermaziun da la adressa dad email necessaria',
'confirmedittext'                  => 'Ti stos confermar tia adressa dad email avant che editar paginas. Inditgescha e conferma per plaschair tia adressa dad email en tias [[Special:Preferences|preferenzas]].',
'loginreqtitle'                    => 'Annunzia necessari',
'loginreqlink'                     => "t'annunziar",
'loginreqpagetext'                 => 'Ti stos $1 per vesair autras paginas.',
'accmailtitle'                     => 'Il pled-clav è vegnì tramess.',
'accmailtext'                      => "In pled-clav casual per l'utilisader [[User talk:$1|$1]] è vegnì tramess a $2.

Il pled-clav per quest nov conto po vegnir midà sin la pagina ''[[Special:ChangePassword|midar pled-clav]]'' suenter che ti t'es annunzià.",
'newarticle'                       => '(Nov)',
'newarticletext'                   => "Ti has cliccà in link ad ina pagina che exista anc betg. Per crear ina pagina, entschaiva a tippar en la stgaffa sutvart (guarda [[{{MediaWiki:Helppage}}|la pagina d'agid]] per t'infurmar).",
'anontalkpagetext'                 => "----''Quai è la pagina da discussiun per in utilisader anomim che n'ha anc betg creà in conto d'utilisader u che n'al utilisescha betg. 
Perquai avain nus d'utilisar l'adressa d'IP per l'identifitgar. 
Ina tala adressa d'IP po vegnir utilisada da differents utilisaders. 
Sche ti es in utilisaders anonim e pensas che commentaris che na pertutgan betg tai vegnan adressads a tai, lura [[Special:UserLogin/signup|creescha in conto]] u [[Special:UserLogin|s'annunzia]] per evitar en futur che ti vegns sbaglià cun auters utilisaders.''",
'noarticletext'                    => 'Quest artitgel cuntegna actualmain nagin text. 
Ti pos [[Special:Search/{{PAGENAME}}|tschertgar il term]] sin in\'autra pagina, 
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tschertgar en ils logs],
u [{{fullurl:{{FULLPAGENAME}}|action=edit}} crear questa pagina]</span>.',
'userpage-userdoesnotexist'        => 'Il conto d\'utilisader "$1" n\'èxista betg. 
Controllescha sch ti vuls propi crear/modiftgar questa pagina.',
'clearyourcache'                   => "'''Remartga''' Svida il chache da tes browser suenter avair memorisà, per vesair las midadas.
'''Mozilla / Firefox / Safari:''' tegnair ''Shift'' durant cliccar ''chargiar danovamain'', u smatgar ''Ctrl-F5'' u ''Ctrl-R'' (''Command-R'' sin in Macintosh);
'''Konqueror: '''clicca ''Reload'' u smatga ''F5'';
'''Opera:''' stizzar il cache sut ''Tools → Preferences'';
'''Internet Explorer:''' tegna ''Ctrl'' durant cliccar ''Refresh,'' u smatga ''Ctrl-F5''.",
'note'                             => "'''Remartga:'''",
'previewnote'                      => "'''Quai è be ina prevista; midadas n'èn anc betg vegnidas memorisadas!'''",
'editing'                          => 'Editar $1',
'editingsection'                   => 'Editar $1 (secziun)',
'yourtext'                         => 'Voss text',
'yourdiff'                         => 'Differenzas',
'copyrightwarning'                 => "Las contribuziuns a {{SITENAME}} vegnan publitgadas confurm a la $2 (contempla $1 per ulteriurs detagls). 
Sche ti na vuls betg che tias contribuziuns vegnan modifitgadas e redistribuidas, lura na las trametta betg qua.<br />
Ti garanteschas che ti has scrit tez quai u copià dad ina funtauna ch'è 'public domain' u dad in'autra funtauna libra.
'''Na trametta naginas ovras ch'èn protegidas da dretgs d'autur senza lubientscha explicita!'''",
'longpagewarning'                  => "'''ADATG: Questa artitgel è $1 kilobytes gronda. Insaquants browsers 
han forsa problems cun editar artitgels da la grondezza 32 kb u pli grond. 
Ponderai per plaschair da divider quest artitgel en pli pitschnas parts. '''",
'longpageerror'                    => "'''SBAGL: Il text che ti has tramess è $1 kilobytes gronds. Quei ei pli grond ch'il maximum da $2 kilobytes. Il text na sa betg vegnir memorisà. '''",
'protectedpagewarning'             => "'''ATTENZIUN: Questa pagina è vegnida bloccada, uschè che be utilisaders cun dretgs dad administraturs pon editar ella. '''",
'templatesused'                    => 'Templates utilisads sin questa pagina:',
'templatesusedpreview'             => 'Templates utilisads en questa prevista:',
'template-protected'               => '(bloccà)',
'template-semiprotected'           => '(mez protegidas)',
'hiddencategories'                 => 'Quest artitgel è commember da {{PLURAL:$1|1 categoria zuppentada|$1 categorias zuppentadas}}:',
'edittools'                        => '<!-- Text here will be shown below edit and upload forms. -->',
'nocreatetext'                     => "{{SITENAME}} ha restrinschì las pussaivladas da crear novas paginas. 
Ti pos ir anavos ed editar ina pagina existenta, u [[Special:UserLogin|t'annunziar u registrar]].",
'permissionserrorstext-withaction' => 'Ti na dastgas betg $2. Quai ord {{PLURAL:$1|il suandant motiv|ils suandants motivs}}:',
'recreate-deleted-warn'            => "'''Attenziun: Ti recreeschas in artitgel ch'è vegni stidà pli baud.'''

Esi propi adattà da puspè crear questa pagina? 
En il ''log da stidar'' che suonda pos ti guardar daco che la pagina è vegnida stidada.",

# Parser/template warnings
'post-expand-template-inclusion-warning'  => "'''Attenziun:''' La grondezza dals models integrads è memia gronda. 
Insaquants models vegnan betg integrads.",
'post-expand-template-inclusion-category' => 'Paginas, en las qualas la grondezza maximala da models è surpassada',
'post-expand-template-argument-warning'   => "'''Attenziun:''' Questa pagina cuntegna almain in argument d'in model che ha ina memia gronda grondezza d'expansiun. 
Quests arguments vegnan ignorads.",
'post-expand-template-argument-category'  => 'Paginas che cuntegnan arguments ignorads per models',
'parser-template-loop-warning'            => 'Chattà cirquit da models: [[$1]]',
'parser-template-recursion-depth-warning' => 'Surpassa la limita da recursiun da models ($1)',

# Account creation failure
'cantcreateaccounttitle' => 'Betg pussaivel da crear il conto',

# History pages
'viewpagelogs'           => 'Guardar ils logs da questa pagina',
'currentrev'             => 'Versiun actuala',
'revisionasof'           => 'Versiun dals $1',
'revision-info'          => "Quai è ina versiun veglia. Temp da la midada ''$1'' da ''$2''", # Additionally available: $3: revision id
'previousrevision'       => '← versiun pli veglia',
'nextrevision'           => 'versiun pli nova →',
'currentrevisionlink'    => 'Guardar la versiun actuala',
'cur'                    => 'act',
'next'                   => 'proxim',
'last'                   => 'davosa',
'page_first'             => 'entschatta',
'page_last'              => 'fin',
'histlegend'             => 'Per vesair las differenzas tranter duas versiuns, marca ils quaderins da la versiuns che ti vul cumparegliar e clicca sin "cumparegliar las versiuns selecziunadas".
* (act) = differenzas cun la versiun actuala
* (davosa) = differenza cun la versiun precedenta
* M = Midà be bagatellas',
'history-fieldset-title' => 'tschertgar en la cronica',
'deletedrev'             => '[stidà]',
'histfirst'              => 'pli veglia',
'histlast'               => 'pli nova',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(vid)',

# Revision feed
'history-feed-title'          => 'Cronologia da las versiuns',
'history-feed-description'    => 'Cronologia da versiuns per questa pagina sin questa vichi',
'history-feed-item-nocomment' => '$1 las $2', # user at time

# Revision deletion
'rev-deleted-comment' => '(eliminà commentari)',
'rev-deleted-user'    => "(stidà num d'utilisader)",
'rev-deleted-event'   => '(stidà acziun dal log)',
'rev-delundel'        => 'mussar/zuppar',
'revdel-restore'      => 'midar la visibilitad',
'revdelete-content'   => 'Cuntegn',
'revdelete-summary'   => 'resumaziun da la midada',
'revdelete-uname'     => "num d'utilisader",

# Merge log
'revertmerge' => 'Revocar la fusiun',

# Diffs
'history-title'           => 'Cronica da versiuns da "$1"',
'difference'              => '(differenza tranter versiuns)',
'lineno'                  => 'Lingia $1:',
'compareselectedversions' => 'cumparegliar las versiuns selecziunadas',
'editundo'                => 'revocar',
'diff-multi'              => '({{PLURAL:$1|Ina versiun|$1 versiuns}} tranter en na vegnan betg mussadas.)',

# Search results
'searchresults'                    => 'Resultats da tschertga',
'searchresults-title'              => 'Resultats da tschertga per "$1"',
'searchresulttext'                 => "Per dapli infurmaziuns davart il tschertgar sin {{SITENAME}}, guarda l'[[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchsubtitle'                   => 'Ti has tschertgà \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tut las paginas che entschevan cun "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|colliaziuns a "$1"]])',
'searchsubtitleinvalid'            => "Ti has tschertgà '''$1'''",
'noexactmatch-nocreate'            => "'''I n'exista nagina pagina cun il titel \"\$1\".'''",
'notitlematches'                   => 'Nagin titel correspunda',
'notextmatches'                    => 'Nagin text correspunda',
'prevn'                            => 'davos $1',
'nextn'                            => 'proxims $1',
'prevn-title'                      => '{{PLURAL:$1|Ultim resultat|Ultims resultats}}',
'nextn-title'                      => '{{PLURAL:$1|Proxim resultat|Proxims resultats}}',
'shown-title'                      => 'Mussar $1 {{PLURAL:$1|resultat|resultats}} per pagina',
'viewprevnext'                     => 'Mussar ($1) ($2) ($3).',
'searchmenu-legend'                => 'Opziuns da tschertgar',
'searchmenu-exists'                => "'''Igl exista ina pagina cun il num \"[[:\$1]] sin questa vichi\"'''",
'searchmenu-new'                   => "'''Crear la pagina \"[[:\$1]]\" sin questa vichi!'''",
'searchhelp-url'                   => 'Help:Cuntegn',
'searchprofile-everything'         => 'Tut',
'searchprofile-advanced'           => 'Avanzà',
'searchprofile-articles-tooltip'   => 'Tschertgar en $1',
'searchprofile-project-tooltip'    => 'Tschertgar en $1',
'searchprofile-images-tooltip'     => 'Tschertgar datotecas',
'searchprofile-everything-tooltip' => 'Tschertgar en tut il cuntegn (inclusivamain paginas da discussiun)',
'searchprofile-advanced-tooltip'   => 'Tschertgar en ulteriurs tips da pagina',
'search-result-size'               => '$1 ({{PLURAL:$2|in pled|$2 pleds}})',
'search-result-score'              => 'Relevanza: $1 %',
'search-redirect'                  => '(renvià da $1)',
'search-section'                   => '(chapitel $1)',
'search-suggest'                   => 'Has ti manegià: $1',
'search-interwiki-caption'         => 'Projects sumegliants',
'search-interwiki-default'         => '$1 resultats:',
'search-interwiki-more'            => '(dapli)',
'search-mwsuggest-enabled'         => 'cun propostas',
'search-mwsuggest-disabled'        => 'naginas propostas',
'searchall'                        => 'tuts',
'showingresults'                   => "Sutvart èn enfin {{PLURAL:$1|'''in''' resultat|'''$1''' resultats}} cumenzond cun il numer '''$2'''.",
'showingresultsnum'                => "Qua èn {{PLURAL:$3|'''1''' resultat|'''$3''' resultats}}, cumenzond cun il number '''$2'''.",
'nonefound'                        => "'''Remartga''': Sco standard vegn be tschertga en tscherts tips da pagina. 
Scriva il prefix ''all:'' avant il term che ti vuls tschertgar, per tschertgar en tut las paginas (incl. discussiuns, models etc.) u scriva directamain il prefix dal spazi da num en il qual ti vuls tschertgar.",
'search-nonefound'                 => 'Per il term tschertga èn nagins resultats vegnids chattads.',
'powersearch'                      => 'retschertgar',
'powersearch-legend'               => 'Tschertga extendida',
'powersearch-ns'                   => 'Tschertgar en tips da pagina:',
'powersearch-redir'                => 'Mussar sviaments',
'powersearch-field'                => 'Tschertgar',

# Preferences page
'preferences'               => 'Preferenzas',
'mypreferences'             => 'Mias preferenzas',
'prefs-edits'               => 'Dumber da las modificaziuns:',
'prefsnologin'              => "Betg t'annunzià",
'prefsnologintext'          => 'Ti stos esser <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} annunzià]</span> per midar tias preferenzas.',
'prefsreset'                => 'Preferenzas da standard ein vegnì reconstruidas.',
'qbsettings'                => 'Glista laterala',
'qbsettings-none'           => 'Nagins',
'qbsettings-fixedleft'      => 'Sanester, fixà',
'qbsettings-fixedright'     => 'Dretg, fixà',
'qbsettings-floatingleft'   => 'Sanester, flottand',
'qbsettings-floatingright'  => 'Dretg, flottand',
'changepassword'            => 'Midar pled-clav',
'skin-preview'              => 'Prevista',
'dateformat'                => 'format da las datas',
'datedefault'               => 'Nagina preferenza',
'datetime'                  => 'Data e temp',
'prefs-personal'            => 'datas dal utilisader',
'prefs-rc'                  => 'Mussar ultimas midadas',
'prefs-watchlist'           => "glista d'observaziun",
'prefs-watchlist-days'      => "Dumber dals dis che vegnan inditgads sin la glista d'observaziun:",
'prefs-watchlist-edits'     => 'Dumber da las midadas mussadas en la glista dad observaziun extendida:',
'prefs-misc'                => 'Different',
'prefs-resetpass'           => 'Midar il pled clav',
'saveprefs'                 => 'memorisar',
'resetprefs'                => 'remetter las preferenzas (reset)',
'restoreprefs'              => 'Restituir tut las preferenzas da standard',
'prefs-edit-boxsize'        => 'Grondezza da la fanestra da modifitgar',
'rows'                      => 'Lingias:',
'columns'                   => 'Colonnas:',
'searchresultshead'         => 'Tschertga',
'resultsperpage'            => 'resultats per pagina:',
'contextlines'              => 'Lingia per resultat:',
'contextchars'              => 'Segns per lingia:',
'savedprefs'                => 'Tias preferenzas èn vegnidas memorisadas.',
'timezonelegend'            => "Zona d'urari:",
'timezonetext'              => 'Inditgescha la differenza tranter voss temp local e quel dal server (UTC).',
'localtime'                 => 'Temp local:',
'timezoneoffset'            => 'Differenza¹:',
'servertime'                => 'Temp dal server:',
'guesstimezone'             => 'Emplenescha dal browser',
'timezoneregion-africa'     => 'Africa',
'timezoneregion-america'    => 'America',
'timezoneregion-antarctica' => 'Antarctica',
'timezoneregion-arctic'     => 'Arctica',
'timezoneregion-asia'       => 'Asia',
'timezoneregion-atlantic'   => 'Ocean atlantic',
'timezoneregion-australia'  => 'Australia',
'timezoneregion-europe'     => 'Europa',
'timezoneregion-indian'     => 'Ocean Indic',
'timezoneregion-pacific'    => 'Ocean pacific',
'allowemail'                => 'retschaiver emails dad auters utilisaders',
'prefs-searchoptions'       => 'Opziuns da tschertgar',
'prefs-namespaces'          => 'Tips da pagina',
'defaultns'                 => 'En quests tips da pagina duai vegnir tschertga sco standard:',
'default'                   => 'Standard',

# User rights
'userrights'               => "Administraziun da dretgs d'utilisaders", # Not used as normal message but as header for the special page itself
'userrights-lookup-user'   => "Administrar gruppas d'utilisaders",
'userrights-user-editname' => "Inditgescha in num d'utilisader:",
'userrights-groupsmember'  => 'Commember da:',
'userrights-nologin'       => "Ti stos [[Special:UserLogin|t'annunziar]] cun in conto d'aministratur per modifitgar ils dretgs d'utilisader.",

# Groups
'group'               => 'Gruppa:',
'group-user'          => 'Utilisaders',
'group-autoconfirmed' => 'Utilisaders confermads automaticamain',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administraturs',
'group-bureaucrat'    => 'Birocrat',
'group-suppress'      => 'Oversights',
'group-all'           => '(tuts)',

'group-user-member'          => 'Utilisader',
'group-autoconfirmed-member' => 'Utilisader confermà automaticamain',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administratur',
'group-bureaucrat-member'    => 'Birocrat',
'group-suppress-member'      => 'Oversight',

'grouppage-sysop' => '{{ns:project}}:Administraturs',

# Rights
'right-read'             => 'Leger paginas',
'right-edit'             => 'Modifitgar paginas',
'right-createpage'       => 'Crear paginas (danor paginas da discussiun)',
'right-createtalk'       => 'Crear paginas da discussiun',
'right-createaccount'    => "Crear novs contos d'utilisader",
'right-minoredit'        => 'Marcar modificaziuns sco pitschnas',
'right-move'             => 'Spustar paginas',
'right-move-subpages'    => 'Spustar paginas cun las subpaginas',
'right-movefile'         => 'Spustar datotecas',
'right-suppressredirect' => 'Impedir da crear renviaments cun spustar paginas',
'right-upload'           => 'Chargiar si datotecas',
'right-delete'           => 'Stizzar paginas',

# User rights log
'rightslog'  => "Log dals dretgs d'utilisader",
'rightsnone' => '(nagins)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'               => 'editar questa pagina',
'action-createpage'         => 'crear ina pagina',
'action-createtalk'         => 'crear ina pagina da discussiun',
'action-createaccount'      => "Crear quest conto d'utilisader",
'action-minoredit'          => 'marcar sco pitschna midada',
'action-move'               => 'spustar questa pagina',
'action-move-subpages'      => 'spustar questa pagina e sias sutpaginas',
'action-move-rootuserpages' => "spustar la pagina principala d'utilisaders",
'action-movefile'           => 'spustar questa datoteca',
'action-upload'             => 'chargiar si questa datoteca',
'action-reupload'           => 'surscriver questa datoteca existenta',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|midada|midadas}}',
'recentchanges'                     => 'Ultimas midadas',
'recentchanges-legend'              => 'Opziuns per las ultimas midadas',
'recentchangestext'                 => "Sin questa pagina pos ti suandar las ultimas midadas sin '''{{SITENAME}}'''.",
'recentchanges-feed-description'    => 'Suonda las ultimas midadas en la wiki cun quet feed.',
'rcnote'                            => "Sutvart {{PLURAL:$1|è '''ina''' midada|èn las ultimas '''$1''' midadass}} {{PLURAL:$2|da l'ultim di|dals ultims '''$2''' dis}}, versiun dals  $4 $5.",
'rcnotefrom'                        => "Midadas dapi '''$2''' (maximalmain '''$1''' vegnan mussads).",
'rclistfrom'                        => 'Mussar las novas midadas entschavend cun $1',
'rcshowhideminor'                   => '$1 midadas pitschnas',
'rcshowhidebots'                    => '$1 bots',
'rcshowhideliu'                     => '$1 utilisaders annunziads',
'rcshowhideanons'                   => '$1 utilisaders anonims',
'rcshowhidepatr'                    => '$1 midadas controlladas',
'rcshowhidemine'                    => '$1 mias midadas',
'rclinks'                           => 'Mussar las davosas $1 midadas dals ultims $2 dis<br />$3',
'diff'                              => 'diff',
'hist'                              => 'ist',
'hide'                              => 'zuppar',
'show'                              => 'mussar',
'minoreditletter'                   => 'P',
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|utilisader|utilisaders}} observeschan quest artitgel]',
'rc_categories'                     => 'Be paginas ord las categorias (seperar cun "|")',
'rc_categories_any'                 => 'Tuts',
'newsectionsummary'                 => 'Nov chapitel /* $1 */',
'rc-enhanced-expand'                => 'Mussar detagls (JavaScript è necessari)',
'rc-enhanced-hide'                  => 'Zuppentar detagls',

# Recent changes linked
'recentchangeslinked'          => 'midadas sin paginas cun links',
'recentchangeslinked-title'    => 'Midadas en artitgels ch\'èn colliads cun "$1"',
'recentchangeslinked-noresult' => 'Naginas midadas sin artitgels collads durant la perioda endatada.',
'recentchangeslinked-summary'  => "Quest è ina glista da las midadas ch'èn vegnidas fatgas da curt en artitgels ch'èn colliads cun ina pagina specifica (ni en commembers d'ina categoria specifica). 
Paginas sin [[Special:Watchlist|tia glista d'observaziun]] èn '''grassas'''.",
'recentchangeslinked-page'     => 'Num da la pagina:',
'recentchangeslinked-to'       => 'Mussar midadas da paginas che han ina colliaziun a questa pagina',

# Upload
'upload'                   => 'Chargiar si in file',
'uploadbtn'                => 'Chargiar si il file',
'reupload'                 => 'chargiar si danovamain',
'reuploaddesc'             => 'Anavos tar la pagina da chargiar si.',
'uploadnologin'            => "Betg t'annunzià",
'uploadnologintext'        => "Ti stos [[Special:UserLogin|t'annunziar]] per chargiar si files.",
'uploadtext'               => "Utilisescha quest formular per chargiar si datotecas. 
Per contemplar u tschertgar datotecas gia chargiada si, visita la pagina [[Special:FileList|list of uploaded files]]. Tut las datotecas che vegnan chargiadas si èn era notads en il [[Special:Log/upload|log da chargiar si]], quellas ch'èn vegnidas stizzadas en il [[Special:Log/delete|log dal stizzar]].

Per integrar ina datoteca en in artitgel pos ti per exempel duvrar in dals suandants cumonds: 
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' per utilisar la versiun cumplaina da la datoteca
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' per utilisar in maletg da la ladezza da 200 pixels en in champ da la vart sanestra cun la descripziun 'alt text'
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' per directamain far ina colliaziun a la datoteca senza integrar la datoteca",
'upload-permitted'         => 'Tips da datotecas lubids: $1.',
'upload-preferred'         => 'Tips da datotecas preferids: $1.',
'upload-prohibited'        => 'Tips da datotecas betg lubids: $1.',
'uploadlog'                => 'Log dal chargiar si',
'uploadlogpage'            => 'Log da chargiar si',
'uploadlogpagetext'        => "Quai è ina glista da las datotecas ch'èn vegnidas chargiadas si sco ultimas. 
Guarda era la [[Special:NewFiles|galleria da novas datotecas]] per ina survista pli visuala.",
'filename'                 => 'Num da datoteca',
'filedesc'                 => 'Resumaziun',
'fileuploadsummary'        => 'Resumaziun:',
'filereuploadsummary'      => 'Midadas da la datoteca:',
'filestatus'               => "Status dals dretgs d'autur:",
'filesource'               => 'Funtauna:',
'uploadedfiles'            => 'Datotecas chargiadas si',
'ignorewarning'            => "Ignorar l'avertiment e memorisar la datoteca",
'ignorewarnings'           => 'Ignorar tut ils avertiments (Warnung)',
'minlength1'               => 'Nums da datotecas ston esser almain in bustab lung.',
'illegalfilename'          => 'Il num da datoteca "$1" cuntegna almain in segn betg lubì. Endatescha in\'auter num ed emprova danovamain da chargiar si la datoteca.',
'badfilename'              => 'Midà num dal file sin "$1".',
'filetype-badmime'         => 'Datotecas dal tip da MIME "$1" na dastgan betg vegnir chargiads si.',
'largefileserver'          => "Quest file è memia gronds. Il server è configurà uschè ch'el accepta be files enfin ina tscherta grondezza.",
'fileexists-thumbnail-yes' => "Quest maletg para dad esser in maletg da grondezza reducida ''(Maletg da prevista)''.
[[$1|thumb]]
Controllescha per plaschair la datoteca ''<tt>[[:$1]]</tt>'''. 
Sche la datoteca menziunada survart è il medem maletg en grondezza originala n'èsi betg necessari da chargiar si in maletg da pervista.",
'file-thumbnail-no'        => "Il num da la datoteca cumenza cun '''<tt>$1</tt>''', perquai para quai dad esser in maletg da grondezza reducida ''(Maletg da prevista)''.
Controllescha sche ti has era il maletg en grondezza originala e chargia si quel sut il num original.",
'uploadedimage'            => '"[[$1]]" è vengì chargià si',
'uploadvirus'              => 'La datoteca cuntegna in virus! Detagls: $1',
'sourcefilename'           => 'file sin tes computer:',
'destfilename'             => 'num dal file sin il server:',
'upload-maxfilesize'       => 'Grondezza da datoteca maximala: $1',

# Special:ListFiles
'imgfile'               => 'datoteca',
'listfiles'             => 'Glista dals maletgs',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Num',
'listfiles_user'        => 'Utilisader',
'listfiles_size'        => 'Grondezza',
'listfiles_description' => 'Descripziun',
'listfiles_count'       => 'Versiuns',

# File description page
'filehist'                  => 'Istorgia da las versiuns',
'filehist-help'             => 'Clicca sin ina data/temps per vesair la versiun da lura.',
'filehist-deleteall'        => 'Stidar tut las versiuns',
'filehist-deleteone'        => 'Stidar questa versiun',
'filehist-revert'           => 'reinizialisar',
'filehist-current'          => 'actual',
'filehist-datetime'         => 'data/temp',
'filehist-thumb'            => 'Maletg da prevista',
'filehist-nothumb'          => 'Nagin maletg da prevista',
'filehist-user'             => 'Utilisader',
'filehist-dimensions'       => 'dimensiuns',
'filehist-filesize'         => 'grondezza da datoteca',
'filehist-comment'          => 'commentari',
'imagelinks'                => 'Paginas che cuntegnan la datoteca',
'linkstoimage'              => '{{PLURAL:$1|La suandanta pagina è colliada|Las suandantas $1 paginas èn colliadas}} cun questa datoteca:',
'nolinkstoimage'            => 'Naginas paginas mussan sin questa datoteca.',
'redirectstofile'           => '{{PLURAL:$1|Suandanta datoteca renviescha|Suandantas $1 datotecas renvieschan}} a questa datoteca:',
'sharedupload'              => 'Quai è ina datoteca da $1 e vegn eventualmain utilisada dad auters projects.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'noimage'                   => "Ina datoteca cun quest num n'exista betg, ti pos dentant $1.",
'noimage-linktext'          => 'chargiar sin in',
'uploadnewversion-linktext' => 'Chargiar si ina nova versiun da questa datoteca',

# MIME search
'mimesearch' => 'tschertgar tenor tip da MIME',
'download'   => 'telechargiar',

# List redirects
'listredirects' => 'Glista cun tut ils renviaments',

# Unused templates
'unusedtemplates'     => 'Models betg utilisads',
'unusedtemplatestext' => "Questa pagina mussa tut las paginas en il tip da pagina {{ns:template}} ch'èn betg integrads en in'autra pagina. 
Betg emblida da controllar sche autras colliaziuns mainan als models avant ch'als stizzar.",
'unusedtemplateswlh'  => 'Autras colliaziuns',

# Random page
'randompage' => 'Artitgel casual',

# Random redirect
'randomredirect'         => 'Renviament casual',
'randomredirect-nopages' => 'I na vegn betg renvià al tip da pagina "$1".',

# Statistics
'statistics'              => 'Statisticas',
'statistics-header-pages' => 'Statistica da paginas',
'statistics-header-edits' => 'Statistica da modificaziuns',
'statistics-header-users' => 'Statisticas davart ils utilisaders',
'statistics-pages-desc'   => 'Tut las paginas en la vichi, inclusivamain paginas da discussiun, renviaments, etc.',

'disambiguations'     => 'pagina per la decleraziun da noziuns',
'disambiguationspage' => 'Template:disambiguiziun',

'doubleredirects'            => 'Renviaments dubels',
'doubleredirectstext'        => "Questa glista mussa renviaments che mainan puspè a renviaments. 
Mintga colonna cuntegna colliaziuns a l'emprim ed al segund renviaments, sco era la pagina finala dal segund renviament che è probablamain la pagina a la quala duess vegnir renvià. 
Elements <s>stritgads</s> èn gia eliminads.",
'double-redirect-fixed-move' => '[[$1]] è vegnì spustà. 
I renviescha uss a [[$2]].',
'double-redirect-fixer'      => 'Bot da renviaments',

'brokenredirects'     => 'Renviaments defects',
'brokenredirectstext' => 'Ils suandants renviaments mainan a paginas betg existentas:',

'withoutinterwiki' => 'Artitgels senza colliaziuns ad autras linguas',

'fewestrevisions' => 'Artitgels cun las pli biaras actualisaziuns',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'                  => '$1 {{PLURAL:$1|colliaziun|colliaziuns}}',
'nmembers'                => '$1 {{PLURAL:$1|commember|commembers}}',
'nrevisions'              => '{{PLURAL:$1|Ina versiun|$1 versiuns}}',
'nviews'                  => 'Contemplà $1 {{PLURAL:$1|giada|giadas}}',
'specialpage-empty'       => 'Questa pagina cuntegna actualmain naginas endataziuns.',
'lonelypages'             => 'Paginas bandunadas',
'lonelypagestext'         => "Las suandantas paginas n'èn betg integradas u n'èn betg colliadas cun autras paginas sin {{SITENAME}}.",
'uncategorizedpages'      => 'Artitgels betg categorisads',
'uncategorizedcategories' => 'Categorias betg categorisadas',
'uncategorizedimages'     => 'Datotecas betg categorisadas',
'uncategorizedtemplates'  => 'Models betg categorisads',
'unusedcategories'        => 'Categorias betg utilisadas',
'unusedimages'            => 'Maletgs betg utilisads',
'popularpages'            => 'Paginas popularas',
'wantedcategories'        => 'Categorias giavischadas',
'wantedpages'             => 'Artitgels giavischads',
'wantedpages-badtitle'    => 'Titel nunvalid en il resultat: $1',
'wantedfiles'             => 'Datotecas giavischadas',
'wantedtemplates'         => 'Models giavischads',
'mostlinked'              => 'Artitgels sin ils quals las pli biaras colliaziuns mussan',
'mostlinkedcategories'    => 'Categorias utilisadas il pli savens',
'mostlinkedtemplates'     => 'Models integrads il pli savens',
'mostcategories'          => "Artitgels ch'èn en las pli biaras chategorias",
'mostimages'              => 'Datotecas utilisadas il pli savens',
'mostrevisions'           => 'Artitgels cun las pli biaras revisiuns',
'prefixindex'             => 'Tut las paginas cun prefix',
'shortpages'              => 'Paginas curtas',
'longpages'               => 'Artitgels lungs',
'deadendpages'            => 'artitgels senza links interns che mainan anavant',
'protectedpages'          => 'Paginas protegidas',
'protectedtitles'         => 'Titels bloccads',
'protectedtitlestext'     => 'Suandants titels èn bloccads per vegnir creads.',
'protectedtitlesempty'    => 'Cun ils parameters inditgads èn naginas titels actualmain bloccads per vegnir creads.',
'listusers'               => 'Glista dals utilisaders',
'usercreated'             => 'Creà ils $1 las $2 uras',
'newpages'                => 'Artitgels novs',
'newpages-username'       => "Num d'utilisader:",
'ancientpages'            => 'Artitgels il pli ditg betg modifitgads',
'move'                    => 'spustar',
'movethispage'            => 'Spustar quest artitgel',
'pager-newer-n'           => '{{PLURAL:$1|pli nov|ils $1 pli novs}}',
'pager-older-n'           => '{{PLURAL:$1|in pli vegl|$1 pli vegls}}',

# Book sources
'booksources'               => 'Tschertga da ISBN',
'booksources-search-legend' => 'Tschertgar pussaivladad da cumpra per cudeschs',
'booksources-go'            => 'Leger',

# Special:Log
'specialloguserlabel'  => 'Utilisader:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'logs / cudesch da navigaziun',
'all-logs-page'        => 'Tut ils logs publics',

# Special:AllPages
'allpages'          => 'tut ils *** artitgels',
'alphaindexline'    => '$1 enfin $2',
'nextpage'          => 'proxima pagina ($1)',
'prevpage'          => 'ultima pagina ($1)',
'allpagesfrom'      => 'Mussar paginas naven da:',
'allpagesto'        => 'Mussar paginas enfin:',
'allarticles'       => 'Tut ils artitgels',
'allinnamespace'    => 'tut las paginas (tip da pagina $1)',
'allnotinnamespace' => 'Tut ils artitgels (betg dal tip da pagina $1)',
'allpagesprev'      => 'enavos',
'allpagesnext'      => 'enavant',
'allpagessubmit'    => 'Mussar',
'allpagesprefix'    => 'mussar paginas cun il prefix:',
'allpages-bad-ns'   => 'Il tip da pagina "$1" n\'existà betg sin {{SITENAME}}.',

# Special:Categories
'categories' => 'Categorias',

# Special:LinkSearch
'linksearch'    => 'Links externs',
'linksearch-ns' => 'Tip da pagina:',
'linksearch-ok' => 'Tschertgar',

# Special:Log/newusers
'newuserlogpage'           => "Log d'utilisaders creads",
'newuserlog-create-entry'  => "Nov conto d'utilisader",
'newuserlog-create2-entry' => 'Creà in nov conto "$1"',

# Special:ListGroupRights
'listgrouprights-members' => '(glista dals commembers)',

# E-mail user
'mailnologintext' => "Ti stos [[Special:UserLogin|t'annunziar]] ed avair ina adressa d'email valaivla en tias [[Special:Preferences|preferenzas]] per trametter emails ad auters utilisaders.",
'emailuser'       => 'Trametter in email a quest utilisader',
'emailpage'       => 'Utilisader dad email',
'emailpagetext'   => "Ti pos utilisar il formular sutvart per trametter in'e-mail a quest utilisader. 
L'adressa d'e-mail che ti has endatà en [[Special:Preferences|tias preferenzas]] vegn inditgada sco speditur da l'e-mail, uschia ch'il retschavider po rispunder directamain a tai.",
'defemailsubject' => '{{SITENAME}} email',
'emailfrom'       => 'Da:',
'emailto'         => 'A:',
'emailsubject'    => 'Pertutga:',
'emailmessage'    => 'Messadi:',
'emailsend'       => 'Trametter',
'emailsent'       => 'Tramess email.',
'emailsenttext'   => 'Tes email è vegnì tramess.',

# Watchlist
'watchlist'            => "mia glista d'observaziun",
'mywatchlist'          => "Mia glista d'observaziun",
'watchlistfor'         => "(per '''$1''')",
'watchnologin'         => "Ti n'es betg t'annunzià!",
'watchnologintext'     => "Ti stos [[Special:UserLogin|t'annunziar]] per midar tia glista d'observaziun.",
'addedwatch'           => 'Agiuntà a la glista dad observaziun',
'addedwatchtext'       => "L'artitgel \"[[:\$1]]\" è vegnì agiuntà a vossa [[Special:Watchlist|glista dad observaziun]]. 
Midadas futuras vid quai artitgel e la pagina da discussiun appertegnenta vegnan enumeradas là e l'artitgel vegn marcà '''grass''' en la [[Special:RecentChanges|glista da las ultimas midadas]].",
'removedwatch'         => "Allontanà da la glista d'observaziun",
'removedwatchtext'     => 'La pagina "[[:$1]]" è vegnida stizzada da [[Special:Watchlist|tia glista d\'observaziun]].',
'watch'                => 'Observar',
'watchthispage'        => 'Guarda questa pagina!',
'unwatch'              => 'betg pli observar',
'watchnochange'        => 'Nagin dals artitgels che ti observeschas è vegnì midà durant la perioda da temp inditgada.',
'watchlist-details'    => "{{PLURAL:$1|Ina pagina|$1 paginas}} èn sin tia glista d'observaziun (senza dumbrar las paginas da discussiun).",
'wlheader-showupdated' => "* artitgels che èn vegnids midà suenter che ti as vis els la davosa giada èn mussads '''grass'''",
'watchmethod-recent'   => "intercurir las davosas midadas per la glista d'observaziun",
'watchmethod-list'     => 'intercurir las paginas observadas davart novas midadas',
'watchlistcontains'    => "Tia glista d'observaziun cuntegna $1 {{PLURAL:$1|pagina|paginas}}.",
'iteminvalidname'      => "Problem cun endataziun '$1', num nunvalaivel...",
'wlnote'               => "Sutvart {{PLURAL:$1|è l'ultima midada|èn las ultimas '''$1''' midadas}} entaifer {{PLURAL:$2|l'ultima ura|las ultimas '''$2''' uras}}.",
'wlshowlast'           => 'Mussar: las ultimas $1 uras, ils ultims $2 dis u $3.',
'watchlist-options'    => "Opziuns per la glista d'observaziun",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'observ...',
'unwatching' => 'observ betg pli...',

'changed' => 'midà',
'created' => 'creà',

# Delete
'deletepage'            => 'Stizzar la pagina',
'confirm'               => 'Confermar',
'historywarning'        => "Attenziun: L'artitgel che ti vuls stidar ha in'istorgia (pliras versiuns):",
'confirmdeletetext'     => 'Ti es vidlonder da stizzar permanentamain in artitgel u in maletg e tut las versiuns pli veglias ord la datoteca. <br /> 
Conferma per plaschair che ti ta es conscient da las consequenzas e che ti ageschas tenor las [[{{MediaWiki:Policy-url}}|directivas da {{SITENAME}}]].',
'actioncomplete'        => "L' acziun è terminada.",
'deletedtext'           => '"<nowiki>$1</nowiki>" è vegnì stizzà.
Sin $2 chattas ti ina glista dals davos artitgels stizzads.',
'deletedarticle'        => '"[[$1]]" è stizzà',
'dellogpage'            => 'log dal stizzar',
'deletecomment'         => 'Motiv:',
'deleteotherreason'     => 'Autra / supplementara raschun:',
'deletereasonotherlist' => 'Autra raschun:',

# Rollback
'rollbacklink'  => 'reinizialisar',
'alreadyrolled' => "Impussibel da reinizialisar la midada da l'artitgel [[:$1]] da l'utilisader [[User:$2|$2]] ([[User talk:$2|talk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
Enzatgi auter ha gia modifitga u reinizialisà qeusta pagina. 

L'ultima modificaziun vid questa pagina è da [[User:$3|$3]] ([[User talk:$3|talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",

# Protect
'protectlogpage'              => 'Log da las protecziuns',
'protectedarticle'            => 'bloccà "[[$1]]"',
'modifiedarticleprotection'   => 'Midà il livel da protecziun per "[[$1]]"',
'prot_1movedto2'              => 'Spustà [[$1]] tar [[$2]]',
'protect-legend'              => 'Midar il status da protecziun da la pagina.',
'protectcomment'              => 'Motiv:',
'protectexpiry'               => 'Pretegì enfin:',
'protect_expiry_invalid'      => "Il temp endatà n'è betg valaivel.",
'protect_expiry_old'          => 'Il temp da proteger giascha en il passà.',
'protect-unchain'             => 'Midar la protecziun per spustar',
'protect-text'                => "Qua pos ti contemplar ed midar il livel da protecziun per l'artitgel '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Tes conto d'utilisader n'ha betg il dretg da midar ils livels da protecziun. 
Qua èn las reglas actualas per l'artitgel '''$1''':",
'protect-cascadeon'           => 'Questa pagina è actualmain protegida, perquai ch\'ella è integrada en {{PLURAL:$1|suandant artitgel che ha activà|suandants artitgels che han activà}} la "protecziun ertada". 
Ti pos midar il livel da protecziun da quest\'artitgel, quai na vegn dentant betg ad avair in effec sin la "protecziun ertada".',
'protect-default'             => 'Lubir tut ils utilisaders',
'protect-fallback'            => 'Il dretg "$1" è necessari',
'protect-level-autoconfirmed' => 'Bloccar utilisaders novs e na-registrads',
'protect-level-sysop'         => 'be administraturs',
'protect-summary-cascade'     => '"protecziun ertaivla"',
'protect-expiring'            => 'Scroda $1 (UTC)',
'protect-cascade'             => 'Proteger paginas integradas en questa pagina ("protecziun ertaivla")',
'protect-cantedit'            => "Ti na pos betg midar il livel da protecziun da questa pagina, perquai che ti n'has betg ils dretgs per far quai.",
'restriction-type'            => 'Status da protecziun:',
'restriction-level'           => 'Livel da protecziun:',

# Restrictions (nouns)
'restriction-edit' => 'Editar',
'restriction-move' => 'spustar',

# Undelete
'viewdeletedpage'           => 'guardar las paginas stizzadas',
'undeletebtn'               => 'restituir',
'undeletelink'              => 'mussar/restituir',
'undeletedarticle'          => 'restituì "[[$1]]"',
'undelete-search-submit'    => 'Tschertga',
'undelete-show-file-submit' => 'Gea',

# Namespace form on various pages
'namespace'      => 'Tip da pagina:',
'invert'         => 'invertar la selecziun',
'blanknamespace' => '(principal)',

# Contributions
'contributions'       => "contribuziuns da l'utilisader",
'contributions-title' => "Contribuziuns d'utilisader da $1",
'mycontris'           => 'mias contribuziuns',
'contribsub2'         => 'Per $1 ($2)',
'uctop'               => '(actual)',
'month'               => 'dal mais (e pli baud):',
'year'                => "da l'onn (e pli baud):",

'sp-contributions-newbies'     => 'Be mussar contribuziuns da contos novs',
'sp-contributions-newbies-sub' => "Per novs contos d'utilisader",
'sp-contributions-blocklog'    => 'Log dal bloccar',
'sp-contributions-search'      => "Tschertgar contribuziuns d'utilisaders",
'sp-contributions-username'    => "Adressa d'IP u num d'utilisader:",
'sp-contributions-submit'      => 'Tschertga',

# What links here
'whatlinkshere'            => 'Links sin questa pagina',
'whatlinkshere-title'      => 'Paginas ch\'èn colliadas cun "$1"',
'whatlinkshere-page'       => 'Pagina:',
'linkshere'                => "Suandantas paginas èn colliadas cun '''[[:$1]]''':",
'nolinkshere'              => "Naginas paginas èn colliadas cun '''[[:$1]]'''.",
'nolinkshere-ns'           => "Naginas paginas èn colliadas cun '''[[:$1]]''' en il tip da pagina tschernì.",
'isredirect'               => 'Pagina che renviescha',
'istemplate'               => 'Integraziun da models',
'isimage'                  => 'colliaziun da datoteca',
'whatlinkshere-prev'       => '{{PLURAL:$1|ultim|ultims $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|proxim|proxims $1}}',
'whatlinkshere-links'      => '← colliaziuns',
'whatlinkshere-hideredirs' => '$1 sviaments',
'whatlinkshere-hidetrans'  => '$1 integraziuns da models',
'whatlinkshere-hidelinks'  => '$1 colliaziuns',
'whatlinkshere-filters'    => 'Filters',

# Block/unblock
'blockip'                  => 'Bloccar utilisader',
'ipaddress'                => "Adressa d'IP:",
'ipadressorusername'       => "Adressa d'IP u num d'utilisader:",
'ipbexpiry'                => 'Temp da bloccaziun:',
'ipboptions'               => '2 uras:2 hours,1 di:1 day,3 dis:3 days,1 emna:1 week,2 emnas:2 weeks,1 mais:1 month,3 mais:3 months,6 mais:6 months,1 onn:1 year,infinit:infinite', # display1:time1,display2:time2,...
'badipaddress'             => "l'adressa-IP è nunvalida",
'ipblocklist'              => "Glista da las adressas da'IP e dals nums d'utilisader bloccads",
'ipblocklist-submit'       => 'Tschertgar',
'blocklink'                => 'bloccar',
'unblocklink'              => 'de-bloccar',
'change-blocklink'         => 'Midar opziuns da bloccar',
'contribslink'             => 'contribuziuns',
'autoblocker'              => "Vossa adressa dad IP è vegnida bloccada perquai che vus utilisais ina adressa dad IP cun [[User:$1|$1]]. Motiv per bolccar $1: '''$2'''.",
'blocklogpage'             => 'Log dal bloccar',
'blocklogentry'            => 'bloccà [[$1]] per $2. Motiv: $3',
'unblocklogentry'          => "debloccà l'utilisader „$1“",
'block-log-flags-nocreate' => 'Deactivà la creaziun da contos',

# Developer tools
'databasenotlocked' => 'Questa banca da datas è betg bloccada.',

# Move page
'move-page'                 => 'Spustar "$1"',
'move-page-legend'          => 'Spustar la pagina',
'movepagetext'              => "Cun il formular sutvart das ti in nov num ad in artitgel e spostas l'entira istorgia da l'artitgel al nov. 
L'artitgel vegl renviescha lura al nov. 
Ti pos actualisar automaticamain paginas che renvieschan a l'artitgel original. 
Sche ti na vuls betg quai, controllescha p. pl las paginas che renvieschan [[Special:DoubleRedirects|dublamain]] u [[Special:BrokenRedirects|incorrect]]. 
Ti ès responsabels che tut las colliaziuns mainan al lieu ch'els duessan. 

Fa stim, che la pagina '''na vegn betg''' spustada sch'i exista gia in artitgel cun il nov titel, auter sche quel è vids u renviescha ad in'autra pagina e n'ha nagina istorgia. 

'''ATTENZIUN!'''
Quai po esser ina midada drastica ed nunspetgada per in artitgel popular; 
sajas conscient da las consequenzas che quai process po avair.",
'movepagetalktext'          => "La pagina da discussiun che tutga tar l'artitgel vegn spustada automaticamain cun l'artitgel, '''sche betg''':
*Ina pagina da discussiun betg vida exista gia sut il lemma nov
*Ti prendas ora il crutschin dal champ sutvart

En quests cas as ti da spustar u colliar manualmain las paginas, sche giavischà.",
'movearticle'               => 'Spustar artitgel:',
'movenologin'               => "Ti n'ès betg t'annunzià",
'movenologintext'           => "Ti stos [[Special:UserLogin|t'annunziar]] per spustar in artitgel.",
'newtitle'                  => 'Al titel nov:',
'move-watch'                => 'Observar questa pagina',
'movepagebtn'               => 'Spustar la pagina',
'pagemovedsub'              => 'Spustà cun success',
'movepage-moved'            => '\'\'\'"$1" è vegnì spustà a "$2"\'\'\'', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movepage-moved-redirect'   => 'In renviament è vegnì creà.',
'movepage-moved-noredirect' => 'I è vegnì impedì da crear in renviament.',
'articleexists'             => 'I exista gia in artitgel cun quai num. Tscherni per plaschair in auter.',
'talkexists'                => "'''L'artitgel è vegnì spustà cun success. Dentant exista sut il nov num gia ina pagina da discussiun, perquai è la pagina da discussiun betg vegnida spustada. Fa quai p. pl. a maun.'''",
'movedto'                   => 'spustà a',
'movetalk'                  => "Spustar la pagina da discussiun che tutga tar l'artitgel",
'1movedto2'                 => 'Spustà [[$1]] a [[$2]]',
'1movedto2_redir'           => 'Spustà [[$1]] a [[$2]] cun in renviament',
'move-redirect-suppressed'  => 'Impedì renviament',
'movelogpage'               => 'Log dal spustar',
'movereason'                => 'Motiv:',
'revertmove'                => 'spustar anavos',
'delete_and_move'           => 'Stizzar e spustar',
'delete_and_move_text'      => '==Stizzar necessari==

L\'artitgel da destinaziun "[[:$1]]" exista gia. Vul ti stizzar el per far plaz per spustar?',
'delete_and_move_confirm'   => 'Gea, stizzar il artitgel da destinaziun per spustar',
'delete_and_move_reason'    => 'Stizzà per far plaz per spustar',
'immobile-source-namespace' => 'Paginas dal tip da pagina "$1" na pon betg vegnir spustadas',
'immobile-target-namespace' => 'Betg pussaivel da spustar paginas en il tip da pagina "$1"',
'imagenocrossnamespace'     => 'Betg pussaivel da spustar ina datoteca ad in tip da pagina betg da datoteca',
'fix-double-redirects'      => 'Schliar renviaments dubels suenter il spustar',
'move-leave-redirect'       => 'Crear renviament',

# Export
'export'           => 'Exportar paginas',
'export-addnstext' => 'Agiuntar paginas ord il tip da pagina:',
'export-templates' => 'Includer models',

# Namespace 8 related
'allmessages'               => 'communicaziuns dal sistem',
'allmessagesname'           => 'num',
'allmessagesdefault'        => 'text original',
'allmessagescurrent'        => 'text actual',
'allmessagestext'           => 'Quai è ina glista da tut las communicaziuns dals differents tips da paginas da MediaWiki che vegnan utilisadas da la software da MediaWiki.
Fai ina visita sin [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] e [http://translatewiki.net translatewiki.net] sche ti vuls gidar da translatar la software da MediaWiki.',
'allmessagesnotsupportedDB' => "Questa pagina na po betg vegnir mussada, perquai che '''\$wgUseDatabaseMessages''' è vegnì deactivà.",
'allmessagesfilter'         => 'filter dals nums da las novitads:',
'allmessagesmodified'       => 'Be mussar modifitgads',

# Thumbnails
'thumbnail-more'           => 'Mussar pli grond',
'thumbnail_error'          => 'Sbagl cun crear il maletg da prevista: $1',
'thumbnail_invalid_params' => 'Parameters nunvalids dal maletg da prevista',
'thumbnail_dest_directory' => "Betg pussaivel da crear l'ordinatur da destinaziun.",

# Special:Import
'import'                     => 'Impurtar paginas',
'import-interwiki-templates' => 'Includer tut ils models',
'import-interwiki-namespace' => 'Tip da pagina da destinaziun:',

# Import log
'importlogpage' => 'Log dals imports',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "Guardar tia pagina d'utilisader",
'tooltip-pt-mytalk'               => 'Guardar tia pagina da discussiun',
'tooltip-pt-preferences'          => 'mias preferenzas',
'tooltip-pt-watchlist'            => 'La glista da las paginas da las qualas jau observ las midadas',
'tooltip-pt-mycontris'            => 'Guardar la glista da tut tias contribuziuns',
'tooltip-pt-login'                => "I fiss bun sche ti s'annunziassas, ti na stos dentant betg.",
'tooltip-pt-anonlogin'            => "I fiss bun sche ti t'annunziassas; quai n'è dentant betg obligatoric.",
'tooltip-pt-logout'               => 'Sortir',
'tooltip-ca-talk'                 => "Discussiuns davart il cuntegn da l'artitgel",
'tooltip-ca-edit'                 => "Ti pos editar questa pagina. 
Utilisescha per plaschair il buttun 'mussar prevista' avant che memorisar.",
'tooltip-ca-addsection'           => 'Cumenzar nov paragraf',
'tooltip-ca-viewsource'           => 'Questa pagina è protegida.
Ti pos vesair il code-fundamental.',
'tooltip-ca-history'              => 'Versiuns pli veglias da questa pagina',
'tooltip-ca-protect'              => 'Proteger questa pagina',
'tooltip-ca-delete'               => 'Stizzar quest artitgel',
'tooltip-ca-move'                 => 'Spustar questa pagina',
'tooltip-ca-watch'                => "Agiuntar questa pagina a tia glista d'observaziun",
'tooltip-ca-unwatch'              => "Allontanar questa pagina da tia pagina d'observaziun",
'tooltip-search'                  => 'Intercurir {{SITENAME}}',
'tooltip-search-go'               => "Mussar la pagina cun exact quest num (sch'ella exista)",
'tooltip-search-fulltext'         => 'Tschertgar en tut las paginas quest text',
'tooltip-n-mainpage'              => 'Ir a la pagina principala',
'tooltip-n-portal'                => 'Infurmaziuns davart il project, tge che ti pos far, nua che ti chassas infurmaziuns',
'tooltip-n-currentevents'         => 'Chattar infurmaziuns davart occurrenzas actualas',
'tooltip-n-recentchanges'         => 'La glista da las ultimas midadas en la wiki.',
'tooltip-n-randompage'            => 'Chargiar ina pagina casuala.',
'tooltip-n-help'                  => 'Qua chattas agid.',
'tooltip-t-whatlinkshere'         => 'Glista da tut las paginas vichi che mussan sin questa pagina',
'tooltip-t-recentchangeslinked'   => 'Ultimas midadas sin paginas colliadas cun questa pagina',
'tooltip-feed-rss'                => 'RSS feed per questa pagina',
'tooltip-feed-atom'               => 'Atom feed per questa pagina',
'tooltip-t-contributions'         => 'Guardar las contribuziuns da quest utilisader',
'tooltip-t-emailuser'             => 'Trametter in e-mail a quest utilisader',
'tooltip-t-upload'                => 'Chargiar si datotecas',
'tooltip-t-specialpages'          => 'Glista da tut las paginas spezialas',
'tooltip-t-print'                 => 'Versiun per stampar da questa pagina',
'tooltip-t-permalink'             => 'Link permanent tar questa versiun da la pagina',
'tooltip-ca-nstab-main'           => "Guardar l'artitgel",
'tooltip-ca-nstab-user'           => "Guardar la pagina da l'utilisader",
'tooltip-ca-nstab-media'          => 'Guardiar la pagina cun medias',
'tooltip-ca-nstab-special'        => 'Quai è ina pagina speziala, quella na pos ti betg editar',
'tooltip-ca-nstab-project'        => 'Guardar la pagina da project',
'tooltip-ca-nstab-image'          => 'Guardar la pagina da la datoteca',
'tooltip-ca-nstab-mediawiki'      => 'Guardar ils messadis dal sistem',
'tooltip-ca-nstab-template'       => 'Mussar il model',
'tooltip-ca-nstab-help'           => "Guardar la pagina d'agid",
'tooltip-ca-nstab-category'       => 'Guardar la pagina da la categoria',
'tooltip-minoredit'               => 'Marcar questa midada sco midada pitschna',
'tooltip-save'                    => 'Memorisar las midadas',
'tooltip-preview'                 => 'Prevista da las midadas. Utilisescha p. pl. questa funcziun avant che memorisar!',
'tooltip-diff'                    => 'Mussar las midadas che ti has fatg en il text.',
'tooltip-compareselectedversions' => 'Mussar la differenza tranter las duas versiuns selecziunadas da questa pagina.',
'tooltip-watch'                   => "Agiuntar questa pagina a tia pagina d'observaziun",
'tooltip-rollback'                => "Revochescha tut las modificaziuns vid questa pagina da l'ultim utilisader cun be in clic.",
'tooltip-undo'                    => 'Revochescha be questa midada e mussa il resultat en la prevista, per che ti pos inditgar en il champ da resumaziun in motiv.',

# Stylesheets
'common.css'   => '/** CSS placed here will be applied to all skins */',
'monobook.css' => "/* editescha quest file per adattar il skin momobook per l'entira pagina */",

# Scripts
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Attribution
'anonymous'        => '{{PLURAL:$1|In utilisader anonim|Utilisaders anonims}} da {{SITENAME}}',
'siteuser'         => 'utilisader $1 da {{SITENAME}}',
'lastmodifiedatby' => 'Questa pagina è vegnida modifitgada la davosa giada ils $1 las $2 da $3.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Basescha sin la lavur da $1.',
'others'           => 'auters',
'creditspage'      => 'Statistica da la pagina',

# Info page
'numedits'       => 'Dumber da las versiuns da quest artitgel: $1',
'numtalkedits'   => 'Dumber da las versiuns da la pagina da discussiun: $1',
'numwatchers'    => 'dumber dals observaturs: $1',
'numauthors'     => 'Dumber dals auturs da quest artitgel: $1',
'numtalkauthors' => 'dumber dals participants da la discussiun: $1',

# Math options
'mw_math_png'    => 'Adina mussar sco PNG',
'mw_math_simple' => 'HTML sche fitg simpel, uschiglio PNG',
'mw_math_html'   => 'HTML sche pussibel ed uschigio PNG',
'mw_math_source' => 'Schar en furma da TeX (per browsers da text)',
'mw_math_modern' => 'Recumandà per browsers moderns',
'mw_math_mathml' => 'MathML sche pussibel (experimental)',

# Image deletion
'deletedrevision' => 'Stizzà la veriun veglia $1.',

# Browsing diffs
'previousdiff' => '← Versiun pli veglia',
'nextdiff'     => 'versiun pli nova →',

# Media information
'imagemaxsize'         => 'Grondezza maximala per maletgs sin paginas da descripziun',
'thumbsize'            => 'grondezza dals maletgs da prevista:',
'file-info-size'       => '($1 × $2 pixels, grondezza da datoteca: $3, tip da MIME: $4)',
'file-nohires'         => '<small>Nagina resuluziun pli auta disponibla.</small>',
'svg-long-desc'        => '(datoteca da SVG, grondezza da basa $1 × $2 pixels, grondezza da datoteca: $3)',
'show-big-image'       => 'Resoluziun cumplaina',
'show-big-image-thumb' => '<small>Grondezza da quest prevista: $1 × $2 pixels</small>',

# Special:NewFiles
'newimages' => 'Novs maletgs',
'ilsubmit'  => 'Tschertgar',
'bydate'    => 'tenor data',

# Bad image list
'bad_image_list' => "Il format è sco suonda: 

Be elements da glistas (lingias che entschaivan cun in *) vegnan risguardads. 
L'emprima colliaziun duai esser ina colliaziun ad in maletg betg giavischà. 
Tut las colliaziuns che suandan sin la medema lingia vegnan risguardadas sco excepziuns.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Questa datoteca cuntegna infurmaziuns supplementaras, probablamain agiuntadas da la camera digitala u dal scanner utilisà per crear digitalisar ella. 
Sche la datoteca è vegnida midada dal status original èn tscherts detagls eventualmain betg pli corrects.',
'metadata-expand'   => 'Mussar detagls extendids',
'metadata-collapse' => 'Zuppar detagls extendids',
'metadata-fields'   => 'Suandants champs da las EXIF-Metadata en quest text da sistem da MediaWiki vegnan mussads sin las paginas da descripziun dal maletg; uleriurs detagls zuppads normalmain pon vegnir mussads.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'  => 'Ladezza',
'exif-imagelength' => 'Autezza',

'exif-lightsource-0'  => 'Betg enconuschent',
'exif-lightsource-1'  => 'Glisch dal di',
'exif-lightsource-2'  => 'Fluorescent',
'exif-lightsource-3'  => 'Pair electric',
'exif-lightsource-4'  => 'Chametg/straglisch',
'exif-lightsource-9'  => "Bel'aura",
'exif-lightsource-10' => 'Nivels',
'exif-lightsource-11' => 'Sumbriva',

# External editor support
'edit-externally'      => 'Editar questa datoteca cun in program extern',
'edit-externally-help' => '(See the [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] for more information)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tuts',
'imagelistall'     => 'tuts',
'watchlistall2'    => 'tut',
'namespacesall'    => 'tuts',
'monthsall'        => 'tuts',

# E-mail address confirmation
'confirmemail'            => "Confermar l'adressa dad email",
'confirmemail_text'       => "Questa wiki dovra ina confermaziun da tia adressa d'email per pudair utilisar las funcziuns dad email. Activescha il buttun sutvart per trametter ina damonda da confermaziun a tia adressa d'email. L'email cuntegn in link cun code. Clicca il link per confermar tia adressa.",
'confirmemail_send'       => 'Ma trametta in code da confermaziun!',
'confirmemail_sent'       => "Tramess l'email da confermaziun.",
'confirmemail_sendfailed' => "{{SITENAME}} na pudeva betg trametter l'e-mail per confermar l'adressa d'e-mail. Controllescha sche ti has endatà caracters nunvalids. 

Il mailer ha returnà: $1",
'confirmemail_invalid'    => 'Il code da confermaziun è invalaivels. Il code è probablamain scrudà.',
'confirmemail_needlogin'  => "Ti stos $1 per confirmar tia adressa d'e-mail.",
'confirmemail_success'    => "Tia adressa d'email è vegnida confermada. Ti pos uss t'annunziar e guder la wiki rumantscha.",
'confirmemail_loggedin'   => "Tia adressa d'email è ussa vegnida confermada.",
'confirmemail_error'      => 'Insatge è crappà cun tes mail da confermaziun. Stgisa foll!',
'confirmemail_subject'    => "Confermaziun da l'adressa d'email tar {{SITENAME}}",
'confirmemail_body'       => 'Olla, insatgi cun l\'adressa dad IP $1,probablamain ti, ha pretendì ina confermaziun da questa adressa ad mail per il conto d\'utilisader "$2" sin {{SITENAME}}.

Per confermar che quest conta tutga propi tar tia adress da mail, avra per plaschair la suandanta colliaziun, che è valaivel enfin ils $4, en tes browser: 

$3

Sche l\'adressa na tutga *betg* tar il conto numnà, suanda per plaschair *betg* a questa colliaziun: 

$5

Bler divertiment!',

# Scary transclusion
'scarytranscludefailed' => "[Betg reussì d'integrar in model per $1]",

# Delete conflict
'deletedwhileediting' => "'''Attenziun:''' Questa pagina è vegnida stizzada suenter che ti has cumanzà a l'editar.",
'confirmrecreate'     => "L'utilisader [[User:$1|$1]] ([[User talk:$1|talk]]) ha stizzà quest artitgel (motiv: ''$2'') suenter che ti as cumenzà a modifitgar l'artitgel. 
Conferma per plaschair che ti vuls propi crear danovamain quest artitgel.",

# action=purge
'confirm_purge_button' => 'ok',
'confirm-purge-top'    => 'Stizzar il cache da questa pagina?',

# Multipage image navigation
'imgmultipageprev' => '← ultima pagina',
'imgmultipagenext' => 'proxima pagina →',

# Table pager
'table_pager_next'         => 'Proxima pagina',
'table_pager_prev'         => 'Ultima pagina',
'table_pager_first'        => 'Emprima pagina',
'table_pager_last'         => 'Ultima pagina',
'table_pager_limit'        => 'Mussar $1 elements per pagina',
'table_pager_limit_submit' => 'Dai',
'table_pager_empty'        => 'Nagins resultats',

# Auto-summaries
'autoredircomment' => 'Creà renviament a [[$1]]',

# Watchlist editing tools
'watchlisttools-view' => 'Guardar las midadas relevantas',
'watchlisttools-edit' => "Guardar ed editar la glista d'observaziun",
'watchlisttools-raw'  => 'Editar il format da la glista (import/export)',

# Special:Version
'version'              => 'Versiun', # Not used as normal message but as header for the special page itself
'version-extensions'   => 'Extensiuns installadas',
'version-specialpages' => 'Paginas spezialas',

# Special:FilePath
'filepath'        => 'Percurs da la datoteca',
'filepath-page'   => 'Datoteca:',
'filepath-submit' => 'Percurs',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Num da datoteca:',
'fileduplicatesearch-submit'   => 'Tschertgar',
'fileduplicatesearch-info'     => '$1 x $2 pixels<br />Grondezza da datoteca: $3<br />Tip da MIME: $4',

# Special:SpecialPages
'specialpages'                 => 'Paginas spezialas',
'specialpages-group-other'     => 'Autras paginas specialas',
'specialpages-group-login'     => "T'annunziar / registrar",
'specialpages-group-pages'     => 'Glistas da paginas',
'specialpages-group-redirects' => 'Paginas specialas che renvieschan',

# Database error messages
'dberr-problems' => 'Stgisa!
Questa pagina ha actualmain difficultads tecnicas.',

);
