<?php  namespace Dinkbit\Payme\Gateways;

use Dinkbit\Payme\Gateways\Paypal\PaypalCommon;

class PaypalExpress extends PaypalCommon {

	protected $liveEndpoint = 'https://www.paypal.com/webscr';
	protected $testEndpoint = 'https://www.sandbox.paypal.com/webscr';

	/**
	 * @param $config
	 */
	public function __construct($config)
	{
		$this->requires($config, ['login', 'password', 'signature']);
	}

	/**
	 * Map the raw transaction array to a Payme Transaction instance.
	 *
	 * @param  array $transaction
	 * @return \Dinkbit\Payme\Transaction
	 */
	protected function mapResponseToTransaction(array $transaction)
	{
		return (new Transaction)->setRaw($transaction)->map([
			'isSuccessful' => true,
			'isRedirect' => true,
			'code' => isset($transaction['code_auth']) ? $transaction['code_auth'] : null,
			'message' => null,
		]);
	}

	public function getRedirectUrl()
	{
		$query = array(
			'cmd' => '_express-checkout',
			'useraction' => 'commit',
			'token' => '123',
		);

		return $this->testCheckoutEndpoint.'?'.http_build_query($query, '', '&');
	}

	/**
	 * @return mixed
	 */
	protected function getRequestUrl()
	{
		return $this->liveEndpoint;
	}
}