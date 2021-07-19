<?php

namespace Omnipay\PerfectMoney\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class RefundRequest extends AbstractRequest
{
    protected $endpoint = 'https://perfectmoney.is/acct/confirm.asp?';

    /**
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('accountId', 'payeeAccount', 'amount', 'paymentId', 'description');

        $data['AccountID'] = $this->getAccountId();
        $data['PassPhrase'] = $this->getPassword();
        $data['Payer_Account'] = $this->getAccount();
        $data['Payee_Account'] = $this->getPayeeAccount();
        $data['Amount'] = $this->getAmount();
        $data['PAYMENT_ID'] = $this->getPaymentId();
        $data['Memo'] = $this->getDescription();
        $data['PAY_IN'] = '1';

        return $data;
    }

    public function sendData($data)
    {
        $query = http_build_query($data);
        $httpResponse = $this->httpClient->request('GET', $this->endpoint . $query, []);
        return new RefundResponse($this, $httpResponse->getBody()->getContents());
    }
}
