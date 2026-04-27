# 📝 nextcloud-protokolle

**Kollaborativer Protokoll-Editor für studentische Gremien als
Nextcloud-App.**

`nextcloud-protokolle` soll Sitzungsprotokolle dort entstehen lassen, wo sie
später ohnehin liegen, geteilt und archiviert werden: direkt im
Nextcloud-Dateisystem.

Das Projekt startet für die Studierendenschaft der Hochschule Darmstadt und
ist langfristig so gedacht, dass auch andere deutsche Studierendenschaften es
einsetzen, anpassen und gemeinsam weiterentwickeln können.

## 🚦 Status

**Phase 0: Projekt-Setup, Recherche und Architektur — abgeschlossen**

Aktuell gibt es noch keinen lauffähigen App-Code, kein Composer-Setup, keine
Node-Abhängigkeiten und keine installierbare Nextcloud-App. Dieses Repository
legt zuerst die konzeptionelle Basis: Doku, Lizenz, Struktur und technische
Richtung.

Die Architektur ist inzwischen bewusst eng an Nextcloud Text angelehnt:
Markdown im Files-Tree, Tiptap, Yjs/Hocuspocus und Nextcloud-kompatible
Persistierung bilden die Leitplanken. `nextcloud-protokolle` bleibt eine
eigene App, übernimmt aber bewährte Patterns statt den Editor-Stack neu zu
erfinden.

| Bereich | Status |
| --- | --- |
| Repository & Lizenz | ✅ angelegt |
| Architektur & Roadmap | ✅ geschärft |
| Upstream-Recherche | ✅ dokumentiert |
| Nextcloud-App-Code | ⏳ geplant |
| Editor | ⏳ geplant |
| Live-Collaboration | ⏳ geplant |
| PDF-Export | ⏳ geplant |

## 💡 Warum?

Viele studentische Gremien schreiben Protokolle heute in Office-Dateien,
kopierten Vorlagen, Markdown-Dokumenten oder externen Kollaborationstools.
Das funktioniert, aber es macht den Alltag oft unnötig schwer:

- 📄 Vorlagen driften auseinander
- 🔎 Beschlüsse sind später schwer auffindbar
- 🧾 PDF-Exporte müssen manuell nachbearbeitet werden
- 🔐 Berechtigungen leben neben der eigentlichen Dateiablage
- 🧠 Neue Protokollant*innen müssen erst lokale Formatierungsregeln lernen

`nextcloud-protokolle` soll daraus einen einfachen, wiederholbaren Workflow
machen.

## 🔁 Geplanter Ablauf

```text
📁 Nextcloud Files
   ↓
➕ Neues Protokoll anlegen
   ↓
🧩 Semantischer Editor öffnet sich
   ↓
✍️ TOPs, Anwesenheit, Abstimmungen und Beschlüsse erfassen
   ↓
💾 Markdown-Datei wird im Ordner gespeichert
   ↓
📚 Beschlüsse und Sitzungen werden indizierbar
   ↓
📤 PDF-Export via Typst
```

Der wichtigste Gedanke: Ein Protokoll ist nicht nur formatierter Text. Ein
Tagesordnungspunkt, eine Abstimmung oder ein Beschluss sind fachliche
Bestandteile einer Sitzung. Genau diese Struktur soll der Editor verstehen.

## 🧱 Geplante Bausteine

| Baustein | Aufgabe |
| --- | --- |
| 📁 Markdown-Dateien mit YAML-Blöcken | Protokolle bleiben als `.md` oder `.protokoll.md` im normalen Nextcloud-Ordnerbaum lesbar |
| ☁️ Nextcloud-App | Integration in Files, Berechtigungen, Sharing und Datenbank |
| 📑 Nextcloud Text als Architektur-Vorbild | Referenz für Markdown-Persistierung, Tiptap, Yjs/Hocuspocus und Files-Integration |
| 🧩 Vue 3 + Tiptap | Block-Editor mit eigenen Nodes für TOPs, Abstimmungen, Beschlüsse und Anwesenheit |
| 🔐 user_oidc als Auth-Bridge | Nextcloud ↔ authentik läuft über die bestehende OIDC-App, nicht über eigenen authentik-Code |
| 🗃️ Stammdaten | Gremien, Personen, Rollen und Mitgliedschaften in der Nextcloud-DB |
| 🗳️ Rollenmodell | Stimmrechte werden aus Rollen im jeweiligen Gremium abgeleitet |
| 📄 Typst CLI | Serverseitiger finaler PDF-Export |
| ⚡ typst.ts | Spätere Live-Preview im Browser |
| 🤝 Yjs + Hocuspocus | Spätere Echtzeit-Zusammenarbeit mit Awareness-Cursors |
| 📚 Beschluss-Index | Beschlüsse als eigene Entitäten mit stabiler ID |

## 🗂️ Repository-Struktur

```text
nextcloud-protokolle/
├── nextcloud-app/        # späterer PHP-Code der Nextcloud-App
├── editor/               # spätere Vue/Tiptap-SPA
├── hocuspocus-server/    # späterer Yjs-WebSocket-Server
├── typst-templates/      # spätere Typst-Templates
├── docs/                 # weitere Dokumentation
├── README.md
├── ROADMAP.md
├── ARCHITECTURE.md
├── CONTRIBUTING.md
└── LICENSE
```

## 🛣️ Nächste Schritte

Der erste technische Meilenstein ist ein Single-User-MVP:

1. 🧩 Nextcloud-App-Skelett erstellen
2. 🗃️ Stammdaten für Gremien, Rollen und Mitgliedschaften modellieren
3. ✍️ Tiptap-Editor mit semantischen Blöcken aufbauen
4. 💾 Markdown-Dateien mit eingebetteten YAML-Blöcken lesen und schreiben
5. 📤 PDF-Export über Typst CLI anbinden

Live-Collaboration kommt bewusst danach, damit der Grundworkflow zuerst stabil
steht.

## 🔗 Quick Links

- 🛣️ [Roadmap](ROADMAP.md)
- 🏗️ [Architektur](ARCHITECTURE.md)
- 🚀 [Getting Started](docs/getting-started.md)
- 🧱 [Verwendete Open-Source-Projekte](docs/upstream-projects.md)
- 🤝 [Beitragen](CONTRIBUTING.md)
- ⚖️ [Lizenz](LICENSE)

## ⚖️ Lizenz

Dieses Projekt steht unter der **GNU Affero General Public License v3.0**
(`AGPL-3.0`).

Die Lizenz passt zum Ziel des Projekts: Verbesserungen an einer serverseitig
betriebenen, gemeinschaftlich nutzbaren Anwendung sollen wieder der
Gemeinschaft zugutekommen.
