FROM otel/opentelemetry-collector-contrib:0.152.0@sha256:f41d7995565df3733b7568702073a9c490792f9c6ac60684fe6a4da21a313f8d AS upstream

FROM debian:13.4-slim@sha256:109e2c65005bf160609e4ba6acf7783752f8502ad218e298253428690b9eaa4b
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
