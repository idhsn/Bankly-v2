CREATE DATABASE IF NOT EXISTS bankly_v2;
USE bankly_v2;

CREATE TABLE Utilisateur (
    id_utilisateur INT PRIMARY KEY AUTO_INCREMENT,
    nom_utilisateur VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Client (
    id_client INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    cin VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Compte (
    id_compte INT PRIMARY KEY AUTO_INCREMENT,
    numero_compte VARCHAR(20) NOT NULL UNIQUE,
    id_client INT NOT NULL,
    type_compte ENUM('courant', 'epargne') NOT NULL,
    solde DECIMAL(15,2) DEFAULT 0.00,
    statut ENUM('actif', 'suspendu', 'ferme') DEFAULT 'actif',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES Client(id_client) ON DELETE CASCADE
);

CREATE TABLE Transaction (
    id_transaction INT PRIMARY KEY AUTO_INCREMENT,
    id_compte INT NOT NULL,
    id_utilisateur INT NOT NULL,
    type_transaction ENUM('depot', 'retrait') NOT NULL,
    montant DECIMAL(15,2) NOT NULL,
    solde_avant DECIMAL(15,2) NOT NULL,
    solde_apres DECIMAL(15,2) NOT NULL,
    date_transaction TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_compte) REFERENCES Compte(id_compte) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);
