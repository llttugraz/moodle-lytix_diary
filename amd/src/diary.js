import $ from 'jquery';
import Ajax from 'core/ajax';
import ModalFactory from 'core/modal_factory';
import ModalEvents from 'core/modal_events';
import {loadFragment} from 'core/fragment';
import Templates from 'core/templates';
import Yui from 'core/yui';
import {getStrings} from 'lytix_helper/widget';
import {makeLoggingFunction} from 'lytix_logs/logs';

const moment = window.moment;

let log; // This will be the logging function.

var diary = {

    contextid: -1,
    courseid: -1,
    userid: -1,
    strings: null,
    semStart: null,
    semEnd: null,

    drawLoading: function() {
        var imgtype;
        if (Number(M.cfg.version) < 2024042200) {
            imgtype = "gif";
        } else {
            imgtype = "svg";
        }
        const img = '<img src="../../../pix/i/loading.' + imgtype + '" ' +
            'alt="LoadingImage" style="width:48px;height:48px;">';
        var widget = document.getElementById('lytix_diary');
        widget.innerHTML = img + ' ' + diary.strings.loading_msg;
    },
    drawdiary: function() {
        var promises = Ajax.call([
            {
                methodname: 'local_lytix_lytix_diary_get',
                args: {
                    contextid: diary.contextid, courseid: diary.courseid, userid: diary.userid},
            }
        ]);
        promises[0].done(function(response) {
            diary.semStart = moment.unix(response.semStart);
            diary.semEnd = moment.unix(response.semEnd);
            return Templates.render('lytix_diary/diary_table', response)
            .then(html => {
                $('#lytix_diary').html(html);
                return;
            })
            .fail(function(ex) {
                diary.renderTableFail(ex);
            });
        });
    },

    setAddListener: function() {
        // Set click listener to add button.
        $("#page").on('click', '#add_diary_entry', function() {
            log('OPEN', 'DIARY');
            diary.createDiaryEntryModal({id: -1, userid: diary.userid, courseid: diary.courseid});
        });
    },

    setEditListener: function() {
        // Set click listener to every row.
        $("#page").on('click', '.editdiaryentry', function() {
            var id = $(this).attr('id');
            log('OPEN', 'DIARY', null, id);
            var promises = Ajax.call([
                {
                    methodname: 'local_lytix_lytix_diary_entry_get',
                    args: {
                        contextid: diary.contextid, courseid: diary.courseid, userid: diary.userid, id: id
                    },
                }
            ]);
            promises[0].done(function(response) {
                if (response.entry !== null) {
                    diary.createDiaryEntryModal(response.entry);
                }
            });
        });
    },

    setDeleteListener: function() {
        // Set click listener to every row.
        $("#page").on('click', '.removediaryentry', function() {
            var id = $(this).attr('id');
            diary.deleteDiaryEntry(id);
        });
    },

    renderTableFail: function(ex) {
        var text = diary.strings.error_text + '<p>' + ex.message + '</p>';
        $('#lytix_diary').html(text);
    },

    renderModalFail: function(ex, id) {
        var text = diary.strings.error_text + '<p>' + ex.message + '</p>';
        $('#' + id + '.modal-body').html(text);
    },

    createDiaryEntryModal: function(item) {
        var trigger = $('#create-modal');
        var form = loadFragment('lytix_diary', 'new_diary_entry_form', diary.contextid, item);
        var title = diary.strings.diary_entry;
        ModalFactory.create({
            type: ModalFactory.types.SAVE_CANCEL,
            title: title,
            body: form,
        }, trigger).done(function(modal) {
            // Forms are big, we want a big modal.
            modal.setLarge();
            modal.show();
            var root = modal.getRoot();
            root.on(ModalEvents.save, function(m) {
                // Convert all the form elements values to a serialised string.
                var formData = root.find('form').serialize();
                // Check for mandatory values.
                if (document.getElementById('id_title').value === "") {
                    m.preventDefault();
                } else {
                    let diaryDate = new Date(document.getElementById('id_startdate_year').value,
                        document.getElementById('id_startdate_month').value - 1,
                        document.getElementById('id_startdate_day').value);
                    var timestampofentry = diaryDate.getTime();
                    if (timestampofentry < diary.semStart || timestampofentry > diary.semEnd) {
                        m.preventDefault();
                        modal.setBody(modal.getBody().innerHTML);
                        modal.getBody().append(diary.strings.date_out_of_range);
                    } else {
                        var mandatoryFlag = true;
                        var starthour = parseInt(document.getElementById('id_startdate_hour').value);
                        var endhour = parseInt(document.getElementById('id_hour').value);
                        var endminute = parseInt(document.getElementById('id_minute').value);
                        var startminute = parseInt(document.getElementById('id_startdate_minute').value);
                        if ((endhour < starthour) || (starthour === endhour && endminute < startminute)) {
                            mandatoryFlag = false;
                            m.preventDefault();
                            modal.setBody(modal.getBody().innerHTML);
                            modal.getBody().append(diary.strings.time_smaller);
                        }
                        if (mandatoryFlag) {
                            // Call the webservice with formData as param.
                            var promises = Ajax.call([
                                {
                                    methodname: 'local_lytix_lytix_diary_entry',
                                    args: {contextid: diary.contextid, jsonformdata: JSON.stringify(formData)},
                                }
                            ]);
                            promises[0].done(function() {
                                diary.resetModal();
                                diary.drawdiary();
                            }).fail(function(ex) {
                                // TODO Find solution to show error message in modal.
                                window.console.log(ex);
                                diary.resetModal();
                            });
                        }
                    }
                }
            });
            root.on(ModalEvents.hidden, function() {
                log('CLOSE', 'DIARY', null, item.id);
                modal.hide();
                modal.destroy();
            });
            root.on(ModalEvents.cancel, function() {
                diary.resetModal();
            });
        });
    },

    deleteDiaryEntry: function(id) {
        var trigger = $('#create-modal');
        var title = diary.strings.diary;
        var body = Templates.render('lytix_diary/delete_entry', {});
        ModalFactory.create({
            type: ModalFactory.types.SAVE_CANCEL,
            title: title,
            body: body,
        }, trigger).done(function(modal) {
            // Forms are big, we want a big modal.
            modal.show();
            var root = modal.getRoot();
            root.on(ModalEvents.save, function() {
                // Convert all the form elements values to a serialised string.
                var promises = Ajax.call([
                    {
                        methodname: 'local_lytix_lytix_diary_delete_entry',
                        args: {
                            contextid: diary.contextid, courseid: diary.courseid,
                            userid: diary.userid, id: id,
                        },
                    },
                ]);
                promises[0].done(function(response) {
                    if (response.success) {
                        diary.drawdiary();
                        diary.resetModal();
                    }
                }).fail(function(ex) {
                    diary.renderModalFail(ex, modal.body[0].id);
                    modal.show();
                });
            });
            root.on(ModalEvents.cancel, function() {
                diary.resetModal();
            });
        });
    },

    resetModal: function() {
        Yui.use('moodle-core-formchangechecker', function() {
            M.core_formchangechecker.reset_form_dirty_state();
        });
        // Reload that changes in table are done too.
        // document.location.reload();
    },
};

export const init = async (contextid, courseid, userid) => { // eslint-disable-line space-before-function-paren
    diary.contextid = contextid;
    diary.courseid = courseid;
    diary.userid = userid;
    diary.strings = await getStrings({
        lytix_diary: { // eslint-disable-line camelcase
            identical: [
                "error_text",
                "diary_entry",
                "diary",
                "loading_msg",
                "title_required",
                "date_out_of_range",
                "time_smaller",
            ],
        },
    });

    log = makeLoggingFunction(userid, courseid, contextid, 'diary');

    diary.drawLoading();
    diary.drawdiary();
    diary.setEditListener();
    diary.setDeleteListener();
    diary.setAddListener();
};
