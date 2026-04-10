FROM otel/opentelemetry-collector-contrib:0.149.0@sha256:0fba96233274f6d665ac8831ad99dfe6479a9a20459f6e2719c0d20945773b46 AS upstream

FROM debian:13.4-slim@sha256:4ffb3a1511099754cddc70eb1b12e50ffdb67619aa0ab6c13fcd800a78ef7c7a
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
