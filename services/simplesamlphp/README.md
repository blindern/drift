# simplesamlphp

## Testing locally

Since all keys are available in this repo this will start a working
instance where you can log in with "foreningsbruker".

```bash
docker-compose up --build

# fix volume on first run
docker-compose exec simplesamlphp chown www-data:www-data /storage
```

http://localhost:8888/simplesaml/

## Testing full flow

See https://github.com/blindern/intern

Run `intern/backend` with the `DEV_SSO` env set.

```bash
DEV_SSO=true php artisan serve --port 8081
```

Trigger login flow at http://localhost:8081/intern/api/saml2/login
