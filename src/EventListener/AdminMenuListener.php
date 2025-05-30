<?php

namespace App\EventListener;

use Sonata\AdminBundle\Event\ConfigureMenuEvent;

class AdminMenuListener
{
    public function __invoke(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();

        $postsGroup = $menu->getChild('Posts');

        if (!$postsGroup) {
            $postsGroup = $menu->addChild('Posts', [
                'extras' => ['label' => 'Posts'],
            ]);
        }

        $postsGroup->addChild('Graph', [
            'route' => 'admin_article_stats',
//            'extras' => ['icon' => 'fa fa-bar-chart'], // Add to change icon in dashboard
        ]);
    }
}