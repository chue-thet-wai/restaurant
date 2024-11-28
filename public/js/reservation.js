$(document).ready(function () {
    reservationRejectNote();

    $('#reservatoin_edit #status').on('change', function () {
        reservationRejectNote();
    });

    const $branchSelect = $('.create-reservation #branch');
    const $dateInput = $('.create-reservation #date');
    const $timeSelect = $('.create-reservation #time');
    const $tableSelect = $('.create-reservation #table');

    $branchSelect.on('change', function () {
        updateTimeSlots($branchSelect, $dateInput, $timeSelect);
    });

    $dateInput.on('change', function () {
        updateTimeSlots($branchSelect, $dateInput, $timeSelect);
    });

    $timeSelect.on('change', function () {
        updateTables($branchSelect, $dateInput, $timeSelect, $tableSelect);
    });

    $branchSelect.on('change', function () {
        updateTables($branchSelect, $dateInput, $timeSelect, $tableSelect);
    });

    $dateInput.on('change', function () {
        updateTables($branchSelect, $dateInput, $timeSelect, $tableSelect);
    });
});

function updateTimeSlots($branchSelect, $dateInput, $timeSelect) {
    const branch = $branchSelect.val();
    const date = $dateInput.val();

    if (branch && date) {
        $.ajax({
            url: '/get-available-times',
            type: 'GET',
            data: {
                branch: branch,
                date: date,
            },
            success: function (data) {
                $timeSelect.empty();
                if (data.times && data.times.length) {
                    $.each(data.times, function (index, time) {
                        const formattedTime = formatTime(time);
                        const option = $('<option></option>')
                            .val(time)
                            .text(formattedTime);
                        $timeSelect.append(option);
                    });
                } else {
                    $timeSelect.append(
                        $('<option></option>')
                            .val('')
                            .text('No available times')
                            .prop('disabled', true)
                    );
                }
                $timeSelect.trigger('change'); // Ensure tables update after times change
            },
            error: function (xhr, status, error) {
                console.error('Error fetching available times:', error);
            },
        });
    }
}

function updateTables($branchSelect, $dateInput, $timeSelect, $tableSelect) {
    const branch = $branchSelect.val();
    const date = $dateInput.val();
    const time = $timeSelect.val();

    if (branch && date && time) {
        $.ajax({
            url: '/get-available-tables',
            type: 'GET',
            data: {
                branch: branch,
                date: date,
                time: time,
            },
            success: function (data) {
                console.log(data);
                $tableSelect.empty();
                if (data.tables && data.tables.length) {
                    $.each(data.tables, function (index, table) {
                        const option = $('<option></option>')
                            .val(table.id)
                            .text(table.table_name);
                        $tableSelect.append(option);
                    });
                } else {
                    $tableSelect.append(
                        $('<option></option>')
                            .val('')
                            .text('No available tables')
                            .prop('disabled', true)
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching available tables:', error);
            },
        });
    }
}

function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour12 = hours % 12 || 12;
    const period = hours >= 12 ? 'PM' : 'AM';
    return `${hour12}:${minutes} ${period}`;
}

function reservationRejectNote() {
    const statusSelect = $('#reservatoin_edit #status').val();
    const rejectNoteRow = $('#reservatoin_edit #reject_note_row');

    if (statusSelect === '3') {
        rejectNoteRow.show();
    } else {
        rejectNoteRow.hide();
    }
}
