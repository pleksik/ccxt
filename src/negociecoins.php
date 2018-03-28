<?php

namespace ccxt;

class negociecoins extends Exchange {

    public function describe () {
        return array_replace_recursive (parent::describe (), array (
            'id' => 'negociecoins',
            'name' => 'NegocieCoins',
            'countries' => 'BR',
            'rateLimit' => 1000,
            'has' => array (
                'fetchOpenOrders' => true,
                'fetchClosedOrders' => true,
            ),
            'urls' => array (
                'logo' => 'https://user-images.githubusercontent.com/1294454/38008571-25a6246e-3258-11e8-969b-aeb691049245.jpg',
                'api' => array (
                    'public' => 'https://broker.negociecoins.com.br/api/v3',
                    'private' => 'https://broker.negociecoins.com.br/tradeapi/v1',
                ),
                'www' => 'https://www.negociecoins.com.br',
                'doc' => array (
                    'https://www.negociecoins.com.br/documentacao-tradeapi',
                    'https://www.negociecoins.com.br/documentacao-api',
                ),
                'fees' => 'https://www.negociecoins.com.br/comissoes',
            ),
            'api' => array (
                'public' => array (
                    'get' => array (
                        '{PAR}/ticker',
                        '{PAR}/orderbook',
                        '{PAR}/trades',
                        '{PAR}/trades/{timestamp_inicial}',
                        '{PAR}/trades/{timestamp_inicial}/{timestamp_final}',
                    ),
                ),
                'private' => array (
                    'get' => array (
                        'user/balance',
                        'user/order/{orderId}',
                    ),
                    'post' => array (
                        'user/order',
                        'user/orders',
                    ),
                    'delete' => array (
                        'user/order/{orderId}',
                    ),
                ),
            ),
            'markets' => array (
                'B2X/BRL' => array ( 'id' => 'b2xbrl', 'symbol' => 'B2X/BRL', 'base' => 'B2X', 'quote' => 'BRL' ),
                'BCH/BRL' => array ( 'id' => 'bchbrl', 'symbol' => 'BCH/BRL', 'base' => 'BCH', 'quote' => 'BRL' ),
                'BTC/BRL' => array ( 'id' => 'btcbrl', 'symbol' => 'BTC/BRL', 'base' => 'BTC', 'quote' => 'BRL' ),
                'BTG/BRL' => array ( 'id' => 'btgbrl', 'symbol' => 'BTG/BRL', 'base' => 'BTG', 'quote' => 'BRL' ),
                'DASH/BRL' => array ( 'id' => 'dashbrl', 'symbol' => 'DASH/BRL', 'base' => 'DASH', 'quote' => 'BRL' ),
                'LTC/BRL' => array ( 'id' => 'ltcbrl', 'symbol' => 'LTC/BRL', 'base' => 'LTC', 'quote' => 'BRL' ),
            ),
            'fees' => array (
                'trading' => array (
                    'maker' => 0.003,
                    'taker' => 0.004,
                ),
                'funding' => array (
                    'withdraw' => array (
                        'BTC' => 0.001,
                        'BCH' => 0.00003,
                        'BTG' => 0.00009,
                        'LTC' => 0.005,
                    ),
                ),
            ),
            'limits' => array (
                'amount' => array (
                    'min' => 0.001,
                    'max' => null,
                ),
            ),
            'precision' => array (
                'amount' => 8,
                'price' => 8,
            ),
        ));
    }

    public function parse_ticker ($ticker, $market = null) {
        $timestamp = $ticker['date'] * 1000;
        $symbol = ($market !== null) ? $market['symbol'] : null;
        $last = floatval ($ticker['last']);
        return array (
            'symbol' => $symbol,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'high' => floatval ($ticker['high']),
            'low' => floatval ($ticker['low']),
            'bid' => floatval ($ticker['buy']),
            'bidVolume' => null,
            'ask' => floatval ($ticker['sell']),
            'askVolume' => null,
            'vwap' => null,
            'open' => null,
            'close' => $last,
            'last' => $last,
            'previousClose' => null,
            'change' => null,
            'percentage' => null,
            'average' => null,
            'baseVolume' => floatval ($ticker['vol']),
            'quoteVolume' => null,
            'info' => $ticker,
        );
    }

    public function fetch_ticker ($symbol, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $ticker = $this->publicGetPARTicker (array_merge (array (
            'PAR' => $market['id'],
        ), $params));
        return $this->parse_ticker($ticker, $market);
    }

    public function fetch_order_book ($symbol, $limit = null, $params = array ()) {
        $this->load_markets();
        $orderbook = $this->publicGetPAROrderbook (array_merge (array (
            'PAR' => $this->market_id($symbol),
        ), $params));
        return $this->parse_order_book($orderbook, null, 'bid', 'ask', 'price', 'quantity');
    }

    public function parse_trade ($trade, $market = null) {
        $timestamp = $trade['date'] * 1000;
        $price = floatval ($trade['price']);
        $amount = floatval ($trade['amount']);
        $symbol = $market['symbol'];
        $cost = floatval ($this->cost_to_precision($symbol, $price * $amount));
        return array (
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'symbol' => $symbol,
            'id' => $this->safe_string($trade, 'tid'),
            'order' => null,
            'type' => 'limit',
            'side' => strtolower ($trade['type']),
            'price' => $price,
            'amount' => $amount,
            'cost' => $cost,
            'fee' => null,
            'info' => $trade,
        );
    }

    public function fetch_trades ($symbol, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        if ($since === null)
            $since = 0;
        $request = array (
            'PAR' => $market['id'],
            'timestamp_inicial' => intval ($since / 1000),
        );
        $trades = $this->publicGetPARTradesTimestampInicial (array_merge ($request, $params));
        return $this->parse_trades($trades, $market, $since, $limit);
    }

    public function fetch_balance ($params = array ()) {
        $this->load_markets();
        $balances = $this->privateGetUserBalance ($params);
        $result = array ( 'info' => $balances );
        $currencies = is_array ($balances) ? array_keys ($balances) : array ();
        for ($i = 0; $i < count ($currencies); $i++) {
            $id = $currencies[$i];
            $balance = $balances[$id];
            $currency = $this->common_currency_code($id);
            $account = array (
                'free' => floatval ($balance['total']),
                'used' => 0.0,
                'total' => floatval ($balance['available']),
            );
            $account['used'] = $account['total'] - $account['free'];
            $result[$currency] = $account;
        }
        return $this->parse_balance($result);
    }

    public function parse_order ($order, $market = null) {
        $symbol = null;
        if (!$market) {
            $market = $this->safe_value($this->marketsById, $order['pair']);
            if ($market)
                $symbol = $market['symbol'];
        }
        $timestamp = $this->parse8601 ($order['created']);
        $price = floatval ($order['price']);
        $amount = floatval ($order['quantity']);
        $cost = $this->safe_float($order, 'total');
        $remaining = $this->safe_float($order, 'pending_quantity');
        $filled = $this->safe_float($order, 'executed_quantity');
        $status = $order['status'];
        // cancelled, $filled, partially $filled, pending, rejected
        if ($status === 'filled') {
            $status = 'closed';
        } else if ($status === 'cancelled') {
            $status = 'canceled';
        } else {
            $status = 'open';
        }
        $trades = null;
        // if ($order['operations'])
        //     $trades = $this->parse_trades($order['operations']);
        return array (
            'id' => (string) $order['id'],
            'datetime' => $this->iso8601 ($timestamp),
            'timestamp' => $timestamp,
            'status' => $status,
            'symbol' => $symbol,
            'type' => 'limit',
            'side' => $order['type'],
            'price' => $price,
            'cost' => $cost,
            'amount' => $amount,
            'filled' => $filled,
            'remaining' => $remaining,
            'trades' => $trades,
            'fee' => array (
                'currency' => $market['quote'],
                'cost' => floatval ($order['fee']),
            ),
            'info' => $order,
        );
    }

    public function create_order ($symbol, $type, $side, $amount, $price = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $response = $this->privatePostUserOrder (array_merge (array (
            'pair' => $market['id'],
            'price' => $this->price_to_precision($symbol, $price),
            'volume' => $this->amount_to_precision($symbol, $amount),
            'type' => $side,
        ), $params));
        $order = $this->parse_order($response[0], $market);
        $id = $order['id'];
        $this->orders[$id] = $order;
        return $order;
    }

    public function cancel_order ($id, $symbol = null, $params = array ()) {
        $this->load_markets();
        $market = $this->markets[$symbol];
        $response = $this->privateDeleteUserOrderOrderId (array_merge (array (
            'orderId' => $id,
        ), $params));
        return $this->parse_order($response[0], $market);
    }

    public function fetch_order ($id, $symbol = null, $params = array ()) {
        $this->load_markets();
        $order = $this->privateGetUserOrderOrderId (array_merge (array (
            'orderId' => $id,
        ), $params));
        return $this->parse_order($order[0]);
    }

    public function fetch_orders ($symbol = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $request = array (
            'pair' => $market['id'],
            // type => buy, sell
            // status => cancelled, filled, partially filled, pending, rejected
            // startId
            // endId
            // startDate yyyy-MM-dd
            // endDate => yyyy-MM-dd
        );
        if ($since !== null)
            $request['startDate'] = $this->ymd ($since);
        if ($limit !== null)
            $request['pageSize'] = $limit;
        $orders = $this->privatePostUserOrders (array_merge ($request, $params));
        return $this->parse_orders($orders, $market);
    }

    public function fetch_open_orders ($symbol = null, $since = null, $limit = null, $params = array ()) {
        return $this->fetch_orders($symbol, $since, $limit, array_merge (array (
            'status' => 'pending',
        ), $params));
    }

    public function fetch_closed_orders ($symbol = null, $since = null, $limit = null, $params = array ()) {
        return $this->fetch_orders($symbol, $since, $limit, array_merge (array (
            'status' => 'filled',
        ), $params));
    }

    public function nonce () {
        return $this->milliseconds ();
    }

    public function sign ($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        $url = $this->urls['api'][$api] . '/' . $this->implode_params($path, $params);
        $query = $this->omit ($params, $this->extract_params($path));
        $queryString = $this->urlencode ($query);
        if ($api === 'public') {
            if (strlen ($queryString))
                $url .= '?' . $queryString;
        } else {
            $this->check_required_credentials();
            $timestamp = (string) $this->seconds ();
            $nonce = (string) $this->nonce ();
            $content = '';
            if (strlen ($queryString)) {
                $body = $this->json ($query);
                $content = $this->hash ($this->encode ($body), 'md5', 'base64');
            } else {
                $body = '';
            }
            $uri = strtolower ($this->encode_uri_component($url));
            $payload = implode ('', array ($this->apiKey, $method, $uri, $timestamp, $nonce, $content));
            $secret = base64_decode ($this->secret);
            $signature = $this->hmac ($this->encode ($payload), $this->encode ($secret), 'sha256', 'base64');
            $signature = $this->binary_to_string($signature);
            $auth = implode (':', array ($this->apiKey, $signature, $nonce, $timestamp));
            $headers = array (
                'Authorization' => 'amx ' . $auth,
            );
            if ($method === 'POST') {
                $headers['Content-Type'] = 'application/json; charset=UTF-8';
                $headers['Content-Length'] = is_array ($body) ? count ($body) : 0;
            } else if (strlen ($queryString)) {
                $url .= '?' . $queryString;
                $body = null;
            }
        }
        return array ( 'url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers );
    }
}
