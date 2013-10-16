<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;

(@include_once __DIR__ . '/../vendor/autoload.php') || @include_once __DIR__ . '/../../../autoload.php';

require __DIR__.'/../../../../bootstrap/autoload.php';
$app = require_once __DIR__.'/../../../../bootstrap/start.php';

$env = getenv('LARAVEL_ENV');

if (!empty($env)) {
	App::instance('env', $env);
}

$app->boot();

$em = App::make('doctrine');

$helperSet = new HelperSet(array(
	'db' => new ConnectionHelper($em->getConnection()),
	'em' => new EntityManagerHelper($em),
	'dialog' => new DialogHelper(),
));

$migration_directory = App::make('path').'/database/doctrine-migrations';

$migrations_config = new Configuration($em->getConnection());
$migrations_config->setName('Doctrine Sandbox Migrations');
$migrations_config->setMigrationsNamespace('DoctrineMigrations');
$migrations_config->setMigrationsTableName('doctrine_migration_versions');
$migrations_config->setMigrationsDirectory($migration_directory);


$commands = array(
	new DiffCommand(),
	new ExecuteCommand(),
	new GenerateCommand(),
	new MigrateCommand(),
	new StatusCommand(),
	new VersionCommand()
);

foreach ($commands as $command) {
	$command->setMigrationConfiguration($migrations_config);
}

ConsoleRunner::run($helperSet, $commands);
