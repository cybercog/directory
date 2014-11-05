<?php

namespace app\modules\directory\helpers;

class ajaxJSONResponseHelper {
    const standartAccessDenidedMessage = 'access denided';
    const messageField = 'message';
    const targetField = 'target';
    const additionalField = 'additional';
    const resultField = 'result';
    const failResult = 'fail';
    const okResult = 'ok';

    public static function createResponse($ok = true, $message = null, $additionalParams = null) {
        $response = [
            ajaxJSONResponseHelper::resultField=>$ok ? ajaxJSONResponseHelper::okResult : ajaxJSONResponseHelper::failResult
        ];
        
        if(isset($message)) {
            $response = array_merge($response, [ajaxJSONResponseHelper::messageField=>$message]);
        }

        if(isset($additionalParams)) {
            $response = array_merge($response, [ajaxJSONResponseHelper::additionalField=>$additionalParams]);
        }
        
        return $response;
    }
}
