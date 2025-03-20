<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Накладная № {{ $order->id }}</title>
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
            .packing-title {
                font-size: 18pt;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .order-details {
                margin-bottom: 20px;
            }
            .address-section {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
            }
            .address-box {
                width: 45%;
                border: 1px solid #ddd;
                padding: 10px;
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
            .signature-section {
                margin-top: 50px;
                display: flex;
                justify-content: space-between;
            }
            .signature-box {
                width: 45%;
            }
            .signature-line {
                border-top: 1px solid #000;
                margin-top: 40px;
                padding-top: 5px;
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
        .packing-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .address-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .address-box {
            width: 45%;
            border: 1px solid #ddd;
            padding: 10px;
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
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
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
            <button class="print-btn" onclick="window.print()">Печать накладной</button>
        </div>
        
        <div class="header">
            <div class="logo">ЭкоМаркет</div>
            <div class="packing-title">НАКЛАДНАЯ № {{ $order->id }}</div>
            <div>от {{ $order->created_at->format('d.m.Y') }}</div>
        </div>
        
        <div class="order-details">
            <div class="section-title">Информация о заказе:</div>
            <div>Номер заказа: {{ $order->id }}</div>
            <div>Дата заказа: {{ $order->created_at->format('d.m.Y H:i') }}</div>
            <div>Статус заказа: 
                @if($order->status === 'pending')
                    Ожидает обработки
                @elseif($order->status === 'processing')
                    В обработке
                @elseif($order->status === 'completed')
                    Завершен
                @elseif($order->status === 'cancelled')
                    Отменен
                @else
                    {{ $order->status }}
                @endif
            </div>
            <div>Способ доставки: 
                @if($order->shipping_method === 'standard')
                    Стандартная доставка
                @elseif($order->shipping_method === 'express')
                    Экспресс-доставка
                @elseif($order->shipping_method === 'pickup')
                    Самовывоз
                @else
                    {{ $order->shipping_method }}
                @endif
            </div>
        </div>
        
        <div class="address-section">
            <div class="address-box">
                <div class="section-title">Отправитель:</div>
                <div>ЭкоМаркет</div>
                <div>123456, г. Москва</div>
                <div>ул. Зеленая, д. 123</div>
                <div>Тел: 8-800-123-45-67</div>
                <div>Email: shipping@ecomarket.ru</div>
            </div>
            
            <div class="address-box">
                <div class="section-title">Получатель:</div>
                <div>{{ $order->customer_name }}</div>
                <div>{{ $order->shipping_address_line1 }}</div>
                @if($order->shipping_address_line2)
                    <div>{{ $order->shipping_address_line2 }}</div>
                @endif
                <div>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</div>
                <div>{{ $order->shipping_country }}</div>
                <div>Тел: {{ $order->customer_phone }}</div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Артикул</th>
                    <th>Наименование</th>
                    <th>Кол-во</th>
                    <th>Вес/объем</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->sku ?? 'N/A' }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->weight ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="signature-section">
            <div class="signature-box">
                <div>Товар отпустил:</div>
                <div class="signature-line">Подпись</div>
            </div>
            
            <div class="signature-box">
                <div>Товар получил:</div>
                <div class="signature-line">Подпись</div>
            </div>
        </div>
        
        <div class="footer">
            <p>Накладная № {{ $order->id }} от {{ $order->created_at->format('d.m.Y') }}</p>
            <p>ЭкоМаркет - 123456, г. Москва, ул. Зеленая, д. 123</p>
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