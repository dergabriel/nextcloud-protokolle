# Lokale Nextcloud-Entwicklungsumgebung

Diese Umgebung ist nur für lokale Entwicklung gedacht. Sie ist nicht für
Produktion geeignet.

## Starten

```bash
docker compose up -d
```

Die Nextcloud ist anschließend unter http://localhost:8080 erreichbar.

## Erstinstallation und Login

Beim ersten Aufruf von http://localhost:8080 führt Nextcloud durch das
Admin-Setup. Für lokale Entwicklung kann ein beliebiger Admin-Account
angelegt werden.

SQLite reicht für diese Entwicklungsumgebung aus. Es wird kein separater
Datenbank-Container gestartet.

## App aktivieren

Nach der Erstinstallation:

1. Als Admin in Nextcloud anmelden.
2. In den Admin-Bereich für Apps wechseln.
3. Die App **Protokolle** unter den lokalen oder deaktivierten Apps suchen.
4. Die App aktivieren.
5. Danach sollte **Protokolle** im Hauptmenü erscheinen.

Das lokale Verzeichnis `nextcloud-app/` wird in den Container nach
`/var/www/html/custom_apps/protokolle/` eingebunden.

## Stoppen

```bash
docker compose down
```

## Stoppen und Daten löschen

```bash
docker compose down -v
```

Damit wird auch das lokale Docker-Volume mit den Nextcloud-Daten entfernt.
