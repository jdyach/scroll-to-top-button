# Scroll to Top Button

A lightweight WordPress plugin that injects a configurable floating "scroll to top" button on the front end. Zero dependencies — no jQuery, no bloat.

## Features

- **Floating button** fixed to the bottom-left or bottom-right corner
- Configurable **icon color**, **background color**, **screen position**, and **scroll threshold**
- Custom **label / icon** (text, HTML entity, or emoji)
- Smooth scroll to top on click
- Button fades in with a CSS transition once the page is scrolled past the threshold
- Uses `IntersectionObserver` for performant scroll detection (no scroll event listeners)
- CSS custom properties for color theming — minimal DOM style recalculations
- Settings injected as inline CSS variables — no hardcoded values in stylesheet

## WordPress Concepts Demonstrated

| Concept | Where Used |
|---|---|
| Settings API (`register_setting`, `add_settings_section`, `add_settings_field`) | `class-stb-settings.php` |
| `sanitize_hex_color()` | Sanitizing color picker values |
| `wp_localize_script()` | Passing PHP settings (threshold) to JS |
| `wp_add_inline_style()` | Injecting CSS custom properties from saved options |
| `wp_footer` action | Rendering the button markup |
| `register_activation_hook` | Setting default options on activation |
| `wp_parse_args()` | Merging saved options with defaults |
| `wp_enqueue_style` / `wp_enqueue_script` | Front-end asset loading |
| `IntersectionObserver` API | Performant scroll position detection |

## File Structure

```
scroll-to-top-button/
├── scroll-to-top-button.php    # Plugin bootstrap + activation hook
├── includes/
│   ├── class-stb-settings.php  # Settings API page (Settings → Scroll to Top)
│   └── class-stb-output.php    # Enqueue assets + render button in footer
├── assets/
│   ├── css/front.css           # Button styles with CSS custom properties
│   └── js/front.js             # IntersectionObserver + smooth scroll
└── README.md
```

## Installation

1. Upload the `scroll-to-top-button` folder to `/wp-content/plugins/`
2. Activate in **Plugins → Installed Plugins**
3. Configure via **Settings → Scroll to Top**

## Requirements

- WordPress 6.0+
- PHP 8.0+
