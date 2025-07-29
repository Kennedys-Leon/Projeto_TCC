-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema banco_usuario
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema banco_usuario
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `banco_usuario` DEFAULT CHARACTER SET utf8 ;
USE `banco_usuario` ;

-- -----------------------------------------------------
-- Table `banco_usuario`.`cadastro`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco_usuario`.`cadastro` (
  `idcadastro` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NULL,
  `cpf` CHAR(11) NULL,
  `endereco` VARCHAR(100) NULL,
  `telefone` CHAR(11) NULL,
  `email` VARCHAR(50) NULL,
  `senha` VARCHAR(50) NULL,
  `foto` VARCHAR(255) NULL,
  PRIMARY KEY (`idcadastro`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `banco_usuario`.`login`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco_usuario`.`login` (
  `idlogin` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(50) NULL,
  `senha` VARCHAR(50) NULL,
  `idcadastro` INT NOT NULL,
  PRIMARY KEY (`idlogin`),
  INDEX `fk_login_cadastro_idx` (`idcadastro` ASC) VISIBLE,
  CONSTRAINT `fk_login_cadastro`
    FOREIGN KEY (`idcadastro`)
    REFERENCES `banco_usuario`.`cadastro` (`idcadastro`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
