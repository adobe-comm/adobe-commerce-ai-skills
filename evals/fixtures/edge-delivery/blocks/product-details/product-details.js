import initializeProductDetails from '../../scripts/initializers/product-details.js';
import './product-details.css';

function readBlockConfig(block) {
  const config = { sku: block.dataset.sku || '' };

  [...block.children].forEach((row) => {
    const cols = [...row.children];
    if (cols.length < 2) {
      return;
    }
    const key = cols[0].textContent.trim().toLowerCase().replace(/\s+/g, '-');
    config[key] = cols[1].textContent.trim();
  });

  return config;
}

export default async function decorate(block) {
  const config = readBlockConfig(block);

  block.classList.add('product-details');
  block.replaceChildren();

  if (config['promo-badge']) {
    const badge = document.createElement('p');
    badge.className = 'product-details-promo-badge';
    badge.textContent = config['promo-badge'];
    block.append(badge);
  }

  const mount = document.createElement('div');
  mount.className = 'product-details-dropin';
  if (config.sku) {
    mount.dataset.sku = config.sku;
  }
  block.append(mount);

  await initializeProductDetails(mount, config);
}
