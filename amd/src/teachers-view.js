import Widget from 'lytix_helper/widget';
import Templates from 'core/templates';

const WIDGET_ID = 'diary';

export const init = (contextid, userid, courseid, locale) => {

    const wsData = Widget.getData(
        'local_lytix_lytix_diary_history',
        {contextid: contextid, courseid: courseid}
    );
    locale = Widget.convertLocale(locale);
    Promise.resolve(wsData).then(data => {
        const
            counts = data.Counts,
            length = counts.length,
            context = {
                bars: Array(length),
                counts: Array(length),
                months: Array(length),
            };

        let highestCount = 0;
        for (let i = 0; i < length; ++i) {
            const currentCount = context.counts[i] = counts[i];
            if (currentCount > highestCount) {
                highestCount = currentCount;
            }
        }

        let monthIndex = data.Start - 1;
        const
            month = new Date(2022, monthIndex), // Arbitrary year, we only need the month name.
            format = (new Intl.DateTimeFormat(locale, {month: 'short'})).format;
        for (let i = length - 1; i >= 0; --i) {
            context.bars[i] = counts[i] / highestCount * 100;
            context.months[i] = format(month);
            month.setMonth(++monthIndex % 12);
        }
        context.months.shift(); // Remove first entry because it is replaced by ‘today’ in the template.

        return Templates.render('lytix_diary/teachers-view', context);
    })
    .then(html => {
        const container = Widget.getContentContainer(WIDGET_ID);
        container.insertAdjacentHTML('beforeend', html);
        return;
    })
    .finally(() => {
        document.getElementById(WIDGET_ID).classList.remove('loading');
    })
    .catch(error => {
        Widget.handleError(error, WIDGET_ID);
    });
};
