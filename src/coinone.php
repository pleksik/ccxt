<?php

namespace ccxt;

class coinone extends Exchange {

    public function describe () {
        return array_replace_recursive (parent::describe (), array (
            'id' => 'coinone',
            'name' => 'CoinOne',
            'countries' => 'KR', // Korea
            'rateLimit' => 90,
            'version' => 'v2',
            'has' => array (
                'CORS' => false,
                'createMarketOrder' => false,
            ),
            'urls' => array (
                'logo' => 'https://user-images.githubusercontent.com/1294454/38003300-adc12fba-323f-11e8-8525-725f53c4a659.jpg',
                'api' => 'https://api.coinone.co.kr',
                'www' => 'https://coinone.co.kr',
                'doc' => 'https://doc.coinone.co.kr',
            ),
            'requiredCredentials' => array (
                'apiKey' => true,
                'secret' => true,
            ),
            'api' => array (
                'public' => array (
                    'get' => array (
                        'orderbook/',
                        'trades/',
                        'ticker/',
                    ),
                ),
                'private' => array (
                    'post' => array (
                        'account/btc_deposit_address/',
                        'account/balance/',
                        'account/daily_balance/',
                        'account/user_info/',
                        'account/virtual_account/',
                        'order/cancel_all/',
                        'order/cancel/',
                        'order/limit_buy/',
                        'order/limit_sell/',
                        'order/complete_orders/',
                        'order/limit_orders/',
                        'order/order_info/',
                        'transaction/auth_number/',
                        'transaction/history/',
                        'transaction/krw/history/',
                        'transaction/btc/',
                        'transaction/coin/',
                    ),
                ),
            ),
            'markets' => array (
                'BCH/KRW' => array ( 'id' => 'bch', 'symbol' => 'BCH/KRW', 'base' => 'BCH', 'quote' => 'KRW' ),
                'BTC/KRW' => array ( 'id' => 'btc', 'symbol' => 'BTC/KRW', 'base' => 'BTC', 'quote' => 'KRW' ),
                'BTG/KRW' => array ( 'id' => 'btg', 'symbol' => 'BTG/KRW', 'base' => 'BTG', 'quote' => 'KRW' ),
                'ETC/KRW' => array ( 'id' => 'etc', 'symbol' => 'ETC/KRW', 'base' => 'ETC', 'quote' => 'KRW' ),
                'ETH/KRW' => array ( 'id' => 'eth', 'symbol' => 'ETH/KRW', 'base' => 'ETH', 'quote' => 'KRW' ),
                'IOT/KRW' => array ( 'id' => 'iota', 'symbol' => 'IOT/KRW', 'base' => 'IOT', 'quote' => 'KRW' ),
                'LTC/KRW' => array ( 'id' => 'ltc', 'symbol' => 'LTC/KRW', 'base' => 'LTC', 'quote' => 'KRW' ),
                'QTUM/KRW' => array ( 'id' => 'qtum', 'symbol' => 'QTUM/KRW', 'base' => 'QTUM', 'quote' => 'KRW' ),
                'XRP/KRW' => array ( 'id' => 'xrp', 'symbol' => 'XRP/KRW', 'base' => 'XRP', 'quote' => 'KRW' ),
            ),
            'fees' => array (
                'trading' => array (
                    'tierBased' => true,
                    'percentage' => true,
                    'taker' => 0.001,
                    'maker' => 0.001,
                    'tiers' => array (
                        'taker' => [
                            [0, 0.001],
                            [100000000, 0.0009],
                            [1000000000, 0.0008],
                            [5000000000, 0.0007],
                            [10000000000, 0.0006],
                            [20000000000, 0.0005],
                            [30000000000, 0.0004],
                            [40000000000, 0.0003],
                            [50000000000, 0.0002],
                        ],
                        'maker' => [
                            [0, 0.001],
                            [100000000, 0.0008],
                            [1000000000, 0.0006],
                            [5000000000, 0.0004],
                            [10000000000, 0.0002],
                            [20000000000, 0],
                            [30000000000, 0],
                            [40000000000, 0],
                            [50000000000, 0],
                        ],
                    ),
                ),
            ),
        ));
    }

    public function fetch_balance ($params = array ()) {
        $response = $this->privateGetV2AccountBalance ();
        $result = array ( 'info' => $response );
        $ids = is_array ($this->markets) ? array_keys ($this->markets) : array ();
        for ($i = 0; $i < count ($ids); $i++) {
            $market = $ids[$i];
            $id = $market['id'];
            $symbol = $market['symbol'];
            if (is_array ($response) && array_key_exists ($id, $response)) {
                $balance = $response[$id];
                $account = array (
                    'free' => floatval ($balance['avail']),
                    'used' => floatval ($balance['balance']) - floatval ($balance['avail']),
                    'total' => floatval ($balance['balance']),
                );
                $result[$symbol] = $account;
            }
        }
        return $this->parse_balance($result);
    }

    public function fetch_order_book ($symbol, $params = array ()) {
        $market = $this->market ($symbol);
        $response = $this->publicGetOrderbook (array_merge (array (
            'currency' => $market['id'],
            'format' => 'json',
        ), $params));
        return $this->parse_order_book($response, null, 'bid', 'ask', 'price', 'qty');
    }

    public function fetch_ticker ($symbol, $params = array ()) {
        $market = $this->market ($symbol);
        $response = $this->publicGetTicker (array_merge (array (
            'currency' => $market['id'],
            'format' => 'json',
        ), $params));
        return $this->parse_ticker($response, $market);
    }

    public function parse_ticker ($ticker, $market = null) {
        $timestamp = $this->milliseconds ();
        $last = $this->safe_float($ticker, 'last');
        $previousClose = $this->safe_float($ticker, 'yesterday_last');
        $change = null;
        if ($last !== null && $previousClose !== null)
            $change = $previousClose - $last;
        $symbol = ($market !== null) ? $market['symbol'] : null;
        return array (
            'symbol' => $symbol,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'high' => $this->safe_float($ticker, 'high'),
            'low' => $this->safe_float($ticker, 'low'),
            'bid' => null,
            'bidVolume' => null,
            'ask' => null,
            'askVolume' => null,
            'vwap' => null,
            'open' => $this->safe_float($ticker, 'first'),
            'close' => $last,
            'last' => $last,
            'previousClose' => $previousClose,
            'change' => $change,
            'percentage' => null,
            'average' => null,
            'baseVolume' => $this->safe_float($ticker, 'volume'),
            'quoteVolume' => null,
            'info' => $ticker,
        );
    }

    public function parse_trade ($trade, $market = null) {
        $timestamp = intval ($trade['timestamp']) * 1000;
        $symbol = ($market !== null) ? $market['symbol'] : null;
        return array (
            'id' => null,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'order' => null,
            'symbol' => $symbol,
            'type' => null,
            'side' => null,
            'price' => $this->safe_float($trade, 'price'),
            'amount' => $this->safe_float($trade, 'qty'),
            'fee' => null,
            'info' => $trade,
        );
    }

    public function fetch_trades ($symbol, $since = null, $limit = null, $params = array ()) {
        $market = $this->market ($symbol);
        $response = $this->publicGetTrades (array_merge (array (
            'currency' => $market['id'],
            'period' => 'hour',
            'format' => 'json',
        ), $params));
        return $this->parse_trades($response['completeOrders'], $market, $since, $limit);
    }

    public function create_order ($symbol, $type, $side, $amount, $price = null, $params = array ()) {
        if ($type !== 'limit')
            throw new ExchangeError ($this->id . ' allows limit orders only');
        $this->load_markets();
        $order = array (
            'price' => $price,
            'currency' => $this->market_id($symbol),
            'qty' => $amount,
        );
        $method = 'privatePostOrder' . $this->capitalize ($type) . $this->capitalize ($side);
        $response = $this->$method (array_merge ($order, $params));
        // todo => return the full $order structure
        // return $this->parse_order($response, market);
        $orderId = $this->safe_string($response, 'orderId');
        return array (
            'info' => $response,
            'id' => $orderId,
        );
    }

    public function cancel_order ($id, $symbol = null, $params = array ()) {
        return $this->privatePostOrderCancel (array ( 'orderID' => $id ));
    }

    public function sign ($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        $request = $this->implode_params($path, $params);
        $query = $this->omit ($params, $this->extract_params($path));
        $url = $this->urls['api'] . '/' . $request;
        $headers = array ();
        if ($api === 'public') {
            if ($query) {
                $url .= '?' . $this->urlencode ($query);
            }
        } else {
            $this->check_required_credentials();
            $nonce = (string) $this->nonce ();
            $payload = base64_encode ($this->json (array ( 'access_token' => $this->apiKey, 'nonce' => $nonce )));
            $body = $payload;
            $signature = $this->hmac ($payload, $this->encode (strtoupper ($this->secret)), 'sha512', 'hex');
            $headers = array (
                'content-type' => 'application/json',
                'X-COINONE-PAYLOAD' => $payload,
                'X-COINONE-SIGNATURE' => $signature,
            );
        }
        return array ( 'url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers );
    }

    public function handle_errors ($code, $reason, $url, $method, $headers, $body) {
        if (($body[0] === '{') || ($body[0] === '[')) {
            $response = json_decode ($body, $as_associative_array = true);
            if (is_array ($response) && array_key_exists ('result', $response)) {
                $result = $response['result'];
                if ($result !== 'success')
                    throw new ExchangeError ($this->id . ' ' . $body);
            } else {
                throw new ExchangeError ($this->id . ' ' . $body);
            }
        }
    }
}
