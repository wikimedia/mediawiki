<?php
/** Brazilian Portugese (Portuguêsi do Brasil)
 *
 * @addtogroup Language
 *
 * @author Yves Marques Junqueira
 * @author Rodrigo Calanca Nishino
 */

$fallback = 'pt';

$skinNames = array(
	'standard' => 'Padrão',
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Especial',
	NS_MAIN           => '',
	NS_TALK           => 'Discussão',
	NS_USER           => 'Usuário',
	NS_USER_TALK      => 'Usuário_Discussão',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_Discussão',
	NS_IMAGE          => 'Imagem',
	NS_IMAGE_TALK     => 'Imagem_Discussão',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_Discussão',
	NS_TEMPLATE       => 'Predefinição',
	NS_TEMPLATE_TALK  => 'Predefinição_Discussão',
	NS_HELP           => 'Ajuda',
	NS_HELP_TALK      => 'Ajuda_Discussão',
	NS_CATEGORY       => 'Categoria',
	NS_CATEGORY_TALK  => 'Categoria_Discussão'
);
$linkTrail = "/^([a-z]+)(.*)\$/sD";


$messages = array(
# User preference toggles
'tog-underline'               => 'Sublinha links',
'tog-highlightbroken'         => 'Formata links quebrados <a href="" class="new"> como isto </a> (alternative: como isto<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Justifica parágrafos',
'tog-hideminor'               => 'Esconder edições secundárias em mudanças recentes',
'tog-usenewrc'                => 'Mudanças recentes melhoradas(nem todos os navegadores)',
'tog-numberheadings'          => 'Auto-numerar cabeçalhos',
'tog-showtoolbar'             => 'Mostrar barra de edição',
'tog-editondblclick'          => 'Editar páginas quando houver clique duplo(JavaScript)',
'tog-editsection'             => 'Habilitar seção de edição via links [edit]',
'tog-editsectiononrightclick' => 'Habilitar seção de edição por clique <br /> com o botão direito no título da seção (JavaScript)',
'tog-showtoc'                 => 'Mostrar Tabela de Conteúdos<br />(para artigos com mais de 3 cabeçalhos)',
'tog-rememberpassword'        => 'Lembra senha entre sessões',
'tog-editwidth'               => 'Caixa de edição com largura completa',
'tog-watchdefault'            => 'Observa artigos novos e modificados',
'tog-minordefault'            => 'Marca todas as edições como secundárias, por padrão',
'tog-previewontop'            => 'Mostrar Previsão antes da caixa de edição ao invés de ser após',
'tog-nocache'                 => 'Desabilitar caching de página',

# Dates
'sunday'    => 'Domingo',
'monday'    => 'Segunda',
'tuesday'   => 'Terça-Feira',
'wednesday' => 'Quarta-Feira',
'thursday'  => 'Quinta-Feira',
'friday'    => 'Sexta-Feira',
'saturday'  => 'Sábado',
'january'   => 'Janeiro',
'february'  => 'Fevereiro',
'march'     => 'Março',
'april'     => 'Abril',
'may_long'  => 'Maio',
'june'      => 'Junho',
'july'      => 'Julho',
'august'    => 'Agosto',
'september' => 'Setembro',
'october'   => 'Outubro',
'november'  => 'Novembro',
'december'  => 'Dezembro',
'jan'       => 'Jan',
'feb'       => 'Fev',
'mar'       => 'Mar',
'apr'       => 'Abr',
'may'       => 'Mai',
'jun'       => 'Jun',
'jul'       => 'Jul',
'aug'       => 'Ago',
'sep'       => 'Set',
'oct'       => 'Out',
'nov'       => 'Nov',
'dec'       => 'Dez',

# Bits of text used by many pages
'categories'      => 'Page categories',
'pagecategories'  => 'Page categories',
'category_header' => 'Articles in category "$1"',
'subcategories'   => 'Subcategories',

'mainpagetext' => 'Software Wiki instalado com sucesso.',

'about'         => 'Sobre',
'cancel'        => 'Cancela',
'qbfind'        => 'Procura',
'qbbrowse'      => 'Folhear',
'qbedit'        => 'Editar',
'qbpageoptions' => 'Opções de página',
'qbpageinfo'    => 'Informação de página',
'qbmyoptions'   => 'Minhas opções',
'mypage'        => 'Minha página',
'mytalk'        => 'Minha discussão',

'errorpagetitle'    => 'Erro',
'returnto'          => 'Retorna para $1.',
'help'              => 'Ajuda',
'search'            => 'Busca',
'searchbutton'      => 'Busca',
'go'                => 'Vai',
'searcharticle'     => 'Vai',
'history'           => 'Histórico',
'printableversion'  => 'Versão para impressão',
'editthispage'      => 'Editar esta página',
'deletethispage'    => 'Apagar esta página',
'protectthispage'   => 'Proteger esta página',
'unprotectthispage' => 'Desproteger esta página',
'newpage'           => 'Nova página',
'talkpage'          => 'Discutir esta página',
'postcomment'       => 'Post a comment',
'articlepage'       => 'Ver atigo',
'userpage'          => 'Ver página de usuário',
'projectpage'       => 'Ver meta página',
'imagepage'         => 'Ver página de imagens',
'viewtalkpage'      => 'Ver discussões',
'otherlanguages'    => 'Outras línguas',
'redirectedfrom'    => '(Redirecionado de $1)',
'lastmodifiedat'    => 'Está página foi modificada pela última vez em $2, $1.', # $1 date, $2 time
'viewcount'         => 'Esta página foi acessada $1 vezes.',
'protectedpage'     => 'Página protegida',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Sobre a {{SITENAME}}',
'aboutpage'         => 'Project:Sobre',
'bugreports'        => "Reportagem de 'bugs'",
'bugreportspage'    => 'Project:Reportag_Bug',
'copyrightpagename' => 'Direitos Autorais da {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Copyrights',
'currentevents'     => 'Eventos atuais',
'edithelp'          => 'Ajuda de edição',
'edithelppage'      => 'Help:Como_editar_uma_página',
'faq'               => 'FAQ',
'faqpage'           => 'Project:FAQ',
'helppage'          => 'Help:Ajuda',
'mainpage'          => 'Página principal',

'ok'              => 'OK',
'retrievedfrom'   => 'Retirado de  "$1"',
'newmessageslink' => 'novas mensagens',
'editsection'     => 'editar',
'editold'         => 'editar',
'toc'             => 'Conteúdo',
'showtoc'         => 'mostrar',
'hidetoc'         => 'esconder',

# Main script and global functions
'nosuchaction'      => 'Ação não existente',
'nosuchactiontext'  => 'A ação especificada pela URL não é
reconhecida pelo programa da {{SITENAME}}',
'nosuchspecialpage' => 'Não exista esta página especial',
'nospecialpagetext' => 'Você requisitou uma página especial que não é
reconhecida pelo software da {{SITENAME}}.',

# General errors
'error'           => 'Erro',
'databaseerror'   => 'Erro no banco de dados',
'dberrortext'     => 'Um erro de sintaxe de busca no banco de dados ocorreu.
A última tentativa de busca no banco de dados foi:
<blockquote><tt>$1</tt></blockquote>
na função "<tt>$2</tt>".
MySQL retornou o erro "<tt>$3: $4</tt>".',
'dberrortextcl'   => 'Um erro de sintaxe de pesquisa no banco
de dados ocorreu.
A última tentativa de pesquisa no banco de dados foi:
"$1"
com a função"$2".
MySQL retornou o erro "$3: $4".',
'noconnect'       => 'Desculpe! O wiki está passando por algumas
dificuldades técnicas, e não pode contactar o servidor de bando de dados.',
'nodb'            => 'Não foi possível selecionar o banco de dados $1',
'cachederror'     => 'O que segue é uma cópia em cache da página
solicitada, e pode não estar atualizada.',
'readonly'        => 'Banco de dados somente para leitura',
'enterlockreason' => 'Entre com um motivo para trancá-lo, incluindo uma estimativa de quando poderá novamente ser escrito',
'readonlytext'    => 'O Banco-de-dados da {{SITENAME}} está atualmente bloqueado para novos
artigos e outras modificações, provávelmente por uma manutenção rotineira no Bando de Dados,
mais tarde voltará ao normal.

O administrador que fez o bloqueio oferece a seguinte explicação:
<p>$1',
'missingarticle'  => 'O Banco-de-Dados não encontrou o texto de uma página
que deveria ser encontrado, chamado "$1".

<p>Isto é geralmente causado pela procura de um diff antigo ou um histórico que leva a uma página que foi deletada.

<p>Se isto não for o caso, você pode ter encontrado um bug no software.
Por favor, comunique isto ao administrador, tenha nota da URL.',
'internalerror'   => 'Erro Interno',
'filecopyerror'   => 'Não foi possível copiar o arquivo "$1" para "$2".',
'filerenameerror' => 'Não foi possível renomear o arquivo "$1" para "$2"',
'filedeleteerror' => 'Não foi possível deletar o arquivo "$1".',
'filenotfound'    => 'Não foi possível encontrar o arquivo "$1".',
'unexpected'      => 'Valor não esperado: "$1"="$2".',
'formerror'       => 'Erro: Não foi possível enviar o formulário',
'badarticleerror' => 'Esta acção não pode ser performada nesta página.',
'cannotdelete'    => 'Não foi possível excluir página ou imagem especificada. (Ela já pode ter sido deletada por alguém.)',
'badtitle'        => 'Título ruim',
'badtitletext'    => 'O título de página requisitado era inválido, vazio, ou
um link incorreto de inter-linguagem ou título inter-wiki .',
'perfdisabled'    => 'Desculpe! Esta opção foi temporariamente desabilitada
porque tornava o banco de dados lento demais a ponto de impossibilitar o wiki.',

# Login and logout pages
'logouttitle'           => 'Saída de utilizador',
'logouttext'            => 'Você agora não está mais autenticado.
Você pode continuar a usar a {{SITENAME}} anonimamente, ou pode se autenticar
novamente como o mesmo utilizador ou como um utilizador diferente.',
'welcomecreation'       => '<h2>Bem-vindo, $1!</h2><p>Sua conta foi criada.
Não se esqueça de personalizar suas preferências na {{SITENAME}}.',
'loginpagetitle'        => 'Login de usuário',
'yourname'              => 'Seu nome de usuário',
'yourpassword'          => 'Sua senha',
'yourpasswordagain'     => 'Redigite sua senha',
'remembermypassword'    => 'Lembrar de minha senha em outras sessões.',
'loginproblem'          => '<b>Houve um problema com a sua autenticação.</b><br />Tente novamente!',
'alreadyloggedin'       => '<strong>Utilizador $1, você já está autenticado!</strong><br />',
'login'                 => 'Entrar',
'userlogin'             => 'Entrar',
'logout'                => 'Sair',
'userlogout'            => 'sair',
'notloggedin'           => 'Não-logado',
'createaccount'         => 'Criar nova conta',
'createaccountmail'     => 'por e-Mail',
'badretype'             => 'As senhas que você digitou não são iguais.',
'userexists'            => 'O nome de usuário que você digitou já existe. Por favor, escolha um nome diferente.',
'youremail'             => 'Seu e-mail*',
'yournick'              => 'Seu apelido (para assinaturas)',
'loginerror'            => 'Erro de autenticação',
'noname'                => 'Você não colocou um nome de usuário válido.',
'loginsuccesstitle'     => 'Login bem sucedido',
'loginsuccess'          => 'Agora você está logado na {{SITENAME}} como "$1".',
'nosuchuser'            => 'Não há nenhum usuário com o nome "$1".
Verifique sua grafia, ou utilize o formulário a baixo para criar uma nova conta de usuário.',
'wrongpassword'         => 'A senha que você entrou é inválida. Por favor tente novamente.',
'mailmypassword'        => 'Envie uma nova senha por e-mail',
'passwordremindertitle' => 'Lembrador de senhas da {{SITENAME}}',
'passwordremindertext'  => 'Alguém (provavelmente você, do endereço de IP $1)
solicitou que nós lhe enviássemos uma nova senha para login.
A senha para o usuário "$2" é a partir de agora "$3".
Você pode realizar um login e mudar sua senha agora.',
'noemail'               => 'Não há nenhum e-Mail associado ao usuário "$1".',
'passwordsent'          => 'Uma nova senha está sendo enviada para o endereço de e-Mail
registrado para "$1".
Por favor, reconecte-se ao recebê-lo.',

# Edit pages
'summary'              => 'Sumário',
'subject'              => 'Assunto',
'minoredit'            => 'Edição menor',
'watchthis'            => 'Observar este artigo',
'savearticle'          => 'Salvar página',
'preview'              => 'Prever',
'showpreview'          => 'Mostrar Pré-Visualização',
'blockedtitle'         => 'Usuário está bloqueado',
'blockedtext'          => "Seu nome de usuário ou numero de IP foi bloqueado por $1.
O motivo é:<br />''$2''<p>Você pode contactar $1 ou outro
[[{{ns:project}}:administradores|administrador]] para discutir sobre o bloqueio.",
'whitelistedittitle'   => 'Login necessário para edição',
'whitelistedittext'    => 'Você precisa se [[Especial:Userlogin|logar]] para editar artigos.',
'whitelistreadtitle'   => 'Login necessário para leitura',
'whitelistreadtext'    => 'Você precisa se [[Especial:Userlogin|logar]] para ler artigos.',
'whitelistacctitle'    => 'Você não está habilitado a criar uma conta',
'whitelistacctext'     => 'Para ter permissão para se criar uma conta neste Wiki você precisará estar [[Especial:Userlogin|logado]] e ter as permissões apropriadas.',
'accmailtitle'         => 'Senha enviada.',
'accmailtext'          => "A senha de '$1' foi enviada para $2.",
'newarticle'           => '(Novo)',
'newarticletext'       => "Você seguiu um link para um artigo que não existe.
Para criá-lo, começe escrevendo na caixa abaixo
(veja [[{{ns:project}}:Ajuda|a página de ajuda]] para mais informações).
Se você chegou aqui por engano, apenas clique no botão '''voltar''' do seu navegador.",
'anontalkpagetext'     => "---- ''Esta é a página de discussão para um usuário anônimo que não criou uma conta ainda ou que não a usa. Então nós temos que usar o endereço numérico de IP para identificá-lo. Um endereço de IP pode ser compartilhado por vários usuários. Se você é um usuário anônimo e acha irrelevante que os comentários sejam direcionados a você, por favor [[Especial:Userlogin|crie uma conta ou autentique-se]] para evitar futuras confusões com outros usuários anônimos.''",
'noarticletext'        => '(Não há atualmente nenhum texto nesta página)',
'updated'              => '(Atualizado)',
'note'                 => '<strong>Nota:</strong>',
'previewnote'          => 'Lembre-se que isto é apenas uma previsão. O conteúdo ainda não foi salvo!',
'previewconflict'      => 'Esta previsão reflete o texto que está na área de edição acima e como ele aparecerá se você escolher salvar.',
'editing'              => 'Editando $1',
'editinguser'          => 'Editando $1',
'editingsection'       => 'Editando $1 (seção)',
'editingcomment'       => 'Editando $1 (comentário)',
'editconflict'         => 'Conflito de edição: $1',
'explainconflict'      => 'Alguém mudou a página enquanto você a estava editando.
A área de texto acima mostra o texto original.
Suas mudanças são mostradas na área abaixo.
Você terá que mesclar suas modificações no texto existente.
<b>SOMENTE</b> o texto na área acima será salvo quando você pressionar "Salvar página".<br />',
'yourtext'             => 'Seu texto',
'storedversion'        => 'Versão guardada',
'editingold'           => '<strong>CUIDADO: Você está editando uma revisão desatualizada deste artigo.
Se você salvá-lo, todas as mudanças feitas a partir desta revisão serão perdidas.</strong>',
'yourdiff'             => 'Diferenças',
'longpagewarning'      => '<strong>CUIDADO: Esta página tem $1 kilobytes ; alguns browsers podem ter problemas ao editar páginas maiores que 32kb.
Por favor considere quebrar a página em sessões menores.</strong>',
'readonlywarning'      => '<strong>CUIDADO: O banco de dados está sendo bloqueado para manutenção.
No momento não é possível salvar suas edições. Você pode copiar e colar o texto em um arquivo de texto e salvá-lo em seu computador para adicioná-lo ao wiki mais tarde.</strong>',
'protectedpagewarning' => '<strong>CUIDADO: Apenas os usuários com privilégios de sysop podem editar esta página pois ela foi bloqueada. Certifique-se de que você está seguindo o [[Project:Guia_de_páginas_protegidas|guia de páginas protegidas]].</strong>',

# History pages
'revhistory'      => 'Histórico de revisões',
'nohistory'       => 'Não há histórico de revisões para esta página.',
'revnotfound'     => 'Revisão não encontrada',
'revnotfoundtext' => 'A antiga revisão da página que você está procurando não pode ser encontrada.
Por favor verifique a URL que você usou para acessar esta página.',
'loadhist'        => 'Carregando histórico',
'currentrev'      => 'Revisão atual',
'revisionasof'    => 'Revisão de $1',
'cur'             => 'atu',
'next'            => 'prox',
'last'            => 'ult',
'orig'            => 'orig',
'histlegend'      => 'Legenda: (atu) = diferenças da versão atual,
(ult) = diferença da versão precedente, M = edição minoritária',

# Diffs
'difference'  => 'Diferença entre revisões)',
'loadingrev'  => 'carregando a busca por diferenças',
'lineno'      => 'Linha $1:',
'editcurrent' => 'Editar a versão atual desta página',

# Search results
'searchresults'         => 'Buscar resultados',
'searchresulttext'      => 'Para mais informações sobre busca na {{SITENAME}}, veja [[Project:Procurando|Busca na {{SITENAME}}]].',
'searchsubtitle'        => 'Para pedido de busca "[[:$1]]"',
'searchsubtitleinvalid' => 'Para pedido de busca "$1"',
'badquery'              => 'Linha de busca incorretamente formada',
'badquerytext'          => 'Nós não pudemos processar seu pedido de busca.
Isto acoenteceu provavelmente porque você tentou procurar uma palavra de menos que três letras, coisa que o software ainda não consegue realizar. Isto também pode ter ocorrido porque você digitou incorretamente a expressão, por
exemplo: "peixes <strong>and and</strong> scales".
Por favor realize ouro pedido de busca.',
'matchtotals'           => 'A pesquisa "$1" resultou $2 títulos de artigos
e $3 artigos com o texto procurado.',
'noexactmatch'          => "'''Não existe uma página com o título \"\$1\".''' Você pode [[:\$1|criar tal página]].",
'titlematches'          => 'Resultados nos títulos dos artigos',
'notitlematches'        => 'Sem resultados nos títulos dos artigos',
'textmatches'           => 'Resultados nos textos dos artigos',
'notextmatches'         => 'Sem resultados nos textos dos artigos',
'prevn'                 => 'anterior $1',
'nextn'                 => 'próximo $1',
'viewprevnext'          => 'Ver ($1) ($2) ($3).',
'showingresults'        => 'Mostrando os próximos <b>$1</b> resultados começando com #<b>$2</b>.',
'showingresultsnum'     => 'Mostrando <b>$3</b> resultados começando com #<b>$2</b>.',
'nonefound'             => '<strong>Nota</strong>: pesquisas mal sucedidas são geralmente causadas devido o uso de palavras muito comuns como "tem" e "de",
que não são indexadas, ou pela especificação de mais de um termo (somente as páginas contendo todos os termos aparecerão nos resultados).',
'powersearch'           => 'Pesquisa',
'powersearchtext'       => '
Procurar nos namespaces :<br />
$1<br />
$2 Lista redireciona &nbsp; Procura por $3 $9',
'blanknamespace'        => '(Principal)',

# Preferences page
'preferences'           => 'Preferências',
'prefsnologin'          => 'Não autenticado',
'prefsnologintext'      => 'Você precisa estar [[Special:Userlogin|autenticado]]
para definir suas preferências.',
'prefsreset'            => 'Preferências foram reconfiguradas.',
'qbsettings'            => 'Configurações da Barra Rápida',
'changepassword'        => 'Mudar senha',
'skin'                  => 'Aparência(Skin)',
'math'                  => 'Renderização matemática',
'dateformat'            => 'Formato da Data',
'math_failure'          => 'Falhou ao checar gramática(parse)',
'math_unknown_error'    => 'erro desconhecido',
'math_unknown_function' => 'função desconhecida',
'math_lexing_error'     => 'erro léxico',
'math_syntax_error'     => 'erro de síntaxe',
'saveprefs'             => 'Salvar preferências',
'resetprefs'            => 'Redefinir preferências',
'oldpassword'           => 'Senha antiga',
'newpassword'           => 'Nova senha',
'retypenew'             => 'Redigite a nova senha',
'textboxsize'           => 'Tamanho da caixa de texto',
'rows'                  => 'Linhas',
'columns'               => 'Colunas',
'searchresultshead'     => 'Configurar resultados de pesquisas',
'resultsperpage'        => 'Resultados por página',
'contextlines'          => 'Linhas por resultados',
'contextchars'          => 'Letras de contexto por linha',
'recentchangescount'    => 'Número de títulos em Mudanças Recentes',
'savedprefs'            => 'Suas preferências foram salvas.',
'timezonetext'          => 'Entre com o número de horas que o seu horário local difere do horário do servidor (UTC).',
'localtime'             => 'Display de hora local',
'timezoneoffset'        => 'Offset',
'servertime'            => 'Horário do servidor é',
'guesstimezone'         => 'Colocar no navegador',
'defaultns'             => 'Procurar nestes namespaces por padrão:',

# Recent changes
'recentchanges'     => 'Mudanças Recentes',
'recentchangestext' => 'Veja as mais novas mudanças na {{SITENAME}} nesta página.
[[{{ns:project}}:Bem Vindo,_novatos|Bem Vindo, novatos]]!
Por favor, dê uma olhada nestas páginas: [[{{ns:project}}:FAQ|FAQ da {{SITENAME}}]],
[[{{ns:project}}:Políticas e Normas| Política da {{SITENAME}}]]
(especialmente [[{{ns:project}}:Convenções de nomenclatura|convenções de nomenclatura]],
[[{{ns:project}}:Ponto de vista neutro|Ponto de vista neutro]]),
e [[{{ns:project}}:Most common {{SITENAME}} faux pas|most common {{SITENAME}} faux pas]].

Se você quer ver a {{SITENAME}} crescer, é muito importante que você não adicione material restrito por outras [[{{ns:project}}:Copyrights|copyrights]].
Um problema legal poderia realmente prejudicar o projeto de maneira que pedimos, por avor, não faça isso.',
'rcnote'            => 'Abaixo estão as últimas <strong>$1</strong> alterações nos últimos <strong>$2</strong> dias.',
'rcnotefrom'        => 'Abaixo estão as mudanças desde <b>$2</b> (até <b>$1</b> mostradas).',
'rclistfrom'        => 'Mostrar as novas alterações a partir de $1',
'rclinks'           => 'Mostrar as últimas $1 mudanças nos últimos $2 dias; $3 edições minoritárias',
'diff'              => 'dif',
'hist'              => 'hist',
'hide'              => 'esconde',
'show'              => 'mostra',
'minoreditletter'   => 'M',
'newpageletter'     => 'N',

# Recent changes linked
'recentchangeslinked' => 'Páginas relacionadas',

# Upload
'upload'            => 'Carregar arquivo',
'uploadbtn'         => 'Carregar arquivo',
'reupload'          => 'Re-carregar',
'reuploaddesc'      => 'Retornar ao formulário de Uploads.',
'uploadnologin'     => 'Não autenticado',
'uploadnologintext' => 'Você deve estar [[Special:Userlogin|autenticado]]
para carregar arquivos.',
'uploaderror'       => 'Erro ao Carregar',
'uploadlog'         => 'log de uploads',
'uploadlogpage'     => 'Log_de_Uploads',
'uploadlogpagetext' => 'Segue uma lista dos uploads mais recentes.
Todas as datas mostradas são do servidor (UTC).',
'filename'          => 'Nome do arquivo',
'filedesc'          => 'Sumário',
'uploadedfiles'     => 'Arquivos carregados',
'badfilename'       => 'O nome da imagem mudou para "$1".',
'successfulupload'  => 'Carregamento efetuado com sucesso',
'uploadwarning'     => 'Aviso de Upload',
'savefile'          => 'Salvar arquivo',
'uploadedimage'     => '"[[$1]]" carregado',

# Image list
'imagelist'           => 'Lista de Imagens',
'imagelisttext'       => 'A seguir uma lista de $1 imagens organizadas $2.',
'getimagelist'        => 'buscando lista de imagens',
'ilsubmit'            => 'Procura',
'showlast'            => 'Mostrar as  $1 imagens organizadas $2.',
'byname'              => 'por nome',
'bydate'              => 'por data',
'bysize'              => 'por tamanho',
'imgdelete'           => 'del',
'imgdesc'             => 'desc',
'imglegend'           => 'Legenda: (desc) = mostrar/editar descrição de imagem.',
'imghistory'          => 'Histórico das imagens',
'revertimg'           => 'rev',
'deleteimg'           => 'del',
'deleteimgcompletely' => 'del',
'imghistlegend'       => 'Legenda: (cur) = esta é a imagem atual, (del) = deletar
esta versão antiga, (rev) = reverter para esta versão antiga.
<br /><i>Clique em data para ver das imagens carregadas nesta data</i>.',
'imagelinks'          => 'Links das imagens',
'linkstoimage'        => 'As páginas seguintes apontam para esta imagem:',
'nolinkstoimage'      => 'Nenhuma página aponta para esta imagem.',

# Statistics
'statistics'    => 'Estatísticas',
'sitestats'     => 'Estatísticas do Site',
'userstats'     => 'Estatística dos usuários',
'sitestatstext' => 'Há atualmente um total de <b>$1</b> páginas em nosso banco de dados.
Isto inclui páginas  "talk", páginas sobre a {{SITENAME}}, páginas de rascunho, redirecionamentos, e outras que provavelmente não são qualificadas como artigos.
Excluindo estas, há <b>$2</b> páginas que provavelmente são artigos legitimos .<p>
Há um total de <b>$3</b> páginas vistas, e <b>$4</b> edições de página
desde a última atualização do software (Janeiro de 2004).
O que nos leva a aproximadamente <b>$5</b> edições por página, e <b>$6</b> vistas por edição.',
'userstatstext' => 'Há atualmente <b>$1</b> usuários registrados.
Destes, <b>$2</b> são administradores (veja $3).',

'disambiguations'     => 'Páginas de desambiguamento',
'disambiguationspage' => '{{ns:project}}:Links_para_desambiguar_páginas',

'doubleredirects'     => 'Double Redirects',
'doubleredirectstext' => '<b>Atenção:</b> Esta lista pode conter positivos falsos. O que usualmente significa que há texto adicional com links depois do primeiro #REDIRECT.<br />
Cada linha contem links para o primeiro e segundo redirecionamento, bem como a primeira linha do segundo texto redirecionado , geralmente dando o artigo alvo "real" , para onde o primeiro redirecionamento deveria apontar.',

'brokenredirects'     => 'Redirecionamentos Quebrados',
'brokenredirectstext' => 'Os seguintes redirecionamentos apontam para um artigo inexistente.',

# Miscellaneous special pages
'nbytes'           => '$1 bytes',
'nlinks'           => '$1 links',
'nviews'           => '$1 visitas',
'lonelypages'      => 'Páginas órfãns',
'unusedimages'     => 'Imagens não utilizadas',
'popularpages'     => 'Páginas populares',
'wantedpages'      => 'Páginas procuradas',
'allpages'         => 'Todas as páginas',
'randompage'       => 'Página aleatória',
'shortpages'       => 'Páginas Curtas',
'longpages'        => 'Paginas Longas',
'listusers'        => 'Lista de Usuários',
'specialpages'     => 'Páginas especiais',
'spheading'        => 'Páginas especiais para todos os usuários',
'rclsub'           => '(para páginas linkadas de "$1")',
'newpages'         => 'Páginas novas',
'ancientpages'     => 'Artigos mais antigos',
'intl'             => 'Links interlínguas',
'movethispage'     => 'Mover esta página',
'unusedimagestext' => '<p>Por favor note que outros websites como
as Wikipédias internacionais podem apontar para uma imagem com uma URL direta, e por isto pode estar aparecendo aqui mesmo estando em uso ativo.',

# Book sources
'booksources' => 'Fontes de livros',

'alphaindexline' => '$1 para $2',

# E-mail user
'mailnologin'     => 'Sem endereço ed envio',
'mailnologintext' => 'Você deve estar [[Special:Userlogin|autenticado]]
e ter um e-mail válido em suas [[Special:Preferences|preferências]]
para poder enviar e-mails para outros usuários.',
'emailuser'       => 'Contactar usuário',
'emailpage'       => 'Enviar e-mail ao usuário',
'emailpagetext'   => 'Se este usuário disponibilizou um endereço válido de e-mail em suas preferências, o formulário a seguir enviará uma mensagem única.
O endereço de e-mail que você disponibilizou em suas preferências aparecerá como remetente da mensagem, então, o usuário poderá responder a você diretamente.',
'noemailtitle'    => 'Sem endereço de e-mail',
'noemailtext'     => 'Este usuário não especificou um endereço de e-mail válido, ou optou por não receber mensagens de outros usuários.',
'emailfrom'       => 'De',
'emailto'         => 'Para',
'emailsubject'    => 'Assunto',
'emailmessage'    => 'Mensagem',
'emailsend'       => 'Enviar',
'emailsent'       => 'E-mail enviado',
'emailsenttext'   => 'Sua mensagem foi enviada.',

# Watchlist
'watchlist'          => 'Artigos do meu interesse',
'mywatchlist'        => 'Artigos do meu interesse',
'nowatchlist'        => 'Você não está monitorando nenhum artigo.',
'watchnologin'       => 'Não está autenticado',
'watchnologintext'   => 'Você deve estar [[Special:Userlogin|autenticado]]
para modificar a lista de artigos do seu interesse.',
'addedwatchtext'     => 'A página "$1" foi adicionada à [[Special:Watchlist|lista de artigos do seu interesse]].
Modificações futuras neste artigo e páginas de discussão associadas serão listadas aqui,
e a página aparecerá <b>em negrito</b> na [[Special:Recentchanges|lista de mudanças recentes]] para que
possa achá-la com maior facilidade.

Se você quiser remover futuramente o artigo da sua lista de artigos vigiados, clique em  "Desinteressar-se" na barra lateral.',
'removedwatch'       => 'Removida da lista de monitoramento',
'removedwatchtext'   => 'A página "$1" não é mais de seu interesse e portanto foi removida de sua lista de monitoramento.',
'watchthispage'      => 'Interessar-se por esta página',
'unwatchthispage'    => 'Desinteressar-se',
'notanarticle'       => 'Não é um artigo',
'watchnochange'      => 'Nenhum dos itens monitorados foram editados no período exibido.',
'watchlist-details'  => '$1 páginas monitoradas excluindo-se as páginas de discussão.',
'watchmethod-recent' => 'checando edições recentes para os artigos monitorados',
'watchmethod-list'   => 'checando páginas monitoradas de edições recentes',
'watchlistcontains'  => 'Sua lista contém $1 páginas.',
'iteminvalidname'    => "Problema com item '$1', nome inválido...",
'wlnote'             => 'Segue as últimas $1 mudanças nas últimas <b>$2</b> horas.',

# Delete/protect/revert
'deletepage'        => 'Deletar página',
'confirm'           => 'Confirmar',
'excontent'         => "conteúdo era: '$1'",
'exbeforeblank'     => "conteúdo antes de apagar era: '$1'",
'exblank'           => 'página estava vazia',
'confirmdelete'     => 'Confirmar deleção',
'deletesub'         => '(Apagando "$1")',
'historywarning'    => 'Atenção: A página que você quer deletar tem um histórico:',
'confirmdeletetext' => 'Você está  prestes a deletar permanentemente uma página ou imagem junto com todo seu histórico do banco de dados.
Por favor, confirme que você realmente pretende fazer isto, que você compreende as consequências, e que você está fazendo isto em acordo com a [[{{MediaWiki:Policy-url}}|Política da {{SITENAME}}]].',
'actioncomplete'    => 'Ação efetuada com sucesso',
'deletedtext'       => '"$1" foi deletada.
Veja $2 para um registro de deleções recentes.',
'deletedarticle'    => 'apagado "$1"',
'dellogpage'        => 'Registro de eliminação',
'dellogpagetext'    => 'Segue uma lista das deleções mais recentes.
Todos os horários mostrados estão no horário do servidor (UTC).',
'deletionlog'       => 'registro de deleções',
'reverted'          => 'Revertido para versão mais nova',
'deletecomment'     => 'Motivo da deleção',
'imagereverted'     => 'Reversão para versão mais atual efetuada com sucesso.',
'rollback'          => 'Voltar edições',
'rollbacklink'      => 'voltar',
'rollbackfailed'    => 'A reversão falhou',
'cantrollback'      => 'Não foi possível reverter a edição; o último contribuidor é o único autor deste artigo.',
'alreadyrolled'     => 'Não foi possível reverter as edições de  [[:$1]]
por [[User:$2|$2]] ([[User talk:$2|discussão]]); alguém o editou ou já o reverteu.

A última edição foi de  [[User:$3|$3]] ([[User talk:$3|Conversar com ele]]).',
'editcomment'       => 'O comentário de edição era: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'        => 'Revertido para a última edição por  $1',

# Undelete
'undelete'          => 'Restaurar páginas deletadas',
'undeletepage'      => 'Ver e restaurar páginas deletadas',
'undeletepagetext'  => 'As páginas seguintes foram apagadas mas ainda permanecem no bando de dados e podem ser restauradas. O arquivo pode ser limpo periodicamente.',
'undeleterevisions' => '$1 revisões arquivadas',
'undeletehistory'   => 'Se você restaurar uma página, todas as revisões serão restauradas para o histórico.
Se uma nova página foi criada com o mesmo nome desde a deleção, as revisões restauradas aparecerão primeiro no histórico e a página existente não será automaticamente recolocada.',
'undeletebtn'       => 'Restaurar!',
'undeletedarticle'  => ' "$1" restaurado',

# Contributions
'contributions' => 'Contribuições de usuários',
'mycontris'     => 'Minhas contribuições',
'contribsub2'   => 'Para $1 ($2)',
'nocontribs'    => 'Não foram encontradas mudanças com este critério.',
'ucnote'        => 'Segue as últimas  <b>$1</b> mudanças nos últimos <b>$2</b> dias do usuário.',
'uclinks'       => 'Ver as últimas $1 mudanças; ver os últimos $2 dias.',
'uctop'         => ' (topo)',

# What links here
'whatlinkshere' => 'Artigos Relacionado',
'notargettitle' => 'Sem alvo',
'notargettext'  => 'Você não especificou um alvo ou usuário para performar esta função.',
'linklistsub'   => '(Lista de links)',
'linkshere'     => 'Os seguintes artigos contém links que apontam para cá:',
'isredirect'    => 'página de redirecionamento',

# Block/unblock
'blockip'            => 'Bloquear endereço de IP',
'ipaddress'          => 'Endereço de IP',
'ipbreason'          => 'Motivo',
'ipbsubmit'          => 'Bloquear este endereço',
'badipaddress'       => 'O endereço de IP está mal-formado.',
'blockipsuccesssub'  => 'Bloqueio bem sucedido',
'blockipsuccesstext' => 'O endereço de IP "$1" Foi bloqueado.
<br />Veja [[Special:Ipblocklist|Lista de IP\'s bloqueados]] para rever os bloqueios.',
'unblockip'          => 'Desbloquear endereço de IP',
'unblockiptext'      => 'Utilize o formulário a seguir para restaurar o acesso a escrita para um endereço de IP previamente bloqueado.',
'ipusubmit'          => 'Desbloquear este endereço',
'ipblocklist'        => "Lista de IP's bloqueados",
'blocklistline'      => '$1, $2 bloqueado $3 ($4)',
'blocklink'          => 'bloquear',
'unblocklink'        => 'desbloquear',
'contribslink'       => 'contribs',

# Developer tools
'lockdb'              => 'Trancar Banco de Dados',
'unlockdb'            => 'Destrancar Banco de Dados',
'lockdbtext'          => 'Trancar o banco de dados suspenderá a habilidade de todos os usuários de editarem páginas, mudarem suas preferências, listas de monitoramento e outras coisas que requerem mudanças no banco de dados.
Por favor confirme que você realmente pretende fazer isto, e que você vai desbloquear o banco de dados quando sua manutenção estiver completa.',
'unlockdbtext'        => 'Desbloquear o banco de dados vai restaurar a habilidade de todos os usuários de editar artigos, mudar suas preferências, editar suas listas de monitoramento e outras coisas que requerem mudanças no banco de dados. Por favor, confirme que você realmente pretende fazer isto.',
'lockconfirm'         => 'SIM, eu realmente pretendo trancar o banco de dados.',
'unlockconfirm'       => 'SIM, eu realmente pretendo destrancar o banco de dados.',
'lockbtn'             => 'Trancar banco',
'unlockbtn'           => 'Destrancar banco',
'locknoconfirm'       => 'Você não checou a caixa de confirmação.',
'lockdbsuccesssub'    => 'Tranca bem sucedida',
'unlockdbsuccesssub'  => 'Destranca bem sucedida',
'lockdbsuccesstext'   => 'O banco de dados da {{SITENAME}} foi trancado.
<br />Lembre-se de remover a tranca após a manutenção.',
'unlockdbsuccesstext' => 'O bando de dados da {{SITENAME}} foi destrancado.',

# Move page
'movepage'         => 'Mover página',
'movepagetext'     => "Usando o formulário a seguir você poderá renomear uma página, movendo todo o histórico para o novo nome.
O título antigo será transformado num redirecionamento para o novo título.
Links para as páginas antigas não serão mudados; certifique-se de checar redirecionamentos  quebrados ou artigos duplos.
Você é responsável por certificar-se que os links continuam apontando para onde eles deveriam apontar.

Note que a página '''não''' será movida se já existe uma página com o novo título, a não ser que ele esteja vazio ou seja um redirecionamento e não tenha histórico de edições. Isto significa que você pode renomear uma págna de volta para o nome que era antigamente se você cometer algum enganoe você não pode sobrescrever uma página.

<b>!!!CUIDADO!!!</b>
Isto pode ser uma mudança drástica e inexperada para uma página popular;
por favor tenha certeza de que compreende as consequencias disto antes de proceder.",
'movepagetalktext' => "A página associada, se existir, será automaticamente movida,  '''a não ser que:'''
*Você esteja movendo uma página estre namespaces,
*Uma página talk (não-vazia) já exista sob o novo nome, ou
*Você não marque a caixa abaixo.

Nestes casos, você terá que mover ou mesclar a página manualmente se desejar .",
'movearticle'      => 'Mover página',
'movenologin'      => 'Não Autenticado',
'movenologintext'  => 'Você deve ser um usuário registrado e [[Special:Userlogin|autenticado]]
para mover uma página.',
'newtitle'         => 'Pata novo título',
'movepagebtn'      => 'Mover página',
'pagemovedsub'     => 'Moção bem sucedida',
'articleexists'    => 'Uma página com este nome já existe, ou o nome que você escolheu é inválido.
Por favor, escolha outro nome.',
'talkexists'       => 'A página em si foi movida com sucesso, porém a página de discussão não pode ser movida por que já existe uma com este nome. Por favor, mescle-as manualmente.',
'movedto'          => 'movido para',
'movetalk'         => 'Mover página  de discussão também, se aplicável.',
'talkpagemoved'    => 'A página de discussão correspondente foi movida com sucesso.',
'talkpagenotmoved' => 'A página de discussão correspondente  <strong>não</strong> foi movida.',

# Math options
'mw_math_png'    => 'Sempre renderizar PNG',
'mw_math_simple' => 'HTML se for bem simples e PNG',
'mw_math_html'   => 'HTML se possível ou então PNG',
'mw_math_source' => 'Deixar como TeX (para navegadores em modo texto)',
'mw_math_modern' => 'Recomendado para navegadores modernos',
'mw_math_mathml' => 'MathML',

);


