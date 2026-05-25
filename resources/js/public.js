import '../css/app.css';

import Alpine from 'alpinejs';
import pdfViewer from './pdf-viewer';

Alpine.data('pdfViewer', pdfViewer);

window.Alpine = Alpine;
Alpine.start();
