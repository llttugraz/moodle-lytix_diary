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

$string['pluginname'] = 'Lytix Diary';

$string['privacy:metadata'] = 'This plugin does not store any data.';

// Diary.
$string['diary'] = 'Lerntagebuch';
$string['diary_entry'] = 'Lerntagebucheintrag';
$string['diary_entries_missing'] = 'Keine Einträge gefunden... Erstellen Sie neue Einträge, indem Sie unten auf die Schaltfläche klicken';
$string['entry_title'] = 'Titel';
$string['add_diary_entry'] = 'Neuer Eintrag';
$string['add_diary_entry_help'] = 'Klicken Sie auf den Button, um einen neuen Eintrag in ihr Tagebuch hinzuzufügen.';
$string['status_diary_entry'] = 'Status';
$string['diary_title'] = 'Titel setzen';
$string['diary_title_help'] = 'Schreiben Sie einen Titel für diesen Eintrag in die dafür vorgesehene Box.';
$string['entry_date'] = 'Datum';
$string['entry_date_help'] = 'Wählen Sie ein Datum für den Eintrag.';
$string['time_spend'] = 'Zeit investiert(Min.):';
$string['time_spend_help'] = 'Fügen Sie eine Zeit(in Minuten) die Sie für diesen Eintrag investiert haben ein.';
$string['required'] = 'A title for the entry is required.';
$string['numeric'] = 'Bitte nur die Minuten als Zahl eingeben.';
$string['event_or_milestone'] = 'Steht diese Lerneinheit im Zusammenhang mit einem Kursereignis und/oder einem persönlichen Ereignis?';
$string['event_select'] = 'Kursereignis';
$string['event_select_help'] = 'Wählen sie ein Kursereignis das Sie verbinden möchten.';
$string['milestone_select'] = 'Meilenstein:';
$string['milestone_select_help'] = 'Wählen sie einen persönlichen Meilenstein den Sie verbinden möchten.';
$string['no_event'] = 'kein Kursereignis';
$string['no_milestone'] = 'kein persönliches Ereignis';
$string['diary_select_acticity'] = 'Was habe ich getan?';
$string['diary_read'] = 'Lesen von Folien, Bücher usw...';
$string['diary_nodes'] = 'Notizen gemacht';
$string['diary_exercise'] = 'Übung(en) gelöst';
$string['diary_information'] = 'Informationen organisiert';
$string['diary_reflected'] = 'Überlegt';
$string['diary_discuss_students'] = 'Mit anderen Studierenden diskutiert';
$string['diary_discuss_teacher'] = 'Mit dem Lehrpersonal gesprochen';
$string['diary_other'] = 'Andere Aktivitäten...';
$string['diary_other_help'] = 'Wenn angehakt, schreiben Sie andere Aktivitäten in die dafür vorgesehene Box.';
$string['diary_other_text'] = 'Welche?';
$string['diary_select_materials'] = 'Welche Materialien habe ich verwendet?';
$string['diary_select_materials_help'] = 'Welche Materialien habe ich verwendet?';
$string['diary_slides'] = 'Kursfolien';
$string['diary_script'] = 'Kursskript';
$string['diary_materials_exercise'] = 'Kursübung';
$string['diary_recommended'] = 'Empfohlene Kursmaterialien, z.B. Buchartikel';
$string['diary_proposed'] = 'Andere, die vom Lehrpersonal vorgeschlagen wurden';
$string['diary_proposed_help'] = 'Wenn angehakt, schreiben Sie die vom Lehrpersonal vorgschlagenen Materialien in die dafür vorgesehene Box.';
$string['diary_proposed_text'] = 'Welche?';
$string['diary_found'] = 'Andere, die ich selbst gefunden/erstellt habe.';
$string['diary_found_help'] = 'Wenn angehakt, schreiben Sie die selbst gefundenen oder erstellte Materialien in die dafür vorgesehene Box.';
$string['diary_found_text'] = 'Welche?';
$string['diary_learned_text'] = 'Was habe ich gelernt? Was war neu für mich?';
$string['diary_not_understand_text'] = 'Was habe ich nicht verstanden? Was war weniger umfassend? Warum?';
$string['diary_goals_met'] = 'Habe ich meine Ziele für diese Lerneinheit erreicht?';
$string['diary_goals_met_help'] = 'Wenn "Nein" ausgewählt wurde, schreiben Sie in die dafür vorgesehene Box warum Sie das Ziel nicht erreicht haben.';
$string['diary_goals_met_text'] = 'Warum?';
$string['diary_goals_met_text_help'] = 'Warum haben Sie die gesetzten Ziele für diese Lernsitzung nicht erreicht?';
$string['diary_next'] = 'Was würde ich das nächste Mal anders machen?';
$string['diary_delete'] = 'Sind Sie sicher, dass Sie diesen Eintrag löschen möchten? Sie können den Vorgang nicht rückgängig machen!';
$string['set_hour'] = 'Wählen Sie die Stunde für die Endzeit: ';
$string['set_minute'] = 'Wählen Sie die Minute für die Endzeit:';
$string['set_endtime'] = 'Endzeit:';
$string['set_endtime_help'] = 'Wählen Sie die Endzeit (Stunde und Minute) für dieses Ereignis aus.';
$string['goals'] = 'Ziele:';
$string['goals_help'] = 'Was sind die Ziele für diese Lerneinheit?';

// Form headers.
$string['planner'] = 'Planer';
$string['activity'] = 'Aktivitäten';
$string['materials'] = 'Materialien';
$string['selfreflection'] = 'Selbstreflexion';
// Modal warning.
$string['title_required'] = '<div class="alert alert-danger">Ein Titel ist erforderlich.</div>';
$string['date_out_of_range'] = '<div class="alert alert-danger">Die ausgewählte Zeit liegt nicht im Zeitbereich des Kurses.</div>';
$string['time_smaller'] = '<div class="alert alert-danger">Endzeit ist kleiner als Startzeit!</div>';
// Teacher’s view.
$string['entries'] = 'Einträge';
$string['month'] = 'Monat';
$string['today'] = 'Heute';
// Privacy.
$string['privacy:metadata:lytix_diary'] = "Für das Lerntagebuch müssen einige Daten gespeichert werden";
$string['privacy:metadata:lytix_diary:courseid'] = "Die Kursid wird gebraucht um den Eintrag einem Kurs zu zuweisen";
$string['privacy:metadata:lytix_diary:userid'] = "Die Userid wird verwendet um einen Eintrag einem User zu zuweisen";
$string['privacy:metadata:lytix_diary:timecreated'] = "Zeitpunkt der Erstellung";
$string['privacy:metadata:lytix_diary:deleted'] = "Gelöscht Flag";
$string['privacy:metadata:lytix_diary:title'] = "Tiel des Eintrages";
$string['privacy:metadata:lytix_diary:startdate'] = "Startdatum";
$string['privacy:metadata:lytix_diary:enddate'] = "Enddatum";
$string['privacy:metadata:lytix_diary:time_spend'] = "Zeit für die Aktivität verbracht";
$string['privacy:metadata:lytix_diary:eventid'] = "Eereigniss Id";
$string['privacy:metadata:lytix_diary:mstoneid'] = "Meilenstein Id";
$string['privacy:metadata:lytix_diary:do_read'] = "Gelesen";
$string['privacy:metadata:lytix_diary:do_nodes'] = "Notizen erstellt";
$string['privacy:metadata:lytix_diary:do_exercise'] = "Übungen absolviert";
$string['privacy:metadata:lytix_diary:do_information'] = "Informationen eingeholt";
$string['privacy:metadata:lytix_diary:do_reflected'] = "Reflektiert";
$string['privacy:metadata:lytix_diary:do_discuss_student'] = "Mit Kommilitonen geredet";
$string['privacy:metadata:lytix_diary:do_discuss_teacher'] = "Mit Vortragenden geredet";
$string['privacy:metadata:lytix_diary:do_other'] = "Andere Aktivitäten Flag";
$string['privacy:metadata:lytix_diary:do_other_text'] = "Andere Aktivitäten Text";
$string['privacy:metadata:lytix_diary:materials_slides'] = "Slides gelesen";
$string['privacy:metadata:lytix_diary:materials_script'] = "Skriptum gelesen";
$string['privacy:metadata:lytix_diary:materials_exercise'] = "Übungen absolviert";
$string['privacy:metadata:lytix_diary:materials_recommended'] = "Empfohlene Lernmaterialien gelesen";
$string['privacy:metadata:lytix_diary:materials_proposed'] = "Vorgeschlagene Lernmaterialien gelesen";
$string['privacy:metadata:lytix_diary:materials_proposed_text'] = "Vorgeschalgene Lernmaterialien Text";
$string['privacy:metadata:lytix_diary:materials_found'] = "Selbst gefundene Lernmaterialien";
$string['privacy:metadata:lytix_diary:materials_found_text'] = "Selbst gefundene Lernmaterialien Text";
$string['privacy:metadata:lytix_diary:learned_text'] = "Gelernt Text";
$string['privacy:metadata:lytix_diary:not_understand_text'] = "Nicht verstanden Text";
$string['privacy:metadata:lytix_diary:goals_met'] = "Ziele erreicht";
$string['privacy:metadata:lytix_diary:goals_met_text'] = "Ziele erreicht Textfeld";
$string['privacy:metadata:lytix_diary:different_next'] = "Nächstes mal anders Textfeld";
$string['privacy:metadata:lytix_diary:goals'] = "Ziele Textfeld";

