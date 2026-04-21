// assets/js/pdf_viewer_wrapper.js
// רכיב <pdf-viewer> מתקדם בעברית, ללא תלות חיצונית, עם PDF.js מקומי.
// יכולות: גלילה רציפה של כל הדפים (Lazy Render), זום, התאמה לרוחב,
// קפיצה לדף, דף קודם/הבא, הורדה, הדפסה. RTL מוכלל.
// נדרש שpdf.min.js נטען לפני קובץ זה, ושה-worker יוגדר דרך data-worker.

class PdfViewerElement extends HTMLElement {
  constructor() {
    super();
    this._shadow = this.attachShadow({ mode: 'open' });

    const style = document.createElement('style');
    style.textContent = `
      :host { display:block; width:100%; box-sizing:border-box; direction: rtl; }
      .toolbar {
        display:flex; flex-wrap:wrap; gap:8px; align-items:center; justify-content:center;
        padding:10px; border:1px solid #eee; border-radius:10px; margin:8px auto 12px; max-width: 980px;
        box-shadow: 0 2px 6px rgba(0,0,0,.04);
      }
      .toolbar button, .toolbar input[type="number"], .toolbar a {
        padding:6px 10px; font-size:14px; line-height:1.2; border-radius:8px; border:1px solid #ddd; background:#fafafa; cursor:pointer;
      }
      .toolbar button:hover, .toolbar a:hover { background:#f0f0f0; }
      .toolbar .spacer { flex: 1 0 12px; }
      .pagebar { display:flex; gap:6px; align-items:center; }
      .pages { display:flex; flex-direction:column; align-items:center; gap:14px; }
      .pageCanvas {
        width: var(--page-width, 80%); max-width: 1200px; height:auto; box-shadow: 0 0 8px rgba(0,0,0,.08);
        background:#fff; border-radius:4px;
      }
      .note { text-align:center; font-size:13px; color:#666; margin-top:6px; }
      .hidden-iframe { display:none; width:0; height:0; border:0; }
    `;

    // סרגל כלים
    const toolbar = document.createElement('div');
    toolbar.className = 'toolbar';
    toolbar.innerHTML = `
      <div class="pagebar">
        <button type="button" id="prevPage" aria-label="דף קודם">דף קודם</button>
        <span>דף:</span>
        <input type="number" id="pageInput" min="1" value="1" style="width:76px" aria-label="מספר דף">
        <span id="pageCount">/ ?</span>
        <button type="button" id="nextPage" aria-label="דף הבא">דף הבא</button>
      </div>

      <div class="spacer"></div>

      <div class="zoombar">
        <button type="button" id="zoomOut" aria-label="זום החוצה">-</button>
        <button type="button" id="zoomIn" aria-label="זום פנימה">+</button>
        <button type="button" id="fitWidth" aria-label="התאם לרוחב">התאם-לרוחב</button>
        <button type="button" id="resetZoom" aria-label="איפוס זום">איפוס</button>
      </div>

      <div class="spacer"></div>

      <div class="actions">
        <a id="downloadBtn" href="#" download rel="noopener" aria-label="הורדה">הורד PDF</a>
        <button type="button" id="printBtn" aria-label="הדפסה">הדפס</button>
      </div>
    `;

    // קונטיינר לדפים
    this._pagesWrap = document.createElement('div');
    this._pagesWrap.className = 'pages';

    // Iframe נסתר להדפסה בטוחה
    this._printFrame = document.createElement('iframe');
    this._printFrame.className = 'hidden-iframe';
    this._printFrame.setAttribute('aria-hidden', 'true');

    this._shadow.append(style, toolbar, this._pagesWrap, this._printFrame);

    // refs
    this._pageInput   = toolbar.querySelector('#pageInput');
    this._pageCountEl = toolbar.querySelector('#pageCount');
    this._zoomInBtn   = toolbar.querySelector('#zoomIn');
    this._zoomOutBtn  = toolbar.querySelector('#zoomOut');
    this._fitWidthBtn = toolbar.querySelector('#fitWidth');
    this._resetZoomBtn= toolbar.querySelector('#resetZoom');
    this._prevBtn     = toolbar.querySelector('#prevPage');
    this._nextBtn     = toolbar.querySelector('#nextPage');
    this._downloadBtn = toolbar.querySelector('#downloadBtn');
    this._printBtn    = toolbar.querySelector('#printBtn');

    // מצב פנימי
    this._pdfDoc     = null;
    this._numPages   = 0;
    this._scale      = 1.2;
    this._baseWidth  = 0; // יאוכלס אחרי הדף הראשון
    this._canvases   = []; // [{canvas, rendered:boolean}]
    this._observer   = null;

    // אירועים
    this._zoomInBtn.addEventListener('click', () => this._zoom(+0.1));
    this._zoomOutBtn.addEventListener('click', () => this._zoom(-0.1));
    this._fitWidthBtn.addEventListener('click', () => this._fitToWidth());
    this._resetZoomBtn.addEventListener('click', () => this._resetZoom());

    this._prevBtn.addEventListener('click', () => this._gotoPage((this._currentPage() - 1)));
    this._nextBtn.addEventListener('click', () => this._gotoPage((this._currentPage() + 1)));
    this._pageInput.addEventListener('change', (e) => {
      const v = parseInt(e.target.value || '1', 10);
      this._gotoPage(v);
    });

    this._printBtn.addEventListener('click', () => this._print());
  }

  static get observedAttributes() {
    return ['data-pdf', 'data-worker'];
  }

  attributeChangedCallback(name, oldVal, newVal) {
    if ((name === 'data-pdf' || name === 'data-worker') && this.isConnected) {
      this._init();
    }
  }

  connectedCallback() {
    this._init();
  }

  // ===== Init / Load =====
  async _init() {
    const pdfUrl   = this.getAttribute('data-pdf');
    const workerUrl= this.getAttribute('data-worker');
    if (!pdfUrl) return;

    // קבע כפתור הורדה:
    if (this._downloadBtn) {
      this._downloadBtn.href = pdfUrl;
    }

    // הגדרת worker מקומי
    const pdfjsLib = window['pdfjs-dist/build/pdf'];
    if (!pdfjsLib) {
      console.error('PDF.js לא נטען. ודא pdf.min.js לפני wrapper.');
      return;
    }
    if (workerUrl) {
      pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrl;
    }

    // ניקוי קודם
    this._pagesWrap.innerHTML = '';
    this._canvases = [];
    this._numPages = 0;
    if (this._observer) {
      this._observer.disconnect();
      this._observer = null;
    }

    try {
      this._pdfDoc = await pdfjsLib.getDocument({ url: pdfUrl }).promise;
      this._numPages = this._pdfDoc.numPages;
      this._updatePageUI(1, this._numPages);

      // יצירת קנבסים לכל הדפים (עדיין בלי רינדור)
      for (let i = 1; i <= this._numPages; i++) {
        const c = document.createElement('canvas');
        c.className = 'pageCanvas';
        c.setAttribute('data-page', String(i));
        c.width = 1; c.height = 1; // יוגדרו ברינדור
        this._pagesWrap.appendChild(c);
        this._canvases.push({ canvas: c, rendered: false });
      }

      // רנדר Lazy: כשדף נכנס לפריים
      this._observer = new IntersectionObserver((entries) => {
        for (const entry of entries) {
          if (entry.isIntersecting) {
            const canvas = entry.target;
            const pageNum = parseInt(canvas.getAttribute('data-page') || '1', 10);
            this._renderPage(pageNum);
          }
        }
      }, { root: null, rootMargin: '200px 0px', threshold: 0 });

      this._canvases.forEach(obj => this._observer.observe(obj.canvas));

      // גלילה לדף 1
      this._scrollToPage(1);

    } catch (err) {
      console.error('שגיאה בטעינת PDF:', err);
      this._showNote('אירעה שגיאה בטעינת המסמך. אפשר להוריד את הקובץ ולפתוח ידנית.');
    }
  }

  // ===== Rendering =====
  async _renderPage(num) {
    if (!this._pdfDoc) return;
    if (num < 1 || num > this._numPages) return;
    const rec = this._canvases[num - 1];
    if (!rec || rec.rendered) return;

    try {
      const page = await this._pdfDoc.getPage(num);

      // לפי זום נוכחי: נגדיר viewport
      const viewport = page.getViewport({ scale: this._scale });

      // בסיס התאמה לרוחב נשמר מהדף הראשון
      if (!this._baseWidth) {
        this._baseWidth = viewport.width;
      }

      const canvas = rec.canvas;
      const ctx = canvas.getContext('2d', { alpha: false });

      canvas.width  = Math.floor(viewport.width);
      canvas.height = Math.floor(viewport.height);

      const renderContext = { canvasContext: ctx, viewport };
      await page.render(renderContext).promise;

      rec.rendered = true;
    } catch (e) {
      console.error('שגיאה ברינדור דף', num, e);
    }
  }

  // ===== UI helpers =====
  _updatePageUI(current, total) {
    if (this._pageInput) {
      this._pageInput.value = String(current);
      this._pageInput.max   = String(total);
      this._pageInput.min   = '1';
    }
    if (this._pageCountEl) {
      this._pageCountEl.textContent = '/ ' + String(total);
    }
  }

  _currentPage() {
    // הערכת “הדף המרכזי” על פי האלמנט הקרוב לאמצע המסך
    const midY = window.scrollY + window.innerHeight / 2;
    let best = 1;
    let bestDist = Infinity;

    this._canvases.forEach((rec, idx) => {
      const r = rec.canvas.getBoundingClientRect();
      const center = window.scrollY + r.top + (r.height / 2);
      const d = Math.abs(center - midY);
      if (d < bestDist) { bestDist = d; best = idx + 1; }
    });
    return best;
  }

  _scrollToPage(num) {
    const rec = this._canvases[num - 1];
    if (!rec) return;
    rec.canvas.scrollIntoView({ behavior: 'smooth', block: 'start' });
    this._updatePageUI(num, this._numPages);
  }

  _gotoPage(num) {
    if (!this._numPages) return;
    const n = Math.min(Math.max(1, num), this._numPages);
    this._scrollToPage(n);
  }

  _zoom(delta) {
    this._scale = Math.max(0.2, this._scale + delta);
    this._rerenderAllVisible();
  }

  _resetZoom() {
    this._scale = 1.2;
    this._rerenderAllVisible(true);
  }

  _fitToWidth() {
    // קבע רוחב דף ל-100% מהמסך (ויזואלי), וננסה לכייל את ה-scale כדי לקבל איכות טובה
    this.style.setProperty('--page-width', '100%');
    // בעקרון ה-scale משפיע על רזולוציית הקנבס. נגדיל מעט לאיכות:
    this._scale = Math.max(this._scale, 1.4);
    this._rerenderAllVisible(true);
  }

  _rerenderAllVisible(force = false) {
    // “ביטול” רינדור לקנבס גלוי כדי שיירנדר מחדש בזום החדש
    const viewportTop = window.scrollY - 200;
    const viewportBottom = window.scrollY + window.innerHeight + 200;

    this._canvases.forEach((rec) => {
      const rect = rec.canvas.getBoundingClientRect();
      const top = window.scrollY + rect.top;
      const bottom = top + rect.height;

      const visible = !(bottom < viewportTop || top > viewportBottom);
      if (visible || force) {
        rec.rendered = false; // יסומן לרינדור מחדש
        if (this._observer) this._observer.unobserve(rec.canvas); // טריק קטן לטריגר
        if (this._observer) this._observer.observe(rec.canvas);
      }
    });
  }

  _showNote(text) {
    let note = this._shadow.querySelector('.note');
    if (!note) {
      note = document.createElement('div');
      note.className = 'note';
      this._shadow.append(note);
    }
    note.textContent = text;
  }

  _print() {
    const pdfUrl = this.getAttribute('data-pdf');
    if (!pdfUrl) return;

    // הדפסה בטוחה דרך iFrame מקומי
    const frame = this._printFrame;
    frame.onload = () => {
      try {
        frame.contentWindow.focus();
        frame.contentWindow.print();
      } catch (_) {}
    };
    // שימוש ב-timeout קטן מבטיח טריגר הדפסה בדפדפנים “רגישים”
    frame.src = pdfUrl + (pdfUrl.includes('?') ? '&' : '?') + 'print=1';
  }
}

customElements.define('pdf-viewer', PdfViewerElement);
