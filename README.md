1. Create the following variables within `.env`:

```
BIG_COMMERCE_STORE_HASH=
BIG_COMMERCE_STORE_URL=
BIG_COMMERCE_API_PATH=
BIG_COMMERCE_CLIENT_ID=
BIG_COMMERCE_CLIENT_SECRET=
BIG_COMMERCE_AUTH_CLIENT=
BIG_COMMERCE_AUTH_TOKEN=
```

2. I've wired up the test we used to generate logs we sent you last week.

`tests/Feature/ExampleTest.php` 

The test `can_show_detail_by_checkout_id` will produce the Guzzle Header logs, such as:

```
*   Trying 35.227.219.245:443...
* TCP_NODELAY set
* Connected to api.bigcommerce.com (35.227.219.245) port 443 (#0)
* ALPN, offering http/1.1
* successfully set certificate verify locations:
*   CAfile: /etc/ssl/certs/ca-certificates.crt
  CApath: /etc/ssl/certs
* SSL connection using TLSv1.2 / ECDHE-RSA-CHACHA20-POLY1305
* ALPN, server accepted to use http/1.1
* Server certificate:
*  subject: C=AU; ST=New South Wales; L=Ultimo; O=Bigcommerce Pty Ltd; CN=*.bigcommerce.com
*  start date: Jan 17 00:00:00 2019 GMT
*  expire date: Mar 19 12:00:00 2021 GMT
*  subjectAltName: host "api.bigcommerce.com" matched cert's "*.bigcommerce.com"
*  issuer: C=US; O=DigiCert Inc; OU=www.digicert.com; CN=DigiCert SHA2 High Assurance Server CA
*  SSL certificate verify ok.
> GET /stores/5pecd49xfo/v2/orders HTTP/1.1
Host: api.bigcommerce.com
Content-Type: application/json
Accept: application/json
X-Auth-Token: ****
X-Auth-Client: ****
User-Agent: GuzzleHttp/7
```

The request/response logs are saved to `storage/logs/guzzle.log`


Hope this helps :)
