<?php
/**
 * Plugin Name: Silktide Consent Manager
 * Description: Adds Silktide cookie banner via proper WordPress enqueue.
 * Version: 1.0
 * Author: Hans Pålsson based on silktide.com/consent-manager
 * License: GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_enqueue_scripts', function () {

    $base_url = plugin_dir_url( __FILE__ ) . 'assets/';

    // CSS
    wp_enqueue_style(
        'silktide-consent-manager',
        $base_url . 'silktide-consent-manager.css',
        [],
        null
    );

    // JS
    wp_enqueue_script(
        'silktide-consent-manager',
        $base_url . 'silktide-consent-manager.js',
        [],
        null,
        false // false = head, vilket Silktide kräver
    );

    // Inline config (ersätter <script> i <head>)
    wp_add_inline_script(
        'silktide-consent-manager',
        <<<JS
silktideCookieBannerManager.updateCookieBannerConfig({
  background: {
    showBackground: false
  },
  cookieIcon: {
    position: "bottomLeft"
  },
  cookieTypes: [
    {
      id: "necessary",
      name: "Nödvänliga",
      description: "<p>Dessa cookies är nödvändiga för att webbplatsen ska fungera korrekt och kan inte stängas av. De hjälper till med saker som inloggning och att spara dina sekretessinställningar.</p>",
      required: true,
      onAccept: function() {
        console.log('Necessary accepted');
      }
    },
    {
      id: "analytics",
      name: "Analys",
      description: "<p>Dessa cookies hjälper oss att förbättra webbplatsen genom att samla in anonym statistik om hur webbplatsen används.</p>",
      required: false,
      onAccept: function() {
        console.log('Analytics accepted');
      },
      onReject: function() {
        console.log('Analytics rejected');
      }
    },
    {
      id: "advertising",
      name: "Marknadsföring",
      description: "<p>Dessa cookies ger extra funktioner och personalisering för att förbättra din upplevelse. De kan sättas av oss eller av partners vars tjänster vi använder.</p>",
      required: false,
      onAccept: function() {
        console.log('Advertising accepted');
      },
      onReject: function() {
        console.log('Advertising rejected');
      }
    }
  ],
  text: {
    banner: {
      description: "<p>Vi använder cookies på vår webbplats för att förbättra din användarupplevelse, tillhandahålla personligt innehåll och analysera vår trafik. <a href=\\"/cookie-policy\\" target=\\"_blank\\">Cookiepolicy</a></p>",
      acceptAllButtonText: "Acceptera alla",
      rejectNonEssentialButtonText: "Avvisa icke-nödvänliga",
      preferencesButtonText: "Inställningar"
    },
    preferences: {
      title: "Anpassa dina cookie-inställningar",
      description: "<p>Vi respekterar din rätt till integritet. Du kan välja att inte tillåta vissa typer av cookies. Dina cookie-inställningar kommer att gälla på hela vår webbplats.</p>"
    }
  },
  position: {
    banner: "bottomLeft"
  }
});
JS
    );
});
