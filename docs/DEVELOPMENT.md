# Estatein — development notes

Classic theme + a small settings plugin. See [README.md](../README.md) for local setup.

## Layout

| Path | Role |
|------|------|
| `theme/estatein/` | Templates, CSS, JS, theme logic |
| `theme/estatein/inc/` | Modular PHP loaded from `functions.php` |
| `plugin/estatein-settings/` | Site-wide options (announcement, contact, socials, CTA, footer) |

### Theme `inc/` files

| File | Purpose |
|------|---------|
| `setup.php` | Theme supports, menus, asset enqueue |
| `post-types.php` | `property` + `estatein_inquiry` CPTs |
| `inquiry-admin.php` | Form Submissions admin UI |
| `helpers.php` | ACF/meta helpers, URLs, property search query |
| `icons.php` | SVG icon helpers |
| `navigation.php` | Primary nav placeholders, fallback menu |
| `template-tags.php` | Section headers, cards, pricing, FAQ, features grid |
| `forms.php` | Contact/newsletter forms + handler |
| `seo.php` | Meta / Open Graph tags |
| `seed.php` | Demo content on theme activation |

## Custom post types

- **`property`** — public archive at `/properties/`, single property pages. Fields via ACF Free (or post meta fallback): price, location, beds, baths, area, type label, featured flag, amenities, gallery images.
- **`estatein_inquiry`** — private admin-only log of form submissions (wp-admin → Form Submissions).

## Form submission

custom handler using WordPress `admin-post.php`.

### Forms

| Form | Where | `form_type` |
|------|-------|-------------|
| Contact | `/contact/` (`page-contact.php`) | `contact` |
| Property inquiry | Single property (`single-property.php`) | `property_detail` |
| Newsletter | Footer (`footer.php`) | `newsletter` |

### Flow

1. Form POSTs to `admin-post.php` with `action=estatein_form`
2. Handler: `estatein_handle_form()` in `inc/forms.php`
3. Security: WordPress nonce (`estatein_nonce`) + honeypot field (`website`)
4. Validation:
   - **Contact** — first/last name, email, message, terms checkbox
   - **Property inquiry** — first/last name, email, selected property, terms (message optional)
   - **Newsletter** — valid email only
5. On success:
   - Saves a private `estatein_inquiry` post with field data in `_form_data` meta
   - Sends plain-text email via `wp_mail()` to `contact_email` from Estatein Settings (falls back to WP admin email)
6. Redirects back with `?form_status=success|error&form_message=...`
7. Notice rendered by `estatein_form_notice()` — on contact page inside `#contact-form`; on property details inside `#property-inquiry-form`

Client-side validation lives in `main.js` (`data-estatein-form`).

### Local email

`wp_mail()` may not deliver on Local WP without SMTP/Mailhog. Submissions still appear under **Form Submissions** in wp-admin even if email fails (handler succeeds if either save or send works).

## Property search (`archive-property.php`)

GET form — no AJAX (page reload keeps URLs shareable and code small).

| Param | Works? | Filters on |
|-------|--------|------------|
| `q` | Yes | Title/content search |
| `location` | Yes | `location` meta |
| `type` | Yes | `property_type_label` meta |
| `price` | Yes | `price` meta (`under-500k`, `500k-1m`, `1m-plus`) |
| Property Size | No (Figma only) | — |
| Build Year | No (Figma only) | — |

Query builder: `estatein_property_search_args()` in `inc/helpers.php`.

Changing a working filter `<select>` auto-submits the form.

## Shared UI components

- **`estatein_features_grid()`** — feature/contact tile row (homepage + contact page). Cards support `icon`, `title`, `url`, or `links` (social multi-link card).
- **`estatein_section_header()`**, **`estatein_property_card()`**, **`estatein_faq_section()`** — reusable template parts in `inc/template-tags.php`.

## Navigation

About Us and Services are **non-linking placeholders** (Figma labels only). Rendered as `<span>` via `estatein_placeholder_nav_item()` filter even if the menu item has a URL.

## Demo seed

`estatein_seed_demo()` runs on theme activation (`inc/seed.php`): Home + Contact pages, 3 demo properties, Primary menu, default settings if missing.

## Linting

```bash
composer install   # once
composer lint      # check
composer lint:fix  # auto-fix formatting where possible
```

Optional pre-commit hook (runs `composer lint` before each commit):

```bash
git config core.hooksPath .githooks
```

## Intentionally not implemented

- Property inquiry form on properties archive (links to contact instead)
- Property Size / Build Year filters (no matching fields)
- ACF field group in code (configure fields in ACF UI or import separately)
- Settings sanitize callback removed from plugin (trusted admin only; theme still escapes on output)
