# Scroll to Top

Adds a button that scrolls the page back to the top.

The button stays hidden until the visitor has scrolled one viewport height. On short pages that never reach that point, it does not appear. Clicking it scrolls smoothly back to the top.

## Requirements

- WordPress 6.0 or later
- PHP 8.0 or later

## Installation

1. Install dependencies and build the assets:

   ```bash
   composer install
   npm install
   npm run build
   ```

2. Copy the plugin into `wp-content/plugins/`, or work from this directory if it is already there.
3. Activate **Scroll to Top** in the WordPress admin.

`vendor/` and `dist/` are not in the repository. Both commands above are required before the plugin can run.

## Settings

Open **Settings → Scroll to Top** to change:

- **Background color**, the button background
- **Icon color**, the chevron
- **Distance from the right** and **distance from the bottom**, in pixels

These values are stored in the `lcfdev_scroll_to_top` option. If they have not been saved yet, the button uses black, white, and a 24 px offset from each edge.

## Development

Rebuild assets while you edit CSS or JavaScript:

```bash
npm run watch
```

Frontend source lives in `resources/`. PHP lives in `src/`.

## License

GPL-2.0-or-later
