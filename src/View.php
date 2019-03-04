<?php
namespace App;

/**
 * Template renderer, that can render templates with limited variable scope
 */

class View {
    
    private $basePath;

    public function __construct($basePath) {
        $this->basePath = $basePath;
    }

    public function render($path, $data) {
        $inc = self::createInclude();
        return $this->buffer(function() use ($inc, $path, $data) {
            $inc($this->basePath . $path, $data);
        });
    }

    private static function createInclude() {
        return function() {
            extract(func_get_arg(1));
            include func_get_arg(0);
        };
    }

    private function buffer(callable $wrap) {
        $cur_level = ob_get_level();
        try {
            ob_start();
            $wrap();
            return ob_get_clean();
        } catch (\Exception $e) {}
          catch (\Throwable $e) {}
        // clean the ob stack
        while (ob_get_level() > $cur_level) {
            ob_end_clean();
        }
        throw $e;
    }
}