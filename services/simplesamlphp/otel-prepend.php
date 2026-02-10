<?php

declare(strict_types=1);

if (PHP_SAPI === 'cli' || !file_exists('/var/simplesamlphp/vendor/autoload.php')) {
    return;
}

require_once '/var/simplesamlphp/vendor/autoload.php';

use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\API\Trace\StatusCode;
use OpenTelemetry\SemConv\TraceAttributes;

try {
    $tracer = Globals::tracerProvider()->getTracer('simplesamlphp');

    $method = $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN';
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

    $span = $tracer->spanBuilder("$method $path")
        ->setSpanKind(SpanKind::KIND_SERVER)
        ->setAttribute(TraceAttributes::HTTP_REQUEST_METHOD, $method)
        ->setAttribute(TraceAttributes::URL_PATH, $path)
        ->setAttribute(TraceAttributes::SERVER_ADDRESS, $_SERVER['SERVER_NAME'] ?? '')
        ->setAttribute(TraceAttributes::SERVER_PORT, (int) ($_SERVER['SERVER_PORT'] ?? 80))
        ->startSpan();
    $scope = $span->activate();

    register_shutdown_function(function () use ($span, $scope) {
        $statusCode = http_response_code() ?: 200;
        $span->setAttribute(TraceAttributes::HTTP_RESPONSE_STATUS_CODE, $statusCode);
        if ($statusCode >= 500) {
            $span->setStatus(StatusCode::STATUS_ERROR);
        }
        $scope->detach();
        $span->end();
    });
} catch (\Throwable $e) {
    error_log('otel-prepend: ' . $e->getMessage());
}
