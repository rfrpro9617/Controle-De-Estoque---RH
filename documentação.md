# Como a sessão é inicializada

# Cadastro e autenticação de usuários

Crie a tabela de usuários antes de iniciar a aplicação:

```sh
mysql -u SEU_USUARIO -p SEU_BANCO < database/estoque_usuarios.sql
```

Substitua `SEU_USUARIO` e `SEU_BANCO` pelos dados da instalação.

O cadastro está disponível em `/cadastro`. Senhas novas são armazenadas em `senha_hash` usando Argon2id quando disponível no PHP, com o algoritmo padrão do PHP (bcrypt) como alternativa. Senhas existentes na tabela antiga `programa` não são consultadas nem aceitas.

`email` deve ser único. O cadastro autentica o usuário após a criação da conta.

1. Antes do Login: Se o usuário acabou de abrir a página de login pela primeira vez, $\_SESSION estará vazia ([]).
2. Durante o Login: O método login() valida os dados, executa o session_regenerate_id(true) (que deleta o arquivo antigo do servidor e muda o ID no navegador, mas mantém os dados da memória) e em seguida injeta os dados:

$\_SESSION['auth'] = [
'user_id' => $user->id,
];

# Core/Auth

Auth: responde se o usuário está autenticado.
AuthGuard: responde essa rota exige autenticação? Se sim, o usuário está autenticado?

Logout:

- A ideia é remover somente o estado de autenticação e não destruir a sessão inteira.
- Porque futuramente sua aplicação pode colocar outras informações na sessão que não devem ser destruidas:
  $_SESSION['flash'] = ...;
    $\_SESSION['preferences'] = ...;
  $\_SESSION['something_else'] = ...;

# Por que salvar apenas o ID do usuário na sessão?

A sessão não precisa armazenar os dados do usuário; ela precisa armazenar apenas uma informação que permita identificar o usuário autenticado.
Por que isso é melhor?
Imagine que você armazenasse:

$\_SESSION['user'] = [
'id' => 15,
'name' => 'Rodrigo',
'email' => 'rodrigo@email.com',
];

Dados podem ficar desatualizados
A sessão fica responsável por mais informações
A sessão deveria responder principalmente: "Existe um usuário autenticado? Quem é ele?"
E não: "Quais são todos os dados desse usuário?"
Os dados do usuário, podemos buscar no banco de dados por meio do id do usuário.

# Por que no método check do do Auth verificar o id do usuário e não somente se existe algo genérico na sessão?

Por que isso resolve o problema?
O isset() verifica se o índice existe e se seu valor não é null.
Antes, bastava existir $\_SESSION['auth'] para retornar true, mesmo que não houvesse um user_id.
Agora, a verificação corresponde melhor à nossa regra:

$_SESSION['auth'] não existe → false.
$\_SESSION['auth']['user_id'] não existe → false.
$_SESSION['auth']['user_id'] é null → false.
$\_SESSION['auth']['user_id'] contém um valor → true.

# Como podemos usar variáveis estáticas para criar cache?

Podemos salvar informações do banco em variáveis estáticas e usar para compartilhar entre chamados que fariam a mesma consulta para recuperar as informações no banco.

# Por que devemos verificar se o id do usuário da sessão é elegível?

Pois o id do usuário salvo na sessão, pode não ser mais elegível no banco de dados, pois foi deletado.

# Componente Shell

# Leitura inicial de pedidos em PDF

A tela autenticada `/estoque/importar-pdf` recebe arquivos PDF de até 10 MB e usa `Smalot\PdfParser` para extrair o texto durante a requisição. O arquivo temporário não é copiado para o armazenamento da aplicação. A página apresenta uma prévia limitada a 12.000 caracteres.

Instale as dependências com `composer install`. Ao importar um PDF, os itens extraídos são gravados em `estoque_materiais`; a descrição é armazenada em `nome`, o código em `codigo` e cada material é associado à categoria criada ou localizada em `estoque_categorias`. Cada item também é vinculado ao pedido em `estoque_pedido_itens`, com quantidade e valor unitário.