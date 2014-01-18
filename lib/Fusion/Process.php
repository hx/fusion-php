<?php

namespace Fusion;
use Fusion\Exceptions;

/**
 * @method static string uglifyjs(array $args = [], $stdin = null)
 * @method static string coffee(array $args = [], $stdin = null)
 * @method static string sass(array $args = [], $stdin = null)
 */
class Process {

    public static $paths = [];

    public static function __callStatic($name, $args) {

        $bin = isset(self::$paths[$name]) ? self::$paths[$name] : $name;
        $binPath = trim(`which $bin`);

        if(!is_executable($binPath)) {
            throw new Exceptions\BadInterpreter($name, $binPath ?: $bin);
        }

        $shellArgs = isset($args[0]) ? $args[0] : [];
        $stdin = isset($args[1]) ? $args[1] : null;

        $desc = [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w']
        ];

        $cmd = $binPath . ' ' . implode(' ', array_map('escapeshellarg', $shellArgs));

        $proc = proc_open($cmd, $desc, $pipes);

        if($stdin !== null) {
            fwrite($pipes[0], $stdin);
        }

        fclose($pipes[0]);

        $ret = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $error = trim(stream_get_contents($pipes[2]));
        fclose($pipes[2]);

        $status = proc_close($proc);

        if($status || $error !== '') {
            throw new Exceptions\ProcessFailure($status, $error);
        }

        return $ret;

    }

}
