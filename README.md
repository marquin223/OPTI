## OPTI

"OPTI" é uma aplicação que otimiza a produtividade da sua loja de manutenção de hardwares. Com o software OPTI você irá gerenciar seus chamados técnicos de forma simples e ágil. Evite frustrações e custos de deslocamentos desnecessários.

### Dependências

- Docker
- Docker Compose

### To run

#### Clone Repository

```
$ git@github.com:marquin223/OPTI.git
$ cd OPTI
```

#### Define the env variables

```
$ cp .env.example .env
```

#### Install the dependencies

```
$ ./run composer install
```

#### Up the containers

```
$ docker compose up -d
```

ou

```
$ ./run up -d
```

#### Create database and tables

```
$ ./run db:reset
```

#### Populate database

```
$ ./run db:populate
```

### Fixed uploads folder permission

```
sudo chown www-data:www-data public/assets/uploads
```

#### Run the tests

```
$ docker compose run --rm php ./vendor/bin/phpunit tests --color
```

ou

```
$ ./run test
```

#### Run the linters

[PHPCS](https://github.com/PHPCSStandards/PHP_CodeSniffer/)

```
$ ./run phpcs
```

[PHPStan](https://phpstan.org/)

```
$ ./run phpstan
```

Access [localhost](http://localhost)
