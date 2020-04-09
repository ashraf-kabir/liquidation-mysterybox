<?php

use Mailgun\Mailgun;

defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Mail_service
{
    /**
     * Mail Adapter.
     *
     * @var mixed
     */
    public $_adapter = null;

    /**
     * Adapter selected.
     *
     * @var string
     */
    public $_type = '';

    /**
     * Domain send from.
     *
     * @var string
     */
    public $_domain = '';

    /**
     * Platform name.
     *
     * @var string
     */
    public $_platform_name = '';

    /**
     * CI.
     *
     * @var mixed
     */
    public $_ci = null;

    /**
     * Set mail service to correct way to send emails.
     *
     * @param string $type
     *
     * @throws Exception
     */
    public function set_adapter($type)
    {
        $this->_type = $type;
        $this->_ci = &get_instance();
        $this->_platform_name = $this->_ci->config->item('platform_name');

        switch ($type)
        {
            case 'mailgun':
                $this->_adapter = Mailgun::create($this->_ci->config->item('mailgun_key'));
                $this->_domain = $this->_ci->config->item('mail_domain');
                break;

            case 'test':
                break;
            case 'smtp':
            default:
                $this->_ci->load->library('email');
                $settings = $this->_ci->config->item('email_smtp');

                if (empty($settings))
                {
                    throw new Exception('Email not setup');
                }

                $this->_adapter = $this->_ci->email->initialize($settings);
                break;
        }
    }

    /**
     * Set domain to send from (mailgun exclusive).
     *
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->_domain = $domain;
    }

    /**
     * Send email.
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $html
     */
    public function send($from, $to, $subject, $html)
    {
        switch ($this->_type) {
            case 'mailgun':
                return $this->_adapter->messages()->send($this->_domain, [
                    'from' => $from,
                    'to' => $to,
                    'subject' => $subject,
                    'html' => $html,
                ]);
                break;

            case 'test':
                return [
                    'from' => $from,
                    'to' => $to,
                    'subject' => $subject,
                    'html' => $html
                ];
                break;

            case 'smtp':

            default:
                $this->_ci->load->library('encryption');
                $this->_adapter->to($to);
                $this->_adapter->from($from);
                $this->_adapter->subject($subject);
                $this->_adapter->message($html);
                $result = $this->_adapter->send();

                if (!$result)
                {
                    error_log($this->_adapter->print_debugger());
                }

                return $result;

                break;
        }
    }
}
