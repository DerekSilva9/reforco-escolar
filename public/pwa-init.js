const installButtonSelector = '[data-pwa-install]';
let deferredPrompt = null;

function getInstallButtons() {
  return Array.from(document.querySelectorAll(installButtonSelector));
}

function isStandaloneMode() {
  return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
}

function setInstallButtonsVisible(visible) {
  getInstallButtons().forEach((button) => {
    button.hidden = !visible;
    button.setAttribute('aria-hidden', String(!visible));
  });
}

async function installPWA() {
  if (!deferredPrompt) {
    return;
  }

  deferredPrompt.prompt();
  await deferredPrompt.userChoice;
  deferredPrompt = null;
  setInstallButtonsVisible(false);
}

if ('serviceWorker' in navigator) {
  window.addEventListener('load', async () => {
    try {
      await navigator.serviceWorker.register('/service-worker.js', { scope: '/' });
    } catch (error) {
      console.warn('[PWA] Service Worker registration failed:', error);
    }
  });

  let refreshing = false;
  navigator.serviceWorker.addEventListener('controllerchange', () => {
    if (refreshing) {
      return;
    }

    refreshing = true;
    window.location.reload();
  });
}

window.addEventListener('beforeinstallprompt', (event) => {
  event.preventDefault();
  deferredPrompt = event;

  if (!isStandaloneMode()) {
    setInstallButtonsVisible(true);
  }
});

window.addEventListener('appinstalled', () => {
  deferredPrompt = null;
  setInstallButtonsVisible(false);
});

document.addEventListener('click', (event) => {
  const trigger = event.target.closest(installButtonSelector);
  if (!trigger) {
    return;
  }

  event.preventDefault();
  installPWA();
});

document.addEventListener('DOMContentLoaded', () => {
  setInstallButtonsVisible(false);

  if (isStandaloneMode()) {
    document.documentElement.classList.add('pwa-installed');
  }
});

window.installPWA = installPWA;
