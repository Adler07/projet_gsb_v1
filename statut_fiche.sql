CREATE TABLE statut_fiche (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fiche_id INT,
    statut VARCHAR(50),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fiche_id) REFERENCES fiche_frais(`n°fiche_frais`) ON DELETE CASCADE
);