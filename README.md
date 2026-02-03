# Sistema de Gestão Académica

Plataforma web em PHP, MySQL, HTML, CSS e jQuery para administração académica do Instituto Industrial e de Computação Armando Emílio Guebuza. O sistema suporta diferentes perfis de utilizadores (administração, docentes, estudantes e secretaria) e centraliza operações essenciais como gestão de cursos, turmas, inscrições, notas e comunicados.

## ⚙️ Pré-requisitos

- PHP 8.1+
- Servidor web (Apache/Nginx) ou `php -S`
- MySQL 8.0+
- Composer opcional (não obrigatório)

## 🚀 Configuração Local

1. Clone o repositório e aceda à pasta do projeto:
   ```bash
   git clone <repo>
   cd <repo>
   ```

2. Crie a base de dados e execute o script de schema:
   ```bash
   mysql -u root -p -e "CREATE DATABASE academic_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -u root -p academic_management < database/schema.sql
   ```

3. Configure as credenciais no ambiente (opcional) ou ajuste `config/config.php`:
   ```bash
   export DB_HOST=localhost
   export DB_USER=root
   export DB_PASS=secret
   export DB_NAME=academic_management
   ```

4. Inicie um servidor de desenvolvimento:
   ```bash
   php -S localhost:8000
   ```

5. Aceda a `http://localhost:8000/login.php` e utilize as credenciais de teste:
   - Admin: `admin@iic-aeg.ac.mz` / `admin123`
   - Docente: `docente@iic-aeg.ac.mz` / `docente123`
   - Estudante: `estudante@iic-aeg.ac.mz` / `estudante123`

## 📁 Estrutura

```
config/               # Configuração da base de dados
includes/             # Sessão, autenticação e layout
pages/                # Páginas modulares por funcionalidade
actions/              # Handlers POST seguros para cada recurso
public/css, public/js # Activos estáticos
database/schema.sql   # Criação de tabelas e dados de arranque
vercel.json           # Configuração de deployment
```

## 👥 Perfis e Funcionalidades

- **Administrador**: gerir utilizadores, departamentos, cursos, disciplinas e turmas; publicar comunicados; acompanhar métricas.
- **Docente**: gerir inscrições nas turmas atribuídas e lançar notas; publicar comunicados a estudantes.
- **Estudante**: consultar disciplinas inscritas, horários, notas e comunicados relevantes.
- **Secretaria**: base para extensões futuras (estrutura preparada na base de dados).

## 🔐 Segurança Essencial

- Sessões PHP com cookies `httponly` e `SameSite=Lax`
- Passwords hashadas com `password_hash`
- Restrições por perfil em todas as rotas protegidas

## 📦 Deployment na Vercel

O ficheiro `vercel.json` está preparado para utilização do runtime `@vercel/php`. Certifique-se que as variáveis de ambiente de base de dados estão configuradas na Vercel antes do deploy de produção.

## 🧪 Testes Rápidos

Depois de configurar a base de dados execute:
```bash
php -l index.php actions/*.php pages/*.php
```
para validar a sintaxe PHP.

---

Projeto desenvolvido para digitalizar a gestão académica do Instituto Industrial e de Computação Armando Emílio Guebuza.
