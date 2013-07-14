<?

class ApiAuth extends Eloquent {
    public static function AuthorizeRequest($domain, $apiKey) {
        $apiKeyForDomain = DB::table('api_keys')->select('*')->where('domain', '=', $domain)->take(1)->get();
        if ($apiKey === $apiKeyForDomain[0]->key)
            return true;
        else
            return false;
    }
}