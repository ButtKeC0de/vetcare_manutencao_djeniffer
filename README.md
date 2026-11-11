# VetCare — Sistema veterinário para atividade de Manutenção de Sistemas

Projeto didático em PHP + MySQL/MariaDB para execução local no XAMPP.

## Instalação
1. Copie a pasta `vetcare_manutencao` para `C:\xampp\htdocs\`.
2. Inicie Apache e MySQL no XAMPP.
3. Acesse `http://localhost/phpmyadmin` e importe `database/vetcare.sql`.
4. Acesse `http://localhost/vetcare_manutencao/`.
5. Entre com:
   - Usuário: `admin`
   - Senha: `admin123`

> **Atenção:** este sistema contém falhas intencionais para uma atividade de identificação, registro e priorização de chamados. Use somente em ambiente local isolado, com dados fictícios. Não publique na internet nem utilize dados reais.

## Funcionalidades
- Login e logout
- Dashboard
- CRUD de tutores
- CRUD de animais
- CRUD de consultas
- Busca/listagem e exclusão de registros
- Central de suporte: abertura, consulta, filtros, atualização de status, responsável e histórico de comentários

## Proposta de atividade
Os estudantes devem testar o sistema, reproduzir comportamentos inesperados, registrar evidências e abrir chamados contendo:
- título objetivo;
- módulo afetado;
- pré-condições;
- passos para reprodução;
- resultado atual;
- resultado esperado;
- severidade/prioridade;
- evidência (captura de tela ou dados usados).

A central de suporte fica no menu **Suporte**. Os estudantes podem abrir chamados com título, módulo, descrição, passos de reprodução, resultados esperado/obtido, severidade e prioridade. O histórico registra comentários e mudanças de status.

O arquivo `DOCENTE_GABARITO.md` contém o inventário das falhas intencionais. Não o disponibilize aos estudantes antes da atividade.

## Estrutura
- `config/`: conexão e autenticação
- `includes/`: cabeçalho e navegação
- `tutores/`, `animais/`, `consultas/`: operações CRUD
- `database/vetcare.sql`: banco e dados fictícios
