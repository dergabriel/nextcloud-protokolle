<?php

declare(strict_types=1);

namespace OCA\Protokolle\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version000001Date20260428000001 extends SimpleMigrationStep {
    public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        if (!$schema->hasTable('protokolle_gremium')) {
            $table = $schema->createTable('protokolle_gremium');
            $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
            $table->addColumn('name', 'string', ['length' => 200, 'notnull' => true]);
            $table->addColumn('kuerzel', 'string', ['length' => 30, 'notnull' => false]);
            $table->addColumn('beschreibung', 'text', ['notnull' => false]);
            $table->addColumn('created_at', 'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
            $table->addColumn('updated_at', 'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
            $table->setPrimaryKey(['id'], 'pr_gremium_pk');
            $table->addUniqueIndex(['name'], 'pr_gremium_name_u');
        }

        if (!$schema->hasTable('protokolle_rolle')) {
            $table = $schema->createTable('protokolle_rolle');
            $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
            $table->addColumn('gremium_id', 'integer', ['notnull' => true]);
            $table->addColumn('name', 'string', ['length' => 200, 'notnull' => true]);
            $table->addColumn('stimmberechtigt_default', 'boolean', ['notnull' => false, 'default' => 1]);
            $table->addColumn('beschreibung', 'text', ['notnull' => false]);
            $table->addColumn('created_at', 'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
            $table->addColumn('updated_at', 'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
            $table->setPrimaryKey(['id'], 'pr_rolle_pk');
            $table->addUniqueIndex(['gremium_id', 'name'], 'pr_rolle_grem_name_u');
            $table->addForeignKeyConstraint(
                $schema->getTable('protokolle_gremium'),
                ['gremium_id'],
                ['id'],
                ['onDelete' => 'CASCADE'],
                'pr_rolle_grem_fk',
            );
        }

        if (!$schema->hasTable('protokolle_person')) {
            $table = $schema->createTable('protokolle_person');
            $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
            $table->addColumn('nextcloud_user_id', 'string', ['length' => 64, 'notnull' => false]);
            $table->addColumn('vorname', 'string', ['length' => 200, 'notnull' => false]);
            $table->addColumn('nachname', 'string', ['length' => 200, 'notnull' => false]);
            $table->addColumn('email', 'string', ['length' => 200, 'notnull' => false]);
            $table->addColumn('extern', 'boolean', ['notnull' => false, 'default' => 0]);
            $table->addColumn('notizen', 'text', ['notnull' => false]);
            $table->addColumn('created_at', 'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
            $table->addColumn('updated_at', 'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
            $table->setPrimaryKey(['id'], 'pr_person_pk');
            $table->addUniqueIndex(['nextcloud_user_id'], 'pr_person_nc_uid_u');
        }

        if (!$schema->hasTable('protokolle_mitgliedschaft')) {
            $table = $schema->createTable('protokolle_mitgliedschaft');
            $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
            $table->addColumn('person_id', 'integer', ['notnull' => true]);
            $table->addColumn('rolle_id', 'integer', ['notnull' => true]);
            $table->addColumn('stimmberechtigt_override', 'boolean', ['notnull' => false]);
            $table->addColumn('created_at', 'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
            $table->addColumn('updated_at', 'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
            $table->setPrimaryKey(['id'], 'pr_mitgliedschaft_pk');
            $table->addUniqueIndex(['person_id', 'rolle_id'], 'pr_mitgliedschaft_u');
            $table->addForeignKeyConstraint(
                $schema->getTable('protokolle_person'),
                ['person_id'],
                ['id'],
                ['onDelete' => 'CASCADE'],
                'pr_mit_person_fk',
            );
            $table->addForeignKeyConstraint(
                $schema->getTable('protokolle_rolle'),
                ['rolle_id'],
                ['id'],
                ['onDelete' => 'CASCADE'],
                'pr_mit_rolle_fk',
            );
        }

        return $schema;
    }
}
