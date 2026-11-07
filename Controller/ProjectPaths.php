<?php

if (!function_exists('projectUrl')) {
    function projectUrl($path = '')
    {
        $scriptName = isset($_SERVER['SCRIPT_NAME'])
            ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME'])
            : '';
        $scriptDirectory = str_replace('\\', '/', dirname($scriptName));

        if (basename($scriptDirectory) === 'Controller') {
            $scriptDirectory = dirname($scriptDirectory);
        }

        $basePath = rtrim($scriptDirectory, '/');
        if ($basePath === '.' || $basePath === DIRECTORY_SEPARATOR) {
            $basePath = '';
        }

        return $basePath . '/' . ltrim($path, '/');
    }
}
