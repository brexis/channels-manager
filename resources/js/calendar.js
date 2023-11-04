import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import axios from 'axios';
import * as bootstrap from 'bootstrap'

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
            document.getElementById('reservation-start').innerHTML = info.event.startStr;
            document.getElementById('reservation-end').innerHTML = info.event.endStr;
            document.getElementById('reservation-description').innerHTML = info.event.extendedProps.description;
            console.log(info.event);
            const myModal = new bootstrap.Modal(document.getElementById('show-reservation'), {});
            console.log(myModal.show());
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

    document.getElementById('listing-switch').addEventListener('change', function () {
        window.location = this.value;
    });
});
