<?php

namespace App\Support\Logging;

use DateTime;
use Monolog\Formatter\JsonFormatter;
use Throwable;

class GCloudFormatter extends JsonFormatter
{
    public function format(array $record): string
    {
        return json_encode(
                $this->translateRecordForGoogleCloudLoggingFormat($record)
            ) . ($this->appendNewline ? "\n" : '');
    }

    protected function formatBatchJson(array $records): string
    {
        $records = array_map(
            function ($record) {
                return $this->translateRecordForGoogleCloudLoggingFormat($record);
            },
            $records
        );
        return json_encode($records);
    }

    /**
     * @param array $record
     *
     * @return array
     */
    protected function translateRecordForGoogleCloudLoggingFormat(array $record): array
    {
        $exception = $record['context']['exception'] ?? null;
        if ($exception instanceof Throwable) {
            $record['context']['exception'] = $this->formatException($exception);
        }

        $dt = $record['datetime'];
        /** @var DateTime $dt */
        $formatted = [
            'message' => $record['message'],
            'severity' => $record['level_name'],
            'timestamp' => [
                'seconds' => $dt->getTimestamp(),
                'nanos' => 0,
            ],
            'channel' => $record['channel'],
        ];

        return array_merge($record['context'], $record['extra'], $formatted);
    }

    private function formatException(Throwable $exception): array
    {
        $trace = [];
        foreach ($exception->getTrace() as $record) {
            if (isset($record['file']) && str_contains($record['file'], 'vendor') === false) {
                $trace[] = $record;
            }
        }

        return [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $trace
        ];
    }
}
