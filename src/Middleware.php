<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited.
 */

namespace Threefold\Middleware;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface as Logger;
use Threefold\Middleware\Exception\AdvantageConnectionException;
use Threefold\Middleware\Exception\InvalidTokenException;
use Threefold\Middleware\Exception\MiddlewareException;

/**
 * A class to wrap middleware calls into nice php methods and return shiny objects.
 *
 * @author Ciaran McGrath
 * @author Aine Hickey <ahickey@threefoldsystems.com>
 */
class Middleware implements MiddlewareInterface
{
    /**
     * Object container for the logging system.
     *
     * @var Logger
     */
    protected $log;
    /**
     * @var string The token used to authenticate with the rest service. Inserted into the header as a value for 'token:'
     */
    protected $token;
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * Constructor
     *
     * @param Logger $log Logger
     * @param ClientInterface $httpClient
     * @param string $token
     */
    public function __construct(Logger $log, ClientInterface $httpClient, $token)
    {
        $this->log = $log;
        $this->token = $token;
        $this->httpClient = $httpClient;
    }

    /**
     * @MwBase get_customer_number_by_contact_id_org_id
     * Find customer number by contact ID and org ID
     *
     * Get the customer number from their M essage Central Contact ID and Org ID
     *
     * @deprecated
     * @param $contactId
     * @param $orgId
     * @return mixed
     */
    public function getCustomerNumberByContactIdOrgId($contactId, $orgId)
    {
        $url = 'customer/contactid/'.$contactId.'/orgid/'.$orgId;
        return $this->get($url);
    }
    /**
     * @link 1.1 findCustomerIdentifier
     * @MwBase get_customer_by_login
     * Find customer identifier
     *
     * Get Customer ID From username and password
     *
     * @param string $username
     * @param string $password
     * @return array Associative array of returned data
     */
    public function getCustomerByLogin($username, $password)
    {
        $username = base64_encode($username);
        $password = base64_encode($password);

        $url = 'customer/username/'.$username.'/password/'.$password;
        $result = $this->get($url);

        return $result;
    }

    /**
     * @link 1.2 findPostalAddressesByCustomerNumber
     * @MwBase get_customer_address_by_id
     * Find postal addresses by customer number
     *
     * Get customer address by customer ID
     *
     * @param string $customerId
     * @return array Associative array of returned data
     **/
    public function getCustomerAddressById($customerId)
    {
        $url = 'postaladdress/customernumber/'.$customerId;
        return $this->get($url);
    }

    /**
     * @link 1.3 findPostalAddressesByEmailAddress
     * @MwBase get_postal_addresses_by_email
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
    public function getPostalAddressesByEmail($email)
    {
        $url = 'postaladdress/emailaddress/'.urlencode($email);

        return $this->get($url);
    }

    /**
     * @link 1.4	updatePostalAddress
     * @MwBase put_update_postal_address
     * Update the postal address associated to the customer’s account
     * @param $payload
     *
     * @return mixed
     */
    public function putUpdatePostalAddress($payload){
        $url = $this->url . 'customer/update/postaladdress';
        $defaults = array('addressCode' => 'ADDR-01');
        $payload = wp_parse_args($payload, $defaults);
        return $this->post($url, $payload);
    }

    /**
     * @link 1.5 findEmailAddressesByCustomerNumber
     * @MwBase get_customer_email_by_id
     * Find email addresses by customer number
     *
     * Get customer email address by customer ID
     *
     * @param string $customerId
     * @return array An array of email addresses for the given customer ID
     */
    public function getCustomerEmailById($customerId)
    {
        $url = 'customer/emailaddress/customernumber/'.$customerId;
        return $this->get($url);
    }

    /**
     * @link 1.7	updateEmailAddress
     * @MwBase put_update_email_address
     * Update the email address associated to the customer’s account
     * @param      $customerNumber
     * @param      $emailAddress
     *
     * @return array|mixed|WP_Error
     */
    public function putUpdateEmailAddress($customerNumber, $emailAddress){
        $url = $this->url . 'customer/update/emailaddress';
        $payload = array(
            'emailAddress' => $emailAddress,
            'customerNumber' => $customerNumber
        );
        return $this->post($url, $payload);
    }

    /**
     * @link 1.11 findCustomerNumberByContactIdOrgIdStackName
     * @MwBase get_customer_number_by_contact_id_org_id_stack_name
     * Find customer number by contact ID, orgID and stack name
     *
     * Get the customer number from their Message Central Contact ID, Org ID and Stack Name
     *
     * @param $contactId
     * @param $orgId
     * @param $stackName
     * @return mixed
     */
    public function getCustomerNumberByContactIdOrgIdStackName($contactId, $orgId, $stackName)
    {
        $url = 'customer/contactid/'.$contactId.'/orgid/'.$orgId.'/stackname/'.$stackName;
        return $this->get($url);
    }

    /**
     * @link 1.12 findEmailAddressbyContactIdOrgIdStackName
     * @MwBase get_customer_email_by_contact_id_org_id_stack_name
     * Find customer email by contact ID, orgId and stack name
     *
     * Get the customer email address from their Message Cntral Contact ID, Org ID and Stack Name
     *
     * @param $contact_id
     * @param $org_id
     * @param $stack_name
     * @return mixed
     */
    function getCustomerEmailByContactIdOrgIdStackName($contact_id, $org_id, $stack_name){
        $url = $this->url . 'lookup/emailaddress/contactid/' . $contact_id . '/orgid/' . $org_id . '/stack/' . $stack_name;
        return $this->get($url);
    }
    /**
     * @link 1.14 findLowestCustomerNumberByEmailAddress
     * @MwBase get_lowest_customer_number_by_email
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
    public function getLowestCustomerNumberByEmail($email)
    {
        $url = 'customer/findlowestactivecustomernumber/emailaddress/'.urlencode($email);

        return $this->get($url);
    }

    /**
     * @link 1.16	createCustomer
     * @MwBase put_create_customer_by_email
     * Create a new customer
     *
     * Service returns the customer number for newly-created customer.
     * Result set is not restricted to any portalCode/authGroup. The body of the response will return the following outputs
     *
     * @param $email
     * @return array|mixed|WP_Error
     */
    public function putCreateCustomerByEmail($email){
        $url = $this->url . 'customer/create';
        $payload = array('emailAddress' => $email);
        return $this->post($url, $payload);
    }

    /**
     * @link 2.2 findAccountByCustomerNumber
     * @MwBase get_account_by_id
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
    public function getAccountById($customerId)
    {
        $url = 'account/customernumber/'.$customerId;
        return $this->get($url);
    }

    /**
     * @link 2.3 findAccountByEmailAddress
     * @MwBase get_account_by_email
     * Find account by email address
     *
     * Get Customer account by email address
     *
     * @param string $email Email address
     * @return object
     * @throws InvalidTokenException If invalid token is used to make call
     */
    public function getAccountByEmail($email)
    {
        $url = 'account/emailaddress?email='.urlencode($email);
        $this->http_args['timeout'] = '10';

        return $this->get($url);
    }

    /**
     * @link 2.6	addAccount
     * @MwBase put_add_account_by_id_username_pass
     * Add a customer account.
     *
     * @param $customer_id
     * @param $username
     * @param $password
     *
     * @return array|mixed|WP_Error
     */
    public function putAddAccountByIdUsernamePass($customer_id, $username, $password){
        $url = $this->url . 'account/authentication/create';
        $payload = array('customerNumber' => $customer_id, 'username' => $username, 'password' => $password);
        return $this->post($url, $payload);
    }

    /**
     * @link 2.8	updatePassword
     * @MwBase put_update_password
     * Update the password associated to the customer’s account
     *
     * @param $payload
     * @return mixed
     */
    public function putUpdatePassword($customer_id, $username, $password, $newPassword){
        $url = $this->url . 'account/update/password';
        $payload = array('customerNumber' => $customer_id, 'username' => $username, 'existingPassword' => $password, 'newPassword' => $newPassword);
        return $this->post($url, $payload);
    }

    /**
     * @link 3.1 findActiveSubscriptionsByCustomerNumber
     * @MwBase get_active_subscriptions_by_id
     * Find active subscriptions by customer number
     *
     * Get *ACTIVE* Customer Subscriptions for a given customer ID
     *
     * @param string $customerId
     * @return array
     */
    public function getActiveSubscriptionsById($customerId)
    {
        $url = 'sub/active/customernumber/'.$customerId;
        return $this->get($url);
    }

    /**
     * @link 3.2 findSubscriptionsByCustomerNumber
     * @MwBase get_subscriptions_by_id
     * Find subscriptions by customer number
     *
     * Get customer subscriptions for a given customer ID, both active AND inactive
     *
     * @param string $customerId
     * @return string JSON
     */
    public function getSubscriptionsById($customerId)
    {
        $url = 'sub/customernumber/'.$customerId;
        return $this->get($url);
    }

    /**
     * 3.11	findSubscriptionPostalAddressByPurchaseOrderNumber
     * @MwBase findSubscriptionsAndPostalAddressesByPurchaseOrderNumber
     * Get the records combining subscription and postal address using a purchase order number.
     * @param $purchaseOrderNumber
     *
     * @return array
     */
    public function getSubscriptionsAndPostalAddressesByPurchaseOrderNumber($purchaseOrderNumber)
    {
        $url = $this->url . 'sub/postaladdress/purchaseordernumber/' . $purchaseOrderNumber;
        return $this->get($url);
    }

        /**
     * @link 5.3 findLoginAggregateData
     * @MwBase get_aggregate_data_by_login
     * Find login aggregate data
     *
     * Get Customer Aggregate Data for a given Username and Password
     *
     * @param string $username An advantage username
     * @param string $password An advantage password to accompany the Username
     * @return object
     */
    public function getAggregateDataByLogin($username, $password)
    {
        $username = base64_encode(stripslashes($username));
        $password = base64_encode($password);
        $url = 'data/username/'.$username.'/password/'.$password;

        return $this->get($url);
    }

    /**
     * @link 7.1 findCustomerListSignupsByCustomerNumber
     * @MwBase get_customer_list_signups_by_id
     * Find customer list signups by customer number
     *
     * Find the customer’s list signups using the customer number.
     *
     * @param $customerId
     * @return array
     */
    public function getCustomerListSignupsById($customerId)
    {
        $url = 'adv/list/signup/customernumber/' . $customerId;
        return $this->get($url);
    }

    /**
     * @link7.3 findCustomerListSignupsByEmailAddress
     * @MwBase get_customer_list_signups_by_email
     * Find the customer's list signups using their email address.
     *
     * @param $email
     * @return array
     */
    public function getCustomerListSignupsByEmail($email){
        $url = $this->url . 'adv/list/signup/emailaddress/' . $email;
        return $this->get($url);
    }

    /**
     * @link 7.8	addCustomerSignup
     * @MwBase put_customer_signup_by_email
     * Add a customer signup to a list.
     *
     * @param      $email
     * @param      $list_code
     * @param      $source_code
     * @param null $attributes
     *
     * @return array|mixed|WP_Error
     */
    public function put_customer_signup_by_email($email, $list_code, $source_code, $attributes = null){
        $url = $this->url . 'list/customersignup/add';
        $payload = array(
            'emailAddress'  => $email,
            'listCode'      => $list_code,
            'sourceId'      => $source_code
        );
        if($attributes !== null)
            $payload = array_merge($payload, $attributes);
        return $this->post($url, $payload);
    }

    /**
     * @link 7.11	unsubCustomerSignup
     * @MwBase put_unsub_customer_signup
     *
     * Unsubscribe a customer signup.
     *
     *
     *
     * @param      $list_code  string
     *                         Code that identifies the list from which the customer will unsubscribe
     * @param      $email_address string
     *                          The customer’s e-mail address
     * @param null $reference string
     *                          Reference number to track this unsub. This can be any random, alphanumeric ID
     *                          If no reference number is supplied, MW2 generates it by applying the hash
     *                          algorithm on the other required fields supplied.
     *
     * @return array|mixed|WP_Error
     */
    public function putUnsubCustomerSignup($list_code, $email_address, $reference = null){
        $url = $this->url . '/list/customersignup/unsub';
        $payload = array(
            'listCode' => $list_code,
            'emailAddress' => $email_address
        );
        if($reference) $payload['referenceNumber'] = $reference;
        return $this->post($url, $payload);
    }

    /**
     * @link 8.1 findEmailFulfillmentHistoryByCustomerNumber
     * @MwBase get_email_fulfillment_history_by_id
     * Get email fulfillment history for a given customer ID
     *
     * @param string $customerId
     * @return array
     */
    public function getEmailFulfillmentHistoryById($customerId)
    {
        $url = 'emailfulfillment/history/customernumber/'.$customerId;
        return $this->get($url);
    }

    /**
     * Get Customer subscriptions by login
     *
     * @param string $username
     * @param string $password
     * @return object|false PHP object of user data
     *
     **/
    public function getSubscriptionsByLogin($username, $password)
    {
        $json = $this->getCustomerByLogin($username, $password);
        $content = json_decode($json);

        if (isset($content->customerNumber) && !is_null($content->customerNumber)) {
            return $this->getSubscriptionsById($content->customerNumber);
        }
        return false;
    }

    /**
     * @link 12.1 findAffiliateFactsByCustomerNumber
     * @MwBase get_affiliate_facts_by_id
     * Find affiliate facts by customer number
     *
     * Retrieve affiliate facts based off of a customer number.
     *
     * @param $customerId
     * @return array
     */
    public function getAffiliateFactsById($customerId)
    {
        $url = 'target/affiliate/fact/customernumber/'.$customerId;
        return $this->get($url);
    }

    /**
     * @link 12.2 findListFactsByCustomerNumber
     * @MwBase get_list_facts_by_id
     * Find list facts by customer number
     *
     * Retrieve list facts based off of a customer number
     *
     * @param $customerId
     * @return array
     */
    public function getListFactsById($customerId)
    {
        $url = 'target/list/fact/customernumber/'.$customerId;
        return $this->get($url);
    }

    /**
     * @link 12.3 findAffiliateTagsByCustomerNumber
     * @MwBase get_affiliate_tags_by_id
     * Find affiliate tags by customer number
     *
     * Retrieve affiliate tagging information based off of a customer number
     *
     * @param $customerId
     * @return array
     */
    public function getAffiliateTagsById($customerId)
    {
        return 'skipped';
        // @todo Unskip
        $url = 'target/affiliate/tag/'.$customerId;

        return $this->get($url);
    }

    /**
     * @link 12.4 findAffiliateTagsByEmailAddressOwningOrg
     * @MwBase get_affiliate_tags_by_email_owning_org
     * Retrieve affiliate tagging information based off of an email address and owning org
     * @param $email
     * @param $owning_org
     *
     * @return array
     */
    public function getAffiliateTagsByEmailOwningOrg($email, $owning_org){
        $url = $this->url . 'target/affiliate/tag/emailaddress/' . $email . '/owningorg/' . $owning_org;
        return $this->get($url);
    }

    /**
     * @link 12.5 createAffiliateTags
     * @MwBase put_create_affiliate_tags
     * Create an Affiliate Tag
     *
     * @param      $customer_id
     * @param      $email
     * @param      $tag_name
     * @param      $tag_value
     * @param null $owning_org
     *
     * @return array|mixed|WP_Error
     */
    public function putCreateAffiliateTags($customer_id, $email, $tag_name, $tag_value, $owning_org = null){
        $url = $this->url . 'middleware/target/affiliate/tag/create';
        $payload = array(
            'customerNumber'    => $customer_id,
            'emailAddress'      => $email,
            'tagName'           => $tag_name,
            'tagValue'          => $tag_value,
        );
        if(!empty($owning_org)) $payload['ownOrg'] = $owning_org;
        return $this->post($url, $payload);
    }

    /**
     * Make GET request
     *
     * A helper method to reduce repetition.
     *
     * @param string $url
     * @return array Associative array of returned data. Returns WP_Error object on error
     * @throws MiddlewareException If invalid response status code received
     * @throws InvalidTokenException If invalid token is used to make call
     */
    protected function get($url)
    {
        $this->log->info('Middleware GET Request to: ' . $url . ' (token: ' . $this->token . ')');
        try {
            // Make request
            $headers = ['token' => $this->token];
            $response = $this->httpClient->request(
                'GET',
                $url,
                ['headers' => $headers]
            );
            $statusCode = $response->getStatusCode();
            $contents = $response->getBody()->getContents();
            // Check result
            switch ($statusCode) {
                case 200:
                    // This is a successful call and will return a php object
                    $this->log->info('Response contents', (array)$contents);
                    return $contents;
                case 204:
                    // No content - no results found
                    return false;
                default:
                    $this->log->error('Response contents', (array)$contents);
                    throw new MiddlewareException('Bad result. Status Code: ' . $statusCode);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            switch ($e->getResponse()->getStatusCode()) {
                // Invalid token exception
                case 403:
                    throw new InvalidTokenException('Invalid token');
                default:
                    // Rethrow everything else
                    throw $e;
            }
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Advantage error
            if ($e->getResponse()->getStatusCode() == 500
                && strpos($e->getResponse()->getBody(), 'Failed, advantage connection') !== false
            ) {
                throw new AdvantageConnectionException('Unable to connect to Advantage', null, $e);
            }
        }
    }

    /**
     * Helper Method for POST requests
     *
     * @param $url
     * @param $payload
     *
     * @return array|mixed
     */
    private function post($url, $payload)
    {
        $this->log->info('Middleware POST Request to: ' . $url);
        try {

            $headers = ['token' => $this->token,'Content-Type'=> 'application/json'];
            $response = $this->httpClient->request(
                'POST',
                $url,
                ['body' => json_encode($payload),'headers' => $headers]
            );
            $statusCode = $response->getStatusCode();
            $contents = $response->getBody()->getContents();
            $this->log->info('Response from call',['response' => $response, 'url' => $url, 'payload'=> $payload]);
            // Check result
            switch ($statusCode) {
                case 200:
                    // This is a successful call and will return a php object
                    $this->log->info('Response contents', (array)$contents);
                    return $contents;
                case 204:
                    // No content - no results found
                    return false;
                default:
                    $this->log->error('Response contents', (array)$contents);
                    throw new MiddlewareException('Bad result. Status Code: ' . $statusCode);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            switch ($e->getResponse()->getStatusCode()) {
                // Invalid token exception
                case 403:
                    throw new InvalidTokenException('Invalid token');
                default:
                    // Rethrow everything else
                    throw $e;
            }
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Advantage error
            if ($e->getResponse()->getStatusCode() == 500) {

                if (strpos($e->getResponse()->getBody(), 'Failed, advantage connection') !== false) {
                    throw new AdvantageConnectionException('Unable to connect to Advantage', null, $e);

                } elseif (strpos($e->getResponse()->getBody(), 'Customer not found for the update') !== false) {

                    throw new InvalidCustomerException('Customer not found for the update', null, $e);
                }
            }
            throw $e;
        }
    }

    /**
     * Helper Method for PUT requests
     *
     * @param $url
     * @param $payload
     *
     * @return array|mixed
     */
    private function put($url, $payload)
    {
        $this->log->info('Middleware PUT Request to: ' . $url);
        try {
            $headers = ['token' => $this->token,'Content-Type'=> 'application/json'];
            $response = $this->httpClient->request(
                'PUT',
                $url,
                ['headers' => $headers]
            );
            $statusCode = $response->getStatusCode();
            $contents = $response->getBody()->getContents();
            // Check result
            switch ($statusCode) {
                case 200:
                    // This is a successful call and will return a php object
                    $this->log->info('Response contents', (array)$contents);
                    return $contents;
                case 204:
                    // No content - no results found
                    return false;
                default:
                    $this->log->error('Response contents', (array)$contents);
                    throw new MiddlewareException('Bad result. Status Code: ' . $statusCode);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            switch ($e->getResponse()->getStatusCode()) {
                // Invalid token exception
                case 403:
                    throw new InvalidTokenException('Invalid token');
                default:
                    // Rethrow everything else
                    throw $e;
            }
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Advantage error
            if ($e->getResponse()->getStatusCode() == 500
                && strpos($e->getResponse()->getBody(), 'Failed, advantage connection') !== false
            ) {
                throw new AdvantageConnectionException('Unable to connect to Advantage', null, $e);
            }
        }
    }

    /**
     * A method to give a list of middleware calls by method
     *
     * @param string $type A string describing the type of calls we want e.g. get, put, update, delete
     * @param string $input A string describing what inputs to use. e.g. login, customer_ID
     * @return array Associative array of methods that match the requested parameters
     * @throws \Exception If invalid $type is given
     */
    public function listMethods($type, $input)
    {
        $methods = get_class_methods($this);
        // Check and clean up the $type parameter
        if (!in_array($type, array('get', 'put', 'update', 'delete'))) {
            throw new \Exception('Invalid Value: '.$type.' for Parameter $type');
        }
        // Check and cleanup the $input parameter
        if ($input == 'customerId') {
            $input = 'ById';
        } elseif ($input == 'login') {
            $input = 'ByLogin';
        } elseif ($input == 'email') {
            $input = 'ByEmail';
        } else {
            \Exception('Invalid Value: ' . $input. ' for Parameter $input');
        }
        // Cycle through all the methods, and find those that match the type and input
        $results = [];
        foreach ($methods as $method) {
            if (strpos($method, $type) !== false) {
                if (strpos($method, $input)) {
                    $results[] = $method;
                }
            }
        }
        return $results;
    }
}
