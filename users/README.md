# Oppdatering av beboerlisten (postlisten)

## Last opp ny Excel-versjon

Postlisten lastes opp til `/fbs/drift/users/` på foreningenbs.no. Dette kan
gjøres ved å gå til https://foreningenbs.no/foreningen/IT-gruppa-drift/users
og laste opp direkte der (det er samme mappe).

Postlisten må skaffes fra kontoret og skal være i Excel-format (xls).
Sørg for at navnet blir `postliste.xls` når det er lastet opp (evt. endre på
kommandoen nedenfor).

## Konverter til json-format

SSH til foreningenbs.no som brukeren din.

```bash
cd /fbs/drift/users
./postliste.py postliste.xls beboerliste.json
```

Listen er nå oppdatert, og romlisten og postlisten på nett er også oppdatert:

 * https://foreningenbs.no/henrik/postliste/
 * https://foreningenbs.no/henrik/romliste/

Datoen som står oppført på nettsiden hentes fra Excel-dokumentet.

# Sjekke brukerlisten på foreningenbs.no mot beboerlisten

```bash
cd /fbs/drift/users
./check_users.py beboerliste.json
```

Du får nå en rapport over hvem som mangler, samt hvem som ikke lenger er i
postlisten og har flyttet ut/permisjon.

# Sjekke skaplisten mot beboerlisten

```bash
cd /fbs/drift/users
./check_skapliste.py beboerliste.json
```

Du får nå en rapport over skap som tilhører folk som ikke lenger er listet
opp i postlisten.

Listen over hvem som har hvilke skap hentes fra wikisiden foreningenbs.no/skap
