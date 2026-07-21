-- ============================================
--  Base de données : gestion de dettes
--  SGBD : PostgreSQL
-- ============================================

-- 1. Les types ENUM (correspondent aux «enumeration» du diagramme)
CREATE TYPE role_utilisateur AS ENUM ('admin', 'client');
CREATE TYPE etat_client      AS ENUM ('non solvable', 'solvable', 'nouveau');
CREATE TYPE etat_dette       AS ENUM ('non soldee', 'soldee');

-- 2. Table utilisateur (le côté "1")
CREATE TABLE utilisateur (
    id           SERIAL PRIMARY KEY,
    nom          VARCHAR(50)  NOT NULL,
    prenom       VARCHAR(50)  NOT NULL,
    email        VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone    VARCHAR(20),
    role         role_utilisateur NOT NULL DEFAULT 'client',
    etat_client  etat_client      NOT NULL DEFAULT 'nouveau'
);

-- 3. Table dette (le côté "0..*", elle porte la clé étrangère)
CREATE TABLE dette (
    id             SERIAL PRIMARY KEY,
    numero         VARCHAR(20)   NOT NULL UNIQUE,
    montant        NUMERIC(10,2) NOT NULL,
    date           DATE          NOT NULL DEFAULT CURRENT_DATE,
    etat_dette     etat_dette    NOT NULL DEFAULT 'non soldee',
    id_utilisateur INTEGER       NOT NULL,
    CONSTRAINT fk_dette_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur(id)
        ON DELETE CASCADE
);

-- 4. Quelques données de test (facultatif mais pratique pour la démo)
INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, telephone, role, etat_client) VALUES
('Thiam',  'Ben',   'ben.thiam@mail.sn',     '$2y$10$exempleHashAremplacer', '770000001', 'client', 'non solvable'),
('Diagne', 'Penda', 'penda.diagne@mail.sn',  '$2y$10$exempleHashAremplacer', '770000002', 'client', 'solvable'),
('Sow',    'Awa',   'awa.sow@mail.sn',       '$2y$10$exempleHashAremplacer', '770000003', 'admin',  'nouveau');

INSERT INTO dette (numero, montant, date, etat_dette, id_utilisateur) VALUES
('D001', 55200.00, '2025-07-15', 'soldee',     1),
('D002', 23450.00, '2025-07-12', 'non soldee', 1),
('D003', 12000.00, '2025-07-18', 'non soldee', 2);