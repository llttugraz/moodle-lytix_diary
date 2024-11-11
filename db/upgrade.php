<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Upgrade changes between versions
 *
 * @package   lytix_diary
 * @author     Guenther Moser <moser@tugraz.at>
 * @copyright  2023 Educational Technologies, Graz, University of Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or laterB
 */

/**
 * Upgrade Diary Basic DB
 * @param int $oldversion
 * @return bool
 * @throws ddl_exception
 * @throws downgrade_exception
 * @throws upgrade_exception
 */
function xmldb_lytix_diary_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2022032800) {
        // Basic savepoint reached.
        upgrade_plugin_savepoint(true, 2022032800, 'lytix', 'diary');
    }

    if ($oldversion < 2022072200) {

        // Define field timecreated to be added to lytix_diary_diary_entries.
        $table = new xmldb_table('lytix_diary_diary_entries');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'userid');

        // Conditionally launch add field timecreated.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field deleted to be added to lytix_diary_diary_entries.
        $table = new xmldb_table('lytix_diary_diary_entries');
        $field = new xmldb_field('deleted', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'timecreated');

        // Conditionally launch add field deleted.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Diary savepoint reached.
        upgrade_plugin_savepoint(true, 2022072200, 'lytix', 'diary');
    }

    if ($oldversion < 2022072500) {
        // Basic savepoint reached.
        upgrade_plugin_savepoint(true, 2022072500, 'lytix', 'diary');
    }

    if ($oldversion < 2022092100) {
        // Basic savepoint reached.
        upgrade_plugin_savepoint(true, 2022092100, 'lytix', 'diary');
    }

    if ($oldversion < 2024111100) {
        global $DB;
        // Delete deleted users from table 'lytix_diary_diary_entries'.
        $DB->delete_records_select('lytix_diary_diary_entries',
                'userid IN (SELECT id FROM  {user} WHERE deleted = 1)');

        // Delete non-existing courses from table 'lytix_diary_diary_entries'.
        $DB->delete_records_select('lytix_diary_diary_entries',
                'courseid NOT IN (SELECT id FROM  {course})');

        // Coursepolicy savepoint reached.
        upgrade_plugin_savepoint(true, 2024111100, 'lytix', 'diary');
    }

    return true;
}
