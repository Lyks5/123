<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Счет № {{ $order->id }}</title>
    <style>
        @media print {
            body {
                font-family: 'Arial', sans-serif;
                font-size: 12pt;
                line-height: 1.5;
                color: #000;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .logo {
                font-size: 24pt;
                font-weight: bold;
                margin-bottom: 10px;
            }
            .invoice-title {
                font-size: 18pt;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .invoice-details {
                margin-bottom: 20px;
            }
            .customer-details {
                margin-bottom: 20px;
            }
            .section-title {
                font-weight: bold;
                margin-bottom: 5px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th {
                background-color: #f0f0f0;
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            td {
                border: 1px solid #ddd;
                padding: 8px;
            }
            .totals {
                width: 300px;
                margin-left: auto;
            }
            .footer {
                margin-top: 50px;
                text-align: center;
                font-size: 10pt;
                color: #666;
            }
            .print-controls {
                display: none;
            }
        }
        
        /* Styles for screen view */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .customer-details {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .totals {
            width: 300px;
            margin-left: auto;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .print-controls {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .print-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="print-controls">
            <button class="print-btn" onclick="window.print()">Печать счета</button>
        </div>
        
        <div class="header">
            <div class="logo">ЭкоМаркет</div>
            <div class="invoice-title">СЧЕТ № {{ $order->id }}</div>
            <div>от {{ $order->created_at->format('d.m.Y') }}</div>
        </div>
        
        <div class="invoice-details">
            <div class="section-title">Информация о счете:</div>
            <div>Номер заказа: {{ $order->id }}</div>
            <div>Дата заказа: {{ $order->created_at->format('d.m.Y H:i') }}</div>
            <div>Способ оплаты: 
                @if($order->payment_method === 'credit_card')
                    Банковская карта
                @elseif($order->payment_method === 'paypal')
                    PayPal
                @elseif($order->payment_method === 'bank_transfer')
                    Банковский перевод
                @else
                    {{ $order->payment_method }}
                @endif
            </div>
        </div>
        
        <div class="customer-details">
            <div class="section-title">Покупатель:</div>
            <div>{{ $order->customer_name }}</div>
            <div>{{ $order->customer_email }}</div>
            <div>{{ $order->customer_phone }}</div>
            <div>{{ $order->shipping_address_line1 }}, {{ $order->shipping_city }}</div>
            <div>{{ $order->shipping_postal_code }}, {{ $order->shipping_country }}</div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Товар</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, '.', ' ') }} ₽</td>
                        <td>{{ number_format($item->subtotal, 0, '.', ' ') }} ₽</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="totals">
            <table>
                <tr>
                    <td>Промежуточный итог:</td>
                    <td>{{ number_format($order->subtotal, 0, '.', ' ') }} ₽</td>
                </tr>
                <tr>
                    <td>НДС (20%):</td>
                    <td>{{ number_format($order->tax_amount, 0, '.', ' ') }} ₽</td>
                </tr>
                <tr>
                    <td>Доставка:</td>
                    <td>{{ number_format($order->shipping_amount, 0, '.', ' ') }} ₽</td>
                </tr>
                @if($order->discount_amount > 0)
                    <tr>
                        <td>Скидка:</td>
                        <td>-{{ number_format($order->discount_amount, 0, '.', ' ') }} ₽</td>
                    </tr>
                @endif
                <tr>
                    <td><strong>ИТОГО К ОПЛАТЕ:</strong></td>
                    <td><strong>{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</strong></td>
                </tr>
            </table>
        </div>
        
        <div class="footer">
            <p>Спасибо за ваш заказ в ЭкоМаркет!</p>
            <p>Если у вас возникли вопросы, свяжитесь с нами по телефону 8-800-123-45-67 или по email: support@ecomarket.ru</p>
        </div>
    </div>
    
    <script>
        // Automatically open print dialog when page loads
        window.onload = function() {
            // Delay to ensure page is fully loaded
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>