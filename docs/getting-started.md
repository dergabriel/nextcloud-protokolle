# Getting Started

Diese Anleitung zeigt, wie die lokale Entwicklungsumgebung gestartet und das
Hello-World-Skelett der Nextcloud-App sichtbar gemacht wird.

## 1. Voraussetzungen

Aktuell geprüft mit:

- Docker `29.2.1`
- Docker Compose `v5.1.0`
- Node.js `v25.6.1` (mindestens Version 20)
- npm `11.9.0`
- PHP `8.5.5` (mindestens Version 8.1)
- Composer `2.9.7`
- git `2.50.1`
- GitHub CLI `2.91.0`

## 2. Repo klonen

```bash
git clone https://github.com/dergabriel/nextcloud-protokolle.git
cd nextcloud-protokolle
```

## 3. Dev-Umgebung starten

```bash
cd dev-environment
docker compose up -d
```

## 4. Nextcloud öffnen und Erstinstallation durchführen

Nextcloud läuft lokal unter:

http://localhost:8080

Beim ersten Start führt Nextcloud durch das Admin-Setup. Für die lokale
Entwicklung kann ein beliebiger Admin-Account angelegt werden. SQLite reicht
für diese Entwicklungsumgebung aus.

## 5. App aktivieren

Nach der Erstinstallation:

1. Als Admin anmelden.
2. In den Admin-Bereich für Apps wechseln.
3. Die App **Protokolle** unter den lokalen oder deaktivierten Apps suchen.
4. Die App aktivieren.

## 6. App öffnen

Nach der Aktivierung sollte **Protokolle** im Nextcloud-Hauptmenü erscheinen.
Die Seite zeigt aktuell nur die Hello-World-Seite mit der Überschrift
**Protokolle**.

## 7. Test-Endpoint manuell prüfen

```bash
curl http://localhost:8080/index.php/apps/protokolle/hello
```

Erwartete Antwort:

```json
{"status":"ok","message":"Hallo aus Protokolle"}
```

## 8. Tests lokal ausführen

```bash
cd nextcloud-app
composer install
make test-php
```

## 9. Häufige Probleme

### Port 8080 ist bereits belegt

Eine andere lokale Anwendung nutzt den Port. In
`dev-environment/docker-compose.yml` kann das linke Port-Mapping angepasst
werden, zum Beispiel `"8081:80"`.

### Docker läuft nicht

Docker Desktop oder die lokale Docker-Engine starten und danach erneut
ausführen:

```bash
cd dev-environment
docker compose up -d
```

### App erscheint nicht in Nextcloud

Prüfen, ob das lokale App-Verzeichnis korrekt eingebunden ist:

```bash
docker compose exec nextcloud ls -la /var/www/html/custom_apps/protokolle
```

Dort sollten unter anderem `appinfo/`, `lib/`, `templates/` und `img/`
sichtbar sein.

### Composer-Abhängigkeiten fehlen

Im App-Verzeichnis installieren:

```bash
cd nextcloud-app
composer install
```

### Umgebung vollständig zurücksetzen

```bash
cd dev-environment
docker compose down -v
```

Danach kann die Erstinstallation neu durchgeführt werden.
