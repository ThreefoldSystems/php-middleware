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
        return '{
"customerNumber": "000068210556",
"role": ""
}';
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
        return '
        {
countryCode:""
postalCode:"212015442"
street:"1001 CATHEDRAL STREET"
street2:"4TH FLOOR"
street3:""
city:"BALTIMORE"
state:"MD"
county:"BALTIMORE CITY"
firstName:"GEORGE"
middleInitial:""
lastName:"COSTANZA"
companyName:"VANDELAY INDUSTRIES"
departmentName:"ARCHITECTURE"
phoneNumber:"410 867-5309"
phoneNumber2:""
phoneNumber3:""
faxNumber:""
suffix:""
title:""
emailAddress: {
    emailAddress:GCOSTANZA@VANDELAYINDUSTRIES.COM
    temp:false
}
birthDate:""
temp:false
id:{
    customerNumber:"000068210556"
    addressCode:"ADDR-01"
    addressFlag:"0"
    }
}';
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
        return '{
    countryCode:""
    postalCode:"212015442"
    street:"1001 CATHEDRAL STREET"
    street2:"4TH FLOOR"
    street3:""
    city:"BALTIMORE"
    state:"MD"
    county:"BALTIMORE CITY"
    firstName:"GEORGE"
    middleInitial:""
    lastName:"COSTANZA"
    companyName:"VANDELAY INDUSTRIES"
    departmentName:"ARCHITECTURE"
    phoneNumber:"410 867-5309"
    phoneNumber2:""
    phoneNumber3:""
    faxNumber:""
    suffix:""
    title:""
    emailAddress: {
        emailAddress:GCOSTANZA@VANDELAYINDUSTRIES.COM
        temp:false
    }
    birthDate:""
    temp:false
    id: {
        customerNumber: "000068210556"
        addressCode:"ADDR-01"
        addressFlag:"0"
    }
}
';
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
        return '{emailAddress: "GCOSTANA@VANDELAYINDUSTRIES.COM"}';
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
        return '{
    "customerNumber": "000060056499"
    "role": ""
}';
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
        return '{
        "emailAddress": GCOSTANZA@YAHOO.COM
        "emailId": "000020782464"
        }';
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
        return '{"customerNumber": "000060056499"}';
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
        return '{"customerNumber": "000060056499"}';
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
        return 'Deprecated';
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
        return '{
    cviNbr: "000006804705"
    customerNumber: "000068211152"
    role: ""
    password: "PASSWORD"
    id:
    {
        userName: "MIDDLEWARETESTER@GMAIL.COM"
        portalCode:
        {
            authGroup: "PPR"
        }
        authType: "L"
    }
    denyAccess: "N"
    authStatus: "A"
}';
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
        return '[
    {
        cviNbr: "000006804705"
        customerNumber: "000068211152"
        role: ""
        password: "PASSWORD"
        id: {
            userName: "MIDDLEWARETESTER@GMAIL.COM"
            portalCode: {
                authGroup: "PPR"
            }
            authType: "L"
        }
        denyAccess: "N"
        authStatus: "A"
    }
}
,
{
    cviNbr:"000006804712"
    customerNumber:"000068211162"
    role:
        ""
    password:"2000"
    id:
    {
        userName:"PHAMTEST11162"
        portalCode:
        {
            authGroup:"PPR"
        }
        authType:"L
    }
    denyAccess: "N"
    authStatus: "A"
}
]';
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
        return '';
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
        return '';
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
        return '{
    temp:false
    circStatus:"C"
    issuesRemaining:0
    memberCat:null
    memberOrg:""
    renewMethod:"A"
    term:12
    termNumber:1
    subType:"LIFE"
    purchaseOrder
    Number:"10823896"
    startDate:"2007-09-0100:00:00"
    expirationDate:"2009-08-01 00:00:00"
    finalExpirationDate:"2009-08-0100:00:00"
    deliveryCode:"EM"
    rate:0
    remainingLiability:0
    promoCode: "MLHARA08"
    graceFlag: Y
    graceIssues: 0
    id:
    {
        customerNumber: "000069070765"
        subRef:"000025184612"
        item:
        {
            itemDescription:"Advanced Income"
            itemNumber:"BTR"
            itemType:"CIR"
            serviceCode:""
        }
    }
}';
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
        return '{
    temp:false
    circStatus:"C"
    issuesRemaining:0
    memberCat:null
    memberOrg:""
    renewMethod:"A"
    term:12
    termNumber:1
    subType:"LIFE"
    purchaseOrderNumber: "10823896"
    startDate:"2007-09-01 00:00:00"
    expirationDate:"2009-08-01 00:00:00"
    finalExpirationDate:"2009-08-01 00:00:00"
    deliveryCode:"EM"
    rate:0remainingLiability:0
    promoCode: "MLHARA08"
    graceFlag: Y
    graceIssues: 0
    id: {
        customerNumber:"000069070765"
        subRef:"000025184612"
        item: {
            itemDescription:"Advanced Income"
            itemNumber:"BTR"
            itemType:"CIR"
            serviceCode:""
        }
    }
}
        ';
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
        return '"subscription"
:
[
    {
        "circStatus": "R",
        "issuesRemaining": 12,
        "memberCat": null,
        "memberOrg": "",
        "renewMethod": "C",
        "term": 12,
        "termNumber": 1,
        "subType": "",
        "purchaseOrderNumber": "10823896",
        "itemNumber": "LHA",
        "customerNumber": "000070043064",
        "subRef": "000030357152",
        "itemDescription": "Logical Health Alternatives",
        "itemType": "CIR",
        "serviceCode": " ",
        "authGroup": "LHA",
        "startDate": "2015-12-01",
        "expirationDate": "2016 -11-01",
        "finalExpirationDate": "2016-11-01",
        "deliveryCode": "ME",
        "rate": 37,
        "remainingLiability": 37,
        "promoCode": "MLHARA08"
        "graceFlag": Y
        "graceIssues": 0
    }
],
    "postalAddress"
:
[
    {
        "countryCode": "US",
        "postalCode": "020451218",
        "street": "4 RIPLEY RD",
        "street2": null,
        "street3": null,
        "city": "HULL",
        "state": "MA",
        "county": "PLYMOUTH",
        "firstName": "PAUL",
        "middleInitial": null,
        "lastName": "WIERS",
        "companyName": null,
        "departmentName": null,
        "phoneNumber": "617 548 -7842",
        "phoneNumber2": null,
        "phoneNumber3": null,
        "faxNumber": null,
        "suffix": null,
        "title": null,
        "emailAddress": null,
        "birthDate": "",
        "temp": false,
        "id": {
            "customerNumber": "000070043064",
            "addressCode": "ADDR-01",
            "addressFlag": "0"
        }
    }
]
}';
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
        return '{
    "accounts"
:
    [
        {
            "role": " ", "authType": "L", "password": "BOSC0", "username": "GCONSTANZ1" "portalCode": {
            "authGroup": "PSI"
        }
            "customerNumber": "000000007426", "cviNbr": "122333444455", "temp": false
        }
        "denyAccess"
:
    "N"
    "authStatus"
:
    "A"
}
],
"emailAddresses"
:
[
    {
        "emailAddress": "GCOSTANZA@VANDELAYINDUSTRIES.COM"
    }],
{
    subscriptions: {
        temp: false
        circStatus: "R"
        issuesRemaining: 13
        memberCat: null
        memberOrg: ""
        renewMethod: "C"
        term: 12
        termNumber: 2
        subType: ""
        startDate: "2014-01-01 00:00:00"
        expirationDate: "2014-12-01 00:00:00"
        finalExpirationDate: "2015-12-01 00:00:00"
        deliveryCode: "ME"
        rate: 0
        remainingLiability: 0
        promoCode: "MLHARA08"
        graceFlag: Y
        graceIssues: 0
        id: {
            customerNumber: "000038919045" subRef: "000022267363"
            item: {
                itemDescription: "International Living" itemNumber: "ILV"
                itemType: "CIR"
                serviceCode: " "
            }
            productOrders: {
                temp: false
                customerNumber: "000038919045" orderDate: "2013-06-23 00:00:00" orderLineNumber: null
                item: {
                    itemDescription: "Best Deal On Airfare Online" itemNumber: "120SBAIRO"
                    itemType: "PRO"
                    serviceCode: " "
                }
                orderType: "I" orderStatus: "F" applyToNumber: "" originalOrderNumber: "" allowAccess: true
                id: {
                    orderNumber: "20009517" orderSpsq: "000000" orderSeq: "00001"
                }
                accessMaintenanceOrders: [0]
            }
]';
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
        return '{
    "status": "A",
    "referenceNumber": "",
    "customerNumber": "000012345678",
    "emailAddress": "GCOSTANZA@VANDELAYINSTRUSTRIES.COM", "addressCode": "",
    "listCode": "5MIN",
    "emailId": "000001700000",
    "confirmationDate": null,
    "dateAdded": "2014-06-06",
    "demographicData": "XUU55003 0",
    "isDoubleOptIn": false,
    "listCategory": "400",
    "listDescription": "5 minute forecast", "originalSourceCode": "XUU55003",
    "profileId": "000000000000",
    "sourceCode": "XMISSING",
    "reasonCode": "11",
    "lastEnrollment": "2014-06-30"
}';
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
        return '{
        "status": "A",
"referenceNumber": "",
"customerNumber": "000012345678",
"emailAddress": "GCOSTANZA@VANDELAYINSTRUSTRIES.COM", "addressCode": "",
"listCode": "5MIN",
"emailId": "000001700000",
"confirmationDate": null,
"dateAdded": 1177387200000,
"demographicData": "XUU55003 0",
"isDoubleOptIn": false,
"listCategory": "400",
"listDescription": "5 minute forecast", "originalSourceCode": "XUU55003",
"profileId": "000000000000",
"sourceCode": "XMISSING",
"reasonCode": "11",
"dateAdded": "2014-06-06",
"lastEnrollment": "2014-06-30"
        }';
    }

    /**
     * Find the customer's lists by listcode and email address.
     *
     * 7.5 findCustomerListsByListCodeAndEmail
     *
     * @param $listcode
     * @param $email
     * @return string JSON
     *
     */
    public function findCustomerListSignupsByEmailAddressListCode($listcode,$email)
    {
        return '{
  "status": "A",
  "referenceNumber": "",
  "customerNumber": "000012345678",
  "emailAddress": "GCOSTANZA@PUBSVS.COM",
  "addressCode": "",
  "listCode": "EALERT",
  "profileId": "000000000000",
  "dateAdded": "2008-08-27",
  "emailId": "000000352858",
  "listDescription": "190SBAND Buyers",
  "demographicData": "X190J808",
  "deliveryType": "",
  "listCategory": "190",
  "sourceCode": "X190J808",
  "confirmationDate": null,
  "originalSourceCode": "X190J808",
  "isDoubleOptIn": false,
  "reasonCode": "",
  "lastEnrollment": null
}';
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
        return '{
    "emailAddress": "GCOSTANZA@PUBSVS.COM",
    "listCode": "EALERT",
    "sourceId": "XMISSING",
    "referenceNumber":"123skz",
    "firstName": "GEORGE",
    "lastName": "COSTANZA",
    "middleInitial":"H",
    "address": "1117 ST. PAUL ST",
    "address2": "2ND FLOOR",
    "city": "BALTIMORE",
    "stateProvinceCode": "MD",
    "postalCode": "21202",
    "reasonCode": "21",
    "dateAdded": "2014-06-05",
    "communicationPreference": "E"
    "emailTargetDevice": "SMS"
}';
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
        return '{
        "emailAddress": "GCOSTANZA@PUBSVS.COM",
        "listCode": "EALERT",
        "referenceNumber":"123skz"
        }';
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
        return '{
customerNumber: "000060056499"
itemNumber: "CLB"
emailAddressId: "000029742943"
dateSent: "2014-06-27 07:04:48" emailSubject: "CLB603 IFL47 (93665/111579)" sentStatus: "D"
bounceReason: ""
}';
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
        return '{
    customerNumber: "000541908411"
    owningOrganization: "400"
    activeListCodes: null
    backEndPublicationCodes: null
    frontEndPublicationCodes: null
    publicationCount: 0
    remainingLiabilityAmount: 0
    megaPublicationCodes: null
    buyerFlag: true
    firstPurchaseDate: "2013-11-21 00:00:00.0"
    lastEmailClickDate: null
    lastEmailOpenDate: null
    lifetimeValueMonth3: 49
    lifetimeValueMonth6: 49
    lifetimeValueMonth12: 49
    lifetimeValueInitialAmt: 49
    lastLoginDate: null
    ntaItem: "TEK"
    ntaChannel: "R"
    ntaVendor: ""
}';
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
        return '{
    customerNumber: "000073019625"
    owningOrganization: "400"
    listCode: "DR"
    emailID: "000042545917"
    publicationCount: 0
    firstPurchaseDate: null
    firstSignupDate: "2015-03-14 00:00:00.0"
    lastEmailClickDate: null
    lastEmailOpenDate: null
        }';
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
        return '{
    owningOrg: null
    customerNumber: "000069953332"
    emailAddress: "PUBSVS@PUBSVS.COM"
    tagName: "BUZZER"
    tagValue: "TEST 123"
}';
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
        return '{
    owningOrg: null
    customerNumber: "000069953332"
    emailAddress: "AGANDHI@PUBSVS.COM"
    tagName: "BUZZER"
    tagValue: "TEST 123"
}';
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
        return '{
    "customerNumber": "000069953332",
    "emailAddress": "AGANDHI@PUBSVS.COM",
    "tagName": "BUZZER",
    "tagValue": "TEST 123",
    "owningOrg" : "300"
}';
    }

    /******************************************************************************************************************
     * 13 Owningorg Services
     **/

}
