-- moviemntações: usuario -> usuario id

ALTER TABLE movimentacoes_patrimonio
    -- 1. Remover a coluna antiga
    DROP COLUMN `usuario`,

    -- 2. Adicionar a nova coluna usuario_id
    ADD COLUMN `usuario_id` INT UNSIGNED NULL AFTER `localizacao_nova_id`,

    -- 3. Criar índice para FK (opcional, mas recomendado)
    ADD INDEX `fk_usuario_mov_idx` (`usuario_id`),

    -- 4. Criar chave estrangeira
    ADD CONSTRAINT `fk_usuario_mov`
        FOREIGN KEY (`usuario_id`)
        REFERENCES `usuario` (`usuario_id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE;

-----------------

-- 2) pedido: add usuario_id

ALTER TABLE pedido_consumivel
ADD COLUMN usuario_id INT UNSIGNED NULL AFTER observacoes;

ALTER TABLE pedido_consumivel
ADD CONSTRAINT fk_pedido_consumivel_usuario
    FOREIGN KEY (usuario_id)
    REFERENCES usuario(usuario_id)
    ON DELETE SET NULL
    ON UPDATE CASCADE;







