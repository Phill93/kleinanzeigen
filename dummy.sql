USE kleinanzeigen;
INSERT INTO category(name)
  VALUES('Fahrzeuge');
INSERT INTO category(name)
  VALUES('Möbel');
INSERT INTO category(name)
  VALUES('Haustiere');
INSERT INTO category(name)
  VALUES('Hobby');


# User max@muster.de PW muster
INSERT INTO kleinanzeigen.ort (id, name, postcode) VALUES (1, 'Musterhausen', '12345');
INSERT INTO kleinanzeigen.user (name, surname, mail, password, street, housenumber, fsOrt) VALUES ('Max', 'Mustermann', 'max@muster.de', '$2y$12$4b168e4d6745edbd6755fuRpOmUVKIFIorS79rkkT2m5/Enoe7jmy', 'Musterstraße', '12', 1);
INSERT INTO kleinanzeigen.ad (title, content, price, fsUser, fscategory) VALUES ('Muster zu Verkaufen', 'Verkaufe mein Muster Gruß Max', 50.99, 1, 2);