
# Desafio Bússola - Carrinho de Compras

Este projeto utiliza Docker e Docker Compose para facilitar o desenvolvimento e execução do ambiente. Abaixo estão as instruções detalhadas para subir o ambiente e rodar o projeto.

[Link para o desafio](https://github.com/bussola-social/desafio-carrinho)

## Requisitos

Antes de começar, certifique-se de ter os seguintes requisitos instalados:

- Docker e Docker Compose

## Subindo os Containers

Para subir o ambiente com Docker, execute o comando abaixo:

```bash
docker-compose up -d --build
```

Isso iniciará os containers definidos no arquivo `docker-compose.yml`.

## Acessando o Container

Para acessar o container do PHP, utilize o comando abaixo:

```bash
docker exec -it desafio-bussola bash
```

## Instalando Dependências com Composer

Dentro do container, execute o comando:

```bash
composer install
```

## Executando a aplicação com PHP
Dentro do container, execute o comando:

```bash
php index.php
```

## Rodando Testes

Dentro do container, rode os testes com o comando:

```bash
vendor/bin/phpunit tests
```

## Padrões de Projeto Utilizados

O projeto utiliza os seguintes padrões de projeto:

- **DTO (Data Transfer Object):** `src/DTO/`, utilizado para encapsular e transferir dados.
- **Strategy:** `src/Strategy/`, usado para definir estratégias diferentes de pagamento.
- **Service:** `src/Services/`, abstraindo regras de negócio.
- **Adapter:** `src/Adapters/`, útil quando você precisa transformar dados de uma fonte externa ou diferente em um formato utilizado.

## Estrutura do Projeto

```
├── src/
│   ├── Adapter/
│   │   ├── ItemAdapter.php
│   │   ├── PaymentAdapter.php
│   ├── DTO/
│   │   ├── ItemDTO.php
│   │   ├── PaymentDTO.php
│   ├── Exceptions/
│   │   ├── InvalidPaymentMethodException.php
│   │   ├── PaymentMethodNotSetException.php
│   ├── Services/
│   │   ├── CartService.php
│   ├── Strategy/
│   │   ├── CreditCardPayment.php
│   │   ├── PaymentStrategy.php
│   │   ├── PixPayment.php
├── tests/
│   ├── CartServiceTest.php
├── docker-compose.yml
├── Dockerfile
├── composer.json
├── composer.lock
```