document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', () => {
      const btn = form.querySelector('button[type="submit"], button:not([type])');
      if (btn) {
        btn.disabled = true;
        btn.textContent = 'Processing...';
      }
    });
  });
});
