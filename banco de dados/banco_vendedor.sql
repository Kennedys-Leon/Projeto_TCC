-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema banco_vendedor
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema banco_vendedor
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `banco_vendedor` DEFAULT CHARACTER SET utf8 ;
USE `banco_vendedor` ;

-- -----------------------------------------------------
-- Table `banco_vendedor`.`vendedor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco_vendedor`.`vendedor` (
  `idvendedor` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NULL,
  `cpf` CHAR(11) NULL,
  `telefone` CHAR(11) NULL,
  `email` VARCHAR(50) NULL,
  `senha` VARCHAR(50) NULL,
  `cnpj` CHAR(14) NULL,
  PRIMARY KEY (`idvendedor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco_vendedor`.`loginvend`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco_vendedor`.`loginvend` (
  `idloginvend` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NULL,
  `email` VARCHAR(50) NULL,
  `senha` VARCHAR(50) NULL,
  `idvendedor` INT NOT NULL,
  PRIMARY KEY (`idloginvend`),
  INDEX `fk_loginvend_vendedor_idx` (`idvendedor` ASC) VISIBLE,
  CONSTRAINT `fk_loginvend_vendedor`
    FOREIGN KEY (`idvendedor`)
    REFERENCES `banco_vendedor`.`vendedor` (`idvendedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco_vendedor`.`produto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco_vendedor`.`produto` (
  `idproduto` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NULL,
  `preco` FLOAT NULL,
  `categoria` VARCHAR(50) NULL,
  `quantidade_estoque` INT NULL,
  `idvendedor` INT NOT NULL,
  PRIMARY KEY (`idproduto`),
  INDEX `fk_produto_vendedor1_idx` (`idvendedor` ASC) VISIBLE,
  CONSTRAINT `fk_produto_vendedor1`
    FOREIGN KEY (`idvendedor`)
    REFERENCES `banco_vendedor`.`vendedor` (`idvendedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco_vendedor`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco_vendedor`.`usuario` (
  `idusuario` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idusuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco_vendedor`.`pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco_vendedor`.`pedido` (
  `idpedido` INT NOT NULL AUTO_INCREMENT,
  `valor_total` INT NULL,
  `data_pedido` DATE NULL,
  `idusuario` INT NOT NULL,
  PRIMARY KEY (`idpedido`),
  INDEX `fk_pedido_usuario1_idx` (`idusuario` ASC) VISIBLE,
  CONSTRAINT `fk_pedido_usuario1`
    FOREIGN KEY (`idusuario`)
    REFERENCES `banco_vendedor`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco_vendedor`.`item_pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco_vendedor`.`item_pedido` (
  `iditem_pedido` INT NOT NULL AUTO_INCREMENT,
  `quantidade` INT NULL,
  `idproduto` INT NOT NULL,
  `idpedido` INT NOT NULL,
  PRIMARY KEY (`iditem_pedido`),
  INDEX `fk_item_pedido_produto1_idx` (`idproduto` ASC) VISIBLE,
  INDEX `fk_item_pedido_pedido1_idx` (`idpedido` ASC) VISIBLE,
  CONSTRAINT `fk_item_pedido_produto1`
    FOREIGN KEY (`idproduto`)
    REFERENCES `banco_vendedor`.`produto` (`idproduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_pedido_pedido1`
    FOREIGN KEY (`idpedido`)
    REFERENCES `banco_vendedor`.`pedido` (`idpedido`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
