FROM otel/opentelemetry-collector-contrib:0.152.1@sha256:fa1f6ea8dabd3042fabf4411eed7fa52f253edd8940140bec89a573e62a24eb7 AS upstream

FROM debian:13.5-slim@sha256:28de0877c2189802884ccd20f15ee41c203573bd87bb6b883f5f46362d24c5c2
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
