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
 * Testcases for diary.
 *
 * @package    lytix_diary
 * @author     Guenther Moser <moser@tugraz.at>
 * @copyright  2023 Educational Technologies, Graz, University of Technology
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace lytix_diary;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/webservice/tests/helpers.php');
require_once($CFG->dirroot . '/lib/externallib.php');

use external_api;
use externallib_advanced_testcase;
use lytix_helper\dummy;

/**
 * Class diary_entries_lib_test
 * @group learners_corner
 * @coversDefaultClass \lytix_diary\diary_entries_lib
 */
class diary_entries_lib_test extends externallib_advanced_testcase {
    /**
     * Variable for course.
     *
     * @var \stdClass|null
     */
    private $course = null;

    /**
     * Variable for the context
     *
     * @var bool|\context|\context_course|null
     */
    private $context = null;

    /**
     * Variable for the students
     *
     * @var array
     */
    private $students = [];

    /**
     * Setup called before any test case.
     */
    public function setUp(): void {
        $this->resetAfterTest(true);
        $this->setAdminUser();

        $course            = new \stdClass();
        $course->fullname  = 'Diary Lib Test Course';
        $course->shortname = 'diary_lib_test_course';
        $course->category  = 1;

        $this->students = dummy::create_fake_students(10);
        $return         = dummy::create_course_and_enrol_users($course, $this->students);
        $this->course   = $return['course'];
        $this->context  = \context_course::instance($this->course->id);

        set_config('course_list', $this->course->id, 'local_lytix');
        // Set platform.
        set_config('platform', 'learners_corner', 'local_lytix');

        // Set course start and enddate.
        $fivemonthsago = new \DateTime('2 months ago');
        $fivemonthsago->setTime(0, 0);
        set_config('semester_start', $fivemonthsago->format('Y-m-d'), 'local_lytix');
        $today = new \DateTime('today midnight');
        date_add($today, date_interval_create_from_date_string('1 month'));
        set_config('semester_end', $today->format('Y-m-d'), 'local_lytix');
    }

    /**
     * Tests diary webservice.
     *
     * @covers ::diary_get
     * @covers ::diary_get_parameters
     * @covers ::diary_get_returns
     *
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \restricted_context_exception
     */
    public function test_empty_diary() {
        $return = diary_entries_lib::diary_get($this->context->id, $this->course->id, get_admin()->id);
        try {
            external_api::clean_returnvalue(diary_entries_lib::diary_get_returns(), $return);
        } catch (\invalid_response_exception $e) {
            if ($e) {
                self::assertFalse(true, "invalid_responce_exception thrown.");
            }
        }
        // Basic asserts.
        $this::assertEquals(3, count($return));

        $this->assertTrue(key_exists('semStart', $return));
        $this->assertTrue(key_exists('semEnd', $return));
        $this->assertTrue(key_exists('entries', $return));

        // No entries.
        $this::assertEquals(0, count($return['entries']));
    }

    /**
     * Create a diary and delete it.
     *
     * @covers ::diary_entry
     * @covers ::diary_entry_parameters
     * @covers ::diary_entry_returns
     *
     * @covers ::diary_get
     * @covers ::diary_get_parameters
     * @covers ::diary_get_returns
     *
     * @covers ::diary_entry_get
     * @covers ::diary_entry_get_parameters
     * @covers ::diary_entry_get_returns
     *
     * @covers ::diary_delete_entry
     * @covers ::diary_delete_entry_parameters
     * @covers ::diary_delete_entry_returns
     *
     * @covers ::diary_history
     * @covers ::diary_history_parameters
     * @covers ::diary_history_returns
     *
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \invalid_response_exception
     * @throws \restricted_context_exception
     */
    public function test_diary_entry() {
        $diarydate = new \DateTime('now');
        $diarydate->setTime($diarydate->format('G'), $diarydate->format('i'), 00);
        $diaryenddate = new \DateTime('now');
        $diaryenddate->setTime($diaryenddate->format('G'), $diaryenddate->format('i'), 00);
        $diaryenddate->modify('+' . 2 . ' hours');
        $title = "Title";

        $formdata = "\"id=-1&userid=" . get_admin()->id . "&courseid=" . $this->course->id . "&title=" . $title .
                    "&startdate%5Bday%5D=" . $diarydate->format('j') . "&startdate%5Bmonth%5D=" . $diarydate->format('n') .
                    "&startdate%5Byear%5D=" . $diarydate->format('Y') . "&startdate%5Bhour%5D=" . $diarydate->format('G') .
                    "&startdate%5Bminute%5D=" . $diarydate->format('i') . "&hour=" . $diaryenddate->format('G') .
                    "&minute=" . $diaryenddate->format('i') . "&goals=&eventid=0&mstoneid=0&do_read=0&do_nodes=0" .
                    "&do_exercise=0&do_information=0&do_reflected=0&do_discuss_students=0&do_discuss_teacher=0&do_other=0"
                    . "&materials_slides=0&materials_script=0&materials_exercise=0&materials_recommended=0&materials_proposed=0"
                    . "&materials_found=0&materials_found_text%5Btext%5D=&materials_found_text%5Bformat%5D=1&learned_text=" .
                    "&not_understand_text=&goals_met=1&different_next=\"";

        $result = diary_entries_lib::diary_entry($this->context->id, $formdata);
        external_api::clean_returnvalue(diary_entries_lib::diary_entry_returns(), $result);

        // Check if the diary data is correct.
        $entries = diary_entries_lib::diary_get($this->context->id, $this->course->id, get_admin()->id);

        $this->assertTrue(key_exists('entries', $entries));
        $this::assertEquals(1, count($entries['entries']));

        $date = (new \DateTime())->setTimestamp($diarydate->getTimestamp())->format('d-m-Y');

        $this::assertEquals($title, $entries['entries'][0]->entry_title);
        $this::assertEquals($date, $entries['entries'][0]->entry_date);

        external_api::clean_returnvalue(diary_entries_lib::diary_get_returns(), $entries);

        // Check if the diary entry is created correctly.
        $entry = diary_entries_lib::diary_entry_get($this->context->id, $this->course->id, get_admin()->id,
                                                    $entries['entries'][0]->entry_id);
        $this->assertTrue(key_exists('entry', $entry));
        $this::assertEquals(1, count($entry));

        $this::assertEquals($this->course->id, $entry['entry']->courseid);
        $this::assertEquals(get_admin()->id, $entry['entry']->userid);
        $this::assertEquals($title, $entry['entry']->title);
        $this::assertEquals($diarydate->getTimestamp(), $entry['entry']->startdate);
        $this::assertEquals(1, $entry['entry']->goals_met);

        external_api::clean_returnvalue(diary_entries_lib::diary_entry_get_returns(), $entry);

        // Delete diary entry.
        $deleteentry = diary_entries_lib::diary_delete_entry($this->context->id, $this->course->id, get_admin()->id,
                                                             $entries['entries'][0]->entry_id);
        external_api::clean_returnvalue(diary_entries_lib::diary_delete_entry_returns(), $deleteentry);

        // Check if the entry is deleted correctly.
        $entries = diary_entries_lib::diary_get($this->context->id, $this->course->id, get_admin()->id);

        $this::assertEquals(0, count($entries['entries']));

        external_api::clean_returnvalue(diary_entries_lib::diary_get_returns(), $entries);

        // Check for diary history.
        $history = diary_entries_lib::diary_history($this->context->id, $this->course->id);
        $this::assertTrue($history['Start'] >= 1 && $history['Start'] <= 12, "Must be between 1 and 12");
        $this::assertEquals(3, count($history['Counts']));
        $this::assertEquals(1, $history['Counts'][0]);

        external_api::clean_returnvalue(diary_entries_lib::diary_history_returns(), $history);
    }
}
