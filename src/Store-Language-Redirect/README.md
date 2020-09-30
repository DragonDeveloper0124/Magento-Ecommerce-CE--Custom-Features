# Store language redirect

- [About](#about)
- [How to use](#how-to-use)
- [Compatibility](#compatibility)
- [Resources](#resources)


<br><br>

## About

This is an example setup of how to redirect the user to another store depending on the language. There are different ways to do that, depending on your domains and store configuration. It's been tested in different shops and - if done correctly - has no bad effect on SEO.

This is a modified version from 'thetoine' without the quality sort but working for more and different StoreView setups.

About language based redirects:

- Store codes need to be in ISO language format like 'de', 'en' or redirect will not work.

About the language 'quality sort':

- The Firefox plugin 'Quick locale switcher' does not work if 'quality sort' is enabled. You'll need the complete system to be english. Changing GUI or OS afterwards does not work.
  - Untested: Use a headless CLI browser or try out a proxy.
- Always test with an additional `HTTP_ACCEPT_LANGUAGE` header that does _not_ contain any existing language to see if fallbacks are working.


<br><br>

## How to use

Modify the `index.php` file in the shop root folder and implement a custom PHP function which might be integrated under `/lib/`.

### Installation

- Include in `index.php` in root.
- Define a fallback language if using language redirect.
- Set all domains and storeview code redirections in `TheRemoteCoder_SwitchStore`, depending on your shops configuration.


<br><br>

## Compatibility

Tested with: Magento CE 1.5.1.0

