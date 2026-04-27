FROM otel/opentelemetry-collector-contrib:0.149.0@sha256:0fba96233274f6d665ac8831ad99dfe6479a9a20459f6e2719c0d20945773b46 AS upstream

FROM debian:13.4-slim@sha256:cedb1ef40439206b673ee8b33a46a03a0c9fa90bf3732f54704f99cb061d2c5a
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
