<?php

namespace lpagedev\Helpers;

class FileHelper
{
    /**
     * @param string $pFilePath
     * @return string|null
     */
    public static function FileRead(string $pFilePath): ?string
    {
        if (file_exists($pFilePath)) {
            $file = fopen($pFilePath, "r");
            $fileContents = fread($file, filesize($pFilePath));
            fclose($file);
            return $fileContents;
        }
        return null;
    }

    /**
     * @param string $pFilePath
     * @param string $pContents
     * @return bool
     */
    public static function FileWrite(string $pFilePath, string $pContents): bool
    {
        if (!is_dir($pFilePath)) {
            $file = fopen($pFilePath, "w");
            $res = fwrite($file, $pContents);
            fclose($file);
            if (file_exists($pFilePath)) return $res;
        }
        return false;
    }

    /**
     * @param string $pFilePath
     * @return array|null
     */
    public static function CSVRead(string $pFilePath): ?array
    {
        $rows = [];
        $auto_detect_line_endings = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', TRUE);

        $handle = fopen($pFilePath, 'r');
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (!is_null($data)) $rows[] = $data;
        }
        ini_set('auto_detect_line_endings', $auto_detect_line_endings);
        if (count($rows) > 0) return $rows;
        return null;
    }

    /**
     * @param string $pFilePath
     * @param array $pRows
     * @return bool
     */
    public static function CSVWrite(string $pFilePath, array $pRows, bool $pAddHeader = true, string $pSeparator = ',', string $pEnclosure = '"', string $pEscape = '\\'): bool
    {
        if (!is_dir($pFilePath)) {
            $file = fopen($pFilePath, "w");
            $length = 0;
            foreach ($pRows as $row) {
                if ($pAddHeader && $length == 0) {
                    $header = [];
                    foreach ($row as $name => $value) {
                        $header[] = $name;
                    }
                    $res = fputcsv($file, $header, $pSeparator, $pEnclosure, $pEscape);
                    if ($res) $length += $res;
                }
                $res = fputcsv($file, $row, $pSeparator, $pEnclosure, $pEscape);
                if ($res) $length += $res;
            }
            fclose($file);
            if (file_exists($pFilePath)) return $length;
        }
        return false;
    }
}
