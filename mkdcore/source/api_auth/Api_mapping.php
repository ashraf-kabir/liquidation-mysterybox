<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Api_mapping
{
    public $HTTP_CONTINUE = 100;

    public $HTTP_SWITCHING_PROTOCOLS = 101;

    public $HTTP_PROCESSING = 102; // RFC2518
    public $HTTP_OK = 200; // Success

    /**
     * The server successfully created a new resource
     */
    public $HTTP_CREATED = 201;

    public $HTTP_ACCEPTED = 202;

    public $HTTP_NON_AUTHORITATIVE_INFORMATION = 203;

    /**
     * The server successfully processed the request, though no content is returned
     */
    public $HTTP_NO_CONTENT = 204;

    public $HTTP_RESET_CONTENT = 205;

    public $HTTP_PARTIAL_CONTENT = 206;

    public $HTTP_MULTI_STATUS = 207;
    // RFC4918
    public $HTTP_ALREADY_REPORTED = 208;
    // RFC5842
    public $HTTP_IM_USED = 226;
    // RFC3229
    // Redirection
    public $HTTP_MULTIPLE_CHOICES = 300;

    public $HTTP_MOVED_PERMANENTLY = 301;

    public $HTTP_FOUND = 302;

    public $HTTP_SEE_OTHER = 303;

    /**
     * The resource has not been modified since the last request
     */
    public $HTTP_NOT_MODIFIED = 304;

    public $HTTP_USE_PROXY = 305;

    public $HTTP_RESERVED = 306;

    public $HTTP_TEMPORARY_REDIRECT = 307;

    public $HTTP_PERMANENTLY_REDIRECT = 308;
    // RFC7238
    // Client Error
    /**
     * The request cannot be fulfilled due to multiple errors
     */
    public $HTTP_BAD_REQUEST = 400;

    /**
     * The user is unauthorized to access the requested resource
     */
    public $HTTP_UNAUTHORIZED = 401;

    public $HTTP_PAYMENT_REQUIRED = 402;

    /**
     * The requested resource is unavailable at this present time
     */
    public $HTTP_FORBIDDEN = 403;

    /**
     * The requested resource could not be found
     *
     * Note: This is sometimes used to mask if there was an UNAUTHORIZED (401) or
     * FORBIDDEN (403) error, for security reasons
     */
    public $HTTP_NOT_FOUND = 404;

    /**
     * The request method is not supported by the following resource
     */
    public $HTTP_METHOD_NOT_ALLOWED = 405;

    /**
     * The request was not acceptable
     */
    public $HTTP_NOT_ACCEPTABLE = 406;

    public $HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;

    public $HTTP_REQUEST_TIMEOUT = 408;

    /**
     * The request could not be completed due to a conflict with the current state
     * of the resource
     */
    public $HTTP_CONFLICT = 409;

    public $HTTP_GONE = 410;

    public $HTTP_LENGTH_REQUIRED = 411;

    public $HTTP_PRECONDITION_FAILED = 412;

    public $HTTP_REQUEST_ENTITY_TOO_LARGE = 413;

    public $HTTP_REQUEST_URI_TOO_LONG = 414;

    public $HTTP_UNSUPPORTED_MEDIA_TYPE = 415;

    public $HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    public $HTTP_EXPECTATION_FAILED = 417;

    public $HTTP_I_AM_A_TEAPOT = 418;
    // RFC2324
    public $HTTP_UNPROCESSABLE_ENTITY = 422;
    // RFC4918
    public $HTTP_LOCKED = 423;
    // RFC4918
    public $HTTP_FAILED_DEPENDENCY = 424;
    // RFC4918
    public $HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425;
    // RFC2817
    public $HTTP_UPGRADE_REQUIRED = 426;
    // RFC2817
    public $HTTP_PRECONDITION_REQUIRED = 428;
    // RFC6585
    public $HTTP_TOO_MANY_REQUESTS = 429;
    // RFC6585
    public $HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    // RFC6585
    // Server Error
    /**
     * The server encountered an unexpected error
     *
     * Note: This is a generic error message when no specific message
     * is suitable
     */
    public $HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * The server does not recognise the request method
     */
    public $HTTP_NOT_IMPLEMENTED = 501;

    public $HTTP_BAD_GATEWAY = 502;

    public $HTTP_SERVICE_UNAVAILABLE = 503;

    public $HTTP_GATEWAY_TIMEOUT = 504;

    public $HTTP_VERSION_NOT_SUPPORTED = 505;

    public $HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;
 // RFC2295
    public $HTTP_INSUFFICIENT_STORAGE = 507;
 // RFC4918
    public $HTTP_LOOP_DETECTED = 508;
 // RFC5842
    public $HTTP_NOT_EXTENDED = 510;
 // RFC2774
    public $HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;
}