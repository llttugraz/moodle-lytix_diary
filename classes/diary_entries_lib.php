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
 * @author     Guenther Moser <moser@tugraz.at>
 * @copyright  2023 Educational Technologies, Graz, University of Technology
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace lytix_diary;

use lytix_helper\course_settings;
use lytix_logs\logger;

/**
 * Class diary_entries_lib
 */
class diary_entries_lib extends \external_api {
    /**
     * Checks diary paramteters.
     * @return \external_function_parameters
     */
    public static function diary_get_parameters() {
        return new \external_function_parameters(
            [
                'contextid' => new \external_value(PARAM_INT, 'Context Id', VALUE_REQUIRED),
                'courseid' => new \external_value(PARAM_INT, 'Course Id', VALUE_REQUIRED),
                'userid' => new \external_value(PARAM_INT, 'User Id', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Checks diary return values.
     * @return \external_single_structure
     */
    public static function diary_get_returns() {
        return new \external_single_structure(
            [
                'semStart' => new \external_value(PARAM_INT, 'CourseId', VALUE_REQUIRED),
                'semEnd' => new \external_value(PARAM_INT, 'CourseId'), VALUE_REQUIRED,
                'entries' => new \external_multiple_structure(
                    new \external_single_structure(
                        [
                            'entry_id' => new \external_value(PARAM_INT, 'ID of entry', VALUE_REQUIRED),
                            'entry_title' => new \external_value(PARAM_TEXT, 'Title of entry', VALUE_REQUIRED),
                            'entry_date' => new \external_value(PARAM_TEXT, 'Date of entry', VALUE_REQUIRED),
                        ], '', VALUE_OPTIONAL
                    ), '', VALUE_OPTIONAL
                ),
            ]
        );
    }

    /**
     * Gets diary data.
     * @param int $contexid
     * @param int $courseid
     * @param int $userid
     * @return mixed
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \restricted_context_exception
     */
    public static function diary_get($contexid, $courseid, $userid) {
        global $DB;
        $data['semStart'] = course_settings::getcoursestartdate($courseid)->getTimestamp();
        $data['semEnd']   = course_settings::getcourseenddate($courseid)->getTimestamp();
        $data['entries'] = array();

        $params  = self::validate_parameters(self::diary_get_parameters(), [
            'contextid' => $contexid,
            'courseid' => $courseid,
            'userid' => $userid
        ]);

        // We always must call validate_context in a webservice.
        $context = \context::instance_by_id($params['contextid'], MUST_EXIST);
        self::validate_context($context);

        if (!$DB->record_exists('lytix_diary_diary_entries', [
            'courseid' => $params['courseid'],
            'userid' => $params['userid'],
            'deleted' => 0
        ])) {
            return $data;
        }

        $records = $DB->get_records('lytix_diary_diary_entries',
            [
                'courseid' => $params['courseid'],
                'userid' => $params['userid'],
                'deleted' => 0
            ], 'startdate');

        $entries = [];
        $records = array_reverse($records);
        foreach ($records as $record) {
            $entry = new \stdClass();
            $entry->entry_id = $record->id;
            $entry->entry_title = $record->title;
            $entry->entry_date = (new \DateTime())->setTimestamp($record->startdate)->format('d-m-Y');
            $entries[] = $entry;
        }

        $data['entries'] = $entries;

        return $data;
    }

    /**
     * Checks diary entry parameters.
     * @return \external_function_parameters
     */
    public static function diary_entry_parameters() {
        return new \external_function_parameters(
            array(
                'contextid'    => new \external_value(PARAM_INT, 'The context id for the course'),
                'jsonformdata' => new \external_value(PARAM_RAW, 'The data from the milestone form (json).')
            )
        );
    }

    /**
     * Checks diary entry return values.
     * @return \external_single_structure
     */
    public static function diary_entry_returns() {
        return new \external_single_structure(
            [
                'success' => new \external_value(PARAM_BOOL, 'Diary entry updated / inserted', VALUE_REQUIRED)
            ]
        );
    }

    /**
     * Checks diary entries.
     * @param int $contextid
     * @param float|mixed|\stdClass $jsonformdata
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \restricted_context_exception
     */
    public static function diary_entry($contextid, $jsonformdata) {
        global $DB;

        $data = [];

        $params  = self::validate_parameters(self::diary_entry_parameters(), [
            'contextid' => $contextid,
            'jsonformdata' => $jsonformdata
        ]);

        // We always must call validate_context in a webservice.
        $context = \context::instance_by_id($params['contextid'], MUST_EXIST);
        self::validate_context($context);

        $record = array();
        $serialiseddata = json_decode($params['jsonformdata']);
        parse_str($serialiseddata, $record);

        $startdate = (new
        \DateTime($record['startdate']['day'] . '-' . $record['startdate']['month'] . '-' . $record['startdate']['year']));
        $enddate   = (new
        \DateTime($record['startdate']['day'] . '-' . $record['startdate']['month'] . '-' . $record['startdate']['year']));

        $startdate->setTime($record['startdate']['hour'], $record['startdate']['minute']);
        $enddate->setTime($record['hour'], $record['minute']);

        $timediff = $startdate->diff($enddate);

        $record['startdate'] = $startdate->getTimestamp();
        $record['enddate'] = $enddate->getTimestamp();

        $minutes = $timediff->days * 24 * 60;
        $minutes += $timediff->h * 60;
        $minutes += $timediff->i;

        $record['time_spend'] = $minutes;
        $record['timecreated'] = (new \DateTime('now'))->getTimestamp();

        if ($record['id'] != -1) {
            $record['materials_found_text'] = ((object)$record)->materials_found_text['text'];
            $success = $DB->update_record('lytix_diary_diary_entries', (object)$record);
            if ($success) {
                logger::add($record['userid'], $record['courseid'], $params['contextid'], logger::TYPE_EDIT,
                    logger::TYPE_DIARY, $record['id']);
            }
        } else {
            unset($data['id']);
            $record['materials_found_text'] = ((object)$record)->materials_found_text['text'];
            $success = $DB->insert_record('lytix_diary_diary_entries', (object)$record);
            if ($success) {
                logger::add($record['userid'], $record['courseid'], $params['contextid'], logger::TYPE_ADD,
                    logger::TYPE_DIARY, $success);
            }
        }

        return [
            'success' => (bool)$success,
        ];
    }

    /**
     * Checks diary delete entry parameters.
     * @return \external_function_parameters
     */
    public static function diary_delete_entry_parameters() {
        return new \external_function_parameters(
            [
                'contextid' => new \external_value(PARAM_INT, 'Context Id', VALUE_REQUIRED),
                'courseid' => new \external_value(PARAM_INT, 'Course Id', VALUE_REQUIRED),
                'userid' => new \external_value(PARAM_INT, 'User Id', VALUE_REQUIRED),
                'id' => new \external_value(PARAM_INT, 'Diary enrtry Id', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Checks diary delete entry return values.
     * @return \external_single_structure
     */
    public static function diary_delete_entry_returns() {
        return new \external_single_structure(
            [
                'success' => new \external_value(PARAM_BOOL, 'Milestone updated / inserted', VALUE_REQUIRED)
            ]
        );
    }

    /**
     * Deletes diary entry.
     * @param int $contexid
     * @param int $courseid
     * @param int $userid
     * @param int $id
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \restricted_context_exception
     */
    public static function diary_delete_entry($contexid, $courseid, $userid, $id) {
        global $DB;

        $params  = self::validate_parameters(self::diary_delete_entry_parameters(), [
            'contextid' => $contexid,
            'courseid' => $courseid,
            'userid' => $userid,
            'id' => $id
        ]);

        // We always must call validate_context in a webservice.
        $context = \context::instance_by_id($params['contextid'], MUST_EXIST);
        self::validate_context($context);

        $record = $DB->get_record('lytix_diary_diary_entries',  ['id' => $params['id']]);
        $record->deleted = 1;

        $success = $DB->update_record('lytix_diary_diary_entries', $record);
        if ($success) {
            logger::add($record->userid, $record->courseid, $params['contextid'], logger::TYPE_DELETE,
                logger::TYPE_DIARY, $record->id);
        }

        return [
            'success' => $success,
        ];
    }

    /**
     * Checks diary entry parameters.
     * @return \external_function_parameters
     */
    public static function diary_entry_get_parameters() {
        return new \external_function_parameters(
            [
                'contextid' => new \external_value(PARAM_INT, 'Context Id', VALUE_REQUIRED),
                'courseid' => new \external_value(PARAM_INT, 'Course Id', VALUE_REQUIRED),
                'userid' => new \external_value(PARAM_INT, 'User Id', VALUE_REQUIRED),
                'id' => new \external_value(PARAM_INT, 'Diary enrtry Id', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Checks diary entries return values.
     * @return \external_single_structure
     */
    public static function diary_entry_get_returns() {
        return new \external_single_structure(
            [
                'entry' => new \external_single_structure(
                    [
                        'id' => new \external_value(PARAM_INT, 'id of entry', VALUE_REQUIRED),
                        'courseid' => new \external_value(PARAM_INT, 'courseid of entry', VALUE_REQUIRED),
                        'userid' => new \external_value(PARAM_INT, 'userid of entry', VALUE_REQUIRED),
                        'timecreated' => new \external_value(PARAM_INT, 'timestamp of entry', VALUE_REQUIRED),
                        'deleted' => new \external_value(PARAM_INT, 'status of entry', VALUE_REQUIRED),
                        'title' => new \external_value(PARAM_TEXT, 'title of entry', VALUE_REQUIRED),
                        'startdate' => new \external_value(PARAM_INT, 'date of entry', VALUE_REQUIRED),
                        'enddate' => new \external_value(PARAM_INT, 'enddate of entry', VALUE_REQUIRED),
                        'time_spend' => new \external_value(PARAM_INT, 'time spend for this entry', VALUE_REQUIRED),
                        'eventid' => new \external_value(PARAM_INT, 'event id', VALUE_REQUIRED),
                        'mstoneid' => new \external_value(PARAM_INT, 'milestone id', VALUE_REQUIRED),
                        'do_read' => new \external_value(PARAM_BOOL, 'do_read of entry', VALUE_REQUIRED),
                        'do_nodes' => new \external_value(PARAM_BOOL, 'do_nodes of entry', VALUE_REQUIRED),
                        'do_exercise' => new \external_value(PARAM_BOOL, 'do_exercise of entry', VALUE_REQUIRED),
                        'do_information' => new \external_value(PARAM_BOOL, 'do_information of entry', VALUE_REQUIRED),
                        'do_reflected' => new \external_value(PARAM_BOOL, 'do_reflected of entry', VALUE_REQUIRED),
                        'do_discuss_students' => new \external_value(PARAM_BOOL, 'do_discuss_students of entry', VALUE_REQUIRED),
                        'do_discuss_teacher' => new \external_value(PARAM_BOOL, 'do_discuss_teacher of entry', VALUE_REQUIRED),
                        'do_other' => new \external_value(PARAM_BOOL, 'do_other of entry', VALUE_REQUIRED),
                        'do_other_text' => new \external_value(PARAM_TEXT, 'do_other_text of entry', VALUE_REQUIRED),
                        'materials_slides' => new \external_value(PARAM_BOOL, 'materials_slides of entry', VALUE_REQUIRED),
                        'materials_script' => new \external_value(PARAM_BOOL, 'materials_script of entry', VALUE_REQUIRED),
                        'materials_exercise' => new \external_value(PARAM_BOOL, 'materials_exercise of entry', VALUE_REQUIRED),
                        'materials_recommended' => new \external_value(PARAM_BOOL, 'materials_recommended of entry',
                            VALUE_REQUIRED),
                        'materials_proposed' => new \external_value(PARAM_BOOL, 'materials_proposed of entry', VALUE_REQUIRED),
                        'materials_proposed_text' => new \external_value(PARAM_TEXT, 'materials_proposed_text of entry',
                            VALUE_REQUIRED),
                        'materials_found' => new \external_value(PARAM_BOOL, 'materials_found of entry', VALUE_REQUIRED),
                        'materials_found_text' => new \external_value(PARAM_RAW, 'materials_found_text of entry', VALUE_REQUIRED),
                        'learned_text' => new \external_value(PARAM_TEXT, 'learned_text of entry', VALUE_REQUIRED),
                        'not_understand_text' => new \external_value(PARAM_TEXT, 'not_understand_text of entry', VALUE_REQUIRED),
                        'goals' => new \external_value(PARAM_TEXT, 'goals of entry', VALUE_REQUIRED),
                        'goals_met' => new \external_value(PARAM_BOOL, 'goals_met of entry', VALUE_REQUIRED),
                        'goals_met_text' => new \external_value(PARAM_TEXT, 'goals_met_text of entry', VALUE_REQUIRED),
                        'different_next' => new \external_value(PARAM_TEXT, 'different_next of entry', VALUE_REQUIRED),
                    ], '', false
                ),
            ]
        );
    }

    /**
     * Gets diary entries.
     * @param int $contexid
     * @param int $courseid
     * @param int $userid
     * @param int $id
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \restricted_context_exception
     */
    public static function diary_entry_get($contexid, $courseid, $userid, $id) {
        global $DB;

        $params  = self::validate_parameters(self::diary_entry_get_parameters(), [
            'contextid' => $contexid,
            'courseid' => $courseid,
            'userid' => $userid,
            'id' => $id
        ]);

        // We always must call validate_context in a webservice.
        $context = \context::instance_by_id($params['contextid'], MUST_EXIST);
        self::validate_context($context);

        $data = [];

        $record = $DB->get_record('lytix_diary_diary_entries', [
            'id' => $params['id'],
            'courseid' => $params['courseid'],
            'userid' => $params['userid']
        ]);
        $data['entry'] = ($record != false) ? $record : [];
        return $data;
    }

    /**
     * The call parameters.
     * @return \external_function_parameters
     */
    public static function diary_history_parameters() {
        return new \external_function_parameters(
            [
                'contextid' => new \external_value(PARAM_INT, 'Context Id', VALUE_REQUIRED),
                'courseid' => new \external_value(PARAM_INT, 'Course Id', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * The return parameters.
     * @return \external_single_structure
     */
    public static function diary_history_returns() {
        return new \external_single_structure(
            [
                'Start' => new \external_value(PARAM_INT, 'Startmonth', VALUE_REQUIRED),
                'Counts' => new \external_multiple_structure(
                    new \external_value(PARAM_INT, 'Entries for this month', VALUE_OPTIONAL)
                )
            ]
        );
    }

    /**
     * Provides a statistic of created entries.
     * @param int $contexid
     * @param int $courseid
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \restricted_context_exception
     */
    public static function diary_history($contexid, $courseid) {
        global $DB;

        $params  = self::validate_parameters(self::diary_history_parameters(), [
            'contextid' => $contexid,
            'courseid' => $courseid,
        ]);

        // We always must call validate_context in a webservice.
        $context = \context::instance_by_id($params['contextid'], MUST_EXIST);
        self::validate_context($context);

        $start = course_settings::getcoursestartdate($courseid);
        $tmp = course_settings::getcoursestartdate($courseid);
        $end = new \DateTime("now");
        $intervals = [];

        while ($tmp->getTimestamp() < $end->getTimestamp()) {
            $month = new \stdClass();
            $month->start = strtotime('first day of this month', $tmp->getTimestamp());
            $month->end = strtotime('last day of this month', $tmp->getTimestamp());
            $intervals[] = $month;

            $tmp->modify("+ 1 month");
        }

        $counts = [];
        foreach ($intervals as $interval) {
            $sql = 'SELECT count(*)
                  FROM {lytix_diary_diary_entries}
                 WHERE courseid = :courseid AND timecreated >= :start AND timecreated < :end';
            $params = [
                'courseid' => $courseid,
                'start' => $interval->start,
                'end' => $interval->end
            ];
            $count = $DB->count_records_sql($sql, $params);
            $counts[] = $count;
        }

        return [
            'Start' => (int)$start->format('m'),
            'Counts' => array_reverse($counts)
        ];
    }
}
