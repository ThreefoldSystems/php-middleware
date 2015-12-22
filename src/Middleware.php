<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited.
 */

namespace Threefold\Middleware;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface as Logger;
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
     * The URL to use for the API calls.
     *
     * @var string
     */
    protected $url;
    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzleClient;

    /**
     * Constructor
     *
     * @param Logger $log Logger
     * @param string $url
     * @param string $token
     */
    public function __construct(Logger $log, $url, $token)
    {
        $this->log = $log;
        $this->url = $url;
        $this->token = $token;

        $this->guzzleClient = new Client(['base_uri' => $this->url]);
        $this->url = '';
    }

    /**
     * Find customer number by contact ID and org ID
     *
     * Get the customer number from their M essage Central Contact ID and Org ID
     *
     * @param $contactId
     * @param $orgId
     * @return mixed
     */
    public function getCustomerNumberByContactIdOrgId($contactId, $orgId)
    {
        $url = $this->url.'customer/contactid/'.$contactId.'/orgid/'.$orgId;

        return $this->get($url);
    }

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
    public function getCustomerNumberByContactIdOrgIdStackName($contactId, $orgId, $stackName)
    {
        $url = $this->url.'customer/contactid/'.$contactId.'/orgid/'.$orgId.'/stackname/'.$stackName;

        return $this->get($url);
    }

    /**
     * Find account by email address
     *
     * Get Customer account by email address
     *
     * @param string $email Email address
     * @return object
     */
    public function getAccountByEmail($email)
    {
        $url = $this->url.'account/emailaddress?email='.urlencode($email);
        $this->http_args['timeout'] = '10';

        return $this->get($url);
    }

    /**
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
        $url = $this->url.'data/username/'.$username.'/password/'.$password;

        return $this->get($url);
    }

    /**
     * Find subscriptions by customer number
     *
     * Get customer subscriptions for a given customer ID, both active AND inactive
     *
     * @param string $customerId
     * @return array
     */
    public function getSubscriptionsById($customerId)
    {
        $url = $this->url.'sub/customernumber/'.$customerId;

        return $this->get($url);
    }

    /**
     * Find active subscriptions by customer number
     *
     * Get *ACTIVE* Customer Subscriptions for a given customer ID
     *
     * @param string $customerId
     * @return array
     */
    public function getActiveSubscriptionsById($customerId)
    {
        $url = $this->url.'sub/active/customernumber/'.$customerId;

        return $this->get($url);
    }

    /**
     * Find email addresses by customer number
     *
     * Get customer email address by customer ID
     *
     * @param string $customerId
     * @return array An array of email addresses for the given customer ID
     */
    public function getCustomerEmailById($customerId)
    {
        $url = $this->url.'customer/emailaddress/customernumber/'.$customerId;

        return $this->get($url);
    }

    /**
     * Get email fulfillment history for a given customer ID
     *
     * @param string $customerId
     * @return array
     */
    public function getEmailFulfillmentHistoryById($customerId)
    {
        $url = $this->url.'emailfulfillment/history/customernumber/'.$customerId;

        return $this->get($url);
    }

    /**
     * Find postal addresses by customer number
     *
     * Get customer address by customer ID
     *
     * @param string $customerId
     * @return array Associative array of returned data
     **/
    public function getCustomerAddressById($customerId)
    {
        $url = $this->url.'postaladdress/customernumber/'.$customerId;

        return $this->get($url);
    }

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
    public function getAccountById($customerId)
    {
        $url = $this->url.'account/customernumber/'.$customerId;

        return $this->get($url);
    }

    /**
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

        $url = $this->url.'customer/username/'.$username.'/password/'.$password;
        $result = $this->get($url);

        return $result;
    }

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
    public function getPostalAddressesByEmail($email)
    {
        $url = $this->url.'postaladdress/emailaddress/'.urlencode($email);

        return $this->get($url);
    }

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
    public function getLowestCustomerNumberByEmail($email)
    {
        $url = $this->url.'customer/findlowestactivecustomernumber/emailaddress/'.urlencode($email);

        return $this->get($url);
    }

    /**
     * Get Customer subscriptions by login
     *
     * @param string $username
     * @param string $password
     * @return object PHP object of user data
     **/
    public function getSubscriptionsByLogin($username, $password)
    {
        $content = $this->get_customer_by_login($username, $password);
        if (isset($content->customerNumber) && !is_null($content->customerNumber)) {
            return $this->get_subscriptions_by_id($customer->customerNumber);
        }
    }

    /**
     * Find customer list signups by customer number
     *
     * Find the customer’s list signups using the customer number.
     *
     * @param $customerId
     * @return array
     */
    public function getCustomerListSignupsById($customerId)
    {
        $url = $this->url.'adv/list/signup/customernumber/'.$customerId;

        return $this->get($url);
    }

    /**
     * Find affiliate facts by customer number
     *
     * Retrieve affiliate facts based off of a customer number.
     *
     * @param $customerId
     * @return array
     */
    public function getAffiliateFactsById($customerId)
    {
        $url = $this->url.'target/affiliate/fact/customernumber/'.$customerId;

        return $this->get($url);
    }

    /**
     * Find list facts by customer number
     *
     * Retrieve list facts based off of a customer number
     *
     * @param $customerId
     * @return array
     */
    public function getListFactsById($customerId)
    {
        $url = $this->url.'target/list/fact/customernumber/'.$customerId;

        return $this->get($url);
    }

    /**
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
        $url = $this->url.'target/affiliate/tag/'.$customerId;

        return $this->get($url);
    }

    /**
     * Make GET request
     *
     * A helper method to reduce repetition.
     *
     * @param string $url
     * @param string $token (optional)
     * @return array Associative array of returned data. Returns WP_Error object on error
     * @throws MiddlewareException
     */
    protected function get($url, $token = null)
    {
        if (!$token) {
            $token = $this->getToken();
        }

        $this->log->info('Middleware GET Request to: '.$url.' (token: '.$token.')');

        try {
            // Make request
            $headers = ['token' => $token];
            $response = $this->guzzleClient->request(
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
                    $this->log->info('Response contents', (array) $contents);

                    return $contents;

                case 204:
                    // No content - no results found
                    return false;

//				case 422:
//					// Some middleware calls return 422 HTTP code if nothing is found, others return a 200 code but empty content.
//					$this->log->error('Result: ', $result);
//					break;

                default:
                    $this->log->error('Response contents', (array) $contents);
                    throw new MiddlewareException('Bad result. Status Code: '.$statusCode);

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
        }
    }

    /**
     * A method to give a list of middleware calls by method
     *
     * @param string $type A string describing the type of calls we want e.g. get, put, update, delete
     * @param string $input A string describing what inputs to use. e.g. login, customer_ID
     * @return array Associative array of methods that match the requested parameters
     **/
    public function listMethods($type, $input)
    {
        $methods = get_class_methods($this);

        // Check and clean up the $type parameter
        if (in_array($type, array('get', 'put', 'update', 'delete'))) {
            $type .= '_';
        } else {
            trigger_error('Invalid Value: '.$type.' for Parameter $type');

            return;
        }

        // Check and cleanup the $input parameter
        if ($input == 'customer_ID') {
            $input = '_by_id';
        } elseif ($input == 'login') {
            $input = '_by_login';
        } elseif ($input == 'email') {
            $input = '_by_email';
        } else {
            trigger_error('Invalid Value: '.$input.' for Parameter $input');

            return;
        }

        // Cycle through all the methods, and find those that match the type and input
        foreach ($methods as $m) {
            if (strpos($m, $type) !== false) {
                if (strpos($m, $input)) {
                    $result[] = $m;
                }
            }
        }

        return $result;
    }
}