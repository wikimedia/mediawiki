<?php

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesCa = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Especial',
	NS_MAIN				=> '',
	NS_TALK				=> 'Discussió',
	NS_USER				=> 'Usuari',
	NS_USER_TALK		=> 'Usuari_Discussió',
	NS_WIKIPEDIA		=> 'Viquipèdia',
	NS_WIKIPEDIA_TALK	=> 'Viquipèdia_Discussió',
	NS_IMAGE			=> 'Imatge',
	NS_IMAGE_TALK		=> 'Imatge_Discussió',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'MediaWiki_Discussió',
	NS_TEMPLATE			=> 'Template',
	NS_TEMPLATE_TALK	=> 'Template_Discussió',
	NS_HELP				=> 'Ajuda',
	NS_HELP_TALK		=> 'Ajuda_Discussió',
	NS_CATEGORY			=> 'Categoria',
	NS_CATEGORY_TALK	=> 'Categoria_Discussió'
) + $wgNamespaceNamesEn;

/* Note that some default options can be customized -- see
   '$wgDefaultUserOptionsEn' in Language.php */

/* private */ $wgQuickbarSettingsCa = array(
	"Cap", "Fixa a la dreta", "Fixa a l'esquerra", "Surant a l'esquerra"
);

/* private */ $wgSkinNamesCa = array(
	'standard' => "Estàndard",
	'nostalgia' => "Nostàlgia",
	'cologneblue' => "Colònia blava",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);

/* private */ $wgMathNamesCa = array(
	"Produeix sempre PNG",
	"HTML si és molt simple, si no PNG",
	"HTML si és possible, si no PNG",
	"Deixa com a TeX (per a navegadors de text)"
);

/* private */ $wgDateFormatsCa = array(
	"No preference",
	"January 15, 2001",
	"15 January 2001",
	"2001 January 15",
	"2001-01-15"
);

/* private */ $wgUserTogglesCa = array(
	"hover"		=> "Mostra caixa surant sobre els enllaços wiki",
	"underline" => "Subratlla enllaços",
	"highlightbroken" => "Destaca enllaços a temes buits",
	"justify"	=> "Ajusta paràgrafs",
	"hideminor" => "Amaga edicions menors en canvis recents",
	"usenewrc" => "Canvis recents remarcats (no per tots els navegadors)",
	"numberheadings" => "Autoenumera encapçalaments",
	"showtoolbar" => "Show edit toolbar",
	"rememberpassword" => "Recorda la contrasenya entre sessions",
	"editwidth" => "La caixa d'edició té l'ample màxim",
	"editondblclick" => "Edita pàgines amb un doble clic (JavaScript)",
	"editsection"=>"Enable section editing via [edit] links",
	"editsectiononrightclick"=>"Enable section editing by right clicking<br> on section titles (JavaScript)",
	"showtoc"=>"Show table of contents<br>(for articles with more than 3 headings)",
	"watchdefault" => "Vigila articles nous i modificats",
	"minordefault" => "Marca totes les edicions com menors per defecte",
	"previewontop" => "Show preview before edit box and not after it",
	"nocache" => "Disable page caching"
);

/* Please customize this with some Catalan-language bookshops
   and/or reference sites that can look up by ISBN number */
/* private */ $wgBookstoreListCa = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgWeekdayNamesCa = array(
	"Diumenge", "Dilluns", "Dimarts", "Dimecres", "Dijous",
	"Divendres", "Dissabte"
);

/* private */ $wgMonthNamesCa = array(
	"gener", "febrer", "març", "abril", "maig", "juny",
	"juliol", "agost", "setembre", "octubre", "novembre",
	"desembre"
);

/* private */ $wgMonthAbbreviationsCa = array(
	"gen", "feb", "mar", "abr", "mai", "jun", "jul", "ago",
	"set", "oct", "nov", "des"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesCa = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Preferències d'usuari",
	"Watchlist"		=> "El meu llistat de seguiment",
	"Recentchanges" => "Canvis Recents",
	"Upload"		=> "Puja una imatge",
	"Imagelist"		=> "Llistat d'imatges",
	"Listusers"		=> "Usuaris registrats",
	"Statistics"	=> "Estadístiques del lloc",
	"Randompage"	=> "Article aleatori",

	"Lonelypages"	=> "Articles orfes",
	"Unusedimages"	=> "Imatges hores",
	"Popularpages"	=> "Articles populars",
	"Wantedpages"	=> "Articles més sol·licitats",
	"Shortpages"	=> "Articles curts",
	"Longpages"		=> "Articles llargs",
	"Newpages"		=> "Articles nous",
	"Allpages"		=> "Totes les pàgines (alfabètic)",

	"Ipblocklist"	=> "Direccions d'IP bloquejades",
	"Maintenance"   => "Pàgina de manutenció",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"     => "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"   => "Fonts externes de llibres",
	"Export"		=> "XML export",
	"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesCa = array(
	"Blockip"		=> "Bloqueja una direcció IP",
	"Asksql"		=> "Recerca en la base de dades",
	"Undelete"      => "Mostra i restaura les pàgines esborrades"
);

/* private */ $wgDeveloperSpecialPagesCa = array(
	"Lockdb"		=> "Tanca l'accés d'escriptura a la base de dades",
	"Unlockdb"		=> "Restaura accés d'escriptura a la base de dades",
);

/* private */ $wgAllMessagesCa = array(

# Bits of text used by many pages:
#
"linktrail"     => "/^((?:[a-z]|à|è|é|í|ò|ó|ú|ç|·|ï|ü|')]+)(.*)\$/sD",
"mainpage"		=> "Portada",
"about"			=> "Quant a...",
"aboutwikipedia" => "Quant a la Viquipèdia",
"aboutpage"		=> "Viquipèdia: Quant a",
"help"			=> "Ajuda",
"helppage"		=> "Viquipèdia:Ajuda",
"wikititlesuffix"		=>"Viquipèdia",
"bugreports"	=> "Informes d'error del programari",
"bugreportspage" => "Viquipèdia:Informes_d'error",
"faq"			=> "PMF",
"faqpage"		=> "Viquipèdia:PMF",
"edithelp"		=> "Ajuda d'edició",
"edithelppage"	=> "Viquipèdia:Com_s'edita_una_pàgina",
"cancel"		=> "Anul·la",
"qbfind"		=> "Troba",
"qbbrowse"		=> "Fulleja",
"qbedit"		=> "Edita",
"qbpageoptions" => "Opcions de pàgina",
"qbpageinfo"	=> "Informació de pàgina",
"qbmyoptions"	=> "Les meves opcions",
"mypage"		=> "La meva pàgina",
"mytalk"        => "La meva discussió",
"currentevents" => "Actualitat",
"errorpagetitle" => "Error",
"returnto"		=> "Torna a $1.",
"fromwikipedia"	=> "De Viquipèdia, l'enciclopèdia lliure.",
"whatlinkshere"	=> "Pàgines que enllacen ací",
"help"			=> "Ajuda",
"search"		=> "Cerca",
"history"		=> "Història",
"printableversion" => "Versió per imprimir",
"editthispage"	=> "Edita aquesta pàgina",
"deletethispage" => "Esborra aquesta pàgina",
"protectthispage" => "Protegeix aquesta pàgina",
"unprotectthispage" => "Desprotegeix aquesta pàgina",
"talkpage"		=> "Discuteix aquesta pàgina",
"articlepage"   => "Mostra article",
"subjectpage"	=> "Article",
"userpage" => "Mostra pàgina d'usuari",
"wikipediapage" => "Mostra meta pàgina",
"imagepage" => 	"Mostra pàgina d'imatge",
"otherlanguages" => "Altres idiomes",
"redirectedfrom" => "(Redirigit des de $1)",
"lastmodified"	=> "Aquesta pàgina ha estat modificada per última vegada el $1.",
"viewcount"		=> "Aquesta pàgina ha estat visitada $1 vegades.",
"gnunote" => "Aquesta pàgina es fa disponible sota la <a class=internal href='$wgScriptPath/GNU_FDL'>GNU FDL</a>.",
"printsubtitle" => "(De http://ca.wikipedia.org)",
"protectedpage" => "Pàgina protegida",
"administrators" => "Viquipèdia:Administradors",
"sysoptitle"	=> "Accés requerit de Sysop",
"sysoptext"		=> "L'acció que heu requerit només pot ser duta a terme per usuaris amb estatus de \"sysop\".
Ver $1.",
"developertitle" => "Accés de desenvolupador requerit",
"developertext"	=> "L'acció que heu requerit només pot ser duta a terme per usuaris amb l'estatus de \"desenvolpador\".
Vegeu $1.",
"nbytes"		=> "$1 octets",
"go"			=> "Vés-hi",
"ok"			=> "D'acord",
"sitetitle"		=> "Viquipèdia",
"sitesubtitle"	=> "L'Enciclopèdia Lliure",
"retrievedfrom" => "Obtingut de \"$1\"",

# Main script and global functions
#
"nosuchaction"	=> "Aquesta acció no existeix",
"nosuchactiontext" => "L'acció especificada per l'URL no és reconeguda pel programari de la Viquipèdia",
"nosuchspecialpage" => "No existeix aquesta pàgina especial",
"nospecialpagetext" => "Heu requerit una pàgina especial que no és reconeguda pel programari de la Viquipèdia.",

# General errors
#
"error"			=> "Error",
"databaseerror" => "Error de la base de dades",
"dberrortext"	=> "Ha ocorregut un error de sintaxi en una consulta a la base de dades. Això pot ser degut a una recerca il·legal (vegeu $5)

,o pot indicar un error en el programari. L'última consulta que s'ha intentat ha estat:
<blockquote><tt>$1</tt></blockquote>L'error de retorn de MySQL ha estat\"<tt>$3: $4</tt>\".",
"noconnect"		=> "No s'ha pogut connectar a la base de dades a $1",
"nodb"			=> "No s'ha pogut seleccionar la base de dades$1",
"readonly"		=> "Base de dades bloquejada",
"enterlockreason" => "Entreu una raó per bloquejar-la, incloent una estimació de quan s'efecturarà el bloqueig",
"readonlytext"	=> "La base de dades de la Viquipèdia està temporalment bloquejada per noves entrades o altres modificacions, 

probablement per manteniment de rutina, després del qual tornarà a la normalitat. L'administrador la bloquejada ha ofert aquesta explicació:
<p>$1",
"missingarticle" => "La base de dades no ha trobat el text d'una
pàgina que hauria d'haver trobat, anomenada \"$1\".
Això no és un error de la base de dades, sinó més probablement
un error en el programari. Informeu-ne si us plau a un administrador,
fent-ne arribar l'URL.",
"internalerror" => "Error intern",
"filecopyerror" => "No s'ha pogut copiar l'arxiu \"$1\" a \"$2\".",
"filerenameerror" => "No s'ha pogut reanomenar l'arxiu \"$1\" a \"$2\".",
"filedeleteerror" => "No s'ha pogut esborrar l'arxiu \"$1\".",
"filenotfound"	=> "No s'ha pogut trobar l'arxiu \"$1\".",
"unexpected"	=> "Valor no esperat: \"$1\"=\"$2\".",
"formerror"		=> "Error: no s'ha pogut trametre la forma",	
"badarticleerror" => "Aquesta acció no es pot dur a terme en aquesta pagina.",
"cannotdelete"	=> "No s`'ha pogut esborrar la pàgina o imatge especificada.(Pot haver estat esborrada per algú abans)",
"badtitle"		=> "Títol incorrecte",
"badtitletext"	=> "El títol de la pàgina requerida era invàlid, buit, o un enllaç interidioma o interwiki incorrecte.",

"perfdisabled" => "Aquesta funció està temporalment desactivada",

# Login and logout pagesítulo
"logouttitle"	=> "Fin de sessió",
"logouttext"	=> "Heu acabat la vostra sessió.
Podeu continuar emprant la Viquipèdia de forma anònima, o podeu
iniciar sessió un altre cop amb el mateix o un altre nom d'usuari.\n",

"welcomecreation" => "<h2>Benvingut, $1!</h2><p>El vostre compte ha estat creat.
Recordeu personalitzar les vostres preferències del Viquipèdia.",

"loginpagetitle" => "Registre/Entrada",
"yourname"		=> "Nom d'usuari",
"yourpassword"	=> "Contrasenya",
"yourpasswordagain" => "Repetiu la contrasenya",
"newusersonly"	=> " (només usuaris nous)",
"remembermypassword" => "Vull que recordis la meva contrasenya entre sessions.",
"loginproblem"	=> "<b>Hi ha hagut un problema amb l'entrada.</b><br>Proveu-ho de nou!",
"alreadyloggedin" => "<font color=red><b>Benvingut, usuari $1!</b></font><br>\n",

"areyounew"		=> "Si sou nou a la Viquipèdia en català, i
voleu tenir un compte d'usuari, ingresseu un nom d'usuari,
i teclegeu i repetiu una contrasenya.
La vostra direcció electrònica és opcional: si perdeu o oblideu la
contrasenya, podeu demanar que s'enviï a la direcció que vulgueu<br>\n",
"login"			=> "Registre/Entrada",
"userlogin"		=> "Registre/Entrada",
"logout"		=> "Sortida",
"userlogout"	=> "Sortida",
"createaccount"	=> "Crea un nou compte",
"badretype"		=> "Les contrasenyes que heu ingressat no concorden.",
"userexists"	=> "El nom que heu entrat ja és en ús. Escolliu un nombre diferent.",
"youremail"		=> "Direcció electrònica",
"yournick"		=> "Malnom (nom que es mostrarà)",
"emailforlost"	=> "Si perdeu o oblideu la vostra contrasenya, podeu demanar que se us enviï una nova a la vostra direcció electrònica.", 
"loginerror"	=> "Error d'inici de sessió",
"noname"		=> "No heu especificat un nom vàlid d'usuari.",
"loginsuccesstitle" => "S'ha iniciat la sessió amb èxit",
"loginsuccess"	=> "Heu iniciat la sessió a Viquipèdia com a \"$1\".",
"nosuchuser"	=> "No hi ha cap usuari amb el nom \"$1\".
Reviseu-ne l'ortografia, o empreu el formulari d'avall per crear un nou compte d'usuari.",
"wrongpassword"	=> "La contrasenya que heu ingressat és incorrecta. Torneu-ho a provar.",
"mailmypassword" => "Envia'm una nova contrasenya per correu electrònic",
"passwordremindertitle" => "Recordatori de contrasenya de la Viquipèdia",
"passwordremindertext" => "Algú (amb l'IP $1)
ha sol·licitat que li enviéssim una nova contrasenya per iniciar la sessió a la Viquipèdia.
La contrasenya per l'usuari \"$2\" és ara \"$3\".
Ara hauríeu d'iniciar la sessió i canviar la vostra contrasenya.",
"noemail"		=> "No hi ha cap direcció electrònica registrada per l'usuari \"$1\".",
"passwordsent"	=> "S'ha enviat una nova contrasenya a la direcció electrònica registrada per \"$1\".
Entreu-hi de nou després de rebre-la.cibas.",

# Edit pages
#
"summary"		=> "Resum",
"minoredit"		=> "Aquesta és una edició menor.",
"watchthis"		=> "Vigila aquest article.",
"savearticle"	=> "Desa la pàgina",
"preview"		=> "Previsualitza",
"showpreview"	=> "Mostra previsualizació",
"blockedtitle"	=> "L'usuari està bloquejat",
"blockedtext"	=> "El vostre nombre d'usuari o direcció IP ha estat bloquejada per $1.
La raó és la que segueix:<br>$2<p>Podeu contactar amb l'administrador per discutir el bloqueig.",
"newarticle"	=> "(Nou)",
"newarticletext" => "Poseu el text per la pàgina nova ací.",
"noarticletext" => "(En aquest moment, a aquesta pàgina, no hi ha text)",
"updated"		=> "(Actualitzat)",
"note"			=> "<strong>Nota:</strong> ",
"previewnote"	=> "Recordeu que això només és una previsualització, i encara no s'ha gravat!",
"previewconflict" => "Aquesta previsualització reflexa el text a l'ària
d'edició superior tal i com apareixerà si escolliu gravar.",
"editing"		=> "S'està editant $1",
"editconflict"	=> "Conflicte d'edició: $1",
"explainconflict" => "Algú més ha canviat aquesta pàgina des que l'heu editada. 
L'ària de text superior conté el text de la pàgina com existeix actualment. Els vostres canvis es mostren a l'ària de text inferior.
Haureu d'incorporar els vostres canvis en el text existent.
<b>Sólo</b> el text a l'ària de text superior serà gravat quan premeu
 \"Desa pàgina\".\n<p>",
"yourtext"		=> "El vostre text",
"storedversion" => "Versió emmagatzemada",
"editingold"	=> "<strong>ATENCIÓ:Esteu editant una versió antiga d'aquesta pàgina.
Si la graveu, els canvis fets des d'eixa revisió es perdran.</strong>\n",
"yourdiff"		=> "Diferències",
"copyrightwarning" => "Noteu que totes les contribucions a la Viquipèdia 
es consideren fetes públiques sota la llicència de documentació lliure GNU 
(mostra detalls a $1). 
 Si no desitgeu que la gent corregeixi els vostres escrits sense pietat 
i els distribueixi lliurement, llavors no els poseu ací. <br>
També ens heu d'assegurar que tot plegat és obra vostra i que sou l'amo dels drets d'autor, o els heu copiat des del domini públic 
o una altra font lliura.
 <strong>NO EMPREU ESCRITS AMB COPYRIGHT SENSE PERMÍS!</strong>",

# History pages
#
"revhistory"	=> "Història de revisions",
"nohistory"		=> "No hi ha una història de revisions per a aquesta pàgina.",
"revnotfound"	=> "Revisió no trobada",
"revnotfoundtext" => "No s'ha pogut trobar la revisió antiga de la pàgina.
Revidseu l'URL que heu emprat per accedir-hi.\n",
"loadhist"		=> "Recuperant la història de la pàgina",
"currentrev"	=> "Revisió actual",
"revisionasof"	=> "Revisió de $1",
"cur"			=> "act",
"next"			=> "seg",
"last"			=> "prev",
"orig"			=> "orig",
"histlegend"	=> "Simbologia: (act) = diferència amb la versió actual,
(prev) = diferència amb la versió prèvia, M = edició menor",

# Diffs
#
"difference"	=> "(Diferència entre revisions)",
"loadingrev"	=> "recuperant revisió per a diff",
"lineno"		=> "Línia $1:",
"editcurrent"	=> "Edita la versió actual d'aquesta pàgina",

# Search results
#
"searchresults" => "Resultats de la recerca",
"searchhelppage" => "Viquipèdia:Recerca",
"searchingwikipedia" => "S'està cercant a la Viquipèdia",
"searchresulttext" => "Per a més informació sobre les recerques de la Viquipèdia, aneu a $1.",
"searchquery"	=> "Per consulta \"$1\"",
"badquery"		=> "Consulta de recerca formulada de manera incorrecta",
"badquerytext"	=> "No s'ha pogut processar la recerca.
El motiu és probablement per què heu intentat cercar una paraula de menys de tres lletres, la qual cosa encara no és possible.
També pot ser que hàgiu comès un error en lletrejar el terme.
Torneu-ho a provar amb una altra recerca.",
"matchtotals"	=> "La consulta \"$1\" ha coincidit amb $2  títols d'articles
i el text de $3 articles.",
"titlematches"	=> "Coincidències de títol d'article",
"notitlematches" => "No hi ha coincidències de títol d'article",
"textmatches"	=> "Coincidències de text d'article",
"notextmatches"	=> "No hi ha coincidències de text d'article",
"prevn"			=> "$1 anteriors",
"nextn"			=> "$1 següents",
"viewprevnext"	=> "Vés a ($1) ($2) ($3).",
"showingresults" => "S'està mostrant a sota <b>$1</b> resultats començant per #<b>$2</b>.",
"nonefound"		=> "<strong>Nota</strong>: les recerques sense èxit són causades tot sovint
per recerques de paraules comunes com \"la\" o \"de\",
que no es troben a l'índex, o per especificar més d'una paraula a cercar( només pàgines
que contenen tots els termes d'una recerca apareixeran en el resultat).",
"powersearch" => "Recerca",
"powersearchtext" => "
Cerca en espais de nom :<br>
$1<br>
$2 Llista redireccions   Cerca $3 $9",

# Preferences page
#
"preferences"	=> "Preferències",
"prefsnologin" => "No heu entrat",
"prefsnologintext"	=> "Has haver <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">entrat</a>
per seleccionar preferències d'usuari.",
"prefslogintext" => "Heu entrat amb el nom \"$1\".
El vostre número d'identificació intern és $2.",
"prefsreset"	=> "Les preferències han estat respostes des d'emmagatzematge.",
"qbsettings"	=> "Preferències de \"Quickbar\"", 
"changepassword" => "Canvia contrasenya",
"skin"			=> "Aparença",
"math"			=> "Com es mostren les fòrmules",
"math_failure"		=> "No s'ha pogut entendre",
"math_unknown_error"	=> "error desconegut",
"math_unknown_function"	=> "funció desconeguda",
"math_lexing_error"	=> "error de lèxic",
"math_syntax_error"	=> "error de sintaxi",
"saveprefs"		=> "Desa preferències",
"resetprefs"	=> "Torna a preferències per defecte",
"oldpassword"	=> "Contrasenya antiga",
"newpassword"	=> "Contrasenya nova",
"retypenew"		=> "Reescriviu la nova contrasenya",
"textboxsize"	=> "Dimensions de la caixa de text",
"rows"			=> "Files",
"columns"		=> "Columnes",
"searchresultshead" => "Preferències de resultat de recerca",
"resultsperpage" => "Resultats a mostrar per pàgina",
"contextlines"	=> "Línies a mostrar per resultat",
"contextchars"	=> "Caràcters de context per línia",
"stubthreshold" => "Llindar d'article mínim" ,
"recentchangescount" => "Nombre de títols en canvis recents",
"savedprefs"	=> "Les vostres preferències han estat desades.",
"timezonetext"	=> "Entreu el número d'hores de diferència entre la vostra hora local
i l'hora del servidor (UTC).",
"localtime"	=> "Hora local",
"timezoneoffset" => "Diferència",
"emailflag"     => "No voleu rebre correu electrònic d'altres usuaris",

# Recent changes
#
"recentchanges" => "Canvis recents",
"recentchangestext" => "Seguiu els canvis més recentes de la Viquipèdia en aquesta pàgina.
[[Viquipèdia:Benvingut]]!
Mireu aquestes pàgines, si us plau: [[viquipèdia:PMF|PMF de la Viquipèdia]],
[[Viquipèdia:Polítiques i guies|polítiques de la Viquipèdia]]
(especialment [[viquipèdia:Convencions de noms|les convencions per anomenar articles]]i
[[viquipèdia:Punt de vista neutral|punt de vista neutral]]).

Si voleu que la Viquipèdia tingui èxit, és molt important que no hi afegiu
material restringit per [[viquipèdia:Copyrights|drets d'autor]].
La responsabilitat legal podria realment malmetre un projecte com aquest, així que si us plau, no ho feu.

Vegeu també [http://meta.wikipedia.org/wiki/Special:Recentchanges discussió recent en Meta (multilingüe)].",
"rcloaderr"		=> "s'està carregant els canvis recents",
"rcnote"		=> "A sota hi ha els últims <b>$1</b> canvis en els últims <b>$2</b> dies.",
"rclistfrom"	=> "Mostra els canvis nous des de $1",
"rcnotefrom"	=> "A sota hi ha els canvis des de <b>$2</b> (es mostren fins <b>$1</b>).",
"rclinks"		=> "Mostra els últims $1 canvis en els últims $2 dies.",
"rchide"		=> "en forma $4 ; $1 edicions menors; $2 espais de nom secundaris; $3 ediciones múltiples.",
"diff"			=> "diferències",
"hist"			=> "història",
"hide"			=> "amaga",
"show"			=> "mostra",
"tableform"     => "taula",
"listform"		=> "llistat",
"nchanges"		=> "$1 canvis",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Carrega",
"uploadbtn"		=> "Carrega un arxiu",

"uploadlink"	=> "Carrega imatges",
"reupload"		=> "Carrega de nou",
"reuploaddesc"	=> "Torna al formulari per pujar.",
"uploadnologin" => "No heu iniciat una sessió",
"uploadnologintext"	=> "Deveu haver <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">entrat</a>
per carregar arxius.",
"uploadfile"	=> "Carrega arxiu",
"uploaderror"	=> "S'ha produït un error en l'intent de carregar",
"uploadtext"	=> "Per veure o cercar imatges que s'hagin carregat
previament, aneu al <a href=\"" . wfLocalUrlE( "Especial:Imagelist" ) .
"\">llistat d'imatges carregades</a>.
Le càrregues i els esborrats són registrats en el <a href=\"" .
wfLocalUrlE( "Viquipèdia:Registre de càrregues" ) . "\">registre de càrregues</a>.
Aneu també a <a href=\"" . wfLocalUrlE( "Viquipèdia:Política d'ús d'imatges" ) .
"\">política d'ús d'imatges</a>.
<p>Empreu la forma de sota per carregar nous arxius d'imatges per il·lustrar els articles.
Amb la majoria dels navegadors, veureu un botó \"Browse...\", que
farà aparèixer la selecció d'arxius estàndard en el vostre sistema operatiu.
Quan escolliu un arxiu el nom d'aqueix arxiu apareixerà en el camp de text
al costat del botó.
També heu de marcar la caixa afirmant que no esteu
violant cap copyright en carregar l'arxiu.
Pitgeu el botó \"Carrega\" per completar la càrrega.
això pot prendre algun temps si teniu una connexió lenta a internet.
<p>Els formats preferits són el JPEG per imatges fotogràfiques, el PNG
per dibuixos i altres imatges icòniques, i OGG per sons.
Seria convenient que donéssiu noms descriptius als arxius per evitar confusions.
Per incloure la imatge en un article, empreu un enllaç de la forma
<b>[[imatge:arxiu.jpg]]</b> o <b>[[imatge:arxiu.png|alt text]]</b>
o <b>[[media:arxiu.ogg]]</b> per sons.
<p>Noteu que de la mateixa manera com passa amb les pàgines de la Viquipèdia, altri pot
editar o esborrar els arxius que heu carregat si pensen que és bo per a
l'enciclopèdia, i se us pot bloquejar, impedint-vos carregar arxius si abuseu del sistema.",
"uploadlog"		=> "registre de càrregues",
"uploadlogpage" => "Registre_de_càrregues",
"uploadlogpagetext" => "A sota hi ha un llistat dels arxius que s'han
carregat més recentement. Totes les hores són les del servidor (UTC).
<ul>
</ul>
",
"filename"		=> "Nom de l'arxiu",
"filedesc"		=> "Sumari",
"affirmation"	=> "Afirmo que el propietari del copyright d'aquest arxiu
està d'acord en llicenciar-lo sota els termes de $1.",
"copyrightpage" => "Viquipèdia:Copyrights",
"copyrightpagename" => "Viquipèdia copyright",
"uploadedfiles"	=> "Arxius carregats",
"noaffirmation" => "Heu d'afirmar que les carregues d'arxius no violen cap copyright.",
"ignorewarning"	=> "Ignora l'advertència i desa l'arxiu de totes maneres.",
"minlength"		=> "Els noms de les imatges han de tenir un mínim de tres lletres.",
"badfilename"	=> "El nom de la imatge s'ha canviat a \"$1\".",
"badfiletype"	=> "\".$1\" no és un format recomanat d'imatge.",
"largefile"		=> "Es recomana que les imatges no superin la mida de 100k.",
"successfulupload" => "L'arxiu s'ha carregat amb èxit",
"fileuploaded"	=> "L'arxiu \"$1\" s'ha carregat amb èxit.
Seguiu aquest enllaç si us plau: ($2) a la pàgina de descripció i empleneu
la informació necesàir sobre l'arxiu, tal com la procedència, la data de creació
i l'autor, i qualsevol altra cosa que pugueu saber al respecte.",
"uploadwarning" => "Advertència de càrrega d'arxiu",
"savefile"		=> "Desa arxiu",
"uploadedimage" => "\"$1\" carregat.",

# Image list
#
"imagelist"		=> "Llistat d'imatges",
"imagelisttext"	=> "A sota hi ha un llistat de $1 imatges ordenades $2.",
"getimagelist"	=> " obtenint el llistat d'imatges",
"ilshowmatch"	=> "Mostra totes les imatges amb noms que coincideixin amb",
"ilsubmit"		=> "Recerca",
"showlast"		=> "Mostra les últimes $1 imatges ordenades  $2.",
"all"			=> "totes",
"byname"		=> "per nom",
"bydate"		=> "per data",
"bysize"		=> "per mida",
"imgdelete"		=> "edi",
"imgdesc"		=> "desc",
"imglegend"		=> "Simbologia: (edi) = mostra/edita la descripció de la imatge.",
"imghistory"	=> "Història de la imatge",
"revertimg"		=> "rev",
"deleteimg"		=> "borr",
"deleteimgcompletely"		=> "borr",
"imghistlegend" => "Simbologia: (act) = aquesta és la imatge actual, (esb) = esborra
aquesta versió antiga, (rev) = reverteix a aquesta versió antiga.
<br><i>Cliqueu a la data per veure la imatge carregada en aquesta data</i>.",
"imagelinks"	=> "Enllaços a la imatge",
"linkstoimage"	=> "Les següents pàgines enllacen a aquesta imatge:",
"nolinkstoimage" => "No hi ha pàgines que enllacen aquesta imatge.",

# Statistics
#
"statistics"	=> "Estadístiques",
"sitestats"		=> "Estadístiques del lloc",
"userstats"		=> "Estadístiques d'usuari",
"sitestatstext" => "Hi ha un total de <b>$1</b> pàgines en la base de dades.
Això inclou pàgines de discussió, pàgines sobre la Viquipèdia, pàgines mínimes,
redireccions, i altres que probablement no es poden classificar com a articles.
Excloent-les, hi ha <b>$2</b> pàgines que probablement són articles legítims.<p>
Hi ha hagut un total de <b>$3</b> visites a pàgines, i <b>$4</b> edicions de pàgina
des que el programari ha estat actualitzat (Febrer 2003).
Això resulta en un promig de <b>$5</b> edicions per pàgina, 
i <b>$6</b> visites per edició.",
"userstatstext" => "Hi ha <b>$1</b> usuaris registrats.
dels quals <b>$2</b> són administradors (vegeu $3).",

# Maintenance Page
#
"maintenance"		=> "Pàgina de manteniment",
"maintnancepagetext"	=> "Aquesta pàgina inclou diverses eines útils per al manteniment diari. Algunes d'aquestes funcions tendeixen a 

sobrecarregar la base de dades, així que si us plau, no torneu a carregar la pàgina després de cada element que arregleu ;-)",
"maintenancebacklink"	=> "Torna a la pàgina de manteniment",
"disambiguations"	=> "Pàgines de desambiguació",
"disambiguationspage"	=> "Viquipèdia:Enllaços a pàgines de desambiguació",
"disambiguationstext"	=> "Els següents articles enllacen a una<i>pàgina de desambiguació</i>. Haurien d'enllaçar al tema apropiat.

<br>Una pàgina és considerada una pàgina de desambiguació si és enllaçada des de $1.<br>Enllaços des d'altres espais de nom (Com Viquipè

dia: o usuari:) <i>no</i> són  llistats ací.",
"doubleredirects"	=> "Redireccions Dobles",
"doubleredirectstext"	=> "<b>Atenció:</b> aquest llistat pot contenir falsos positius. Això normalment significa que hi ha text 

addicional amb enllaços sota el primer #REDIRECT.<br>\nCada fila conté enllaços al segon i tercer redireccionament, així com la primera línia del 

segon redireccionament, la qual cosa dóna normalment l'article \"real\", al que el primer redireccionamet hauria d'apuntar.",
"selflinks"		=> "Pàgines amb autoenllaços",
"selflinkstext"		=> "Les següents pàgines contenen un enllaç a si mateixes, la qual cosa no és recomanable.",
"mispeelings"       => "Pàgines amb faltes d'ortografia",
"mispeelingstext"               => "Les següents pàgines contenen una falta d'ortografia comuna, les quals s'han llistat a $1. L'escriptura correcta 

pot ser donada (com això).",
"mispeelingspage"       => "Llistat de faltes d'ortografia comunes",           
"missinglanguagelinks"  => "Enllaços Interidioma Faltants",
"missinglanguagelinksbutton"    => "Troba els enllaços interidioma que falten per",
"missinglanguagelinkstext"      => "Aquests articles <i>no</i> enllacen a les seves contraparts a $1. <i>No</i> es mostren redireccions i 

subpàgines.",


# Miscellaneous special pages
#
"orphans"		=> "Pàgines orfes",
"lonelypages"	=> "Pàgines orfes",
"unusedimages"	=> "Imatges sens ús",
"popularpages"	=> "Pàgines populars",
"nviews"		=> "$1 visites",
"wantedpages"	=> "Pàgines requerides",
"nlinks"		=> "$1 enllaços",
"allpages"		=> "Totes les pàgines",
"randompage"	=> "Pàgina aleatòria",
"shortpages"	=> "Pàgines curtes",
"longpages"		=> "Pàgines llargues",
"listusers"		=> "Llistat d'usuaris",
"specialpages"	=> "Pàgines especials",
"spheading"		=> "Pàgines especials",
"sysopspheading" => "Pàgines especials per ús de sysops",
"developerspheading" => "Pàgines especials per a l'ús dels desenvolupadors",
"protectpage"	=> "Pàgines protegides",
"recentchangeslinked" => "Seguiment d'enllaços",
"rclsub"		=> "(a pàgines enllaçades des de \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "Pàgines noves",
"movethispage"	=> "Trasllada aquesta pàgina",
"unusedimagestext" => "<p>Noteu que altres llocs web
com ara altres Viquipèdies poden enllaçar a una imatge
amb un URL directe, i d'aquixa manera estar encara llistada ací
tot i estar en ús actiu.",
"booksources"   => "Fonts de llibres",
"booksourcetext" => "A continuació hi ha un llistat d'enllaços a altres llocs que venen llibres nous i de segona mà, i també poden contenir 

informació addicional sobre els llibres que esteu cercant.
La Viquipèdia no està afiliada amb cap d'aquests negocis, i aquest llistat no ha de ser considerat com propaganda.",

# Email this user
#
"mailnologin"	=> "No enviïs la direcció",
"mailnologintext" => "Heu d'haver <a href=\"" .

  wfLocalUrl( "Especial:Userlogin" ) . "\">entrat</a>
i tenir una direcció electrònica vàlida en les vostres <a href=\"" .
  wfLocalUrl( "Especial:Preferences" ) . "\">preferències</a>
per enviar un correu electrònic a altres usuaris.",
"emailuser"		=> "Envia correu electrònic a aquest usuari",
"emailpage"		=> "Correu electrònic a usuari",
"emailpagetext"	=> "Si aquest usuari ha entrat una direcció electrònica vàlida en les vostres preferències d'usuari, el següent formulari 

serveix per enviar-li un missatge.
La direcció electrònica que heu entrat en les vostres preferències d'usuari apareixerà en el remitent, de manera que el destinatari pugui 

respondre.",
"noemailtitle"	=> "No hi ha cap direcció electrònica",
"noemailtext"	=> "Aquest usuari no ha especificat una direcció electrònica vàlida, o ha escollit no rebre correu electrònic d'altres usuaris

.",
"emailfrom"		=> "De",
"emailto"		=> "Per",
"emailsubject"	=> "Assumpte",
"emailmessage"	=> "Missatge",
"emailsend"		=> "Envia",
"emailsent"		=> "Correu electrònic enviat",
"emailsenttext" => "El vostre correu electrònic ha estat enviat.",

# Watchlist
#
"watchlist"		=> "Llistat de seguiment",
"watchlistsub"	=> "(per a l'usuari \"$1\")",
"nowatchlist"	=> "No teniu cap element en el vostre llistat de seguiment.",
"watchnologin"	=> "No heu iniciat sessió",
"watchnologintext"	=> "Heu d'<a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">entrar</a>
per modificar el vostre llistat de seguiment.",
"addedwatch"	=> "Afegit al llistat de seguiment",
"addedwatchtext" => "La pàgina \"$1\" ha estat afegida al vostre  <a href=\"" .
  wfLocalUrl( "Especial:Watchlist" ) . "\">llistat de seguiment</a>.
Canvis futurs a aquesta pàgina i a la vostra pàgina de discussió associada hi serà llistat, i la pàgina apareixerà <b>en negreta</b> al <a href=

\"" .
  wfLocalUrl( "Especial:Recentchanges" ) . "\">llistat de canvis recents</a> per fer-la notar més fàcilment.</p>

<p>Quan volgueu extreure la pàgina del vostre llistat de seguiment, pitgeu \"Deixa de vigilar\" a la barra del costat.",
"removedwatch"	=> "S'ha extret del llistat de seguiment",
"removedwatchtext" => "La pàgina \"$1\" ha estat extreta del vostre llistat de seguiment.",
"watchthispage"	=> "Vigila aquesta pàgina",
"unwatchthispage" => "Deixa de vigilar",
"notanarticle"	=> "No és un article",

# Delete/protect/revert
#
"deletepage"	=> "Esborra aquesta pàgina",
"confirm"		=> "Confirma",
"confirmdelete" => "Confirma l'esborrat",
"deletesub"		=> "(Esborrant \"$1\")",
"confirmdeletetext" => "Esteu a punt d'esborrar una pàgina o imatge 
de forma permanent, així com tota la seva història de la base de dades.
Confirmeu que realment ho voleu fer, que enteneu les
conseqüències, i que el esteu fent està d'acord amb [[Viquipèdia:Polítiques]].",
"confirmcheck"	=> "Sí, realment ho vull esborrar.",
"actioncomplete" => "Acció completa",
"deletedtext"	=> "\"$1\" ha estat esborrat.
Mostra $2 per a un registre dels esborrats més recents.",
"deletedarticle" => "esborrat \"$1\"",
"dellogpage"	=> "Registre_d'esborrats",
"dellogpagetext" => "A sota hi ha un llistat dels esborrats més recents.
Tots els temps es mostren en l'hora del servidor (UTC).
<ul>
</ul>
",
"deletionlog"	=> "Registre d'esborrats",
"reverted"		=> "Invertit amb una revisió anterior",
"deletecomment"	=> "Raó per ser esborrat",
"imagereverted" => "S'ha revertit amb èxit a una versió anterior.",
"rollback"		=> "Reverteix edicions",
"rollbacklink"	=> "Reverteix",
"cantrollback"	=> "No s'ha pogut revertir les edicions; l'últim col·laborador és l'únic autor d'aquest article.",
"revertpage"	=> "Revertida a l'última edició por $1",

# Undelete
"undelete" => "Restaura una pàgina esborrada",
"undeletepage" => "Mostra i restaura pàgines esborrades",
"undeletepagetext" => "Les següents pàgines han estat esborrades però encara són a l'arxiu i poden ser restaurades. L'arxiu pot ser netejat 

periòdicament.",
"undeletearticle" => "Restaura l'article esborrat",
"undeleterevisions" => "$1 revisions arxivades",
"undeletehistory" => "Si restaureu una pàgina, totes les revisions seran restaurades a la història.
Si una nova pàgina amb el mateix nom ha estat creada des de l'esborrat, les versions restaurades apareixeran com a història anterior, i la 

revisió actual del la pàgina \"viva\" no serà substituïda automàticament.",
"undeleterevision" => "Revisió esborrada al $1",
"undeletebtn" => "Restaura!",
"undeletedarticle" => "restaurat \"$1\"",
"undeletedtext"   => "L'article [[$1]] ha estat restaurat amb èxit.
Vegeu [[Viquipèdia:Registre_d'esborrats]] per un llistat d'esborrats i restauracions recents.",

# Contributions
#
"contributions"	=> "Contribucions de l'usuari",
"contribsub"	=> "Per $1",
"nocontribs"	=> "No s'ha trobat canvis que encaixessin amb aquests criteris.",
"ucnote"		=> "A sota hi ha els últims <b>$1</b> canvis d'aquest usuari en els últims <b>$2</b> dies.",
"uclinks"		=> "Mostra els últims $1 canvis; mostra els últims $2 dies.",
"uctop"		=> " (amunt)" ,

# What links here
#
"whatlinkshere"	=> "El que enllaça ací",
"notargettitle" => "No hi ha pàgina en blanc",
"notargettext"	=> "No heu especificat a quina pàgina dur a terme aquesta funció.",
"linklistsub"	=> "(Llistat d'enllaços)",
"linkshere"		=> "Les següents pàgines enllacen ací:",
"nolinkshere"	=> "Ací no enllaça cap pàgina.",
"isredirect"	=> "pàgina redirigida",

# Block/unblock IP
#
"blockip"		=> "Bloqueig de direccions IP",
"blockiptext"	=> "Empreu el següent formulari per bloquejar l'accés
d'escriptura des d'una direcció IP específica.
això hauria de fer-se només per prevenir el vandalisme, i
d'acord a la [[Viquipèdia:Política| política de la Viquipèdia]].
Empleneu el diàleg d'avall amb una raó específica (per exemple, citant
quines pàgines en concret estan sent vandalitzades).",
"ipaddress"		=> "Direcció IP",
"ipbreason"		=> "Raó",
"ipbsubmit"		=> "Bloqueja aquesta direcció",
"badipaddress"	=> "La direcció IP no té el format correcte.",
"noblockreason" => "Heu de donar un motiu per al bloqueig.",
"blockipsuccesssub" => "S'ha bloquejat amb èxit",
"blockipsuccesstext" => "La direcció IP  \"$1\" ha estat bloquejada.
<br>Vegeu [[Especial:llistat d'IP bloquejades|llistat d'IPs bloquejades]] per revisar bloquejos.",
"unblockip"		=> "Desbloqueja direcció IP",
"unblockiptext"	=> "Empreu el següent formulari per restaurar 
l'accés a l'escriptura a una direcció IP prèviament bloquejada.",
"ipusubmit"		=> "Desbloqueja aquesta direcció",
"ipusuccess"	=> "Direcció IP \"$1\" desbloquejada",
"ipblocklist"	=> "Llistat de direccions IP bloquejades",
"blocklistline"	=> "$1, $2 bloqueja $3",
"blocklink"		=> "bloqueja",
"unblocklink"	=> "desbloqueja",
"contribslink"	=> "contribucions",

# Developer tools
#
"lockdb"		=> "Bloqueja la base de dades",
"unlockdb"		=> "Desbloqueja la base de dades",
"lockdbtext"	=> "Bloquejant la base de dades s'anul·larà la capacitat de tots els
usuaris d'editar pàgines, canviar les preferències, editar els llistats de seguiments, i
altres canvis que requereixen canvis en la base de dades.
Confirmeu que això és el que intenteu fer, i sobretot no us oblideu
de desbloquejar la base de dades quan acabeu el manteniment.",
"unlockdbtext"	=> "Desbloquejant la base de dades es restaurarà l'habilitat de tots
els usuaris d'editar pàgines, canviar les preferències, editar els llistats de seguiment, i
altres accions que requereixen canvis en la base de dades.
Confirmeu que això és el que voleu fer.",
"lockconfirm"	=> "Sí, realment vull bloquejar la base de dades.",
"unlockconfirm"	=> "Sí, realment vull desbloquejar la base dades.",
"lockbtn"		=> "Bloqueja la base de dades",
"unlockbtn"		=> "Desbloqueja la base de dades",
"locknoconfirm" => "No heu respost al diàleg de confirmació.",
"lockdbsuccesssub" => "S'ha assolit el bloqueig de la base de dades",
"unlockdbsuccesssub" => "S'ha extret el bloqueig de la base de dades",
"lockdbsuccesstext" => "S'ha bloquejat la base de dades de la Viquipèdia.
<br>Recordeu-vos-en d'extreure el bloqueig havent acabant el manteniment.",
"unlockdbsuccesstext" => "La base de dades de la Viquipèdia ha estat desbloquejada.",

# SQL query
#
"asksql"		=> "Consulta SQL",
"asksqltext"	=> "Empreu el següent formulari per fer una consulta directa
a la base de dades de la Viquipèdia. Empreu les cometes simples ('como aquestes') per delimitar
cadenes de caràcters literals.
això pot afegir una càrrega considerable al servidor, així que
si us plau empreu aquesta funció el mínim possible.",
"sqlquery"		=> "Entreu la consulta",
"querybtn"		=> "Envia la consulta",
"selectonly"	=> "Consultes diferents a \"SELECT\" estan restringides només
als desenvolupadors de la Viquipèdia.",
"querysuccessful" => "Consulta amb èxit",

# Move page
#
"movepage"		=> "Reanomena pàgina",
"movepagetext"	=> "Emprant el següent formulari reanomenareu una pàgina,
movent tota la seva història al nou nom.
El títol anterior es convertirà en un redireccionament al nou títol.
Els enllaços a l'antic títol de la pàgina no es canviaran. Assegureu-vos-en de [[Especial:Maintenance|verificar]] que no deixeu redireccions 

dobles o trencades.
Sou el responsable de fer que els enllaços segueixin apuntant on se suposa que ho facin. 

Noteu que la pàgina '''no''' serà traslladada si ja existeix una pàgina amb el títol nou, a no ser que sigui una pàgina buida o un 

''redireccionament'' sense història. 
Això significa que podeu reanomenar de nou una pàgina al seu títol original si cometeu un error, i que no podeu sobreescriure una pàgina 

existent.

<b>ADVERTÈNCIA!</b>
Això pot ser un canvi dràstic i inesperat per una pàgina popular;
assegureu-vos-en d'entendre les conseqüències que comporta
abans de seguir endavant.",
"movepagetalktext" => "La pàgina de discussió associada, si existeix, serà traslladada automàticament '''a menys que:'''
*Esteu movent la pàgina entre espais de nom diferents,
*Una pàgina de discussió no buida ja existeix amb el nom nou, o
*Heu deseleccionat la caixa de sota.

En aquests casos, haureu de traslladar o barrejar la pàgina manualment si ho desitgeu.",
"movearticle"	=> "Reanomena pàgina",
"movenologin"	=> "No sou a dins d'una sessió",
"movenologintext" => "Heu de ser un usuari registrat i estar<a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">dintre d'una sessió</a>
per reanomenar una pàgina.",
"newtitle"		=> "A títol nou",
"movepagebtn"	=> "Reanomena pàgina",
"pagemovedsub"	=> "Reanomenament amb èxit",
"pagemovedtext" => "Pàgina \"[[$1]]\" reanomenada a \"[[$2]]\".",
"articleexists" => "Ja existeix una pàgina amb aquest nom, o el nom que heu
escollit no és vàlid.
Escolliu un altre nom, si us plau.",
"talkexists"	=> "S'ha reanomenat la pàgina amb èxit, però la pàgina de discussió no s'ha pogut moure car ja no existeix en el títol nou. 

Incorporeu-les manualment, si us plau.",
"movedto"		=> "reanomenat a",
"movetalk"	=> "Reanomena també la pàgina de discussió si és aplicable.",
"talkpagemoved" =>  "També ha estat reanomenada la pàgina de discussió corresponent.",
"talkpagenotmoved" => "La pàgina de discussió corresponent <strong>no</strong> ha estat reanomenada.",

);

require_once( "LanguageUtf8.php" );
class LanguageCa extends LanguageUtf8 {

	# Inherent default user options unless customization is desired

    function getBookstoreList () {
		global $wgBookstoreListCa ;
		return $wgBookstoreListCa ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesCa;
		return $wgNamespaceNamesCa;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesCa;
		return $wgNamespaceNamesCa[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesCa;

		foreach ( $wgNamespaceNamesCa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsCa;
		return $wgQuickbarSettingsCa;
	}

	function getSkinNames() {
		global $wgSkinNamesCa;
		return $wgSkinNamesCa;
	}

	function getMathNames() {
		global $wgMathNamesCa;
		return $wgMathNamesCa;
	}


	function getUserToggles() {
		global $wgUserTogglesCa;
		return $wgUserTogglesCa;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesCa;
		return $wgMonthNamesCa[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsCa;
		return $wgMonthAbbreviationsCa[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesCa;
		return $wgWeekdayNamesCa[$key-1];
	}

	# Inherit userAdjust()
        
	function shortdate( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " " .$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . ", " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " de " .$this->getMonthName( substr( $ts, 4, 2 ) ) . ", " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function time( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->time( $ts, $adj ) . " " . $this->shortdate( $ts, $adj );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesCa;
		return $wgValidSpecialPagesCa;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesCa;
		return $wgSysopSpecialPagesCa;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesCa;
		return $wgDeveloperSpecialPagesCa;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesCa;
		return $wgAllMessagesCa[$key];
	}
}

?>
