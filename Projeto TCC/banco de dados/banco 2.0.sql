-- =============================================================
-- SCRIPT UNIFICADO: SISTEMA DE VENDEDORES + PARTICIPAÇÃO PERCENTUAL
-- Banco: banco
-- =============================================================

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `banco` DEFAULT CHARACTER SET utf8mb4;
USE `banco`;

-- -----------------------------------------------------
-- 1) TABELA: participacao_percentual (níveis de vendedor)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS participacao_percentual (
    idparticipacao_percentual INT AUTO_INCREMENT PRIMARY KEY,
    percentual DECIMAL(5,2) NOT NULL,
    min_vendas INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- níveis padrão
INSERT INTO participacao_percentual (percentual, min_vendas)
VALUES
  (30.00, 0),
  (35.00, 10),
  (40.00, 25),
  (45.00, 50),
  (50.00, 100)
ON DUPLICATE KEY UPDATE percentual = VALUES(percentual), min_vendas = VALUES(min_vendas);

-- -----------------------------------------------------
-- 2) TABELA: vendedor
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS vendedor (
  idvendedor INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100),
  cpf CHAR(14),
  telefone CHAR(15),
  email VARCHAR(150),
  senha VARCHAR(255),
  cnpj CHAR(18),
  foto_de_perfil LONGBLOB,
  idparticipacao_percentual INT DEFAULT 1,
  CONSTRAINT fk_vendedor_participacao FOREIGN KEY (idparticipacao_percentual)
    REFERENCES participacao_percentual (idparticipacao_percentual)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 3) TABELA: produto
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS produto (
  idproduto INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100),
  preco DECIMAL(10,2),
  categoria VARCHAR(50),
  quantidade_estoque INT DEFAULT 0,
  data_pub DATE,
  descricao TEXT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  idvendedor INT NOT NULL,
  CONSTRAINT fk_produto_vendedor FOREIGN KEY (idvendedor)
    REFERENCES vendedor (idvendedor)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 4) TABELA: usuario
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS usuario (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    cpf CHAR(14),
    cep CHAR(9),
    endereco VARCHAR(50),
    cidade VARCHAR(50),
    estado VARCHAR(50),
    bairro VARCHAR(50),
    telefone CHAR(14),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255),
    foto_de_perfil LONGBLOB
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- -----------------------------------------------------
-- 5) TABELA: pedido
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS pedido (
  idpedido INT AUTO_INCREMENT PRIMARY KEY,
  valor_total DECIMAL(10,2),
  data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
  idusuario INT NOT NULL,
  CONSTRAINT fk_pedido_usuario FOREIGN KEY (idusuario)
    REFERENCES usuario (idusuario)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 6) TABELA: item_pedido
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS item_pedido (
  iditem_pedido INT AUTO_INCREMENT PRIMARY KEY,
  quantidade INT DEFAULT 1,
  idpedido INT NOT NULL,
  idproduto INT NOT NULL,
  CONSTRAINT fk_item_pedido_pedido FOREIGN KEY (idpedido)
    REFERENCES pedido (idpedido)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_item_pedido_produto FOREIGN KEY (idproduto)
    REFERENCES produto (idproduto)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- -----------------------------------------------------
-- 7) TABELA: imagens (produto)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS imagens (
  idimagens INT AUTO_INCREMENT PRIMARY KEY,
  imagem LONGBLOB,
  idproduto INT NOT NULL,
  CONSTRAINT fk_imagens_produto FOREIGN KEY (idproduto)
    REFERENCES produto (idproduto)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 8) TABELA: vendas
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS vendas (
  idvenda INT AUTO_INCREMENT PRIMARY KEY,
  idvendedor INT NOT NULL,
  idproduto INT NULL,
  quantidade INT DEFAULT 1,
  valor DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_venda_vendedor FOREIGN KEY (idvendedor)
    REFERENCES vendedor (idvendedor)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_venda_produto FOREIGN KEY (idproduto)
    REFERENCES produto (idproduto)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 9) INSERÇÃO DE NÍVEIS DE PARTICIPAÇÃO PADRÃO
-- -----------------------------------------------------
INSERT INTO participacao_percentual (percentual, min_vendas)
VALUES
  (30.00, 0),
  (35.00, 10),
  (40.00, 25),
  (45.00, 50),
  (50.00, 100)
ON DUPLICATE KEY UPDATE percentual = VALUES(percentual), min_vendas = VALUES(min_vendas);

ALTER TABLE usuario ADD COLUMN status_conta ENUM('ativo', 'desativado') DEFAULT 'ativo';
ALTER TABLE usuario ADD COLUMN ativo TINYINT(1) DEFAULT 1;
ALTER TABLE vendedor ADD COLUMN status_conta ENUM('ativo', 'desativado') DEFAULT 'ativo';
ALTER TABLE vendedor ADD COLUMN ativo TINYINT(1) DEFAULT 1;

ALTER TABLE item_pedido
ADD COLUMN idvendedor INT NOT NULL,
ADD CONSTRAINT fk_item_pedido_vendedor FOREIGN KEY (idvendedor)
REFERENCES vendedor(idvendedor)
ON DELETE CASCADE ON UPDATE CASCADE;

-- =============================================================
-- FIM
-- Estrutura compatível com PHP para atualização dinâmica do nível:
-- O cálculo do percentual e atualização do vendedor é feito via PHP.
-- =============================================================

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;