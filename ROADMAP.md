# Roadmap

Die Roadmap beschreibt den geplanten Weg von einem leeren Repository zu einer
nutzbaren Nextcloud-App. Zeitangaben sind grobe Schaetzungen fuer ein junges
Open-Source-Projekt und haengen stark von Verfuegbarkeit, Tests in echten
Gremiensitzungen und Rueckmeldungen aus der Nextcloud-Umgebung ab.

## Phase 0 - Projekt-Setup

**Status:** in Arbeit

### Ziele

- Oeffentliches Repository mit klarer Lizenz anlegen
- Grundlegende Dokumentation erstellen
- Architekturentscheidungen schriftlich festhalten
- Projektstruktur fuer spaetere Komponenten vorbereiten
- Einen gemeinsamen sprachlichen Rahmen fuer Mitwirkende schaffen

### Done when...

- Das Repository ist initialisiert und enthaelt README, Roadmap,
  Architektur-Dokument, Beitragsplatzhalter, Lizenz und `.gitignore`.
- Die geplanten Komponenten sind als leere Verzeichnisse sichtbar.
- Die Lizenz ist eindeutig AGPL-3.0.
- Es wird noch kein nicht lauffaehiger App- oder Build-Code vorgetaeuscht.

### Geschaetzter Aufwand

Wenige Tage fuer Setup, Abstimmung und erste Dokumentation.

## Phase 1 - MVP

### Ziele

- Nextcloud-App-Skelett auf Basis des Nextcloud-App-Frameworks erstellen
- Stammdatenverwaltung fuer Gremien, Rollen und Mitgliedschaften umsetzen
- Personen aus Nextcloud-Usern referenzieren
- Tiptap-Editor mit semantischen Bloecken bereitstellen:
  TOP, Bullet, Abstimmung, Beschluss und Anwesenheit
- `.protokoll`-Dateien lesen, schreiben und validieren
- Integration in Nextcloud Files vorbereiten:
  Datei anlegen, Datei oeffnen, Editor anzeigen
- Serverseitigen PDF-Export ueber Typst CLI implementieren
- Single-User-Arbeitsfluss stabil nutzbar machen

### Done when...

- Ein*e Nutzer*in kann in Nextcloud ein Protokoll anlegen, bearbeiten,
  speichern und wieder oeffnen.
- Die Datei liegt als `.protokoll` im normalen Nextcloud-Dateibaum.
- Mindestens ein Gremium mit Rollen und Mitgliedschaften kann gepflegt
  werden.
- Aus Rollen wird ableitbar, wer stimmberechtigt ist.
- Abstimmungen und Beschluesse werden strukturiert im Dokument gespeichert.
- Ein PDF kann serverseitig reproduzierbar erzeugt werden.
- Der MVP funktioniert ohne Live-Collaboration.

### Geschaetzter Aufwand

2-3 Monate.

## Phase 2 - Live-Collaboration

### Ziele

- Hocuspocus-Server fuer Yjs-basierte Echtzeitbearbeitung bereitstellen
- Yjs in den Tiptap-Editor integrieren
- Auth-Bridge zur Nextcloud-Session entwerfen und umsetzen
- Awareness-Cursors und Praesenzinformationen anzeigen
- Konfliktarme Synchronisation zwischen `.protokoll`-Datei und Yjs-Dokument
  definieren
- Browser-Live-Preview mit `typst.ts` erproben

### Done when...

- Zwei angemeldete Nutzer*innen koennen dasselbe Protokoll gleichzeitig
  bearbeiten.
- Aenderungen erscheinen ohne manuelles Neuladen bei allen Teilnehmenden.
- Der WebSocket-Zugriff respektiert Nextcloud-Berechtigungen.
- Anwesenheit und Cursorpositionen sind im Editor sichtbar.
- Die Live-Preview rendert eine realistische Vorschau, ohne den finalen
  Server-Export zu ersetzen.

### Geschaetzter Aufwand

1-2 Monate.

## Phase 3 - Multi-Gremium & Beschluss-API

### Ziele

- Templates fuer unterschiedliche Gremienarten ergaenzen, insbesondere
  StuPa, FSK und FSR
- Beschluss-Index als REST-API bereitstellen
- Beschluesse ueber stabile IDs auffindbar machen
- Markdown-Export fuer Wikis und externe Dokumentationssysteme ergaenzen
- Unterschiede zwischen Geschaeftsordnungen und Protokollstilen abbildbar
  machen, ohne den Editor zu ueberladen

### Done when...

- Mehrere Gremien koennen eigene Vorlagen und Rollenmodelle verwenden.
- Beschluesse lassen sich gremien- und sitzungsuebergreifend abfragen.
- Externe Systeme koennen Beschlussdaten ueber eine dokumentierte API lesen.
- Ein Markdown-Export erzeugt brauchbare Inhalte fuer Wiki-Workflows.
- Die Datenstruktur bleibt kompatibel zu bestehenden `.protokoll`-Dateien
  aus dem MVP.

### Geschaetzter Aufwand

1-2 Monate.

## Phase 4 - fzs-Readiness

### Ziele

- Internationalisierung fuer Deutsch und Englisch vorbereiten
- Setup-Dokumentation fuer andere Studierendenschaften schreiben
- Betrieb, Updates, Backup und Rechtekonzepte dokumentieren
- Nextcloud-App-Store-Submission vorbereiten
- Rueckmeldungen aus externen Testinstallationen einarbeiten

### Done when...

- Die App kann ausserhalb der Hochschule Darmstadt nachvollziehbar
  installiert und konfiguriert werden.
- Die wichtigsten Oberflaechentexte sind uebersetzbar.
- Installations- und Betriebshinweise sind fuer ehrenamtliche Admins
  verstaendlich.
- Die App erfuellt die formalen Voraussetzungen fuer eine
  Nextcloud-App-Store-Einreichung.
- Das Projekt ist organisatorisch offen genug, damit weitere
  Studierendenschaften sinnvoll beitragen koennen.

### Geschaetzter Aufwand

2-3 Monate.

