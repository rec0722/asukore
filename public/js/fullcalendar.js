document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        timeZone: 'Asia/Tokyo',
        locale: 'ja',
        height: 'auto',
        businessHours: true,
        editable: true,
        buttonText: {
            today: '今月'
        },
        initialView: 'dayGridMonth',
    });
    calendar.render();
});