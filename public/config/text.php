<?php
/**
 * Copyright (c) Daniel Bacher 2016.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 *  Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
 */

// HTTP Fehler
define("ERROR_MSG_404", "<h3>Fehler 404: Seite nicht gefunden</h3><br />Hallo, <br />hier fehlt scheinbar was. Bitte überprüfe die eingebene URL.");

// Bild löschen
define("MSG_IMG_DELETE_SUCCESS", "Bild erfolgreich gelöscht");
define("MSG_IMG_DELETE_FAIL", "Bild konnte nicht gelöscht werden");

// Bild ersetzten
define("MSG_IMG_REPLACE_SUCCESS", "Bild erfolgreich ersetzt");
define("MSG_IMG_REPLACE_FAIL", "Bild konnte nicht ersetzt werden");



// Anzeige anlegen
define("MSG_AD_CREATE_UNATHORIZED", "Für das erstellen von Anzeigen musst du angemeldet sein");
define("MSG_AD_CREATE_SUCCESS", "Anzeige erfolgreich angelegt");

// Anzeige löschen
define("MSG_SUCCESFULL_DELETE", "Anzeige erfolgreich gelöscht");
define("MSG_FAILD_DELETE", "Anzeige konnte nicht gelöscht werden");

// Anzeige verändern
define("MSG_AD_MODIFY_SUCCESS", "Anzeige erfolgreich verändert");
define("MSG_AD_MODIFY_FAIL", "Anzeige nicht verändert");
define("MSG_AD_MODIFY_UNATHORIZED", "Für das verändern von Anzeigen musst du angemeldet und deren ersteller sein!");

// Anzeige anzeigen
define("MSG_AD_VIEW_NOT_FOUND", "Anzeige wurde nicht gefunden!");

// Anzeige suchen
define("MSG_AD_SEARCH_NOT_FOUND", "Nichts gefunden!");

// User löschen
define("MSG_USER_DELETE_SUCCESS", "Account und alle zugehörigen Daten erfolgreich gelöscht!");

// User registrieren
define("MSG_USER_REGISTER_DATA_ERROR", "Fehlerhafte Daten eingegeben!");
define("MSG_USER_REGISTER_MAIL_EXISTS", "Diese E-Mail existiert bereits");
define("MSG_USER_REGISTER_SUCCESS", "Account erfolgreich angelegt");
define("MSG_USER_REGISTER_FAIL", "Account konnte nicht erstellt werden");

// User login
define("MSG_USER_LOGIN_SUCCESS", "Erfolgreich eingeloggt!");
define("MSG_USER_LOGIN_FAIL", "Fehler beim Einloggen bitte überprüfe deine Daten");