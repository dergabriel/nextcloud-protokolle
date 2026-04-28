# Lokale Nextcloud-Entwicklungsumgebung

Diese Umgebung ist nur für lokale Entwicklung gedacht. Sie ist nicht für
Produktion geeignet.

## Starten

```bash
docker compose up -d
```

Die Nextcloud ist anschließend unter http://localhost:8080 erreichbar.

## Erstinstallation und Login

Beim ersten Start legt die Compose-Umgebung automatisch den lokalen
Admin-Account `admin` mit dem Passwort `admin` an.

SQLite reicht für diese Entwicklungsumgebung aus. Es wird kein separater
Datenbank-Container gestartet.

## App aktivieren

Die App wird in der lokalen Entwicklungsumgebung bevorzugt per CLI aktiviert:

```bash
docker compose exec --user www-data nextcloud php occ app:enable protokolle
```

Dieser Weg ist robuster als die Aktivierung über die Apps-Settings-Seite, weil
die Settings-Seite in frischen Dev-Umgebungen während der Initialisierung noch
Fehler zeigen kann.

Ist die App aktiv, ist sie direkt erreichbar unter:

http://localhost:8080/index.php/apps/protokolle/

Das lokale Verzeichnis `nextcloud-app/` wird in den Container nach
`/var/www/html/custom_apps/protokolle/` eingebunden. Das übergeordnete
`custom_apps`-Verzeichnis liegt auf einem schreibbaren Docker-Volume, damit
Nextcloud seine App-Prüfungen und lokalen Dev-Abläufe ausführen kann.

## Wenn die Dev-Umgebung zickt

Für einen vollständigen Reset:

```bash
docker compose down -v && docker compose up -d
```

Danach sind Erstinitialisierung und App-Aktivierung erneut nötig. Die lokalen
Default-Credentials sind `admin` / `admin`.

## Stoppen

```bash
docker compose down
```

## Stoppen und Daten löschen

```bash
docker compose down -v
```

Damit wird auch das lokale Docker-Volume mit den Nextcloud-Daten entfernt.
