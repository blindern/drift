FROM otel/opentelemetry-collector-contrib:0.164.0@sha256:340c9ca6a36617084c6661bdc4f0fe92b7cda5a805d61673ec8db375fddc522b AS upstream

FROM debian:13.7-slim@sha256:a29215f6a35e51e22adffa17f89e9d2ef06214e64a2bad10d765c46aea49f11f
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
