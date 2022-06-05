# ldap-toolbox

Dette brukes for å legge til nye foreningsbrukere på bakgrunn av
e-poster vi får fra registrering.

Denne oppskriften kan brukes for å opprette brukere:

Forutsetning:

* Må ha SSH-tilgang til miljøene våre (se Ansible-rollene `ssh-keys-coreos`
  og `ssh-keys-fcos` i dette repoet)

## Opprette brukere (samt oppdatere attributter på eksisterende brukere)

1. Logg inn på coreos-2:

   ```bash
   ssh -A core@coreos-2.foreningenbs.no
   ```

   coreos-2 er samme maskin som vi lagrer filer for brukerregistrering (se web-1).

1. Gå til mappen hvor vi har synket opp dette repoet:

   ```bash
   cd /data/drift/ldap-toolbox
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

## Endre grupper

1. Følg oppskriften over, men ikke kjør scriptet for å opprette/oppdatere bruker

1. Kall på ldap-scriptene som f.eks.:

   1. `ldapaddusertogroup henrste beboer`

   Se https://foreningenbs.no/confluence/display/FBS/LDAP for mer detaljer.

## Feilsøking

OBS! Vi har en bug som gjør at navn med æøå mister disse bokstavene. Man kan
redigere variablene i `<id>.sh` manuelt hvis man vil fikse dette før man
oppretter/oppdaterer en bruker.
