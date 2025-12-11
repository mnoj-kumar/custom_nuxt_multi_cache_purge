# Custom Nuxt Multi Cache Purge

## Overview
`custom_nuxt_multi_cache_purge` is a custom Drupal module that integrates with Nuxt applications to purge cached content using tags. It is fully compatible with multi-domain configurations.

## Features
- Supports multi-domain cache purging.
- Works with both Nuxt 2 and Nuxt 3.
- Purges cached content based on URL alias.
- Configurable frontend URL, purge endpoint, and authentication token per domain.
- Uses Drupal's configuration system for easy management.

## Installation

1. Enable the module using Drush:
   ```sh
   drush en custom_nuxt_multi_cache_purge -y
   ```

2. Clear the cache:
   ```sh
   drush cache:rebuild
   ```

## Configuration

1. Go to `/admin/config/system/nuxt-cache-purge`.
2. Configure the following settings:
   - **Frontend URL** (Base URL of your Nuxt application)
   - **Purge Endpoint** (API endpoint for cache purging)
   - **Authentication Token** (Token used to authenticate cache purge requests)
3. Save the configuration.

## Purge API Endpoints

Use the following endpoints based on your Nuxt version:

- **Nuxt 3**: `/api/nuxt-multi-cache/purge/tags`
- **Nuxt 2**: `/__nuxt_multi_cache/purge/tags`

## Usage

This module automatically purges the Nuxt cache when content changes in Drupal. You can also manually trigger a cache purge using the provided service:

```php
\Drupal::service('custom_nuxt_multi_cache_purge.purger')->purge('/node/123');
```

## Troubleshooting

- Ensure the correct API endpoint is configured for your Nuxt version.
- Check the Drupal logs (`admin/reports/dblog`) for any errors related to cache purging.
- Verify that the domain settings are correctly applied if using a multi-domain setup.

## License
This module is open-source and licensed under the MIT License.

