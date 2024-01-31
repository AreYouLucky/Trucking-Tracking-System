<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Delivery;
use Psy\VersionUpdater\Downloader\CurlDownloader;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function smsCustomer(){
        curl -L -g 'https://3g129j.api.infobip.com/sms/2/text/advanced' \
        -H 'Authorization: App ead6f7c2e70a12400a95d9034492c0b1-48ad373c-6574-48e6-a7fd-cf63c5c3e1f0' \
        -H 'Content-Type: application/json' \
        -H 'Accept: application/json' \
        -d '{"messages":[{"destinations":[{"to":"639683013603"}],"from":"ServiceSMS","text":"Hello,\n\nThis is a test message from Infobip. Have a nice day!"}]}'

    }
}
