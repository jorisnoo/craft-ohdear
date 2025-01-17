# Oh Dear plugin for Craft CMS 5.x

Integrate Oh Dear into Craft CMS.

![Oh Dear overview](https://raw.githubusercontent.com/webhubworks/craft-ohdear/develop/resources/img/screenshots/overview.jpg)

## Requirements

This plugin requires **Craft CMS 5.0.0** or later and **PHP 8.2** or later.

You'll also have to provide an Oh Dear API key.

🙏 You can create an Oh Dear account using our affiliate link: [Oh Dear](https://ohdear.app/?via=webhub) We will offer you a free license key for Craft Oh Dear if you sign up using our affiliate link. Just send us a message.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin.

        composer require webhubworks/craft-ohdear

3. Install and enable the plugin:

        ./craft plugin/install ohdear && ./craft plugin/enable ohdear

   OR: In the Control Panel, go to Settings → Plugins and click the “Install” button for Oh Dear.

## Oh Dear Overview

Craft Oh Dear provides deep integration into Craft and saves developer's time by empowering editors.

With Oh Dear you don‘t just monitor your homepage but your entire website. Oh Dear monitors uptime, checks your SSL certificates, detects broken links and mixed content. Craft Oh Dear integrates all this nicely into the Control Panel.

To learn more about the features visit the [plugin store page](https://plugins.craftcms.com/ohdear).

## Configuring Oh Dear

Go to the settings page and paste your Oh Dear API key. (You can create a token on Oh Dears's [API token page](https://ohdear.app/user/api-tokens).) You can choose from the sites of all teams that your personal Oh Dear account belongs to.

Currently there is no multi-site support. You can only connect to a single Oh Dear site from one Craft installation. Multi-site support is on our Roadmap. Let us know if you desperately need it.

## Oh Dear Roadmap
- Application Health Checks for site security
- More Checks like Sitemap, DNS, Scheduled Tasks etc.

## Questions and answers

### Why not just use Oh Dear‘s dashboard?

As an Oh Dear customer you can be a member of many teams each with their own monitored websites. It is tailored to you as a developer. With Craft Oh Dear you‘ll bring all relevant features to your Craft editors. Empower them to find and fix broken links and mixed content, check app and certificate health, review performance metrics and analyse uptime.

### Does Craft Oh Dear detect 404s?

Oh Dear crawls your website to find broken links that refer to external or internal pages from the point of view of a user that is on your site. Thus it cannot detect 404 errors for users that arrive from outside to your website. For this purpose you may find several other Craft plugins or services that detect 404s to your site when they occure.

### What's Mixed Content?

Take a look at this good explanation on [MDN](https://developer.mozilla.org/en-US/docs/Web/Security/Mixed_content).

### Is this an official Oh Dear plugin?

This plugin is independent from Oh Dear. You can see all available 3rd party integrations in [Oh Dear's docs](https://ohdear.app/docs/integrations/3rd-party-integrations/introduction).

### Where is version 3?

Starting at version 4 the version number will correspond to the Craft version that the plugin runs on. Craft Oh Dear 2 requires Craft 3 and Craft Oh Dear 3 does not exist. New features will only be available in Craft Oh Dear 5 which requires Craft 5.

Brought to you by [webhub](https://webhub.de)
