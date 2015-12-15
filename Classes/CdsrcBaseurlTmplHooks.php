<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Matthias Toscanelli <m.toscanelli@code-source.ch>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Hook for template object
 *
 * @author	Matthias Toscanelli <m.toscanelli@code-source.ch>
 * @package CDSRC
 * @subpackage baseurl
 */
class CdsrcBaseurlTmplHooks {

    /**
     * 
     * @var boolean
     */
    protected $versionUsingNamespace = NULL;

    /**
     * Append baseUrl to TSFE based on loaded domain
     *
     * @param array     $config: Reference to configuration array
     * @param tslib_fe  $tsfe: Reference to TypoScript based Front End object
     */
    public function hookInitConfig(&$config, &$tsfe) {
        if ($this->isEnabled($tsfe)) {
            if ($this->isVersionUsingNamespace()) {
                $parts = parse_url(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL'));
                if (($domain = \TYPO3\CMS\Backend\Utility\BackendUtility::getDomainStartPage($parts['host'], $parts['path'])) && $domain['tx_cdsrcbaseurl_to_baseurl']) {
                    $baseURL = rtrim(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST'), '/') . '/';
                    $this->updateBaseUrl($baseURL, $tsfe);
                }
            } else {
                require_once(PATH_t3lib . 'class.t3lib_befunc.php');
                require_once(PATH_t3lib . 'class.t3lib_div.php');

                $parts = parse_url(t3lib_div::getIndpEnv('TYPO3_SITE_URL'));
                if (($domain = t3lib_BEfunc::getDomainStartPage($parts['host'], $parts['path'])) && $domain['tx_cdsrcbaseurl_to_baseurl']) {
                    $baseURL = rtrim(t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST'), '/') . '/';
                    $this->updateBaseUrl($baseURL, $tsfe);
                }
            }
        }
    }

    /**
     * Is current TYPO3 version using Namespace?
     * 
     * @return boolean
     */
    protected function isVersionUsingNamespace() {
        if ($this->versionUsingNamespace === NULL) {
            if (class_exists('\\TYPO3\\CMS\\Core\Utility\\GeneralUtility') && \TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('6.0')) {
                $this->versionUsingNamespace = TRUE;
            } else {
                $this->versionUsingNamespace = FALSE;
            }
        }
        return $this->versionUsingNamespace;
    }

    /**
     * Is Hook enable?
     * @param tslib_fe  $tsfe: Reference to TypoScript based Front End object
     * 
     * @return boolean
     */
    protected function isEnabled($tsfe) {
        if (isset($tsfe->config['config']['tx_cdsrcbaseurl_disabled.'])) {
            if ($this->isVersionUsingNamespace() && class_exists('\\TYPO3\\CMS\\Frontend\ContentObject\\ContentObjectRenderer')) {
                $cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
            } else {
                require_once(PATH_t3lib . 'class.t3lib_div.php');
                require_once(PATH_tslib . 'class.tslib_content.php');
                $cObj = t3lib_div::makeInstance('tslib_cObj');
            }
            return !$cObj->stdWrap($tsfe->config['config']['tx_cdsrcbaseurl_disabled'], $tsfe->config['config']['tx_cdsrcbaseurl_disabled.']);
        } elseif (isset($tsfe->config['config']['tx_cdsrcbaseurl_disabled'])) {
            return !$tsfe->config['config']['tx_cdsrcbaseurl_disabled'];
        }
        return TRUE;
    }

    /**
     * Set or replace baseURL in TSFE
     * 
     * @param string $baseURL
     * @param tslib_fe  $tsfe: Reference to TypoScript based Front End object
     */
    protected function updateBaseUrl($baseURL, &$tsfe) {
        if ($tsfe->cacheContentFlag) {
            if (!$tsfe->tx_cdsrcbaseurl_tmplhooks_done) {
                $tsfe->tx_cdsrcbaseurl_tmplhooks_done = 1;
                // Replace baseURL in TSFE's cached content
                $matches = array();
                if (preg_match('#(<base[^>]+href=["\']([^"\']+)["\'][^>]*>(</base>)?)#iu', $tsfe->content, $matches)) {
                    if ($matches[2] !== $baseURL) {
                        $submatches = array();
                        $target = preg_match('#target=["\']([^"\']+)["\']#i', $matches[1], $submatches) ? ' target="' . $submatches[1] . '"' : '';
                        $tsfe->content = str_replace($matches[1], '<base href="' . $baseURL . '"' . $target . ' />', $tsfe->content);
                    }
                } else {
                    $tsfe->content = preg_replace('#(<head[^>]*>)#iu', '$1<base href="' . $baseURL . '" />', $tsfe->content);
                }
            }
        } elseif (!$tsfe->config['config']['baseURL']) {
            $tsfe->config['config']['baseURL'] = $baseURL;
        }
    }

}

/**
 * TYPO3 4.x branch compatibility fix
 */
class user_CdsrcBaseurlTmplHooks extends CdsrcBaseurlTmplHooks {
    
}

?>