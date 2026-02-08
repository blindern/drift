FROM otel/opentelemetry-collector-contrib:0.145.0@sha256:a7343f01869071ea3f4c5e1e97df1bb1b3c4d5c77247db80e053a80b9df530c4 AS upstream

FROM debian:13.3-slim@sha256:f6e2cfac5cf956ea044b4bd75e6397b4372ad88fe00908045e9a0d21712ae3ba
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
