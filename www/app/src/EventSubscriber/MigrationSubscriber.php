<?php

namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;

class MigrationSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            \Doctrine\Migrations\Events::onMigrationsMigrating,
            \Doctrine\Migrations\Events::onMigrationsMigrated,
        ];
    }

    public function onMigrationsMigrating(\Doctrine\Migrations\Event\MigrationsEventArgs $args): void
    {
        $args->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS=0;');
    }

    public function onMigrationsMigrated(\Doctrine\Migrations\Event\MigrationsEventArgs $args): void
    {
        $args->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS=1;');
    }
}
