/* ===================================================================

 * report

=================================================================== */

$(function() {
    $(document).on('click', '.addItem', function(e) {
        e.preventDefault();
        let itemV = document.getElementById('getItemNum').value; // 個数を取得
        let tableBody = $("#tableAction tbody");
        let trLast = tableBody.find("tr:last");
        let clone = trLast.clone();
        // inputを複製
        clone.find('.id input:hidden').attr('name', 'action_list[' + itemV + '][id]').val('');
        clone.find('.input1 input').attr('name', 'action_list[' + itemV + '][time1]').val('');
        clone.find('.input2 input').attr('name', 'action_list[' + itemV + '][time2]').val('');
        clone.find('.input3 input').attr('name', 'action_list[' + itemV + '][customer]').val('');
        clone.find('.input4 input').attr('name', 'action_list[' + itemV + '][action]').val('');
        clone.find('.input5 input').attr('name', 'action_list[' + itemV + '][approach]').val('');
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

    $(document).on('click', '.deleteItem', function() {
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
});



/* ===================================================================

 * common

=================================================================== */
$(function() {
    $('.dropdown-trigger').dropdown({
        coverTrigger: false,
    });
    $('.sidenav').sidenav();
    $('.datepicker').datepicker({
        autoClose: true,
        defaultDate: null,
        format: 'yyyy-mm-dd',
        firstDay: 1,
        showMonthAfterYear: true,
        i18n: {
            months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            monthsShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            weekdays: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
            weekdaysAbbrev: ['日', '月', '火', '水', '木', '金', '土']
        }
    });
});