# Foreningsbrukere

## Vedlikehold av brukere

Se [ldap-toolbox](../ldap-toolbox/README.md) for grunnleggende oppsett
for å kunne kjøre SSH-kommandoer osv.

Se også https://foreningenbs.no/confluence/display/IT/LDAP for
mer informasjon rundt hvordan systemet fungerer.

### Opprette brukere (samt oppdatere attributter på eksisterende brukere)

1. Logg inn på fcos-3:

   ```bash
   ssh -A root@fcos-3.nrec.foreningenbs.no
   ```

   fcos-3 er samme maskin som vi lagrer filer for brukerregistrering (se web-1).

1. Gå til mappen hvor vi har synket opp dette repoet:

   ```bash
   cd /var/mnt/data/drift/ldap-toolbox
   ```

   Oppdater gjerne repoet (derfor `-A` i ssh-tilkoblingen):

   ```bash
   git pull
   ```

1. Kjør på Docker-containeren som inneholder toolingen vi trenger:

   ```bash
   ./run.sh
   ```

1. Opprett bruker som tidligere som beskrevet i e-post ved registrering:

   ```bash
   /fbs/drift/nybruker/process.sh <id>.sh
   ```

### Endre grupper

1. Følg oppskriften over, men ikke kjør scriptet for å opprette/oppdatere bruker

1. Kall på ldap-scriptene som f.eks.:

   1. `ldapaddusertogroup henrste beboer`

   Se https://foreningenbs.no/confluence/display/FBS/LDAP for mer detaljer.

### Feilsøking

OBS! Vi har en bug som gjør at navn med æøå mister disse bokstavene. Man kan
redigere variablene i `<id>.sh` manuelt hvis man vil fikse dette før man
oppretter/oppdaterer en bruker.

## Oppdatering av beboerlisten (postlisten)

(Ikke oppdatert per 2023.)

### Last opp ny Excel-versjon

Postlisten lastes opp til `/fbs/drift/users/` på foreningenbs.no. Dette kan
gjøres ved å gå til https://foreningenbs.no/foreningen/IT-gruppa-drift/users
og laste opp direkte der (det er samme mappe).

Postlisten må skaffes fra kontoret og skal være i Excel-format (xls).
Sørg for at navnet blir `postliste.xls` når det er lastet opp (evt. endre på
kommandoen nedenfor).

### Konverter til json-format

SSH til foreningenbs.no som brukeren din.

```bash
cd /fbs/drift/users
./postliste.py postliste.xls beboerliste.json
```

Listen er nå oppdatert, og romlisten og postlisten på nett er også oppdatert:

* https://foreningenbs.no/henrik/postliste/
* https://foreningenbs.no/henrik/romliste/

Datoen som står oppført på nettsiden hentes fra Excel-dokumentet.

## Sjekke brukerlisten på foreningenbs.no mot beboerlisten

(Ikke oppdatert per 2023.)

```bash
cd /fbs/drift/users
./check_users.py beboerliste.json
```

Du får nå en rapport over hvem som mangler, samt hvem som ikke lenger er i
postlisten og har flyttet ut/permisjon.

## Sjekke skaplisten mot beboerlisten

(Ikke oppdatert per 2023.)

```bash
cd /fbs/drift/users
./check_skapliste.py beboerliste.json
```

Du får nå en rapport over skap som tilhører folk som ikke lenger er listet
opp i postlisten.

Listen over hvem som har hvilke skap hentes fra wikisiden foreningenbs.no/skap
