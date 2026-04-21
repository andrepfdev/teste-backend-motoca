# Teste Tecnico - Backend Laravel (Concessionaria)

API REST para gerenciamento de veiculos e leads de uma concessionaria.

---

## Stack

- PHP 8.4
- Laravel 13
- PostgreSQL
- Docker / Docker Compose
- Nginx
- Laravel Sanctum (autenticacao)

---

## Como rodar o projeto

### 1. Clonar e configurar o ambiente

```bash
git clone <repositorio>
cd teste-backend-motoca
cp .env.example .env
```

### 2. Subir os containers

```bash
docker compose up -d
```

A aplicacao ficara disponivel em `http://localhost:8000`.

### 3. Rodar as migrations e seeders

```bash
docker compose exec app php artisan migrate --seed
```

O seeder cria um usuario padrao para autenticacao:

- **Email:** `admin@motoca.com`
- **Senha:** `password`

---

## Autenticacao

A API usa Laravel Sanctum com tokens via header.

```
POST /api/login
```

```json
{
  "email": "admin@motoca.com",
  "password": "password"
}
```

Use o token retornado no header de todas as requisicoes autenticadas:

```
Authorization: Bearer {token}
```

---

## Endpoints

### Vehicles

| Metodo | Rota | Autenticado |
|--------|------|-------------|
| GET | /api/vehicles | Nao |
| POST | /api/vehicles | Sim |
| GET | /api/vehicles/{id} | Nao |
| PUT | /api/vehicles/{id} | Sim |
| DELETE | /api/vehicles/{id} | Sim |

Filtros disponiveis:

```
GET /api/vehicles?type=car
GET /api/vehicles?max_price=80000
```

### Leads

| Metodo | Rota | Autenticado |
|--------|------|-------------|
| POST | /api/leads | Nao |
| GET | /api/leads | Sim |
| GET | /api/vehicles/{id}/leads | Sim |

### Dashboard

```
GET /api/dashboard  (autenticado)
```

---

## Abordagem tecnica

### Validacao com Form Requests

Toda validacao de entrada e feita via classes `FormRequest`, mantendo os controllers limpos e a logica de validacao isolada e testavel. Cada endpoint com entrada de dados tem seu proprio Form Request com regras e mensagens customizadas.

### Testes com PHPUnit

A cobertura de testes usa PHPUnit com Feature Tests, priorizando o comportamento real da API (requests HTTP, respostas JSON, estado do banco). Os testes cobrem:

- Fluxo feliz (happy path) de cada endpoint
- Falhas de validacao
- Acesso nao autorizado a rotas protegidas
- Regras de negocio (ex: `year >= 2000`, `price > 0`)

O banco de dados de testes usa `LazilyRefreshDatabase` para performance, com factories e estados para criacao de dados.

### Controllers

Controllers sao enxutos: recebem o Form Request validado, delegam para o model/Eloquent e retornam Eloquent API Resources. Nenhuma logica de negocio vive no controller.

### API Resources

Todas as respostas JSON sao formatadas via Eloquent API Resources, garantindo consistencia e desacoplamento entre o modelo e o contrato da API.

---

## Colecao Postman

O arquivo `postman_collection.json` na raiz do repositorio contem todos os endpoints documentados e prontos para importar.

---

## Testes

```bash
docker compose exec app php artisan test --compact
```
