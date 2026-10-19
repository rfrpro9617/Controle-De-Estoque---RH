-- TODO: excluir os registros abaixos cadastrados para teste
INSERT INTO estoque_fornecedores (nome, ativo) VALUES
('Tech Distribuidora Ltda', 1),
('Global Office Suprimentos', 1),
('Comercial Alfa Brasil', 1),
('Mega Informática e Equipamentos', 1),
('Logística Express Ltda', 0);

INSERT INTO estoque_setores (nome, sigla, ativo) VALUES
('Almoxarifado', 'ALMOX', 1),
('Informática', 'INFO', 1),
('Administrativo', 'ADM', 1);