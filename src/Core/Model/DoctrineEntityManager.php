<?php

namespace App\Core\Model;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;

class DoctrineEntityManager
{

    /**
     * Adds the 'Enum' type to Doctrine for MySQL and returns the Entity Manager instance.
     * @return EntityManager
     * @throws Exception
     */
    public function getEntityManager(): EntityManager
    {
        Type::addType('enumUserRoleType', 'App\\Core\\Model\\DBAL\\EnumUserRoleType');
        Type::addType('enumArticleStatusType', 'App\\Core\\Model\\DBAL\\EnumArticleStatusType');
        $db = [
            'driver' => $_ENV['DB_TYPE'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'dbname' => $_ENV['DB_NAME'],
        ];
        $config = Setup::createAnnotationMetadataConfiguration([ENTITIES], $_ENV['APP_ENV'] === 'dev', null, null, false);
        try {
            $em = EntityManager::create($db, $config);
            $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('db_enumUserRoleType', 'enumUserRoleType');
            $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('db_enumArticleStatusType', 'enumArticleStatusType');
            return $em;
        } catch (ORMException $e) {
            var_dump($e->getMessage()); die;
        }
    }
}
