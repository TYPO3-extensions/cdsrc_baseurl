<?php
namespace CDSRC\CdsrcBaseurl\Hook;

/**
 * @copyright Copyright (c) 2016 Code-Source
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Hook for template object
 *
 * @author    Matthias Toscanelli <m.toscanelli@code-source.ch>
 * @package CDSRC
 * @subpackage baseurl
 */
class BaseUrlHook
{

    /**
     * Append baseUrl to TSFE based on loaded domain
     *
     * @param array $config : Reference to configuration array
     * @param TypoScriptFrontendController $tsfe : Reference to TypoScript based Front End object
     */
    public function execute(array $config, TypoScriptFrontendController $tsfe)
    {
        if ($this->isEnabled($config['config'])) {
            $parts = parse_url(GeneralUtility::getIndpEnv('TYPO3_SITE_URL'));
            $domain = BackendUtility::getDomainStartPage($parts['host'], $parts['path']);
            if ($domain && $domain['tx_cdsrcbaseurl_to_baseurl']) {
                $baseURL = rtrim(GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST'), '/') . '/';
                $this->updateBaseUrl($baseURL, $tsfe);
            }
        }
    }

    /**
     * Is Hook enable?
     *
     * @param array $config
     *
     * @return boolean
     */
    protected function isEnabled(array $config)
    {
        if (isset($config['tx_cdsrcbaseurl_disabled.'])) {
            /** @var ContentObjectRenderer $cObj */
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);

            return !$cObj->stdWrap($config['tx_cdsrcbaseurl_disabled'], $config['tx_cdsrcbaseurl_disabled.']);
        } elseif (isset($config['tx_cdsrcbaseurl_disabled'])) {
            return !$config['tx_cdsrcbaseurl_disabled'];
        }

        return true;
    }

    /**
     * Set or replace baseURL in TSFE
     *
     * @param string $baseURL
     * @param TypoScriptFrontendController $tsfe
     */
    protected function updateBaseUrl($baseURL, TypoScriptFrontendController $tsfe)
    {
        if ($tsfe->isGeneratePage()) {
            // Check if baseURL is not set manually
            if (isset($tsfe->config['config']['baseURL']) && $tsfe->config['config']['baseURL']) {
                return;
            }
            $tsfe->config['config']['baseURL'] = $baseURL;
        } elseif (!isset($tsfe->config['config']['tx_cdsrcbaseurl_cache_overridden'])) {
            $tsfe->config['config']['tx_cdsrcbaseurl_cache_overridden'] = 1;
            // Replace baseURL in TSFE's cached content
            $matches = [];
            if (preg_match('#(<base[^>]+href=["\']([^"\']+)["\'][^>]*>(</base>)?)#iu', $tsfe->content, $matches)) {
                if ($matches[2] !== $baseURL) {
                    $targetMatches = [];
                    $hasTarget = preg_match('#target=["\']([^"\']+)["\']#i', $matches[1], $targetMatches);
                    $target = $hasTarget ? ' target="' . $targetMatches[1] . '"' : '';
                    $tsfe->content = str_replace(
                        $matches[1],
                        '<base href="' . $baseURL . '"' . $target . ' />',
                        $tsfe->content
                    );
                }
            } else {
                $tsfe->content = preg_replace(
                    '#(<head[^>]*>)#iu',
                    '$1<base href="' . $baseURL . '" />',
                    $tsfe->content
                );
            }
        }
    }
}