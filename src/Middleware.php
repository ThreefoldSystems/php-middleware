<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited.
 */

namespace Threefold\Middleware;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Log\LoggerInterface as Logger;
use Threefold\Middleware\Exception\AdvantageConnectionException;
use Threefold\Middleware\Exception\EmailAlreadyExistsException;
use Threefold\Middleware\Exception\InvalidCustomerException;
use Threefold\Middleware\Exception\InvalidEntityException;
use Threefold\Middleware\Exception\InvalidTokenException;
use Threefold\Middleware\Exception\MiddlewareException;

/**
 * A class to wrap middleware calls into nice php methods and return shiny objects.
 *
 * @author Ciaran McGrath
 * @author Aine Hickey <ahickey@threefoldsystems.com>
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Middleware documentation
 * ------------------------
 * This class links to the middleware API.
 * @link http://ezimages.net/middleware/MW%202%2019%2011%20REST%20Service%20Inventory_061316.pdf
 *
 * Threefold Wordpress Plugin
 * --------------------------
 * Threefold Systems has a wordpress plugin for middleware. In the future we hope to use this library to make
 * the calls to Middleware in this plugin. For this reason, the following custom phpdoc tag has been created
 * @mw-wp Lists the function name in the wordpress middleware base plugin
 */
class Middleware implements MiddlewareInterface
{
    /**
     * @var Logger
     */
    protected $log;
    /**
     * @var string The token used to authenticate with the REST service
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

    /******************************************************************************************************************
     * 1 Customer Services
     **/

    /**
     * Find customer identifier
     *
     * 1.1 findCustomerIdentifier
     *
     * @param string $username
     * @param string $password
     * @return string JSON with parameters: customerNumber, role
     */
    public function findCustomerIdentifier($username, $password)
    {
        $url = 'customer/username/' . $username . '/password/' . $password;
        return $this->get($url);
    }

    /**
     * Get customer address by customer ID
     *
     * 1.2 findPostalAddressesByCustomerNumber
     *
     * @param string $customerId
     * @return string JSON with parameters including: countryCode, postalCode, street, street2, street3, city, state, county,
     * firstName, middleInitial, lastName, companyName, departmentName, phoneNumber, phoneNumber2, phoneNumber3,
     * faxNumber, suffix, title", emailAddress, birthDate
     **/
    public function findPostalAddressesByCustomerNumber($customerId)
    {
        $url = 'postaladdress/customernumber/' . $customerId;
        return $this->get($url);
    }

    /**
     * Find postal addresses by email address
     *
     * 1.3 findPostalAddressesByEmailAddress
     *
     * Get the customer’s demographic information using their email address—this call is like the previous call, but
     * uses emailAddress instead of customerNumber
     *
     * The postal address data block contains demographic information such as name, postalAddress, emailAddress, etc.
     * The  data returned in this call can be used to pre-populate personalized fields on a website
     *
     * @param string $email
     * @return string JSON
     *
     * @mw-wp get_postal_addresses_by_email
     */
    public function findPostalAddressesByEmailAddress($email)
    {
        $url = 'postaladdress/emailaddress/' . urlencode($email);
        return $this->get($url);
    }

    /**
     * Find email addresses by customer number
     *
     * 1.5 findEmailAddressesByCustomerNumber
     *
     * @param string $customerId
     * @return string JSON
     *
     * @mw-wp get_customer_email_by_id
     */
    public function findEmailAddressesByCustomerNumber($customerId)
    {
        $url = 'customer/emailaddress/customernumber/' . $customerId;
        return $this->get($url);
    }

    /**
     * Find customer number by contact ID, orgID and stack name
     *
     * 1.11 findCustomerNumberByContactIdOrgIdStackName
     *
     * @param int $contactId Message Central contact ID.
     * @param int $orgId The affiliate’s org ID in Message Central.
     * @param MC|MC2 $stackName Message central stack name.
     * @return string JSON
     * @throws InvalidEntityException If invalid stackName is given
     *
     * @mw-wp get_customer_number_by_contact_id_org_id_stack_name
     */
    public function findCustomerNumberByContactIdOrgIdStackName($contactId, $orgId, $stackName)
    {
        $url = 'customer/contactid/' . $contactId . '/orgid/' . $orgId . '/stackname/' . $stackName;
        return $this->get($url);
    }

    /**
     * Find customer email by contact ID, orgId and stack name
     *
     * 1.12 findEmailAddressbyContactIdOrgIdStackName
     *
     * @param int $contactId
     * @param int $orgId
     * @param string $stackName
     * @return string JSON
     *
     * @mw-wp get_customer_email_by_contact_id_org_id_stack_name
     */
    public function findEmailAddressByContactIdOrgIdStackName($contactId, $orgId, $stackName)
    {
        $url = 'lookup/emailaddress/contactid/' . $contactId . '/orgid/' . $orgId . '/stack/' . $stackName;
        return $this->get($url);
    }

    /**
     * Find lowest customer number by email address
     *
     * 1.14 findLowestCustomerNumberByEmailAddress
     *
     * Service returns the lowest customer number for e-mail address supplied in the request URL.
     * Result set is not restricted to any portalCode/authGroup.
     *
     * @param string $email
     * @return string JSON - {"customerNumber":"123456789"}"
     *
     * @mw-wp get_lowest_customer_number_by_email
     */
    public function findLowestCustomerNumberByEmailAddress($email)
    {
        $url = 'customer/findlowestactivecustomernumber/emailaddress/' . urlencode($email);
        return $this->get($url);
    }

    /**
     * Create a new customer
     *
     * 1.16 createCustomer
     *
     * Service returns the customer number for newly-created customer.
     *
     * @param $email
     * @param bool $skipDuplicateCheck (optional) Check to see if the email is already associated to a customer in
     * Advantage. If set to true and email already exists, customer number will be returned.
     * @param string $communicationPreference (optional) Set the preferred method of communication for a customer.
     * @return string JSON - {"customerNumber":"123456789"}
     * @throws EmailAlreadyExistsException If email already exists and skipDuplicatateCheck is false

     * @mw-wp put_create_customer_by_email
     */
    public function createCustomer($email, $skipDuplicateCheck = false, $communicationPreference = 'E')
    {
        $url = 'customer/create';
        $payload = [
            'emailAddress'            => $email,
            'skipDuplicateCheck'      => $skipDuplicateCheck,
            'communicationPreference' => $communicationPreference,
        ];
        return $this->post($url, $payload);
    }

    /**
     * Find customer number by contact ID and org ID
     *
     * Get the customer number from their Message Central contact ID and org ID
     *
     * @deprecated
     * @param $contactId
     * @param $orgId
     * @return mixed
     *
     * @mw-wp  get_customer_number_by_contact_id_org_id
     */
    public function findCustomerNumberByContactIdOrgId($contactId, $orgId)
    {
        $url = 'customer/contactid/' . $contactId . '/orgid/' . $orgId;
        return $this->get($url);
    }

    /******************************************************************************************************************
     * 2 Account Services
     **/

    /**
     * Find account by customer number
     *
     * 2.2 findAccountByCustomerNumber
     *
     * Service returns the account information for the customer number supplied in the request URL.
     * The response is restricted to the portalCode/authGroup tied to the token.
     *
     * @param $customerId
     * @return string
     *
     * @mw-wp get_account_by_id
     */
    public function findAccountByCustomerNumber($customerId)
    {
        $url = 'account/customernumber/' . $customerId;
        return $this->get($url);
    }

    /**
     * Find account by email address
     *
     * 2.3 findAccountByEmailAddress
     *
     * @param string $email Email address
     * @return string JSON
     *
     * @mw-wp get_account_by_email
     */
    public function findAccountByEmailAddress($email)
    {
        $url = 'account/emailaddress?email=' . urlencode($email);
        $this->http_args['timeout'] = '10';

        return $this->get($url);
    }

    /**
     * Add a customer account.
     *
     * 2.6 addAccount
     *
     * @param string $customerId
     * @param string $username
     * @param string $password
     * @return sring JSON
     *
     * @mw-wp put_add_account_by_id_username_pass
     */
    public function addAccount($customerId, $username, $password)
    {
        $url = 'account/authentication/create';
        $payload = [
            'customerNumber' => $customerId,
            'username'       => $username,
            'password'       => $password
        ];
        return $this->post($url, $payload);
    }

    /**
     * Update the password associated to the customer’s account
     *
     * 2.8 updatePassword
     *
     * @param string $customerId
     * @param string $username
     * @param string $existingPassword Existing password
     * @param string $newPassword
     * @return string JSON
     *
     * @mw-wp put_update_password
     */
    public function updatePassword($customerId, $username, $existingPassword, $newPassword)
    {
        $url = 'account/update/password';
        $payload = [
            'customerNumber'   => $customerId,
            'username'         => $username,
            'existingPassword' => $existingPassword,
            'newPassword'      => $newPassword
        ];
        return $this->post($url, $payload);
    }

    /******************************************************************************************************************
     * 3 Subscription Services
     **/

    /**
     * Find active subscriptions by customer number
     *
     * 3.1 findActiveSubscriptionsByCustomerNumber
     *
     * @param string $customerNumber
     * @return string JSON
     *
     * @mw-wp get_active_subscriptions_by_id
     */
    public function findActiveSubscriptionsByCustomerNumber($customerNumber)
    {
        $url = 'sub/active/customernumber/' . $customerNumber;
        return $this->get($url);
    }

    /**
     * Find subscriptions by customer number
     *
     * 3.2 findSubscriptionsByCustomerNumber
     *
     * @param string $customerNumber
     * @return string JSON
     *
     * @mw-wp get_subscriptions_by_id
     */
    public function findSubscriptionsByCustomerNumber($customerNumber)
    {
        $url = 'sub/customernumber/' . $customerNumber;
        return $this->get($url);
    }

    /**
     * Find the records combining subscription and postal address using a purchase order number.
     *
     * 3.11 findSubscriptionPostalAddressByPurchaseOrderNumber
     *
     * @param string $purchaseOrderNumber
     * @return string|false JSON or false if purchase order number not found
     *
     * @mw-wp findSubscriptionsAndPostalAddressesByPurchaseOrderNumber
     */
    public function findSubscriptionPostalAddressByPurchaseOrderNumber($purchaseOrderNumber)
    {
        $url = 'sub/postaladdress/purchaseordernumber/' . $purchaseOrderNumber;
        return $this->get($url);
    }

    /******************************************************************************************************************
     * 4 Order Services
     **/

    /******************************************************************************************************************
     * 5 Data Services
     **/

    /**
     * Find login aggregate data for a given username and password
     *
     * 5.3 findLoginAggregateData
     *
     * @param string $username An advantage username
     * @param string $password An advantage password to accompany the Username
     * @return string JSON
     *
     * @mw-wp get_aggregate_data_by_login
     */
    public function findLoginAggregateData($username, $password)
    {
        $url = 'data/username/' . $username . '/password/' . $password;
        return $this->get($url);
    }

    /******************************************************************************************************************
     * 6 AMB Services
     **/

    /******************************************************************************************************************
     * 7 List Services
     **/

    /**
     * Find customer list signups by customer number
     *
     * 7.1 findCustomerListSignupsByCustomerNumber
     *
     * @param string $customerId
     * @return string JSON
     *
     * @mw-wp get_customer_list_signups_by_id
     */
    public function findCustomerListSignupsByCustomerNumber($customerId)
    {
        $url = 'adv/list/signup/customernumber/' . $customerId;
        return $this->get($url);
    }

    /**
     * Find the customer's list signups using their email address.
     *
     * 7.3 findCustomerListSignupsByEmailAddress
     *
     * @param $email
     * @return string JSON
     *
     * @mw-wp get_customer_list_signups_by_email
     */
    public function findCustomerListSignupsByEmailAddress($email)
    {
        $url = 'adv/list/signup/emailaddress/' . $email;
        return $this->get($url);
    }

    /**
     * Add a customer signup to a list.
     *
     * 7.8 addCustomerSignup
     *
     * @param string $email
     * @param string $listCode Code that identifies the list to which the customer will subscribe.
     * @param string $sourceCode Source from which signup originated.
     * @param array $attributes (optional)
     * @return string JSON
     *
     * @mw-wp put_customer_signup_by_email
     */
    public function addCustomerSignup($email, $listCode, $sourceCode, $attributes = null)
    {
        $url = 'list/customersignup/add';
        $payload = array(
            'emailAddress'  => $email,
            'listCode'      => $listCode,
            'sourceId'      => $sourceCode
        );
        if ($attributes !== null) {
            $payload = array_merge($payload, $attributes);
        }
        return $this->post($url, $payload);
    }

    /**
     * Unsubscribe a customer signup.
     *
     * 7.11 unsubCustomerSignup
     *
     * @param string $listCode Code that identifies the list from which the customer will unsubscribe
     * @param string $email The customer’s e-mail address
     * @param string $referenceNumber (optional) Reference number to track this unsub. This can be any random, alphanumeric ID
     * If no reference number is supplied, MW2 generates it by applying the hash algorithm on the other required fields
     * supplied.
     * @return string JSON
     *
     * @mw-wp put_unsub_customer_signup
     */
    public function unsubscribeCustomerSignup($listCode, $email, $referenceNumber = null)
    {
        $url = '/middleware/list/customersignup/unsub';
        $payload = array(
            'listCode'     => $listCode,
            'emailAddress' => $email,
        );
        if ($referenceNumber) {
            $payload['referenceNumber'] = $referenceNumber;
        }
        return $this->post($url, $payload);
    }

    /******************************************************************************************************************
     * 8 Email Services
     **/

    /**
     * Get email fulfillment history for a given customer ID
     *
     * 8.1 findEmailFulfillmentHistoryByCustomerNumber
     *
     * @param string $customerNumber
     * @return string JSON
     *
     * @mw-wp get_email_fulfillment_history_by_id
     */
    public function findEmailFulfillmentHistoryByCustomerNumber($customerNumber)
    {
        $url = 'emailfulfillment/history/customernumber/' . $customerNumber;
        return $this->get($url);
    }

    /******************************************************************************************************************
     * 9 Geolocation Services
     **/

    /******************************************************************************************************************
     * 10 Marketing Services
     **/

    /******************************************************************************************************************
     * 11 Open Call Services
     **/

    /******************************************************************************************************************
     * 12 Targeting Services
     **/

    /**
     * Find affiliate facts by customer number
     *
     * 12.1 findAffiliateFactsByCustomerNumber
     *
     * @param string $customerNumber
     * @return string JSON
     *
     * @mw-wp get_affiliate_facts_by_id
     */
    public function findAffiliateFactsByCustomerNumber($customerNumber)
    {
        $url = 'target/affiliate/fact/customernumber/' . $customerNumber;
        return $this->get($url);
    }

    /**
     * Find list facts by customer number
     *
     * 12.2 findListFactsByCustomerNumber
     *
     * @param $customerNumber
     * @return string JSON
     *
     * @mw-wp get_list_facts_by_id
     */
    public function findListFactsByCustomerNumber($customerNumber)
    {
        $url = 'target/list/fact/customernumber/' . $customerNumber;
        return $this->get($url);
    }

    /**
     * Find affiliate tags by customer number
     *
     * 12.3 findAffiliateTagsByCustomerNumber
     *
     * @param string $customerNumber
     * @return string|false JSON

     * @mw-wp get_affiliate_tags_by_id
     */
    public function findAffiliateTagsByCustomerNumber($customerNumber)
    {
        $url = 'target/affiliate/tag/customernumber/' . $customerNumber;
        return $this->get($url);
    }

    /**
     * Retrieve affiliate tagging information based off of an email address and owning org
     *
     * 12.4 findAffiliateTagsByEmailAddressOwningOrg
     *
     * @param string $email
     * @param string $owningOrg Unique identifier of organization.
     * @return string JSON
     *
     * @mw-wp get_affiliate_tags_by_email_owning_org
     */
    public function findAffiliateTagsByEmailAddressOwningOrg($email, $owningOrg)
    {
        $url = 'target/affiliate/tag/emailaddress/' . urlencode($email) . '/owningorg/' . $owningOrg;
        return $this->get($url);
    }

    /**
     * Create an Affiliate Tag
     *
     * 12.5 createAffiliateTags
     *
     * @param string $customerId
     * @param string $email
     * @param string $tagName
     * @param string $tagValue
     * @param string $owningOrg (optional)
     * @return string
     *
     * @mw-wp put_create_affiliate_tags
     */
    public function createAffiliateTags($customerId, $email, $tagName, $tagValue, $owningOrg = null)
    {
        $url = 'target/affiliate/tag/create';
        $payload = array(
            'customerNumber'    => $customerId,
            'emailAddress'      => $email,
            'tagName'           => $tagName,
            'tagValue'          => $tagValue,
        );
        if (!empty($owningOrg)) {
            $payload['ownOrg'] = $owningOrg;
        }
        return $this->post($url, $payload);
    }

    /******************************************************************************************************************
     * 13 Owningorg Services
     **/

    /******************************************************************************************************************/

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
        } catch (ClientException $e) {
            switch ($e->getResponse()->getStatusCode()) {
                // 422 Unprocessable Entity
                case 422:
                    throw new InvalidEntityException('StackName is not valid');
                // Invalid token exception
                case 403:
                    throw new InvalidTokenException('Invalid token');
                default:
                    // Rethrow everything else
                    throw $e;
            }
        } catch (ServerException $e) {
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
     * @return bool|string
     * @throws InvalidCustomerException
     * @throws MiddlewareException
     */
    protected function post($url, $payload)
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
            $this->log->info('Response from call', ['response' => $response, 'url' => $url, 'payload'=> $payload]);

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

        } catch (ClientException $e) {
            switch ($e->getResponse()->getStatusCode()) {
                // Invalid token exception
                case 403:
                    throw new InvalidTokenException('Invalid token');
                default:
                    // Rethrow everything else
                    throw $e;
            }

        } catch (ServerException $e) {

            if ($e->getResponse()->getStatusCode() !== 500) {
                throw $e;
            }

            switch (true) {
                // Advantage error
                case (strpos($e->getResponse()->getBody(), 'Failed, advantage connection') !== false):
                    throw new AdvantageConnectionException('Unable to connect to Advantage');
                // Invalid customer
                case (strpos($e->getResponse()->getBody(), 'Customer not found for the update') !== false):
                    throw new InvalidCustomerException('Customer not found for the update');
                // Advantage customer not found
                case (strpos($e->getMessage(), 'Advantage customer not found') !== false):
                    throw new InvalidCustomerException('Advantage customer not found');
                // Email already exists
                case (strpos($e->getMessage(), 'Email') !== false
                    && strpos($e->getMessage(), 'already exists') !== false):
                    throw new EmailAlreadyExistsException('Email already exists');
                default:
                    throw $e;
            }
        }
    }
}
