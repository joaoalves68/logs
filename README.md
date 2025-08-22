# Logs
Este projeto foi configurado para rodar em um ambiente de desenvolvimento isolado usando **Docker** e **Docker Compose**, o que elimina a necessidade de instalar PHP, MySQL e outras dependências diretamente na sua máquina.

## Pré-requisitos

Para rodar este projeto, você precisa **obrigatoriamente** ter apenas o **Docker Desktop** instalado e rodando na sua máquina.

* [Docker Desktop para Mac, Windows ou Linux](https://www.docker.com/products/docker-desktop/)

> **Atenção usuários Windows:** É fundamental que você tenha o **WSL 2 (Windows Subsystem for Linux)** instalado e ativado, pois o Docker Desktop depende dele. Siga [este guia da Microsoft](https://docs.microsoft.com/pt-br/windows/wsl/install) para a instalação.

## Guia de Instalação Passo a Passo

Siga estas instruções na ordem exata. Todos os comandos devem ser executados no seu terminal (como PowerShell, Terminal do Mac/Linux ou o terminal do seu VS Code).
1. **Clone o Repositório:**
    ```bash
        git clone git@github.com:joaoalves68/logs.git
        cd logs
    ```

2.  **Copie o Arquivo de Ambiente:**
    ```bash
        cp .env.example .env
    ```
    *(Opcional: Revise o `.env` para configurações como `APP_PORT`, `OPENAI_API_KEY` e `WHOIS_API_KEY`)*

3.  **Rode esses comandos para buildar o projeto, migrar o banco de dados, instalar dependências etc.:**
    ```bash
        docker-compose build
        docker-compose up -d
        docker-compose exec app php artisan migrate:fresh --seed
        docker-compose exec app npm install
        docker-compose exec app npm run build
        docker-compose exec app php artisan queue:work
    ```

5. **Acesse a Aplicação:**
    Abra seu navegador e acesse `http://localhost:8000` (ou a porta configurada em `APP_PORT` no seu `.env`)
    ```bash
        admin@admin.com
        1234
    ```
