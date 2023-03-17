$(document).ready(function () {
    $('#users-datatable').DataTable({
        responsive: true,
        "columnDefs": [
            {
                "width": "0",
                "targets": 5
            },
            {
                "width": "0",
                "targets": 6
            },
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 2, targets: 3},
            { responsivePriority: 2, targets: 4},
            { responsivePriority: 2, targets: 5},
            { responsivePriority: 1, targets: 6},
        ]
    });

});