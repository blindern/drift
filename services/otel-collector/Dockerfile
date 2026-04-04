FROM otel/opentelemetry-collector-contrib:0.149.0@sha256:0fba96233274f6d665ac8831ad99dfe6479a9a20459f6e2719c0d20945773b46 AS upstream

FROM debian:13.3-slim@sha256:1d3c811171a08a5adaa4a163fbafd96b61b87aa871bbc7aa15431ac275d3d430
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
