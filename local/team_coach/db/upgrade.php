<?php

function xmldb_local_team_coach_upgrade($oldversion): bool
{
    global $CFG, $DB;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    if ($oldversion < 2023051112) {
        // Perform the upgrade from version 2023051103 to the next version.

        // The content of this section should be generated using the XMLDB Editor.

        $table = new xmldb_table('menu_configuration');

        // Add columns.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL);
        $table->add_field('theme_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL);
        $table->add_field('menu_name', XMLDB_TYPE_CHAR, '200', null, null);
        $table->add_field('menu_url', XMLDB_TYPE_TEXT, '200', null, null);
        $table->add_field('menu_index', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('time_created', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('time_modified', XMLDB_TYPE_INTEGER, '20', null, null);

        // Add keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Create the table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('signup_value');

        // Add columns.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('signup', XMLDB_TYPE_CHAR, '100', null, null);
        $table->add_field('time_created', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('time_modified', XMLDB_TYPE_INTEGER, '20', null, null);

        // Add keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Create the table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
    }

    if ($oldversion < 2023051103) {
        // Perform the upgrade from version 2023051103 to the next version.

        // The content of this section should be generated using the XMLDB Editor.
    }

    // Everything has succeeded to here. Return true.
    return true;
}
