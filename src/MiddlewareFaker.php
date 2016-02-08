<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace Threefold\Middleware;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Fake Middleware class for testing
 *
 * @author Aine Hickey <ahickey@threefoldsystems.com>
 * @package Threefold/Middleware
 */
class MiddlewareFaker extends Middleware implements MiddlewareInterface
{
    /**
     * @var Logger
     */
    protected $log;

    /**
     * Middleware Faker constructor.
     *
     * @param Logger $log
     * @param ClientInterface $clientInterface
     * @param string $token
     */
    public function __construct(Logger $log, ClientInterface $clientInterface, $token)
    {
        $this->log = $log;
    }

    /**
     * Find customer number by contact ID and org ID
     *
     * Get the customer number from their Message Central Contact ID and Org ID
     *
     * @param $contactId
     * @param $orgId
     * @return mixed
     */
    public function getCustomerNumberByContactIdOrgId($contactId, $orgId)
    {

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
        return '[
  {
    "cviNbr": "000008623499",
    "customerNumber": "000072121625",
    "role": "",
    "temp": false,
    "password": "xxx",
    "id": {
      "userName": "MCGRATH14905",
      "portalCode": {
        "authGroup": "ILV"
      },
      "authType": "L"
    },
    "denyAccess": "N",
    "authStatus": "A"
    }
]';
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
        return '{
  "accounts": null,
  "emailAddresses": null,
  "subscriptionsAndOrders": null,
  "postalAddresses": null
}';
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
        return '[
  {
    "temp": false,
    "circStatus": "R",
    "issuesRemaining": 42,
    "memberCat": null,
    "memberOrg": "",
    "renewMethod": "D",
    "term": 52,
    "termNumber": 1,
    "subType": "",
    "startDate": "2015-06-22 00:00:00",
    "expirationDate": "2016-06-13 00:00:00",
    "finalExpirationDate": "2016-06-13 00:00:00",
    "deliveryCode": "EM",
    "rate": null,
    "remainingLiability": null,
    "id": {
      "customerNumber": "000072121625",
      "subRef": "000029014905",
      "item": {
        "itemDescription": "Costa Rica Insider",
        "itemNumber": "CRI",
        "itemType": "CIR",
        "serviceCode": " "
      }
    }
  },
  {
    "temp": false,
    "circStatus": "R",
    "issuesRemaining": 118,
    "memberCat": null,
    "memberOrg": "",
    "renewMethod": "D",
    "term": 120,
    "termNumber": 1,
    "subType": "",
    "startDate": "2015-08-01 00:00:00",
    "expirationDate": "2025-07-01 00:00:00",
    "finalExpirationDate": "2025-07-01 00:00:00",
    "deliveryCode": "EM",
    "rate": null,
    "remainingLiability": null,
    "id": {
      "customerNumber": "000072121625",
      "subRef": "000029014917",
      "item": {
        "itemDescription": "IL\'s Explorers Club",
        "itemNumber": "EXC",
        "itemType": "CIR",
        "serviceCode": ""
      }
    }
  },
  {
    "temp": false,
    "circStatus": "R",
    "issuesRemaining": 118,
    "memberCat": null,
    "memberOrg": "",
    "renewMethod": "D",
    "term": 120,
    "termNumber": 1,
    "subType": "",
    "startDate": "2015-06-01 00:00:00",
    "expirationDate": "2025-05-01 00:00:00",
    "finalExpirationDate": "2025-05-01 00:00:00",
    "deliveryCode": "EM",
    "rate": null,
    "remainingLiability": null,
    "id": {
      "customerNumber": "000072121625",
      "subRef": "000029015282",
      "item": {
        "itemDescription": "Platinum Circle",
        "itemNumber": "PLC",
        "itemType": "CIR",
        "serviceCode": ""
      }
    }
  },
  {
    "temp": false,
    "circStatus": "R",
    "issuesRemaining": 117,
    "memberCat": null,
    "memberOrg": "",
    "renewMethod": "D",
    "term": 120,
    "termNumber": 1,
    "subType": "",
    "startDate": "2015-06-01 00:00:00",
    "expirationDate": "2025-05-01 00:00:00",
    "finalExpirationDate": "2025-05-01 00:00:00",
    "deliveryCode": "EM",
    "rate": null,
    "remainingLiability": null,
    "id": {
      "customerNumber": "000072121625",
      "subRef": "000029014918",
      "item": {
        "itemDescription": "Il Publisher\'s Roundtable",
        "itemNumber": "RND",
        "itemType": "CIR",
        "serviceCode": " "
      }
    }
  }
]';
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
        return '[
  {
    "temp": false,
    "circStatus": "R",
    "issuesRemaining": 42,
    "memberCat": null,
    "memberOrg": "",
    "renewMethod": "D",
    "term": 52,
    "termNumber": 1,
    "subType": "",
    "startDate": "2015-06-22 00:00:00",
    "expirationDate": "2016-06-13 00:00:00",
    "finalExpirationDate": "2016-06-13 00:00:00",
    "deliveryCode": "EM",
    "rate": null,
    "remainingLiability": null,
    "id": {
      "customerNumber": "000072121625",
      "subRef": "000029014905",
      "item": {
        "itemDescription": "Costa Rica Insider",
        "itemNumber": "CRI",
        "itemType": "CIR",
        "serviceCode": " "
      }
    }
  },
  {
    "temp": false,
    "circStatus": "R",
    "issuesRemaining": 118,
    "memberCat": null,
    "memberOrg": "",
    "renewMethod": "D",
    "term": 120,
    "termNumber": 1,
    "subType": "",
    "startDate": "2015-08-01 00:00:00",
    "expirationDate": "2025-07-01 00:00:00",
    "finalExpirationDate": "2025-07-01 00:00:00",
    "deliveryCode": "EM",
    "rate": null,
    "remainingLiability": null,
    "id": {
      "customerNumber": "000072121625",
      "subRef": "000029014917",
      "item": {
        "itemDescription": "IL\'s Explorers Club",
        "itemNumber": "EXC",
        "itemType": "CIR",
        "serviceCode": ""
      }
    }
  },
  {
    "temp": false,
    "circStatus": "R",
    "issuesRemaining": 118,
    "memberCat": null,
    "memberOrg": "",
    "renewMethod": "D",
    "term": 120,
    "termNumber": 1,
    "subType": "",
    "startDate": "2015-06-01 00:00:00",
    "expirationDate": "2025-05-01 00:00:00",
    "finalExpirationDate": "2025-05-01 00:00:00",
    "deliveryCode": "EM",
    "rate": null,
    "remainingLiability": null,
    "id": {
      "customerNumber": "000072121625",
      "subRef": "000029015282",
      "item": {
        "itemDescription": "Platinum Circle",
        "itemNumber": "PLC",
        "itemType": "CIR",
        "serviceCode": ""
      }
    }
  },
  {
    "temp": false,
    "circStatus": "R",
    "issuesRemaining": 117,
    "memberCat": null,
    "memberOrg": "",
    "renewMethod": "D",
    "term": 120,
    "termNumber": 1,
    "subType": "",
    "startDate": "2015-06-01 00:00:00",
    "expirationDate": "2025-05-01 00:00:00",
    "finalExpirationDate": "2025-05-01 00:00:00",
    "deliveryCode": "EM",
    "rate": null,
    "remainingLiability": null,
    "id": {
      "customerNumber": "000072121625",
      "subRef": "000029014918",
      "item": {
        "itemDescription": "Il Publisher\'s Roundtable",
        "itemNumber": "RND",
        "itemType": "CIR",
        "serviceCode": " "
      }
    }
  }
]';
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
        return '[
  {
    "emailAddress": "CMCGRATH@THREEFOLDSYSTEMS.COM",
    "temp": false
  }
]';
    }

    /**
     * Get email fulfillment history for a given customer ID
     *
     * @param string $customerId
     * @return array
     */
    public function getEmailFulfillmentHistoryById($customerId)
    {
        return '[
  {
    "customerNumber": "000072121625",
    "itemNumber": "CRI",
    "emailAddressId": "000041321248",
    "dateSent": "2015-09-13 00:00:00",
    "emailSubject": "CRI Interview Series 5",
    "sentStatus": "D",
    "bounceReason": "",
    "stackName": "MC",
    "orgId": "12",
    "mailingId": "166631",
    "contactId": "3906009",
    "lastResentDate": null
  },
  {
    "customerNumber": "000072121625",
    "itemNumber": "PLC",
    "emailAddressId": "000041321248",
    "dateSent": "2015-09-01 00:00:00",
    "emailSubject": "2015-09-01_INA_Alert_PLC",
    "sentStatus": "D",
    "bounceReason": "",
    "stackName": "MC",
    "orgId": "12",
    "mailingId": "163893",
    "contactId": "3906009",
    "lastResentDate": null
  }
]';
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
        return '[
  {
    "countryCode": "IRL",
    "postalCode": "",
    "street": "CARRIGANORE",
    "street2": "WATERFORD",
    "street3": "",
    "city": "WATERFORD",
    "state": "",
    "county": "",
    "firstName": "CIARAN",
    "middleInitial": "",
    "lastName": "MCGRATH",
    "companyName": "THREE FOLD SYSTEMS",
    "departmentName": "",
    "phoneNumber": "",
    "phoneNumber2": "",
    "phoneNumber3": "",
    "faxNumber": "",
    "suffix": "",
    "title": "",
    "emailAddress": {
      "emailAddress": "CMCGRATH@THREEFOLDSYSTEMS.COM",
      "temp": false
    },
    "birthDate": "",
    "temp": false,
    "id": {
      "customerNumber": "000072121625",
      "addressCode": "ADDR-01",
      "addressFlag": "0"
    }
  }
]';
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
        return '[
  {
    "cviNbr": "000008623499",
    "customerNumber": "000072121625",
    "role": "",
    "temp": false,
    "password": "WATERFORD",
    "id": {
      "userName": "MCGRATH14905",
      "portalCode": {
        "authGroup": "ILV"
      },
      "authType": "L"
    },
    "denyAccess": "N",
    "authStatus": "A"
  }
]';
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
        return '{
  "customerNumber": null,
  "role": null
}';
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
        return '[
  {
    "countryCode": "IRL",
    "postalCode": "",
    "street": "CARRIGANORE",
    "street2": "WATERFORD",
    "street3": "",
    "city": "WATERFORD",
    "state": "",
    "county": "",
    "firstName": "CIARAN",
    "middleInitial": "",
    "lastName": "MCGRATH",
    "companyName": "THREE FOLD SYSTEMS",
    "departmentName": "",
    "phoneNumber": "",
    "phoneNumber2": "",
    "phoneNumber3": "",
    "faxNumber": "",
    "suffix": "",
    "title": "",
    "emailAddress": {
      "emailAddress": "CMCGRATH@THREEFOLDSYSTEMS.COM",
      "temp": false
    },
    "birthDate": "",
    "temp": false,
    "id": {
      "customerNumber": "000072121625",
      "addressCode": "ADDR-01",
      "addressFlag": "0"
    }
  }
]';
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
        return '{
  "customerNumber": "000072121625"
}';
    }

    /**
     * Get Customer subscriptions by login
     *
     * @param string $username
     * @param string $password
     * @return string JSON string
     **/
    public function getSubscriptionsByLogin($username, $password)
    {
        return '[
    {
        "temp":false,
        "circStatus":"R",
        "issuesRemaining":22,
        "memberCat":null,
        "memberOrg":"",
        "renewMethod":"D",
        "term":52,
        "termNumber":1,
        "subType":"",
        "purchaseOrderNumber":null,
        "startDate":"2015-06-22 00:00:00",
        "expirationDate":"2016-06-13 00:00:00",
        "finalExpirationDate":"2016-06-13 00:00:00",
        "deliveryCode":"EM",
        "rate":0.000000,
        "remainingLiability":0.000000,
        "promoCode":"COMP01",
        "graceFlag":"Y",
        "graceIssues":0,
        "id":{
            "customerNumber":"000072121625",
            "subRef":"000029014905",
            "item":{
                "itemDescription":"Costa Rica Insider",
                "itemNumber":"CRI",
                "itemType":"CIR",
                "serviceCode":" ",
                "packageFlag":"N"
            }
        }
    },
    {
        "temp":false,
        "circStatus":"R",
        "issuesRemaining":113,
        "memberCat":null,
        "memberOrg":"",
        "renewMethod":"D",
        "term":120,
        "termNumber":1,
        "subType":"",
        "purchaseOrderNumber":null,
        "startDate":"2015-08-01 00:00:00",
        "expirationDate":"2025-07-01 00:00:00",
        "finalExpirationDate":"2025-07-01 00:00:00",
        "deliveryCode":"EM",
        "rate":0.000000,
        "remainingLiability":0.000000,
        "promoCode":"COMP01",
        "graceFlag":"Y",
        "graceIssues":0,
        "id":{
            "customerNumber":"000072121625",
            "subRef":"000029014917",
            "item":{
                "itemDescription":"IL\'s Explorers Club",
                "itemNumber":"EXC",
                "itemType":"CIR",
                "serviceCode":"",
                "packageFlag":"N"
            }
        }
    },
    {
        "temp":false,
        "circStatus":"R",
        "issuesRemaining":113,
        "memberCat":null,
        "memberOrg":"",
        "renewMethod":"D",
        "term":120,
        "termNumber":1,
        "subType":"",
        "purchaseOrderNumber":null,
        "startDate":"2015-06-01 00:00:00",
        "expirationDate":"2025-05-01 00:00:00",
        "finalExpirationDate":"2025-05-01 00:00:00",
        "deliveryCode":"EM",
        "rate":0.000000,
        "remainingLiability":0.000000,
        "promoCode":"COMP01",
        "graceFlag":"Y",
        "graceIssues":0,
        "id":{
            "customerNumber":"000072121625",
            "subRef":"000029015282",
            "item":{
                "itemDescription":"Platinum Circle",
                "itemNumber":"PLC",
                "itemType":"CIR",
                "serviceCode":"",
                "packageFlag":"N"
            }
        }
    },
    {
        "temp":false,
        "circStatus":"R",
        "issuesRemaining":133,
        "memberCat":null,
        "memberOrg":"",
        "renewMethod":"D",
        "term":120,
        "termNumber":1,
        "subType":"",
        "purchaseOrderNumber":null,
        "startDate":"2015-06-01 00:00:00",
        "expirationDate":"2025-05-01 00:00:00",
        "finalExpirationDate":"2025-05-01 00:00:00",
        "deliveryCode":"EM",
        "rate":0.000000,
        "remainingLiability":0.000000,
        "promoCode":"COMP01",
        "graceFlag":"Y",
        "graceIssues":0,
        "id":{
            "customerNumber":"000072121625",
            "subRef":"000029014918",
            "item":{
                "itemDescription":"Il Publisher\'s Roundtable",
                "itemNumber":"RND",
                "itemType":"CIR",
                "serviceCode":" ",
                "packageFlag":"N"
            }
        }
    }
]';
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
        return '[
  {
    "status": "A",
    "referenceNumber": "MSnull",
    "customerNumber": "000072121625",
    "emailAddress": "CMCGRATH@THREEFOLDSYSTEMS.COM",
    "addressCode": "",
    "listCode": "GENERAL",
    "profileId": "000000000000",
    "dateAdded": "2015-01-07",
    "emailId": "000041321248",
    "listDescription": "CC_General",
    "demographicData": "        00",
    "deliveryType": "",
    "listCategory": "999",
    "sourceCode": "XBALTIMO",
    "confirmationDate": null,
    "originalSourceCode": "XBALTIMO",
    "isDoubleOptIn": false,
    "reasonCode": "00",
    "lastEnrollment": null
  }
]';
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
        return 'No response received';
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
        return 'No response received';
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
        return 'The requested resource is not available.';
    }
}
