<?php
/* global LocalStrings['uage PT v.3.5.3. 02/10/2018
	
	NOTA: NON MODIFICARE LE VOCI IN QUESTI FILE DI LINGUA "GLOBAL" PRESENTI IN QUESTA CARTELLA - LocalStrings['uages
 	SE OCCORRONO MODIFICHE MEGLIO COPIARE IL ' => '....',) E INSERIRLO NEI FILE LINGUA COORISPONDENTI PRESENTI NELLA CARTELLA - pages/default/LocalStrings['
*/
 
/* GENERALI */
$localStrings = array_merge($localStrings,array
    (
	'user' => 'pt',
	'label' => 'portoghese',
	'label_abb' => 'por',
	'Config::$dbTablePrefix' => '_pt',
	'decimal separator' => ".",
	'data format' => "YYYY-dd-mm",
	'data format string' => "{STRINGMONTH} {DAY}, {YEAR}",
	'charset' => 'pt-PT',
	'charset date' => 'pt_PT',

	'lista giorni' => array('N.D.','domingo','segunda-feira','terça-feira','quarta-feira','quinta-feira','sexta-feira','sabado'),
	'lista mesi' => array('N.D.','janeiro','fevereiro','março','april','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'),

	'lingue' => "idiomas",
	'lingua' => "idioma",
	'lista lingue' => array
	(
		'it' => "italian",
		'en' => "inglês",
		'es' => "espanhol",
		'fr' => "francês",
		'de' => "alemão",
		'pt' => "português",
		'ru' => "russo",
		'hr' => "croata",
		'cn' => "chinês",
		'el' => "grego",
		'pl' => "polacco",
	),

	'lista lingue abbreviate' => array
	(
		'it' => "ita",
		'en' => "ing",
		'es' => "esp",
		'fr' => "fra",
		'de' => "ale",
		'pt' => "por",
		'ru' => "rus",
		'hr' => "cro",
		'cn' => "chi",
		'el' => "gre",
		'pl' => "pol",
	),

	'home' => "casa",
	'menù' => "cardápio",
	'privacy policy' => "política de privacidade",
	'href-title privacy policy' => "Leia a política de privacidade",
	'termini e condizioni' => "termos e condições",
	'Termini e Condizioni' => "Termos e Condições",
	'href-title termini e condizioni' => "Leia os termos e condições",
	'cookie policy' => "Política de cookie",
	'href-title cookie policy' => "Leia a política de cookie",
	'faq' => "faq",
	'href-title faq' => "Leia as nossas FAQs",
	'link utili' => "links úteis",
	'notizia' => "notícia",
	'notizie' => "notícias",
	'dettaglio notizia' => "detalhe da notícia",
	'inserita il' => "inserido em",
	'ultime notizie' => "últimas notícias",
	'domande' => "perguntas",
	'risposte' => "respostas",
	'domanda' => "pergunta",
	'risposta' => "resposta",
	'portfolio' => "carteira",
	'sito web' => "website",
	'data' => "data",
	'nota' => "nota",
	'note' => "nota",
	'lavoro' => "trabalho",
	'lavori' => "trabalhos",
	'descrizioni' => "descrições",
	'descrizione' => "descrição",
	'descrizione lavoro' => "descrição trabalho",
	'descrizione lavori' => "descrição trabalhos",
	'dettaglio lavoro' => "detalhe trabalho",
	'dettaglio lavori' => "detalhe trabalhos",
	'dettagli lavoro' => "detalhes trabalho",
	'dettagli lavori' => "detalhes trabalhos",
	'altro lavoro' => "outros trabalho",
	'altri lavori' => "outras trabalhos",
	'galleria fotografica' => "galeria de fotos",
	'gallerie fotografiche' => "galerias de fotos",
	'galleria' => "galeria",
	'gallerie' => "galerias",
	'video' => "video",
	'e' => "e",
	'immagine' => "imagen",
	'immagini' => "imagens",
	'di' => "de",
	'il' => "on",
	'da' => "por",
	'dal' => "de",
	'del' => "de",
	'al' => "a",
	'per' => "para",
	'dal sito' => "do site",
	'del sito' => "do site",
	'tutto' => "todos",
	'tutti' => "todos",
	'tutte' => "todos",
	'invio' => "envio",
	'invia' => "enviar",
	'accetto' => "aceitável",
	'accetta' => "aceitar",
	'conferma' => "confirmar",
	'confermo' => "confirmar",
	'leggi' => "ler",
	'leggi tutto' => "ler tudo",
	'chiudi' => "fechar",
	'chiudi il popup' => "fechar popup",
	'chiudi la pagina' => "fechar página",
	'seleziona' => "selecionar",
	'selezionare' => "selecionar",
	'selezionato' => "selecionado",
	'selezionati' => "selecionado",
	'precedente' => "previous",
	'successivo' => "próximo",
	'successiva' => "próximo",
	'prossimo' => "próximo",
	'prossima' => "próximo",
	'avanti' => "forward",
	'indietro' => "voltar",
	'pagina' => "página",
	'pagine' => "páginas",
	'titolo' => "título",
	'titoli' => "títulos",
	'vedi tutto' => "ver tudo",
	'continua' => "continuar",
	'nascondi' => "ocultar",
	'mostra' => "mostrar",
	'spiegazioni' => "explicações",
	'cerca' => "pesquisa",
	'ricerca' => "pesquisa",
	'risultati della ricerca' => "resultados da pesquisa",
	'la ricerca per parole %{SEARCHSTRING%} ha trovato %{ITEMSNUMBER%} articoli.' => "pesquisar por palavras   &#171, %{SEARCHSTRING%} &#187,  encontrado itens %{ITEMSNUMBER%}.",
	'modifica' => "modificar",
	'cancella' => "apagar",
	'visualizza' => "exibe",
	'annulla' => "cancela",
	'torna indietro' => "volte",
	'newsletter' => "newsletter",
	'ultimo aggiornamento' =>  "última atualização",
	'aggiornata il' =>  "atualizar em",
	'aggiornato il' =>  "atualizar em",
	'immagine di default' => "imagem padrão",
	'filtra per' => "filtrar por",
	'vai al sito' => "ir para o site",
	'qui' => "aqui",
	'salva cambiamenti' => "salvar",
	'salva cambiamenti' => "salvar alterações",
	'evento' => "evento",
	'eventi' => "eventos",
	'messaggio' => "mensagem",
	'contatto' => "contato",
	'contatti' => "contatos",
	'inviaci un messaggio' => "envie-nos uma mensagem",
	'invia un messaggio' => "enviar uma mensagem",
	'invia messaggio' => "enviar mensagem",

/* UTENTE E/O CLIENTE */
	'anagrafica' => "detalhes do usuário",
	'nome' => "nome",
	'cognome' => "sobrenome",
	'indirizzo' => "endereço",
	'indirizzo fatturazione' => "endereço de faturamento",
	'cap' => "Código Z.I.P.",
	'città' => "cidade",
	'provincia' => "país",
	'sigla provincia' => "país ode",
	'solo italia' => "apenas itália",
	'stato' => "estado",
	'telefono' => "telefone",
	'telefono abbreviato' => "tel",
	'cellulare' => "móvel",
	'indirizzo email' => "endereço de e-mail",
	'email' => "e-mail",
	'fax' => "fax",
	'avatar' => "avatar",
	'altre comunicazioni' => "outras comunicações",
	'partita iva' => "partita iva",
	'Partita IVA (solo per Italia)' => "Partita IVA <br> (apenas para a Itália)",
	'P. IVA ' => "P. IVA",
	'IVA' => "IVA",
	'VAT' => "IVA",
	'VAT (se stato CEE diverso da Italia)' => "IVA (em caso de Estado da CEE)",
	'codice fiscale' => "código tributário",
	'cliente' => "cliente",
	'codice cliente' => "código do cliente",
	'cognome e nome o denominazione della ditta' => "nome ou nome da empresa",
	'registrazione' => "gravação",
	'dati utente' => "dados do usuário",
	'dati cliente' => "dados do cliente",
	'banca' => "banco",
	'agenzia' => "agência",
	'(solo per Italia)' => "(apenas para a Itália)",
	'utente' => "usuário",
	'utenti' => "usuários",
	'nome utente' => "username",
	'password' => "senha",
	'password di controllo' => "senha para correspondência",
	'recupera nome utente' => "recuperar nome de usuário",
	'recupera password' => "recuperar a senha",
	'recupera nome utente testo intro' => "Depois de preencher os campos corretamente, o sistema pesquisará o nome de usuário associado ao endereço de e-mail e você será enviado. <br> Se após o término do procedimento você não receberá o Verifique se ele não está no filtro anti-spam (se houver) ou entre em contato com o administrador. ",
	'recupera password testo intro' => "Depois de preencher os campos corretamente o sistema irá gerar uma senha aleatória que será enviada para o e-mail mostrado no perfil.<br>Se após o término do procedimento você não receberá Verificar se ele não está no filtro anti-spam (se houver) ou entre em contato com o administrador. ",

	'iscriviti' => "subscrever",
	'registrati' => "registrado",
	'prego registrati' => "por favor, inscreva-se",
	'registra un nuovo account' => "registrar uma nova conta",
	'Già registrato? Clicca %HERE% por loggarti con il tuo account ' => "Já está registado? Clique em %HERE% para iniciar sessão com a sua conta",
	'Non hai un account? Clicca %HERE% per registrarti ' => "Você não tem uma conta? Clique %HERE% para registrar.",
	'login' => "login",
	'loggati' => "login",
	'loggati con il tuo account' => "efetue login com sua conta",
	'prego loggati' => "por favor, faça login",
	'resta loggato' => "permanece registrado",
	'sloggati' => "logout",
	'sloggati dal sistema' => "logout do sistema",
	'profilo' => "perfil",
	'account' => "conta",
	'account dimenticato' => "esqueci a conta",
	'Ho letto %HERE%' => "Eu li %HERE%",
	'modifica profilo' => "editar perfil",
	'modifica questo profilo' => "editar este perfil",
	'modifica account' => "editar conta",
	'modifica questo account' => "editar esta conta",
	'modifica password' => "editar senha",
	'modifica la password' => "editar senha",
	'modifica la password di accesso' => "senha de acesso de edição",
	'modifica opzioni pagamento' => "editar opções de pagamento",
	'modifica le opzioni pagamento' => "editar as opções de pagamento",

/* SHOP */
	'articoli' => "itens",
	'articolo' => "item",
	'descrizione articolo' => "Descrição do produto",
	'categoria' => "categoria",
	'categorie' => "categorias",
	'descrizione prodotto' => "descrição do produto",
	'codice' => "código",
	'codice articolo' => "código",
	'prezzo' => "preço",
	'prezzi' => "preços",
	'prezzo netto' => "preço líquido",
	'prezzo lordo' => "preço bruto",
	'importo' => "quantidade",
	'importi' => "montantes",
	'importo netto' => "montante líquido",
	'importo lordo' => "montante bruto",
	'importo totale' => "montante total",
	'sconto' => "desconto",
	'sconti' => "descontos",
	'totale' => "total",
	'totali' => "totais",

	'peso' => "peso",
	'peso netto' => "peso líquido",
	'peso lordo' => "peso bruto",
	'netto' => "líquido",
	'lordo' => "bruto",
	'peso totale' => "peso total",
	'millimetri' => "milímetro",
	'milimetro' => "milímetro",
	'mm' => "mm",
	'centimetri' => "centímetros",
	'centimetro' => "centímetro",
	'cm' => "cm",
	'metri' => "metros",
	'metro' => "metro",
	'mt' => "mt",
	'm3' => "m & sup3,",
	'grammi' => "gramas",
	'grammo' => "grama",
	'gr' => "gr",
	'etti' => "onças",
	'etto' => "onça",
	'he' => "h",
	'kilogrammo' => "quilograma",
	'kilogrammi' => "quilogramas",
	'chilogrammo' => "quilograma",
	'chilogrammi' => "quilogramas",
	'kg' => "kg",
	'tonnellata' => "tonelada métrica",
	'tonnellate' => "toneladas",
	'tn' => "tn",
	'quintale' => "quintal",
	'quintali' => "quintals",
	'ql' => "ql",
	'qtà' => "q.ty",
	'quantità' => "quantidade",
	'qtà min.' => "min.q.ty",
	'quantità min.' => "Quantidade mínima.",
	'qtà minima' => "q.tà minimun",
	'quantità minima' => "quantidade mínima",
	'sconti per quantità' => "descontos de quantidade",
	'unità di misura' => "unidade de medida.",
	'metri quadrati' => "metros quadrados",
	'metro quadrato' => "metro quadrado",
	'mq' => "mq",
	'spessore' => "espessura",

	'pezzi' => "peças",
	'pezzo' => "peça",
	'volume' => "volume",
	'volume abbreviato' => "vol",
	'volume netto' => "volume líquido",
	'volume lordo' => "volume bruto",
	'volume totale' => "volume total",
	'volumi' => "volumi",

	'pagamento' => "pagamento",
	'opzioni pagamento' => "opções de pagamento",
	'ordine' => "ordem",
	'ordini' => "ordens",
	'ordine fatto da' => "ordem feita por",
	'consegna' => "entrega",
	'spese trasporto' => "entregar despesas",
	'spese di trasporto' => "despesas de entrega",
	'conferma ordine' => "confirmação da ordem",
	'svuota il carrello' => "esvazia o carro",
	'altri acquisti' => "outras compras",
	'altre eventuali spese' => "(mais IVA, transporte e outras despesas)",
	'carrello' => "carrinho de compras",
	'riepilogo' => "resumo detalhado",
	'altra destinazione' => "outro destino",

	'oggetto' => "objeto",
	'oggetti' => "objetos",
	'soggetto' => "assunto",
	'soggetti' => "assuntos",
	'prodotto' => "produto",
	'prodotti' => "produtos",
	'azienda' => "empresa",
	'aziende' => "empresas",

/* messaggi errore  e/o conferma */
	'inserisci il nome' => "inserir nome",
	'inserisci il cognome' => "inserir sobrenome",
	'inserisci il nome e il cognome' => "inserir nome e sobrenome",
	'inserisci un indirizzo' => "inserir um endereço",
	'inserisci un cap' => "inserir um CÓDIGO POSTAL",
	'inserisci una città' => "inserir uma cidade",
	'inserisci una provincia' => "inserir um país",
	'inserisci uno stato' => "inserir uma nação",
	'inserisci il numero telefono' => "inserir número de telefone",
	'inserisci un indirizzo email valido' => "inserir endereço de e-mail válido",
	'inserisci un indirizzo email' => "inserir um endereço de email",
	'inserisci un número de fax' => "inserir um número de fax",
	'inserisci un numero di cellulare' => "inserir um número de celular",
	'inserisci un account di skype' => "inserir uma conta skype",
	'inserisci il tuo messaggio' => "Por favor, insira sua mensagem",
	'inserisci il testo immagine' => "inserir texto da imagem",
	'inserisci un nome utente' => "inserir um nome de usuário",
	'inserisci una password' => "inserir uma senha",
	'inserisci uma senha de controle' => "inserir uma senha de controle",
	'inserisci minimo %NUMCHAR% caratteri!' => "Inserir %NUMCHAR% caracteres mínimos!",
	'inserisci massimo% NUMCHAR% caratteri!' => "Inserir %NUMCHAR% caracteres máximo!",
	'inserisci il messaggio' => "inserir uma mensagem",

	'Devi inserire tutti i campi richiesti!' => "Você deve digitar os campos obrigatórios!",
	'Devi inserire un nome!' => "Você deve digitar um nome!",
	'Devi inserire un cognome!' => "Você deve digitar um sobrenome!",
	'Devi inserire un nome ed un cognome!' => "Você deve digitar um sobrenome e um sobrenome!",
	'Devi inserire uil cognome e nome o denominazione della ditta!' => "Você deve digitar o nome completo ou o nome da empresa!",
	'Devi inserire un cliente!' => "Você deve digitar um cliente!",
	'Devi inserire una azienda!' => "Você deve digitar uma empresa!",
	'Devi inserire un indirizzo!' => "Você deve digitar um endereço!",
	'Devi inserire una città!' => "Você deve digitar uma cidade!",
	'Devi inserire un cap!' => "Você deve digitar um CEP!",
	'Devi inserire una provincia!' => "Você deve digitar uma província!",
	'Devi scegliere uno stato!' => "Você deve escolher um país!",
	'Devi selezionare uno stato!' => "Você deve selecionar uma nação!",
	'Devi inserire un indirizzo email!' => "Você deve digitar um endereço de e-mail!",
	'Devi inserire un indirizzo email valido!' => "Você deve digitar um endereço de e-mail válido!",
	'Devi inserire un numero telefonico!' => "Você deve digitar um número de telefone!",
	'Devi inserire un titolo!' => "Você deve digitar um título!",
	'Devi inserire un codice!' => "Você deve digitar um código!",
	'Devi inserire un numero!' => "Você deve digitar um número!",
	'Devi inserire almeno un telefono, un fax oppure un indirizzo email!' => "Você deve digitar um número de telefone, um número de fax ou um endereço de e-mail!",
	'Devi selezionare una modalità di pagamento!' => "Você deve selecionar um tipo paymant!",
	'Devi inserire un oggetto!' => "Você deve digitar um objeto!",
	'Devi inserire un soggetto!' => "Você deve digitar um assunto!",
	'Devi selezionare una categoria!' => "Você deve selecionar uma categoria!",
	'Devi inserire una data!' => "Você deve digitar um dado!",
	'Devi inserire il testo che appare immagine!' => "Você deve digitar o texto que aparece na imagem!",
	'Devi inserire um nome utente!' => "Você deve digitar um nome de usuário!",
	'Devi inserire una password!' => "Você deve digitar uma senha!",
	'Devi inserire una password di controllo!' => "Você deve digitar um controle de senha!",
	'Devi inserire un messaggio!' => "Você deve digitar uma mensagem!",
	'Devi leggere i termini e le condizioni!' => "Você deve ler os termos e condições!",
	'Devi inserire tutti i campi richiesti!' => "Você deve digitar todos os campos obrigatórios!",
	'Devi autorizzare il trattamento della privacy!' => 'Você deve autorizar o tratamento da privacidade!',

	'errore!' => "erro!",
	'errore' => "erro",
	'Errore, ordine NON cancellato!' => "Erro, ordem não excluída!",
	'sono presenti uno o più errori!' => "Há um ou mais erros!",
	'Errore database! Sei pregato di riprovare oppure di contattare amministratore!' => "Erro de banco de dados Você deve tentar novamente ou entrar em contato com o administrador!",

	'vai alla home cliente' => "ir para a casa do cliente",
	'vai al carrello prodotti' => "vá para o carrinho do shoppimg",
	'vai alla pagina' => "ir para a página",
	'vai alla pagina %PAGE%' => "ir para a página %PAGE%",

	'pagina non trovata!' => "página não encontrada!!",
	'la pagina che stai cercando non è stata trovata!' => "a página que você está procurando não foi encontrada!",
	'Pagina non trovata!' => "Página não encontrada!!",
	'La pagina che stavi cercando non è stata trovata!' => "A página que você está procurando não foi encontrada!",

	'Cliente riconosciuto!' => "Cliente reconhecido!",
	'Cliente NON riconosciuto!' => "Cliente NÃO reconhecido!",
	'Cliente registrato!' => "Cliente registrado!",
	'Cliente NON registrato!' => "Cliente NÃO registrado!",
	'Cliente aggiornato!' => "Cliente atualizado!",
	'Cliente NON aggiornato!' => "Cliente NÃO atualizado!",

	'Ordine inviato!' => "Ordem enviada!",
	'Ordine annullato!' => "Ordem excluído!",
	'Ordine cancellato!' => "Ordem excluído!",

	'Il tuo messaggio E stato spedito! Riceverai un messaggio di conferma.' => "Sua mensagem foi enviada! Você receberá uma mensagem de confirmação.",
	'Il tuo messaggio di conferma NON è stato spedito! Riprova.' => "Sua mensagem de confirmação não foi enviada! Tente novamente.",
	'Il tuo messaggio NON è stato spedito! Riprova.' => "Sua mensagem não foi enviada! Tente novamente.",
	'Il tuo messaggio è stato spedito!' => "Sua mensagem foi enviada!",
	'Il tuo messaggio NON è stato spedito!' => "Sua mensagem NÃO FOI enviada!",

	'torna alla pagina %PAGE%' => "voltar à página %PAGE%",
	'torna a %PAGE%' => "voltar à %PAGE%",

	'Il testo digitato non corrisponde a quello immagine!' => "O texto que você digita não corresponde à imagem!",
	'Le due password non corrispondono!' => "As duas senhas não correspondem!",

	'Sei stato identificato come robot!' => "Você foi identificado como um robô!",
	'Devi verificare che non sei un robot!' => "Você deve verificar se você não é um robô!",

	'Ci sono problemi con la validazione del modulo, sei pregato di controllare!' => "Há problemas com a validação do formulário, por favor verifique!",
	'Nessun ordine trovato!' => "Nenhuma ordem encontrada!",
	'sei sicuro?' => "você tem certeza?",
	'Server non risponde!' => "Desculpe, parece que o servidor de e-mail não está respondendo. Tente escrever um e-mail para %EMAIL%. Desculpe pelo inconveniente!",
	'Nessuna voce presente!' => "Nenhum item apresenta!",

	'Il nome utente esiste gia!' => "O nome de usuário já existe!",
	'Il nome utente è disponibile!' => "O nome de usuário está disponível!",
	'Il nome utente non esiste!' => "O nome de usuário não existe!",

	'Indirizzo email esiste gia!' => "O endereço de e-mail já existe!",
	'Indirizzo email è disponibile!' => "O endereço de e-mail está disponível!",
	'Indirizzo email non esiste!' => "O endereço de e-mail não existe!",

/* SPECIFICHE ACCOUNT */
	'gestisci il tuo nome, cogmome e indirizzo' => "gerenciar seu nome completo e endereço",
	'indirizzo e la password possono essere cambiati nella scheda successiva' => "o endereço de e-mail ea senha podem ser alterados no próximo separador",
	'modifica impostazioni sicurezza' => "alterar configurações de segurança",

/* EMAILS UTENTE */
/* registrazione */
/*
	'utente - soggetto email conferma registrazione' => "Entrada no site %SITENAME%",
	'utente - contenuto email conferma registrazione' => "Oi, você enviou uma solicitação de registro para o site %SITENAME% om nome de usuário: <strong>%USERNAME%</strong> email <strong>%EMAIL%</strong>.<br>Você é solicitado a confirmar seu registro clicando em <a href=\"%URLCONFIRM%\"><strong>AQUI</strong></a>.",
	'utente - errore database conferma registrazione' => "Erro de banco de dados no registro! Entre em contato com o administrador do site.",
	'utente - errore invio email conferma registrazione' => "Erro ao enviar e-mail na gravação! Entre em contato com o administrador do site.",
	'utente - procedura conferma registrazione ok' => "O registro do site foi bem sucedido!<br>Receberá um e-mail de confirmação no link para confirmar seu registro.",
/* recupero password */
/*
'utente - soggetto email recupero password' => "Recuperação de senha do site %Ste - contenuto email recupero password' => "Conforme solicitado, enviamos a nova senha para o usuário associado ao <b>%USERNAME%</b> registrado no site <b>%SITENAME%</b><br>Senha: <b>%PASSWOte - errore invio email recupero password' => "Erro ao enviar e-mail para recuperar te - procedura recupero password ok' => "A nova senha foi enviada com um endereço de e-mail associado e armazenado no sistema!",
/* recupero ute - soggetto email recupero username' => "Nome do usuário de recuperação do site %Ste - contenuto email recupero username' => "Conforme solicitado, enviamos a nova usuário associado ao <b>%EMAIL%</b> registrado no site <b>%SITENAME%</b><br> Usuário: <b>%USERNAte - errore invio email recupero username' => "Erro ao enviar e-mail para recuperar o nome do te - procedura recupero username ok' => "Seu nome de usuário foi enviado com um endereço de e-mail associado!",

/* EMAILS CLIENTE */
/* registnte - soggetto email conferma registrazione' => "Entrada no %Snte - contenuto email conferma registrazione' => "Olá, você envia uma solicitação de inscrição para a loja %SITENAME% com nome de usuário: <strong>%USERNAME%</strong> email <strong>%EMAIL%</strong>.<br>Você é solicitado a confirmar seu registro clicando em <a href=\"%URLCONFIRM%\"><strong>AQUI</stronte - soggetto email staff conferma registrazione' => "Notificar novo registro para a loja %Snte - contenuto email staff conferma registrazione' => "Novo pedido de registo na loja %SITENAME% con id: <strong>%IDUSER%</strong>, username: <strong>%USERNAME%</strong> e email: <strong>%EMAIL%</nte - errore database conferma registrazione' => "Erro de banco de dados na gravação! Entre em contato com o administrador nte - errore invio email conferma registrazione' => "Erro ao enviar e-mail na gravação! Entre em contato com o administrador nte - procedura conferma registrazione ok' => "O registro na loja foi bem sucedido!<br>Receberá um e-mail de confirmação com a sua inteno o link para confirmar o seu registo.",
/* recupero pnte - soggetto email recupero password' => "Recuperação de Senha da loja %Snte - contenuto email recupero password' => "Conforme solicitado, enviamos uma nova senha associada ao cliente <b>%USERNAME%</b> registrada na loja <b>%SITENAME%</b><br>Senha: <b>%PASSWOnte - errore invio email recupero password' => "Erro ao enviar e-mail para recuperar nte - procedura recupero password ok' => "A nova senha foi enviada com um endereço de e-mail associado e armazenado no sistema!",
/* recupero unte - soggetto email recupero username' => "Nome do cliente de recuperação shop %Snte - contenuto email recupero username' => "Conforme solicitado, enviamos o nome do cliente associado ao endereço de email <b>%EMAIL%</b> registrado no site <b>%SITENAME%</b><br>Nome de usuário: <b>%USERNAnte - errore invio email recupero username' => "Erro ao enviar e-mail para recuperar o nome do nte - procedura recupero username ok' => "Seu nome de cliente (nome de usuário) foi enviado com um endereço de e-mail associado!",

/ie advise panel text' => "Os cookies nos ajudam a fornecer nossos serviços. Ao usar nossos serviços, você concorda em usar nossos cookies.",
*/
	)
);
?>