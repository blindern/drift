FROM otel/opentelemetry-collector-contrib:0.144.0@sha256:213886eb6407af91b87fa47551c3632be1a6419ff3a5114ef1e6fc364628496f AS upstream

FROM debian:13.3-slim@sha256:ef514b33e858a6ddd5a2af2b50f08f7ff2e43726a14d5f53bdb1d75220dfa5fb
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
