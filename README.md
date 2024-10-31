# lytix\_diary

This plugin is a subplugin of [local_lytix](https://github.com/llttugraz/moodle-local_lytix).  
The `lytix_diary` plugin provides a feature within Moodle where students can conduct self-reflection.

## Installation

1. Download the plugin and extract the files.
2. Move the extracted folder to your `moodle/local/lytix/modules` directory.
3. Log in as an admin in Moodle and navigate to `Site Administration > Plugins > Install plugins`.
4. Follow the on-screen instructions to complete the installation.

## Requirements

- Supported Moodle Version: 4.1 - 4.5
- Supported PHP Version:    7.4 - 8.3
- Supported Databases: MariaDB, PostgreSQL
- Supported Moodle Themes: Boost

This plugin has only been tested under the above system requirements against the specified versions.
However, it may work under other system requirements and versions as well.

## Features

- For students: the plugin offers a space for students to engage in self-reflection, aiding in their learning process.
- For teachers: the plugin offers an overview for the teachers, how many entries where made for a specific month by students.

## Configuration

No settings for the subplugin are available.

## Usage

The provided widget of this subplugin is part of the LYTIX operation mode `Learner's Corner`. We refer to [local_lytix](https://github.com/llttugraz/moodle-local_lytix) for the configuration of this operation mode. If the mode `Learner's Corner` is active  and if a course is in the list of supported courses for this mode, then this widget is displayed when clicking on `Learner's Corner` in the main course view.

## API Documentation

No API.

## Privacy

The following personal data are stored each time an entry is made in the diary:

| Entry                   | Description                               |
|-------------------------|-------------------------------------------|
| userid                  | The ID of the user                        |
| courseid                | The ID of the corresponding course        |
| title                   | Title of entry                            |
| timecreate              | Timestamp of creation                     |
| time_spend              | Time spen  for the activity               |
| targetid                | The ID of the  target                     |
| timestamp               | The corresponding timestamp               |
| startdate               | Staddate of entry                         |
| not_understand_text     | What did i not understand text field      |
| mstoneid                | Milestone ID                              |
| materials_slides        | Learned the slides flag                   |
| deleted                 | Entry deleted flag                        |
| different_next          | Do different next time text field         |
| do_discuss_student      | Discussed with other students flag        |
| do_discuss_teacher      | Discussed with teachers flag              |
| do_exercise             | Did exercises flag                        |
| do_information          | Fetched information's flag                |
| do_nodes                | Took nodes flag                           |
| do_other                | Other activities flag                     |
| do_other_text           | Other activities text field               |
| do_read                 | Read materials flag                       |
| do_reflected            | Reflected flag                            |
| enddate                 | Enddate of entry                          |
| eventid                 | Evendt Id                                 |
| goals                   | Goals text field                          |
| goals_met               | Goals met flag                            |
| goals_met_text          | Goals not met text field                  |
| learned_text            | What did i learned text field             |
| materials_exercise      | Did the extercise flag                    |
| materials_found         | Found materials on my own flag            |
| materials_found_text    | Found materials on my onw text field      |
| materials_proposed      | Learned proposed materials flag           |
| materials_proposed_text | Learn proposed materials text             |
| materials_recommended   | Learned recommended materials flag        |
| materials_script        | Learned the script flag                   |



## Known Issues

There are no known issues related to this plugin.

## Dependencies

- [local_lytix](https://github.com/llttugraz/moodle-local_lytix)
- [lytix_logs](https://github.com/llttugraz/moodle-lytix_logs)
- [lytix_helper](https://github.com/llttugraz/moodle-lytix_helper)

Note: In order that the provided widget is displayed in `Learner's Corner` the **subplugin** [lytix_config](https://github.com/llttugraz/moodle-lytix_config) **is required**.

## License

This plugin is licensed under the [GNU GPL v3](https://github.com/llttugraz/moodle-lytix_diary?tab=GPL-3.0-1-ov-file).

## Contributors

- **Günther Moser** - Developer - [GitHub](https://github.com/ghinta)
- **Alex Kremser** - Developer
