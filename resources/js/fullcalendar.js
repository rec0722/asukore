document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        businessHours: true,
        buttonText: {
            today: '今月'
        },
        editable: true,
        height: 'auto',
        initialView: 'dayGridMonth',
        locale: 'ja',
        timeZone: 'Asia/Tokyo',
        selectable: false,
        dateClick: function(obj) {
            let request = document.getElementById('report_date');
            request.value = obj.dateStr;
            let form = document.forms.dashboard;
            form.submit();
        }
    });
    calendar.render();
});