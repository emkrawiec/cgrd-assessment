<?php

namespace Emkrawiec\CgrdAssessment\Framework;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigViewRenderer implements ViewRenderer
{
    private const TEMPLATES_DIR = __DIR__ . '/../../templates';
    private const VIEWS_DIR = self::TEMPLATES_DIR . '/views';
    private Environment $twig;

    /**
     * @throws LoaderError
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(self::TEMPLATES_DIR);
        $this->augmentTwigPaths($loader);

        $twig = new Environment($loader);
        $this->augmentTwigFunctions($twig);
        $this->augmentTwigGlobals($twig);

        $this->twig = $twig;
    }


    /**
     * @param string $viewName
     * @param array<mixed> $viewContext
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $viewName, array $viewContext = array()): void
    {
        $template = $this->twig->load($viewName);
        $renderedView = $template->render($viewContext);
        echo $renderedView;
    }

    private function augmentTwigFunctions(Environment $twig): void
    {
        $twig->addFunction(new \Twig\TwigFunction('attributes', function ($array, $init_attrs_arr = []) {
            $merged_attrs_arr = [];
            $array = empty($array) ? [] : $array;
            if (!empty($init_attrs_arr)) {
                foreach ($array as $attr => $attr_val) {
                    if (array_key_exists($attr, $init_attrs_arr)) {
                        $merged_attrs_arr[$attr] = $init_attrs_arr[$attr] .= " {$attr_val}";
                        unset($init_attrs_arr[$attr]);
                        unset($array[$attr]);
                    }
                }

                $merged_attrs_arr = array_merge($merged_attrs_arr, $init_attrs_arr, $array);
            } else {
                $merged_attrs_arr = $array;
            }

            $str = '';
            foreach ($merged_attrs_arr as $key => $value) {
                if (!isset($value) || $value === false) {
                    continue;
                }

                if ($value === true) {
                    $value = 'true';
                }

                $str .= ' ' . $key . '="' . addcslashes($value, '"') . '"';
            }

            return trim($str);
        }, [
            'is_safe' => ['html']
        ]));
    }

    /**
     * @throws LoaderError
     */
    private function augmentTwigPaths(FilesystemLoader $loader): void
    {
        $loader->addPath(self::VIEWS_DIR, 'views');
    }

    private function augmentTwigGlobals(Environment $twig): void
    {
        $twig->addGlobal('is_dev', $_ENV['ENV'] === 'dev');
        $twig->addGlobal('is_prod', $_ENV['ENV'] === 'production');
    }
}
