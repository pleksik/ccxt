<?php

namespace ccxt;

class bitcoincoid extends Exchange {

    public function describe () {
        return array_replace_recursive (parent::describe (), array (
            'id' => 'bitcoincoid',
            'name' => 'Bitcoin.co.id',
            'countries' => 'ID', // Indonesia
            'has' => array (
                'CORS' => false,
                'createMarketOrder' => false,
                'fetchTickers' => false,
                'fetchOrder' => true,
                'fetchOrders' => false,
                'fetchClosedOrders' => true,
                'fetchOpenOrders' => true,
                'fetchMyTrades' => false,
                'fetchCurrencies' => false,
                'withdraw' => false,
            ),
            'version' => '1.7', // as of 6 November 2017
            'urls' => array (
                'logo' => 'https://user-images.githubusercontent.com/1294454/27766138-043c7786-5ecf-11e7-882b-809c14f38b53.jpg',
                'api' => array (
                    'public' => 'https://vip.bitcoin.co.id/api',
                    'private' => 'https://vip.bitcoin.co.id/tapi',
                ),
                'www' => 'https://www.bitcoin.co.id',
                'doc' => array (
                    'https://vip.bitcoin.co.id/downloads/BITCOINCOID-API-DOCUMENTATION.pdf',
                ),
            ),
            'api' => array (
                'public' => array (
                    'get' => array (
                        '{pair}/ticker',
                        '{pair}/trades',
                        '{pair}/depth',
                    ),
                ),
                'private' => array (
                    'post' => array (
                        'getInfo',
                        'transHistory',
                        'trade',
                        'tradeHistory',
                        'getOrder',
                        'openOrders',
                        'cancelOrder',
                        'orderHistory',
                    ),
                ),
            ),
            'markets' => array (
                'BTC/IDR' => array ( 'id' => 'btc_idr', 'symbol' => 'BTC/IDR', 'base' => 'BTC', 'quote' => 'IDR', 'baseId' => 'btc', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 0.0001, 'max' => null ))),
                'BCH/IDR' => array ( 'id' => 'bch_idr', 'symbol' => 'BCH/IDR', 'base' => 'BCH', 'quote' => 'IDR', 'baseId' => 'bch', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 0.001, 'max' => null ))),
                'BTG/IDR' => array ( 'id' => 'btg_idr', 'symbol' => 'BTG/IDR', 'base' => 'BTG', 'quote' => 'IDR', 'baseId' => 'btg', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 0.01, 'max' => null ))),
                'ETH/IDR' => array ( 'id' => 'eth_idr', 'symbol' => 'ETH/IDR', 'base' => 'ETH', 'quote' => 'IDR', 'baseId' => 'eth', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 0.01, 'max' => null ))),
                'ETC/IDR' => array ( 'id' => 'etc_idr', 'symbol' => 'ETC/IDR', 'base' => 'ETC', 'quote' => 'IDR', 'baseId' => 'etc', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 0.1, 'max' => null ))),
                'IGNIS/IDR' => array ( 'id' => 'ignis_idr', 'symbol' => 'IGNIS/IDR', 'base' => 'IGNIS', 'quote' => 'IDR', 'baseId' => 'ignis', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 1, 'max' => null ))),
                'LTC/IDR' => array ( 'id' => 'ltc_idr', 'symbol' => 'LTC/IDR', 'base' => 'LTC', 'quote' => 'IDR', 'baseId' => 'ltc', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 0.01, 'max' => null ))),
                'NXT/IDR' => array ( 'id' => 'nxt_idr', 'symbol' => 'NXT/IDR', 'base' => 'NXT', 'quote' => 'IDR', 'baseId' => 'nxt', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 5, 'max' => null ))),
                'TEN/IDR' => array ( 'id' => 'ten_idr', 'symbol' => 'TEN/IDR', 'base' => 'TEN', 'quote' => 'IDR', 'baseId' => 'ten', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 5, 'max' => null ))),
                'WAVES/IDR' => array ( 'id' => 'waves_idr', 'symbol' => 'WAVES/IDR', 'base' => 'WAVES', 'quote' => 'IDR', 'baseId' => 'waves', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 0.1, 'max' => null ))),
                'XRP/IDR' => array ( 'id' => 'xrp_idr', 'symbol' => 'XRP/IDR', 'base' => 'XRP', 'quote' => 'IDR', 'baseId' => 'xrp', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 10, 'max' => null ))),
                'XZC/IDR' => array ( 'id' => 'xzc_idr', 'symbol' => 'XZC/IDR', 'base' => 'XZC', 'quote' => 'IDR', 'baseId' => 'xzc', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 0.1, 'max' => null ))),
                'XLM/IDR' => array ( 'id' => 'str_idr', 'symbol' => 'XLM/IDR', 'base' => 'XLM', 'quote' => 'IDR', 'baseId' => 'str', 'quoteId' => 'idr', 'limits' => array ( 'amount' => array ( 'min' => 20, 'max' => null ))),
                'BTS/BTC' => array ( 'id' => 'bts_btc', 'symbol' => 'BTS/BTC', 'base' => 'BTS', 'quote' => 'BTC', 'baseId' => 'bts', 'quoteId' => 'btc', 'limits' => array ( 'amount' => array ( 'min' => 0.01, 'max' => null ))),
                'DASH/BTC' => array ( 'id' => 'drk_btc', 'symbol' => 'DASH/BTC', 'base' => 'DASH', 'quote' => 'BTC', 'baseId' => 'drk', 'quoteId' => 'btc', 'limits' => array ( 'amount' => array ( 'min' => 0.01, 'max' => null ))),
                'DOGE/BTC' => array ( 'id' => 'doge_btc', 'symbol' => 'DOGE/BTC', 'base' => 'DOGE', 'quote' => 'BTC', 'baseId' => 'doge', 'quoteId' => 'btc', 'limits' => array ( 'amount' => array ( 'min' => 1, 'max' => null ))),
                'ETH/BTC' => array ( 'id' => 'eth_btc', 'symbol' => 'ETH/BTC', 'base' => 'ETH', 'quote' => 'BTC', 'baseId' => 'eth', 'quoteId' => 'btc', 'limits' => array ( 'amount' => array ( 'min' => 0.001, 'max' => null ))),
                'LTC/BTC' => array ( 'id' => 'ltc_btc', 'symbol' => 'LTC/BTC', 'base' => 'LTC', 'quote' => 'BTC', 'baseId' => 'ltc', 'quoteId' => 'btc', 'limits' => array ( 'amount' => array ( 'min' => 0.01, 'max' => null ))),
                'NXT/BTC' => array ( 'id' => 'nxt_btc', 'symbol' => 'NXT/BTC', 'base' => 'NXT', 'quote' => 'BTC', 'baseId' => 'nxt', 'quoteId' => 'btc', 'limits' => array ( 'amount' => array ( 'min' => 0.01, 'max' => null ))),
                'TEN/BTC' => array ( 'id' => 'ten_btc', 'symbol' => 'TEN/BTC', 'base' => 'TEN', 'quote' => 'BTC', 'baseId' => 'ten', 'quoteId' => 'btc', 'limits' => array ( 'amount' => array ( 'min' => 0.01, 'max' => null ))),
                'XLM/BTC' => array ( 'id' => 'str_btc', 'symbol' => 'XLM/BTC', 'base' => 'XLM', 'quote' => 'BTC', 'baseId' => 'str', 'quoteId' => 'btc', 'limits' => array ( 'amount' => array ( 'min' => 0.01, 'max' => null ))),
                'XEM/BTC' => array ( 'id' => 'nem_btc', 'symbol' => 'XEM/BTC', 'base' => 'XEM', 'quote' => 'BTC', 'baseId' => 'nem', 'quoteId' => 'btc', 'limits' => array ( 'amount' => array ( 'min' => 1, 'max' => null ))),
                'XRP/BTC' => array ( 'id' => 'xrp_btc', 'symbol' => 'XRP/BTC', 'base' => 'XRP', 'quote' => 'BTC', 'baseId' => 'xrp', 'quoteId' => 'btc', 'limits' => array ( 'amount' => array ( 'min' => 0.01, 'max' => null ))),
            ),
            'fees' => array (
                'trading' => array (
                    'tierBased' => false,
                    'percentage' => true,
                    'maker' => 0,
                    'taker' => 0.003,
                ),
            ),
        ));
    }

    public function fetch_balance ($params = array ()) {
        $this->load_markets();
        $response = $this->privatePostGetInfo ();
        $balance = $response['return'];
        $result = array ( 'info' => $balance );
        $codes = is_array ($this->currencies) ? array_keys ($this->currencies) : array ();
        for ($i = 0; $i < count ($codes); $i++) {
            $code = $codes[$i];
            $currency = $this->currencies[$code];
            $lowercase = $currency['id'];
            $account = $this->account ();
            $account['free'] = $this->safe_float($balance['balance'], $lowercase, 0.0);
            $account['used'] = $this->safe_float($balance['balance_hold'], $lowercase, 0.0);
            $account['total'] = $this->sum ($account['free'], $account['used']);
            $result[$code] = $account;
        }
        return $this->parse_balance($result);
    }

    public function fetch_order_book ($symbol, $limit = null, $params = array ()) {
        $this->load_markets();
        $orderbook = $this->publicGetPairDepth (array_merge (array (
            'pair' => $this->market_id($symbol),
        ), $params));
        return $this->parse_order_book($orderbook, null, 'buy', 'sell');
    }

    public function fetch_ticker ($symbol, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $response = $this->publicGetPairTicker (array_merge (array (
            'pair' => $market['id'],
        ), $params));
        $ticker = $response['ticker'];
        $timestamp = floatval ($ticker['server_time']) * 1000;
        $baseVolume = 'vol_' . strtolower ($market['baseId']);
        $quoteVolume = 'vol_' . strtolower ($market['quoteId']);
        return array (
            'symbol' => $symbol,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'high' => floatval ($ticker['high']),
            'low' => floatval ($ticker['low']),
            'bid' => floatval ($ticker['buy']),
            'ask' => floatval ($ticker['sell']),
            'vwap' => null,
            'open' => null,
            'close' => null,
            'first' => null,
            'last' => floatval ($ticker['last']),
            'change' => null,
            'percentage' => null,
            'average' => null,
            'baseVolume' => floatval ($ticker[$baseVolume]),
            'quoteVolume' => floatval ($ticker[$quoteVolume]),
            'info' => $ticker,
        );
    }

    public function parse_trade ($trade, $market) {
        $timestamp = intval ($trade['date']) * 1000;
        return array (
            'id' => $trade['tid'],
            'info' => $trade,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'symbol' => $market['symbol'],
            'type' => null,
            'side' => $trade['type'],
            'price' => floatval ($trade['price']),
            'amount' => floatval ($trade['amount']),
        );
    }

    public function fetch_trades ($symbol, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $response = $this->publicGetPairTrades (array_merge (array (
            'pair' => $market['id'],
        ), $params));
        return $this->parse_trades($response, $market, $since, $limit);
    }

    public function parse_order ($order, $market = null) {
        $side = null;
        if (is_array ($order) && array_key_exists ('type', $order))
            $side = $order['type'];
        $status = $this->safe_string($order, 'status', 'open');
        if ($status === 'filled') {
            $status = 'closed';
        } else if ($status === 'calcelled') {
            $status = 'canceled';
        }
        $symbol = null;
        $cost = null;
        $price = $this->safe_float($order, 'price');
        $amount = null;
        $remaining = null;
        $filled = null;
        if ($market) {
            $symbol = $market['symbol'];
            $quoteId = $market['quoteId'];
            $baseId = $market['baseId'];
            if (($market['quoteId'] === 'idr') && (is_array ($order) && array_key_exists ('order_rp', $order)))
                $quoteId = 'rp';
            if (($market['baseId'] === 'idr') && (is_array ($order) && array_key_exists ('remain_rp', $order)))
                $baseId = 'rp';
            $cost = $this->safe_float($order, 'order_' . $quoteId);
            if ($cost) {
                $amount = $cost / $price;
                $remainingCost = $this->safe_float($order, 'remain_' . $quoteId);
                if ($remainingCost !== null) {
                    $remaining = $remainingCost / $price;
                    $filled = $amount - $remaining;
                }
            } else {
                $amount = $this->safe_float($order, 'order_' . $baseId);
                $cost = $price * $amount;
                $remaining = $this->safe_float($order, 'remain_' . $baseId);
                $filled = $amount - $remaining;
            }
        }
        $average = null;
        if ($filled)
            $average = $cost / $filled;
        $timestamp = intval ($order['submit_time']);
        $fee = null;
        $result = array (
            'info' => $order,
            'id' => $order['order_id'],
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'symbol' => $symbol,
            'type' => 'limit',
            'side' => $side,
            'price' => $price,
            'cost' => $cost,
            'average' => $average,
            'amount' => $amount,
            'filled' => $filled,
            'remaining' => $remaining,
            'status' => $status,
            'fee' => $fee,
        );
        return $result;
    }

    public function fetch_order ($id, $symbol = null, $params = array ()) {
        if (!$symbol)
            throw new ExchangeError ($this->id . ' fetchOrder requires a symbol');
        $this->load_markets();
        $market = $this->market ($symbol);
        $response = $this->privatePostGetOrder (array_merge (array (
            'pair' => $market['id'],
            'order_id' => $id,
        ), $params));
        $orders = $response['return'];
        $order = $this->parse_order(array_merge (array ( 'id' => $id ), $orders['order']), $market);
        return array_merge (array ( 'info' => $response ), $order);
    }

    public function fetch_open_orders ($symbol = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = null;
        $request = array ();
        if ($symbol) {
            $market = $this->market ($symbol);
            $request['pair'] = $market['id'];
        }
        $response = $this->privatePostOpenOrders (array_merge ($request, $params));
        $rawOrders = $response['return']['orders'];
        // array ( success => 1, return => { orders => null )} if no orders
        if (!$rawOrders)
            return array ();
        // array ( success => 1, return => { orders => array ( ... objects ) )} for orders fetched by $symbol
        if ($symbol !== null)
            return $this->parse_orders($rawOrders, $market, $since, $limit);
        // array ( success => 1, return => { orders => array ( marketid => array ( ... objects ) ))} if all orders are fetched
        $marketIds = is_array ($rawOrders) ? array_keys ($rawOrders) : array ();
        $exchangeOrders = array ();
        for ($i = 0; $i < count ($marketIds); $i++) {
            $marketId = $marketIds[$i];
            $marketOrders = $rawOrders[$marketId];
            $market = $this->markets_by_id[$marketId];
            $parsedOrders = $this->parse_orders($marketOrders, $market, $since, $limit);
            $exchangeOrders = $this->array_concat($exchangeOrders, $parsedOrders);
        }
        return $exchangeOrders;
    }

    public function fetch_closed_orders ($symbol = null, $since = null, $limit = null, $params = array ()) {
        if (!$symbol)
            throw new ExchangeError ($this->id . ' fetchOrders requires a symbol');
        $this->load_markets();
        $request = array ();
        $market = null;
        if ($symbol) {
            $market = $this->market ($symbol);
            $request['pair'] = $market['id'];
        }
        $response = $this->privatePostOrderHistory (array_merge ($request, $params));
        $orders = $this->parse_orders($response['return']['orders'], $market, $since, $limit);
        $orders = $this->filter_by($orders, 'status', 'closed');
        if ($symbol)
            return $this->filter_by_symbol($orders, $symbol);
        return $orders;
    }

    public function create_order ($symbol, $type, $side, $amount, $price = null, $params = array ()) {
        if ($type !== 'limit')
            throw new ExchangeError ($this->id . ' allows limit orders only');
        $this->load_markets();
        $market = $this->market ($symbol);
        $order = array (
            'pair' => $market['id'],
            'type' => $side,
            'price' => $price,
        );
        $currency = $market['baseId'];
        if ($side === 'buy') {
            $order[$market['quoteId']] = $amount * $price;
        } else {
            $order[$market['baseId']] = $amount;
        }
        $order[$currency] = $amount;
        $result = $this->privatePostTrade (array_merge ($order, $params));
        return array (
            'info' => $result,
            'id' => (string) $result['return']['order_id'],
        );
    }

    public function cancel_order ($id, $symbol = null, $params = array ()) {
        if ($symbol === null)
            throw new ExchangeError ($this->id . ' cancelOrder requires a $symbol argument');
        $side = $this->safe_value($params, 'side');
        if ($side === null)
            throw new ExchangeError ($this->id . ' cancelOrder requires an extra "$side" param');
        $this->load_markets();
        $market = $this->market ($symbol);
        return $this->privatePostCancelOrder (array_merge (array (
            'order_id' => $id,
            'pair' => $market['id'],
            'type' => $params['side'],
        ), $params));
    }

    public function sign ($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        $url = $this->urls['api'][$api];
        if ($api === 'public') {
            $url .= '/' . $this->implode_params($path, $params);
        } else {
            $this->check_required_credentials();
            $body = $this->urlencode (array_merge (array (
                'method' => $path,
                'nonce' => $this->nonce (),
            ), $params));
            $headers = array (
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Key' => $this->apiKey,
                'Sign' => $this->hmac ($this->encode ($body), $this->encode ($this->secret), 'sha512'),
            );
        }
        return array ( 'url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers );
    }

    public function handle_errors ($code, $reason, $url, $method, $headers, $body, $response = null) {
        // array ( success => 0, error => "invalid order." )
        if ($response === null)
            if ($body[0] === '{')
                $response = json_decode ($body, $as_associative_array = true);
        if (!(is_array ($response) && array_key_exists ('success', $response)))
            return; // no 'success' property on public responses
        if ($response['success'] === 1) {
            // array ( success => 1, return => { orders => array () )}
            if (!(is_array ($response) && array_key_exists ('return', $response)))
                throw new ExchangeError ($this->id . ' => malformed $response => ' . $this->json ($response));
            else
                return;
        }
        $message = $response['error'];
        $feedback = $this->id . ' ' . $this->json ($response);
        if ($message === 'Insufficient balance.') {
            throw new InsufficientFunds ($feedback);
        } else if ($message === 'invalid order.') {
            throw new OrderNotFound ($feedback); // cancelOrder(1)
        } else if (mb_strpos ($message, 'Minimum price ') !== false) {
            throw new InvalidOrder ($feedback); // price < limits.price.min, on createLimitBuyOrder ('ETH/BTC', 1, 0)
        } else if (mb_strpos ($message, 'Minimum order ') !== false) {
            throw new InvalidOrder ($feedback); // cost < limits.cost.min on createLimitBuyOrder ('ETH/BTC', 0, 1)
        } else if ($message === 'Invalid credentials. API not found or session has expired.') {
            throw new AuthenticationError ($feedback); // on bad apiKey
        } else if ($message === 'Invalid credentials. Bad sign.') {
            throw new AuthenticationError ($feedback); // on bad secret
        }
        throw new ExchangeError ($this->id . ' => unknown error => ' . $this->json ($response));
    }
}
