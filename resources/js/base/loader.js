/**
 *
 * Loader
 * Adds a spinner class to the body if the DOMContentLoaded is not fired for 500 ms.
 * This prevents seeing a spinner on an already visited page.
 *
 **/

(function () {
  window.isContentLoaded = false;
  const timeoutId = setInterval(() => {
    if (!window.isContentLoaded) {
        document.body.classList.add('spinner');
    }else{
        document.body.classList.remove('spinner');
    }
  }, 500);

  window.addEventListener('DOMContentLoaded', (event) => {
      window.isContentLoaded = true;
  });
})();
