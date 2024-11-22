DROP DATABASE IF EXISTS gsb1;
CREATE DATABASE gsb1;
USE gsb1;

DROP TABLE IF EXISTS utilisateur;
CREATE TABLE utilisateur (
id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(50),
        prenom VARCHAR(50),
        email VARCHAR(100),
        pass VARCHAR(255),
        ville VARCHAR(100),
        role_compte VARCHAR(100),
        age INT(11)
    );
INSERT INTO utilisateur (`nom`, `prenom`, `email`, `pass`, `ville`, `role_compte`, `age`) 
VALUES ('Jean', 'Dupont', 'jean.dupont@gsb.com', 'password123', 'Lyon', 'administrateur', '38'),
('Pierre', 'Richard', 'pierre.richard@gsb.com', 'password456', 'Villeurbanne', 'visiteur médical', '26'),
('Marie', 'Dupont', 'marie.dupont@gsb.com', 'password789', 'Lyon', 'comptable', '32');

SELECT * FROM utilisateur;

UPDATE utilisateur SET statut = 'actif' WHERE role_compte = 'administrateur';
UPDATE utilisateur SET statut = 'actif' WHERE role_compte = 'visiteur médical';
UPDATE utilisateur SET statut = 'inactif' WHERE role_compte = 'comptable';