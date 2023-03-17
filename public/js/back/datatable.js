$(document).ready(function () {
    $('#users-datatable').DataTable({
        language: {
            search: "Rechercher&nbsp;:",
            lengthMenu:    "Afficher _MENU_ utilisateurs",
            info:           "Affichage des utilisateurs _START_ &agrave; _END_ sur _TOTAL_ utilisateurs",
            infoEmpty:      "Affichage des utilisateurs 0 &agrave; 0 sur 0 utilisateurs",
            infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            paginate: {
                first: "Premier",
                previous: "Pr&eacute;c&eacute;dent",
                next: "Suivant",
                last: "Dernier"

            },
        },
        responsive: true,
        "columnDefs": [
            {
                "width": "0",
                "targets": 5
            },
            {
                "width": "0",
                ordering:  false,
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