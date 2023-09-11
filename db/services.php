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
 * This is a one-line short description of the file.
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    lytix_diary
 * @category   backup
 * @author     Guenther Moser <moser@tugraz.at>
 * @copyright  2023 Educational Technologies, Graz, University of Technology
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// We defined the web service functions to install.
$functions = array(
    'local_lytix_lytix_diary_get'                          => array(
        'classname'   => 'lytix_diary\\diary_entries_lib',
        'methodname'  => 'diary_get',
        'description' => 'Provides data for the diary widget',
        'type'        => 'read',
        'ajax'        => 'true'
    ),
    'local_lytix_lytix_diary_entry'                        => array(
        'classname'   => 'lytix_diary\\diary_entries_lib',
        'methodname'  => 'diary_entry',
        'description' => 'Adds or edits an entry for the diary widget. Created by the student',
        'type'        => 'write',
        'ajax'        => 'true'
    ),
    'local_lytix_lytix_diary_entry_get'                    => array(
        'classname'   => 'lytix_diary\\diary_entries_lib',
        'methodname'  => 'diary_entry_get',
        'description' => 'Returns the entry data for the diary edit modal.',
        'type'        => 'write',
        'ajax'        => 'true'
    ),
    'local_lytix_lytix_diary_delete_entry'                 => array(
        'classname'   => 'lytix_diary\\diary_entries_lib',
        'methodname'  => 'diary_delete_entry',
        'description' => 'Deletes an entry of the diary widget',
        'type'        => 'write',
        'ajax'        => 'true'
    ),
    'local_lytix_lytix_diary_history'                 => array(
        'classname'   => 'lytix_diary\\diary_entries_lib',
        'methodname'  => 'diary_history',
        'description' => 'Deletes an entry of the diary widget',
        'type'        => 'write',
        'ajax'        => 'true'
    ),
);



