
Aplicação web para cadastro de livros, autores e assuntos, com exportação de relatórios em PDF por autor.
Desenvolvido com React no frontend e integração com API RESTful.

### Frontend
- [React](https://reactjs.org/)
- [Bootstrap](https://getbootstrap.com/)
- [React-Bootstrap](https://react-bootstrap.github.io/)
- Fetch API
- DOMPDF (para geração de PDF no backend)

### Backend
- PHP 8.2
- Symfony 7
- DOMPDF (geração de relatórios em PDF)
- API REST
- MySQL database

## Funcionalidades

- 📗 Cadastro, edição e remoção de livros, autores e assuntos
- 📄 Geração de relatório em PDF agrupados por autor
- 💰 Visualização e formatação de preço em reais (R$)
- 🎨 Estilização com Bootstrap responsiva


## Instalação

### Requisitos para instalação (local)
- Docker (Docker version ^28.0.2)
- Docker Compose (Docker Compose version ^v2.21.0)

### 1. Clonar o repositório
```
git clone https://github.com/felipepinson/book_storage.git
```

Acesso o diretorio clonado

```
cd book_storage
```

Va até o diretorio `backend`

```
cd book_storage/backend
```

Duplique o arquivo `.env.dist` e renomeie para `.env`

Va até a raiz do projeto contendo os arquivos (`Dockerfile.api`, `Dockerfile.app` e `docker-compose.yaml`) e
execute o comando abaixo, para iniciar os containers

```
docker-compose up --build
```

Aguarde até os containers ficarem todos disponiveis:

- `books-api-nginx-server` - (container com servidor http nginx para api backend)
- `books-api` - (container contendo a aplicação backend em PHP com Symfony)
- `books-api-mysql` - (Banco de dados do backend)
- `app-react-frontend` - (aplicação em node + react para frontend)


#### Toda a estrutura da aplicação frentend e backend (Aplicação, tabelas do banco de dados e view) devem ter sido criadas.

## Como comerçar !;

#### Assim que todos os containers estiverem disponiveis


1. Acesso o container da aplicação backend `books-api`.

```
docker exec -ti books-api ash
```

2. Execute o seguinte comando para carregar livros inicias no banco

```
bin/console doctrine:fixtures:load --no-interaction
```

O frontend ficaria disponivel na porta `3000`, será possivel acesso-lo, usando a seguinte URL:

```
http://localhost:3000/
```


## backend ficara disponivel na segunda URL usando a porta `8080`

```
http://localhost:8080/
```

Uma pagina simples contendo um JSON com uma mensagem, será exibido

```
    {
        "message": "Tudo ok :) Obrigado por confirmar!"
    }
```


### Para executar os Tests unitarios e funcionais execute o seguinte comando (obrigatorio executar dentro do container)

- `vendor/bin/phpunit` (ira executar os tests)