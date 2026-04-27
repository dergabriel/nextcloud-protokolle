# Architektur

`nextcloud-protokolle` ist als Nextcloud-App mit mehreren lose gekoppelten
Schichten geplant. Die Architektur soll die Stärken von Nextcloud nutzen:
Dateien, Berechtigungen, Sharing, Nutzerverwaltung und App-Framework bleiben
die Basis. Der Protokoll-Editor ergänzt diese Basis um eine
domänenspezifische Schreib- und Export-Erfahrung für studentische Gremien.

## Komponenten-Übersicht

Die geplante Architektur besteht aus vier lose gekoppelten Schichten:

1. **Nextcloud-App-Schicht**

   Diese Schicht integriert sich in Nextcloud Files, stellt Controller,
   Services, Datenbank-Migrationen und App-Routen bereit und prüft
   Berechtigungen über die bestehenden Nextcloud-Mechanismen. Sie ist die
   verbindende Schicht zwischen Dateiablage, Stammdaten, Export und UI.

2. **Editor-Schicht**

   Der Editor wird als Vue-3-Anwendung mit Tiptap geplant. Er arbeitet mit
   semantischen Blöcken statt nur mit formatiertem Fließtext. Ein
   Tagesordnungspunkt, eine Abstimmung oder ein Beschluss sind dadurch nicht
   bloß optische Abschnitte, sondern strukturierte Inhalte mit eigener
   Bedeutung.

3. **Collaboration-Schicht**

   Für Live-Collaboration ist ein Hocuspocus-Server mit Yjs vorgesehen. Diese
   Schicht ist bewusst separat, damit der Single-User-MVP ohne WebSocket-
   Infrastruktur funktionieren kann. Später vermittelt eine Auth-Bridge
   zwischen Nextcloud-Session und Collaboration-Server.

4. **Rendering- und Export-Schicht**

   PDF-Ausgaben werden final serverseitig mit Typst CLI erzeugt. Für schnelle
   Vorschauen im Browser ist später `typst.ts` geplant. Beide Wege sollen aus
   demselben strukturierten Protokollmodell rendern, aber unterschiedliche
   Zuverlässigkeits- und Latenzanforderungen bedienen.

## Datenmodell

Das Datenmodell trennt Stammdaten, Sitzungsinhalte und exportierbare
Beschlussdaten.

**Gremium** beschreibt eine organisatorische Einheit wie AStA, StuPa, FSK
oder einen Fachschaftsrat. Ein Gremium besitzt Namen, optionale Metadaten und
eine Menge von Rollen und Mitgliedschaften.

**Person** beschreibt eine natürliche Person. Primär wird eine Person mit
einem Nextcloud-User verknüpft. Für spätere Ausbaustufen ist ein
`extern`-Flag vorgesehen, damit auch Gäste, beratende Mitglieder oder
Personen ohne Nextcloud-Account auftauchen können.

**Rolle** beschreibt eine Funktion innerhalb eines Gremiums, zum Beispiel
Mitglied, Vorsitz, Gast, Protokoll oder beratendes Mitglied. Rollen tragen ein
Stimmrecht-Flag. Dadurch wird Stimmrecht nicht direkt an einzelne Personen
gehängt, sondern an die Rolle, die eine Person in einem Gremium innehat.

**Mitgliedschaft** verbindet Person, Rolle und Gremium über einen Zeitraum.
So lässt sich abbilden, dass eine Person in einem Semester stimmberechtigtes
Mitglied ist, später aber nur noch beratend teilnimmt oder aus dem Gremium
ausscheidet.

**Sitzung** lebt inhaltlich in einer `.protokoll`-Datei. Die Datei ist die
primäre Quelle für Tagesordnung, Mitschrift, Abstimmungen und Beschlüsse.
Die Datenbank kann Sitzungen indizieren, zum Beispiel für Listenansichten,
Suche, Beschlussverweise oder Exporte.

**Sitzungsblock** ist ein strukturierter Abschnitt innerhalb einer Sitzung.
Geplante Blocktypen sind TOP, Bullet, Abstimmung, Beschluss und Anwesenheit.
Der Editor darf diese Blöcke komfortabel bearbeitbar machen, während das
Dateiformat die semantische Struktur erhält.

**Beschluss** ist eine eigene Entität mit stabiler ID. Ein Beschluss entsteht
aus einem Beschlussblock, soll aber später auch unabhängig indexiert und
über eine REST-API abgefragt werden können.

**Abstimmung** beschreibt eine strukturierte Entscheidungssituation mit
Ja-/Nein-/Enthaltungswerten, Stimmrechtskontext und optionalem Bezug zu einem
Beschluss.

## Datenfluss A: User öffnet Protokoll

1. Ein*e Nutzer*in öffnet in Nextcloud Files eine `.protokoll`-Datei.
2. Die Nextcloud-App prüft die Dateiberechtigung über Nextcloud.
3. Die App liest die JSON-Datei aus dem Storage.
4. Das Dokument wird validiert und in ein Editor-Modell überführt.
5. Der Vue/Tiptap-Editor rendert die semantischen Blöcke.
6. Beim Speichern wird das Editor-Modell wieder als `.protokoll`-JSON
   geschrieben.

Im MVP ist dieser Datenfluss single-user-fähig. Live-Collaboration kommt erst
in einer späteren Phase hinzu.

## Datenfluss B: PDF wird exportiert

1. Die Nutzerin oder der Nutzer startet den PDF-Export aus dem Editor.
2. Die Nextcloud-App liest die aktuelle `.protokoll`-Datei.
3. Stammdaten wie Gremium, Rollen, Anwesenheit und Stimmrechte werden aus der
   Datenbank ergänzt.
4. Ein Rendering-Service erzeugt aus Protokolldaten und Typst-Template ein
   Typst-Dokument.
5. Der Server ruft Typst CLI auf.
6. Das erzeugte PDF wird als Download angeboten oder neben dem Protokoll in
   Nextcloud Files abgelegt.

Der finale Export läuft serverseitig, weil dort Fonts, Versionen und
Reproduzierbarkeit besser kontrollierbar sind als in einem Browser.

## Datenfluss C: Zwei User editieren live parallel

1. Zwei berechtigte Nutzer*innen öffnen dieselbe `.protokoll`-Datei.
2. Die Nextcloud-App prüft für beide die Berechtigung und stellt ein
   Collaboration-Token oder eine vergleichbare Session-Brücke bereit.
3. Der Editor verbindet sich mit dem Hocuspocus-Server.
4. Yjs synchronisiert Änderungen in Echtzeit zwischen den Clients.
5. Awareness-Daten wie Cursor, Name und aktuelle Auswahl werden verteilt.
6. In definierten Intervallen oder bei stabilen Zustandswechseln wird der
   Yjs-Zustand zurück in das `.protokoll`-Format persistiert.

Die genaue Persistenzstrategie wird in Phase 2 festgelegt. Wichtig ist, dass
Nextcloud-Berechtigungen weiterhin die Autorität für Zugriff bleiben.

## Design-Entscheidungen

### Warum datei-basiert statt DB-basiert?

Protokolle sind Dokumente und sollen sich in Nextcloud wie Dokumente
verhalten: Sie liegen in Ordnern, können geteilt, verschoben, versioniert,
gesichert und über bestehende Berechtigungen kontrolliert werden. Eine rein
DB-basierte Ablage würde viele dieser Stärken umgehen und eine zweite,
app-spezifische Dokumentenwelt erzeugen.

Die Datenbank bleibt trotzdem wichtig. Sie speichert Stammdaten, Rollen,
Mitgliedschaften und später Indizes für Sitzungen und Beschlüsse. Die
Quelle des Protokollinhalts bleibt aber die `.protokoll`-Datei.

### Warum Hybrid-Rendering?

Live-Arbeit braucht schnelle Rückmeldung. Dafür ist eine Browser-Preview
mit `typst.ts` attraktiv, weil sie ohne Server-Roundtrip ein visuelles Gefühl
für das spätere PDF geben kann.

Der finale Export hat andere Anforderungen: stabile Fonts, reproduzierbare
Versionen, saubere Fehlerbehandlung und serverseitige Kontrolle. Deshalb wird
das verbindliche PDF mit Typst CLI auf dem Server erzeugt. Browser-Preview und
Server-Export dürfen sich ergänzen, aber der Server-Export ist die
maßgebliche Ausgabe.

### Warum AGPL?

Die App ist für gemeinschaftlich betriebene Infrastruktur gedacht. Gerade
bei serverseitiger Software kann es passieren, dass Verbesserungen zwar
genutzt, aber nicht zurückgegeben werden. Die AGPL-3.0 stellt sicher, dass
auch bei Netzwerkbetrieb die Freiheit der Nutzer*innen und die Rückgabe von
Verbesserungen ernst genommen werden.

Das passt zum Umfeld studentischer Selbstverwaltung: Viele Gruppen haben
wenig Zeit, aber ähnliche Probleme. Eine starke Copyleft-Lizenz hilft, dass
gemeinsame Arbeit nicht in isolierten Einzellösungen verschwindet.

### Warum rollenbasierte Stimmrechte?

In Gremien hängt Stimmrecht selten dauerhaft an einer Person allein. Es
ergibt sich aus Funktion, Wahlperiode, Gremium und Zeitraum. Deshalb modelliert
das Projekt Stimmrechte über Rollen in Mitgliedschaften.

Das macht Wechsel nachvollziehbarer: Wenn eine Person eine andere Rolle
bekommt oder eine Mitgliedschaft endet, ändert sich das Stimmrecht über die
Struktur, nicht über einzelne Sonderregeln. Gleichzeitig bleibt abbildbar,
welche Personen in einer konkreten Sitzung stimmberechtigt waren.

### Warum Versionsverwaltung über Nextcloud Files?

Das Projekt nutzt die eingebaute Dateiversionierung von Nextcloud und
implementiert keine eigene Versionierungsschicht für `.protokoll`-Dateien.

Diese Entscheidung reduziert Code, Wartungsaufwand und Konfliktpotenzial. Sie
passt außerdem zum Files-Workflow, den Nutzer*innen bereits kennen: Wer ein
Protokoll öffnet, teilt oder wiederherstellt, bleibt in den gewohnten
Nextcloud-Mechanismen.
