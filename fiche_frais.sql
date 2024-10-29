CREATE TABLE fiche_frais (
    n°fiche_frais INT AUTO_INCREMENT PRIMARY KEY,
    montant_repas DECIMAL(10, 2) NULL,
    montant_hebergement DECIMAL(10, 2) NULL,
    montant_deplacement DECIMAL(10, 2) NULL,
    total DECIMAL(10, 2) NOT NULL,
    date_soumission DATE NOT NULL
);