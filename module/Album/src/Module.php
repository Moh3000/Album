<?php

namespace Album;

class Module
{
    public function getConfig(): array
    {
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function onBootstrap($e)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
