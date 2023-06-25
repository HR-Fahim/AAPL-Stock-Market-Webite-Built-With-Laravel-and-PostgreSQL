## About

This repository shows the process of building a simple website that displays recent AAPL stock market values fetched from a CSV file. The website will be built using Visual Studio 2022 and the Laravel framework. Additionally, the stock data will be stored in a PostgreSQL database.

![Stock Chart](https://github.com/HR-Fahim/AAPL-Stock-Market-Site-Using-Laravel-and-PostgreSQL/assets/66734379/db9544a0-87c1-4fa3-946c-d3f4c0b554ab)


### Prerequisites

Before starting, make sure you have the following:

- Visual Studio 2022 installed on your system.
- PHP and Composer installed.
- PostgreSQL installed and running.
- Basic knowledge of Laravel.

### Step 1: Create a new Laravel project

1. Open Visual Studio 2022.
2. Create a new Laravel project by following these steps:
   - Click on "Create a new project" on the start page.
   - Select "Laravel" from the project templates.
   - Choose a project name and location.
   - Click "Create" to generate the Laravel project.
     
or using cmd:

1. Open Visual Studio 2022 and create a new Laravel project using Composer by running the following command in the terminal:
   ```
   composer create-project --prefer-dist laravel/laravel stock-website
   ```
2. Change to the project directory:
   ```
   cd stock-website
   ```

### Step 2: Set up the PostgreSQL database

1. Open the `.env` file in the root directory of your Laravel project.
2. Update the following database configuration values to match your PostgreSQL database:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

### Step 3: Install Required Packages

1. Open a terminal and navigate to the root directory of your Laravel project.
2. Run the following command to install the Guzzle HTTP client:
   ```
   composer require guzzlehttp/guzzle
   ```
3. Run the following command to install the PostgreSQL driver by running the following commands:
   ```
   composer require --dev pgsql
   ```

### Step 4: Create a new controller

1. In Visual Studio 2022, right-click on the "Controllers" folder in the "app/Http" directory.
2. Select "Add" -> "New Scaffolded Item".
3. Choose "MVC Controller with views, using Entity Framework".
4. Enter "StockController" as the controller name.
5. Click "Add".
6. Select "New Data Context".
7. Enter "StockContext" as the data context name.
8. Click "Add" to create the controller and data context.

or use this method by creating Route and Controller:

1. Open the `routes/web.php` file and add the following route:
   ```
   use App\Http\Controllers\StockController;
   Route::get('/stock', [StockController::class, 'index']);
   ```
2. Create a new controller by running the following command in the terminal:
   ```
   php artisan make:controller StockController
   ```

### Step 5: Modify the StockController

1. Open the `app/Http/Controllers/StockController.php` file.
2. Replace the existing code with the following and add API:
   ```php
   <?php

   /**
   * MIT License
   * 
   * Copyright (c) 2023, Habibur Rahaman Fahim
   * 
   * Permission is hereby granted, free of charge, to any person obtaining a copy
   * of this software and associated documentation files (the "Software"), to deal
   * in the Software without restriction, including without limitation the rights
   * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
   * copies of the Software, and to permit persons to whom the Software is
   * furnished to do so, subject to the following conditions:
   * 
   * The above copyright notice and this permission notice shall be included in all
   * copies or substantial portions of the Software.
   * 
   * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
   * SOFTWARE.
   */
   
   namespace App\Http\Controllers;
   
   use App\Models\StockValue; // Detail in next steps
   use GuzzleHttp\Client;
   use Illuminate\Http\Request;
   
   class StockController extends Controller
   {
       public function index()
       {
          // API used from 'https://data.nasdaq.com'
          $url = 'https://data.nasdaq.com/api/v3/datasets/OPEC/ORB.csv?api_key=USE_YOUR_API';
   
           $client = new Client();
           $response = $client->get($url);
           $csvData = $response->getBody()->getContents();
   
           $lines = explode("\n", $csvData);
           $headers = str_getcsv(array_shift($lines));
   
           $data = [];
           foreach ($lines as $line) {
               $row = array_combine($headers, str_getcsv($line));
               $data[] = $row;
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
   ```
   Make sure to include the `use App\Models\StockValue;` statement at the beginning of the file. Follow the next steps to implement this.

### Step 6: Create the StockValue model

1. In Visual Studio 2022, right-click on the "Models" folder in the "app" directory.
2. Select "Add" -> "Class".
3. Enter "StockValue" as the class name.
4. Click "Add".
5. Replace the code in the newly created `StockValue.php` file with the following:
   ```php
   <?php

   /**
   * MIT License
   * 
   * Copyright (c) 2023, Habibur Rahaman Fahim
   * 
   * Permission is hereby granted, free of charge, to any person obtaining a copy
   * of this software and associated documentation files (the "Software"), to deal
   * in the Software without restriction, including without limitation the rights
   * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
   * copies of the Software, and to permit persons to whom the Software is
   * furnished to do so, subject to the following conditions:
   * 
   * The above copyright notice and this permission notice shall be included in all
   * copies or substantial portions of the Software.
   * 
   * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
   * SOFTWARE.
   */
   
   namespace App\Models;
   
   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;
   
   class StockValue extends Model
   {
       use HasFactory;
   
       protected $fillable = [
           'date',
           'open',
           'high',
           'low',
           'close',
           'volume',
           'ex_dividend',
           'split_ratio',
           'adj_open',
           'adj_high',
           'adj_low',
           'adj_close',
           'adj_volume'
       ];
   }
   ```

### Step 7: Create the database migration

1. In Visual Studio 2022, open the terminal.
2. Run the following command to generate a new database migration:
   ```
   php artisan make:migration create_stock_values_table --create=stock_values
   ```
3. Open the newly created migration file in the `database/migrations` directory.
4. Replace the code with the following:
   
   ```php
   <?php
   
   /**
   * MIT License
   * 
   * Copyright (c) 2023, Habibur Rahaman Fahim
   * 
   * Permission is hereby granted, free of charge, to any person obtaining a copy
   * of this software and associated documentation files (the "Software"), to deal
   * in the Software without restriction, including without limitation the rights
   * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
   * copies of the Software, and to permit persons to whom the Software is
   * furnished to do so, subject to the following conditions:
   * 
   * The above copyright notice and this permission notice shall be included in all
   * copies or substantial portions of the Software.
   * 
   * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
   * SOFTWARE.
   */
   
   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;
   
   class CreateStockValuesTable extends Migration
   {
       /**
        * Run the migrations.
        *
        * @return void
        */
       public function up()
       {
             Schema::create('stock_values', function (Blueprint $table) {
              $table->id();
              $table->date('date');
              $table->float('open');
              $table->float('high');
              $table->float('low');
              $table->float('close');
              $table->decimal('volume', 18, 2); // Adjust the precision and scale accordingly
              $table->float('ex_dividend');
              $table->float('split_ratio');
              $table->float('adj_open');
              $table->float('adj_high');
              $table->float('adj_low');
              $table->float('adj_close');
              $table->decimal('adj_volume', 18, 2); // Adjust the precision and scale accordingly
              $table->timestamps();
          });
       }
   
       /**
        * Reverse the migrations.
        *
        * @return void
        */
       public function down()
       {
           Schema::dropIfExists('stock_values');
       }
   }
   ```
6. Save the migration file.
7. Run the migration to create the `stock_values` table by running the following command in the terminal:
   ```
   php artisan migrate
   ```

### Step 8: Create the view

1. To include Chart.js in project open terminal and run the following command to install Chart.js using npm:
   ```
   npm install chart.js --save
   ```
2. In Visual Studio 2022, open the `resources/views` directory.
3. Create a new directory called `stock`.
4. Inside the `stock` directory, create a new
file called `index.blade.php`.
5. Replace the code in the `index.blade.php` file with the following:
   
   ```html
   <!DOCTYPE html>
    <html>
    
    <head>
        <title>Stock Website</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    
    <body>

       <!--
        The license for this code.
        Copyright (c) 2023, Habibur Rahaman Fahim
    
        Permission is hereby granted, free of charge, to any person obtaining a copy
        of this software and associated documentation files (the "Software"), to deal
        in the Software without restriction, including without limitation the rights
        to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
        copies of the Software, and to permit persons to whom the Software is
        furnished to do so, subject to the following conditions:
    
        The above copyright notice and this permission notice shall be included in all
        copies or substantial portions of the Software.
    
        THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
        IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
        FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
        AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
        LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
        OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
        SOFTWARE.
        -->
    
        <canvas id="stockChart"></canvas>
    
        <script>
            // Retrieve the stock data from PHP and format it for the chart
            var stockData = @json($data);
    
            var labels = stockData.map(function (stock) {
                return stock['Date'];
            });
    
            var closePrices = stockData.map(function (stock) {
                return parseFloat(stock['Close']);
            });
    
            // Create the chart
            var ctx = document.getElementById('stockChart').getContext('2d');
            var stockChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'AAPL Stock Prices',
                        data: closePrices,
                        borderColor: 'blue',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Price'
                            }
                        }
                    }
                }
            });
        </script>
    
        <table align="center">
            <tr>
                <th>Date</th>
                <th>Open</th>
                <th>High</th>
                <th>Low</th>
                <th>Close</th>
                <th>Volume</th>
            </tr>
            @foreach ($data as $stock)
            <tr>
                <td>{{ $stock['Date'] }}</td>
                <td>{{ $stock['Open'] }}</td>
                <td>{{ $stock['High'] }}</td>
                <td>{{ $stock['Low'] }}</td>
                <td>{{ $stock['Close'] }}</td>
                <td>{{ $stock['Volume'] }}</td>
            </tr>
            @endforeach
        </table>
    
        
    </body>
    
    </html>
    ```

### Step 9: Test the application

1. In Visual Studio 2022, open the terminal.
2. Run the following command to start the Laravel development server:
   ```
   php artisan serve
   ```
3. Open your web browser and visit `http://localhost:8000/stock`.
4. The stock values will be displayed in a table, and a line chart representing the closing prices will be shown above the table.

Finally, completed building a simple stock market website using Visual Studio 2022, Laravel, and PostgreSQL. The website fetches stock data from a CSV file, stores it in a PostgreSQL database, and displays the data in a table along with a line chart.

## License

The codes in this repository are licensed under the [MIT License](LICENSE).

