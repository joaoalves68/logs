# Projeto Laravel com Docker e MySQL

Este projeto foi configurado para rodar em um ambiente de desenvolvimento isolado usando **Docker** e **Docker Compose**, o que elimina a necessidade de instalar PHP, MySQL e outras dependências diretamente na sua máquina.

### Requisitos

- [**Docker Desktop**](https://www.docker.com/products/docker-desktop/) (para Windows e macOS)
- [**Docker Engine**](https://docs.docker.com/engine/install/) (para Linux)

### Passo 1: Instalação do Laravel

Antes de tudo, você precisa ter os arquivos do Laravel na raiz do projeto. Caso precise reinstalar, você pode usar o seguinte comando:

```bash
docker-compose up -d --build
docker-compose exec app php artisan migrate
docker-compose exec app npm install
docker-compose exec app composer install
docker-compose exec app npm run build
