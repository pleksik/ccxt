<?php

namespace ccxt;

class bitstamp extends Exchange {

    public function describe () {
        return array_replace_recursive (parent::describe (), array (
            'id' => 'bitstamp',
            'name' => 'Bitstamp',
            'countries' => 'GB',
            'rateLimit' => 1000,
            'version' => 'v2',
            'has' => array (
                'CORS' => true,
                'fetchOrder' => true,
                'fetchMyTrades' => true,
                'withdraw' => true,
            ),
            'urls' => array (
                'logo' => 'https://user-images.githubusercontent.com/1294454/27786377-8c8ab57e-5fe9-11e7-8ea4-2b05b6bcceec.jpg',
                'api' => 'https://www.bitstamp.net/api',
                'www' => 'https://www.bitstamp.net',
                'doc' => 'https://www.bitstamp.net/api',
            ),
            'requiredCredentials' => array (
                'apiKey' => true,
                'secret' => true,
                'uid' => true,
            ),
            'api' => array (
                'public' => array (
                    'get' => array (
                        'order_book/{pair}/',
                        'ticker_hour/{pair}/',
                        'ticker/{pair}/',
                        'transactions/{pair}/',
                        'trading-pairs-info/',
                    ),
                ),
                'private' => array (
                    'post' => array (
                        'balance/',
                        'balance/{pair}/',
                        'bch_withdrawal/',
                        'bch_address/',
                        'user_transactions/',
                        'user_transactions/{pair}/',
                        'open_orders/all/',
                        'open_orders/{pair}/',
                        'order_status/',
                        'cancel_order/',
                        'buy/{pair}/',
                        'buy/market/{pair}/',
                        'sell/{pair}/',
                        'sell/market/{pair}/',
                        'ltc_withdrawal/',
                        'ltc_address/',
                        'eth_withdrawal/',
                        'eth_address/',
                        'xrp_withdrawal/',
                        'xrp_address/',
                        'transfer-to-main/',
                        'transfer-from-main/',
                        'withdrawal/open/',
                        'withdrawal/status/',
                        'withdrawal/cancel/',
                        'liquidation_address/new/',
                        'liquidation_address/info/',
                    ),
                ),
                'v1' => array (
                    'post' => array (
                        'bitcoin_deposit_address/',
                        'unconfirmed_btc/',
                        'bitcoin_withdrawal/',
                        'ripple_withdrawal/',
                        'ripple_address/',
                    ),
                ),
            ),
            'fees' => array (
                'trading' => array (
                    'tierBased' => true,
                    'percentage' => true,
                    'taker' => 0.25 / 100,
                    'maker' => 0.25 / 100,
                    'tiers' => array (
                        'taker' => [
                            [0, 0.25 / 100],
                            [20000, 0.24 / 100],
                            [100000, 0.22 / 100],
                            [400000, 0.20 / 100],
                            [600000, 0.15 / 100],
                            [1000000, 0.14 / 100],
                            [2000000, 0.13 / 100],
                            [4000000, 0.12 / 100],
                            [20000000, 0.11 / 100],
                            [20000001, 0.10 / 100],
                        ],
                        'maker' => [
                            [0, 0.25 / 100],
                            [20000, 0.24 / 100],
                            [100000, 0.22 / 100],
                            [400000, 0.20 / 100],
                            [600000, 0.15 / 100],
                            [1000000, 0.14 / 100],
                            [2000000, 0.13 / 100],
                            [4000000, 0.12 / 100],
                            [20000000, 0.11 / 100],
                            [20000001, 0.10 / 100],
                        ],
                    ),
                ),
                'funding' => array (
                    'tierBased' => false,
                    'percentage' => false,
                    'withdraw' => array (
                        'BTC' => 0,
                        'BCH' => 0,
                        'LTC' => 0,
                        'ETH' => 0,
                        'XRP' => 0,
                        'USD' => 25,
                        'EUR' => 0.90,
                    ),
                    'deposit' => array (
                        'BTC' => 0,
                        'BCH' => 0,
                        'LTC' => 0,
                        'ETH' => 0,
                        'XRP' => 0,
                        'USD' => 25,
                        'EUR' => 0,
                    ),
                ),
            ),
        ));
    }

    public function fetch_markets () {
        $markets = $this->publicGetTradingPairsInfo ();
        $result = array ();
        for ($i = 0; $i < count ($markets); $i++) {
            $market = $markets[$i];
            $symbol = $market['name'];
            list ($base, $quote) = explode ('/', $symbol);
            $baseId = strtolower ($base);
            $quoteId = strtolower ($quote);
            $symbolId = $baseId . '_' . $quoteId;
            $id = $market['url_symbol'];
            $precision = array (
                'amount' => $market['base_decimals'],
                'price' => $market['counter_decimals'],
            );
            $parts = explode (' ', $market['minimum_order']);
            $cost = $parts[0];
            // list ($cost, $currency) = explode (' ', $market['minimum_order']);
            $active = ($market['trading'] === 'Enabled');
            $lot = pow (10, -$precision['amount']);
            $result[] = array (
                'id' => $id,
                'symbol' => $symbol,
                'base' => $base,
                'quote' => $quote,
                'baseId' => $baseId,
                'quoteId' => $quoteId,
                'symbolId' => $symbolId,
                'info' => $market,
                'lot' => $lot,
                'active' => $active,
                'precision' => $precision,
                'limits' => array (
                    'amount' => array (
                        'min' => $lot,
                        'max' => null,
                    ),
                    'price' => array (
                        'min' => pow (10, -$precision['price']),
                        'max' => null,
                    ),
                    'cost' => array (
                        'min' => floatval ($cost),
                        'max' => null,
                    ),
                ),
            );
        }
        return $result;
    }

    public function fetch_order_book ($symbol, $limit = null, $params = array ()) {
        $this->load_markets();
        $orderbook = $this->publicGetOrderBookPair (array_merge (array (
            'pair' => $this->market_id($symbol),
        ), $params));
        $timestamp = intval ($orderbook['timestamp']) * 1000;
        return $this->parse_order_book($orderbook, $timestamp);
    }

    public function fetch_ticker ($symbol, $params = array ()) {
        $this->load_markets();
        $ticker = $this->publicGetTickerPair (array_merge (array (
            'pair' => $this->market_id($symbol),
        ), $params));
        $timestamp = intval ($ticker['timestamp']) * 1000;
        $vwap = floatval ($ticker['vwap']);
        $baseVolume = floatval ($ticker['volume']);
        $quoteVolume = $baseVolume * $vwap;
        return array (
            'symbol' => $symbol,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'high' => floatval ($ticker['high']),
            'low' => floatval ($ticker['low']),
            'bid' => floatval ($ticker['bid']),
            'ask' => floatval ($ticker['ask']),
            'vwap' => $vwap,
            'open' => floatval ($ticker['open']),
            'close' => null,
            'first' => null,
            'last' => floatval ($ticker['last']),
            'change' => null,
            'percentage' => null,
            'average' => null,
            'baseVolume' => $baseVolume,
            'quoteVolume' => $quoteVolume,
            'info' => $ticker,
        );
    }

    public function parse_trade ($trade, $market = null) {
        $timestamp = null;
        $symbol = null;
        if (is_array ($trade) && array_key_exists ('date', $trade)) {
            $timestamp = intval ($trade['date']) * 1000;
        } else if (is_array ($trade) && array_key_exists ('datetime', $trade)) {
            $timestamp = $this->parse8601 ($trade['datetime']);
        }
        // if overrided externally
        $side = $this->safe_string($trade, 'side');
        // only if not overrided externally
        if ($side === null)
            $side = ($trade['type'] === '0') ? 'buy' : 'sell';
        $orderId = $this->safe_string($trade, 'order_id');
        if (is_array ($trade) && array_key_exists ('currency_pair', $trade)) {
            $marketId = $trade['currency_pair'];
            if (is_array ($this->markets_by_id) && array_key_exists ($marketId, $this->markets_by_id))
                $market = $this->markets_by_id[$marketId];
        }
        $price = $this->safe_float($trade, 'price');
        $amount = $this->safe_float($trade, 'amount');
        $id = $this->safe_string($trade, 'tid');
        $id = $this->safe_string($trade, 'id', $id);
        if ($market !== null) {
            $price = $this->safe_float($trade, $market['symbolId'], $price);
            $amount = $this->safe_float($trade, $market['baseId'], $amount);
        }
        if ($market !== null)
            $symbol = $market['symbol'];
        return array (
            'id' => $id,
            'info' => $trade,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'symbol' => $symbol,
            'order' => $orderId,
            'type' => null,
            'side' => $side,
            'price' => $price,
            'amount' => $amount,
        );
    }

    public function fetch_trades ($symbol, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $response = $this->publicGetTransactionsPair (array_merge (array (
            'pair' => $market['id'],
            'time' => 'minute',
        ), $params));
        return $this->parse_trades($response, $market, $since, $limit);
    }

    public function fetch_balance ($params = array ()) {
        $this->load_markets();
        $balance = $this->privatePostBalance ();
        $result = array ( 'info' => $balance );
        $currencies = is_array ($this->currencies) ? array_keys ($this->currencies) : array ();
        for ($i = 0; $i < count ($currencies); $i++) {
            $currency = $currencies[$i];
            $lowercase = strtolower ($currency);
            $total = $lowercase . '_balance';
            $free = $lowercase . '_available';
            $used = $lowercase . '_reserved';
            $account = $this->account ();
            if (is_array ($balance) && array_key_exists ($free, $balance))
                $account['free'] = floatval ($balance[$free]);
            if (is_array ($balance) && array_key_exists ($used, $balance))
                $account['used'] = floatval ($balance[$used]);
            if (is_array ($balance) && array_key_exists ($total, $balance))
                $account['total'] = floatval ($balance[$total]);
            $result[$currency] = $account;
        }
        return $this->parse_balance($result);
    }

    public function create_order ($symbol, $type, $side, $amount, $price = null, $params = array ()) {
        $this->load_markets();
        $method = 'privatePost' . $this->capitalize ($side);
        $order = array (
            'pair' => $this->market_id($symbol),
            'amount' => $amount,
        );
        if ($type === 'market')
            $method .= 'Market';
        else
            $order['price'] = $price;
        $method .= 'Pair';
        $response = $this->$method (array_merge ($order, $params));
        return array (
            'info' => $response,
            'id' => $response['id'],
        );
    }

    public function cancel_order ($id, $symbol = null, $params = array ()) {
        $this->load_markets();
        return $this->privatePostCancelOrder (array ( 'id' => $id ));
    }

    public function parse_order_status ($order) {
        if (($order['status'] === 'Queue') || ($order['status'] === 'Open'))
            return 'open';
        if ($order['status'] === 'Finished')
            return 'closed';
        return $order['status'];
    }

    public function fetch_order_status ($id, $symbol = null) {
        $this->load_markets();
        $response = $this->privatePostOrderStatus (array ( 'id' => $id ));
        return $this->parse_order_status($response);
    }

    public function fetch_my_trades ($symbol = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = null;
        $request = array ();
        $method = 'privatePostUserTransactions';
        if ($symbol !== null) {
            $market = $this->market ($symbol);
            $request['pair'] = $market['id'];
            $method .= 'Pair';
        }
        $response = $this->$method (array_merge ($request, $params));
        return $this->parse_trades($response, $market, $since, $limit);
    }

    public function parse_order ($order, $market = null) {
        $timestamp = null;
        $datetimeString = $this->safe_string($order, 'datetime');
        if ($datetimeString !== null)
            $timestamp = $this->parse8601 ($datetimeString);
        $symbol = null;
        if ($market === null) {
            if (is_array ($order) && array_key_exists ('currency_pair', $order)) {
                $marketId = $order['currency_pair'];
                if (is_array ($this->markets_by_id) && array_key_exists ($marketId, $this->markets_by_id))
                    $market = $this->markets_by_id[$marketId];
            }
        }
        if ($market !== null)
            $symbol = $market['symbol'];
        $status = $this->safe_string($order, 'status');
        if (($status === 'In Queue') || ($status === 'Open'))
            $status = 'open';
        else if ($status === 'Finished')
            $status = 'closed';
        $amount = $this->safe_float($order, 'amount');
        $filled = 0;
        $trades = array ();
        $transactions = $this->safe_value($order, 'transactions');
        if ($transactions !== null) {
            if (gettype ($transactions) === 'array' && count (array_filter (array_keys ($transactions), 'is_string')) == 0) {
                for ($i = 0; $i < count ($transactions); $i++) {
                    $trade = $this->parse_trade($transactions[$i], $market);
                    $filled .= $trade['amount'];
                    $trades[] = $trade;
                }
            }
        }
        $remaining = $amount - $filled;
        $price = $this->safe_float($order, 'price');
        $side = $this->safe_string($order, 'type');
        if ($side !== null)
            $side = ($side === '1') ? 'sell' : 'buy';
        $fee = null;
        $cost = null;
        return array (
            'id' => $order['id'],
            'datetime' => $this->iso8601 ($timestamp),
            'timestamp' => $timestamp,
            'status' => $status,
            'symbol' => $symbol,
            'type' => null,
            'side' => $side,
            'price' => $price,
            'cost' => $cost,
            'amount' => $amount,
            'filled' => $filled,
            'remaining' => $remaining,
            'trades' => $trades,
            'fee' => $fee,
            'info' => $order,
        );
    }

    public function fetch_order ($id, $symbol = null, $params = array ()) {
        $this->load_markets();
        $response = $this->privatePostOrderStatus (array_merge (array (
            'id' => (string) $id,
        ), $params));
        $orders = $this->privatePostOpenOrdersAll ();
        $order = $this->filter_by($orders, 'id', (string) $id);
        return $this->parse_order(array_merge ($response, $order['0']));
    }

    public function get_currency_name ($code) {
        if ($code === 'BTC')
            return 'bitcoin';
        return strtolower ($code);
    }

    public function is_fiat ($code) {
        if ($code === 'USD')
            return true;
        if ($code === 'EUR')
            return true;
        return false;
    }

    public function withdraw ($code, $amount, $address, $tag = null, $params = array ()) {
        $isFiat = $this->is_fiat ($code);
        if ($isFiat)
            throw new ExchangeError ($this->id . ' fiat withdraw() for ' . $code . ' is not implemented yet');
        $name = $this->get_currency_name ($code);
        $request = array (
            'amount' => $amount,
            'address' => $address,
        );
        $v1 = ($code === 'BTC');
        $method = $v1 ? 'v1' : 'private'; // $v1 or v2
        $method .= 'Post' . $this->capitalize ($name) . 'Withdrawal';
        $query = $params;
        if ($code === 'XRP') {
            $tag = $this->safe_string($params, 'destination_tag');
            if ($tag) {
                $request['destination_tag'] = $tag;
                $query = $this->omit ($params, 'destination_tag');
            } else {
                throw new ExchangeError ($this->id . ' withdraw() requires a destination_tag param for ' . $code);
            }
        }
        $response = $this->$method (array_merge ($request, $query));
        return array (
            'info' => $response,
            'id' => $response['id'],
        );
    }

    public function sign ($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        $url = $this->urls['api'] . '/';
        if ($api !== 'v1')
            $url .= $this->version . '/';
        $url .= $this->implode_params($path, $params);
        $query = $this->omit ($params, $this->extract_params($path));
        if ($api === 'public') {
            if ($query)
                $url .= '?' . $this->urlencode ($query);
        } else {
            $this->check_required_credentials();
            $nonce = (string) $this->nonce ();
            $auth = $nonce . $this->uid . $this->apiKey;
            $signature = $this->encode ($this->hmac ($this->encode ($auth), $this->encode ($this->secret)));
            $query = array_merge (array (
                'key' => $this->apiKey,
                'signature' => strtoupper ($signature),
                'nonce' => $nonce,
            ), $query);
            $body = $this->urlencode ($query);
            $headers = array (
                'Content-Type' => 'application/x-www-form-urlencoded',
            );
        }
        return array ( 'url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers );
    }

    public function handle_errors ($httpCode, $reason, $url, $method, $headers, $body) {
        if (gettype ($body) != 'string')
            return; // fallback to default error handler
        if (strlen ($body) < 2)
            return; // fallback to default error handler
        if (($body[0] === '{') || ($body[0] === '[')) {
            $response = json_decode ($body, $as_associative_array = true);
            $status = $this->safe_string($response, 'status');
            if ($status === 'error') {
                $code = $this->safe_string($response, 'code');
                if ($code !== null) {
                    if ($code === 'API0005')
                        throw new AuthenticationError ($this->id . ' invalid signature, use the uid for the main account if you have subaccounts');
                }
                throw new ExchangeError ($this->id . ' ' . $this->json ($response));
            }
        }
    }
}
