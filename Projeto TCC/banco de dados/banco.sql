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
  `vendedor_idvendedor` INT NOT NULL,
  PRIMARY KEY (`idproduto`, `vendedor_idvendedor`),
  INDEX `fk_produto_vendedor1_idx` (`vendedor_idvendedor` ASC) VISIBLE,
  CONSTRAINT `fk_produto_vendedor1`
    FOREIGN KEY (`vendedor_idvendedor`)
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
  `usuario_idusuario` INT NOT NULL,
  PRIMARY KEY (`idpedido`, `usuario_idusuario`),
  INDEX `fk_pedido_usuario1_idx` (`usuario_idusuario` ASC) VISIBLE,
  CONSTRAINT `fk_pedido_usuario1`
    FOREIGN KEY (`usuario_idusuario`)
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
  `pedido_idpedido` INT NOT NULL,
  `idproduto` INT NOT NULL,
  PRIMARY KEY (`iditem_pedido`, `pedido_idpedido`),
  INDEX `fk_item_pedido_pedido1_idx` (`pedido_idpedido` ASC) VISIBLE,
  INDEX `fk_item_pedido_produto1_idx` (`idproduto` ASC) VISIBLE,
  CONSTRAINT `fk_item_pedido_pedido1`
    FOREIGN KEY (`pedido_idpedido`)
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
  `usuario_idusuario` INT NOT NULL,
  PRIMARY KEY (`idlogin`, `usuario_idusuario`),
  INDEX `fk_login_usuario1_idx` (`usuario_idusuario` ASC) VISIBLE,
  CONSTRAINT `fk_login_usuario1`
    FOREIGN KEY (`usuario_idusuario`)
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
  `produto_idproduto` INT NOT NULL,
  PRIMARY KEY (`idimagens`, `produto_idproduto`),
  INDEX `fk_imagens_produto1_idx` (`produto_idproduto` ASC) VISIBLE,
  CONSTRAINT `fk_imagens_produto1`
    FOREIGN KEY (`produto_idproduto`)
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
  `vendedor_idvendedor` INT NOT NULL,
  PRIMARY KEY (`idloginvendedor`, `vendedor_idvendedor`),
  INDEX `fk_loginvendedor_vendedor1_idx` (`vendedor_idvendedor` ASC) VISIBLE,
  CONSTRAINT `fk_loginvendedor_vendedor1`
    FOREIGN KEY (`vendedor_idvendedor`)
    REFERENCES `banco`.`vendedor` (`idvendedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
