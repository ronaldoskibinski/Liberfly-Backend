# API Para Liberfly #

### Instruções para inicializar: ###

Necessário ter instalado o PHP (versão 8.1) ^ e o Composer instalados.
Também é necessário possuir acesso ao banco de dados MySql.

Com tudo isso funcionando normalmente, configurar o arquivo .env com os dados do banco de dados, conforme código abaixo:

```

DB_CONNECTION=mysql <- Driver -> Manter o valor mysql
DB_HOST=127.0.0.1 <- Servidor em que esta executando o mysql
DB_PORT=3306 <- Porta em que esta executando o mysql no servidor
DB_DATABASE=liberfly <- Nome do banco de dados
DB_USERNAME=root <- Usuário no MySQL (root é o padrão para instalação local)
DB_PASSWORD= <- Senha no MySQL (Sem senha é o padrão para instalação local)

```

Após isso, basta executar abrir o prompt de comando, navegar até a pasta do projeto e executar o comando abaixo para instalar todas as dependencias do projeto:


```

composer install;

```

Após isso, deve-se executar o comando abaixo para criar a estrutura do banco de dados:


```

php artisan migrate;

```

Logo depois, execute o próximo comando para criar os usuários padrão, assim como a empresa geral:

```

php artisan db:seed;

```


### Crie uma chave JWT ###

Execute o comando abaixo para criar uma chave JWT e salve-a no ENV como JWT_SECRET=CHAVE:

```

php artisan jwt:secret

```


### Gerando documentação Swagger ###

Para gerar a documentação do swagger atualizada, deve-se executar o comando abaixo:

```

php artisan l5-swagger:generate

```


### Iniciando o servidor localmente ###

Para inicializar o servidor localmente, basta executar o comando abaixo:

```

php artisan serve

```

Com isso, a aplicação estará ouvindo em: http://127.0.0.1:8000

A documentação do swagger estará disponível em: http://localhost:8000/api/documentation



### Suporte ao Docker ###

Para executar a aplicação em sua máquina local, ou mesmo em cloud utilizando o docker, basta seguir os passos abaixo:

- Verifique as configurações no arquivo .env e altere o HOST do banco de dados para db;

- Após isso, executar o comando abaixo para buildar a imagem da aplicação Laravel:

```

docker-compose build app

```

- Com isso, deve-se executar o comando abaixo para subir os demais containers de nginx e mysql:

```

docker-compose up -d

```

Com isso, deve-se executar os mesmos comandos indicados acima conforme exemplos abaixo:


```

docker-compose exec app php artisan migrate

docker-compose exec app php artisan db:seed --class=ProductsSeeder

```

- Com isso, a aplicação estará ouvindo em http://127.0.0.1:8000



### Dicas de Utilização ###

Para paginar utilize: localhost:8000/api/product?page=1&take=10 (?page=1&take=10)
Para filtrar um produto por nome utilize: localhost:8000/api/product?page=1&take=10&name=Nome (&name= "Letra ou nome do produto", o método usado é por like %%)


