FROM otel/opentelemetry-collector-contrib:0.150.1@sha256:a516c26968aa1feb5e5fc0562e3338ea13755cb4f373603226bcc4e276374ad0 AS upstream

FROM debian:13.4-slim@sha256:4ffb3a1511099754cddc70eb1b12e50ffdb67619aa0ab6c13fcd800a78ef7c7a
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
