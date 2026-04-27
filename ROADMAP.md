# Roadmap

Die Roadmap beschreibt den geplanten Weg von einem leeren Repository zu einer
nutzbaren Nextcloud-App. Zeitangaben sind grobe Schätzungen für ein junges
Open-Source-Projekt und hängen stark von Verfügbarkeit, Tests in echten
Gremiensitzungen und Rückmeldungen aus der Nextcloud-Umgebung ab.

```mermaid
flowchart LR
    P0["✅ Phase 0<br/>Projekt-Setup"]
    P1A["⏳ Phase 1a<br/>Backend & Stammdaten"]
    P1B["🔜 Phase 1b<br/>Editor & Datei-Format"]
    P2["🔜 Phase 2<br/>Live-Collaboration"]
    P3["🔜 Phase 3<br/>Multi-Gremium & API"]
    P4["🔜 Phase 4<br/>fzs-Readiness"]

    P0 --> P1A --> P1B --> P2 --> P3 --> P4
```

| Phase | Titel | Status | Geschätzter Aufwand |
| --- | --- | --- | --- |
| Phase 0 | Projekt-Setup | ✅ abgeschlossen | wenige Tage |
| Phase 1a | Backend & Stammdaten | ⏳ als Nächstes | 2 Monate |
| Phase 1b | Editor & Datei-Format | 🔜 geplant | 2-3 Monate |
| Phase 2 | Live-Collaboration | 🔜 geplant | 1-2 Monate |
| Phase 3 | Multi-Gremium & Beschluss-API | 🔜 geplant | 1-2 Monate |
| Phase 4 | fzs-Readiness | 🔜 geplant | 2-3 Monate |

## Querschnittsthemen

Diese Themen laufen über mehrere Phasen hinweg und sollen nicht erst am Ende
nachgezogen werden.

| Thema | Leitlinie |
| --- | --- |
| Tests | Ab Phase 1a automatisierte Tests für kritische Pfade: Stammdaten-CRUD, Datei-Format-Parser und Stimmrechtsableitung. Die Testabdeckung wächst in jeder folgenden Phase mit. |
| Pilot-Sitzungen | Ab Phase 1b regelmäßig in echten Sitzungen der hda-Studierendenschaft testen. Feedback fließt direkt in die laufende Phase zurück. |
| User-Dokumentation | Ab Phase 1b parallel eine Bedienungsanleitung für Protokollführer*innen pflegen, nicht erst kurz vor Veröffentlichung. |
| Sicherheits-Review | Spätestens in Phase 2 für die Auth-Bridge, in Phase 4 erneut vor der App-Store-Submission. |
| Versionsverwaltung | Eingebaute Datei-Versionierung von Nextcloud nutzen; keine eigene Implementierung für `.protokoll`-Versionen. |

---

## ✅ Phase 0 - Projekt-Setup

**Status:** abgeschlossen

### Ziele

- Öffentliches Repository mit klarer Lizenz anlegen
- Grundlegende Dokumentation erstellen
- Architekturentscheidungen schriftlich festhalten
- Projektstruktur für spätere Komponenten vorbereiten
- Einen gemeinsamen sprachlichen Rahmen für Mitwirkende schaffen

### Done when...

- [x] Das Repository ist initialisiert und enthält README, Roadmap,
  Architektur-Dokument, Beitragsplatzhalter, Lizenz und `.gitignore`.
- [x] Die geplanten Komponenten sind als leere Verzeichnisse sichtbar.
- [x] Die Lizenz ist eindeutig AGPL-3.0.
- [x] Es wird noch kein nicht lauffähiger App- oder Build-Code vorgetäuscht.

### Geschätzter Aufwand

Wenige Tage für Setup, Abstimmung und erste Dokumentation.

---

## 🧱 Phase 1a - Backend & Stammdaten

**Status:** als Nächstes

### Ziele

- Nextcloud-App-Skelett auf Basis des Nextcloud-App-Frameworks erstellen
- Stammdatenverwaltung für Gremien, Rollen und Mitgliedschaften umsetzen
- Personen-Sync aus Nextcloud-Usern vorbereiten
- Settings-UI für Stammdaten bereitstellen
- Rollenbasierte Stimmrechtsableitung implementieren
- Automatisierte Tests für Stammdaten-CRUD und Stimmrechtslogik beginnen

### Done when...

- [ ] Stammdaten können vollständig gepflegt und über die Settings-UI
  verwaltet werden.
- [ ] Gremien, Rollen und Mitgliedschaften sind in der Nextcloud-Datenbank
  abbildbar.
- [ ] Personen können aus Nextcloud-Usern referenziert oder synchronisiert
  werden.
- [ ] Aus Rollen wird ableitbar, wer in einem Gremium stimmberechtigt ist.
- [ ] Kritische Stammdaten- und Stimmrechtslogik ist durch erste Tests
  abgesichert.

### Geschätzter Aufwand

2 Monate.

---

## 🧩 Phase 1b - Editor & Datei-Format

**Status:** geplant

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

Phase 1b nutzt ein einzelnes, generisches Template. Gremienspezifische
Templates kommen bewusst erst in Phase 3. Diese Phase soll zuerst beweisen,
dass ein generischer Protokoll-Workflow für eine reale Sitzung funktioniert.

### Done when...

- [ ] Eine reale Gremiensitzung kann mit der App protokolliert und als PDF
  exportiert werden.
- [ ] Ein*e Nutzer*in kann in Nextcloud ein Protokoll anlegen, bearbeiten,
  speichern und wieder öffnen.
- [ ] Die Datei liegt als `.protokoll` im normalen Nextcloud-Dateibaum.
- [ ] Abstimmungen und Beschlüsse werden strukturiert im Dokument gespeichert.
- [ ] Ein PDF kann serverseitig reproduzierbar erzeugt werden.
- [ ] Der Single-User-MVP funktioniert ohne Live-Collaboration.

### Geschätzter Aufwand

2-3 Monate.

---

## 🤝 Phase 2 - Live-Collaboration

**Status:** geplant

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

- [ ] Zwei angemeldete Nutzer*innen können dasselbe Protokoll gleichzeitig
  bearbeiten.
- [ ] Änderungen erscheinen ohne manuelles Neuladen bei allen Teilnehmenden.
- [ ] Der WebSocket-Zugriff respektiert Nextcloud-Berechtigungen.
- [ ] Anwesenheit und Cursorpositionen sind im Editor sichtbar.
- [ ] Die Live-Preview rendert eine realistische Vorschau, ohne den finalen
  Server-Export zu ersetzen.
- [ ] Die Auth-Bridge wurde mindestens einmal extern reviewed (Security).

### Geschätzter Aufwand

1-2 Monate.

---

## 🌐 Phase 3 - Multi-Gremium & Beschluss-API

**Status:** geplant

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

- [ ] Mehrere Gremien können eigene Vorlagen und Rollenmodelle verwenden.
- [ ] Beschlüsse lassen sich gremien- und sitzungsübergreifend abfragen.
- [ ] Externe Personen können in Sitzungen, Anwesenheiten und Rollenmodellen
  sinnvoll abgebildet werden.
- [ ] Externe Systeme können Beschlussdaten über eine dokumentierte API lesen.
- [ ] Ein Markdown-Export erzeugt brauchbare Inhalte für Wiki-Workflows.
- [ ] Die Datenstruktur bleibt kompatibel zu bestehenden `.protokoll`-Dateien
  aus Phase 1b oder es gibt eine dokumentierte Migration.

### Geschätzter Aufwand

1-2 Monate.

---

## 🚀 Phase 4 - fzs-Readiness

**Status:** geplant

### Ziele

- Internationalisierung für Deutsch und Englisch vorbereiten
- Setup-Dokumentation für andere Studierendenschaften schreiben
- Betrieb, Updates, Backup und Rechtekonzepte dokumentieren
- Nextcloud-App-Store-Submission vorbereiten
- Rückmeldungen aus externen Testinstallationen einarbeiten
- Sicherheits-Review vor der App-Store-Submission wiederholen

### Done when...

- [ ] Die App kann außerhalb der Hochschule Darmstadt nachvollziehbar
  installiert und konfiguriert werden.
- [ ] Die wichtigsten Oberflächentexte sind übersetzbar.
- [ ] Installations- und Betriebshinweise sind für ehrenamtliche Admins
  verständlich.
- [ ] Die App erfüllt die formalen Voraussetzungen für eine
  Nextcloud-App-Store-Einreichung.
- [ ] Das Projekt ist organisatorisch offen genug, damit weitere
  Studierendenschaften sinnvoll beitragen können.

### Geschätzter Aufwand

2-3 Monate.

---

## Wie diese Roadmap gepflegt wird

Diese Roadmap ist ein lebendes Projektdokument. Aufwände sind Schätzungen,
keine Zusagen, und sollen nach Pilot-Sitzungen, Reviews und Implementierungs-
erfahrung angepasst werden. Änderungen an Scope, Reihenfolge oder Done-when-
Kriterien sollten nachvollziehbar begründet werden. Pull Requests mit
konkreten Verbesserungen sind willkommen.
