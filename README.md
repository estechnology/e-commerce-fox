<b>Documentação dos Endpoints:</b> https://documenter.getpostman.com/view/30434146/2sAYkKJHv9 <br>
<b>Link publicado da API:</b> https://apifox.ngrok.dev <br>
<b>Pontos de melhoria:</b> Em se tratando de um endpooint com múltiplas requisições simultâneas, eu escolheria um outro framework para este projeto, utilizaria o Laravel Octane com Swoole, por trabalhar com requisições assincronas e de alto desempenho.<br><br>

Abaixo descrevo como eu fui criando a logica dos itens desenvolvidos e sua sequencia para vocês irem acompanhando a linha de Raciocínio: <br><br>

1º Rode o git clone https://github.com/estechnology/e-commerce-fox.git<br>
2º Rode o comando : Utilizar o comando "composer dump-autoload" para gerar o autoload <br>
3º Rode o comando: php -S localhost:8000 -t public<br>
4º Para testar a instalação e seu primeiro Hello World acesse: http://localhost:8000/hello-twig<br>
5º Faça a configuração de conexão com seu banco de dados Mysql<br>
![image](https://github.com/user-attachments/assets/095ca1a5-9681-4fd0-908e-50de26e07757)<br><br>
6º Para verificar se a conexão deu certo acesse a rota: http://localhost:8000/test-db <br>
7º Rode o comando no terminal : vendor/bin/phinx init <br>
8º Rode o comando no terminal : vendor/bin/phinx create CreateUsersTable <br>
9º Rode o comando no terminal: vendor/bin/phinx migrate <br>
10º Verifique em seu banco de dados se foi criado a tabela de users. <br>
11º Crie rode seu primeiro Seed de users: vendor/bin/phinx seed:run -s UserSeeder  <br>
12º Rode o select no banco para a confirmação do insert da Seed: <br>
![image](https://github.com/user-attachments/assets/2bc07af2-625f-4ba6-a369-199124b72dfa) <br><br>

13º Executar Seeds Products : vendor/bin/phinx seed:run -s ProductSeeder <br>
14º Executar Seeds Cart_itens : vendor/bin/phinx seed:run -s CartItemSeeder <br>
15º Executar primeira lógica de cart-itens: <br>
![image](https://github.com/user-attachments/assets/2314c420-3279-455a-b325-1b53b44fa3b5) <br>
16º Ajustes controller e Seeds para vincular os itens a um carrinho; <br>
17º Implementado Token JWT para gerar a segurança de acesso aos endpooints, como vai ser uma demontração deixei fixo o acesso:<br>
{
    "username": "admFox",
    "password": "ixFezwFgT^rY@a&pf%2q$@"
}<br>
 18º Criação de documentação e testes unitários: <br>
 ![image](https://github.com/user-attachments/assets/681431b0-03a5-4f8d-8818-7ec74d4074cc)<br>

