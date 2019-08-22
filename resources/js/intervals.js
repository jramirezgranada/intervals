$(document).ready(function () {
    getAllIntervals();

    $("#add-interval").on("click", function () {
        addInterval();
    });

    $("#truncate-interval").on("click", function () {
        if (confirm("Do you want truncate intervals table?") === true) {
            truncateTable();
        }
    })

    $("#body").on("click", ".delete-interval", function () {
        if (confirm("Do you want delete this interval?") === true) {
            deleteInterval($(this).attr('data-id'));
        }
    })
});

function getAllIntervals() {
    $("#interval-list-table").empty();

    $.ajax({
        url: '/api/intervals',
        method: 'get'
    }).done(function (data) {
        let tableElements = '';
        let counter = 1;

        data.forEach(function (interval) {
            tableElements += `<tr>`;
            tableElements += `<th scope="row">${counter}</th>`;
            tableElements += `<td>${interval.start_date.replace('00:00:00', '')}</td>`;
            tableElements += `<td>${interval.end_date.replace('00:00:00', '')}</td>`;
            tableElements += `<td>${interval.price}</td>`;
            tableElements += `<td><a class="btn btn-danger delete-interval" data-id="${interval.id}" href="#">Delete</a></td>`;
            counter++;
        })

        $("#interval-list-table").append(tableElements);
    })

}

function addInterval() {
    let data = {
        start_date: $("#start_date").val(),
        end_date: $("#end_date").val(),
        price: $("#price").val(),
    }

    $.ajax({
        url: '/api/intervals',
        method: 'POST',
        data: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json"
        },
        dataType: "JSON",
    }).done(function (data) {
        let errorsDiv = $(".errors");
        errorsDiv.empty();
        errorsDiv.hide();

        if (data.status === "error") {
            let errorsMsg = '';
            let errors = data.data;

            $.each(errors, function (field, errors) {
                errors.forEach(function (error) {
                    errorsMsg += `<li>${error}</li>`
                })
            });

            errorsDiv.append(errorsMsg).show();

        } else {
            $(".form-control").val('');
            getAllIntervals()
        }
    })
}

function truncateTable() {
    $.ajax({
        url: '/api/truncate-intervals',
        method: 'get'
    }).done(function (data) {
        getAllIntervals()
    })

}

function deleteInterval(intervalId) {
    $.ajax({
        url: `/api/intervals/${intervalId}`,
        method: 'delete'
    }).done(function (data) {
        getAllIntervals();
    })
}