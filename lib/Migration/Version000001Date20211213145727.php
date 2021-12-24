<?php

declare(strict_types=1);

namespace OCA\RcConnect\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

class Version000001Date20211213145727 extends SimpleMigrationStep {

        /**
         * @param IOutput $output
         * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
         * @param array $options
         * @return null|ISchemaWrapper
         */
        public function changeSchema(IOutput $output, Closure $schemaClosure, array $options) {
                /** @var ISchemaWrapper $schema */
                $schema = $schemaClosure();


                if (!$schema->hasTable('rcconnect')) {
                        $table = $schema->createTable('rcconnect');
                        $table->addColumn('id', 'integer', [
                                'autoincrement' => true,
                                'notnull' => true,
                        ]);

                        $table->addColumn('uid', 'string', [
                                'notnull' => true,
                                'length' => '64'
                        ]);

                        $table->addColumn('username', 'string', [
                                'notnull' => true,
                                'length' => '64'
                        ]);

                        $table->addColumn('password', 'string', [
                                'notnull' => true,
                                'length' => '255'
                        ]);

                        $table->setPrimaryKey(['id']);
                }
                return $schema;
        }
}
