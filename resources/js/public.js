import '../css/app.css';

import Alpine from 'alpinejs';
import pdfViewer from './pdf-viewer';

Alpine.data('pdfViewer', pdfViewer);

Alpine.data('googleMapsConsent', () => ({
    consentGiven: false,
    checkboxChecked: false,
    init() {
        this.consentGiven = localStorage.getItem('gmaps_consent') === 'true';
    },
    loadMap() {
        localStorage.setItem('gmaps_consent', 'true');
        this.consentGiven = true;
    },
}));

window.Alpine = Alpine;
Alpine.start();
