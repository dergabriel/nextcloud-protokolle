# Architektur

`nextcloud-protokolle` ist als Nextcloud-App mit mehreren lose gekoppelten
Schichten geplant. Die Architektur soll die Staerken von Nextcloud nutzen:
Dateien, Berechtigungen, Sharing, Nutzerverwaltung und App-Framework bleiben
die Basis. Der Protokoll-Editor ergaenzt diese Basis um eine
domaenenspezifische Schreib- und Export-Erfahrung fuer studentische Gremien.

## Komponenten-Uebersicht

Die geplante Architektur besteht aus vier lose gekoppelten Schichten:

1. **Nextcloud-App-Schicht**

   Diese Schicht integriert sich in Nextcloud Files, stellt Controller,
   Services, Datenbank-Migrationen und App-Routen bereit und prueft
   Berechtigungen ueber die bestehenden Nextcloud-Mechanismen. Sie ist die
   verbindende Schicht zwischen Dateiablage, Stammdaten, Export und UI.

2. **Editor-Schicht**

   Der Editor wird als Vue-3-Anwendung mit Tiptap geplant. Er arbeitet mit
   semantischen Bloecken statt nur mit formatiertem Fliesstext. Ein
   Tagesordnungspunkt, eine Abstimmung oder ein Beschluss sind dadurch nicht
   bloss optische Abschnitte, sondern strukturierte Inhalte mit eigener
   Bedeutung.

3. **Collaboration-Schicht**

   Fuer Live-Collaboration ist ein Hocuspocus-Server mit Yjs vorgesehen. Diese
   Schicht ist bewusst separat, damit der Single-User-MVP ohne WebSocket-
   Infrastruktur funktionieren kann. Spaeter vermittelt eine Auth-Bridge
   zwischen Nextcloud-Session und Collaboration-Server.

4. **Rendering- und Export-Schicht**

   PDF-Ausgaben werden final serverseitig mit Typst CLI erzeugt. Fuer schnelle
   Vorschauen im Browser ist spaeter `typst.ts` geplant. Beide Wege sollen aus
   demselben strukturierten Protokollmodell rendern, aber unterschiedliche
   Zuverlaessigkeits- und Latenzanforderungen bedienen.

## Datenmodell

Das Datenmodell trennt Stammdaten, Sitzungsinhalte und exportierbare
Beschlussdaten.

**Gremium** beschreibt eine organisatorische Einheit wie AStA, StuPa, FSK
oder einen Fachschaftsrat. Ein Gremium besitzt Namen, optionale Metadaten und
eine Menge von Rollen und Mitgliedschaften.

**Person** beschreibt eine natuerliche Person. Primaer wird eine Person mit
einem Nextcloud-User verknuepft. Fuer spaetere Ausbaustufen ist ein
`extern`-Flag vorgesehen, damit auch Gaeste, beratende Mitglieder oder
Personen ohne Nextcloud-Account auftauchen koennen.

**Rolle** beschreibt eine Funktion innerhalb eines Gremiums, zum Beispiel
Mitglied, Vorsitz, Gast, Protokoll oder beratendes Mitglied. Rollen tragen ein
Stimmrecht-Flag. Dadurch wird Stimmrecht nicht direkt an einzelne Personen
gehaengt, sondern an die Rolle, die eine Person in einem Gremium innehat.

**Mitgliedschaft** verbindet Person, Rolle und Gremium ueber einen Zeitraum.
So laesst sich abbilden, dass eine Person in einem Semester stimmberechtigtes
Mitglied ist, spaeter aber nur noch beratend teilnimmt oder aus dem Gremium
ausscheidet.

**Sitzung** lebt inhaltlich in einer `.protokoll`-Datei. Die Datei ist die
primaere Quelle fuer Tagesordnung, Mitschrift, Abstimmungen und Beschluesse.
Die Datenbank kann Sitzungen indizieren, zum Beispiel fuer Listenansichten,
Suche, Beschlussverweise oder Exporte.

**Sitzungsblock** ist ein strukturierter Abschnitt innerhalb einer Sitzung.
Geplante Blocktypen sind TOP, Bullet, Abstimmung, Beschluss und Anwesenheit.
Der Editor darf diese Bloecke komfortabel bearbeitbar machen, waehrend das
Dateiformat die semantische Struktur erhaelt.

**Beschluss** ist eine eigene Entitaet mit stabiler ID. Ein Beschluss entsteht
aus einem Beschlussblock, soll aber spaeter auch unabhaengig indexiert und
ueber eine REST-API abgefragt werden koennen.

## Datenfluss A: User oeffnet Protokoll

1. Ein*e Nutzer*in oeffnet in Nextcloud Files eine `.protokoll`-Datei.
2. Die Nextcloud-App prueft die Dateiberechtigung ueber Nextcloud.
3. Die App liest die JSON-Datei aus dem Storage.
4. Das Dokument wird validiert und in ein Editor-Modell ueberfuehrt.
5. Der Vue/Tiptap-Editor rendert die semantischen Bloecke.
6. Beim Speichern wird das Editor-Modell wieder als `.protokoll`-JSON
   geschrieben.

Im MVP ist dieser Datenfluss single-user-faehig. Live-Collaboration kommt erst
in einer spaeteren Phase hinzu.

## Datenfluss B: PDF wird exportiert

1. Die Nutzerin oder der Nutzer startet den PDF-Export aus dem Editor.
2. Die Nextcloud-App liest die aktuelle `.protokoll`-Datei.
3. Stammdaten wie Gremium, Rollen, Anwesenheit und Stimmrechte werden aus der
   Datenbank ergaenzt.
4. Ein Rendering-Service erzeugt aus Protokolldaten und Typst-Template ein
   Typst-Dokument.
5. Der Server ruft Typst CLI auf.
6. Das erzeugte PDF wird als Download angeboten oder neben dem Protokoll in
   Nextcloud Files abgelegt.

Der finale Export laeuft serverseitig, weil dort Fonts, Versionen und
Reproduzierbarkeit besser kontrollierbar sind als in einem Browser.

## Datenfluss C: Zwei User editieren live parallel

1. Zwei berechtigte Nutzer*innen oeffnen dieselbe `.protokoll`-Datei.
2. Die Nextcloud-App prueft fuer beide die Berechtigung und stellt ein
   Collaboration-Token oder eine vergleichbare Session-Bruecke bereit.
3. Der Editor verbindet sich mit dem Hocuspocus-Server.
4. Yjs synchronisiert Aenderungen in Echtzeit zwischen den Clients.
5. Awareness-Daten wie Cursor, Name und aktuelle Auswahl werden verteilt.
6. In definierten Intervallen oder bei stabilen Zustandswechseln wird der
   Yjs-Zustand zurueck in das `.protokoll`-Format persistiert.

Die genaue Persistenzstrategie wird in Phase 2 festgelegt. Wichtig ist, dass
Nextcloud-Berechtigungen weiterhin die Autoritaet fuer Zugriff bleiben.

## Design-Entscheidungen

### Warum datei-basiert statt DB-basiert?

Protokolle sind Dokumente und sollen sich in Nextcloud wie Dokumente
verhalten: Sie liegen in Ordnern, koennen geteilt, verschoben, versioniert,
gesichert und ueber bestehende Berechtigungen kontrolliert werden. Eine rein
DB-basierte Ablage wuerde viele dieser Staerken umgehen und eine zweite,
app-spezifische Dokumentenwelt erzeugen.

Die Datenbank bleibt trotzdem wichtig. Sie speichert Stammdaten, Rollen,
Mitgliedschaften und spaeter Indizes fuer Sitzungen und Beschluesse. Die
Quelle des Protokollinhalts bleibt aber die `.protokoll`-Datei.

### Warum Hybrid-Rendering?

Live-Arbeit braucht schnelle Rueckmeldung. Dafuer ist eine Browser-Preview
mit `typst.ts` attraktiv, weil sie ohne Server-Roundtrip ein visuelles Gefuehl
fuer das spaetere PDF geben kann.

Der finale Export hat andere Anforderungen: stabile Fonts, reproduzierbare
Versionen, saubere Fehlerbehandlung und serverseitige Kontrolle. Deshalb wird
das verbindliche PDF mit Typst CLI auf dem Server erzeugt. Browser-Preview und
Server-Export duerfen sich ergaenzen, aber der Server-Export ist die
massgebliche Ausgabe.

### Warum AGPL?

Die App ist fuer gemeinschaftlich betriebene Infrastruktur gedacht. Gerade
bei serverseitiger Software kann es passieren, dass Verbesserungen zwar
genutzt, aber nicht zurueckgegeben werden. Die AGPL-3.0 stellt sicher, dass
auch bei Netzwerkbetrieb die Freiheit der Nutzer*innen und die Rueckgabe von
Verbesserungen ernst genommen werden.

Das passt zum Umfeld studentischer Selbstverwaltung: Viele Gruppen haben
wenig Zeit, aber aehnliche Probleme. Eine starke Copyleft-Lizenz hilft, dass
gemeinsame Arbeit nicht in isolierten Einzelloesungen verschwindet.

### Warum rollenbasierte Stimmrechte?

In Gremien haengt Stimmrecht selten dauerhaft an einer Person allein. Es
ergibt sich aus Funktion, Wahlperiode, Gremium und Zeitraum. Deshalb modelliert
das Projekt Stimmrechte ueber Rollen in Mitgliedschaften.

Das macht Wechsel nachvollziehbarer: Wenn eine Person eine andere Rolle
bekommt oder eine Mitgliedschaft endet, aendert sich das Stimmrecht ueber die
Struktur, nicht ueber einzelne Sonderregeln. Gleichzeitig bleibt abbildbar,
welche Personen in einer konkreten Sitzung stimmberechtigt waren.
