# README – Huge Messenger
<img width="530" height="419" alt="Messenger" src="https://github.com/user-attachments/assets/a40bc068-9666-412f-b3bf-bb945e11a914" />

## Projektbeschreibung

Dieses Projekt ist eine Webapplikation auf Basis des **HUge Frameworks (PHP, MVC)**.  
Im Rahmen der Aufgabenstellung wurde ein **einfacher Messengerdienst** implementiert, mit dem eingeloggte Benutzer **untereinander Nachrichten austauschen** können.

---

## Funktionsumfang

- Messenger nur für **angemeldete Benutzer**
- Auswahl anderer Benutzer als **Chatpartner**
- Senden und Empfangen von **Nachrichten**
- Speicherung der Nachrichten in einer **Datenbank**
- Anzeige von **ungelesenen Nachrichten** im Menü
- **Testmöglichkeit über URL-Parameter**

---

## Architektur (MVC)

Die Anwendung folgt dem **Model–View–Controller-Prinzip**:

- **Model**  
  Enthält alle Datenbankzugriffe (Nachrichten speichern, laden, Lese-Status)

- **Controller**  
  Steuert den Ablauf, prüft die Authentifizierung und verarbeitet Benutzereingaben

- **View**  
  Zuständig für die Darstellung und das Laden der Nachrichten per AJAX

Diese Trennung sorgt für **Übersichtlichkeit und Wartbarkeit** des Codes.

---

## Datenbank

Die zentrale Tabelle `messages` speichert:

- Absender (`sender_id`)
- Empfänger (`receiver_id`)
- Nachrichtentext (`content`)
- Lese-Status (`is_read`)
- Zeitstempel (`created_at`)

---

## Sicherheit

- Zugriff auf den Messenger **nur nach Login**
- **Serverseitige Eingabevalidierung**
- Verwendung von **Prepared Statements** zum Schutz vor SQL-Injection

---

## Technologien

- PHP (OOP)
- HUge Framework
- MySQL
- MVC-Architektur
- Git / GitHub

---

## Fazit

Der Messengerdienst erfüllt alle Anforderungen der Aufgabenstellung und zeigt die praktische Anwendung von **MVC**, **Datenbankanbindung** und **Benutzerverwaltung** in einer PHP-Webapplikation.
