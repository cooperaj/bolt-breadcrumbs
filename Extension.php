<?php

namespace Bolt\Extension\Cooperaj\Breadcrumb;

use Bolt\BaseExtension;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Extension extends BaseExtension
{
    /**
     * Extension name
     *
     * @var string
     */
    const NAME = "Breadcrumb";

    /**
     * Extension's service container
     *
     * @var string
     */
    const CONTAINER = 'extensions.Breadcrumb';

    public function getName()
    {
        return Extension::NAME;
    }

    public function initialize()
    {

        /*
         * Frontend
         */
        if ($this->app['config']->getWhichEnd() == 'frontend') {
            // TODO Implement some sort of nice styling for the output
            //$this->addCss('assets/css/breadcrumb.css');

            // Twig functions
            $this->app['twig']->addExtension(new Twig\BreadcrumbExtension($this->app));

            // Add twig templates
            $this->app['twig.loader.filesystem']->prependPath(__DIR__ . "/assets/twig");
        }
    }

    /**
     * Set the defaults for configuration parameters
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return array(

        );
    }
}






