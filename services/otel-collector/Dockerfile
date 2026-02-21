FROM otel/opentelemetry-collector-contrib:0.146.1@sha256:f6e429c1052ab50f85a7afa5f7e32f25931697751622b0e1f453d10f79a1df3c AS upstream

FROM debian:13.3-slim@sha256:f6e2cfac5cf956ea044b4bd75e6397b4372ad88fe00908045e9a0d21712ae3ba
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
