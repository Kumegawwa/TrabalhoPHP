Para criar o **Schema do Banco de Dados** usando o **MySQL via XAMPP**, siga este passo a passo simples e direto:

---

### ✅ **1. Iniciar o XAMPP e o MySQL**

1. **Abra o XAMPP Control Panel**.
2. Clique em **"Start"** nos serviços:

   * **Apache**
   * **MySQL**

> ⚠️ Certifique-se que as portas (geralmente 80 e 3306) não estejam sendo usadas por outros programas (como Skype ou Docker).

---

### ✅ **2. Acessar o phpMyAdmin**

1. Abra o navegador e vá para:

   ```
   http://localhost/phpmyadmin
   ```

2. Você verá o painel do **phpMyAdmin**, que permite gerenciar bancos de dados MySQL visualmente.

---

### ✅ **3. Criar o Banco de Dados**

1. No menu lateral esquerdo, clique em **"Novo"**.
2. No campo **"Nome do banco de dados"**, digite:

   ```
   sga
   ```
3. No tipo de colação, selecione:

   ```
   utf8_general_ci
   ```
4. Clique em **Criar**.

---

### ✅ **4. Criar as Tabelas via SQL (CREATE TABLE)**

1. Com o banco `sga` selecionado, clique na aba **"SQL"**.
2. Cole o seguinte código SQL:

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha_hash VARCHAR(255),
    perfil ENUM('aluno', 'professor', 'admin'),
    cpf VARCHAR(14),
    data_nascimento DATE
);

CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100),
    descricao TEXT,
    professor_id INT,
    FOREIGN KEY (professor_id) REFERENCES usuarios(id)
);

CREATE TABLE materiais_atividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT,
    titulo VARCHAR(100),
    conteudo TEXT,
    tipo ENUM('material', 'atividade'),
    data_postagem DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (curso_id) REFERENCES cursos(id)
);

CREATE TABLE inscricoes_cursos (
    aluno_id INT,
    curso_id INT,
    data_inscricao DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (aluno_id, curso_id),
    FOREIGN KEY (aluno_id) REFERENCES usuarios(id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id)
);

CREATE TABLE entregas_atividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    atividade_id INT,
    aluno_id INT,
    arquivo_entrega VARCHAR(255),
    data_entrega DATETIME,
    nota DECIMAL(5,2),
    FOREIGN KEY (atividade_id) REFERENCES materiais_atividades(id),
    FOREIGN KEY (aluno_id) REFERENCES usuarios(id)
);
```

3. Clique em **Executar**.

---

### ✅ **5. Verificar as Tabelas Criadas**

* No menu à esquerda, clique em `sga`.
* Veja se as tabelas aparecem: `usuarios`, `cursos`, `materiais_atividades`, `inscricoes_cursos`, `entregas_atividades`.

---

### ✅ **6. Pronto para usar!**

Agora seu banco de dados está configurado e pode ser acessado no seu projeto PHP via PDO, como neste exemplo:

```php
$pdo = new PDO('mysql:host=localhost;dbname=sga', 'root', '');
```

---

Se quiser, posso gerar um arquivo `.sql` que você pode importar diretamente pelo phpMyAdmin. Deseja que eu faça isso?
