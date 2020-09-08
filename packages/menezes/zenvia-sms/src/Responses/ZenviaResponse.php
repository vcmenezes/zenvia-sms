<?php

namespace Menezes\ZenviaSms\Responses;

use Illuminate\Support\Facades\Log;

class ZenviaResponse
{
    public const OK = "00";
    public const SCHEDULED = "01";
    public const SENT = "02";
    public const DELIVERED = "03";

    public const DETAIL_CODE = [
        "000" => "Message Sent",
        "002" => "Message successfully canceled",
        "010" => "Empty message content",
        "011" => "Message body invalid",
        "012" => "Message content overflow",
        "013" => "Incorrect or incomplete ‘to’ mobile number",
        "014" => "Empty ‘to’ mobile number",
        "015" => "Scheduling date invalid or incorrect",
        "016" => "ID overflow",
        "017" => "Parameter ‘url’ is invalid or incorrect",
        "018" => "Field ‘from’ invalid",
        "021" => "‘id’ fieldismandatory",
        "080" => "Message with same ID already sent",
        "100" => "Message Queued",
        "110" => "Message sent to operator",
        "111" => "Message confirmation unavailable",
        "120" => "Message received by mobile",
        "130" => "Message blocked",
        "131" => "Message blocked by predictive cleansing",
        "132" => "Message already canceled",
        "133" => "Message content in analysis",
        "134" => "Message blocked by forbidden content",
        "135" => "Aggregate is Invalid or Inactive",
        "136" => "Message expired",
        "140" => "Mobile number not covered",
        "141" => "International sending not allowed",
        "145" => "Inactive mobile number",
        "150" => "Message expired in operator",
        "160" => "Operator network error",
        "161" => "Message rejected by operator",
        "162" => "Message cancelled or blocked by operator",
        "170" => "Bad message",
        "171" => "Bad number",
        "172" => "Missing parameter",
        "180" => "Message ID notfound",
        "190" => "Unknown error",
        "200" => "Messages Sent",
        "210" => "Messages scheduled but Account Limit Reached",
        "240" => "File empty or not sent",
        "241" => "File too large",
        "242" => "File readerror",
        "300" => "Received messages found",
        "301" => "No received messages found",
        "400" => "Entity saved",
        "900" => "Authentication error",
        "901" => "Account type not support this operation.",
        "990" => "Account Limit Reached – Please contact support",
        "998" => "Wrong operation requested",
        "999" => "Unknown Error",
    ];

    private object $response;
    private $statusCode;
    private $statusDescription;
    private $detailCode;
    private $detailDescription;

    public function __construct($response)
    {
        if (is_array($response)) {
            $response = (object)$response;
        }

        if ($response->sendSmsResponse ?? false) {
            $response = (object)$response->sendSmsResponse;
        }

        $this->response = $response;
        Log::channel('zenvia')->info(\GuzzleHttp\json_encode($response));
        $this->statusCode = $response->statusCode;
        $this->statusDescription = $response->statusDescription;
        $this->detailCode = $response->detailCode;
        $this->detailDescription = $response->detailDescription;
    }

    public function success(): bool
    {
        return in_array(
                $this->statusCode,
                [self::OK, self::SCHEDULED, self::SENT, self::DELIVERED],
                true
            ) !== false;
    }

    public function failed(): bool
    {
        return !$this->success();
    }

    public function getDetailCode(): string
    {
        $code = (int) $this->statusCode;

        if($code < 100){
            if($code <= 0){
                $code = '000';
            }else{
                $code = '0'. $code;
            }
        }
        return self::DETAIL_CODE[(string)$code];
    }
}
