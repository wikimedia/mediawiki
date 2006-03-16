<?php


/* private */ $wgAllMessagesCa = array(
# User Toggles

"tog-underline" => "Subratlla enllaços",
"tog-highlightbroken" => "Destaca enllaços a temes buits",
"tog-justify"	=> "Ajusta paràgrafs",
"tog-hideminor" => "Amaga edicions menors en canvis recents",
"tog-usenewrc" => "Canvis recents remarcats (no per tots els navegadors)",
"tog-numberheadings" => "Autoenumera encapçalaments",
"tog-rememberpassword" => "Recorda la contrasenya entre sessions",
"tog-editwidth" => "La caixa d'edició té l'ample màxim",
"tog-editondblclick" => "Edita pàgines amb un doble clic (JavaScript)",
"tog-watchdefault" => "Vigila articles nous i modificats",
"tog-minordefault" => "Marca totes les edicions com menors per defecte",
#Dates

'sunday' => 'Diumenge',
'monday' => 'Dilluns',
'tuesday' => 'Dimarts',
'wednesday' => 'Dimecres',
'thursday' => 'Dijous',
'friday' => 'Divendres',
'saturday' => 'Dissabte',
'january' => 'gener',
'february' => 'febrer',
'march' => 'març',
'april' => 'abril',
'may_long' => 'maig',
'june' => 'juny',
'july' => 'juliol',
'august' => 'agost',
'september' => 'setembre',
'october' => 'octubre',
'november' => 'novembre',
'december' => 'desembre',
'jan' => 'gen',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'abr',
'may' => 'mai',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'ago',
'sep' => 'set',
'oct' => 'oct',
'nov' => 'nov',
'dec' => 'des',


# Bits of text used by many pages:
#
"linktrail"     => "/^([a-zàèéíòóúç·ïü']+)(.*)\$/sDu",
"mainpage"		=> "Portada",
"about"			=> "Quant a...",
"aboutsite"      => "Quant a la {{SITENAME}}",
"aboutpage"		=> "{{ns:4}}: Quant a",
"help"			=> "Ajuda",
"helppage"		=> "{{ns:4}}:Ajuda",
"bugreports"	=> "Informes d'error del programari",
"bugreportspage" => "{{ns:4}}:Informes_d'error",
"faq"			=> "PMF",
"faqpage"		=> "{{NS:4}}:PMF",
"edithelp"		=> "Ajuda d'edició",
"edithelppage"	=> "{{NS:4}}:Com_s'edita_una_pàgina",
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
"tagline"      	=> "De {{SITENAME}}",
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
"printsubtitle" => "(De {{SERVER}})",
"protectedpage" => "Pàgina protegida",
"administrators" => "{{ns:4}}:Administradors",
"sysoptitle"	=> "Accés requerit de Sysop",
"sysoptext"		=> "L'acció que heu requerit només pot ser duta a terme per usuaris amb estatus de \"sysop\".
Ver $1.",
"developertitle" => "Accés de desenvolupador requerit",
"developertext"	=> "L'acció que heu requerit només pot ser duta a terme per usuaris amb l'estatus de \"desenvolpador\".
Vegeu $1.",
"nbytes"		=> "$1 octets",
"go"			=> "Vés-hi",
"ok"			=> "D'acord",
"sitetitle"		=> "{{SITENAME}}",
"retrievedfrom" => "Obtingut de \"$1\"",

# Main script and global functions
#
"nosuchaction"	=> "Aquesta acció no existeix",
"nosuchactiontext" => "L'acció especificada per l'URL no és reconeguda pel programari de la {{SITENAME}}",
"nosuchspecialpage" => "No existeix aquesta pàgina especial",
"nospecialpagetext" => "Heu requerit una pàgina especial que no és reconeguda pel programari de la {{SITENAME}}.",

# General errors
#
"error"			=> "Error",
"databaseerror" => "Error de la base de dades",
"dberrortext"	=> "Ha ocorregut un error de sintaxi en una consulta a la base de dades.
L'última consulta que s'ha intentat ha estat:
<blockquote><tt>$1</tt></blockquote>L'error de retorn de MySQL ha estat\"<tt>$3: $4</tt>\".",
"noconnect"		=> "No s'ha pogut connectar a la base de dades a $1",
"nodb"			=> "No s'ha pogut seleccionar la base de dades$1",
"readonly"		=> "Base de dades bloquejada",
"enterlockreason" => "Entreu una raó per bloquejar-la, incloent una estimació de quan s'efecturarà el bloqueig",
"readonlytext"	=> "La base de dades de la {{SITENAME}} està temporalment bloquejada per noves entrades o altres modificacions,

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
Podeu continuar emprant la {{SITENAME}} de forma anònima, o podeu
iniciar sessió un altre cop amb el mateix o un altre nom d'usuari.",

"welcomecreation" => "<h2>Benvingut, $1!</h2><p>El vostre compte ha estat creat.
Recordeu personalitzar les vostres preferències del {{SITENAME}}.",

"loginpagetitle" => "Registre/Entrada",
"yourname"		=> "Nom d'usuari",
"yourpassword"	=> "Contrasenya",
"yourpasswordagain" => "Repetiu la contrasenya",
"newusersonly"	=> " (només usuaris nous)",
"remembermypassword" => "Vull que recordis la meva contrasenya entre sessions.",
"loginproblem"	=> "<b>Hi ha hagut un problema amb l'entrada.</b><br />Proveu-ho de nou!",
"alreadyloggedin" => "<strong>Benvingut, usuari $1!</strong><br />",

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
"loginsuccess"	=> "Heu iniciat la sessió a {{SITENAME}} com a \"$1\".",
"nosuchuser"	=> "No hi ha cap usuari amb el nom \"$1\".
Reviseu-ne l'ortografia, o empreu el formulari d'avall per crear un nou compte d'usuari.",
"wrongpassword"	=> "La contrasenya que heu ingressat és incorrecta. Torneu-ho a provar.",
"mailmypassword" => "Envia'm una nova contrasenya per correu electrònic",
"passwordremindertitle" => "Recordatori de contrasenya de la {{SITENAME}}",
"passwordremindertext" => "Algú (amb l'IP $1)
ha sol·licitat que li enviéssim una nova contrasenya per iniciar la sessió a la {{SITENAME}}.
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
La raó és la que segueix:<br />$2<p>Podeu contactar amb l'administrador per discutir el bloqueig.",
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
 \"Desa pàgina\".<br />",
"yourtext"		=> "El vostre text",
"storedversion" => "Versió emmagatzemada",
"editingold"	=> "<strong>ATENCIÓ:Esteu editant una versió antiga d'aquesta pàgina.
Si la graveu, els canvis fets des d'eixa revisió es perdran.</strong>",
"yourdiff"		=> "Diferències",
"copyrightwarning" => "Noteu que totes les contribucions a la {{SITENAME}}
es consideren fetes públiques sota la llicència de documentació lliure GNU
(mostra detalls a $1).
 Si no desitgeu que la gent corregeixi els vostres escrits sense pietat
i els distribueixi lliurement, llavors no els poseu ací. <br />
També ens heu d'assegurar que tot plegat és obra vostra i que sou l'amo dels drets d'autor, o els heu copiat des del domini públic
o una altra font lliura.
 <strong>NO EMPREU ESCRITS AMB COPYRIGHT SENSE PERMÍS!</strong>",

# History pages
#
"revhistory"	=> "Història de revisions",
"nohistory"		=> "No hi ha una història de revisions per a aquesta pàgina.",
"revnotfound"	=> "Revisió no trobada",
"revnotfoundtext" => "No s'ha pogut trobar la revisió antiga de la pàgina.
Revidseu l'URL que heu emprat per accedir-hi.",
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
"searchresulttext" => "Per a més informació sobre les recerques de la {{SITENAME}}, aneu a [[Project:Recerca|S'està cercant a la {{SITENAME}}]].",
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
Cerca en espais de nom :<br />
$1<br />
$2 Llista redireccions   Cerca $3 $9",

# Preferences page
#
"preferences"	=> "Preferències",
"prefsnologin" => "No heu entrat",
"prefsnologintext"	=> "Has haver [[Especial:Userlogin|entrat]]
per seleccionar preferències d'usuari.",
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

# Recent changes
#
"recentchanges" => "Canvis recents",
"recentchangestext" => "Seguiu els canvis més recentes de la {{SITENAME}} en aquesta pàgina.
[[{{ns:4}}:Benvingut]]!
Mireu aquestes pàgines, si us plau: [[{{ns:4}}:PMF|PMF de la {{SITENAME}}]],
[[{{ns:4}}:Polítiques i guies|polítiques de la {{SITENAME}}]]
(especialment [[{{ns:4}}:Convencions de noms|les convencions per anomenar articles]]i
[[{{ns:4}}:Punt de vista neutral|punt de vista neutral]]).

Si voleu que la {{SITENAME}} tingui èxit, és molt important que no hi afegiu
material restringit per [[{{ns:4}}:Copyrights|drets d'autor]].
La responsabilitat legal podria realment malmetre un projecte com aquest, així que si us plau, no ho feu.",
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
"uploadnologintext"	=> "Deveu haver [[Especial:Userlogin|entrat]]
per carregar arxius.",
"uploaderror"	=> "S'ha produït un error en l'intent de carregar",
"uploadtext"	=> "Per veure o cercar imatges que s'hagin carregat
previament, aneu al [[Especial:Imagelist|llistat d'imatges carregades]].
Le càrregues i els esborrats són registrats en el
[[Project:Registre de càrregues|registre de càrregues]].
Aneu també a [[Project:Política d'ús d'imatges|política d'ús d'imatges]].

Empreu la forma de sota per carregar nous arxius d'imatges per il·lustrar els articles.
Amb la majoria dels navegadors, veureu un botó \"Browse...\", que
farà aparèixer la selecció d'arxius estàndard en el vostre sistema operatiu.
Quan escolliu un arxiu el nom d'aqueix arxiu apareixerà en el camp de text
al costat del botó.
També heu de marcar la caixa afirmant que no esteu
violant cap copyright en carregar l'arxiu.
Pitgeu el botó \"Carrega\" per completar la càrrega.
això pot prendre algun temps si teniu una connexió lenta a internet.

Els formats preferits són el JPEG per imatges fotogràfiques, el PNG
per dibuixos i altres imatges icòniques, i OGG per sons.
Seria convenient que donéssiu noms descriptius als arxius per evitar confusions.
Per incloure la imatge en un article, empreu un enllaç de la forma
'''<nowiki>[[imatge:arxiu.jpg]]</nowiki> o
'''<nowiki>[[imatge:arxiu.png|alt text]]</nowiki> o
'''<nowiki>[[media:arxiu.ogg]]</nowiki>''' per sons.

Noteu que de la mateixa manera com passa amb les pàgines de la {{SITENAME}}, altri pot
editar o esborrar els arxius que heu carregat si pensen que és bo per a
l'enciclopèdia, i se us pot bloquejar, impedint-vos carregar arxius si abuseu del sistema.",
"uploadlog"		=> "registre de càrregues",
"uploadlogpage" => "Registre_de_càrregues",
"uploadlogpagetext" => "A sota hi ha un llistat dels arxius que s'han
carregat més recentement. Totes les hores són les del servidor (UTC).
<ul>
</ul>",
"filename"		=> "Nom de l'arxiu",
"filedesc"		=> "Sumari",
"copyrightpage" => "{{ns:4}}:Copyrights",
"copyrightpagename" => "{{ns:4}} copyright",
"uploadedfiles"	=> "Arxius carregats",
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
"uploadedimage" => "\"[[$1]]\" carregat.",

# Image list
#
"imagelist"		=> "Llistat d'imatges",
"imagelisttext"	=> "A sota hi ha un llistat de $1 imatges ordenades $2.",
"getimagelist"	=> " obtenint el llistat d'imatges",
"ilsubmit"		=> "Recerca",
"showlast"		=> "Mostra les últimes $1 imatges ordenades  $2.",
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
<br /><i>Cliqueu a la data per veure la imatge carregada en aquesta data</i>.",
"imagelinks"	=> "Enllaços a la imatge",
"linkstoimage"	=> "Les següents pàgines enllacen a aquesta imatge:",
"nolinkstoimage" => "No hi ha pàgines que enllacen aquesta imatge.",

# Statistics
#
"statistics"	=> "Estadístiques",
"sitestats"		=> "Estadístiques del lloc",
"userstats"		=> "Estadístiques d'usuari",
"sitestatstext" => "Hi ha un total de <b>$1</b> pàgines en la base de dades.
Això inclou pàgines de discussió, pàgines sobre la {{SITENAME}}, pàgines mínimes,
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
"disambiguationspage"	=> "{{ns:4}}:Enllaços a pàgines de desambiguació",
"disambiguationstext"	=> "Els següents articles enllacen a una<i>pàgina de desambiguació</i>. Haurien d'enllaçar al tema apropiat.

<br />Una pàgina és considerada una pàgina de desambiguació si és enllaçada des de $1.<br />Enllaços des d'altres espais de nom (Com Viquipè

dia: o usuari:) <i>no</i> són  llistats ací.",
"doubleredirects"	=> "Redireccions Dobles",
"doubleredirectstext"	=> "<b>Atenció:</b> aquest llistat pot contenir falsos positius. Això normalment significa que hi ha text

addicional amb enllaços sota el primer #REDIRECT.<br />\nCada fila conté enllaços al segon i tercer redireccionament, així com la primera línia del

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
La {{SITENAME}} no està afiliada amb cap d'aquests negocis, i aquest llistat no ha de ser considerat com propaganda.",

# Email this user
#
"mailnologin"	=> "No enviïs la direcció",
"mailnologintext" => "Heu d'haver [[Especial:Userlogin|entrat]]
i tenir una direcció electrònica vàlida en les vostres [[Especial:Preferences|preferències]]
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
"watchnologintext"	=> "Heu d'[[Especial:Userlogin|entrar]]
per modificar el vostre llistat de seguiment.",
"addedwatch"	=> "Afegit al llistat de seguiment",
"addedwatchtext" => "La pàgina \"$1\" ha estat afegida al vostre  <a href=\"" .
  "{{localurle:Especial:Watchlist}}\">llistat de seguiment</a>.
Canvis futurs a aquesta pàgina i a la vostra pàgina de discussió associada hi serà llistat, i la pàgina apareixerà <b>en negreta</b> al <a href=

\"" .
  "{{localurle:Especial:Recentchanges}}\">llistat de canvis recents</a> per fer-la notar més fàcilment.</p>

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
conseqüències, i que el esteu fent està d'acord amb [[{{ns:4}}:Polítiques]].",
"actioncomplete" => "Acció completa",
"deletedtext"	=> "\"$1\" ha estat esborrat.
Mostra $2 per a un registre dels esborrats més recents.",
"deletedarticle" => "esborrat \"$1\"",
"dellogpage"	=> "Registre_d'esborrats",
"dellogpagetext" => "A sota hi ha un llistat dels esborrats més recents.
Tots els temps es mostren en l'hora del servidor (UTC).
<ul>
</ul>",
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
"undeletedtext"   => "L'article [[:$1|$1]] ha estat restaurat amb èxit.
Vegeu [[{{ns:4}}:Registre_d'esborrats]] per un llistat d'esborrats i restauracions recents.",

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
d'acord a la [[{{ns:4}}:Política| política de la {{SITENAME}}]].
Empleneu el diàleg d'avall amb una raó específica (per exemple, citant
quines pàgines en concret estan sent vandalitzades).",
"ipaddress"		=> "Direcció IP",
"ipbreason"		=> "Raó",
"ipbsubmit"		=> "Bloqueja aquesta direcció",
"badipaddress"	=> "La direcció IP no té el format correcte.",
"blockipsuccesssub" => "S'ha bloquejat amb èxit",
"blockipsuccesstext" => "La direcció IP  \"$1\" ha estat bloquejada.
<br />Vegeu [[Especial:llistat d'IP bloquejades|llistat d'IPs bloquejades]] per revisar bloquejos.",
"unblockip"		=> "Desbloqueja direcció IP",
"unblockiptext"	=> "Empreu el següent formulari per restaurar
l'accés a l'escriptura a una direcció IP prèviament bloquejada.",
"ipusubmit"		=> "Desbloqueja aquesta direcció",
"ipusuccess"	=> "Direcció IP \"$1\" desbloquejada",
"ipblocklist"	=> "Llistat de direccions IP bloquejades",
"blocklistline"	=> "$1, $2 bloqueja $3 ($4)",
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
"lockdbsuccesstext" => "S'ha bloquejat la base de dades de la {{SITENAME}}.
<br />Recordeu-vos-en d'extreure el bloqueig havent acabant el manteniment.",
"unlockdbsuccesstext" => "La base de dades de la {{SITENAME}} ha estat desbloquejada.",

# Move page
#
"movepage"		=> "Reanomena pàgina",
"movepagetext"	=> "Emprant el següent formulari reanomenareu una pàgina,
movent tota la seva història al nou nom.
El títol anterior es convertirà en un redireccionament al nou títol.
Els enllaços a l'antic títol de la pàgina no es canviaran. Assegureu-vos-en de verificar que no deixeu redireccions

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
"movenologintext" => "Heu de ser un usuari registrat i estar[[Especial:Userlogin|dintre d'una sessió]]
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
#Math
'mw_math_png' => "Produeix sempre PNG",
'mw_math_simple' =>"HTML si és molt simple, si no PNG",
'mw_math_html' => "HTML si és possible, si no PNG",
'mw_math_source' => "Deixa com a TeX (per a navegadors de text)",

);


?>