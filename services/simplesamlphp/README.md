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
