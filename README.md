# nextcloud-protokolle

`nextcloud-protokolle` ist eine geplante Nextcloud-App fuer studentische
Gremien, die Sitzungsprotokolle direkt dort entstehen laesst, wo sie spaeter
liegen, geteilt und archiviert werden: im Nextcloud-Dateisystem.

Das Projekt richtet sich zuerst an die Studierendenschaft der Hochschule
Darmstadt und ist langfristig so gedacht, dass auch andere deutsche
Studierendenschaften es einsetzen, anpassen und gemeinsam weiterentwickeln
koennen.

## Projektidee

Viele studentische Gremien schreiben Protokolle heute in einzelnen
Office-Dateien, kopierten Vorlagen, Markdown-Dokumenten oder externen
Kollaborationstools. Das funktioniert, ist aber oft fehleranfaellig:
Vorlagen driften auseinander, Beschluesse sind schwer auffindbar, Exporte
muessen manuell nachbearbeitet werden und Berechtigungen leben neben der
eigentlichen Dateiablage.

`nextcloud-protokolle` will diesen Arbeitsfluss vereinfachen:

1. Im Nextcloud-Files-Menue wird ein neues Protokoll angelegt.
2. Ein Editor oeffnet sich direkt in Nextcloud.
3. Protokollant*innen schreiben mit semantischen Bloecken statt mit
   Formatierungswissen.
4. Abstimmungen, Beschluesse, Tagesordnungspunkte und Anwesenheit sind
   strukturierte Bestandteile des Dokuments.
5. Am Ende wird ein sauberes PDF erzeugt.

Das Ziel ist nicht, ein weiteres allgemeines Textverarbeitungsprogramm zu
bauen. Das Ziel ist ein Werkzeug fuer den konkreten Gremienalltag:
Sitzungen vorbereiten, mitschreiben, Beschluesse festhalten, exportieren und
wiederfinden.

## Geplanter Funktionsumfang

- Datei-basiertes Format `.protokoll` als JSON im normalen Nextcloud-
  Ordnerbaum
- Nextcloud-App fuer Integration in Files, Berechtigungen und Sharing
- Vue-3-Editor mit Tiptap und eigenen Nodes fuer Protokoll-Strukturen
- Stammdaten fuer Gremien, Rollen und Mitgliedschaften in der Nextcloud-DB
- Rollenbasierte Ableitung von Stimmrechten
- Serverseitiger PDF-Export mit Typst CLI
- Spaetere Live-Collaboration mit Yjs und Hocuspocus
- Spaetere Browser-Live-Preview mit `typst.ts`
- Beschluesse als eigene, indizierbare Entitaeten mit stabiler ID

## Status

Das Projekt befindet sich in Phase 0: Projekt-Setup, Architekturklaerung und
Dokumentation. Es gibt noch keinen lauffaehigen App-Code, kein Composer-
Setup, keine Node-Abhaengigkeiten und keine installierbare Nextcloud-App.

Dieses Repository legt bewusst zuerst die konzeptionelle Basis fest. Die
erste technische Implementierung beginnt mit dem Nextcloud-App-Skelett und
einem Single-User-MVP.

## Quick Links

- [Roadmap](ROADMAP.md)
- [Architektur](ARCHITECTURE.md)
- [Beitragen](CONTRIBUTING.md)
- [Lizenz](LICENSE)

## Lizenz

Dieses Projekt steht unter der GNU Affero General Public License v3.0
(`AGPL-3.0`). Die Lizenz passt zum Ziel des Projekts: Verbesserungen an einer
serverseitig betriebenen, gemeinschaftlich nutzbaren Anwendung sollen wieder
der Gemeinschaft zugutekommen.

