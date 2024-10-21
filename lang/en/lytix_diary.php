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
 * Activity plugin for lytix
 *
 * @package    lytix_diary
 * @author     Guenther Moser <moser@tugraz.at>
 * @copyright  2023 Educational Technologies, Graz, University of Technology
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['activity'] = 'Activity';
$string['add_diary_entry'] = 'New Entry';
$string['add_diary_entry_help'] = 'Click on the button to create a new entry.';
$string['date_out_of_range'] = '<div class="alert alert-danger">The selected time is not in the time range of the course.</div>';
$string['diary'] = 'Learning Diary';
$string['diary_delete'] = 'Are you sure you want to delete this entry?  You can not undo this.';
$string['diary_discuss_students'] = 'Discussed with other students';
$string['diary_discuss_teacher'] = 'Discussed with the teaching staff';
$string['diary_entries_missing'] = 'No entries found... Create new ones by clicking the button below.';
$string['diary_entry'] = 'Learning Diary Entry';
$string['diary_exercise'] = 'Solved exercise(s)';
$string['diary_found'] = 'Others that I found or created on my own.';
$string['diary_found_help'] = 'If checked, write materials that you found or created on your own in the box provided.';
$string['diary_found_text'] = 'Which?';
$string['diary_goals_met'] = 'Did I achieve my goals for this learning session?';
$string['diary_goals_met_help'] = 'If "No" is selected, write in the box provided why you did not achieve the goals.';
$string['diary_goals_met_text'] = 'Why?';
$string['diary_goals_met_text_help'] = 'Why did you not achieve the the goals that you set for this learning session?';
$string['diary_information'] = 'Organized information';
$string['diary_learned_text'] = 'What did I learn? What was new to me?';
$string['diary_materials_exercise'] = 'Course exercise';
$string['diary_next'] = 'What would I do differently next time?';
$string['diary_nodes'] = 'Took notes';
$string['diary_not_understand_text'] = 'What did I not understand? What was less comprehensive? Why?';
$string['diary_other'] = 'Other activities...';
$string['diary_other_help'] = 'If checked, write other activities in the box provided.';
$string['diary_other_text'] = 'Which?';
$string['diary_proposed'] = 'Others proposed by the teaching staff';
$string['diary_proposed_help'] = 'If checked, write proposed materials by the teaching staff in the box provided.';
$string['diary_proposed_text'] = 'Which?';
$string['diary_read'] = 'Read slides, books etc...';
$string['diary_recommended'] = 'Course recommended materials, e.g. books articles';
$string['diary_reflected'] = 'Reflected';
$string['diary_script'] = 'Course script';
$string['diary_select_acticity'] = 'What did I do?';
$string['diary_select_materials'] = 'Which materials did I use?';
$string['diary_select_materials_help'] = 'Which materials did I use?';
$string['diary_slides'] = 'Course slides';
$string['diary_title'] = 'Set Title';
$string['diary_title_help'] = 'Write a title for this entry in the box provided.';
$string['entries'] = 'Entries';
$string['entry_date'] = 'Date';
$string['entry_date_help'] = 'Select a date for this entry.';
$string['entry_title'] = 'Title';
$string['error_text'] = '<div class="alert alert-danger">Something went wrong, please reload the page(F5). <br>
 If this error happens again please contact your administrator.</div>';
$string['event_or_milestone'] = 'Is this learning session related to a course event and/or a personal event?';
$string['event_select'] = 'Course Event:';
$string['event_select_help'] = 'Select a courseevent to connect it with this entry.';
$string['goals'] = 'Goals:';
$string['goals_help'] = 'What are the goals for this learning session?';
$string['loading_msg'] = "Loading data from the system, please wait";
$string['materials'] = 'Materials';
$string['milestone_select'] = 'Milestone:';
$string['milestone_select_help'] = 'Select an personal milestone to connect it with this entry.';
$string['month'] = 'Month';
$string['no_event'] = 'no Course Event';
$string['no_milestone'] = 'no Personal Event';
$string['numeric'] = 'Bitte nur die Minuten als Zahl eingeben.';
$string['numeric_only'] = 'Please enter only the minutes spend.(as integer)';
$string['planner'] = 'Planner';
$string['pluginname'] = 'Lytix Diary';

$string['privacy:metadata'] = 'This plugin does not store any data.';
$string['privacy:metadata:lytix_diary'] = "In order to provide a learning diary for the users , we\
 need to save some user related data";
$string['privacy:metadata:lytix_diary:courseid'] = "The course ID will be saved for knowing to which course the\
 data belongs to";
$string['privacy:metadata:lytix_diary:deleted'] = "Entry deleted flag";
$string['privacy:metadata:lytix_diary:different_next'] = "Do different next time text field";
$string['privacy:metadata:lytix_diary:do_discuss_student'] = "Discussed with other students flag";
$string['privacy:metadata:lytix_diary:do_discuss_teacher'] = "Discussed with teachers flag";
$string['privacy:metadata:lytix_diary:do_exercise'] = "Did exercises flag";
$string['privacy:metadata:lytix_diary:do_information'] = "Fetched information's flag";
$string['privacy:metadata:lytix_diary:do_nodes'] = "Took nodes flag";
$string['privacy:metadata:lytix_diary:do_other'] = "Other activities flag";
$string['privacy:metadata:lytix_diary:do_other_text'] = "Other activities text field";
$string['privacy:metadata:lytix_diary:do_read'] = "Read materials flag";
$string['privacy:metadata:lytix_diary:do_reflected'] = "Reflected flag";
$string['privacy:metadata:lytix_diary:enddate'] = "Enddate of entry";
$string['privacy:metadata:lytix_diary:eventid'] = "Evendt Id";
$string['privacy:metadata:lytix_diary:goals'] = "Goals text field";
$string['privacy:metadata:lytix_diary:goals_met'] = "Goals met flag";
$string['privacy:metadata:lytix_diary:goals_met_text'] = "Goals not met text field";
$string['privacy:metadata:lytix_diary:learned_text'] = "What did i learned text field";
$string['privacy:metadata:lytix_diary:materials_exercise'] = "Did the extercise flag";
$string['privacy:metadata:lytix_diary:materials_found'] = "Found materials on my own flag";
$string['privacy:metadata:lytix_diary:materials_found_text'] = "Found materials on my onw text field";
$string['privacy:metadata:lytix_diary:materials_proposed'] = "Learned proposed materials flag";
$string['privacy:metadata:lytix_diary:materials_proposed_text'] = "Learn proposed materials text";
$string['privacy:metadata:lytix_diary:materials_recommended'] = "Learned recommended materials flag";
$string['privacy:metadata:lytix_diary:materials_script'] = "Learned the script flag";
$string['privacy:metadata:lytix_diary:materials_slides'] = "Learned the slides flag";
$string['privacy:metadata:lytix_diary:mstoneid'] = "Milestone Id";
$string['privacy:metadata:lytix_diary:not_understand_text'] = "What did i not understand text field";
$string['privacy:metadata:lytix_diary:startdate'] = "Staddate of entry";
$string['privacy:metadata:lytix_diary:time_spend'] = "Time spend for the activity";
$string['privacy:metadata:lytix_diary:timecreated'] = "Timestamp of creation";
$string['privacy:metadata:lytix_diary:title'] = "Title of entry";
$string['privacy:metadata:lytix_diary:userid'] = "The user ID will be saved for uniquely identifying the user";
$string['required'] = 'A title for the entry is required.';
$string['selfreflection'] = 'Self-reflection';
$string['set_endtime'] = 'Endtime:';
$string['set_endtime_help'] = 'Select the end time (hour and minute) for this event.';
$string['set_hour'] = 'Select the hour of the endtime: ';
$string['set_minute'] = 'Select the minute of the endtime:';
$string['status_diary_entry'] = 'Status';
$string['time_smaller'] = '<div class="alert alert-danger">Endtime is smaller than starttime!</div>';
$string['time_spend'] = 'Time spend(min.):';
$string['time_spend_help'] = 'Add time spend for this entry in minutes.';
$string['title_required'] = '<div class="alert alert-danger">A title for the entry is required.</div>';
$string['today'] = 'Today';
