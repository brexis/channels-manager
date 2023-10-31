import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import iCalendarPlugin from '@fullcalendar/icalendar'
import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {
    const exportListing = document.getElementById('export-listing')
    exportListing.addEventListener('show.bs.modal', event => {
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

        listingSources.addEventListener('change', function() {
            let value = `${window.APP_URL}/listings/${listingId}/ical`;

            if (this.value) {
                value += `?without=${this.value}`;
            }

            listingUrl.value = value;
        });

        listingUrl.value = `${window.APP_URL}/listings/${listingId}/ical`;
    });

    var calendarEl = document.getElementById('calendar');

    if (!calendarEl) {
        return;
    }

    var calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, dayGridPlugin, iCalendarPlugin],
        initialView: 'dayGridMonth',
        selectable: true,
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
        ]
    });

    calendar.on('select', (e) => {
        const title = window.prompt('New event');
        if (!title) {
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
