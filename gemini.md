# iNET API Documentation Summary

This document summarizes the iNET API for managing reseller services. All API calls use the `POST` method.

## Customer Management

- **Base Path:** `/api/rms/v1/customer/`

### Endpoints

- **/search:** Searches for customers of a reseller.
- **/create:** Creates a new customer.
- **/updateinfo:** Updates existing customer information.
- **/forgotpassword:** Gets a password reset token for a customer.
- **/changepassword:** Updates a customer's login password.
- **/get:** Retrieves customer information by ID.
- **/getbyemail:** Retrieves customer information by email.
- **/suspend:** Suspends a customer's account.
- **/active:** Activates a suspended customer's account.
- **/geturlsignin:** Gets a sign-in link for a customer.

## Domain Management

- **Base Path:** `/api/rms/v1/domain/`

### Endpoints

- **/search:** Search for domains.
- **/checkavailable:** Check domain availability.
- **/create:** Register a new domain.
- **/renew:** Renew an existing domain.
- **/updatedns:** Update domain nameservers.
- **/updaterecord:** Update domain DNS records.
- **/getrecord:** Get domain DNS records.
- **/detail:** Get domain details.
- **/punycode:** Convert domain to punycode.
- **/updatecontact:** Update domain contact information.
- **/uploadfile:** Upload supporting documents.

## Email Management

- **Base Path:** `/api/rms/v1/email/`

### Endpoints

- **/search:** Searches for email packages.
- **/gettotalquota:** Retrieves detailed information about an email package.
- **/checkdomainavailable:** Checks if an email domain is available for registration.
- **/create:** Registers a new email package.
- **/createtrial:** Registers a trial email package.
- **/renew:** Renews an existing email package.
- **/changeplan:** Upgrades an email package to a new plan.
- **/gendkim:** Generates DKIM records for an email package.
- **/getrecordverify:** Retrieves necessary DNS record information.
- **/syncemailaccount:** Synchronizes email accounts on the server.
- **/createdistribution:** Creates a new email distribution group.
- **/updatedistribution:** Modifies an existing email distribution group.
- **/deletedistribution:** Deletes an email distribution group.

- **Base Path:** `/api/rms/v1/emailaccount/`

### Endpoints

- **/create:** Creates a new email account.
- **/update:** Modifies an existing email account.
- **/delete:** Deletes an email account.
- **/createusersession:** Generates a direct login link for an email account.

- **Base Path:** `/api/v1/mailproxy/`

### Endpoints

- **/createaclhttp:** Installs an SSL certificate for an email package.
- **/getacl:** Retrieves ACL information for an SSL certificate.
- **/delcert:** Removes an installed SSL certificate.

- **Base Path:** `/api/public/nslookup/v1/nslookup/`

### Endpoints

- **/lookup:** Performs an NSLOOKUP for a given domain and record type.

- **Base Path:** `/api/rms/v1/plan/`

### Endpoints

- **/list:** Lists available service plans.

## Hosting Management

- **Base Path:** `/api/rms/v1/hosting/`

### Endpoints

- **/search:** Search for hosting packages.
- **/detail:** Get hosting package details.
- **/checkdomainavailable:** Check domain availability for hosting.
- **/create:** Create a new hosting package.
- **/renew:** Renew an existing hosting package.
- **/changeplan:** Upgrade/downgrade a hosting package.
- **/changedomain:** Change the domain of a hosting package.
- **/getsigninurl:** Get a sign-in link for a hosting package.
- **/changepassword:** Change the password for a hosting package.

- **Base Path:** `/api/rms/v1/hostingdb/`

### Endpoints

- **/list:** List databases.
- **/create:** Create a new database.
- **/delete:** Delete a database.
- **/listuser:** List database users.
- **/createuser:** Create a new database user.
- **/deleteuser:** Delete a database user.
- **/setprivileges:** Set privileges for a database user.
- **/getuserprivileges:** View database user privileges.

## VPS Management

- **Base Path:** `/api/rms/v1/vps/`

### Endpoints

- **/list:** List VPS instances.
- **/create:** Create a new VPS instance.
- **/renew:** Renew an existing VPS instance.
- **/changeplan:** Upgrade/downgrade a VPS instance.
- **/detail:** Get VPS instance details.
- **/start:** Start a VPS instance.
- **/stop:** Stop a VPS instance.
- **/restart:** Restart a VPS instance.
- **/reinstall:** Reinstall the OS on a VPS instance.
- **/resetpassword:** Reset the password for a VPS instance.
- **/getconsole:** Get a console link for a VPS instance.

## Category Management

- **Base Path:** `/api/rms/v1/category/`

### Endpoints

- **/provincelist:** List of provinces/cities.
- **/countrylist:** List of countries.

- **Base Path:** `/api/rms/v1/suffix/`

### Endpoints

- **/list:** List of domain suffixes.