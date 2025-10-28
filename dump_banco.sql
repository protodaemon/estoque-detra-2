CREATE TABLE
  `categoria_consumivel` (
    `categoria_consumivel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `nome` varchar(90) NOT NULL,
    `descricao` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`categoria_consumivel_id`),
    UNIQUE KEY `nome_unico_decoracao` (`nome`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 50 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

  CREATE TABLE
  `produtos_consumivel` (
    `produtos_consumivel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `nome` varchar(90) NOT NULL,
    `descricao` varchar(500) DEFAULT NULL,
    `observacoes` varchar(500) DEFAULT NULL,
    `foto` varchar(255) DEFAULT NULL,
    `categoria_consumivel` int(10) unsigned DEFAULT NULL,
    `ativo` tinyint(1) NOT NULL DEFAULT 1,
    `quantidade` int(11) DEFAULT 0,
    PRIMARY KEY (`produtos_consumivel_id`),
    KEY `fk_categoria_patrimonio2` (`categoria_consumivel`),
    CONSTRAINT `fk_categoria_consumivel` FOREIGN KEY (`categoria_consumivel`) REFERENCES `categoria_consumivel` (`categoria_consumivel_id`) ON DELETE
    SET
      NULL ON UPDATE CASCADE
  ) ENGINE = InnoDB AUTO_INCREMENT = 571 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

  CREATE TABLE
  `pedido_consumivel` (
    `pedido_consumivel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `status` varchar(12) DEFAULT 'pendente',
    `data_pedido` date DEFAULT NULL,
    `data_cancelamento` date DEFAULT NULL,
    `data_alteração` date DEFAULT NULL,
    `observacoes` varchar(500) DEFAULT NULL,
    PRIMARY KEY (`pedido_consumivel_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 156 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

  CREATE TABLE
  `produtos_pedido_consumivel` (
    `produtos_pedido_consumivel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `pedido_consumivel_id` int(10) unsigned NOT NULL,
    `produtos_consumivel_id` int(10) unsigned NOT NULL,
    `quantidade` int(11) DEFAULT NULL,
    PRIMARY KEY (`produtos_pedido_consumivel_id`),
    KEY `fk_pedido_consumivel` (`pedido_consumivel_id`),
    KEY `fk_produtos_consumivel` (`produtos_consumivel_id`),
    CONSTRAINT `fk_pedido_consumivel` FOREIGN KEY (`pedido_consumivel_id`) REFERENCES `pedido_consumivel` (`pedido_consumivel_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_produtos_consumivel` FOREIGN KEY (`produtos_consumivel_id`) REFERENCES `produtos_consumivel` (`produtos_consumivel_id`) ON DELETE CASCADE ON UPDATE CASCADE
  ) ENGINE = InnoDB AUTO_INCREMENT = 229 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

INSERT INTO categoria_consumivel (nome, descricao) VALUES
('Eletrônicos', 'Notebooks, projetores, monitores e outros dispositivos eletrônicos.'),
('Mobiliário de Escritório', 'Mesas, cadeiras, armários e estantes.'),
('Eletrodomésticos e Utensílios', 'Cafeteiras, micro-ondas e itens de copa.'),
('Equipamentos de TI', 'Computadores, impressoras e periféricos de TI.'),
('Móveis Diversos', 'Outros tipos de móveis não classificados anteriormente.'),
('Mobiliário Geral', 'Sofás e outros itens de uso geral.'),
('Equipamentos Audiovisuais', 'Câmeras, caixas de som e aparelhos de áudio.'),
('ssssasasa', 'sdsd');

  INSERT INTO produtos_consumivel (nome, descricao, quantidade, observacoes, categoria_consumivel, ativo) VALUES
('Notebook 149', 'Necessita de manutenção preventiva.', 5, 'Localizado no escritório principal.', 33, 0),
('Impressora 518', 'Em ótimo estado, pouco utilizado.', 3, 'Disponível na sala de reuniões.', 36, 1),
('Televisão 824', 'Novo, ainda em período de garantia.', 2, 'Sob responsabilidade do setor de manutenção.', 33, 1),
('Armário 557', 'Em ótimo estado, pouco utilizado.', 10, 'Localizado no escritório principal.', 34, 1),
('Cadeira 840', 'Necessita de manutenção preventiva.', 25, 'Sob responsabilidade do setor de manutenção.', 34, 1),
('Computador 501', 'Item essencial para o funcionamento do setor.', 15, 'Sob responsabilidade do setor de manutenção.', 36, 1),
('Computador 704', 'Equipamento em bom estado de conservação.', 8, 'Sob responsabilidade do setor de manutenção.', 36, 0),
('Notebook 133', 'Necessita de manutenção preventiva.', 7, 'Inventariado no último balanço patrimonial.', 33, 0),
('Armário 402', 'Novo, ainda em período de garantia.', 4, 'Disponível na sala de reuniões.', 34, 1),
('Mesa 135', 'Utilizado em treinamentos e reuniões.', 12, 'Inventariado no último balanço patrimonial.', 34, 0),
('Cafeteira 410', 'Equipamento em bom estado de conservação.', 2, 'Emprestado temporariamente para outro setor.', 35, 0),
('Sofá 537', 'Equipamento em bom estado de conservação.', 3, 'Localizado no escritório principal.', 38, 0),
('Notebook 554', 'Novo, ainda em período de garantia.', 6, 'Etiqueta de patrimônio visível.', 33, 0),
('Caixa de Som 744', 'Apresenta sinais de desgaste, mas funcional.', 4, 'Utilizado pela equipe administrativa.', 39, 0),
('Armário 720', 'Novo, ainda em período de garantia.', 8, 'Alocado no setor de TI.', 34, 0),
('Câmera 665', 'Precisa de substituição em breve.', 3, 'Inventariado no último balanço patrimonial.', 39, 0),
('Projetor 789', 'Em ótimo estado, pouco utilizado.', 2, 'Inventariado no último balanço patrimonial.', 33, 0),
('Televisão 953', 'Equipamento em bom estado de conservação.', 4, 'Disponível na sala de reuniões.', 33, 0),
('Estante 988', 'Item essencial para o funcionamento do setor.', 6, 'Disponível na sala de reuniões.', 34, 1),
('Estante 998', 'Apresenta sinais de desgaste, mas funcional.', 5, 'Localizado no escritório principal.', 34, 0),
('Ventilador 113', 'Item adquirido recentemente para uso administrativo.', 10, 'Utilizado pela equipe administrativa.', 35, 1),
('Armário 277', 'Em ótimo estado, pouco utilizado.', 7, 'Utilizado pela equipe administrativa.', 34, 0),
('Computador 84', 'Precisa de substituição em breve.', 9, 'Sob responsabilidade do setor de manutenção.', 36, 0),
('Cafeteira 886', 'Novo, ainda em período de garantia.', 3, 'Requer atualização no sistema de controle.', 35, 1),
('Notebook 300', 'Apresenta sinais de desgaste, mas funcional.', 11, 'Pertence ao almoxarifado central.', 33, 1),
('Impressora 274', 'Necessita de manutenção preventiva.', 4, 'Localizado no escritório principal.', 36, 0),
('Micro-ondas 205', 'Precisa de substituição em breve.', 2, 'Emprestado temporariamente para outro setor.', 35, 0),
('Projetor 392', 'Novo, ainda em período de garantia.', 3, 'Requer atualização no sistema de controle.', 33, 0),
('Micro-ondas 690', 'Apresenta sinais de desgaste, mas funcional.', 1, 'Utilizado pela equipe administrativa.', 35, 0),
('Computador 564', 'Item essencial para o funcionamento do setor.', 13, 'Localizado no escritório principal.', 36, 0),
('Notebook 41', 'Produto antigo, porém em funcionamento.', 8, 'Disponível na sala de reuniões.', 33, 1),
('Micro-ondas 809', 'Item essencial para o funcionamento do setor.', 2, 'Pertence ao almoxarifado central.', 35, 0),
('Impressora 85', 'Apresenta sinais de desgaste, mas funcional.', 5, 'Disponível na sala de reuniões.', 36, 0),
('Cadeira 977', 'Produto antigo, porém em funcionamento.', 30, 'Disponível na sala de reuniões.', 34, 1),
('Impressora 271', 'Utilizado em treinamentos e reuniões.', 2, 'Utilizado pela equipe administrativa.', 36, 1),
('Televisão 796', 'Necessita de manutenção preventiva.', 3, 'Inventariado no último balanço patrimonial.', 33, 1),
('Ventilador 748', 'Item essencial para o funcionamento do setor.', 8, 'Alocado no setor de TI.', 35, 1),
('Computador 984', 'Em ótimo estado, pouco utilizado.', 20, 'Etiqueta de patrimônio visível.', 36, 1),
('Telefone 833', 'Produto antigo, porém em funcionamento.', 15, 'Alocado no setor de TI.', 39, 0),
('Armário 773', 'Utilizado em treinamentos e reuniões.', 6, 'Disponível na sala de reuniões.', 34, 0),
('Impressora 92', 'Em ótimo estado, pouco utilizado.', 7, 'Sob responsabilidade do setor de manutenção.', 36, 1),
('Armário 431', 'Equipamento em bom estado de conservação.', 9, 'Pertence ao almoxarifado central.', 34, 1),
('Computador 187', 'Equipamento em bom estado de conservação.', 11, 'Sob responsabilidade do setor de manutenção.', 36, 0),
('Aparelho de Som 133', 'Apresenta sinais de desgaste, mas funcional.', 3, 'Localizado no escritório principal.', 39, 1),
('Armário 854', 'Apresenta sinais de desgaste, mas funcional.', 12, 'Requer atualização no sistema de controle.', 34, 1),
('Monitor 386', 'Precisa de substituição em breve.', 22, 'Etiqueta de patrimônio visível.', 33, 1),
('Projetor 957', 'Utilizado em treinamentos e reuniões.', 4, 'Disponível na sala de reuniões.', 33, 1),
('Projetor 991', 'Utilizado em treinamentos e reuniões.', 1, 'Pertence ao almoxarifado central.', 33, 0),
('Sofá 404', 'Utilizado em treinamentos e reuniões.', 2, 'Localizado no escritório principal.', 38, 1),
('Computador 611', 'Precisa de substituição em breve.', 6, 'Sob responsabilidade do setor de manutenção.', 36, 0),
('Monitor 74', 'Produto antigo, porém em funcionamento.', 18, 'Alocado no setor de TI.', 33, 0),
('Cafeteira 620', 'Equipamento em bom estado de conservação.', 2, 'Inventariado no último balanço patrimonial.', 35, 0),
('Câmera 866', 'Precisa de substituição em breve.', 5, 'Etiqueta de patrimônio visível.', 39, 1),
('Aparelho de Som 402', 'Equipamento em bom estado de conservação.', 4, 'Disponível na sala de reuniões.', 39, 1),
('Caixa de Som 520', 'Precisa de substituição em breve.', 6, 'Inventariado no último balanço patrimonial.', 39, 1),
('Ventilador 146', 'Necessita de manutenção preventiva.', 7, 'Localizado no escritório principal.', 35, 0),
('Televisão 605', 'Equipamento em bom estado de conservação.', 5, 'Inventariado no último balanço patrimonial.', 33, 1),
('Armário 934', 'Produto antigo, porém em funcionamento.', 11, 'Etiqueta de patrimônio visível.', 34, 0),
('Impressora 835', 'Apresenta sinais de desgaste, mas funcional.', 3, 'Etiqueta de patrimônio visível.', 36, 0),
('Monitor 144', 'Precisa de substituição em breve.', 14, 'Sob responsabilidade do setor de manutenção.', 33, 1),
('Cadeira 11', 'Apresenta sinais de desgaste, mas funcional.', 40, 'Pertence ao almoxarifado central.', 34, 1),
('Aparelho de Som 18', 'Produto antigo, porém em funcionamento.', 2, 'Disponível na sala de reuniões.', 39, 0),
('Estante 693', 'Produto antigo, porém em funcionamento.', 8, 'Etiqueta de patrimônio visível.', 34, 0),
('Cadeira 284', 'Item essencial para o funcionamento do setor.', 16, 'Emprestado temporariamente para outro setor.', 34, 0),
('Cadeira 239', 'Precisa de substituição em breve.', 10, 'Etiqueta de patrimônio visível.', 34, 0),
('Aparelho de Som 101', 'Novo, ainda em período de garantia.', 5, 'Disponível na sala de reuniões.', 39, 1),
('Mesa 431', 'Produto antigo, porém em funcionamento.', 12, 'Utilizado pela equipe administrativa.', 34, 0),
('Estante 573', 'Item essencial para o funcionamento do setor.', 9, 'Pertence ao almoxarifado central.', 34, 1),
('Aparelho de Som 304', 'Necessita de manutenção preventiva.', 3, 'Requer atualização no sistema de controle.', 39, 0),
('Cadeira 430', 'Utilizado em treinamentos e reuniões.', 18, 'Etiqueta de patrimônio visível.', 34, 0),
('Estante 116', 'Apresenta sinais de desgaste, mas funcional.', 4, 'Sob responsabilidade do setor de manutenção.', 34, 1),
('Câmera 302', 'Utilizado em treinamentos e reuniões.', 6, 'Disponível na sala de reuniões.', 39, 1),
('Caixa de Som 138', 'Em ótimo estado, pouco utilizado.', 8, 'Pertence ao almoxarifado central.', 39, 1),
('Mesa 199', 'Necessita de manutenção preventiva.', 7, 'Inventariado no último balanço patrimonial.', 34, 0),
('Cadeira 553', 'Item essencial para o funcionamento do setor.', 20, 'Localizado no escritório principal.', 34, 0),
('Armário 39', 'Equipamento em bom estado de conservação.', 13, 'Disponível na sala de reuniões.', 34, 1),
('Cadeira 428', 'Produto antigo, porém em funcionamento.', 25, 'Inventariado no último balanço patrimonial.', 34, 0),
('Estante 901', 'Em ótimo estado, pouco utilizado.', 6, 'Sob responsabilidade do setor de manutenção.', 34, 0),
('Telefone 733', 'Apresenta sinais de desgaste, mas funcional.', 10, 'Pertence ao almoxarifado central.', 39, 0),
('Cadeira 613', 'Item adquirido recentemente para uso administrativo.', 28, 'Disponível na sala de reuniões.', 34, 0),
('Geladeira 711', 'Necessita de manutenção preventiva.', 2, 'Utilizado pela equipe administrativa.', 35, 1),
('Cafeteira 23', 'Precisa de substituição em breve.', 1, 'Localizado no escritório principal.', 35, 0),
('Cafeteira 784', 'Em ótimo estado, pouco utilizado.', 4, 'Utilizado pela equipe administrativa.', 35, 1),
('Mesa 446', 'Item essencial para o funcionamento do setor.', 14, 'Alocado no setor de TI.', 34, 1),
('Impressora 15', 'Item essencial para o funcionamento do setor.', 6, 'Emprestado temporariamente para outro setor.', 36, 1);

INSERT INTO pedido_consumivel (status, data_pedido, data_cancelamento, data_alteração, observacoes) VALUES
('concluido', '2025-10-01', NULL, '2025-10-02', 'Pedido urgente para o setor administrativo.'),
('pendente', '2025-10-10', NULL, NULL, 'Aguardando aprovação da gerência.'),
('pendente', '2025-10-12', NULL, NULL, 'Verificar disponibilidade de todos os itens antes de aprovar.'),
('cancelado', '2025-09-20', '2025-09-22', NULL, 'Pedido duplicado, cancelado pelo solicitante.'),
('concluido', '2025-10-05', NULL, NULL, 'Entregar diretamente ao almoxarifado.');

INSERT INTO produtos_pedido_consumivel (pedido_consumivel_id, produtos_consumivel_id, quantidade) VALUES
-- Itens para o Pedido 151 (concluido)
(151, 486, 1),   -- 1 unidade do 'Notebook 149'
(151, 490, 4),   -- 4 unidades da 'Cadeira 840'
(151, 487, 1),   -- 1 unidade da 'Impressora 518'

-- Itens para o Pedido 152 (pendente)
(152, 510, 2),  -- 2 unidades do 'Notebook 300'
(152, 531, 2),  -- 2 unidades do 'Monitor 386'

-- Itens para o Pedido 153 (pendente)
(153, 496, 1),   -- 1 unidade da 'Cafeteira 410'
(153, 519, 10),  -- 10 unidades da 'Cadeira 977'
(153, 569, 2),   -- 2 unidades da 'Mesa 446'

-- Itens para o Pedido 154 (cancelado, com registro histórico)
(154, 501, 1),   -- 1 unidade da 'Câmera 665'

-- Itens para o Pedido 155 (concluido)
(155, 566, 1),  -- 1 unidade da 'Geladeira 711'
(155, 552, 3);  -- 3 unidades da 'Mesa 431'