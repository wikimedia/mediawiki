<?php

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesIa = array(
	-2	=> "Media",
	-1	=> "Special",
	0	=> "",
	1	=> "Discussion",
	2	=> "Usator",
	3	=> "Discussion_Usator",
	4	=> "Wikipedia",
	5	=> "Discussion_Wikipedia",
	6	=> "Imagine",
	7	=> "Discussion_Imagine",
	8	=> "MediaWiki",
	9	=> "Discussion_MediaWiki",
	10  => "Template",
	11  => "Template_talk"


) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsIa = array(
	"Necun", "Fixe a sinistra", "Fixe a dextera", "Flottante a sinistra"
);

/* private */ $wgSkinNamesIa = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Blau Colonia",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);



/* private */ $wgBookstoreListIa = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);


# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesIa = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Preferentias del usator",
	"Watchlist"		=> "Paginas sub observation",
	"Recentchanges" => "Modificationes recente",
	"Upload"		=> "Cargar imagines al servitor",
	"Imagelist"		=> "Lista de imagines",
	"Listusers"		=> "Usatores registerate",
	"Statistics"	=> "Statisticas de accesso",
	"Randompage"	=> "Articulo aleatori",

	"Lonelypages"	=> "Articulos orphanos",
	"Unusedimages"	=> "Imagines orphanas",
	"Popularpages"	=> "Articulos popular",
	"Wantedpages"	=> "Articulos plus demandate",
	"Shortpages"	=> "Articulos curte",
	"Longpages"		=> "Articulos longe",
	"Newpages"		=> "Articulos nove",
#	"Intl"                => "Interlanguage Links",
	"Allpages"		=> "Tote le paginas (per titulo)",

	"Ipblocklist"	=> "Adresses de IP blocate",
	"Maintenance" => "Pagina de mantenentia",
	"Specialpages"  => "", # "Paginas special",
	"Contributions" => "", # "Contributiones",
	"Emailuser"		=> "", # "Inviar email al usator(?)",
	"Whatlinkshere" => "", # "Referentias a iste pagina",
	"Recentchangeslinked" => "", # "Ultime modificationes (?)",
	"Movepage"		=> "", # "Mover pagina",
	"Booksources"	=> "Fontes externe (libros)",
#	"Categories"	=> "Page categories",
	"Export"		=> "Exportar in XML",
	"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesIa = array(
	"Blockip"		=> "Blocar un adresse de IP",
	"Asksql"		=> "Consultar base de datos",
	"Undelete"		=> "Vider e restaurar paginas eliminate"
);

/* private */ $wgDeveloperSpecialPagesIa = array(
	"Lockdb"		=> "Suspender modificationes",
	"Unlockdb"		=> "Permitter modificationes",
);

/* private */ $wgAllMessagesIa = array(
'special_version_prefix' => '',
'special_version_postfix' => '',

# User Toggles
#

"tog-hover"		=> "Evidentiar wikiligamines sub le ratto",
"tog-underline" => "Sublinear ligamines",
"tog-highlightbroken" => "Formatar ligamines rupte <a href=\"\" class=\"new\">assi</a> (alternativemente: assi<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"	=> "Justificar paragraphos",
"tog-hideminor" => "Occultar modificationes recente minor",
"tog-usenewrc" => "Modificationes recente meliorate (non functiona in tote le navigatores)",
"tog-numberheadings" => "Numerar titulos automaticamente",
"tog-showtoolbar" => "Show edit toolbar",
"tog-rememberpassword" => "Recordar contrasigno inter sessiones (usa cookies)",
"tog-editwidth" => "Cassa de redaction occupa tote le largor del fenestra",
"tog-editondblclick" => "Duple clic pro modificar un pagina (usa JavaScript)",
"tog-watchdefault" => "Poner articulos nove e modificate sub observation",
"tog-minordefault" => "Marcar modificationes initialmente como minor",
"tog-previewontop" => "Monstrar previsualisation ante le cassa de edition e non post illo",

# dates
#

'sunday' => 'dominica',
'monday' => 'lunedi',
'tuesday' => 'martedi',
'wednesday' => 'mercuridi',
'thursday' => 'jovedi',
'friday' => 'venerdi',
'saturday' => 'sabbato',
'january' => 'januario',
'february' => 'februario',
'march' => 'martio',
'april' => 'april',
'may_long' => 'maio',
'june' => 'junio',
'july' => 'julio',
'august' => 'augusto',
'september' => 'septembre',
'october' => 'octobre',
'november' => 'novembre',
'december' => 'decembre',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'mai',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'oct',
'nov' => 'nov',
'dec' => 'dec',


# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Frontispicio",
"about"			=> "A proposito",
"aboutsite"      => "A proposito de Wikipedia",
"aboutpage"		=> "Wikipedia:A_proposito",
"help"			=> "Adjuta",
"helppage"		=> "Wikipedia:Adjuta",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "Reportos de disfunctiones",
"bugreportspage" => "Wikipedia:Reportos_de_disfunctiones",
"faq"			=> "Questiones frequente",
"faqpage"		=> "Wikipedia:Questiones_frequente",
"edithelp"		=> "Adjuta al edition",
"edithelppage"	=> "Wikipedia:Como_editar_un_pagina",
"cancel"		=> "Cancellar",
"qbfind"		=> "Trovar",
"qbbrowse"		=> "Foliar",
"qbedit"		=> "Modificar",
"qbpageoptions" => "Optiones de pagina",
"qbpageinfo"	=> "Info del pagina",
"qbmyoptions"	=> "Mi optiones",
"mypage"		=> "Mi pagina",
"mytalk"		=> "Mi discussion",
"currentevents" => "Actualitates",
"errorpagetitle" => "Error",
"returnto"		=> "Retornar a $1.",
"tagline"      	=> "De Wikipedia, le encyclopedia libere.",
"whatlinkshere"	=> "Referentias a iste pagina",
"help"			=> "Adjuta",
"search"		=> "Recercar",
"go"		=> "Ir",
"history"		=> "Chronologia",
"printableversion" => "Version imprimibile",
"editthispage"	=> "Modificar iste pagina",
"deletethispage" => "Eliminar iste pagina",
"protectthispage" => "Proteger iste pagina",
"unprotectthispage" => "Disproteger iste pagina",
"newpage" => "Nove pagina",
"talkpage"		=> "Discuter iste pagina",
"articlepage"	=> "Vider article",
"subjectpage"	=> "Vider subjecto", # For compatibility
"userpage" => "Vider pagina del usator",
"wikipediapage" => "Vider metapagina",
"imagepage" => 	"Vider pagina de imagine",
"viewtalkpage" => "Vider discussion",
"otherlanguages" => "Altere linguas",
"redirectedfrom" => "(Redirigite de $1)",
"lastmodified"	=> "Ultime modification: $1.",
"viewcount"		=> "Iste pagina esseva accessate $1 vices.",
"gnunote" => "Tote le texto es disponibile sub le terminos del <a class=internal href='$wgScriptPath/GNU_FDL'>Licentia de documentation libere GNU</a>.",
"printsubtitle" => "(De http://ia.wikipedia.org)",
"protectedpage" => "Pagina protegite",
"administrators" => "Wikipedia:Administratores",
"sysoptitle"	=> "Reservate a Sysops",
"sysoptext"		=> "Solo usatores con status de \"sysop\"
pote effectuar iste action.
Vide $1.",
"developertitle" => "Reservate a disveloppatores",
"developertext"	=> "Solo usatores con status de \"developer\"
pote effectuar iste action.
Vide $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Ir",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "Le encyclopedia libere",
"retrievedfrom" => "Recuperate de \"$1\"",
"newmessages" => "Tu ha $1.",
"newmessageslink" => "messages nove",

# Main script and global functions
#
"nosuchaction"	=> "Action inexistente",
"nosuchactiontext" => "Le action specificate in le URL non es
recognoscite per le systema de Wikipedia.",
"nosuchspecialpage" => "Pagina special inexistente",
"nospecialpagetext" => "
Tu demandava un pagina special que non es
recognoscite per le systema de Wikipedia.",

# General errors
#
"error"			=> "Error",
"databaseerror" => "Error de base de datos",
"dberrortext"	=> "Occurreva un error de syntaxe in le consulta al base de datos.
Le ultime demanda inviate al base de datos esseva:
<blockquote><tt>$1</tt></blockquote>
de intra le function \"<tt>$2</tt>\".
MySQL retornava le error \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Impossibile connecter al base de datos a $1",
"nodb"			=> "Impossibile selectionar base de datos $1",
"readonly"		=> "Base de datos blocate",
"enterlockreason" => "Describe le motivo del blocage, includente un estimation
de quando illo essera terminate",
"readonlytext"	=> "Actualmente le base de datos de Wikipedia es blocate pro nove
entratas e altere modificationes, probabilemente pro mantenentia
routinari del base de datos, post le qual illo retornara al normal.
Le administrator responsabile dava iste explication:
<p>$1",
"missingarticle" => "Le base de datos non trovava le texto de un pagina
que illo deberea haber trovate, a saper \"$1\".
Isto non es un error de base de datos, mais probabilemente
un disfunction in le systema.
Per favor reporta iste occurrentia a un administrator,
indicante le URL.",
"internalerror" => "Error interne",
"filecopyerror" => "Impossibile copiar file \"$1\" a \"$2\".",
"filerenameerror" => "Impossibile renominar file \"$1\" a \"$2\".",
"filedeleteerror" => "Impossibile eliminar file \"$1\".",
"filenotfound"	=> "Impossibile trovar file \"$1\".",
"unexpected"	=> "Valor impreviste: \"$1\"=\"$2\".",
"formerror"		=> "Error: impossibile submitter formulario",	
"badarticleerror" => "Iste action non pote esser effectuate super iste pagina.",
"cannotdelete"	=> "Impossibile eliminar le pagina o imagine specificate. (Illo pote ja haber essite eliminate per un altere persona.)",
"badtitle"		=> "Titulo incorrecte",
"badtitletext"	=> "Le titulo de pagina demandate esseva invalide, vacue, o
un titulo interlinguistic o interwiki incorrectemente ligate.",
"perfdisabled" => "Pardono! Iste functionalitate es temporarimente inactivate durante
horas de grande affluentia de accessos pro motivo de performance;
retorna inter 02:00 e 14:00 UTC e tenta de nove.",

# Login and logout pages
#
"logouttitle"	=> "Fin de session",
"logouttext"	=> "Tu claudeva tu session.
Tu pote continuar a usar Wikipedia anonymemente, o initiar un
nove session como le mesme o como un altere usator.\n",

"welcomecreation" => "<h2>Benvenite, $1!</h2>
<p>Tu conto de usator esseva create.
Non oblida personalisar Wikipedia secundo tu preferentias.",

"loginpagetitle" => "Aperir session",
"yourname"		=> "Tu nomine de usator",
"yourpassword"	=> "Tu contrasigno",
"yourpasswordagain" => "Confirmar contrasigno",
"newusersonly"	=> " (solmente pro nove usatores)",
"remembermypassword" => "Recordar contrasigno inter sessiones.",
"loginproblem"	=> "<b>Occurreva problemas pro initiar tu session.</b><br>Tenta de nove!",
"alreadyloggedin" => "<font color=red><b>Usator $1, tu session ja es aperte!</b></font><br>\n",

"login"			=> "Aperir session",
"userlogin"		=> "Aperir session",
"logout"		=> "Clauder session",
"userlogout"	=> "Clauder session",
"createaccount"	=> "Crear nove conto",
"badretype"		=> "Le duo contrasignos que tu scribeva non coincide.",
"userexists"	=> "Le nomine de usator que tu selectionava ja es in uso. Per favor selectiona un nomine differente.",
"youremail"		=> "Tu e-mail",
"yournick"		=> "Tu pseudonymo (pro signaturas)",
"emailforlost"	=> "Si tu oblida tu contrasigno, tu pote demandar un nove contrasigno via e-mail.",
"loginerror"	=> "Error in le apertura del session",
"noname"		=> "Tu non specificava un nomine de usator valide.",
"loginsuccesstitle" => "Session aperte con successo",
"loginsuccess"	=> "Tu es identificate in Wikipedia como \"$1\".",
"nosuchuser"	=> "Non existe usator registrate con le nomine \"$1\".
Verifica le orthographia, o usa le formulario infra pro crear un nove conto de usator.",
"wrongpassword"	=> "Le contrasigno que tu scribeva es incorrecte. Per favor tenta de nove.",
"mailmypassword" => "Demandar un nove contrasigno via e-mail",
"passwordremindertitle" => "Nove contrasigno in Wikipedia",
"passwordremindertext" => "Alcuno (probabilemente tu, con adresse de IP $1)

demandava inviar te un nove contrasigno pro Wikipedia.
Le contrasigno pro le usator \"$2\" ora es \"$3\".
Nos consilia que tu initia un session e cambia le contrasigno le plus tosto possibile.",
"noemail"		=> "Non existe adresse de e-mail registrate pro le usator \"$1\".",
"passwordsent"	=> "Un nove contrasigno esseva inviate al adresse de e-mail
registrate pro \"$1\".
Per favor initia un session post reciper lo.",

# Edit pages
#
"summary"		=> "Summario",
"minoredit"		=> "Iste es un modification minor",
"watchthis"		=> "Poner iste articulo sub observation",
"savearticle"	=> "Salvar articulo",
"preview"		=> "Previsualisar",
"showpreview"	=> "Monstrar previsualisation",
"blockedtitle"	=> "Le usator es blocate",
"blockedtext"	=> "Tu nomine de usator o adresse de IP ha essite blocate per $1.
Le motivo presentate es iste:<br>''$2''<p>Tu pote contactar $1 o un del altere
[[Wikipedia:administratores|administratores]] pro discuter le bloco.",
"newarticle"	=> "(Nove)",
"newarticletext" =>
"Tu ha sequite un ligamine a un pagina que ancora non existe.
Pro crear un nove pagina, comencia a scriber in le cassa infra.
(Vide le [[Wikipedia:Adjuta|pagina de adjuta]] pro plus information.)
Si tu es hic per error, simplemente clicca le button '''Retornar''' de tu navigator.",
"anontalkpagetext" => "---- ''Iste es le pagina de discussion pro un usator anonyme qui ancora non ha create un conto o qui non lo usa. Consequentemente nos debe usar le [[adresse de IP]] numeric pro identificar le/la. Un tal adresse de IP pote esser usate in commun per varie personas. Si tu es un usator anonyme e senti que commentarios irrelevante ha essite dirigite a te, per favor [[Special:Userlogin|crea un conto o aperi un session]] pro evitar futur confusiones con altere usatores anonyme.'' ",
"noarticletext" => "(Actualmente il non ha texto in iste pagina)",
"updated"		=> "(Actualisate)",
"note"			=> "<strong>Nota:</strong> ",
"previewnote"	=> "Rememora te que isto es solmente un previsualisation, tu modificationes ancora non ha essite salvate!",
"previewconflict" => "Iste previsualisation reflecte le apparentia final del texto in le area de redaction superior
si tu opta pro salvar lo.",
"editing"		=> "Modification de $1",
"editconflict"	=> "Conflicto de edition: $1",
"explainconflict" => "Alcuno ha modificate iste pagina post que tu
ha comenciate a modificar lo.
Le area de texto superior contine le texto del pagina tal como illo existe actualmente.
Tu modificationes es monstrate in le area de texto inferior.
Tu debera incorporar tu modificationes al texto existente.
<b>Solmente</b> le texto del area superior essera salvate
quando tu premera \"Salvar pagina\".\n</p>",
"yourtext"		=> "Tu texto",
"storedversion" => "Version immagazinate",
"editingold"	=> "<strong>ADVERTIMENTO: In iste momento tu modifica
un version obsolete de iste pagina.
Si tu lo salvara, tote le modificationes facite post iste revision essera perdite.</strong>\n",
"yourdiff"		=> "Differentias",
"copyrightwarning" => "Nota que tote le contributiones a Wikipedia es
considerate public secundo le terminos del Licentia de Documentation Libere GNU
(vide plus detalios in $1).
Si tu non vole que tu scripto sia modificate impietosemente e redistribuite
a voluntate, alora non lo edita hic.<br>
Additionalmente, tu nos garanti que tu es le autor de tu contributiones,
o que tu los ha copiate de un ressource libere de derectos.
<strong>NON USA MATERIAL COPERITE PER DERECTOS DE AUTOR (COPYRIGHT) SIN AUTORISATION EXPRESSE!</strong>",
"longpagewarning" => "ADVERTIMENTO: Iste pagina ha $1 kilobytes de longitude;
alcun navigatores pote presentar problemas in editar
paginas de approximatemente o plus de 32kb.
Considera fragmentar le pagina in sectiones minor.",

# History pages
#
"revhistory"	=> "Chronologia de versiones",
"nohistory"		=> "Iste pagina non ha versiones precedente.",
"revnotfound"	=> "Revision non trovate",
"revnotfoundtext" => "Impossibile trovar le version anterior del pagina que tu ha demandate.
Verifica le URL que tu ha usate pro accessar iste pagina.\n",
"loadhist"		=> "Carga del chronologia del pagina",
"currentrev"	=> "Revision currente",
"revisionasof"	=> "Revision de $1",
"cur"			=> "actu",
"next"			=> "sequ",
"last"			=> "prec",
"orig"			=> "orig",
"histlegend"	=> "Legenda: (actu) = differentia del version actual,
(prec) = differentia con le version precedente, M = modification minor",

# Diffs
#
"difference"	=> "(Differentia inter revisiones)",
"loadingrev"	=> "carga del revision pro diff",
"lineno"		=> "Linea $1:",
"editcurrent"	=> "Modificar le version actual de iste pagina",

# Search results
#
"searchresults" => "Resultatos del recerca",
"searchresulttext" => "Pro plus information super le recerca de {{SITENAME}}, vide [[Project:Recerca|Recerca in {{SITENAME}}]].",
"searchquery"	=> "Pro le consulta \"$1\"",
"badquery"		=> "Consulta de recerca mal formate",
"badquerytext"	=> "Impossibile processar tu consulta.
Probabilemente tu ha tentate recercar un parola con minus
de tres litteras de longitude, situation que le systema non
permitte. Es equalmente possibile que tu ha committite un
error syntactic in le consulta, per exemplo,
\"pisce and and squama\".
Reformula tu consulta.",
"matchtotals"	=> "Le consulta \"$1\" coincide con le titulos de $2 articulos
e le texto de $3 articulos.",
"nogomatch" => "Non existe un pagina con iste titulo exacte, io recurre al recerca de texto integral. ",
"titlematches"	=> "Coincidentias con titulos de articulos",
"notitlematches" => "Necun coincidentia",
"textmatches"	=> "Coincidentias con textos de articulos",
"notextmatches"	=> "Necun coincidentia",
"prevn"			=> "$1 precedentes",
"nextn"			=> "$1 sequentes",
"viewprevnext"	=> "Vider ($1) ($2) ($3).",
"showingresults" => "Monstra de <b>$1</b> resultatos a partir de nº <b>$2</b>.",
"nonefound"		=> "<strong>Nota</strong>: recercas frustrate frequentemente
es causate per le inclusion de vocabulos commun como \"que\" e \"illo\",
que non es includite in le indice, o per le specification de plure
terminos de recerca (solmente le paginas que contine tote le terminos
de recerca apparera in le resultato).",
"powersearch" => "Recercar",
"powersearchtext" => "
Recerca in contextos :<br>
$1<br>
$2 Listar redireciones &nbsp; Recercar pro $3 $9",


# Preferences page
#
"preferences"	=> "Preferentias",
"prefsnologin" => "Session non aperte",
"prefsnologintext"	=> "Tu debe <a href=\"" .
  "{{localurle:Special:Userlogin}}\">aperir un session</a>
pro definir tu preferentias.",
"prefslogintext" => "Tu es identificate como \"$1\".
Tu numero interne de ID es $2.",
"prefsreset"	=> "Tu preferentias salvate previemente ha essite restaurate.",
"qbsettings"	=> "Configuration del barra de utensiles", 
"changepassword" => "Cambiar contrasigno",
"skin"			=> "Apparentia",
"math"			=> "Exhibition de formulas",
"math_failure"		=> "Impossibile analysar",
"math_unknown_error"	=> "error incognite",
"math_unknown_function"	=> "function incognite ",
"math_lexing_error"	=> "error lexic",
"math_syntax_error"	=> "error syntactic",
"saveprefs"		=> "Salvar preferentias",
"resetprefs"	=> "Restaurar preferentias",
"oldpassword"	=> "Contrasigno actual",
"newpassword"	=> "Nove contrasigno",
"retypenew"		=> "Confirmar nove contrasigno",
"textboxsize"	=> "Dimensiones del cassa de texto",
"rows"			=> "Lineas",
"columns"		=> "Columnas",
"searchresultshead" => "Configuration del resultatos de recerca",
"resultsperpage" => "Coincidentias per pagina",
"contextlines"	=> "Lineas per coincidentia",
"contextchars"	=> "Characteres de contexto per linea",
"stubthreshold" => "Limite pro exhibition residual",
"recentchangescount" => "Quantitate de titulos in modificationes recente",
"savedprefs"	=> "Tu preferentias ha essite salvate.",
"timezonetext"	=> "Scribe le differentia de horas inter tu fuso horari
e illo del servitor (UTC).",
"localtime"	=> "Hora local",
"timezoneoffset" => "Differentia de fuso horari",
"emailflag"		=> "Non reciper e-mail de altere usatores",

# Recent changes
#
"changes" => "modificationes",
"recentchanges" => "Modificationes recente",
"recentchangestext" => "Seque le plus recente modificationes a Wikipedia in iste pagina.
[[Wikipedia:Benvenite,_novicios|Benvenite, novicios]]!
Per favor lege equalmente iste paginas: [[wikipedia:Questiones_frequente|Questiones frequente super Wikipedia]],
[[Wikipedia:Politicas e directivas|Politica de Wikipedia]]
(specialmente [[wikipedia:Conventiones de nomenclatura|conventiones de nomenclatura]],
[[wikipedia:Neutralitate e objectivitate|neutralitate e objectivitate]]),
e [[wikipedia:Le passos false plus commun|le passos false plus commun]].

Si tu vole que Wikipedia habe successo, es multo importante que tu non
include material protegite per [[wikipedia:Copyright|derectos de autor]].
Le aspectos legal connexe poterea prejudicar gravemente le projecto,
alora per favor non lo face.
In ultra, lege iste [http://meta.wikipedia.org/wiki/Special:Recentchanges recente discussion] in meta.Wikipedia.",
"rcloaderr"		=> "Carga del modificationes recente",
"rcnote"		=> "Infra es le <strong>$1</strong> ultime modificationes in le <strong>$2</strong> ultime dies.",
"rcnotefrom"	=> "infra es le modificationes a partir de <b>$2</b> (usque a <b>$1</b>).",
"rclistfrom"	=> "Monstrar nove modificationes a partir de $1",
# "rclinks"		=> "Monstrar le $1 ultime modificationes in le $2 ultime horas / $3 ultime dias",
"rclinks"		=> "Monstrar le $1 ultime modificationes in le $2 ultime days.",
"rchide"		=> "in forma de $4; $1 modificationes minor; $2 contextos secundari; $3 modificationes multiple.",
"diff"			=> "diff",
"hist"			=> "prec",
"hide"			=> "occultar",
"show"			=> "monstrar",
"tableform"		=> "tabella",
"listform"		=> "lista",
"nchanges"		=> "$1 modificationes",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Cargar file",
"uploadbtn"		=> "Cargar file",
"uploadlink"	=> "Cargar imagines",
"reupload"		=> "Recargar",
"reuploaddesc"	=> "Retornar al formulario de carga.",
"uploadnologin" => "Session non aperte",
"uploadnologintext"	=> "Tu debe <a href=\"" .
  "{{localurle:Special:Userlogin}}\">aperir un session</a>
pro poter cargar files.",
"uploadfile"	=> "Cargar file",
"uploaderror"	=> "Error de carga",
"uploadtext"	=> "'''STOP!''' Ante cargar files al servitor,
prende cognoscentia del
[[Project:Image_use_policy|politica de Wikipedia super le uso de imagines]],
e assecura te de respectar lo.

Pro vider o recercar imagines cargate previemente,
vade al [[Special:Imagelist|lista de imagines cargate]].
Cargas e eliminationes es registrate in le
[[Project:Upload_log|registro de cargas]].

Usa le formulario infra pro cargar nove files de imagine pro
illustrar tu articulos.
In le major parte del navigatores, tu videra un button \"Browse...\",
que facera apparer le cassa de dialogo de apertura de files
standard de tu systema de operation. Selectiona un file pro
inserer su nomine in le campo de texto adjacente al button.
Tu debe additionalmente marcar le quadrato con le qual tu
declara que tu non viola derectos de autor per medio del carga
del file.
Preme le button \"Cargar\" pro initiar le transmission.
Le carga pote prender alcun tempore si tu connexion al Internet
es lente.

Le formatos preferite es JPEG pro imagines photographic,
PNG pro designos e altere imagines iconic, e OGG pro sonos.
Per favor, attribue nomines descriptive a tu files pro evitar
confusion.
Pro includer le imagine in un articulo, usa un ligamine in
le forma '''<nowiki>[[image:file.jpg]]</nowiki>''' o
'''<nowiki>[[image:file.png|texto alternative]]</nowiki>''' o
'''<nowiki>[[media:file.ogg]]</nowiki>''' pro sonos.

Nota que, justo como occurre con le paginas de Wikipedia, alteros
pote modificar o eliminar le files cargate si illes considera que
isto beneficia le encyclopedia, e tu pote haber tu derecto
de carga blocate si tu abusa del systema.",
"uploadlog"		=> "registro de cargas",
"uploadlogpage" => "Registro_de_cargas",
"uploadlogpagetext" => "Infra es un lista del plus recente cargas de files.
Tote le tempores monstrate es in le fuso horari del servitor (UCT).
<ul>
</ul>
",
"filename"		=> "Nomine del file",
"filedesc"		=> "Description",
"affirmation"	=> "Io declara que le detentor del derecto de autor de iste file
consenti in licentiar lo sub le terminos del $1.",
"copyrightpage" => "Wikipedia:Copyright",
"copyrightpagename" => "Wikipedia e derectos de autor (copyright)",
"uploadedfiles"	=> "Files cargate",
"noaffirmation" => "Tu debe declarar que le files cargate per te non viola
derectos de autor (copyrights).",
"ignorewarning"	=> "Ignorar advertimentos e salvar le file totevia.",
"minlength"		=> "Le nomines de imagines debe haber al minus tres litteras.",
"badfilename"	=> "Le nomine del imagine esseva cambiate a \"$1\".",
"badfiletype"	=> "\".$1\" non es un formato de file de imagine recommendate.",
"largefile"		=> "Es recommendabile que le imagines non excede 100kb.",
"successfulupload" => "Carga complete",
"fileuploaded"	=> "File \"$1\" cargate sin problemas.
Per favor clicca hic: ($2) pro accessar le pagina de description
e fornir information super le file, tal como su origine,
quando illo esseva create e per qui, e toto plus que tu sape
a su proposito.",
"uploadwarning" => "Advertimento de carga",
"savefile"		=> "Salvar file",
"uploadedimage" => "\"$1\" cargate",

# Image list
#
"imagelist"		=> "Lista de imagines",
"imagelisttext"	=> "Infra es un lista de $1 imagines ordinate $2.",
"getimagelist"	=> "recuperation del lista de imagines",
"ilshowmatch"	=> "Monstrar tote le imagines cuje nomine coincide con",
"ilsubmit"		=> "Recercar",
"showlast"		=> "Monstrar le ultime $1 imagines ordinate $2.",
"all"			=> "totes",
"byname"		=> "per nomine",
"bydate"		=> "per data",
"bysize"		=> "per dimension",
"imgdelete"		=> "elim",
"imgdesc"		=> "desc",
"imglegend"		=> "Legenda: (desc) = monstrar/modificar description del imagine.",
"imghistory"	=> "Chronologia del imagine",
"revertimg"		=> "rev",
"deleteimg"		=> "elim",
"deleteimgcompletely"		=> "elim",
"imghistlegend" => "Legend: (actu) = iste es le imagine actual, (elim) = elimina
iste version antique, (rev) = reverte a iste version antique.
<br><i>Clica super le data pro vider le imagine cargate in ille die.</i>",
"imagelinks"	=> "Ligamines al imagine",
"linkstoimage"	=> "Le paginas sequente se liga a iste imagine:",
"nolinkstoimage" => "Necun pagina se liga a iste imagine.",

# Statistics
#
"statistics"	=> "Statisticas",
"sitestats"		=> "Statisticas de accesso",
"userstats"		=> "Statisticas de usator",
"sitestatstext" => "Le base de datos contine un total de <b>$1</b> paginas.
Iste numero include paginas de \"discussion\", paginas super Wikipedia, paginas de \"residuo\"
minime, paginas de redirection, e alteres que probabilemente non se qualifica como articulos.
A parte de istes, il ha <b>$2</b> paginas que probabilemente es
articulos legitime.<p>
Il habeva un total de <b>$3</b> visitas a paginas, e <b>$4</b> modificationes de paginas
desde le actualisation del systema (20 de julio 2002).
Isto representa un media de <b>$5</b> modificationes per pagina, e <b>$6</b> visitas per modification.",
"userstatstext" => "Il ha <b>$1</b> usatores registrate,
del quales <b>$2</b> es administratores (vide $3).",

# Maintenance Page
#
"maintenance"		=> "Pagina de mantenentia",
"maintnancepagetext"	=> "Iste pagina include plure utensiles commode pro le mantenentia quotidian del encyclopedia.
Alcunes del functiones tende a tensionar le base de datos, pro isto per favor
non preme \"Reload\" post cata item reparate. ;-)",
"maintenancebacklink"	=> "Retornar al pagina de mantenentia",
"disambiguations"	=> "Paginas de disambiguation",
"disambiguationspage"	=> "Wikipedia:Ligamines_a_paginas_de_disambiguation",
"disambiguationstext"	=> "Le articulos sequente se liga a un <i>pagina de disambiguation</i>.
Illos deberea ligar se directemente al topico appropriate.<br>
Un pagina es tractate como un pagina de disambiguation si existe un ligamine
a illo in $1. Ligamines de altere contextos <i>non</i> es listate hic.",
"doubleredirects"	=> "Redirectiones duple",
"doubleredirectstext"	=> "<b>Attention:</b> Iste lista pote continer items false.
Illo generalmente significa que il ha texto additional con ligamines sub le prime #REDIRECT.<br>
Cata linea contine ligamines al prime e secunde redirection, assi como le prime linea del
secunde texto de redirection, generalmente exhibiente le articulo scopo \"real\",
al qual le prime redirection deberea referer se.",
"brokenredirects"	=> "Redirectiones van",
"brokenredirectstext"	=> "Le redirectiones sequente se liga a articulos inexistente.",
"selflinks"		=> "Paginas con ligamines circular",
"selflinkstext"		=> "Le paginas sequente contine un ligamine a se mesme, lo que non se recommenda.",
"mispeelings"           => "Paginas con errores orthographic",
"mispeelingstext"               => "Le paginas sequente contine un error orthographic commun, que es listate in $1. Illo debe esser substituite per le orthographia correcte (assi).",
"mispeelingspage"       => "Lista de errores orthographic commun",
"missinglanguagelinks"  => "Ligamines interlinguistic absente",
"missinglanguagelinksbutton"    => "Trovar ligamines interlinguistic mancante pro",
"missinglanguagelinkstext"      => "Iste articulos <i>non</i> se liga a lor equivalentes in $1. Redirectiones e subpaginas <i>non</i> es monstrate.",


# Miscellaneous special pages
#
"orphans"		=> "Paginas orphanas",
"lonelypages"	=> "Paginas orphanas",
"unusedimages"	=> "Imagines non usate",
"popularpages"	=> "Paginas popular",
"nviews"		=> "$1 visitas",
"wantedpages"	=> "Paginas plus demandate",
"nlinks"		=> "$1 ligamines",
"allpages"		=> "Tote le paginas",
"randompage"	=> "Pagina aleatori",
"shortpages"	=> "Paginas curte",
"longpages"		=> "Paginas longe",
"listusers"		=> "Lista de usatores",
"specialpages"	=> "Paginas special",
"spheading"		=> "Paginas special",
"sysopspheading" => "Paginas special pro uso del operatores del systema",
"developerspheading" => "Pagians special pro uso del disveloppatores",
"protectpage"	=> "Proteger pagina",
"recentchangeslinked" => "Modificationes correlate",
"rclsub"		=> "(a paginas ligate a partir de \"$1\")",
"debug"			=> "Reparar disfunctiones",
"newpages"		=> "Nove paginas",
"movethispage"	=> "Mover iste pagina",
"unusedimagestext" => "<p>Nota que altere sitos del web
tal como le Wikipedias international pote ligar se a un imagine
con un URL directe, e consequentemente illos pote esser listate
hic malgrado esser in uso active.",
"booksources"	=> "Fornitores de libros",
"booksourcetext" => "Infra es un lista de ligamines a altere sitos que
vende libros nove e usate, e pote haber information ulterior super
libros que tu cerca.
Wikipedia non es associate a iste interprisas, e iste lista
non debe esser interpretate como alcun appoio special.",

# Email this user
#
"mailnologin"	=> "Necun adresse de invio",
"mailnologintext" => "Tu debe <a href=\"" .
  "{{localurle:Special:Userlogin}}\">aperir un session</a>
e haber un adresse de e-mail valide in tu <a href=\"" .
  "{{localurle:Special:Preferences}}\">preferentias</a>
pro inviar e-mail a altere usatores.",
"emailuser"		=> "Inviar e-mail a iste usator",
"emailpage"		=> "Inviar e-mail al usator",
"emailpagetext"	=> "Si iste usator forniva un adresse de e-mail valide in
su preferentias de usator, le formulario infra le/la inviara un message.
Le adresse de e-mail que tu forniva in tu preferentias de usator apparera
como le adresse del expeditor del e-mail, a fin que le destinatario
pote responder te.",
"noemailtitle"	=> "Necun adresse de e-mail",
"noemailtext"	=> "Iste usator non ha specificate un adresse de e-mail valide,
o ha optate pro non reciper e-mail de altere usatores.",
"emailfrom"		=> "De",
"emailto"		=> "A",
"emailsubject"	=> "Subjecto",
"emailmessage"	=> "Message",
"emailsend"		=> "Inviar",
"emailsent"		=> "E-mail inviate",
"emailsenttext" => "Tu message de e-mail ha essite inviate.",

# Watchlist
#
"watchlist"		=> "Paginas sub observation",
"watchlistsub"	=> "(pro usator \"$1\")",
"nowatchlist"	=> "Tu non ha paginas sub observation.",
"watchnologin"	=> "Session non aperte",
"watchnologintext"	=> "Tu debe <a href=\"" .
  "{{localurle:Special:Userlogin}}\">aperir un session</a>
pro modificar tu lista de paginas sub observation.",
"addedwatch"	=> "Ponite sub observation",
"addedwatchtext" => "Le pagina \"$1\" es ora in tu <a href=\"" .
  "{{localurle:Special:Watchlist}}\">lista de paginas sub observation</a>.
Modificationes futur a iste pagina e su pagina de discussion associate essera listate la,
e le pagina apparera <b>in nigretto</b> in le <a href=\"" .
  "{{localurle:Special:Recentchanges}}\">lista de modificationes recente</a> pro
facilitar su identification.</p>

<p>Si tu vole cessar le obsevation de iste pagina posteriormente, clicca \"Cancellar observation\" in le barra de navigation.",
"removedwatch"	=> "Observation cancellate",
"removedwatchtext" => "Le pagina \"$1\" non es plus sub observation.",
"watchthispage"	=> "Poner iste pagina sub observation",
"unwatchthispage" => "Cancellar observation",
"notanarticle"	=> "Non es un articulo",

# Delete/protect/revert
#
"deletepage"	=> "Eliminar pagina",
"confirm"		=> "Confirmar",
"confirmdelete" => "Confirmar elimination",
"deletesub"		=> "(Elimination de \"$1\")",
"confirmdeletetext" => "Tu es a puncto de eliminar permanentemente un pagina
o imagine del base de datos, conjunctemente con tote su chronologia de versiones.
Per favor, confirma que, si tu intende facer lo, tu comprende le consequentias,
e tu lo face de accordo con [[Wikipedia:Policy]].",
"confirmcheck"	=> "Si, io realmente desira eliminar isto.",
"actioncomplete" => "Action complete",
"deletedtext"	=> "\"$1\" ha essite eliminate.
Vide $2 pro un registro de eliminationes recente.",
"deletedarticle" => "\"$1\" eliminate",
"dellogpage"	=> "Registro_de_eliminationes",
"dellogpagetext" => "Infra es un lista del plus recente eliminationes.
Tote le horas es in le fuso horari del servitor (UTC).
<ul>
</ul>
",
"deletionlog"	=> "registro de eliminationes",
"reverted"		=> "Revertite a revision anterior",
"deletecomment"	=> "Motivo del elimination",
"imagereverted" => "Reversion con successo a version anterior.",
"rollback"		=> "Revocar modificationes",
"rollbacklink"	=> "revocar",
"cantrollback"	=> "Impossibile revocar le modification; le ultime contribuente es le unic autor de iste articulo.",
"revertpage"	=> "Revertite al ultime modification per $1",

# Undelete
"undelete" => "Restaurar pagina eliminate",
"undeletepage" => "Vider e restaurar paginas eliminate",
"undeletepagetext" => "Le paginas sequente ha essite eliminate mais ancora es in le archivo e
pote esser restaurate. Le archivo pote esser evacuate periodicamente.",
"undeletearticle" => "Restaurar articulo eliminate",
"undeleterevisions" => "$1 revisiones archivate",
"undeletehistory" => "Si tu restaura un pagina, tote le revisiones essera restaurate al chronologia.
Si un nove pagina con le mesme nomine ha essite create post le elimination, le revisiones
restaurate apparera in le chronologia anterior, e le revision currente del pagina in vigor
non essera automaticamente substituite.",
"undeleterevision" => "Revision eliminate in $1",
"undeletebtn" => "Restautar!",
"undeletedarticle" => "\"$1\" restaurate",
"undeletedtext"   => "Le articulo [[$1]] ha essite restaurate con successo.
Vide [[Wikipedia:Registro_de_eliminationes]] pro un registro de eliminationes e restaurationes recente.",

# Contributions
#
"contributions"	=> "Contributiones de usator",
"mycontris" => "Mi contributiones",
"contribsub"	=> "Pro $1",
"nocontribs"	=> "Necun modification ha essite trovate secundo iste criterios.",
"ucnote"		=> "Infra es le <b>$1</b> ultime modificationes de iste usator in le <b>$2</b> ultime dies.",
"uclinks"		=> "Vider le $1 ultime modificationes; vider le $2 ultime dies.",
"uctop"		=> " (alto)" ,

# What links here
#
"whatlinkshere"	=> "Referentias a iste pagina",
"notargettitle" => "Sin scopo",
"notargettext"	=> "Tu non ha specificate un pagina o usator super le qual
executar iste function.",
"linklistsub"	=> "(Lista de ligamines)",
"linkshere"		=> "Le paginas sequente se liga a iste pagina:",
"nolinkshere"	=> "Necun pagina se liga a iste.",
"isredirect"	=> "pagina de redirection",

# Block/unblock IP
#
"blockip"		=> "Blocar adresse IP",
"blockiptext"	=> "Usa le formulario infra pro blocar le accesso de scriptura
a partir de un adresse IP specific.
Isto debe esser facite solmente pro impedir vandalismo, e de
accordo con le [[Wikipedia:Policy|politica de Wikipedia]].
Scribe un motivo specific infra (per exemplo, citante paginas
specific que ha essite vandalisate).",
"ipaddress"		=> "Adresse IP",
"ipbreason"		=> "Motivo",
"ipbsubmit"		=> "Blocar iste adresse",
"badipaddress"	=> "Adresse IP mal formate.",
"noblockreason" => "Tu debe fornir un motivo pro le blocage.",
"blockipsuccesssub" => "Blocage con successo",
"blockipsuccesstext" => "Le adresse IP \"$1\" ha essite blocate.
<br>Vide [[Special:Ipblocklist|Lista de IPs blocate]] pro revider le blocages.",
"unblockip"		=> "Disblocar adresse IP",
"unblockiptext"	=> "Usa le formulario infra pro restaurar le accesso de scriptura
a un adresse de IP blocate previemente.",
"ipusubmit"		=> "Disbloca iste adresse",
"ipusuccess"	=> "Adresse IP \"$1\" disblocate",
"ipblocklist"	=> "Lista de adresses IP blocate",
"blocklistline"	=> "$1, $2 ha blockate $3",
"blocklink"		=> "blocar",
"unblocklink"	=> "disblocar",
"contribslink"	=> "contributiones",

# Developer tools
#
"lockdb"		=> "Blocar base de datos",
"unlockdb"		=> "Disblocar base de datos",
"lockdbtext"	=> "Le blocage del base de datos suspendera le capacitate de tote
le usatores de modificar paginas, modificar lor preferentias e listas de paginas sub observation,
e altere actiones que require modificationes in le base de datos.
Per favor confirma que iste es tu intention, e que tu disblocara le
base de datos immediatemente post completar tu mantenentia.",
"unlockdbtext"	=> "Le disblocage del base de datos restaurara le capacitate de tote
le usatores de modificar paginas, modificar lor preferentias e listas de paginas sub observation,
e altere actiones que require modificationes in le base de datos.
Per favor confirma que iste es tu intention.",
"lockconfirm"	=> "Si, io realmente vole blocar le base de datos.",
"unlockconfirm"	=> "Si, io realmente vole disblocar le base de datos.",
"lockbtn"		=> "Blocar base de datos",
"unlockbtn"		=> "Disblocar base de datos",
"locknoconfirm" => "Tu non ha marcate le quadrato de confirmation.",
"lockdbsuccesssub" => "Base de datos blocate con successo",
"unlockdbsuccesssub" => "Base de datos disblocate con successo",
"lockdbsuccesstext" => "Le base de datos de Wikipedia ha essite blocate.
<br>Rememora te de disblocar lo post completar tu mantenentia.",
"unlockdbsuccesstext" => "Le base de datos de Wikipedia ha essite disblocate.",

# SQL query
#
"asksql"		=> "Consulta SQL",
"asksqltext"	=> "Usa le formulario infra pro facer un consulta directe al
base de datos de Wikipedia.
Usa apostrophos ('como istes') pro delimitar catenas de characteres.
Iste function pote supercargar le servitor, alora usa lo con moderation.",
"sqlquery"		=> "Scribe consulta",
"querybtn"		=> "Inviar consulta",
"selectonly"	=> "Consultas differente de \"SELECT\" es restricte al
disveloppatores de Wikipedia.",
"querysuccessful" => "Consulta effectuate con successo",

# Move page
#
"movepage"		=> "Mover pagina",
"movepagetext"	=> "Per medio del formulario infra tu pote renominar un pagina,
movente tote su chronologia al nove nomine.
Le titulo anterior devenira un pagina de redirection al nove titulo.
Le ligamines al pagina anterior non essera modificate;
assecura te de [[Special:Maintenance|verificar]] le apparition de redirectiones duple o van.
Tu es responsabile pro assecurar que le ligamines continua a punctar a ubi illos deberea.

Nota que le pagina '''non''' essera movite si ja existe un pagina
sub le nove titulo, salvo si illo es vacue o un redirection e non
ha un chronologia de modificationes passate. Isto significa que tu
pote renominar un pagina a su titulo original si tu lo ha renominate
erroneemente, e que tu non pote superscriber un pagina existente.

<b>ADVERTIMENTO!</b>
Isto pote esser un cambio drastic e inexpectate pro un pagina popular;
per favor assecura te que tu comprende le consequentias de isto
ante proceder.",
"movepagetalktext" => "Le pagina de discussion associate, si existe, essera automaticamente movite conjunctemente con illo '''a minus que''':
*Tu move le pagina trans contextos,
*Un pagina de discussion non vacue ja existe sub le nove nomine, o
*Tu dismarca le quadrato infra.

Il tal casos, tu debera mover o fusionar le pagina manualmente si desirate.",
"movearticle"	=> "Mover pagina",
"movenologin"	=> "Session non aperte",
"movenologintext" => "Tu debe esser un usator registrate e <a href=\"" .
  "{{localurle:Special:Userlogin}}\">aperir un session</a>
pro mover un pagina.",
"newtitle"		=> "Al nove titulo",
"movepagebtn"	=> "Mover pagina",
"pagemovedsub"	=> "Pagina movite con successo",
"pagemovedtext" => "Pagina \"[[$1]]\" movite a \"[[$2]]\".",
"articleexists" => "Un pagina con iste nomine ja existe, o le
nomine selectionate non es valide.
Per favor selectiona un altere nomine.",
"talkexists"	=> "Le pagina mesme ha essite movite con successo, mais le
pagina de discussion associate non ha essite movite proque ja existe un sub le
nove titulo. Per favor fusiona los manualmente.",
"movedto"		=> "movite a",
"movetalk"		=> "Mover le pagina de \"discussion\" tamben, si applicabile.",
"talkpagemoved" => "Le pagina de discussion correspondente tamben ha essite movite.",
"talkpagenotmoved" => "Le pagina de discussion correspondente <strong>non</strong> ha essite movite.",

# Math

	'mw_math_png' => "Sempre produce PNG",
	'mw_math_simple' => "HTML si multo simple, alteremente PNG",
	'mw_math_html' => "HTML si possibile, alteremente PNG",
	'mw_math_source' => "Lassa lo como TeX (pro navigatores in modo texto)",
	'mw_math_modern' => "Recommendate pro navigatores moderne",
	'mw_math_mathml' => 'MathML',

);

require_once( "LanguageUtf8.php" );

class LanguageIa extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListIa ;
		return $wgBookstoreListIa ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesIa;
		return $wgNamespaceNamesIa;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesIa;
		return $wgNamespaceNamesIa[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesIa;

		foreach ( $wgNamespaceNamesIa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsIa;
		return $wgQuickbarSettingsIa;
	}

	function getSkinNames() {
		global $wgSkinNamesIa;
		return $wgSkinNamesIa;
	}

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . (0 + substr( $ts, 6, 2 )) . ", " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->time( $ts, $adj ) . " " . $this->date( $ts, $adj );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesIa;
		return $wgValidSpecialPagesIa;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesIa;
		return $wgSysopSpecialPagesIa;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesIa;
		return $wgDeveloperSpecialPagesIa;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesIa, $wgAllMessagesEn;
		$m = $wgAllMessagesIa[$key];

		if ( "" == $m ) { return $wgAllMessagesEn[$key]; }
		else return $m;
	}

}

?>
