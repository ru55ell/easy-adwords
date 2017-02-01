<?php

namespace EasyAdwords;


use EasyAdwords\Auth\AdWordsAuth;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\v201609\cm\Selector;

/**
 * Parent of the various entity classes, such as Campaign, AdGroup and Keyword.
 *
 * Class Entity
 * @package EasyAdwords
 */
class Entity extends Base {

    protected $operationResult;
    protected $authObject;
    protected $adWordsServices;

    public function __construct(Config $config) {

        $this->adWordsServices = new AdWordsServices();

        // Create the auth object.
        $this->authObject = new AdWordsAuth($config->getRefreshToken(), $config->getAdwordsConfigPath());

        // Build the session with the auth object.
        $this->authObject->buildSession($config->getClientCustomerId());
    }


    /**
     * Download all the entities that meet the given config criteria and service.
     * Useful if the list needs to be re-downloaded.
     * @param Config $config
     * @param $adwordsService
     * @return
     */
    public function downloadFromGoogle(Config $config, $adwordsService) {

        // Create a selector to select all ad groups for the specified campaign.
        $selector = new Selector();
        $selector->setFields($config->getFields());

        // Set ordering if given in config.
        if ($config->getOrdering()) {
            $selector->setOrdering($config->getOrdering());
        }

        // Set predicates if given in config.
        if ($config->getPredicates()) {
            $selector->setPredicates($config->getPredicates());
        }

        $result = $adwordsService->get($selector);
        return $result;
    }


    /**
     * @return mixed
     */
    public function getOperationResult() {
        return $this->operationResult;
    }

    /**
     * @param mixed $operationResult
     * @return Entity
     */
    public function setOperationResult($operationResult) {
        $this->operationResult = $operationResult;
        return $this;
    }

    /**
     * @return AdWordsAuth
     */
    public function getAuthObject() {
        return $this->authObject;
    }

    /**
     * @param AdWordsAuth $authObject
     * @return Entity
     */
    public function setAuthObject($authObject) {
        $this->authObject = $authObject;
        return $this;
    }

    /**
     * @return AdWordsServices
     */
    public function getAdWordsServices() {
        return $this->adWordsServices;
    }

    /**
     * @param AdWordsServices $adWordsServices
     * @return Entity
     */
    public function setAdWordsServices($adWordsServices) {
        $this->adWordsServices = $adWordsServices;
        return $this;
    }
}