# Confluence oppsett ved bruk av Docker for FBS

Se også https://github.com/cptactionhank/docker-atlassian-confluence/

Se Dockerfile for hva vi gjør ekstra i oppsettet

## Kjøre Confluence

Normalt skal dette være satt opp på maskina allerede, og automatisk starte opp.

Dersom det er behov for å sette opp på nytt:

```bash
sudo docker run --restart=always \
  --name blindern-confluence \
  -v /var/atlassian/confluence:/var/atlassian/confluence \
  blindern/confluence-docker
```

Data blir da persistert i `/var/atlassian/confluence`-mappa.

## Oppdater Confluence

TODO: Dokumenter dette. Se også https://confluence.atlassian.com/doc/upgrading-confluence-4578.html
(men gjelder det egentlig når vi bruker Docker?)

