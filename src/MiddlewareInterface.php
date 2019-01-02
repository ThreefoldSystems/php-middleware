<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace Threefold\Middleware;

interface MiddlewareInterface
{
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
    public function findCustomerIdentifier($username, $password);

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
    public function findPostalAddressesByCustomerNumber($customerId);

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
    public function findPostalAddressesByEmailAddress($email);

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
    public function findEmailAddressesByCustomerNumber($customerId);

    /**
     * Find direct debit information associated with a email.
     *
     * x.x N/A
     *
     * @param string $email
     *
     * @return string JSON
     *
     * @mw-wp n/a
     */

    public function findDirectDebitByEmail($email);

    /**
     * Find direct debit information associated with a customer number.
     *
     * 1.9 findDirectDebitByCustomerNumber
     *
     * @param string $customerId
     * @return string JSON
     *
     * @mw-wp n/a
     */
    public function findDirectDebitByCustomerNumber($customerId);

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
    public function findCustomerNumberByContactIdOrgIdStackName($contactId, $orgId, $stackName);

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
    public function findEmailAddressByContactIdOrgIdStackName($contactId, $orgId, $stackName);

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
    public function findLowestCustomerNumberByEmailAddress($email);

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
    public function createCustomer($email, $skipDuplicateCheck = false, $communicationPreference = 'E');

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
    public function findCustomerNumberByContactIdOrgId($contactId, $orgId);

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
    public function findAccountByCustomerNumber($customerId);

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
    public function findAccountByEmailAddress($email);

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
    public function addAccount($customerId, $username, $password);

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
    public function updatePassword($customerId, $username, $existingPassword, $newPassword);

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
    public function findActiveSubscriptionsByCustomerNumber($customerNumber);

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
    public function findSubscriptionsByCustomerNumber($customerNumber);

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
    public function findSubscriptionPostalAddressByPurchaseOrderNumber($purchaseOrderNumber);

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
    public function findLoginAggregateData($username, $password);

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
    public function findCustomerListSignupsByCustomerNumber($customerId);

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
    public function findCustomerListSignupsByEmailAddress($email);

    /**
     * Find the customer’s list signups using their email address and listCode
     *
     * 7.4 findCustomerListSignupsByEmailAddressListCode
     *
     * @param $listcode
     * @param $email
     * @return string JSON
     *
     */
    public function findCustomerListSignupsByEmailAddressListCode($listcode,$email);

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
    public function addCustomerSignup($email, $listCode, $sourceCode, $attributes = null);

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
    public function unsubscribeCustomerSignup($listCode, $email, $referenceNumber = null);

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
    public function findEmailFulfillmentHistoryByCustomerNumber($customerNumber);

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
    public function findAffiliateFactsByCustomerNumber($customerNumber);

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
    public function findListFactsByCustomerNumber($customerNumber);

    /**
     * Find affiliate tags by customer number
     *
     * 12.3 findAffiliateTagsByCustomerNumber
     *
     * @param string $customerNumber
     * @return string|false JSON

     * @mw-wp get_affiliate_tags_by_id
     */
    public function findAffiliateTagsByCustomerNumber($customerNumber);

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
    public function findAffiliateTagsByEmailAddressOwningOrg($email, $owningOrg);

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
    public function createAffiliateTags($customerId, $email, $tagName, $tagValue, $owningOrg = null);

    /******************************************************************************************************************
     * 13 Owningorg Services
     **/
}
