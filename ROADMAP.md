# Roadmap

Die Roadmap beschreibt den geplanten Weg von einem leeren Repository zu einer
nutzbaren Nextcloud-App. Zeitangaben sind grobe Schätzungen für ein junges
Open-Source-Projekt und hängen stark von Verfügbarkeit, Tests in echten
Gremiensitzungen und Rückmeldungen aus der Nextcloud-Umgebung ab.

## Querschnittsthemen

Diese Themen laufen über mehrere Phasen hinweg und sollen nicht erst am Ende
nachgezogen werden.

**Tests:** Ab Phase 1a sollen automatisierte Tests für kritische Pfade
entstehen, insbesondere für Stammdaten-CRUD, Datei-Format-Parser und
Stimmrechtsableitung. Die Testabdeckung soll in jeder folgenden Phase mit der
Komplexität des Projekts mitwachsen.

**Pilot-Sitzungen:** Ab Phase 1b soll das Projekt regelmäßig in echten
Sitzungen der hda-Studierendenschaft getestet werden. Feedback aus diesen
Sitzungen fließt direkt in die jeweilige Phase zurück.

**User-Dokumentation:** Ab Phase 1b soll parallel eine Bedienungsanleitung
für Protokollführer*innen gepflegt werden. Die Doku entsteht also nicht erst
kurz vor einer Veröffentlichung, sondern wächst mit den realen Workflows.

**Sicherheits-Review:** Spätestens in Phase 2 braucht die Auth-Bridge zur
Nextcloud-Session ein Security-Review. Vor einer App-Store-Submission in
Phase 4 soll ein weiteres Review stattfinden.

**Versionsverwaltung von Protokollen:** Das Projekt nutzt die eingebaute
Dateiversionierung von Nextcloud und implementiert keine eigene
Versionsverwaltung für `.protokoll`-Dateien. Diese Designentscheidung ist in
der Architektur dokumentiert.

## Phase 0 - Projekt-Setup

**Status:** abgeschlossen

### Ziele

- Öffentliches Repository mit klarer Lizenz anlegen
- Grundlegende Dokumentation erstellen
- Architekturentscheidungen schriftlich festhalten
- Projektstruktur für spätere Komponenten vorbereiten
- Einen gemeinsamen sprachlichen Rahmen für Mitwirkende schaffen

### Done when...

- Das Repository ist initialisiert und enthält README, Roadmap,
  Architektur-Dokument, Beitragsplatzhalter, Lizenz und `.gitignore`.
- Die geplanten Komponenten sind als leere Verzeichnisse sichtbar.
- Die Lizenz ist eindeutig AGPL-3.0.
- Es wird noch kein nicht lauffähiger App- oder Build-Code vorgetäuscht.

### Geschätzter Aufwand

Wenige Tage für Setup, Abstimmung und erste Dokumentation.

## Phase 1a - Backend & Stammdaten

### Ziele

- Nextcloud-App-Skelett auf Basis des Nextcloud-App-Frameworks erstellen
- Stammdatenverwaltung für Gremien, Rollen und Mitgliedschaften umsetzen
- Personen-Sync aus Nextcloud-Usern vorbereiten
- Settings-UI für Stammdaten bereitstellen
- Rollenbasierte Stimmrechtsableitung implementieren
- Automatisierte Tests für Stammdaten-CRUD und Stimmrechtslogik beginnen

### Done when...

- Stammdaten können vollständig gepflegt und über die Settings-UI verwaltet
  werden.
- Gremien, Rollen und Mitgliedschaften sind in der Nextcloud-Datenbank
  abbildbar.
- Personen können aus Nextcloud-Usern referenziert oder synchronisiert
  werden.
- Aus Rollen wird ableitbar, wer in einem Gremium stimmberechtigt ist.
- Kritische Stammdaten- und Stimmrechtslogik ist durch erste Tests
  abgesichert.

### Geschätzter Aufwand

2 Monate.

## Phase 1b - Editor & Datei-Format

### Ziele

- Tiptap-Editor mit semantischen Blöcken bereitstellen:
  TOP, Bullet, Abstimmung, Beschluss und Anwesenheit
- `.protokoll`-Datei-Format definieren, lesen, schreiben und validieren
- Integration in Nextcloud Files vorbereiten:
  Datei anlegen, Datei öffnen, Editor anzeigen
- Serverseitigen PDF-Export über Typst CLI implementieren
- Ein einzelnes, generisches Typst-Template für den MVP nutzen
- Single-User-Arbeitsfluss stabil nutzbar machen
- Bedienungsanleitung für Protokollführer*innen parallel beginnen
- Pilot-Sitzungen mit realen Gremien vorbereiten und auswerten

Gremienspezifische Templates kommen bewusst erst in Phase 3. Phase 1b soll
zunächst beweisen, dass ein generischer Protokoll-Workflow für eine reale
Sitzung funktioniert.

### Done when...

- Eine reale Gremiensitzung kann mit der App protokolliert und als PDF
  exportiert werden.
- Ein*e Nutzer*in kann in Nextcloud ein Protokoll anlegen, bearbeiten,
  speichern und wieder öffnen.
- Die Datei liegt als `.protokoll` im normalen Nextcloud-Dateibaum.
- Abstimmungen und Beschlüsse werden strukturiert im Dokument gespeichert.
- Ein PDF kann serverseitig reproduzierbar erzeugt werden.
- Der Single-User-MVP funktioniert ohne Live-Collaboration.

### Geschätzter Aufwand

2-3 Monate.

## Phase 2 - Live-Collaboration

### Ziele

- Hocuspocus-Server für Yjs-basierte Echtzeitbearbeitung bereitstellen
- Yjs in den Tiptap-Editor integrieren
- Auth-Bridge zur Nextcloud-Session entwerfen und umsetzen
- Awareness-Cursors und Präsenzinformationen anzeigen
- Konfliktarme Synchronisation zwischen `.protokoll`-Datei und Yjs-Dokument
  definieren
- Browser-Live-Preview mit `typst.ts` erproben
- Sicherheits-Review der Auth-Bridge einplanen und durchführen

### Done when...

- Zwei angemeldete Nutzer*innen können dasselbe Protokoll gleichzeitig
  bearbeiten.
- Änderungen erscheinen ohne manuelles Neuladen bei allen Teilnehmenden.
- Der WebSocket-Zugriff respektiert Nextcloud-Berechtigungen.
- Anwesenheit und Cursorpositionen sind im Editor sichtbar.
- Die Live-Preview rendert eine realistische Vorschau, ohne den finalen
  Server-Export zu ersetzen.
- Die Auth-Bridge wurde mindestens einmal extern reviewed (Security).

### Geschätzter Aufwand

1-2 Monate.

## Phase 3 - Multi-Gremium & Beschluss-API

### Ziele

- Templates für unterschiedliche Gremienarten ergänzen, insbesondere
  StuPa, FSK und FSR
- Beschluss-Index als REST-API bereitstellen
- Beschlüsse über stabile IDs auffindbar machen
- Markdown-Export für Wikis und externe Dokumentationssysteme ergänzen
- Unterstützung für externe Personen wie Gäste oder beratende Teilnehmende
  umsetzen
- Unterschiede zwischen Geschäftsordnungen und Protokollstilen abbildbar
  machen, ohne den Editor zu überladen
- Falls sich das Format ändert, Migrationen für `.protokoll`-Dateien aus
  Phase 1b bereitstellen

### Done when...

- Mehrere Gremien können eigene Vorlagen und Rollenmodelle verwenden.
- Beschlüsse lassen sich gremien- und sitzungsübergreifend abfragen.
- Externe Personen können in Sitzungen, Anwesenheiten und Rollenmodellen
  sinnvoll abgebildet werden.
- Externe Systeme können Beschlussdaten über eine dokumentierte API lesen.
- Ein Markdown-Export erzeugt brauchbare Inhalte für Wiki-Workflows.
- Die Datenstruktur bleibt kompatibel zu bestehenden `.protokoll`-Dateien
  aus Phase 1b oder es gibt eine dokumentierte Migration.

### Geschätzter Aufwand

1-2 Monate.

## Phase 4 - fzs-Readiness

### Ziele

- Internationalisierung für Deutsch und Englisch vorbereiten
- Setup-Dokumentation für andere Studierendenschaften schreiben
- Betrieb, Updates, Backup und Rechtekonzepte dokumentieren
- Nextcloud-App-Store-Submission vorbereiten
- Rückmeldungen aus externen Testinstallationen einarbeiten
- Sicherheits-Review vor der App-Store-Submission wiederholen

### Done when...

- Die App kann außerhalb der Hochschule Darmstadt nachvollziehbar
  installiert und konfiguriert werden.
- Die wichtigsten Oberflächentexte sind übersetzbar.
- Installations- und Betriebshinweise sind für ehrenamtliche Admins
  verständlich.
- Die App erfüllt die formalen Voraussetzungen für eine
  Nextcloud-App-Store-Einreichung.
- Das Projekt ist organisatorisch offen genug, damit weitere
  Studierendenschaften sinnvoll beitragen können.

### Geschätzter Aufwand

2-3 Monate.
