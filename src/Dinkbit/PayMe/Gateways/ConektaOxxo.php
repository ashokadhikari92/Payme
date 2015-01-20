<?php

namespace Dinkbit\PayMe\Gateways;

use Dinkbit\PayMe\Status;
use Dinkbit\PayMe\Transaction;

class ConektaOxxo extends Conekta
{
    protected $displayName = 'conektaoxxo';

    /**
     * {@inheritdoc}
     */
    public function charge($amount, $payment, $options = [])
    {
        $params = [];

        $params['cash']['type'] = $payment;

        $params = $this->addExpiry($params, $options);
        $params = $this->addOrder($params, $amount, $options);

        return $this->commit('post', $this->buildUrlFromString('charges'), $params);
    }

    public function addExpiry($params, $options)
    {
        $params['cash']['expires_at'] = $this->array_get($options, 'expires', date("Y-m-d", time() + 172800));

        return $params;
    }

    /**
     * {@inheritdoc}
     */
    public function mapResponseToTransaction($success, $response)
    {
        return (new Transaction())->setRaw($response)->map([
            'isRedirect'      => false,
            'success'         => $success,
            'message'         => $success ? $response['payment_method']['barcode_url'] : $response['message_to_purchaser'],
            'test'            => array_key_exists('livemode', $response) ? $response["livemode"] : false,
            'authorization'   => $success ? $response['id'] : $response['type'],
            'status'          => $success ? $this->getStatus($this->array_get($response, 'status', 'paid')) : new Status('failed'),
            'reference'       => $success ? $response['payment_method']['barcode_url'] : false,
            'code'            => $success ? $response['payment_method']['barcode'] : $response['code'],
        ]);
    }
}
