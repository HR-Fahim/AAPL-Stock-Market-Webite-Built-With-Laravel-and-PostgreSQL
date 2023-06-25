<?php

namespace App\Http\Controllers;

use App\Models\StockValue;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $url = 'https://data.nasdaq.com/api/v3/datasets/WIKI/AAPL/data.csv?api_key=AYRF3Egdy36rfxPUhTzz';

        $client = new Client();
        $response = $client->get($url);
        $csvData = $response->getBody()->getContents();

        $lines = explode("\n", $csvData);
        $headers = str_getcsv(array_shift($lines));

        $data = [];
        foreach ($lines as $line) {
            $row = str_getcsv($line);
            if (count($row) === count($headers)) {
                $data[] = array_combine($headers, $row);
            }
        }

        foreach ($data as $stock) {
            StockValue::create([
                'date' => $stock['Date'],
                'open' => $stock['Open'],
                'high' => $stock['High'],
                'low' => $stock['Low'],
                'close' => $stock['Close'],
                'volume' => $stock['Volume'],
                'ex_dividend' => $stock['Ex-Dividend'],
                'split_ratio' => $stock['Split Ratio'],
                'adj_open' => $stock['Adj. Open'],
                'adj_high' => $stock['Adj. High'],
                'adj_low' => $stock['Adj. Low'],
                'adj_close' => $stock['Adj. Close'],
                'adj_volume' => $stock['Adj. Volume']
            ]);
        }

        return view('stock.index', ['data' => $data]);
    }
}
