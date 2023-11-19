import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import axios from 'axios';
import * as bootstrap from 'bootstrap';
import dayjs from 'dayjs';
import 'dayjs/locale/fr';
import localizedFormat from 'dayjs/plugin/localizedFormat';
import utc from 'dayjs/plugin/utc';
import ClipboardJS from 'clipboard';

dayjs.extend(utc);
dayjs.extend(localizedFormat);
dayjs.locale('fr');

document.addEventListener('DOMContentLoaded', function () {
    const exportListing = document.getElementById('export-listing')
    exportListing?.addEventListener('show.bs.modal', event => {
        const listingId = event.relatedTarget.dataset.listingId;

        const listingSources = document.getElementById('listing-sources');
        const listingUrl = document.getElementById('listing-url-input');
        axios({
            method: 'get',
            url: `/listings/${listingId}/sources`
        }).then(function (response) {
            const sources = JSON.parse(response.data);
            listingSources.replaceChildren();
            const defaultSource = document.createElement('option');
            defaultSource.text = 'Inclure toutes les sources';
            defaultSource.value = '';
            listingSources.appendChild(defaultSource);

            sources.forEach(source => {
                const option = document.createElement('option');
                option.text = source.url;
                option.value = source.id;
                listingSources.appendChild(option);
            });
        });

        const defaultExportUrl = `${window.APP_URL}/listings/${listingId}/ical`;
        listingUrl.value = defaultExportUrl;

        listingSources.addEventListener('change', function() {
            let value = defaultExportUrl;

            if (this.value) {
                value += `?without=${this.value}`;
            }

            listingUrl.value = value;
        });
    });

    // Copy
    let clipboardElmt = new ClipboardJS('.btn-copy');
    clipboardElmt.on('success', (e) => {
        const tooltip = new bootstrap.Tooltip(e.trigger, {
            title: 'Copié',
            placement: 'bottom'
        });
        tooltip.show();
    })

    var calendarEl = document.getElementById('calendar');

    if (!calendarEl) {
        return;
    }

    var calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, dayGridPlugin],
        initialView: 'dayGridMonth',
        selectable: window.authenticated && window.listing,
        locale: 'fr',
        eventSources: [
            {
                events: window.source_events,
                color: 'yellow',    // an option!
                textColor: 'black'  // an option!
            },
            {
                events: window.reservations
            }
        ],
        eventClassNames: 'event-class',
        eventClick: function(info) {
            document.getElementById('reservation-title').innerHTML = info.event.title;
            document.getElementById('reservation-start').innerHTML = dayjs(info.event.startStr).utc().format('LLLL');
            document.getElementById('reservation-end').innerHTML = dayjs(info.event.endStr).utc().format('LLLL');
            document.getElementById('reservation-description').innerHTML = info.event.extendedProps.description;
            document.getElementById('reservation-nights').innerHTML = info.event.extendedProps.nights + ' Nuits';
            const a = document.getElementById('reservation-edit');

            if (info.event.extendedProps.reservationId) {
                a.classList.remove('d-none');
                a.href = `${window.APP_URL}/listings/${window.listing.id}/reservations/${info.event.extendedProps.reservationId}/edit`;
            } else {
                a.classList.add('d-none');
            }
            const myModal = new bootstrap.Modal(document.getElementById('show-reservation'), {});
            myModal.show();
        }
    });

    calendar.on('select', (e) => {
        const title = window.prompt('New event');
        if (!title) {
            return;
        }

        if (!window.authenticated) {
            return;
        }

        if (!window.listing) {
            return;
        }

        axios({
            method: 'post',
            url: `/listings/${window.listing.id}/reservations`,
            data: {
                name: title,
                started_at: e.startStr,
                ended_at: e.endStr
            }
        }).then(function (response) {
            calendar.addEventSource([{ title, start: e.startStr, end: e.endStr }]);
        }).catch(function (error) {
            console.log(error);
        });
    });

    calendar.render();

    const listingSwitch = document.getElementById('listing-switch');
    listingSwitch?.addEventListener('change', function () {
        window.location = this.value;
    });
});
