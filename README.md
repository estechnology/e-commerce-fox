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
