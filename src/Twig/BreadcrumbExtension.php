<?php

namespace Bolt\Extension\Cooperaj\Breadcrumb\Twig;

use Bolt\Application;
use Bolt\Extension\Cooperaj\Breadcrumb\Breadcrumb;
use Bolt\Extension\Cooperaj\Breadcrumb\Extension;

class BreadcrumbExtension extends \Twig_Extension
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var array
     */
    private $config;

    /**
     * @var \Twig_Environment
     */
    private $twig = null;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = $this->app[Extension::CONTAINER]->config;
    }

    /**
     * @param \Twig_Environment $environment
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twig = $environment;
    }

    /**
     * Return the name of the extension
     */
    public function getName()
    {
        return 'breadcrumb.extension';
    }

    /**
     * @return array An array of Twig_SimpleFunction objects for twig to use.
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('breadcrumb', array($this, 'twigBreadcrumb'))
        );
    }

    /**
     * @param string $identifier The name of a bolt menu to render
     * @param string $template The name of a template to use to render the menu
     * @return \Twig_Markup
     */
    public function twigBreadcrumb($identifier, $template = '_sub_breadcrumb.twig')
    {
        /** @var \Bolt\Menu $resolvedMenu */
        $resolvedMenu = $this->app['menu']->menu($identifier);

        $breadcrumb = new Breadcrumb($this->app, $resolvedMenu);

        $html = $this->app['render']->render($template, array('breadcrumb' => $breadcrumb));

        return new \Twig_Markup($html, 'UTF-8');
    }
}
