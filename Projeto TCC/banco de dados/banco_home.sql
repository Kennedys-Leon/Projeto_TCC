-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema banco
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema banco
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `banco` DEFAULT CHARACTER SET utf8 ;
USE `banco` ;

-- -----------------------------------------------------
-- Table `banco`.`vendedor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco`.`vendedor` (
  `idvendedor` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NULL,
  `cpf` CHAR(11) NULL,
  `telefone` CHAR(11) NULL,
  `email` VARCHAR(50) NULL,
  `senha` VARCHAR(50) NULL,
  `cnpj` CHAR(14) NULL,
  `foto` VARCHAR(255) NULL,
  PRIMARY KEY (`idvendedor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco`.`produto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco`.`produto` (
  `idproduto` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NULL,
  `preco` FLOAT NULL,
  `categoria` VARCHAR(50) NULL,
  `quantidade_estoque` INT NULL,
  `data_pub` DATE NULL,
  `descricao` VARCHAR(255) NULL,
  `criado_em` TIMESTAMP NULL,
  `idvendedor` INT NOT NULL,
  PRIMARY KEY (`idproduto`, `idvendedor`),
  INDEX `fk_produto_vendedor1_idx` (`idvendedor` ASC),
  CONSTRAINT `fk_produto_vendedor1`
    FOREIGN KEY (`idvendedor`)
    REFERENCES `banco`.`vendedor` (`idvendedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco`.`usuario` (
  `idusuario` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NULL,
  `cpf` VARCHAR(11) NULL,
  `cep` CHAR(8) NULL,
  `telefone` CHAR(14) NULL,
  `email` VARCHAR(50) NULL,
  `senha` VARCHAR(50) NULL,
  `foto` VARCHAR(255) NULL,
  PRIMARY KEY (`idusuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco`.`pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco`.`pedido` (
  `idpedido` INT NOT NULL AUTO_INCREMENT,
  `valor_total` INT NULL,
  `data_pedido` DATE NULL,
  `idusuario` INT NOT NULL,
  PRIMARY KEY (`idpedido`, `idusuario`),
  INDEX `fk_pedido_usuario1_idx` (`idusuario` ASC),
  CONSTRAINT `fk_pedido_usuario1`
    FOREIGN KEY (`idusuario`)
    REFERENCES `banco`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco`.`item_pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco`.`item_pedido` (
  `iditem_pedido` INT NOT NULL AUTO_INCREMENT,
  `quantidade` INT NULL,
  `idpedido` INT NOT NULL,
  `idproduto` INT NOT NULL,
  PRIMARY KEY (`iditem_pedido`, `idpedido`),
  INDEX `fk_item_pedido_pedido1_idx` (`idpedido` ASC),
  INDEX `fk_item_pedido_produto1_idx` (`idproduto` ASC),
  CONSTRAINT `fk_item_pedido_pedido1`
    FOREIGN KEY (`idpedido`)
    REFERENCES `banco`.`pedido` (`idpedido`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_pedido_produto1`
    FOREIGN KEY (`idproduto`)
    REFERENCES `banco`.`produto` (`idproduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco`.`loginusuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco`.`loginusuario` (
  `idlogin` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(50) NULL,
  `senha` VARCHAR(255) NULL,
  `idusuario` INT NOT NULL,
  PRIMARY KEY (`idlogin`, `idusuario`),
  INDEX `fk_login_usuario1_idx` (`idusuario` ASC),
  CONSTRAINT `fk_login_usuario1`
    FOREIGN KEY (`idusuario`)
    REFERENCES `banco`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco`.`imagens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco`.`imagens` (
  `idimagens` INT NOT NULL AUTO_INCREMENT,
  `imagem` LONGBLOB NULL,
  `idproduto` INT NOT NULL,
  PRIMARY KEY (`idimagens`, `idproduto`),
  INDEX `fk_imagens_produto1_idx` (`idproduto` ASC),
  CONSTRAINT `fk_imagens_produto1`
    FOREIGN KEY (`idproduto`)
    REFERENCES `banco`.`produto` (`idproduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco`.`loginvendedor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco`.`loginvendedor` (
  `idloginvendedor` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(50) NULL,
  `senha` VARCHAR(255) NULL,
  `nome` VARCHAR(255) NULL,
  `idvendedor` INT NOT NULL,
  PRIMARY KEY (`idloginvendedor`, `idvendedor`),
  INDEX `fk_loginvendedor_vendedor1_idx` (`idvendedor` ASC),
  CONSTRAINT `fk_loginvendedor_vendedor1`
    FOREIGN KEY (`idvendedor`)
    REFERENCES `banco`.`vendedor` (`idvendedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco`.`vendas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco`.`vendas` (
  `idvendas` INT NOT NULL AUTO_INCREMENT,
  `quantidade` INT NULL,
  `data_venda` DATETIME NULL,
  `idvendedor` INT NOT NULL,
  `idproduto` INT NOT NULL,
  PRIMARY KEY (`idvendas`, `idvendedor`, `idproduto`),
  INDEX `fk_vendas_vendedor1_idx` (`idvendedor` ASC),
  INDEX `fk_vendas_produto1_idx` (`idproduto` ASC),
  CONSTRAINT `fk_vendas_vendedor1`
    FOREIGN KEY (`idvendedor`)
    REFERENCES `banco`.`vendedor` (`idvendedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vendas_produto1`
    FOREIGN KEY (`idproduto`)
    REFERENCES `banco`.`produto` (`idproduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
