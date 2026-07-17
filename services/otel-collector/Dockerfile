FROM otel/opentelemetry-collector-contrib:0.152.1@sha256:fa1f6ea8dabd3042fabf4411eed7fa52f253edd8940140bec89a573e62a24eb7 AS upstream

FROM debian:13.6-slim@sha256:020c0d20b9880058cbe785a9db107156c3c75c2ac944a6aa7ab59f2add76a7bd
RUN apt-get update && apt-get install -y --no-install-recommends \
    systemd \
    ca-certificates \
  && rm -rf /var/lib/apt/lists/*
COPY --from=upstream /otelcol-contrib /otelcol-contrib
ENTRYPOINT ["/otelcol-contrib"]
