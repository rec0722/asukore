/* ===================================================================

 * report

=================================================================== */
$(function() {
    // 時間報告の行追加
    $(document).on('click', '.inputType', function(obj) {
        let itemName = obj.target.name;
        if (itemName === 'input_free') {
            var box = $('#free-box');
        } else if (itemName === 'input_time') {
            var box = $('#time-box');
        } else if (itemName === 'input_pic') {
            var box = $('#pic-box');
        }
        if (obj.target.checked === true) {
            box.css({ 'display': 'block' });
        } else {
            box.css({ 'display': 'none' });
        }
    });
    // 時間報告の行追加
    $(document).on('click', '.addItem', function() {
        let itemV = document.getElementById('getItemNum').value; // 個数を取得
        let tableBody = $("#tableAction tbody");
        let trLast = tableBody.find("tr:last");
        let clone = trLast.clone(true);
        // inputを複製
        clone.find('.id input:hidden').attr('name', 'action_list[' + itemV + '][id]').val('');
        clone.find('.input1 input').attr('name', 'action_list[' + itemV + '][time1]');
        clone.find('.input1 input').attr('id', 'time1_' + itemV).val('');
        clone.find('.input2 input').attr('name', 'action_list[' + itemV + '][time2]');
        clone.find('.input2 input').attr('id', 'time2_' + itemV).val('');
        clone.find('.input3 input').attr('name', 'action_list[' + itemV + '][customer]').val('');
        clone.find('.input4 textarea').attr('name', 'action_list[' + itemV + '][action]').css({ 'height': 'none' }).val('');
        clone.find('.input5 textarea').attr('name', 'action_list[' + itemV + '][approach]').css({ 'height': 'none' }).val('');
        clone.find('.button input').attr('name', 'action_list[' + itemV + '][delete_flg]');
        clone.find('.button input').attr('id', 'flg' + itemV).val('');
        clone.find('.button button').attr('id', itemV);
        // 最後尾に複製した行を追加
        clone.appendTo("#tableAction tbody");
        let tableRow = $('#tableAction').children('tbody').children('tr').eq(itemV);
        tableRow.css({ 'display': 'flex' });
        // itemNumのValueを変更
        itemV++;
        document.getElementById('getItemNum').value = itemV;
    });
    // 時間報告の行削除
    $(document).on('click', '.deleteItem', function(obj) {
        let itemV = document.getElementById('getItemNum').value; // 個数を取得
        if (itemV !== '1') {
            const id = $(this).attr('id'); // 個数を取得
            // 最後尾の行を複製
            let tableRow = $('#tableAction').children('tbody').children('tr').eq(id);
            tableRow.css({ 'display': 'none' });
            let deleteFlg = document.getElementById('flg' + id);
            deleteFlg.value = '1';
        }
    });
    // 画像の削除
    $(document).on('click', '.deleteImg', function(obj) {
        let id = obj.target.parentNode.id;
        if (id !== '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
            });
            $.ajax({
                    //POST通信
                    type: 'POST',
                    url: '/delete-img',
                    dataType: 'json',
                    data: {
                        id: id,
                    },
                })
                //通信が成功したとき
                .done(function(data) {
                    let item = $('#block' + id);
                    item.remove();
                    alert('画像を削除しました。');
                })
                //通信が失敗したとき
                .fail((error) => {
                    console.log(error.statusText);
                });
        };
    });
});

/* ===================================================================

 * master

=================================================================== */
$(function() {
    // 会社選択時、部署変更
    $(document).on('change', '#select-company', function() {
        let companyId = document.getElementById('select-company').value; //会社IDを取得
        if (companyId !== '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
            });
            $.ajax({
                    //POST通信
                    type: 'POST',
                    url: '/get_dept',
                    dataType: 'json',
                    data: {
                        company_id: companyId,
                    },
                })
                //通信が成功したとき
                .done(function(data) {
                    let dept = document.getElementById('change-dept');
                    if (dept.hasChildNodes()) {
                        while (dept.childNodes.length > 0) {
                            dept.removeChild(dept.firstChild)
                        }
                    }
                    $.each(data, function(index, value) {
                        let id = value.id;
                        let name = value.name;
                        var option = document.createElement('option');
                        option.value = id;
                        option.text = name;
                        dept.appendChild(option);
                    });
                })
                //通信が失敗したとき
                .fail((error) => {
                    console.log(error.statusText);
                })
        };
    });

    // 役職の「役員」を選択時、閲覧権限を表示
    $(document).on('click', '.select-role', function() {
        let role = $('.select-role:checked').val();
        let dept = document.getElementById('userDept');
        if (role === '8') {
            $(dept).removeClass('display-none');
        } else {
            $(dept).addClass('display-none');
        }
    });

    // 報告入力のタイプ別デフォルト設定
    $(document).on('click', '.mstType', function(obj) {
        let itemName = obj.target.name;
        if (itemName === 'input_free') {
            obj.tar
        } else if (itemName === 'input_time') {
            var box = $('#time-box');
        } else if (itemName === 'input_pic') {
            var box = $('#pic-box');
        }
        if (obj.target.checked === true) {
            box.css({ 'display': 'block' });
        } else {
            box.css({ 'display': 'none' });
        }
        console.log(box);
    });
});


/* ===================================================================

 * common

=================================================================== */
$(function() {
    $('select').formSelect();
    $('.dropdown-trigger').dropdown({
        coverTrigger: false,
    });
    $('.sidenav').sidenav();
    $('.datepicker').datepicker({
        autoClose: true,
        defaultDate: null,
        format: 'yyyy-mm-dd',
        firstDay: 1,
        onSelect: true,
        showMonthAfterYear: true,
        i18n: {
            months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            monthsShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            weekdays: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
            weekdaysAbbrev: ['日', '月', '火', '水', '木', '金', '土']
        }
    });
    /*
    $(this).timepicker({
        autoClose: true,
        twelveHour: false,
        options: 'step',
    });
    */
});

function TimePicker() {
    var target = '.js-time-picker';

    var now = moment();
    var dateFormat = {
        zone: 'Asia/Tokyo',
        year: now.year(),
        month: now.month(),
        day: now.day(),
        hour: now.hour(),
        minute: 0,
        second: 0
    };

    var date = moment().set(dateFormat);

    $(target).each(function() {
        var self = this;
        var id = $(self).attr('id');

        var picker = new Picker(document.getElementById(id), {
            format: 'HH:mm',
            controls: true,
            headers: true,
            date: new Date(date),
            increment: {
                minute: 10
            },
            text: {
                title: '<span>時間を選択してください</span>'
            },
        });
    })
}

$(document).on('click', '.js-time-picker', function() {
    new TimePicker();
});
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