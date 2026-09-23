/**
 * Mount and configure the Commerce product-details drop-in.
 * Slot/renderer changes inside @dropins/storefront-product-details belong in
 * Adobe's storefront drop-in skills / dropins MCP — not forked here.
 */

const DEFAULT_OPTIONS = {
  hideShortDescription: false,
};

function buildDropinOptions(config) {
  const options = { ...DEFAULT_OPTIONS, sku: config.sku };

  if (config['hide-short-description'] === 'true') {
    options.hideShortDescription = true;
  }

  if (config.anchors) {
    options.anchors = config.anchors.split(',').map((a) => a.trim()).filter(Boolean);
  }

  return options;
}

export default async function initializeProductDetails(container, config = {}) {
  const sku = config.sku || container.dataset.sku;
  if (!sku) {
    return;
  }

  const options = buildDropinOptions({ ...config, sku });

  try {
    const { render } = await import('@dropins/storefront-product-details/render.js');
    await render(container, options);
  } catch {
    // Synthetic fixture: no vendored drop-in packages — expose mount + config for tests.
    container.dataset.dropin = 'product-details';
    container.dataset.config = JSON.stringify(options);
    container.textContent = `product-details (${sku})`;
  }
}
