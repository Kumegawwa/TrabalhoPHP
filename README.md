```markdown
# Sistema de Gestão de Aprendizagem (SGA)

Este é um Sistema de Gestão de Aprendizagem simples, desenvolvido em PHP com padrão MVC, que permite a criação e gestão de cursos, materiais e atividades.

---

## 🚀 Como Começar

Siga os passos abaixo para configurar e rodar o projeto localmente.

### Pré-requisitos

* **XAMPP** (ou Apache e MySQL independentes)

### 1. Configuração do Banco de Dados (MySQL via phpMyAdmin)

1.  **Inicie o Apache e o MySQL** no seu painel de controle XAMPP.
2.  Acesse o **phpMyAdmin** no seu navegador: `http://localhost/phpmyadmin`.
3.  **Crie um novo banco de dados** chamado `sga` com colação `utf8_general_ci`.
4.  Com o banco `sga` selecionado, vá para a aba **"SQL"** e cole o conteúdo do arquivo `sga.sql`.
5.  Clique em **"Executar"** para criar as tabelas e popular com dados de exemplo.

### 2. Configuração do Projeto

1.  **Clone este repositório** ou baixe os arquivos.
2.  Mova a pasta do projeto (ex: `TrabalhoPHP`) para o diretório `htdocs` do seu XAMPP.
3.  **Ajuste a URL Base:** No arquivo `public/index.php`, verifique se `define('BASE_URL', '/TrabalhoPHP');` corresponde ao nome da pasta do seu projeto em `htdocs`.
4.  **Configuração do Banco de Dados:** O arquivo `config/database.php` já vem configurado para `root` sem senha. Se o seu MySQL tiver credenciais diferentes, ajuste-as aqui.

### 3. Acesso à Aplicação

Abra seu navegador e acesse:

```
http://localhost/NOME_DA_SUA_PASTA_DO_PROJETO/public/
```

(Ex: `http://localhost/TrabalhoPHP/public/` se você usou `TrabalhoPHP` como nome da pasta).

---

## 🔑 Credenciais de Teste (após importar `sga.sql`)

* **Professor:**
    * **Email:** `prof@sga.com`
    * **Senha:** `123456` (ou a senha que você configurou para 'Professor Admin' no SQL)
* **Aluno:**
    * **Email:** `aluno@sga.com`
    * **Senha:** `123456` (ou a senha que você configurou para 'Aluno Teste' no SQL)

Você também pode registrar um novo aluno na tela de cadastro.

---

```