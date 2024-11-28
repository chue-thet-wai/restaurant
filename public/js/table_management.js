$(document).ready(function () {
    function updateDateDisplay(date) {
        $('#selectedDate').text(date.format('MMMM D, YYYY'));
    }

    let selectedDate = moment();
    let selectedBranch = $('#branchFilter').val();

    $('#branchFilter').on('change', function() {
        selectedBranch = $(this).val();
        fetchTableList(); 
    });

    $('#prevDate').on('click', function() {
        selectedDate.subtract(1, 'days'); 
        if (selectedDate.isBefore(moment())) {
            selectedDate = moment(); 
        }
        updateDateDisplay(selectedDate); 
        fetchTableList(); 
    });

    $('#nextDate').on('click', function() {
        selectedDate.add(1, 'days');
        if (selectedDate.isAfter(moment().add(7, 'days'))) {
            selectedDate = moment().add(7, 'days');
        }
        updateDateDisplay(selectedDate);
        fetchTableList();
    });

    function fetchTableList() {
        $.ajax({
            url: '/admin/table-management/fetch-table-list',
            type: 'POST',
            data: { 
                date: selectedDate.format('YYYY-MM-DD'),
                branch_id: selectedBranch
            }, 
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (data) {
                $('#table-management-tbody').html(data); // assuming data is HTML for table rows
            },
            error: function (error) {
                console.error('Error fetching table list:', error);
            }
        });
    }

    //change table switch
    $('#table-management-tbody').on('change', '.table-switch', function () {
        const tableId = $(this).data('table-id');
        const timeslotId = $(this).data('timeslot-id');
        const date = selectedDate.format('YYYY-MM-DD'); 
        const isAvailable = $(this).is(':checked') ? 0 : 1;

        $.ajax({
            url: '/admin/table-management/toggle-availability',
            type: 'POST',
            data: { table_id: tableId, timeslot_id: timeslotId, date: date, is_available: isAvailable },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                console.log('Availability toggled');
            },
            error: function (error) {
                console.error('Error toggling availability:', error);
            }
        });
    });

    $('#addTableForm').submit(function (event) {
        event.preventDefault();
        console.log('Form submitted'); 
        $.ajax({
            url: '/admin/table-management/add-table',
            type: 'POST',
            data: {
                table_name: $('#table_name').val(),
                capacity: $('#capacity').val(),
                branch_id:$('#branch').val()
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                console.log('Table added successfully');
                $('#addTableModal').modal('hide');
                fetchTableList();
            },
            error: function (error) {
                console.error('Error adding table:', error);
            }
        });
    });

    $('#addTableModal').on('hidden.bs.modal', function () {
        $('#addTableForm')[0].reset();
    });

    $('#btn-cancel').click(function() {
        $('#addTableForm')[0].reset();
    });
});
