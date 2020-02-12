/*
 *
 *  * @package   yii2-easy-ajax
 *  * @author    Gianpaolo Scrigna <letsjump@gmail.com>
 *  * @link https://github.com/letsjump/yii2-easy-ajax
 *  * @copyright Copyright &copy; Gianpaolo Scrigna, beintech.it, 2017-2020
 *  * @version   1.0.0
 *
 */

yea_options.yea_extends + {
    yea_fullcalendar_refetch:      function (data) {
        $(data.calendar_id).fullCalendar('refetchEvents');
    },
    yea_fullcalendar_event_remove: function (data) {
        $(data.calendar_id).fullCalendar('removeEvents', data.event_id);
    }
}
