const BASE = '/js';

let pdfjsLibPromise = null;

function loadPdfjsLib() {
    if (!pdfjsLibPromise) {
        pdfjsLibPromise = new Promise((resolve, reject) => {
            if (window.pdfjsLib) {
                window.pdfjsLib.GlobalWorkerOptions.workerSrc = `${BASE}/pdf.worker.js`;
                resolve(window.pdfjsLib);
                return;
            }
            const script = document.createElement('script');
            script.src = `${BASE}/pdf.js`;
            script.onload = () => {
                window.pdfjsLib.GlobalWorkerOptions.workerSrc = `${BASE}/pdf.worker.js`;
                resolve(window.pdfjsLib);
            };
            script.onerror = () => reject(new Error('Failed to load PDF.js'));
            document.head.appendChild(script);
        });
    }
    return pdfjsLibPromise;
}

export default function (url) {
    let pdfDoc = null;
    let currentPage = 1;
    let totalPages = 0;

    return {
        page: 1,
        numPages: 0,
        loading: true,
        error: null,
        scale: 1.5,

        async init() {
            if (!url) return;
            try {
                this.loading = true;
                const pdfjsLib = await loadPdfjsLib();

                const loadingTask = pdfjsLib.getDocument({
                    url: url,
                    disableRange: true,
                    disableStream: true,
                    disableAutoFetch: true,
                    disableFontFace: true,
                });
                pdfDoc = await loadingTask.promise;
                totalPages = pdfDoc.numPages;
                this.numPages = totalPages;
                this.loading = false;
                await this.$nextTick();
                await this.renderPage();
                window.addEventListener('keydown', this.onKeyDown);
            } catch (e) {
                console.error('PDF.js error:', e);
                this.error = `Error: ${e.message}${e.name ? ' (' + e.name + ')' : ''}`;
                this.loading = false;
            }
        },

        destroy() {
            window.removeEventListener('keydown', this.onKeyDown);
        },

        onKeyDown(e) {
            if (e.key === 'ArrowLeft') this.prevPage();
            if (e.key === 'ArrowRight') this.nextPage();
        },

        async renderPage() {
            if (!pdfDoc) return;
            try {
                const page = await pdfDoc.getPage(currentPage);
                const viewport = page.getViewport({ scale: this.scale });
                const canvas = this.$refs.canvas;
                const ctx = canvas.getContext('2d');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                await page.render({ canvasContext: ctx, viewport }).promise;
            } catch (e) {
                console.error('Render error:', e);
                this.error = `Render error: ${e.message}`;
            }
        },

        async nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                this.page = currentPage;
                await this.renderPage();
            }
        },

        async prevPage() {
            if (currentPage > 1) {
                currentPage--;
                this.page = currentPage;
                await this.renderPage();
            }
        },
    };
}
