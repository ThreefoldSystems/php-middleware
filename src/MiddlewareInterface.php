<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace Threefold\Middleware;

use GuzzleHttp\ClientInterface;
use \Psr\Log\LoggerInterface as Logger;
use Threefold\Middleware\Exception\InvalidTokenException;

interface MiddlewareInterface
{
    /**
     * Constructor
     *
     * @param Logger $logger Logger
     * @param ClientInterface $httpClient
     * @param string $token
     */
    public function __construct(Logger $logger, ClientInterface $httpClient, $token);

    /**
     * Find customer number by contact ID and org ID
     *
     * Get the customer number from their Message Central Contact ID and Org ID
     *
     * @param $contactId
     * @param $orgId
     * @return mixed
     */
    public function getCustomerNumberByContactIdOrgId($contactId, $orgId);

    /**
     * Find customer number by contact ID, orgID and stack n ame
     *
     * Get the customer number from their Message Central Contact ID, Org ID and Stack Name
     *
     * @param $contactId
     * @param $orgId
     * @param $stackName
     * @return mixed
     */
    public function getCustomerNumberByContactIdOrgIdStackName($contactId, $orgId, $stackName);

    /**
     * Find account by email address
     *
     * Get Customer account by email address
     *
     * @param string $email Email address
     * @return object
     * @throws InvalidTokenException If invalid token is used to make call
     */
    public function getAccountByEmail($email);

    /**
     * Find login aggregate data
     *
     * Get Customer Aggregate Data for a given Username and Password
     *
     * @param string $username An advantage username
     * @param string $password An advantage password to accompany the Username
     * @return object
     */
    public function getAggregateDataByLogin($username, $password);

    /**
     * Find subscriptions by customer number
     *
     * Get customer subscriptions for a given customer ID, both active AND inactive
     *
     * @param string $customerId
     * @return array
     */
    public function getSubscriptionsById($customerId);

    /**
     * Find active subscriptions by customer number
     *
     * Get *ACTIVE* Customer Subscriptions for a given customer ID
     *
     * @param string $customerId
     * @return array
     */
    public function getActiveSubscriptionsById($customerId);

    /**
     * Find email addresses by customer number
     *
     * Get customer email address by customer ID
     *
     * @param string $customerId
     * @return array An array of email addresses for the given customer ID
     */
    public function getCustomerEmailById($customerId);

    /**
     * Get email fulfillment history for a given customer ID
     *
     * @param string $customerId
     * @return array
     */
    public function getEmailFulfillmentHistoryById($customerId);

    /**
     * Find postal addresses by customer number
     *
     * Get customer address by customer ID
     *
     * @param string $customerId
     * @return array Associative array of returned data
     **/
    public function getCustomerAddressById($customerId);

    /**
     * Find account by customer number
     *
     * Find an account using the customer number.
     *
     * Service returns the account information for the customer number supplied in the request URL.
     * The response is restricted to the portalCode/authGroup tied to the token.
     *
     * @param $customerId
     * @return array
     */
    public function getAccountById($customerId);

    /**
     * Find customer identifier
     *
     * Get Customer ID From username and password
     *
     * @param string $username
     * @param string $password
     * @return array Associative array of returned data
     */
    public function getCustomerByLogin($username, $password);

    /**
     * Find postal addresses by email address
     *
     * Get the customer’s demographic information using their email address—this call is like the previous call, but
     * uses emailAddress instead of customerNumber
     *
     * The postal address data block contains demographic information such as name, postalAddress, emailAddress, etc.
     * The  data returned in this call can be used to pre-populate personalized fields on a website
     *
     * @param string $email
     * @return array
     */
    public function getPostalAddressesByEmail($email);

    /**
     * Find lowest customer number by email address
     *
     * Find the lowest customer number using the customer’s e-mail address
     *
     * Service returns the lowest customer number for e-mail address supplied in the request URL.
     * Result set is not restricted to any portalCode/authGroup.
     *
     * @param string $email
     * @return array
     */
    public function getLowestCustomerNumberByEmail($email);

    /**
     * Find customer list signups by customer number
     *
     * Find the customer’s list signups using the customer number.
     *
     * @param $customerId
     * @return array
     */
    public function getCustomerListSignupsById($customerId);

    /**
     * Find affiliate facts by customer number
     *
     * Retrieve affiliate facts based off of a customer number.
     *
     * @param $customerId
     * @return array
     */
    public function getAffiliateFactsById($customerId);

    /**
     * Find list facts by customer number
     *
     * Retrieve list facts based off of a customer number
     *
     * @param $customerId
     * @return array
     */
    public function getListFactsById($customerId);

    /**
     * Find affiliate tags by customer number
     *
     * Retrieve affiliate tagging information based off of a customer number
     *
     * @param $customerId
     * @return array
     */
    public function getAffiliateTagsById($customerId);

    /**
     * A method to give a list of middleware calls by method
     *
     * @param string $type A string describing the type of calls we want e.g. get, put, update, delete
     * @param string $input A string describing what inputs to use. e.g. login, customer_ID
     * @return array Associative array of methods that match the requested parameters
     **/
    public function listMethods($type, $input);
}
