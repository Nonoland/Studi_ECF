import Swal from "sweetalert2";


document.addEventListener('DOMContentLoaded', (event) => {
    if (document.forms['reservation']) {
        let submit_button = document.getElementById('reservation_submit');
        let reservation_date_start_day = document.getElementById('reservation_date_start_day');
        let reservation_date_start_month = document.getElementById('reservation_date_start_month');
        let reservation_date_start_year = document.getElementById('reservation_date_start_year');
        let reservation_date_end_day = document.getElementById('reservation_date_end_day');
        let reservation_date_end_month = document.getElementById('reservation_date_end_month');
        let reservation_date_end_year = document.getElementById('reservation_date_end_year');
        let price_span = document.getElementById('suite_price');
        let suite_price = document.getElementById('suite_price_value').value;

        document.forms['reservation'].addEventListener('change', (event) => {
            let reservation_date_start = new Date(reservation_date_start_year.value, reservation_date_start_month.value-1, reservation_date_start_day.value);
            let reservation_date_end = new Date(reservation_date_end_year.value, reservation_date_end_month.value-1, reservation_date_end_day.value);

            let diff = (reservation_date_end - reservation_date_start) / 86400000;

            if (diff >= 3) {
                submit_button.disabled = false;
            } else {
                submit_button.disabled = true;
            }

            let price = Math.round(diff) * suite_price;
            price_span.textContent = price + '€';
        });

        submit_button.disabled = true;
        submit_button.addEventListener('click', (event) => {
            let reservation_date_start = new Date(reservation_date_start_year.value, reservation_date_start_month.value-1, reservation_date_start_day.value);
            let reservation_date_end = new Date(reservation_date_end_year.value, reservation_date_end_month.value-1, reservation_date_end_day.value);

            let diff = reservation_date_end - reservation_date_start;

            if (diff < 0) {
                Swal.fire('La date du début de votre séjout doit-être inférieur à votre date de retour.');
                event.preventDefault();
            } else if (diff < 259200000) {
                Swal.fire('La durée de votre séjour doit être d\'au moins de 3 jours.');
                event.preventDefault();
            }

            if (reservation_date_start - Date.now() <= 0) {
                Swal.fire('Le début de votre réservation doit au moins commencer aujoud\'hui');
                event.preventDefault();
            }
        });



    }
});

async function sendAjaxRequest(params, done, fail) {
    await $.ajax({
        url: 'http://localhost:8080/ajax/suite/'+params,
        method: 'GET'
    }).done(done).fail(fail);
}
