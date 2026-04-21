// assets/js/main.js
// אתחול פשוט: לא נדרש קוד מיוחד אם ה-<pdf-viewer> כבר מוגדר.
// נשאיר כאן הוק לעתיד, למשל קריאת ?file= מה-URL כדי להחליף on-the-fly.

(function initFromQuery() {
  try {
    const url = new URL(window.location.href);
    const file = url.searchParams.get('file');
    const el = document.getElementById('viewer');
    if (el && file) {
      // אל תאפשר http/https או ".."
      if (!/^https?:\/\//i.test(file) && file.indexOf('..') === -1) {
        // נניח שה-PHP כבר בנה ברירת מחדל. כאן אפשר היה לעדכן, אבל עדיף לתת ל-PHP לקבוע.
        // אם בכל זאת רוצים להחליף דינמית:
        // const base = el.getAttribute('data-pdf');
        // אם הבסיס כולל .../assets/loadedFiles/, ניתן לבנות מזה URL מלא:
        // לא נדרש כרגע.
      }
    }
  } catch (_) {}
})();
