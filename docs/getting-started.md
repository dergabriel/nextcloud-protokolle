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

Beim ersten Start legt die Compose-Umgebung den lokalen Admin-Account `admin`
mit dem Passwort `admin` an. SQLite reicht für diese Entwicklungsumgebung aus.

## 5. App aktivieren

Die App wird in der lokalen Entwicklungsumgebung bevorzugt per CLI aktiviert:

```bash
docker compose exec --user www-data nextcloud php occ app:enable protokolle
```

Bei einer frischen Dev-Umgebung kann die Apps-Settings-Seite zunächst Fehler
zeigen, bis Nextcloud vollständig initialisiert ist. Die Aktivierung über die
CLI ist robuster und wird für lokale Entwicklung als Standardweg empfohlen.

Ist die App einmal aktiv, ist sie direkt erreichbar unter:

http://localhost:8080/index.php/apps/protokolle/

## 6. App öffnen

Nach der Aktivierung sollte **Protokolle** im Nextcloud-Hauptmenü erscheinen.
Die Seite zeigt aktuell nur die Hello-World-Seite mit der Überschrift
**Protokolle**.

## 7. Tests und Validierung

Hello-Endpoint prüfen:

```bash
curl -u admin:admin http://localhost:8080/index.php/apps/protokolle/hello
```

Erwartete Antwort:

```json
{"status":"ok","message":"Hallo aus Protokolle"}
```

Healthcheck-Endpoint prüfen:

```bash
curl -u admin:admin http://localhost:8080/index.php/apps/protokolle/healthcheck
```

Erwartet wird eine JSON-Antwort mit `"app":"protokolle"` und
`"databaseConnection":"ok"`.

## 8. Datenbank-Schema

Die erste Stammdaten-Migration legt vier Tabellen an:

- `oc_protokolle_gremium` für organisatorische Einheiten wie AStA, StuPa
  oder Fachschaftsräte
- `oc_protokolle_rolle` für Rollen innerhalb eines Gremiums inklusive
  Standard-Stimmrecht
- `oc_protokolle_person` für Nextcloud-User-Personen und externe Personen
- `oc_protokolle_mitgliedschaft` für die Zuordnung Person × Rolle

Beim Aktivieren der App führt Nextcloud offene Migrationen automatisch aus.
Für Reset-Szenarien ist der robuste Weg in der lokalen Dev-Umgebung:

```bash
cd dev-environment
docker compose exec --user www-data nextcloud php occ app:disable protokolle
docker compose exec --user www-data nextcloud php occ app:enable protokolle
```

Ältere Nextcloud-Versionen dokumentieren teils zusätzlich
`occ migrations:execute protokolle 000001Date20260428000001`. Die aktuell
verwendete Nextcloud-32-Dev-Instanz stellt diesen Befehl nicht bereit.

Das Schema kann in der lokalen SQLite-Datenbank geprüft werden:

```bash
cd dev-environment
docker compose exec nextcloud sqlite3 /var/www/html/data/nextcloud.db ".schema oc_protokolle_*"
```

Falls `sqlite3` im offiziellen Nextcloud-Container nicht installiert ist,
funktioniert als Fallback:

```bash
cd dev-environment
docker compose exec --user www-data nextcloud php -r '$db=new PDO("sqlite:/var/www/html/data/nextcloud.db"); foreach($db->query("SELECT name, sql FROM sqlite_master WHERE name LIKE \"oc_protokolle_%\" ORDER BY name") as $row){echo "--- ".$row["name"].PHP_EOL.$row["sql"].PHP_EOL;}'
```

## 9. Stammdaten per CLI verwalten

Die Stammdaten können in der Dev-Umgebung ohne UI über `occ` gepflegt werden.
Alle Befehle werden aus `dev-environment/` heraus ausgeführt.

Verfügbare Befehle anzeigen:

```bash
docker compose exec --user www-data nextcloud php occ list protokolle
```

Gremien:

```bash
docker compose exec --user www-data nextcloud php occ protokolle:gremium:create --name "AStA" --kuerzel "AStA"
docker compose exec --user www-data nextcloud php occ protokolle:gremium:list
docker compose exec --user www-data nextcloud php occ protokolle:gremium:delete 1
```

Rollen:

```bash
docker compose exec --user www-data nextcloud php occ protokolle:rolle:create --gremium 1 --name "AStA-Referent:in"
docker compose exec --user www-data nextcloud php occ protokolle:rolle:create --gremium 1 --name "Gast" --no-stimmberechtigt
docker compose exec --user www-data nextcloud php occ protokolle:rolle:list --gremium 1
docker compose exec --user www-data nextcloud php occ protokolle:rolle:delete 1
```

Personen:

```bash
docker compose exec --user www-data nextcloud php occ protokolle:person:create-extern --vorname "Sascha" --nachname "Wellmann"
docker compose exec --user www-data nextcloud php occ protokolle:person:create-from-user --user "admin"
docker compose exec --user www-data nextcloud php occ protokolle:person:list
docker compose exec --user www-data nextcloud php occ protokolle:person:delete 1
```

Mitgliedschaften:

```bash
docker compose exec --user www-data nextcloud php occ protokolle:mitgliedschaft:create --person 1 --rolle 1
docker compose exec --user www-data nextcloud php occ protokolle:mitgliedschaft:create --person 1 --rolle 2 --stimmberechtigt false
docker compose exec --user www-data nextcloud php occ protokolle:mitgliedschaft:list
docker compose exec --user www-data nextcloud php occ protokolle:mitgliedschaft:list --gremium 1
docker compose exec --user www-data nextcloud php occ protokolle:mitgliedschaft:list --person 1
docker compose exec --user www-data nextcloud php occ protokolle:mitgliedschaft:delete 1
```

Durchgehendes Beispiel-Szenario:

```bash
docker compose exec --user www-data nextcloud php occ protokolle:gremium:create --name "AStA" --kuerzel "AStA"
docker compose exec --user www-data nextcloud php occ protokolle:rolle:create --gremium 1 --name "AStA-Referent:in"
docker compose exec --user www-data nextcloud php occ protokolle:person:create-extern --vorname "Sascha" --nachname "Wellmann"
docker compose exec --user www-data nextcloud php occ protokolle:mitgliedschaft:create --person 1 --rolle 1
docker compose exec --user www-data nextcloud php occ protokolle:mitgliedschaft:list
```

Die Listenbefehle geben Tabellen aus. Fehler aus der Service-Schicht, zum
Beispiel doppelte Namen, erscheinen als deutsche Fehlermeldung mit Exit-Code
`1`.

## 10. Tests lokal ausführen

```bash
cd nextcloud-app
composer install
make test-php
```

## 11. Häufige Probleme

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

Danach die App per CLI aktivieren:

```bash
docker compose exec --user www-data nextcloud php occ app:enable protokolle
```

### Apps-Settings-Seite zeigt Fehler

In der Dev-Umgebung ist die CLI-Aktivierung der verbindliche Standardweg. Wenn
die Apps-Settings-Seite direkt nach einem frischen Start noch Fehler zeigt,
zuerst die App per `occ` aktivieren und Nextcloud vollständig initialisieren
lassen.

Bekannte Einschränkung: In der aktuellen lokalen Docker-Dev-Umgebung kann
`/settings/apps` trotz schreibbarem `custom_apps`-Volume weiterhin mit HTTP
500 antworten. Das blockiert den Dev-Workflow nicht; App-Aktivierung per CLI
ist bis zur Klärung der verbindliche Weg.

### Composer-Abhängigkeiten fehlen

Im App-Verzeichnis installieren:

```bash
cd nextcloud-app
composer install
```

### Umgebung vollständig zurücksetzen

```bash
cd dev-environment
docker compose down -v && docker compose up -d
```

Danach sind Erstinstallation und App-Aktivierung erneut nötig. Die lokalen
Default-Credentials sind `admin` / `admin`.
