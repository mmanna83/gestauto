-- Creazione del database
CREATE DATABASE IF NOT EXISTS GestionaleAuto;

-- Utilizzo del database
USE GestionaleAuto;

-- Creazione della tabella Marca
CREATE TABLE IF NOT EXISTS Marca (
    IdMarca INT PRIMARY KEY,
    NomeMarca VARCHAR(255)
);

-- Creazione della tabella Modello
CREATE TABLE IF NOT EXISTS Modello (
    IdModello INT PRIMARY KEY,
    NomeModello VARCHAR(255)
);

-- Creazione della tabella Veicolo
CREATE TABLE IF NOT EXISTS Veicolo (
    IdVeicolo INT PRIMARY KEY,
    IdMarca INT,
    IdModello INT,
    IdConcessionario INT,
    Targa VARCHAR(255),
    Colore VARCHAR(255),
    Motore VARCHAR(255),
    CV INT,
    Posti INT,
    Cilindrata INT,
    Potenza INT,
    INDEX fk_IdMarca (IdMarca),
    INDEX fk_IdModello (IdModello),
    INDEX fk_IdConcessionario (IdConcessionario),
    FOREIGN KEY (IdMarca) REFERENCES Marca(IdMarca),
    FOREIGN KEY (IdModello) REFERENCES Modello(IdModello)
);

-- Creazione della tabella Foto
CREATE TABLE IF NOT EXISTS Foto (
    IdFoto INT PRIMARY KEY,
    IdVeicolo INT,
    Percorso VARCHAR (255)
    FOREIGN KEY (IdVeicolo) REFERENCES Veicolo(IdVeicolo)
);

-- Creazione della tabella Annuncio
CREATE TABLE IF NOT EXISTS Annuncio (
    IdAnnuncio INT PRIMARY KEY,
    IdVeicolo INT,
    IdCanale INT,
    Descrizione TEXT,
    Prezzo DECIMAL(10, 2),
    INDEX fk_IdVeicolo (IdVeicolo),
    INDEX fk_IdCanale (IdCanale),
    FOREIGN KEY (IdVeicolo) REFERENCES Veicolo(IdVeicolo)
    FOREIGN KEY (IdCanale) REFERENCES Canale(IdCanale)
);

-- Creazione della tabella Concessionario
CREATE TABLE IF NOT EXISTS Concessionario (
    IdConcessionario INT PRIMARY KEY,
    RagioneSociale VARCHAR(255),
    Indirizzo VARCHAR(255),
    IdComune INT,
    Mail VARCHAR(255),
    Telefono VARCHAR(20),
    Password VARCHAR(255),
    INDEX fk_IdComune (IdComune),
    FOREIGN KEY (IdComune) REFERENCES Comune(IdComune)
);

-- Creazione della tabella Canale
CREATE TABLE IF NOT EXISTS Canale (
    IdCanale INT PRIMARY KEY,
    Sito VARCHAR(255)
);

-- Creazione della tabella Lead
CREATE TABLE IF NOT EXISTS Lead (
    IdLead INT PRIMARY KEY,
    Nome VARCHAR(255),
    Cognome VARCHAR(255),
    Mail VARCHAR(255),
    Cap VARCHAR(10),
    Telefono VARCHAR(20),
    IdAnnuncio INT,
    INDEX fk_IdAnnuncio (IdAnnuncio),
    FOREIGN KEY (IdAnnuncio) REFERENCES Annuncio(IdAnnuncio)
);

-- Creazione della tabella Cliente
CREATE TABLE IF NOT EXISTS Cliente (
    IdCliente INT PRIMARY KEY,
    IdLead INT,
    Indirizzo VARCHAR(255),
    INDEX fk_IdLead (IdLead),
    FOREIGN KEY (IdLead) REFERENCES Lead(IdLead)
);

-- Creazione della tabella Preventivo
CREATE TABLE IF NOT EXISTS Preventivo (
    IdPreventivo INT PRIMARY KEY,
    PrezzoOfferta DECIMAL(10, 2),
    IdConcessionario INT,
    Data DATE,
    IdCliente INT,
    IdLead INT,
    INDEX fk_IdConcessionario (IdConcessionario),
    INDEX fk_IdCliente (IdCliente),
    INDEX fk_IdLead (IdLead),
    FOREIGN KEY (IdConcessionario) REFERENCES Concessionario(IdConcessionario),
    FOREIGN KEY (IdCliente) REFERENCES Cliente(IdCliente),
    FOREIGN KEY (IdLead) REFERENCES Lead(IdLead)
);

-- Creazione della tabella Provincia
CREATE TABLE IF NOT EXISTS Provincia (
    IdProvincia INT PRIMARY KEY,
    IdRegione INT,
    NomeProvincia VARCHAR(255),
    FOREIGN KEY (IdRegione) REFERENCES Regione(IdRegione)
);

-- Creazione della tabella Regione
CREATE TABLE IF NOT EXISTS Regione (
    IdRegione INT PRIMARY KEY,
    NomeRegione VARCHAR(255)
);

-- Creazione della tabella Comune
CREATE TABLE IF NOT EXISTS Comune (
    IdComune INT PRIMARY KEY,
    NomeComune VARCHAR(255),
    IdProvincia INT,
    INDEX fk_IdProvincia (IdProvincia),
    FOREIGN KEY (IdProvincia) REFERENCES Provincia(IdProvincia)
);

-- Creazione della tabella Cap
CREATE TABLE IF NOT EXISTS Cap (
    IdCap INT PRIMARY KEY,
    IdComune INT,
    CodicePostale VARCHAR(10),
    FOREIGN KEY (IdComune) REFERENCES Comune(IdComune)
);

-- Inserimento dati nella tabella Marca
INSERT INTO Marca (IdMarca, NomeMarca) VALUES
(1, 'Fiat'),
(2, 'Toyota'),
(3, 'Ford');

-- Inserimento dati nella tabella Modello
INSERT INTO Modello (IdModello, NomeModello) VALUES
(1, 'Punto'),
(2, 'Yaris'),
(3, 'Fiesta');

-- Inserimento dati nella tabella Veicolo
INSERT INTO Veicolo (IdVeicolo, IdMarca, IdModello, IdConcessionario, Targa, Colore, Motore, CV, Posti, Cilindrata, Potenza) VALUES
(1, 1, 1, 1, 'AB123CD', 'Blu', 'Benzina', 75, 5, 1200, 100),
(2, 2, 2, 2, 'EF456GH', 'Rosso', 'Benzina', 90, 5, 1300, 110),
(3, 3, 3, 3, 'IJ789KL', 'Nero', 'Diesel', 110, 5, 1500, 120);

-- Inserimento dati nella tabella Foto
INSERT INTO Foto (IdFoto, IdVeicolo, Percorso) VALUES
(1, 1, ‘Foto1.jpg’),
(2, 2, ‘Foto2.jpg’),
(3, 3, ‘Foto3.jpg’);

-- Inserimento dati nella tabella Canale
INSERT INTO Canale (IdCanale, Sito) VALUES
(1, 'autoscout24'),
(2, 'subito.it');

-- Inserimento dati nella tabella Concessionario
INSERT INTO Concessionario (IdConcessionario, RagioneSociale, Indirizzo, IdComune, Mail, Telefono, Password) VALUES
(1, 'Concessionaria Fiat', 'Via Roma 1', 1, 'info@fiat.com', '0123456789', 'password1'),
(2, 'Concessionaria Toyota', 'Via Milano 2', 2, 'info@toyota.com', '9876543210', 'password2'),
(3, 'Concessionaria Ford', 'Via Napoli 3', 3, 'info@ford.com', '1234567890', 'password3');

-- Inserimento dati nella tabella Lead
INSERT INTO Lead (IdLead, Nome, Cognome, Mail, Cap, Telefono, IdAnnuncio) VALUES
(1, 'Mario', 'Rossi', 'mario@email.com', '12345', '0123456789', 1),
(2, 'Luca', 'Verdi', 'luca@email.com', '54321', '9876543210', 2),
(3, 'Paolo', 'Bianchi', 'paolo@email.com', '67890', '1234567890', 3);

-- Inserimento dati nella tabella Cliente
INSERT INTO Cliente (IdCliente, IdLead, Indirizzo) VALUES
(1, 1, 'Via Dante 1'),
(2, 2, 'Via Garibaldi 2'),
(3, 3, 'Via Vittorio Emanuele 3');

-- Inserimento dati nella tabella Preventivo
INSERT INTO Preventivo (IdPreventivo, PrezzoOfferta, IdConcessionario, Data, IdCliente, IdLead) VALUES
(1, 15000.00, 1, '2023-12-01', 1, 1),
(2, 18000.00, 2, '2023-12-02', 2, 2),
(3, 20000.00, 3, '2023-12-03', 3, 3);

-- Inserimento dati nella tabella Provincia
INSERT INTO Provincia (IdProvincia, IdRegione, NomeProvincia) VALUES
(1, 1, 'Torino'),
(2, 2, 'Milano'),
(3, 3, 'Roma');

-- Inserimento dati nella tabella Regione
INSERT INTO Regione (IdRegione, NomeRegione) VALUES
(1, 'Piemonte'),
(2, 'Lombardia'),
(3, 'Lazio');

-- Inserimento dati nella tabella Comune
INSERT INTO Comune (IdComune, NomeComune, IdProvincia) VALUES
(1, 'Torino', 1),
(2, 'Milano', 2),
(3, 'Roma', 3);

-- Inserimento dati nella tabella Cap
INSERT INTO Cap (IdCap, IdComune, CodicePostale) VALUES
(1, 1, '10100'),
(2, 2, '20100'),
(3, 3, '00100');
