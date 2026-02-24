<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Printer Connection Type
    |--------------------------------------------------------------------------
    |
    | Supported: "network", "file", "windows"
    | - network: Connect via TCP/IP (IP address and port)
    | - file: Connect via file path (e.g., /dev/usb/lp0, LPT1)
    | - windows: Connect to Windows shared printer
    |
    */
    'connection' => env('PRINTER_CONNECTION', 'network'),

    /*
    |--------------------------------------------------------------------------
    | Network Printer Settings
    |--------------------------------------------------------------------------
    |
    | Settings for network (TCP/IP) printer connection.
    |
    */
    'ip' => env('PRINTER_IP', '192.168.1.100'),
    'port' => env('PRINTER_PORT', 9100),
    'timeout' => env('PRINTER_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | File/Windows Printer Settings
    |--------------------------------------------------------------------------
    |
    | Path for file connector or printer name for Windows shared printer.
    |
    */
    'path' => env('PRINTER_PATH', ''),

    /*
    |--------------------------------------------------------------------------
    | Receipt Settings
    |--------------------------------------------------------------------------
    |
    | Customize the receipt header and footer.
    |
    */
    'header' => env('PRINTER_HEADER', '126 Club'),
    'footer' => env('PRINTER_FOOTER', 'Thank you for your visit!'),
    'show_qr_code' => env('PRINTER_SHOW_QR', true),

    /*
    |--------------------------------------------------------------------------
    | Receipt Width
    |--------------------------------------------------------------------------
    |
    | Characters per line (typically 42 for 80mm paper, 32 for 58mm paper).
    |
    */
    'width' => env('PRINTER_WIDTH', 42),
];
