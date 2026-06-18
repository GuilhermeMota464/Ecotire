-- Adiciona campo gênero na tabela usuario
-- (coloque no phpMyAdmin/CLI para aplicar)

ALTER TABLE usuario
  ADD COLUMN genero ENUM('masculino','feminino','outros','prefiro_nao_dizer') NOT NULL DEFAULT 'prefiro_nao_dizer';


