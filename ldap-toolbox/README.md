# ldap-toolbox

Dette brukes for å legge til ye foreningsbrukere på bakgrunn av
e-poster vi får ra registrering.

Kjør dette slik:

```bash
./run.sh
```

Du er nå inne i en Docker-container om har nødvendige verktøy installert.



ldapsearch -x -LLL -H ldapi:/// -b ou=Users,dc=foreningenbs,dc=no "(&(mail=henrist@henrist.net))" mail
