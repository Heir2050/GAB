create table banque(
    id_banque int PRIMARY KEY AUTOINCREMENT,
    nom_banque
);

create table guichet(
    id_guichet int PRIMARY KEY AUTOINCREMENT,
    id_banque int,
    nom_guichet
    FOREIGN KEY (id_banque) REFERENCES banque(id_banque),
);

create table province(
    id_province int PRIMARY KEY AUTOINCREMENT,
    id_guichet int,
    nom_province
    FOREIGN KEY (id_guichet) REFERENCES guichet(id_guichet),
);

create table commune(
    id_commune int PRIMARY KEY AUTOINCREMENT,
    id_province int,
    nom_commune
    FOREIGN KEY (id_province) REFERENCES province(id_province),
);

create table zone(
    id_zone int PRIMARY KEY AUTOINCREMENT,
    id_commune int,
    nom_zone
    FOREIGN KEY (id_commune) REFERENCES commune(id_commune),
);

create table quartier(
    id_quartier int PRIMARY KEY AUTOINCREMENT,
    id_zone int,
    nom_quartier
    FOREIGN KEY (id_zone) REFERENCES zone(id_zone),
);