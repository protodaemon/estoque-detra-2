-- =========================
-- LIMPAR TABELAS EXISTENTES (OPCIONAL)
-- =========================
DROP TABLE IF EXISTS produtos_pedido_consumivel;
DROP TABLE IF EXISTS pedido_consumivel;
DROP TABLE IF EXISTS saida_consumivel;
DROP TABLE IF EXISTS entrada_consumivel;
DROP TABLE IF EXISTS produtos_consumivel;
DROP TABLE IF EXISTS categoria_consumivel;
DROP TABLE IF EXISTS usuario;

-- =========================
-- TABELA USUARIO
-- =========================
CREATE TABLE
  `usuario` (
    `usuario_id` int unsigned NOT NULL AUTO_INCREMENT,
    `user` varchar(255) DEFAULT NULL,
    `senha` varchar(255) DEFAULT NULL,
    `nome` varchar(255) DEFAULT NULL,
    `email_rec` varchar(255) DEFAULT NULL,
    `nivel_acesso` varchar(20) NOT NULL DEFAULT 'padrao',
    PRIMARY KEY (`usuario_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci

-- =========================
-- TABELA CATEGORIA_CONSUMIVEL
-- =========================
CREATE TABLE categoria_consumivel (
  categoria_consumivel_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  nome varchar(90) NOT NULL,
  descricao varchar(255) DEFAULT NULL,
  PRIMARY KEY (categoria_consumivel_id),
  UNIQUE KEY nome_unico_decoracao (nome)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- TABELA PRODUTOS_CONSUMIVEL
-- =========================
CREATE TABLE produtos_consumivel (
  produtos_consumivel_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  nome varchar(90) NOT NULL,
  descricao varchar(500) DEFAULT NULL,
  observacoes varchar(500) DEFAULT NULL,
  foto varchar(255) DEFAULT NULL,
  categoria_consumivel int(10) unsigned DEFAULT NULL,
  ativo tinyint(1) NOT NULL DEFAULT 1,
  quantidade int(11) DEFAULT 0,
  PRIMARY KEY (produtos_consumivel_id),
  KEY fk_categoria_patrimonio2 (categoria_consumivel),
  CONSTRAINT fk_categoria_consumivel FOREIGN KEY (categoria_consumivel)
    REFERENCES categoria_consumivel (categoria_consumivel_id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=571 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- TABELA PEDIDO_CONSUMIVEL
-- =========================
CREATE TABLE pedido_consumivel (
  pedido_consumivel_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  status varchar(12) DEFAULT 'pendente',
  data_pedido date DEFAULT NULL,
  data_cancelamento date DEFAULT NULL,
  data_alteracao date DEFAULT NULL,
  observacoes varchar(500) DEFAULT NULL,
  PRIMARY KEY (pedido_consumivel_id)
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- TABELA PRODUTOS_PEDIDO_CONSUMIVEL
-- =========================
CREATE TABLE produtos_pedido_consumivel (
  produtos_pedido_consumivel_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  pedido_consumivel_id int(10) unsigned NOT NULL,
  produtos_consumivel_id int(10) unsigned NOT NULL,
  quantidade int(11) DEFAULT NULL,
  PRIMARY KEY (produtos_pedido_consumivel_id),
  KEY fk_pedido_consumivel (pedido_consumivel_id),
  KEY fk_produtos_consumivel (produtos_consumivel_id),
  CONSTRAINT fk_pedido_consumivel FOREIGN KEY (pedido_consumivel_id)
    REFERENCES pedido_consumivel (pedido_consumivel_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_produtos_consumivel FOREIGN KEY (produtos_consumivel_id)
    REFERENCES produtos_consumivel (produtos_consumivel_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- TABELA SAIDA_CONSUMIVEL
-- =========================
CREATE TABLE saida_consumivel (
  saida_consumivel_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  produto_consumivel_id int(10) unsigned NOT NULL,
  quantidade int(11) NOT NULL,
  data_saida date DEFAULT NULL,
  observacao varchar(255) DEFAULT NULL,
  PRIMARY KEY (saida_consumivel_id),
  KEY fk_produto_saida_consumivel (produto_consumivel_id),
  CONSTRAINT fk_produto_saida_consumivel FOREIGN KEY (produto_consumivel_id)
    REFERENCES produtos_consumivel (produtos_consumivel_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- TABELA ENTRADA_CONSUMIVEL
-- =========================
CREATE TABLE entrada_consumivel (
  entrada_consumivel_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  produto_consumivel_id int(10) unsigned NOT NULL,
  quantidade int(11) NOT NULL,
  data_entrada date DEFAULT NULL,
  observacao varchar(255) DEFAULT NULL,
  PRIMARY KEY (entrada_consumivel_id),
  KEY fk_produto_consumivel (produto_consumivel_id),
  CONSTRAINT fk_produto_consumivel FOREIGN KEY (produto_consumivel_id)
    REFERENCES produtos_consumivel (produtos_consumivel_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- INSERT CATEGORIA_CONSUMIVEL
-- =========================
INSERT INTO categoria_consumivel (categoria_consumivel_id, nome, descricao) VALUES
(50, 'Eletrônicos', 'Notebooks, projetores, monitores e outros dispositivos eletrônicos.'),
(51, 'Mobiliário de Escritório', 'Mesas, cadeiras, armários e estantes.'),
(52, 'Eletrodomésticos e Utensílios', 'Cafeteiras, micro-ondas e itens de copa.'),
(53, 'Equipamentos de TI', 'Computadores, impressoras e periféricos de TI.'),
(54, 'Móveis Diversos', 'Outros tipos de móveis não classificados anteriormente.'),
(55, 'Mobiliário Geral', 'Sofás e outros itens de uso geral.'),
(56, 'Equipamentos Audiovisuais', 'Câmeras, caixas de som e aparelhos de áudio.');

-- =========================
-- INSERT PRODUTOS_CONSUMIVEL (exemplo inicial, IDs de categoria corrigidos)
-- =========================
INSERT INTO produtos_consumivel (nome, descricao, quantidade, observacoes, categoria_consumivel, ativo) VALUES
('Notebook 149', 'Necessita de manutenção preventiva.', 5, 'Localizado no escritório principal.', 50, 0),
('Impressora 518', 'Em ótimo estado, pouco utilizado.', 3, 'Disponível na sala de reuniões.', 53, 1),
('Televisão 824', 'Novo, ainda em período de garantia.', 2, 'Sob responsabilidade do setor de manutenção.', 50, 1),
('Armário 557', 'Em ótimo estado, pouco utilizado.', 10, 'Localizado no escritório principal.', 51, 1),
('Cadeira 840', 'Necessita de manutenção preventiva.', 25, 'Sob responsabilidade do setor de manutenção.', 51, 1),
('Computador 501', 'Item essencial para o funcionamento do setor.', 15, 'Sob responsabilidade do setor de manutenção.', 53, 1),
('Computador 704', 'Equipamento em bom estado de conservação.', 8, 'Sob responsabilidade do setor de manutenção.', 53, 0),
('Notebook 133', 'Necessita de manutenção preventiva.', 7, 'Inventariado no último balanço patrimonial.', 50, 0),
('Armário 402', 'Novo, ainda em período de garantia.', 4, 'Disponível na sala de reuniões.', 51, 1),
('Mesa 135', 'Utilizado em treinamentos e reuniões.', 12, 'Inventariado no último balanço patrimonial.', 51, 0),
('Cafeteira 410', 'Equipamento em bom estado de conservação.', 2, 'Emprestado temporariamente para outro setor.', 52, 0),
('Sofá 537', 'Equipamento em bom estado de conservação.', 3, 'Localizado no escritório principal.', 55, 0),
('Notebook 554', 'Novo, ainda em período de garantia.', 6, 'Etiqueta de patrimônio visível.', 50, 0),
('Caixa de Som 744', 'Apresenta sinais de desgaste, mas funcional.', 4, 'Utilizado pela equipe administrativa.', 56, 0),
('Armário 720', 'Novo, ainda em período de garantia.', 8, 'Alocado no setor de TI.', 51, 0),
('Câmera 665', 'Precisa de substituição em breve.', 3, 'Inventariado no último balanço patrimonial.', 56, 0),
('Projetor 789', 'Em ótimo estado, pouco utilizado.', 2, 'Inventariado no último balanço patrimonial.', 50, 0),
('Televisão 953', 'Equipamento em bom estado de conservação.', 4, 'Disponível na sala de reuniões.', 50, 0),
('Estante 988', 'Item essencial para o funcionamento do setor.', 6, 'Disponível na sala de reuniões.', 51, 1),
('Estante 998', 'Apresenta sinais de desgaste, mas funcional.', 5, 'Localizado no escritório principal.', 51, 0),
('Ventilador 113', 'Item adquirido recentemente para uso administrativo.', 10, 'Utilizado pela equipe administrativa.', 52, 1),
('Armário 277', 'Em ótimo estado, pouco utilizado.', 7, 'Utilizado pela equipe administrativa.', 51, 0),
('Computador 84', 'Precisa de substituição em breve.', 9, 'Sob responsabilidade do setor de manutenção.', 53, 0),
('Cafeteira 886', 'Novo, ainda em período de garantia.', 3, 'Requer atualização no sistema de controle.', 52, 1),
('Notebook 300', 'Apresenta sinais de desgaste, mas funcional.', 11, 'Pertence ao almoxarifado central.', 50, 1),
('Impressora 274', 'Necessita de manutenção preventiva.', 4, 'Localizado no escritório principal.', 53, 0),
('Micro-ondas 205', 'Precisa de substituição em breve.', 2, 'Emprestado temporariamente para outro setor.', 52, 0),
('Projetor 392', 'Novo, ainda em período de garantia.', 3, 'Requer atualização no sistema de controle.', 50, 0),
('Micro-ondas 690', 'Apresenta sinais de desgaste, mas funcional.', 1, 'Utilizado pela equipe administrativa.', 52, 0),
('Computador 564', 'Item essencial para o funcionamento do setor.', 13, 'Localizado no escritório principal.', 53, 0),
('Notebook 41', 'Produto antigo, porém em funcionamento.', 8, 'Disponível na sala de reuniões.', 50, 1),
('Micro-ondas 809', 'Item essencial para o funcionamento do setor.', 2, 'Pertence ao almoxarifado central.', 52, 0),
('Impressora 85', 'Apresenta sinais de desgaste, mas funcional.', 5, 'Disponível na sala de reuniões.', 53, 0),
('Cadeira 977', 'Produto antigo, porém em funcionamento.', 30, 'Disponível na sala de reuniões.', 51, 1),
('Impressora 271', 'Utilizado em treinamentos e reuniões.', 2, 'Utilizado pela equipe administrativa.', 53, 1),
('Televisão 796', 'Necessita de manutenção preventiva.', 3, 'Inventariado no último balanço patrimonial.', 50, 1),
('Ventilador 748', 'Item essencial para o funcionamento do setor.', 8, 'Alocado no setor de TI.', 52, 1),
('Computador 984', 'Em ótimo estado, pouco utilizado.', 20, 'Etiqueta de patrimônio visível.', 53, 1),
('Telefone 833', 'Produto antigo, porém em funcionamento.', 15, 'Alocado no setor de TI.', 56, 0),
('Armário 773', 'Utilizado em treinamentos e reuniões.', 6, 'Disponível na sala de reuniões.', 51, 0),
('Impressora 92', 'Em ótimo estado, pouco utilizado.', 7, 'Sob responsabilidade do setor de manutenção.', 53, 1),
('Armário 431', 'Equipamento em bom estado de conservação.', 9, 'Pertence ao almoxarifado central.', 51, 1),
('Computador 187', 'Equipamento em bom estado de conservação.', 11, 'Sob responsabilidade do setor de manutenção.', 53, 0),
('Aparelho de Som 133', 'Apresenta sinais de desgaste, mas funcional.', 3, 'Localizado no escritório principal.', 56, 1),
('Armário 854', 'Apresenta sinais de desgaste, mas funcional.', 12, 'Requer atualização no sistema de controle.', 51, 1),
('Monitor 386', 'Precisa de substituição em breve.', 22, 'Etiqueta de patrimônio visível.', 50, 1),
('Projetor 957', 'Utilizado em treinamentos e reuniões.', 4, 'Disponível na sala de reuniões.', 50, 1),
('Projetor 991', 'Utilizado em treinamentos e reuniões.', 1, 'Pertence ao almoxarifado central.', 50, 0),
('Sofá 404', 'Utilizado em treinamentos e reuniões.', 2, 'Localizado no escritório principal.', 55, 1),
('Computador 611', 'Precisa de substituição em breve.', 6, 'Sob responsabilidade do setor de manutenção.', 53, 0),
('Monitor 74', 'Produto antigo, porém em funcionamento.', 18, 'Alocado no setor de TI.', 50, 0),
('Cafeteira 620', 'Equipamento em bom estado de conservação.', 2, 'Inventariado no último balanço patrimonial.', 52, 0),
('Câmera 866', 'Precisa de substituição em breve.', 5, 'Etiqueta de patrimônio visível.', 56, 1),
('Aparelho de Som 402', 'Equipamento em bom estado de conservação.', 4, 'Disponível na sala de reuniões.', 56, 1),
('Caixa de Som 520', 'Precisa de substituição em breve.', 6, 'Inventariado no último balanço patrimonial.', 56, 1),
('Ventilador 146', 'Necessita de manutenção preventiva.', 7, 'Localizado no escritório principal.', 52, 0),
('Televisão 605', 'Equipamento em bom estado de conservação.', 5, 'Inventariado no último balanço patrimonial.', 50, 1),
('Armário 934', 'Produto antigo, porém em funcionamento.', 11, 'Etiqueta de patrimônio visível.', 51, 0),
('Impressora 835', 'Apresenta sinais de desgaste, mas funcional.', 3, 'Etiqueta de patrimônio visível.', 53, 0),
('Monitor 144', 'Precisa de substituição em breve.', 14, 'Sob responsabilidade do setor de manutenção.', 50, 1),
('Cadeira 11', 'Apresenta sinais de desgaste, mas funcional.', 40, 'Pertence ao almoxarifado central.', 51, 1),
('Aparelho de Som 18', 'Produto antigo, porém em funcionamento.', 2, 'Disponível na sala de reuniões.', 56, 0),
('Estante 693', 'Produto antigo, porém em funcionamento.', 8, 'Etiqueta de patrimônio visível.', 51, 0),
('Cadeira 284', 'Item essencial para o funcionamento do setor.', 16, 'Emprestado temporariamente para outro setor.', 51, 0),
('Cadeira 239', 'Precisa de substituição em breve.', 10, 'Etiqueta de patrimônio visível.', 51, 0),
('Aparelho de Som 101', 'Novo, ainda em período de garantia.', 5, 'Disponível na sala de reuniões.', 56, 1),
('Mesa 431', 'Produto antigo, porém em funcionamento.', 12, 'Utilizado pela equipe administrativa.', 51, 0),
('Estante 573', 'Item essencial para o funcionamento do setor.', 9, 'Pertence ao almoxarifado central.', 51, 1),
('Aparelho de Som 304', 'Necessita de manutenção preventiva.', 3, 'Requer atualização no sistema de controle.', 56, 0),
('Cadeira 430', 'Utilizado em treinamentos e reuniões.', 18, 'Etiqueta de patrimônio visível.', 51, 0),
('Estante 116', 'Apresenta sinais de desgaste, mas funcional.', 4, 'Sob responsabilidade do setor de manutenção.', 51, 1),
('Câmera 302', 'Utilizado em treinamentos e reuniões.', 6, 'Disponível na sala de reuniões.', 56, 1),
('Caixa de Som 138', 'Em ótimo estado, pouco utilizado.', 8, 'Pertence ao almoxarifado central.', 56, 1),
('Mesa 199', 'Necessita de manutenção preventiva.', 7, 'Inventariado no último balanço patrimonial.', 51, 0),
('Cadeira 553', 'Item essencial para o funcionamento do setor.', 20, 'Localizado no escritório principal.', 51, 0),
('Armário 39', 'Equipamento em bom estado de conservação.', 13, 'Disponível na sala de reuniões.', 51, 1),
('Cadeira 428', 'Produto antigo, porém em funcionamento.', 25, 'Inventariado no último balanço patrimonial.', 51, 0),
('Estante 901', 'Em ótimo estado, pouco utilizado.', 6, 'Sob responsabilidade do setor de manutenção.', 51, 0),
('Telefone 733', 'Apresenta sinais de desgaste, mas funcional.', 10, 'Pertence ao almoxarifado central.', 56, 0),
('Cadeira 613', 'Item adquirido recentemente para uso administrativo.', 28, 'Disponível na sala de reuniões.', 51, 0),
('Geladeira 711', 'Necessita de manutenção preventiva.', 2, 'Utilizado pela equipe administrativa.', 52, 1),
('Cafeteira 23', 'Precisa de substituição em breve.', 1, 'Localizado no escritório principal.', 52, 0),
('Cafeteira 784', 'Em ótimo estado, pouco utilizado.', 4, 'Utilizado pela equipe administrativa.', 52, 1),
('Mesa 446', 'Item essencial para o funcionamento do setor.', 14, 'Alocado no setor de TI.', 51, 1),
('Impressora 15', 'Item essencial para o funcionamento do setor.', 6, 'Emprestado temporariamente para outro setor.', 53, 1);

-- =========================
