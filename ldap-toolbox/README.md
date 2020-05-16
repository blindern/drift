# ldap-toolbox

Dette brukes for å legge til nye foreningsbrukere på bakgrunn av
e-poster vi får ra registrering.

Dette brukes på coreos-2 som er samme maskin som vi p.t. lagrer
filer for brukerregistrering (web-1).

Kjør dette slik:

```bash
./run.sh
```

Du er nå inne i en Docker-container om har nødvendige verktøy installert.
Opprett bruker som tidligere som beskrevet i e-post ved registrering:

```bash
/fbs/drift/nybruker/process.sh <id>.sh
```
