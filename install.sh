#!/bin/bash

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
CYAN='\033[0;36m'
NC='\033[0m'

echo ""
echo -e "${CYAN}=================================================${NC}"
echo -e "${CYAN}   Instalação - Teste Backend Laravel Motoca    ${NC}"
echo -e "${CYAN}=================================================${NC}"
echo ""

# ── 1. Escolha do comando Docker ──────────────────────────────────────────────
echo -e "${YELLOW}Como você executa o Docker Compose?${NC}"
echo "  1) docker compose  (plugin v2)"
echo "  2) docker-compose  (standalone v1)"
echo ""
read -rp "Escolha [1/2]: " docker_choice

case "$docker_choice" in
    1)
        DOCKER_COMPOSE="docker compose"
        ;;
    2)
        DOCKER_COMPOSE="docker-compose"
        ;;
    *)
        echo -e "${RED}Opção inválida. Usando 'docker compose' como padrão.${NC}"
        DOCKER_COMPOSE="docker compose"
        ;;
esac

echo -e "${GREEN}✔ Usando: ${DOCKER_COMPOSE}${NC}"
echo ""

# ── 2. Verificar se o comando existe ──────────────────────────────────────────
if ! command -v ${DOCKER_COMPOSE%% *} &> /dev/null; then
    echo -e "${RED}Erro: Docker não encontrado. Instale o Docker antes de continuar.${NC}"
    exit 1
fi

# ── 3. Copiar .env ─────────────────────────────────────────────────────────────
if [ ! -f .env ]; then
    echo -e "${YELLOW}► Criando arquivo .env a partir do .env.example...${NC}"
    cp .env.example .env
    echo -e "${GREEN}✔ .env criado.${NC}"
else
    echo -e "${YELLOW}► Arquivo .env já existe, pulando.${NC}"
fi
echo ""

# ── 4. Subir containers ────────────────────────────────────────────────────────
echo -e "${YELLOW}► Subindo containers Docker...${NC}"
$DOCKER_COMPOSE up -d --build
echo -e "${GREEN}✔ Containers no ar.${NC}"
echo ""

# ── 5. Aguardar o PostgreSQL ficar pronto ──────────────────────────────────────
echo -e "${YELLOW}► Aguardando o banco de dados ficar pronto...${NC}"
until $DOCKER_COMPOSE exec -T postgres pg_isready -U "${DB_USERNAME:-meutesteandre}" &> /dev/null; do
    printf "."
    sleep 2
done
echo ""
echo -e "${GREEN}✔ Banco de dados pronto.${NC}"
echo ""

# ── 6. Gerar APP_KEY se estiver vazia ──────────────────────────────────────────
APP_KEY_VALUE=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY_VALUE" ]; then
    echo -e "${YELLOW}► Gerando APP_KEY...${NC}"
    $DOCKER_COMPOSE exec -T app php artisan key:generate --no-interaction
    echo -e "${GREEN}✔ APP_KEY gerada.${NC}"
    echo ""
fi

# ── 7. Rodar migrations ────────────────────────────────────────────────────────
echo -e "${YELLOW}► Rodando migrations...${NC}"
$DOCKER_COMPOSE exec -T app php artisan migrate --no-interaction
echo -e "${GREEN}✔ Migrations concluídas.${NC}"
echo ""

# ── 8. Pergunta sobre seeders ──────────────────────────────────────────────────
read -rp "Deseja popular o banco com dados de exemplo (seeders)? [s/N]: " seed_choice

if [[ "$seed_choice" =~ ^[sS]$ ]]; then
    echo -e "${YELLOW}► Rodando seeders...${NC}"
    $DOCKER_COMPOSE exec -T app php artisan db:seed --no-interaction
    echo -e "${GREEN}✔ Seeders concluídos.${NC}"
    echo ""
    echo -e "${CYAN}Dados criados:${NC}"
    echo "  • Usuário: test@example.com / password"
    echo "  • 15 veículos com dados fictícios"
    echo "  • Leads vinculados aleatoriamente"
fi

# ── 9. Resumo ──────────────────────────────────────────────────────────────────
echo ""
echo -e "${CYAN}=================================================${NC}"
echo -e "${GREEN}   Instalação concluída com sucesso!            ${NC}"
echo -e "${CYAN}=================================================${NC}"
echo ""
echo -e "  API disponível em: ${CYAN}http://localhost:8000${NC}"
echo ""
