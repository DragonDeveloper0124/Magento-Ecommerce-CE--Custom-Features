<?php
/**
 * Class TheRemoteCoder_SwitchStore
 *
 * Get correct language from browser/user
 * and redirect to a different store.
 *
 * @version  Magento 1.5.1.0
 */
class TheRemoteCoder_SwitchStore
{
    /**
     * Singleton class instance.
     *
     * @var  object
     */
    protected static $instance = null;

    /**
     * Define fallback store code here.
     * Warning: Absolutely make sure this store exists and is active!
     *
     * @var  string  Store code.
     */
    private $fallbackStoreCode = 'en';


    // ---------------------------------------------------------------------------------------------------------- Public
    // --------------------------------------------------------------------------------- Constructor

    /**
     * Constructor
     */
    protected function __construct()
    {
    }

    /**
     * Clone
     */
    private function __clone()
    {
    }

    /**
     * Singleton class constructor.
     *
     * @return  object  self::$instance  Class instance.
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new TheRemoteCoder_SwitchStore();
        }

        return self::$instance;
    }


    // -------------------------------------------------------------------------- Redirect root path

    /**
     * Auto-redirect base path request to language store view if request is for root.
     *
     * IMPORTANT:
     * Only activate this if you have set the backend to add the store code to URLs
     * or it will result in an infinite redirect loop (!!!).
     *
     * Change the settings here:
     * System -> Configuration -> General -> Web -> Url Options -> Add Store Code to Urls -> 'Yes'
     */
    private function redirectRootByLanguage()
    {
        if ('/' === $_SERVER['REQUEST_URI']) {
            header('Location: ' . $this->getStoreByLanguage(true)->getBaseUrl());
            exit;
        }
    }


    // -------------------------------------------------------------------------------- Store switch

    /**
     * Select StoreView depending on language and server host.
     * Add all your websites and store codes here.
     *
     * Current setup:
     * - Website #1: en, de
     * - Website #2: us
     *
     * @return  string  $_mageRunCode
     */
    public function getRunCode()
    {
        switch ($_SERVER['HTTP_HOST']) {
            // Website #1
            case 'eu-shop.example.com':
            case 'eu-shop.development.localhost':
                $this->redirectRootByLanguage();
                $_mageRunCode = $this->getStoreByLanguage();
                break;

            // Website #2
            case 'usa-shop.example.com':
            case 'usa-shop.development.localhost':
                $_mageRunCode = 'us';
                break;

            // Fallback store. This should never happen.
            default:
                $_mageRunCode = $this->getFallbackStoreCode();
                break;
        }

        return $_mageRunCode;
    }


    // --------------------------------------------------------------------------------------------------------- Private
    // -------------------------------------------------------------------------- Fallback store

    /**
     * Check if fallback store exists and is active.
     * If not, there is a huge problem that must be fixed first.
     *
     * @throws  Exception
     * @return  string  $this->fallbackStoreCode
     */
    private function getFallbackStoreCode()
    {
        $Stores    = Mage::app()->getStores(false, true);
        $Store     = $Stores[$this->fallbackStoreCode];

        if (   !isset($Store)
            || !$Store->getIsActive()) {
            throw new Exception('Fallback store does not exist. Code = [' . $this->fallbackStoreCode . ']');
        }

        return $this->fallbackStoreCode;
    }


    // -------------------------------------------------------------------------- Store for language

    /**
     * Get first found active store by preferred user language.
     * Fallback to a default store if matching fails.
     *
     * Options:
     * - Return values as string (=ISO language code) or Magento object.
     * - Sort languages by quality or default appereance in string.
     *
     * @param   boolean        $asObject
     * @param   boolean        $qualitySort
     * @return  string|object
     */
    private function getStoreByLanguage($asObject = false, $qualitySort = true)
    {
        $Stores    = Mage::app()->getStores(false, true);
        $Store     = null;
        $storeCode = '';

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $languages = array();
            $quality   = array();
            $results   = array();

            foreach (explode(',', strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'])) as $accept) {
                if (preg_match('!([a-z-]+)(;q=([0-9.]+))?!', trim($accept), $results)) {
                    $languages[]   = $results[1];
                    $quality[] = (isset($results[3]) ? (float)$results[3] : 1.0);
                }
            }

            // Order the codes by quality. Use for live systems.
            // This breaks local testing on systems that are not completely in the sent language.
            if ($qualitySort) {
                array_multisort($quality, SORT_NUMERIC, SORT_DESC, $languages);
            }

            // Iterate through languages found in the accept-language header.
            foreach ($languages as $lang) {
                $lang = substr($lang, 0, 2);

                if (   isset($Stores[$lang])
                    && $Stores[$lang]->getIsActive()) {
                    $Store     = $Stores[$lang];
                    $storeCode = $lang;
                    break;
                }
            }
        }

        // Fallback store. This should never happen.
        if (!$Store || !$storeCode) {
            $Store     = $Stores[$this->getFallbackStoreCode()];
            $storeCode = $this->getFallbackStoreCode();
        }

        return ($asObject) ? $Store : $storeCode;
    }
}
