<?php

function xmldb_local_team_coach_upgrade($oldversion): bool
{
    global $CFG, $DB;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    if ($oldversion < 2023051118) {
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

        $table = new xmldb_table('theme_banner');

        // Add columns.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('theme_id', XMLDB_TYPE_INTEGER, '10', null, null);
        $table->add_field('banner', XMLDB_TYPE_INTEGER, '10', null, null);
        $table->add_field('banner_title', XMLDB_TYPE_CHAR, '100', null, null);
        $table->add_field('banner_desc', XMLDB_TYPE_TEXT, '100', null, null);
        $table->add_field('time_created', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('time_modified', XMLDB_TYPE_INTEGER, '20', null, null);

        // Add keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Create the table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        $table = new xmldb_table('theme_section');

        // Add columns.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('theme_id', XMLDB_TYPE_INTEGER, '10', null, null);
        $table->add_field('section_title', XMLDB_TYPE_CHAR, '100', null, null);
        $table->add_field('descrip', XMLDB_TYPE_TEXT, '100', null, null);
        $table->add_field('section_index', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('section', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('descformat', XMLDB_TYPE_INTEGER, '2', null, null);
        $table->add_field('time_created', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('time_modified', XMLDB_TYPE_INTEGER, '20', null, null);

        // Add keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Create the table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('theme_partner');

        // Add columns.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('theme_id', XMLDB_TYPE_INTEGER, '10', null, null);
        $table->add_field('partner_name', XMLDB_TYPE_CHAR, '100', null, null);
        $table->add_field('partner_link', XMLDB_TYPE_TEXT, '100', null, null);
        $table->add_field('partner', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('time_created', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('time_modified', XMLDB_TYPE_INTEGER, '20', null, null);

        // Add keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Create the table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        $table = new xmldb_table('theme_footer_content');

        // Add columns.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('theme_id', XMLDB_TYPE_INTEGER, '10', null, null);
        $table->add_field('content_title', XMLDB_TYPE_CHAR, '100', null, null);
        $table->add_field('content_index', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('contentdesc', XMLDB_TYPE_TEXT, '200', null, null);
        $table->add_field('contentdescformat', XMLDB_TYPE_INTEGER, '10', null, null);
        $table->add_field('time_created', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('time_modified', XMLDB_TYPE_INTEGER, '20', null, null);

        // Add keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Create the table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        
        
        $table = new xmldb_table('theme_subbanner');

        // Add columns.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('themeid', XMLDB_TYPE_INTEGER, '10', null, null);
        $table->add_field('bannertitle', XMLDB_TYPE_CHAR, '100', null, null);
        $table->add_field('banner', XMLDB_TYPE_INTEGER, '20', null, null);
        $table->add_field('bannerimg', XMLDB_TYPE_INTEGER, '10', null, null);
        $table->add_field('bannertext', XMLDB_TYPE_TEXT, '200', null, null);
        $table->add_field('subbannerdesc', XMLDB_TYPE_TEXT, '200', null, null);
        $table->add_field('subbannerdescformat', XMLDB_TYPE_INTEGER, '10', null, null);
        $table->add_field('textcolor', XMLDB_TYPE_TEXT, '100', null, null);
        $table->add_field('backgroundcolor', XMLDB_TYPE_TEXT, '100', null, null);
        $table->add_field('bannerurl', XMLDB_TYPE_TEXT, '200', null, null);
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
