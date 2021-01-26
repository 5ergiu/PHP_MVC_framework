<?php

require_once 'bootstrap.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use App\Core\Model\DoctrineEntityManager;

$entityManager = (new DoctrineEntityManager)->getEntityManager();

return ConsoleRunner::createHelperSet($entityManager);
