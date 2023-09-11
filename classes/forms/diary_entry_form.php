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
 * @package    lytix_diary
 * @author     Guenther Moser <moser@tugraz.at>
 * @copyright  2023 Educational Technologies, Graz, University of Technology
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace lytix_diary\forms;

defined('MOODLE_INTERNAL') || die();

use core_plugin_manager;
use lytix_helper\forms_helper;
use moodleform;
use lytix_planner\forms\form_helper;

// Moodleform is defined in formslib.php.
require_once("$CFG->libdir/formslib.php");
/**
 * Class diary_entry_form
 */
class diary_entry_form extends moodleform {
    /**
     * Add elements to form.
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function definition() {
        global $DB;

        $component = 'lytix_diary';
        $mform     = $this->_form; // Don't forget the underscore!

        // General.
        $mform->addElement('html', '<h4>' . $this->_customdata['title'] . '</h4><br>');

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('hidden', 'id', $this->_customdata['id']);
        $mform->addElement('hidden', 'userid', $this->_customdata['userid']);
        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);

        $mform->addElement('text', 'title', get_string('diary_title', $component));
        $mform->setDefault('title', $this->_customdata['title']);
        $mform->addHelpButton('title', 'diary_title', $component);
        $mform->addRule('title', get_string('required', $component), 'required', null, 'client');

        $startyear = forms_helper::get_semester_start_year();
        $stopyear = forms_helper::get_semester_end_year();

        $mform->addElement('date_time_selector', 'startdate', get_string('entry_date', $component),
            ['startyear' => $startyear,
                'stopyear'  => $stopyear,
                'timezone'  => 99, 'optional' => false]);
        $mform->setDefault('startdate', $this->_customdata['startdate']);
        $mform->addHelpButton('startdate', 'entry_date', $component);

        $times = form_helper::get_hours_minutes_array();

        $hours = $times['hours'];
        $minutes = $times['minutes'];

        $timearray   = array();
        $timearray[] =& $mform->createElement('select', 'hour', get_string('set_hour', $component), $hours);
        $timearray[] =& $mform->createElement('select', 'minute', get_string('set_minute', $component), $minutes);
        $mform->addGroup($timearray, 'endtime', get_string('set_endtime', $component), array(' '), false);
        $mform->addHelpButton('endtime', 'set_endtime', $component);
        form_helper::set_enddate($mform, 'startdate', '', $this->_customdata['enddate']);

        $mform->addElement('text', 'time_spend', get_string('time_spend', $component));
        $mform->addHelpButton('time_spend', 'time_spend', $component);
        if ($this->_customdata['time_spend']) {
            $mform->setDefault('time_spend', $this->_customdata['time_spend']);
        } else {
            $mform->setDefault('time_spend', '60');
        }
        $mform->disabledIf('time_spend', '' , true);

        $mform->addElement('text', 'goals', get_string('goals', $component), array('size' => '80'));
        $mform->setDefault('goals', $this->_customdata['goals']);
        $mform->addHelpButton('goals', 'goals', $component);

        // Planner.
        $plugins = core_plugin_manager::instance()->get_plugins_of_type('planner');
        if ($plugins) {
            $mform->addElement('header', 'plannerhdr', get_string('planner', $component));

            $mform->addElement('html', '<h5>' . get_string('event_or_milestone', $component) . '</h5><br>');
            $events = $DB->get_records('lytix_planner_events', ['courseid' => $this->_customdata['courseid']], 'startdate');
            $eoptions = [];
            $eoptions[0] = get_string('no_event', $component);
            foreach ($events as $item) {
                $eoptions[$item->id] = $item->title .
                ' [' . (new \DateTime())->setTimestamp($item->startdate)->format('Y-m-d H:i:s') . ']';
            }
            $mform->addElement('select', 'eventid', get_string('event_select', $component), $eoptions);
            $mform->setDefault('eventid', $this->_customdata['eventid']);

            $milest = $DB->get_records('lytix_planner_milestone', ['courseid' => $this->_customdata['courseid'],
                'userid' => $this->_customdata['userid']], 'startdate');
            $moptions = [];
            $moptions[0] = get_string('no_milestone', $component);
            foreach ($milest as $item) {
                $moptions[$item->id] = $item->title .
                    ' [' . (new \DateTime())->setTimestamp($item->startdate)->format('Y-m-d H:i:s') . ']';
            }
            $mform->addElement('select', 'mstoneid', get_string('milestone_select', $component), $moptions);
            $mform->setDefault('mstoneid', $this->_customdata['mstoneid']);
        }

        // Activity.
        $mform->addElement('header', 'activityhdr', get_string('activity', $component));

        $mform->addElement('html', '<h5>' . get_string('diary_select_acticity', $component) . '</h5><br>');

        $mform->addElement('advcheckbox', 'do_read', get_string('diary_read', $component));
        $mform->setDefault('do_read', $this->_customdata['do_read']);

        $mform->addElement('advcheckbox', 'do_nodes', get_string('diary_nodes', $component));
        $mform->setDefault('do_nodes', $this->_customdata['do_nodes']);

        $mform->addElement('advcheckbox', 'do_exercise', get_string('diary_exercise', $component));
        $mform->setDefault('do_exercise', $this->_customdata['do_exercise']);

        $mform->addElement('advcheckbox', 'do_information', get_string('diary_information', $component));
        $mform->setDefault('do_information', $this->_customdata['do_information']);

        $mform->addElement('advcheckbox', 'do_reflected', get_string('diary_reflected', $component));
        $mform->setDefault('do_reflected', $this->_customdata['do_reflected']);

        $mform->addElement('advcheckbox', 'do_discuss_students', get_string('diary_discuss_students', $component));
        $mform->setDefault('do_discuss_students', $this->_customdata['do_discuss_students']);

        $mform->addElement('advcheckbox', 'do_discuss_teacher', get_string('diary_discuss_teacher', $component));
        $mform->setDefault('do_discuss_teacher', $this->_customdata['do_discuss_teacher']);

        $mform->addElement('advcheckbox', 'do_other', get_string('diary_other', $component));
        $mform->setDefault('do_other', $this->_customdata['do_other']);
        $mform->addHelpButton('do_other', 'diary_other', $component);

        $mform->addElement('text', 'do_other_text', get_string('diary_other_text', $component), array('size' => '80'));
        $mform->setDefault('do_other_text', $this->_customdata['do_other_text']);
        $mform->disabledIf('do_other_text', 'do_other', 'notchecked');

        // Materials.
        $mform->addElement('header', 'materialshdr', get_string('materials', $component));

        $mform->addElement('html', '<h5>' . get_string('diary_select_materials', $component) . '</h5><br>');

        $mform->addElement('advcheckbox', 'materials_slides', get_string('diary_slides', $component));
        $mform->setDefault('materials_slides', $this->_customdata['materials_slides']);

        $mform->addElement('advcheckbox', 'materials_script', get_string('diary_script', $component));
        $mform->setDefault('materials_script', $this->_customdata['materials_script']);

        $mform->addElement('advcheckbox', 'materials_exercise', get_string('diary_materials_exercise', $component));
        $mform->setDefault('materials_exercise', $this->_customdata['materials_exercise']);

        $mform->addElement('advcheckbox', 'materials_recommended', get_string('diary_recommended', $component));
        $mform->setDefault('materials_recommended', $this->_customdata['materials_recommended']);

        $mform->addElement('advcheckbox', 'materials_proposed', get_string('diary_proposed', $component));
        $mform->setDefault('materials_proposed', $this->_customdata['materials_proposed']);
        $mform->addHelpButton('materials_proposed', 'diary_proposed', $component);

        $mform->addElement('text', 'materials_proposed_text', get_string('diary_proposed_text', $component), array('size' => '80'));
        $mform->setDefault('materials_proposed_text', $this->_customdata['materials_proposed_text']);
        $mform->disabledIf('materials_proposed_text', 'materials_proposed', 'notchecked');

        $mform->addElement('advcheckbox', 'materials_found', get_string('diary_found', $component));
        $mform->setDefault('materials_found', $this->_customdata['materials_found']);
        $mform->addHelpButton('materials_found', 'diary_found', $component);

        // TODO Beschreibung mit Linksymbol hinzufügen.
        $mform->addElement('editor', 'materials_found_text', get_string('diary_found_text', $component),
            array('size' => '80'), array('autosave' => 0, 'enable_filemanagement' => 0));
        $mform->setDefault('materials_found_text', array('text' => $this->_customdata['materials_found_text']));
        $mform->disabledIf('materials_found_text', 'materials_found', 'notchecked');
        $mform->setType('materials_found_text', PARAM_CLEANHTML);

        // Results.
        $mform->addElement('header', 'resultshdr', get_string('selfreflection', $component));

        $mform->addElement('text', 'learned_text', get_string('diary_learned_text', $component), array('size' => '80'));
        $mform->setDefault('learned_text', $this->_customdata['learned_text']);

        $mform->addElement('text', 'not_understand_text', get_string('diary_not_understand_text', $component),
            array('size' => '80'));
        $mform->setDefault('not_understand_text', $this->_customdata['not_understand_text']);

        $choices = array(
            0 => get_string('no'),
            1 => get_string('yes'),
        );
        $mform->addElement('select', 'goals_met', get_string('diary_goals_met', $component), $choices);
        $mform->setDefault('goals_met', $this->_customdata['goals_met']);
        $mform->addHelpButton('goals_met', 'diary_goals_met', $component);

        $mform->addElement('text', 'goals_met_text', get_string('diary_goals_met_text', $component), array('size' => '80'));
        $mform->setDefault('goals_met_text', $this->_customdata['goals_met_text']);
        $mform->addHelpButton('goals_met_text', 'diary_goals_met_text', $component);
        $mform->disabledIf('goals_met_text', 'goals_met', 'eq', '1');

        $mform->addElement('text', 'different_next', get_string('diary_next', $component), array('size' => '80'));
        $mform->setDefault('different_next', $this->_customdata['different_next']);
    }
}
