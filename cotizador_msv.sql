CREATE DATABASE IF NOT EXISTS cotizador_msv
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE cotizador_msv;

CREATE TABLE IF NOT EXISTS clientes (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  compania        VARCHAR(150)   NOT NULL,
  telefono        VARCHAR(25)    NOT NULL,
  contacto        VARCHAR(120)   NOT NULL,
  email           VARCHAR(191)   NOT NULL,
  rfc             VARCHAR(20)    NOT NULL,
  calle           VARCHAR(120)   NOT NULL,
  numero          VARCHAR(20)    NOT NULL,
  colonia         VARCHAR(120)   NOT NULL,
  estado          VARCHAR(120)   NOT NULL,
  pais            VARCHAR(120)   NOT NULL,
  cp              VARCHAR(10)    NOT NULL,
  creado_en       TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uk_clientes_rfc (rfc),
  KEY idx_clientes_email (email)
);

CREATE TABLE IF NOT EXISTS instrumentos (
  id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre              VARCHAR(255) NOT NULL,
  marca               VARCHAR(100) NOT NULL,
  modelo              VARCHAR(100) NOT NULL,
  alcance             VARCHAR(50)  NOT NULL,
  unidades           VARCHAR(50)  NOT NULL,
  puntos_minimos     INT          NOT NULL,
  magnitud           ENUM('temperatura', 'densidad', 'volumen', 'otro') NOT NULL,
  
  -- Información adicional para temperatura
  temp_rango_min     FLOAT NULL,      -- Rango mínimo de temperatura (solo si magnitud es 'temperatura')
  temp_rango_max     FLOAT NULL,      -- Rango máximo de temperatura (solo si magnitud es 'temperatura')
  temp_calibracion   VARCHAR(255) NULL, -- Método de calibración (solo si magnitud es 'temperatura')

  -- Información adicional para densidad-volumen
  densidad_min       FLOAT NULL,      -- Densidad mínima (solo si magnitud es 'densidad')
  densidad_max       FLOAT NULL,      -- Densidad máxima (solo si magnitud es 'densidad')
  volumen_min        FLOAT NULL,      -- Volumen mínimo (solo si magnitud es 'volumen')
  volumen_max        FLOAT NULL,      -- Volumen máximo (solo si magnitud es 'volumen')

  creado_en          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
