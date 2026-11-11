CREATE DATABASE IF NOT EXISTS vetcare_djeniffer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vetcare_djeniffer;

DROP TABLE IF EXISTS consultas;
DROP TABLE IF EXISTS animais;
DROP TABLE IF EXISTS tutores;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  perfil VARCHAR(20) DEFAULT 'admin'
);

INSERT INTO usuarios (nome, usuario, senha, perfil) VALUES
('Administrador VetCare', 'admin', 'admin123', 'admin');

CREATE TABLE tutores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  telefone VARCHAR(30),
  email VARCHAR(120),
  endereco VARCHAR(180),
  ativo TINYINT(1) DEFAULT 1,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE animais (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tutor_id INT NOT NULL,
  nome VARCHAR(100) NOT NULL,
  especie VARCHAR(60),
  raca VARCHAR(80),
  nascimento DATE,
  sexo VARCHAR(20),
  observacoes TEXT,
  ativo TINYINT(1) DEFAULT 1,
  CONSTRAINT fk_animal_tutor FOREIGN KEY (tutor_id) REFERENCES tutores(id)
);

CREATE TABLE consultas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  animal_id INT NOT NULL,
  data_consulta DATETIME NOT NULL,
  veterinario VARCHAR(120) NOT NULL,
  motivo VARCHAR(180),
  status VARCHAR(30) DEFAULT 'Agendada',
  observacoes TEXT,
  CONSTRAINT fk_consulta_animal FOREIGN KEY (animal_id) REFERENCES animais(id)
);

INSERT INTO tutores (nome, telefone, email, endereco, ativo) VALUES
('Mariana Costa', '(47) 99999-1001', 'mariana@email.com', 'Centro', 1),
('Rafael Mendes', '(47) 98888-2002', 'rafael@email.com', 'Bairro Cruzeiro', 1),
('Camila Oliveira', '(47) 97777-3003', 'camila@email.com', 'Serra Alta', 1),
('João Pedro Lima', 'abc123', 'joao@email.com', '25 de Julho', 0);

INSERT INTO animais (tutor_id, nome, especie, raca, nascimento, sexo, observacoes, ativo) VALUES
(1, 'Mel', 'Cão', 'Golden Retriever', '2021-05-10', 'Fêmea', 'Alergia alimentar', 1),
(2, 'Mingau', 'Gato', 'SRD', '2023-11-20', 'Macho', 'Vacinação em dia', 1),
(3, 'Thor', 'Cão', 'Labrador', '2025-10-30', 'Macho', '', 1),
(4, 'Pipoca', 'Coelho', 'Mini Lop', '2024-02-15', 'Fêmea', '', 0);

INSERT INTO consultas (animal_id, data_consulta, veterinario, motivo, status, observacoes) VALUES
(1, DATE_ADD(NOW(), INTERVAL 2 DAY), 'Dra. Paula', 'Retorno dermatológico', 'Agendada', ''),
(2, DATE_SUB(NOW(), INTERVAL 3 DAY), 'Dr. André', 'Avaliação de rotina', 'Realizada', ''),
(3, DATE_ADD(NOW(), INTERVAL 5 DAY), 'Dra. Paula', 'Vacinação', 'Agendada', '');


DROP TABLE IF EXISTS historico_chamados;
DROP TABLE IF EXISTS chamados;

CREATE TABLE chamados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(160) NOT NULL,
  modulo VARCHAR(60) NOT NULL,
  solicitante VARCHAR(120) NOT NULL,
  equipe VARCHAR(100),
  descricao TEXT NOT NULL,
  passos TEXT NOT NULL,
  resultado_esperado TEXT NOT NULL,
  resultado_obtido TEXT NOT NULL,
  severidade ENUM('Baixa','Média','Alta','Crítica') NOT NULL DEFAULT 'Média',
  prioridade ENUM('Baixa','Média','Alta','Urgente') NOT NULL DEFAULT 'Média',
  status ENUM('Aberto','Em análise','Aguardando informações','Resolvido','Fechado') NOT NULL DEFAULT 'Aberto',
  responsavel VARCHAR(120),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE historico_chamados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  chamado_id INT NOT NULL,
  autor VARCHAR(120) NOT NULL,
  comentario TEXT NOT NULL,
  status_anterior VARCHAR(40),
  status_novo VARCHAR(40),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_historico_chado FOREIGN KEY (chamado_id) REFERENCES chamados(id) ON DELETE CASCADE
);

INSERT INTO chamados (titulo, modulo, solicitante, equipe, descricao, passos, resultado_esperado, resultado_obtido, severidade, prioridade, status, responsavel) VALUES
('Menu principal quebra ao navegar em subpastas', 'Dashboard', 'Ana Souza', 'TDS 2A', 'Os links do cabeçalho usam caminhos relativos e quebram quando a página está dentro de uma subpasta.', '1. Acesse /vetcare_manutencao_djeniffer/animais/index.php; 2. Clique no menu Painel; 3. Observe a URL gerada.', 'O sistema deve navegar corretamente para /vetcare_manutencao_djeniffer/index.php.', 'A navegação direciona para URLs inválidas, como /animais/tutores/index.php ou /consultas/index.php.', 'Alta', 'Alta', 'Aberto', 'Equipe de Frontend'),
('Botão de cadastro de tutor cancela o clique', 'Tutores', 'Bruno Lima', 'Grupo 04', 'O botão Novo tutor está com JavaScript preventDefault e não abre o formulário.', '1. Acesse a listagem de tutores; 2. Clique em + Novo tutor; 3. Observe que nada acontece.', 'O botão deve abrir form.php e permitir cadastro.', 'O clique é interrompido por um alert e o formulário não é exibido.', 'Crítica', 'Urgente', 'Em análise', 'Dev Web'),
('Formulário de animais falha ao editar registro', 'Animais', 'Carla M.', 'Turma 202', 'A edição de animais dispara erro de sintaxe ao usar spread em array no UPDATE.', '1. Acesse Animais; 2. Clique em Editar; 3. Salve a alteração; 4. Veja o erro.', 'O sistema deve salvar as alterações sem erro.', 'A página retorna Parse error: unexpected ...', 'Alta', 'Alta', 'Aberto', 'Backend'),
('Formulário de consultas falha no mesmo ponto do animal', 'Consultas', 'Diego N.', 'Equipe 3', 'O formulário de consultas usa a mesma sintaxe incompatível em array_merge e quebra ao salvar.', '1. Acesse Consultas; 2. Clique em Nova consulta; 3. Preencha e envie; 4. Observe o erro.', 'O cadastro deve ser registrado no banco.', 'A aplicação para com erro de parser antes de qualquer gravação.', 'Alta', 'Urgente', 'Aberto', 'Backend'),
('Banco de dados inconsistente entre config e SQL', 'Dashboard', 'Eduarda K.', 'G1', 'A conexão aponta para vetcare, enquanto o script de banco cria vetcare_djeniffer.', '1. Importar database/vetcare.sql; 2. Tentar entrar no sistema; 3. Verificar conexão.', 'O sistema deve conectar no banco correto e listar os dados.', 'Páginas retornam erro de banco ou tabela inexistente.', 'Crítica', 'Urgente', 'Aguardando informações', 'Infra'),
('Cadastro de animais não aceita tutor vazio', 'Animais', 'Fernanda R.', 'Clinica Vet', 'Ao enviar sem selecionar tutor, o campo tutor_id é convertido para 0 e a FK falha.', '1. Acesse Novo animal; 2. Deixe Tutor em branco; 3. Envie o form.', 'O sistema deve validar e impedir cadastro incompleto.', 'O submit falha com erro de integridade referencial.', 'Média', 'Alta', 'Em análise', 'QA'),
('Lista de animais exibe idade incorreta', 'Animais', 'Gabriel S.', 'VetCare 01', 'A idade calcula a diferença de ano e não a idade real em anos completos.', '1. Acesse Animais; 2. Observe registros com nascimento em 2021 e 2025; 3. Compare com a idade exibida.', 'A idade deve respeitar anos completos entre datas.', 'A conta mostra valores inconsistentes e às vezes negativos ou zero.', 'Média', 'Média', 'Aberto', 'Negócios'),
('Filtro de consultas não aplica status corretamente', 'Consultas', 'Helena T.', 'Grupo Delta', 'Quando o usuário filtra por status, a query não usa o parâmetro de busca.', '1. Acesse Consultas; 2. Escolha "Realizada"; 3. Clique em Filtrar.', 'A listagem deve mostrar apenas consultas com o status selecionado.', 'A tabela continua mostrando todas as consultas independente do filtro.', 'Média', 'Média', 'Aguardando informações', 'QA'),
('Botão de excluir tutor não confirma antes da remoção', 'Tutores', 'Igor M.', 'A', 'O comando de exclusão não pede confirmação e remove registros com apenas um clique.', '1. Acesse listagem de tutores; 2. Clique em Excluir; 3. Observe que o registro some imediatamente.', 'O sistema deve pedir confirmação antes de remover o registro.', 'A exclusão é executada sem qualquer validação de segurança.', 'Média', 'Alta', 'Aberto', 'UX'),
('Chamados não registram histórico ao criar novo ticket', 'Suporte', 'Júlia P.', 'Equipe suporte', 'O código insere o chamado e tenta inserir o histórico, mas o campo status_novo pode ser nulo ou inconsistentes.', '1. Abrir um chamado; 2. Verificar histórico; 3. Conferir o registro criado.', 'O histórico deve conter o status inicial do chamado.', 'O histórico fica incompleto ou não é salvo corretamente.', 'Alta', 'Alta', 'Em análise', 'Suporte'),
('Links do menu não funcionam em páginas da pasta animais', 'Dashboard', 'Kauã V.', 'QA 01', 'O cabeçalho usa hrefs relativos e o menu quebra ao acessar páginas internas.', '1. Acesse /animais/index.php; 2. Tente abrir qualquer link do menu; 3. Observe as URLs.', 'Cada link deve abrir a página correta independentemente do diretório atual.', 'Os links levam para caminhos incorretos e a navegação falha.', 'Alta', 'Alta', 'Resolvido', 'Frontend'),
('Login redireciona para rota errada quando não autenticado', 'Login', 'Luan C.', 'Segurança', 'A autenticação usa a URL /vetcare_manutencao_djeniffer/login.php, mas em alguns cenários a rota pode não existir.', '1. Tente acessar qualquer página sem login; 2. Observe o redirecionamento.', 'O sistema deve levar o usuário para a tela de autenticação.', 'O navegador tenta acessar uma rota inválida e cai em erro.', 'Alta', 'Alta', 'Aberto', 'Segurança'),
('Painel inicial mostra consultas com data inválida', 'Dashboard', 'Maya F.', 'Operação', 'A tabela Próximas consultas usa strtotime em campos nulos ou em datas sem formatação correta.', '1. Acesse o Dashboard; 2. Verifique as próximas consultas; 3. Procure por valores quebrados.', 'As datas devem aparecer em formato válido e sem erro.', 'A página pode lançar warnings ou mostrar datas incoerentes.', 'Média', 'Média', 'Aberto', 'Operação');

INSERT INTO historico_chamados (chamado_id, autor, comentario, status_anterior, status_novo) VALUES
(1, 'Ana Souza', 'Registro inicial de falha em navegação.', 'Aberto', 'Em análise'),
(2, 'Bruno Lima', 'Confirmado bloqueio do clique no botão Novo tutor.', 'Aberto', 'Em análise'),
(3, 'Carla M.', 'Erro de parse identificado no UPDATE de animais.', 'Aberto', 'Aberto'),
(4, 'Diego N.', 'Erro de parse identificado no UPDATE de consultas.', 'Aberto', 'Aberto'),
(5, 'Eduarda K.', 'Inconsistência de banco identificada no arquivo de conexão.', 'Aberto', 'Aguardando informações'),
(6, 'Fernanda R.', 'Validação de tutor obrigatório pendente.', 'Aberto', 'Em análise'),
(7, 'Gabriel S.', 'Idade exibida inconsistente.', 'Aberto', 'Aberto'),
(8, 'Helena T.', 'Filtro de consultas não aplicado.', 'Aberto', 'Aguardando informações'),
(9, 'Igor M.', 'Exclusão sem confirmação confirmada.', 'Aberto', 'Aberto'),
(10, 'Júlia P.', 'Histórico incompleto ao registrar chamado.', 'Aberto', 'Em análise'),
(11, 'Kauã V.', 'Correto: menu em subpastas está quebrado.', 'Resolvido', 'Resolvido'),
(12, 'Luan C.', 'Redirecionamento sem autenticação identificado.', 'Aberto', 'Aberto'),
(13, 'Maya F.', 'Data inválida no painel identificada.', 'Aberto', 'Aberto');
