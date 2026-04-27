# Verwendete Open-Source-Projekte

Diese Liste sammelt die Projekte, an denen sich `nextcloud-protokolle`
technisch orientiert oder die als direkte Bausteine vorgesehen sind. Sie ist
kein finaler Dependency-Lock, sondern eine kuratierte Entscheidungsgrundlage
für Phase 1.

## Direkte Bausteine

### nextcloud/app_template

- **GitHub:** https://github.com/nextcloud/app_template
- **Lizenz:** AGPL-3.0
- **Rolle:** Startpunkt für das Nextcloud-App-Skelett

Das App Template bildet den naheliegenden Einstieg in eine saubere
Nextcloud-App-Struktur. Es liefert etablierte Muster für App-Metadaten,
Controller, Frontend-Build und Tests, ohne dass wir diese Basis selbst
zusammensuchen müssen.

### ueberdosis/tiptap

- **GitHub:** https://github.com/ueberdosis/tiptap
- **Lizenz:** MIT
- **Rolle:** Editor-Framework für semantische Protokollblöcke

Tiptap ist ein erweiterbares Headless-Editor-Framework auf ProseMirror-Basis.
Für uns ist besonders wichtig, dass eigene Extensions möglich sind: TOPs,
Abstimmungen, Beschlüsse und Anwesenheitslisten können als domänenspezifische
Blöcke umgesetzt werden.

### ueberdosis/hocuspocus

- **GitHub:** https://github.com/ueberdosis/hocuspocus
- **Lizenz:** MIT
- **Rolle:** Yjs-WebSocket-Server für Live-Collaboration

Hocuspocus ist ein etablierter Backend-Baustein für kollaborative Yjs-
Dokumente. Da Nextcloud Text ebenfalls in diese Richtung arbeitet, passt
Hocuspocus gut zu unserer geplanten Architektur.

### yjs/yjs

- **GitHub:** https://github.com/yjs/yjs
- **Lizenz:** MIT
- **Rolle:** CRDT-Grundlage für konfliktarme Echtzeitbearbeitung

Yjs stellt die eigentliche Datenstruktur für kollaboratives Bearbeiten bereit.
Es ist breit eingesetzt, gut dokumentiert und mit Tiptap/Hocuspocus kompatibel.

### Myriad-Dreamin/typst.ts

- **GitHub:** https://github.com/Myriad-Dreamin/typst.ts
- **Lizenz:** Apache-2.0
- **Rolle:** Browser-Live-Preview für Typst-Dokumente

`typst.ts` bringt Typst über WebAssembly in den Browser. Für Phase 2 ist das
der wichtigste Baustein, um eine schnelle Vorschau zu ermöglichen, ohne den
serverseitigen finalen PDF-Export zu ersetzen.

### Typst CLI

- **GitHub:** https://github.com/typst/typst
- **Website:** https://typst.app/
- **Lizenz:** Apache-2.0
- **Rolle:** Serverseitiger PDF-Export

Typst CLI ist der verbindliche Renderer für finale PDFs. Wir priorisieren
Layout-Qualität und reproduzierbare Exporte; deshalb ist Typst von Anfang an
Teil der Kernarchitektur.

## Architektur-Vorbilder

### nextcloud/text

- **GitHub:** https://github.com/nextcloud/text
- **Lizenz:** AGPL-3.0
- **Rolle:** Hauptreferenz für Files-Integration, Tiptap-Setup,
  Yjs-Auth-Bridge und Markdown-Persistierung

Nextcloud Text ist eine ausgereifte kollaborative Markdown-Editor-App und
nutzt zentrale Bausteine, die auch wir brauchen. Wir forken Text nicht, lesen
aber den Code sehr genau und übernehmen passende Patterns in eine eigene App.

### dergabriel/asta-protokolle

- **GitHub:** https://github.com/dergabriel/asta-protokolle
- **Lizenz:** noch zu klären, bevor Template-Code direkt übernommen wird
- **Rolle:** Bestehendes Typst-Template als Layout-Basis

Das bestehende Protokoll-Template ist fachlich nah am Zielzustand und kann als
Layout-Ausgangspunkt dienen. Vor direkter Übernahme in dieses AGPL-Projekt
muss die Lizenz- und Rechtefrage explizit geklärt werden.

### Mapaor/typst-online-editor

- **GitHub:** https://github.com/Mapaor/typst-online-editor
- **Lizenz:** MIT
- **Rolle:** Referenz für ein Browser-Setup mit `typst.ts`

Das Projekt zeigt kompakt, wie Typst-Kompilierung im Browser über WebAssembly
aufgebaut werden kann. Wir nutzen es nicht als Produktbasis, aber als
praktische Referenz für die spätere Live-Preview.

## Integrationen, die wir nicht selbst bauen

### nextcloud/user_oidc

- **GitHub:** https://github.com/nextcloud/user_oidc
- **Lizenz:** AGPL-3.0
- **Rolle:** OIDC-Anbindung zwischen Nextcloud und authentik

Die App übernimmt die OIDC-Brücke, Nutzer-Provisionierung und Zuordnung in
Nextcloud. `nextcloud-protokolle` arbeitet dadurch auf normalen Nextcloud-
Usern und muss keinen eigenen authentik-Client pflegen.

### goauthentik/authentik

- **GitHub:** https://github.com/goauthentik/authentik
- **Website:** https://goauthentik.io/
- **Lizenz:** MIT
- **Rolle:** Identity Provider der Studierendenschaft

authentik ist für die hda der realistische Identity Provider. Die direkte
Anbindung läuft jedoch nicht über unsere App, sondern über `user_oidc` in
Nextcloud.

## Was wir bewusst nicht nutzen

### Pandoc als PDF-Generator

Pandoc ist stark für Formatkonvertierung, aber nicht unser Zielrenderer für
hochwertige Protokoll-PDFs. Wir entscheiden uns bewusst für Typst, weil
Layout-Qualität, Templates und reproduzierbarer Satz Kernanforderungen sind.

### BlockNote

BlockNote ist ein gutes Open-Source-Projekt für Notion-artige Editoren. Für
dieses Projekt ist es aber zu generisch und zu stark auf allgemeine Block-
Dokumente ausgerichtet. Wir brauchen domänenspezifische Protokollblöcke und
wollen näher an Nextcloud Text/Tiptap bleiben.

### Tiptap Pro / Tiptap Cloud

Tiptap Pro und Tiptap Cloud werden bewusst nicht eingeplant. Das Projekt soll
vollständig auf selbst betreibbaren Open-Source-Bausteinen beruhen und keine
paid oder cloud-only Kernabhängigkeiten einführen.
