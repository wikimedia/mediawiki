<?php

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
# This translation was made by Yves Marques Junqueira 
# and Rodrigo Calanca Nishino from Portuguese Wikipedia
#
/* private */ $wgNamespaceNamesPt = array(
    -1  => "Especial",
    0   => "",
    1   => "Discussão",
    2   => "Usuário",
    3   => "Usuário_Discussão",
    4   => $wgMetaNamespace,
    5   => $wgMetaNamespace."_Discussão",
    6   => "Imagem",
    7   => "Imagem_Discussão",
    8   => "MediaWiki",
    9   => "MediaWiki_Discussão",
);

/* private */ $wgDefaultUserOptionsPt = array(
    "quickbar" => 1, "underline" => 1, "hover" => 1,
    "cols" => 80, "rows" => 25, "searchlimit" => 20,
    "contextlines" => 5, "contextchars" => 50,
    "skin" => 0, "math" => 1, "rcdays" => 7, "rclimit" => 50,
    "highlightbroken" => 1, "stubthreshold" => 0,
    "previewontop" => 1, "editsection"=>1,"editsectiononrightclick"=>0, "showtoc"=>1,
    "date" => 0
);

/* private */ $wgQuickbarSettingsPt = array(
    "Nada", "Fixado �  esquerda", "Fixado �  direita", "Flutuando �  Esquerda"
);

/* private */ $wgSkinNamesPt = array(
    "Padrão", "Nostalgia", "Azul Colonial"
);

/* private */ $wgMathNamesPt = array(
    "Sempre renderizar PNG",
    "HTML se for bem simples e PNG",
    "HTML se possível ou então PNG",
    "Deixar como TeX (para navegadores em modo texto)",
    "Recomendado para navegadores modernos"
);

/* private */ $wgDateFormatsPt = array(
    "Sem preferência",
    "Janeiro 15, 2001",
    "15 Janeiro 2001",
    "2001 Janeiro 15"
);

/* private */ $wgUserTogglesPt = array(
    "hover"     => "Mostra caixa flutante sobre os links wiki",
    "underline" => "Sublinha links",
    "highlightbroken" => "Formata links quebrados <a href=\"\" class=\"new\"> como isto </a> (alternative: como isto<a href=\"\" class=\"internal\">?</a>).",
    "justify"   => "Justifica parágrafos",
    "hideminor" => "Esconder edições secundárias em mudanças recentes",
    "usenewrc" => "Mudanças recentes melhoradas(nem todos os navegadores)",
    "numberheadings" => "Auto-numerar cabeçalhos",
        "showtoolbar" => "Mostrar barra de edição",
    "editondblclick" => "Editar páginas quando houver clique duplo(JavaScript)",
    "editsection"=>"Habilitar seção de edição via links [edit]",
    "editsectiononrightclick"=>"Habilitar seção de edição por clique <br> com o botão direito no título da seção (JavaScript)",
    "showtoc"=>"Mostrar Tabela de Conteúdos<br>(para artigos com mais de 3 cabeçalhos)",
    "rememberpassword" => "Lembra senha entre sessões",
    "editwidth" => "Caixa de edição com largura completa",
    "watchdefault" => "Observa artigos novos e modificados",
    "minordefault" => "Marca todas as edições como secundárias, por padrão",
    "previewontop" => "Mostrar Previsão antes da caixa de edição ao invés de ser após",
    "nocache" => "Desabilitar caching de página"
);

/* private */ $wgBookstoreListPt = array(
    "AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
    "PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
    "Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
    "Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgWeekdayNamesPt = array(
    "Domingo", "Segunda", "Terça-Feira", "Quarta-Feira", "Quinta-Feira",
    "Sexta-Feira", "Sábado"

);

/* private */ $wgMonthNamesPt = array(
    "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
    "Julho", "Agosto", "Setembro", "Outubro", "Novembro",
    "Dezembro"

);

/* private */ $wgMonthAbbreviationsPt = array(
    "Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago",
    "Set", "Out", "Nov", "Dez"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesPt = array(
    "Userlogin"     => "",
    "Userlogout"    => "",
    "Preferences"   => "Configura minhas pref. de usuário",
    "Watchlist"     => "Minha lista de arigos observados",
    "Recentchanges" => "Páginas modificadas recentemente",
    "Upload"        => "Envia arquivo de imagens",
    "Imagelist"     => "Lista de imagens",
    "Listusers"     => "Usuários registrados",
    "Statistics"    => "Estatísticas do site",
    "Randompage"    => "Artigo aleatório",

    "Lonelypages"   => "Artigos órfãos",
    "Unusedimages"  => "Imagens órfãs",
    "Popularpages"  => "Artigos populares",
    "Wantedpages"   => "Artigos mais requisitados",
    "Shortpages"    => "Artigos curtos",
    "Longpages"     => "Artigos longos",
    "Newpages"      => "Artigos criados recentemente",
    "Ancientpages"  => "Artigos mais antigos",
    "Intl"      => "Links de Interlinguagens",
    "Allpages"      => "Todas as páginas, org. por títulos",

    "Ipblocklist"   => "Endereços IP bloqueados",
    "Maintenance" => "Página de manutenção",
    "Specialpages"  => "Páginas Especiais",
    "Contributions" => "Contribuições",
    "Emailuser"     => "Enviar e-mail ao usuário",
    "Whatlinkshere" => "Relacionados",
    "Recentchangeslinked" => "Modificações recentes",
    "Movepage"      => "Mover Página",
    "Booksources"   => "Fontes bibliográficas externas",
    #"Categories" => "Categorias de Páginas",
    "Export"    => "XML export",
);

/* private */ $wgSysopSpecialPagesPt = array(
	"Makesysop" => "Turn a user into a sysop",
    "Blockip"       => "Bloquear um endereço IP",
    "Asksql"        => "Busca o banco-de-dados",
    "Undelete"      => "Ver e restaura páginas apagadas"
);

/* private */ $wgDeveloperSpecialPagesPt = array(
    "Lockdb"        => "Torna o banco de dados como apenas leitura",
    "Unlockdb"      => "Restaura o acesso �  escrita no banco de dados",
    "Debug"         => "\'Debugar\' informações"
);

/* private */ $wgAllMessagesPt = array(

# Bits of text used by many pages:
#
"categories" => "Page categories",

"category" => "category",
"category_header" => "Articles in category \"$1\"",
"subcategories" => "Subcategories",

"linktrail"     => "/^([a-z]+)(.*)\$/sD",
"mainpage"      => "Página principal",
"mainpagetext"  => "Software Wiki instalado com sucesso.",
"about"         => "Sobre",
"aboutwikipedia" => "Sobre a Wikipedia",
"aboutpage"     => "{$wgMetaNamespace}:Sobre",
"help"          => "Ajuda",
"helppage"      => "{$wgMetaNamespace}:Ajuda",
"wikititlesuffix" => "Wikipedia",
"bugreports"    => "Reportagem de 'bugs'",
"bugreportspage" => "{$wgMetaNamespace}:Reportag_Bug",
"faq"           => "FAQ",
"faqpage"       => "{$wgMetaNamespace}:FAQ",
"edithelp"      => "Ajuda de edição",
"edithelppage"  => "{$wgMetaNamespace}:Como_editar_uma_página",
"cancel"        => "Cancela",
"qbfind"        => "Procura",
"qbbrowse"      => "Folhear",
"qbedit"        => "Editar",
"qbpageoptions" => "Opções de página",
"qbpageinfo"    => "Informação de página",
"qbmyoptions"   => "Minhas opções",
"mypage"        => "Minha página",
"mytalk"        => "Minha discussão",
"currentevents" => "Eventos atuais",
"errorpagetitle" => "Erro",
"returnto"      => "Retorna para $1.",
"fromwikipedia" => "Origem: Wikipedia, a enciclopédia livre.",
"whatlinkshere" => "Páginas que se ligam a essa",
"help"          => "Ajuda",
"search"        => "Busca",
"go"        => "Vai",
"history"       => "Histórico",
"printableversion" => "Versão para impressão",
"editthispage"  => "Editar esta página",
"deletethispage" => "Apagar esta página",
"protectthispage" => "Proteger esta página",
"unprotectthispage" => "Desproteger esta página",
"newpage" => "Nova página",
"talkpage"      => "Discutir esta página",
"postcomment"   => "Post a comment",
"articlepage"   => "Ver atigo",
"subjectpage"   => "Ver assunto", # For compatibility
"userpage" => "Ver página de usuário",
"wikipediapage" => "Ver meta página",
"imagepage" =>  "Ver página de imagens",
"viewtalkpage" => "Ver discussões",
"otherlanguages" => "Outras línguas",
"redirectedfrom" => "(Redirecionado de $1)",
"lastmodified"  => "Está página foi modificada pela última vez em $1.",
"viewcount"     => "Esta página foi acessada $1 vezes.",
"gnunote" => "Todo o texto é disponível sob os termos da <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(De http://www.wikipedia.org/pt)",
"protectedpage" => "Página protegida",
"administrators" => "{$wgMetaNamespace}:Administradores",
"sysoptitle"    => "Acesso de OpSys necessário",
"sysoptext"     => "A ação que você requisitou só pode ser
executada por usuários com status de \"opsys\".
Veja $1.",
"developertitle" => "Acesso de desenvolvedor necessário",
"developertext" => "A ação que você requisitou só pode ser 
executada por usuários com status de \"desenvolvedor\".
Veja $1.",
"nbytes"        => "$1 bytes",
"go"            => "vai",
"ok"            => "OK",
"sitetitle"     => "Wikipedia",
"sitesubtitle"  => "A enciclopédia livre",
"retrievedfrom" => "Retirado de  \"$1\"",
"newmessages" => "You have $1.",
"newmessageslink" => "novas mensagens",
"editsection"=>"editar",
"toc" => "Conteúdo",
"showtoc" => "mostrar",
"hidetoc" => "esconder",

# Main script and global functions
#
"nosuchaction"  => "Ação não existente",
"nosuchactiontext" => "A ação especificada pela URL não é
reconhecida pelo programa da Wikipedia",
"nosuchspecialpage" => "Não exista esta página especial",
"nospecialpagetext" => "Você requisitou uma página especial que não é
reconhecida pelo software da Wikipedia.",

# General errors
#
"error"         => "Erro",
"databaseerror" => "Erro no banco de dados",
"dberrortext"   => "Um erro de sintaxe de busca no banco de dados ocorreu.
Isto pode ser devido a uma busca incorreta (veja $5),
ou pode indicar um erro no programa.
A última tentativa de busca no banco de dados foi:
<blockquote><tt>$1</tt></blockquote>
na função \"<tt>$2</tt>\".
MySQL retornou o erro \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Um erro de sintaxe de pesquisa no banco
de dados ocorreu.
A última tentativa de pesquisa no banco de dados foi:
\"$1\"
com a função\"$2\".
MySQL retornou o erro \"$3: $4\".\n",
"noconnect"     => "Desculpe! O wiki está passando por algumas
dificuldades técnicas, e não pode contatar o servidor de bando de dados.",
"nodb"          => "Não foi possível selecionar o banco de dados $1",
"cachederror"       => "O que segue é uma cópia em cache da página 
solicitada, e pode não estar atualizada.",
"readonly"      => "Banco de dados somente para leitura",
"enterlockreason" => "Entre com um motivo para trancá-lo, incluindo uma estimativa de quando poderá novamente ser escrito",
"readonlytext"  => "O Banco-de-dados da Wikipedia está atualmente bloqueado para novos
artigos e outras modificações, provávelmente por uma manutenção rotineira no Bando de Dados,
mais tarde voltará ao normal.

O administrador que fez o bloqueio oferece a seguinte explicação:
<p>$1",
"missingarticle" => "O Banco-de-Dados não encontrou o texto de uma página 
que deveria ser encontrado, chamado \"$1\".

<p>Isto é geralmente causado pela procura de um diff antigo ou um histórico que leva a uma página que foi deletada.

<p>Se isto não for o caso, você pode ter encontrado um bug no software.
Por favor, comunique isto ao administrador, tenha nota da URL.",
"internalerror" => "Erro Interno",
"filecopyerror" => "Não foi possível copiar o arquivo \"$1\" para \"$2\".",
"filerenameerror" => "Não foi possível renomear o arquivo \"$1\" para \"$2\"",
"filedeleteerror" => "Não foi possível deletar o arquivo \"$1\".",
"filenotfound"  => "Não foi possível encontrar o arquivo \"$1\".",
"unexpected"    => "Valor não esperado: \"$1\"=\"$2\".",
"formerror"     => "Erro: Não foi possível enviar o formulário", 
"badarticleerror" => "Esta acção não pode ser performada nesta página.",
"cannotdelete"  => "Não foi possível excluir página ou imagem especificada. (Ela já pode ter sido deletada por alguém.)",
"badtitle"      => "Título ruim",
"badtitletext"  => "O título de pagina requisitado era inválido, vazio, ou
uma ligação incorreta de inter-linguagem ou título inter-wiki .",
"perfdisabled" => "Desculpe! Esta opção foi temporariamente desabilitada
porque tornava o banco de dados lento demais a ponto de impossibilitar o wiki.",
"perfdisabledsub" => "Aqui está uma cópia salva de $1:",

# Login and logout pages
#
"logouttitle"   => "Saída de utilizador",
"logouttext"    => "Você agora não está mais autenticado.
Você pode continuar a usar a Wikipedia anonimamente, ou pode se autenticar
novamente como o mesmo utilizador ou como um utilizador diferente.\n",

"welcomecreation" => "<h2>Bem-vindo, $1!</h2><p>Sua conta foi criada.
Não se esqueça de personalizar suas preferências na Wikipedia.",

"loginpagetitle" => "Login de usuário",
"yourname"      => "Seu nome de usuário",
"yourpassword"  => "Sua senha",
"yourpasswordagain" => "Redigite sua senha",
"newusersonly"  => " (somente novos usuários)",
"remembermypassword" => "Lembrar de minha senha em outras sessões.",
"loginproblem"  => "<b>Houve um problema com a sua autenticação.</b><br>Tente novamente!",
"alreadyloggedin" => "<font color=red><b>Utilizador $1, você já está autenticado!</b></font><br>\n",

"areyounew"     => "Se você é novo(a) na Wikipedia e quer fazer uma conta de utilizador, entre com um nome de utilizador e depois digite e re-digite uma senha. Seu e-mail é opcional - se você perder a sua senha, você pode requisitar para que ela seja enviada para o endereço que você informou.<br>\n",

"login"         => "Entrar",
"userlogin"     => "Entrar",
"logout"        => "Sair",
"userlogout"    => "sair",
"notloggedin"   => "Não-logado",
"createaccount" => "Criar nova conta",
"createaccountmail" => "por e-Mail",
"badretype"     => "As senhas que você digitou não são iguais.",
"userexists"    => "O nome de usuário que você digitou já existe. Por favor, escolha um nome diferente.",
"youremail"     => "Seu e-mail*",
"yournick"      => "Seu apelido (para assinaturas)",
"emailforlost"  => "* Colocar o endereço de e-mail é opcional.  Mas permite que as pessoas entrem em contato com você sem que você tenha que revelar seu e-mail �  elas, e também é útil se você se esquecer da sua senha.",
"loginerror"    => "Erro de autenticação",
"noname"        => "Você não colocou um nome de usuário válido.",
"loginsuccesstitle" => "Login bem sucedido",
"loginsuccess"  => "Agora você está logado na Wikipedia como \"$1\".",
"nosuchuser"    => "Não há nenhum usuário com o nome \"$1\".
Verifique sua grafia, ou utilize o formulário a baixo para criar uma nova conta de usuário.",
"wrongpassword" => "A senha que você entrou é inválida. Por favor tente novamente.",
"mailmypassword" => "Envie uma nova senha por e-mail",
"passwordremindertitle" => "Lembrador de senhas da Wikipedia",
"passwordremindertext" => "Alguém (provavelmente você, do endereço de IP $1)
solicitou que nós lhe enviássemos uma nova senha para login.
A senha para o usuário \"$2\" é a partir de agora \"$3\".
Você pode realizar um login e mudar sua senha agora.",
"noemail"       => "Não há nenhum e-Mail associado ao usuário \"$1\".",
"passwordsent"  => "Uma nova senha está sendo enviada para o endereço de e-Mail 
registrado para \"$1\".
Por favor, reconecte-se ao recebê-lo.",

# Edit pages
#
"summary"       => "Sumário",
"subject"       => "Assunto",
"minoredit"     => "Edição menor",
"watchthis"     => "Observar este artigo",
"savearticle"   => "Salvar página",
"preview"       => "Prever",
"showpreview"   => "Mostrar Pré-Visualização",
"blockedtitle"  => "Usuário está bloqueado",
"blockedtext"   => "Seu nome de usuário ou numero de IP foi bloqueado por $1.
O motivo é:<br>''$2''<p>Você pode contactar $1 ou outro
[[{$wgMetaNamespace}:administradores|administrador]] para discutir sobre o bloqueio.",
"whitelistedittitle" => "Login necessário para edição",
"whitelistedittext" => "Você precisa se [[Especial:Userlogin|logar]] para editar artigos.",
"whitelistreadtitle" => "Login necessário para leitura",
"whitelistreadtext" => "Você precisa se [[Especial:Userlogin|logar]] para ler artigos.",
"whitelistacctitle" => "Você não está habilitado a criar uma conta",
"whitelistacctext" => "Para ter permissão para se criar uma conta neste Wiki você precisará estar [[Especial:Userlogin|logado]] e ter as permissões apropriadas.",
"accmailtitle" => "Senha enviada.",
"accmailtext" => "A senha de '$1' foi enviada para $2.",
"newarticle"    => "(Novo)",
"newarticletext" =>
"Você seguiu um link para um artigo que não existe mais.
Para criar a página, começe escrevendo na caixa a baixo 
(veja [[{$wgMetaNamespace}:Ajuda| a página de ajuda]] para mais informações).
Se você chegou aqui por engano, apenas clique no botão  '''volta''' do seu navegador.",

"anontalkpagetext" => "---- ''Esta é a página de discussão para um usuário anônimo que não criou uma conta ainda ou que não a usa. Então nós temos que usar o endereço numérico de IP para identificá-lo(la). Um endereço de IP pode ser compartilhado por vários usuários. Se você é um usuário anônimo e acha irrelevante que os comentários sejam direcionados a você, por favor [[Especial:Userlogin|crie uma conta ou autentifique-se]] para evitar futuras confusões com outros usuários anônimos.'' ",
"noarticletext" => "(Não há atualmente nenhum texto nesta página)",
"updated"       => "(Atualizado)",
"note"          => "<strong>Nota:</strong> ",
"previewnote"   => "Lembre-se que isto é apenas uma previsão, e não foi ainda salvo!",
"previewconflict" => "Esta previsão reflete o texto que está na área de edição acima e como ele aparecerá se você escolher salvar.",
"editing"       => "Editando $1",
"sectionedit"   => " (seção)",
"commentedit"   => " (comentário)",
"editconflict"  => "Conflito de edição: $1",
"explainconflict" => "Alguém mudou a página enquanto você a estava editando.
A área de texto acima mostra o texto original.
Suas mudanças são mostradas na área a baixo.
Você terá que mesclar suas modificações no texto existente.
<b>SOMENTE</b> o texto na área acima será salvo quando você pressionar \"Salvar página\".\n<p>",
"yourtext"      => "Seu texto",
"storedversion" => "Versão guardada",
"editingold"    => "<strong>CUIDADO: Você está editando uma revisão desatualizada deste artigo.
Se você salvá-lo, todas as mudanças feitas a partir desta revisão serão perdidas.</strong>\n",
"yourdiff"      => "Diferenças",
"copyrightwarning" => "Por favor note que todas as contribuições �  Wikipedia são consideradas lançadas sobre a GNU Free Documentation License
(veja $1 para detalhes).
Se você não quer que seu texto esteja sobre estes termos, então não os envie.<br>
Você também promete que está nos enviando um artigo escrito por você mesmo, ou extraindo de uma fonte de domínio público similar.
<strong>NÃO ENVIE TRABALHO SOB COPYRIGHT SEM PERMISSÃO!</strong>",
"longpagewarning" => "CUIDADO: Esta página tem $1 kilobytes ; alguns browsers podem ter problemas ao editar páginas maiores que 32kb.
Por favor considere quebrar a página em sessões menores.",
"readonlywarning" => "CUIDADO: O banco de dados está sendo bloqueado para manutenção,
você não está habilitado a salvar suas edições. Você pode copiar e colar o texto em um arquivo de texto e salvá-lo em seu computador para adicioná-lo mais tarde.",
"protectedpagewarning" => "CUIDADO:  Está página foi bloqueada então apenas os usuários com privilégios de sysop podem editá-la. Certifique-se de que você está seguindo o <a href='/wiki/{$wgMetaNamespace}:Guia_de_páginas_protegidas'>guia de páginas protegidas</a>.",

# History pages
#
"revhistory"    => "Histórico de revisões",
"nohistory"     => "Não há histórico de revisões para esta página.",
"revnotfound"   => "Revisão não encontrada",
"revnotfoundtext" => "A antiga revisão da página que você está procurando não pode ser encontrada.
Por favor verifique a URL que você usou para acessar esta página.\n",
"loadhist"      => "Carregando histórico",
"currentrev"    => "Revisão atual",
"revisionasof"  => "Revisão de $1",
"cur"           => "atu",
"next"          => "prox",
"last"          => "ult",
"orig"          => "orig",
"histlegend"    => "Legenda: (atu) = diferenças da versão atual,
(ult) = diferença da versão precedente, M = edição minoritária",

# Diffs
#
"difference"    => "Diferença entre revisões)",
"loadingrev"    => "carregando a busca por diferenças",
"lineno"        => "Linha $1:",
"editcurrent"   => "Editar a versão atual desta página",

# Resultados da Busca
#
"searchresults" => "Buscar resultados",
"searchhelppage" => "{$wgMetaNamespace}:Procurando",
"searchingwikipedia" => "Busca na Wikipedia",
"searchresulttext" => "Para mais informações sobre busca na Wikipedia, veja $1.",
"searchquery"   => "Para pedido de busca \"$1\"",
"badquery"      => "Linha de busca incorretamente formada",
"badquerytext"  => "Nós não pudemos processar seu pedido de busca.
Isto acoenteceu provavelmente porque você tentou procurar uma palavra de menos que três letras, coisa que o software ainda não consegue realizar. Isto também pode ter ocorrido porque você digitou incorretamente a expressão, por
exemplo \"peixes <strong>and and</strong> scales\".
Por favor realize ouro pedido de busca.",
"matchtotals"   => "A pesquisa \"$1\" resultou $2 títulos de artigos
e $3 artigos com o texto procurado.",
"nogomatch" => "Nenhum artigo com um título exatamente igual a este foi encontrado, tentando na pesquisa completa por texto.",
"titlematches"  => "Resultados nos títulos dos artigos",
"notitlematches" => "Sem resultados nos títulos dos artigos",
"textmatches"   => "Resultados nos textos dos artigos",
"notextmatches" => "Sem resultados nos textos dos artigos",
"prevn"         => "anterior $1",
"nextn"         => "próximo $1",
"viewprevnext"  => "Ver ($1) ($2) ($3).",
"showingresults" => "Mostrando os próximos <b>$1</b> resultados começando com #<b>$2</b>.",
"showingresultsnum" => "Mostrando <b>$3</b> resultados começando com #<b>$2</b>.",
"nonefound"     => "<strong>Nota</strong>: pesquisas mal sucedidas são geralmente causadas devido o uso de palavras muito comuns como \"tem\" e \"de\",
que não são indexadas, ou pela especificação de mais de um termo (somente as páginas contendo todos os termos aparecerão nos resultados).",
"powersearch" => "Pesquisa",
"powersearchtext" => "
Procurar nos namespaces :<br>
$1<br>
$2 Lista redireciona &nbsp; Procura por $3 $9",
"blanknamespace" => "(Principal)",

# Preferences page
#
"preferences"   => "Preferências",
"prefsnologin" => "Não autenticado",
"prefsnologintext"  => "Você precisa estar <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">autenticado</a>
para definir suas preferências.",
"prefslogintext" => "Você está autenticado como \"$1\".
Seu número identificador interno é $2.

veja [[{$wgMetaNamespace}:Ajuda_preferências_de_usuários]] para aprender a decifrar as opções.",
"prefsreset"    => "Preferências foram reconfiguradas.",
"qbsettings"    => "Configurações da Barra Rápida", 
"changepassword" => "Mudar senha",
"skin"          => "Aparência(Skin)",
"math"          => "Rendering math",
"dateformat"    => "Formato da Data",
"math_failure"      => "Falhou ao checar gramática(parse)",
"math_unknown_error"    => "erro desconhecido",
"math_unknown_function" => "função desconhecida ",
"math_lexing_error" => "erro léxico",
"math_syntax_error" => "erro de síntaxe",
"saveprefs"     => "Salvar preferências",
"resetprefs"    => "Reconfigurar preferências",
"oldpassword"   => "Senha antiga",
"newpassword"   => "Nova senha",
"retypenew"     => "Redigite a nova senha",
"textboxsize"   => "Tamanho da Caixa de texto",
"rows"          => "Linhas",
"columns"       => "Colunas",
"searchresultshead" => "Configurar resultados de pesquisas",
"resultsperpage" => "Resultados por página",
"contextlines"  => "Linhas por resultados",
"contextchars"  => "Letras de contexto por linha",
"stubthreshold" => "Threshold for stub display",
"recentchangescount" => "Número de títulos em Mudanças Recentes",
"savedprefs"    => "Suas preferências foram salvas.",
"timezonetext"  => "Entre com o número de horas que o seu horário local difere do horário do servidor (UTC).",
"localtime" => "Display de hora local",
"timezoneoffset" => "Offset",
"servertime"    => "Horário do servidor é",
"guesstimezone" => "Colocar no navegador",
"emailflag"     => "Desabilitar e-mail de outros usuários",
"defaultns"     => "Procurar nestes namespaces por padrão:",

# Recent changes
#
"changes" => "mudanças",
"recentchanges" => "Mudanças Recentes",
"recentchangestext" => "Veja as mais novas mudanças na Wikipedia nesta página.
[[{$wgMetaNamespace}:Bem Vindo,_novatos|Bem Vindo, novatos]]!
Por favor, dê uma olhada nestas páginas: [[{$wgMetaNamespace}:FAQ|FAQ da Wikipedia]],
[[{$wgMetaNamespace}:Políticas e Normas| Política da Wikipedia]]
(especialmente [[{$wgMetaNamespace}:Convenções de nomenclatura|convenções de nomenclatura]],
[[{$wgMetaNamespace}:Ponto de vista neutro|Ponto de vista neutro]]),
e [[{$wgMetaNamespace}:Most common Wikipedia faux pas|most common Wikipedia faux pas]].

Se você quer ver a Wikipedia crescer, é muito importante que você não adicione material restrito por outras [[{$wgMetaNamespace}:Copyrights|copyrights]].
Um problema legal poderia realmente prejudicar o projeto de maneira que pedimos, por avor, não faça isso. 
Veja também [http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion].",
"rcloaderr"     => "Carregando alterações recentes",
"rcnote"        => "Abaixo estão as últimas <strong>$1</strong> alterações nos últimos <strong>$2</strong> dias.",
"rcnotefrom"    => "Abaixo estão as mudanças desde <b>$2</b> (até <b>$1</b> mostradas).",
"rclistfrom"    => "Mostrar as novas alterações a partir de $1",
# "rclinks"     => "Mostrar as últimas $1 alterações nas últimas $2 hours / últimos $3 dias",
# "rclinks"     => "Mostrar as últimas $1 mudanças nos últimos $2 dias.",
"rclinks"       => "Mostrar as últimas $1 mudanças nos últimos $2 dias; $3 edições minoritárias",
"rchide"        => "em $4 formulários; $1 edições minoritárias; $2 namespaces secundários; $3 múltiplas edições.",
"rcliu"         => "; $1 edições de usuários autenticados",
"diff"          => "dif",
"hist"          => "hist",
"hide"          => "esconde",
"show"          => "mostra",
"tableform"     => "tabela",
"listform"      => "lista",
"nchanges"      => "$1 mudanças",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"        => "Carregar arquivo",
"uploadbtn"     => "Carregar arquivo",
"uploadlink"    => "Carregar imagens",
"reupload"      => "Re-carregar",
"reuploaddesc"  => "Retornar ao formulário de Uploads.",
"uploadnologin" => "Não autenticado",
"uploadnologintext" => "Você deve estar<a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autenticado</a>
para carregar arquivos.",
"uploadfile"    => "Carregar imagens, sons, documentos etc.",
"uploaderror"   => "Erro ao Carregar",
"uploadtext"    => "<strong>PARE!</strong> Antes de você carregar arquivos aqui,
tenha certeza de ter lido e estar em acordo com a <a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Política_de_imagens" ) . "\">política de uso de imagens da Wikipedia</a>.
<p>Para ver ou procurar imagens carregadas,
vá �  <a href=\"" . wfLocalUrlE( "Especial:ListadeImagens" ) .
"\">lista de imagens carregadas</a>.
Uploads e deleções são armazenados no <a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Upload_log" ) . "\">log de uploads</a>.
<p>Use o formulário a seguir para carregar arquivos de imagens para ilustrar seus artigos. Na maioria dos navegadores, você verá um botão \"Browse...\" , que trárá o diálogo padrão de abertura de arquivo padrão do seu Sistema Operacional.
Ao escolher um arquivo, o campo de texto próximo ao botão será preenchido.
Você tembém deve confirmar  que não está carregando nenhum arquivo protegido por Diretos Autorais.
Pressione o botão \"Carregar\" para finalizar o upload.
Isto pode demorar um pouco se você tem possui uma conexão lenta.
<p>Os formatos ideais são JPEG para fotos, PNG
para ilustrações, e OGG para sons.
Por favor, nomeie seus arquivos de forma descritiva para evitar confusões.
Para incluir uma imagem em um artigo, use um link na forma <b>[[image:arquivo.jpg]]</b> ou <b>[[image:arquivo.png|texto descritivo]]</b>
ou <b>[[media:audio.ogg]]</b> para sons.
<p>Por favor, note que com as páginas da Wikipedia, outros usuários podem modificar ou deletar seus uploads se eles acharem que isto seja útil �  wikipedia, e você possa estar bloqueado para uploads devido a abusos do sistema.",
"uploadlog"     => "log de uploads",
"uploadlogpage" => "Log_de_Uploads",
"uploadlogpagetext" => "Segue uma lista dos uploads mais recentes.
Todas as datas mostradas são do servidor (UTC).
<ul>
</ul>
",
"filename"      => "Nome do arquivo",
"filedesc"      => "Sumário",
"affirmation"   => "Eu afirmo que o proprietário deste arquivo concorda em licenciá-lo sob os termos da $1.",
"copyrightpage" => "{$wgMetaNamespace}:Copyrights",
"copyrightpagename" => "Direitos Autorais da Wikipedia",

"uploadedfiles" => "Arquivos carregados",
"noaffirmation" => "Você tem que afirmar que o carregamento deste arquivo não fere nenhum direito autoral.",
"ignorewarning" => "Ignorar aviso e salvar de qualquer forma.",
"minlength"     => "Os nomes das imagens devem ter ao menos três letras.",
"badfilename"   => "O nome da imagem mudou para \"$1\".",
"badfiletype"   => "\".$1\" não está em um formato recomendável.",
"largefile"     => "É recomendado que as imagens não tenham mais que 100k de tamanho.",
"successfulupload" => "Carregamento efetuado com sucesso",
"fileuploaded"  => "Arquivo \"$1\" carregado com sucesso.
Por favor, siga este link : ($2) para ir �  página de descrição e preencha-a com informações sobre o arquivo, como de onde veio , quando e por quem foi criada, e qualquer outra coisa a mais que você saiba.",
"uploadwarning" => "Aviso de Upload",
"savefile"      => "Salvar arquivo",
"uploadedimage" => "\"$1\" carregado",

# Image list
#
"imagelist"     => "Lista de Imagens",
"imagelisttext" => "A seguir uma lista de $1 imagens organizadas $2.",
"getimagelist"  => "buscando lista de imagens",
"ilshowmatch"   => "Mostrar todas as imagens com semelhança no nome",
"ilsubmit"      => "Procura",
"showlast"      => "Mostrar as  $1 imagens organizadas $2.",
"all"           => "todas",
"byname"        => "por nome",
"bydate"        => "por data",
"bysize"        => "por tamanho",
"imgdelete"     => "del",
"imgdesc"       => "desc",
"imglegend"     => "Legenda: (desc) = mostrar/editar descrição de imagem.",
"imghistory"    => "Histórico das imagens",
"revertimg"     => "rev",
"deleteimg"     => "del",
"imghistlegend" => "Legenda: (cur) = esta é a imagem atual, (del) = deletar
esta versão antiga, (rev) = reverter para esta versão antiga.
<br><i>Clique em data para ver das imagens carregadas nesta data</i>.",
"imagelinks"    => "Links das imagens",
"linkstoimage"  => "As páginas seguintes apontam para esta imagem:",
"nolinkstoimage" => "Nenhuma página aponta para esta imagem.",

# Statistics
#
"statistics"    => "Estatísticas",
"sitestats"     => "Estatísticas do Site",
"userstats"     => "Estatística dos usuários",
"sitestatstext" => "Há atualmente um total de <b>$1</b> páginas em nosso banco de dados.
Isto inclui páginas  \"talk\", páginas sobre a Wikipedia, páginas de rascunho, redirecionamentos, e outras que provavelmente não são qualificadas como artigos.
Excluindo estas, há <b>$2</b> páginas que provavelmente são artigos legitimos .<p>
Há um total de <b>$3</b> páginas vistas, e <b>$4</b> edições de página
desde a última atualização do software (Janeiro de 2004).
O que nos leva a aproximadamente <b>$5</b> edições por página, e <b>$6</b> vistas por edição.",
"userstatstext" => "Há atualmente <b>$1</b> usuários registrados.
Destes, <b>$2</b> são administradores (veja $3).",

# Maintenance Page
#
"maintenance"       => "Página de Manutenção",
"maintnancepagetext"    => "Esta página possui diversas ferramentas úteis para a manutenção diária da Wikipedia. Algumas destas funções costumam estressar o banco de dados, então, por favor, não pressione o botão de Recarregar para cada item que você consertar ;-)",
"maintenancebacklink"   => "Voltar para a página de Manutenção",
"disambiguations"   => "Páginas de desambiguamento",
"disambiguationspage"   => "{$wgMetaNamespace}:Links_para_desambiguar_páginas",
"disambiguationstext"   => "Os artigos a seguir apontam para uma <i>página de desambiguamento</i>. Ao invés disso, eles deveriam apontar para um tópico apropriado.<br> Uma página é tratada como disambiguamento se ela é por $1.<br>Links de outros namespaces <i>não</i> estão listados aqui.",
"doubleredirects"   => "Double Redirects",
"doubleredirectstext"   => "<b>Atenção:</b> Esta lista pode conter positivos falsos. O que usualmente significa que há texto adicional com links depois do primeiro #REDIRECT.<br>\nCada linha contem links para o primeiro e segundo redirecionamento, bem como a primeira linha do segundo texto redirecionado , geralmente dando o artigo alvo \"real\" , para onde o primeiro redirecionamento deveria apontar.",
"brokenredirects"   => "Redirecionamentos Quebrados",
"brokenredirectstext"   => "Os seguintes redirecionamentos apontam para um artigo inexistente.",
"selflinks"     => "Páginas com links próprios",
"selflinkstext"     => "As páginas a seguir possuem links para si mesmas, o que não deveria acontecer.",
"mispeelings"           => "Páginas com erros ortográficos",
"mispeelingstext"               => "As páginas a seguir contém erros comuns que estão listados em $1. A ortografia correta deve ser dada (como isto).",
"mispeelingspage"       => "Lista de erros comuns",
"missinglanguagelinks"  => "Missing Language Links",
"missinglanguagelinksbutton"    => "Find missing language links for",
"missinglanguagelinkstext"      => "These articles do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",


# Miscellaneous special pages
#
"orphans"       => "Páginas órfãns",
"lonelypages"   => "Páginas órfãns",
"unusedimages"  => "Imagens não utilizadas",
"popularpages"  => "Páginas populares",
"nviews"        => "$1 visitas",
"wantedpages"   => "Páginas procuradas",
"nlinks"        => "$1 links",
"allpages"      => "Todas as páginas",
"randompage"    => "Página randômica",
"shortpages"    => "Páginas Curtas",
"longpages"     => "Paginas Longas",
"listusers"     => "Lista de Usuários",
"specialpages"  => "Páginas especiais",
"spheading"     => "Páginas especiais para todos os usuários",
"sysopspheading" => "Somente para uso dos SYSOP",
"developerspheading" => "Somente para uso dos desenvolvedores",
"protectpage"   => "Páginas Protegidas",
"recentchangeslinked" => "Páginas relacionadas",
"rclsub"        => "(para páginas linkadas de \"$1\")",
"debug"         => "Debug",
"newpages"      => "Páginas novas",
"ancientpages"      => "Artigos mais antigos",
"intl"      => "Links interlínguas",
"movethispage"  => "Mover esta página",
"unusedimagestext" => "<p>Por favor note que outros websites como
as Wikipedias internacionais podem apontar para uma imagem com uma URL direta, e por isto pode estar aparecendo aqui mesmo estando em uso ativo.",
"booksources"   => "Fontes de livros",
"booksourcetext" => "Segue uma lista de links para outros sites que vendem livros novos e usados , e podem ter informações adicionais sobre livros que você esteja procurando.
A Wikipedia não é afiliada a nenhum destes empreendimentos, e a lista não deve ser construída  como apoio.",
"alphaindexline" => "$1 para $2",

# Email this user
#
"mailnologin"   => "No send address",
"mailnologintext" => "Você deve estar <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autenticado</a>
e ter um e-mail válido em suas <a href=\"" .

  wfLocalUrl( "Special:Preferences" ) . "\">preferências</a>
para poder enviar e-mails para outros usuários.",
"emailuser"     => "Contactar usuário",
"emailpage"     => "Enviar e-mail ao usuário",
"emailpagetext" => "Se este usuário disponibilizou um endereço válido de -mail em suas preferências, o formulário a seguir enviará uma mensagem única.
O endereço de e-mail que você disponibilizou em suas preferências aparecerá como Remetente da mensagem, então, o usuário poderá responder a você diretamente.",
"noemailtitle"  => "Sem endereço de e-mail",
"noemailtext"   => "Este usuário não especificou um endereço de e-mail válido, ou optou por não receber mensagens de outros usuários.",
"emailfrom"     => "De",
"emailto"       => "Para",
"emailsubject"  => "Assunto",
"emailmessage"  => "Mensagem",
"emailsend"     => "Enviar",
"emailsent"     => "E-mail enviado",
"emailsenttext" => "Sua mensagem foi enviada.",

# Watchlist
#
"watchlist"     => "Artigos do meu interesse",
"watchlistsub"  => "(do usuário \"$1\")",
"nowatchlist"   => "Você não está monitorando nenhum artigo.",
"watchnologin"  => "Não está autenticado",
"watchnologintext"  => "Você deve estar <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autenticado</a>
para modificar sua lista de artigos interessantes.",
"addedwatch"    => "Adicionados �  lista",
"addedwatchtext" => "A página \"$1\" foi adicionada a sua <a href=\"" .
  wfLocalUrl( "Especial:Watchlist" ) . "\">lista de artigos de vosso interesse</a>.
Modificações futuras neste artigo e páginas Talk associadas serão listadas aqui,
e a página aparecerá <b>negritada</b> na <a href=\"" .
  wfLocalUrl( "Especial:Recentchanges" ) . "\">lista de mudanças recentes</a> para que
possa pegá-lo com maior facilidade.</p>

<p>Se você quiser remover futuramente o artigo da sua lista monitoramento, clique em  \"Desinteressar-se\" na barra lateral.",
"removedwatch"  => "Removida da lista de observações",
"removedwatchtext" => "A página \"$1\" não é mais de seu interesse e portanto foi removida de sua lista de monitoramento.",
"watchthispage" => "Interessar-se por esta página",
"unwatchthispage" => "Desinteressar-se",
"notanarticle"  => "Não é um artigo",
"watchnochange" => "Nenhum dos itens monitorados foram editados no período exibido.",
"watchdetails" => "($1 páginas monitoradas excluindo-se as páginas talk;
$2 páginas editadas desde data limite;
$3...
<a href='$4'>mostrar e editar a lista completa</a>.)",
"watchmethod-recent" => "checando edições recentes para os artigos monitorados",
"watchmethod-list" => "checando páginas monitoradas de edições recentes",
"removechecked" => "Remover itens selecionados",
"watchlistcontains" => "Sua lista contém $1 páginas.",
"watcheditlist" => "Aqui está uma lista alfabética de sua lista de artigos observados. Marque as caixas dos artigos que você deseja remover e clique no botão 'Remover itens selecionados' na parte de baixo da tela.",
"removingchecked" => "Removendo os itens solicitados de sua lista de monitoramento...",
"couldntremove" => "Não consegui remover o item '$1'...",
"iteminvalidname" => "Problema com item '$1', nome inválido...",
"wlnote" => "Segue as últimas $1 mudanças nas últimas <b>$2</b> horas.",
                                                                                                                                       

# Delete/protect/revert
#
"deletepage"    => "Deletar página",
"confirm"       => "Confirmar",
"excontent" => "conteúdo era:",
"exbeforeblank" => "conteúdo antes de apagar era:",
"exblank" => "página estava vazia",
"confirmdelete" => "Confirmar deleção",
"deletesub"     => "(Apagando \"$1\")",
"historywarning" => "Atenção: A página que você quer deletar tem um histório: ",
"confirmdeletetext" => "Você está  prestes a deletar permanentemente uma página ou imagem junto com todo seu histórico do banco de dados.
Por favor, confirme que você realmente pretende fazer isto, que você compreende as consequências, e que você está fazendo isto em acordo com a [[{$wgMetaNamespace}:Policy| Política da Wkipedia]].",
"confirmcheck"  => "Sim, eu realmente desejo apagar este arquivo.",
"actioncomplete" => "Ação efetuada com sucesso",
"deletedtext"   => "\"$1\" foi deletada.
Veja $2 para um registro de deleções recentes.",
"deletedarticle" => "apagado \"$1\"",
"dellogpage"    => "Deletion_log",
"dellogpagetext" => "Segue uma lista das deleções mais recentes.
Todos os horários mostrados estão no horário do servidor (UTC).
<ul>
</ul>
",
"deletionlog"   => "registro de deleções",
"reverted"      => "Revertido para versão mais nova",
"deletecomment" => "Motivo da deleção",
"imagereverted" => "Reversão para versão mais atual efetuada com sucesso.",
"rollback"      => "Voltar edições",
"rollbacklink"  => "voltar",
"rollbackfailed" => "Rollback falhou",
"cantrollback"  => "Não foi possível reverter a edição; o último contribuidor é o único autor deste artigo.",
"alreadyrolled" => "Não foi possível reverter as edições de  [[$1]]
por [[User:$2|$2]] ([[User talk:$2|Talk]]); alguém o editou ou já o reverteu. 

A última edição foi de  [[User:$3|$3]] ([[User talk:$3|Conversar com ele]]). ",
#   only shown if there is an edit comment
"editcomment" => "O comentário de edição era: \"<i>$1</i>\".", 
"revertpage"    => "Revertido para a última edição por  $1",

# Undelete
"undelete" => "Restaurar páginas deletadas",
"undeletepage" => "Ver e restaurar páginas deletadas",
"undeletepagetext" => "As páginas seguintes foram apagadas mas ainda permanecem no bando de dados e podem ser restauradas. O arquivo pode ser limpo periodicamente.",
"undeletearticle" => "Restaurar artigo deletado",
"undeleterevisions" => "$1 revisões arquivadas",
"undeletehistory" => "Se você restaurar uma página, todas as revisões serão restauradas para o histórico.
Se uma nova página foi criada com o mesmo nome desde a deleção, as revisões restauradas aparecerão primeiro no histórico e a página existente não será automaticamente recolocada.",
"undeleterevision" => "Revisões deletadas de  $1",
"undeletebtn" => "Restaurar!",
"undeletedarticle" => " \"$1\" restaurado",
"undeletedtext"   => "O artigo [[$1]] foi restaurado com sucesso.
Veja [[{$wgMetaNamespace}:Deletion_log]] for a record of recent deletions and restorations.",

# Contributions
#
"contributions" => "Contribuições de usuários",
"mycontris" => "Minhas contribuições",
"contribsub"    => "Para $1",
"nocontribs"    => "Não foram encontradas mudanças com este critério.",
"ucnote"        => "Segue as últimas  <b>$1</b> mudanças nos últimos <b>$2</b> dias do usuário.",
"uclinks"       => "Ver as últimas $1 mudanças; ver os últimos $2 dias.",
"uctop"     => " (topo)" ,

# What links here
#
"whatlinkshere" => "Artigos Relacionado",
"notargettitle" => "Sem alvo",
"notargettext"  => "Você não especificou um alvo ou usuário para performar esta função.",
"linklistsub"   => "(Lista de ligações)",
"linkshere"     => "Os seguintes artigos contém ligações que apontam para cá:",
"nolinkshere"   => "Nenhuma página relaciona-se �  esta.",
"isredirect"    => "página de redirecionamento",

# Block/unblock IP
#
"blockip"       => "Bloquear endereço de IP",
"blockiptext"   => "Utilize o formulário de e-mail �  seguir para bloquear o acesso a escrita de um endereço específico de IP.
Isto só pode ser feito para previnir vandalismo , e em acordo com a  [[{$wgMetaNamespace}:Policy|política da Wikipedia]].
Preencha com um motivo específico (por exemplo, citando páginas que sofreram vandalismo).",
"ipaddress"     => "Endereço de IP",
"ipbreason"     => "Motivo",
"ipbsubmit"     => "Bloquear este endereço",
"badipaddress"  => "O endereço de IP está mal-formado.",
"noblockreason" => "Você deve colocar um motivo.",
"blockipsuccesssub" => "Bloqueio bem sucedido",
"blockipsuccesstext" => "O endereço de IP \"$1\" Foi bloqueado.
<br>Veja [[Special:Ipblocklist|Lista de IP's bloqueados]] para rever os bloqueios.",
"unblockip"     => "Desbloquear endereço de IP",
"unblockiptext" => "Utilize o formulário a seguir para restaurar o acesso a escrita para um endereço de IP previamente bloqueado.",
"ipusubmit"     => "Desbloquear este endereço",
"ipusuccess"    => "Endereço de IP  \"$1\" foi desbloqueado",
"ipblocklist"   => "Lista de IP's bloqueados",
"blocklistline" => "$1, $2 bloqueado $3",
"blocklink"     => "block",
"unblocklink"   => "unblock",
"contribslink"  => "contribs",

# Developer tools
#
"lockdb"        => "Trancar Banco de Dados",
"unlockdb"      => "Destrancar Banco de Dados",
"lockdbtext"    => "Trancar o banco de dados suspenderá a abilidade de todos os usuários de editarem páginas, mudarem suas preferências, lista de monitoração e  outras coisas que requerem mudanças no banco de dados.
Por favor confirme que você realmente pretende fazer isto, e que você vai desbloquear o banco de dados quando sua manutenção estiver completa.",
"unlockdbtext"  => "Desbloquear o banco de dados vai restaurar a abilidade de todos os usuários de editar  artigos,  mudar suas preferências, editar suas listas de monitoramento e outras coisas que requerem mudanças no banco de dados. Por favor , confirme que você realmente pretende fazer isto.",
"lockconfirm"   => "SIM, eu realmente pretendo trancar o banco de dados.",
"unlockconfirm" => "SIM, eu realmente pretendo destrancar o banco de dados.",
"lockbtn"       => "Trancar banco",
"unlockbtn"     => "Destrancar banco",
"locknoconfirm" => "Você não checou a caixa de confirmação.",
"lockdbsuccesssub" => "Tranca bem sucedida",
"unlockdbsuccesssub" => "Destranca bem sucedida",
"lockdbsuccesstext" => "O banco de dados da Wikipedia foi trancado.
<br>Lembre-se de remover a tranca após a manutenção.",
"unlockdbsuccesstext" => "O bando de dados da Wikipedia foi destrancado.",

# SQL query
#
"asksql"        => "SQL query",
"asksqltext"    => "Use o formulário a seguir para fazer uma pesquisa direta no banco de dados.
Use aspas simples ('como isto') para delimitar strings literais.
Isto pode frequentemente sobrecarregar o servidor , sendo assim, por favor use esta função moderadamente .",
"sqlislogged"   => "Por favor, note de todas as pesquisas são registradas (log).",
"sqlquery"      => "Entrar com pesquisa",
"querybtn"      => "Enviar pesquisa",
"selectonly"    => "Pesquisas diferentes de  \"SELECT\" são restritas a desenvolvedores da Wikipedia.",
"querysuccessful" => "Pesquisa bem sucedida",

# Move page
#
"movepage"      => "Mover página",
"movepagetext"  => "Usando o formulário a seguir você poderá renomear uma página , movendo todo o histórico para o novo nome.
O título antigo será transformado num redirecionamento para o novo título.
Links para as páginas antigas não serão mudados; certifique-se de [[Especial:Maintenance| checar]] redirecionamentos  quebrados ou artigos duplos.
Você é responsável por certificar-se que os links continuam apontando para onde eles deveriam apontar.

Note que a página '''não''' será movida se já existe uma página com o novo título, a não ser que ele esteja vazio ou seja um redirecionamento e não tenha histórico de edições. Isto significa que você pode renomear uma págna de volta para o nome que era antigamente se você cometer algum enganoe você não pode sobrescrever uma página.

<b>!!!CUIDADO!!!</b>
Isto pode ser uma mudança drástica e inexperada para uma página popular;
por favor tenha certeza de que compreende as consequencias disto antes de proceder.",
"movepagetalktext" => "A página associada, se existir, será automaticamente movida,  '''a não ser que:'''
*Você esteja movendo uma página estre namespaces,
*Uma página talk (não-vazia) já exista sob o novo nome, ou
*Você não marque a caixa abaixo.

Nestes casos, você terá que mover ou mesclar a página manualmente se desejar .",
"movearticle"   => "Mover página",
"movenologin"   => "Não Autenticado",
"movenologintext" => "Você deve ser um usuário registrado e <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">autenticado</a>
para mover uma página.",
"newtitle"      => "Pata novo título",
"movepagebtn"   => "Mover página",
"pagemovedsub"  => "Moção bem sucedida",
"pagemovedtext" => "Página \"[[$1]]\" movida para \"[[$2]]\".",
"articleexists" => "Uma página com este nome já existe, ou o nome que você escolheu é inválido.
Por favor, escolha outro nome.",
"talkexists"    => "A página em si foi movida com sucesso, porém a página talk não pode ser movida por que já existe uma com este nome. Por favor, mescle-as manualmente.",
"movedto"       => "movido para",
"movetalk"      => "Mover página  \"talk\" também, se aplicável.",
"talkpagemoved" => "A página talk correspondente foi movida com sucesso.",
"talkpagenotmoved" => "A página talk correspondente  <strong>não</strong> foi movida.",

);

include_once( "LanguageUtf8.php" );

class LanguagePt extends LanguageUtf8 {

    function getBookstoreList () {
        global $wgBookstoreListPt;
        return $wgBookstoreListPt;
    }

    function getNamespaces() {
        global $wgNamespaceNamesPt;
        return $wgNamespaceNamesPt;
    }

    function getNsText( $index ) {
        global $wgNamespaceNamesPt;
        return $wgNamespaceNamesPt[$index];
    }

    function getNsIndex( $text ) {
        global $wgNamespaceNamesPt;

        foreach ( $wgNamespaceNamesPt as $i => $n ) {
            if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
        }
        return false;
    }

    function getQuickbarSettings() {
        global $wgQuickbarSettingsPt;
        return $wgQuickbarSettingsPt;
    }

    function getSkinNames() {
        global $wgSkinNamesPt;
        return $wgSkinNamesPt;
    }

    function getMathNames() {
        global $wgMathNamesPt;
        return $wgMathNamesPt;
    }
    
    function getDateFormats() {
        global $wgDateFormatsPt;
        return $wgDateFormatsPt;
    }

    function getUserToggles() {
        global $wgUserTogglesPt;
        return $wgUserTogglesPt;
    }

    function getMonthName( $key )
    {
        global $wgMonthNamesPt;
        return $wgMonthNamesPt[$key-1];
    }
    
    /* by default we just return base form */
    function getMonthNameGen( $key )
    {
        global $wgMonthNamesPt;
        return $wgMonthNamesPt[$key-1];
    }
    
    function getMonthRegex()
    {
        global $wgMonthNamesPt;
        return implode( "|", $wgMonthNamesEn );
    }

    function getMonthAbbreviation( $key )
    {
        global $wgMonthAbbreviationsPt;
        return $wgMonthAbbreviationsPt[$key-1];
    }

    function getWeekdayName( $key )
    {
        global $wgWeekdayNamesPt;
        return $wgWeekdayNamesPt[$key-1];
    }
 
    function timeanddate( $ts, $adj = false )
    {
        return $this->time( $ts, $adj ) . ", " . $this->date( $ts, $adj );
    }

    function getValidSpecialPages()
    {
        global $wgValidSpecialPagesPt;
        return $wgValidSpecialPagesPt;
    }

    function getSysopSpecialPages()
    {
        global $wgSysopSpecialPagesPt;
        return $wgSysopSpecialPagesPt;
    }

    function getDeveloperSpecialPages()
    {
        global $wgDeveloperSpecialPagesPt;
        return $wgDeveloperSpecialPagesPt;
    }

    function getMessage( $key )
    {
        global $wgAllMessagesPt;
        return $wgAllMessagesPt[$key];
    }
}

?>
