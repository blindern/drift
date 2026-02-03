FROM otel/opentelemetry-collector-contrib:0.144.0@sha256:213886eb6407af91b87fa47551c3632be1a6419ff3a5114ef1e6fc364628496f AS upstream

FROM debian:13.3-slim@sha256:f6e2cfac5cf956ea044b4bd75e6397b4372ad88fe00908045e9a0d21712ae3ba
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
