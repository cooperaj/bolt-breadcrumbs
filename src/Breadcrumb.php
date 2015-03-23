<?php

namespace Bolt\Extension\Cooperaj\Breadcrumb;

use Bolt\Application;
use Bolt\Helpers\Menu;

class Breadcrumb extends \ArrayObject
{
    private $route;
    private $route_params;

    public function __construct(Application $app, Menu $menu)
    {
        parent::__construct(array());

        $this->route = $app['request']->get('_route');
        $this->route_params = $app['request']->get('_route_params');

        $breadcrumb = $this->initialise($menu);
        $this->exchangeArray($breadcrumb ?: array());
    }

    protected function initialise(Menu $menu)
    {
        $reflection = new \ReflectionClass(get_class($this));
        $method = 'process' . ucfirst($this->route);

        if ($reflection->hasMethod($method)) {
            return call_user_func(array($this, $method), $menu);
        }
    }

    protected function pruneTree(array $menu, $path)
    {
        foreach ($menu as $menu_item)
        {
            if ($menu_item['path'] === $path) {
                $menu_item['current'] = 'true';
                return array($menu_item);
            }

            if (isset($menu_item['submenu'])) {
                $sub_items = $this->pruneTree($menu_item['submenu'], $path);

                if (!empty($sub_items)) {
                    $menu_item['current'] = 'true';
                    array_unshift($sub_items, $menu_item);
                    return $sub_items;
                }
            }
        }

        return array();
    }

    protected function createPath() {
        $path = '';

        $path .= $this->route_params['contenttypeslug'] ?: '';
        $path .= strlen($path) > 0 ? '/' : '';
        $path .= $this->route_params['slug'] ?: '';

        $path = trim($path, '/');

        return $path;
    }

    protected function processPagebinding(Menu $menu)
    {
        $breadcrumb = $this->pruneTree($menu->getItems(), $this->createPath());

        return $breadcrumb;
    }

    protected function processContentlisting(Menu $menu)
    {
        $breadcrumb = $this->pruneTree($menu->getItems(), $this->createPath());

        return $breadcrumb;
    }

    protected function processTaxonomylink(Menu $menu)
    {
        $breadcrumb = $this->pruneTree($menu->getItems(), $this->createPath());

        return $breadcrumb;
    }

    protected function processContentlink(Menu $menu)
    {
        $breadcrumb = $this->pruneTree($menu->getItems(), $this->createPath());

        return $breadcrumb;
    }

}
