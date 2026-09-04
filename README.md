# Estatein WordPress Theme

Classic WordPress theme for the [Estatein Figma template](https://www.figma.com/community/file/1314076616839640516/real-estate-business-website-ui-template-dark-theme-produce-ui) (Growmodo assessment).

WordPress picks templates by filename. This theme is only those files, plus `functions.php` and `style.css`.

## Local setup

1. Open this folder as a [Local WP](https://localwp.com/) site (or clone into an existing Local site).
2. Symlink the theme and settings plugin if they are not already in `wp-content`:

```bash
ln -sf "$(pwd)/theme/estatein" app/public/wp-content/themes/estatein
ln -sf "$(pwd)/plugin/estatein-settings" app/public/wp-content/plugins/estatein-settings
```

3. In wp-admin:
   - Activate **Estatein** theme
   - Activate **Estatein Settings** plugin (site-wide options)
   - Install and activate [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) (free) so property fields show in the editor
   - **Settings → Permalinks → Save** (so `/properties/` works)
   - **Appearance → Menus** if the primary menu was not created automatically

On first load the theme creates Home, Contact, three demo properties, and a Primary menu (Home + Properties). Set **Settings → Reading** to the Home page if it is not already the front page.

## Theme files WordPress looks for

| File | URL |
|------|-----|
| `front-page.php` | Site home (static front page) |
| `page-contact.php` | `/contact/` |
| `archive-property.php` | `/properties/` |
| `single-property.php` | One property |
| `page.php` | Any other Page |
| `index.php` | Fallback for everything else |
| `404.php` | Unknown URL |
| `header.php` / `footer.php` | Included via `get_header()` / `get_footer()` |
| `functions.php` | Loader for `inc/` modules (setup, CPTs, forms, seed, etc.) |
| `style.css` | Theme name + all CSS |
| `main.js` | Mobile nav, carousel, forms, gallery |

## Editing content

- **Site settings** — wp-admin → **Estatein** (announcement, contact email/phone, socials, CTA, footer). No ACF Pro needed.
- **Properties** — wp-admin → Properties. Title, content, featured image, plus ACF: price, beds, baths, area, location, featured flag, amenities, three gallery images.
- Marketing copy on Home / Contact lives in those PHP templates. Focus pages: **Properties** archive and **Property Details**.

## License

GPL-2.0-or-later
