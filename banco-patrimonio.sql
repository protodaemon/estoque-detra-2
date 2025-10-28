CREATE TABLE
  `categoria_patrimonio` (
    `categoria_patrimonio_id` int unsigned NOT NULL AUTO_INCREMENT,
    `nome` varchar(90) NOT NULL,
    `descricao` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`categoria_patrimonio_id`),
    UNIQUE KEY `nome_unico_decoracao` (`nome`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci

  CREATE TABLE
  `localizacoes` (
    `localizacao_id` int unsigned NOT NULL AUTO_INCREMENT,
    `nome` varchar(100) NOT NULL,
    PRIMARY KEY (`localizacao_id`),
    UNIQUE KEY `nome` (`nome`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci

CREATE TABLE
  `produtos_patrimonio` (
    `produtos_patrimonio_id` int unsigned NOT NULL AUTO_INCREMENT,
    `nome` varchar(90) DEFAULT NULL,
    `descricao` varchar(500) DEFAULT NULL,
    `numero_identificacao` char(5) NOT NULL,
    `observacoes` varchar(500) DEFAULT NULL,
    `foto` varchar(255) DEFAULT NULL,
    `categoria_patrimonio` int unsigned DEFAULT NULL,
    `ativo` tinyint(1) NOT NULL DEFAULT '1',
    `localizacao_id` int unsigned DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`produtos_patrimonio_id`),
    UNIQUE KEY `numero_identificacao_unico` (`numero_identificacao`),
    KEY `fk_categoria_patrimonio1` (`categoria_patrimonio`),
    KEY `fk_localizacao` (`localizacao_id`),
    CONSTRAINT `fk_categoria_patrimonio1` FOREIGN KEY (`categoria_patrimonio`) REFERENCES `categoria_patrimonio` (`categoria_patrimonio_id`) ON DELETE
    SET
      NULL ON UPDATE CASCADE,
      CONSTRAINT `fk_localizacao` FOREIGN KEY (`localizacao_id`) REFERENCES `localizacoes` (`localizacao_id`) ON DELETE
    SET
      NULL ON UPDATE CASCADE
  ) ENGINE = InnoDB AUTO_INCREMENT = 154 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci

CREATE TABLE
  `entrada_patrimonio` (
    `entrada_patrimonio_id` int unsigned NOT NULL AUTO_INCREMENT,
    `produto_patrimonio_id` int unsigned NOT NULL,
    `quantidade` int NOT NULL,
    `data_entrada` date DEFAULT NULL,
    `observacao` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`entrada_patrimonio_id`),
    KEY `fk_produto_patrimonio` (`produto_patrimonio_id`),
    CONSTRAINT `fk_produto_patrimonio` FOREIGN KEY (`produto_patrimonio_id`) REFERENCES `produtos_patrimonio` (`produtos_patrimonio_id`) ON DELETE CASCADE ON UPDATE CASCADE
  ) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci

  CREATE TABLE
  `movimentacoes_patrimonio` (
    `movimentacao_id` int unsigned NOT NULL AUTO_INCREMENT,
    `produto_patrimonio_id` int unsigned NOT NULL,
    `tipo_movimentacao` enum('criacao', 'exclusao', 'mudanca_localizacao') NOT NULL,
    `localizacao_anterior_id` int unsigned DEFAULT NULL,
    `localizacao_nova_id` int unsigned DEFAULT NULL,
    `usuario_id` int unsigned DEFAULT NULL,
    `observacao` varchar(500) DEFAULT NULL,
    `data_movimentacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`movimentacao_id`),
    KEY `fk_produto_movimentacao` (`produto_patrimonio_id`),
    KEY `fk_localizacao_anterior` (`localizacao_anterior_id`),
    KEY `fk_localizacao_nova` (`localizacao_nova_id`),
    KEY `idx_data_movimentacao` (`data_movimentacao`),
    CONSTRAINT `fk_localizacao_anterior_mov` FOREIGN KEY (`localizacao_anterior_id`) REFERENCES `localizacoes` (`localizacao_id`) ON DELETE
    SET
      NULL ON UPDATE CASCADE,
      CONSTRAINT `fk_localizacao_nova_mov` FOREIGN KEY (`localizacao_nova_id`) REFERENCES `localizacoes` (`localizacao_id`) ON DELETE
    SET
      NULL ON UPDATE CASCADE,
      CONSTRAINT `fk_produto_movimentacao` FOREIGN KEY (`produto_patrimonio_id`) REFERENCES `produtos_patrimonio` (`produtos_patrimonio_id`) ON DELETE CASCADE ON UPDATE CASCADE
  ) ENGINE = InnoDB AUTO_INCREMENT = 19 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci