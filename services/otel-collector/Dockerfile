FROM otel/opentelemetry-collector-contrib:0.151.0@sha256:d57bfe8eee2378f31cb1193239fbcac521d54a5a071fca2bfc106916a32b892d AS upstream

FROM debian:13.4-slim@sha256:109e2c65005bf160609e4ba6acf7783752f8502ad218e298253428690b9eaa4b
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
