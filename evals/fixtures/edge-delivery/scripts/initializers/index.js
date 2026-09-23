import initializeProductDetails from './product-details.js';

export default function init() {
  document
    .querySelectorAll('.product-details-dropin:not([data-initialized])')
    .forEach((mount) => {
      mount.dataset.initialized = 'true';
      initializeProductDetails(mount, { sku: mount.dataset.sku });
    });
}
